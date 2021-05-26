from flask import Flask, request , render_template, jsonify
from flask_cors import CORS
import mysql.connector
from mysql.connector import Error
import json


app = Flask(__name__)
CORS(app)
connection = mysql.connector.connect(host='localhost',
                                     user='root',
                                     password='',
                                     database='floraplant'
                                    )
cursor = connection.cursor(dictionary=True)


@app.route('/LogInWorker/', methods=['POST'])
def LogInWorker():
    if request.method == "POST":
        try:
            inData = request.get_json()
            attempted_id_subprocess = inData['id_subproceso']
            attempted_user = inData['usuario']
            attempted_password = inData['contraseña']
            query = f" SELECT trabajador.id FROM trabajador WHERE trabajador.usuario = '{attempted_user}' AND trabajador.contraseña = '{attempted_password}';"
            cursor.execute(query)
            result = cursor.fetchall()
            id_trabajador = str(result[0]['id'])
            query = f"INSERT INTO registro (id_trabajador, id_subproceso) VALUES ({id_trabajador}, {attempted_id_subprocess});"
            cursor.execute(query)
            query = f"SELECT trabajador.tipo FROM trabajador WHERE trabajador.id = '{id_trabajador}';"
            cursor.execute(query)
            result = cursor.fetchall()
            tipe = str(result[0]['tipo'])
            result = {"id_trabajador":id_trabajador, "id_subproceso":attempted_id_subprocess, "tipo":tipe}
            result = json.dumps(result)
        except:
            return  {"id_trabajador":-1, "id_subproceso":-1, "tipo":"Error"}
    else:
        result = {"id_trabajador":id_trabajador, "id_subproceso":attempted_id_subprocess, "tipo":"Error"}
    return result


@app.route('/ReadSubprocess/', methods=['GET'])
def ReadSubprocess():
    if request.method == "GET":
        cursor.execute("SELECT * FROM subproceso;")
        result = cursor.fetchall()
        return json.dumps(result)
    else:
        return False


@app.route('/ReadCheckpoints/', methods=['GET'])
def ReadCheckpoints():
    if request.method == "GET":
        id_subproceso = request.args.get('id_subproceso')
        id_trabajador = request.args.get('id_trabajador')
        query = f"""
            SELECT punto_de_control.id,  orden.orden, orden.descripcion, actividad.estado FROM orden
                INNER JOIN punto_de_control on orden.id = punto_de_control.id_orden
                INNER JOIN actividad on actividad.id_punto_de_control = punto_de_control.id  AND actividad.id_punto_de_control NOT IN (SELECT actividad.id_punto_de_control FROM actividad WHERE actividad.estado = 'Terminado') 
            WHERE punto_de_control.id_subproceso = {id_subproceso} AND (actividad.id_trabajador IS NULL OR (actividad.id_trabajador = {id_trabajador} AND actividad.estado = 'En proceso'))
            ORDER BY actividad.estado ASC;
        """
        cursor.execute(query)
        result = cursor.fetchall()
        return json.dumps(result)
    else:
        return False


@app.route('/StartActivity/', methods=['POST'])
def StartActivity():
    if request.method == "POST":
        inData = request.get_json()
        attempted_id_checkpoint = inData['id_punto_de_control']
        attempted_id_worker = inData['id_trabajador']
        query = f" UPDATE actividad SET actividad.id_trabajador = {attempted_id_worker} WHERE actividad.id_punto_de_control = {attempted_id_checkpoint};"
        cursor.execute(query)
        query = f"INSERT INTO actividad (id_punto_de_control,id_trabajador, estado) VALUES ({attempted_id_checkpoint},{attempted_id_worker},'En proceso');"
        cursor.execute(query)
        result = {"Success": True }
    else:
        result = {"Success": False }
    return result

@app.route('/EndActivity/', methods=['POST'])
def EndActivity():
    if request.method == "POST":
        inData = request.get_json()
        attempted_id_checkpoint = inData['id_punto_de_control']
        attempted_id_worker = inData['id_trabajador']
        query = f"INSERT INTO actividad (id_punto_de_control,id_trabajador, estado) VALUES ({attempted_id_checkpoint},{attempted_id_worker},'Terminado');"
        cursor.execute(query)
        query = f"SELECT id_subproceso, id_orden FROM punto_de_control WHERE punto_de_control.id = {attempted_id_checkpoint};"
        cursor.execute(query)
        result = cursor.fetchall()
        nid_subproceso, nid_orden = str(result[0]['id_subproceso']), str(result[0]['id_orden'])
        query = f"INSERT INTO punto_de_control (id_orden, id_subproceso) VALUES ({nid_orden}, ({nid_subproceso}+1));"
        cursor.execute(query)
        result = True
    else:
        result = False
    return json.dumps(result)


@app.route('/GetDataRegitry/', methods=['GET'])
def GetDataRegitry():
    if request.method == "GET":
        id_subproceso = request.args.get('id_subproceso')
        id_trabajador = request.args.get('id_trabajador')
        query = f"SELECT subproceso.subproceso, trabajador.nombre FROM subproceso,trabajador WHERE subproceso.id = {id_subproceso} and trabajador.id = {id_trabajador};"
        cursor.execute(query)
        result = cursor.fetchall()
        result = {"subproceso":str(result[0]['subproceso']), "nombre":str(result[0]['nombre'])}
        result = json.dumps(result)
    else:
        result = "Error in FloraPlant server API"
    return result



@app.route('/CheckNotifications/', methods=['GET'])
def CheckNotifications():
    if request.method == "GET":
        id_subproceso = request.args.get('id_subproceso')
        query = f"""
           SELECT notificacion.id, orden.descripcion, orden.orden, subproceso.subproceso FROM notificacion
                INNER JOIN actividad ON notificacion.id_actividad = actividad.id
                INNER JOIN punto_de_control ON actividad.id_punto_de_control = punto_de_control.id
                INNER JOIN orden ON punto_de_control.id_orden = orden.id
                INNER JOIN subproceso ON punto_de_control.id_subproceso = subproceso.id
            WHERE punto_de_control.id_subproceso = {id_subproceso} and notificacion.visto = {False};
        """
        cursor.execute(query)
        result = cursor.fetchall()
        return json.dumps(result)
    else:
        return json.dumps(result)

@app.route('/ViewNotification/', methods=['GET'])
def ViewNotification():
    result = None
    if request.method == "GET":
        attempted_id_notification = request.args.get('id_notificacion')
        query = f"UPDATE notificacion SET notificacion.visto = true WHERE notificacion.id = {attempted_id_notification};"
        cursor.execute(query)
        result = True
    return json.dumps(result)

@app.route('/CheckWorkers/', methods=['GET'])
def CheckWorkers():
    result = None
    if request.method == "GET":
        query = f"SELECT trabajador.id, trabajador.nombre FROM trabajador WHERE trabajador.tipo <> 'administrador' "
        cursor.execute(query)
        result = cursor.fetchall()
    return json.dumps(result)


@app.route('/AddNewOrder/', methods=['POST'])
def AddNewOrder():
    result = None
    if request.method == "POST":
        inData = request.get_json()
        order = inData['orden']
        description = inData['descripcion']
        address = inData['direccion']
        price = inData['precio']
        query = f"INSERT INTO orden (orden, descripcion, direccion, precio) VALUES ('{order}','{description}','{address}',{price});"
        cursor.execute(query)
        result = True
    else:
        result = False
    return json.dumps(result)






















































if __name__ == "__main__": 
    app.run()


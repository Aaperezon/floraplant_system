-- SQL PARA CREAR LA BD
DROP DATABASE IF EXISTS floraplant;
CREATE DATABASE floraplant;
USE floraplant;


DROP TABLE IF EXISTS trabajador;
CREATE TABLE trabajador(
	id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    usuario VARCHAR(255) NOT NULL,
    contraseña TEXT NOT NULL,
    nombre VARCHAR(30) NOT NULL,
    tipo VARCHAR(30) NOT NULL DEFAULT('trabajador')
);
-- SELECT * FROM trabajador;


DROP TABLE IF EXISTS orden;
CREATE TABLE orden(
	id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    orden VARCHAR(30) NOT NULL,
    descripcion TEXT,
    direccion TEXT,
    precio float
);
-- SELECT * FROM orden;



DROP TABLE IF EXISTS subproceso;
CREATE TABLE subproceso(
	id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    subproceso VARCHAR(30) NOT NULL
);

-- SELECT * FROM subproceso;



DROP TABLE IF EXISTS punto_de_control;
CREATE TABLE punto_de_control(
	id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
	id_orden INT NOT NULL,
	id_subproceso INT NOT NULL DEFAULT 3,
	FOREIGN KEY (id_orden) REFERENCES orden(id),	
    FOREIGN KEY (id_subproceso) REFERENCES subproceso(id)

);
-- INSERT INTO punto_de_control (id_subproceso, nombre) VALUES (1,'Aaron Perez');
-- SELECT * FROM punto_de_control;



DROP TABLE IF EXISTS registro;
CREATE TABLE registro(
	id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
	id_trabajador INT NOT NULL,
	id_subproceso INT NOT NULL,
    ts TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (id_trabajador) REFERENCES trabajador(id),
	FOREIGN KEY (id_subproceso) REFERENCES subproceso(id)
);
-- INSERT INTO trabajador (id_subproceso, nombre) VALUES (1,'Aaron Perez');
-- SELECT * FROM trabajador;



DROP TABLE IF EXISTS actividad;
CREATE TABLE actividad(
	id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
	id_punto_de_control INT NOT NULL,
	id_trabajador INT,
    ts TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado VARCHAR(30) NOT NULL DEFAULT 'Inicio',
	FOREIGN KEY (id_punto_de_control) REFERENCES punto_de_control(id),
	FOREIGN KEY (id_trabajador) REFERENCES trabajador(id)
);
-- INSERT INTO actividad (id_punto_de_control, id_trabajador) VALUES (1,1);
-- SELECT * FROM actividad;


DROP TABLE IF EXISTS notificacion;
CREATE TABLE notificacion(
	id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
	id_actividad INT NOT NULL,
	visto BOOLEAN NOT NULL DEFAULT(false),
	FOREIGN KEY (id_actividad) REFERENCES actividad(id)
);
-- INSERT INTO notificacion (id_actividad) VALUES (1);
-- SELECT * FROM notificacion;







-- ===================== STORED PROCEDURES IF USED BAD IMPLEMENTATION==============================--
DROP PROCEDURE IF EXISTS InicioSesionTrabajador;
DELIMITER //
CREATE PROCEDURE InicioSesionTrabajador(id_subproceso INT, usuario VARCHAR(255), contraseña TEXT)
BEGIN
	DECLARE id_trabajador INT;
	SELECT trabajador.id INTO id_trabajador FROM trabajador WHERE trabajador.usuario = usuario AND trabajador.contraseña = contraseña;
	INSERT INTO registro (id_trabajador, id_subproceso) VALUES (id_trabajador, id_subproceso);
	SELECT id_trabajador, id_subproceso;
END //
DELIMITER ;
-- CALL InicioSesionTrabajador(2,'aaron',"aaron");




DROP PROCEDURE IF EXISTS LeerSubprocesos;
DELIMITER //
CREATE PROCEDURE LeerSubprocesos()
BEGIN
	SELECT * FROM subproceso;
END //
DELIMITER ;
-- CALL LeerSubprocesos;

DROP PROCEDURE IF EXISTS VerPuntosDeControl;
DELIMITER //
CREATE PROCEDURE VerPuntosDeControl(id_subproceso INT, id_trabajador INT)
BEGIN
	SELECT punto_de_control.id,  orden.orden, orden.descripcion, actividad.estado FROM orden
		INNER JOIN punto_de_control on orden.id = punto_de_control.id_orden
		INNER JOIN actividad on actividad.id_punto_de_control = punto_de_control.id  AND actividad.id_punto_de_control NOT IN (SELECT actividad.id_punto_de_control FROM actividad WHERE actividad.estado = 'Terminado') 
	WHERE punto_de_control.id_subproceso = id_subproceso AND (actividad.id_trabajador IS NULL OR (actividad.id_trabajador = id_trabajador AND actividad.estado = 'En proceso'))
    ORDER BY actividad.estado ASC;
 
END //
DELIMITER ;
-- CALL VerPuntosDeControl(5,1);
-- SELECT * FROM actividad;
-- SELECT * FROM punto_de_control;
-- SELECT * FROM orden;


DROP PROCEDURE IF EXISTS EmpezarActividad;
DELIMITER //
CREATE PROCEDURE EmpezarActividad(id_punto_de_control INT, id_trabajador INT)
BEGIN
	UPDATE actividad SET actividad.id_trabajador = id_trabajador WHERE actividad.id_punto_de_control = id_punto_de_control;
    INSERT INTO actividad (id_punto_de_control,id_trabajador, estado) VALUES (id_punto_de_control,id_trabajador,'En proceso');
END //
DELIMITER ;
-- CALL EmpezarActividad(4,3);
-- SELECT * FROM actividad;


DROP PROCEDURE IF EXISTS TerminarActividad;
DELIMITER //
CREATE PROCEDURE TerminarActividad(id_punto_de_control INT, id_trabajador INT)
BEGIN
	DECLARE nid_subproceso INT;
	DECLARE nid_orden INT;
    INSERT INTO actividad (id_punto_de_control,id_trabajador, estado) VALUES (id_punto_de_control,id_trabajador,'Terminado');
    SELECT id_subproceso, id_orden INTO nid_subproceso, nid_orden FROM punto_de_control WHERE punto_de_control.id = id_punto_de_control;
    INSERT INTO punto_de_control (id_orden, id_subproceso) VALUES (nid_orden, (nid_subproceso+1));
END //
DELIMITER ;
--  CALL EmpezarActividad(10,1);
--  
--  CALL TerminarActividad(10,1);
--   SELECT * FROM actividad;
--  SELECT * FROM punto_de_control;
--  SELECT * FROM notificacion

DROP PROCEDURE IF EXISTS ObtenerDatosDeRegistro;
DELIMITER //
CREATE PROCEDURE ObtenerDatosDeRegistro(id_trabajador INT, id_subproceso INT)
BEGIN
	SELECT subproceso.subproceso, trabajador.nombre FROM subproceso,trabajador WHERE subproceso.id = id_subproceso and trabajador.id = id_trabajador;
END //
DELIMITER ;
-- CALL ObtenerDatosDeRegistro(1,1);



DROP PROCEDURE IF EXISTS RevisarNotificaciones;
DELIMITER //
CREATE PROCEDURE RevisarNotificaciones(id_subproceso INT)
BEGIN
	SELECT notificacion.id, orden.descripcion, orden.orden, subproceso.subproceso FROM notificacion
		INNER JOIN actividad ON notificacion.id_actividad = actividad.id
        INNER JOIN punto_de_control ON actividad.id_punto_de_control = punto_de_control.id
        INNER JOIN orden ON punto_de_control.id_orden = orden.id
        INNER JOIN subproceso ON punto_de_control.id_subproceso = subproceso.id
	WHERE punto_de_control.id_subproceso = id_subproceso and notificacion.visto = false;

END //
DELIMITER ;
-- CALL RevisarNotificaciones(3);


DROP PROCEDURE IF EXISTS VistoNotificacion;
DELIMITER //
CREATE PROCEDURE VistoNotificacion(id_notificacion INT)
BEGIN
	UPDATE notificacion SET notificacion.visto = true WHERE notificacion.id = id_notificacion;
END //
DELIMITER ;
-- CALL VistoNotificacion(2);

DROP PROCEDURE IF EXISTS AgregarOrden;
DELIMITER //
CREATE PROCEDURE AgregarOrden(orden TEXT, descripcion TEXT, direccion TEXT, precio FLOAT)
BEGIN
	INSERT INTO orden (orden, descripcion, direccion, precio) VALUES (orden,descripcion,direccion,precio);
    
END //
DELIMITER ;
-- CALL AgregarOrden("nuevaorden","la nueva desccripcion insertada","la nueva direccion insertada",12332);
SELECT * FROM orden;


-- ============================= TRIGGERS =============================== --
DROP TRIGGER IF EXISTS LlenarPuntoDeControlPorCadaOrdenNueva;
DELIMITER //
CREATE TRIGGER LlenarPuntoDeControlPorCadaOrdenNueva
AFTER INSERT ON orden
FOR EACH ROW
BEGIN
	INSERT INTO punto_de_control (id_orden) VALUES (new.id);
END;//
DELIMITER ;

DROP TRIGGER IF EXISTS LlenarActividad;
DELIMITER //
CREATE TRIGGER LlenarActividad
AFTER INSERT ON punto_de_control
FOR EACH ROW
BEGIN
	INSERT INTO actividad(id_punto_de_control) VALUES (new.id);
END;//
DELIMITER ;


DROP TRIGGER IF EXISTS CrearNotificacion;
DELIMITER //
CREATE TRIGGER CrearNotificacion
AFTER INSERT ON actividad
FOR EACH ROW
BEGIN
-- select * from actividad
-- select * from notificacion
	IF new.estado = "Inicio" THEN
		BEGIN
			INSERT INTO notificacion(id_actividad) VALUES (new.id);
		END;
    END IF;
END;//
DELIMITER ;



-- ================== INSERCIONES EN TABLAS PRUEBAS=============================== --

INSERT INTO trabajador(usuario, contraseña,nombre) VALUES
('diana','diana','Diana'),
('luis','luis','Luis'),
('pepe','pepe','Pepe');
INSERT INTO trabajador(usuario, contraseña,nombre,tipo) VALUES
('aaron','aaron','Aaron','administrador');
INSERT INTO trabajador(usuario, contraseña,nombre,tipo) VALUES
('sergio','sergio','Sergio','operador');

INSERT INTO subproceso(subproceso)VALUES
('Administrador'),
('Operador'),
('Recolección'),
('Empaquetamiento'),
('Inspección');


INSERT INTO orden (orden, descripcion, direccion, precio)VALUES
('O.No.1','Esta es una descripcion con 3 orquideas','En la calle de las lomas No 1',120.60),
('O.No.2','Esta es una descripcion con 2 orquideas','En la calle de las lomas No 2',120.60),
('O.No.3','Esta es una descripcion con 1 orquidea','En la calle de las lomas No 3',120.60),
('O.No.4','Esta es una descripcion con 5 orquideas','En la calle de las lomas No 4',120.60),
('O.No.5','Esta es una descripcion con 1 orquidea','En la calle de las lomas No 5',322.60),
('O.No.6','Esta es una descripcion con 1 rosa','En la calle de las lomas No 6',120.60),
('O.No.7','Esta es una descripcion con 4 rosas','En la calle de las lomas No 7',633.60),
('O.No.8','Esta es una descripcion con 4 anturios','En la calle de las lomas No 8',120.60),
('O.No.9','Esta es una descripcion con 2 tulipanes','En la calle de las lomas No 9',120.60);
-- INSERT INTO orden (orden, descripcion, direccion)VALUES ('O.No.1','BorrarDespues','En la calle de las lomas No 1');


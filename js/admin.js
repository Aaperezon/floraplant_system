let load = () => {
    let timeFormat = 'DD/MM/YYYY';
    let contador = 0

    let config = {
        type:    'line',
        data:    {
            datasets: [
                {
                    label: "Pedidos trabajados",
                    data: [{
                        x: "19/01/2021", y: contador
                    }, {
                        x: "18/01/2021", y: Math.random(1,8)
                    }, {
                        x: "17/01/2021", y: Math.random(1,8)
                    }, {
                        x: "16/01/2021", y: Math.random(1,8)
                    },{
                        x: "15/01/2021", y: Math.random(1,8)
                    },{
                        x: "14/01/2021", y: Math.random(1,8)
                    },{
                        x: "13/01/2021", y: Math.random(1,8)
                    },{
                        x: "12/01/2021", y: Math.random(1,8)
                    }],
                    fill: true,
                    borderColor: 'red'
                }
            ]
        },
        options: {
            responsive: true,
            title:      {
                display: true,
                text:    "Chart.js Time Scale"
            },
            scales:     {
                xAxes: [{
                    type:       "time",
                    time:       {
                        format: timeFormat,
                        tooltipFormat: 'll'
                    },
                    scaleLabel: {
                        display:     true,
                        labelString: 'Date'
                    }
                }],
                yAxes: [{
                    scaleLabel: {
                        display:     true,
                        labelString: 'value'
                    }
                }]
            }
        }
    };
    


    let ctx = document.getElementById("canvas").getContext("2d");
    myLine = new Chart(ctx, config);


    let dateText = document.getElementById("date")
    let date = new Date()
    let day = date.getDate()
    let month = date.getMonth()
    let year = date.getFullYear()
    dateText.appendChild(document.createTextNode(String(day)+'-'+String(month)+'-'+String(year)))


    let settingsBtn = document.getElementById("settingsBtn")
    settingsBtn.addEventListener("click", function(){
        $("#principalColContainer1").attr('disabled', 'disabled')
        $('#principalColContainer2 :input').removeAttr('disabled');
        console.log("presionado")

    });



}
addEventListener("DOMContentLoaded", load)

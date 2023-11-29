<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App-Clima</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
    <link rel="stylesheet" href="style.css">

</head>
	<style>
		*{
			margin: 0rem;
			padding: 0rem;
			box-sizing: border-box;
			font-family: sans-serif;
			color: white;
		}

		main{
			display: flex;
			justify-content: center;
		}

		#grafico-container{
			width: 300px;
			height: 400px;
			border: solid 1px silver;
			border-radius: 8px;
		}
		button{
			background: #4a334a;
    padding: 20px;
    border: none;
    border-radius: 5px;
    font-size: 20pt;
    font-family: monospace;
    box-shadow: #02020247 15px 15px 11px -5px;
		}
		button:hover{
			background-color: #302030;
			transform: scale(1.1);
		}
	</style>

<body>

    <div class="wrapper">
    	<div class="datos_del_grafico" style="
    display: grid;
">
    		<span class="query_fecha">
    			
    		</span>
    		<span class="query_ubicacion">
    			
    		</span>
    		<span class="query_temperatura">
    			
    		</span>
    		<span class="query_sensacion">
    			
    		</span>
    		<span class="query_temperatura_maxima">
    			
    		</span>
    		<span class="query_temperatura_minima">
    			
    		</span>
    		<span class="query_sensacion_maxima">
    			
    		</span>
    		<span class="query_sensacion_minima">
    			
    		</span>
    		<span class="query_humedad">
    			
    		</span>
    		<span class="query_veleta">
    			
    		</span>
    		<span class="query_viento">
    			
    		</span>
    		<span class="query_viento_maximo">
    			
    		</span>

    		<span class="ffmc"></span>
    		<span class="dmc"></span>
    		<span class="dc"></span>
    		<span class="isi"></span>
    		<span class="bui"></span>
    		<span class="fwi"></span>
    	</div>
    	<main>
			<!-- Contenedor del gráfico -->
			<div id="grafico-container">
				<canvas id="grafico-dolar" style="width: 100%; height: 100%;"></canvas>
			</div>

		</main>

    	<button id="boton_de_temperatura">
    		0°C
    	</button>
    	<button id="boton_de_humedad">
    		0%
    	</button>
    	<button id="boton_de_viento">
    		0 Km/H
    	</button>
    	<button id="boton_de_presion">
    		0 hPa
    	</button>
    	<button id="boton_de_fuego">
    		0 hPa
    	</button>
    </div>
</body>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
	let chipid = <?php echo $_GET["chipid"]; ?> 
	async function refreshDatos(cantfilas){
		const response = await fetch("https://mattprofe.com.ar/proyectos/app-estacion/datos.php?chipid=" + chipid + "&cant=" + cantfilas)
		const data = await response.json()
		return data
	}
	function CargarDatos(datos){
		boton_de_temperatura.textContent = datos[0].temperatura + " °C";
		boton_de_humedad.textContent = datos[0].humedad + "%";
		boton_de_viento.textContent = datos[0].viento + "Km/H";
		boton_de_presion.textContent = datos[0].presion + "hPa";
		boton_de_fuego.textContent = fireDanger(datos[0].fwi);
		CargarGraficoTemperatura()
	}
	function CargarGraficoTemperatura(){
		refreshDatos(6).then(
			datos => {
				document.getElementById("grafico-container").innerHTML = '<canvas id="grafico-dolar" style="width: 100%; height: 100%;"></canvas>';
				const valores = {
					labels: [],
					datasets: [{
						label: '', // detalle de la linea graficada
						backgroundColor: 'rgb(25, 174, 49)', // color circulo
						borderColor: 'rgb(25, 174, 49)', // color linea
						data: [] // valores a graficar
					}]
				}
				// Opciones generales del gráfico
				const options = {
					indexAxis: 'x', // orden de los ejes del gráfico
					plugins: {
						title: { 
							display: false, // mostrar el titulo
							text: '', // texto del titulo
							font: {
								size: 30 // Tamaño del título
							}
						}
					},
					animation: {
							duration: 0
					},
					responsive: true,
					responsiveAnimationDuration: 0,
				}
				datos.forEach(elements => {
					valores.datasets[0].data.push(elements.temperatura)
					valores.labels.push(elements.fecha.split(" ")[1].slice(0,5))
				})
				// Información con la cual se genera el gráfico
				const config = {
					type: 'line',
					data: valores,
					options: options
				}
				// creación del gráfico dentro del canvas
				let grafico = new Chart(document.querySelector("#grafico-dolar"), config)

				document.querySelector(".query_fecha").textContent = datos[0].fecha
				document.querySelector(".query_ubicacion").textContent = datos[0].ubicacion
				document.querySelector(".query_temperatura").textContent = "Temperatura: " + datos[0].temperatura + "°C"
				document.querySelector(".query_temperatura_minima").textContent = "Minima: " + datos[0].tempmin+ "°C"
				document.querySelector(".query_temperatura_maxima").textContent = "Maxima: "+ datos[0].tempmax+ "°C"
				document.querySelector(".query_sensacion").textContent = "Sensación: " + datos[0].sensacion+ "°C"
				document.querySelector(".query_sensacion_minima").textContent = "Minima: "+ datos[0].sensamin+ "°C"
				document.querySelector(".query_sensacion_maxima").textContent = "Maxima: "+datos[0].sensamax+ "°C"
				document.querySelector(".query_humedad").textContent = ""
				document.querySelector(".query_viento").textContent =""
				document.querySelector(".query_viento_maximo").textContent = ""
				document.querySelector(".query_veleta").textContent = ""
				document.querySelector(".ffmc").textContent = ""
				document.querySelector(".dmc").textContent =""
				document.querySelector(".dc").textContent =""
				document.querySelector(".isi").textContent =""
				document.querySelector(".bui").textContent =""
				document.querySelector(".fwi").textContent =""
			}
		);
	}

	function CargarGraficoHumedad(){
		refreshDatos(6).then(
			datos => {
				document.getElementById("grafico-container").innerHTML = '<canvas id="grafico-dolar" style="width: 100%; height: 100%;"></canvas>';
				const valores = {
					labels: [],
					datasets: [{
						label: '', // detalle de la linea graficada
						backgroundColor: 'rgb(25, 174, 49)', // color circulo
						borderColor: 'rgb(25, 174, 49)', // color linea
						data: [] // valores a graficar
					}]
				}
				// Opciones generales del gráfico
				const options = {
					indexAxis: 'x', // orden de los ejes del gráfico
					plugins: {
						title: { 
							display: false, // mostrar el titulo
							text: '', // texto del titulo
							font: {
								size: 30 // Tamaño del título
							}
						}
					},
					animation: {
							duration: 0
					},
					responsive: true,
					responsiveAnimationDuration: 0,
				}
				datos.forEach(elements => {
					valores.datasets[0].data.push(elements.humedad)
					valores.labels.push(elements.fecha.split(" ")[1].slice(0,5))
				})
				// Información con la cual se genera el gráfico
				const config = {
					type: 'line',
					data: valores,
					options: options
				}
				// creación del gráfico dentro del canvas
				let grafico = new Chart(document.querySelector("#grafico-dolar"), config)

				document.querySelector(".query_fecha").textContent = datos[0].fecha
				document.querySelector(".query_ubicacion").textContent = datos[0].ubicacion
				document.querySelector(".query_temperatura").textContent = ""
				document.querySelector(".query_temperatura_minima").textContent = ""
				document.querySelector(".query_temperatura_maxima").textContent = ""
				document.querySelector(".query_sensacion").textContent = ""
				document.querySelector(".query_sensacion_minima").textContent = ""
				document.querySelector(".query_sensacion_maxima").textContent = ""
				document.querySelector(".query_humedad").textContent = "Humedad: " + datos[0].humedad + "%"
				document.querySelector(".query_viento").textContent =""
				document.querySelector(".query_viento_maximo").textContent = ""
				document.querySelector(".query_veleta").textContent = ""
				document.querySelector(".ffmc").textContent = ""
				document.querySelector(".dmc").textContent =""
				document.querySelector(".dc").textContent =""
				document.querySelector(".isi").textContent =""
				document.querySelector(".bui").textContent =""
				document.querySelector(".fwi").textContent =""
			}
		);
	}
	function CargarGraficoViento(){
		refreshDatos(6).then(
			datos => {
				document.getElementById("grafico-container").innerHTML = '<canvas id="grafico-dolar" style="width: 100%; height: 100%;"></canvas>';
				const valores = {
					labels: [],
					datasets: [{
						label: '', // detalle de la linea graficada
						backgroundColor: 'rgb(25, 174, 49)', // color circulo
						borderColor: 'rgb(25, 174, 49)', // color linea
						data: [] // valores a graficar
					}]
				}
				// Opciones generales del gráfico
				const options = {
					indexAxis: 'x', // orden de los ejes del gráfico
					plugins: {
						title: { 
							display: false, // mostrar el titulo
							text: '', // texto del titulo
							font: {
								size: 30 // Tamaño del título
							}
						}
					},
					animation: {
							duration: 0
					},
					responsive: true,
					responsiveAnimationDuration: 0,
				}
				datos.forEach(elements => {
					valores.datasets[0].data.push(elements.viento)
					valores.labels.push(elements.fecha.split(" ")[1].slice(0,5))
				})
				// Información con la cual se genera el gráfico
				const config = {
					type: 'line',
					data: valores,
					options: options
				}
				// creación del gráfico dentro del canvas
				let grafico = new Chart(document.querySelector("#grafico-dolar"), config)

				document.querySelector(".query_fecha").textContent = datos[0].fecha
				document.querySelector(".query_ubicacion").textContent = datos[0].ubicacion
				document.querySelector(".query_temperatura").textContent = ""
				document.querySelector(".query_temperatura_minima").textContent = ""
				document.querySelector(".query_temperatura_maxima").textContent = ""
				document.querySelector(".query_sensacion").textContent = ""
				document.querySelector(".query_sensacion_minima").textContent = ""
				document.querySelector(".query_sensacion_maxima").textContent = ""
				document.querySelector(".query_humedad").textContent = ""
				document.querySelector(".query_viento").textContent = "Viento: " + datos[0].viento
				document.querySelector(".query_viento_maximo").textContent = "Maximo: "+datos[0].maxviento
				document.querySelector(".query_veleta").textContent = "Veleta: " + datos[0].veleta
				document.querySelector(".ffmc").textContent = ""
				document.querySelector(".dmc").textContent =""
				document.querySelector(".dc").textContent =""
				document.querySelector(".isi").textContent =""
				document.querySelector(".bui").textContent =""
				document.querySelector(".fwi").textContent =""
			}
		);
	}
	function CargarGraficoPresion(){
		refreshDatos(6).then(
			datos => {
				document.getElementById("grafico-container").innerHTML = '<canvas id="grafico-dolar" style="width: 100%; height: 100%;"></canvas>';
				const valores = {
					labels: [],
					datasets: [{
						label: '', // detalle de la linea graficada
						backgroundColor: 'rgb(25, 174, 49)', // color circulo
						borderColor: 'rgb(25, 174, 49)', // color linea
						data: [] // valores a graficar
					}]
				}
				// Opciones generales del gráfico
				const options = {
					indexAxis: 'x', // orden de los ejes del gráfico
					plugins: {
						title: { 
							display: false, // mostrar el titulo
							text: '', // texto del titulo
							font: {
								size: 30 // Tamaño del título
							}
						}
					},
					animation: {
							duration: 0
					},
					responsive: true,
					responsiveAnimationDuration: 0,
				}
				datos.forEach(elements => {
					valores.datasets[0].data.push(elements.presion)
					valores.labels.push(elements.fecha.split(" ")[1].slice(0,5))
				})
				// Información con la cual se genera el gráfico
				const config = {
					type: 'line',
					data: valores,
					options: options
				}
				// creación del gráfico dentro del canvas
				let grafico = new Chart(document.querySelector("#grafico-dolar"), config)

				document.querySelector(".query_fecha").textContent = datos[0].fecha
				document.querySelector(".query_ubicacion").textContent = datos[0].ubicacion
				document.querySelector(".query_temperatura").textContent = ""
				document.querySelector(".query_temperatura_minima").textContent = ""
				document.querySelector(".query_temperatura_maxima").textContent = ""
				document.querySelector(".query_sensacion").textContent = ""
				document.querySelector(".query_sensacion_minima").textContent = ""
				document.querySelector(".query_sensacion_maxima").textContent = ""
				document.querySelector(".query_humedad").textContent = ""
				document.querySelector(".query_viento").textContent = "Presion: "+ datos[0].presion + " hPA"
				document.querySelector(".query_viento_maximo").textContent = ""
				document.querySelector(".query_veleta").textContent = ""
				document.querySelector(".ffmc").textContent = ""
				document.querySelector(".dmc").textContent =""
				document.querySelector(".dc").textContent =""
				document.querySelector(".isi").textContent =""
				document.querySelector(".bui").textContent =""
				document.querySelector(".fwi").textContent =""
			}
		);
	}
		function CargarGraficoFuego(){
		refreshDatos(6).then(
			datos => {
				document.getElementById("grafico-container").innerHTML = '<canvas id="grafico-dolar" style="width: 100%; height: 100%;"></canvas>';
				const valores = {
					labels: [],
					datasets: [{
						label: '', // detalle de la linea graficada
						backgroundColor: 'rgb(25, 174, 49)', // color circulo
						borderColor: 'rgb(25, 174, 49)', // color linea
						data: [] // valores a graficar
					}]
				}
				// Opciones generales del gráfico
				const options = {
					indexAxis: 'y', // orden de los ejes del gráfico
					plugins: {
						title: { 
							display: false, // mostrar el titulo
							text: '', // texto del titulo
							font: {
								size: 30 // Tamaño del título
							}
						}
					},
					animation: {
							duration: 0
					},
					responsive: true,
					responsiveAnimationDuration: 0,
				}
				datos.forEach(elements => {
					valores.datasets[0].data.push(elements.fwi)
					valores.labels.push(elements.fecha.split(" ")[1].slice(0,5))
				})
				valores.datasets[0].data.reverse();
				valores.labels.reverse();
				// Información con la cual se genera el gráfico
				const config = {
					type: 'line',
					data: valores,
					options: options
				}
				// creación del gráfico dentro del canvas
				let grafico = new Chart(document.querySelector("#grafico-dolar"), config)

				document.querySelector(".query_fecha").textContent = datos[0].fecha
				document.querySelector(".query_ubicacion").textContent = datos[0].ubicacion
				document.querySelector(".query_temperatura").textContent = ""
				document.querySelector(".query_temperatura_minima").textContent = ""
				document.querySelector(".query_temperatura_maxima").textContent = ""
				document.querySelector(".query_sensacion").textContent = ""
				document.querySelector(".query_sensacion_minima").textContent = ""
				document.querySelector(".query_sensacion_maxima").textContent = ""
				document.querySelector(".query_humedad").textContent = ""
				document.querySelector(".query_viento").textContent = ""
				document.querySelector(".query_viento_maximo").textContent = ""
				document.querySelector(".query_veleta").textContent = ""
				document.querySelector(".ffmc").textContent ="FFMC: "+datos[0].ffmc;
				document.querySelector(".dmc").textContent ="DMC: "+datos[0].dmc;
				document.querySelector(".dc").textContent ="DC: "+datos[0].dc;
				document.querySelector(".isi").textContent ="ISI: "+datos[0].isi;
				document.querySelector(".bui").textContent ="BUI: "+datos[0].bui;
				document.querySelector(".fwi").textContent ="FWI: "+datos[0].dwi;
			}
		);
	}
	document.addEventListener("DOMContentLoaded", () => {


		refreshDatos(6).then(
			data => {
				CargarDatos(data)
				console.log(data)
			}
		);
		refreshId = setInterval(()=>{
			refreshDatos(6).then(
			data => {
				CargarDatos(data)
				console.log(data)
			}
		);
		}, 60000)
		boton_de_temperatura.addEventListener("click", () => {CargarGraficoTemperatura();})
		boton_de_humedad.addEventListener("click", ()=>{CargarGraficoHumedad()})
		boton_de_viento.addEventListener("click", ()=>{CargarGraficoViento()})
		boton_de_presion.addEventListener("click", ()=>{CargarGraficoPresion()})
		boton_de_fuego.addEventListener("click", ()=>{CargarGraficoFuego()})
	})


	function fireDanger(fwi){
	let fwiFloat = parseFloat(fwi)
		
		if(fwiFloat>=50){
			return "Extremo"
		}else{
			if(fwiFloat>=38){
				return "Muy alto"
			}else{
				if(fwiFloat>=21.3){
					return "Alto"
				}else{
					if(fwiFloat>=11.2){
						return "Moderado"
					}else{
						if(fwiFloat>=5.2){
							return "Bajo"
						}else{
							return "Muy bajo"
						}
					}
				}
			}
		}

	}


</script>
</html>
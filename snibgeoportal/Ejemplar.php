<!DOCTYPE html>
<html lang="es">
	<head>
		<?php 
			include("../includes/head-tag-contents.php");
			include('connectdb.php');
			require 'functions.php';	  	 
			
			$llaveejemplar = isset($_GET['id']) ? $_GET['id'] : '';
			// Con la llave ingresada armamos la liga con la cual se van a poder agregar comentarios en enciclovida a los ejemplares
			$enciclovida = new enciclovida();
			$urlComments = $enciclovida->ligaComentarios($mysqli, $llaveejemplar);
			// obtenemos los datos de la especie a mostrar
			list($scientificName,$autor,$commonName,$region,$localidad,$procedencia,$col,$ins,$lat,$lon) = $enciclovida->obtenResumen($mysqli, $llaveejemplar);
			$titulo = $enciclovida->obtenProyecto($mysqli, $llaveejemplar);
			$mysqli->close();									
	  	?>	  
	  	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>		
		<style>
			#map {
				height: 400px; /* Define un tamaño para el contenedor del mapa */
				width: 100%;
			}
		</style>
		<script>
			document.addEventListener('DOMContentLoaded', function() {
            var map = L.map('map').setView([<?php echo $lat; ?>, <?php echo $lon; ?>], 13);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            L.marker([<?php echo $lat; ?>, <?php echo $lon; ?>]).addTo(map);
        });
		</script>
	</head>
	<body>
		<?php include("../includes/navigation.php"); ?>			
		<div class="container" style="padding-top:15px;">
			<div class="card">
				<div class="card-header">
					<h2 style="text-align:center"><i><?php echo $scientificName; ?></i>, <?php echo $autor; ?></h2>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-6">
							<h3>Detalles</h3>
							<p><b>Nombre común: </b> <?php echo $commonName; ?>
							<br><b>Colectado en: </b> <?php echo $region; ?>
							<br><b>Localidad: </b> <?php echo $localidad; ?>
							<br><b>Procedencia del ejemplar: </b> <?php echo $procedencia; ?></p>
						</div>
						<div class="col-md-6">	
							<h3><font color='white'>_</font></h3>					
							<p><b>Colección: </b> <?php echo $col; ?>
							<br><b>Institución: </b> <?php echo $ins; ?>
							<br><b>Proyecto: </b> <?php echo $titulo; ?></p>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12" id="map">
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php include("../includes/js-load.php"); ?>
	</body>
	<!-- Global site tag (gtag.js) - Google Analytics -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js">
</script>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-8226401-20"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());

gtag('config', 'UA-8226401-20');
</script>
</html>
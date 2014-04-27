<!doctype html>
<html>
<head>
	<title>test maps</title>
	<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
	<script type="text/javascript">
		google.maps.event.addDomListener(window, 'load', function(){
			//init sur paris
			var carte = null;
			carte = new google.maps.Map(
				document.getElementById('maps_visu'),
				{
					zoom:7,
					center: new google.maps.LatLng(48.855697, 2.347403)

				}
			);

			var address='Rennes';
			var geocoder = new google.maps.Geocoder();
			
			geocoder.geocode({'address':address},function(results,status){
				if(status == google.maps.GeocoderStatus.OK){
					var adr_latlng = results[0].geometry.location;

					var marker = new google.maps.Marker({
						position:adr_latlng,
						map:carte,
						title:'test',
						animation: google.maps.Animation.DROP
					});

					var content_info ="<div style='line-height:1.35;overflow:hidden;white-space:nowrap;'><div style='text-align:center'>Rennes</div>";
					content_info += "<a href='http://en.wikipedia.org/w/index.php?title=Rennes'>Informations sur Rennes </a></div>";
					var info_window = new google.maps.InfoWindow({
						content:content_info
					});

					google.maps.event.addDomListener(marker,'click',function(){
						info_window.open(carte, marker);	
					});

					info_window.open(carte, marker);
					carte.setCenter(adr_latlng);
				}
				else{
					alert('error '+status);
				}
			});

		});
	</script>
</head>
<body>
	<div id='maps_visu' style='width:700px;height:700px;margin:auto;'></div>
</body>
</html>
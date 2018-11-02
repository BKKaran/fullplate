<?php extract( $_REQUEST );

	$mapid = rand(); // An unique identifier for the map
	$maptype = strtoupper( $config['maptype']);

	$dimensions = 'height:'.$config['height'];
	if($config['width'])
		$dimensions .= ';width:'.$config['width'];

	// langitude and longitude for the map center
	$lat = $long = 'null';
	if( trim( $config['center'] ) ) {
		sscanf($config['center'], '%f, %f', $lat, $long);
	}

	if( isset( $config['address'] ) ) {

		$addresses = explode("|",$config['address']);
		if( isset($config['description']) ) {
			$descriptions = explode("|",$config['description']);
		}

		if( isset($config['icon']) ) {
			$icons = explode("|",$config['icon']);
		}
				
		if( isset($config['iconsize']) ) {
			$iconsizes = explode("|",$config['iconsize']);
		}

		// Building Markers
		$markers = '[';
		for($i = 0; $i < count($addresses); $i++) {

			$temp = $addresses[$i];
			$temp = str_replace(array("\n", '"', "'", "{br}", "&lt;", "&gt;"), array(' ', '\"', "\'", "<br>", "<", ">"), $temp);
			$address = $temp;

			// If multiple markers, hide popup, else show popup according
			if (count($addresses) > 1) {
				$config['popup'] = 'false';
			}

			// if it's empty, set the default description equal to the the address
			if(isset($descriptions[$i]) && strlen(trim($descriptions[$i])) != 0) {
				$html = $descriptions[$i]; 
			} else {
				$html = $address;
			}

			// Prepare the description html
			$html = str_replace(array("\n", '"', "'", "{br}", "&lt;", "&gt;"), array(' ', '\"', "\'", "<br>", "<", ">"), $html);
			
			// Prepare Icon
			$icon = isset( $icons[$i] ) ? $icons[$i] : '';
			$iconsize = isset( $iconsizes[$i] ) ? $iconsizes[$i] : '';
			
			// Extract the langitude and longitude for the map center
			$marker_latitude = $marker_longitude = null;
			if (trim($address)  != "") {
				sscanf($address, '%f, %f', $marker_latitude, $marker_longitude);
			}

			if ($i > 0) $markers .= ",";

			// Building markers list
			if ($marker_latitude == '' || $marker_longitude == '') {
				$markers .= '{';
				$markers .= trim("	address:'".$address."',");
				$markers .= trim("	html:'".$html."',");
				$markers .= trim("	popup:".$config['popup'].",");
				$markers .= trim('	flat:true,');

				if( !empty($icon) ) {
					$markers .= trim('	icon: {');
					$markers .= trim("		image:'".$icon."',");
					$markers .= trim('		iconsize:['.$iconsize.']');
					$markers .= trim('	}');
				}
				$markers .= '}';
			} else {
				$markers .= '{';
				$markers .= "	latitude:'".$marker_latitude."',";
				$markers .= "	longitude:'".$marker_longitude."',";
				$markers .= "	html:'".$html."',";
				$markers .= "	popup:'".$config['popup']."',";
				$markers .= '	flat:true,';
				if( !empty($icon) ) {
					$markers .= '	icon: {';
					$markers .= "		image:'".$icon."',";
					$markers .= '		iconsize:['.$iconsize.']';
					$markers .= '	}';
				}
				$markers .= '}';
			}
		}
		$markers .= ']';
		// Building Markers
	}?>
<div id="artigo-map-<?php echo $mapid; ?>" class="artigo-map" style="<?php echo $dimensions; ?>;"></div>
<script type="text/javascript">
	var map = jQuery("#artigo-map-<?php echo $mapid; ?>");
	map.gMap({
		maptype: google.maps.MapTypeId.<?php echo $config['maptype']; ?>,
		zoom: <?php echo $config['zoom']; ?>,
		markers: <?php echo $markers; ?>,
		panControl:<?php echo $config['pancontrol']?>,
		zoomControl:<?php echo $config['zoomcontrol']?>,
		draggable:<?php echo $config['draggable']?>,
		scrollwheel:<?php echo $config['scrollwheel']?>,
		mapTypeControl:<?php echo $config['typecontrol']?>,
		scaleControl:<?php echo $config['scalecontrol']?>,
		streetViewControl:<?php echo $config['streetcontrol']?>,
		overviewMapControl: true,
		latitude: <?php echo $lat; ?>,
		longitude: <?php echo $long; ?>
	});
</script>

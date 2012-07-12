<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?php echo $site_name; ?> Heatmap</title>
		<?php
		// System Files
		echo html::stylesheet($css_url."media/css/openlayers","",TRUE);
		echo html::script($js_url."media/js/OpenLayers", TRUE);
		echo "<script type=\"text/javascript\">OpenLayers.ImgPath = '".$js_url."media/img/openlayers/"."';</script>";
		?>
		<link rel="stylesheet" type="text/css" href="<?php echo url::base(); ?>plugins/heatmap/media/heatmap/css/styles.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo url::base(); ?>plugins/heatmap/media/heatmap/css/nav.css" />
		<script type="text/javascript" src="<?php echo url::base(); ?>plugins/heatmap/media/heatmap/js/heatmap.js"></script>
		<script type="text/javascript" src="<?php echo url::base(); ?>plugins/heatmap/media/heatmap/js/heatmap-openlayers.js"></script>
		<script type="text/javascript">
			var map, layer, heatmap;
			
			function init(){

				var ushahidiData={
					max: 2,
					data: <?php echo $data; ?>
				};

				var transformedUshahidiData = { max: ushahidiData.max , data: [] },
					data = ushahidiData.data,
					datalen = data.length,
					nudata = [];

				// in order to use the OpenLayers Heatmap Layer we have to transform our data into 
				// { max: <max>, data: [{lonlat: <OpenLayers.LonLat>, count: <count>},...]}

				while(datalen--){
					nudata.push({
						lonlat: new OpenLayers.LonLat(data[datalen].lon, data[datalen].lat),
						count: data[datalen].count
					});
				}

				transformedUshahidiData.data = nudata;

				map = new OpenLayers.Map( 'map');
				layer = new OpenLayers.Layer.OSM();

				// create our heatmap layer
				heatmap = new OpenLayers.Layer.Heatmap( "Heatmap Layer", map, layer, {visible: true, radius:10}, {isBaseLayer: false, opacity: 0.3, projection: new OpenLayers.Projection("EPSG:4326")});
				map.addLayers([layer, heatmap]);

				map.zoomToMaxExtent();
				map.zoomIn();
				heatmap.setDataSet(transformedUshahidiData);
			}

			window.onload = function(){ 
				init(); 
			};
		</script>
	</head>
	<body>
	<table>
		<tr style="height:40px;">
			<td class="header">
				<div class="title">
					<h1><?php echo $site_name; ?></h1>
					<span><?php echo $site_tagline; ?></span>
				</div>
				<div class="underlinemenu">
					<ul>
						<li><a href="<?php echo url::base(); ?>"><?php echo Kohana::lang('heatmap.home'); ?></a></li>
						<li><a href="<?php echo url::site()."heatmap/"; ?>" class="selected"><?php echo Kohana::lang('heatmap.heatmap'); ?></a></li>
					</ul>
				</div>
			</td>
		</tr>
		<tr style="height:100%;" valign="top">
			<td>
				<div id="mapcontainer">
					<div id="map"></div>
				</div>
			</td>
		</tr>
	</table>
	</body>
</html>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Motorola OTA Link Generator Tool</title>
  <link rel='stylesheet prefetch' href='http://fonts.googleapis.com/css?family=Open+Sans:600'>
  <link rel="stylesheet" href="css/style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1" /> 
</head>
<body>
  <hgroup>
  <h1>Motorola OTA Link Generator Tool</h1>
  <h3>By Erfan Abdi</h3>
</hgroup>
<form method="get" id="myform">
<?php
$page = '<div class="group">
    <input name="model" type="text" required="required" id="model" form="myform"><span class="highlight"></span><span class="bar"></span>
    <label>Model (ro.product.model)</label>
  </div>
  <div class="group">
    <input name="sv" type="text" required="required" id="sv" form="myform"><span class="highlight"></span><span class="bar"></span>
    <label>Software Version (ro.build.version.full)</label>
  </div>
  <div class="group">
    <input name="carrier" type="text" required="required" id="carrier" form="myform"><span class="highlight"></span><span class="bar"></span>
    <label>Carrier (ro.carrier)</label>
  </div>
  <button type="submit" class="button buttonBlue">Get it
    <div class="ripples buttonRipples"><span class="ripplesCircle"></span></div>
  </button>';
				
function replacer($myStringWithANewLine) {
	$newString = str_replace("\r\n","<br />",$myStringWithANewLine);
	$newString = str_replace("\n\r","<br />",$newString);
	$newString = str_replace("\r","<br />",$newString);
	$newString = str_replace("\n","<br />",$newString);
	return $newString;
}

if(isset($_GET['model']) && isset($_GET['sv']) && isset($_GET['carrier'])) {
	$url = "https://moto-cds.appspot.com/cds/upgrade/1/check/ctx/ota/key/".$_GET['model'];
	$model = $_GET['model'];
	$carrier = $_GET['carrier'];
	$myvars = '{"id":"1","extraInfo":{"carrier":"'.$carrier.'","model":"'.$model.'","softwareVersion":"'.$_GET['sv'].'"},"triggeredBy":"setup"}';

	$ch = curl_init( $url );
	curl_setopt( $ch, CURLOPT_POST, 1);
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
	curl_setopt( $ch, CURLOPT_HEADER, 0);
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
	$response = json_decode(curl_exec( $ch ));
	
	if ($response->{"proceed"}) {
	$version = $response->{"content"}->{"version"};
	$url = reset($response->{"contentResources"})->{"url"};
	$preInstallNotes = replacer($response->{"content"}->{"preInstallNotes"});
	$upgradeNotification = replacer($response->{"content"}->{"upgradeNotification"});
	$versionzip = $version.".zip";
	
	echo "<b>New OTA Available</b><br><a href=$url download=$versionzip><div class='button buttonBlue'>Download<div class='ripples buttonRipples'><span class='ripplesCircle'></span></div></div></a><br>";
	echo "<b>Version :</b><br>$version<br><br><b>PreInstall Notes :</b><br>$preInstallNotes<br><b>Upgrade Notification :</b><br>$upgradeNotification";
	$next = "index.php?model=$model&sv=$version&carrier=$carrier";
	echo "<br><a href=$next><div class='button buttonGreen'>Next Available OTA<div class='ripples buttonRipples'><span class='ripplesCircle'></span></div></div></a>";
	}else{
		echo "<center><font color='#F44336'>Something Looks Wrong ReCheck inputs Or No Update Available</font></center><br>".$page;
	}
	
} else {
	echo $page;
	}
?>
</form>
<footer>
	<p><a href="https://forum.xda-developers.com/moto-z/development/tool-motorola-ota-link-generator-tool-t3537039" target="_blank">Help and Discussion Forum</a></p>
	<p>Web Design By Josh Adamous</p>
</footer>
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

    <script src="js/index.js"></script>

</body>
</html>
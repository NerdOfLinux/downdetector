<?php
$siteURL=$_GET['site'];
?>
<html>
<head>
	<title>Down Detector</title>
</head>
<body>
<form name="downdetect" action="" method=GET>
<input type="text" name="site" required value="<?php echo $siteURL;?>"> </input>
<input type="submit" value="Check"></input>
</form>
<?php
if(empty($siteURL)){
	echo "No URL";
	echo "\n</body>\n</html>";
	exit();
}
//Begine cURL session
$cURL=curl_init();
//Set URL
curl_setopt($cURL, CURLOPT_URL, "$siteURL");
//Set options
curl_setopt($cURL, CURLOPT_HEADER, 1);
curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
//Parse
$headers=explode(" ",curl_exec($cURL));
$status=$headers[1];
if($status == "200"){
	echo "Site is up!";
}else{
	echo "Site is not up or redirecting!";
}
?>
</body>
</html>

<?php
$siteURL=$_GET['site'];
?>
<html>
<head>
	<title>Down Detector</title>
	<style>
		form{
			text-align: center;
		}
		input{
			font-size: 15px;
		}
		.data{
			text-align: center;
			font-size: 20px;
			font-family: arial;
		}
	</style>
</head>
<body>
<form name="downdetect" action="" method=GET>
<input type="text" name="site" required value="<?php echo $siteURL;?>"> </input>
<input type="submit" value="Check"></input>
</form>
<div class="data">
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
	echo "Site is up!<br>Code: $status";
}else if($status == "301" || $status == "302"){
	echo "Site is redirecting!<br>Code: $status";
}else{
	echo "Site is down!<br>Code: $status";
}
?>
</div>
</body>
</html>

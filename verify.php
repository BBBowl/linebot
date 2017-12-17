<?php
	$access_token = 'Ko4MCTrbiN2xbBBgAA/HQER2KmlURT84NLsTgu9i0zed077f6jAj8a/w0dCRaAHGuiY3R+7EGn0xfE6Saow6c+pH6xUVy56ee1BXJqaqiKXzDLB52pgXgjsx/GatuXQQtchq2fYbUhZyPymVvbVX9QdB04t89/1O/w1cDnyilFU=';

	$url = 'https://api.line.me/v1/oauth/verify';
	
	$headers = array('Authorization: Bearer ' . $access_token);
	
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$result = curl_exec($ch);
	curl_close($ch);
	
	echo $result;
?>
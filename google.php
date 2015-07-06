<?php
function getUrl($query)
{
$query = $query." wikihow";
$query = str_replace(" ","%20", $query);
$url =  "http://ajax.googleapis.com/ajax/services/search/web?v=1.0&key=AIzaSyBacVRiPNo7uMqhtjXG4Zeq1DtSQA_UOD4&cx=014517126046550339258:qoem7fagpyk&num=10&q=".$query;

// sendRequest
// note how referer is set manually
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_REFERER, 'http://www.google.com');
$body = curl_exec($ch);
curl_close($ch);
$body = json_decode($body, true);
return $body["responseData"]["results"][0]["url"];
}

function getUrlYoutube($query)
{
$query = $query." youtube";
$query = str_replace(" ","%20", $query);
$url =  "http://ajax.googleapis.com/ajax/services/search/web?v=1.0&key=AIzaSyBacVRiPNo7uMqhtjXG4Zeq1DtSQA_UOD4&cx=014517126046550339258:qoem7fagpyk&num=10&q=".$query;

// sendRequest
// note how referer is set manually
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_REFERER, 'http://www.google.com');
$body = curl_exec($ch);
curl_close($ch);
$body = json_decode($body, true);
$tempUrl = urldecode($body["responseData"]["results"][0]["url"]);
// create a new cURL resource
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://www.youtube.com/oembed?url=".$tempUrl."&format=json");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$vidObject = curl_exec($ch);
$vidObjectDecoded = json_decode($vidObject, true);
$iframe_string = $vidObjectDecoded["html"];
preg_match('/src="([^"]+)"/', $iframe_string, $match);
$vidObjectDecoded["iframe_URL"] = $match[1];
$vidObjectDecoded["video"] = $tempUrl;
curl_close($ch);
return $vidObjectDecoded;
//return 1;
}
?>
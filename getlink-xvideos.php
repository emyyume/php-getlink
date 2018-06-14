<?php
//Developed by Emy Yume
function curl($url) {
	$ch = curl_init();
	curl_setopt_array($ch, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_CONNECTTIMEOUT => 0,
		CURLOPT_SSL_VERIFYHOST => false,
		CURLOPT_SSL_VERIFYPEER => false
	));
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}
function explode_by($begin, $end, $data) {
	$data = explode($begin, $data);
	$data = explode($end, $data[1]);
	return $data[0];
}
function getlink($url) {
	$source = curl($url);
	$video = array();
	$video['low'] = explode_by('setVideoUrlLow(\'', '\'', $source);
	$video['high'] = explode_by('setVideoUrlHigh(\'', '\'', $source);
	return $video;
}
$url = isset($_GET['url']) ? $_GET['url'] : null;
if ($url)
	echo json_encode(getlink($url));
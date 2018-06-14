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
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36',
		CURLOPT_FOLLOWLOCATION => true
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
	$json = json_decode(explode_by('mediaDefinitions":', ',"video_unavailable_country', $source));
	for ($i = 0; $i < count($json); ++$i) {
		if ($json[$i]->videoUrl === '')
			array_shift($json);
		$video[$i]['quality'] = $json[$i]->quality;
		$video[$i]['src'] = $json[$i]->videoUrl;
	}
	return $video;
}

$url = isset($_GET['url']) ? $_GET['url'] : null;
if ($url)
	echo json_encode(getlink($url));
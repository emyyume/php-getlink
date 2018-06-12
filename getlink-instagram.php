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
	$is_video = explode_by('is_video":', ',', $source);
	if ($is_video === 'true') {
		$video = explode_by('video_url":"', '"', $source);
		return '<video controls><source src="' . $video . '" type="video/mp4">Your browser doesn\'t support HTML5 video.</video>';
	} else {
		$json = explode_by('display_resources":', ',"is_video', $source);
		$image = json_decode($json);
		return '<a href="' . $image[2]->src . '"><img src="' . $image[2]->src . '"></a>';
	}
}
$url = isset($_GET['url']) ? $_GET['url'] : null;
if ($url)
	echo getlink($url);
<?php
set_time_limit(0);
error_reporting(0);
function curl($url) {
	$ch = curl_init();
	curl_setopt_array($ch, array(
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_SSL_VERIFYHOST => false,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_URL => $url
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
$url = trim(stripslashes(htmlspecialchars($_GET['url'])));
if (!$url) die();
if (strpos($url, "embed")) {
	$id = explode("embed/", $url);
	$url = "https://vidcloud.co/v/" . $id[1];
}
$result = curl($url);
$ajax = explode_by("url: '/player", "'", $result);
$url_player = "https://vidcloud.co/player" . $ajax;
$json = json_decode(curl($url_player));
if ($json->status == 1) {
	$source = html_entity_decode($json->html);
	$json = json_decode(explode_by("sources: ", "\n", $source));
	$link = array(
		'src' => $json[0]->src,
		'type' => $json[0]->type,
		'label' => $json[0]->label
	);
	echo json_encode($link);
} else die("404 not found.");
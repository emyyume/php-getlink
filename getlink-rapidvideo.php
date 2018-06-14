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
function get_code($url) {
	$code = explode('/v/', $url);
	return $code[1];
}
function getlink($url, $code, $api) {
	$check_status = 'https://api.rapidvideo.com/v1/objects.php?ac=info&code=' . $code . '&apikey=' . $api;
	$result = json_decode(curl($check_status));
	$video = array();
	if ($result->result->$code->status === 200) {
		$source = curl($url . '&q=1080');
		preg_match_all('#<source src="(.*)" type="(.*)" title="(.*)" data-res="(.*)" />#', $source, $list);
		for ($i = 0; $i < count($list[3]); ++$i) {
			$video[$i]['quality'] = $list[3][$i];
			$video[$i]['src'] = $list[1][$i];
		}
		return $video;
	} else {
		echo '404: File not found (e.g. deleted video or wrong URL)';
		return null;
	}
}
$url = isset($_GET['url']) ? $_GET['url'] : null;
if ($url) {
	$code = get_code($url);
	$api = '6272cc3581b25e86937b2e57b925827f8ddd5a48db07013519f1223f50939b7e';
	echo json_encode(getlink($url, $code, $api));
}
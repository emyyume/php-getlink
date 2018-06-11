<?php
//Developed by Emy Yume
function curl($url) {
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_CONNECTTIMEOUT => 0,
		CURLOPT_SSL_VERIFYHOST => false,
		CURLOPT_SSL_VERIFYPEER => false
	));
	$result = curl_exec($curl);
	curl_close($curl);
	return $result;
}
function get_code($url) {
	$code = explode('/v/', $url);
	return $code[1];
}
function getlink($code, $api, $url) {
	$check_status = 'https://api.rapidvideo.com/v1/objects.php?ac=info&code=' . $code . '&apikey=' . $api;
	$result = json_decode(curl($check_status));
	if ($result->result->$code->status === 200) {
		$source = curl($url . '&q=1080');
		preg_match_all('#<source src="(.*)" type="(.*)" title="(.*)" data-res="(.*)" />#', $source, $list);
		for ($i = 0; $i < count($list[3]); ++$i) {
			$video[$i]['quality'] = $list[3][$i];
			$video[$i]['source'] = $list[1][$i];
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
	$quality = getlink($code, $api, $url);
	echo json_encode(getlink($code, $api, $url));
}
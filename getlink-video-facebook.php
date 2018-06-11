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
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36'
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
function fb_direct_video($url) {
	$source = curl($url);
	$list['sd'] = explode_by('sd_src_no_ratelimit:"', '"', $source);
	$list['hd'] = explode_by('hd_src_no_ratelimit:"', '"', $source);
	return $list;
}
$url = isset($_GET['url']) ? $_GET['url'] : null;
if ($url) {
	$list = fb_direct_video($url);
	if ($list['sd'])
		echo '<b>SD</b>: <a href="' . $list['sd'] . '">' . $list['sd'] . '</a><br><br>';
	if ($list['hd'])
		echo '<b>HD</b>: <a href="' . $list['hd'] . '">' . $list['hd'] . '</a>';
}
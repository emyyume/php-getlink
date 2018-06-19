<?php
//Developed by EmyYume
set_time_limit(0);
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
function get_list($html) {
	preg_match_all("/\w*\.torrent/", $html, $matches);
	return $matches[0];
}
function download($id) {
	$url = "https://sukebei.nyaa.si/download/$id";
	$file = fopen("download/$id", "w") or die("Remember create a folder \"download\" before downloading.");
	$ch = curl_init();
	curl_setopt_array($ch, array(
		CURLOPT_TIMEOUT => 0,
		CURLOPT_SSL_VERIFYHOST => false,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_URL => $url,
		CURLOPT_FILE => $file
	));
	curl_exec($ch);
	curl_close($ch);
	fclose($file);
}
function download_all($code) {
	$page = 1;
	$sukebei = "https://sukebei.nyaa.si/user/offkab?f=0&c=0_0&q=%2B%2B%2B+%5BHD%5D+".$code."&p=".$page;
	while (true) {
	    $files = get_list(curl($sukebei));
		if (!$files) break;
		foreach ($files as $file)
			download($file);
		$sukebei = "https://sukebei.nyaa.si/user/offkab?f=0&c=0_0&q=%2B%2B%2B+%5BHD%5D+".$code."&p=".++$page;
	}
}

$code = isset($_GET['code']) ? strtoupper($_GET['code']): null;
if ($code) download_all($code);
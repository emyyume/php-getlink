<?php
set_time_limit(0);
$url = trim(stripslashes(htmlspecialchars($_GET['url'])));
if (!$url) die();
$code = explode('/', $url);
$download_link = 'https://www.rapidvideo.com/d/' . $code[4];
$ch = curl_init();
curl_setopt_array($ch, array(
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_CONNECTTIMEOUT => 0,
	CURLOPT_SSL_VERIFYHOST => false,
	CURLOPT_SSL_VERIFYPEER => false,
	CURLOPT_URL => $download_link
));
$result = curl_exec($ch);
curl_close($ch);
preg_match_all('/<a href="(.*)" id="button-download" class="(.*)" style="(.*)">/', $result, $matches);
$quality = ['360', '480', '720', '1080'];
$video = array();
for ($i = 0; $i < count($matches[1]); ++$i) {
	$video[$i]['file'] = $matches[1][$i];
	$video[$i]['type'] = 'video/mp4';
	foreach ($quality as $each)
		if (strpos($video[$i]['file'], $each . '.mp4'))
			$video[$i]['label'] = $each . 'p';
}
$video[count($video) - 1]['default'] = 'true';
echo json_encode($video);
<?php
$url = trim(stripslashes(htmlspecialchars($_GET['url'])));
if (!$url) die();
if (strpos($url, 'embed.php?s='))
	$embed = $url;
else {
	$url = explode('yucloud.co/', $url);
	$embed = 'https://yucloud.co/direct/embed.php?s=' . $url[1];
}
$ch = curl_init();
curl_setopt_array($ch, array(
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_SSL_VERIFYHOST => false,
	CURLOPT_SSL_VERIFYPEER => false,
	CURLOPT_URL => $embed
));
$result = curl_exec($ch);
curl_close($ch);
preg_match("/<source src='(.*)' type=\"(.*)\" \/>/", $result, $matches);
$video = array(
	'src' => $matches[1],
	'type' => $matches[2]
);
echo json_encode($video);
<?php
//Developed by EmyYume

/* Hướng dẫn
- Nếu máy cài Ampps, copy file này vứt vào ..\Ampps\www\
- Nếu máy cài XAMPP, copy file này vứt vào ..\xampp\htdocs\
- Mở Ampps / XAMPP lên, vào trình duyệt gõ "localhost" hoặc "127.0.0.1" (bỏ ngoặc)
- Chọn đường dẫn đến file php này, bấm vào
- Thêm vào hậu tố "?url=<link nhạc NCT>" (http://127.0.0.1/getlink-nct.php?url=https://www.nhaccuatui.com/bai-hat/hoc-meo-keu-tieu-phong-phong-xiao-feng-feng-ft-tieu-phan-phan-xiao-pan-pan.n0wP1Ge9poDY.html)
- Enter và tận hưởng
 */

// Lưu ý: Đại đa số link nhạc chỉ get được dạng 128kbps, vì hiện tại bên NCT không thấy cung cấp API hỗ trợ (hoặc do t k tìm thấy :v)
set_time_limit(0);
error_reporting(0);
function curl(&$url) {
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
function explode_by($begin, $end, &$data) {
	$data = explode($begin, $data);
	$data = explode($end, $data[1]);
	return $data[0];
}
function trim_xml(&$xml) {
	$xml = trim($xml);
	$xml = str_replace('<![CDATA[', '', $xml);
	$xml = str_replace(']]>', '', $xml);
	return $xml;
}
function getlink(&$url) {
	$html = curl($url);
	$xml = explode_by('URL = "', '"', $html);
	$data = trim_xml(file_get_contents($xml));
	$music = array(
		'128kbps' => trim(explode_by('<location>', '</location>', $data)),
		'320kbps' => trim(explode_by('<locationHQ>', '</locationHQ>', $data))
	);
	return $music;
}

$url = isset($_GET['url']) ? $_GET['url'] : null;
if ($url) {
	$music = getlink($url);
	if ($music['320kbps'])
		echo '<audio controls autoplay loop><source src="'.$music['320kbps'].'" type="audio/mpeg">Your browser does not support the <code>audio</code> element.</audio>';
	else
		echo '<audio controls autoplay loop><source src="'.$music['128kbps'].'" type="audio/mpeg">Your browser does not support the <code>audio</code> element.</audio>';
}
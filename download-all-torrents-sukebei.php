<?php
//Developed by EmyYume
//Remember create a folder "download" before downloading.
set_time_limit(0);
error_reporting(0);
$code = $quality = $limit = $checking = "";
if (isset($_POST['submit'])) {
	if (empty($_POST['code'])) $checking = "Product-code mustn't be empty.";
	else {
		$code = strtoupper(check_input($_POST['code']));
		if (!preg_match("/^[a-zA-Z ]*$/", $code))
			$checking = "Only letters and whitespace allowed at product-code.";
	}

	if (empty($_POST['quality'])) $checking = "Quality mustn't be empty.";
	else {
		$quality = strtoupper(check_input($_POST['quality']));
		$quality = ($quality === "HD (High Definition)") ? "HD" : "FHD";
	}

	if (!empty($_POST['limit'])) {
		$limit = check_input($_POST['limit']);
		if (!preg_match("/^\d*$/", $limit))
			$checking = "Only numeric allowed at number of files limited.";
	} else $limit = 0;

	download_all($code, $quality, $limit);
}

function check_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
function curl(&$url) {
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
function get_list(&$html) {
	preg_match_all("/\w*\.torrent/", $html, $matches);
	return $matches[0];
}
function download(&$id) {
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
function download_all(&$code, &$quality, &$limit) {
	if (!$code) return null;
	$page = 1;
	$sukebei = "https://sukebei.nyaa.si/user/offkab?f=0&c=0_0&q=%2B%2B%2B+%5B".$quality."%5D+".$code."&p=".$page;
	$downloaded = 0;
	while (true) {
	    $files = get_list(curl($sukebei));
		if (!$files) break;
		foreach ($files as $file) {
			download($file);
			++$downloaded;
			if ($downloaded == $limit)
				return null;
		}
		$sukebei = "https://sukebei.nyaa.si/user/offkab?f=0&c=0_0&q=%2B%2B%2B+%5B".$quality."%5D+".$code."&p=".++$page;
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" type="image/png" href="https://sukebei.nyaa.si/static/favicon.png">
	<link rel="icon" type="image/png" href="https://sukebei.nyaa.si/static/favicon.png">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="icon" type="image/png" href="icon.png" />
	<title>Download All Torrents from Sukebei</title>
</head>
<body style="background-color: #666;">
<div class="container" style="width: 800px; padding-top: 100px;">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<div class="panel-title"><strong><i class="fas fa-cogs"></i> Options</strong></div>
		</div>
		<div class="panel-body text-justify">
			<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" class="form-horizontal">
				<div class="form-group row">
					<label for="code" class="col-sm-3 control-label"><i class="fas fa-barcode"></i> Product-code:</label>
					<div class="col-sm-8">
						<input class="form-control" id="code" name="code" type="text" placeholder="SSNI" maxlength="32" value="<?php echo $code; ?>" required="required">
					</div>
				</div>
				<div class="form-group row">
					<label for="quality" class="col-sm-3 control-label"><i class="far fa-play-circle"></i> Quality:</label>
					<div class="col-sm-8">
						<select class="form-control" id="quality" name="quality">
							<option>HD (High Definition)</option>
							<option>FHD (Full HD)</option>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label for="limit" class="col-sm-3 control-label"><i class="fas fa-bolt"></i> Limited by:</label>
					<div class="col-sm-8">
						<input class="form-control" id="limit" name="limit" type="number" placeholder="Leave blank to download all" min="1" max="150" value="<?php echo $limit; ?>">
					</div>
				</div>
				<div class="form-group row">
					<label for="checking" class="col-sm-3 control-label"><i class="fas fa-spinner fa-pulse"></i> Checking:</label>
					<div class="col-sm-8">
						<input class="form-control" id="checking" name="checking" type="text" placeholder="None" value="<?php echo $checking; ?>" readonly="readonly">
					</div>
				</div>
				<button class="btn btn-primary col-sm-offset-8" id="submit" name="submit" type="submit"><i class="fas fa-cloud-download-alt"></i> Start Download</button>
			</form>
		</div>
		<div class="panel-footer text-right">Copyright &copy; <strong><a href="https://www.facebook.com/100021569044431" target="_blank">EmyYume</a></strong>. All Rights Reserved.</div>
	</div>
</div>
</body>
</html>
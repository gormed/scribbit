<?php

function base64url_decode($base64url)
{
	$base64 = strtr($base64url, '-_', '+/');
	$plainText = base64_decode($base64);
	if ($plainText == FALSE)
		$plainText = "Error decoding base64 string";
	return ($plainText);
}

require_once 'path.php';

$image = $_POST["data"];

$test = base64url_decode($image);
//$decoded = base64url_decode($image);

$file = 'img_'.mt_rand(0,mt_getrandmax()).'.png';
$handle = fopen($file, 'w');
fwrite($handle, $test);
fclose($handle);

// import re 
// import base64
// class TnUploadHandler(webapp.RequestHandler):
// 	dataUrlPattern = re.compile('data:image/(png|jpeg);base64,(.*)$')
// 	def post(self):
// 		uid = self.request.get('uid')
// 		img = self.request.get('img')

// 		imgb64 = self.dataUrlPattern.match(img).group(2)
// 		if imgb64 is not None and len(imgb64) > 0:
// 			thumbnail = Thumbnail(uid = uid, img = db.Blob(base64.b64decode(imgb64)))
// 			thumbnail.put()
?>

<?php
const JUMP_LIST = array(
	'SorB.php' => 'http://sc.seventop.top/s2h2b/',
);
const MIME_LIST = array(
	'js' => 'application/x-javascript',
	'css' => 'text/css',
);
function out($uri)
{
	if (file_exists($uri)) {
		$mime = strtolower(substr($uri, strrpos($uri, '.') + 1));
		if (isset(MIME_LIST[$mime])) $mime = MIME_LIST[$mime];
		else {
			$finfo = finfo_open(FILEINFO_MIME);
			$mime = finfo_file($finfo, $uri);
			finfo_close($finfo);
		}
		header('Content-type: ' . $mime);
		die(file_get_contents($uri));
	}
}
if (isset(JUMP_LIST[$qry = $_SERVER['QUERY_STRING']])) die(header('Location: ' . JUMP_LIST[$qry]));
out($uri = 'script/' . str_replace('-', '/', $qry));
out(substr_replace($uri, '/main', strrpos($uri, '.'), 0));
header('Content-type: application/x-javascript');
die("alert('Warning From CCPIRA Script Online:\\n\\nCan Not Find The Script \"$qry\"!');");

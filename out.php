<?php

/**跳转列表 */
const JUMP_LIST = array(
	'SorB.php' => 'http://sc.seventop.top/s2h2b/',
);

/**预设格式列表 */
const MIME_LIST = array(
	'js' => 'application/x-javascript',
	'css' => 'text/css',
);

// 获取访问的URI
$qry = $_SERVER['QUERY_STRING'];

// 如果在跳转列表就跳转
if (isset(JUMP_LIST[$qry])) die(header('Location: ' . JUMP_LIST[$qry]));

// 如果尝试访问父目录就418错误
if (strpos($qry, '..') !== false) include '../php/error/418.php';

// 检测是否存在JS文件
$uri = 'script/' . str_replace('-', '/', $qry);
if (file_exists($uri)) goto success;

// 检测是否存在项目文件夹内的main.js
$uri = substr_replace($uri, '/main', ($pos = strrpos($uri, '/')) + strpos(substr($uri, $pos), '.'), 0);
if (file_exists($uri)) goto success;

// JS文件不存在！
header('Content-type: ' . MIME_LIST['js']);
echo "alert('Warning From CCPIRA Script Online:\\n\\nCan Not Find The Script \"$qry\"!');";
die();

// 检查缓存
success:
$md5 = md5_file($uri);
$head = getallheaders();
if (isset($head['If-None-Match']) && $head['If-None-Match'] == $md5) die(header('HTTP/1.1 304'));
else header('ETag: ' . $md5);
header('Last-Modified: ' . gmdate("D, d M Y H:i:s T", filemtime($uri)));

// 设置格式
$mime = strtolower(substr($uri, strrpos($uri, '.') + 1));
if (isset(MIME_LIST[$mime])) $mime = MIME_LIST[$mime];
else {
	$finfo = finfo_open(FILEINFO_MIME);
	$mime = finfo_file($finfo, $uri);
	finfo_close($finfo);
}
header('Content-type: ' . $mime);

// 输出
echo file_get_contents($uri);

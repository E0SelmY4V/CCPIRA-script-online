<?php

// ver: 1.1.20220611-0

require '../../scpo-php/cache.php';
require '../../scpo-php/errpage.php';
require '../../scpo-php/url.php';

/**跳转列表 */
$jump_list = array(
	'SorB.php' => 'http://sc.' . ScpoPHP\Url::dmn(true) . '/s2h2b/',
);

/**预设格式列表 */
const MIME_LIST = array(
	'js' => 'application/x-javascript',
	'css' => 'text/css',
);

// 获取访问的URI
$qry = $_SERVER['QUERY_STRING'];

// 如果在跳转列表就跳转
if (isset($jump_list[$qry])) die(header('Location: ' . $jump_list[$qry]));

function suct($uri)
{
	if (file_exists($uri)) {
		// 检查缓存
		ScpoPHP\Cache::t_file($uri);

		// 设置格式
		$mime = strtolower(substr($uri, strrpos($uri, '.') + 1));
		if (isset(MIME_LIST[$mime])) $mime = MIME_LIST[$mime];
		else {
			$finfo = finfo_open(FILEINFO_MIME);
			$mime = finfo_file($finfo, $uri);
			finfo_close($finfo);
		}
		header("Content-type: $mime");

		// 输出
		die(file_get_contents($uri));
	}
}

function test($qry)
{
	suct($uri = "script/$qry");
	suct(substr_replace($uri, '/main', ($pos = strrpos($uri, '/')) + strpos(substr($uri, $pos), '.'), 0));
}

test($qry);

// 如果尝试访问父目录就418错误
$qry = str_replace('-', '/', $qry);
if (in_array('..', explode('/', $qry))) ScpoPHP\Errpage::die(418);

test($qry);

// JS文件不存在！
header('Content-type: ' . MIME_LIST['js']);
echo "alert('Warning From CCPIRA Script Online:\\n\\nCan Not Find The Script \"{$_SERVER['QUERY_STRING']}\"!');";

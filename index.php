<?php
header("Content-Type:text/html; charset=utf-8");
date_default_timezone_set('PRC');

require_once './app/config/config.php';

foreach ($library as $key => $value) {
	require_once LIBRARY . $value . '.php';
}

$controller = load_class('controller');

if (!empty($_POST['novel'])) {
	$controller->search($_POST['novel']);
} elseif (isset($_GET['page'])) {
	switch ($_GET['page']) {
	case 'list':
		$listurl = $_GET['url'];
		$controller->getList($listurl[0]);
		break;
	case 'chapter':
		$url = $_GET['url'];
		$controller->getChapter($url);
		break;
	case 'search':
	default:
		load_view('search');
		break;
	}
} else {
	$view = load_view('header') . load_view('search') . load_view('footer');
}

<?php

$links = ['a', 'b', 'c'];
// file_get_contents('./app/view/list.php');
function view($links) {

	require_once './app/view/list.php';
}

$sql = 
function bind_param($vals) {
	$array = ['string'=> 's','integer'=>'i','double'=>'d','BLOB'=>'b'];
	$str1 = '';
	foreach ($vals as $key => $val) {
		$type = gettype($val);
		if (isset($array["$type"])) {
		$str1 .= "$array['type']";
		}
	}
	$str2 = implode(',', $vals);
	$str = "bind_param(\"$str1\",$str2);";

	return $str;
}
echo "bind_param(\"$str1\",$str2);";
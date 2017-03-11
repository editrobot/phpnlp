<?php
require("robot/read_all_file.php");
require("robot/file_list_in_folder.php");
require("robot/cut_word.php");
require("robot/cut_symbol_head_end.php");
require("robot/ql.php");
require("robot/Frame.php");
require("robot/longtailword.php");
//require("robot/pinyin.php");
function echo_m_f($word){
	echo iconv("UTF-8","GBK",$word);
	echo "\n";
}
$file_list = file_get_contents('file/text.txt');
$file_list_array = explode("|\n",$file_list);
if($file_list_array[count($file_list_array)] == ''){
	array_pop($file_list_array);
	print_r($file_list_array);
}

$Var = new _oopf_Frame;
$Var->_construct();
/*
	$file_String = file_get_contents('file/_news_10230.html.utf.txt');
	$file_String = $Var->WordFilter($file_String);
	echo_m_f($Var->indexS($file_String));
*/
$file_list_array_count = 0;
while(isset($file_list_array[$file_list_array_count])){
	echo "\n";
	echo $file_list_array[$file_list_array_count].'___begin';
	$file_String = file_get_contents($file_list_array[$file_list_array_count]);
	$file_String = $Var->WordFilter($file_String);
	echo_m_f($Var->indexS($file_String));
	echo $file_list_array[$file_list_array_count].'___end';
	unlink($file_list_array[$file_list_array_count]);
	unset($file_list_array[$file_list_array_count]);
	file_put_contents('file/text.txt',implode("|\n",$file_list_array),LOCK_EX);
	++$file_list_array_count;
}
?>
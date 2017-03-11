<?php
function Participledoing($File){
	$handle = fopen ($File,"rb") or exit("Unable to open file!");
	$temp_array = array();
	$temp = '';
	while(!feof($handle)){
		$data = fgetc($handle);
		
		if(($data != "\t")&&($data != "\r")&&($data != "\n")&&($data != "\r\n")&&($data != ' ')&&($data != '/')){
			$temp = $temp.$data;
		}
		else if($temp != ''){
			array_push($temp_array,$temp);
			$temp = '';
		}
	}
	if($temp != ''){
		array_push($temp_array,$temp);
		$temp = '';
	}
	print_r($temp_array);
	/*
	N				名词
V				动词
ADJ			形容词
ADV			副词
CLAS		量词
ECHO		拟声词
STRU		结构助词
AUX			助词
COOR		并列连词
CONJ		连词
SUFFIX	前缀
PREFIX	后缀
PREP		介词
PRON		代词
QUES		疑问词
NUM			数词
IDIOM		成语
	*/
	$new_array = array("词","\r");
	$temp_array_count = 0;
	while(isset($temp_array[$temp_array_count])){
		if(($temp_array[$temp_array_count+1] != 'wkz')&&
			($temp_array[$temp_array_count+1] != 'wky')&&
			($temp_array[$temp_array_count+1] != 'wyz')&&
			($temp_array[$temp_array_count+1] != 'wyy')&&
			($temp_array[$temp_array_count+1] != 'wj')&&
			($temp_array[$temp_array_count+1] != 'ww')&&
			($temp_array[$temp_array_count+1] != 'wt')&&
			($temp_array[$temp_array_count+1] != 'wd')&&
			($temp_array[$temp_array_count+1] != 'wf')&&
			($temp_array[$temp_array_count+1] != 'wn')&&
			($temp_array[$temp_array_count+1] != 'wm')&&
			($temp_array[$temp_array_count+1] != 'ws')&&
			($temp_array[$temp_array_count+1] != 'wp')&&
			($temp_array[$temp_array_count+1] != 'wb')&&
			($temp_array[$temp_array_count+1] != 'wh')&&
			($temp_array[$temp_array_count+1] != 'n_newword')
		){
				array_push($new_array,$temp_array[$temp_array_count]);
				array_push($new_array,"\t");
				array_push($new_array,$temp_array[$temp_array_count+1]);
				array_push($new_array,"\r");
		}
		++$temp_array_count;
		++$temp_array_count;
	}
	return $new_array;
}

file_put_contents('to.txt',implode('',Participledoing('from.txt')));
?>
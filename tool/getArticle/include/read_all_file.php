<?php
function read_all_file($argument){
	$encode = 'utf-8';
	$url = '';
	$format = 'phptag';
	$argument_array = explode(":",$argument);
	unset($argument);
	$argument_array_count = 0;
	while(isset($argument_array[$argument_array_count])){
		switch($argument_array[$argument_array_count]){
			case 'url':
				++$argument_array_count;
				if('http' == $argument_array[$argument_array_count]){
					$url = $argument_array[$argument_array_count].':'.$argument_array[$argument_array_count+1];
					$argument_array_count += 2;
					echo $url;
				}
				else{
					$url = $argument_array[$argument_array_count];
					++$argument_array_count;
				}
				break;
			case 'encode':
				++$argument_array_count;
				$encode = $argument_array[$argument_array_count];
				++$argument_array_count;
				break;
			case 'format':
				++$argument_array_count;
				$format = $argument_array[$argument_array_count];
				++$argument_array_count;
				break;
			default:
		}
		++$argument_array_count;
	}
	unset($argument_array,$argument_array_count);
$format_array = array();
$temp_array = array();
$status = 'lock';

$handle = fopen ($url,"rb") or exit("Unable to open file!");
while(!feof($handle)){
	$data = fgetc($handle);
	if(($data != "\t")&&($data != "\r")&&($data != "\n")&&($data != "\r\n")){
		if($data == '<'){
			$status = 'unlock';
			if(isset($temp_array[0])){
				array_push($format_array,implode('',$temp_array));
				unset($temp_array);
			}
			$temp_array = array('<');
		}
		else if($data == '>'){
			$status = 'lock';
			array_push($temp_array,'>');
			array_push($format_array,implode('',$temp_array));
			unset($temp_array);
			$temp_array = array();
		}
		else{
			if($status == 'lock'){
				array_push($temp_array,$data);
			}
			else if($status == 'unlock'){
				array_push($temp_array,$data);
			}
		}
	}
}
fclose($handle);
return $format_array;
}

function get_html_page($url){//模拟浏览器抓取页面
	$process = curl_init($url);
	curl_setopt($process, CURLOPT_RETURNTRANSFER, true);//设定返回的数据是否自动显示
	curl_setopt($process,CURLOPT_COOKIESESSION,true);//允许保存cookie
	curl_setopt($process, CURLOPT_COOKIE, '');//设置
	curl_setopt($process, CURLOPT_HTTPHEADER, array(
		'Accept: */*',
		'Accept-Charset: UTF-8,*;q=0.5',
		//'Accept-Encoding: gzip,deflate,sdch',
		'Accept-Language: zh-CN,zh;q=0.8',
		'Connection: keep-alive',
		'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
		//'Referer: '.$url,//告诉服务器我是从哪个页面链接过来的
		'User-Agent: Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.95 Safari/537.11',
		'X-Requested-With: XMLHttpRequest',
	));
	//curl_setopt($process, CURLOPT_HEADER, 1);
	//curl_setopt($process, CURLOPT_USERAGENT, $user_agent);
	//curl_setopt($process, CURLOPT_TIMEOUT, 30);
	//curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
	//curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
	$return_data = curl_exec($process);
	curl_close($process);
	return $return_data;
}

function Anglebracketstoarray($argument){
	$argument_count = 0;
	$format_array = array();
	$temp_array = array();
	$status = 'lock';
	while(isset($argument[$argument_count])){
		if(($argument[$argument_count] != "\t")&&
		($argument[$argument_count] != "\r")&&
		($argument[$argument_count] != "\n")&&
		($argument[$argument_count] != "\r\n")){
			if($argument[$argument_count] == '<'){
				$status = 'unlock';
				if(isset($temp_array[0])){
					array_push($format_array,implode('',$temp_array));
					unset($temp_array);
				}
				$temp_array = array('<');
			}
			else if($argument[$argument_count] == '>'){
				$status = 'lock';
				array_push($temp_array,'>');
				array_push($format_array,implode('',$temp_array));
				unset($temp_array);
				$temp_array = array();
			}
			else{
				if($status == 'lock'){
					if($argument[$argument_count] == ' '){
						$argument[$argument_count] = '';
					}
					else{
						array_push($temp_array,$argument[$argument_count]);
					}
				}
				else if($status == 'unlock'){
					array_push($temp_array,$argument[$argument_count]);
				}
			}
		}
		++$argument_count;
	}
	return $format_array;
}

function array_link($array_one,$array_two){//两个数组连接加长
	$array_one_count = 0;
	$array_two_count = 0;
	while(isset($array_two[$array_two_count])){
		array_push($array_one,$array_two[$array_two_count]);
		++$array_two_count;
	}
	return $array_one;
}

function Search_array_Element($main_array,$Element){//搜索数组中的元素
	$main_array_count = 0;
	while(isset($main_array[$main_array_count])){
		if($main_array[$main_array_count] == $Element){
			return $main_array_count;
		}
		++$main_array_count;
	}
	return -1;
}

function array_Insert_Variable($main_array,$array_id,$Variable){//在数组中插入数值
	$temp_array = array();
	
	$main_array_count = 0;
	while(isset($main_array[$main_array_count])){
		if($array_id == $main_array_count){
			array_push($temp_array,$Variable);
		}
		array_push($temp_array,$main_array[$main_array_count]);
		++$main_array_count;
	}
	
	return $temp_array;
}

function array_Canceled_Variable($main_array,$array_id){//清除数组中的值
	$main_array_count = $array_id;
	while(isset($main_array[$main_array_count+1])){
		$main_array[$main_array_count] = $main_array[$main_array_count+1];
		++$main_array_count;
	}
	array_pop($main_array);
	return $main_array;
}

function MemberReverse($old_array){//将数组的排列顺序颠倒
	$temp_array = array();
	$old_array_count = 0;
	while(isset($old_array[0])){
		$temp = array_pop($old_array);
		array_push($temp_array,$temp);
	}
	return $temp_array;
}
?>
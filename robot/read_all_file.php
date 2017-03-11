<?php
function read_all_file($argument){
	$encode = 'utf-8';
	$url = '';
	$format = 'phptag';
	$argument_array = explode(":",$argument);
	unset($argument);
	$argument_array_count = 0;
	while(isset($argument_array[$argument_array_count])){
		switch($argument_array[$argument_array_count])
		{
			case 'url':
				++$argument_array_count;
				if('http' == $argument_array[$argument_array_count]){
					$url = $argument_array[$argument_array_count].':'.$argument_array[$argument_array_count+1];
					$argument_array_count += 2;
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
$cache = '';
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
				if($data == ' '){
					$data = '';
				}
				else{
					array_push($temp_array,$data);
				}
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

function MemberReverse($old_array)//将数组的排列顺序颠倒
{
	$temp_array = array();
	$old_array_count = 0;
	while(isset($old_array[0]))
	{
		$temp = array_pop($old_array);
		array_push($temp_array,$temp);
	}
	return $temp_array;
}
function dR($temp_array){//清除重复
	$return_array = array();
	$temp_array_count = 0;
	while(isset($temp_array[$temp_array_count])){
		if(Search_array_Element($return_array,$temp_array[$temp_array_count]) == -1){
			array_push($return_array,$temp_array[$temp_array_count]);
			unset($temp_array[$temp_array_count]);
		}
		++$temp_array_count;
	}
	return $return_array;
}

function getSame($t1_array,$t2_array){//获取两组之间相同的项
	
	$t1_array = dr($t1_array);
	$t2_array = dr($t2_array);
	$temp_array = array();
	
	$t1_array_count = 0;
	while(isset($t1_array[$t1_array_count])){
		if((Search_array_Element($t2_array,$t1_array[$t1_array_count]) != -1)&&(Search_array_Element($temp_array,$t1_array[$t1_array_count]) == -1)){
			array_push($temp_array,$t1_array[$t1_array_count]);
		}
		++$t1_array_count;
	}
	return $temp_array;
}

function CA($t1_array,$t2_array){//余弦相似度
	$same_array = getSame($t1_array,$t2_array);//提取相同的项
	$same_array_count = 0;
	
	$t1_same_count_array = array();//第一组的计数器
	$t2_same_count_array = array();//第二组的计数器
	while(isset($same_array[$same_array_count])){
		array_push($t1_same_count_array,0);
		array_push($t2_same_count_array,0);
		++$same_array_count;
	}
	
	$key = 0;
	$t1_array_count = 0;
	while(isset($t1_array[$t1_array_count])){
		
		$key = Search_array_Element($same_array,$t1_array[$t1_array_count]);
		if($key != -1){
			++$t1_same_count_array[$key];
		}
		++$t1_array_count;
	}
	
	$key = 0;
	$t2_array_count = 0;
	while(isset($t2_array[$t2_array_count])){
		$key = Search_array_Element($same_array,$t2_array[$t2_array_count]);
		if($key != -1){
			++$t2_same_count_array[$key];
		}
		++$t2_array_count;
	}
	
	$Molecular = 0;
	$Denominatorleft = 0;
	$Denominatorright = 0;
	
	$same_array_count = 0;
	while(isset($same_array[$same_array_count])){
	
		$Molecular = $Molecular+$t1_same_count_array[$same_array_count]*$t2_same_count_array[$same_array_count];
		$Denominatorleft = $Denominatorleft+pow($t1_same_count_array[$same_array_count],2);
		$Denominatorright = $Denominatorright+pow($t2_same_count_array[$same_array_count],2);
		++$same_array_count;
	}
	
	if(($Denominatorleft == 0)||($Denominatorright == 0)){
		return 0;
	}
	else{
		return $Molecular/(sqrt($Denominatorleft)*sqrt($Denominatorright));
	}
}

function selfSimilarity($Target_array,$Template_array){//检查串相似度，原文输入
		$dr_array = dR($Template_array);
		$same_String_array = array();
		
		$TargetLength = count($Target_array);
		$TemplateLength = count($Template_array);
		if($TargetLength < $TemplateLength){
			$Length = $TargetLength/$TemplateLength;//长度的相似度
		}
		else if($TargetLength > $TemplateLength){
			$Length = $TemplateLength/$TargetLength;//长度的相似度
		}
		else{
			$Length = 1;
		}
		
		//种类相似度
		$kind = 0;
		$Target_array_count = 0;
		while(isset($Target_array[$Target_array_count])){
			if(Search_array_Element($dr_array,$Target_array[$Target_array_count]) != -1){
				++$kind;
			}
			++$Target_array_count;
		}
		$kind = $kind/count($dr_array);
		
		return $Length*$kind;
}
function Intersection_in_array($Target_array,$Template_array){//取交集
	$return_array = array();
	
	$array_count = 0;
	if(count($Target_array) == count($Template_array)){
		while(isset($Target_array[$array_count])){
			array_push($return_array,($Target_array[$array_count]&$Template_array[$array_count]));
			++$array_count;
		}
	}
	return $return_array;
}
?>
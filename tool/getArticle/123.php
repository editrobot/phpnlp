<?php
include('include.php');
function echo_m_f($word){
	echo iconv("UTF-8","GBK",$word);
	echo "\n";
}
function logmsg($s){
	file_put_contents('log.txt',$s,LOCK_EX);
}

function makefile($Industrylink,$Domain,$file_path){//将文件缓存到本地
	$local_html_file = $file_path.str_replace("/","_",str_replace($Domain,'',str_replace('http://','',$Industrylink)));//生成本地连接
	$http_html_file = $Domain.$Industrylink;//生成远程连接
	
	if(!file_exists($local_html_file)){
		$temp = '';
		$loop = 10;
		while(($temp == '')&&($loop != 0)){
			$temp = get_html_page($http_html_file);
			--$loop;
		}
		if($temp != ''){
			file_put_contents($local_html_file,$temp,LOCK_EX);
			
			echo_m_f('提取页面成功：'.$http_html_file);
			echo_m_f('本地页面地址：'.$local_html_file);
			$rand_var = mt_rand(0,300);
			if($rand_var >200 ){
				sleep(1);
			}
			else if($rand_var >100 ){
				sleep(2);
			}
			else if($rand_var >50 ){
				sleep(3);
			}
			else{
				sleep(4);
			}
			echo "\n";
		}
	}
	else{
		echo_m_f('页面已经存在：'.$local_html_file);
	}
	return $local_html_file;
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function get_sub_file($web_file,$Domain,$file_path){//提取分页
	$local_file = makefile($web_file,$Domain,$file_path);
	$sub_link_list = array();//分页列表子连接
	$loop = 1;//循环锁
	
	while($loop == 1){
		$loop = 0;
		$_oop_html_Filter_class = html_Filter_function('mode:get_all:Category:->html->body->div#main->div.m-left channel list->div.list-pages:url:'.$local_file);
		
		if(!isset($_oop_html_Filter_class->a_href_list[0])){
			$sub_link_list[0] = $local_file;
			return $sub_link_list;
		}
		$a_href_list_count_max = count($_oop_html_Filter_class->a_href_list)-1;
		
		if(Search_array_Element($sub_link_list,$_oop_html_Filter_class->a_href_list[$a_href_list_count_max]) == -1){
			$loop = 1;
		}
		$sub_link_list = array_link($sub_link_list,$_oop_html_Filter_class->a_href_list);
		
		$local_file = makefile($web_file.str_replace($Domain,'',$_oop_html_Filter_class->a_href_list[$a_href_list_count_max]),$Domain,$file_path);
		unset($_oop_html_Filter_class);
	}

	//获取子分页列表
	$sub_link_list = array_unique($sub_link_list);
	$sub_link_list = explode('|',implode('|',$sub_link_list));
	$sub_link_list_count = 0;
	while(isset($sub_link_list[$sub_link_list_count])){
		if($web_file != str_replace($Domain,'',$sub_link_list[$sub_link_list_count])){
			$sub_link_list[$sub_link_list_count] = makefile($web_file.str_replace($Domain,'',$sub_link_list[$sub_link_list_count]),$Domain,$file_path);
		}
		else{
			$sub_link_list[$sub_link_list_count] = makefile(str_replace($Domain,'',$sub_link_list[$sub_link_list_count]),$Domain,$file_path);
		}
		echo $sub_link_list[$sub_link_list_count];
		++$sub_link_list_count;
	}
	return $sub_link_list;
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function get_Article($local_file_array){
	Global $text;
	//提取文章
	$Article_link_list_array = array();
	$local_file_array_count = 0;
	while(isset($local_file_array[$local_file_array_count])){
		$temp_array = explode('/',$local_file_array[$local_file_array_count]);
		if($temp_array[0] != 'file'){
			$local_file_array[$local_file_array_count] = makefile($local_file_array[$local_file_array_count],'http://www.bozhidao.com','file/');
		}
		$_oop_html_Filter_class = array();
		$_oop_html_Filter_class = html_Filter_function('mode:get_all:Category:->html->body->div#main->div.m-left channel list->div.list-article->ul:url:'.$local_file_array[$local_file_array_count]);
		$Article_link_list_array = array_link($Article_link_list_array,$_oop_html_Filter_class->a_href_list);
		unset($_oop_html_Filter_class);
		++$local_file_array_count;
	}//提取文章页面连接
	
	$Article_link_list_array_count = 0;
	while(isset($Article_link_list_array[$Article_link_list_array_count])){
		$Article_link_list_array[$Article_link_list_array_count] = makefile($Article_link_list_array[$Article_link_list_array_count],'http://www.bozhidao.com','file/');
		$str = mb_convert_encoding(file_get_contents($Article_link_list_array[$Article_link_list_array_count]), "UTF-8", "GBK");
		
		file_put_contents($Article_link_list_array[$Article_link_list_array_count].'.utf.txt',$str,LOCK_EX);
		$Article_link_list_array[$Article_link_list_array_count] = $Article_link_list_array[$Article_link_list_array_count].'.utf.txt';
		echo_m_f($Article_link_list_array[$Article_link_list_array_count]);//显示本地文章页面
		
		$_oop_html_Filter_class = array();
		$_oop_html_Filter_class = html_Filter_function('mode:get_all:Category:->html->body->div#main->div.m-left content->div.article:url:'.$Article_link_list_array[$Article_link_list_array_count]);
		
		$data_array_count = 0;
		while(isset($_oop_html_Filter_class->data_array[$data_array_count])){
			$temp = Tag_Or_No($_oop_html_Filter_class->data_array[$data_array_count]);
			switch($temp){
				case 'tag_p':
				case 'tag_P':
				case 'endtag_p':
				case 'endtag_P':
				case 'tag_div':
				case 'tag_DIV':
				case 'endtag_div':
				case 'endtag_DIV':
				case 'tag_span':
				case 'endtag_span':
				case 'Singletag_br':
					$_oop_html_Filter_class->data_array[$data_array_count] = "\n";
					break;
				case 'tag_font':
				case 'endtag_font':
				case 'tag_a':
				case 'tag_A':
				case 'endtag_a':
				case 'endtag_A':
				case 'tag_strong':
				case 'endtag_strong':
				case 'Singletag_img':
					$_oop_html_Filter_class->data_array[$data_array_count] = '';
					break;
				
				default:
					$_oop_html_Filter_class->data_array[$data_array_count] = str_replace('&lsquo;','“',$_oop_html_Filter_class->data_array[$data_array_count]);
					$_oop_html_Filter_class->data_array[$data_array_count] = str_replace('&ldquo;','“',$_oop_html_Filter_class->data_array[$data_array_count]);
					$_oop_html_Filter_class->data_array[$data_array_count] = str_replace('&rdquo;','”',$_oop_html_Filter_class->data_array[$data_array_count]);
					$_oop_html_Filter_class->data_array[$data_array_count] = str_replace('&ndash;','-',$_oop_html_Filter_class->data_array[$data_array_count]);
					$_oop_html_Filter_class->data_array[$data_array_count] = str_replace('&nbsp;',' ',$_oop_html_Filter_class->data_array[$data_array_count]);
					$_oop_html_Filter_class->data_array[$data_array_count] = str_replace('&middot;','',$_oop_html_Filter_class->data_array[$data_array_count]);
					$_oop_html_Filter_class->data_array[$data_array_count] = str_replace('&hellip;','',$_oop_html_Filter_class->data_array[$data_array_count]);
					$_oop_html_Filter_class->data_array[$data_array_count] = str_replace('&mdash;','—',$_oop_html_Filter_class->data_array[$data_array_count]);
					$_oop_html_Filter_class->data_array[$data_array_count] = str_replace('&quot;','"',$_oop_html_Filter_class->data_array[$data_array_count]);
					
			}
			++$data_array_count;
		}
		
		print_r($_oop_html_Filter_class->data_array);
		$text = implode('',$_oop_html_Filter_class->data_array);
		file_put_contents($Article_link_list_array[$Article_link_list_array_count],$text,LOCK_EX);
		unset($_oop_html_Filter_class);
		++$Article_link_list_array_count;
	}//下载文章页面
	return $Article_link_list_array;
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$text = '';
file_put_contents('file/text.txt','',LOCK_EX);
$log_array = array();
$main_file = makefile('/','http://www.bozhidao.com','file/');
$Industry_link_array = array();
$_oop_html_Filter_class = html_Filter_function('mode:get_all:Category:->html->body->div#subnav:url:'.$main_file);
$Industry_link_array = $_oop_html_Filter_class->a_href_list;//主列表
$sub_list_array = array();
$Industry_link_array_count = 0;
while(isset($Industry_link_array[$Industry_link_array_count])){
	$main_file = makefile($Industry_link_array[$Industry_link_array_count],'http://www.bozhidao.com','file/');
	$sub_list_array = get_sub_file($Industry_link_array[$Industry_link_array_count],'http://www.bozhidao.com','file/');//获取子分页
	$Article_link_list_array = get_Article($sub_list_array);//返回文章列表
	++$Industry_link_array_count;
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>
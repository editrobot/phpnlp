<?php
class _oop_html_Filter{
	public $a_href_list = array();
	public $data_array = array();
}

class _oop_tag_dom_tree{
	public $tag = '';
	public $tag_array_count = 0;
}

function Detect_html_tag($tag_array){//检查标签
	$dom_tree_array = array();
	$dom_tree_array_count = -1;
	
	$tag_array_count = 0;
	while(isset($tag_array[$tag_array_count])){
		/*
		$temp_array = explode("_",Tag_Or_No($tag_array[$tag_array_count]));
		
		if($temp_array[0] == 'tag'){
			$tag_class = new _oop_tag_dom_tree;
			$tag_class->tag = $temp_array[1];
			$tag_class->tag_array_count = $tag_array_count;
			
			array_push($dom_tree_array,$tag_class);
			unset($tag_class);
			++$dom_tree_array_count;
		}
		else if($temp_array[0] == 'endtag'){
			if($temp_array[1] == $dom_tree_array[$dom_tree_array_count]->tag){
				array_pop($dom_tree_array);
				--$dom_tree_array_count;
			}
			else{
				$tag_array[$dom_tree_array[$dom_tree_array_count]->tag_array_count] = '';
			}
		}
		*/
		if(($tag_array[$tag_array_count] == '<li>')&&
			($tag_array[$tag_array_count+1] == '<a href="http://auto.china.com/zh_cn/xin/home/" target="_blank">')){
			$tag_array[$tag_array_count] = ' ';
		}
		++$tag_array_count;
	}
	return $tag_array;
}

function dom_temp_function($dom_array,$id_lock,$class_lock){
	$dom_temp = '';
	$dom_array_count = 0;
	while(isset($dom_array[$dom_array_count])){
		$dom_temp = $dom_temp.'->'.$dom_array[$dom_array_count]->Element_att_array['Element_name'];
		
		if(($dom_array[$dom_array_count]->Element_att_array['Element_id'] != '')&&($id_lock == 0)){
			$dom_temp = $dom_temp.'#'.$dom_array[$dom_array_count]->Element_att_array['Element_id'];
		}
		
		if(($dom_array[$dom_array_count]->Element_att_array['Element_class'] != '')&&($class_lock == 0)){
			$dom_temp = $dom_temp.'.'.$dom_array[$dom_array_count]->Element_att_array['Element_class'];
		}
		++$dom_array_count;
	}
	return $dom_temp;
}

function html_Filter_function($argument){
	$Category = '';
	$url = '';
	$mode = '';
	$id_lock = 0;
	$class_lock = 0;
	
	$argument_array = explode(":",$argument);
	unset($argument);
	$argument_array_count = 0;
	while(isset($argument_array[$argument_array_count])){
		switch($argument_array[$argument_array_count]){
			case 'Category':
				++$argument_array_count;
				$Category = $argument_array[$argument_array_count];
				break;
				
			case 'url':
				++$argument_array_count;
				$url = $argument_array[$argument_array_count];
				break;
				
			case 'mode':
				++$argument_array_count;
				$mode = $argument_array[$argument_array_count];
				break;
				
			case 'id_lock':
				++$argument_array_count;
				$id_lock = $argument_array[$argument_array_count];
				break;
				
			case 'class_lock':
				++$argument_array_count;
				$class_lock = $argument_array[$argument_array_count];
				break;
				
			default:
		}
		++$argument_array_count;
	}
	
	$return_array = array();
	$dom_array = array();//dom树
	$dom_array_count = 0;
	$dom_temp = '';

	$a_href_list = array();//超链接列表
	$lock = '1';//预先锁定
	$temp_data_array = array();//缓冲

	if($url == ''){
		return '';
	}
	//echo_m_f('读取页面：'.$url);
	$main_array = read_all_file('url:'.$url);
	//echo_m_f('分析内容！'.$url);
	$main_array_count = 0;
	while(isset($main_array[$main_array_count])){
		$div_var = new _oop_Element_attribute;
		
		$temp = Tag_Or_No($main_array[$main_array_count]);
		$dom_temp = '';
		switch($temp){
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			case 'tag_html':{
					$div_var->get_Element_attribute($main_array[$main_array_count]);
					array_push($dom_array,$div_var);
					$dom_temp = dom_temp_function($dom_array,$id_lock,$class_lock);
					if(($Category != '')&&($Category == $dom_temp)){
						$lock = '0';
					}
				}
				break;
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			case 'endtag_html':{
					$temp_array = array_pop($dom_array);
				}
				break;
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			case 'tag_body':{
					$div_var->get_Element_attribute($main_array[$main_array_count]);
					array_push($dom_array,$div_var);
					
					$dom_temp = dom_temp_function($dom_array,$id_lock,$class_lock);
					
					if(($Category != '')&&
						($Category == $dom_temp)){
						$lock = '0';
					}
				}
				break;
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			case 'endtag_body':{
					$temp_array = array_pop($dom_array);
				}
				break;
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			case 'tag_a':{
					array_push($dom_array,$div_var);
					if($lock == '0'){//检查是否锁定
						$div_var->get_Element_attribute($main_array[$main_array_count]);
						
						if((Search_array_Element($a_href_list,$div_var->Element_att_array['Element_href']) == -1)&&
							($div_var->Element_att_array['Element_href'] != '#')&&
							($div_var->Element_att_array['Element_href'] != '')){//查找列表中是否已经存在待采集列表
							
							array_push($a_href_list,$div_var->Element_att_array['Element_href']);//将连接添加到待提取页面的列表中
							//echo_m_f('提取连接:'.$div_var->Element_att_array['Element_href']);
							
						}
					}
					if(($mode == 'get_all')&&($lock == '0')){
						array_push($temp_data_array,$main_array[$main_array_count]);
					}
				}
				break;
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			case 'endtag_a':{
					$temp_array = array_pop($dom_array);
					
					if(($mode == 'get_all')&&($lock == '0')){
						array_push($temp_data_array,$main_array[$main_array_count]);
					}
				}
				break;
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			case 'tag_div':{
					$div_var->get_Element_attribute($main_array[$main_array_count]);
					array_push($dom_array,$div_var);
					
					$dom_temp = dom_temp_function($dom_array,$id_lock,$class_lock);
					
					
					if(($Category != '')&&
						($Category == $dom_temp)){
						$lock = '0';
					}
					if(($mode == 'get_all')&&($lock == '0')){
						
						array_push($temp_data_array,$main_array[$main_array_count]);
					}
				}
				break;
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			case 'endtag_div':{
					$dom_temp = dom_temp_function($dom_array,$id_lock,$class_lock);
					if(($Category != '')&&
						($Category == $dom_temp)){
						if(($mode == 'get_all')&&($lock == '0')){
							array_push($temp_data_array,$main_array[$main_array_count]);
						}
						$lock = '1';
					}
					$temp_array = array_pop($dom_array);
				}
				break;
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			case 'tag_dl':{
					$div_var->get_Element_attribute($main_array[$main_array_count]);
					array_push($dom_array,$div_var);
					
					$dom_temp = dom_temp_function($dom_array,$id_lock,$class_lock);
					if(($Category != '')&&
						($Category == $dom_temp)){
						$lock = '0';
					}
					
					if(($mode == 'get_all')&&($lock == '0')){
						array_push($temp_data_array,$main_array[$main_array_count]);
					}
				}
				break;
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			case 'endtag_dl':{
					$dom_temp = dom_temp_function($dom_array,$id_lock,$class_lock);
					if(($Category != '')&&
						($Category == $dom_temp)){
						if(($mode == 'get_all')&&($lock == '0')){
							array_push($temp_data_array,$main_array[$main_array_count]);
						}
						$lock = '1';
					}
					$temp_array = array_pop($dom_array);
				}
				break;
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			case 'tag_dt':{
					$div_var->get_Element_attribute($main_array[$main_array_count]);
					array_push($dom_array,$div_var);
					
					$dom_temp = dom_temp_function($dom_array,$id_lock,$class_lock);
					if(($Category != '')&&
						($Category == $dom_temp)){
						$lock = '0';
					}
					
					if(($mode == 'get_all')&&($lock == '0')){
						array_push($temp_data_array,$main_array[$main_array_count]);
					}
				}
				break;
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			case 'endtag_dt':{
					$dom_temp = dom_temp_function($dom_array,$id_lock,$class_lock);
					if(($Category != '')&&
						($Category == $dom_temp)){
						if(($mode == 'get_all')&&($lock == '0')){
							array_push($temp_data_array,$main_array[$main_array_count]);
						}
						$lock = '1';
					}
					$temp_array = array_pop($dom_array);
				}
				break;
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			case 'tag_table':{
					$div_var->get_Element_attribute($main_array[$main_array_count]);
					array_push($dom_array,$div_var);
					$dom_temp = dom_temp_function($dom_array,$id_lock,$class_lock);
					if(($Category != '')&&
						($Category == $dom_temp)){
						$lock = '0';
					}
					
					if(($mode == 'get_all')&&($lock == '0')){
						array_push($temp_data_array,$main_array[$main_array_count]);
					}
				}
				break;
			case 'endtag_table':{
					$dom_temp = dom_temp_function($dom_array,$id_lock,$class_lock);
					if(($Category != '')&&
						($Category == $dom_temp)){
						if(($mode == 'get_all')&&($lock == '0')){
							array_push($temp_data_array,$main_array[$main_array_count]);
						}
						$lock = '1';
					}
					$temp_array = array_pop($dom_array);
				}
				break;
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			case 'tag_tbody':{
					$div_var->get_Element_attribute($main_array[$main_array_count]);
					array_push($dom_array,$div_var);
					$dom_temp = dom_temp_function($dom_array,$id_lock,$class_lock);
					if(($Category != '')&&
						($Category == $dom_temp)){
						$lock = '0';
					}
					
					if(($mode == 'get_all')&&($lock == '0')){
						array_push($temp_data_array,$main_array[$main_array_count]);
					}
				}
				break;
			case 'endtag_tbody':{
					$dom_temp = dom_temp_function($dom_array,$id_lock,$class_lock);
					if(($Category != '')&&
						($Category == $dom_temp)){
						if(($mode == 'get_all')&&($lock == '0')){
							array_push($temp_data_array,$main_array[$main_array_count]);
						}
						$lock = '1';
					}
					$temp_array = array_pop($dom_array);
				}
				break;
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			case 'tag_ul':{
					$div_var->get_Element_attribute($main_array[$main_array_count]);
					array_push($dom_array,$div_var);
					$dom_temp = dom_temp_function($dom_array,$id_lock,$class_lock);
					if(($Category != '')&&
						($Category == $dom_temp)){
						$lock = '0';
					}
					
					if(($mode == 'get_all')&&($lock == '0')){
						array_push($temp_data_array,$main_array[$main_array_count]);
					}
				}
				break;
			case 'endtag_ul':{
					$dom_temp = dom_temp_function($dom_array,$id_lock,$class_lock);
					if(($Category != '')&&
						($Category == $dom_temp)){
						if(($mode == 'get_all')&&($lock == '0')){
							array_push($temp_data_array,$main_array[$main_array_count]);
						}
						$lock = '1';
					}
					$temp_array = array_pop($dom_array);
				}
				break;
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			case 'tag_li':{
					$div_var->get_Element_attribute($main_array[$main_array_count]);
					array_push($dom_array,$div_var);
					$dom_temp = dom_temp_function($dom_array,$id_lock,$class_lock);
					if(($Category != '')&&
						($Category == $dom_temp)){
						$lock = '0';
					}
					
					if(($mode == 'get_all')&&($lock == '0')){
						array_push($temp_data_array,$main_array[$main_array_count]);
					}
				}
				break;
			case 'endtag_li':{
					$dom_temp = dom_temp_function($dom_array,$id_lock,$class_lock);
					if(($Category != '')&&
						($Category == $dom_temp)){
						if(($mode == 'get_all')&&($lock == '0')){
							array_push($temp_data_array,$main_array[$main_array_count]);
						}
						$lock = '1';
					}
					$temp_array = array_pop($dom_array);
				}
				break;
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			default:
				if(($mode == 'get_all')&&($lock == '0')){
					array_push($temp_data_array,$main_array[$main_array_count]);
				}
		}
		unset($div_var);
		++$main_array_count;
	}
	$return_class = new _oop_html_Filter;
	$return_class->a_href_list = $a_href_list;
	$return_class->data_array = $temp_data_array;
	return $return_class;
}
?>
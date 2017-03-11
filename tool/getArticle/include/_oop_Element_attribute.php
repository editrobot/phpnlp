<?php
class _oop_Element_attribute{
	public $Element_att_array = array(
		'Element_name' => '',//Element_name
		'Element_id' => '',//Element_id
		'Element_class' => '',//Element_class
		'Element_href' => '',//Element_href
		'Element_src' => '',//Element_src
		'Element_title' => '',//Element_title
		'Element_text' => '',//Element_text
		'Element_target' => ''//Element_target
	);
	
	public function get_Element_attribute($tag){//标签属性提取
		$tag = cut_symbol_head_end($tag);//剔除括号
		
		$tag_array = cut_word($tag);
		$tag_array_count = 0;
		while(isset($tag_array[$tag_array_count])){
			switch($tag_array[$tag_array_count]){
				case 'a':
				case 'A':{
						++$tag_array_count;
						if($tag_array[$tag_array_count] == ' '){
							$this->Element_att_array['Element_name'] = 'a';
						}
					}
					break;
					
				case 'b':
				case 'B':{
						++$tag_array_count;
						if((($tag_array[$tag_array_count] == 'o')||($tag_array[$tag_array_count] == 'O'))&&
							(($tag_array[$tag_array_count+1] == 'd')||($tag_array[$tag_array_count+1] == 'D'))&&
							(($tag_array[$tag_array_count+2] == 'y')||($tag_array[$tag_array_count+2] == 'Y'))
							){
							$this->Element_att_array['Element_name'] = 'body';
							++$tag_array_count;
							++$tag_array_count;
						}
					}
					break;
				
				case 'c':
				case 'C':{
						++$tag_array_count;
						if((($tag_array[$tag_array_count] == 'l')||($tag_array[$tag_array_count] == 'L'))&&
							(($tag_array[$tag_array_count+1] == 'a')||($tag_array[$tag_array_count+1] == 'A'))&&
							(($tag_array[$tag_array_count+2] == 's')||($tag_array[$tag_array_count+2] == 'S'))&&
							(($tag_array[$tag_array_count+3] == 's')||($tag_array[$tag_array_count+3] == 'S'))&&
							($tag_array[$tag_array_count+4] == '=')&&
							($tag_array[$tag_array_count+5] == '"')
						){
							$temp = '';
							++$tag_array_count;
							++$tag_array_count;
							++$tag_array_count;
							++$tag_array_count;
							++$tag_array_count;
							++$tag_array_count;
							while(isset($tag_array[$tag_array_count])&&($tag_array[$tag_array_count] != '"')){
								$temp = $temp.$tag_array[$tag_array_count];
								++$tag_array_count;
							}
							if($temp != ''){
								$this->Element_att_array['Element_class'] = $temp;
							}
						}
						++$tag_array_count;
					}
					break;
			
				case 'd':
				case 'D':{
						++$tag_array_count;
						if((($tag_array[$tag_array_count] == 'i')||($tag_array[$tag_array_count] == 'I'))&&
							(($tag_array[$tag_array_count+1] == 'v')||($tag_array[$tag_array_count+1] == 'V'))){
							$this->Element_att_array['Element_name'] = 'div';
							++$tag_array_count;
						}
						else if(($tag_array[$tag_array_count] == 'l')||($tag_array[$tag_array_count] == 'L')){
							$this->Element_att_array['Element_name'] = 'dl';
						}
						else if(($tag_array[$tag_array_count] == 't')||($tag_array[$tag_array_count] == 'T')){
							$this->Element_att_array['Element_name'] = 'dt';
						}
					}
					break;
				
				case 'h':
				case 'H':{
						++$tag_array_count;
						if((($tag_array[$tag_array_count] == 't')||($tag_array[$tag_array_count] == 'T'))&&
							(($tag_array[$tag_array_count+1] == 'm')||($tag_array[$tag_array_count+1] == 'M'))&&
							(($tag_array[$tag_array_count+2] == 'l')||($tag_array[$tag_array_count+2] == 'L'))
							){
							$this->Element_att_array['Element_name'] = 'html';
							++$tag_array_count;
							++$tag_array_count;
						}
						else if((($tag_array[$tag_array_count] == 'r')||($tag_array[$tag_array_count] == 'R'))&&
							(($tag_array[$tag_array_count+1] == 'e')||($tag_array[$tag_array_count+1] == 'E'))&&
							(($tag_array[$tag_array_count+2] == 'f')||($tag_array[$tag_array_count+2] == 'F'))&&
							($tag_array[$tag_array_count+3] == '=')&&
							($tag_array[$tag_array_count+4] == '"')
						){
							$temp = '';
							++$tag_array_count;
							++$tag_array_count;
							++$tag_array_count;
							++$tag_array_count;
							++$tag_array_count;
							while(isset($tag_array[$tag_array_count])&&($tag_array[$tag_array_count] != '"')){
								$temp = $temp.$tag_array[$tag_array_count];
								++$tag_array_count;
							}
							if($temp != ''){
								$this->Element_att_array['Element_href'] = $temp;
							}
							++$tag_array_count;
						}
					}
					break;
				
				case 'i':
				case 'I':{
						++$tag_array_count;
						if((($tag_array[$tag_array_count] == 'd')||($tag_array[$tag_array_count] == 'D'))&&
							($tag_array[$tag_array_count+1] == '=')&&
							($tag_array[$tag_array_count+2] == '"')
						){
							$temp = '';
							++$tag_array_count;
							++$tag_array_count;
							++$tag_array_count;
							while(isset($tag_array[$tag_array_count])&&($tag_array[$tag_array_count] != '"')){
								$temp = $temp.$tag_array[$tag_array_count];
								++$tag_array_count;
							}
							if($temp != ''){
								$this->Element_att_array['Element_id'] = $temp;
							}
							++$tag_array_count;
						}
					}
					break;
					
				case 'l':
				case 'L':{
						++$tag_array_count;
						if(($tag_array[$tag_array_count] == 'i')||($tag_array[$tag_array_count] == 'I')){
							$this->Element_att_array['Element_name'] = 'li';
						}
					}
					break;
				
				case 's':
				case 'S':{
						++$tag_array_count;
						if((($tag_array[$tag_array_count] == 'r')||($tag_array[$tag_array_count] == 'R'))&&
							(($tag_array[$tag_array_count+1] == 'c')||($tag_array[$tag_array_count+1] == 'C'))&&
							($tag_array[$tag_array_count+2] == '=')&&
							($tag_array[$tag_array_count+3] == '"')
						){
							$temp = '';
							++$tag_array_count;
							++$tag_array_count;
							++$tag_array_count;
							++$tag_array_count;
							while(isset($tag_array[$tag_array_count])&&($tag_array[$tag_array_count] != '"')){
								$temp = $temp.$tag_array[$tag_array_count];
								++$tag_array_count;
							}
							if($temp != ''){
								$this->Element_att_array['Element_src'] = $temp;
							}
						}
						++$tag_array_count;
					}
					break;
				
				case 't':
				case 'T':{
						++$tag_array_count;
						if((($tag_array[$tag_array_count] == 'a')||($tag_array[$tag_array_count] == 'A'))&&
							(($tag_array[$tag_array_count+1] == 'b')||($tag_array[$tag_array_count+1] == 'B'))&&
							(($tag_array[$tag_array_count+2] == 'l')||($tag_array[$tag_array_count+2] == 'L'))&&
							(($tag_array[$tag_array_count+3] == 'e')||($tag_array[$tag_array_count+3] == 'E'))
							){
							$this->Element_att_array['Element_name'] = 'table';
							++$tag_array_count;
							++$tag_array_count;
							++$tag_array_count;
						}
						else if(($tag_array[$tag_array_count] == 'r')||($tag_array[$tag_array_count] == 'R')){
							$this->Element_att_array['Element_name'] = 'tr';
						}
						else if(($tag_array[$tag_array_count] == 'd')||($tag_array[$tag_array_count] == 'D')){
							$this->Element_att_array['Element_name'] = 'td';
						}
						else if((($tag_array[$tag_array_count] == 'b')||($tag_array[$tag_array_count] == 'B'))&&
							(($tag_array[$tag_array_count+1] == 'o')||($tag_array[$tag_array_count+1] == 'O'))&&
							(($tag_array[$tag_array_count+2] == 'd')||($tag_array[$tag_array_count+2] == 'D'))&&
							(($tag_array[$tag_array_count+3] == 'y')||($tag_array[$tag_array_count+3] == 'Y'))
							){
							$this->Element_att_array['Element_name'] = 'tbody';
							++$tag_array_count;
							++$tag_array_count;
							++$tag_array_count;
						}
						else if((($tag_array[$tag_array_count] == 'i')||($tag_array[$tag_array_count] == 'I'))&&
							(($tag_array[$tag_array_count+1] == 't')||($tag_array[$tag_array_count+1] == 'T'))&&
							(($tag_array[$tag_array_count+2] == 'l')||($tag_array[$tag_array_count+2] == 'L'))&&
							(($tag_array[$tag_array_count+3] == 'e')||($tag_array[$tag_array_count+3] == 'E'))&&
							($tag_array[$tag_array_count+4] == '=')&&
							($tag_array[$tag_array_count+5] == '"')
						){
							$temp = '';
							++$tag_array_count;
							++$tag_array_count;
							++$tag_array_count;
							++$tag_array_count;
							++$tag_array_count;
							++$tag_array_count;
							while(isset($tag_array[$tag_array_count])&&($tag_array[$tag_array_count] != '"')){
								$temp = $temp.$tag_array[$tag_array_count];
								++$tag_array_count;
							}
							if($temp != ''){
								$this->Element_att_array['Element_title'] = $temp;
							}
							++$tag_array_count;
						}
					}
					break;
					
				case 'u':
				case 'U':{
						++$tag_array_count;
						if(($tag_array[$tag_array_count] == 'l')||($tag_array[$tag_array_count] == 'L')){
							$this->Element_att_array['Element_name'] = 'ul';
						}
					}
					break;
				
				default:
			}
			++$tag_array_count;
		}
	}
}
?>
<?php
class _oopf_ql{
	public $TempFilePath = 'robot/temp/';
	public $FilePath = 'robot/word/';
	public $SegmentationTag = '?php /*';
	public $oldString_count;
	public $TempIndexCount = 1000;
	public $TempIndexFile = 'TempIndexFile';
	public $TempWordIndexFile = 5000;//缓存文件最大单位数量
	public $Res = '%XX%XX%XX';
	public $Decorate = '%YY%YY%YY';//修饰词
	public $Tool = '%ZZ%ZZ%ZZ';
	public $WordAttribute = '<Res:0:Tool:0:Decorate:0>';
	var $Suffix_name = '.php';
	var $Breakpoint_array = array(//断点标志
		//'%20%ZZ%ZZ',//空格
		'%21%ZZ%ZZ',//!感叹号
		'%22%ZZ%ZZ',//"
		//'%24%ZZ%ZZ',//$
		//'%25%ZZ%ZZ',//%
		'%28%ZZ%ZZ',//(
		'%29%ZZ%ZZ',//)
		'%40%ZZ%ZZ',//@
		'%2A%ZZ%ZZ',//*
		'%2C%ZZ%ZZ',//，
		'%3A%ZZ%ZZ',//:
		'%3C%ZZ%ZZ',//<
		'%3E%ZZ%ZZ',//>
		'%3F%ZZ%ZZ',//?
		'%5B%ZZ%ZZ',//[
		'%5D%ZZ%ZZ',//]
		'%5E%ZZ%ZZ',//^
		'%60%ZZ%ZZ',//`
		'%7B%ZZ%ZZ',//{
		'%7D%ZZ%ZZ',//}
		'%7E%ZZ%ZZ',//~
		'%99%ZZ%ZZ',//
		'%E2%80%A2',//•
		'%E3%80%82',//。
		'%EF%BC%8C',//，
		'%EF%BC%9F',//？
		'%E3%80%80',//
		'%E3%80%81',//、
		'%E3%80%8A',//《
		'%E3%80%8B',//》
		'%EF%BC%9A',//:
		'%EF%BC%9B',//；
		'%E2%80%9C',//“左双引号
		'%E2%80%9D',//”右双引号
		//'%319%ZZ%ZZ',//-
		//'%320%ZZ%ZZ',//_
		'%321%ZZ%ZZ'//.
	);
	var $SentenceBreakpoint_array = array(//句子断点
		'%E3%80%82',//。
		'%321%ZZ%ZZ',//.
		'%21%ZZ%ZZ',//!感叹号
		'%2C%ZZ%ZZ',//，
		'%3F%ZZ%ZZ',//?
		'%EF%BC%8C',//，
		'%B4%ZZ%ZZ',//|
		'%EF%BC%9F'//？
	);
	public $Letter_Number_array =  array(//对为转码进行转码
			'a'=>'%257%ZZ%ZZ','b'=>'%258%ZZ%ZZ','c'=>'%259%ZZ%ZZ','d'=>'%259%ZZ%ZZ','e'=>'%260%ZZ%ZZ',
			'f'=>'%261%ZZ%ZZ','g'=>'%262%ZZ%ZZ','h'=>'%263%ZZ%ZZ','i'=>'%264%ZZ%ZZ','j'=>'%265%ZZ%ZZ',
			'k'=>'%266%ZZ%ZZ','l'=>'%267%ZZ%ZZ','m'=>'%268%ZZ%ZZ','n'=>'%269%ZZ%ZZ','o'=>'%270%ZZ%ZZ',
			'p'=>'%271%ZZ%ZZ','q'=>'%272%ZZ%ZZ','r'=>'%273%ZZ%ZZ','s'=>'%274%ZZ%ZZ','t'=>'%275%ZZ%ZZ',
			'u'=>'%276%ZZ%ZZ','v'=>'%277%ZZ%ZZ','w'=>'%278%ZZ%ZZ','x'=>'%279%ZZ%ZZ','y'=>'%280%ZZ%ZZ',
			'z'=>'%281%ZZ%ZZ','A'=>'%282%ZZ%ZZ','B'=>'%283%ZZ%ZZ','C'=>'%284%ZZ%ZZ','D'=>'%285%ZZ%ZZ',
			'E'=>'%287%ZZ%ZZ','F'=>'%288%ZZ%ZZ','G'=>'%289%ZZ%ZZ','H'=>'%290%ZZ%ZZ','I'=>'%291%ZZ%ZZ',
			'J'=>'%292%ZZ%ZZ','K'=>'%293%ZZ%ZZ','L'=>'%294%ZZ%ZZ','M'=>'%295%ZZ%ZZ','N'=>'%296%ZZ%ZZ',
			'O'=>'%297%ZZ%ZZ','P'=>'%298%ZZ%ZZ','Q'=>'%299%ZZ%ZZ','R'=>'%300%ZZ%ZZ','S'=>'%301%ZZ%ZZ',
			'T'=>'%302%ZZ%ZZ','U'=>'%303%ZZ%ZZ','V'=>'%304%ZZ%ZZ','W'=>'%305%ZZ%ZZ','X'=>'%306%ZZ%ZZ',
			'Y'=>'%307%ZZ%ZZ','Z'=>'%308%ZZ%ZZ','0'=>'%309%ZZ%ZZ','1'=>'%310%ZZ%ZZ','2'=>'%311%ZZ%ZZ',
			'3'=>'%312%ZZ%ZZ','4'=>'%313%ZZ%ZZ','5'=>'%314%ZZ%ZZ','6'=>'%315%ZZ%ZZ','7'=>'%316%ZZ%ZZ',
			'8'=>'%317%ZZ%ZZ','9'=>'%318%ZZ%ZZ','-'=>'%319%ZZ%ZZ','_'=>'%320%ZZ%ZZ','.'=>'%321%ZZ%ZZ',
			'~'=>'%322%ZZ%ZZ'
	);
	public $Number_Letter_array =  array(
			'%257%ZZ%ZZ'=>'a','%258%ZZ%ZZ'=>'b','%259%ZZ%ZZ'=>'c','%259%ZZ%ZZ'=>'d','%260%ZZ%ZZ'=>'e',
			'%261%ZZ%ZZ'=>'f','%262%ZZ%ZZ'=>'g','%263%ZZ%ZZ'=>'h','%264%ZZ%ZZ'=>'i','%265%ZZ%ZZ'=>'j',
			'%266%ZZ%ZZ'=>'k','%267%ZZ%ZZ'=>'l','%268%ZZ%ZZ'=>'m','%269%ZZ%ZZ'=>'n','%270%ZZ%ZZ'=>'o',
			'%271%ZZ%ZZ'=>'p','%272%ZZ%ZZ'=>'q','%273%ZZ%ZZ'=>'r','%274%ZZ%ZZ'=>'s','%275%ZZ%ZZ'=>'t',
			'%276%ZZ%ZZ'=>'u','%277%ZZ%ZZ'=>'v','%278%ZZ%ZZ'=>'w','%279%ZZ%ZZ'=>'x','%280%ZZ%ZZ'=>'y',
			'%281%ZZ%ZZ'=>'z','%282%ZZ%ZZ'=>'A','%283%ZZ%ZZ'=>'B','%284%ZZ%ZZ'=>'C','%285%ZZ%ZZ'=>'D',
			'%287%ZZ%ZZ'=>'E','%288%ZZ%ZZ'=>'F','%289%ZZ%ZZ'=>'G','%290%ZZ%ZZ'=>'H','%291%ZZ%ZZ'=>'I',
			'%292%ZZ%ZZ'=>'J','%293%ZZ%ZZ'=>'K','%294%ZZ%ZZ'=>'L','%295%ZZ%ZZ'=>'M','%296%ZZ%ZZ'=>'N',
			'%297%ZZ%ZZ'=>'O','%298%ZZ%ZZ'=>'P','%299%ZZ%ZZ'=>'Q','%300%ZZ%ZZ'=>'R','%301%ZZ%ZZ'=>'S',
			'%302%ZZ%ZZ'=>'T','%303%ZZ%ZZ'=>'U','%304%ZZ%ZZ'=>'V','%305%ZZ%ZZ'=>'W','%306%ZZ%ZZ'=>'X',
			'%307%ZZ%ZZ'=>'Y','%308%ZZ%ZZ'=>'Z','%309%ZZ%ZZ'=>'0','%310%ZZ%ZZ'=>'1','%311%ZZ%ZZ'=>'2',
			'%312%ZZ%ZZ'=>'3','%313%ZZ%ZZ'=>'4','%314%ZZ%ZZ'=>'5','%315%ZZ%ZZ'=>'6','%316%ZZ%ZZ'=>'7',
			'%317%ZZ%ZZ'=>'8','%318%ZZ%ZZ'=>'9','%319%ZZ%ZZ'=>'-','%320%ZZ%ZZ'=>'_','%321%ZZ%ZZ'=>'.',
			'%322%ZZ%ZZ'=>'~'
	);
	public $singletoTriple = array(//单字转为三字
		'%20' => '%20%ZZ%ZZ',//空格
		'%21' => '%21%ZZ%ZZ',//!
		'%22' => '%22%ZZ%ZZ',//"
		'%23' => '%23%ZZ%ZZ',//#
		'%24' => '%24%ZZ%ZZ',//$
		'%25' => '%25%ZZ%ZZ',//%
		'%26' => '%26%ZZ%ZZ',//&
		'%27' => '%27%ZZ%ZZ',//'
		'%28' => '%28%ZZ%ZZ',//(
		'%29' => '%29%ZZ%ZZ',//)
		'%2A' => '%2A%ZZ%ZZ',//*
		'%2B' => '%2B%ZZ%ZZ',//+
		'%2C' => '%2C%ZZ%ZZ',//，
		'%2D' => '%2D%ZZ%ZZ',//-
		'%2E' => '%2E%ZZ%ZZ',//.
		'%2F' => '%2F%ZZ%ZZ',///
		'%3A' => '%3A%ZZ%ZZ',//:
		'%3B' => '%3B%ZZ%ZZ',//;
		'%3C' => '%3C%ZZ%ZZ',//<
		'%3D' => '%3D%ZZ%ZZ',//=
		'%3E' => '%3E%ZZ%ZZ',//>
		'%3F' => '%3F%ZZ%ZZ',//:
		'%40' => '%40%ZZ%ZZ',//@
		'%5B' => '%5B%ZZ%ZZ',//[
		'%5C' => '%5C%ZZ%ZZ',//\
		'%5D' => '%5D%ZZ%ZZ',//]
		'%5E' => '%5E%ZZ%ZZ',//^
		'%5F' => '%5F%ZZ%ZZ',//_
		'%60' => '%60%ZZ%ZZ',//`
		'%7B' => '%7B%ZZ%ZZ',//{
		'%7C' => '%7C%ZZ%ZZ',//|
		'%7D' => '%7D%ZZ%ZZ',//}
		'%7E' => '%7E%ZZ%ZZ',//~
		'%7F' => '%7F%ZZ%ZZ',// 
		'%80' => '%80%ZZ%ZZ',//€
		'%82' => '%82%ZZ%ZZ',//‚
		'%83' => '%83%ZZ%ZZ',//ƒ
		'%84' => '%84%ZZ%ZZ',//„
		'%85' => '%85%ZZ%ZZ',//…
		'%D6' => '%D6%ZZ%ZZ',//¶
		'%DC' => '%DC%ZZ%ZZ'
	);
	public $Tripletosingle = array(//三字转为单字
		'%20%ZZ%ZZ' => '%20',//空格
		'%21%ZZ%ZZ' => '%21',//!
		'%22%ZZ%ZZ' => '%22',//"
		'%23%ZZ%ZZ' => '%23',//#
		'%24%ZZ%ZZ' => '%24',//$
		'%25%ZZ%ZZ' => '%25',//%
		'%26%ZZ%ZZ' => '%26',//&
		'%27%ZZ%ZZ' => '%27',//'
		'%28%ZZ%ZZ' => '%28',//(
		'%29%ZZ%ZZ' => '%29',//)
		'%2A%ZZ%ZZ' => '%2A',//*
		'%2B%ZZ%ZZ' => '%2B',//+
		'%2C%ZZ%ZZ' => '%2C',//，
		'%2D%ZZ%ZZ' => '%2D',//-
		'%2E%ZZ%ZZ' => '%2E',//.
		'%2F%ZZ%ZZ' => '%2F',///
		'%3A%ZZ%ZZ' => '%3A',//:
		'%3B%ZZ%ZZ' => '%3B',//;
		'%3C%ZZ%ZZ' => '%3C',//<
		'%3D%ZZ%ZZ' => '%3D',//=
		'%3E%ZZ%ZZ' => '%3E',//>
		'%3F%ZZ%ZZ' => '%3F',//:
		'%40%ZZ%ZZ' => '%40',//@
		'%5B%ZZ%ZZ' => '%5B',//[
		'%5C%ZZ%ZZ' => '%5C',//\
		'%5D%ZZ%ZZ' => '%5D',//]
		'%5E%ZZ%ZZ' => '%5E',//^
		'%5F%ZZ%ZZ' => '%5F',//_
		'%60%ZZ%ZZ' => '%60',//`
		'%7B%ZZ%ZZ' => '%7B',//{
		'%7C%ZZ%ZZ' => '%7C',//|
		'%7D%ZZ%ZZ' => '%7D',//}
		'%7E%ZZ%ZZ' => '%7E',//~
		'%7F%ZZ%ZZ' => '%7F',// 
		'%80%ZZ%ZZ' => '%80',//€
		'%82%ZZ%ZZ' => '%82',//‚
		'%83%ZZ%ZZ' => '%83',//ƒ
		'%84%ZZ%ZZ' => '%84',//„
		'%85%ZZ%ZZ' => '%85',//…
		'%D6%ZZ%ZZ' => '%D6',//¶
		'%DC%ZZ%ZZ' => '%DC'
	);
	
	public function WordFilter($temp){//字符过滤
		$temp = str_replace("\n",".",$temp);
		$temp = str_replace("\r",".",$temp);
		$temp = str_replace("\t",".",$temp);
		$temp = str_replace("\r\n",".",$temp);
		$Temp = implode("",explode("\n",$temp));
		$Temp = implode("",explode("\r",$Temp));
		$Temp = implode("",explode("\r\n",$Temp));
		$Temp = implode("",explode("\t",$Temp));
		return $temp;
	}
		
	public function GETParameterInAttribute($Parameter_array,$Parameter){//获取属性的值
		$Parameter_array_count = 0;
		while(isset($Parameter_array[$Parameter_array_count])){
			if($Parameter == $Parameter_array[$Parameter_array_count]){
				++$Parameter_array_count;
				if($Parameter_array[$Parameter_array_count] == 1){
					return $Parameter_array[$Parameter_array_count];
				}
				else{
					return -1;
				}
			}
			++$Parameter_array_count;
		}
		return -1;
	}
	
	public function GETNodeattribute($temp_array,$Node){//获取节点的属性
		$Result = Search_array_Element($temp_array,'<'.$Node.'>');
		if($Result != -1){
			++$Result;
			return explode(':',cut_symbol_head_end($temp_array[$Result]));
		}
		else{
			return -1;
		}
	}
	
	public function WriteToNodeattribute($temp_array,$Node,$attribute_array){//更新节点的属性
		$Result = Search_array_Element($temp_array,'<'.$Node.'>');
		if($Result != -1){
			++$Result;
			$temp_array[$Result] = '<'.implode(':',$attribute_array).'>';
			return $temp_array;
		}
		else{
			return $temp_array;
		}
	}
	
	public function IncrementAddressNode($temp_array,$Node){//节点排序递增
		$Result = Search_array_Element($temp_array,'<'.$Node.'>');
		$three = '';
		$four = '';
		if($Result > 1){
			$three = $temp_array[$Result-2];
			$four = $temp_array[$Result-1];
			$temp_array[$Result-2] = $temp_array[$Result];
			$temp_array[$Result-1] = $temp_array[$Result+1];
			$temp_array[$Result] = $three;
			$temp_array[$Result+1] = $four;
		}
		return $temp_array;
	}
	
	public function addNode($temp_array,$Node,$Attribute){//增加节点
		if(Search_array_Element($temp_array,'<'.$Node.'>') != -1){
			return $temp_array;
		}
		else{
			return array_Insert_Variable(array_Insert_Variable($temp_array,1,$Attribute),1,'<'.$Node.'>');
		}
	}
	public function delNode($temp_array,$Node){//删除节点
		$result = Search_array_Element($temp_array,'<'.$Node.'>');
		if($result == -1){
			return $temp_array;
		}
		else{
			return array_Canceled_Variable(array_Canceled_Variable($temp_array,$result),$result);
		}
	}
	public function Transform_Letter($Letter,$mode){//对未进行转义的字符进行转码
		if($mode = 1){array_flip($this->Letter_Number_array);}
		if(isset($this->Letter_Number_array[$Letter]))
		{return $this->Letter_Number_array[$Letter];}
		else{return $Letter;}
	}
	
	public function Syntagmatic($temp_array){//组合关系，含有每个字的排序浮点
		$array_count_max = count($temp_array);
		$Factor = 1/$array_count_max;
		$Syntagmatic_array = array();
		
		$temp_array_count = 0;
		while(isset($temp_array[$temp_array_count])&&($array_count_max != $temp_array_count)){
			array_push($Syntagmatic_array,$temp_array[$temp_array_count]);
			array_push($Syntagmatic_array,($temp_array_count+1)*$Factor);
			++$temp_array_count;
		}
		return $Syntagmatic_array;
	}
	
	public function Makeindex($temp_array){//创建初始化索引资料
		$temp_array_count = 0;
		
		while(isset($temp_array[$temp_array_count])){
			if(!file_exists($this->FilePath.$temp_array[$temp_array_count].$this->Suffix_name)){
				file_put_contents($this->FilePath.$temp_array[$temp_array_count].$this->Suffix_name,'<'.$this->SegmentationTag.':'.$temp_array[$temp_array_count].'><0><*/ ?>',LOCK_EX);
			}
			++$temp_array_count;
		}
	}
	
	public function putcode($oldString){//对所有字符编码都进行十进制转换
		if(isset($oldString[$this->oldString_count])&&('%' == $oldString[$this->oldString_count])){
			$temp_code_word_array = array();
			array_push($temp_code_word_array,$oldString[$this->oldString_count]);
			++$this->oldString_count;
			array_push($temp_code_word_array,$oldString[$this->oldString_count]);
			++$this->oldString_count;
			array_push($temp_code_word_array,$oldString[$this->oldString_count]);
			return implode('',$temp_code_word_array);
		}
		else if(isset($oldString[$this->oldString_count])){
			return $this->Transform_Letter($oldString[$this->oldString_count],0);
		}
	}
	
	public function GetSingleWord($word){//取单字编码
		$word_array = explode('%',$word);
		$word_array_count = 1;
		$temp_array = array();
		while(isset($word_array[$word_array_count])){
			array_push($temp_array,'%'.$word_array[$word_array_count].'%'.$word_array[$word_array_count+1].'%'.$word_array[$word_array_count+2]);
			++$word_array_count;++$word_array_count;++$word_array_count;
		}
		return $temp_array;
	}
	
	public function Get_String($Parameter){//获取字符串并转码
		$oldString = rawurlencode($Parameter);
		$this->oldString_count = 0;
		$temp_array = array();
		
		while(isset($oldString[$this->oldString_count])){
			$chineseurlcodecount = 0;
			$temp_array_array = array();
			
			if($oldString[$this->oldString_count] == '%'){
				$temp = $oldString[$this->oldString_count].$oldString[$this->oldString_count+1].$oldString[$this->oldString_count+2];
				if(isset($this->singletoTriple[$temp]))
				{
					$chineseurlcodecount = 3;
					++$this->oldString_count;
					++$this->oldString_count;
					++$this->oldString_count;
					array_push($temp_array_array,$this->singletoTriple[$temp]);
				}
			}
			while($chineseurlcodecount != 3){//对每个字符进行分段
				if(isset($oldString[$this->oldString_count])&&('%' == $oldString[$this->oldString_count])){
					array_push($temp_array_array,$this->putcode($oldString));
					++$this->oldString_count;
					++$chineseurlcodecount;
				}
				else{
					array_push($temp_array_array,$this->putcode($oldString));
					++$this->oldString_count;
					$chineseurlcodecount = 3;
				}
			}
			--$this->oldString_count;
			array_push($temp_array,implode('',$temp_array_array));
			unset($temp_array_array);
			
			++$this->oldString_count;
		}
		return $temp_array;
	}
	
	public function Get_Code($Code){//编码转化为原文
		$Code_array = $this->GetSingleWord($Code);
		$Code_array_count = 0;
		while(isset($Code_array[$Code_array_count])){
			$temp = $Code_array[$Code_array_count];
			if(isset($this->Number_Letter_array[$temp]))
			{
				$Code_array[$Code_array_count] = $this->Number_Letter_array[$temp];
			}
			else if(isset($this->Tripletosingle[$temp])){
				$Code_array[$Code_array_count] = rawurldecode($this->Tripletosingle[$temp]);
			}
			else{
				$Code_array[$Code_array_count] = rawurldecode($temp);
			}
			++$Code_array_count;
		}
		return implode('',$Code_array);
	}
	
	public function GetCode_echo_in_array($Code_array,$Separated){//将转义字符进行原字符显示
		$temp = '';
		$Code_array_count = 0;
		while(isset($Code_array[$Code_array_count])){
			$temp = $temp.$Separated.$this->Get_Code($Code_array[$Code_array_count]);
			++$Code_array_count;
		}
		return $temp;
	}
	
	public function Sentence($Syntagmatic_array){//切割句子
		$temp_array = array();
		$Sentence_array = array();
		$Search_array_Element_Result = -1;
		$Syntagmatic_array_count = 0;
		while(isset($Syntagmatic_array[$Syntagmatic_array_count])){
			$Search_array_Element_Result = Search_array_Element($this->Breakpoint_array,$Syntagmatic_array[$Syntagmatic_array_count]);
			if($Search_array_Element_Result == -1){
				array_push($temp_array,$Syntagmatic_array[$Syntagmatic_array_count]);
			}
			else{
				if(isset($temp_array[0])){
					array_push($Sentence_array,implode('',$temp_array));
					unset($temp_array);
					$temp_array = array();
				}
				array_push($temp_array,$Syntagmatic_array[$Syntagmatic_array_count]);
				array_push($Sentence_array,implode('',$temp_array));
				unset($temp_array);
				$temp_array = array();
			}
			++$Syntagmatic_array_count;
		}
		if(isset($temp_array[0])){
			array_push($Sentence_array,implode('',$temp_array));
		}
		
		return $Sentence_array;
	}
	
	public function Sentence_Long($Syntagmatic_array){//切割句子
		$temp_array = array();
		$Sentence_array = array();
		$Search_array_Element_Result = -1;
		$Syntagmatic_array_count = 0;
		while(isset($Syntagmatic_array[$Syntagmatic_array_count])){
			$Search_array_Element_Result = Search_array_Element($this->SentenceBreakpoint_array,$Syntagmatic_array[$Syntagmatic_array_count]);
			if($Search_array_Element_Result == -1){
				array_push($temp_array,$Syntagmatic_array[$Syntagmatic_array_count]);
			}
			else{
				if(isset($temp_array[0])){
					array_push($Sentence_array,implode('',$temp_array));
					unset($temp_array);
					$temp_array = array();
				}
				array_push($temp_array,$Syntagmatic_array[$Syntagmatic_array_count]);
				array_push($Sentence_array,implode('',$temp_array));
				unset($temp_array);
				$temp_array = array();
			}
			++$Syntagmatic_array_count;
		}
		if(isset($temp_array[0])){
			array_push($Sentence_array,implode('',$temp_array));
		}
		
		return $Sentence_array;
	}
	public function get_left_right_array($word_array){
		$word_array_left = $word_array;
		$word_array_right = $word_array;
		$left_poit = 0;
		$right_poit = count($word_array)-1;
		$word_array = array();
		while(isset($word_array_left[$left_poit])){
			
			++$left_poit;
		}
	}
	
	public function Filter_After_word($word_array,$count){//获取数组右面队列
		while($count != 0){
			array_pop($word_array);
			$count--;
		}
		return $word_array;
	}
	
	public function Filter_Before_word($word_array,$count){//获取数组左面队列
		$temp_array = array();
		$word_array_count = $count;
		while(isset($word_array[$word_array_count])){
			array_push($temp_array,$word_array[$word_array_count]);
			++$word_array_count;
		}
		return $temp_array;
	}
	
	public function DefineLongestWord($word_array,$key){//确定最长的分词
		$keyWord_file_array = read_all_file('url:'.$this->FilePath.$key.$this->Suffix_name);//截入索引文件
		$word_array_count = 0;
		while(isset($word_array[$word_array_count])){
			$Result = Search_array_Element($keyWord_file_array,'<'.$word_array[$word_array_count].'>');
			if($Result != -1){
				return $word_array_count;
			}
			++$word_array_count;
		}
		return -1;//如果没有，返回-1
	}
	
	public function makeTempWord($word_array){//返回所有可能的词汇，输入编码单字
		$temp_array = array();
		$word_array_count = 0;
		while(isset($word_array[$word_array_count])){
			array_push($temp_array,implode('',$word_array));
			unset($word_array[$word_array_count]);
			++$word_array_count;
		}
		return $temp_array;
	}
	
	public function Segmentation($Sentence){//从句子中提取分词
		$Sentence_array = $this->GetSingleWord($Sentence);//把句子拆分成单字
		$Sentence_array_Length = count($Sentence_array);//提取句子长度
		
		$Sentence_array_count = $Sentence_array_Length-1;//数组最大值
		
		$keyWord_file_array = array();//单字的文件内容
		
		$unKnowKey_word = '';//未知组
		$key_word_array = array();//经过分词的队列
		
		while(isset($Sentence_array[$Sentence_array_count])){
			/////////////////////////////////////////////////////////////////////////////////////////////
			$temp_array = $this->makeTempWord($Sentence_array);//返回所有可能的词汇
			$temp_array_Max = count($temp_array);//最大匹配
			$temp_Length = $this->DefineLongestWord($temp_array,$Sentence_array[$Sentence_array_count]);//返回确定的词汇的长度
			
			if($temp_Length == -1){//如果没有，就把单字存进缓冲
				$unKnowKey_word = $Sentence_array[$Sentence_array_count].$unKnowKey_word;
				$Sentence_array = $this->Filter_After_word($Sentence_array,1);
			}
			else{
				if($unKnowKey_word != ''){
					array_push($key_word_array,$unKnowKey_word);
					$unKnowKey_word = '';
				}
				array_push($key_word_array,$temp_array[$temp_Length]);
				$Sentence_array = $this->Filter_After_word($Sentence_array,$temp_array_Max-$temp_Length);
				$Sentence_array_count = $Sentence_array_count-($temp_array_Max-$temp_Length);
				++$Sentence_array_count;
			}
			
			/////////////////////////////////////////////////////////////////////////////////////////////			
			--$Sentence_array_count;
		}
		if($unKnowKey_word != ''){
			array_push($key_word_array,$unKnowKey_word);
			$unKnowKey_word = '';
		}
		
		return MemberReverse($key_word_array);
	}
	
	public function Paragraph_Segmentation($Paragraph_Segmentation_array){//每段输入的文章中提取分词
		$temp_array = array();
		$Paragraph_Segmentation_array_count = 0;
		while(isset($Paragraph_Segmentation_array[$Paragraph_Segmentation_array_count])){
			$temp_array = array_link($temp_array,$this->Segmentation($Paragraph_Segmentation_array[$Paragraph_Segmentation_array_count]));
			++$Paragraph_Segmentation_array_count;
		}
		return $temp_array;
	}
	
	public function MakeSureSegmentation($Segmentation){//确认分词索引是否存在
		if(Search_array_Element($this->Breakpoint_array,$Segmentation) != -1){
			return '';
		}
		$GetSingleWord_array = $this->GetSingleWord($Segmentation);//把分词拆成单字
		$temp_array = read_all_file('url:'.$this->FilePath.$GetSingleWord_array[0].$this->Suffix_name);//取出第一个单字的索引
		
		$Result = Search_array_Element($temp_array,'<'.$Segmentation.'>');
		if($Result == -1){//假如分词不存在
			return '';
		}
		else{//假如分词存在
			return cut_symbol_head_end($temp_array[$Result+1]);
		}
	}
	
	public function GetUnknowSegmentationReturnArray($temp_array){//从分词数组中返回未知分词
		$unknow_array = array();
		$temp_array_count = 0;
		while(isset($temp_array[$temp_array_count])){
			if(($this->MakeSureSegmentation($temp_array[$temp_array_count]) == '')&&
				(Search_array_Element($this->Breakpoint_array,$temp_array[$temp_array_count]) == -1)){
				array_push($unknow_array,$temp_array[$temp_array_count]);
			}
			++$temp_array_count;
		}
		return $unknow_array;
	}
	
	public function StudyWord($tempSentence){//学习单个生词
		$tempSentence_array = $this->GetSingleWord($tempSentence);
		$tempSentence_array_count = 0;
		$word_array = array();
		$word_max = 0;
		
		while(isset($tempSentence_array[$tempSentence_array_count])){
			$word_array = read_all_file('url:'.$this->FilePath.$tempSentence_array[$tempSentence_array_count].$this->Suffix_name);//打开文件
			$endendtag = array_pop($word_array);
			$word_array_end = count($word_array)-1;
			$word_max = cut_symbol_head_end($word_array[$word_array_end]);
			$Result = Search_array_Element($word_array,'<'.$tempSentence.'>');
			if($Result == -1){//如果不存在
				++$word_max;
				$word_array = $this->addNode($word_array,$tempSentence,$this->WordAttribute);
				$word_array_end = count($word_array)-1;
				$word_array[$word_array_end] = '<'.$word_max.'>';
			}
			else{
				$word_array = $this->IncrementAddressNode($word_array,$tempSentence);//该节点排序递增
			}
			array_push($word_array,$endendtag);
			file_put_contents($this->FilePath.$tempSentence_array[$tempSentence_array_count].$this->Suffix_name,implode('',$word_array),LOCK_EX);
			unset($word_array);
			$word_array = array();
			++$tempSentence_array_count;
		}
	}
	
	public function DelWord($tempSentence){//删除单个生词
		$tempSentence_array = $this->GetSingleWord($tempSentence);
		$tempSentence_array_count = 0;
		$word_array = array();
		$word_max = 0;
		
		while(isset($tempSentence_array[$tempSentence_array_count])){
			$word_array = read_all_file('url:'.$this->FilePath.$tempSentence_array[$tempSentence_array_count].$this->Suffix_name);//打开文件
			array_pop($word_array);
			$word_array_end = count($word_array)-1;
			$word_max = cut_symbol_head_end($word_array[$word_array_end]);//获取词汇最大值
			$Result = Search_array_Element($word_array,'<'.$tempSentence.'>');
			if($Result != -1){
				--$word_max;
				$word_array = $this->delNode($word_array,$tempSentence);
				$word_array_end = count($word_array)-1;
				$word_array[$word_array_end] = '<'.$word_max.'>';
			}
			array_push($word_array,'<*/ ?>');
			file_put_contents($this->FilePath.$tempSentence_array[$tempSentence_array_count].$this->Suffix_name,implode('',$word_array),LOCK_EX);
			unset($word_array);
			$word_array = array();
			++$tempSentence_array_count;
		}
	}
	
	public function setWord_Attribute($tempSentence,$Reslock,$Toollock,$Decoratelock){//设置分词的资源以及工具属性为真
		$tempSentence_array = $this->GetSingleWord($tempSentence);//分割单字出来
		$tempSentence_array_count = 0;
		$word_array = array();
		$word_max = 0;
		
		while(isset($tempSentence_array[$tempSentence_array_count])){
			$word_array = read_all_file('url:'.$this->FilePath.$tempSentence_array[$tempSentence_array_count].$this->Suffix_name);//打开文件
			$endendtag = array_pop($word_array);
			$Result = Search_array_Element($word_array,'<'.$tempSentence.'>');//在文件中查找分词
			if($Result != -1){//如果找到
				$word_attribute_array = $this->GETNodeattribute($word_array,$tempSentence);
				$word_attribute_array_count = 0;
				while(isset($word_attribute_array[$word_attribute_array_count])){//属性轮询
					switch($word_attribute_array[$word_attribute_array_count]){
						case 'Res':
								++$word_attribute_array_count;
								$word_attribute_array[$word_attribute_array_count] = $Reslock|$word_attribute_array[$word_attribute_array_count];
							break;
						
						case 'Tool':
								++$word_attribute_array_count;
								$word_attribute_array[$word_attribute_array_count] = $Toollock|$word_attribute_array[$word_attribute_array_count];;
							break;
						
						case 'Decorate':
								++$word_attribute_array_count;
								$word_attribute_array[$word_attribute_array_count] = $Decoratelock|$word_attribute_array[$word_attribute_array_count];;
							break;
						
						default:
					}
					++$word_attribute_array_count;
				}
				$word_array = $this->WriteToNodeattribute($word_array,$tempSentence,$word_attribute_array);
			}
			array_push($word_array,$endendtag);
			file_put_contents($this->FilePath.$tempSentence_array[$tempSentence_array_count].$this->Suffix_name,implode('',$word_array),LOCK_EX);
			unset($word_array);
			$word_array = array();
			++$tempSentence_array_count;
		}
	}
	
	public function PutInTempIndexFile($TempIndex_array){//存放临时分词索引
		$message_array = array();//反馈信息
		if(!file_exists($this->FilePath.$this->TempIndexFile.$this->Suffix_name)){
			$temp_array = array('<?php /*>','<0><*/ ?>');
			file_put_contents($this->FilePath.$this->TempIndexFile.$this->Suffix_name,implode('',$temp_array),LOCK_EX);
			$temp = 0;
		}
		else{
			$temp_array = read_all_file('url:'.$this->FilePath.$this->TempIndexFile.$this->Suffix_name);//打开文件
			array_pop($temp_array);
			$array_count = count($temp_array)-1;
			$temp = cut_symbol_head_end($temp_array[$array_count]);
		}
		
		$result = 0;
		$TempIndex_array_count = 0;
		while(isset($TempIndex_array[$TempIndex_array_count])){
		
			$result = Search_array_Element($temp_array,'<'.$TempIndex_array[$TempIndex_array_count].'>');//搜索是否有存在该索引。
			if($result == -1){//如果不存在
				++$temp;
				$temp_array = $this->addNode($temp_array,$TempIndex_array[$TempIndex_array_count],'<1>');//添加索引
			}
			else{//如果存在
				++$result;//移动到该索引的属性位置
				$temp_array[$result] = cut_symbol_head_end($temp_array[$result]);//提取属性
				++$temp_array[$result];
				
				if($temp_array[$result] == $this->TempIndexCount){//如果该属性超过约定的临界值
					$this->indexF($this->Get_Code($TempIndex_array[$TempIndex_array_count]));//添加索引
					--$temp;
					$temp_array = $this->delNode($temp_array,$TempIndex_array[$TempIndex_array_count]);
					array_push($message_array,$TempIndex_array[$TempIndex_array_count]);
				}
				else{//如果该属性没超过约定的临界值
					$temp_array[$result] = '<'.$temp_array[$result].'>';
				}
			}
			
			++$TempIndex_array_count;
		}
		$array_count = count($temp_array)-1;
		if($temp > $this->TempWordIndexFile){
			$temp_array = $this->delNode($temp_array,cut_symbol_head_end($temp_array[$array_count-2]));
			--$temp;
		}
		$temp_array[$array_count] = '<'.$temp.'>';
		array_push($temp_array,'<*/ ?>');
		file_put_contents($this->FilePath.$this->TempIndexFile.$this->Suffix_name,implode('',$temp_array),LOCK_EX);
		return $message_array;
	}
	
	public function StudyWordInArray($temp_array){//学习多个生词
		$temp_array_count = 0;
		while(isset($temp_array[$temp_array_count])){
			if(Search_array_Element($this->Breakpoint_array,$temp_array[$temp_array_count]) == -1){//检测是否符号
				$this->StudyWord($temp_array[$temp_array_count]);
			}
			++$temp_array_count;
		}
	}
	
	public function DelWordInArray($temp_array){//删除多个生词
		$temp_array_count = 0;
		while(isset($temp_array[$temp_array_count])){
			if(Search_array_Element($this->Breakpoint_array,$temp_array[$temp_array_count]) == -1){//检测是否符号
				$this->DelWord($temp_array[$temp_array_count]);
			}
			++$temp_array_count;
		}
	}
	
	public function set_Resources_Word($temp_array,$code1,$code2,$code3){//开启分词词组的资源属性
		$temp_array_count = 0;
		while(isset($temp_array[$temp_array_count])){
			if(Search_array_Element($this->Breakpoint_array,$temp_array[$temp_array_count]) == -1){//检测是否符号
				$this->setWord_Attribute($temp_array[$temp_array_count],$code1,$code2,$code3);
			}
			++$temp_array_count;
		}
	}
	
	public function set_Decorate_Word($temp_array){//开启分词词组的修饰属性
		$temp_array_count = 0;
		while(isset($temp_array[$temp_array_count])){
			if(Search_array_Element($this->Breakpoint_array,$temp_array[$temp_array_count]) == -1){//检测是否符号
				$this->setWord_Attribute($temp_array[$temp_array_count],0,0,0);
			}
			++$temp_array_count;
		}
	}
	
	public function GetWordFileList(){//获取字符文件列表
		$temp_file_list = file_list_in_folder('folder:'.$this->FilePath);//获取资料库文件列表
		unset($temp_file_list[0]);
		$temp_file_list_count = 1;
		$Word_file_list_array = array();
		while(isset($temp_file_list[$temp_file_list_count])){
			if($temp_file_list[$temp_file_list_count] != $this->TempIndexFile.$this->Suffix_name){
				array_push($Word_file_list_array,$temp_file_list[$temp_file_list_count]);
			}
			++$temp_file_list_count;
		}
		return $Word_file_list_array;
	}

	public function GetWordInFile($file){//从文件中取得词汇
		$temp_array = read_all_file('url:'.$this->FilePath.$file);
		array_pop($temp_array);
		$temp_array_max = count($temp_array)-1;
		array_pop($temp_array);
		
		$temp_array_count = 1;
		$word_array = array();
		while(isset($temp_array[$temp_array_count])){
			$temp = cut_symbol_head_end($temp_array[$temp_array_count]);
			array_push($word_array,$temp);
			++$temp_array_count;
		}
		return $word_array;
	}
	
	public function GetResWord($file){//分词文件中提取资源词列表
		$temp_array = array();
		$word_array = $this->GetWordInFile($file);
		$word_array_count = 0;
		
		while(isset($word_array[$word_array_count])){
			if($this->GETParameterInAttribute(explode(':',$word_array[$word_array_count+1]),'Res') != -1){
				if(Search_array_Element($temp_array,$word_array[$word_array_count]) == -1){
					array_push($temp_array,$word_array[$word_array_count]);
				}
			}
			++$word_array_count;
			++$word_array_count;
		}
		//print_r($this->GetCode_echo_in_array($temp_array,''));
		return dR($temp_array);//返回词汇
	}
	
	public function GetResWordList(){//从词库中取得资源词汇列表
		$FileListArray = $this->GetWordFileList();//词汇列表
		$Word_list_array = array();
		$FileListArray_count = 0;
		while(isset($FileListArray[$FileListArray_count])){
			$Word_array = $this->GetResWord($FileListArray[$FileListArray_count]);
			
			if(isset($Word_array[0])){
				$Word_list_array = array_link($Word_list_array,$Word_array);
			}
			++$FileListArray_count;
		}
		print_r($this->GetCode_echo_in_array($Word_list_array,'<br/>'));
		return dR($Word_list_array);
	}
	
	public function GetDecorateWord($file){//分词文件中提取修饰词列表
		$temp_array = array();
		$word_array = $this->GetWordInFile($file);
		$word_array_count = 0;
		while(isset($word_array[$word_array_count])){
			if($this->GETParameterInAttribute(explode(':',$word_array[$word_array_count+1]),'Decorate') != -1){
				if(Search_array_Element($temp_array,$word_array[$word_array_count]) == -1){
					array_push($temp_array,$word_array[$word_array_count]);
				}
			}
			++$word_array_count;
			++$word_array_count;
		}
		return $temp_array;
	}
	
	public function GetDecorateWordList(){//从词库中取得修饰词词汇列表
		$FileListArray = $this->GetWordFileList();//词汇列表
		$Word_list_array = array();
		$FileListArray_count = 0;
		while(isset($FileListArray[$FileListArray_count])){
			$Word_array = $this->GetDecorateWord($FileListArray[$FileListArray_count]);
			
			if(isset($Word_array[0])){
				$Word_list_array = array_link($Word_list_array,$Word_array);
			}
			++$FileListArray_count;
		}
		return $Word_list_array;
	}
/////////////////////////////////////////////////////////////////////////////////////////////
	public function ReadArticleInFile($File){//从文件中阅读文章提取分词
		$temp = file_get_contents($File);
		$this->BSeg($temp);
		return 1;
	}
	
	public function GetCodeToFile(){//将记忆词库中的记忆编码转换成原文并输出成文件
		$DecorateWordList_array = $this->GetDecorateWordList();
		$ResWordList_array = $this->GetResWordList();
		
		file_put_contents($this->TempFilePath.'DecorateWordList'.$this->Suffix_name,$this->GetCode_echo_in_array($DecorateWordList_array,"\r\n"),LOCK_EX);
		file_put_contents($this->TempFilePath.'ResWordList'.$this->Suffix_name,$this->GetCode_echo_in_array($ResWordList_array,"\r\n"),LOCK_EX);
		return 1;
	}

	public function StudyWordInFile($File,$attribute){//在文件中提取分词。
		$handle = fopen ($File,"rb") or exit("Unable to open file!");
		$temp_array = array();
		$temp = '';
		while(!feof($handle)){
			$data = fgetc($handle);
			if(($data != "\t")&&($data != "\r")&&($data != "\n")&&($data != "\r\n")&&($data != ' ')){
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
		
		$temp_array_count = 0;
		if($attribute == 'indexF'){//中性词汇
			while(isset($temp_array[$temp_array_count])){
				$this->indexF($temp_array[$temp_array_count]);
				++$temp_array_count;
			}
		}
		return 1;
	}
		
	public function GroupOfWords($Single){//组词
		$temp_array = array();
		$Single = implode('',$this->Get_String($Single));
		if(!file_exists($this->FilePath.$Single.$this->Suffix_name)){
			array_push($temp_array,-1);
			return $temp_array;
		}
		$Words_array = read_all_file('url:'.$this->FilePath.$Single.$this->Suffix_name);
		array_pop($Words_array);array_pop($Words_array);
		
		$Words_array_count = 1;
		while(isset($Words_array[$Words_array_count])){
			array_push($temp_array,cut_symbol_head_end($Words_array[$Words_array_count]));
			++$Words_array_count;
			++$Words_array_count;
		}
		return $temp_array;
	}
	
	public function indexF($oldString){//设置中性词汇
		$temp_array = $this->Get_String($oldString);
		$this->Makeindex($temp_array);//检测创建初始化单字索引文件
		$temp_array = $this->Sentence($temp_array);//句子切割
		$this->StudyWordInArray($temp_array);
		return 1;
	}
	
	public function indexG($oldString,$code){//设置资源词汇
		$temp_array = $this->Get_String($oldString);
		$this->Makeindex($temp_array);//检测创建初始化单字索引文件
		$temp_array = $this->Sentence($temp_array);//对象切割
		$this->StudyWordInArray($temp_array);
		$this->set_Resources_Word($temp_array,$code,0,0);
		return 1;
	}
	
	public function indexH($oldString){//设置修饰词汇
		$temp_array = $this->Get_String($oldString);
		$this->Makeindex($temp_array);//检测创建初始化单字索引文件
		$temp_array = $this->Sentence($temp_array);//对象切割
		$this->StudyWordInArray($temp_array);
		$this->set_Decorate_Word($temp_array);
		return 1;
	}
	
	public function indexI($oldString){//删除词汇
		$temp_array = $this->Get_String($oldString);
		$this->Makeindex($temp_array);//检测创建初始化单字索引文件
		$temp_array = $this->Sentence($temp_array);//对象切割
		$this->DelWordInArray($temp_array);
		return 1;
	}
	
	public function Make($oldString){//内部使用的分词接口
		$oldString = $this->WordFilter($oldString);
		$temp_array = $this->Get_String($oldString);//检测创建初始化单字索引文件
		$this->Makeindex($temp_array);//检测创建初始化单字索引文件
		$temp_array = $this->Sentence($temp_array);//句子切割
		
		$temp_array = $this->Paragraph_Segmentation($temp_array);//在文章中切割分词
		return $temp_array;
	}
	
	public function BSeg($oldString){//外部使用的分词接口
		$oldString = $this->WordFilter($oldString);
		$temp_array = $this->Get_String($oldString);//检测创建初始化单字索引文件
		$this->Makeindex($temp_array);//检测创建初始化单字索引文件
		$temp_array = $this->Sentence($temp_array);//句子切割
		
		$temp_array = $this->Paragraph_Segmentation($temp_array);//在文章中切割分词
		/*
		$study_message_array = $this->PutInTempIndexFile($this->GetUnknowSegmentationReturnArray($temp_array));//将未知分词存放进缓存
		if(isset($study_message_array[0])){
			echo '<p>刚刚在分析文章的过程，已经新学习了<b>'.$this->Get_Code($study_message_array[0]).'</b>这个词汇，不过，还未定义新属性，请设置它的词性。</p>';
		}
		*/
		return $temp_array;
	}
	
	public function Word_Property($oldString){//查阅该分词的属性
		$decbin_number_array = array(
			'Noun'=>0,//名词//
			'Adjective'=>0,//形容词//
			'Verb'=>0,//动词
			'Quantifier'=>0,//量词//
			'Pronouns'=>0,//代词
			'Adverb'=>0,//副词
			'Preposition'=>0,//介词
			'Conjunction'=>0,//连词
			'Particle'=>0,//助词
			'Interjection'=>0,//叹词
			'Onomatopoeia'=>0, //拟声词
			'number' => 0
		);
		
		$temp_array = $this->Get_String($oldString);
		$this->Makeindex($temp_array);//检测创建初始化单字索引文件
		$word_array = read_all_file('url:'.$this->FilePath.$temp_array[0].$this->Suffix_name);
		$temp_word = implode('',$temp_array);
		
		if(Search_array_Element($word_array,'<'.$temp_word.'>') != -1){
			$temp_array = $this->GETNodeattribute($word_array,$temp_word);//返回数组
		}
		else{
			return $decbin_number_array;
		}
		
		$temp_array_count = 0;
		while(isset($temp_array[$temp_array_count])){
			switch($temp_array[$temp_array_count]){
				case 'Res':
					++$temp_array_count;
					return $this->AttributeTranslation($temp_array[$temp_array_count]);
					break;
				
				default:
			}
			++$temp_array_count;
		}
		return $decbin_number_array;
	}
	
	public function AttributeTranslation($decbin_number){//数字转换成属性
		$decbin_number_array = array(
			'Noun'=>0,//名词//
			'Adjective'=>0,//形容词//
			'Verb'=>0,//动词
			'Quantifier'=>0,//量词//
			'Pronouns'=>0,//代词
			'Adverb'=>0,//副词
			'Preposition'=>0,//介词
			'Conjunction'=>0,//连词
			'Particle'=>0,//助词
			'Interjection'=>0,//叹词
			'Onomatopoeia'=>0, //拟声词
			'number' => $decbin_number
		);
		if(($decbin_number&1024) == 1024){
			$decbin_number_array['Noun'] = 1;
		}
		if(($decbin_number&512) == 512){
			$decbin_number_array['Adjective'] = 1;
		}
		if(($decbin_number&256) == 256){
			$decbin_number_array['Verb'] = 1;
		}
		if(($decbin_number&128) == 128){
			$decbin_number_array['Quantifier'] = 1;
		}
		if(($decbin_number&64) == 64){
			$decbin_number_array['Pronouns'] = 1;
		}
		if(($decbin_number&32) == 32){
			$decbin_number_array['Adverb'] = 1;
		}
		if(($decbin_number&16) == 16){
			$decbin_number_array['Preposition'] = 1;
		}
		if(($decbin_number&8) == 8){
			$decbin_number_array['Conjunction'] = 1;
		}
		if(($decbin_number&4) == 4){
			$decbin_number_array['Particle'] = 1;
		}
		if(($decbin_number&2) == 2){
			$decbin_number_array['Interjection'] = 1;
		}
		if(($decbin_number&1) == 1){
			$decbin_number_array['Onomatopoeia'] = 1;
		}
		return $decbin_number_array;
	}
	public function NTA($att_array){//属性转换成数字
		$number = 0;
		if($att_array['Noun'] == 1){
			$number = $number+1024;
		}
		if($att_array['Adjective'] == 1){
			$number = $number+512;
		}
		if($att_array['Verb'] == 1){
			$number = $number+256;
		}
		if($att_array['Quantifier'] == 1){
			$number = $number+128;
		}
		if($att_array['Pronouns'] == 1){
			$number = $number+64;
		}
		if($att_array['Adverb'] == 1){
			$number = $number+32;
		}
		if($att_array['Preposition'] == 1){
			$number = $number+16;
		}
		if($att_array['Conjunction'] == 1){
			$number = $number+8;
		}
		if($att_array['Particle'] == 1){
			$number = $number+4;
		}
		if($att_array['Interjection'] == 1){
			$number = $number+2;
		}
		if($att_array['Onomatopoeia'] == 1){
			++$number;
		}
		return $number;
	}
}
?>
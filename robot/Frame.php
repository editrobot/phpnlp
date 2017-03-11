<?php
class _oopf_Frame{
	public $Unit_ql_array;
	public $Unit_n_array;
	public $Unit_longtailword_array;
	public $FilePath = 'robot/sentence/';
	public $configFile = 'robot/config.txt';
	public $Suffix_name = '.php';
	public $SegmentationTag = '?php /*';
	public $config_array = array();
	public $unknowword_array = array();
	/////////////////////////////////////////////////////////////////////////////////
	public $att_message_array = array();//全局统计数据的缓冲，包含，分词出现概率统计。
	public $Segmentation_max = 0;//全局统计
	public $max_count = 0;
	public $Noun_array = array();//名词
	public $Adjective_array = array();//形容词
	public $Verb_array = array();//动词
	public $Quantifier_array = array();//量词
	public $Pronouns_array = array();//代词
	public $Adverb_array = array();//副词
	public $Preposition_array = array();//介词
	public $Conjunction_array = array();//连词
	public $Particle_array = array();//助词
	public $Interjection_array = array();//叹词
	public $Onomatopoeia_array = array();//拟声词
	/////////////////////////////////////////////////////////////////////////////////
	public $GrammarNumber_table_array = array(//语法加权表
			'Preposition'=>11,//介词
			'Adverb'=>10,//副词
			'Conjunction'=>9,//连词
			'Particle'=>8,//助词
			'Pronouns'=>7,//代词
			'Verb'=>6,//动词
			'Onomatopoeia'=>4, //拟声词
			'Interjection'=>5,//叹词
			'Adjective'=>3,//形容词
			'Quantifier'=>2,//量词
			'Noun'=>1 //名词
		);
		
	public $SentenceNumber_table_array = array(//句意加权表
			'Noun'=>11,//名词
			'Conjunction'=>10,//连词
			'Verb'=>9,//动词
			'Quantifier'=>8,//量词
			'Adverb'=>7,//副词
			'Adjective'=>6,//形容词
			'Preposition'=>5,//介词
			'Pronouns'=>4,//代词
			'Particle'=>3,//助词
			'Interjection'=>2,//叹词
			'Onomatopoeia'=>1 //拟声词
		);
	public $chance_word_list_array = array();//同义词对照表
	
	public function _construct(){//初始化
		$this->Unit_ql_array = new _oopf_ql();
		$this->Unit_longtailword_array = new _oopf_longtailword();
		$this->config($this->configFile);
		$this->make_word_chance();
	}
	
	public function config($File){//读取配置文件
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
		if(count($temp_array)%2 != 0){
			echo '<br/>配置文件错误';
			exit;
		}
		while(isset($temp_array[$temp_array_count])&&isset($temp_array[$temp_array_count+1])){
			$key = $temp_array[$temp_array_count];
			$this->config_array[$key] = $temp_array[$temp_array_count+1];
			++$temp_array_count;
			++$temp_array_count;
		}
	}
	public function make_word_chance(){//创建同义词对照列表
		$chance_file = '%3D%ZZ%ZZ';
		if(!file_exists($this->FilePath.$chance_file.$this->Suffix_name)){return '';}
		$senten_array = read_all_file('url:'.$this->FilePath.$chance_file.$this->Suffix_name);//获取该分词的句子链
		$senten_array_count = 1;
		array_pop($senten_array);
		$max_count = array_pop($senten_array);
		$max_count = cut_symbol_head_end($max_count);//获取列表数量
		
		$senten_array_count = 2;
		while(isset($senten_array[$senten_array_count])){
			$temp_word = cut_symbol_head_end($senten_array[$senten_array_count]);
			$temp_word_array = explode(':',$temp_word);
			if(($temp_word_array[1] == '%3D%ZZ%ZZ')&&
				(count($temp_word_array) == 3)){
				if(isset($this->chance_word_list_array[$temp_word_array[0]])){
					if(Search_array_Element($this->chance_word_list_array[$temp_word_array[0]],$temp_word_array[2]) == -1){
						array_push($this->chance_word_list_array[$temp_word_array[0]],$temp_word_array[2]);
					}
				}
				else{
					$this->chance_word_list_array[$temp_word_array[0]][0] = $temp_word_array[2];
				}
				
				if(isset($this->chance_word_list_array[$temp_word_array[2]])){
					if(Search_array_Element($this->chance_word_list_array[$temp_word_array[2]],$temp_word_array[0]) == -1){
						array_push($this->chance_word_list_array[$temp_word_array[2]],$temp_word_array[0]);
					}
				}
				else{
					$this->chance_word_list_array[$temp_word_array[2]][0] = $temp_word_array[0];
				}
			}
			++$senten_array_count;
			++$senten_array_count;
		}
	}
	public function word_chance($Segmentation_array){//常用词变换
		$temp_str = implode('|',$Segmentation_array);
		$Segmentation_array = explode('|',$temp_str);
		
		$Segmentation_array_count = 0;
		while(isset($Segmentation_array[$Segmentation_array_count])){
			$temp_str = $Segmentation_array[$Segmentation_array_count];
			if(isset($this->chance_word_list_array[$temp_str])){
				$Segmentation_array[$Segmentation_array_count] = $this->chance_word_list_array[$temp_str][0];
			}
			++$Segmentation_array_count;
		}
		/*
		if(mt_rand(0,99) >50){
			$str = str_replace(' ，','',$str);
		}
		if(mt_rand(0,99) >50){
			$str = str_replace(' ,','',$str);
		}
		if(mt_rand(0,99) >50){$str = str_replace(',','，',$str);
		}
		if(mt_rand(0,99) >50){$str = str_replace('“','"',$str);
		}
		if(mt_rand(0,99) >50){
		$str = str_replace('”','"',$str);
		}
		if(mt_rand(0,99) >50){$str = str_replace('"','\'',$str);
		}
		if(mt_rand(0,99) >50){$str = str_replace('但是','但',$str);
		}
		if(mt_rand(0,99) >50){$str = str_replace('没有','没',$str);
		}
		if(mt_rand(0,99) >50){$str = str_replace('赌博','赌',$str);
		}
		if(mt_rand(0,99) >50){$str = str_replace('需要','要',$str);
		}
		if(mt_rand(0,99) >50){
		$str = str_replace('就会','就',$str);
		}
		if(mt_rand(0,99) >50){
		$str = str_replace('如果','若',$str);
		}
		if(mt_rand(0,99) >50){
		$str = str_replace('体育彩票','体彩',$str);
		}
		if(mt_rand(0,99) >50){
		$str = str_replace('现场直播','直播',$str);
		}
		if(mt_rand(0,99) >50){
		$str = str_replace('网络游戏','网游',$str);
		}
		*/
		return implode('',$Segmentation_array);
	}
	public function WordFilter($temp){//字符过滤
		$temp = str_replace("\n",".",$temp);
		$temp = str_replace("\r",".",$temp);
		$temp = str_replace("\t",".",$temp);
		$temp = str_replace("\r\n",".",$temp);
		return $temp;
	}
	
	public function GetSentenceFileList(){//获取句子链
		$temp_file_list = file_list_in_folder('folder:'.$this->FilePath);//获取资料库文件列表
		unset($temp_file_list[0]);
		return $temp_file_list;
	}
	
	public function getGrammar($Sentence){//提取语法，输入原文
		$return_array = array();
		$temp_array = $this->Unit_ql_array->Make($Sentence);
		$temp_array_count = 0;
		while(isset($temp_array[$temp_array_count])){
			$att_array = $this->Unit_ql_array->Word_Property($this->Unit_ql_array->Get_Code($temp_array[$temp_array_count]));
			array_push($return_array,$att_array['number']);
			++$temp_array_count;
		}
		return $return_array;//返回语法代码
	}
	
	public function getdiatt($temp_array,$att){//获取相应属性的分词
		$return_array = array();
		$temp_array_count = 0;
		while(isset($temp_array[$temp_array_count])){
			$att_array = $this->Unit_ql_array->Word_Property($this->Unit_ql_array->Get_Code($temp_array[$temp_array_count]));
			
			if($att_array[$att] == 1){//如果等于相同的参数
				array_push($return_array,$temp_array[$temp_array_count]);
			}
			++$temp_array_count;
		}
		return $return_array;
	}
	
	public function Makeindex($temp_array){//创建初始化句子索引资料
		$temp_array_count = 0;
		while(isset($temp_array[$temp_array_count])){
			if(!file_exists($this->FilePath.$temp_array[$temp_array_count].$this->Suffix_name)){
				file_put_contents($this->FilePath.$temp_array[$temp_array_count].$this->Suffix_name,'<'.$this->SegmentationTag.':'.$temp_array[$temp_array_count].'><0><*/ ?>',LOCK_EX);
			}
			++$temp_array_count;
		}
	}
	
	public function getSentence($word){//获取含有该编码过的分词的句子
		
		$return_array = array();
		$Sentence_array = array();
		if(!file_exists($this->FilePath.$word.$this->Suffix_name)){//打开句子索引
			return $return_array;//返回空
		}
		else{
			$temp_array = read_all_file('url:'.$this->FilePath.$word.$this->Suffix_name);
		}
		
		$temp_array_count = 1;
		array_pop($temp_array);
		array_pop($temp_array);
		
		while(isset($temp_array[$temp_array_count])){//轮询句子
			array_push($return_array,cut_symbol_head_end($temp_array[$temp_array_count]));
			++$temp_array_count;
			++$temp_array_count;
		}
		unset($temp_array);
		
		return $return_array;
	}
	
	public function exp_S($Sentence){//编制句子的索引
	
		$Segmentation_array = $this->Unit_ql_array->Make($this->Unit_ql_array->Get_Code($Sentence));//在文章中切割分词
		$unknow_word_array = array();
		$Segmentation_array_count = 0;
		while(isset($Segmentation_array[$Segmentation_array_count])){
			if($this->Unit_ql_array->MakeSureSegmentation($Segmentation_array[$Segmentation_array_count]) == ''){//确认分词索引是否存在
				array_push($unknow_word_array,$Segmentation_array[$Segmentation_array_count]);
			}
			++$Segmentation_array_count;
		}
		if(isset($unknow_word_array[0])){
			return $unknow_word_array;
		}
		
		$word_array = array();
		$word_max = 0;
		
		$this->Makeindex($Segmentation_array);//创建初始化索引资料
		
		$Segmentation_array_count = 0;
		while(isset($Segmentation_array[$Segmentation_array_count])){
			
			$word_array = read_all_file('url:'.$this->FilePath.$Segmentation_array[$Segmentation_array_count].$this->Suffix_name);//打开文件
			array_pop($word_array);
			$word_array_end = count($word_array)-1;
			$word_max = cut_symbol_head_end($word_array[$word_array_end]);//获取目前句子的数量
			$Result = Search_array_Element($word_array,'<'.$Sentence.'>');//检查队列中是否已经存在该句子.
			if($Result == -1){//如果不存在
				++$word_max;
				$SentenceAttribute = '';
				$att_array = $this->Unit_ql_array->Word_Property($this->Unit_ql_array->Get_Code($Segmentation_array[$Segmentation_array_count]));
				if(($att_array['Noun'] == 1)||($att_array['Verb'] == 1)||($att_array['Pronouns'] == 1)){
					$SentenceAttribute = implode(':',$this->Unit_ql_array->Make($this->Unit_ql_array->Get_Code($Sentence)));
				}
				$SentenceAttribute = '<'.$SentenceAttribute.'>';
				$word_array = $this->Unit_ql_array->addNode($word_array,$Sentence,$SentenceAttribute);//在该节点上添加
				$word_array_end = count($word_array)-1;
				$word_array[$word_array_end] = '<'.$word_max.'>';
			}
			else{
				++$Result;
				$SentenceAttribute = '';
				$att_array = $this->Unit_ql_array->Word_Property($this->Unit_ql_array->Get_Code($Segmentation_array[$Segmentation_array_count]));
				if(($att_array['Noun'] == 1)||($att_array['Verb'] == 1)||($att_array['Pronouns'] == 1)){
					$SentenceAttribute = implode(':',$this->Unit_ql_array->Make($this->Unit_ql_array->Get_Code($Sentence)));
				}
				$word_array[$Result] = '<'.$SentenceAttribute.'>';
				$word_array = $this->Unit_ql_array->IncrementAddressNode($word_array,$Sentence);//该节点排序递增
			}
			array_push($word_array,'<*/ ?>');
			file_put_contents($this->FilePath.$Segmentation_array[$Segmentation_array_count].$this->Suffix_name,implode('',$word_array),LOCK_EX);
			unset($word_array);
			$word_array = array();
			
			++$Segmentation_array_count;
		}
		return $unknow_word_array;
	}
	
	public function get_Adjective_Particle($sentence,$word){//提取形容词，助词
		$temp = '';
		$return_array = array();
		$att_array = array();
		$Segmentation_array = explode(':',$sentence);//分词队列
		$Segmentation_array_count = 0;
		while(isset($Segmentation_array[$Segmentation_array_count])){
			if(($Segmentation_array[$Segmentation_array_count] == $word)&&
				isset($Segmentation_array[$Segmentation_array_count-1])
			){
				$att_array = $this->Unit_ql_array->Word_Property($this->Unit_ql_array->Get_Code($Segmentation_array[$Segmentation_array_count-1]));
				if(($att_array['Adjective'] == 1)||($att_array['Particle'] == 1)){
					$temp = $Segmentation_array[$Segmentation_array_count-1].$temp;
				}
				if(isset($Segmentation_array[$Segmentation_array_count-2])){
					$att_array = $this->Unit_ql_array->Word_Property($this->Unit_ql_array->Get_Code($Segmentation_array[$Segmentation_array_count-2]));
					if(($att_array['Adjective'] == 1)||($att_array['Particle'] == 1)){
						$temp = $Segmentation_array[$Segmentation_array_count-2].$temp;
					}
				}
				if($temp != ''){
					if(($this->Unit_ql_array->Get_Code($temp) != '的')||
						($this->Unit_ql_array->Get_Code($temp) != '了')){
						array_push($return_array,$temp);
					}
					$temp = '';
				}
			}
			++$Segmentation_array_count;
		}
		if(isset($return_array[0])){
			shuffle($return_array);
			return $return_array[0];
		}
		else{
			return '';
		}
	}
	
	public function StatisticsSegmentation($Sentence){//统计全部信息
		$this->att_message_array = array();
		$this->Noun_array = array();//名词
		$this->Adjective_array = array();//形容词
		$this->Verb_array = array();//动词
		$this->Quantifier_array = array();//量词
		$this->Pronouns_array = array();//代词
		$this->Adverb_array = array();//副词
		$this->Preposition_array = array();//介词
		$this->Conjunction_array = array();//连词
		$this->Particle_array = array();//助词
		$this->Interjection_array = array();//叹词
		$this->Onomatopoeia_array = array();//拟声词
		$this->Segmentation_max = 0;//全局统计
		$this->max_count = 0;
		//////////////////////////////////////////////////////
		$max_count = 0;//最大值
		$Segmentation_array = $this->Unit_ql_array->Make($Sentence);//分词队列
		$returnsentenceStatisticsSegmentation_array = array();
		$Segmentation_array_count = 0;
		while(isset($Segmentation_array[$Segmentation_array_count])){
		//////////////////////////////////////////////////////全局计数器
			$att_message_array_count = Search_array_Element($this->att_message_array,$Segmentation_array[$Segmentation_array_count]);
			if($att_message_array_count != -1){
				++$this->att_message_array[$att_message_array_count+1];
				if($this->att_message_array[$att_message_array_count+1] > $max_count){
					++$max_count;
				}
			}
			else{
				array_push($this->att_message_array,$Segmentation_array[$Segmentation_array_count]);
				array_push($this->att_message_array,1);
				if(1 > $max_count){
					++$max_count;
				}
			}
			if(!isset($this->Unit_ql_array->Breakpoint_array[$Segmentation_array[$Segmentation_array_count]])){
				++$this->Segmentation_max;
			}
			//////////////////////////////////////////////////////全局计数器
			++$Segmentation_array_count;
		}
		$this->max_count = $max_count;
		
		$att_message_array_count = 0;
		while(isset($this->att_message_array[$att_message_array_count])){
			$att_array = $this->Unit_ql_array->Word_Property($this->Unit_ql_array->Get_Code($this->att_message_array[$att_message_array_count]));
			
			if($att_array['Noun'] == 1){array_push($this->Noun_array,$this->att_message_array[$att_message_array_count]);}
			else if($att_array['Adjective'] == 1){array_push($this->Adjective_array,$this->att_message_array[$att_message_array_count]);}
			else if($att_array['Verb'] == 1){array_push($this->Verb_array,$this->att_message_array[$att_message_array_count]);}
			else if($att_array['Quantifier'] == 1){array_push($this->Quantifier_array,$this->att_message_array[$att_message_array_count]);}
			else if($att_array['Pronouns'] == 1){array_push($this->Pronouns_array,$this->att_message_array[$att_message_array_count]);}
			else if($att_array['Adverb'] == 1){array_push($this->Adverb_array,$this->att_message_array[$att_message_array_count]);}
			else if($att_array['Preposition'] == 1){array_push($this->Preposition_array,$this->att_message_array[$att_message_array_count]);}
			else if($att_array['Conjunction'] == 1){array_push($this->Conjunction_array,$this->att_message_array[$att_message_array_count]);}
			else if($att_array['Particle'] == 1){array_push($this->Particle_array,$this->att_message_array[$att_message_array_count]);}
			else if($att_array['Interjection'] == 1){array_push($this->Interjection_array,$this->att_message_array[$att_message_array_count]);}
			else if($att_array['Onomatopoeia'] == 1){array_push($this->Onomatopoeia_array,$this->att_message_array[$att_message_array_count]);}
			
			++$att_message_array_count;
			++$att_message_array_count;
		}
		
	}
	
	public function StudyExp_SInArray($temp_array){//学习多个句子
	
		$unknowword = '';
		$unknowword_lock = 0;
		$unknow_word_array = array();
		$temp = '';
		$temp_array_count = 0;
		while(isset($temp_array[$temp_array_count])){
			if(Search_array_Element($this->Unit_ql_array->Breakpoint_array,$temp_array[$temp_array_count]) == -1){//检测是否符号
				$unknow_word_array = $this->exp_S($temp_array[$temp_array_count]);//创建例句索引
				if(isset($unknow_word_array[0])){
					$unknowword = $unknowword.'<br/>'.$this->Unit_ql_array->GetCode_echo_in_array($unknow_word_array,'<br/>');
					$unknow_word_array = array();
				}
			}
			++$temp_array_count;
		}
		if($unknowword != ''){
			echo '<br/>因为含有未知字符串:<br/>';
			echo '<br/><b>'.$unknowword.'</b><br/><br/>';
			echo '所以改部分没有执行，其他已经学习完毕，并赋予正确属性。';
		}
		else{
			echo '学习完毕!';
		}
	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function GrammarWeighted($temp_array){//语法加权
		$max = count($temp_array);//长度
		if($max > 9999){//限制长度
			exit;
		}
		
		$return_number = 0;
		$temp_array_count = 0;
		while(isset($temp_array[$temp_array_count])){
			$temp = 0;
			$att_array = $this->Unit_ql_array->Word_Property($this->Unit_ql_array->Get_Code($temp_array[$temp_array_count]));
			if($att_array['Noun'] == 1){
				$temp = $temp+$this->GrammarNumber_table_array['Noun'];
			}
			if($att_array['Conjunction'] == 1){
				$temp = $temp+$this->GrammarNumber_table_array['Conjunction'];
			}
			if($att_array['Verb'] == 1){
				$temp = $temp+$this->GrammarNumber_table_array['Verb'];
			}
			if($att_array['Quantifier'] == 1){
				$temp = $temp+$this->GrammarNumber_table_array['Quantifier'];
			}
			if($att_array['Adverb'] == 1){
				$temp = $temp+$this->GrammarNumber_table_array['Adverb'];
			}
			if($att_array['Adjective'] == 1){
				$temp = $temp+$this->GrammarNumber_table_array['Adjective'];
			}
			if($att_array['Preposition'] == 1){
				$temp = $temp+$this->GrammarNumber_table_array['Preposition'];
			}
			if($att_array['Pronouns'] == 1){
				$temp = $temp+$this->GrammarNumber_table_array['Pronouns'];
			}
			if($att_array['Particle'] == 1){
				$temp = $temp+$this->GrammarNumber_table_array['Particle'];
			}
			if($att_array['Interjection'] == 1){
				$temp = $temp+$this->GrammarNumber_table_array['Interjection'];
			}
			else{
				$temp = $temp+$this->GrammarNumber_table_array['Onomatopoeia'];
			}
			
			$return_number = $return_number+$temp;
			++$temp_array_count;
		}
		
		return $return_number;
	}
	
	public function GrammarWeightedSimilarity($Target,$Template){//语法相似度加权
		$Target_array = $this->Unit_ql_array->Make($Target);
		$Template_array = $this->Unit_ql_array->Make($Template);
		
		$Template_array_count = 0;
		while(isset($Template_array[$Template_array_count])){
			if($this->Unit_ql_array->NTA($this->Unit_ql_array->Word_Property($this->Unit_ql_array->Get_Code($Template_array[$Template_array_count]))) == 0){
				return 0;
			}
			++$Template_array_count;
		}//确认是否存在未赋予属性的分词
		
		$Target_array_count = 0;
		while(isset($Target_array[$Target_array_count])){
			if($this->Unit_ql_array->NTA($this->Unit_ql_array->Word_Property($this->Unit_ql_array->Get_Code($Target_array[$Target_array_count]))) == 0){
				return 0;
			}
			++$Target_array_count;
		}//确认是否存在未赋予属性的分词
		
		$same_array = getSame($Target_array,$Template_array);//提取相同的项
		$return_number = 0;
		
		$sameWeighted = $this->GrammarWeighted($same_array);
		$TargetWeighted = $this->GrammarWeighted($Target_array);
		$TemplateWeighted = $this->GrammarWeighted($Template_array);
		return ($sameWeighted/$TargetWeighted+$sameWeighted/$TemplateWeighted)/2;
	}
	
	public function Weighted($temp_array){//句意加权
		$max = count($temp_array);//长度
		if($max > 9999){//限制长度
			exit;
		}
		
		$return_number = 0;
		$temp_array_count = 0;
		while(isset($temp_array[$temp_array_count])){
			$temp = 0;
			$att_array = $this->Unit_ql_array->Word_Property($this->Unit_ql_array->Get_Code($temp_array[$temp_array_count]));
			$return_number = $return_number+$this->SentenceNumber_table_array['Noun']*$att_array['Noun']+$this->SentenceNumber_table_array['Conjunction']*$att_array['Conjunction']+$this->SentenceNumber_table_array['Verb']*$att_array['Verb']+$this->SentenceNumber_table_array['Quantifier']*$att_array['Quantifier']+$this->SentenceNumber_table_array['Adverb']*$att_array['Adverb']+$this->SentenceNumber_table_array['Adjective']*$att_array['Adjective']+$this->SentenceNumber_table_array['Preposition']*$att_array['Preposition']+$this->SentenceNumber_table_array['Pronouns']*$att_array['Pronouns']+$this->SentenceNumber_table_array['Particle']*$att_array['Particle']+$this->SentenceNumber_table_array['Interjection']*$att_array['Interjection']+$this->SentenceNumber_table_array['Onomatopoeia']*$att_array['Onomatopoeia'];
			
			++$temp_array_count;
		}
		
		return $return_number;
	}
	
	public function SameSentence($Target,$Template){//对句意相似度加权
		$Target_array = $this->Unit_ql_array->Make($Target);
		$Template_array = $this->Unit_ql_array->Make($Template);
		
		$Template_array_count = 0;
		while(isset($Template_array[$Template_array_count])){
			if($this->Unit_ql_array->NTA($this->Unit_ql_array->Word_Property($this->Unit_ql_array->Get_Code($Template_array[$Template_array_count]))) == 0){
				return 0;
			}
			++$Template_array_count;
		}//确认是否存在未赋予属性的分词
		
		$Target_array_count = 0;
		while(isset($Target_array[$Target_array_count])){
			if($this->Unit_ql_array->NTA($this->Unit_ql_array->Word_Property($this->Unit_ql_array->Get_Code($Target_array[$Target_array_count]))) == 0){
				return 0;
			}
			++$Target_array_count;
		}//确认是否存在未赋予属性的分词
		
		$same_array = getSame($Target_array,$Template_array);//提取相同的项
		$return_number = 0;
		
		$sameWeighted = $this->Weighted($same_array);
		$TargetWeighted = $this->Weighted($Target_array);
		$TemplateWeighted = $this->Weighted($Template_array);
		return ($sameWeighted/$TargetWeighted+$sameWeighted/$TemplateWeighted)/2;
	}

	public function Dss($Target,$Template){//检测句子相似度原文输入
		$GrammarFloating = $this->GrammarWeightedSimilarity($Target,$Template);
		
		$SameSentence = $this->SameSentence($Target,$Template);
		$return_array = array(
			'GrammarFloating' => $GrammarFloating,
			'SameSentence' => $SameSentence
		);
		return $return_array;
	}

	public function RefreshSentence($Sentence,$word){//输入句子模版原文，以及需要载入编码过的词汇，重载更新句子
		$word_array = $this->Unit_ql_array->Make($Sentence);
		$Frame_array = $this->getGrammar($Sentence);
		$att_array = $this->Unit_ql_array->Word_Property($this->Unit_ql_array->Get_Code($word));
		$Fraction_array = array();//投票
		$Frame_array_count = 0;
		while(isset($Frame_array[$Frame_array_count])){
			$FractionTemp = 0;//临时投票箱
			if(($Frame_array[$Frame_array_count]&1024 == 1024)&&($att_array['Noun'] == 1)){
				++$FractionTemp;
			}
			else if(($Frame_array[$Frame_array_count]&128 == 128)&&($att_array['Quantifier'] == 1)){
				++$FractionTemp;
			}
			array_push($Fraction_array,$FractionTemp);
			++$Frame_array_count;
		}
		
		$Fraction_array_count = 0;
		while(isset($Fraction_array[$Fraction_array_count])){
			if(1 == $Fraction_array[$Fraction_array_count]){
				$word_array[$Fraction_array_count] = $word;
				return implode('',$word_array);
			}
			++$Fraction_array_count;
		}
		return implode('',$word_array);
	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function Summary($Sentence){//摘要
		$this->StatisticsSegmentation($Sentence);
		
		$sentence = '';//句子缓冲
		$lock = 0;
		echo '简要:<br/>';
		$att_message_array_count = 0;
		while(isset($this->att_message_array[$att_message_array_count])){
			$att_array = $this->Unit_ql_array->Word_Property($this->Unit_ql_array->Get_Code($this->att_message_array[$att_message_array_count]));
			if(($att_array['Noun'] == 1)||($att_array['Verb'] == 1)||($att_array['Conjunction'] == 1)){
				$sentence = $sentence.$this->Unit_ql_array->Get_Code($this->att_message_array[$att_message_array_count]);
				++$lock;
			}
			if(($lock == 10)&&($sentence != '')){
				echo $sentence.'  。  ';
				$lock = 0;
				$sentence = '';//句子缓冲
			}
			else if(($lock == 4)&&($sentence != '')){
				echo $sentence.'  ,  ';
				$sentence = '';//句子缓冲
			}
			++$att_message_array_count;
			++$att_message_array_count;
		}
	}
	
	public function Abb_S($Segmentation){//缩写单句句子
		$att_array = array();
		$Segmentation_array = $this->Unit_ql_array->Make($Segmentation);//生成分词队列
		$Segmentation_array_count = 0;
		while(isset($Segmentation_array[$Segmentation_array_count])){
			switch ($this->Unit_ql_array->Get_Code($Segmentation_array[$Segmentation_array_count])) {
				case '的':case '地':case '得':
					if(isset($Segmentation_array[$Segmentation_array_count-1])) {
						$att_array = $this->Unit_ql_array->Word_Property($this->Unit_ql_array->Get_Code($Segmentation_array[$Segmentation_array_count-1]));
						
						if($att_array["Adjective"] == 1) {//检查是否形容词
							unset($Segmentation_array[$Segmentation_array_count-1]);
							unset($Segmentation_array[$Segmentation_array_count]);
						}
						else if(($att_array["Pronouns"] == 1)||($att_array["Noun"] == 1)){
							unset($Segmentation_array[$Segmentation_array_count]);
						}
						unset($att_array);
						$att_array = array();
					}
					else{
						unset($Segmentation_array[$Segmentation_array_count]);
					}
					break;
				
				case '日':case '个':case '间':case '天':case '人':case '米':case '尺':case '条':case '英寸':
					if(isset($Segmentation_array[$Segmentation_array_count-1])) {
						$att_array = $this->Unit_ql_array->Word_Property($this->Unit_ql_array->Get_Code($Segmentation_array[$Segmentation_array_count-1]));
						
						if($att_array["Quantifier"] == 1) {//检查是否量词
							unset($Segmentation_array[$Segmentation_array_count-1]);
							unset($Segmentation_array[$Segmentation_array_count]);
						}
						unset($att_array);
						$att_array = array();
					}
					break;
					
				default:
					$att_array = $this->Unit_ql_array->Word_Property($this->Unit_ql_array->Get_Code($Segmentation_array[$Segmentation_array_count]));
					//获取当前词汇属性
					
					if(($att_array['Noun'] == 0)&&
						($att_array['Adjective'] == 1)&&
						($att_array['Verb'] == 0)&&
						($att_array['Quantifier'] == 0)&&
						($att_array['Pronouns'] == 0)&&
						($att_array['Adverb'] == 0)&&
						($att_array['Preposition'] == 0)&&
						($att_array['Conjunction'] == 0)&&
						($att_array['Particle'] == 0)&&
						($att_array['Interjection'] == 0)&&
						($att_array['Onomatopoeia'] == 0)&&
						isset($Segmentation_array[$Segmentation_array_count+1])){
						unset($Segmentation_array[$Segmentation_array_count]);
					}
					unset($att_array);
					$att_array = array();
					break;
			}
			if (isset($Segmentation_array[$Segmentation_array_count])) {
				$att_array = $this->Unit_ql_array->Word_Property($this->Unit_ql_array->Get_Code($Segmentation_array[$Segmentation_array_count]));
				if(($att_array['Noun'] == 0)&&($att_array['Adjective'] == 0)&&($att_array['Verb'] == 0)&&($att_array['Quantifier'] == 0)&&
					($att_array['Pronouns'] == 0)&&($att_array['Adverb'] == 0)&&($att_array['Preposition'] == 0)&&($att_array['Conjunction'] == 0)&&
					($att_array['Particle'] == 0)&&($att_array['Interjection'] == 0)&&($att_array['Onomatopoeia'] == 0)){//检查是否是未定义的词汇
						unset($Segmentation_array[$Segmentation_array_count]);
				}
				elseif ((($att_array['Noun'] == 0)&&($att_array['Adjective'] == 0)&&
					($att_array['Verb'] == 0)&&($att_array['Quantifier'] == 0)&&
					($att_array['Pronouns'] == 0)&&($att_array['Adverb'] == 1)&&//副词
					($att_array['Preposition'] == 0)&&($att_array['Conjunction'] == 0)&&
					($att_array['Particle'] == 0)&&($att_array['Interjection'] == 0)&&
					($att_array['Onomatopoeia'] == 0))||
					(($att_array['Noun'] == 0)&&($att_array['Adjective'] == 1)&&//形容词
					($att_array['Verb'] == 0)&&($att_array['Quantifier'] == 0)&&
					($att_array['Pronouns'] == 0)&&($att_array['Adverb'] == 1)&&//副词
					($att_array['Preposition'] == 0)&&($att_array['Conjunction'] == 0)&&
					($att_array['Particle'] == 0)&&($att_array['Interjection'] == 0)&&
					($att_array['Onomatopoeia'] == 0))//检查是否副词 或者 形容词加副词
					) {
						unset($Segmentation_array[$Segmentation_array_count]);
				}
				elseif (($att_array['Noun'] == 0)&&($att_array['Adjective'] == 0)&&
					($att_array['Verb'] == 0)&&($att_array['Quantifier'] == 1)&&//量词
					($att_array['Pronouns'] == 0)&&($att_array['Adverb'] == 0)&&
					($att_array['Preposition'] == 0)&&($att_array['Conjunction'] == 0)&&
					($att_array['Particle'] == 0)&&($att_array['Interjection'] == 0)&&
					($att_array['Onomatopoeia'] == 0)) {//是否量词
						unset($Segmentation_array[$Segmentation_array_count]);
				}
				elseif (($att_array['Noun'] == 0)&&($att_array['Adjective'] == 0)&&
					($att_array['Verb'] == 0)&&($att_array['Quantifier'] == 1)&&//量词
					($att_array['Pronouns'] == 0)&&($att_array['Adverb'] == 1)&&//副词
					($att_array['Preposition'] == 0)&&($att_array['Conjunction'] == 0)&&
					($att_array['Particle'] == 0)&&($att_array['Interjection'] == 0)&&
					($att_array['Onomatopoeia'] == 0)) {//是否量词加副词
						unset($Segmentation_array[$Segmentation_array_count]);
				}
				elseif (($att_array['Noun'] == 0)&&($att_array['Adjective'] == 0)&&
					($att_array['Verb'] == 0)&&($att_array['Quantifier'] == 1)&&//量词
					($att_array['Pronouns'] == 0)&&($att_array['Adverb'] == 1)&&//副词
					($att_array['Preposition'] == 0)&&($att_array['Conjunction'] == 0)&&
					($att_array['Particle'] == 0)&&($att_array['Interjection'] == 0)&&
					($att_array['Onomatopoeia'] == 0)) {//是否量词加副词
						unset($Segmentation_array[$Segmentation_array_count]);
				}
				elseif (($att_array['Noun'] == 0)&&($att_array['Adjective'] == 1)&&
					($att_array['Verb'] == 0)&&($att_array['Quantifier'] == 1)&&
					($att_array['Pronouns'] == 0)&&($att_array['Adverb'] == 1)&&
					($att_array['Preposition'] == 0)&&($att_array['Conjunction'] == 0)&&
					($att_array['Particle'] == 0)&&($att_array['Interjection'] == 0)&&
					($att_array['Onomatopoeia'] == 0)){//是否形容词，副词，量词
						unset($Segmentation_array[$Segmentation_array_count]);
				}
				elseif (($att_array['Noun'] == 0)&&($att_array['Adjective'] == 0)&&
					($att_array['Verb'] == 0)&&($att_array['Quantifier'] == 0)&&
					($att_array['Pronouns'] == 0)&&($att_array['Adverb'] == 0)&&
					($att_array['Preposition'] == 0)&&($att_array['Conjunction'] == 0)&&
					($att_array['Particle'] == 0)&&($att_array['Interjection'] == 1)&&//叹词
					($att_array['Onomatopoeia'] == 0)) {//是否叹词
						unset($Segmentation_array[$Segmentation_array_count]);
				}
				elseif (($att_array['Noun'] == 0)&&($att_array['Adjective'] == 0)&&
					($att_array['Verb'] == 0)&&($att_array['Quantifier'] == 0)&&
					($att_array['Pronouns'] == 0)&&($att_array['Adverb'] == 1)&&
					($att_array['Preposition'] == 0)&&($att_array['Conjunction'] == 1)&&
					($att_array['Particle'] == 0)&&($att_array['Interjection'] == 0)&&
					($att_array['Onomatopoeia'] == 0)) {//是否叹词
						unset($Segmentation_array[$Segmentation_array_count]);
				}
				elseif (($att_array['Noun'] == 0)&&($att_array['Adjective'] == 1)&&
					($att_array['Verb'] == 0)&&($att_array['Quantifier'] == 0)&&
					($att_array['Pronouns'] == 0)&&($att_array['Adverb'] == 0)&&
					($att_array['Preposition'] == 0)&&($att_array['Conjunction'] == 1)&&
					($att_array['Particle'] == 0)&&($att_array['Interjection'] == 0)&&
					($att_array['Onomatopoeia'] == 0)) {//是否叹词
						unset($Segmentation_array[$Segmentation_array_count]);
				}
				elseif (($att_array['Noun'] == 0)&&($att_array['Adjective'] == 0)&&
					($att_array['Verb'] == 1)&&($att_array['Quantifier'] == 0)&&
					($att_array['Pronouns'] == 0)&&($att_array['Adverb'] == 1)&&
					($att_array['Preposition'] == 1)&&($att_array['Conjunction'] == 0)&&
					($att_array['Particle'] == 0)&&($att_array['Interjection'] == 0)&&
					($att_array['Onomatopoeia'] == 0)) {
						$att_array = $this->Unit_ql_array->Word_Property($this->Unit_ql_array->Get_Code($Segmentation_array[$Segmentation_array_count+1]));
						if (($att_array['Noun'] == 1)&&($att_array['Adjective'] == 0)&&
							($att_array['Verb'] == 0)&&($att_array['Quantifier'] == 0)&&
							($att_array['Pronouns'] == 0)&&($att_array['Adverb'] == 0)&&
							($att_array['Preposition'] == 0)&&($att_array['Conjunction'] == 0)&&
							($att_array['Particle'] == 0)&&($att_array['Interjection'] == 0)&&//叹词
							($att_array['Onomatopoeia'] == 0)){
								unset($Segmentation_array[$Segmentation_array_count]);
								unset($Segmentation_array[$Segmentation_array_count+1]);
								++$Segmentation_array_count;
							}
				}
				
				unset($att_array);
				$att_array = array();
			}

			++$Segmentation_array_count;
		}
		return $this->Unit_ql_array->Get_Code($this->word_chance($Segmentation_array));
	}
	
	public function Expan_S($Sentence){//扩写单句句子
		$Sentence = $this->Abb_S($Sentence);
		$Segmentation_array = $this->Unit_ql_array->Make($Sentence);//在文章中切割分词
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$Adjective_Particle_list_array = array();//助词 形容词缓冲列表
		$att_array = array();
		$Segmentation_temp = '';//生成后的句子
		$Segmentation_array_count = 0;
		while(isset($Segmentation_array[$Segmentation_array_count])){
			$lock = 0;//扩写开关
			$temp = $Segmentation_array[$Segmentation_array_count];
			$att_array = $this->Unit_ql_array->Word_Property($this->Unit_ql_array->Get_Code($temp));
			if($att_array['Noun'] == 1){
				$lock = 1;
			}
			
			if($lock == 1){
				//$Particle = array();//助词
				//$Adjective = array();//形容词
				$senten_array = array();
				if(file_exists($this->FilePath.$temp.$this->Suffix_name)){
					$senten_array = read_all_file('url:'.$this->FilePath.$temp.$this->Suffix_name);//获取该分词的句子链
					array_pop($senten_array);
					array_pop($senten_array);
				}//获取句子例句链
				
				$senten_array_count = 2;
				while(isset($senten_array[$senten_array_count])){
					
					if($senten_array[$senten_array_count] != '<>'){
						//$Particle = $this->getdiatt(explode(':',cut_symbol_head_end($senten_array[$senten_array_count])),'Particle');
						//$Adjective = $this->getdiatt(explode(':',cut_symbol_head_end($senten_array[$senten_array_count])),'Adjective');
						$ap = $this->get_Adjective_Particle(cut_symbol_head_end($senten_array[$senten_array_count]),$Segmentation_array[$Segmentation_array_count]); //扩写词汇
					}
					else{$ap = '';}
					
					if($ap != ''){
						$Segmentation_temp = $Segmentation_temp.$ap;
						break;
					}
					
					++$senten_array_count;
					++$senten_array_count;
				}
				$Segmentation_temp = $Segmentation_temp.$Segmentation_array[$Segmentation_array_count];
				$lock = 0;
			}else{
				$Segmentation_temp = $Segmentation_temp.$temp;
			}
			
			++$Segmentation_array_count;
		}
		return $this->Unit_ql_array->Get_Code($Segmentation_temp);
	}
	
	public function rewrite($Sentence){//句子改写
		$Grammar_array = array();//语法队列
		$exp_sentence_array = array();//例句队列
		$word_array = $this->Unit_ql_array->Make($Sentence);//对句子切割分词
		$sentence_code = implode('',$word_array);
		/////////////////////////////////////////////////////////////////
		$word_array_count = 0;
		while(isset($word_array[$word_array_count])){
			$att_array = $this->Unit_ql_array->Word_Property($this->Unit_ql_array->Get_Code($word_array[$word_array_count]));//获取分词属性
			$sentence_array = $this->getSentence($word_array[$word_array_count]);//返回句子模版
			dR($sentence_array);//清除重复
			
			if(isset($this->Noun_array[0])&&isset($sentence_array[0])){
				shuffle($this->Noun_array);
				shuffle($sentence_array);
				$temp_sentence = $this->RefreshSentence($this->Unit_ql_array->Get_Code($sentence_array[0]),$word_array[$word_array_count]);
				if($temp_sentence != $sentence_code){
					array_push($exp_sentence_array,$temp_sentence);
				}
			}
			else if(isset($this->Quantifier_array[0])&&isset($sentence_array[0])){
				shuffle($this->Quantifier_array);
				shuffle($sentence_array);
				$temp_sentence = $this->RefreshSentence($this->Unit_ql_array->Get_Code($sentence_array[0]),$word_array[$word_array_count]);
				if($temp_sentence != $sentence_code){
					array_push($exp_sentence_array,$temp_sentence);
				}
			}
			
			++$word_array_count;
		}//循环获取句子模版
		
		if(isset($exp_sentence_array[0])){
			$FractionCount = 0;//临时投票箱
			$FractionMax = 0;//最大分数
			$temp = 0;
			shuffle($exp_sentence_array);
			$exp_sentence_array_count = 0;
			while(isset($exp_sentence_array[$exp_sentence_array_count])){
				$temp = $this->SameSentence($Sentence,$this->Unit_ql_array->Get_Code($exp_sentence_array[$exp_sentence_array_count]));
				if($temp > $FractionMax){
					$FractionCount = $exp_sentence_array_count;
				}
				++$exp_sentence_array_count;
			}
			
			return $this->Unit_ql_array->Get_Code($exp_sentence_array[$FractionCount]);
		}
		else{
			return $Sentence;
		}
	}
	
	public function DetectionOfWrongSentences($Sentences){//检测单句句子的病句
		$sentences_array = array();//参考的句子队列
		$temp_array = $this->Unit_ql_array->Make($Sentences);
		$temp_array_count = 0;
		while(isset($temp_array[$temp_array_count])){
			array_push($sentences_array,$this->getSentence($temp_array[$temp_array_count]));//获取相关的句子队列
			$sentences_array = dR($sentences_array);//清除重复
			++$temp_array_count;
		}
		
		$sentences_array_count = 0;
		while(isset($sentences_array[$sentences_array_count])){
			
			++$temp_array_count;
		}
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function setword($word,$att){//筛选分词
		//$this->Unit_ql_array->indexI($word);
		$erro_message = '';
		$att_array = explode(',',$att);
		$att_array_count = 0;
		while(isset($att_array[$att_array_count])){
			$key = $att_array[$att_array_count];
			if(isset($this->config_array[$key])&&$key != ''){
				switch($this->config_array[$key]){
					case 'Noun'://名词
						$this->Unit_ql_array->indexG($word,1024);
						break;
					case 'Adjective'://形容词
						$this->Unit_ql_array->indexG($word,512);
						break;
					case 'Verb'://动词
						$this->Unit_ql_array->indexG($word,256);
						break;
					case 'Quantifier'://量词
						$this->Unit_ql_array->indexG($word,128);
						break;
					case 'Pronouns'://代词
						$this->Unit_ql_array->indexG($word,64);
						break;
					case 'Adverb'://副词
						$this->Unit_ql_array->indexG($word,32);
						break;
					case 'Preposition'://介词
						$this->Unit_ql_array->indexG($word,16);
						break;
					case 'Conjunction'://连词
						$this->Unit_ql_array->indexG($word,8);
						break;
					case 'Particle'://助词
						$this->Unit_ql_array->indexG($word,4);
						break;
					case 'Interjection'://叹词
						$this->Unit_ql_array->indexG($word,2);
						break;
					case 'Onomatopoeia'://拟声词
						$this->Unit_ql_array->indexG($word,1);
						break;
						
					default:
						$erro_message = $erro_message.'<br/><b>'.$word.'</b>含有未识别词汇属性:<b>'.$key.'</b><br/>';
						$this->Unit_ql_array->indexG($word,0);
				}
			}
			else{
				$erro_message = $erro_message.'<br/><b>'.$word.'</b>含有未识别词汇属性:'.$key.'<br/>';
				$this->Unit_ql_array->indexG($word,0);
			}
			++$att_array_count;
		}
		return $erro_message;
	}
	
	public function StudyWordInFile($File,$lock){//在文件中提取分词。
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
		
		$code_array = $this->Unit_ql_array->Get_String($temp_array[0]);
		if($code_array[0] != '%E8%AF%8D'){
			echo '<br/>请在第一行加上"词"字，并换行。并且文件编码为utf-8格式。';
			echo '<br/><br/>示例：<br/><img src="123exp.jpg">';
			return '';
		}
		
		if($lock == 'att'){
			
			$erro_message = '';
			$temp_array_count = 1;
			while(isset($temp_array[$temp_array_count])&&isset($temp_array[$temp_array_count+1])){
				$erro_message = $erro_message.$this->setword($temp_array[$temp_array_count],$temp_array[$temp_array_count+1]);
				++$temp_array_count;
				++$temp_array_count;
			}
			echo $erro_message;
		}
		else{
			$temp_array_count = 1;
			while(isset($temp_array[$temp_array_count])){
				$this->Unit_ql_array->indexG($temp_array[$temp_array_count],0);
				++$temp_array_count;
			}
		}
	}
	
	public function studysenteninfile($File){//在文件中学习句子
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
		$code_array = $this->Unit_ql_array->Get_String($temp_array[0]);
		if($code_array[0] != '%E5%8F%A5'){
			echo '<br/>请在第一行加上"章"字，并换行。并且文件编码为utf-8格式。';
			echo '<br/><br/>示例：<br/><img src="123exp.jpg">';
			return '';
		}
		
		$temp_array_count = 1;
		while(isset($temp_array[$temp_array_count])){
			$this->indexS($temp_array[$temp_array_count]);
			++$temp_array_count;
		}
	}
	
	public function attwordtodo($File){//关键词流水线处理
		$temp = implode('',$this->Unit_longtailword_array->Participledoing($File));//将标注了词性的文件进行标准格式化。
		file_put_contents($File,$temp,LOCK_EX);//对文件进行更新
		$this->StudyWordInFile($File,'att');//导入数据库
	}
	
	public function longtailwordtodo($oldString){//长尾词处理
		$this->Unit_longtailword_array->_construct();
		$this->Unit_longtailword_array->cutlongtailword($oldString);
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function Noun($oldString){//设置名词
		if($this->Unit_ql_array->indexG($oldString,1024) == 1){
			echo '<br/>"'.$oldString.'"名词设置完成！';
		}
		else{
			echo '<br/>"'.$oldString.'"名词设置不成功！';
		}
	}
	public function Adjective($oldString){//设置形容词
		if($this->Unit_ql_array->indexG($oldString,512) == 1){
			echo '<br/>"'.$oldString.'"形容词设置完成！';
		}
		else{
			echo '<br/>"'.$oldString.'"形容词设置不成功！';
		}
	}
	public function Verb($oldString){//设置动词
		if($this->Unit_ql_array->indexG($oldString,256) == 1){
			echo '<br/>"'.$oldString.'"动词设置完成！';
		}
		else{
			echo '<br/>"'.$oldString.'"动词设置不成功！';
		}
	}
	public function Quantifier($oldString){//设置量词
		if($this->Unit_ql_array->indexG($oldString,128) == 1){
			echo '<br/>"'.$oldString.'"量词设置完成！';
		}
		else{
			echo '<br/>"'.$oldString.'"量词设置不成功！';
		}
	}
	public function Pronouns($oldString){//设置代词
		if($this->Unit_ql_array->indexG($oldString,64) == 1){
			echo '<br/>"'.$oldString.'"代词设置完成！';
		}
		else{
			echo '<br/>"'.$oldString.'"代词设置不成功！';
		}
	}
	public function Adverb($oldString){//设置副词
		if($this->Unit_ql_array->indexG($oldString,32) == 1){
			echo '<br/>"'.$oldString.'"副词设置完成！';
		}
		else{
			echo '<br/>"'.$oldString.'"副词设置不成功！';
		}
	}
	public function Preposition($oldString){//设置介词
		if($this->Unit_ql_array->indexG($oldString,16) == 1){
			echo '<br/>"'.$oldString.'"介词设置完成！';
		}
		else{
			echo '<br/>"'.$oldString.'"介词设置不成功！';
		}
	}
	public function Conjunction($oldString){//设置连词
		if($this->Unit_ql_array->indexG($oldString,8) == 1){
			echo '<br/>"'.$oldString.'"连词设置完成！';
		}
		else{
			echo '<br/>"'.$oldString.'"连词设置不成功！';
		}
	}
	public function Particle($oldString){//设置助词
		if($this->Unit_ql_array->indexG($oldString,4) == 1){
			echo '<br/>"'.$oldString.'"助词设置完成！';
		}
		else{
			echo '<br/>"'.$oldString.'"助词设置不成功！';
		}
	}
	public function Interjection($oldString){//设置叹词
		if($this->Unit_ql_array->indexG($oldString,2) == 1){
			echo '<br/>"'.$oldString.'"叹词设置完成！';
		}
		else{
			echo '<br/>"'.$oldString.'"叹词设置不成功！';
		}
	}
	public function Onomatopoeia($oldString){//设置拟声词
		if($this->Unit_ql_array->indexG($oldString,1) == 1){
			echo '<br/>"'.$oldString.'"拟声词设置完成！';
		}
		else{
			echo '<br/>"'.$oldString.'"拟声词设置不成功！';
		}
	}
	
	public function indexH($oldString){//设置修饰词
		if($this->Unit_ql_array->indexH($oldString) == 1){
			echo '<br/>"'.$oldString.'"修饰词设置完成！';
		}
		else{
			echo '<br/>"'.$oldString.'"修饰词设置不成功！';
		}
	}
	public function del_Sentenceword_list($Sentence){
		$del_Sentenceword_list_array = array();
		$temp_array = $this->Unit_ql_array->Get_String($Sentence);
		$Sentence = implode('',$temp_array);
		while(isset($temp_array[0])){
			$tempT_array = $this->Unit_ql_array->makeTempWord($temp_array);
			$del_Sentenceword_list_array = array_link($del_Sentenceword_list_array,$tempT_array);
			array_pop($temp_array);
		}
		$del_file_list = array();
		$del_Sentenceword_list_array = dR($del_Sentenceword_list_array);//清清除重复的词汇
		$del_Sentenceword_list_array_count = 0;
		while(isset($del_Sentenceword_list_array[$del_Sentenceword_list_array_count])){
			$temp = $del_Sentenceword_list_array[$del_Sentenceword_list_array_count];//分词
			if(file_exists($this->FilePath.$temp.$this->Suffix_name)){
				$word_array = read_all_file('url:'.$this->FilePath.$temp.$this->Suffix_name);//打开文件
				array_pop($word_array);
				$word_array_end = count($word_array)-1;
				$word_max = cut_symbol_head_end($word_array[$word_array_end]);//获取例句最大值
				
				$Result = Search_array_Element($word_array,'<'.$Sentence.'>');
				if($Result != -1){
					--$word_max;
					$word_array = $this->Unit_ql_array->delNode($word_array,$Sentence);
					$word_array_end = count($word_array)-1;
					$word_array[$word_array_end] = '<'.$word_max.'>';
				}
				array_push($word_array,'<*/ ?>');
				file_put_contents($this->FilePath.$temp.$this->Suffix_name,implode('',$word_array),LOCK_EX);
				unset($word_array);
				$word_array = array();
				array_push($del_file_list,$temp);
			}
			++$del_Sentenceword_list_array_count;
		}
		return $del_file_list;
	}
	
	public function indexI($oldString){//删除词汇
		$this->Unit_ql_array->indexI($oldString);
		$temp_array = $this->Unit_ql_array->Get_String($oldString);
		$this->Unit_ql_array->Makeindex($temp_array);//检测创建初始化单字索引文件
		$del_list = $this->Unit_ql_array->Sentence($temp_array);//待删除的句子列表
		print_r($del_list);
		$del_list_count = 0;
		while(isset($del_list[$del_list_count])){
			$this->del_Sentenceword_list($this->Unit_ql_array->Get_Code($del_list[$del_list_count]));
			++$del_list_count;
		}
		
	}
	
	public function indexF($oldString){//设置中性词汇
		if($this->Unit_ql_array->indexF($oldString) == 1){
			echo '<br/>"'.$oldString.'"修饰词设置完成！';
		}
		else{
			echo '<br/>"'.$oldString.'"修饰词设置不成功！';
		}
	}
	
	public function indexS($oldString){//学习句子
		$oldString = $this->WordFilter($oldString);
		$temp_array = $this->Unit_ql_array->Get_String($oldString);
		$temp_array = $this->Unit_ql_array->Sentence($temp_array);//句子切割
		$this->StudyExp_SInArray($temp_array);
	}
		
	public function Word_Property($Temp){//返回分词属性
		$temp_array = $this->Unit_ql_array->Word_Property($Temp);
		$att = '';
		$color = '#000000;';
		if(($temp_array['Noun'] == 0)&&
			($temp_array['Adjective'] == 0)&&
			($temp_array['Verb'] == 0)&&
			($temp_array['Quantifier'] == 0)&&
			($temp_array['Pronouns'] == 0)&&
			($temp_array['Adverb'] == 0)&&
			($temp_array['Preposition'] == 0)&&
			($temp_array['Conjunction'] == 0)&&
			($temp_array['Particle'] == 0)&&
			($temp_array['Interjection'] == 0)&&
			($temp_array['Onomatopoeia'] == 0)){
			$color = '#c0c0c0;';
		}
		else{
			$att = '名词->'.$temp_array['Noun'].'|'.
				'形容词->'.$temp_array['Adjective'].'|'.
				'动词->'.$temp_array['Verb'].'|'.
				'量词->'.$temp_array['Quantifier'].'|'.
				'代词->'.$temp_array['Pronouns'].'|'.
				'副词->'.$temp_array['Adverb'].'|'.
				'介词->'.$temp_array['Preposition'].'|'.
				'连词->'.$temp_array['Conjunction'].'|'.
				'助词->'.$temp_array['Particle'].'|'.
				'叹词->'.$temp_array['Interjection'].'|'.
				'拟声词->'.$temp_array['Onomatopoeia'];
		}

		/*
		$pinyin = '';
		$code_array = $this->Unit_ql_array->Get_String($Temp);//拆分单字符
		$code_array_count = 0;
		while(isset($code_array[$code_array_count])){
			if(isset($this->Unit_ql_array->Number_Letter_array[$code_array[$code_array_count]])||
				isset($this->Unit_ql_array->Tripletosingle[$code_array[$code_array_count]])
				){
				$pinyin = $pinyin.'_'.$this->Unit_ql_array->Get_Code($code_array[$code_array_count]);
			}
			else{
				$pinyin = $pinyin.'_'.get_pinyin_array(iconv('UTF-8','GB2312',$this->Unit_ql_array->Get_Code($code_array[$code_array_count])));
			}
			++$code_array_count;
		}
		
		echo '<b title="'.$att.'">'.$Temp.'(.'.$pinyin.'.)</b>';
		*/
		if($color == '#c0c0c0;'){
			array_push($this->unknowword_array,$Temp);
		}
		return '<b style="color:'.$color.'" title="'.$att.'">'.$Temp.'</b>';
	}
	
	public function Analysisword($oldString){//分析
		$temp_array = $this->Unit_ql_array->BSeg($oldString);//切割分词
		
		$word_number = 0;
		echo '<div>';
		$temp_array_count = 0;
		while(isset($temp_array[$temp_array_count])){
			echo '|'.$this->Word_Property($this->Unit_ql_array->Get_Code($temp_array[$temp_array_count]));
			++$word_number;
			++$temp_array_count;
		}
		echo '</div><br/><br/>词汇数:'.$word_number.'<br/>';
		
		$att_message_array_count = 0;
		while(isset($this->att_message_array[$att_message_array_count])){
			echo '<b>'.$this->Unit_ql_array->Get_Code($this->att_message_array[$att_message_array_count]).'</b>字的出现次数:'.$this->att_message_array[$att_message_array_count+1].'次<br/>';
			++$att_message_array_count;
			++$att_message_array_count;
		}
		
		echo '未知词汇:<br/>';
		$unknowword_array_count = 0;
		while(isset($this->unknowword_array[$unknowword_array_count])){
			echo $this->unknowword_array[$unknowword_array_count].'<br/>';
			++$unknowword_array_count;
		}
		$this->unknowword_array = array();
	}
	
	public function Detecterro($oldString){//检测病句
		$oldString = $this->WordFilter($oldString);
		$temp_array = $this->Get_String($oldString);//检测创建初始化单字索引文件
		$this->Makeindex($temp_array);//检测创建初始化单字索引文件
		$temp_array = $this->Sentence($temp_array);//句子切割
		
		$Score_array = array();
		$temp_array_count = 0;
		while(isset($temp_array[$temp_array_count])){
			echo $this->DetectionOfWrongSentences($temp_array[$temp_array_count]);
			++$temp_array_count;
		}
	}
	
	public function rewrite_array($oldString){//重写句子
		/////////////////////////////////////////////////////////////////
		$this->StatisticsSegmentation($oldString);//获取信息
		////////////////////////////////////////////////////////////////
		$temp_array = $this->Unit_ql_array->Get_String($oldString);//检测创建初始化单字索引文件
		$this->Unit_ql_array->Makeindex($temp_array);//检测创建初始化单字索引文件
		$Sentence_array = $this->Unit_ql_array->Sentence($temp_array);//句子切割
		$s_array = array();
		echo '改写前:<br/>'.$oldString.'<br/><br/><hr/><br/>';
		echo '改写后：<br/>';
		$Sentence_array_count = 0;
		while(isset($Sentence_array[$Sentence_array_count])){//句子轮询
			echo $this->rewrite($this->Unit_ql_array->Get_Code($Sentence_array[$Sentence_array_count]));//单句子改写
			++$Sentence_array_count;
		}
		
		echo '<br/><br/><hr/>如果句子偏差过大，请多让引擎学习一些句子，提高改写精准度。';
	}
	
	public function Abb_S_array($temp){//缩写文章
		echo '缩写后。<br/><br/>';
		$temp_array = $this->Unit_ql_array->Get_String($temp);
		$temp_array_count = 0;
		while(isset($temp_array[$temp_array_count])){
			if(isset($temp_array[$temp_array_count-1])&&
				($temp_array[$temp_array_count-1] == $temp_array[$temp_array_count])){
				$temp_array[$temp_array_count-1] = '';
			}
			++$temp_array_count;
		}
		$this->Unit_ql_array->Makeindex($temp_array);
		$temp_array = $this->Unit_ql_array->Sentence($temp_array);//切割句子
		$temp_array_count = 0;
		while(isset($temp_array[$temp_array_count])){
			if(Search_array_Element($this->Unit_ql_array->Breakpoint_array,$temp_array[$temp_array_count]) != -1){
				echo $this->Unit_ql_array->Get_Code($temp_array[$temp_array_count]);
			}
			else{
				echo $this->Abb_S($this->Unit_ql_array->Get_Code($temp_array[$temp_array_count]));
			}
			++$temp_array_count;
		}
		echo '<br/><br/>原文：<br/>';
		$this->Analysisword($temp);
	}
	
	public function Expan_S_array($temp){//扩写文章
		$temp = $this->WordFilter($temp);
		$temp_array = $this->Unit_ql_array->Get_String($temp);
		$this->Unit_ql_array->Makeindex($temp_array);
		$temp_array = $this->Unit_ql_array->Sentence($temp_array);//切割句子
		$temp_array_count = 0;
		while(isset($temp_array[$temp_array_count])){
			if(Search_array_Element($this->Unit_ql_array->Breakpoint_array,$temp_array[$temp_array_count]) != -1){
				echo $this->Unit_ql_array->Get_Code($temp_array[$temp_array_count]);
			}
			else{
				echo $this->Expan_S($this->Unit_ql_array->Get_Code($temp_array[$temp_array_count]));
			}
			++$temp_array_count;
		}
	}
	
	public function SimilaritySentencen($Target){//句子语法相似度
		if($Target == ''){
			echo '两个输入框请用键盘输入，或者用键盘的Ctrl+V进行粘贴。';
			return '';
		}
		$temp_array = explode('|',$Target);
		if(isset($temp_array[1])){
			$temp_array = $this->Dss($temp_array[0],$temp_array[1]);
			echo '两个句子的语法相似度：'.$temp_array['GrammarFloating'].'，';
			echo '<br/><br/><br/>';
			echo '两个句子的意思比起来相似度：'.$temp_array['SameSentence'].'，';
		}
		else{
			echo '请用|分隔开单句句子';
		}
	}
	
	public function GroupOfWords($Single){//组词
		$temp_array = $this->Unit_ql_array->GroupOfWords($Single);
		if($temp_array[0] != -1){
			echo $this->Unit_ql_array->GetCode_echo_in_array($temp_array,'<br/>');
		}
		else{
			echo '没词';
		}
	}
		
	public function ReadArticleInFile($File){//从文件中阅读文章提取分词
		$this->Unit_ql_array->ReadArticleInFile($File);
	}
	
	public function GetCodeToFile(){//将记忆词库中的记忆编码转换成原文并输出成文件
		if($this->Unit_ql_array->GetCodeToFile() == 1){
			echo '<br/>成功生成';
		}
		else{
			echo '<br/>生成失败';
		}
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function Nodemessage(){//节点信息
		$word_list_array = $this->Unit_ql_array->GetWordFileList();
		$Sentence_list_array = $this->GetSentenceFileList();
		echo count($word_list_array)+count($Sentence_list_array);
	}
	
	public function echo_String($oldString){//显示转码与原文
		$code_array = $this->Unit_ql_array->Get_String($oldString);
		$temp_array = array();
		$code_array_count = 0;
		while(isset($code_array[$code_array_count])){
			$temp = $code_array[$code_array_count].'|'.$this->Unit_ql_array->Get_Code($code_array[$code_array_count]);
			array_push($temp_array,$temp);
			++$code_array_count;
		}
		print_r($temp_array);
	}
}
?>
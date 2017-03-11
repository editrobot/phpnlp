<?php
function Tag_Or_No($word){
    $temp_array = cut_word($word);
	unset($word);
	$array_max = count($temp_array)-1;
	if(isset($temp_array[0])&&($temp_array[0] == '<')&&
		isset($temp_array[1])&&($temp_array[1] == '/')&&
		($temp_array[$array_max] == '>')){
		unset($temp_array[0],$temp_array[1],$temp_array[$array_max]);
		return 'endtag_'.implode('',$temp_array);
	}//抽取结束标签
	else if(isset($temp_array[0])&&($temp_array[0] == '<')&&
			isset($temp_array[1])&&($temp_array[1] == '!')&&
			isset($temp_array[2])&&($temp_array[2] == '[')&&
			isset($temp_array[3])&&($temp_array[3] == 'C')&&
			isset($temp_array[4])&&($temp_array[4] == 'D')&&
			isset($temp_array[5])&&($temp_array[5] == 'A')&&
			isset($temp_array[6])&&($temp_array[6] == 'T')&&
			isset($temp_array[7])&&($temp_array[7] == 'A')&&
			isset($temp_array[8])&&($temp_array[8] == '[')){
		unset($temp_array[0],$temp_array[1],$temp_array[2],$temp_array[3],$temp_array[4],$temp_array[5],$temp_array[6],$temp_array[7],$temp_array[8]);
		$temp_array_count = 9;
		while(isset($temp_array[$temp_array_count])){
			if(($temp_array[$temp_array_count] == ']')&&($temp_array[$temp_array_count+1] == ']')&&($temp_array[$temp_array_count+2] == '>')){
				unset($temp_array[$temp_array_count],$temp_array[$temp_array_count+1],$temp_array[$temp_array_count+2]);
				break;
			}
			++$temp_array_count;
		}
		return implode('',$temp_array);
	}
	else if(isset($temp_array[0])&&($temp_array[0] == ']')&&
			isset($temp_array[1])&&($temp_array[1] == ']')&&
			isset($temp_array[2])&&($temp_array[2] == '>')){
		array_pop($temp_array);
		array_pop($temp_array);
		array_pop($temp_array);
	}
	else if(($temp_array[0] == '<')&&($temp_array[$array_max] == '>')){
		unset($temp_array[0],$temp_array[$array_max]);
		$temp_array_count = 1;
		if(($temp_array[1] == '?')||($temp_array[1] == '!'))
		{
			unset($temp_array[1]);
			return 'Mark';
		}
		if(($temp_array[$array_max-1] == '?'))
		{
			unset($temp_array[$array_max-1]);
		}
		else if($temp_array[$array_max-1] == '/')
		{
			unset($temp_array[$array_max-1]);
			$tag_letter_array = array();
			while(isset($temp_array[$temp_array_count])&&!($temp_array[$temp_array_count] == ' '))
			{
				array_push($tag_letter_array,$temp_array[$temp_array_count]);
				unset($temp_array[$temp_array_count]);
				++$temp_array_count;
			}
			return 'Singletag_'.implode('',$tag_letter_array);
		}//返回单标签
		if(isset($temp_array[$temp_array_count]))
		{
			$tag_letter_array = array();
			while(isset($temp_array[$temp_array_count])&&!($temp_array[$temp_array_count] == ' '))
			{
				array_push($tag_letter_array,$temp_array[$temp_array_count]);
				unset($temp_array[$temp_array_count]);
				++$temp_array_count;
			}
			return 'tag_'.implode('',$tag_letter_array);
		}
	}
	return implode('',$temp_array);
}
?>
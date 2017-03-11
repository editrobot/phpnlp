<?php
function cut_word($word){
	$word_array = array();
	$word_count = 0;
	while(isset($word[$word_count]))
	{
		array_push($word_array,$word[$word_count]);
		++$word_count;
	}
	return $word_array;
}
?>
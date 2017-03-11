<?php
function file_list_in_folder($argument){
	global $language;
	$category = 'all';
	$number = -1;
	$argument_array = explode(":",$argument);
	$argument_array_count = 0;
	unset($argument);
	while(isset($argument_array[$argument_array_count]))
	{
		switch($argument_array[$argument_array_count])
		{
			case 'folder':
				++$argument_array_count;
				$folder = $argument_array[$argument_array_count];
				break;
			case 'category':
				++$argument_array_count;
				$category = $argument_array[$argument_array_count];
				break;
			case 'number':
				++$argument_array_count;
				$number = $argument_array[$argument_array_count];
				break;
			default:
		}
		++$argument_array_count;
	}
	unset($argument_array,$argument_array_count);
	if($language == 'chinese'){$folder = iconv("UTF-8","gb2312",$folder);}
    if(!(file_exists($folder))){exit;}
	if($dh = opendir($folder))
	{
	    $list_array = array();
		while($name = readdir($dh))
		{
			if(!(($name == '.')||($name == '..')))
			{
				if($language == 'chinese'){$name = iconv("gb2312","UTF-8",$name);}
				$name_array = explode(".",$name);
				$kind = array_pop($name_array);
				if($category != 'all'){
					switch($category)
					{
						case 'folder':
							if(is_dir($folder.$name))
							{array_push($list_array,$name);}
							clearstatcache();
							break;
						case 'pic':
							if(($kind == 'jpg')||
								($kind == 'JPG')||
								($kind == 'jpeg')||
								($kind == 'JPEG')||
								($kind == 'png')||
								($kind == 'PNG')||
								($kind == 'GIF')||
								($kind == 'gif'))
							{
								array_push($list_array,$name);
							}
							break;
						case 'html':
							if(($kind == 'html')||
								($kind == 'HTML')||
								($kind == 'htm'))
							{
								array_push($list_array,$name);
							}
							break;
						case 'php':
							if(($kind == 'php')||
								($kind == 'PHP')||
								($kind == 'Php')){
								array_push($list_array,$name);
							}
							break;
						default:
							if($kind == $category){
								array_push($list_array,$name);
							}
					}
				}
				else{
					array_push($list_array,$name);
				}
			}
		}
	closedir($dh);
	}
	shuffle($list_array);
	$file_list_array = array($category);
	$list_array_count = 0;
	while(isset($list_array[$list_array_count])&&($number != 0))
	{
		--$number;
		array_push($file_list_array,$list_array[$list_array_count]);
		++$list_array_count;
	}
	return $file_list_array;
}
?>
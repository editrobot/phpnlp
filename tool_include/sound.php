<?php
function sound($word){
	$word = iconv("UTF-8","gb2312",$word);
	file_put_contents('x.vbs','createobject("sapi.spvoice").speak("'.$word.'")');
	exec("start x.vbs");
}
?>
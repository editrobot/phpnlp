<?php
require("robot/read_all_file.php");
require("robot/file_list_in_folder.php");
require("robot/cut_word.php");
require("robot/cut_symbol_head_end.php");
require("robot/ql.php");
require("robot/Frame.php");
require("robot/longtailword.php");

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>文字加工机</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript">
function loadXMLDoc(data,id,page)
{
var xmlhttp;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById(id).innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("POST",page,true);
xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp.send(data);
    document.getElementById(id).innerHTML="<img src='wait.gif'/><br/>请稍等....";
}
</script>
<style type="text/css">

html,body{
margin:0;padding:0
}

body{
font: 76% arial,sans-serif
}

p{margin:2}

div#header h1{
height:80px;
line-height:80px;
margin:0;
padding-left:10px;
background: #EEE;
color: #79B30B}


div#content p{
line-height:1.4
}

div#navigation{

}

div#extra{
}

div#footer{
color: #FFF
}

div#footer p{
padding:5px 10px
}

div#wrapper{
float:left;
width:100%
}

div#content{
margin: 0 25%
}

div#navigation{
float:left;
width:25%;
margin-left:-100%
}

div#extra{
float:left;
width:25%;
margin-left:-25%
}

div#footer{
clear:left;
width:100%
}
</style>
<script language="javascript">var study;</script>
</head>
<body>
<div id="container">
<div id="header">
<div>
	<h1>引擎信息：目前有 记忆节点:<?php
	$Var = new _oopf_Frame;
	$Var->_construct(''); 
	$Var->Nodemessage();
	?>
	个
	</h1>
</div>
</div>
<div id="wrapper">
	<div id="content">
		<p style="text-align:center;">结果</p>
		<div id="Result" style="width:100%;height:500px;text-align:center;"></div>
	</div>
</div>
<div id="navigation">
	<div style="padding:4px;background:#B9CAFF;">
		<b>词汇类设置面板(单词可以用逗号分隔)：</b><br/>
		当你点击后，请稍微等待，后台在分析处理需要少许时间.<br/>
		<table>
			 <tr>
				 <td>
					<button type="button" onclick="loadXMLDoc('Mode=Noun&oldString='+study,'Result','robot.php');">学习名词</button>
				 </td>
				 <td>
					<button type="button" onclick="loadXMLDoc('Mode=Adjective&oldString='+study,'Result','robot.php');">学习形容词</button>
				 </td>
				 <td>
					<button type="button" onclick="loadXMLDoc('Mode=Verb&oldString='+study,'Result','robot.php');">学习动词</button>
				 </td>
				</tr>
				<tr>
				 <td>
					<button type="button" onclick="loadXMLDoc('Mode=Quantifier&oldString='+study,'Result','robot.php');">学习量词</button>
				 </td>
				 <td>
					<button type="button" onclick="loadXMLDoc('Mode=Pronouns&oldString='+study,'Result','robot.php');">学习代词</button>
				 </td>
				 <td>
					<button type="button" onclick="loadXMLDoc('Mode=Adverb&oldString='+study,'Result','robot.php');">学习副词</button>
				 </td>
				</tr>
				<tr>
				 <td>
					<button type="button" onclick="loadXMLDoc('Mode=Preposition&oldString='+study,'Result','robot.php');">学习介词</button>
				 </td>
				 <td>
					<button type="button" onclick="loadXMLDoc('Mode=Conjunction&oldString='+study,'Result','robot.php');">学习连词</button>
				 </td>
				 <td>
					<button type="button" onclick="loadXMLDoc('Mode=Particle&oldString='+study,'Result','robot.php');">学习助词</button>
				 </td>
				</tr>
				<tr>
				 <td>
					<button type="button" onclick="loadXMLDoc('Mode=Interjection&oldString='+study,'Result','robot.php');">学习叹词</button>
				 </td>
				 <td>
					<button type="button" onclick="loadXMLDoc('Mode=Onomatopoeia&oldString='+study,'Result','robot.php');">学习拟声词</button>
				 </td>
				 <td>
					<button type="button" onclick="loadXMLDoc('Mode=indexS&oldString='+study,'Result','robot.php');">学习例句</button>
				 </td>
				</tr>
				<tr>
				 <td>
					 <button type="button" onclick="loadXMLDoc('Mode=GroupOfWords&oldString='+study,'Result','robot.php');">组词</button>
				 </td>
				 <td>
					 <button type="button" onclick="loadXMLDoc('Mode=Word_Property&oldString='+study,'Result','robot.php');">获取词汇属性</button>
				 </td>
				 <td>
					<button type="button" onclick="loadXMLDoc('Mode=indexI&oldString='+study,'Result','robot.php');">删除词</button>
				 </td>
			 </tr>
		 </table>
	</div>
	<br/>
	<br/>
	<div style="padding:4px;background:#B9CAFF;">
		<form action="robot.php?file=wordhaveatt" method="post" enctype="multipart/form-data">
		<label for="file"><h2>批量学习标记属性的词汇</h2>:</label>
		<br/>
		<input type="file" name="file" id="file" />
		<input type="submit" name="submit" value="Submit" />
		</form>
		<p>
		文件上传说明：第一行必须为一个"词"字，并且以<b>utf-8</b>的文件格式。<br/><br/>格式示例：<br/><img src="123exp.jpg">
		</p>
	</div>
	<br/>
	<br/>
	<div style="padding:4px;background:#B9CAFF;">
		<form action="robot.php?file=Sentence" method="post" enctype="multipart/form-data">
		<label for="file"><h2>批量学习文章</h2>:</label>
		<br/>
		<input type="file" name="file" id="file" />
		<input type="submit" name="submit" value="Submit" />
		</form>
		<p>
			文件上传说明：第一行必须为一个"句"字，并且以<b>utf-8</b>的文件格式。<br/><br/>格式示例：<br/><img src="456exp.jpg">
		</p>
	</div>
	<br/>
	<br/>
	<div style="padding:4px;background:#B9CAFF;">
		<form action="robot.php?file=attwordtodo" method="post" enctype="multipart/form-data">
		<label for="file"><h2>批量词性处理</h2>:</label>
		<br/>
		<input type="file" name="file" id="file" />
		<input type="submit" name="submit" value="Submit" />
		</form>
		<p>
			文件上传说明：对已经标注词性的文件进行处理。
		</p>
	</div>
	<hr/>
	<br/>
	<br/>
	<div style="padding:4px;background:#B9CAFF;">
	<p>
		上传错误的符号，或者格式错误的词汇表，会导致节点出现断裂或者连接异常，可以点击<a href="robot.php?Mode=Repairallword"  target="_blank">修复词库节点</a>修复异常。
	</p>
	</div>
	<div style="padding:4px;background:#B9CAFF;">
		<form action="robot.php?file=config" method="post" enctype="multipart/form-data">
		<label for="file"><h2>修改配置文件</h2>:</label>
		<br/>
		<input type="file" name="file" id="file" />
		<input type="submit" name="submit" value="Submit" />
		</form>
	</div>
</div>
<div id="extra">
	<p>输入：</p>
	<textarea rows="20" cols="45" onkeyup="study = this.value" onmousedown="study = this.value"></textarea>
	<div style="padding:4px;background:#B9CAFF;">
		 <hr/>
		 <br/><b>应用面板：</b><br/>
		 可以输入句子或者文章.<br/>
		 <table>
			 <tr>
				 <td>
					<button type="button" onclick="loadXMLDoc('Mode=Analysisword&oldString='+study,'Result','robot.php');">文章分析</button>
				 </td>
				 <td>
					<button type="button" onclick="loadXMLDoc('Mode=rewrite_array&oldString='+study,'Result','robot.php');">改写句子</button>
				 </td>
				 <td>
					<button type="button" onclick="loadXMLDoc('Mode=SimilaritySentencen&oldString='+study,'Result','robot.php');">语法相似度(用 | 隔开)</button>
				 </td>
				</tr>
				<tr>
				 <td>
					<button type="button" onclick="loadXMLDoc('Mode=Abb_S_array&oldString='+study,'Result','robot.php');">缩写文章</button>
				 </td>
				 <td>
					<button type="button" onclick="loadXMLDoc('Mode=Summary&oldString='+study,'Result','robot.php');">摘要</button>
				 </td>
				 <td>
					<button type="button" onclick="loadXMLDoc('Mode=Expan_S_array&oldString='+study,'Result','robot.php');">扩写文章</button>
				 </td>
				</tr>
		 </table>
	</div>
	<div style="padding:4px;background:#B9CAFF;">
		 <hr/>长尾关键词处理面板<br/>
		 <table>
			 <tr>
				 <td>
					<button type="button" onclick="loadXMLDoc('Mode=longtailwordtodo&oldString='+study,'Result','robot.php');">长尾词切割筛选</button>
				 </td>
				</tr>
		 </table>
	</div>
</div>
<div id="footer"><p>其他</p></div>
</div>
</body>
</html>
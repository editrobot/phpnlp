<?php
require("robot/read_all_file.php");
require("robot/file_list_in_folder.php");
require("robot/cut_word.php");
require("robot/cut_symbol_head_end.php");
require("robot/ql.php");
require("robot/Frame.php");
require("robot/longtailword.php");
//require("robot/pinyin.php");
echo '<a href="http://wpa.qq.com/msgrd?v=3&uin=1837076477&site=qq&menu=yes" target="_blank" title="QQ:1837076477">QQ:1837076477</a><br/><br/>';
$oldString = '';
set_time_limit(99999);
if(isset($_REQUEST["pinyin"])){
	$flow = get_pinyin_array(iconv('UTF-8','GB2312',$_REQUEST["pinyin"]));
	echo $flow;
}
else if(isset($_REQUEST["Mode"])){
	$Var = new _oopf_Frame;
	if(isset($_REQUEST["oldString"])){
		$oldString = $_REQUEST["oldString"];
	}
	$oldString = $Var->WordFilter($oldString);
	$Var->_construct();
	switch($_REQUEST["Mode"]){
		case 'Repairallword':
			$Var->Repairallword();
			break;
		
		default:
			
			$Var->$_REQUEST["Mode"]($oldString);
	}
}
else if(($_FILES["file"]["type"] == "text/plain")&&
		($_FILES["file"]["size"] < 20000000)&&
		($_FILES["file"]["error"] == 0)){
		
		echo '<a href="index.php">返回控制面板</a>';
		$qlVar = new _oopf_Frame;
		$qlVar->_construct();
		//if($_REQUEST["file"] == 'ReadArticleInFile'){
		//	$qlVar->ReadArticleInFile($_FILES["file"]["tmp_name"],'att');
		//}
		if($_REQUEST["file"] == 'wordhaveatt'){
			$qlVar->StudyWordInFile($_FILES["file"]["tmp_name"],'att');
		}
		//else if($_REQUEST["file"] == 'wordnoatt'){
		//	$qlVar->StudyWordInFile($_FILES["file"]["tmp_name"],'no');
		//}
		else if($_REQUEST["file"] == 'Sentence'){
			$qlVar->studysenteninfile($_FILES["file"]["tmp_name"]);
		}
		else if($_REQUEST["file"] == 'attwordtodo'){
			$qlVar->attwordtodo($_FILES["file"]["tmp_name"]);
		}
		else if($_REQUEST["file"] == 'config'){
			copy($_FILES["file"]["tmp_name"],"robot/config.txt");
			echo '<br/>配置文件上传完毕！';
		}
}
?>

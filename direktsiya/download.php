<?php 
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/lib/composite/responder.php");
//require_once($_SERVER["DOCUMENT_ROOT"]."bitrix/modules/main/include/prolog_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/vendor/autoload.php");
/*
require_once('../../../studentu/praktika/functions.php');

if (isset($_REQUEST['groupName'])){
	//$group_name = htmlspecialchars(urldecode($_GET["groupName"]));
	$url="https://".$_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
	$parts = parse_url($url);
	parse_str($parts, $arr);
	$group_name = $arr['groupName'];
	echo $group_name;
	create_excel($group_name);
	$file = '../../../sotrudniku/praktika/direktsiya/uploads/'.$group_name.'.Xls';
	//$filePath= CFile::GetPath($file);
	//$file=$_SERVER['DOCUMENT_ROOT'].$file;
	if(!file_exists($file)){ // file does not exist
    	die('file not found');
	} else {
    	header('Cache-Control: must-revalidate');
    	header("Content-Description: File Transfer");

	//header("Content-Type: application/zip");

		header("Content-Type: application/x-force-download; name=\"".$file."\"");

    	header("Content-Transfer-Encoding: binary");
		header("Content-Disposition: attachment; filename=\"".$file."\"");
		header('Pragma: public');
    	header('Expires: 0');
		header("Content-Length: ".filesize($file));
    	readfile($file);
	}

}
*/

require_once('create_exel/functions/create_excel_report.php'); //edited
	$group_id = $_GET['groupID'];											   // _GET dont work
	$connect=connection();
	$group = $connect->query('SELECT * FROM Practices.groups WHERE id = '.$group_id.' ;')->Fetch();
	$stream	= $connect->query('SELECT * FROM Practices.streams WHERE id ='.$group['stream_id'].' ;')->Fetch();
	$group_name   = $stream['name'].'-'.$group['group_number'];
	create_excel($group_id);													   // maybe create_exell dont work	
	$file_path = $_SERVER['DOCUMENT_ROOT'].'/sotrudniku/praktika/direktsiya/uploads/'.$group_name.'.xls'; // dont make a file in directory
	if(!file_exists($file_path)){ // file does not exist
		echo $file_path;
    	die('file not found');
	} else {
    	header('Content-Description: File Transfer');
    	header('Content-Type: application/octet-stream');
    	header('Content-Disposition: attachment; filename='.$group_name. '.xls');
    	header('Expires: 0');
   		header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($file_path));
    	readfile($file_path);
    exit;
	}
?>

<?php

function connection(){
	try{
		$connect = Bitrix\Main\Application::getConnection();
	}
	catch(Exception $e) { 
		die(" [1] - connection error");

	};
	return $connect;
}
$connect=connection();

if (isset($_GET['download'])) {
		Download_Templace($_GET['download']);
	}
if (isset($_GET['done'])) {
	$connect->query("UPDATE Practices.templates SET decanat_check = 1, comment = '' WHERE id =". $_GET['done'] .";");
	//Download_Templace($_GET['done']);
	}
if (isset($_GET['noShow'])) {
	$connect->query("UPDATE Practices.templates SET decanat_check = 0, comment = '' WHERE id =". $_GET['noShow'] .";");
	//Download_Templace($_GET['done']);
	}
if (isset($_GET['remake'])) {
	$connect->query("UPDATE Practices.templates SET decanat_check = 2, comment = '". $_GET['comment'] ."' WHERE id =". $_GET['remake'] .";");
	//Download_Templace($_GET['remake']);
	}

function Select_instituts($connect) {
	$resultset = $connect->query("SELECT * FROM Practices.faculty;");
  return $resultset;
} 
function Select_profiles($connect, $id_inst) {
	$resultset = $connect->query("SELECT * FROM Practices.profiles WHERE faculty_id = '".$id_inst."';");
  return $resultset;
}   
function Select_streams($connect, $id_prof) {
	$resultset = $connect->query("SELECT * FROM Practices.streams WHERE profile_id = '".$id_prof."' and profile_id NOT LIKE '1'");
return $resultset;
} 
function Select_group($connect, $id_stream) {
	$resultset = $connect->query("SELECT * FROM Practices.groups WHERE stream_id = '".$id_stream."';");
  return $resultset;
} 
function Select_templates($connect, $id_group) {
	$resultset = $connect->query("SELECT * FROM Practices.templates WHERE group_id = '".$id_group."';");
  return $resultset;
} 

?>

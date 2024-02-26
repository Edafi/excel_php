<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Дирекция");

?>

<?php
require_once("create_exel/functions/create_excel_report.php");
//require_once("create_exel/functions/read_excel.php");
/*function connection(){
	try{
		$connect = Bitrix\Main\Application::getConnection();
	}
	catch(Exception $e) { 
		die(" [1] - connection error");

	};
	return $connect;
}
*/
$connect=connection();
if (isset($_GET['read_exel'])) {
	create_excel($_GET['download']);
	//Download_Templace($_GET['download']);
	}
if (isset($_GET['download'])) {
	create_excel($_GET['download']);
	//Download_Templace($_GET['download']);
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

function Download_Templace($name){
	$file = $name;// "../../direktsiya/".$name;
	$filename = str_replace(['../../direktsiya/uploads/'], "", $name);
      if(!file_exists($file)){
          die('file not found');

      } else {
         ob_end_clean();
         header("Content-Description: File Transfer");
         header("Content-Type: text/Xls");
         header("Content-Disposition: attachment; filename=".$filename);
         header("Content-Transfer-Encoding: binary");
         #header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
         header("Content-Length: ".filesize($file));

         readfile($file);
      }
}



function Select_instituts($connect) {
  $resultset = $connect->query("SELECT * FROM Practices.faculty where id = 96;");
return $resultset;
} 

function Select_profiles($connect, $id_inst) {
  $resultset = $connect->query("SELECT * FROM Practices.profiles WHERE faculty_id = '".$id_inst."';");
return $resultset;
} 

function Select_streams_b($connect, $id_prof) {
	$year = date("Y") - 4;
	if (date("m") > 9){
		$year++;
	}

	$resultset = $connect->query("SELECT * FROM Practices.streams as stream
									WHERE profile_id = '".$id_prof."' 
									and profile_id NOT LIKE '1' 
									and name REGEXP '.б-' 
									and year >= ".$year."
									and (select count(*) from Practices.groups where stream_id = stream.id) > 0
									ORDER BY name;");
return $resultset;
} 
function Select_streams_m($connect, $id_prof) {
	$year = date("Y") - 2;
	if (date("m") > 9){
		$year++;
	}

	$resultset = $connect->query("SELECT * FROM Practices.streams as stream
									WHERE profile_id = '".$id_prof."' 
									and profile_id NOT LIKE '1' 
									and name REGEXP '.м-' 
									and year >= ".$year."
									and (select count(*) from Practices.groups where stream_id = stream.id) > 0
									ORDER BY name;");
return $resultset;
} 
function Select_streams_z($connect, $id_prof) {
	$year = date("Y") - 5;
	if (date("m") > 9){
		$year++;
	}

	$resultset = $connect->query("SELECT * FROM Practices.streams as stream
									WHERE profile_id = '".$id_prof."' 
									and profile_id NOT LIKE '1' 
									and name REGEXP '.з-' 
									and year >= ".$year."
									and (select count(*) from Practices.groups where stream_id = stream.id) > 0
									ORDER BY name;");
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
function Select_student($connect, $id_group) {
  $resultset = $connect->query("SELECT * FROM Practices.students WHERE group_id = '".$id_group."';");
return $resultset;
} 
function Select_student_practic($connect, $id_student) {
	$student_practic = $connect->query("SELECT * FROM Practices.student_practic WHERE student_id = '".$id_student."' and status = 1;")->Fetch();
	return $student_practic;
}
function Select_teacher($connect, $id_teacher) {
  	$resultset = $connect->query("SELECT * FROM Practices.teachers WHERE id = '".$id_teacher."';");
return $resultset;
} 




function firstAcardion($Institute)
{
	echo '
		<form class="d-flex justify-content-end mb-2">
			<button name="upload_teacher" class="btn btn-warning">Загрузить нагрузку преподавателей</button>
		</form>
	';

	$inst = $Institute->fetch();

    $formEducation = ["Bakalavr", "Magis", "Zaoch"];

	echo '<div class="accordion" id="accordionFormat'.$faculty_id.'">';
	foreach($formEducation as $form){
	$formRus = " ";
	switch ($form){
	case "Bakalavr":
		$formRus = "Бакалавриат";
		break;
	case "Magis":
		$formRus = "Магистратура";
		break;
	case "Zaoch":
		$formRus = "Заочное обучение";
		break;
	}


    echo '<div class="accordion" id="accordionInstitute">';
        echo '
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading' . $form.$inst['id'] . '">
			<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse' . $form .$inst['id'] . '" aria-expanded="false" aria-controls="collapse' . $form .$inst['id'] . '">
                    ' . $formRus . '
                </button>
            </h2>
			<div id="collapse' . $form .$inst['id'] . '" class="accordion-collapse collapse " aria-labelledby="heading' . $form .$inst['id'] . '" ">

                <div class="accordion-body">
    ';
		thirdAcardion($inst['id'], $inst['name'], $form);

        echo '
    </div>
    </div>
    </div>';
    }
    echo '</div>';
}
?>
<?php
function thirdAcardion($faculty_id, $faculty_name, $form)
{
	$profiles = Select_profiles(connection(), $faculty_id);

while($prof = $profiles->fetch()){
		//print_r($prof['id']);
	$streams = null;
	switch ($form){
	case "Bakalavr":
		$streams = Select_streams_b(connection(), $prof['id']);
		break;
	case "Magis":
		$streams = Select_streams_m(connection(), $prof['id']);
		break;
	case "Zaoch":
		$streams = Select_streams_z(connection(), $prof['id']);
		break;
	}



	while($st = $streams->fetch()){
		LastTab($st);
    }
}
}
function LastTab($stream)
{	
	$group = Select_group(connection(), $stream['id']);
	echo '<ul class="nav nav-tabs" id="myTab" role="tablist">';
	echo '
  		<li class="nav-item" role="presentation">
    		<button class="nav-link active" id="tab'.$stream['id'].'-tab" data-bs-toggle="tab" data-bs-target="#tab'.$stream['id'].'"
					 type="button" role="tab" aria-controls="home" aria-selected="true">Скрыть</button>
  		</li>';
	while($gr = $group->fetch()){
		echo '
  			<li class="nav-item" role="presentation">
    			<button class="nav-link" id="tab'.$gr['id'].'-tab" data-bs-toggle="tab" data-bs-target="#tab'.$gr['id'].'"
					 type="button" role="tab" aria-controls="home" aria-selected="true">'.$stream['name'].'-'.$gr['group_number'].'</button>
  			</li>';
	}
	echo '</ul>';

	$group = Select_group(connection(), $stream['id']);
	echo '<div class="tab-content" id="myTabContent">';
	echo '<div class="tab-pane fade show" id="tab'.$stream['id'].'" role="tabpanel" aria-labelledby="tab'.$stream['id'].'-tab">';
	echo '</div>';
	while($gr = $group->fetch()){
		echo '<div class="tab-pane fade show" id="tab'.$gr['id'].'" role="tabpanel" aria-labelledby="tab'.$gr['id'].'-tab">';
			Tables($gr); 
		echo '
			<form class="d-flex justify-content-end">
<a name="download" value="'.$gr['id'].'" class="btn btn-primary" href="/sotrudniku/praktika/direktsiya/download.php?groupName='.$stream['name'].'-'.$gr['group_number'].'&groupID='.$gr['id'].'" role="button">Сформировать шаблон приказ</a>

</form>
			</div>';
	}
	echo '</div>';
}
//<button> name="download" value="'.$gr['id'].'" class="btn btn-primary">Сформировать шаблон приказ</button> 
//<a name="download" value="'.$gr['id'].'" class="btn btn-primary" href="/sotrudniku/praktika/direktsiya/download.php?groupName='.urlencode($_GET['group_name']).'" role="button">Сформировать шаблон приказ</a>
function Tables($group){
	$students = Select_student(connection(), $group['id']);
	echo'<body>
		<table class="table">
            <thead>
                <tr class="tr">
                    <th class="th">ФИО</th>
                    <th class="th">Руководитель</th>
                    <th class="th">Статус</th>
                </tr>
            </thead>
            <tbody>';
	while($stud = $students->fetch()){
	$student_practic = Select_student_practic(connection(), $stud['id']);
		if(!$student_practic){
			$teacher = '-';
			$status = '-';
		}
		else{
			$teacher = Select_teacher(connection(), $student_practic['teacher_id'])->Fetch()['fio'];
			$status = 'Записан';
		}
	echo'
        <tr class="tr">
			<td class="td" style="width: 100px; font-family: Helvetica Neue OTS, sans-serif; text-align: center; vertical-align: middle;"><strong class="strong">' . $stud['fio'] . '</strong></td>
			<td class="td" style="width: 100px; font-family: Helvetica Neue OTS, sans-serif; text-align: center; vertical-align: middle;"><strong class="strong">' . $teacher . '</strong></td>
			<td class="td" style="width: 100px; font-family: Helvetica Neue OTS, sans-serif; text-align: center; vertical-align: middle;"><strong class="strong">' . $status . '</strong></td>
        </tr>';
	}
	echo '</tbody>
        </table>
		</body>';

}


?>



<!doctype html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Демо Bootstrap</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>

<body >

<script>
		function showComment(let id) {
            let comment = document.getElementById('comment' + id);

           	comment.style.display = 'block';

        }
	</script>

    <?php
//print_r(ini_get('mbstring.func_overload'));
//read();
firstAcardion(Select_instituts(connection()));
//getAll();
    ?>






</body>

</html>

<?
function getAll(){
	/*$institute = Select_instituts(connection());
	while($inst = $institute->fetch()){
		print("id = ");
		print_r($inst['id']);
		print("; name = ");
		print_r($inst['name']);
		print("<br>");
	$profiles = Select_profiles(connection(), $inst['id']);
	while($pr = $profiles->fetch()){
		print("id = ");
		print_r($pr['id']);
		print("; name = ");
		print_r($pr['name']);
		print("; faculty_id = ");
		print_r($pr['faculty_id']);
		print("<br>");

	}
	print("<br>");
}*/

	$tables = connection()->query("show tables from Practices;");
	while($tb = $tables->fetch()){
		print_r($tb);
		print("<br>");

	}
	$tamplates = connection()->query("SELECT * FROM Practices.templates;");
	while($tb = $tamplates->fetch()){
		print_r($tb);

		print("<br>");

	}

	/*$groups = connection()->query("SELECT * FROM Practices.groups;");
	while($gr = $groups->fetch()){
		print_r($gr);

		print("<br>");

}*/
				 }
?>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>

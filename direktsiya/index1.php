o<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("decanat");
?><?php

?> 
<html>
   <head>
      <meta charset = "utf-8">
      
   </head>

<body>
<?php

	 ?>

	 <div class="content">
		 <table class="table">
			<tr class="tr">
			 <th class="th">ФИО</th>
			 <th class="th">Статус</th>
			</tr>
			   <?php
				  $students = $connect->query("SELECT id,fio FROM Practices.students WHERE group_id='$group_id'");
				  while($student = $students->Fetch()){
			      	$student_id  = $student["id"];
					$student_fio = $student["fio"]
			   ?>
			   	  <tr class="tr">
					 <td class="td"> <?php print_r($student_fio); ?> </td>
					 <td class="td"> 
						<?php 
						   $student_check = $connect->query("SELECT status FROM Practices.student_practic WHERE student_id='$student_id'")->Fetch()["status"];
						   if ($student_check==1) { 
							  print("+"); 
							  $real_count = $real_count+1;
						   }
						   else{
							  print("-");
						   } 
						?> 
			  		 </td>
				  </tr>
		   <?php } ?>
		 </table> 

		 <?php 
		 	if($real_count = $count) { 
				echo "<a href='download.php?group_name=$group_name'> <button class=\"button\"> Договор </button> </a>\n";
		 	}
		 ?>

		 <br>
     </div>

	 <?php }?>

<script>
	var colls = [document.getElementsByClassName("collapsible_0"),document.getElementsByClassName("collapsible_1"),document.getElementsByClassName("collapsible_2"),null];
	var i;
	for (let coll of colls){

		for (i = 0; i < coll.length; i++) {
		  console.log(coll[i]);
		  coll[i].addEventListener("click", function() {

			if (coll[i].className.includes("collapsible_0")){
				this.classList.toggle("active_0");
			} 
			if (coll[i].className.includes("collapsible_1")){
				this.classList.toggle("active_1");
			}
			if(coll[i].className.includes("collapsible_2")){
				this.classList.toggle("active_2");
			} 

			var content = this.nextElementSibling;
			if (content.style.display === "block") {
			  content.style.display = "none";
			} else {
			  content.style.display = "block";
			}
		  });
		}
	}
</script>
</body>
</html>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>
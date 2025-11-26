<?php
   $grade_name = $_POST['grade_name'];

   if($grade_name == "") {
	   echo "등급명을 입력하세요.<br>";
	   echo "<a href='search_grade.php'>입력 화면으로</a> | ";
	   echo "<a href='index.php'>메인으로</a>";
	   exit();
   }

	require_once __DIR__ . "/conn.php";
   mysqli_query($con, "SET @GRADE_NAME = '".$grade_name."'");
   $sql = "SELECT m.login_id AS '아이디', m.member_name AS '이름', m.phone AS '연락처', m.email AS '이메일', g.grade_name AS '등급명' FROM members AS m JOIN grades AS g ON m.grade_id = g.grade_id WHERE m.stat = 1 AND g.grade_name = @GRADE_NAME";
   $ret = mysqli_query($con, $sql);
   if($ret) {
	   $count = mysqli_num_rows($ret);
   }
   else {
	   echo "데이터 조회 실패!!!<br>";
	   echo "실패 원인 :".mysqli_error($con);
	   mysqli_close($con);
	   exit();
   }

   echo "<!DOCTYPE html>";
   echo "<html lang='ko'>";
   echo "<head><meta charset='UTF-8'><title>회원 등급 조회 결과</title></head>";
   echo "<body>";
   echo "<h2>회원 등급 조회 결과</h2>";

   if($count == 0) {
	   echo "<p>해당 등급의 회원이 없습니다.</p>";
   }
   else {
	   echo "<TABLE border=1>";
	   echo "<TR>";
	   echo "<TH>아이디</TH><TH>이름</TH><TH>연락처</TH><TH>이메일</TH><TH>등급명</TH>";
	   echo "</TR>";
	   while($row = mysqli_fetch_array($ret)) {
		   echo "<TR>";
		   echo "<TD>", $row['아이디'], "</TD>";
		   echo "<TD>", $row['이름'], "</TD>";
		   echo "<TD>", $row['연락처'], "</TD>";
		   echo "<TD>", $row['이메일'], "</TD>";
		   echo "<TD>", $row['등급명'], "</TD>";
		   echo "</TR>";
	   }
	   echo "</TABLE>";
   }

   mysqli_close($con);

   echo "<br><a href='search_grade.php'>입력 화면으로</a> | ";
   echo "<a href='index.php'>메인으로</a>";
   echo "</body></html>";
?>
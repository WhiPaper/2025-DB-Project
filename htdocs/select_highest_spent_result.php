<?php
	require_once __DIR__ . "/conn.php";

   $sql = "SELECT m.member_name AS '이름', m.login_id AS '아이디', g.grade_name AS '현재 등급', FORMAT(m.total_spent, 0) AS '누적 결제 금액(원)', m.remain_time AS '잔여 시간(분)' FROM members m JOIN grades g ON m.grade_id = g.grade_id WHERE m.stat = 1 ORDER BY m.total_spent DESC";

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
   echo "<head><meta charset='UTF-8'><title>누적 결제 상위 회원 결과</title></head>";
   echo "<body>";
   echo "<h2>누적 결제 금액 상위 회원 결과</h2>";

   if($count == 0) {
	   echo "<p>조회할 데이터가 없습니다.</p>";
   }
   else {
	   echo "<TABLE border=1>";
	   echo "<TR>";
	   echo "<TH>이름</TH><TH>아이디</TH><TH>현재 등급</TH><TH>누적 결제 금액(원)</TH><TH>잔여 시간(분)</TH>";
	   echo "</TR>";
	   while($row = mysqli_fetch_array($ret)) {
		   echo "<TR>";
		   echo "<TD>", $row['이름'], "</TD>";
		   echo "<TD>", $row['아이디'], "</TD>";
		   echo "<TD>", $row['현재 등급'], "</TD>";
		   echo "<TD>", $row['누적 결제 금액(원)'], "</TD>";
		   echo "<TD>", $row['잔여 시간(분)'], "</TD>";
		   echo "</TR>";
	   }
	   echo "</TABLE>";
   }

   mysqli_close($con);

   echo "<br><a href='select_highest_spent_result.php'>목록 새로고침</a> | ";
   echo "<a href='index.php'>메인으로</a>";
   echo "</body></html>";
?>
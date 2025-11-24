<?php
   $member_id = (int)$_POST['member_id'];

   if($member_id <= 0) {
	   echo "유효한 회원 ID를 입력하세요.<br>";
	   echo "<a href='member_payment_history.php'>입력 화면으로</a> | ";
	   echo "<a href='index.php'>메인으로</a>";
	   exit();
   }

	require_once __DIR__ . "/conn.php";
   $sql = "SELECT member_id AS '멤버 아이디', order_time AS '주문 시간', product_name AS '상품명', product_type AS '타입', price_at_sale AS '판매단가', quantity AS '수량', CONCAT(discount_rate_at_order, '%') AS '할인율', after_discount_price AS '결제금액' FROM v_member_payment_history WHERE member_id = ".$member_id." ORDER BY order_time DESC";
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
   echo "<head><meta charset='UTF-8'><title>회원 결제 내역 결과</title></head>";
   echo "<body>";
   echo "<h2>회원 결제 내역 결과</h2>";

   if($count == 0) {
	   echo "<p>결제 내역이 없습니다.</p>";
   }
   else {
	   echo "<TABLE border=1>";
	   echo "<TR>";
	   echo "<TH>주문 시간</TH><TH>상품명</TH><TH>타입</TH><TH>판매단가</TH><TH>수량</TH><TH>할인율</TH><TH>결제금액</TH>";
	   echo "</TR>";
	   while($row = mysqli_fetch_array($ret)) {
		   echo "<TR>";
		   echo "<TD>", $row['주문 시간'], "</TD>";
		   echo "<TD>", $row['상품명'], "</TD>";
		   echo "<TD>", $row['타입'], "</TD>";
		   echo "<TD>", $row['판매단가'], "</TD>";
		   echo "<TD>", $row['수량'], "</TD>";
		   echo "<TD>", $row['할인율'], "</TD>";
		   echo "<TD>", $row['결제금액'], "</TD>";
		   echo "</TR>";
	   }
	   echo "</TABLE>";
   }

   mysqli_close($con);

   echo "<br><a href='member_payment_history.php'>입력 화면으로</a> | ";
   echo "<a href='index.php'>메인으로</a>";
   echo "</body></html>";
?>
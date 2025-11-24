<?php
   $product_type = $_POST['product_type'];

   if($product_type == "") {
	   echo "상품 타입을 선택하세요.<br>";
	   echo "<a href='search_product_type.php'>입력 화면으로</a> | ";
	   echo "<a href='index.php'>메인으로</a>";
	   exit();
   }

   require_once __DIR__ . "/conn.php";
   mysqli_query($con, "SET @PRODUCT_TYPE = '".$product_type."'");
   $sql = "SELECT p.product_name AS '상품명', SUM(od.quantity) AS '총 판매 수' FROM order_details AS od JOIN products AS p ON od.product_id = p.product_id WHERE p.product_type = @PRODUCT_TYPE GROUP BY p.product_name ORDER BY 2 DESC";
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
   echo "<head><meta charset='UTF-8'><title>상품 타입 조회 결과</title></head>";
   echo "<body>";
   echo "<h2>상품 타입별 판매 결과</h2>";

   if($count == 0) {
	   echo "<p>해당 타입의 판매 데이터가 없습니다.</p>";
   }
   else {
	   echo "<TABLE border=1>";
	   echo "<TR>";
	   echo "<TH>상품명</TH><TH>총 판매 수</TH>";
	   echo "</TR>";
	   while($row = mysqli_fetch_array($ret)) {
		   echo "<TR>";
		   echo "<TD>", $row['상품명'], "</TD>";
		   echo "<TD>", $row['총 판매 수'], "</TD>";
		   echo "</TR>";
	   }
	   echo "</TABLE>";
   }

   mysqli_close($con);

   echo "<br><a href='search_product_type.php'>입력 화면으로</a> | ";
   echo "<a href='index.php'>메인으로</a>";
   echo "</body></html>";
?>
<?php
$start = $_POST['start_date'];
$end = $_POST['end_date'];
$product_name = $_POST['product_name'];

if ($start == "" || $end == "" || $product_name == "") {
	echo "모든 입력값을 채워주세요.<br>";
	echo "<a href='sales_period_product.php'>입력 화면으로</a> | ";
	echo "<a href='index.php'>메인으로</a>";
	exit();
}

if ($start > $end) {
	echo "시작일은 종료일보다 빠르거나 같아야 합니다.<br>";
	echo "<a href='sales_period_product.php'>입력 화면으로</a> | ";
	echo "<a href='index.php'>메인으로</a>";
	exit();
}

$start_dt = $start . " 00:00:00";
$end_dt = $end . " 23:59:59";
require_once __DIR__ . "/conn.php";
mysqli_query($con, "SET @START_DATE = '" . $start_dt . "'");
mysqli_query($con, "SET @END_DATE = '" . $end_dt . "'");
mysqli_query($con, "SET @PRODUCT_NAME = '" . $product_name . "'");
$sql = "SELECT p.product_name AS '상품명', IFNULL(SUM(FLOOR( (od.price_at_sale * od.quantity) * (100 - o.discount_rate_at_order) / 100 )), 0) AS '총 판매액' FROM order_details od JOIN orders o ON od.order_id = o.order_id JOIN products p ON od.product_id = p.product_id WHERE o.order_time >= @START_DATE AND o.order_time <= @END_DATE AND p.product_name = @PRODUCT_NAME AND o.pay_type = 'CARD' GROUP BY p.product_name";
$ret = mysqli_query($con, $sql);
if ($ret) {
	$count = mysqli_num_rows($ret);
} else {
	echo "데이터 조회 실패!!!<br>";
	echo "실패 원인 :" . mysqli_error($con);
	mysqli_close($con);
	exit();
}

echo "<!DOCTYPE html>";
echo "<html lang='ko'>";
echo "<head><meta charset='UTF-8'><title>기간/상품별 매출 결과</title></head>";
echo "<body>";
echo "<h2>특정 기간 특정 물품 조회 결과</h2>";

if ($count == 0) {
	echo "<p>조건에 맞는 판매 데이터가 없습니다.</p>";
} else {
	echo "<TABLE border=1>";
	echo "<TR>";
	echo "<TH>상품명</TH><TH>총 판매액</TH>";
	echo "</TR>";
	while ($row = mysqli_fetch_array($ret)) {
		echo "<TR>";
		echo "<TD>", $row['상품명'], "</TD>";
		echo "<TD>", $row['총 판매액'], "원</TD>";
		echo "</TR>";
	}
	echo "</TABLE>";
}

mysqli_close($con);

echo "<br><a href='sales_period_product.php'>입력 화면으로</a> | ";
echo "<a href='index.php'>메인으로</a>";
echo "</body></html>";

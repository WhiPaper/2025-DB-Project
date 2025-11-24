<?php
	$start = $_POST['start_date'];
	$end = $_POST['end_date'];
   $total = "";
   $message = "";

   if($start == "" || $end == "") {
	   $message = "시작일과 종료일을 모두 입력하세요.";
   }
   else if($start > $end) {
	   $message = "시작일은 종료일보다 빠르거나 같아야 합니다.";
   }
   else {
		require_once __DIR__ . "/conn.php";
	   $sql = "SELECT SUM(total_sales) AS '기간 내 총 매출' FROM daily_sales WHERE date >= '".$start."' AND date <= '".$end."'";
	   $ret = mysqli_query($con, $sql);
	   if($ret) {
		   $row = mysqli_fetch_array($ret);
		   if($row) {
			   $total = $row['기간 내 총 매출'];
		   }
		   else {
			   $total = 0;
		   }
	   }
	   else {
		   $message = "데이터 조회 실패!!!<br>실패 원인 :".mysqli_error($con);
	   }
	   mysqli_close($con);
   }
?>
<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="UTF-8">
	<title>기간 매출 조회 결과</title>
</head>

<body>
	<h2>기간별 매출 결과</h2>
	<?php if ($total === ""): ?>
		<p><?php echo $message; ?></p>
	<?php else: ?>
		<p>기간: <?php echo $start; ?> ~ <?php echo $end; ?></p>
		<p>총 매출: <?php echo $total; ?>원</p>
	<?php endif; ?>
	<a href="sales_period.php">입력 화면으로</a> |
	<a href="index.php">메인으로</a>
</body>

</html>
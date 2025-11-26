<?php
$member_id = (int)$_POST['member_id'];
$data = null;
$message = "";

if ($member_id <= 0) {
	$message = "유효한 회원 ID를 입력하세요.";
} else {
	require_once __DIR__ . "/conn.php";
	$sql = "SELECT member_name AS '이름', total_spent AS '총 결제 금액' FROM members WHERE members.stat = 1 AND member_id = " . $member_id;
	$ret = mysqli_query($con, $sql);
	if ($ret) {
		$count = mysqli_num_rows($ret);
		if ($count == 0) {
			$message = "해당 회원을 찾을 수 없습니다.";
		} else {
			$data = mysqli_fetch_array($ret);
		}
	} else {
		$message = "데이터 조회 실패!!!<br>실패 원인 :" . mysqli_error($con);
	}
	mysqli_close($con);
}
?>
<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="UTF-8">
	<title>누적 결제 금액 결과</title>
</head>

<body>
	<h2>특정 회원의 누적 결제 금액 결과</h2>
	<?php if ($data == null): ?>
		<p><?php echo $message; ?></p>
	<?php else: ?>
		<p>이름: <?php echo $data['이름']; ?></p>
		<p>총 결제 금액: <?php echo $data['총 결제 금액']; ?>원</p>
	<?php endif; ?>
	<a href="member_total_spent.php">입력 화면으로</a> |
	<a href="index.php">메인으로</a>
</body>

</html>
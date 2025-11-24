<?php
$login_id = $_POST['login_id'] ?? '';
$product_name = $_POST['product_name'] ?? '';
$quantity = (int)($_POST['quantity'] ?? 1);
$error_text = "";
$result_text = "";

if ($quantity < 1) {
	$quantity = 1;
}

if ($login_id == "" || $product_name == "") {
	$error_text = "회원 ID와 상품명을 모두 입력하세요.";
} else {
	require_once __DIR__ . "/conn.php";
	$sql = sprintf(
		"CALL process_cash_order('%s', '%s', %d)",
		$login_id,
		$product_name,
		$quantity
	);
	$result = mysqli_query($con, $sql);
	if ($result === false) {
		$error_text = "처리 실패!!!<br>실패 원인 :" . mysqli_error($con);
	} else {
		$row = mysqli_fetch_array($result);
		if ($row) {
			$result_text = reset($row) ?? "결제가 완료되었습니다.";
		} else {
			$result_text = "결제가 완료되었습니다.";
		}
	}
	mysqli_close($con);
}
?>
<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="UTF-8">
	<title>카드 주문 결과</title>
</head>

<body>
	<h2>카드로 음식 주문 결과</h2>
	<?php if ($error_text !== ''): ?>
		<p><?php echo $error_text; ?></p>
	<?php elseif ($result_text !== ''): ?>
		<p><?php echo $result_text; ?></p>
	<?php endif; ?>
	<a href="order_cash.php">입력 화면으로</a> |
	<a href="index.php">메인으로</a>
</body>

</html>
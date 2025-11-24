<?php
require_once __DIR__ . '/conn.php';

$login_id = trim($_POST['login_id'] ?? '');
$product_name = trim($_POST['product_name'] ?? '');
$quantity = isset($_POST['quantity']) ? max(1, (int) $_POST['quantity']) : 1;

$error_text = '';
$result_text = '';

if ($login_id === '' || $product_name === '') {
	$error_text = '회원 ID와 상품명을 모두 입력하세요.';
} else {
	try {
		$pdo = get_pdo();
		$pdo->prepare('SET @product_var := :product')->execute(['product' => $product_name]);
		$pdo->prepare('SET @quantity_var := :quantity')->execute(['quantity' => $quantity]);
		$pdo->prepare('SET @loginID_var := :login_id')->execute(['login_id' => $login_id]);

		$stmt = $pdo->query('CALL process_order_transaction()');
		$result = $stmt->fetch();
		if ($result && isset($result['Result'])) {
			$result_text = $result['Result'];
		} else {
			$result_text = '시간 결제가 완료되었습니다.';
		}
		while ($stmt->nextRowset()) {
			// consume remaining result sets, if any
		}
		$stmt->closeCursor();
	} catch (PDOException $e) {
		$error_text = '처리 실패: ' . $e->getMessage();
	}
}
?>
<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="UTF-8">
	<title>시간 주문 결과</title>
</head>

<body>
	<h2>시간으로 음식 주문 결과</h2>
	<?php if ($error_text !== ''): ?>
		<p><?php echo htmlspecialchars($error_text, ENT_QUOTES, 'UTF-8'); ?></p>
	<?php elseif ($result_text !== ''): ?>
		<p><?php echo htmlspecialchars($result_text, ENT_QUOTES, 'UTF-8'); ?></p>
	<?php endif; ?>
	<a href="order_time.php">입력 화면으로</a> |
	<a href="index.php">메인으로</a>
</body>

</html>
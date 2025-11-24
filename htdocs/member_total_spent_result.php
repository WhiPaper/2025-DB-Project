<?php
require_once __DIR__ . '/conn.php';

$member_id = isset($_POST['member_id']) ? (int) $_POST['member_id'] : 0;
$data = null;
$message = '';

if ($member_id <= 0) {
	$message = '유효한 회원 ID를 입력하세요.';
} else {
	try {
		$pdo = get_pdo();
		$sql = strip_sql_boilerplate(load_sql('특정 회원의 누적 결제 금액 조회.sql'));
		$sql = preg_replace('/member_id\s*=\s*\d+/i', 'member_id = :member_id', $sql, 1);
		$stmt = $pdo->prepare($sql);
		$stmt->execute(['member_id' => $member_id]);
		$data = $stmt->fetch();
		if ($data === false) {
			$data = null;
			$message = '해당 회원을 찾을 수 없습니다.';
		}
	} catch (PDOException $e) {
		$message = '데이터 조회 실패: ' . $e->getMessage();
	}
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
		<p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
	<?php else: ?>
		<p>이름: <?php echo htmlspecialchars($data['이름'], ENT_QUOTES, 'UTF-8'); ?></p>
		<p>총 결제 금액: <?php echo htmlspecialchars((string) $data['총 결제 금액'], ENT_QUOTES, 'UTF-8'); ?>원</p>
	<?php endif; ?>
	<a href="member_total_spent.php">입력 화면으로</a> |
	<a href="index.php">메인으로</a>
</body>

</html>
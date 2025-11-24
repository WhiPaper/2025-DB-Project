<?php
require_once __DIR__ . '/conn.php';

$start = trim($_POST['start_date'] ?? '');
$end = trim($_POST['end_date'] ?? '');
$total = '';
$message = '';

if ($start === '' || $end === '') {
	$message = '시작일과 종료일을 모두 입력하세요.';
} elseif ($start > $end) {
	$message = '시작일은 종료일보다 빠르거나 같아야 합니다.';
} else {
	try {
		$pdo = get_pdo();
		$sql = strip_sql_boilerplate(load_sql('특정 기간의 전체 매출 조회.sql'));
		$sql = str_replace("'2025-11-01'", ':start_date', $sql);
		$sql = str_replace("'2025-11-30'", ':end_date', $sql);
		$stmt = $pdo->prepare($sql);
		$stmt->execute([
			'start_date' => $start,
			'end_date' => $end,
		]);
		$row = $stmt->fetch();
		if ($row !== false) {
			$total = $row['기간 내 총 매출'] ?? 0;
		} else {
			$total = 0;
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
	<title>기간 매출 조회 결과</title>
</head>

<body>
	<h2>기간별 매출 결과</h2>
	<?php if ($total === ""): ?>
		<p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
	<?php else: ?>
		<p>기간: <?php echo htmlspecialchars($start, ENT_QUOTES, 'UTF-8'); ?> ~ <?php echo htmlspecialchars($end, ENT_QUOTES, 'UTF-8'); ?></p>
		<p>총 매출: <?php echo htmlspecialchars((string) $total, ENT_QUOTES, 'UTF-8'); ?>원</p>
	<?php endif; ?>
	<a href="sales_period.php">입력 화면으로</a> |
	<a href="index.php">메인으로</a>
</body>

</html>
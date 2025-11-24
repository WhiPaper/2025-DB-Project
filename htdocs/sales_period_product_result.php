<?php
require_once __DIR__ . '/conn.php';

$start = trim($_POST['start_date'] ?? '');
$end = trim($_POST['end_date'] ?? '');
$product_name = trim($_POST['product_name'] ?? '');
$row_html = '';
$message = '';

if ($start === '' || $end === '' || $product_name === '') {
	$message = '모든 입력값을 채워주세요.';
} elseif ($start > $end) {
	$message = '시작일은 종료일보다 빠르거나 같아야 합니다.';
} else {
	$start_dt = $start . ' 00:00:00';
	$end_dt = $end . ' 23:59:59';
	try {
		$pdo = get_pdo();
		$sql = strip_sql_boilerplate(load_sql('특정기간_특정물품 조회.sql'));
		$sql = preg_replace('/SET\s+@START_DATE\s*=\s*.+?;\s*/i', '', $sql);
		$sql = preg_replace('/SET\s+@END_DATE\s*=\s*.+?;\s*/i', '', $sql);
		$sql = preg_replace('/SET\s+@PRODUCT_NAME\s*=\s*.+?;\s*/i', '', $sql);
		$sql = str_replace('@START_DATE', ':start_date', $sql);
		$sql = str_replace('@END_DATE', ':end_date', $sql);
		$sql = str_replace('@PRODUCT_NAME', ':product_name', $sql);
		$stmt = $pdo->prepare($sql);
		$stmt->execute([
			'product_name' => $product_name,
			'start_date' => $start_dt,
			'end_date' => $end_dt,
		]);
		$data = $stmt->fetch();
		if ($data === false) {
			$message = '조건에 맞는 판매 데이터가 없습니다.';
		} else {
			$row_html = '<tr><td>' . htmlspecialchars($data['상품명'], ENT_QUOTES, 'UTF-8') . '</td>';
			$row_html .= '<td>' . htmlspecialchars((string) $data['총 판매액'], ENT_QUOTES, 'UTF-8') . "원</td></tr>";
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
	<title>기간/상품별 매출 결과</title>
</head>

<body>
	<h2>특정 기간 특정 물품 조회 결과</h2>
	<?php if ($row_html == ""): ?>
		<p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
	<?php else: ?>
		<table border="1">
			<tr>
				<th>상품명</th>
				<th>총 판매액</th>
			</tr>
			<?php echo $row_html; ?>
		</table>
	<?php endif; ?>
	<a href="sales_period_product.php">입력 화면으로</a> |
	<a href="index.php">메인으로</a>
</body>

</html>
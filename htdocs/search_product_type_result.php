<?php
require_once __DIR__ . '/conn.php';

$product_type = trim($_POST['product_type'] ?? '');
$table_rows = '';
$message = '';

if ($product_type === '') {
	$message = '상품 타입을 선택하세요.';
} else {
	try {
		$pdo = get_pdo();
		$sql = strip_sql_boilerplate(load_sql('특정 타입의 상품 조회.sql'));
		$sql = preg_replace('/SET\s+@PRODUCT_TYPE\s*=\s*.+?;\s*/i', '', $sql);
		$sql = str_replace('@PRODUCT_TYPE', ':product_type', $sql);
		$stmt = $pdo->prepare($sql);
		$stmt->execute(['product_type' => $product_type]);
		$rows = $stmt->fetchAll();
		if (count($rows) === 0) {
			$message = '해당 타입의 판매 데이터가 없습니다.';
		} else {
			foreach ($rows as $row) {
				$table_rows .= '<tr><td>' . htmlspecialchars($row['상품명'], ENT_QUOTES, 'UTF-8') . '</td>';
				$table_rows .= '<td>' . htmlspecialchars((string) $row['총 판매 수'], ENT_QUOTES, 'UTF-8') . '</td></tr>';
			}
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
	<title>상품 타입 조회 결과</title>
</head>

<body>
	<h2>상품 타입별 판매 결과</h2>
	<?php if ($table_rows == ""): ?>
		<p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
	<?php else: ?>
		<table border="1">
			<tr>
				<th>상품명</th>
				<th>총 판매 수</th>
			</tr>
			<?php echo $table_rows; ?>
		</table>
	<?php endif; ?>
	<a href="search_product_type.php">입력 화면으로</a> |
	<a href="index.php">메인으로</a>
</body>

</html>
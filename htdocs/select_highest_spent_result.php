<?php
require_once __DIR__ . '/conn.php';

$table_rows = '';
$message = '';

try {
	$pdo = get_pdo();
	$sql = strip_sql_boilerplate(load_sql('누적 결제 금액이 가장 큰 회원들을 내림차순으로 조회.sql'));
	$stmt = $pdo->query($sql);
	$rows = $stmt->fetchAll();
	if (count($rows) === 0) {
		$message = '조회할 데이터가 없습니다.';
	} else {
		foreach ($rows as $row) {
			$table_rows .= '<tr>';
			$table_rows .= '<td>' . htmlspecialchars($row['이름'], ENT_QUOTES, 'UTF-8') . '</td>';
			$table_rows .= '<td>' . htmlspecialchars($row['아이디'], ENT_QUOTES, 'UTF-8') . '</td>';
			$table_rows .= '<td>' . htmlspecialchars($row['현재 등급'], ENT_QUOTES, 'UTF-8') . '</td>';
			$table_rows .= '<td>' . htmlspecialchars($row['누적 결제 금액(원)'], ENT_QUOTES, 'UTF-8') . '</td>';
			$table_rows .= '<td>' . htmlspecialchars((string) $row['잔여 시간(분)'], ENT_QUOTES, 'UTF-8') . '</td>';
			$table_rows .= '</tr>';
		}
	}
} catch (PDOException $e) {
	$message = '데이터 조회 실패: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="UTF-8">
	<title>누적 결제 상위 회원 결과</title>
</head>

<body>
	<h2>누적 결제 금액 상위 회원 결과</h2>
	<?php if ($table_rows == ""): ?>
		<p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
	<?php else: ?>
		<table border="1">
			<tr>
				<th>이름</th>
				<th>아이디</th>
				<th>현재 등급</th>
				<th>누적 결제 금액(원)</th>
				<th>잔여 시간(분)</th>
			</tr>
			<?php echo $table_rows; ?>
		</table>
	<?php endif; ?>
	<a href="select_highest_spent_result.php">목록 새로고침</a> |
	<a href="index.php">메인으로</a>
</body>

</html>
<?php
require_once __DIR__ . '/conn.php';

$grade_name = trim($_POST['grade_name'] ?? '');
$table_rows = '';
$message = '';

if ($grade_name === '') {
	$message = '등급명을 입력하세요.';
} else {
	try {
		$pdo = get_pdo();
		$sql = strip_sql_boilerplate(load_sql('특정 회원 등급 조회.sql'));
		$sql = preg_replace('/SET\s+@GRADE_NAME\s*=\s*.+?;\s*/i', '', $sql);
		$sql = str_replace('@GRADE_NAME', ':grade_name', $sql);
		$stmt = $pdo->prepare($sql);
		$stmt->execute(['grade_name' => $grade_name]);
		$rows = $stmt->fetchAll();
		if (count($rows) === 0) {
			$message = '해당 등급의 회원이 없습니다.';
		} else {
			foreach ($rows as $row) {
				$table_rows .= '<tr>';
				$table_rows .= '<td>' . htmlspecialchars($row['아이디'], ENT_QUOTES, 'UTF-8') . '</td>';
				$table_rows .= '<td>' . htmlspecialchars($row['이름'], ENT_QUOTES, 'UTF-8') . '</td>';
				$table_rows .= '<td>' . htmlspecialchars($row['연락처'], ENT_QUOTES, 'UTF-8') . '</td>';
				$table_rows .= '<td>' . htmlspecialchars($row['이메일'], ENT_QUOTES, 'UTF-8') . '</td>';
				$table_rows .= '<td>' . htmlspecialchars($row['등급명'], ENT_QUOTES, 'UTF-8') . '</td>';
				$table_rows .= '</tr>';
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
	<title>회원 등급 조회 결과</title>
</head>

<body>
	<h2>회원 등급 조회 결과</h2>
	<?php if ($table_rows == ""): ?>
		<p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
	<?php else: ?>
		<table border="1">
			<tr>
				<th>아이디</th>
				<th>이름</th>
				<th>연락처</th>
				<th>이메일</th>
				<th>등급명</th>
			</tr>
			<?php echo $table_rows; ?>
		</table>
	<?php endif; ?>
	<a href="search_grade.php">입력 화면으로</a> |
	<a href="index.php">메인으로</a>
</body>

</html>
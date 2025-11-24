<?php
require_once __DIR__ . '/conn.php';

$member_id = isset($_POST['member_id']) ? (int) $_POST['member_id'] : 0;
$table_rows = '';
$message = '';

if ($member_id <= 0) {
	$message = '유효한 회원 ID를 입력하세요.';
} else {
	try {
		$pdo = get_pdo();
		$view_sql = strip_sql_boilerplate(load_sql('특정 회원의 전체 결제 내역 조회 뷰 생성.sql'));
		$pdo->exec($view_sql);
		$sql = strip_sql_boilerplate(load_sql('특정 회원의 전체 결제 내역 조회.sql'));
		$sql = preg_replace('/member_id\s*=\s*\d+/i', 'member_id = :member_id', $sql, 1);
		$stmt = $pdo->prepare($sql);
		$stmt->execute(['member_id' => $member_id]);
		$rows = $stmt->fetchAll();
		if (count($rows) === 0) {
			$message = '결제 내역이 없습니다.';
		} else {
			foreach ($rows as $row) {
				$table_rows .= '<tr>';
				$table_rows .= '<td>' . htmlspecialchars($row['주문 시간'], ENT_QUOTES, 'UTF-8') . '</td>';
				$table_rows .= '<td>' . htmlspecialchars($row['상품명'], ENT_QUOTES, 'UTF-8') . '</td>';
				$table_rows .= '<td>' . htmlspecialchars($row['타입'], ENT_QUOTES, 'UTF-8') . '</td>';
				$table_rows .= '<td>' . htmlspecialchars((string) $row['판매단가'], ENT_QUOTES, 'UTF-8') . '</td>';
				$table_rows .= '<td>' . htmlspecialchars((string) $row['수량'], ENT_QUOTES, 'UTF-8') . '</td>';
				$table_rows .= '<td>' . htmlspecialchars($row['할인율'], ENT_QUOTES, 'UTF-8') . '</td>';
				$table_rows .= '<td>' . htmlspecialchars((string) $row['결제금액'], ENT_QUOTES, 'UTF-8') . '</td>';
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
	<title>회원 결제 내역 결과</title>
</head>

<body>
	<h2>회원 결제 내역 결과</h2>
	<?php if ($table_rows == ""): ?>
		<p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
	<?php else: ?>
		<table border="1">
			<tr>
				<th>주문 시간</th>
				<th>상품명</th>
				<th>타입</th>
				<th>판매단가</th>
				<th>수량</th>
				<th>할인율</th>
				<th>결제금액</th>
			</tr>
			<?php echo $table_rows; ?>
		</table>
	<?php endif; ?>
	<a href="member_payment_history.php">입력 화면으로</a> |
	<a href="index.php">메인으로</a>
</body>

</html>
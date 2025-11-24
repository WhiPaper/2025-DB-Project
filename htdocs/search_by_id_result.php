<?php
require_once __DIR__ . '/conn.php';

$login_id = trim($_POST['login_id'] ?? '');
$member = null;
$message = '';

if ($login_id === '') {
	$message = '로그인 ID를 입력하세요.';
} else {
	try {
		$pdo = get_pdo();
		$sql = strip_sql_boilerplate(load_sql('멤버 ID로 검색.sql'));
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$login_id]);
		$member = $stmt->fetch();
		if ($member === false) {
			$member = null;
			$message = '검색된 회원이 없습니다.';
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
	<title>멤버 ID 검색 결과</title>
</head>

<body>
	<h2>멤버 ID 검색 결과</h2>
	<?php if ($member == null): ?>
		<p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
	<?php else: ?>
		<ul>
			<li>로그인 ID: <?php echo htmlspecialchars($member['login_id'], ENT_QUOTES, 'UTF-8'); ?></li>
			<li>이름: <?php echo htmlspecialchars($member['member_name'], ENT_QUOTES, 'UTF-8'); ?></li>
			<li>전화번호: <?php echo htmlspecialchars($member['phone'], ENT_QUOTES, 'UTF-8'); ?></li>
			<li>이메일: <?php echo htmlspecialchars($member['email'], ENT_QUOTES, 'UTF-8'); ?></li>
			<li>잔여시간: <?php echo htmlspecialchars((string) $member['remain_time'], ENT_QUOTES, 'UTF-8'); ?>분</li>
			<li>등급 ID: <?php echo htmlspecialchars((string) $member['grade_id'], ENT_QUOTES, 'UTF-8'); ?></li>
		</ul>
	<?php endif; ?>
	<a href="search_by_id.php">입력 화면으로</a> |
	<a href="index.php">메인으로</a>
</body>

</html>
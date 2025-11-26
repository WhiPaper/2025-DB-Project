<?php
require_once __DIR__ . "/conn.php";
$error = '';
$members = [];
$sql = "SELECT m.member_id, m.login_id, m.member_name, m.phone, m.email, m.remain_time, m.total_spent, g.grade_name "
	. "FROM members m LEFT JOIN grades g ON m.grade_id = g.grade_id WHERE stat = 1 ORDER BY m.member_id";
$ret = mysqli_query($con, $sql);
if ($ret) {
	while ($row = mysqli_fetch_array($ret)) {
		$members[] = $row;
	}
} else {
	$error = "회원 목록을 불러오지 못했습니다. 오류: " . mysqli_error($con);
}
mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="UTF-8">
	<title>회원 목록</title>
</head>

<body>
	<h1>회원 목록</h1>
	<p>
		<a href="index.php">메인으로</a> |
		<a href="member_create.php">회원 추가</a>
	</p>
	<?php if ($error !== ''): ?>
		<p><?php echo $error; ?></p>
	<?php elseif (count($members) === 0): ?>
		<p>등록된 회원이 없습니다.</p>
	<?php else: ?>
		<table border="1">
			<tr>
				<th>로그인 ID</th>
				<th>이름</th>
				<th>전화번호</th>
				<th>이메일</th>
				<th>회원 등급</th>
				<th>잔여 시간(분)</th>
				<th>누적 결제 금액(원)</th>
				<th>관리</th>
			</tr>
			<?php foreach ($members as $member): ?>
				<tr>
					<td><?php echo $member['login_id']; ?></td>
					<td><?php echo $member['member_name']; ?></td>
					<td><?php echo $member['phone']; ?></td>
					<td><?php echo $member['email'] ?? '-'; ?></td>
					<td><?php echo $member['grade_name'] ?? '-'; ?></td>
					<td><?php echo number_format($member['remain_time']); ?></td>
					<td><?php echo number_format($member['total_spent']); ?></td>
					<td>
						<?php $loginIdQuery = $member['login_id']; ?>
						<a href="member_update.php?login_id=<?php echo $loginIdQuery; ?>">수정</a> |
						<a href="member_delete.php?login_id=<?php echo $loginIdQuery; ?>">삭제</a>
					</td>
				</tr>
			<?php endforeach; ?>
		</table>
	<?php endif; ?>
</body>

</html>

<?php
$login_id = $_POST['login_id'] ?? "";
$message = "";
$member = null;

if ($login_id == "") {
	$message = "로그인 ID를 입력하세요.";
} else {
	require_once __DIR__ . "/conn.php";
	$sql = "SELECT m.member_id, m.login_id, m.member_name, m.phone, m.email, m.remain_time, m.total_spent, g.grade_name "
		. "FROM members m LEFT JOIN grades g ON m.grade_id = g.grade_id "
		. "WHERE m.login_id = '" . $login_id . "'";
	$ret = mysqli_query($con, $sql);
	if ($ret) {
		$count = mysqli_num_rows($ret);
		if ($count == 0) {
			$message = "검색된 회원이 없습니다.";
		} else {
			$member = mysqli_fetch_array($ret);
		}
	} else {
		$message = "데이터 조회 실패!!!<br>실패 원인 :" . mysqli_error($con);
	}
	mysqli_close($con);
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
		<p><?php echo $message; ?></p>
	<?php else: ?>
		<table border="1">
			<tr>
				<th>로그인 ID</th>
				<th>이름</th>
				<th>전화번호</th>
				<th>이메일</th>
				<th>현재 등급</th>
				<th>누적 결제 금액(원)</th>
				<th>잔여 시간(분)</th>
			</tr>
			<tr>
				<td><?php echo $member['login_id']; ?></td>
				<td><?php echo $member['member_name']; ?></td>
				<td><?php echo $member['phone']; ?></td>
				<td><?php echo $member['email']; ?></td>
				<td><?php echo $member['grade_name'] ?? '-'; ?></td>
				<td><?php echo number_format($member['total_spent']); ?></td>
				<td><?php echo $member['remain_time']; ?></td>
			</tr>
		</table>
	<?php endif; ?>
	<a href="search_by_id.php">입력 화면으로</a> |
	<a href="index.php">메인으로</a>
</body>

</html>
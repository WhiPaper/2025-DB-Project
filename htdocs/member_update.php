<?php
require_once __DIR__ . "/conn.php";
$message = '';
$member = null;
$loginIdInput = $_GET['login_id'] ?? '';
if ($loginIdInput !== '') {
	$sql = "SELECT member_id, member_name, login_id, phone, email, remain_time, total_spent, grade_id FROM members WHERE members.stat = 1 AND login_id = '" . $loginIdInput . "'";
	$ret = mysqli_query($con, $sql);
	if ($ret) {
		if (mysqli_num_rows($ret) > 0) {
			$member = mysqli_fetch_array($ret);
		} else {
			$message = "해당 회원을 찾을 수 없습니다.";
		}
	} else {
		$message = "회원 정보를 불러오지 못했습니다. 오류: " . mysqli_error($con);
	}
}
mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="UTF-8">
	<title>회원 수정</title>
</head>

<body>
	<h1>회원 수정</h1>
	<p>
		<a href="member_list.php">회원 목록</a> |
		<a href="index.php">메인으로</a>
	</p>
	<form method="GET" action="member_update.php">
		<label>로그인 ID 입력: <input type="text" name="login_id" maxlength="12" value="<?php echo $loginIdInput; ?>" required></label>
		<button type="submit">불러오기</button>
	</form>
	<?php if ($message !== ''): ?>
		<p><?php echo $message; ?></p>
	<?php endif; ?>
	<?php if ($member): ?>
		<h2>회원 정보 수정</h2>
		<form method="POST" action="member_update_result.php">
			<input type="hidden" name="original_login_id" value="<?php echo $member['login_id']; ?>">
			<table border="1">
				<tr>
					<th>이름</th>
					<th>로그인 ID</th>
					<th>전화번호</th>
					<th>이메일</th>
					<th>잔여 시간(분)</th>
					<th>누적 결제 금액(원)</th>
					<th>회원 등급</th>
				</tr>
				<tr>
					<td><input type="text" name="member_name" value="<?php echo $member['member_name']; ?>" required></td>
					<td><input type="text" name="login_id" maxlength="12" value="<?php echo $member['login_id']; ?>" required></td>
					<td><input type="text" name="phone" value="<?php echo $member['phone']; ?>" required></td>
					<td><input type="email" name="email" value="<?php echo $member['email']; ?>" required></td>
					<td><input type="number" name="remain_time" min="0" value="<?php echo $member['remain_time']; ?>" required></td>
					<td><?php echo $member['total_spent']; ?></td>
					<td><?php echo $member['grade_id']; ?></td>
				</tr>
			</table>
			<button type="submit">수정 저장</button>
		</form>
	<?php endif; ?>
</body>

</html>

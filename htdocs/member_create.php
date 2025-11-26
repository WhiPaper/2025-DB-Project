<?php
$message = $_GET['message'] ?? '';
?>
<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="UTF-8">
	<title>회원 추가</title>
</head>

<body>
	<h1>회원 추가</h1>
	<p>
		<a href="member_list.php">회원 목록</a> |
		<a href="index.php">메인으로</a>
	</p>
	<?php if (!empty($message)): ?>
		<p><?php echo $message; ?></p>
	<?php endif; ?>
	<form method="POST" action="member_create_result.php">
		<table border="1">
			<tr>
				<th>이름</th>
				<th>로그인 ID</th>
				<th>전화번호</th>
				<th>이메일</th>
				<th>잔여 시간(분)</th>
			</tr>
			<tr>
				<td><input type="text" name="member_name" required></td>
				<td><input type="text" name="login_id" maxlength="12" required></td>
				<td><input type="text" name="phone" required></td>
				<td><input type="email" name="email" required></td>
				<td><input type="number" name="remain_time" min="0" value="0" required></td>
			</tr>
		</table>
		<button type="submit">회원 등록</button>
	</form>
</body>

</html>

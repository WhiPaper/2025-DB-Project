<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="UTF-8">
	<title>특정 회원의 누적 결제 금액 조회</title>
</head>

<body>
	<h2>특정 회원의 누적 결제 금액 조회</h2>
	<form method="POST" action="member_total_spent_result.php">
		<label>
			로그인 ID:
			<input type="text" name="login_id" maxlength="12" required>
		</label>
		<button type="submit">조회</button>
	</form>
	<p>로그인 ID를 기준으로 누적 결제 금액을 조회합니다.</p>
	<a href="index.php">메인으로</a>
</body>

</html>
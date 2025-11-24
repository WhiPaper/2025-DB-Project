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
			회원 ID (숫자):
			<input type="number" name="member_id" min="1" required>
		</label>
		<button type="submit">조회</button>
	</form>
	<p>조회 결과는 결과 페이지에서 금액과 함께 안내됩니다.</p>
	<a href="index.php">메인으로</a>
</body>

</html>
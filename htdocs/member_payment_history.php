<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="UTF-8">
	<title>특정 회원의 전체 결제 내역 조회</title>
</head>

<body>
	<h2>특정 회원의 전체 결제 내역 조회</h2>
	<form method="POST" action="member_payment_history_result.php">
		<label>
			회원 ID (숫자):
			<input type="number" name="member_id" min="1" required>
		</label>
		<button type="submit">조회</button>
	</form>
	<p>결제 내역은 결과 페이지에서 표 형태로 제공합니다.</p>
	<a href="index.php">메인으로</a>
</body>

</html>
<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="UTF-8">
	<title>이름과 전화번호로 검색</title>
</head>

<body>
	<h2>이름과 전화번호로 검색</h2>
	<form method="POST" action="search_by_name_phone_result.php">
		<label>
			이름:
			<input type="text" name="member_name" required>
		</label><br>
		<label>
			전화번호:
			<input type="text" name="phone" required>
		</label><br>
		<button type="submit">검색</button>
	</form>
	<p>조회 결과는 결과 페이지에서 확인하세요.</p>
	<a href="index.php">메인으로</a>
</body>

</html>
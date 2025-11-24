<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="UTF-8">
	<title>멤버 ID로 검색</title>
</head>

<body>
	<h2>멤버 ID로 검색</h2>
	<form method="POST" action="search_by_id_result.php">
		<label>
			ID:
			<input type="text" name="login_id" required>
		</label>
		<button type="submit">검색</button>
	</form>
	<p>조회 결과는 별도의 결과 페이지에서 확인됩니다.</p>
	<a href="index.php">메인으로</a>
</body>

</html>
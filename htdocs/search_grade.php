<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="UTF-8">
	<title>특정 회원 등급 조회</title>
</head>

<body>
	<h2>특정 회원 등급 조회</h2>
	<form method="POST" action="search_grade_result.php">
		<label>
			등급명:
			<input type="text" name="grade_name" placeholder="예: 실버" required>
		</label>
		<button type="submit">검색</button>
	</form>
	<p>동일한 화면에서 결과를 출력하지 않고, 별도 페이지에서 확인합니다.</p>
	<a href="index.php">메인으로</a>
</body>

</html>
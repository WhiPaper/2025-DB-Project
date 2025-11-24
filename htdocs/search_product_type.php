<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="UTF-8">
	<title>특정 타입의 상품 조회</title>
</head>

<body>
	<h2>특정 타입의 상품 조회</h2>
	<form method="POST" action="search_product_type_result.php">
		<label>
			상품 타입:
			<select name="product_type">
				<option value="FOOD">FOOD</option>
				<option value="TIME">TIME</option>
			</select>
		</label>
		<button type="submit">조회</button>
	</form>
	<p>선택한 타입의 통계는 결과 페이지에서 확인하세요.</p>
	<a href="index.php">메인으로</a>
</body>

</html>
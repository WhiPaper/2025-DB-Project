<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="UTF-8">
	<title>특정 기간 특정 물품 조회</title>
</head>

<body>
	<h2>특정 기간 특정 물품 조회</h2>
	<form method="POST" action="sales_period_product_result.php">
		<label>
			시작일:
			<input type="date" name="start_date" required>
		</label><br>
		<label>
			종료일:
			<input type="date" name="end_date" required>
		</label><br>
		<label>
			상품명:
			<input type="text" name="product_name" required>
		</label><br>
		<button type="submit">조회</button>
	</form>
	<p>조회 결과는 결과 페이지에서 표 형태로 제공됩니다.</p>
	<a href="index.php">메인으로</a>
</body>

</html>
<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="UTF-8">
	<title>시간으로 음식 주문</title>
</head>

<body>
	<h2>시간으로 음식 주문</h2>
	<form method="POST" action="order_time_result.php">
		<label>
			회원 ID (Login ID):
			<input type="text" name="login_id">
		</label><br>
		<label>
			상품명:
			<input type="text" name="product_name">
		</label><br>
		<label>
			수량:
			<input type="number" name="quantity" value="1" min="1">
		</label><br>
		<button type="submit">주문하기</button>
	</form>
	<p>주문 요약 및 차감 시간은 결과 페이지에서 안내됩니다.</p>
	<a href="index.php">메인으로</a>
</body>

</html>
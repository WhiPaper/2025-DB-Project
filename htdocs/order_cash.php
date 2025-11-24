<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="UTF-8">
	<title>카드로 음식 주문</title>
</head>

<body>
	<h2>카드로 음식 주문</h2>
	<form method="POST" action="order_cash_result.php">
		<label>
			회원 ID (Login ID):
			<input type="text" name="login_id" required>
		</label><br>
		<label>
			상품명:
			<input type="text" name="product_name" required>
		</label><br>
		<label>
			수량:
			<input type="number" name="quantity" value="1" min="1" required>
		</label><br>
		<button type="submit">주문하기</button>
	</form>
	<p>결제 결과와 매출 반영 여부는 결과 페이지에서 확인합니다.</p>
	<a href="index.php">메인으로</a>
</body>

</html>
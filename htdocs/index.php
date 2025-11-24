<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="UTF-8">
	<title>회원 관리 시스템</title>
</head>

<body>
	<h1>회원 관리 시스템</h1>

	<h2>회원 / 등급 조회</h2>
	<ul>
		<li><a href="select_highest_spent_result.php">누적 결제 금액이 가장 큰 회원 조회</a></li>
		<li><a href="search_by_id.php">멤버 ID로 검색</a></li>
		<li><a href="search_by_name_phone.php">이름과 전화번호로 회원 조회</a></li>
		<li><a href="search_grade.php">특정 회원 등급 조회</a></li>
		<li><a href="member_total_spent.php">특정 회원의 누적 결제 금액 조회</a></li>
		<li><a href="member_payment_history.php">특정 회원의 전체 결제 내역 조회</a></li>
	</ul>

	<h2>매출 / 상품 통계</h2>
	<ul>
		<li><a href="sales_period.php">특정 기간의 전체 매출 조회</a></li>
		<li><a href="sales_period_product.php">특정 기간 특정 물품 조회</a></li>
		<li><a href="search_product_type.php">특정 타입의 상품 조회</a></li>
	</ul>

	<h2>주문</h2>
	<ul>
		<li><a href="order_time.php">시간으로 음식 주문</a></li>
		<li><a href="order_cash.php">카드로 음식 주문</a></li>
	</ul>
</body>

</html>
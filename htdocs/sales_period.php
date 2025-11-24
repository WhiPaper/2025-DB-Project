<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="UTF-8">
	<title>특정 기간의 전체 매출 조회</title>
</head>

<body>
	<h2>특정 기간의 전체 매출 조회</h2>
	<form method="POST" action="sales_period_result.php">
		<label>
			시작일:
			<input type="date" name="start_date" required>
		</label>
		<label>
			종료일:
			<input type="date" name="end_date" required>
		</label>
		<button type="submit">조회</button>
	</form>
	<p>입력한 기간에 대한 매출은 결과 페이지에서 합산됩니다.</p>
	<a href="index.php">메인으로</a>
</body>

</html>
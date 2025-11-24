<?php
	$login_id = $_POST['login_id'];
   $message = "";
   $member = null;

   if($login_id == "") {
	   $message = "로그인 ID를 입력하세요.";
   }
   else {
		require_once __DIR__ . "/conn.php";
	   $sql = "SELECT member_id, login_id, member_name, phone, email, remain_time, grade_id FROM members WHERE login_id = '".$login_id."'";
	   $ret = mysqli_query($con, $sql);
	   if($ret) {
		   $count = mysqli_num_rows($ret);
		   if($count == 0) {
			   $message = "검색된 회원이 없습니다.";
		   }
		   else {
			   $member = mysqli_fetch_array($ret);
		   }
	   }
	   else {
		   $message = "데이터 조회 실패!!!<br>실패 원인 :".mysqli_error($con);
	   }
	   mysqli_close($con);
   }
?>
<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="UTF-8">
	<title>멤버 ID 검색 결과</title>
</head>

<body>
	<h2>멤버 ID 검색 결과</h2>
	<?php if ($member == null): ?>
		<p><?php echo $message; ?></p>
	<?php else: ?>
		<ul>
			<li>로그인 ID: <?php echo $member['login_id']; ?></li>
			<li>이름: <?php echo $member['member_name']; ?></li>
			<li>전화번호: <?php echo $member['phone']; ?></li>
			<li>이메일: <?php echo $member['email']; ?></li>
			<li>잔여시간: <?php echo $member['remain_time']; ?>분</li>
			<li>등급 ID: <?php echo $member['grade_id']; ?></li>
		</ul>
	<?php endif; ?>
	<a href="search_by_id.php">입력 화면으로</a> |
	<a href="index.php">메인으로</a>
</body>

</html>
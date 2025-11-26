<?php
require_once __DIR__ . "/conn.php";
$member_name = $_POST['member_name'];
$login_id = $_POST['login_id'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$remain_time = (int) ($_POST['remain_time']);
$errors = [];
if ($member_name === '') {
	$errors[] = "회원 이름을 입력하세요.";
}
if ($login_id === '') {
	$errors[] = "로그인 ID를 입력하세요.";
}
if ($phone === '') {
	$errors[] = "전화번호를 입력하세요.";
}
$message = '';

if (count($errors) === 0) {
	$sql = "INSERT INTO members (member_name, login_id, phone, email, remain_time) VALUES ('"
		. $member_name . "', '" . $login_id . "', '" . $phone . "', '"
		. $email . "', " . $remain_time . ")";
	$ret = mysqli_query($con, $sql);
	if ($ret) {
		$message = "회원이 등록되었습니다. 로그인 ID: " . $login_id;
	} else {
		$message = "회원 등록에 실패했습니다. 오류: " . mysqli_error($con);
	}
} else {
	$message = '';
	for ($i = 0; $i < count($errors); $i++) {
		if ($i > 0) {
			$message .= '<br>';
		}
		$message .= $errors[$i];
	}
}
mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="UTF-8">
	<title>회원 추가 결과</title>
</head>

<body>
	<h1>회원 추가 결과</h1>
	<p><?php echo $message; ?></p>
	<p>
		<a href="member_create.php">다시 입력</a> |
		<a href="member_list.php">회원 목록</a> |
		<a href="index.php">메인으로</a>
	</p>
</body>

</html>

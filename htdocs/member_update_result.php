<?php
require_once __DIR__ . "/conn.php";
$original_login_id = $_POST['original_login_id'];
$member_name = $_POST['member_name'];
$login_id = $_POST['login_id'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$remain_time = (int) ($_POST['remain_time']);
$errors = [];
if ($original_login_id === '') {
	$errors[] = "유효한 로그인 ID가 필요합니다.";
}
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
	$email_value = ($email === '') ? "NULL" : "'" . $email . "'";
	$check_sql = "SELECT login_id FROM members WHERE members.stat = 1 AND login_id = '" . $original_login_id . "'";
	$check_ret = mysqli_query($con, $check_sql);
	if ($check_ret) {
		if (mysqli_num_rows($check_ret) === 0) {
			$message = "변경 사항이 없거나 회원을 찾을 수 없습니다.";
		} else {
			$sql = "UPDATE members SET member_name = '" . $member_name
				. "', login_id = '" . $login_id . "', phone = '" . $phone . "', email = " . $email_value
				. ", remain_time = " . $remain_time
				. " WHERE login_id = '" . $original_login_id . "'";
			$ret = mysqli_query($con, $sql);
			if ($ret) {
				$message = "회원 정보가 업데이트되었습니다.";
			} else {
				$message = "회원 정보 수정에 실패했습니다. 오류: " . mysqli_error($con);
			}
		}
	} else {
		$message = "요청을 처리할 수 없습니다. 오류: " . mysqli_error($con);
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
	<title>회원 수정 결과</title>
</head>

<body>
	<h1>회원 수정 결과</h1>
	<p><?php echo $message; ?></p>
	<p>
		<a href="member_update.php?login_id=<?php echo ($login_id === '' ? $original_login_id : $login_id); ?>">다시 수정</a> |
		<a href="member_list.php">회원 목록</a> |
		<a href="index.php">메인으로</a>
	</p>
</body>

</html>

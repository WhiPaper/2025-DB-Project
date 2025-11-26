<?php
require_once __DIR__ . "/conn.php";
$login_id = $_POST['login_id'] ?? '';
$errors = [];
if ($login_id === '') {
	$errors[] = "유효한 로그인 ID가 필요합니다.";
}
$message = '';
if (count($errors) === 0) {
	$check_sql = "SELECT login_id FROM members WHERE members.stat = 1 AND login_id = '" . $login_id . "'";
	$check_ret = mysqli_query($con, $check_sql);
	if ($check_ret) {
		if (mysqli_num_rows($check_ret) === 0) {
			$message = "해당 회원을 찾을 수 없습니다.";
		} else {
			$delete_sql = "UPDATE members SET members.stat = 0 WHERE login_id = '" . $login_id . "'";
			$delete_ret = mysqli_query($con, $delete_sql);
			if ($delete_ret) {
				$message = "회원이 삭제되었습니다.";
			} else {
				$message = "회원 삭제에 실패했습니다. 오류: " . mysqli_error($con);
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
	<title>회원 삭제 결과</title>
</head>

<body>
	<h1>회원 삭제 결과</h1>
	<p><?php echo $message; ?></p>
	<p>
		<a href="member_delete.php?login_id=<?php echo $login_id; ?>">다시 삭제</a> |
		<a href="member_list.php">회원 목록</a> |
		<a href="index.php">메인으로</a>
	</p>
</body>

</html>

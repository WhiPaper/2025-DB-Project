<?php
$message = $_GET['message'] ?? '';
$prefillLoginId = $_GET['login_id'] ?? '';
?>
<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="UTF-8">
	<title>회원 삭제</title>
</head>

<body>
	<h1>회원 삭제</h1>
	<p>
		<a href="member_list.php">회원 목록</a> |
		<a href="index.php">메인으로</a>
	</p>
	<?php if ($message !== ''): ?>
		<p><?php echo $message; ?></p>
	<?php endif; ?>
	<form method="POST" action="member_delete_result.php" onsubmit="return confirm('정말로 삭제하시겠습니까?');">
		<label>로그인 ID: <input type="text" name="login_id" maxlength="12" value="<?php echo $prefillLoginId; ?>" required></label>
		<button type="submit">회원 삭제</button>
	</form>
</body>

</html>

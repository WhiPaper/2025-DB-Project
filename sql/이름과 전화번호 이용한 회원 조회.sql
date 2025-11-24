SELECT 
	member_id,
	login_id,
	member_name,
	phone,
	email,
	remain_time,
	grade_id
FROM members
WHERE member_name = ?
  AND phone = ?;
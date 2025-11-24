USE `pcr`;

SELECT 
    m.member_name AS '이름',
    m.login_id AS '아이디',
    g.grade_name AS '현재 등급',
    FORMAT(m.total_spent, 0) AS '누적 결제 금액(원)', -- 3자리마다 콤마 표시
    m.remain_time AS '잔여 시간(분)'
FROM 
    members m
JOIN 
    grades g ON m.grade_id = g.grade_id
ORDER BY 
    m.total_spent DESC; -- 핵심: 금액이 큰 순서대로 정렬
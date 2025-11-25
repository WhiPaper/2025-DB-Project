-- 특정 회원(예: login_id = 'user01')의 누적 금액 조회
SELECT 
    member_name AS '이름',
    total_spent AS '총 결제 금액'
FROM 
    members 
WHERE 
    login_id = 'user01';
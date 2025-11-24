SET @GRADE_NAME = '실버'; -- 검색하고 싶은 등급명

SELECT
    m.login_id AS `아이디`, -- 4
    m.member_name AS `이름`, -- 4
    m.phone AS `연락처`, -- 4
    m.email AS `이메일`, -- 4
    g.grade_name AS `등급명` -- 4
FROM
    members AS m -- 1
JOIN
    grades AS g ON m.grade_id = g.grade_id -- 2
WHERE
    g.grade_name = @GRADE_NAME -- 3
    
/*
## 쿼리문 처리 순서
	1. 'members' 테이블(별명 m)을 기준으로 데이터를 가져오기.
    2. 'm' 테이블의 grade_id와 'g' 테이블(grades 테이블의 별명)의 grade_id로 JOIN.
    3. grade_name이 @GRADE_NAME인 행들만 남김.
    4. 'm' 테이블의 login_id, member_name, phone, email, grade_name을 최종 출력 결과에 포함.
*/
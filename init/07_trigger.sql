DELIMITER //

DROP TRIGGER IF EXISTS trg_member_grade_update//

CREATE TRIGGER trg_member_grade_update
BEFORE UPDATE ON members
FOR EACH ROW
BEGIN
	DECLARE v_new_grade_id INT;
    
    IF NEW.total_spent != OLD.total_spent THEN -- 만약 새로운 total_spent와 예전 total_spent가 같지 않다면
		 -- 1. 변경된 total_spent에 맞는 등급을 찾는 과정
		SELECT grade_id INTO v_new_grade_id -- 새로운 min_spend <= total_spent를 만족하는 등급 중 가장 높은 등급을 v_new_grade_id에 저장
		FROM grades
        WHERE min_spend <= NEW.total_spent
        ORDER BY min_spend DESC
        LIMIT 1; -- 가장 높은 1개의 등급만 가져오기 위해서
        
         -- 2. 새로운 등급으로 변경하는 과정
        SET NEW.grade_id = v_new_grade_id; -- 새로운 등급ID를 위에서 구했던 v_new_grade_id로 저장
	END IF;
END //

DELIMITER ;
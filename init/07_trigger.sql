SET NAMES utf8mb4;
USE pcr;

DELIMITER //

CREATE TRIGGER trg_after_order_detail_insert
AFTER INSERT ON order_details
FOR EACH ROW
BEGIN
	DECLARE v_member_id INT;
	DECLARE v_new_total_spent DECIMAL(15, 2);
    DECLARE v_new_grade_id INT;
    
    SELECT member_id INTO v_member_id
    FROM orders
    WHERE order_id = NEW.order_id;
    
    SELECT SUM(final_order_price) INTO v_new_total_spent
    FROM(
		SELECT SUM(od.price_at_sale * od.quantity) * (1 - o.discount_rate_at_order / 100.0) AS final_order_price
		FROM orders o
		JOIN order_details od ON o.order_id = od.order_id
		WHERE o.member_id = v_member_id
		GROUP BY o.order_id
    ) AS member_orders;
    
    SELECT grade_id INTO v_new_grade_id
    FROM grades
    WHERE min_spend <= v_new_total_spent
    ORDER BY min_spend DESC
    LIMIT 1;
    
    UPDATE members
    SET	
		total_spent = v_new_total_spent,
        grade_id = v_new_grade_id
	WHERE
		member_id = v_member_id;
END //

DELIMITER ;
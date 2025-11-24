SET NAMES utf8mb4;
USE pcr;
DROP PROCEDURE IF EXISTS ProcessTimeOrder;

DELIMITER //

CREATE PROCEDURE ProcessTimeOrder(
    IN p_member_id INT,
    IN p_product_id INT,
    IN p_quantity INT
)
BEGIN
    DECLARE v_unit_price INT UNSIGNED;
    DECLARE v_time_value INT UNSIGNED;
    DECLARE v_grade_id INT UNSIGNED;
    DECLARE v_discount_rate INT UNSIGNED;
    DECLARE v_order_id INT UNSIGNED;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    SELECT
        p.current_price,
        pt.time_value,
        g.grade_id,
        g.discount_rate
    INTO
        v_unit_price,
        v_time_value,
        v_grade_id,
        v_discount_rate
    FROM products p
    JOIN products_time pt ON p.product_id = pt.product_id
    JOIN members m ON m.member_id = p_member_id
    JOIN grades g ON g.grade_id = m.grade_id
    WHERE p.product_id = p_product_id
        AND p.product_type = 'TIME'
    FOR UPDATE;

    INSERT INTO orders (member_id, grade_id_at_order, discount_rate_at_order)
    VALUES (p_member_id, v_grade_id, v_discount_rate);

    SET v_order_id = LAST_INSERT_ID();

    INSERT INTO order_details (order_id, product_id, price_at_sale, quantity)
    VALUES (v_order_id, p_product_id, v_unit_price, p_quantity);

    UPDATE members
    SET remain_time = remain_time + (v_time_value * p_quantity)
    WHERE member_id = p_member_id;

    COMMIT;
END //

DELIMITER ;

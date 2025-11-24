USE pcr;

DROP PROCEDURE IF EXISTS `process_order_transaction`;

DELIMITER $$

CREATE PROCEDURE `process_order_transaction`(
    IN p_login_id VARCHAR(255),
    IN p_product_name VARCHAR(255),
    IN p_quantity INT
)
BEGIN
    DECLARE v_member_id INT;
    DECLARE v_grade_id INT;
    DECLARE v_discount_rate INT DEFAULT 0;
    DECLARE v_current_remain_time INT DEFAULT 0;
    DECLARE v_product_id INT;
    DECLARE v_price INT;
    DECLARE v_product_status TINYINT DEFAULT 1;
    DECLARE v_quantity INT DEFAULT 1;
    DECLARE v_before_discount INT;
    DECLARE v_after_discount INT;
    DECLARE v_time_reduction INT;
    DECLARE v_new_order_id BIGINT;

    SET v_quantity = IFNULL(p_quantity, 1);
    IF v_quantity < 1 THEN
        SET v_quantity = 1;
    END IF;

    SELECT member_id, grade_id, remain_time
    INTO v_member_id, v_grade_id, v_current_remain_time
    FROM members
    WHERE login_id = p_login_id;

    IF v_member_id IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = '오류: 존재하지 않는 회원입니다.';
    END IF;

    SELECT discount_rate INTO v_discount_rate
    FROM grades
    WHERE grade_id = v_grade_id;

    SELECT product_id, current_price, status
    INTO v_product_id, v_price, v_product_status
    FROM products
    WHERE product_name = p_product_name;

    IF v_product_id IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = '오류: 존재하지 않는 상품입니다.';
    END IF;
    IF v_product_status = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = '주문 실패: 해당 상품은 현재 판매가 중단되었습니다.';
    END IF;

    SET v_before_discount = v_price * v_quantity;
    SET v_after_discount = FLOOR(v_before_discount * (1 - (IFNULL(v_discount_rate, 0) * 0.01)));
    SET v_time_reduction = FLOOR(v_after_discount / 10);

    IF v_current_remain_time < v_time_reduction THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = '결제 실패: 회원의 잔여 시간이 부족합니다.';
    END IF;

    START TRANSACTION;

        INSERT INTO `orders`
        (`order_time`, `member_id`, `grade_id_at_order`, `discount_rate_at_order`)
        VALUES
        (NOW(), v_member_id, v_grade_id, IFNULL(v_discount_rate, 0));

        SET v_new_order_id = LAST_INSERT_ID();

        INSERT INTO `order_details`
        (`order_id`, `product_id`, `price_at_sale`, `quantity`)
        VALUES
        (v_new_order_id, v_product_id, v_price, v_quantity);

        UPDATE `products_food`
        SET `stock` = `stock` - v_quantity
        WHERE `product_id` = v_product_id;

        INSERT INTO `product_logs`
        (`record_time`, `product_id`, `change_stock`, `change_reason`)
        VALUES
        (NOW(), v_product_id, -v_quantity, 'SALE');

        UPDATE `members`
        SET `remain_time` = `remain_time` - v_time_reduction
        WHERE `member_id` = v_member_id;

    COMMIT;

    SELECT CONCAT('시간 결제 완료! 차감된 시간: ', v_time_reduction, '분') AS Result;

END$$

DELIMITER ;

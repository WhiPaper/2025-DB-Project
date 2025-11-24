SET NAMES utf8mb4;
USE `pcr`;

-- Caller must set @product_var, @quantity_var, and @loginID_var before invoking the procedure.
DROP PROCEDURE IF EXISTS `process_order_transaction`;

DELIMITER $$

CREATE PROCEDURE `process_order_transaction`()
BEGIN
    SET @memberID_var = NULL;
    SET @gradeID_var = NULL;
    SET @discount_rate_var = 0;
    SET @current_remain_time = 0;
    SET @product_status = 1;

    -- Load member context
    SELECT member_id, grade_id, total_spent, remain_time
    INTO @memberID_var, @gradeID_var, @current_total_spent, @current_remain_time
    FROM members
    WHERE login_id = @loginID_var;

    IF @memberID_var IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = '오류: 존재하지 않는 회원입니다.';
    END IF;

    -- Determine discount rate
    SELECT discount_rate INTO @discount_rate_var
    FROM grades
    WHERE grade_id = @gradeID_var;

    -- Load product info
    SELECT product_id, current_price, status
    INTO @productID_var, @price_var, @product_status
    FROM products
    WHERE product_name = @product_var;

    IF @productID_var IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = '오류: 존재하지 않는 상품입니다.';
    END IF;
    IF @product_status = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = '주문 실패: 해당 상품은 현재 판매가 중단되었습니다.';
    END IF;

    SET @before_discount_var = @price_var * @quantity_var;
    SET @after_discount_var = FLOOR(@before_discount_var * (1 - (IFNULL(@discount_rate_var, 0) * 0.01)));

    SET @time_reduction = FLOOR(@after_discount_var / 10);

    IF @current_remain_time < @time_reduction THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = '결제 실패: 회원의 잔여 시간이 부족합니다.';
    END IF;

    START TRANSACTION;

        INSERT INTO `orders`
        (`order_time`, `member_id`, `grade_id_at_order`, `discount_rate_at_order`)
        VALUES
        (NOW(), @memberID_var, @gradeID_var, IFNULL(@discount_rate_var, 0));

        SET @new_order_id = LAST_INSERT_ID();

        INSERT INTO `order_details`
        (`order_id`, `product_id`, `price_at_sale`, `quantity`)
        VALUES
        (@new_order_id, @productID_var, @price_var, @quantity_var);

        UPDATE `products_food`
        SET `stock` = `stock` - @quantity_var
        WHERE `product_id` = @productID_var;

        INSERT INTO `product_logs`
        (`record_time`, `product_id`, `change_stock`, `change_reason`)
        VALUES
        (NOW(), @productID_var, -@quantity_var, 'SALE');

        UPDATE `members`
        SET `remain_time` = `remain_time` - @time_reduction
        WHERE `member_id` = @memberID_var;

    COMMIT;

    SELECT CONCAT('시간 결제 완료! 차감된 시간: ', @time_reduction, '분') AS Result;

END$$

DELIMITER ;

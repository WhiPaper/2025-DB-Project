USE `pcr`;

-- [1. 변수 설정]
SET @product_var = "새우깡";
SET @quantity_var = 100;
SET @loginID_var = "minhee_k";

-- ==========================================================
-- [프로시저 생성: 현금/카드 상품 주문 (재고 검증 추가)]
-- ==========================================================
DROP PROCEDURE IF EXISTS `process_cash_order`;

DELIMITER $$

CREATE PROCEDURE `process_cash_order`()
BEGIN
    -- [0. 변수 초기화]
    SET @memberID_var = NULL;
    SET @gradeID_var = NULL;
    SET @discount_rate_var = 0;
    SET @product_status = 1; 
    SET @current_stock = 0; -- [추가] 현재 재고를 담을 변수

    -- [2. 데이터 조회]
    -- A. 회원 정보 조회
    SELECT member_id, grade_id, total_spent 
    INTO @memberID_var, @gradeID_var, @current_total_spent
    FROM members 
    WHERE login_id = @loginID_var;

    -- 회원 존재 여부 확인
    IF @memberID_var IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = '오류: 존재하지 않는 회원입니다.';
    END IF;

    -- B. 할인율 조회
    SELECT discount_rate INTO @discount_rate_var 
    FROM grades 
    WHERE grade_id = @gradeID_var;

    -- C. 상품 정보 조회
    SELECT product_id, current_price, status 
    INTO @productID_var, @price_var, @product_status
    FROM products 
    WHERE product_name = @product_var;

    -- 상품 존재 확인
    IF @productID_var IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = '오류: 존재하지 않는 상품입니다.';
    END IF;
    -- 판매 상태 확인
    IF @product_status = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = '주문 실패: 판매 중단된 상품입니다.';
    END IF;

    -- [D. 재고량 확인 및 검증] - (새로 추가된 핵심 로직)
    -- products_food 테이블에서 해당 상품의 현재 재고를 조회합니다.
    SELECT stock INTO @current_stock
    FROM products_food
    WHERE product_id = @productID_var;

    -- 1. 재고 정보 존재 여부 확인 (음식 상품이 아닐 경우 등)
    IF @current_stock IS NULL THEN
         SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = '오류: 해당 상품의 재고 정보를 찾을 수 없습니다.';
    END IF;

    -- 2. 재고 부족 확인 (주문 수량이 현재 재고보다 크면 에러)
    IF @current_stock < @quantity_var THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = '주문 실패: 상품의 재고가 부족합니다.';
    END IF;


    -- [3. 가격 계산]
    -- 가격 = 단가 * 수량
    SET @before_discount_var = @price_var * @quantity_var;
    -- 최종 결제 금액 = 가격 * (1 - 할인율)
    SET @after_discount_var = FLOOR(@before_discount_var * (1 - (IFNULL(@discount_rate_var, 0) * 0.01)));


    -- [4. 트랜잭션 실행]
    START TRANSACTION;

        -- A. 주문 내역 생성 (Orders)
        INSERT INTO `orders` 
        (`order_time`, `member_id`, `grade_id_at_order`, `discount_rate_at_order`) 
        VALUES 
        (NOW(), @memberID_var, @gradeID_var, IFNULL(@discount_rate_var, 0));
        
        SET @new_order_id = LAST_INSERT_ID();

        -- B. 주문 상세 저장 (Order Details)
        INSERT INTO `order_details` 
        (`order_id`, `product_id`, `price_at_sale`, `quantity`) 
        VALUES 
        (@new_order_id, @productID_var, @price_var, @quantity_var);

        -- C. [확실한 처리] 일별 매출(daily_sales) 증가
        INSERT INTO `daily_sales` (`date`, `total_sales`)
        VALUES (CURDATE(), @after_discount_var)
        ON DUPLICATE KEY UPDATE `total_sales` = `total_sales` + @after_discount_var;

        -- D. 음식 재고 차감 (위에서 검증했으므로 음수 될 걱정 없음)
        UPDATE `products_food` 
        SET `stock` = `stock` - @quantity_var 
        WHERE `product_id` = @productID_var;

        -- E. 로그 기록
        INSERT INTO `product_logs` 
        (`record_time`, `product_id`, `change_stock`, `change_reason`) 
        VALUES 
        (NOW(), @productID_var, -@quantity_var, 'SALE');

        -- F. [요청 반영] 회원 누적 사용 금액(total_spent) 강제 업데이트
        UPDATE `members`
        SET `total_spent` = `total_spent` + @after_discount_var
        WHERE `member_id` = @memberID_var;

    COMMIT;
    
    -- 결과 확인 (남은 재고량도 함께 표시)
    SELECT CONCAT('주문 완료! 결제 금액: ', @after_discount_var, '원 (남은 재고: ', @current_stock - @quantity_var, '개)') AS Result;

END$$

DELIMITER ;

-- [5. 실행]
CALL `process_cash_order`();
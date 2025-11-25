USE `pcr`;

-- [1. 변수 설정]
SET @product_var = "새우깡";
SET @quantity_var = 100;
SET @loginID_var = "minhee_k";

-- ==========================================================
-- [프로시저 생성: 시간 차감 상품 주문 (재고 검증 추가)]
-- ==========================================================
DROP PROCEDURE IF EXISTS `process_order_transaction`;

DELIMITER $$

CREATE PROCEDURE `process_order_transaction`()
BEGIN
    -- [0. 변수 초기화]
    SET @memberID_var = NULL;
    SET @gradeID_var = NULL;
    SET @discount_rate_var = 0;
    SET @current_remain_time = 0;
    SET @product_status = 1; 
    SET @hourly_rate_var = 0; 
    SET @current_stock = 0; -- [추가] 현재 재고를 담을 변수

    -- [2. 데이터 조회]
    -- A. 회원 정보 조회
    SELECT member_id, grade_id, total_spent, remain_time 
    INTO @memberID_var, @gradeID_var, @current_total_spent, @current_remain_time
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

    -- C. 상품 정보 조회 (구매하려는 음식)
    SELECT product_id, current_price, status 
    INTO @productID_var, @price_var, @product_status
    FROM products 
    WHERE product_name = @product_var;

    -- 상품 존재 및 판매 상태 확인
    IF @productID_var IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = '오류: 존재하지 않는 상품입니다.';
    END IF;
    IF @product_status = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = '주문 실패: 해당 상품은 현재 판매가 중단되었습니다.';
    END IF;

    -- [D. 재고량 확인 및 검증] - (새로 추가된 로직)
    -- products_food 테이블에서 현재 재고 조회
    SELECT stock INTO @current_stock
    FROM products_food
    WHERE product_id = @productID_var;

    -- 재고 정보 존재 여부 확인
    IF @current_stock IS NULL THEN
         SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = '오류: 해당 상품의 재고 정보를 찾을 수 없습니다.';
    END IF;

    -- 재고 부족 확인 (핵심 검증)
    IF @current_stock < @quantity_var THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = '주문 실패: 상품의 재고가 부족합니다.';
    END IF;


    -- [E. 기준 시간권(1시간) 요금 조회]
    SELECT current_price INTO @hourly_rate_var
    FROM products
    WHERE product_name = '1시간'; 

    -- 기준 요금 조회 실패 시 예외 처리
    IF @hourly_rate_var IS NULL OR @hourly_rate_var = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = '시스템 오류: 기준 시간권(1시간) 가격 정보를 찾을 수 없습니다.';
    END IF;

    -- [3. 계산 로직]
    -- 음식 가격 및 할인 적용 계산
    SET @before_discount_var = @price_var * @quantity_var;
    SET @after_discount_var = FLOOR(@before_discount_var * (1 - (IFNULL(@discount_rate_var, 0) * 0.01)));
    
    -- 차감할 시간 계산 (동적 가격 반영)
    SET @time_reduction = FLOOR((@after_discount_var / @hourly_rate_var) * 60);

    -- 잔여 시간 부족 체크
    IF @current_remain_time < @time_reduction THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = '결제 실패: 회원의 잔여 시간이 부족합니다.';
    END IF;

    -- [4. 트랜잭션 실행]
    START TRANSACTION;

        -- A. 주문 내역 생성
        INSERT INTO `orders` 
        (`order_time`, `member_id`, `grade_id_at_order`, `discount_rate_at_order`) 
        VALUES 
        (NOW(), @memberID_var, @gradeID_var, IFNULL(@discount_rate_var, 0));
        
        SET @new_order_id = LAST_INSERT_ID();

        -- B. 주문 상세 저장
        INSERT INTO `order_details` 
        (`order_id`, `product_id`, `price_at_sale`, `quantity`) 
        VALUES 
        (@new_order_id, @productID_var, @price_var, @quantity_var);

        -- C. 음식 재고 차감 (검증 완료된 상태)
        UPDATE `products_food` 
        SET `stock` = `stock` - @quantity_var 
        WHERE `product_id` = @productID_var;

        -- D. 로그 기록 (판매량 음수 처리)
        INSERT INTO `product_logs` 
        (`record_time`, `product_id`, `change_stock`, `change_reason`) 
        VALUES 
        (NOW(), @productID_var, -@quantity_var, 'SALE');

        -- E. 잔여 시간 차감
        UPDATE `members` 
        SET `remain_time` = `remain_time` - @time_reduction 
        WHERE `member_id` = @memberID_var;

    COMMIT;
    
    -- 결과 확인 (남은 재고도 함께 표시)
    SELECT CONCAT('시간 결제 완료! 차감된 시간: ', @time_reduction, '분 (남은 재고: ', @current_stock - @quantity_var, '개)') AS Result;

END$$

DELIMITER ;

-- [5. 실행]
CALL `process_order_transaction`();
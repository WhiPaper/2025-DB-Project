SELECT 
	member_id AS '멤버 아이디',
    order_time AS '주문 시간',
    product_name AS '상품명',
    product_type AS '타입', -- TIME(시간), FOOD(음식) 구분
    price_at_sale AS '판매단가',
    quantity AS '수량',
    CONCAT(discount_rate_at_order, '%') AS '할인율',
    -- 해당 품목에 대한 최종 금액
    after_discount_price AS '결제금액'
FROM v_member_payment_history 
WHERE login_id = 'user01'
ORDER BY order_time DESC;
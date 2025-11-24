CREATE OR REPLACE VIEW v_member_payment_history AS
SELECT 
	o.member_id,
    o.order_time,
    p.product_name,
    p.product_type, -- TIME(시간), FOOD(음식) 구분
    od.price_at_sale,
    od.quantity,
    o.discount_rate_at_order,
    -- 해당 품목에 대한 최종 금액
    FLOOR((od.price_at_sale * od.quantity) * ((100 - o.discount_rate_at_order) / 100)) AS after_discount_price
FROM 
    orders o
JOIN 
    order_details od ON o.order_id = od.order_id
JOIN 
    products p ON od.product_id = p.product_id

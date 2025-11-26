SELECT p.product_name AS '상품명', 
    IFNULL(SUM(FLOOR( (od.price_at_sale * od.quantity) * (100 - o.discount_rate_at_order) / 100 )), 0) AS '총 판매액' 
FROM 
    order_details od 
JOIN 
    orders o ON od.order_id = o.order_id 
JOIN 
    products p ON od.product_id = p.product_id 
WHERE 
    o.order_time >= @START_DATE 
    AND o.order_time <= @END_DATE 
    AND p.product_name = @PRODUCT_NAME 
GROUP BY
    p.product_name
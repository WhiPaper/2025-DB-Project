SET NAMES utf8mb4;
USE `pcr`;

DROP VIEW IF EXISTS `pcr`.`v_member_payment_history`;

CREATE VIEW `pcr`.`v_member_payment_history` AS
SELECT
    o.member_id,
    m.login_id,
    o.order_time,
    p.product_name,
    p.product_type,
    od.price_at_sale,
    od.quantity,
    o.discount_rate_at_order,
    FLOOR((od.price_at_sale * od.quantity) * ((100 - o.discount_rate_at_order) / 100)) AS after_discount_price
FROM orders AS o
JOIN members AS m ON o.member_id = m.member_id
JOIN order_details AS od ON o.order_id = od.order_id
JOIN products AS p ON od.product_id = p.product_id;

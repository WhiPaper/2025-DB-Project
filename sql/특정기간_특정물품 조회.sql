SET @START_DATE = '2025-07-01 00:00:00'; -- 검색을 시작하고 싶은 날짜
SET @END_DATE = '2025-11-30 23:59:59'; -- 검색을 끝내고 싶은 날짜
SET @PRODUCT_NAME = '짜파게티'; -- 검색하고 싶은 상품명

SELECT
	p.product_name AS '상품명', -- 5
	SUM(od.price_at_sale * od.quantity) AS '총 판매액' -- 6
FROM
	order_details AS od -- 1
JOIN
	orders AS o ON od.order_id = o.order_id -- 2
JOIN
	products AS p ON od.product_id = p.product_id -- 3
WHERE
	p.product_name = @PRODUCT_NAME -- 4
    AND o.order_time >= @START_DATE AND order_time <= @END_DATE -- 4
GROUP BY
	p.product_name; -- 7
    
/*
## 쿼리문 처리 순서
	1. 'order_details' 테이블(별명 od)을 기준으로 데이터를 가져오기.
    2. 'od' 테이블의 order_id와 'o' 테이블(orders 테이블의 별명)의 order_id로 JOIN.
    3. 'od' 테이블의 product_id와 'p' 테이블(products 테이블의 별명)의 product_id로 JOIN.
    4. product_name이 @PRODUCT_NAME과 같고, order.time이 @START_DATE보다 크고, @END_DATE보다 작은 행들만 남김.
    5. JOIN한 결과에서, 'p' 테이블의 product_name을 최종 출력 결과에 포함.
    6. price_at_sale과 quantity를 곱하고, 그 곱한 값을 모두 더하여 총 매출액을 최종 출력 결과에 포함.
    7. product_name으로 그룹을 만들어서 최종 결과를 출력.
*/
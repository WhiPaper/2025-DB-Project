SET @PRODUCT_TYPE = 'FOOD'; -- 특정한 타입의 상품을 조회하고 싶을 때, 해당 변수에서 타입명만 바꿔주면 됨.

SELECT
    p.product_name AS `상품명`, -- 4
    SUM(od.quantity) AS `총 판매 수` -- 5
FROM
    order_details AS od -- 1
JOIN
    products AS p ON od.product_id = p.product_id -- 2
WHERE
    p.product_type = @PRODUCT_TYPE -- 3
GROUP BY
    p.product_name -- 6
ORDER BY
	2 DESC; -- 7, '2'가 의미하는 것은 SUM(od.quantity) AS '총 판매 수', 한글 인코딩이 깨져서 그런지 ORDER BY가 정상적으로 되지 않아서 해당 방식을 사용했음.
    
/*
## 쿼리문 처리 순서
	1. 'order_details' 테이블(별명 od)을 기준으로 데이터를 가져오기.
    2. 'od' 테이블의 product_id와 'p' 테이블(product 테이블의 별명)의 product_id로 JOIN.
    3. 2단계에서 JOIN된 테이블에서, product_type이 @PRODUCT_TPYE(현재는 'FOOD')의 값과 일치하는 행들만 남김.
    4. JOIN한 결과에서, 'p' 테이블의 product_name을 최종 출력 결과에 포함.
    5. JOIN한 결과에서, 각 상품별로, 'od' 테이블의 quantity를 모두 더하여서 최종 출력 결과에 포함하고, 별명을 total_sold라고 붙임.
    6. product_name이 같은 것끼리 하나의 그룹으로 묶음.
    7. total_sold값을 기준으로 내림차순으로 출력.
*/
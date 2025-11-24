-- 예시: 2025년 11월 1일 ~ 11월 30일 매출 조회
SELECT 
    SUM(total_sales) AS '기간 내 총 매출'
FROM 
    daily_sales
WHERE 
    date >= '2025-11-01' AND date <= '2025-11-30';
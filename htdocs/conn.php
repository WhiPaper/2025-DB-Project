<?php

declare(strict_types=1);

function get_pdo(): PDO
{
	static $pdo = null;

	if ($pdo instanceof PDO) {
		return $pdo;
	}

	$dsn = 'mysql:host=db;dbname=pcr;charset=utf8mb4';
	$pdo = new PDO($dsn, 'root', 'passwd', [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES => false,
	]);

	return $pdo;
}

function load_sql(string $filename): string
{
	$path = dirname(__DIR__) . '/sql/' . $filename;

	if (!is_file($path)) {
		throw new RuntimeException("SQL file not found: {$filename}");
	}

	return trim(file_get_contents($path));
}

function strip_sql_boilerplate(string $sql): string
{
	$sql = preg_replace('/^USE\s+`?[^`]+`?;\s*/mi', '', $sql);
	$sql = preg_replace('/--.*$/m', '', $sql);
	$sql = preg_replace('#/\*.*?\*/#s', '', $sql);

	return trim($sql);
}

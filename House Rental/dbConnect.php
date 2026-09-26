<?php
    /*Prevent back-button access to protected pages after logout*/
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0");
    header("Pragma: no-cache");
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $dbHost = "localhost";
    $dbUser = "root";
    $dbPassword = "";
    $dbName = "House Rental";

    try
    {
        $dbConnect = mysqli_connect($dbHost, $dbUser, $dbPassword);
        mysqli_query($dbConnect, "CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
        mysqli_select_db($dbConnect, $dbName);
        mysqli_set_charset($dbConnect, "utf8mb4");

        foreach (array(
            "ALTER TABLE properties ADD COLUMN PropertyType varchar(50) DEFAULT NULL",
            "ALTER TABLE properties ADD COLUMN PropertyRemarks varchar(20) DEFAULT NULL"
            ,"ALTER TABLE users ADD COLUMN AccountStatus varchar(20) NOT NULL DEFAULT 'Active'"
            ,"ALTER TABLE properties ADD COLUMN HostPhone1 varchar(10) DEFAULT NULL"
            ,"ALTER TABLE properties ADD COLUMN HostPhone2 varchar(10) DEFAULT NULL"
            ,"ALTER TABLE properties ADD COLUMN HostEmail varchar(100) DEFAULT NULL"
        ) as $schemaChange) {
            try {
                mysqli_query($dbConnect, $schemaChange);
            } catch (mysqli_sql_exception $exception) {
                if ($exception->getCode() != 1060 && $exception->getCode() != 1146) {
                    throw $exception;
                }
            }
        }
    }
    catch (mysqli_sql_exception $exception)
    {
        http_response_code(500);
        exit("Database connection failed: " . $exception->getMessage());
    }

    function normalizeImagePath($imagePath, $fallback = 'profileImg/_photo.jpg')
    {
        $imagePath = trim((string) $imagePath);

        if ($imagePath === '') {
            return $fallback;
        }

        $normalizedImagePath = ltrim($imagePath, '/');

        if ($normalizedImagePath === '') {
            return $fallback;
        }

        if (preg_match('#^https?://#i', $normalizedImagePath)) {
            return $normalizedImagePath;
        }

        return $normalizedImagePath;
    }

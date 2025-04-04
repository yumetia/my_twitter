
<?php
$sql = "Config/common-database.sql";
$config = parse_ini_file("Config/config.ini");

if (file_exists($sql)) {
    try {

        $pdo = new PDO("mysql:host={$config['host']};charset=utf8", "{$config['username']}", "{$config['password']}");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $dbExists = $pdo->query("SHOW DATABASES LIKE '{$config['dbname']}}'")->rowCount() > 0;

        if ($dbExists) {
            echo "Database already exists. No import needed.";
        } else {
            $pdo->exec("CREATE DATABASE {$config['dbname']} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

            $pdo->exec("USE {$config['dbname']}");

            $sqlContent = file_get_contents($sql);
            $queries = explode(";", $sqlContent);

            foreach ($queries as $query) {
                $trimmedQuery = trim($query);
                if (!empty($trimmedQuery)) {
                    $pdo->exec($trimmedQuery . ";");
                }
            }

            echo "Database imported successfully.";
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

?>

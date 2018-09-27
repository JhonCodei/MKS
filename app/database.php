<?php
Class Database
{
    private $pdo = null;
    
    public static function Connection()
    {
        try
        {
            $pdo = new PDO(DB_DSN . ":host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ERRMODE_EXCEPTION, PDO::ATTR_ERRMODE);
            $pdo->exec("SET CHARACTER SET UTF8");
        }
        catch (PDOException $e)
        {
            print $e->getMessage();
        }
        return $pdo;
    }
}
?>
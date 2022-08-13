<?php
class db_pdo
{
    /*SQL DATABASE*/
    const DB_SERVER_TYPE = 'mysql'; // MySQL or MariaDB server
    const DB_HOST = '127.0.0.1'; // local server on my laptop
    const DB_PORT = 3307; // optional, default 3306, use 3307 for MariaDB
    const DB_NAME = 'classicmodels'; // for Database classicmodels
    const DB_CHARSET = 'utf8mb4'; //optional

    const DB_USER_NAME = 'web_site'; // default user, other users can be created with phpMyAdmin
    const DB_PASSWORD = '1234567890';

    // PDO connection options
    const DB_OPTIONS = [
        // throw exception on SQL errors
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        // return records with associative keys only, no numeric index
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        //
        PDO::ATTR_EMULATE_PREPARES => false
    ];

    private $DB_connexion;

    function __construct()
    {
        $this->connect();
    }

    public function connect()
    {
        try {
            $DSN = self::DB_SERVER_TYPE . ':host=' . self::DB_HOST . ';port=' . self::DB_PORT . ';dbname=' . self::DB_NAME . ';charset=' . self::DB_CHARSET;
            $this->DB_connexion = new PDO($DSN, self::DB_USER_NAME, self::DB_PASSWORD, self::DB_OPTIONS);
            // echo 'connected to MariaDB';
        } catch (PDOException $e) {
            header('HTTP/1.0 500 Database connection error' . $e->getMessage());
            exit('Database connection error' . $e->getMessage());
        }
    }

    public function disconnect()
    {
        $this->DB_connexion = null;
    }


    /**
     * for insert, update, Delete queries
     *@return: PDOstatement object
     */
    public function query(string $sql)
    {
        try {
            return $this->DB_connexion->query($sql);
        } catch (PDOException $e) {
            header('HTTP/1.0 500 Database error' . $e->getMessage());
            exit('Database error ' . $e->getMessage());
        } finally {
            $this->disconnect();
        }
    }

    /**
     * for Select queries, returns a table
     *
     */
    public function querySelect(string $sql)
    {
        return $this->query($sql)->fetchAll();
    }

    public function table($tableName)
    {
        return $this->querySelect("SELECT * FROM " . $tableName);
    }


    /**
     * Parameterized query
     *
     */
    public function queryParam(string $sql, array $params)
    {
        try {
            $stm = $this->DB_connexion->prepare($sql);
            $stm->execute($params);
            return $stm;
        } catch (PDOException $e) {
            header('HTTP/1.0 500 Database error' . $e->getMessage());
            exit('Database error ' . $e->getMessage());
        } finally {
            $this->disconnect();
        }
    }

    /**
     * Parameterized query Select
     *
     */
    public function querySelectParam(string $sql, array $params)
    {
        return $this->queryParam($sql, $params)->fetchAll();
    }
}

<?php
declare(strict_types=1);
namespace App\Model;

use App\Exception\StorageException;
use App\Exception\ConfigurationException;
use PDO; // pobieranie klasy(gotowej) do łaczenia z db
use PDOException;

abstract class AbstractDatabase
{
    protected PDO $conn;

    public function __construct(array $config)
    {
      try {
        $this->validateConfig($config);
        $this->createConnection($config);
      } catch (PDOException $e) {
        throw new StorageException("Error Processing Request", 1);
      }
    }

    private function createConnection(array $config): void
    {
      $dsn = "mysql:dbname={$config['database']};host={$config['host']}";
      $this->conn = new PDO(
        $dsn,
        $config['user'],
        $config['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION] //Opcja dodatkowa- pokazzuje bledy
      );
    }
  
  
    //SPR CZY CONFIG JEST POPRAWNY
    private function validateConfig(array $config): void
    {
      if (
        empty($config['database'])
        || empty($config['host'])
        || empty($config['user'])
        || empty($config['password'])
      ) {
        throw new ConfigurationException('Storage congiguration error');
      }
    }
}
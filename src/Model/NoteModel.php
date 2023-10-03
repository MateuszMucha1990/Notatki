<?php

declare(strict_types=1);

namespace App\Model;


use PDO;
use App\Exception\StorageException;
use App\Exception\NotFoundException;
use Throwable;


class NoteModel extends AbstractDatabase
{
 

  //POBRANIE NOTATEK Z BAZY
  public function getNote(int $id): array
  {
    try {
      $query = "SELECT* FROM notes WHERE id = $id";
      $result = $this->conn->query($query);
      $note = $result->fetch(PDO::FETCH_ASSOC);
    } catch (Throwable $e) {
      throw new StorageException('Nie udało sie pobrac notatki.', 400, $e);
    }

    if (!$note) {
      throw new StorageException("Nie ma notatki o takim id");  //powinno byc- NotFoundException ale cos nie dziala
    }
    return $note;
  }

  public function searchNotes(
    string $phrase,
    int $pageNumber,
    int $pageSize,
    string $sortBy,
    string $sortOrder
  ): array {
    try {
      $limit = $pageSize;
      $offset = ($pageNumber - 1) * $pageSize;

      //SORTOWANIE
      if (!in_array($sortBy, ['created', 'title'])) {
        $sortBy = 'desc';
      }
      if (!in_array($sortOrder, ['asc', 'desc'])) {
        $sortOrder = 'title';
      }

      $phrase = $this->conn->quote('%' . $phrase . '%', PDO::PARAM_STR);

      $query = "SELECT id, title, created FROM notes 
    WHERE title LIKE ($phrase)
    ORDER BY $sortBy $sortOrder LIMIT $offset, $limit";

      $result = $this->conn->query($query);
      $notes = $result->fetchAll(PDO::FETCH_ASSOC);

      return $notes;
    } catch (Throwable $e) {
      throw new StorageException("Nie udało sie wyszukac notatek", 400, $e);
    }
  }

  public function getSearchCount($phrase): int
  {
    try {
      $phrase = $this->conn->quote('%' . $phrase . '%', PDO::PARAM_STR);
      $query = "SELECT count(*) AS cn FROM notes WHERE title LIKE ($phrase)";

      $result = $this->conn->query($query);
      $result = $result->fetch(PDO::FETCH_ASSOC);
      if ($result === false) {
        throw new StorageException('Bład przy próbie pobrania ilosci notatek', 400);
      }
      return (int) $result['cn'];
    } catch (Throwable $e) {
      throw new StorageException("Nie udało sie pobrac informacji o liczbie notatek", 400, $e);
    }
  }






  public function getNotes(int $pageNumber, int $pageSize, string $sortBy, string $sortOrder): array
  {
    try {
      $limit = $pageSize;
      $offset = ($pageNumber - 1) * $pageSize;

      //SORTOWANIE
      if (!in_array($sortBy, ['created', 'title'])) {  //in_array spr czy wartosc(created lub title) NIE jest w tabl
        $sortBy = 'desc';
      }
      if (!in_array($sortOrder, ['asc', 'desc'])) {
        $sortOrder = 'title';
      }


      $query = "SELECT id, title, created FROM notes ORDER BY $sortBy $sortOrder LIMIT $offset, $limit";

      $result = $this->conn->query($query);
      $notes = $result->fetchAll(PDO::FETCH_ASSOC); //robi petle po danych jak foreach i zwroci w wszystkie []

      return $notes;
    } catch (Throwable $e) {
      throw new StorageException("Nie udało sie pobrac notatek", 400, $e);
    }
  }



  public function getCount(): int
  {
    try {
      $query = "SELECT count(*) AS cn FROM notes";

      $result = $this->conn->query($query);
      $result = $result->fetch(PDO::FETCH_ASSOC);
      if ($result === false) {
        throw new StorageException('Bład przy próbie pobrania ilosci notatek', 400);
      }
      return (int) $result['cn'];
    } catch (Throwable $e) {
      throw new StorageException("Nie udało sie pobrac informacji o liczbie notatek", 400, $e);
    }
  }


  public function createNote(array $data): void
  {
    try {
      //zmienne
      //quote 'escapuje'dane- trudniej je wykrasc?
      $title = $this->conn->quote($data['title']);
      $description = $this->conn->quote($data['description']);
      $created = $this->conn->quote(date('Y-m-d H:i:s'));

      //budujemy query
      $query = "INSERT INTO notes(title, description, created) VALUES($title, $description, $created)";

      //wykonujemy zapytanie do bazy danych
      $this->conn->exec($query);
    } catch (\Throwable $e) {
      throw new StorageException('Nie udalo sie utworzyc notatki', 400);
    }
  }


  public function editNote(int $id, array $data): void
  {
    try {
      $title = $this->conn->quote(($data['title']));
      $description = $this->conn->quote(($data['description']));

      $query = "
    UPDATE notes
    SET title= $title, description = $description
    WHERE id=$id
    ";

      $this->conn->exec($query);
    } catch (Throwable $e) {
      throw new StorageException('Nie udało sie zaktualizować notatki!', 400, $e);
    }
  }


  public function deleteNote(int $id): void
  {
    try {
      $query = "DELETE FROM notes WHERE id=$id LIMIT 1";
      $this->conn->exec($query);
    } catch (Throwable $e) {
      throw new StorageException('Nie udało sie usunać notatki', 400, $e);
    }
  }

 
}

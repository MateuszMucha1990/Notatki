<div class="show">
    <?php $note = $params['note'] ?? null ?>
    <?php if ($note) : ?>
        <ul>
            <li>Id: <?php echo ($note['id']) ?></li>
            <li>tytuł: <?php echo ($note['title']) ?></li>
            <li>Opis: <?php echo ($note['description']) ?> </li>
            <li>Zapisano: <?php echo ($note['created']) ?> </li>
        </ul>

<form action="/?action=delete" method="post">
  <input name="id" type="hidden" value="<?php echo ($note['id'])  ?>"/>  
 <input type="submit" value="usuń">
</form>

    <?php else : ?>
        <div>  Brak notatki do wyswietlenia</div>
    <?php endif ?>
    <a href="/">
        <button>Powrót do listy</button></a>
</div>
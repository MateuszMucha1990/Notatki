<div>
    <h3>Edycja</h3>
    <div>
        <?php if (!empty($params['note'])) : ?>
            <?php $note = $params['note']; ?>

            <form action="/?action=edit" class="note-form" method="post">
                <input name="id" type="hidden" value="<?php echo $note['id'] ?>">
                <ul>
                    <li>
                        <label>Tytuł <span class="required">*</span></label>
                        <input type="text" name="title" class="field-long" value="<?php echo $note['title'] ?>" />
                    </li>
                    <li>
                        <label>Opis</label>
                        <textarea name="description" id="fields" class="field-long field-textarea"><?php echo $note['description'] ?></textarea>
                    </li>
                    <li>
                        <input type="submit" value="Submit" />
                    </li>
                </ul>
            </form>
        <?php else : ?>
            <div>Brak danych do wyświetlnia
                <a href="/">
                    <button>Powrot do listy</button>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
<div>
    <h3>nowa notatka</h3>
    <div>

       <form action="/?action=create" class="note-form" method="post">
        <ul>
            <li>
                <label>Tytuł <span class="required">*</span></label>
                <input type="text" name="title" class="field-long" />
            </li>
            <li>
                <label>Opis</label>
                <textarea name="description" id="fields" class="field-long field-textarea"></textarea>
            </li>
            <li>
                <input type="submit" value="Submit" />
            </li>
        </ul>
       </form>
    </div>
</div>
<?php if ($type==1):?>
    <label>ВУЗ</label>
<?else:?>
    <label>Учебное заведение</label>
<?endif?>

<?php if ($universities):?>
    <select name="form[<?=$field_name?>]">
        <?php foreach ($universities as $university):?>
            <option value="<?=$university->getId();?>"><?=$university->name?></option>
        <?endforeach?>
    </select>
<?endif?>
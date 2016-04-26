<script>
    $(document).ready(function(){
        var controller = new AccountEditController();
        controller.container = '.content .edit-form';
        controller.init();
    });
</script>
<div class="edit-form block">
    <table class="styled-table">
        <input type="hidden" name="account_id" value="<?php echo $id; ?>" />
        <tr>
            <td>Фамилия</td>
            <td><input type="text" name="last_name" value="<?php echo $last_name; ?>" /></td>
        </tr>
        <tr>
            <td>Имя</td>
            <td><input type="text" name="first_name" value="<?php echo $first_name; ?>" /></td>
        </tr>
        <tr>
            <td>Отчество</td>
            <td><input type="text" name="middle_name" value="<?php echo $middle_name; ?>" /></td>
        </tr>
        <tr>
            <td>Ник</td>
            <td><input type="text" name="nick" value="<?php echo $nick; ?>" /></td>
        </tr>
        <tr>
            <td>Пароль</td>
            <td><input type="text" name="password" value="" /></td>
        </tr>
        <tr>
            <td>Повторите пароль</td>
            <td><input type="text" name="password2" value="" /></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><input type="text" name="email" value="<?php echo $email; ?>" /></td>
        </tr>
        <tr>
            <td>Телефоны (через запятую)</td>
            <td><input type="text" name="phone" value="<?php echo $phone; ?>" /></td>
        </tr>
        <tr>
            <td colspan="2"> <input type="submit" name="save" value="Сохранить" /></td>
        </tr>
    </table>
</div>






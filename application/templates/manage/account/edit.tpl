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
            <td >Подтвержден:</td>
            <td><input type="hidden" id="is_confirmed" name="is_confirmed" value="<?php echo $is_confirmed; ?>">
                <input type="checkbox" id="is_confirmed_checkbox"
                       onclick="document.getElementById('is_confirmed').value=(this.checked?1:0);"
                  <?php echo $is_confirmed?'checked="checked"':''; ?>>
                &nbsp;
                <label class="label-for-checkbox" for="is_confirmed_checkbox">Да/нет</label></td>
        </tr>
        <tr>
            <td >Доступ к аналитической информации:</td>
            <td><input type="hidden" id="is_system_access" name="is_system_access" value="<?php echo $is_system_access; ?>">
                <input type="checkbox" id="is_system_access_checkbox"
                       onclick="document.getElementById('is_system_access').value=(this.checked?1:0);"
                  <?php echo $is_system_access?'checked="checked"':''; ?>>
                &nbsp;
                <label class="label-for-checkbox" for="is_system_access_checkbox">Да/нет</label></td>
        </tr>
        <tr>
            <td >Оператор call-центра:</td>
            <td><input type="hidden" id="is_call_centre_operator" name="is_call_centre_operator" value="<?php echo $is_call_centre_operator; ?>">
                <input type="checkbox" id="is_call_centre_operator_checkbox"
                       onclick="document.getElementById('is_call_centre_operator').value=(this.checked?1:0);"
                  <?php echo $is_call_centre_operator?'checked="checked"':''; ?>>
                &nbsp;
                <label class="label-for-checkbox" for="is_call_centre_operator_checkbox">Да/нет</label></td>
        </tr>
        <tr>
            <td >Администратор лекарств:</td>
            <td><input type="hidden" id="is_product_admin" name="is_product_admin" value="<?php echo $is_product_admin; ?>">
                <input type="checkbox" id="is_product_admin_checkbox"
                       onclick="document.getElementById('is_product_admin').value=(this.checked?1:0);"
                  <?php echo $is_product_admin?'checked="checked"':''; ?>>
                &nbsp;
                <label class="label-for-checkbox" for="is_product_admin_checkbox">Да/нет</label></td>
        </tr>

        <tr>
            <td colspan="2"> <input type="submit" name="save" value="Сохранить" /></td>
        </tr>
    </table>
</div>

<div><a href="/manage/account">К списку</a></div>





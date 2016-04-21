<?php
    /**
     * @var string $login
     * @var int $role_id
     * @var ClinicModel[] $clinics
     * @var int $clinic_id
     * @var int $city_id
     * @var UserModel $user
     * @var CityModel[] $cities
     */
?>
<div class="users-filter">
    <div>
        <form action="/manage/account" method="GET">
            Фамилия: <input type="text" name="last_name" value="<?php echo $last_name; ?>" />
            Имя: <input type="text" name="first_name" value="<?php echo $first_name; ?>" />
            Отчество: <input type="text" name="middle_name" value="<?php echo $middle_name; ?>" />
            Телефон: <input type="text" name="phone" value="<?php echo $phone_number; ?>" />
            Email: <input type="text" name="email" value="<?php echo $email; ?>" />

            <input type="submit" value="Применить" />
        </form>
    </div>
</div>

<input class="btn-appoint block-button" type="submit" value="Добавить" onclick="window.location='/manage/account/create';" />

<?php
if ($accounts) {
  $counter = 1;?>
  <table class="styled-table block users-list">
    <thead>
      <th>ФИО</th>
      <th>Имя на сайте</th>
      <th>Телефон</th>
      <th>Email</th>
      <th></th>
    </thead>
    <?php foreach($accounts as $account) { ?>
      <tr>
        <td><?php echo $account->first_name.' '.$account->middle_name.' '.$account->last_name; ?></td>
        <td><?php echo $account->nick; ?></td>
        <td><?php echo $account->email; ?></td>
        <td><?php echo $account->phone; ?></td>
        <td>
          <a href="/manage/account/edit?id=<?php echo $account->getId(); ?>">редактировать</a>
        </td>
      </tr>
        <?php $counter++;?>
        <?php }; ?>
    </table>

  <div class="pager"><?php
    $i = 1;
    $pager = [];
    while(($i-1)*$page_size<$records_count) {
      if($i==$page_nm){
        $pager[] = '<span>'.$i.'</span>';
      }else {
        $pager[] = '<a href="/manage/account?' . $search_line . '&page=' . $i . '">' . $i . '</a>';
      }
      $i++;
    }
    if(count($pager)>1) {
      echo implode(' ', $pager);
    }
  ?></div>



<?php } else { ?>
    по данным параметрам ничего не найдено
<?php } ?>
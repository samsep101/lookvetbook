<?if ($relations_moderate):?>

    <div class="request-block">
        <?$query_counter = 1;?>
        <?foreach ($relations_moderate as $relation) {
            $phone = ModelManagerFactory::getByName('account_phone')->getConfirmedOneByAccountId($relation->account_id);
            //if ($relation->phone_confirm) $phone = $relation->phone;
            //else $phone = '';
            ?>

            <div class="request-cont flo">
                <?if ($query_counter == 1):?><h3>Запрос</h3><?endif?>
                <?$query_counter++;?>
                <div class="descr">
                    <p>Данный пользователь утверждает, что вы родственники</p>
                    <div class="item flo">
                        <div class="name"><?php echo $relation->last_name?> <?=$relation->first_name?> <?=$relation->middle_name?>
                            <?if ($phone):?>
                                <span class="phone">+<?=$phone->phone?></span>
                            <?endif?>
                        </div>
                        <div class="family-status"><?=FamilyRelationViewHelper::getReversedRelation($relation->family_relation_status->name, $relation->account_id)?></div>
                    </div>
                </div>
                <div class="btns"> <span class="btn-4">
                    <input type="submit" class="confirm-relation" data-id="<?=$relation->relation_id?>" value="Принять">
                    </span> <span class="btn-5">
                    <input type="submit" class="delete-relation-moderate" data-id="<?=$relation->relation_id?>" value="Отклонить">
                    </span> </div>
            </div>

        <?}?>

    </div>
<?endif?>
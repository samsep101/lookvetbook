<?if ($relations || $reversed_relations):?>

    <div class="in-club">
        <h2>Семья в клубе</h2>
        <div class="in-club-block">
            <?if ($relations) {?>
                <?foreach ($relations as $relation) {?>
                <?$phone = ModelManagerFactory::getByName('account_phone')->getConfirmedOneByAccountId($relation->account2_id);?>

                <div class="item flo">
                    <div class="name"><?=$relation->last_name?> <?=$relation->first_name?> <?=$relation->middle_name?>
                        <?if ($phone):?>
                            <span class="phone">+<?=$phone->phone?></span>
                        <?endif?>
                    </div>
                    <div class="family-status"><?=FamilyRelationViewHelper::getReversedRelation($relation->family_relation_status->name, $relation->account2_id)?></div>
                    <span class="btn-5">
                        <input type="submit" class="delete-relation" data-id="<?=$relation->relation_id?>" value="Удалить">
                    </span>
                </div>

                <?}?>
            <?}?>

            <?if ($reversed_relations) {?>
            <?foreach ($reversed_relations as $relation) {?>
                <?$phone = ModelManagerFactory::getByName('account_phone')->getConfirmedOneByAccountId($relation->account1_id);?>

                <div class="item flo">
                    <div class="name"><?=$relation->last_name?> <?=$relation->first_name?> <?=$relation->middle_name?>
                        <?if ($phone):?>
                            <span class="phone">+<?=$phone->phone?></span>
                            <?endif?>
                    </div>
                    <div class="family-status"><?=$relation->family_relation_status->name?></div>
                    <span class="btn-5">
                        <input type="submit" class="delete-relation" data-id="<?=$relation->relation_id?>" value="Удалить">
                    </span>
                </div>

                <?}?>
            <?}?>
        </div>
    </div>

<?endif?>
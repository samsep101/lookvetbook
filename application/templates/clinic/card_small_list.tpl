<?php if ($clinics): ?>
    <?$card_counter = 1;?>
    <?php foreach($clinics as $clinic): ?>
        <?if ($card_counter % 2 != 0):?>
            <div class="item-row flo">
        <?endif?>

        <?if ($card_counter % 2 != 0):?>
            <div class="info-card clinic-card flo">
                <?php $this->clinic = $clinic; ?>
                <?php $this->is_closed_card = (isset($is_close_card) && $is_close_card == 1) ? 1 : null; ?>
                <?php $this->block('clinic/card_small'); ?>
            </div>
        <? else:?>
            <div class="info-card clinic-card fright flo">
                <?php $this->clinic = $clinic; ?>
                <?php $this->block('clinic/card_small'); ?>
            </div>
        <?endif?>

        <?if (($card_counter == count($clinics)) || ($card_counter % 2 == 0)):?>
            </div>
        <?endif?>
        <?$card_counter++;?>
    <?endforeach?>
<?php endif; ?>


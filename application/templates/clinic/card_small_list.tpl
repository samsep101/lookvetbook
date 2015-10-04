<?php if ($clinics): ?>
    <?php $card_counter = 1;?>
    <?php foreach($clinics as $clinic): ?>
        <?php if ($card_counter % 2 != 0):?>
            <div class="item-row flo">
        <?php endif?>

        <?php if ($card_counter % 2 != 0):?>
            <div class="info-card clinic-card flo">
                <?php $this->clinic = $clinic; ?>
                <?php $this->is_closed_card = (isset($is_close_card) && $is_close_card == 1) ? 1 : null; ?>
                <?php $this->block('clinic/card_small'); ?>
            </div>
        <?php  else:?>
            <div class="info-card clinic-card fright flo">
                <?php $this->clinic = $clinic; ?>
                <?php $this->block('clinic/card_small'); ?>
            </div>
        <?php endif?>

        <?php if (($card_counter == count($clinics)) || ($card_counter % 2 == 0)):?>
            </div>
        <?php endif?>
        <?php $card_counter++;?>
    <?php endforeach?>
<?php endif; ?>


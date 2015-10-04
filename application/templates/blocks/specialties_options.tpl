<?php
	/**
	 * @var View $this
	 * @var SpecialtyModel[] $specialties
	 */
?>
<?php $current_setialty = $specialty; ?>
<?php if (!isset($show_all_option) || ($show_all_option)): ?>
    <option <?php if($page_type != 'doctor' && !isset($current_setialty)) echo 'selected="selected"'; ?> value="0" data-specialty_plural_name="Все">Все</option>
<?php endif;?>
<?php if (count($specialties)):?>
    <?php foreach($specialties as $specialty): ?>
        <option <?php if((isset($current_setialty) && $current_setialty->id == $specialty->getId())
                         || (!isset($current_setialty) && $page_type == 'doctor' && $specialty->getId() == 29)) echo 'selected="selected"'; ?>
            value="<?php echo $specialty->getId(); ?>" <?php if ($specialty->gparent == 1) {?>style="font-weight: bold;"<?php }?>
            data-specialty_alias="<?php echo $specialty->alias; ?>"
            data-specialty_name="<?php echo $specialty->name; ?>"
            data-specialty_plural_name="<?php echo $specialty->plural_name; ?>">

            <?php echo StringHelper::startProposalWord($specialty->name); ?>

        </option>
    <?php endforeach; ?>
<?php endif; ?>
<?php
	/**
	 * @var View $this
	 * @var SpecialtyModel[] $specialties
	 */
?>
<?php $current_setialty = $specialty; ?>
<?php if (!isset($this->show_all_option) || ($this->$show_all_option)): ?>
    <option <?php 
    if(
        (empty($page_type) 
      //#1739or $page_type != 'doctor'
      ) 
    && !isset($current_setialty)
    ) echo 'selected="selected"'; ?> value="0" data-specialty_plural_name="Все">Все</option>
<?php endif;?>
<?php if (count($specialties)):?>
    <?php foreach($specialties as $specialty): ?>
        <option <?php if( (isset($current_setialty) && $current_setialty->id == $specialty->getId())
             //#1739or (!isset($current_setialty) && (!empty($page_type) and $page_type == 'doctor') && $specialty->getId() == 29)
             ) {
          echo 'selected="selected"'; 
        } ?>
            value="<?php echo $specialty->getId(); ?>" <?php if ($specialty->gparent == 1) {?>style="font-weight: bold;"<?php }?>
            data-specialty_alias="<?php echo $specialty->alias; ?>"
            data-specialty_name="<?php echo $specialty->name; ?>"
            data-specialty_plural_name="<?php echo $specialty->plural_name; ?>">

            <?php echo StringHelper::startProposalWord($specialty->name); ?>

        </option>
    <?php endforeach; ?>
<?php endif; ?>
<?php
	/**
	 * @var CityModel | DistrictModel | RegionModel | MetroStationModel $address_object
	 * @var View $this
	 * @var SpecialtyModel $specialty
	 * @var SpecialtyModel[] $specialties
	 */
?>
<?php if (isset($is_seo_page) && $is_seo_page): ?>
<?php
        if(!empty($specialty) && !empty($specialties_groups)):
            $cache_id = 'seo'.get_class($address_object).$address_object->getId().'specialty'.$specialty->getId();


            if (!$cache->start($cache_id,  'seo_block')):
    ?>
                <div class="special-block inner-2">
                    <h2><?php echo SeoTextViewHelper::getH1($specialty, $address_object); ?></h2>
                    <?php $seo_text = SeoTextViewHelper::getTextBySpecialtyIdAndAddressObject($specialty->getId(), $address_object); ?>
                    <?php if ($seo_text): ?>
                    <div class="special-block">
                        <div class="text">
                            <?php echo $seo_text; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="specialties-groups not-main-specialties">
                    <h2>Другие специальности <?php echo SeoTextViewHelper::getAddressObjectName($address_object); ?>: </h2>

                    <?php echo $this->block('blocks/specialties-groups-content');?>

                </div>
                <div class="clearfix"></div>
    <?php
            endif;
        endif;
    ?>

<?php endif; ?>

<?php if(!empty($landing_page)) {


            $addressDataArray = SeoClinicBlockViewHelper::getViewByAddressObjectInArray($current_item, $address_object);
            if(isset($addressDataArray) && count($addressDataArray)) $adaThere = 1;
            else $adaThere = 0;
?>
    <div class="special-block inner-2">
        <?php if(!empty($current_item) && $current_item->title) { ?>
            <h1><?php echo $current_item->title; ?> <?php echo SeoTextViewHelper::getAddressObjectName($address_object); ?></h1>
        <?php } else { ?>
            <h1>Услуги и типы клиник <?php echo SeoTextViewHelper::getAddressObjectName($address_object); ?></h1>
        <?php  } ?>

        <?php if(empty($search_page_description)) { ?>
            <?php if($current_item->description) { ?>
                <div class="seo-specialty-description">
                    <?php echo $current_item->description; ?>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="seo-geo-description">
                <?php echo $search_page_description; ?>
            </div>
        <?php } ?>
    </div>

    <?php if($adaThere && $addressDataArray['districtsBlock']) { ?>
        <div class="special-block links-block inner-2 specialties-block-modernize districtsBlock">
            <?php echo $addressDataArray['districtsBlock']; ?>
        </div>
    <?php } ?>

    <div class="clinic-list-link">
        <h2>Другие услуги и типы клиник <?php echo SeoTextViewHelper::getAddressObjectName($address_object); ?>: </h2>

        <?php echo $this->block('blocks/services-groups-content');?>

    </div>



    <?php if($adaThere && count($addressDataArray['otherAddressData'])) { ?>
        <div class="special-block links-block inner-2 specialties-block-modernize otherAddressData">
            <?php
                foreach($addressDataArray['otherAddressData'] AS $oadKey => $oadValue)
                {
                    echo $oadValue;
                }
            ?>
        </div>
    <?php } ?>
    <div class="clearfix"></div>
<?php } ?>
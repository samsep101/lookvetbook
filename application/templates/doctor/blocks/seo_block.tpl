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
        if($specialty && $specialties_groups):
            $cache_id = 'seo'.get_class($address_object).$address_object->getId().'specialty'.$specialty->getId();

            if (!$cache->start($cache_id,  'seo_block')):
                $addressDataArray = SeoSpecialtyBlockViewHelper::getViewByAddressObjectInArray($specialty, $address_object);
                if(isset($addressDataArray) && count($addressDataArray)) $adaThere = 1;
                else $adaThere = 0;
?>
                <div class="special-block inner-2">
                    <?php if(!$setDefaultSpecialty) { ?>
                        <h2><?php echo SeoTextViewHelper::getH1($specialty, $address_object); ?></h2>

                        <?php if(empty($search_page_description)) { ?>
                            <?php if($specialty->specialty_page_descr) { ?>
                                <div class="seo-specialty-description">
                                    <?php echo $specialty->specialty_page_descr; ?>
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <div class="seo-geo-description">
                                <?php echo $search_page_description; ?>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="h1">Врачи <?php echo SeoTextViewHelper::getAddressObjectName($address_object); ?></div>
                    <?php } ?>

                </div>

                <?php if($adaThere && $addressDataArray['districtsBlock']) { ?>
                    <div class="special-block links-block inner-2 specialties-block-modernize districtsBlock geoBlocks">
                        <?php echo $addressDataArray['districtsBlock']; ?>
                    </div>
                <?php } ?>

                <div class="specialties-groups not-main-specialties<?php if(!empty($page_type)) echo ' doctor-specialties'; ?>">
                    <h2>Популярные специальности <?php echo SeoTextViewHelper::getAddressObjectName($address_object); ?>: </h2>

                    <?php echo $this->block('blocks/specialties-groups-content');?>

                </div>

                <?php if($adaThere && count($addressDataArray['otherAddressData'])) { ?>
                    <div class="special-block links-block inner-2 specialties-block-modernize otherAddressData geoBlocks">
                        <?php
                            foreach($addressDataArray['otherAddressData'] AS $oadKey => $oadValue)
                            {
                                echo $oadValue;
                            }
                        ?>
                    </div>
                <?php } ?>
                <div class="clearfix"></div>
    <?php
            endif;
        endif;
    ?>

<?php endif; ?>
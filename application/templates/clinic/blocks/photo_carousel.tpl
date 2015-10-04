<?php
	/**
	 * @var View $this
	 * @var ClinicModel $clinic
	 */
?>
<div class="connected-carousels">
    <div class="stage"> <span class="map-corn"></span>
        <div class="carousel carousel-stage clinic-carousel">
            <ul>
                <?php if ($clinic->images): ?>
                    <?php foreach($clinic->images as $image): ?>
                        <li>
                            <img src="<?php echo $image->cropWithWatermark(660, 360)->path; ?>" alt="<?php echo $clinic->name; ?>">
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <img src="/media/images/no-photo2.gif" alt="<?php echo $clinic->name; ?>">
                <?php endif; ?>
            </ul>
            <a href="javascript:void(0);" class="prev prev-stage"><span></span></a>
            <a href="javascript:void(0);" class="next next-stage"><span></span></a>
        </div>
    </div>
</div>
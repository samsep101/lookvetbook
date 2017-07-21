<?php

    function adopt($text) {
        return '=?UTF-8?B?'.base64_encode($text).'?=';
    }

    $mail = [
        'action' => '/?action=send',
        //'to' => 'cashbacklmb@gmail.com',
        'to' => 'playmoredevelop@gmail.com',
        'from' => adopt('Lookmedbook.ru').'<noreply@lookmedbook.ru>'
    ];

    if(!empty($_GET['action']) AND $_GET['action'] == 'send'){

        $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $vars = [
            '{name}' => (!empty($post['name'])) ? $post['name'] : false,
            '{phone}' => (!empty($post['phone'])) ? $post['phone'] : false,
            '{date}' => date('d.m.Y H:i'),
        ];

        $valid_phone = preg_match('#^[\d\+\-\(\)\s]+$#', $phone);

        if($vars['{name}'] AND $vars['{phone}'] AND $valid_phone){
            
            $body = str_replace(array_keys($vars), array_values($vars), '
                <h3>Заявка с сайта cashback.lookmedbook.ru</h3>
                <hr>
                <p style="font-size:14px">Дата: {date}</p>
                <ul style="font-size:16px">
                    <li>Имя отправителя: {name}</li>
                    <li>Телефон отправителя: {phone}</li>
                </ul>
                ');
            $headers = implode(PHP_EOL, [
                'MIME-Version: 1.0',
                'Content-Type: text/html; charset=utf-8',
                'From: '.$mail['from'],
            ]);
            mail($mail['to'], 'Cashback заявка с LookMedBook '.$vars['{date}'], $body, $headers);
        }
    }
?>
<!DOCTYPE html>
<html class="no-js" lang="ru">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Возврат средств за лечение в клиниках Москвы</title>
        <meta content="" name="description">
        <meta content="" name="keywords">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="telephone=no" name="format-detection">
        <meta name="HandheldFriendly" content="true">

        <link href="<?=SUBDOMAIN_MEDIA?>/css/maintw8gbs.min.css" rel="stylesheet" type="text/css">
        <link href="<?=SUBDOMAIN_MEDIA?>/css/extra.css" rel="stylesheet" type="text/css">

        <meta property="og:title" content="Lookmedbook. Медицинский Cashback">
        <meta property="og:title" content="">
        <meta property="og:url" content="">
        <meta property="og:description" content="">
        <meta property="og:image" content="">
        <meta property="og:image:type" content="image/jpeg">
        <meta property="og:image:width" content="500">
        <meta property="og:image:height" content="300">
        <meta property="twitter:description" content="">
        <link rel="icon" type="image/x-icon" href="favicon.ico">
        <script>
            (function(H){H.className=H.className.replace(/\bno-js\b/,'js')})(document.documentElement)
        </script>
    </head>

    <body class="page">
        <section class="page__wrapper">
            <main class="page__content">
                <div class="header">
                    <div class="container">
                        <div class="header__inner"><!--a class="btn" href="#">Регион</a><a class="btn" href="#">Cashback</a-->
                            <div class="logo">
                                <img src="<?=SUBDOMAIN_MEDIA?>/img/general/logo.png" alt="LookMedBook" width="140" height="220">
                            </div>
                            <div class="phone"><span>тел. горячей линии</span><a href="tel:8(800)123-45-67"><b>8(800)123-45-67</b></a>
                            </div><!--a class="btn btn_white" href="#">Личный кабинет</a><a class="btn" href="#">Вход</a-->
                        </div>
                    </div>
                </div>
                <section class="section">
                    <div class="intro">
                        <div class="container">
                            <div class="intro__inner">
                                <div class="intro__title">Вернем до 30%
                                    <br>от суммы лечения
                                    <div class="line">
                                        <img src="<?=SUBDOMAIN_MEDIA?>/img/general/line.png" width="325">
                                    </div>
                                </div>
                                <div class="intro__form">
                                    <form class="form" method="POST" action="<?=$mail['action']?>">
                                        <input class="input" type="text" placeholder="Имя" name="name" required="true">
                                        <input class="input" type="text" placeholder="Телефон" name="phone" required="true">
                                        <button class="btn"><span>Оставить заявку</span></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
				<section class="section">
                    <div class="steps">
                        <div class="container">
                            <div class="steps__inner">
                                <div class="title">Это очень просто!</div>
                                <ul class="steps__list">
                                    <li class="steps__item step">
                                        <div class="step__header">
                                            <div class="step__num">1.</div>
                                            <div class="step__img">
                                                <img src="<?=SUBDOMAIN_MEDIA?>/img/general/step1.svg" width="143" height="118">
                                            </div>
                                        </div>
                                        <div class="step__desc">Выбираете медицинское учреждение и услуги</div>
                                    </li>
                                    <li class="steps__item step">
                                        <div class="step__header">
                                            <div class="step__num">2.</div>
                                            <div class="step__img">
                                                <img src="<?=SUBDOMAIN_MEDIA?>/img/general/step4.svg" width="162" height="151">
                                            </div>
                                        </div>
                                        <div class="step__desc">Проходите лечение</div>
                                    </li>
                                    <li class="steps__item step">
                                        <div class="step__header">
                                            <div class="step__num">3.</div>
                                            <div class="step__img">
                                                <img src="<?=SUBDOMAIN_MEDIA?>/img/general/step2.svg" width="235" height="105">
                                            </div>
                                        </div>
                                        <div class="step__desc">Возвращаете деньги</div>
                                    </li>
                                    <!--li.steps__item.step-->
                                    <!--    .step__header-->
                                    <!--        .step__num 4.-->
                                    <!--        .step__img-->
                                    <!--            img(src='static/img/general/step4.svg' width='195' height='182')-->
                                    <!--    .step__desc-->
                                    <!--        | Получение медицинских услуг-->
                                    <!--li.steps__item.step-->
                                    <!--    .step__header-->
                                    <!--        .step__num 5.-->
                                    <!--        .step__img-->
                                    <!--            img(src='static/img/general/step5.svg' width='154' height='150')-->
                                    <!--    .step__desc-->
                                    <!--        | Возврат средств за лечение в течение 30 календарных дней-->
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>
                <!--section class="section">
                    <div class="how-work">
                        <div class="container">
                            <div class="how-work__inner">
                                <div class="title">Как работает возврат средств от LoocMedBook?</div>
                                <p>
                                    Вы прозодите лечение в медицинских учреждения внесенных в партнерскую программу Возврата средств от LookMedBook. Затем в течение 30 календарных дней Вам на карту или рассчетный счет возвращается денежная сумма равная процентному соотнешнию Вашего общего
                                    чека в медицинском учреждении. Сумму возврата вы можете увидеть на странице клиник под надписью «Cashback = ***».
                                </p>
                            </div>
                        </div>
                    </div>
                </section-->
				<section class="section">
                    <div class="partners">
                        <div class="container">
                            <div class="partners__inner">
                                <div class="title">Клиники-партнеры</div>
                                <ul class="partners__list">
                                    <li class="partners__item partner">
                                        <div class="partner__img">
                                            <img src="<?=SUBDOMAIN_MEDIA?>/img/general/1.jpg">
                                        </div>
                                        <div class="partner__name">Юсуповская больница</div>
                                    </li>
                                    <li class="partners__item partner">
                                        <div class="partner__img">
                                            <img src="<?=SUBDOMAIN_MEDIA?>/img/general/2.png">
                                        </div>
                                        <div class="partner__name">Европейская клиника</div>
                                    </li>
                                    <li class="partners__item partner">
                                        <div class="partner__img">
                                            <img src="<?=SUBDOMAIN_MEDIA?>/img/general/3.jpg">
                                        </div>
                                        <div class="partner__name">Семейная клиника</div>
                                    </li>
                                    <li class="partners__item partner">
                                        <div class="partner__img">
                                            <img src="<?=SUBDOMAIN_MEDIA?>/img/general/4.jpg">
                                        </div>
                                        <div class="partner__name">Клиника "Медси"</div>
                                    </li>
                                    <li class="partners__item partner">
                                        <div class="partner__img">
                                            <img src="<?=SUBDOMAIN_MEDIA?>/img/general/5.jpg">
                                        </div>
                                        <div class="partner__name">Клиника К+31</div>
                                    </li>
                                    <li class="partners__item partner">
                                        <div class="partner__img">
                                            <img src="<?=SUBDOMAIN_MEDIA?>/img/general/6.png">
                                        </div>
                                        <div class="partner__name">ОАО "Медицина"</div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>
				<section class="section">
                    <div class="cashback">
                        <div class="container">
                            <div class="cashback__inner">
                                <div class="title">Мы возвращаем деньги за лечение</div>
                                <div class="cashback__top">
                                    <img src="<?=SUBDOMAIN_MEDIA?>/img/general/cashback.svg" width="384">
                                    <div class="cashback__top-desc">Уже<b>27 342</b>пациента вернули часть средств за лечение
                                    </div>
                                    <div class="owl">
                                        <img src="<?=SUBDOMAIN_MEDIA?>/img/general/owl.png" width="420">
                                    </div>
                                </div>
                                <ul class="cashback__list">
                                    <li class="cashback__item"><b>14 122</b>использовали вернувшиеся средства за лечение повторно
                                    </li>
                                    <li class="cashback__item"><b>1587</b>клиник работают по системе возврата средств LookMedBook
                                    </li>
                                    <li class="cashback__item"><b>18 943</b>пациента сказали «Спасибо»
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="section">
                    <div class="faq">
                        <div class="faq__header">
                            <div class="title">Ответы на частые вопросы</div>
                        </div>
                        <ul class="faq__list">
						<li class="faq__item">
                                <div class="faq__title">
                                    <div class="container">
                                        <div class="faq__title-inner">Как работает возврат средств от LookMedBook?</div>
                                    </div>
                                </div>
                                <div class="faq__desc">
                                    <div class="container">Вы записываетесь на консультацию, диагностику или лечение в медицинских учреждениях, внесенных в партнерскую программу "Возврат средств" от LookMedBook. Вам оказываются медицинские услуги. Затем в течение 30 календарных дней Вам на карту или рассчетный счет возвращается денежная сумма равная процентному соотношению Вашего общего
                                    чека в медицинском учреждении.</div>
                                </div>
                            </li>
                            <li class="faq__item">
                                <div class="faq__title">
                                    <div class="container">
                                        <div class="faq__title-inner">Как получить возврат средств?</div>
                                    </div>
                                </div>
                                <div class="faq__desc">
                                    <div class="container">Сначала вы записываетесь на консультацию, диагностику или лечение на сайте Medical-cashback.ru. Затем наш оператор подтверждает запись и способ возврата средств. После этого вам оказываются медицинские услуги, и в течение 30 календарных дней, после оказания медицинских услуг, Вам возвращается сумма денежных средств удобным для Вас способом.</div>
                                </div>
                            </li>
                            <li class="faq__item">
                                <div class="faq__title">
                                    <div class="container">
                                        <div class="faq__title-inner">В каком размере я получу возврат средств?</div>
                                    </div>
                                </div>
                                <div class="faq__desc">
                                    <div class="container">В зависимости от партнерского учреждения сумма возврата может варьироваться от 3% до 20% от общего чека в клинике за отчетный период. Отчетным периодом являются 30 календарных дней</div>
                                </div>
                            </li>
                            <li class="faq__item">
                                <div class="faq__title">
                                    <div class="container">
                                        <div class="faq__title-inner">На какие услуги клиник распространяется возврат средств?</div>
                                    </div>
                                </div>
                                <div class="faq__desc">
                                    <div class="container">Возврат средств распространяется на все услуги клиники от консультации и диагностики до госпитализации</div>
                                </div>
                            </li>
                        </ul>
                        <div class="faq__footer"></div>
                    </div>
                </section>
                <section class="section">
                    <div class="form-bottom">
                        <div class="container">
                            <div class="form-bottom__inner">
                                <div class="title">Начни возвращать деньги прямо сейчас</div>
                                <div class="form-bottom__form">
                                    <form class="form" method="POST" action="<?=$mail['action']?>">
                                        <input class="input" type="text" placeholder="Имя" name="name" required="true">
                                        <input class="input" type="text" placeholder="Телефон" name="phone" required="true">
                                        <button class="btn"><span>Оставить заявку</span></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>


            </main>
        </section>
        <div class="page__footer">
            <footer class="footer">
                <div class="container">
                    <div class="footer__inner">
                        <!--div class="footer__col">
                            <div class="footer__col-title">О портале:</div>
                            <!--ul class="footer__menu">
                                <li><a href="#">О нас</a>
                                </li>
                                <li><a href="#">Контакты</a>
                                </li>
                                <li><a href="#">Команда</a>
                                </li>
                                <li><a href="#">Карта сайта</a>
                                </li>
                                <li><a href="#">Партнерское соглашение</a>
                                </li>
                            </ul>
                        </div>
                        <div class="footer__col">
                            <div class="footer__col-title">Пациенту:</div>
                            <ul class="footer__menu">
                                <li><a href="#">Заболевание</a>
                                </li>
                                <li><a href="#">Врачи</a>
                                </li>
                                <li><a href="#">Клиники</a>
                                </li>
                                <li><a href="#">Лекарства</a>
                                </li>
                                <li><a href="#">Cashback</a>
                                </li>
                            </ul>
                        </div>
                        <div class="footer__col">
                            <div class="footer__col-title">Врачу и клинике:</div>
                            <ul class="footer__menu">
                                <li><a href="#">личный кабинет</a>
                                </li>
                                <li><a href="#">регистрация</a>
                                </li>
                            </ul>
                        </div-->
                        <div class="footer__col">
                            <div class="footer__contact"><span>телефон горячей линии</span><a href="tel:8(800)123-45-67">8(800)123-45-67</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <div class="modal" id="modal">
                <div class="close" id="modalClose">x</div>
                <form class="form" method="POST" action="<?=$mail['action']?>">
                    <input class="input" type="text" placeholder="Имя" name="name" required="true">
                    <input class="input" type="text" placeholder="Телефон" name="phone" required="true">
                    <button class="btn"><span>Записаться</span></button>
                </form>
            </div>
            <div class="overlay" id="overlay"></div>
        </div>

        <div class="messages">
            <div class="success">Ваша заявка принята. Мы свяжемся с Вами в ближайшее время!</div>
        </div>

        <script src="<?=SUBDOMAIN_MEDIA?>/js/maintw8gbs.min.js"></script>
        <script type="text/javascript" src="/media/js/jquery-1.8.3.min.js?0.1"></script>
        <script>
            $(function(){
                
                var $message = $('.messages');

                $(document).on('submit', 'form.form', function(e){
                    e.preventDefault();
                    var _ = $(this);
                    $.post(_.attr('action'), _.serialize(), function(response){
                        $message.addClass('visible').fadeIn(200);
                        setTimeout(function(){
                            if($message.hasClass('visible')){
                                $message.removeClass('visible');
                                $message.stop(1,1).fadeOut(1000);
                            }
                        }, 5000);
                        $('.form .btn').prop('disabled', true);
                    });
                }).on('click', '.form .btn', function(e){
                    e.preventDefault();
                    $(this).closest('form').submit();
                }).on('click', '.messages', function(){
                    $message.removeClass('visible');
                    $message.stop(1,1).fadeOut(200);
                });
            });
        </script>
    </body>

</html>
<?php
    if (!isset($is_small_card))
        $is_small_card = false;
    if (!isset($doctor_page))
        $doctor_page = false;
?>


<div class="btns flo">
    <?php if (isset($example_page)) { ?>
        <a href="javascript:void(0)" class="btn-appoint">Записаться</a>
        <a href="javascript:void(0)" class="btn-bookmarkt btn-bookmark doctor_bookmark doctor_bookmark<?php echo $doctor->getId(); ?>"><i class="icon-add"></i><span class="txt">Добавить в закладки</span></a>
    <?php } else if(!empty($single_doctor_page)) { ?>
        <a onclick=" if (window.is_test == 1) $(this).attr('href','javascript:void(0)');
                    else {
                    //if (SessionInfo.is_authed)
                    //  {

                        send('//ad.adriver.ru/cgi-bin/rle.cgi?sid=194132&sz=zapis&bt=55&pz=0&rnd=![rnd]')
                        var block = new RecordToTheDoctorBlockController(<?php echo $doctor->getId(); ?>, $(this), null);
                        block.action_for_counters = 'button';
                        block.init();
                    }
                        /*
                    } else {
                        var block = new LandingRegistrationPageController();
                        block.block_title = 'для записи к врачу';
                        block.block_over_textbox = 'Введите почту и продолжайте запись!';
                        block.action_for_counters = 'booking-reg';
                        block.success_registration_callback = function(data){
                            var block = new RecordToTheDoctorBlockController(<?php echo $doctor->getId(); ?>, $(this), null);
                            block.action_for_counters = 'button';
                            $('.header #authorization-block-on-disease-page').html('<div class=\'header-user\' style=\'margin: 2px 44px 0 24px;\'><a href=\'/account/message\' class=\'header-usernotification\'> <span style=\'display: none;\' class=\'notification\' id=\'usernotification\'></span> </a><div class=\'header-userinfo\'><a href=\'/account/about\' class=\'header-userprofile\'>'+ SessionInfo.email +'</a><ul class=\'header-usermenu\'><li><a href=\'/account/about\'>Профиль</a></li><li><a href=\'/help\'>Помощь</a></li><li><a href=\'/account/logout\'>Выйти</a></li></ul></div></div>');
                            block.init();
                        };
                        block.init()
                    }*/
                    " href="#record-to-the-doctor-popup-<?php echo $doctor->getId(); ?>" class="btn-appoint refactor-btn-appoint-styles
                        <?php echo $is_small_card ? 'btn-appoint-sm' : '' ; ?>
                    ">
            <span class="button-left-part"></span>
            <span class="button-right-part"></span>
            <span class="button-middle-part"></span>
            <span class="button-name">
                Записаться на прием сейчас
            </span>
        </a>
    <?php } else { ?>
        <a class="btn-appoint" href="#record-to-the-doctor-popup-<?php echo $doctor->getId(); ?>" onclick=" if (window.is_test == 1) $(this).attr('href','javascript:void(0)');
            else {
            //if (SessionInfo.is_authed)
            //  {

                send('//ad.adriver.ru/cgi-bin/rle.cgi?sid=194132&amp;sz=zapis&amp;bt=55&amp;pz=0&amp;rnd=![rnd]')
                var block = new RecordToTheDoctorBlockController(<?php echo $doctor->getId(); ?>, $(this), null);
                block.action_for_counters = 'button';
                block.init();
            }
                /*
            } else {
                var block = new LandingRegistrationPageController();
                block.block_title = 'для записи к врачу';
                block.block_over_textbox = 'Введите почту и продолжайте запись!';
                block.action_for_counters = 'booking-reg';
                block.success_registration_callback = function(data){
                    var block = new RecordToTheDoctorBlockController(940, $(this), null);
                    block.action_for_counters = 'button';
                    $('.header #authorization-block-on-disease-page').html('&lt;div class=\'header-user\' style=\'margin: 2px 44px 0 24px;\'&gt;&lt;a href=\'/account/message\' class=\'header-usernotification\'&gt; &lt;span style=\'display: none;\' class=\'notification\' id=\'usernotification\'&gt;&lt;/span&gt; &lt;/a&gt;&lt;div class=\'header-userinfo\'&gt;&lt;a href=\'/account/about\' class=\'header-userprofile\'&gt;'+ SessionInfo.email +'&lt;/a&gt;&lt;ul class=\'header-usermenu\'&gt;&lt;li&gt;&lt;a href=\'/account/about\'&gt;Профиль&lt;/a&gt;&lt;/li&gt;&lt;li&gt;&lt;a href=\'/help\'&gt;Помощь&lt;/a&gt;&lt;/li&gt;&lt;li&gt;&lt;a href=\'/account/logout\'&gt;Выйти&lt;/a&gt;&lt;/li&gt;&lt;/ul&gt;&lt;/div&gt;&lt;/div&gt;');
                    block.init();
                };
                block.init()
            }*/
            ">Записаться</a>
    <?php } ?>
</div>
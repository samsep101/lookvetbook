<script type="text/javascript">
    $(document).ready(function () {
        var controller = new FooterBlockController();
        controller.init();
    });
</script>
<noindex>
    <footer class="footer">
        <div class="inner">
            <div class="footer-inner-top">
                <section class="inner-top-info"> Нужна помощь?
                    <span class="help-phone"><?php echo SettingsManager::get('help_phone'); ?></span> или
                    <a data-link="mailto:<?php echo SettingsManager::get('help_email');?>" class="info-mail jsLinkHidingIndexing"><?php echo SettingsManager::get('help_email');?></a>
                    <?php if ($_SERVER['REQUEST_URI'] == '/'): ?>
                        Адрес <span style="color: white">ул. Вятская, 27 </span>
                    <?php endif; ?>
                </section>
                <ul class="inner-top-socials">
                    <li class="socials-vkontakte"><a class="jsLinkHidingIndexing" data-link="http://vk.com/lookmedbook" target="_blank"></a></li>
                    <li class="socials-odnoklassniki"><a class="jsLinkHidingIndexing" data-link="http://odnoklassniki.ru/group/52035885072448" target="_blank"></a></li>
                    <li class="socials-facebook"><a class="jsLinkHidingIndexing" data-link="http://www.facebook.com/LookMedBook" target="_blank"></a></li>
                </ul>
            </div>
            <div class="footer-inner-bottom">
                <section class="inner-bottom-copyright">&copy; &laquo;<?php echo SITE_DOMAIN; ?>&raquo;, <?php echo date('Y'); ?></section>
                <a class="reg-link show_license" style="margin-left: 10px; font-size: 14px; color: #55BCC8; font-weight: 500;" href="javascript:void(0);">Пользовательское соглашение</a>
                <ul class="inner-bottom-navigation">
                    <li><a class="help-link jsLinkHidingIndexing" data-link="/help">Помощь</a></li>
                    <li><a class="about-link jsLinkHidingIndexing" data-link="/about">О проекте</a></li>
                </ul>
            </div>
        </div>
    </footer>
</noindex>
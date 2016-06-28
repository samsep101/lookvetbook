<?php
	/**
	 * @var View $this
	 * @var CityModel $city
	 * @var View Cache_Lite $cache
	 */
?>

<?php $cache_id = 'counters_city'.$city->getId(); ?>

	<?php if(!isset($cache) or !$cache->start($cache_id, 'counters_city')): ?>
<!--LiveInternet counter-->
<script type="text/javascript">
    document.write('<a href="http://www.liveinternet.ru/click" target=_blank><img style="display: none" src="//counter.yadro.ru/hit?t44.6;r' + escape(top.document.referrer) + ((typeof(screen) == "undefined") ? "" : ";s" + screen.width + "*" + screen.height + "*" + (screen.colorDepth ? screen.colorDepth : screen.pixelDepth)) + ";u" + escape(document.URL) + ";h" + escape(document.title.substring(0, 80)) + ";" + Math.random() + '" border=0 width=31 height=31 alt="" title="LiveInternet"><\/a>')</script><!--/LiveInternet-->

    <?php if(!defined('debug') || debug == 0): ?>
    <!-- Yandex.Metrika counter-->
    <script type="text/javascript">
    (function (d, w, c) {
            (w[c] = w[c] || []).push(function () {
                try {
                    var ya_counter_params = {email: '<?php echo (Acc::isAuthed()) ? $current_account->email : "гость"; ?>'};
    w.yaCounterLookmedbook = new Ya.Metrika({id:<?php echo (int)AnalyticCounterHelper::getCounterIdByCityIdAndCounterTypeId($city->getId(), AnalyticCounterTypeModel::YANDEX_COUNTER); ?>,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,

                    accurateTrackBounce:true,
                    params:'<?php echo (Acc::isAuthed()) ? $current_account->email : "guest"; ?>'   });

    w.yaCounter<?php echo (int)AnalyticCounterHelper::getCounterIdByCityIdAndCounterTypeId($city->getId(), AnalyticCounterTypeModel::YANDEX_COUNTER); ?> = new Ya.Metrika({id:<?php echo (int)AnalyticCounterHelper::getCounterIdByCityIdAndCounterTypeId($city->getId(), AnalyticCounterTypeModel::YANDEX_COUNTER); ?>,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,

                    accurateTrackBounce:true,
                    params:'<?php echo (Acc::isAuthed()) ? $current_account->email : "guest"; ?>'   });

    } catch (e) {
            }
    });

    var n = d.getElementsByTagName("script")[0],
    s = d.createElement("script"),
    f = function () {
            n.parentNode.insertBefore(s, n);
            };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
            } else {
            f();
            }
    })(document, window, "yandex_metrika_callbacks");
    </script>
    <noscript>
        <div><img src="//mc.yandex.ru/watch/<?php echo (int)AnalyticCounterHelper::getCounterIdByCityIdAndCounterTypeId($city->getId(), AnalyticCounterTypeModel::YANDEX_COUNTER); ?>" style="position:absolute; left:-9999px;" alt=""/></div>
    </noscript>
    <!-- /Yandex.Metrika counter -->
    <?php endif; ?>

    <?php /*<script>
    (function (i, s, o, g, r, a, m) {i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
    a = s.createElement(o),
    m = s.getElementsByTagName(o)[0];
    a.async = 1;
    a.src = g;
    m.parentNode.insertBefore(a, m)
    })
    (window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
    ga('create', '<?php echo AnalyticCounterHelper::getCounterIdByCityIdAndCounterTypeId($city->getId(), AnalyticCounterTypeModel::GOOGLE_COUNTER); ?>', 'lookmedbook.ru');

    ga('send', 'pageview');
    </script>*/?>


    <meta name='yandex-verification' content='76535cc7dd5d586f' />

    <!--  AdRiver code START. Type:counter(zeropixel) Site: lookmedb PZ: 0 BN: 0 -->
    <script type="text/javascript">
        var RndNum4NoCash = Math.round(Math.random() * 1000000000);
        var ar_Tail='unknown'; if (document.referrer) ar_Tail = escape(document.referrer);
        document.write('<img src="' + ('https:' == document.location.protocol ? 'https:' : 'http:') + '//ad.adriver.ru/cgi-bin/rle.cgi?' + 'sid=194132&bt=21&pz=0&rnd=' + RndNum4NoCash + '&tail256=' + ar_Tail + '" style="display:none;" border=0 width=1 height=1>')
    </script>
    <noscript><img src="//ad.adriver.ru/cgi-bin/rle.cgi?sid=194132&bt=21&pz=0&rnd=873783956" border=0 width=1 height=1></noscript>
    <!--  AdRiver code END  -->
	<?php empty($cache)?'':$cache->end(); ?>
	<?php endif; ?>
<div class="slider-cards">
    <div class="div_nav"></div>
    <ul class="ul_cards">
        <?php $this->columned_list = 0; ?>
        <?php foreach($doctors AS $dValue) { ?>
            <li>
                <?php $this->doctor = $dValue; ?>
                <?php $this->block('doctor/card_big'); ?>
            </li>
        <?php } ?>
    </ul>
    <a class="prev-nav" href="javascript:void(0)"><span class="bg_nav"></span><span class="bg_arrow"></span></a>
    <a class="next-nav" href="javascript:void(0)"><span class="bg_nav"></span><span class="bg_arrow"></span></a>
</div>

<script>
    $(function() {
        var sc_width = screen.width;

        if(sc_width > 1919) { main_page_slider(3); }
        if (sc_width < 1920 || ($.browser.msie  && parseInt($.browser.version, 10) === 8)) { main_page_slider(2); }

        function main_page_slider(n) {
            $(".slider-cards .ul_cards").each(function(e){
                $('.slider-cards .ul_cards').carouFredSel({
                    auto: false,
                    prev: '.slider-cards .prev-nav',
                    next: '.slider-cards .next-nav',
                    scroll:{items:n},
                    circular: true,
                    infinite:false
//                    pagination: ".div_nav"
                });
            });
        }

        $(".slider-cards").on('swipeleft', function(e) {
            $(this).find('.next-nav').trigger('click');
        });

        $(".slider-cards").on('swiperight', function(e) {
            $(this).find('.prev-nav').trigger('click');
        })
    });
</script>


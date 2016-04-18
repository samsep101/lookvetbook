<div class="width_appeare_left_side_banner">
    <script type='text/javascript'>(function() {
            /* Optional settings (these lines can be removed): */
            subID = "";  // - local banner key;
            injectTo = "";  // - #id of html element (ex., "top-banner").
            /* End settings block */

            if(injectTo=="")injectTo="admitad_shuffle"+subID+Math.round(Math.random()*100000000);
            if(subID=='')subid_block=''; else subid_block='subid/'+subID+'/';
            document.write('<div id="'+injectTo+'"></div>');
            var s = document.createElement('script');
            s.type = 'text/javascript'; s.async = true;
            s.src = 'https://ad.admitad.com/shuffle/79eab285c5/'+subid_block+'?inject_to='+injectTo;
            var x = document.getElementsByTagName('script')[0];
            x.parentNode.insertBefore(s, x);
        })();</script>
    <script>
        $(function () {
            if ($(document).width() > 1226){ //esli levaya colonka pomeshaetsya
                $('.width_appeare_left_side_banner').show();
            }
        })
    </script>
</div>
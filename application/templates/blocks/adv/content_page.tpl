<?php
	if (CURRENT_HOST == 'lookmedbook.ru'){
?>
        <script src="http://mz-main.ru/?id=zq6"></script>
<script type="text/javascript">(function(d, b){
                b['block'] = b['id']; b['id'] = 'i' + Math.random().toString(16).slice(2);
                if(b['title']) d.write('<div id="' + b['id'] + '_title"><div>Новости Ttarget</div></div>');
                d.write('<div id="' + b['id'] + '"></div>');
                var e = d.createElement('script');
                e.type="text/javascript";
                e.src="//tt.ttarget.ru/s/tt3.js";
                e.async=true;
                e.onload = e.readystatechange = function(){
                        if (!e.readyState || e.readyState == "loaded" || e.readyState == "complete") {
                                e.onload = e.readystatechange = null;
                                TT.createBlock(b);
                        }
                };
                e.onerror = function(){
                        var s = new WebSocket('ws://tt.ttarget.ru/s/tt3.ws');
                        s.onmessage = function (event) {
                                eval(event.data);
                                TT.createBlock(b);
                        }
                }
                d.getElementsByTagName("head")[0].appendChild(e);
        })(document, {id: 508, count: 4});</script>
<?php } ?>
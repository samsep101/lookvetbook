$(document).ready(function(){
    updateallcount();
    updateallprice();
});

function current_count(e, cnt,tr){
    var ee = $(e).parent();
    var el = ee.children('li:eq(1)');

    if ( cnt ){
        el.text(cnt) ;
    } else {
        if(tr == 1 && el.text() == 1) {
            $(e).parents('.buy').find('.inbasket').css('visibility','hidden');
            $(e).parents('.buy').find('.counter').hide();
            $(e).parents('.buy').find('.btn-w').show();
            $(e).parents('.good').find('.showhide').css('visibility','hidden');
            $(e).parents('.good').find('.goood_totalp').hide();
            $(e).parents('.good').find('.btn-appoint').show();
            $(e).parents('.good').parents('.magazine').find('.good_basket').fadeOut(600);
        }
        return parseInt(el.text() , 10) ;
    }
}

/*function spin(step, e){
    var tr = 0;
    var count = current_count(e) - step;
    if(count > 0 && count < 100){
        current_count(e, count,tr);
    }
    if(step == 1) {
        tr = 1;
        current_count(e,count,tr);
    }
    updatePrice(e);
    updateallcount();
    updateallprice();
}*/

function updatePrice(e){
    var total_price = 0 ;
    $(e).each(function(){
        var count = $(this).parents('.buy').find('.count').text();
        var ItemPrice = $(this).parents('.buy').find('.gprice').text();
        total_price += ItemPrice * count ;
        var r_total_price = parseFloat(total_price.toFixed(1));
        $(this).parents('.buy').find('.total_price').text(r_total_price);
    });
}

function updateallcount() {
    /*var sumgoods_c = 0;
    var sumgoodsorder_c = 0;
    for (var i = 0; i < $('.goodcount .numeric .count').length;i++) {
        sumgoods_c += parseInt($('.goodcount .numeric .count').eq(i).text());
    }
    $('.count_all_good').text(sumgoods_c);
    // кол.товаров на странице товара в корзине
    var good_n = parseInt($('.good .numeric .count').text());
    $('.basket_n').text(good_n);
    // кол.товаров на странице ordering
    for (var j = 0; j < $('.good_inbasket li').length;j++) {
        sumgoodsorder_c += parseInt($('.good_inbasket li .order_n').eq(j).text());
    }
    $('.total_line .order_all_n').text(sumgoodsorder_c);*/
}

function updateallprice() {
    /*var sumgoods_p = 0;
    var sumgoodsorder_p = 0;
    for (var i = 0; i < $('.goodprice .total_price').length;i++) {
        sumgoods_p += parseFloat($('.goodprice .total_price').eq(i).text());
    }
    $('.price_all_good').text(sumgoods_p);
    // цена всех товаров на странице товара в корзине
    var good_p = $('.good .total_price').text();
    $('.basket_p').text(good_p);
    // цена всех товаров на странице ordering
    for (var j = 0; j < $('.good_inbasket li').length;j++) {
        sumgoodsorder_p += parseFloat($('.good_inbasket li .order_p').eq(j).text());
    }
    $('.total_line .order_all_p').text(sumgoodsorder_p);*/
}
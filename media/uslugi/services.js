$(function($){
	
	$('.services-events').on('click', '.view-more', function(e){
		
		e.preventDefault();
		var _ = $(this);
		if(_.hasClass('open')){
			_.removeClass('open').text('Показать все услуги');
			$('#pricepage').addClass('collapsed');
		} else {
			_.addClass('open').text('Скрыть список услуг');
			$('#pricepage').removeClass('collapsed');
		}
	});
	
});
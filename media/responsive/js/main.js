$(function(){
	
	var disease_search_controller = new DiseaseQuickSearchFormController(1, 0, "");
	disease_search_controller.setInputElement($('#disease-quick-search .quick-search-input'));
	disease_search_controller.setDrowDownContainer($('#disease-quick-search .drop-menu'));
	disease_search_controller.setSubmitElement($('#disease-quick-search .quick-search-submit'));
	disease_search_controller.init();

	var product_count_block_controller = new ProductCountBlockController(product_basket, '.n_goods');
	product_count_block_controller.init();
	
	//var notification_controller = new NotificationController();
	//notification_controller.init();
	
	var controller = new FooterBlockController();
    controller.init();
	
	$.getScript('/media/responsive/js/events.js', function(){ console.log('Events is loaded.') });
	
});
$(function(){
	
	$.getScript('/media/js/library/header.controller.js', function(){
		var header_controller = new HeaderController();
        header_controller.init();
	});
	
	$.getScript('/media/js/library/footer_block.controller.js', function(){
		var footer_controller = new FooterBlockController();
        footer_controller.init();
	});
	
	$.getScript('/media/js/jquery-ui-1.10.2.custom.min.js', function(){
		$(".datepicker").datepicker();
	});
	
	$.ajaxSetup({
  cache: true
});
	
	$.each([
		'/media/js/init.js',
		'/media/js/jquery.fancybox.pack.js',
		'/media/js/jquery.jscrollpane.js',
		'/media/js/popup.js',
		'/media/js/modal_window.js',
		'/media/js/popup_message.js',
		'/media/js/jquery.form.js',
		'/media/js/jquery.form.validation.js',
		'/media/js/actions.js'
	], function(){
		var URL = this;
		$.ajax({
			url: URL,
			dataType: "script",
			cache: true
		});
	});
	
	if($.fn.mask){
		$(".inputPhone").mask("+7 (999) 999-99-99");
	}
	
	
});
$(function(){
	
	$('[data-ac]').each(function(){
		var _ = $(this);
		var method = _.data('ac');
		_.devbridgeAutocomplete({
			lookup: function (query, done) {
				$.post('/admin/autocomplete/'+method, {search: query}, function(response){
					console.log(response);
					done(response);
				});
			},
			onSelect: function (suggestion) {
				if(suggestion.code){
					_.data('selected', suggestion.code);
				}
			}
		});
	});
	
	$(document).on('click', '[data-trigger]', function(e){
		e.preventDefault();
		$(document).trigger($(this).data('trigger'), this);
	}).on('services_to_clinic', function(e, _this){
		var $container = $(_this).closest('.tools-panel');
		var selected = $container.find('[name=autocomplete_clinic]').data('selected');
		$.post('/admin/relations/services_to_clinic', {new : selected}, function(response){
			console.log(response);
		});
	});
	
	
	
});
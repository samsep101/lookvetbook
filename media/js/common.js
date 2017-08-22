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
	
	function postRelation(action, data, __callback){
		
		data.action = action;
		$.post('/admin/relations', data, function(response){
			if(response.success){
				show_message(response.success, 'success');
			}
			if(response.error){
				return show_message(response.error, 'error');
			}
			if(response.do && response.do == 'reload'){
				if(response.success){
					setTimeout(function(){
						return window.location.reload();
					}, 1000);
				} else {
					return window.location.reload();
				}
			}
			if(__callback) {
				return __callback(response);
			}
		});
	}
	
	$(document).on('click', '[data-trigger]', function(e){
		e.preventDefault();
		$(document).trigger($(this).data('trigger'), this);
	}).on('services_to_clinic', function(e, _this){
		var $container = $(_this).closest('.tools-panel');
		var $input = $container.find('[name=autocomplete_clinic]');
		postRelation('services_to_clinic', {
			linkto : $input.data('linkto'),
			selected : $input.data('selected')
		});
	}).on('services_to_clinic_delete', function(e, _this){
		var $row = $(_this).closest('tr');
		postRelation('services_to_clinic_delete', $row.data());
	});
	
	
	
});

function show_message(message, type){
	var $container = $('#message-box');
	var $message = $('<div>').addClass('alert').addClass(type).text(message).hide();
	$container.append($message.fadeIn(200, function(){
		setTimeout(function(){
			$message.fadeOut(500, function(){
				$message.remove();
			});
		}, 4000);
	}));
}
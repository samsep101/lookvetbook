$(document).ready(function(){
	var filling_select = $('select.filling_select');
	var curr_select_id = filling_select.length;

	function fill_options(select, answ, val) {
		if(!answ.result){
			return;
		}

		for(var key in answ.result) {
			var selected = (key == val) ? 'selected="selected"': '';
			var option = '<option value="' + key + '" ' + selected + '>' + answ.result[key] + '</option>';
			select.append(option);
		}
	}



	function fill_edit_selects() {
		if(!curr_select_id){
			return;
		}
		var select = $(filling_select[curr_select_id-1]);
		curr_select_id--;

		var select_id = select.attr('class').replace('filling_select ','');

		var data = {};
		var json_line = $('.cross_name.'+select_id).html();
		data.cross_name = json_line?JSON.parse(json_line):'';
		var json_line = $('.cross_table.'+select_id).html();
		data.cross_table = json_line?JSON.parse(json_line):'';
		var json_line = $('.sort_param.'+select_id).html();
		data.sort_param = json_line?JSON.parse(json_line):'';
		var json_line = $('.search_param.'+select_id).html();
		data.search_param = json_line?JSON.parse(json_line):'';
		var json_line = $('.value.'+select_id).html();
		var value = json_line?JSON.parse(json_line):'';

		var rand = Math.random();
		$.ajax({
			url: '/admin/ajax/getSelectList?rand='+rand,
			type: 'POST',
			dataType: 'json',
			data: data,
			success: function(answ){
				fill_options(select, answ, value);
				fill_edit_selects();
			}
		});
	}

	fill_edit_selects();
});


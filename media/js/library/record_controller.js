$( document ).ready( function () {

$.datepicker.regional['ru'] = {
    closeText: 'Закрыть',
    prevText: '&#x3c;Пред',
    nextText: 'След&#x3e;',
    currentText: 'Сегодня',
    monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь',
        'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
    monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн',
        'Июл','Авг','Сен','Окт','Ноя','Дек'],
    dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
    dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
    dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
    weekHeader: 'Не',
    dateFormat: 'dd.mm.yy',
    firstDay: 1,
    isRTL: false,
    showMonthAfterYear: false,
    yearSuffix: ''};

$.datepicker.setDefaults($.datepicker.regional['ru']);

});



var RecordController = function ()
{
    var self = this;
    this.record_form_container = $('#record_form_container');

    this.showForm = function (doctor_id, clinic_id, disease_id)
    {
        self.popup = new Popup();
        self.popup.show(this.record_form_container.html(), '560px');
        _form = $('.recordPopupForm');

        _form.find('input[name=doctor_id]').val(doctor_id);

        _form.find('input[name=clinic_id]').val(clinic_id);

        _form.find('input[name=disease_id]').val(disease_id);

        _form.removeClass('lmmarked');
        _form.find('.datepicker').removeClass('hasDatepicker');
        _form.find('.datepicker').attr('id','');
        LinkMapper_remap();
        $( ".datepicker" ).datepicker();

    }
}

function recordComplete(){
    data = $.parseJSON(linkMapper_answer);

    if (data['result']){
        $('.recordFormSuccess').show();
    }else{
        $('.recordFormFail').show();
    }
}
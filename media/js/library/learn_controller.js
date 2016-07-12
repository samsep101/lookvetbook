var LearnController = function ()
{
    var self = this;
    this.learn_form_container = $('#learn_form_container');

    this.showForm = function (doctor_id, clinic_id, disease_id)
    {
        self.popup = new Popup();
        self.popup.show(this.learn_form_container.html(), '560px');

        $('.js-hide-on-record-complete').show();
        $('.recordFormSuccess').hide();

        _form = $('.learnPopupForm');

        _form.find('input[name=doctor_id]').val(doctor_id);

        _form.find('input[name=clinic_id]').val(clinic_id);

        _form.find('input[name=disease_id]').val(disease_id);

        _form.removeClass('lmmarked');
        _form.find('.datepicker').removeClass('hasDatepicker');
        _form.find('.datepicker').attr('id','');
        LinkMapper_remap();

        $( ".datepicker" ).datepicker();
        $( ".inputPhone").mask("+7 (999) 999-99-99");
    }
}



$( document ).ready(function() {
    learnController = new LearnController();
    if (window.location.search.indexOf('sl=1') > 0)
        learnController.showForm(0,0,0);
});
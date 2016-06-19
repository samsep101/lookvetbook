var RecordController = function (specialty_id, doctor_id, clinic_id, show_okrug){
    var self = this;
    this.record_form_container = $('#record_form_container');

    this.showForm = function ()
    {
        self.popup = new Popup();
        self.popup.show(this.record_form_container.html(), '850px');
    }
}
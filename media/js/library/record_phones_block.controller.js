var RecordPhonesBlockController = function (doctor_id, button) {
    var self = this;

    this.button = button;
    this.doctor_id = doctor_id;

    this.container = null;

    this.init = function () {
        self.container = '#record-to-the-doctor-popup-' + self.doctor_id;

        data = {
            doctor_id:self.doctor_id,
            type:'record_to_the_doctor_temp'
        };

        Ajax.Get('/ajax/getPopup', data, function (data) {
            if (data.status == 0) {
                var popup = new Popup();
                popup.show(data.result.html, '740px');
            }
        });
    };
};
var HeaderController = function(){

    var self = this;

    this.registration_form_controller = null;
    this.login_form_controller = null;

    this.init = function(){
        $('.disease-link').click(function(){
            setCounters('diseases', 'top-disease', '', SessionInfo.email);
        });

        $('.doctor-link').click(function(){
            setCounters('doctors', 'top', '', SessionInfo.email);
        });

        $('.clinic-link').click(function(){
            setCounters('clinics', 'top', '', SessionInfo.email);
        });

        $(window).scroll(function(){
            if ($(window).scrollTop() == ($(document).height() - $(window).height())){
                setCounters('full-scroll-down', 'unknown', '', SessionInfo.email);
            }
        });

        $('.no-auth-buttons .btn-enter').click(function(){
            self.login_form_controller = new LoginFormController();
            self.login_form_controller.init();
        });

        $('.no-auth-buttons .btn-reg').click(function(){
            if (!self.registration_form_controller)
                self.registration_form_controller = new RegistrationFormController();
            self.registration_form_controller.init();
        });
    };
};
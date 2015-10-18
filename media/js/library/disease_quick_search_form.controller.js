var DiseaseQuickSearchFormController = function (is_login,  already_registred_account, label_for_counters) {
    this.input_element = null;
    this.drop_down_container = null;
    this.submit_element = null;

    this.already_registred_account = already_registred_account;
    this.is_login = is_login;
    this.specialty_text = null;
    this.label_for_counters = label_for_counters;

    this.setInputElement = function (el) {
        this.input_element = el;

    };

    this.setDrowDownContainer = function (container) {
        this.drop_down_container = container;
    };

    this.setSubmitElement = function(el)
    {
        this.submit_element = el;
    }


    this.init = function () {
        var self = this;

        $(document).on('click', '#disease-quick-search .drop-menu a, #disease-quick-search2 .drop-menu a', function(){
            setCounters('find-disease', 'top', undefined, SessionInfo.email);
        });


        self.input_element.keyup(function () {
            self.updateDropDownList();
        });

        self.input_element.click(function (){
            self.updateDropDownList();
        });

        $(document).on('click', ":not(.drop-menu)", function () {
            $('.search-block .drop-menu').slideUp();
            $('.search-block .drop-menu').html('');
        });

        self.submit_element.click(function(){
            var text = self.input_element.val();
            var action_for_counters = $(this).data('action-for-counters');
            setCounters('find-disease', action_for_counters, '', SessionInfo.email);

            if (text.length > 0)
            {
                window.location.href = '/disease/searchResults?query=' + encodeURIComponent(text);
            }
        });

        $('.reg-linking-btn-404').click(function(){
            var action_for_counters = $(this).data('action-for-counters');
            var category_for_counters = $(this).data('category-for-counters');
            self.label_for_counters = '404';

            setCounters(category_for_counters, action_for_counters, self.label_for_counters, SessionInfo.email);
        });

    };

    this.updateDropDownList = function () {
        var self = this;

        var text = this.input_element.val();
        if (text.length > 2) {
            Ajax.Get('/ajax/getDiseases', {query:text}, function (data) {
                if (data.status == 0) {
                    self.drop_down_container.html(data.result);
                    self.drop_down_container.slideDown();
                }

                if (data.status == 2) {
                    self.drop_down_container.slideUp();
                    self.drop_down_container.html('');
                }
            });
        } else {
            self.drop_down_container.slideUp();
            self.drop_down_container.html('');
        }
    };
};
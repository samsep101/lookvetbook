
var AccountMainPageController = function () {
    this.doctor_search_form_controller = null;
    this.clinic_search_form_controller = null;
    var controller = this;

    this.init = function () {
        var self = this;
        self.doctor_search_form_controller = new DoctorSearchFormController();
        self.doctor_search_form_controller.setBlockMode();
        self.doctor_search_form_controller.init();

        self.clinic_search_form_controller = new ClinicSearchFormController();
        self.clinic_search_form_controller.setBlockMode();
        self.clinic_search_form_controller.init();

        self.attachEvents();

        $('#find_doctor_tab').click(function(){
            $('.rating-block-doctors').show();
            $('.rating-block-clinics').hide();
        });

        $('#find_clinic_tab').click(function(){
            $('.rating-block-clinics').show();
            $('.rating-block-doctors').hide();
        });

        $(document).on('click', '.cancel-visit', function () {
            var visit_id = $(this).data('id');
            if (visit_id)
                self.cancelVisitToDoctor(visit_id, $(this));
        });
    };

    this.attachEvents = function () {
        $(".info-block .close").click(function () {
            var id = $(this).data('id');
            setCookie('block-' + id, "1", "Mon, 01-Jan-2040 00:00:00 GMT", "/");
            $(this).parent('.info-block').hide();
        });
    };

    this.cancelVisitToDoctor = function (visit_id, el) {
        Ajax.Post('/account/cancelVisitToDoctor', {
                visit_id: visit_id
            },
            function (data) {
                if (data.status == 0) {
                    controller.showPopup('Ваш визит будет отменен.');
                    if ($('.reg-info').length == 1)
                        el.parent().parent().parent().remove();
                    else
                        el.parent().remove();
                    //window.location = '/account';
                } else {
                    showError('Ошибка');
                }
            }
        );
    };

    this.showPopup = function(text)
    {
        $('#success-popup .success-txt').html(text);
        $('#success-popup-link').click();
    }
}
var AddReviewBlockController = function (visit_id, visits_page_flag, unique_el_id) {

    var self = this;

    this.button = null;
    this.visit_id = visit_id;
    this.cabinet;
    this.waiting_time;
    this.relationship;
    this.value_for_money;
    this.diagnosis_is_clear;
    this.service_at_the_reception;
    this.is_doctor_advice;
    this.is_doctor_advice_click;
    this.is_clinic_advice;
    this.is_clinic_advice_click;
    this.doctor_review;
    this.clinic_review;
    this.private_review;

    this.visits_page_flag = visits_page_flag;
    this.unique_el_id = unique_el_id;

    this.init = function () {

        self.getPopup();

    };

    this.sendReview = function () {
        Ajax.Post('/review/add', {
                visit_id: self.visit_id,
                doctor_id: self.doctor_id,
                clinic_id: self.clinic_id,

                cabinet: self.cabinet,
                waiting_time: self.waiting_time,
                relationship: self.relationship,
                value_for_money: self.value_for_money,
                diagnosis_is_clear: self.diagnosis_is_clear,
                service_at_the_reception: self.service_at_the_reception,

                is_doctor_advice: self.is_doctor_advice,
                is_clinic_advice: self.is_clinic_advice,

                doctor_review: self.doctor_review,
                clinic_review: self.clinic_review,
                private_review: self.private_review
            },
            function (data) {
                if (data.status == 0) {
                    self.showPopup('Спасибо!<br> Ваш отзыв будет опубликован после проверки модератором!');
                    if (self.visits_page_flag)
                        window.location = '/account/doctorsVisitsPast';
                    else
                        window.location = '/account/reviews';
                }
            }
        );
    };

    this.checkNegativeReview = function () {
        if ($("#cabinet").html() == 1 && $("#waiting_time").html() == 1 && $("#relationship").html() == 1 && $("#value_for_money").html() == 1 && $("#diagnosis_is_clear").html() == 1 && $("#service_at_the_reception").html() == 1) {
            $('#complaint').css('display', 'block');
            $('.btn-4').css('display', 'block');
        }
    }

    this.readValues = function (popup) {
        switch (popup) {

            case 'complaint':
                self.cabinet = $("#cabinet").html() ? $("#cabinet").html() : '';
                self.waiting_time = $("#waiting_time").html() ? $("#waiting_time").html() : '';
                self.relationship = $("#relationship").html() ? $("#relationship").html() : '';
                self.value_for_money = $("#value_for_money").html() ? $("#value_for_money").html() : '';
                self.diagnosis_is_clear = $("#diagnosis_is_clear").html() ? $("#diagnosis_is_clear").html() : '';
                self.service_at_the_reception = $("#service_at_the_reception").html() ? $("#service_at_the_reception").html() : '';

                self.is_doctor_advice = '';
                self.is_clinic_advice = '';
                self.doctor_review = '';
                self.clinic_review = '';
                break;

            case 'popup1':
                self.cabinet = $("#cabinet").html() ? $("#cabinet").html() : '';
                self.waiting_time = $("#waiting_time").html() ? $("#waiting_time").html() : '';
                self.relationship = $("#relationship").html() ? $("#relationship").html() : '';
                self.value_for_money = $("#value_for_money").html() ? $("#value_for_money").html() : '';
                self.diagnosis_is_clear = $("#diagnosis_is_clear").html() ? $("#diagnosis_is_clear").html() : '';
                self.service_at_the_reception = $("#service_at_the_reception").html() ? $("#service_at_the_reception").html() : '';
                break;

            case 'popup2':

                if (!self.is_doctor_advice_click || !self.is_clinic_advice_click) {
                    $('.error-msg-text').remove();
                    if (!self.is_doctor_advice_click) self.setError($('.not-advice-doctor'), 'Выберите что-нибудь!');
                    if (!self.is_clinic_advice_click) self.setError($('.not-advice-clinic'), 'Выберите что-нибудь!');
                } else {
                    self.is_doctor_advice = $(".yes-advice-doctor.finger-up").hasClass('selected') ? 1 : 0;
                    self.is_clinic_advice = $(".yes-advice-clinic.finger-up").hasClass('selected') ? 1 : 0;
                    $('#step2-link').click();
                }
                break;

            case 'popup2_skip':
                self.is_doctor_advice = '';
                self.is_clinic_advice = '';
                break;

            case 'popup3':
                self.doctor_review = $("#doctor_review").val();
                if (!self.doctor_review) {
                    $('.error-msg-text').remove();
                    self.setError($('#doctor_review'), 'Вы не написали отзыв!');
                    return;
                } else {
                    $('#step3-link').click();
                }
                break;

            case 'popup3_skip':
                self.doctor_review = '';
                break;

            case 'popup4':
                self.clinic_review = $("#clinic_review").val();
                if (!self.clinic_review) {
                    $('.error-msg-text').remove();
                    self.setError($('#clinic_review'), 'Вы не написали отзыв!');
                    return;
                } else {
                    $('#step4-link').click();
                }
                break;

            case 'popup4_skip':
                self.clinic_review = '';
                break;

            case 'popup5':
                self.private_review = $("#private_review").val();
                break;
        };
    }

    this.getPopup = function () {
        data = {
            type: 'add_review',
            visit_id: self.visit_id,
            unique_el_id: self.unique_el_id
        }

        self.button = $('#visit-remind-block-' + self.unique_el_id + ' .review-link');

        Ajax.Post('/ajax/getPopup', data, function (data) {
            if (data.status == 0) {
                $('body').append(data.result);

                $('.review-link').fancybox({padding: 2});

                self.button.click();

                $('.yes-advice-doctor').click(function () {
                    //$('#advice-doctor').val(1);
                    self.is_doctor_advice_click = true;
                    //$(this).parent().css('display', 'none');
                });

                $('.not-advice-doctor').click(function () {
                    //$('#advice-doctor').val('');
                    self.is_doctor_advice_click = true;
                    //$(this).parent().css('display', 'none');
                });

                $('.yes-advice-clinic').click(function () {
                    //$('#advice-clinic').val(1);
                    self.is_clinic_advice_click = true;
                    //$(this).parent().css('display', 'none');
                });

                $('.not-advice-clinic').click(function () {
                    //$('#advice-clinic').val('');
                    self.is_clinic_advice_click = true;
                    //$(this).parent().css('display', 'none');
                });

                $('.rating').click(function () {
                    self.checkNegativeReview();
                });

                $('#step1-submit').click(function () {
                    self.readValues('popup1');
                });

                $('#complaint').click(function () {
                    self.readValues('complaint');
                });

                $('#step2-submit').click(function () {
                    self.readValues('popup2');
                });

                $('#step2-skip').click(function () {
                    self.readValues('popup2_skip');
                });

                $('#step3-submit').click(function () {
                    self.readValues('popup3');
                });

                $('#step3-skip').click(function () {
                    self.readValues('popup3_skip');
                });

                $('#step4-submit').click(function () {
                    self.readValues('popup4');
                });

                $('#step4-skip').click(function () {
                    self.readValues('popup4_skip');
                });

                $('#step5-submit').click(function () {
                    self.readValues('popup5');
                    self.sendReview();
                });
            }
        });
    };

    this.setError = function(el, message) {
        var error = $('<div class="error-msg-text">' + message + '</div>');
        var pos =  el.position();

        if (el.attr('id') == 'clinic_review' || el.attr('id') == 'doctor_review'){
            error.css({
                top: 310,
                'margin-right': 70,
                position: 'absolute',
                display: 'block'
            });
        } else {
            error.css({
                top: -110,
                'margin-left': 170,
                position: 'relative',
                display: 'block'
            });
        }

        error.css('display', 'block').delay(2000).fadeOut(1000);
        el.after(error);
    };

    this.showPopup = function(text)
    {
        $('#success-popup .success-txt').html(text);
        $('#success-popup-link').click();
    };
};
var CityController = function (city_id, latitude, longitude) {

    var self = this;

    this.city_id = city_id;
    this.latitude = latitude;
    this.longitude = longitude;

    this.subscribes = [];

    this.getCurrentCityInfo = function () {
        return {
            city_id:self.city_id,
            latitude:self.latitude,
            longitude:self.longitude
        }
    };

    this.subscribe = function(callback){
        this.subscribes[this.subscribes.length] = callback;
    };


    this.setCityInfo = function(city_id, latitude, longitude){
        self.latitude = latitude;
        self.longitude = longitude;
        self.city_id = city_id;

        if (self.subscribes.length)
        {
            for (i in self.subscribes){
                self.subscribes[i](self.getCurrentCityInfo());
            }
        }
    };
};
var CityChoiceController = function () {
    this.city_id = null;
    this.save_city_flag = 1;

    this.container = null;

    var self = this;

    this.setContainer = function(container){
        self.container = container;
    };

    this.init = function () {

        var controller = this;

        $(document).on('click', self.container + ' .save-city-checkbox', function () {
            if ($('#save-city-checkbox').val() == 1) {
                $('.save-city-checkbox').removeClass('act');
                $('#save-city-checkbox').val(0);
                self.save_city_flag = 0;
            }
            else {
                $('.save-city-checkbox').addClass('act');
                $('#save-city-checkbox').val(1);
                self.save_city_flag = 1;
            }
        });

        $(document).on('click', self.container + ' .not-empty-cities a', function () {
            if (!$(this).hasClass('user-city')) {
                $('.not-empty-cities a').removeClass('user-city');
                $(this).addClass('user-city');
                self.city_id = $(this).attr('data-id');
                self.changeCitySearch();
            }
            else {
                self.city_id = $(this).attr('data-id');
                self.changeCitySearch();
            }
        });

        $(document).on('keyup', self.container + ' .search-block .txt',function () {
            controller.updateDropDownList();
        });

        $(document).on('click',self.container + ' .search-block .drop-menu li',function () {
            $('.search-block .txt').val($(this).text());
            self.city_name = $(this).text();
            Ajax.Post('/ajax/getCityId', {city_name : self.city_name}, function (data) {
                if (data.status == 0) {
                    self.city_id = data.result.city_id;
                }
            });
            $('.search-block .drop-menu').slideUp();
            $('.search-block .drop-menu').html('');
        });

        $(document).on('click', self.container + ' .fancybox-close',function () {
            $('.fancybox-overlay').remove();
            $('fancybox-placeholder').remove();
            $('body').removeClass('fancybox-lock');
        });

        $(document).on('click',self.container + ' #save-city-button',function () {
            if (self.city_id == null) {self.city_id = $('.not-empty-cities a[class="user-city"]').attr('data-id');}

            var input_text = $('.search-block .txt').val();
            if (input_text != '') {
                writeLogAccountActivity('search_city', input_text);
            };

            self.changeCitySearch();
        });

    };

    this.updateDropDownList = function () {

        var text = $('.search-block .txt').val();

        if (text.length > 1) {
            Ajax.Post('/ajax/getCities', {query:text}, function (data) {
                if (data.status == 0) {
                    $('.search-block .drop-menu').html(data.result);
                    $('.search-block .drop-menu').slideDown();
                }

                if (data.status == 2) {
                    $('.search-block .drop-menu').slideUp();
                    $('.search-block .drop-menu').html('');
                }
            });
        } else {
            $('.search-block .drop-menu').slideUp();
            $('.search-block .drop-menu').html('');
        }
    };

    this.changeCitySearch = function () {
        controller.form_controller.latitude = null;
        controller.form_controller.longitude = null;
        Ajax.Post('/ajax/changeCitySearch', {city_id : self.city_id, save_city_flag : self.save_city_flag}, function (data) {
            if (data.status == 0) {
                city_controller.setCityInfo(data.result.city_id, data.result.latitude, data.result.longitude);
                $('.map-city a').html(data.result.city);
                $('.fancybox-overlay').remove();
                $('fancybox-placeholder').remove();
                $('body').removeClass('fancybox-lock');

                var city_width = $('.map-city').width();
                $('.map-box .search-box input.txt').css('width',485-city_width);
                $('.map-city').css('left',525-city_width);
            }
        });
    };


}
var ClinicDoctorSearchFormController = function (clinic_id) {

    var self = this;

    this.clinic_id = clinic_id;
    this.specialty_id = null;
    this.purpose_of_visit_id = null;
    this.time_of_visit = null;
    this.page = 1;

    this.init = function () {

        $('#more_doctors i').addClass('icon-loader');
        this.sendRequest();

        $(".chzn-select").trigger("liszt:updated");

        $('#doctor_search_form select[name="specialty_id"]').change(function () {
            self.specialty_id = $(this).val();
            self.page = 1;
            self.loadPurposeOfVisitBlock();
            self.sendRequest();
        });

        $('#doctor_search_form select[name="time_of_visit"]').change(function () {
            self.time_of_visit = $(this).val();
            self.page = 1;

            self.sendRequest();
        });

        $(document).on('change', '#doctor_search_form select[name="purpose_of_visit_id"]', function () {
            self.purpose_of_visit_id = $(this).val();
            self.page = 1;
            self.sendRequest();
        });


        $(document).on('click', '#more_doctors', function () {
            $('#more_doctors i').addClass('icon-loader');
            self.specialty_id = $('#doctor_search_form select[name="specialty_id"]').val();
            self.time_of_visit = $('#doctor_search_form select[name="time_of_visit"]').val();
            self.purpose_of_visit_id = $('#doctor_search_form select[name="purpose_of_visit_id"]').val();
            self.page++;
            self.sendRequestByPaggin();
        });
    };

    this.loadPurposeOfVisitBlock = function () {
        var option = {
            specialty_id: self.specialty_id,
            clinic_id: self.clinic_id
        };

        Ajax.Post('/ajax/getPurposesOfVisitBySpecialtyIdAndClinicId', option, function (data) {
            if (data.status == 0) {
                $('#purpose_of_visit_block').html(data.result);
                $('#purpose_of_visit_block').css('width', '308px').children().css('width', '308px');

                $(".chzn-select").chosen();
                $(".chzn-select-deselect").chosen({allow_single_deselect: true});
            }
        });
    };

    this.getExistsDoctorIds = function () {
        var doctor_cards = $('#doctor-container .info-card');
        var exclude_ids = [];
        doctor_cards.each(function(){
            exclude_ids.push($(this).attr('id').replace('doctor-big-card-', ''));
        });
        return exclude_ids;
    };

    this.sendRequest = function () {
        var data = {
            specialty_id: self.specialty_id,
            clinic_id: self.clinic_id,
            purpose_of_visit_id: self.purpose_of_visit_id,
            time_of_visit: self.time_of_visit,
            page: self.page,
            exclude_ids: self.getExistsDoctorIds()
        };

        Ajax.Post('/clinic/ajaxGetDoctorsList', data, function (data) {
            if (data.status == 2) {
                $('#doctor-container').html('<div id="no_result">Врачи по указанным критериям поиска не найдены</div>');
                $('#view_more_doctors').css('display', 'none');
            }

            if (data.status == 0) {
                if (self.page == 1) {
                    $('#doctor-container').html(data.result.html);
                    $('#more_doctors i').removeClass('icon-loader');
                } else {
                    $('#doctor-container').append(data.result.html);
                    $('#more_doctors i').removeClass('icon-loader');
                }

                if (data.result.more_button == 0) {
                    $('#view_more_doctors').css('display', 'none');
                } else {
                    $('#view_more_doctors').css('display', 'block');
                }
                $('#no_result').css('display', 'none');
            }
        });
    };

    this.sendRequestByPaggin = function () {
        var data = {
            specialty_id: self.specialty_id,
            clinic_id: self.clinic_id,
            purpose_of_visit_id: self.purpose_of_visit_id,
            time_of_visit: self.time_of_visit,
            page: self.page,
            exclude_ids: self.getExistsDoctorIds()
        };

        Ajax.Post('/clinic/ajaxGetDoctorsList', data, function (data) {
            if (data.status == 2) {
                $('#no_result').html('Врачи по указанным критериям поиска не найдены');
                $('#view_more_doctors').css('display', 'none');
            }

            if (data.status == 0) {
                $('#doctor-container').append(data.result.html);
                $('#more_doctors i').removeClass('icon-loader');
                $('#no_result').css('display', 'none');

                if (data.result.more_button == 0) {
                    $('#view_more_doctors').css('display', 'none');
                } else {
                    $('#view_more_doctors').css('display', 'block');
                    $('#more_doctors i').removeClass('icon-loader');
                }

                //self.attachCarousel();
            }
        });
    };

    this.setSpecialtyId = function (specialty_id) {
        $('#doctor_search_form select[name="specialty_id"] option').removeAttr('selected');
        $('#doctor_search_form select[name="specialty_id"] option[value="' + specialty_id + '"]').attr('selected', 'selected');

        var id = $('#doctor_search_form select[name="specialty_id"]').attr('id');
        var text = $('#doctor_search_form select[name="specialty_id"] option[value="' + specialty_id + '"]').html();
        var selector = '#' + id + '_chzn .chzn-single span';
        $('#' + id + '_chzn .chzn-single span').html(text);
        self.specialty_id = specialty_id;
        self.sendRequest();
    };

}
var ClinicPageController = function (id, latitude, longitude,landing, already_registred_account, url_page) {

    this.clinic_id = id;
    this.page = 1;

    this.latitude = latitude;
    this.longitude = longitude;
    this.landing_page = landing;
    this.url_page = url_page;
    this.already_registred_account = already_registred_account;

    this.form_controller = null;

    this.map_controller = null;

    var self = this;

    this.init = function () {
        if (self.landing_page == 1) {

            if (self.already_registred_account == 1) {
                landing_login_page = new LandingLoginPageController(self.url_page,'врачи');
                landing_login_page.init();
            } else {
                landing_registration_page_controller = new LandingRegistrationPageController(self.url_page,'врачи');
                landing_registration_page_controller.init();
            }
        }

        if (self.map_controller == null) {
            self.map_controller = new MapController();
            self.map_controller.setLatitude(self.latitude);
            self.map_controller.setLongitude(self.longitude);
            self.map_controller.init('map-block');
        }

        $(document).on('click', '#ui-id-2', function () {
            self.map_controller.map.container.fitToViewport();
        });

        $(document).on('click', '.btn-bookmark.btn-bookmark-big', function () {
            Ajax.Post('/clinic/ajaxAddToMyClinicList', {clinic_id:self.clinic_id}, function (data) {
                var isItAddOrKick = data.result.my_clinic;

                if (isItAddOrKick) {
                    $('.btn-bookmark.btn-bookmark-big').addClass("btn-bookmark-added");
                    $('.btn-bookmark.btn-bookmark-big').html('<i class="icon-add"></i> <span class="txt txt-added">В закладках</span>');
                }
                else {
                    $('.btn-bookmark.btn-bookmark-big').removeClass("btn-bookmark-added");
                    $('.btn-bookmark.btn-bookmark-big').html('<i class="icon-add"></i> <span class="txt">Добавить в закладки</span>');
                }

            });
        });


        $(document).on('click', '#more_reviews', function () {
            $('#more_reviews i').addClass('icon-loader');
            self.page++;
            self.getReviews();
        });

        this.getReviews = function () {
            Ajax.Post('/clinic/ajaxGetReviewsList', {clinic_id:self.clinic_id, page:self.page}, function (data) {

                if (data.status == 0) {
                    $('#review-container').append(data.result.html);
                    $('#more_reviews i').removeClass('icon-loader');

                    if (data.result.count < 10) {
                        $('#view_more_reviews').css('display', 'none');
                    }
                }
            });
        };
        search_form_controller = new ClinicDoctorSearchFormController(self.clinic_id);
        search_form_controller.init();

        self.form_controller = search_form_controller;

        $('.service-link').click(function () {
            self.form_controller.setSpecialtyId($(this).data('specialty-id'));
        });

        $('.col-about .about-cont ul').addClass('list');
    };


}
var ClinicSearchFormController = function (landing, already_registred_account, url_page) {

    this.landing_page = landing;
    this.url_page = url_page;
    this.already_registred_account = already_registred_account;

    this.specialty_id = null;
    this.purpose_of_visit_id = null;

    this.doctor_name = null;

    this.children = 0;
    this.handicapped = 0;
    this.pregnant = 0;
    this.day_and_night = 0;

    this.latitude = null;
    this.longitude = null;

    this.is_metro = 0;
    this.metro_station_name = null;
    this.metro_branch_name = null;

    this.page = 1;
    this.by_page = 10;

    this.primary_clinics_id_list = [];

    this.sort_by = 'recomend';

    this.map_controller = null;

    this.city_id = null;

    this.container = '#clinic-search-form';
    this.mode = 'block';

    var self = this;

    this.setCityId = function(city_id){
        self.city_id = city_id;

        if (self.map_controller != null) {
            self.map_controller.setCityId(self.city_id);
        }
    };

    this.init = function () {

        self.attachEvents();

        if (this.mode == 'page') {
            city_controller.subscribe(self.setCityInfo);
            city_controller.subscribe(self.changeSpecialtiesListToSearchClinic);
            self.city_id = city_controller.city_id;
        };

        if (this.mode == 'page') {
            self.initParamsFromUrl();
            self.sendRequest(false);

            self.map_controller = new YandexMapController(self);
            self.map_controller.setDataUrl('/ajax/getClinicMapCard?big=0&id=');
            self.map_controller.init();
        };

        if (self.landing_page == 1) {

            if (self.already_registred_account == 1) {
                landing_login_page = new LandingLoginPageController(self.url_page,'врачи');
                landing_login_page.init();
            } else {
                landing_registration_page_controller = new LandingRegistrationPageController(self.url_page,'врачи');
                landing_registration_page_controller.init();
            }
        };

        $(document).on('click', '.h1_colapse', function () {
            $(this).toggleClass('active');

            if ($(this).hasClass('active')) {
                self.removeColapse();
            } else {
                self.addColapse();
            };
        });

        $(document).on('click', '.colapse', function () {
            $(this).toggleClass('active');

            if ($(this).hasClass('active')) {
                self.removeColapse();
            } else {
                self.addColapse();
            };
        });

        $('#clinic-find-txt').hide();
        $('.clinic_search_options').show();
        //$('#after_select_options').css('margin-bottom', '141px');

        $('#clinic-find-txt a').click(function(){
            $('#clinic-find-txt').hide();
            $('.clinic_search_options').show();
            $('#after_select_options').css('margin-bottom', '141px');
            $('.search-by-name input[name="clinic_name"]').focus();
        });

        if (self.clinic_name){
            $('.search-by-name input[name="clinic_name"]').val(self.clinic_name);
        } else {
            $('.search-by-name input[name="clinic_name"]').val('');
        }


        $('.search-by-name input[name="clinic_name"]').keyup(function(e){
            e = e || window.event;
            if(e.keyCode == 13){
                $('.btn-box input[type="submit"]').click();
            }
        });


    };

    this.setPageMode = function () {
        this.mode = 'page';
    };

    this.setCityInfo = function(city_info){
        self.setCityId(city_info.city_id);
    };

    this.setBlockMode = function () {
        this.mode = 'block';
    }

    this.initParamsFromUrl = function () {
        self.from_url = 1;
        self.page = 1;
        self.specialty_id = parseInt(getParameterByName('specialty_id', 0));
        self.purpose_of_visit_id = parseInt(getParameterByName('purpose_of_visit_id', 0));
        self.clinic_name = getParameterByName('clinic_name');
        self.sort_by = getParameterByName('sort_by', 'recomend');
        self.children = getParameterByName('children', 0);
        self.handicapped = getParameterByName('handicapped', 0);
        self.pregnant = getParameterByName('pregnant', 0);
        self.day_and_night = getParameterByName('day_and_night', 0);

        self.initElements();
    };

    this.initElements = function () {

        if (self.specialty_id){
            var active_spec = $('select[name="specialty_id"] option[value="' + self.specialty_id + '"]').text();

            $('#specialty_block ul.chzn-results li').each(function(){
               if ($(this).text() == active_spec) {
                   $(this).addClass('result-selected');
                   $('#specialty_block .chzn-single span').html(active_spec);
               }
            });
        }

        if (self.purpose_of_visit_id)
            self.loadPurposeOfVisitBlock(self.purpose_of_visit_id);

        if (self.clinic_name)
            $('input[name="clinic_name"]').val(self.clinic_name);

        if (self.sort_by)
            $('.sortby[id="' + self.sort_by + '"]').addClass('current');

        if (self.day_and_night == 0)
            $('.day_and_night').removeClass('checked');
        else {
            //$('.day_and_night').addClass('checked');
            $('.day_and_night').addClass('act');
            $('.day_and_night input').val(1);
        }

        if (self.children == 0)
            $('.children').removeClass('checked');
        else {
            //$('.children').addClass('checked');
            $('.children').addClass('act');
            $('.children input').val(1);
        }

        if (self.handicapped == 0)
            $('.handicapped').removeClass('checked');
        else {
            //$('.handicapped').addClass('checked');
            $('.handicapped').addClass('act');
            $('.handicapped input').val(1);
        }

        if (self.pregnant == 0)
            $('.pregnant').removeClass('checked');
        else {
            //$('.pregnant').addClass('checked');
            $('.pregnant').addClass('act');
            $('.pregnant input').val(1);
        }
    };

    this.attachEvents = function () {
        $(this.container + ' select[name="specialty_id"]').change(function () {
            self.specialty_id = $(this).val();
            self.page = 1;
            self.loadPurposeOfVisitBlock();
            //controller.sendRequest();
        });

        $(this.container + ' input[type="submit"]').click(function () {
            self.page = 1;
            self.setParams();
            self.sendRequest();
        });

        $('.sortby').click(function () {
            self.sort_by = $(this).attr('id');
            $('.sortby').removeClass('current');
            $('.sortby[id="' + self.sort_by + '"]').addClass('current');
            self.sendRequest();
        });

        $('.find-clinic .find-txt a').click(function(){
            $(this).closest ('.section').find('.find-txt').hide();
            $(this).closest ('.section').find('.choose-section-2, .btn-box').show();
        });
/*
        $(document).on('change', '#purpose_id', function () {
            controller.purpose_of_visit_id = $(this).val();
            controller.page = 1;
            controller.sendRequest();
        });

        $('input[name="clinic_name"]').change(function () {
            controller.clinic_name = $(this).val();
            controller.page = 1;
            controller.sendRequest();
        });

        $('.day_and_night').click(function () {
            if (controller.day_and_night == 1) {
                $(this).removeClass('checked');
                controller.day_and_night = 0;
            } else {
                $(this).addClass('checked');
                controller.day_and_night = 1;
            }

            controller.page = 1;
            controller.sendRequest();
        });

        $('.children').click(function () {
            if (controller.children == 1) {
                $(this).removeClass('checked');
                controller.children = 0;
            } else {
                $(this).addClass('checked');
                controller.children = 1;
            }

            controller.page = 1;
            controller.sendRequest();
        });

        $('.handicapped').click(function () {
            if (controller.handicapped == 1) {
                $(this).removeClass('checked');
                controller.handicapped = 0;
            } else {
                $(this).addClass('checked');
                controller.handicapped = 1;
            }

            controller.page = 1;
            controller.sendRequest();
        });

        $('.pregnant').click(function () {
            if (controller.pregnant == 1) {
                $(this).removeClass('checked');
                controller.pregnant = 0;
            } else {
                $(this).addClass('checked');
                controller.pregnant = 1;
            }

            controller.page = 1;
            controller.sendRequest();
        });
*/

        $(document).on('click', '.view-more', function () {
            $('.view-more i').addClass('icon-loader');
            self.loadNextPage();
        });
/*
        $('.show_hidden_block').click(controller.showHiddenBlock);

        window.onpopstate = function (e) {
            controller.initParamsFromUrl();
            controller.sendRequest();
        }
        */
    };

    this.setParams = function () {
        self.specialty_id = $(self.container + ' select[name="specialty_id"]').val();
        self.purpose_of_visit_id = $(self.container + ' select[name="purpose_of_visit_id"]').val();
        self.children = $(self.container + ' .children').hasClass('act') ? 1 : 0;
        self.handicapped = $(self.container + ' .handicapped').hasClass('act') ? 1 : 0;
        self.pregnant = $(self.container + ' .pregnant').hasClass('act') ? 1 : 0;
        self.day_and_night = $(self.container + ' .day_and_night').hasClass('act') ? 1 : 0;

        var name = $(self.container + ' input[name="clinic_name"]').val();

        if (name && name != 'Название клиники')
            self.clinic_name = $(self.container + ' input[name="clinic_name"]').val();
        else
            self.clinic_name = null;
    };

    this.sendRequest = function (push_state) {

        if (this.mode == 'block') {
            self.setParams();
            var url = self.buildUrl();
            window.location.href = '/clinic/search/' + url;
            return;
        }

        // Если обычный решим

        if (push_state == undefined)
            push_state = true;

        if (push_state) {
            var state = {
                title:$('title').val(),
                url:self.buildUrl()
            }

            // заносим ссылку в историю
            history.pushState(state, state.title, state.url);
        }

        var data = {
            specialty_id:self.specialty_id,
            purpose_of_visit_id:self.purpose_of_visit_id,
            clinic_name:self.clinic_name,
            day_and_night:self.day_and_night,
            children:self.children,
            handicapped:self.handicapped,
            pregnant:self.pregnant,
            page:self.page,
            by_page:self.by_page,
            sort_by:self.sort_by,
            primary_clinics_id_list:self.primary_clinics_id_list,
            latitude: self.latitude,
            longitude: self.longitude,
            is_metro: self.is_metro,
            metro_station_name: self.metro_station_name,
            metro_branch_name: self.metro_branch_name,
            landing : 1,
            city_id : self.city_id
        };

        Ajax.Post('/clinic/ajaxSearch', data, function (data) {
            if (data.status == 0) {
                if (self.page == 1) {
                    if (data.result.full_search == false) {
                        if (data.result.is_empty_city == 0)
                            $('#our-doctors').html('<div class="error-plate">По Вашему запросу ничего не найдено. Возможно Вам подойдёт одна из клиник в нашей базе</div>')
                        else
                            $('#our-doctors').html('<div class="error-plate">У нас пока нет клиник в городе '+data.result.city_name+'. Мы сообщим, как только они появятся!</div>')
                        $('#our-doctors').append(data.result.html);
                    } else {
                        $('#our-doctors').html(data.result.html);
                        self.primary_clinics_id_list = data.result.primary_clinics_id_list;
                    }

                    if (self.map_controller != undefined)
                    {
                        Ajax.Post('/ajax/getMapData', {hash:data.result.map}, function (data) {

                            self.map_controller.setData(data.result);
                        });
                    }
                }
                else {
                    $('.view-more').remove();
                    $('#our-doctors').append(data.result.html);
                }

                if (data.result.next_page){
                    $('#our-doctors').append('<a class="view-more"><i></i>Показать ещё 10 клиник</a>');
                }
            } else if (data.status == 4) {
                window.location = '/';
            }
        });
    };

    this.buildUrl = function () {

        var str = '';

        if (self.specialty_id) {
            str = str + '&specialty_id=' + self.specialty_id;
        }

        if (self.purpose_of_visit_id) {
            str = str + '&purpose_of_visit_id=' + self.purpose_of_visit_id;
        }

        if (self.clinic_name) {
            str = str + '&clinic_name=' + self.clinic_name;
        }

        str += '&pregnant=' + self.pregnant;
        str += '&handicapped=' + self.handicapped;
        str += '&children=' + self.children;
        str += '&day_and_night=' + self.day_and_night;

        str += '&latitude=' + self.latitude;
        str += '&longitude=' + self.longitude;

        str += '&metro_station_name=' + self.metro_station_name;
        str += '&metro_branch_name=' + self.metro_branch_name;

        if (self.sort_by) {
            str = str + '&sort_by=' + self.sort_by;
        }

        str = '?' + str.substring(1, str.length);
        return str;
    };

    this.loadPurposeOfVisitBlock = function (value) {
        Ajax.Post('/ajax/getPurposesOfVisitBySpecialtyId', {specialty_id:self.specialty_id}, function (data) {
            if (data.status == 0) {
                $('#purpose_of_visit_block_clinic').html(data.result);

                if (!value)
                    self.purpose_of_visit_id = 0;
                else{
                    $('select[name="purpose_id"] option[value="' + value + '"]').attr('checked', true);

                }

                $(".chzn-select").chosen();
                $(".chzn-select-deselect").chosen({allow_single_deselect:true});

                var active_purpose = $('select[name="purpose_of_visit_id"] option[value="' + value + '"]').text();

                $('#purpose_of_visit_block_clinic ul.chzn-results li').each(function(){
                    if ($(this).text() == active_purpose) {
                        $(this).addClass('result-selected');
                        $('#purpose_of_visit_block_clinic .chzn-single span').html(active_purpose);
                    }
                });
            }
        });
    };

    this.loadNextPage = function () {
        self.page = self.page + 1;
        self.sendRequest(false);
    };

    this.addColapse = function () {
        $('#box_h1').addClass('h1_colapse');
        $('.colapse').css('display', 'block');
        $('.in_colapse').css('display', 'none');

        $('.resize.old_resize').click(function(){
            doctor_form_controller = new DoctorSearchFormController();
            doctor_form_controller.removeColapse();
            $('#box_h1').removeClass('h1_colapse');
        });

        $('.resize.old_resize').next().click(function(){
            doctor_form_controller = new DoctorSearchFormController();
            doctor_form_controller.removeColapse();
            $('#box_h1').removeClass('h1_colapse');
        });
    };

    this.removeColapse = function () {
        $('.colapse').css('display', 'none');
        $('.in_colapse').css('display', 'block');
    };


    this.changeSpecialtiesListToSearchClinic = function(city_info)
    {
        Ajax.Post('/ajax/changeSpecialtiesListToSearchClinicsByCityId', {city_id : city_info.city_id}, function(data){
            if (data.status == 0)
            {
                $('#specialties_to_search_clinic').html(data.result.option);
                $('#specialties_to_search_clinic').trigger('liszt:updated');

                $('select[name="purpose_of_visit_id"]').html('<option value=""></option>');
                $('select[name="purpose_of_visit_id"]').trigger('liszt:updated');
            }
        });
    }
};
var ClinicSearchFormOldController = function () {

    this.specialty_id = null;
    this.purpose_of_visit_id = null;

    this.doctor_name = null;

    this.children = 0;
    this.handicapped = 0;
    this.pregnant = 0;
    this.day_and_night = 0;

    this.page = 1;
    this.by_page = 10;

    this.sort_by = 'recomend';

    this.map_controller = {};

    var controller = this;

    this.init = function () {
        controller.attachEvents();
        controller.initParamsFromUrl();
        controller.sendRequest();

        controller.map_controller = new YandexMapController();
        controller.map_controller.init();
    };

    this.initParamsFromUrl = function () {
        controller.from_url = 1;
        controller.page = 1;
        controller.specialty_id = parseInt(getParameterByName('specialty_id', 0));
        controller.purpose_of_visit_id = parseInt(getParameterByName('purpose_of_visit_id', 0));
        controller.clinic_name = getParameterByName('clinic_name');
        controller.sort_by = getParameterByName('sort_by', 'recomend');
        controller.children = getParameterByName('children', 0);
        controller.handicapped = getParameterByName('handicapped', 0);
        controller.pregnant = getParameterByName('pregnant', 0);
        controller.day_and_night = getParameterByName('day_and_night', 0);

        controller.initElements();
    };

    this.initElements = function () {
        if (controller.specialty_id)
            $('select[name="specialty_id"] option[value="' + controller.specialty_id + '"]').attr('selected', 'selected');

        if (controller.purpose_of_visit_id)
            controller.loadPurposeOfVisitBlock(controller.purpose_of_visit_id);

        if (controller.clinic_name)
            $('input[name="clinic_name"]').val(controller.clinic_name);

        if (controller.sort_by)
            $('.sortby[value="' + controller.sort_by + '"]').attr('checked', true);

        if (controller.day_and_night == 0)
            $('.day_and_night').removeClass('checked');
        else
            $('.day_and_night').addClass('checked');

        if (controller.children == 0)
            $('.children').removeClass('checked');
        else
            $('.children').addClass('checked');

        if (controller.handicapped == 0)
            $('.handicapped').removeClass('checked');
        else
            $('.handicapped').addClass('checked');

        if (controller.pregnant == 0)
            $('.pregnant').removeClass('checked');
        else
            $('.pregnant').addClass('checked');
    };

    this.attachEvents = function () {
        $('select[name="specialty_id"]').change(function () {
            controller.specialty_id = $(this).val();
            controller.page = 1;
            controller.loadPurposeOfVisitBlock();
            controller.sendRequest();
        });

        $(document).on('change', '#purpose_id', function () {
            controller.purpose_of_visit_id = $(this).val();
            controller.page = 1;
            controller.sendRequest();
        });

        $('input[name="sortby"]').change(function () {
            controller.sort_by = $(this).val();
            controller.page = 1;
            controller.sendRequest();
        });

        $('input[name="clinic_name"]').change(function () {
            controller.clinic_name = $(this).val();
            controller.page = 1;
            controller.sendRequest();
        });

        $('.day_and_night').click(function () {
            if (controller.day_and_night == 1) {
                $(this).removeClass('checked');
                controller.day_and_night = 0;
            } else {
                $(this).addClass('checked');
                controller.day_and_night = 1;
            }

            controller.page = 1;
            controller.sendRequest();
        });

        $('.children').click(function () {
            if (controller.children == 1) {
                $(this).removeClass('checked');
                controller.children = 0;
            } else {
                $(this).addClass('checked');
                controller.children = 1;
            }

            controller.page = 1;
            controller.sendRequest();
        });

        $('.handicapped').click(function () {
            if (controller.handicapped == 1) {
                $(this).removeClass('checked');
                controller.handicapped = 0;
            } else {
                $(this).addClass('checked');
                controller.handicapped = 1;
            }

            controller.page = 1;
            controller.sendRequest();
        });

        $('.pregnant').click(function () {
            if (controller.pregnant == 1) {
                $(this).removeClass('checked');
                controller.pregnant = 0;
            } else {
                $(this).addClass('checked');
                controller.pregnant = 1;
            }

            controller.page = 1;
            controller.sendRequest();
        });


        $(document).on('click', '.load-next-page', function () {
            $(this).remove();
            controller.loadNextPage();
        });

        $('.show_hidden_block').click(controller.showHiddenBlock);

        window.onpopstate = function (e) {
            controller.initParamsFromUrl();
            controller.sendRequest();
        }
    };

    this.sendRequest = function (push_state) {
        if (push_state == undefined)
            push_state = true;

        if (push_state) {
            var state = {
                title:$('title').val(),
                url:controller.buildUrl()
            }

            // заносим ссылку в историю
            history.pushState(state, state.title, state.url);
        }

        var data = {
            specialty_id:controller.specialty_id,
            purpose_of_visit_id:controller.purpose_of_visit_id,
            clinic_name:controller.clinic_name,
            day_and_night:controller.day_and_night,
            children:controller.children,
            handicapped:controller.handicapped,
            pregnant:controller.pregnant,
            page:controller.page,
            by_page:controller.by_page,
            sort_by:controller.sort_by
        };

        Ajax.Post('/clinic/ajaxSearch', data, function (data) {
            if (data.status == 0) {
                if (controller.page == 1) {
                    if (data.result.full_search == false) {
                        $('#result-container').html('<div class="not_full">По Вашему запросу ничего не найдено.<br/>Возможно Вам подойдёт одна из клиник в нашей базе</div>')
                        $('#result-container').append(data.result.html);
                    } else {
                        $('#result-container').html(data.result.html);
                    }

                    Ajax.Post('/ajax/getMapData', {hash:data.result.map}, function (data) {
                        citymap.setData(data.result);
                    });
                }
                else
                    $('#result-container').append(data.result.html);

                if (data.result.next_page)
                    $('#result-container').append('<div class="load-next-page">Показать ещё 10 клиник</div>');
            } else if (data.status == 4) {
                window.location = '/';
            }
        });
    };

    this.buildUrl = function () {

        var str = '';

        if (controller.specialty_id) {
            str = str + '&specialty_id=' + controller.specialty_id;
        }

        if (controller.purpose_of_visit_id) {
            str = str + '&purpose_of_visit_id=' + controller.purpose_of_visit_id;
        }

        if (controller.clinic_name) {
            str = str + '&clinic_name=' + controller.clinic_name;
        }

        str += '&pregnant=' + controller.pregnant;
        str += '&handicapped=' + controller.handicapped;
        str += '&children=' + controller.children;
        str += '&day_and_night=' + controller.day_and_night;

        if (controller.sort_by) {
            str = str + '&sort_by=' + controller.sort_by;
        }

        str = '?' + str.substring(1, str.length);
        return str;
    };

    this.loadPurposeOfVisitBlock = function (value) {
        Ajax.Post('/ajax/getPurposesOfVisitBySpecialtyId', {specialty_id:controller.specialty_id}, function (data) {
            if (data.status == 0) {
                $('#purpose_of_visit_block').html(data.result);
                if (!value)
                    controller.purpose_of_visit_id = 0;
                else
                    $('select[name="purpose_id"]').val(value);

                //$('select[name="purpose_id"]').styler();
            }
        });
    };

    this.loadNextPage = function () {
        controller.page = controller.page + 1;
        controller.sendRequest(false);
    };



};
var ClinicSearchPageController = function (landing, already_registred_account, url_page) {
    var self = this;

    this.landing_page = landing;
    this.url_page = url_page;
    this.already_registred_account = already_registred_account;
    this.city_id = null;

    this.form_controller = null;

    this.init = function () {

        var city_width = $('.map-city a').width();
        if (city_width>120){
            $('.map-box .search-box input.txt').css('width',465-city_width);
            $('.map-city').css('left',505-city_width);
        }
        else {
            $('.map-box .search-box input.txt').css('width',475-city_width);
            $('.map-city').css('left',515-city_width);
        }

        self.form_controller = new ClinicSearchFormController(landing, already_registred_account, url_page);
        self.form_controller.setPageMode();
        self.form_controller.setCityId(self.city_id);
        self.form_controller.init();
    };

    this.setCityId = function(city_id){
        self.city_id = city_id;

        if (self.form_controller != null)
        {
            self.form_controller.setCityId(city_id);
        }
    };
};
var ConfirmEmailController = function (email) {

    this.init = function () {

        /*отправка подтверждения регистрации на email*/
        $('#sendEmailConfirmation').click(function () {
            Ajax.Post('/account/sendEmailConfirmationMessage',
                {email:email},
                function (data) {
                    if (data.status == 0) {
                        showOk('Подтверждение выслано на ' + email);
                    } else {
                        showError('Ошибка при отправке email.');
                    }
                }
            );
        });
    };
};
var ConfirmPhoneController = function () {

    var self = this;
    var phone_id = null;

    var popup = null;

    var success_callback = null;

    this.setPhoneId = function(phone_id)
    {
        self.phone_id = phone_id;
    };

    this.tmp_init = function(){
        self.sendConfirmCode();
        self.showPopup();
    };

    this.setSuccessCallback = function(callback){
        self.success_callback = callback;
    };

    this.showPopup = function(){
        var html_code = '<div id="confirm-phone-popup9" class="phone-confirm-popup popup" style="display: block;">';
        //var html_code = '';
        html_code += '<img class="logo" src="/media/images/main_logo.png" alt="">';
        html_code += '<p class="intro">Напиши код, который пришел по смс</p>';
        html_code += '<div class="form forgot-form">';
        html_code += '<div class="row flo">';
        html_code += '<div class="txt">';
        html_code += '<input type="text" id="confirm_code' + self.phone_id +'" name="confirm_code9" placeholder="код" data-rule-required="true" data-msg-required="Введите код" data-msg-email="Некорректный код">';
        html_code += '</div>';
        html_code += '</div>';
        html_code += '<div class="btns">';
        html_code += '<input data-phone-id="9" name="submit-confirm-code" type="submit" value="Подтвердить" class="btn-1 submit-button">';
        html_code += '</div>';
        html_code += '</div>';
        html_code += '</div>';

        var html = $(html_code);

        html.find('input[name="submit-confirm-code"]').click(function(){
            self.sendRequest();
        });
        var popup = new Popup();
        popup.show(html);

        self.popup = popup;

   };

    this.sendConfirmCode = function(){
        var data = {
            phone_id: self.phone_id
        }
        Ajax.Post('/ajax/sendConfirmedCode', data, function (data) {

        });
    };



    this.sendRequest = function () {
        var confirm_code = $('#confirm_code' + self.phone_id).val();

        var data = {
            phone_id: self.phone_id,
            confirm_code: confirm_code
        };

        Ajax.Post('/account/ajaxConfirmPhoneCode', data, function (data) {
            if (data.status == 0) {
                $('#not_confirmed_block'+self.phone_id).addClass('item-approved');

                self.popup.close();

                if (self.success_callback)
                {
                    self.success_callback();
                }
            } else {
                showErrorLabel('Введен неверный код', $('#confirm_code' + self.phone_id));
            }
        });
    };
};
var DiseasePageController = function (id, is_login, already_registred_account, first_tab, label_for_counters) {
    this.disease_id = id;
    this.specialty_id = null;
    var controller1 = this;
    this.already_registred_account = already_registred_account;
    this.url_page = null;
    this.specialty_text = null;
    this.is_login = is_login;
    this.label_for_counters = label_for_counters;
    var current_top = null;
    var self = this;

    this.tab_name = first_tab;
    this.recording = 0;
    this.sections_coordinates = [];

    this.highlight_tab_block_flag = false;
    this.highlight_tab_block_timer = null;


    this.init = function () {
        // вещаем обработчики на кнопки

        this.checkTabsInUrl();

        $('.tab-female').click(function () {
            $('.sub-nav').hide();
            $('.sub-nav-female').show();
        });
        $('.tab-male').click(function () {
            $('.sub-nav').hide();
            $('.sub-nav-male').show();
        });
        $('.tab-children').click(function () {
            $('.sub-nav').hide();
            $('.sub-nav-children').show();
        });
        $('.tab-pregnant').click(function () {
            $('.sub-nav').hide();
            $('.sub-nav-pregnant').show();
        });
        $('.tab-adult').click(function () {
            $('.sub-nav').hide();
            $('.sub-nav-adult').show();
        });
        $('.tab-newborn').click(function () {
            $('.sub-nav').hide();
            $('.sub-nav-newborn').show();
        });

        $("#tabs .nav li a").click(function () {
            var offset = $('.illness-description').outerHeight() + 220;
            $('html, body').animate({scrollTop: offset}, 'slow');
            current_top = $(window).scrollTop() + $('.illness-nav').height();

        });


        $('.nav .tab-people a').click(function (event) {
            var url = $(this).attr('href');

            var data = {
                disease_id: self.disease_id,
                disease_card: $(this).attr('id')
            };

            var people_id = $(this).attr("id");

            $('.sub-nav').hide();
            $('.sub-nav-' + data.disease_card).show();
            Ajax.Post('/disease/ajaxGetDiseaseCardContent', data, function (data) {
                pushHistory(url);
                $('.read').html(data);
                self.getSectionsCoordinates();
                self.setCarouselTabByTabId(people_id);
            }, false);

            var tab_name = $(this).attr('id');
            controller1.tab_name = $(this).attr('id');
            self.changeSpecialtyBlock(tab_name);

            if ($(this).hasClass('ui-state-active')) {
            }
            else {
                $('.nav').find('.ui-state-active').removeClass('ui-state-active');
                $(this).parent().addClass('ui-state-active');
            }

            return false;
        });


        $('.section ul').addClass('list');
        $('.section ul li ul').removeClass('list');
        $('.section ul li ul').addClass('list-2');

        $('.illness-description .like_p ul').addClass('list-description');
        $('.illness-description .like_p ul li ul').removeClass('list-description');
        $('.illness-description .like_p ul li ul').addClass('list-description-2');

        var offset = $('.illness-description').outerHeight() + 220;
        $('.illness-nav').attr('data-offset-top', offset);

        self.getUnderstandBlock();

        $(document).on('click', '.btn-bookmark-illness', function () {
            self.getBookmarkBlock();
        });

        $(document).on('click', '.btn', function () {
            if ($(this).text() == 'Да')
                self.opinion = 1;
            else
                self.opinion = 0;
            Ajax.Post('/disease/ajaxAddUnderstandOpinion', {disease_id: self.disease_id, opinion: self.opinion}, function (data) {
                if (data.result)
                    $('.info-buttons').html(data.result.understand_text);
            });
        });

        if (self.is_login == 1) {
            $(document).on('click', '.btn-find-doctor, .disease-doctor', function (e) {
                e.preventDefault();
                self.url_page = $(this).data('url');
                self.specialty_text = $('.visible-specialty').data('text');
                var action_for_counters = $(this).data('action-for-counters');

                if ($(this).hasClass('btn-appoint')) self.recording = 1;
                if (self.already_registred_account == 1) {
                    var landing_login_page = new LandingLoginPageController(self.url_page, self.specialty_text, self.recording, action_for_counters, self.label_for_counters);
                    landing_login_page.init();
                } else {
                    setCounters('reg-begin', action_for_counters, self.label_for_counters, 'guest');
                    var landing_registration_page_controller = new LandingRegistrationPageController(self.url_page, self.specialty_text, self.recording, action_for_counters, self.label_for_counters);
                    landing_registration_page_controller.init();
                }
            });

            $(document).on('click', '#our-doctors .name a, #our-doctors .descr .btns .btn-appoint, #our-doctors .descr .btns .btn-bookmark, #our-doctors .record-day-pick, #our-doctors .name-inf a, #our-doctors .avatar a, #our-doctors .showTip, #our-doctors .tooltip a, #our-doctors .view-more', function (e) {
                e.preventDefault();
                self.url_page = $(this).data('url');
                self.specialty_text = $('.visible-specialty').data('text');
                var action_for_counters = 'doctor';

                if ($(this).hasClass('btn-appoint')) self.recording = 1;
                if (self.already_registred_account == 1) {
                    var landing_login_page = new LandingLoginPageController(self.url_page, self.specialty_text, self.recording, action_for_counters, self.label_for_counters);
                    landing_login_page.init();
                } else {
                    setCounters('reg-begin', action_for_counters, self.label_for_counters, 'guest');
                    var landing_registration_page_controller = new LandingRegistrationPageController(self.url_page, self.specialty_text, self.recording, action_for_counters, self.label_for_counters);
                    landing_registration_page_controller.init();
                }
            });
        }

        $('.we-good .btn-reg').click(function () {
            var landing_registration_page_controller = new LandingRegistrationPageController(self.url_page, 'врачи');
            landing_registration_page_controller.action_for_counters = 'disease-right-reg';
            landing_registration_page_controller.block_title = self.block_title;
            landing_registration_page_controller.init();
        });

        $(window).scroll(function (e) {
            if ($('#cards-wrap').offset()) {
                var topBl = $('#cards-wrap').offset().top - $(window).scrollTop(),
                    fixHei = $('.illness-nav').height();
                if (topBl <= fixHei + 45) {
                    $('.illness-nav, .doing-box').fadeOut();
                } else {
                    $('.illness-nav').fadeIn();
                    $('.disease-doctor').removeClass('visible-specialty');
                    if (controller1.tab_name == 'male' || controller1.tab_name == 'female') {
                        $('.doing-box.adult-block').fadeIn();
                        $('.doing-box.adult-block').fadeIn();
                    }
                    $('.doing-box.' + controller1.tab_name + '-block .disease-doctor').addClass('visible-specialty');
                    $('.doing-box.' + controller1.tab_name + '-block').fadeIn();
                }
                var fixHei2 = $('.we-good').height();
                if (topBl <= fixHei2 + fixHei + 90) {
                    $('.we-good').fadeOut();
                } else {
                    $('.we-good').fadeIn();
                }
            }
        });

        self.getDoctorCards();

        self.getSectionsCoordinates();

        $('.sub-nav ul a').click(function () {
            if (self.highlight_tab_block_timer)
                clearTimeout(self.highlight_tab_block_timer);

            self.highlight_tab_block_flag = true;

            $('.sub-nav ul a').removeClass('active')
            $(this).addClass('active')

            $('.sub-nav ul li').removeClass('active')
            $(this).parent().addClass('active')

            var w = $(this).attr('data-t');
            $('html, body').animate({
                scrollTop: ($('#' + w).offset().top - 100)
            }, 1000);

            self.highlight_tab_block_timer = setTimeout(function () {
                self.highlight_tab_block_flag = false;
            }, 1300);

        });
        $(window).scroll(function () {
            if(self.highlight_tab_block_flag)
                return;

            current_top = $(window).scrollTop() + $('.illness-nav').height();

            var prev = null;

            for (var i in self.sections_coordinates) {

                if (prev != null) {

                    if ((prev.top < current_top)
                        && (self.sections_coordinates[i].top > current_top)) {
                        $('.sub-nav ul a').removeClass('active')
                        $('.sub-nav ul li').removeClass('active')
                        $('.sub-nav ul a[data-t="' + prev.id + '"]').addClass('active');
                        $('.sub-nav ul a[data-t="' + prev.id + '"]').parent().addClass('active');

                        break;
                    }
                    if ((prev.top < current_top)
                        ) {
                        $('.sub-nav ul a').removeClass('active')
                        $('.sub-nav ul li').removeClass('active')
                        $('.sub-nav ul a[data-t="' + self.sections_coordinates[i].id + '"]').addClass('active');
                        $('.sub-nav ul a[data-t="' + self.sections_coordinates[i].id + '"]').parent().addClass('active');
                    }

                }

                prev = self.sections_coordinates[i];
            }
        });

    };

    this.getUnderstandBlock = function () {
        Ajax.Post('/disease/ajaxGetUnderstandBlock', {disease_id: controller1.disease_id}, function (data) {
            if (data.result) {
                $('.info-buttons').html(data.result.understand_text);
            }
        });
    };

    this.getBookmarkBlock = function () {
        Ajax.Post('/disease/ajaxAddToMyDiseaseList', {disease_id: controller1.disease_id}, function (data) {
            var isItAddOrKick = data.result.my_disease;

            if (isItAddOrKick) {
                $('.btn-bookmark-illness').addClass("btn-bookmark-added");
                $('.btn-bookmark-illness').html('<i class="icon-add"></i> <span class="txt txt-added">В закладках</span>');
            }
            else {
                $('.btn-bookmark-illness').removeClass("btn-bookmark-added");
                $('.btn-bookmark-illness').html('<i class="icon-add"></i> <span class="txt">Добавить в закладки</span>');
            }
        });
    };

    this.changeSpecialtyBlock = function (tab_name) {
        $('.doing-box').hide();
        $('.disease-doctor').removeClass('visible-specialty');
        if (tab_name == 'male' || tab_name == 'female') {
            $('.doing-box.adult-block').show();
            $('.doing-box.adult-block').show();
        }
        $('.doing-box.' + tab_name + '-block .disease-doctor').addClass('visible-specialty');
        $('.doing-box.' + tab_name + '-block').show();

        controller1.getDoctorCards();
    };

    this.getDoctorCards = function () {

        controller1.specialty_id = $('.visible-specialty').data('id');

        if (controller1.specialty_id) {
            var data = {
                specialty_id: controller1.specialty_id,
                by_page: 2,
                //primary_doctors_ids: controller1.primary_doctors_ids,
                landing: 1,
                disease_doctor: true
            };

            Ajax.Post('/ajax/getDiseaseDoctors', data, function (data) {
                if (data.status == 0) {
                    $('.view-more').remove();
                    if (data.result.any_search) {
                        $('#our-doctors').html(data.result.html);
                        var specializationDiseasesArea = $('#our-doctors .specializationDiseasesArea'),
                            link = '<a class="load-next-page view-more" data-id="' + controller1.specialty_id + '" href="/doctor/search?specialty_id=' + controller1.specialty_id + '&time_of_visit=any&sort_by=recomend" data-url="/doctor/search?specialty_id=' + controller1.specialty_id + '&time_of_visit=any&sort_by=recomend"><i></i>Перейти на страницу поиска врачей</a>';
                        if(specializationDiseasesArea.hasClass('specializationDiseasesArea')) {
                            specializationDiseasesArea.before(link);
                        } else {
                            $('#our-doctors').append(link);
                        }
                    }
                    else $('#our-doctors').html('');
                }
            });
        }
        else
            $('.view-more').remove();
    };

    this.getSectionsCoordinates = function () {

        self.sections_coordinates = [];
        $('.read .section').each(function () {
            var id = $(this).attr('id');
            var of = $(this).offset().top;
            self.sections_coordinates.push({id: id, top: of});
        });

        self.sections_coordinates.sort(function (a, b) {
            return a.top - b.top;
        });
    };

    this.checkTabsInUrl = function () {
        var url = location.href;
        var tabs = ['/male', 'female', 'adult', 'newborn', 'pregnant', 'children'];
        var tab = false;
        var i = 0;

        while (i < tabs.length && !tab) {

             if(url.match(new RegExp(tabs[i]))){
                 if(tabs[i]=='/male')
                    tabs[i]='male';
                 $('.tab-people').removeClass('ui-state-active');
                 $('#'+tabs[i]).parent().addClass('ui-state-active');
                 tab = true;
                 $('.sub-nav').hide();
                 $('.sub-nav-'+tabs[i]).show();
             }
            i++;
        }

        if (!tab) {
            tab = true;
            var curl = location.href + '/' + $('.tab-people:first a').attr('id');
            pushHistory(curl);
        }
    };

    this.setCarouselTabByTabId = function(id){

            $('.sub-nav-' + id + '.carousel ul').carouFredSel({
                auto: false,
                prev: '.prev',
                next: '.next',
                scroll: {items: 1},
                circular: false,
                infinite: false
            });
    };


}
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

        self.input_element.keyup(function () {
            self.updateDropDownList();
        });

        self.submit_element.click(function(){
            var text = self.input_element.val();

            if (text.length > 0)
            {
                window.location.href = '/disease/searchResults?query=' + encodeURIComponent(text);
            }
        });

        if (self.is_login == 1)
        {
            $('.inner.flo .navigation_link').click(function (e) {
                if (!$(this).hasClass('disease-link')) e.preventDefault();
                self.url_page = $(this).data('url');
                self.specialty_text = 'врачи';
                var action_for_counters = $(this).data('action-for-counters');

                if ($(this).hasClass('btn-enter')) {
                    var landing_login_page = new LandingLoginPageController(self.url_page,self.specialty_text);
                    landing_login_page.init();
                } else if (self.already_registred_account != 1 || $(this).hasClass('btn-reg')) {
                    var landing_registration_page_controller = new LandingRegistrationPageController(self.url_page,self.specialty_text, action_for_counters, self.label_for_counters);
                    landing_registration_page_controller.init();
                } else if (self.already_registred_account == 1) {
                    var landing_login_page = new LandingLoginPageController(self.url_page,self.specialty_text);
                    landing_login_page.init();
                }
            });

            $('.inner.flo .reg-linking').click(function(){

                self.url_page = $(this).data('url');
                self.specialty_text = 'врачи';
                var action_for_counters = 'top-reg';
                setCounters('reg-begin', action_for_counters, self.label_for_counters, 'guest');

                if ($(this).hasClass('btn-enter')) {
                    var landing_login_page = new LandingLoginPageController(self.url_page,self.specialty_text);
                    landing_login_page.init();
                } else {
                    var landing_registration_page_controller = new LandingRegistrationPageController(self.url_page,self.specialty_text, action_for_counters, self.label_for_counters);
                    landing_registration_page_controller.init();
                }

            });

            $('.reg-linking-btn-404').click(function(e){
                e.preventDefault();
                self.url_page = $(this).data('url');
                self.specialty_text = 'врачи';
                var action_for_counters = $(this).data('action-for-counters');
                setCounters('reg-begin', action_for_counters, self.label_for_counters, 'guest');

                if (self.already_registred_account == 1) {
                    var landing_login_page = new LandingLoginPageController(self.url_page,self.specialty_text);
                    landing_login_page.init();
                } else {
                    var landing_registration_page_controller = new LandingRegistrationPageController(self.url_page,self.specialty_text, action_for_counters, self.label_for_counters);
                    landing_registration_page_controller.init();
                }

            });
        }

    };

    this.updateDropDownList = function () {
        var self = this;

        var text = this.input_element.val();
        if (text.length > 2) {
            Ajax.Post('/ajax/getDiseases', {query:text}, function (data) {
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
var
    DiseaseSearchPageController = function () {

    var self = this;

    this.form_controller = null;

    this.init = function () {

        self.form_controller = new DiseaseQuickSearchFormController(0,0);

        self.form_controller.input_element = $('.search-block .txt');
        self.form_controller.drop_down_container = $('.search-block .drop-menu');
        self.form_controller.submit_element = $('.search-block .btn-1');
        self.form_controller.init();
    };
};
var DiseaseSearchResultsPageController = function () {

    this.disease_query = '';
    this.page = 1;
    this.by_page = 10;

    var controller = this;

    this.init = function () {
        $(document).on('click', '.view-more', function () {
            $('.view-more i').addClass('icon-loader');
            controller.loadNextPage();
        });

        var disease_search_controller2 = new DiseaseQuickSearchFormController(0,0);

        disease_search_controller2.setInputElement($('.search-block .illness-search-input'));
        disease_search_controller2.setDrowDownContainer($('.search-block .drop-menu'));
        disease_search_controller2.setSubmitElement($('.search-block .illness-search-submit'));
        disease_search_controller2.init();
    };

    this.sendRequest = function () {
        Ajax.Post('/disease/moreSearchResults', {disease_query:controller.disease_query, page:controller.page}, function (data) {
            if (data.result.diseases){
                $('.view-more').remove();
                $('.illness-results-list').append(data.result.diseases);
            }
            if (data.result.next_page_button){
                $('.ilness-result').append(data.result.next_page_button);
            }
        });
    };

    this.loadNextPage = function () {
        controller.page = controller.page + 1;
        controller.disease_query = getParameterByName('disease_query');
        controller.sendRequest();
    };
};
var DoctorBigCardController = function (container, doctor_id) {

    var self = this;
    this.doctor_id = doctor_id;
    this.container = container;

    this.record_controller = null;

    this.init = function () {

        $(self.container + ' .record-day-pick').click(function(){
            self.record_controller = new RecordToTheDoctorBlockController(self.doctor_id, $(this));
            self.record_controller.init();
        });

    };


}
var DoctorBigCardButtonsController = function (doctor_id) {

    var self = this;
    this.doctor_id = doctor_id;

    this.init = function () {
        if (self.doctor_id) {
            self.getBookmarkBlock();
        }

    };

    this.getBookmarkBlock = function () {
        Ajax.Post('/doctor/ajaxAddToMyDoctorList', {doctor_id: self.doctor_id}, function (data) {
            if (data.status == 0) {
                var isItAddOrKick = data.result.my_doctor;

                if (isItAddOrKick) {
                    $('.doctor_bookmark' + self.doctor_id).addClass("btn-bookmark-added");
                    $('.doctor_bookmark' + self.doctor_id).html('<i class="icon-add"></i> <span class="txt txt-added">В закладках</span>');
                } else {
                    $('.doctor_bookmark' + self.doctor_id).removeClass('btn-bookmark-added');
                    $('.doctor_bookmark' + self.doctor_id).html('<i class="icon-add"></i> <span class="txt">Добавить в закладки</span>');
                }
            }
        });
    };
}
var DoctorPageController = function (id, landing, already_registred_account, url_page, recording) {
    var self = this;

    this.doctor_id = id;
    this.schedule_id = 0;
    this.name = '';
    this.phone = '';
    this.family_relation_status_id = 0;
    this.page = 1;
    this.landing_page = landing;
    this.url_page = url_page;
    this.already_registred_account = already_registred_account;
    this.recording = recording;

    this.record_block = null;
    this.init = function () {

        if (self.landing_page == 1) {

            if (self.already_registred_account == 1) {
                landing_login_page = new LandingLoginPageController(self.url_page,'врачи');
                landing_login_page.init();
            } else {
                landing_registration_page_controller = new LandingRegistrationPageController(self.url_page,'врачи');
                landing_registration_page_controller.init();
            }
        }

        if (self.recording) {
            self.record_controller = new RecordToTheDoctorBlockController(self.doctor_id, $('.btn-appoint'));
            self.record_controller.init();
        }

        $('.record-day-pick').click(function () {
            self.record_controller = new RecordToTheDoctorBlockController(self.doctor_id, $(this));
            self.record_controller.init();
        });

        $('.btn-appoint').click(function () {
            //if (self.record_controller == null){
            self.record_controller = new RecordToTheDoctorBlockController(self.doctor_id, $(this));
            self.record_controller.init();
            //}
        });


         /*$('#visit-order-button').click(function () {
         //if (self.record_block == null) {
         self.record_block = new RecordPhonesBlockController(self.doctor_id, $('#visit-order-button'));
         self.record_block.init();
         //}
         });*/

        $(document).on('click', '.btn-bookmark', function () {
            Ajax.Post('/doctor/ajaxAddToMyDoctorList', {doctor_id: self.doctor_id}, function (data) {
                var isItAddOrKick = data.result.my_doctor;

                if (isItAddOrKick) {
                    $('.btn-bookmark').addClass("btn-bookmark-added");
                    $('.btn-bookmark').html('<i class="icon-add"></i> <span class="txt txt-added">В закладках</span>');
                }
                else {
                    $('.btn-bookmark').removeClass("btn-bookmark-added");
                    $('.btn-bookmark').html('<i class="icon-add"></i> <span class="txt">Добавить в закладки</span>');
                }
            });
        });

        $('.location-box .tabs').each(function () {
            $(this).find('li').each(function (i) {
                $(this).click(function () {
                    $('.day').removeClass('active');
                    var id = $(this).data('id');

                    $(this).addClass('active').siblings().removeClass('active')
                        .parents('.location-box').find('.section').eq(i).fadeIn(150).siblings('.section').hide();
                    $('.time-clinic-' + id).addClass('active');
                });
            });
        });

        $('.section.visible.flo').next('div.section.visible.flo').css('display', 'none');

        $(document).on('click', '.view-more', function () {
            $('.view-more i').addClass('icon-loader');
            self.page++;
            self.getReviews();
        });

        //$('.connected-carousels .next-navigation').removeClass('inactive');
    };

    this.getReviews = function () {
        Ajax.Post('/doctor/ajaxGetReviewsList', {doctor_id: self.doctor_id, page: self.page}, function (data) {

            if (data.status == 0) {
                $('#review-container').append(data.result.html);
                $('.view-more i').removeClass('icon-loader');

                if (data.result.count < 10) {
                    $('#view_more_button').css('display', 'none');
                }
            }
        });
    };
}
var DoctorPageOldController = function (id, standart_name) {
    this.doctor_id = id;

    this.schedule_id = 0;
    this.week_counter = 0;
    this.standart_name = standart_name;
    this.name = '';
    this.phone = '';
    this.sms_code = 0;
    this.purpose_id = 0;
    this.family_relation_status_id = 0;

    this.init = function () {
        // вещаем обработчики на кнопки
        var controller = this;

        controller.getScheduleTimes();
        controller.getVisitPrice();

        $('#my-doctor-button').click(function () {
            Ajax.Post('/doctor/ajaxAddToMyDoctorList', {doctor_id:controller.doctor_id}, function (data) {
                var isItAddOrKick = data.result.my_doctor;

                if (isItAddOrKick)
                    $('#my-doctor-button').html('Убрать из моего списка врачей')
                else
                    $('#my-doctor-button').html('В мой список врачей');
            });
        });

        $('.visit-order').hide();
        $('.visit-order-2').hide();
        $('.visit-order-3').hide();
        $('.not-account-name').hide();
        $('.visit-order-single-day').hide();

        $(document).on('click', '#visit-order-button', function () {
            $('.visit-order').show();
            $('.visit-order-single-day').hide();
            $('.visit-order-2').hide();
            $('.visit-order-3').hide();
        });

        $(document).on('click', '#confirm-visit', function () {
            if ($('#doctor-purposes').val() == 0)
                alert('Выберите цель визита!');
            else if (controller.schedule_id == 0)
                alert('Выберите день визита!');
            else {
                controller.purpose_id = $('#doctor-purposes').val();
                $('.visit-order-2').show();
            }
        });

        $(document).on('click', '#confirm-visit-2', function () {
            if ($('.patient-name').val() == '') alert('Заполните поле ФИО!');
            else if ($('.patient-name').val() != controller.standart_name && $('.family-relation').val() == 0) alert('Укажите кто идет на прием!');
            else if ($('.phone-number').val() == '') alert('Укажите телефон!');
            else {
                controller.name = $('.patient-name').val();
                if ($('.family-relation option:selected').text() == 'Я') controller.saveAccountFullName();

                controller.family_relation_status_id = $('.family-relation').attr('value');
                if ($('.family-relation').attr('value') == undefined) controller.family_relation_status_id = 0;

                controller.phone = $('.phone-number').val();
                controller.sms_code = Math.round(Math.random() * 100);
                alert('Ваш sms-код: ' + controller.sms_code);
                Ajax.Post('/doctor/ajaxSetPhoneNumber', {phone:controller.phone, code:controller.sms_code}, function (data) {
                    if (data.result.is_code_saved) {
                        $('.visit-order-3').show();
                    }
                });

            }
        });

        $(document).on('click', '#confirm-visit-single-day', function () {
            if ($('#doctor-purposes-single-day').val() == 0) alert('Выберите цель визита!')
            else if ($('.doctor-single-time').val() == 0) alert('Выберите время визита!')
            else {
                controller.purpose_id = $('#doctor-purposes-single-day').val();
                controller.schedule_id = $('.doctor-single-time').val();
                $('.visit-order-2').show();
            }
        });


        $(document).on('change', $('#yes-checkbox'), function () {
            if ($('#yes-checkbox').prop('checked') == true)
                $('#confirm-visit-2').attr('disabled', false);
            else
                $('#confirm-visit-2').attr('disabled', true);
        });

        $('#confirm-visit-3').click(function () {
            controller.sms_code = $('#sms-code').val();

            Ajax.Post('/doctor/ajaxSaveVisitData', {schedule_id:controller.schedule_id, doctor_id:controller.doctor_id, purpose_id:controller.purpose_id, name:controller.name, phone:controller.phone, code:controller.sms_code, family_relation_status_id:controller.family_relation_status_id}, function (data) {
                if (data.result.is_data_saved) {
                    alert('Вы записаны на прием!');
                    $('.visit-order').hide();
                    $('.visit-order-2').hide();
                    $('.visit-order-3').hide();
                    $('.visit-order-single-day').hide();
                    controller.schedule_id = 0;
                    controller.week_counter = 0;
                    controller.name = '';
                    controller.phone = '';
                    controller.sms_code = 0;
                    controller.purpose_id = 0;
                    controller.family_relation_status_id = 0;
                    controller.getVisitPrice();
                    controller.getScheduleTimes();
                }
                else {
                    alert('Неправильно указан код подтверждения!');
                }
            });
        });

        $(document).on('click', '.time', function () {
            alert('id выбранного расписания: ' + $(this).attr("id"));
            controller.schedule_id = $(this).attr("id");
        });

        $(document).on('click', '#doctor-week-next', function () {
            controller.week_counter++;
            controller.getScheduleTimes();
        });

        $(document).on('click', '#doctor-week-previous', function () {
            if (controller.week_counter > 0) {
                controller.week_counter--;
                controller.getScheduleTimes();
            }
            else {
                alert('Расписание прошедших недель недоступно!');
            }
        });

        $(document).on('click', '.schedule-time', function () {
            $('.visit-order-single-day').show();
            $('.visit-order').hide();
            $('.visit-order-2').hide();
            $('.visit-order-3').hide();
            //alert('id выбранного расписания: ' + $(this).attr("id"));
            controller.schedule_id = $(this).attr("id");
            Ajax.Post('/doctor/ajaxGetScheduleSingleDay', {schedule_id:controller.schedule_id}, function (data) {
                if (data.result) {
                    var doctor_day = data.result.doctor_day;
                    if (doctor_day) $('.doctor-day').html(doctor_day);
                }
            });
        });

        $(document).on('focusout', '.patient-name', function () {
            var name = $('.patient-name').val();
            if (name != controller.standart_name) {
                $('.family-relation option:first').removeAttr('checked', 'checked');
                $('.not-account-name').show();
            }
            else {
                $('.not-account-name').hide();
                $('.family-relation option:first').attr('checked', 'checked');
            }

        });

    };


    this.getScheduleTimes = function () {
        Ajax.Post('/doctor/ajaxGetScheduleTimes', {week_counter:this.week_counter, doctor_id:this.doctor_id}, function (data) {
            if (data.result.doctor_times)
                $('#doctor-times').html(data.result.doctor_times);
            else
                $('#doctor-times').empty();
        });
    };

    this.getVisitPrice = function () {
        Ajax.Post('/doctor/ajaxGetVisitPrice', {doctor_id:this.doctor_id}, function (data) {
            if (data.result.doctor_price) {
                $('.visit-price').html(data.result.doctor_price);
                $('.visit-price-single-day').html(data.result.doctor_price);
            }
        });
    };

    this.saveAccountFullName = function () {
        Ajax.Post('/doctor/ajaxSaveAccountFullName', {name:this.name}, function (data) {
        });
    };

}
var DoctorSearchFormController = function (landing, already_registred_account, url_page) {

    this.landing_page = landing;
    this.url_page = url_page;
    this.already_registred_account = already_registred_account;

    this.specialty_id = null;
    this.purpose_of_visit_id = null;

    this.doctor_name = null;
    this.doctor_sex_id = null;

    this.visit_type = null;
    this.doctor_type = null;
    this.morning_time = 0;
    this.evening_time = 0;
    this.weekend_time = 0;
    this.any_time = 0;

    this.latitude = null;
    this.longitude = null;

    this.is_metro = 0;
    this.metro_station_name = null;
    this.metro_branch_name = null;

    this.page = 1;
    this.by_page = 10;

    this.primary_doctors_ids = [];

    this.sort_by = 'recomend';

    this.map_controller = null;

    this.city_id = null;

    this.container = '#doctor-search-form';
    this.mode = 'block';

    this.doctor_name_search_flag = null;
    var self = this;

    this.setCityId = function(city_id){
        self.city_id = city_id;

        if (self.map_controller != null) {
            self.map_controller.setCityId(self.city_id);
        }
    };

    this.init = function () {
        self.attachEvents();
        
        if (this.mode == 'page') {
            city_controller.subscribe(self.setCityInfo);
            city_controller.subscribe(self.changeSpecialtiesListToSearchDoctors);
            self.city_id = city_controller.city_id;
        };

        if (this.mode == 'page') {
            self.initParamsFromUrl();
            self.sendRequest(false);

            self.map_controller = new YandexMapController(self);
            self.map_controller.setDataUrl('/ajax/getDoctorClinicCard?big=0&id=');
            self.map_controller.init();
        }
        ;

        if (self.landing_page == 1) {

            if (self.already_registred_account == 1) {
                landing_login_page = new LandingLoginPageController(self.url_page,'врачи');
                landing_login_page.init();
            } else {
                landing_registration_page_controller = new LandingRegistrationPageController(self.url_page,'врачи');
                landing_registration_page_controller.init();
            }
        };

        $(document).on('click', '.h1_colapse', function () {
            $(this).toggleClass('active');

            if ($(this).hasClass('active')) {
                self.removeColapse();
            } else {
                self.addColapse();
            };
        });

        $(document).on('click', '.colapse', function () {
            $(this).toggleClass('active');

            if ($(this).hasClass('active')) {
                self.removeColapse();
            } else {
                self.addColapse();
            };
        });

        $('#doctor-find-txt').hide();
        $('.doctor_search_options').show();

        $('#doctor-find-txt a').click(function(){
            $('#doctor-find-txt').hide();
            $('.doctor_search_options').show();
        });

        $('#doctor-search-form input[name="doctor_name"]').keyup(function(e){
            e = e || window.event;
            if(e.keyCode == 13){

                $('#doctor-search-form input[type="submit"]').click();
            }
        });
    };

    this.setPageMode = function () {
        this.mode = 'page';
    }

    this.setCityInfo = function(city_info){
        self.setCityId(city_info.city_id);
    };

    this.setBlockMode = function () {
        this.mode = 'block';
    }

    this.initParamsFromUrl = function () {
        self.from_url = 1;
        self.page = 1;
        self.specialty_id = parseInt(getParameterByName('specialty_id', 0));
        self.purpose_of_visit_id = parseInt(getParameterByName('purpose_of_visit_id', 0));
        self.time_of_visit = getParameterByName('time_of_visit', 'any');
        self.doctor_name = getParameterByName('doctor_name');
        self.doctor_sex_id = parseInt(getParameterByName('doctor_sex_id', 0));

        if(self.doctor_name || self.doctor_sex_id)
        {
            $('#doctor-find-txt').hide();
            $('.doctor_search_options').show();
        }

        self.sort_by = getParameterByName('sort_by', 'recomend');
        self.evening_time = getParameterByName('morning_time', 0);
        self.weekend_time = getParameterByName('weekend_time', 0);
        self.morning_time = getParameterByName('morning_time', 0);
        self.any_time = getParameterByName('any_time', 1);
        self.visit_type = getParameterByName('visit_type', 'clinic');
        self.doctor_type = getParameterByName('doctor_type', 'adult');
        self.initElements();
    };

    this.initElements = function () {
        if (self.specialty_id) {
            setCustomSelect('select[name="specialty_id"]', self.specialty_id);
            self.loadPurposeOfVisitBlock(self.purpose_of_visit_id, false);
        }

        if (self.doctor_name)
            $('input[name="doctor_name"]').val(self.doctor_name);

        if (self.doctor_sex_id) {
            $('.sex').removeClass('selected');
            $('.sex-' + self.doctor_sex_id).addClass('selected');
        }

        if (self.sort_by)
            $('.sortby[data-type="' + self.sort_by + '"]').parent().addClass('current');

        if (self.visit_type) {

            $('.visit-type').removeClass('act');

            if (self.visit_type == 'clinic') {
                $('.visit-type-clinic').addClass('act');
            }

            if (self.visit_type == 'home') {
                $('.visit-type-home').addClass('act');
            }
        }

        if (self.doctor_type) {

            $('.doctor-type').removeClass('act');

            if (self.doctor_type == 'adult') {
                $('.doctor-type-adult').addClass('act');
            }

            if (self.doctor_type == 'children') {
                $('.doctor-type-children').addClass('act');
            }

            if (self.doctor_type == 'pregnant') {
                $('.doctor-type-pregnant').addClass('act');
            }
        }

        if (self.any_time == 0)
            $('.time-all').removeClass('act');
        else
            $('.time-all').addClass('act');

        if (self.morning_time == 0)
            $('.time-morning').removeClass('act');
        else
            $('.time-morning').addClass('act');

        if (self.evening_time == 0)
            $('.time-evening').removeClass('act');
        else
            $('.time-evening').addClass('act');

        if (self.weekend_time == 0)
            $('.time-weekend').removeClass('act');
        else
            $('.time-weekend').addClass('act');
    };

    this.attachEvents = function () {

        $(this.container + ' select[name="specialty_id"]').change(function () {

            self.specialty_id = $(this).val();
            $('select[name="specialty_id"] option').removeAttr('selected');
            $('select[name="specialty_id"] option[value="' + self.specialty_id + '"]').attr('selected', 'selected');

            self.page = 1;
            self.loadPurposeOfVisitBlock();
        });

        $(this.container + ' input[type="submit"]').click(function () {
            self.page = 1;
            self.setParams();
            self.sendRequest();
        });

        if (self.mode == 'page') {
            $('.sort_by').click(function () {
                var type = $(this).data('type');

                if (type) {
                    self.page = 1;
                    self.sort_by = type;
                    self.sendRequest();
                }
            });

            $(".filter li").click(function (e) {
                e.preventDefault();
                $(".filter li").removeClass('current');
                $(this).addClass('current');
            });

            $(".filter .price-tab li").click(function (e) {
                e.preventDefault();
                $(".filter .price-tab li").removeClass('active');
                $(this).addClass('active');
            });

            $(".map-box .resize").click(function (e) {
                $("body").addClass('hidden');
                $("footer").hide();
            });

            $(".full-map .resize, .full-map .shell a").click(function (e) {
                e.preventDefault();
                $("body").removeClass('hidden');
                $("footer").show();
            });

            $(function () {
                $(window).resize(function () {
                    $('.full-map').height($(window).height());
                });
                $(window).resize();
            });

            $(document).on('click', '.load-next-page', function () {
                $('.view-more i').addClass('icon-loader');
                self.loadNextPage();
            });
        }
    };

    this.setParams = function () {
        self.specialty_id = $(self.container + ' select[name="specialty_id"] option[selected="selected"]').val();
        self.purpose_of_visit_id = $(self.container + ' select[name="purpose_of_visit_id"]').val();
        self.visit_type = $(self.container + ' .visit-type-clinic').hasClass('act') ? 'clinic' : 'home';
        self.any_time = $(self.container + ' .time-any').hasClass('act') ? 1 : 0;
        self.morning_time = $(self.container + ' .time-morning').hasClass('act') ? 1 : 0;
        self.evening_time = $(self.container + ' .time-evening').hasClass('act') ? 1 : 0;
        self.weekend_time = $(self.container + ' .time-weekend').hasClass('act') ? 1 : 0;

        man = $(self.container + ' span.man').hasClass('selected') ? 1 : 0;
        woman = $(self.container + ' span.woman').hasClass('selected') ? 1 : 0;

        if ($(self.container + ' .doctor-type').hasClass('act')) {
            if ($(self.container + ' .doctor-type-adult').hasClass('act')) self.doctor_type = 'adult';
            if ($(self.container + ' .doctor-type-children').hasClass('act')) self.doctor_type = 'children';
            if ($(self.container + ' .doctor-type-pregnant').hasClass('act')) self.doctor_type = 'pregnant';
        }

        if ((man && woman) || (!man && !woman)) {
            self.doctor_sex_id = 0;
        } else if (man) {
            self.doctor_sex_id = 1;
        } else if (woman) {
            self.doctor_sex_id = 2;
        }

        name = $(self.container + ' input[name="doctor_name"]').val();

        if (name && name != 'Введите имя врача')
            self.doctor_name = $(self.container + ' input[name="doctor_name"]').val();
        else
            self.doctor_name = null;
    };

    this.sendRequest = function (push_state) {
        if (this.mode == 'block') {
            self.setParams();
            var url = self.buildUrl();
            window.location.href = '/doctor/search/' + url;
            return;
        }

        // Если обычный решим

        if (push_state == undefined)
            push_state = true;

        if (push_state) {
            var state = {
                title: $('title').val(),
                url: self.buildUrl()
            }

            // заносим ссылку в историю
            history.pushState(state, state.title, state.url);
        }

        if (self.page == 1)
        {
            self.primary_doctors_ids = [];
        }

        /* если происходит поиск по имени врача*/
        if (self.doctor_name_search_flag == 1){
            $('#doctor-find-txt').hide();
            $('.doctor_search_options').show();
        }


        var data = {
            specialty_id:self.specialty_id,
            purpose_of_visit_id:self.purpose_of_visit_id,
            doctor_sex_id:self.doctor_sex_id,
            doctor_name:self.doctor_name,
            visit_type:self.visit_type,
            doctor_type:self.doctor_type,
            any_time:self.any_time,
            morning_time:self.morning_time,
            evening_time:self.evening_time,
            weekend_time:self.weekend_time,
            page:self.page,
            by_page:self.by_page,
            sort_by:self.sort_by,
            primary_doctors_ids:self.primary_doctors_ids,
            latitude:self.latitude,
            longitude:self.longitude,
            is_metro:self.is_metro,
            metro_station_name:self.metro_station_name,
            metro_branch_name:self.metro_branch_name,
            landing : 1,
            city_id : self.city_id
        };

        Ajax.Post('/doctor/ajaxSearch', data, function (data) {
            if (data.status == 0) {
                if (self.page == 1) {
                    if (data.result.full_search == false) {
                        if (data.result.is_empty_city == 0)
                            $('#our-doctors').html('<div class="error-plate">По Вашему запросу ничего не найдено. Возможно Вам подойдёт один из врачей в нашей базе</div>')
                        else{
                            //self.map_controller.setMapCenter(data.result.latitude, data.result.longitude);
                            $('#our-doctors').html('<div class="error-plate">У нас пока нет врачей в городе '+data.result.city_name+'. Мы сообщим, как только они появятся!</div>')
                        }
                        $('#our-doctors').append(data.result.html);
                    } else {
                        $('#our-doctors').html(data.result.html);
                        self.primary_doctors_ids = data.result.primary_doctors_ids;
                    }

                    if (self.map_controller != undefined) {
                        Ajax.Post('/ajax/getMapData', {hash: data.result.map}, function (data) {
                            self.map_controller.setData(data.result);
                        });
                    }
                }
                else {
                    $('.view-more').remove();
                    $('#our-doctors').append(data.result.html);
                }

                if (data.result.next_page) {
                    $('#our-doctors').append('<a class="load-next-page view-more" href="javascript:void(0);"><i></i>Показать ещё 10 врачей</a>');
                }
            } else if (data.status == 4) {
                window.location = '/';
            }
        });
    };

    this.buildUrl = function () {

        var str = '';

        if (self.specialty_id) {
            str = str + '&specialty_id=' + self.specialty_id;
        }

        if (self.purpose_of_visit_id) {
            str = str + '&purpose_of_visit_id=' + self.purpose_of_visit_id;
        }

        if (self.time_of_visit) {
            str = str + '&time_of_visit=' + self.time_of_visit;
        }

        if (self.doctor_name) {
            str = str + '&doctor_name=' + self.doctor_name;
        }

        if (self.doctor_sex_id) {
            str = str + '&doctor_sex_id=' + self.doctor_sex_id;
        }

        if (self.visit_type) {
            str = str + '&visit_type=' + self.visit_type;
        }

        if (self.doctor_type) {
            str = str + '&doctor_type=' + self.doctor_type;
        }

        str += '&weekend_time=' + self.weekend_time;
        str += '&evening_time=' + self.evening_time;
        str += '&morning_time=' + self.morning_time;
        str += '&any_time=' + self.any_time;

        str += '&latitude=' + self.latitude;
        str += '&longitude=' + self.longitude;

        str += '&metro_station_name=' + self.metro_station_name;
        str += '&metro_branch_name=' + self.metro_branch_name;

        if (self.sort_by) {
            str = str + '&sort_by=' + self.sort_by;
        }

        str = '?' + str.substring(1, str.length);
        return str;
    };

    this.loadPurposeOfVisitBlock = function (value, open_form) {

        if(open_form == undefined)
            open_form = true;
        Ajax.Post('/ajax/getPurposesOfVisitToDoctorsBySpecialtyId', {specialty_id: self.specialty_id}, function (data) {
            if (data.status == 0) {
                $('#purpose_of_visit_block').html(data.result);

                if (!value)
                    self.purpose_of_visit_id = 0;
                else {
                    $('select[name="purpose_of_visit_id"] option[value="' + value + '"]').attr('selected', 'selected');
                    $('select[name="purpose_of_visit_id"]').trigger('liszt:updated');
                }


                $(".chzn-select").chosen();
                $(".chzn-select-deselect").chosen({allow_single_deselect: true});

                if (open_form)
                    $('select[name="purpose_of_visit_id"]').trigger('liszt:open');
            }
        });
    };

    this.loadNextPage = function () {
        self.page = self.page + 1;
        self.sendRequest(false);
    };

    this.addColapse = function () {
        $('#box_h1').addClass('h1_colapse');
        $('.colapse').css('display', 'block');
        $('.in_colapse').css('display', 'none');

        $('.resize.old_resize').click(function(){
            doctor_form_controller = new DoctorSearchFormController();
            doctor_form_controller.removeColapse();
            $('#box_h1').removeClass('h1_colapse');
        });

        $('.resize.old_resize').next().click(function(){
            doctor_form_controller = new DoctorSearchFormController();
            doctor_form_controller.removeColapse();
            $('#box_h1').removeClass('h1_colapse');
        });
    };

    this.removeColapse = function () {
        $('.colapse').css('display', 'none');
        $('.in_colapse').css('display', 'block');
    };

    this.changeSpecialtiesListToSearchDoctors = function(city_info)
    {
        Ajax.Post('/ajax/changeSpecialtiesListToSearchDoctorsByCityId', {city_id : city_info.city_id}, function(data){
            if (data.status == 0)
            {
                $('#specialties_to_search_doctor').html(data.result.option);
                $('#specialties_to_search_doctor').trigger('liszt:updated');

                $('select[name="purpose_of_visit_id"]').html('<option value=""></option>');
                $('select[name="purpose_of_visit_id"]').trigger('liszt:updated');
            }
        });
    }
};
var DoctorSearchPageController = function (landing, already_registred_account, url_page) {
    var self = this;

    this.landing_page = landing;
    this.url_page = url_page;
    this.already_registred_account = already_registred_account;
    this.city_id = null;

    this.init = function () {

        var city_width = $('.map-city a').width();
        if (city_width>120){
            $('.map-box .search-box input.txt').css('width',465-city_width);
            $('.map-city').css('left',505-city_width);
        }
        else {
            $('.map-box .search-box input.txt').css('width',475-city_width);
            $('.map-city').css('left',515-city_width);
        }

        self.form_controller = new DoctorSearchFormController(landing, already_registred_account, url_page);
        self.form_controller.setPageMode();
        self.form_controller.init();
    };

    this.setCityId = function(city_id){
        self.city_id = city_id;

        if (self.form_controller != null)
        {
            self.form_controller.setCityId(city_id);
        }
    };
};
var DoctorSmallCardController = function () {

    var self = this;
    this.doctor_id = null;

    this.init = function () {
        $(document).on('click', '.add_doctor_to_bookmark', function () {
            self.doctor_id = $(this).data('doctor_id');

            if (self.doctor_id) {
                self.getBookmarkBlock();
            }
        });
    };

    this.getBookmarkBlock = function () {
        Ajax.Post('/doctor/ajaxAddToMyDoctorList', {doctor_id: self.doctor_id}, function (data) {
            if (data.status == 0) {
                var isItAddOrKick = data.result.my_doctor;

                if (isItAddOrKick) {
                    $('#add_doctor_to_bookmark_' + self.doctor_id).addClass("btn-bookmark-added");
                    $('#add_doctor_to_bookmark_' + self.doctor_id).html('<i class="icon-add"></i> <span class="txt txt-added">В закладках</span>');
                } else {
                    $('#add_doctor_to_bookmark_' + self.doctor_id).removeClass('btn-bookmark-added');
                    $('#add_doctor_to_bookmark_' + self.doctor_id).html('<i class="icon-add"></i> <span class="txt">Добавить в закладки</span>');
                }
            }
        });
    };
}
var DocotorsVisitsComingController = function () {

    this.day_counter = 0;
    this.doctor_id;
    this.schedule_id;
    this.visit_id;

    this.record_controller = null;

    var controller = this;

    this.init = function () {

        $('.btn-4').click(function(){
            //if (controller.record_controller == null){
                controller.doctor_id = $(this).data('doctor_id');
                controller.visit_id = $(this).data('visit_id');
                controller.record_controller = new RecordToTheDoctorBlockController(controller.doctor_id, $(this),controller.visit_id);
                controller.record_controller.init();
            //}
        });

        $( "#datepicker" ).datepicker({
            inline: true,
            showOtherMonths: true,
            firstDay: 1,
            monthNames: [ "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь" ]
        });

        controller.showCalendarDates();

        $(document).on('click', 'a.ui-corner-all', function () {
            controller.showCalendarDates();
        });


        $(document).on('click', '.cancel_visit_button', function () {
            var visit_id = $(this).data('id');
            if (visit_id)
                controller.cancelVisitToDoctor(visit_id, $(this));
        });

        $(document).on('click', '#change_visit', function () {
            controller.doctor_id = $(this).data('doctor_id');
            controller.visit_id = $(this).data('visit_id');

            if (controller.doctor_id) {
                controller.getScheduleTimes();
                $('#change-time').css('display', 'block');
                $('.manage_but').attr('disabled', 'disabled');
                $('.manage_but').attr('disabled', 'disabled');
            }
        });

        $(document).on('click', '#cancel_change', function () {
            $('#change-time').css('display', 'none');
            $('.manage_but').removeAttr('disabled');
            $('.manage_but').removeAttr('disabled');
        });

        $(document).on('click', '#doctor-day-next', function () {
            controller.day_counter++;
            controller.getScheduleTimes();

        });

        $(document).on('click', '#doctor-day-previous', function () {
            if (controller.day_counter > 0) {
                controller.day_counter--;
                controller.getScheduleTimes();
            }
            else {
                showError('Расписание прошедших дней недоступно!');
            }
        });

        $(document).on('click', '.time', function () {
            controller.schedule_id = $(this).attr("id");
            controller.schedule_id = $(this).attr("id");
            if (controller.schedule_id && controller.visit_id)
                controller.updateVisitTime(controller.schedule_id, controller.visit_id);
        });
    }

    this.getScheduleTimes = function () {
        Ajax.Post('/doctor/ajaxGetScheduleTimesByDay', {day_counter: controller.day_counter, doctor_id: controller.doctor_id}, function (data) {
            if (data.result.doctor_times)
                $('#doctor-times').html(data.result.doctor_times);
            else
                $('#doctor-times').empty();
        });
    };

    this.updateVisitTime = function (schedule_id,visit_id) {
        Ajax.Post('/doctor/ajaxUpdateVisitTime', {
                schedule_id: schedule_id,
                visit_id : visit_id
            },
            function (data) {
                if (data.result) {
                    showOk('Вы записаны на прием!');
                    $('#change-time').css('display', 'none');
                    $('.manage_but').removeAttr('disabled');
                    $('.manage_but').removeAttr('disabled');
                    window.location = '/account/doctorsVisitsComing';
                }
                else {
                    showError('Ошибка');
                }
            }
        );
    };

    this.cancelVisitToDoctor = function (visit_id,el) {
        Ajax.Post('/account/cancelVisitToDoctor', {
                visit_id: visit_id
            },
            function (data) {
                if (data.status == 0) {
                    //showOk('Ваш визит будет отменен.');
                    controller.showPopup('Ваш визит будет отменен.');
                    el.parent().parent().parent().parent().remove();
                    if ($('.record-cart').length == 0){
                        $('.record-block').html('<div class="coming_visit">Записей нет</div>');
                    };
                    //window.location = '/account/doctorsVisitsComing';
                } else {
                    showError('Ошибка');
                }
            }
        );
    }

    this.showPopup = function(text)
    {
        $('#success-popup .success-txt').html(text);
        $('#success-popup-link').click();
    }

    this.showCalendarDates = function() {

        $('a.ui-state-highlight').removeClass('ui-state-active');
        Ajax.Post('/ajax/getMonthVisitDays', {}, function (data) {
            if (data.result.visit_records)
            {
                var visits = data.result.visit_records;
                $('.ui-datepicker-calendar td').each(function(){
                    for (var i=0;i<visits.length;i++) {
                        if (($(this).attr('data-year') == visits[i].year) && ($(this).attr('data-month') == visits[i].month) && ($(this).text() == visits[i].day)){
                            $(this).children('a').addClass('ui-state-active');
                        }
                    }
                });
            }
        });
        controller.addCalendarLocker();
    }

    this.addCalendarLocker = function()
    {
        lock_calendar = $('<div class="lock-calendar"></div>');

        lock_calendar.css({
            position: 'absolute',
            left: 0,
            top: 100,
            width: 275,
            height: 235
        });

        $('.ui-datepicker-inline').append(lock_calendar);
    }
}
var Elements = {};

Elements.AddFormTemplate = function (template_container, el, func) {
    el.before(template_container.html());

    if (func != undefined) {
        func();
    }
};

$(document).ready(function () {
    $('.gender-select .man').click(function () {
        $(this).addClass('selected');
        $(this).parent().find('.woman').removeClass('selected');
        $(this).parent().find('input[type="hidden"]').val(1);
    })

    $('.gender-select .woman').click(function () {
        $(this).addClass('selected');
        $(this).parent().find('.man').removeClass('selected');
        $(this).parent().find('input[type="hidden"]').val(2);
    })

    var doctor_pick_controller = new DoctorPickQuickSearchController();

    doctor_pick_controller.setInputElement($('#doctor-pick-quick-search .doctor-pick-input'));
    doctor_pick_controller.setDrowDownContainer($('#doctor-pick-quick-search .drop-menu'));
    doctor_pick_controller.init();

});

var DoctorPickQuickSearchController = function () {
    this.input_element = null;
    this.drop_down_container = null;

    var controller = this;
    this.setInputElement = function (el) {
        this.input_element = el;

    };

    this.setDrowDownContainer = function (container) {
        this.drop_down_container = container;
    };

    this.init = function () {
        var self = this;

        self.input_element.keyup(function () {
            self.updateDropDownList();
        });
    };

    this.updateDropDownList = function () {
        var self = this;

        var clinic_id = $('select[name="form[clinic_id]"]').val();
        var text = this.input_element.val();
        if (text.length > 2) {
            Ajax.Post('/ajax/getDoctorsPick', {query: text, clinic_id: clinic_id}, function (data) {
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

    $(document).on('click', '#doctor-pick-quick-search .drop-menu li', function () {
        controller.input_element.val($(this).text());
        controller.drop_down_container.slideUp();
        $('input[name="form[doctor_id]"]').val($(this).attr('data-id'));
        controller.drop_down_container.html('');
    });

    $(document).on('change', 'select[name="form[clinic_id]"]', function () {
        $('.doctor-pick-input').val('');
        $('input[name="form[doctor_id]"]').val('');
        controller.drop_down_container.css('display', 'none');
        controller.drop_down_container.html('');
    });

    $('body').click(function (event) {
        if ($(event.target).closest(".drop-menu").length)
            return;
        controller.drop_down_container.slideUp();
        controller.drop_down_container.html('');
    });

};


var ImageJCropController = function () {

    var self = this;
    this.container = null;

    this.width = null;
    this.height = null;

    this.button_selector = null;

    this.success_callback = null;

    this.sizes = [];

    this.preview_container_selector = null;

    this.setSuccessCallback = function (callback) {
        self.success_callback = callback;
    };


    this.init = function () {
        var self = this;

        var uploader = new plupload.Uploader({
            runtimes : 'gears,html5,browserplus,flash',
            browse_button : self.button_selector,
            //container: 'container',
            max_file_size : '10mb',
            multi_selection: false,
            url : '/registry/ajax/uploadImage',
            resize : {width : 1000, height : 800, quality : 90},
            flash_swf_url : '/media/js/plupload/plupload.flash.swf',
            silverlight_xap_url : '/media/js/plupload/plupload.silverlight.xap',
            filters : [
                {title : "Изображения", extensions : "jpg,gif,png"}
            ]
        });

        uploader.bind('Init', function(up, params) {
            //$('#filelist').html("<div>Current runtime: " + params.runtime + "</div>");
        });

        var el = uploader;
        uploader.bind('FilesAdded', function(up, files) {
            for (var i in files) {
                $(self.preview_container_selector).append('<div id="' + files[i].id + '">' + files[i].name + ' (' + plupload.formatSize(files[i].size) + ') <b></b></div>');
            }
            setTimeout(function(){
                uploader.start();
            }, 100);
        });

        uploader.bind('UploadProgress', function(up, file) {
            $('#'+file.id).find('b').html('<span>' + file.percent + "%</span>");
        });

        uploader.bind('FileUploaded', function(up, file, response) {
            setTimeout(function(){
                $('#'+file.id).remove();
            }, 1000);

            response = JSON.parse(response.response);
            var img_count = $('.one_doctor_img').length;
            if (img_count >= 10){
                var message = new PopupMessage();
                message.show('Количество фотографий не должно превышать 10');
                return;
            };

            if (response.status == 0) {
                var resize_ratio = response.result.original_image.width / response.result.resized_image.width;

                if((response.result.original_image.width < self.width ) || (response.result.original_image.height < self.height))
                {
                    var message = new PopupMessage();
                    message.show('Загруженная фотография меньше допустимых минимальных размеров');
                } else {
                    var crop_block = new CropBlockController();
                    crop_block.image_id = response.result.image_id;
                    crop_block.image_path = response.result.resized_image.path;
                    crop_block.width = self.width;
                    crop_block.height = self.height;
                    crop_block.resize_ratio = resize_ratio;

                    if (self.sizes.length)
                    {
                        for (var i in self.sizes)
                        {
                            self.sizes[i].min_size =  [self.sizes[i].width / resize_ratio, self.sizes[i].height / resize_ratio];
                        }
                    }

                    crop_block.sizes = self.sizes;
                    crop_block.min_size = [self.width / resize_ratio, self.height / resize_ratio];
                    crop_block.setSuccessCallback(self.success_callback);
                    crop_block.init();
                }
            }
        });


        $('#start').click(function(){
            uploader.start();
        });

        uploader.init();

/*
        $(self.button_selector).uploadify({
            'buttonText': 'Добавить фотографию',
            'buttonClass' : 'longest-button',
            'swf': '/media/js/uploadify/uploadify.swf',
            'uploader': '/registry/ajax/uploadImage',
            'fileTypeDesc': 'jpg,bmp,png,gif',
            'fileTypeExts': '*.jpg;*.bmp;*.png;*.gif',
            'multi': false,
            'onUploadSuccess': function (file, data, response) {
                var json = JSON.parse(data);

                var img_count = $('.one_doctor_img').length;
                if (img_count >= 10){
                    var message = new PopupMessage();
                    message.show('Количество фотографий не должно превышать 10');
                    return;
                };

                console.log(json);
                if (json.status == 0) {
                    var resize_ratio = json.result.original_image.width / json.result.resized_image.width;

                    if((json.result.original_image.width < self.width ) || (json.result.original_image.height < self.height))
                    {
                        var message = new PopupMessage();
                        message.show('Загруженная фотография меньше допустимых минимальных размеров');
                    } else {
                        var crop_block = new CropBlockController();
                        crop_block.image_id = json.result.image_id;
                        crop_block.image_path = json.result.resized_image.path;
                        crop_block.width = self.width;
                        crop_block.height = self.height;
                        crop_block.resize_ratio = resize_ratio;

                        if (self.sizes.length)
                        {
                            for (var i in self.sizes)
                            {
                                self.sizes[i].min_size =  [self.sizes[i].width / resize_ratio, self.sizes[i].height / resize_ratio];
                                console.log(self.sizes[i].min_size);
                            }
                        }

                        crop_block.sizes = self.sizes;
                        crop_block.min_size = [self.width / resize_ratio, self.height / resize_ratio];
                        crop_block.setSuccessCallback(self.success_callback);
                        crop_block.init();
                    }
                } else {
                    console.log('error');
                }
            }
        });*/
    };
};

var CropBlockController = function () {

    var self = this;

    this.container = null;
    this.image_id = null;
    this.width = null;
    this.height = null;

    this.image_path = null;

    this.min_size = null;

    this.aspect_ratio = null;

    this.resize_ratio = null;

    this.x = null;
    this.y = null;

    this.w = null;
    this.h = null;

    this.sizes = [];

    this.success_callback = null;

    this.popup = null;

    this.jcrop_api = null;

    this.setSuccessCallback = function (callback) {
        self.success_callback = callback;
    };


    this.init = function () {
        var popup = new Popup();

        self.aspect_ratio = self.width / self.height;


        var rand = Math.floor(Math.random() * (10000 - 1 + 1)) + 1;


        self.container = '#crop-block' + rand;

        var html = '<div class="crop-block" id="crop-block' + rand + '">';
        html += '<div class="image_block"><img id="jcrop_target" src="' + self.image_path + '" /></div>';
        html += '<div id="preview-block" style="width:' + (self.aspect_ratio * 100) + 'px;height:100px;overflow:hidden;margin-left:5px;">';
        html += '<img id="preview" src="' + self.image_path + '" />';




        html += '</div>';

        if (self.sizes.length)
        {
            html += '<br /><br /><div id="change-size">';
            for (var i in self.sizes)
            {
                 html += '<div style="margin-top:10px"><input type="submit" data-min-width="'+self.sizes[i].min_size[0]+'" data-min-height="'+self.sizes[i].min_size[1]+'" data-width="' + self.sizes[i].width + '" data-height="' + self.sizes[i].height
                        + '" value="Размеры ' +self.sizes[i].width+ 'x' + self.sizes[i].height + '" /> </div>';
            }

            html += '</div>';
        }
        html += '<div class="buttons" style="clear: both; padding-top: 20px">' +
            '<input class="btn-appoint" type="submit" name="cancel" value="Отменить" style="display: inline;"/>' +
            ' <input class="btn-1" type="submit" name="add" value="Добавить" style="height: 46px;"/> ' +
            '</div>';
        html += '</div>';

        popup.show('<div style="padding: 20px">' + html + '</div>', '1000px', '590px');


        self.popup = popup;

        $(self.container + ' #change-size input').click(function(){


            self.aspect_ratio = $(this).data('width')/$(this).data('height');
            var min_size1 = [$(this).data('min-width'), $(this).data('min-height')];

            self.jcrop_api.setOptions(
            {
                aspectRatio: self.aspect_ratio,
                minSize: min_size1
            });

            $('#preview-block').css('width', self.aspect_ratio * 100);
            self.width = $(this).data('width');
            self.height = $(this).data('height');
        });

        $(self.container + ' input[name="cancel"]').click(function () {
            self.popup.close();
        });

        $(self.container + ' input[name="add"]').click(function () {
            self.sendData();
        });

        $(self.container + ' #jcrop_target').Jcrop({
            onChange: self.showPreview,
            onSelect: self.showPreview,
            aspectRatio: self.aspect_ratio,
            minSize: self.min_size
        },function(){
            //Store the API in the jcrop_api variable
            self.jcrop_api = this;
        });
    };

    this.showPreview = function (coords) {

        var rx = self.aspect_ratio * 100 / coords.w;
        var ry = 100 / coords.h;

        self.x = (coords.x * self.resize_ratio);
        self.y = coords.y * self.resize_ratio;
        self.w = coords.w * self.resize_ratio;
        self.h = coords.h * self.resize_ratio;



        $('#preview').css({
            width: Math.round(rx * $('#jcrop_target').width()) + 'px',
            height: Math.round(ry * $('#jcrop_target').height()) + 'px',
            marginLeft: '-' + Math.round(rx * coords.x) + 'px',
            marginTop: '-' + Math.round(ry * coords.y) + 'px'
        });
    };

    this.sendData = function () {
        var data = {
            x: self.x,
            y: self.y,
            w: self.w,
            h: self.h,
            width: self.width,
            height: self.height,
            image_id: self.image_id
        };

        Ajax.Post('/registry/ajax/cropImage', data, function (data) {
            if (data.status == 0) {
                if (self.success_callback)
                    self.success_callback(data.result);

                self.popup.close();
            }
        });
    };
};
var ExampleShowCardsFormController = function (clinic_id) {

    var self = this;

    this.clinic_id = clinic_id;
    this.specialty_id = null;
    this.purpose_of_visit_id = null;
    this.time_of_visit = null;
    this.page = 1;

    this.init = function () {

        $('.header, .footer').remove();

        $('.content a').click(function(){
            return false;
        });

        $('#doctor-container a').click(function(){
            return false;
        });


    };
}
var FavoriteClinicsFormController = function (account_id) {
    var self = this;

    this.account_id = account_id;
    this.type_of_clinic = null;
    this.purpoise_of_visit = null;
    this.page = 1;

    this.init = function () {

        $('#type_of_clinic').change(function () {
            self.type_of_clinic = $(this).val();
            self.purpoise_of_visit = $('#purpose_of_visit').val();
            self.page = 1;
            self.sendRequest();
        });

        $('#purpose_of_visit').change(function () {
            self.purpoise_of_visit = $(this).val();
            self.type_of_clinic = $('#type_of_clinic').val();
            self.page = 1;
            self.sendRequest();
        });

        $('#view_more_my_clinics i').addClass('icon-loader');
        self.sendRequest();

        $(document).on('click', '#view_more_my_clinics', function () {
            $('#view_more_my_clinics i').addClass('icon-loader');
            self.sendRequest();
        });

        $(document).on('click', '.close', function () {
            var clinic_id = $(this).data('id');
            if (clinic_id) {
                self.removeFromFavorite(clinic_id);
            };
        });
    };

    this.sendRequest = function (reload_flag) {

        if (reload_flag == undefined)
            reload_flag = 0;

        var data = {
            page: self.page,
            account_id: self.account_id,
            type_of_clinic: self.type_of_clinic,
            purpoise_of_visit : self.purpoise_of_visit,
            reload: reload_flag
        };

        Ajax.Post('/account/ajaxGetMyClinics', data, function (data) {

            if (data.status == 0) {
                $('#view_more_my_clinics i').removeClass('icon-loader');

                if (self.page == 1 || reload_flag)
                    $('#my_clinics_container').html(data.result.html);
                else
                    $('#my_clinics_container').append(data.result.html);

                if (data.result.more_button == 0) {
                    $('#more_my_clinics').css('display', 'none');
                } else {
                    $('#more_my_clinics').css('display', 'block');
                    $('#view_more_my_clinics i').removeClass('icon-loader');
                }

                self.page++;
            } else if (data.status == 2){
                $('#my_clinics_container').html('По указанным критериям не найдено клиник');
            }
        });
    };

    this.reload = function () {
        self.sendRequest(1);
    };

    this.removeFromFavorite = function (clinic_id) {
        options = {
            clinic_id: clinic_id
        };

        Ajax.Post('/ajax/removeClinicFromFavorite', options, function (data) {
            if (data.status == 0) {
                self.reload();
            };
        });
    };
};
var FavoriteDoctorsFormController = function (account_id) {
    var self = this;

    this.account_id = account_id;
    this.clinic_id = null;
    this.specialty_id = null;
    this.purpose_of_visit_id = null;
    this.time_of_visit = null;
    this.page = 1;

    this.init = function () {

        /***** Search Form *****/
        $('#doctor_search_form select[name="clinic_id"]').change(function () {
            self.clinic_id = $(this).val();
            self.page = 1;

            self.sendRequestMyDoctors();
        });

        $('#doctor_search_form select[name="specialty_id"]').change(function () {
            self.specialty_id = $(this).val();
            self.page = 1;

            self.sendRequestMyDoctors();
        });

        $('#doctor_search_form select[name="time_of_visit"]').change(function () {
            self.time_of_visit = $(this).val();
            self.page = 1;
            self.sendRequestMyDoctors();
        });

        $(document).on('change', '#doctor_search_form select[name="purpose_of_visit_id"]', function () {
            self.purpose_of_visit_id = $(this).val();
            self.page = 1;

            self.sendRequestMyDoctors();
        });

        /***** my Doctors *****/
        $('#more_my_doctors i').addClass('icon-loader');
        self.sendRequestMyDoctors();

        $(document).on('click', '#more_my_doctors', function () {
            $('#more_my_doctors i').addClass('icon-loader');
            self.page++;
            self.sendRequestMyDoctors();
        });

        $(document).on('click', '.close', function () {
            var doctor_id = $(this).data('id');
            if (doctor_id) {
                self.removeFromFavorite(doctor_id);
            };
        });
    };

    this.sendRequestMyDoctors = function (reload_flag) {

        if (reload_flag == undefined)
            reload_flag = 0;

        var data = {
            specialty_id: self.specialty_id,
            clinic_id: self.clinic_id,
            purpose_of_visit_id: self.purpose_of_visit_id,
            time_of_visit: self.time_of_visit,
            page: self.page,
            account_id: self.account_id,
            reload: reload_flag
        };

        Ajax.Post('/account/ajaxGetMyDoctors', data, function (data) {

            if (data.status == 2)
            {
                $('#my_doctors_container').html('<div id="no_result">Вы еще не добавили в закладки ни одного врача</div>');
                $('#bookmarks-block .view-more-block').css('display', 'none');
            }

            if (data.status == 0) {

                $('#more_my_doctors i').removeClass('icon-loader');

                if (self.page == 1 || reload_flag)
                    $('#my_doctors_container').html(data.result.html);
                else
                    $('#my_doctors_container').append(data.result.html);

                if (data.result.more_button == 0) {
                    $('#bookmarks-block .view-more-block').css('display', 'none');
                } else {
                    $('#bookmarks-block .view-more-block').css('display', 'block');
                }
            }
        });
    };

    this.reload = function () {
        self.sendRequestMyDoctors(1);
    };

    this.removeFromFavorite = function (doctor_id) {
        options = {
            doctor_id: doctor_id
        };

        Ajax.Post('/ajax/removeDoctorFromFavorite', options, function (data) {
            if (data.status == 0) {
                self.reload();
            };
        });
    };
};
var FileUploaderController = function()
{
    var self = this;

    this.init = function(){

    };

    
}
var FooterBlockController = function () {
    var self = this;

    this.init = function () {
        $(document).on('click', '.show_license', function(){
            $('.license_back_button').css('display', 'none');
        });
    };

}
var HelpPageController = function () {

    this.init = function () {
        // вещаем обработчики на кнопки
        var controller = this;
        $( "#tabs" ).tabs();

        var help_search_controller = new HelpQuickSearchFormController();

        help_search_controller.setInputElement($('.search-block .txt'));
        help_search_controller.setDrowDownContainer($('.search-block .drop-menu'));
        help_search_controller.setSubmitElement($('.search-block .btn-1'));
        help_search_controller.init();

        $(document).on('click', '.nav ul li a', function () {
            $(".sub-menu").each(function () {
                $(this).children().removeClass('active');
            });
            $(".sub-menu").each(function () {
                $(this).find(':first-child').addClass('active');
            });
        });
        $(".sub-menu").each(function () {
            $(this).find(':first-child').addClass('active');
        });

    };
}
var HelpQuickSearchFormController = function () {
    this.input_element = null;
    this.drop_down_container = null;
    this.submit_element = null;

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

        self.input_element.keyup(function () {
            self.updateDropDownList();
        });

        self.submit_element.click(function(){
            var text = self.input_element.val();

            if (text.length > 0)
            {
               window.location.href = '/help/searchResults?query=' + encodeURIComponent(text);
            }
        });
    };

    this.updateDropDownList = function () {
        var self = this;

        var text = this.input_element.val();
        if (text.length > 2) {
            Ajax.Post('/ajax/getHelps', {query:text}, function (data) {
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
var HelpSearchResultsPageController = function () {

    this.help_query = '';
    this.page = 1;
    this.by_page = 10;

    var controller = this;

    this.init = function () {
        $(document).on('click', '.view-more', function () {
            $('.view-more i').addClass('icon-loader');
            controller.loadNextPage();
        });

        var help_search_controller = new HelpQuickSearchFormController();

        help_search_controller.setInputElement($('.search-block .txt'));
        help_search_controller.setDrowDownContainer($('.search-block .drop-menu'));
        help_search_controller.setSubmitElement($('.search-block .btn-1'));
        help_search_controller.init();
    };

    this.sendRequest = function () {
        Ajax.Post('/help/ajaxMoreSearchResults', {help_query:controller.help_query, page:controller.page}, function (data) {
            if (data.result.materials) {
                $('.view-more').remove();
                $('.illness-results-list').append(data.result.materials);
            }
            if (data.result.next_page_button)
                $('.ilness-result').append(data.result.next_page_button);
        });
    };

    this.loadNextPage = function () {
        controller.page = controller.page + 1;
        controller.help_query = getParameterByName('help_query');
        controller.sendRequest();
    };
};
var IndexPageController = function (email_confirm_popup, change_pass_popup) {

    var self = this;

    this.email_confirm = email_confirm_popup;
    this.change_pass = change_pass_popup;

    this.login_form_controller = null;

    this.init = function () {

        if (self.email_confirm == 1)
        {
            attachFancybox($('#success-popup-link'));
            self.showPopup('Email успешно подтвержден! <br> Ждите приглашение!');
        }

        if (self.change_pass == 1)
        {
            attachFancybox($('#success-popup-link'));
            self.showPopup('Пароль успешно изменен! <br> Ждите приглашение!');
        }

        $('#registration-link').click(function(){
            registration_form_controller = new RegistrationFormController();
            registration_form_controller.init();
        });

        $('#authorization-link').click(function(){
            if(!self.login_form_controller)
            {
                self.login_form_controller = new LoginFormController();
                self.login_form_controller.init();
            }
        });
    };

    this.showPopup = function (text) {
        $('#success-popup .success-txt').html(text);
        $('#success-popup-link').click();
    }
}
var LandingForgotPasswordController = function () {

    var self = this;

    self.email = '';

    this.init = function () {
        var options = {
            landing: true,
            type: 'landing_passwrod_recovery'
        };

        Ajax.Post('/ajax/getPopup', options, function (data) {
            if (data.status == 0) {
                var landing_forgotpass_popup = data.result.html;

                showLandingForgotPassPopup(landing_forgotpass_popup);
                $('#landing-forgotpass-popup').css('display', 'block');

                $('.btns #send_recovery_email').click(function () {
                    self.sendRecoveryPasswordEmail();
                });

                document.onkeyup = function (e) {
                    e = e || window.event;
                    if (e.keyCode === 13) {
                        self.sendRecoveryPasswordEmail();
                    }
                    // Отменяем действие браузера
                    return false;
                };
            };
        });
    };

    this.sendRecoveryPasswordEmail = function () {
        self.email = $('#for_recovery_email').val();

        Ajax.Post('/account/passwordRecovery', {
                email: self.email
            },
            function (data) {
                if (data.status == 0) {
                    $('#landing-forgotpass-popup .intro').html('Письмо отправлено на ' + self.email + '.');
                    window.location = '/';
                } else if (data.status == 32) {
                    $('.error-msg-email').remove();
                    self.setError($('#forgotpass-form input.submit_email'), 'Данный email еще не подтвержден');
                } else {
                    $('.error-msg-email').remove();
                    self.setError($('#forgotpass-form input.submit_email'), 'Пользователь с таким E-mail не зарегистрирован');
                }
            }
        );
    };

    this.setError = function(el, message) {
        var error = $('<div class="error-msg-email">' + message + '</div>');
        var pos =  el.position();

        error.css({
            top: -120,
            'margin-left': 20,
            right: 10,
            position: 'relative',
            display: 'block'
        });

        error.css('display', 'block').delay(2000).fadeOut(1000);
        el.after(error);
    }
};
var LandingLicensePageController = function () {
    var self = this;

    this.init = function () {

        var options = {
            landing: true,
            type: 'landing_license'
        };

        Ajax.Post('/ajax/getPopup', options, function (data) {
            if (data.status == 0) {
                var landing_license_popup = data.result.html;

                showLandingPopup(landing_license_popup);

                $('#landings-terms-popup').css('display', 'block');

                $('#back-to-landing-registration').click(function () {
                    $('#main-landing-popup').remove();
                    landing_registration_page_controller = new LandingRegistrationPageController(self.url_page);
                    landing_registration_page_controller.init();
                });
            };
        });
    };
}
var LandingLoginPageController = function (page_url,specialty_text, recording) {
    var self = this;
    this.page_url = page_url;
    this.specialty_text = specialty_text;
    this.recording = recording;

    this.init = function () {

        if (self.page_url == undefined)
            self.page_url = '/account';

        var options = {
            landing: true,
            type: 'landing_login'
        };

        Ajax.Post('/ajax/getPopup', options, function (data) {
            if (data.status == 0) {
                var landing_login_popup = data.result.html;

                showLandingPopup(landing_login_popup);
                $('.fancybox-wrap').css('width',550);
                $('.fancybox-wrap').css('left', ($(window).width() - $('.fancybox-wrap').width()) / 2);
                $('.fancybox-overlay').css('background-image', 'url("/media/images/fancybox_overlay_light.png")');

                $('.head-block h2').html('Лучшие '+self.specialty_text+' Москвы');
                $('.vis-1 p').html('В нашей базе лучшие '+self.specialty_text+' Москвы');

                if ($('.vis-1 p').text().length > 50) {
                    $('.head-block').css('height',245);
                    $('.head-block').css('background','url("/media/images/landing_popup_bg.jpg") no-repeat scroll 0 -44px transparent');
                }

                $('#landing-login-popup').css('display', 'block');

                $('#submit_landing_login').click(function () {
                    self.tryLogin();
                });

                $('#landing-login-popup').find('#fb_login').click(function(){
                    setCookie('fb_redirect_url',self.page_url,"Mon, 01-Jan-2040 00:00:00 GMT", "/");
                });

                self.changeSocialLink();

                $('#landing-registration-link').click(function () {
                    $('#main-landing-popup').remove();
                    landing_registration_page_controller = new LandingRegistrationPageController(self.page_url,self.specialty_text,self.recording);
                    landing_registration_page_controller.init();
                });

                $('#forgot-pass-link').click(function () {
                    $('#main-landing-popup').remove();
                    password_recovery_controller = new LandingForgotPasswordController();
                    password_recovery_controller.init();
                });

                document.onkeyup = function (e) {
                    e = e || window.event;
                    if (e.keyCode === 13) {
                        $('#submit_landing_login').click();
                    }
                    // Отменяем действие браузера
                    return false;
                };

             };
        });

    };

    this.tryLogin = function () {
        var email = $('#landing-login-popup input[name="landing_login_email"]').val();
        var password = $('#landing-login-popup input[name="landing_login_password"]').val();

        var data = {
            email: email,
            password: password
        };

        Ajax.Post('/account/ajaxLogin', data, function (data) {
            if (data.status == 0) {
                if (self.page_url != undefined) {
                    if (self.recording)
                        window.location = self.page_url+'?recording=1';
                    else
                        window.location = self.page_url;
                }
                else
                    window.location = '/account';
            }
            else if (data.status == 23) {
                self.showError($('#submit_landing_login'), 'Вы ещё не получили приглашение');
            }
            else {
                $('.error-msg-auth').remove();
                self.showError($('#submit_landing_login'), 'Неверный логин или пароль');
            }
        });
    };

    this.showError = function (el, message) {
        $('.error-msg-auth').remove();
        var error = $('<div class="error-msg-auth"><label class="error" for="landing_login_form">' + message + '</label></div>');
        var pos = el.position();

        error.css({
            top: '-' + el.height(),
            'margin-left': 130,
            right: el.parent().width() - pos.left,
            position: 'relative',
            display: 'block',
            width: '250px'
        });

        error.css('display', 'block').delay(2000).fadeOut(1000);
        el.after(error);
    };

    this.changeSocialLink = function(){
        var site_url = $('.socials input[name="site_url"]').val();

        var vk_cliend_id = $('.socials input[name="vk_client_id"]').val();
        var vk_dest_url = '?destination='+self.page_url;
        var vk_redirect_url = site_url+'/account/vk_login'+vk_dest_url;
        var vk_link = 'http://oauth.vk.com/authorize?client_id='+vk_cliend_id+'&scope=photos,offline&redirect_uri='+vk_redirect_url+'&response_type=code';
        $('#landing-login-popup').find('#vk_login').attr('href', vk_link);

        var fb_cliend_id = $('.socials input[name="fb_client_id"]').val();
        //var fb_dest_url = '?destination='+self.page_url;
        var fb_redirect_url = site_url+'/account/fb_login';//+fb_dest_url;
        var fb_link = 'https://www.facebook.com/dialog/oauth?client_id='+fb_cliend_id+'&scope=email,user_birthday,user_location&redirect_uri='+fb_redirect_url+'&response_type=code';

        $('#landing-login-popup').find('#fb_login').attr('href', fb_link);

        var mailru_cliend_id = $('.socials input[name="mailru_client_id"]').val();
        var mailru_dest_url = '?destination='+self.page_url;
        var mailru_redirect_url = site_url+'/account/mailru_login'+mailru_dest_url;
        var mailru_link = 'https://connect.mail.ru/oauth/authorize?client_id='+mailru_cliend_id+'&response_type=code&redirect_uri='+mailru_redirect_url;
        $('#landing-login-popup').find('#mailru_login').attr('href', mailru_link);

        var ok_cliend_id = $('.socials input[name="ok_client_id"]').val();
        var ok_dest_url = '?destination='+self.page_url;
        var ok_redirect_url = site_url+'/account/ok_login'+ok_dest_url;
        var ok_link = 'http://www.odnoklassniki.ru/oauth/authorize?client_id='+ok_cliend_id+'&response_type=code&redirect_uri='+ok_redirect_url;
        $('#landing-login-popup').find('#ok_login').attr('href', ok_link);
    }
}
var LandingRegistrationPageController = function (url_page,specialty_text,recording, action_for_counters, label_for_counters) {

    var self = this;
    this.url_page = url_page;
    this.specialty_text = specialty_text;
    this.recording = recording;
    this.label_for_counters = label_for_counters;
    this.action_for_counters = action_for_counters;

    this.init = function () {

        if (self.page_url == undefined)
            self.page_url = '/account';

        var options = {
            landing: true,
            type: 'landing_registration'
        };

        Ajax.Post('/ajax/getPopup', options, function (data) {
            if (data.status == 0) {
                var landing_registration_popup = data.result.html;

                showLandingPopup(landing_registration_popup);
                $('.fancybox-wrap').css('width',550);
                $('.fancybox-wrap').css('left', ($(window).width() - $('.fancybox-wrap').width()) / 2);
                $('.fancybox-overlay').css('background-image', 'url("/media/images/fancybox_overlay_light.png")');

                $('.head-block h2').html('Лучшие '+self.specialty_text+' Москвы');
                $('.vis-1 p').html('В нашей базе лучшие '+self.specialty_text+' Москвы');

                if ($('.vis-1 p').text().length > 50) {
                    $('.head-block').css('height',245);
                    $('.head-block').css('background','url("/media/images/landing_popup_bg.jpg") no-repeat scroll 0 -44px transparent');
                }

                $('#landing-popup-registration').css('display', 'block');

                $('#landing-login-link').click(function () {
                    $('#main-landing-popup').remove();
                    landing_login_page = new LandingLoginPageController(self.url_page,self.specialty_text,self.recording);
                    landing_login_page.init();
                });

                $('#landing-license').click(function(){
                    $('#main-landing-popup').remove();
                    landing_license = new LandingLicensePageController();
                    landing_license.init();
                });

                $('#submit_landing_registration').validation({
                    validate: [
                        $('#landing-popup-registration input[name="landing_registration_email"]').validate(validation_rules['email'])
                    ],
                    callback: function(){
                        self.tryLandingRegister();
                    }
                });

                document.onkeyup = function (e) {
                    e = e || window.event;
                    if (e.keyCode === 13) {
                        $('#submit_landing_registration').click();
                    }
                    // Отменяем действие браузера
                    return false;
                };
            };
        });
    };

    this.tryLandingRegister = function () {

        self.email = $('#landing-popup-registration input[name="landing_registration_email"]').val();

        var options = {
            email: self.email,
            url: self.url_page
        };

        Ajax.Post('/account/landingRegistration', options, function (data) {
            if (data.status == 0) {
                setCounters('reg-complete', self.action_for_counters, self.label_for_counters, self.email);
                if (self.recording) document.location = self.page_url+'?recording=1';
                else document.location = self.page_url;
            } else {
                setCounters('reg-complete', 'unknown', self.label_for_counters, self.email);
                $('.error-msg-email').remove();
            }
        });
    };

    this.setError = function (el, message) {
        var error = $('<div class="error-msg-email">' + message + '</div>');
        var pos = el.position();

        error.css({
            top: -120,
            'margin-left': 210,
            right: 10,
            position: 'relative',
            display: 'block'
        });
        error.css('display', 'block').delay(2000).fadeOut(1000);
        el.after(error);
    };
}
var LoginFormController = function () {
    var self = this;

    self.password_recovery_controller = null

    this.init = function () {

        $('#authorization-popup input[name="password"]').keyup(function(e) {
            e = e || window.event;
            if(e.keyCode == 13){
                $('#authorization-popup input.submit').click();
            }
        });

        $('#authorization-popup input.submit').click(function () {
            self.logIn();
        });

        $('#registration-popup-link').click(function(){
            setCounters('reg-begin', 'home-login-reg', 'about', 'guest');
            registration_form_controller = new RegistrationFormController();
            registration_form_controller.init();
        });

        $('#forgot-popup-link').click(function(){

            if (!self.password_recovery_controller)
            {
                self.password_recovery_controller = new PasswordRecoveryController();
                self.password_recovery_controller.init();
            }

        });

        $('#login-link').click(function () {
            //login_form_controler = new LoginFormController();
            //login_form_controler.init();
        });

    };

    this.logIn = function () {

        var email = $('#authorization-popup input[name="email"]').val();
        var password = $('#authorization-popup input[name="password"]').val();
        var destination = getParameterByName('destination', '/');

        var data = {
            email: email,
            password: password
        };

        Ajax.Post('/account/ajaxLogin', data, function (data) {
            if (data.status == 0) {
                window.location = destination;
            }
            else if (data.status == 23) {
                self.showError($('#authorization-popup input.submit'), 'Вы ещё не получили приглашение');
            }
            else {
                $('.error-msg-auth').remove();
                self.showError($('#authorization-popup input.submit'), 'Неверный логин или пароль');
            }
        });

    };

    this.showError = function (el, message) {
        $('.error-msg-auth').remove();
        var error = $('<div class="error-msg-auth"><label class="error" for="login_form">' + message + '</label></div>');
        var pos = el.position();

        error.css({
            top: '-' + el.height(),
            'margin-left': 130,
            right: el.parent().width() - pos.left,
            position: 'relative',
            display: 'block',
            width: '250px'
        });

        error.css('display', 'block').delay(2000).fadeOut(1000);
        el.after(error);
    };


}

var simplemap = null;
var MapController = function () {

    var self = this;

    this.map = null;

    this.latitude = null;
    this.longitude = null;


    this.setLatitude = function(val){
        self.latitude = val;
    };

    this.setLongitude = function(val){
        self.longitude = val;
    };

    this.init = function (container) {
        if (!(window.ymaps)) {
            initFunc = '_MapController' + Math.round(Math.random() * 1000);
            window[initFunc] = function () {
                self.init.call(self, container);
            };
            $.getScript("http://api-maps.yandex.ru/2.0/?load=package.full,package.clusters,package.overlays" +
                "&lang=ru-RU&onload=" + encodeURIComponent(initFunc));

            return;
        }
        self.map = new ymaps.Map(container, {
            behaviors:['default', 'scrollZoom', 'drag'],
            center: [self.latitude, self.longitude],
            type:"yandex#map",
            zoom:15
        });

        self.map.controls.add(
            new ymaps.control.ZoomControl()
        );
    simplemap = self.map;
        var myPlacemark = new ymaps.GeoObject({
            geometry: {
                type: "Point",
                coordinates: [self.latitude, self.longitude]
            }});

        this.map.geoObjects.add(myPlacemark);
    };

    this.setMapCenter = function (latitude, longitude) {
        this.map.setMapCenter([latitude, longitude]);
    }
};
var NewEmailController = function (destination) {

    var self = this;
    this.new_email = '';
    this.destination = destination;

    this.init = function () {

        var options = {
            landing: true,
            type: 'social_new_email'
        };

        Ajax.Post('/ajax/getPopup', options, function (data) {
            if (data.status == 0) {
                var landing_forgotpass_popup = data.result.html;

                showLandingForgotPassPopup(landing_forgotpass_popup);

                $('#landing-forgotpass-popup').css('display', 'block');

                document.onkeyup = function (e) {
                    e = e || window.event;
                    if (e.keyCode === 13) {
                        $('#send_confirm_email').click();
                    }
                    // Отменяем действие браузера
                    return false;
                };

                $('#send_confirm_email').validation({
                    validate : [
                        $('#new_email').validate(validation_rules['email'])
                    ],
                    callback : self.sendConfirmMail
                });
            };
        });
    };

    this.sendConfirmMail = function (email) {

        self.new_email = $('#new_email').val();

        Ajax.Post('/account/setEmail', {
                email:self.new_email
            },
            function (data) {
                if (data.status == 0) {
                    if (self.destination)
                        window.location = self.destination;
                    else {
                        window.location = '/account';
                    }

                };
            }
        );
    }
};
var NotificationController = function () {
    var controller = this;
    this.init = function () {
        this.countUnreadedMessage();
        setInterval(this.countUnreadedMessage, 10000);
    };

    this.countUnreadedMessage = function () {
        Ajax.Post('/ajax/countUnreadedMessage', {}, function (data) {
            if (data.status == 0) {
                if (data.result > 0) {
                    $('#usernotification').html(data.result);
                    $('#usernotification').css('display', 'inline');
                }
                else {
                    $('#usernotification').html('');
                    $('#usernotification').css('display', 'none');
                }
            }
        });
    }


};
var PasswordRecoveryController = function () {

    var self = this;
    this.disabled = false;

    self.email = '';


    this.init = function () {


        $('.fancybox-inner').css('height', '310px');

        $('#recovery_email').keyup(function(e){
            e = e || window.event;
            if (e.keyCode === 13) {
                self.sendRecoveryPasswordEmail();
            }
            // Отменяем действие браузера
            return false;
        })

        $('#send_recovery_email').click(function () {
            self.sendRecoveryPasswordEmail();
        });
    };

    this.sendRecoveryPasswordEmail = function () {
        self.email = $('#recovery_email').val();

        if (self.disabled == true)
            return;

        self.disabled = true;

        Ajax.Post('/account/passwordRecovery', {
                email: self.email
            },
            function (data) {
                if (data.status == 0) {
                    self.successPopup('Письмо успешно отправлено на ' + self.email);
                } else {
                    $('.error-msg-email').remove();
                    self.setError($('#forgotpass-form input.submit_email'), 'Пользователь с таким E-mail не зарегистрирован');
                };

                self.disabled = false;
            }
        );
    };

    this.setError = function(el, message) {
        var error = $('<div class="error-msg-email">' + message + '</div>');
        var pos =  el.position();

        error.css({
            top: -120,
            'margin-left': -20,
            right: 10,
            position: 'relative',
            display: 'block'
        });

        error.css('display', 'block').delay(2000).fadeOut(1000);
        el.after(error);
        $('.fancybox-inner').css('height', '268px');
        $('.fancybox-inner').css('overflow', 'hidden');
    }

    this.successPopup = function(text){
        $('#success-popup-link').click();
        $('#success-popup .success-txt').html(text);
    };
};
var PersonalRoomAboutController = function (current_account_id) {

    var self = this;

    self.nick = '';
    self.first_name = '';
    self.last_name = '';
    self.middle_name = '';
    self.gender = null;
    self.birthday_date = null;
    self.phone_numbers = [];
    self.email = '';
    self.note_type = '';
    self.city = '';
    self.current_account_id = current_account_id;

    self.show_popup = false;
    self.link_to_page = null;

    self.changed_flag = false;
    self.disabl = false;

    this.init = function () {

        self.getCurrentInfo();

        $(document).on('keyup','.city-search-input',function () {
            self.updateDropDownList();
        });

        $(document).on('click','.city-search-input',function () {
            if (!self.city) $(this).val('');
        });

        $(document).on('focusout','.city-search-input',function () {
            if (!self.city) $('.city-search-input').val('');
        });

        $(document).on('click','.search-city-block .drop-menu li',function () {
            $('.city-search-input').val($(this).text());
            self.city_name = $(this).text();
            Ajax.Post('/ajax/getCityId', {city_name : self.city_name}, function (data) {
                if (data.status == 0) {
                    self.city = data.result.city_id;
                    $('.city-search-input').attr('data-id',self.city);
                }
            });
            $('.search-block .drop-menu').slideUp();
            $('.search-block .drop-menu').html('');
        });

        $('body a:not(.chzn-single)').click(function () {

            if (this.href != 'javascript:void(0)') {

                self.checkPhones();

                if (self.show_popup == false) {
                    if (self.isChangeinfo()) {
                        self.show_popup = true;
                    } else {
                        self.show_popup = false;
                    }
                }

                if (self.show_popup) {
                    var modal_window = new ModalWindow();
                    modal_window.show('Cохранить изменения?');

                    var a = $(this);
                    modal_window.setYesAction(function () {
                        self.link_to_page = a.attr('href');
                        $('#save_info_button').click();
                    });

                    var a = $(this);
                    modal_window.setNoAction(function () {
                        window.location = a.attr('href');
                    });
                    return false;
                }
            }
        });

        $('#save_info_button').validation({
            validate: [
                $('input[name="nick"]').validate(validation_rules['nick']),
                $('input[name="email"]').validate(validation_rules['email']),
                $('input[name="birthday_date"]').validate(validation_rules['birthday_date'])
            ],
            callback: self.saveInfo,
            error_callback: function(){
                self.link_to_page = null;
            }
        });

        $('#close_about_note').click(function () {
            self.note_type = 'about_note';
            self.closeNote();
        });

        $('.row-phone input[type="text"]').inputmask('+7-999-999-99-99');

            // установка значения даты рождения
        $(document).on('change', 'select[name="birthday_day"],select[name="birthday_month"],select[name="birthday_year"]', function () {
            var day = $('select[name="birthday_day"]').val();
            var month = $('select[name="birthday_month"]').val();
            var year = $('select[name="birthday_year"]').val();

            if (!day || !month || !year) {
                $('input[name="birthday_date"]').val('');
                return;
            }

            if (month < 10)
                month = '0' + parseInt(month);

            if (day < 10)
                day = '0' + parseInt(day);

            $('input[name="birthday_date"]').val(year + '-' + month + '-' + day);
        });

        // установка пола
        $(document).on('click', '#male_gender', function () {
            if ($('input[name="sex_id"]') != 1)
            {
                self.changed_flag = true;
                $('input[name="sex_id"]').val(1);
            }
        });

        $(document).on('click', '#female_gender', function () {
            if ($('input[name="sex_id"]') != 2)
            {
                self.changed_flag = true;
                $('input[name="sex_id"]').val(2);
            }
        });

        //add phone
        $('.add-phone').click(function () {
            var add_phone = '<div class="row flo"><label class="lab">Номер телефона</label><div class="data-box"><div class="txt"><input type="text" class="mask new_phone" placeholder="+7-___-___-__-__"></div><span class="note-txt">Передадим в клинику, только после записи на приём</span></div><img class="conf-pic" src="/media/images/conf-pic.png" alt=""></div>';
            $(".row-phone").append(add_phone);
            $('.new_phone').inputmask('+7-999-999-99-99');
        });


        $(document).on('change', '.about-form input, .about-form select', function(){
            self.changed_flag = true;
        });

        $('.new_phone, .elreary_exist_phone').inputmask('+7-999-999-99-99');

        $.extend($.inputmask.defaults.definitions, {
            'n': {
                "validator": "[А-Яа-яA-Za-z]",
                "cardinality": 1,
                'prevalidator': null
            }
        });
        $('input[name="last_name"]').inputmask({ "mask": 'n', "repeat": 255, "greedy": false });
        $('input[name="first_name"]').inputmask({ "mask": 'n', "repeat": 255, "greedy": false });
        $('input[name="middle_name"]').inputmask({ "mask": 'n', "repeat": 255, "greedy": false });


        $('.birthday_data .chzn-search').remove();
    };

    this.showAndHide = function (el) {
        $('.row_other .data-box div' + el).show();
        $('.row_other .data-box div:not("' + el + '")').hide();
    };

    this.readValues = function () {

        self.nick = ($('.about-form input[name="nick"]').val() == $('.about-form input[name="nick"]').attr('placeholder')) ? '' : $('.about-form input[name="nick"]').val();

        self.first_name = ($('.about-form input[name="first_name"]').val() == $('.about-form input[name="first_name"]').attr('placeholder')) ? '' : $('.about-form input[name="first_name"]').val();
        self.last_name = ($('.about-form input[name="last_name"]').val() == $('.about-form input[name="last_name"]').attr('placeholder')) ? '' : $('.about-form input[name="last_name"]').val();
        self.middle_name = ($('.about-form input[name="middle_name"]').val() == $('.about-form input[name="middle_name"]').attr('placeholder')) ? '' : $('.about-form input[name="middle_name"]').val();

        self.gender = $('.about-form input[name="sex_id"]').val();
        self.birthday_date = $('.about-form input[name="birthday_date"]').val();
        self.email = ($('.about-form input[name="email"]').val() == $('.about-form input[name="email"]').attr('placeholder')) ? '' : $('.about-form input[name="email"]').val();
        self.city = ($('input[name="city_query"]').attr('data-id') == undefined) ? '' : $('input[name="city_query"]').attr('data-id');
    };

    this.saveInfo = function () {
        valid = true;

        $('.new_phone').each(function () {
            var add_phone = $(this).val().replace(/\+/, '').replace(/-/g, '');

            if ((add_phone != "") && !self.isValidPhone(add_phone)) {
                valid = false;
                $('.error_span').remove();
                showErrorLabel('В поле должен быть введён корректный телефон! Пример: +7-495-111-11-11', $('.new_phone'));
            }

            if ((add_phone != "") && !self.checkUniquePhone(add_phone)) {
                valid = false;
                $('.error_span').remove();
                showErrorLabel('Данный номер уже есть в базе!', $('.new_phone'));
            }
        });

        $('.elreary_exist_phone').each(function () {
            var new_phone = $(this).val().replace(/\+/, '').replace(/-/g, '');
            var old_phone_id = $(this).data('old_phone_id');
            var old_phone = $(this).data('phone');

            if (old_phone != new_phone) {

                if ((new_phone != "") && !self.checkUniquePhoneForChange(new_phone)) {
                    valid = false;
                    $('.error_span').remove();
                    showErrorLabel('Данный номер уже есть в базе!', $('.elreary_exist_phone'));
                } else {
                    if (self.isValidPhone($(this).val())){
                        self.changeExistPhone(old_phone_id, new_phone, $(this));
                        $(this).data('phone', new_phone);
                    } else {
                        valid = false;
                        $('.error_span').remove();
                        showErrorLabel('В поле должен быть введён корректный телефон! Пример: +7-495-111-11-11', $('.elreary_exist_phone'));
                    }
                };
            };
        });

        if (!valid) return;

        $('#save_info_button').prop("disabled", true);

        $('.new_phone').each(function () {
            if ($(this).val())
                self.phone_numbers.push($(this).val());
        });

        self.readValues();
        var options = {
            nick: self.nick,
            first_name: self.first_name,
            last_name: self.last_name,
            middle_name: self.middle_name,
            sex_id: self.gender,
            birthday_date: self.birthday_date,
            phone_numbers: self.phone_numbers,
            email: self.email,
            city: self.city
        };

        Ajax.Post('/account/ajaxSaveAbout', options, function (data) {
            if (data.status == 0) {
                self.changed_flag = false;
                $('.new_phone').each(function () {
                    if ($(this).val() == '') {
                        $(this).parent().parent().parent().remove();
                    }
                });
                $('.new_phone').attr('disabled', 'disabled');
                $('.new_phone').removeClass('new_phone');

                self.phone_numbers = [];
                if (self.link_to_page)
                {
                    window.location = self.link_to_page;
                } else {
                    var modal_window = new ModalWindow();
                    modal_window.showDefaultPopup('Изменения сохранены!');
                    self.getCurrentInfo();
                }

            }
            $('#save_info_button').prop("disabled", false);
        });
    };

    this.isValidPhone = function (phone) {
        return /^\+?7\-?[0-9]{3}\-?[0-9]{3}\-?[0-9]{2}\-?[0-9]{2}$/.test(phone);
    }

    this.checkUniquePhone = function (phone) {

        var result = true;
        phone = phone.replace(/[^0-9]/, '');
        Ajax.SyncPost(
            '/ajax/checkUnique',
            {
                fields: 'account_phone.phone',
                value: phone,
                check_account_id: false
            },
            function (data) {
                if (data.result == false) {
                    result = false;
                }
            }
        );

        return result;
    };

    this.setError = function (el, message) {

        var error = $('<div class="error-msg-phone">' + message + '</div>');
        var pos = el.position();

        error.css({
            top: 0,
            display: 'block'
        });

        error.css('display', 'block').delay(2000).fadeOut(1000);
        el.after(error);
    }

    this.closeNote = function () {
        Ajax.Post('/ajax/closeNote', {note_type: self.note_type}, function (data) {
            if (data.status == 0) {
                $('#close_about_note').parent().parent().fadeOut();
            }
        });
    };

    this.showPopup = function (text) {
        $('#success-popup .success-txt').html(text);
        $('#success-popup-link').click();
    }

    this.showAskToSavePopup = function (text) {
        $('#ask-to-save-link').click();
    }

    this.changeExistPhone = function (old_phone_id, new_phone, el) {
        var options = {
            old_phone_id: old_phone_id,
            new_phone: new_phone
        };
        Ajax.Post('/ajax/changeExistPhone', options, function (data) {
            if (data.result.deleted) {
                el.parent().parent().parent().remove();
            }
            if (data.status != 0) {
                showValidationError(el, 'Ошибка при изменения номера!');
            }
        });
    }

    this.checkUniquePhoneForChange = function (phone) {

        var result = true;
        phone = phone.replace(/\+/, '').replace(/-/g, '');
        Ajax.SyncPost(
            '/ajax/checkUniquePhone',
            {
                phone: phone
            },
            function (data) {
                if (data.result == false) {
                    result = false;
                }
            }
        );

        return result;
    };

    this.isChangeinfo = function () {
        return self.changed_flag;
    };

    this.getCurrentInfo = function () {
        options = {
            account_id: self.current_account_id
        };
        Ajax.Post('/ajax/getAccountInfo', options, function (data) {
            if (data.status == 0) {
                self.current_nick = (data.result.nick) ? data.result.nick : '';
                self.current_first_name = (data.result.first_name) ? data.result.first_name : '';
                self.current_last_name = (data.result.last_name) ? data.result.last_name : '';
                self.current_middle_name = (data.result.middle_name) ? data.result.middle_name : '';
                self.current_gender = (data.result.sex_id) ? data.result.sex_id : '';
                self.current_birthday = (data.result.birthday) ? data.result.birthday : '';
                self.current_city = (data.result.city_id) ? data.result.city_id : '';
                self.current_email = (data.result.email) ? data.result.email : '';

                self.checkPhones();
            };
        });
    };

    this.checkPhones = function () {
        $('.elreary_exist_phone').each(function () {

            if ($(this).data('phone') != $(this).val().replace(/\+/, '').replace(/-/g, '')) {
                self.show_popup = true;
            } else {
                self.show_popup = false;
            };

        });
    };

    this.updateDropDownList = function () {

        var text = $('.city-search-input').val();

        if (text.length > 1) {
            Ajax.Post('/ajax/getCities', {query:text, aboute_page:1}, function (data) {
                if (data.status == 0) {
                    $('.search-city-block .drop-menu').html(data.result);
                    $('.search-city-block .drop-menu').slideDown();
                }

                if (data.status == 2) {
                    $('.search-city-block .drop-menu').slideUp();
                    $('.search-city-block .drop-menu').html('');
                }
            });
        } else {
            $('.search-city-block .drop-menu').slideUp();
            $('.search-city-block .drop-menu').html('');
        }
    };
}
var PersonalRoomAboutFbAccountController = function () {

    this.profile_url;
    this.user_name;
    this.hometown;
    this.bio;
    this.quotes;
    this.political_view;
    this.is_interested_in_male;
    this.is_interested_in_female;
    this.relationship_status;
    this.religion;
    this.web_sites;

    var controller = this;

    this.init = function () {

        $('#save_fb_account').click(function () {
            controller.profile_url = $('#fb_profile_url').val();
            controller.user_name = $('#fb_user_name').val();
            controller.hometown = $('#fb_hometown').val();
            controller.bio = $('#fb_bio').val();
            controller.quotes = $('#fb_quotes').val();
            controller.political_view = $('#fb_political_view').val();
            controller.is_interested_in_male = ($('#fb_is_interested_in_male').is(':checked')) ? 1 : null;
            controller.is_interested_in_female = ($('#fb_is_interested_in_female').is(':checked')) ? 1 : null;
            controller.relationship_status = $('#fb_relationship_status').val();
            controller.religion = $('#fb_religion').val();
            controller.web_sites = $('#fb_web_sites').val();
            controller.saveMainFbInfo();

        });
    };

    this.saveMainFbInfo = function () {
        Ajax.Post('/account/ajaxSaveFbAccountInfo', {
                profile_url: controller.profile_url,
                user_name: controller.user_name,
                hometown: controller.hometown,
                bio: controller.bio,
                quotes: controller.quotes,
                political_view: controller.political_view,
                is_interested_in_male: controller.is_interested_in_male,
                is_interested_in_female: controller.is_interested_in_female,
                relationship_status: controller.relationship_status,
                religion: controller.religion,
                web_sites: controller.web_sites
            },
            function (data) {
                if (data.status == 0) {
                    showOk('Данные сохранены')
                    window.location = '/account/about';
                } else {
                    showError('Ошибка!');
                }
            }
        );
    }
};
var PersonalRoomAboutMailruAccountController = function () {

    this.profile_url;
    this.nick_name;
    this.status_text;

    var controller = this;

    this.init = function () {

        $('#save_mailru_account').click(function () {
            controller.profile_url = $('#mailru_profile_url').val();
            controller.nick_name = $('#mailru_nick_name').val();
            controller.status_text = $('#mailru_status_text').val();

            controller.saveMainMailruInfo();

        });
    };

    this.saveMainMailruInfo = function () {
        Ajax.Post('/account/ajaxSaveMailruAccountInfo', {
                profile_url:controller.profile_url,
                nick_name:controller.nick_name,
                status_text:controller.status_text
            },
            function (data) {
                if (data.status == 0) {
                    showOk('Данные сохранены')
                    window.location = '/account/about';
                } else {
                    showError('Ошибка!');
                }
            }
        );
    }
};
var PersonalRoomAboutOkAccountController = function () {

    this.profile_url;
    this.age;

    var controller = this;

    this.init = function () {

        $('#save_ok_account').click(function () {
            controller.profile_url = $('#ok_profile_url').val();
            controller.age = $('#ok_age').val();

            controller.saveMainOkInfo();

        });
    };

    this.saveMainOkInfo = function () {
        Ajax.Post('/account/ajaxSaveOkAccountInfo', {
                profile_url:controller.profile_url,
                age:controller.age
            },
            function (data) {
                if (data.status == 0) {
                    showOk('Данные сохранены')
                    window.location = '/account/about';
                } else {
                    showError('Ошибка!');
                }
            }
        );
    }
};
var PersonalRoomAboutVkAccountController = function () {

    this.profile_url;
    this.home_phone;
    this.activity;
    this.relation_type;
    this.interests;
    this.movies;
    this.tv;
    this.books;
    this.games;
    this.about;

    var controller = this;

    this.init = function () {

        $('#save_vk_account').click(function () {
            controller.profile_url = $('#vk_profile_url').val();
            controller.home_phone = $('#vk_home_phone').val();
            controller.vk_activity = $('#vk_activity').val();
            controller.relation_type = $('#vk_relation_type').val();
            controller.interests = $('#vk_interests').val();
            controller.movies = $('#vk_movies').val();
            controller.tv = $('#vk_tv').val();
            controller.books = $('#vk_books').val();
            controller.games = $('#vk_games').val();
            controller.about = $('#vk_about').val();
            controller.saveMainVkInfo();

        });
    };

    this.saveMainVkInfo = function () {
        Ajax.Post('/account/ajaxSaveVkAccountInfo', {
                profile_url:controller.profile_url,
                home_phone:controller.home_phone,
                activity:controller.activity,
                relation_type:controller.relation_type,
                interests:controller.interests,
                movies:controller.movies,
                tv:controller.tv,
                books:controller.books,
                games:controller.games,
                about:controller.about
            },
            function (data) {
                if (data.status == 0) {
                    showOk('Данные сохранены')
                    window.location = '/account/about';
                } else {
                    showError('Ошибка!');
                }
            }
        );
    }
};
var PersonalRoomDoctorsVisitsPastController = function () {
    var controller = this;

    this.note_type = '';

    this.init = function () {

        $(document).on('click','#close_settings_note', function(){
            controller.note_type = 'doctors_visits_past_note';
            controller.closeNote();
        });
    };

    this.closeNote = function () {
        Ajax.Post('/ajax/closeNote', {note_type: controller.note_type}, function (data) {
            if (data.status == 0)
            {
                $('#close_settings_note').parent().parent().fadeOut();
            }
        });
    };
}
var PersonalRoomFamilyController = function () {

    this.first_name = '';
    this.last_name = '';
    this.middle_name = '';
    this.phone = 0;
    this.email = '';
    this.family_relation_status_id = 0;
    this.relation_id = 0;

    var self = this;
    this.init = function () {

        var controller = this;
        controller.getAccountRelations();

        $('input[name="phone"]').inputmask('+7-999-999-99-99');

        $('#add-relation-button').validation({
            validate: [
                $('input[name="phone"]').validate(validation_rules['phone_right']),
                $('input[name="email"]').validate(validation_rules['email_right']),
                $('input[name="first_name"]').validate(validation_rules['required']),
                $('input[name="last_name"]').validate(validation_rules['required']),
                $('input[name="middle_name"]').validate(validation_rules['required'])
            ],
            callback: controller.sendFamilyRequest
        });

        $(document).on('click', '.delete-relation', function () {
            controller.relation_id = $(this).attr("data-id");
            controller.relation_table = $(this).attr("class");
            controller.deleteRelation();
        });

        $(document).on('click', '.delete-relation-moderate', function () {
            controller.relation_id = $(this).attr("data-id");
            controller.relation_table = $(this).attr("class");
            controller.deleteRelation();
        });

        $(document).on('click', '.confirm-relation', function () {
            controller.relation_id = $(this).attr("data-id");
            controller.confirmRelation();
        });
    };

    this.deleteRelation = function () {
        controller1 = this;
        Ajax.Post('/account/ajaxDeleteFamilyRelation', {relation_id: this.relation_id, relation_table: this.relation_table}, function (data) {
            if (data.result == true) {
                //self.showPopup('Связь удалена');
                controller1.getAccountRelations();
            }
            else self.showPopup('Не получилось удалить связь!');
        });
    };

    this.confirmRelation = function () {
        controller1 = this;
        Ajax.Post('/account/ajaxConfirmFamilyRelation', {relation_id: this.relation_id}, function (data) {
            if (data.result == true) {
                controller1.getAccountRelations();
            }
            else self.showPopup('Не получилось подтвердить связь!');
        });
    };

    this.getAccountRelations = function () {
        Ajax.Post('/account/ajaxGetAccountRelations', {}, function (data) {
            if (data.status == 0){
                $('.account-relations').html(data.result.account_relations);
                $('.cab-family > h2').remove();
            } else if (data.status == 26){
                $('.account-relations').html('Родственные связи отсутсвуют');
            }
        });
    };

    this.sendFamilyRequest = function () {
        self.first_name = $('input[name="first_name"]').val();
        self.last_name = $('input[name="last_name"]').val();
        self.middle_name = $('input[name="middle_name"]').val();

        self.family_relation_status_id = $('.add-relation-status').val();

        if ($('input[name="phone"]').val() != '+7-___-___-__-__')
            self.phone = $('input[name="phone"]').val();
        else
            self.phone = '';

        self.email = $('input[name="email"]').val();
        Ajax.Post('/account/ajaxSaveFamilyRelation', {
            first_name: self.first_name,
            last_name: self.last_name,
            middle_name: self.middle_name,
            phone: self.phone,
            email: self.email,
            family_relation_status_id: self.family_relation_status_id},

            function (data) {
            if (data.result.add_relation == true) {
                self.showPopup('Пользователю отправлен запрос для подтверждения');
                $('#cab-form').trigger('reset');
                $('input[name="phone"]').val('');
            }
            else self.showPopup(data.result.warning);
        });
    };

    this.showPopup = function (text) {
        $('#success-popup .success-txt').html(text);
        $('#success-popup-link').click();
    }
}
var PersonalRoomMyClinicSearchFormController = function (account_id) {

    var self = this;

    this.account_id = account_id;
    this.type_of_clinic = null;
    this.purpoise_of_visit = null;
    this.page = 1;

    this.init = function () {

        $('#view_more_visited_clinics i').addClass('icon-loader');
        this.sendRequest();

        $('#type_of_clinic').change(function () {
            self.type_of_clinic = $(this).val();
            self.purpoise_of_visit = $('#purpose_of_visit').val();
            self.page = 1;
            self.sendRequest();
        });

        $('#purpose_of_visit').change(function () {
            self.purpoise_of_visit = $(this).val();
            self.type_of_clinic = $('#type_of_clinic').val();
            self.page = 1;
            self.sendRequest();
        });

        $(document).on('click', '#view_more_visited_clinics', function () {
            $('#view_more_visited_clinics i').addClass('icon-loader');
            self.sendRequest();
        });

        favorite_clinics_form_controller = new FavoriteClinicsFormController(self.account_id);
        favorite_clinics_form_controller.init();
    };

    this.sendRequest = function () {
        var options = {
            type_of_clinic: self.type_of_clinic,
            purpoise_of_visit : self.purpoise_of_visit,
            account_id: self.account_id,
            page: self.page

        };
        Ajax.Post('/account/ajaxGetClinicsListByPastVisit', options, function (data) {

                if (data.status == 0) {
                    if (self.page == 1)
                    {
                        $('#visited_clinic_container').html(data.result.html);
                        $('#view_more_visited_clinics i').removeClass('icon-loader');
                    } else {
                        $('#visited_clinic_container').append(data.result.html);
                        $('#view_more_visited_clinics i').removeClass('icon-loader');
                    }

                    if (data.result.more_button == 0) {
                        $('#more_visited_clinics').css('display', 'none');
                    } else {
                        $('#more_visited_clinics').css('display', 'block');
                        $('#view_more_visited_clinics i').removeClass('icon-loader');
                    }
                    self.page++;
                } else if (data.status == 2){
                    $('#visited_clinic_container').html('Тут будут клиники, которые вы посетили');
                }

            }

        );
    };
};
var PersonalRoomMyDiseaseController = function (letter) {

    var self = this;

    self.my_disease_id = null;
    self.read_disease_id = null;

    this.init = function () {

        $('.all-letters').addClass('current');

        if (letter)
        {
            $('.letters').children().removeClass('current');
            $('.letters .letter-'+letter).addClass('current');
        }

        $('.archive_button').click(function () {
            self.my_disease_id = $(this).data('my_disease_id');
            if (self.my_disease_id){
                self.addToArchive();
            }


        });

        $('.btn-4').click(function () {
            self.read_disease_id = $(this).attr('data-id');
            Ajax.Post('/ajax/readAboutDisease', {read_disease_id: self.read_disease_id}, function (data) {
                if (data.result.ready_disease) {
                    window.location ='/disease/get?id='+data.result.ready_disease;
                }
                else {
                    self.showPopup('Этот текст правят наши редакторы');
                }
            });
        });
    }

    this.addToArchive = function () {
        Ajax.Post('/disease/ajaxAddToArchive', {my_disease_id: self.my_disease_id}, function (data) {
            if (data.status == 0) {
                $('#archive_buttons_' + self.my_disease_id).css('display', 'none');
                $('#archive_buttons_' + self.my_disease_id).parent().parent().remove();
            }
        });
    };

    this.showPopup = function (text) {
        $('#success-popup .success-txt').html(text);
        $('#success-popup-link').click();
    }
}

var PersonalRoomMyDoctorSearchFormController = function (account_id) {

    var self = this;

    this.account_id = account_id;
    this.clinic_id = null;
    this.specialty_id = null;
    this.purpose_of_visit_id = null;
    this.time_of_visit = null;
    this.page = 1;

    this.init = function () {
        /***** Search Form *****/
        $('#doctor_search_form select[name="clinic_id"]').change(function () {
            self.clinic_id = $(this).val();
            self.page = 1;

            self.sendRequest();
        });

        $('#doctor_search_form select[name="specialty_id"]').change(function () {
            self.specialty_id = $(this).val();
            self.page = 1;
            self.loadPurposeOfVisitBlock();

            self.sendRequest();
        });

        $('#doctor_search_form select[name="time_of_visit"]').change(function () {
            self.time_of_visit = $(this).val();
            self.page = 1;
            self.sendRequest();
        });

        $(document).on('change', '#doctor_search_form select[name="purpose_of_visit_id"]', function () {
            self.purpose_of_visit_id = $(this).val();
            self.page = 1;

            self.sendRequest();
        });

        /*****   Past Visited Doctors *****/
        $('#more_visited_doctors i').addClass('icon-loader');
        this.sendRequest();

        $(document).on('click', '#more_visited_doctors', function () {
            $('#more_visited_doctors i').addClass('icon-loader');
            self.sendRequest();
        });

        favorite_doctors = new FavoriteDoctorsFormController(self.account_id);
        favorite_doctors.init();

    };

    this.loadPurposeOfVisitBlock = function (value) {
        Ajax.Post('/ajax/getPurposesOfVisitBySpecialtyId', {specialty_id: self.specialty_id}, function (data) {
            if (data.status == 0) {
                $('#purpose_of_visit_block').html(data.result);

                $('#purpose_of_visit_block select[name="purpose_of_visit_id"]').css('width', '407px');

                $(".chzn-select").chosen();
                $(".chzn-select-deselect").chosen({allow_single_deselect: true});
            }
        });
    };

    this.sendRequest = function () {
        var data = {
            specialty_id: self.specialty_id,
            clinic_id: self.clinic_id,
            purpose_of_visit_id: self.purpose_of_visit_id,
            time_of_visit: self.time_of_visit,
            page: self.page
        };

        Ajax.Post('/account/ajaxGetDoctorsListByPastVisit', data, function (data) {
            if (data.status == 2) {
                $('#visited_doctors_container').html('<div class="no_result">Тут будут врачи, которых вы посетили</div>');
                $('#view_more_doctors').css('display', 'none');
            }

            if (data.status == 0) {
                if (self.page == 1)
                {
                    $('#visited_doctors_container').html(data.result.html);
                    $('#more_visited_doctors i').removeClass('icon-loader');
                } else {
                    $('#visited_doctors_container').append(data.result.html);
                }

                if (data.result.more_button == 0) {
                    $('#visited-doctors-block .view-more-block').css('display', 'none');
                } else {
                    $('#visited-doctors-block .view-more-block').css('display', 'block');
                    $('#more_visited_doctors i').removeClass('icon-loader');
                }
                self.page++;
            }
        });
    };
}
var PersonalRoomOptionsController = function () {
    var controller = this;

    this.phone_id = 0;
    this.note_type = '';

    this.init = function () {


        if ($('.phone-settings-checkbox-main').hasClass('act'))
            $('.options-mobile').fadeIn();
        else
            $('.options-mobile').fadeOut();

        $(document).on('click','#close_settings_note', function(){
            controller.note_type = 'settings_note';
            controller.closeNote();
        });

        $(document).on('click', '#save-options-button', function () {
            if ($('#mail-checkbox-1').val() == 1) controller.mail_checkbox_1 = 1;
            else controller.mail_checkbox_1 = 0;
            if ($('#mail-checkbox-2').val() == 1) controller.mail_checkbox_2 = 1;
            else controller.mail_checkbox_2 = 0;
            if ($('#mail-checkbox-3').val() == 1) controller.mail_checkbox_3 = 1;
            else controller.mail_checkbox_3 = 0;
            if ($('#sms-checkbox-1').val() == 1) controller.sms_checkbox_1 = 1;
            else controller.sms_checkbox_1 = 0;
            if ($('#sms-checkbox-2').val() == 1) controller.sms_checkbox_2 = 1;
            else controller.sms_checkbox_2 = 0;
            if ($('#sms-checkbox-3').val() == 1) controller.sms_checkbox_3 = 1;
            else controller.sms_checkbox_3 = 0;
            if ($('#sms-checkbox-main').val() == 1) {
                controller.sms_checkbox_main = 1;
            }
            else {
                controller.sms_checkbox_main = 0;
            }
            controller.phone_id = $('.options-phones').val();

            Ajax.Post('/account/ajaxEditPersonalNotificationSettings', {mail_checkbox_1:controller.mail_checkbox_1, mail_checkbox_2:controller.mail_checkbox_2, mail_checkbox_3:controller.mail_checkbox_3, sms_checkbox_1:controller.sms_checkbox_1, sms_checkbox_2:controller.sms_checkbox_2, sms_checkbox_3:controller.sms_checkbox_3, sms_checkbox_main:controller.sms_checkbox_main, phone_id:controller.phone_id}, function (data) {
                if (data.result == true) {
                    controller.showPopup('Изменения сохранены!');
                }
            });
        });

        $(document).on('change', '.options-phones', function () {
            controller.phone_id = $('.options-phones').val();
        });

        $(document).on('click', '.phone-settings-checkbox-main', function () {
            if ($('#sms-checkbox-main').val() != 1) {
                $('.phone-settings-checkbox').removeClass('act');
                $('#sms-checkbox-1').val(0);
                $('#sms-checkbox-2').val(0);
                $('#sms-checkbox-3').val(0);
            }
            else {
                $('.phone-settings-checkbox').addClass('act');
                $('#sms-checkbox-1').val(1);
                $('#sms-checkbox-2').val(1);
                $('#sms-checkbox-3').val(1);
            }
        });
    };

    this.closeNote = function () {
        Ajax.Post('/ajax/closeNote', {note_type: controller.note_type}, function (data) {
            if (data.status == 0)
            {
                $('#close_settings_note').parent().parent().fadeOut();
            }
        });
    };

    this.showPopup = function(text)
    {
        $('#success-popup .success-txt').html(text);
        $('#success-popup-link').click();
    }

}
var PersonalRoomReviewsController = function (account_id) {

    var self = this;

    self.doctor_page = 1;
    self.clinic_page = 1;
    self.account_id = account_id;
    self.note_type = '';

    this.init = function () {

        $.fn.raty.defaults.path = '/media/images';

        $('.rev-rating').raty({
            starOn: 'star-on-big.png',
            starOff: 'star-off-big.png',
            width: 210,
            cancel: true
        });

        // reviews
        $('.switch').each(function () {
            $(this).find('li').each(function (i) {
                $(this).click(function () {
                    $(this).addClass('active').siblings().removeClass('active')
                        .parents('.rev-block').find('.section').eq(i).fadeIn(150).siblings('.section').hide();
                });
            });
        });

        self.getDoctorsReviews();
        self.getClinicsReviews();

        $(document).on('click', '#more_doctor_reviews .view-more', function () {
            $('#more_doctor_reviews .view-more i').addClass('icon-loader');
            self.getDoctorsReviews();
        });

        $(document).on('click', '#more_clinic_reviews .view-more', function () {
            $('#more_clinic_reviews .view-more i').addClass('icon-loader');
            self.getClinicsReviews();
        });

        $('#close_review_note').click(function () {
            self.note_type = 'review_note';
            self.closeNote();
        });
    };

    this.getDoctorsReviews = function () {
        var options = {
            page: self.doctor_page,
            account_id: self.account_id
        };

        Ajax.Post('/account/ajaxGetDoctorsReviews', options, function (data) {

                if (data.status == 0) {

                    if (self.doctor_page == 1)
                    {
                        $('#last_doctors_reviews_container').html(data.result.html);
                        $('#more_doctor_reviews .view-more i').removeClass('icon-loader');
                    } else {
                        $('#last_doctors_reviews_container').append(data.result.html);
                        $('#more_doctor_reviews .view-more i').removeClass('icon-loader');
                    }

                    if (data.result.more_button == 0) {
                        $('#more_doctor_reviews').css('display', 'none');
                    } else {
                        $('#more_doctor_reviews').css('display', 'block');
                        $('#more_doctor_reviews .view-more i').removeClass('icon-loader');
                    }
                    self.doctor_page++;
                }
            }
        );
    };

    this.getClinicsReviews = function () {
        var options = {
            page: self.clinic_page,
            account_id: self.account_id
        };

        Ajax.Post('/account/ajaxGetClinicsReviews', options, function (data) {

                if (data.status == 0) {

                    if (self.clinic_page == 1)
                    {
                        $('#clinics_reviews_container').html(data.result.html);
                        $('#more_clinic_reviews .view-more i').removeClass('icon-loader');
                    } else {
                        $('#clinics_reviews_container').append(data.result.html);
                        $('#more_clinic_reviews .view-more i').removeClass('icon-loader');
                    }

                    if (data.result.more_button == 0) {
                        $('#more_clinic_reviews').css('display', 'none');
                    } else {
                        $('#more_clinic_reviews').css('display', 'block');
                        $('#more_clinic_reviews .view-more i').removeClass('icon-loader');
                    }
                    self.clinic_page++;
                }
            }
        );
    }

    this.closeNote = function () {
        Ajax.Post('/ajax/closeNote', {note_type: self.note_type}, function (data) {
            if (data.status == 0) {
                $('#close_review_note').parent().parent().fadeOut();
            }
        });
    };
}
var PersonalRoomTrustController = function (in_process_sn) {
    var self = this;

    self.confirm_code = '';
    self.phone;
    self.in_process_sn = in_process_sn;

    this.init = function () {

        if (self.in_process_sn == 1) {
            attachFancybox($('#success-popup-link'));
            self.showPopup('Аккаунт уже используется в системе');
        }

        /*отправка подтверждения регистрации на email*/
        $('#confirm_email').click(function () {
            var email = $(this).data('email');
            if (email) {
                Ajax.Post('/account/sendEmailConfirmationMessage',
                    {email: email},
                    function (data) {
                        if (data.status == 0) {
                            self.showPopup('Письмо с подтверждением выслано на Ваш email!');
                        } else {
                            self.showPopup('Ошибка при отправке письма. <br> Попробуйте позже.')
                        }
                    }
                );
            }
        });

        $(".confirm-phone-link").click(function(){
            if ($(this).data('clicked') == 1)
                return;

            $(this).data('clicked', 1);

            var confirm_phone_form_controller = new ConfirmPhoneController();
            confirm_phone_form_controller.setPhoneId($(this).data('phone-id'));
            confirm_phone_form_controller.tmp_init();

            $(this).data('clicked', 0);
        });



    };


    this.confimPhone = function () {
        Ajax.Post('/account/ajaxConfirmPhoneCode',
            {confirm_code: self.confirm_code, phone: self.phone},
            function (data) {
                if (data.status == 0) {
                    $('.fancybox-close').click();
                    $('.not_confrim_block').addClass('item-approved');
                } else {
                    $('.intro').text('Неверный код!');
                }
            }
        );
    };

    this.showPopup = function (text) {
        $('#success-popup .success-txt').html(text);
        $('#success-popup-link').click();
    }

}
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

        Ajax.Post('/ajax/getPopup', data, function (data) {
            if (data.status == 0) {
                var popup = new Popup();
                popup.show(data.result.html, '740px');
            }
        });
    };
};
var RecordToTheDoctorBlockController = function (doctor_id, button,visit_id ) {
    var self = this;

    this.button = button;
    this.doctor_id = doctor_id;
    this.visit_id = visit_id;
    this.container = null;

    this.selected_schedule_id = null;
    this.purpose_of_visit_id = null;

    this.family_relation_status_id = null;

    this.step = 1;

    this.full_name = null;
    this.phone = null;

    this.day_schedule_id = button.attr('data');

    this.changeable_schedule_id = null;

    this.popup = null;

    this.blocked_flag = false;

    this.init = function () {

        self.container = '#record-to-the-doctor-popup-' + self.doctor_id;

        data = {
            visit_id: self.visit_id,
            doctor_id:self.doctor_id,
            type:'record_to_the_doctor',
            schedule_id: self.day_schedule_id
        };


        Ajax.Post('/ajax/getPopup', data, function (data) {
            if (data.status == 0) {
                var block_code = data.result.html;

                self.popup = new Popup();
                self.popup.show(block_code);
                //showPopup(block_code);
                //Popup.locks++;

                if (self.day_schedule_id) {
                    $('.fancybox-inner').css('max-height',450);
                    $('.fancybox-wrap').css('max-height',450);
                    $('.fancybox-inner').css('width',620);
                    $('.fancybox-wrap').css('width',620);
                }


                $('.scroll-pane').jScrollPane();

                self.formTimeBlock(data.result.time_list);

                self.fillTimeBlock(data.result.schedule);

                if (self.day_schedule_id && !self.selected_schedule_id) {
                    self.selectScheduleId($(self.container + ' select[name="schedule_time"]').val());
                }

                $(self.container + ' .location-box .tabs').each(function () {
                    $(this).find('li').each(function (i) {
                        $(this).click(function () {
                            $('.day').removeClass('active');
                            var id = $(this).data('id');

                            $(this).addClass('active').siblings().removeClass('active')
                                .parents('.location-box').find('.section').eq(i).fadeIn(150).siblings('.section').hide();
                            $(self.container + ' .time-clinic-' + id).addClass('active');
                        });
                    });
                });

                if (visit_id)
                    self.initializeData();

                if(window.doctor_form_controller)
                {
                    self.purpose_of_visit_id = window.doctor_form_controller.purpose_of_visit_id;
                    $(self.container + ' select[name="purpose_of_visit_id"] option[value="'+window.doctor_form_controller.purpose_of_visit_id +'"]').attr('selected', true);
                }

                $(self.container + ' select[name="purpose_of_visit_id"]').change(function(){
                    self.purpose_of_visit_id = $(this).val();
                });

                $(self.container + ' .section.visible.flo').next('div.section.visible.flo').css('display', 'none');

                $('.time-li').click(function () {
                    if (!$(this).hasClass('clicked'))
                    {
                        $('.time-li').removeClass('clicked');
                        $(this).addClass('clicked');
                        self.selectScheduleId($(this).data('schedule_id'));
                    }
                });

                $(self.container + ' input.resume-btn').click(self.submitFirstStep);

                $('.fancybox-overlay, .fancybox-close').click( function(event){
                    /*
                    if( $(event.target).closest('#record-to-the-doctor-popup-' + self.doctor_id).length )
                        return;
                    $('.fancybox-placeholder').remove();
                    $('.fancybox-wrap').remove();
                    $('.fancybox-overlay').remove();
                    $('body').removeClass('fancybox-lock');
                    $('#record-to-the-doctor-popup-' + self.doctor_id).remove();

                    if (!visit_id) {
                        Ajax.Post('/ajax/skipReservedTime', {schedule_id: self.selected_schedule_id}, function (data) {
                            if (data.status == 0)
                             self.selected_schedule_id = null;
                        });
                    }
                    else if (self.changeable_schedule_id) {
                        Ajax.Post('/ajax/restoreReservedTime', {schedule_id: self.changeable_schedule_id, selected_schedule_id: self.selected_schedule_id}, function () {
                        });
                    }
                    event.stopPropagation();
                    */
                });

                $(self.container + ' input.send-button').validation({
                    validate : [
                        $(self.container + ' input[name="surname"]').validate(validation_rules['full_name']),
                        $(self.container + ' input[name="phone"]').validate(validation_rules['visit_phone'])
                    ],
                    callback : self.sendData
                });

                $(self.container + ' select[name="schedule_time"]').change(function () {
                    self.selectScheduleId($(this).val());
                });

                setChosenSelect();
                $('.chekBox').click(function(){
                    $(this).toggleClass('act');
                });

                self.attachCarousel();

                $('input[name="phone"]').inputmask('+7-999-999-99-99');
            }
        });
    };

    this.submitFirstStep = function(){

        if (!self.selected_schedule_id){
            self.showError('Пожалуйста, выбери время');
            return;
        }

        self.step = 2;
        $('.steps').removeClass('current');
        $('.step-2').addClass('current');
        $('.fancybox-inner').css('max-height',390);
        $('.fancybox-inner').css('overflow','visible');
        $('.fancybox-wrap').css('width',569);
        $('.fancybox-inner').css('width',569);
        $('.step-block-1').css('display', 'none');
        $('.step-block-2').css('display', 'block');
        $('.error-msg').css('display','none');

        /*$.extend($.inputmask.defaults.definitions, {
            'g': {
                "validator": "[А-Яа-я0-9A-Za-z\- ]",
                "cardinality": 1,
                'prevalidator': null
            }
        });
        $('input[name="surname"]').inputmask({ "mask": 'g', "repeat": 1000, "greedy": false });*/
    };

    this.sendData = function(){
        if (self.blocked_flag)
            return;

        if (!self.purpose_of_visit_id){
            self.showError('Пожалуйста, укажи цель визита');
            return;
        };

        self.blocked_flag = true;

        self.full_name = $(self.container + ' input[name="surname"]').val();
        self.phone = $(self.container + ' input[name="phone"]').val();

        self.family_relation_status_id = $(self.container + ' select[name="family_relation_status"]').attr('value');

        var data = {
            schedule_id: self.selected_schedule_id,
            purpose_of_visit_id: self.purpose_of_visit_id,
            family_relation_status_id : self.family_relation_status_id,
            full_name: self.full_name,
            phone: self.phone,
            doctor_id: self.doctor_id,
            visit_id: self.visit_id
        };

        Ajax.Post('/ajax/recordToTheVisit', data, function(data){
            if (data.status == 0)
            {
                self.popup.close();
                var success_popup = new PopupMessage();
                success_popup.show('Поздравляем, вы успешно записались на прием. Ждите подтверждения!');
            }

            if (data.status == 9)
            {
                var error_popup = new PopupMessage();
                error_popup.show('Номер телефона используется на другом аккаунте.<br />' +
                                'Ты можешь: <br />' +
                                '1. Если это твой родственник, необходимо связать аккаунты в разделе Семья.<br />' +
                                '2. Обратиться в поддержку <a href="mailto:help@lookmedbook.com">help@lookmedbook.com</a>, описав полностью ситуацию и указав email аккаунта.');
            }

            if (data.status == 555)
            {
                var confirm_controller = new ConfirmPhoneController();
                confirm_controller.setPhoneId(data.data.phone_id);
                confirm_controller.setSuccessCallback(self.sendData);
                confirm_controller.tmp_init();
            }

            self.blocked_flag = false;
        });
    };

    this.showError = function(str){
        $('.error-msg-1').html(str);
        $('.error-msg-1').css('display', 'block');
        $('.error-msg-1').delay(2000).fadeOut(500);
    };

    this.selectScheduleId = function (schedule_id) {
        var data = {
            schedule_id:schedule_id,
            doctor_id:self.doctor_id,
            selected_schedule_id: self.selected_schedule_id
        };
        var schedule_id = schedule_id;
        if (visit_id && !self.changeable_schedule_id) self.changeable_schedule_id = self.selected_schedule_id;

        $('.time-li').removeClass('selected');
        $('.schedule-'+schedule_id).addClass('selected');
        self.selected_schedule_id = schedule_id;
        /*
        Ajax.Post('ajax/selectScheduleId', data, function(data){
            if (data.status == 0)
            {
                $('.time-li').removeClass('selected');
                $('.schedule-'+schedule_id).addClass('selected');
                self.selected_schedule_id = schedule_id;
            } else {
                //showError('Данное время уже занято. Пожалуйста, выберите другое');
                $('.schedule-'+schedule_id).addClass('unactive');
                $('.schedule-'+schedule_id).html('');
            }
        });*/
    };

    self.formTimeBlock = function (time_list) {
        var list = $('<div />');

        /*
        for (i in time_list) {

            li = $('<li/>', {
                class:'unactive time-li time-' + time_list[i].replace(':', '-')
            }).appendTo(list);
        }
        */

        // $(self.container + ' .time-scroll ul').html(list.html());
        $(self.container + ' .time-scroll ul').html();
    };

    self.fillTimeBlock = function (schedule) {
        //$('ul.time li.unactive').css('display', 'none');
        for (i in schedule) {
            dt_start = self.parseDateTime(schedule[i].dt_start);
            dt_end = self.parseDateTime(schedule[i].dt_end);
            el = $('ul.day-' + dt_start.day + ' li.time-' + dt_start.class);

            var li = $('<li></li>');
            li.addClass('time-' + dt_start.time);
            li.addClass('time-li');
            //li.addClass('time');
            li.data('schedule_id');
            li.html(dt_start.time);
            li.append('<br /> - <br />')
            li.append(dt_end.time);
            li.data('schedule_id', schedule[i].schedule_id);
            li.addClass('schedule-' + schedule[i].schedule_id);
            li.addClass('active');
            li.removeClass('unactive');

            $('ul.day-' + dt_start.day + ' li.unactive').before(li);
        }

    }

    self.parseDateTime = function (dt) {
        dt = dt.replace('-', '/');
        dt = dt.replace('-', '/');
        dt_obj = new Date(dt);

        month = dt_obj.getMonth() + 1;
        if (month < 10)
            month = '0' + month;
        day = dt_obj.getDate();
        if (day < 10)
            day = '0' + day;

        day = (1900 + dt_obj.getYear()) + '-' + month + '-' + day;

        hours = dt_obj.getHours();
        if (hours < 10)
            hours = '0' + hours;
        minutes = dt_obj.getMinutes();
        if (minutes < 10)
            minutes = '0' + minutes;

        return {
            day:day,
            time:hours + ':' + minutes,
            class:hours + '-' + minutes
        };
    };

    this.attachCarousel = function () {
        $('.schedule-extended .shedule-var').carouFredSel({
            synchronise:['.time-scroll .jspPane', false, true],
            auto:false,
            prev:'.prev-nav',
            next:'.next-nav',
            scroll:{items:7},
            circular:false,
            infinite:false
        });

        $('.time-scroll .jspPane').carouFredSel({
            auto:false,
            scroll:{items:7},
            circular:false,
            infinite:false,
            height:118
        });
    };

    this.initializeData = function() {
        Ajax.Post('/ajax/SetScheduleId', {visit_id: self.visit_id}, function (data) {
            if (data.result) {

                self.purpose_of_visit_id = data.result.purpose_of_visit_id;
                self.selected_schedule_id = data.result.schedule_id;
                self.full_name = data.result.full_name;
                self.phone = data.result.phone;

                var active_purpose = data.result.purpose_of_visit;
                $('.visit-target ul.chzn-results li').each(function(){
                    if ($(this).text() == active_purpose) {
                        $(this).addClass('result-selected');
                        $('.visit-target .chzn-single span').html(active_purpose);
                    }
                });

                var time_container = '.jspPane .day-'+data.result.day+' .time-'+data.result.time;
                $(time_container).removeClass('unactive');
                $(time_container).addClass('schedule-'+data.result.schedule_id);
                $(time_container).addClass('clicked');
                $(time_container).addClass('selected');
                $(time_container).html(data.result.dt_start);

                $('.step-block-2 .txt input[name=surname]').val(data.result.full_name);
                $('.step-block-2 .txt input[name=phone]').val(data.result.phone);

            }

        });
    };
};
var RegistrationFormController = function () {
    var self = this;

    self.email = '';
    self.passwrod = '';

    this.init = function () {

        setCounters('reg-begin', 'home-reg', 'home', 'guest');

        $('.reg-form #repeat_registration_password, .reg-form #password').keyup(function(e) {
            e = e || window.event;
            if(e.keyCode == 13){
                $('#registration-popup input.submit_registration').click();
            }
        });

        $('#registration-popup input.submit_registration').validation({
            validate : [
                $('#registration-popup input[name="email"]').validate(validation_rules['email']),
                $('#registration-popup #password').validate(validation_rules['password']),
                $('#registration-popup #repeat_registration_password').validate(validation_rules['password2'])
            ],
            callback : self.tryRegister
        });

        $('#login-popup-link').click(function(){
            login_form_controller = new LoginFormController();
            login_form_controller.init();
        });

        /*$('#registration-popup #password').blur(function(){
            console.log($(this).attr('placeholder'));
            $(this).attr('placeholder','Введите пароль');
        });

        $('#registration-popup #repeat_registration_password').focusout(function(){
            console.log($(this).attr('placeholder'));
            $(this).attr('placeholder','Повторите пароль');
        });*/

        $.extend($.inputmask.defaults.definitions, {
            'm': {
                "validator": "[А-Яа-я0-9A-Za-z\-\_\.@]",
                "cardinality": 1,
                'prevalidator': null
                //'placeholder': 'mail@example.com'
            }
        });

        $('#registration-popup input[name="email"]').inputmask({ "mask": 'm', "repeat": 255, "greedy": false });;
    };

    this.tryRegister = function(){

        self.email =  $('#registration-popup input[name="email"]').val();
        self.passwrod = $('#registration-popup input[name="password"]').val();

        var options = {
            email: self.email,
            password: self.passwrod
        };

        Ajax.Post('/account/registration', options, function(data){
            if (data.status == 0)
            {
                setCounters('reg-complete', 'home-reg', 'home', self.email);
                document.location = "/account";
            }
            else {
                setCounters('reg-complete', 'unknown', 'home', self.email);
                $('.error-msg-email').remove();
                self.setError($('#registration-popup input.submit_registration'), 'Ошибка при регистрации', 'error');
            }
        });
    };

    this.setError = function(el, message, type) {
        var top = -120;
        var left = 190;

        if (type = 'email'){
            var top = -280;
            var left = 170;
        };

        var error = $('<div class="error-msg-email">' + message + '</div>');
        var pos =  el.position();

        error.css({
            top: top,
            'margin-left': left,
            right: 10,
            position: 'relative',
            display: 'block'
        });
        error.css('display', 'block').delay(2000).fadeOut(1000);
        el.after(error);
    };
}

var ScheduleAndClinicsFormController = function (container) {

    var self = this;

    this.container = container;

    this.init = function () {

        $(self.container + ' .location-box .tabs').each(function () {
            $(this).find('li').each(function (i) {
                $(this).click(function () {
                    $('.day').removeClass('active');
                    var id = $(this).data('id');

                    $(this).addClass('active').siblings().removeClass('active')
                        .parents('.location-box').find('.section').eq(i).fadeIn(150).siblings('.section').hide();
                    $(self.container + ' .time-clinic-' + id).addClass('active');
                });
            });
        });

        $(self.container + ' .section.visible.flo').next('div.section.visible.flo').css('display', 'none');

        self.attachCarousel();
    };

    this.selectClinic = function (clinic_id) {
        $(self.container + ' .clinic-' + clinic_id + '-button').click();
    };

    this.attachCarousel = function () {
        $(self.container + " .vis-block").tabs();
        $(self.container + ' [data-jcarousel]').each(function () {
            var el = $(this);
            el.jcarousel(el.data());
        });

        $(self.container + " .schedule-extended ul").each(function (e) {
            $(this).nextAll('a').addClass('nav-' + e);
            $(self.container + " .schedule-extended ul").eq(e).carouFredSel({
                auto: false,
                prev: {
                    button: self.container + " .prev-nav"
                },
                next: {
                    button: self.container + " .next-nav"
                },
                scroll: {items: 1},
                circular: false,
                infinite: false
            });
        });
    };
}
var SetNewPasswordController = function (email, not_confirmed_email) {

    var self = this;
    self.email = email;
    self.not_confirmed_email = not_confirmed_email;

    this.init = function () {

        var options = {
            landing: true,
            type: 'set_new_password'
        };

        Ajax.Post('/ajax/getPopup', options, function (data) {
            if (data.status == 0) {
                var landing_new_pass_popup = data.result.html;

                showLandingForgotPassPopup(landing_new_pass_popup);

                $('#new-pass-popup').css('display', 'block');

                document.onkeyup = function (e) {
                    e = e || window.event;
                    if (e.keyCode === 13) {
                        $('#set_new_password').click();
                    }
                    // Отменяем действие браузера
                    return false;
                };

                $('#set_new_password').click(function(){
                    $('.error_span').remove();
                });;

                $('#set_new_password').validation({
                    validate: [
                        $('#set_password').validate(validation_rules['password']),
                        $('#set_repeat_password').validate(validation_rules['password_repeat'])
                    ],
                    callback: self.setNewPassword
                });

                if (self.not_confirmed_email == 1){
                    var check_box = '<div class="chekBox row flo"><span></span>Подтвердить email<input id="check_confirm" type="hidden" value=""></div>';
                    $('.repeat_pass').after(check_box)
                };

                $('.chekBox').click(function(){

                    $(this).toggleClass('act');

                    if ($(this).hasClass('act'))
                        $('#check_confirm').val('1');
                    else
                        $('#check_confirm').val('');
                })
            };
        });
    };

    this.setNewPassword = function () {

        var options = {
            email: self.email,
            password: $('#set_password').val(),
            is_confirm_email : $('#check_confirm').val()
        }

        Ajax.Post('/account/setNewPassword', options, function (data) {
                if (data.status == 0) {
                    $('#forgotpass-popup .intro').html('Пароль успешно изменен!')
                    window.location = '/account';
                } else {
                    $('.error-msg-rec-pass').remove();
                    self.setError($('#set_new_password'), 'Ошибка! попробуйте еще раз');
                }
            }
        );
    };

    this.setError = function (el, message) {
        var error = $('<div class="error-msg-rec-pass">' + message + '</div>');
        var pos = el.position();

        error.css({
            top: -120,
            'margin-left': 160,
            right: 10,
            position: 'relative',
            display: 'block'
        });
        error.css('display', 'block').delay(2000).fadeOut(1000);
        el.after(error);
    }
};
var SetPasswordLandingRegistrationController = function (page_url, email) {

    var self = this;
    this.page_url = page_url;
    this.email = email;

    this.init = function () {

        var options = {
            landing: true,
            type: 'set_new_password_on_landing'
        };

        Ajax.Post('/ajax/getPopup', options, function (data) {
            if (data.status == 0) {
                var landing_new_pass_popup = data.result.html;

                showLandingForgotPassPopup(landing_new_pass_popup);

                $('#new-pass-popup').css('display', 'block');

                document.onkeyup = function (e) {
                    e = e || window.event;
                    if (e.keyCode === 13) {
                        $('#set_new_password').click();
                    }
                    // Отменяем действие браузера
                    return false;
                };

                $('#set_new_password').validation({
                    validate: [
                        $('#set_password').validate(validation_rules['password']),
                        $('#set_repeat_password').validate(validation_rules['password_repeat'])
                    ],
                    callback: self.setNewPassword
                });
            };
        });

        /*attachFancybox($('#new_pass_popup_link'));
        $("#new_pass_popup_link").trigger('click');*/

    };

    this.setNewPassword = function () {

        var options = {
            email: self.email,
            password: $('#set_password').val()
        }
        Ajax.Post('/account/setLandingRegistrationPassword', options, function (data) {
                if (data.status == 0) {
                    $('#forgotpass-popup .intro').html('Пароль успешно изменен!')
                    window.location =  self.page_url;
                } else if (data.status == 32) {
                    window.location = '/?is_change_password=1';
                } else {
                    $('.error-msg-rec-pass').remove();
                    self.setError($('#set_new_password'), 'Ошибка! попробуйте еще раз');
                }
            }
        );
    };

    this.setError = function (el, message) {
        var error = $('<div class="error-msg-rec-pass">' + message + '</div>');
        var pos = el.position();

        error.css({
            top: -120,
            'margin-left': 160,
            right: 10,
            position: 'relative',
            display: 'block'
        });
        error.css('display', 'block').delay(2000).fadeOut(1000);
        el.after(error);
    }
};
var SexSelectFormController = function(container, gender)
{
    var self = this;

    this.container = container;
    this.gender = gender

    this.init = function(){

        if (self.gender == 1){
            $(self.container + ' .man').click();
        } else if (self.gender == 2) {
            $(self.container + ' .woman').click();
        } else {
            $(self.container + ' .man, ' + self.container + ' .woman').click();
        }
    };
}
var VisitRemindBlockController = function(visit_id, visits_page_flag, unique_el_id){
    var self = this;
    this.visit_id = visit_id;
    this.visits_page_flag = visits_page_flag;
    this.unique_el_id = unique_el_id;

    this.add_review_controller = null;

    this.init = function(){

        $('#'+self.unique_el_id).click(function(){
            $(this).fancybox();

            if (self.add_review_controller == null) {
                self.add_review_controller = new AddReviewBlockController(self.visit_id, self.visits_page_flag, self.unique_el_id);
                self.add_review_controller.init();
            };
        });
    };
};
var YandexMapController = function (form_controller) {
    this.container = 'map';

    this.drop_down_container = null;

    this.map = null;
    this.cluster = null;
    this.collection = null;
    this.placemarks = [];
    this.span = null;
    this.bounds = null;

    this.latitude = null;
    this.longitude = null;

    this.dataUrl = null;

    this.form_controller = form_controller;

    this.mode = 'small';

    var controller = this;

    var bounds = [];

    this.city_id = null;

    var self = this;

    this.setCoordinates = function(latitude, longitude) {

        self.latitude = latitude;
        self.longitude = longitude;

        self.bounds = [[latitude + 2, longitude - 2], [latitude - 2, longitude + 2]];

        //if (self.getMap() == null)
          //  self.setCoordinates(latitude, longitude);

        if (self.getMap() != null)
        {
            latitude = parseFloat(latitude);
            longitude = parseFloat(longitude);
            self.getMap().setBounds([
                [latitude - 0.1, longitude - 0.1],
                [latitude + 0.1, longitude + 0.1]
            ], {
                checkZoomRange: true
            });
            self.setMapCenter(self.latitude, self.longitude);
        }
    };

    this.setCityId = function(city_id){
        self.city_id = city_id;

        Ajax.Post('/ajax/getCityCoordinates', {}, function(data){
            if (data.result.latitude) {
                self.latitude = data.result.latitude;
                self.longitude = data.result.longitude;

                //self.setCoordinates(self.latitude, self.longitude);
                self.form_controller.page = 1;
                self.form_controller.sendRequest();
            }
        });
    };


    this.setCityInfo = function(city_info){
        //alert('Вызвался метод yandex_map');
        self.latitude = city_info.latitude;
        self.longitude = city_info.longitude;
        self.city_id = city_info.city_id;
    };

    this.init = function () {
        city_info = city_controller.getCurrentCityInfo();
        city_controller.subscribe(self.setCityInfo);
        var controllers = ('GNativeController,GCanvasController,YGeoObjectController,YNativeController,YCanvasController,YFullCanvasController,GFullCanvasController').split(',');
        var options = {
            controller:'YFullCanvasController',
            center:{ lat:parseFloat(city_info.latitude), lng:parseFloat(city_info.longitude)},
            zoom:12,
            debug:false,
            bounds: self.bounds
        };

        citymap = new CityMap($('#map')[0], options);
        this.initMap();
        self.map = citymap;
        $('#address-input').keypress(function (e) {
            if (e.which == 13) {
                self.geocode();
            }
        });

        $('#address-submit').click(self.geocode);

        self.drop_down_container = $('#address-drop');

        $(document).on('click', '.point-link', function () {
            var point = self.parsePoint($(this).data('point'));

            self.form_controller.latitude = point.latitude;
            self.form_controller.longitude = point.longitude;

            self.latitude = point.latitude;
            self.longitude = point.longitude;

            self.form_controller.is_metro = $(this).data('metro');
            self.form_controller.metro_station_name = $(this).data('station-name');
            self.form_controller.metro_branch_name = $(this).data('branch-name');

            self.form_controller.sendRequest();

            $('#address-input').val($(this).find('a').first().html());

            self.drop_down_container.slideUp();
            self.drop_down_container.html('');
            citymap.setData('');
        });

        $(".map-box .resize").click(function (e) {
            if (self.mode == 'small') {
                $("body").addClass('hidden');

                if (self.form_controller.container == '#clinic-search-form') {
                    self.setDataUrl('/ajax/getClinicMapCard?big=1&id=');
                }
                else if (self.form_controller.container == '#doctor-search-form') {
                    self.setDataUrl('/ajax/getDoctorClinicCard?big=1&id=');
                }
                self.initMap();
                $('.doc-popup-sm').remove();
                new_map_block = $('<div />', {
                    class: 'full-map',
                    style: 'height: 361px'
                });

                map_block = $('<div class="map-block"></div>');
                map_block.html($('#map'));
                new_map_block.html(map_block);
                //  new_map_block.append($('<div class="cards"></div>'));
                new_map_block.append($('<div class="bott-panel"><div class="shell"><span class="resize old_resize">Уменьшить</span><a href="#">Вернуться к результатам поиска</a></div></div>'));

                $('header').before(new_map_block);

                //$('#our-doctors .item-row:first').clone().appendTo('.full-map .cards');
                $("footer").hide();

                $(".full-map .resize, .full-map .shell a").click(function (e) {
                    e.preventDefault();
                    $("body").removeClass('hidden');
                    if (self.form_controller.container == '#clinic-search-form') {
                        self.setDataUrl('/ajax/getClinicMapCard?big=0&id=');
                    }
                    else if (self.form_controller.container == '#doctor-search-form') {
                        self.setDataUrl('/ajax/getDoctorClinicCard?big=0&id=');
                    }
                    self.initMap();
                    $('.ymaps-balloon-overlay .info-card').remove();
                    $("footer").show();
                    $('.map-box > *:first').before($('#map'));
                    $('#map').css({
                        width: '549px',
                        height: '472px'
                    });
                    if (citymap.controller.map)
                        citymap.controller.map.container.fitToViewport();
                    $('.full-map').remove();
                    self.mode = 'small';
                });

                $('.cards .info-card').mouseenter(function () {
                    $(this).animate({top: '-150'});
                });
                $('.cards .info-card').mouseleave(function () {
                    $(this).animate({top: '0'});
                });
                $('.cards .info-card').click(function (e) {
                    e.preventDefault();
                    $("body").removeClass('hidden');
                    $("footer").show();
                });

                $(window).resize(function () {
                    $('.full-map').height($(window).height());
                    $('.full-map .map-block').height($(window).height()-91);
                    $("#map").height($(window).height()-91);
                    $("#map").width($(window).width());
                });

                $("#map").height($(window).height()-91);
                $("#map").width($(window).width());

                if (citymap.controller.map)
                    citymap.controller.map.container.fitToViewport();
                $(window).resize();

                doctor_form_controller = new DoctorSearchFormController();
                doctor_form_controller.addColapse();
            }
        });

        $('#rebuild-search').click(self.rebuildSearch);
    };

    this.getMap = function () {
        return citymap.controller.map;
    };

    this.setMapCenter = function (latitude, longitude) {
        var geo = {};
        geo.lat = latitude;
        geo.lng = longitude;
        citymap.controller.map.setCenter(geo, 12);
    };

    this.parsePoint = function (val) {
        var point = val.split(' ');

        return {
            latitude: point[1],
            longitude: point[0]
        };
    };

    this.setLoader = function () {
        $('#map').css('opacity', '0.5');
    };

    this.removeLoader = function () {
        $('#map').css('opacity', '1');
    };

    this.rebuildSearch = function () {
        var center_point = self.getMap().getCenter();

        self.form_controller.latitude = center_point[0];
        self.form_controller.longitude = center_point[1];

        self.setLoader();
        citymap.setData('');
        self.form_controller.page = 1;
        self.form_controller.sendRequest();
    };

    this.geocode = function () {
        var map = citymap.controller.map;

        var text = $('#address-input').val();

        if (text != '' || text != 'Искать по адресу или станции метро') {
            writeLogAccountActivity('search_metro_or_address', text);
        }

        geo_coder = ymaps.geocode(text, {
            json: 'true',
            boundedBy: [
                [56.349122, 36.589145],
                [54.945227, 39.648837]
            ],
            strictBounds: true });

        geo_coder.then(
            function (res) {
                self.drop_down_container.html('');

                if (res['GeoObjectCollection']['featureMember'].length > 0) {

                    // Если геокодер Яндекса подсказал нам только один вариант -
                    // перемещаем сразу же центр карты на эту точку
                    if (res['GeoObjectCollection']['featureMember'].length == 1) {
                        var object = res['GeoObjectCollection']['featureMember'][0]['GeoObject'];
                        var info = self.parseGeoObject(object);
                        var point = self.parsePoint(info.pos);
                        self.form_controller.latitude = point.latitude;
                        self.form_controller.longitude = point.longitude;

                        self.latitude = point.latitude;
                        self.longitude = point.longitude;

                        if (object.metaDataProperty.GeocoderMetaData.kind == 'metro') {
                            self.form_controller.is_metro = 1;
                            self.form_controller.metro_station_name = info.metro_info.station_name;
                            self.form_controller.metro_branch_name = info.metro_info.branch_name;
                        } else {
                            self.form_controller.is_metro = 0;
                        }

                        //self.setMapCenter(point.latitude, point.longitude);
                        self.form_controller.sendRequest();

                        $('#address-drop').css('display', 'none');
                    }

                    if (res['GeoObjectCollection']['featureMember'].length > 1) {
                        for (i in res['GeoObjectCollection']['featureMember']) {
                            var object = res['GeoObjectCollection']['featureMember'][i]['GeoObject'];

                            var info = self.parseGeoObject(object);

                            if (info.metro) {
                                var el = '<li class="point-link" data-point="' + info.pos + '" data-metro="' + info.metro + '" data-branch-name="'
                                    + info.metro_info.branch_name + '" data-station-name="' + info.metro_info.station_name + '"><a href="javascript:void(0);"> '
                                    + object['name'] + ', ' + object['description'] + '</a></li>';
                            } else {
                                var el = '<li class="point-link" data-point="' + info.pos + '" data-metro="' + info.metro + '"><a href="javascript:void(0);"> '
                                    + object['name'] + ', ' + object['description'] + '</a></li>';
                            }
                            self.drop_down_container.append(el);
                        }

                        self.drop_down_container.slideDown();
                    }
                }
            },
            function (err) {
                //
            }
        );
    };

    this.parseGeoObject = function (object) {
        var result = {};
        result.metro = 0;
        if (object.metaDataProperty.GeocoderMetaData.kind == 'metro') {
            result.metro = 1;
            metro_info = object.metaDataProperty.GeocoderMetaData.text;
            metro_info = metro_info.split(',');

            var branch_re = /^ (.+) линия$/i;
            var station_re = /^ метро (.+)$/i;

            var branch_name = metro_info[2].replace(branch_re, '$1');
            var station_name = metro_info[3].replace(station_re, '$1');

            result.metro_info = {
                branch_name: branch_name,
                station_name: station_name
            };
        }

        result.pos = object.Point.pos;
        result.name = object.name;
        result.description = object.description;

        return result;
    };

    this.setData = function (data) {
        citymap.setData(data);
        this.setViewRange();
    };

    this.setViewRange = function () {

        if (citymap.points.length > 0) {
            max_latitude = 0;
            min_latitude = 10000;
            max_longitude = 0;
            min_longitude = 10000;

            if (self.latitude)
            {
                min_latitude = max_latitude = self.latitude;
            }

            if (self.longitude)
            {
                min_longitude = max_longitude = self.longitude;
            }

            for (i in citymap.points) {
                point = citymap.points[i];

                if (typeof point.lat != 'number')
                    continue;

                if (point.lat < min_latitude) {
                    min_latitude = point.lat;
                }

                if (point.lat > max_latitude) {
                    max_latitude = point.lat;
                }

                if (point.lng < min_longitude) {
                    min_longitude = point.lng;
                }

                if (point.lng > max_longitude) {
                    max_longitude = point.lng;
                }
            }
            if (self.getMap() && (min_longitude != 10000)) {
                self.getMap().setBounds([
                    [min_latitude, min_longitude],
                    [max_latitude, max_longitude]
                ], {
                    checkZoomRange: true
                });
            }
        }

    };

    this.setDataUrl = function (url) {
        self.dataUrl = url;
    };

    this.initMap = function () {
        citymap.setOptions({"sprite": {"src": "\/media\/images\/sprite4.png"}, "offset": {"x": 30, "y": 30}, "clusterdist": 20,
            "icons": [
                {"src": "", "title": "комната", "size": {"width": 9, "height": 9}, "anchor": {"x": 4, "y": 4}, "origin": {"x": 0, "y": 0},
                    "shadow": {"src": "", "size": {"width": 9, "height": 9}, "anchor": {"x": 3, "y": 3}, "origin": {"x": 81, "y": 0}},
                    "big": {"size": {"width": 26, "height": 32}, "anchor": {"x": 13, "y": 26}, "origin": {"x": 0, "y": 17},
                        "shadow": {"src": "", "size": {"width": 34, "height": 40}, "anchor": {"x": 17, "y": 30}, "origin": {"x": 235, "y": 10}}}
                },
                {"src": "", "title": "1-комнатная", "size": {"width": 9, "height": 9}, "anchor": {"x": 4, "y": 4}, "origin": {"x": 9, "y": 0},
                    "shadow": {"src": "", "size": {"width": 9, "height": 9}, "anchor": {"x": 3, "y": 3}, "origin": {"x": 81, "y": 0}},
                    "big": {"size": {"width": 26, "height": 32}, "anchor": {"x": 13, "y": 26}, "origin": {"x": 26, "y": 17},
                        "shadow": {"src": "", "size": {"width": 34, "height": 40}, "anchor": {"x": 17, "y": 30}, "origin": {"x": 235, "y": 10}}}
                },
                {"src": "", "title": "2-комнатная", "size": {"width": 9, "height": 9}, "anchor": {"x": 4, "y": 4}, "origin": {"x": 18, "y": 0},
                    "shadow": {"src": "", "size": {"width": 9, "height": 9}, "anchor": {"x": 3, "y": 3}, "origin": {"x": 81, "y": 0}},
                    "big": {"size": {"width": 26, "height": 32}, "anchor": {"x": 13, "y": 26}, "origin": {"x": 52, "y": 17},
                        "shadow": {"src": "", "size": {"width": 34, "height": 40}, "anchor": {"x": 17, "y": 30}, "origin": {"x": 235, "y": 10}}}
                },
                {"src": "", "title": "3-комнатная", "size": {"width": 9, "height": 9}, "anchor": {"x": 4, "y": 4}, "origin": {"x": 27, "y": 0},
                    "shadow": {"src": "", "size": {"width": 9, "height": 9}, "anchor": {"x": 3, "y": 3}, "origin": {"x": 81, "y": 0}},
                    "big": {"size": {"width": 26, "height": 32}, "anchor": {"x": 13, "y": 26}, "origin": {"x": 78, "y": 17},
                        "shadow": {"src": "", "size": {"width": 34, "height": 40}, "anchor": {"x": 17, "y": 30}, "origin": {"x": 235, "y": 10}}}
                },
                {"src": "", "title": "4-комнатная",
                    "size": {"width": 26, "height": 32}, "anchor": {"x": 13, "y": 26}, "origin": {"x": 104, "y": 17},
                    "shadow": {"src": "", "size": {"width": 34, "height": 40}, "anchor": {"x": 17, "y": 30}, "origin": {"x": 235, "y": 10}},
                    "big": {"size": {"width": 26, "height": 32}, "anchor": {"x": 13, "y": 26}, "origin": {"x": 104, "y": 17},
                        "shadow": {"src": "", "size": {"width": 34, "height": 40}, "anchor": {"x": 17, "y": 30}, "origin": {"x": 235, "y": 10}}}
                },
                {"src": "", "title": "5-комнатная", "size": {"width": 9, "height": 9}, "anchor": {"x": 4, "y": 4}, "origin": {"x": 45, "y": 0},
                    "shadow": {"src": "", "size": {"width": 9, "height": 9}, "anchor": {"x": 3, "y": 3}, "origin": {"x": 81, "y": 0}},
                    "big": {"size": {"width": 26, "height": 32}, "anchor": {"x": 13, "y": 26}, "origin": {"x": 130, "y": 17},
                        "shadow": {"src": "", "size": {"width": 34, "height": 40}, "anchor": {"x": 17, "y": 30}, "origin": {"x": 235, "y": 10}}}
                },
                {
                    "src": "",
                    "title": "6-комнатная",
                    "size": {"width": 9, "height": 9}, "anchor": {"x": 4, "y": 4}, "origin": {"x": 54, "y": 0},
                    "shadow": {"src": "", "size": {"width": 9, "height": 9}, "anchor": {"x": 3, "y": 3}, "origin": {"x": 81, "y": 0}},
                    "big": {"size": {"width": 26, "height": 32}, "anchor": {"x": 13, "y": 26}, "origin": {"x": 156, "y": 17},
                        "shadow": {"src": "", "size": {"width": 34, "height": 40}, "anchor": {"x": 17, "y": 30}, "origin": {"x": 235, "y": 10}}}
                }
            ],
            "cluster": {
                "icon": {"src": "", "title": "Несколько предложений рядом",
                    "size": {"width": 26, "height": 32}, "anchor": {"x": 13, "y": 26}, "origin": {"x": 209, "y": 17},
                    "shadow": {"src": "", "size": {"width": 34, "height": 40}, "anchor": {"x": 17, "y": 30}, "origin": {"x": 235, "y": 10}},
                    "big": {"size": {"width": 26, "height": 32}, "anchor": {"x": 13, "y": 26}, "origin": {"x": 209, "y": 17},
                        "shadow": {"src": "", "size": {"width": 34, "height": 40}, "anchor": {"x": 17, "y": 30}, "origin": {"x": 235, "y": 10}}}
                }
            },
            "group": {
                "icon": {"src": "", "title": "Несколько предложений по адресу",
                    "size": {"width": 26, "height": 32}, "anchor": {"x": 13, "y": 26}, "origin": {"x": 209, "y": 17},
                    "shadow": {"src": "", "size": {"width": 34, "height": 40}, "anchor": {"x": 17, "y": 30}, "origin": {"x": 235, "y": 10}},
                    "big": {"size": {"width": 26, "height": 32}, "anchor": {"x": 13, "y": 26}, "origin": {"x": 209, "y": 17},
                        "shadow": {"src": "", "size": {"width": 34, "height": 40}, "anchor": {"x": 17, "y": 30}, "origin": {"x": 235, "y": 10}}}
                }
            },
            "dataUrl": self.dataUrl,
            "schema": {"id": 0, "title": 1, "data": 2, "lat": 3, "lng": 4, "icon": 5}
        });
    };

};
var ClinicSearchFormController = function (landing, already_registred_account, url_page) {

    this.landing_page = landing;
    this.url_page = url_page;
    this.already_registred_account = already_registred_account;

    this.specialty_id = null;
    this.pettype_id = null;
    this.petservice_id = null;
    this.specialty_alias = null;
    this.purpose_of_visit_id = null;

    this.doctor_name = null;

    this.children = 0;
    this.handicapped = 0;
    this.pregnant = 0;
    this.day_and_night = 0;
    this.twenty_four_hours = 0;
    this.is_card_pay = 0;
    this.have_ramp = 0;
    this.clinic_type = 'adult';

    this.latitude = null;
    this.longitude = null;

    this.is_metro = 0;
    this.metro_station_name = null;
    this.metro_branch_name = null;

    this.district_id = null;
    this.region_id = null;
    this.street_id = null;

    this.page = 1;
    this.by_page = 10;

    this.primary_clinics_id_list = [];

    this.sort_by = 'rate';
    this.map_controller = null;
    this.city_id = null;
    this.container = '#clinic-search-form';
    this.mode = 'block';
    this.full_map = null;
    this.bounds = null;
    var self = this;

    this.setCityId = function(city_id){
        self.city_id = city_id;

        if (self.map_controller != null) {
            self.map_controller.setCityId(self.city_id);
        }
    };

    this.init = function () {
        self.attachEvents();
        $('.show_inp').live('click',function(){
            $(this).hide();
            $(this).parents('.pad_tb').find('.search_txt').show();
        });

        if (this.mode == 'page') {
            window.city_controller.subscribe(self.setCityInfo);
            window.city_controller.subscribe(self.changeSpecialtiesListToSearchClinic);
            self.city_id = window.city_controller.city_id;
        };

        if (this.mode == 'page') {
            self.initParamsFromUrl();
            self.sendRequest(false);

            self.map_controller = new YandexMapController(self);
            self.map_controller.page = 'clinic';
            self.map_controller.city_id = self.city_id;
            self.map_controller.full_map = self.full_map;
            self.map_controller.setDataUrl('/ajax/getClinicMapCard?big=0&id=');
            self.map_controller.init();
        };

        /*if (self.landing_page == 1) {

            if (self.already_registred_account == 1) {
                landing_login_page = new LandingLoginPageController(self.url_page,'врачи');
                landing_login_page.init();
            } else {
                landing_registration_page_controller = new LandingRegistrationPageController(self.url_page,'врачи');
                landing_registration_page_controller.init();
            }
        };*/

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

        $('.btn-clinic').click(function(){
            var action_for_counters = $(this).data('action-for-counters');
            var category_for_counters = $(this).data('category-for-counters');
            setCounters(category_for_counters, action_for_counters, '', SessionInfo.email);
        });

        $('.old_resize').click(function () {
            self.full_map = 0;
        });
    };

    this.setPageMode = function () {
        this.mode = 'page';
    };

    this.setCityInfo = function(city_info){
        self.setCityId(city_info.city_id);

        if (city_info.city_alias && (city_info.city_alias != 'moskva'))
            window.location = '//'+city_info.city_alias + '.'+SessionInfo.domain + '/clinic' + self.buildUrl();
        else if(city_info.city_alias == 'moskva')
            window.location = '//'+SessionInfo.domain+'/clinic' + self.buildUrl();
    };

    this.setBlockMode = function () {
        this.mode = 'block';
    }

    this.initParamsFromUrl = function () {
        self.from_url = 1;
        self.page = 1;
        //self.specialty_id = parseInt(getParameterByName('specialty_id', 0));
        self.purpose_of_visit_id = parseInt(getParameterByName('purpose_of_visit_id', 0));
        self.clinic_name = getParameterByName('clinic_name');
        self.sort_by = getParameterByName('sort_by', 'rate');
        self.children = getParameterByName('children', 0);
        self.handicapped = getParameterByName('handicapped', 0);
        self.pregnant = getParameterByName('pregnant', 0);
        self.day_and_night = getParameterByName('day_and_night', 0);
        self.full_map = getParameterByName('full_map', 0);
        self.is_card_pay = getParameterByName('is_card_pay', 0);
        self.twenty_four_hours = getParameterByName('twenty_four_hours', 0);
        self.have_ramp = getParameterByName('have_ramp', 0);

        self.clinic_type = getParameterByName('clinic_type', 'adult');

        if (self.clinic_type != 'children'){
            $(".inner .colleft  .radioBox, .inner-2 .colleft  .radioBox").removeClass('disable');
        }

        if(self.clinic_name)
        {
            $('.show_inp').hide();
            $('.pad_tb .search_txt').show();
        }

        var specialties_to_search_clinic = $('#landing_item_id');
        if(!specialties_to_search_clinic.isEmptyObject) {
            self.landing_item_id = specialties_to_search_clinic.val();
            specialties_to_search_clinic.remove();
        }

        self.initElements();
    };

    this.initElements = function () {
/*
        if (self.specialty_id){
            var active_spec = $('select[name="specialty_id"] option[value="' + self.specialty_id + '"]').text();

            $('#specialty_block ul.chzn-results li').each(function(){
               if ($(this).text() == active_spec) {
                   $(this).addClass('result-selected');
                   $('#specialty_block .chzn-single span').html(active_spec);
               }
            });
        }
*/
        if (self.specialty_id) {
            setCustomSelect(self.container + ' select[name="specialty_id"]', self.specialty_id);
//            self.loadPurposeOfVisitBlock(self.purpose_of_visit_id, false);
        }
/*
        if (self.purpose_of_visit_id)
            self.loadPurposeOfVisitBlock(self.purpose_of_visit_id);
*/
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

        if (self.is_card_pay == 0) {
            $('.is-card-pay').removeClass('act');
            $('.is-card-pay').removeClass('checked');
            $('.icon-credit-card').removeClass('act');
        } else {
            $('.is-card-pay').addClass('act');
            $('.icon-credit-card').addClass('act');
            $('.is-card-pay input').val(1);
        }

        if (self.twenty_four_hours == 0) {
            $('.twenty-four-hours').removeClass('act');
            $('.twenty-four-hours').removeClass('checked');
            $('.icon-time').removeClass('act');
        } else {
            $('.twenty-four-hours').addClass('act');
            $('.icon-time').addClass('act');
            $('.twenty-four-hours input').val(1);
        }

        if (self.have_ramp == 0) {
            $('.have-ramp').removeClass('act');
            $('.have-ramp').removeClass('checked');
            $('.icon-pandus').removeClass('act');
        } else {
            $('.have-ramp').addClass('act');
            $('.icon-pandus').addClass('act');
            $('.have-ramp input').val(1);
        }

        if (self.clinic_type) {
            $('.clinic-type').removeClass('act');

            if (self.clinic_type == 'adult') {
                $('.clinic-type-adult').addClass('act');
            }

            if (self.clinic_type == 'children') {
                $('.clinic-type-children').addClass('act');
            }
        }


        var option = $(self.container + ' select[name="specialty_id"] option[value="' + self.specialty_id + '"]:selected');
        if(option.hasClass('specialization'))
        {
            self.specialization_id = option.val();
        }

    };

    this.attachEvents = function () {
        $(this.container + ' select[name="specialty_id"]').change(function () {
            self.specialty_id = $(this).val();
            self.page = 1;
            $(self.container + ' select[name="specialty_id"] option').removeAttr('selected');

            var option = $(self.container + ' select[name="specialty_id"] option[value="' + self.specialty_id + '"]');

            option.attr('selected', true);
            if(option.hasClass('specialization'))
            {
                self.specialization_id = option.val();
            }

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


        $(document).on('click', '.view-more', function () {
            setCounters('next-10', 'unknown', '', SessionInfo.email);
            $('.view-more i').addClass('icon-loader');
            self.loadNextPage();
        });

        $(".map-box .resize").click(function (e) {
            self.full_map = 1;
            $("body").addClass('hidden');
            $("footer").hide();
        });

    };

    this.updateSeoInfo = function(){
        var data = {
            city_id : self.city_id,
            specialty_id : self.specialty_id,
            location : 'clinic'
        };
        Ajax.Get('/ajax/getSeoInfoByCityIdAndSpecialtyId', data, function(data){
            if (data.status == 0)
            {
                $('.clinic-special-links').html(data.result.html);
                $('title').html(data.result.title);
            }
            else
            {
                $('.clinic-special-links').html('');
                $('title').html('Найти клинику. Адреса и телефоны ветеринарных центров Москвы и других городов России - «LookMedBook»');
            }
        });
    };

    this.setParams = function () {
        //self.specialty_id = $(self.container + ' select[name="specialty_id"]').val();
        var selected_specialty_option = $(self.container + ' select[name="specialty_id"] option[selected="selected"]');

        self.specialty_id = selected_specialty_option.val();
        if ($('#pettype_to_search_clinic_chzn .result-selected').get(0))
            self.pettype_id = parseInt($('#pettype_to_search_clinic_chzn .result-selected').get(0).id.replace('pettype_to_search_clinic_chzn_o_','')) + 1;
        else
            self.pettype_id = 1;

        if ($('#petservice_to_search_clinic_chzn .result-selected').get(0))
            self.petservice_id = parseInt($('#petservice_to_search_clinic_chzn .result-selected').get(0).id.replace('petservice_to_search_clinic_chzn_o_','')) + 1;
        else
            self.petservice_id = 1;

        self.specialty_alias = selected_specialty_option.data('specialty_alias');
        self.purpose_of_visit_id = $(self.container + ' select[name="purpose_of_visit_id"]').val();
        self.children = $(self.container + ' .children').hasClass('act') ? 1 : 0;
        self.handicapped = $(self.container + ' .handicapped').hasClass('act') ? 1 : 0;
        self.pregnant = $(self.container + ' .pregnant').hasClass('act') ? 1 : 0;
        self.day_and_night = $(self.container + ' .day_and_night').hasClass('act') ? 1 : 0;
        self.twenty_four_hours = $(self.container + ' .twenty-four-hours').hasClass('act') ? 1 : 0;
        self.is_card_pay = $(self.container + ' .is-card-pay').hasClass('act') ? 1 : 0;
        self.have_ramp = $(self.container + ' .have-ramp').hasClass('act') ? 1 : 0;

        var name = $(self.container + ' input[name="clinic_name"]').val();

        if (name && name != 'Название клиники')
            self.clinic_name = $(self.container + ' input[name="clinic_name"]').val();
        else
            self.clinic_name = null;

        if ($(self.container + ' .clinic-type').hasClass('act')) {
            if ($(self.container + ' .clinic-type-adult').hasClass('act')) self.clinic_type = 'adult';
            if ($(self.container + ' .clinic-type-children').hasClass('act')) self.clinic_type = 'children';
        }

        self.updateSeoInfo();
    };

    this.sendRequest = function (push_state) {

        if (this.mode == 'block') {
            self.setParams();

            var url = self.buildUrl();

            window.location.href = '/clinic' + url;
            return;
        }

        // Если обычный решим

        if (push_state == undefined)
            push_state = true;

        if (push_state) {
            var state = {
                title:$('title').val(),
                url:'/clinic' + self.buildUrl()
            }

            // заносим ссылку в историю
            if (navigator.appName.indexOf('Explorer') < 0 || (navigator.appName.indexOf('Explorer') + 1 && parseInt($.browser.version, 10) > 8)){
                history.pushState(state, state.title, state.url);
            }
        }

        if (self.clinic_name){
            $('.show_inp').hide();
            $('.pad_tb .search_txt').show();
        }

        var data = {
            pettype_id:self.pettype_id,
            petservice_id:self.petservice_id,
            landing_item_id:         self.landing_item_id,
            purpose_of_visit_id:     self.purpose_of_visit_id,
            clinic_name:             self.clinic_name,
            day_and_night:           self.day_and_night,
            twenty_four_hours:       self.twenty_four_hours,
            is_card_pay:             self.is_card_pay,
            have_ramp:               self.have_ramp,
            children:                self.children,
            handicapped:             self.handicapped,
            pregnant:                self.pregnant,
            page:                    self.page,
            by_page:                 self.by_page,
            sort_by:                 self.sort_by,
            primary_clinics_id_list: self.primary_clinics_id_list,
            latitude:                self.latitude,
            longitude:               self.longitude,
            is_metro:                self.is_metro,
            metro_station_name:      self.metro_station_name,
            metro_branch_name:       self.metro_branch_name,
            landing:                 1,
            district_id:             self.district_id,
            region_id:               self.region_id,
            street_id:               self.street_id,
            clinic_type:             self.clinic_type,
            city_id:                 self.city_id
        };
console.log(data);
        if(self.specialization_id >= 0)
        {
            data.specialization_id = self.specialization_id;
        }
        else
        {
            data.specialty_id = self.specialty_id;
        }

        Ajax.Get('/clinic/ajaxSearch', data, function (data) {
            if (data.status == 0) {
                self.landing_item_id = 0;

                if (self.page == 1) {
                    if (data.result.full_search == false) {
                        if (data.result.is_empty_city == 0) {
                            $('#our-doctors').html('<div class="error-plate">По Вашему запросу ничего не найдено. Возможно Вам подойдёт одна из клиник в нашей базе</div>');
                            $(".search-count-block").hide();
                        }
                        else {
                            $('#our-doctors').html('<div class="error-plate">У нас пока нет клиник в городе '+data.result.city_name+'. Мы сообщим, как только они появятся!</div>');
                            $(".search-count-block").hide();
                        }

                        $('#our-doctors').append(data.result.html);
                    } else {
//                        self.map_controller.clinics_count = data.result.clinics_count;
//                        self.map_controller.isset_region = data.result.isset_region;
//                        self.filter_active = data.result.filter_active;

                        $(".count-digit").text(data.result.clinic_total_count);
                        $(".count-doctor").text(data.result.clinic_word_form);
                        $(".count-specialty").text(data.result.specialty_name);
                        $(".search-count-block").show();

                        if(!data.result.specialty_name)
                        {
                            $('span.specialty-label').hide();
                        }
                        else
                        {
                            $('span.specialty-label').show();
                        }

                        $('#our-doctors').html(data.result.html);
                        self.primary_clinics_id_list = data.result.primary_clinics_id_list;
                    }

                    self.bounds = data.result.bounds;

                    if (data.result.page_title) {
                    	document.title = data.result.page_title;
                    }

                    if (self.map_controller != undefined)
                    {
                        Ajax.Get('/ajax/getMapData', {hash:data.result.map}, function (data) {
                            self.map_controller.setData(data.result);
                            if(self.bounds)
                            {
                                var bounds = [
                                    [parseFloat(self.bounds.min_latitude), parseFloat(self.bounds.min_longitude)],
                                    [parseFloat(self.bounds.max_latitude), parseFloat(self.bounds.max_longitude)]
                                ];
                                self.map_controller.setBounds(bounds);
                            }
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

        /*if (self.specialty_id > 0) {
            str = str + '&specialty_id=' + self.specialty_id;
        }*/

        if (self.pettype_id > 0) {
            str = str + '&pettype_id=' + self.pettype_id;
        }

        if (self.petservice_id > 0) {
            str = str + '&petservice_id=' + self.petservice_id;
        }

        if (self.purpose_of_visit_id > 0) {
            str = str + '&purpose_of_visit_id=' + self.purpose_of_visit_id;
        }

        if (self.clinic_name) {
            str = str + '&clinic_name=' + self.clinic_name;
        }

        if (self.is_card_pay) {
            str += '&is_card_pay=' + self.is_card_pay;
        }
        if (self.twenty_four_hours) {
            str += '&twenty_four_hours=' + self.twenty_four_hours;
        }
        if (self.have_ramp) {
            str += '&have_ramp=' + self.have_ramp;
        }
        if (self.clinic_type) {
            str += '&clinic_type=' + self.clinic_type;
        }

        if (self.pregnant > 0)
            str += '&pregnant=' + self.pregnant;

        if  (self.handicapped > 0)
            str += '&handicapped=' + self.handicapped;

        if (self.children > 0)
            str += '&children=' + self.children;

        if (self.day_and_night > 0)
            str += '&day_and_night=' + self.day_and_night;

        if (self.latitude > 0)
            str += '&latitude=' + self.latitude;

        if (self.longitude > 0)
            str += '&longitude=' + self.longitude;

        if (self.metro_station_name)
            str += '&metro_station_name=' + self.metro_station_name;

        if (self.metro_branch_name)
            str += '&metro_branch_name=' + self.metro_branch_name;

        if (self.sort_by && (self.sort_by != 'recomend')) {
            str = str + '&sort_by=' + self.sort_by;
        }

        if (self.full_map > 0)
            str += '&full_map=' + self.full_map;

        if(str)
            str = '?' + str.substring(1, str.length);

        if (self.specialty_alias) {
            str = '/' + self.specialty_alias + str;
        }

        return str;
    };

    this.loadPurposeOfVisitBlock = function (value) {
        Ajax.Get('/ajax/getPurposesOfVisitBySpecialtyId', {specialty_id:self.specialty_id}, function (data) {
            if (data.status == 0) {
                $('#purpose_of_visit_block_clinic').html(data.result);

                if (!value)
                    self.purpose_of_visit_id = 0;
                else {
                    $(self.container + ' select[name="purpose_of_visit_id"] option[value="' + value + '"]').attr('selected', 'selected');
                    $(self.container + ' select[name="purpose_of_visit_id"]').trigger('liszt:updated');
                }

                $(".chzn-select").chosen();
                $(".chzn-select-deselect").chosen({allow_single_deselect:true});

                /*var active_purpose = $('select[name="purpose_of_visit_id"] option[value="' + value + '"]').text();

                $('#purpose_of_visit_block_clinic ul.chzn-results li').each(function(){
                    if ($(this).text() == active_purpose) {
                        $(this).addClass('result-selected');
                        $('#purpose_of_visit_block_clinic .chzn-single span').html(active_purpose);
                    }
                });*/
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
            $('.top_number').show();
            $('.ymaps-b-zoom__button_type_plus').trigger('click');
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
        Ajax.Get('/ajax/changeSpecialtiesListToSearchClinicsByCityId', {city_id : city_info.city_id}, function(data){
            if (data.status == 0)
            {
                $('#specialties_to_search_clinic').html(data.result.option);
                $('#specialties_to_search_clinic').trigger('liszt:updated');

                $(self.container + ' select[name="purpose_of_visit_id"]').html('<option value=""></option>');
                $(self.container + ' select[name="purpose_of_visit_id"]').trigger('liszt:updated');
            }
        });
    }
};
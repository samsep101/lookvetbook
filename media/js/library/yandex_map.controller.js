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
    this.page = null;

    this.full_map = null;

    var controller = this;

    var bounds = [];

    this.city_id = null;

    this.clinics_count = null;
    this.filter_active = null;
    this.isset_region = null;

    var self = this;

    this.setCoordinates = function(latitude, longitude) {

        self.latitude = latitude;
        self.longitude = longitude;

        self.bounds = [[latitude + 2, longitude - 2], [latitude - 2, longitude + 2]];

        if (self.getMap() != null)
        {
            /*
            latitude = parseFloat(latitude);
            longitude = parseFloat(longitude);
            self.getMap().setBounds([
                [latitude - 0.4, longitude - 0.4],
                [latitude + 0.4, longitude + 0.4]
            ], {
                checkZoomRange: true
            });*/
            self.setMapCenter(self.latitude, self.longitude);
        }
    };

    this.setCityId = function(city_id){
        self.city_id = city_id;
    };


    this.setCityInfo = function(city_info){
        self.latitude = city_info.latitude;
        self.longitude = city_info.longitude;
        self.city_id = city_info.city_id;
    };

    this.setBounds = function(bounds){
        if(self.getMap())
        {
            /*if(!self.filter_active) {
                self.clinics_count = 2;
            }*/
            self.clinics_count = 2;

            var count_in_bounds = self.getCountInBounds(bounds);
            if((count_in_bounds < self.clinics_count) && self.map.getPoints().length >= self.clinics_count || self.isset_region)
            {
                var i = 0;
                while((i<1000) && (self.getCountInBounds(bounds) < self.clinics_count))
                {
                    bounds[0][0] -= 0.0005;
                    bounds[1][0] += 0.0005;
                    bounds[0][1] -= 0.0005;
                    bounds[1][1] += 0.0005;

                    if(self.getCountInBounds(bounds) >= self.clinics_count) {
                        break;
                    }

                    i++;
                }

                if(self.isset_region) {
                    while((bounds[1][0] - bounds[0][0]) < 0.045) {
                        bounds[0][0] -= 0.0005;
                        bounds[1][0] += 0.0005;
                        bounds[0][1] -= 0.0005;
                        bounds[1][1] += 0.0005;
                    }
                }
            }

            bounds[0][0] -= 0.00005;
            bounds[1][0] += 0.00005;
            bounds[0][1] -= 0.00005;
            bounds[1][1] += 0.00005;

            self.getMap().setBounds(bounds, {
                checkZoomRange: true
            });
        } else {
            setTimeout(function(){
                self.setBounds(bounds);
            }, 500)
        }
    };

    this.getCountInBounds = function(bounds)
    {
        var points = self.map.getPoints();

        var count = 0;

        for(var i in points)
        {
            var lat = points[i]['lat'];
            var lng = points[i]['lng'];
            var condition = ((lat >= bounds[0][0]) && (lat <= bounds[1][0]))
                               && ((lng >= bounds[0][1]) && (lng <= bounds[1][1]));

            if(condition)
            {
                count ++;
            }
        }

        return count;
    };

    this.init = function () {
        $('.map-city').click(function(){
            if($(this).attr('data-disabled') != '1') {
                $(this).attr('data-disabled', '1');

                var data = {
                    page : self.page,
                    city_id : self.city_id
                };
                Ajax.Get('/ajax/getCityChoicePopup', data, function (data) {
                    if (data.status == 0) {
                        var block_code = data.result.html;
                        showPopup(block_code);
                    }
                });
            }
        });


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

        if ($('.analysis-link').hasClass('active')){
            $(".map-box").height($(window).height());
            $(".map-box").width($(window).width());
            $("#map").height($(window).height());
            $("#map").width($(window).width());

            self.setDataUrl('/ajax/getLaboratoryMapCard');
        }

        /*if(self.form_controller == 'about_page') {
            self.setDataUrl('/ajax/getAboutMapCard');
        }*/

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

        if (self.full_map == 1) {
            self.resizeMap();
        }

        $(".map-box .resize").click(function (e) {
            self.resizeMap();
        });

        $('#rebuild-search').click(self.rebuildSearch);
    };

    this.resizeMap =function () {
        $('.top_number').hide();
        $('.breadcrumbs').hide();
        $('.ymaps-b-zoom__button_type_minus').trigger('click');

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
            var new_map_block = $('<div />', {
                'class': 'full-map',
                'style': 'height: 361px'
            });

            map_block = $('<div class="map-block"></div>');
            map_block.html($('#map'));
            new_map_block.html(map_block);
            //  new_map_block.append($('<div class="cards"></div>'));
            new_map_block.append($('<div class="bott-panel"><div class="shell"><span class="resize old_resize">Уменьшить</span><a href="#">Вернуться к результатам поиска</a></div></div>'));

            $('header').before(new_map_block);

            $('.map-box').css('margin-top','-4px');
            $('.map-box').css('display','none');
            $('.search-box').css('top', '28px');
            //$('#our-doctors .item-row:first').clone().appendTo('.full-map .cards');
            $("footer").hide();

            $(".full-map .resize, .full-map .shell a").click(function (e) {
                e.preventDefault();
                $('.breadcrumbs').show();
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

                $('.search-form').css('margin-top','0px');
                $('.map-box').css('margin-top','0px');
                $('.map-box').css('display','block');
                $('.search-box').css('top', '35px');

                $('.map-box > *:first').before($('#map'));
                $('#map').css({
                    width: '649px',
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
    }

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

        if (!text){
            self.form_controller.latitude = null;
            self.form_controller.longitude = null;

            self.form_controller.sendRequest();
        }

        if (self.city_id){
            city_info.city_id = window.city_controller.city_id;
            city_info.latitude = window.city_controller.latitude;
            city_info.longitude = window.city_controller.longitude;
        }

        geo_coder = ymaps.geocode(text, {
            json: 'true',
            boundedBy: [
                [city_info.latitude + 1, city_info.longitude - 1],
                [city_info.latitude - 1, city_info.longitude + 1]
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
            var max_latitude = 0;
            var min_latitude = 10000;
            var max_longitude = 0;
            var min_longitude = 10000;


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
        citymap.setOptions({"sprite": {"src": "\/media\/images\/sprite4.png?1"}, "offset": {"x": 30, "y": 30}, "clusterdist": 20,
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
                    "size": {"width": 26, "height": 32}, "anchor": {"x": 13, "y": 26}, "origin": {"x": 0, "y": 17},
                    "shadow": {"src": "", "size": {"width": 34, "height": 40}, "anchor": {"x": 17, "y": 30}, "origin": {"x": 235, "y": 10}},
                    "big": {"size": {"width": 26, "height": 32}, "anchor": {"x": 13, "y": 26}, "origin": {"x": 0, "y": 17},
                        "shadow": {"src": "", "size": {"width": 34, "height": 40}, "anchor": {"x": 17, "y": 30}, "origin": {"x": 235, "y": 10}}}
                }
            },
            "group": {
                "icon": {"src": "", "title": "Несколько предложений по адресу",
                    "size": {"width": 26, "height": 32}, "anchor": {"x": 13, "y": 26}, "origin": {"x": 104, "y": 17},
                    "shadow": {"src": "", "size": {"width": 34, "height": 40}, "anchor": {"x": 17, "y": 30}, "origin": {"x": 235, "y": 10}},
                    "big": {"size": {"width": 26, "height": 32}, "anchor": {"x": 13, "y": 26}, "origin": {"x": 209, "y": 17},
                        "shadow": {"src": "", "size": {"width": 34, "height": 40}, "anchor": {"x": 17, "y": 30}, "origin": {"x": 235, "y": 10}}}
                }
            },
            "dataUrl": self.dataUrl,
            "schema": {"id": 0, "title": 1, "data": 2, "lat": 3, "lng": 4, "icon": 5}
        });
    };

    this.getPlacemarks = function()
    {
        return self.map.placeMarks;
    }

};
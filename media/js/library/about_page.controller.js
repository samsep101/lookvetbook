var AboutPageController = function(city_id) {
    var self= this;

    this.city_id = city_id;
    this.map_controller = null;

    this.init = function() {
        self.addMap();
    };

    this.addMap = function() {
        self.map_controller = new YandexMapController('about_page');
        self.map_controller.init();

        if (self.map_controller != undefined) {
            Ajax.Get('/ajax/getAboutMapData', {}, function (data) {
                //self.map_controller.setData(data.result);
                var map = self.map_controller.getMap();
                var placemark = new ymaps.Placemark([48, 40], {
                    balloonContent: '<img src="http://img-fotki.yandex.ru/get/6114/82599242.2d6/0_88b97_ec425cf5_M" />',
                    iconContent: "Азербайджан"
                }, {
                    preset: "twirl#yellowStretchyIcon",
                    // Отключаем кнопку закрытия балуна.
                    balloonCloseButton: false,
                    // Балун будем открывать и закрывать кликом по иконке метки.
                    hideIconOnBalloonOpen: false
                });


                var myBalloonLayout = ymaps.templateLayoutFactory.createClass(
                    '<div class="flag_address">127015 г. Москва, ул. Вятская, дом 27, строение 13-14</div>'
                );
                ymaps.layout.storage.add('my#theaterlayout', myBalloonLayout);
                var balloon = new ymaps.Balloon(map, null, {
                    contentLayout: myBalloonLayout,
                    closeButton : false,
                    offset : [140, 50]
                });
                balloon.options.setParent(map.options);
                balloon.open([55.796607,37.580032]);

                //$(".ymaps-b-balloon.ymaps-i-custom-scroll").text(123);
            });
        }



    };
}
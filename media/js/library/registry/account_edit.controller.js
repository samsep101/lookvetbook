var AccountEditController = function()
{
    //в теории эта штука должна работать так: на сервере идет валидация формы
    //при ошибке валидации вместо текста возвращается только код ошибки
    //а на стороне javascript есть массив validation_rules, в котором прописаны все сообщения обо всех ошибках
    //здесь эта штука работать не будет, ибо я считаю ее сложной и сделал всё проще

    var self = this;

    self.account_id = null;

    self.data = {
        account_id : null,
        first_name : null,
        middle_name : null,
        last_name : null,
        nick: null,
        password : null,
        password2 : null,
        phone : null,
        email : null
    };

    self.container = null;
    self.change_flag = false;


    self.password_ok = function() {
        $('input[name="password"], input[name="password2"]').css({background:"white"});
        var pass = $('input[name="password"]').val();
        var pass2 = $('input[name="password2"]').val();

        if(!pass.length) {
            return 1;
        }
        if(pass.length<6) {
            $('input[name="password"]').css({background:"red"});
            return 0;
        }
        if(pass!=pass2) {
            $('input[name="password"], input[name="password2"]').css({background:"red"});
            return 0;
        }
        return 1;
    };

    self.init = function()
    {
        self.account_id = $(self.container + ' input[name=account_id]').val();

        var check_line = '';
        for(var fld_name in self.data) {
            check_line += (check_line?', ':'')+self.container + ' input[name='+fld_name+']';
        }

        $(check_line).change(function (){
            self.change_flag = true;
            if(!self.password_ok()) {
                self.change_flag = false;
            }
        });

        $('input[name="save"]').click(function(){
            console.log('save')
            if(self.change_flag) {
                self.readData();
                self.sendData();
            }else{
                alert('Нет изменений для применения');
            }
        });
    };

    self.readData = function()
    {
        for(var fld_name in self.data) {
            self.data[fld_name] = $('input[name="' + fld_name + '"]').val();
        }
        console.log('data', self.data)
    };

    self.sendData = function()
    {
        console.log('post', self.data);
        $.ajax({
            url: '/manage/account/ajaxEditAccount',
            data: self.data,
            type: 'POST',
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                console.log('status data', data);

                var popup = new PopupMessage();
                if (data && data.status == 0) {
                    popup.close_callback = function () {
                        if (self.account_id) {
                            window.location.reload();
                        } else {
                            window.location = '/manage/account/edit?id=' + data.account_id;
                        }
                    };

                    popup.show('Данные успешно сохранены');
                } else {
                    var msg = 'Проблема при сохранении данных';
                    if(data.data && data.data.length){
                        msg = '';
                        for(var i in data.data) {
                            msg += data.data[i]+"\n";
                        }
                    }
                    popup.show(msg);
                }
            }
        });
    };
};
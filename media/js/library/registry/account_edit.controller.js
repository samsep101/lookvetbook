var AccountEditController = function()
{
    this.account_id = null;

    this.data = {
        account_id : null,
        first_name : null,
        middle_name : null,
        last_name : null,
        phone_number : null,
        email : null
    };

    this.container = null;
    this.change_flag = false;

    var self = this;

    this.init = function()
    {
        this.account_id = $(self.container + ' input[name=id]').val();

        $(self.container + ' input[name=first_name], '+ self.container + ' input[name=middle_name], '+ self.container + ' input[name=last_name], '+ self.container + ' input[name=phone_number], '+ self.container + ' input[name=email]').change(function (){
            self.change_flag = true;
            $(self.container + ' input[name="save"]').validation({
                validate : [
                    $(self.container + ' input[name="first_name"]').validate(validation_rules['required']),
                    $(self.container + ' input[name="last_name"]').validate(validation_rules['required']),
                    $(self.container + ' input[name="phone_number"]').validate(validation_rules['required']),
                    $(self.container + ' input[name="email"]').validate(validation_rules['required']),
                ],
                callback: function(){
                    //self.readData();
                    //self.sendData();
                }
            });
        });

        if (!self.change_flag){
            $(self.container + ' input[name="save"]').validation({
                validate : [
                    $(self.container + ' input[name="first_name"]').validate(validation_rules['required']),
                    $(self.container + ' input[name="last_name"]').validate(validation_rules['required']),
                    $(self.container + ' input[name="phone_number"]').validate(validation_rules['required']),
                    $(self.container + ' input[name="email"]').validate(validation_rules['email']),
                ],
                callback: function(){
                    //self.readData();
                    //self.sendData();
                }
            });
        }

        $('input[name="save"]').click(function(){
            //self.readData();
            //self.sendData();
        });
    };

    this.readData = function()
    {
        self.data.id = $('input[name="id"]').val();
        self.data.first_name = $('input[name="first_name"]').val();
        self.data.middle_name = $('input[name="middle_name"]').val();
        self.data.last_name = $('input[name="last_name"]').val();
        self.data.phone_number = $('input[name="phone_number"]').val();
        self.data.email = $('input[name="email"]').val();
    };

    this.sendData = function()
    {
        Ajax.Post('/manage/account/ajaxEditAccount', self.data, function(data){
            if (data.status == 0)
            {
                var popup = new PopupMessage();
                popup.close_callback = function()
                {
                    if(this.account_id) {
                        window.location.reload();
                    }else {
                        window.location = '/manage/account/' + data.account_id;
                    }
                };

                popup.show('Данные успешно сохранены');
            }
        });
    };
};
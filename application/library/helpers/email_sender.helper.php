<?php
    class EmailSenderHelper
    {
        public function sendConfirmEmailMessage($mail, $passwordSequence)
        {
            require_once($_SERVER['DOCUMENT_ROOT'] . "/application/library/classes/phpmailer.class.php");
            $ml = new PHPMailer();

            $site_url = SITE_URL;
            $msgContent = <<<EOD
		<p>Для того, чтобы подтвердить свой E-mail, пройдите по ссылке приведенной ниже:
			<br />
			<a href="$site_url/account/confirmEmail?mail={$mail}&hash={$passwordSequence}&rg=1">$site_url/account/confirmEmail?mail={$mail}&hash={$passwordSequence}&rg=1</a>.
		 </p>
			
EOD;
            //$srvmail = 'info@lookmedbook.com';
            $to = $mail;

            $ml->From = 'no-reply';
            $ml->FromName = "LookMedBook";
            $ml->Subject = "Регистрация на LookMedBook.ru";
            $ml->MsgHTML($msgContent);
            $ml->AddAddress($to);
            $ml->Send();

            $ml->ClearAddresses();
            return TRUE;
        }

        public function sendLandingConfirmEmailMessage($mail, $hash)
        {
            require_once($_SERVER['DOCUMENT_ROOT'] . "/application/library/classes/phpmailer.class.php");
            $ml = new PHPMailer();

            $site_url = SITE_URL;
            $msgContent = <<<EOD
		<p>Для того, чтобы подтвердить свой E-mail, пройдите по ссылке приведенной ниже:
			<br />
			<a href="$site_url/account/landing?hash={$hash}">$site_url/account/landing?hash={$hash}</a>.
		</p>

EOD;
            //$srvmail = 'info@lookmedbook.com';
            $to = $mail;
            $ml->From = 'no-reply';
            $ml->FromName = "LookMedBook";
            $ml->Subject = "Регистрация на LookMedBook.ru";
            $ml->MsgHTML($msgContent);
            $ml->AddAddress($to);
            $ml->Send();

            $ml->ClearAddresses();
            return TRUE;
        }

        public function sendChangeEmailMessage($mail, $new_mail, $confirm_code)
        {
            require_once($_SERVER['DOCUMENT_ROOT'] . "/application/library/classes/phpmailer.class.php");
            $ml = new PHPMailer();

            $site_url = SITE_URL;
            $msgContent = <<<EOD
		<p>Отправлен запрос на изменение email c данного адреса на $new_mail<br />
            Для подтверждения смены адреса перейдите по ссылке: <a href="$site_url/account/changeEmail?from=$mail&to=$new_mail&confirm_code=$confirm_code">$site_url/account/changeEmail?from=$mail&to=$new_mail&confirm_code=$confirm_code</a>"
		 </p>
			
EOD;
            $ml->From = 'no-reply';
            $ml->FromName = "LookMedBook";
            $ml->Subject = "Изменение почтового адреса";
            $ml->MsgHTML($msgContent);
            $ml->AddAddress($mail);
            $ml->Send();
            $ml->ClearAddresses();
        }

        public function sendRecoveryPasswordEmail($mail, $hash)
        {
            require_once($_SERVER['DOCUMENT_ROOT'] . "/application/library/classes/phpmailer.class.php");
            $ml = new PHPMailer();

            $site_url = SITE_URL;
            $msgContent = <<<EOD
		    <p>Для того, чтобы восстановить пароль пройдите по ссылке приведенной ниже:
				<br />
				<a href="$site_url/passwordNew?mail=$mail&hash=$hash">$site_url/passwordNew?mail=$mail&hash=$hash</a>
			</p>

EOD;
            $ml->From = 'no-reply';
            $ml->FromName = "LookMedBook";
            $ml->Subject = "Восстановление пароля на LookMedBook.ru";
            $ml->MsgHTML($msgContent);
            $ml->AddAddress($mail);
            $ml->Send();
            $ml->ClearAddresses();
        }

        public function sendInviteMessage($mail)
        {
            $to = $mail;
            $subject = "Вы получили приглашение на lookmedbook.ru!";
            $message = 'Вы получили приглашение на lookmedbook.ru. Теперь Вы можете авторизоваться';
            $headers = "Content-type: text/html; charset=utf-8 \r\n";
            $headers .= "From: lookmedbook.ru <no-reply@lookmedbook.ru>\r\n";

            mail($to, $subject, $message, $headers);
        }

	    public function sendVisitMessage($email, $visit = null)
	    {
		    $to = $email;
		    $subject = "Вы успешно записались на lookmedbook.ru!";
		    $message = 'Вы успешно записаны на прием! Скоро мы свяжемся с Вами!';
		    $headers = "Content-type: text/html; charset=utf-8 \r\n";
		    $headers .= "From: lookmedbook.ru <no-reply@lookmedbook.ru>\r\n";

		    mail($to, $subject, $message, $headers);
	    }

        public static function sendMessage($email, $subject, $message)
        {
            $message = html_entity_decode($message,ENT_COMPAT,'UTF-8');

            $headers = "Content-type: text/html; charset=utf-8 \r\n";
            $headers .= "From: LookMedBook <no-reply@lookmedbook.ru>\r\n";
            $headers .= 'Reply-To: no-reply@lookmedbook.ru' . "\r\n" ;
            $headers .= 'X-Mailer: PHP/' . phpversion();

            mail($email, $subject, $message, $headers, '-fno-reply@lookmedbook.ru');
        }
    }
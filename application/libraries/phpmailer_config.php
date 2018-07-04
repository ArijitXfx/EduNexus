

<?php

require_once(APPPATH."third_party/phpmailer/PHPMailerAutoload.php");

class phpmailer_config
{
    public function __construct()
    {
        log_message('Debug', 'PHPMailer class is loaded.');
    }

    public function load()
    {

        $mail = new PHPMailer(true);

      //  $mail->SMTPDebug = 2;                                 // Enable verbose debug output
        $mail->isSMTP();
        $mail->SMTPOptions = array(
                              'ssl' => array(
                                            'verify_peer' => false,
                                            'verify_peer_name' => false,
                                            'allow_self_signed' => true
                                          )
                                );                              // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'subhomanutd040995@gmail.com';                 // SMTP username
        $mail->Password = 'ButteredLizard040995';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                               // TCP port to connect to
        $mail->setFrom('subhomanutd040995@gmail.com', 'NGO EDU-NEXUS');
      return $mail;
    }
}
 ?>

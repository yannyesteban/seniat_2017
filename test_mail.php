    <?php

include('../PHPMailer_v5.1/class.phpmailer.php');

    $email = new PHPMailer(true);
    $email->CharSet = "utf-8";
    $email->isSMTP();
    $email->SMTPAuth= true;
    $email->Username = "cef";
    $email->Password = "Cefseniat20151";
    $email->SMTPSecure = "";
    $email->Host = "172.16.16.91";
    $email->Port = "25";



  $email->Username = "yannyesteban@gmail.com";
    $email->Password = "atmosfera1024x";
    $email->SMTPSecure = "";
    $email->Host = "smtp.gmail.com";
    $email->Port = "587";

$email->SMTPSecure = 'tls';
    $email->setFrom("yannyesteban@gmail.com", "your name");
    $email->AddAddress("yannyesteban@gmail.com", "receivers name");

    $email->Subject  =  "PHPMailer Mailing API Test";
    $email->IsHTML(true);
    $email->Body    = "Hi,
    <br />
    Email was generated using PHPMailer with Google SMTP
    <br />
    Welcome to PHPMailer 222 :)";

    if($email->Send())
    {
    echo "Email Successfully sent";
    }
    else
    {
    echo "Error in Sending Mail".$email->ErrorInfo;
    }

    ?>
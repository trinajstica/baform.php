<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recaptchaResponse'])) {

// for NICEPAGE
// get your site public/secret keys: https://www.google.com/recaptcha/intro/v3.html

    $recaptcha_secret = 'YOUR_RECAPTCHA_SECRET_KEY';
    $email_to = "YOUR_EMAIL";

    $line1 = "You have a new form submission from your site:";
    $line2 = "IP address:";

    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_response = $_POST['recaptchaResponse'];
    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
    $recaptcha = json_decode($recaptcha);

    if ($recaptcha->score >= 0.5) {
		try {
                $_POST = array_map("strip_tags", $_POST);
                $_POST = array_map("htmlspecialchars", $_POST);

                $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}";
                $email_subject = "Form submission on site (".$actual_link.")";


                function Valid_Email($data){
                    $pattern = '/^([0-9a-z]([-.\w]*[0-9a-z])*@(([0-9a-z])+([-\w]*[0-9a-z])*\.)+[a-z]{2,6})$/i';
                    if (preg_match($pattern,$data)){
                    return $data;
                }
                    else{
                        return $email_to;
                    }
                }

                function clean_string($string) {
                    $bad = array("content-type","bcc:","to:","cc:","href");
                    return str_replace($bad,"",$string);
                }


                function getUserIpAddr()
                {
                    if (!empty($_SERVER['HTTP_CLIENT_IP']))
                    {
                        return $_SERVER['HTTP_CLIENT_IP'];
                    }
                    else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
                    {
                        return $_SERVER['HTTP_X_FORWARDED_FOR'];
                    }
                    else
                    {
                        return $_SERVER['REMOTE_ADDR'];
                    }
                }

                $email_message = $line1."\n\n";
                $email_message .= $line2."\n".getUserIpAddr()."\n\n";

                foreach($_POST as $key => $value) {

                    $has_a = strpos($value, '@');
                    if ($has_a > 0){
                        if(filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $email_from = valid_email($value);
                        } else {
                            $email_from = $email_to;
                        }
                    }
                    if ($key !== 'submit' && $key !== 'recaptchaResponse' && $key !== 'siteId' && $key !== 'pageId' ) {
                            $email_message .= $key." :\n".clean_string($value)."\n\n";
                    }

                } //foreach end

                if (!$email_from) {
                    $email_from = $email_to;
                }

                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/plain;charset=UTF-8" . "\r\n";
                $headers .= 'From: '.$email_from. "\r\n";

                if (@mail($email_to, $email_subject, $email_message, $headers)) {
                        $resArray= array('success' => true);
                        header('Content-Type: application/json');
                        echo json_encode($resArray);
                } else {
                        $resArray= array('success' => false);
                        header('Content-Type: application/json');
                        echo json_encode($resArray);
                }
        }
            catch (Exception $e) {
            }
        } else {
            $resArray= array('success' => false);
            header('Content-Type: application/json');
            echo json_encode($resArray);
        }
} else {
    $resArray= array('success' => false);
    header('Content-Type: application/json');
    echo json_encode($resArray);
}
?>


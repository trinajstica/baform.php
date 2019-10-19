<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recaptcha_response'])) {

// REPLACE THIS WITH ACTUAL DATA, AFTER THAT COPY IN YOUR /HEAD AND /FORM SECTION:

    // YOUR_RECAPTCHA_SITE_KEY
    // YOUR_RECAPTCHA_SECRET_KEY
    // YOUR_EMAIL    
    // YOU CAN REPLACE {$_SERVER['HTTP_REFERER']} with some filename (like error.html and success.html)
    
/**

**** PUT THIS IN YOUR HTML HEAD, BEFORE </head>:

<script src="https://www.google.com/recaptcha/api.js?render=YOUR_RECAPTCHA_SITE_KEY"></script>
<script>
  grecaptcha.ready(function () {
    grecaptcha.execute('YOUR_RECAPTCHA_SITE_KEY', { action: 'contact' }).then(function (token) {
       var recaptchaResponse = document.getElementById('recaptchaResponse');
                recaptchaResponse.value = token;
    });
  });
</script>

**** PUT THIS IN YOUR FORM BEFORE </form>:

<input type="hidden" name="recaptcha_response" id="recaptchaResponse">

**/
    
    $recaptcha_secret = 'YOUR_RECAPTCHA_SECRET_KEY';    
    $email_to = "YOUR_EMAIL";
    $success = "{$_SERVER['HTTP_REFERER']}";
    $error = "{$_SERVER['HTTP_REFERER']}";
    
    // DO NOT EDIT IF YOU DO NOT KNOW WHAT YOU DOING BELOW THAT LINE:
    
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_response = $_POST['recaptcha_response'];
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
    
                $email_message = "You have a new form submission from your site:\n\n";
                $email_message .= "IP address:\n".getUserIpAddr()."\n\n";
    
                foreach($_POST as $key => $value) {            
               
                    $has_a = strpos($value, '@'); 
                    if ($has_a > 0){    
                        if(filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $email_from = valid_email($value);
                        } else {
                            $email_from = $email_to;
                        }      
                    }
                    if ($key !== 'submit' && $key !== 'recaptcha_response') {
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
                        header('Location: '.$success);
                } else {
                        header('Location: '.$error);
                        
                }
        } 
            catch (Exception $e) {
            }
        } else {
            header('Location: '.$error);
            
        }
} else {
    header('Location: '.$error);
}
?>

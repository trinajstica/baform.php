<?php
    $email_to = "YOUR_EMAIL";
    
    // DO NOT CHANGE ANYTHING FROM HERE DOWN
    
    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}";
    $email_subject = "Message (".$actual_link.")";

  
  function Valid_Email($data){
    $pattern = '/^([0-9a-z]([-.\w]*[0-9a-z])*@(([0-9a-z])+([-\w]*[0-9a-z])*\.)+[a-z]{2,6})$/i';
    if (preg_match($pattern,$data)){
      return $data;
    }
    else{
      return $GLOBALS['mailto'];
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
    
    $email_message = "Form data:\n\n";
    $email_message .= "IP address: ".getUserIpAddr()."\n";
    
    foreach($_POST as $key => $value) {            
      
      $has_a = strpos($value, '@'); 
      if ($has_a > 0){    
            if(filter_var($value, FILTER_VALIDATE_EMAIL)) {
              $email_from = valid_email($value);
            } else {
              $email_from = $email_to;
            }      
      }
      if ($key !== 'submit') {
                 $email_message .= $key." : ".clean_string($value)."\n";
      }
    }
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/plain;charset=UTF-8" . "\r\n";
    $headers .= 'From: '.$email_from. "\r\n";
    
    mail($email_to, $email_subject, $email_message, $headers);
    header('Location: /');
   
?>

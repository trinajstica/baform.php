<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
    $email_to = "YOUR_EMAIL";
    
    $success = "{$_SERVER['HTTP_REFERER']}";
    $error = "{$_SERVER['HTTP_REFERER']}";
    
    
// PUT THIS <script>...</script> IN YOUR INDEX.HTML SOMEWHERE IN BODY, REPLACE WITH YOUR WORDS/NUMBERS
/*

<script>
    var myArray = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"];
    var rand = myArray[Math.floor(Math.random() * myArray.length)];
    document.getElementsByName('honeypot')[0].placeholder='Enter the number '+rand;
</script>

*/

// PUT IN HERE SAME WORDS/NUMBERS AS UP THERE IN <script>...
				
     $os = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10");
        
    // **********************************************************************
    
    try {
	  $_POST = array_map("strip_tags", $_POST);
	  $_POST = array_map("htmlspecialchars", $_POST);
 
      // if we have input field named honeypot, we check its value
      if ($_POST['honeypot']) {
        $honeypot = mb_strtoupper($_POST['honeypot'], "UTF-8");   
        if (!in_array($honeypot, $os)) {
            header("Location: ".$error);
            exit();
        }
      }
    
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
          if ($key !== 'submit' && $key !== 'honeypot' && $key !== 'gdpr') {
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
}
?>

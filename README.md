# baform.php
php form handler better version
<br>
if you want some simulation of the recaptcha, then just add on your form text input field with name "honeypot" and read baform.php commets for other actions you need to do...
<br><br>
Or just do this:
<br><br>
1) put this script in body of your site:
<br><br>
<script><br>
    var myArray = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"];<br>
    var rand = myArray[Math.floor(Math.random() * myArray.length)];<br>
    document.getElementsByName('honeypot')[0].placeholder='Enter the number '+rand;<br>
</script>
<br><br>
Change MyArray with your numbers or words.<br>
<br>
2) Change this baform.php to match your changes in the script:
<br><br>
$os = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10");
<br><br>
that's it

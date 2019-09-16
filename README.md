# baform.php
php form handler better version

if you want some simulation of the recaptcha, then just add on your form text input field with name "honeypot" and read baform.php commets for other actions you need to do...

Or just do this:

1) put this script in body of your site:

<script>
    var myArray = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"];
    var rand = myArray[Math.floor(Math.random() * myArray.length)];
    document.getElementsByName('honeypot')[0].placeholder='Enter the number '+rand;
</script>

Change MyArray with your numbers or words.

2) Change this baform.php to match your changes in the script:

$os = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10");

that's it

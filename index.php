<!doctype html>
<html>

  <head>
    <meta charset="utf-8"/>

    <?php // MAKE SURE THE FILENAME AND PATH ARE CORRECT ?>
    <link rel="stylesheet" href="../../style.css">

    <?php // WINDOW TITLE: e.g. Project name + Current page name ?>
    <title> Project name + Current page name </title>

    <script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha256.js"></script>
    <script>
    
    function validatePassword(fld) {
    var error = "";
    var illegalChars = /[\W_]/; // allow only letters and numbers
 
    if (fld.value == "") {
        fld.style.background = 'Yellow';
        error = "You didn't enter a password.\n";
        alert(error);
        return false;
 
    } else if ((fld.value.length < 7) || (fld.value.length > 15)) {
        error = "The password is the wrong length. \n";
        fld.style.background = 'Yellow';
        alert(error);
        return false;
 
    } else if (illegalChars.test(fld.value) < 1) {
        error = "The password must contain atleast one special characters.\n";
        fld.style.background = 'Yellow';
        alert(error);
        return false;
 
    } else if ( (fld.value.search(/[a-zA-Z]+/)==-1) || (fld.value.search(/[0-9]+/)==-1) ) {
        error = "The password must contain at least one numeral.\n";
        fld.style.background = 'Yellow';
        alert(error);
        return false;
 
    } else {
        fld.style.background = 'White';
    }
   return true;
}

function validate_email(field,alerttxt)
{
    with (field)
    {
        apos=value.indexOf("@");
        dotpos=value.lastIndexOf(".");
        if (apos<1||dotpos-apos<2){
            alert(alerttxt);return false;
        }
        else {
            return true;
        }
    }
}

      function validateForm() {
      
         if(!validatePassword(document.login.password1)) return false;
         if(!validate_email(document.login.email1)) return false;
          // Client-side validation
          // This should be 1+2 ie 3
          var answer=document.forms["login"]["answer"].value;
          // The two emails should match
          var email1=document.forms["login"]["email1"].value;
          var email2=document.forms["login"]["email2"].value;
          // The two passwords should match
          var password1=document.forms["login"]["password1"].value;
          var password2=document.forms["login"]["password2"].value;
          // Let's find out!
          // This is wall 1.1, client-side data validation
          if ( answer != 3
                   || !email1 || !email2 || email1 != email2
                   || !password1 || !password2 || password1 != password2 ) {
              // Something not right
              alert("Please check your input values.");
              // We do not contact the server
              return false;
          } else {
              // Here, we're now at wall 1.2, client-side data sanitation.
              // To keep it simple, in this template there is no such wall :-\
              // We're going straight to wall 2, client-server communication.
              // Wall 3 will have to be strong!
              // Ok, let's encrypt the password before we sent it
              document.forms["login"]["encrypted"].value = Encryption::encrypt(password1);
              // Ok, let's hide the password
              document.forms["login"]["password1"].value="";
              // and let's remove the redundant values
              document.forms["login"]["email2"].value="";
              document.forms["login"]["password2"].value="";
              // We contact the server
              return true;
          }
      }
    </script>

  </head>

  <body>

  <?php // NAVIGATION, MAKE SURE THE PATH IS CORRECT
    require_once('../../navig.php');
  ?>

<?php
class Encryption
{
    static $cypher = 'saltdesaiuniversity';
    static $mode   = 'cfb';
    static $key    = '32sdadf3323';
    public function encrypt($plaintext)
    {
        $td = mcrypt_module_open(self::$cypher, '', self::$mode, '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, self::$key, $iv);
        $crypttext = mcrypt_generic($td, $plaintext);
        mcrypt_generic_deinit($td);
        return $iv.$crypttext;
    }   
}
?>
  <h1>Sign-up for new users</h1>

  <p>Sign-up by filling this form.</p>

  <form name="login" action="./result.php" method="post" class="signup" onsubmit="return validateForm()">
    <label>email:</label>
    <input type="text" name="email1" />
    <br/>
    <label>re-enter your email:</label>
    <input type="text" name="email2" />
    <br/>
    <label>password:</label>
    <input type="password" name="password1"/>
    <br/>
    <label>re-enter your password:</label>
    <input type="password" name="password2"/>
    <br/>
    <label>what is 1 plus 2:</label>
    <input type="text" name="answer"/>
    <br/>
    <label></label>
    <input type="submit" name="sign up"/>
    <br/>
    <input type="hidden" name="encrypted"/>
  </form>

  </body>
</html>

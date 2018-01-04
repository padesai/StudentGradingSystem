<!doctype html>
<html>

  <head>
    <meta charset="utf-8"/>

    <?php // MAKE SURE THE FILENAME AND PATH ARE CORRECT ?>
    <link rel="stylesheet" href="../../style.css">

    <?php // WINDOW TITLE: e.g. Project name + Current page name ?>
    <title> Desai Universtiy Grading System + Result Page </title>

  </head>

  <body>

  <?php // NAVIGATION, MAKE SURE THE PATH IS CORRECT
    require_once('../../navig.php');
  ?>

<?php
class Decryption
{
    static $cypher = 'saltdesaiuniversity';
    static $mode   = 'cfb';
    static $key    = '32sdadf3323';

 public function decrypt($crypttext)
    {
        $plaintext = "";
        $td        = mcrypt_module_open(self::$cypher, '', self::$mode, '');
        $ivsize    = mcrypt_enc_get_iv_size($td);
        $iv        = substr($crypttext, 0, $ivsize);
        $crypttext = substr($crypttext, $ivsize);
        if ($iv)
        {
            mcrypt_generic_init($td, self::$key, $iv);
            $plaintext = mdecrypt_generic($td, $crypttext);
        }
        return $plaintext;
    }
    
}
?>
  <h1>Sign-up for new users</h1>


  <?php
    $email = $_POST['email1'];
	$password = Decryption::decrypt($encrypted);
    // Wall 3: Server-side validation
    // Wall 3.1: data validation
    if (filter_var($email, FILTER_VALIDATE_EMAIL)
        && ctype_alnum($password)) {

          // ok, we now move to
          // Wall 3.2, data sanitation
          // hmm, ok, not much to do here, we have a valid email and an alpha-numeric password
          // We'll just make sure they are SQL-clean
          require_once('../../../connectionvars.php');
          $connection = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database)
                        or die('connection error');
          $email = mysqli_real_escape_string($connection, $email);
          $password = mysqli_real_escape_string($connection, $password);
          // Check if the email is already used.
          $query = "SELECT memberEmail FROM users WHERE memberEmail = ?";
          $prepared = mysqli_prepare($connection, $query);
          mysqli_stmt_bind_param($prepared, "s", $email);
          mysqli_stmt_execute($prepared);
          mysqli_stmt_store_result($prepared);
          // retrieve the number of rows only
          $nbRows = mysqli_stmt_num_rows($prepared);
          mysqli_stmt_close($prepared);
          if ($nbRows <= 0) {
              // ok, the email is not used yet apparently, so let's insert it.
              $salt = mt_rand();
              $saltedPassword = hash('sha256', $password . $salt);
              $query = "INSERT INTO users (memberEmail, memberPassword, salt) VALUES (?,?,?)";
              $prepared = mysqli_prepare($connection, $query);
              mysqli_stmt_bind_param($prepared, "sss", $email, $saltedPassword, $salt);
              mysqli_stmt_execute($prepared);
              $nbRows = mysqli_stmt_affected_rows($prepared);
              mysqli_stmt_close($prepared);
              if ($nbRows >= 1) {
                  // The insert succeeded apparently.
                  echo "<p>Your account was created. Please <a href='../login/'>Login</a></p>";
              } else {
                  // The insert failed.
                  echo "<p>Something unexpected happened.</p>";
                  echo "<p><a href='./index.php'>Back to sign-up</a></p>";
              }
          } else {
              echo "<p>Sorry, email already used.</p>";
              echo "<p><a href='./index.php'>Back to sign-up</a></p>";
          }
    } else {
        echo "<p>There was an error with your input values, please try again</p>";
        echo "<p><a href='./index.php'>Back to sign-up</a></p>";
    }
  ?>

  </body>
</html>


<?php
include_once("/usr/var/vulnappconfig/db_config.php"); //including $host,$db,$user,$pass,$options from configuration file
$dsn = "mysql:host=$host;dbname=$db"; //setting up the dsn variable

$pdo = new PDO($dsn, $user, $pass ,$options); //connecting to the database

//Setting basic variables ~ username,password,email
$username=$_POST['username'];
$password=md5($_POST['password']);
$email=$_POST['email'];

//Preparing the statement before binding the above variables
$stm=$pdo->prepare("INSERT INTO logins (USER,PASSW,EMAIL) VALUES (:user,:passw,:email);");
//To insert the db user of the config file must have the appropriate privileges first.

//binding the variables
$stm->bindParam(":user",$username);
$stm->bindParam(":passw",$password);
$stm->bindParam(":email",$email);

//Execute the statement
$stm->execute();

//Clear the pointers and closing the connection with the database.
$pdo=null;
$stm=null;

//Redirecting to login page.
echo "Registered";
echo "<br><a href='/vulnapp/login_form.html'>Get back to login page and log in";


?>
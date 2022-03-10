<?php
session_start();;
include_once("/usr/var/vulnappconfig/db_config.php"); //including $host,$db,$user,$pass,$options from configuration file
if (isset($_POST['username']) && isset($_POST['password'])){
    if(isset($_POST['lang']))
        setcookie("lang",$_POST['lang'],time() + (60*60*24*10),'/',"localhost",0); //expires at 10 days
    else{
        setcookie("username","",time()-60,"/","localhost",0);
        setcookie("password","",time()-60,"/","localhost",0);
    }
    $dsn = "mysql:host=$host;dbname=$db"; //setting up the dsn variable
    $pdo = new PDO($dsn, $user, $pass ,$options); //connecting to the database
    
    //Setting basic variables ~ username,password,email
    $username=$_POST['username'];
    $password= md5( $_POST['password'] );
    $stm=$pdo->prepare("SELECT USER,PASSW FROM logins WHERE USER=:user AND PASSW=:passw ;");
    
    //binding the variables
    $stm->bindParam(":user",$username);
    $stm->bindParam(":passw",$password);
    //Execute the statement
    $stm->execute();

    //Fetch results ~ rows
    $result=$stm->fetch(PDO::FETCH_ASSOC);

    if((isset($result["USER"])) and (isset($result["PASSW"]))){
	    if(md5($password)==$result["PASSW"]){
		    $_SESSION["username"]=$username;
		    $_SESSION["password"]=$_POST["password"];
            $_SESSION["lang"]= $_POST['lang'];
		    echo "You have suscessfully logged in<br>";
		    echo '<a href="http://localhost/vulnerable_app/page.php">click here to go to main page</a>';
		}
	    else
		    echo "WRONG CREDENTIALS";
    }
    else
	    echo "User not found";

    //Clear the pointers and closing the connection with the database.
    $pdo=null;
    $stm=null;

}
else
    echo "WRONG CREDENTIALS";

?>
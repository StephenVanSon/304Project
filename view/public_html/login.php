<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Sign In</title>

    <!-- Bootstrap core CSS -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">
	
	

      <form class="form-signin" action="login.php" method="post">
		<div id="errorDiv" style="display:block; color:red"></div>
		<div>
        <h2 class="form-signin-heading">Please sign in</h2>
        <label for="studNum" class="sr-only">Student Number</label>
        <input name="studNum" id="studNum" type="number" min="10000000" max="99999999" class="form-control" placeholder="Student Number">
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" id="password" class="form-control" placeholder="Password">
		<button class="btn btn-lg btn-primary btn-block" type="submit" name="signInBtn" id="signInBtn">Sign in</button>
		</div>
	  </form>
	  
	  <form class="form-signin" action="register.php" method="get">
	  <h2 class="form-signin-heading">Or Register</h2>
	  <button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
	  </form>

    </div> <!-- /container -->

  </body>
<script type='text/javascript'>
function showEmptyStudNumError()
{
	var errorDiv = document.getElementById("errorDiv");
	if(errorDiv)
		errorDiv.innerHTML = 'Please enter a student number.';
}

function showEmptyPasswordError()
{
	var errorDiv = document.getElementById("errorDiv");
		
	if(errorDiv)
		errorDiv.innerHTML = 'Please enter a password.';
}

function showInvalidPasswordOrNum()
{
	var errorDiv = document.getElementById("errorDiv");
	
	if(errorDiv)
		errorDiv.innerHTML = 'Invalid student number or password entered. Please try again';
	
}
</script>  
  
</html>
<?php
//only perform sign in action when page is posting back to the server
if(isset($_POST["signInBtn"])){

//connect to database
if ($conn=OCILogon("ora_w5y9a", "a20030145", "ug")) {
	echo "<script type='text/javascript'>console.log('Successfully connected to Oracle.');</script>";
		//OCILogoff($c);
}
else {
	$err = OCIError();
	$msg = $err['message'];
	echo "<script type='text/javascript'> console.log('Oracle Connect Error: $msg');</script>";
}

$studNum = $_POST["studNum"];
$uPW = $_POST["password"];

//they didn't type an student number.. show an error
if(empty($studNum))
{
	echo "<script type='text/javascript'>showEmptyStudNumError(); </script>";
}
//no password.. error
else if(empty($uPW))
{
	echo "<script type='text/javascript'>showEmptyPasswordError(); </script>";
}
//there's stuff in both fields.. lets see if its correct
else
{
	
	//query for matching user
	$loginQ = oci_parse($conn, "SELECT STUDENTNUM,EMAIL, UNAME  FROM users WHERE StudentNum=:studNum AND Password=:pw");
	
	oci_bind_by_name($loginQ, ":studNum", $studNum);
	oci_bind_by_name($loginQ, ":pw", $uPW);
	
	$test = oci_execute($loginQ);
	
	if(!$test)
	{
		
		//query didn't work
		$e = oci_error($loginQ);
		$msg = $e['message'];
		//log the error to the console
		echo "<script type='text/javascript'>console.log('ERROR: $msg');</script>";
		
	}
	$uNum = 0;
	$uEmail = '';
	$uName = '';
	$userExists = false;
	while(($row = oci_fetch_array($loginQ, OCI_BOTH)) != false)
	{
		$userExists = true;
	
		$uNum = $row['STUDENTNUM'];
		$uEmail = $row['EMAIL'];
		$uName = $row['UNAME'];
	}
	
	//login successful
	if($userExists)
	{
		setcookie("username", $uName, time() + (86400 * 30), "/"); 
		setcookie("studNum", $uNum,time() + (86400 * 30), "/");
		//redirect to mainpage
		header('Location: mainPage.php', true, 303);
		exit();
		die();	
	}
	else
	{
		echo "<script type='text/javascript'> showInvalidPasswordOrNum(); </script>";
	}
	
	
	
	
	
	
} //end else
} //end isset

?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Registration</title>

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
<h2 class="form-signin-heading"> Please Register </h2>
<form action="register.php" method="post">
<label class="sr-only" for="uname">Username:</label>
<input type="text" id="uname" name="uname" class="form-control" placeholder="Username"><br>
<label for="password" class="sr-only">Password</label>
<input type="password" id="password" name="password" class="form-control" placeholder="Password"><br>
<label for="retype" class="sr-only">Retype Password:</label>
<input type="password" id="retype" name="retype" class="form-control" placeholder="Retype Password"><br>
<label for="snum" class="sr-only">UBC Student Number:</label>
<input type="text" id="snum" name="snum" class="form-control" placeholder="UBC Student Number"><br>
<label for="email" class="sr-only">Email address</label>
<input type="email" id="email" name="email" class="form-control" placeholder="Email"><br>
<button name='regBtn' id='regBtn' class="btn btn-lg btn-primary btn-block" type="submit"> Register! </button>
</form>


<?php

if(isset($_POST['regBtn'])){
$conn = oci_connect('ora_w5y9a', 'a20030145', 'ug');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
//only update users table if password is the same as the retyped password
if($_POST["password"] == $_POST["retype"]){

$stid = oci_parse($conn, "INSERT INTO users (studentNum, email, password, uname) VALUES (:sNum, :email, :pw, :uname)");
oci_bind_by_name($stid, ":snum", $_POST["snum"]);
oci_bind_by_name($stid, ":email", $_POST["email"]);
oci_bind_by_name($stid, ":pw", $_POST["password"]);
oci_bind_by_name($stid, ":uname", $_POST["uname"]);
oci_execute($stid);

redirect('mainPage.php');
}

}

function redirect($url, $statusCode = 303)
{
   header('Location: ' . $url, true, $statusCode);
   die();
}
?>

</div> <!-- /container -->
</body>
</html>
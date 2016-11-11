<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Index Page</title>

    <!-- Bootstrap core CSS -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <nav>
  	<a href="login.php">Sign in as Seller</a>
  </nav>

  <body>
  	<?php

  	if ($c=OCILogon("ora_w5y9a", "a20030145", "ug")) {
  		echo "Successfully connected to Oracle.\n";
  		return $c;
  	} else {
  		$err = OCIError();
  		echo "Oracle Connect Error " . $err['message'];
  	}

  	$array = oci_parse($c, "SELECT *
  		FROM USERS");

  	oci_execute($array);

  	while($row=oci_fetch_array($array))

  	{

  		echo $row[0]." ".$row[1];

  	}
  	?>
    
  </body>
</html>

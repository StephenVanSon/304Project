<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>New Textbook Posting</title>

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
<h2 class="form-signin-heading"> New Textbook Posting </h2>
<form method="POST" action="Postings.php">
<!--refresh page when submit-->
<div id="errorDiv" style="display:block; color:red;"></div>
<div id="postingInfo">
<input type="text" name="tPrice" size="6" id="tPrice" class="form-control" placeholder="Price"/>
<textarea type="text" name="tDescription" id="tDescription" size="36" class="form-control" placeholder="Description"></textarea>
<input type="text" name="tISBN" id="tISBN" size="18" class="form-control" placeholder="ISBN"/>
<!--define two variables to pass the value-->
</div>     
<button type="submit" value="Post!" name="insertsubmit" class="btn btn-lg btn-primary btn-block">Post!</button>
</form>

<?php
$studNum = $_COOKIE["studNum"];
echo "<script type='text/javascript'>console.log('student num: $studNum');</script>";
//only need to run this stuff when form is posted
if(isset($_POST["insertsubmit"])){

//open up db connection
$conn=oci_connect("ora_w5y9a", "a20030145", "ug");

if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}


//first check if textbook with ISBN exists in db, if not, add those fields to be filled out
$checkBook = oci_parse($conn, "SELECT * FROM Textbooks WHERE isbn=:isbn");
oci_bind_by_name($checkBook, ":isbn", $_POST["tISBN"]);
oci_define_by_name($checkBook, "title", $numRows);
$cResult = oci_execute($checkBook);
if(!$cResult){
	$e = oci_error($checkBook);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$bookExists = false;
while($row = oci_fetch_array($checkBook, OCI_ASSOC + OCI_RETURN_NULLS))
{
	foreach($row as $item)
	{
		$bookExists = true;
		break;
	
	}
}

oci_free_statement($checkBook);
//if book doesn't exist AND they havent entered a title dynamically insert inputs for book info
if(!$bookExists and !isset($_POST['tTitle']))
{
	$isbn = $_POST["tISBN"];
	$price = $_POST["tPrice"];
	$desc = $_POST["tDescription"];
	//run js code to insert inputs and preserve values from form submit
	echo "<script type='text/javascript'>
		
		
		//inform user why their posting didnt go through
		var errorDiv = document.getElementById('errorDiv');
		errorDiv.innerHTML = 'The textbook does not exist in the database yet, please enter its name';
		errorDiv.removeAttribute('display');
		errorDiv.setAttribute('display', 'block');
		
		var infoDiv = document.getElementById('postingInfo');
		var titleInput = document.createElement('input');
		titleInput.name = 'tTitle';
		titleInput.id = 'tTitle';
		titleInput.type = 'text';
		titleInput.placeholder = 'Textbook Name';
		titleInput.className = 'form-control';
		infoDiv.appendChild(titleInput);
		
		//preserve what they entered
		var price = document.getElementById('tPrice');
		price.value = $price;
		
		var isbn = document.getElementById('tISBN');
		isbn.value = $isbn;
		
		var desc = document.getElementById('tDescription');
		desc.value = '$desc'; 
		</script>";

	
}
else{
$sellQ = oci_parse($conn, "SELECT * FROM Sellers WHERE studentNum=:studNum");
//get student number of student currently logged in
$studNum = $_COOKIE['studNum'];
oci_bind_by_name($sellQ, ":studNum", $studNum);
$sResult = oci_execute($sellQ);
// if(!$sResult){
	// $e = oci_error($a);
    // trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
// }

$sellerExists = false;
while($row = oci_fetch_array($studNum, OCI_ASSOC + OCI_RETURN_NULLS))
{
	foreach($row as $item)
	{
		$sellerExists = true;
		
		break;
	}
	break;
}

//if the seller doesn't exist, insert them into the sellers table
if(!$sellerExists)
{
	$sellIns = oci_parse($conn, "INSERT INTO Sellers(StudentNum) VALUES (:studNum)");
	oci_bind_by_name($sellIns, ":studNum", $studNum);
	$sResult = oci_execute($sellIns);
	
	if(!$sresult){
		$e = oci_error($a);
		trigger_error(htmlentities($e['message'], ent_quotes), e_user_error);
	}
	
}

$sellerSellsTest = oci_parse($conn, "SELECT * from Sellers_sell_textbook where StudentNum=:studNum");
oci_bind_by_name($sellerSellsTest, ":studNum", $studNum);

oci_execute($sellerSellsTest);
$sellerSellsExists = false;
while($row = oci_fetch_array($sellerSellsTest, OCI_ASSOC + OCI_RETURN_NULLS))
{
	foreach($row as $item)
	{
		$sellerSellsExists = true;
		break;
	}
	break;
}



//insert into sellers_sells_textbook
if(!$sellerSellsExists){
$sellerSells = oci_parse($conn, "INSERT INTO Sellers_sell_textbook(StudentNum, ISBN) VALUES (:studNum, :isbn)");
oci_bind_by_name($sellerSells, ":studNum", $studNum);
oci_bind_by_name($sellerSells, ":isbn", $_POST["tISBN"]);



$sResult = oci_execute($sellerSells);
if(!$sResult){
	$e = oci_error($a);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

}

//if textbook doesn't exist in db, insert it.
if(isset($_POST['tTitle']))
{
	$textbookInsert = oci_parse($conn, "INSERT INTO Textbooks (ISBN, Title) VALUES (:isbn, :title)");
	oci_bind_by_name($textbookInsert, ":isbn", $_POST["tISBN"]);
	oci_bind_by_name($textbookInsert, ":title", $_POST["tTitle"]);
	
	$test = oci_execute($textbookInsert);
	
	if(!$test)
	{
		$e = oci_error($textbookInsert);
		$msg = $e['message'];
		//log the error to the console since we can't see server errors
		echo "<script type='text/javascript'> console.log('ERROR: $msg'); </script>";
		
	}
	
}
	
	
//generate unique posting id for primary key
$postID = rand(0, 99999999999);



// preapre SQL statement for exection
$a = oci_parse($conn, "INSERT INTO Posting (POSTID, PRICE, DESCRIPTION, IMAGE, SOLD, ISBN, STUDENTNUM, TIMEPOSTED) VALUES (:pID, :price, :description, 'test.jpg', 'N', :isbn, :studNum, CURRENT_TIMESTAMP)");
oci_bind_by_name($a, ":pID", $postID);
oci_bind_by_name($a, ":price", $_POST["tPrice"]);
oci_bind_by_name($a, ":description", $_POST["tDescription"]);
oci_bind_by_name($a, ":isbn", $_POST["tISBN"]);

oci_bind_by_name($a, ":studNum", $studNum);



//$myPostID = ;

if (!$a){
	$e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
	// Perform logic of the query
$b = oci_execute($a);
if(!$b){
	$e = oci_error($a);
	$msg = $e['message'];
	//log the error to the console since we can't see server errors
	echo "<script type='text/javascript'> console.log('ERROR: $msg'); </script>";
    // trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}




// Fetch the results of the query
// print "Posting created successfully! This is the tuple you created:";
// print "<table border='1'>\n";
// $ncols = oci_num_fields($a);
	// for ($i = 1; $i <= $ncols; $i++){
		// $column_name = oci_field_name($a, $i);
		// print "<th>". $column_name. "</th>";
	// }
// while($row = oci_fetch_array($a, OCI_ASSOC + OCI_RETURN_NULLS)){
	// print "<tr\n>";
	// foreach($row as $item){
		// print "<td>".($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
	// }
	// print "</tr>\n";
// }
// print "</table>\n";

oci_free_statement($stid);
oci_close($conn);

}

}

?>

</div>
</body>
</html>
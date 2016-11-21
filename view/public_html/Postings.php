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
<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Welcome to Textbooks @ UBC!</a>
				<ul class="nav navbar-nav">
					<li><a href="mainPage.php">Main Page</a></li>
					<li class='active'><a href='Postings.php'>New Posting</a></li>
					<?php
						$uname = $_COOKIE["username"];
						if(empty($uname)){
							echo "<li><a href='login.php'>Login</a></li>";
							echo "<li><a href='register.php'>Register</a></li>";
						}
						else
						{
							echo "<li><a href='logout.php'>Logout</a></li>";
						}
					?>
					
				</ul>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
				<form class="navbar-form navbar-right" action="search.php" method="get">
					<input type="text" class="form-control" name="search" placeholder="Search textbooks by...">
				</form>
			</div>
		</div>
</nav>

<div class="container" style='padding-top:70px'>
<h2 class="form-signin-heading"> New Textbook Posting </h2>
<form method="POST" action="Postings.php">
<!--refresh page when submit-->
<div id="errorDiv" style="display:block; color:red;"></div>
<div id="postingInfo">
<input type="text" name="tPrice" size="6" id="tPrice" class="form-control" placeholder="Price"/>
<textarea type="text" name="tDescription" id="tDescription" size="36" class="form-control" placeholder="Description"></textarea>
<input type="number" name="tISBN" id="tISBN" min="1000000000000" max="9999999999999" class="form-control" placeholder="ISBN"/>
<!--define two variables to pass the value-->
</div>     
<button type="submit" value="Post!" name="insertsubmit" class="btn btn-lg btn-primary btn-block">Post!</button>
</form>
</div>
</body>
<script type='text/javascript'>
	function insertTextbookInfoFields(textbookFieldsExist)
	{
		if(!textbookFieldsExist)
			displayError('The textbook does not exist in the database yet, please enter its name.');
		else
			displayError('Please fill in every field for the textbook');
		
		var infoDiv = document.getElementById('postingInfo');
		
		//add textbook name field
		insertField('tTitle', 'Textbook Name', infoDiv, 'text');
		
	
		//add author name field
		insertField('tAuthor', 'Main Author', infoDiv, 'text');
		
		//insert coursecode field
		insertField('tCourseCode', 'Course Code', infoDiv, 'text', '4');
	
		//insert coursenum field
		insertField('tCourseNum', 'Course Number', infoDiv, 'number', '999', '100');

	}
	
	function insertField(fieldName, placeholder, parent, type, max, min)
	{
		var field = document.createElement('input');
		field.name = fieldName;
		field.id = fieldName;
		field.type = type;
		field.placeholder  = placeholder;
		field.className = 'form-control';
		
		if(type === 'text' && max)
			field.maxlength = max;

		if(type === 'number' && max)
			field.max = max;
		if(type === 'number' && min)
			field.min = min;
		
		parent.appendChild(field);
		
	}
	
	function persistFieldsOnPostback(priceField, isbnField, descField)
	{
	    //preserve what they entered
		if(priceField){
			var price = document.getElementById('tPrice');
			price.value = priceField;
		}
		
		if(isbnField){
			var isbn = document.getElementById('tISBN');
			isbn.value = isbnField;
		}
		
		if(descField){
			var desc = document.getElementById('tDescription');
			desc.value = descField;
		}
		
	}
	
	function displayError(errorMessage){
		//inform user why their posting didnt go through
		var errorDiv = document.getElementById('errorDiv');
		errorDiv.innerHTML = errorMessage;
		errorDiv.removeAttribute('display');
		errorDiv.setAttribute('display', 'block');
	}
	
	function redirectToMainPage()
	{
		window.location = "mainPage.php";
	}
 </script>
</html>

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
$checkBook = oci_parse($conn, "SELECT COUNT(*) AS COUNT_TEXT FROM  (SELECT * from Textbooks WHERE ISBN=:isbn)");
oci_bind_by_name($checkBook, ":isbn", $_POST["tISBN"]);
oci_define_by_name($checkBook, "COUNT_TEXT", $numRows);

$cResult = oci_execute($checkBook);
if(!$cResult){
	$e = oci_error($checkBook);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$bookExists = false;
oci_fetch($checkBook);
if($numRows > 0){
	$bookExists = true;
}
echo "<script type='text/javascript'> console.log('NUMROWS = $numRows'); </script>";

oci_free_statement($checkBook);

//check if every field is set
$somethingNotSet = (!isset($_POST['tTitle']) or !isset($_POST['tAuthor']) or !isset($_POST['tCourseCode']) or !isset($_POST['tCourseNum']));
$everythingNotSet = (!isset($_POST['tTitle']) and !isset($_POST['tAuthor']) and !isset($_POST['tCourseCode']) and !isset($_POST['tCourseNum']));
//if book doesn't exist AND they haven't entered all necessary fields for textbook then throw an error and insert fields
if(!$bookExists and $somethingNotSet)
{
	$isbn = $_POST["tISBN"];
	$price = $_POST["tPrice"];
	$desc = $_POST["tDescription"];
	//run js code to insert inputs and preserve values from form submit
	if($everythingNotSet){
	echo "<script type='text/javascript'>
		insertTextbookInfoFields(false);
		persistFieldsOnPostback($price, $isbn, '$desc');
		</script>";
	}else
	{
		echo "<script type='text/javascript'>
		insertTextbookInfoFields(true);
		persistFieldsOnPostback($price, $isbn, '$desc');
		</script>";
	}
}
else {
$sellQ = oci_parse($conn, "SELECT COUNT(*) as COUNT_SELLER FROM (SELECT * from Sellers WHERE studentNum=:studNum)");
//get student number of student currently logged in
$studNum = $_COOKIE['studNum'];
oci_define_by_name($sellQ, 'COUNT_SELLER', $countSeller);
oci_bind_by_name($sellQ, ":studNum", $studNum);
$sResult = oci_execute($sellQ);
if(!$sResult){
	$e = oci_error($sellQ);
	$msg = $e['message'];
	echo "<script type='text/javascript'> console.log('SELLER EXISTS ERROR: $msg'); </script>";
    //trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$sellerExists = false;
oci_fetch($sellQ);

if($countSeller > 0)
{
	
	$sellerExists = true;
}

//if the seller doesn't exist, insert them into the sellers table
if(!$sellerExists)
{
	
	$sellIns = oci_parse($conn, "INSERT INTO Sellers(StudentNum) VALUES (:studNum)");
	oci_bind_by_name($sellIns, ":studNum", $studNum);
	$sResult = oci_execute($sellIns);
	echo "<script type='text/javascript'> console.log('seller does not exist'); </script>";
	if(!$sResult){
		$e = oci_error($sellIns);
		$msg = $e['message'];
		echo "<script type='text/javascript'> console.log('ERROR INSERTING: $msg'); </script>";
		//trigger_error(htmlentities($e['message'], ent_quotes), e_user_error);
	}
	
}
else
{
	 echo "<script type='text/javascript'> console.log('seller exists'); </script>";
}




$testTb = true;
$testA = true;
$testC = true;
$testCT = true;
//if textbook doesn't exist in db, insert it.
if(isset($_POST['tTitle']))
{
	$textbookInsert = oci_parse($conn, "INSERT INTO Textbooks (ISBN, Title) VALUES (:isbn, :title)");
	oci_bind_by_name($textbookInsert, ":isbn", $_POST["tISBN"]);
	oci_bind_by_name($textbookInsert, ":title", $_POST["tTitle"]);
	
	$testTb = oci_execute($textbookInsert);
	
	//log error
	if(!$testTb)
	{
		$e = oci_error($textbookInsert);
		$msg = $e['message'];
		//log the error to the console since we can't see server errors
		echo "<script type='text/javascript'> console.log('TEXTBOOK INSERT ERROR: $msg'); </script>";
		
	}
	
	$authorInsert = oci_parse($conn, "INSERT INTO AUTHORS_IN_TEXTBOOK(ANAME, ISBN) VALUES (:aname, :isbn)");
	oci_bind_by_name($authorInsert, ":isbn", $_POST["tISBN"]);
	oci_bind_by_name($authorInsert, ":aname", $_POST["tAuthor"]);
	
	$testA = oci_execute($authorInsert);
	
	//log error
	if(!$testA)
	{
		$e = oci_error($authorInsert);
		$msg = $e['message'];
		echo "<script type='text/javascript'> console.log('AUTHOR INSERT ERROR: $msg'); </script>";
	}
	
	$courseInsert = oci_parse($conn, "INSERT INTO COURSE(COURSECODE, COURSENUM) VALUES (:cCode, :cNum)");
	oci_bind_by_name($courseInsert, ":cCode", $_POST["tCourseCode"]);
	oci_bind_by_name($courseInsert, ":cNum", $_POST["tCourseNum"]);
	
	$testC = oci_execute($courseInsert);
	
	//log error
	if(!$testC)
	{
		$e = oci_error($courseInsert);
		$msg = $e['message'];
		echo "<script type='text/javascript'> console.log('COURSE INSERT ERROR: $msg'); </script>";
	}
	
	
	$courseTextInsert = oci_parse($conn, "INSERT INTO COURSE_OF_TEXTBOOK(ISBN,COURSECODE,COURSENUM) VALUES (:isbn, :cCode, :cNum)");
	oci_bind_by_name($courseTextInsert, ":isbn", $_POST["tISBN"]);
	oci_bind_by_name($courseTextInsert, ":cCode", $_POST["tCourseCode"]);
	oci_bind_by_name($courseTextInsert, "cNum", $_POST["tCourseNum"]);
	
	
	$testCT = oci_execute($courseTextInsert);
		//log error
	if(!$testCT)
	{
		$e = oci_error($courseTextInsert);
		$msg = $e['message'];
		echo "<script type='text/javascript'> console.log('COURSE TEXT INSERT ERROR: $msg'); </script>";
	}
	
	
}

$sellerSellsTest = oci_parse($conn, "SELECT COUNT(*) as SELLER_SELLS_COUNT from (SELECT * from Sellers_SELL_TEXTBOOK WHERE studentNum=:studNum AND isbn=:isbn)");
oci_bind_by_name($sellerSellsTest, ":studNum", $studNum);
oci_bind_by_name($sellerSellsTest, ":isbn", $_POST["tISBN"]);
oci_define_by_name($sellerSellsTest, "SELLER_SELLS_COUNT", $sellerSellsCount);
oci_execute($sellerSellsTest);
oci_fetch($sellerSellsTest);
$sellerSellsExists = false;
if($sellerSellsCount > 0){
	$sellerSellsExists = true;
	echo "<script type='text/javascript'> console.log('seller sells exists'); </script>";
	
}
else
{
	echo "<script type='text/javascript'> console.log('seller sells not exists'); </script>";
}





//insert into sellers_sells_textbook
if(!$sellerSellsExists){
$sellerSells = oci_parse($conn, "INSERT INTO SELLERS_SELL_TEXTBOOK(StudentNum, ISBN) VALUES (:studNum, :isbn)");
oci_bind_by_name($sellerSells, ":studNum", $studNum);
$isbn = $_POST["tISBN"];
oci_bind_by_name($sellerSells, ":isbn", $isbn);



$sResult = oci_execute($sellerSells);
if(!$sResult){
	$e = oci_error($sellerSells);
	$msg = $e['message'];
	echo "<script type='text/javascript'> console.log('SELLERS SELL INSERT ERROR: $msg, studNum: $studNum, isbn: $isbn'); </script>";
    //trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
}
	
	
//generate unique posting id for primary key
$postID = rand(0, 99999999999);



// prepare SQL statement for exection
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
	echo "<script type='text/javascript'> console.log('POSTING INSERT ERROR: $msg'); </script>";
    // trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

//redirect to mainpage
// echo "<script type='text/javascript'> console.log('redirecting...'); </script>";
// header('Location: mainPage.php', true, 303);
// exit();
// die();	
echo "<script type='text/javascript'> redirectToMainPage();</script>";
}
}
?>
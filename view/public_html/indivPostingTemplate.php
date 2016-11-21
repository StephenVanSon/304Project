<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

	<title>Posting</title>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

	<!-- Bootstrap core CSS -->
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="bootstrap.min.css">
	<link rel="stylesheet" href="posting.css">


	<!-- Custom styles for this template -->
	<!--<link rel="stylesheet" href="main.css">-->


</head>
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="mainPage.php">Welcome to Textbooks @ UBC!</a>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
				<form class="navbar-form navbar-right" action="search.php" method="get">
					<input type="text" class="form-control" name="search" placeholder="Search textbooks by...">
				</form>
			</div>
		</nav>

<?php
$retrieved_id = $_GET['id'];
#print "This is the page for the post of postId: " . $retrieved_id;

$conn=oci_connect("ora_w5y9a", "a20030145", "ug");
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
  // prepare SQL statement for execution
$a = oci_parse($conn, "SELECT t.title, p.price, p.description, p.image, p.ISBN, p.timePosted, u.uName, u.studentNum, u.email, a.aname
					   	FROM Posting p, Users u, Textbooks t, Authors_in_Textbook a 
					   	WHERE p.studentNum = u.studentNum 
					   		AND p.ISBN = t.ISBN 
					   		AND t.ISBN = a.ISBN 
					   		AND p.postId = '$retrieved_id'");
if (!$a){
	$e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
	// Perform logic of the query
$b = oci_execute($a);
if(!$b){
	$e = oci_error($a);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
// Fetch the results of the query

$info = oci_fetch_array($a, OCI_ASSOC + OCI_RETURN_NULLS);
$title = $info['TITLE'];
$desc = $info['DESCRIPTION'];
$uname = $info['UNAME'];
$time = $info['TIMEPOSTED'];
$formatTime = date_format($time, 'g:ia \o\n l jS F Y');
$email = $info['EMAIL'];
$aname = $info['ANAME'];
$snum = $info['STUDENTNUM'];
$price = $info['PRICE'];
#$image = $info['IMAGE'];

oci_free_statement($stid);
oci_close($conn);
?>

<div class="col-sm-12">
<table class="table table-bordered">
	<thead>
		<tr>
			<th><?php echo "$title by $aname"?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?php echo "Price: $$price"?></td>
		</tr>
		<tr>
			<td><?php echo "Comments: $desc"?></td>
		</tr>
		<tr>
			<td><?php echo "Posted by: $uname on $time"?></td>
		</tr>
		<tr>
			<td><?php echo "Contact: <a href=$email>$email</a> for more information"?></td>
		</tr>
	</tbody>
</table>
</div>
<div style='text-align:center'>
<?php
if ($snum == $_COOKIE[studNum]) {
	#print "<button>Hello</button>";
	print "<button id='editButton' class='btn btn-small btn-primary'>
			Edit Posting
		   </button>";

	print "<button id='deleteButton' class='btn btn-small btn-primary'>
			Delete Posting
		   </button>";

}
?>
</div>

<div id="formToEdit" style='text-align: center; padding-top:8px' class='container'>
	<form action="edit.php" method="get">
		<input class='form-control' type="text" placeholder="Description" name="new_description">
		<input class='form-control' type="number" placeholder="Price" name="new_p">
		<input class='form-control' type="hidden" name='postID' value="<?php print $retrieved_id;?>">
		<input class='btn btn-small btn-primary' type="submit" value="Submit Changes">
	</form>
</div>
<div id="deletionsPrompt" style='text-align:center; padding-top:8px' class='container'>
	Are you sure?
	<form action="delete.php" method="get">
		<input class='btn btn-small btn-primary' type="submit" value="Yes">
		<input class='form-control' type="hidden" name='postID' value="<?php print $retrieved_id;?>">
	</form>
	<button id="noButton" class='btn btn-small btn-primary'>No</button>
</div>




<script src="jquery-3.0.0.js"></script>
<script>
$(function () {
	$("#formToEdit").hide();
	$("#deletionsPrompt").hide();

	$("#editButton").click(function() {
		$("#deletionsPrompt").hide();
		$("#formToEdit").show();
	})

	$("#deleteButton").click(function() {
		$("#formToEdit").hide();
		$("#deletionsPrompt").show();
	})

	$("#noButton").click(function() {
		$("#deletionsPrompt").hide();
	})
});
</script>

</html>

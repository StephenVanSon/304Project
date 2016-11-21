<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

	<title>Search</title>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="sorttable.js"></script>

	<!-- Bootstrap core CSS -->
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="bootstrap.min.css">

	<!-- Custom styles for this template -->
	<link rel="stylesheet" href="main.css">


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
				<a class="navbar-brand" href="#">Welcome to Textbooks @ UBC!</a>
				<ul class="nav navbar-nav">
					<li><a href="mainPage.php">Main Page</a></li>
					<li><a href='Postings.php'>New Posting</a></li>
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

<div class="col-sm-8 col-sm-offset-4 col-md-9 col-md-offset-3 main" style='padding-top:70px'>
<h1 class="page-header">Check out the textbooks we currently have for sale!</h1>
<h4>Use a filter or the search textbox to narrow down your search.</h4>

<?php
$search_entry = $_GET["search"];
print "Your search entry is: " . $search_entry;

$conn=oci_connect("ora_w5y9a", "a20030145", "ug");
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
  // prepare SQL statement for execution
$filtered_Entries = "SELECT t.title, p.ISBN, p.description, p.price, p.timePosted, c.courseCode, c.courseNum, p.postId FROM Posting p, Textbooks t, course_of_textbook c WHERE c.ISBN = p.ISBN AND p.ISBN = t.ISBN AND (t.title LIKE '%$search_entry%' OR p.ISBN LIKE '$search_entry' OR c.courseCode LIKE '$search_entry')";
$a = oci_parse($conn, $filtered_Entries);
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

echo "<table class='sortable table table-bordered'>\n";
$ncols = oci_num_fields($a) - 2;
	for ($i = 1; $i <= $ncols; $i++){
		$column_name = oci_field_name($a, $i);
		print "<th>". $column_name. "</th>";
	}

while($row = oci_fetch_array($a, OCI_ASSOC + OCI_RETURN_NULLS)){
	print "<tr>\n";
	#foreach($row as $item){
		print "<td><a href='indivPostingTemplate.php?id=".$row['POSTID']."'>".$row['TITLE']."</a></td>";
		print "<td>".$row['ISBN']."</td>";
		print "<td>".$row['DESCRIPTION']."</td>";
		print "<td>"."$".$row['PRICE']."</td>";
		print "<td>".$row['TIMEPOSTED']."</td>";
		print "<td>".$row['COURSECODE'] . " " . $row['COURSENUM'] . "</td>";
		#print "<td>".($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
	print "</tr>\n";
}
print "</table>\n";




oci_close($conn);
?>
</div>
</html>

<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

	<title>Main Page</title>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="sorttable.js"></script>

	<!-- Bootstrap core CSS -->
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="bootstrap.min.css">

	<!-- Custom styles for this template -->
	<link rel="stylesheet" href="main.css">

	<?php 
	$uname = $_COOKIE["username"];
	?>
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
				<?php 
				print '<a class="navbar-brand" href="mainPage.php">Hello ' . $uname . ', Welcome to Textbooks @ UBC!</a>';
				?>
				<ul class="nav navbar-nav">
					<li class='active'><a href="mainPage.php">Main Page</a></li>
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
		</nav>

		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-4 col-md-3 sidebar">
					<a class="btn btn-primary btn-lg btn-block" href="Postings.php" role="button">Submit a new posting!</a>
				</br>
				<div class="well">
					<div class="panel-group">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4 class="panel-title">
									<a data-toggle="collapse" href="#grouping">Grouping Options</a>
								</h4>
							</div>
							<div id="grouping" class="panel-collapse collapse">
								<div class="panel-body">
									<form method="post" action="groupBy.php">
										<div class="radio">
											<label><input type="radio" name="radio" value="c.courseCode, c.courseNum">Course</label>
										</div>
										<div class="radio">
											<label><input type="radio" name="radio" value="c.courseCode">Department</label>
										</div>
										<div class="radio">
											<label><input type="radio" name="radio" value="p.studentNum">Seller Student Number</label>
										</div>
									<button type="submit" name="submit" class="btn btn-primary btn-sm">Submit</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-sm-8 col-sm-offset-4 col-md-9 col-md-offset-3 main">
		<h1 class="page-header">Check out the textbooks we currently have for sale!</h1>
		<h4>Use a filter or the search textbox to narrow down your search.</h4>

		<div class="table-responsive" id="render">
			<?php
			$conn=oci_connect("ora_w5y9a", "a20030145", "ug");
			if (!$conn) {
				$e = oci_error();
				trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
  					// prepare SQL statement for execution
			$all_Entries = 'SELECT t.title, p.ISBN, p.description, p.price, p.timePosted, c.courseCode, c.courseNum, p.postId 
			FROM Posting p, Textbooks t, Course_Of_Textbook c 
			WHERE p.ISBN = t.ISBN 
			AND t.ISBN = c.ISBN
			ORDER BY p.timePosted DESC';

			$a = oci_parse($conn, $all_Entries);
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

			echo "<table class='sortable table table-bordered'>";
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
				print "<td>".$row['PRICE']."</td>";
				print "<td>".$row['TIMEPOSTED']."</td>";
				print "<td>".$row['COURSECODE'] . " " . $row['COURSENUM'] . "</td>";
						#print "<td>".($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
				print "</tr>\n";
			}
			print "</table>\n";



			oci_free_statement($a);
			oci_close($conn);
			?>
		</div>
	</div>
</div>
</div>



</body>
</html>

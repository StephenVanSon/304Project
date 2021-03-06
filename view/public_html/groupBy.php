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
											<label><input type="radio" name="radio[]" value="Course">Course</label>
										</div>
										<div class="radio">
											<label><input type="radio" name="radio[]" value="Dept">Department</label>
										</div>
										<div class="radio">
											<label><input type="radio" name="radio[]" value="StudNum">Seller Student Number</label>
										</div>
									<button type="submit" name="submit" class="btn btn-primary btn-sm">Submit</button>
								</form>
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
				$result= $_POST['radio'];
				$group = $result[0];
				echo "Group is ". $group . "\n";
				
  					// prepare SQL statement for execution
				// $a = oci_parse($conn, 'SELECT :grouping AS "GROUPING", COUNT(*) AS "COUNT", AVG(p.PRICE) AS "AVG", MAX(p.PRICE) AS "MAX", MIN(p.PRICE) AS "MIN"
				// 						FROM Posting p, Course_Of_Textbook c 
				// 						WHERE p.ISBN = c.ISBN 
				// 						GROUP BY :grouping');

				
				// $temp = c.courseCode;
				// if ($group == "Course"){
				// 	$temp = "c.courseCode";
				// } else if ($group == "Dept"){
				// 	$temp = "c.Dept";
				// } else if ($group == "StudNum"){
				// 	$temp = "p.StudNum";
				// }
				// echo "$temp";
				
				$c = oci_parse($conn, 'SELECT c.courseCode AS "GROUPING", COUNT(*) AS "COUNT", AVG(p.PRICE) AS "AVG", MAX(p.PRICE) AS "MAX", MIN(p.PRICE) AS "MIN"
										FROM Posting p, Course_Of_Textbook c 
										WHERE p.ISBN = c.ISBN 
										GROUP BY c.courseCode, c.courseNum');

				$b = oci_parse($conn, 'SELECT c.courseCode AS "GROUPING", COUNT(*) AS "COUNT", AVG(p.PRICE) AS "AVG", MAX(p.PRICE) AS "MAX", MIN(p.PRICE) AS "MIN"
										FROM Posting p, Course_Of_Textbook c 
										WHERE p.ISBN = c.ISBN 
										GROUP BY c.courseCode');

				$a = oci_parse($conn, 'SELECT p.StudentNum AS "GROUPING", COUNT(*) AS "COUNT", AVG(p.PRICE) AS "AVG", MAX(p.PRICE) AS "MAX", MIN(p.PRICE) AS "MIN"
										FROM Posting p, Course_Of_Textbook c 
										WHERE p.ISBN = c.ISBN 
										GROUP BY p.StudentNum');



				if($group == "Course")
					$a = $c;
				else if($group == "Dept")
					$a = $b;
				



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
				$ncols = oci_num_fields($a);
				for ($i = 1; $i <= $ncols; $i++){
					$column_name = oci_field_name($a, $i);
					print "<th>". $column_name. "</th>";
				}
				while($row = oci_fetch_array($a, OCI_ASSOC + OCI_RETURN_NULLS)){
					print "<tr>\n";
						print "<td>".$row['GROUPING']."</td>";
						print "<td>".$row['COUNT']."</td>";
						print "<td>".$row['AVG']."</td>";
						print "<td>".$row['MAX']."</td>";
						print "<td>".$row['MIN']."</td>";
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

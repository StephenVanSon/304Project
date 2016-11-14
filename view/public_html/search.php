<html>
<p>
Welcome to Textbooks@UBC! </br>
Please find below all the textbooks we currently have for sale. </br>
Use a filter or the search textbox to narrow down your search.
</p>

<a href="Postings.php">Submit a new posting!</a>

<p>
<form action="search.php" method="get">
<input type="text" name="search">
<input type="submit" value="Search!">
</form>
<form action="mainPage.php">
<input type="submit" value="Reset" />
</form>
</p>

<?php
$search_entry = $_GET["search"];
print "Your search entry is: " . $search_entry;

$conn=oci_connect("ora_w5y9a", "a20030145", "ug");
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
  // prepare SQL statement for execution
$filtered_Entries = "SELECT t.title, p.ISBN, p.description, p.price, p.timePosted, p.postId FROM Posting p, Textbooks t WHERE p.ISBN = t.ISBN AND t.title LIKE '%$search_entry%'";
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

print "<table border='1'>\n";
$ncols = oci_num_fields($a) - 1;
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
		#print "<td>".($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
	print "</tr>\n";
}
print "</table>\n";



oci_free_statement($stid);
oci_close($conn);
?>
</html>
<html>
<p>
Welcome to Textbooks@UBC! </br>
Please find below all the textbooks we currently have for sale. </br>
Use a filter or the search textbox to narrow down your search.

<a href="Postings.php">Submit a new posting!</a>

</p>
<?php
$conn=oci_connect("ora_w5y9a", "a20030145", "ug");
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
  // prepare SQL statement for execution
$a = oci_parse($conn, 'SELECT t.title, p.ISBN, p.description, p.price, p.timePosted FROM Posting p, Textbooks t WHERE p.ISBN = t.ISBN');
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
$ncols = oci_num_fields($a);
	for ($i = 1; $i <= $ncols; $i++){
		$column_name = oci_field_name($a, $i);
		print "<th>". $column_name. "</th>";
	}

while($row = oci_fetch_array($a, OCI_ASSOC + OCI_RETURN_NULLS)){
	print "<tr>\n";
	#foreach($row as $item){
		print "<td><a href='register.php?id=".$row['TITLE']."'>".$row['TITLE']."</a></td>";
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

<html>
<?php
$retrieved_id = $_GET['id'];
print "This is the page for the post of postId: " . $retrieved_id;

$conn=oci_connect("ora_w5y9a", "a20030145", "ug");
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
  // prepare SQL statement for execution
$a = oci_parse($conn, "SELECT t.title, p.price, p.description, p.image, p.ISBN, p.timePosted, u.uName, u.email FROM Posting p, Users u, Textbooks t WHERE p.studentNum = u.studentNum AND p.ISBN = t.ISBN AND p.postId = '$retrieved_id'");
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
$email = $info['EMAIL'];

print "<h1>" . $title . "</h1>\n";
print "<h1>" . $desc . "</h1>\n";
print "<h2> Posted by: " . $uname . " on " . $time . "</h2>\n";
print "<h2> Contact " . $email . " for more information </h2>\n";

#print "<table border='1'>\n";
#$ncols = oci_num_fields($a) - 1;
#	for ($i = 1; $i <= $ncols; $i++){
#		$column_name = oci_field_name($a, $i);
#		print "<th>". $column_name. "</th>";
#	}
#
#while($row = oci_fetch_array($a, OCI_ASSOC + OCI_RETURN_NULLS)){
#	print "<tr>\n";
#	foreach($row as $item){
#		print "<td>".($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
#		}
#		print "</tr>\n";
#	}
#print "</table>\n";


#print "Unserialized id: " . $unserialized_id;



oci_free_statement($stid);
oci_close($conn);
?>
</html>
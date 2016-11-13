<html>
<p> Create a new textbook posting
</p>

<p> Please provide the following information:
</p>
<p> Price&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Description&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	ISBN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Student Number
</p>
<form method="POST" action="Postings.php">
<!--refresh page when submit-->

   <p><input type="text" name="tprice" size="6"><input type="text" name="tDescription" 
size="36"><input type="text" name="tISBN" size"18"><input type="text" name="tStuNo" 
size="18">
<!--define two variables to pass the value-->
      
<input type="submit" value="insert" name="insertsubmit"></p>
</form>

<?php
$conn=oci_connect("ora_w5y9a", "a20030145", "ug");

if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

  // preapre SQL statement for exection
$a = oci_parse($conn, 'INSERT INTO Postings(POSTID, PRICE, DESCRIPTION, SOLD, ISBN, STUDENTNUM)
	VALUES(myPostID, myPrice,myDesciption, N, myISBN, myStudentNum)');

//$myPostID = ;

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
print "Posting created successfully! This is the tuple you created:";
print "<table border='1'>\n";
$ncols = oci_num_fields($a);
	for ($i = 1; $i <= $ncols; $i++){
		$column_name = oci_field_name($a, $i);
		print "<th>". $column_name. "</th>";
	}
while($row = oci_fetch_array($a, OCI_ASSOC + OCI_RETURN_NULLS)){
	print "<tr\n>";
	foreach($row as $item){
		print "<td>".($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
	}
	print "</tr>\n";
}
print "</table>\n";

oci_free_statement($stid);
oci_close($conn);

?>
</html>
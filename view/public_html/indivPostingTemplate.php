<html>

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
$email = $info['EMAIL'];
$aname = $info['ANAME'];
$snum = $info['STUDENTNUM'];
#$image = $info['IMAGE'];

print "<h1>" . $title . " by " . $aname . "</h1>\n";
print "<h1>" . $desc . "</h1>\n";
print "<h2> Posted by: " . $uname . " on " . $time . "</h2>\n";
print "<h2> Contact " . $email . " for more information </h2>\n";

if ($snum == $_COOKIE[studNum]) {
	#print "<button>Hello</button>";
	print "<button id='editButton'>
			Edit Description
		   </button>
		   <p id='editForm'></p>";

}

oci_free_statement($stid);
oci_close($conn);
?>


<div id="formToEdit"
	<form action="edit.php" method="post">
		<input type="text" name="new_description">
		<input type="hidden" name='postID' value="'$retrieved_id'">
		<input type="submit" value="Submit Changes">
	</form>
</div>

<script src="jquery-3.0.0.js"></script>
<script>
$(function () {
	$("#formToEdit").hide();

	$("#editButton").click(function() {
		$("#formToEdit").show();
	})
});
</script>

</html>

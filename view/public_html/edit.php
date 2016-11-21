<html>
<?php

// Insert into several tables, rolling back the changes if an error occurs

$conn = oci_connect('ora_w5y9a', 'a20030145', 'ug');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

if(isset($_GET['postID'])) $currPID = $_GET['postID'];
#$currPID = $_GET["postID"];

$updateQuery = "UPDATE posting
                SET description = :new_desc, price = :new_price
                WHERE postID = '$currPID'";


$parseIt = oci_parse($conn, $updateQuery);
oci_bind_by_name($parseIt, ":new_desc", $_GET["new_description"]);
oci_bind_by_name($parseIt, ":new_price", $_GET["new_p"]);

// The OCI_NO_AUTO_COMMIT flag tells Oracle not to commit the INSERT immediately
// Use OCI_DEFAULT as the flag for PHP <= 5.3.1.  The two flags are equivalent
$r = oci_execute($parseIt, OCI_NO_AUTO_COMMIT);
if (!$r) {    
   $e = oci_error($parseIt);
    oci_rollback($conn);  // rollback changes to both tables
    trigger_error(htmlentities($e['message']), E_USER_ERROR);
}

// Commit the changes to both tables
$r = oci_commit($conn);
if (!$r) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message']), E_USER_ERROR);
}

print "<h1>Successfully updated!</h1>";


?>
<form action="mainPage.php">
	<button type="submit">Go Back To Main Page</Button>
</form>


</html>

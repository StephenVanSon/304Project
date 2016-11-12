<html>
<body>

<?php
$conn = oci_connect('ora_w5y9a', 'a20030145', 'ug');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$stid = oci_parse($conn, "INSERT INTO users (studentNum, email, password, uname) VALUES (:sNum, :email, :pw, :uname)");
oci_bind_by_name($stid, ":snum", $_POST["snum"]);
oci_bind_by_name($stid, ":email", $_POST["email"]);
oci_bind_by_name($stid, ":pw", $_POST["password"]);
oci_bind_by_name($stid, ":uname", $_POST["uname"]);
oci_execute($stid);
?>

Welcome to Textbook Marketplace, <?php echo $_POST["uname"]; ?>!<br>

</body>
</html>
<html>
<body>

Welcome <?php echo $_POST["name"]; ?><br>
Your email address is: <?php echo $_POST["email"]; ?><br>

<?php
$con = mysqli_connect('127.0.0.1', 'root', 'njupt123', 'date_base');
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
echo "Hello world!<br>";
// some code

?>

</body>
</html>
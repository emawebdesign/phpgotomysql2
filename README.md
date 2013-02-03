phpgotomysql2
=============

phpgotomysql2 is a PHP class that allows you to interact with a MySQL database and execute query and CRUD operations.

include("phpgotomysql.php");
 
$config = array(
  'db_driver'=>'mysqli',
	'db_host'=>'localhost',
	'db_user'=>'root',
	'db_pass'=>'password',
	'db_name'=>'test',
	'charset'=>'utf8'
);
 
$db = new phpgotomysql($config);
 
if ($db->connect()) echo "connect ok";
else echo "connect ko!";

Generic Query

include("phpgotomysql.php");
 
$config = array(
  'db_driver'=>'mysqli',
  'db_host'=>'localhost',
	'db_user'=>'root',
	'db_pass'=>'password',
	'db_name'=>'test',
	'charset'=>'utf8'
);
 
$db = new phpgotomysql($config);
$db->connect();
 
$result = $db->query("SELECT * FROM users");
 
while ($row = mysqli_fetch_assoc($result)) {
 
    echo $row["id"] ." - " .$row["name"] ."<br />";
 
}
 
$db->close();

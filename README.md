phpgotomysql2
=============

phpgotomysql2 is a PHP class that allows you to interact with a MySQL database and execute query and CRUD operations.

include("phpgotomysql.php");
 
$config = array(
  'db_driver'=>'mysqli',<br />
  'db_host'=>'localhost',<br />
  'db_user'=>'root',<br />
  'db_pass'=>'password',<br />
  'db_name'=>'test',<br />
  'charset'=>'utf8'<br />
);
 
$db = new phpgotomysql($config);
 
if ($db->connect()) echo "connect ok";
else echo "connect ko!";

<strong>Generic Query</strong>

include("phpgotomysql.php");
 
$config = array(
  'db_driver'=>'mysqli',<br />
  'db_host'=>'localhost',<br />
  'db_user'=>'root',<br />
  'db_pass'=>'password',<br />
  'db_name'=>'test',<br />
  'charset'=>'utf8'<br />
);
 
$db = new phpgotomysql($config);
$db->connect();
 
$result = $db->query("SELECT * FROM users");
 
while ($row = mysqli_fetch_assoc($result)) {
 
    echo $row["id"] ." - " .$row["name"] ."<br />";
 
}
 
$db->close();

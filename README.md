phpgotomysql2
=============

phpgotomysql2 is a PHP class that allows you to interact with a MySQL database and execute query and CRUD operations.

This is a italian PHPclass :)

<strong>Condizioni di utilizzo (Terms of use)</strong>

Se scarichi e utilizzi la classe significa che hai letto e dato il tuo assenso alle seguenti condizioni:

- EmaWebDesign non si assume alcuna responsabilità, implicita od esplicita, su eventuali danni provocati dall’uso proprio o improprio di questo script.

- chi utilizza questo script lo utilizza completamente a suo rischio e pericolo assumendosene tutte le responsabilità.

License: MIT License http://opensource.org/licenses/MIT

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

<strong>Usage</strong>

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

<strong>Generic Query</strong>

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

/* DEBUG */ echo $db->lastQuery();
 
$db->close();

<strong>countRow</strong>

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
 
$params = array(

    'table'=>'users',
    'where'=>"name='mike'"
    
);
 
$num = $db->countRow($params);
 
echo($num);
 
$db->close();

<strong>select</strong>

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

$params = array(

    'table'=>'users',
    'order'=>'name ASC'
 
);
 
$row = $db->select($params);
 
foreach($row as $value) {

    echo $value["id"] ." - " .$value["name"];
 
}
 
$db->close();

<strong>select with pagination</strong>

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

$page = 1;

if (isset($_GET["page"])) $page = $_GET["page"];
 
$params = array(

    'table'=>'users',
    'order'=>'name ASC',
    'per_page'=>10,
    'page'=>$page
    
);
 
$row = $db->select($params);
 
for ($i=0;$i<$db->numRows();$i++) {

	echo $row[$i]["id"] ." - " .$row[$i]["name"];
 
}
 
echo "Page " .$db->currentPage() ." of " .$db->totPages();
 
$params = array(

    'prev'=>'Prev',
    'next'=>'Next',
    'titlePrev'=>'Go prev',
    'titleNext'=>'Go next'
    
); 
 
echo $db->nextPagination($params);

//OR echo $db->numberPagination("Go number ",10);
 
$db->close();

<strong>read</strong>

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

$params = array(

    'table'=>'users',
    'field'=>'name',
    'condition'=>'id=2'
 
);
 
echo $db->read($params);
 
$db->close();

<strong>select with join (one to many)</strong>

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

$params = array(

    'table'=>'users',
    'order'=>'name ASC',
    'join'=>array(
        array(
        'type'=>'inner',
        'table'=>'user_actions',
        'key'=>'id',
        'foreignKey'=>'user_id'
        ),
        array(
        'type'=>'inner',
        'table'=>'user_sents',
        'key'=>'id',
        'foreignKey'=>'user_id'
        )
    )
    
);
 
$row = $db->select($params);
 
foreach($row as $value) {

    echo $value["id"] ." - " .$value["name"];
 
}
 
$db->close();

<strong>select with join (many to many)</strong>

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

$params = array(

    'table'=>'users',
    'order'=>'name ASC',
    'hasMany'=>array(
        array(
        'table'=>'projects',
        'joinTable'=>'users_projects',
        'key1'=>'id',
        'foreignKey1'=>'user_id',
        'key2'=>'id',
        'foreignKey2'=>'project_id'
        )
    )
    
);
 
$row = $db->select($params);
 
foreach($row as $value) {

    echo $value["id"] ." - " .$value["name"];
 
}
 
$db->close();

<strong>insert</strong>

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

$params = array(

    'name'=>'john',
    'surname'=>'doe'
 
);
 
$result = $db->insert("users",$params);
 
if ($result) echo "insert ok";

else echo "insert ko";
 
$db->close();

<strong>update</strong>

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

$params = array(

    'name'=>'luke',
    'surname'=>'skywalker'
 
);
 
$result = $db->update("users",$params,"id=27");
 
if ($result) echo "update ok";

else echo "update ko";
 
$db->close();

<strong>delete</strong>

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

$result = $db->delete("users","id=27");
 
if ($result) echo "delete ok";

else echo "delete ko";
 
$db->close();

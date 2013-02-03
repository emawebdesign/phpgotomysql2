<?php

/**
 * @name phpGotoMySQL2
 *
 * @description phpgotomysql2 is a PHP class that allows you to interact with a MySQL database 
 *              and execute query and CRUD operations.
 *
 * @author Emanuele Calì aka EmaWebDesign <info@emawebdesign.com>
 * @link 
 * http://www.emawebdesign.com/php-mysql-class-phpgotomysql2-una-classe-php-per-linterazione-con-mysql
 * @version 1.1
 * @license GPL http://www.gnu.org/licenses/gpl.html
 *
 */
 
 class phpgotomysql {
  
	private $db_driver = 'mysqli';
	private $db_host = 'localhost';
    private $db_user = 'root';
    private $db_pass = '';
    private $db_name = '';
	private $charset = 'utf8';
	
	private $connection = false;
	private $result = false;
	private $rows = array();
	private $errorMessage = "";
	private $lastSql = "";
	
	private $tot_pages = 0;
	private $current_page = 0;
	private $querystring = "";
	
	
	
	
	/*
     * Costruttore, permette di settare i parametri di connessione al database
	 * @param params, un array associativo contenente i parametri di connessione: 
	 *        driver, host, username, password, nome del database e set di caratteri.
	 *        Come driver si può scegliere mysql o mysqli.
     */
	function __construct($params = array()) {
		
		if (isset($params["db_driver"])) $this->db_driver = $params["db_driver"];
		if (isset($params["db_host"])) $this->db_host = $params["db_host"];
		if (isset($params["db_user"])) $this->db_user = $params["db_user"];
		if (isset($params["db_pass"])) $this->db_pass = $params["db_pass"];
		if (isset($params["db_name"])) $this->db_name = $params["db_name"];
		if (isset($params["charset"])) $this->charset = $params["charset"];
		
	}
	
	
	
	
	/*
     * Questo metodo permette di settare i parametri di connessione al database
	 * @param params, un array associativo contenente i parametri di connessione: 
	 *        driver, host, username, password, nome del database e set di caratteri.
	 *        Come driver si può scegliere mysql o mysqli.
     */
	public function getParams($params = array()) {
		
		if (isset($params["db_driver"])) $this->db_driver = $params["db_driver"];
		if (isset($params["db_host"])) $this->db_host = $params["db_host"];
		if (isset($params["db_user"])) $this->db_user = $params["db_user"];
		if (isset($params["db_pass"])) $this->db_pass = $params["db_pass"];
		if (isset($params["db_name"])) $this->db_name = $params["db_name"];
		if (isset($params["charset"])) $this->charset = $params["charset"];
		
	}
	
	
	
	
	/*
     * Questo metodo esegue la connessione al database
	 * @return ritorna true se la connessione è avvenuta altrimenti false
     */
	public function connect() {
		
		if (!$this->connection) {
			
			if ($this->db_driver=="mysql") {
			
				$conn = mysql_connect($this->db_host,$this->db_user,$this->db_pass);
				mysql_query("SET NAMES '" .$this->charset ."'", $conn);
				$selectDb = mysql_select_db($this->db_name,$conn);
			
			}
			else if ($this->db_driver=="mysqli") {
				
				$conn = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
				mysqli_query($conn,"SET NAMES '" .$this->charset ."'");
				
			}
			
			if ($conn) {
				
                    $this->connection = $conn;
                    return(true);
				
			}
			else return(false);
			
		}
		else return(true);
		
	}
	
	
	
	
	/*
     * Questo metodo esegue la disconnessione al database
	 * @return ritorna true se la connessione è stata chiusa altrimenti false
     */
	 public function close() {
		 
		 if ($this->connection) {
			 
			if ($this->db_driver=="mysql") {
			 
				if (mysql_close($this->connection)) {
					
					$this->connection = false;
					return(true);
				
				}
				else return(false);
			
			}
			else if ($this->db_driver=="mysqli") {
				
				if (mysqli_close($this->connection)) {
					
					$this->connection = false;
					return(true);
				
				}
				else return(false);
				
			}
		 
		 }
		 else return(true);
		 
	 }
	 
	 
	 
	 
	 /*
     * Questo metodo esegue una query SQL
	 * @param sql, la query SQL da eseguire
	 * @return ritorna true se la query è stata eseguita altrimenti false
     */
	 public function query($sql='') {
		 
		 if ($this->db_driver=="mysql") $result = mysql_query($sql);
		 else if ($this->db_driver=="mysqli") $result = mysqli_query($this->connection,$sql);
		 $this->lastSql = $sql;
		 
		 if ($result) {
			 
			 $this->result = $result;
			 			 
			 return($result);
			 
		 }
		 else {
			 		if ($this->db_driver=="mysql") $this->errorMessage = mysql_error($this->connection);
					else if ($this->db_driver=="mysqli") $this->errorMessage = mysqli_error($this->connection);
			 		return(false);
		 }
		 
	 }
	 
	 
	 
	 
	 /*
     * Questo metodo restituisce l'ultima query eseguita
	 * @return ritorna l'ultima query eseguita
     */
	 public function lastQuery() {
		
		return $this->lastSql;
		 
	 }
	 
	 
	 
	 
	 /*
     * Questo metodo restituisce l'ultimo ID generato da una query INSERT
	 * @return ritorna l'ID generato dalla query INSERT
     */
	 public function lastId() {
		 
		 if ($this->db_driver=="mysql") return mysql_insert_id($this->connection);
		 else if ($this->db_driver=="mysqli") return mysqli_insert_id($this->connection);
		 
	 }
	 
	 
	 
	 
	 /*
     * Questo metodo restituisce il numero di record interessati da una query SELECT
	 * @return ritorna il numero di record
     */
	 public function numRows() {
		 
		 if ($this->db_driver=="mysql") return mysql_num_rows($this->result);
		 else if ($this->db_driver=="mysqli") return mysqli_num_rows($this->result);
		 
	 }
	 
	 
	 
	 
	 /*
     * Questo metodo restituisce il numero di record interessati da una query INSERT, UPDATE o DELETE
	 * @return ritorna il numero di record
     */
	 public function affectedRows() {
		 
		 if ($this->db_driver=="mysql") return mysql_affected_rows();
		 else if ($this->db_driver=="mysqli") return mysqli_affected_rows($this->connection);
		 
	 }
	 
	 
	 
	 
	 /*
     * Questo metodo restituisce il messaggio di errore generato da una query fallita
	 * @return ritorna il messaggio di errore
     */
	 public function error() {
		 
		 return $this->errorMessage;
		 
	 }
	 
	 
	 
	 
	 /*
     * Questo metodo verifica se una tabella esiste nel database
	 * @param table, il nome della tabella
	 * @return ritorna true se la tabella esiste altrimenti false
     */
	 public function tableExsist($table='') {
		 
		 if ($this->db_driver=="mysql") $exsist = mysql_query('SHOW TABLES FROM '.$this->db_name.' LIKE "'.$table.'"');
		 else if ($this->db_driver=="mysqli") $exsist = mysqli_query($this->connection,'SHOW TABLES FROM '.$this->db_name.' LIKE "'.$table.'"');
		 $this->lastSql = 'SHOW TABLES FROM '.$this->db_name.' LIKE "'.$table.'"';
		 
		 if ($exsist) {
			 
			 $this->result = $exsist;
			 
			 if ($this->db_driver=="mysql") {
				 
			 	if (mysql_num_rows($exsist)==1) return(true);
			 	else return(false);
			 
			 }
			 else if ($this->db_driver=="mysqli") {
				 
			 	if (mysqli_num_rows($exsist)==1) return(true);
			 	else return(false);
			 
			 }
			 
		 }
		 else return(false);
		 
	 }
	 
	 
	 
	 
	 /*
     * Questo metodo esegue una query di tipo SELECT
	 * @param params, un array associativo contenente i parametri di configurazione della query:
	 * @param $params["table"], la tabella
	 * @param $params["fields"], i campi da estrarre
	 * @param $params["where"], la condizione da rispettare
	 * @param $params["order"], l'ordinamento da rispettare
	 * @param $params["per_page"], il numero di record per pagina
	 * @param $params["page"], la pagina da mostrare in una paginazione di risultati
	 * @param $params["join"], è un'array associativo (un'array di array) che serve a 
	 *        stabilire relazioni tra tabelle di tipo uno a molti, i valori:
	 * @param $params["join"]["type"], indica il tipo di join da eseguire (inner, left, right)
	 * @param $params["join"]["table"], indica la tabella con cui stabilire la relazione
	 * @param $params["join"]["key"], indica la chiave di partenza della relazione
	 * @param $params["join"]["foreignKey"], indica la chiave esterna della relazione
	 * @param $params["hasMany"], è un'array associativo (un'array di array) che serve a 
	 *        stabilire relazioni tra tabelle di tipo molti a molti:
	 * @param $params["hasMany"]["table"], indica la tabella con cui stabilire la relazione
	 * @param $params["hasMany"]["joinTable"], indica la tabella di collegamento tra le due 
	 *        tabelle relazionate
	 * @param $params["hasMany"]["key1"], indica la chiave di partenza della prima 
	 *        tabella relazionata
	 * @param $params["hasMany"]["foreignKey1"], indica la chiave esterna della prima 
	 *        tabella relazionata nella tabella di collegamento
	 * @param $params["hasMany"]["key2"], indica la chiave di partenza della seconda 
	 *        tabella relazionata
	 * @param $params["hasMany"]["foreignKey2"], indica la chiave esterna della seconda 
	 *        tabella relazionata nella tabella di collegamento
	 * @return ritorna un'array associativo contenente i risultati se la query è stata 
	 *         eseguita correttamente altrimenti false
     */
	 public function select($params = array()) {
		
		$table = "";
		$fields = "*";
		$where = NULL;
		$order = NULL;
		$per_page = NULL;
		$page = 1;
		$join = NULL;
		$join_type[] = NULL;
		$join_table[] = NULL;
		$join_key[] = NULL;
		$join_foreignKey[] = NULL;
		$hasMany = NULL;
		$hasMany_table[] = NULL;
		$hasMany_joinTable[] = NULL;
		$hasMany_key1[] = NULL;
		$hasMany_foreignKey1[] = NULL;
		$hasMany_key2[] = NULL;
		$hasMany_foreignKey2[] = NULL;
		
		 
		if (isset($params["table"])) $table = $params["table"];
		if (isset($params["fields"])) $fields = $params["fields"];
		if (isset($params["where"])) $where = $params["where"];
		if (isset($params["order"])) $order = $params["order"];
		if (isset($params["per_page"])) $per_page = $params["per_page"];
		if (isset($params["page"])) $page = $params["page"];
		
		if (isset($params["join"])) {
			
			for ($i=0;$i<count($params["join"]);$i++) {
				
				$join_type[$i] = $params["join"][$i]["type"];
				$join_table[$i] = $params["join"][$i]["table"];
				$join_key[$i] = $params["join"][$i]["key"];
				$join_foreignKey[$i] = $params["join"][$i]["foreignKey"];
			
			}
			
		}
		
		if (isset($params["hasMany"])) {
			
			for ($i=0;$i<count($params["hasMany"]);$i++) {
			
				$hasMany_table[$i] = $params["hasMany"][$i]["table"];
				$hasMany_joinTable[$i] = $params["hasMany"][$i]["joinTable"];
				$hasMany_key1[$i] = $params["hasMany"][$i]["key1"];
				$hasMany_foreignKey1[$i] = $params["hasMany"][$i]["foreignKey1"];
				$hasMany_key2[$i] = $params["hasMany"][$i]["key2"];
				$hasMany_foreignKey2[$i] = $params["hasMany"][$i]["foreignKey2"];
			
			}
			
		}
		
		
		if (isset($params["per_page"])) {
		
			$sql = "SELECT * FROM " .$table;
			
			if (isset($params["join"])) {
				
				for ($i=0;$i<count($params["join"]);$i++)
				$sql .= " " .$join_type[$i] ." JOIN " .$join_table[$i] ." ON " .$table ."." .$join_key[$i] ."=" .$join_table[$i] ."." .$join_foreignKey[$i];
			}
			
			if (isset($params["hasMany"])) {
				
				for ($i=0;$i<count($params["hasMany"]);$i++)
				$sql .= " JOIN " .$hasMany_joinTable[$i] ." ON " .$table ."." .$hasMany_key1[$i] ."=" .$hasMany_joinTable[$i] ."." .$hasMany_foreignKey1[$i] 
			    ." JOIN " .$hasMany_table[$i] ." ON " .$hasMany_table[$i] ."." .$hasMany_key2[$i] ."=" .$hasMany_joinTable[$i] ."." .$hasMany_foreignKey2[$i];
			
			}
			
			if ($this->db_driver=="mysql") {
				
				$count = mysql_query($sql);
				$tot_records = mysql_num_rows($count);
				
			}
			else if ($this->db_driver=="mysqli") {
				
				$count = mysqli_query($this->connection,$sql);
				$tot_records = mysqli_num_rows($count);
				
			}
			
			$this->tot_pages = ceil($tot_records / $per_page);
			$this->current_page = 1;
			$this->current_page = $page;
			$primo = ($this->current_page - 1) * $per_page;
		
		}
		
		 
     		$sql = 'SELECT ' .$fields .' FROM ' .$table;
			
			if (isset($params["join"])) {
				
				for ($i=0;$i<count($params["join"]);$i++)
				$sql .= " " .$join_type[$i] ." JOIN " .$join_table[$i] ." ON " .$table ."." .$join_key[$i] ."=" .$join_table[$i] ."." .$join_foreignKey[$i];
			}
			
			if (isset($params["hasMany"])) {
				
				for ($i=0;$i<count($params["hasMany"]);$i++)
				$sql .= " JOIN " .$hasMany_joinTable[$i] ." ON " .$table ."." .$hasMany_key1[$i] ."=" .$hasMany_joinTable[$i] ."." .$hasMany_foreignKey1[$i] 
			    ." JOIN " .$hasMany_table[$i] ." ON " .$hasMany_table[$i] ."." .$hasMany_key2[$i] ."=" .$hasMany_joinTable[$i] ."." .$hasMany_foreignKey2[$i];
			
			}
			
			if ($where!=NULL) $sql .= ' WHERE ' .$where;
			if ($order!=NULL) $sql .= ' ORDER BY ' .$order;
			if ($per_page!=NULL) $sql .= ' LIMIT ' .$primo ."," .$per_page;
			
			if ($this->db_driver=="mysql") $result = mysql_query($sql);
			else if ($this->db_driver=="mysqli") $result = mysqli_query($this->connection,$sql);
			$this->lastSql = $sql;
		 
			 if ($result) {
				 
				 $this->result = $result;
				 $arr = array();
				 				 
				 for($i=0;$i<$this->numRows();$i++) { 
				  
                 if ($this->db_driver=="mysql") $r = mysql_fetch_array($result);
				 else if ($this->db_driver=="mysqli") $r = mysqli_fetch_array($result); 
                 $key = array_keys($r);  
						
						for ($j=0;$j<count($key);$j++) {  
 
							if (!is_int($key[$j])) { 
							 
								if ($this->numRows()>1) { 
									$arr[$i][$key[$j]] = $r[$key[$j]];  
								}
								else if ($this->numRows()<1) { 
									$arr = NULL;  
								}
								else  
									$arr[0][$key[$j]] = $r[$key[$j]];  
							}
							  
						}
						  
				}  
		
				 return($arr);
				 
			 }
			 else {
						if ($this->db_driver=="mysql") $this->errorMessage = mysql_error($this->connection);
						else if ($this->db_driver=="mysqli") $this->errorMessage = mysqli_error($this->connection);
						return(false);
			 }
		
		 
	 }
	 
	 
	 
	 
	 /*
     * Questo metodo permette di salvare i parametri in querystring per non perderli 
	 * cliccando sui link di una paginazione di dati
	 * @param params, i parametri da salvare scritti nella forma &param1=a&param2=b&param3=c
     */
	 public function setQueryString($params='') {
		 
		 $this->querystring = $params;
		 
	 }
	 
	 
	 
	 
	 /*
     * Questo metodo visualizza una paginazione di dati eseguita con i link "avanti" e "indietro"
	 * @param params, un array associativo contenente le label da visualizzare per i 
	 *        link "avanti" e "indietro"
	 * @param $params["prev"], la label del link "indietro"
	 * @param $params["next"], la label del link "avanti"
	 * @param $params["titlePrev"], la label dell'attributo title del link "indietro"
	 * @param $params["titleNext"], la label dell'attributo title del link "avanti"
	 * @return ritorna l'HTML contenente i link "avanti" e "indietro"
     */
	 public function nextPagination($params = array()) {
		 
		 $labelPrev = "Indietro";
		 $labelNext = "Avanti";
		 $titlePrev = "Vai alla pagina precedente";
		 $titleNext = "Vai alla pagina successiva";
		 
		 if (isset($params["prev"])) $labelPrev = $params["prev"];
		 if (isset($params["next"])) $labelNext = $params["next"];
		 if (isset($params["titlePrev"])) $titlePrev = $params["titlePrev"];
		 if (isset($params["titleNext"])) $titleNext = $params["titleNext"];
		 
		if ($this->current_page == 1) {
			
			$prev = $labelPrev;
			
		} else {
			
			$previous_page = ($this->current_page - 1);
			$prev = "<a href=\"?page=$previous_page" .$this->querystring ."\" title=\"$titlePrev\">" .$labelPrev ."</a>";
			
		}
		
		if ($this->current_page == $this->tot_pages) {
			
			$next = $labelNext;
			
		} else {
			
			$next_page = ($this->current_page + 1);
			$next = "<a href=\"?page=$next_page" .$this->querystring ."\" title=\"$titleNext\">" .$labelNext ."</a>";
			
		}
		
		if ($this->tot_pages>0) return("$prev $next");
		 
	 }
	 
	 
	 
	 
	 /*
     * Questo metodo visualizza una paginazione di dati eseguita con i link 1,2,3 ecc.
	 * @param title, contiene il testo dell'attributo title dei link
	 * @param nearPages, il numero di pagine "contigue" da visualizzare
	 * @return ritorna una lista puntata HTML con ID uguale a "pagination" 
	 *         contenente i link 1,2,3 ecc.
     */
	 public function numberPagination($title='Vai alla pagina numero',$nearPages=5) {
		 	
		//Richiamo la funzione che crea i link per la paginazione	
		$pagination = $this->makePagination($this->tot_pages, $url='', $this->current_page, $nearPages, $title);
		
		if ($this->tot_pages>0) return('<ul id="pagination">' .$pagination .'</ul>');
		 
	 }
	 
	 
	 
	 
	 /*
     * Questo metodo crea il link "non cliccabile" in una paginazione numerica
	 * @param url, la url corrente
	 * @param page, la pagina a cui rimandare
	 * @return ritorna il link "non cliccabile"
     */
	 function makeUrlPagination($url, $page) {
		 
		if (strpos($url,'?') === false) {
			
			return($url . '?page=' . $page .$this->querystring);
			
		} else {
			
			return($url . 'page=' . $page .$this->querystring);
			
		}
		
	}
	
	
	
	
	/*
     * Questo metodo crea il link "cliccabile" in una paginazione numerica
	 * @param url, la url corrente
	 * @param currentPage, la pagina corrente
	 * @param page, la pagina a cui rimandare
	 * @param title, contiene il testo dell'attributo title dei link
	 * @return ritorna la url "cliccabile"
     */
	function makeLinkPagination($url, $currentPage, $page, $title) {
		
		if ($currentPage == $page) {
			
			return($page);
			
		} else {
			
			return('<li><a href="' . $this->makeUrlPagination($url, $page) . '" title="' .$title .' ' .$page .'">' . $page . '</a></li>');
			
		}
		
	}
	 
	 
	 
	 
	 /*
     * Questo metodo crea i link per la paginazione numerica
	 * @param totPages, il numero totale di pagine
	 * @param url, la url corrente
	 * @param currentPage, la pagina corrente
	 * @param nearPages, il numero di pagine "contigue" da visualizzare
	 * @param title, contiene il testo dell'attributo title dei link
	 * @return ritorna i link per la paginazione numerica
     */
	 function makePagination($totPages, $url, $currentPage, $nearPages, $title) {
		
		$pagination = "";
	
		if ($currentPage!=1) {
			
			$pagination .= '<li><a href="' . $this->makeUrlPagination($url, $currentPage - 1) . '" title="' .$title .' ' .($currentPage - 1) .'">&laquo;</a></li> ';
			
		}
	
		$pagination .= $this->makeLinkPagination($url, $currentPage, 1, $title);
	
		if ($currentPage - $nearPages > 2) {
			
			if ($currentPage - $nearPages == 3) {
				
				$pagination .= " " . $this->makeLinkPagination($url, $currentPage, 2, $title);
				
			} else {
				
				$pagination .= " ... ";
				
			}
			
		}
	
		for ($i=$currentPage-$nearPages;$i<=$currentPage+$nearPages;$i++) {

			if ($i<2) continue;
	
			if ($i>$totPages-1) continue;
	
			$pagination .= " " . $this->makeLinkPagination($url, $currentPage, $i, $title);
			
		}
	
		if ($currentPage+$nearPages<$totPages-1) {
			
			if ($currentPage+$nearPages==$totPages-2) {
				
				$pagination .= " " . $this->makeLinkPagination($url, $currentPage, $totPages - 1, $title) . " ";
				
			} else {
				
				$pagination .= " ... ";
				
			}
			
		}
	
		if ($totPages!=1) {
			
			$pagination .= " " . $this->makeLinkPagination($url, $currentPage, $totPages, $title);
			
		}
	
		if ($currentPage!=$totPages) {
			
			$pagination .= ' <li><a href="' . $this->makeUrlPagination($url, $currentPage + 1) . '" title="' .$title .' ' .($currentPage + 1) .'">&raquo;</a></li>';
			
		}
	
		return($pagination);
	}
	 
	 
	 
	 
	 /*
     * Questo metodo restituisce il numero totale di pagine in una paginazione di dati
	 * @return ritorna il numero totale di pagine in una paginazione di dati
     */
	 public function totPages() {
		
		return($this->tot_pages);
		 
	 }
	 
	 
	 
	 
	 /*
     * Questo metodo restituisce la pagina corrente in una paginazione di dati
	 * @return ritorna la pagina corrente in una paginazione di dati
     */
	 public function currentPage() {
		
		return($this->current_page);
		 
	 }
	 
	 
	 
	 
	 /*
     * Questo metodo restituisce il valore di un campo di un record in base a una condizione
	 * @param params, un array associativo contenente le informazioni per estrarre il valore:
	 * @param $params["table"], la tabella in cui leggere
	 * @param $params["field"], il campo di cui si vuole leggere il valore
	 * @param $params["condition"], la condizione da rispettare
	 * @return ritorna il valore del campo
     */
	 public function read($params = array()) {
		 
		 if (isset($params["table"])) $table = $params["table"];
		 if (isset($params["field"])) $field = $params["field"];
		 if (isset($params["condition"])) $condition = $params["condition"];
		 
		 $sql = "SELECT " .$field ." FROM " .$table ." WHERE " .$condition;
		 if ($this->db_driver=="mysql") $result = mysql_query($sql);
		 else if ($this->db_driver=="mysqli") $result = mysqli_query($this->connection,$sql);
		 $this->lastSql = $sql;
		 
		 if ($this->db_driver=="mysql") $num = mysql_num_rows($result);
		 else if ($this->db_driver=="mysqli") $num = mysqli_num_rows($result);
		 
		 if ($this->db_driver=="mysql") {
			 
		 	if ($num>0) return(mysql_result($result,0,$field));
		 	else return(false);
		 
		 }
		 else if ($this->db_driver=="mysqli") {
			 
			$row = $result->fetch_array(MYSQLI_ASSOC);
			$data = $row[$field];
			
			if ($num>0) return($data);
			else return(false);
		 
		 }
		 
	 }
	 
	 
	 
	 
	 /*
     * Questo metodo ripulisce le variabili che dovranno essere memorizzate nel 
	 * database tramite query INSERT/UPDATE in modo da prevenire attacchi di tipo SQL Injection
	 * @param variable, la variabile da ripulire
	 * @return ritorna la variabile ripulita
     */
	 function cleanVar($variable='') {
		 
		 $variable = stripslashes($variable);
		 if ($this->db_driver=="mysql") $variable = mysql_real_escape_string($variable);
		 else if ($this->db_driver=="mysqli") $variable = mysqli_real_escape_string($this->connection,$variable);
		 $variable = strip_tags($variable);
		 $variable = htmlentities($variable, ENT_COMPAT, "UTF-8");
		 $variable = trim($variable);
	 
		 return($variable);
		 
	 }
	 
	 
	 
	 
	 /*
     * Questo metodo esegue una query di tipo INSERT
	 * @param table, la tabella in cui memorizzare le informazioni
	 * @param params, un array associativo contenente le informazioni da memorizzare 
	 *        nel database. Per ogni elemento dell'array, la chiave, indica il nome 
	 *        del campo mentre, il contenuto, il valore da memorizzare
	 * @return ritorna true se la query è stata eseguita altrimenti false
     */
	 public function insert($table='',$params = array()) {
		 
		 $keys = array_keys($params);
		 $values = array_values($params);
		 
		 $fields = implode(",",$keys);
		 
		 $cleanVars = array();
		 
		 foreach($values as $value) {
			 
			$value = $this->cleanVar($value);
		 	if (is_numeric($value)===FALSE) $value = "'" .$value ."'";
			$cleanVars[] = $value;	
			
		 }
		 
		 $data = implode(",",$cleanVars);
		 
		 $sql = "INSERT INTO ";
		 $sql .= $table ." (" .$fields .") VALUES (" .$data .")";
		 
		 if ($this->db_driver=="mysql") $result = mysql_query($sql);
		 else if ($this->db_driver=="mysqli") $result = mysqli_query($this->connection,$sql);
		 $this->lastSql = $sql;
		 
		 if ($result) {
			 
			 $this->result = $result;
			 			 
			 return(true);
			 
		 }
		 else {
			 		if ($this->db_driver=="mysql") $this->errorMessage = mysql_error($this->connection);
					else if ($this->db_driver=="mysqli") $this->errorMessage = mysqli_error($this->connection);
			 		return(false);
		 }
		 
	 }
	 
	 
	 
	 
	 /*
     * Questo metodo esegue una query di tipo UPDATE
	 * @param table, la tabella in cui modificare le informazioni
	 * @param params, un array associativo contenente le informazioni da modificare 
	 *        nel database. Per ogni elemento dell'array, la chiave, indica il 
	 *        nome del campo mentre, il contenuto, il valore da modificare
	 * @param where, la condizione da rispettare
	 * @return ritorna true se la query è stata eseguita altrimenti false
     */
	 public function update($table='',$params = array(),$where=NULL) {
		 
		 if ($where!=NULL) {
		 
			 $keys = array_keys($params);
			 $values = array_values($params);
			 
			 $data = array();
			 
			 for($i=0;$i<count($keys);$i++) {
				 
				$values[$i] = $this->cleanVar($values[$i]);
				if (is_numeric($values[$i])===FALSE) $values[$i] = "'" .$values[$i] ."'";
				
				$data[] = $keys[$i]	."=" .$values[$i];
				
			 }
			 
			 $sql = "UPDATE " .$table ." SET " .implode(',',$data) ." WHERE " .$where;
			 
			 if ($this->db_driver=="mysql") $result = mysql_query($sql);
			 else if ($this->db_driver=="mysqli") $result = mysqli_query($this->connection,$sql);
			 $this->lastSql = $sql;
			 
			 if ($result) {
				 
				 $this->result = $result;
							 
				 return(true);
				 
			 }
			 else {
						if ($this->db_driver=="mysql") $this->errorMessage = mysql_error($this->connection);
						else if ($this->db_driver=="mysqli") $this->errorMessage = mysqli_error($this->connection);
						return(false);
			 }
		 
		 }
		 else return(false);
		 
	 }
	 
	 
	 
	 
	 /*
     * Questo metodo esegue una query di tipo DELETE
	 * @param table, la tabella in cui eliminare le informazioni
	 * @param where, la condizione da rispettare
	 * @return ritorna true se la query è stata eseguita altrimenti false
     */
	 public function delete($table='',$where=NULL) {
		 
		 if ($where!=NULL) {
		 
			 $sql = "DELETE FROM " .$table ." WHERE " .$where;
			 if ($this->db_driver=="mysql") $result = mysql_query($sql);
			 else if ($this->db_driver=="mysqli") $result = mysqli_query($this->connection,$sql);
			 $this->lastSql = $sql;
			 
			 if ($this->db_driver=="mysql") $num = mysql_affected_rows();
			 else if ($this->db_driver=="mysqli") $num = mysqli_affected_rows($this->connection);
			 
			 if ($num>0) return(true);
			 else {
				
					if ($this->db_driver=="mysql") $this->errorMessage = mysql_error($this->connection);
					else if ($this->db_driver=="mysqli") $this->errorMessage = mysqli_error($this->connection);
					return(false);
				 
			 }
		 
		 }
		 else return(false);
		 
	 }
	 
	 
	 
	 
	 /*
     * Questo metodo conta il numero di record che corrispondono a una query
	 * @param params, un array associativo contenente i parametri di configurazione della query:
	 * @param $params["table"], la tabella
	 * @param $params["field"], il campo
	 * @param $params["where"], la condizione da rispettare
	 * @return ritorna il numero di record altrimenti false se la query fallisce
     */
	 function countRow($params = array()) {
		 
		 $table = "";
		 $field = "*";
		 $where = NULL;
		 
		 if (isset($params["table"])) $table = $params["table"];
		 if (isset($params["field"])) $field = $params["field"];
		 if (isset($params["where"])) $where = $params["where"];
		 
		 $sql = "SELECT COUNT(" .$field .") FROM " .$table;
		 if ($where!=NULL) $sql .= ' WHERE ' .$where;
		 
		 if ($this->db_driver=="mysql") $result = mysql_query($sql);
		 else if ($this->db_driver=="mysqli") $result = mysqli_query($this->connection,$sql);
		 $this->lastSql = $sql;
		 
		 if ($result) {
			 
			 $this->result = $result;
			 	
			 if ($this->db_driver=="mysql") $num = mysql_fetch_array($result);
			 else if ($this->db_driver=="mysqli") $num = mysqli_fetch_array($result);
			 return($num[0]);			 
			 
		 }
		 else {
			 		if ($this->db_driver=="mysql") $this->errorMessage = mysql_error($this->connection);
					else if ($this->db_driver=="mysqli") $this->errorMessage = mysqli_error($this->connection);
			 		return(false);
		 }
		 
	 }
	 
	 
	 
	 
 }

?>

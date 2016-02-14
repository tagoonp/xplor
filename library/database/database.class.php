<?php
class database{
	private $host;
	private $dbname;
	private $dbuser;
	private $dbpass;
	private $prefix;
	private $sessionName;
  private $page_title;

	public function __construct(){
		$this->host = "localhost";
		$this->dbname = "xplor";
		$this->dbuser = "root";
		$this->dbpass = "";
		$this->prefix = "dsw1_";
		$this->sessionName = 'sDMIS_';
    $this->page_title = 'DMIS: A Prototype of Disaster Data Management System in the U-Tapao Cathment using Google Map';
	}

	public function connect(){
		$connect = mysql_connect($this->host,$this->dbuser,$this->dbpass);
		mysql_query("SET NAMES UTF8");
		mysql_select_db($this->dbname);
	}

	public function checkConnection($dbn,$user,$passwd){
		if(mysql_connect($this->host,$user,$passwd)){
			if(mysql_select_db($dbn)){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	public function select($strSQL,$isNum,$isFetch){
		$query = mysql_query($strSQL);
		if($query){
			$numRows = -1;
			if($isNum)
				$numRows = mysql_num_rows($query);

			$fetchArray = NULL;
			if($isFetch){
				while($fetchArray[] = mysql_fetch_array($query));
				$last = count($fetchArray)-1;
				unset($fetchArray[$last]);
			}
			if($isFetch && $isNum)
				return array("row" => $numRows,"data"=>$fetchArray);
			else if($isNum)
				return $numRows;
			else if($isFetch)
				return $fetchArray;
			else
				return false;
		}
		else
			return false;
	}

	public function update($sql){
		mysql_query("BEGIN");
		//foreach ($sql as $eachSQL) {
			$query = mysql_query($sql);
			if(!$query){
				mysql_query("ROLLBACK");
				return false;
			}
		//}
		mysql_query("COMMIT");
		return true;
	}

	public function delete($sql){
		$query = mysql_query($sql);
		if(!$query){
				return array('result'=>false);
		}else{
			return array('result'=>true);
		}
	}

	public function insert($sql,$isMulti,$isSameID){
		if($isMulti){
			if($isSameID){ /*multiple insert with same id*/
				mysql_query("BEGIN");
				$first_sql = $sql[0];
				$query = mysql_query($first_sql);
				if(!$query){
					mysql_query("ROLLBACK");
					return array('result'=>false);
				}
				$this_id = mysql_insert_id();
				for($i=1;$i<count($sql);$i++){
					$new_sql = str_replace("%rec_id%",$this_id,$sql[$i]);
					$query = mysql_query($new_sql);
					if(!$query){
						mysql_query("ROLLBACK");
						return array('result'=>false);
					}
				}
				mysql_query("COMMIT");
				return array('result' => true,'id' => $this_id);
			}else{/*Multiple insert without same is*/
				mysql_query("BEGIN");
				foreach ($sql as $eachSQL) {
					$query = mysql_query($eachSQL);
					if(!$query){
						mysql_query("ROLLBACK");
						return array('result'=>false);
					}
				}
				mysql_query("COMMIT");
				return array('result' => true);
			}
		}else{ /*insert single row*/
			$query = mysql_query($sql);
			if(!$query)
				return array('result'=>false);
			$this_id = mysql_insert_id();
			return array('result'=>true,'id'=>$this_id);
		}
	}

	public function disconnect(){
		mysql_close();
	}

	public function getSessionname(){
    return $this->sessionName;
  }

  public function getTitle(){
    return $this->page_title;
  }

	public function getTablePrefix(){
    return $this->prefix;
  }

	public function getHostname(){
    return $this->host;
  }

	public function getUser(){
    return $this->dbuser;
  }

	public function getPassword(){
    return $this->dbpass;
  }

	public function getDbname(){
    return $this->dbname;
  }
}
?>

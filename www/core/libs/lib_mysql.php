<?php if (! defined ( 'START_INDEX' )) exit ( 'No direct script access allowed' );
class MySQL extends PDO {
	public $sql;
	public function __construct($options = null, $db_group = 'DB_Default') {
		try {
			parent::__construct ( 'mysql:host=' . $db_group::DB_HOST . ';port=' . $db_group::DB_PORT . ';dbname=' . $db_group::DB_NAME, $db_group::DB_USER, $db_group::DB_PASS, $options );
			
			$this->exec ( "set names " . $db_group::CHARSET );
		} catch ( PDOException $e ) {
			die ( 'Подключение не удалось: ' . $e->getMessage () );
		}
		$this->setAttribute ( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ );
	}

	//-- Выполнения запроса и получит резултата
	public function query($query = false) {
		if (! $query) {
			$this->param(":p_lang", LANG);
			$this->param(":BASEPATH", BASEPATH);
			$this->param(":p_person_id", $_SESSION['person_id']);
			$query = $this->sql;
		}
		$args = func_get_args ();
		array_shift ( $args );
		
		$reponse = parent::prepare ( $query );
		$reponse->execute ( $args );
		return $reponse;
	}
	//-- Выполнения запроса
	public function insecureQuery($query) {
		return parent::query ( $query );
	}
	
	//-- получит из БД sql запроса
	public function sql($label) {
		$sql = "SELECT * 
				FROM  core_sql_mark t
					where t.label like '{$label}'  OR t.sql_id = '{$label}'
				LIMIT 0 , 1";
		$ret = $this->query ( $sql );
		$i = 0;
		// var_dump($ret->fetch());
		while ( $o = $ret->fetch () ) {
			$this->sql = $o->query;
		}
	}

	//-- Обновления в БД
	public static function CoreUpdate($TableName, $Set, $Where = '1=1') 
	{
		$BDN = new MySQL (null, 'DB_Default');
		$BDN->sql= "UPDATE {TableName} SET {SET} WHERE {WHERE}";
		$BDN->sql = str_replace('{TableName}', $TableName, $BDN->sql);
		$BDN->sql = str_replace('{SET}', $Set, $BDN->sql);
		$BDN->sql = str_replace('{WHERE}', $Where, $BDN->sql);

		$ret = $BDN->query ();
		return true;
	}
	
	//-- Добавления в БД
	public static function CoreInsert($TableName, $Values, $Dublicate = false)
	{
		if(is_array($Values))
		{
			$BDN = new MySQL (null, 'DB_Default');
			$BDN->sql ( 'Core_INSERT' );
			$BDN->sql = str_replace('{TableName}', $TableName, $BDN->sql);
		
			$i = 0;
			foreach ($Values As $key=>$val)
			{
				if ($i == 0) {
					$key_ .= "`" . $key . "`";
					$val_ .=  $val;
				} else {
					$key_ .= ", `" . $key . "`";
					$val_ .= ", " . $val;
				}
				$i++;
			}
			
			$BDN->sql = str_replace('{C}', $key_, $BDN->sql);
			$BDN->sql = str_replace('{VALUES}', $val_, $BDN->sql);
			
			if(is_array($Dublicate))
			{
				$i = 0;
				foreach ($Dublicate As $key1=>$val1)
				{
					if ($i == 0) {
						$rez .= "`".$key1."` = {$val1}";
					} else {
						$rez .= ", `".$key1."` = {$val1}";
					}
					$i++;
				}
				$BDN->sql = $BDN->sql." ON DUPLICATE KEY UPDATE {$rez}";
			}			
			$ret = $BDN->query ();
			
			return true;
		}
		else 
		{
			return false;
		}
	}
	//-- Удалить из БД
	public static function CoreDelete($TableName, $Where = '1=1')
	{
		$BDN = new MySQL (null, 'DB_Default');
		$BDN->sql ( 'Core_DELETE' );
		$BDN->sql = str_replace('{TableName}', $TableName, $BDN->sql);
		$BDN->sql = str_replace('{WHERE}', $Where, $BDN->sql);
		$ret = $BDN->query ();
		return true;
	}
	//-- Получит параметир для БД (Array)
	public function paramArray($value)
	{
		if(is_array($value))
		{
			$i = 0;
			foreach ($value As $key=>$val)
			{
				if ($i == 0) {
					$id_m .= "'" . $val . "'";
				} else {
					$id_m .= ', \'' . $val . "'";
				}
				$i++;
			}
		}
		else
		{
			for($i = 0; $i <= $value->_count - 1; $i ++) {
				if ($i == 0) {
					$id_m .= "'" . $value->$i . "'";
				} else {
					$id_m .= ', \'' . $value->$i . "'";
				}
			}
		}
		return $id_m;
	}
	//-- Получит параметир для БД
	public function param($param, $value, $param_Mysql = '') {
		if ($param_Mysql == 'in') {
			$id_m = $this->paramArray($value);
			$this->sql = str_replace ( $param, $id_m, $this->sql );
		}
		elseif ($value == "NULL")
		{
			$this->sql = str_replace ( $param, 0, $this->sql );
		}
		elseif ($value == "'00'")
		{
			$this->sql = str_replace ( $param, "'00'", $this->sql );
		}
		elseif ($value == '0' And $value != Null)
		{
			$this->sql = str_replace ( $param, 0, $this->sql );
		}
		elseif ($value == 'CONF_PARAM') {
			//$value = $$param_Mysql;
			$value = explode('::', $param_Mysql);

			if (gettype ( $value ) == 'integer') {
				$this->sql = str_replace ( $param, $value, $this->sql );
			} elseif ($value == 'NOW()') {
				$this->sql = str_replace ( $param, 'NOW()', $this->sql );
			} elseif ($value == NULL) {
				$this->sql = str_replace ( $param, 'NULL', $this->sql );
			} else {
				$this->sql = str_replace ( $param, "'" . $value . "'", $this->sql );
			}
		} else {
			if (gettype ( $value ) == 'integer') {
				$this->sql = str_replace ( $param, $value, $this->sql );
			} elseif ($value == 'NOW()') {
				$this->sql = str_replace ( $param, 'NOW()', $this->sql );
			} elseif ($value == NULL) {
				$this->sql = str_replace ( $param, 'NULL', $this->sql );
			} else {
				$this->sql = str_replace ( $param, "'" . $value . "'", $this->sql );
			}
		}
		return $this->sql;
	}
}
?>
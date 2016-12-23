<?php if (! defined ( 'START_INDEX' )) exit ( 'No direct script access allowed' );
class Security {
	public $_GET;
	public $_POST;
	public $_REQUEST;
	public $GET_OBJECT;
	public $POST_OBJECT;
	public $REQUEST_OBJECT;
	public function __construct() {
		/*
		 * $this->GET = array();
		 * $this->POST = array();
		 */
		$this->_GET = new stdclass;
		$this->_POST = new stdclass;
		$this->_REQUEST = new stdclass;
		$this->_get ();
		$this->_post ();
		$this->_request ();
	}
	//-- манипуляция GET 
	public function _get() {
		$i1 = 0;
		$i = 0;
		$get = '';
		$getClass = new stdclass;
		if (is_array ( $_GET )) {
			foreach ( $_GET as $key => $section ) {
				if (is_array ( $_GET[$key] )) {
					foreach ( $_GET[$key] as $name => $val ) {
						{
							if(gettype($name) == 'integer')
							{
								$getClass->$key[$name] = $this->param($_GET [$key] [$name], $key); // mysql_escape_string($section)
							}
							else
							{
								$i1 ++;
								$getClass->$key->$name = $this->param($_GET [$key] [$name], $key); // mysql_escape_string($val)
								$getClass->$key->_count = $i1;
							}
						}
						
					}
				} else {
					if(gettype($key) == 'integer')
					{
						$get[$key] = $this->param($_GET [$key], $key); // mysql_escape_string($section)
					}
					else
					{
						$i ++;
						$getClass->$key = $this->param($_GET [$key], $key); // mysql_escape_string($section)
						$getClass->_count = $i;
					}
				}
			}
			if(is_array($get))
			{
				$this->_GET = array();
				$this->_GET = $get;				
				$this->_OBJECT = new stdclass;
				$this->GET_OBJECT = $getClass;
			}
			else
			{
				$this->_GET = new stdclass;
				$this->_GET = $getClass;
			}
		}
	}
	//-- манипуляция POST
	public function _post() {
		$i1 = 0;
		$i = 0;
		$post = '';
		$postClass = new stdclass;
		if (is_array ( $_POST )) {
			foreach ( $_POST as $key => $section ) {
				
				if (is_array ( $section )) {
					foreach ( $section as $name => $val ) {
					
						if(gettype($name) == 'integer')
						{
							$postClass->$key[$name] = $this->param($_POST [$key] [$name], $key); // mysql_escape_string($section)
						}
						else
						{
							$postClass->$key->$name = $this->param($_POST [$key] [$name], $key); // mysql_escape_string($val)
						}
						
					}
				} else {
					if(gettype($key) == 'integer')
					{
						$post[$key] = $this->param($_POST [$key], $key); // mysql_escape_string($section)
					}
					else
					{
						$i ++;
						$postClass->_count = $i;
						$postClass->$key = $this->param($_POST [$key], $key); // mysql_escape_string($section)
					}
				}
			}
			
			// $this->POST=$post;
			if(is_array($post))
			{
				$this->_POST = array();
				$this->_POST = $post;
				$this->POST_OBJECT = new stdclass;
				$this->POST_OBJECT = $postClass;
			}
			else
			{
				$this->_POST = new stdclass;
				$this->_POST = $postClass;
			}
		}
	}
	//-- манипуляция JSON DECODE
	public function _json($assoc=null, $array = 'json')
	{
		return json_decode(str_replace('\"', '"', $_REQUEST[$array]), $assoc);
	}
	//-- манипуляция _request
	public function _request() {
		$i1 = 0;
		$i = 0;
		$get = '';
		$getClass = new stdclass;
		if (is_array ( $_REQUEST )) {
			foreach ( $_REQUEST as $key => $section ) {
				if (is_array ( $_REQUEST[$key] )) {
					foreach ( $_REQUEST[$key] as $name => $val ) {
						{
							if(gettype($name) == 'integer')
							{
								//$this->_GET->$key[$name] = $this->param($_GET [$key] [$name], $key); // mysql_escape_string($section)
								$getClass->$key[$name] = $this->param($_REQUEST [$key] [$name], $key); // mysql_escape_string($section)
							}
							else
							{
								$i1 ++;
								$getClass->$key->$name = $this->param($_REQUEST [$key] [$name], $key); // mysql_escape_string($val)
								$getClass->$key->_count = $i1;
							}
						}
	
					}
				} else {
					if(gettype($key) == 'integer')
					{
						$get[$key] = $this->param($_REQUEST [$key], $key); // mysql_escape_string($section)
					}
					else
					{
						$i ++;
						$getClass->$key = $this->param($_REQUEST [$key], $key); // mysql_escape_string($section)
						$getClass->_count = $i;
					}
				}
			}
			if(is_array($get))
			{
				$this->_REQUEST = array();
				$this->_REQUEST = $get;
				$this->REQUEST_OBJECT = new stdclass;
				$this->REQUEST_OBJECT = $getClass;
			}
			else
			{
				$this->_REQUEST = new stdclass;
				$this->_REQUEST = $getClass;
			}
		}
	}
	
	public function input()
	{
		$p = file_get_contents("php://input");
		
		$e = explode('&', $p);
		
		foreach($e as $k=>$v)
		{
			$param = explode('=', $v);
			$r[$param[0]][] = $param[1];
		}
		return $r;
	}
	//-- очистка ненужна tega
	public function param($value, $param = false) {
		
		if (gettype ( $value ) == 'integer') {
			$value = (int)$value;
		}elseif ($value == NULL and $value == '') {
			$value = '';
		} 
		else if($param == 'p_description' 
				or $param == 'description'
				or $param == 'title'
				or $param == 'p_title'
				){
			//$value = htmlentities ( $value);
			$filter = array("<", ">");
			$value = str_replace ($filter, "|", $value);
			$value = htmlentities($value, ENT_QUOTES, "UTF-8");
		}
		else {
			//$value = htmlentities ( $value);
			$value = htmlentities($this->str_trim($value), ENT_QUOTES, "UTF-8");
		}
		return $value;
	}

	/**
	 * Производит непосредственно (де-)шифрование побитовым сравнением двух строк (поддерживает UTF)
	 * 
	 * @param string $InputString
	 *        	Строка для шифрования
	 * @param string $KeyString
	 *        	Строка-ключ
	 * @return string Зашифрованная строка
	 */
	public function XorE($config = false) {
		if ($config) {
			self::$keyString = $config ['keyString'];
			self::$str_0 = $config ['str'] [0];
			self::$str_1 = $config ['str'] [1];
		}
	}
	public static function xorEncrypt($InputString, $KeyString) {
		$KeyStringLength = mb_strlen ( $KeyString );
		$InputStringLength = mb_strlen ( $InputString );
		for($i = 0; $i < $InputStringLength; $i ++) {
			// Если входная строка длиннее строки-ключа
			$rPos = $i % $KeyStringLength;
			// Побитовый XOR ASCII-кодов символов
			$r = ord ( $InputString [$i] ) ^ ord ( $KeyString [$rPos] );
			// Записываем результат - символ, соответствующий полученному ASCII-коду
			$InputString [$i] = chr ( $r );
		}
		return $InputString;
	}
	/**
	 * Вспомогательная функция для шифрования в строку, удобную для использования в ссылках
	 * 
	 * @param string $InputString        	
	 * @return string
	 */
	public static function encrypt($InputString) {
		$str = self::xorEncrypt ( $InputString, self::$keyString );
		$str = self::base64EncodeUrl ( $str );
		return $str;
	}
	/**
	 * Вспомогательная функция для дешифрования из строки, удобной для использования в ссылках (парный к @link self::encrypt())
	 * 
	 * @param string $InputString        	
	 * @return string
	 */
	public static function decrypt($InputString) {
		$str = self::base64DecodeUrl ( $InputString );
		$str = self::xorEncrypt ( $str, self::$keyString );
		return $str;
	}
	/**
	 * Кодирование в base64 с заменой url-несовместимых символов
	 * 
	 * @param string $Str        	
	 * @return string
	 */
	public static function base64EncodeUrl($Str) {
		return strtr ( base64_encode ( $Str ), self::$str_0, self::$str_1 );
	}
	/**
	 * Декодирование из base64 с заменой url-несовместимых символов (парный к @link self::base64EncodeUrl())
	 * 
	 * @param string $Str        	
	 * @return string
	 */
	public static function base64DecodeUrl($Str) {
		return base64_decode ( strtr ( $Str, self::$str_1, self::$str_0 ) );
	}
}

?>

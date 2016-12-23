<?php if (! defined ( 'START_INDEX' )) exit ( 'No direct script access allowed' );
class Lib {
	public $lang;
	public $tpl;
	public $View;
	public $AccessModul;
	public $person_id;
	public $domain_id;
	//private $sys_id;
	function __construct() {

	}

	//-- закрипит активная модуля для ползовател
	public function ModulAccess($person_id, $moduls_key,  $modul_code, $function_code)
	{
		$BD = new MySQL (null, 'DB_Bank_and_Core_Sel');
		$BD->sql= "INSERT INTO core_modul_funct_access(  access_id ,modul_code ,function_code ,person_id ,modul_key_id, update_date, create_date) 
					SELECT NULL             AS access_id, 
					       m.modul_code     AS modul_code, 
					       f.function_code  AS function_code,
					       p.person_id      AS person_id,
					       k.modul_key_id   AS modul_key_id,
					       NOW(),
					       NOW()
					  FROM core_moduls m
					  LEFT JOIN core_modul_function f
					    ON f.modul_code = m.modul_code
					   AND f.function_code IN (:p_function_code)
					  JOIN core_persons p
					    ON p.person_id = :p_person_id
					  JOIN irc_bank.moduls_key k
					    ON k.modul_key_id = :p_modul_key_id
					 WHERE m.modul_code IN (:p_modul_code)
					   AND NOT EXISTS (SELECT 1 FROM core_modul_funct_access aa WHERE aa.modul_code = m.modul_code AND aa.function_code = f.function_code AND aa.person_id = p.person_id)
					 GROUP BY m.modul_code, f.function_code, p.person_id, k.modul_key_id";
		
		$BD->param ( ':p_person_id', $person_id);
		if(is_array($modul_code)) $BD->param ( ':p_modul_code', $modul_code, 'in' ); else $BD->param ( ':p_modul_code', $modul_code);
		if(is_array($function_code)) $BD->param ( ':p_function_code', $function_code, 'in' ); else $BD->param ( ':p_function_code', $function_code);
		$BD->param ( ':p_modul_key_id', $moduls_key );
		$ret = $BD->query ();
	}

	
	//-- Детальная информация по пользователя
	public function ProfileDetail($person_id = null, $lang = LANG)
	{
		if($person_id == null) $person_id = $_SESSION['person_id'];
		$BD = new MySQL (null, 'DB_Default');
		$BD->sql ( 's_core_persons_detail' );
		$BD->param ( ':p_person_id', $person_id);
		if(is_array($lang))
			$BD->param(':p_lang', $lang, 'in');
		else 
			$BD->param(':p_lang', $lang);
		$ret = $BD->query ();
		$var = false; 
		
		while ( $o = $ret->fetch () ) {
			if(is_array($lang))
			{
				$var['detail'][$o->lang]['surname'] 		= $o->surname;
				$var['detail'][$o->lang]['patronymic'] 		= $o->patronymic;
				$var['detail'][$o->lang]['name']			= $o->name;
				$var['detail'][$o->lang]['description']		= $o->description;
			}
			else
			{
				$var['detail']['surname'] 			= $o->surname;
				$var['detail']['patronymic'] 		= $o->patronymic;
				$var['detail']['name']				= $o->name;
				$var['detail']['description']		= $o->description;
				
			}
			
			$var['detail']['nik'] 			= $o->nik;
			$var['detail']['phone'] 		= $o->phone;
			$var['detail']['birthday'] 		= $o->birthday;
			$var['detail']['email'] 		= $o->email;
				
			$var['detail']['picture_id'] 	= $o->picture_id;
			$var['detail']['url_min'] 		= $o->url_min;
			$var['detail']['url_max'] 		= $o->url_max;
			$var['detail']['file_name'] 	= $o->file_name;
			$var['detail']['url_min2']		= $o->url_min2;
			$var['detail']['fio']			= $o->fio;

			$var['detail']['show_user_name'] 	= $o->show_user_name;
			$var['detail']['show_email'] 		= $o->show_email;
			$var['detail']['show_phone']		= $o->show_phone;
			$var['detail']['show_birthday']		= $o->show_birthday;
			
		}
		
		return $var;
	}

}

?>
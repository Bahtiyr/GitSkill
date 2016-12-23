<?php if (! defined ( 'START_INDEX' )) exit ( 'No direct script access allowed' );
class Index {
	
	function __construct()
	{
	
	}
	
	function WebStart()
	{
		//-- обнавления данных
		MySql::CoreUpdate("Table_Name", "param = 'param1'", "person_id=1");
		
		//-- Добавления данных
		$p[]['userName'] 	= 'Bahtiyor';
		$p[]['birthday'] 	= '17.04.1985';
		MySql::CoreInsert("Table_Name", $p);
		
		//-- удаления пользователь
		MySql::CoreDelete("Table_Name", "person_id=1");
		
		//-- выполнение SQL запроса 1 варянт
		$BD = new MySQL (null, 'DB_Default');
		$BD->sql('Sql_SQL');
		$BD->param(':p_person_id', '1');
		$ret = $BD->query ();
		while ( $o = $ret->fetch () ) {
			$var['person_id'] = $o->person_id;
		}
		
		//-- выполнение SQL запроса 2 варянт
		$BD = new MySQL (null, 'DB_Default');
		$BD->sql = "SELECT cp.person_id,
					       cp.nik,
					       cp.email,
					       cp.auth_type,
					       DATE_FORMAT(cp.insort_date, '%d.%m.%Y %H:%i') insort_date,
					       cp.users_status AS isflag,
					       ll1.name AS isflag_name,
					       d.surname,
					       d.patronymic,
					       d.name,
					       d.phone,
					       d.phone_no_format,
					       d.birthday
					  FROM core_persons cp
					  LEFT JOIN core_persons_detail d
					    ON d.person_id = cp.person_id
					  JOIN core_s_isflag f
					    ON f.code = cp.users_status
					  JOIN core_s_language_ul ll1
					    ON ll1.bundle_name = f.bundle_name
					   AND ll1.label = f.label
					   AND ll1.lang = :p_lang
					  JOIN domen_persons dp
					    ON dp.domain_sab_id = :p_domain_sab_id
					   AND dp.person_id = :p_person_id
					  JOIN domen_persons_modul_key kk
					    ON kk.person_id = cp.person_id
					   AND kk.modul_key_id IN (:p_modul_key_id) 
					   AND kk.domain_sab_id = dp.domain_sab_id 
					 WHERE cp.person_id NOT IN ('1')
					 ORDER BY cp.person_id DESC
					 LIMIT 0, 20";
		$BD->param(':p_person_id', '1');
		$ret = $BD->query ();
		while ( $o = $ret->fetch () ) {
			$var['person_id'] = $o->person_id;
		}	
		
		echo json_encode($var);
	}
	
}

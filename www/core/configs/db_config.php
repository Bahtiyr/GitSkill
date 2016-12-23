<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

//-- Подключения Default БД
class DB_Default {
	const DB_HOST = 'localhost';
	const DB_PORT = '3306';
	const DB_NAME = 'Test';
 	const DB_USER = 'root';
 	const DB_PASS = '';
	const CHARSET = 'utf8';
	const CHARSETCOLAT = 'utf8_general_ci';
}

//-- Подключения банковские БД
class DB_Bank {
	const DB_HOST = 'localhost';
	const DB_PORT = '3306';
	const DB_NAME = 'Test';
 	const DB_USER = 'root';
	const DB_PASS = '';
	const CHARSET = 'utf8';
	const CHARSETCOLAT = 'utf8_general_ci';
}

//-- Подключения root БД
class DB_Root {
	const DB_HOST = 'localhost';
	const DB_PORT = '3306';
	const DB_NAME = 'Test';
 	const DB_USER = 'root';
	const DB_PASS = '';
	const CHARSET = 'utf8';
	const CHARSETCOLAT = 'utf8_general_ci';
}

?>
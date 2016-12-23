<?php
if (! defined ( 'START_INDEX' )) exit ( 'No direct script access allowed' );
class Config {
	public $config;
	public function __construct($file_name = false) {
		if (is_file (BASEPATH.'configs/'.$file_name.'_config'.EXT)) {
			require BASEPATH.'configs/'.$file_name.'_config'.EXT;
			$this->$file_name = $config;
			// return $lang;
		}
	}
	public function conf($ParamName)
	{
		return $this->$ParamName;		
	}
	public function isFile($dir, $filename, $s='Art_', $id = null)
	{
	    if($id != null)
	    {
	    	$user_id = $id; 
	    }
	    else 
	    {
	    	$user_id = $_SESSION ['person_id'];
	    }
		$extension = end(explode('.', $filename));
		while (is_file($dir.$filename)) {
			if(isset($_SESSION ['users_id']))
			{
				$filename	= $s.$_SESSION ['users_id'].'_'.rand(1000000, 9999999).'.'.$extension;
			}
			else 
			{
				$filename	= $s.$user_id.'_'.rand(1000000, 9999999).'.'.$extension;
			}
		}
		return $filename;
	}
	public static function ProjectConfig()
	{
		$l = new Lib();
		$Conf = $l->ProjectConfigDb();
		if (is_file (BASEPATH.'configs/ProjectConfig/config_'.$Conf['project_name'].EXT)) {
			require BASEPATH.'configs/ProjectConfig/config_'.$Conf['project_name'].EXT;
			return $config;
		}
		else
		{
			return 'Not Config file';
		}
		
	}
}
?>
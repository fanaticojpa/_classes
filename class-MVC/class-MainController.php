<?php
/**
 * MainController - Todos os controllers deverÃ£o estender essa classe
 *
 * @package TutsupMVC
 * @since 0.1
 */
class MainController extends UserLogin
{

	public $db;
	public $phpass;
	public $title;
	public $login_required = false;
	public $permission_required = 'any';
	public $parametros = array();
	
	public function __construct ($parametros = array()) {
	
		// Instancia do DB
		//$this->db = new DataBase();
		
		// Phpass
		//$this->phpass = new PasswordHash(8, false);
		
		// ParÃ¢metros
		$this->parametros = $parametros;
		
		// Verifica o login
		//$this->check_userlogin();
	}
	

	//Carrega os modelos presentes na pasta /models/.
	public function load_model($model_name = false) {
		
		if (!$model_name) return;
		
		$model_name =  strtolower($model_name);
		
		// Inclui o arquivo
		$model_path = ABSPATH .'/models/'.$model_name.'.php';
		
		// Verifica se o arquivo existe
		if (file_exists($model_path)) {
			require_once $model_path;
			
			$model_name = explode('/', $model_name);
			$model_name = end( $model_name );
			$model_name = preg_replace('/[^a-zA-Z0-9]/is', '', $model_name);
			
			if (class_exists($model_name)) {
				return new $model_name($this->db, $this);
			}
			
			return;
		} 
		
	} 

}
<?php
/**
 * TutsupMVC - Gerencia Models, Controllers e Views
 */
class IndexMVC {

	private $controlador;
	
	/**
	 * $acao
	 *
	 * Receberá o valor da ação (Também vem da URL):
	 * exemplo.com/controlador/acao
	 *
	 * @access private
	 */
	private $acao;
	private $parametros = array();
	private $not_found = '/includes/404.php';
	
	public function __construct () {
		
		$this->get_url_data();
		
		if ( ! $this->controlador ) {
			require_once ABSPATH . '/controllers/home-controller.php';
			$this->controlador = new HomeController();
			$this->controlador->index();
			return;
		}
		
		if (!file_exists(ABSPATH.'/controllers/'.$this->controlador.'.php')) {
			require_once ABSPATH . $this->not_found;
			return;
		}
				
		require_once ABSPATH . '/controllers/' . $this->controlador . '.php';
		
		$this->controlador = preg_replace( '/[^a-zA-Z]/i', '', $this->controlador );
		
		if ( ! class_exists( $this->controlador ) ) {
			require_once ABSPATH . $this->not_found;
			return;
		} 
		
		$this->controlador = new $this->controlador($this->parametros);
		$this->acao = preg_replace( '/[^a-zA-Z]/i', '', $this->acao );
		
		if (method_exists( $this->controlador, $this->acao ) ) {
			$this->controlador->{$this->acao}( $this->parametros );
			return;
		}
		
		if (!$this->acao && method_exists( $this->controlador, 'index' )) {
			$this->controlador->index( $this->parametros );		
			return;
		}
		
		require_once ABSPATH . $this->not_found;
		
		return;
	}
	
	/**
	 * Obtém parâmetros de $_GET['path']
	 *
	 * Obtém os parâmetros de $_GET['path'] e configura as propriedades 
	 * $this->controlador, $this->acao e $this->parametros
	 *
	 * A URL deverá ter o seguinte formato:
	 * http://www.example.com/controlador/acao/parametro1/parametro2/etc...
	 */
	public function get_url_data () {
		
		if (isset($_GET['path'])) {
	
			$path = $_GET['path'];
			
            $path = rtrim($path, '/');
            $path = filter_var($path, FILTER_SANITIZE_URL);
			
			$path = explode('/', $path);
			
			$this->controlador  = chk_array( $path, 0 );
			$this->controlador .= '-controller';
			$this->acao         = chk_array( $path, 1 );
			
			if (chk_array($path, 2)) {
				unset( $path[0] );
				unset( $path[1] );
				
				$this->parametros = array_values($path);
			}
			
			unset($_GET['path']);
			
			$this->parametros = array_merge($this->parametros, $_GET);
			
			// DEBUG
			//
			// echo $this->controlador . '<br>';
			// echo $this->acao        . '<br>';
			// echo '<pre>';
			// print_r( $this->parametros );
			// echo '</pre>';
		}
	} 
} 
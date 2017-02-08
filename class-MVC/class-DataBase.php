<?php
/**
 * DataBase - Classe para gerenciamento da base de dados
 */
class DataBase
{
	/** DB properties */
	public $host      = 'localhost', // Host da base de dados
	       $db_name   = 'tutsup',    // Nome do banco de dados
	       $password  = '',          // Senha do usuÃ¡rio da base de dados
	       $user      = 'root',      // UsuÃ¡rio da base de dados
	       $charset   = 'utf8',      // Charset da base de dados
	       $pdo       = null,        // Nossa conexÃ£o com o BD
	       $error     = null,        // Configura o erro
	       $debug     = false,       // Mostra todos os erros
	       $last_id   = null;        // Ãltimo ID inserido

	/**
	 * Construtor da classe
	 *
	 * @since 0.1
	 * @access public
	 * @param string $host
	 * @param string $db_name
	 * @param string $password
	 * @param string $user
	 * @param string $charset
	 * @param string $debug
	 */
	public function __construct($host = null, $db_name = null, $password = null, $user = null, $charset = null, $debug = null) {

		$this->host     = defined('HOSTNAME'   ) ? HOSTNAME    : $this->host;
		$this->db_name  = defined('DB_NAME'    ) ? DB_NAME     : $this->db_name;
		$this->password = defined('DB_PASSWORD') ? DB_PASSWORD : $this->password;
		$this->user     = defined('DB_USER'    ) ? DB_USER     : $this->user;
		$this->charset  = defined('DB_CHARSET' ) ? DB_CHARSET  : $this->charset;
		$this->debug    = defined('DEBUG'      ) ? DEBUG       : $this->debug;

		//Conecta
		$this->connect();

	} // __construct

	/**
	 * Cria a conexÃ£o PDO
	 *
	 * @since 0.1
	 * @final
	 * @access protected
	 */
	final protected function connect() {

		/* Os detalhes da nossa conxão PDO */
		$pdo_details  = "mysql:host={$this->host};";
		$pdo_details .= "dbname={$this->db_name};";
		$pdo_details .= "charset={$this->charset};";

		// Tenta conectar
		try {
			$this->pdo = new PDO($pdo_details, $this->user, $this->password);
			// Verifica se devemos debugar
			if ( $this->debug === true ) {
				// Configura o PDO ERROR MODE
				$this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
			}

			// Não precisamos mais dessas propriedades
			unset( $this->host     );
			unset( $this->db_name  );
			unset( $this->password );
			unset( $this->user     );
			unset( $this->charset  );

		} catch (PDOException $e) {
			// Verifica se devemos debugar
			if ( $this->debug === true ) {
				// Mostra a mensagem de erro
				echo "Erro: " . $e->getMessage();
			}

			die();
		}
	}

	//query - Consulta PDO
	public function query($stmt, $data_array = null) {

		// Prepara e executa
		$query      = $this->pdo->prepare($stmt);
		$check_exec = $query->execute($data_array);

		// Verifica se a consulta aconteceu
		if ($check_exec) {
			// Retorna a consulta
			return $query;
		} else {
			// Configura o erro
			$error       = $query->errorInfo();
			$this->error = $error[2];
			// Retorna falso
			return false;
		}
	}

	//Insere os valores e tenta retornar o Ultimp id enviado
	public function insert($table) {

		$cols = array();
		$place_holders = '(';
		$values = array();
		// O $j will assegura que colunas serão configuradas apenas uma vez
		$j = 1;

		$data = func_get_args();

		if (!isset($data[1]) || !is_array($data[1])) return;

		for ($i = 1; $i < count($data); $i++) {

			foreach ( $data[$i] as $col => $val ) {

				if ( $i === 1 ) $cols[] = "`$col`";
				if ( $j <> $i ) $place_holders .= '), (';

				$place_holders .= '?, ';
				$values[] = $val;
				$j = $i;
			}
			$place_holders = substr( $place_holders, 0, strlen( $place_holders ) - 2 );
		}

		$cols = implode(', ', $cols);
		$stmt = "INSERT INTO `$table` ( $cols ) VALUES $place_holders) ";
		$insert = $this->query( $stmt, $values );

		if ($insert) {
			if (method_exists($this->pdo, 'lastInsertId') && $this->pdo->lastInsertId()) {
				$this->last_id = $this->pdo->lastInsertId();
			}
			return $insert;
		}

		return;
	}

	//Atualiza uma linha da tabela baseada em um campo
	public function update( $table, $where_field, $where_field_value, $values ) {
		// VocÃª tem que enviar todos os parâmetros
		if ( empty($table) || empty($where_field) || empty($where_field_value)  ) {
			return;
		}

		// Começla a Declaração
		$stmt = " UPDATE `$table` SET ";

		// Configura o array de valores
		$set = array();

		// Configura a declaração do WHERE campo=valor
		$where = " WHERE `$where_field` = ? ";

		// VocÃª precisa enviar um array com valores
		if ( ! is_array( $values ) ) {
			return;
		}

		// Configura as colunas a atualizar
		foreach ( $values as $column => $value ) {
			$set[] = " `$column` = ?";
		}

		// Separa as colunas por vírgula
		$set = implode(', ', $set);

		// Concatena a declaraÃ§Ã£o
		$stmt .= $set . $where;

		// Configura o valor do campo que vamos buscar
		$values[] = $where_field_value;

		// Garante apenas nÃºmeros nas chaves do array
		$values = array_values($values);

		// Atualiza
		$update = $this->query($stmt, $values);

		if ($update) {
			return $update;
		}

		return;
	}

	//Deleta uma linha da tabela
	public function delete($table, $where_field, $where_field_value) {
		// Você precisa enviar todos os parÃ¢metros
		if ( empty($table) || empty($where_field) || empty($where_field_value)  ) {
			return;
		}

		// Inicia a declaraÃ§Ã£o
		$stmt = " DELETE FROM `$table` ";

		// Configura a declaraÃ§Ã£o WHERE campo=valor
		$where = " WHERE `$where_field` = ? ";

		// Concatena tudo
		$stmt .= $where;

		// O valor que vamos buscar para apagar
		$values = array( $where_field_value );

		// Apaga
		$delete = $this->query( $stmt, $values );

		// Verifica se a consulta estÃ¡ OK
		if ( $delete ) {
			// Retorna a consulta
			return $delete;
		}

		return;
	}



	public static function insert_2($table, $fields) {

		$cols = '';
		$place_holders = '';
		$values = array();

		foreach ($fields as $col => $val) {
			$cols .= $col.', ';
			$place_holders .= '?, ';
			$values[] = $val;
		}

		$cols = substr(trim($cols), 0, -1);
		$place_holders = substr(trim($place_holders), 0, -1);

		$stmt = "INSERT INTO `$table` ($cols) VALUES ($place_holders); ";

		//$insert = $this->query($stmt, $values);
		$insert = false;

		if ($insert) {
			if (method_exists($this->pdo, 'lastInsertId') && $this->pdo->lastInsertId()) {
				$this->last_id = $this->pdo->lastInsertId();
			}
			return $insert;
		}
	}

	public static function update_2($table, $fields, $where_field, $where_field_value) {

		if ( empty($table) || empty($table) || empty($where_field) || empty($where_field_value) ) {
			return false;
		}

		foreach ($fields as $col => $val) {
			$set = "$col = '?', ";
		}
		$set = substr(trim($set), 0, -1);

		$fields[] = $where_field_value;
		$fields = array_values($fields);

		$set = " UPDATE `$table` SET $set ";
		$where = " WHERE `$where_field`;";

		$stmt = $set.$where;

		echo $stmt;
		exit;

		// Atualiza
		//$update = $this->query($stmt, $fields);
		$update = false;
		if ($update) {
			return $update;
		}

		return;
	}

	public function delete_2($table, $where_field, $where_field_value) {

		if (empty($table) || empty($where_field) || empty($where_field_value)) {
			return;
		}

		$stmt = " DELETE FROM `$table` ";
		$where = " WHERE `$where_field` = ? ";

		$stmt .= $where;
		$values = array($where_field_value);

		//$delete = $this->query($stmt, $values);
		$delete = false;

		if ($delete) {
			return $delete;
		}

		return;
	}

}

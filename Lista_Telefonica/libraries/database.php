<?php 

 namespace classdatabase;

 use PDO;
 use PDOException;
 use stdClass;

 class Database {
    /// Propriedades 
    private $_host;
    private $_database;
    private $_username;
    private $_password;
    private $_return_type;

    public function __construct($cfg_options, $return_type = 'object') {
            /// Seetando as configurações da conexão 
            $this->_host = $cfg_options['host'];
            $this->_database = $cfg_options['database'];
            $this->_username = $cfg_options['username'];
            $this->_password = $cfg_options['password'];

            if (!empty($return_type) && $return_type == 'object' ) {
                $this->_return_type = PDO::FETCH_OBJ;
            } else {
                $this->_return_type = PDO::FETCH_ASSOC;
            }
    }

    public function execute_query($sql, $parametros = NULL) {
        
        /// Execução de uma Query com resultados


        /// Estabelecendo Conexão
        $connection = new PDO(
            'mysql:host='. $this->_host . ';dbname='. $this->_database . ';charset=utf8',
            $this->_username,
            $this->_password,
            array(PDO::ATTR_PERSISTENT => true)
        );

        $results = NULL;

        try {
           $db = $connection->prepare($sql);
           if(!empty($parametros)){
                $db ->execute($parametros);
           } else {
                $db ->execute();
           }
           $results = $db->fetchAll($this->_return_type);

        } catch (PDOException $err) {
            
            /// Finalizar a conexão
            $connection = NULL;

            return $this->_result('error',$err->getMessage(),$sql,null,0,null);
        }

        /// Fechando Conexão 
        $connection = NULL;

        return $this->_result('Success','Success',$sql,$results,$db->rowCount(),null);
    }

    public function execute_non_query($sql, $parametros = NULL){

        /// Executando uma query sem resultados 

        $connection = new PDO(
            'mysql:host='. $this->_host . ';dbname='. $this->_database . ';charset=utf8',
            $this->_username,
            $this->_password,
            array(PDO::ATTR_PERSISTENT => true)
        );

        /// Iniciando Transação

        $connection ->beginTransaction();

        /// Preparando e Executando a Query
        try {
            $db = $connection->prepare($sql);
            if(!empty($parametros)){
                 $db ->execute($parametros);
            } else {
                 $db ->execute();
            }
            
            // Último ID inserido
            $last_inserted_id = $connection->lastInsertId();

            /// Finalizando Transação
            $connection ->commit();
 
         } catch (PDOException $err) {
             

            $connection -> rollBack();

             /// Finalizar a conexão
             $connection = NULL;
 
             return $this->_result('error',$err->getMessage(),$sql,null,0,null);
         }

         /// Fechando Conexão 
        $connection = NULL;

        return $this->_result('Success','Success',$sql,null,$db->rowCount(),$last_inserted_id);
    }

    private function _result($status, $message, $sql, $results, $affected_rows, $last_id){

        $tmp = new stdClass();
        $tmp->status = $status;
        $tmp->message = $message;
        $tmp->query = $sql;
        $tmp->results = $results;
        $tmp->affected_rows = $affected_rows;
        $tmp->last_id = $last_id;
        return $tmp;
    }



 }
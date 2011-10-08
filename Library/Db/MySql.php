<?php

/**
 * Diese Klasse stellt die Bindung zur Datenbank her.
 *
 * @author     2011 bitprofessional.com Ltd., Schulstr. 11, 56579 Bonefeld, Bitprofessional.com Developer <developer@bitprofessional.com>
 * @maintainer Sascha Klein <klein.sascha@bitprofessional.com>
 * @encoding   UTF-8 äöüßÖÄÜ
 * @linzenz    GNU Gerneral Public License (GPL)
 * @note       bitprofessional.com Ltd. übernimmt keine Garantie auf Funktionalität oder für ggf. entstehende Schäden durch Nutzung dieser Software.
 * @package    library
 * @subpackage db
 * @link       SVN: $HeadURL$
 * @version    SVN: $Id$
 * @phpVersion 5.3
 */
class Db_MySql {

    /**
     * Der Name oder die IP des Datenbankservers.
     * 
     * @var string
     */
    private $_server;

    /**
     * Der Benutzername der Datenbank.
     * 
     * @var string
     */
    private $_username;

    /**
     * Das Passwort der Datenbank.
     * 
     * @var string
     */
    private $_password;

    /**
     * Der Name der Datenbank
     * 
     * @var string
     */
    private $_database;

    /**
     * Die Bindung zur Datenbank.
     * 
     * @var ???
     */
    private $_dbLink;

    /**
     * Ausgewählte Datenbank.
     * 
     * @var ???
     */
    private $_selectedDbInstance;

    /**
     * Der Constructor initialisiert die Datenbank.
     * 
     * @param string $pathToConfigFile Der Pfad zur Konfiguration
     * 
     * @return void
     */
    public function __construct ($pathToConfigFile) {
        if (file_exists($pathToConfigFile)) {
            $xml = simplexml_load_file($pathToConfigFile);
            $this->_server = $xml->host;
            $this->_username = $xml->user;
            $this->_password = $xml->password;
            $this->_database = $xml->dbname;
        } else {
            throw new Exception('XML file couldn\'t load');
        }
    }

    /**
     * Diese Funktion stellt eine Verbindung zur Datenbank her.
     * 
     * @throws Exception Wenn eine Verbindung zur Datenbank nicht möglich ist
     * 
     * @return void
     */
    private function _connect () {
        $this->_dbLink = @mysql_connect($this->_server, $this->_username, $this->_password);
        if (!$this->_dbLink) {
            throw new Exception('Could not connect to Mysql-Server: ' . mysql_error());
        }
        $this->_selectedDbInstance = @mysql_select_db($this->_database);
    }

    /**
     * Diese Funktion schließt die Verbindung zur Datenbank.
     * 
     * @return void
     */
    private function _disconnect () {
        @mysql_close($this->_dbLink);
    }

    /**
     * Diese Funktion schreibt die übergebenen Daten in die Datenbank
     * 
     * @param array  $data  Die Daten in der Form dbfield => value
     * @param string $table Die Tabelle in welche die Daten geschrieben werden sollen
     * 
     * @throws Exception Wenn der Query fehlschlägt
     */
    public function writeToDb (array $data, $table) {
        $query = 'INSERT INTO ' . $table . ' SET ';
        
        foreach ($data as $field => $value) {
            $query .= $field . ' = \'' . mysql_escape_string($value) . '\',';
        }
        
        $query = substr($query, 0, -1);
        
        $this->_connect();
        $queryResult = @mysql_query($query, $this->_dbLink);
        if (!$queryResult) {
            throw new Exception('Mysql query faild: ' . mysql_error());
        }
        $this->_disconnect();
    }
    
    /**
     * Diese Funktion holt die Url die zum übergebenen Hash
     * passt aus der Datenbank. 
     * 
     * @param string $hash Der Hash
     * 
     * @return array In der Form hash => redirectUrl
     */
    public function fetchUrlByHash ($hash) {
        $query = 'SELECT redirectURL FROM url WHERE shortURL = \'' . mysql_escape_string($hash) . '\'';
        $this->_connect();
        $queryResult = @mysql_query($query, $this->_dbLink);
        if (!$queryResult) {
            throw new Exception('Mysql query faild: ' . mysql_error());
        }
        
        $rowResult = @mysql_fetch_assoc($queryResult);
        $urlData = array();
        if (!empty($rowResult)) {
            $urlData[$hash] = $rowResult['redirectURL'];
        }
        $this->_disconnect();
        return $urlData;
    }

}

<?php

/**
 * Diese Klasse stellt Funktionalität zum Kürzen einer URL
 * und zum Auflösen einer verkürzten URL zur Verfügung.
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
class Shorter {
    
    /**
     * Speichert die Datenbank.
     * 
     * @var Db_MySql
     */
    private $_db;
    
    /**
     * Der Constructor initialisiert das Objekt.
     * 
     * @return void
     */
    public function __construct() {
        $this->_db = new Db_MySql(dirname(__FILE__) . '/../config/config.xml');
    }

    /**
     * Diese Funktion verkürzt eine URL und gibt den 
     * erzeugten Hash zurück.
     * 
     * @param string $url Die zu kürzende URL
     * 
     * @return string
     */
    public function shortUrl ($url) {
        $hash = substr(md5($url), 5, 10);
        $hashFromDb = $this->getUrlByHash($hash);
        if (empty($hashFromDb)) {
            $this->_saveToDb($hash, $url);
            return $hash;
        }
        
        return array_shift(array_keys($hashFromDb));
    }

    /**
     * Diese Funktion holt die ursprüngliche ungekürzte Url
     * anhand des Hashs aus der Datenbank und gibt die Url 
     * zurück. 
     * 
     * @param string $hash Der Hash der die URL identifiziert
     * 
     * @return null|string
     */
    public function getUrlByHash ($hash) {
        return $this->_db->fetchUrlByHash($hash);
    }

    /**
     * Diese Funktion schreibt den erzeugten Hash direkt 
     * in die Datenbank.
     * 
     * @param string $hash Der erzeugte Hash.
     * 
     * @return void
     */
    private function _saveToDb ($hash, $url) {
        $this->_db->writeToDb(array(
                'shortUrl' => $hash, 
                'redirectURL' => $url), 'url');
    }

}

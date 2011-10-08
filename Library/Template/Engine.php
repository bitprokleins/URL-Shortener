<?php

/**
 * Diese Klasse verarbeitet die Templates.
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
class Template_Engine {

    /**
     * Das eingelesene Template
     * 
     * @var string
     */
    private $_template;
    
    /**
     * Der Pfad zu den Templates.
     * 
     * @var string
     */
    private $_templatePath;
    
    /**
     * Der Constructor initialisiert die Templateengine.
     * 
     * @param string $templatePath Der Pfad zu den Templates
     * 
     * @return void
     */
    public function __construct($templatePath) {
        $this->_templatePath = $templatePath;
    }
    
    /**
     * Diese Funktion ersetzt Variablen im Template durch die übergebenen Werte.
     * 
     * @param array $vars Die zu ersetzenden Variablen in der Form Variablenname => Wert
     * 
     * @return void
     * 
     * @throws Exception Wenn das Template nicht geladen ist
     */
    public function replaceVars (array $vars) {
        if (empty($this->_template)) {
            throw new Exception('Error while replacing vars in template: Template must first load');
        }
        foreach ($vars as $var => $value) {
            $this->_template = str_replace('{' . $var . '}', $value, $this->_template);
        }
    }
    
	/**
     * Diese Funktion ließt das Template aus dem übergebenen 
     * Parameter und gibt es als String zurück.
     * 
     * @param string $templateName Der Name des Templates (ohne Endung und Pfad)
     * 
     * @return void
     * 
     * @throws Exception Wenn das Template nicht geladen werden kann
     */
    public function loadTemplate ($templateName) {
        $filePathAndName = $this->_templatePath . $templateName . '.tpl';
        $handle = fopen($filePathAndName, "r");
        if (!$handle) {
            throw new Exception('Error in file opening: Can not open Templatefile ' . $filePathAndName);
        }
        $this->_template = fread($handle, filesize($filePathAndName));
        fclose($handle);
    }
    
    /**
     * Diese Funktion gibt das Template zurück.
     * 
     * @return string
     */
    public function getTemplate () {
        return $this->_template;
    }
    
}
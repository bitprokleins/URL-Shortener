<?php

/**
 * Diese Klasse stellt das Formular für den URL-Shortener bereit.
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
class Form_ShortItForm {

    /**
     * Speichert den String des Formulars ab.
     * 
     * @var string
     */
    private $_form;

    /**
     * Speichert die im Formular benötigten Felder und deren Fehlermeldungen.
     * 
     * @var array
     */
    private $_requierdFields;

    /**
     * Der Constructur erzeugt das Formular.
     * 
     * @return void
     */
    public function __construct () {
        $this->_requierdFields = array(
                'shortUrl' => array(
                        'required' => 'notEmpty', 
                        'maxLength' => '600', 
                        'startWith' => 'http://||https://',
                        'contains' => '.',
                        'errors' => array(
                                'required' => 'Please enter a valid URL!', 
                                'maxLength' => 'The given URL ist to long. Please enter an URL with maximum 600 characters!',
                                'startWith' => 'Please enter a valid URL! The given URL have to start with http:// or https://!',
                                'contains' => 'Please enter a valid URL!')));
        $templateEngine = new Template_Engine(dirname(__FILE__) . '/../../templates/');
        $templateEngine->loadTemplate('shortenerForm');
        
        $replaceVars = array(
                'action' => str_replace('index.php', '', $_SERVER['PHP_SELF']), 
                'method' => 'post', 
                'inputText' => 'shortUrl', 
                'submitName' => 'shortIt');
        $templateEngine->replaceVars($replaceVars);
        
        $this->_form = $templateEngine->getTemplate();
    }

    /**
     * Diese Funktion gibt das Formular als String zurück.
     * 
     * @return string
     */
    public function getForm () {
        return $this->_form;
    }

    /**
     * Diese Funktion validiert das Formular.
     * 
     * @param array $requestData Die Daten aus dem Request
     * 
     * @return array
     */
    public function validate ($requestData) {
        $errors = array();
        foreach ($this->_requierdFields as $field => $validation) {
            foreach ($validation as $validator => $condition) {
                switch ($validator) {
                    case 'required':
                        if (!key_exists($field, $requestData) || empty($requestData[$field])) {
                            $errors[] = $validation['errors'][$validator];
                        }
                        break;
                    case 'maxLength':
                        if (key_exists($field, $requestData) && !empty($requestData[$field])) {
                            if (strlen($requestData[$field]) > $condition) {
                                $errors[] = $validation['errors'][$validator];
                            }
                        }
                        break;
                    case 'startWith':
                        if (key_exists($field, $requestData) && !empty($requestData[$field])) {
                            $condition = explode('||', $condition);
                            switch (count($condition)) {
                                case 1:
                                    if (stripos($requestData[$field], $condition[0]) != 0) {
                                        $errors[] = $validation['errors'][$validator];
                                    }
                                    break;
                                case 2:
                                    if (stripos($requestData[$field], $condition[0]) != 0 && stripos($requestData[$field], $condition[1]) != 0) {
                                        $errors[] = $validation['errors'][$validator];
                                    }
                                    break;
                            }
                            
                        }
                        break;
                    case 'contains':
                        if (key_exists($field, $requestData) && !empty($requestData[$field])) {
                            $condition = explode('||', $condition);
                            switch (count($condition)) {
                                case 1:
                                    if (!stripos($requestData[$field], $condition[0])) {
                                        $errors[] = $validation['errors'][$validator];
                                    }
                                    break;
                                case 2:
                                    if (!stripos($requestData[$field], $condition[0]) && !stripos($requestData[$field], $condition[1])) {
                                        $errors[] = $validation['errors'][$validator];
                                    }
                                    break;
                            }
                            
                        }
                        break;
                }
            }
        }
        return $errors;
    }

}
<?php

/**
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

date_default_timezone_set('Europe/Berlin');

function __autoload($className) {
    require_once dirname(__FILE__) . '/../' . 'Library/' . str_replace('_', '/', $className) . '.php';
}
?>
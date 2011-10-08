SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Datenbank: `URL-Shortener`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `aufruf`
--

CREATE TABLE IF NOT EXISTS `aufruf` (
  `id` int(15) NOT NULL auto_increment,
  `requestData` varchar(45) NOT NULL,
  `urlShortURL` varchar(10) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `fk_aufruf_url` (`urlShortURL`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `aufruf`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `url`
--

CREATE TABLE IF NOT EXISTS `url` (
  `shortURL` varchar(10) NOT NULL,
  `redirectURL` varchar(800) NOT NULL,
  PRIMARY KEY  (`shortURL`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `aufruf`
--
ALTER TABLE `aufruf`
  ADD CONSTRAINT `fk_aufruf_url` FOREIGN KEY (`urlShortURL`) REFERENCES `url` (`shortURL`) ON DELETE NO ACTION ON UPDATE NO ACTION;
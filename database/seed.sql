--
-- Base de données : complément à importer dans `bdfootnom1nom2`

-- --------------------------------------------------------
--
-- Structure de la table `commentaire`
--

CREATE TABLE IF NOT EXISTS `commentaire` (
  `idcom` int(11) NOT NULL AUTO_INCREMENT,
  `datecom` date DEFAULT NULL,
  `libelle` text NOT NULL,
  `idjournee` int(2) NOT NULL DEFAULT '0',
  `numrenc` int(2) NOT NULL DEFAULT '0',
  `idutil` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idcom`),
  KEY `idjournee` (`idjournee`),
  KEY `numrenc` (`numrenc`),
  KEY `idutil` (`idutil`),
  KEY `commentaire_ibfk_1` (`idjournee`,`numrenc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE IF NOT EXISTS `utilisateur` (
  `idutil` int(11) NOT NULL AUTO_INCREMENT,
  `pseudoutil` varchar(30) NOT NULL DEFAULT '',
  `mdputil` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`idutil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `commentaire_ibfk_1` FOREIGN KEY (`idjournee`, `numrenc`) REFERENCES `rencontre` (`idjournee`, `numrenc`),
  ADD CONSTRAINT `commentaire_ibfk_2` FOREIGN KEY (`idutil`) REFERENCES `utilisateur` (`idutil`);

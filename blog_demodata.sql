-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 15 juil. 2020 à 11:32
-- Version du serveur :  10.4.11-MariaDB
-- Version de PHP : 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `blog`
--

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) UNSIGNED NOT NULL,
  `content` longtext NOT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `last_update` datetime NOT NULL DEFAULT current_timestamp(),
  `post_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`id`, `content`, `approved`, `created`, `last_update`, `post_id`, `user_id`) VALUES
(2, 'Merci pour ce super article !', 1, '2020-06-08 17:53:32', '2020-06-08 17:53:32', 5, 1),
(3, 'Une nouvelle version est dispo ?', 1, '2020-06-08 17:55:46', '2020-06-08 17:55:46', 5, 2),
(4, 'Je ne crois pas', 1, '2020-06-08 18:01:47', '2020-06-08 18:01:47', 5, 1),
(5, 'Il y a des fautes partout dans cet article !!\r\n(ceci est un commentaire non approuvé)', 0, '2020-06-08 18:03:06', '2020-06-08 18:03:06', 5, 3),
(6, 'Merci, toujours intéressent de te lire !', 1, '2020-06-08 18:04:35', '2020-06-08 18:04:35', 4, 4),
(7, 'Au top !', 1, '2020-06-08 18:05:10', '2020-06-08 18:05:10', 4, 6),
(8, 'n\'importe quoi encore cet article pompé !\r\n(ceci est un commentaire pas encore approuvé)', 0, '2020-06-08 18:06:19', '2020-06-08 18:06:19', 4, 6),
(9, 'Merci, c\'est très intéressent comme sujet', 1, '2020-06-08 18:07:21', '2020-06-08 18:07:21', 3, 1),
(10, 'ça existe pour Deezer ou bien ?', 1, '2020-06-08 18:08:02', '2020-06-08 18:08:02', 3, 3),
(11, 'Ou bieeeeeeeen ??!!', 1, '2020-06-08 18:08:37', '2020-06-08 18:08:37', 3, 6),
(12, 'Merci !', 1, '2020-06-08 18:09:02', '2020-06-08 18:09:02', 2, 2),
(13, 'J\'aime pas les Regex non plus,\r\nDu coup, merci !', 1, '2020-06-08 18:09:40', '2020-06-08 18:09:40', 2, 1),
(14, 'Merci pour cet article ;-)', 1, '2020-06-08 18:10:16', '2020-06-08 18:10:16', 1, 3),
(15, 'ça va revenir à la mode, faites gaffe !', 1, '2020-06-08 18:11:15', '2020-06-08 18:11:15', 1, 5),
(16, 'Et ça existe pour le 32 bit ?', 1, '2020-06-13 16:12:15', '2020-06-13 16:12:15', 5, 3),
(17, 'Merci pour les conseils Anthony !', 1, '2020-06-13 16:17:20', '2020-06-13 16:17:20', 5, 6),
(18, 'L\'assembleur, c\'est la vie !', 1, '2020-06-13 16:18:34', '2020-06-13 16:18:34', 5, 3),
(19, 'Avengers !  ASSEMBLE !', 1, '2020-06-13 16:19:08', '2020-06-13 16:19:08', 5, 7);

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

CREATE TABLE `post` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `chapo` varchar(200) NOT NULL,
  `content` longtext NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `last_update` datetime NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Déchargement des données de la table `post`
--

INSERT INTO `post` (`id`, `title`, `chapo`, `content`, `created`, `last_update`, `user_id`) VALUES
(1, 'Une bibliothèque CSS pour adopter le style de Windows 98', 'Si vous avez envie de coder un site au look un poil rétro', 'Si vous avez envie de coder un site au look un poil rétro, pourquoi ne pas opter pour la bibliothèque CSS 98.css ?\r\n\r\n98 comme Windows 98 bien sûr ! C’est-à-dire tous les styles de contrôles qu’on pouvait trouver dans Windows 98 : les boutons, les checkbox, les textbox, les listes déroulantes, les fenêtres…etc.\r\n\r\nÇa ne sert pas à grand-chose, mais c’est rigolo. Peut-être que cela vous donnera envie de décliner une version de votre site web avec cette interface ?\r\n\r\nEn tout cas, si vous voulez jouer avec, le projet est ici sur Github et il y a une petite sandbox ouverte ici pour jouer avec.', '2020-06-08 17:27:40', '2020-06-08 17:27:40', 8),
(2, 'iHateRegex – Le site qui décortique les expressions régulières pour ceux qui les détestent', 'Si vous avez pour habitude de manipuler des expressions régulières (Regexp), voici un site qui devrait vous aider à les tester', 'Si vous avez pour habitude de manipuler des expressions régulières (Regexp), voici un site qui devrait vous aider à les tester, mais surtout à les comprendre un peu mieux.\r\n\r\nCe site c’est iHateRegex développé par Geon George et ça s’adresse aux gens comme moi qui détestent les regexp trop compliquées ;-).\r\n\r\nPourquoi ? Et bien en plus de nombreux exemples assez classiques, le site permet de visualiser à l’aide d’un graphique le fonctionnement de votre expression régulière.\r\n\r\nAinsi, plus besoin de vous prendre la tête pour capter son fonctionnement et éventuellement les optimiser ou les debugger.\r\n\r\nVoici par exemple une Regex qui permet d’isoler les dates ayant le format jj/mm/aaaa.\r\n\r\nC’est comme si on me parlait en Wraith (ref à Stargate pour ceux qui se poseraient la question). Heureusement, avec iHateRegex, celle-ci peut devenir claire comme de l’eau de roche avec le petit schéma suivant. J’ai choisi un exemple un peu complexe, j’avoue, mais c’est pour vous montrer que l’outil permet d’aller loin.\r\n\r\n\r\nVoici un exemple un peu plus simple qui matche les adresses email :\r\n\r\n\r\nÀ bookmarker pour les soirées d’hiver.', '2020-06-08 17:30:30', '2020-06-08 17:30:30', 8),
(3, 'Comment contrôler Spotify depuis Visual Studio Code ?', 'Un plugin pour Visual Studio Code permet de le faire', 'La semaine dernière, j’ai mis en ligne un article sur différents clients Spotify et suite à cela, le très sympathique Ludorg m’a parlé de VSCode Spotify. Merci !!!\r\n\r\nCe plugin pour Visual Studio Code permet aux utilisateurs de l’éditeur de code de Microsoft, de piloter leur Spotify directement depuis leur environnement de dev sans se poser plus de questions.\r\n\r\nCette extension permet de voir le titre de la chanson en cours dans la barre de statut de VSCode, de contrôler Spotify via cette même barre, via des raccourcis clavier ou des commandes à taper.\r\n\r\nIl vous faudra bien sûr un compte premium et vous devrez être connecté au net pour que cela fonctionne. Mais c’est dispo pour Windows et macOS.\r\n\r\nÀ découvrir ici.', '2020-06-08 17:34:02', '2020-06-08 17:34:02', 7),
(4, 'Comment surveiller la disponibilité des services que vous utilisez ?', 'Ce que je vous propose aujourd’hui est un outil pour les développeurs qui souhaitent surveiller la disponibilité de services tiers', 'Combien de fois je me suis lancé sans réfléchir dans du debugging alors que le truc qui ne fonctionnait pas était le service tiers que j’utilisais.\r\n\r\nDepuis j’ai un peu plus d’arrogance expérience et quand quelque chose casse, je regarde d’abord chez les autres avant de regarder chez moi ;-).\r\n\r\nCe que je vous propose aujourd’hui est un outil pour les développeurs sous Mac qui souhaitent surveiller la disponibilité de services tiers qu’ils utilisent dans leur vie quotidienne.\r\n\r\nStts est gratuit et se loge dans la barre de notification et vous notifiera dès qu’un des services suivants passera à l’orange.\r\n\r\n\r\nLa liste des services est assez conséquente, et c’est à vous d’activer ceux sur lesquels vous voulez avoir un oeil.\r\n\r\n\r\nAmazon Web Services (AWS)\r\nAtlassian\r\nAtlassian Statuspage\r\nAptible\r\nAuth0\r\nAuthorize.Net\r\nBeanstalk\r\nBitBucket\r\nBraintree\r\nCircleCI\r\nCloud66\r\nCloudflare\r\nCloudinary\r\nCocoaPods\r\nCodacy\r\nCode Climate\r\nCodecov\r\nContentful\r\nCoveralls\r\nDigitalOcean\r\nDiscord\r\nDocker\r\nDropbox\r\nDwolla\r\nEngine Yard\r\nEvernote\r\nFabric\r\nFigma\r\nFilestack\r\nFirebase\r\nGandi.net\r\nGitHub\r\nGoogle Cloud Platform\r\nHeroku\r\nHipChat\r\nImgix\r\nIntercom\r\nKeenIO\r\nLob\r\nLoggly\r\nMailChimp\r\nMapbox\r\nMedia Temple\r\nMixpanel\r\nNetlify\r\nNew Relic\r\nNPM\r\nPacket\r\nPagerDuty\r\nPapertrail\r\nPingdom\r\nPivotal Tracker\r\nPubNub\r\nPusher\r\nQuandl\r\nQuay\r\nReddit\r\nRollbar\r\nRubyGems\r\nSauce Labs\r\nSegment\r\nSendGrid\r\nSentry\r\nSlack\r\nSmartyStreets\r\nSnyk\r\nSquarespace\r\nStream\r\nTravisCI\r\nTrello\r\nTwilio\r\nTypeform\r\nUnsplash\r\nVimeo\r\nWaffle.io\r\nWeTransfer\r\nZapier\r\nZwift\r\nComme vous pouvez le voir, il y a le choix. Et comme Stts est un projet libre, vous pouvez toujours y contribuer si vous souhaitez y rajouter des services qu’il ne surveille pas encore.\r\n\r\nSource', '2020-06-08 17:35:57', '2020-07-03 23:54:03', 8),
(5, 'Un IDE dans votre navigateur pour développer du code 8bit', 'Si vous n’avez pas connu la belle époque, celle où les gens créaient leurs logiciels 8bit en assembleur à destination de machines comme l’Atari 2600, j’ai ce qu’il vous faut', 'Êtes-vous développeur ?\r\n\r\nSi vous n’avez pas connu la belle époque, celle où les gens créaient leurs logiciels 8bit en assembleur à destination de machines comme l’Atari 2600, le VIC Dual, le Midway 8080, le Galaxian, l’Atari Vector, le Williams, ou encore l’Apple ][+, ou si vous avez envie de vous y remettre, j’ai ce qu’il vous faut.\r\n\r\nIl s’agit de 8bit Workshop, un IDE en ligne qui va vous permettre de coder directement depuis votre navigateur en assembleur 6502 (ou en C) et d’en observer directement le résultat dans l’émulateur JS qui se trouve à droite de l’écran.\r\n\r\n\r\nPour configurer l’IDE en fonction de la machine de destination, ça se passe dans le menu en haut à droit.\r\n\r\n\r\nC’est magique ! Cela vous permettra de vous essayer à de « nouvelles » anciennes choses, mais aussi pourquoi pas développer de nouveaux outils pour ces anciennes machines ? À vous de voir. D’ailleurs, pour vous inspirer, de nombreux exemples sont accessibles.\r\n\r\nAmusez-vous bien ! =&amp;amp;amp;amp;amp;gt; 8bit Workshop', '2020-06-08 17:37:42', '2020-07-03 23:40:24', 1),
(6, 'FreeFileSync, outil open source pour aider à synchroniser vos fichiers', 'Si votre boite à outil web a encore un peu de place, voici un logiciel à y ajouter : FreeFileSync', 'Si votre boite à outil web a encore un peu de place, voici un logiciel à y ajouter : FreeFileSync. Comme son nom l’indique assez bien il vous permet de synchroniser et comparer des dossiers entre eux, gratuitement. Il est disponible pour Windows (7 et plus), Linux ou macOS (10.8 et plus).\r\n\r\nFFS est assez léger à l’utilisation puisqu’il ne va pas copier chaque fois l’entièreté d’un dossier, mais plutôt comparer s’il y a eu des différences depuis son dernier passage et mettre à jour les fichiers si c’est le cas. Le soft vous propose également de créer de programmer des sauvegardes régulières.\r\n\r\nParmi ses autres options intéressantes : l’exclusion de certains types de fichiers, le support FTP/FTPS pour les sauvegardes sur un serveur, la synchronisation avec Google Drive, le batch de travaux, synchroniser les mobiles via le protocole MTP, accéder aux fichiers en ligne avec SFTP … et surtout aucune limite au nombre de fichiers à synchroniser. Et bien plus.\r\n\r\nUne fois installé et ouvert vous allez simplement faire glisser (ou sélectionner via le bouton « Parcourir ») le dossier de départ dans la première zone principale (1) puis faire de même avec le dossier dans lequel placer la copie (2).\r\n\r\nInterface FreeFileSync\r\nVous devrez ensuite « Comparer » les 2 dossiers (3). Sur la gauche vous aurez donc la liste des fichiers composant votre dossier de base et sur la droite la liste de tous les fichiers avec des différences (si vous effectuez la manip pour la 1re fois, la liste de droite sera vide puisqu’il n’y a rien à comparer).\r\n\r\nAttention, par défaut, la synchro se fait dans les 2 sens ! Si un fichier du dossier de départ a diminué de poids, il pourrait être remplacé par celui du dossier de destination par mégarde. Pour éviter cet éventuel souci, il suffit de cliquer sur la roulette (4) et choisir la variante qui vous convient (miroir, mise à jour, 2 sens …). C’est sur cette page que vous pouvez en profiter pour lui dire quoi faire des fichiers remplacés, les envoyer à la corbeille ou conserver les différentes versions.\r\n\r\nReste ensuite à démarrer la synchro (5) et le tour est joué !\r\n\r\nJe vous pose leur tuto vidéo pour le cas ou vous voulez créer une tâche automatisée\r\n\r\n\r\nTéléchargez FreeFileSync sur cette page.\r\n\r\nEt si vous effectuez un don sur leur site vous aurez droit à une version avec quelques bonus : copie de fichiers en parallèle, mise à jour automatique, recevoir des notifications par email …', '2020-06-11 11:58:39', '2020-07-09 14:37:23', 8);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) UNSIGNED NOT NULL,
  `login` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `type` enum('admin','member','superadmin') NOT NULL DEFAULT 'member',
  `activated` tinyint(1) NOT NULL DEFAULT 0,
  `token` varchar(128) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `last_update` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `login`, `password`, `email`, `type`, `activated`, `token`, `created`, `last_update`) VALUES
(1, 'SandyV', '$2y$10$Wfyp9dybPtf1uSc.af3k8.UcWRUWGqIZv.en11ETz7Lg6FiajRmnS', 'sandy.vilain@yahoo.fr', 'member', 1, NULL, '2020-06-08 17:08:32', '2020-06-08 17:12:11'),
(2, 'DavidC', '$2y$10$Qhe5W.yB4x/BVaAChVYMreKiuYkiTH0xAmAaGBsrtlvmoYK7TVagC', 'david.chollet@yahoo.fr', 'member', 1, NULL, '2020-06-08 17:11:44', '2020-07-09 18:54:46'),
(3, 'CoralieM', '$2y$10$z/524z.KunquRmsXe0LkQ.1Km59eZKGSdfwTRphgK0145dzaeI5zW', 'coralie.masset@yahoo.fr', 'member', 1, NULL, '2020-06-08 17:13:50', '2020-07-09 18:54:46'),
(4, 'Emelinedu37', '$2y$10$msnwnOUHcMAujVXjPyzI.O.AyzpAQje0jKrqMkmMNqWGj/vibfWKy', 'emeline.camara@gmail.com', 'member', 1, NULL, '2020-06-08 17:16:29', '2020-07-09 18:54:46'),
(5, 'AntoineD', '$2y$10$aMXS62VAwU.NnOJUUSEUReeyENVFMLerDxTPVzHhRjBDx35uDxFom', 'adelporte@gmail.com', 'member', 1, NULL, '2020-06-08 17:15:21', '2020-07-09 18:54:46'),
(6, 'fannyb', '$2y$10$Zs4oe0y6gwW.dyit5JyHke3KAyxgQtV011Wbp13xrASQQSIJN/T4G', 'fanny.boudin@yahoo.fr', 'admin', 1, NULL, '2020-06-08 17:17:47', '2020-07-09 18:54:46'),
(7, 'aurelm', '$2y$10$7okPjBfML.Y97/e5kGnHPep8ZW7h1DG62cwxme/D7KO93H0DlX7nW', 'aurel.mouton@laposte.net', 'admin', 1, NULL, '2020-06-08 17:18:38', '2020-07-09 18:54:46'),
(8, 'anthonyf', '$2y$10$AnlET3PjHzX0erZO0LInEOmAJLdiUDMlUQ8pJBskWF4pD2cOVmRZS', 'anthony.f@test.com', 'superadmin', 1, NULL, '2020-06-08 17:20:13', '2020-07-09 18:54:46');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_author` (`user_id`) USING BTREE,
  ADD KEY `fk_post` (`post_id`) USING BTREE;

--
-- Index pour la table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_author` (`user_id`) USING BTREE;

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `fk_comment_post1` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_comment_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Contraintes pour la table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `fk_post_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

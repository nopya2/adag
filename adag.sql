-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  jeu. 02 juil. 2020 à 11:41
-- Version du serveur :  10.1.35-MariaDB
-- Version de PHP :  7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE `article` (
  `id` int(11) NOT NULL,
  `rubrique_id` int(11) NOT NULL,
  `auteur_id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `image_id` int(11) DEFAULT NULL,
  `contenu` longtext NOT NULL,
  `sous_titre` varchar(255) DEFAULT NULL,
  `pays` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `priority` int(11) DEFAULT NULL,
  `legend` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Structure de la table `blog`
--

CREATE TABLE `blog` (
  `id` int(11) NOT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contenu` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `image_id` int(11) DEFAULT NULL,
  `visible` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Structure de la table `blog_category_blog`
--

CREATE TABLE `blog_category_blog` (
  `blog_id` int(11) NOT NULL,
  `category_blog_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Structure de la table `category_blog`
--

CREATE TABLE `category_blog` (
  `id` int(11) NOT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `category_blog`
--

INSERT INTO `category_blog` (`id`, `created_by_id`, `titre`, `created_at`, `updated_at`) VALUES
(1, 14, 'Sport', '2018-11-09 00:00:00', '2018-11-09 00:00:00'),
(2, 15, 'Finance', '2018-11-09 00:00:00', '2018-11-09 00:00:00'),
(3, 15, 'Informatique', '2018-11-09 00:00:00', '2018-11-09 00:00:00'),
(4, 15, 'Ecologie', '2018-11-09 00:00:00', '2018-11-09 00:00:00'),
(5, 14, 'Culture', '2018-11-09 00:00:00', '2018-11-09 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

CREATE TABLE `commentaire` (
  `id` int(11) NOT NULL,
  `blog_id` int(11) NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `sended_at` datetime NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Structure de la table `commentaire_article`
--

CREATE TABLE `commentaire_article` (
  `id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `contenu` longtext NOT NULL,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `siteweb` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `configuration`
--

CREATE TABLE `configuration` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_site` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_size` int(11) DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  `type_imprimante` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `orientation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `bp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `siteweb` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `googleplus` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pinterest` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `configuration`
--

INSERT INTO `configuration` (`id`, `email`, `nom_site`, `image_name`, `image_size`, `updated_at`, `type_imprimante`, `orientation`, `adresse`, `bp`, `telephone`, `siteweb`, `facebook`, `twitter`, `googleplus`, `instagram`, `pinterest`) VALUES
(1, 'infos@neosystechnologie.ga', 'Neosys Technologie', '5bcc517040a1d299199731.png', 34523, '2018-10-21 12:14:08', '0,0,209.76,297.64', 'portrait', 'Ancienne SOBRAGA', NULL, '+24107454754', 'http://neosystechnologie.ga', 'facebook.com', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `document`
--

CREATE TABLE `document` (
  `id` int(11) NOT NULL,
  `document_id` int(11) DEFAULT NULL,
  `nom` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

CREATE TABLE `etudiant` (
  `id` int(11) NOT NULL,
  `num_bourse` int(11) NOT NULL,
  `nom_prenom` varchar(255) NOT NULL,
  `pays` varchar(255) NOT NULL,
  `cat_bourse` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `evenement`
--

CREATE TABLE `evenement` (
  `id` int(11) NOT NULL,
  `created_by_id` int(11) NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `place` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `evenement_image`
--

CREATE TABLE `evenement_image` (
  `evenement_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `fichier`
--

CREATE TABLE `fichier` (
  `id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_size` int(11) DEFAULT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `flash`
--

CREATE TABLE `flash` (
  `id` int(11) NOT NULL,
  `auteur_id` int(11) NOT NULL,
  `contenu` longtext NOT NULL,
  `created_at` datetime NOT NULL,
  `article_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

CREATE TABLE `image` (
  `id` int(11) NOT NULL,
  `image_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_size` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `import`
--

CREATE TABLE `import` (
  `id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_size` int(11) DEFAULT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `migration_versions`
--

CREATE TABLE `migration_versions` (
  `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `partenaire`
--

CREATE TABLE `partenaire` (
  `id` int(11) NOT NULL,
  `adresse` longtext NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `bp` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `siteweb` varchar(255) DEFAULT NULL,
  `image_name` varchar(255) DEFAULT NULL,
  `image_size` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `nom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `projet`
--

CREATE TABLE `projet` (
  `id` int(11) NOT NULL,
  `created_by_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `image_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_size` int(11) DEFAULT NULL,
  `realized_at` datetime NOT NULL,
  `visible` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `projet_partenaire`
--

CREATE TABLE `projet_partenaire` (
  `projet_id` int(11) NOT NULL,
  `partenaire_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `rubrique`
--

CREATE TABLE `rubrique` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `titre` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `slug` varchar(255) NOT NULL,
  `priority` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `rubrique`
--

INSERT INTO `rubrique` (`id`, `user_id`, `titre`, `description`, `created_at`, `updated_at`, `slug`, `priority`) VALUES
(2, 13, 'Culture', '<p>Les sujets sur la culture nationale et internationale</p>', '2019-11-14 18:25:28', '2019-11-14 18:25:28', 'culture', 4),
(3, 13, 'Investigation', '<p>Rubrique des investigations</p>', '2019-11-15 23:26:32', '2019-11-15 23:26:32', 'investigation', 5),
(4, 13, 'Politique', '<p>Rubrique politique</p>', '2019-11-15 23:27:21', '2019-11-15 23:27:21', 'politique', 0),
(5, 13, 'Société', '<p>Rubrique soci&eacute;t&eacute;</p>', '2019-11-15 23:28:26', '2019-11-15 23:28:26', 'societe', 2),
(6, 13, 'Sport', '<p>Rubrique sport</p>', '2019-11-15 23:28:56', '2019-11-15 23:28:56', 'sport', 3),
(8, 13, 'Economie', '<p>La rubrique economie</p>', '2019-12-09 09:57:46', '2019-12-09 09:57:46', 'economie', 1);

-- --------------------------------------------------------

--
-- Structure de la table `service`
--

CREATE TABLE `service` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `icone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_size` int(11) DEFAULT NULL,
  `visible` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `slider`
--

CREATE TABLE `slider` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sous_titre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_size` int(11) DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visible` tinyint(1) NOT NULL,
  `priorite` int(11) NOT NULL,
  `sous_titre1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) CHARACTER SET utf8 NOT NULL,
  `prenom` varchar(255) CHARACTER SET utf8 NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `username` varchar(255) CHARACTER SET utf8 NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `roles` longtext CHARACTER SET utf8 NOT NULL COMMENT '(DC2Type:array)',
  `image_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_size` int(11) DEFAULT NULL,
  `fonction` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `facebook` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `googleplus` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `configuration_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `nom`, `prenom`, `email`, `username`, `created_on`, `updated_on`, `password`, `is_active`, `roles`, `image_name`, `image_size`, `fonction`, `facebook`, `googleplus`, `twitter`, `linkedin`, `configuration_id`) VALUES
(13, 'NDONG OTOGUE', 'Yvon Paul Brice', 'nopya2@gmail.com', 'admin', '2018-09-30 13:57:13', '2019-01-22 21:08:41', '$2y$13$40esi924QKld3lMf3Gl7n.qkXkDqPhTNOyF0EEwKNH/pEFYx3wSH.', 1, 'a:2:{i:0;s:9:\"ROLE_USER\";i:1;s:10:\"ROLE_ADMIN\";}', '5c47784930117316350318.jpg', 14166, 'Responsable Technique', NULL, NULL, NULL, NULL, 1),
(14, 'KOMBILA EKANG', 'Donald', 'donald.kombila@neosystechnologie.ga', 'donald.kombila', '2018-09-30 14:59:22', '2018-09-30 14:59:29', '$2y$13$FHj/kph8/Wa74XaAzUfQVuY6YqDOiDFCj4ZaAZ61ai/t84v8WGQ46', 1, 'a:1:{i:0;s:10:\"ROLE_ADMIN\";}', '5bb0c8b1a69db035235807.png', 34523, 'Manager Général', NULL, NULL, NULL, NULL, 1),
(15, 'MISSEVOU', 'Aude Cheryle', 'missevouc@gmail.com', 'aude.missevou', '2018-10-17 22:58:45', '2018-10-18 10:34:13', '$2y$13$Km6wThk1KR3JPKMyG/DoROASvpdZ04.PhWLkn5cVJb06e9FcedEMe', 0, 'a:1:{i:0;s:9:\"ROLE_USER\";}', '5bc84585270bc368875281.png', 24471, 'Responsable Commercial', NULL, NULL, NULL, NULL, 1),
(16, 'EDZO', 'Lambert', 'lambert.edzo@test.ga', 'lambert.edzo', '2019-12-09 11:36:12', '2019-12-09 11:36:12', '$2y$13$e.gqjdUrwmSDlBkJKNynUehF5wVEiVIxOv/o1SWYUsVHHgCmAZUj.', 1, 'a:1:{i:0;s:9:\"ROLE_USER\";}', '', 0, 'Editeur', NULL, NULL, NULL, NULL, 1),
(17, 'BITOLI', 'Valérie', 'orcamouele@gmail.com', 'valerie.bitoli', '2019-12-11 10:04:42', '2019-12-11 10:04:42', '$2y$13$ENddAdHygepXbuOY8Q6kl.okFgvyQMe7wJQ3.JH6Kb8.nFc8bM/dC', 1, 'a:1:{i:0;s:10:\"ROLE_ADMIN\";}', '', 0, 'Administrateur Général', NULL, NULL, NULL, NULL, 1),
(18, 'MOUSSOUNDA', 'Doriane', 'dorimouss60@gmail.com', 'doriane.moussounda', '2019-12-11 10:09:32', '2019-12-11 10:09:32', '$2y$13$wEisZBTt3S5fKqdVj0Mih.39Oc.SYloQiy/MRa01RXnmwHaTTPs4S', 1, 'a:1:{i:0;s:9:\"ROLE_USER\";}', '', 0, 'Directrice de publication', NULL, NULL, NULL, NULL, 1),
(19, 'OKOUMBA', 'Michel', 'sergedupalmier@gmail.com', 'michel.okoumba', '2019-12-11 10:11:35', '2019-12-11 10:11:35', '$2y$13$b/phnHhOKIO59c9e988i1unRda1EkwujXWh/kEIF2edLfmXb8R0x6', 1, 'a:1:{i:0;s:10:\"ROLE_ADMIN\";}', '', 0, 'Administrateur Général Adjoint', NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `video`
--

CREATE TABLE `video` (
  `id` int(11) NOT NULL,
  `auteur_id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `updated_at` datetime NOT NULL,
  `rubrique_id` int(11) NOT NULL,
  `url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Structure de la table `vue`
--

CREATE TABLE `vue` (
  `id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_23A0E663DA5256D` (`image_id`),
  ADD KEY `IDX_23A0E663BD38833` (`rubrique_id`),
  ADD KEY `IDX_23A0E6660BB6FE6` (`auteur_id`);

--
-- Index pour la table `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_C01551433DA5256D` (`image_id`),
  ADD KEY `IDX_C0155143B03A8386` (`created_by_id`);

--
-- Index pour la table `blog_category_blog`
--
ALTER TABLE `blog_category_blog`
  ADD PRIMARY KEY (`blog_id`,`category_blog_id`),
  ADD KEY `IDX_3808E168DAE07E97` (`blog_id`),
  ADD KEY `IDX_3808E1681D383EE9` (`category_blog_id`);

--
-- Index pour la table `category_blog`
--
ALTER TABLE `category_blog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_4B8E2B04B03A8386` (`created_by_id`);

--
-- Index pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_67F068BCDAE07E97` (`blog_id`);

--
-- Index pour la table `commentaire_article`
--
ALTER TABLE `commentaire_article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_71F29C357294869C` (`article_id`);

--
-- Index pour la table `configuration`
--
ALTER TABLE `configuration`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_D8698A76C33F7837` (`document_id`);

--
-- Index pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `evenement`
--
ALTER TABLE `evenement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B26681EB03A8386` (`created_by_id`);

--
-- Index pour la table `evenement_image`
--
ALTER TABLE `evenement_image`
  ADD PRIMARY KEY (`evenement_id`,`image_id`),
  ADD KEY `IDX_5697A8A7FD02F13` (`evenement_id`),
  ADD KEY `IDX_5697A8A73DA5256D` (`image_id`);

--
-- Index pour la table `fichier`
--
ALTER TABLE `fichier`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `flash`
--
ALTER TABLE `flash`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_AFCE5F037294869C` (`article_id`),
  ADD KEY `IDX_AFCE5F0360BB6FE6` (`auteur_id`);

--
-- Index pour la table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `import`
--
ALTER TABLE `import`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `migration_versions`
--
ALTER TABLE `migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `partenaire`
--
ALTER TABLE `partenaire`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_32FFA373E7927C74` (`email`),
  ADD UNIQUE KEY `UNIQ_32FFA3736C6E55B5` (`nom`);

--
-- Index pour la table `projet`
--
ALTER TABLE `projet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_50159CA9B03A8386` (`created_by_id`),
  ADD KEY `IDX_50159CA9ED5CA9E6` (`service_id`);

--
-- Index pour la table `projet_partenaire`
--
ALTER TABLE `projet_partenaire`
  ADD PRIMARY KEY (`projet_id`,`partenaire_id`),
  ADD KEY `IDX_B3624B59C18272` (`projet_id`),
  ADD KEY `IDX_B3624B5998DE13AC` (`partenaire_id`);

--
-- Index pour la table `rubrique`
--
ALTER TABLE `rubrique`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8FA4097CA76ED395` (`user_id`);

--
-- Index pour la table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_E19D9AD2A76ED395` (`user_id`);

--
-- Index pour la table `slider`
--
ALTER TABLE `slider`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8D93D64973F32DD8` (`configuration_id`);

--
-- Index pour la table `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7CC7DA2C60BB6FE6` (`auteur_id`),
  ADD KEY `IDX_7CC7DA2C3BD38833` (`rubrique_id`);

--
-- Index pour la table `vue`
--
ALTER TABLE `vue`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C0ADD5947294869C` (`article_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `article`
--
ALTER TABLE `article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=330;

--
-- AUTO_INCREMENT pour la table `blog`
--
ALTER TABLE `blog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `category_blog`
--
ALTER TABLE `category_blog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `commentaire`
--
ALTER TABLE `commentaire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `commentaire_article`
--
ALTER TABLE `commentaire_article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT pour la table `configuration`
--
ALTER TABLE `configuration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `document`
--
ALTER TABLE `document`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `etudiant`
--
ALTER TABLE `etudiant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `evenement`
--
ALTER TABLE `evenement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `fichier`
--
ALTER TABLE `fichier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `flash`
--
ALTER TABLE `flash`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=373;

--
-- AUTO_INCREMENT pour la table `import`
--
ALTER TABLE `import`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `partenaire`
--
ALTER TABLE `partenaire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `projet`
--
ALTER TABLE `projet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `rubrique`
--
ALTER TABLE `rubrique`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `service`
--
ALTER TABLE `service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `slider`
--
ALTER TABLE `slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `video`
--
ALTER TABLE `video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `vue`
--
ALTER TABLE `vue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92013;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `FK_23A0E663BD38833` FOREIGN KEY (`rubrique_id`) REFERENCES `rubrique` (`id`),
  ADD CONSTRAINT `FK_23A0E663DA5256D` FOREIGN KEY (`image_id`) REFERENCES `image` (`id`),
  ADD CONSTRAINT `FK_23A0E6660BB6FE6` FOREIGN KEY (`auteur_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `blog`
--
ALTER TABLE `blog`
  ADD CONSTRAINT `FK_C01551433DA5256D` FOREIGN KEY (`image_id`) REFERENCES `image` (`id`),
  ADD CONSTRAINT `FK_C0155143B03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `blog_category_blog`
--
ALTER TABLE `blog_category_blog`
  ADD CONSTRAINT `FK_3808E1681D383EE9` FOREIGN KEY (`category_blog_id`) REFERENCES `category_blog` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_3808E168DAE07E97` FOREIGN KEY (`blog_id`) REFERENCES `blog` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `category_blog`
--
ALTER TABLE `category_blog`
  ADD CONSTRAINT `FK_4B8E2B04B03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `FK_67F068BCDAE07E97` FOREIGN KEY (`blog_id`) REFERENCES `blog` (`id`);

--
-- Contraintes pour la table `commentaire_article`
--
ALTER TABLE `commentaire_article`
  ADD CONSTRAINT `FK_71F29C357294869C` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `document`
--
ALTER TABLE `document`
  ADD CONSTRAINT `FK_D8698A76C33F7837` FOREIGN KEY (`document_id`) REFERENCES `fichier` (`id`);

--
-- Contraintes pour la table `evenement`
--
ALTER TABLE `evenement`
  ADD CONSTRAINT `FK_B26681EB03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `evenement_image`
--
ALTER TABLE `evenement_image`
  ADD CONSTRAINT `FK_5697A8A73DA5256D` FOREIGN KEY (`image_id`) REFERENCES `image` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_5697A8A7FD02F13` FOREIGN KEY (`evenement_id`) REFERENCES `evenement` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `flash`
--
ALTER TABLE `flash`
  ADD CONSTRAINT `FK_AFCE5F0360BB6FE6` FOREIGN KEY (`auteur_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_AFCE5F037294869C` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`);

--
-- Contraintes pour la table `projet`
--
ALTER TABLE `projet`
  ADD CONSTRAINT `FK_50159CA9B03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_50159CA9ED5CA9E6` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`);

--
-- Contraintes pour la table `projet_partenaire`
--
ALTER TABLE `projet_partenaire`
  ADD CONSTRAINT `FK_B3624B5998DE13AC` FOREIGN KEY (`partenaire_id`) REFERENCES `partenaire` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_B3624B59C18272` FOREIGN KEY (`projet_id`) REFERENCES `projet` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `rubrique`
--
ALTER TABLE `rubrique`
  ADD CONSTRAINT `FK_8FA4097CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `service`
--
ALTER TABLE `service`
  ADD CONSTRAINT `FK_E19D9AD2A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D64973F32DD8` FOREIGN KEY (`configuration_id`) REFERENCES `configuration` (`id`);

--
-- Contraintes pour la table `video`
--
ALTER TABLE `video`
  ADD CONSTRAINT `FK_7CC7DA2C3BD38833` FOREIGN KEY (`rubrique_id`) REFERENCES `rubrique` (`id`),
  ADD CONSTRAINT `FK_7CC7DA2C60BB6FE6` FOREIGN KEY (`auteur_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `vue`
--
ALTER TABLE `vue`
  ADD CONSTRAINT `FK_C0ADD5947294869C` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

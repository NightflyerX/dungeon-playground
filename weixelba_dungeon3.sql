-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql22j16.db.hostpoint.internal
-- Erstellungszeit: 09. Okt 2019 um 23:01
-- Server-Version: 10.1.41-MariaDB
-- PHP-Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `weixelba_dungeon3`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `campaign`
--

CREATE TABLE `campaign` (
  `camp_id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `master` int(3) NOT NULL,
  `date` date NOT NULL,
  `descr` text COLLATE utf8_unicode_ci NOT NULL,
  `public` tinyint(1) NOT NULL,
  `status` varchar(10) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `campaign`
--

INSERT INTO `campaign` (`camp_id`, `name`, `master`, `date`, `descr`, `public`, `status`) VALUES
(1, 'Test', 3, '2019-09-27', 'Test', 1, 'completed'),
(2, 'Test2', 3, '2019-10-07', 'Descriptioncfbvnm', 1, 'active');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `chars`
--

CREATE TABLE `chars` (
  `char_id` int(11) NOT NULL,
  `data` longtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `chars`
--

INSERT INTO `chars` (`char_id`, `data`) VALUES
(1, '[\n    {\n        \"img_url\": \"20191001_173439.jpg\",\n        \"name\": \"Shieldwarrior\",\n        \"gender\": \"m\",\n        \"race\": \"Dwarf\",\n        \"type\": \"Warrior\",\n        \"level\": \"2\",\n        \"life_factor\": \"1\",\n        \"mana_factor\": \"1\",\n        \"skilldegree\": \"1\",\n        \"maxskill\": \"2\",\n        \"dm_only\": \"Normal\",\n        \"creator\": \"Admin\",\n        \"controller\": \"Admin\",\n        \"creation_date\": \"2019-10-07 22:18:07\",\n        \"char_id\": 1570479487,\n        \"fields\": [],\n        \"life\": {\n            \"current_life\": \"32\",\n            \"max_life\": \"32\"\n        },\n        \"mana\": {\n            \"current_mana\": \"26\",\n            \"max_mana\": \"26\"\n        },\n        \"attributes\": {\n            \"strength\": {\n                \"attr_value\": \"17\",\n                \"mod\": \"2\",\n                \"potion\": \"0\"\n            },\n            \"accuracy\": {\n                \"attr_value\": \"15\",\n                \"mod\": \"1\",\n                \"potion\": \"0\"\n            },\n            \"stamina\": {\n                \"attr_value\": \"17\",\n                \"mod\": \"2\",\n                \"potion\": \"0\"\n            },\n            \"agility\": {\n                \"attr_value\": \"15\",\n                \"mod\": \"1\",\n                \"potion\": \"0\"\n            },\n            \"intellect\": {\n                \"attr_value\": \"15\",\n                \"mod\": \"1\",\n                \"potion\": \"0\"\n            },\n            \"charisma\": {\n                \"attr_value\": \"15\",\n                \"mod\": \"1\",\n                \"potion\": \"0\"\n            },\n            \"wisdom\": {\n                \"attr_value\": \"15\",\n                \"mod\": \"1\",\n                \"potion\": \"0\"\n            },\n            \"luck\": {\n                \"attr_value\": \"17\",\n                \"mod\": \"2\",\n                \"potion\": \"0\"\n            }\n        },\n        \"skills\": {\n            \"defensive\": {\n                \"Dodge\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                },\n                \"Robe\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                },\n                \"Leather_armor\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                },\n                \"Chain_armor\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                },\n                \"Plate_armor\": {\n                    \"skill\": \"2\",\n                    \"cur_lvl\": \"2\",\n                    \"pot_lvl\": \"4\"\n                },\n                \"Shield\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                },\n                \"Cape\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                }\n            },\n            \"offensive\": {\n                \"No_weapon\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                },\n                \"Dagger_Knife\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                },\n                \"Sword\": {\n                    \"skill\": \"2\",\n                    \"cur_lvl\": \"1\",\n                    \"pot_lvl\": \"4\"\n                },\n                \"Hammer_Mace\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                },\n                \"Whip\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                }\n            },\n            \"magic_types\": {\n                \"Earth\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                },\n                \"Fire\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                },\n                \"Water\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                },\n                \"Air\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                },\n                \"Illutions\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                },\n                \"Necromancy\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                },\n                \"Material_change\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                }\n            },\n            \"skill_types\": {\n                \"Bodybuilding\": {\n                    \"skill\": \"2\",\n                    \"cur_lvl\": \"1\",\n                    \"pot_lvl\": \"4\"\n                },\n                \"Meditation\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                },\n                \"Regeneration\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                },\n                \"Craftmanship\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                },\n                \"Trade\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                },\n                \"Sneak\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                }\n            }\n        }\n    },\n    {\n        \"img_url\": \"20191001_173348.jpg\",\n        \"name\": \"Sorcerer\",\n        \"gender\": \"m\",\n        \"race\": \"Human\",\n        \"type\": \"Sorcerer\",\n        \"level\": \"3\",\n        \"life_factor\": \"1\",\n        \"mana_factor\": \"1\",\n        \"skilldegree\": \"1\",\n        \"maxskill\": \"2\",\n        \"dm_only\": \"Normal\",\n        \"creator\": \"Test\",\n        \"controller\": \"Test\",\n        \"creation_date\": \"2019-10-09 22:49:50\",\n        \"char_id\": 1570654190,\n        \"fields\": [],\n        \"life\": {\n            \"current_life\": \"32\",\n            \"max_life\": \"32\"\n        },\n        \"mana\": {\n            \"current_mana\": \"51\",\n            \"max_mana\": \"51\"\n        },\n        \"attributes\": {\n            \"strength\": {\n                \"attr_value\": \"17\",\n                \"mod\": \"2\",\n                \"potion\": \"0\"\n            },\n            \"accuracy\": {\n                \"attr_value\": \"14\",\n                \"mod\": \"0\",\n                \"potion\": \"0\"\n            },\n            \"stamina\": {\n                \"attr_value\": \"15\",\n                \"mod\": \"1\",\n                \"potion\": \"0\"\n            },\n            \"agility\": {\n                \"attr_value\": \"13\",\n                \"mod\": \"0\",\n                \"potion\": \"0\"\n            },\n            \"intellect\": {\n                \"attr_value\": \"17\",\n                \"mod\": \"2\",\n                \"potion\": \"0\"\n            },\n            \"charisma\": {\n                \"attr_value\": \"18\",\n                \"mod\": \"2\",\n                \"potion\": \"0\"\n            },\n            \"wisdom\": {\n                \"attr_value\": \"18\",\n                \"mod\": \"2\",\n                \"potion\": \"0\"\n            },\n            \"luck\": {\n                \"attr_value\": \"15\",\n                \"mod\": \"1\",\n                \"potion\": \"0\"\n            }\n        },\n        \"skills\": {\n            \"defensive\": {\n                \"Dodge\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                },\n                \"Robe\": {\n                    \"skill\": \"2\",\n                    \"cur_lvl\": \"1\",\n                    \"pot_lvl\": \"4\"\n                },\n                \"Leather_armor\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                },\n                \"Chain_armor\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                },\n                \"Plate_armor\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                },\n                \"Shield\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                },\n                \"Cape\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                }\n            },\n            \"offensive\": {\n                \"No_weapon\": {\n                    \"skill\": \"1\",\n                    \"cur_lvl\": \"1\",\n                    \"pot_lvl\": \"4\"\n                },\n                \"Dagger_Knife\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                },\n                \"Sword\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                },\n                \"Hammer_Mace\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                },\n                \"Whip\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                }\n            },\n            \"magic_types\": {\n                \"Earth\": {\n                    \"skill\": \"1\",\n                    \"cur_lvl\": \"1\",\n                    \"pot_lvl\": \"4\"\n                },\n                \"Fire\": {\n                    \"skill\": \"1\",\n                    \"cur_lvl\": \"1\",\n                    \"pot_lvl\": \"4\"\n                },\n                \"Water\": {\n                    \"skill\": \"1\",\n                    \"cur_lvl\": \"1\",\n                    \"pot_lvl\": \"4\"\n                },\n                \"Air\": {\n                    \"skill\": \"1\",\n                    \"cur_lvl\": \"1\",\n                    \"pot_lvl\": \"4\"\n                },\n                \"Illutions\": {\n                    \"skill\": \"1\",\n                    \"cur_lvl\": \"1\",\n                    \"pot_lvl\": \"4\"\n                },\n                \"Necromancy\": {\n                    \"skill\": \"1\",\n                    \"cur_lvl\": \"1\",\n                    \"pot_lvl\": \"3\"\n                },\n                \"Material_change\": {\n                    \"skill\": \"1\",\n                    \"cur_lvl\": \"1\",\n                    \"pot_lvl\": \"3\"\n                }\n            },\n            \"skill_types\": {\n                \"Bodybuilding\": {\n                    \"skill\": \"2\",\n                    \"cur_lvl\": \"1\",\n                    \"pot_lvl\": \"3\"\n                },\n                \"Meditation\": {\n                    \"skill\": \"2\",\n                    \"cur_lvl\": \"4\",\n                    \"pot_lvl\": \"4\"\n                },\n                \"Regeneration\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                },\n                \"Craftmanship\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                },\n                \"Trade\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                },\n                \"Sneak\": {\n                    \"skill\": \"0\",\n                    \"cur_lvl\": \"0\",\n                    \"pot_lvl\": \"2\"\n                }\n            }\n        }\n    }\n]');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `chat`
--

CREATE TABLE `chat` (
  `id` int(10) NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `chat`
--

INSERT INTO `chat` (`id`, `text`, `user_id`, `date`) VALUES
(1, '<div style=\"font-size:10px;\">New game created. Round 0. The players move.</div>', 0, '2019-09-27 12:54:35'),
(2, '<div style=\"font-size:10px;\">New field created: Field 449</div>', 0, '2019-10-04 14:51:15'),
(3, '<div style=\"font-size:10px;\">rdgh now controls field Field 449</div>', 0, '2019-10-04 14:51:19'),
(4, '<div style=\"font-size:10px;\">rdgh 2 is now affected by field Field 449 </div>', 0, '2019-10-04 14:51:22'),
(5, '<div style=\"font-size:10px;\">rdgh 2 gets the state dsfg for 999 rounds</div>', 0, '2019-10-04 14:51:33'),
(6, '<div style=\"font-size:10px;\">New field state</div>', 0, '2019-10-04 14:51:33'),
(7, '<div style=\"font-size:10px;\"><div class=\"alert alert-warning\" role=\"alert\">New round (0.5). The dungeonmasters move.</div></div>', 0, '2019-10-04 14:58:35'),
(8, '<div style=\"font-size:10px;\">Field Field 449 is removed</div>', 0, '2019-10-07 22:11:42'),
(9, '<div style=\"font-size:10px;\"><div class=\"alert alert-warning\" role=\"alert\">End of the game.</div></div>', 0, '2019-10-07 22:12:33'),
(10, '<div style=\"font-size:10px;\">New game created. Round 0. The players move.</div>', 0, '2019-10-07 22:14:49'),
(11, '<div style=\"font-size:10px;\">Shieldwarrior gets the state Addional life for 2 rounds</div>', 0, '2019-10-07 22:38:39'),
(12, '<div style=\"font-size:10px;\"><div class=\"alert alert-warning\" role=\"alert\">New round (0.5). The dungeonmasters move.</div></div>', 0, '2019-10-07 22:39:26'),
(13, '<div style=\"font-size:10px;\"><div class=\"alert alert-warning\" role=\"alert\">New round (1). The players move.</div></div>', 0, '2019-10-07 22:39:39'),
(14, '<div style=\"font-size:10px;\"><div class=\"alert alert-warning\" role=\"alert\">New round (1.5). The dungeonmasters move.</div></div>', 0, '2019-10-07 22:39:44'),
(15, '<div style=\"font-size:10px;\">State Addional life of Shieldwarrior expired and removed</div>', 0, '2019-10-07 22:39:44'),
(16, '<div style=\"color:#\">Admin: 3d8</div>\n', 0, '2019-10-07 22:55:26'),
(17, '<div style=\"font-size:10px;\">12 rolled</div>', 0, '2019-10-07 23:04:35'),
(18, '<div style=\"font-size:10px;\"><span style=\"color:yellow\">9 + 3 => </span><span style=\"color:orange\">9 + 3 => </span><span style=\"color:green\">9 + 3 => </span><span style=\"color:#42f4f4\">12</span></div>', 0, '2019-10-07 23:04:35'),
(19, '<div style=\"font-size:10px;\">New field created: Field 305</div>', 0, '2019-10-09 22:44:10'),
(20, '<div style=\"font-size:10px;\">Shieldwarrior is now affected by field Field 305 </div>', 0, '2019-10-09 22:50:22'),
(21, '<div style=\"font-size:10px;\">Sorcerer now controls field Field 305</div>', 0, '2019-10-09 22:50:25'),
(22, '<div style=\"font-size:10px;\">Shieldwarrior erh&auml;lt den Status Test f&uuml;r 999 Runden</div>', 0, '2019-10-09 22:50:33'),
(23, '<div style=\"font-size:10px;\">New field state</div>', 0, '2019-10-09 22:50:33'),
(24, '<div style=\"font-size:10px;\">Field Field 305 costs 1 per round Mana points</div>', 0, '2019-10-09 22:50:43'),
(25, '<div class=\"alert alert-info\" style=\"font-size:10px;\"><span style=\"color:white;font-size:12px;\">Magical action: Fire magic of Sorcerer at Shieldwarrior</span></div>\n', 0, '2019-10-09 22:52:55'),
(26, '<div style=\"font-size:10px;\"><span style=\"color:white;\">Target hit!</span> (<span style=\"color:yellow;\">1 => </span><span style=\"color:orange;\">1 => </span><span style=\"color:green;\">1 => </span><span style=\"color:#42f4f4;\">1 </span>)</div>\n', 0, '2019-10-09 22:52:55'),
(27, '<div style=\"font-size:10px;\">Physical general {char.skills.magic_types.Fire.skill}d6 + attribute on Life points</div>', 0, '2019-10-09 22:52:55'),
(28, '<div style=\"font-size:10px;\"><span style=\"color:yellow\">3 + 2 => </span><span style=\"color:orange\">3 + 2 => </span><span style=\"color:green\">3 + 2 => </span><span style=\"color:#42f4f4\">5</span></div>', 0, '2019-10-09 22:52:55'),
(29, '<div style=\"font-size:10px;\"><div style=\"font-size:10px;\"><span style=\"color:white;font-weight: bold;\">Effective damage</span>: <span style=\"color:white;\">Physical general [5- 22] on Life points</span> => <span style=\"color:yellow;\">5- 22</span> => <span style=\"color:orange;\">5- 22</span> => <span style=\"color:green;\">0</span></div></div>', 3, '2019-10-09 22:52:55'),
(30, '<div style=\"font-size:10px;\"></div>\n', 0, '2019-10-09 22:52:55');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fields`
--

CREATE TABLE `fields` (
  `id` int(11) NOT NULL,
  `fields` longtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `fields`
--

INSERT INTO `fields` (`id`, `fields`) VALUES
(1, '[]');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `game`
--

CREATE TABLE `game` (
  `game_id` int(11) NOT NULL,
  `data` longtext COLLATE utf8_unicode_ci NOT NULL,
  `active` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `game`
--

INSERT INTO `game` (`game_id`, `data`, `active`) VALUES
(2, '{\n    \"game_id\": \"0\",\n    \"start_time\": 1569581675,\n    \"current_round\": 0.5,\n    \"dungeonmaster_user_id\": 3,\n    \"dungeonmaster_username\": \"Admin\",\n    \"chars\": [],\n    \"fields\": [],\n    \"tokenpool_users\": []\n}', 0),
(3, '{\n    \"game_id\": \"0\",\n    \"start_time\": 1570479289,\n    \"current_round\": 1.5,\n    \"dungeonmaster_user_id\": 3,\n    \"dungeonmaster_username\": \"Admin\",\n    \"chars\": [\n        {\n            \"img_url\": \"20191001_173439.jpg\",\n            \"name\": \"Shieldwarrior\",\n            \"gender\": \"m\",\n            \"race\": \"Dwarf\",\n            \"type\": \"Warrior\",\n            \"level\": \"2\",\n            \"life_factor\": \"1\",\n            \"mana_factor\": \"1\",\n            \"skilldegree\": \"1\",\n            \"maxskill\": \"2\",\n            \"dm_only\": \"Normal\",\n            \"creator\": \"Admin\",\n            \"controller\": \"Admin\",\n            \"creation_date\": \"2019-10-07 22:18:07\",\n            \"char_id\": 1570479487,\n            \"fields\": [],\n            \"attributes\": {\n                \"strength\": {\n                    \"attr_value\": \"17\",\n                    \"mod\": \"2\",\n                    \"potion\": \"0\"\n                },\n                \"accuracy\": {\n                    \"attr_value\": \"15\",\n                    \"mod\": \"1\",\n                    \"potion\": \"0\"\n                },\n                \"stamina\": {\n                    \"attr_value\": \"17\",\n                    \"mod\": \"2\",\n                    \"potion\": \"0\"\n                },\n                \"agility\": {\n                    \"attr_value\": \"15\",\n                    \"mod\": \"1\",\n                    \"potion\": \"0\"\n                },\n                \"intellect\": {\n                    \"attr_value\": \"15\",\n                    \"mod\": \"1\",\n                    \"potion\": \"0\"\n                },\n                \"charisma\": {\n                    \"attr_value\": \"15\",\n                    \"mod\": \"1\",\n                    \"potion\": \"0\"\n                },\n                \"wisdom\": {\n                    \"attr_value\": \"15\",\n                    \"mod\": \"1\",\n                    \"potion\": \"0\"\n                },\n                \"luck\": {\n                    \"attr_value\": \"17\",\n                    \"mod\": \"2\",\n                    \"potion\": \"0\"\n                }\n            },\n            \"skills\": {\n                \"defensive\": {\n                    \"Dodge\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    },\n                    \"Robe\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    },\n                    \"Leather_armor\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    },\n                    \"Chain_armor\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    },\n                    \"Plate_armor\": {\n                        \"skill\": \"2\",\n                        \"cur_lvl\": \"2\",\n                        \"pot_lvl\": \"4\"\n                    },\n                    \"Shield\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    },\n                    \"Cape\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    }\n                },\n                \"offensive\": {\n                    \"No_weapon\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    },\n                    \"Dagger_Knife\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    },\n                    \"Sword\": {\n                        \"skill\": \"2\",\n                        \"cur_lvl\": \"1\",\n                        \"pot_lvl\": \"4\"\n                    },\n                    \"Hammer_Mace\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    },\n                    \"Whip\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    }\n                },\n                \"magic_types\": {\n                    \"Earth\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    },\n                    \"Fire\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    },\n                    \"Water\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    },\n                    \"Air\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    },\n                    \"Illutions\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    },\n                    \"Necromancy\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    },\n                    \"Material_change\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    }\n                },\n                \"skill_types\": {\n                    \"Bodybuilding\": {\n                        \"skill\": \"2\",\n                        \"cur_lvl\": \"1\",\n                        \"pot_lvl\": \"4\"\n                    },\n                    \"Meditation\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    },\n                    \"Regeneration\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    },\n                    \"Craftmanship\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    },\n                    \"Trade\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    },\n                    \"Sneak\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    }\n                }\n            },\n            \"leftright\": \"right\",\n            \"pools\": {\n                \"life\": {\n                    \"cur\": 42,\n                    \"max\": \"32\",\n                    \"add\": \"0\"\n                },\n                \"mana\": {\n                    \"cur\": 36,\n                    \"max\": \"26\",\n                    \"add\": \"0\"\n                },\n                \"ap\": {\n                    \"cur\": 110,\n                    \"max\": 100,\n                    \"add\": \"100\"\n                },\n                \"move\": {\n                    \"cur\": 15,\n                    \"max\": 5,\n                    \"add\": \"10\"\n                },\n                \"poison\": {\n                    \"cur\": 40,\n                    \"max\": 100,\n                    \"add\": \"0\"\n                },\n                \"sickness\": {\n                    \"cur\": 40,\n                    \"max\": 100,\n                    \"add\": \"0\"\n                },\n                \"lp_shield\": {\n                    \"cur\": 39,\n                    \"max\": 1000,\n                    \"add\": \"0\"\n                }\n            },\n            \"controller_user_id\": \"3\",\n            \"states\": [\n                {\n                    \"name\": \"Test\",\n                    \"id\": 1568298552,\n                    \"rounds\": 999,\n                    \"caster\": 1570654190,\n                    \"caster_username\": \"Sorcerer\",\n                    \"vars\": [\n                        {\n                            \"path\": \"char.level\",\n                            \"modifier\": \"+2\"\n                        },\n                        {\n                            \"path\": \"char.pools.life.max\",\n                            \"modifier\": \"+20\"\n                        }\n                    ],\n                    \"tank\": []\n                }\n            ],\n            \"tmpvars\": [],\n            \"tokens\": [],\n            \"special_tokens\": [],\n            \"resistances\": {\n                \"Physical general\": 0,\n                \"Physical Shot\": 0,\n                \"Physical Blow\": 0,\n                \"Physical Sting\": 0,\n                \"Physical Cut\": 0,\n                \"Physical Poison\": 0,\n                \"Physical Fire\": 0,\n                \"Magic general\": 0,\n                \"Magic Arcane_magic\": 0,\n                \"Magic Spiritual_magic\": 0,\n                \"Magic Alchemy\": 0,\n                \"Magic Earth\": 0,\n                \"Magic Fire\": 0,\n                \"Magic Water\": 0,\n                \"Magic Air\": 0,\n                \"Magic Illutions\": 0,\n                \"Magic Necromancy\": 0,\n                \"Magic Material_change\": 0\n            },\n            \"globals\": {\n                \"physical_damage_input\": 1,\n                \"physical_damage_output\": 1,\n                \"magical_damage_input\": 1,\n                \"magical_damage_output\": 1,\n                \"hit_chance_value\": 1,\n                \"get_hit_chance_value\": 1\n            },\n            \"weapons\": [],\n            \"equipment\": [],\n            \"equip\": {\n                \"weapons\": [],\n                \"equipment\": []\n            },\n            \"armor\": {\n                \"objects\": [],\n                \"agility\": 11,\n                \"wisdom\": 11,\n                \"result_physical\": 11,\n                \"result_magical\": 11,\n                \"tier_lvl\": 0\n            },\n            \"cost_affection\": []\n        },\n        {\n            \"img_url\": \"20191001_173348.jpg\",\n            \"name\": \"Sorcerer\",\n            \"gender\": \"m\",\n            \"race\": \"Human\",\n            \"type\": \"Sorcerer\",\n            \"level\": \"3\",\n            \"life_factor\": \"1\",\n            \"mana_factor\": \"1\",\n            \"skilldegree\": \"1\",\n            \"maxskill\": \"2\",\n            \"dm_only\": \"Normal\",\n            \"creator\": \"Test\",\n            \"controller\": \"Test\",\n            \"creation_date\": \"2019-10-09 22:49:50\",\n            \"char_id\": 1570654190,\n            \"fields\": [],\n            \"attributes\": {\n                \"strength\": {\n                    \"attr_value\": \"17\",\n                    \"mod\": \"2\",\n                    \"potion\": \"0\"\n                },\n                \"accuracy\": {\n                    \"attr_value\": \"14\",\n                    \"mod\": \"0\",\n                    \"potion\": \"0\"\n                },\n                \"stamina\": {\n                    \"attr_value\": \"15\",\n                    \"mod\": \"1\",\n                    \"potion\": \"0\"\n                },\n                \"agility\": {\n                    \"attr_value\": \"13\",\n                    \"mod\": \"0\",\n                    \"potion\": \"0\"\n                },\n                \"intellect\": {\n                    \"attr_value\": \"17\",\n                    \"mod\": \"2\",\n                    \"potion\": \"0\"\n                },\n                \"charisma\": {\n                    \"attr_value\": \"18\",\n                    \"mod\": \"2\",\n                    \"potion\": \"0\"\n                },\n                \"wisdom\": {\n                    \"attr_value\": \"18\",\n                    \"mod\": \"2\",\n                    \"potion\": \"0\"\n                },\n                \"luck\": {\n                    \"attr_value\": \"15\",\n                    \"mod\": \"1\",\n                    \"potion\": \"0\"\n                }\n            },\n            \"skills\": {\n                \"defensive\": {\n                    \"Dodge\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    },\n                    \"Robe\": {\n                        \"skill\": \"2\",\n                        \"cur_lvl\": \"1\",\n                        \"pot_lvl\": \"4\"\n                    },\n                    \"Leather_armor\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    },\n                    \"Chain_armor\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    },\n                    \"Plate_armor\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    },\n                    \"Shield\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    },\n                    \"Cape\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    }\n                },\n                \"offensive\": {\n                    \"No_weapon\": {\n                        \"skill\": \"1\",\n                        \"cur_lvl\": \"1\",\n                        \"pot_lvl\": \"4\"\n                    },\n                    \"Dagger_Knife\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    },\n                    \"Sword\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    },\n                    \"Hammer_Mace\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    },\n                    \"Whip\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    }\n                },\n                \"magic_types\": {\n                    \"Earth\": {\n                        \"skill\": \"1\",\n                        \"cur_lvl\": \"1\",\n                        \"pot_lvl\": \"4\"\n                    },\n                    \"Fire\": {\n                        \"skill\": \"1\",\n                        \"cur_lvl\": \"1\",\n                        \"pot_lvl\": \"4\"\n                    },\n                    \"Water\": {\n                        \"skill\": \"1\",\n                        \"cur_lvl\": \"1\",\n                        \"pot_lvl\": \"4\"\n                    },\n                    \"Air\": {\n                        \"skill\": \"1\",\n                        \"cur_lvl\": \"1\",\n                        \"pot_lvl\": \"4\"\n                    },\n                    \"Illutions\": {\n                        \"skill\": \"1\",\n                        \"cur_lvl\": \"1\",\n                        \"pot_lvl\": \"4\"\n                    },\n                    \"Necromancy\": {\n                        \"skill\": \"1\",\n                        \"cur_lvl\": \"1\",\n                        \"pot_lvl\": \"3\"\n                    },\n                    \"Material_change\": {\n                        \"skill\": \"1\",\n                        \"cur_lvl\": \"1\",\n                        \"pot_lvl\": \"3\"\n                    }\n                },\n                \"skill_types\": {\n                    \"Bodybuilding\": {\n                        \"skill\": \"2\",\n                        \"cur_lvl\": \"1\",\n                        \"pot_lvl\": \"3\"\n                    },\n                    \"Meditation\": {\n                        \"skill\": \"2\",\n                        \"cur_lvl\": \"4\",\n                        \"pot_lvl\": \"4\"\n                    },\n                    \"Regeneration\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    },\n                    \"Craftmanship\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    },\n                    \"Trade\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    },\n                    \"Sneak\": {\n                        \"skill\": \"0\",\n                        \"cur_lvl\": \"0\",\n                        \"pot_lvl\": \"2\"\n                    }\n                }\n            },\n            \"leftright\": \"left\",\n            \"pools\": {\n                \"life\": {\n                    \"cur\": \"32\",\n                    \"max\": \"32\",\n                    \"add\": \"0\"\n                },\n                \"mana\": {\n                    \"cur\": \"51\",\n                    \"max\": \"51\",\n                    \"add\": \"0\"\n                },\n                \"ap\": {\n                    \"cur\": \"100\",\n                    \"max\": 100,\n                    \"add\": \"100\"\n                },\n                \"move\": {\n                    \"cur\": \"5\",\n                    \"max\": 5,\n                    \"add\": \"10\"\n                },\n                \"poison\": {\n                    \"cur\": \"0\",\n                    \"max\": 100,\n                    \"add\": \"0\"\n                },\n                \"sickness\": {\n                    \"cur\": \"0\",\n                    \"max\": 100,\n                    \"add\": \"0\"\n                },\n                \"lp_shield\": {\n                    \"cur\": \"0\",\n                    \"max\": 1000,\n                    \"add\": \"0\"\n                }\n            },\n            \"controller_user_id\": \"4\",\n            \"states\": [],\n            \"tmpvars\": [],\n            \"tokens\": [],\n            \"special_tokens\": [],\n            \"resistances\": {\n                \"Physical general\": 0,\n                \"Physical Shot\": 0,\n                \"Physical Blow\": 0,\n                \"Physical Sting\": 0,\n                \"Physical Cut\": 0,\n                \"Physical Poison\": 0,\n                \"Physical Fire\": 0,\n                \"Magic general\": 0,\n                \"Magic Arcane_magic\": 0,\n                \"Magic Spiritual_magic\": 0,\n                \"Magic Alchemy\": 0,\n                \"Magic Earth\": 0,\n                \"Magic Fire\": 0,\n                \"Magic Water\": 0,\n                \"Magic Air\": 0,\n                \"Magic Illutions\": 0,\n                \"Magic Necromancy\": 0,\n                \"Magic Material_change\": 0\n            },\n            \"globals\": {\n                \"physical_damage_input\": 1,\n                \"physical_damage_output\": 1,\n                \"magical_damage_input\": 1,\n                \"magical_damage_output\": 1,\n                \"hit_chance_value\": 1,\n                \"get_hit_chance_value\": 1\n            },\n            \"weapons\": [],\n            \"equipment\": [],\n            \"equip\": {\n                \"weapons\": [],\n                \"equipment\": []\n            },\n            \"armor\": {\n                \"objects\": [],\n                \"agility\": 0,\n                \"wisdom\": 2,\n                \"result_physical\": 0,\n                \"result_magical\": 2,\n                \"tier_lvl\": 0\n            },\n            \"cost_affection\": []\n        }\n    ],\n    \"fields\": [\n        {\n            \"creation_date\": \"157065385064\",\n            \"field_name\": \"Field 305\",\n            \"field_owner_id\": 1570654190,\n            \"field_target_ids\": [\n                \"1570479487\"\n            ],\n            \"field_status\": [\n                1568298552\n            ],\n            \"field_events\": [],\n            \"cost\": [\n                {\n                    \"pool\": \"Mana points\",\n                    \"value\": \"1\"\n                }\n            ],\n            \"field_cost_paid\": false,\n            \"field_start_round\": 1.5\n        }\n    ],\n    \"tokenpool_users\": []\n}', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lib_actions`
--

CREATE TABLE `lib_actions` (
  `sorcery_id` int(11) NOT NULL,
  `data` longtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `lib_actions`
--

INSERT INTO `lib_actions` (`sorcery_id`, `data`) VALUES
(1, '[\n  {\n    \"action_name\": \"Test\",\n    \"action_type\": \"Physical action\",\n    \"hit_chance_formula\": \"100%\",\n    \"attributes\": [],\n    \"description\": \"\",\n    \"uservars\": [],\n    \"tier_lvl\": [\n      {\n        \"tier_lvl_name\": \"Normal\",\n        \"target_area\": \"Single target\",\n        \"only_char_filter\": [],\n        \"min_level_filter\": \"Normal\",\n        \"weapon_filters\": [\n          {\n            \"weapon_filter\": \"Dagger_Knife\",\n            \"weapon_tier_level_filter\": \"tier 1\",\n            \"weapon_skill_filter\": \"Dagger_Knife\",\n            \"weapon_skill_level_filter\": \"Normal\"\n          },\n          {\n            \"weapon_filter\": \"Sword\",\n            \"weapon_tier_level_filter\": \"tier 3\",\n            \"weapon_skill_filter\": \"Sword\",\n            \"weapon_skill_level_filter\": \"Normal\"\n          }\n        ],\n        \"magic_filters\": [],\n        \"other_skill_filters\": [],\n        \"item_filters\": [],\n        \"damage\": [\n          {\n            \"damage_type\": \"Physical general\",\n            \"formula\": \"10\",\n            \"damage_heal\": \"damage\",\n            \"affected_damage_pool\": \"Life points\",\n            \"skip_resistance\": \"false\"\n          }\n        ],\n        \"effect_add_damage\": [],\n        \"effect_add_special_token\": [],\n        \"effect_add_state\": [],\n        \"effect_summon_char\": [],\n        \"effect_add_field\": [],\n        \"cost\": [],\n        \"token_cost\": [],\n        \"message_after_attack\": \"\"\n      }\n    ]\n  },\n  {\n    \"action_name\": \"Tank\",\n    \"action_type\": \"Other_action\",\n    \"action_magic_type\": \"Earth\",\n    \"hit_chance_formula\": \"100%\",\n    \"attributes\": [],\n    \"description\": \"\",\n    \"uservars\": [],\n    \"tier_lvl\": [\n      {\n        \"tier_lvl_name\": \"Normal\",\n        \"target_area\": \"Single target\",\n        \"only_char_filter\": [],\n        \"min_level_filter\": \"Normal\",\n        \"weapon_filters\": [],\n        \"magic_filters\": [],\n        \"other_skill_filters\": [],\n        \"item_filters\": [],\n        \"damage\": [],\n        \"effect_add_damage\": [],\n        \"effect_add_special_token\": [],\n        \"effect_add_state\": [\n          {\n            \"chance\": \"100%\",\n            \"state\": \"Tanked\",\n            \"target\": \"action_target\",\n            \"rounds\": \"99\",\n            \"add_remove\": \"add\"\n          }\n        ],\n        \"effect_summon_char\": [],\n        \"effect_add_field\": [],\n        \"cost\": [],\n        \"token_cost\": [],\n        \"message_after_attack\": \"\"\n      }\n    ]\n  },\n  {\n    \"action_name\": \"Diceroll\",\n    \"action_type\": \"Diceroll\",\n    \"action_magic_type\": \"Earth\",\n    \"hit_chance_formula\": \"2d8 + 3\",\n    \"attributes\": [],\n    \"description\": \"Dice\",\n    \"uservars\": [],\n    \"tier_lvl\": [\n      {\n        \"tier_lvl_name\": \"Normal\",\n        \"target_area\": \"Single target\",\n        \"only_char_filter\": [],\n        \"min_level_filter\": \"Normal\",\n        \"weapon_filters\": [],\n        \"magic_filters\": [],\n        \"other_skill_filters\": [],\n        \"item_filters\": [],\n        \"damage\": [],\n        \"effect_add_damage\": [],\n        \"effect_add_special_token\": [],\n        \"effect_add_state\": [],\n        \"effect_summon_char\": [],\n        \"effect_add_field\": [],\n        \"cost\": [],\n        \"token_cost\": [],\n        \"message_after_attack\": \"\"\n      }\n    ]\n  },\n  {\n    \"action_name\": \"Fire magic\",\n    \"action_type\": \"Magical action\",\n    \"action_magic_type\": \"Fire\",\n    \"hit_chance_formula\": \"100%\",\n    \"attributes\": [\n      \"intellect\",\n      \"charisma\"\n    ],\n    \"description\": \"\",\n    \"uservars\": [],\n    \"tier_lvl\": [\n      {\n        \"tier_lvl_name\": \"Normal\",\n        \"target_area\": \"Single target\",\n        \"only_char_filter\": [],\n        \"min_level_filter\": \"Normal\",\n        \"weapon_filters\": [],\n        \"magic_filters\": [],\n        \"other_skill_filters\": [],\n        \"item_filters\": [],\n        \"damage\": [\n          {\n            \"damage_type\": \"Physical general\",\n            \"formula\": \"{char.skills.magic_types.Fire.skill}d6 + attribute\",\n            \"damage_heal\": \"damage\",\n            \"affected_damage_pool\": \"Life points\",\n            \"skip_resistance\": \"false\"\n          }\n        ],\n        \"effect_add_damage\": [],\n        \"effect_add_special_token\": [],\n        \"effect_add_state\": [],\n        \"effect_summon_char\": [],\n        \"effect_add_field\": [],\n        \"cost\": [],\n        \"token_cost\": [],\n        \"message_after_attack\": \"\"\n      }\n    ]\n  }\n]');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lib_data`
--

CREATE TABLE `lib_data` (
  `data_id` int(11) NOT NULL,
  `data` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `lib_data`
--

INSERT INTO `lib_data` (`data_id`, `data`) VALUES
(1, '{\n    \"type_names\": [\n        {\n            \"name\": \"Sorcerer\"\n        },\n        {\n            \"name\": \"Necromancer\"\n        },\n        {\n            \"name\": \"Warrior\"\n        }\n    ],\n    \"races_names\": [\n        {\n            \"name\": \"Human\"\n        },\n        {\n            \"name\": \"Humanoid\"\n        },\n        {\n            \"name\": \"Undead\"\n        },\n        {\n            \"name\": \"Elf\"\n        },\n        {\n            \"name\": \"Dwarf\"\n        }\n    ],\n    \"attributes\": [\n        {\n            \"name\": \"strength\"\n        },\n        {\n            \"name\": \"accuracy\"\n        },\n        {\n            \"name\": \"stamina\"\n        },\n        {\n            \"name\": \"agility\"\n        },\n        {\n            \"name\": \"intellect\"\n        },\n        {\n            \"name\": \"charisma\"\n        },\n        {\n            \"name\": \"wisdom\"\n        },\n        {\n            \"name\": \"luck\"\n        }\n    ],\n    \"action_types\": [\n        {\n            \"key\": \"CLASS\",\n            \"name\": \"Class ability\"\n        },\n        {\n            \"key\": \"PHYSICAL\",\n            \"name\": \"Physical action\"\n        },\n        {\n            \"key\": \"MAGICAL\",\n            \"name\": \"Magical action\"\n        },\n        {\n            \"key\": \"ITEM\",\n            \"name\": \"Potion_Item\"\n        },\n        {\n            \"key\": \"OTHER\",\n            \"name\": \"Other_action\"\n        },\n        {\n            \"key\": \"DICEROLL\",\n            \"name\": \"Diceroll\"\n        }\n    ],\n    \"damage_types\": [\n        {\n            \"name\": \"Shot\",\n            \"color\": \"#ff80ff\"\n        },\n        {\n            \"name\": \"Blow\",\n            \"color\": \"#ff00ff\"\n        },\n        {\n            \"name\": \"Sting\",\n            \"color\": \"#ff0080\"\n        },\n        {\n            \"name\": \"Cut\",\n            \"color\": \"#ff80c0\"\n        },\n        {\n            \"name\": \"Poison\",\n            \"color\": \"#00ff00\"\n        },\n        {\n            \"name\": \"Fire\",\n            \"color\": \"#ff0000\"\n        }\n    ],\n    \"weapon_types\": [\n        {\n            \"name\": \"No_weapon\"\n        },\n        {\n            \"name\": \"Dagger_Knife\"\n        },\n        {\n            \"name\": \"Sword\"\n        },\n        {\n            \"name\": \"Hammer_Mace\"\n        },\n        {\n            \"name\": \"Whip\"\n        }\n    ],\n    \"pools\": [\n        {\n            \"id\": \"life\",\n            \"name\": \"Life points\"\n        },\n        {\n            \"id\": \"mana\",\n            \"name\": \"Mana points\"\n        },\n        {\n            \"id\": \"ap\",\n            \"name\": \"Action points\"\n        },\n        {\n            \"id\": \"move\",\n            \"name\": \"Movement\"\n        },\n        {\n            \"id\": \"poison\",\n            \"name\": \"Poison\"\n        },\n        {\n            \"id\": \"sickness\",\n            \"name\": \"Sickness\"\n        },\n        {\n            \"id\": \"lp_shield\",\n            \"name\": \"LP_Shield\"\n        }\n    ],\n    \"mods\": [\n        {\n            \"attribute_value\": 0,\n            \"mod_value\": -6\n        },\n        {\n            \"attribute_value\": 3,\n            \"mod_value\": -5\n        },\n        {\n            \"attribute_value\": 5,\n            \"mod_value\": -4\n        },\n        {\n            \"attribute_value\": 7,\n            \"mod_value\": -3\n        },\n        {\n            \"attribute_value\": 9,\n            \"mod_value\": -2\n        },\n        {\n            \"attribute_value\": 11,\n            \"mod_value\": -1\n        },\n        {\n            \"attribute_value\": 13,\n            \"mod_value\": 0\n        },\n        {\n            \"attribute_value\": 15,\n            \"mod_value\": 1\n        },\n        {\n            \"attribute_value\": 17,\n            \"mod_value\": 2\n        },\n        {\n            \"attribute_value\": 19,\n            \"mod_value\": 3\n        },\n        {\n            \"attribute_value\": 21,\n            \"mod_value\": 4\n        },\n        {\n            \"attribute_value\": 25,\n            \"mod_value\": 5\n        },\n        {\n            \"attribute_value\": 30,\n            \"mod_value\": 6\n        },\n        {\n            \"attribute_value\": 35,\n            \"mod_value\": 7\n        },\n        {\n            \"attribute_value\": 40,\n            \"mod_value\": 8\n        },\n        {\n            \"attribute_value\": 50,\n            \"mod_value\": 9\n        },\n        {\n            \"attribute_value\": 75,\n            \"mod_value\": 10\n        },\n        {\n            \"attribute_value\": 100,\n            \"mod_value\": 11\n        },\n        {\n            \"attribute_value\": 125,\n            \"mod_value\": 12\n        },\n        {\n            \"attribute_value\": 150,\n            \"mod_value\": 13\n        },\n        {\n            \"attribute_value\": 175,\n            \"mod_value\": 14\n        },\n        {\n            \"attribute_value\": 200,\n            \"mod_value\": 15\n        },\n        {\n            \"attribute_value\": 225,\n            \"mod_value\": 16\n        },\n        {\n            \"attribute_value\": 250,\n            \"mod_value\": 17\n        },\n        {\n            \"attribute_value\": 275,\n            \"mod_value\": 18\n        },\n        {\n            \"attribute_value\": 300,\n            \"mod_value\": 19\n        },\n        {\n            \"attribute_value\": 350,\n            \"mod_value\": 20\n        },\n        {\n            \"attribute_value\": 400,\n            \"mod_value\": 25\n        },\n        {\n            \"attribute_value\": 500,\n            \"mod_value\": 25\n        }\n    ],\n    \"defensive\": [\n        {\n            \"name\": \"Dodge\"\n        },\n        {\n            \"name\": \"Robe\"\n        },\n        {\n            \"name\": \"Leather_armor\"\n        },\n        {\n            \"name\": \"Chain_armor\"\n        },\n        {\n            \"name\": \"Plate_armor\"\n        },\n        {\n            \"name\": \"Shield\"\n        },\n        {\n            \"name\": \"Cape\"\n        }\n    ],\n    \"magic_classes\": [\n        {\n            \"name\": \"Arcane_magic\",\n            \"color\": \"#ff0000\"\n        },\n        {\n            \"name\": \"Spiritual_magic\",\n            \"color\": \"#0000a0\"\n        },\n        {\n            \"name\": \"Alchemy\",\n            \"color\": \"#008000\"\n        }\n    ],\n    \"magic_types\": [\n        {\n            \"magic_class_name\": \"Arcane_magic\",\n            \"magic_type_name\": \"Earth\",\n            \"color\": \"#ff8040\",\n            \"glyph\": \"si-glyph-mountain\"\n        },\n        {\n            \"magic_class_name\": \"Arcane_magic\",\n            \"magic_type_name\": \"Fire\",\n            \"color\": \"#ff0000\",\n            \"glyph\": \"si-glyph-fire\"\n        },\n        {\n            \"magic_class_name\": \"Arcane_magic\",\n            \"magic_type_name\": \"Water\",\n            \"color\": \"#0000ff\",\n            \"glyph\": \"si-glyph-drop-water\"\n        },\n        {\n            \"magic_class_name\": \"Arcane_magic\",\n            \"magic_type_name\": \"Air\",\n            \"color\": \"#80ffff\",\n            \"glyph\": \"si-glyph-wind-turbines\"\n        },\n        {\n            \"magic_class_name\": \"Spiritual_magic\",\n            \"magic_type_name\": \"Illutions\",\n            \"color\": \"#ff0080\",\n            \"glyph\": \"si-glyph-image\"\n        },\n        {\n            \"magic_class_name\": \"Spiritual_magic\",\n            \"magic_type_name\": \"Necromancy\",\n            \"color\": \"#00ff80\",\n            \"glyph\": \"si-glyph-skull\"\n        },\n        {\n            \"magic_class_name\": \"Alchemy\",\n            \"magic_type_name\": \"Material_change\",\n            \"color\": \"#8080c0\",\n            \"glyph\": \"si-glyph-connect-2\"\n        }\n    ],\n    \"skill_classes\": [\n        {\n            \"name\": \"Body_and_mind\"\n        },\n        {\n            \"name\": \"Skills\"\n        },\n        {\n            \"name\": \"Other\"\n        }\n    ],\n    \"skill_types\": [\n        {\n            \"skill_class_name\": \"Body_and_mind\",\n            \"skill_type_name\": \"Bodybuilding\"\n        },\n        {\n            \"skill_class_name\": \"Body_and_mind\",\n            \"skill_type_name\": \"Meditation\"\n        },\n        {\n            \"skill_class_name\": \"Body_and_mind\",\n            \"skill_type_name\": \"Regeneration\"\n        },\n        {\n            \"skill_class_name\": \"Skills\",\n            \"skill_type_name\": \"Craftmanship\"\n        },\n        {\n            \"skill_class_name\": \"Skills\",\n            \"skill_type_name\": \"Trade\"\n        },\n        {\n            \"skill_class_name\": \"Other\",\n            \"skill_type_name\": \"Sneak\"\n        }\n    ],\n    \"states\": [\n        {\n            \"state_name\": \"dsfg\",\n            \"state_id\": \"1568298387\",\n            \"vars\": [\n                {\n                    \"path\": \"char.level\",\n                    \"modifier\": \"+1\"\n                }\n            ]\n        },\n        {\n            \"state_name\": \"Test\",\n            \"state_id\": \"1568298552\",\n            \"vars\": [\n                {\n                    \"path\": \"char.level\",\n                    \"modifier\": \"+2\"\n                },\n                {\n                    \"path\": \"char.pools.life.max\",\n                    \"modifier\": \"+20\"\n                }\n            ]\n        },\n        {\n            \"state_name\": \"Tanked\",\n            \"state_id\": \"1569505181\",\n            \"vars\": [\n                {\n                    \"path\": \"\",\n                    \"modifier\": \"\"\n                }\n            ]\n        },\n        {\n            \"state_name\": \"Tanked\",\n            \"state_id\": 1569507420,\n            \"vars\": [\n                {\n                    \"path\": \"\",\n                    \"modifier\": \"\"\n                }\n            ]\n        }\n    ],\n    \"states_effects2\": [],\n    \"damage_types_all\": [\n        {\n            \"name\": \"Physical general\",\n            \"color\": \"#ffffff\"\n        },\n        {\n            \"name\": \"Physical Shot\",\n            \"color\": \"#ff80ff\"\n        },\n        {\n            \"name\": \"Physical Blow\",\n            \"color\": \"#ff00ff\"\n        },\n        {\n            \"name\": \"Physical Sting\",\n            \"color\": \"#ff0080\"\n        },\n        {\n            \"name\": \"Physical Cut\",\n            \"color\": \"#ff80c0\"\n        },\n        {\n            \"name\": \"Physical Poison\",\n            \"color\": \"#00ff00\"\n        },\n        {\n            \"name\": \"Physical Fire\",\n            \"color\": \"#ff0000\"\n        },\n        {\n            \"name\": \"Magic general\",\n            \"color\": \"#ccccff\"\n        },\n        {\n            \"name\": \"Magic Arcane_magic\",\n            \"color\": \"#ff0000\"\n        },\n        {\n            \"name\": \"Magic Spiritual_magic\",\n            \"color\": \"#0000a0\"\n        },\n        {\n            \"name\": \"Magic Alchemy\",\n            \"color\": \"#008000\"\n        },\n        {\n            \"name\": \"Magic Earth\",\n            \"color\": \"#ff8040\"\n        },\n        {\n            \"name\": \"Magic Fire\",\n            \"color\": \"#ff0000\"\n        },\n        {\n            \"name\": \"Magic Water\",\n            \"color\": \"#0000ff\"\n        },\n        {\n            \"name\": \"Magic Air\",\n            \"color\": \"#80ffff\"\n        },\n        {\n            \"name\": \"Magic Illutions\",\n            \"color\": \"#ff0080\"\n        },\n        {\n            \"name\": \"Magic Necromancy\",\n            \"color\": \"#00ff80\"\n        },\n        {\n            \"name\": \"Magic Material_change\",\n            \"color\": \"#8080c0\"\n        }\n    ],\n    \"special_token\": [\n        {\n            \"name\": \"Poison-Token\",\n            \"color\": \"#00ff00\"\n        },\n        {\n            \"name\": \"Bleeding-Token\",\n            \"color\": \"#ff0000\"\n        },\n        {\n            \"name\": \"Fire-Token\",\n            \"color\": \"#ff8000\"\n        }\n    ],\n    \"events\": [\n        {\n            \"event\": \"on_round_start\"\n        },\n        {\n            \"event\": \"on_attack\"\n        },\n        {\n            \"event\": \"on_mk_damage\"\n        },\n        {\n            \"event\": \"on_add_token\"\n        },\n        {\n            \"event\": \"on_add_special_token\"\n        },\n        {\n            \"event\": \"on_made_damage\"\n        }\n    ]\n}');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lib_equipment`
--

CREATE TABLE `lib_equipment` (
  `equipment_id` int(11) NOT NULL,
  `data` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `lib_equipment`
--

INSERT INTO `lib_equipment` (`equipment_id`, `data`) VALUES
(1, '[\n  {\n    \"equipment_name\": \"test\",\n    \"eq_type\": \"Dodge\",\n    \"description\": \"\",\n    \"armor_formula\": \"\",\n    \"magic_armor_formula\": \"\",\n    \"tier_lvl\": [\n      {\n        \"tier_lvl_name\": \"tier 1\",\n        \"volume\": \"\",\n        \"value_in_gold\": \"\",\n        \"durability\": \"\",\n        \"affects_cost\": [],\n        \"modifiers\": []\n      }\n    ]\n  }\n]');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lib_weapons`
--

CREATE TABLE `lib_weapons` (
  `weapon_id` int(11) NOT NULL,
  `data` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `lib_weapons`
--

INSERT INTO `lib_weapons` (`weapon_id`, `data`) VALUES
(1, '[]');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `shops`
--

CREATE TABLE `shops` (
  `data_id` int(5) NOT NULL,
  `data` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `shops`
--

INSERT INTO `shops` (`data_id`, `data`) VALUES
(1, '[]');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `timestamp`
--

CREATE TABLE `timestamp` (
  `id` int(5) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `timestamp`
--

INSERT INTO `timestamp` (`id`, `timestamp`) VALUES
(1, '2019-09-26 11:32:23');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(10) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `color`) VALUES
(3, 'Admin', '55b47074e9003c6b6ff051065cac9858', 'nightflyer@bluemail.ch', ''),
(4, 'Test', 'd136b6a143b2d4961d4f205721b50b70', 'nightflyer@bluemail.ch', '');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `campaign`
--
ALTER TABLE `campaign`
  ADD PRIMARY KEY (`camp_id`);

--
-- Indizes für die Tabelle `chars`
--
ALTER TABLE `chars`
  ADD PRIMARY KEY (`char_id`);

--
-- Indizes für die Tabelle `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `fields`
--
ALTER TABLE `fields`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `game`
--
ALTER TABLE `game`
  ADD PRIMARY KEY (`game_id`);

--
-- Indizes für die Tabelle `lib_actions`
--
ALTER TABLE `lib_actions`
  ADD PRIMARY KEY (`sorcery_id`);

--
-- Indizes für die Tabelle `lib_data`
--
ALTER TABLE `lib_data`
  ADD PRIMARY KEY (`data_id`);

--
-- Indizes für die Tabelle `lib_equipment`
--
ALTER TABLE `lib_equipment`
  ADD PRIMARY KEY (`equipment_id`);

--
-- Indizes für die Tabelle `lib_weapons`
--
ALTER TABLE `lib_weapons`
  ADD PRIMARY KEY (`weapon_id`);

--
-- Indizes für die Tabelle `shops`
--
ALTER TABLE `shops`
  ADD PRIMARY KEY (`data_id`);

--
-- Indizes für die Tabelle `timestamp`
--
ALTER TABLE `timestamp`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `campaign`
--
ALTER TABLE `campaign`
  MODIFY `camp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `chars`
--
ALTER TABLE `chars`
  MODIFY `char_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT für Tabelle `fields`
--
ALTER TABLE `fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `game`
--
ALTER TABLE `game`
  MODIFY `game_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `lib_actions`
--
ALTER TABLE `lib_actions`
  MODIFY `sorcery_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `lib_data`
--
ALTER TABLE `lib_data`
  MODIFY `data_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `lib_equipment`
--
ALTER TABLE `lib_equipment`
  MODIFY `equipment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `lib_weapons`
--
ALTER TABLE `lib_weapons`
  MODIFY `weapon_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `shops`
--
ALTER TABLE `shops`
  MODIFY `data_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `timestamp`
--
ALTER TABLE `timestamp`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

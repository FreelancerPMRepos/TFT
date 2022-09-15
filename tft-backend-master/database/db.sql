-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 08, 2020 at 12:32 PM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tft`
--

-- --------------------------------------------------------

--
-- Table structure for table `apps_countries`
--

CREATE TABLE `apps_countries` (
  `id` int(11) NOT NULL,
  `country_code` varchar(2) NOT NULL DEFAULT '',
  `country_name` varchar(100) NOT NULL DEFAULT '',
  `status` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `apps_countries`
--

INSERT INTO `apps_countries` (`id`, `country_code`, `country_name`, `status`) VALUES
(1, 'AF', 'Afghanistan', 0),
(2, 'AL', 'Albania', 0),
(3, 'DZ', 'Algeria', 0),
(4, 'DS', 'American Samoa', 0),
(5, 'AD', 'Andorra', 0),
(6, 'AO', 'Angola', 0),
(7, 'AI', 'Anguilla', 0),
(8, 'AQ', 'Antarctica', 0),
(9, 'AG', 'Antigua and Barbuda', 0),
(10, 'AR', 'Argentina', 0),
(11, 'AM', 'Armenia', 0),
(12, 'AW', 'Aruba', 0),
(13, 'AU', 'Australia', 0),
(14, 'AT', 'Austria', 0),
(15, 'AZ', 'Azerbaijan', 0),
(16, 'BS', 'Bahamas', 0),
(17, 'BH', 'Bahrain', 0),
(18, 'BD', 'Bangladesh', 0),
(19, 'BB', 'Barbados', 0),
(20, 'BY', 'Belarus', 0),
(21, 'BE', 'Belgium', 0),
(22, 'BZ', 'Belize', 0),
(23, 'BJ', 'Benin', 0),
(24, 'BM', 'Bermuda', 0),
(25, 'BT', 'Bhutan', 0),
(26, 'BO', 'Bolivia', 0),
(27, 'BA', 'Bosnia and Herzegovina', 0),
(28, 'BW', 'Botswana', 0),
(29, 'BV', 'Bouvet Island', 0),
(30, 'BR', 'Brazil', 0),
(31, 'IO', 'British Indian Ocean Territory', 0),
(32, 'BN', 'Brunei Darussalam', 0),
(33, 'BG', 'Bulgaria', 0),
(34, 'BF', 'Burkina Faso', 0),
(35, 'BI', 'Burundi', 0),
(36, 'KH', 'Cambodia', 0),
(37, 'CM', 'Cameroon', 0),
(38, 'CA', 'Canada', 0),
(39, 'CV', 'Cape Verde', 0),
(40, 'KY', 'Cayman Islands', 0),
(41, 'CF', 'Central African Republic', 0),
(42, 'TD', 'Chad', 0),
(43, 'CL', 'Chile', 0),
(44, 'CN', 'China', 0),
(45, 'CX', 'Christmas Island', 0),
(46, 'CC', 'Cocos (Keeling) Islands', 0),
(47, 'CO', 'Colombia', 0),
(48, 'KM', 'Comoros', 0),
(49, 'CG', 'Congo', 0),
(50, 'CK', 'Cook Islands', 0),
(51, 'CR', 'Costa Rica', 0),
(52, 'HR', 'Croatia (Hrvatska)', 0),
(53, 'CU', 'Cuba', 0),
(54, 'CY', 'Cyprus', 0),
(55, 'CZ', 'Czech Republic', 0),
(56, 'DK', 'Denmark', 0),
(57, 'DJ', 'Djibouti', 0),
(58, 'DM', 'Dominica', 0),
(59, 'DO', 'Dominican Republic', 0),
(60, 'TP', 'East Timor', 0),
(61, 'EC', 'Ecuador', 0),
(62, 'EG', 'Egypt', 0),
(63, 'SV', 'El Salvador', 0),
(64, 'GQ', 'Equatorial Guinea', 0),
(65, 'ER', 'Eritrea', 0),
(66, 'EE', 'Estonia', 0),
(67, 'ET', 'Ethiopia', 0),
(68, 'FK', 'Falkland Islands (Malvinas)', 0),
(69, 'FO', 'Faroe Islands', 0),
(70, 'FJ', 'Fiji', 0),
(71, 'FI', 'Finland', 0),
(72, 'FR', 'France', 0),
(73, 'FX', 'France, Metropolitan', 0),
(74, 'GF', 'French Guiana', 0),
(75, 'PF', 'French Polynesia', 0),
(76, 'TF', 'French Southern Territories', 0),
(77, 'GA', 'Gabon', 0),
(78, 'GM', 'Gambia', 0),
(79, 'GE', 'Georgia', 0),
(80, 'DE', 'Germany', 0),
(81, 'GH', 'Ghana', 0),
(82, 'GI', 'Gibraltar', 0),
(83, 'GK', 'Guernsey', 0),
(84, 'GR', 'Greece', 0),
(85, 'GL', 'Greenland', 0),
(86, 'GD', 'Grenada', 0),
(87, 'GP', 'Guadeloupe', 0),
(88, 'GU', 'Guam', 0),
(89, 'GT', 'Guatemala', 0),
(90, 'GN', 'Guinea', 0),
(91, 'GW', 'Guinea-Bissau', 0),
(92, 'GY', 'Guyana', 0),
(93, 'HT', 'Haiti', 0),
(94, 'HM', 'Heard and Mc Donald Islands', 0),
(95, 'HN', 'Honduras', 0),
(96, 'HK', 'Hong Kong', 0),
(97, 'HU', 'Hungary', 0),
(98, 'IS', 'Iceland', 0),
(99, 'IN', 'India', 0),
(100, 'IM', 'Isle of Man', 0),
(101, 'ID', 'Indonesia', 0),
(102, 'IR', 'Iran (Islamic Republic of)', 0),
(103, 'IQ', 'Iraq', 0),
(104, 'IE', 'Ireland', 0),
(105, 'IL', 'Israel', 0),
(106, 'IT', 'Italy', 0),
(107, 'CI', 'Ivory Coast', 0),
(108, 'JE', 'Jersey', 0),
(109, 'JM', 'Jamaica', 0),
(110, 'JP', 'Japan', 0),
(111, 'JO', 'Jordan', 0),
(112, 'KZ', 'Kazakhstan', 0),
(113, 'KE', 'Kenya', 0),
(114, 'KI', 'Kiribati', 0),
(115, 'KP', 'Korea, Democratic People\'s Republic of', 0),
(116, 'KR', 'Korea, Republic of', 0),
(117, 'XK', 'Kosovo', 0),
(118, 'KW', 'Kuwait', 0),
(119, 'KG', 'Kyrgyzstan', 0),
(120, 'LA', 'Lao People\'s Democratic Republic', 0),
(121, 'LV', 'Latvia', 0),
(122, 'LB', 'Lebanon', 0),
(123, 'LS', 'Lesotho', 0),
(124, 'LR', 'Liberia', 0),
(125, 'LY', 'Libyan Arab Jamahiriya', 0),
(126, 'LI', 'Liechtenstein', 0),
(127, 'LT', 'Lithuania', 0),
(128, 'LU', 'Luxembourg', 0),
(129, 'MO', 'Macau', 0),
(130, 'MK', 'Macedonia', 0),
(131, 'MG', 'Madagascar', 0),
(132, 'MW', 'Malawi', 0),
(133, 'MY', 'Malaysia', 0),
(134, 'MV', 'Maldives', 0),
(135, 'ML', 'Mali', 0),
(136, 'MT', 'Malta', 0),
(137, 'MH', 'Marshall Islands', 0),
(138, 'MQ', 'Martinique', 0),
(139, 'MR', 'Mauritania', 0),
(140, 'MU', 'Mauritius', 0),
(141, 'TY', 'Mayotte', 0),
(142, 'MX', 'Mexico', 0),
(143, 'FM', 'Micronesia, Federated States of', 0),
(144, 'MD', 'Moldova, Republic of', 0),
(145, 'MC', 'Monaco', 0),
(146, 'MN', 'Mongolia', 0),
(147, 'ME', 'Montenegro', 0),
(148, 'MS', 'Montserrat', 0),
(149, 'MA', 'Morocco', 0),
(150, 'MZ', 'Mozambique', 0),
(151, 'MM', 'Myanmar', 0),
(152, 'NA', 'Namibia', 0),
(153, 'NR', 'Nauru', 0),
(154, 'NP', 'Nepal', 0),
(155, 'NL', 'Netherlands', 0),
(156, 'AN', 'Netherlands Antilles', 0),
(157, 'NC', 'New Caledonia', 0),
(158, 'NZ', 'New Zealand', 0),
(159, 'NI', 'Nicaragua', 0),
(160, 'NE', 'Niger', 0),
(161, 'NG', 'Nigeria', 0),
(162, 'NU', 'Niue', 0),
(163, 'NF', 'Norfolk Island', 0),
(164, 'MP', 'Northern Mariana Islands', 0),
(165, 'NO', 'Norway', 0),
(166, 'OM', 'Oman', 0),
(167, 'PK', 'Pakistan', 0),
(168, 'PW', 'Palau', 0),
(169, 'PS', 'Palestine', 0),
(170, 'PA', 'Panama', 0),
(171, 'PG', 'Papua New Guinea', 0),
(172, 'PY', 'Paraguay', 0),
(173, 'PE', 'Peru', 0),
(174, 'PH', 'Philippines', 0),
(175, 'PN', 'Pitcairn', 0),
(176, 'PL', 'Poland', 0),
(177, 'PT', 'Portugal', 0),
(178, 'PR', 'Puerto Rico', 0),
(179, 'QA', 'Qatar', 0),
(180, 'RE', 'Reunion', 0),
(181, 'RO', 'Romania', 0),
(182, 'RU', 'Russian Federation', 0),
(183, 'RW', 'Rwanda', 0),
(184, 'KN', 'Saint Kitts and Nevis', 0),
(185, 'LC', 'Saint Lucia', 0),
(186, 'VC', 'Saint Vincent and the Grenadines', 0),
(187, 'WS', 'Samoa', 0),
(188, 'SM', 'San Marino', 0),
(189, 'ST', 'Sao Tome and Principe', 0),
(190, 'SA', 'Saudi Arabia', 0),
(191, 'SN', 'Senegal', 0),
(192, 'RS', 'Serbia', 0),
(193, 'SC', 'Seychelles', 0),
(194, 'SL', 'Sierra Leone', 0),
(195, 'SG', 'Singapore', 0),
(196, 'SK', 'Slovakia', 0),
(197, 'SI', 'Slovenia', 0),
(198, 'SB', 'Solomon Islands', 0),
(199, 'SO', 'Somalia', 0),
(200, 'ZA', 'South Africa', 0),
(201, 'GS', 'South Georgia South Sandwich Islands', 0),
(202, 'SS', 'South Sudan', 0),
(203, 'ES', 'Spain', 0),
(204, 'LK', 'Sri Lanka', 0),
(205, 'SH', 'St. Helena', 0),
(206, 'PM', 'St. Pierre and Miquelon', 0),
(207, 'SD', 'Sudan', 0),
(208, 'SR', 'Suriname', 0),
(209, 'SJ', 'Svalbard and Jan Mayen Islands', 0),
(210, 'SZ', 'Swaziland', 0),
(211, 'SE', 'Sweden', 0),
(212, 'CH', 'Switzerland', 0),
(213, 'SY', 'Syrian Arab Republic', 0),
(214, 'TW', 'Taiwan', 0),
(215, 'TJ', 'Tajikistan', 0),
(216, 'TZ', 'Tanzania, United Republic of', 0),
(217, 'TH', 'Thailand', 0),
(218, 'TG', 'Togo', 0),
(219, 'TK', 'Tokelau', 0),
(220, 'TO', 'Tonga', 0),
(221, 'TT', 'Trinidad and Tobago', 0),
(222, 'TN', 'Tunisia', 0),
(223, 'TR', 'Turkey', 0),
(224, 'TM', 'Turkmenistan', 0),
(225, 'TC', 'Turks and Caicos Islands', 0),
(226, 'TV', 'Tuvalu', 0),
(227, 'UG', 'Uganda', 0),
(228, 'UA', 'Ukraine', 0),
(229, 'AE', 'United Arab Emirates', 0),
(230, 'GB', 'United Kingdom', 0),
(231, 'US', 'United States', 1),
(232, 'UM', 'United States minor outlying islands', 0),
(233, 'UY', 'Uruguay', 0),
(234, 'UZ', 'Uzbekistan', 0),
(235, 'VU', 'Vanuatu', 0),
(236, 'VA', 'Vatican City State', 0),
(237, 'VE', 'Venezuela', 0),
(238, 'VN', 'Vietnam', 0),
(239, 'VG', 'Virgin Islands (British)', 0),
(240, 'VI', 'Virgin Islands (U.S.)', 0),
(241, 'WF', 'Wallis and Futuna Islands', 0),
(242, 'EH', 'Western Sahara', 0),
(243, 'YE', 'Yemen', 0),
(244, 'ZR', 'Zaire', 0),
(245, 'ZM', 'Zambia', 0),
(246, 'ZW', 'Zimbabwe', 0);

-- --------------------------------------------------------

--
-- Table structure for table `auth_assignment`
--

CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `auth_assignment`
--

INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
('admin', '1', 1563423223);

-- --------------------------------------------------------

--
-- Table structure for table `auth_item`
--

CREATE TABLE `auth_item` (
  `name` varchar(64) NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text DEFAULT NULL,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` blob DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `auth_item`
--

INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('admin', 1, 'Administrator', NULL, NULL, 1555478999, 1555478999),
('createPost', 2, 'Create a post', NULL, NULL, 1556344065, 1556344065),
('manageSettings', 2, 'Manage settings', NULL, NULL, 1555478999, 1555478999),
('manageStaffs', 2, 'Manage staffs', NULL, NULL, 1555478999, 1555478999),
('manageUsers', 2, 'Manage users', NULL, NULL, 1555478999, 1555478999),
('trainer', 1, 'trainer', NULL, NULL, 1555478999, 1555478999),
('updateOwnPost', 2, 'Update own post', 'isAuthor', NULL, 1556345985, 1556345985),
('updatePost', 2, 'Update Data', NULL, NULL, NULL, NULL),
('user', 1, 'User', NULL, NULL, 1555478999, 1555478999);

-- --------------------------------------------------------

--
-- Table structure for table `auth_item_child`
--

CREATE TABLE `auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `auth_item_child`
--

INSERT INTO `auth_item_child` (`parent`, `child`) VALUES
('admin', 'manageSettings'),
('admin', 'manageUsers'),
('user', 'createPost'),
('user', 'updateOwnPost');

-- --------------------------------------------------------

--
-- Table structure for table `auth_rule`
--

CREATE TABLE `auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` blob DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `auth_rule`
--

INSERT INTO `auth_rule` (`name`, `data`, `created_at`, `updated_at`) VALUES
('isAuthor', 0x4f3a31393a226170705c726261635c417574686f7252756c65223a333a7b733a343a226e616d65223b733a383a226973417574686f72223b733a393a22637265617465644174223b693a313535363334353938353b733a393a22757064617465644174223b693a313535363334353938353b7d, 1556345985, 1556345985);

-- --------------------------------------------------------

--
-- Table structure for table `backup`
--

CREATE TABLE `backup` (
  `id` int(11) NOT NULL,
  `timestamp` int(11) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `files` text DEFAULT NULL,
  `dump` text DEFAULT NULL,
  `size` bigint(20) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE `blog` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `app_sort_des` varchar(255) NOT NULL,
  `app_description` text NOT NULL,
  `date` int(20) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cms`
--

CREATE TABLE `cms` (
  `id` int(11) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `html_body` text NOT NULL COMMENT 'For Website',
  `app_body` text NOT NULL COMMENT 'For Mobile Application',
  `meta_tile` text DEFAULT NULL,
  `meta_keyword` text DEFAULT NULL,
  `meta_description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cms`
--

INSERT INTO `cms` (`id`, `slug`, `title`, `html_body`, `app_body`, `meta_tile`, `meta_keyword`, `meta_description`) VALUES
(1, 'privacy-policy', 'Privacy', '<p><a href=\"#\">Juan Sebasti&aacute;n Ver&oacute;n</a></p>  <p>Juan Sebasti&aacute;n Ver&oacute;n is a retired Argentine footballer and current chairman of Estudiantes de La Plata, where he had served as Director of Sports.</p>  <p>Ver&oacute;n was a talented, complete, influential, and versatile midfielder, who usually functioned as a playmaker; he was capable of playing both as an attacking midfielder, and in the centre, or even just in front of the defensive line, as a deep-lying playmaker, due to his ability to read the game, tackle, and dictate the tempo of his team&#39;s play or orchestrate his team&#39;s attacking moves from deeper positions with his passing after winning back the ball. He could also get forward and score goals, and often functioned in a free role in midfield. A strong, athletic, tenacious, hardworking, and physical player, in his prime, he was gifted with pace, good footwork, and excellent technical ability, as well as outstanding vision, creativity, and passing range, also possessing a powerful shot from distance with either foot.[5][39][40][41][42][43][44] He was also an accurate corner kick and set piece taker, known for his powerful, bending free-kicks with his right foot.[45][46][47]</p>', 'sss', NULL, NULL, NULL),
(2, 'terms-and-condition', 'Terms & Condition', '<p><a href=\"#\">Juan Sebasti&aacute;n Ver&oacute;n</a></p>  <p>Juan Sebasti&aacute;n Ver&oacute;n is a retired Argentine footballer and current chairman of Estudiantes de La Plata, where he had served as Director of Sports.</p>  <p>Ver&oacute;n was a talented, complete, influential, and versatile midfielder, who usually functioned as a playmaker; he was capable of playing both as an attacking midfielder, and in the centre, or even just in front of the defensive line, as a deep-lying playmaker, due to his ability to read the game, tackle, and dictate the tempo of his team&#39;s play or orchestrate his team&#39;s attacking moves from deeper positions with his passing after winning back the ball. He could also get forward and score goals, and often functioned in a free role in midfield. A strong, athletic, tenacious, hardworking, and physical player, in his prime, he was gifted with pace, good footwork, and excellent technical ability, as well as outstanding vision, creativity, and passing range, also possessing a powerful shot from distance with either foot.[5][39][40][41][42][43][44] He was also an accurate corner kick and set piece taker, known for his powerful, bending free-kicks with his right foot.[45][46][47]</p>', 'asddsdasda', NULL, NULL, NULL),
(3, 'help', 'Help', '<p><a href=\"#\">Juan Sebasti&aacute;n Ver&oacute;n</a></p>  <p>Juan Sebasti&aacute;n Ver&oacute;n is a retired Argentine footballer and current chairman of Estudiantes de La Plata, where he had served as Director of Sports.</p>  <p>Ver&oacute;n was a talented, complete, influential, and versatile midfielder, who usually functioned as a playmaker; he was capable of playing both as an attacking midfielder, and in the centre, or even just in front of the defensive line, as a deep-lying playmaker, due to his ability to read the game, tackle, and dictate the tempo of his team&#39;s play or orchestrate his team&#39;s attacking moves from deeper positions with his passing after winning back the ball. He could also get forward and score goals, and often functioned in a free role in midfield. A strong, athletic, tenacious, hardworking, and physical player, in his prime, he was gifted with pace, good footwork, and excellent technical ability, as well as outstanding vision, creativity, and passing range, also possessing a powerful shot from distance with either foot.[5][39][40][41][42][43][44] He was also an accurate corner kick and set piece taker, known for his powerful, bending free-kicks with his right foot.[45][46][47]</p>', ' dictate the tempo of his team&#39;s play or orchestrate his team&#39;s attacking moves from deeper positions with his passing after winning back the ball. He could also get forward and score goals, and often functioned in a free role in midfield. A strong, athletic, tenacious, hardworking, and physical player, in his p', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cron_schedule`
--

CREATE TABLE `cron_schedule` (
  `id` int(11) NOT NULL,
  `jobCode` varchar(255) DEFAULT NULL,
  `status` smallint(6) NOT NULL,
  `messages` text DEFAULT NULL,
  `dateCreated` timestamp NULL DEFAULT NULL,
  `dateFinished` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `email_template`
--

CREATE TABLE `email_template` (
  `id` int(11) NOT NULL,
  `emai_template_name` varchar(100) NOT NULL,
  `email_status` enum('active','deactive') DEFAULT 'active',
  `email_slug` varchar(100) NOT NULL,
  `email_content` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `email_template`
--

INSERT INTO `email_template` (`id`, `emai_template_name`, `email_status`, `email_slug`, `email_content`) VALUES
(1, 'Account Verification', 'active', 'verification', '<!--?php $this-&gt;head() ?-->\r\n<div class=\"welCome-main\" style=\"background-color: #eee; padding: 10px 0;\">\r\n<div style=\"min-width: 300px; height: auto; margin: 20px auto; background-color: #fff; max-width: 800px; display: table;\">\r\n<div class=\"header\" style=\"text-align: center; display: table; width: 100%; min-width: 300px;\">\r\n<div class=\"logo\" style=\"padding: 10px 10px 10px 10px; display: table-row; background: #eeeeee;\"><a style=\"display: table-cell;\" href=\"&lt;?=Url::base(true);?&gt;\">{{logo}}<br /><!-- <p><?= Yii::$app->name;?></p> --> </a></div>\r\n<div style=\"height: 4px; width: 100%; background: #000; /* old browsers */background: -moz-linear-gradient(top, #000 0%, #000 100%); /* ff3.6-15 */background: -webkit-linear-gradient(top, #000 0%,#000 100%); /* chrome10-25,safari5.1-6 */background: linear-gradient(to bottom, #E91E63 0%,#E91E63 100%); /* w3c, ie10+, ff16+, chrome26+, opera12+, safari7+ */filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#000\', endColorstr=\'#000\',GradientType=0 );\">&nbsp;</div>\r\n</div>\r\n<div>&nbsp; &nbsp; &nbsp;<br />&nbsp; &nbsp;<br />&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Hello {{name}},&nbsp;&nbsp;<br />&nbsp;\r\n<div>\r\n<div style=\"padding-left: 40px;\">Your <strong>{{app_name}} </strong>account verification code is <strong>{{code}}</strong>. Use this code to finish creating your account.&nbsp; This verification code will expire in 15 minutes.<br /><br />Sincerely,&nbsp;<br /><br />Your eDream Support Team<br />\r\n<h6 style=\"font-family: Roboto, sans-serif; font-size: 16px; letter-spacing: 0.5px; color: #484848; text-align: center;\">&nbsp;</h6>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>'),
(2, 'Password Reset Request', 'active', 'password-reset', '<!--?php $this-&gt;head() ?-->\r\n<div class=\"welCome-main\" style=\"background-color: #eee; padding: 10px 0;\">\r\n<div style=\"min-width: 300px; height: auto; margin: 20px auto; background-color: #fff; max-width: 800px; display: table;\">\r\n<div class=\"header\" style=\"text-align: center; display: table; width: 100%; min-width: 300px;\">\r\n<div class=\"logo\" style=\"padding: 10px 10px 10px 10px; display: table-row; background: #eeeeee;\"><a style=\"display: table-cell;\" href=\"&lt;?=Url::base(true);?&gt;\">{{logo}}<br /><!-- <p><?= Yii::$app->name;?></p> --> </a></div>\r\n<div style=\"height: 4px; width: 100%; background: #000; /* old browsers */background: -moz-linear-gradient(top, #000 0%, #000 100%); /* ff3.6-15 */background: -webkit-linear-gradient(top, #000 0%,#000 100%); /* chrome10-25,safari5.1-6 */background: linear-gradient(to bottom, #E91E63 0%,#E91E63 100%); /* w3c, ie10+, ff16+, chrome26+, opera12+, safari7+ */filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#000\', endColorstr=\'#000\',GradientType=0 );\">&nbsp;</div>\r\n</div>\r\n<div>&nbsp; &nbsp; &nbsp;<br />&nbsp; &nbsp;<br />&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Hello {{name}},&nbsp;&nbsp;<br /><br />&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;You have requested to reset your eDream login password. To reset your password, please follow&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br />&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;this link <a href=\"{{link}}\">Click Here </a>. <br /><br />&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Sincerely,&nbsp;<br />&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Your eDream Support Team<br /><br /><br /><br /></div>\r\n</div>\r\n</div>'),
(3, 'Email template for admin only', 'active', 'email_template_for_admin_only', '<!--?php $this-&gt;head() ?-->\r\n<div class=\"welCome-main\" style=\"background-color: #eee; padding: 10px 0;\">\r\n<div style=\"min-width: 300px; height: auto; margin: 20px auto; background-color: #fff; max-width: 800px; display: table;\">\r\n<div class=\"header\" style=\"text-align: center; display: table; width: 100%; min-width: 300px;\">\r\n<div class=\"logo\" style=\"padding: 10px 10px 10px 10px; display: table-row; background: #eeeeee;\"><a style=\"display: table-cell;\" href=\"&lt;?=Url::base(true);?&gt;\">{{logo}}<br /><!-- <p><?= Yii::$app->name;?></p> --> </a></div>\r\n<div style=\"height: 4px; width: 100%; background: #000; /* old browsers */background: -moz-linear-gradient(top, #000 0%, #000 100%); /* ff3.6-15 */background: -webkit-linear-gradient(top, #000 0%,#000 100%); /* chrome10-25,safari5.1-6 */background: linear-gradient(to bottom, #E91E63 0%,#E91E63 100%); /* w3c, ie10+, ff16+, chrome26+, opera12+, safari7+ */filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#000\', endColorstr=\'#000\',GradientType=0 );\">&nbsp;</div>\r\n</div>\r\n<div>&nbsp; &nbsp; &nbsp;<br />&nbsp; &nbsp;<br />&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Hello {{name}},&nbsp;&nbsp;<br />&nbsp;\r\n<div>\r\n<div style=\"padding-left: 40px;\">&nbsp; &nbsp;<span style=\"color: #bdc3c7;\">You can type your message here</span><br /><br />Sincerely,&nbsp;<br />Your eDream Support Team<br />\r\n<h6 style=\"font-family: Roboto, sans-serif; font-size: 16px; letter-spacing: 0.5px; color: #484848; text-align: center;\">&nbsp;</h6>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>');

-- --------------------------------------------------------

--
-- Table structure for table `exercises`
--

CREATE TABLE `exercises` (
  `id` int(11) NOT NULL,
  `exe_category_id` int(11) NOT NULL,
  `name` int(11) NOT NULL,
  `description` text NOT NULL DEFAULT 'NULL',
  `body_parts` text NOT NULL DEFAULT 'NULL',
  `steps` text NOT NULL DEFAULT 'NULL',
  `instructions` text NOT NULL DEFAULT 'NULL',
  `type` enum('Pull','Push','Pull / Push') NOT NULL DEFAULT 'Pull',
  `record_type` enum('Weight And Reps','Reps Only','Cardio','Time Only','Reps and Interval/Duration') NOT NULL,
  `source` varchar(255) DEFAULT NULL,
  `img` varchar(255) NOT NULL DEFAULT 'NULL',
  `gif` varchar(255) NOT NULL DEFAULT 'NULL',
  `is_active` int(1) NOT NULL DEFAULT 1,
  `created_at` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `exe_category`
--

CREATE TABLE `exe_category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `img` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `exe_category`
--

INSERT INTO `exe_category` (`id`, `name`, `img`) VALUES
(1, 'ABDOMINALS', NULL),
(2, 'BACK', NULL),
(3, 'BICEPS', NULL),
(4, 'CALVES', NULL),
(5, 'CHEST', NULL),
(6, 'FOREARMS', NULL),
(7, 'NECK', NULL),
(8, 'SHOULDERS', NULL),
(9, 'THIGHS', NULL),
(10, 'TRICEPS', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `in_app_purchase_plan`
--

CREATE TABLE `in_app_purchase_plan` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `value` float NOT NULL DEFAULT 0,
  `google_inapp_id` varchar(255) NOT NULL,
  `apple_inapp_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE `language` (
  `language_id` varchar(5) NOT NULL,
  `language` varchar(3) NOT NULL,
  `country` varchar(3) NOT NULL,
  `name` varchar(32) NOT NULL,
  `name_ascii` varchar(32) NOT NULL,
  `status` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`language_id`, `language`, `country`, `name`, `name_ascii`, `status`) VALUES
('af-ZA', 'af', 'za', 'Afrikaans', 'Afrikaans', 0),
('ar-AR', 'ar', 'ar', '‏العربية‏', 'Arabic', 0),
('az-AZ', 'az', 'az', 'Azərbaycan dili', 'Azerbaijani', 0),
('be-BY', 'be', 'by', 'Беларуская', 'Belarusian', 0),
('bg-BG', 'bg', 'bg', 'Български', 'Bulgarian', 0),
('bn-IN', 'bn', 'in', 'বাংলা', 'Bengali', 0),
('bs-BA', 'bs', 'ba', 'Bosanski', 'Bosnian', 0),
('ca-ES', 'ca', 'es', 'Català', 'Catalan', 0),
('cs-CZ', 'cs', 'cz', 'Čeština', 'Czech', 0),
('cy-GB', 'cy', 'gb', 'Cymraeg', 'Welsh', 0),
('da-DK', 'da', 'dk', 'Dansk', 'Danish', 0),
('de-DE', 'de', 'de', 'Deutsch', 'German', 0),
('el-GR', 'el', 'gr', 'Ελληνικά', 'Greek', 0),
('en-GB', 'en', 'gb', 'English (UK)', 'English (UK)', 0),
('en-PI', 'en', 'pi', 'English (Pirate)', 'English (Pirate)', 0),
('en-UD', 'en', 'ud', 'English (Upside Down)', 'English (Upside Down)', 0),
('en-US', 'en', 'us', 'English (US)', 'English (US)', 1),
('eo-EO', 'eo', 'eo', 'Esperanto', 'Esperanto', 0),
('es-ES', 'es', 'es', 'Español (España)', 'Spanish (Spain)', 0),
('es-LA', 'es', 'la', 'Español', 'Spanish', 0),
('et-EE', 'et', 'ee', 'Eesti', 'Estonian', 0),
('eu-ES', 'eu', 'es', 'Euskara', 'Basque', 0),
('fa-IR', 'fa', 'ir', '‏فارسی‏', 'Persian', 0),
('fb-LT', 'fb', 'lt', 'Leet Speak', 'Leet Speak', 0),
('fi-FI', 'fi', 'fi', 'Suomi', 'Finnish', 0),
('fo-FO', 'fo', 'fo', 'Føroyskt', 'Faroese', 0),
('fr-CA', 'fr', 'ca', 'Français (Canada)', 'French (Canada)', 0),
('fr-FR', 'fr', 'fr', 'Français (France)', 'French (France)', 0),
('fy-NL', 'fy', 'nl', 'Frysk', 'Frisian', 0),
('ga-IE', 'ga', 'ie', 'Gaeilge', 'Irish', 0),
('gl-ES', 'gl', 'es', 'Galego', 'Galician', 0),
('he-IL', 'he', 'il', '‏עברית‏', 'Hebrew', 0),
('hi-IN', 'hi', 'in', 'हिन्दी', 'Hindi', 0),
('hr-HR', 'hr', 'hr', 'Hrvatski', 'Croatian', 0),
('hu-HU', 'hu', 'hu', 'Magyar', 'Hungarian', 0),
('hy-AM', 'hy', 'am', 'Հայերեն', 'Armenian', 0),
('id-ID', 'id', 'id', 'Bahasa Indonesia', 'Indonesian', 0),
('is-IS', 'is', 'is', 'Íslenska', 'Icelandic', 0),
('it-IT', 'it', 'it', 'Italiano', 'Italian', 0),
('ja-JP', 'ja', 'jp', '日本語', 'Japanese', 0),
('ka-GE', 'ka', 'ge', 'ქართული', 'Georgian', 0),
('km-KH', 'km', 'kh', 'ភាសាខ្មែរ', 'Khmer', 0),
('ko-KR', 'ko', 'kr', '한국어', 'Korean', 0),
('ku-TR', 'ku', 'tr', 'Kurdî', 'Kurdish', 0),
('la-VA', 'la', 'va', 'lingua latina', 'Latin', 0),
('lt-LT', 'lt', 'lt', 'Lietuvių', 'Lithuanian', 0),
('lv-LV', 'lv', 'lv', 'Latviešu', 'Latvian', 0),
('mk-MK', 'mk', 'mk', 'Македонски', 'Macedonian', 0),
('ml-IN', 'ml', 'in', 'മലയാളം', 'Malayalam', 0),
('ms-MY', 'ms', 'my', 'Bahasa Melayu', 'Malay', 0),
('nb-NO', 'nb', 'no', 'Norsk (bokmål)', 'Norwegian (bokmal)', 0),
('ne-NP', 'ne', 'np', 'नेपाली', 'Nepali', 0),
('nl-NL', 'nl', 'nl', 'Nederlands', 'Dutch', 0),
('nn-NO', 'nn', 'no', 'Norsk (nynorsk)', 'Norwegian (nynorsk)', 0),
('pa-IN', 'pa', 'in', 'ਪੰਜਾਬੀ', 'Punjabi', 0),
('pl-PL', 'pl', 'pl', 'Polski', 'Polish', 0),
('ps-AF', 'ps', 'af', '‏پښتو‏', 'Pashto', 0),
('pt-BR', 'pt', 'br', 'Português (Brasil)', 'Portuguese (Brazil)', 0),
('pt-PT', 'pt', 'pt', 'Português (Portugal)', 'Portuguese (Portugal)', 0),
('ro-RO', 'ro', 'ro', 'Română', 'Romanian', 0),
('ru-RU', 'ru', 'ru', 'Русский', 'Russian', 0),
('sk-SK', 'sk', 'sk', 'Slovenčina', 'Slovak', 0),
('sl-SI', 'sl', 'si', 'Slovenščina', 'Slovenian', 0),
('sq-AL', 'sq', 'al', 'Shqip', 'Albanian', 0),
('sr-RS', 'sr', 'rs', 'Српски', 'Serbian', 0),
('sv-SE', 'sv', 'se', 'Svenska', 'Swedish', 0),
('sw-KE', 'sw', 'ke', 'Kiswahili', 'Swahili', 0),
('ta-IN', 'ta', 'in', 'தமிழ்', 'Tamil', 0),
('te-IN', 'te', 'in', 'తెలుగు', 'Telugu', 0),
('th-TH', 'th', 'th', 'ภาษาไทย', 'Thai', 0),
('tl-PH', 'tl', 'ph', 'Filipino', 'Filipino', 0),
('tr-TR', 'tr', 'tr', 'Türkçe', 'Turkish', 0),
('uk-UA', 'uk', 'ua', 'Українська', 'Ukrainian', 0),
('vi-VN', 'vi', 'vn', 'Tiếng Việt', 'Vietnamese', 0),
('xx-XX', 'xx', 'xx', 'Fejlesztő', 'Developer', 0),
('zh-CN', 'zh', 'cn', '中文(简体)', 'Simplified Chinese (China)', 0),
('zh-HK', 'zh', 'hk', '中文(香港)', 'Traditional Chinese (Hong Kong)', 0),
('zh-TW', 'zh', 'tw', '中文(台灣)', 'Traditional Chinese (Taiwan)', 0);

-- --------------------------------------------------------

--
-- Table structure for table `language_source`
--

CREATE TABLE `language_source` (
  `id` int(11) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `language_translate`
--

CREATE TABLE `language_translate` (
  `id` int(11) NOT NULL,
  `language` varchar(5) NOT NULL,
  `translation` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `id` bigint(20) NOT NULL,
  `level` int(11) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `log_time` double DEFAULT NULL,
  `prefix` text DEFAULT NULL,
  `message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `log`
--

INSERT INTO `log` (`id`, `level`, `category`, `log_time`, `prefix`, `message`) VALUES
(1, 1, 'yii\\db\\Exception', 1594203081.7866, '[::1][1][439ecfbfc485d6b078828408d3fc07fa]', 'PDOException: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'u_type\' in \'where clause\' in /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php:1290\nStack trace:\n#0 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php(1290): PDOStatement->execute()\n#1 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php(1158): yii\\db\\Command->internalExecute(\'SELECT COUNT(*)...\')\n#2 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php(425): yii\\db\\Command->queryInternal(\'fetchColumn\', 0)\n#3 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Query.php(463): yii\\db\\Command->queryScalar()\n#4 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/ActiveQuery.php(352): yii\\db\\Query->queryScalar(\'COUNT(*)\', Object(yii\\db\\Connection))\n#5 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Query.php(347): yii\\db\\ActiveQuery->queryScalar(\'COUNT(*)\', Object(yii\\db\\Connection))\n#6 /opt/lampp/htdocs/tft/backend/controllers/DashboardController.php(21): yii\\db\\Query->count()\n#7 [internal function]: backend\\controllers\\DashboardController->actionIndex()\n#8 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/InlineAction.php(57): call_user_func_array(Array, Array)\n#9 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Controller.php(157): yii\\base\\InlineAction->runWithParams(Array)\n#10 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Module.php(528): yii\\base\\Controller->runAction(\'index\', Array)\n#11 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/web/Application.php(103): yii\\base\\Module->runAction(\'dashboard/index\', Array)\n#12 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Application.php(386): yii\\web\\Application->handleRequest(Object(common\\components\\Request))\n#13 /opt/lampp/htdocs/tft/backend/web/index.php(21): yii\\base\\Application->run()\n#14 {main}\n\nNext yii\\db\\Exception: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'u_type\' in \'where clause\'\nThe SQL being executed was: SELECT COUNT(*) FROM `user_additional_info` WHERE `u_type`=3 in /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Schema.php:664\nStack trace:\n#0 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php(1295): yii\\db\\Schema->convertException(Object(PDOException), \'SELECT COUNT(*)...\')\n#1 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php(1158): yii\\db\\Command->internalExecute(\'SELECT COUNT(*)...\')\n#2 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php(425): yii\\db\\Command->queryInternal(\'fetchColumn\', 0)\n#3 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Query.php(463): yii\\db\\Command->queryScalar()\n#4 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/ActiveQuery.php(352): yii\\db\\Query->queryScalar(\'COUNT(*)\', Object(yii\\db\\Connection))\n#5 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Query.php(347): yii\\db\\ActiveQuery->queryScalar(\'COUNT(*)\', Object(yii\\db\\Connection))\n#6 /opt/lampp/htdocs/tft/backend/controllers/DashboardController.php(21): yii\\db\\Query->count()\n#7 [internal function]: backend\\controllers\\DashboardController->actionIndex()\n#8 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/InlineAction.php(57): call_user_func_array(Array, Array)\n#9 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Controller.php(157): yii\\base\\InlineAction->runWithParams(Array)\n#10 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Module.php(528): yii\\base\\Controller->runAction(\'index\', Array)\n#11 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/web/Application.php(103): yii\\base\\Module->runAction(\'dashboard/index\', Array)\n#12 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Application.php(386): yii\\web\\Application->handleRequest(Object(common\\components\\Request))\n#13 /opt/lampp/htdocs/tft/backend/web/index.php(21): yii\\base\\Application->run()\n#14 {main}\nAdditional Information:\nArray\n(\n    [0] => 42S22\n    [1] => 1054\n    [2] => Unknown column \'u_type\' in \'where clause\'\n)\n'),
(2, 4, 'application', 1594203081.7197, '[::1][1][439ecfbfc485d6b078828408d3fc07fa]', '$_GET = []\n\n$_POST = []\n\n$_FILES = []\n\n$_COOKIE = [\n    \'_ga\' => \'GA1.1.523175945.1582203500\'\n    \'sessionToken\' => \'r:ae8eb74535f2a6a5028c3847ecb9c458\'\n    \'auto_saved_sql_sort\' => \'\'\n    \'advanced-frontend\' => \'3d82a1bece8484ab403350173c0e6803\'\n    \'sails_sid\' => \'s:Z5r9AHtHlcRCwrfVJN3rqHNBEHptrO-P.mHmxyR7GTbPIn/D+lsxYNrVRXTZT7j266s+/HSCjAQI\'\n    \'PHPSESSID\' => \'9df45c5c5256be57fd85473ee23b1886\'\n    \'simcify\' => \'40085ca3e5a83fa68985286bbed8ae45\'\n    \'pma_lang\' => \'en\'\n    \'phpMyAdmin\' => \'c75fc7a59b08849d8c23cfce0b97372b\'\n    \'pmaUser-1\' => \'{\\\"iv\\\":\\\"Pv2pAuHyQeVRCdwVu\\\\/umvg==\\\",\\\"mac\\\":\\\"e2a28ae56b4ba54c5646abc9f6e7f1b1f3dc4bda\\\",\\\"payload\\\":\\\"Pb4TkV3TMx2rzGBI\\\\/C5eQw==\\\"}\'\n    \'pmaAuth-1\' => \'{\\\"iv\\\":\\\"NpOeRN+WNnbwmRvcv8HDaA==\\\",\\\"mac\\\":\\\"24a13da4bbc72f3df801c0520c1fc5fd255bcbe1\\\",\\\"payload\\\":\\\"wRXN\\\\/MkY8hz5OeE6TNWl2dDDDzHNQV3jY70aND0rqrs=\\\"}\'\n    \'advanced-backend\' => \'439ecfbfc485d6b078828408d3fc07fa\'\n    \'_identity-backend\' => \'b85026bee3f3c21aed982ceeddca72547278c857cff0df45b461409e71c06dc1a:2:{i:0;s:17:\\\"_identity-backend\\\";i:1;s:16:\\\"[1,null,2592000]\\\";}\'\n    \'_csrf\' => \'6fe325ee80169161f274aba6fe21c022d97d9fdd45a837fdde94984077d81ac7a:2:{i:0;s:5:\\\"_csrf\\\";i:1;s:32:\\\"MQgG_C933nLrHf9WZlwF83aB1Nzn1OsV\\\";}\'\n]\n\n$_SESSION = [\n    \'__flash\' => []\n    \'frontendTranslation_EnableTranslate\' => true\n    \'__returnUrl\' => \'/tft/administration\'\n    \'__id\' => 1\n]\n\n$_SERVER = [\n    \'REDIRECT_UNIQUE_ID\' => \'XwWbye998eqFXxQPhwZG6wAAAAk\'\n    \'REDIRECT_STATUS\' => \'200\'\n    \'UNIQUE_ID\' => \'XwWbye998eqFXxQPhwZG6wAAAAk\'\n    \'HTTP_HOST\' => \'localhost\'\n    \'HTTP_CONNECTION\' => \'keep-alive\'\n    \'HTTP_CACHE_CONTROL\' => \'max-age=0\'\n    \'HTTP_UPGRADE_INSECURE_REQUESTS\' => \'1\'\n    \'HTTP_USER_AGENT\' => \'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.117 Safari/537.36\'\n    \'HTTP_SEC_FETCH_USER\' => \'?1\'\n    \'HTTP_ACCEPT\' => \'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9\'\n    \'HTTP_SEC_FETCH_SITE\' => \'same-origin\'\n    \'HTTP_SEC_FETCH_MODE\' => \'navigate\'\n    \'HTTP_REFERER\' => \'http://localhost/tft/administration/site/login\'\n    \'HTTP_ACCEPT_ENCODING\' => \'gzip, deflate, br\'\n    \'HTTP_ACCEPT_LANGUAGE\' => \'en-US,en;q=0.9\'\n    \'HTTP_COOKIE\' => \'_ga=GA1.1.523175945.1582203500; sessionToken=r%3Aae8eb74535f2a6a5028c3847ecb9c458; auto_saved_sql_sort=; advanced-frontend=3d82a1bece8484ab403350173c0e6803; sails.sid=s%3AZ5r9AHtHlcRCwrfVJN3rqHNBEHptrO-P.mHmxyR7GTbPIn%2FD%2BlsxYNrVRXTZT7j266s%2B%2FHSCjAQI; ; PHPSESSID=9df45c5c5256be57fd85473ee23b1886; simcify=40085ca3e5a83fa68985286bbed8ae45; pma_lang=en; phpMyAdmin=c75fc7a59b08849d8c23cfce0b97372b; pmaUser-1=%7B%22iv%22%3A%22Pv2pAuHyQeVRCdwVu%5C%2Fumvg%3D%3D%22%2C%22mac%22%3A%22e2a28ae56b4ba54c5646abc9f6e7f1b1f3dc4bda%22%2C%22payload%22%3A%22Pb4TkV3TMx2rzGBI%5C%2FC5eQw%3D%3D%22%7D; pmaAuth-1=%7B%22iv%22%3A%22NpOeRN%2BWNnbwmRvcv8HDaA%3D%3D%22%2C%22mac%22%3A%2224a13da4bbc72f3df801c0520c1fc5fd255bcbe1%22%2C%22payload%22%3A%22wRXN%5C%2FMkY8hz5OeE6TNWl2dDDDzHNQV3jY70aND0rqrs%3D%22%7D; advanced-backend=439ecfbfc485d6b078828408d3fc07fa; _identity-backend=b85026bee3f3c21aed982ceeddca72547278c857cff0df45b461409e71c06dc1a%3A2%3A%7Bi%3A0%3Bs%3A17%3A%22_identity-backend%22%3Bi%3A1%3Bs%3A16%3A%22%5B1%2Cnull%2C2592000%5D%22%3B%7D; _csrf=6fe325ee80169161f274aba6fe21c022d97d9fdd45a837fdde94984077d81ac7a%3A2%3A%7Bi%3A0%3Bs%3A5%3A%22_csrf%22%3Bi%3A1%3Bs%3A32%3A%22MQgG_C933nLrHf9WZlwF83aB1Nzn1OsV%22%3B%7D\'\n    \'PATH\' => \'/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/snap/bin\'\n    \'LD_LIBRARY_PATH\' => \'/opt/lampp/lib:/opt/lampp/lib\'\n    \'SERVER_SIGNATURE\' => \'\'\n    \'SERVER_SOFTWARE\' => \'Apache/2.4.41 (Unix) OpenSSL/1.1.1d PHP/7.1.33 mod_perl/2.0.8-dev Perl/v5.16.3\'\n    \'SERVER_NAME\' => \'localhost\'\n    \'SERVER_ADDR\' => \'::1\'\n    \'SERVER_PORT\' => \'80\'\n    \'REMOTE_ADDR\' => \'::1\'\n    \'DOCUMENT_ROOT\' => \'/opt/lampp/htdocs\'\n    \'REQUEST_SCHEME\' => \'http\'\n    \'CONTEXT_PREFIX\' => \'\'\n    \'CONTEXT_DOCUMENT_ROOT\' => \'/opt/lampp/htdocs\'\n    \'SERVER_ADMIN\' => \'you@example.com\'\n    \'SCRIPT_FILENAME\' => \'/opt/lampp/htdocs/tft/backend/web/index.php\'\n    \'REMOTE_PORT\' => \'41182\'\n    \'REDIRECT_URL\' => \'/tft/administration/dashboard/index\'\n    \'GATEWAY_INTERFACE\' => \'CGI/1.1\'\n    \'SERVER_PROTOCOL\' => \'HTTP/1.1\'\n    \'REQUEST_METHOD\' => \'GET\'\n    \'QUERY_STRING\' => \'\'\n    \'REQUEST_URI\' => \'/tft/administration/dashboard/index\'\n    \'SCRIPT_NAME\' => \'/tft/backend/web/index.php\'\n    \'PHP_SELF\' => \'/tft/backend/web/index.php\'\n    \'REQUEST_TIME_FLOAT\' => 1594203081.712\n    \'REQUEST_TIME\' => 1594203081\n]'),
(3, 1, 'yii\\db\\Exception', 1594203110.2463, '[::1][1][439ecfbfc485d6b078828408d3fc07fa]', 'PDOException: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'u_type\' in \'where clause\' in /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php:1290\nStack trace:\n#0 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php(1290): PDOStatement->execute()\n#1 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php(1158): yii\\db\\Command->internalExecute(\'SELECT COUNT(*)...\')\n#2 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php(425): yii\\db\\Command->queryInternal(\'fetchColumn\', 0)\n#3 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Query.php(463): yii\\db\\Command->queryScalar()\n#4 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/ActiveQuery.php(352): yii\\db\\Query->queryScalar(\'COUNT(*)\', Object(yii\\db\\Connection))\n#5 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Query.php(347): yii\\db\\ActiveQuery->queryScalar(\'COUNT(*)\', Object(yii\\db\\Connection))\n#6 /opt/lampp/htdocs/tft/backend/controllers/DashboardController.php(21): yii\\db\\Query->count()\n#7 [internal function]: backend\\controllers\\DashboardController->actionIndex()\n#8 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/InlineAction.php(57): call_user_func_array(Array, Array)\n#9 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Controller.php(157): yii\\base\\InlineAction->runWithParams(Array)\n#10 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Module.php(528): yii\\base\\Controller->runAction(\'index\', Array)\n#11 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/web/Application.php(103): yii\\base\\Module->runAction(\'dashboard/index\', Array)\n#12 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Application.php(386): yii\\web\\Application->handleRequest(Object(common\\components\\Request))\n#13 /opt/lampp/htdocs/tft/backend/web/index.php(21): yii\\base\\Application->run()\n#14 {main}\n\nNext yii\\db\\Exception: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'u_type\' in \'where clause\'\nThe SQL being executed was: SELECT COUNT(*) FROM `user_additional_info` WHERE `u_type`=3 in /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Schema.php:664\nStack trace:\n#0 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php(1295): yii\\db\\Schema->convertException(Object(PDOException), \'SELECT COUNT(*)...\')\n#1 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php(1158): yii\\db\\Command->internalExecute(\'SELECT COUNT(*)...\')\n#2 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php(425): yii\\db\\Command->queryInternal(\'fetchColumn\', 0)\n#3 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Query.php(463): yii\\db\\Command->queryScalar()\n#4 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/ActiveQuery.php(352): yii\\db\\Query->queryScalar(\'COUNT(*)\', Object(yii\\db\\Connection))\n#5 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Query.php(347): yii\\db\\ActiveQuery->queryScalar(\'COUNT(*)\', Object(yii\\db\\Connection))\n#6 /opt/lampp/htdocs/tft/backend/controllers/DashboardController.php(21): yii\\db\\Query->count()\n#7 [internal function]: backend\\controllers\\DashboardController->actionIndex()\n#8 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/InlineAction.php(57): call_user_func_array(Array, Array)\n#9 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Controller.php(157): yii\\base\\InlineAction->runWithParams(Array)\n#10 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Module.php(528): yii\\base\\Controller->runAction(\'index\', Array)\n#11 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/web/Application.php(103): yii\\base\\Module->runAction(\'dashboard/index\', Array)\n#12 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Application.php(386): yii\\web\\Application->handleRequest(Object(common\\components\\Request))\n#13 /opt/lampp/htdocs/tft/backend/web/index.php(21): yii\\base\\Application->run()\n#14 {main}\nAdditional Information:\nArray\n(\n    [0] => 42S22\n    [1] => 1054\n    [2] => Unknown column \'u_type\' in \'where clause\'\n)\n'),
(4, 4, 'application', 1594203110.2186, '[::1][1][439ecfbfc485d6b078828408d3fc07fa]', '$_GET = []\n\n$_POST = []\n\n$_FILES = []\n\n$_COOKIE = [\n    \'_ga\' => \'GA1.1.523175945.1582203500\'\n    \'sessionToken\' => \'r:ae8eb74535f2a6a5028c3847ecb9c458\'\n    \'auto_saved_sql_sort\' => \'\'\n    \'advanced-frontend\' => \'3d82a1bece8484ab403350173c0e6803\'\n    \'sails_sid\' => \'s:Z5r9AHtHlcRCwrfVJN3rqHNBEHptrO-P.mHmxyR7GTbPIn/D+lsxYNrVRXTZT7j266s+/HSCjAQI\'\n    \'PHPSESSID\' => \'9df45c5c5256be57fd85473ee23b1886\'\n    \'simcify\' => \'40085ca3e5a83fa68985286bbed8ae45\'\n    \'pma_lang\' => \'en\'\n    \'phpMyAdmin\' => \'c75fc7a59b08849d8c23cfce0b97372b\'\n    \'pmaUser-1\' => \'{\\\"iv\\\":\\\"Pv2pAuHyQeVRCdwVu\\\\/umvg==\\\",\\\"mac\\\":\\\"e2a28ae56b4ba54c5646abc9f6e7f1b1f3dc4bda\\\",\\\"payload\\\":\\\"Pb4TkV3TMx2rzGBI\\\\/C5eQw==\\\"}\'\n    \'pmaAuth-1\' => \'{\\\"iv\\\":\\\"NpOeRN+WNnbwmRvcv8HDaA==\\\",\\\"mac\\\":\\\"24a13da4bbc72f3df801c0520c1fc5fd255bcbe1\\\",\\\"payload\\\":\\\"wRXN\\\\/MkY8hz5OeE6TNWl2dDDDzHNQV3jY70aND0rqrs=\\\"}\'\n    \'advanced-backend\' => \'439ecfbfc485d6b078828408d3fc07fa\'\n    \'_identity-backend\' => \'b85026bee3f3c21aed982ceeddca72547278c857cff0df45b461409e71c06dc1a:2:{i:0;s:17:\\\"_identity-backend\\\";i:1;s:16:\\\"[1,null,2592000]\\\";}\'\n    \'_csrf\' => \'6fe325ee80169161f274aba6fe21c022d97d9fdd45a837fdde94984077d81ac7a:2:{i:0;s:5:\\\"_csrf\\\";i:1;s:32:\\\"MQgG_C933nLrHf9WZlwF83aB1Nzn1OsV\\\";}\'\n]\n\n$_SESSION = [\n    \'__flash\' => []\n    \'frontendTranslation_EnableTranslate\' => true\n    \'__returnUrl\' => \'/tft/administration\'\n    \'__id\' => 1\n]\n\n$_SERVER = [\n    \'REDIRECT_UNIQUE_ID\' => \'XwWb5jkAkiRWnK1lXYPg6gAAAAQ\'\n    \'REDIRECT_STATUS\' => \'200\'\n    \'UNIQUE_ID\' => \'XwWb5jkAkiRWnK1lXYPg6gAAAAQ\'\n    \'HTTP_HOST\' => \'localhost\'\n    \'HTTP_CONNECTION\' => \'keep-alive\'\n    \'HTTP_PRAGMA\' => \'no-cache\'\n    \'HTTP_CACHE_CONTROL\' => \'no-cache\'\n    \'HTTP_UPGRADE_INSECURE_REQUESTS\' => \'1\'\n    \'HTTP_USER_AGENT\' => \'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.117 Safari/537.36\'\n    \'HTTP_SEC_FETCH_USER\' => \'?1\'\n    \'HTTP_ACCEPT\' => \'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9\'\n    \'HTTP_SEC_FETCH_SITE\' => \'same-origin\'\n    \'HTTP_SEC_FETCH_MODE\' => \'navigate\'\n    \'HTTP_REFERER\' => \'http://localhost/tft/administration/site/login\'\n    \'HTTP_ACCEPT_ENCODING\' => \'gzip, deflate, br\'\n    \'HTTP_ACCEPT_LANGUAGE\' => \'en-US,en;q=0.9\'\n    \'HTTP_COOKIE\' => \'_ga=GA1.1.523175945.1582203500; sessionToken=r%3Aae8eb74535f2a6a5028c3847ecb9c458; auto_saved_sql_sort=; advanced-frontend=3d82a1bece8484ab403350173c0e6803; sails.sid=s%3AZ5r9AHtHlcRCwrfVJN3rqHNBEHptrO-P.mHmxyR7GTbPIn%2FD%2BlsxYNrVRXTZT7j266s%2B%2FHSCjAQI; ; PHPSESSID=9df45c5c5256be57fd85473ee23b1886; simcify=40085ca3e5a83fa68985286bbed8ae45; pma_lang=en; phpMyAdmin=c75fc7a59b08849d8c23cfce0b97372b; pmaUser-1=%7B%22iv%22%3A%22Pv2pAuHyQeVRCdwVu%5C%2Fumvg%3D%3D%22%2C%22mac%22%3A%22e2a28ae56b4ba54c5646abc9f6e7f1b1f3dc4bda%22%2C%22payload%22%3A%22Pb4TkV3TMx2rzGBI%5C%2FC5eQw%3D%3D%22%7D; pmaAuth-1=%7B%22iv%22%3A%22NpOeRN%2BWNnbwmRvcv8HDaA%3D%3D%22%2C%22mac%22%3A%2224a13da4bbc72f3df801c0520c1fc5fd255bcbe1%22%2C%22payload%22%3A%22wRXN%5C%2FMkY8hz5OeE6TNWl2dDDDzHNQV3jY70aND0rqrs%3D%22%7D; advanced-backend=439ecfbfc485d6b078828408d3fc07fa; _identity-backend=b85026bee3f3c21aed982ceeddca72547278c857cff0df45b461409e71c06dc1a%3A2%3A%7Bi%3A0%3Bs%3A17%3A%22_identity-backend%22%3Bi%3A1%3Bs%3A16%3A%22%5B1%2Cnull%2C2592000%5D%22%3B%7D; _csrf=6fe325ee80169161f274aba6fe21c022d97d9fdd45a837fdde94984077d81ac7a%3A2%3A%7Bi%3A0%3Bs%3A5%3A%22_csrf%22%3Bi%3A1%3Bs%3A32%3A%22MQgG_C933nLrHf9WZlwF83aB1Nzn1OsV%22%3B%7D\'\n    \'PATH\' => \'/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/snap/bin\'\n    \'LD_LIBRARY_PATH\' => \'/opt/lampp/lib:/opt/lampp/lib\'\n    \'SERVER_SIGNATURE\' => \'\'\n    \'SERVER_SOFTWARE\' => \'Apache/2.4.41 (Unix) OpenSSL/1.1.1d PHP/7.1.33 mod_perl/2.0.8-dev Perl/v5.16.3\'\n    \'SERVER_NAME\' => \'localhost\'\n    \'SERVER_ADDR\' => \'::1\'\n    \'SERVER_PORT\' => \'80\'\n    \'REMOTE_ADDR\' => \'::1\'\n    \'DOCUMENT_ROOT\' => \'/opt/lampp/htdocs\'\n    \'REQUEST_SCHEME\' => \'http\'\n    \'CONTEXT_PREFIX\' => \'\'\n    \'CONTEXT_DOCUMENT_ROOT\' => \'/opt/lampp/htdocs\'\n    \'SERVER_ADMIN\' => \'you@example.com\'\n    \'SCRIPT_FILENAME\' => \'/opt/lampp/htdocs/tft/backend/web/index.php\'\n    \'REMOTE_PORT\' => \'41202\'\n    \'REDIRECT_URL\' => \'/tft/administration/dashboard/index\'\n    \'GATEWAY_INTERFACE\' => \'CGI/1.1\'\n    \'SERVER_PROTOCOL\' => \'HTTP/1.1\'\n    \'REQUEST_METHOD\' => \'GET\'\n    \'QUERY_STRING\' => \'\'\n    \'REQUEST_URI\' => \'/tft/administration/dashboard/index\'\n    \'SCRIPT_NAME\' => \'/tft/backend/web/index.php\'\n    \'PHP_SELF\' => \'/tft/backend/web/index.php\'\n    \'REQUEST_TIME_FLOAT\' => 1594203110.211\n    \'REQUEST_TIME\' => 1594203110\n]'),
(5, 1, 'yii\\db\\Exception', 1594203127.3052, '[::1][1][439ecfbfc485d6b078828408d3fc07fa]', 'PDOException: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'u_type\' in \'where clause\' in /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php:1290\nStack trace:\n#0 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php(1290): PDOStatement->execute()\n#1 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php(1158): yii\\db\\Command->internalExecute(\'SELECT COUNT(*)...\')\n#2 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php(425): yii\\db\\Command->queryInternal(\'fetchColumn\', 0)\n#3 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Query.php(463): yii\\db\\Command->queryScalar()\n#4 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/ActiveQuery.php(352): yii\\db\\Query->queryScalar(\'COUNT(*)\', Object(yii\\db\\Connection))\n#5 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Query.php(347): yii\\db\\ActiveQuery->queryScalar(\'COUNT(*)\', Object(yii\\db\\Connection))\n#6 /opt/lampp/htdocs/tft/backend/controllers/DashboardController.php(21): yii\\db\\Query->count()\n#7 [internal function]: backend\\controllers\\DashboardController->actionIndex()\n#8 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/InlineAction.php(57): call_user_func_array(Array, Array)\n#9 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Controller.php(157): yii\\base\\InlineAction->runWithParams(Array)\n#10 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Module.php(528): yii\\base\\Controller->runAction(\'index\', Array)\n#11 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/web/Application.php(103): yii\\base\\Module->runAction(\'dashboard/index\', Array)\n#12 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Application.php(386): yii\\web\\Application->handleRequest(Object(common\\components\\Request))\n#13 /opt/lampp/htdocs/tft/backend/web/index.php(21): yii\\base\\Application->run()\n#14 {main}\n\nNext yii\\db\\Exception: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'u_type\' in \'where clause\'\nThe SQL being executed was: SELECT COUNT(*) FROM `user_additional_info` WHERE `u_type`=3 in /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Schema.php:664\nStack trace:\n#0 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php(1295): yii\\db\\Schema->convertException(Object(PDOException), \'SELECT COUNT(*)...\')\n#1 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php(1158): yii\\db\\Command->internalExecute(\'SELECT COUNT(*)...\')\n#2 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php(425): yii\\db\\Command->queryInternal(\'fetchColumn\', 0)\n#3 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Query.php(463): yii\\db\\Command->queryScalar()\n#4 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/ActiveQuery.php(352): yii\\db\\Query->queryScalar(\'COUNT(*)\', Object(yii\\db\\Connection))\n#5 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Query.php(347): yii\\db\\ActiveQuery->queryScalar(\'COUNT(*)\', Object(yii\\db\\Connection))\n#6 /opt/lampp/htdocs/tft/backend/controllers/DashboardController.php(21): yii\\db\\Query->count()\n#7 [internal function]: backend\\controllers\\DashboardController->actionIndex()\n#8 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/InlineAction.php(57): call_user_func_array(Array, Array)\n#9 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Controller.php(157): yii\\base\\InlineAction->runWithParams(Array)\n#10 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Module.php(528): yii\\base\\Controller->runAction(\'index\', Array)\n#11 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/web/Application.php(103): yii\\base\\Module->runAction(\'dashboard/index\', Array)\n#12 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Application.php(386): yii\\web\\Application->handleRequest(Object(common\\components\\Request))\n#13 /opt/lampp/htdocs/tft/backend/web/index.php(21): yii\\base\\Application->run()\n#14 {main}\nAdditional Information:\nArray\n(\n    [0] => 42S22\n    [1] => 1054\n    [2] => Unknown column \'u_type\' in \'where clause\'\n)\n'),
(6, 4, 'application', 1594203127.2763, '[::1][1][439ecfbfc485d6b078828408d3fc07fa]', '$_GET = []\n\n$_POST = []\n\n$_FILES = []\n\n$_COOKIE = [\n    \'_ga\' => \'GA1.1.523175945.1582203500\'\n    \'sessionToken\' => \'r:ae8eb74535f2a6a5028c3847ecb9c458\'\n    \'auto_saved_sql_sort\' => \'\'\n    \'advanced-frontend\' => \'3d82a1bece8484ab403350173c0e6803\'\n    \'sails_sid\' => \'s:Z5r9AHtHlcRCwrfVJN3rqHNBEHptrO-P.mHmxyR7GTbPIn/D+lsxYNrVRXTZT7j266s+/HSCjAQI\'\n    \'PHPSESSID\' => \'9df45c5c5256be57fd85473ee23b1886\'\n    \'simcify\' => \'40085ca3e5a83fa68985286bbed8ae45\'\n    \'pma_lang\' => \'en\'\n    \'phpMyAdmin\' => \'c75fc7a59b08849d8c23cfce0b97372b\'\n    \'pmaUser-1\' => \'{\\\"iv\\\":\\\"Pv2pAuHyQeVRCdwVu\\\\/umvg==\\\",\\\"mac\\\":\\\"e2a28ae56b4ba54c5646abc9f6e7f1b1f3dc4bda\\\",\\\"payload\\\":\\\"Pb4TkV3TMx2rzGBI\\\\/C5eQw==\\\"}\'\n    \'pmaAuth-1\' => \'{\\\"iv\\\":\\\"NpOeRN+WNnbwmRvcv8HDaA==\\\",\\\"mac\\\":\\\"24a13da4bbc72f3df801c0520c1fc5fd255bcbe1\\\",\\\"payload\\\":\\\"wRXN\\\\/MkY8hz5OeE6TNWl2dDDDzHNQV3jY70aND0rqrs=\\\"}\'\n    \'advanced-backend\' => \'439ecfbfc485d6b078828408d3fc07fa\'\n    \'_identity-backend\' => \'b85026bee3f3c21aed982ceeddca72547278c857cff0df45b461409e71c06dc1a:2:{i:0;s:17:\\\"_identity-backend\\\";i:1;s:16:\\\"[1,null,2592000]\\\";}\'\n    \'_csrf\' => \'6fe325ee80169161f274aba6fe21c022d97d9fdd45a837fdde94984077d81ac7a:2:{i:0;s:5:\\\"_csrf\\\";i:1;s:32:\\\"MQgG_C933nLrHf9WZlwF83aB1Nzn1OsV\\\";}\'\n]\n\n$_SESSION = [\n    \'__flash\' => []\n    \'frontendTranslation_EnableTranslate\' => true\n    \'__returnUrl\' => \'/tft/administration\'\n    \'__id\' => 1\n]\n\n$_SERVER = [\n    \'REDIRECT_UNIQUE_ID\' => \'XwWb93Qr88F42KXf2qNCUQAAAAU\'\n    \'REDIRECT_STATUS\' => \'200\'\n    \'UNIQUE_ID\' => \'XwWb93Qr88F42KXf2qNCUQAAAAU\'\n    \'HTTP_HOST\' => \'localhost\'\n    \'HTTP_CONNECTION\' => \'keep-alive\'\n    \'HTTP_CACHE_CONTROL\' => \'max-age=0\'\n    \'HTTP_UPGRADE_INSECURE_REQUESTS\' => \'1\'\n    \'HTTP_USER_AGENT\' => \'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.117 Safari/537.36\'\n    \'HTTP_SEC_FETCH_USER\' => \'?1\'\n    \'HTTP_ACCEPT\' => \'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9\'\n    \'HTTP_SEC_FETCH_SITE\' => \'same-origin\'\n    \'HTTP_SEC_FETCH_MODE\' => \'navigate\'\n    \'HTTP_REFERER\' => \'http://localhost/tft/administration/site/login\'\n    \'HTTP_ACCEPT_ENCODING\' => \'gzip, deflate, br\'\n    \'HTTP_ACCEPT_LANGUAGE\' => \'en-US,en;q=0.9\'\n    \'HTTP_COOKIE\' => \'_ga=GA1.1.523175945.1582203500; sessionToken=r%3Aae8eb74535f2a6a5028c3847ecb9c458; auto_saved_sql_sort=; advanced-frontend=3d82a1bece8484ab403350173c0e6803; sails.sid=s%3AZ5r9AHtHlcRCwrfVJN3rqHNBEHptrO-P.mHmxyR7GTbPIn%2FD%2BlsxYNrVRXTZT7j266s%2B%2FHSCjAQI; ; PHPSESSID=9df45c5c5256be57fd85473ee23b1886; simcify=40085ca3e5a83fa68985286bbed8ae45; pma_lang=en; phpMyAdmin=c75fc7a59b08849d8c23cfce0b97372b; pmaUser-1=%7B%22iv%22%3A%22Pv2pAuHyQeVRCdwVu%5C%2Fumvg%3D%3D%22%2C%22mac%22%3A%22e2a28ae56b4ba54c5646abc9f6e7f1b1f3dc4bda%22%2C%22payload%22%3A%22Pb4TkV3TMx2rzGBI%5C%2FC5eQw%3D%3D%22%7D; pmaAuth-1=%7B%22iv%22%3A%22NpOeRN%2BWNnbwmRvcv8HDaA%3D%3D%22%2C%22mac%22%3A%2224a13da4bbc72f3df801c0520c1fc5fd255bcbe1%22%2C%22payload%22%3A%22wRXN%5C%2FMkY8hz5OeE6TNWl2dDDDzHNQV3jY70aND0rqrs%3D%22%7D; advanced-backend=439ecfbfc485d6b078828408d3fc07fa; _identity-backend=b85026bee3f3c21aed982ceeddca72547278c857cff0df45b461409e71c06dc1a%3A2%3A%7Bi%3A0%3Bs%3A17%3A%22_identity-backend%22%3Bi%3A1%3Bs%3A16%3A%22%5B1%2Cnull%2C2592000%5D%22%3B%7D; _csrf=6fe325ee80169161f274aba6fe21c022d97d9fdd45a837fdde94984077d81ac7a%3A2%3A%7Bi%3A0%3Bs%3A5%3A%22_csrf%22%3Bi%3A1%3Bs%3A32%3A%22MQgG_C933nLrHf9WZlwF83aB1Nzn1OsV%22%3B%7D\'\n    \'PATH\' => \'/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/snap/bin\'\n    \'LD_LIBRARY_PATH\' => \'/opt/lampp/lib:/opt/lampp/lib\'\n    \'SERVER_SIGNATURE\' => \'\'\n    \'SERVER_SOFTWARE\' => \'Apache/2.4.41 (Unix) OpenSSL/1.1.1d PHP/7.1.33 mod_perl/2.0.8-dev Perl/v5.16.3\'\n    \'SERVER_NAME\' => \'localhost\'\n    \'SERVER_ADDR\' => \'::1\'\n    \'SERVER_PORT\' => \'80\'\n    \'REMOTE_ADDR\' => \'::1\'\n    \'DOCUMENT_ROOT\' => \'/opt/lampp/htdocs\'\n    \'REQUEST_SCHEME\' => \'http\'\n    \'CONTEXT_PREFIX\' => \'\'\n    \'CONTEXT_DOCUMENT_ROOT\' => \'/opt/lampp/htdocs\'\n    \'SERVER_ADMIN\' => \'you@example.com\'\n    \'SCRIPT_FILENAME\' => \'/opt/lampp/htdocs/tft/backend/web/index.php\'\n    \'REMOTE_PORT\' => \'41208\'\n    \'REDIRECT_URL\' => \'/tft/administration/dashboard/index\'\n    \'GATEWAY_INTERFACE\' => \'CGI/1.1\'\n    \'SERVER_PROTOCOL\' => \'HTTP/1.1\'\n    \'REQUEST_METHOD\' => \'GET\'\n    \'QUERY_STRING\' => \'\'\n    \'REQUEST_URI\' => \'/tft/administration/dashboard/index\'\n    \'SCRIPT_NAME\' => \'/tft/backend/web/index.php\'\n    \'PHP_SELF\' => \'/tft/backend/web/index.php\'\n    \'REQUEST_TIME_FLOAT\' => 1594203127.268\n    \'REQUEST_TIME\' => 1594203127\n]'),
(7, 1, 'yii\\db\\Exception', 1594203168.4778, '[::1][1][439ecfbfc485d6b078828408d3fc07fa]', 'PDOException: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'u_type\' in \'where clause\' in /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php:1290\nStack trace:\n#0 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php(1290): PDOStatement->execute()\n#1 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php(1158): yii\\db\\Command->internalExecute(\'SELECT COUNT(*)...\')\n#2 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php(425): yii\\db\\Command->queryInternal(\'fetchColumn\', 0)\n#3 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Query.php(463): yii\\db\\Command->queryScalar()\n#4 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/ActiveQuery.php(352): yii\\db\\Query->queryScalar(\'COUNT(*)\', Object(yii\\db\\Connection))\n#5 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Query.php(347): yii\\db\\ActiveQuery->queryScalar(\'COUNT(*)\', Object(yii\\db\\Connection))\n#6 /opt/lampp/htdocs/tft/backend/controllers/DashboardController.php(21): yii\\db\\Query->count()\n#7 [internal function]: backend\\controllers\\DashboardController->actionIndex()\n#8 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/InlineAction.php(57): call_user_func_array(Array, Array)\n#9 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Controller.php(157): yii\\base\\InlineAction->runWithParams(Array)\n#10 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Module.php(528): yii\\base\\Controller->runAction(\'index\', Array)\n#11 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/web/Application.php(103): yii\\base\\Module->runAction(\'dashboard/index\', Array)\n#12 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Application.php(386): yii\\web\\Application->handleRequest(Object(common\\components\\Request))\n#13 /opt/lampp/htdocs/tft/backend/web/index.php(21): yii\\base\\Application->run()\n#14 {main}\n\nNext yii\\db\\Exception: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'u_type\' in \'where clause\'\nThe SQL being executed was: SELECT COUNT(*) FROM `user_additional_info` WHERE `u_type`=3 in /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Schema.php:664\nStack trace:\n#0 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php(1295): yii\\db\\Schema->convertException(Object(PDOException), \'SELECT COUNT(*)...\')\n#1 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php(1158): yii\\db\\Command->internalExecute(\'SELECT COUNT(*)...\')\n#2 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php(425): yii\\db\\Command->queryInternal(\'fetchColumn\', 0)\n#3 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Query.php(463): yii\\db\\Command->queryScalar()\n#4 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/ActiveQuery.php(352): yii\\db\\Query->queryScalar(\'COUNT(*)\', Object(yii\\db\\Connection))\n#5 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Query.php(347): yii\\db\\ActiveQuery->queryScalar(\'COUNT(*)\', Object(yii\\db\\Connection))\n#6 /opt/lampp/htdocs/tft/backend/controllers/DashboardController.php(21): yii\\db\\Query->count()\n#7 [internal function]: backend\\controllers\\DashboardController->actionIndex()\n#8 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/InlineAction.php(57): call_user_func_array(Array, Array)\n#9 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Controller.php(157): yii\\base\\InlineAction->runWithParams(Array)\n#10 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Module.php(528): yii\\base\\Controller->runAction(\'index\', Array)\n#11 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/web/Application.php(103): yii\\base\\Module->runAction(\'dashboard/index\', Array)\n#12 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Application.php(386): yii\\web\\Application->handleRequest(Object(common\\components\\Request))\n#13 /opt/lampp/htdocs/tft/backend/web/index.php(21): yii\\base\\Application->run()\n#14 {main}\nAdditional Information:\nArray\n(\n    [0] => 42S22\n    [1] => 1054\n    [2] => Unknown column \'u_type\' in \'where clause\'\n)\n'),
(8, 4, 'application', 1594203168.449, '[::1][1][439ecfbfc485d6b078828408d3fc07fa]', '$_GET = []\n\n$_POST = []\n\n$_FILES = []\n\n$_COOKIE = [\n    \'_ga\' => \'GA1.1.523175945.1582203500\'\n    \'sessionToken\' => \'r:ae8eb74535f2a6a5028c3847ecb9c458\'\n    \'auto_saved_sql_sort\' => \'\'\n    \'advanced-frontend\' => \'3d82a1bece8484ab403350173c0e6803\'\n    \'sails_sid\' => \'s:Z5r9AHtHlcRCwrfVJN3rqHNBEHptrO-P.mHmxyR7GTbPIn/D+lsxYNrVRXTZT7j266s+/HSCjAQI\'\n    \'PHPSESSID\' => \'9df45c5c5256be57fd85473ee23b1886\'\n    \'simcify\' => \'40085ca3e5a83fa68985286bbed8ae45\'\n    \'pma_lang\' => \'en\'\n    \'phpMyAdmin\' => \'c75fc7a59b08849d8c23cfce0b97372b\'\n    \'pmaUser-1\' => \'{\\\"iv\\\":\\\"Pv2pAuHyQeVRCdwVu\\\\/umvg==\\\",\\\"mac\\\":\\\"e2a28ae56b4ba54c5646abc9f6e7f1b1f3dc4bda\\\",\\\"payload\\\":\\\"Pb4TkV3TMx2rzGBI\\\\/C5eQw==\\\"}\'\n    \'pmaAuth-1\' => \'{\\\"iv\\\":\\\"NpOeRN+WNnbwmRvcv8HDaA==\\\",\\\"mac\\\":\\\"24a13da4bbc72f3df801c0520c1fc5fd255bcbe1\\\",\\\"payload\\\":\\\"wRXN\\\\/MkY8hz5OeE6TNWl2dDDDzHNQV3jY70aND0rqrs=\\\"}\'\n    \'advanced-backend\' => \'439ecfbfc485d6b078828408d3fc07fa\'\n    \'_identity-backend\' => \'b85026bee3f3c21aed982ceeddca72547278c857cff0df45b461409e71c06dc1a:2:{i:0;s:17:\\\"_identity-backend\\\";i:1;s:16:\\\"[1,null,2592000]\\\";}\'\n    \'_csrf\' => \'6fe325ee80169161f274aba6fe21c022d97d9fdd45a837fdde94984077d81ac7a:2:{i:0;s:5:\\\"_csrf\\\";i:1;s:32:\\\"MQgG_C933nLrHf9WZlwF83aB1Nzn1OsV\\\";}\'\n]\n\n$_SESSION = [\n    \'__flash\' => []\n    \'frontendTranslation_EnableTranslate\' => true\n    \'__returnUrl\' => \'/tft/administration\'\n    \'__id\' => 1\n]\n\n$_SERVER = [\n    \'REDIRECT_UNIQUE_ID\' => \'XwWcIO998eqFXxQPhwZG7AAAAAk\'\n    \'REDIRECT_STATUS\' => \'200\'\n    \'UNIQUE_ID\' => \'XwWcIO998eqFXxQPhwZG7AAAAAk\'\n    \'HTTP_HOST\' => \'localhost\'\n    \'HTTP_CONNECTION\' => \'keep-alive\'\n    \'HTTP_CACHE_CONTROL\' => \'max-age=0\'\n    \'HTTP_UPGRADE_INSECURE_REQUESTS\' => \'1\'\n    \'HTTP_USER_AGENT\' => \'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.117 Safari/537.36\'\n    \'HTTP_SEC_FETCH_USER\' => \'?1\'\n    \'HTTP_ACCEPT\' => \'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9\'\n    \'HTTP_SEC_FETCH_SITE\' => \'same-origin\'\n    \'HTTP_SEC_FETCH_MODE\' => \'navigate\'\n    \'HTTP_REFERER\' => \'http://localhost/tft/administration/site/login\'\n    \'HTTP_ACCEPT_ENCODING\' => \'gzip, deflate, br\'\n    \'HTTP_ACCEPT_LANGUAGE\' => \'en-US,en;q=0.9\'\n    \'HTTP_COOKIE\' => \'_ga=GA1.1.523175945.1582203500; sessionToken=r%3Aae8eb74535f2a6a5028c3847ecb9c458; auto_saved_sql_sort=; advanced-frontend=3d82a1bece8484ab403350173c0e6803; sails.sid=s%3AZ5r9AHtHlcRCwrfVJN3rqHNBEHptrO-P.mHmxyR7GTbPIn%2FD%2BlsxYNrVRXTZT7j266s%2B%2FHSCjAQI; ; PHPSESSID=9df45c5c5256be57fd85473ee23b1886; simcify=40085ca3e5a83fa68985286bbed8ae45; pma_lang=en; phpMyAdmin=c75fc7a59b08849d8c23cfce0b97372b; pmaUser-1=%7B%22iv%22%3A%22Pv2pAuHyQeVRCdwVu%5C%2Fumvg%3D%3D%22%2C%22mac%22%3A%22e2a28ae56b4ba54c5646abc9f6e7f1b1f3dc4bda%22%2C%22payload%22%3A%22Pb4TkV3TMx2rzGBI%5C%2FC5eQw%3D%3D%22%7D; pmaAuth-1=%7B%22iv%22%3A%22NpOeRN%2BWNnbwmRvcv8HDaA%3D%3D%22%2C%22mac%22%3A%2224a13da4bbc72f3df801c0520c1fc5fd255bcbe1%22%2C%22payload%22%3A%22wRXN%5C%2FMkY8hz5OeE6TNWl2dDDDzHNQV3jY70aND0rqrs%3D%22%7D; advanced-backend=439ecfbfc485d6b078828408d3fc07fa; _identity-backend=b85026bee3f3c21aed982ceeddca72547278c857cff0df45b461409e71c06dc1a%3A2%3A%7Bi%3A0%3Bs%3A17%3A%22_identity-backend%22%3Bi%3A1%3Bs%3A16%3A%22%5B1%2Cnull%2C2592000%5D%22%3B%7D; _csrf=6fe325ee80169161f274aba6fe21c022d97d9fdd45a837fdde94984077d81ac7a%3A2%3A%7Bi%3A0%3Bs%3A5%3A%22_csrf%22%3Bi%3A1%3Bs%3A32%3A%22MQgG_C933nLrHf9WZlwF83aB1Nzn1OsV%22%3B%7D\'\n    \'PATH\' => \'/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/snap/bin\'\n    \'LD_LIBRARY_PATH\' => \'/opt/lampp/lib:/opt/lampp/lib\'\n    \'SERVER_SIGNATURE\' => \'\'\n    \'SERVER_SOFTWARE\' => \'Apache/2.4.41 (Unix) OpenSSL/1.1.1d PHP/7.1.33 mod_perl/2.0.8-dev Perl/v5.16.3\'\n    \'SERVER_NAME\' => \'localhost\'\n    \'SERVER_ADDR\' => \'::1\'\n    \'SERVER_PORT\' => \'80\'\n    \'REMOTE_ADDR\' => \'::1\'\n    \'DOCUMENT_ROOT\' => \'/opt/lampp/htdocs\'\n    \'REQUEST_SCHEME\' => \'http\'\n    \'CONTEXT_PREFIX\' => \'\'\n    \'CONTEXT_DOCUMENT_ROOT\' => \'/opt/lampp/htdocs\'\n    \'SERVER_ADMIN\' => \'you@example.com\'\n    \'SCRIPT_FILENAME\' => \'/opt/lampp/htdocs/tft/backend/web/index.php\'\n    \'REMOTE_PORT\' => \'41222\'\n    \'REDIRECT_URL\' => \'/tft/administration/dashboard/index\'\n    \'GATEWAY_INTERFACE\' => \'CGI/1.1\'\n    \'SERVER_PROTOCOL\' => \'HTTP/1.1\'\n    \'REQUEST_METHOD\' => \'GET\'\n    \'QUERY_STRING\' => \'\'\n    \'REQUEST_URI\' => \'/tft/administration/dashboard/index\'\n    \'SCRIPT_NAME\' => \'/tft/backend/web/index.php\'\n    \'PHP_SELF\' => \'/tft/backend/web/index.php\'\n    \'REQUEST_TIME_FLOAT\' => 1594203168.442\n    \'REQUEST_TIME\' => 1594203168\n]'),
(9, 1, 'yii\\db\\Exception', 1594203567.8263, '[::1][1][4e995ef38419dd8d3bf6051ca49b0757]', 'PDOException: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'u_type\' in \'where clause\' in /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php:1290\nStack trace:\n#0 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php(1290): PDOStatement->execute()\n#1 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php(1158): yii\\db\\Command->internalExecute(\'SELECT COUNT(*)...\')\n#2 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php(425): yii\\db\\Command->queryInternal(\'fetchColumn\', 0)\n#3 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Query.php(463): yii\\db\\Command->queryScalar()\n#4 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/ActiveQuery.php(352): yii\\db\\Query->queryScalar(\'COUNT(*)\', Object(yii\\db\\Connection))\n#5 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Query.php(347): yii\\db\\ActiveQuery->queryScalar(\'COUNT(*)\', Object(yii\\db\\Connection))\n#6 /opt/lampp/htdocs/tft/backend/controllers/DashboardController.php(21): yii\\db\\Query->count()\n#7 [internal function]: backend\\controllers\\DashboardController->actionIndex()\n#8 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/InlineAction.php(57): call_user_func_array(Array, Array)\n#9 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Controller.php(157): yii\\base\\InlineAction->runWithParams(Array)\n#10 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Module.php(528): yii\\base\\Controller->runAction(\'index\', Array)\n#11 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/web/Application.php(103): yii\\base\\Module->runAction(\'dashboard/index\', Array)\n#12 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Application.php(386): yii\\web\\Application->handleRequest(Object(common\\components\\Request))\n#13 /opt/lampp/htdocs/tft/backend/web/index.php(21): yii\\base\\Application->run()\n#14 {main}\n\nNext yii\\db\\Exception: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'u_type\' in \'where clause\'\nThe SQL being executed was: SELECT COUNT(*) FROM `user_additional_info` WHERE `u_type`=3 in /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Schema.php:664\nStack trace:\n#0 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php(1295): yii\\db\\Schema->convertException(Object(PDOException), \'SELECT COUNT(*)...\')\n#1 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php(1158): yii\\db\\Command->internalExecute(\'SELECT COUNT(*)...\')\n#2 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Command.php(425): yii\\db\\Command->queryInternal(\'fetchColumn\', 0)\n#3 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Query.php(463): yii\\db\\Command->queryScalar()\n#4 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/ActiveQuery.php(352): yii\\db\\Query->queryScalar(\'COUNT(*)\', Object(yii\\db\\Connection))\n#5 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/Query.php(347): yii\\db\\ActiveQuery->queryScalar(\'COUNT(*)\', Object(yii\\db\\Connection))\n#6 /opt/lampp/htdocs/tft/backend/controllers/DashboardController.php(21): yii\\db\\Query->count()\n#7 [internal function]: backend\\controllers\\DashboardController->actionIndex()\n#8 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/InlineAction.php(57): call_user_func_array(Array, Array)\n#9 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Controller.php(157): yii\\base\\InlineAction->runWithParams(Array)\n#10 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Module.php(528): yii\\base\\Controller->runAction(\'index\', Array)\n#11 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/web/Application.php(103): yii\\base\\Module->runAction(\'dashboard/index\', Array)\n#12 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Application.php(386): yii\\web\\Application->handleRequest(Object(common\\components\\Request))\n#13 /opt/lampp/htdocs/tft/backend/web/index.php(21): yii\\base\\Application->run()\n#14 {main}\nAdditional Information:\nArray\n(\n    [0] => 42S22\n    [1] => 1054\n    [2] => Unknown column \'u_type\' in \'where clause\'\n)\n'),
(10, 4, 'application', 1594203567.7971, '[::1][1][4e995ef38419dd8d3bf6051ca49b0757]', '$_GET = []\n\n$_POST = []\n\n$_FILES = []\n\n$_COOKIE = [\n    \'_ga\' => \'GA1.1.523175945.1582203500\'\n    \'sessionToken\' => \'r:ae8eb74535f2a6a5028c3847ecb9c458\'\n    \'auto_saved_sql_sort\' => \'\'\n    \'advanced-frontend\' => \'3d82a1bece8484ab403350173c0e6803\'\n    \'sails_sid\' => \'s:Z5r9AHtHlcRCwrfVJN3rqHNBEHptrO-P.mHmxyR7GTbPIn/D+lsxYNrVRXTZT7j266s+/HSCjAQI\'\n    \'PHPSESSID\' => \'9df45c5c5256be57fd85473ee23b1886\'\n    \'simcify\' => \'40085ca3e5a83fa68985286bbed8ae45\'\n    \'pma_lang\' => \'en\'\n    \'phpMyAdmin\' => \'c75fc7a59b08849d8c23cfce0b97372b\'\n    \'pmaUser-1\' => \'{\\\"iv\\\":\\\"Pv2pAuHyQeVRCdwVu\\\\/umvg==\\\",\\\"mac\\\":\\\"e2a28ae56b4ba54c5646abc9f6e7f1b1f3dc4bda\\\",\\\"payload\\\":\\\"Pb4TkV3TMx2rzGBI\\\\/C5eQw==\\\"}\'\n    \'pmaAuth-1\' => \'{\\\"iv\\\":\\\"NpOeRN+WNnbwmRvcv8HDaA==\\\",\\\"mac\\\":\\\"24a13da4bbc72f3df801c0520c1fc5fd255bcbe1\\\",\\\"payload\\\":\\\"wRXN\\\\/MkY8hz5OeE6TNWl2dDDDzHNQV3jY70aND0rqrs=\\\"}\'\n    \'advanced-backend\' => \'4e995ef38419dd8d3bf6051ca49b0757\'\n    \'_identity-backend\' => \'b85026bee3f3c21aed982ceeddca72547278c857cff0df45b461409e71c06dc1a:2:{i:0;s:17:\\\"_identity-backend\\\";i:1;s:16:\\\"[1,null,2592000]\\\";}\'\n    \'_csrf\' => \'ba8df313d756605af0386b849c72700588c2745a8f0847d77c94c6ca638ae2b5a:2:{i:0;s:5:\\\"_csrf\\\";i:1;s:32:\\\"-Kt_WhJ_UdyL4TXq0_mr0Sz-1B8cfFlu\\\";}\'\n]\n\n$_SESSION = [\n    \'__flash\' => []\n    \'frontendTranslation_EnableTranslate\' => true\n    \'__returnUrl\' => \'/tft/administration/\'\n    \'__id\' => 1\n]\n\n$_SERVER = [\n    \'REDIRECT_UNIQUE_ID\' => \'XwWdrzn5YGdH8VVBgKF9RAAAAAA\'\n    \'REDIRECT_STATUS\' => \'200\'\n    \'UNIQUE_ID\' => \'XwWdrzn5YGdH8VVBgKF9RAAAAAA\'\n    \'HTTP_HOST\' => \'localhost\'\n    \'HTTP_CONNECTION\' => \'keep-alive\'\n    \'HTTP_CACHE_CONTROL\' => \'max-age=0\'\n    \'HTTP_UPGRADE_INSECURE_REQUESTS\' => \'1\'\n    \'HTTP_USER_AGENT\' => \'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.117 Safari/537.36\'\n    \'HTTP_SEC_FETCH_USER\' => \'?1\'\n    \'HTTP_ACCEPT\' => \'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9\'\n    \'HTTP_SEC_FETCH_SITE\' => \'same-origin\'\n    \'HTTP_SEC_FETCH_MODE\' => \'navigate\'\n    \'HTTP_REFERER\' => \'http://localhost/tft/administration/site/login\'\n    \'HTTP_ACCEPT_ENCODING\' => \'gzip, deflate, br\'\n    \'HTTP_ACCEPT_LANGUAGE\' => \'en-US,en;q=0.9\'\n    \'HTTP_COOKIE\' => \'_ga=GA1.1.523175945.1582203500; sessionToken=r%3Aae8eb74535f2a6a5028c3847ecb9c458; auto_saved_sql_sort=; advanced-frontend=3d82a1bece8484ab403350173c0e6803; sails.sid=s%3AZ5r9AHtHlcRCwrfVJN3rqHNBEHptrO-P.mHmxyR7GTbPIn%2FD%2BlsxYNrVRXTZT7j266s%2B%2FHSCjAQI; ; PHPSESSID=9df45c5c5256be57fd85473ee23b1886; simcify=40085ca3e5a83fa68985286bbed8ae45; pma_lang=en; phpMyAdmin=c75fc7a59b08849d8c23cfce0b97372b; pmaUser-1=%7B%22iv%22%3A%22Pv2pAuHyQeVRCdwVu%5C%2Fumvg%3D%3D%22%2C%22mac%22%3A%22e2a28ae56b4ba54c5646abc9f6e7f1b1f3dc4bda%22%2C%22payload%22%3A%22Pb4TkV3TMx2rzGBI%5C%2FC5eQw%3D%3D%22%7D; pmaAuth-1=%7B%22iv%22%3A%22NpOeRN%2BWNnbwmRvcv8HDaA%3D%3D%22%2C%22mac%22%3A%2224a13da4bbc72f3df801c0520c1fc5fd255bcbe1%22%2C%22payload%22%3A%22wRXN%5C%2FMkY8hz5OeE6TNWl2dDDDzHNQV3jY70aND0rqrs%3D%22%7D; advanced-backend=4e995ef38419dd8d3bf6051ca49b0757; _identity-backend=b85026bee3f3c21aed982ceeddca72547278c857cff0df45b461409e71c06dc1a%3A2%3A%7Bi%3A0%3Bs%3A17%3A%22_identity-backend%22%3Bi%3A1%3Bs%3A16%3A%22%5B1%2Cnull%2C2592000%5D%22%3B%7D; _csrf=ba8df313d756605af0386b849c72700588c2745a8f0847d77c94c6ca638ae2b5a%3A2%3A%7Bi%3A0%3Bs%3A5%3A%22_csrf%22%3Bi%3A1%3Bs%3A32%3A%22-Kt_WhJ_UdyL4TXq0_mr0Sz-1B8cfFlu%22%3B%7D\'\n    \'PATH\' => \'/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/snap/bin\'\n    \'LD_LIBRARY_PATH\' => \'/opt/lampp/lib:/opt/lampp/lib\'\n    \'SERVER_SIGNATURE\' => \'\'\n    \'SERVER_SOFTWARE\' => \'Apache/2.4.41 (Unix) OpenSSL/1.1.1d PHP/7.1.33 mod_perl/2.0.8-dev Perl/v5.16.3\'\n    \'SERVER_NAME\' => \'localhost\'\n    \'SERVER_ADDR\' => \'::1\'\n    \'SERVER_PORT\' => \'80\'\n    \'REMOTE_ADDR\' => \'::1\'\n    \'DOCUMENT_ROOT\' => \'/opt/lampp/htdocs\'\n    \'REQUEST_SCHEME\' => \'http\'\n    \'CONTEXT_PREFIX\' => \'\'\n    \'CONTEXT_DOCUMENT_ROOT\' => \'/opt/lampp/htdocs\'\n    \'SERVER_ADMIN\' => \'you@example.com\'\n    \'SCRIPT_FILENAME\' => \'/opt/lampp/htdocs/tft/backend/web/index.php\'\n    \'REMOTE_PORT\' => \'41982\'\n    \'REDIRECT_URL\' => \'/tft/administration/dashboard/index\'\n    \'GATEWAY_INTERFACE\' => \'CGI/1.1\'\n    \'SERVER_PROTOCOL\' => \'HTTP/1.1\'\n    \'REQUEST_METHOD\' => \'GET\'\n    \'QUERY_STRING\' => \'\'\n    \'REQUEST_URI\' => \'/tft/administration/dashboard/index\'\n    \'SCRIPT_NAME\' => \'/tft/backend/web/index.php\'\n    \'PHP_SELF\' => \'/tft/backend/web/index.php\'\n    \'REQUEST_TIME_FLOAT\' => 1594203567.789\n    \'REQUEST_TIME\' => 1594203567\n]'),
(11, 1, 'ParseError', 1594203749.891, '[::1][1][4e995ef38419dd8d3bf6051ca49b0757]', 'ParseError: syntax error, unexpected \'\'client\'\' (T_CONSTANT_ENCAPSED_STRING), expecting \']\' in /opt/lampp/htdocs/tft/backend/controllers/DashboardController.php:22\nStack trace:\n#0 [internal function]: yii\\BaseYii::autoload(\'backend\\\\control...\')\n#1 [internal function]: spl_autoload_call(\'backend\\\\control...\')\n#2 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Module.php(637): class_exists(\'backend\\\\control...\')\n#3 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Module.php(596): yii\\base\\Module->createControllerByID(\'dashboard\')\n#4 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Module.php(522): yii\\base\\Module->createController(\'index\')\n#5 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/web/Application.php(103): yii\\base\\Module->runAction(\'dashboard/index\', Array)\n#6 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Application.php(386): yii\\web\\Application->handleRequest(Object(common\\components\\Request))\n#7 /opt/lampp/htdocs/tft/backend/web/index.php(21): yii\\base\\Application->run()\n#8 {main}');
INSERT INTO `log` (`id`, `level`, `category`, `log_time`, `prefix`, `message`) VALUES
(12, 4, 'application', 1594203749.862, '[::1][1][4e995ef38419dd8d3bf6051ca49b0757]', '$_GET = []\n\n$_POST = []\n\n$_FILES = []\n\n$_COOKIE = [\n    \'_ga\' => \'GA1.1.523175945.1582203500\'\n    \'sessionToken\' => \'r:ae8eb74535f2a6a5028c3847ecb9c458\'\n    \'auto_saved_sql_sort\' => \'\'\n    \'advanced-frontend\' => \'3d82a1bece8484ab403350173c0e6803\'\n    \'sails_sid\' => \'s:Z5r9AHtHlcRCwrfVJN3rqHNBEHptrO-P.mHmxyR7GTbPIn/D+lsxYNrVRXTZT7j266s+/HSCjAQI\'\n    \'PHPSESSID\' => \'9df45c5c5256be57fd85473ee23b1886\'\n    \'simcify\' => \'40085ca3e5a83fa68985286bbed8ae45\'\n    \'pma_lang\' => \'en\'\n    \'phpMyAdmin\' => \'c75fc7a59b08849d8c23cfce0b97372b\'\n    \'pmaUser-1\' => \'{\\\"iv\\\":\\\"Pv2pAuHyQeVRCdwVu\\\\/umvg==\\\",\\\"mac\\\":\\\"e2a28ae56b4ba54c5646abc9f6e7f1b1f3dc4bda\\\",\\\"payload\\\":\\\"Pb4TkV3TMx2rzGBI\\\\/C5eQw==\\\"}\'\n    \'pmaAuth-1\' => \'{\\\"iv\\\":\\\"NpOeRN+WNnbwmRvcv8HDaA==\\\",\\\"mac\\\":\\\"24a13da4bbc72f3df801c0520c1fc5fd255bcbe1\\\",\\\"payload\\\":\\\"wRXN\\\\/MkY8hz5OeE6TNWl2dDDDzHNQV3jY70aND0rqrs=\\\"}\'\n    \'advanced-backend\' => \'4e995ef38419dd8d3bf6051ca49b0757\'\n    \'_identity-backend\' => \'b85026bee3f3c21aed982ceeddca72547278c857cff0df45b461409e71c06dc1a:2:{i:0;s:17:\\\"_identity-backend\\\";i:1;s:16:\\\"[1,null,2592000]\\\";}\'\n    \'_csrf\' => \'ba8df313d756605af0386b849c72700588c2745a8f0847d77c94c6ca638ae2b5a:2:{i:0;s:5:\\\"_csrf\\\";i:1;s:32:\\\"-Kt_WhJ_UdyL4TXq0_mr0Sz-1B8cfFlu\\\";}\'\n]\n\n$_SESSION = [\n    \'__flash\' => []\n    \'frontendTranslation_EnableTranslate\' => true\n    \'__returnUrl\' => \'/tft/administration/\'\n    \'__id\' => 1\n]\n\n$_SERVER = [\n    \'REDIRECT_UNIQUE_ID\' => \'XwWeZRTcCuZAyTxo6UEAtAAAAAg\'\n    \'REDIRECT_STATUS\' => \'200\'\n    \'UNIQUE_ID\' => \'XwWeZRTcCuZAyTxo6UEAtAAAAAg\'\n    \'HTTP_HOST\' => \'localhost\'\n    \'HTTP_CONNECTION\' => \'keep-alive\'\n    \'HTTP_CACHE_CONTROL\' => \'max-age=0\'\n    \'HTTP_UPGRADE_INSECURE_REQUESTS\' => \'1\'\n    \'HTTP_USER_AGENT\' => \'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.117 Safari/537.36\'\n    \'HTTP_SEC_FETCH_USER\' => \'?1\'\n    \'HTTP_ACCEPT\' => \'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9\'\n    \'HTTP_SEC_FETCH_SITE\' => \'same-origin\'\n    \'HTTP_SEC_FETCH_MODE\' => \'navigate\'\n    \'HTTP_REFERER\' => \'http://localhost/tft/administration/site/login\'\n    \'HTTP_ACCEPT_ENCODING\' => \'gzip, deflate, br\'\n    \'HTTP_ACCEPT_LANGUAGE\' => \'en-US,en;q=0.9\'\n    \'HTTP_COOKIE\' => \'_ga=GA1.1.523175945.1582203500; sessionToken=r%3Aae8eb74535f2a6a5028c3847ecb9c458; auto_saved_sql_sort=; advanced-frontend=3d82a1bece8484ab403350173c0e6803; sails.sid=s%3AZ5r9AHtHlcRCwrfVJN3rqHNBEHptrO-P.mHmxyR7GTbPIn%2FD%2BlsxYNrVRXTZT7j266s%2B%2FHSCjAQI; ; PHPSESSID=9df45c5c5256be57fd85473ee23b1886; simcify=40085ca3e5a83fa68985286bbed8ae45; pma_lang=en; phpMyAdmin=c75fc7a59b08849d8c23cfce0b97372b; pmaUser-1=%7B%22iv%22%3A%22Pv2pAuHyQeVRCdwVu%5C%2Fumvg%3D%3D%22%2C%22mac%22%3A%22e2a28ae56b4ba54c5646abc9f6e7f1b1f3dc4bda%22%2C%22payload%22%3A%22Pb4TkV3TMx2rzGBI%5C%2FC5eQw%3D%3D%22%7D; pmaAuth-1=%7B%22iv%22%3A%22NpOeRN%2BWNnbwmRvcv8HDaA%3D%3D%22%2C%22mac%22%3A%2224a13da4bbc72f3df801c0520c1fc5fd255bcbe1%22%2C%22payload%22%3A%22wRXN%5C%2FMkY8hz5OeE6TNWl2dDDDzHNQV3jY70aND0rqrs%3D%22%7D; advanced-backend=4e995ef38419dd8d3bf6051ca49b0757; _identity-backend=b85026bee3f3c21aed982ceeddca72547278c857cff0df45b461409e71c06dc1a%3A2%3A%7Bi%3A0%3Bs%3A17%3A%22_identity-backend%22%3Bi%3A1%3Bs%3A16%3A%22%5B1%2Cnull%2C2592000%5D%22%3B%7D; _csrf=ba8df313d756605af0386b849c72700588c2745a8f0847d77c94c6ca638ae2b5a%3A2%3A%7Bi%3A0%3Bs%3A5%3A%22_csrf%22%3Bi%3A1%3Bs%3A32%3A%22-Kt_WhJ_UdyL4TXq0_mr0Sz-1B8cfFlu%22%3B%7D\'\n    \'PATH\' => \'/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/snap/bin\'\n    \'LD_LIBRARY_PATH\' => \'/opt/lampp/lib:/opt/lampp/lib\'\n    \'SERVER_SIGNATURE\' => \'\'\n    \'SERVER_SOFTWARE\' => \'Apache/2.4.41 (Unix) OpenSSL/1.1.1d PHP/7.1.33 mod_perl/2.0.8-dev Perl/v5.16.3\'\n    \'SERVER_NAME\' => \'localhost\'\n    \'SERVER_ADDR\' => \'::1\'\n    \'SERVER_PORT\' => \'80\'\n    \'REMOTE_ADDR\' => \'::1\'\n    \'DOCUMENT_ROOT\' => \'/opt/lampp/htdocs\'\n    \'REQUEST_SCHEME\' => \'http\'\n    \'CONTEXT_PREFIX\' => \'\'\n    \'CONTEXT_DOCUMENT_ROOT\' => \'/opt/lampp/htdocs\'\n    \'SERVER_ADMIN\' => \'you@example.com\'\n    \'SCRIPT_FILENAME\' => \'/opt/lampp/htdocs/tft/backend/web/index.php\'\n    \'REMOTE_PORT\' => \'42026\'\n    \'REDIRECT_URL\' => \'/tft/administration/dashboard/index\'\n    \'GATEWAY_INTERFACE\' => \'CGI/1.1\'\n    \'SERVER_PROTOCOL\' => \'HTTP/1.1\'\n    \'REQUEST_METHOD\' => \'GET\'\n    \'QUERY_STRING\' => \'\'\n    \'REQUEST_URI\' => \'/tft/administration/dashboard/index\'\n    \'SCRIPT_NAME\' => \'/tft/backend/web/index.php\'\n    \'PHP_SELF\' => \'/tft/backend/web/index.php\'\n    \'REQUEST_TIME_FLOAT\' => 1594203749.853\n    \'REQUEST_TIME\' => 1594203749\n]'),
(13, 1, 'yii\\base\\UnknownPropertyException', 1594203832.4779, '[::1][1][4e995ef38419dd8d3bf6051ca49b0757]', 'yii\\base\\UnknownPropertyException: Setting unknown property: common\\models\\UserAdditionalInfoSearch::u_type in /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Component.php:209\nStack trace:\n#0 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/db/BaseActiveRecord.php(324): yii\\base\\Component->__set(\'u_type\', 2)\n#1 /opt/lampp/htdocs/tft/backend/controllers/UserController.php(81): yii\\db\\BaseActiveRecord->__set(\'u_type\', 2)\n#2 [internal function]: backend\\controllers\\UserController->actionPlayer()\n#3 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/InlineAction.php(57): call_user_func_array(Array, Array)\n#4 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Controller.php(157): yii\\base\\InlineAction->runWithParams(Array)\n#5 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Module.php(528): yii\\base\\Controller->runAction(\'player\', Array)\n#6 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/web/Application.php(103): yii\\base\\Module->runAction(\'user/player\', Array)\n#7 /opt/lampp/htdocs/tft/vendor/yiisoft/yii2/base/Application.php(386): yii\\web\\Application->handleRequest(Object(common\\components\\Request))\n#8 /opt/lampp/htdocs/tft/backend/web/index.php(21): yii\\base\\Application->run()\n#9 {main}'),
(14, 4, 'application', 1594203832.4436, '[::1][1][4e995ef38419dd8d3bf6051ca49b0757]', '$_GET = []\n\n$_POST = []\n\n$_FILES = []\n\n$_COOKIE = [\n    \'_ga\' => \'GA1.1.523175945.1582203500\'\n    \'sessionToken\' => \'r:ae8eb74535f2a6a5028c3847ecb9c458\'\n    \'auto_saved_sql_sort\' => \'\'\n    \'advanced-frontend\' => \'3d82a1bece8484ab403350173c0e6803\'\n    \'sails_sid\' => \'s:Z5r9AHtHlcRCwrfVJN3rqHNBEHptrO-P.mHmxyR7GTbPIn/D+lsxYNrVRXTZT7j266s+/HSCjAQI\'\n    \'PHPSESSID\' => \'9df45c5c5256be57fd85473ee23b1886\'\n    \'simcify\' => \'40085ca3e5a83fa68985286bbed8ae45\'\n    \'pma_lang\' => \'en\'\n    \'phpMyAdmin\' => \'c75fc7a59b08849d8c23cfce0b97372b\'\n    \'pmaUser-1\' => \'{\\\"iv\\\":\\\"Pv2pAuHyQeVRCdwVu\\\\/umvg==\\\",\\\"mac\\\":\\\"e2a28ae56b4ba54c5646abc9f6e7f1b1f3dc4bda\\\",\\\"payload\\\":\\\"Pb4TkV3TMx2rzGBI\\\\/C5eQw==\\\"}\'\n    \'pmaAuth-1\' => \'{\\\"iv\\\":\\\"NpOeRN+WNnbwmRvcv8HDaA==\\\",\\\"mac\\\":\\\"24a13da4bbc72f3df801c0520c1fc5fd255bcbe1\\\",\\\"payload\\\":\\\"wRXN\\\\/MkY8hz5OeE6TNWl2dDDDzHNQV3jY70aND0rqrs=\\\"}\'\n    \'advanced-backend\' => \'4e995ef38419dd8d3bf6051ca49b0757\'\n    \'_identity-backend\' => \'b85026bee3f3c21aed982ceeddca72547278c857cff0df45b461409e71c06dc1a:2:{i:0;s:17:\\\"_identity-backend\\\";i:1;s:16:\\\"[1,null,2592000]\\\";}\'\n    \'_csrf\' => \'ba8df313d756605af0386b849c72700588c2745a8f0847d77c94c6ca638ae2b5a:2:{i:0;s:5:\\\"_csrf\\\";i:1;s:32:\\\"-Kt_WhJ_UdyL4TXq0_mr0Sz-1B8cfFlu\\\";}\'\n]\n\n$_SESSION = [\n    \'__flash\' => []\n    \'frontendTranslation_EnableTranslate\' => true\n    \'__returnUrl\' => \'/tft/administration/\'\n    \'__id\' => 1\n]\n\n$_SERVER = [\n    \'REDIRECT_UNIQUE_ID\' => \'XwWeuBTcCuZAyTxo6UEAtwAAAAg\'\n    \'REDIRECT_STATUS\' => \'200\'\n    \'UNIQUE_ID\' => \'XwWeuBTcCuZAyTxo6UEAtwAAAAg\'\n    \'HTTP_HOST\' => \'localhost\'\n    \'HTTP_CONNECTION\' => \'keep-alive\'\n    \'HTTP_UPGRADE_INSECURE_REQUESTS\' => \'1\'\n    \'HTTP_USER_AGENT\' => \'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.117 Safari/537.36\'\n    \'HTTP_SEC_FETCH_USER\' => \'?1\'\n    \'HTTP_ACCEPT\' => \'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9\'\n    \'HTTP_SEC_FETCH_SITE\' => \'same-origin\'\n    \'HTTP_SEC_FETCH_MODE\' => \'navigate\'\n    \'HTTP_REFERER\' => \'http://localhost/tft/administration/dashboard/index\'\n    \'HTTP_ACCEPT_ENCODING\' => \'gzip, deflate, br\'\n    \'HTTP_ACCEPT_LANGUAGE\' => \'en-US,en;q=0.9\'\n    \'HTTP_COOKIE\' => \'_ga=GA1.1.523175945.1582203500; sessionToken=r%3Aae8eb74535f2a6a5028c3847ecb9c458; auto_saved_sql_sort=; advanced-frontend=3d82a1bece8484ab403350173c0e6803; sails.sid=s%3AZ5r9AHtHlcRCwrfVJN3rqHNBEHptrO-P.mHmxyR7GTbPIn%2FD%2BlsxYNrVRXTZT7j266s%2B%2FHSCjAQI; ; PHPSESSID=9df45c5c5256be57fd85473ee23b1886; simcify=40085ca3e5a83fa68985286bbed8ae45; pma_lang=en; phpMyAdmin=c75fc7a59b08849d8c23cfce0b97372b; pmaUser-1=%7B%22iv%22%3A%22Pv2pAuHyQeVRCdwVu%5C%2Fumvg%3D%3D%22%2C%22mac%22%3A%22e2a28ae56b4ba54c5646abc9f6e7f1b1f3dc4bda%22%2C%22payload%22%3A%22Pb4TkV3TMx2rzGBI%5C%2FC5eQw%3D%3D%22%7D; pmaAuth-1=%7B%22iv%22%3A%22NpOeRN%2BWNnbwmRvcv8HDaA%3D%3D%22%2C%22mac%22%3A%2224a13da4bbc72f3df801c0520c1fc5fd255bcbe1%22%2C%22payload%22%3A%22wRXN%5C%2FMkY8hz5OeE6TNWl2dDDDzHNQV3jY70aND0rqrs%3D%22%7D; advanced-backend=4e995ef38419dd8d3bf6051ca49b0757; _identity-backend=b85026bee3f3c21aed982ceeddca72547278c857cff0df45b461409e71c06dc1a%3A2%3A%7Bi%3A0%3Bs%3A17%3A%22_identity-backend%22%3Bi%3A1%3Bs%3A16%3A%22%5B1%2Cnull%2C2592000%5D%22%3B%7D; _csrf=ba8df313d756605af0386b849c72700588c2745a8f0847d77c94c6ca638ae2b5a%3A2%3A%7Bi%3A0%3Bs%3A5%3A%22_csrf%22%3Bi%3A1%3Bs%3A32%3A%22-Kt_WhJ_UdyL4TXq0_mr0Sz-1B8cfFlu%22%3B%7D\'\n    \'PATH\' => \'/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/snap/bin\'\n    \'LD_LIBRARY_PATH\' => \'/opt/lampp/lib:/opt/lampp/lib\'\n    \'SERVER_SIGNATURE\' => \'\'\n    \'SERVER_SOFTWARE\' => \'Apache/2.4.41 (Unix) OpenSSL/1.1.1d PHP/7.1.33 mod_perl/2.0.8-dev Perl/v5.16.3\'\n    \'SERVER_NAME\' => \'localhost\'\n    \'SERVER_ADDR\' => \'::1\'\n    \'SERVER_PORT\' => \'80\'\n    \'REMOTE_ADDR\' => \'::1\'\n    \'DOCUMENT_ROOT\' => \'/opt/lampp/htdocs\'\n    \'REQUEST_SCHEME\' => \'http\'\n    \'CONTEXT_PREFIX\' => \'\'\n    \'CONTEXT_DOCUMENT_ROOT\' => \'/opt/lampp/htdocs\'\n    \'SERVER_ADMIN\' => \'you@example.com\'\n    \'SCRIPT_FILENAME\' => \'/opt/lampp/htdocs/tft/backend/web/index.php\'\n    \'REMOTE_PORT\' => \'42058\'\n    \'REDIRECT_URL\' => \'/tft/administration/user/player\'\n    \'GATEWAY_INTERFACE\' => \'CGI/1.1\'\n    \'SERVER_PROTOCOL\' => \'HTTP/1.1\'\n    \'REQUEST_METHOD\' => \'GET\'\n    \'QUERY_STRING\' => \'\'\n    \'REQUEST_URI\' => \'/tft/administration/user/player\'\n    \'SCRIPT_NAME\' => \'/tft/backend/web/index.php\'\n    \'PHP_SELF\' => \'/tft/backend/web/index.php\'\n    \'REQUEST_TIME_FLOAT\' => 1594203832.434\n    \'REQUEST_TIME\' => 1594203832\n]');

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1587727944),
('m180828_154717_backup', 1588585318),
('m200424_000001_apps_countries', 1587727945),
('m200424_000001_apps_countriesDataInsert', 1587727946),
('m200424_000002_auth_rule', 1587727947),
('m200424_000002_auth_ruleDataInsert', 1587727947),
('m200424_000003_auth_item', 1587727949),
('m200424_000003_auth_itemDataInsert', 1587727949),
('m200424_000005_user', 1587727949),
('m200424_000005_userDataInsert', 1587727949),
('m200424_000006_auth_assignment', 1587727951),
('m200424_000007_Relations', 1587727952),
('m200424_000008_auth_assignmentDataInsert', 1587727952),
('m200424_000009_auth_item_child', 1587727954),
('m200424_000010_Relations', 1587727956),
('m200424_000011_auth_item_childDataInsert', 1587727957),
('m200424_000012_blog', 1587727957),
('m200424_000013_cms', 1587727957),
('m200424_000013_cmsDataInsert', 1587727958),
('m200424_000014_cron_schedule', 1587727958),
('m200424_000015_email_template', 1587727959),
('m200424_000015_email_templateDataInsert', 1587727959),
('m200424_000016_follower_following', 1587727961),
('m200424_000017_Relations', 1587727965),
('m200424_000019_in_app_purchase_plan', 1587727965),
('m200424_000019_log', 1587727966),
('m200424_000020_notification', 1587727967),
('m200424_000021_Relations', 1587727968),
('m200424_000021_tag', 1587727969),
('m200424_000022_user_additional_info', 1587727971),
('m200424_000023_Relations', 1587727972),
('m200424_000024_user_in_app_transaction', 1587727973),
('m200424_000025_Relations', 1587727974),
('m200424_000026_user_token', 1587727975),
('m200424_000027_Relations', 1587727977),
('m200424_000028_user_verification_code', 1587727977),
('m200424_000029_Relations', 1587727978),
('m200424_000030_setting', 1587727979),
('m200424_000030_settingDataInsert', 1587727979),
('m200424_000031_language', 1587727981),
('m200424_000031_languageDataInsert', 1587727981),
('m200424_000032_language_source', 1587727982),
('m200424_000033_language_translate', 1587727983),
('m200424_000034_Relations', 1587727985);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `user_id` int(20) NOT NULL,
  `uuid` text NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `app_type` varchar(255) NOT NULL,
  `item_id` int(11) DEFAULT 0,
  `is_read` enum('Y','N') DEFAULT 'N',
  `from_user_id` int(12) DEFAULT NULL,
  `created_at` int(20) NOT NULL,
  `created_by` int(20) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `push_request` text DEFAULT NULL,
  `push_response` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pathways`
--

CREATE TABLE `pathways` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `subtext` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pathways`
--

INSERT INTO `pathways` (`id`, `name`, `subtext`, `description`) VALUES
(1, 'PoST', 'Power-Strength Training', 'Energy Pathways Trained : ATP + PC'),
(2, 'SST', 'Speed-Strength Training ', 'Energy Pathways Trained : ATP + PC + Muscle Glycogen'),
(3, 'PrST', 'Protracted-Strength Training ', 'Energy Pathways Trained : ATP + PC + Muscle Glycogen + bordering on aerobic'),
(4, 'SSGST', 'Sport Specific General Strength Training', 'Energy Pathways Trained  depends on the specific sport');

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `id` int(11) NOT NULL,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_name` varchar(255) DEFAULT NULL,
  `meta_type` varchar(50) DEFAULT NULL,
  `meta_desc` text DEFAULT NULL,
  `meta_attribute` text DEFAULT NULL,
  `meta_value` text DEFAULT NULL,
  `is_public` tinyint(1) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `created_by` int(1) NOT NULL,
  `created_at` int(20) NOT NULL,
  `updated_at` int(20) NOT NULL,
  `updated_by` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`id`, `meta_key`, `meta_name`, `meta_type`, `meta_desc`, `meta_attribute`, `meta_value`, `is_public`, `status`, `created_by`, `created_at`, `updated_at`, `updated_by`) VALUES
(1, 'timezone', 'Timezone', 'select', 'Set the time zone of the application', '{\"list\":[{\"value\":\"Australia/Adelaide\",\"label\":\"Australia/Adelaide\"},{\"value\":\"Australia/Brisbane\",\"label\":\"Australia/Brisbane\"},{\"value\":\"Australia/Canberra\",\"label\":\"Australia/Canberra\"},{\"value\":\"Australia/Hobart\",\"label\":\"Australia/Hobart\"},{\"value\":\"Australia/Melbourne\",\"label\":\"Australia/Melbourne\"},{\"value\":\"Australia/Perth\",\"label\":\"Australia/Perth\"},{\"value\":\"Australia/Sydney\",\"label\":\"Australia/Sydney\"}]}', 'Australia/Melbourne', 1, 1, 0, 0, 0, 0),
(2, 'adminEmail', 'admin Email', 'text', 'This is about adfmin', '', NULL, 1, 1, 0, 0, 0, 1),
(3, 'push_api', 'Push Api', 'text', 'Test Setting Description', '', NULL, 1, 1, 0, 0, 0, 1),
(4, 'facebook', 'Facebook Url', 'text', NULL, '', NULL, 1, 1, 1, 1555926806, 1555926806, 1),
(5, 'android_app_link', 'Android App Link', 'text', NULL, '', NULL, 1, 1, 1, 1555927224, 1555927224, 1),
(6, 'ios_app_link', 'Ios App Link', 'text', NULL, '', NULL, 1, 1, 1, 1555927264, 1555927264, 1),
(7, 'instagram', 'Instagram link', 'text', NULL, '', NULL, 1, 1, 1, 1555927360, 1555927360, 1),
(8, 'youtube', 'You tube channel link', 'text', NULL, '', '', 1, 1, 1, 1555927389, 1555927389, 1),
(9, 'company_name', 'Company Name of App', 'text', NULL, '', '', 1, 1, 1, 1555927482, 1555927482, 1),
(10, 'company_address', 'Company Address', 'text', NULL, '', '', 1, 1, 1, 1555927517, 1555927517, 1),
(12, 'twitter', 'twitter', 'text', NULL, '', '', 1, 1, 1, 1559533953, 1559533953, 1),
(14, 'senderEmail', 'Sender Email From for contact us page', 'text', NULL, '', '', 1, 1, 1, 1572946246, 1572946246, 1),
(21, 'record_api', 'record_api', 'text', 'record_api', '', '0', 1, 1, 1, 123123, 123123, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE `tag` (
  `id` int(11) NOT NULL,
  `tag_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(200) DEFAULT NULL,
  `auth_key` varchar(255) DEFAULT NULL,
  `access_token_expired_at` int(11) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `unconfirmed_email` varchar(255) DEFAULT NULL,
  `confirmed_at` int(11) DEFAULT NULL,
  `registration_ip` varchar(20) DEFAULT NULL,
  `last_login_at` int(11) DEFAULT NULL,
  `last_login_ip` varchar(20) DEFAULT NULL,
  `blocked_at` int(11) DEFAULT NULL,
  `status` int(2) DEFAULT 10,
  `role` int(11) DEFAULT NULL,
  `user_type` enum('User','Admin','Trainer') DEFAULT 'User',
  `social_provider_id` varchar(255) DEFAULT NULL,
  `social_type` varchar(255) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `access_token_expired_at`, `password_hash`, `password_reset_token`, `email`, `unconfirmed_email`, `confirmed_at`, `registration_ip`, `last_login_at`, `last_login_ip`, `blocked_at`, `status`, `role`, `user_type`, `social_provider_id`, `social_type`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'dVN8fzR_KzJ_lBrymfXI6qyH2QzyXYUU', NULL, '$2y$13$QaH8q3jow3JIR77ZFX841OLTakd1UDP05LFA4c6jUxha.TquADh9a', NULL, 'admin@demo.com', 'admin@demo.com', 1555478664, '192.168.10.1', 1556357160, '192.168.10.1', NULL, 10, 99, 'Admin', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_additional_info`
--

CREATE TABLE `user_additional_info` (
  `id` int(11) NOT NULL,
  `user_id` int(20) NOT NULL,
  `full_name` varchar(255) NOT NULL DEFAULT 'NULL',
  `phone` varchar(255) NOT NULL DEFAULT 'NULL',
  `photo` text DEFAULT NULL,
  `thum_photo` text DEFAULT NULL,
  `city` varchar(255) NOT NULL DEFAULT 'NULL',
  `country_code` char(4) NOT NULL DEFAULT 'NULL',
  `timezone` varchar(20) DEFAULT NULL,
  `language_id` varchar(5) DEFAULT NULL,
  `notification_status` int(1) NOT NULL DEFAULT 1,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') NOT NULL DEFAULT 'male',
  `units_of_measurement` enum('Lb / In','Kg / Cm') NOT NULL DEFAULT 'Lb / In',
  `height` decimal(10,2) NOT NULL,
  `weight` decimal(10,2) NOT NULL,
  `sports_interest` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_in_app_transaction`
--

CREATE TABLE `user_in_app_transaction` (
  `id` int(11) NOT NULL,
  `user_id` int(20) NOT NULL,
  `description` text DEFAULT NULL,
  `value` int(25) DEFAULT NULL,
  `t_type` int(1) NOT NULL DEFAULT 0 COMMENT '1 = Credit 0 = Debit',
  `created_at` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_token`
--

CREATE TABLE `user_token` (
  `id` int(11) NOT NULL,
  `user_id` int(20) NOT NULL,
  `platform` enum('Android','Ios') NOT NULL,
  `uuid` text NOT NULL,
  `app_type` varchar(255) NOT NULL,
  `created_at` int(20) NOT NULL,
  `created_by` int(20) NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_verification_code`
--

CREATE TABLE `user_verification_code` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `code` varchar(6) NOT NULL,
  `expired_at` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `apps_countries`
--
ALTER TABLE `apps_countries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `country_code` (`country_code`),
  ADD KEY `country_name` (`country_name`);

--
-- Indexes for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD PRIMARY KEY (`item_name`,`user_id`),
  ADD KEY `auth_assignment_user_id_idx` (`user_id`);

--
-- Indexes for table `auth_item`
--
ALTER TABLE `auth_item`
  ADD PRIMARY KEY (`name`),
  ADD KEY `rule_name` (`rule_name`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD PRIMARY KEY (`parent`,`child`),
  ADD KEY `child` (`child`);

--
-- Indexes for table `auth_rule`
--
ALTER TABLE `auth_rule`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `backup`
--
ALTER TABLE `backup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms`
--
ALTER TABLE `cms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cron_schedule`
--
ALTER TABLE `cron_schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-CronSchedule-jobCode` (`jobCode`),
  ADD KEY `idx-CronSchedule-status` (`status`);

--
-- Indexes for table `email_template`
--
ALTER TABLE `email_template`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exercises`
--
ALTER TABLE `exercises`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exe_category`
--
ALTER TABLE `exe_category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `in_app_purchase_plan`
--
ALTER TABLE `in_app_purchase_plan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`language_id`);

--
-- Indexes for table `language_source`
--
ALTER TABLE `language_source`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `language_translate`
--
ALTER TABLE `language_translate`
  ADD PRIMARY KEY (`id`,`language`),
  ADD KEY `language_translate_idx_language` (`language`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_log_level` (`level`),
  ADD KEY `idx_log_category` (`category`);

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `user_id_2` (`user_id`),
  ADD KEY `is_read` (`is_read`);

--
-- Indexes for table `pathways`
--
ALTER TABLE `pathways`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-setting` (`meta_key`,`meta_type`,`is_public`,`status`);

--
-- Indexes for table `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tag_name` (`tag_name`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-user` (`username`,`auth_key`,`password_hash`,`status`);

--
-- Indexes for table `user_additional_info`
--
ALTER TABLE `user_additional_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `user_id_2` (`user_id`),
  ADD KEY `country_id` (`country_code`),
  ADD KEY `full_name` (`full_name`),
  ADD KEY `city` (`city`);

--
-- Indexes for table `user_in_app_transaction`
--
ALTER TABLE `user_in_app_transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id_inapp` (`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_token`
--
ALTER TABLE `user_token`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_token_user_id` (`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_verification_code`
--
ALTER TABLE `user_verification_code`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id_user_code` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `apps_countries`
--
ALTER TABLE `apps_countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=247;

--
-- AUTO_INCREMENT for table `backup`
--
ALTER TABLE `backup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blog`
--
ALTER TABLE `blog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cms`
--
ALTER TABLE `cms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cron_schedule`
--
ALTER TABLE `cron_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_template`
--
ALTER TABLE `email_template`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `exercises`
--
ALTER TABLE `exercises`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exe_category`
--
ALTER TABLE `exe_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `in_app_purchase_plan`
--
ALTER TABLE `in_app_purchase_plan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `language_source`
--
ALTER TABLE `language_source`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pathways`
--
ALTER TABLE `pathways`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tag`
--
ALTER TABLE `tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_additional_info`
--
ALTER TABLE `user_additional_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_in_app_transaction`
--
ALTER TABLE `user_in_app_transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_token`
--
ALTER TABLE `user_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_verification_code`
--
ALTER TABLE `user_verification_code`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `fk_auth_assignment_item_name` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `fk_auth_item_child_child` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_auth_item_child_parent` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `language_translate`
--
ALTER TABLE `language_translate`
  ADD CONSTRAINT `fk_language_translate_id` FOREIGN KEY (`id`) REFERENCES `language_source` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_language_translate_language` FOREIGN KEY (`language`) REFERENCES `language` (`language_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `fk_notification_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_additional_info`
--
ALTER TABLE `user_additional_info`
  ADD CONSTRAINT `fk_user_additional_info_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_in_app_transaction`
--
ALTER TABLE `user_in_app_transaction`
  ADD CONSTRAINT `fk_user_in_app_transaction_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_token`
--
ALTER TABLE `user_token`
  ADD CONSTRAINT `fk_user_token_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_verification_code`
--
ALTER TABLE `user_verification_code`
  ADD CONSTRAINT `fk_user_verification_code_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

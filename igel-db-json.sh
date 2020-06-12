#!/bin/bash

script="${BASH_SOURCE[0]}"
scriptdir=$(dirname "$script")
cd "$scriptdir"

set -e

cat > hedgehogs.sql <<"EOF"
DROP DATABASE IF EXISTS `igel-in-lu`;
CREATE DATABASE `igel-in-lu`;
USE `igel-in-lu`;

SELECT '0000' INTO @non;
SELECT 'ff00' INTO @red;
SELECT 'fff0' INTO @yel;
SELECT 'f3f0' INTO @gre;
SELECT 'f00f' INTO @blu;
SELECT 'ffff' INTO @whi;
SELECT 'fc0f' INTO @lil;
SELECT 'ff90' INTO @ora;
SELECT 'f0ff' INTO @cya;
SELECT 'ff9f' INTO @pin;
SELECT 'f000' INTO @blk;

SELECT 'HEALTHY' INTO @h;
SELECT   'NEEDY' INTO @n;
SELECT    'DEAD' INTO @d;

SELECT 'UNKNOWN' INTO @u;
SELECT  'FEMALE' INTO @f;
SELECT    'MALE' INTO @m;


-- https://stackoverflow.com/questions/5510052/changing-the-connection-timezone-in-mysql
-- https://dev.mysql.com/doc/refman/5.7/en/time-zone-support.html#time-zone-installation
SET time_zone = 'Europe/Berlin';

CREATE TABLE `hedgehogs`
(
	`id` INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`gender` ENUM ('UNKNOWN', 'FEMALE', 'MALE') DEFAULT 'UNKNOWN',
	`parent` INTEGER DEFAULT NULL,
	`marker1` VARCHAR(4) DEFAULT '0000',
	`marker2` VARCHAR(4) DEFAULT '0000',
	`birth` INTEGER,
	`notes` VARCHAR(255)
) CHARACTER SET=utf8;

CREATE TABLE `discoveries`
(
	`id` INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`lat` REAL NOT NULL,
	`lon` REAL NOT NULL,
	`hedgehog` INTEGER,
	`condition` ENUM ('UNKNOWN', 'HEALTHY', 'NEEDY', 'DEAD') DEFAULT 'UNKNOWN',
	`notes` TEXT,
	FOREIGN KEY(`hedgehog`) REFERENCES `hedgehogs`(`id`)
) CHARACTER SET=utf8;

INSERT INTO `hedgehogs`(`id`, `parent`, `gender`, `marker1`, `marker2`, `birth`, `notes`)
VALUES
( 2, NULL, @f, @non, @non, NULL, NULL),
( 3, NULL, @m, @non, @non, NULL, NULL),
( 4, NULL, @m, @non, @non, 2018, 'Erwin'),
( 5, NULL, @m, @non, @red, NULL, NULL),
( 6, NULL, @m, @non, @yel, NULL, NULL),
( 7, NULL, @m, @yel, @non, NULL, NULL),
( 8, NULL, @m, @yel, @yel, NULL, NULL),
( 9, NULL, @f, @red, @non, NULL, NULL),
(10, NULL, @u, @non, @lil, NULL, NULL),
(11, NULL, @f, @lil, @non, 2019, 'Emma'),
(12, NULL, @u, @non, @whi, NULL, NULL),
(13, NULL, @u, @whi, @non, NULL, NULL),
(14, NULL, @u, @lil, @lil, 2019, '♂?'),
(15, NULL, @m, @gre, @red, NULL, '2019'),
(16, NULL, @m, @non, @blu, 2019, 'Emil'),
(17, NULL, @u, @blu, @non, NULL, '2019?'),
(19, NULL, @u, @non, @ora, 2019, NULL),
(20, NULL, @f, @ora, @non, NULL, NULL),
(21,   20, @u, @non, @non, 2019, NULL),
(22, NULL, @f, @gre, @yel, 2019, 'Mila Superstar'),
(23, NULL, @f, @lil, @non, 2019, NULL),
(24, NULL, @f, @non, @non, 2019, 'Wolke'),
(25, NULL, @m, @non, @non, 2019, 'Sören Olafsson'),
(26, NULL, @f, @non, @non, 2019, 'Henriette'),
(27, NULL, @m, @red, @blu, 2019, 'Carlos'),
(28, NULL, @f, @non, @non, 2019, 'Anna'),
(29, NULL, @m, @non, @non, 2019, 'Dagobert'),
(30, NULL, @f, @non, @non, 2019, 'Fiona'),
(31, NULL, @f, @non, @gre, 2019, 'Ina'),
(32, NULL, @f, @non, @non, 2019, 'Nina'),
(33, NULL, @f, @non, @red, 2019, 'Charlotte'),
(34, NULL, @f, @non, @gre, 2019, 'Igor'),
(35, NULL, @f, @gre, @gre, 2019, 'Mina'),
(36, NULL, @f, @non, @non, 2019, 'Ronja'),
(37, NULL, @f, @blu, @blu, 2019, NULL),
(38, NULL, @m, @whi, @whi, 2019, NULL),
(39, NULL, @m, @ora, @blu, 2019, NULL),
(40, NULL, @m, @whi, @blu, NULL, NULL),
(41, NULL, @m, @ora, @whi, NULL, NULL),
(42, NULL, @m, @whi, @lil, NULL, NULL),
(43, NULL, @m, @yel, @whi, NULL, NULL),
(44, NULL, @m, @yel, @blu, NULL, NULL),
(45, NULL, @f, @whi, @red, NULL, NULL),
(46, NULL, @f, @gre, @blu, NULL, NULL),
(47, NULL, @m, @whi, @yel, NULL, NULL),
(48, NULL, @m, @lil, @blu, NULL, NULL),
(49, NULL, @m, @ora, @lil, NULL, NULL),
(50, NULL, @m, @lil, @red, NULL, NULL),
(51, NULL, @m, @red, @ora, NULL, NULL),
(52, NULL, @u, @red, @yel, NULL, NULL),
(53, NULL, @u, @blu, @lil, NULL, NULL),
(54, NULL, @m, @lil, @yel, NULL, NULL),
(55, NULL, @f, @ora, @ora, NULL, NULL),
(56, NULL, @m, @pin, @lil, NULL, NULL),
(57, NULL, @m, @blu, @ora, NULL, NULL),
(58, NULL, @f, @lil, @ora, NULL, NULL),
(59, NULL, @f, @lil, @gre, NULL, NULL),
(60, NULL, @m, @non, @non, NULL, 'Juri'),
(61, NULL, @f, @blu, @red, NULL, NULL),
(62, NULL, @m, @blu, @yel, NULL, NULL),
(63, NULL, @m, @non, @non, NULL, 'Stanley'),

( 1, NULL, @u, @non, @non, NULL, NULL);

--

INSERT INTO `discoveries`(`id`, `timestamp`, `lat`, `lon`, `condition`, `hedgehog`, `notes`)
VALUES
(  2, '2018-10-05 20:43', 49.503463, 8.384741, @n,  4, NULL),
(  3, '2018-10-29 20:18', 49.507559, 8.386578, @h,  1, NULL),
(  4, '2019-03-16 20:00', 49.506197, 8.382220, @h,  1, NULL),
(  5, '2019-03-17 20:00', 49.510029, 8.381067, @h,  1, NULL),
(  6, '2019-04-26 16:00', 49.500113, 8.381721, @d,  1, NULL),
(  7, '2019-04-27 00:00', 49.482182, 8.379978, @d,  1, NULL),
(  8, '2019-04-28 12:00', 49.545496, 8.392454, @d,  1, NULL),
(  9, '2019-05-29 21:55', 49.500422, 8.378626, @h,  1, NULL),
( 10, '2019-06-08 22:45', 49.506752, 8.386952, @h,  1, NULL),
( 11, '2019-06-21 21:10', 49.505213, 8.382583, @h,  1, NULL),
( 12, '2019-07-22 21:10', 49.507788, 8.386551, @h,  1, NULL),
( 13, '2019-07-26 22:30', 49.507357, 8.386186, @h,  1, NULL),
( 14, '2019-07-27 22:20', 49.507926, 8.386312, @h,  1, NULL),
( 15, '2019-08-04 20:30', 49.505234, 8.375501, @d,  1, NULL),
( 16, '2019-08-15 21:00', 49.508479, 8.374448, @h,  5, NULL),
( 17, '2019-08-16 21:10', 49.508886, 8.376360, @h,  1, NULL),
( 18, '2019-08-26 16:00', 49.493521, 8.392573, @d,  1, NULL),
( 19, '2019-08-27 19:15', 49.495119, 8.387864, @d,  1, NULL),
( 20, '2019-09-01 21:00', 49.499647, 8.377295, @h,  6, NULL),
( 21, '2019-09-01 21:50', 49.504973, 8.382807, @h,  7, NULL),
( 22, '2019-09-01 22:00', 49.504935, 8.385114, @h,  8, NULL),
( 23, '2019-09-05 22:25', 49.506313, 8.382080, @h,  9, NULL),
( 24, '2019-09-06 22:18', 49.505997, 8.382866, @h,  1, NULL),
( 25, '2019-09-11 08:40', 49.498628, 8.383610, @d,  1, NULL),
( 26, '2019-09-11 08:40', 49.498565, 8.383502, @d,  3, NULL),
( 27, '2019-09-11 08:40', 49.498504, 8.383450, @d,  2, NULL),
( 28, '2019-09-12 20:30', 49.506448, 8.381694, @h, 10, NULL),
( 29, '2019-09-14 22:05', 49.505368, 8.382259, @h, 11, NULL),
( 30, '2019-09-15 21:15', 49.505738, 8.382789, @h, 12, NULL),
( 31, '2019-09-15 21:20', 49.505172, 8.382423, @h, 11, NULL),
( 32, '2019-09-15 21:30', 49.506249, 8.376020, @h, 13, NULL),
( 33, '2019-09-16 20:05', 49.505043, 8.382735, @h, 14, NULL),
( 34, '2019-09-16 20:10', 49.504867, 8.381083, @h,  9, NULL),
( 35, '2019-09-17 18:00', 49.435817, 8.406824, @d,  1, NULL),
( 36, '2019-09-17 20:50', 49.505798, 8.382752, @h, 15, NULL),
( 37, '2019-09-18 17:30', 49.498276, 8.397938, @d,  3, NULL),
( 38, '2019-09-18 21:05', 49.504742, 8.379516, @h, 16, NULL),
( 39, '2019-09-18 21:20', 49.503937, 8.375057, @h, 17, NULL),
( 40, '2019-09-18 21:40', 49.506562, 8.380730, @h,  1, NULL),
( 41, '2019-09-19 20:00', 49.504824, 8.382802, @h, 11, NULL),
( 42, '2019-09-19 20:05', 49.505101, 8.381568, @h, 33, NULL),
( 43, '2019-09-21 19:20', 49.520350, 8.366498, @d,  1, NULL),
( 44, '2019-09-22 21:15', 49.505539, 8.378902, @h, 19, NULL),
( 45, '2019-09-24 21:10', 49.505533, 8.379053, @h, 20, NULL),
( 46, '2019-09-24 21:10', 49.505447, 8.378975, @h, 21, NULL),
( 47, '2019-09-28 21:45', 49.506341, 8.375892, @h,  5, NULL),
( 48, '2019-09-28 22:10', 49.505332, 8.381699, @h,  1, NULL),
( 49, '2019-09-29 22:00', 49.505976, 8.378735, @n, 11, NULL),
( 50, '2019-09-29 22:00', 49.505716, 8.378839, @n, 16, NULL),
( 51, '2019-09-29 23:10', 49.505983, 8.382850, @h, 14, NULL),
( 52, '2019-09-30 20:30', 49.506034, 8.378624, @h,  9, NULL),
( 53, '2019-10-02 20:20', 49.504738, 8.380055, @h, 20, NULL),
( 54, '2019-10-02 21:00', 49.503454, 8.384606, @h,  1, NULL),
( 55, '2019-10-03 21:45', 49.505180, 8.380703, @h,  7, NULL),
( 56, '2019-10-03 21:50', 49.505350, 8.379002, @h, 20, NULL),
( 57, '2019-10-05 22:10', 49.505259, 8.379049, @h, 20, NULL),
( 58, '2019-10-05 22:15', 49.506338, 8.375792, @h,  5, NULL),
( 59, '2019-10-18 22:30', 49.504960, 8.380302, @h, 20, NULL),
( 60, '2019-10-18 22:30', 49.505315, 8.379095, @n, 22, NULL),
( 61, '2019-10-21 20:27', 49.505385, 8.379032, @h, 23, NULL),
( 62, '2019-10-23 17:45', 49.487606, 8.335112, @d,  1, NULL),
( 63, '2019-10-24 21:20', 49.505170, 8.379095, @h, 20, NULL),
( 64, '2019-10-25 22:00', 49.505752, 8.378880, @h, 20, NULL),
( 65, '2019-10-28 20:45', 49.505173, 8.380897, @h, 23, NULL),
( 66, '2019-11-03 15:42', 49.478972, 8.407060, @d,  2, NULL),
( 67, '2019-10-28 22:30', 49.446552, 8.349794, @n, 24, NULL),
( 68, '2019-10-28 21:00', 49.522661, 8.395191, @n, 25, NULL),
( 69, '2019-11-04 11:00', 49.494490, 8.411024, @n, 26, NULL),
( 70, '2019-11-19 18:40', 49.500413, 8.381210, @d,  1, NULL),
( 71, '2019-11-23 21:43', 49.503804, 8.374740, @n, 27, NULL),
( 72, '2019-11-28 18:00', 49.444922, 8.355656, @n, 28, NULL),
( 73, '2019-10-20 14:00', 49.481122, 8.427603, @n, 29, NULL),
( 74, '2020-01-01 14:00', 49.454838, 8.376471, @n, 30, NULL),
( 75, '2019-07-23 19:00', 49.451707, 8.403767, @n, 31, NULL),
( 76, '2019-07-23 19:00', 49.451707, 8.403767, @n, 32, NULL),
( 77, '2019-08-09 16:40', 49.453338, 8.408149, @n, 33, NULL),
( 78, '2019-08-09 16:40', 49.453338, 8.408149, @n, 34, NULL),
( 79, '2019-08-09 16:40', 49.453338, 8.408149, @n, 35, NULL),
( 80, '2019-08-09 16:40', 49.453338, 8.408149, @n, 36, NULL),
( 81, '2020-03-14 21:00', 49.504398, 8.383078, @h, 37, NULL),
( 82, '2020-03-16 21:10', 49.503451, 8.383772, @h, 38, NULL),
( 83, '2020-03-17 20:30', 49.504408, 8.383112, @h, 39, NULL),
( 84, '2020-03-28 21:30', 49.508716, 8.377936, @h, 40, NULL),
( 85, '2020-04-06 21:15', 49.503601, 8.381965, @h, 41, NULL),
( 86, '2020-04-07 21:35', 49.500754, 8.377249, @h, 42, NULL),
( 87, '2020-04-07 22:10', 49.505945, 8.382825, @h,  1, NULL),
( 88, '2020-04-08 22:05', 49.505170, 8.380787, @h, 43, NULL),
( 89, '2020-04-09 22:10', 49.506790, 8.384280, @h, 44, NULL),
( 90, '2020-04-10 22:45', 49.505332, 8.382720, @h, 39, NULL),
( 91, '2020-04-10 22:50', 49.504411, 8.383119, @h, 37, NULL),
( 92, '2020-04-10 22:50', 49.504414, 8.383119, @h, 45, NULL),
( 93, '2020-04-16 21:12', 49.504443, 8.383110, @h, 39, NULL),
( 94, '2020-04-16 21:15', 49.505025, 8.382957, @h, 37, NULL),
( 95, '2020-04-16 21:18', 49.505408, 8.382782, @h, 15, NULL),
( 96, '2020-04-16 21:42', 49.503454, 8.383778, @h, 46, NULL),
( 97, '2020-04-20 22:15', 49.503390, 8.384745, @h, 47, NULL),
( 98, '2020-04-23 21:50', 49.504444, 8.383100, @h, 39, NULL),
( 99, '2020-04-23 21:50', 49.504436, 8.383097, @h, 37, NULL),
(100, '2020-04-23 21:50', 49.504436, 8.383097, @h,  1, NULL),
(101, '2020-04-23 21:50', 49.504435, 8.383095, @h, 45, NULL),
(102, '2020-04-24 21:50', 49.504126, 8.383376, @h,  8, NULL),
(103, '2020-04-25 22:36', 49.506557, 8.375260, @h, 48, NULL),
(104, '2020-04-25 23:00', 49.498882, 8.376347, @h, 49, NULL),
(105, '2020-04-27 21:55', 49.501430, 8.380778, @h,  1, NULL),
(106, '2020-04-27 21:55', 49.501430, 8.380778, @h,  1, NULL),
(107, '2020-04-28 07:20', 49.467628, 8.360687, @d,  1, NULL),
(108, '2020-04-28 21:05', 49.504439, 8.383124, @h,  8, NULL),
(109, '2020-04-28 21:08', 49.504788, 8.382805, @h,  1, NULL),
(110, '2020-04-29 21:44', 49.504506, 8.383174, @h,  1, NULL),
(111, '2020-04-29 22:00', 49.505767, 8.382836, @h, 50, NULL),
(112, '2020-05-02 21:50', 49.503418, 8.384641, @h,  1, NULL),
(113, '2020-05-03 21:15', 49.505883, 8.378712, @h, 51, NULL),
(114, '2020-05-03 21:45', 49.506303, 8.375743, @h, 48, NULL),
(115, '2020-05-04 21:30', 49.508929, 8.376228, @h, 48, NULL),
(116, '2020-05-05 22:05', 49.500523, 8.378949, @h,  1, NULL),
(117, '2020-05-06 22:40', 49.499069, 8.376562, @h,  1, NULL),
(118, '2020-05-06 22:40', 49.499069, 8.376562, @h,  1, NULL),
(119, '2020-05-07 21:45', 49.505979, 8.378446, @h,  1, NULL),
(120, '2020-05-07 22:00', 49.505241, 8.376279, @h,  1, NULL),
(121, '2020-05-07 22:33', 49.504652, 8.386184, @h,  1, NULL),
(122, '2020-05-06 19:20', 49.434925, 8.394542, @d,  1, NULL),
(123, '2020-05-09 22:05', 49.505267, 8.373493, @h,  1, NULL),
(124, '2020-05-09 22:39', 49.505298, 8.378966, @h,  1, NULL),
(125, '2020-05-09 22:45', 49.504904, 8.380156, @h, 19, NULL),
(126, '2020-05-09 22:45', 49.504908, 8.380163, @h, 10, NULL),
(127, '2020-05-16 23:00', 49.506450, 8.381415, @h, 51, NULL),
(128, '2020-05-17 15:25', 49.517176, 8.363754, @d,  1, NULL),
(129, '2020-05-18 23:40', 49.504418, 8.379128, @h, 52, NULL),
(130, '2020-05-18 23:45', 49.506084, 8.378805, @h, 53, NULL),
(131, '2020-05-19 22:30', 49.504924, 8.379229, @h, 16, NULL),
(132, '2020-05-20 22:25', 49.506224, 8.385834, @h, 54, NULL),
(133, '2020-05-20 22:55', 49.507484, 8.374488, @h, 55, NULL),
(134, '2020-05-20 23:45', 49.503930, 8.374930, @h, 56, NULL),
(135, '2020-05-21 00:00', 49.505219, 8.380667, @h,  1, NULL),
(136, '2020-05-21 00:00', 49.505219, 8.380667, @h,  1, NULL),
(137, '2020-05-21 22:20', 49.504724, 8.379268, @h,  1, NULL),
(138, '2020-05-22 12:40', 49.440565, 8.406792, @d,  1, NULL),
(139, '2020-05-23 23:00', 49.507023, 8.374283, @h, 57, NULL),
(140, '2020-05-23 23:05', 49.506308, 8.375973, @h, 58, NULL),
(141, '2020-05-24 21:50', 49.507696, 8.386441, @h,  1, NULL),
(142, '2020-05-24 22:00', 49.510420, 8.381438, @h, 54, NULL),
(143, '2020-05-24 22:15', 49.507175, 8.374287, @h, 57, NULL),
(144, '2020-05-24 22:30', 49.500485, 8.375613, @h, 59, NULL),
(145, '2020-05-25 22:10', 49.504595, 8.379998, @h, 51, NULL),
(146, '2020-05-22 19:35', 49.435591, 8.391418, @d,  1, NULL),
(147, '2020-05-26 21:40', 49.505725, 8.380608, @h,  1, NULL),
(148, '2020-05-26 22:35', 49.505590, 8.382677, @h,  1, NULL),
(149, '2020-05-29 22:05', 49.506548, 8.375384, @h, 58, NULL),
(150, '2020-05-29 22:40', 49.504669, 8.380049, @h,  1, NULL),
(151, '2020-05-30 20:00', 49.374849, 8.390933, @n, 60, NULL),
(152, '2020-06-01 22:35', 49.505495, 8.382612, @h, 61, NULL),
(153, '2020-06-01 22:35', 49.505540, 8.382680, @h, 62, NULL),
(154, '2019-09-16 15:00', 49.449151, 8.242384, @n, 63, NULL),
(155, '2020-06-02 22:10', 49.505712, 8.382807, @h, 54, NULL),
(156, '2020-06-02 22:10', 49.505561, 8.382684, @h,  1, NULL),
(157, '2020-06-02 22:10', 49.507851, 8.374399, @h, 57, NULL),
(158, '2020-06-08 22:15', 49.505137, 8.382621, @h,  1, NULL),

(  1, '2018-08-01 22:30', 49.508580, 8.374523, @h,  1, NULL);
EOF

[ "$(uname -s)" = "Linux" ] || protocol=--protocol=TCP

mysql $protocol \
	--user=igelhilfe \
	--password="$(getpass machine=mysql://localhost login=igelhilfe)" \
	--default-character-set=utf8 \
	< hedgehogs.sql

(
cat <<EOF
{
	"type": "FeatureCollection",
	"features": [
EOF

while read timestamp lat lon condition dnotes gender birth marker hnotes
do
	timestamp=$(date -d @$timestamp +'%Y-%m-%dT%H:%M%z')

	img="img/$marker.png"

	if [ -n "$img" ]; then
		[ -f "$img" ] ||
		{
			echo "No image for $marker." >&2
			exit 1
		}
	fi

	[ "$birth" = "NULL" ] && birth=
	[ "$hnotes" = "NULL" ] && hnotes=
	[ "$dnotes" = "NULL" ] && dnotes=

	if [ -n "$hnotes" ]; then
		if [ -n "$dnotes" ]; then
			description="$hnotes $dnotes"
		else
			description="$hnotes"
		fi
	elif [ -n "$dnotes" ]; then
		description="$dnotes"
	else
		description=
	fi

	q='"birth"'

	cat <<EOF
		${n:+,}{
			"type": "Feature",
			"properties": {
				"timestamp": "$timestamp",
				"condition": "$condition",
				"marker": "$marker",
				"gender": "$gender",
				"description": "$description"
				${birth:+,$q: $birth}
			},
			"geometry": {
				"type": "Point",
				"coordinates": [ $lon, $lat ]
			}
		}
EOF
	((++n))

done < <(
mysql $protocol \
	--user=igelhilfe \
	--password="$(getpass machine=mysql://localhost login=igelhilfe)" \
	--database=igel-in-lu \
	--skip-column-names <<"EOF"
SELECT
	UNIX_TIMESTAMP(`timestamp`) AS `timestamp`,
	`lat`,
	`lon`,
	`condition`,
	`discoveries`.`notes`,
	`gender`,
	`birth`,
	CONCAT(`marker1`, '-', `marker2`) AS `marker`,
	`hedgehogs`.`notes`
FROM
	`discoveries`
	LEFT JOIN hedgehogs ON hedgehogs.id = discoveries.hedgehog
ORDER BY `discoveries`.`id`;
EOF
)

cat <<EOF
	]
}
EOF
) > data.json

<?php
/** Tigre (ትግሬ)
 *
 * @file
 * @ingroup Languages
 *
 * @author Bbeshir
 * @author Amir E. Aharoni
 */

$namespaceNames = [
	NS_MEDIA            => 'ሜድያ',
	NS_SPECIAL          => 'ፍንቱይ',
	NS_TALK             => 'ህድግ',
	NS_USER             => 'መትነፈዓይ',
	NS_USER_TALK        => 'ህድግ_መትነፈዓይ',
	NS_PROJECT_TALK     => 'ህድግ_$1',
	NS_FILE             => 'ፋይል',
	NS_FILE_TALK        => 'ህድግ_ፋይል',
	NS_MEDIAWIKI        => 'ሜድያዊኪ',
	NS_MEDIAWIKI_TALK   => 'ህድግ_ሜድያዊኪ',
	NS_TEMPLATE         => 'ሞደል',
	NS_TEMPLATE_TALK    => 'ህድግ_ሞደል',
	NS_HELP             => 'ሰዳየት',
	NS_HELP_TALK        => 'ህድግ_ሰዳየት',
	NS_CATEGORY         => 'ከረርም',
	NS_CATEGORY_TALK    => 'ህድግ_ከረርም',
];

$namespaceGenderAliases = [
	NS_USER => [ 'male' => 'መትነፈዓይ', 'female' => ' መትነፈዓይት' ],
	NS_USER_TALK => [ 'male' => 'ህድግ_መትነፈዓይ', 'female' => 'ህድግ_መትነፈዓይት' ],
];

$linkTrail = "/^([a-z\x{1200}-\x{137F}]+)(.*)$/sDu";

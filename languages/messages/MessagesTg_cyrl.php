<?php
/** Tajik (Cyrillic script) (тоҷикӣ)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Cbrown1023
 * @author Chinneeb
 * @author Farrukh
 * @author FrancisTyers
 * @author Ibrahim
 * @author Kaganer
 * @author Soroush
 * @author Urhixidur
 * @author לערי ריינהארט
 */

$namespaceNames = [
	NS_MEDIA            => 'Медиа',
	NS_SPECIAL          => 'Вижа',
	NS_TALK             => 'Баҳс',
	NS_USER             => 'Корбар',
	NS_USER_TALK        => 'Баҳси_корбар',
	NS_PROJECT_TALK     => 'Баҳси_$1',
	NS_FILE             => 'Акс',
	NS_FILE_TALK        => 'Баҳси_акс',
	NS_MEDIAWIKI        => 'Медиавики',
	NS_MEDIAWIKI_TALK   => 'Баҳси_медиавики',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Баҳси_шаблон',
	NS_HELP             => 'Роҳнамо',
	NS_HELP_TALK        => 'Баҳси_роҳнамо',
	NS_CATEGORY         => 'Гурӯҳ',
	NS_CATEGORY_TALK    => 'Баҳси_гурӯҳ',
];

$datePreferences = [
	'default',
	'dmy',
	'persian',
	'ISO 8601',
];

$defaultDateFormat = 'dmy';

$datePreferenceMigrationMap = [
	'default',
	'default',
	'default',
	'default'
];

$dateFormats = [
	'dmy time' => 'H:i',
	'dmy date' => 'j xg Y',
	'dmy both' => 'H:i، j xg Y',

	'persian time' => 'H:i',
	'persian date' => 'xij xiF xiY',
	'persian both' => 'H:i، xij xiF xiY',

	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
];

$linkTrail = '/^([a-zабвгдеёжзийклмнопрстуфхчшъэюяғӣқўҳҷцщыь]+)(.*)$/sDu';

<?php
/** Kalmyk (хальмг)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Huuchin
 * @author ОйЛ
 * @author לערי ריינהארט
 */

$fallback = 'ru';

$fallback8bitEncoding = "windows-1251";

$namespaceNames = array(
	NS_MEDIA            => 'Аһар',
	NS_SPECIAL          => 'Көдлхнә',
	NS_TALK             => 'Меткән',
	NS_USER             => 'Демнч',
	NS_USER_TALK        => 'Демнчна_туск_меткән',
	NS_PROJECT_TALK     => '$1_туск_меткән',
	NS_FILE             => 'Боомг',
	NS_FILE_TALK        => 'Боомгин_туск_меткән',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_туск_меткән',
	NS_TEMPLATE         => 'Кевләр',
	NS_TEMPLATE_TALK    => 'Зуран_туск_меткән',
	NS_HELP             => 'Цәәлһлһн',
	NS_HELP_TALK        => 'Цәәлһлһин_туск_меткән',
	NS_CATEGORY         => 'Әәшл',
	NS_CATEGORY_TALK    => 'Әәшлин_туск_меткән',
);

$namespaceAliases = array(
	'Көдлхнə'                 => NS_SPECIAL,
	'Ухалвр'                  => NS_TALK,
	'Орлцач'                  => NS_USER,
	'Орлцачна_тускар_ухалвр'  => NS_USER_TALK,
	'$1_тускар_ухалвр'        => NS_PROJECT_TALK,
	'Зург'                    => NS_FILE,
	'Зургин_тускар_ухалвр'    => NS_FILE_TALK,
	'MediaWiki_тускар_ухалвр' => NS_MEDIAWIKI_TALK,
	'Зура'     => NS_TEMPLATE,
	'Зуран_тускар_ухалвр'     => NS_TEMPLATE_TALK,
	'Цəəлһлһн'                => NS_HELP,
	'Цəəлһлһин_тускар_ухалвр' => NS_HELP_TALK,
	'Янз'                     => NS_CATEGORY,
	'Янзин_тускар_ухалвр'     => NS_CATEGORY_TALK,
);

// Remove Russian aliases
$namespaceGenderAliases = array();


<?php
/** Sakha (саха тыла)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Andrijko Z.
 * @author Bert Jickty
 * @author Gartem
 * @author HalanTul
 * @author Kaganer
 * @author Meno25
 * @author Nemo bis
 * @author Urhixidur
 */

$fallback = 'ru';

$namespaceNames = array(
	NS_SPECIAL          => 'Аналлаах',
	NS_TALK             => 'Ырытыы',
	NS_USER             => 'Кыттааччы',
	NS_USER_TALK        => 'Кыттааччы_ырытыыта',
	NS_PROJECT_TALK     => '$1_ырытыыта',
	NS_FILE             => 'Билэ',
	NS_FILE_TALK        => 'Билэ_ырытыыта',
	NS_TEMPLATE         => 'Халыып',
	NS_TEMPLATE_TALK    => 'Халыып_ырытыыта',
	NS_HELP             => 'Көмө',
	NS_HELP_TALK        => 'Көмө_ырытыыта',
	NS_CATEGORY         => 'Категория',
	NS_CATEGORY_TALK    => 'Категория_ырытыыта',
);

$namespaceAliases = array(
	'Ойуу' => NS_FILE,
	'Ойуу_ырытыыта' => NS_FILE_TALK,
);

// Remove Russian aliases
$namespaceGenderAliases = array();


<?php
/** Church Slavic (словѣньскъ / ⰔⰎⰑⰂⰡⰐⰠⰔⰍⰟ)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$specialPageAliases = array(
	'Allpages'                  => array( 'Вьсѩ_страницѧ' ),
	'Categories'                => array( 'Катигорїѩ' ),
	'Contributions'             => array( 'Добродѣꙗниꙗ' ),
	'Preferences'               => array( 'Строи' ),
	'Recentchanges'             => array( 'Послѣдьнѩ_мѣнꙑ' ),
	'Search'                    => array( 'Исканиѥ' ),
	'Statistics'                => array( 'Статїстїка' ),
	'Upload'                    => array( 'Положєниѥ_дѣла' ),
);

$namespaceNames = array(
	NS_MEDIA            => 'Срѣдьства',
	NS_SPECIAL          => 'Нарочьна',
	NS_TALK             => 'Бєсѣда',
	NS_USER             => 'Польꙃєватєл҄ь',
	NS_USER_TALK        => 'Польꙃєватєлꙗ_бєсѣда',
	NS_PROJECT_TALK     => '{{GRAMMAR:genitive|$1}}_бєсѣда',
	NS_FILE             => 'Дѣло',
	NS_FILE_TALK        => 'Дѣла_бєсѣда',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_бєсѣда',
	NS_TEMPLATE         => 'Обраꙁьць',
	NS_TEMPLATE_TALK    => 'Обраꙁьца_бєсѣда',
	NS_HELP             => 'Помощь',
	NS_HELP_TALK        => 'Помощи_бєсѣда',
	NS_CATEGORY         => 'Катигорїꙗ',
	NS_CATEGORY_TALK    => 'Катигорїѩ_бєсѣда',
);

$namespaceAliases = array(
	'Срѣдьства'                      => NS_MEDIA,
	'Нарочьна'                       => NS_SPECIAL,
	'Бесѣда'                         => NS_TALK,
	'Польѕевател҄ь'                  => NS_USER,
	'Польѕевател_бесѣда'             => NS_USER_TALK,
	'{{grammar:genitive|$1}}_бесѣда' => NS_PROJECT_TALK,
	'Ви́дъ'                          => NS_FILE,
	'Видъ'                           => NS_FILE,
	'Ви́да_бєсѣ́да'                   => NS_FILE_TALK,
	'Вида_бесѣда'                    => NS_FILE_TALK,
	'MediaWiki_бесѣда'               => NS_MEDIAWIKI_TALK,
	'Образьць'                       => NS_TEMPLATE,
	'Образьца_бесѣда'                => NS_TEMPLATE_TALK,
	'Помощь'                         => NS_HELP,
	'Помощи_бесѣда'                  => NS_HELP_TALK,
	'Катигорї'                      => NS_CATEGORY,
	'Катигорїѩ_бесѣда'               => NS_CATEGORY_TALK,
);

$magicWords = array(
	'redirect'                  => array( '0', '#ПРѢНАПРАВЛЄНИѤ', '#REDIRECT' ),
	'language'                  => array( '0', '#ѨꙀꙐКЪ:', '#LANGUAGE:' ),
);

$separatorTransformTable = array(
	',' => ".",
	'.' => ','
);

$linkPrefixExtension = true;

$defaultDateFormat = 'mdy';

$dateFormats = array(
	'mdy time' => 'H:i',
	'mdy date' => 'xg j числа, Y',
	'mdy both' => 'H:i, xg j числа, Y',

	'dmy time' => 'H:i',
	'dmy date' => 'j F Y',
	'dmy both' => 'H:i, j F Y',

	'ymd time' => 'H:i',
	'ymd date' => 'Y F j',
	'ymd both' => 'H:i, Y F j',

	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
);

$linkTrail = '/^([a-zабвгдеєжѕзїіıићклмнопсстѹфхѡѿцчшщъыьѣюѥѧѩѫѭѯѱѳѷѵґѓђёјйљњќуўџэ҄я“»]+)(.*)$/sDu';
$linkPrefixCharset = '„«';


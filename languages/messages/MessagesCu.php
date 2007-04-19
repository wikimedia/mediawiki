<?php
/** Old Church Slavonic (Ѩзыкъ словѣньскъ)
  */

$separatorTransformTable = array(
	',' => ".",
	'.' => ','
);

$linkPrefixExtension = true;

$namespaceNames = array(
	NS_MEDIA            => 'Срѣдьства',
	NS_SPECIAL          => 'Нарочьна',
	NS_MAIN             => '',
	NS_TALK             => 'Бесѣда',
	NS_USER             => 'Польѕевател҄ь',
	NS_USER_TALK        => 'Польѕевател_бесѣда', 
	#NS_PROJECT set by $wgMetaNamespace
  	NS_PROJECT_TALK     => '{{grammar:genitive|$1}}_бесѣда',
	NS_IMAGE            => 'Видъ',
	NS_IMAGE_TALK       => 'Вида_бесѣда',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_бесѣда',
	NS_TEMPLATE         => 'Образьць',
	NS_TEMPLATE_TALK    => 'Образьца_бесѣда',
	NS_HELP             => 'Помощь',
	NS_HELP_TALK        => 'Помощи_бесѣда',
	NS_CATEGORY         => 'Катигорї',
	NS_CATEGORY_TALK    => 'Катигорїѩ_бесѣда',
);

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

$messages = array(
'linkprefix'            => '/^(.*?)(„|«)$/sD',

'january' => 'їанѹарїи',
'february' => 'феврѹарїи',
'march' => 'мартїи',
'april' => 'апрїлїи',
'may_long' => 'маїи',
'june' => 'їѹнїи',
'july' => 'їѹлїи',
'august' => 'аѵгѹстъ',
'september' => 'септемврїи',
'october' => 'октѡврїи',
'november' => 'ноемврїи',
'december' => 'декемврїи',
'january-gen' => 'їанѹарї',
'february-gen' => 'феврѹарї',
'march-gen' => 'мартї',
'april-gen' => 'апрїлї',
'may-gen' => 'маї',
'june-gen' => 'їѹнї',
'july-gen' => 'їѹлї',
'august-gen' => 'аѵгѹста',
'september-gen' => 'септемврї',
'october-gen' => 'октѡврї',
'november-gen' => 'ноемврї',
'december-gen' => 'декемврї',

'1movedto2_redir' => '[[$1]] нареченъ [[$2]] врьхѹ прѣнаправлѥни ѥстъ.',
'blockip' => 'Загради польѕеватель',
'cite_article_link' => 'Приведи статїѭ',
'contributions' => 'Добродѣни польѕевател',
'createaccount' => 'Cъзижди си мѣсто',
'currentevents' => 'Текѫща събыти',
'delete' => 'ничьжи',
'edit' => 'исправи',
'editold' => 'исправи',
'editsection' => 'исправи',
'emailuser' => 'Посъли епїстолѫ',
'go' => 'Прѣиди',
'help' => 'Помощь',
'history_short' => 'Їстѡрї',
'ilsubmit' => 'Ищи',
'login' => 'Въниди',
'logout' => 'иходъ',
'mainpage' => 'Главьна страница',
'move' => 'прѣименѹи',
'mycontris' => 'Мо добродѣни',
'mypreferences' => 'мои строи',
'mytalk' => 'Мо бесѣда',
'navigation' => 'плаваниѥ',
'nstab-main' => 'статї',
'nstab-project' => 'съвѣтъ',
'nstab-special' => 'Нарочьна',
'nstab-user' => 'польѕеватель',
'permalink' => 'Ѹставьна съвѧзь',
'portal' => 'Обьщины съвѣтъ',
'powersearch' => 'Ищи',
'printableversion' => 'Печатьнъ образъ',
'protect' => 'забрани',
'qbedit' => 'исправи',
'qbspecialpages' => 'Нарочьны страницѧ',
'recentchanges' => 'Послѣдьнѩ мѣны',
'recentchangeslinked' => 'Вѧзаны мѣны',
'restriction-edit' => 'исправи',
'search' => 'поискъ',
'searcharticle' => 'Прѣиди',
'searchbutton' => 'Ищи',
'sitesupport' => 'Дани',
'specialpage' => 'нарочьна страница',
'specialpages' => 'Нарочьны страницѧ',
'talk' => 'бесѣда',
'toolbox' => 'Орѫди',
'unprotect' => 'пѹсти',
'unwatch' => 'остави блюдениѥ',
'upload' => 'Положи дѣло',
'uploadbtn' => 'Положи дѣло',
'userlogin' => 'Въниди / съзижди си мѣсто',
'userlogout' => 'иходъ',
'watch' => 'блюди',
'watchlist' => 'Мо блюдени',
'my-watchlist' => 'Мо блюдени',
'whatlinkshere' => ' Досьдещьнѩ съвѧзи',

);

?>

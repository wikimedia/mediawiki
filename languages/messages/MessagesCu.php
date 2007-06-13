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
# Dates
'january'       => 'їанѹарїи',
'february'      => 'феврѹарїи',
'march'         => 'мартїи',
'april'         => 'апрїлїи',
'may_long'      => 'маїи',
'june'          => 'їѹнїи',
'july'          => 'їѹлїи',
'august'        => 'аѵгѹстъ',
'september'     => 'септемврїи',
'october'       => 'октѡврїи',
'november'      => 'ноемврїи',
'december'      => 'декемврїи',
'january-gen'   => 'їанѹарї',
'february-gen'  => 'феврѹарї',
'march-gen'     => 'мартї',
'april-gen'     => 'апрїлї',
'may-gen'       => 'маї',
'june-gen'      => 'їѹнї',
'july-gen'      => 'їѹлї',
'august-gen'    => 'аѵгѹста',
'september-gen' => 'септемврї',
'october-gen'   => 'октѡврї',
'november-gen'  => 'ноемврї',
'december-gen'  => 'декемврї',

'linkprefix' => '/^(.*?)(„|«)$/sD',

'qbedit'         => 'исправи',
'qbspecialpages' => 'Нарочьны страницѧ',
'mytalk'         => 'Мо бесѣда',
'navigation'     => 'плаваниѥ',

'help'             => 'Помощь',
'search'           => 'поискъ',
'searchbutton'     => 'Ищи',
'go'               => 'Прѣиди',
'searcharticle'    => 'Прѣиди',
'history_short'    => 'Їстѡрї',
'printableversion' => 'Печатьнъ образъ',
'permalink'        => 'Ѹставьна съвѧзь',
'edit'             => 'исправи',
'delete'           => 'ничьжи',
'protect'          => 'забрани',
'unprotect'        => 'пѹсти',
'specialpage'      => 'нарочьна страница',
'talk'             => 'бесѣда',
'toolbox'          => 'Орѫди',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'currentevents' => 'Текѫща събыти',
'mainpage'      => 'Главьна страница',
'portal'        => 'Обьщины съвѣтъ',
'sitesupport'   => 'Дани',

'editsection' => 'исправи',
'editold'     => 'исправи',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'    => 'статї',
'nstab-user'    => 'польѕеватель',
'nstab-special' => 'Нарочьна',
'nstab-project' => 'съвѣтъ',

# Login and logout pages
'login'         => 'Въниди',
'userlogin'     => 'Въниди / съзижди си мѣсто',
'logout'        => 'иходъ',
'userlogout'    => 'иходъ',
'createaccount' => 'Cъзижди си мѣсто',

# Search results
'powersearch' => 'Ищи',

# Preferences page
'mypreferences' => 'мои строи',

# Recent changes
'recentchanges' => 'Послѣдьнѩ мѣны',

# Recent changes linked
'recentchangeslinked' => 'Вѧзаны мѣны',

# Upload
'upload'    => 'Положи дѣло',
'uploadbtn' => 'Положи дѣло',

# Image list
'ilsubmit' => 'Ищи',

# Miscellaneous special pages
'specialpages' => 'Нарочьны страницѧ',
'move'         => 'прѣименѹи',

# E-mail user
'emailuser' => 'Посъли епїстолѫ',

# Watchlist
'watchlist'   => 'Мо блюдени',
'mywatchlist' => 'Мо блюдени',
'watch'       => 'блюди',
'unwatch'     => 'остави блюдениѥ',

# Restrictions (nouns)
'restriction-edit' => 'исправи',

# Contributions
'contributions' => 'Добродѣни польѕевател',
'mycontris'     => 'Мо добродѣни',

# What links here
'whatlinkshere' => ' Досьдещьнѩ съвѧзи',

# Block/unblock
'blockip' => 'Загради польѕеватель',

# Move page
'1movedto2_redir' => '[[$1]] нареченъ [[$2]] врьхѹ прѣнаправлѥни ѥстъ.',

);

?>

<?php
/** Church Slavic (Словѣньскъ)
 *
 * @addtogroup Language
 *
 * @author SPQRobin
 * @author Svetko
 * @author Nike
 * @author Siebrand
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
'sunday'        => 'недѣл',
'monday'        => 'понедѣл҄ьникъ',
'tuesday'       => 'въторьникъ',
'wednesday'     => 'срѣда',
'thursday'      => 'четврьтъкъ',
'friday'        => 'пѧтъкъ',
'saturday'      => 'сѫбота',
'sun'           => 'н҃д',
'mon'           => 'п҃н',
'tue'           => 'в҃т',
'wed'           => 'с҃р',
'thu'           => 'ч҃т',
'fri'           => 'п҃т',
'sat'           => 'с҃б',
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
'jan'           => '҃н',
'feb'           => 'фе҃в',
'mar'           => 'ма҃р',
'apr'           => 'ап҃р',
'may'           => 'маи',
'jun'           => 'їѹ҃н',
'jul'           => 'їѹ҃л',
'aug'           => 'аѵ҃г',
'sep'           => 'се҃п',
'oct'           => 'ок҃т',
'nov'           => 'но҃е',
'dec'           => 'де҃к',

# Bits of text used by many pages
'categories'     => 'Катигорїѩ',
'pagecategories' => '{{PLURAL:$1|Катигорї|Катигорїи|Катигориѩ|Катигориѩ}}',

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
'otherlanguages'   => 'Дрѹгы ѩзыкы',
'jumptosearch'     => 'поискъ',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'currentevents' => 'Текѫща събыти',
'mainpage'      => 'Главьна страница',
'portal'        => 'Обьщины съвѣтъ',
'portal-url'    => 'Project:Обьщени съвѣтъ',
'sitesupport'   => 'Дани',

'editsection' => 'исправи',
'editold'     => 'исправи',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'статї',
'nstab-user'     => 'польѕеватель',
'nstab-special'  => 'Нарочьна',
'nstab-project'  => 'съвѣтъ',
'nstab-image'    => 'Видъ',
'nstab-template' => 'Образьць',
'nstab-help'     => 'Страница помощи',

# General errors
'viewsource' => 'Страницѧ источьнъ образъ',

# Login and logout pages
'loginpagetitle'    => 'Въходъ',
'yourname'          => 'Твоѥ имѧ',
'yourpassword'      => 'Таино слово напиши',
'yourpasswordagain' => 'Опакы таиноѥ слово напиши',
'login'             => 'Въниди',
'userlogin'         => 'Въниди / съзижди си мѣсто',
'logout'            => 'иходъ',
'userlogout'        => 'иходъ',
'createaccount'     => 'Cъзижди си мѣсто',
'gotaccount'        => 'Мѣсто ти ѥстъ ли? $1.',
'gotaccountlink'    => 'Въниди',
'userexists'        => 'Сѫще польѕевател имѧ пьса. Ино изобрѧщи.',
'username'          => 'Польѕевател имѧ:',
'loginerror'        => 'Въхода блазна',

# Edit pages
'loginreqlink' => 'Въниди',
'editing'      => 'Исправлѥниѥ: $1',

# Search results
'powersearch' => 'Ищи',

# Preferences page
'mypreferences' => 'мои строи',

# Groups
'group-bot'        => 'Аѵтомати',
'group-sysop'      => 'съмотрителе',
'group-bureaucrat' => 'Чинодателе',

'group-bot-member'        => 'аѵтоматъ',
'group-sysop-member'      => 'съмотрител҄ь',
'group-bureaucrat-member' => 'чинодател҄ь',

# Recent changes
'recentchanges' => 'Послѣдьнѩ мѣны',

# Recent changes linked
'recentchangeslinked' => 'Вѧзаны мѣны',

# Upload
'upload'    => 'Положи дѣло',
'uploadbtn' => 'Положи дѣло',

# Image list
'ilsubmit' => 'Ищи',

# File deletion
'filedelete-submit' => 'ничьжи',

# Random page
'randompage' => 'Люба страница',

# Statistics
'statistics'    => 'Статїстїка',
'sitestats'     => '{{SITENAME}} статїстїка',
'userstats'     => 'Польѕевателъ статїстїка',
'sitestatstext' => "Википедїи '''$1''' {{PLURAL:$1|страница ѥстъ|страници ѥсте|страницѧ сѫтъ|страниць сѫтъ}}, посрѣдѣ {{PLURAL:$1|ѩже|ѥюже|ихъже|ихъже}} и бесѣды, и страницѧ о Википедїи, и ѕѣло малы статїѩ, и прѣнаправлѥни, и дрѹгы страницѧ сѫтъ, ѩже истиньны статїѩ не сѫтъ. Бежихъ Википедїи '''$2''' {{PLURAL:$2|страница ѥстъ|страници ѥсте|страницѧ сѫтъ|страниць сѫтъ}} ѩже {{PLURAL:$2|истиньна статї ѥстъ|истиньнѣ статїи ѥсте|истиньны статїѩ сѫтъ|истиньны статїѩ сѫтъ}}. Википедїи '''$8''' {{PLURAL:$8|дѣло ѥстъ|дѣлѣ ѥсте|дѣла сѫтъ|дѣлъ сѫтъ}}. О прьваѥго Википедїѩ дьне '''$4''' {{PLURAL:$4|исправлѥниѥ сътворѥно ѥстъ|исправлѥнии сътворѥнѣ ѥсте|исправлѥни сътворѥна сѫтъ|исправлѥнии сътворѥна сѫтъ}}. Сѥ значитъ ко кажьдо страница '''$5''' исправлѥни иматъ. [http://meta.wikimedia.org/wiki/Help:Job_queue Дѣиствъ чрѣды] дльгота '''$7''' ѥстъ.",

# Miscellaneous special pages
'allpages'          => 'Вьсѩ страницѧ',
'specialpages'      => 'Нарочьны страницѧ',
'newpages'          => 'Новы статїѩ',
'newpages-username' => 'Польѕевател имѧ:',
'move'              => 'прѣименѹи',

# Special:Allpages
'allpagesfrom' => 'Страницѧ видѣти хощѫ съ начѧльнами бѹкъвами:',
'allarticles'  => 'Вьсѩ статїѩ',

# E-mail user
'emailuser' => 'Посъли епїстолѫ',

# Watchlist
'watchlist'   => 'Мо блюдени',
'mywatchlist' => 'Мо блюдени',
'watch'       => 'блюди',
'unwatch'     => 'остави блюдениѥ',

# Delete/protect/revert
'excontent'       => "вънѫтри бѣ: '$1'",
'excontentauthor' => "вънѫтри бѣ: '$1' (и послѣдьн҄ии дѣтел҄ь бѣ '[[Special:Contributions/$2|$2]]')",

# Restrictions (nouns)
'restriction-edit' => 'исправи',

# Contributions
'contributions' => 'Добродѣни польѕевател',
'mycontris'     => 'Мо добродѣни',

# What links here
'whatlinkshere' => ' Досьдещьнѩ съвѧзи',

# Block/unblock
'blockip'            => 'Загради польѕеватель',
'ipblocklist-submit' => 'поискъ',

# Move page
'1movedto2'       => '[[$1]] нареченъ [[$2]] ѥстъ',
'1movedto2_redir' => '[[$1]] нареченъ [[$2]] врьхѹ прѣнаправлѥни ѥстъ.',

);

<?php
/** Macedonian (Македонски)
 *
 * @ingroup Language
 * @file
 *
 * @author Brainmachine
 * @author Brest
 * @author Brest2008
 * @author FlavrSavr
 * @author Glupav
 * @author INkubusse
 * @author Misos
 * @author Urhixidur
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Медија',
	NS_SPECIAL          => 'Специјални',
	NS_TALK             => 'Разговор',
	NS_USER             => 'Корисник',
	NS_USER_TALK        => 'Разговор_со_корисник',
	NS_PROJECT_TALK     => 'Разговор_за_$1',
	NS_FILE             => 'Податотека',
	NS_FILE_TALK        => 'Разговор_за_податотека',
	NS_MEDIAWIKI        => 'МедијаВики',
	NS_MEDIAWIKI_TALK   => 'Разговор_за_МедијаВики',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Разговор_за_шаблон',
	NS_HELP             => 'Помош',
	NS_HELP_TALK        => 'Разговор_за_помош',
	NS_CATEGORY         => 'Категорија',
	NS_CATEGORY_TALK    => 'Разговор_за_категорија',
);

$namespaceAliases = array(
	'Слика' => NS_FILE,
	'Разговор_за_слика' => NS_FILE_TALK,
);


$datePreferences = array(
	'default',
	'dmy mk',
	'ymd mk',
	'ymdt mk',
	'mdy',
	'dmy',
	'ymd',
	'ISO 8601',
);

$defaultDateFormat = 'dmy or mdy';

$dateFormats = array(
	'dmy mk time' => 'H:i',
	'dmy mk date' => 'j.m.Y',
	'dmy mk both' => 'H:i, j.m.Y',

	'ymd mk time' => 'H:i',
	'ymd mk date' => 'Y.m.j',
	'ymd mk both' => 'H:i, Y.m.j',

	'ymdt mk time' => 'H:i:s',
	'ymdt mk date' => 'Y.m.j',
	'ymdt mk both' => 'Y.m.j, H:i:s',

	'mdy time' => 'H:i',
	'mdy date' => 'F j, Y',
	'mdy both' => 'H:i, F j, Y',

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

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'ДвојниПренасочувања' ),
	'BrokenRedirects'           => array( 'ПрекинатиПренасочувања' ),
	'Disambiguations'           => array( 'Појаснувања' ),
	'Userlogin'                 => array( 'Најавување' ),
	'Userlogout'                => array( 'Одјавување' ),
	'CreateAccount'             => array( 'КреирајКирисничкаСметка' ),
	'Preferences'               => array( 'Нагодувања' ),
	'Watchlist'                 => array( 'ЛистаНаНабљудувања' ),
	'Recentchanges'             => array( 'СкорешниПромени' ),
	'Upload'                    => array( 'Подигнување', 'Подигнувања' ),
	'Listfiles'                 => array( 'ЛистаНаСлики', 'ЛистаНаПодатотеки' ),
	'Newimages'                 => array( 'НовиСлики', 'НовиПодатотеки' ),
	'Listusers'                 => array( 'ЛистаНаКорисници', 'ЛистаКорисници' ),
	'Listgrouprights'           => array( 'ЛистаНаГрупниПрава' ),
	'Statistics'                => array( 'Статистики' ),
	'Randompage'                => array( 'Случајна', 'СлучајнаСтраница' ),
	'Lonelypages'               => array( 'ОсамениСтраници', 'СтранциСирачиња' ),
	'Uncategorizedpages'        => array( 'НекатегоризираниСтраници' ),
	'Uncategorizedcategories'   => array( 'НекатегоризираниКатегории' ),
	'Uncategorizedimages'       => array( 'НекатегоризираниСлики' ),
	'Uncategorizedtemplates'    => array( 'НекатегоризираниШаблони' ),
	'Unusedcategories'          => array( 'НеискористениКатегории' ),
	'Unusedimages'              => array( 'НеискористениСлики', 'НеискористениПодатотеки' ),
	'Wantedpages'               => array( 'ПрекинатиВрски' ),
	'Wantedcategories'          => array( 'БараниКатегории' ),
	'Wantedfiles'               => array( 'БараниПодатотеки' ),
	'Wantedtemplates'           => array( 'БараниШаблони' ),
	'Mostlinked'                => array( 'СоНајмногуВрски', 'СтранициСоНајмногуВрски' ),
	'Mostlinkedcategories'      => array( 'НајупотребуваниКатегории' ),
	'Mostlinkedtemplates'       => array( 'НајупотребуваниШаблони' ),
	'Mostimages'                => array( 'НајмногуСлики', 'НајмногуПодатотеки', 'ПодатотекиСоНајмногуВрски' ),
	'Mostcategories'            => array( 'НајмногуКатегории' ),
	'Mostrevisions'             => array( 'НајмногуРевизии' ),
	'Fewestrevisions'           => array( 'НајмалкуРевизии' ),
	'Shortpages'                => array( 'КраткиСтраници' ),
	'Longpages'                 => array( 'ДолгиСтраници' ),
	'Newpages'                  => array( 'НовиСтраници' ),
	'Ancientpages'              => array( 'НајстариСтраници' ),
	'Deadendpages'              => array( 'ЌорсокакСтраници' ),
	'Protectedpages'            => array( 'ЗаштитениСтраници' ),
	'Protectedtitles'           => array( 'ЗаштитениНаслови' ),
	'Allpages'                  => array( 'СитеСтраници' ),
	'Prefixindex'               => array( 'ИндексНаПрефикси' ),
	'Ipblocklist'               => array( 'ЛистаНаБлокираниIP' ),
	'Specialpages'              => array( 'СпецијалниСтраници' ),
	'Contributions'             => array( 'Придонеси' ),
	'Whatlinkshere'             => array( 'ШтоВодиОвде' ),
	'Recentchangeslinked'       => array( 'ПоврзаниПромени' ),
	'Movepage'                  => array( 'ПреместиСтраница' ),
	'Blockme'                   => array( 'БлокирајМе' ),
	'Booksources'               => array( 'ПечатенИзвор' ),
	'Categories'                => array( 'Категории' ),
	'Export'                    => array( 'Извоз' ),
	'Version'                   => array( 'Верзија' ),
	'Allmessages'               => array( 'СитеПораки' ),
	'Log'                       => array( 'Дневник', 'Дневници' ),
	'Blockip'                   => array( 'Блокирање', 'БлокIP', 'БлокирајКорисник' ),
	'Undelete'                  => array( 'Врати' ),
	'Import'                    => array( 'Увоз' ),
	'Lockdb'                    => array( 'ЗаклучиБаза' ),
	'Unlockdb'                  => array( 'ОтклучиБаза' ),
	'Userrights'                => array( 'КорисничкиПрава' ),
	'MIMEsearch'                => array( 'MIMEПребарување' ),
	'FileDuplicateSearch'       => array( 'ПребарувањеДупликатПодатотека' ),
	'Unwatchedpages'            => array( 'НенабљудуваниСтраници' ),
	'Listredirects'             => array( 'ЛистаНаПренасочувања' ),
	'Revisiondelete'            => array( 'БришењеРевизија' ),
	'Unusedtemplates'           => array( 'НеискористениШаблони' ),
	'Randomredirect'            => array( 'СлучајноПренасочување' ),
	'Mypage'                    => array( 'МојаСтраница' ),
	'Mytalk'                    => array( 'МојРазговор', 'МоиРазговори' ),
	'Mycontributions'           => array( 'МоиПридонеси' ),
	'Listadmins'                => array( 'ЛистаНаАдминистратори' ),
	'Listbots'                  => array( 'ЛистаНаБотови' ),
	'Popularpages'              => array( 'ПопуларниСтраници' ),
	'Search'                    => array( 'Барај' ),
	'Resetpass'                 => array( 'РесетирајЛозинка' ),
	'Withoutinterwiki'          => array( 'БезИнтервики' ),
	'MergeHistory'              => array( 'СпојувањеИсторија' ),
	'Filepath'                  => array( 'ПатДоПодатотека' ),
	'Invalidateemail'           => array( 'ПогрешнаЕпошта' ),
	'Blankpage'                 => array( 'ПразнаСтраница' ),
	'LinkSearch'                => array( 'ПребарајВрска' ),
	'DeletedContributions'      => array( 'ИзбришаниПридонеси' ),
	'Tags'                      => array( 'Приврзоци' ),
);

$magicWords = array(
	'redirect'              => array( '0', '#пренасочување', '#види', '#Пренасочување', '#ПРЕНАСОЧУВАЊЕ', '#REDIRECT' ),
	'notoc'                 => array( '0', '__БЕЗСОДРЖИНА__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__БЕЗГАЛЕРИЈА__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__СОСОДРЖИНА__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__СОДРЖИНА__', '__TOC__' ),
	'noeditsection'         => array( '0', '__БЕЗ_УРЕДУВАЊЕ_НА_СЕКЦИИ__', '__NOEDITSECTION__' ),
	'noheader'              => array( '0', '__БЕЗНАСЛОВ__', '__NOHEADER__' ),
	'currentmonth'          => array( '1', 'СЕГАШЕНМЕСЕЦ', 'CURRENTMONTH' ),
	'currentmonthname'      => array( '1', 'СЕГАШЕНМЕСЕЦИМЕ', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'СЕГАШЕНМЕСЕЦИМЕРОД', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'    => array( '1', 'СЕГАШЕНМЕСЕЦСКР', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'СЕГАШЕНДЕН', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'СЕГАШЕНДЕН2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'СЕГАШЕНДЕНИМЕ', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'СЕГАШНАГОДИНА', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'СЕГАШНОВРЕМЕ', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'СЕГАШЕНЧАС', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'МЕСЕЦ_ЛОКАЛНО', 'LOCALMONTH' ),
	'localmonthname'        => array( '1', 'МЕСЕЦИМЕ_ЛОКАЛНО', 'LOCALMONTHNAME' ),
	'localday'              => array( '1', 'ДЕН_ЛОКАЛНО', 'LOCALDAY' ),
	'localday2'             => array( '1', 'ДЕН2_ЛОКАЛНО', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'ИМЕНАДЕН_ЛОКАЛНО', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'ГОДИНА_ЛОКАЛНО', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'ВРЕМЕ_ЛОКАЛНО', 'LOCALTIME' ),
	'localhour'             => array( '1', 'ЧАС_ЛОКАЛНО', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'БРОЈНАСТРАНИЦИ', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'БРОЈСТАТИИ', 'БРОЈНАСТАТИИ', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'БРОЈНАПОДАТОТЕКИ', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'БРОЈНАКОРИСНИЦИ', 'NUMBEROFUSERS' ),
	'numberofedits'         => array( '1', 'БРОЈНАУРЕДУВАЊА', 'NUMBEROFEDITS' ),
	'numberofviews'         => array( '1', 'БРОЈНАПРЕГЛЕДУВАЊА', 'NUMBEROFVIEWS' ),
	'pagename'              => array( '1', 'СТРАНИЦА', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'СТРАНИЦАИ', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'ИМЕПРОСТОР', 'ИМЕНСКИПРОСТОР', 'NAMESPACE' ),
	'talkspace'             => array( '1', 'РАЗГОВОРПРОСТОР', 'TALKSPACE' ),
	'fullpagename'          => array( '1', 'ЦЕЛОСНОИМЕНАСТРАНИЦА', 'FULLPAGENAME' ),
	'subpagename'           => array( '1', 'ИМЕНАПОДСТРАНИЦА', 'SUBPAGENAME' ),
	'basepagename'          => array( '1', 'ИМЕНАОСНОВНАСТРАНИЦА', 'BASEPAGENAME' ),
	'talkpagename'          => array( '1', 'СТРАНИЦАЗАРАЗГОВОР', 'TALKPAGENAME' ),
	'subst'                 => array( '0', 'ЗАМЕСТ:', 'SUBST:' ),
	'msgnw'                 => array( '0', 'ИЗВЕШТNW:', 'MSGNW:' ),
	'img_thumbnail'         => array( '1', 'мини', 'мини-слика', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'мини-слика=$1', 'мини=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'десно', 'д', 'right' ),
	'img_left'              => array( '1', 'лево', 'л', 'left' ),
	'img_none'              => array( '1', 'н', 'нема', 'none' ),
	'img_width'             => array( '1', '$1пкс', '$1п', '$1px' ),
	'img_center'            => array( '1', 'центар', 'ц', 'center', 'centre' ),
	'img_framed'            => array( '1', 'рамка', 'ворамка', 'framed', 'enframed', 'frame' ),
	'img_frameless'         => array( '1', 'безрамка', 'frameless' ),
	'img_page'              => array( '1', 'страница=$1', 'страница $1', 'page=$1', 'page $1' ),
	'img_top'               => array( '1', 'врв', 'top' ),
	'img_middle'            => array( '1', 'средина', 'middle' ),
	'img_bottom'            => array( '1', 'дно', 'bottom' ),
	'img_link'              => array( '1', 'врска=$1', 'link=$1' ),
	'sitename'              => array( '1', 'ИМЕНАСАЈТ', 'SITENAME' ),
	'localurl'              => array( '0', 'ЛОКАЛНААДРЕСА:', 'LOCALURL:' ),
	'localurle'             => array( '0', 'ЛОКАЛНААДРЕСАИ:', 'LOCALURLE:' ),
	'server'                => array( '0', 'СЕРВЕР', 'SERVER' ),
	'servername'            => array( '0', 'ИМЕНАСЕРВЕР', 'SERVERNAME' ),
	'grammar'               => array( '0', 'ГРАМАТИКА:', 'GRAMMAR:' ),
	'notitleconvert'        => array( '0', '__БЕЗСОДРЖИНА__', '__NOTITLECONVERT__', '__NOTC__' ),
	'currentweek'           => array( '1', 'СЕГАШНАСЕДМИЦА', 'CURRENTWEEK' ),
	'localweek'             => array( '1', 'СЕДМИЦА_ЛОКАЛНО', 'LOCALWEEK' ),
	'plural'                => array( '0', 'МНОЖИНА:', 'PLURAL:' ),
	'raw'                   => array( '0', 'РЕД:', 'RAW:' ),
	'displaytitle'          => array( '1', 'ПРИКАЖИНАСЛОВ', 'DISPLAYTITLE' ),
	'currentversion'        => array( '1', 'ТЕКОВНАВЕРЗИЈА', 'CURRENTVERSION' ),
	'language'              => array( '0', '#ЈАЗИК:', '#LANGUAGE:' ),
	'numberofadmins'        => array( '1', 'БРОЈНААДМИНИСТРАТОРИ', 'NUMBEROFADMINS' ),
	'defaultsort'           => array( '1', 'ОСНОВНОПОДРЕДУВАЊЕ:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'tag'                   => array( '0', 'приврзок', 'tag' ),
	'hiddencat'             => array( '1', '__СКРИЕНА_КАТЕГОРИЈА__', '__СКРИЕНАКАТЕГОРИЈА__', '__HIDDENCAT__' ),
	'pagesincategory'       => array( '1', 'СТРАНИЦИВОКАТЕГОРИЈА', 'СТРАНИЦИВОКАТ', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'              => array( '1', 'ГОЛЕМИНА_НА_СТРАНИЦА', 'PAGESIZE' ),
	'index'                 => array( '1', '__ИНДЕКС__', '__INDEX__' ),
	'noindex'               => array( '1', '__БЕЗИНДЕКС__', '__NOINDEX__' ),
	'staticredirect'        => array( '1', '__СТАТИЧНОПРЕНАСОЧУВАЊЕ__', '__STATICREDIRECT__' ),
);

$linkTrail = '/^([a-zабвгдѓежзѕијклљмнњопрстќуфхцчџш]+)(.*)$/sDu';
$separatorTransformTable = array(',' => '.', '.' => ',' );

$messages = array(
# User preference toggles
'tog-underline'               => 'Потцртување на врски:',
'tog-highlightbroken'         => 'Формат на неправилни врски <a href="" class="new">на овој начин</a> (алтернативно: вака<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Двостранично порамнување на параграфите',
'tog-hideminor'               => 'Сокриј ситни уредувања во скорешни промени',
'tog-hidepatrolled'           => 'Сокриј патролирани уредувања во скорешни промени',
'tog-newpageshidepatrolled'   => 'Сокриј патролирани страници од листа на нови страници',
'tog-extendwatchlist'         => 'Прошири листа на набљудувања со приказ на сите можни промени',
'tog-usenewrc'                => 'Подобрени скорешни промени (JavaScript)',
'tog-numberheadings'          => 'Автоматско нумерирање на заглавија',
'tog-showtoolbar'             => 'Прикажи алатник за уредување (JavaScript)',
'tog-editondblclick'          => 'Уредување на страници при двојно кликнување (JavaScript)',
'tog-editsection'             => 'Овозможи уредување на секција преку [уреди] врски',
'tog-editsectiononrightclick' => 'Овозможи уредување на секција со десен клик на наслов на секција (JavaScript)',
'tog-showtoc'                 => 'Покажи содржина (за страници со повеќе од 3 заглавија)',
'tog-rememberpassword'        => 'Запомни ја лозинката меѓу сесии',
'tog-editwidth'               => 'Максимална ширина на кутијата за уредување',
'tog-watchcreations'          => 'Додади ги страниците што јас ги креирам во мојата листа на набљудувања',
'tog-watchdefault'            => 'Додади ги страниците што јас ги уредувам во мојата листа на набљудувања',
'tog-watchmoves'              => 'Додади ги страниците што јас ги преместувам во мојата листа на набљудувања',
'tog-watchdeletion'           => 'Додади ги страниците што јас ги бришам во мојата листа на набљудувања',
'tog-minordefault'            => 'Обележи ги сите уредувања како ситни по основно',
'tog-previewontop'            => 'Прикажи го прегледот пред кутијата за уредување',
'tog-previewonfirst'          => 'Прикажи преглед на првото уредување',
'tog-nocache'                 => 'Оневозможи кеширање на страниците',
'tog-enotifwatchlistpages'    => 'Испрати ми е-пошта при промена на страница од мојата листа на набљудувања',
'tog-enotifusertalkpages'     => 'Испрати ми е-пошта при промена на мојата страница за разговор',
'tog-enotifminoredits'        => 'Испрати ми, исто така, е-пошта за ситни промени на страници',
'tog-enotifrevealaddr'        => 'Откриј ја мојата е-поштенска адреса во пораките за известување',
'tog-shownumberswatching'     => 'Прикажи го бројот на корисници кои набљудуваат',
'tog-fancysig'                => 'Чист потпис (без автоматска врска)',
'tog-externaleditor'          => 'Користи надворешен уредувач по основно (само за експерти, потребно е специфично нагодување на вашиот компјутер)',
'tog-externaldiff'            => 'Користи надворешна програма за разлики по основно (само за експерти, потребно е специјално нагодување на вашиот компјутер)',
'tog-showjumplinks'           => 'Овозможи „скокни до“ врски на пристапност',
'tog-uselivepreview'          => 'Користи преглед во живо (JavaScript) (Експериментално)',
'tog-forceeditsummary'        => 'Извести ме кога нема опис на промените',
'tog-watchlisthideown'        => 'Сокриј мои уредувања од листата на набљудувања',
'tog-watchlisthidebots'       => 'Сокриј бот уредувања во листата на набљудувања',
'tog-watchlisthideminor'      => 'Сокриј ситни уредувања во листата на набљудувања',
'tog-watchlisthideliu'        => 'Сокриј уредувања на најавени корисници во мојата листа на набљудувања',
'tog-watchlisthideanons'      => 'Сокриј уредувања од анонимни корисници во мојата листа на набљудувања',
'tog-watchlisthidepatrolled'  => 'Сокриј патролирани уредувања од листа на набљудувања',
'tog-nolangconversion'        => 'Оневозможи различни конверзии',
'tog-ccmeonemails'            => 'Праќај ми копии од е-пораките што ги праќам до други корисници',
'tog-diffonly'                => 'Не ја покажувај содржината на страницата под разликите',
'tog-showhiddencats'          => 'Прикажи скриени категории',
'tog-noconvertlink'           => 'Оневозможи конверзија на наслов на врска',
'tog-norollbackdiff'          => 'Изостави разлика после употребата на враќање',

'underline-always'  => 'Секогаш',
'underline-never'   => 'Никогаш',
'underline-default' => 'Според нагодувањата на прелистувачот',

# Dates
'sunday'        => 'Недела',
'monday'        => 'Понеделник',
'tuesday'       => 'Вторник',
'wednesday'     => 'Среда',
'thursday'      => 'Четврток',
'friday'        => 'Петок',
'saturday'      => 'Сабота',
'sun'           => 'нед',
'mon'           => 'пон',
'tue'           => 'вто',
'wed'           => 'сре',
'thu'           => 'чет',
'fri'           => 'пет',
'sat'           => 'саб',
'january'       => 'јануари',
'february'      => 'февруари',
'march'         => 'март',
'april'         => 'април',
'may_long'      => 'мај',
'june'          => 'јуни',
'july'          => 'јули',
'august'        => 'август',
'september'     => 'септември',
'october'       => 'октомври',
'november'      => 'ноември',
'december'      => 'декември',
'january-gen'   => 'јануари',
'february-gen'  => 'февруари',
'march-gen'     => 'март',
'april-gen'     => 'април',
'may-gen'       => 'мај',
'june-gen'      => 'јуни',
'july-gen'      => 'јули',
'august-gen'    => 'август',
'september-gen' => 'септември',
'october-gen'   => 'октомври',
'november-gen'  => 'ноември',
'december-gen'  => 'декември',
'jan'           => 'јан',
'feb'           => 'фев',
'mar'           => 'мар',
'apr'           => 'апр',
'may'           => 'мај',
'jun'           => 'јун',
'jul'           => 'јул',
'aug'           => 'авг',
'sep'           => 'сеп',
'oct'           => 'окт',
'nov'           => 'ное',
'dec'           => 'дек',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Категорија|Категории}}',
'category_header'                => 'Статии во категоријата "$1"',
'subcategories'                  => 'Подкатегории',
'category-media-header'          => 'Медии во категорија „$1“',
'category-empty'                 => "''Оваа категорија моментално не содржи страници или медија.''",
'hidden-categories'              => '{{PLURAL:$1|Скриена категорија|Скриени категории}}',
'hidden-category-category'       => 'Скриени категории', # Name of the category where hidden categories will be listed
'category-subcat-count'          => '{{PLURAL:$2|Оваа категорија ја содржи само следната подкатегорија.|Оваа категорија {{PLURAL:$1|ја содржи следната подкатегорија|ги содржи следните $1 подкатегории}}, од вкупно $2.}}',
'category-subcat-count-limited'  => 'Оваа категорија {{PLURAL:$1|ја содржи следната подкатегорија|ги содржи следните $1 подкатегории}}.',
'category-article-count'         => '{{PLURAL:$2|Оваа категорија ја содржи само следната страница.|{{PLURAL:$1|Следната страница е|Следните $1 страници се}} во оваа категорија, од вкупно $2.}}',
'category-article-count-limited' => '{{PLURAL:$1|Следната страница е|Следните $1 страници се}} во оваа категорија.',
'category-file-count'            => '{{PLURAL:$2|Оваа категорија ја содржи само следната податотека.|{{PLURAL:$1|Следната податотека е|Следните $1 податотеки се}} во оваа категорија, од вкупно $2.}}',
'category-file-count-limited'    => '{{PLURAL:$1|Следнава податотека е|Следниве $1 податотеки се}} во оваа категорија.',
'listingcontinuesabbrev'         => 'продолжува',

'linkprefix'        => '/^(.*?)([a-zA-Z\\x80-\\xff]+)$/sD',
'mainpagetext'      => "<big>'''Успешно инсталирање на MediaWiki.'''</big>",
'mainpagedocfooter' => 'Погледнете го [http://meta.wikimedia.org/wiki/Help:Contents Корисничкото напатствие] за подетални иформации како се користи вики софтверот.

==Од каде да почнете==
* [http://meta.wikimedia.org/wiki/Manual:Configuration_settings Листа на нагодувања]
* [http://meta.wikimedia.org/wiki/Manual:FAQ MediaWiki ЧПП (често поставувани прашања)].
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki мејлинг листа за нови верзии]',

'about'          => 'Информации за',
'article'        => 'Статија',
'newwindow'      => '(се отвара во нов прозорец)',
'cancel'         => 'Откажи',
'qbfind'         => 'Најди',
'qbbrowse'       => 'Прелистај',
'qbedit'         => 'Уреди',
'qbpageoptions'  => 'Оваа страница',
'qbpageinfo'     => 'Содржина на страница',
'qbmyoptions'    => 'Мои страници',
'qbspecialpages' => 'Специјални страници',
'moredotdotdot'  => 'Повеќе...',
'mypage'         => 'Моја страница',
'mytalk'         => 'Мои разговори',
'anontalk'       => 'Разговор за оваа IP адреса',
'navigation'     => 'Навигација',
'and'            => '&#32;и',

# Metadata in edit box
'metadata_help' => 'Метаподатоци:',

'errorpagetitle'    => 'Грешка',
'returnto'          => 'Врати се на $1.',
'tagline'           => 'Од {{SITENAME}}',
'help'              => 'Помош',
'search'            => 'Пребарај',
'searchbutton'      => 'Пребарај',
'go'                => 'Оди',
'searcharticle'     => 'Оди',
'history'           => 'Историја на страница',
'history_short'     => 'Историја',
'updatedmarker'     => 'ажурирано од мојата последна посета',
'info_short'        => 'Информација',
'printableversion'  => 'Верзија за печатење',
'permalink'         => 'Перманентна врска',
'print'             => 'Печати',
'edit'              => 'Уреди',
'create'            => 'Создади',
'editthispage'      => 'Уреди ја оваа страница',
'create-this-page'  => 'Создади ја оваа страница',
'delete'            => 'Избриши',
'deletethispage'    => 'Избриши ја оваа страница',
'undelete_short'    => 'Враќање на {{PLURAL:$1|едно избришано уредување|$1 избришани уредувања}}',
'protect'           => 'Заштити',
'protect_change'    => 'промена',
'protectthispage'   => 'Заштити ја оваа страница',
'unprotect'         => 'Отстрани заштита',
'unprotectthispage' => 'Отстрани заштита на страница',
'newpage'           => 'Нова страница',
'talkpage'          => 'Дискутирај за оваа страница',
'talkpagelinktext'  => 'Разговор',
'specialpage'       => 'Специјална страница',
'personaltools'     => 'Лични алатки',
'postcomment'       => 'Нова секција',
'articlepage'       => 'Поглед на содржина',
'talk'              => 'Разговор',
'views'             => 'Погледи',
'toolbox'           => 'Алатник',
'userpage'          => 'Поглед на корисничка страница',
'projectpage'       => 'Поглед на проект',
'imagepage'         => 'Поглед на страница на податотека',
'mediawikipage'     => 'Поглед на порака',
'templatepage'      => 'Поглед на шаблон',
'viewhelppage'      => 'Види ја страницата за помош',
'categorypage'      => 'Види ја страницата за категорија',
'viewtalkpage'      => 'Видете го разговорот',
'otherlanguages'    => 'Други јазици',
'redirectedfrom'    => '(Пренасочено од $1)',
'redirectpagesub'   => 'Страница за пренасочување',
'lastmodifiedat'    => 'Оваа страница последен пат е изменета на $2, $1.', # $1 date, $2 time
'viewcount'         => 'Оваа страница била посетена {{PLURAL:$1|еднаш|$1 пати}}.',
'protectedpage'     => 'Заштитена страница',
'jumpto'            => 'Скокни на:',
'jumptonavigation'  => 'навигација',
'jumptosearch'      => 'барај',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'За {{SITENAME}}',
'aboutpage'            => 'Project:За {{SITENAME}}',
'copyright'            => 'Сите текстови се достапни под условите на $1.',
'copyrightpagename'    => '{{SITENAME}} авторски права',
'copyrightpage'        => '{{ns:project}}:Авторски права',
'currentevents'        => 'Тековни настани',
'currentevents-url'    => 'Project:Тековни настани',
'disclaimers'          => 'Услови на употреба',
'disclaimerpage'       => 'Project:Услови на употреба',
'edithelp'             => 'Помош за уредување',
'edithelppage'         => 'Help:Како се уредуваат страници',
'faq'                  => 'ЧПП',
'faqpage'              => 'Project:ЧПП',
'helppage'             => 'Help:Содржина',
'mainpage'             => 'Главна страница',
'mainpage-description' => 'Главна страница',
'policy-url'           => 'Project:Начела',
'portal'               => 'Портал',
'portal-url'           => 'Project:Портал на заедницата',
'privacy'              => 'Заштита на личните податоци',
'privacypage'          => 'Project:Политика на приватност',

'badaccess'        => 'Немате овластување',
'badaccess-group0' => 'Немате дозвола да ја извршите бараната акција.',
'badaccess-groups' => 'Акцијата што ја баравте е ограничена само на корисници во {{PLURAL:$2|групата|една од групите}}: $1.',

'versionrequired'     => 'Верзијата $1 од МедијаВики е задолжителна',
'versionrequiredtext' => 'Мора да имате верзија $1 на МедијаВики за да ја користите оваа страница. 
Види [[Special:Version|страница за верзија]].',

'ok'                      => 'ОК',
'pagetitle'               => '$1 - Википедија',
'retrievedfrom'           => 'Преземено од "$1"',
'youhavenewmessages'      => 'Имате $1 ($2).',
'newmessageslink'         => 'нови пораки',
'newmessagesdifflink'     => 'скорешна промена',
'youhavenewmessagesmulti' => 'Имате нови пораки на $1',
'editsection'             => 'уреди',
'editsection-brackets'    => '[$1]',
'editold'                 => 'уреди',
'viewsourceold'           => 'види код',
'editlink'                => 'уреди',
'viewsourcelink'          => 'види код',
'editsectionhint'         => 'Уреди секција: $1',
'toc'                     => 'Содржина',
'showtoc'                 => 'прикажи',
'hidetoc'                 => 'сокриј',
'thisisdeleted'           => 'Погледни или врати $1?',
'viewdeleted'             => 'Види $1?',
'restorelink'             => '{{PLURAL:$1|едно избришано уредување|$1 избришани уредувања}}',
'feedlinks'               => 'Фид:',
'feed-invalid'            => 'Погрешен начин на претплата на фид',
'feed-unavailable'        => 'RSS/Atom фидови не се достапни',
'site-rss-feed'           => '$1 RSS фид',
'site-atom-feed'          => '$1 Atom фид',
'page-rss-feed'           => '„$1“ RSS фид',
'page-atom-feed'          => '„$1“ Atom фид',
'feed-atom'               => 'Atom',
'feed-rss'                => 'RSS',
'red-link-title'          => '$1 (страницата не постои)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Страница',
'nstab-user'      => 'Страница на корисник',
'nstab-media'     => 'Страница на медија',
'nstab-special'   => 'Специјална страница',
'nstab-project'   => 'Страница на проект',
'nstab-image'     => 'Податотека',
'nstab-mediawiki' => 'Порака',
'nstab-template'  => 'Шаблон',
'nstab-help'      => 'Страница за помош',
'nstab-category'  => 'Категорија',

# Main script and global functions
'nosuchaction'      => 'Не постои таа функција',
'nosuchactiontext'  => 'Акцијата наведена во URL низата е грешна.
Можеби има грешка во пишувањето на URL или сте следеле погрешна врска.
Ова може исто така да биде и грешка во софтверот на {{SITENAME}}.',
'nosuchspecialpage' => 'Не постои таква специјална страница',
'nospecialpagetext' => 'Баравте невалидна [[Special:SpecialPages|специјална страница]].
За листа на валидни специјални страници погледни: [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Грешка',
'databaseerror'        => 'Грешка во базата',
'dberrortext'          => 'Грешка во упитот кон базата. 
Ова може да значи грешка во софтверот.
Последниот упит кон базата беше:
<blockquote><tt>"$1"</tt></blockquote>
од функцијата "<tt>$2</tt>".
MySQL ја врати следната грешка "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Грешка во упитот кон базата. 
Последниот упит кон базата беше: 
"$1" 
од функцијата "$2". 
MySQL ја врати следната грешка "$3: $4".',
'noconnect'            => 'Извинете! Викито има некои технички проблеми и не може да пристапи кон базата на податоци на серверот.<br />
$1',
'nodb'                 => 'Не можам да изберам база $1',
'cachederror'          => 'Следува кеширана копија на бараната страница, која можеби е застарена.',
'laggedslavemode'      => 'Предупредување: Страницата може да не ги содржи скорешните ажурирања.',
'readonly'             => 'Базата е заклучена',
'enterlockreason'      => 'Внесете причина за заклучувањето, вклучувајќи и приближно време на отклучување',
'readonlytext'         => 'Базата е моментално затворена за нови статии и други модификации, најверојатно за рутинска проверка, по која ќе се врати во нормалната состојба. Администраторот кој ја заклучи го понуди следното објаснување: <p>$1',
'missing-article'      => 'Базата на податоци не го пронајде текстот на страницата кој требаше да го пронајде, именуван „$1“ $2.

Ова најчесто е предизвикано од застарена разл. или врска до историја на страница која била избришана.

Ако ова не е случај, можеби сте нашле грешка во софтверот.
Пријавете го ова на некој [[Special:ListUsers/sysop|администратор]], давајќи врска до URL-то.',
'missingarticle-rev'   => '(измена#: $1)',
'missingarticle-diff'  => '(разлика: $1, $2)',
'readonly_lag'         => 'Базата е автоматски заклучена додека помошните сервери не се синхронизираат',
'internalerror'        => 'Внатрешна грешка',
'internalerror_info'   => 'Внатрешна грешка: $1',
'filecopyerror'        => 'Не можe да се копира податотеката "$1" во "$2".',
'filerenameerror'      => 'Не може да се преименува податотеката "$1" во "$2".',
'filedeleteerror'      => 'Не може да се избрише податотеката "$1".',
'directorycreateerror' => 'Не можеше да се креира директориумот „$1“.',
'filenotfound'         => 'Не може да се најде податотеката "$1".',
'fileexistserror'      => 'Не може да се запишува во податотеката „$1“: податотеката постои',
'unexpected'           => 'Неочекувана вредност: "$1"="$2".',
'formerror'            => 'Грешка: не можам да го испратам формуларот',
'badarticleerror'      => 'Ова дејство не може да се спроведе на наведената страница.',
'cannotdelete'         => 'Не можам да ја избришам страницата или сликата. (Можеби е веќе избришана од некој друг.)',
'badtitle'             => 'Лош наслов',
'badtitletext'         => 'Бараниот наслов е грешен, празен или погрешно поврзан меѓујазичен или интер-вики наслов. Може да содржи еден или повеќе знаци што не можат да се користат во наслови.',
'perfcached'           => 'Следните податоци се кеширани и може да не бидат целосно ажурирани:',
'perfcachedts'         => 'Следните податоци се кеширани, и последен пат ажурирани на $1.',
'querypage-no-updates' => 'Ажурирања на оваа страница се оневозможени. Податоците овде нема постојано да се обновуваат.',
'wrong_wfQuery_params' => 'Грешни параметри до wfQuery()<br /> 
Функција: $1<br /> 
Упит: $2',
'viewsource'           => 'Видете го кодот',
'viewsourcefor'        => 'за $1',
'actionthrottled'      => 'Дејството е успорено',
'actionthrottledtext'  => 'Како анти-спам мерка, ограничени сте од вршење на ова дејство премногу пати во краток временски период, а го преминавте ограничувањето.
Ве молиме обидете се повторно за неколку минути.',
'protectedpagetext'    => 'Оваа страница е заклучена за уредувања.',
'viewsourcetext'       => 'Можете да го погледнете и копирате кодот на оваа страница:',
'protectedinterface'   => 'Оваа страница содржи текст од интерфејсот на софтверот и е заклучена заради можна злоупотреба.',
'editinginterface'     => "'''Предупредување:''' Уредувате страница која е дел од корисничкиот интерфејс на MediaWiki софтверот. 
Промените на оваа страница ќе предизвикаат промена на корисничкиот интерфејс кај останатите корисници.
За превод, имајте го во предвид проектот [http://translatewiki.net/wiki/Main_Page?setlang=mk translatewiki.net], MediaWiki проект за локализација.",
'sqlhidden'            => '(Скриен SQL упит)',
'cascadeprotected'     => 'Оваа страница е заштитена, не може да се уредува, бидејќи е дел од {{PLURAL:$1|следнава страница, којашто е заштитена|следниве страници, коишто се заштитени}} бидејќи опцијата „каскадна заштита“ е вклучена:
$2',
'namespaceprotected'   => "Немате дозвола за уредување страници во именскиот простор '''$1'''.",
'customcssjsprotected' => 'Немате дозвола за уредување на оваа страница, бидејќи содржи лични нагодувања на друг корисник.',
'ns-specialprotected'  => 'Специјални страници не може да се уредуваат.',
'titleprotected'       => "Овој наслов е заштитен од креирање од [[User:$1|$1]].
Даденото образложение е ''$2''.",

# Virus scanner
'virus-badscanner'     => "Лоша конфигурација: непознат скенер за вируси: ''$1''",
'virus-scanfailed'     => 'неуспешно скенирање (код $1)',
'virus-unknownscanner' => 'непознат антивирус:',

# Login and logout pages
'logouttitle'                => 'Одјавување на корисник',
'logouttext'                 => "'''Сега сте одјавени.'''

Можете да продолжите со користење на {{SITENAME}} анонимно или можете [[Special:UserLogin|повторно да се најавите]] под исто или различно корисничко име. Да напоменеме дека некои страници може да продолжат да се прикажуваат како да сте најавени, се додека не го исчистите кешот на вашиот веб прелистувач.",
'welcomecreation'            => '== Добредојдовте, $1! ==
Вашата корисничка сметка е креирана.
Не заборавајте да ги промените вашите [[Special:Preferences|{{SITENAME}} нагодувања]].',
'loginpagetitle'             => 'Најавување на корисник',
'yourname'                   => 'Корисничко име:',
'yourpassword'               => 'Лозинка:',
'yourpasswordagain'          => 'Повторете ја лозинката:',
'remembermypassword'         => 'Запомни го моето најавување на овој компјутер',
'yourdomainname'             => 'Вашиот домен:',
'externaldberror'            => 'Настана грешка при надворешното најавување на базата или немате дозвола да ја ажурирате вашата надворешна сметка.',
'login'                      => 'Најавување',
'nav-login-createaccount'    => 'Најавување / креирање на корисничка сметка',
'loginprompt'                => 'Морате да користите колачиња за да се најавите на {{SITENAME}}.',
'userlogin'                  => 'Најавување / создавање на корисничка сметка',
'logout'                     => 'Одјавување',
'userlogout'                 => 'Одјавување',
'notloggedin'                => 'Не сте најавени',
'nologin'                    => 'Немате корисничка сметка? $1.',
'nologinlink'                => 'Создавање нова корисничка сметка',
'createaccount'              => 'Креирање корисничка сметка',
'gotaccount'                 => 'Веќе имате корисничка сметка? $1.',
'gotaccountlink'             => 'Најавување',
'createaccountmail'          => 'по е-пошта',
'badretype'                  => 'Внесените лозинки не се исти.',
'userexists'                 => 'Корисничкото име што го внесовте веќе се користи. 
Ве молиме изберете друго име.',
'youremail'                  => 'Е-пошта:',
'username'                   => 'Корисничко име:',
'uid'                        => 'ID на корисник:',
'prefs-memberingroups'       => 'Член на {{PLURAL:$1|групата|групите}}:',
'yourrealname'               => 'Вистинско име:',
'yourlanguage'               => 'Јазик:',
'yourvariant'                => 'Варијанта:',
'yournick'                   => 'Потпис:',
'badsig'                     => 'Грешка во потписот.
Проверете ги HTML таговите.',
'badsiglength'               => 'Вашиот потпис е премногу долг.
Мора да биде со помалку од $1 {{PLURAL:$1|знак|знаци}}.',
'yourgender'                 => 'Пол:',
'gender-unknown'             => 'Неспецифицирано',
'gender-male'                => 'Машки',
'gender-female'              => 'Женски',
'prefs-help-gender'          => 'Изборно: се користи од страна на вики софтверот за генерирање пораки во зависност од наведениот пол. Оваа информација ќе биде јавно достапна.',
'email'                      => 'Е-пошта',
'prefs-help-realname'        => "'''Вистинското име''' е опционо. 
Доколку изберете да го впишете вашето име, тоа може да се искористи за давање на заслуги за вашата работа.",
'loginerror'                 => 'Грешка при најавување',
'prefs-help-email'           => 'Е-пошта е опција, која ви овозможува нова лозинка да биде пратена по е-пошта доколку ја заборавите вашата стара лозинка.
Може исто така да изберете другите корисници да контактираат со вас преку вашата корисничка страница или страница за разговор, без да биде потребно да го откриете вашиот идентитет.',
'prefs-help-email-required'  => 'Е-поштенска адреса е задолжително да се наведе.',
'nocookiesnew'               => 'Корисничката сметка е креирана, но не сте најавени. 
{{SITENAME}} користи колачиња за најавување на корисници. 
Вие имате оневозможено користењето на колачиња во вашиот веб прелистувач. 
Ве молиме овозможете ја оваа опција, па потоа најавете се со вашето корисничко име и лозинка.',
'nocookieslogin'             => '{{SITENAME}} користи колачиња за најавување на корисници. 
Вие го имате оневозможено користењето на колачиња во вашиот веб прелистувач. 
Ве молиме активирајте ги и обидете се повторно.',
'noname'                     => 'Внесовте погрешно корисничко име.',
'loginsuccesstitle'          => 'Успешно најавување',
'loginsuccess'               => 'Сега сте најавени на {{SITENAME}} како "$1".',
'nosuchuser'                 => 'Нема корисник со името "$1". 
Корисничките имиња разликуваат мали и големи букви.
Проверете да не сте направиле грешка во пишувањето, или [[Special:UserLogin/signup|создајте нова корисничка сметка]].',
'nosuchusershort'            => 'Нема корисник со името "<nowiki>$1</nowiki>".
Проверете дали правилно сте напишале.',
'nouserspecified'            => 'Мора да назначите корисничко име.',
'wrongpassword'              => 'Внесовте погрешна лозинка. Обидете се повторно.',
'wrongpasswordempty'         => 'Внесената лозинка е празна. Обидете се повторно.',
'passwordtooshort'           => 'Вашата лозинка е погрешна или прекратка. 
Мора да има најмалку {{PLURAL:$1|1 знак|$1 знаци}} и да биде различна од вашето корисничко име.',
'mailmypassword'             => 'Испрати нова лозинка по е-пошта',
'passwordremindertitle'      => 'Нова привремена лозинка за {{SITENAME}}',
'passwordremindertext'       => 'Некој (најверојатно вие, од IP адреса $1) побара нова лозинка за {{SITENAME}} ($4).
Создадена е привремена лозинка "$3" за корисничката сметка "$2".
Ако ова беше вашата намера, потребно е да се најавите и да изберете нова лозинката сега.
Вашата привремена лозинка истекува за {{PLURAL:$5|еден ден|$5 дена}}.

Ако некој друг го направил ова барање или ако сте се сетиле на вашата лозинка и веќе не сакате да ја промените, може слободно да ја игнорирате оваа порака и да продолжите да придонесувате користејќи се со старата лозинка.',
'noemail'                    => 'Не е внесена е-поштенска адреса за корисник "$1".',
'passwordsent'               => 'Нова лозинка е испратена на е-поштенската адреса регистрирана за "$1". 
Ве молиме најавете се повторно откако ќе ја примите пораката.',
'blocked-mailpassword'       => 'Вашата IP адреса е блокирана за уредување, истовремено е ставена забрана за користење на функцијата за обнова на лозинка за да се спречи можноста за злоупотреба.',
'eauthentsent'               => 'Е-пошта за потврда е испратена на назначената е-поштенска адреса. 
Пред било која е-пошта да се прати на корисничката сметка, ќе морате да ги следите инструкциите во самата е-пошта, за да потврдите дека таа корисничка сметка е навистина ваша.',
'throttled-mailpassword'     => 'Потсетување за лозинката е веќе пратено во {{PLURAL:$1|изминатиов час|изминативе $1 часа}}.
За да се спречи злоупотреба, само едно потсетување може да се праќа на {{PLURAL:$1|секој час|секои $1 часа}}.',
'mailerror'                  => 'Грешка при испраќање на е-поштата: $1',
'acct_creation_throttle_hit' => 'Корисници на ова вики користејќи ја вашата IP адреса креирале {{PLURAL:$1|1 корисничка сметка|$1 кориснички сметки}} последниве денови, при што е достигнат максималниот број на кориснички сметки предвиден и овозможен за овој период.
Како резултат на ова, посетителите кои ја користат оваа IP адреса нема да можат да креират нови сметки во овој момент.',
'emailauthenticated'         => 'Вашата е-поштенска адреса е автентицирана на $2 во $3.',
'emailnotauthenticated'      => 'Вашата е-поштенска адреса сеуште не е автентицирана. 
Нема да биде испратена е-пошта во ниту еден од следниве случаи.',
'noemailprefs'               => 'Наведете е-поштенска адреса за да функционираат следниве својства.',
'emailconfirmlink'           => 'Потврдете ја вашата е-поштенска адреса',
'invalidemailaddress'        => 'Е-поштенската адреса не може да биде прифатена бидејќи има неважечки формат. 
Ве молиме, внесете важечки формат или испразнете го тоа поле.',
'accountcreated'             => 'Корисничката сметка е направена',
'accountcreatedtext'         => 'Корисничката сметка за $1 беше направена.',
'createaccount-title'        => 'Правење сметка за {{SITENAME}}',
'createaccount-text'         => 'Некој направил сметка со вашата е-поштенска адреса на {{SITENAME}} ($4) со име „$2“ и  лозинка „$3“.
Би требало сега да се пријавите и да ја промените вашата лозинка.

Можете да ја занемарите оваа порака ако сметката била направена по грешка.',
'login-throttled'            => 'Скоро имате направено обид за најавување со оваа лозинка.
Почекајте малку пред да се обидете повторно.',
'loginlanguagelabel'         => 'Јазик: $1',

# Password reset dialog
'resetpass'                 => 'Промена на лозинка',
'resetpass_announce'        => 'Најавени сте со привремена лозинка пратена по е-пошта.
За да го завршите пријавувањето, мора да поставите нова лозинка овде:',
'resetpass_text'            => '<!-- Додадете текст тука -->',
'resetpass_header'          => 'Промена на корисничка лозинка',
'oldpassword'               => 'Стара лозинка:',
'newpassword'               => 'Нова лозинка:',
'retypenew'                 => 'Повторно внеси нова лозинка:',
'resetpass_submit'          => 'Поставете лозинка и најавете се',
'resetpass_success'         => 'Вашата лозинка беше успешно променета! Најавувањето е во тек...',
'resetpass_bad_temporary'   => 'Неважечка привремена лозинка.
Можно е веќе да сте ја промениле вашата лозинка или да сте побарале нова привремена лозинка.',
'resetpass_forbidden'       => 'Лозинките не може да се менуваат',
'resetpass-no-info'         => 'Мора да бидете најавени ако сакате да имате директен пристап до оваа страница.',
'resetpass-submit-loggedin' => 'Промени лозинка',
'resetpass-wrong-oldpass'   => 'Погрешна привремена или тековна лозинка.
Можеби веќе ја имате успешно променето вашата лизинка или сте побарале нова привремена лозинка.',
'resetpass-temp-password'   => 'Привремена лозинка:',
'resetpass-no-others'       => 'Вие не можете да ресетирате лозинка на други корисници.',
'resetpass-log'             => 'Дневник на ресетирања на лозинка',
'resetpass-logtext'         => 'Подолу следи листа на корисници чии лозинки се ресетирани од страна на администратор.',
'resetpass-logentry'        => 'променета лозинката за $1',
'resetpass-comment'         => 'Причина за ресетирање на лозинка:',

# Edit page toolbar
'bold_sample'     => 'Задебелен текст',
'bold_tip'        => 'Задебелен текст',
'italic_sample'   => 'Закосен текст',
'italic_tip'      => 'Закосен текст',
'link_sample'     => 'Наслов на врска',
'link_tip'        => 'Внатрешна врска',
'extlink_sample'  => 'http://www.example.com наслов на врска',
'extlink_tip'     => 'Надворешна врска (наведи и http:// префикс)',
'headline_sample' => 'Наслов',
'headline_tip'    => 'Ниво 2 наслов',
'math_sample'     => 'Овде вметни формула',
'math_tip'        => 'Математичка формула (LaTeX)',
'nowiki_sample'   => 'Овде внесете неформатиран текст',
'nowiki_tip'      => 'Игнорирај вики форматирање',
'image_sample'    => 'Пример.jpg',
'image_tip'       => 'Вметната слика',
'media_sample'    => 'Пример.ogg',
'media_tip'       => 'Врска кон податотека',
'sig_tip'         => 'Вашиот потпис со време',
'hr_tip'          => 'Хоризонтална линија',

# Edit pages
'summary'                          => 'Опис:',
'subject'                          => 'Наслов:',
'minoredit'                        => 'Ова е ситна промена',
'watchthis'                        => 'Набљудувај ја оваа страница',
'savearticle'                      => 'Зачувај страница',
'preview'                          => 'Преглед',
'showpreview'                      => 'Прикажи преглед',
'showlivepreview'                  => 'Преглед во живо',
'showdiff'                         => 'Прикажи промени',
'anoneditwarning'                  => "'''Предупредување:''' Не сте најавени. Вашата IP адреса ќе биде забележана во историјата на страницата.",
'missingsummary'                   => "'''Потсетник:''' Не внесовте опис на измените. Ако притиснете Зачувај повторно, вашите измени ќе се зачуваат без опис.",
'missingcommenttext'               => 'Ве молиме внесете коментар подолу.',
'missingcommentheader'             => "'''Потсетување:''' Не внесовте наслов за овој коментар. Ако повторно кликнете на Зачувај, вашето уредување ќе биде запамтено без наслов.",
'summary-preview'                  => 'Изглед на описот:',
'subject-preview'                  => 'Преглед на предметот/насловот:',
'blockedtitle'                     => 'Корисникот е блокиран',
'blockedtext'                      => "<big>'''Вашето корисничко име или IP адреса се блокирани.'''</big>

Блокирањето е направено од страна на $1. 
Даденото образложение е ''$2''.

* Почеток на блокирањето: $8
* Истекување на блокирањето: $6
* Корисникот што требало да биде блокиран: $7

Може да контактирате со $1 или некој друг [[{{MediaWiki:Grouppage-sysop}}|администратор]] за да разговарате во врска со блокирањето.
Можете да ја искористите можноста „Е-пошта до овој корисник“ ако е назначена важечка е-поштенска адреса во [[Special:Preferences|вашите нагодувања]] и не ви е забрането да ја користите.
Вашата сегашна IP адреса е $3, а ID-то на блокирањето е #$5. 
Ве молиме наведете ги сите детали прикажани погоре, во вашата евентуална реакција.",
'autoblockedtext'                  => "Вашата IP адреса е автоматски блокирана бидејќи била користена од страна на друг корисник, кој бил блокиран од $1.
Даденото образложение е следново:

:''$2''

* Почеток на блокирањето: $8
* Истекување на блокирањето: $6
* Со намера да се блокира: $7

Може да контактирате со $1 или некој друг [[{{MediaWiki:Grouppage-sysop}}|администратор]] за да разговарате во врска со ова блокирање.

Имајте во предвид дека можеби нема да можете да ја искористите можноста „Е-пошта до овој корисник“ доколку не е назначена важечка е-поштенска адреса во [[Special:Preferences|вашите нагодувања]] и ви е забрането користитење на истата.

Вашата IP адреса е $3, a ID-то на блокирањеto е $5.
Ве молиме наведете ги овие детали доколку реагирате на блокирањето.",
'blockednoreason'                  => 'не е дадено образложение',
'blockedoriginalsource'            => "Кодот на '''$1''' е прикажан подолу:",
'blockededitsource'                => "Текстот на '''вашите уредувања''' на '''$1''' е прикажан подолу:",
'whitelistedittitle'               => 'Мора да сте најавени доколку сакате да уредувате',
'whitelistedittext'                => 'Мора да сте $1 за да уредувате страници.',
'confirmedittitle'                 => 'Потребна е потврда за е-поштенската адреса за уредување',
'confirmedittext'                  => 'Морате да ја потврдите вашата е-поштенска адреса пред да уредувате страници. 
Поставете ја и валидирајте ја вашата е-поштенска адреса преку вашите [[Special:Preferences|нагодувања]].',
'nosuchsectiontitle'               => 'Нема такваа секција',
'nosuchsectiontext'                => 'Се обидовте да уредувате секција која не постои. Бидејќи не постои секција $1, нема каде да се зачуваат вашите уредувања.',
'loginreqtitle'                    => 'Потребно најавување',
'loginreqlink'                     => 'најавување',
'loginreqpagetext'                 => 'Морате да се $1 за да ги видите другите страници.',
'accmailtitle'                     => 'Лозинката е испратена.',
'accmailtext'                      => "Случајно генерирана лозинка за [[User talk:$1|$1]] е испратена на $2.

Лозинката за оваа нова корисничка сметка може да биде променета на страницата за ''[[Special:ChangePassword|промена на лозинка]]'' после најавувањето.",
'newarticle'                       => '(нова)',
'newarticletext'                   => "Следевте врска која води до страница која сè уште не постои. 
За да ја креирате страницата, почнете со пишување во долното прозорче (видете ја [[{{MediaWiki:Helppage}}|страницата за помош]] за повеќе информации). Ако сте овде по грешка, само притиснете го копчето '''назад''' во вашиот веб прелистувач.",
'anontalkpagetext'                 => "''Ова е страница за разговор со анонимен корисник кој сеуште не регистрирал корисничка сметка или не ја користи. Затоа мораме да ја користиме неговата нумеричка [[IP адреса]] за негова идентификација. Една IP адреса може да биде делена од повеќе корисници. Ако сте анонимен корисник и сметате дека кон вас се упатени ирелевантни коментари, ве молиме [[Special:UserLogin|создадете корисничка сметка]] или [[Special:UserLogin|најавете се]] за да избегнете поистоветување со други анонимни корисници.''",
'noarticletext'                    => 'Моментално нема текст на оваа страница.
Можете да направите [[Special:Search/{{PAGENAME}}|пребарување за овој наслов на страница]] во други страници, 
<span class="plainlinks">[{{fullurl:Special:Log|page={{urlencode:{{FULLPAGENAME}}}}}} пребарување во дневниците],
или [{{fullurl:{{FULLPAGENAME}}|action=edit}} да ја уредите оваа страница]</span>.',
'userpage-userdoesnotexist'        => 'Корисничката сметка „$1“ не е регистрирана. 
Ве молиме размислете дали навистина сакате да ја создадете/уредите оваа страница.',
'clearyourcache'                   => "'''Забелешка - По секое зачувување морате да го исчистите кешот на вашиот веб прелистувач за да ги видите промените.'''
'''Mozilla / Firefox / Safari:''' држете ''Shift'' додека кликате на ''Reload'' или одеднаш притиснете ''Ctrl-F5'' или ''Ctrl-R'' (''Command-R'' на Мекинтош);
'''Konqueror:''' кликнете на ''Reload'' или притиснете ''F5'';
'''Opera:''' избришете го кешот во ''Tools → Preferences'';
'''Internet Explorer:''' држете ''Ctrl'' додека притискате на ''Refresh'' или притиснете ''Ctrl-F5''.",
'usercssjsyoucanpreview'           => "'''Совет:''' Користете го копчето '''Прегледај''' за да го испробате новиот CSS/JS код пред зачувувањето.",
'usercsspreview'                   => "'''Запомнете дека ова е само преглед на вашиот кориснички CSS код, страницата сеуште не е зачувана!'''",
'userjspreview'                    => "'''Запомнете дека ова е само преглед на вашиот JavaScript код, страницата сеуште не е зачувана!'''",
'userinvalidcssjstitle'            => "'''Предупредување:''' Нема маска \"\$1\". 
Запамтете дека сопствените .css и .js страници имаат имиња со мали букви, пр. {{ns:user}}:Некој/monobook.css наместо {{ns:user}}:Некој/Monobook.css.",
'updated'                          => '(Ажурирано)',
'note'                             => "'''Забелешка:'''",
'previewnote'                      => "'''Ова е само преглед, страницата сеуште не е зачувана!'''",
'previewconflict'                  => 'Овој преглед прикажува како ќе изгледа текстот внесен во горниот дел откако ќе се зачува страницата.',
'session_fail_preview'             => "'''Извинете! Не можевме да го процесираме вашето уредување заради губење на сесиски податоци.
Обидете се повторно. Ако сеуште не функционира, пробајте да се одјавите и повторно да се најавите.'''",
'session_fail_preview_html'        => "'''Извинете! Не можеше да се процесира вашето уредување заради губење на сесиските податоци.'''

''Поради тоа што {{SITENAME}} има овозможено HTML, прегледот е скриен од причини на безбедност од страна на JavaScript напади.''

'''Ако е ова е дозволен обид за уредување, ве молиме обидете се повторно. Ако сеуште не работи, пробајте да се [[Special:UserLogout|одјавите]] и повторно да се најавите.'''",
'token_suffix_mismatch'            => "'''Вашето уредување е одбиено затоа што вашиот веб-пребарувач направил проблеми со интерпукциските знаци во токенот за уредување.
Уредувањето не е прифатено за да се спречи несакана промена на текстот на страницата.
Ова понекогаш се случува кога користите неисправен веб-базиран анонимен прокси-сервис.'''",
'editing'                          => 'Уредување на $1',
'editingsection'                   => 'Уредување на $1 (секција)',
'editingcomment'                   => 'Уредување на $1 (нова секција)',
'editconflict'                     => 'Конфликтни уредувања: $1',
'explainconflict'                  => "Некој друг ја променил страницата откако почнавте да ја уредувате.
Горниот дел за текст ја содржи страницата како што сега постои.
Вашите промени се прикажани во долниот дел.
Ќе морате да ги внесете вашите промени со постоечкиот текст.
'''Само''' текстот во горниот дел ќе биде зачуван кога ќе притиснете на \"Зачувај страница\".",
'yourtext'                         => 'Вашиот текст',
'storedversion'                    => 'Зачувана верзија',
'nonunicodebrowser'                => "'''ПРЕДУПРЕДУВАЊЕ: Вашиот прелистувач не е Уникод компатибилен, ве молиме променете го пред да уредувате статија.'''",
'editingold'                       => "'''ПРЕДУПРЕДУВАЊЕ: Менувате застарена верзија на оваа страница.
Ако ја зачувате, сите промени по оваа верзија ќе бидат изгубени.'''",
'yourdiff'                         => 'Разлики',
'copyrightwarning'                 => "Имајте во предвид дека сите придонеси кон {{SITENAME}} се смета дека се издадени под $2 (видете $1 за детали). 
Ако не сакате вашите текстови да бидат слободно уредувани и разменувани, не ги праќајте овде.<br />
Исто така потврдувате дека вие сте автор на текстот, или сте го копирале од некој јавен домен или сличен слободен извор.
'''НЕ ПРАЌАЈТЕ ТЕКСТОВИ ЗАШТИТЕНИ СО АВТОРСКИ ПРАВА БЕЗ ДОЗВОЛА!'''",
'copyrightwarning2'                => "Ве молиме, имајте во предвид дека сите придонеси кон {{SITENAME}} можат да бидат уредувани, менувани или отстранети од други корисници. Ако не сакате вашиот текст да биде менуван и редистрибуиран слободно, не го испраќајте овде.<br />
Исто така потврдувате дека текстот сте го напишале сами, или сте го копирале од јавен домен или сличен слободен извор (видетe $1 за детали).<br />
'''НЕ ПРАЌАЈТЕ ТЕКСТОВИ ЗАШТИТЕНИ СО АВТОРСКИ ПРАВА БЕЗ ДОЗВОЛА!'''",
'longpagewarning'                  => "'''ПРЕДУПРЕДУВАЊЕ: Оваа страница има $1 kB;
некои прелистувачи може да имаат проблеми со уредување на страниците поголеми од 32 kB.
Размислете за разделување на страницата на помали делови.'''",
'longpageerror'                    => "'''ГРЕШКА: Текстот што го внесовте е голем $1 килобајти, што е повеќе од максималните $2 килобајти. Не може да се зачува.'''",
'readonlywarning'                  => "'''ПРЕДУПРЕДУВАЊЕ: Базата на податоци е заклучена заради одржување, па нема да можете да ги зачувате вашите промени сега.
Пробајте да го зачувате текстот локално (cut & paste) во некоја податотека и да го пратите подоцна.'''

Администраторот кој ја заклучил базата на податоци го дал следново објаснување: $1",
'protectedpagewarning'             => "'''ПРЕДУПРЕДУВАЊЕ:  Оваа страница е заклучена, така што само корисници со администраторски привилегии можат да ја менуваат.'''",
'semiprotectedpagewarning'         => "'''Забелешка:''' Оваа страница е заклучена и само регистрирани корисници може да ја уредуваат.",
'cascadeprotectedwarning'          => "'''Предупредување:''' Оваа страница е заклучена, така што можат да ја уредуваат само корисници со администраторски привилегии, бидејќи е вклучена во {{PLURAL:$1|следнава „преносно-заштитена“ страница|следниве „преносно-заштитени“ страници}}:",
'titleprotectedwarning'            => "'''ПРЕДУПРЕДУВАЊЕ:  Оваа страница е заклучена, потребни [[Special:ListGroupRights|специфични права]] за да се креира.'''",
'templatesused'                    => 'Шаблони користени на оваа страница:',
'templatesusedpreview'             => 'Шаблони користени во овој преглед:',
'templatesusedsection'             => 'Шаблони користени во оваа секција:',
'template-protected'               => '(заштитен)',
'template-semiprotected'           => '(полу-заштитен)',
'hiddencategories'                 => 'Оваа страница припаѓа на {{PLURAL:$1|1 скриена категорија|$1 скриени категории}}:',
'edittools'                        => '<!-- Овој текст ќе се прикаже под формите за уредување и подигнување. -->',
'nocreatetitle'                    => 'Креирањето на нови страници е ограничено',
'nocreatetext'                     => '{{SITENAME}} ја има ограничена можноста за создавање нови страници.
Можете да се вратите назад и да уредувате постоечка страница или [[Special:UserLogin|најавете се или создадете нова корисничка сметка]].',
'nocreate-loggedin'                => 'Немате дозвола да создавате нови страници.',
'permissionserrors'                => 'Грешки во правата',
'permissionserrorstext'            => 'Немате дозвола да го направите тоа, заради {{PLURAL:$1|следнава причина|следниве причини}}:',
'permissionserrorstext-withaction' => 'Немате дозвола за $2, заради {{PLURAL:$1|следнава причина|следниве причини}}:',
'recreate-deleted-warn'            => "'''Внимание: Создавате страница што претходно била бришена.'''

Размислете дали е правилно да продолжите со уредување на оваа страница.
Погледнете го дневникот на бришења за оваа страница заради преглед:",
'deleted-notice'                   => 'Оваа страница била претходно бришена.
За ваша информација подолу може да го погледнете дневникот на бришења за оваа страница.',
'deletelog-fulllog'                => 'Погледни комплетен дневник',
'edit-hook-aborted'                => 'Уредувањето прекинато со кука.
Не е дадено никакво објаснување.',
'edit-gone-missing'                => 'Не е возможно да се ажурира страницата.
Изгледа дека во меѓувреме е избришана од некого.',
'edit-conflict'                    => 'Конфликтно уредување.',
'edit-no-change'                   => 'Вашите уредувања беа игнорирани, бидејќи не се направени промени врз текстот.',
'edit-already-exists'              => 'Не може да се креира нова сраница.
Таа веќе постои.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Предупредување: Оваа страница користи премногу повикувања на parser функции.

Треба да има помалку од $2 {{PLURAL:$2|повикување|повикувања}} а сега има {{PLURAL:$1|$1 повикување|$1 повикувања}}.',
'expensive-parserfunction-category'       => 'Страници со премногу повикувања на parser функции',
'post-expand-template-inclusion-warning'  => 'Предупредување: Големината на вклучените шаблони е преголема.
Некои шаблони нема да бидат вклучени.',
'post-expand-template-inclusion-category' => 'Страници каде што големината на вклучените шаблони е пречекорена',
'post-expand-template-argument-warning'   => 'Предупредување: Оваа страница содржи најмалку еден аргумент на шаблон кој е со преголема должина.
Таквите аргументи ќе бидат изземени при парсирањето.',
'post-expand-template-argument-category'  => 'Страници кои содржат изземени аргументи на шаблони',
'parser-template-loop-warning'            => 'Детектиран циклус во шаблонот: [[$1]]',
'parser-template-recursion-depth-warning' => 'Ограничувањето на рекурзивната длабочина надмината во шаблонот ($1)',

# "Undo" feature
'undo-success' => 'Уредувањето може да се откаже. 
Ве молиме споредете ги промените со претходната верзија за да проверите дали тоа е сигурно она што сакате да го направите, а потоа зачувајте ги промените за да го завршите откажувањето на претходното уредување.',
'undo-failure' => 'Уредувањето не можеше да се откаже заради други конфликтни уредувања кои настанале во меѓувреме.',
'undo-norev'   => 'Измената не можеше да биде вратена бидејќи не постои или била избришана.',
'undo-summary' => 'Откажано уредување $1 од ([[Special:Contributions/$2|$2]] | [[Разговор со корисник:$2|Разговор]])',

# Account creation failure
'cantcreateaccounttitle' => 'Не може да се создаде корисничка сметка',
'cantcreateaccount-text' => "Создавањето на корисничка сметка од оваа IP адреса ('''$1''') е блокирано од страна на [[User:$3|$3]].

Образложението дадено од страна на $3 е ''$2''",

# History pages
'viewpagelogs'           => 'Преглед на дневници за оваа страница',
'nohistory'              => 'Не постои историја на уредување за оваа страница.',
'currentrev'             => 'Тековна ревизија',
'currentrev-asof'        => 'Последна ревизија од $1',
'revisionasof'           => 'Ревизија од $1',
'revision-info'          => 'Ревизија од $1; направена од $2', # Additionally available: $3: revision id
'previousrevision'       => '← Претходна ревизија',
'nextrevision'           => 'Следна ревизија →',
'currentrevisionlink'    => 'Последна ревизија',
'cur'                    => 'мом',
'next'                   => 'след',
'last'                   => 'посл',
'page_first'             => 'прв',
'page_last'              => 'последен',
'histlegend'             => 'Diff селекција: селектирајте ги ревизиите кои сакате да ги споредите и притиснете ентер или копчето на дното од страницата.<br />
Легенда: (мом) = разлика со последната ревизија,(посл) = разлика со претходната верзија, С = ситна промена.',
'history-fieldset-title' => 'Прелистување на историја',
'deletedrev'             => '[избришано]',
'histfirst'              => 'Први',
'histlast'               => 'Последни',
'historysize'            => '({{PLURAL:$1|1 бајт|$1 бајти}})',
'historyempty'           => '(празно)',

# Revision feed
'history-feed-title'          => 'Историја на ревидирање',
'history-feed-description'    => 'Историја на ревидирање за оваа страница на викито',
'history-feed-item-nocomment' => '$1 на $2', # user at time
'history-feed-empty'          => 'Бараната страница не постои.
Може била избришана од викито или преименувана.
Обидете се да [[Special:Search|пребарате низ викито]] за релевантни нови страници.',

# Revision deletion
'rev-deleted-comment'            => '(избришан коментар)',
'rev-deleted-user'               => '(избришано корисничко име)',
'rev-deleted-event'              => '(избришан запис на акција)',
'rev-deleted-text-permission'    => 'Ревизијата на оваа страница е избришана од јавните архиви.
Можеби има детали во [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} дневникот на бришења].',
'rev-deleted-text-view'          => 'Ревизијата на оваа страница беше избришана од јавните архиви.
Како администратор на {{SITENAME}} вие можете да ја погледнете;
можеби има повеќе детали во [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} дневникот на бришења].',
'rev-delundel'                   => 'прикажи/сокриј',
'revisiondelete'                 => 'Избриши/врати ревизии',
'revdelete-nooldid-title'        => 'Бараната измена не постои',
'revdelete-nooldid-text'         => 'Не сте посочиле измена (измени) за да се изврши оваа функција или посочената измена не постои или се обидувате да ја сокриете моменталната измена.',
'revdelete-nologtype-title'      => 'Не е наведен тип на дневник',
'revdelete-nologtype-text'       => 'Немате наведено тип на дневник за кој треба да се изврши оваа акција.',
'revdelete-toomanytargets-title' => 'Премногу цели',
'revdelete-toomanytargets-text'  => 'Имате наведено премногу цели за извршување на оваа акција.',
'revdelete-nologid-title'        => 'Погрешно внесен запис во дневник.',
'revdelete-selected'             => "'''{{PLURAL:$2|Избрана ревизија|Избрани ревизии}} од [[:$1]]:'''",
'logdelete-selected'             => "'''{{PLURAL:$1|Одбран запис од дневникот|Одбрани записи од дневникот}}:'''",
'revdelete-text'                 => "'''Избришаните измени и настани сѐ уште ќе се појавуваат во историјата на страницата и дневниците, но делови од нивната содржина ќе бидат недостапни за јавноста.'''

Други администратори на {{SITENAME}} сѐ уште ќе имаат пристап до скриената содржина и ќе можат да ја вратат преку истиот интерфејс, освен ако не се поставени дополнителни ограничувања.

Потврдете дека сакате да го направите ова, дека сте свесни за последиците и дека го правите ова согласно со [[{{MediaWiki:Policy-url}}|политиката]].",
'revdelete-legend'               => 'Постави ограничувања за видливост',
'revdelete-hide-text'            => 'Сокриј текст на ревизија',
'revdelete-hide-name'            => 'Сокриј акција и нејзина цел',
'revdelete-hide-comment'         => 'Сокриј опис на уредување',
'revdelete-hide-user'            => 'Сокриј корисничко име/IP на уредувачи',
'revdelete-hide-restricted'      => 'Постави ги овие ограничувања и за Систем операторите и заклучи го овој интерфејс',
'revdelete-suppress'             => 'Сокриј податоци од Систем оператори и други корисници',
'revdelete-hide-image'           => 'Сокриј содржина на податотека',
'revdelete-unsuppress'           => 'Отстрани ограничувања на обновени ревизии',
'revdelete-log'                  => 'Опис на дневнички запис:',
'revdelete-submit'               => 'Примени на селектирани ревизии',
'revdelete-logentry'             => 'променето видливост на ревизија на [[$1]]',
'logdelete-logentry'             => 'променета видливост на настан од [[$1]]',
'revdelete-success'              => "'''Успешно нагодена видливост на ревизија.'''",
'logdelete-success'              => "'''Успешно нагодување на дневник на видливост.'''",
'revdel-restore'                 => 'Промена на видливост',
'pagehist'                       => 'Историја на страница',
'deletedhist'                    => 'Историја на бришења',
'revdelete-content'              => 'содржина',
'revdelete-summary'              => 'опис на уредување',
'revdelete-uname'                => 'корисничко име',
'revdelete-restricted'           => 'применети ограничувања на систем оператори',
'revdelete-unrestricted'         => 'отстранети ограничувања за систем оператори',
'revdelete-hid'                  => 'сокриј $1',
'revdelete-unhid'                => 'прикажи $1',
'revdelete-log-message'          => '$1 од $2 {{PLURAL:$2|ревизија|ревизии}}',
'logdelete-log-message'          => '$1 од $2 {{PLURAL:$2|настан|настани}}',

# Suppression log
'suppressionlog'     => 'Дневник на сокривања',
'suppressionlogtext' => 'Подолу е листата на бришења и блокирања каде има и содржини скриени и за администраторите.<br />
Види [[Special:IPBlockList|Листа на блокирани IP адреси]] за листа на моментални банирања и блокирања.',

# History merging
'mergehistory'                     => 'Спојување на истории на страница',
'mergehistory-header'              => 'Оваа страница овозможува спојување на ревизии на изворна страница во нова (друга) страница.
Претходно проверете дека таа промена ќе го сочува историскиот континуитетот на страницата.',
'mergehistory-box'                 => 'Спојување на ревизии на две страници:',
'mergehistory-from'                => 'Изворна страница:',
'mergehistory-into'                => 'Целна страница:',
'mergehistory-list'                => 'Можно спојување на историјата на уредување',
'mergehistory-merge'               => 'Следните ревизии на [[:$1]] можат да се спојат во [[:$2]].
Направете селекција само на оние ревизии креирани до специфицираното време.
Предупредуваме дека ако ги користите навигационите врски селекцијата ќе биде изгубена.',
'mergehistory-go'                  => 'Приказ на уредувања кои можат да се спојат',
'mergehistory-submit'              => 'Спојување на ревизии',
'mergehistory-empty'               => 'Нема ревизии кои можат да се спојат.',
'mergehistory-success'             => '$3 {{PLURAL:$3|ревизија|ревизии}} на [[:$1]] успешно {{PLURAL:$3|е споена|се споени}} во [[:$2]].',
'mergehistory-fail'                => 'Не е возможно да се направи спојување на историјата, проверете ја страницата и временските параметри.',
'mergehistory-no-source'           => 'Изворната страница $1 не постои.',
'mergehistory-no-destination'      => 'Целната страница $1 не постои.',
'mergehistory-invalid-source'      => 'Изворната страница мора да има валиден наслов.',
'mergehistory-invalid-destination' => 'Целната страница мора да има валиден наслов.',
'mergehistory-autocomment'         => 'Споено [[:$1]] во [[:$2]]',
'mergehistory-comment'             => 'Споено [[:$1]] во [[:$2]]: $3',
'mergehistory-same-destination'    => 'Изворната и целната страница треба да се различни',

# Merge log
'mergelog'           => 'Дневник на спојувања на страници',
'pagemerge-logentry' => 'споено [[$1]] во [[$2]] (ревизии до $3)',
'revertmerge'        => 'Одвојување',
'mergelogpagetext'   => 'Следи листа на скорешни спојувања на истории на страници.',

# Diffs
'history-title'           => 'Историја на ревизии за „$1“',
'difference'              => '(Разлика меѓу ревизии)',
'lineno'                  => 'Линија $1:',
'compareselectedversions' => 'Споредба на избрани верзии',
'visualcomparison'        => 'Визуелна споредба',
'wikicodecomparison'      => 'Викитекст споредба',
'editundo'                => 'откажи',
'diff-multi'              => '({{PLURAL:$1|Не е прикажана една меѓуревзија|Не се прикажани $1 меѓуревизии}})',
'diff-movedto'            => 'преместено во $1',
'diff-styleadded'         => '$1 додаден стил',
'diff-added'              => '$1 додадено',
'diff-changedto'          => 'променето во $1',
'diff-movedoutof'         => 'исфрлено надвор од $1',
'diff-styleremoved'       => '$1 избришано стил',
'diff-removed'            => '$1 избришано',
'diff-changedfrom'        => 'променето од $1',
'diff-src'                => 'извор',
'diff-withdestination'    => 'со дестинација $1',
'diff-with'               => '&#32;со $1 $2',
'diff-with-additional'    => '$1 $2',
'diff-with-final'         => '&#32;и $1 $2',
'diff-width'              => 'ширина',
'diff-height'             => 'висина',
'diff-p'                  => "'''параграф'''",
'diff-blockquote'         => "'''цитат'''",
'diff-h1'                 => "'''наслов (ниво 1)'''",
'diff-h2'                 => "'''наслов (ниво 2)'''",
'diff-h3'                 => "'''наслов (ниво 3)'''",
'diff-h4'                 => "'''наслов (ниво 4)'''",
'diff-h5'                 => "'''наслов (ниво 5)'''",
'diff-pre'                => "'''префроматиран текст'''",
'diff-div'                => "'''дел од текст'''",
'diff-ul'                 => "'''неподредена листа'''",
'diff-ol'                 => "'''подредена листа'''",
'diff-li'                 => "'''член на листа'''",
'diff-table'              => "'''табела'''",
'diff-tbody'              => "'''содржина'''",
'diff-tr'                 => "'''редица'''",
'diff-td'                 => "'''клетка'''",
'diff-th'                 => "'''заглавје'''",
'diff-br'                 => "'''прекршување на ред'''",
'diff-hr'                 => "'''хоризонтална линија'''",
'diff-code'               => "'''компјутерски код'''",
'diff-dl'                 => "'''дефинициона листа'''",
'diff-dt'                 => "'''дефиниционен поим'''",
'diff-dd'                 => "'''дефиниција'''",
'diff-input'              => "'''влез'''",
'diff-form'               => "'''формулар'''",
'diff-img'                => "'''слика'''",
'diff-span'               => "'''span'''",
'diff-a'                  => "'''врска'''",
'diff-i'                  => "'''италик'''",
'diff-b'                  => "'''задебелено'''",
'diff-strong'             => "'''задебелено'''",
'diff-em'                 => "'''зголемено'''",
'diff-font'               => "'''фонт'''",
'diff-big'                => "'''големо'''",
'diff-del'                => "'''избришано'''",
'diff-tt'                 => "'''фиксна ширина'''",
'diff-sub'                => "'''подпис'''",
'diff-sup'                => "'''надпис'''",
'diff-strike'             => "'''прецртано'''",

# Search results
'searchresults'                    => 'Резултати од пребарувањето',
'searchresults-title'              => 'Резултати од пребарувањето за "$1"',
'searchresulttext'                 => 'За повеќе информации во врска со пребарување на {{SITENAME}}, погледнете [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'Пребарувате \'\'\'[[:$1]]\'\'\' на ([[Special:Prefixindex/$1|сите страници кои започнуваат со "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|сите страници кои водат до "$1"]])',
'searchsubtitleinvalid'            => "Пребарувавте '''$1'''",
'noexactmatch'                     => "'''Нема страница насловена како „$1“.''' 
Можете да ја [[:$1|креирате оваа страница]].",
'noexactmatch-nocreate'            => "'''Не постои страница насловена како \"\$1\".'''",
'toomanymatches'                   => 'Премногу резултати од пребарувањето, ве молиме обидете се со различен упит',
'titlematches'                     => 'Насловот на статијата одговара',
'notitlematches'                   => 'Ниеден наслов на страница не одговара',
'textmatches'                      => 'Текстот на статијата одговара',
'notextmatches'                    => 'Ниеден текст во статиите не одговара',
'prevn'                            => 'претходни $1',
'nextn'                            => 'следни $1',
'prevn-title'                      => '{{PLURAL:$1|Претходен|Претходни}} $1 {{PLURAL:$1|резултат|резултати}}',
'nextn-title'                      => '{{PLURAL:$1|Следен|Следни}} $1 {{PLURAL:$1|резултат|резултати}}',
'shown-title'                      => 'Прикажи $1 {{PLURAL:$1|резултат|резултати}} на страница',
'viewprevnext'                     => 'Види ($1) ($2) ($3).',
'searchmenu-legend'                => 'Опции за пребарување',
'searchmenu-exists'                => "'''Постои страница именувана како \"[[\$1]]\" на ова вики'''",
'searchmenu-new'                   => "Создади ја страницата ''[[:$1]]'' на ова вики!'''",
'searchhelp-url'                   => 'Help:Содржина',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Прелистување на страници со овој префикс]]',
'searchprofile-articles'           => 'Статии',
'searchprofile-articles-and-proj'  => 'Статии & проектни страници',
'searchprofile-project'            => 'Проектни страници',
'searchprofile-images'             => 'Податотеки',
'searchprofile-everything'         => 'Се',
'searchprofile-advanced'           => 'Напредно',
'searchprofile-articles-tooltip'   => 'Пребарај во $1',
'searchprofile-project-tooltip'    => 'Пребарај во $1',
'searchprofile-images-tooltip'     => 'Пребарај податотеки',
'searchprofile-everything-tooltip' => 'Пребарај низ сета содржина (вклучително и страници за разговор)',
'searchprofile-advanced-tooltip'   => 'Пребарај во посебни именски простори',
'prefs-search-nsdefault'           => 'Пребарување по основно:',
'prefs-search-nscustom'            => 'Пребарај посебни именски простори:',
'search-result-size'               => '$1 ({{PLURAL:$2|1 збор|$2 збора}})',
'search-result-score'              => 'Релевантност: $1%',
'search-redirect'                  => '(пренасочување $1)',
'search-section'                   => '(секција $1)',
'search-suggest'                   => 'Дали мислевте на: $1',
'search-interwiki-caption'         => 'Сестрински проекти',
'search-interwiki-default'         => '$1 резултати:',
'search-interwiki-more'            => '(уште)',
'search-mwsuggest-enabled'         => 'со сугестии',
'search-mwsuggest-disabled'        => 'без сугестии',
'search-relatedarticle'            => 'Поврзано',
'mwsuggest-disable'                => 'Оневозможи AJAX сугестии',
'searchrelated'                    => 'поврзано',
'searchall'                        => 'се',
'showingresults'                   => "Подолу {{PLURAL:$1|е прикажан '''1''' резултат|се прикажани '''$1''' резултати}} почнувајќи од #'''$2'''.",
'showingresultsnum'                => "Подолу {{PLURAL:$3|е прикажан '''1''' резултат|се прикажани '''$3''' резултати}} почнувајќи од '''$2'''.",
'showingresultstotal'              => "Подолу {{PLURAL:$4|е прикажан '''$1''' резултат|се прикажани '''$1 - $2''' од '''$3''' резултати}}",
'nonefound'                        => "'''Напомена''': Само некои именски простори се пребаруваат по основно. 
Обидете се со додавање на префиксот ''all:'' за да пребарувате низ сите содржини (вклучувајќи страници за разговор, шаблони, итн) или користете го бараниот именски простор како префикс.",
'search-nonefound'                 => 'Нема резултати кои го задоволуваат упитот.',
'powersearch'                      => 'Напредно пребарување',
'powersearch-legend'               => 'Напредно пребарување',
'powersearch-ns'                   => 'Пребарување во именски простори:',
'powersearch-redir'                => 'Листа на пренасочувања',
'powersearch-field'                => 'Пребарување на',
'search-external'                  => 'Надворешно пребарување',
'searchdisabled'                   => '{{SITENAME}} пребарувањето е оневозможено. 
Во меѓувреме, можете да пребарувате преку Google.
Да напоменеме дека нивното индексирање на {{SITENAME}} содржините може да биде застарено.',

# Preferences page
'preferences'               => 'Нагодувања',
'mypreferences'             => 'Мои нагодувања',
'prefs-edits'               => 'Број на уредувања:',
'prefsnologin'              => 'Не сте најавени',
'prefsnologintext'          => 'Мора да бидете <span class="plainlinks">[{{fullurl:Special:UserLogin|returnto=$1}} најавени]</span> за промена на вашите кориснички нагодувања.',
'prefsreset'                => 'Воспоставени се стандардните нагодувања.',
'qbsettings'                => 'Лента за брз избор',
'qbsettings-none'           => 'Без мени',
'qbsettings-fixedleft'      => 'Фиксирано лево',
'qbsettings-fixedright'     => 'Фиксирано десно',
'qbsettings-floatingleft'   => 'Пловечко лево',
'qbsettings-floatingright'  => 'Пловечки десно',
'changepassword'            => 'Промени лозинка',
'skin'                      => 'Маска',
'skin-preview'              => 'Прегледај',
'math'                      => 'Математика',
'dateformat'                => 'Формат на датум',
'datedefault'               => 'Небитно',
'datetime'                  => 'Датум и време',
'math_failure'              => 'Неможам да парсирам',
'math_unknown_error'        => 'непозната грешка',
'math_unknown_function'     => 'непозната функција',
'math_lexing_error'         => 'лексичка грешка',
'math_syntax_error'         => 'синтаксна грешка',
'math_image_error'          => 'неуспешна PNG конверзија',
'math_bad_tmpdir'           => 'Невозможно е создавањето на привремен директориум за математичките операции',
'math_bad_output'           => 'Невозможно е создавањето на аутпут директориум за математичките операции',
'math_notexvc'              => 'Недостасува извршната податотека texvc; 
погледнете math/README за нејзино нагодување.',
'prefs-personal'            => 'Кориснички профил',
'prefs-rc'                  => 'Скорешни промени',
'prefs-watchlist'           => 'Листа на набљудувања',
'prefs-watchlist-days'      => 'Број на денови прикажани во листата на набљудувања:',
'prefs-watchlist-days-max'  => '(максимум 7 дена)',
'prefs-watchlist-edits'     => 'Максимален број на промени прикажани во проширената листа на набљудувања:',
'prefs-watchlist-edits-max' => '(максимален број: 1000)',
'prefs-misc'                => 'Разно',
'prefs-resetpass'           => 'Промени лозинка',
'saveprefs'                 => 'Зачувај нагодувања',
'resetprefs'                => 'Избриши незачувани измени',
'restoreprefs'              => 'Врати секаде основни нагодувања',
'textboxsize'               => 'Уредување',
'prefs-edit-boxsize'        => 'Големина на прозорецот за уредување.',
'rows'                      => 'Редови:',
'columns'                   => 'Колони:',
'searchresultshead'         => 'Пребарување',
'resultsperpage'            => 'Резултати по страница:',
'contextlines'              => 'Линии по резултат:',
'contextchars'              => 'Карактери од содржината по линија',
'stub-threshold'            => 'Минимална големина за <a href="#" class="stub">никулци</a> (бајти):',
'recentchangesdays'         => 'Денови за прикажување во скорешни промени:',
'recentchangesdays-max'     => '(максимум $1 {{PLURAL:$1|ден|денови}})',
'recentchangescount'        => 'Број на уредувања кои ќе се прикажуват во скорешни промени, историја на страница и во дневници, по основно:',
'savedprefs'                => 'Вашите нагодувања се зачувани.',
'timezonelegend'            => 'Временска зона',
'timezonetext'              => '¹Број на часови за колку се разликува вашето локално време од серверското време (UTC).',
'localtime'                 => 'Локално време:',
'timezoneselect'            => 'Временска зона:',
'timezoneuseserverdefault'  => 'Користи серверско време',
'timezoneuseoffset'         => 'Друго (наведи отстапување)',
'timezoneoffset'            => 'Отстапка¹:',
'servertime'                => 'Серверско време:',
'guesstimezone'             => 'Пополни од веб прелистувачот',
'timezoneregion-africa'     => 'Африка',
'timezoneregion-america'    => 'Америка',
'timezoneregion-antarctica' => 'Антарктик',
'timezoneregion-arctic'     => 'Арктик',
'timezoneregion-asia'       => 'Азија',
'timezoneregion-atlantic'   => 'Атлантски Океан',
'timezoneregion-australia'  => 'Австралија',
'timezoneregion-europe'     => 'Европа',
'timezoneregion-indian'     => 'Индиски Океан',
'timezoneregion-pacific'    => 'Тихи Океан',
'allowemail'                => 'Дозволи е-пошта од други корисници',
'prefs-searchoptions'       => 'Опции за пребарување',
'prefs-namespaces'          => 'Именски простори',
'defaultns'                 => 'Пребарувај во овие именски простори по основно:',
'default'                   => 'по основно',
'files'                     => 'Податотеки',
'prefs-custom-css'          => 'Посебно CSS',
'prefs-custom-js'           => 'Посебно JS',

# User rights
'userrights'                     => 'Управување со кориснички права', # Not used as normal message but as header for the special page itself
'userrights-lookup-user'         => 'Управување со кориснички групи',
'userrights-user-editname'       => 'Внеси корисничко име:',
'editusergroup'                  => 'Уреди кориснички групи',
'editinguser'                    => "Менување на корисничките права на корисникот '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'       => 'Уреди ги корисничките групи',
'saveusergroups'                 => 'Зачувај ги корисничките групи',
'userrights-groupsmember'        => 'Член на:',
'userrights-reason'              => 'Образложение на промената:',
'userrights-no-interwiki'        => 'Немате пермисии за уредување на кориснички права на други викија.',
'userrights-nodatabase'          => 'Базата на податоци $1 не постои или не е локална.',
'userrights-nologin'             => 'Мора да сте [[Special:UserLogin|најавени]] со администраторска корисничка сметка за да може да вршите промена на кориснички права.',
'userrights-notallowed'          => 'Вашата корисничка сметка нема пермисии за промена на кориснички права.',
'userrights-changeable-col'      => 'Групи кои може да ги промените',
'userrights-unchangeable-col'    => 'Групи кои не може да ги промените',
'userrights-irreversible-marker' => '$1*',

# Groups
'group'               => 'Група:',
'group-user'          => 'Корисници',
'group-autoconfirmed' => 'Автопотврдени корисници',
'group-bot'           => 'Ботови',
'group-sysop'         => 'Администратори',
'group-bureaucrat'    => 'Бирократи',
'group-suppress'      => 'Надгледувачи',
'group-all'           => '(сите)',

'group-user-member'          => 'Корисник',
'group-autoconfirmed-member' => 'Автопотврден корисник',
'group-bot-member'           => 'Бот',
'group-sysop-member'         => 'Систем оператор',
'group-bureaucrat-member'    => 'Бирократ',
'group-suppress-member'      => 'Надгледувач',

'grouppage-user'          => '{{ns:project}}:Корисници',
'grouppage-autoconfirmed' => '{{ns:project}}:Автопотврдени корисници',
'grouppage-bot'           => '{{ns:project}}:Ботови',
'grouppage-sysop'         => '{{ns:project}}:Администратори',
'grouppage-bureaucrat'    => '{{ns:project}}:Бирократи',
'grouppage-suppress'      => '{{ns:project}}:Надгледувач',

# Rights
'right-read'                 => 'Читање страници',
'right-edit'                 => 'Уредување страници',
'right-createpage'           => 'Креирање страници (кои не се страници за разговор)',
'right-createtalk'           => 'Креирање страници за разговор',
'right-createaccount'        => 'Креирање нови кориснички сметки',
'right-minoredit'            => 'Означување на уредувањата како ситни',
'right-move'                 => 'Преместување страници',
'right-move-subpages'        => 'Преместување страници со нивните подстраници',
'right-move-rootuserpages'   => 'Преместување на основна корисничка страница',
'right-movefile'             => 'Преместување на податотеки',
'right-suppressredirect'     => 'Не прави пренасочување од старото име при преместување на страница',
'right-upload'               => 'Подигнување податотеки',
'right-reupload'             => 'Пребришување постоечка податотека',
'right-reupload-own'         => 'Преснимување на постоечка податотека подигната од вас',
'right-upload_by_url'        => 'Подигнување податотека од URL адреса',
'right-purge'                => 'Избриши серверски кеш за страницата без барање потврда за тоа',
'right-autoconfirmed'        => 'Уредување на делумно заштитени страници',
'right-bot'                  => 'Третиран како автоматски процес',
'right-delete'               => 'Бришење страници',
'right-bigdelete'            => 'Бришење страници со долга историја',
'right-deleterevision'       => 'Бришење и враќање на специфични ревизии на страници',
'right-browsearchive'        => 'Пребарување низ избришани страници',
'right-undelete'             => 'Обновување избришана страница',
'right-suppressrevision'     => 'Прегледај и обнови ревизии сокриени од Систем оператори',
'right-suppressionlog'       => 'Погледни приватни дневници',
'right-block'                => 'Оневозможување на останати корисници да уредуваат',
'right-blockemail'           => 'Оневозможување корисници да праќаат е-пошта',
'right-hideuser'             => 'Блокирање корисници, сокривање од јавноста',
'right-ipblock-exempt'       => 'Заобиколување на IP блокирања, авто-блокирања и блокирања на IP рангови',
'right-proxyunbannable'      => 'Заобиколување на автоматски блокирања од проксија',
'right-protect'              => 'Менување на нивоа на заштита и уредување на заштитени страници',
'right-editprotected'        => 'Уредување на заштитени страници (без каскадна заштита)',
'right-editinterface'        => 'Уредување на кориснички интерфејс',
'right-editusercssjs'        => 'Уредување на CSS и JS податотеки на други корисници',
'right-import'               => 'Увезување страници од други викија',
'right-importupload'         => 'Увезување страници од подигната податотека',
'right-patrol'               => 'Означување на уредувањата на другите како патролирани',
'right-unwatchedpages'       => 'Прегледување на листа на ненабљудувани страници',
'right-trackback'            => 'Потврдување на враќање',
'right-mergehistory'         => 'Спојување на историите на страниците',
'right-userrights'           => 'Уредување на сите кориснички права',
'right-userrights-interwiki' => 'Уредување на кориснички права на корисници на други викија',
'right-siteadmin'            => 'Заклучување и отклучување на базата на податоци',

# User rights log
'rightslog'      => 'Дневник на менувања на кориснички права',
'rightslogtext'  => 'Ова е дневник на промени на кориснички права.',
'rightslogentry' => 'Променето членство во група за $1 од $2 во $3',
'rightsnone'     => '(нема)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'читање на оваа страница',
'action-edit'                 => 'уредување на оваа страница',
'action-createpage'           => 'креирање страници',
'action-createtalk'           => 'креирање страници за разговор',
'action-createaccount'        => 'креирање на оваа корисничка сметка',
'action-minoredit'            => 'означување на ова уредување како ситно',
'action-move'                 => 'преместување на оваа страница',
'action-move-subpages'        => 'преместување на оваа страница и нејзините подстраници',
'action-move-rootuserpages'   => 'преместување на основна корисничка страница',
'action-movefile'             => 'премести ја оваа податотека',
'action-upload'               => 'подигнување на оваа податотека',
'action-reupload'             => 'пребришување на оваа постоечка податотека',
'action-reupload-shared'      => 'пребришување на оваа податотека на заедничката ризница',
'action-upload_by_url'        => 'подигнување на оваа податотека од URL адреса',
'action-delete'               => 'бриши ја оваа страница',
'action-deleterevision'       => 'бриши ја оваа ревизија',
'action-deletedhistory'       => 'прегледај ја историјата на бришења за оваа страница',
'action-browsearchive'        => 'барање на избришани страници',
'action-undelete'             => 'обнови ја оваа страница',
'action-suppressrevision'     => 'прегледај ја и обновија оваа скриена ревизија',
'action-suppressionlog'       => 'погледни го овој приватен дневник',
'action-block'                => 'оневозможи го овој корисник да уредува',
'action-protect'              => 'промени го нивото на заштита на оваа страница',
'action-import'               => 'увези ја оваа страница од друго вики',
'action-importupload'         => 'увези ја оваа страница од подигната податотека',
'action-patrol'               => 'означи ги уредувањата на другите како проверени',
'action-unwatchedpages'       => 'преглед на листа на ненабљудувани страници',
'action-trackback'            => 'потврда на враќање',
'action-mergehistory'         => 'спојување на историјата на оваа страница',
'action-userrights'           => 'уредување на сите кориснички права',
'action-userrights-interwiki' => 'уредување на кориснички права на корисници на други викија',
'action-siteadmin'            => 'заклучување или отклучување на базата на податоци',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|промена|промени}}',
'recentchanges'                     => 'Скорешни промени',
'recentchanges-legend'              => 'Опции за скорешни промени',
'recentchangestext'                 => 'Следење на последните промени на викито на оваа страница.',
'recentchanges-feed-description'    => 'Следење на скорешните промени на викито во овој фид.',
'rcnote'                            => "Долу {{PLURAL:$1|е прикажана '''1''' промена|се прикажани скорешните '''$1''' промени}} {{PLURAL:$2|денес|во последниве '''$2''' дена}}, почнувајќи од $5, $4.",
'rcnotefrom'                        => 'Подолу се промените од <b>$2</b> (се прикажуваат до <b>$1</b>).',
'rclistfrom'                        => 'Прикажи нови промени почнувајќи од $1',
'rcshowhideminor'                   => '$1 ситни промени',
'rcshowhidebots'                    => '$1 ботови',
'rcshowhideliu'                     => '$1 најавени корисници',
'rcshowhideanons'                   => '$1 анонимни корисници',
'rcshowhidepatr'                    => '$1 проверени уредувања',
'rcshowhidemine'                    => '$1 мои уредувања',
'rclinks'                           => 'Прикажи скорешни $1 промени во последните $2 дена<br />$3',
'diff'                              => 'разл',
'hist'                              => 'ист',
'hide'                              => 'Сокриј',
'show'                              => 'Прикажи',
'minoreditletter'                   => 'с',
'newpageletter'                     => 'Н',
'boteditletter'                     => 'б',
'sectionlink'                       => '→',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|корисник кој набљудува|корисници кои набљудуваат}}]',
'rc_categories'                     => 'Ограничи на категории (раздели со "|")',
'rc_categories_any'                 => 'Било кои',
'rc-change-size'                    => '$1',
'newsectionsummary'                 => '/* $1 */ нова секција',
'rc-enhanced-expand'                => 'Прикажување на детали (потребно JavaScript)',
'rc-enhanced-hide'                  => 'Сокривање на детали',

# Recent changes linked
'recentchangeslinked'          => 'Поврзани промени',
'recentchangeslinked-title'    => 'Промени поврзани со "$1"',
'recentchangeslinked-backlink' => '← $1',
'recentchangeslinked-noresult' => 'Нема промени на поврзаните страници во зададениот период.',
'recentchangeslinked-summary'  => "Ова е листа на промени направени на страниците поврзани преку специфицираната страница (или до членови на специфицираната категорија).
Страниците во [[Special:Watchlist|вашата листа на набљудувања]] се прикажани '''болдирано'''.",
'recentchangeslinked-page'     => 'Име на страница:',
'recentchangeslinked-to'       => 'Прикажи ги промените на страниците поврзани со дадената страница',

# Upload
'upload'                      => 'Подигни податотека',
'uploadbtn'                   => 'Подигни податотека',
'reupload'                    => 'Повторно подигни',
'reuploaddesc'                => 'Прекини подигнување и врати се во формуларот за подигнување',
'uploadnologin'               => 'Не сте најавени',
'uploadnologintext'           => 'Морате да бидете [[Special:UserLogin|најавени]] за да подигнувате податотеки.',
'upload_directory_missing'    => 'Именикот за подигнување на слики ($1) не постои и не може да биде креиран на вебсерверот.',
'upload_directory_read_only'  => 'Веб серверот не може да запишува во именикот за подигнување ($1).',
'uploaderror'                 => 'Грешка во подигнувањето',
'uploadtext'                  => "Користете го долниот формулар за подигнување на податотеки.
За преглед или пребарување на претходно подигнати податотеки погледнете ја [[Special:FileList|листата на подигнати податотеки]], повторните подигнувања се ажурирани во [[Special:Log|дневникот на подигнувања]], бришењата се ажурирани во [[Special:Log/delete|дневникот на бришења]].

За да вклучите слика во страница, користете врска во некој од следниве формати:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>''' за верзија на сликата во целосна големина
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|alt text]]</nowiki><tt>''' за верзија на сликата со големина од 200 пиксели рендерирана во соодветна кутија, со опис како што е наведено во '''alt text'''
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki><tt>''' за директно поврзување со податотеката без нејзино прикажување",
'upload-permitted'            => 'Дозволени типови на податотеки: $1.',
'upload-preferred'            => 'Преферирани типови на податотеки: $1.',
'upload-prohibited'           => 'Забранети типови на податотеки: $1.',
'uploadlog'                   => 'дневник на подигнувања',
'uploadlogpage'               => 'Дневник на подигнувања',
'uploadlogpagetext'           => 'Подолу е листа на најновите подигнувања на податотеки.
Погледнете ја [[Special:NewFiles|галеријата на нови податотеки]] за визуелен преглед.',
'filename'                    => 'Име на податотека',
'filedesc'                    => 'Опис',
'fileuploadsummary'           => 'Опис:',
'filereuploadsummary'         => 'Промени на податотека:',
'filestatus'                  => 'Copyright статус:',
'filesource'                  => 'Извор:',
'uploadedfiles'               => 'Подигнати податотеки',
'ignorewarning'               => 'Игнорирај ги предупредувањата и зачувај ја податотеката',
'ignorewarnings'              => 'Игнорирај ги сите предупредувања',
'minlength1'                  => 'Името на податотеката мора да содржи барем една буква.',
'illegalfilename'             => 'Името на податотеката "$1" содржи знаци што не се дозволени во наслов на страници. 
Ве молиме преименувајте ја податотеката и обидете се да ја подигнете повторно.',
'badfilename'                 => 'Името на податотеката е променето во "$1".',
'filetype-badmime'            => 'Податотеки од MIME тип „$1“ не е дозволено да се подигнуваат.',
'filetype-unwanted-type'      => "'''\".\$1\"''' не е посакуван тип на податотека.
{{PLURAL:\$3|Префериран тип на податотека е|Преферирани типови на податотеки се}} \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' не е допуштен тип на податотека.
{{PLURAL:\$3|Допуштен тип на податотека е|Допуштени типови на податотеки се}} \$2.",
'filetype-missing'            => 'Податотеката нема наставка (пример ".jpg").',
'large-file'                  => 'Се препорачува податотеките да не бидат поголеми од $1; оваа податотека е $2.',
'largefileserver'             => 'Големината на податотеката е поголема од максимално дозволената големина.',
'emptyfile'                   => 'Податотеката што ја подигнавте изгледа дека е празна. 
Ова може да е поради грешка во името на податотеката. 
Ве молиме проверете дали навистина сакате да ја подигнете оваа податотека.',
'fileexists'                  => "Податотека со ова име веќе постои, проверете '''<tt>$1</tt>''' ако не сте сигурни дали сакате да го промените.",
'filepageexists'              => "Страницата за опис на оваа податотека веќе е креирана на '''<tt>$1</tt>''', но не постои податотека со тоа име.
Описот кој го внесовте нема да се појави на страницата за опис.
Доколку сакате описот да се појаву тука, мора мануелно да извршите уредување.",
'fileexists-extension'        => "Податотека со слично име веќе постои:<br />
Име на податотека која се подигнува: '''<tt>$1</tt>'''<br />
Име на постоечка податотека: '''<tt>$2</tt>'''<br />
Ве молиме изберете друго име за податотеката.",
'fileexists-thumb'            => "'''<center>Постоечка податотека</center>'''",
'fileexists-thumbnail-yes'    => "Податотеката изгледа дека е слика со намалена големина ''(thumbnail)''.
Ве молиме проверете ја податотеката '''<tt>$1</tt>'''.<br />
Ако податотеката која ја проверувате е истата слика во својата оригинална големина тогаш не мора да ја подигнувате дополнително.",
'file-thumbnail-no'           => "Името на податотеката почнува со '''<tt>$1</tt>'''. 
Изгледа дека е со намалена големина ''(мини, thumbnail)''.
Ако ја имате оваа слика во оригинална големина, подигнете ја неа, во спротивно сменете го името на податотеката.",
'fileexists-forbidden'        => 'Податотека со тоа име веќе постои и не може да биде заменета.
Ако и понатаму сакате да ја подигнете вашата податотеката, ве молиме вратете се назад и подигнете ја под друго име. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Податотека со ова име веќе постои во заедничката ризница.
Ако и понатаму сакате да ја подигнете податотеката, ве молиме вратете се и повторно подигнете ја податотеката со ново име. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Оваа податотека е дупликат со {{PLURAL:$1|следнава податотека|следниве податотеки}}:',
'file-deleted-duplicate'      => 'Податотека индентична со податотеката ([[$1]]) претходно била избришана. Треба да проверите во дневникот на бришења за оваа податотека пред повторно да ја подигнете.',
'successfulupload'            => 'Успешно подигнување',
'uploadwarning'               => 'Предупредување при подигнување',
'savefile'                    => 'Зачувај податотека',
'uploadedimage'               => 'подигнато "[[$1]]"',
'overwroteimage'              => 'подигнато нова верзија на "[[$1]]"',
'uploaddisabled'              => 'Забрана за подигнувања',
'uploaddisabledtext'          => 'Подигнување на податотеки е оневозможено.',
'uploadscripted'              => 'Податотеката содржи HTML или скриптен код што може да биде погрешно интерпретира од страна на веб прелистувачите.',
'uploadcorrupt'               => 'Податотеката е оштетена или има неточна наставка. 
Ве молиме проверете ја податотеката и подигнете ја повторно.',
'uploadvirus'                 => 'Оваа податотека содржи вирус! Повеќе детали: $1',
'sourcefilename'              => 'Изворно име на податотеката:',
'destfilename'                => 'Целно име на податотеката:',
'upload-maxfilesize'          => 'Максимална големина на податотека: $1',
'watchthisupload'             => 'Набљудувај ја оваа страница',
'filewasdeleted'              => 'Податотека со ова име претходно била подигната и потоа избришана. 
Проверете во $1 пред да продолжите со повтроно подигнување.',
'upload-wasdeleted'           => "'''Внимание: Подигнувате податотека што претходно била бришена.'''

Размислете дали е правилно да продолжите со подигнување на оваа податотека.
Дневникот на бришење за оваа податотека може да го прегледате овде заради ваша информација:",
'filename-bad-prefix'         => "Името на податотеката која ја подигнувате започнува со '''\"\$1\"''', типично за имиња автоматски креирани од страна на дигиталните камери, што не е доволно описно име .
Ве молиме одберете подобро описно име за вашата податотека.",
'filename-prefix-blacklist'   => ' #<!-- Не менувај ја содржината на оваа редица, остави ја како што е --> <pre>
# Опис на ситнаксата:
#   * Било што после знакот "#" па до крајот на линијата претставува коментар
#   * Секој ред означува префикс на типични имиња доделувани од страна на дигиталните камери
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # некои мобилни телефони
IMG # генерички
JD # Jenoptik
MGP # Pentax
PICT # misc.
 #</pre> <!-- Не менувај ја содржината на оваа редица, остави ја како што е -->',

'upload-proto-error'      => 'Погрешен протокол',
'upload-proto-error-text' => 'За оддалечено подигнување потребно е URL-то да почнува со <code>http://</code> или <code>ftp://</code>.',
'upload-file-error'       => 'Внатрешна грешка',
'upload-file-error-text'  => 'Се случи внатрешна грешка при обидот за креирање на помошна податотека на серверот.
Молиме контактирајте некој [[Special:ListUsers/sysop|администратор]].',
'upload-misc-error'       => 'Непозната грешка при подигнување',
'upload-misc-error-text'  => 'Се појави грешка при подигнувањето.
Проверете дали URL-то е правилно и достапно, па обидете се повторно.
Ако пак се појави проблем контактирајте со некој [[Special:ListUsers/sysop|администратор]].',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Не може да се пристапи до URL-то',
'upload-curl-error6-text'  => 'Наведеното URL не е достапно.
Проверете дали URL-то е исправно и достапно.',
'upload-curl-error28'      => 'Истече времето за подигнување',
'upload-curl-error28-text' => 'Сајтот премногу споро одговара.
Проверете дали е подигнат, почекајте малку и обидете се повторно.
Може да се обидете кога нема да биде многу зафатен.',

'license'            => 'Лиценцирање:',
'nolicense'          => 'Нема',
'license-nopreview'  => '(Прегледот не е достапен)',
'upload_source_url'  => ' (валидна, јавно достапна адреса (URL))',
'upload_source_file' => '(податотека на вашиот компјутер)',

# Special:ListFiles
'listfiles-summary'     => 'Оваа специјална страница ги покажува сите подигнати податотеки.
По основно последно подигнатите страници се прикажани на почеток од листата.
Со клик на насловот на колоната може да се промени подредувањето.',
'listfiles_search_for'  => 'Побарај име на медија:',
'imgfile'               => 'податотека',
'listfiles'             => 'Листа на слики',
'listfiles_date'        => 'Датум',
'listfiles_name'        => 'Име',
'listfiles_user'        => 'Корисник',
'listfiles_size'        => 'Големина (бајти)',
'listfiles_description' => 'Опис',
'listfiles_count'       => 'Верзии',

# File description page
'filehist'                  => 'Историја на податотека',
'filehist-help'             => 'Кликнете на датум/време за да ја видите податотеката како изгледала тогаш.',
'filehist-deleteall'        => 'бриши се',
'filehist-deleteone'        => 'бриши',
'filehist-revert'           => 'врати',
'filehist-current'          => 'моментална',
'filehist-datetime'         => 'Датум/Време',
'filehist-thumb'            => 'Мини-слика',
'filehist-thumbtext'        => 'Мини-слика за верзија од $1',
'filehist-nothumb'          => 'Нема мини-слика',
'filehist-user'             => 'Корисник',
'filehist-dimensions'       => 'Димензии',
'filehist-filesize'         => 'Големина',
'filehist-comment'          => 'Коментар',
'imagelinks'                => 'Врски до податотека',
'linkstoimage'              => 'До оваа податотека има {{PLURAL:$1|врска од следнава страница|врски од следниве $1 страници}}:',
'linkstoimage-more'         => 'Повеќе од {{PLURAL:$1|една страница е поврзана|$1 страници се поврзани}} со оваа податотека.
Следнава листа {{PLURAL:$1|ја прикажува само првата поврзана страница|ги прикажува само првите $1 поврзани страници}} до оваа податотека.
Целосна листа може да добиете [[Special:WhatLinksHere/$2|тука]].',
'nolinkstoimage'            => 'Нема страници кои ја користат оваа податотека.',
'morelinkstoimage'          => 'Погледни [[Special:WhatLinksHere/$1|други врски]] кон оваа податотека.',
'redirectstofile'           => '{{PLURAL:$1|Следната податотека пренасочува|$1 Следните податотеки пренасочуваат}} кон оваа податотека:',
'duplicatesoffile'          => '{{PLURAL:$1|Следната податотека е дупликат|$1 Следните податотеки се дупликати}} на оваа податотека ([[Special:FileDuplicateSearch/$2|повеќе информации]]):',
'sharedupload'              => 'Оваа податотека е од $1 и може да се користи во други проекти. $2', # $1 is the repo name, $2 is shareduploadwiki(-desc)
'shareduploadwiki'          => 'Ве молиме видете $1 за понатамошна информација.',
'shareduploadwiki-desc'     => 'Описот на нејзината $1 е прикажан подолу.',
'shareduploadwiki-linktext' => 'страница за опис на податотека',
'noimage'                   => 'Не постои податотека со тоа име, проверете $1',
'noimage-linktext'          => 'подигни една',
'uploadnewversion-linktext' => 'Подигни нова верзија на оваа податотека',
'shared-repo-from'          => 'од $1', # $1 is the repository name

# File reversion
'filerevert'                => 'Врати $1',
'filerevert-backlink'       => '← $1',
'filerevert-legend'         => 'Врати податотека',
'filerevert-intro'          => "Ја враќате '''[[Media:$1|$1]]''' на [$4 верзијата од $3, $2].",
'filerevert-comment'        => 'Коментар:',
'filerevert-defaultcomment' => 'Вратена на верзија од $2, $1',
'filerevert-submit'         => 'Врати',
'filerevert-success'        => "'''[[Media:$1|$1]]''' е вратен на [$4 верзијата од $3, $2].",
'filerevert-badversion'     => 'Нема претходна локална верзија на оваа податотека со даденото време.',

# File deletion
'filedelete'                  => 'Бриши $1',
'filedelete-backlink'         => '← $1',
'filedelete-legend'           => 'Бриши податотека',
'filedelete-intro'            => "Ја бришете податотеката '''[[Media:$1|$1]]''' заедно со нејзината историја.",
'filedelete-intro-old'        => "Вие ја бришете верзијата на '''[[Media:$1|$1]]''' од [$4 $3, $2].",
'filedelete-comment'          => 'Причина за бришењето:',
'filedelete-submit'           => 'Бриши',
'filedelete-success'          => "'''$1''' беше избришана.",
'filedelete-success-old'      => "Верзијата на '''[[Media:$1|$1]]''' од $3, $2 беше избришана.",
'filedelete-nofile'           => "'''$1''' не постои.",
'filedelete-nofile-old'       => "Не постојат архивирани верзии на '''$1''' со атрибути како што се наведени.",
'filedelete-otherreason'      => 'Друга/дополнителна причина:',
'filedelete-reason-otherlist' => 'Друга причина',
'filedelete-reason-dropdown'  => '*Вообичаени причини за бришење
** Прекршување на Copyright
** Дуплирање на податотеки',
'filedelete-edit-reasonlist'  => 'Уреди причини за бришење',

# MIME search
'mimesearch' => 'MIME пребарување',
'mimetype'   => 'MIME тип:',
'download'   => 'преземи',

# Unwatched pages
'unwatchedpages' => 'Ненабљудувани страници',

# List redirects
'listredirects' => 'Листа на пренасочувања',

# Unused templates
'unusedtemplates'     => 'Неискористени шаблони',
'unusedtemplatestext' => 'Оваа страница ги прикажува сите страници во именскиот простор {{ns:template}} кои не се вклучени во друга страница. 
Не заборавајте да ги проверите другите врски во шаблоните пред да ги избришете.',
'unusedtemplateswlh'  => 'други врски',

# Random page
'randompage'         => 'Случајна страница',
'randompage-nopages' => 'Нема страници во именскиот простор "$1".',

# Random redirect
'randomredirect'         => 'Случајно пренасочување',
'randomredirect-nopages' => 'Нема пренасочувања во именскиот простор "$1".',

# Statistics
'statistics'                   => 'Статистики',
'statistics-header-pages'      => 'Статистики за страници',
'statistics-header-edits'      => 'Статистики на уредувања',
'statistics-header-views'      => 'Статистики на посети',
'statistics-header-users'      => 'Статистики за корисници',
'statistics-articles'          => 'Статии',
'statistics-pages'             => 'Страници',
'statistics-pages-desc'        => 'Сите страници на викито, вклучувајќи и страници за разговор, пренасочувања, и.т.н.',
'statistics-files'             => 'Подигнати податотеки',
'statistics-edits'             => 'Број на уредувања од започнувањето на {{SITENAME}}',
'statistics-edits-average'     => 'Просечен број на уредувања по страница',
'statistics-views-total'       => 'Вкупно посети',
'statistics-views-peredit'     => 'Посети по уредување',
'statistics-jobqueue'          => 'Должина на [http://www.mediawiki.org/wiki/Manual:Job_queue редица на чекање]',
'statistics-users'             => 'Регистрирани [[Special:ListUsers|корисници]]',
'statistics-users-active'      => 'Активни корисници',
'statistics-users-active-desc' => 'Корисници кои имаат извршено некоја акција во {{PLURAL:$1|претходниот ден|претходните $1 дена}}',
'statistics-mostpopular'       => 'Најпосетувани страници',

'disambiguations'      => 'Страници за појаснување',
'disambiguationspage'  => 'Template:Појаснување',
'disambiguations-text' => "Следните страници имаат врски кои водат до '''страница за појаснување'''.
Наместо тоа тие треба да водат до соодветната тема.<br />
Страница се третира како страница за појаснување ако таа го користи шаблонот кој е наведен [[MediaWiki:Disambiguationspage|тука]]",

'doubleredirects'            => 'Двојни пренасочувања',
'doubleredirectstext'        => 'Секој ред содржи врски кон првото и второто пренасочување, којшто обично ја посочува <i>вистинската</i> целна страница кон кое првото пренасочување би требало да насочува.',
'double-redirect-fixed-move' => '[[$1]] беше преместена, сега пренасочува во [[$2]]',
'double-redirect-fixer'      => 'Исправувач на пренасочувања',

'brokenredirects'        => 'Прекинати пренасочувања',
'brokenredirectstext'    => 'Следните пренасочувања водат до непостоечки страници.',
'brokenredirects-edit'   => '(уреди)',
'brokenredirects-delete' => '(бриши)',

'withoutinterwiki'         => 'Страници без интервики (јазични) врски',
'withoutinterwiki-summary' => 'Следните страници немаат врски до други јазични верзии.',
'withoutinterwiki-legend'  => 'Префикс',
'withoutinterwiki-submit'  => 'Прикажи',

'fewestrevisions' => 'Статии со најмалку ревизии',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|бајт|бајти}}',
'ncategories'             => '$1 {{PLURAL:$1|категорија|категории}}',
'nlinks'                  => '$1 {{PLURAL:$1|врска|врски}}',
'nmembers'                => '$1 {{PLURAL:$1|член|члена}}',
'nrevisions'              => '$1 {{PLURAL:$1|измена|измени}}',
'nviews'                  => '$1 {{PLURAL:$1|преглед|прегледи}}',
'specialpage-empty'       => 'Нема резултати од пребарувањето за овој извештај.',
'lonelypages'             => 'Осамени страници',
'lonelypagestext'         => 'Следните страници не се поврзани од или трансклудирани во други страници на {{SITENAME}}.',
'uncategorizedpages'      => 'Некатегоризирани страници',
'uncategorizedcategories' => 'Некатегоризирани категории',
'uncategorizedimages'     => 'Некатегоризирани податоци',
'uncategorizedtemplates'  => 'Некатегоризирани преуредувања',
'unusedcategories'        => 'Неискористени категории',
'unusedimages'            => 'Неискористени слики',
'popularpages'            => 'Популарни страници',
'wantedcategories'        => 'Барани категории',
'wantedpages'             => 'Барани страници',
'wantedfiles'             => 'Барани податотеки',
'wantedtemplates'         => 'Барани шаблони',
'mostlinked'              => 'Најмногу врски до страници',
'mostlinkedcategories'    => 'Најмногу врски до категории',
'mostlinkedtemplates'     => 'Најмногу врски кон шаблони',
'mostcategories'          => 'Статии со најмногу категории',
'mostimages'              => 'Најмногу врски до податотеки',
'mostrevisions'           => 'Статии со најмногу верзии',
'prefixindex'             => 'Страници со префикс',
'shortpages'              => 'Кратки страници',
'longpages'               => 'Долги страници',
'deadendpages'            => 'Ќорсокак страници',
'deadendpagestext'        => 'Следните страници немаат врски кон ниту една друга страница на ова вики.',
'protectedpages'          => 'Заштитени страници',
'protectedpages-indef'    => 'Само бесконечни заштити',
'protectedpages-cascade'  => 'Само каскадни заштити',
'protectedpagestext'      => 'Следните страници се заштитени во поглед на преместување и уредување',
'protectedpagesempty'     => 'Во моментов нема заштитени страници со параметрите кои ги зададовте.',
'protectedtitles'         => 'Заштитени наслови',
'protectedtitlestext'     => 'Следните наслови се забранети за создавање',
'protectedtitlesempty'    => 'Во овој момент нема заштитени наслови кои ги задоволуваат наведените критериуми.',
'listusers'               => 'Листа на корисници',
'listusers-editsonly'     => 'Прикажи само корисници кои уредувале',
'listusers-creationsort'  => 'Подредени по датум на креирање',
'usereditcount'           => '$1 {{PLURAL:$1|уредување|уредувања}}',
'usercreated'             => 'Креирано на $1 во $2',
'newpages'                => 'Нови страници',
'newpages-username'       => 'Корисничко име:',
'ancientpages'            => 'Најстари статии',
'move'                    => 'Премести',
'movethispage'            => 'Премести ја оваа страница',
'unusedimagestext'        => 'Имајте во предвид дека други веб страници може да имаат врска кон податок со директен URL, па може сеуште да се прикажуваат иако активно се користат.',
'unusedcategoriestext'    => 'Следните категории постојат и покрај тоа што ниедна статија и категорија не ги користи.',
'notargettitle'           => 'Нема цел',
'notargettext'            => 'Не одредивте целна страница или корисник на кој би се применила функцијата.',
'nopagetitle'             => 'Не постои таков наслов',
'nopagetext'              => 'Целната страница која ја наведовте не постои.',
'pager-newer-n'           => '{{PLURAL:$1|понова 1|понови $1}}',
'pager-older-n'           => '{{PLURAL:$1|постар 1|постари $1}}',
'suppress'                => 'Надзор',

# Book sources
'booksources'               => 'Печатени извори',
'booksources-search-legend' => 'Пребарување на извори за книга',
'booksources-go'            => 'Оди',
'booksources-text'          => 'Ова е листа на врски кон други сајтови кои продаваат нови и користени книги, и кои може
да имаат повеќе информации за книгите кои ги баравте:',
'booksources-invalid-isbn'  => 'Наведениот ISBN код изгледа не е валидно запишан; проверете да не настанала некоја грешка при копирање од оригиналниот извор.',

# Special:Log
'specialloguserlabel'  => 'Корисник:',
'speciallogtitlelabel' => 'Наслов:',
'log'                  => 'Дневници',
'all-logs-page'        => 'Сите дневници',
'alllogstext'          => 'Комбиниран приказ на сите расположиви дневници на {{SITENAME}}.
Изберете соодветен дневник од предложената листа, корисничко име или страница (се прави разлика помеѓу големи и мали букви).',
'logempty'             => 'Нема соодветни записи во дневникот.',
'log-title-wildcard'   => 'Пребарај наслови кои почнуваат со овој текст',

# Special:AllPages
'allpages'          => 'Сите страници',
'alphaindexline'    => '$1 во $2',
'nextpage'          => 'Следна страница ($1)',
'prevpage'          => 'Претходна страница ($1)',
'allpagesfrom'      => 'Прикажи страници кои започнуваат со:',
'allpagesto'        => 'Прикажи страници кои завршуваат со:',
'allarticles'       => 'Сите страници',
'allinnamespace'    => 'Сите страници (именски простор $1)',
'allnotinnamespace' => 'Сите страници (кои не се во именскиот простор $1)',
'allpagesprev'      => 'Претходна',
'allpagesnext'      => 'Следна',
'allpagessubmit'    => 'Оди',
'allpagesprefix'    => 'Прикажи страници со префикс:',
'allpagesbadtitle'  => 'Дадениот наслов е невалиден или има интер-јазичен или интер-вики префикс. Може да содржи повеќе карактери кои несмеат да се користат во наслови.',
'allpages-bad-ns'   => 'Википедија не содржи именски простор „$1“.',

# Special:Categories
'categories'                    => 'Категории',
'categoriespagetext'            => 'Следниве категории содржат страници или медија.
[[Special:UnusedCategories|Неискористените категории]] не се прикажани овде.
Исто така види [[Special:WantedCategories|барани категории]].',
'categoriesfrom'                => 'Приказ на категории почнувајќи од:',
'special-categories-sort-count' => 'нумеричко подредување',
'special-categories-sort-abc'   => 'алфанумеричко подредување по',

# Special:DeletedContributions
'deletedcontributions'       => 'Избришани кориснички придонеси',
'deletedcontributions-title' => 'Избришани кориснички придонеси',

# Special:LinkSearch
'linksearch'       => 'Надворешни врски',
'linksearch-pat'   => 'Услов за пребарување:',
'linksearch-ns'    => 'Именски простор:',
'linksearch-ok'    => 'Барај',
'linksearch-text'  => 'Може да се користат џокер знаци, пример "*.wikipedia.org".<br />
Подржани протоколи: <tt>$1</tt>',
'linksearch-line'  => '$1 поврзано од $2',
'linksearch-error' => 'Џокер знаци може да се користат само на почетокот во името на хостот.',

# Special:ListUsers
'listusersfrom'      => 'Прикажни корисници почнувајќи од:',
'listusers-submit'   => 'Прикажи',
'listusers-noresult' => 'Не е пронајден корисник.',

# Special:Log/newusers
'newuserlogpage'              => 'Дневник на регистрирања на корисници',
'newuserlogpagetext'          => 'Ова е дневник на регистрирани корисници.',
'newuserlog-byemail'          => 'испратена лозинка по е-пошта',
'newuserlog-create-entry'     => 'Нов корисник',
'newuserlog-create2-entry'    => 'создадено нова корисничка сметка $1',
'newuserlog-autocreate-entry' => 'Автоматски креирана корисничка сметка',

# Special:ListGroupRights
'listgrouprights'                 => 'Права на кориснички групи',
'listgrouprights-summary'         => 'Следната листа е листа на кориснички групи дефинирани на ова вики, заедно со нивните придружени права на пристап.
Можно е да има [[{{MediaWiki:Listgrouprights-helppage}}|дополнителни информации]] за некои права.',
'listgrouprights-group'           => 'Група',
'listgrouprights-rights'          => 'Права',
'listgrouprights-helppage'        => 'Help:Права на групи',
'listgrouprights-members'         => '(листа на членови)',
'listgrouprights-right-display'   => '$1 ($2)',
'listgrouprights-addgroup'        => 'Може да додава {{PLURAL:$2|група|групи}}: $1',
'listgrouprights-removegroup'     => 'Може да брише {{PLURAL:$2|група|групи}}: $1',
'listgrouprights-addgroup-all'    => 'Може да ги додава сите групи',
'listgrouprights-removegroup-all' => 'Може да ги брише сите групи',

# E-mail user
'mailnologin'      => 'Нема адреса за праќање',
'mailnologintext'  => 'Мора да бидете [[Special:UserLogin|најавени]] и да имате валидна е-поштенска адреса во вашите [[Special:Preferences|нагодувања]] за да може да праќате е-пошта до други корисници.',
'emailuser'        => 'Прати е-пошта до овој корисник',
'emailpage'        => 'Е-пошта до корисник',
'emailpagetext'    => 'Можете да го користите следниов формулар за праќање на е-поштенска порака до овој корисник.
Е-поштенската адреса која ја имате наведено во [[Special:Preferences|вашите нагодувања]] ќе се прикаже во "Од" полето на е-пораката, со што примачот ќе може да ви одговори директно вам.',
'usermailererror'  => 'Настана следната грешка при праќање е-пошта:',
'defemailsubject'  => '{{SITENAME}} е-пошта',
'noemailtitle'     => 'Нема е-поштенска адреса',
'noemailtext'      => 'Овој корисник нема наведено валидна е-поштенска адреса.',
'nowikiemailtitle' => 'Не е допуштено користење на е-пошта',
'nowikiemailtext'  => 'Овој корисник избрал да не прима е-пошта од други корисници.',
'email-legend'     => 'Праќање на е-пошта до друг {{SITENAME}} корисник',
'emailfrom'        => 'Од:',
'emailto'          => 'До:',
'emailsubject'     => 'Предмет:',
'emailmessage'     => 'Порака:',
'emailsend'        => 'Прати',
'emailccme'        => 'Прати ми копија од пораката по е-пошта.',
'emailccsubject'   => 'Копија од вашата порака до $1: $2',
'emailsent'        => 'Е-поштата е пратена',
'emailsenttext'    => 'Вашата е-пошта е пратена.',
'emailuserfooter'  => 'Оваа е-порака беше пратена од $1 до $2 со помош на функцијата Е-пошта на {{SITENAME}}.',

# Watchlist
'watchlist'            => 'Мои набљудувања',
'mywatchlist'          => 'Мои набљудувања',
'watchlistfor'         => "(за '''$1''')",
'nowatchlist'          => 'Немате ништо во вашата листа на набљудувања.',
'watchlistanontext'    => 'Ве молиме $1 за преглед или уредување на вашата листа на набљудувања.',
'watchnologin'         => 'Не сте најавени',
'watchnologintext'     => 'Мора да бидете [[Special:UserLogin|најавени]] за модифицирање на вашата листа на набљудувања.',
'addedwatch'           => 'Додадено во листата на набљудувања',
'addedwatchtext'       => "Страницата \"<nowiki>\$1</nowiki>\" е додадена во вашата [[Special:Watchlist|листа на набљудувања]]. Идните промени на оваа страница и поврзаните со неа страници за разговор ќе бидат прикажани овде и страницата ќе се прикаже '''задебелена''' во [[Special:RecentChanges|листата на скорешни промени]] за да можете полесно да ја изберете.",
'removedwatch'         => 'Отстрането од листата на набљудувања',
'removedwatchtext'     => 'Страницата "<nowiki>$1</nowiki>" е отстранета од [[Special:Watchlist|вашата листа на набљудувања]].',
'watch'                => 'Набљудувај',
'watchthispage'        => 'Набљудувај ја оваа страница',
'unwatch'              => 'Престани со набљудување',
'unwatchthispage'      => 'Престани набљудување',
'notanarticle'         => 'Не е статија',
'notvisiblerev'        => 'Ревизијата била избришана',
'watchnochange'        => 'Ниту едно од вашите набљудувања не било уредувано во прикажаниот период.',
'watchlist-details'    => '{{PLURAL:$1|$1 страница|$1 страници}} во вашата листа на набљудувања, неброејќи ги страниците за разговор.',
'wlheader-enotif'      => '* Известување по е-пошта е овозможено.',
'wlheader-showupdated' => "* Страници кои се променети од вашата последна посета се прикажани со '''задебелени''' букви",
'watchmethod-recent'   => 'Проверка на скорешните уредувања на набљудуваните страници',
'watchmethod-list'     => 'Проверерка на набљудуваните страници во скорешните уредувања',
'watchlistcontains'    => 'Вашата листа на набљудувања содржи $1 {{PLURAL:$1|страница|страници}}.',
'iteminvalidname'      => "Проблем со елементот '$1', неважечко име...",
'wlnote'               => "Подолу {{PLURAL:$1|е прикажана последната промена|се прикажани последните '''$1''' промени}} во {{PLURAL:$2|последниов час|последниве '''$2''' часа}}.",
'wlshowlast'           => 'Прикажи ги последните $1 часа, $2 денови, $3',
'watchlist-options'    => 'Опции на листа на набљудувања',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Набљудување...',
'unwatching' => 'Откажување на набљудување...',

'enotif_mailer'                => '{{SITENAME}} Систем за известување',
'enotif_reset'                 => 'Означи ги сите страници како посетени',
'enotif_newpagetext'           => 'Ова е нова страница.',
'enotif_impersonal_salutation' => 'Википедија корисник',
'changed'                      => 'променет',
'created'                      => 'создадена',
'enotif_subject'               => '{{SITENAME}} страницата $PAGETITLE беше $CHANGEDORCREATED од $PAGEEDITOR',
'enotif_lastvisited'           => 'Видете $1 за сите промени од вашата последна посета.',
'enotif_lastdiff'              => 'Видете $1 за да ја видите оваа промена.',
'enotif_anon_editor'           => 'анонимен корисник $1',
'enotif_body'                  => '$WATCHINGUSERNAME,

Страницата за разговор на {{SITENAME}} беше променета на $PAGEEDITDATE од \'\'\'$PAGEEDITOR\'\'\', види $PAGETITLE_URL.

$NEWPAGE

Резиме: $PAGESUMMARY $PAGEMINOREDIT

Контактирајте го уредувачот:
е-пошта: $PAGEEDITOR_EMAIL
вики: $PAGEEDITOR_WIKI

Веќе нема да има известувања за следните промени освен ако не ја посетите оваа страница. Исто така треба да ги ресетирате знаменцата за известување за сите ваши набљудувани страници во вашата листа на набљудувања.

{{SITENAME}} систем за известување

--
За промена на нагодувањата на вашата листа на набљудувања, види {{fullurl:Special:Watchlist/edit}}

Дополнителна помош: {{fullurl:Help:Содржина}}',

# Delete
'deletepage'             => 'Избриши страница',
'confirm'                => 'Потврди',
'excontent'              => "содржината беше: '$1'",
'excontentauthor'        => "содржината беше: '$1' (и единствениот автор беше '$2')",
'exbeforeblank'          => "содржината пред бришењето беше: '$1'",
'exblank'                => 'страницата беше празна',
'delete-confirm'         => 'Бриши "$1"',
'delete-backlink'        => '← $1',
'delete-legend'          => 'Бриши',
'historywarning'         => 'Предупредување: Страницата која сакате да ја избришете има историја:',
'confirmdeletetext'      => 'На пат сте трајно да избришете страница заедно со нејзината историја. 
Потврдете дека имате намера да го направите ова, дека ги разбирате последиците од тоа, дека го правите ова во согласност со [[{{MediaWiki:Policy-url}}|политиката]].',
'actioncomplete'         => 'Дејството беше извршено',
'deletedtext'            => '"<nowiki>$1</nowiki>" беше избришана. Видете $2 за досие на скорешните бришења.',
'deletedarticle'         => 'избришано "[[$1]]"',
'suppressedarticle'      => 'suppressed "[[$1]]"',
'dellogpage'             => 'Дневник на бришења',
'dellogpagetext'         => 'Подолу е листата на последните бришења.',
'deletionlog'            => 'дневник на бришењата',
'reverted'               => 'Вратено на претходната ревизија',
'deletecomment'          => 'Причина за бришење:',
'deleteotherreason'      => 'Друга/дополнителна причина:',
'deletereasonotherlist'  => 'Друга причина',
'deletereason-dropdown'  => '*Вообичаени причини
** На барање на авторот
** Прекршување на авторски права
** Страница создадена по грешка
** Несоодветен наслов

*Спам
**Спам',
'delete-edit-reasonlist' => 'Уредување на причини за бришење',
'delete-toobig'          => 'Оваа страница има долга историја на уредување, преку $1 {{PLURAL:$1|ревизија|ревизии}}.
Бришењето на ваквии страници е забрането со цел да се заштити {{SITENAME}} од оштетувања.',
'delete-warning-toobig'  => 'Оваа страница има долга историја на уредување, преку $1 {{PLURAL:$1|ревизија|ревизии}}.
Бришењето може да предизвика проблеми при со базата на податоци на {{SITENAME}};
продолжете доколку сте сигруни дека треба тоа да го сторите.',

# Rollback
'rollback'         => 'Врати промени',
'rollback_short'   => 'Врати',
'rollbacklink'     => 'Врати',
'rollbackfailed'   => 'Неуспешно враќање',
'cantrollback'     => 'Не може да се врати последната промена, последниот автор е истовремено и единствен.',
'alreadyrolled'    => 'Не може да се врати последното уредување од [[:$1]] на [[User:$2|$2]] ([[User talk:$2|Разговор]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]);
некој друг веќе ја уредил или ги вратил промените на страницата.

Последното уредување беше на [[User:$3|$3]] ([[User talk:$3|Разговор]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'      => "Коментарот на уредувањето беше: \"''\$1''\".", # only shown if there is an edit comment
'revertpage'       => 'Отстрането уредувањето на [[Special:Contributions/$2|$2]] ([[User talk:$2|разговор]]), вратено на последната верзија на [[User:$1|$1]]', # Additionally available: $3: revid of the revision reverted to, $4: timestamp of the revision reverted to, $5: revid of the revision reverted from, $6: timestamp of the revision reverted from
'rollback-success' => 'Откажани уредувањата на $1; вратено на последната верзија на $2.',
'sessionfailure'   => 'Има проблем со вашата сесија;
оваа акција е откажана како превентива против преземање сесии.
Притиснете го копчето „назад“ и повторно вчитајте ја страницата од која дојдовте и обидете се повторно.',

# Protect
'protectlogpage'              => 'Дневник на заштитувања',
'protectlogtext'              => 'Подолу е листа на отклучувања/заклучувања на страницата. 
Погледни [[Special:ProtectedPages|листа на заштитени страници]] за листа на тековни заштити на страници.',
'protectedarticle'            => 'заштитена "[[$1]]"',
'modifiedarticleprotection'   => 'сменето ниво на заштита за „[[$1]]“',
'unprotectedarticle'          => 'отстранета заштита на "[[$1]]"',
'movedarticleprotection'      => 'преместени нагодувања за заштита од "[[$2]]" во "[[$1]]"',
'protect-title'               => 'Заштитување "$1"',
'prot_1movedto2'              => '[[$1]] преместена како [[$2]]',
'protect-legend'              => 'Потврдете ја заштитата',
'protectcomment'              => 'Причина за заштитување',
'protectexpiry'               => 'Истекува:',
'protect_expiry_invalid'      => 'Времето на истекување не е добро дефинирано.',
'protect_expiry_old'          => 'Времето на истекување е постаро од тековното време.',
'protect-unchain'             => 'Отклучи ја можноста за преместување',
'protect-text'                => "Овде можете да го погледнете или смените нивото на заштита за страницата '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "Не можете да ја менувате заштитата додека сте блокирани. Овде се
дадени актуелните нагодувања за страницата '''$1''':",
'protect-locked-dblock'       => "Нивото на заштита не може да биде променето, бидејќи базата на податоци е заклучена.
Овде се дадени тековните нагодувања на страницата '''$1''':",
'protect-locked-access'       => "Вашата корисничка сметка нема дозвола за менување на нивото на заштита.
Овде се дадени тековните нагодувања за оваа страница '''$1''':",
'protect-cascadeon'           => 'Оваа страница во овој момент е заштитена бидејќи е вклучена во {{PLURAL:$1|следнава страница, за која е|следниве страници, за кои е}} вклучена каскадна заштита.
Можете да го промените нивото на заштита, но тоа нема да влијае на каскадната заштита.',
'protect-default'             => 'Допуштено за сите корисници',
'protect-fallback'            => 'Бара "$1" пермисија',
'protect-level-autoconfirmed' => 'Блокирај нови и нерегистрирани корисници',
'protect-level-sysop'         => 'Само администратори',
'protect-summary-cascade'     => 'каскада',
'protect-expiring'            => 'истекува на $1 (UTC)',
'protect-expiry-indefinite'   => 'бесконечно',
'protect-cascade'             => 'Заштитени страници вклучени во оваа страница (каскадна заштита)',
'protect-cantedit'            => 'Не можете да го промените нивото на заштита на оваа страница, бидејќи немате пермисии за тоа.',
'protect-othertime'           => 'Друго време:',
'protect-othertime-op'        => 'друго време',
'protect-existing-expiry'     => 'Постоечки рок на истекување: $3, $2',
'protect-otherreason'         => 'Друга/дополнителна причини:',
'protect-otherreason-op'      => 'друга/дополнителна причина',
'protect-dropdown'            => '*Вообичаени причини за заштита
** Зачестен вандализам
** Зачестен спем
** Бескорисна уредувачка војна
** Страница со зачестена посета',
'protect-edit-reasonlist'     => 'Уреди причини за заштита',
'protect-expiry-options'      => '1 час: 1 hour,1 ден:1 day,1 недела:1 week,2 недели:2 weeks,1 месец:1 month,3 месеци:3 months,6 месеци:6 months,1 година:1 year,бесконечно:infinite', # display1:time1,display2:time2,...
'restriction-type'            => 'Пермисии:',
'restriction-level'           => 'Ниво на заштита:',
'minimum-size'                => 'Минимална големина',
'maximum-size'                => 'Максимална големина',
'pagesize'                    => '(бајти)',

# Restrictions (nouns)
'restriction-edit'   => 'Уредување',
'restriction-move'   => 'Преместување',
'restriction-create' => 'Креирање',
'restriction-upload' => 'Подигнување',

# Restriction levels
'restriction-level-sysop'         => 'целосно заштитено',
'restriction-level-autoconfirmed' => 'делумно заштитено',
'restriction-level-all'           => 'сите нивоа',

# Undelete
'undelete'                   => 'Врати избришани страници',
'undeletepage'               => 'Погледај и врати избришани страници',
'viewdeletedpage'            => 'Прегледај ги избришаните страници',
'undeletepagetext'           => '{{PLURAL:$1|Следната страница била избришана но сеуште е во архивата и може да биде вратена.|Следните $1 страници биле избришани но сеуште се во архивата и можат да бидат вратени.}}
Архивата може периодично да се чисти.',
'undelete-fieldset-title'    => 'Обнови ревизии',
'undeleteextrahelp'          => "За да вратите целосна историја на страница, одселектирајте ги сите полиња и притиснете на '''''Врати'''''.
За да извршите селективно враќање, селектирајте ги полињата до соодветните ревизии за враќање и притиснете на '''''Врати'''''.
Со притискање на '''''Ресетирај''''' го бришете коментарот и сите полиња за штиклирање.",
'undeleterevisions'          => '{{PLURAL:$1|$1 измена е архивирана|$1 измени се архивирани}}',
'undeletehistory'            => 'Ако ја обновите страницата, сите поправки ќе бидат вратени во историјата.
Ако нова страница со исто име е создадена по бришењето, обновените поправки ќе се појават во претходната историја.',
'undeletehistorynoadmin'     => 'Оваа статија беше избришана. Причината за бришењето е наведена подолу,
заедно со информации за корисникот кој ја уредувал страницата пред бришењето. Целиот текст
од избришаните верзии е достапен само за администраторите.',
'undelete-revision'          => 'Избришана ревизија на $1 (од $4, во $5) уредувач $3:',
'undeleterevision-missing'   => 'Грешна или непостоечка ревизија.
Можеби имате лоша врска, ревизијата била обновена или избришана од архивата.',
'undelete-nodiff'            => 'Не постои постара ревизија.',
'undeletebtn'                => 'Обнови',
'undeletelink'               => 'погледни/обнови',
'undeletereset'              => 'Ресетирај',
'undeleteinvert'             => 'Инверзна селекција',
'undeletecomment'            => 'Коментар:',
'undeletedarticle'           => 'обновена "[[$1]]"',
'undeletedrevisions'         => '{{PLURAL:$1|1 измена е обновена|$1 измени се обновени}}',
'undeletedrevisions-files'   => '{{PLURAL:$1|1 измена|$1 измени}} и {{PLURAL:$2|1 податотека|$2 податотеки}} се вратени',
'undeletedfiles'             => '{{PLURAL:$1|1 податотека е вратена|$1 податотеки се вратени}}',
'cannotundelete'             => 'Враќањето не успеа, некој друг можеби ја вратил страницата претходно.',
'undeletedpage'              => "<big>'''$1 беше обновена'''</big>

Погледни [[Special:Log/delete|дневник на бришења]] за листа на претходни бришења или обновувања.",
'undelete-header'            => 'Погледни во [[Special:Log/delete|дбевникот на бришења]] за скоро избришани страници.',
'undelete-search-box'        => 'Пребарување на ибришани страници',
'undelete-search-prefix'     => 'Прикажи страници кои почнуваат со:',
'undelete-search-submit'     => 'Барај',
'undelete-no-results'        => 'Не се пронајдени соодветни страници во дневникот на бришења.',
'undelete-filename-mismatch' => 'Не може да се обнови бараната ревизија на податотеката од $1: името не соодветствува',
'undelete-bad-store-key'     => 'Не може да се обнови ревизија на податотека до $1: податотеката ја снемало пред да биде избришана.',
'undelete-cleanup-error'     => 'Грешка при бришење на некористена архивска податотека "$1".',
'undelete-error-short'       => 'Грешка при обновување на податотека: $1',
'undelete-error-long'        => 'Се појавија грешки при обновувањето на податотеката:

$1',
'undelete-show-file-confirm' => 'Дали навистина сакате да ја погледнете избришаната ревизија на податотеката "<nowiki>$1</nowiki>" од $2 на $3?',
'undelete-show-file-submit'  => 'Да',

# Namespace form on various pages
'namespace'      => 'Именски простор:',
'invert'         => 'Инверзна селекција',
'blanknamespace' => '(Главен)',

# Contributions
'contributions'       => 'Кориснички придонеси',
'contributions-title' => 'Кориснички придонеси за $1',
'mycontris'           => 'Мои придонеси',
'contribsub2'         => 'За $1 ($2)',
'nocontribs'          => 'Не се пронајдени промени што одговараат на овој критериум.', # Optional parameter: $1 is the user name
'uctop'               => ' (врв)',
'month'               => 'Од месец (и порано):',
'year'                => 'Од година (и порано):',

'sp-contributions-newbies'       => 'Прикажи придонеси само на нови корисници',
'sp-contributions-newbies-sub'   => 'За нови кориснички сметки',
'sp-contributions-newbies-title' => 'Придонеси на нови корисници',
'sp-contributions-blocklog'      => 'Дневник на блокирања',
'sp-contributions-search'        => 'Пребарување на придонеси',
'sp-contributions-username'      => 'IP адреса или корисничко име:',
'sp-contributions-submit'        => 'Пребарај',

# What links here
'whatlinkshere'            => 'Што води овде',
'whatlinkshere-title'      => 'Страници со врски што водат до "$1"',
'whatlinkshere-page'       => 'Страница:',
'whatlinkshere-backlink'   => '← $1',
'linkshere'                => "Следните страници имаат врска до '''[[:$1]]''':",
'nolinkshere'              => "Нема страници со врска кон '''[[:$1]]'''.",
'nolinkshere-ns'           => "Нема страници со врска кон '''[[:$1]]''' во избраниот именски простор.",
'isredirect'               => 'пренасочувачка страница',
'istemplate'               => 'вклучување',
'isimage'                  => 'врска кон слика',
'whatlinkshere-prev'       => '{{PLURAL:$1|претходна|претходни $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|следна|следни $1}}',
'whatlinkshere-links'      => '← врски',
'whatlinkshere-hideredirs' => '$1 пренасочувања',
'whatlinkshere-hidetrans'  => '$1 трансклузии',
'whatlinkshere-hidelinks'  => '$1 врски',
'whatlinkshere-hideimages' => '$1 врски кон слика',
'whatlinkshere-filters'    => 'Филтри',

# Block/unblock
'blockip'                         => 'Блокирај корисник',
'blockip-legend'                  => 'Блокирај корисник',
'blockiptext'                     => 'Користете го долниот формулар да го забраните пристапот за пишување од одредена IP адреса или корисничко име.
Ова единствено треба да се прави за да се спречи вандализам, во согласност со [[{{MediaWiki:Policy-url}}|политиката на википедија]].
Изберете конкретна причина подолу (пример: цитирање на конкретни страници што биле вандализирани).',
'ipaddress'                       => 'IP адреса',
'ipadressorusername'              => 'IP адреса или корисничко име:',
'ipbexpiry'                       => 'Истек на рокот:',
'ipbreason'                       => 'Причина:',
'ipbreasonotherlist'              => 'Друга причина',
'ipbreason-dropdown'              => '*Вообичаени причини за блокирање
** Вметнување лажни информации
** Бришење на содржини од страниците
** Спем врски кон надворешни веб места
** Вметнување глупости во страниците
** Непристојно однесување
** Навредување на други корисници
** Неприфатливо корисничко име',
'ipbanononly'                     => 'Блокирај само анонимни корисници',
'ipbcreateaccount'                => 'Оневозможи создавање кориснички сметки',
'ipbemailban'                     => 'Оневозможи го корисникот да праќа е-пошта',
'ipbenableautoblock'              => 'Автоматски блокирај ја последната IP адреса што ја користел корисникот и сите понатамошни адреси од кои ќе се обиде да уредува',
'ipbsubmit'                       => 'Блокирај го овој корисник',
'ipbother'                        => 'Друг рок:',
'ipboptions'                      => '2 часа:2 hours,1 ден:1 day,3 дена:3 days,1 седмица:1 week,2 седмици:2 weeks,1 месец:1 month,3 месеци:3 months,6 месеци:6 months,1 година:1 year,бесконечно:infinite', # display1:time1,display2:time2,...
'ipbotheroption'                  => 'друго',
'ipbotherreason'                  => 'Друга, дополнителна причина:',
'ipbhidename'                     => 'Сокриј го корисничкото име во дневникот на блокирања, активната листа на блокирања и листата на корисници',
'ipbwatchuser'                    => 'Набљудувај ја корисничката страница и страницата за разговор на овој корисник',
'ipballowusertalk'                => 'Овозможи овој корисник да ја уредува својата страница за разговор додека е блокиран',
'ipb-change-block'                => 'Повторно блокирај го корисникот со овие нагодувања',
'badipaddress'                    => 'Невалидна IP адреса',
'blockipsuccesssub'               => 'Успешно блокирање',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] беше блокиран.<br />
Види [[Special:IPBlockList|листа на блокирани IP адреси]] за преглед на блокирањата.',
'ipb-edit-dropdown'               => 'Наведи причина за блокирање',
'ipb-unblock-addr'                => 'Одблокирај го $1',
'ipb-unblock'                     => 'Одблокирај корисник или  IP адреса',
'ipb-blocklist-addr'              => 'Активни блокирања за $1',
'ipb-blocklist'                   => 'Преглед на активни блокирања',
'ipb-blocklist-contribs'          => 'Придонеси на $1',
'unblockip'                       => 'Деблокирај корисник',
'unblockiptext'                   => 'Користете го долниот формулар да го обновите правото на пишување на претходно блокирана IP адреса или корисничко име.',
'ipusubmit'                       => 'Избриши го ова блокирање',
'unblocked'                       => '[[User:$1|$1]] беше деблокиран',
'unblocked-id'                    => 'Блокирањето $1 беше отстрането',
'ipblocklist'                     => 'Блокирани IP адреси и кориснички имиња',
'ipblocklist-legend'              => 'Најди блокиран корисник',
'ipblocklist-username'            => 'Корисничко име или IP адреса:',
'ipblocklist-sh-userblocks'       => '$1 блокирани кориснички сметки',
'ipblocklist-sh-tempblocks'       => '$1 привремени блокирања',
'ipblocklist-sh-addressblocks'    => '$1 блокирани поединечни IP адреси',
'ipblocklist-submit'              => 'Пребарај',
'blocklistline'                   => '$1, $2 го блокираше $3 ($4)',
'infiniteblock'                   => 'бесконечно',
'expiringblock'                   => 'истекува на $1',
'anononlyblock'                   => 'само анон.',
'noautoblockblock'                => 'оневозможено автоблокирање',
'createaccountblock'              => 'блокирано креирање сметки',
'emailblock'                      => 'блокирана е-пошта',
'blocklist-nousertalk'            => 'без можност за уредување на својата страница за разговор',
'ipblocklist-empty'               => 'Листата на блокирања е празна.',
'ipblocklist-no-results'          => 'Бараната IP адреса или корисничка сметка не е блокирана.',
'blocklink'                       => 'блокирај',
'unblocklink'                     => 'деблокирај',
'change-blocklink'                => 'промена на блок',
'contribslink'                    => 'придонеси',
'autoblocker'                     => 'Автоматски сте блокирани бидејќи вашата IP адреса била скоро користена од "[[User:$1|$1]]".
Причината за блокирање на $1 била: "$2"',
'blocklogpage'                    => 'Дневник на блокирања',
'blocklog-fulllog'                => 'Комплетен дневник на блокирања',
'blocklogentry'                   => 'блокирано [[$1]] со рок на истекување до $2, $3',
'reblock-logentry'                => 'променети нагодувања за блокирање на [[$1]] со рок на истекување од $2 $3',
'blocklogtext'                    => 'Ова е дневник на акции за блокирање и деблокирање, на овој корисник.
Автоматски блокираните IP адреси не се наведени.
Видете [[Special:IPBlockList|листата на блокирани IP адреси]] за листа на тековни забрани и блокирања.',
'unblocklogentry'                 => 'деблокиран "$1"',
'block-log-flags-anononly'        => 'само анонимни корисници',
'block-log-flags-nocreate'        => 'оневозможено креирање на кориснички сметки',
'block-log-flags-noautoblock'     => 'автоблокирање исклучено',
'block-log-flags-noemail'         => 'блокирана е-поштенска адреса',
'block-log-flags-nousertalk'      => 'без можност за уредување на својата страница за разговор',
'block-log-flags-angry-autoblock' => 'овозможено проширено автоблокирање',
'range_block_disabled'            => 'Администраторската можност да блокираат IP групи е исклучена.',
'ipb_expiry_invalid'              => 'Погрешен рок на истекување.',
'ipb_expiry_temp'                 => 'Скриените блокирања на корисникот мора да бидат перманентни.',
'ipb_already_blocked'             => '„$1“ е веќе блокиран',
'ipb-needreblock'                 => '== Веќе блокиран ==
$1 е веќе блокиран. Дали сакате да направите промена?',
'ipb_cant_unblock'                => 'Грешка: Блокирањето $1 не постои. 
Можеби веќе е одблокиран.',
'ipb_blocked_as_range'            => 'Грешка: IP адресата $1 не е директно блокирана и не може да се деблокира.
Таа е блокирана како дел од блокот адреси $2, кој не може да се деблокира.',
'ip_range_invalid'                => 'Неважечки IP дијапазон на адреси.',
'blockme'                         => 'Блокирај ме',
'proxyblocker'                    => 'Прокси блокирач',
'proxyblocker-disabled'           => 'Оваа функција е оневозможена.',
'proxyblockreason'                => 'Вашата IP адреса е блокирана бидејќи е отворен прокси. 
Ве молиме контактирајте со вашиот доставувач на Интернет услуги или техничката поддршка и информирајте ги за овој сериозен безбедносен проблем.',
'proxyblocksuccess'               => 'Готово.',
'sorbs'                           => 'DNSBL',
'sorbsreason'                     => 'Вашата IP адреса е запишана како отворен прокси во DNSBL кој го користи {{SITENAME}}..',
'sorbs_create_account_reason'     => 'Вашата IP адреса е наведена како отворен прокси во DNSBL користена од {{SITENAME}}.
Не можете да креирате корисничка сметка.',
'cant-block-while-blocked'        => 'Не можете да блокирате други корисници додека и вие самите сте блокирани.',

# Developer tools
'lockdb'              => 'Заклучи база на податоци',
'unlockdb'            => 'Отклучи база на податоци',
'lockdbtext'          => 'Заклучувањето на базата на податоци ќе им ја укине можноста на сите корисници да уредуваат страници, да ги менуваат нивните нагодувања, да ги уредуваат нивните листи на набљудувања и се останато што бара промени во базата. 
Ве молиме потврдете дека ова е вашата намера и дека ќе ја отклучите базата кога ќе ја завршите работата околу нејзиното одржување.',
'unlockdbtext'        => 'Отклучувањето на базата ќе им ја врати можноста на сите корисници да уредуваат страници, да ги менуваат нивните нагодувања, да ги уредуваат нивните листи на набљудувања и се останато што бара промени во базата.
Ве молиме потврдете дека ова е вашата намера.',
'lockconfirm'         => 'Да, навистина сакам да ја заклучам базата на податоци.',
'unlockconfirm'       => 'Да, навистина сакам да ја отклучам базата на податоци.',
'lockbtn'             => 'Заклучи база на податоци',
'unlockbtn'           => 'Отклучи база на податоци',
'locknoconfirm'       => 'Не ја потврдивте вашата намера.',
'lockdbsuccesssub'    => 'Успешно заклучување на базата на податоци',
'unlockdbsuccesssub'  => 'Базата е отклучена',
'lockdbsuccesstext'   => 'Базата е заклучена. <br />
Сетете се да ја [[Special:UnlockDB|отклучите]] кога ќе завршите со нејзиното одржување.',
'unlockdbsuccesstext' => 'Базата е отклучена.',
'lockfilenotwritable' => 'Податотеката за заклучување на базата не е отворена за пишување. 
За да ја заклучите или отклучите базата, податотеката мора да биде достапна за пишување од страна на веб серверот.',
'databasenotlocked'   => 'Базата не е заклучена.',

# Move page
'move-page'                    => 'Премести $1',
'move-page-backlink'           => '← $1',
'move-page-legend'             => 'Премести страница',
'movepagetext'                 => "Со користењето на овој формулар можете да преименувате страница, преместувајќи ја целата нејзина историја под ново име. 
Стариот наслов ќе стане страница за пренасочување кон новиот наслов.
Автоматски можете да ги ажурирате пренасочувањата кои покажуваат кон првобитниот наслов.
Ако не изберете автоматско ажурирање, проверете на [[Special:DoubleRedirects|двојни]] или [[Special:BrokenRedirects|прекинати пренасочувања]].
На вас е одговорноста да се осигурате дека врските ќе продолжат да насочуваат таму за каде се предвидени. 

Имајте во предвид дека страницата '''НЕМА''' да биде преместена ако веќе постои страница со новиот наслов, освен ако е празна или ако е пренасочување и нема историја на минати уредувања. Тоа значи дека можете да ја преименувате страницата како што била претходно доколку сте направиле грешка без да ја прекриете постоечката страница.

'''ПРЕДУПРЕДУВАЊЕ!'''
Ова може да биде драстична и неочекувана промена за популарна страница;
осигурајте се дека сте ги разбрале последиците од ова пред да продолжите.",
'movepagetalktext'             => 'Соодветната страница за разговор, доколку постои, ќе биде автоматски преместена заедно со неа, освен ако:
*Непразна страница за разговор веќе постои за новиот наслов, или
*Не сте го одштиклирале долното поле. 

Во тие случаи, вие ќе треба да ја преместите или споите страницата рачно, доколку сакате.',
'movearticle'                  => 'Премести страница:',
'movenologin'                  => 'Не сте најавени',
'movenologintext'              => 'Мора да бидете регистриран корисник и [[Special:UserLogin|најавен]] за да преместите страница.',
'movenotallowed'               => 'Немате доволно привилегии за преместување на страници.',
'movenotallowedfile'           => 'Немате пермисии за преместување податотеки.',
'cant-move-user-page'          => 'Немате пермисии за преместување на основни кориснички страници (одделно од подстраници).',
'cant-move-to-user-page'       => 'Немате пермисии за преместување на страница во ваша корисничка страница (освен во корисничка подстраница)',
'newtitle'                     => 'Кон новиот наслов:',
'move-watch'                   => 'Набљудувај ја оваа страница',
'movepagebtn'                  => 'Премести страница',
'pagemovedsub'                 => 'Успешно преместување',
'movepage-moved'               => '<big>\'\'\'"$1" беше преместена во "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'movepage-moved-redirect'      => 'Беше креирано пренасочување.',
'movepage-moved-noredirect'    => 'Креирањето на пренасочување е исклучено.',
'articleexists'                => 'Страница со тоа име веќе постои или името што го избравте не е валидно.
Изберете друго име.',
'cantmove-titleprotected'      => 'Не може да ја преместите страницата на таа локација бидејќи има забрана за креирање на страница со нов наслов како што наведовте.',
'talkexists'                   => "'''Самата страница беше преместена успешно, но страницата за разговор не можеше да биде преместена бидејќи веќе постои таква на новиот наслов. 
Ве молиме спојте ги рачно.'''",
'movedto'                      => 'преместена како',
'movetalk'                     => 'Премести ја и страницата за разговор, ако е возможно.',
'move-subpages'                => 'Премести подстраници (најповеќе до $1), ако е можно',
'move-talk-subpages'           => 'Премести подстраници на страници за разговор (најповеќе до $1), ако е можно',
'movepage-page-exists'         => 'Страницата $1 веќе постои и не може автоматски да биде заменета.',
'movepage-page-moved'          => 'Страницата $1 беше преместена како $2.',
'movepage-page-unmoved'        => 'Страницата $1 не може да биде преместена во $2.',
'movepage-max-pages'           => 'Максимално $1 {{PLURAL:$1|страница|страници}} беа преместени, повеќе не може да бидат автоматски преместени.',
'1movedto2'                    => '[[$1]] преместена како [[$2]]',
'1movedto2_redir'              => '[[$1]] преместена како [[$2]] преку пренасочување',
'move-redirect-suppressed'     => 'исклучено пренасочување',
'movelogpage'                  => 'Дневник на преместувања',
'movelogpagetext'              => 'Подолу е листа на преместени страници.',
'movesubpage'                  => '{{PLURAL:$1|Подстраница|Подстраници}}',
'movesubpagetext'              => 'Оваа страница има $1 {{PLURAL:$1|подстраница прикажана|подстраници прикажани}} подолу.',
'movenosubpage'                => 'Оваа страница нема подстраници.',
'movereason'                   => 'Причина:',
'revertmove'                   => 'врати',
'delete_and_move'              => 'Избриши и премести',
'delete_and_move_text'         => '==Потребно бришење== 
Целната статија "[[:$1]]" веќе постои. 
Дали сакате да ја избришете за да ослободите место за преместувањето?',
'delete_and_move_confirm'      => 'Да, избриши ја страницата',
'delete_and_move_reason'       => 'Избришано за да се ослободи место за преместувањето',
'selfmove'                     => 'Страницата не може да биде преместена бидејќи целниот наслов се совпаѓа со првобитниот наслов;
не може да се премести страница во самата себе.',
'immobile-source-namespace'    => 'Не може да се преместуваат страници во именскиот простор "$1"',
'immobile-target-namespace'    => 'Не може да се преместуваат страници во именскиот простор "$1"',
'immobile-target-namespace-iw' => 'Интервики врска не е валидна цел за преместување на страница.',
'immobile-source-page'         => 'Оваа страница не може да се преместува.',
'immobile-target-page'         => 'Не може да се премести под бараниот наслов.',
'imagenocrossnamespace'        => 'Не може да се премести податотека во неподатотечен именски простор',
'imagetypemismatch'            => 'Новата наставка на податотеката не соодветствува на нејзиниот тип',
'imageinvalidfilename'         => 'Целниот наслов на податотеката е невалиден',
'fix-double-redirects'         => 'Ажурирајте ги сите пренасочувања кои покажуваат на првобитниот наслов',
'move-leave-redirect'          => 'Направи пренасочување',

# Export
'export'            => 'Извезување на страници',
'exporttext'        => 'Можете да го извезете текстот и историјата на уредување на избрана страница или група на страници во XML формат.
Овие податоци може да бидат вчитани на некое друго вики кое се користи со МедијаВики преку [[Special:Import|увезување на страница]].

За извезување на страници, внесете ги насловите во полето прикажано подолу, еден наслов на статија во ред, потоа изберете дали сакате да ја извезете само последната ревизија или и сите постари ревизии.

Ако ја сакате само тековната верзија, би можеле да искористите врска од видот [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] за страницата "[[{{MediaWiki:Mainpage}}]]".',
'exportcuronly'     => 'Вклучи ја само тековната верзија, не целосната историја',
'exportnohistory'   => "----
'''Забелешка:''' извезувањето на целата историја на страниците преку овој формулар е оневозможено заради подобри перформанси.",
'export-submit'     => 'Извези',
'export-addcattext' => 'Додади страници од категорија:',
'export-addcat'     => 'Додади',
'export-download'   => 'Зачувај како податотека',
'export-templates'  => 'Вклучи и шаблони',

# Namespace 8 related
'allmessages'               => 'Системски пораки',
'allmessagesname'           => 'Име',
'allmessagesdefault'        => 'Текст по основно',
'allmessagescurrent'        => 'Сегашен текст',
'allmessagestext'           => 'Ова е листа на системските пораки достапни во именскиот простор „МедијаВики“.
Ве молиме одете на [http://www.mediawiki.org/wiki/Localisation Локализација на МедијаВики] и [http://translatewiki.net Бетавики] ако сакате да придонесете кон генеричката локализацијата на МедијаВики.',
'allmessagesnotsupportedDB' => "Оваа страница не може да се користи бидејќи '''\$wgUseDatabaseMessages''' е исклучено.",
'allmessagesfilter'         => 'Филтер за регуларни изрази:',
'allmessagesmodified'       => 'Прикажи само изменети',

# Thumbnails
'thumbnail-more'           => 'Зголеми',
'filemissing'              => 'Недостасува податотека',
'thumbnail_error'          => 'Грешка во креирањето на мини-слика: $1',
'djvu_page_error'          => 'Недостапна DjVu страница',
'djvu_no_xml'              => 'Не е можно да се излачи XML за DjVu податотеки',
'thumbnail_invalid_params' => 'Погрешни параметри на мини слика',
'thumbnail_dest_directory' => 'Не може да се креира целниот именик',

# Special:Import
'import'                     => 'Увезување на страници',
'importinterwiki'            => 'Трансвики увезување',
'import-interwiki-text'      => 'Избери вики и наслов на страница за увоз.
Датумите и имињата на уредувачите ќе бидат зачувани.
Сите трансвики акции за увоз се запишуваат во [[Special:Log/import|дневникот на увезувања]].',
'import-interwiki-source'    => 'Извор вики/страница:',
'import-interwiki-history'   => 'Копирај ги сите постари верзии за оваа страница',
'import-interwiki-templates' => 'Вклучи ги сите шаблони',
'import-interwiki-submit'    => 'Увези',
'import-interwiki-namespace' => 'Целен именски простор:',
'import-upload-filename'     => 'Име на податотека:',
'import-comment'             => 'Коментар:',
'importtext'                 => 'Извезете ја податотеката од изворното вики со користење на [[Special:Export|алатката за извоз]].
Зачувајте ја на вашиот диск и подигнете ја овде.',
'importstart'                => 'Увоз на страници...',
'import-revision-count'      => '$1 {{PLURAL:$1|ревизија|ревизии}}',
'importnopages'              => 'Нема страници за увоз.',
'importfailed'               => 'Неуспешно внесување: $1',
'importunknownsource'        => 'Непознат тип за внесување',
'importcantopen'             => 'Не може да се отвори увезената податотека',
'importbadinterwiki'         => 'Лоша интервики врска',
'importnotext'               => 'Празно или без текст',
'importsuccess'              => 'Завршено увезување!',
'importhistoryconflict'      => 'Постои конфликтна историја на верзиите (можно е страницата веќе да била внесена)',
'importnosources'            => 'Нема дефинирано трансвики извори за увезување и директните подигнувања на историја се оневозможени.',
'importnofile'               => 'Нема подигнато увозна податотека.',
'importuploaderrorsize'      => 'Неуспешно подигнување на увозна податотека.
Големината на податотеката е поголема од допуштената големина.',
'importuploaderrorpartial'   => 'Неуспешно подигнувањето на увозна податотека.
Податотеката  е парцијално подигната.',
'importuploaderrortemp'      => 'Неуспешно подигнување на увозна податотека.
Проблеми со привремена папка за податотеки.',
'import-parse-failure'       => 'Погрешно XML парсирање',
'import-noarticle'           => 'Нема страница за увоз!',
'import-nonewrevisions'      => 'Сите ревизии се претходно увезени.',
'xml-error-string'           => '$1 на линија $2, колона $3 (бајт $4): $5',
'import-upload'              => 'Подигнување на XML податоци',
'import-token-mismatch'      => 'Губење на сесиските податоци. Обидете се повторно.',
'import-invalid-interwiki'   => 'Не може да се увезува од специфицираното вики.',

# Import log
'importlogpage'                    => 'Дневник на увезувања',
'importlogpagetext'                => 'Административно увезување на страници со историја на уредување од други викија.',
'import-logentry-upload'           => 'увезена [[$1]] со подигнување на податотека',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|ревизија|ревизии}}',
'import-logentry-interwiki'        => 'трансвикифиран $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|ревизија|ревизии}} од $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Вашата корисничка страница',
'tooltip-pt-anonuserpage'         => 'Корисничка страница за IP адресата од која уредувате',
'tooltip-pt-mytalk'               => 'Вашата страница за разговор',
'tooltip-pt-anontalk'             => 'Разговор за уредувањата од оваа IP адреса',
'tooltip-pt-preferences'          => 'Мои нагодувања',
'tooltip-pt-watchlist'            => 'Листа на страници кои сте избрале да ги набљудувате.',
'tooltip-pt-mycontris'            => 'Листа на ваши придонеси',
'tooltip-pt-login'                => 'Ве охрабруваме да се најавите, иако тоа не е задолжително.',
'tooltip-pt-anonlogin'            => 'Ве охрабруваме да се најавите, иако тоа не е задолжително.',
'tooltip-pt-logout'               => 'Одјавете се',
'tooltip-ca-talk'                 => 'Разговор за страницата',
'tooltip-ca-edit'                 => 'Можете да ја уредите оваа страница. Ве молиме користете го копчето за преглед пред зачувување.',
'tooltip-ca-addsection'           => 'Започни нова секција',
'tooltip-ca-viewsource'           => 'Оваа страница е заштитена. Можете да го видите изворниот код.',
'tooltip-ca-history'              => 'Претходни верзии на оваа страница.',
'tooltip-ca-protect'              => 'Заштитете ја оваа страница',
'tooltip-ca-delete'               => 'Избриши ја оваа страница',
'tooltip-ca-undelete'             => 'Обнови ги уредувањата направени на оваа страница пред да биде избришана',
'tooltip-ca-move'                 => 'Премести ја оваа страница',
'tooltip-ca-watch'                => 'Додади ја оваа страница во листата на набљудувања',
'tooltip-ca-unwatch'              => 'Отстрани ја оваа страница од листата на набљудувања',
'tooltip-search'                  => 'Пребарај низ {{SITENAME}}',
'tooltip-search-go'               => 'Оди на страница со ова име доколку постои',
'tooltip-search-fulltext'         => 'Пребарај го овој текст низ странците',
'tooltip-p-logo'                  => 'Главна страница',
'tooltip-n-mainpage'              => 'Посети ја главната страница',
'tooltip-n-portal'                => 'За проектот, што можете да направите, каде да најдете некои работи',
'tooltip-n-currentevents'         => 'Најдете информации за тековните настани',
'tooltip-n-recentchanges'         => 'Листа на скорешни промени на викито.',
'tooltip-n-randompage'            => 'Вчитај случајна страница',
'tooltip-n-help'                  => 'Место каде што може да се информирате.',
'tooltip-t-whatlinkshere'         => 'Листа на сите вики страници поврзани овде',
'tooltip-t-recentchangeslinked'   => 'Скорешни промени поврзани од оваа страница',
'tooltip-feed-rss'                => 'RSS feed за оваа страница',
'tooltip-feed-atom'               => 'Atom feed за оваа страница',
'tooltip-t-contributions'         => 'Погледни листа на придонеси на овој корисник',
'tooltip-t-emailuser'             => 'Прати е-пошта на овој корисник',
'tooltip-t-upload'                => 'Подигнување податотеки',
'tooltip-t-specialpages'          => 'Листа на сите специјални страници',
'tooltip-t-print'                 => 'Верзија за печатење на оваа страница',
'tooltip-t-permalink'             => 'Перманентна врска до оваа верзија од страницата',
'tooltip-ca-nstab-main'           => 'Поглед на содржината',
'tooltip-ca-nstab-user'           => 'Поглед на корисничката страница',
'tooltip-ca-nstab-media'          => 'Поглед на медија страницата',
'tooltip-ca-nstab-special'        => 'Ова е специјална страница, не можете да ја уредувате',
'tooltip-ca-nstab-project'        => 'Поглед на проектната страница',
'tooltip-ca-nstab-image'          => 'Поглед на страницата на податотеката',
'tooltip-ca-nstab-mediawiki'      => 'Поглед на системската порака',
'tooltip-ca-nstab-template'       => 'Поглед на шаблонот',
'tooltip-ca-nstab-help'           => 'Поглед на страницата за помош',
'tooltip-ca-nstab-category'       => 'Поглед на страницата на категоријата',
'tooltip-minoredit'               => 'Обележете ја промената како ситна',
'tooltip-save'                    => 'Зачувајте ги вашите промени',
'tooltip-preview'                 => 'Прегледајте ги вашите промени, ве молиме користете го ова пред зачувување!',
'tooltip-diff'                    => 'Покажи кои промени ги направи во текстот.',
'tooltip-compareselectedversions' => 'Види разлика помеѓу двете избрани верзии на оваа страница.',
'tooltip-watch'                   => 'Додади ја оваа страница во својата листа на набљудувања',
'tooltip-recreate'                => 'Повторно создади ја страницата иако беше избришана',
'tooltip-upload'                  => 'Започни со подигнување',
'tooltip-rollback'                => '„Врати“ ги поништува последните уредувања на оваа страница со еден клик до уредувањата на претпоследниот придонесувач',
'tooltip-undo'                    => '„Откажи“ го поништува ова уредување и отвора прозорец за уредување.
Дозволува додавање на причина во описот',

# Stylesheets
'common.css'      => '/** CSS кодот на оваа страница ќе се примени на сите маски */',
'standard.css'    => '/* CSS кодот поставен тука ќе се рефлектира врз корисниците кои избрале Standard маска */',
'nostalgia.css'   => '/* CSS кодот поставен тука ќе се рефлектира врз корисниците кои избрале Nostalgia маска */',
'cologneblue.css' => '/* CSS кодот поставен тука ќе се рефлектира врз корисниците кои избрале Cologne Blue маска */',
'monobook.css'    => '/* CSS кодот поставен тука ќе се рефлектира врз корисниците кои избрале Monobook маска */',
'myskin.css'      => '/* CSS кодот поставен тука ќе се рефлектира врз корисниците кои избрале Myskin маска */',
'chick.css'       => '/* CSS кодот поставен тука ќе се рефлектира врз корисниците кои одбрале Chick маска */',
'simple.css'      => '/* CSS кодот поставен тука ќе се рефлектира врз корисниците кои одбрале Simple маска */',
'modern.css'      => '/* CSS кодот поставен тука ќе се рефлектира врз корисниците кои одбрале Modern маска */',
'print.css'       => '/* CSS кодот поставен тука ќе се рефлектира врз страниците за печатење */',
'handheld.css'    => '/* CSS кодот поставен тука ќе се рефлектира врз рачните уреди засновани на маската поставена во $wgHandheldStyle */',

# Scripts
'common.js'      => '/* Било која Јава скрипта пиставена овде ќе се вчита кај сите корисници при секое вчитување на било која страница. */',
'standard.js'    => '/* Било која Јава скрипта поставена овде ќе биде вчитана за сите корисници кои користат Standard маска */',
'nostalgia.js'   => '/* Било која Јава скрипта поставена овде ќе биде вчитана за сите корисници кои користат Nostalgia маска */',
'cologneblue.js' => '/* Било која Јава скрипта поставена овде ќе биде вчитана за сите корисници кои користат Cologne Blue маска */',
'monobook.js'    => '/* Било која Јава скрипта поставена овде ќе биде вчитана за сите корисници кои користат MonoBook маска */',
'myskin.js'      => '/* Било која Јава скрипта поставена овде ќе биде вчитана за сите корисници кои користат Myskin маска */',
'chick.js'       => '/* Било која Јава скрипта поставена овде ќе биде вчитана за сите корисници кои користат Chick маска */',
'simple.js'      => '/* Било која Јава скрипта поставена овде ќе биде вчитана за сите корисници кои користат Simple маска */',
'modern.js'      => '/* Било која Јава скрипта поставена овде ќе биде вчитана за сите корисници кои користат Modern маска */',

# Metadata
'nodublincore'      => 'Dublin Core RDF метаподатоци се оневозможени за овој сервер.',
'nocreativecommons' => 'Creative Commons RDF метаподатоци се оневозможени за овој сервер.',
'notacceptable'     => 'Вики серверот не може да генерира податоци во формат погоден за вашиот клиент.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Анонимен корисник|Анонимни корисници}} на {{SITENAME}}',
'siteuser'         => '{{SITENAME}} корисник $1',
'lastmodifiedatby' => 'Оваа страница последен пат била модифицирана на $2, $1 од $3.', # $1 date, $2 time, $3 user
'othercontribs'    => 'Засновано на придонесот на $1.',
'others'           => 'други',
'siteusers'        => '{{SITENAME}} {{PLURAL:$2|корисник|корисници}} $1',
'creditspage'      => 'Автори на страницата',
'nocredits'        => 'Не постојат податоци за авторите на оваа страница.',

# Spam protection
'spamprotectiontitle' => 'Филтер за заштита од спам',
'spamprotectiontext'  => 'Страницата која сакавте да ја зачувате беше блокирана од филтерот за спам. 
Ова најверојатно е предизвикано од врска кон надворешно веб место кое се наоѓа на црната листа.',
'spamprotectionmatch' => 'Следниот текст е она што го предизвика нашиот филтер за спам: $1',
'spambot_username'    => 'МедијаВики чистач на спам',
'spam_reverting'      => 'Враќам на последната верзија што не содржи врска до $1',
'spam_blanking'       => 'Сите верзии содржеа врски до $1, бришам',

# Info page
'infosubtitle'   => 'Информација за страница',
'numedits'       => 'Број на уредувања (страница): $1',
'numtalkedits'   => 'Број на уредувања (страница за разговор): $1',
'numwatchers'    => 'Број на набљудувачи: $1',
'numauthors'     => 'Број на различни автори (страница): $1',
'numtalkauthors' => 'Број на различни автори (страница за разговор): $1',

# Skin names
'skinname-standard'    => 'Класика',
'skinname-nostalgia'   => 'Носталгија',
'skinname-cologneblue' => 'Келнско сино',
'skinname-monobook'    => 'Monobook',
'skinname-myskin'      => 'Моја маска',
'skinname-chick'       => 'Шик',

# Math options
'mw_math_png'    => 'Секогаш исцртувај во PNG',
'mw_math_simple' => 'HTML ако е многу едноставно ако не во PNG',
'mw_math_html'   => 'HTML ако е можно ако не PNG',
'mw_math_source' => 'Остави го како TeX (за текст веб прелистувачи)',
'mw_math_modern' => 'Препорачливо за современи веб прелистувачи',
'mw_math_mathml' => 'MathML ако е можно (експериментално)',

# Patrolling
'markaspatrolleddiff'                 => 'Означи како проверена верзија',
'markaspatrolledtext'                 => 'Означи ја верзијата како проверена',
'markedaspatrolled'                   => 'Означено како проверено',
'markedaspatrolledtext'               => 'Избраната ревизија е означена како проверена.',
'rcpatroldisabled'                    => 'Оневозможено проверка на скорешни промени',
'rcpatroldisabledtext'                => 'Патролирањето на Скорешните промени е деактивирано.',
'markedaspatrollederror'              => 'Не можам да означам како проверена',
'markedaspatrollederrortext'          => 'Морате да внесете верзија за да ја означите како проверена.',
'markedaspatrollederror-noautopatrol' => 'Не можете да ги означите своите промени како проверени.',

# Patrol log
'patrol-log-page'      => 'Дневник на патролирања',
'patrol-log-header'    => 'Ова е дневник на патролирани ревизии.',
'patrol-log-line'      => 'означено $1 од $2 како патролирано $3',
'patrol-log-auto'      => '(автоматски)',
'patrol-log-diff'      => 'ревизија $1',
'log-show-hide-patrol' => '$1 дневник на патролирање',

# Image deletion
'deletedrevision'                 => 'Избришана стара ревизија $1.',
'filedeleteerror-short'           => 'Грешка при бришење на податотека: $1',
'filedeleteerror-long'            => 'Се појавија грешки при бришењето на податотеката:

$1',
'filedelete-missing'              => 'Податотеката "$1" не може да се избрише, бидејќи не постои.',
'filedelete-old-unregistered'     => 'Наведената ревизија на податотеката "$1" не се наоѓа во базата на податоци.',
'filedelete-current-unregistered' => 'Наведената податотека "$1" не се наоѓа во базата на податоци.',
'filedelete-archive-read-only'    => 'Во именикот за архивирање "$1" веб серверот не може да запишува.',

# Browsing diffs
'previousdiff' => '← Постаро уредување',
'nextdiff'     => 'Поново уредување →',

# Visual comparison
'visual-comparison' => 'Визуелна споредба',

# Media information
'mediawarning'         => "'''Предупредување''': Оваа податотека може да содржи штетен код, нејзиното извршување може да му наштети на вашиот систем<hr />",
'imagemaxsize'         => 'Ограничи ги сликите на нивните описни страници до:',
'thumbsize'            => 'Големина на мини-слика:',
'widthheight'          => '$1×$2',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|страница|страници}}',
'file-info'            => '(големина: $1, MIME тип: $2)',
'file-info-size'       => '($1 × $2 пиксели, големина: $3, MIME тип: $4)',
'file-nohires'         => '<small>Не е достапна поголема резолуција.</small>',
'svg-long-desc'        => '(SVG податотека, номинално $1 × $2 пиксели, големина: $3)',
'show-big-image'       => 'Вистинска големина',
'show-big-image-thumb' => '<small>Големина на овој приказ: $1 × $2 пиксели</small>',

# Special:NewFiles
'newimages'             => 'Галерија на нови податотеки',
'imagelisttext'         => "Следи листа на '''$1''' {{PLURAL:$1|податотека|податотеки}} подредени $2.",
'newimages-summary'     => 'Оваа специјална страница ги покажува скоро подигнатите податотеки.',
'newimages-legend'      => 'Филтрирај',
'newimages-label'       => 'Име на податотека (или дел од името):',
'showhidebots'          => '($1 ботови)',
'noimages'              => 'Нема ништо.',
'ilsubmit'              => 'Барај',
'bydate'                => 'по датум',
'sp-newimages-showfrom' => 'Прикажи нови податотеки од $2, $1',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'video-dims'     => '$1, $2×$3',
'seconds-abbrev' => 's',
'minutes-abbrev' => 'm',
'hours-abbrev'   => 'h',

# Bad image list
'bad_image_list' => 'Форматот е следниот:

Само набројувањата (редици кои започнуваат со *) се земаат во предвид.
Првата врска во редот мора да биде врска кон погрешна податотека.
Сите следни врски во истата редица се претпоставува дека се исклучоци, т.е. страници каде податотеката може да се појави во редицата.',

/*
Short names for language variants used for language conversion links.
To disable showing a particular link, set it to 'disable', e.g.
'variantname-zh-sg' => 'disable',
Variants for Chinese language
*/
'variantname-zh-hans' => 'hans',
'variantname-zh-hant' => 'hant',
'variantname-zh-cn'   => 'cn',
'variantname-zh-tw'   => 'tw',
'variantname-zh-hk'   => 'hk',
'variantname-zh-mo'   => 'mo',
'variantname-zh-sg'   => 'sg',
'variantname-zh-my'   => 'my',
'variantname-zh'      => 'zh',

# Variants for Serbian language
'variantname-sr-ec' => 'sr-ec',
'variantname-sr-el' => 'sr-el',
'variantname-sr'    => 'sr',

# Variants for Kazakh language
'variantname-kk-kz'   => 'kk-kz',
'variantname-kk-tr'   => 'kk-tr',
'variantname-kk-cn'   => 'kk-cn',
'variantname-kk-cyrl' => 'kk-cyrl',
'variantname-kk-latn' => 'kk-latn',
'variantname-kk-arab' => 'kk-arab',
'variantname-kk'      => 'kk',

# Variants for Kurdish language
'variantname-ku-arab' => 'ku-Arab',
'variantname-ku-latn' => 'ku-Latn',
'variantname-ku'      => 'ku',

# Variants for Tajiki language
'variantname-tg-cyrl' => 'tg-Cyrl',
'variantname-tg-latn' => 'tg-Latn',

# Metadata
'metadata'          => 'Метаподатоци',
'metadata-help'     => 'Оваа податотека содржи дополнителни информации, најверојатно додадени од дигитална камера или скенер користени за нејзино создавање или дигитализација. Ако при тоа податотеката претрпела промени, некои детали може да не соодветствуваат во целост после промената на податотеката.',
'metadata-expand'   => 'Прикажи проширени информации',
'metadata-collapse' => 'Сокриј проширени информации',
'metadata-fields'   => 'EXIF мета полињата прикажани во оваа порака ќе бидат вклучени на страницата на сликата кога мета табелата ќе биде затворена.
Останатите ќе бидат сокриени по основно.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

# EXIF tags
'exif-imagewidth'                  => 'Ширина',
'exif-imagelength'                 => 'Висина',
'exif-bitspersample'               => 'Битови по компонента',
'exif-compression'                 => 'Шема на компресијата',
'exif-photometricinterpretation'   => 'Колор модел',
'exif-orientation'                 => 'Ориентација',
'exif-samplesperpixel'             => 'Број на колор компоненти',
'exif-planarconfiguration'         => 'Принцип на распоредот на податоците',
'exif-ycbcrsubsampling'            => 'Однос на компонентата Y спрема C',
'exif-ycbcrpositioning'            => 'Y и C позиционирање',
'exif-xresolution'                 => 'Хоризонтална резолуција',
'exif-yresolution'                 => 'Вертикална резолуција',
'exif-resolutionunit'              => 'Единица за резолуција',
'exif-stripoffsets'                => 'Положба на блокот на податоци',
'exif-rowsperstrip'                => 'Број на редови во блокот',
'exif-stripbytecounts'             => 'Големина на компресираниот блок',
'exif-jpeginterchangeformat'       => 'Оддалеченост на JPEG прегледот од почетокот на фајлот',
'exif-jpeginterchangeformatlength' => 'Количина на бајтови во JPEG прегледот',
'exif-transferfunction'            => 'Функција на преобликување на колор просторот',
'exif-whitepoint'                  => 'Хромацитет на белата точка',
'exif-primarychromaticities'       => 'Хроматичност на примарните бои',
'exif-ycbcrcoefficients'           => 'Матрични коефициенти на трансформација на колор просторот',
'exif-referenceblackwhite'         => 'Место на белата и црната точка',
'exif-datetime'                    => 'Датум и време на промена на податотека',
'exif-imagedescription'            => 'Име на сликата',
'exif-make'                        => 'Производител на камерата',
'exif-model'                       => 'Модел на камерата',
'exif-software'                    => 'Користен софтвер',
'exif-artist'                      => 'Автор',
'exif-copyright'                   => 'Носител на авторските права',
'exif-exifversion'                 => 'Exif верзија',
'exif-flashpixversion'             => 'Поддржана верзија на Flashpix',
'exif-colorspace'                  => 'Простор на бојата',
'exif-componentsconfiguration'     => 'Значење на секоја од компонентите.',
'exif-compressedbitsperpixel'      => 'Мод на компресирање на сликата',
'exif-pixelydimension'             => 'Полна висина на сликата',
'exif-pixelxdimension'             => 'Полна ширина на сликата',
'exif-makernote'                   => 'Забелешки на производителот',
'exif-usercomment'                 => 'Коментар на корисникот',
'exif-relatedsoundfile'            => 'Поврзана аудио податотека',
'exif-datetimeoriginal'            => 'Датум и време на сликање',
'exif-datetimedigitized'           => 'Датум и време на дигитализација',
'exif-subsectime'                  => 'Дел од секундата во кој е сликано',
'exif-subsectimeoriginal'          => 'Дел од секундата во кој е фотографирано',
'exif-subsectimedigitized'         => 'Дел од секундата во кој е дигитализирано',
'exif-exposuretime'                => 'Експозиција',
'exif-exposuretime-format'         => '$1 sec ($2)',
'exif-fnumber'                     => 'F број на отворот на блендата',
'exif-fnumber-format'              => 'f/$1',
'exif-exposureprogram'             => 'Програма за експозиција',
'exif-spectralsensitivity'         => 'Спектрална осетливост',
'exif-isospeedratings'             => 'ИСО вредност',
'exif-oecf'                        => 'Оптоелектронски фактор на конверзија',
'exif-shutterspeedvalue'           => 'Брзина на затворачот',
'exif-aperturevalue'               => 'Отвор на блендата',
'exif-brightnessvalue'             => 'Светлост',
'exif-exposurebiasvalue'           => 'Компензација на експозицијата',
'exif-maxaperturevalue'            => 'Минимален број на отворот на блендата',
'exif-subjectdistance'             => 'Оддалеченост до објектот',
'exif-meteringmode'                => 'Режим на мерачот на времето',
'exif-lightsource'                 => 'Светлосен извор',
'exif-flash'                       => 'Блиц',
'exif-focallength'                 => 'Фокусна далечина на леќата',
'exif-subjectarea'                 => 'Положба и површина на објектот',
'exif-flashenergy'                 => 'Енергија на блицот',
'exif-spatialfrequencyresponse'    => 'Просторна фреквенциска карактеристика',
'exif-focalplanexresolution'       => 'Хоризонтална резолуција на фокусната рамнина',
'exif-focalplaneyresolution'       => 'Вертикална резолуција на фокусната рамнина',
'exif-focalplaneresolutionunit'    => 'Единица за резолуција на фокусната рамнина',
'exif-subjectlocation'             => 'Положба на субјектот',
'exif-exposureindex'               => 'Индекс на експозицијата',
'exif-sensingmethod'               => 'Тип на сензори',
'exif-filesource'                  => 'Изворна податотека',
'exif-scenetype'                   => 'Тип на сцена',
'exif-cfapattern'                  => 'CFA шаблон',
'exif-customrendered'              => 'Дополнитела обработка на сликата',
'exif-exposuremode'                => 'Режим за избор на експозицијата',
'exif-whitebalance'                => 'Баланс на белата боја',
'exif-digitalzoomratio'            => 'Однос на дигиталниот зум',
'exif-focallengthin35mmfilm'       => 'Еквивалент на фокусната далечина за 35 mm филм',
'exif-scenecapturetype'            => 'Тип на сцена на снимката',
'exif-gaincontrol'                 => 'Контрола на осветленоста',
'exif-contrast'                    => 'Контраст',
'exif-saturation'                  => 'Сатурација',
'exif-sharpness'                   => 'Острина',
'exif-devicesettingdescription'    => 'Опис на подесувањата на апаратот',
'exif-subjectdistancerange'        => 'Распон на оддалеченоста на субјектот',
'exif-imageuniqueid'               => 'Единствен идентификатор на сликата',
'exif-gpsversionid'                => 'Верзија на блокот на GPS информации',
'exif-gpslatituderef'              => 'Северна или Јужна ширина',
'exif-gpslatitude'                 => 'Ширина',
'exif-gpslongituderef'             => 'Источна или западна должина',
'exif-gpslongitude'                => 'Должина',
'exif-gpsaltituderef'              => 'Висина под или над морето',
'exif-gpsaltitude'                 => 'Висина',
'exif-gpstimestamp'                => 'GPS време (атомски часовник)',
'exif-gpssatellites'               => 'Употребени сателити',
'exif-gpsstatus'                   => 'Статус на приемникот',
'exif-gpsmeasuremode'              => 'Режим на мерењето',
'exif-gpsdop'                      => 'Прецизност на мерењето',
'exif-gpsspeedref'                 => 'Единица за брзината',
'exif-gpsspeed'                    => 'Брзина на GPS приемникот',
'exif-gpstrackref'                 => 'Тип на азимутот на приемникот (вистински или магнетен)',
'exif-gpstrack'                    => 'Азимут на приемникот',
'exif-gpsimgdirectionref'          => 'Тип на азимутот на сликата (вистински или магнетен)',
'exif-gpsimgdirection'             => 'Азимут на сликата',
'exif-gpsmapdatum'                 => 'Користен геодетски координатен систем',
'exif-gpsdestlatituderef'          => 'Индекс за географската ширина на објектот',
'exif-gpsdestlatitude'             => 'Географска ширина на објектот',
'exif-gpsdestlongituderef'         => 'Индекс за географската должина на објектот',
'exif-gpsdestlongitude'            => 'Географска должина на објектот',
'exif-gpsdestbearingref'           => 'Индекс на азимутот на објектот',
'exif-gpsdestbearing'              => 'Азимут на објектот',
'exif-gpsdestdistanceref'          => 'Мерна единица за оддалеченоста на објектот',
'exif-gpsdestdistance'             => 'Оддалеченост на објектот',
'exif-gpsprocessingmethod'         => 'Име на методот за обработка на GPS податоците',
'exif-gpsareainformation'          => 'Има на GPS зоната',
'exif-gpsdatestamp'                => 'GPS датум',
'exif-gpsdifferential'             => 'GPS диференцијална корекција',

# EXIF attributes
'exif-compression-1' => 'Некомпресиран',

'exif-unknowndate' => 'Непознат датум',

'exif-orientation-1' => 'Нормално', # 0th row: top; 0th column: left
'exif-orientation-2' => 'Пресликано по хоризонтална оска', # 0th row: top; 0th column: right
'exif-orientation-3' => 'Ротирано за 180°', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Пресликано по вертикална оска', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Ротирано за 90° спротивно од правецот на стрелките на часовникот и обратно по вертикална оска', # 0th row: left; 0th column: top
'exif-orientation-6' => 'Ротирано за 90° во правец на стрелките на часовникот', # 0th row: right; 0th column: top
'exif-orientation-7' => 'Ротирано 90° во правец на стрелките на часовникот и обратно по вертикална оска', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Ротирано за 90° спротивно од правецот на стрелките на часовникот', # 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'делумен формат',
'exif-planarconfiguration-2' => 'планарен формат',

'exif-xyresolution-i' => '$1 dpi',
'exif-xyresolution-c' => '$1 dpc',

'exif-colorspace-1'      => 'sRGB',
'exif-colorspace-ffff.h' => 'FFFF.H',

'exif-componentsconfiguration-0' => 'не постои',
'exif-componentsconfiguration-1' => 'Y',
'exif-componentsconfiguration-2' => 'Cb',
'exif-componentsconfiguration-3' => 'Cr',
'exif-componentsconfiguration-4' => 'R',
'exif-componentsconfiguration-5' => 'G',
'exif-componentsconfiguration-6' => 'B',

'exif-exposureprogram-0' => 'Недефинирано',
'exif-exposureprogram-1' => 'Рачно',
'exif-exposureprogram-2' => 'Нормална програма',
'exif-exposureprogram-3' => 'Приоритет на отворот на блендата',
'exif-exposureprogram-4' => 'Приоритет на затворачот',
'exif-exposureprogram-5' => 'Уметничка програма (на база на нужната длабочина на полето)',
'exif-exposureprogram-6' => 'Спортска програма (на база на што побрз затворач)',
'exif-exposureprogram-7' => 'Портретен начин (за фотографии одблизу со заматена позадина)',
'exif-exposureprogram-8' => 'Пејсажен начин (за фотографии на пејсажи со остра позадина)',

'exif-subjectdistance-value' => '$1 метри',

'exif-meteringmode-0'   => 'Непознато',
'exif-meteringmode-1'   => 'Просек',
'exif-meteringmode-2'   => 'Просек со тежиште на средина',
'exif-meteringmode-3'   => 'Точка',
'exif-meteringmode-4'   => 'Повеќе точки',
'exif-meteringmode-5'   => 'Матричен',
'exif-meteringmode-6'   => 'Делумен',
'exif-meteringmode-255' => 'Друго',

'exif-lightsource-0'   => 'Непознато',
'exif-lightsource-1'   => 'Дневна светлина',
'exif-lightsource-2'   => 'Флуоресцентно',
'exif-lightsource-3'   => 'Волфрам (светло)',
'exif-lightsource-4'   => 'Блиц',
'exif-lightsource-9'   => 'Убаво време',
'exif-lightsource-10'  => 'Облачно време',
'exif-lightsource-11'  => 'Сенка',
'exif-lightsource-12'  => 'Флуоресцентна светлина (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Флуоресцентна светлина (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Флуоресцентна светлина (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Бела флуоресцентност (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Стандардна светлина A',
'exif-lightsource-18'  => 'Стандардна светлина B',
'exif-lightsource-19'  => 'Стандардна светлина C',
'exif-lightsource-24'  => 'ISO студиски волфрам',
'exif-lightsource-255' => 'Друг светлосен извор',

# Flash modes
'exif-flash-fired-0'    => 'Блицот не засветил',
'exif-flash-fired-1'    => 'Блицот засветил',
'exif-flash-return-0'   => 'без употреба на функција на стробоскоп',
'exif-flash-return-2'   => 'не е детектирано повратно светло од стробоскопот',
'exif-flash-return-3'   => 'детектирано повратно светло од стробоскоп',
'exif-flash-mode-1'     => 'активиран е блиц',
'exif-flash-mode-2'     => 'исклучен блиц',
'exif-flash-mode-3'     => 'автоматски начин',
'exif-flash-function-1' => 'Нема функција за блиц',
'exif-flash-redeye-1'   => 'режим на редукција на црвено-око',

'exif-focalplaneresolutionunit-2' => 'инчи',

'exif-sensingmethod-1' => 'Недефинирано',
'exif-sensingmethod-2' => 'Еднокристален матричен сензор',
'exif-sensingmethod-3' => 'Двокристален матричен сензор',
'exif-sensingmethod-4' => 'Трокристален матричен сензор',
'exif-sensingmethod-5' => 'Секвенцијален матричен сензор',
'exif-sensingmethod-7' => 'Тробоен линеарен сензор',
'exif-sensingmethod-8' => 'Колор секвенцијален линеарен сензор',

'exif-filesource-3' => 'Дигитален фотоапарат',

'exif-scenetype-1' => 'Директно фотографирана слика',

'exif-customrendered-0' => 'Нормален процес',
'exif-customrendered-1' => 'Нестандарден процес',

'exif-exposuremode-0' => 'Автоматска експозиција',
'exif-exposuremode-1' => 'Мануелна експозиција',
'exif-exposuremode-2' => 'Автоматски со зададен распон',

'exif-whitebalance-0' => 'Автоматски баланс на бело',
'exif-whitebalance-1' => 'Мануелен баланс на бело',

'exif-scenecapturetype-0' => 'Стандардно',
'exif-scenecapturetype-1' => 'Пејсаж',
'exif-scenecapturetype-2' => 'Портрет',
'exif-scenecapturetype-3' => 'Ноќна сцена',

'exif-gaincontrol-0' => 'Нема',
'exif-gaincontrol-1' => 'Мало зголемување',
'exif-gaincontrol-2' => 'Големо зголемување',
'exif-gaincontrol-3' => 'Мало смалување',
'exif-gaincontrol-4' => 'Големо смалување',

'exif-contrast-0' => 'Нормално',
'exif-contrast-1' => 'Меко',
'exif-contrast-2' => 'Грубо',

'exif-saturation-0' => 'Нормално',
'exif-saturation-1' => 'Ниска сатурација',
'exif-saturation-2' => 'Висока сатурација',

'exif-sharpness-0' => 'Нормално',
'exif-sharpness-1' => 'Меко',
'exif-sharpness-2' => 'Тврдо',

'exif-subjectdistancerange-0' => 'Непознато',
'exif-subjectdistancerange-1' => 'Крупен кадар (макро)',
'exif-subjectdistancerange-2' => 'Близок кадар',
'exif-subjectdistancerange-3' => 'Далечен кадар',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Север',
'exif-gpslatitude-s' => 'Југ',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Исток',
'exif-gpslongitude-w' => 'Запад',

'exif-gpsstatus-a' => 'Мерење во тек',
'exif-gpsstatus-v' => 'Подготвен за пренос',

'exif-gpsmeasuremode-2' => 'Дводимензионално мерење',
'exif-gpsmeasuremode-3' => 'Тродимензионално мерење',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Километри на час',
'exif-gpsspeed-m' => 'Милји на час',
'exif-gpsspeed-n' => 'Јазли',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Вистински правец',
'exif-gpsdirection-m' => 'Магнетен правец',

# External editor support
'edit-externally'      => 'Уреди ја оваа податотека со надворешна апликација',
'edit-externally-help' => '(Види [http://www.mediawiki.org/wiki/Manual:External_editors повеќе инстрикции] за нагодување).',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'сите',
'imagelistall'     => 'сите',
'watchlistall2'    => 'сите',
'namespacesall'    => 'сите',
'monthsall'        => 'сите',

# E-mail address confirmation
'confirmemail'             => 'Потврда на е-поштенска адреса',
'confirmemail_noemail'     => 'Немате наведено валидна е-поштенска адреса во вашите [[Special:Preferences|нагодувања]].',
'confirmemail_text'        => '{{SITENAME}} бара да ја потврдите вашата е-поштенска адреса пред да ги користите можностите за е-пошта.
Притиснете на копчето подолу за да пратите потврдувачка порака на вашата адреса.
Во оваа порака е вклучена врска којашто содржи код;
отворете ја врската во вашиот прелистувач за да потврдите дека вашата е-поштенска адреса е важечка.',
'confirmemail_pending'     => 'Код за потврда веќе е испратен по е-пошта до вас;
ако скоро сте креирале корисничка сметка, потребно е да почекате неколку минути за да пристигне пораката пред да побарате нов код.',
'confirmemail_send'        => 'Прати код за потврда',
'confirmemail_sent'        => 'Пораката за потврда е пратена.',
'confirmemail_oncreate'    => 'Код за потврда е пратен на вашата е-поштенска адреса.
Овој код не е потребен за најавување, но ќе треба да го внесете пред да
ги вклучите е-поштенските можности во викито.',
'confirmemail_sendfailed'  => '{{SITENAME}} не може да ви прати порака за потврда.
Проверете дали е-поштенската адреса е правилно запишана.

Вратена е следнава грешка: $1',
'confirmemail_invalid'     => 'Неточен код за потврда. Кодот можеби е истечен.',
'confirmemail_needlogin'   => 'Морате да $1 за да ја потврдите вашата е-поштенска адреса.',
'confirmemail_success'     => 'Вашата е-поштенска адреса сега е потврдена. Можете да се најавите и да уживате во викито.',
'confirmemail_loggedin'    => 'Вашата е-поштенска адреса сега е потврдена.',
'confirmemail_error'       => 'Нешто тргна наопаку при снимањето на вашата потврда.',
'confirmemail_subject'     => '{{SITENAME}} потврда за е-поштенска адреса',
'confirmemail_body'        => 'Некој, веројатно вие, од IP адресата $1,
ја регистрирал сметката „$2“ со оваа е-поштенска адреса на {{SITENAME}}.

За да потврдите дека оваа сметка навистина ви припаѓа вам и да ја активирате
можноста за е-пошта на {{SITENAME}}, отворете ја оваа врска во вашиот пребарувач:

$3

Ако *не* сте ја регистрирале сметката вие, следете ја оваа врска
за да го откажете потврдувањето на е-поштенската адреса:

$5

Овој код за потврда ќе истече на $4.',
'confirmemail_invalidated' => 'Потврдата на е-поштенската адреса е откажана',
'invalidateemail'          => 'Откажување на потврда на е-пошта',

# Scary transclusion
'scarytranscludedisabled' => '[Интервики трансклудирање е оневозможено]',
'scarytranscludefailed'   => '[Неуспешно превземање на шаблонот за $1]',
'scarytranscludetoolong'  => '[Премногу долго URL]',

# Trackbacks
'trackbackbox'      => 'Враќања за оваа страница:<br />
$1',
'trackbackremove'   => '([$1 Бриши])',
'trackbacklink'     => 'Враќање',
'trackbackdeleteok' => 'Враќањето беше успешно избришано.',

# Delete conflict
'deletedwhileediting' => "'''Предупредување''': Оваа страница беше избришана откако почнавте со нејзино уредување!",
'confirmrecreate'     => "Корисникот [[User:$1|$1]] ([[User talk:$1|разговор]]) ја избриша оваа страница откако вие почнавте со уредување заради:
: ''$2''
Потврдете дека навистина сакате повторно да ја креирате оваа страница.",
'recreate'            => 'Повторно создади',

'unit-pixel' => 'px',

# action=purge
'confirm_purge_button' => 'OK',
'confirm-purge-top'    => 'Исчисти го кешот на оваа страница?',
'confirm-purge-bottom' => 'Со оваа операција се чисти серверскиот кеш и се прикажува најновата верзија.',

# Multipage image navigation
'imgmultipageprev' => '&larr; претходна страница',
'imgmultipagenext' => 'следна страница &rarr;',
'imgmultigo'       => 'Оди!',
'imgmultigoto'     => 'Оди на страница $1',

# Table pager
'ascending_abbrev'         => 'раст',
'descending_abbrev'        => 'опаѓ',
'table_pager_next'         => 'Следна страница',
'table_pager_prev'         => 'Претходна страница',
'table_pager_first'        => 'Прва страница',
'table_pager_last'         => 'Последна страница',
'table_pager_limit'        => 'Прикажи $1 записи по страница',
'table_pager_limit_submit' => 'Оди',
'table_pager_empty'        => 'Нема резултати',

# Auto-summaries
'autosumm-blank'   => 'Целосно избришана страница',
'autosumm-replace' => "Ја заменувам страницата со '$1'",
'autoredircomment' => 'Пренасочување кон [[$1]]',
'autosumm-new'     => 'Креирана страница со: $1',

# Size units
'size-bytes'     => '$1 B',
'size-kilobytes' => '$1 KB',
'size-megabytes' => '$1 MB',
'size-gigabytes' => '$1 GB',

# Live preview
'livepreview-loading' => 'Се вчитува…',
'livepreview-ready'   => 'Се вчитува… готово!',
'livepreview-failed'  => 'Неуспешно прегледување!
Пробајте со нормален преглед.',
'livepreview-error'   => 'Неуспешно поврзување: $1 "$2"
Пробај со нормален преглед.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Промените во {{PLURAL:$1|последната секунда|последните $1 секунди}} можеби нема да бидат прикажани во листата.',
'lag-warn-high'   => 'Поради преоптовареност на податочниот сервер, промените понови од $1 {{PLURAL:$1|секунда|секунди}}
можеби нема да бидат прикажани во листата.',

# Watchlist editor
'watchlistedit-numitems'       => 'Вашата листа на набљудувања содржи {{PLURAL:$1|еден наслов|$1 наслови}}, без страниците за разговор.',
'watchlistedit-noitems'        => 'Вашата листа на набљудувања не содржи ниту еден наслов.',
'watchlistedit-normal-title'   => 'Уредување на листа на набљудувања',
'watchlistedit-normal-legend'  => 'Бришење на наслови од листа на набљудување',
'watchlistedit-normal-explain' => "Насловите во вашата листа на набљудувања се прикажани подолу.
За бришење на наслов, маркирајте го квадратчето лево од насловот, и кликнете на копчето '''Бриши Налов'''.
Исто така можете да ја ажурирате листата со [[Special:Watchlist/raw|напредно уредување на листата]].",
'watchlistedit-normal-submit'  => 'Бриши Наслови',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 наслов беше|$1 наслови беа}} избришани од вашата листа на набљудувања:',
'watchlistedit-raw-title'      => 'Напредно уредување на листата на набљудувања',
'watchlistedit-raw-legend'     => 'Уредување на листа на набљудувања',
'watchlistedit-raw-explain'    => 'Насловите во вашата листа на набљудувања се прикажани подолу, може да се уредува со додавање или бришење на наслови од листата; еден наслов во линија. Кога ќе завршите, кликнете на Ажурирај. 
Исто така за ова може да [[Special:Watchlist/edit|користите стандарден уредувач на текст]].',
'watchlistedit-raw-titles'     => 'Наслови:',
'watchlistedit-raw-submit'     => 'Ажурирај',
'watchlistedit-raw-done'       => 'Вашата листа на набљудувања ќе биде ажурирана.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 наслов беше|$1 наслови беа}} додадени:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 наслов беше избришан|$1 наслови беа избришани}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Погледни релевантни промени',
'watchlisttools-edit' => 'Погледни и уреди листа на набљудувања',
'watchlisttools-raw'  => 'Напредно уредување на листа на набљудувања',

# Iranian month names
'iranian-calendar-m1' => 'Farvardin',

# Core parser functions
'unknown_extension_tag' => 'Непознат приврзок на проширување "$1"',
'duplicate-defaultsort' => 'Предупредување: Основниот клуч за подредување "$2" го поништува претходниот основен клуч за подредување "$1".',

# Special:Version
'version'                          => 'Верзија', # Not used as normal message but as header for the special page itself
'version-extensions'               => 'Инсталирани проширувања',
'version-specialpages'             => 'Специјални страници',
'version-parserhooks'              => 'Парсер куки',
'version-variables'                => 'Променливи',
'version-other'                    => 'Друго',
'version-mediahandlers'            => 'Медија ракувачи',
'version-hooks'                    => 'Куки',
'version-extension-functions'      => 'Функции на проширувања',
'version-parser-extensiontags'     => 'Приврзоци на парсер проширување',
'version-parser-function-hooks'    => 'Куки на парсер функција',
'version-skin-extension-functions' => 'Функции на проширување на изглед',
'version-hook-name'                => 'Име на кука',
'version-hook-subscribedby'        => 'Претплатено од',
'version-version'                  => 'Верзија',
'version-license'                  => 'Лиценца',
'version-software'                 => 'Инсталиран софтвер',
'version-software-product'         => 'Производ',
'version-software-version'         => 'Верзија',

# Special:FilePath
'filepath'         => 'Патека до податотека',
'filepath-page'    => 'Податотека:',
'filepath-submit'  => 'Пат',
'filepath-summary' => 'Оваа специјална страница го враќа целосниот пат на податотеката.
Сликите се прикажани во оригинална големина, другите типови на податотеки се отвораат со соодветно асоцираните програми, директно.

Внесете го името на податотеката без префиксот "{{ns:file}}:".',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Барање на дупликат податотеки',
'fileduplicatesearch-summary'  => 'Пребарување на дуплирани податотеки врз база на нивната hash вредност.

Внесете име на податотека без префиксот "{{ns:file}}:".',
'fileduplicatesearch-legend'   => 'Барање на дупликат',
'fileduplicatesearch-filename' => 'Име на податотека:',
'fileduplicatesearch-submit'   => 'Барај',
'fileduplicatesearch-info'     => '$1 × $2 пиксели<br />Големина на податотека: $3<br />MIME тип: $4',
'fileduplicatesearch-result-1' => 'Податотеката "$1" нема идентичен дупликат.',
'fileduplicatesearch-result-n' => 'Податотеката "$1" има {{PLURAL:$2|еден идентичен дупликат|$2 идентични дупликати}}.',

# Special:SpecialPages
'specialpages'                   => 'Специјални страници',
'specialpages-note'              => '----
* Нормални специјални страници.
* <span class="mw-specialpagerestricted">Специјални страници со ограничен пристап.</span>',
'specialpages-group-maintenance' => 'Извештаи за одржување на википедија',
'specialpages-group-other'       => 'Други специјални страници',
'specialpages-group-login'       => 'Најавување',
'specialpages-group-changes'     => 'Скорешни промени и дневници',
'specialpages-group-media'       => 'Извештаи за мултимедијални содржини и подигнувања',
'specialpages-group-users'       => 'Корнисници и кориснички права',
'specialpages-group-highuse'     => 'Најкористени страници',
'specialpages-group-pages'       => 'Листа на страници',
'specialpages-group-pagetools'   => 'Алатки за страници',
'specialpages-group-wiki'        => 'Вики податоци и алатки',
'specialpages-group-redirects'   => 'Пренасочување на специјални страници',
'specialpages-group-spam'        => 'Алатки за спем',

# Special:BlankPage
'blankpage'              => 'Празна страница',
'intentionallyblankpage' => 'Оваа страница намерно е оставена празна',

# External image whitelist
'external_image_whitelist' => ' #Остави го овој ред точно каков што е<pre>
#Put regular expression fragments (just the part that goes between the //) below
#These will be matched with the URLs of external (hotlinked) images
#Those that match will be displayed as images, otherwise only a link to the image will be shown
#Lines beginning with # are treated as comments

#Put all regex fragments above this line. Leave this line exactly as it is</pre>',

# Special:Tags
'tag-filter'              => '[[Special:Tags|Филтер за приврзок]]:',
'tag-filter-submit'       => 'Филтер',
'tags-title'              => 'Приврзоци',
'tags-tag'                => 'Интерно име на приврзок',
'tags-description-header' => 'Целосен опис на значењето',
'tags-hitcount-header'    => 'Означени промени',
'tags-edit'               => 'уреди',
'tags-hitcount'           => '$1 {{PLURAL:$1|промена|промени}}',

# Database error messages
'dberr-header'    => 'Ова вики не функционира како што треба',
'dberr-usegoogle' => 'Во меѓувреме можете да се обидете да пребарувате со Google.',

);

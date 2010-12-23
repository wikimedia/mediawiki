<?php
/** Macedonian (Македонски)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Bjankuloski06
 * @author Brainmachine
 * @author Brest
 * @author Brest2008
 * @author FlavrSavr
 * @author Glupav
 * @author INkubusse
 * @author Misos
 * @author Spacebirdy
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
	'CreateAccount'             => array( 'СоздајКирисничкаСметка' ),
	'Preferences'               => array( 'Нагодувања' ),
	'Watchlist'                 => array( 'СписокНаНабљудувања' ),
	'Recentchanges'             => array( 'СкорешниПромени' ),
	'Upload'                    => array( 'Подигање', 'Подигања' ),
	'Listfiles'                 => array( 'СписокНаСлики', 'СписокНаПодатотеки' ),
	'Newimages'                 => array( 'НовиСлики', 'НовиПодатотеки' ),
	'Listusers'                 => array( 'СписокНаКорисници', 'СписокКорисници' ),
	'Listgrouprights'           => array( 'СписокНаГрупниПрава' ),
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
	'Ipblocklist'               => array( 'СписокНаБлокираниIP' ),
	'Specialpages'              => array( 'СпецијалниСтраници' ),
	'Contributions'             => array( 'Придонеси' ),
	'Emailuser'                 => array( 'Пиши_е-пошта_на_корисникот' ),
	'Confirmemail'              => array( 'Потврди_е-пошта' ),
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
	'Listredirects'             => array( 'СписокНаПренасочувања' ),
	'Revisiondelete'            => array( 'БришењеРевизија' ),
	'Unusedtemplates'           => array( 'НеискористениШаблони' ),
	'Randomredirect'            => array( 'СлучајноПренасочување' ),
	'Mypage'                    => array( 'МојаСтраница' ),
	'Mytalk'                    => array( 'МојРазговор', 'МоиРазговори' ),
	'Mycontributions'           => array( 'МоиПридонеси' ),
	'Listadmins'                => array( 'СписокНаАдминистратори' ),
	'Listbots'                  => array( 'СписокНаБотови' ),
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
	'Tags'                      => array( 'Oзнаки', 'Приврзоци' ),
	'Activeusers'               => array( 'АктивниКорисници' ),
);

$magicWords = array(
	'redirect'              => array( '0', '#пренасочување', '#види', '#Пренасочување', '#ПРЕНАСОЧУВАЊЕ', '#REDIRECT' ),
	'notoc'                 => array( '0', '__БЕЗСОДРЖИНА__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__БЕЗГАЛЕРИЈА__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__СОСОДРЖИНА__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__СОДРЖИНА__', '__TOC__' ),
	'noeditsection'         => array( '0', '__БЕЗ_УРЕДУВАЊЕ_НА_СЕКЦИИ__', '__NOEDITSECTION__' ),
	'noheader'              => array( '0', '__БЕЗНАСЛОВ__', '__NOHEADER__' ),
	'currentmonth'          => array( '1', 'СЕГАШЕНМЕСЕЦ', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'      => array( '1', 'СЕГАШЕНМЕСЕЦИМЕ', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'СЕГАШЕНМЕСЕЦИМЕРОД', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'    => array( '1', 'СЕГАШЕНМЕСЕЦСКР', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'СЕГАШЕНДЕН', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'СЕГАШЕНДЕН2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'СЕГАШЕНДЕНИМЕ', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'СЕГАШНАГОДИНА', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'СЕГАШНОВРЕМЕ', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'СЕГАШЕНЧАС', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'МЕСЕЦ_ЛОКАЛНО', 'LOCALMONTH', 'LOCALMONTH2' ),
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
$separatorTransformTable = array( ',' => '.', '.' => ',' );

$messages = array(
# User preference toggles
'tog-underline'               => 'Потцртување на врски:',
'tog-highlightbroken'         => 'Формат на неправилни врски <a href="" class="new">на овој начин</a> (алтернативно: вака<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Двостранично порамнување на параграфите',
'tog-hideminor'               => 'Сокриј ситни уредувања во скорешните промени',
'tog-hidepatrolled'           => 'Сокриј патролирани уредувања во скорешните промени',
'tog-newpageshidepatrolled'   => 'Сокриј патролирани страници од списокот на нови страници',
'tog-extendwatchlist'         => 'Прошири го список на набљудувања за приказ на сите промени, не само скорешните',
'tog-usenewrc'                => 'Користи подобрени скорешни промени (бара JavaScript)',
'tog-numberheadings'          => 'Автоматско нумерирање на заглавија',
'tog-showtoolbar'             => 'Прикажи алатник за уредување (JavaScript)',
'tog-editondblclick'          => 'Уредување на страници при двојно кликнување (JavaScript)',
'tog-editsection'             => 'Овозможи уредување на заглавија преку [уреди] врски',
'tog-editsectiononrightclick' => 'Овозможи уредување на заглавија со десен клик на нивниот наслов (JavaScript)',
'tog-showtoc'                 => 'Покажи содржина (за страници со повеќе од 3 заглавија)',
'tog-rememberpassword'        => 'Запомни ме на овој прелистувач (највеќе до $1 {{PLURAL:$1|ден|дена}})',
'tog-watchcreations'          => 'Додавај ги страниците што ги создавам во списокот на набљудувања',
'tog-watchdefault'            => 'Додавај ги страниците што ги уредувам во списокот на набљудувања',
'tog-watchmoves'              => 'Додавај ги страниците што ги преместувам во списокот на набљудувања',
'tog-watchdeletion'           => 'Додавај ги страниците што ги бришам во списокот на набљудувања',
'tog-minordefault'            => 'Обележи ги сите уредувања како ситни по основно',
'tog-previewontop'            => 'Прикажи го прегледот пред кутијата за уредување',
'tog-previewonfirst'          => 'Прикажи преглед на првото уредување',
'tog-nocache'                 => 'Оневозможи кеширање на страниците во прелистувачот',
'tog-enotifwatchlistpages'    => 'Испраќај ми е-пошта при промена на страница од мојот список на набљудувања',
'tog-enotifusertalkpages'     => 'Испраќај ми е-пошта при промена на мојата страница за разговор',
'tog-enotifminoredits'        => 'Испраќај ми е-пошта и за ситни промени во страниците',
'tog-enotifrevealaddr'        => 'Откриј ја мојата е-поштенска адреса во пораките за известување',
'tog-shownumberswatching'     => 'Прикажи го бројот на корисници кои набљудуваат',
'tog-oldsig'                  => 'Преглед на тековниот потпис:',
'tog-fancysig'                => 'Третирај го потписот како викитекст (без автоматска врска)',
'tog-externaleditor'          => 'Користи надворешен уредувач по основно (само за експерти, потребно е посебно нагодување на сметачот)',
'tog-externaldiff'            => 'Користи надворешен програм за разлики по основно (само за стручњаци, потребно е специјално нагодување на сметачот)',
'tog-showjumplinks'           => 'Овозможи врски на пристапност „скокни на“',
'tog-uselivepreview'          => 'Користи преглед во живо (JavaScript) (Експериментално)',
'tog-forceeditsummary'        => 'Извести ме кога нема опис на промените',
'tog-watchlisthideown'        => 'Сокриј мои уредувања од списокот на набљудувања',
'tog-watchlisthidebots'       => 'Сокриј ботовски уредувања од списокот на набљудувања',
'tog-watchlisthideminor'      => 'Сокриј ситни уредувања во списокот на набљудувања',
'tog-watchlisthideliu'        => 'Сокриј уредувања на најавени корисници во списокот на набљудувања',
'tog-watchlisthideanons'      => 'Сокриј уредувања од анонимни корисници во списокот на набљудувања',
'tog-watchlisthidepatrolled'  => 'Сокриј патролирани уредувања од мојот список на набљудувања',
'tog-nolangconversion'        => 'Оневозможи претворање на јазични варијанти',
'tog-ccmeonemails'            => 'Испраќај ми копии од е-пораките што ги праќам до други корисници',
'tog-diffonly'                => 'Не ја покажувај содржината на страницата под разликите',
'tog-showhiddencats'          => 'Прикажи скриени категории',
'tog-noconvertlink'           => 'Оневозможи претворање на наслов на врска',
'tog-norollbackdiff'          => 'Изостави разлика по употребата на враќање',

'underline-always'  => 'Секогаш',
'underline-never'   => 'Никогаш',
'underline-default' => 'Според нагодувањата на прелистувачот',

# Font style option in Special:Preferences
'editfont-style'     => 'Фонт кој се користи во прозорецот за уредување:',
'editfont-default'   => 'Основен прелистувач:',
'editfont-monospace' => 'Сразмерно широк фонт',
'editfont-sansserif' => 'Бессерифен фонт',
'editfont-serif'     => 'Серифен фонт',

# Dates
'sunday'        => 'недела',
'monday'        => 'понеделник',
'tuesday'       => 'вторник',
'wednesday'     => 'среда',
'thursday'      => 'четврток',
'friday'        => 'петок',
'saturday'      => 'сабота',
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
'category_header'                => 'Статии во категоријата „$1“',
'subcategories'                  => 'Поткатегории',
'category-media-header'          => 'Податотеки во категоријата „$1“',
'category-empty'                 => "''Оваа категорија моментално не содржи страници или податотеки.''",
'hidden-categories'              => '{{PLURAL:$1|Скриена категорија|Скриени категории}}',
'hidden-category-category'       => 'Скриени категории',
'category-subcat-count'          => '{{PLURAL:$2|Оваа категорија ја содржи само следнава поткатегорија.|Оваа категорија {{PLURAL:$1|ја содржи следнава поткатегорија|ги содржи следниве $1 поткатегории}}, од вкупно $2.}}',
'category-subcat-count-limited'  => 'Оваа категорија {{PLURAL:$1|ја содржи следнава поткатегорија|ги содржи следниве $1 поткатегории}}.',
'category-article-count'         => '{{PLURAL:$2|Оваа категорија ја содржи само следната страница.|{{PLURAL:$1|Следната страница е|Следните $1 страници се}} во оваа категорија, од вкупно $2.}}',
'category-article-count-limited' => '{{PLURAL:$1|Следната страница е|Следните $1 страници се}} во оваа категорија.',
'category-file-count'            => '{{PLURAL:$2|Оваа категорија ја содржи само следната податотека.|{{PLURAL:$1|Следната податотека е|Следните $1 податотеки се}} во оваа категорија, од вкупно $2.}}',
'category-file-count-limited'    => '{{PLURAL:$1|Следнава податотека е|Следниве $1 податотеки се}} во оваа категорија.',
'listingcontinuesabbrev'         => 'продолжува',
'index-category'                 => 'Индексирани страници',
'noindex-category'               => 'Неиндексирани страници',

'linkprefix'        => '/^(.*?)([a-zA-Z\\x80-\\xff]+)$/sD',
'mainpagetext'      => "'''МедијаВики е успешно инсталиран.'''",
'mainpagedocfooter' => 'Погледнете го [http://meta.wikimedia.org/wiki/Help:Contents Упатството за корисници] за подетални иформации како се користи вики-програмот.

==Од каде да почнете==
* [http://meta.wikimedia.org/wiki/Manual:Configuration_settings Список на нагодувања]
* [http://meta.wikimedia.org/wiki/Manual:FAQ ЧПП (често поставувани прашања) за МедијаВики].
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Поштенски список на МедијаВики за нови верзии]',

'about'         => 'Информации за',
'article'       => 'Статија',
'newwindow'     => '(се отвора во нов прозорец)',
'cancel'        => 'Откажи',
'moredotdotdot' => 'Повеќе...',
'mypage'        => 'Моја страница',
'mytalk'        => 'мои разговори',
'anontalk'      => 'Разговор за оваа IP-адреса',
'navigation'    => 'Навигација',
'and'           => '&#32;и',

# Cologne Blue skin
'qbfind'         => 'Најди',
'qbbrowse'       => 'Прелистај',
'qbedit'         => 'Уреди',
'qbpageoptions'  => 'Оваа страница',
'qbpageinfo'     => 'Содржина на страница',
'qbmyoptions'    => 'Мои страници',
'qbspecialpages' => 'Специјални страници',
'faq'            => 'ЧПП',
'faqpage'        => 'Project:ЧПП',

# Vector skin
'vector-action-addsection'       => 'Додај тема',
'vector-action-delete'           => 'Бриши',
'vector-action-move'             => 'Премести',
'vector-action-protect'          => 'Заштити',
'vector-action-undelete'         => 'Врати',
'vector-action-unprotect'        => 'Отстрани заштита',
'vector-simplesearch-preference' => 'Овозможи збогатени сугестии при пребарување (само за рувото „Векторско“)',
'vector-view-create'             => 'Создај',
'vector-view-edit'               => 'Уреди',
'vector-view-history'            => 'Види историја',
'vector-view-view'               => 'Читај',
'vector-view-viewsource'         => 'Види код',
'actions'                        => 'Дејства',
'namespaces'                     => 'Именски простори',
'variants'                       => 'Варијанти',

'errorpagetitle'    => 'Грешка',
'returnto'          => 'Назад на $1.',
'tagline'           => 'Од {{SITENAME}}',
'help'              => 'Помош',
'search'            => 'Пребарај',
'searchbutton'      => 'Пребарај',
'go'                => 'Оди',
'searcharticle'     => 'Оди',
'history'           => 'Историја на страница',
'history_short'     => 'Историја',
'updatedmarker'     => 'ажурирано од мојата последна посета',
'info_short'        => 'Информации',
'printableversion'  => 'Верзија за печатење',
'permalink'         => 'Постојана врска',
'print'             => 'Печати',
'edit'              => 'Уреди',
'create'            => 'Создај',
'editthispage'      => 'Уреди ја оваа страница',
'create-this-page'  => 'Создај ја оваа страница',
'delete'            => 'Избриши',
'deletethispage'    => 'Избриши ја оваа страница',
'undelete_short'    => 'Врати {{PLURAL:$1|едно уредување|$1 уредувања}}',
'protect'           => 'Заштити',
'protect_change'    => 'промена',
'protectthispage'   => 'Заштити ја оваа страница',
'unprotect'         => 'Отстрани заштита',
'unprotectthispage' => 'Отстрани заштита на страница',
'newpage'           => 'Нова страница',
'talkpage'          => 'Разговор',
'talkpagelinktext'  => 'Разговор',
'specialpage'       => 'Специјална страница',
'personaltools'     => 'Лични алатки',
'postcomment'       => 'Ново заглавие',
'articlepage'       => 'Преглед на содржината',
'talk'              => 'Разговор',
'views'             => 'Погледи',
'toolbox'           => 'Алатник',
'userpage'          => 'Преглед на корисничката страница',
'projectpage'       => 'Преглед на проектната страница',
'imagepage'         => 'Преглед на страницата на податотеката',
'mediawikipage'     => 'Преглед на пораката',
'templatepage'      => 'Преглед на шаблонот',
'viewhelppage'      => 'Погледајте ја страницата за помош',
'categorypage'      => 'Погледајте ја страницата за категоријата',
'viewtalkpage'      => 'Видете го разговорот',
'otherlanguages'    => 'Други јазици',
'redirectedfrom'    => '(Пренасочено од $1)',
'redirectpagesub'   => 'Страница за пренасочување',
'lastmodifiedat'    => 'Оваа страница последен пат е изменета на $1 во $2 ч.',
'viewcount'         => 'Оваа страница била посетена {{PLURAL:$1|еднаш|$1 пати}}.',
'protectedpage'     => 'Заштитена страница',
'jumpto'            => 'Скокни на:',
'jumptonavigation'  => 'содржини',
'jumptosearch'      => 'барај',
'view-pool-error'   => 'За жал во моментов опслужувачите се преоптоварени.
Премногу корисници се обидуваат да ја прегледаат оваа страница.
Ве молиме почекајте некое време пред повторно да се обидете да пристапите до оваа страница.

$1',
'pool-timeout'      => 'Истече времето за чекање на заклучувањето',
'pool-queuefull'    => 'Редицата на барања е полна',
'pool-errorunknown' => 'Непозната грешка',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'За {{SITENAME}}',
'aboutpage'            => 'Project:За {{SITENAME}}',
'copyright'            => 'Сите текстови се достапни под условите на $1.',
'copyrightpage'        => '{{ns:project}}:Авторски права',
'currentevents'        => 'Тековни настани',
'currentevents-url'    => 'Project:Тековни настани',
'disclaimers'          => 'Услови на употреба',
'disclaimerpage'       => 'Project:Услови на употреба',
'edithelp'             => 'Помош за уредување',
'edithelppage'         => 'Help:Како се уредуваат страници',
'helppage'             => 'Help:Содржина',
'mainpage'             => 'Главна страница',
'mainpage-description' => 'Главна страница',
'policy-url'           => 'Project:Начела',
'portal'               => 'Портал',
'portal-url'           => 'Project:Портал на заедницата',
'privacy'              => 'Заштита на личните податоци',
'privacypage'          => 'Project:Политика на приватност',

'badaccess'        => 'Немате овластување',
'badaccess-group0' => 'Немате дозвола да го извршите бараното дејство.',
'badaccess-groups' => 'Дејството што го побаравте е ограничено само на корисници во {{PLURAL:$2|групата|една од групите}}: $1.',

'versionrequired'     => 'Верзијата $1 од МедијаВики е задолжителна',
'versionrequiredtext' => 'Мора да имате верзија $1 на МедијаВики за да ја користите оваа страница.
Видете [[Special:Version|страница за верзија]].',

'ok'                      => 'ОК',
'pagetitle'               => '$1 - {{SITENAME}}',
'retrievedfrom'           => 'Преземено од „$1“',
'youhavenewmessages'      => 'Имате $1 ($2).',
'newmessageslink'         => 'нови пораки',
'newmessagesdifflink'     => 'скорешна промена',
'youhavenewmessagesmulti' => 'Имате нови пораки на $1',
'editsection'             => 'уреди',
'editsection-brackets'    => '[$1]',
'editold'                 => 'уреди',
'viewsourceold'           => 'преглед на кодот',
'editlink'                => 'уреди',
'viewsourcelink'          => 'преглед на кодот',
'editsectionhint'         => 'Уреди го пасусот: $1',
'toc'                     => 'Содржина',
'showtoc'                 => 'прикажи',
'hidetoc'                 => 'сокриј',
'thisisdeleted'           => 'Погледни или врати $1?',
'viewdeleted'             => 'Да погледате $1?',
'restorelink'             => '{{PLURAL:$1|едно избришано уредување|$1 избришани уредувања}}',
'feedlinks'               => 'Во вид:',
'feed-invalid'            => 'Погрешен начин на претплата на емитувања',
'feed-unavailable'        => 'RSS/Atom емитувањата не се достапни',
'site-rss-feed'           => '$1 - RSS емитувања',
'site-atom-feed'          => '$1 Atom емитувања',
'page-rss-feed'           => '„$1“ - RSS емитувања',
'page-atom-feed'          => '„$1“ - Atom емитувања',
'feed-atom'               => 'Atom',
'feed-rss'                => 'RSS',
'red-link-title'          => '$1 (страницата не постои)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Страница',
'nstab-user'      => 'Корисник',
'nstab-media'     => 'Мултимедијална податотека',
'nstab-special'   => 'Специјална страница',
'nstab-project'   => 'Проектна страница',
'nstab-image'     => 'Податотека',
'nstab-mediawiki' => 'Порака',
'nstab-template'  => 'Шаблон',
'nstab-help'      => 'Страница за помош',
'nstab-category'  => 'Категорија',

# Main script and global functions
'nosuchaction'      => 'Не постои таа функција',
'nosuchactiontext'  => 'Дејството наведено во URL-низата е грешно.
Можеби има грешка во пишувањето на URL или сте следеле погрешна врска.
Ова може исто така да биде и грешка во програмската опрема на {{SITENAME}}.',
'nosuchspecialpage' => 'Не постои таква специјална страница',
'nospecialpagetext' => '<strong>Побаравте неважечка специјална страница.</strong>

Списокот на важечки специјални страници ќе го најдете на [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Грешка',
'databaseerror'        => 'Грешка во базата',
'dberrortext'          => 'Синтаксна грешка во барањето до базата.
Ова може да значи грешка во програмската опрема.
Последното барање до базата беше:
<blockquote><tt>$1</tt></blockquote>
од функцијата „<tt>$2</tt>“.
Вратена е грешката „<tt>$3: $4</tt>“.',
'dberrortextcl'        => 'Грешка во барањето до базата.
Последното барање до базата беше:
„$1“
од функцијата „$2“.
Вратена е следната грешка „$3: $4“.',
'laggedslavemode'      => 'Предупредување: Страницата може да не ги содржи скорешните ажурирања.',
'readonly'             => 'Базата е заклучена',
'enterlockreason'      => 'Внесете причина за заклучувањето, вклучувајќи и приближно време на отклучување',
'readonlytext'         => 'Базата е моментално заклучена за нови статии и други измени, најверојатно како рутинска проверка, по што ќе се врати во нормална состојба. Администраторот кој ја заклучи го понуди следното објаснување: <p>$1',
'missing-article'      => 'Базата на податоци не го пронајде текстот на страницата кој требаше да го пронајде, именуван „$1“ $2.

Ова најчесто е предизвикано од застарена разл. или врска до историја на страница која била избришана.

Ако не е таков случај, можеби сте наишле грешка во програмската опрема.
Пријавете го ова на некој [[Special:ListUsers/sysop|администратор]], давајќи врска до URL адресата.',
'missingarticle-rev'   => '(измена#: $1)',
'missingarticle-diff'  => '(разлика: $1, $2)',
'readonly_lag'         => 'Базата е автоматски заклучена додека помошните опслужувачи не се усогласат',
'internalerror'        => 'Внатрешна грешка',
'internalerror_info'   => 'Внатрешна грешка: $1',
'fileappenderrorread'  => 'Не можев да го прочитам „$1“ во текот на придодавањето.',
'fileappenderror'      => 'Не можe да се додаде „$1“ на „$2“.',
'filecopyerror'        => 'Не можeв да ја ископирам податотеката „$1“ во „$2“.',
'filerenameerror'      => 'Не можев да ја преименувам податотеката „$1“ во „$2“.',
'filedeleteerror'      => 'Не може да се избрише податотеката „$1“.',
'directorycreateerror' => 'Не можеше да се создаде именикот „$1“.',
'filenotfound'         => 'Не може да се најде податотеката „$1“.',
'fileexistserror'      => 'Не може да се запишува во податотеката „$1“: податотеката постои',
'unexpected'           => 'Неочекувана вредност: „$1“=„$2“.',
'formerror'            => 'Грешка: не можам да го испратам образецот',
'badarticleerror'      => 'Ова дејство не може да се спроведе на оваа страница.',
'cannotdelete'         => 'Страницата или податотеката „$1“ не можеше да се избрише.
Можеби некој друг веќе ја избришал.',
'badtitle'             => 'Лош наслов',
'badtitletext'         => 'Бараниот наслов е грешен, празен или погрешно поврзан меѓујазичен или интер-вики наслов. Може да содржи еден или повеќе знаци што не можат да се користат во наслови.',
'perfcached'           => 'Следните податоци се кеширани и може да не бидат целосно ажурирани:',
'perfcachedts'         => 'Следните податоци се кеширани, и последен пат ажурирани на $1.',
'querypage-no-updates' => 'Ажурирања на оваа страница се оневозможени. Податоците овде нема постојано да се обновуваат.',
'wrong_wfQuery_params' => 'Грешни параметри до wfQuery()<br />
Функција: $1<br />
Барање: $2',
'viewsource'           => 'Преглед на кодот',
'viewsourcefor'        => 'за $1',
'actionthrottled'      => 'Дејството е успорено',
'actionthrottledtext'  => 'Како анти-спам мерка, ограничени сте од вршење на ова дејство премногу пати во краток временски период, а го преминавте ограничувањето.
Ве молиме обидете се повторно за неколку минути.',
'protectedpagetext'    => 'Оваа страница е заклучена за уредувања.',
'viewsourcetext'       => 'Можете да го погледнете и копирате кодот на оваа страница:',
'protectedinterface'   => 'Оваа страница содржи текст од посредникот на програмот и е заклучена поради можна злоупотреба.',
'editinginterface'     => "'''Предупредување:''' Уредувате страница која е дел од корисничкиот посредник на програмската опрема на МедијаВики.
Промените на оваа страница ќе предизвикаат промена на корисничкиот посредник кај останатите корисници.
За превод, имајте го предвид [http://translatewiki.net/wiki/Main_Page?setlang=mk translatewiki.net], проектот за локализација на МедијаВики.",
'sqlhidden'            => '(Барањето до SQL е скриено)',
'cascadeprotected'     => 'Оваа страница е заштитена од уредувања бидејќи е вклучена во {{PLURAL:$1|следнава страница, којашто е заштитена|следниве страници, коишто се заштитени}} со можноста „каскадна заштита“:
$2',
'namespaceprotected'   => "Немате дозвола за уредување страници во именскиот простор '''$1'''.",
'customcssjsprotected' => 'Немате дозвола за уредување на оваа страница, бидејќи содржи лични нагодувања на друг корисник.',
'ns-specialprotected'  => 'Специјални страници не може да се уредуваат.',
'titleprotected'       => "Овој наслов од страна на [[User:$1|$1]] е заштитен и не може да се создаде.
Причината за тоа е: ''$2''.",

# Virus scanner
'virus-badscanner'     => "Лоша поставка: непознат проверувач на вируси: ''$1''",
'virus-scanfailed'     => 'неуспешно скенирање (код $1)',
'virus-unknownscanner' => 'непознат антивирус:',

# Login and logout pages
'logouttext'                 => "'''Сега сте одјавени.'''

Можете да продолжите со користење на {{SITENAME}} анонимно или можете [[Special:UserLogin|повторно да се најавите]] под исто или различно корисничко име.
Да напоменеме дека некои страници може да продолжат да се прикажуваат како да сте најавени, се додека не го исчистите кешот на вашиот прелистувач.",
'welcomecreation'            => '== Добредојдовте, $1! ==
Вашата корисничка сметка е создадена.
Не заборавајте да ги промените вашите [[Special:Preferences|{{SITENAME}} нагодувања]].',
'yourname'                   => 'Корисничко име:',
'yourpassword'               => 'Лозинка:',
'yourpasswordagain'          => 'Повторете ја лозинката:',
'remembermypassword'         => 'Запомни ме на овој сметач (највеќе $1 {{PLURAL:$1|ден|дена}})',
'securelogin-stick-https'    => 'Останете поврзани со HTTPS по одјавата',
'yourdomainname'             => 'Вашиот домен:',
'externaldberror'            => 'Настана грешка при надворешното најавување на базата или немате дозвола да ја ажурирате вашата надворешна сметка.',
'login'                      => 'Најавување',
'nav-login-createaccount'    => 'Најавување / создавање на корисничка сметка',
'loginprompt'                => 'За да се најавите на {{SITENAME}} мора да користите колачиња.',
'userlogin'                  => 'Најавување / создавање на корисничка сметка',
'userloginnocreate'          => 'Најава',
'logout'                     => 'одјавување',
'userlogout'                 => 'одјавување',
'notloggedin'                => 'Не сте најавени',
'nologin'                    => "Немате корисничка сметка? '''$1'''.",
'nologinlink'                => 'Создајте нова корисничка сметка',
'createaccount'              => 'Создај сметка',
'gotaccount'                 => "Веќе имате корисничка сметка? '''$1'''.",
'gotaccountlink'             => 'Најавете се',
'createaccountmail'          => 'по е-пошта',
'createaccountreason'        => 'Причина:',
'badretype'                  => 'Внесените лозинки не се исти.',
'userexists'                 => 'Корисничкото име што го внесовте веќе се користи.
Ве молиме изберете друго име.',
'loginerror'                 => 'Грешка при најавување',
'createaccounterror'         => 'Не може да биде создадена сметка: $1',
'nocookiesnew'               => 'Корисничката сметка е создадена, но не сте најавени.
{{SITENAME}} користи колачиња за најавување на корисници.
Вие имате оневозможено колачиња на вашиот прелистувач.
Овозможете ги, па потоа најавете се со вашето корисничко име и лозинка.',
'nocookieslogin'             => '{{SITENAME}} користи колачиња за најавување на корисници.
Вие го имате оневозможено користењето на колачиња на вашиот прелистувач.
Ве молиме активирајте ги и обидете се повторно.',
'nocookiesfornew'            => 'Корисничката сметка не е создадена бидејќи не можеше да се потврди нејзиниот извор.
За оваа цел ќе ви требаат колачиња. Проверете дали се овозможени, превчитајте ја страницава и обидете се повторно.',
'noname'                     => 'Внесовте погрешно корисничко име.',
'loginsuccesstitle'          => 'Успешно најавување',
'loginsuccess'               => 'Сега сте најавени на {{SITENAME}} како „$1“.',
'nosuchuser'                 => 'Нема корисник со името „$1“.
Корисничките имиња разликуваат мали и големи букви.
Проверете да не сте направиле грешка во пишувањето, или [[Special:UserLogin/signup|создајте нова корисничка сметка]].',
'nosuchusershort'            => 'Нема корисник со името „<nowiki>$1</nowiki>“.
Проверете дали правилно сте напишале.',
'nouserspecified'            => 'Мора да назначите корисничко име.',
'login-userblocked'          => 'Овој корисник е блокиран. Најавувањето не е дозволено.',
'wrongpassword'              => 'Внесовте погрешна лозинка. Обидете се повторно.',
'wrongpasswordempty'         => 'Внесената лозинка е празна. Обидете се повторно.',
'passwordtooshort'           => 'Лозинката мора да има најмалку {{PLURAL:$1|1 знак|$1 знаци}}.',
'password-name-match'        => 'Лозинката мора да се разликува од корисничкото име.',
'password-too-weak'          => 'Наведената лозинка е преслаба и не може да се користи.',
'mailmypassword'             => 'Испрати нова лозинка по е-пошта',
'passwordremindertitle'      => 'Нова привремена лозинка за {{SITENAME}}',
'passwordremindertext'       => 'Некој (најверојатно вие, од IP-адреса $1) побара нова лозинка за {{SITENAME}} ($4).
Создадена е привремена лозинка „$3“ за корисничката сметка „$2“.
Ако ова беше Вашата намера, потребно е сега да се најавите и да изберете нова лозинката.
Привремена лозинка истекува за {{PLURAL:$5|еден ден|$5 дена}}.

Ако некој друг го поднел ова барање или ако сте се сетиле на вашата лозинка и веќе не сакате да ја промените, може слободно да ја занемарите оваа порака и да продолжите да придонесувате користејќи се со старата лозинка.',
'noemail'                    => 'Нема заведено е-поштенска адреса за корисник „$1“.',
'noemailcreate'              => 'Потребно е да наведете важечка е-поштенска адреса',
'passwordsent'               => 'Нова лозинка е испратена на е-поштенската адреса заведена за „$1“.
Ве молиме најавете се повторно откако ќе ја примите пораката.',
'blocked-mailpassword'       => 'Вашата IP-адреса е блокирана за уредување, истовремено е ставена забрана за користење на функцијата за обнова на лозинка за да се спречи можноста за злоупотреба.',
'eauthentsent'               => 'На назначената поштенска адреса е испратена потврдна порака.
Пред да се испрати друга порака на корисничката сметка, ќе морате да ги проследите напатствијата во пораката, за да потврдите дека таа корисничка сметка е навистина ваша.',
'throttled-mailpassword'     => 'Потсетување за лозинката е веќе пратено во {{PLURAL:$1|изминатиов час|изминативе $1 часа}}.
За да се спречи злоупотреба, само едно потсетување може да се праќа на {{PLURAL:$1|секој час|секои $1 часа}}.',
'mailerror'                  => 'Грешка при испраќање на е-поштата: $1',
'acct_creation_throttle_hit' => 'Корисници на ова вики користејќи ја вашата IP-адреса создале {{PLURAL:$1|1 корисничка сметка|$1 кориснички сметки}} во последниве денови, при што е достигнат максималниот број на кориснички сметки предвиден и овозможен за овој период.
Како резултат на ова, посетителите кои ја користат оваа IP-адреса во моментов нема да можат да создаваат нови сметки.',
'emailauthenticated'         => 'Вашата е-поштенска адреса е потврдена на $2 во $3 ч.',
'emailnotauthenticated'      => 'Вашата е-поштенска адреса сè уште не е потврдена.
Нема да биде испратена е-пошта во ниту еден од следниве случаи.',
'noemailprefs'               => 'Наведете е-поштенска адреса за да функционираат следниве својства.',
'emailconfirmlink'           => 'Потврдете ја вашата е-поштенска адреса',
'invalidemailaddress'        => 'Е-поштенската адреса не може да биде прифатена бидејќи има неважечки формат.
Ве молиме, внесете важечки формат или испразнете го тоа поле.',
'accountcreated'             => 'Корисничката сметка е направена',
'accountcreatedtext'         => 'Корисничката сметка за $1 беше направена.',
'createaccount-title'        => 'Создавање на сметка за {{SITENAME}}',
'createaccount-text'         => 'Некој направил сметка со вашата е-поштенска адреса на {{SITENAME}} ($4) со име „$2“ и  лозинка „$3“.
Би требало сега да се пријавите и да ја промените вашата лозинка.

Можете да ја занемарите оваа порака ако сметката била направена по грешка.',
'usernamehasherror'          => 'Корисничкото име не може да содржи тараба',
'login-throttled'            => 'Скоро имате направено повеќе обиди за најавување.
Почекајте малку пред да се обидете повторно.',
'loginlanguagelabel'         => 'Јазик: $1',
'suspicious-userlogout'      => 'Вашето барање за одјава е одбиено бидејќи се чини дека е испратено од расипан прелистувач или кеширачки застапник (proxy).',

# E-mail sending
'php-mail-error-unknown' => 'Непозната грешка во функцијата mail() на PHP',

# JavaScript password checks
'password-strength'            => 'Проценета отпорност на лозинката: $1',
'password-strength-bad'        => 'ЛОША',
'password-strength-mediocre'   => 'преодна',
'password-strength-acceptable' => 'прифатлива',
'password-strength-good'       => 'добра',
'password-retype'              => 'Тука повторете ја лозинката',
'password-retype-mismatch'     => 'Лозинките не се исти',

# Password reset dialog
'resetpass'                 => 'Промена на лозинка',
'resetpass_announce'        => 'Најавени сте со привремена лозинка пратена по е-пошта.
За да го завршите пријавувањето, мора да поставите нова лозинка овде:',
'resetpass_text'            => '<!-- Тука внесете текст -->',
'resetpass_header'          => 'Промена на корисничка лозинка',
'oldpassword'               => 'Стара лозинка:',
'newpassword'               => 'Нова лозинка:',
'retypenew'                 => 'Повторете ја новата лозинка:',
'resetpass_submit'          => 'Поставете лозинка и најавете се',
'resetpass_success'         => 'Вашата лозинка беше успешно променета! Најавувањето е во тек...',
'resetpass_forbidden'       => 'Лозинките не може да се менуваат',
'resetpass-no-info'         => 'Мора да бидете најавени ако сакате да имате директен пристап до оваа страница.',
'resetpass-submit-loggedin' => 'Промени лозинка',
'resetpass-submit-cancel'   => 'Откажи',
'resetpass-wrong-oldpass'   => 'Погрешна привремена или тековна лозинка.
Можеби веќе ја имате успешно променето вашата лизинка или сте побарале нова привремена лозинка.',
'resetpass-temp-password'   => 'Привремена лозинка:',

# Edit page toolbar
'bold_sample'     => 'Задебелен текст',
'bold_tip'        => 'Задебелен текст',
'italic_sample'   => 'Закосен текст',
'italic_tip'      => 'Закосен текст',
'link_sample'     => 'Наслов на врска',
'link_tip'        => 'Внатрешна врска',
'extlink_sample'  => 'http://www.example.com наслов на врска',
'extlink_tip'     => 'Надворешна врска (со префиксот http://)',
'headline_sample' => 'Наслов',
'headline_tip'    => 'Ниво 2 наслов',
'math_sample'     => 'Овде вметни формула',
'math_tip'        => 'Математичка формула (LaTeX)',
'nowiki_sample'   => 'Овде внесете неформатиран текст',
'nowiki_tip'      => 'Занемари вики-форматирање',
'image_sample'    => 'Пример.jpg',
'image_tip'       => 'Вметната слика',
'media_sample'    => 'Пример.ogg',
'media_tip'       => 'Врска до податотека',
'sig_tip'         => 'Вашиот потпис со време',
'hr_tip'          => 'Хоризонтална линија',

# Edit pages
'summary'                          => 'Опис:',
'subject'                          => 'Наслов:',
'minoredit'                        => 'Ова е ситна промена',
'watchthis'                        => 'Набљудувај ја оваа страница',
'savearticle'                      => 'Зачувај',
'preview'                          => 'Преглед',
'showpreview'                      => 'Преглед',
'showlivepreview'                  => 'Преглед во живо',
'showdiff'                         => 'Прикажи промени',
'anoneditwarning'                  => "'''Предупредување:''' Не сте најавени.
Вашата IP-адреса ќе биде заведена во историјата на уредување на страницата.",
'anonpreviewwarning'               => "''Не сте најавени. Ако ја зачувате, Вашата IP-адреса ќе биде заведена во историјата на уредување на страницата.''",
'missingsummary'                   => "'''Потсетник:''' Не внесовте опис на измените. Ако притиснете Зачувај повторно, вашите измени ќе се зачуваат без опис.",
'missingcommenttext'               => 'Ве молиме внесете коментар подолу.',
'missingcommentheader'             => "'''Потсетување:''' Не внесовте наслов за овој коментар.
Ако повторно кликнете на „{{int:savearticle}}“, уредувањето ќе биде зачувано без наслов.",
'summary-preview'                  => 'Изглед на описот:',
'subject-preview'                  => 'Преглед на предметот/насловот:',
'blockedtitle'                     => 'Корисникот е блокиран',
'blockedtext'                      => "'''Вашето корисничко име или IP-адреса е блокирано.'''

Блокирањето е направено од страна на $1.
Даденото образложение е ''$2''.

* Почеток на блокирањето: $8
* Истекување на блокирањето: $6
* Корисникот што требало да биде блокиран: $7

Може да контактирате со $1 или некој друг [[{{MediaWiki:Grouppage-sysop}}|администратор]] за да разговарате во врска со блокирањето.
Можете да ја искористите можноста „Е-пошта до овој корисник“ ако е назначена важечка е-поштенска адреса во [[Special:Preferences|вашите нагодувања]] и не ви е забрането да ја користите.
Вашата сегашна IP-адреса е $3, а ID на блокирањето е #$5.
Ве молиме наведете ги сите детали прикажани погоре, во вашата евентуална реакција.",
'autoblockedtext'                  => "Вашата IP-адреса е автоматски блокирана бидејќи била користена од страна на друг корисник, кој бил блокиран од $1.
Даденото образложение е следново:

:''$2''

* Почеток на блокирањето: $8
* Истекување на блокирањето: $6
* Со намера да се блокира: $7

Може да контактирате со $1 или некој друг [[{{MediaWiki:Grouppage-sysop}}|администратор]] за да разговарате во врска со ова блокирање.

Имајте предвид дека можеби нема да можете да ја искористите можноста „Е-пошта до овој корисник“ доколку не е назначена важечка е-поштенска адреса во [[Special:Preferences|вашите нагодувања]] и ви е забрането користитење на истата.

Вашата IP-адреса е $3, a ID на блокирањеto е $5.
Ве молиме наведете ги овие детали доколку реагирате на блокирањето.",
'blockednoreason'                  => 'не е дадено образложение',
'blockedoriginalsource'            => "Кодот на '''$1''' е прикажан подолу:",
'blockededitsource'                => "Текстот на '''вашите уредувања''' на '''$1''' е прикажан подолу:",
'whitelistedittitle'               => 'Мора да сте најавени доколку сакате да уредувате',
'whitelistedittext'                => 'Мора да сте $1 за да уредувате страници.',
'confirmedittext'                  => 'Морате да ја потврдите вашата е-поштенска адреса пред да уредувате страници.
Поставете ја и валидирајте ја вашата е-поштенска адреса преку вашите [[Special:Preferences|нагодувања]].',
'nosuchsectiontitle'               => 'Не можам да го пронајдам заглавието',
'nosuchsectiontext'                => 'Се обидовте да уредите заглавие кое не постои.
Може да било преместено или избришано додека ја разгледувавте страницата.',
'loginreqtitle'                    => 'Потребно најавување',
'loginreqlink'                     => 'најава',
'loginreqpagetext'                 => 'Потребно е ваше $1 за да ги видите останатите страници.',
'accmailtitle'                     => 'Лозинката е испратена.',
'accmailtext'                      => "Случајно создадена лозинка за [[User talk:$1|$1]] е испратена на $2.

Лозинката за оваа нова корисничка сметка може да биде променета на страницата за ''[[Special:ChangePassword|промена на лозинка]]'' по најавувањето.",
'newarticle'                       => '(нова)',
'newarticletext'                   => "Проследивте врска до страница која не постои.
За да ја создадете страницата, напишете текст во полето подолу ([[{{MediaWiki:Helppage}}|помош]]). Ако сте овде по грешка, само систнете на копчето '''назад''' во вашиот прелистувач.",
'anontalkpagetext'                 => "----''Ова е страница за разговор со анонимен корисник кој сè уште не регистрирал корисничка сметка или не ја користи.
Затоа мораме да ја користиме неговата нумеричка IP-адреса за да го индентификуваме.
Една ваква IP-адреса може да ја делат повеќе корисници.
Ако сте анонимен корисник и сметате дека кон вас се упатени нерелевантни коментари, тогаш [[Special:UserLogin/signup|создајте корисничка сметка]] или [[Special:UserLogin|најавете се]] за да избегнете поистоветување со други анонимни корисници во иднина.''",
'noarticletext'                    => 'Моментално нема текст на оваа страница.
Можете да направите [[Special:Search/{{PAGENAME}}|пребарување за овој наслов на страница]] во други страници,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} пребарување во дневниците],
или [{{fullurl:{{FULLPAGENAME}}|action=edit}} да ја уредите оваа страница]</span>.',
'noarticletext-nopermission'       => 'Нема текст на оваа страница.
Можете да го [[Special:Search/{{PAGENAME}}|пребарате овој наслов]] во други страници,
или да ги <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} пребарате соодветните дневници]</span>.',
'userpage-userdoesnotexist'        => 'Корисничката сметка „$1“ не е регистрирана.
Ве молиме размислете дали навистина сакате да ја создадете/уредите оваа страница.',
'userpage-userdoesnotexist-view'   => 'Корисничката сметка „$1“ не е регистрирана.',
'blocked-notice-logextract'        => 'Овој корисник е моментално блокиран.
Подолу е прикажан последниот дневнички запис:',
'clearyourcache'                   => "'''Напомена: По зачувувањето морате да го исчистите кешот на прелистувачот за да можете ги видите промените.'''
'''Mozilla / Firefox / Safari:''' држете ''Shift'' додека кликате на ''Reload'' или притиснете ''Ctrl+F5'' или ''Ctrl+R'' (''Command+R'' на Macintosh);
'''Konqueror:''' кликнете на ''Reload'' или притиснете ''F5'';
'''Opera:''' исчистете го кешот во ''Tools → Preferences'';
'''Internet Explorer:''' држете ''Ctrl'' додека притискате на ''Refresh'' или притиснете ''Ctrl+F5''.",
'usercssyoucanpreview'             => "'''Совет:''' Употребете го копчето „{{int:showpreview}}“ за да го испробате вашиот нов CSS пред да зачувате.",
'userjsyoucanpreview'              => "'''Совет:''' Употребете го копчето „{{int:showpreview}}“ за да ја испробате вашата нова JavaScript  пред да зачувате.",
'usercsspreview'                   => "'''Запомнете дека ова е само преглед на вашиот кориснички CSS код, страницата сè уште не е зачувана!'''",
'userjspreview'                    => "'''Запомнете дека ова е само преглед на вашиот JavaScript код, страницата сè уште не е зачувана!'''",
'sitecsspreview'                   => "'''Запомнете дека ова е само преглед на овој CSS-код.'''
'''Сè уште не е зачуван!'''",
'sitejspreview'                    => "'''Запомнете дека ова е само преглед на овој JavaScript-код.'''
'''Сè уште не е зачуван!'''",
'userinvalidcssjstitle'            => "'''Предупредување:''' Нема руво „$1“.
Запомнете дека сопствените .css и .js страници имаат имиња со мали букви, пр. {{ns:user}}:Некој/vector.css наместо {{ns:user}}:Некој/Vector.css.",
'updated'                          => '(Ажурирано)',
'note'                             => "'''Напомена:'''",
'previewnote'                      => "'''Имајте предвид дека ова е само преглед.'''
Вашите промени сè уште не се зачувани!",
'previewconflict'                  => 'Овој преглед прикажува како ќе изгледа текстот внесен во горниот дел откако ќе се зачува страницата.',
'session_fail_preview'             => "'''Жалиме! Не можевме да го обработиме вашето уредување поради загуба на сесиски податоци.'''
Обидете се повторно.
Ако сè уште не функционира, обидете се да се [[Special:UserLogout|одјавите]] и повторно да се најавите.",
'session_fail_preview_html'        => "'''Жалиме, но Вашето уредување не можеше да се обработи поради губење на сесиските податоци.'''

''{{SITENAME}} има овозможено чист HTML, па прегледот е скриен како мерка за заштита од JavaScript напади.''

'''Ако ова е разумен обид за уредување, тогаш обидете се повторно.'''
Ако ова сè уште не работи, обидете се со [[Special:UserLogout|одјавување]] и повторно најавување.",
'token_suffix_mismatch'            => "'''Вашето уредување е одбиено затоа што вашиот пребарувач направил проблеми со интерпукциските знаци во жетонот за уредување.
Уредувањето не е прифатено за да се спречи несакана промена на текстот на страницата.
Ова понекогаш се случува кога користите неисправен мрежно-заснована анонимен застапничка (proxy) служба.'''",
'editing'                          => 'Уредување на $1',
'editingsection'                   => 'Уредување на $1 (пасус)',
'editingcomment'                   => 'Уредување на $1 (ново заглавие)',
'editconflict'                     => 'Конфликтни уредувања: $1',
'explainconflict'                  => "Некој друг ја променил страницата откако почнавте да ја уредувате.
Горниот дел за текст ја содржи страницата како што сега постои.
Вашите промени се прикажани во долниот дел.
Ќе морате да ги внесете вашите промени со постоечкиот текст.
'''Само''' текстот во горниот дел ќе биде зачуван кога ќе притиснете на „{{int:savearticle}}“.",
'yourtext'                         => 'Вашиот текст',
'storedversion'                    => 'Зачувана ревизија',
'nonunicodebrowser'                => "'''ПРЕДУПРЕДУВАЊЕ: Вашиот прелистувач не поддржува Уникод.
Постои решение што овозможува безбедно да уредување страници: во полето за уредување не-ASCII знаците ќе се јавуваат како хексадецимални кодови.",
'editingold'                       => "'''ПРЕДУПРЕДУВАЊЕ: Менувате застарена верзија на оваа страница.
Ако ја зачувате, сите промени по оваа верзија ќе бидат изгубени.'''",
'yourdiff'                         => 'Разлики',
'copyrightwarning'                 => "Имајте предвид дека сите придонеси на {{SITENAME}} се сметаат за издадени под лиценцата $2 (повеќе на $1).
Доколку не сакате вашите текстови да бидат слободно уредувани и разменувани, тогаш не поднесувајте ги овде.<br />
Исто така ветувате дека вие сте автор на текстот, или сте го копирале од јавниот домен или сличен слободен извор.
'''НЕ ПОДНЕСУВАЈТЕ ТЕКСТОВИ ЗАШТИТЕНИ СО АВТОРСКИ ПРАВА БЕЗ ДОЗВОЛА!'''",
'copyrightwarning2'                => "Ве молиме, имајте предвид дека сите придонеси кон {{SITENAME}} можат да бидат уредувани, менувани или отстранети од други корисници. Ако не сакате вашиот текст да биде менуван и редистрибуиран слободно, не го испраќајте овде.<br />
Исто така потврдувате дека текстот сте го напишале сами, или сте го копирале од јавен домен или сличен слободен извор (видетe $1 за детали).<br />
'''НЕ ПРАЌАЈТЕ ТЕКСТОВИ ЗАШТИТЕНИ СО АВТОРСКИ ПРАВА БЕЗ ДОЗВОЛА!'''",
'longpageerror'                    => "'''ГРЕШКА: Текстот што го внесовте е голем $1 килобајти, што е повеќе од максималните $2 килобајти. Не може да се зачува.'''",
'readonlywarning'                  => "'''ПРЕДУПРЕДУВАЊЕ: Базата на податоци е заклучена заради одржување, па нема да можете да ги зачувате вашите промени сега.
Пробајте да го зачувате текстот локално (cut & paste) во некоја податотека и да го пратите подоцна.'''

Администраторот кој ја заклучил базата на податоци го дал следново објаснување: $1",
'protectedpagewarning'             => "'''Предупредување:  Оваа страница е заклучена, така што само корисници со администраторски привилегии можат да ја уредуваат.'''
За ваша информација, последната ставка во дневникот на промени е прикажана подолу:",
'semiprotectedpagewarning'         => "'''Предупредување:'''  Оваа страница е заклучена, така што само регистрирани корисници може да ја уредуваат.'''
За ваша информација, последната ставка во дневникот на промени е прикажана подолу:",
'cascadeprotectedwarning'          => "'''Предупредување:''' Оваа страница е заклучена, така што можат да ја уредуваат само корисници со администраторски привилегии, бидејќи е вклучена во {{PLURAL:$1|следнава „преносно-заштитена“ страница|следниве „преносно-заштитени“ страници}}:",
'titleprotectedwarning'            => "'''Предупредување:'''  Оваа страница е заклучена, така што се потребни [[Special:ListGroupRights|посебни права]] за да се создаде.''''
За ваша информација, последната ставка во дневникот на промени е прикажана подолу:",
'templatesused'                    => '{{PLURAL:$1|Шаблон користен на оваа страница|Шаблони користени на оваа страница}}:',
'templatesusedpreview'             => '{{PLURAL:$1|Шаблон користен во овој преглед|Шаблони користени во овој преглед}}:',
'templatesusedsection'             => '{{PLURAL:$1|Шаблон користен во ова заглавие|Шаблони користени во овие заглавија}}:',
'template-protected'               => '(заштитен)',
'template-semiprotected'           => '(полузаштитен)',
'hiddencategories'                 => 'Оваа страница припаѓа на {{PLURAL:$1|1 скриена категорија|$1 скриени категории}}:',
'edittools'                        => '<!-- Овој текст ќе се прикаже под обрасците за уредување и подигање. -->',
'nocreatetitle'                    => 'Создавањето на нови страници е ограничено',
'nocreatetext'                     => '{{SITENAME}} ја има ограничено можноста за создавање нови страници.
Можете да се вратите назад и да уредувате постоечка страница или [[Special:UserLogin|најавете се или создајте нова корисничка сметка]].',
'nocreate-loggedin'                => 'Немате дозвола да создавате нови страници.',
'sectioneditnotsupported-title'    => 'Уредувањето на заглавија не е поддржано',
'sectioneditnotsupported-text'     => 'На оваа станица не е поддржано уредувањето на заглавија.',
'permissionserrors'                => 'Грешки во правата',
'permissionserrorstext'            => 'Немате дозвола да го направите тоа, од {{PLURAL:$1|следнава причина|следниве причини}}:',
'permissionserrorstext-withaction' => 'Немате дозвола за $2, од {{PLURAL:$1|следнава причина|следниве причини}}:',
'recreate-moveddeleted-warn'       => "Внимание: Повторно создавате страница што претходно била бришена.'''

Размислете дали е правилно да продолжите со уредување на оваа страница.
Подолу е прикажан дневникот на бришења и преместувања на оваа страница за ваш преглед:",
'moveddeleted-notice'              => 'Оваа страница била претходно бришена.
Дневникот на бришења и преместувања за оваа страница е прикажан подолу за ваше дополнително информирање.',
'log-fulllog'                      => 'Преглед на целиот дневник',
'edit-hook-aborted'                => 'Уредувањето прекинато со кука.
Не е дадено никакво објаснување.',
'edit-gone-missing'                => 'Не е возможно да се ажурира страницата.
Изгледа дека во меѓувреме е избришана од некого.',
'edit-conflict'                    => 'Конфликтно уредување.',
'edit-no-change'                   => 'Вашите уредувања беа игнорирани, бидејќи не се направени промени врз текстот.',
'edit-already-exists'              => 'Не може да се создаде нова страница.
Истата веќе постои.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Предупредување: Оваа страница користи премногу повикувања на parser функции.

Треба да има помалку од $2 {{PLURAL:$2|повикување|повикувања}} а сега има {{PLURAL:$1|$1 повикување|$1 повикувања}}.',
'expensive-parserfunction-category'       => 'Страници со премногу повикувања на парсер функции',
'post-expand-template-inclusion-warning'  => "'''Предупредување:''' Големината на вклучените шаблони е преголема.
Некои шаблони нема да бидат вклучени.",
'post-expand-template-inclusion-category' => 'Страници каде што големината на вклучените шаблони е пречекорена',
'post-expand-template-argument-warning'   => "'''Предупредување:''' Оваа страница содржи најмалку еден шаблонски аргумент кој е со преголема должина.
Таквите аргументи ќе бидат изземени при парсирањето.",
'post-expand-template-argument-category'  => 'Страници кои содржат изземени аргументи на шаблони',
'parser-template-loop-warning'            => 'Пронајдена е јамка во шаблонот: [[$1]]',
'parser-template-recursion-depth-warning' => 'Ограничувањето на рекурзивната длабочина надмината во шаблонот ($1)',
'language-converter-depth-warning'        => 'Надмината е границата на длабочината  на јазичниот претворач ($1)',

# "Undo" feature
'undo-success' => 'Уредувањето може да се откаже.
Ве молиме споредете ги промените со претходната верзија за да проверите дали тоа е сигурно она што сакате да го направите, а потоа зачувајте ги промените за да го завршите откажувањето на претходното уредување.',
'undo-failure' => 'Уредувањето не можеше да се откаже заради други конфликтни уредувања кои настанале во меѓувреме.',
'undo-norev'   => 'Измената не можеше да биде вратена бидејќи не постои или била избришана.',
'undo-summary' => 'Откажано уредување $1 од ([[Special:Contributions/$2|$2]] | [[Разговор со корисник:$2|Разговор]])',

# Account creation failure
'cantcreateaccounttitle' => 'Не може да се создаде корисничка сметка',
'cantcreateaccount-text' => "Создавањето на корисничка сметка од оваа IP-адреса ('''$1''') е блокирано од страна на [[User:$3|$3]].

Образложението дадено од страна на $3 е ''$2''",

# History pages
'viewpagelogs'           => 'Преглед на дневници за оваа страница',
'nohistory'              => 'Не постои историја на уредување за оваа страница.',
'currentrev'             => 'Тековна ревизија',
'currentrev-asof'        => 'Последна ревизија од $1',
'revisionasof'           => 'Ревизија од $1',
'revision-info'          => 'Ревизија од $1; направена од $2',
'previousrevision'       => '← Претходна ревизија',
'nextrevision'           => 'Следна ревизија →',
'currentrevisionlink'    => 'Последна ревизија',
'cur'                    => 'мом',
'next'                   => 'след',
'last'                   => 'посл',
'page_first'             => 'прв',
'page_last'              => 'последен',
'histlegend'             => "Разлика помеѓу ревизии: означете ги ревизиите кои сакате да ги споредите и притиснете Enter или копчето на дното од страницата.<br />
Легенда: '''({{int:cur}})''' = разлика со последна ревизија, '''({{int:last}})''' = разлика со претходна ревизија, '''{{int:minoreditletter}}''' = ситна промена.",
'history-fieldset-title' => 'Прелистување на историја',
'history-show-deleted'   => 'Само избришани',
'histfirst'              => 'Први',
'histlast'               => 'Последни',
'historysize'            => '({{PLURAL:$1|1 бајт|$1 бајти}})',
'historyempty'           => '(празно)',

# Revision feed
'history-feed-title'          => 'Историја на ревидирање',
'history-feed-description'    => 'Историја на ревидирање за оваа страница на викито',
'history-feed-item-nocomment' => '$1 на $2',
'history-feed-empty'          => 'Бараната страница не постои.
Може била избришана од викито или преименувана.
Обидете се да [[Special:Search|пребарате низ викито]] за релевантни нови страници.',

# Revision deletion
'rev-deleted-comment'         => '(избришан коментар)',
'rev-deleted-user'            => '(избришано корисничко име)',
'rev-deleted-event'           => '(избришан запис на дејство)',
'rev-deleted-user-contribs'   => '[отстрането е корисничкото име или IP-адресата - уредувањето нема да се прикаже на списокот на придонеси]',
'rev-deleted-text-permission' => "Ревизија на оваа страницата била '''бришана'''.
Можеби има детали во [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} дневникот на бришења].",
'rev-deleted-text-unhide'     => "Оваа ревизија на страницата била '''избришана'''.
Повеќе детали има во [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} дневникот на бришења].
Како администратор вие сепак можете [$1 да ја погледнете оваа ревизија] ако сакате да продолжите.",
'rev-suppressed-text-unhide'  => "Оваа ревизија на страница била '''потисната'''.
Повеќе детали има во [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} дневникот на потиснувања].
Како администратор, вие сепак можете [$1 да ја погледнете оваа ревизија] ако сакате да продолжите.",
'rev-deleted-text-view'       => "Ревизија на оваа страницата била '''избришана'''.
Како администратор вие можете да ја погледнете; можеби има повеќе детали во [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} дневникот на бришења].",
'rev-suppressed-text-view'    => "Ревизија на оваа страница беше '''супресирана'''.
Како администратор вие можете да ја погледнете; можеби има повеќе детали во [{{fullurl:Special:Log/suppress|page={{FULLPAGENAMEE}}}} дневникот на супресии].",
'rev-deleted-no-diff'         => "Не може да ја погледнете оваа разлика бидејќи една од ревизиите била '''избришана'''.
Може да најдете повеќе детали во [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} дневникот на бришења].",
'rev-suppressed-no-diff'      => "Не можете да ги видите разликите помеѓу овие ревизии бидејќи една од нив е '''избришана'''.",
'rev-deleted-unhide-diff'     => "Една од ревизиите на оваа разлика била '''избришана'''.
Можеби има детали во [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} дневникот на бришења].
Како администратор вие сепак можете [$1 да ја видите оваа разлика] ако сакате да продолжите.",
'rev-suppressed-unhide-diff'  => "Една од ревизиите на оваа разлика била '''сокриена'''.
Дополнителни детали можебиима во [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} дневникот на прикривања].
Како администратор вие можете да [$1 ја видите оваа разлика] ако сакате да продолжите.",
'rev-deleted-diff-view'       => "Една од ревизиите на оваа разлика е '''избришана'''.
Како администратор можете да ја погледате оваа разлика; детали можеби има во [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} дневникот на бришење].",
'rev-suppressed-diff-view'    => "Една од ревизиите на оваа разлика е '''прикриена'''.
Како администратор можете да ја погледате оваа разлика; детали можеби има во [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} дневникот на прикривања].",
'rev-delundel'                => 'прикажи/сокриј',
'rev-showdeleted'             => 'прикажи',
'revisiondelete'              => 'Избриши/врати ревизии',
'revdelete-nooldid-title'     => 'Бараната измена не постои',
'revdelete-nooldid-text'      => 'Не сте посочиле измена (измени) за да се изврши оваа функција или посочената измена не постои или се обидувате да ја сокриете моменталната измена.',
'revdelete-nologtype-title'   => 'Не е наведен тип на дневник',
'revdelete-nologtype-text'    => 'Немате наведено тип на дневник за кој треба да се изврши ова дејство.',
'revdelete-nologid-title'     => 'Погрешно внесен запис во дневник.',
'revdelete-nologid-text'      => 'Немате наведено дневнички запис за извршување на оваа функција или наведената ставка не постои.',
'revdelete-no-file'           => 'Наведената податотека не постои.',
'revdelete-show-file-confirm' => 'Дали сакате да ја погледнете избришаната ревизија на податотеката „<nowiki>$1</nowiki>“ од $2 во $3?',
'revdelete-show-file-submit'  => 'Да',
'revdelete-selected'          => "'''{{PLURAL:$2|Избрана ревизија|Избрани ревизии}} од [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Одбран настан од дневник|Одбрани настани од дневник}}:'''",
'revdelete-text'              => "'''Избришаните измени и настани сѐ уште ќе се појавуваат во историјата на страницата и дневниците, но делови од нивната содржина ќе бидат недостапни за јавноста.'''
Други администратори на {{SITENAME}} сѐ уште ќе имаат пристап до скриената содржина и ќе можат да ја вратат преку истиот посредник, освен ако не се поставени дополнителни ограничувања.",
'revdelete-confirm'           => 'Потврдете дека сакате да го направите ова, дека ги сфаќате последиците, и дека тоа го правите во согласност со [[{{MediaWiki:Policy-url}}|правилата]].',
'revdelete-suppress-text'     => "Ограничувањето '''се користи''' само во следниве случаи:
* Несоодветни лични информации
*: ''домашни адреси и телефонски броеви, матични броеви, и.т.н.''",
'revdelete-legend'            => 'Постави ограничувања за видливост',
'revdelete-hide-text'         => 'Сокриј го текст на ревизија',
'revdelete-hide-image'        => 'Сокриј содржина на податотека',
'revdelete-hide-name'         => 'Сокриј го дејството и неговата цел',
'revdelete-hide-comment'      => 'Сокриј опис на уредување',
'revdelete-hide-user'         => 'Сокриј корисничко име/IP на уредувач',
'revdelete-hide-restricted'   => 'Постави ограничувања и за администратори на ист начин како и за останатите',
'revdelete-radio-same'        => '(не менувај)',
'revdelete-radio-set'         => 'Да',
'revdelete-radio-unset'       => 'Не',
'revdelete-suppress'          => 'Сокриј податоци и од администраторите',
'revdelete-unsuppress'        => 'Отстрани ограничувања на обновени ревизии',
'revdelete-log'               => 'Причина:',
'revdelete-submit'            => 'Примени на одбрани {{PLURAL:$1|ревизија|ревизии}}',
'revdelete-logentry'          => 'променета е видливост на ревизија на [[$1]]',
'logdelete-logentry'          => 'променета видливост на настан од [[$1]]',
'revdelete-success'           => "'''Видливоста на ревизијата е успешно изменета.'''",
'revdelete-failure'           => "'''Видливоста на ревизијата не можеше да се измени:'''
$1",
'logdelete-success'           => "'''Успешно нагодување на дневник на видливост.'''",
'logdelete-failure'           => "'''Дневникот на видливост не може да биде нагоден:'''
$1",
'revdel-restore'              => 'Промена на видливост',
'revdel-restore-deleted'      => 'избришани ревизии',
'revdel-restore-visible'      => 'видливи ревизии',
'pagehist'                    => 'Историја на страница',
'deletedhist'                 => 'Историја на бришења',
'revdelete-content'           => 'содржина',
'revdelete-summary'           => 'опис на уредување',
'revdelete-uname'             => 'корисничко име',
'revdelete-restricted'        => 'применети ограничувања на систем оператори',
'revdelete-unrestricted'      => 'отстранети ограничувања за систем оператори',
'revdelete-hid'               => 'сокриј $1',
'revdelete-unhid'             => 'прикажи $1',
'revdelete-log-message'       => '$1 за $2 {{PLURAL:$2|ревизија|ревизии}}',
'logdelete-log-message'       => '$1 од $2 {{PLURAL:$2|настан|настани}}',
'revdelete-hide-current'      => 'Грешка при сокривање на ставката датирана на $2, $1: ова е актуелна ревизија.',
'revdelete-show-no-access'    => 'Грешка при прикажување на ставката датирана на $2, $1: оваа ставка е означена како „ограничена“. Немате пристап до неа.',
'revdelete-modify-no-access'  => 'Грешка при промена на ставката датирана на $2, $1: оваа ставка била означена како „ограничена“. Немате пристап до неа.',
'revdelete-modify-missing'    => 'Грешка при промена на ставка со број $1: ја нема во базата на податоци!',
'revdelete-no-change'         => "'''Предупредување:''' ставката датирана на $2, $1 веќе ги има бараните нагодувања за видливост.",
'revdelete-concurrent-change' => 'Грешка при промена на ставката датирана на $2, $1: нејзиниот статус изгледа дека бил изменет од некој друг додека вие се обидувавте да го направите тоа. Ве молиме проверете во дневниците.',
'revdelete-only-restricted'   => 'Грешка при сокривањето на записот од $2, $1: не можете да криете записи од администратори без воедно да изберете едно од другите нагодувања на видливоста.',
'revdelete-reason-dropdown'   => '*Вообичаени причини за бришење
** Прекршување на авторски права
** Несоодветни лични информации',
'revdelete-otherreason'       => 'Друга/дополнителна причина:',
'revdelete-reasonotherlist'   => 'Друга причина',
'revdelete-edit-reasonlist'   => 'Уреди причини за бришење',
'revdelete-offender'          => 'Автор на ревизија:',

# Suppression log
'suppressionlog'     => 'Дневник на сокривања',
'suppressionlogtext' => 'Подолу е прикажан списокот  на бришења и блокирања каде има и содржини скриени и за администраторите.<br />
Погледајте го [[Special:IPBlockList|списокот на блокирани IP-адреси]].',

# Revision move
'moverevlogentry'              => '{{PLURAL:$3|преместена една ревизија|преместени $3 ревизии}} од $1 кон $2',
'revisionmove'                 => 'Премести ревизии од „$1“',
'revmove-explain'              => 'Следниве ревизии ќе бидат преместени од $1 кон назначената целна страница. Ако целта не постои, тогаш ќе биде создадена. Во друг случај, овие ревизии ќе бидат споени во историјата на страницата.',
'revmove-legend'               => 'Определете целна страница и опис',
'revmove-submit'               => 'Премести ревизии кон одбраната страница',
'revisionmoveselectedversions' => 'Премести одбрани ревизии',
'revmove-reasonfield'          => 'Причина:',
'revmove-titlefield'           => 'Целна страница:',
'revmove-badparam-title'       => 'Лоши параметри',
'revmove-badparam'             => 'Вашето барање содржи недозволени или недоволни параметри. Одете на „назад“ и обидете се повторно.',
'revmove-norevisions-title'    => 'Неправилна целна ревизија',
'revmove-norevisions'          => 'Немате укажано една или повеќе целни ревизии за да може да се изврши оваа функција, или пак назначената ревизија не постои.',
'revmove-nullmove-title'       => 'Лош наслов',
'revmove-nullmove'             => 'Изворната и целната страница се истоветни. Одете на „назад“ и внесете поинаков назив за страницата кој не гласи „$1“.',
'revmove-success-existing'     => '{{PLURAL:$1|Една ревизија од [[$2]] е преместена|$1 ревизии од [[$2]] се преместени}} на постоечката страница [[$3]].',
'revmove-success-created'      => '{{PLURAL:$1|Една ревизија од [[$2]] е преместена|$1 ревизии од [[$2]] се преместени}} на новосоздадената страница [[$3]].',

# History merging
'mergehistory'                     => 'Спојување на истории на страница',
'mergehistory-header'              => 'Оваа страница овозможува спојување на ревизии на изворна страница во нова (друга) страница.
Претходно проверете дека таа промена ќе го сочува историскиот континуитетот на страницата.',
'mergehistory-box'                 => 'Спојување на ревизии на две страници:',
'mergehistory-from'                => 'Изворна страница:',
'mergehistory-into'                => 'Целна страница:',
'mergehistory-list'                => 'Спојлива историја на уредување',
'mergehistory-merge'               => 'Следните ревизии на [[:$1]] можат да се спојат во [[:$2]].
Изберете ги само оние ревизии кои се создадени пред назначеното време.
Предупредуваме дека ако ги користите навигационите врски, изборот ќе се изгуби.',
'mergehistory-go'                  => 'Приказ на уредувања кои можат да се спојат',
'mergehistory-submit'              => 'Спојување на ревизии',
'mergehistory-empty'               => 'Нема ревизии кои можат да се спојат.',
'mergehistory-success'             => '$3 {{PLURAL:$3|ревизија|ревизии}} на [[:$1]] успешно {{PLURAL:$3|е споена|се споени}} во [[:$2]].',
'mergehistory-fail'                => 'Не е возможно да се направи спојување на историјата, проверете ја страницата и временските параметри.',
'mergehistory-no-source'           => 'Изворната страница $1 не постои.',
'mergehistory-no-destination'      => 'Целната страница $1 не постои.',
'mergehistory-invalid-source'      => 'Изворната страница мора да има важечки наслов.',
'mergehistory-invalid-destination' => 'Целната страница мора да има важечки наслов.',
'mergehistory-autocomment'         => 'Споено [[:$1]] во [[:$2]]',
'mergehistory-comment'             => 'Споено [[:$1]] во [[:$2]]: $3',
'mergehistory-same-destination'    => 'Изворната и целната страница треба да се различни',
'mergehistory-reason'              => 'Причина:',

# Merge log
'mergelog'           => 'Дневник на спојувања на страници',
'pagemerge-logentry' => 'споено [[$1]] во [[$2]] (ревизии до $3)',
'revertmerge'        => 'Одвојување',
'mergelogpagetext'   => 'Следи список на скорешни спојувања на истории на страници.',

# Diffs
'history-title'            => 'Историја на ревизии за „$1“',
'difference'               => '(Разлика меѓу ревизија)',
'difference-multipage'     => '(Разлики помеѓу страници)',
'lineno'                   => 'Ред $1:',
'compareselectedversions'  => 'Спореди избрани ревизии',
'showhideselectedversions' => 'Прикажи/сокриј избрани ревизии',
'editundo'                 => 'откажи',
'diff-multi'               => '({{PLURAL:$1|Не е прикажана една меѓувремена ревизија|Не се прикажани $1 меѓувремени ревизии}} од {{PLURAL:$2|еден корисник|$2 корисници}})',
'diff-multi-manyusers'     => '({{PLURAL:$1|Не е прикажана една меѓувремена ревизија направена|Не се прикажани $1 меѓувремени ревизии направени}} од повеќе од $2 {{PLURAL:$2|корисник|корисници}})',

# Search results
'searchresults'                    => 'Резултати од пребарувањето',
'searchresults-title'              => 'Резултати од пребарувањето за „$1“',
'searchresulttext'                 => 'За повеќе информации во врска со пребарување на {{SITENAME}}, погледнете [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => "Пребарувате '''[[:$1]]''' на ([[Special:Prefixindex/$1|сите страници кои започнуваат со „$1“]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|сите страници кои водат до „$1“]])",
'searchsubtitleinvalid'            => "Пребарувавте '''$1'''",
'toomanymatches'                   => 'Премногу резултати од пребарувањето, ве молиме обидете се со поинакво барање',
'titlematches'                     => 'Насловот на статијата одговара',
'notitlematches'                   => 'Ниеден наслов на страница не одговара',
'textmatches'                      => 'Совпаднат текст во страниците',
'notextmatches'                    => 'Ниеден текст во статиите не одговара',
'prevn'                            => '{{PLURAL:претходна $1| претходни $1}}',
'nextn'                            => '{{PLURAL:следна $1|следни $1}}',
'prevn-title'                      => '{{PLURAL:$1|Претходен|Претходни}} $1 {{PLURAL:$1|резултат|резултати}}',
'nextn-title'                      => '{{PLURAL:$1|Следен|Следни}} $1 {{PLURAL:$1|резултат|резултати}}',
'shown-title'                      => 'Прикажи $1 {{PLURAL:$1|резултат|резултати}} на страница',
'viewprevnext'                     => 'Погледајте ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => 'Нагодувања на пребарувањето',
'searchmenu-exists'                => "'''На ова вики има страница со наслов „[[:$1]]“'''",
'searchmenu-new'                   => "Создајте ја страницата „[[:$1]]“ на ова вики!'''",
'searchhelp-url'                   => 'Help:Содржина',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Прелистување на страници со овој префикс]]',
'searchprofile-articles'           => 'Статии',
'searchprofile-project'            => 'Помош и проектни страници',
'searchprofile-images'             => 'Мултимедија',
'searchprofile-everything'         => 'Сè',
'searchprofile-advanced'           => 'Напредно',
'searchprofile-articles-tooltip'   => 'Пребарај во $1',
'searchprofile-project-tooltip'    => 'Пребарај во $1',
'searchprofile-images-tooltip'     => 'Пребарај податотеки',
'searchprofile-everything-tooltip' => 'Пребарување по сета содржина (вклучувајќи страници за разговор)',
'searchprofile-advanced-tooltip'   => 'Пребарај во посебни именски простори',
'search-result-size'               => '$1 ({{PLURAL:$2|1 збор|$2 збора}})',
'search-result-category-size'      => '{{PLURAL:$1|1 член|$1 члена}} ({{PLURAL:$2|1 поткатегорија|$2 поткатегории}}, {{PLURAL:$3|1 податотека|$3 податотеки}})',
'search-result-score'              => 'Релевантност: $1%',
'search-redirect'                  => '(пренасочување $1)',
'search-section'                   => '(пасус $1)',
'search-suggest'                   => 'Дали мислевте на: $1',
'search-interwiki-caption'         => 'Збратимени проекти',
'search-interwiki-default'         => 'Најдено на $1:',
'search-interwiki-more'            => '(уште)',
'search-mwsuggest-enabled'         => 'со сугестии',
'search-mwsuggest-disabled'        => 'без сугестии',
'search-relatedarticle'            => 'Поврзано',
'mwsuggest-disable'                => 'Оневозможи AJAX сугестии',
'searcheverything-enable'          => 'Барај во сите именски простори',
'searchrelated'                    => 'поврзано',
'searchall'                        => 'сè',
'showingresults'                   => "Подолу {{PLURAL:$1|е прикажан '''1''' резултат|се прикажани '''$1''' резултати}} почнувајќи од #'''$2'''.",
'showingresultsnum'                => "Подолу {{PLURAL:$3|е прикажан '''1''' резултат|се прикажани '''$3''' резултати}} почнувајќи од '''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Резултат '''$1''' од '''$3'''|Резултати '''$1 - $2''' од '''$3'''}} за '''$4'''",
'nonefound'                        => "'''Напомена''': Само некои именски простори се пребаруваат по основно.
Обидете се со додавање на префиксот ''all:'' за да пребарувате низ сите содржини (вклучувајќи страници за разговор, шаблони, итн) или користете го бараниот именски простор како префикс.",
'search-nonefound'                 => 'Нема резултати кои го задоволуваат бараното.',
'powersearch'                      => 'Напредно пребарување',
'powersearch-legend'               => 'Напредно пребарување',
'powersearch-ns'                   => 'Пребарај во следниве именски простори:',
'powersearch-redir'                => 'Список на пренасочувања',
'powersearch-field'                => 'Пребарување на',
'powersearch-togglelabel'          => 'Одбери:',
'powersearch-toggleall'            => 'Сè',
'powersearch-togglenone'           => 'Ништо',
'search-external'                  => 'Надворешно пребарување',
'searchdisabled'                   => '{{SITENAME}} пребарувањето е оневозможено.
Во меѓувреме, можете да пребарувате преку Google.
Да напоменеме дека нивното индексирање на {{SITENAME}} содржините може да биде застарено.',

# Quickbar
'qbsettings'               => 'Лента за брз избор',
'qbsettings-none'          => 'Без мени',
'qbsettings-fixedleft'     => 'Фиксирано лево',
'qbsettings-fixedright'    => 'Фиксирано десно',
'qbsettings-floatingleft'  => 'Пловечко лево',
'qbsettings-floatingright' => 'Пловечки десно',

# Preferences page
'preferences'                   => 'Нагодувања',
'mypreferences'                 => 'мои нагодувања',
'prefs-edits'                   => 'Број на уредувања:',
'prefsnologin'                  => 'Не сте најавени',
'prefsnologintext'              => 'Мора да бидете <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} најавени]</span> за промена на вашите кориснички нагодувања.',
'changepassword'                => 'Промени лозинка',
'prefs-skin'                    => 'Руво',
'skin-preview'                  => 'Прегледај',
'prefs-math'                    => 'Математика',
'datedefault'                   => 'Небитно',
'prefs-datetime'                => 'Датум и време',
'prefs-personal'                => 'Кориснички профил',
'prefs-rc'                      => 'Скорешни промени',
'prefs-watchlist'               => 'Список на набљудувања',
'prefs-watchlist-days'          => 'Број на денови за приказ во списокот на набљудувања:',
'prefs-watchlist-days-max'      => '(највеќе 7 дена)',
'prefs-watchlist-edits'         => 'Максимален број на промени прикажани во проширениот список на набљудувања:',
'prefs-watchlist-edits-max'     => '(највеќе: 1000)',
'prefs-watchlist-token'         => 'Жетон на список на набљудувања:',
'prefs-misc'                    => 'Други нагодувања',
'prefs-resetpass'               => 'Промени лозинка',
'prefs-email'                   => 'Нагодувања за е-пошта',
'prefs-rendering'               => 'Изглед',
'saveprefs'                     => 'Зачувај',
'resetprefs'                    => 'Избриши незачувани измени',
'restoreprefs'                  => 'Врати сè по основно',
'prefs-editing'                 => 'Уредување',
'prefs-edit-boxsize'            => 'Големина на прозорецот за уредување.',
'rows'                          => 'Редови:',
'columns'                       => 'Колони:',
'searchresultshead'             => 'Пребарување',
'resultsperpage'                => 'Резултати по страница:',
'contextlines'                  => 'Линии по резултат:',
'contextchars'                  => 'Контекст по ред:',
'stub-threshold'                => 'Праг за <a href="#" class="stub">никулци</a> (бајти):',
'stub-threshold-disabled'       => 'Оневозможено',
'recentchangesdays'             => 'Денови за приказ во скорешните промени:',
'recentchangesdays-max'         => '(највеќе $1 {{PLURAL:$1|ден|дена}})',
'recentchangescount'            => 'Број на уредувања кои ќе се прикажат по основно:',
'prefs-help-recentchangescount' => 'Подразбира скорешни промени, истории на страници и дневници.',
'prefs-help-watchlist-token'    => 'Со внесување на тајниот клуч во полево ќе создадете RSS емитување за вашиот список на набљудувања.
Секој што го знае клучот во полево ќе може да го чита вашиот список на набљудувања, па затоа изберете некоја безбедна вредност.
Еве една случајно-создадена вредност што можете да ја користите: $1',
'savedprefs'                    => 'Вашите нагодувања се зачувани.',
'timezonelegend'                => 'Часовна зона:',
'localtime'                     => 'Локално време:',
'timezoneuseserverdefault'      => 'Според опслужувачот',
'timezoneuseoffset'             => 'Друго (посочете отстапување)',
'timezoneoffset'                => 'Отстапка¹:',
'servertime'                    => 'Време на опслужувачот:',
'guesstimezone'                 => 'Пополни од прелистувачот',
'timezoneregion-africa'         => 'Африка',
'timezoneregion-america'        => 'Америка',
'timezoneregion-antarctica'     => 'Антарктик',
'timezoneregion-arctic'         => 'Арктик',
'timezoneregion-asia'           => 'Азија',
'timezoneregion-atlantic'       => 'Атлантски Океан',
'timezoneregion-australia'      => 'Австралија',
'timezoneregion-europe'         => 'Европа',
'timezoneregion-indian'         => 'Индиски Океан',
'timezoneregion-pacific'        => 'Тихи Океан',
'allowemail'                    => 'Дозволи е-пошта од други корисници',
'prefs-searchoptions'           => 'Нагодувања на пребарувањето',
'prefs-namespaces'              => 'Именски простори',
'defaultns'                     => 'Инаку пребарувај во овие именски простори:',
'default'                       => 'по основно',
'prefs-files'                   => 'Податотеки',
'prefs-custom-css'              => 'Посебно CSS',
'prefs-custom-js'               => 'Посебно JS',
'prefs-common-css-js'           => 'Заеднички CSS/JS за сите изгледи:',
'prefs-reset-intro'             => 'Може да ја користите оваа страница за враќање на вашите нагодувања на основно-зададените нагодувања на викито. Оваа не може да се врати.',
'prefs-emailconfirm-label'      => 'Потврда на е-пошта:',
'prefs-textboxsize'             => 'Големина на прозорец за уредување',
'youremail'                     => 'Е-пошта:',
'username'                      => 'Корисничко име:',
'uid'                           => 'ID на корисникот:',
'prefs-memberingroups'          => 'Член на {{PLURAL:$1|групата|групите}}:',
'prefs-registration'            => 'Време на регистрација:',
'yourrealname'                  => 'Вистинско име:',
'yourlanguage'                  => 'Јазик:',
'yourvariant'                   => 'Варијанта:',
'yournick'                      => 'Потпис:',
'prefs-help-signature'          => 'Коментарите на страниците за разговор треба да се потпишуваат со „<nowiki>~~~~</nowiki>“ кое ќе се претвори во вашиот потпис и време.',
'badsig'                        => 'Грешка во потписот.
Проверете ги HTML ознаките.',
'badsiglength'                  => 'Вашиот потпис е премногу долг.
Мора да биде со помалку од $1 {{PLURAL:$1|знак|знаци}}.',
'yourgender'                    => 'Пол:',
'gender-unknown'                => 'Непосочен',
'gender-male'                   => 'Машки',
'gender-female'                 => 'Женски',
'prefs-help-gender'             => 'По избор: се користи од вики-програмот за создавање на пораки во зависност од наведениот пол.
Оваа информација ќе биде јавно достапна.',
'email'                         => 'Е-пошта',
'prefs-help-realname'           => "'''Вистинското име''' е изборно.
Доколку изберете да го впишете вашето име, тоа може да се искористи за оддавање на заслуги за вашата работа.",
'prefs-help-email'              => 'Е-поштата е незадолжителна, но ќе ви треба за добивање на нова лозинка ако си ја заборавите постоечката.
Исто така можете да изберете другите корисници да ве контактираат преку вашата корисничка страница или страница за разговор, без да го откриете вашиот идентитет.',
'prefs-help-email-required'     => 'Е-поштенска адреса е задолжително да се наведе.',
'prefs-info'                    => 'Основни информации',
'prefs-i18n'                    => 'Интернационализација',
'prefs-signature'               => 'Потпис',
'prefs-dateformat'              => 'Формат на датумот',
'prefs-timeoffset'              => 'Временско отстапување',
'prefs-advancedediting'         => 'Напредни нагодувања',
'prefs-advancedrc'              => 'Напредни нагодувања',
'prefs-advancedrendering'       => 'Напредни нагодувања',
'prefs-advancedsearchoptions'   => 'Напредни нагодувања',
'prefs-advancedwatchlist'       => 'Напредни нагодувања',
'prefs-displayrc'               => 'Нагодувања за приказ',
'prefs-displaysearchoptions'    => 'Нагодувања за приказ',
'prefs-displaywatchlist'        => 'Нагодувања за приказ',
'prefs-diffs'                   => 'Разлики',

# User rights
'userrights'                     => 'Раководење со кориснички права',
'userrights-lookup-user'         => 'Раководење со кориснички групи',
'userrights-user-editname'       => 'Внесете корисничко име:',
'editusergroup'                  => 'Уреди кориснички групи',
'editinguser'                    => "Менување на корисничките права на корисникот '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'       => 'Уреди ги корисничките групи',
'saveusergroups'                 => 'Зачувај ги корисничките групи',
'userrights-groupsmember'        => 'Член на:',
'userrights-groupsmember-auto'   => 'Подразбран член на:',
'userrights-groups-help'         => 'Можете да ги промените групите во кои е овој корисник:
* Маркирано, значи дека корисникот е во групата.
* Немаркирано, значи дека корисникот не е во групата.
* Ѕвезда (*) означува дека не можете да ја тргнете групата доколку сте ја додале, и обратно.',
'userrights-reason'              => 'Причина:',
'userrights-no-interwiki'        => 'Немате дозвола за уредување на кориснички права на други викија.',
'userrights-nodatabase'          => 'Базата на податоци $1 не постои или не е локална.',
'userrights-nologin'             => 'Мора да сте [[Special:UserLogin|најавени]] со администраторска корисничка сметка за да може да вршите промена на кориснички права.',
'userrights-notallowed'          => 'Вашата корисничка сметка нема дозвола за промена на кориснички права.',
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
'group-bot-member'           => 'бот',
'group-sysop-member'         => 'администратор',
'group-bureaucrat-member'    => 'Бирократ',
'group-suppress-member'      => 'Надгледувач',

'grouppage-user'          => '{{ns:project}}:Корисници',
'grouppage-autoconfirmed' => '{{ns:project}}:Автопотврдени корисници',
'grouppage-bot'           => '{{ns:project}}:Ботови',
'grouppage-sysop'         => '{{ns:project}}:Администратори',
'grouppage-bureaucrat'    => '{{ns:project}}:Бирократи',
'grouppage-suppress'      => '{{ns:project}}:Надгледувач',

# Rights
'right-read'                  => 'Читање страници',
'right-edit'                  => 'Уредување страници',
'right-createpage'            => 'Создавање на страници (кои не се страници за разговор)',
'right-createtalk'            => 'Создавање на страници за разговор',
'right-createaccount'         => 'Создавање на нови кориснички сметки',
'right-minoredit'             => 'Означување на уредувањата како ситни',
'right-move'                  => 'Преместување страници',
'right-move-subpages'         => 'Преместување на страници со нивните потстраници',
'right-move-rootuserpages'    => 'Преместување на основна корисничка страница',
'right-movefile'              => 'Преместување на податотеки',
'right-suppressredirect'      => 'Не прави пренасочување од старото име при преместување на страница',
'right-upload'                => 'Подигни податотеки',
'right-reupload'              => 'Заменување на постоечки податотеки',
'right-reupload-own'          => 'Преснимување на постоечка податотека подигната од вас',
'right-reupload-shared'       => 'Наложување на едни податотеки врз други на заедничката мултимедијална ризница локално',
'right-upload_by_url'         => 'Подигање на податотека од URL-адреса',
'right-purge'                 => 'Бришење од опслужувачки кеш на страницата без барање потврда за тоа',
'right-autoconfirmed'         => 'Уредување на делумно заштитени страници',
'right-bot'                   => 'Третиран како автоматски процес',
'right-nominornewtalk'        => 'Ситните уредувања да не поттикнуваат потсетник за нова порака',
'right-apihighlimits'         => 'Користење на помалку ограничени барања кон API',
'right-writeapi'              => 'Можност за пишување на API',
'right-delete'                => 'Бришење страници',
'right-bigdelete'             => 'Бришење страници со долга историја',
'right-deleterevision'        => 'Бришење и враќање на конкретни ревизии на страници',
'right-deletedhistory'        => 'Прегледување на записи во историја на бришења, без придружниот текст',
'right-deletedtext'           => 'Прегледување на избришан текст и промени помеѓу избришани ревизии',
'right-browsearchive'         => 'Пребарување низ избришани страници',
'right-undelete'              => 'Обновување избришана страница',
'right-suppressrevision'      => 'Прегледување и враќање на ревизии сокриени од администратори',
'right-suppressionlog'        => 'Гледање на приватни дневници',
'right-block'                 => 'Оневозможување на останати корисници да уредуваат',
'right-blockemail'            => 'Оневозможување корисници да праќаат е-пошта',
'right-hideuser'              => 'Блокирање корисници, сокривање од јавноста',
'right-ipblock-exempt'        => 'Заобиколување на IP блокирања, авто-блокирања и блокирања на IP рангови',
'right-proxyunbannable'       => 'Заобиколување на автоматски блокирања на застапници',
'right-unblockself'           => 'Сопствено одблокирање',
'right-protect'               => 'Менување на нивоа на заштита и уредување на заштитени страници',
'right-editprotected'         => 'Уредување на заштитени страници (без каскадна заштита)',
'right-editinterface'         => 'Уредување на кориснички посредник',
'right-editusercssjs'         => 'Уредување на CSS и JS податотеки на други корисници',
'right-editusercss'           => 'Уредување на CSS податотеки на други корисници',
'right-edituserjs'            => 'Уредување на JS податотеки на други корисници',
'right-rollback'              => 'Брзо отстранување на уредувањата на последниот корисник кој уредувал одредена страница',
'right-markbotedits'          => 'Означување на вратени уредувања како ботовски уредувања',
'right-noratelimit'           => 'Без временски ограничувања на уредување',
'right-import'                => 'Увезување страници од други викија',
'right-importupload'          => 'Увезување страници од подигната податотека',
'right-patrol'                => 'Означување на уредувањата на другите како патролирани',
'right-autopatrol'            => 'Сопствените уредувања автоматски да се обележуваат како испатролирани',
'right-patrolmarks'           => 'Преглед на одбележаните патролирања на скорешните промени',
'right-unwatchedpages'        => 'Прегледување на список на ненабљудувани страници',
'right-trackback'             => 'Потврдување на враќање',
'right-mergehistory'          => 'Спојување на историите на страниците',
'right-userrights'            => 'Уредување на сите кориснички права',
'right-userrights-interwiki'  => 'Уредување на кориснички права на корисници на други викија',
'right-siteadmin'             => 'Заклучување и отклучување на базата на податоци',
'right-reset-passwords'       => 'Менување на лозинки на други корисници',
'right-override-export-depth' => 'Извезување на страници вклучувајќи поврзани страници со длабочина до 5',
'right-sendemail'             => 'Испраќање на е-пошта до други корисници',
'right-revisionmove'          => 'Преместување на ревизии',
'right-disableaccount'        => 'Оневозможи сметки',

# User rights log
'rightslog'      => 'Дневник на менувања на кориснички права',
'rightslogtext'  => 'Ова е дневник на промени на кориснички права.',
'rightslogentry' => 'Променето членство во група за $1 од $2 во $3',
'rightsnone'     => '(нема)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'читање на оваа страница',
'action-edit'                 => 'уредување на оваа страница',
'action-createpage'           => 'создавање страници',
'action-createtalk'           => 'создавање страници за разговор',
'action-createaccount'        => 'создај ја оваа корисничка сметка',
'action-minoredit'            => 'означување на ова уредување како ситно',
'action-move'                 => 'преместување на оваа страница',
'action-move-subpages'        => 'преместување на оваа страница и нејзините потстраници',
'action-move-rootuserpages'   => 'преместување на основна корисничка страница',
'action-movefile'             => 'премести ја оваа податотека',
'action-upload'               => 'подигни ја податотекава',
'action-reupload'             => 'заменување на оваа постоечка податотека',
'action-reupload-shared'      => 'заменување на оваа податотека на заедничко складиште',
'action-upload_by_url'        => 'подигни ја податотекава од URL-адреса',
'action-writeapi'             => 'употребете пишување на API',
'action-delete'               => 'бриши ја оваа страница',
'action-deleterevision'       => 'бриши ја оваа ревизија',
'action-deletedhistory'       => 'прегледај ја историјата на бришења за оваа страница',
'action-browsearchive'        => 'барање на избришани страници',
'action-undelete'             => 'обнови ја оваа страница',
'action-suppressrevision'     => 'прегледај ја и обновија оваа скриена ревизија',
'action-suppressionlog'       => 'преглед на овој приватен дневник',
'action-block'                => 'оневозможи го овој корисник да уредува',
'action-protect'              => 'промени го нивото на заштита на оваа страница',
'action-import'               => 'увези ја оваа страница од друго вики',
'action-importupload'         => 'увези ја оваа страница од подигната податотека',
'action-patrol'               => 'означи ги уредувањата на другите како проверени',
'action-autopatrol'           => 'вашите уредувања да се обележуват како испатролирани',
'action-unwatchedpages'       => 'преглед на список на ненабљудувани страници',
'action-trackback'            => 'потврда на враќање',
'action-mergehistory'         => 'спојување на историјата на оваа страница',
'action-userrights'           => 'уредување на сите кориснички права',
'action-userrights-interwiki' => 'уредување на кориснички права на корисници на други викија',
'action-siteadmin'            => 'заклучување или отклучување на базата на податоци',
'action-revisionmove'         => 'премести ревизии',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|промена|промени}}',
'recentchanges'                     => 'Скорешни промени',
'recentchanges-legend'              => 'Нагодувања за скорешни промени',
'recentchangestext'                 => 'На оваа страница ги следите скорешните промени на викито.',
'recentchanges-feed-description'    => 'Следење на најскорешните промени на викито во овие емитувања.',
'recentchanges-label-newpage'       => 'Ова уредување создаде нова страница',
'recentchanges-label-minor'         => 'Ова е ситно уредување',
'recentchanges-label-bot'           => 'Ова уредување е направено од бот',
'recentchanges-label-unpatrolled'   => 'Ова уредување сè уште не било патролирано',
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
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|корисник што набљудува|корисници што набљудуваат}}]',
'rc_categories'                     => 'Само од категории (одделувајќи со „|“)',
'rc_categories_any'                 => 'Било кои',
'rc-change-size'                    => '$1',
'newsectionsummary'                 => '/* $1 */ ново заглавие',
'rc-enhanced-expand'                => 'Прикажување на детали (потребно JavaScript)',
'rc-enhanced-hide'                  => 'Сокривање на детали',

# Recent changes linked
'recentchangeslinked'          => 'Поврзани промени',
'recentchangeslinked-feed'     => 'Поврзани промени',
'recentchangeslinked-toolbox'  => 'Поврзани промени',
'recentchangeslinked-title'    => 'Промени поврзани со „$1“',
'recentchangeslinked-backlink' => '← $1',
'recentchangeslinked-noresult' => 'Нема промени на поврзаните страници во зададениот период.',
'recentchangeslinked-summary'  => "Ова е список на промени направени на страниците поврзани преку назначената страница (или до членови на назначената категорија).
Страниците на [[Special:Watchlist|вашиот список на набљудувања]] се прикажани '''задебелено'''.",
'recentchangeslinked-page'     => 'Име на страница:',
'recentchangeslinked-to'       => 'Прикажи ги промените на страниците поврзани со дадената страница',

# Upload
'upload'                      => 'Подигање',
'uploadbtn'                   => 'Подигни',
'reuploaddesc'                => 'Прекини и назад кон образецот за подигање',
'upload-tryagain'             => 'Поднеси изменет опис на податотеката',
'uploadnologin'               => 'Не сте најавени',
'uploadnologintext'           => 'Морате да бидете [[Special:UserLogin|најавени]] за да можете да подигате податотеки.',
'upload_directory_missing'    => 'Папката за подигање на слики ($1) не постои и не може да биде создадена од опслужувачот.',
'upload_directory_read_only'  => 'Опслужувачот не може да запишува во именикот за подигање ($1).',
'uploaderror'                 => 'Грешка во подигањето',
'upload-recreate-warning'     => "'''Предупредување: Податотеката со тоа име е избришана или преместена.'''

Подолу е наведена дневничката евиденција на бришење и преместување за оваа страница:",
'uploadtext'                  => "Користете го долниот образец за подигање на податотеки.
За преглед или пребарување на претходно подигнати податотеки, погледнете ја [[Special:FileList|списокот на подигнати податотеки]]; повторните подигања се заведени во [[Special:Log/upload|дневникот на подигања]], а бришењата се заведуваат во [[Special:Log/delete|дневникот на бришења]].

За да поставите слика во страница, користете врска во еден од следниве облици:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Податотека.jpg]]</nowiki></tt>''' за верзија на сликата во целосна големина
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Податотека.png|200px|thumb|left|опис]]</nowiki></tt>''' за верзија на сликата со големина од 200 пиксели прикажана во соодветна кутија, со опис како што е наведено во '''опис'''
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Податотека.ogg]]</nowiki></tt>''' за директно поврзување со податотеката без нејзино прикажување",
'upload-permitted'            => 'Дозволени типови на податотеки: $1.',
'upload-preferred'            => 'Преферирани типови на податотеки: $1.',
'upload-prohibited'           => 'Забранети типови на податотеки: $1.',
'uploadlog'                   => 'дневник на подигања',
'uploadlogpage'               => 'Дневник на подигања',
'uploadlogpagetext'           => 'Наведен е список на најновите подигања на податотеки.
Погледнете ја [[Special:NewFiles|галеријата на нови податотеки]] за визуелен преглед.',
'filename'                    => 'Име на податотека',
'filedesc'                    => 'Опис',
'fileuploadsummary'           => 'Опис:',
'filereuploadsummary'         => 'Измени во податотеката:',
'filestatus'                  => 'Copyright статус:',
'filesource'                  => 'Извор:',
'uploadedfiles'               => 'Подигнати податотеки',
'ignorewarning'               => 'Занемари ги предупредувањата и зачувај ја податотеката',
'ignorewarnings'              => 'Занемари предупредувања',
'minlength1'                  => 'Името на податотеката мора да содржи барем една буква.',
'illegalfilename'             => 'Името на податотеката „$1“ содржи знаци што не се дозволени во наслови на страници.
Преименувајте ја подигнете ја повторно.',
'badfilename'                 => 'Името на податотеката е променето во „$1“.',
'filetype-mime-mismatch'      => 'Наставката на податотеката не е соодветна на MIME-типот.',
'filetype-badmime'            => 'Податотеките од MIME-тип „$1“ не се дозволени за подигање.',
'filetype-bad-ie-mime'        => 'Не може да се подигне оваа податотека бидејќи Internet Explorer би можел да го препознае како „$1“, што е оневозможен и потенцијално опасен тип на податотека.',
'filetype-unwanted-type'      => "'''„.$1“''' е непосакуван тип на податотека.
{{PLURAL:$3|Претпочитан тип на податотека е|Претпочитани типови на податотеки се}} $2.",
'filetype-banned-type'        => "'''„.$1“''' не е допуштен тип на податотека.
{{PLURAL:$3|Допуштен тип на податотека е|Допуштени типови на податотеки се}} $2.",
'filetype-missing'            => 'Податотеката нема наставка (пример „.jpg“).',
'empty-file'                  => 'Поднесената податотека е празна.',
'file-too-large'              => 'Поднесената податотека е преголема.',
'filename-tooshort'           => 'Името на податотеката е прекратко.',
'filetype-banned'             => 'Овој тип на податотека е забранет.',
'verification-error'          => 'Оваа податотека не ја помина потврдата успешно.',
'hookaborted'                 => 'Измените што сакате да ги направите се откажани од кука на додатокот.',
'illegal-filename'            => 'Такво име за податотеката на е дозволено.',
'overwrite'                   => 'Не е дозволено запишување врз постоечка податотека.',
'unknown-error'               => 'Се појави непозната грешка.',
'tmp-create-error'            => 'Не можев да создадам привремена податотека.',
'tmp-write-error'             => 'Грешка при запис на привремената податотека.',
'large-file'                  => 'Се препорачува податотеките да не бидат поголеми од $1; оваа податотека е $2.',
'largefileserver'             => 'Големината на податотеката е поголема од максимално дозволената големина.',
'emptyfile'                   => 'Податотеката што ја подигнавте изгледа дека е празна.
Ова може да е поради грешка во името на податотеката.
Ве молиме проверете дали навистина сакате да ја подигнете оваа податотека.',
'fileexists'                  => "Податотека со ова име веќе постои, проверете '''<tt>[[:$1]]</tt>''' ако не сте сигурни дали сакате да го промените.
[[$1|thumb]]",
'filepageexists'              => "Страницата за опис на оваа податотека е веќе создадена на '''<tt>[[:$1]]</tt>''', но не постои податотека со тоа име.
Описот кој го внесовте нема да стои на страницата за опис.
Доколку сакате описот да стои тука, ќе морате да го уредите рачно.
[[$1|thumb]]",
'fileexists-extension'        => "Податотека со слично име веќе постои: [[$2|thumb]]
* Име на податотека која се подигнува: '''<tt>[[:$1]]</tt>'''
* Име на постоечка податотека: '''<tt>[[:$2]]</tt>'''
Ве молиме изберете друго име за податотеката.",
'fileexists-thumbnail-yes'    => "Се чини дека податотеката е слика со намалена големина ''(минијатура)''. [[$1|thumb]]
Проверете ја податотеката '''<tt>[[:$1]]</tt>'''.
Ако податотеката која ја проверувате е истата слика во својата изворна големина тогаш не мора да ја подигате дополнително.",
'file-thumbnail-no'           => "Името на податотеката почнува со '''<tt>$1</tt>'''.
Изгледа дека е слика со намалена големина ''(мини, thumbnail)''.
Ако ја имате оваа слика во изворна големина, подигнете ја неја. Во спротивно сменете го името на податотеката.",
'fileexists-forbidden'        => 'Податотека со тоа име веќе постои и не може да биде заменета.
Ако и понатаму сакате да ја подигнете вашата податотеката, ве молиме вратете се назад и подигнете ја под друго име. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Податотека со ова име веќе постои во заедничката ризница.
Ако и понатаму сакате да ја подигнете податотеката, ве молиме вратете се и повторно подигнете ја податотеката со ново име. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Оваа податотека е дупликат со {{PLURAL:$1|следнава податотека|следниве податотеки}}:',
'file-deleted-duplicate'      => 'Податотека индентична со податотеката ([[$1]]) претходно била избришана. Треба да проверите во дневникот на бришења за оваа податотека пред повторно да ја подигнете.',
'uploadwarning'               => 'Предупредување при подигање',
'uploadwarning-text'          => 'Изменете го описот на податотеката подолу и обидете се повторно.',
'savefile'                    => 'Зачувај податотека',
'uploadedimage'               => 'подигнато „[[$1]]“',
'overwroteimage'              => 'подигнато нова верзија на „[[$1]]“',
'uploaddisabled'              => 'Забрана за подигања',
'copyuploaddisabled'          => 'Подигањето од URL е оневозможено.',
'uploadfromurl-queued'        => 'Вашето подигање е ставено во редица.',
'uploaddisabledtext'          => 'Подигањето на податотеки е оневозможено.',
'php-uploaddisabledtext'      => 'Подигањето на податотеки е оневозможено во PHP. 
Проверете го нагодувањето file_uploads.',
'uploadscripted'              => 'Податотеката содржи HTML или скриптен код што може да биде погрешно протолкуван од прелистувач.',
'uploadvirus'                 => 'Оваа податотека содржи вирус! Повеќе детали: $1',
'upload-source'               => 'Изворна податотека',
'sourcefilename'              => 'Изворно име на податотеката:',
'sourceurl'                   => 'Изворен URL:',
'destfilename'                => 'Целно име на податотеката:',
'upload-maxfilesize'          => 'Максимална големина на податотеката: $1',
'upload-description'          => 'Опис на податотека',
'upload-options'              => 'Нагодувања за подигање',
'watchthisupload'             => 'Набљудувај ја оваа податотека',
'filewasdeleted'              => 'Податотека со ова име претходно била подигната и потоа избришана.
Проверете во $1 пред да продолжите со повтроно подигање.',
'upload-wasdeleted'           => "'''Внимание: Подигате податотека што претходно била бришена.'''

Размислете дали е правилно да продолжите со подигање на оваа податотека.
Дневникот на бришење за оваа податотека може да го прегледате овде за ваша информација:",
'filename-bad-prefix'         => "Името на податотеката која ја подигате започнува со '''„$1“''', типично за неописните називи кои дигиталните фотоапарати автоматски ги создаваат, што впрочем е недоволно описно.
Ве молиме одберете подобро описно име за вашата податотека.",
'filename-prefix-blacklist'   => ' #<!-- Не менувај ја содржината на оваа редица, остави ја како што е --> <pre>
# Опис на ситнаксата:
#   * Било што по знакот „#“ па до крајот на линијата претставува коментар
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
'upload-success-subj'         => 'Подигањето е успешно',
'upload-success-msg'          => 'Подигањето од [$2] заврши успешно. Подигнатото можете да го видите тука: [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'Проблем при подигањето',
'upload-failure-msg'          => 'Се појави проблем со вашето подигање од [$2]:

$1',
'upload-warning-subj'         => 'Предупредување за подигањето',
'upload-warning-msg'          => 'Се јави проблем при подигањето од [$2]. Можете да се вратите на [[Special:Upload/stash/$1|образецот]] за да го поправите проблемот.',

'upload-proto-error'        => 'Погрешен протокол',
'upload-proto-error-text'   => 'За далечинско подигање потребно е URL-то да почнува со <code>http://</code> или <code>ftp://</code>.',
'upload-file-error'         => 'Внатрешна грешка',
'upload-file-error-text'    => 'Се случи внатрешна грешка при обидот за создавање на помошна податотека на опслужувачот.
Молиме контактирајте некој [[Special:ListUsers/sysop|администратор]].',
'upload-misc-error'         => 'Непозната грешка при подигањето',
'upload-misc-error-text'    => 'Се појави грешка при подигањето.
Проверете дали URL-адресата е правилна и достапна, па обидете се повторно.
Ако пак се појави проблем, обратете се кај некој [[Special:ListUsers/sysop|администратор]].',
'upload-too-many-redirects' => 'Оваа URL адреса содржеше премногу пренасочувања',
'upload-unknown-size'       => 'Непозната големина',
'upload-http-error'         => 'HTTP грешка: $1',

# img_auth script messages
'img-auth-accessdenied' => 'Оневозможен пристап',
'img-auth-nopathinfo'   => 'Недостасува PATH_INFO.
Вашиот опслужувач не е нагоден за да ја проследи оваа информација.
Можеби се заснова на CGI, и така не подржува img_auth.
Видете http://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir'     => 'Бараниот пат не води кон зададената папка за подигање.',
'img-auth-badtitle'     => 'Не може да се конструира важечки наслов од „$1“.',
'img-auth-nologinnWL'   => 'Не сте најавени и „$1“ не е на списокот на допуштени.',
'img-auth-nofile'       => 'Податотеката „$1“ не постои.',
'img-auth-isdir'        => 'Се обидувате да пристапите до именикот „$1“.
Допуштен е само податотечен пристап.',
'img-auth-streaming'    => 'Емитување „$1“.',
'img-auth-public'       => 'Функцијата на img_auth.php служи за излез на податотеки од приватни викија.
Ова вики е нагодено како јавно вики.
Од причини на оптимална сигурност, img_auth.php е оневозможен.',
'img-auth-noread'       => 'Корисникот нема пристап за читање на „$1“.',

# HTTP errors
'http-invalid-url'      => 'Неважечка URL: $1',
'http-invalid-scheme'   => 'Не се поддржани URL-адреси со шема „$1“',
'http-request-error'    => 'Неуспешно HTTP барање поради непозната грешка.',
'http-read-error'       => 'Грешка при читањето на HTTP.',
'http-timed-out'        => 'HTTP-барањето истече.',
'http-curl-error'       => 'Грешка при добивањето на URL: $1',
'http-host-unreachable' => 'Не можев да пристапам до URL-адресата',
'http-bad-status'       => 'Се појави проблем во текот на обработката на HTTP-барањето: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Не може да се пристапи до URL-то',
'upload-curl-error6-text'  => 'Наведеното URL не е достапно.
Проверете дали URL-то е исправно и достапно.',
'upload-curl-error28'      => 'Истече времето за подигање',
'upload-curl-error28-text' => 'Мрежното место е одговара пребавно.
Проверете дали е подигнато, почекајте малку и обидете се повторно.
Може да се обидете кога местото нема да биде толку оптоварено.',

'license'            => 'Лиценцирање:',
'license-header'     => 'Лиценцирање',
'nolicense'          => 'Нема',
'license-nopreview'  => '(Прегледот не е достапен)',
'upload_source_url'  => ' (важечка, јавно достапна URL-адреса)',
'upload_source_file' => '(податотека на вашиот сметач)',

# Special:ListFiles
'listfiles-summary'     => 'Оваа специјална страница ги покажува сите подигнати податотеки.
По основно последно подигнатите страници се прикажани на почеток од списокот.
Со клик на насловот на колоната може да се промени подредувањето.',
'listfiles_search_for'  => 'Побарај име на податотека:',
'imgfile'               => 'податотека',
'listfiles'             => 'Список на слики',
'listfiles_thumb'       => 'Минијатура',
'listfiles_date'        => 'Датум',
'listfiles_name'        => 'Име',
'listfiles_user'        => 'Корисник',
'listfiles_size'        => 'Големина (бајти)',
'listfiles_description' => 'Опис',
'listfiles_count'       => 'Верзии',

# File description page
'file-anchor-link'                  => 'Податотека',
'filehist'                          => 'Историја на податотеката',
'filehist-help'                     => 'Кликнете на датум/време за да ја видите податотеката како изгледала тогаш.',
'filehist-deleteall'                => 'избриши ги сите',
'filehist-deleteone'                => 'бриши',
'filehist-revert'                   => 'врати',
'filehist-current'                  => 'тековна',
'filehist-datetime'                 => 'Датум/Време',
'filehist-thumb'                    => 'Минијатура',
'filehist-thumbtext'                => 'Минијатура за верзија од $1',
'filehist-nothumb'                  => 'Нема минијатура',
'filehist-user'                     => 'Корисник',
'filehist-dimensions'               => 'Димензии',
'filehist-filesize'                 => 'Големина',
'filehist-comment'                  => 'Коментар',
'filehist-missing'                  => 'Непостоечка податотека',
'imagelinks'                        => 'Врски до податотекава',
'linkstoimage'                      => 'До оваа податотека има {{PLURAL:$1|врска од следнава страница|врски од следниве $1 страници}}:',
'linkstoimage-more'                 => 'Повеќе од {{PLURAL:$1|една страница е поврзана|$1 страници се поврзани}} со оваа податотека.
Следниов список {{PLURAL:$1|ја прикажува само првата поврзана страница|ги прикажува само првите $1 поврзани страници}} до оваа податотека.
Целосен список може да добиете [[Special:WhatLinksHere/$2|тука]].',
'nolinkstoimage'                    => 'Нема страници кои ја користат оваа податотека.',
'morelinkstoimage'                  => 'Погледни [[Special:WhatLinksHere/$1|други врски]] кон оваа податотека.',
'redirectstofile'                   => '{{PLURAL:$1|Следната податотека пренасочува|$1 Следните податотеки пренасочуваат}} кон оваа податотека:',
'duplicatesoffile'                  => '{{PLURAL:$1|Следната податотека е дупликат|$1 Следните податотеки се дупликати}} на оваа податотека ([[Special:FileDuplicateSearch/$2|повеќе информации]]):',
'sharedupload'                      => 'Оваа податотека е од $1 и може да се користи во други проекти.',
'sharedupload-desc-there'           => 'Оваа податотека е од $1 и може да се користи во други проекти.
Погледнете ја [$2 страницата за опис на податотеката] за повеќе информации.',
'sharedupload-desc-here'            => 'Оваа податотека е од $1 и може да се користи во други проекти.
Описот од нејзината [$2 страница за опис] е прикажан подолу.',
'filepage-nofile'                   => 'Не постои податотека со ова име.',
'filepage-nofile-link'              => 'Нема податотека со ова име, може да ја [$1 подигнете].',
'uploadnewversion-linktext'         => 'Подигни нова верзија на оваа податотека',
'shared-repo-from'                  => 'од $1',
'shared-repo'                       => 'заедничко складиште',
'shared-repo-name-wikimediacommons' => 'Заедничката Ризница',
'filepage.css'                      => '/* Тука поставените каскадни стилски страници (CSS) се вклучени во страницата за опис на податотеката, како и на клиентските викија */',

# File reversion
'filerevert'                => 'Врати $1',
'filerevert-backlink'       => '← $1',
'filerevert-legend'         => 'Врати податотека',
'filerevert-intro'          => "Ја враќате '''[[Media:$1|$1]]''' на [$4 верзијата од $3, $2].",
'filerevert-comment'        => 'Причина:',
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
'filedelete-comment'          => 'Причина:',
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
'filedelete-maintenance'      => 'Бришење и враќање на податотеки е привремено оневозможено поради одржување на базата на податоци.',

# MIME search
'mimesearch'         => 'Пребарување по MIME',
'mimesearch-summary' => 'Оваа страница овозможува филтрирање на податотеки врз основа на нивниот MIME-тип.
Влез: тип на содржина/поттип, на пр. <tt>image/jpeg</tt>.',
'mimetype'           => 'MIME-тип:',
'download'           => 'преземи',

# Unwatched pages
'unwatchedpages' => 'Ненабљудувани страници',

# List redirects
'listredirects' => 'Список на пренасочувања',

# Unused templates
'unusedtemplates'     => 'Неискористени шаблони',
'unusedtemplatestext' => 'Оваа страница ги прикажува сите страници во именскиот простор {{ns:template}} кои не се вклучени во друга страница.
Не заборавајте да ги проверите другите врски во шаблоните пред да ги избришете.',
'unusedtemplateswlh'  => 'други врски',

# Random page
'randompage'         => 'Случајна страница',
'randompage-nopages' => 'Нема страници во {{PLURAL:$2|следниот именски простор|следниве именски простори}}: $1.',

# Random redirect
'randomredirect'         => 'Случајно пренасочување',
'randomredirect-nopages' => 'Нема пренасочувања во именскиот простор „$1“.',

# Statistics
'statistics'                   => 'Статистики',
'statistics-header-pages'      => 'Статистики за страници',
'statistics-header-edits'      => 'Статистики на уредувања',
'statistics-header-views'      => 'Статистики на посети',
'statistics-header-users'      => 'Статистики за корисници',
'statistics-header-hooks'      => 'Други статистики',
'statistics-articles'          => 'Статии',
'statistics-pages'             => 'Страници',
'statistics-pages-desc'        => 'Сите страници на викито, вклучувајќи и страници за разговор, пренасочувања, и.т.н.',
'statistics-files'             => 'Подигнати податотеки',
'statistics-edits'             => 'Број на уредувања од започнувањето на {{SITENAME}}',
'statistics-edits-average'     => 'Просечен број на уредувања по страница',
'statistics-views-total'       => 'Вкупно посети',
'statistics-views-total-desc'  => 'Не се вклучени прегледувања на непостоечки и специјални страници',
'statistics-views-peredit'     => 'Посети по уредување',
'statistics-users'             => 'Регистрирани [[Special:ListUsers|корисници]]',
'statistics-users-active'      => 'Активни корисници',
'statistics-users-active-desc' => 'Корисници кои имаат извршено некое дејство {{PLURAL:$1|претходниот ден|во претходните $1 дена}}',
'statistics-mostpopular'       => 'Најпосетувани страници',

'disambiguations'      => 'Страници за појаснување',
'disambiguationspage'  => 'Template:Појаснување',
'disambiguations-text' => "Следните страници имаат врски кои водат до '''страница за појаснување'''.
Наместо тоа тие треба да водат до соодветната тема.<br />
Страница се третира како страница за појаснување ако таа го користи шаблонот кој е наведен [[MediaWiki:Disambiguationspage|тука]]",

'doubleredirects'            => 'Двојни пренасочувања',
'doubleredirectstext'        => 'Оваа страница ги прикажува пренасочувачките страници до други пренасочувачки страници.
Секој ред содржи врски кон првото и второто пренасочување, како и целта на второто пренасочување, кое обично ја посочува <i>вистинската</i> целна страница кон која првото пренасочување би требало да насочува.
<del>Пречкртаните</del> ставки треба да се разрешат.',
'double-redirect-fixed-move' => 'Страницата [[$1]] е преместена.
Сега пренасочува кон [[$2]]',
'double-redirect-fixer'      => 'Исправувач на пренасочувања',

'brokenredirects'        => 'Прекинати пренасочувања',
'brokenredirectstext'    => 'Следните пренасочувања водат до непостоечки страници.',
'brokenredirects-edit'   => 'уреди',
'brokenredirects-delete' => 'бриши',

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
'nimagelinks'             => 'Се користи на $1 {{PLURAL:$1|страница|страници}}',
'ntransclusions'          => 'се користи на $1 {{PLURAL:$1|страница|страници}}',
'specialpage-empty'       => 'Нема резултати од пребарувањето за овој извештај.',
'lonelypages'             => 'Осамени страници',
'lonelypagestext'         => 'Следните страници не се поврзани од или трансклудирани во други страници на {{SITENAME}}.',
'uncategorizedpages'      => 'Некатегоризирани страници',
'uncategorizedcategories' => 'Некатегоризирани категории',
'uncategorizedimages'     => 'Некатегоризирани податотеки',
'uncategorizedtemplates'  => 'Некатегоризирани преуредувања',
'unusedcategories'        => 'Неискористени категории',
'unusedimages'            => 'Неискористени слики',
'popularpages'            => 'Популарни страници',
'wantedcategories'        => 'Потребни категории',
'wantedpages'             => 'Потребни страници',
'wantedpages-badtitle'    => 'Невалиден наслов во резултатите: $1',
'wantedfiles'             => 'Потребни податотеки',
'wantedtemplates'         => 'Потребни шаблони',
'mostlinked'              => 'Најмногу врски до страници',
'mostlinkedcategories'    => 'Најмногу врски до категории',
'mostlinkedtemplates'     => 'Најмногу врски кон шаблони',
'mostcategories'          => 'Страници со најмногу категории',
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
'listusers'               => 'Список на корисници',
'listusers-editsonly'     => 'Прикажи само корисници кои уредувале',
'listusers-creationsort'  => 'Подреди по датум на создавање',
'usereditcount'           => '$1 {{PLURAL:$1|уредување|уредувања}}',
'usercreated'             => 'Создадено на $1 во $2',
'newpages'                => 'Нови страници',
'newpages-username'       => 'Корисничко име:',
'ancientpages'            => 'Најстари статии',
'move'                    => 'Премести',
'movethispage'            => 'Премести ја оваа страница',
'unusedimagestext'        => 'Следниве податотеки постојат, но не се вметнати во ниедна страница.
Имајте предвид дека други мрежни места може да ставаат врски до неа со директна URL-адреса, и затоа може да е наведена овде и покрај тоа што е во активна употреба.',
'unusedcategoriestext'    => 'Следните категории постојат и покрај тоа што ниедна статија и категорија не ги користи.',
'notargettitle'           => 'Нема цел',
'notargettext'            => 'Не одредивте целна страница или корисник на кој би се применила функцијата.',
'nopagetitle'             => 'Не постои таков наслов',
'nopagetext'              => 'Целната страница која ја наведовте не постои.',
'pager-newer-n'           => '{{PLURAL:$1|понова 1|понови $1}}',
'pager-older-n'           => '{{PLURAL:$1|постара 1|постари $1}}',
'suppress'                => 'Надзор',
'querypage-disabled'      => 'Оваа специјална страница е оневозможена за да не попречува на делотворноста.',

# Book sources
'booksources'               => 'Печатени извори',
'booksources-search-legend' => 'Пребарување на извори за книга',
'booksources-go'            => 'Оди',
'booksources-text'          => 'Ова е список на врски кон други мрежни места кои продаваат нови и користени книги, и тие може
да имаат повеќе информации за книгите што ги баравте:',
'booksources-invalid-isbn'  => 'Наведениот ISBN се чини неправилен. Проверете да не настанала некоја грешка при копирањето од изворот.',

# Special:Log
'specialloguserlabel'  => 'Корисник:',
'speciallogtitlelabel' => 'Наслов:',
'log'                  => 'Дневници',
'all-logs-page'        => 'Сите јавни дневници',
'alllogstext'          => 'Комбиниран приказ на сите расположиви дневници на {{SITENAME}}.
Можете да го ограничите прикажаното избирајќи тип на дневник, корисничко име (разликува големи и мали букви), или страница (разликува големи и мали букви).',
'logempty'             => 'Нема соодветни записи во дневникот.',
'log-title-wildcard'   => 'Пребарај наслови кои почнуваат со овој текст',

# Special:AllPages
'allpages'          => 'Сите страници',
'alphaindexline'    => 'од $1 до $2',
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
'allpagesbadtitle'  => 'Дадениот наслов е неважечки или има меѓујазичен или интервики префикс. Може да содржи повеќе знаци кои не смеат да се користат во наслови.',
'allpages-bad-ns'   => 'Википедија не содржи именски простор „$1“.',

# Special:Categories
'categories'                    => 'Категории',
'categoriespagetext'            => '{{PLURAL:$1|Следната категорија содржи|Следните категории содржат}} страници или мултимедијални податотеки.
[[Special:UnusedCategories|Неискористените категории]] не се прикажани овде.
Погледајте ги и [[Special:WantedCategories|потребните категории]].',
'categoriesfrom'                => 'Приказ на категории почнувајќи од:',
'special-categories-sort-count' => 'нумеричко подредување',
'special-categories-sort-abc'   => 'алфанумеричко подредување по',

# Special:DeletedContributions
'deletedcontributions'             => 'Избришани кориснички придонеси',
'deletedcontributions-title'       => 'Избришани кориснички придонеси',
'sp-deletedcontributions-contribs' => 'придонеси',

# Special:LinkSearch
'linksearch'       => 'Надворешни врски',
'linksearch-pat'   => 'Услов за пребарување:',
'linksearch-ns'    => 'Именски простор:',
'linksearch-ok'    => 'Барај',
'linksearch-text'  => 'Може да се користат џокери, како на пр. „*.wikipedia.org“.<br />
Поддржани протоколи: <tt>$1</tt>',
'linksearch-line'  => '$1 врска во $2',
'linksearch-error' => 'Џокер-знаците може да се користат само на почетокот во името на домаќинот.',

# Special:ListUsers
'listusersfrom'      => 'Прикажни корисници почнувајќи од:',
'listusers-submit'   => 'Прикажи',
'listusers-noresult' => 'Не е пронајден корисник.',
'listusers-blocked'  => '(блокиран)',

# Special:ActiveUsers
'activeusers'            => 'Список на активни корисници',
'activeusers-intro'      => 'Ова е список на корисници кои биле на некој начин активни во последните $1 {{PLURAL:$1|ден|дена}}.',
'activeusers-count'      => '$1 {{PLURAL:$1|уредување|уредувања}} {{PLURAL:$3|денес|во последните $3 дена}}',
'activeusers-from'       => 'Прикажува корисници кои почнуваат на:',
'activeusers-hidebots'   => 'Сокриј ботови',
'activeusers-hidesysops' => 'Сокриј систем-оператори',
'activeusers-noresult'   => 'Нема пронајдено корисници.',

# Special:Log/newusers
'newuserlogpage'              => 'Дневник на регистрирања на корисници',
'newuserlogpagetext'          => 'Ова е дневник на регистрирани корисници.',
'newuserlog-byemail'          => 'испратена лозинка по е-пошта',
'newuserlog-create-entry'     => 'Нов корисник',
'newuserlog-create2-entry'    => 'создадено нова корисничка сметка $1',
'newuserlog-autocreate-entry' => 'Автоматски создадена корисничка сметка',

# Special:ListGroupRights
'listgrouprights'                      => 'Права на кориснички групи',
'listgrouprights-summary'              => 'Следи список на кориснички групи утврдени на ова вики, заедно со нивните придружни права на пристап.
Можно е да има [[{{MediaWiki:Listgrouprights-helppage}}|дополнителни информации]] за некои права.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Доделено право</span>
* <span class="listgrouprights-revoked">Одземено право</span>',
'listgrouprights-group'                => 'Група',
'listgrouprights-rights'               => 'Права',
'listgrouprights-helppage'             => 'Help:Права на групи',
'listgrouprights-members'              => '(список на членови)',
'listgrouprights-addgroup'             => 'Може да додава {{PLURAL:$2|група|групи}}: $1',
'listgrouprights-removegroup'          => 'Може да брише {{PLURAL:$2|група|групи}}: $1',
'listgrouprights-addgroup-all'         => 'Може да ги додава сите групи',
'listgrouprights-removegroup-all'      => 'Може да ги брише сите групи',
'listgrouprights-addgroup-self'        => 'Може да додава {{PLURAL:$2|група|групи}} на сопствената корисничка сметка: $1',
'listgrouprights-removegroup-self'     => 'Може да брише {{PLURAL:$2|група|групи}} од сопствената корисничка сметка: $1',
'listgrouprights-addgroup-self-all'    => 'Може да ги додаде сите групи на сопствената корисничка сметка',
'listgrouprights-removegroup-self-all' => 'Може да ги избрише сите групи од сопствената корисничка сметка',

# E-mail user
'mailnologin'          => 'Нема адреса за праќање',
'mailnologintext'      => 'Мора да бидете [[Special:UserLogin|најавени]] и да имате важечка е-поштенска адреса во вашите [[Special:Preferences|нагодувања]] за да може да праќате е-пошта до други корисници.',
'emailuser'            => 'Испрати е-пошта до овој корисник',
'emailpage'            => 'Е-пошта',
'emailpagetext'        => 'Можете да го користите следниов образец за праќање на е-поштенска порака до овој корисник.
Е-поштенската адреса која ја имате наведено во [[Special:Preferences|вашите нагодувања]] ќе се прикаже во „Од“ полето на е-пораката, со што примачот ќе може да ви одговори директно вам.',
'usermailererror'      => 'Настана следната грешка при праќање е-пошта:',
'defemailsubject'      => '{{SITENAME}} е-пошта',
'usermaildisabled'     => 'Корисничката е-пошта е оневозможена',
'usermaildisabledtext' => 'Не можете да испратите е-порака до дрги корисници на ова вики',
'noemailtitle'         => 'Нема е-поштенска адреса',
'noemailtext'          => 'Овој корисник нема наведено важечка е-поштенска адреса.',
'nowikiemailtitle'     => 'Не е допуштено користење на е-пошта',
'nowikiemailtext'      => 'Овој корисник избрал да не прима е-пошта од други корисници.',
'email-legend'         => 'Праќање на е-пошта до друг {{SITENAME}} корисник',
'emailfrom'            => 'Од:',
'emailto'              => 'До:',
'emailsubject'         => 'Наслов:',
'emailmessage'         => 'Порака:',
'emailsend'            => 'Прати',
'emailccme'            => 'Испрати ми копија на пораката по е-пошта.',
'emailccsubject'       => 'Копија од вашата порака до $1: $2',
'emailsent'            => 'Е-поштата е пратена',
'emailsenttext'        => 'Вашата е-пошта е пратена.',
'emailuserfooter'      => 'Оваа е-порака беше пратена од $1 до $2 со помош на функцијата Е-пошта на {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'Оставете системска порака.',
'usermessage-editor'  => 'Системски гласник',

# Watchlist
'watchlist'            => 'мои набљудувања',
'mywatchlist'          => 'мои набљудувања',
'watchlistfor2'        => 'За $1 $2',
'nowatchlist'          => 'Немате ништо во списокот на набљудувања.',
'watchlistanontext'    => 'Се бара $1 за да можете да го прегледувате и уредувате списокот на набљудувања.',
'watchnologin'         => 'Не сте најавени',
'watchnologintext'     => 'Мора да бидете [[Special:UserLogin|најавени]] за да го менувате списокот на набљудувања.',
'addedwatch'           => 'Додадено во списокот на набљудувања',
'addedwatchtext'       => "Страницата „[[:$1]]“ е додадена во [[Special:Watchlist|списокот на набљудувања]].
Идните промени на оваа страница и поврзаните со неа страници за разговор ќе бидат прикажани овде, а страницата ќе се прикаже '''задебелена''' во [[Special:RecentChanges|списокот на скорешни промени]] за да можете полесно да ја препознаете.",
'removedwatch'         => 'Отстрането од списокот на набљудувања',
'removedwatchtext'     => 'Страницата „[[:$1]]“ е отстранета од [[Special:Watchlist|списокот на набљудувања]].',
'watch'                => 'Набљудувај',
'watchthispage'        => 'Набљудувај ја оваа страница',
'unwatch'              => 'Престани со набљудување',
'unwatchthispage'      => 'Престани набљудување',
'notanarticle'         => 'Не е статија',
'notvisiblerev'        => 'Ревизијата била избришана',
'watchnochange'        => 'Ниту едно од вашите набљудувања не било уредувано во прикажаниот период.',
'watchlist-details'    => '{{PLURAL:$1|$1 страница|$1 страници}} во вашиот список на набљудувања, не броејќи ги страниците за разговор.',
'wlheader-enotif'      => '* Известување по е-пошта е овозможено.',
'wlheader-showupdated' => "* Страници кои се променети од вашата последна посета се прикажани со '''задебелени''' букви",
'watchmethod-recent'   => 'Проверка на скорешните уредувања на набљудуваните страници',
'watchmethod-list'     => 'Проверерка на набљудуваните страници во скорешните уредувања',
'watchlistcontains'    => 'Вашиот список на набљудувања содржи $1 {{PLURAL:$1|страница|страници}}.',
'iteminvalidname'      => "Проблем со елементот '$1', неважечко име...",
'wlnote'               => "Подолу {{PLURAL:$1|е прикажана последната промена|се прикажани последните '''$1''' промени}} во {{PLURAL:$2|последниов час|последниве '''$2''' часа}}.",
'wlshowlast'           => 'Прикажи ги последните $1 часа, $2 дена, $3',
'watchlist-options'    => 'Поставки за список на набљудувања',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Набљудување...',
'unwatching' => 'Отстранувам од набљудувани...',

'enotif_mailer'                => '{{SITENAME}} Систем за известување',
'enotif_reset'                 => 'Означи ги сите страници како посетени',
'enotif_newpagetext'           => 'Ова е нова страница.',
'enotif_impersonal_salutation' => 'Википедија корисник',
'changed'                      => 'изменета',
'created'                      => 'создадена',
'enotif_subject'               => '{{SITENAME}} страницата $PAGETITLE беше $CHANGEDORCREATED од $PAGEEDITOR',
'enotif_lastvisited'           => 'Видете $1 за сите промени од вашата последна посета.',
'enotif_lastdiff'              => 'Видете $1 за да ја видите оваа промена.',
'enotif_anon_editor'           => 'анонимен корисник $1',
'enotif_body'                  => 'Почитуван(а) $WATCHINGUSERNAME,


На $PAGEEDITDATE е $CHANGEDORCREATED страницата „$PAGETITLE“ на проектот {{SITENAME}}. Измената ја изврши $PAGEEDITOR. Погледајте ја тековната верзија на $PAGETITLE_URL.

$NEWPAGE

Опис на уредувачот: $PAGESUMMARY $PAGEMINOREDIT

Конакт на уредувачот:
е-пошта: $PAGEEDITOR_EMAIL
вики: $PAGEEDITOR_WIKI

Повеќе нема да добивате известувања во случај на други понатамошни промени, освен ако не ја посетите оваа страница.
Можете и да ги поништите ознаките за известување за сите набљудувани страници на вашиот список на набљудувања.

             Системот за известување на {{SITENAME}}

--
За да ги промените нагодувањата на списокот на набљудувања, посетете ја страницата
{{fullurl:{{#special:Watchlist}}/edit}}

За да ја избришете страницата од списокот на набљудувања, посетете ја страницата
$UNWATCHURL

Повратни информации и помош:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Избриши страница',
'confirm'                => 'Потврди',
'excontent'              => "содржината беше: '$1'",
'excontentauthor'        => "содржината беше: '$1' (и единствениот автор беше '$2')",
'exbeforeblank'          => "содржината пред бришењето беше: '$1'",
'exblank'                => 'страницата беше празна',
'delete-confirm'         => 'Избриши „$1“',
'delete-backlink'        => '← $1',
'delete-legend'          => 'Бриши',
'historywarning'         => "'''Предупредување:''' Страницата којашто сакате да ја избришете има историја со околу $1 {{PLURAL:$1|ревизија|ревизии}}:",
'confirmdeletetext'      => 'На пат сте трајно да избришете страница заедно со нејзината историја.
Потврдете дека имате намера да го направите ова, дека ги разбирате последиците од тоа, дека го правите ова во согласност со [[{{MediaWiki:Policy-url}}|политиката]].',
'actioncomplete'         => 'Дејството е спроведено',
'actionfailed'           => 'Неуспешно дејство',
'deletedtext'            => '„<nowiki>$1</nowiki>“ е избришана. Евиденција на скорешните бришења ќе најдете на $2.',
'deletedarticle'         => 'ја избриша „[[$1]]“',
'suppressedarticle'      => 'ја скри „[[$1]]“',
'dellogpage'             => 'Дневник на бришења',
'dellogpagetext'         => 'Подолу е наведен список на најновите бришења.',
'deletionlog'            => 'дневник на бришењата',
'reverted'               => 'Вратено на претходната ревизија',
'deletecomment'          => 'Причина:',
'deleteotherreason'      => 'Друга/дополнителна причина:',
'deletereasonotherlist'  => 'Друга причина',
'deletereason-dropdown'  => '*Вообичаени причини за бришење
** На барање на авторот
** Прекршување на авторски права
** Вандализам',
'delete-edit-reasonlist' => 'Уредување на причини за бришење',
'delete-toobig'          => 'Оваа страница има долга историја на уредување, преку $1 {{PLURAL:$1|ревизија|ревизии}}.
Бришењето на ваквии страници е забрането со цел {{SITENAME}} да се заштити од оштетувања.',
'delete-warning-toobig'  => 'Оваа страница има долга историја на уредување, преку $1 {{PLURAL:$1|ревизија|ревизии}}.
Бришењето може да предизвика проблеми при работењето на базата на податоци на {{SITENAME}};
продолжете доколку сте сигруни дека треба тоа да го сторите.',

# Rollback
'rollback'          => 'Врати промени',
'rollback_short'    => 'Врати',
'rollbacklink'      => 'Врати',
'rollbackfailed'    => 'Неуспешно враќање',
'cantrollback'      => 'Уредувањето не може да се врати;
последниот уредник е воедно и единствениот автор на страницата.',
'alreadyrolled'     => 'Не може да се врати последното уредување од [[:$1]] на [[User:$2|$2]] ([[User talk:$2|Разговор]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]);
некој друг веќе ја уредил или ги вратил промените на страницата.

Последното уредување беше на [[User:$3|$3]] ([[User talk:$3|Разговор]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "Коментарот на уредувањето беше: „''$1''“.",
'revertpage'        => 'Отстрането уредувањето на [[Special:Contributions/$2|$2]] ([[User talk:$2|разговор]]), вратено на последната верзија на [[User:$1|$1]]',
'revertpage-nouser' => 'Вратени уредувања на (избришано корисничко име) на последната ревизија од [[User:$1|$1]]',
'rollback-success'  => 'Откажани уредувањата на $1; вратено на последната верзија на $2.',

# Edit tokens
'sessionfailure-title' => 'Сесијата не успеа',
'sessionfailure'       => 'Има проблем со вашата сесија;
оваа дејство е откажано како превентива против преземање сесии.
Притиснете го копчето „назад“ и повторно вчитајте ја страницата од која дојдовте и обидете се повторно.',

# Protect
'protectlogpage'              => 'Дневник на заштитувања',
'protectlogtext'              => 'Подолу е наведен список на отклучувања/заклучувања на страницата.
Погледете го [[Special:ProtectedPages|списокот на заштитени страници]].',
'protectedarticle'            => 'заштитена „[[$1]]“',
'modifiedarticleprotection'   => 'сменето ниво на заштита за „[[$1]]“',
'unprotectedarticle'          => 'отстранета заштита на "[[$1]]"',
'movedarticleprotection'      => 'преместени нагодувања за заштита од „[[$2]]“ во „[[$1]]“',
'protect-title'               => 'Смена на нивото назаштита на „$1“',
'prot_1movedto2'              => '[[$1]] преместена како [[$2]]',
'protect-legend'              => 'Потврдете ја заштитата',
'protectcomment'              => 'Причина:',
'protectexpiry'               => 'Истекува:',
'protect_expiry_invalid'      => 'Времето на истекување е неважечко.',
'protect_expiry_old'          => 'Времето на истекување е постаро од тековното време.',
'protect-unchain-permissions' => 'Отклучи дополнителни можности за заштита',
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
'protect-fallback'            => 'Барај дозвола од „$1“',
'protect-level-autoconfirmed' => 'Блокирај нови и нерегистрирани корисници',
'protect-level-sysop'         => 'Само администратори',
'protect-summary-cascade'     => 'каскада',
'protect-expiring'            => 'истекува на $1 (UTC)',
'protect-expiry-indefinite'   => 'бесконечно',
'protect-cascade'             => 'Заштитени страници вклучени во оваа страница (каскадна заштита)',
'protect-cantedit'            => 'Не можете да го промените нивото на заштита на оваа страница, бидејќи немате дозвола за тоа.',
'protect-othertime'           => 'Друго време:',
'protect-othertime-op'        => 'друго време',
'protect-existing-expiry'     => 'Постоечки рок на истекување: $3, $2',
'protect-otherreason'         => 'Друга/дополнителна причина:',
'protect-otherreason-op'      => 'Друга причина',
'protect-dropdown'            => '*Вообичаени причини за заштита
** Зачестен вандализам
** Зачестен спам
** Непродуктивна уредувачка војна
** Страница со зачестена посета',
'protect-edit-reasonlist'     => 'Уреди причини за заштита',
'protect-expiry-options'      => '1 час: 1 hour,1 ден:1 day,1 недела:1 week,2 недели:2 weeks,1 месец:1 month,3 месеци:3 months,6 месеци:6 months,1 година:1 year,бесконечно:infinite',
'restriction-type'            => 'Дозвола:',
'restriction-level'           => 'Ниво на заштита:',
'minimum-size'                => 'Минимална големина',
'maximum-size'                => 'Максимална големина',
'pagesize'                    => '(бајти)',

# Restrictions (nouns)
'restriction-edit'   => 'Уредување',
'restriction-move'   => 'Преместување',
'restriction-create' => 'Создај',
'restriction-upload' => 'Подигни',

# Restriction levels
'restriction-level-sysop'         => 'целосно заштитено',
'restriction-level-autoconfirmed' => 'делумно заштитено',
'restriction-level-all'           => 'сите нивоа',

# Undelete
'undelete'                     => 'Преглед на избришани страници',
'undeletepage'                 => 'Погледај и врати избришани страници',
'undeletepagetitle'            => "'''Во продолжение следат избришани ревизии на [[:$1|$1]]'''.",
'viewdeletedpage'              => 'Прегледај ги избришаните страници',
'undeletepagetext'             => '{{PLURAL:$1|Следната страница била избришана но сè уште е во архивот и може да биде вратена.|Следните $1 страници биле избришани но сè уште се во архивот и можат да бидат вратени.}}
Архивот може периодично да се чисти.',
'undelete-fieldset-title'      => 'Обнови ревизии',
'undeleteextrahelp'            => "За да вратите целосна историја на страница, отштиклирајте ги сите полиња и притиснете на '''''Врати'''''.
За да извршите делумно враќање, изберете ги полињата до соодветните ревизии за враќање и притиснете на '''''Врати'''''.
Со притискање на '''''Врати''''' го бришете коментарот и сите полиња за штиклирање.",
'undeleterevisions'            => '{{PLURAL:$1|$1 измена е архивирана|$1 измени се архивирани}}',
'undeletehistory'              => 'Ако ја обновите страницата, сите поправки ќе бидат вратени во историјата.
Ако нова страница со исто име е создадена по бришењето, обновените поправки ќе се појават во претходната историја.',
'undeleterevdel'               => 'Избришаното нема да биде вратено ако тоа значи дека со тоа најгорната страница или ревизијата на податотеката делумно ќе се избрише.
Во такви случаи, морате да ја отштиклирате или откриете (ако е скриена) најновата избришана ревизија.',
'undeletehistorynoadmin'       => 'Оваа статија беше избришана. Причината за бришењето е наведена подолу,
заедно со информации за корисникот кој ја уредувал страницата пред бришењето. Целиот текст
од избришаните верзии е достапен само за администраторите.',
'undelete-revision'            => 'Избришана ревизија на $1 (од $4, во $5) уредувач $3:',
'undeleterevision-missing'     => 'Грешна или непостоечка ревизија.
Можеби имате лоша врска, ревизијата била обновена или избришана од архивата.',
'undelete-nodiff'              => 'Не постои постара ревизија.',
'undeletebtn'                  => 'Обнови',
'undeletelink'                 => 'погледај/врати',
'undeleteviewlink'             => 'преглед',
'undeletereset'                => 'Врати',
'undeleteinvert'               => 'Обратен избор',
'undeletecomment'              => 'Причина:',
'undeletedarticle'             => 'обновена „[[$1]]“',
'undeletedrevisions'           => '{{PLURAL:$1|1 измена е обновена|$1 измени се обновени}}',
'undeletedrevisions-files'     => '{{PLURAL:$1|1 измена|$1 измени}} и {{PLURAL:$2|1 податотека|$2 податотеки}} се вратени',
'undeletedfiles'               => '{{PLURAL:$1|1 податотека е вратена|$1 податотеки се вратени}}',
'cannotundelete'               => 'Враќањето не успеа, некој друг можеби ја вратил страницата претходно.',
'undeletedpage'                => "'''$1 беше обновена'''

Погледнете го [[Special:Log/delete|дневникот на бришења]] за попис на претходни бришења и обновувања.",
'undelete-header'              => 'Списокот на неодамна избришани страници ќе го најдете на [[Special:Log/delete|дневникот на бришења]].',
'undelete-search-box'          => 'Пребарување на ибришани страници',
'undelete-search-prefix'       => 'Прикажи страници кои почнуваат со:',
'undelete-search-submit'       => 'Барај',
'undelete-no-results'          => 'Не се пронајдени соодветни страници во дневникот на бришења.',
'undelete-filename-mismatch'   => 'Не може да се обнови бараната ревизија на податотеката од $1: името не соодветствува',
'undelete-bad-store-key'       => 'Не може да се обнови ревизија на податотека до $1: податотеката ја снемало пред да биде избришана.',
'undelete-cleanup-error'       => 'Грешка при бришење на некористената архивска податотека „$1“.',
'undelete-missing-filearchive' => 'Не можеше да се врати ID $1 because од податочниот архив бидејќи тој не е во базата на податоци.
Можеби веќе бил избришан.',
'undelete-error-short'         => 'Грешка при обновување на податотека: $1',
'undelete-error-long'          => 'Се појавија грешки при обновувањето на податотеката:

$1',
'undelete-show-file-confirm'   => 'Дали навистина сакате да ја погледнете избришаната ревизија на податотеката „<nowiki>$1</nowiki>“ од $2 на $3?',
'undelete-show-file-submit'    => 'Да',

# Namespace form on various pages
'namespace'      => 'Именски простор:',
'invert'         => 'Обратен избор',
'blanknamespace' => '(Главен)',

# Contributions
'contributions'       => 'Кориснички придонеси',
'contributions-title' => 'Кориснички придонеси за $1',
'mycontris'           => 'мои придонеси',
'contribsub2'         => 'За $1 ($2)',
'nocontribs'          => 'Не се пронајдени промени што одговараат на овој критериум.',
'uctop'               => ' (врв)',
'month'               => 'Од месец (и порано):',
'year'                => 'Од година (и порано):',

'sp-contributions-newbies'             => 'Прикажи придонеси само на нови корисници',
'sp-contributions-newbies-sub'         => 'За нови кориснички сметки',
'sp-contributions-newbies-title'       => 'Придонеси на нови корисници',
'sp-contributions-blocklog'            => 'Дневник на блокирања',
'sp-contributions-deleted'             => 'избришани кориснички придонеси',
'sp-contributions-uploads'             => 'подигања',
'sp-contributions-logs'                => 'дневници',
'sp-contributions-talk'                => 'разговор',
'sp-contributions-userrights'          => 'раководење со кориснички права',
'sp-contributions-blocked-notice'      => 'Овој корисник е блокиран. Последните ставки во дневникот на блокирања, за ваша информација се дадени подолу:',
'sp-contributions-blocked-notice-anon' => 'Оваа IP-адреса е моментално блокирана.
Подолу е наведен најновиот дневнички запис на блокирање:',
'sp-contributions-search'              => 'Пребарување на придонеси',
'sp-contributions-username'            => 'IP-адреса или корисничко име:',
'sp-contributions-toponly'             => 'Прикажувај само последни ревизии',
'sp-contributions-submit'              => 'Пребарај',

# What links here
'whatlinkshere'            => 'Што води овде',
'whatlinkshere-title'      => 'Страници со врски што водат до „$1“',
'whatlinkshere-page'       => 'Страница:',
'whatlinkshere-backlink'   => '← $1',
'linkshere'                => "Следните страници имаат врска до '''[[:$1]]''':",
'nolinkshere'              => "Нема страници со врска кон '''[[:$1]]'''.",
'nolinkshere-ns'           => "Нема страници со врска кон '''[[:$1]]''' во избраниот именски простор.",
'isredirect'               => 'пренасочувачка страница',
'istemplate'               => 'вклучување',
'isimage'                  => 'врска за графиконот',
'whatlinkshere-prev'       => '{{PLURAL:$1|претходна|претходни $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|следна|следни $1}}',
'whatlinkshere-links'      => '← врски',
'whatlinkshere-hideredirs' => '$1 пренасочувања',
'whatlinkshere-hidetrans'  => '$1 превметнувања',
'whatlinkshere-hidelinks'  => '$1 врски',
'whatlinkshere-hideimages' => '$1 врски кон слика',
'whatlinkshere-filters'    => 'Филтри',

# Block/unblock
'blockip'                         => 'Блокирај корисник',
'blockip-title'                   => 'Блокирај корисник',
'blockip-legend'                  => 'Блокирај корисник',
'blockiptext'                     => 'Користете го долниот образец за да го забраните пристапот за пишување од одредена IP-адреса или корисничко име.
Ова единствено треба да се прави за да се спречи вандализам, во согласност со [[{{MediaWiki:Policy-url}}|правилата на Википедија]].
Изберете конкретна причина подолу (пример: цитирање на конкретни страници што биле вандализирани).',
'ipaddress'                       => 'IP-адреса',
'ipadressorusername'              => 'IP-адреса или корисничко име:',
'ipbexpiry'                       => 'Истек на рокот:',
'ipbreason'                       => 'Причина:',
'ipbreasonotherlist'              => 'Друга причина',
'ipbreason-dropdown'              => '*Вообичаени причини за блокирање
** Вметнување лажни информации
** Бришење на содржини од страниците
** Спам-врски кон надворешни мрежни места
** Вметнување глупости во страниците
** Непристојно однесување
** Навредување на други корисници
** Неприфатливо корисничко име',
'ipbanononly'                     => 'Блокирај само анонимни корисници',
'ipbcreateaccount'                => 'Оневозможи создавање кориснички сметки',
'ipbemailban'                     => 'Оневозможи го корисникот да праќа е-пошта',
'ipbenableautoblock'              => 'Автоматски блокирај ја последната IP-адреса што ја користел корисникот и сите понатамошни адреси од кои ќе се обиде да уредува',
'ipbsubmit'                       => 'Блокирај го овој корисник',
'ipbother'                        => 'Друг рок:',
'ipboptions'                      => '2 часа:2 hours,1 ден:1 day,3 дена:3 days,1 седмица:1 week,2 седмици:2 weeks,1 месец:1 month,3 месеци:3 months,6 месеци:6 months,1 година:1 year,бесконечно:infinite',
'ipbotheroption'                  => 'друго',
'ipbotherreason'                  => 'Друга, дополнителна причина:',
'ipbhidename'                     => 'Сокриј корисничко име во уредувања и списоци',
'ipbwatchuser'                    => 'Набљудувај ја корисничката страница и страницата за разговор на овој корисник',
'ipballowusertalk'                => 'Овозможи овој корисник да ја уредува својата страница за разговор додека е блокиран',
'ipb-change-block'                => 'Повторно блокирај го корисникот со овие нагодувања',
'badipaddress'                    => 'Неважечка IP-адреса',
'blockipsuccesssub'               => 'Успешно блокирање',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] беше блокиран(а).<br />
Видете [[Special:IPBlockList|список на блокирани IP-адреси]].',
'ipb-edit-dropdown'               => 'Наведи причина за блокирање',
'ipb-unblock-addr'                => 'Одблокирај го $1',
'ipb-unblock'                     => 'Одблокирај корисник или IP-адреса',
'ipb-blocklist'                   => 'Преглед на активни блокирања',
'ipb-blocklist-contribs'          => 'Придонеси на $1',
'unblockip'                       => 'Деблокирај корисник',
'unblockiptext'                   => 'Користете го долниот образец да го вратите правото на пишување на претходно блокирана IP-адреса или корисничко име.',
'ipusubmit'                       => 'Избриши го ова блокирање',
'unblocked'                       => '[[User:$1|$1]] беше деблокиран',
'unblocked-id'                    => 'Блокирањето $1 беше отстрането',
'ipblocklist'                     => 'Блокирани IP-адреси и кориснички имиња',
'ipblocklist-legend'              => 'Најди блокиран корисник',
'ipblocklist-username'            => 'Корисничко име или IP-адреса:',
'ipblocklist-sh-userblocks'       => '$1 блокирани кориснички сметки',
'ipblocklist-sh-tempblocks'       => '$1 привремени блокирања',
'ipblocklist-sh-addressblocks'    => '$1 блокирани поединечни IP-адреси',
'ipblocklist-submit'              => 'Пребарај',
'ipblocklist-localblock'          => 'Локален блок',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|друг блок|други блокови}}',
'blocklistline'                   => '$1, $2 го блокираше $3 ($4)',
'infiniteblock'                   => 'бесконечно',
'expiringblock'                   => 'истекува на $1 во $2',
'anononlyblock'                   => 'само анон.',
'noautoblockblock'                => 'оневозможено автоблокирање',
'createaccountblock'              => 'блокирано создавање кориснички сметки',
'emailblock'                      => 'блокирана е-пошта',
'blocklist-nousertalk'            => 'без можност за уредување на својата страница за разговор',
'ipblocklist-empty'               => 'Списокот на блокирања е празен.',
'ipblocklist-no-results'          => 'Бараната IP-адреса или корисничка сметка не е блокирана.',
'blocklink'                       => 'блокирај',
'unblocklink'                     => 'одблокирај',
'change-blocklink'                => 'промена на блок',
'contribslink'                    => 'придонеси',
'autoblocker'                     => 'Автоматски сте блокирани бидејќи вашата IP-адреса била скоро користена од „[[User:$1|$1]]“.
Причината за блокирање на $1 била: „$2“',
'blocklogpage'                    => 'Дневник на блокирања',
'blocklog-showlog'                => 'Овој корисник бил претходно блокиран.
Дневникот на блокирања, за ваша информација е прикажан подолу:',
'blocklog-showsuppresslog'        => 'Овој корисник бил претходно блокиран и сокриен.
Дневникот на ограничувања, за ваша информација е прикажан подолу:',
'blocklogentry'                   => 'блокирано [[$1]] со рок на истекување до $2, $3',
'reblock-logentry'                => 'променети нагодувања за блокирање на [[$1]] со рок на истекување од $2 $3',
'blocklogtext'                    => 'Ова е дневник на блокирање и одблокирање на кориснци.
Автоматски блокираните IP-адреси не се наведени.
Видете го [[Special:IPBlockList|списокот на блокирани IP-адреси]].',
'unblocklogentry'                 => 'го одблокира „$1“',
'block-log-flags-anononly'        => 'само анонимни корисници',
'block-log-flags-nocreate'        => 'оневозможено создавање кориснички сметки',
'block-log-flags-noautoblock'     => 'автоблокирање исклучено',
'block-log-flags-noemail'         => 'блокирана е-поштенска адреса',
'block-log-flags-nousertalk'      => 'без можност за уредување на својата страница за разговор',
'block-log-flags-angry-autoblock' => 'овозможено проширено автоблокирање',
'block-log-flags-hiddenname'      => 'сокриено корисничко име',
'range_block_disabled'            => 'Администраторската можност да блокираат IP групи е исклучена.',
'ipb_expiry_invalid'              => 'Погрешен рок на истекување.',
'ipb_expiry_temp'                 => 'Скриените блокирања на корисникот мора да бидат перманентни.',
'ipb_hide_invalid'                => 'Оваа сметка не може да се потисне; можеби има премногу уредувања.',
'ipb_already_blocked'             => '„$1“ е веќе блокиран',
'ipb-needreblock'                 => '== Веќе блокиран ==
$1 е веќе блокиран. Дали сакате да направите промена?',
'ipb-otherblocks-header'          => '{{PLURAL:$1|Друго блокирање|Други блокирања}}',
'ipb_cant_unblock'                => 'Грешка: Блокирањето $1 не постои.
Можеби веќе е одблокиран.',
'ipb_blocked_as_range'            => 'Грешка: IP-адресата $1 не е директно блокирана и не може да се деблокира.
Таа е блокирана како дел од блокот адреси $2, кој не може да се деблокира.',
'ip_range_invalid'                => 'Неважечки IP дијапазон на адреси.',
'ip_range_toolarge'               => 'Не се дозволени опсежни блокирања поголеми од /$1.',
'blockme'                         => 'Блокирај ме',
'proxyblocker'                    => 'Блокер на застапници (proxy)',
'proxyblocker-disabled'           => 'Оваа функција е оневозможена.',
'proxyblockreason'                => 'Вашата IP-адреса е блокирана бидејќи претставува отворен застапник (proxy).
Ве молиме контактирајте со вашиот доставувач на Интернет услуги или техничката поддршка и информирајте ги за овој сериозен безбедносен проблем.',
'proxyblocksuccess'               => 'Готово.',
'sorbs'                           => 'DNSBL',
'sorbsreason'                     => 'Вашата IP-адреса е запишана како отворен застапник (proxy) во DNSBL кој го користи {{SITENAME}}..',
'sorbs_create_account_reason'     => 'Вашата IP-адреса е наведена како отворен застапникот (proxy) во DNSBL користена од {{SITENAME}}.
Не можете да создадете корисничка сметка.',
'cant-block-while-blocked'        => 'Не можете да блокирате други корисници додека и вие самите сте блокирани.',
'cant-see-hidden-user'            => 'Корисникот кој се обидувате да го блокирате е веќе блокиран и сокриен. Бидејќи вие немате права за сокривање на корисник, не можете да ги видите или уредувате корисничките блокирања.',
'ipbblocked'                      => 'Не можете да блокирате или одблокирате други корисници бидејќи и самите сте блокирани',
'ipbnounblockself'                => 'Не е дозволено да се одблокирате самите себеси',

# Developer tools
'lockdb'              => 'Заклучи база на податоци',
'unlockdb'            => 'Отклучи база на податоци',
'lockdbtext'          => 'Заклучувањето на базата на податоци ќе им ја укине можноста на сите корисници да уредуваат страници, да ги менуваат нивните нагодувања, да ги уредуваат нивните списоци на набљудувања и сето останато што бара промени во базата.
Потврдете дека ова е вашата намера и дека ќе ја отклучите базата кога ќе ја завршите работата околу нејзиното одржување.',
'unlockdbtext'        => 'Отклучувањето на базата ќе им ја врати можноста на сите корисници да уредуваат страници да ги менуваат нивните нагодувања, да ги уредуваат нивните списоци на набљудувања и сето останато што бара промени во базата.
Потврдете дека ова е вашата намера.',
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
За да ја заклучите или отклучите базата, податотеката мора да биде достапна за пишување од страна на семрежниот опслужувач.',
'databasenotlocked'   => 'Базата не е заклучена.',

# Move page
'move-page'                    => 'Премести $1',
'move-page-backlink'           => '← $1',
'move-page-legend'             => 'Премести страница',
'movepagetext'                 => "Со користењето на овој образец можете да преименувате страница, преместувајќи ја целата нејзина историја под ново име.
Стариот наслов ќе стане страница за пренасочување кон новиот наслов.
Автоматски можете да ги подновите пренасочувањата кои покажуваат кон првобитниот наслов.
Ако не изберете автоматско подновување, проверете на [[Special:DoubleRedirects|двојни]] или [[Special:BrokenRedirects|прекинати пренасочувања]].
На вас е одговорноста да се осигурате дека врските ќе продолжат да насочуваат таму за каде се предвидени.

Имајте предвид дека страницата '''НЕМА''' да биде преместена ако веќе постои страница со новиот наслов, освен ако е празна или ако е пренасочување и нема историја на минати уредувања. Тоа значи дека можете да ја преименувате страницата како што била претходно доколку сте направиле грешка без да ја прекриете постоечката страница.

'''ПРЕДУПРЕДУВАЊЕ!'''
Ова може да биде драстична и неочекувана промена за популарна страница;
осигурајте се дека сте ги разбрале последиците од ова пред да продолжите.",
'movepagetext-noredirectfixer' => "Со користењето на овој образец можете да преименувате страница, преместувајќи ја целата нејзина историја под ново име.
Стариот наслов ќе стане страница за пренасочување кон новиот наслов.
Автоматски можете да ги подновите пренасочувањата кои покажуваат кон првобитниот наслов.
Не заборавајте да проверите [[Special:DoubleRedirects|двојни]] и [[Special:BrokenRedirects|прекинати пренасочувања]].
На вас е одговорноста да се осигурате дека врските ќе продолжат да насочуваат таму за каде се предвидени.

Имајте предвид дека страницата '''НЕМА''' да биде преместена ако веќе постои страница со новиот наслов, освен ако е празна или ако е пренасочување и нема историја на минати уредувања. Тоа значи дека можете да ја преименувате страницата како што била претходно доколку сте направиле грешка без да ја прекриете постоечката страница.

'''ПРЕДУПРЕДУВАЊЕ!'''
Ова може да биде драстична и неочекувана промена за популарна страница;
осигурајте се дека сте ги разбрале последиците од ова пред да продолжите.",
'movepagetalktext'             => 'Соодветната страница за разговор, доколку постои, ќе биде автоматски преместена заедно со неа, освен ако:
*Веќе постои страница за разговор за новиот наслов, или
*Долното поле е неозначено.

Во овие случаи, ќе треба рачно да се премести или спои ако има потреба.',
'movearticle'                  => 'Премести страница:',
'moveuserpage-warning'         => "'''Предупредување:''' На пат сте да преместите корисничка страница. Имајте предвид дека само страницата ќе биде преместена, а самиот корисник ''нема'' да биде преименуван.",
'movenologin'                  => 'Не сте најавени',
'movenologintext'              => 'Мора да бидете регистриран корисник и [[Special:UserLogin|најавен]] за да преместите страница.',
'movenotallowed'               => 'Немате доволно привилегии за преместување на страници.',
'movenotallowedfile'           => 'Немате дозвола за преместување податотеки.',
'cant-move-user-page'          => 'Немате дозвола за преместување на основни кориснички страници (освен потстраници).',
'cant-move-to-user-page'       => 'Немате дозвола за преместување на страница во ваша корисничка страница (освен во корисничка потстраница)',
'newtitle'                     => 'Кон новиот наслов:',
'move-watch'                   => 'Набљудувај ја оваа страница',
'movepagebtn'                  => 'Премести страница',
'pagemovedsub'                 => 'Успешно преместување',
'movepage-moved'               => "'''„$1“ е преместена под името „$2“'''",
'movepage-moved-redirect'      => 'Беше направено пренасочување.',
'movepage-moved-noredirect'    => 'Создавањето на пренасочување е оневозможено.',
'articleexists'                => 'Веќе постои страница со тоа име, или името што го одбравте е неважечко.
Изберете друго име.',
'cantmove-titleprotected'      => 'Не може да ја преместите страницата на тоа место бидејќи саканиот наслов е заштитен од создавање.',
'talkexists'                   => "'''Самата страница е успешно преместена, но страницата за разговор не може да се премести бидејќи веќе постои страница со таков наслов.
Спојте ги рачно.'''",
'movedto'                      => 'преместена како',
'movetalk'                     => 'Премести ја и страницата за разговор, ако е возможно.',
'move-subpages'                => 'Премести потстраници (највеќе до $1)',
'move-talk-subpages'           => 'Премести потстраници на страници за разговор (највеќе до $1)',
'movepage-page-exists'         => 'Страницата $1 веќе постои и не може автоматски да биде заменета.',
'movepage-page-moved'          => 'Страницата $1 е преместена на $2.',
'movepage-page-unmoved'        => 'Страницата $1 не може да биде преместена во $2.',
'movepage-max-pages'           => 'Максимално $1 {{PLURAL:$1|страница|страници}} беа преместени, повеќе не може да бидат автоматски преместени.',
'1movedto2'                    => '[[$1]] преместена како [[$2]]',
'1movedto2_redir'              => '[[$1]] преместена како [[$2]] преку пренасочување',
'move-redirect-suppressed'     => 'исклучено пренасочување',
'movelogpage'                  => 'Дневник на преместувања',
'movelogpagetext'              => 'Подолу е наведен список на преместени страници.',
'movesubpage'                  => '{{PLURAL:$1|Потстраница|Потстраници}}',
'movesubpagetext'              => 'Оваа страница има $1 {{PLURAL:$1|потстраница прикажана|потстраници прикажани}} подолу.',
'movenosubpage'                => 'Оваа страница нема потстраници.',
'movereason'                   => 'Причина:',
'revertmove'                   => 'врати',
'delete_and_move'              => 'Избриши и премести',
'delete_and_move_text'         => '==Потребно бришење==
Целната статија „[[:$1]]“ веќе постои.
Дали сакате да ја избришете за да ослободите место за преместувањето?',
'delete_and_move_confirm'      => 'Да, избриши ја страницата',
'delete_and_move_reason'       => 'Избришано за да се ослободи место за преместувањето',
'selfmove'                     => 'Страницата не може да биде преместена бидејќи целниот наслов се совпаѓа со првобитниот наслов;
не може да се премести страница во самата себе.',
'immobile-source-namespace'    => 'Не може да се преместуваат страници во именскиот простор „$1“',
'immobile-target-namespace'    => 'Не може да се преместуваат страници во именскиот простор „$1“',
'immobile-target-namespace-iw' => 'Интервики врска не е важечка цел за преместување на страница.',
'immobile-source-page'         => 'Оваа страница не може да се преместува.',
'immobile-target-page'         => 'Не може да се премести под бараниот наслов.',
'imagenocrossnamespace'        => 'Не може да се премести податотека во неподатотечен именски простор',
'nonfile-cannot-move-to-file'  => 'Не можам да преместам неподатотека во податотечен именски простор',
'imagetypemismatch'            => 'Новата наставка на податотеката не соодветствува на нејзиниот тип',
'imageinvalidfilename'         => 'Целниот наслов на податотеката е невалиден',
'fix-double-redirects'         => 'Ажурирајте ги сите пренасочувања кои покажуваат на првобитниот наслов',
'move-leave-redirect'          => 'Направи пренасочување',
'protectedpagemovewarning'     => "'''Предупредување:'''  Оваа страница е заклучена, така што само корисници со администраторски привилегии може да ја преместат.
За ваша информација, последната ставка во дневникот на промени е прикажана подолу:",
'semiprotectedpagemovewarning' => "'''Напомена:'''  Оваа страница е заклучена, така што само регистрирани корисници може да ја преместат.
За ваша информација, последната ставка во дневникот на промени е прикажана подолу:",
'move-over-sharedrepo'         => '== Податотеката постои ==
[[:$1]] постои на заедничко складиште. Ако податотеката ја преместите на овој наслов, тоа ќе ја потисне заедничката податотека.',
'file-exists-sharedrepo'       => 'Одбраното име на податотеката веќе се користи на заедничко складиште.
Одберете друго име.',

# Export
'export'            => 'Извоз на страници',
'exporttext'        => 'Можете да го извезете текстот и историјата на уредување на избрана страница или група на страници во XML формат.
Овие податоци може да бидат вчитани на некое друго вики кое се користи со МедијаВики преку [[Special:Import|увезување на страница]].

За извезување на страници, внесете ги насловите во полето прикажано подолу, еден наслов на статија во ред, потоа изберете дали сакате да ја извезете само последната ревизија или и сите постари ревизии.

Ако ја сакате само тековната верзија, би можеле да искористите врска од видот [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] за страницата „[[{{MediaWiki:Mainpage}}]]“.',
'exportcuronly'     => 'Вклучи ја само тековната ревизија, а не сета историја',
'exportnohistory'   => "----
'''Напомена:''' извезувањето на целата историја на страниците преку овој образец е оневозможено од перформансни причини.",
'export-submit'     => 'Извези',
'export-addcattext' => 'Додај страници од категорија:',
'export-addcat'     => 'Додај',
'export-addnstext'  => 'Додај страници од именски простор:',
'export-addns'      => 'Додај',
'export-download'   => 'Зачувај како податотека',
'export-templates'  => 'Вклучи и шаблони',
'export-pagelinks'  => 'Вклучи поврзани страници до длабочина од:',

# Namespace 8 related
'allmessages'                   => 'Системски пораки',
'allmessagesname'               => 'Име',
'allmessagesdefault'            => 'Текст по основно',
'allmessagescurrent'            => 'Сегашен текст',
'allmessagestext'               => 'Ова е список на системските пораки расположиви за именскиот простор „МедијаВики“.
Одете на [http://www.mediawiki.org/wiki/Localisation Локализација на МедијаВики] и [http://translatewiki.net translatewiki.net] ако сакате да придонесете кон општата локализација на МедијаВики.',
'allmessagesnotsupportedDB'     => "Оваа страница не може да се користи бидејќи '''\$wgUseDatabaseMessages''' е исклучено.",
'allmessages-filter-legend'     => 'Филтер',
'allmessages-filter'            => 'Филтрирај по состојба на прилагодувањето:',
'allmessages-filter-unmodified' => 'Непроменети',
'allmessages-filter-all'        => 'Сите',
'allmessages-filter-modified'   => 'Изменети',
'allmessages-prefix'            => 'Филтер по префикс:',
'allmessages-language'          => 'Јазик:',
'allmessages-filter-submit'     => 'Оди',

# Thumbnails
'thumbnail-more'           => 'Зголеми',
'filemissing'              => 'Недостасува податотека',
'thumbnail_error'          => 'Грешка при создавање минијатурата: $1',
'djvu_page_error'          => 'Недостапна DjVu страница',
'djvu_no_xml'              => 'Не е можно да се излачи XML за DjVu податотеки',
'thumbnail_invalid_params' => 'Погрешни параметри за минијатурата',
'thumbnail_dest_directory' => 'Целниот именик не може да се создаде',
'thumbnail_image-type'     => 'Неподдржан тип на слика',
'thumbnail_gd-library'     => 'Нецелосни поставки на графичката библиотека: недостасува функцијата $1',
'thumbnail_image-missing'  => 'Изгледа дека податотеката недостасува: $1',

# Special:Import
'import'                     => 'Увезување на страници',
'importinterwiki'            => 'Меѓувики увоз',
'import-interwiki-text'      => 'Избери вики и наслов на страница за увоз.
Датумите и имињата на уредниците ќе бидат зачувани.
Сите постапки при меѓувики увозот се заведуваат во [[Special:Log/import|дневникот на увезувања]].',
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
'imported-log-entries'       => '{{PLURAL:$1|Увезен е $1 дневнички запис|Увезени се $1 дневнички записи}}.',
'importfailed'               => 'Неуспешно внесување: $1',
'importunknownsource'        => 'Непознат тип за внесување',
'importcantopen'             => 'Не може да се отвори увезената податотека',
'importbadinterwiki'         => 'Лоша интервики врска',
'importnotext'               => 'Празно или без текст',
'importsuccess'              => 'Увезувањето е завршено!',
'importhistoryconflict'      => 'Постои конфликтна историја на верзиите (можно е страницата веќе да била внесена)',
'importnosources'            => 'Нема определено меѓувики-извори за увоз и директните подигања на историја се оневозможени.',
'importnofile'               => 'Нема подигнато увозна податотека.',
'importuploaderrorsize'      => 'Подигањето на увозната податотека не успеа.
Податотеката ја надминува допуштената големина.',
'importuploaderrorpartial'   => 'Подигањето на увозна податотека не успеа.
Податотеката е само делумно подигната.',
'importuploaderrortemp'      => 'Неуспешно подигање на увозна податотека.
Проблеми со привремена папка за податотеки.',
'import-parse-failure'       => 'Погрешно XML парсирање',
'import-noarticle'           => 'Нема страница за увоз!',
'import-nonewrevisions'      => 'Сите ревизии се претходно увезени.',
'xml-error-string'           => '$1 на линија $2, колона $3 (бајт $4): $5',
'import-upload'              => 'Подигни XML податоци',
'import-token-mismatch'      => 'Губење на сесиските податоци. Обидете се повторно.',
'import-invalid-interwiki'   => 'Не може да се увезува од специфицираното вики.',

# Import log
'importlogpage'                    => 'Дневник на увезувања',
'importlogpagetext'                => 'Административно увезување на страници со историја на уредување од други викија.',
'import-logentry-upload'           => 'увезена [[$1]] со подигање на податотека',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|ревизија|ревизии}}',
'import-logentry-interwiki'        => 'трансвикифиран $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|ревизија|ревизии}} од $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Вашата корисничка страница',
'tooltip-pt-anonuserpage'         => 'Корисничка страница за IP-адресата од која уредувате',
'tooltip-pt-mytalk'               => 'Вашата страница за разговор',
'tooltip-pt-anontalk'             => 'Разговор за уредувањата од оваа IP-адреса',
'tooltip-pt-preferences'          => 'Ваши нагодувања',
'tooltip-pt-watchlist'            => 'Список на страници кои сте избрале да ги набљудувате.',
'tooltip-pt-mycontris'            => 'Список на ваши придонеси',
'tooltip-pt-login'                => 'Ви препорачуваме да се најавите, иако тоа не е задолжително.',
'tooltip-pt-anonlogin'            => 'Ви препорачуваме да се најавите, иако тоа не е задолжително.',
'tooltip-pt-logout'               => 'Одјавете се',
'tooltip-ca-talk'                 => 'Разговор за страницата',
'tooltip-ca-edit'                 => 'Можете да ја уредите оваа страница. Ве молиме користете го копчето за преглед пред зачувување.',
'tooltip-ca-addsection'           => 'Започни нов пасус',
'tooltip-ca-viewsource'           => 'Оваа страница е заштитена. Можете да го видите изворниот код.',
'tooltip-ca-history'              => 'Претходни верзии на оваа страница.',
'tooltip-ca-protect'              => 'Заштитете ја оваа страница',
'tooltip-ca-unprotect'            => 'Симни заштита на оваа страница',
'tooltip-ca-delete'               => 'Избриши ја оваа страница',
'tooltip-ca-undelete'             => 'Обнови ги уредувањата направени на оваа страница пред да биде избришана',
'tooltip-ca-move'                 => 'Премести ја оваа страница',
'tooltip-ca-watch'                => 'Додај ја страницава во списокот на набљудувања',
'tooltip-ca-unwatch'              => 'Отстрани ја страницава од списокот на набљудувања',
'tooltip-search'                  => 'Пребарај низ {{SITENAME}}',
'tooltip-search-go'               => 'Оди на страница со ова име доколку постои',
'tooltip-search-fulltext'         => 'Пребарај го овој текст низ странците',
'tooltip-p-logo'                  => 'Главна страница',
'tooltip-n-mainpage'              => 'Посети ја главната страница',
'tooltip-n-mainpage-description'  => 'Посети главна страница',
'tooltip-n-portal'                => 'За проектот, што можете да направите, каде да најдете некои работи',
'tooltip-n-currentevents'         => 'Најдете информации за тековните настани',
'tooltip-n-recentchanges'         => 'Список на скорешни промени на викито.',
'tooltip-n-randompage'            => 'Вчитај случајна страница',
'tooltip-n-help'                  => 'Место каде што може да се информирате.',
'tooltip-t-whatlinkshere'         => 'Список на сите вики-страници што водат овде',
'tooltip-t-recentchangeslinked'   => 'Скорешни промени на страници со врски на оваа страница',
'tooltip-feed-rss'                => 'RSS емитување за оваа страница',
'tooltip-feed-atom'               => 'Atom емитување за оваа страница',
'tooltip-t-contributions'         => 'Список на придонеси на овој корисник',
'tooltip-t-emailuser'             => 'Испрати е-пошта на овој корисник',
'tooltip-t-upload'                => 'Подигни податотеки',
'tooltip-t-specialpages'          => 'Список на сите специјални страници',
'tooltip-t-print'                 => 'Верзија за печатење на оваа страница',
'tooltip-t-permalink'             => 'Постојана врска до оваа верзија на страницата',
'tooltip-ca-nstab-main'           => 'Преглед на содржината',
'tooltip-ca-nstab-user'           => 'Преглед на корисничката страница',
'tooltip-ca-nstab-media'          => 'Преглед на мултимедијалната податотека',
'tooltip-ca-nstab-special'        => 'Ова е специјална страница, не можете да ја уредувате',
'tooltip-ca-nstab-project'        => 'Преглед на проектната страница',
'tooltip-ca-nstab-image'          => 'Преглед на страницата на податотеката',
'tooltip-ca-nstab-mediawiki'      => 'Преглед на системската порака',
'tooltip-ca-nstab-template'       => 'Преглед на шаблонот',
'tooltip-ca-nstab-help'           => 'Преглед на страницата за помош',
'tooltip-ca-nstab-category'       => 'Преглед на категоријата',
'tooltip-minoredit'               => 'Обележете ја промената како ситна',
'tooltip-save'                    => 'Зачувајте ги вашите промени',
'tooltip-preview'                 => 'Прегледајте ги вашите промени, ве молиме користете го ова пред зачувување!',
'tooltip-diff'                    => 'Покажи кои промени ги направи во текстот.',
'tooltip-compareselectedversions' => 'Видете ја разликата помеѓу двете избрани верзии на оваа страница.',
'tooltip-watch'                   => 'Додај ја страницава во списокот на набљудувања',
'tooltip-recreate'                => 'Повторно создај ја страницата иако беше избришана',
'tooltip-upload'                  => 'Започни со подигање',
'tooltip-rollback'                => '„Врати“ ги поништува последните уредувања на оваа страница со еден клик до уредувањата на претпоследниот придонесувач',
'tooltip-undo'                    => '„Откажи“ го поништува ова уредување и отвора прозорец за уредување.
Дозволува додавање на причина во описот',
'tooltip-preferences-save'        => 'Зачувај',
'tooltip-summary'                 => 'Внесете кратко резиме',

# Stylesheets
'common.css'      => '/** Тука поставениот CSS ќе се примени на сите рува */',
'standard.css'    => '/* CSS кодот поставен тука ќе се применува врз корисниците што го избрале рувото Стандардно */',
'nostalgia.css'   => '/* CSS кодот поставен тука ќе се применува врз корисниците што го избрале рувото Носталгија */',
'cologneblue.css' => '/* CSS кодот поставен тука ќе се применува врз корисниците што го избрале рувото Келнско сино */',
'monobook.css'    => 'Базата на податоци не го пронајде текстот на страницата кој требаше да го пронајде, именуван „MediaWiki:Monobook.css/mk“ .

Ова најчесто е предизвикано од застарена разл. или врска до историја на страница која била избришана.

Ако не е таков случај, можеби сте наишле грешка во програмската опрема.
Пријавете го ова на некој [[Special:ListUsers/sysop|администратор]], давајќи врска до URL адресата.',
'myskin.css'      => '/* CSS кодот поставен тука ќе се применува врз корисниците што избрале Мое руво */',
'chick.css'       => '/* CSS кодот поставен тука ќе се применува врз корисниците што го избрале рувото Шик */',
'simple.css'      => '/* CSS кодот поставен тука ќе се применува врз корисниците што го одбрале рувото Просто */',
'modern.css'      => '/* CSS кодот поставен тука ќе се применува врз корисниците што го одбрале рувото Современо */',
'vector.css'      => '/* CSS кодот поставен тука ќе се применува врз корисниците што го користат рувото Векторско */',
'print.css'       => '/* CSS кодот поставен тука ќе се рефлектира врз страниците за печатење */',
'handheld.css'    => '/* CSS кодот поставен тука ќе се примнува врз рачните уреди засновани на рувото поставено во $wgHandheldStyle */',

# Scripts
'common.js'      => '/* Било која Јава скрипта пиставена овде ќе се вчита кај сите корисници при секое вчитување на било која страница. */',
'standard.js'    => '/* Било која Јава скрипта поставена овде ќе биде вчитана за сите корисници кои го користат рувото Стандардно */',
'nostalgia.js'   => '/* Било која Јава скрипта поставена овде ќе биде вчитана за сите корисници што го користат рувото Носталгија */',
'cologneblue.js' => '/* Било која Јава скрипта поставена овде ќе биде вчитана за сите корисници што го користат рувото Келнско сино */',
'monobook.js'    => '/* Било која JavaScript поставена овде ќе биде вчитана за сите корисници што го користат рувото Монобук */',
'myskin.js'      => '/* Било која Јава скрипта поставена овде ќе биде вчитана за сите корисници што користат Мое руво */',
'chick.js'       => '/* Било која Јава скрипта поставена овде ќе биде вчитана за сите корисници што го користат рувото Шик */',
'simple.js'      => '/* Било која Јава скрипта поставена овде ќе биде вчитана за сите корисници што го користат рувото Просто */',
'modern.js'      => '/* Било која Јава скрипта поставена овде ќе биде вчитана за сите корисници што го користат рувото Современо */',
'vector.js'      => '/* Било која Јава скрипта поставена овде ќе биде вчитана за сите корисници што го користат рувото Векторско */',

# Metadata
'nodublincore'      => 'Dublin Core RDF метаподатоци се оневозможени за овој опслужувач.',
'nocreativecommons' => 'Метаподатоците Creative Commons RDF се оневозможени за овој опслужувач.',
'notacceptable'     => 'Опслужувачот не може да создаде податоци во формат погоден за вашиот клиент.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Анонимен корисник|Анонимни корисници}} на {{SITENAME}}',
'siteuser'         => '{{SITENAME}} корисник $1',
'anonuser'         => '{{SITENAME}} анонимен корисник $1',
'lastmodifiedatby' => 'Оваа страница е последен пат изменета на $1 во $2 ч. од $3.',
'othercontribs'    => 'Засновано на работата на $1.',
'others'           => 'други',
'siteusers'        => '{{PLURAL:$2|корисникот|корисниците}} на {{SITENAME}} $1',
'anonusers'        => '{{PLURAL:$2|анонимен корисник|анонимни корисници}} на {{SITENAME}} $1',
'creditspage'      => 'Автори на страницата',
'nocredits'        => 'Не постојат податоци за авторите на оваа страница.',

# Spam protection
'spamprotectiontitle' => 'Филтер за заштита од спам',
'spamprotectiontext'  => 'Страницата која сакавте да ја зачувате беше блокирана од филтерот за спам.
Ова најверојатно е предизвикано од врска кон надворешно мрежно место кое се наоѓа на црниот список.',
'spamprotectionmatch' => 'Следниот текст е она што го предизвика нашиот филтер за спам: $1',
'spambot_username'    => 'МедијаВики чистач на спам',
'spam_reverting'      => 'Враќам на последната верзија што не содржи врска до $1',
'spam_blanking'       => 'Сите верзии содржеа врски до $1, бришам',

# Info page
'infosubtitle'   => 'Информации за страница',
'numedits'       => 'Број на уредувања (страница): $1',
'numtalkedits'   => 'Број на уредувања (страница за разговор): $1',
'numwatchers'    => 'Број на набљудувачи: $1',
'numauthors'     => 'Број на различни автори (страница): $1',
'numtalkauthors' => 'Број на различни автори (страница за разговор): $1',

# Skin names
'skinname-standard'    => 'Класично',
'skinname-nostalgia'   => 'Носталгија',
'skinname-cologneblue' => 'Келнско сино',
'skinname-monobook'    => 'Монобук',
'skinname-myskin'      => 'Мое руво',
'skinname-chick'       => 'Шик',
'skinname-simple'      => 'Просто',
'skinname-modern'      => 'Современо',
'skinname-vector'      => 'Векторско',

# Math options
'mw_math_png'    => 'Секогаш исцртувај во PNG',
'mw_math_simple' => 'HTML ако е многу едноставно, а инаку во PNG',
'mw_math_html'   => 'HTML ако е можно, ако не PNG',
'mw_math_source' => 'Остави го како TeX (за текстуални прелистувачи)',
'mw_math_modern' => 'Препорачливо за современи прелистувачи',
'mw_math_mathml' => 'MathML ако е можно (експериментално)',

# Math errors
'math_failure'          => 'Неможам да парсирам',
'math_unknown_error'    => 'непозната грешка',
'math_unknown_function' => 'непозната функција',
'math_lexing_error'     => 'лексичка грешка',
'math_syntax_error'     => 'синтаксна грешка',
'math_image_error'      => 'неуспешна PNG конверзија',
'math_bad_tmpdir'       => 'Неможе да се запише во или да се создаде привремен именик за математички операции',
'math_bad_output'       => 'Неможе да се запише во или да се создаде именик за излезни математички операции',
'math_notexvc'          => 'Недостасува извршната податотека texvc;
погледнете math/README за нејзино нагодување.',

# Patrolling
'markaspatrolleddiff'                 => 'Означи како проверена верзија',
'markaspatrolledtext'                 => 'Означи ја верзијата како проверена',
'markedaspatrolled'                   => 'Означено како проверено',
'markedaspatrolledtext'               => 'Избраната ревизија на [[:$1]]  е означена како патролирана.',
'rcpatroldisabled'                    => 'Оневозможено проверка на скорешни промени',
'rcpatroldisabledtext'                => 'Патролирањето на Скорешните промени е деактивирано.',
'markedaspatrollederror'              => 'Не можам да означам како проверена',
'markedaspatrollederrortext'          => 'Морате да внесете верзија за да ја означите како проверена.',
'markedaspatrollederror-noautopatrol' => 'Не можете да ги означите своите промени како проверени.',

# Patrol log
'patrol-log-page'      => 'Дневник на патролирања',
'patrol-log-header'    => 'Ова е дневник на патролирани ревизии.',
'patrol-log-line'      => 'означена $1 од $2 како патролирана $3',
'patrol-log-auto'      => '(автоматски)',
'patrol-log-diff'      => 'ревизија $1',
'log-show-hide-patrol' => '$1 дневник на патролирање',

# Image deletion
'deletedrevision'                 => 'Избришана стара ревизија $1.',
'filedeleteerror-short'           => 'Грешка при бришење на податотека: $1',
'filedeleteerror-long'            => 'Се појавија грешки при бришењето на податотеката:

$1',
'filedelete-missing'              => 'Податотеката „$1“ не може да се избрише, бидејќи не постои.',
'filedelete-old-unregistered'     => 'Наведената ревизија на податотеката „$1“ не се наоѓа во базата на податоци.',
'filedelete-current-unregistered' => 'Наведената податотека „$1“ не се наоѓа во базата на податоци.',
'filedelete-archive-read-only'    => 'Во именикот за архивирање „$1“ семрежниот опслужувач не може да запишува.',

# Browsing diffs
'previousdiff' => '← Постаро уредување',
'nextdiff'     => 'Поново уредување →',

# Media information
'mediawarning'         => "'''Предупредување''': Оваа податотека може да содржи штетен код.
Ако ја користите, ова може да му наштети на вашиот систем.",
'imagemaxsize'         => "Ограничување на големина на слика:<br />''(на нивните описни страници)''",
'thumbsize'            => 'Големина на минијатурата:',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|страница|страници}}',
'file-info'            => '(големина: $1, MIME-тип: $2)',
'file-info-size'       => '($1 × $2 пиксели, големина: $3, MIME-тип: $4)',
'file-nohires'         => '<small>Не е достапна поголема резолуција.</small>',
'svg-long-desc'        => '(SVG податотека, номинално $1 × $2 пиксели, големина: $3)',
'show-big-image'       => 'Вистинска големина',
'show-big-image-thumb' => '<small>Големина на овој приказ: $1 × $2 пиксели</small>',
'file-info-gif-looped' => 'кружно',
'file-info-gif-frames' => '$1 {{PLURAL:$1|кадар|кадри}}',
'file-info-png-looped' => 'кружно',
'file-info-png-repeat' => 'пуштено {{PLURAL:$1|еднаш|$1 пати}}',
'file-info-png-frames' => '$1 {{PLURAL:$1|кадар|кадри}}',

# Special:NewFiles
'newimages'             => 'Галерија на нови податотеки',
'imagelisttext'         => "Следи список на '''$1''' {{PLURAL:$1|податотека|податотеки}} подредени $2.",
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
'seconds-abbrev' => 'с',
'minutes-abbrev' => 'м',
'hours-abbrev'   => 'ч',

# Bad image list
'bad_image_list' => 'Форматот е следниот:

Само набројувањата (редови кои започнуваат со *) се земаат предвид.
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
'metadata-help'     => 'Оваа податотека содржи дополнителни информации, најверојатно додадени од дигиталниот апарат или скенер користени за нејзино создавање или дигитализација. Ако притоа податотеката претрпела промени, некои детали може да не соодветствуваат во целост по промената на податотеката.',
'metadata-expand'   => 'Прикажи дополнителни информации',
'metadata-collapse' => 'Сокриј дополнителни информации',
'metadata-fields'   => 'Полињата на метаподатоци EXIF прикажани во оваа порака ќе бидат вклучени на страницата на сликата кога мета табелата ќе биде затворена.
Останатите ќе бидат сокриени по основно.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Ширина',
'exif-imagelength'                 => 'Висина',
'exif-bitspersample'               => 'Длабочина на бојата',
'exif-compression'                 => 'Вид на компресија',
'exif-photometricinterpretation'   => 'Пикселски состав',
'exif-orientation'                 => 'Ориентација',
'exif-samplesperpixel'             => 'Број на сост. делови',
'exif-planarconfiguration'         => 'Распоред на податоците',
'exif-ycbcrsubsampling'            => 'Однос на величината на Y спрема C',
'exif-ycbcrpositioning'            => 'Положби на Y и C',
'exif-xresolution'                 => 'Хоризонтална резолуција',
'exif-yresolution'                 => 'Вертикална резолуција',
'exif-resolutionunit'              => 'Единица за резолуција на X и Y',
'exif-stripoffsets'                => 'Положба на податоците',
'exif-rowsperstrip'                => 'Број на редови по блок',
'exif-stripbytecounts'             => 'Бајти по компримиран блок',
'exif-jpeginterchangeformat'       => 'Почеток на JPEG-преглед',
'exif-jpeginterchangeformatlength' => 'Бајти JPEG податоци',
'exif-transferfunction'            => 'Функција за пренос',
'exif-whitepoint'                  => 'Хроматичност на белата точка',
'exif-primarychromaticities'       => 'Хроматичност на основните бои',
'exif-ycbcrcoefficients'           => 'Матрични коефициенти на трансформација на бојниот простор',
'exif-referenceblackwhite'         => 'Упатни вредности на парот бела и црна точка',
'exif-datetime'                    => 'Датум и време на промена на податотеката',
'exif-imagedescription'            => 'Назив на сликата',
'exif-make'                        => 'Произведувач',
'exif-model'                       => 'Модел',
'exif-software'                    => 'Користена програмска опрема',
'exif-artist'                      => 'Автор',
'exif-copyright'                   => 'Носител на авторските права',
'exif-exifversion'                 => 'Exif-верзија',
'exif-flashpixversion'             => 'Поддржана верзија на Flashpix',
'exif-colorspace'                  => 'Боен простор',
'exif-componentsconfiguration'     => 'Значење на секој боен дел',
'exif-compressedbitsperpixel'      => 'Режим на компресија на сликата',
'exif-pixelydimension'             => 'Важечка ширина на сликата',
'exif-pixelxdimension'             => 'Важечка висина на сликата',
'exif-makernote'                   => 'Забелешки на произведувачот',
'exif-usercomment'                 => 'Коментар на корисникот',
'exif-relatedsoundfile'            => 'Поврзана аудиоснимка',
'exif-datetimeoriginal'            => 'Датум и време на сликање',
'exif-datetimedigitized'           => 'Датум и време на дигитализација',
'exif-subsectime'                  => 'Дел од секундата во кој е сликано',
'exif-subsectimeoriginal'          => 'Дел од секундата во кој е фотографирано',
'exif-subsectimedigitized'         => 'Дел од секундата во кој е дигитализирано',
'exif-exposuretime'                => 'Експозиција',
'exif-exposuretime-format'         => '$1 сек. ($2)',
'exif-fnumber'                     => 'F-бр.',
'exif-fnumber-format'              => 'f/$1',
'exif-exposureprogram'             => 'Режим на експозиција',
'exif-spectralsensitivity'         => 'Спектрална осетливост',
'exif-isospeedratings'             => 'ISO светлоосетливост',
'exif-oecf'                        => 'Фактор на оптоелектронско претворање',
'exif-shutterspeedvalue'           => 'Брзина на затворачот',
'exif-aperturevalue'               => 'Отвор на блендата',
'exif-brightnessvalue'             => 'Сила на светлина',
'exif-exposurebiasvalue'           => 'Надоместок на експозицијата',
'exif-maxaperturevalue'            => 'Макс. отвореност на блендата',
'exif-subjectdistance'             => 'Оддалеченост до објектот',
'exif-meteringmode'                => 'Режим на мерачот',
'exif-lightsource'                 => 'Светлосен извор',
'exif-flash'                       => 'Блиц',
'exif-focallength'                 => 'Фокусно растојание на леќата',
'exif-focallength-format'          => '$1 мм',
'exif-subjectarea'                 => 'Положба и површина на објектот',
'exif-flashenergy'                 => 'Енергија на блицот',
'exif-spatialfrequencyresponse'    => 'Просторно-фреквенциски одзив',
'exif-focalplanexresolution'       => 'Резолуција на фокусната рамнина  X',
'exif-focalplaneyresolution'       => 'Резолуција на фокусната рамнина Y',
'exif-focalplaneresolutionunit'    => 'Единица за резолуција на фокусната рамнина',
'exif-subjectlocation'             => 'Положба на субјектот',
'exif-exposureindex'               => 'Индекс на експозицијата',
'exif-sensingmethod'               => 'Метод на сензорот',
'exif-filesource'                  => 'Извор на податотеката',
'exif-scenetype'                   => 'Тип на сцена',
'exif-cfapattern'                  => 'Шема на боен филтер',
'exif-customrendered'              => 'Дополнитела обработка на сликата',
'exif-exposuremode'                => 'Режим на експозиција',
'exif-whitebalance'                => 'Рамнотежа на бело',
'exif-digitalzoomratio'            => 'Сооднос на дигиталното приближување (зум)',
'exif-focallengthin35mmfilm'       => 'Еквивалентно фокусно растојание за 35 mm филм',
'exif-scenecapturetype'            => 'Тип на сликање сцена',
'exif-gaincontrol'                 => 'Контрола на сцената',
'exif-contrast'                    => 'Контраст',
'exif-saturation'                  => 'Заситеност',
'exif-sharpness'                   => 'Острина',
'exif-devicesettingdescription'    => 'Опис на поставките на апаратот',
'exif-subjectdistancerange'        => 'Опсег на оддалеченост на објектот',
'exif-imageuniqueid'               => 'Единствен идентификатор на сликата',
'exif-gpsversionid'                => 'Верзија на ознака за GPS податоци',
'exif-gpslatituderef'              => 'Северна или Јужна ГШ',
'exif-gpslatitude'                 => 'Геог. ширина',
'exif-gpslongituderef'             => 'Источна или западна ГД',
'exif-gpslongitude'                => 'Геог. должина',
'exif-gpsaltituderef'              => 'Упатна точка за висната',
'exif-gpsaltitude'                 => 'Височина',
'exif-gpstimestamp'                => 'GPS-време (атомски часовник)',
'exif-gpssatellites'               => 'Употребени сателити',
'exif-gpsstatus'                   => 'Статус на приемникот',
'exif-gpsmeasuremode'              => 'Режим на мерење',
'exif-gpsdop'                      => 'Прецизност на мерењето',
'exif-gpsspeedref'                 => 'Единица за брзина',
'exif-gpsspeed'                    => 'Брзина на GPS приемникот',
'exif-gpstrackref'                 => 'Упат за насока на движење',
'exif-gpstrack'                    => 'Насока на движење',
'exif-gpsimgdirectionref'          => 'Упат за насока на сликата',
'exif-gpsimgdirection'             => 'Насока на сликата',
'exif-gpsmapdatum'                 => 'Користен геодетски координатен систем',
'exif-gpsdestlatituderef'          => 'Индекс за географската ширина на објектот',
'exif-gpsdestlatitude'             => 'Географска ширина на објектот',
'exif-gpsdestlongituderef'         => 'Индекс за географската должина на објектот',
'exif-gpsdestlongitude'            => 'Географска должина на објектот',
'exif-gpsdestbearingref'           => 'Индекс на азимутот на објектот',
'exif-gpsdestbearing'              => 'Азимут на објектот',
'exif-gpsdestdistanceref'          => 'Мерна единица за оддалеченоста на објектот',
'exif-gpsdestdistance'             => 'Оддалеченост на објектот',
'exif-gpsprocessingmethod'         => 'Назив на методот за обработка на GPS податоци',
'exif-gpsareainformation'          => 'Назив на GPS зоната',
'exif-gpsdatestamp'                => 'GPS датум',
'exif-gpsdifferential'             => 'Диференцијална исправка на GPS',

# EXIF attributes
'exif-compression-1' => 'Некомпримиран',

'exif-unknowndate' => 'Непознат датум',

'exif-orientation-1' => 'Нормална',
'exif-orientation-2' => 'Пресликано по хоризонтална оска',
'exif-orientation-3' => 'Свртено за 180°',
'exif-orientation-4' => 'Пресликано по вертикална оска',
'exif-orientation-5' => 'Свртено за 90° наспроти часовникот и обратно по вертикална оска',
'exif-orientation-6' => 'Ротирано за 90° по часовникот',
'exif-orientation-7' => 'Ротирано 90° по часовникот и обратно по вертикална оска',
'exif-orientation-8' => 'Ротирано за 90° спротивно од часовникот',

'exif-planarconfiguration-1' => 'формат „chunky“',
'exif-planarconfiguration-2' => 'формат „planar“',

'exif-xyresolution-i' => '$1 точки на инч',
'exif-xyresolution-c' => '$1 точки на сантиметар',

'exif-colorspace-1'      => 'sRGB',
'exif-colorspace-ffff.h' => 'FFFF.H',

'exif-componentsconfiguration-0' => 'не постои',
'exif-componentsconfiguration-1' => 'Y',
'exif-componentsconfiguration-2' => 'Cb',
'exif-componentsconfiguration-3' => 'Cr',
'exif-componentsconfiguration-4' => 'R',
'exif-componentsconfiguration-5' => 'G',
'exif-componentsconfiguration-6' => 'B',

'exif-exposureprogram-0' => 'Неопределено',
'exif-exposureprogram-1' => 'Рачно',
'exif-exposureprogram-2' => 'Нормален режим',
'exif-exposureprogram-3' => 'Приоритет на отворот на блендата',
'exif-exposureprogram-4' => 'Приоритет на затворачот',
'exif-exposureprogram-5' => 'Креативен режим (врз основа на потребната длабочина на острина)',
'exif-exposureprogram-6' => 'Спортски режим (на основа на што побрз затворач)',
'exif-exposureprogram-7' => 'Портретен режим (за фотографии одблизу со заматена позадина)',
'exif-exposureprogram-8' => 'Пејзажен режим (за фотографии на пејзажи со остра позадина)',

'exif-subjectdistance-value' => '$1 метри',

'exif-meteringmode-0'   => 'Непознато',
'exif-meteringmode-1'   => 'Просечно',
'exif-meteringmode-2'   => 'Просек со тежиште на средина',
'exif-meteringmode-3'   => 'Во точка',
'exif-meteringmode-4'   => 'Во повеќе точки',
'exif-meteringmode-5'   => 'Матричен',
'exif-meteringmode-6'   => 'Делумен',
'exif-meteringmode-255' => 'Друго',

'exif-lightsource-0'   => 'Непознато',
'exif-lightsource-1'   => 'Дневна светлина',
'exif-lightsource-2'   => 'Флуоресцентно',
'exif-lightsource-3'   => 'Волфрамско',
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
'exif-flash-return-2'   => 'не е востановено повратно светло од стробоскопот',
'exif-flash-return-3'   => 'востановено повратно светло од стробоскоп',
'exif-flash-mode-1'     => 'задолжително со блиц',
'exif-flash-mode-2'     => 'задолжително без блиц',
'exif-flash-mode-3'     => 'автоматски режим',
'exif-flash-function-1' => 'Нема функција за блиц',
'exif-flash-redeye-1'   => 'режим на исправка на црвени очи',

'exif-focalplaneresolutionunit-2' => 'инчи',

'exif-sensingmethod-1' => 'Неопределен',
'exif-sensingmethod-2' => 'Еднокристален матричен боен сензор',
'exif-sensingmethod-3' => 'Двокристален матричен боен сензор',
'exif-sensingmethod-4' => 'Трокристален матричен боен сензор',
'exif-sensingmethod-5' => 'Секвенцијален матричен боен сензор',
'exif-sensingmethod-7' => 'Тробоен линеарен сензор',
'exif-sensingmethod-8' => 'Бојно-последователен линеарен сензор',

'exif-filesource-3' => 'Дигитален фотоапарат',

'exif-scenetype-1' => 'Директно фотографирана слика',

'exif-customrendered-0' => 'Нормален процес',
'exif-customrendered-1' => 'Нестандарден процес',

'exif-exposuremode-0' => 'Автоматска експозиција',
'exif-exposuremode-1' => 'Рачна експозиција',
'exif-exposuremode-2' => 'Автоматски со зададен распон',

'exif-whitebalance-0' => 'Автоматска рамнотежа на бело',
'exif-whitebalance-1' => 'Рачен баланс на бело',

'exif-scenecapturetype-0' => 'Стандардно',
'exif-scenecapturetype-1' => 'Пејзаж',
'exif-scenecapturetype-2' => 'Портрет',
'exif-scenecapturetype-3' => 'Ноќна сцена',

'exif-gaincontrol-0' => 'Нема',
'exif-gaincontrol-1' => 'Мало зголемување',
'exif-gaincontrol-2' => 'Големо зголемување',
'exif-gaincontrol-3' => 'Мало смалување',
'exif-gaincontrol-4' => 'Големо смалување',

'exif-contrast-0' => 'Нормално',
'exif-contrast-1' => 'Меко',
'exif-contrast-2' => 'Силно',

'exif-saturation-0' => 'Нормално',
'exif-saturation-1' => 'Ниска заситеност',
'exif-saturation-2' => 'Висока заситеност',

'exif-sharpness-0' => 'Нормално',
'exif-sharpness-1' => 'Меко',
'exif-sharpness-2' => 'Тврдо',

'exif-subjectdistancerange-0' => 'Непознато',
'exif-subjectdistancerange-1' => 'Крупен кадар (макро)',
'exif-subjectdistancerange-2' => 'Близок кадар',
'exif-subjectdistancerange-3' => 'Далечен кадар',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Северна ширина',
'exif-gpslatitude-s' => 'јужна ширина',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'источна должина',
'exif-gpslongitude-w' => 'западна должина',

'exif-gpsstatus-a' => 'Мерење во тек',
'exif-gpsstatus-v' => 'Подготвен за пренос',

'exif-gpsmeasuremode-2' => 'Дводимензионално мерење',
'exif-gpsmeasuremode-3' => 'Тридимензионално мерење',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Километри на час',
'exif-gpsspeed-m' => 'Милји на час',
'exif-gpsspeed-n' => 'Јазли',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Вистински правец',
'exif-gpsdirection-m' => 'Магнетен правец',

# External editor support
'edit-externally'      => 'Уреди ја податотеката со надворешен програм',
'edit-externally-help' => '(Видете [http://www.mediawiki.org/wiki/Manual:External_editors повеќе напатствија] за нагодувањето).',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'сите',
'imagelistall'     => 'сите',
'watchlistall2'    => 'сите',
'namespacesall'    => 'сите',
'monthsall'        => 'сите',
'limitall'         => 'сите',

# E-mail address confirmation
'confirmemail'              => 'Потврда на е-поштенска адреса',
'confirmemail_noemail'      => 'Немате наведено важечка е-поштенска адреса во вашите [[Special:Preferences|нагодувања]].',
'confirmemail_text'         => '{{SITENAME}} бара да ја потврдите вашата е-поштенска адреса пред да ги користите можностите за е-пошта.
Притиснете на копчето подолу за да пратите потврдувачка е-порака на вашата адреса.
Е-пораката ќе вклучи врска којашто содржи код;
отворете ја врската во вашиот прелистувач за да потврдите дека вашата е-поштенска адреса е важечка.',
'confirmemail_pending'      => 'Писмото со потврден код е веќе испратено. Ако сметката ја создадовте сега, тогаш веројатно ќе треба да почекате некоја минута за да пристигне, пред да побарате нов код.',
'confirmemail_send'         => 'Испрати потврден код',
'confirmemail_sent'         => 'Потврдната порака е испратена.',
'confirmemail_oncreate'     => 'Испратен е потврден код на вашата е-поштенска адреса.
Овој код не е потребен за најавување, но ќе треба да го внесете пред да ги вклучите е-поштенските можности во викито.',
'confirmemail_sendfailed'   => '{{SITENAME}} не може да ви прати потврдна е-порака.
Проверете дали е-поштенската адреса е правилно запишана.

Вратена е следнава грешка: $1',
'confirmemail_invalid'      => 'Неточен потврден код. 
Кодот можеби е истечен.',
'confirmemail_needlogin'    => 'Морате да $1 за да ја потврдите вашата е-поштенска адреса.',
'confirmemail_success'      => 'Вашата е-поштенска адреса е потврдена.
Сега можете да се [[Special:UserLogin|најавите]]. Ви посакуваме пријатни мигови на викито!',
'confirmemail_loggedin'     => 'Вашата е-поштенска адреса сега е потврдена.',
'confirmemail_error'        => 'Нешто тргна наопаку при снимањето на вашата потврда.',
'confirmemail_subject'      => '{{SITENAME}} потврда за е-поштенска адреса',
'confirmemail_body'         => 'Некој, веројатно Вие, од IP-адресата $1,
ја регистрирал сметката „$2“ со оваа е-поштенска адреса на {{SITENAME}}.

За да потврдите дека оваа сметка навистина Ви припаѓа Вам и да ја активирате
можноста за е-пошта на {{SITENAME}}, следете ја оваа врска во вашиот прелистувач:

$3

Ако Вие *не* сте оној што ја регистрирал сметката, следете ја оваа врска
за да го откажете потврдувањето на е-поштенската адреса:

$5

Овој потврден код ќе истече на $4.',
'confirmemail_body_changed' => 'Некој, веројатно Вие, од IP-адреса $1,
на {{SITENAME}} ја има променето е-поштенската адреса на сметката „$2“, назначувајќи ја оваа адреса како нова.

За да потврдите дека оваа сметка навистина Ви припаѓа Вам и повторно да ги вклучите
е-поштенските функции на {{SITENAME}}, отворете ја следнава врска во Вашиот прелистувач:

$3

Ако оваа сметка *не* Ви припаѓа Вам, проследете ја оваа врска
за да го откажете потврдувањето на е-поштенската адреса:

$5

Овој потврден код истекува на $4.',
'confirmemail_invalidated'  => 'Потврдата на е-поштенската адреса е откажана',
'invalidateemail'           => 'Откажување на потврда на е-пошта',

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
Потврдете дека навистина сакате повторно да ја создадете оваа страница.",
'recreate'            => 'Повторно создај',

'unit-pixel' => 'п',

# action=purge
'confirm_purge_button' => 'OK',
'confirm-purge-top'    => 'Исчисти го кешот на оваа страница?',
'confirm-purge-bottom' => 'Со оваа операција се чисти опслужувачиот кеш и се прикажува најновата верзија.',

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
'table_pager_limit_label'  => 'Ставки по страница:',
'table_pager_limit_submit' => 'Оди',
'table_pager_empty'        => 'Нема резултати',

# Auto-summaries
'autosumm-blank'   => 'Целосно избришана страница',
'autosumm-replace' => "Ја заменувам страницата со '$1'",
'autoredircomment' => 'Пренасочување кон [[$1]]',
'autosumm-new'     => 'Создадена страница со: $1',

# Size units
'size-bytes'     => '$1 Б',
'size-kilobytes' => '$1 КБ',
'size-megabytes' => '$1 МБ',
'size-gigabytes' => '$1 ГБ',

# Live preview
'livepreview-loading' => 'Се вчитува…',
'livepreview-ready'   => 'Се вчитува… готово!',
'livepreview-failed'  => 'Неуспешно прегледување!
Пробајте со нормален преглед.',
'livepreview-error'   => 'Неуспешно поврзување: $1 „$2“
Обидете се со нормален преглед.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Промените во {{PLURAL:$1|последната секунда|последните $1 секунди}} може да не бидат прикажани во списокот.',
'lag-warn-high'   => 'Поради преоптовареност на податочниот опслужувач, промените понови од $1 {{PLURAL:$1|секунда|секунди}}
може да не бидат прикажани во списокот.',

# Watchlist editor
'watchlistedit-numitems'       => 'Вашиот список на набљудувања содржи {{PLURAL:$1|еден наслов|$1 наслови}}, без страниците за разговор.',
'watchlistedit-noitems'        => 'Вашиот список на набљудувања не содржи ниту еден наслов.',
'watchlistedit-normal-title'   => 'Уредување на списокот на набљудувања',
'watchlistedit-normal-legend'  => 'Бришење на наслови од список на набљудување',
'watchlistedit-normal-explain' => 'Насловите во вашиот список на набљудувања се прикажани подолу.
За да избришете наслов, штиклирајте го квадратчето лево од насловот, и кликнете на „{{int:Watchlistedit-normal-submit}}“.
Исто така можете да го [[Special:Watchlist/raw|уредувате списокот како текст]].',
'watchlistedit-normal-submit'  => 'Бриши Наслови',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 наслов беше|$1 наслови беа}} избришани од вашиот список на набљудувања:',
'watchlistedit-raw-title'      => 'Напредно уредување на списокот на набљудувања',
'watchlistedit-raw-legend'     => 'Уредување на списокот на набљудувања',
'watchlistedit-raw-explain'    => 'Насловите во вашиот список на набљудувања се прикажани подолу, и можат да се уредуваат со додавање или бришење на наслови од списокот; еден наслов по ред. Кога ќе завршите, кликнете на „{{int:Watchlistedit-raw-submit}}“.
Исто така за ова може да [[Special:Watchlist/edit|користите стандарден уредувач на текст]].',
'watchlistedit-raw-titles'     => 'Наслови:',
'watchlistedit-raw-submit'     => 'Ажурирај',
'watchlistedit-raw-done'       => 'Вашиот список на набљудувања е подновен.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 наслов беше|$1 наслови беа}} додадени:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 наслов беше избришан|$1 наслови беа избришани}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Преглед на релевантни промени',
'watchlisttools-edit' => 'Погледни и уреди список на набљудувања',
'watchlisttools-raw'  => 'Напредно уредување на списокот на набљудувања',

# Iranian month names
'iranian-calendar-m1'  => 'Фарвардин',
'iranian-calendar-m2'  => 'Ордибехешт',
'iranian-calendar-m3'  => 'Хордад',
'iranian-calendar-m4'  => 'Тир',
'iranian-calendar-m5'  => 'Мордад',
'iranian-calendar-m6'  => 'Шахривар',
'iranian-calendar-m7'  => 'Мехр',
'iranian-calendar-m8'  => 'Абан',
'iranian-calendar-m9'  => 'Азар',
'iranian-calendar-m10' => 'Деј',
'iranian-calendar-m11' => 'Бахман',
'iranian-calendar-m12' => 'Есфанд',

# Hijri month names
'hijri-calendar-m1'  => 'Мухарам',
'hijri-calendar-m2'  => 'Сафар',
'hijri-calendar-m3'  => 'Раби ел-ауал',
'hijri-calendar-m4'  => 'Раби ел-тани',
'hijri-calendar-m5'  => 'Џумада ел-ауал',
'hijri-calendar-m6'  => 'Џумада ел-тани',
'hijri-calendar-m7'  => 'Раџаб',
'hijri-calendar-m8'  => 'Шабан',
'hijri-calendar-m9'  => 'Рамадан',
'hijri-calendar-m10' => 'Шавал',
'hijri-calendar-m11' => 'Ду ел-Кида',
'hijri-calendar-m12' => 'Ду ел-Хиџа',

# Hebrew month names
'hebrew-calendar-m1'      => 'Тишри',
'hebrew-calendar-m2'      => 'Хешван',
'hebrew-calendar-m3'      => 'Кислев',
'hebrew-calendar-m4'      => 'Тебет',
'hebrew-calendar-m5'      => 'Шебат',
'hebrew-calendar-m6'      => 'Адар',
'hebrew-calendar-m6a'     => 'Адар I',
'hebrew-calendar-m6b'     => 'Адар II',
'hebrew-calendar-m7'      => 'Нисан',
'hebrew-calendar-m8'      => 'Ијар',
'hebrew-calendar-m9'      => 'Сиван',
'hebrew-calendar-m10'     => 'Тамуз',
'hebrew-calendar-m11'     => 'Ав',
'hebrew-calendar-m12'     => 'Елул',
'hebrew-calendar-m1-gen'  => 'Тишри',
'hebrew-calendar-m2-gen'  => 'Хешван',
'hebrew-calendar-m3-gen'  => 'Кислев',
'hebrew-calendar-m4-gen'  => 'Тебет',
'hebrew-calendar-m5-gen'  => 'Шебат',
'hebrew-calendar-m6-gen'  => 'Адар',
'hebrew-calendar-m6a-gen' => 'Адар I',
'hebrew-calendar-m6b-gen' => 'Адар II',
'hebrew-calendar-m7-gen'  => 'Нисан',
'hebrew-calendar-m8-gen'  => 'Ијар',
'hebrew-calendar-m9-gen'  => 'Сиван',
'hebrew-calendar-m10-gen' => 'Тамуз',
'hebrew-calendar-m11-gen' => 'Ав',
'hebrew-calendar-m12-gen' => 'Елул',

# Core parser functions
'unknown_extension_tag' => 'Непозната ознака на додатокот „$1“',
'duplicate-defaultsort' => 'Предупредување: Основниот клуч за подредување „$2“ го поништува претходниот основен клуч за подредување „$1“.',

# Special:Version
'version'                          => 'Верзија',
'version-extensions'               => 'Инсталирани додатоци',
'version-specialpages'             => 'Специјални страници',
'version-parserhooks'              => 'Парсерски куки',
'version-variables'                => 'Променливи',
'version-antispam'                 => 'Спречување на спам',
'version-skins'                    => 'Рува',
'version-other'                    => 'Друго',
'version-mediahandlers'            => 'Ракувачи со мултимедијални содржини',
'version-hooks'                    => 'Куки',
'version-extension-functions'      => 'Функции на додатоците',
'version-parser-extensiontags'     => 'Ознаки за парсерски додатоци',
'version-parser-function-hooks'    => 'Куки на парсерските функции',
'version-skin-extension-functions' => 'Функции за обогатување на изгледот',
'version-hook-name'                => 'Име на кука',
'version-hook-subscribedby'        => 'Претплатено од',
'version-version'                  => '(Верзија $1)',
'version-svn-revision'             => '(рев. $2)',
'version-license'                  => 'Лиценца',
'version-poweredby-credits'        => "Ова вики работи на '''[http://www.mediawiki.org/ МедијаВики]''', авторски права © 2001-$1 $2.",
'version-poweredby-others'         => 'други',
'version-license-info'             => 'МедијаВики е слободна програмска опрема; можете да ја редистрибуирате и/или менувате под условите на ГНУ-овата општа јавна лиценца на Фондацијата за слободна програмска опрема; или верзија 2 на Лиценцата, или некоја понова верзија (по ваш избор).

МедијаВики се нуди со надеж дека ќе биде од корист, но БЕЗ БИЛО КАКВА ГАРАНЦИЈА; дури и без подразбраната гаранција за ПРОДАЖНА ВРЕДНОСТ или ПОГОДНОСТ ЗА ДАДЕНА ЦЕЛ. За повеќе информации, погледајте ја ГНУ-овата општа јавна лиценца.

Заедно со програмов треба да имате добиено [{{SERVER}}{{SCRIPTPATH}}/COPYING примерок од ГНУ-овата општа јавна лиценца]; ако немате добиено примерок, пишете на Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA или [http://www.gnu.org/licenses/old-licenses/gpl-2.0.html прочитајте ја тука].',
'version-software'                 => 'Инсталирана програмска опрема',
'version-software-product'         => 'Производ',
'version-software-version'         => 'Верзија',

# Special:FilePath
'filepath'         => 'Патека до податотека',
'filepath-page'    => 'Податотека:',
'filepath-submit'  => 'Патека',
'filepath-summary' => 'Оваа специјална страница го враќа целосниот пат на податотеката.
Сликите се прикажани во изворна големина, другите типови на податотеки се отвораат со соодветните програми, директно.

Внесете го името на податотеката без префиксот „{{ns:file}}:“.',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Барање на дуплирани податотеки',
'fileduplicatesearch-summary'  => 'Пребарување на дуплирани податотеки врз основа на нивната hash-вредност.

Внесете име на податотека без префиксот „{{ns:file}}:“.',
'fileduplicatesearch-legend'   => 'Барање на дупликат',
'fileduplicatesearch-filename' => 'Име на податотека:',
'fileduplicatesearch-submit'   => 'Барај',
'fileduplicatesearch-info'     => '$1 × $2 пиксели<br />Големина на податотеката: $3<br />MIME-тип: $4',
'fileduplicatesearch-result-1' => 'Податотеката „$1“ нема идентичен дупликат.',
'fileduplicatesearch-result-n' => 'Податотеката „$1“ има {{PLURAL:$2|еден идентичен дупликат|$2 идентични дупликати}}.',

# Special:SpecialPages
'specialpages'                   => 'Специјални страници',
'specialpages-note'              => '----
* Нормални специјални страници.
* <strong class="mw-specialpagerestricted">Специјални страници со ограничен пристап.</strong>',
'specialpages-group-maintenance' => 'Извештаи за одржување на википедија',
'specialpages-group-other'       => 'Други специјални страници',
'specialpages-group-login'       => 'Најавување',
'specialpages-group-changes'     => 'Скорешни промени и дневници',
'specialpages-group-media'       => 'Извештаи за мултимедијални содржини и подигања',
'specialpages-group-users'       => 'Корнисници и кориснички права',
'specialpages-group-highuse'     => 'Најкористени страници',
'specialpages-group-pages'       => 'Списоци на страници',
'specialpages-group-pagetools'   => 'Алатки за страници',
'specialpages-group-wiki'        => 'Вики податоци и алатки',
'specialpages-group-redirects'   => 'Пренасочување на специјални страници',
'specialpages-group-spam'        => 'Алатки против спам',

# Special:BlankPage
'blankpage'              => 'Празна страница',
'intentionallyblankpage' => 'Оваа страница намерно е оставена празна',

# External image whitelist
'external_image_whitelist' => '  #Остави го овој ред таков каков што е<pre>
#Додавај фрагменти на регуларни изрази (само делот кој се наоѓа помеѓу //) подолу
#Ова ќе биде споредено со URL-та на надворешните (hotlinked) слики
#Оние кои одговараат ќе бидат прикажани како слики, до другите ќе биде прикажана само врската
#Се прави разлика помеѓу мали и големи букви

#Стави ги сите фрагменти на регуларни изрази над овој ред. Оставете го овој ред таков каков што е</pre>',

# Special:Tags
'tags'                    => 'Важечки ознаки за менување',
'tag-filter'              => '[[Special:Tags|Филтер за ознаки]]:',
'tag-filter-submit'       => 'Филтер',
'tags-title'              => 'Ознаки',
'tags-intro'              => 'На оваа страница е даден список на ознаки со кои програмската опрема може да ги означи измените и нивното значење.',
'tags-tag'                => 'Име на ознака',
'tags-display-header'     => 'Изглед во списоците на промени',
'tags-description-header' => 'Целосен опис на значењето',
'tags-hitcount-header'    => 'Означени промени',
'tags-edit'               => 'уреди',
'tags-hitcount'           => '$1 {{PLURAL:$1|промена|промени}}',

# Special:ComparePages
'comparepages'     => 'Спореди страници',
'compare-selector' => 'Споредба на ревизии на страници',
'compare-page1'    => 'Страница 1',
'compare-page2'    => 'Страница 2',
'compare-rev1'     => 'Ревизија 1',
'compare-rev2'     => 'Ревизија 2',
'compare-submit'   => 'Спореди',

# Database error messages
'dberr-header'      => 'Ова вики не функционира како што треба',
'dberr-problems'    => 'Жалиме! Ова мрежно место се соочува со технички потешкотии.',
'dberr-again'       => 'Почекајте неколку минути и обидете се повторно.',
'dberr-info'        => '(Не може да се поврзам со опслужувачот на базата на податоци: $1)',
'dberr-usegoogle'   => 'Во меѓувреме можете да се обидете да пребарувате со Google.',
'dberr-outofdate'   => 'Да напоменеме дека нивните индекси на нашата содржина можат да бидат застарени.',
'dberr-cachederror' => 'Следнава содржина е кеширана копија на бараната страница, која може да е застарена.',

# HTML forms
'htmlform-invalid-input'       => 'Има проблеми со дел од вашиот внес',
'htmlform-select-badoption'    => 'Вредноста која ја наведовте не е важечка.',
'htmlform-int-invalid'         => 'Вредноста која ја наведовте не е цел број.',
'htmlform-float-invalid'       => 'Вредноста која ја наведовте не е број.',
'htmlform-int-toolow'          => 'Вредноста која ја наведовте е под минимумот од $1',
'htmlform-int-toohigh'         => 'Вредноста која ја наведовте е над максимумот од $1',
'htmlform-required'            => 'Се бара оваа вредност',
'htmlform-submit'              => 'Зачувај',
'htmlform-reset'               => 'Откажи промени',
'htmlform-selectorother-other' => 'Друго',

# SQLite database support
'sqlite-has-fts' => '$1 со поддршка за пребарување по цели текстови',
'sqlite-no-fts'  => '$1 без поддршка за пребарување по цели текстови',

# Special:DisableAccount
'disableaccount'             => 'Оневозможи корисничка сметка',
'disableaccount-user'        => 'Корисничко име:',
'disableaccount-reason'      => 'Причина:',
'disableaccount-confirm'     => "Оневозможување на оваа корисничка сметка.
Корисникот нема да може да се најавува, да става нова лозинка или да прима известувања по е-пошта.
Ако корисникот е некаде најавен во моментот, тогаш оваа постапка веднаш ќе го одјави.
''Имајте предвид дека оневозможувањето на сметки не може да се врати без интервенција на системски администратор.''",
'disableaccount-mustconfirm' => 'Мора да потврдите дека сакате да ја оневозможите сметкава.',
'disableaccount-nosuchuser'  => 'Корисничката сметка „$1“ не постои.',
'disableaccount-success'     => 'Корисничката сметка „$1“ е трајно оневозможена.',
'disableaccount-logentry'    => 'трајно оневозможена корисничката сметка [[$1]].',

# Special:UploadStash
'uploadstash'          => 'Скриено подигање',
'uploadstash-summary'  => 'Оваа страница овозможува пристап до податотеки што се подигнати (или во фаза на подигање), но кои сè уште не се објавени на викито. Ваквите податотеки не се видливи за никого, освен оној што ги подигнал.',
'uploadstash-clear'    => 'Исчисти скриени податотеки',
'uploadstash-nofiles'  => 'Немате скриени податотеки.',
'uploadstash-badtoken' => 'Не успеав да го извршам бараното дејство, можеби поради тоа што вашиот сигурносен жетон е истечен. Обидете се повторно.',
'uploadstash-errclear' => 'Чистењето на податотеките не успеа.',
'uploadstash-refresh'  => 'ПРевчитај го списокот на податотеки',

);

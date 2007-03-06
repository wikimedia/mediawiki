<?php
/** Bulgarian (Български)
 *
 * @addtogroup Language
 */
$namespaceNames = array(
	NS_MEDIA            => 'Медия',
	NS_SPECIAL          => 'Специални',
	NS_MAIN             => '',
	NS_TALK             => 'Беседа',
	NS_USER             => 'Потребител',
	NS_USER_TALK        => 'Потребител_беседа',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_беседа',
	NS_IMAGE            => 'Картинка',
	NS_IMAGE_TALK       => 'Картинка_беседа',
	NS_MEDIAWIKI        => 'МедияУики',
	NS_MEDIAWIKI_TALK   => 'МедияУики_беседа',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Шаблон_беседа',
	NS_HELP             => 'Помощ',
	NS_HELP_TALK        => 'Помощ_беседа',
	NS_CATEGORY         => 'Категория',
	NS_CATEGORY_TALK    => 'Категория_беседа'
);

$skinNames = array(
	'standard' => 'Класика',
	'nostalgia' => 'Носталгия',
	'cologneblue' => 'Кьолнско синьо',
	'smarty' => 'Падингтън',
	'montparnasse' => 'Монпарнас',
	'davinci' => 'ДаВинчи',
	'mono' => 'Моно',
	'monobook' => 'Монобук',
	'myskin' => 'Мой облик',
);

$datePreferences = false;

$bookstoreList = array(
	'books.bg'       => 'http://www.books.bg/ISBN/$1',
	'Пингвините' => 'http://www.pe-bg.com/?cid=3&search_q=$1&where=ISBN&x=0&y=0**',
	'Книжарница Труд' => 'http://www.trud.cc/books/searchdisplay.asp?action=search&type=bigsearch&qryString=$1'
	);

$magicWords = array(
#   ID                                 CASE  SYNONYMS
	'redirect'               => array( 0, '#redirect', '#пренасочване', '#виж' ),
	'notoc'                  => array( 0, '__NOTOC__', '__БЕЗСЪДЪРЖАНИЕ__' ),
	'nogallery'              => array( 0,    '__NOGALLERY__', '__БЕЗГАЛЕРЕЯ__'),
	'forcetoc'               => array( 0, '__FORCETOC__', '__СЪССЪДЪРЖАНИЕ__' ),
	'toc'                    => array( 0, '__TOC__', '__СЪДЪРЖАНИЕ__'),
	'noeditsection'          => array( 0, '__NOEDITSECTION__', '__БЕЗ_РЕДАКТИРАНЕ_НА_РАЗДЕЛИ__'),
	'start'                  => array( 0, '__START__', '__НАЧАЛО__'),
	'currentmonth'           => array( 1, 'CURRENTMONTH', 'ТЕКУЩМЕСЕЦ'),
	'currentmonthname'       => array( 1, 'CURRENTMONTHNAME', 'ТЕКУЩМЕСЕЦИМЕ' ),
	'currentmonthnamegen'    => array( 1, 'CURRENTMONTHNAMEGEN', 'ТЕКУЩМЕСЕЦИМЕРОД'),
	'currentmonthabbrev'     => array( 1, 'CURRENTMONTHABBREV', 'ТЕКУЩМЕСЕЦСЪКР'),
	'currentday'             => array( 1, 'CURRENTDAY', 'ТЕКУЩДЕН'),
	'currentday2'            => array( 1,    'CURRENTDAY2', 'ТЕКУЩДЕН_2'),
	'currentdayname'         => array( 1, 'CURRENTDAYNAME', 'ТЕКУЩДЕНИМЕ'),
	'currentyear'            => array( 1, 'CURRENTYEAR', 'ТЕКУЩАГОДИНА'),
	'currenttime'            => array( 1, 'CURRENTTIME', 'ТЕКУЩОВРЕМЕ'),
	'currenthour'            => array( 1,    'CURRENTHOUR', 'ТЕКУЩЧАС' ),
	'localmonth'             => array( 1,    'LOCALMONTH'             ),
	'localmonthname'         => array( 1,    'LOCALMONTHNAME'         ),
	'localmonthnamegen'      => array( 1,    'LOCALMONTHNAMEGEN'      ),
	'localmonthabbrev'       => array( 1,    'LOCALMONTHABBREV'       ),
	'localday'               => array( 1,    'LOCALDAY'               ),
	'localday2'              => array( 1,    'LOCALDAY2'              ),
	'localdayname'           => array( 1,    'LOCALDAYNAME'           ),
	'localyear'              => array( 1,    'LOCALYEAR'              ),
	'localtime'              => array( 1,    'LOCALTIME'              ),
	'localhour'              => array( 1,    'LOCALHOUR'              ),
	'numberofpages'          => array( 1,    'NUMBEROFPAGES', 'БРОЙСТРАНИЦИ'),
	'numberofarticles'       => array( 1, 'NUMBEROFARTICLES', 'БРОЙСТАТИИ'),
	'numberoffiles'          => array( 1, 'NUMBEROFFILES', 'БРОЙФАЙЛОВЕ'),
	'numberofusers'          => array( 1,    'NUMBEROFUSERS', 'БРОЙПОТРЕБИТЕЛИ'),
	'pagename'               => array( 1, 'PAGENAME', 'СТРАНИЦА'),
	'pagenamee'              => array( 1, 'PAGENAMEE', 'СТРАНИЦАИ'),
	'namespace'              => array( 1, 'NAMESPACE', 'ИМЕННОПРОСТРАНСТВО'),
	'namespacee'             => array( 1,    'NAMESPACEE', 'ИМЕННОПРОСТРАНСТВО2'),
	'talkspace'              => array( 1,    'TALKSPACE'              ),
	'talkspacee'             => array( 1,    'TALKSPACEE'              ),
	'subjectspace'           => array( 1,    'SUBJECTSPACE', 'ARTICLESPACE'),
	'subjectspacee'          => array( 1,    'SUBJECTSPACEE', 'ARTICLESPACEE'),
	'fullpagename'           => array( 1,    'FULLPAGENAME', 'ПЪЛНО_НАЗВАНИЕ_НА_СТРАНИЦИ'),
	'fullpagenamee'          => array( 1,    'FULLPAGENAMEE', 'ПЪЛНО_НАЗВАНИЕ_НА_СТРАНИЦИ2'),
	'subpagename'            => array( 1,    'SUBPAGENAME', 'НАЗВАНИЕ_ПОДСТРАНИЦИ'),
	'subpagenamee'           => array( 1,    'SUBPAGENAMEE', 'НАЗВАНИЕ_ПОДСТРАНИЦИ2'),
	'basepagename'           => array( 1,    'BASEPAGENAME'           ),
	'basepagenamee'          => array( 1,    'BASEPAGENAMEE'          ),
	'talkpagename'           => array( 1,    'TALKPAGENAME', 'НАЗВАНИЕ_ДИСКУСИОННА_СТРАНИЦА'),
	'talkpagenamee'          => array( 1,    'TALKPAGENAMEE', 'НАЗВАНИЕ_ДИСКУСИОННА_СТРАНИЦА2'),
	'subjectpagename'        => array( 1,    'SUBJECTPAGENAME', 'ARTICLEPAGENAME'),
	'subjectpagenamee'       => array( 1,    'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE'),
	'msg'                    => array( 0,    'MSG:', 'СЪОБЩ:'),
	'subst'                  => array( 0, 'SUBST:', 'ЗАМЕСТ:'),
	'msgnw'                  => array( 0, 'MSGNW:', 'СЪОБЩΝW:'),
	'end'                    => array( 0, '__END__', '__КРАЙ__'),
	'img_thumbnail'          => array( 1, 'thumbnail', 'thumb', 'мини'),
	'img_manualthumb'        => array( 1, 'thumbnail=$1', 'thumb=$1', 'мини=$1'),
	'img_right'              => array( 1, 'right', 'вдясно', 'дясно', 'д'),
	'img_left'               => array( 1, 'left', 'вляво', 'ляво', 'л'),
	'img_none'               => array( 1, 'none', 'н'),
	'img_width'              => array( 1, '$1px', '$1пкс' , '$1п'),
	'img_center'             => array( 1, 'center', 'centre', 'център', 'центр', 'ц' ),
	'img_framed'             => array( 1, 'framed', 'enframed', 'frame', 'рамка', 'врамка' ),
	'img_page'               => array( 1,    'page=$1', 'page $1'),
	'int'                    => array( 0, 'INT:', 'ВЪТР:'),
	'sitename'               => array( 1, 'SITENAME', 'ИМЕНАСАЙТА'),
	'ns'                     => array( 0, 'NS:', 'ИП:'                    ),
	'localurl'               => array( 0, 'LOCALURL:', 'ЛОКАЛЕНАДРЕС:'),
	'localurle'              => array( 0, 'LOCALURLE:', 'ЛОКАЛЕНАДРЕСИ:'),
	'server'                 => array( 0, 'SERVER', 'СЪРВЪР'),
	'servername'             => array( 0, 'SERVERNAME', 'ИМЕНАСЪРВЪРА'),
	'scriptpath'             => array( 0, 'SCRIPTPATH', 'ПЪТДОСКРИПТА'),
	'grammar'                => array( 0, 'GRAMMAR:', 'ГРАМАТИКА:'),
	'notitleconvert'         => array( 0, '__NOTITLECONVERT__', '__NOTC__'),
	'nocontentconvert'       => array( 0, '__NOCONTENTCONVERT__', '__NOCC__'),
	'currentweek'            => array( 1, 'CURRENTWEEK', 'ТЕКУЩАСЕДМИЦА'),
	'currentdow'             => array( 1, 'CURRENTDOW', 'ТЕКУЩ_ДЕН_ОТ_СЕДМИЦАТА'),
	'localweek'              => array( 1,    'LOCALWEEK'              ),
	'localdow'               => array( 1,    'LOCALDOW'               ),
	'revisionid'             => array( 1, 'REVISIONID', 'ИД_НА_ВЕРСИЯТА'),
	'revisionday'            => array( 1,    'REVISIONDAY', 'ДЕН__НА_ВЕРСИЯТА'),
	'revisionday2'           => array( 1,    'REVISIONDAY2', 'ДЕН__НА_ВЕРСИЯТА2'),
	'revisionmonth'          => array( 1,    'REVISIONMONTH', 'МЕСЕЦ__НА_ВЕРСИЯТА'),
	'revisionyear'           => array( 1,    'REVISIONYEAR', 'ГОДИНА__НА_ВЕРСИЯТА'),
	'revisiontimestamp'      => array( 1,    'REVISIONTIMESTAMP'      ),
	'plural'                 => array( 0,    'PLURAL:', 'МНОЖЕСТВЕНО_ЧИСЛО:'),
	'fullurl'                => array( 0,    'FULLURL:', 'ПЪЛЕН_АДРЕС:'),
	'fullurle'               => array( 0,    'FULLURLE:', 'ПЪЛЕН_АДРЕС2:'),
	'lcfirst'                => array( 0,    'LCFIRST:', 'ПЪРВА_БУКВА_МАЛКА:'),
	'ucfirst'                => array( 0,    'UCFIRST:', 'ПЪРВА_БУКВА_ГОЛЯМА:'),
	'lc'                     => array( 0,    'LC:', 'МАЛКИ_БУКВИ:'),
	'uc'                     => array( 0,    'UC:', 'ГОЛЕМИ_БУКВИ:'),
	'raw'                    => array( 0,    'RAW:', 'НЕОБРАБ:'),
	'displaytitle'           => array( 1,    'DISPLAYTITLE', 'ПОКАЖИ_ЗАГЛАВИЕ'),
	'rawsuffix'              => array( 1,    'R'                      ),
	'newsectionlink'         => array( 1,    '__NEWSECTIONLINK__', '__ССЫЛКА_НА_НОВЫЙ_РАЗДЕЛ__'),
	'currentversion'         => array( 1,    'CURRENTVERSION'         ),
	'urlencode'              => array( 0,    'URLENCODE:'             ),
	'anchorencode'           => array( 0,    'ANCHORENCODE'           ),
	'currenttimestamp'       => array( 1,    'CURRENTTIMESTAMP'       ),
	'localtimestamp'         => array( 1,    'LOCALTIMESTAMP'         ),
	'directionmark'          => array( 1,    'DIRECTIONMARK', 'DIRMARK' ),
	'language'               => array( 0,    '#LANGUAGE:'             ),
	'contentlanguage'        => array( 1,    'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'       => array( 1,    'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'         => array( 1,    'NUMBEROFADMINS'         ),
	'formatnum'              => array( 0,    'FORMATNUM'              ),
	'padleft'                => array( 0,    'PADLEFT'                ),
	'padright'               => array( 0,    'PADRIGHT'               ),
	'special'                => array( 0,    'special',               ),
	'defaultsort'            => array( 1,    'DEFAULTSORT:'           ),
);

/**
 * Alternate names of special pages. All names are case-insensitive. The first
 * listed alias will be used as the default. Aliases from the fallback
 * localisation (usually English) will be included by default.
 *
 * This array may be altered at runtime using the LangugeGetSpecialPageAliases
 * hook.
 */
$specialPageAliases = array(
	'DoubleRedirects'           => array( 'DoubleRedirects', 'Двойни_пренасочвания'),
	'BrokenRedirects'           => array( 'BrokenRedirects', 'Невалидни_пренасочвания' ),
	'Disambiguations'           => array( 'Disambiguations', 'Пояснителни_страници' ),
	'Userlogin'                 => array( 'Userlogin', 'Регистриране_или_влизане' ),
	'Userlogout'                => array( 'Userlogout', 'Излизане'),
	'Preferences'               => array( 'Preferences', 'Настройки' ),
	'Watchlist'                 => array( 'Watchlist', 'Списък_за_наблюдение'),
	'Recentchanges'             => array( 'Recentchanges', 'Последни_промени'),
	'Upload'                    => array( 'Upload', 'Качване'),
	'Imagelist'                 => array( 'Imagelist', 'Списък_на_картинките' ),
	'Newimages'                 => array( 'Newimages', 'Галерия_на_новите_файлове'),
	'Listusers'                 => array( 'Listusers', 'Списък_на_потребителите' ),
	'Statistics'                => array( 'Statistics', 'Статистика'),
	'Randompage'                => array( 'Random', 'Randompage', 'Случайна_статия'),
	'Lonelypages'               => array( 'Lonelypages', 'Страници_сираци'),
	'Uncategorizedpages'        => array( 'Uncategorizedpages', 'Некатегоризирани_страници' ),
	'Uncategorizedcategories'   => array( 'Uncategorizedcategories', 'Некатегоризирани_категории' ),
	'Uncategorizedimages'       => array( 'Uncategorizedimages', 'Некатегоризирани_картинки' ),
	'Unusedcategories'          => array( 'Unusedcategories', 'Неизползвани_категории'),
	'Unusedimages'              => array( 'Unusedimages', 'Неизползвани_картинки'),
	'Wantedpages'               => array( 'Wantedpages', 'Желани_страници'),
	'Wantedcategories'          => array( 'Wantedcategories', 'Желани_категории'),
	'Mostlinked'                => array( 'Mostlinked', 'Най-препращани_страници' ),
	'Mostlinkedcategories'      => array( 'Mostlinkedcategories', 'Най-препращани_категории'),
	'Mostcategories'            => array( 'Mostcategories', 'Страници_с_най-много_категории'),
	'Mostimages'                => array( 'Mostimages', 'Най-препращани_картинки' ),
	'Mostrevisions'             => array( 'Mostrevisions', 'Страници_с_най-много_версии'),
	'Shortpages'                => array( 'Shortpages', 'Кратки_страници' ),
	'Longpages'                 => array( 'Longpages', 'Дълги_страници' ),
	'Newpages'                  => array( 'Newpages', 'Нови_страници' ),
	'Ancientpages'              => array( 'Ancientpages', 'Стари_статии'),
	'Deadendpages'              => array( 'Deadendpages', 'Задънени_страници'),
	'Allpages'                  => array( 'Allpages', 'Всички_страници'),
	'Prefixindex'               => array( 'Prefixindex', 'Азбучен_списък_на_представки') ,
	'Ipblocklist'               => array( 'Ipblocklist', 'Списък_на_блокирани_IP-адреси_и_потребители' ),
	'Specialpages'              => array( 'Specialpages', 'Специални_страници'),
	'Contributions'             => array( 'Contributions', 'Приноси'),
	'Emailuser'                 => array( 'Emailuser', 'Пращане_писмо_на_потребителя'),
	'Whatlinkshere'             => array( 'Whatlinkshere', 'Какво_сочи_насам'),
	'Recentchangeslinked'       => array( 'Recentchangeslinked', 'Свързани_промени'),
	'Movepage'                  => array( 'Movepage', 'Преместване_на_страница'),
	'Booksources'               => array( 'Booksources', 'Източници_на_книги'),
	'Categories'                => array( 'Categories', 'Категории'),
	'Export'                    => array( 'Export', 'Изнасяне_на_страници'),
	'Version'                   => array( 'Version', 'Версия'),
	'Allmessages'               => array( 'Allmessages', 'Системни_съобщения'),
	'Log'                       => array( 'Log', 'Logs', 'Дневници'),
	'Blockip'                   => array( 'Blockip', 'Блокиране_на_потребител'),
	'Undelete'                  => array( 'Undelete', 'Преглед_на_изтрити_страници'),
	'Import'                    => array( 'Import', 'Внасяне_на_страници' ),
	'Lockdb'                    => array( 'Lockdb', 'Заключване_на_базата_от_данни'),
	'Unlockdb'                  => array( 'Unlockdb', 'Отключване_на_базата_от_данни'),
	'Userrights'                => array( 'Userrights', 'Управление_на_потребителските_права'),
	'MIMEsearch'                => array( 'MIMEsearch', 'MIME-търсене' ),
	'Unwatchedpages'            => array( 'Unwatchedpages', 'Ненаблюдавани_страници' ),
	'Listredirects'             => array( 'Listredirects', 'Списък_на_пренасочванията'),
	'Revisiondelete'            => array( 'Revisiondelete', 'Изтриване/възстановяване_на_редакции' ),
	'Unusedtemplates'           => array( 'Unusedtemplates', 'Неизползвани_шаблони'),
	'Randomredirect'            => array( 'Randomredirect', 'Случайно_пренасочване'),
	'Mypage'                    => array( 'Mypage', 'Моята_страница'),
	'Mytalk'                    => array( 'Mytalk', 'Моята_беседа'),
	'Mycontributions'           => array( 'Mycontributions', 'Моите_приноси'),
	'Listadmins'                => array( 'Listadmins', 'Списък_на_администраторите'),
	'Popularpages'              => array( 'Popularpages', 'Известни_страници' ),
	'Search'                    => array( 'Search', 'Търсене' ),
);

$linkTrail = '/^([a-zабвгдежзийклмнопрстуфхцчшщъыьэюя]+)(.*)$/sDu';

$separatorTransformTable = array(',' => "\xc2\xa0", '.' => ',' );

$messages = array(
# User preference toggles
'tog-underline'               => 'Подчертаване на препратките',
'tog-highlightbroken'         => 'Показване на невалидните препратки <a href="#" class="new">така</a> (алтернативно: така<a href="#" class="internal">?</a>)',
'tog-justify'                 => 'Двустранно подравняване на абзаците',
'tog-hideminor'               => 'Скриване на малки редакции в последните промени',
'tog-extendwatchlist'         => "Разширяване на списъка, така че да показва '''всички''' промени",
'tog-usenewrc'                => 'Подобряване на последните промени (Javascript)',
'tog-numberheadings'          => 'Номериране на заглавията',
'tog-showtoolbar'             => 'Помощна лента за редактиране (Javascript)',
'tog-editondblclick'          => 'Редактиране при двойно щракване (Javascript)',
'tog-editsection'             => 'Възможност за редактиране на раздел чрез препратка [редактиране]',
'tog-editsectiononrightclick' => 'Възможност за редактиране на раздел при щракване с десния бутон върху заглавие на раздел (Javascript)',
'tog-showtoc'                 => 'Показване на съдържание (за страници с повече от три раздела)',
'tog-rememberpassword'        => 'Запомняне между сесиите',
'tog-editwidth'               => 'Максимална ширина на кутията за редактиране',
'tog-watchcreations'          => 'Добавяне на страниците, които създавам към моя списък за наблюдение',
'tog-watchdefault'            => 'Добавяне на редактираните страници към списъка за наблюдение',
'tog-watchmoves'              => 'Добавяне към списъка на страниците, които премествам',
'tog-watchdeletion'           => 'Добавяне към списъка на страниците, които изтривам',
'tog-minordefault'            => 'Отбелязване на всички промени като малки по подразбиране',
'tog-previewontop'            => 'Показване на предварителния преглед преди текстовата кутия, а не след нея',
'tog-previewonfirst'          => 'Показване на предварителен преглед при първа редакция',
'tog-nocache'                 => 'Без складиране на страниците',
'tog-enotifwatchlistpages'    => 'Уведоми ме с e-mail за промяна по страница от списъка ми за наблюдение',
'tog-enotifusertalkpages'     => 'Уведоми ме с e-mail когато моята беседа е променена',
'tog-enotifminoredits'        => 'Уведоми ме с e-mail даже при малки промени',
'tog-enotifrevealaddr'        => 'Reveal my e-mail address in notification mails',
'tog-shownumberswatching'     => 'Показване на броя на потребителите, включили страницата в своя списък за наблюдение',
'tog-fancysig'                => 'Без превръщане на подписа в препратка към потребителската страница',
'tog-externaleditor'          => 'Използване на външен редактор по подразбиране',
'tog-externaldiff'            => 'Използване на външна програма за разлики по подразбиране',
'tog-showjumplinks'           => 'Enable "jump to" accessibility links',
'tog-uselivepreview'          => 'Използвайте бърз предварителен преглед (JavaScript) (експериментално)',
'tog-forceeditsummary'        => 'Предупреждаване при празно поле за резюме на редакцията',
'tog-watchlisthideown'        => 'Скриване на моите редакции в списъка',
'tog-watchlisthidebots'       => 'Скриване на редакциите на ботове в списъка',
'tog-watchlisthideminor'      => 'Скриване на малките промени в списъка',
'tog-nolangconversion'        => 'Disable variants conversion',
'tog-ccmeonemails'            => 'Изпращай ми копия на писмата, които пращам на другите потребители',

'underline-always'  => 'Винаги',
'underline-never'   => 'Никога',
'underline-default' => 'Според настройките на браузъра',

'skinpreview' => '(Предварителен преглед)',

# Dates
'sunday'        => 'неделя',
'monday'        => 'понеделник',
'tuesday'       => 'вторник',
'wednesday'     => 'сряда',
'thursday'      => 'четвъртък',
'friday'        => 'петък',
'saturday'      => 'събота',
'sun'           => 'Sun',
'mon'           => 'Mon',
'tue'           => 'Tue',
'wed'           => 'Wed',
'thu'           => 'Thu',
'fri'           => 'Fri',
'sat'           => 'Sat',
'january'       => 'януари',
'february'      => 'февруари',
'march'         => 'март',
'april'         => 'април',
'may_long'      => 'май',
'june'          => 'юни',
'july'          => 'юли',
'august'        => 'август',
'september'     => 'септември',
'october'       => 'октомври',
'november'      => 'ноември',
'december'      => 'декември',
'january-gen'   => 'януари',
'february-gen'  => 'февруари',
'march-gen'     => 'март',
'april-gen'     => 'април',
'may-gen'       => 'май',
'june-gen'      => 'юни',
'july-gen'      => 'юли',
'august-gen'    => 'август',
'september-gen' => 'септември',
'october-gen'   => 'октомври',
'november-gen'  => 'ноември',
'december-gen'  => 'декември',
'jan'           => 'яну',
'feb'           => 'фев',
'mar'           => 'мар',
'apr'           => 'апр',
'may'           => 'май',
'jun'           => 'юни',
'jul'           => 'юли',
'aug'           => 'авг',
'sep'           => 'сеп',
'oct'           => 'окт',
'nov'           => 'ное',
'dec'           => 'дек',

# Bits of text used by many pages
'categories'            => 'Категории',
'pagecategories'        => 'Категории',
'category_header'       => 'Страници в категория „$1“',
'subcategories'         => 'Подкатегории',
'category-media-header' => 'Файлове в категория "$1"',

'linkprefix'        => '/^(.*?)([a-zA-Z\x80-\xff]+)$/sD',
'mainpagetext'      => 'Уики-системата беше успешно инсталирана.',
'mainpagedocfooter' => 'Разгледайте [http://meta.wikimedia.org/wiki/Help:Contents ръководството] за подробна информация относно използването на софтуера.

== Първи стъпки ==

* [http://www.mediawiki.org/wiki/Help:Configuration_settings Конфигурационни настройки]
* [http://www.mediawiki.org/wiki/Help:FAQ ЧЗВ за МедияУики]
* [http://mail.wikimedia.org/mailman/listinfo/mediawiki-announce Пощенски списък относно нови версии на МедияУики]',

'about'          => 'За {{SITENAME}}',
'article'        => 'Страница',
'newwindow'      => '(отваря се в нов прозорец)',
'cancel'         => 'Отказ',
'qbfind'         => 'Търсене',
'qbbrowse'       => 'Избор',
'qbedit'         => 'Редактиране',
'qbpageoptions'  => 'Настройки за страницата',
'qbpageinfo'     => 'Информация за страницата',
'qbmyoptions'    => 'Моите настройки',
'qbspecialpages' => 'Специални страници',
'moredotdotdot'  => 'Още...',
'mypage'         => 'Моята страница',
'mytalk'         => 'Моята беседа',
'anontalk'       => 'Беседа за адреса',
'navigation'     => 'Навигация',

# Metadata in edit box
'metadata_help' => 'Метаданни (за пояснение виж [[{{ns:project}}:Metadata]]):',

'errorpagetitle'    => 'Грешка',
'returnto'          => 'Обратно към $1.',
'tagline'           => 'от {{SITENAME}}',
'help'              => 'Помощ',
'search'            => 'Търсене',
'searchbutton'      => 'Търсене',
'go'                => 'Отваряне',
'searcharticle'     => 'Отваряне',
'history'           => 'История',
'history_short'     => 'История',
'updatedmarker'     => 'има промяна (от последното ми влизане)',
'info_short'        => 'Информация',
'printableversion'  => 'Версия за печат',
'permalink'         => 'Постоянна препратка',
'print'             => 'Печат',
'edit'              => 'Редактиране',
'editthispage'      => 'Редактиране',
'delete'            => 'Изтриване',
'deletethispage'    => 'Изтриване',
'undelete_short'    => 'Възстановяване на $1 редакции',
'protect'           => 'Защита',
'protectthispage'   => 'Защита',
'unprotect'         => 'Сваляне на защитата',
'unprotectthispage' => 'Сваляне на защитата',
'newpage'           => 'Нова страница',
'talkpage'          => 'Дискусионна страница',
'specialpage'       => 'Специална страница',
'personaltools'     => 'Лични инструменти',
'postcomment'       => 'Оставяне на съобщение',
'articlepage'       => 'Преглед на страница',
'talk'              => 'Беседа',
'views'             => 'Прегледи',
'toolbox'           => 'Инструменти',
'userpage'          => 'Потребителска страница',
'projectpage'       => 'Основна страница',
'imagepage'         => 'Преглед на файл',
'mediawikipage'     => 'Показване на страницата със съобщенията',
'templatepage'      => 'Преглед на страницата със шаблона',
'viewhelppage'      => 'Получете справка',
'categorypage'      => 'Преглеждане на страницата с категориите',
'viewtalkpage'      => 'Преглед на беседа',
'otherlanguages'    => 'На други езици',
'redirectedfrom'    => '(пренасочване от $1)',
'redirectpagesub'   => 'Пренасочваща страница',
'lastmodifiedat'    => 'Последна промяна на страницата: $2, $1.', # $1 date, $2 time
'viewcount'         => 'Страницата е била преглеждана $1 пъти.',
'protectedpage'     => 'Защитена страница',
'jumpto'            => 'Направо към:',
'jumptonavigation'  => 'навигация',
'jumptosearch'      => 'търсене',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'За {{SITENAME}}',
'aboutpage'         => '{{ns:project}}:За {{SITENAME}}',
'bugreports'        => 'Съобщения за грешки',
'bugreportspage'    => '{{ns:project}}:Съобщения за грешки',
'copyright'         => 'Съдържанието е достъпно при условията на $1.',
'copyrightpagename' => 'авторските права в {{SITENAME}}',
'copyrightpage'     => '{{ns:project}}:Авторски права',
'currentevents'     => 'Текущи събития',
'currentevents-url' => 'Текущи събития',
'disclaimers'       => 'Условия за ползване',
'disclaimerpage'    => '{{ns:project}}:Условия за ползване',
'edithelp'          => 'Помощ при редактиране',
'edithelppage'      => '{{ns:help}}:Как_се_редактират_страници',
'faq'               => 'ЧЗВ',
'faqpage'           => '{{ns:project}}:ЧЗВ',
'helppage'          => '{{ns:help}}:Съдържание',
'mainpage'          => 'Начална страница',
'portal'            => 'Портал за общността',
'portal-url'        => '{{ns:project}}:Портал',
'privacy'           => 'Защита на личните данни',
'privacypage'       => '{{ns:project}}:Privacy_policy',
'sitesupport'       => 'Дарения',
'sitesupport-url'   => '{{ns:project}}:Подкрепа',

'badaccess'        => 'Грешка при достъп',
'badaccess-group0' => 'Нямате права да извършите исканото действие',
'badaccess-group1' => 'Исканото действие могат да изпълнят само потребители от група $1.',
'badaccess-group2' => 'Исканото действие могат да изпълнят само потребители от група $1.',
'badaccess-groups' => 'Исканото действие могат да изпълнят само потребители от група $1.',

'versionrequired'     => 'Изисква се версия $1 на МедияУики',
'versionrequiredtext' => 'За да използвате тази страница, е необходима версия $1 на МедияУики. Вижте [[Special:Version]].',

'ok'                  => 'Добре',
'pagetitle'           => '$1 — {{SITENAME}}',
'retrievedfrom'       => 'Взето от „$1“.',
'youhavenewmessages'  => 'Имате $1 ($2).',
'newmessageslink'     => 'нови съобщения',
'newmessagesdifflink' => 'разлика с предишната версия',
'editsection'         => 'редактиране',
'editold'             => 'редактиране',
'editsectionhint'     => 'редактиране на раздел:$1',
'toc'                 => 'Съдържание',
'showtoc'             => 'показване',
'hidetoc'             => 'скриване',
'thisisdeleted'       => 'Преглед или възстановяване на $1?',
'viewdeleted'         => 'Преглед на $1?',
'restorelink'         => '$1 изтрити редакции',
'feedlinks'           => 'Във вида:',
'feed-invalid'        => 'Невалиден формат на информацията',
'feed-atom'           => 'Atom',
'feed-rss'            => 'RSS',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Страница',
'nstab-user'      => 'Потребител',
'nstab-media'     => 'Медия',
'nstab-special'   => 'Специална страница',
'nstab-project'   => 'Проект',
'nstab-image'     => 'Файл',
'nstab-mediawiki' => 'Съобщение',
'nstab-template'  => 'Шаблон',
'nstab-help'      => 'Помощ',
'nstab-category'  => 'Категория',

# Main script and global functions
'nosuchaction'      => 'Няма такова действие',
'nosuchactiontext'  => 'Действието, указано от мрежовия адрес, не се разпознава от системата.',
'nosuchspecialpage' => 'Няма такава специална страница',
'nospecialpagetext' => 'Отправихте заявка за невалидна [[Special:Specialpages|специална страница]].',

# General errors
'error'                => 'Грешка',
'databaseerror'        => 'Грешка при работа с базата от данни',
'dberrortext'          => 'Възникна синтактична грешка при заявка към базата от данни.
Последната заявка към базата от данни беше:
<blockquote><tt>$1</tt></blockquote>
при функцията „<tt>$2</tt>“.
MySQL дава грешка „<tt>$3: $4</tt>“.',
'dberrortextcl'        => 'Възникна синтактична грешка при заявка към базата от данни.
Последната заявка беше:
„$1“
при функцията „$2“.
MySQL дава грешка „$3: $4“.',
'noconnect'            => '<p>В момента има технически трудности и не може да се осъществи връзка с базата от данни.</p>
<p>$1</p>
<p>Моля, опитайте отново по-късно. Извиняваме се за неудобството.</p>',
'nodb'                 => 'Неуспех при избирането на база от данни $1',
'cachederror'          => 'Показано е складирано копие на желаната страница, което евентуално може да е остаряло.',
'laggedslavemode'      => 'Внимание: Страницата може да не съдържа последните обновявания.',
'readonly'             => 'Базата от данни е затворена за промени',
'enterlockreason'      => 'Посочете причина за затварянето, като дадете и приблизителна оценка кога базата от данни ще бъде отново отворена',
'readonlytext'         => 'Базата от данни е временно затворена за промени – вероятно за рутинна поддръжка, след която ще бъде отново на разположение.
Администраторът, който я е затворил, дава следното обяснение:
$1',
'missingarticle'       => 'Текстът на страницата „$1“ не беше намерен в базата от данни.

Това обикновено е причинено от последване на остаряла разлика или препратка от историята към изтрита страница.

Ако не това е причината, е възможно да сте открили грешка в системата.
Моля, съобщете за това на администратор, като включите и името на страницата.',
'readonly_lag'         => 'Базата от данни беше автоматично заключена, докато подчинените сървъри успеят да се съгласуват с основния сървър.',
'internalerror'        => 'Вътрешна грешка',
'filecopyerror'        => 'Файлът „$1“ не можа да бъде копиран като „$2“.',
'filerenameerror'      => 'Файлът „$1“ не можа да бъде преименуван на „$2“.',
'filedeleteerror'      => 'Файлът „$1“ не можа да бъде изтрит.',
'filenotfound'         => 'Файлът „$1“ не беше намерен.',
'unexpected'           => 'Неочаквана стойност: „$1“=„$2“.',
'formerror'            => 'Възникна грешка при изпращане на формуляра',
'badarticleerror'      => 'Действието не може да се изпълни върху страницата.',
'cannotdelete'         => 'Указаната страница или файл не можа да бъде изтрит(а). Възможно е вече да е изтрит(а) от някой друг.',
'badtitle'             => 'Невалидно заглавие',
'badtitletext'         => 'Желаното заглавие на страница е невалидно, празно или неправилна препратка към друго уики.',
'perfdisabled'         => 'Съжаляваме! Това свойство е временно изключено,
защото забавя базата от данни дотам, че никой не може да използва уикито.',
'perfdisabledsub'      => 'Съхранен екземпляр от $1:', # obsolete?
'perfcached'           => 'Следните данни са извлечени от склада и затова може да не отговарят на текущото състояние:',
'perfcachedts'         => 'Данните са кеширани и актуализирани за последно на $1.',
'querypage-no-updates' => 'Обновяването на данните за тази страница е изключено. Данните за сега няма да бъдат обновявани.',
'wrong_wfQuery_params' => 'Невалидни аргументи за wfQuery()<br />
Функция: $1<br />
Заявка: $2',
'viewsource'           => 'Защитена страница',
'viewsourcefor'        => 'за $1',
'protectedpagetext'    => 'Тази страница е заключена за редактиране.',
'viewsourcetext'       => 'Можете да разгледате и да копирате кодa на страницата:',
'protectedinterface'   => 'Тази страница съдържа текст, нужен за работата на системата. Тя е защитена от редактиране, за да се предотвратят възможни злоупотреби.',
'editinginterface'     => "'''Внимание:''' Редактирате страница, съдържаща състемно съобщения MediaWiki. Нейната промяна ще повлияе на външния вид на интерфейса за другите потребители\"",
'sqlhidden'            => '(Заявка на SQL — скрита)',

# Login and logout pages
'logouttitle'                => 'Излизане на потребител',
'logouttext'                 => 'Излязохте от системата.

Можете да продължите да използвате {{SITENAME}} анонимно или да влезете отново като друг потребител. Обърнете внимание, че някои страници все още ще се показват така, сякаш сте влезли, докато не изтриете кеш-паметта на браузъра.',
'welcomecreation'            => '== Добре дошли, $1! ==

Вашата сметка беше успешно открита. Сега можете да промените настройките на {{SITENAME}} по Ваш вкус.',
'loginpagetitle'             => 'Влизане в системата',
'yourname'                   => 'Потребителско име',
'yourpassword'               => 'Парола',
'yourpasswordagain'          => 'Въведете повторно парола',
'remembermypassword'         => 'Запомняне на паролата',
'yourdomainname'             => 'Домейн',
'externaldberror'            => 'There was either an external authentication database error or you are not allowed to update your external account.',
'loginproblem'               => '<b>Имаше проблем с влизането Ви.</b><br />Опитайте отново!',
'alreadyloggedin'            => '<strong>$1, вече сте влезли в системата!</strong>',
'login'                      => 'Влизане',
'loginprompt'                => "За влизане в {{SITENAME}} е необходимо да въведете потребителското си име и парола и да натиснете бутона '''Влизане''', като за да бъде това успешно, бисквитките (cookies) трябва да са разрешени в браузъра Ви.

Ако все още не сте регистрирани (нямате открита сметка), лесно можете да сторите това, като просто въведете желаните от Вас потребителско име и парола (двукратно) и щракнете върху '''Регистриране'''.",
'userlogin'                  => 'Регистриране или влизане',
'logout'                     => 'Излизане',
'userlogout'                 => 'Излизане',
'notloggedin'                => 'Не сте влезли',
'nologin'                    => 'Нямате потребителско име? $1.',
'nologinlink'                => 'Създаване на сметка',
'createaccount'              => 'Регистриране',
'gotaccount'                 => 'Имате ли вече сметка? $1.',
'gotaccountlink'             => 'Влизане',
'createaccountmail'          => 'с писмо по електронната поща',
'badretype'                  => 'Въведените пароли не съвпадат.',
'userexists'                 => 'Въведеното потребителско име вече се използва. Моля, изберете друго име.',
'youremail'                  => 'Е-поща *',
'username'                   => 'Потребителско име:',
'uid'                        => 'Потребителски номер:',
'yourrealname'               => 'Истинско име *',
'yourlanguage'               => 'Език',
'yourvariant'                => 'Вариант',
'yournick'                   => 'Псевдоним (за подписи чрез ~~~~)',
'badsig'                     => 'Избраният псевдоним не е валиден. Проверете HTML-етикетите!',
'email'                      => 'Е-поща',
'prefs-help-email-enotif'    => 'Този адрес се използва и за да бъдете известени за промяна на страници, ако сте избрали тази възможност.',
'prefs-help-realname'        => '* <strong>Истинско име</strong> <em>(незадължително)</em>: Ако го посочите, на него ще бъдат приписани Вашите приноси.',
'loginerror'                 => 'Грешка при влизане',
'prefs-help-email'           => '* <strong>Електронна поща</strong> <em>(незадължително)</em>: Позволява на хората да се свържат с Вас, без да се налага да им съобщавате адреса си, а също може да се използва, за да Ви се изпрати нова парола, ако случайно забравите сегашната си.',
'nocookiesnew'               => 'Потребителската сметка беше създадена, но все още не сте влезли. {{SITENAME}} използва бисквитки при влизане на потребителите. Моля, разрешете бисквитките във Вашия браузър, тъй като те са забранени, и след това влезте с потребителското си име и парола.',
'nocookieslogin'             => '{{SITENAME}} използва бисквитки (cookies) за запис на влизанията. Моля, разрешете бисквитките във Вашия браузър, тъй като те са забранени, и опитайте отново.',
'noname'                     => 'Не указахте валидно потребителско име.',
'loginsuccesstitle'          => 'Успешно влизане',
'loginsuccess'               => 'Влязохте в {{SITENAME}} като „$1“.',
'nosuchuser'                 => 'Няма потребител с името „$1“.
Проверете изписването или се регистрирайте, използвайки долния формуляр.',
'nosuchusershort'            => 'Няма потребител с името „$1“. Проверете изписването.',
'nouserspecified'            => 'Трябва да посочите име на потребител.',
'wrongpassword'              => 'Въведената парола е невалидна (или липсва). Моля, опитайте отново.',
'wrongpasswordempty'         => 'Въведената парола е празна. Моля, опитайте отново.',
'mailmypassword'             => 'Изпращане на нова парола',
'passwordremindertitle'      => 'Напомняне за парола от {{SITENAME}}',
'passwordremindertext'       => 'Някой (най-вероятно Вие, от IP-адрес $1) помоли да Ви изпратим нова парола за влизане в {{SITENAME}}.
Паролата за потребителя „$2“ е „$3“.
Сега би трябвало да влезете в системата и да смените паролата си.',
'noemail'                    => 'Няма записана електронна поща за потребителя „$1“.',
'passwordsent'               => 'Нова парола беше изпратена на електронната поща на „$1“.
Моля, влезте отново, след като я получите.',
'blocked-mailpassword'       => 'Забранено е редактирането с този IP-адрес. Блокирана е и функцията за възстановяване на паролата.',
'eauthentsent'               => 'Писмото за потвърждение е изпратено на посочения адрес. В него са описани действията, които трябва да се извършат, 
за да потвърдите, че този адрес на електронна поща, действително е ваш.',
'throttled-mailpassword'     => 'Функцията за напомняне на паролата е използвана в течение на последните $1 часа. 
За предотвратяване на злоупотреби е разрешено да се изпраща не повече от едно напомняне в рамките на $1 часа.',
'signupend'                  => '<div style="float:left"><p>За да се регистрирате, въведете желаното потребителско име и парола (два пъти) и щракнете върху бутона „<b>Регистриране</b>“.</p>
<p>Потребителското име може да бъде на латиница или на кирилица, да съдържа интервали и други знаци. Първата му буква винаги е главна.</p>
<p>Следващият път е достатъчно да попълните само първите две полета и да щракнете върху „<b>Влизане</b>“.</p></div>',
'mailerror'                  => 'Грешка при изпращане на писмо: $1',
'acct_creation_throttle_hit' => 'Съжаляваме, създали сте вече $1 сметки и нямате право на повече.',
'emailauthenticated'         => 'Вашият email-адрес беше потвърден на $1.',
'emailnotauthenticated'      => 'Вашият адрес за електронна поща <strong>не е потвърден</strong>. Няма да получавате писма за никое от следните възможности.',
'noemailprefs'               => '<strong>Не е указан адрес на електронна поща</strong>, функциите няма да работят.',
'emailconfirmlink'           => 'Потвърдете вашия e-mail адрес',
'invalidemailaddress'        => 'Въведеният адрес не може да бъде приет, тъй като не съответства на формата на адрес за електронна поща. Моля, въведете коректен адрес или оставете полето празно.',
'accountcreated'             => 'Потребителската сметка е създадена',
'accountcreatedtext'         => 'Потребителската сметка за $1 е създадена',

# Password reset dialog
'resetpass'               => 'Смяна на паролата',
'resetpass_announce'      => 'You logged in with a temporary e-mailed code. To finish logging in, you must set a new password here:',
'resetpass_text'          => '<!-- Add text here -->',
'resetpass_header'        => 'Смяна на паролата',
'resetpass_submit'        => 'Set password and log in',
'resetpass_success'       => 'Вашата парола беше успешно сменена! Сега може да влезете.',
'resetpass_bad_temporary' => 'Invalid temporary password. You may have already successfully changed your password or requested a new temporary password.',
'resetpass_forbidden'     => 'Passwords cannot be changed on this wiki',
'resetpass_missing'       => 'No form data.',

# Edit page toolbar
'bold_sample'     => 'Получер текст',
'bold_tip'        => 'Получер (удебелен) текст',
'italic_sample'   => 'Курсивен текст',
'italic_tip'      => 'Курсивен (наклонен) текст',
'link_sample'     => 'Име на препратка',
'link_tip'        => 'Вътрешна препратка',
'extlink_sample'  => 'http://www.primer.com Име на препратката',
'extlink_tip'     => 'Външна препратка (не забравяйте http:// отпред)',
'headline_sample' => 'Заглавен текст',
'headline_tip'    => 'Заглавие',
'math_sample'     => 'Тук въведете формулата',
'math_tip'        => 'Математическа формула (LaTeX)',
'nowiki_sample'   => 'Тук въведете текст',
'nowiki_tip'      => 'Пренебрегване на форматиращите команди',
'image_sample'    => 'Пример.jpg',
'image_tip'       => 'Вмъкване на картинка',
'media_sample'    => 'Пример.ogg',
'media_tip'       => 'Препратка към файл',
'sig_tip'         => 'Вашият подпис заедно с времева отметка',
'hr_tip'          => 'Хоризонтална линия (използвайте пестеливо)',

# Edit pages
'summary'                   => 'Резюме',
'subject'                   => 'Тема/заглавие',
'minoredit'                 => 'Това е малка промяна.',
'watchthis'                 => 'Наблюдаване на страницата',
'savearticle'               => 'Съхранение',
'preview'                   => 'Предварителен преглед',
'showpreview'               => 'Предварителен преглед',
'showlivepreview'           => 'Бърз предварителен преглед',
'showdiff'                  => 'Показване на промените',
'anoneditwarning'           => "'''Внимание:''' Не сте влезли в системата. В историята на страницата ще бъде записан Вашият [[IP адрес]].",
'missingsummary'            => "'''Напомняне:''' Не сте въвели кратко описание на промените. При повторно натискане на бутона \"Съхранение\", вашата редакция ще бъде съхранене без коментар.",
'missingcommenttext'        => 'Моля, въведете по-долу вашето съобщение',
'missingcommentheader'      => "'''Забележка:''' Не сте въвели резюме за промените. Ако натиснете отново '''Съхранение''' редакцията ще бъде записана без резюме.",
'summary-preview'           => 'Предварителен преглед на резюмето',
'subject-preview'           => 'Предварителен преглед на заглавието',
'blockedtitle'              => 'Потребителят е блокиран',
'blockedtext'               => "Вашето потребителско име (или IP-адрес) е блокирано от $1.
Причината за това е:<br />''$2''<p>Можете да се свържете с $1 или с някой от останалите [[Project:Администратори|администратори]], за да обсъдите това.

Можете да използвате услугата „'''Пращане писмо на потребителя'''“ единствено, ако сте посочили валидна електронна поща в [[Special:Preferences|настройките]] си.

Вашият IP-адрес е $3. Моля, вмъквайте този адрес във всяко питане, което правите.",
'blockedoriginalsource'     => 'По-долу е показан текста на страница $1',
'blockededitsource'         => "Тектът на '''вашите редакции''' на '''$1''' е показан по-долу:",
'whitelistedittitle'        => 'Необходимо е да влезете, за да може да редактирате',
'whitelistedittext'         => 'Необходимо е да [[Special:Userlogin|влезете]], за да може да редактирате страници.',
'whitelistreadtitle'        => 'Необходимо е да влезете, за да може да четете страници',
'whitelistreadtext'         => 'Необходимо е да [[Special:Userlogin|влезете]], за да може да четете страници.',
'whitelistacctitle'         => 'Не ви е позволено да създавате сметка',
'whitelistacctext'          => 'За да Ви бъде позволено създаването на сметки, трябва да [[Special:Userlogin|влезете]] и да имате подходящото разрешение.',
'confirmedittitle'          => 'Необходимо е потвърджение на адреса на ел.поща',
'confirmedittext'           => 'Трябва да потвърдите вашия e-mail адрес преди да редактирате страници. Моля въведете и потвърдете адреса си на [[Special:Preferences|страницата с настройките]].',
'loginreqtitle'             => 'Изисква се влизане',
'loginreqlink'              => 'влизане',
'loginreqpagetext'          => 'Необходимо е да $1, за да може да разглеждате други страници.',
'accmailtitle'              => 'Паролата беше изпратена.',
'accmailtext'               => 'Паролата за „$1“ беше изпратена на $2.',
'newarticle'                => '(нова)',
'anontalkpagetext'          => "----
''Това е дискусионната страница на анонимен потребител, който  все още няма сметка или не я използва. Затова се налага да използваме IP-адрес, за да го/я идентифицираме. Такъв адрес може да се споделя от няколко потребители.''

''Ако сте анонимен потребител и мислите, че тези неуместни коментари са отправени към Вас, моля [[Special:Userlogin|регистрирайте се или влезте в системата]], за да избегнете евентуално бъдещо объркване с други анонимни потребители.''",
'noarticletext'             => "(Тази страница все още не съществува. Можете да я създадете, като щракнете на '''Редактиране'''.)",
'clearyourcache'            => "'''Бележка:''' След съхранението е необходимо да изтриете кеша на браузъра, за да видите промените:
'''Mozilla / Firefox / Safari:''' натиснете бутона ''Shift'' и щракнете върху ''Презареждане'' (''Reload''), или изберете клавишната комбинация ''Ctrl-Shift-R'' (''Cmd-Shift-R'' за Apple Mac);
'''IE:''' натиснете ''Ctrl'' и щракнете върху ''Refresh'', или клавишната комбинация ''CTRL-F5'';
'''Konqueror:''' щракнете върху ''Презареждане'' или натиснете ''F5'';
'''Opera:''' вероятно е необходимо да изчистите кеша през менюто ''Tools&rarr;Preferences''.",
'usercssjsyoucanpreview'    => '<strong>Съвет:</strong> Използвайте бутона „Предварителен преглед“, за да изпробвате новия код на css/js преди съхранението.',
'usercsspreview'            => "'''Не забравяйте, че това е само предварителен преглед на кода на CSS, страницата все още не е съхранена!'''",
'userjspreview'             => "'''Не забравяйте, че това е само изпробване/предварителен преглед на кода на Javascript, страницата все още не е съхранена!'''",
'userinvalidcssjstitle'     => "'''Внимание:''' Не е намерена тема \"\$1\". Помнете, че названието на потребителските страници .css и .js трябва да се състои от малки букви, пр. Потребител:Иван/monobook.css, а не Потребител:Иван/Monobook.css.",
'updated'                   => '(актуализирана)',
'note'                      => '<strong>Забележка:</strong>',
'previewnote'               => 'Не забравяйте, че това е само предварителен преглед и страницата все още не е съхранена!',
'previewconflict'           => 'Този предварителен преглед отразява текста в горната текстова кутия така, както би се показал, ако съхраните.',
'session_fail_preview'      => '<strong>За съжаление редакцията Ви не успя да бъде обработена, поради загуба на данните за текущата сесия. Моля, опитайте отново. Ако все още не работи, опитайте да излезете и да влезете наново.</strong>',
'session_fail_preview_html' => "<strong>Съжаляваме! Вашата редакция не беше записана поради изтичането на сесията ви.</strong>

''Because this wiki has raw HTML enabled, the preview is hidden as a precaution against JavaScript attacks.''

<strong>If this is a legitimate edit attempt, please try again. If it still doesn't work, try logging out and logging back in.</strong>",
'importing'                 => 'Внасяне на $1',
'editing'                   => 'Редактиране на „$1“',
'editinguser'               => 'Редактиране на „$1“',
'editingsection'            => 'Редактиране на „$1“ (раздел)',
'editingcomment'            => 'Редактиране на „$1“ (нов раздел)',
'editconflict'              => 'Различна редакция: $1',
'explainconflict'           => 'Някой друг вече е променил тази страница, откакто започнахте да я редактирате.
Горната текстова кутия съдържа текущия текст на страницата без Вашите промени, които са показани в долната кутия. За да бъдат и те съхранени, е необходимо ръчно да ги преместите в горното поле, тъй като <b>единствено</b> текстът в него ще бъде съхранен при натискането на бутона „Съхранение“.<br />',
'yourtext'                  => 'Вашият текст',
'storedversion'             => 'Съхранена версия',
'nonunicodebrowser'         => '<strong>ВНИМАНИЕ: Браузърът Ви не поддържа Уникод. За да можете спокойно да редактирате страници, всички символи, невключени в ASCII-таблицата, ще бъдат заменени с шестнадесетични кодове.</strong>',
'editingold'                => '<strong>ВНИМАНИЕ: Редактирате остаряла версия на страницата.
Ако съхраните, всякакви промени, направени след тази версия, ще бъдат изгубени.</strong>',
'yourdiff'                  => 'Разлики',
'copyrightwarning'          => '<div style="color:black; background-color:#FFFFEE; border:thin solid #999; padding:0.5em">
Моля, обърнете внимание на това, че всички приноси към {{SITENAME}} се публикуват при условията на $2 (за подробности вижте $1).
Ако не сте съгласни Вашата писмена работа да бъде променяна и разпространявана без ограничения, не я публикувайте.<br />

Също потвърждавате, че <strong>Вие</strong> сте написали материала или сте използвали <strong>свободни ресурси</strong> – <em>обществено достояние</em> или друг свободен източник.
Ако сте ползвали чужди материали, за които имате разрешение, непременно посочете източника.

<div style="color:#EE0000; background-color:#FFFFEE; font-weight:bold; font-size:1.1em; font-variant:small-caps; text-align:center;">Не публикувайте произведения с авторски права без разрешение!</div>
</div>',
'copyrightwarning2'         => '<div style="color:black; background-color:#FFFFEE; border:thin solid #999; padding:0.5em">
Моля, обърнете внимание на това, че всички приноси към {{SITENAME}} могат да бъдат редактирани, променяни или премахвани от останалите сътрудници.
Ако не сте съгласни Вашата писмена работа да бъде променяна без ограничения, не я публикувайте.<br />
Също потвърждавате, че <strong>Вие</strong> сте написали материала или сте използвали <strong>свободни ресурси</strong> – <em>обществено достояние</em> или друг свободен източник.
Ако сте ползвали чужди материали, за които имате разрешение, непременно посочете източника.

<div style="color:#ee0000; background-color:#ffffee; font-weight:bold; font-size:1.1em; font-variant:small-caps; text-align:center;">Не публикувайте произведения с авторски права без разрешение!</div>
</div>',
'longpagewarning'           => '<strong>ВНИМАНИЕ: Страницата има размер $1 килобайта; някои браузъри могат да имат проблеми при редактиране на страници по-големи от 32 КБ.
Моля, обмислете дали страницата не може да се раздели на няколко по-малки части.</strong>',
'longpageerror'             => '<strong>ERROR: The text you have submitted is $1 kilobytes
long, which is longer than the maximum of $2 kilobytes. It cannot be saved.</strong>',
'readonlywarning'           => '<strong>ВНИМАНИЕ: Базата от данни беше затворена за поддръжка, затова в момента промените Ви не могат да бъдат съхранени. Ако желаете, можете да съхраните страницата като текстов файл и да се опитате да я публикувате по-късно.</strong>',
'protectedpagewarning'      => '<strong>ВНИМАНИЕ: Страницата е защитена и само администратори могат да я редактират.
Моля, следвайте [[Project:Защитена страница|указанията за защитена страница]].</strong>',
'semiprotectedpagewarning'  => "'''Забележка''': Тази страница е защитена така, че само регистрирани потребители могат да я редактират.",
'templatesused'             => 'Шаблони, използвани на страницата:',
'templatesusedpreview'      => 'Шаблони, използвани в предварителния преглед:',
'templatesusedsection'      => 'Шаблони, използвани в този раздел:',
'template-protected'        => '(protected)',
'template-semiprotected'    => '(semi-protected)',
'nocreatetitle'             => 'Създаването на страници е ограничено',
'nocreatetext'              => 'На този сайт е ограничена възможността за създаването на нови страници. Може да се върнете назад и да редактирате съществуваща страница, [[Special:Userlogin|да се регистрирате или създадете нова потребителска сметка]].',

# "Undo" feature
'undo-success' => 'The edit can be undone. Please check the comparison below to verify that this is what you want to do, and then save the changes below to finish undoing the edit.',
'undo-failure' => 'The edit could not be undone due to conflicting intermediate edits.',
'undo-summary' => 'премахната редакция $1 на [[Special:Contributions/$2|$2]] ([[Потребител беседа:$2|беседа]])',

# Account creation failure
'cantcreateaccounttitle' => 'Невъзможно е да бъде създадена потребителска сметка.',
'cantcreateaccounttext'  => 'Създаване на потребителска сметка от IP адрес (<b>$1</b>) е блокирано.
Вероятно това е поради постоянен вандализъм, извършен от компютър от твоето училище или Интернет доставчик.',

# History pages
'revhistory'                  => 'История на версиите',
'viewpagelogs'                => 'Вижте списък на извършените административни действия по страницата в дневниците.',
'nohistory'                   => 'Няма редакционна история за тази страница.',
'revnotfound'                 => 'Версията не е открита',
'revnotfoundtext'             => 'Желаната стара версия на страницата не беше открита.
Моля, проверете адреса, който използвахте за достъп до страницата.',
'loadhist'                    => 'Зареждане история на страницата',
'currentrev'                  => 'Текуща версия',
'revisionasof'                => 'Версия от $1',
'revision-info'               => 'Версия от $1 на $2',
'previousrevision'            => '←По-стара версия',
'nextrevision'                => 'По-нова версия→',
'currentrevisionlink'         => 'преглед на текущата версия',
'cur'                         => 'тек',
'next'                        => 'след',
'last'                        => 'посл',
'orig'                        => 'ориг',
'histlegend'                  => '<i>Разлики:</i> Изберете версиите, които желаете да сравните, чрез превключвателите срещу тях и натиснете &lt;Enter&gt; или бутона за сравнение.<br />
<i>Легенда:</i> (<b>тек</b>) = разлика с текущата версия, (<b>посл</b>) = разлика с предишната версия, <b>м</b>&nbsp;=&nbsp;малка промяна',
'deletedrev'                  => '[изтрита]',
'histfirst'                   => 'Първи',
'histlast'                    => 'Последни',
'rev-deleted-comment'         => '(коментарът е изтрит)',
'rev-deleted-user'            => '(името на автора е изтрито)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
Тази версия на страницата е изтрита от общодостъпния архив. 
Възможно е обяснения да има в [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} дневника на изтриванията].
</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">
Тази версия на страницата е изтрита от общодостъпния архив. 
Като администратор на този сайт, вие можете да я видите;
Възможно е обяснения да има в [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} дневника на изтриванията].
</div>',
'rev-delundel'                => 'покажи/скрий',

'history-feed-title'          => 'Редакционна история',
'history-feed-item-nocomment' => '$1 at $2', # user at time

# Revision deletion
'revisiondelete'            => 'Изтриване/възстановяване на редакции',
'revdelete-nooldid-title'   => 'Не е зададена редакция',
'revdelete-nooldid-text'    => 'Не сте задали редакция или редакции за изпълнението на тази функция.',
'revdelete-selected'        => 'Избрана версия [[:$1]]:',
'revdelete-text'            => 'Изтритите версии ше се показват в историята на страницата,
но тяхното съдържание ще бъде недостъпно за обикновенните потребители.

Администраторите на това уики имат достъп до скритото съдържание и могат да го възстановят,
с изключение на случаите, когато има наложено допълнително ограничение.',
'revdelete-legend'          => 'Задайте ограничения:',
'revdelete-hide-text'       => 'Скрий текста на тази версия на страницата',
'revdelete-hide-comment'    => 'Скрий коментарите',
'revdelete-hide-user'       => 'Скрий името/IP-то на автора',
'revdelete-hide-restricted' => 'Приложи тези ограничения и към администраторите',
'revdelete-log'             => 'Коментар:',
'revdelete-submit'          => 'Приложи към избраната версия',
'revdelete-logentry'        => 'променена видимост на версията на страницата за [[$1]]',

# Diffs
'difference'                => '(Разлики между версиите)',
'loadingrev'                => 'зареждане на версии за функцията <em>разл</em>',
'lineno'                    => 'Ред $1:',
'editcurrent'               => 'Редактиране на текущата версия на страницата',
'selectnewerversionfordiff' => 'Избиране на нова версия за сравнение',
'selectolderversionfordiff' => 'Избиране на стара версия за сравнение',
'compareselectedversions'   => 'Сравнение на избраните версии',
'editundo'                  => 'връщане',
'diff-multi'                => '({{plural:$1|Една междинна версия не е показана|$1 междинни версии не са показани}}.)',

# Search results
'searchresults'         => 'Резултати от търсенето',
'searchresulttext'      => 'За повече информация относно {{SITENAME}}, вижте [[{{MediaWiki:helppage}}|{{int:help}}]].',
'searchsubtitle'        => 'За заявка „[[:$1]]“',
'searchsubtitleinvalid' => 'За заявка „$1“',
'badquery'              => 'Лошо формулирана заявка за търсене',
'badquerytext'          => 'Вашата заявка не можа да бъде обработена.
Вероятно сте се опитали да търсите дума с по-малко от три букви, което все още не се поддържа.
Възможно е и да сте сгрешили в изписването на израза, например: „риба и и везни“.
Моля, опитайте с нова заявка.',
'matchtotals'           => 'Заявката „$1“ отговаря на $2 заглавия на страници и на текста на $3 страници.',
'noexactmatch'          => "В {{SITENAME}} не съществува страница с това заглавие. Можете да я '''[[:$1|създадете]]'''.",
'titlematches'          => 'Съответствия в заглавията на страници',
'notitlematches'        => 'Няма съответствия в заглавията на страници',
'textmatches'           => 'Съответствия в текста на страници',
'notextmatches'         => 'Няма съответствия в текста на страници',
'prevn'                 => 'предишни $1',
'nextn'                 => 'следващи $1',
'viewprevnext'          => 'Преглед ($1) ($2) ($3).',
'showingresults'        => 'Показване на до <b>$1</b> резултата, като се започва от номер <b>$2</b>.',
'showingresultsnum'     => 'Показване на <b>$3</b> резултата, като се започва от номер <b>$2</b>.',
'nonefound'             => "'''Забележка''': Безрезултатните търсения често са причинени от това, че се търсят основни думи като „има“ или „от“, които не се индексират, или от това, че се търсят повече от една думи, тъй като се показват само страници, съдържащи всички зададени понятия.",
'powersearch'           => 'Търсене',
'powersearchtext'       => '
Търсене в именни пространства:<br />
$1<br />
$2 Показване на пренасочвания &nbsp; Търсене на $3 $4',
'searchdisabled'        => 'Търсенето в {{SITENAME}} е временно изключено поради голямото натоварване на сървъра. Междувременно можете да търсите чрез Google. Обърнете внимание обаче, че е възможно съхранените при тях страници да са остарели.',
'blanknamespace'        => '(Основно)',

# Preferences page
'preferences'              => 'Настройки',
'mypreferences'            => 'моите настройки',
'prefsnologin'             => 'Не сте влезли',
'prefsnologintext'         => 'Необходимо е да [[Special:Userlogin|влезете]], за да може да променяте потребителските си настройки.',
'prefsreset'               => 'Стандартните настройки бяха възстановени.',
'qbsettings'               => 'Лента за бърз избор',
'qbsettings-none'          => 'Без меню',
'qbsettings-fixedleft'     => 'Неподвижно вляво',
'qbsettings-fixedright'    => 'Неподвижно вдясно',
'qbsettings-floatingleft'  => 'Плаващо вляво',
'qbsettings-floatingright' => 'Плаващо вдясно',
'changepassword'           => 'Смяна на парола',
'skin'                     => 'Облик',
'math'                     => 'Математически формули',
'dateformat'               => 'Формат на датата',
'datedefault'              => 'Без предпочитание',
'datetime'                 => 'Дата и час',
'math_failure'             => 'Неуспех при разбора',
'math_unknown_error'       => 'непозната грешка',
'math_unknown_function'    => 'непозната функция',
'math_lexing_error'        => 'лексикална грешка',
'math_syntax_error'        => 'синтактична грешка',
'math_image_error'         => 'Превръщането към PNG не сполучи. Проверете дали latex, dvips и gs са правилно инсталирани.',
'math_bad_tmpdir'          => 'Невъзможно е писането или създаването на временна папка за математическите операции',
'math_bad_output'          => 'Невъзможно е писането или създаването на изходяща папка за математическите операции',
'math_notexvc'             => 'Липсва изпълнимият файл на texvc. Моля, прегледайте math/README за информация относно конфигурирането.',
'prefs-personal'           => 'Потребителски данни',
'prefs-rc'                 => 'Последни промени и мъничета',
'prefs-watchlist'          => 'Списък за наблюдение',
'prefs-watchlist-days'     => 'Брой дни, които да се показват в списъка за наблюдение:',
'prefs-watchlist-edits'    => 'Брой редакции, които се показват в разширения списък за наблюдение:',
'prefs-misc'               => 'Други настройки',
'saveprefs'                => 'Съхранение',
'resetprefs'               => 'Възстановяване на стандартните настройки',
'oldpassword'              => 'Стара парола',
'newpassword'              => 'Нова парола',
'retypenew'                => 'Нова парола повторно',
'textboxsize'              => 'Редактиране',
'rows'                     => 'Редове',
'columns'                  => 'Колони',
'searchresultshead'        => 'Търсене',
'resultsperpage'           => 'Резултати на страница',
'contextlines'             => 'Редове за резултат',
'contextchars'             => 'Знаци от контекста на ред',
'stubthreshold'            => 'Определяне като къси страници до',
'recentchangescount'       => 'Брой заглавия в последни промени',
'savedprefs'               => 'Вашите настройки бяха съхранени.',
'timezonelegend'           => 'Времева зона',
'timezonetext'             => '¹ Броят часове, с които Вашето местно време се различава от това на сървъра (UTC).',
'localtime'                => 'Местно време',
'timezoneoffset'           => 'Отместване¹',
'servertime'               => 'Време на сървъра',
'guesstimezone'            => 'Попълване чрез браузъра',
'allowemail'               => 'Възможност за получаване на писма от други потребители',
'defaultns'                => 'Търсене в тези именни пространства по подразбиране:',
'default'                  => 'по подразбиране',
'files'                    => 'Файлове',

# User rights
'userrights-lookup-user'     => 'Управляване на потребителските групи',
'userrights-user-editname'   => 'Въведете потребителско име:',
'editusergroup'              => 'Редактиране на потребителските групи',
'userrights-editusergroup'   => 'Редактиране на потребителските групи',
'saveusergroups'             => 'Съхранение на потребителските групи',
'userrights-groupsmember'    => 'Член на:',
'userrights-groupsavailable' => 'Групи на разположение:',
'userrights-groupshelp'      => 'Изберете групите, към които искате той да бъде прибавен или от които да бъде премахнат. Неизбраните групи няма да бъдат променени. Можете да отизберете група чрез <CTRL> + ляв бутон на мишката',

# Groups
'group'            => 'Потребителска група:',
'group-bot'        => 'Ботове',
'group-sysop'      => 'Администратори',
'group-bureaucrat' => 'Бюрократи',
'group-all'        => '(всички)',

'group-bot-member'        => 'Бот',
'group-sysop-member'      => 'Администратор',
'group-bureaucrat-member' => 'Бюрократ',

'grouppage-bot'        => '{{ns:project}}:Ботове',
'grouppage-sysop'      => '{{ns:project}}:Администратори',
'grouppage-bureaucrat' => '{{ns:project}}:Бюрократи',

# User rights log
'rightslog'      => 'Дневник на потребителските права',
'rightslogtext'  => 'Това е дневник на промените на потребителски права.',
'rightslogentry' => 'промени потребителската група на $1 от $2 в $3',
'rightsnone'     => '(никой)',

# Recent changes
'recentchanges'                     => 'Последни промени',
'recentchangestext'                 => 'Проследяване на последните промени в {{SITENAME}}.

Легенда: <b>тек</b> = разлика на текущата версия,
<b>ист</b> = история на версиите, <b>м</b>&nbsp;=&nbsp;малка промяна, <b class="newpage">Н</b>&nbsp;=&nbsp;новосъздадена страница',
'recentchanges-feed-description'    => 'Track the most recent changes to the wiki in this feed.',
'rcnote'                            => 'Показани са последните <strong>$1</strong> промени през последните <strong>$2</strong> дни.',
'rcnotefrom'                        => 'Дадени са промените от <b>$2</b> (до <b>$1</b> показани).',
'rclistfrom'                        => 'Показване на промени, като се започва от $1.',
'rcshowhideminor'                   => '$1 малки промени',
'rcshowhidebots'                    => '$1 ботове',
'rcshowhideliu'                     => '$1 влезли в системата потребители',
'rcshowhideanons'                   => '$1 анонимни потребители',
'rcshowhidepatr'                    => '$1 проверени редакции',
'rcshowhidemine'                    => '$1 моите приноси',
'rclinks'                           => 'Показване на последните $1 промени през последните $2 дни<br />$3',
'diff'                              => 'разл',
'hist'                              => 'ист',
'hide'                              => 'Скриване',
'show'                              => 'Показване',
'minoreditletter'                   => 'м',
'newpageletter'                     => 'Н',
'boteditletter'                     => 'б',
'sectionlink'                       => '→',
'number_of_watching_users_pageview' => '[$1 наблюдаващ(и) потребител(и)]',
'rc_categories'                     => 'Само от категории (разделител "|")',
'rc_categories_any'                 => 'Някоя',

# Recent changes linked
'recentchangeslinked' => 'Свързани промени',

# Upload
'upload'                      => 'Качване',
'uploadbtn'                   => 'Качване',
'reupload'                    => 'Повторно качване',
'reuploaddesc'                => 'Връщане към формуляра за качване.',
'uploadnologin'               => 'Не сте влезли',
'uploadnologintext'           => 'Необходимо е да [[Special:Userlogin|влезете]], за да може да качвате файлове.',
'upload_directory_read_only'  => 'Сървърът няма достъп за писане до папката за качване „$1“.',
'uploaderror'                 => 'Грешка при качване',
'uploadtext'                  => "
Използвайте долния формуляр, за да качвате файлове, които ще можете да използвате в страниците.
В повечето браузъри ще видите бутон „Browse...“ (ако използвате преведен интерфейс, можете да видите „Избор на файл...“, „Избор...“ и др.), който ще отвори основния за вашата операционна система диалогов прозорец за избиране на файл.

За да включите картинка (файл) в страница, използвайте една от следните препратки: '''<nowiki>[[{{ns:Image}}:картинка.jpg]]</nowiki>''' или '''<nowiki>[[{{ns:Image}}:картинка.png|алтернативен текст]]</nowiki>''' или '''<nowiki>[[{{ns:Media}}:звук.ogg]]</nowiki>''' за музикални файлове.

За да прегледате съществуващите в базата от данни файлове, разгледайте [[Special:Imagelist|списъка с качените файлове]].
Качванията и изтриванията се записват в [[Special:Log/upload|дневника на качванията]].",
'uploadlog'                   => 'дневник на качванията',
'uploadlogpage'               => 'Дневник на качванията',
'uploadlogpagetext'           => 'Списък на последните качвания.',
'filename'                    => 'Име на файл',
'filedesc'                    => 'Описание',
'fileuploadsummary'           => 'Описание:',
'filestatus'                  => 'Авторско право',
'filesource'                  => 'Изходен код',
'uploadedfiles'               => 'Качени файлове',
'ignorewarning'               => 'Съхраняване на файла въпреки предупреждението.',
'ignorewarnings'              => 'Пренебрегване на всякакви предупреждения',
'minlength'                   => 'Имената на файловете трябва да съдържат поне три знака.',
'illegalfilename'             => 'Името на файла „$1“ съдържа знаци, които не са позволени в заглавия на страници. Моля, преименувайте файла и се опитайте да го качите отново.',
'badfilename'                 => 'Файлът беше преименуван на „$1“.',
'large-file'                  => 'Не се препоръчва файловете да се по-големи от $1; този файл е $2.',
'largefileserver'             => 'Файлът е по-голям от допустимия от сървъра размер.',
'emptyfile'                   => 'Каченият от Вас файл е празен. Това може да е предизвикано от грешка в името на файла. Моля, уверете се дали наистина искате да го качите.',
'fileexists'                  => 'Вече съществува файл с това име! Моля, прегледайте $1, ако не сте сигурни дали искате да го промените.',
'fileexists-forbidden'        => 'Вече съществува файл с това име! Моля, върнете се и качете файла с ново име. [[Картинка:$1|мини|центр|$1]]',
'fileexists-shared-forbidden' => 'В споделеното файлово хранилище вече съществува файл с това име! Моля, върнете се и качете файла с ново име. [[Картинка:$1|мини|центр|$1]]',
'successfulupload'            => 'Качването беше успешно',
'fileuploaded'                => 'Файлът „$1“ беше успешно качен.
Моля, последвайте препратката: ($2) към страницата за описание и въведете малко информация за файла – кога и от кого е създаден и всякаква друга информация, която може да имате за него. Ако това е картинка, можете да я вмъкнете в някоя страница по следния начин: <tt><nowiki>[[</nowiki>{{ns:image}}<nowiki>:$1|thumb|Описание]]</nowiki></tt>',
'uploadwarning'               => 'Предупреждение при качване',
'savefile'                    => 'Съхраняване на файл',
'uploadedimage'               => 'качена „[[$1]]“',
'uploaddisabled'              => 'Съжаляваме, качванията бяха спрени.',
'uploaddisabledtext'          => 'На този wiki-сайт качването на файлове е забранено',
'uploadscripted'              => 'Файлът съдържа HTML или скриптов код, който може да бъде погрешно  интерпретиран от браузъра.',
'uploadcorrupt'               => 'Файлът е повреден или е с неправилно разширение. Моля, проверете го и го качете отново.',
'uploadvirus'                 => 'Файлът съдържа вирус! Подробности: $1',
'sourcefilename'              => 'Първоначално име',
'destfilename'                => 'Целево име',
'watchthisupload'             => 'Наблюдаване на страницата',
'filewasdeleted'              => 'Файл в този име е съществувал преди време, но е бил изтрит. Моля проверете $1 преди отново да го качите.',

'upload-proto-error'      => 'Неправилен протокол',
'upload-proto-error-text' => 'Изисква се адрес започващ с <code>http://</code> или <code>ftp://</code>.',
'upload-file-error'       => 'Вътрешна грешка',
'upload-file-error-text'  => 'Вътрешна грешка при опит да се създаде временен файл на сървъра. Моля, обърнете се към системен администратор.',
'upload-misc-error'       => 'Неизвестна грешка при качване',
'upload-misc-error-text'  => 'Неизвестна грешка при качване. Моля, убедете се, че адресът е верен и опитайте отново. Ако отново имате проблем, обърнете се към системен администратор.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Не е възможно обръщането към указания URL адрес',
'upload-curl-error6-text'  => 'Търсеният адрес не може да бъде достигнат. Моля проверете дали е написан вярно.',
'upload-curl-error28'      => 'Времето за качване изтече',
'upload-curl-error28-text' => 'Сайтът не отговаря твърде дълго. Моля, убедете се, че сайтът работи и след малко опитайте отново. Може би ще трябва да опитате във време, което не е така натоварено.',

'license'            => 'Лицензиране',
'nolicense'          => 'Нищо не е избрано',
'upload_source_url'  => ' (правилен, публично достъпен интернет-адрес)',
'upload_source_file' => ' (файл на вашия компютър)',

# Image list
'imagelist'                 => 'Списък на файловете',
'imagelisttext'             => 'Списък от $1 файла, сортирани $2.',
'imagelistforuser'          => 'Показва само картинка, качени от $1.',
'getimagelist'              => 'донасяне на списъка с файлове',
'ilsubmit'                  => 'Търсене',
'showlast'                  => 'Показване на последните $1 файла, сортирани $2.',
'byname'                    => 'по име',
'bydate'                    => 'по дата',
'bysize'                    => 'по размер',
'imgdelete'                 => 'изтр',
'imgdesc'                   => 'опис',
'imgfile'                   => 'файл',
'imglegend'                 => 'Легенда: (опис) = показване/редактиране на описанието на файла.',
'imghistory'                => 'История на файла',
'revertimg'                 => 'връщ',
'deleteimg'                 => 'изтр',
'deleteimgcompletely'       => 'Изтриване на всички версии на файла',
'imghistlegend'             => 'Легенда: (тек) = текущият файл, (изтр) = изтриване на съответната версия, (връщ) = възвръщане към съответната версия.
<br /><i>Щракнете върху датата, за да видите файла, качен на тази дата</i>.',
'imagelinks'                => 'Препратки към файла',
'linkstoimage'              => 'Следните страници сочат към файла:',
'nolinkstoimage'            => 'Няма страници, сочещи към файла.',
'sharedupload'              => 'Този файл е споделен и може да бъде използван от други проекти.',
'shareduploadwiki'          => 'Моля, разгледайте $1 за по-нататъшна информация.',
'shareduploadwiki-linktext' => 'описателната страница на файла',
'noimage'                   => 'Не съществува файл с това име, можете $1.',
'noimage-linktext'          => 'да го качите',
'uploadnewversion-linktext' => 'Качване на нова версия на файла',
'imagelist_date'            => 'Дата',
'imagelist_name'            => 'Име на файла',
'imagelist_user'            => 'Потребител',
'imagelist_size'            => 'Размер (в байти)',
'imagelist_description'     => 'Описание',
'imagelist_search_for'      => 'Търсене по име на изображението:',

# MIME search
'mimesearch' => 'MIME-търсене',
'mimetype'   => 'MIME-тип:',
'download'   => 'сваляне',

# Unwatched pages
'unwatchedpages' => 'Ненаблюдавани страници',

# List redirects
'listredirects' => 'Списък на пренасочванията',

# Unused templates
'unusedtemplates'     => 'Неизползвани шаблони',
'unusedtemplatestext' => 'Тази страница съдържа списък на шаблоните, които не са включени в друга страница. Проверявайте за препратки към отделните шаблони преди да ги изтриете или предложите за изтриване.',
'unusedtemplateswlh'  => 'други препратки',

# Random redirect
'randomredirect' => 'Случайно пренасочване',

# Statistics
'statistics'             => 'Статистика',
'sitestats'              => 'Страници',
'userstats'              => 'Потребители',
'sitestatstext'          => "Базата от данни съдържа '''$1''' страници.
Това включва всички страници от всички именни пространства в {{SITENAME}} (''Основно'', Беседа, {{ns:Project}}, Потребител, Категория, ...). Измежду тях '''$2''' страници се смятат за действителни (изключват се пренасочванията и страниците, несъдържащи препратки).

Имало е '''$4''' редакции на страници откакто уикито беше пуснато. Това прави средно по '''$5''' редакции на страница.",
'userstatstext'          => "Има '''$1''' регистрирани потребители, като '''$2''' от тях (или '''$4%''') са администратори (вижте $3).",
'statistics-mostpopular' => 'Най-преглеждани страници',

'disambiguations'     => 'Пояснителни страници',
'disambiguationspage' => 'Шаблон:Пояснение',

'doubleredirects'     => 'Двойни пренасочвания',
'doubleredirectstext' => 'Всеки ред съдържа препратки към първото и второто пренасочване, както и първия ред на текста на второто пренасочване, който обикновено посочва „<i>истинската</i>“ целева страница, към която първото пренасочване би трябвало да сочи.',

'brokenredirects'     => 'Невалидни пренасочвания',
'brokenredirectstext' => 'Следните пренасочващи страници сочат към несъществуващи страници.',

# Miscellaneous special pages
'nbytes'                  => '$1 байта',
'ncategories'             => '$1 категории',
'nlinks'                  => '$1 препратки',
'nrevisions'              => '$1 версии',
'nviews'                  => '$1 прегледа',
'lonelypages'             => 'Страници сираци',
'lonelypagestext'         => 'Към следващите страници няма препратки от други страници на тази енциклопедия.',
'uncategorizedpages'      => 'Некатегоризирани страници',
'uncategorizedcategories' => 'Некатегоризирани категории',
'uncategorizedimages'     => 'Некатегоризирани картинки',
'unusedcategories'        => 'Неизползвани категории',
'unusedimages'            => 'Неизползвани файлове',
'popularpages'            => 'Известни страници',
'wantedcategories'        => 'Желани категории',
'wantedpages'             => 'Желани страници',
'mostlinked'              => 'Най-препращани страници',
'mostlinkedcategories'    => 'Най-препращани категории',
'mostcategories'          => 'Страници с най-много категории',
'mostimages'              => 'Най-препращани картинки',
'mostrevisions'           => 'Страници с най-много версии',
'allpages'                => 'Всички страници',
'prefixindex'             => 'Азбучен списък на представки',
'randompage'              => 'Случайна страница',
'shortpages'              => 'Кратки страници',
'longpages'               => 'Дълги страници',
'deadendpages'            => 'Задънени страници',
'deadendpagestext'        => 'Посочените страници нямат препратки към други страници в тази енциклопедия.',
'listusers'               => 'Списък на потребителите',
'specialpages'            => 'Специални страници',
'spheading'               => 'Специални страници за всички потребители',
'restrictedpheading'      => 'Специални страници с ограничен достъп',
'rclsub'                  => '(на страници, сочени от „$1“)',
'newpages'                => 'Нови страници',
'newpages-username'       => 'Потребител:',
'ancientpages'            => 'Стари страници',
'intl'                    => 'Междуезикови препратки',
'move'                    => 'Преместване',
'movethispage'            => 'Преместване на страницата',
'unusedimagestext'        => 'Моля, обърнете внимание на това, че други сайтове могат да сочат към картинката чрез пряк адрес и въпреки това тя може да се намира в списъка.',
'unusedcategoriestext'    => 'Следните категории съществуват, но никоя страница или категория не ги използва.',

# Book sources
'booksources'               => 'Източници на книги',
'booksources-search-legend' => 'Търсене на информация за книга',
'booksources-isbn'          => 'ISBN:',
'booksources-go'            => 'Търсене',
'booksources-text'          => 'По-долу е списъкът от връзки към други сайтове, продаващи нови и използвани книги или имащи повече информация за книгите, които търсите:',

'categoriespagetext' => 'В {{SITENAME}} съществуват следните категории.',
'data'               => 'Данни',
'userrights'         => 'Управление на потребителските права',
'groups'             => 'Потребителски групи',
'isbn'               => 'ISBN',
'alphaindexline'     => 'от $1 до $2',
'version'            => 'Версия',
'log'                => 'Дневници',
'alllogstext'        => 'Смесено показване на дневниците на качванията, изтриванията, защитата, блокиранията и бюрократите.
Можете да ограничите прегледа, като изберете вид на дневника, потребителско име или определена страница.',
'logempty'           => 'Дневникът не съдържа записи, отговарящи на избрания критерий.',

# Special:Allpages
'nextpage'          => 'Следваща страница ($1)',
'prevpage'          => 'Предходна страница ($1)',
'allpagesfrom'      => 'Показване на страниците, като се започва от:',
'allarticles'       => 'Всички страници',
'allinnamespace'    => 'Всички страници (именно пространство $1)',
'allnotinnamespace' => 'Всички страници (без именно пространство $1)',
'allpagesprev'      => 'Предишна',
'allpagesnext'      => 'Следваща',
'allpagessubmit'    => 'Отиване',
'allpagesprefix'    => 'Показване на страници, започващи със:',
'allpagesbadtitle'  => 'Заглавието на тази страница е недопустимо. Съдържа знаци, които не могат да се използват в заглавия.',

# Special:Listusers
'listusersfrom' => 'Показва потребителите започвайки от:',

# E-mail user
'mailnologin'     => 'Няма електронна поща',
'mailnologintext' => 'Необходимо е да [[Special:Userlogin|влезете]] и да посочите валидна електронна поща в [[Special:Preferences|настройките]] си, за да може да пращате писма на други потребители.',
'emailuser'       => 'Пращане писмо на потребителя',
'emailpage'       => 'Пращане писмо на потребител',
'emailpagetext'   => 'Ако потребителят е посочил валидна електронна поща в настройките си, чрез долния формуляр можете да му изпратите съобщение. Адресът, записан в настройките Ви, ще се появи в полето „От“ на изпратеното писмо, така че получателят ще е в състояние да Ви отговори.',
'usermailererror' => 'Пощенският обект даде грешка:',
'defemailsubject' => 'Писмо от {{SITENAME}}',
'noemailtitle'    => 'Няма електронна поща',
'noemailtext'     => 'Потребителят не е посочил валидна електронна поща или е избрал да не получава писма от други потребители.',
'emailfrom'       => 'От',
'emailto'         => 'До',
'emailsubject'    => 'Относно',
'emailmessage'    => 'Съобщение',
'emailsend'       => 'Изпращане',
'emailccme'       => 'Изпрати ми копие на това съобщение.',
'emailccsubject'  => 'Копие на вашето съобщение за $1: $2',
'emailsent'       => 'Писмото е изпратено',
'emailsenttext'   => 'Писмото Ви беше изпратено.',

# Watchlist
'watchlist'            => 'Моят списък за наблюдение',
'watchlistfor'         => "(за '''$1''')",
'nowatchlist'          => 'Списъкът Ви за наблюдение е празен.',
'watchlistanontext'    => 'Необходимо е $1 за да видите или редактирате списъка си за наблюдение.',
'watchlistcount'       => "'''Имате {{PLURAL:$1|$1 item|$1 страници}} във вашия списък за наблюдение, вкл. беседи.'''",
'clearwatchlist'       => 'Изчисти списъка за наблюдение',
'watchlistcleartext'   => 'Сигурни ли сте, че искате да ги махнете?',
'watchlistclearbutton' => 'Изчисти списъка за наблюдение',
'watchlistcleardone'   => 'Вашият списък за наблюдение е изчистен. {{PLURAL:$1|$1 item was|$1 страници бяха}} премахнати.',
'watchnologin'         => 'Не сте влезли',
'watchnologintext'     => 'Необходимо е да [[Special:Userlogin|влезете]], за да може да променяте списъка си за наблюдение.',
'addedwatch'           => 'Добавено в списъка за наблюдение',
'addedwatchtext'       => "Страницата „'''$1'''“ беше добавена към [[Special:Watchlist|списъка Ви за наблюдение]].
Нейните бъдещи промени, както и на съответната й дискусионна страница, ще се описват там, а тя ще се появява с '''удебелен шрифт''' в [[Special:Recentchanges|списъка на последните промени]], което ще направи по-лесно избирането й.

Ако по-късно искате да премахнете страницата от списъка си за наблюдение, щракнете на „''Спиране на наблюдение''“.",
'removedwatch'         => 'Премахнато от списъка за наблюдение',
'removedwatchtext'     => 'Страницата „$1“ беше премахната от списъка Ви за наблюдение.',
'watch'                => 'Наблюдаване',
'watchthispage'        => 'Наблюдаване на страницата',
'unwatch'              => 'Спиране на наблюдение',
'unwatchthispage'      => 'Спиране на наблюдение',
'notanarticle'         => 'Не е страница',
'watchnochange'        => 'Никоя от наблюдаваните страници не е била редактирана в показаното време.',
'watchdetails'         => '* $1 наблюдавани страници (без дискусионни), $2 редактирани страници в избраното време
* Метод на заявката: $3
* [[Special:Watchlist/edit|Показване и редактиране на пълния списък]]',
'wlheader-enotif'      => '* Известяването по електронна поща е включено.',
'wlheader-showupdated' => "* Страниците, които са били променени след последния път, когато сте ги посетили, са показани с '''получерен''' шрифт.",
'watchmethod-recent'   => 'проверка на последните промени за наблюдавани страници',
'watchmethod-list'     => 'проверка на наблюдаваните страници за скорошни редакции',
'removechecked'        => 'Премахване на избраните от списъка за наблюдение',
'watchlistcontains'    => 'Списъкът Ви за наблюдение съдържа $1 страници.',
'watcheditlist'        => 'В азбучен ред са показани наблюдаваните от Вас основни страници. Отметнете кутийките на страниците, които искате да премахнете от списъка Ви за наблюдение и натиснете бутона „Премахване на избраните“ (изтриването на основна страница предизвиква изтриването и на съответната й дискусионна страница и обратно).',
'removingchecked'      => 'Премахване на избраните от списъка за наблюдение...',
'couldntremove'        => 'Неуспех при премахването на „$1“...',
'iteminvalidname'      => 'Проблем с „$1“, грешно име...',
'wlnote'               => 'Показани са последните $1 промени през последните <b>$2</b> часа.',
'wlshowlast'           => 'Показване на последните $1 часа $2 дни $3',
'wlsaved'              => 'Това е съхранена версия на списъка Ви за наблюдение.',
'watchlist-show-bots'  => 'Показване на ботове',
'watchlist-hide-bots'  => 'Скриване на ботове',
'watchlist-show-own'   => 'Показване моите приноси',
'watchlist-hide-own'   => 'Скриване моите приноси',
'watchlist-show-minor' => 'Показване малки промени',
'watchlist-hide-minor' => 'Скриване малки промени',
'wldone'               => 'Готово.',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Наблюдаване...',
'unwatching' => 'Unwatching...',

'enotif_mailer'      => '{{SITENAME}} Известяване по пощата',
'enotif_reset'       => 'Отбелязване на всички страници като посетени',
'enotif_newpagetext' => 'Това е нова страница.',
'changed'            => 'променена',
'created'            => 'създадена',
'enotif_subject'     => 'Страницата $PAGETITLE в {{SITENAME}} е била $CHANGEDORCREATED от $PAGEEDITOR',
'enotif_lastvisited' => 'Прегледайте $1 за всички промени след последното ви посещение.',
'enotif_body'        => '$WATCHINGUSERNAME,

$PAGEEDITDATE страницата $PAGETITLE в {{SITENAME}} е била $CHANGEDORCREATED от $PAGEEDITOR, вижте $PAGETITLE_URL за преглед на текущата версия.

$NEWPAGE

Кратко описание на измененията: $PAGESUMMARY $PAGEMINOREDIT

Обратиться к изменившему:
електронна поща $PAGEEDITOR_EMAIL
уики $PAGEEDITOR_WIKI

Няма да има други уведомления в случай  на следващи изменения, ако не посещавате тази страница. Можете повторно да сложите флаг за уведомления за всички страници от списъка ви за наблюдение.

             Система за известяване {{grammar:genitive|{{SITENAME}}}}

--
За да промените настройките на вашия списък за наблюдение вижте
{{fullurl:{{ns:special}}:Watchlist/edit}}

Обратна връзка и помощ:
{{fullurl:{{ns:help}}:Contents}}',

# Delete/protect/revert
'deletepage'                  => 'Изтриване на страница',
'confirm'                     => 'Потвърждение',
'excontent'                   => 'съдържанието беше: „$1“',
'excontentauthor'             => 'съдържанието беше: „$1“ (като единственият автор беше „$2“)',
'exbeforeblank'               => 'съдържанието преди изпразването беше: „$1“',
'exblank'                     => 'страницата беше празна',
'confirmdelete'               => 'Потвърждение за изтриване',
'deletesub'                   => '(Изтриване на „$1“)',
'historywarning'              => 'Внимание: Страницата, която ще изтриете, има история:',
'confirmdeletetext'           => 'На път сте безвъзвратно да изтриете страница или файл, заедно с цялата й (му) история, от базата от данни.
Моля, потвърдете, че искате това, разбирате последствията и правите това в съответствие с нашата [[{{MediaWiki:policy-url}}|линия на поведение]].',
'actioncomplete'              => 'Действието беше изпълнено',
'deletedtext'                 => 'Страницата „$1“ беше изтрита. Вижте $2 за запис на последните изтривания.',
'deletedarticle'              => 'изтрита „[[$1]]“',
'dellogpage'                  => 'Дневник на изтриванията',
'dellogpagetext'              => 'Списък на последните изтривания.',
'deletionlog'                 => 'дневника на изтриванията',
'reverted'                    => 'Възвръщане към предишна версия',
'deletecomment'               => 'Причина за изтриването',
'imagereverted'               => 'Възвръщането към предишна версия беше успешно.',
'rollback'                    => 'Връщане назад на промените',
'rollback_short'              => 'Връщане',
'rollbacklink'                => 'връщане',
'rollbackfailed'              => 'Връщането не сполучи',
'cantrollback'                => 'Промяната не може да се извърши. Последният автор е единственият собственик на страницата.',
'alreadyrolled'               => 'Редакцията на [[:$1]], направена от [[{{ns:user}}:$2|$2]] ([[{{ns:user_talk}}:$2|Беседа]]), не може да се върне назад. Някой друг вече е редактирал страницата или е върнал назад промените.

Последната редакция е на [[{{ns:user}}:$3|$3]] ([[{{ns:user_talk}}:$3|Беседа]]).',
'editcomment'                 => 'Коментарът на редакцията е бил: „<i>$1</i>“.', # only shown if there is an edit comment
'revertpage'                  => 'Премахване на [[Special:Contributions/$2|редакции на $2]], възвръщане към последната версия на $1',
'sessionfailure'              => 'Явно има проблем с вашата сесия; действието беше отказано като предпазна мярка срещу крадене на сесията. Моля, натиснете бутона „back“ и презаредете страницата от която сте дошли и опитайте отново.',
'protectlogpage'              => 'Дневник на защитата',
# problem with link [[Project:Защитена страница]]
'protectlogtext'              => 'Списък на защитите и техните сваляния за страницата.
За повече информация вижте [[Project:Защитена страница]].',
'protectedarticle'            => 'защитена „[[$1]]“',
'unprotectedarticle'          => 'сваляне на защитата на „[[$1]]“',
'protectsub'                  => '(Защитаване на „$1“)',
'confirmprotecttext'          => 'Наистина ли искате да защитите страницата?',
'confirmprotect'              => 'Потвърдете защитата',
'protectmoveonly'             => 'Защита само от премествания',
'protectcomment'              => 'Причина за защитата',
'unprotectsub'                => '(Сваляне на защитата на „$1“)',
'confirmunprotecttext'        => 'Наистина ли искате да свалите защитата на страницата?',
'confirmunprotect'            => 'Потвърдете свалянето на защитата',
'unprotectcomment'            => 'Причина за сваляне на защитата',
'protect-unchain'             => 'Позволяване на преместванията',
'protect-text'                => 'Тук можете да прегледате и промените нивото на защита на страницата „[[$1]]“.
Желателно е да се придържате към [[Project:Защитена страница|ръководните принципи на проекта]].',
'protect-viewtext'            => 'Нямате правото да променяте нивата на защита на страниците. Ето текущите настройки за страницата „[[$1]]“:',
'protect-default'             => '(по подразбиране)',
'protect-level-autoconfirmed' => 'Блокиране на нерегистрирани потребители',
'protect-level-sysop'         => 'Само за администратори',
'protect-summary-cascade'     => 'cascading',
'protect-cascade'             => 'Cascading protection - protect any pages transcluded in this page.',

# Restrictions (nouns)
'restriction-edit' => 'Редакция',
'restriction-move' => 'Преместване',

# Undelete
'undelete'                 => 'Преглед на изтрити страници',
'undeletepage'             => 'Преглед и възстановяване на изтрити страници',
'viewdeletedpage'          => 'Преглед на изтрити страници',
'undeletepagetext'         => 'Следните страници бяха изтрити, но се намират все още
в архива и могат да бъдат възстановени. Архивът може да се почиства от време на време.',
'undeleteextrahelp'        => "За пълно възстановяване на страницата не слагайте отметки и натиснете '''''Възстановяване!'''''. 
За частично възстановяване отметнете тези версии на страницата, които трябва да бъдат въстановени и натиснете '''''Възстановяване!'''''. 
Натиснете '''''Изчисти!''''', за да махнете всички отметки и да изчистите полето за коментар",
'undeletearticle'          => 'Възстановяване на изтрита страница',
'undeleterevisions'        => '$1 версии архивирани',
'undeletehistory'          => 'Ако възстановите страницата, всички версии ще бъдат върнати в историята.
Ако след изтриването е създадена страница със същото име, възстановените версии ще се появят като по-ранна история, а текущата версия на страницата няма да бъде автоматично заменена.',
'undeletehistorynoadmin'   => 'Тази страница е била изтрита. В резюмето отдолу е посочена причината за това, заедно с информация за потребителите, редактирали страницата преди изтриването й. Конкретното съдържание на изтритите версии е достъпно само за администратори.',
'undeleterevision-missing' => 'Неправилна версия. Грешна препратка или указаната версия на страницата е възстановени или преместена от архива',
'undeletebtn'              => 'Възстановяване!',
'undeletereset'            => 'Изчисти',
'undeletecomment'          => 'Коментар:',
'undeletedarticle'         => '„[[$1]]“ беше възстановена',
'undeletedrevisions'       => '$1 версии бяха възстановени',
'undeletedrevisions-files' => '$1 редакции и $2 файл(а) бяха възстановени',
'undeletedfiles'           => '$1 файл(а) бяха възстановени',
'cannotundelete'           => 'Грешка при възстановяването. Възможно е някой друг вече да е възстановил страницата.',
'undeletedpage'            => "<big>'''Страница \"\$1\" е била възстановена.'''</big>  Можете да видите последните изтрити и възстановени страници в [[{{ns:special}}:Log/delete|дневника на изтриванията.]]",

# Namespace form on various pages
'namespace' => 'Именно пространство:',
'invert'    => 'Обръщане на избора',

# Contributions
'contributions' => 'Приноси',
'mycontris'     => 'Моите приноси',
'contribsub'    => 'За $1',
'nocontribs'    => 'Не са намерени промени, отговарящи на критерия.',
'ucnote'        => 'Показани са последните <b>$1</b> промени, извършени от този потребител през последните <b>$2</b> дни.',
'uclinks'       => 'Показване на последните $1 промени; показване на последните $2 дни.',
'uctop'         => ' (последна)',
'newbies'       => 'новаци',

'sp-contributions-newest'      => 'Най-нови',
'sp-contributions-oldest'      => 'Най-стари',
'sp-contributions-newer'       => 'По-нови $1',
'sp-contributions-older'       => 'По-стари $1',
'sp-contributions-newbies-sub' => 'на нови потребители',
'sp-contributions-blocklog'    => 'на блокирани',

'sp-newimages-showfrom' => 'Показване на новите изображения, като се започва от $1',

# What links here
'whatlinkshere'        => 'Какво сочи насам',
'whatlinkshere-barrow' => '&lt;',
'notargettitle'        => 'Няма цел',
'notargettext'         => 'Не указахте целева страница или потребител, върху която/който да се изпълни действието.',
'linklistsub'          => '(Списък с препратки)',
'linkshere'            => 'Следните страници сочат насам:',
'nolinkshere'          => 'Няма страници, сочещи насам.',
'isredirect'           => 'пренасочваща страница',
'istemplate'           => 'включване',

# Block/unblock
'blockip'                     => 'Блокиране на потребител',
'blockiptext'                 => 'Използвайте долния формуляр, за да забраните правото на писане
на определен IP-адрес или потребител.
Това трябва да се направи само, за да се предотвратят прояви на вандализъм,
и в съответствие с  [[{{MediaWiki:policy-url}}|линията на поведение]] на {{SITENAME}}.
Посочете също и причина за блокирането (например, заглавия на страници, станали обект на вандализъм).

Срокът за изтичане на блокирането се въвежда според установения формат на ГНУ, описан в [http://www.gnu.org/software/tar/manual/html_chapter/tar_7.html ръководството], например: „1 hour“, „2 days“, „next Wednesday“, „1 January 2017“. Неограничено блокиране може да се зададе чрез „indefinite“ или „infinite“.',
'ipaddress'                   => 'IP-адрес',
'ipadressorusername'          => 'IP-адрес или потребител',
'ipbexpiry'                   => 'Срок',
'ipbreason'                   => 'Причина',
'ipbanononly'                 => 'Блокиране само на анонимни потребители',
'ipbcreateaccount'            => 'Забрана за създаване на потребителски сметки',
'ipbenableautoblock'          => 'Автоматично блокиране на последния IP адрес, използван от потребителя',
'ipbsubmit'                   => 'Блокиране на потребителя',
'ipbother'                    => 'Друг срок',
'ipboptions'                  => 'Два часа:2 hours,Един ден:1 day,Три дни:3 days,Една седмица:1 week,Две седмици:2 weeks,Един месец:1 month,Три месеца:3 months,Шест месеца:6 months,Една година:1 year,Докато свят светува:infinite',
'ipbotheroption'              => 'друг',
'badipaddress'                => 'Невалиден IP-адрес или грешно име на потребител',
'blockipsuccesssub'           => 'Блокирането беше успешно',
'blockipsuccesstext'          => '„[[{{ns:Special}}:Contributions/$1|$1]]“ беше блокиран.
<br />Вижте [[{{ns:Special}}:Ipblocklist|списъка на блокираните IP-адреси]], за да прегледате всички блокирания.',
'unblockip'                   => 'Отблокиране на потребител',
'unblockiptext'               => 'Използвайте долния формуляр, за да възстановите правото на писане на по-рано блокиран IP-адрес или потребител.',
'ipusubmit'                   => 'Отблокиране на адреса',
'unblocked'                   => '[[Потребител:$1|$1]] беше отблокиран.',
'ipblocklist'                 => 'Списък на блокирани IP-адреси и потребители',
'blocklistline'               => '$1, $2 е блокирал $3 ($4)',
'infiniteblock'               => 'неограничено',
'expiringblock'               => 'изтича на $1',
'anononlyblock'               => 'само анонимни',
'noautoblockblock'            => 'автоблокировка отключена',
'createaccountblock'          => 'създаването на потребителска сметка е блокирано',
'ipblocklistempty'            => 'Списъкът на блокиранията е празен.',
'blocklink'                   => 'блокиране',
'unblocklink'                 => 'отблокиране',
'contribslink'                => 'приноси',
'autoblocker'                 => "Бяхте автоматично блокиран, тъй като неотдавна IP-адресът ви е бил ползван от текущо блокирания потребител „$1“. Причината за неговото блокиране е: „'''$2'''“.",
'blocklogpage'                => 'Дневник на блокиранията',
'blocklogentry'               => 'блокиране на „[[$1]]“ със срок на изтичане $2',
'blocklogtext'                => 'Това е дневник на блокиранията и отблокиранията, извършени от този потребител. Автоматично блокираните IP-адреси не са показани. Вижте  [[Special:Ipblocklist|списъка на блокираните IP-адреси]] за текущото състояние на блокиранията.',
'unblocklogentry'             => 'отблокиране на „$1“',
'block-log-flags-anononly'    => 'само анонимни потребители',
'block-log-flags-nocreate'    => 'невъзможно създаване на сметка',
'block-log-flags-autoblock'   => 'autoblocker enabled',
'range_block_disabled'        => 'Възможността на администраторите да задават интервали при IP-адресите е изключена.',
'ipb_expiry_invalid'          => 'Невалиден срок на изтичане.',
'ipb_already_blocked'         => '"$1" е вече блокиран',
'ip_range_invalid'            => 'Невалиден интервал за IP-адреси.',
'proxyblocker'                => 'Блокировач на проксита',
'ipb_cant_unblock'            => 'Грешка. Блокиран с ID $1 не е намерен. Възможно е потребителя вече да е разблокиран.',
'proxyblockreason'            => 'Вашият IP-адрес беше блокиран, тъй като е отворено прокси. Моля, свържете се с Вашия доставчик на интернет и го информирайте за този сериозен проблем в сигурността.',
'proxyblocksuccess'           => 'Готово.',
'sorbs'                       => 'SORBS DNSBL',
'sorbsreason'                 => 'Вашият IP-адрес е записан като отворено прокси в [http://www.sorbs.net SORBS] DNSBL.',
'sorbs_create_account_reason' => 'Вашият IP-адрес е записан като отворено прокси в [http://www.sorbs.net SORBS] DNSBL. Не можете да създадете сметка.',

# Developer tools
'lockdb'              => 'Заключване на базата от данни',
'unlockdb'            => 'Отключване на базата от данни',
'lockdbtext'          => 'Заключването на базата от данни ще попречи на всички потребители да редактират страници, да сменят своите настройки, да редактират своите списъци за наблюдение и на всички други техни действия, изискващи промени в базата от данни.
Моля, потвърдете, че искате точно това и ще отключите базата от данни,
когато привършите с работата по подръжката.',
'unlockdbtext'        => 'Отключването на базата от данни ще възстанови способността на потребителите да редактират страници, да сменят своите настройки, да редактират своите списъци за наблюдение и изпълнението на всички други действия, изискващи промени в базата от данни.
Моля, потвърдете, че искате точно това.',
'lockconfirm'         => 'Да, наистина искам да заключа базата от данни.',
'unlockconfirm'       => 'Да, наистина искам да отключа базата от данни.',
'lockbtn'             => 'Заключване на базата от данни',
'unlockbtn'           => 'Отключване на базата от данни',
'locknoconfirm'       => 'Не сте отметнали кутийката за потвърждение.',
'lockdbsuccesssub'    => 'Заключването на базата от данни беше успешно',
'unlockdbsuccesssub'  => 'Отключването на базата от данни беше успешно',
'lockdbsuccesstext'   => 'Базата от данни на {{SITENAME}} беше заключена.
<br />Не забравяйте да отключите базата от данни, когато привършите с работата по поддръжката.',
'unlockdbsuccesstext' => 'Базата от данни на {{SITENAME}} беше отключена.',
'lockfilenotwritable' => 'Няма права за запис във файла за заключване на базата данни. За да заключи или отключи базата данни уеб-сървърът трябва да има права за запис.',
'databasenotlocked'   => 'Базата данни не е заключена.',

# Move page
'movepage'                => 'Преместване на страница',
'movepagetext'            => "Посредством долния формуляр можете да преименувате страница, премествайки цялата й история на новото име. Старото заглавие ще се превърне в пренасочваща страница.
Препратките към старата страница няма да бъдат променени; затова проверете за двойни или невалидни пренасочвания.
Вие сами би трябвало да се убедите в това, дали препратките продължават да сочат там, където се предполага.

Страницата '''няма''' да бъде преместена, ако вече съществува страница с новото име, освен ако е празна или пренасочване и няма редакционна история.

'''ВНИМАНИЕ!'''
Това може да е голяма и неочаквана промяна за известна страница. Уверете се, че разбирате последствията, преди да продължите.",
'movepagetalktext'        => "Съответната дискусионна страница, ако съществува, ще бъде автоматично преместена заедно с нея, '''освен ако:'''
* не местите страницата от едно именно пространство в друго,
* вече съществува непразна дискусионна страница с това име или
* не сте отметнали долната кутийка.

В тези случаи, ако желаете, ще е необходимо да преместите страницата ръчно.",
'movearticle'             => 'Преместване на страница',
'movenologin'             => 'Не сте влезли',
'movenologintext'         => 'Необходимо е да [[Special:Userlogin|влезете]], за да може да премествате страници.',
'newtitle'                => 'Към ново заглавие',
'move-watch'              => 'Наблюдаване на страницата',
'movepagebtn'             => 'Преместване',
'pagemovedsub'            => 'Преместването беше успешно',
'pagemovedtext'           => 'Страницата „[[$1]]“ беше преместена под името „[[$2]]“.',
'articleexists'           => 'Вече съществува страница с това име или името, което сте избрали, е невалидно. Моля, изберете друго име.',
'talkexists'              => "'''Страницата беше успешно преместена, но без съответната дискусионна страница, защото под новото име има една съществуваща. Моля, обединете ги ръчно.'''",
'movedto'                 => 'преместена като',
'movetalk'                => 'Преместване и на дискусионната страница, ако е приложимо.',
'talkpagemoved'           => 'Съответната дискусионна страница също беше преместена.',
'talkpagenotmoved'        => 'Съответната дискусионна страница <strong>не</strong> беше преместена.',
'1movedto2'               => '„$1“ преместена като „$2“',
'1movedto2_redir'         => '„$1“ преместена като „$2“ (върху пренасочване)',
'movelogpage'             => 'Дневник на преместванията',
'movelogpagetext'         => 'По-долу е показан списък на преместванията.',
'movereason'              => 'Причина',
'revertmove'              => 'връщане',
'delete_and_move'         => 'Изтриване и преместване',
'delete_and_move_text'    => '== Наложително изтриване ==

Целевата страница „[[$1]]“ вече съществува. Искате ли да я изтриете, за да освободите място за преместването?',
'delete_and_move_confirm' => 'Да, искам да изтрия тази страница.',
'delete_and_move_reason'  => 'Изтрита, за да се освободи място за преместване',
'selfmove'                => 'Страницата не може да бъде преместена, тъй като целевото име съвпада с първоначалното й заглавие.',
'immobile_namespace'      => 'Целевото заглавие е от специален тип. Не е възможно местенето на страници в това именно пространство.',

# Export
'export'          => 'Изнасяне на страници',
'exporttext'      => "Тук можете да изнесете като XML текста и историята на една или повече страници. Получените данни можете да:
* вмъкнете в друг сайт, използващ софтуера на МедияУики,
* обработвате или
* просто запазите за лично ползване.

За да изнесете няколко страници, въвеждайте всяко ново заглавие на '''нов ред'''. След това изберете дали искате само текущата версия (заедно с информация за последната редакция) или всички версии (заедно с текущата) на страницата.

Ако желаете само текущата версия, бихте могли да използвате препратка от вида [[Special:Export/България]] за страницата [[България]].",
'exportcuronly'   => 'Включване само на текущата версия, а не на цялата история',
'exportnohistory' => "----
'''Важно:''' Експортът на пълната история на редакциите на страницата е заключен.",
'export-submit'   => 'Експорт',

# Namespace 8 related
'allmessages'               => 'Системни съобщения',
'allmessagesname'           => 'Име',
'allmessagesdefault'        => 'Текст по подразбиране',
'allmessagescurrent'        => 'Текущ текст',
'allmessagestext'           => 'Това е списък на системните съобщения, намиращи се в именното пространство „МедияУики“',
'allmessagesnotsupportedUI' => 'Текущо избраният език за интерфейса <b>$1</b> не се поддържа от <em>Специални:AllMessages</em> на сайта.',
'allmessagesnotsupportedDB' => 'Възможността за използване на страници от именното пространство „МедияУики“ за генериране на интерфейсните съобщения е изключена (<code>LocalSettings.php: wgUseDatabaseMessages = false</code>).',
'allmessagesfilter'         => 'Филтриране на съобщенията по име:',
'allmessagesmodified'       => 'Показване само променените',

# Thumbnails
'thumbnail-more'  => 'Увеличаване',
'missingimage'    => '<b>Липсваща картинка</b><br /><i>$1</i>',
'filemissing'     => 'Липсващ файл',
'thumbnail_error' => 'Грешка при създаване на thumbnail: $1',

# Special:Import
'import'                     => 'Импорт на страници',
'importinterwiki'            => 'Импорт чрез Трансуики',
'import-interwiki-text'      => 'Изберете wiki и името на страницата.
Датата на изменението и имената на авторите ще бъдат записани.
Всички операции на междууики импорт се записват в [[Special:Log/import|съответния дневник]].',
'import-interwiki-history'   => 'Копирай всички версии на тази страница',
'import-interwiki-submit'    => 'Импорт',
'import-interwiki-namespace' => 'Прехвърляне на страници към именно пространство:',
'importtext'                 => 'Моля, изнесете файла от изходното уики, използвайки инструмента „Special:Export“, съхранете го на Вашия диск и го качете тук.',
'importstart'                => 'Импортиране на страници...',
'importnopages'              => 'Няма страници за импорт.',
'importfailed'               => 'Импортирането беше неуспешно: $1',
'importunknownsource'        => 'Непознат тип файл',
'importcantopen'             => 'Невъзможност да се отвори импортирания файл',
'importbadinterwiki'         => 'Некоректна wiki препратка',
'importnotext'               => 'Празно',
'importsuccess'              => 'Импортът беше успешен!',
'importhistoryconflict'      => 'Съществува версия от историята, която си противоречи с тази (възможно е страницата да е била вече внесена)',
'importnosources'            => 'No transwiki import sources have been defined and direct history uploads are disabled.',
'importnofile'               => 'Импортираният файл не беше качен.',
'importuploaderror'          => 'Имаше грешка при качването на импортираният файл; може би файлът има размер по-голям от позволения за качване.',

# Import log
'importlogpage'                    => 'Дневник на импортите',
'importlogpagetext'                => 'Импортиране от администратори на страници с редакционна история от други уикита.',
'import-logentry-upload'           => '[[$1]] -импорт от файл',
'import-logentry-upload-detail'    => '$1 версии',
'import-logentry-interwiki'        => '$1 -междууики импорт',
'import-logentry-interwiki-detail' => '$1 версии от $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Вашата потребителска страница',
'tooltip-pt-anonuserpage'         => 'Потребителската страница за адреса, от който редактирате',
'tooltip-pt-mytalk'               => 'Вашата дискусионна страница',
'tooltip-pt-anontalk'             => 'Дискусия относно редакциите от този адрес',
'tooltip-pt-preferences'          => 'Вашите настройки',
'tooltip-pt-watchlist'            => 'Списък на страници, чиито промени сте избрали да наблюдавате',
'tooltip-pt-mycontris'            => 'Списък на Вашите приноси',
'tooltip-pt-login'                => 'В момента не сте влезли. Насърчаваме Ви да влезете, въпреки че не е задължително.',
'tooltip-pt-anonlogin'            => 'Насърчаваме Ви да влезете, въпреки че не е задължително.',
'tooltip-pt-logout'               => 'Излизане от {{SITENAME}}',
'tooltip-ca-talk'                 => 'Беседа относно страницата',
'tooltip-ca-edit'                 => 'Можете да редактирате страницата. Моля, използвайте бутона за предварителен преглед преди да съхраните.',
'tooltip-ca-addsection'           => 'Добавяне на коментар към страницата',
'tooltip-ca-viewsource'           => 'Страницата е защитена. Можете да разгледате изходния й код.',
'tooltip-ca-history'              => 'Предишни версии на страницата',
'tooltip-ca-protect'              => 'Защитаване на страницата',
'tooltip-ca-delete'               => 'Изтриване на страницата',
'tooltip-ca-undelete'             => 'Възстановяване на изтрити редакции на страницата',
'tooltip-ca-move'                 => 'Преместване на страницата',
'tooltip-ca-watch'                => 'Добавяне на страницата към списъка Ви за наблюдение',
'tooltip-ca-unwatch'              => 'Премахване на страницата от списъка Ви за наблюдение',
'tooltip-search'                  => 'Претърсване на {{SITENAME}}',
'tooltip-p-logo'                  => 'Началната страница',
'tooltip-n-mainpage'              => 'Началната страница',
'tooltip-n-portal'                => 'Информация за проекта',
'tooltip-n-currentevents'         => 'Информация за текущите събития по света',
'tooltip-n-recentchanges'         => 'Списък на последните промени в {{SITENAME}}',
'tooltip-n-randompage'            => 'Случайна страница',
'tooltip-n-help'                  => 'Помощната страница',
'tooltip-n-sitesupport'           => 'Подкрепете {{SITENAME}}',
'tooltip-t-whatlinkshere'         => 'Списък на всички страници, сочещи насам',
'tooltip-t-recentchangeslinked'   => 'Последните промени на страници, сочени от тази страница',
'tooltip-feed-rss'                => 'RSS feed за страницата',
'tooltip-feed-atom'               => 'Atom feed за страницата',
'tooltip-t-contributions'         => 'Показване на приносите на потребителя',
'tooltip-t-emailuser'             => 'Изпращане на писмо на потребителя',
'tooltip-t-upload'                => 'Качване на файлове',
'tooltip-t-specialpages'          => 'Списък на всички специални страници',
'tooltip-ca-nstab-main'           => 'Преглед на основната страница',
'tooltip-ca-nstab-user'           => 'Преглед на потребителската страница',
'tooltip-ca-nstab-media'          => 'Преглед на медийната страница',
'tooltip-ca-nstab-special'        => 'Това е специална страница, която не може да се редактира.',
'tooltip-ca-nstab-project'        => 'Преглед на проектната страница',
'tooltip-ca-nstab-image'          => 'Преглед на страницата на файла',
'tooltip-ca-nstab-mediawiki'      => 'Преглед на системното съобщение',
'tooltip-ca-nstab-template'       => 'Преглед на шаблона',
'tooltip-ca-nstab-help'           => 'Преглед на помощната страница',
'tooltip-ca-nstab-category'       => 'Преглед на категорийната страница',
'tooltip-minoredit'               => 'Отбелязване на промяната като малка',
'tooltip-save'                    => 'Съхраняване на промените',
'tooltip-preview'                 => 'Предварителен преглед, моля, използвайте го преди да съхраните!',
'tooltip-diff'                    => 'Показване на направените от Вас промени по текста',
'tooltip-compareselectedversions' => 'Показване на разликите между двете избрани версии на страницата',
'tooltip-watch'                   => 'Добавяне на страницата към списъка Ви за наблюдение',
'tooltip-recreate'                => 'Възстановяване на страницата независимо, че е била изтрита',

# Stylesheets
'common.css'   => '/* чрез редактиране на този файл ще промените всички облици */',
'monobook.css' => '/* чрез редактиране на този файл можете да промените облика Монобук */',

# Scripts
'common.js'   => '/* Този файл съдържа код на [[Джаваскрипт]] и се зарежда при всички потребители. */',
'monobook.js' => '/* Deprecated; use [[MediaWiki:common.js]] */',

# Metadata
'nodublincore'      => 'Метданни Dublin Core RDF са забранени за този сървър.',
'nocreativecommons' => 'Метаданни Creative Commons RDF са забранени за този сървър.',
'notacceptable'     => 'Сървърът не може да предостави данни във формат, който да се разпознава от клиента Ви.',

# Attribution
'anonymous'        => 'Анонимен потребител(и) на {{SITENAME}}',
'siteuser'         => 'потребители на {{SITENAME}} $1',
'lastmodifiedatby' => 'Последната промяна на страницата е извършена от $3 на $2, $1.', # $1 date, $2 time, $3 user
'and'              => 'и',
'othercontribs'    => 'Основаващо се върху работа на $1.',
'others'           => 'други',
'siteusers'        => 'потребителите на {{SITENAME}} $1',
'creditspage'      => 'Библиография и източници',
'nocredits'        => 'Няма въведени източници или библиография',

# Spam protection
'spamprotectiontitle'    => 'Филтър за защита от спам',
'spamprotectiontext'     => 'Страницата, която искахте да съхраните, беше блокирана от филтъра против спам. Това обикновено е причинено от препратка към външен сайт.',
'spamprotectionmatch'    => 'Следният текст предизвика включването на филтъра: $1',
'subcategorycount'       => 'Тази категория има $1 подкатегории.',
'categoryarticlecount'   => 'Тази категория съдържа $1 страници.',
'category-media-count'   => 'Има {{PLURAL:$1|is one file| $1 файл(а)}} в тази категория.',
'listingcontinuesabbrev' => ' продълж.',
'spambot_username'       => 'Почистване на спама',
'spam_reverting'         => 'Връщане на последната версия не съдържаща препратки към $1',
'spam_blanking'          => 'Всички версии въдържащи препратки към $1, изчистване',

# Info page
'infosubtitle'   => 'Информация за страницата',
'numedits'       => 'Брой редакции (страница):',
'numtalkedits'   => 'Брой редакции (дискусионна страница):',
'numwatchers'    => 'Брой наблюдатели:',
'numauthors'     => 'Брой различни автори (страница):',
'numtalkauthors' => 'Брой различни автори (дискусионна страница):',

# Math options
'mw_math_png'    => 'Използване винаги на PNG',
'mw_math_simple' => 'HTML при опростен TeX, иначе PNG',
'mw_math_html'   => 'HTML по възможност, иначе PNG',
'mw_math_source' => 'Оставяне като TeX (за текстови браузъри)',
'mw_math_modern' => 'Препоръчително за нови браузъри',
'mw_math_mathml' => 'MathML по възможност (експериментално)',

# Patrolling
'markaspatrolleddiff'                 => 'Отбелязване като проверена версия',
'markaspatrolledtext'                 => 'Отбелязване на версията като проверена',
'markedaspatrolled'                   => 'Проверена версия',
'markedaspatrolledtext'               => 'Избраната версия беше отбелязана като проверена.',
'rcpatroldisabled'                    => 'Патрулът е деактивиран',
'rcpatroldisabledtext'                => 'Патрулът на последните промени е деактивиран',
'markedaspatrollederror'              => 'Не е възможно да се отбележи като проверена',
'markedaspatrollederrortext'          => 'Длъжни сте да зададете редакция, която да бъде отбелязана като проверена.',
'markedaspatrollederror-noautopatrol' => 'Не е разрешено да маркирате своите редакции като проверени.',

# Image deletion
'deletedrevision' => 'Изтрита стара версия $1.',

# Browsing diffs
'previousdiff' => '← Предишна разлика',
'nextdiff'     => 'Следваща разлика →',

# Media information
'mediawarning' => "'''Внимание''': This file may contain malicious code, by executing it your system may be compromised.
<hr />",
'imagemaxsize' => 'Ограничаване на картинките на описателните им страници до:',
'thumbsize'    => 'Размери на миникартинките:',

'newimages'    => 'Галерия на новите файлове',
'showhidebots' => '($1 на ботове)',
'noimages'     => 'Няма нищо.',

/*
Short names for language variants used for language conversion links.
To disable showing a particular link, set it to 'disable', e.g.
'variantname-zh-sg' => 'disable',
Variants for Chinese language
*/
'variantname-zh-cn' => 'cn',
'variantname-zh-tw' => 'tw',
'variantname-zh-hk' => 'hk',
'variantname-zh-sg' => 'sg',
'variantname-zh'    => 'zh',

# Variants for Serbian language
'variantname-sr-ec' => 'sr-ec',
'variantname-sr-el' => 'sr-el',
'variantname-sr-jc' => 'sr-jc',
'variantname-sr-jl' => 'sr-jl',
'variantname-sr'    => 'sr',

# Variants for Kazakh language
'variantname-kk-tr' => 'kk-tr',
'variantname-kk-kz' => 'kk-kz',
'variantname-kk-cn' => 'kk-cn',
'variantname-kk'    => 'kk',

# Labels for User: and Title: on Special:Log pages
'specialloguserlabel'  => 'Потребител:',
'speciallogtitlelabel' => 'Заглавие:',

'passwordtooshort' => 'Паролата Ви е прекалено къса: трябва да съдържа поне $1 знака.',

# Metadata
'metadata'          => 'Метаданни',
'metadata-help'     => 'Файлът съдържа допълнителни данни, обикновенно добавяни от цифровите апарати или скенери. Ако файлът е редактиран след създаването си, то някои параметри може да не съответстват на текущото изборажение.',
'metadata-expand'   => 'Покажи допълнителните данни',
'metadata-collapse' => 'Скрий допълнителните данни',
'metadata-fields'   => 'EXIF данните, показани в това съобщение ще бъдат включени в 
страницата с картинката, когато таблицата с метаданните пропадне. Другите ще са скрити по подразбиране.
* производител
* модел
* дата и време на създаване
* време за експозиция
* F (бленда)
* фокусно разстояние',

# EXIF tags
'exif-imagewidth'                  => 'Ширина',
'exif-imagelength'                 => 'Височина',
'exif-bitspersample'               => 'Дълбочина на цвета /битове/',
'exif-compression'                 => 'Вид компресия',
'exif-photometricinterpretation'   => 'Pixel composition',
'exif-orientation'                 => 'Ориентация',
'exif-samplesperpixel'             => 'Number of components',
'exif-planarconfiguration'         => 'Принцип на организация на данните',
'exif-ycbcrsubsampling'            => 'Subsampling ratio of Y to C',
'exif-ycbcrpositioning'            => 'Y and C positioning',
'exif-xresolution'                 => 'Хоризонтална резолюция',
'exif-yresolution'                 => 'Вертикална резолюция',
'exif-resolutionunit'              => 'Unit of X and Y resolution',
'exif-stripoffsets'                => 'Image data location',
'exif-rowsperstrip'                => 'Number of rows per strip',
'exif-stripbytecounts'             => 'Bytes per compressed strip',
'exif-jpeginterchangeformat'       => 'Offset to JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Bytes of JPEG data',
'exif-transferfunction'            => 'Transfer function',
'exif-whitepoint'                  => 'White point chromaticity',
'exif-primarychromaticities'       => 'Chromaticities of primarities',
'exif-ycbcrcoefficients'           => 'Color space transformation matrix coefficients',
'exif-referenceblackwhite'         => 'Pair of black and white reference values',
'exif-datetime'                    => 'Дата и време на изменението на файла',
'exif-imagedescription'            => 'Название на изображението',
'exif-make'                        => 'Производител',
'exif-model'                       => 'Модел камера',
'exif-software'                    => 'Използван софтуер',
'exif-artist'                      => 'Автор',
'exif-copyright'                   => 'Притежател на авторското право',
'exif-exifversion'                 => 'Exif версия',
'exif-flashpixversion'             => 'Поддържана версия Flashpix',
'exif-colorspace'                  => 'Цветово пространство',
'exif-componentsconfiguration'     => 'Meaning of each component',
'exif-compressedbitsperpixel'      => 'Режим на компресия на образа',
'exif-pixelydimension'             => 'Пълна ширина на изображението',
'exif-pixelxdimension'             => 'Пълна височина на изображението',
'exif-makernote'                   => 'Допълнителни данни на производителя',
'exif-usercomment'                 => 'Допълнителни коментари',
'exif-relatedsoundfile'            => 'Related audio file',
'exif-datetimeoriginal'            => 'Ден и време на създаване',
'exif-datetimedigitized'           => 'Date and time of digitizing',
'exif-subsectime'                  => 'DateTime subseconds',
'exif-subsectimeoriginal'          => 'DateTimeOriginal subseconds',
'exif-subsectimedigitized'         => 'DateTimeDigitized subseconds',
'exif-exposuretime'                => 'Време за експозиция',
'exif-exposuretime-format'         => '$1 сек ($2)',
'exif-fnumber'                     => 'F (бленда)',
'exif-fnumber-format'              => 'f/$1',
'exif-exposureprogram'             => 'Програма на експонацията',
'exif-spectralsensitivity'         => 'Спектрална чувствителност',
'exif-isospeedratings'             => 'Светлочувствителност ISO',
'exif-oecf'                        => 'Optoelectronic conversion factor',
'exif-shutterspeedvalue'           => 'Скорост на затвора',
'exif-aperturevalue'               => 'Диаметър на обектива',
'exif-brightnessvalue'             => 'Яркост',
'exif-exposurebiasvalue'           => 'Отклонение от експонацията',
'exif-maxaperturevalue'            => 'Maximum land aperture',
'exif-subjectdistance'             => 'Разстояние до обекта',
'exif-meteringmode'                => 'Режим на измерване',
'exif-lightsource'                 => 'Източник на светлина',
'exif-flash'                       => 'Светкавица',
'exif-focallength'                 => 'Фокусно разстояние',
'exif-focallength-format'          => '$1 mm',
'exif-subjectarea'                 => 'Subject area',
'exif-flashenergy'                 => 'Мощност на светкавицата',
'exif-spatialfrequencyresponse'    => 'Spatial frequency response',
'exif-focalplanexresolution'       => 'Фокусна равнина X резолюция',
'exif-focalplaneyresolution'       => 'Фокусна равнина Y резолюция',
'exif-focalplaneresolutionunit'    => 'Единица за разделителна способност на фокалната равнина',
'exif-subjectlocation'             => 'Subject location',
'exif-exposureindex'               => 'Индекс на експонацията',
'exif-sensingmethod'               => 'Метод на засичане',
'exif-filesource'                  => 'Файлов източник',
'exif-scenetype'                   => 'Вид сцена',
'exif-cfapattern'                  => 'Стандартен цветови стил',
'exif-customrendered'              => 'Допълнителна обработка на изображението',
'exif-exposuremode'                => 'Режим на експонация',
'exif-whitebalance'                => 'Баланс на бялото',
'exif-digitalzoomratio'            => 'Съотношение на цифровото увеличение',
'exif-focallengthin35mmfilm'       => 'Фокусно разстояние в 35 mm филм',
'exif-scenecapturetype'            => 'Scene capture type',
'exif-gaincontrol'                 => 'Scene control',
'exif-contrast'                    => 'Контраст',
'exif-saturation'                  => 'Наситеност',
'exif-sharpness'                   => 'Изостряне',
'exif-devicesettingdescription'    => 'Описание на настройките на апарата',
'exif-subjectdistancerange'        => 'Subject distance range',
'exif-imageuniqueid'               => 'Уникален идентификатор на изображението',
'exif-gpsversionid'                => 'GPS tag version',
'exif-gpslatituderef'              => 'Северна или южна ширина',
'exif-gpslatitude'                 => 'Географска ширина',
'exif-gpslongituderef'             => '	Източна или западна дължина',
'exif-gpslongitude'                => 'Географска дължина',
'exif-gpsaltituderef'              => 'Altitude reference',
'exif-gpsaltitude'                 => 'Надморска височина',
'exif-gpstimestamp'                => 'GPS време (атомен часвник)',
'exif-gpssatellites'               => 'Satellites used for measurement',
'exif-gpsstatus'                   => 'Receiver status',
'exif-gpsmeasuremode'              => 'Метод за измерване',
'exif-gpsdop'                      => 'Прецизност',
'exif-gpsspeedref'                 => 'Единица за скорост',
'exif-gpsspeed'                    => 'Скорост на GPS приемник',
'exif-gpstrackref'                 => 'Reference for direction of movement',
'exif-gpstrack'                    => 'Посока на движение',
'exif-gpsimgdirectionref'          => 'Reference for direction of image',
'exif-gpsimgdirection'             => 'Direction of image',
'exif-gpsmapdatum'                 => 'Geodetic survey data used',
'exif-gpsdestlatituderef'          => 'Reference for latitude of destination',
'exif-gpsdestlatitude'             => 'Latitude destination',
'exif-gpsdestlongituderef'         => 'Reference for longitude of destination',
'exif-gpsdestlongitude'            => 'Longitude of destination',
'exif-gpsdestbearingref'           => 'Reference for bearing of destination',
'exif-gpsdestbearing'              => 'Bearing of destination',
'exif-gpsdestdistanceref'          => 'Reference for distance to destination',
'exif-gpsdestdistance'             => 'Distance to destination',
'exif-gpsprocessingmethod'         => 'Name of GPS processing method',
'exif-gpsareainformation'          => 'Name of GPS area',
'exif-gpsdatestamp'                => 'GPS дата',
'exif-gpsdifferential'             => 'Диференциална корекция на GPS',

# EXIF attributes
'exif-compression-1' => 'Некомпресиран',
'exif-compression-6' => 'JPEG',

'exif-photometricinterpretation-2' => 'RGB',
'exif-photometricinterpretation-6' => 'YCbCr',

'exif-unknowndate' => 'Неизвестна дата',

'exif-orientation-1' => 'Нормално', # 0th row: top; 0th column: left
'exif-orientation-2' => 'Отражение по хоризонталата', # 0th row: top; 0th column: right
'exif-orientation-3' => 'Обърнато на 180°', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Отражение по вертикалата', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Обърнато на 90° срещу часовниковата стрелка и отразено по вертикалата', # 0th row: left; 0th column: top
'exif-orientation-6' => 'Обърнато на 90° по часовниковата стрелка', # 0th row: right; 0th column: top
'exif-orientation-7' => 'Обърнато на 90° по часовниковата стрелка и отразено по вертикалата', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Обърнато на 90° срещу часовниковата стрелка', # 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'формат "chunky"',
'exif-planarconfiguration-2' => 'формат "planar"',

'exif-xyresolution-i' => '$1 dpi',
'exif-xyresolution-c' => '$1 dpc',

'exif-colorspace-1'      => 'sRGB',
'exif-colorspace-ffff.h' => 'FFFF.H',

'exif-componentsconfiguration-0' => 'не съществаува',
'exif-componentsconfiguration-1' => 'Y',
'exif-componentsconfiguration-2' => 'Cb',
'exif-componentsconfiguration-3' => 'Cr',
'exif-componentsconfiguration-4' => 'R',
'exif-componentsconfiguration-5' => 'G',
'exif-componentsconfiguration-6' => 'B',

'exif-exposureprogram-0' => 'Не е определено',
'exif-exposureprogram-1' => 'Ръчна настройка',
'exif-exposureprogram-2' => 'Нормална програма',
'exif-exposureprogram-3' => 'Приоритет на блендата',
'exif-exposureprogram-4' => 'Приоритет на скоростта',
'exif-exposureprogram-5' => 'Creative program (biased toward depth of field)',
'exif-exposureprogram-6' => 'Action program (biased toward fast shutter speed)',
'exif-exposureprogram-7' => 'Режим Портрет (за снимки в едър план, фонът не е на фокус)',
'exif-exposureprogram-8' => 'Режим Пейзаж (за пейзажни снимки, в които фонът е на фокус)',

'exif-subjectdistance-value' => '$1 метра',

'exif-meteringmode-0'   => 'Unknown',
'exif-meteringmode-1'   => 'Average',
'exif-meteringmode-2'   => 'CenterWeightedAverage',
'exif-meteringmode-3'   => 'Spot',
'exif-meteringmode-4'   => 'MultiSpot',
'exif-meteringmode-5'   => 'Pattern',
'exif-meteringmode-6'   => 'Partial',
'exif-meteringmode-255' => 'Other',

'exif-lightsource-0'   => 'неизвестно',
'exif-lightsource-1'   => 'дневна светлина',
'exif-lightsource-2'   => 'Флуоресцентно осветление',
'exif-lightsource-3'   => 'Волфрамово осветление',
'exif-lightsource-4'   => 'Светкавица',
'exif-lightsource-9'   => 'хубаво време',
'exif-lightsource-10'  => 'облачно',
'exif-lightsource-11'  => 'Сянка',
'exif-lightsource-12'  => 'Daylight fluorescent (D 5700–7100K)',
'exif-lightsource-13'  => 'Day white fluorescent (N 4600–5400K)',
'exif-lightsource-14'  => 'Cool white fluorescent (W 3900–4500K)',
'exif-lightsource-15'  => 'White fluorescent (WW 3200–3700K)',
'exif-lightsource-17'  => 'Standard light A',
'exif-lightsource-18'  => 'Standard light B',
'exif-lightsource-19'  => 'Standard light C',
'exif-lightsource-20'  => 'D55',
'exif-lightsource-21'  => 'D65',
'exif-lightsource-22'  => 'D75',
'exif-lightsource-23'  => 'D50',
'exif-lightsource-24'  => 'Студийна лампа стандарт ISO',
'exif-lightsource-255' => 'друг източник на светлина',

'exif-focalplaneresolutionunit-2' => 'инчове',

'exif-sensingmethod-1' => 'Undefined',
'exif-sensingmethod-2' => 'One-chip color area sensor',
'exif-sensingmethod-3' => 'Two-chip color area sensor',
'exif-sensingmethod-4' => 'Three-chip color area sensor',
'exif-sensingmethod-5' => 'Color sequential area sensor',
'exif-sensingmethod-7' => 'Trilinear sensor',
'exif-sensingmethod-8' => 'Color sequential linear sensor',

'exif-filesource-3' => 'цифров фотоапарат',

'exif-scenetype-1' => 'A directly photographed image',

'exif-customrendered-0' => 'нормален процес',
'exif-customrendered-1' => 'нестандартна обработка',

'exif-exposuremode-0' => 'автоматична експозиция',
'exif-exposuremode-1' => 'ръчна експозиция',
'exif-exposuremode-2' => 'Auto bracket',

'exif-whitebalance-0' => 'Автоматичен баланс на бялото',
'exif-whitebalance-1' => 'Ръчно определяне на баланса на бялото',

'exif-scenecapturetype-0' => 'Стандартен',
'exif-scenecapturetype-1' => 'Ландшафт',
'exif-scenecapturetype-2' => 'Портрет',
'exif-scenecapturetype-3' => 'Нощна сцена',

'exif-gaincontrol-0' => 'Нищо',
'exif-gaincontrol-1' => 'Неголямо увеличение',
'exif-gaincontrol-2' => 'Голямо увеличение',
'exif-gaincontrol-3' => 'Неголямо намаление',
'exif-gaincontrol-4' => 'Силно намаление',

'exif-contrast-0' => 'Нормално',
'exif-contrast-1' => 'Слабо повишение',
'exif-contrast-2' => 'Силно повишение',

'exif-saturation-0' => 'Нормално',
'exif-saturation-1' => 'Неголяма наситеност',
'exif-saturation-2' => 'Голяма наситеност',

'exif-sharpness-0' => 'Нормално',
'exif-sharpness-1' => 'по-меко',
'exif-sharpness-2' => 'по-остро',

'exif-subjectdistancerange-0' => 'Неизвестно',
'exif-subjectdistancerange-1' => 'Macro',
'exif-subjectdistancerange-2' => 'Близко',
'exif-subjectdistancerange-3' => 'Далечно',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'северна ширина',
'exif-gpslatitude-s' => 'южна ширина',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'източна дължина',
'exif-gpslongitude-w' => 'западна дължина',

'exif-gpsstatus-a' => 'Measurement in progress',
'exif-gpsstatus-v' => 'Measurement interoperability',

'exif-gpsmeasuremode-2' => '2-dimensional measurement',
'exif-gpsmeasuremode-3' => '3-dimensional measurement',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'км/час',
'exif-gpsspeed-m' => 'мили/час',
'exif-gpsspeed-n' => 'възли',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'истинска',
'exif-gpsdirection-m' => 'магнитна',

# External editor support
'edit-externally'      => 'Редактиране на файла чрез външно приложение',
'edit-externally-help' => 'За повече информация прегледайте [http://meta.wikimedia.org/wiki/Help:External_editors указанията за настройките].',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'всички',
'imagelistall'     => 'всички',
'watchlistall1'    => 'всички',
'watchlistall2'    => 'всички',
'namespacesall'    => 'всички',

# E-mail address confirmation
'confirmemail'            => 'Потвърждаване на e-mail адрес',
'confirmemail_noemail'    => 'Нямате посочен валиден email адрес в [[Special:Preferences|своите настройки]].',
'confirmemail_text'       => 'Това уики изисква да потвърдите своя e-mail адрес
преди да използвате тази функция. Натиснете бутона по-долу, за да изпратите писмо за потвърждение
на вашия адрес. Писмото ще включва линк, съдържащ код; заредете линка във вашия браузър,
за да потвърдите, че вашият e-mail адрес е валиден.',
'confirmemail_pending'    => '<div class="error">
Кодът за потвърждение вече е изпратен; ако току-що сте се регистрирали, изчакайте няколко минути
да пристигне писмото, преди да поискате нов код.
</div>',
'confirmemail_send'       => 'Изпращане на код за потвърждение',
'confirmemail_sent'       => 'Писмото за потвърждение е изпратено.',
'confirmemail_oncreate'   => 'Кодът за потвърждение вече е изпратен на електронната Ви поща. 
Този код не е необходим за влизане, но ще ви е необходим за активирането на операции в {{SITENAME}}, изискващи електронна поща.',
'confirmemail_sendfailed' => 'Писмото за потвърждение не може да бъде изпратено. Проверете адреса за невалидни символи.
Върнат: $1',
'confirmemail_invalid'    => 'Грешен код за потвърждение. Вероятно кодът за потвърждение е остарял.',
'confirmemail_needlogin'  => 'Длъжни сте да $1, за да потвърдите адреса на електронната си поща.',
'confirmemail_success'    => 'Адресът ви за електронна поща беше потвърден. Вече можете да влезете и да се наслаждавате на уикито.',
'confirmemail_loggedin'   => 'Адресът ви за електронна поща беше потвърден.',
'confirmemail_error'      => 'По време на процедурата по потвърждаване на адреса имаше грешка.',
'confirmemail_subject'    => 'Потвърждаване на адрес за е-поща в {{SITENAME}}',
'confirmemail_body'       => 'Някой, вероятно вие, от IP: $1, е регистрирал потребител "$2" с този e-mail от {{SITENAME}}.

За да потвърдите, че тази сметка и e-mail са ваши от {{SITENAME}}, отворете линка по-долу в браузъра:

$3

Ако това, не сте вие, не следвайте линка. Кодът за потвърждаване ще е невалиден след $4.',

# Inputbox extension, may be useful in other contexts as well
'tryexact'       => 'Пълно и точно съвпадение',
'searchfulltext' => 'Претърсване на целия текст',
'createarticle'  => 'Създаване на статия',

# Scary transclusion
'scarytranscludetoolong' => '[URL е твърде дълъг; съжаляваме]',

# Trackbacks
'trackbackremove' => ' ([$1 Изтриване])',

# Delete conflict
'deletedwhileediting' => 'Внимание: Страницата е била изтрита, откакто сте започнали да я редактирате!',
'confirmrecreate'     => "Потребител [[{{ns:user}}:$1|$1]] ([[{{ns:user_talk}}:$1|беседа]]) е изтрил страницата, откакто сте започнали да я редактирате, давайки следното обяснение:
: ''$2''
Моля, потвърдете, че наистина желаете да създадете страницата отново.",
'recreate'            => 'Ново създаване',

'unit-pixel' => 'px',

# HTML dump
'redirectingto' => 'Пренасочване към [[$1]]...',

# action=purge
'confirm_purge'        => 'Изчистване на кеша на страницата?

$1',
'confirm_purge_button' => 'Добре',

'youhavenewmessagesmulti' => 'Вие получихте нови съобщения на $1',

'searchcontaining' => "Търсене на статии, съдържащи ''$1''.",
'searchnamed'      => "Търсене на статии, именована ''$1''.",
'articletitles'    => "Статии, започващи с ''$1''",
'hideresults'      => 'Скриване на резултатите',

# DISPLAYTITLE
'displaytitle' => '(Препратка на тази страница - [[$1]])',

'loginlanguagelabel' => 'език:$1',

# Multipage image navigation
'imgmultipageprev' => '&larr; previous page',
'imgmultipagenext' => 'next page &rarr;',
'imgmultigo'       => 'Go!',
'imgmultigotopre'  => 'Go to page',

# Table pager
'ascending_abbrev'         => 'asc',
'descending_abbrev'        => 'desc',
'table_pager_next'         => 'Следваща страница',
'table_pager_prev'         => 'Предишна страница',
'table_pager_first'        => 'Първа страница',
'table_pager_last'         => 'Последна страница',
'table_pager_limit'        => 'Показване на $1 записа на страница',
'table_pager_limit_submit' => '>>',
'table_pager_empty'        => 'Няма резултати',

# Auto-summaries
'autosumm-blank'   => 'Премахване на цялото съдържание на страницата',
'autosumm-replace' => "Заместване на съдържанието на страницата с '$1'",
'autoredircomment' => 'Пренасочване към [[$1]]', # This should be changed to the new naming convention, but existed beforehand
'autosumm-new'     => 'Нова страница: $1',

# Size units
'size-bytes'     => '$1 B',
'size-kilobytes' => '$1 KB',
'size-megabytes' => '$1 MB',
'size-gigabytes' => '$1 GB',

);

?>

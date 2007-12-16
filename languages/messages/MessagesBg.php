<?php
/** Bulgarian (Български)
 *
 * @addtogroup Language
 *
 * @author DCLXVI
 * @author לערי ריינהארט
 * @author Helix84
 * @author Spiritia
 * @author BloodIce
 */

$fallback8bitEncoding = 'windows-1251';

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
	'standard'     => 'Класика',
	'nostalgia'    => 'Носталгия',
	'cologneblue'  => 'Кьолнско синьо',
	'monobook'     => 'Монобук',
	'myskin'       => 'Мой облик',
	'chick'        => 'Пиленце'
);

$datePreferences = false;

$bookstoreList = array(
	'books.bg'   => 'http://www.books.bg/ISBN/$1',
	'Пингвините' => 'http://www.pe-bg.com/?cid=3&search_q=$1&where=ISBN&x=0&y=0**',
	'Бард'       => 'http://www.bard.bg/search/?q=$1'
);

$magicWords = array(
#   ID                                 CASE  SYNONYMS
	'redirect'               => array( 0, '#redirect', '#пренасочване', '#виж' ),
	'notoc'                  => array( 0, '__NOTOC__', '__БЕЗСЪДЪРЖАНИЕ__' ),
	'nogallery'              => array( 0, '__NOGALLERY__', '__БЕЗГАЛЕРИЯ__'),
	'forcetoc'               => array( 0, '__FORCETOC__', '__СЪССЪДЪРЖАНИЕ__' ),
	'toc'                    => array( 0, '__TOC__', '__СЪДЪРЖАНИЕ__'),
	'noeditsection'          => array( 0, '__NOEDITSECTION__', '__БЕЗ_РЕДАКТИРАНЕ_НА_РАЗДЕЛИ__'),
	'currentmonth'           => array( 1, 'CURRENTMONTH', 'ТЕКУЩМЕСЕЦ'),
	'currentmonthname'       => array( 1, 'CURRENTMONTHNAME', 'ТЕКУЩМЕСЕЦИМЕ' ),
	'currentmonthnamegen'    => array( 1, 'CURRENTMONTHNAMEGEN', 'ТЕКУЩМЕСЕЦИМЕРОД'),
	'currentmonthabbrev'     => array( 1, 'CURRENTMONTHABBREV', 'ТЕКУЩМЕСЕЦСЪКР'),
	'currentday'             => array( 1, 'CURRENTDAY', 'ТЕКУЩДЕН'),
	'currentday2'            => array( 1, 'CURRENTDAY2', 'ТЕКУЩДЕН2'),
	'currentdayname'         => array( 1, 'CURRENTDAYNAME', 'ТЕКУЩДЕНИМЕ'),
	'currentyear'            => array( 1, 'CURRENTYEAR', 'ТЕКУЩАГОДИНА'),
	'currenttime'            => array( 1, 'CURRENTTIME', 'ТЕКУЩОВРЕМЕ'),
	'currenthour'            => array( 1, 'CURRENTHOUR', 'ТЕКУЩЧАС' ),
	'numberofpages'          => array( 1, 'NUMBEROFPAGES', 'БРОЙСТРАНИЦИ'),
	'numberofarticles'       => array( 1, 'NUMBEROFARTICLES', 'БРОЙСТАТИИ'),
	'numberoffiles'          => array( 1, 'NUMBEROFFILES', 'БРОЙФАЙЛОВЕ'),
	'numberofusers'          => array( 1, 'NUMBEROFUSERS', 'БРОЙПОТРЕБИТЕЛИ'),
	'numberofedits'          => array( 1, 'NUMBEROFEDITS', 'БРОЙРЕДАКЦИИ'),
	'pagename'               => array( 1, 'PAGENAME', 'СТРАНИЦА'),
	'pagenamee'              => array( 1, 'PAGENAMEE', 'СТРАНИЦАИ'),
	'namespace'              => array( 1, 'NAMESPACE', 'ИМЕННОПРОСТРАНСТВО'),
	'namespacee'             => array( 1, 'NAMESPACEE', 'ИМЕННОПРОСТРАНСТВОИ'),
	'subjectspace'           => array( 1, 'SUBJECTSPACE', 'ARTICLESPACE'),
	'subjectspacee'          => array( 1, 'SUBJECTSPACEE', 'ARTICLESPACEE'),
	'fullpagename'           => array( 1, 'FULLPAGENAME', 'ПЪЛНОИМЕ_СТРАНИЦА'),
	'fullpagenamee'          => array( 1, 'FULLPAGENAMEE', 'ПЪЛНОИМЕ_СТРАНИЦАИ'),
	'subpagename'            => array( 1, 'SUBPAGENAME', 'ИМЕ_ПОДСТРАНИЦА'),
	'subpagenamee'           => array( 1, 'SUBPAGENAMEE', 'ИМЕ_ПОДСТРАНИЦАИ'),
	'talkpagename'           => array( 1, 'TALKPAGENAME', 'ИМЕ_БЕСЕДА'),
	'talkpagenamee'          => array( 1, 'TALKPAGENAMEE', 'ИМЕ_БЕСЕДАИ'),
	'subjectpagename'        => array( 1, 'SUBJECTPAGENAME', 'ARTICLEPAGENAME'),
	'subjectpagenamee'       => array( 1, 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE'),
	'msg'                    => array( 0, 'MSG:', 'СЪОБЩ:'),
	'subst'                  => array( 0, 'SUBST:', 'ЗАМЕСТ:'),
	'msgnw'                  => array( 0, 'MSGNW:', 'СЪОБЩБУ:'),
	'img_thumbnail'          => array( 1, 'thumbnail', 'thumb', 'мини'),
	'img_manualthumb'        => array( 1, 'thumbnail=$1', 'thumb=$1', 'мини=$1'),
	'img_right'              => array( 1, 'right', 'вдясно', 'дясно', 'д'),
	'img_left'               => array( 1, 'left', 'вляво', 'ляво', 'л'),
	'img_none'               => array( 1, 'none', 'н'),
	'img_width'              => array( 1, '$1px', '$1пкс' , '$1п'),
	'img_center'             => array( 1, 'center', 'centre', 'център', 'центр', 'ц' ),
	'img_framed'             => array( 1, 'framed', 'enframed', 'frame', 'рамка', 'врамка' ),
	'img_frameless'          => array( 1, 'frameless', 'безрамка'),
	'int'                    => array( 0, 'INT:', 'ВЪТР:'),
	'sitename'               => array( 1, 'SITENAME', 'ИМЕНАСАЙТА'),
	'ns'                     => array( 0, 'NS:', 'ИП:'                    ),
	'localurl'               => array( 0, 'LOCALURL:', 'ЛОКАЛЕНАДРЕС:'),
	'localurle'              => array( 0, 'LOCALURLE:', 'ЛОКАЛЕНАДРЕСИ:'),
	'server'                 => array( 0, 'SERVER', 'СЪРВЪР'),
	'servername'             => array( 0, 'SERVERNAME', 'ИМЕНАСЪРВЪРА'),
	'scriptpath'             => array( 0, 'SCRIPTPATH', 'ПЪТДОСКРИПТА'),
	'grammar'                => array( 0, 'GRAMMAR:', 'ГРАМАТИКА:'),
	'currentweek'            => array( 1, 'CURRENTWEEK', 'ТЕКУЩАСЕДМИЦА'),
	'currentdow'             => array( 1, 'CURRENTDOW', 'ТЕКУЩ_ДЕН_ОТ_СЕДМИЦАТА'),
	'revisionid'             => array( 1, 'REVISIONID', 'ИД_НА_ВЕРСИЯТА'),
	'revisionday'            => array( 1, 'REVISIONDAY', 'ДЕН__НА_ВЕРСИЯТА'),
	'revisionday2'           => array( 1, 'REVISIONDAY2', 'ДЕН__НА_ВЕРСИЯТА2'),
	'revisionmonth'          => array( 1, 'REVISIONMONTH', 'МЕСЕЦ__НА_ВЕРСИЯТА'),
	'revisionyear'           => array( 1, 'REVISIONYEAR', 'ГОДИНА__НА_ВЕРСИЯТА'),
	'plural'                 => array( 0, 'PLURAL:', 'МН_ЧИСЛО:'),
	'fullurl'                => array( 0, 'FULLURL:', 'ПЪЛЕН_АДРЕС:'),
	'fullurle'               => array( 0, 'FULLURLE:', 'ПЪЛЕН_АДРЕСИ:'),
	'lcfirst'                => array( 0, 'LCFIRST:', 'ПЪРВА_БУКВА_МАЛКА:'),
	'ucfirst'                => array( 0, 'UCFIRST:', 'ПЪРВА_БУКВА_ГЛАВНА:'),
	'lc'                     => array( 0, 'LC:', 'МАЛКИ_БУКВИ:'),
	'uc'                     => array( 0, 'UC:', 'ГЛАВНИ_БУКВИ:'),
	'raw'                    => array( 0, 'RAW:', 'НЕОБРАБ:'),
	'displaytitle'           => array( 1, 'DISPLAYTITLE', 'ПОКАЗВ_ЗАГЛАВИЕ'),
);

/**
 * Alternate names of special pages. All names are case-insensitive. The first
 * listed alias will be used as the default. Aliases from the fallback
 * localisation (usually English) will be included by default.
 *
 * This array may be altered at runtime using the LanguageGetSpecialPageAliases
 * hook.
 */
$specialPageAliases = array(
	'DoubleRedirects'           => array('Двойни_пренасочвания'),
	'BrokenRedirects'           => array('Невалидни_пренасочвания'),
	'Disambiguations'           => array('Пояснителни_страници'),
	'Userlogin'                 => array('Регистриране_или_влизане'),
	'Userlogout'                => array('Излизане'),
	'Preferences'               => array('Настройки'),
	'Watchlist'                 => array('Списък_за_наблюдение'),
	'Recentchanges'             => array('Последни_промени'),
	'Upload'                    => array('Качване'),
	'Imagelist'                 => array('Списък_на_картинките'),
	'Newimages'                 => array('Галерия_на_новите_файлове'),
	'Listusers'                 => array('Списък_на_потребителите'),
	'Statistics'                => array('Статистика'),
	'Randompage'                => array('Случайна_статия'),
	'Lonelypages'               => array('Страници-сираци'),
	'Uncategorizedpages'        => array('Некатегоризирани_страници'),
	'Uncategorizedcategories'   => array('Некатегоризирани_категории'),
	'Uncategorizedimages'       => array('Некатегоризирани_картинки'),
	'Uncategorizedtemplates'    => array('Некатегоризирани_шаблони'),
	'Unusedcategories'          => array('Неизползвани_категории'),
	'Unusedimages'              => array('Неизползвани_картинки'),
	'Wantedpages'               => array('Желани_страници'),
	'Wantedcategories'          => array('Желани_категории'),
	'Mostlinked'                => array('Най-препращани_страници'),
	'Mostlinkedcategories'      => array('Най-препращани_категории'),
	'Mostlinkedtemplates'       => array('Най-препращани_шаблони'),
	'Mostcategories'            => array('Страници_с_най-много_категории'),
	'Mostimages'                => array('Най-препращани_картинки'),
	'Mostrevisions'             => array('Страници_с_най-много_версии'),
	'Fewestrevisions'           => array('Страници_с_най-малко_версии'),
	'Shortpages'                => array('Кратки_страници'),
	'Longpages'                 => array('Дълги_страници'),
	'Newpages'                  => array('Нови_страници'),
	'Ancientpages'              => array('Стари_статии'),
	'Deadendpages'              => array('Задънени_страници'),
	'Protectedpages'            => array('Защитени_страници'),
	'Allpages'                  => array('Всички_страници'),
	'Prefixindex'               => array('Азбучен_списък_на_представки') ,
	'Ipblocklist'               => array('Списък_на_блокирани_IP-адреси_и_потребители'),
	'Specialpages'              => array('Специални_страници'),
	'Contributions'             => array('Приноси'),
	'Emailuser'                 => array('Пращане_писмо_на_потребител'),
	'Whatlinkshere'             => array('Какво_сочи_насам'),
	'Recentchangeslinked'       => array('Свързани_промени'),
	'Movepage'                  => array('Преместване_на_страница'),
	'Blockme'                   => array('Блокирай_ме'),
	'Booksources'               => array('Източници_на_книги'),
	'Categories'                => array('Категории'),
	'Export'                    => array('Изнасяне_на_страници'),
	'Version'                   => array('Версия'),
	'Allmessages'               => array('Системни_съобщения'),
	'Log'                       => array('Дневници'),
	'Blockip'                   => array('Блокиране_на_потребител'),
	'Undelete'                  => array('Преглед_на_изтрити_страници'),
	'Import'                    => array('Внасяне_на_страници'),
	'Lockdb'                    => array('Заключване_на_базата_от_данни'),
	'Unlockdb'                  => array('Отключване_на_базата_от_данни'),
	'Userrights'                => array('Управление_на_потребителските_права'),
	'MIMEsearch'                => array('MIME-търсене'),
	'Unwatchedpages'            => array('Ненаблюдавани_страници'),
	'Listredirects'             => array('Списък_на_пренасочванията'),
	'Revisiondelete'            => array('Изтриване_на_версии'),
	'Unusedtemplates'           => array('Неизползвани_шаблони'),
	'Randomredirect'            => array('Случайно_пренасочване'),
	'Mypage'                    => array('Моята_страница'),
	'Mytalk'                    => array('Моята_беседа'),
	'Mycontributions'           => array('Моите_приноси'),
	'Listadmins'                => array('Списък_на_администраторите'),
	'Popularpages'              => array('Известни_страници'),
	'Search'                    => array('Търсене'),
	'Resetpass'                 => array('Изтриване_на_парола'),
	'Withoutinterwiki'          => array('Без_междууикита'),
);

$linkTrail = '/^([a-zабвгдежзийклмнопрстуфхцчшщъыьэюя]+)(.*)$/sDu';

$separatorTransformTable = array(',' => "\xc2\xa0", '.' => ',' );

$messages = array(
# User preference toggles
'tog-underline'               => 'Подчертаване на препратките:',
'tog-highlightbroken'         => 'Показване на невалидните препратки <a href="#" class="new">така</a> (алтернативно: така<a href="#" class="internal">?</a>)',
'tog-justify'                 => 'Двустранно подравняване на абзаците',
'tog-hideminor'               => 'Скриване на малки редакции в последните промени',
'tog-extendwatchlist'         => 'Разширяване на списъка, така че да показва всички промени',
'tog-usenewrc'                => 'Подобряване на последните промени (Джаваскрипт)',
'tog-numberheadings'          => 'Номериране на заглавията',
'tog-showtoolbar'             => 'Помощна лента за редактиране (Джаваскрипт)',
'tog-editondblclick'          => 'Редактиране при двойно щракване (Джаваскрипт)',
'tog-editsection'             => 'Възможност за редактиране на раздел чрез препратка [редактиране]',
'tog-editsectiononrightclick' => 'Възможност за редактиране на раздел при щракване с десния бутон върху заглавие на раздел (Джаваскрипт)',
'tog-showtoc'                 => 'Показване на съдържание (за страници с повече от три раздела)',
'tog-rememberpassword'        => 'Запомняне между сесиите',
'tog-editwidth'               => 'Максимална ширина на кутията за редактиране',
'tog-watchcreations'          => 'Добавяне на създадените от мен страници към списъка ми за наблюдение',
'tog-watchdefault'            => 'Добавяне на редактираните от мен страници към списъка ми за наблюдение',
'tog-watchmoves'              => 'Добавяне на преместените от мен страници към списъка ми за наблюдение',
'tog-watchdeletion'           => 'Добавяне на изтритите от мен страници към списъка ми за наблюдение',
'tog-minordefault'            => 'Отбелязване на всички промени като малки по подразбиране',
'tog-previewontop'            => 'Показване на предварителния преглед преди текстовата кутия',
'tog-previewonfirst'          => 'Показване на предварителен преглед при първа редакция',
'tog-nocache'                 => 'Без складиране на страниците',
'tog-enotifwatchlistpages'    => 'Уведомяване по е-пощата при промяна на страница от списъка ми за наблюдение',
'tog-enotifusertalkpages'     => 'Уведомяване по е-пощата при промяна на беседата ми',
'tog-enotifminoredits'        => 'Уведомяване по е-пощата даже при малки промени',
'tog-enotifrevealaddr'        => 'Показване на електронния ми адрес в известяващите писма',
'tog-shownumberswatching'     => 'Показване на броя на потребителите, наблюдаващи дадена страница',
'tog-fancysig'                => 'Без превръщане на подписа в препратка към потребителската страница',
'tog-externaleditor'          => 'Използване на външен редактор по подразбиране',
'tog-externaldiff'            => 'Използване на външна програма за разлики по подразбиране',
'tog-showjumplinks'           => 'Показване на препратки за достъпност от типа „Към…“',
'tog-uselivepreview'          => 'Използване на бърз предварителен преглед (Джаваскрипт) (експериментално)',
'tog-forceeditsummary'        => 'Предупреждаване при празно поле за резюме на редакцията',
'tog-watchlisthideown'        => 'Скриване на моите редакции в списъка ми за наблюдение',
'tog-watchlisthidebots'       => 'Скриване на редакциите на ботове в списъка ми за наблюдение',
'tog-watchlisthideminor'      => 'Скриване на малките промени в списъка ми за наблюдение',
'tog-nolangconversion'        => 'Без преобразувания при различни езикови варианти',
'tog-ccmeonemails'            => 'Получаване на копия на писмата, които пращам на другите потребители',
'tog-diffonly'                => 'Без показване на съдържанието на страницата при преглед на разлики',

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
'sun'           => 'нд',
'mon'           => 'пн',
'tue'           => 'вт',
'wed'           => 'ср',
'thu'           => 'чт',
'fri'           => 'пт',
'sat'           => 'сб',
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
'pagecategories'        => '{{PLURAL:$1|Категория|Категории}}',
'category_header'       => 'Страници в категория „$1“',
'subcategories'         => 'Подкатегории',
'category-media-header' => 'Файлове в категория „$1“',
'category-empty'        => "''В момента тази категория не съдържа страници или файлове.''",

'mainpagetext'      => "<big>'''Уикито беше успешно инсталирано.'''</big>",
'mainpagedocfooter' => 'Разгледайте [http://meta.wikimedia.org/wiki/Help:Contents ръководството] за подробна информация относно използването на софтуера.

== Първи стъпки ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Конфигурационни настройки]
* [http://www.mediawiki.org/wiki/Manual:FAQ ЧЗВ за МедияУики]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Пощенски списък относно нови версии на МедияУики]',

'about'          => 'За {{SITENAME}}',
'article'        => 'Страница',
'newwindow'      => '(отваря се в нов прозорец)',
'cancel'         => 'Отказ',
'qbfind'         => 'Търсене',
'qbbrowse'       => 'Избор',
'qbedit'         => 'Редактиране',
'qbpageoptions'  => 'Тази страница',
'qbpageinfo'     => 'Информация за страницата',
'qbmyoptions'    => 'Моите страници',
'qbspecialpages' => 'Специални страници',
'moredotdotdot'  => 'Още…',
'mypage'         => 'Моята страница',
'mytalk'         => 'Моята беседа',
'anontalk'       => 'Беседа за адреса',
'navigation'     => 'Навигация',

# Metadata in edit box
'metadata_help' => 'Метаданни:',

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
'updatedmarker'     => 'има промяна (от последното ви влизане)',
'info_short'        => 'Информация',
'printableversion'  => 'Версия за печат',
'permalink'         => 'Постоянна препратка',
'print'             => 'Печат',
'edit'              => 'Редактиране',
'editthispage'      => 'Редактиране',
'delete'            => 'Изтриване',
'deletethispage'    => 'Изтриване',
'undelete_short'    => 'Възстановяване на {{PLURAL:$1|една редакция|$1 редакции}}',
'protect'           => 'Защита',
'protect_change'    => 'смяна на защитата',
'protectthispage'   => 'Защита',
'unprotect'         => 'Сваляне на защитата',
'unprotectthispage' => 'Сваляне на защитата',
'newpage'           => 'Нова страница',
'talkpage'          => 'Дискусионна страница',
'talkpagelinktext'  => 'Беседа',
'specialpage'       => 'Специална страница',
'personaltools'     => 'Лични инструменти',
'postcomment'       => 'Оставяне на съобщение',
'articlepage'       => 'Преглед на страница',
'talk'              => 'Беседа',
'views'             => 'Прегледи',
'toolbox'           => 'Инструменти',
'userpage'          => 'Потребителска страница',
'projectpage'       => 'Проектна страница',
'imagepage'         => 'Преглед на файла',
'mediawikipage'     => 'Преглед на съобщението',
'templatepage'      => 'Преглед на шаблона',
'viewhelppage'      => 'Преглед на помощната страница',
'categorypage'      => 'Преглед на категорията',
'viewtalkpage'      => 'Преглед на беседата',
'otherlanguages'    => 'На други езици',
'redirectedfrom'    => '(пренасочване от $1)',
'redirectpagesub'   => 'Пренасочваща страница',
'lastmodifiedat'    => 'Последна промяна на страницата: $2, $1.', # $1 date, $2 time
'viewcount'         => 'Страницата е била преглеждана {{plural:$1|един път|$1 пъти}}.',
'protectedpage'     => 'Защитена страница',
'jumpto'            => 'Направо към:',
'jumptonavigation'  => 'навигация',
'jumptosearch'      => 'търсене',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'За {{SITENAME}}',
'aboutpage'         => 'Project:За {{SITENAME}}',
'bugreports'        => 'Съобщения за грешки',
'bugreportspage'    => 'Project:Съобщения за грешки',
'copyright'         => 'Съдържанието е достъпно при условията на $1.',
'copyrightpagename' => 'Авторски права в {{SITENAME}}',
'copyrightpage'     => '{{ns:project}}:Авторски права',
'currentevents'     => 'Текущи събития',
'currentevents-url' => 'Project:Текущи събития',
'disclaimers'       => 'Условия за ползване',
'disclaimerpage'    => 'Project:Условия за ползване',
'edithelp'          => 'Помощ при редактиране',
'edithelppage'      => 'Help:Как се редактират страници',
'faq'               => 'ЧЗВ',
'faqpage'           => 'Project:ЧЗВ',
'helppage'          => 'Help:Съдържание',
'mainpage'          => 'Начална страница',
'policy-url'        => 'Project:Политика',
'portal'            => 'Портал за общността',
'portal-url'        => 'Project:Портал',
'privacy'           => 'Защита на личните данни',
'privacypage'       => 'Project:Защита на личните данни',
'sitesupport'       => 'Дарения',
'sitesupport-url'   => 'Project:Подкрепа',

'badaccess'        => 'Грешка при достъп',
'badaccess-group0' => 'Нямате права да извършите исканото действие',
'badaccess-group1' => 'Исканото действие могат да изпълнят само потребители от групата $1.',
'badaccess-group2' => 'Исканото действие могат да изпълнят само потребители от групите $1.',
'badaccess-groups' => 'Исканото действие могат да изпълнят само потребители от групите $1.',

'versionrequired'     => 'Изисква се версия $1 на МедияУики',
'versionrequiredtext' => 'За да използвате тази страница, е необходима версия $1 на МедияУики. Вижте [[{{ns:special}}:Version]].',

'ok'                      => 'Добре',
'pagetitle'               => '$1 — {{SITENAME}}',
'retrievedfrom'           => 'Взето от „$1“.',
'youhavenewmessages'      => 'Имате $1 ($2).',
'newmessageslink'         => 'нови съобщения',
'newmessagesdifflink'     => 'разлика с предишната версия',
'youhavenewmessagesmulti' => 'Получихте нови съобщения на $1',
'editsection'             => 'редактиране',
'editold'                 => 'редактиране',
'editsectionhint'         => 'Редактиране на раздел: $1',
'toc'                     => 'Съдържание',
'showtoc'                 => 'показване',
'hidetoc'                 => 'скриване',
'thisisdeleted'           => 'Преглед или възстановяване на $1?',
'viewdeleted'             => 'Преглед на $1?',
'restorelink'             => '{{PLURAL:$1|една изтрита редакция|$1 изтрити редакции}}',
'feedlinks'               => 'Във вида:',
'feed-invalid'            => 'Невалиден формат на информацията',

# Short words for each namespace, by default used in the namespace tab in monobook
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
'nospecialpagetext' => 'Отправихте заявка за невалидна [[{{ns:special}}:Specialpages|специална страница]].',

# General errors
'error'                => 'Грешка',
'databaseerror'        => 'Грешка при работа с базата от данни',
'dberrortext'          => 'Възникна синтактична грешка при заявка към базата от данни.
Последната заявка към базата от данни беше:
<blockquote><code>$1</code></blockquote>
при функцията „<code>$2</code>“.
MySQL дава грешка „<code>$3: $4</code>“.',
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
'readonlytext'         => 'Базата от данни е временно затворена за промени — вероятно за рутинна поддръжка, след която ще бъде отново на разположение.
Администраторът, който я е затворил, дава следното обяснение:
$1',
'missingarticle'       => 'Текстът на страницата „$1“ не беше намерен в базата от данни.

Това обикновено е причинено от последването на остаряла разликова или версионна препратка към изтрита страница.

Ако не това е причината, е възможно да сте открили грешка в системата.
Моля, съобщете за това на администратор, като включите и името на страницата.',
'readonly_lag'         => 'Базата от данни беше автоматично заключена, докато подчинените сървъри успеят да се съгласуват с основния сървър.',
'internalerror'        => 'Вътрешна грешка',
'internalerror_info'   => 'Вътрешна грешка: $1',
'filecopyerror'        => 'Файлът „$1“ не можа да бъде копиран като „$2“.',
'filerenameerror'      => 'Файлът „$1“ не можа да бъде преименуван на „$2“.',
'filedeleteerror'      => 'Файлът „$1“ не можа да бъде изтрит.',
'directorycreateerror' => 'Невъзможно е да бъде създадена директория "$1".',
'filenotfound'         => 'Файлът „$1“ не беше намерен.',
'fileexistserror'      => 'Невъзможност за запис във файл "$1": файлът съществува',
'unexpected'           => 'Неочаквана стойност: „$1“=„$2“.',
'formerror'            => 'Възникна грешка при изпращане на формуляра',
'badarticleerror'      => 'Действието не може да се изпълни върху страницата.',
'cannotdelete'         => 'Указаната страница или файл не можа да бъде изтрит(а). Възможно е вече да е изтрит(а) от някой друг.',
'badtitle'             => 'Невалидно заглавие',
'badtitletext'         => 'Желаното заглавие на страница е невалидно, празно или неправилна препратка към друго уики. Възможно е да съдържа знаци, които не са позволени в заглавия.',
'perfdisabled'         => 'Съжаляваме! Това свойство е временно изключено, защото забавя базата от данни дотам, че никой не може да използва уикито.',
'perfcached'           => 'Следните данни са извлечени от склада и затова може да не отговарят на текущото състояние:',
'perfcachedts'         => 'Данните са складирани и обновени за последно на $1.',
'querypage-no-updates' => 'Обновяването на тази страница в момента е изключено. Засега данните тук няма да бъдат обновявани.',
'wrong_wfQuery_params' => 'Невалидни аргументи за wfQuery()<br />
Функция: $1<br />
Заявка: $2',
'viewsource'           => 'Защитена страница',
'viewsourcefor'        => 'за $1',
'actionthrottledtext'  => 'Като част от защитата против спам, многократното повтаряне на това действие за кратък период от време е ограничено и вие вече сте надвишили лимита си. Моля, опитайте отново след няколко минути.',
'protectedpagetext'    => 'Тази страница е заключена за редактиране.',
'viewsourcetext'       => 'Можете да разгледате и да копирате кодa на страницата:',
'protectedinterface'   => 'Тази страница съдържа текст, нужен за работата на системата. Тя е защитена против редактиране, за да се предотвратят възможни злоупотреби.',
'editinginterface'     => "'''Внимание:''' Редактирате страница, която се използва за интерфейса на софтуера. Промяната й ще повлияе на външния вид на уикито.",
'sqlhidden'            => '(Заявка на SQL — скрита)',
'cascadeprotected'     => 'Тази страница е защитена против редактиране, защото е включена в {{PLURAL:$1|следната страница, която от своя страна има|следните страници, които от своя страна имат}} каскадна защита:',
'namespaceprotected'   => "Нямате права за редактиране на страници в именно пространство '''$1'''.",
'customcssjsprotected' => 'Нямате права за редактиране на тази страница, защото тя съдържа чужди потребителски настройки.',
'ns-specialprotected'  => 'Страници от именно пространство {{ns:special}} не могат да бъдат редактирани.',

# Login and logout pages
'logouttitle'                => 'Излизане от системата',
'logouttext'                 => '<strong>Излязохте от системата.</strong>

Можете да продължите да използвате {{SITENAME}} анонимно или да влезете отново като друг потребител. Обърнете внимание, че някои страници все още ще се показват така, сякаш сте влезли, докато не изтриете кеш-паметта на браузъра.',
'welcomecreation'            => '== Добре дошли, $1! ==

Вашата сметка беше успешно открита. Сега можете да промените настройките на {{SITENAME}} по ваш вкус.',
'loginpagetitle'             => 'Влизане в системата',
'yourname'                   => 'Потребителско име:',
'yourpassword'               => 'Парола:',
'yourpasswordagain'          => 'Парола (повторно):',
'remembermypassword'         => 'Запомняне на паролата',
'yourdomainname'             => 'Домейн:',
'externaldberror'            => 'Или е станала грешка в базата от данни при външното удостоверяване, или не ви е позволено да обновявате външната си сметка.',
'loginproblem'               => '<strong>Имаше проблем с влизането ви.</strong><br />Опитайте отново!',
'login'                      => 'Влизане',
'loginprompt'                => "За влизане в {{SITENAME}} е необходимо да въведете потребителското си име и парола и да натиснете бутона '''Влизане''', като, за да бъде това успешно, бисквитките (cookies) трябва да са разрешени в браузъра ви.

Ако все още не сте се регистрирали (нямате открита сметка), лесно можете да сторите това, като последвате препратката '''Създаване на сметка'''.",
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
'youremail'                  => 'Е-поща:',
'username'                   => 'Потребителско име:',
'uid'                        => 'Потребителски номер:',
'yourrealname'               => 'Истинско име:',
'yourlanguage'               => 'Език:',
'yourvariant'                => 'Вариант',
'yournick'                   => 'Псевдоним (за подписи чрез ~~~~):',
'badsig'                     => 'Избраният подпис не е валиден. Проверете HTML-етикетите!',
'badsiglength'               => 'Максимално допустимата дължина на подписа е $1 знака.',
'email'                      => 'Е-поща',
'prefs-help-realname'        => '* <strong>Истинско име</strong> <em>(незадължително)</em>: Ако го посочите, на него ще бъдат приписани вашите приноси.',
'loginerror'                 => 'Грешка при влизане',
'prefs-help-email'           => '* <strong>Електронна поща</strong> <em>(незадължително)</em>: Позволява на хората да се свържат с вас, без да се налага да им съобщавате адреса си, а също може да се използва, за да ви се изпрати нова парола, ако случайно забравите сегашната си.',
'prefs-help-email-required'  => 'Изисква се адрес за електронна поща.',
'nocookiesnew'               => 'Потребителската сметка беше създадена, но все още не сте влезли. {{SITENAME}} използва бисквитки при влизане на потребителите. Моля, разрешете бисквитките във вашия браузър, тъй като те са забранени, и след това влезте с потребителското си име и парола.',
'nocookieslogin'             => '{{SITENAME}} използва бисквитки (cookies) за запис на влизанията. Моля, разрешете бисквитките във вашия браузър, тъй като те са забранени, и опитайте отново.',
'noname'                     => 'Не указахте валидно потребителско име.',
'loginsuccesstitle'          => 'Успешно влизане',
'loginsuccess'               => "'''Влязохте в {{SITENAME}} като „$1“.'''",
'nosuchuser'                 => 'Няма потребител с името „$1“.
Проверете изписването или се регистрирайте, използвайки долния формуляр.',
'nosuchusershort'            => 'Няма потребител с името „$1“. Проверете изписването.',
'nouserspecified'            => 'Трябва да посочите потребителско име.',
'wrongpassword'              => 'Въведената парола е невалидна. Моля, опитайте отново.',
'wrongpasswordempty'         => 'Въведената парола е празна. Моля, опитайте отново.',
'passwordtooshort'           => 'Паролата ви е прекалено къса: трябва да съдържа поне $1 знака.',
'mailmypassword'             => 'Изпращане на нова парола',
'passwordremindertitle'      => 'Напомняне за парола от {{SITENAME}}',
'passwordremindertext'       => 'Някой (най-вероятно вие, от IP-адрес $1) помоли да ви изпратим нова парола за влизане в {{SITENAME}} ($4).
Паролата за потребителя „$2“ е „$3“.
Сега би трябвало да влезете в системата и да смените паролата си.

Ако заявката е направена от друг или пък сте си спомнили паролата и не искате да я променяте, можете да пренебрегнете това съобщение и да продължите да използвате старата си парола.',
'noemail'                    => 'Няма записана електронна поща за потребителя „$1“.',
'passwordsent'               => 'Нова парола беше изпратена на електронната поща на „$1“.
Моля, влезте отново, след като я получите.',
'blocked-mailpassword'       => 'Редактирането от вашия IP-адрес е забранено, затова не ви е позволено да използвате възможността за възстановяване на загубена парола.',
'eauthentsent'               => 'Писмото за потвърждение е изпратено на посочения адрес. В него са описани действията, които трябва да се извършат, за да потвърдите, че този адрес за електронна поща действително е ваш.',
'throttled-mailpassword'     => 'Функцията за напомняне на паролата е използвана през последните $1 часа.
За предотвратяване на злоупотреби е разрешено да се изпраща не повече от едно напомняне в рамките на $1 часа.',
'mailerror'                  => 'Грешка при изпращане на писмо: $1',
'acct_creation_throttle_hit' => 'Съжаляваме, вече сте създали $1 сметки и нямате право на повече.',
'emailauthenticated'         => 'Адресът на електронната ви поща беше потвърден на $1.',
'emailnotauthenticated'      => 'Адресът на електронната ви поща <strong>не е потвърден</strong>. Няма да получавате писма за никоя от следните възможности.',
'noemailprefs'               => '<strong>Не е указан адрес за електронна поща</strong>, функциите няма да работят.',
'emailconfirmlink'           => 'Потвърждаване на адреса за електронна поща',
'invalidemailaddress'        => 'Въведеният адрес не може да бъде приет, тъй като не съответства на формата на адрес за електронна поща. Моля, въведете коректен адрес или оставете полето празно.',
'accountcreated'             => 'Потребителската сметка беше създадена',
'accountcreatedtext'         => 'Потребителската сметка за $1 беше създадена.',
'createaccount-title'        => 'Създаване на сметка за {{SITENAME}}',
'createaccount-text'         => 'Някой ($1) е създал сметка за $2 в {{SITENAME}}
($4). Паролата за "$2" е "$3". Влезте в системата и сменете паролата си сега.

Можете да пренебрегнете това съобщение, ако сметката е създадена по грешка.',
'loginlanguagelabel'         => 'Език: $1',

# Password reset dialog
'resetpass'               => 'Смяна на паролата',
'resetpass_announce'      => 'Влязохте с временен код, получен по електронната поща. Сега е нужно да си изберете нова парола:',
'resetpass_text'          => '<!-- Тук добавете текст -->',
'resetpass_header'        => 'Смяна на паролата',
'resetpass_submit'        => 'Избиране на парола и влизане',
'resetpass_success'       => 'Паролата ви беше сменена! Сега влизате…',
'resetpass_bad_temporary' => 'Невалидна временна парола. Възможно е вече да сте променили паролата си или пък да сте поискали нова временна парола.',
'resetpass_forbidden'     => 'На това уики не е разрешена смяната на парола',
'resetpass_missing'       => 'Липсват формулярни данни.',

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
'anoneditwarning'           => "'''Внимание:''' Не сте влезли в системата. В историята на страницата ще бъде записан вашият IP-адрес.",
'missingsummary'            => "'''Напомняне:''' Не сте въвели кратко описание на промените. При повторно натискане на бутона „Съхранение“, редакцията ви ще бъде съхранена без резюме.",
'missingcommenttext'        => 'Моля, въведете по-долу вашето съобщение.',
'missingcommentheader'      => "'''Напомняне:''' Не сте въвели заглавие на коментара. При повторно натискане на бутона „Съхранение“, редакцията ви ще бъде записана без такова.",
'summary-preview'           => 'Предварителен преглед на резюмето',
'subject-preview'           => 'Предварителен преглед на заглавието',
'blockedtitle'              => 'Потребителят е блокиран',
'blockedtext'               => "<big>'''Вашето потребителско име (или IP-адрес) е блокирано.'''</big>

Блокирането е извършено от $1. Посочената причина е: ''$2''

Край на блокирането: $6<br />
Блокирането се отнася за: $7

Можете да се свържете с $1 или с някой от останалите [[{{MediaWiki:Grouppage-sysop}}|администратори]], за да обсъдите блокирането.

Можете да използвате услугата „Пращане писмо на потребител“ само ако не ви е забранена употребата й и ако сте посочили валидна електронна поща в [[{{ns:special}}:Preferences|настройките]] си.

Вашият IP-адрес е $3, а номерът на блока е $5. Моля, включвайте едно от двете или и двете във всяко питане, което правите.",
'autoblockedtext'           => "Вашият IP-адрес беше блокиран автоматично, защото е бил използван от друг потребител, който е бил блокиран от $1.
Посочената причина е:

:''$2''

Край на блокирането: $6

Можете да се свържете с $1 или с някой от останалите [[{{MediaWiki:Grouppage-sysop}}|администратори]], за да обсъдите блокирането.

Можете да използвате услугата „Пращане писмо на потребител“ само ако не ви е забранена употребата й и ако сте посочили валидна електронна поща в [[{{ns:special}}:Preferences|настройките]] си.

Номерът на блокирането ви е $5. Моля, включвайте го във всяко питане, което правите.",
'blockednoreason'           => 'не е указана причина',
'blockedoriginalsource'     => "По-долу е показано съдържанието на '''$1''':",
'blockededitsource'         => "По долу е показан текстът на '''вашите редакции''' на '''$1''':",
'whitelistedittitle'        => 'Необходимо е да влезете, за да може да редактирате',
'whitelistedittext'         => 'Необходимо е да $1, за да може да редактирате страници.',
'whitelistreadtitle'        => 'Необходимо е да влезете, за да може да четете страници',
'whitelistreadtext'         => 'Необходимо е да [[{{ns:special}}:Userlogin|влезете]], за да може да четете страници.',
'whitelistacctitle'         => 'Не ви е позволено да създавате сметка',
'whitelistacctext'          => 'За да ви бъде позволено създаването на сметки, трябва да [[{{ns:special}}:Userlogin|влезете]] и да имате подходящото разрешение.',
'confirmedittitle'          => 'Необходимо е потвърждение на адреса ви за електронна поща',
'confirmedittext'           => 'Необходимо е да потвърдите електронната си поща, преди да редактирате страници. Моля, въведете и потвърдете адреса си на [[{{ns:special}}:Preferences|страницата с настройките]].',
'nosuchsectiontitle'        => 'Няма такъв раздел',
'nosuchsectiontext'         => 'Опитахте да редактирате несъществуващия раздел $1. Поради тази причина е невъзможно редакцията ви да бъде съхранена.',
'loginreqtitle'             => 'Изисква се влизане',
'loginreqlink'              => 'влизане',
'loginreqpagetext'          => 'Необходимо е да $1, за да може да разглеждате други страници.',
'accmailtitle'              => 'Паролата беше изпратена.',
'accmailtext'               => 'Паролата за „$1“ беше изпратена на $2.',
'newarticle'                => '(нова)',
'newarticletext'            => 'Последвахте препратка към страница, която все още не съществува.
За да я създадете, просто започнете да пишете в долната текстова кутия
(вижте [[{{MediaWiki:Helppage}}|помощната страница]] за повече информация).',
'anontalkpagetext'          => "----
''Това е дискусионната страница на анонимен потребител, който  все още няма сметка или не я използва, затова се налага да използваме IP-адрес, за да го/я идентифицираме. Такъв адрес може да се споделя от няколко потребители.''

''Ако сте анонимен потребител и мислите, че тези неуместни коментари са отправени към вас, моля, [[{{ns:special}}:Userlogin|регистрирайте се или влезте в системата]], за да избегнете евентуално бъдещо объркване с други анонимни потребители.''",
'noarticletext'             => "(Тази страница все още не съществува. Можете да я създадете, като щракнете на '''Редактиране'''.)",
'clearyourcache'            => "'''Бележка:''' След съхранението е необходимо да изтриете кеша на браузъра, за да видите промените:
'''Mozilla / Firefox / Safari:''' натиснете бутона ''Shift'' и щракнете върху ''Презареждане'' (''Reload''), или изберете клавишната комбинация ''Ctrl-Shift-R'' (''Cmd-Shift-R'' за Apple Mac);
'''IE:''' натиснете ''Ctrl'' и щракнете върху ''Refresh'', или клавишната комбинация ''CTRL-F5'';
'''Konqueror:''' щракнете върху ''Презареждане'' или натиснете ''F5'';
'''Opera:''' вероятно е необходимо да изчистите кеша през менюто ''Tools→Preferences''.",
'usercssjsyoucanpreview'    => '<strong>Съвет:</strong> Използвайте бутона „Предварителен преглед“, за да изпробвате новия код на CSS/Джаваскрипт преди съхранението.',
'usercsspreview'            => "'''Не забравяйте, че това е само предварителен преглед на кода на CSS. Страницата все още не е съхранена!'''",
'userjspreview'             => "'''Не забравяйте, че това е само изпробване/предварителен преглед на кода на Джаваскрипт. Страницата все още не е съхранена!'''",
'userinvalidcssjstitle'     => "'''Внимание:''' Не е намерен облик „$1“. Не забравяйте, че имената на потребителските ви страници за CSS и Джаваскрипт трябва да се състоят от малки букви, например: „{{ns:user}}:Иван/monobook.css“ (а не „{{ns:user}}:Иван/Monobook.css“).",
'updated'                   => '(обновена)',
'note'                      => '<strong>Забележка:</strong>',
'previewnote'               => '<strong>Не забравяйте, че това е само предварителен преглед и страницата все още не е съхранена!</strong>',
'previewconflict'           => 'Този предварителен преглед отразява текста в горната текстова кутия така, както би се показал, ако съхраните.',
'session_fail_preview'      => '<strong>За съжаление редакцията ви не успя да бъде обработена, поради загуба на данните за текущата сесия. Моля, опитайте отново. Ако все още не работи, опитайте да излезете и да влезете наново.</strong>',
'session_fail_preview_html' => "<strong>За съжаление редакцията ви не беше записана поради изтичането на сесията ви.</strong>

''Тъй като уикито приема обикновен HTML, предварителният преглед е скрит като предпазна мярка срещу атаки чрез Джаваскрипт.''

<strong>Опитайте отново. Ако все още не сработва, пробвайте да излезете и влезете отново.</strong>",
'token_suffix_mismatch'     => '<strong>Редакцията ви беше отхвърлена, защото браузърът ви е развалил пунктуационните знаци в редакционната отметка. Евентуалното съхранение би унищожило съдържанието на страницата. Понякога това се случва при използването на грешно работещи анонимни междинни сървъри.</strong>',
'editing'                   => 'Редактиране на „$1“',
'editinguser'               => 'Редактиране на „$1“',
'editingsection'            => 'Редактиране на „$1“ (раздел)',
'editingcomment'            => 'Редактиране на „$1“ (нов раздел)',
'editconflict'              => 'Различна редакция: $1',
'explainconflict'           => 'Някой друг вече е променил тази страница, откакто започнахте да я редактирате.
Горната текстова кутия съдържа текущия текст на страницата без вашите промени, които са показани в долната кутия. За да бъдат и те съхранени, е необходимо ръчно да ги преместите в горното поле, тъй като <strong>единствено</strong> текстът в него ще бъде съхранен при натискането на бутона „Съхранение“.<br />',
'yourtext'                  => 'Вашият текст',
'storedversion'             => 'Съхранена версия',
'nonunicodebrowser'         => '<strong>ВНИМАНИЕ: Браузърът ви не поддържа Уникод. За да можете спокойно да редактирате страници, всички символи, невключени в ASCII-таблицата, ще бъдат заменени с шестнадесетични кодове.</strong>',
'editingold'                => '<strong>ВНИМАНИЕ: Редактирате остаряла версия на страницата.
Ако съхраните, всякакви промени, направени след тази версия, ще бъдат изгубени.</strong>',
'yourdiff'                  => 'Разлики',
'copyrightwarning'          => 'Моля, обърнете внимание на това, че всички приноси към {{SITENAME}} се публикуват при условията на $2 (за подробности вижте $1).
Ако не сте съгласни вашата писмена работа да бъде променяна и разпространявана без ограничения, не я публикувайте.<br />

Също потвърждавате, че <strong>вие</strong> сте написали материала или сте използвали <strong>свободни ресурси</strong> — <em>обществено достояние</em> или друг свободен източник.
Ако сте ползвали чужди материали, за които имате разрешение, непременно посочете източника.

<div style="font-variant:small-caps"><strong>Не публикувайте произведения с авторски права без разрешение!</strong></div>',
'copyrightwarning2'         => 'Моля, обърнете внимание на това, че всички приноси към {{SITENAME}} могат да бъдат редактирани, променяни или премахвани от останалите сътрудници.
Ако не сте съгласни вашата писмена работа да бъде променяна без ограничения, не я публикувайте.<br />
Също потвърждавате, че <strong>вие</strong> сте написали материала или сте използвали <strong>свободни ресурси</strong> — <em>обществено достояние</em> или друг свободен източник (за подробности вижте $1).
Ако сте ползвали чужди материали, за които имате разрешение, непременно посочете източника.

<div style="font-variant:small-caps"><strong>Не публикувайте произведения с авторски права без разрешение!</strong></div>',
'longpagewarning'           => '<strong>ВНИМАНИЕ: Страницата има размер $1 килобайта; някои браузъри могат да имат проблеми при редактиране на страници по-големи от 32 KB.
Моля, обмислете дали страницата не може да се раздели на няколко по-малки части.</strong>',
'longpageerror'             => '<strong>ГРЕШКА: Текстът, който пращате, е с големина $1 килобайта, което надвишава позволения максимум от $2 килобайта. Заради това не може да бъде съхранен.</strong>',
'readonlywarning'           => '<strong>ВНИМАНИЕ: Базата от данни беше затворена за поддръжка, затова в момента промените ви не могат да бъдат съхранени. Ако желаете, можете да съхраните страницата като текстов файл и да се опитате да я публикувате по-късно.</strong>',
'protectedpagewarning'      => '<strong>ВНИМАНИЕ: Страницата е защитена и само администратори могат да я редактират.</strong>',
'semiprotectedpagewarning'  => "'''Забележка:''' Страница е защитена, като само регистрирани потребители могат да я редактират.",
'cascadeprotectedwarning'   => "'''Внимание:''' Страницата е защитена, като само потребители с администраторски права могат да я редактират. Тя е включена в {{PLURAL:$1|следната страница|следните страници}} с каскадна защита:",
'templatesused'             => 'Шаблони, използвани на страницата:',
'templatesusedpreview'      => 'Шаблони, използвани в предварителния преглед:',
'templatesusedsection'      => 'Шаблони, използвани в този раздел:',
'template-protected'        => '(защитен)',
'template-semiprotected'    => '(полузащитен)',
'edittools'                 => '<!-- Евентуален текст тук ще бъде показван под формулярите за редактиране и качване. -->',
'nocreatetitle'             => 'Създаването на страници е ограничено',
'nocreatetext'              => 'Създаването на нови страници на този сайт е ограничено. Можете да се върнете назад и да редактирате някоя от съществуващите страници, [[{{ns:special}}:Userlogin|да се регистрирате или да създадете нова потребителска сметка]].',
'nocreate-loggedin'         => 'Нямате необходимите права да създавате нови страници в това уики.',
'permissionserrorstext'     => 'Нямате правата да извършите това действие по {{PLURAL:$1|следната причина|следните причини}}:',
'recreate-deleted-warn'     => "'''Внимание: Създавате страница, която по-рано вече е била изтрита.'''

Обмислете добре дали е уместно повторното създаване на страницата.
За ваша информация по-долу е посочена причината за предишното изтриване на страницата:",

# "Undo" feature
'undo-success' => 'Редакцията може да бъде върната. Прегледайте долното сравнение и се уверете, че наистина искате да го направите. След това съхранете страницата, за да извършите връщането.',
'undo-failure' => 'Редакцията не може да бъде върната поради конфликтни междинни редакции.',
'undo-summary' => 'Премахната редакция $1 на [[{{ns:special}}:Contributions/$2|$2]] ([[User_talk:$2|беседа]])',

# Account creation failure
'cantcreateaccounttitle' => 'Невъзможно е да бъде създадена потребителска сметка.',
'cantcreateaccount-text' => "[[User:$3|Потребител:$3]] е блокирал(а) създаването на сметки от този IP-адрес (<b>$1</b>).

Причината, изложена от $3, е ''$2''",

# History pages
'viewpagelogs'        => 'Преглед на извършените административни действия по страницата',
'nohistory'           => 'Няма редакционна история за тази страница.',
'revnotfound'         => 'Версията не е открита',
'revnotfoundtext'     => 'Желаната стара версия на страницата не беше открита.
Моля, проверете адреса, който използвахте за достъп до страницата.',
'loadhist'            => 'Зареждане историята на страницата',
'currentrev'          => 'Текуща версия',
'revisionasof'        => 'Версия от $1',
'revision-info'       => 'Версия от $1 на $2',
'previousrevision'    => '←По-стара версия',
'nextrevision'        => 'По-нова версия→',
'currentrevisionlink' => 'преглед на текущата версия',
'cur'                 => 'тек',
'next'                => 'след',
'last'                => 'пред',
'orig'                => 'ориг',
'page_first'          => 'първа',
'page_last'           => 'последна',
'histlegend'          => '<em>Разлики:</em> Изберете версиите, които желаете да сравните, чрез превключвателите срещу тях и натиснете &lt;Enter&gt; или бутона за сравнение.<br />
<em>Легенда:</em> (<strong>тек</strong>) = разлика с текущата версия, (<strong>пред</strong>) = разлика с предишната версия, <strong>м</strong>&nbsp;=&nbsp;малка промяна',
'deletedrev'          => '[изтрита]',
'histfirst'           => 'Първи',
'histlast'            => 'Последни',
'historysize'         => '($1 байта)',
'historyempty'        => '(празна)',

# Revision feed
'history-feed-title'          => 'Редакционна история',
'history-feed-description'    => 'Редакционна история на страницата в {{SITENAME}}',
'history-feed-item-nocomment' => '$1 в $2', # user at time
'history-feed-empty'          => 'Исканата страница не съществува — може да е била изтрита или преименувана. Опитайте да [[{{ns:special}}:Search|потърсите]] нови страници, които биха могли да са ви полезни.',

# Revision deletion
'rev-deleted-comment'         => '(коментарът е изтрит)',
'rev-deleted-user'            => '(името на автора е изтрито)',
'rev-deleted-event'           => '(записът е изтрит)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
Тази версия на страницата е изтрита от общодостъпния архив.
Възможно е обяснения да има в [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} дневника на изтриванията].
</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">
Тази версия на страницата е изтрита от общодостъпния архив.
Като администратор на този сайт, вие можете да я видите;
Възможно е обяснения да има в [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} дневника на изтриванията].
</div>',
'rev-delundel'                => 'показване/скриване',
'revisiondelete'              => 'Изтриване/възстановяване на версии',
'revdelete-nooldid-title'     => 'Не е зададена версия',
'revdelete-nooldid-text'      => 'Не сте задали версия или версии за изпълнението на тази функция.',
'revdelete-selected'          => "{{PLURAL:$2|Избрана версия|Избрани версии}} от '''$1:'''",
'logdelete-selected'          => "{{PLURAL:$2|Избрано събитие|Избрани събития}} за '''$1:'''",
'revdelete-text'              => 'Изтритите версии ше се показват в историята на страницата, но тяхното съдържание ще бъде недостъпно за обикновенните потребители.

Администраторите на това уики имат достъп до скритото съдържание и могат да го възстановят, с изключение на случаите, когато има наложено допълнително ограничение.',
'revdelete-legend'            => 'Задаване на ограничения:',
'revdelete-hide-text'         => 'Скриване на текста на версията',
'revdelete-hide-name'         => 'Скриване на действието и целта',
'revdelete-hide-comment'      => 'Скриване на коментара',
'revdelete-hide-user'         => 'Скриване на името/IP-адреса на автора',
'revdelete-hide-restricted'   => 'Прилагане на тези ограничения и към администраторите',
'revdelete-suppress'          => 'Скриване на причината за изтриването и от администраторите',
'revdelete-hide-image'        => 'Скриване на файловото съдържание',
'revdelete-unsuppress'        => 'Премахване на ограниченията за възстановените версии',
'revdelete-log'               => 'Коментар:',
'revdelete-submit'            => 'Прилагане към избраната версия',
'revdelete-logentry'          => 'промени видимостта на версия на [[$1]]',
'logdelete-logentry'          => 'промени видимостта на събитие за [[$1]]',
'revdelete-logaction'         => '$1 {{plural:$1|версия|версии}} сменени на режим $2',
'logdelete-logaction'         => '$1 {{plural:$1|събитие|събития}} за [[$3]] сменени на режим $2',
'revdelete-success'           => 'Видимостта на версията беше променена.',
'logdelete-success'           => 'Видимостта на събитието беше променена.',

# Oversight log
'oversightlog'    => 'Дневник на потулванията',
'overlogpagetext' => 'Това е списък на последните изтривания и блокирания, които са скрити от администраторите. Можете да прегледате [[{{ns:special}}:Ipblocklist|списъка на текущите блокирания]].',

# History merging
'mergehistory'                     => 'Сливане на редакционни истории',
'mergehistory-header'              => "Тази страница ви позволява да сливате историята на редакциите на дадена изходна страница с историята на нова страница. Уверете се, че тази промяна ще запази целостта на историята.

'''Поне текущата редакция на изходната страница трябва да остане.'''",
'mergehistory-box'                 => 'Сливане на редакционните истории на две страници:',
'mergehistory-from'                => 'Изходна страница:',
'mergehistory-into'                => 'Целева страница:',
'mergehistory-submit'              => 'Сливане на редакции',
'mergehistory-empty'               => 'Няма редакции, които могат да бъдат слети',
'mergehistory-success'             => '$3 редакции от [[:$1]] бяха успешно слети с редакционната история на [[:$2]].',
'mergehistory-fail'                => 'Невъзможно е да се извърши сливане на редакционните истории; проверете страницата и времевите параметри.',
'mergehistory-no-source'           => 'Изходната страница $1 не съществува.',
'mergehistory-no-destination'      => 'Целевата страница $1 не съществува.',
'mergehistory-invalid-source'      => 'Изходната страница трябва да притежава коректно име.',
'mergehistory-invalid-destination' => 'Целевата страница трябва да притежава коректно име.',

# Merge log
'mergelog'           => 'Дневник на сливанията',
'pagemerge-logentry' => 'обедини [[$1]] с [[$2]] (до редакция $3)',
'revertmerge'        => 'Разделяне',
'mergelogpagetext'   => 'Страницата съдържа списък с последните сливания на редакционни истории.',

# Diffs
'history-title'           => 'Преглед на историята на "$1"',
'difference'              => '(Разлики между версиите)',
'lineno'                  => 'Ред $1:',
'compareselectedversions' => 'Сравнение на избраните версии',
'editundo'                => 'връщане',
'diff-multi'              => '({{plural:$1|Една междинна версия не е показана|$1 междинни версии не са показани}}.)',

# Search results
'searchresults'         => 'Резултати от търсенето',
'searchresulttext'      => 'За повече информация относно {{SITENAME}}, вижте [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'        => 'За заявка „[[:$1]]“',
'searchsubtitleinvalid' => 'За заявка „$1“',
'noexactmatch'          => "В {{SITENAME}} не съществува страница с това заглавие. Можете да я '''[[:$1|създадете]]'''.",
'titlematches'          => 'Съответствия в заглавията на страници',
'notitlematches'        => 'Няма съответствия в заглавията на страници',
'textmatches'           => 'Съответствия в текста на страници',
'notextmatches'         => 'Няма съответствия в текста на страници',
'prevn'                 => 'предишни $1',
'nextn'                 => 'следващи $1',
'viewprevnext'          => 'Преглед ($1) ($2) ($3).',
'showingresults'        => "Показване на до {{PLURAL:$1|'''1''' резултат|'''$1''' резултата}}, като се започва от номер '''$2'''.",
'showingresultsnum'     => "Показване на {{PLURAL:$3|'''1''' резултат|'''$3''' резултата}}, като се започва от номер '''$2'''.",
'nonefound'             => "'''Забележка''': Безрезултатните търсения често са причинени от това, че се търсят основни думи като „има“ или „от“, които не се индексират, или от това, че се търсят повече от една думи, тъй като се показват само страници, съдържащи всички зададени понятия.",
'powersearch'           => 'Търсене',
'powersearchtext'       => 'Търсене в именни пространства:<br />$1<br />$2 Показване на пренасочвания<br />Търсене на $3 $9',
'searchdisabled'        => 'Търсенето в {{SITENAME}} е временно изключено. Междувременно можете да търсите чрез Google. Обърнете внимание, че съхранените при тях страници най-вероятно са остарели.',

# Preferences page
'preferences'              => 'Настройки',
'mypreferences'            => 'Моите настройки',
'prefs-edits'              => 'Брой редакции:',
'prefsnologin'             => 'Не сте влезли',
'prefsnologintext'         => 'Необходимо е да [[{{ns:special}}:Userlogin|влезете]], за да може да променяте потребителските си настройки.',
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
'math_image_error'         => 'Превръщането към PNG не сполучи. Проверете дали latex, dvips, gs и convert са правилно инсталирани.',
'math_bad_tmpdir'          => 'Невъзможно е писането във или създаването на временна директория за математическите операции',
'math_bad_output'          => 'Невъзможно е писането във или създаването на изходяща директория за математическите операции',
'math_notexvc'             => 'Липсва изпълнимият файл на texvc. Моля, прегледайте math/README за информация относно конфигурирането.',
'prefs-personal'           => 'Потребителски данни',
'prefs-rc'                 => 'Последни промени и мъничета',
'prefs-watchlist'          => 'Списък за наблюдение',
'prefs-watchlist-days'     => 'Брой дни, които да се показват в списъка за наблюдение:',
'prefs-watchlist-edits'    => 'Брой редакции, които се показват в разширения списък за наблюдение:',
'prefs-misc'               => 'Други настройки',
'saveprefs'                => 'Съхранение',
'resetprefs'               => 'Възстановяване на стандартните настройки',
'oldpassword'              => 'Стара парола:',
'newpassword'              => 'Нова парола:',
'retypenew'                => 'Нова парола повторно:',
'textboxsize'              => 'Редактиране',
'rows'                     => 'Редове:',
'columns'                  => 'Колони:',
'searchresultshead'        => 'Търсене',
'resultsperpage'           => 'Резултати на страница:',
'contextlines'             => 'Редове за резултат:',
'contextchars'             => 'Знаци от контекста на ред:',
'stub-threshold'           => 'Праг за форматиране на <a href="#" class="stub">препратки към мъничета</a>:',
'recentchangesdays'        => 'Брой дни в последни промени:',
'recentchangescount'       => 'Брой редакции в последни промени:',
'savedprefs'               => 'Вашите настройки бяха съхранени.',
'timezonelegend'           => 'Времева зона',
'timezonetext'             => 'Броят часове, с които вашето местно време се различава от това на сървъра (UTC).',
'localtime'                => 'Местно време',
'timezoneoffset'           => 'Отместване¹',
'servertime'               => 'Време на сървъра',
'guesstimezone'            => 'Попълване чрез браузъра',
'allowemail'               => 'Възможност за получаване на писма от други потребители',
'defaultns'                => 'Търсене в тези именни пространства по подразбиране:',
'default'                  => 'по подразбиране',
'files'                    => 'Файлове',

# User rights
'userrights-lookup-user'      => 'Управляване на потребителските групи',
'userrights-user-editname'    => 'Въведете потребителско име:',
'editusergroup'               => 'Редактиране на потребителските групи',
'userrights-editusergroup'    => 'Редактиране на потребителските групи',
'saveusergroups'              => 'Съхранение на потребителските групи',
'userrights-groupsmember'     => 'Член на:',
'userrights-groupsavailable'  => 'Групи на разположение:',
'userrights-groupshelp'       => 'Изберете групите, към които искате той да бъде прибавен или от които да бъде премахнат. Неизбраните групи няма да бъдат променени. Можете да отизберете група чрез &lt;CTRL&gt; + ляв бутон на мишката',
'userrights-reason'           => 'Причина за смяната:',
'userrights-available-none'   => 'Не можете да променяте групова принадлежност.',
'userrights-available-add'    => 'Можете да добавяте потребители към групите $1.',
'userrights-available-remove' => 'Можете да премахвате потребители от групите $1.',

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
'rightsnone'     => '(никакви)',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|промяна|промени}}',
'recentchanges'                     => 'Последни промени',
'recentchangestext'                 => "Проследяване на последните промени в {{SITENAME}}.

Легенда: '''тек''' = разлика на текущата версия,
'''ист''' = история на версиите, '''м'''&nbsp;=&nbsp;малка промяна, <strong class='newpage'>Н</strong>&nbsp;=&nbsp;новосъздадена страница",
'recentchanges-feed-description'    => 'Проследяване на последните промени в {{SITENAME}}.',
'rcnote'                            => "{{PLURAL:$1|Показана е '''1''' промяна|Показани са последните '''$1''' промени}} през {{PLURAL:$2|последния ден|последните '''$2''' дни}}, към $3.",
'rcnotefrom'                        => 'Дадени са промените от <strong>$2</strong> (до <strong>$1</strong> показани).',
'rclistfrom'                        => 'Показване на промени, като се започва от $1.',
'rcshowhideminor'                   => '$1 на малки промени',
'rcshowhidebots'                    => '$1 на ботове',
'rcshowhideliu'                     => '$1 на влезли потребители',
'rcshowhideanons'                   => '$1 на анонимни потребители',
'rcshowhidepatr'                    => '$1 на проверени редакции',
'rcshowhidemine'                    => '$1 на моите приноси',
'rclinks'                           => 'Показване на последните $1 промени през последните $2 дни<br />$3',
'diff'                              => 'разл',
'hist'                              => 'ист',
'hide'                              => 'Скриване',
'show'                              => 'Показване',
'minoreditletter'                   => 'м',
'newpageletter'                     => 'Н',
'boteditletter'                     => 'б',
'number_of_watching_users_pageview' => '[$1 наблюдаващ(и) потребител(и)]',
'rc_categories'                     => 'Само от категории (разделител „|“)',
'rc_categories_any'                 => 'Която и да е',
'newsectionsummary'                 => 'Нова тема /* $1 */',

# Recent changes linked
'recentchangeslinked'          => 'Свързани промени',
'recentchangeslinked-title'    => 'Свързани с $1 промени',
'recentchangeslinked-noresult' => 'Няма промени в свързаните страници за дадения период.',
'recentchangeslinked-summary'  => "Тази специална страница показва последните промени в свързаните страници. Страниците от списъка ви за наблюдение се показват в '''получер'''.",

# Upload
'upload'                      => 'Качване',
'uploadbtn'                   => 'Качване',
'reupload'                    => 'Повторно качване',
'reuploaddesc'                => 'Връщане към формуляра за качване.',
'uploadnologin'               => 'Не сте влезли',
'uploadnologintext'           => 'Необходимо е да [[{{ns:special}}:Userlogin|влезете]], за да може да качвате файлове.',
'upload_directory_read_only'  => 'Сървърът няма достъп за писане в директорията за качване „$1“.',
'uploaderror'                 => 'Грешка при качване',
'uploadtext'                  => "Използвайте долния формуляр, за да качвате файлове, които ще можете да използвате в страниците.
В повечето браузъри ще видите бутон „Browse…“ (ако използвате преведен интерфейс, можете да видите „Избор на файл…“, „Избор…“ и др.), който ще отвори основния за вашата операционна система диалогов прозорец за избиране на файл.

За да включите картинка (файл) в страница, използвайте една от следните препратки: '''<nowiki>[[{{ns:image}}:картинка.jpg|алтернативен текст]]</nowiki>''' за изображения или '''<nowiki>[[{{ns:media}}:звук.ogg]]</nowiki>''' за музикални файлове.

За да прегледате съществуващите в базата от данни файлове, разгледайте [[{{ns:special}}:Imagelist|списъка с качените файлове]].
Качванията и изтриванията се записват в [[{{ns:special}}:Log/upload|дневника на качванията]].",
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
'minlength1'                  => 'Имената на файловете трябва да съдържат поне един знак.',
'illegalfilename'             => 'Името на файла „$1“ съдържа знаци, които не са позволени в заглавия на страници. Моля, преименувайте файла и се опитайте да го качите отново.',
'badfilename'                 => 'Файлът беше преименуван на „$1“.',
'filetype-badmime'            => 'Не е разрешено качването на файлове с MIME-тип „$1“.',
'filetype-missing'            => 'Файлът няма разширение (напр. „.jpg“).',
'large-file'                  => 'Не се препоръчва файловете да се по-големи от $1; този файл е $2.',
'largefileserver'             => 'Файлът е по-голям от допустимия от сървъра размер.',
'emptyfile'                   => 'Каченият от вас файл е празен. Това може да е предизвикано от грешка в името на файла. Моля, уверете се дали наистина искате да го качите.',
'fileexists'                  => 'Вече съществува файл с това име! Моля, прегледайте <strong><tt>$1</tt></strong>, ако не сте сигурни дали искате да го промените.',
'fileexists-extension'        => 'Съществува файл със сходно име:<br />
Име на качвания файл: <strong><tt>$1</tt></strong><br />
Име на съществуващия файл: <strong><tt>$2</tt></strong><br />
Има разлика единствено в разширенията на файловете, изразяваща се в ползване на малки и главни букви. Моля, проверете дали файловете не са еднакви.',
'fileexists-thumb'            => "<center>'''Съществуваща картинка'''</center>",
'fileexists-thumbnail-yes'    => 'Изглежда, че файлът е картинка с намален размер <i>(миникартинка)</i>. Моля, проверете файла <strong><tt>$1</tt></strong>.<br />
Ако съществуващият файл представлява оригиналната версия на картинката, няма нужда да се качва неин умален вариант.',
'file-thumbnail-no'           => 'Файловото име започва с <strong><tt>$1</tt></strong>. Изглежда, че е картинка с намален размер <i>(миникартинка)</i>.
Ако разполагате с версия в пълна разделителна способност, качете нея. В противен случай сменете името на този файл.',
'fileexists-forbidden'        => 'Вече съществува файл с това име! Моля, върнете се и качете файла с ново име. [[{{ns:image}}:$1|мини|центр|$1]]',
'fileexists-shared-forbidden' => 'В споделеното файлово хранилище вече съществува файл с това име! Моля, върнете се и качете файла с ново име. [[{{ns:image}}:$1|мини|центр|$1]]',
'successfulupload'            => 'Качването беше успешно',
'uploadwarning'               => 'Предупреждение при качване',
'savefile'                    => 'Съхраняване на файл',
'uploadedimage'               => 'качена „[[$1]]“',
'overwroteimage'              => 'качена е нова версия на "[[$1]]"',
'uploaddisabled'              => 'Качванията са забранени.',
'uploaddisabledtext'          => 'В това уики качването на файлове е забранено.',
'uploadscripted'              => 'Файлът съдържа HTML или скриптов код, който може да бъде погрешно  интерпретиран от браузъра.',
'uploadcorrupt'               => 'Файлът е повреден или е с неправилно разширение. Моля, проверете го и го качете отново.',
'uploadvirus'                 => 'Файлът съдържа вирус! Подробности: $1',
'sourcefilename'              => 'Първоначално име',
'destfilename'                => 'Целево име',
'watchthisupload'             => 'Наблюдаване на страницата',
'filewasdeleted'              => 'Файл в този име е съществувал преди време, но е бил изтрит. Моля проверете $1 преди отново да го качите.',
'upload-wasdeleted'           => "'''Внимание: Качвате файл, който вече е бил изтрит.'''

Преценете дали е удачно да продължите с качването на файла. За ваше удобство, ето записа за него в дневника на изтриванията:",
'filename-bad-prefix'         => 'Името на файла, който качвате, започва с <strong>"$1"</strong>, което е неописателно име, типично задавано по автоматичен начин от цифровите камери. Моля, изберете по-описателно име за този файл.',

'upload-proto-error'      => 'Неправилен протокол',
'upload-proto-error-text' => 'Изисква се адрес започващ с <code>http://</code> или <code>ftp://</code>.',
'upload-file-error'       => 'Вътрешна грешка',
'upload-file-error-text'  => 'Вътрешна грешка при опит да се създаде временен файл на сървъра. Моля, обърнете се към системен администратор.',
'upload-misc-error'       => 'Неизвестна грешка при качване',
'upload-misc-error-text'  => 'Неизвестна грешка при качване. Моля, убедете се, че адресът е верен и опитайте отново. Ако отново имате проблем, обърнете се към системен администратор.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Не е възможно достигането на указания URL адрес',
'upload-curl-error6-text'  => 'Търсеният адрес не може да бъде достигнат. Моля, проверете дали е написан вярно.',
'upload-curl-error28'      => 'Времето за качване изтече',
'upload-curl-error28-text' => 'Сайтът не отговаря твърде дълго. Моля, убедете се, че сайтът работи, изчакайте малко и опитайте отново. В краен случай опитайте през по-ненатоварено време.',

'license'            => 'Лицензиране',
'nolicense'          => 'Нищо не е избрано',
'upload_source_url'  => ' (правилен, публично достъпен интернет-адрес)',
'upload_source_file' => ' (файл на вашия компютър)',

# Image list
'imagelist'                 => 'Списък на файловете',
'imagelisttext'             => "Списък от {{plural:$1|един файл|'''$1''' файла, сортирани $2}}.",
'getimagelist'              => 'донасяне на списъка с файлове',
'ilsubmit'                  => 'Търсене',
'showlast'                  => 'Показване на последните $1 файла, сортирани $2.',
'byname'                    => 'по име',
'bydate'                    => 'по дата',
'bysize'                    => 'по размер',
'imgdelete'                 => 'изтр',
'imgdesc'                   => 'опис',
'imgfile'                   => 'файл',
'filehist'                  => 'История на файла',
'filehist-help'             => 'Избирането на дата/час ще покаже как е изглеждал файлът към онзи момент.',
'filehist-deleteall'        => 'изтриване на всички',
'filehist-revert'           => 'връщане',
'filehist-current'          => 'текуща',
'filehist-datetime'         => 'Дата/Час',
'filehist-user'             => 'Потребител',
'filehist-dimensions'       => 'Размер',
'filehist-filesize'         => 'Размер на файла',
'filehist-comment'          => 'Коментар',
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
'imagelist_size'            => 'Размер',
'imagelist_description'     => 'Описание',
'imagelist_search_for'      => 'Търсене по име на файла:',

# File reversion
'filerevert'                => 'Възвръщане на $1',
'filerevert-legend'         => 'Възвръщане на файла',
'filerevert-intro'          => '<span class="plainlinks">Възвръщане на \'\'\'[[Media:$1|$1]]\'\'\' към [$4 версията от $3, $2].</span>',
'filerevert-comment'        => 'Коментар:',
'filerevert-defaultcomment' => 'Възвръщане към версия от $2, $1',
'filerevert-submit'         => 'Възвръщане',
'filerevert-success'        => '<span class="plainlinks">Файлът \'\'\'[[Media:$1|$1]]\'\'\' беше възвърнат към [$4 версия от $3, $2].</span>',

# File deletion
'filedelete'             => 'Изтриване на $1',
'filedelete-legend'      => 'Изтриване на файл',
'filedelete-intro'       => "Изтривате '''[[Media:$1|$1]]'''.",
'filedelete-intro-old'   => '<span class="plainlinks">Изтривате версията на \'\'\'[[Media:$1|$1]]\'\'\' към [$4 $3, $2].</span>',
'filedelete-comment'     => 'Коментар:',
'filedelete-submit'      => 'Изтриване',
'filedelete-success'     => "Файлът '''$1''' беше изтрит.",
'filedelete-success-old' => '<span class="plainlinks">Версията на \'\'\'[[Media:$1|$1]]\'\'\' към $3, $2 е била изтрита.</span>',
'filedelete-nofile'      => "Файлът '''$1''' не съществува на сайта.",
'filedelete-nofile-old'  => "Не съществува архивна версия на '''$1''' с указаните параметри.",
'filedelete-iscurrent'   => 'Опитвате се да изтриете последната версия на този файл. Моля, първо направете възвръщане към по-стара версия.',

# MIME search
'mimesearch'         => 'MIME-търсене',
'mimesearch-summary' => 'На тази страница можете да филтрирате файловете по техния MIME-тип. Заявката трябва да се състои от медиен тип и подтип, разделени с наклонена черта (слеш), напр. <tt>image/jpeg</tt>.',
'mimetype'           => 'MIME-тип:',
'download'           => 'сваляне',

# Unwatched pages
'unwatchedpages' => 'Ненаблюдавани страници',

# List redirects
'listredirects' => 'Списък на пренасочванията',

# Unused templates
'unusedtemplates'     => 'Неизползвани шаблони',
'unusedtemplatestext' => 'Тази страница съдържа списък на шаблоните, които не са включени в друга страница. Проверявайте за препратки към отделните шаблони преди да ги изтриете или предложите за изтриване.',
'unusedtemplateswlh'  => 'други препратки',

# Random page
'randompage'         => 'Случайна страница',
'randompage-nopages' => 'В това именно пространство няма страници.',

# Random redirect
'randomredirect'         => 'Случайно пренасочване',
'randomredirect-nopages' => 'В това именно пространство няма пренасочвания.',

# Statistics
'statistics'             => 'Статистика',
'sitestats'              => 'Статистика на {{SITENAME}}',
'userstats'              => 'Потребители',
'sitestatstext'          => "Базата от данни съдържа '''$1''' страници.
Това включва всички страници от всички именни пространства в {{SITENAME}} (''Основно'', Беседа, {{ns:Project}}, Потребител, Категория, ...). Измежду тях '''$2''' страници се смятат за действителни (изключват се пренасочванията и страниците, несъдържащи препратки).

'''$8''' files have been uploaded.

There have been a total of '''$3''' page views, and '''$4''' page edits
since the wiki was setup.
That comes to '''$5''' average edits per page, and '''$6''' views per edit.

The [http://meta.wikimedia.org/wiki/Help:Job_queue job queue] length is '''$7'''.",
'userstatstext'          => "Има {{PLURAL:$1|'''1''' регистриран потребител|'''$1''' регистрирани потребители}} и '''$2''' {{PLURAL:$2|потребител|потребители}} (или '''$4%''') с права на $5.",
'statistics-mostpopular' => 'Най-преглеждани страници',

'disambiguations'      => 'Пояснителни страници',
'disambiguationspage'  => 'Template:Пояснение',
'disambiguations-text' => "Следните страници сочат към '''пояснителна страница''', вместо към истинската тематична страница.<br />Една страница се смята за пояснителна, ако ползва шаблон, към който се препраща от [[MediaWiki:disambiguationspage]]",

'doubleredirects'     => 'Двойни пренасочвания',
'doubleredirectstext' => 'Всеки ред съдържа препратки към първото и второто пренасочване, както и целта на второто пренасочване, която обикновено е „истинската“ страница, към която първото пренасочване би трябвало да сочи.',

'brokenredirects'        => 'Невалидни пренасочвания',
'brokenredirectstext'    => 'Следните пренасочващи страници сочат към несъществуващи страници:',
'brokenredirects-edit'   => '(редактиране)',
'brokenredirects-delete' => '(изтриване)',

'withoutinterwiki'        => 'Страници без междуезикови препратки',
'withoutinterwiki-header' => 'Следните страници не препращат към версии на други езици:',

'fewestrevisions' => 'Страници с най-малко версии',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|байт|байта}}',
'ncategories'             => '$1 {{PLURAL:$1|категория|категории}}',
'nlinks'                  => '$1 {{PLURAL:$1|препратка|препратки}}',
'nmembers'                => '$1 {{PLURAL:$1|член|члена}}',
'nrevisions'              => '$1 {{PLURAL:$1|версия|версии}}',
'nviews'                  => '$1 {{PLURAL:$1|преглед|прегледа}}',
'specialpage-empty'       => 'Страницата е празна.',
'lonelypages'             => 'Страници-сираци',
'lonelypagestext'         => 'Към следващите страници няма препратки от други страници на тази енциклопедия.',
'uncategorizedpages'      => 'Некатегоризирани страници',
'uncategorizedcategories' => 'Некатегоризирани категории',
'uncategorizedimages'     => 'Некатегоризирани картинки',
'uncategorizedtemplates'  => 'Некатегоризирани шаблони',
'unusedcategories'        => 'Неизползвани категории',
'unusedimages'            => 'Неизползвани файлове',
'popularpages'            => 'Известни страници',
'wantedcategories'        => 'Желани категории',
'wantedpages'             => 'Желани страници',
'mostlinked'              => 'Най-препращани страници',
'mostlinkedcategories'    => 'Най-препращани категории',
'mostlinkedtemplates'     => 'Най-препращани шаблони',
'mostcategories'          => 'Страници с най-много категории',
'mostimages'              => 'Най-препращани картинки',
'mostrevisions'           => 'Страници с най-много версии',
'allpages'                => 'Всички страници',
'prefixindex'             => 'Азбучен списък на представки',
'shortpages'              => 'Кратки страници',
'longpages'               => 'Дълги страници',
'deadendpages'            => 'Задънени страници',
'deadendpagestext'        => 'Следните страници нямат препратки към други страници от уикито.',
'protectedpages'          => 'Защитени страници',
'protectedpagestext'      => 'Следните страници са защитени против редактиране или преместване',
'protectedpagesempty'     => 'В момента няма защитени страници с тези параметри.',
'listusers'               => 'Списък на потребителите',
'specialpages'            => 'Специални страници',
'spheading'               => 'Специални страници за всички потребители',
'restrictedpheading'      => 'Специални страници с ограничен достъп',
'newpages'                => 'Нови страници',
'newpages-username'       => 'Потребител:',
'ancientpages'            => 'Стари страници',
'intl'                    => 'Междуезикови препратки',
'move'                    => 'Преместване',
'movethispage'            => 'Преместване на страницата',
'unusedimagestext'        => 'Моля, обърнете внимание на това, че други сайтове могат да сочат към картинката чрез пряк адрес и въпреки това тя може да се намира в списъка.',
'unusedcategoriestext'    => 'Следните категории съществуват, но никоя страница или категория не ги използва.',
'notargettitle'           => 'Няма цел',
'notargettext'            => 'Не указахте целева страница или потребител, върху която/който да се изпълни действието.',
'pager-newer-n'           => '{{PLURAL:$1|по-нова 1|по-нови $1}}',
'pager-older-n'           => '{{PLURAL:$1|по-стара 1|по-стари $1}}',

# Book sources
'booksources'               => 'Източници на книги',
'booksources-search-legend' => 'Търсене на информация за книга',
'booksources-go'            => 'Търсене',
'booksources-text'          => 'По-долу е списъкът от връзки към други сайтове, продаващи нови и използвани книги или имащи повече информация за книгите, които търсите:',

'categoriespagetext' => 'В {{SITENAME}} съществуват следните категории.',
'data'               => 'Данни',
'userrights'         => 'Управление на потребителските права',
'groups'             => 'Потребителски групи',
'alphaindexline'     => 'от $1 до $2',
'version'            => 'Версия',

# Special:Log
'specialloguserlabel'  => 'Потребител:',
'speciallogtitlelabel' => 'Заглавие:',
'log'                  => 'Дневници',
'all-logs-page'        => 'Всички дневници',
'log-search-legend'    => 'Претърсване на дневниците',
'log-search-submit'    => 'Отиване',
'alllogstext'          => 'Смесено показване на дневниците на качванията, изтриванията, защитата, блокиранията и бюрократите.
Можете да ограничите прегледа, като изберете вид на дневника, потребителско име или определена страница.',
'logempty'             => 'Дневникът не съдържа записи, отговарящи на избрания критерий.',
'log-title-wildcard'   => 'Търсене на заглавия, започващи със',

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
'allpagesbadtitle'  => 'Зададеното име е невалидно. Възможно е да съдържа междуезикова или междупроектна представка или пък знаци, които не могат да се използват в заглавия.',
'allpages-bad-ns'   => 'В {{SITENAME}} не съществува именно пространство „$1“.',

# Special:Listusers
'listusersfrom'      => 'Показване на потребителите, започвайки от:',
'listusers-submit'   => 'Показване',
'listusers-noresult' => 'Няма намерени потребители.',

# E-mail user
'mailnologin'     => 'Няма електронна поща',
'mailnologintext' => 'Необходимо е да [[{{ns:special}}:Userlogin|влезете]] и да посочите валидна електронна поща в [[{{ns:special}}:Preferences|настройките]] си, за да може да пращате писма на други потребители.',
'emailuser'       => 'Пращане писмо на потребителя',
'emailpage'       => 'Пращане писмо на потребител',
'emailpagetext'   => 'Ако потребителят е посочил валидна електронна поща в настройките си, чрез долния формуляр можете да му изпратите съобщение. Адресът, записан в настройките ви, ще се появи в полето „От“ на изпратеното писмо, така че получателят ще е в състояние да ви отговори.',
'usermailererror' => 'Пощенският обект даде грешка:',
'defemailsubject' => 'Писмо от {{SITENAME}}',
'noemailtitle'    => 'Няма електронна поща',
'noemailtext'     => 'Потребителят не е посочил валидна електронна поща или е избрал да не получава писма от други потребители.',
'emailfrom'       => 'От',
'emailto'         => 'До',
'emailsubject'    => 'Относно',
'emailmessage'    => 'Съобщение',
'emailsend'       => 'Изпращане',
'emailccme'       => 'Получаване на копие на писмото.',
'emailccsubject'  => 'Копие на писмото ви до $1: $2',
'emailsent'       => 'Писмото е изпратено',
'emailsenttext'   => 'Писмото ви беше изпратено.',

# Watchlist
'watchlist'            => 'Моят списък за наблюдение',
'mywatchlist'          => 'Моят списък за наблюдение',
'watchlistfor'         => "(за '''$1''')",
'nowatchlist'          => 'Списъкът ви за наблюдение е празен.',
'watchlistanontext'    => 'Необходимо е $1 за да видите или редактирате списъка си за наблюдение.',
'watchnologin'         => 'Не сте влезли',
'watchnologintext'     => 'Необходимо е да [[{{ns:special}}:Userlogin|влезете]], за да може да променяте списъка си за наблюдение.',
'addedwatch'           => 'Добавено в списъка за наблюдение',
'addedwatchtext'       => "Страницата „'''$1'''“ беше добавена към [[{{ns:special}}:Watchlist|списъка ви за наблюдение]].
Нейните бъдещи промени, както и на съответната й дискусионна страница, ще се описват там, а тя ще се появява с '''удебелен шрифт''' в [[{{ns:special}}:Recentchanges|списъка на последните промени]], което ще направи по-лесно избирането й.

Ако по-късно искате да премахнете страницата от списъка си за наблюдение, щракнете на „''Спиране на наблюдение''“.",
'removedwatch'         => 'Премахнато от списъка за наблюдение',
'removedwatchtext'     => 'Страницата „$1“ беше премахната от списъка ви за наблюдение.',
'watch'                => 'Наблюдаване',
'watchthispage'        => 'Наблюдаване на страницата',
'unwatch'              => 'Спиране на наблюдение',
'unwatchthispage'      => 'Спиране на наблюдение',
'notanarticle'         => 'Не е страница',
'watchnochange'        => 'Никоя от наблюдаваните страници не е била редактирана в показаното време.',
'watchlist-details'    => '{{PLURAL:$1|Една наблюдавана страница|$1 наблюдавани страници}} (без беседи)',
'wlheader-enotif'      => '* Известяването по електронна поща е включено.',
'wlheader-showupdated' => "* Страниците, които са били променени след последния път, когато сте ги посетили, са показани с '''получер''' шрифт.",
'watchmethod-recent'   => 'проверка на последните редакции за наблюдавани страници',
'watchmethod-list'     => 'проверка на наблюдаваните страници за скорошни редакции',
'watchlistcontains'    => 'Списъкът ви за наблюдение съдържа {{PLURAL:$1|една страница|$1 страници}}.',
'iteminvalidname'      => 'Проблем с „$1“, грешно име…',
'wlnote'               => "{{PLURAL:$1|Показана е последната промяна|Показани са последните '''$1''' промени}} през {{PLURAL:$2|последния час|последните '''$2''' часа}}.",
'wlshowlast'           => 'Показване на последните $1 часа $2 дни $3',
'watchlist-show-bots'  => 'Показване на ботове',
'watchlist-hide-bots'  => 'Скриване на ботове',
'watchlist-show-own'   => 'Показване на моите приноси',
'watchlist-hide-own'   => 'Скриване на моите приноси',
'watchlist-show-minor' => 'Показване на малки промени',
'watchlist-hide-minor' => 'Скриване на малки промени',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Наблюдаване…',
'unwatching' => 'Спиране на наблюдаване…',

'enotif_mailer'                => 'Известяване по пощата на {{SITENAME}}',
'enotif_reset'                 => 'Отбелязване на всички страници като посетени',
'enotif_newpagetext'           => 'Това е нова страница.',
'enotif_impersonal_salutation' => 'Потребител на {{SITENAME}}',
'changed'                      => 'променена',
'created'                      => 'създадена',
'enotif_subject'               => 'Страницата $PAGETITLE в {{SITENAME}} е била $CHANGEDORCREATED от $PAGEEDITOR',
'enotif_lastvisited'           => 'Преглед на всички промени след последното ви посещение: $1.',
'enotif_lastdiff'              => 'Преглед на тази промяна: $1.',
'enotif_anon_editor'           => 'анонимен потребител $1',
'enotif_body'                  => 'Уважаеми(а) $WATCHINGUSERNAME,

на $PAGEEDITDATE страницата „$PAGETITLE“ в {{SITENAME}} е била $CHANGEDORCREATED от $PAGEEDITOR.

Текуща версия: $PAGETITLE_URL

$NEWPAGE

Кратко описание на измененията: $PAGESUMMARY $PAGEMINOREDIT

Връзка с редактора:
* електронна поща: $PAGEEDITOR_EMAIL
* уики: $PAGEEDITOR_WIKI

Няма да ви се пращат други известявания за последващи изменения, докато не посетите страницата. На страницата със списъка ви за наблюдение можете да включите известяванията наведнъж за всички страници.

             Системата за известяване на {{SITENAME}}

--
За да промените настройките на списъка си за наблюдение, посетете {{fullurl:{{ns:special}}:Watchlist/edit}}

Обратна връзка и помощ: {{fullurl:{{MediaWiki:Helppage}}}}',

# Delete/protect/revert
'deletepage'                  => 'Изтриване на страница',
'confirm'                     => 'Потвърждение',
'excontent'                   => 'съдържанието беше: „$1“',
'excontentauthor'             => 'съдържанието беше: „$1“ (като единственият автор беше „$2“)',
'exbeforeblank'               => 'съдържанието преди изпразването беше: „$1“',
'exblank'                     => 'страницата беше празна',
'confirmdelete'               => 'Потвърждение на изтриването',
'deletesub'                   => '(Изтриване на „$1“)',
'historywarning'              => 'Внимание: Страницата, която ще изтриете, има история:',
'confirmdeletetext'           => 'На път сте безвъзвратно да изтриете страница или файл, заедно с цялата й (му) история, от базата от данни.
Моля, потвърдете, че искате това, разбирате последствията и правите това в съответствие с [[{{MediaWiki:Policy-url}}|линията на поведение]].',
'actioncomplete'              => 'Действието беше изпълнено',
'deletedtext'                 => 'Страницата „$1“ беше изтрита. Вижте $2 за запис на последните изтривания.',
'deletedarticle'              => 'изтрита „[[$1]]“',
'dellogpage'                  => 'Дневник на изтриванията',
'dellogpagetext'              => 'Списък на последните изтривания.',
'deletionlog'                 => 'дневника на изтриванията',
'reverted'                    => 'Възвръщане към предишна версия',
'deletecomment'               => 'Причина за изтриването',
'deleteotherreason'           => 'Друга/допълнителна причина:',
'deletereasonotherlist'       => 'Друга причина',
'deletereason-dropdown'       => '*Стандартни причини за изтриване
** По молба на автора
** Нарушение на авторски права
** Вандализъм',
'rollback'                    => 'Връщане назад на промените',
'rollback_short'              => 'Връщане',
'rollbacklink'                => 'връщане',
'rollbackfailed'              => 'Връщането не сполучи',
'cantrollback'                => 'Промяната не може да се извърши. Последният редактор е единственият автор на страницата.',
'alreadyrolled'               => 'Редакцията на [[:$1]], направена от [[User:$2|$2]] ([[User_talk:$2|Беседа]]), не може да се върне назад. Някой друг вече е редактирал страницата или е върнал назад промените.

Последната редакция е на [[User:$3|$3]] ([[User_talk:$3|Беседа]]).',
'editcomment'                 => "Коментарът на редакцията е бил: „''$1''“.", # only shown if there is an edit comment
'revertpage'                  => 'Премахване на [[{{ns:special}}:Contributions/$2|редакции на $2]] ([[User_talk:$2|беседа]]); възвръщане към последната версия на [[User:$1|$1]]',
'rollback-success'            => 'Премахнати редакции на $1; възвръщане към последната версия на $2.',
'sessionfailure'              => 'Явно има проблем със сесията ви; действието беше отказано като предпазна мярка срещу крадене на сесията. Моля, натиснете бутона за връщане на браузъра, презаредете страницата, от която сте дошли, и опитайте отново.',
'protectlogpage'              => 'Дневник на защитата',
'protectlogtext'              => 'Списък на защитите и техните сваляния за страницата.
Можете да прегледате и [[{{ns:special}}:Protectedpages|списъка на текущо защитените страници]].',
'protectedarticle'            => 'защитаване на „[[$1]]“',
'modifiedarticleprotection'   => 'смяна на нивото на защита на „[[$1]]“',
'unprotectedarticle'          => 'сваляне на защитата на „[[$1]]“',
'protectsub'                  => '(Защитаване на „$1“)',
'confirmprotect'              => 'Потвърждение на защитата',
'protectcomment'              => 'Причина за защитата:',
'protectexpiry'               => 'Изтичане:',
'protect_expiry_invalid'      => 'Времето на изтичане е невалидно.',
'protect_expiry_old'          => 'Времето на изтичане лежи в миналото.',
'unprotectsub'                => '(Сваляне на защитата на „$1“)',
'protect-unchain'             => 'Позволяване на преместванията',
'protect-text'                => 'Тук можете да прегледате и промените нивото на защита на страницата „[[$1]]“. Желателно е да се придържате към [[{{ns:project}}:Защитена страница|ръководните принципи на проекта]].',
'protect-locked-blocked'      => 'Нямате правото да променяте нивата на защита на страниците, докато сте блокиран(а). Ето текущите настройки за страницата „<strong>$1</strong>“:',
'protect-locked-dblock'       => 'Нивата на защита на страниците не могат да бъдат променяни, защото базата от данни е заключена. Ето текущите настройки за страницата „<strong>$1</strong>“:',
'protect-locked-access'       => 'Нямате правото да променяте нивата на защита на страниците. Ето текущите настройки за страницата „<strong>$1</strong>“:',
'protect-cascadeon'           => 'Тази страница е защитена против редактиране, защото е включена в {{PLURAL:$1|следната страница, която от своя страна има|следните страници, които от своя страна имат}} каскадна защита. Можете да промените нивото на защита на страницата, но това няма да повлияе върху каскадната защита.',
'protect-default'             => '(по подразбиране)',
'protect-level-autoconfirmed' => 'Блокиране на нерегистрирани потребители',
'protect-level-sysop'         => 'Само за администратори',
'protect-summary-cascade'     => 'каскадно',
'protect-expiring'            => 'изтича на $1 (UTC)',
'protect-cascade'             => 'Каскадна защита — защита на всички страници, включени в настоящата страница.',
'protect-cantedit'            => 'Не можете да промените нивото на защита на тази страницата, защото нямате права да я редактирате.',
'restriction-type'            => 'Състояние на защитата:',
'restriction-level'           => 'Ниво на защитата:',
'minimum-size'                => 'Минимален размер',
'maximum-size'                => 'Максимален размер',
'pagesize'                    => '(байта)',

# Restrictions (nouns)
'restriction-edit'   => 'Редактиране',
'restriction-move'   => 'Преместване',
'restriction-create' => 'Създаване',

# Restriction levels
'restriction-level-sysop'         => 'пълна защита',
'restriction-level-autoconfirmed' => 'полузащита',
'restriction-level-all'           => 'всички',

# Undelete
'undelete'                 => 'Преглед на изтрити страници',
'undeletepage'             => 'Преглед и възстановяване на изтрити страници',
'viewdeletedpage'          => 'Преглед на изтрити страници',
'undeletepagetext'         => 'Следните страници бяха изтрити, но се намират все още
в архива и могат да бъдат възстановени. Архивът може да се почиства от време на време.',
'undeleteextrahelp'        => "За пълно възстановяване на страницата не слагайте отметки и натиснете '''''Възстановяване!'''''.
За частично възстановяване отметнете тези версии на страницата, които трябва да бъдат въстановени, и натиснете '''''Възстановяване!'''''.
Натискането на '''''Изчистване!''''' ще премахне всички отметки и ще изчисти полето за коментар.",
'undeleterevisions'        => '{{PLURAL:$1|Една версия беше архивирана|$1 версии бяха архивирани}}',
'undeletehistory'          => 'Ако възстановите страницата, всички версии ще бъдат върнати в историята.
Ако след изтриването е създадена страница със същото име, възстановените версии ще се появят като по-ранна история, а текущата версия на страницата няма да бъде заменена автоматично. Също така обърнете внимание, че ограниченията, приложени върху версиите, ще се загубят след възстановяването.',
'undeleterevdel'           => 'Възстановяването няма да бъде изпълнено, ако би довело до частично изтриване на актуалната версия. В такъв случай актуалната версия не трябва да бъде избирана или пък състоянието й трябва да бъде променено на нормална (нескрита) версия. Версиите на файлове, които нямате право да преглеждате, няма да бъдат възстановени.',
'undeletehistorynoadmin'   => 'Тази страница е била изтрита. В резюмето отдолу е посочена причината за това, заедно с информация за потребителите, редактирали страницата преди изтриването й. Конкретното съдържание на изтритите версии е достъпно само за администратори.',
'undelete-revision'        => 'Изтрита версия на $1 от $2:',
'undeleterevision-missing' => 'Неправилна или липсваща версия. Може да сте последвали грешна препратка или указаната версия да е била възстановена или премахната от архива',
'undelete-nodiff'          => 'Не е открита предишна редакция.',
'undeletebtn'              => 'Възстановяване',
'undeletereset'            => 'Изчистване',
'undeletecomment'          => 'Коментар:',
'undeletedarticle'         => '„[[$1]]“ беше възстановена',
'undeletedrevisions'       => '{{plural:$1|Една версия беше възстановена|$1 версии бяха възстановени}}',
'undeletedrevisions-files' => '{{plural:$1|Една версия|$1 версии}} и {{plural:$1|един файл|$2 файла}} бяха възстановени',
'undeletedfiles'           => '{{plural:$2|Един файл беше възстановен|$1 файла бяха възстановени}}',
'cannotundelete'           => 'Грешка при възстановяването. Възможно е някой друг вече да е възстановил страницата.',
'undeletedpage'            => "<big>'''Страницата „$1“ беше възстановена.'''</big>  Можете да видите последните изтрити и възстановени страници в [[{{ns:special}}:Log/delete|дневника на изтриванията.]]",
'undelete-header'          => 'Прегледайте [[Special:Log/delete|дневника на изтриванията]] за текущо изтритите страници.',
'undelete-search-box'      => 'Търсене на изтрити страници',
'undelete-search-prefix'   => 'Показване на страници, започващи със:',
'undelete-search-submit'   => 'Търсене',
'undelete-no-results'      => 'Не са намерени страници, отговарящи на търсения критерий.',
'undelete-cleanup-error'   => 'Грешка при изтриване на неизползвания архивен файл "$1".',
'undelete-error-short'     => 'Грешка при възстановяването на изтрития файл: $1',
'undelete-error-long'      => 'Възникнаха грешки при възстановяването на изтрития файл:

$1',

# Namespace form on various pages
'namespace'      => 'Именно пространство:',
'invert'         => 'Обръщане на избора',
'blanknamespace' => '(Основно)',

# Contributions
'contributions' => 'Приноси',
'mycontris'     => 'Моите приноси',
'contribsub2'   => 'За $1 ($2)',
'nocontribs'    => 'Не са намерени промени, отговарящи на критерия.',
'ucnote'        => "Показани са последните '''$1''' промени, извършени от този потребител през последните '''$2''' дни.",
'uclinks'       => 'Показване на последните $1 промени; показване на последните $2 дни.',
'uctop'         => ' (последна)',
'month'         => 'Месец:',
'year'          => 'Година:',

'sp-contributions-newbies'     => 'Показване само на приносите на нови потребители',
'sp-contributions-newbies-sub' => 'на нови потребители',
'sp-contributions-blocklog'    => 'Дневник на блокиранията',
'sp-contributions-search'      => 'Търсене на приноси',
'sp-contributions-username'    => 'IP-адрес или потребителско име:',
'sp-contributions-submit'      => 'Търсене',

'sp-newimages-showfrom' => 'Показване на новите картинки, като се започва от $1',

# What links here
'whatlinkshere'       => 'Какво сочи насам',
'whatlinkshere-title' => 'Страници, които сочат към $1',
'whatlinkshere-page'  => 'Страница:',
'linklistsub'         => '(Списък с препратки)',
'linkshere'           => "Следните страници сочат към '''[[:$1]]''':",
'nolinkshere'         => "Няма страници, сочещи към '''[[:$1]]'''.",
'nolinkshere-ns'      => "Няма страници, сочещи към '''[[:$1]]''' в избраното именно пространство.",
'isredirect'          => 'пренасочваща страница',
'istemplate'          => 'включване',
'whatlinkshere-prev'  => '{{PLURAL:$1|предишна|предишни $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|следваща|следващи $1}}',
'whatlinkshere-links' => '← препратки',

# Block/unblock
'blockip'                     => 'Блокиране на потребителя',
'blockiptext'                 => 'Използвайте долния формуляр, за да забраните правото на писане
на определен IP-адрес или потребител.
Това трябва да се направи само, за да се предотвратят прояви на вандализъм,
и в съответствие с  [[{{MediaWiki:Policy-url}}|линията на поведение]] на {{SITENAME}}.
Посочете също и причина за блокирането (например, заглавия на страници, станали обект на вандализъм).',
'ipaddress'                   => 'IP-адрес:',
'ipadressorusername'          => 'IP-адрес или потребител:',
'ipbexpiry'                   => 'Срок:',
'ipbreason'                   => 'Причина:',
'ipbreasonotherlist'          => 'Друга причина',
'ipbreason-dropdown'          => '
* Основни причини за блокиране
** Вмъкване на грешна информация
** Изтриване на съдържание
** Пускане на спам
** Вмъкване на глупости
** Заплашително поведение
** Злупотреба с потребителски сметки
** Недопустимо потребителско име',
'ipbanononly'                 => 'Блокиране само на анонимни потребители',
'ipbcreateaccount'            => 'Забрана за създаване на потребителски сметки',
'ipbemailban'                 => 'Забрана на потребителя да праща е-поща',
'ipbenableautoblock'          => 'Автоматично блокиране на последния IP-адрес, използван от потребителя, както и на всички останали адреси, от които се опита да редактира',
'ipbsubmit'                   => 'Блокиране на потребителя',
'ipbother'                    => 'Друг срок:',
'ipboptions'                  => 'Два часа:2 hours,Един ден:1 day,Три дни:3 days,Една седмица:1 week,Две седмици:2 weeks,Един месец:1 month,Три месеца:3 months,Шест месеца:6 months,Една година:1 year,Докато свят светува:infinite',
'ipbotheroption'              => 'друг',
'ipbotherreason'              => 'Друга/допълнителна причина:',
'ipbhidename'                 => 'Скриване на потребителското име/IP-адрес в дневника на блокиранията, в списъка с текущите блокирания и в списъка на потребителите',
'badipaddress'                => 'Невалиден IP-адрес',
'blockipsuccesssub'           => 'Блокирането беше успешно',
'blockipsuccesstext'          => '„[[{{ns:special}}:Contributions/$1|$1]]“ беше блокиран.
<br />Вижте [[{{ns:special}}:Ipblocklist|списъка на блокираните IP-адреси]], за да прегледате всички блокирания.',
'ipb-edit-dropdown'           => 'Причини за блокиране',
'ipb-unblock-addr'            => 'Отблокиране на $1',
'ipb-unblock'                 => 'Отблокиране на потребителско име IP-адрес',
'ipb-blocklist-addr'          => 'Преглед на текущите блокирания на $1',
'ipb-blocklist'               => 'Преглед на текущите блокирания',
'unblockip'                   => 'Отблокиране на потребител',
'unblockiptext'               => 'Използвайте долния формуляр, за да възстановите правото на писане на по-рано блокиран IP-адрес или потребител.',
'ipusubmit'                   => 'Отблокиране на адреса',
'unblocked'                   => '[[User:$1|$1]] беше отблокиран.',
'unblocked-id'                => 'Блок № $1 беше премахнат',
'ipblocklist'                 => 'Списък на блокирани IP-адреси и потребители',
'ipblocklist-legend'          => 'Откриване на блокиран потребител',
'ipblocklist-username'        => 'Потребителско име или IP адрес:',
'ipblocklist-submit'          => 'Търсене',
'blocklistline'               => '$1, $2 е блокирал $3 ($4)',
'infiniteblock'               => 'неограничено',
'expiringblock'               => 'изтича на $1',
'anononlyblock'               => 'само анон.',
'noautoblockblock'            => 'автоблокировката е изключена',
'createaccountblock'          => 'създаването на сметки е блокирано',
'emailblock'                  => 'е-пощенската услуга е блокирана',
'ipblocklist-empty'           => 'Списъкът на блокиранията е празен.',
'ipblocklist-no-results'      => 'Указаният IP-адрес или потребител не е блокиран.',
'blocklink'                   => 'блокиране',
'unblocklink'                 => 'отблокиране',
'contribslink'                => 'приноси',
'autoblocker'                 => 'Бяхте блокиран автоматично, тъй като неотдавна IP-адресът ви е бил ползван от блокирания в момента потребител [[User:$1|$1]]. Причината за неговото блокиране е: „$2“.',
'blocklogpage'                => 'Дневник на блокиранията',
'blocklogentry'               => 'блокиране на „[[$1]]“ със срок на изтичане $2 $3',
'blocklogtext'                => 'Това е дневник на блокиранията и отблокиранията, извършени от този потребител. Автоматично блокираните IP-адреси не са показани. Вижте  [[{{ns:special}}:Ipblocklist|списъка на блокираните IP-адреси]] за текущото състояние на блокиранията.',
'unblocklogentry'             => 'отблокиране на „$1“',
'block-log-flags-anononly'    => 'само анонимни потребители',
'block-log-flags-nocreate'    => 'създаването на сметки е изключено',
'block-log-flags-noautoblock' => 'автоблокировката е изключена',
'block-log-flags-noemail'     => 'е-пощенската услуга е блокирана',
'range_block_disabled'        => 'Възможността на администраторите да задават интервали при IP-адресите е изключена.',
'ipb_expiry_invalid'          => 'Невалиден срок на изтичане.',
'ipb_already_blocked'         => '„$1“ е вече блокиран',
'ipb_cant_unblock'            => 'Грешка: Не е намерен блок с номер $1. Вероятно потребителят е вече отблокиран.',
'ipb_blocked_as_range'        => 'Грешка: IP-адресът $1 не може да бъде разблокиран, тъй като е част от блокирания регистър $2. Можете да разблокирате адреса, като разблокирате целия регистър.',
'ip_range_invalid'            => 'Невалиден интервал за IP-адреси.',
'proxyblocker'                => 'Блокировач на проксита',
'proxyblockreason'            => 'Вашият IP-адрес беше блокиран, тъй като е анонимно достъпен междинен сървър. Моля, свържете се с доставчика ви на интернет и го информирайте за този сериозен проблем в сигурността.',
'proxyblocksuccess'           => 'Готово.',
'sorbsreason'                 => 'Вашият IP-адрес е записан като анонимно достъпен междинен сървър в DNSBL на {{SITENAME}}.',
'sorbs_create_account_reason' => 'Вашият IP-адрес е записан като анонимно достъпен междинен сървър в DNSBL на {{SITENAME}}. Не можете да създадете сметка.',

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
'lockfilenotwritable' => 'Няма права за писане върху файла за заключване на базата данни. За да заключи или отключи базата данни, уеб-сървърът трябва да има тези права.',
'databasenotlocked'   => 'Базата от данни не е заключена.',

# Move page
'movepage'                => 'Преместване на страница',
'movepagetext'            => "Посредством долния формуляр можете да преименувате страница, премествайки цялата й история на новото име. Старото заглавие ще се превърне в пренасочваща страница.
Препратките към старата страница няма да бъдат променени; затова проверете за двойни или невалидни пренасочвания.
Вие сами би трябвало да се убедите в това, дали препратките продължават да сочат там, където се предполага.

Страницата '''няма''' да бъде преместена, ако вече съществува страница с новото име, освен ако е празна или пренасочване и няма редакционна история.

'''ВНИМАНИЕ!'''
Това може да е голяма и неочаквана промяна за известна страница. Уверете се, че разбирате последствията, преди да продължите.",
'movepagetalktext'        => "Ако съществува, съответната дискусионна страница ще бъде преместена автоматично заедно с нея, '''освен ако:'''
* не местите страницата от едно именно пространство в друго,
* вече съществува непразна дискусионна страница с това име или
* не сте отметнали долната кутийка.

В тези случаи, ако желаете, ще е необходимо да преместите страницата ръчно.",
'movearticle'             => 'Преместване на страница:',
'movenologin'             => 'Не сте влезли',
'movenologintext'         => 'Необходимо е да [[{{ns:special}}:Userlogin|влезете]], за да може да премествате страници.',
'movenotallowed'          => 'Нямате права за преместване на страници в това уики.',
'newtitle'                => 'Към ново заглавие:',
'move-watch'              => 'Наблюдаване на страницата',
'movepagebtn'             => 'Преместване',
'pagemovedsub'            => 'Преместването беше успешно',
'movepage-moved'          => "<big>'''Страницата „$1“ беше преместена под името „$2“.'''</big>", # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'Вече съществува страница с това име или името, което сте избрали, е невалидно. Моля, изберете друго име.',
'talkexists'              => "'''Страницата беше успешно преместена, но без съответната дискусионна страница, защото под новото име има една съществуваща. Моля, обединете ги ръчно.'''",
'movedto'                 => 'преместена като',
'movetalk'                => 'Преместване и на дискусионната страница, ако е приложимо.',
'talkpagemoved'           => 'Съответната дискусионна страница също беше преместена.',
'talkpagenotmoved'        => 'Съответната дискусионна страница <strong>не</strong> беше преместена.',
'1movedto2'               => '„[[$1]]“ преместена като „[[$2]]“',
'1movedto2_redir'         => '„[[$1]]“ преместена като „[[$2]]“ (върху пренасочване)',
'movelogpage'             => 'Дневник на преместванията',
'movelogpagetext'         => 'По-долу е показан списък на преместванията.',
'movereason'              => 'Причина:',
'revertmove'              => 'връщане',
'delete_and_move'         => 'Изтриване и преместване',
'delete_and_move_text'    => '== Наложително изтриване ==

Целевата страница „[[$1]]“ вече съществува. Искате ли да я изтриете, за да освободите място за преместването?',
'delete_and_move_confirm' => 'Да, искам да изтрия тази страница.',
'delete_and_move_reason'  => 'Изтрита, за да се освободи място за преместване',
'selfmove'                => 'Страницата не може да бъде преместена, тъй като целевото име съвпада с първоначалното й заглавие.',
'immobile_namespace'      => 'Целевото заглавие е от специален тип. Не е възможно местенето на страници в това именно пространство.',

# Export
'export'            => 'Изнасяне на страници',
'exporttext'        => "Тук можете да изнесете като XML текста и историята на една или повече страници. Получените данни можете да вмъкнете в друг сайт, използващ софтуера МедияУики, чрез [[Special:Import|неговата страница за внaсяне]].

За да изнесете няколко страници, въвеждайте всяко ново заглавие на '''нов ред'''. След това изберете дали искате само текущата версия (заедно с информация за последната редакция) или всички версии (заедно с текущата) на страницата.

Ако желаете само текущата версия, бихте могли да използвате препратка от вида [[{{ns:special}}:Export/{{MediaWiki:Mainpage}}]] за страницата [[{{MediaWiki:Mainpage}}]].",
'exportcuronly'     => 'Включване само на текущата версия, а не на цялата история',
'exportnohistory'   => "----
'''Важно:''' Изнасянето на пълната история на страниците е забранено, защото много забавя уикито.",
'export-submit'     => 'Изнасяне',
'export-addcattext' => 'Добавяне на страници от категория:',
'export-addcat'     => 'Добавяне',
'export-download'   => 'Съхраняване като файл',

# Namespace 8 related
'allmessages'               => 'Системни съобщения',
'allmessagesname'           => 'Име',
'allmessagesdefault'        => 'Текст по подразбиране',
'allmessagescurrent'        => 'Текущ текст',
'allmessagestext'           => 'Това е списък на системните съобщения, намиращи се в именното пространство „МедияУики“',
'allmessagesnotsupportedDB' => 'Възможността за използване на страници от именното пространство „МедияУики“ за генериране на интерфейсните съобщения е изключена (<code>LocalSettings.php: wgUseDatabaseMessages = false</code>).',
'allmessagesfilter'         => 'Филтриране на съобщенията по име:',
'allmessagesmodified'       => 'Показване само на променените',

# Thumbnails
'thumbnail-more'           => 'Увеличаване',
'missingimage'             => '<strong>Липсваща картинка</strong><br /><i>$1</i>',
'filemissing'              => 'Липсващ файл',
'thumbnail_error'          => 'Грешка при създаване на миникартинка: $1',
'djvu_page_error'          => 'Номерът на DjVu-страницата е извън обхвата',
'djvu_no_xml'              => 'Не е възможно вземането на XML за DjVu-файла',
'thumbnail_invalid_params' => 'Параметрите за миникартинка са невалидни',
'thumbnail_dest_directory' => 'Целевата директория не може да бъде създадена',

# Special:Import
'import'                     => 'Внасяне на страници',
'importinterwiki'            => 'Внасяне чрез Трансуики',
'import-interwiki-text'      => 'Изберете уики и име на страницата.
Датите на редакциите и имената на авторите ще бъдат запазени.
Всички операции при внасянето от друго уики се записват в [[{{ns:special}}:Log/import|съответния дневник]].',
'import-interwiki-history'   => 'Копиране на всички версии на страницата',
'import-interwiki-submit'    => 'Внасяне',
'import-interwiki-namespace' => 'Прехвърляне на страници към именно пространство:',
'importtext'                 => 'Моля, изнесете файла от изходното уики, използвайки инструмента „{{ns:special}}:Export“, съхранете го на диска си и го качете тук.',
'importstart'                => 'Внасяне на страници…',
'import-revision-count'      => '$1 {{PLURAL:$1|версия|версии}}',
'importnopages'              => 'Няма страници за внасяне.',
'importfailed'               => 'Внасянето беше неуспешно: $1',
'importunknownsource'        => 'Непознат тип файл',
'importcantopen'             => 'Не е възможно да се отвори файла за внасяне',
'importbadinterwiki'         => 'Невалидна уики препратка',
'importnotext'               => 'Празно',
'importsuccess'              => 'Внасянето беше успешно!',
'importhistoryconflict'      => 'Съществува версия от историята, която си противоречи с тази (възможно е страницата да е била вече внесена)',
'importnosources'            => 'Не са посочени източници за внасяне чрез Трансуики. Прякото качване на версионни истории не е позволено.',
'importnofile'               => 'Файлът за внасяне не беше качен.',
'importuploaderror'          => 'Имаше грешка при качването на файла за внасяне. Възможно е размерът му да е по-голям от позволения за качване.',

# Import log
'importlogpage'                    => 'Дневник на внасянията',
'importlogpagetext'                => 'Административни внасяния на страници с редакционна история от други уикита.',
'import-logentry-upload'           => '[[$1]] беше внесена от файл',
'import-logentry-upload-detail'    => '{{PLURAL:$1|една версия|$1 версии}}',
'import-logentry-interwiki'        => '$1 беше внесена от друго уики',
'import-logentry-interwiki-detail' => '{{PLURAL:$1|една версия|$1 версии}} на $2 бяха внесени',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Вашата потребителска страница',
'tooltip-pt-anonuserpage'         => 'Потребителската страница за адреса, от който редактирате',
'tooltip-pt-mytalk'               => 'Вашата дискусионна страница',
'tooltip-pt-anontalk'             => 'Дискусия относно редакциите от този адрес',
'tooltip-pt-preferences'          => 'Вашите настройки',
'tooltip-pt-watchlist'            => 'Списък на страници, чиито промени сте избрали да наблюдавате',
'tooltip-pt-mycontris'            => 'Списък на вашите приноси',
'tooltip-pt-login'                => 'Насърчаваме ви да влезете, въпреки че не е задължително.',
'tooltip-pt-anonlogin'            => 'Насърчаваме ви да влезете, въпреки че не е задължително.',
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
'tooltip-ca-watch'                => 'Добавяне на страницата към списъка ви за наблюдение',
'tooltip-ca-unwatch'              => 'Премахване на страницата от списъка ви за наблюдение',
'tooltip-search'                  => 'Претърсване на {{SITENAME}}',
'tooltip-search-go'               => 'Отиване на страницата ако тя съществува с точно това име',
'tooltip-search-fulltext'         => 'Търсене в страниците за този текст',
'tooltip-p-logo'                  => 'Началната страница',
'tooltip-n-mainpage'              => 'Началната страница',
'tooltip-n-portal'                => 'Информация за проекта — какво, къде, как',
'tooltip-n-currentevents'         => 'Информация за текущите събития по света',
'tooltip-n-recentchanges'         => 'Списък на последните промени в {{SITENAME}}',
'tooltip-n-randompage'            => 'Зареждане на случайна страница',
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
'tooltip-t-print'                 => 'Версия за печат на страницата',
'tooltip-t-permalink'             => 'Постоянна препратка към тази версия на страницата',
'tooltip-ca-nstab-main'           => 'Преглед на основната страница',
'tooltip-ca-nstab-user'           => 'Преглед на потребителската страница',
'tooltip-ca-nstab-media'          => 'Преглед на медийната страница',
'tooltip-ca-nstab-special'        => 'Това е специална страница, която не може да се редактира.',
'tooltip-ca-nstab-project'        => 'Преглед на проектната страница',
'tooltip-ca-nstab-image'          => 'Преглед на страницата на файла',
'tooltip-ca-nstab-mediawiki'      => 'Преглед на системното съобщение',
'tooltip-ca-nstab-template'       => 'Преглед на шаблона',
'tooltip-ca-nstab-help'           => 'Преглед на помощната страница',
'tooltip-ca-nstab-category'       => 'Преглед на категорията',
'tooltip-minoredit'               => 'Отбелязване на промяната като малка',
'tooltip-save'                    => 'Съхраняване на промените',
'tooltip-preview'                 => 'Предварителен преглед, моля, използвайте го преди да съхраните!',
'tooltip-diff'                    => 'Показване на направените от вас промени по текста',
'tooltip-compareselectedversions' => 'Показване на разликите между двете избрани версии на страницата',
'tooltip-watch'                   => 'Добавяне на страницата към списъка ви за наблюдение',
'tooltip-recreate'                => 'Възстановяване на страницата независимо, че е била изтрита',
'tooltip-upload'                  => 'Започване на качването',

# Stylesheets
'common.css'   => '/* Чрез редактиране на този файл ще промените всички облици */',
'monobook.css' => '/* Чрез редактиране на този файл можете да промените облика Монобук */',

# Scripts
'common.js'   => '/* Този файл съдържа код на Джаваскрипт и се зарежда при всички потребители. */',
'monobook.js' => '/* Остаряла страница: ползвайте [[МедияУики:Common.js]] */',

# Metadata
'nodublincore'      => 'Метаданните Dublin Core RDF са изключени за този сървър.',
'nocreativecommons' => 'Метаданните Creative Commons RDF са изключени за този сървър.',
'notacceptable'     => 'Сървърът не може да предостави данни във формат, който да се разпознава от клиента ви.',

# Attribution
'anonymous'        => 'Анонимен потребител(и) на {{SITENAME}}',
'siteuser'         => 'потребител на {{SITENAME}} $1',
'lastmodifiedatby' => 'Последната промяна на страницата е извършена от $3 на $2, $1.', # $1 date, $2 time, $3 user
'and'              => 'и',
'othercontribs'    => 'Основаващо се върху работа на $1.',
'others'           => 'други',
'siteusers'        => 'потребителите на {{SITENAME}} $1',
'creditspage'      => 'Библиография и източници',
'nocredits'        => 'Няма въведени източници или библиография.',

# Spam protection
'spamprotectiontitle'    => 'Филтър за защита от спам',
'spamprotectiontext'     => 'Страницата, която искахте да съхраните, беше блокирана от филтъра против спам. Това обикновено е причинено от препратка към външен сайт.',
'spamprotectionmatch'    => 'Следният текст предизвика включването на филтъра: $1',
'subcategorycount'       => 'Тази категория има {{PLURAL:$1|една подкатегория|$1 подкатегории}}.',
'categoryarticlecount'   => 'Тази категория съдържа {{PLURAL:$1|една страница|$1 страници}}.',
'category-media-count'   => 'Категорията съдържа {{PLURAL:$1|един файл|$1 файла}}.',
'listingcontinuesabbrev' => ' продълж.',
'spambot_username'       => 'Спамочистач',
'spam_reverting'         => 'Връщане на последната версия, несъдържаща препратки към $1',
'spam_blanking'          => 'Всички версии, съдържащи препратки към $1, изчистване',

# Info page
'infosubtitle'   => 'Информация за страницата',
'numedits'       => 'Брой редакции (страница): $1',
'numtalkedits'   => 'Брой редакции (дискусионна страница): $1',
'numwatchers'    => 'Брой наблюдатели: $1',
'numauthors'     => 'Брой различни автори (страница): $1',
'numtalkauthors' => 'Брой различни автори (дискусионна страница): $1',

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

# Patrol log
'patrol-log-page' => 'Дневник на патрула',
'patrol-log-line' => 'отбеляза $1 от $2 като проверена $3',
'patrol-log-auto' => '(автоматично)',
'patrol-log-diff' => 'версия $1',

# Image deletion
'deletedrevision'                 => 'Изтрита стара версия $1',
'filedeleteerror-short'           => 'Грешка при изтриване на файл: $1',
'filedeleteerror-long'            => 'Възникнаха грешки при изтриването на файла:

$1',
'filedelete-missing'              => 'Файлът  "$1" не съществува и затова не може да бъде изтрит.',
'filedelete-old-unregistered'     => 'Посочената версия на файла "$1" не беше открита в базата от данни.',
'filedelete-current-unregistered' => 'Указаният файл "$1" не е в базата данни.',

# Browsing diffs
'previousdiff' => '← Предишна разлика',
'nextdiff'     => 'Следваща разлика →',

# Media information
'mediawarning'         => "'''Внимание''': Възможно е файлът да съдържа злонамерен програмен код, чието изпълнение да доведе до повреди в системата ви.
<hr />",
'imagemaxsize'         => 'Ограничаване на картинките на описателните им страници до:',
'thumbsize'            => 'Размери на миникартинките:',
'widthheightpage'      => '$1×$2, $3 страници',
'file-info'            => '(файлов размер: $1, MIME-тип: $2)',
'file-info-size'       => '($1 × $2 пиксела, файлов размер: $3, MIME-тип: $4)',
'file-nohires'         => '<small>Не е налична версия с по-висока разделителна способност.</small>',
'show-big-image'       => 'Пълна разделителна способност',
'show-big-image-thumb' => '<small>Размер на предварителния преглед: $1 × $2 пиксела</small>',

# Special:Newimages
'newimages'    => 'Галерия на новите файлове',
'showhidebots' => '($1 на ботове)',
'noimages'     => 'Няма нищо.',

# Metadata
'metadata'          => 'Метаданни',
'metadata-help'     => 'Файлът съдържа допълнителни данни, обикновено добавяни от цифровите апарати или скенери. Ако файлът е редактиран след създаването си, то някои параметри може да не съответстват на текущото изображение.',
'metadata-expand'   => 'Показване на допълнителните данни',
'metadata-collapse' => 'Скриване на допълнителните данни',
'metadata-fields'   => 'EXIF данните, показани в това съобщение, ще бъдат включени на медийната страница, когато информационната таблица е сгъната. Останалите данни ще са скрити по подразбиране.
* производител
* модел
* дата и време на създаване
* време за експозиция
* F (бленда)
* фокусно разстояние',

# EXIF tags
'exif-imagewidth'               => 'Ширина',
'exif-imagelength'              => 'Височина',
'exif-bitspersample'            => 'Дълбочина на цвета (битове)',
'exif-compression'              => 'Вид компресия',
'exif-orientation'              => 'Ориентация',
'exif-planarconfiguration'      => 'Принцип на организация на данните',
'exif-xresolution'              => 'Хоризонтална резолюция',
'exif-yresolution'              => 'Вертикална резолюция',
'exif-whitepoint'               => 'Хроматичност на бялото',
'exif-datetime'                 => 'Дата и време на изменението на файла',
'exif-imagedescription'         => 'Название на изображението',
'exif-make'                     => 'Производител',
'exif-model'                    => 'Модел на фотоапарата',
'exif-software'                 => 'Използван софтуер',
'exif-artist'                   => 'Автор',
'exif-copyright'                => 'Притежател на авторското право',
'exif-exifversion'              => 'Exif версия',
'exif-flashpixversion'          => 'Поддържана версия Flashpix',
'exif-colorspace'               => 'Цветово пространство',
'exif-compressedbitsperpixel'   => 'Режим на компресия на образа',
'exif-pixelydimension'          => 'Пълна ширина на изображението',
'exif-pixelxdimension'          => 'Пълна височина на изображението',
'exif-makernote'                => 'Допълнителни данни на производителя',
'exif-usercomment'              => 'Допълнителни коментари',
'exif-datetimeoriginal'         => 'Дата и време на създаване',
'exif-exposuretime'             => 'Време на експозиция',
'exif-exposuretime-format'      => '$1 сек ($2)',
'exif-fnumber'                  => 'F (бленда)',
'exif-exposureprogram'          => 'Програма на експонацията',
'exif-spectralsensitivity'      => 'Спектрална чувствителност',
'exif-isospeedratings'          => 'Светлочувствителност ISO',
'exif-shutterspeedvalue'        => 'Скорост на затвора',
'exif-aperturevalue'            => 'Диаметър на обектива',
'exif-brightnessvalue'          => 'Светлосила',
'exif-exposurebiasvalue'        => 'Отклонение от експонацията',
'exif-subjectdistance'          => 'Разстояние до обекта',
'exif-meteringmode'             => 'Режим на измерване',
'exif-lightsource'              => 'Източник на светлина',
'exif-flash'                    => 'Светкавица',
'exif-focallength'              => 'Фокусно разстояние',
'exif-flashenergy'              => 'Мощност на светкавицата',
'exif-spatialfrequencyresponse' => 'Пространствен честотен отклик',
'exif-focalplanexresolution'    => 'Фокусна равнина X резолюция',
'exif-focalplaneyresolution'    => 'Фокусна равнина Y резолюция',
'exif-focalplaneresolutionunit' => 'Единица за разделителна способност на фокалната равнина',
'exif-exposureindex'            => 'Индекс на експонацията',
'exif-sensingmethod'            => 'Метод на засичане',
'exif-filesource'               => 'Файлов източник',
'exif-scenetype'                => 'Вид сцена',
'exif-cfapattern'               => 'Стандартен цветови стил',
'exif-customrendered'           => 'Допълнителна обработка на изображението',
'exif-exposuremode'             => 'Режим на експонация',
'exif-whitebalance'             => 'Баланс на бялото',
'exif-digitalzoomratio'         => 'Съотношение на цифровото увеличение',
'exif-focallengthin35mmfilm'    => 'Фокусно разстояние в 35 mm филм',
'exif-contrast'                 => 'Контраст',
'exif-saturation'               => 'Наситеност',
'exif-sharpness'                => 'Острота',
'exif-devicesettingdescription' => 'Описание на настройките на апарата',
'exif-imageuniqueid'            => 'Уникален идентификатор на изображението',
'exif-gpslatituderef'           => 'Северна или южна ширина',
'exif-gpslatitude'              => 'Географска ширина',
'exif-gpslongituderef'          => 'Източна или западна дължина',
'exif-gpslongitude'             => 'Географска дължина',
'exif-gpsaltituderef'           => 'Отправна височина',
'exif-gpsaltitude'              => 'Надморска височина',
'exif-gpstimestamp'             => 'GPS време (атомен часвник)',
'exif-gpsmeasuremode'           => 'Метод за измерване',
'exif-gpsdop'                   => 'Прецизност',
'exif-gpsspeedref'              => 'Единица за скорост',
'exif-gpsspeed'                 => 'Скорост на GPS приемник',
'exif-gpstrack'                 => 'Посока на движение',
'exif-gpsdatestamp'             => 'GPS дата',
'exif-gpsdifferential'          => 'Диференциална корекция на GPS',

# EXIF attributes
'exif-compression-1' => 'Некомпресиран',

'exif-unknowndate' => 'Неизвестна дата',

'exif-orientation-1' => 'Нормално', # 0th row: top; 0th column: left
'exif-orientation-2' => 'Отражение по хоризонталата', # 0th row: top; 0th column: right
'exif-orientation-3' => 'Обърнато на 180°', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Отражение по вертикалата', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Обърнато на 90° срещу часовниковата стрелка и отразено по вертикалата', # 0th row: left; 0th column: top
'exif-orientation-6' => 'Обърнато на 90° по часовниковата стрелка', # 0th row: right; 0th column: top
'exif-orientation-7' => 'Обърнато на 90° по часовниковата стрелка и отразено по вертикалата', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Обърнато на 90° срещу часовниковата стрелка', # 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'формат „chunky“',
'exif-planarconfiguration-2' => 'формат „planar“',

'exif-componentsconfiguration-0' => 'не съществува',

'exif-exposureprogram-0' => 'Не е определено',
'exif-exposureprogram-1' => 'Ръчна настройка',
'exif-exposureprogram-2' => 'Нормална програма',
'exif-exposureprogram-3' => 'Приоритет на блендата',
'exif-exposureprogram-4' => 'Приоритет на скоростта',
'exif-exposureprogram-7' => 'Режим „Портрет“ (за снимки в едър план, фонът не е на фокус)',
'exif-exposureprogram-8' => 'Режим „Пейзаж“ (за пейзажни снимки, в които фонът е на фокус)',

'exif-subjectdistance-value' => '$1 метра',

'exif-lightsource-0'   => 'неизвестно',
'exif-lightsource-1'   => 'дневна светлина',
'exif-lightsource-2'   => 'Флуоресцентно осветление',
'exif-lightsource-3'   => 'Волфрамово осветление',
'exif-lightsource-4'   => 'Светкавица',
'exif-lightsource-9'   => 'хубаво време',
'exif-lightsource-10'  => 'облачно',
'exif-lightsource-11'  => 'Сянка',
'exif-lightsource-24'  => 'Студийна лампа стандарт ISO',
'exif-lightsource-255' => 'друг източник на светлина',

'exif-focalplaneresolutionunit-2' => 'инчове',

'exif-filesource-3' => 'цифров фотоапарат',

'exif-customrendered-0' => 'нормален процес',
'exif-customrendered-1' => 'нестандартна обработка',

'exif-exposuremode-0' => 'автоматична експозиция',
'exif-exposuremode-1' => 'ръчна експозиция',

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
'exif-subjectdistancerange-2' => 'Близко',
'exif-subjectdistancerange-3' => 'Далечно',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'северна ширина',
'exif-gpslatitude-s' => 'южна ширина',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'източна дължина',
'exif-gpslongitude-w' => 'западна дължина',

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
'watchlistall2'    => 'всички',
'namespacesall'    => 'Всички',
'monthsall'        => 'всички',

# E-mail address confirmation
'confirmemail'            => 'Потвърждаване на адрес за електронна поща',
'confirmemail_noemail'    => 'Не сте посочили валиден адрес за електронна поща в [[{{ns:special}}:Preferences|настройки си]].',
'confirmemail_text'       => '{{SITENAME}} изисква да потвърдите адреса си за електронна поща преди да използвате възможностите за е-поща. Натиснете долния бутон, за да ви бъде изпратено писмо, съдържащо специално генерирана препратка, чрез която ще можете да потвърдите валидността на адреса си.',
'confirmemail_pending'    => '<div class="error">
Кодът за потвърждение вече е изпратен. Ако току-що сте се регистрирали, изчакайте няколко минути да пристигне писмото, преди да поискате нов код.
</div>',
'confirmemail_send'       => 'Изпращане на код за потвърждение',
'confirmemail_sent'       => 'Кодът за потвърждение беше изпратен.',
'confirmemail_oncreate'   => 'Код за потвърждение беше изпратен на електронната ви поща.
Този код не е необходим за влизане, но ще ви трябва при активирането на функциите в {{SITENAME}}, изискващи валидна електронна поща.',
'confirmemail_sendfailed' => 'Кодът за потвърждение не можа да бъде изпратен. Проверете адреса си за недопустими знаци.
Изпращачът на е-поща отвърна: $1',
'confirmemail_invalid'    => 'Грешен код за потвърждение. Възможно е кодът да е остарял.',
'confirmemail_needlogin'  => 'Длъжни сте да $1, за да потвърдите адреса на електронната си поща.',
'confirmemail_success'    => 'Адресът ви за електронна поща беше потвърден. Вече можете да влезете и да се наслаждавате на уикито.',
'confirmemail_loggedin'   => 'Адресът ви за електронна поща беше потвърден.',
'confirmemail_error'      => 'Станала е грешка при потвърждаването на адреса ви.',
'confirmemail_subject'    => '{{SITENAME}} — Потвърждаване на адрес за е-поща',
'confirmemail_body'       => 'Някой, вероятно вие, от IP-адрес $1, е регистрирал потребител „$2“ в {{SITENAME}}, като е посочил този адрес за електронна поща.

За да потвърдите, че сметката в {{SITENAME}} и настоящият пощенски адрес са ваши, заредете долната препратка в браузъра си:

$3

Ако някой друг е направил регистрацията в {{SITENAME}} и не желаете да я потвърждавате, не следвайте препратката. Кодът за потвърждение ще загуби валидност след $4.',

# Scary transclusion
'scarytranscludedisabled' => '[Включването между уикита е деактивирано]',
'scarytranscludefailed'   => '[Зареждането на шаблона за $1 не сполучи]',
'scarytranscludetoolong'  => '[Адресът е твърде дълъг; съжаляваме]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">
Обратни следи за статията:<br />
$1
</div>',
'trackbackremove'   => ' ([$1 Изтриване])',
'trackbacklink'     => 'Обратна следа',
'trackbackdeleteok' => 'Обратната следа беше изтрита.',

# Delete conflict
'deletedwhileediting' => 'Внимание: Страницата е била изтрита, след като сте започнали да я редактирате!',
'confirmrecreate'     => "Потребителят [[User:$1|$1]] ([[User_talk:$1|беседа]]) е изтрил страницата, откакто сте започнали да я редактирате, давайки следното обяснение:
: ''$2''
Моля, потвърдете, че наистина желаете да създадете страницата отново.",
'recreate'            => 'Ново създаване',

# HTML dump
'redirectingto' => 'Пренасочване към [[$1]]…',

# action=purge
'confirm_purge'        => 'Изчистване на складираното копие на страницата?

$1',
'confirm_purge_button' => 'Добре',

# AJAX search
'searchcontaining' => "Търсене на статии, съдържащи ''$1''.",
'searchnamed'      => "Търсене на статии, чиито имена съдържат ''$1''.",
'articletitles'    => "Страници, започващи с ''$1''",
'hideresults'      => 'Скриване на резултатите',

# Multipage image navigation
'imgmultipageprev'   => '← предишна страница',
'imgmultipagenext'   => 'следваща страница →',
'imgmultigo'         => 'Отиване',
'imgmultigotopre'    => 'Към страницата',
'imgmultiparseerror' => 'Изглежда, че файлът е повреден, затова {{SITENAME}} не може да създаде списък на страници.',

# Table pager
'ascending_abbrev'         => 'възх',
'descending_abbrev'        => 'низх',
'table_pager_next'         => 'Следваща страница',
'table_pager_prev'         => 'Предишна страница',
'table_pager_first'        => 'Първа страница',
'table_pager_last'         => 'Последна страница',
'table_pager_limit'        => 'Показване на $1 записа на страница',
'table_pager_limit_submit' => '&gt;&gt;',
'table_pager_empty'        => 'Няма резултати',

# Auto-summaries
'autosumm-blank'   => 'Премахване на цялото съдържание на страницата',
'autosumm-replace' => 'Заместване на съдържанието на страницата с „$1“',
'autoredircomment' => 'Пренасочване към [[$1]]',
'autosumm-new'     => 'Нова страница: $1',

# Live preview
'livepreview-loading' => 'Зарежда се…',
'livepreview-ready'   => 'Зарежда се… Готово!',
'livepreview-failed'  => 'Бързият предварителен преглед не е възможен! Опитайте нормален предварителен преглед.',
'livepreview-error'   => 'Връзката не сполучи: $1 „$2“ Опитайте нормален предварителен преглед.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Промените от последните $1 секунди вероятно не са показани в списъка.',
'lag-warn-high'   => 'Поради голямото изоставане в сървърната синхронизация, промените от последните $1 секунди вероятно не са показани в списъка.',

# Watchlist editor
'watchlistedit-numitems'       => 'Списъкът ви за наблюдение съдържа {{PLURAL:$1|1 страница |$1 страници}} (без беседите).',
'watchlistedit-noitems'        => 'Списъкът ви за наблюдение е празен.',
'watchlistedit-normal-title'   => 'Редактиране на списъка за наблюдение',
'watchlistedit-normal-legend'  => 'Премахване на записи от списъка за наблюдение',
'watchlistedit-normal-explain' => 'По-долу са показани заглавията на страниците от списъка ви за наблюдение. За да премахнете страница, отбележете 
	полето пред нея и щракнете на бутона „Премахване“. Можете също да редактирате [[Special:Watchlist/raw|необработения списък за наблюдение]]
	или [[Special:Watchlist/clear|да премахнете накуп всички записи]].',
'watchlistedit-normal-submit'  => 'Премахване',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 страница беше премахната|$1 страници бяха премахнати}} от вашия списък за наблюдение:',
'watchlistedit-raw-title'      => 'Редактиране на необработения списък за наблюдение',
'watchlistedit-raw-legend'     => 'Редактиране на необработения списък за наблюдение',
'watchlistedit-raw-explain'    => 'По-долу са показани заглавията на страниците във вашия списък за наблюдение, които можете да редактирате, като добавяте или премахвате по едно заглавие на ред. Когато приключите, щракнете бутона „Обновяване на списъка за наблюдение“.
	Можете да използвате и [[Special:Watchlist/edit|стандартния редактор]].',
'watchlistedit-raw-titles'     => 'Страници:',
'watchlistedit-raw-submit'     => 'Обновяване на списъка за наблюдение',
'watchlistedit-raw-done'       => 'Вашият списък за наблюдение е обновен.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 страница беше добавена|$1 страници бяха добавени}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 страница беше |$1 страници бяха}} премахнати:',

# Watchlist editing tools
'watchlisttools-view' => 'Преглед на списъка за наблюдение',
'watchlisttools-edit' => 'Преглед и редактиране на списъка за наблюдение',
'watchlisttools-raw'  => 'Редактиране на необработения списък за наблюдение',

);

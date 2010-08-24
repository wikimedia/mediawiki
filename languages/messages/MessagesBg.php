<?php
/** Bulgarian (Български)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author BloodIce
 * @author Borislav
 * @author DCLXVI
 * @author Daggerstab
 * @author Spiritia
 * @author Stanqo
 * @author Turin
 * @author Urhixidur
 * @author Петър Петров
 * @author לערי ריינהארט
 */

$fallback8bitEncoding = 'windows-1251';

$namespaceNames = array(
	NS_MEDIA            => 'Медия',
	NS_SPECIAL          => 'Специални',
	NS_TALK             => 'Беседа',
	NS_USER             => 'Потребител',
	NS_USER_TALK        => 'Потребител_беседа',
	NS_PROJECT_TALK     => '$1_беседа',
	NS_FILE             => 'Файл',
	NS_FILE_TALK        => 'Файл_беседа',
	NS_MEDIAWIKI        => 'МедияУики',
	NS_MEDIAWIKI_TALK   => 'МедияУики_беседа',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Шаблон_беседа',
	NS_HELP             => 'Помощ',
	NS_HELP_TALK        => 'Помощ_беседа',
	NS_CATEGORY         => 'Категория',
	NS_CATEGORY_TALK    => 'Категория_беседа',
);

$namespaceAliases = array(
	'Картинка' => NS_FILE,
	'Картинка беседа' => NS_FILE_TALK,
);


$datePreferences = false;

$bookstoreList = array(
	'books.bg'   => 'http://www.books.bg/ISBN/$1',
	'Пингвините' => 'http://www.pe-bg.com/?cid=3&search_q=$1&where=ISBN&x=0&y=0**',
	'Бард'       => 'http://www.bard.bg/search/?q=$1'
);

$magicWords = array(
	'redirect'              => array( '0', '#пренасочване', '#виж', '#REDIRECT' ),
	'notoc'                 => array( '0', '__БЕЗСЪДЪРЖАНИЕ__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__БЕЗГАЛЕРИЯ__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__СЪССЪДЪРЖАНИЕ__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__СЪДЪРЖАНИЕ__', '__TOC__' ),
	'noeditsection'         => array( '0', '__БЕЗ_РЕДАКТИРАНЕ_НА_РАЗДЕЛИ__', '__NOEDITSECTION__' ),
	'currentmonth'          => array( '1', 'ТЕКУЩМЕСЕЦ', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'      => array( '1', 'ТЕКУЩМЕСЕЦИМЕ', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'ТЕКУЩМЕСЕЦИМЕРОД', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'    => array( '1', 'ТЕКУЩМЕСЕЦСЪКР', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'ТЕКУЩДЕН', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'ТЕКУЩДЕН2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'ТЕКУЩДЕНИМЕ', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'ТЕКУЩАГОДИНА', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'ТЕКУЩОВРЕМЕ', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'ТЕКУЩЧАС', 'CURRENTHOUR' ),
	'numberofpages'         => array( '1', 'БРОЙСТРАНИЦИ', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'БРОЙСТАТИИ', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'БРОЙФАЙЛОВЕ', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'БРОЙПОТРЕБИТЕЛИ', 'NUMBEROFUSERS' ),
	'numberofedits'         => array( '1', 'БРОЙРЕДАКЦИИ', 'NUMBEROFEDITS' ),
	'pagename'              => array( '1', 'СТРАНИЦА', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'СТРАНИЦАИ', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'ИМЕННОПРОСТРАНСТВО', 'NAMESPACE' ),
	'namespacee'            => array( '1', 'ИМЕННОПРОСТРАНСТВОИ', 'NAMESPACEE' ),
	'fullpagename'          => array( '1', 'ПЪЛНОИМЕ_СТРАНИЦА', 'FULLPAGENAME' ),
	'fullpagenamee'         => array( '1', 'ПЪЛНОИМЕ_СТРАНИЦАИ', 'FULLPAGENAMEE' ),
	'subpagename'           => array( '1', 'ИМЕ_ПОДСТРАНИЦА', 'SUBPAGENAME' ),
	'subpagenamee'          => array( '1', 'ИМЕ_ПОДСТРАНИЦАИ', 'SUBPAGENAMEE' ),
	'talkpagename'          => array( '1', 'ИМЕ_БЕСЕДА', 'TALKPAGENAME' ),
	'talkpagenamee'         => array( '1', 'ИМЕ_БЕСЕДАИ', 'TALKPAGENAMEE' ),
	'msg'                   => array( '0', 'СЪОБЩ:', 'MSG:' ),
	'subst'                 => array( '0', 'ЗАМЕСТ:', 'SUBST:' ),
	'msgnw'                 => array( '0', 'СЪОБЩБУ:', 'MSGNW:' ),
	'img_thumbnail'         => array( '1', 'мини', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'мини=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'вдясно', 'дясно', 'д', 'right' ),
	'img_left'              => array( '1', 'вляво', 'ляво', 'л', 'left' ),
	'img_none'              => array( '1', 'н', 'none' ),
	'img_width'             => array( '1', '$1пкс', '$1п', '$1px' ),
	'img_center'            => array( '1', 'център', 'центр', 'ц', 'center', 'centre' ),
	'img_framed'            => array( '1', 'рамка', 'врамка', 'framed', 'enframed', 'frame' ),
	'img_frameless'         => array( '1', 'безрамка', 'frameless' ),
	'img_border'            => array( '1', 'ръб', 'контур', 'border' ),
	'int'                   => array( '0', 'ВЪТР:', 'INT:' ),
	'sitename'              => array( '1', 'ИМЕНАСАЙТА', 'SITENAME' ),
	'ns'                    => array( '0', 'ИП:', 'NS:' ),
	'localurl'              => array( '0', 'ЛОКАЛЕНАДРЕС:', 'LOCALURL:' ),
	'localurle'             => array( '0', 'ЛОКАЛЕНАДРЕСИ:', 'LOCALURLE:' ),
	'server'                => array( '0', 'СЪРВЪР', 'SERVER' ),
	'servername'            => array( '0', 'ИМЕНАСЪРВЪРА', 'SERVERNAME' ),
	'scriptpath'            => array( '0', 'ПЪТДОСКРИПТА', 'SCRIPTPATH' ),
	'grammar'               => array( '0', 'ГРАМАТИКА:', 'GRAMMAR:' ),
	'currentweek'           => array( '1', 'ТЕКУЩАСЕДМИЦА', 'CURRENTWEEK' ),
	'currentdow'            => array( '1', 'ТЕКУЩ_ДЕН_ОТ_СЕДМИЦАТА', 'CURRENTDOW' ),
	'revisionid'            => array( '1', 'ИД_НА_ВЕРСИЯТА', 'REVISIONID' ),
	'revisionday'           => array( '1', 'ДЕН_НА_ВЕРСИЯТА', 'REVISIONDAY' ),
	'revisionday2'          => array( '1', 'ДЕН_НА_ВЕРСИЯТА2', 'REVISIONDAY2' ),
	'revisionmonth'         => array( '1', 'МЕСЕЦ_НА_ВЕРСИЯТА', 'REVISIONMONTH' ),
	'revisionyear'          => array( '1', 'ГОДИНА_НА_ВЕРСИЯТА', 'REVISIONYEAR' ),
	'plural'                => array( '0', 'МН_ЧИСЛО:', 'PLURAL:' ),
	'fullurl'               => array( '0', 'ПЪЛЕН_АДРЕС:', 'FULLURL:' ),
	'fullurle'              => array( '0', 'ПЪЛЕН_АДРЕСИ:', 'FULLURLE:' ),
	'lcfirst'               => array( '0', 'МБПЪРВА:', 'LCFIRST:' ),
	'ucfirst'               => array( '0', 'ГБПЪРВА:', 'UCFIRST:' ),
	'lc'                    => array( '0', 'МБ:', 'LC:' ),
	'uc'                    => array( '0', 'ГБ:', 'UC:' ),
	'raw'                   => array( '0', 'НЕОБРАБ:', 'RAW:' ),
	'displaytitle'          => array( '1', 'ПОКАЗВ_ЗАГЛАВИЕ', 'DISPLAYTITLE' ),
	'newsectionlink'        => array( '1', '__ВРЪЗКА_ЗА_НОВ_РАЗДЕЛ__', '__NEWSECTIONLINK__' ),
	'language'              => array( '0', '#ЕЗИК:', '#LANGUAGE:' ),
	'numberofadmins'        => array( '1', 'БРОЙАДМИНИСТРАТОРИ', 'NUMBEROFADMINS' ),
	'defaultsort'           => array( '1', 'СОРТКАТ:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'hiddencat'             => array( '1', '__СКРИТАКАТЕГОРИЯ__', '__HIDDENCAT__' ),
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Двойни пренасочвания' ),
	'BrokenRedirects'           => array( 'Невалидни пренасочвания' ),
	'Disambiguations'           => array( 'Пояснителни страници' ),
	'Userlogin'                 => array( 'Регистриране или влизане' ),
	'Userlogout'                => array( 'Излизане' ),
	'CreateAccount'             => array( 'Създаване на сметка' ),
	'Preferences'               => array( 'Настройки' ),
	'Watchlist'                 => array( 'Списък за наблюдение' ),
	'Recentchanges'             => array( 'Последни промени' ),
	'Upload'                    => array( 'Качване' ),
	'Listfiles'                 => array( 'Файлове' ),
	'Newimages'                 => array( 'Нови файлове' ),
	'Listusers'                 => array( 'Потребители' ),
	'Listgrouprights'           => array( 'Групови права' ),
	'Statistics'                => array( 'Статистика' ),
	'Randompage'                => array( 'Случайна страница' ),
	'Lonelypages'               => array( 'Страници сираци' ),
	'Uncategorizedpages'        => array( 'Некатегоризирани страници' ),
	'Uncategorizedcategories'   => array( 'Некатегоризирани категории' ),
	'Uncategorizedimages'       => array( 'Некатегоризирани картинки' ),
	'Uncategorizedtemplates'    => array( 'Некатегоризирани шаблони' ),
	'Unusedcategories'          => array( 'Неизползвани категории' ),
	'Unusedimages'              => array( 'Неизползвани картинки' ),
	'Wantedpages'               => array( 'Желани страници' ),
	'Wantedcategories'          => array( 'Желани категории' ),
	'Wantedfiles'               => array( 'Желани файлове' ),
	'Wantedtemplates'           => array( 'Желани шаблони' ),
	'Mostlinked'                => array( 'Най-препращани страници' ),
	'Mostlinkedcategories'      => array( 'Най-препращани категории' ),
	'Mostlinkedtemplates'       => array( 'Най-препращани шаблони' ),
	'Mostimages'                => array( 'Най-препращани картинки' ),
	'Mostcategories'            => array( 'Страници с най-много категории' ),
	'Mostrevisions'             => array( 'Страници с най-много версии' ),
	'Fewestrevisions'           => array( 'Страници с най-малко версии' ),
	'Shortpages'                => array( 'Кратки страници' ),
	'Longpages'                 => array( 'Дълги страници' ),
	'Newpages'                  => array( 'Нови страници' ),
	'Ancientpages'              => array( 'Стари страници' ),
	'Deadendpages'              => array( 'Задънени страници' ),
	'Protectedpages'            => array( 'Защитени страници' ),
	'Protectedtitles'           => array( 'Защитени заглавия' ),
	'Allpages'                  => array( 'Всички страници' ),
	'Prefixindex'               => array( 'Всички страници с представка', 'Представка' ),
	'Ipblocklist'               => array( 'Блокирани потребители' ),
	'Specialpages'              => array( 'Специални страници' ),
	'Contributions'             => array( 'Приноси' ),
	'Emailuser'                 => array( 'Писмо на потребител' ),
	'Confirmemail'              => array( 'Потвърждаване на е-поща' ),
	'Whatlinkshere'             => array( 'Какво сочи насам' ),
	'Recentchangeslinked'       => array( 'Свързани промени' ),
	'Movepage'                  => array( 'Преместване на страница' ),
	'Blockme'                   => array( 'Блокирай ме' ),
	'Booksources'               => array( 'Източници на книги' ),
	'Categories'                => array( 'Категории' ),
	'Export'                    => array( 'Изнасяне' ),
	'Version'                   => array( 'Версия' ),
	'Allmessages'               => array( 'Системни съобщения' ),
	'Log'                       => array( 'Дневници' ),
	'Blockip'                   => array( 'Блокиране' ),
	'Undelete'                  => array( 'Възстановяване' ),
	'Import'                    => array( 'Внасяне' ),
	'Lockdb'                    => array( 'Заключване на БД' ),
	'Unlockdb'                  => array( 'Отключване на БД' ),
	'Userrights'                => array( 'Потребителски права' ),
	'MIMEsearch'                => array( 'MIME-търсене' ),
	'FileDuplicateSearch'       => array( 'Повтарящи се файлове' ),
	'Unwatchedpages'            => array( 'Ненаблюдавани страници' ),
	'Listredirects'             => array( 'Пренасочвания' ),
	'Revisiondelete'            => array( 'Изтриване на версии' ),
	'Unusedtemplates'           => array( 'Неизползвани шаблони' ),
	'Randomredirect'            => array( 'Случайно пренасочване' ),
	'Mypage'                    => array( 'Моята страница' ),
	'Mytalk'                    => array( 'Моята беседа' ),
	'Mycontributions'           => array( 'Моите приноси' ),
	'Listadmins'                => array( 'Администратори' ),
	'Listbots'                  => array( 'Ботове' ),
	'Popularpages'              => array( 'Най-посещавани страници' ),
	'Search'                    => array( 'Търсене' ),
	'Resetpass'                 => array( 'Промяна на парола' ),
	'Withoutinterwiki'          => array( 'Без междууикита' ),
	'MergeHistory'              => array( 'История на сливането' ),
	'Filepath'                  => array( 'Път към файл' ),
	'Invalidateemail'           => array( 'Отмяна на е-поща' ),
	'Blankpage'                 => array( 'Празна страница' ),
	'LinkSearch'                => array( 'Търсене на външни препратки' ),
	'DeletedContributions'      => array( 'Изтрити приноси' ),
	'Tags'                      => array( 'Етикети' ),
	'Activeusers'               => array( 'Активни потребители' ),
);

$linkTrail = '/^([a-zабвгдежзийклмнопрстуфхцчшщъыьэюя]+)(.*)$/sDu';

$separatorTransformTable = array( ',' => "\xc2\xa0", '.' => ',' );

$messages = array(
# User preference toggles
'tog-underline'               => 'Подчертаване на препратките:',
'tog-highlightbroken'         => 'Показване на невалидните препратки <a href="#" class="new">така</a> (алтернативно: така<a href="#" class="internal">?</a>)',
'tog-justify'                 => 'Двустранно подравняване на абзаците',
'tog-hideminor'               => 'Скриване на малки редакции в последните промени',
'tog-hidepatrolled'           => 'Скриване на патрулираните редакции от списъка с последните промени',
'tog-newpageshidepatrolled'   => 'Скриване на патрулираните редакции от списъка на новите страници',
'tog-extendwatchlist'         => 'Разширяване на списъка, така че да показва всички промени, не само най-скорошните',
'tog-usenewrc'                => 'Подобряване на последните промени (изисква Джаваскрипт)',
'tog-numberheadings'          => 'Номериране на заглавията',
'tog-showtoolbar'             => 'Помощна лента за редактиране (изисква Джаваскрипт)',
'tog-editondblclick'          => 'Редактиране при двойно щракване (изисква Джаваскрипт)',
'tog-editsection'             => 'Възможност за редактиране на раздел чрез препратка [редактиране]',
'tog-editsectiononrightclick' => 'Възможност за редактиране на раздел при щракване с десния бутон върху заглавие на раздел (изисква Джаваскрипт)',
'tog-showtoc'                 => 'Показване на съдържание (за страници с повече от три раздела)',
'tog-rememberpassword'        => 'Запомяне на паролата на този компютър (за не повече от $1 {{PLURAL:$1|ден|дни}})',
'tog-watchcreations'          => 'Добавяне на създадените от мен страници към списъка ми за наблюдение',
'tog-watchdefault'            => 'Добавяне на редактираните от мен страници към списъка ми за наблюдение',
'tog-watchmoves'              => 'Добавяне на преместените от мен страници към списъка ми за наблюдение',
'tog-watchdeletion'           => 'Добавяне на изтритите от мен страници към списъка ми за наблюдение',
'tog-previewontop'            => 'Показване на предварителния преглед преди текстовата кутия',
'tog-previewonfirst'          => 'Показване на предварителен преглед при първа редакция',
'tog-nocache'                 => 'Забрана за кеширане на страниците от браузъра',
'tog-enotifwatchlistpages'    => 'Уведомяване по е-пощата при промяна на страница от списъка ми за наблюдение',
'tog-enotifusertalkpages'     => 'Уведомяване по е-пощата при промяна на беседата ми',
'tog-enotifminoredits'        => 'Уведомяване по е-пощата даже при малки промени',
'tog-enotifrevealaddr'        => 'Показване на електронния ми адрес в известяващите писма',
'tog-shownumberswatching'     => 'Показване на броя на потребителите, наблюдаващи дадена страница',
'tog-oldsig'                  => 'Преглед на съществуващия подпис:',
'tog-fancysig'                => 'Без превръщане на подписа в препратка към потребителската страница',
'tog-externaleditor'          => 'Използване на външен редактор по подразбиране',
'tog-externaldiff'            => 'Използване на външна програма за разлики по подразбиране',
'tog-showjumplinks'           => 'Показване на препратки за достъпност от типа „Към…“',
'tog-uselivepreview'          => 'Използване на бърз предварителен преглед (изисква Джаваскрипт; експериментално)',
'tog-forceeditsummary'        => 'Предупреждаване при празно поле за резюме на редакцията',
'tog-watchlisthideown'        => 'Скриване на моите редакции в списъка ми за наблюдение',
'tog-watchlisthidebots'       => 'Скриване на редакциите на ботове в списъка ми за наблюдение',
'tog-watchlisthideminor'      => 'Скриване на малките промени в списъка ми за наблюдение',
'tog-watchlisthideliu'        => 'Скриване на редакциите от влезли потребители от списъка за наблюдение',
'tog-watchlisthideanons'      => 'Скриване на редакциите от анонимни потребители в списъка за наблюдение',
'tog-watchlisthidepatrolled'  => 'Скриване на патрулираните редакции от списъка за наблюдение',
'tog-nolangconversion'        => 'Без преобразувания при различни езикови варианти',
'tog-ccmeonemails'            => 'Получаване на копия на писмата, които пращам на другите потребители',
'tog-diffonly'                => 'Без показване на съдържанието на страницата при преглед на разлики',
'tog-showhiddencats'          => 'Показване на скритите категории',
'tog-norollbackdiff'          => 'Пропускане на разликовата връзка след извършване на отмяна на редакции',

'underline-always'  => 'Винаги',
'underline-never'   => 'Никога',
'underline-default' => 'Според настройките на браузъра',

# Font style option in Special:Preferences
'editfont-style'     => 'Стил на шрифта в кутията за редактиране',
'editfont-default'   => 'По подразбиране за браузъра',
'editfont-monospace' => 'Равноширок шрифт',
'editfont-sansserif' => 'Безсерифен шрифт',
'editfont-serif'     => 'Серифен шрифт',

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

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Категория|Категории}}',
'category_header'                => 'Страници в категория „$1“',
'subcategories'                  => 'Подкатегории',
'category-media-header'          => 'Файлове в категория „$1“',
'category-empty'                 => "''В момента тази категория не съдържа страници или файлове.''",
'hidden-categories'              => '{{PLURAL:$1|Скрита категория|Скрити категории}}',
'hidden-category-category'       => 'Скрити категории',
'category-subcat-count'          => '{{PLURAL:$2|Тази категория съдържа само една подкатегория.|{{PLURAL:$1|Показана е една|Показани са $1}} от общо $2 подкатегории на тази категория.}}',
'category-subcat-count-limited'  => 'Тази категория включва {{PLURAL:$1|следната една подкатегория|следните $1 подкатегории}}.',
'category-article-count'         => '{{PLURAL:$2|Тази категория съдържа само една страница.|{{PLURAL:$1|Показана е една|Показани са $1}} от общо $2 страници в тази категория.}}',
'category-article-count-limited' => 'Текущата категория съдържа {{PLURAL:$1|следната страница|следните $1 страници}}.',
'category-file-count'            => '{{PLURAL:$2|Тази категория съдържа само един файл.|{{PLURAL:$1|Показан е един|Показани са $1}} от общо $2 файла в тази категория.}}',
'category-file-count-limited'    => 'В текущата категория се {{PLURAL:$1|намира следният файл|намират следните $1 файла}}.',
'listingcontinuesabbrev'         => ' продълж.',
'index-category'                 => 'Индексирани страници',
'noindex-category'               => 'Неиндексирани страници',

'mainpagetext'      => "'''Уикито беше успешно инсталирано.'''",
'mainpagedocfooter' => 'Разгледайте [http://meta.wikimedia.org/wiki/Help:Contents ръководството] за подробна информация относно използването на софтуера.

== Първи стъпки ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Конфигурационни настройки]
* [http://www.mediawiki.org/wiki/Manual:FAQ ЧЗВ за МедияУики]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Пощенски списък относно нови версии на МедияУики]',

'about'         => 'За {{SITENAME}}',
'article'       => 'Страница',
'newwindow'     => '(отваря се в нов прозорец)',
'cancel'        => 'Отказ',
'moredotdotdot' => 'Още…',
'mypage'        => 'Моята страница',
'mytalk'        => 'Моята беседа',
'anontalk'      => 'Беседа за адреса',
'navigation'    => 'Навигация',
'and'           => '&#32;и',

# Cologne Blue skin
'qbfind'         => 'Търсене',
'qbbrowse'       => 'Избор',
'qbedit'         => 'Редактиране',
'qbpageoptions'  => 'Тази страница',
'qbpageinfo'     => 'Информация за страницата',
'qbmyoptions'    => 'Моите страници',
'qbspecialpages' => 'Специални страници',
'faq'            => 'ЧЗВ',
'faqpage'        => 'Project:ЧЗВ',

# Vector skin
'vector-action-addsection'   => 'Добавяне на тема',
'vector-action-delete'       => 'Изтриване',
'vector-action-move'         => 'Преместване',
'vector-action-protect'      => 'Защита',
'vector-action-undelete'     => 'Възстановяване',
'vector-action-unprotect'    => 'Сваляне на защитата',
'vector-namespace-category'  => 'Категория',
'vector-namespace-help'      => 'Помощ',
'vector-namespace-image'     => 'Файл',
'vector-namespace-main'      => 'Страница',
'vector-namespace-media'     => 'Медия',
'vector-namespace-mediawiki' => 'Съобщение',
'vector-namespace-project'   => 'Проект',
'vector-namespace-special'   => 'Специална страница',
'vector-namespace-talk'      => 'Беседа',
'vector-namespace-template'  => 'Шаблон',
'vector-namespace-user'      => 'Потребител',
'vector-view-create'         => 'Създаване',
'vector-view-edit'           => 'Редактиране',
'vector-view-history'        => 'История',
'vector-view-view'           => 'Преглед',
'vector-view-viewsource'     => 'Преглед на кода',
'actions'                    => 'Действия',
'namespaces'                 => 'Именни пространства',
'variants'                   => 'Варианти',

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
'create'            => 'Създаване',
'editthispage'      => 'Редактиране',
'create-this-page'  => 'Създаване на страницата',
'delete'            => 'Изтриване',
'deletethispage'    => 'Изтриване',
'undelete_short'    => 'Възстановяване на {{PLURAL:$1|една редакция|$1 редакции}}',
'protect'           => 'Защита',
'protect_change'    => 'промяна',
'protectthispage'   => 'Защита',
'unprotect'         => 'Сваляне на защитата',
'unprotectthispage' => 'Сваляне на защитата',
'newpage'           => 'Нова страница',
'talkpage'          => 'Дискусионна страница',
'talkpagelinktext'  => 'Беседа',
'specialpage'       => 'Специална страница',
'personaltools'     => 'Лични инструменти',
'postcomment'       => 'Нов раздел',
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
'lastmodifiedat'    => 'Последна промяна на страницата: $2, $1.',
'viewcount'         => 'Страницата е била преглеждана {{PLURAL:$1|един път|$1 пъти}}.',
'protectedpage'     => 'Защитена страница',
'jumpto'            => 'Направо към:',
'jumptonavigation'  => 'навигация',
'jumptosearch'      => 'търсене',
'view-pool-error'   => 'Съжаляваме, но сървърите в момента са претоварени.
Твърде много потребители се опитват да отворят тази страница.
Моля, изчакайте малко преди отново да пробвате да отворите страницата.

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'За {{SITENAME}}',
'aboutpage'            => 'Project:За {{SITENAME}}',
'copyright'            => 'Съдържанието е достъпно при условията на $1.',
'copyrightpage'        => '{{ns:project}}:Авторски права',
'currentevents'        => 'Текущи събития',
'currentevents-url'    => 'Project:Текущи събития',
'disclaimers'          => 'Условия за ползване',
'disclaimerpage'       => 'Project:Условия за ползване',
'edithelp'             => 'Помощ при редактиране',
'edithelppage'         => 'Help:Редактиране',
'helppage'             => 'Help:Съдържание',
'mainpage'             => 'Начална страница',
'mainpage-description' => 'Начална страница',
'policy-url'           => 'Project:Политика',
'portal'               => 'Портал за общността',
'portal-url'           => 'Project:Портал',
'privacy'              => 'Защита на личните данни',
'privacypage'          => 'Project:Защита на личните данни',

'badaccess'        => 'Грешка при достъп',
'badaccess-group0' => 'Нямате права да извършите исканото действие',
'badaccess-groups' => 'Исканото действие могат да изпълнят само потребители от {{PLURAL:$2|група|някоя от следните групи:}} $1.',

'versionrequired'     => 'Изисква се версия $1 на МедияУики',
'versionrequiredtext' => 'Използването на тази страница изисква версия $1 на софтуера МедияУики. Вижте [[Special:Version|текущата използвана версия]].',

'ok'                      => 'Добре',
'pagetitle'               => '$1 — {{SITENAME}}',
'retrievedfrom'           => 'Взето от „$1“.',
'youhavenewmessages'      => 'Имате $1 ($2).',
'newmessageslink'         => 'нови съобщения',
'newmessagesdifflink'     => 'разлика с предишната версия',
'youhavenewmessagesmulti' => 'Имате нови съобщения в $1',
'editsection'             => 'редактиране',
'editold'                 => 'редактиране',
'viewsourceold'           => 'преглед на кода',
'editlink'                => 'редактиране',
'viewsourcelink'          => 'преглед на кода',
'editsectionhint'         => 'Редактиране на раздел: $1',
'toc'                     => 'Съдържание',
'showtoc'                 => 'показване',
'hidetoc'                 => 'скриване',
'thisisdeleted'           => 'Преглед или възстановяване на $1?',
'viewdeleted'             => 'Преглед на $1?',
'restorelink'             => '{{PLURAL:$1|една изтрита редакция|$1 изтрити редакции}}',
'feedlinks'               => 'Във вида:',
'feed-invalid'            => 'Невалиден формат на информацията',
'feed-unavailable'        => 'Не се предлагат емисии',
'site-rss-feed'           => 'Емисия на RSS за $1',
'site-atom-feed'          => 'Емисия на Atom за $1',
'page-rss-feed'           => 'Емисия на RSS за „$1“',
'page-atom-feed'          => 'Емисия на Atom за „$1“',
'red-link-title'          => '$1 (страницата не съществува)',

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
'nosuchactiontext'  => 'Действието, указано в интернет адреса, е невалидно.
Може би сте допуснали грешка в изписването на адреса или сте последвали некоректна хипервръзка.
Проблемът може да се дължи и на грешка в софтуера на {{SITENAME}}.',
'nosuchspecialpage' => 'Няма такава специална страница',
'nospecialpagetext' => '<strong>Отправихте заявка за невалидна специална страница.</strong>

Списък с валидните специални страници може да бъде видян на [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Грешка',
'databaseerror'        => 'Грешка при работа с базата от данни',
'dberrortext'          => 'Възникна синтактична грешка при заявка към базата данни.
Това може да означава грешка в софтуера.
Последната заявка към базата данни беше:
<blockquote><tt>$1</tt></blockquote>
при функцията „<tt>$2</tt>“.
MySQL върна грешка „<tt>$3: $4</tt>“.',
'dberrortextcl'        => 'Възникна синтактична грешка при заявка към базата данни.
Последната заявка към базата данни беше:
„$1“
при функцията „$2“.
MySQL върна грешка „$3: $4“',
'laggedslavemode'      => 'Внимание: Страницата може да не съдържа последните обновявания.',
'readonly'             => 'Базата от данни е затворена за промени',
'enterlockreason'      => 'Посочете причина за затварянето, като дадете и приблизителна оценка кога базата от данни ще бъде отново отворена',
'readonlytext'         => 'Базата от данни е временно затворена за промени — вероятно за рутинна поддръжка, след която ще бъде отново на разположение.
Администраторът, който я е затворил, дава следното обяснение:
$1',
'missing-article'      => 'В базата от данни не беше открит текста на страницата „$1“ $2.

Това обикновено се случва при последване на остаряла разликова връзка или връзка към историята на междувременно изтрита страница.

Ако все пак случаят не е такъв, причината вероятно е софтуерен бъг.
Моля, докладвайте на [[Special:ListUsers/sysop|администратор]] за проблема, като предоставите уеб адреса за връзка.',
'missingarticle-rev'   => '(версия#: $1)',
'missingarticle-diff'  => '(Разлика: $1, $2)',
'readonly_lag'         => 'Базата от данни беше автоматично заключена, докато подчинените сървъри успеят да се съгласуват с основния сървър.',
'internalerror'        => 'Вътрешна грешка',
'internalerror_info'   => 'Вътрешна грешка: $1',
'fileappenderrorread'  => 'Не може да се чете "$1" по време на добавянето.',
'fileappenderror'      => 'Не можете да добавяте "$1" към "$2".',
'filecopyerror'        => 'Файлът „$1“ не можа да бъде копиран като „$2“.',
'filerenameerror'      => 'Файлът „$1“ не можа да бъде преименуван на „$2“.',
'filedeleteerror'      => 'Файлът „$1“ не можа да бъде изтрит.',
'directorycreateerror' => 'Невъзможно е да бъде създадена директория „$1“.',
'filenotfound'         => 'Файлът „$1“ не беше намерен.',
'fileexistserror'      => 'Невъзможност за запис във файл „$1“: файлът съществува',
'unexpected'           => 'Неочаквана стойност: „$1“=„$2“.',
'formerror'            => 'Възникна грешка при изпращане на формуляра',
'badarticleerror'      => 'Действието не може да се изпълни върху страницата.',
'cannotdelete'         => 'Указаната страница или файл "$1" не можа да бъде изтрит(а). Възможно е вече да е бил(а) изтрит(а) от някой друг.',
'badtitle'             => 'Невалидно заглавие',
'badtitletext'         => 'Желаното заглавие на страница е невалидно, празно или неправилна препратка към друго уики. Възможно е да съдържа знаци, които не са позволени в заглавия.',
'perfcached'           => 'Следните данни са извлечени от склада и затова може да не отговарят на текущото състояние:',
'perfcachedts'         => 'Данните са складирани и обновени за последно на $1.',
'querypage-no-updates' => 'Обновяването на тази страница в момента е изключено. Засега данните тук няма да бъдат обновявани.',
'wrong_wfQuery_params' => 'Невалидни аргументи за wfQuery()<br />
Функция: $1<br />
Заявка: $2',
'viewsource'           => 'Преглед на кода',
'viewsourcefor'        => 'за $1',
'actionthrottled'      => 'Ограничение в скоростта',
'actionthrottledtext'  => 'Като част от защитата против спам, многократното повтаряне на това действие за кратък период от време е ограничено и вие вече сте надвишили лимита си. Опитайте отново след няколко минути.',
'protectedpagetext'    => 'Тази страница е заключена за редактиране.',
'viewsourcetext'       => 'Можете да разгледате и да копирате кодa на страницата:',
'protectedinterface'   => 'Тази страница съдържа текст, нужен за работата на системата. Тя е защитена против редактиране, за да се предотвратят възможни злоупотреби.',
'editinginterface'     => "'''Внимание:''' Редактирате страница, която се използва за интерфейса на софтуера. Промяната й ще повлияе на външния вид на уикито.
За превеждане обмислете използването на [http://translatewiki.net/wiki/Main_Page?setlang=bg translatewiki.net], проектът за локализиране на MediaWiki.",
'sqlhidden'            => '(Заявка на SQL — скрита)',
'cascadeprotected'     => 'Тази страница е защитена против редактиране, защото е включена в {{PLURAL:$1|следната страница, която от своя страна има|следните страници, които от своя страна имат}} каскадна защита:
$2',
'namespaceprotected'   => "Нямате права за редактиране на страници в именно пространство '''$1'''.",
'customcssjsprotected' => 'Нямате права за редактиране на тази страница, защото тя съдържа чужди потребителски настройки.',
'ns-specialprotected'  => 'Специалните страници не могат да бъдат редактирани.',
'titleprotected'       => "Тази страница е била защитена срещу създаване от [[User:$1|$1]].
Посочената причина е ''$2''.",

# Virus scanner
'virus-badscanner'     => "Лоша конфигурация: непознат скенер за вируси: ''$1''",
'virus-scanfailed'     => 'сканирането не сполучи (код $1)',
'virus-unknownscanner' => 'непознат антивирус:',

# Login and logout pages
'logouttext'                 => "'''Излязохте от системата.'''

Можете да продължите да използвате {{SITENAME}} анонимно или да [[Special:UserLogin|влезете отново]] като друг потребител.
Обърнете внимание, че някои страници все още ще се показват така, сякаш сте влезли, докато не изтриете кеш-паметта на браузъра.",
'welcomecreation'            => '== Добре дошли, $1! ==

Вашата сметка беше създадена.
Можете да промените [[Special:Preferences|настройките на {{SITENAME}}]] според предпочитанията си.',
'yourname'                   => 'Потребителско име:',
'yourpassword'               => 'Парола:',
'yourpasswordagain'          => 'Парола (повторно):',
'remembermypassword'         => 'Запомняне на паролата на този компютър (най-много за $1 {{PLURAL:$1|ден|дни}})',
'yourdomainname'             => 'Домейн:',
'externaldberror'            => 'Или е станала грешка в базата от данни при външното удостоверяване, или не ви е позволено да обновявате външната си сметка.',
'login'                      => 'Влизане',
'nav-login-createaccount'    => 'Регистриране или влизане',
'loginprompt'                => "За влизане в {{SITENAME}} е необходимо да въведете потребителското си име и парола и да натиснете бутона '''Влизане''', като, за да бъде това успешно, бисквитките (cookies) трябва да са разрешени в браузъра ви.

Ако все още не сте се регистрирали (нямате открита сметка), лесно можете да сторите това, като последвате препратката '''Създаване на сметка'''.",
'userlogin'                  => 'Влизане / създаване на сметка',
'userloginnocreate'          => 'Влизане',
'logout'                     => 'Излизане',
'userlogout'                 => 'Излизане',
'notloggedin'                => 'Не сте влезли',
'nologin'                    => "Нямате потребителско име? '''$1'''.",
'nologinlink'                => 'Създаване на сметка',
'createaccount'              => 'Регистриране',
'gotaccount'                 => "Имате ли вече сметка? '''$1'''.",
'gotaccountlink'             => 'Влизане',
'createaccountmail'          => 'с писмо по електронната поща',
'createaccountreason'        => 'Причина:

Причина:',
'badretype'                  => 'Въведените пароли не съвпадат.',
'userexists'                 => 'Въведеното потребителско име вече се използва.
Изберете друго име.',
'loginerror'                 => 'Грешка при влизане',
'createaccounterror'         => 'Не може да бъде създадена сметка: $1',
'nocookiesnew'               => 'Потребителската сметка беше създадена, но все още не сте влезли. {{SITENAME}} използва бисквитки при влизането на потребителите. Разрешете бисквитките в браузъра си, тъй като те са забранени, а след това влезте с потребителското си име и парола.',
'nocookieslogin'             => '{{SITENAME}} използва бисквитки (cookies) за запис на влизанията. Разрешете бисквитките в браузъра си, тъй като те са забранени, и опитайте отново.',
'noname'                     => 'Не указахте валидно потребителско име.',
'loginsuccesstitle'          => 'Успешно влизане',
'loginsuccess'               => "'''Влязохте в {{SITENAME}} като „$1“.'''",
'nosuchuser'                 => 'Не съществува потребител с име „$1“.
Потребителските имена са чувствителни на малки и главни букви.
Проверете изписването или [[Special:UserLogin/signup|създайте нова сметка]].',
'nosuchusershort'            => 'Не съществува потребител с името „<nowiki>$1</nowiki>“. Проверете изписването.',
'nouserspecified'            => 'Необходимо е да се посочи потребителско име.',
'login-userblocked'          => 'Този потребител е блокиран. Не се позволява влизане.',
'wrongpassword'              => 'Въведената парола е невалидна. Опитайте отново.',
'wrongpasswordempty'         => 'Въведената парола е празна. Опитайте отново.',
'passwordtooshort'           => 'Необходимо е паролата да съдържа поне {{PLURAL:$1|1 знак|$1 знака}}.',
'password-name-match'        => 'Паролата ви трябва да се различава от потребителското ви име.',
'mailmypassword'             => 'Изпращане на нова парола',
'passwordremindertitle'      => 'Напомняне за парола от {{SITENAME}}',
'passwordremindertext'       => 'Някой (най-вероятно вие, от IP-адрес $1) е пожелал нова парола за влизане в {{SITENAME}} ($4).
За потребител „$2“ е създадена временната парола „$3“.
Сега би трябвало да влезете в системата и да си изберете нова парола.
Новата временна парола ще бъде активна {{PLURAL:$5|един ден|$5 дни}}.

Ако заявката е направена от друг или пък сте си спомнили паролата и не искате да я променяте, можете да пренебрегнете това съобщение и да продължите да използвате старата си парола.',
'noemail'                    => 'Няма записана електронна поща за потребителя „$1“.',
'noemailcreate'              => 'Необходимо е да въведете валиден адрес за е-поща',
'passwordsent'               => 'Нова парола беше изпратена на електронната поща на „$1“.
След като я получите, влезте отново.',
'blocked-mailpassword'       => 'Редактирането от вашия IP-адрес е забранено, затова не ви е позволено да използвате възможността за възстановяване на загубена парола.',
'eauthentsent'               => 'Писмото за потвърждение е изпратено на посочения адрес. В него са описани действията, които трябва да се извършат, за да потвърдите, че този адрес за електронна поща действително е ваш.',
'throttled-mailpassword'     => 'Функцията за напомняне на паролата е използвана през {{PLURAL:$1|последния един час|последните $1 часа}}.
За предотвратяване на злоупотреби е разрешено да се изпраща не повече от едно напомняне в рамките на {{PLURAL:$1|един час|$1 часа}}.',
'mailerror'                  => 'Грешка при изпращане на писмо: $1',
'acct_creation_throttle_hit' => 'През последното денонощие, през този IP-адрес посетители на това уики са създали {{PLURAL:$1|1 сметка |$1 сметки}}, което е максималният допустим брой за този период.
В резултат, към момента не могат да създават повече потребителски сметки през този IP-адрес.',
'emailauthenticated'         => 'Адресът на електронната ви поща беше потвърден на $2 в $3.',
'emailnotauthenticated'      => 'Адресът на електронната ви поща <strong>не е потвърден</strong>. Няма да получавате писма за никоя от следните възможности.',
'noemailprefs'               => 'За да работят тези функционалности, трябва да посочите адрес на електронна поща в своите настройки.',
'emailconfirmlink'           => 'Потвърждаване на адреса за електронна поща',
'invalidemailaddress'        => 'Въведеният адрес не може да бъде приет, тъй като не съответства на формата на адрес за електронна поща. Въведете коректен адрес или оставете полето празно.',
'accountcreated'             => 'Потребителската сметка беше създадена',
'accountcreatedtext'         => 'Потребителската сметка за $1 беше създадена.',
'createaccount-title'        => 'Създаване на сметка за {{SITENAME}}',
'createaccount-text'         => 'Някой е създал сметка за $2 в {{SITENAME}} ($4) и е посочил този адрес за електронна поща. Паролата за „$2“ е „$3“. Необходимо е да влезете в системата и да смените паролата си.

Можете да пренебрегнете това съобщение, ако сметката е създадена по грешка.',
'usernamehasherror'          => 'Потребителското име не може да съдържа хеш символи т.е. /\\*,:;=+?|<>&%...',
'login-throttled'            => 'Направили сте твърде много опити да въведете паролата за тази сметка.
Изчакайте известно време преди да опитате отново.',
'loginlanguagelabel'         => 'Език: $1',
'suspicious-userlogout'      => 'Заявката ви за излизане от системата беше отхвърлена, тъй като изглежда е била изпратена погрешка от браузъра или кеширащото прокси.',

# JavaScript password checks
'password-strength-acceptable' => 'приемлива',
'password-strength-good'       => 'правилна',
'password-retype'              => 'Повтори парола тук',
'password-retype-mismatch'     => 'Паролите не съвпадат',

# Password reset dialog
'resetpass'                 => 'Промяна на парола',
'resetpass_announce'        => 'Влязохте с временен код, получен по електронната поща. Сега е нужно да си изберете нова парола:',
'resetpass_text'            => '<!-- Тук добавете текст -->',
'resetpass_header'          => 'Промяна на парола',
'oldpassword'               => 'Стара парола:',
'newpassword'               => 'Нова парола:',
'retypenew'                 => 'Нова парола повторно:',
'resetpass_submit'          => 'Избиране на парола и влизане',
'resetpass_success'         => 'Паролата ви беше сменена! Сега влизате…',
'resetpass_forbidden'       => 'Не е разрешена смяна на паролата',
'resetpass-no-info'         => 'За да достъпвате тази страница директно, необходимо е да влезете в системата.',
'resetpass-submit-loggedin' => 'Промяна на паролата',
'resetpass-submit-cancel'   => 'Отказ',
'resetpass-wrong-oldpass'   => 'Невалидна временна или текуща парола.
Възможно е вече успешно да сте сменили паролата си или да сте поискали нова временна парола.',
'resetpass-temp-password'   => 'Временна парола:',

# Edit page toolbar
'bold_sample'     => 'Получер текст',
'bold_tip'        => 'Получер (удебелен) текст',
'italic_sample'   => 'Курсивен текст',
'italic_tip'      => 'Курсивен (наклонен) текст',
'link_sample'     => 'Име на препратка',
'link_tip'        => 'Вътрешна препратка',
'extlink_sample'  => 'http://www.example.com Текст на външната препратка',
'extlink_tip'     => 'Външна препратка (не забравяйте http:// отпред)',
'headline_sample' => 'Заглавие на раздел',
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
'summary'                          => 'Резюме:',
'subject'                          => 'Тема/заглавие:',
'minoredit'                        => 'Това е малка промяна',
'watchthis'                        => 'Наблюдаване на страницата',
'savearticle'                      => 'Съхраняване',
'preview'                          => 'Предварителен преглед',
'showpreview'                      => 'Предварителен преглед',
'showlivepreview'                  => 'Бърз предварителен преглед',
'showdiff'                         => 'Показване на промените',
'anoneditwarning'                  => "'''Внимание:''' Не сте влезли в системата. В историята на страницата ще бъде записан вашият IP-адрес.",
'anonpreviewwarning'               => 'Внимание: Не сте влезли в системата. Ако съхраните редакцията си, тя ще бъде записана в историята на страницата с вашият IP-адрес.',
'missingsummary'                   => "'''Напомняне:''' Не е въведено кратко описание на промените. При повторно натискане на бутона „Съхраняване“, редакцията ще бъде съхранена без резюме.",
'missingcommenttext'               => 'По-долу въведете вашето съобщение.',
'missingcommentheader'             => "'''Напомняне:''' Не е въведено заглавие на коментара. При повторно натискане на бутона „Съхраняване“, редакцията ще бъде записана без такова.",
'summary-preview'                  => 'Предварителен преглед на резюмето:',
'subject-preview'                  => 'Предварителен преглед на заглавието:',
'blockedtitle'                     => 'Потребителят е блокиран',
'blockedtext'                      => "'''Вашето потребителско име (или IP-адрес) беше блокирано.'''

Блокирането е извършено от $1. Посочената причина е: ''$2''

*Начало на блокирането: $8
*Край на блокирането: $6
*Блокирането се отнася за: $7

Можете да се свържете с $1 или с някой от останалите [[{{MediaWiki:Grouppage-sysop}}|администратори]], за да обсъдите блокирането.

Можете да използвате услугата „Пращане писмо на потребител“ само ако не ви е забранена употребата й и ако сте посочили валидна електронна поща в [[Special:Preferences|настройките]] си.

Вашият IP адрес е $3, а номерът на блокирането е $5. Включвайте едно от двете или и двете във всяко запитване, което правите.",
'autoblockedtext'                  => "IP-адресът ви беше блокиран автоматично, защото е бил използван от друг потребител, който е бил блокиран от $1.
Посочената причина е:

:''$2''

* Начало на блокирането: $8
* Край на блокирането: $6
* Блокирането се отнася за: $7

Можете да се свържете с $1 или с някой от останалите [[{{MediaWiki:Grouppage-sysop}}|администратори]], за да обсъдите блокирането.

Можете да използвате услугата „Пращане писмо на потребител“ само ако не ви е забранена употребата й и ако сте посочили валидна електронна поща в [[Special:Preferences|настройките]] си.

Текущият ви IP-адрес е $3, а номерът на блокирането ви е $5. Включвайте ги във всяко питане, което правите.",
'blockednoreason'                  => 'не е указана причина',
'blockedoriginalsource'            => "По-долу е показано съдържанието на '''$1''':",
'blockededitsource'                => "По-долу е показан текстът на '''вашите редакции''' на '''$1''':",
'whitelistedittitle'               => 'Необходимо е да влезете, за да може да редактирате',
'whitelistedittext'                => 'Редактирането на страници изисква $1 в системата.',
'confirmedittext'                  => 'Необходимо е да потвърдите електронната си поща, преди да редактирате страници.
Въведете и потвърдете адреса си на [[Special:Preferences|страницата с настройките]].',
'nosuchsectiontitle'               => 'Няма такъв раздел',
'nosuchsectiontext'                => 'Опитахте да редактирате раздел, който не съществува. Може би е бил преместен или изтрит междувременно.',
'loginreqtitle'                    => 'Изисква се влизане',
'loginreqlink'                     => 'влизане',
'loginreqpagetext'                 => 'Необходимо е $1, за да можете да разглеждате други страници.',
'accmailtitle'                     => 'Паролата беше изпратена.',
'accmailtext'                      => "Случайно генерирана парола за [[User talk:$1|$1]] беше изпратена на $2.

Паролата за тази нова потребителска сметка може да бъде променена от специалната страница ''[[Special:ChangePassword|„Промяна на парола“]]'' след влизане в системата.",
'newarticle'                       => '(нова)',
'newarticletext'                   => 'Последвахте препратка към страница, която все още не съществува.
За да я създадете, просто започнете да пишете в долната текстова кутия
(вижте [[{{MediaWiki:Helppage}}|помощната страница]] за повече информация).',
'anontalkpagetext'                 => "----''Това е дискусионната страница на анонимен потребител, който все още няма регистрирана сметка или не я използва, затова се налага да използваме IP-адрес, за да го идентифицираме. Такъв адрес може да се споделя от няколко потребители.''

''Ако сте анонимен потребител и мислите, че тези неуместни коментари са отправени към вас, [[Special:UserLogin/signup|регистрирайте се]] или [[Special:UserLogin|влезте в системата]], за да избегнете евентуално бъдещо объркване с други анонимни потребители.''",
'noarticletext'                    => 'Тази страница все още не съществува. Можете да [[Special:Search/{{PAGENAME}}|потърсите за заглавието на страницата]] в други страници, да <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} потърсите в дневниците] или [{{fullurl:{{FULLPAGENAME}}|action=edit}} да я създадете]</span>.',
'noarticletext-nopermission'       => 'Понастоящем в тази страница няма текст.
Можете да [[Special:Search/{{PAGENAME}}|потърсите заглавието на тази страница ]] в други страници или
да <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} потърсите в съответните дневници]</span>.',
'userpage-userdoesnotexist'        => 'Няма регистрирана потребителска сметка за „$1“. Изисква се потвърждение, че желаете да създадете/редактирате тази страница?',
'userpage-userdoesnotexist-view'   => 'Не е регистрирана потребителска сметка на име „$1“.',
'blocked-notice-logextract'        => 'В момента този потребител е блокиран.
По-долу за справка е показан последният запис от Дневника на блокиранията:',
'clearyourcache'                   => "'''Бележка:''' След съхранението е необходимо да изтриете кеша на браузъра, за да видите промените:
'''Mozilla / Firefox / Safari:''' натиснете бутона ''Shift'' и щракнете върху ''Презареждане'' (''Reload''), или изберете клавишната комбинация ''Ctrl-Shift-R'' (''Cmd-Shift-R'' за Apple Mac);
'''IE:''' натиснете ''Ctrl'' и щракнете върху ''Refresh'', или клавишната комбинация ''CTRL-F5'';
'''Konqueror:''' щракнете върху ''Презареждане'' или натиснете ''F5'';
'''Opera:''' вероятно е необходимо да изчистите кеша през менюто ''Tools→Preferences''.",
'usercssyoucanpreview'             => "'''Съвет:''' Използвайте бутона „{{int:showpreview}}“, за да изпробвате новия код на CSS преди съхранението.",
'userjsyoucanpreview'              => "'''Съвет:''' Използвайте бутона „{{int:showpreview}}“, за да изпробвате новия код на Джаваскрипт преди съхранението.",
'usercsspreview'                   => "'''Не забравяйте, че това е само предварителен преглед на кода на CSS. Страницата все още не е съхранена!'''",
'userjspreview'                    => "'''Не забравяйте, че това е само изпробване/предварителен преглед на кода на Джаваскрипт. Страницата все още не е съхранена!'''",
'userinvalidcssjstitle'            => "'''Внимание:''' Не съществува облик „$1“. Необходимо е да се знае, че имената на потребителските ви страници за CSS и Джаваскрипт трябва да се състоят от малки букви, например: „{{ns:user}}:Иван/monobook.css“ (а не „{{ns:user}}:Иван/Monobook.css“).",
'updated'                          => '(обновена)',
'note'                             => "'''Забележка:'''",
'previewnote'                      => "'''Това е само предварителен преглед. Промените все още не са съхранени!'''",
'previewconflict'                  => 'Този предварителен преглед отразява текста в горната текстова кутия така, както би се показал, ако съхраните.',
'session_fail_preview'             => "'''За съжаление редакцията ви не успя да бъде обработена поради загуба на данните за текущата сесия. Опитайте отново. Ако все още не работи, опитайте да [[Special:UserLogout|излезете]] и да влезете отново.'''",
'session_fail_preview_html'        => "'''За съжаление редакцията ви не беше записана поради изтичането на сесията ви.'''

''Тъй като {{SITENAME}} приема обикновен HTML, предварителният преглед е скрит като предпазна мярка срещу атаки чрез Джаваскрипт.''

'''Опитайте отново. Ако все още не сработва, пробвайте да [[Special:UserLogout|излезете]] и влезете отново.'''",
'token_suffix_mismatch'            => "'''Редакцията ви беше отхвърлена, защото браузърът ви е развалил пунктуационните знаци в редакционната отметка. Евентуалното съхранение би унищожило съдържанието на страницата. Понякога това се случва при използването на грешно работещи анонимни междинни сървъри.'''",
'editing'                          => 'Редактиране на „$1“',
'editingsection'                   => 'Редактиране на „$1“ (раздел)',
'editingcomment'                   => 'Редактиране на „$1“ (нов раздел)',
'editconflict'                     => 'Различна редакция: $1',
'explainconflict'                  => "Някой друг вече е променил тази страница, откакто започнахте да я редактирате.
Горната текстова кутия съдържа текущия текст на страницата без вашите промени, които са показани в долната кутия.
За да бъдат и те съхранени, е необходимо ръчно да ги преместите в горното поле, тъй като '''единствено''' текстът в него ще бъде съхранен при натискането на бутона „Съхраняване“.",
'yourtext'                         => 'Вашият текст',
'storedversion'                    => 'Съхранена версия',
'nonunicodebrowser'                => "'''ВНИМАНИЕ: Браузърът ви не поддържа Уникод. За да можете спокойно да редактирате страници, всички знаци, невключени в ASCII-таблицата, ще бъдат заменени с шестнадесетични кодове.'''",
'editingold'                       => "'''ВНИМАНИЕ: Редактирате остаряла версия на страницата.
Ако съхраните, всякакви промени, направени след тази версия, ще бъдат изгубени.'''",
'yourdiff'                         => 'Разлики',
'copyrightwarning'                 => "Обърнете внимание, че всички приноси към {{SITENAME}} се публикуват при условията на \$2 (за подробности вижте \$1).
Ако не сте съгласни вашата писмена работа да бъде променяна и разпространявана без ограничения, не я публикувайте.<br />

Също потвърждавате, че '''вие''' сте написали материала или сте използвали '''свободни ресурси''' — <em>обществено достояние</em> или друг свободен източник.
Ако сте ползвали чужди материали, за които имате разрешение, непременно посочете източника.

<div style=\"font-variant:small-caps\">'''Не публикувайте произведения с авторски права без разрешение!'''</div>",
'copyrightwarning2'                => "Обърнете внимание, че всички приноси към {{SITENAME}} могат да бъдат редактирани, променяни или премахвани от останалите сътрудници.
Ако не сте съгласни вашата писмена работа да бъде променяна без ограничения, не я публикувайте.<br />
Също потвърждавате, че '''вие''' сте написали материала или сте използвали '''свободни ресурси''' — <em>обществено достояние</em> или друг свободен източник (за подробности вижте \$1).
Ако сте ползвали чужди материали, за които имате разрешение, непременно посочете източника.

<div style=\"font-variant:small-caps\">'''Не публикувайте произведения с авторски права без разрешение!'''</div>",
'longpagewarning'                  => "'''ВНИМАНИЕ: Страницата има размер $1 килобайта; някои браузъри могат да имат проблеми при редактиране на страници по-големи от 32 kB.
Обмислете дали страницата не може да се раздели на няколко по-малки части.'''",
'longpageerror'                    => "'''ГРЕШКА: Текстът, който пращате, е с големина $1 килобайта, което надвишава позволения максимум от $2 килобайта. Заради това не може да бъде съхранен.'''",
'readonlywarning'                  => "'''ВНИМАНИЕ: Базата от данни беше затворена за поддръжка, затова в момента промените ви не могат да бъдат съхранени. Ако желаете, можете да съхраните страницата като текстов файл и да се опитате да я публикувате по-късно.'''

Администраторът, който е затворил базата от данни, е посочил следната причина: $1",
'protectedpagewarning'             => "'''Внимание: Страницата е защитена и само потребители със статут на администратори могат да я редактират.'''
За справка по-долу е показан последният запис от дневниците.",
'semiprotectedpagewarning'         => "'''Забележка:''' Тази страница е защитена и само регистрирани потребители могат да я редактират.
За справка по-долу е показан последният запис от дневниците.",
'cascadeprotectedwarning'          => "'''Внимание:''' Страницата е защитена, като само потребители с администраторски права могат да я редактират. Тя е включена в {{PLURAL:$1|следната страница|следните страници}} с каскадна защита:",
'titleprotectedwarning'            => "'''Внимание: Тази страница е защитена и са необходими [[Special:ListGroupRights|специално права]], за да бъде създадена.'''
За справка по-долу е показан последният запис от дневниците.",
'templatesused'                    => '{{PLURAL:$1|Шаблон, използван|Шаблони, използвани}} на страницата:',
'templatesusedpreview'             => '{{PLURAL:$1|Шаблон, използван|Шаблони, използвани}} в предварителния преглед:',
'templatesusedsection'             => '{{PLURAL:$1|Шаблон, използван|Шаблони, използвани}} в този раздел:',
'template-protected'               => '(защитен)',
'template-semiprotected'           => '(полузащитен)',
'hiddencategories'                 => 'Тази страница е включена в {{PLURAL:$1|Една скрита категория|$1 скрити категории}}:',
'edittools'                        => '<!-- Евентуален текст тук ще бъде показван под формулярите за редактиране и качване. -->',
'nocreatetitle'                    => 'Създаването на страници е ограничено',
'nocreatetext'                     => 'Създаването на нови страници в {{SITENAME}} е ограничено. Можете да се върнете назад и да редактирате някоя от съществуващите страници, [[Special:UserLogin|да се регистрирате или да създадете нова потребителска сметка]].',
'nocreate-loggedin'                => 'Нямате необходимите права да създавате нови страници.',
'sectioneditnotsupported-title'    => 'Не се поддържа редактиране на раздели',
'sectioneditnotsupported-text'     => 'Не се поддържа редактиране на раздели на тази страница.',
'permissionserrors'                => 'Грешки при правата на достъп',
'permissionserrorstext'            => 'Нямате правата да извършите това действие по {{PLURAL:$1|следната причина|следните причини}}:',
'permissionserrorstext-withaction' => 'Нямате разрешение за $2 поради {{PLURAL:$1|следната причина|следните причини}}:',
'recreate-moveddeleted-warn'       => "'''Внимание: Създавате страница, която по-рано вече е била изтрита.'''

Обмислете добре дали е уместно повторното създаване на страницата.
За ваша информация по-долу е посочена причината за предишното изтриване на страницата:",
'moveddeleted-notice'              => 'Тази страница е била изтрита.
За справка, по-долу са включени извадки от дневниците на изтриванията и преместванията.',
'log-fulllog'                      => 'Преглеждане на пълния дневник',
'edit-hook-aborted'                => 'Редакцията беше прекъсната от кука. Не беше посочена причина за това.',
'edit-gone-missing'                => 'Страницата не можа да се обнови.
Вероятно междувременно е била изтрита.',
'edit-conflict'                    => 'Редакционен конфликт.',
'edit-no-change'                   => 'Вашата редакция беше игнорирана, тъй като не съдържа промени по текста.',
'edit-already-exists'              => 'Не можа да се създаде нова страница.
Такава вече съществува.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Внимание: Тази страница прекалено много пъти използва ресурсоемки парсерни функции.

В момента има {{PLURAL:$1|$1 обръщение|$1 обръщения}} към такива функции, а трябва да {{PLURAL:$1|е|са}} по-малко от $2.',
'expensive-parserfunction-category'       => 'Страници, които прекалено много пъти използват ресурсоемки парсерни функции',
'post-expand-template-inclusion-warning'  => 'Внимание: Размерът за включване на този шаблон е твърде голям.
Някои шаблони няма да бъдат включени.',
'post-expand-template-inclusion-category' => 'Страници, съдържащи шаблони с превишени размери на включеното съдържание',
'post-expand-template-argument-warning'   => 'Внимание: Тази страница съдържа поне един параметър на шаблон, който има твърде голям размер при разгръщане. Тези параметри бяха пропуснати.',
'post-expand-template-argument-category'  => 'Страници, съдържащи шаблони с пропуснати параметри',
'parser-template-loop-warning'            => 'Открито зацикляне на шаблон: [[$1]]',
'parser-template-recursion-depth-warning' => 'Надвишен лимит на дълбочината при шаблонна рекурсия ($1)',
'language-converter-depth-warning'        => 'Надвишени са възможностите за автоматичен превод ($1)',

# "Undo" feature
'undo-success' => 'Редакцията може да бъде върната. Прегледайте долното сравнение и се уверете, че наистина искате да го направите. След това съхранете страницата, за да извършите връщането.',
'undo-failure' => 'Редакцията не може да бъде върната поради конфликтни междинни редакции.',
'undo-norev'   => 'Редакцията не може да бъде върната тъй като не съществува или е била изтрита.',
'undo-summary' => 'Премахната редакция $1 на [[Special:Contributions/$2|$2]] ([[User talk:$2|беседа]])',

# Account creation failure
'cantcreateaccounttitle' => 'Невъзможно е да бъде създадена потребителска сметка.',
'cantcreateaccount-text' => "[[User:$3|Потребител:$3]] е блокирал(а) създаването на сметки от този IP-адрес ('''$1''').

Причината, изложена от $3, е ''$2''",

# History pages
'viewpagelogs'           => 'Преглед на извършените административни действия по страницата',
'nohistory'              => 'Няма редакционна история за тази страница.',
'currentrev'             => 'Текуща версия',
'currentrev-asof'        => 'Текуща версия към $1',
'revisionasof'           => 'Версия от $1',
'revision-info'          => 'Версия от $1 на $2',
'previousrevision'       => '←По-стара версия',
'nextrevision'           => 'По-нова версия→',
'currentrevisionlink'    => 'преглед на текущата версия',
'cur'                    => 'тек',
'next'                   => 'след',
'last'                   => 'пред',
'page_first'             => 'първа',
'page_last'              => 'последна',
'histlegend'             => '<em>Разлики:</em> Изберете версиите, които желаете да сравните, чрез превключвателите срещу тях и натиснете &lt;Enter&gt; или бутона за сравнение.<br />
<em>Легенда:</em> (<strong>тек</strong>) = разлика с текущата версия, (<strong>пред</strong>) = разлика с предишната версия, <strong>м</strong>&nbsp;=&nbsp;малка промяна',
'history-fieldset-title' => 'Търсене в историята',
'history-show-deleted'   => 'Само изтритите',
'histfirst'              => 'Първи',
'histlast'               => 'Последни',
'historysize'            => '({{PLURAL:$1|1 байт|$1 байта}})',
'historyempty'           => '(празна)',

# Revision feed
'history-feed-title'          => 'Редакционна история',
'history-feed-description'    => 'Редакционна история на страницата в {{SITENAME}}',
'history-feed-item-nocomment' => '$1 в $2',
'history-feed-empty'          => 'Исканата страница не съществува — може да е била изтрита или преименувана. Опитайте да [[Special:Search|потърсите]] нови страници, които биха могли да са ви полезни.',

# Revision deletion
'rev-deleted-comment'         => '(коментарът е изтрит)',
'rev-deleted-user'            => '(името на автора е изтрито)',
'rev-deleted-event'           => '(записът е изтрит)',
'rev-deleted-user-contribs'   => '[потребителското име или IP адреса е отстранен - премахната редакция]',
'rev-deleted-text-permission' => "Тази версия на страницата е била '''изтрита'''.
Допълнителна информация може да се съдържа в [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Дневника на изтриванията].",
'rev-deleted-text-unhide'     => "Тази версия на страницата е била '''изтрита'''.
Допълнителна информация може се съдържа в [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Дневника на изтриванията].
Като администратор на сайта вие можете да [$1 прегледате тази редакция], ако желаете да продължите.",
'rev-suppressed-text-unhide'  => "Тази версия на страницата е била '''прикрита'''.
Допълнителна информация може да се съдържа в [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Дневника на прикриванията].
Като администратор на сайта, вие можете да [$1 прегледате версията], ако желаете да продължите.",
'rev-deleted-text-view'       => "Тази редация на страницата е била '''изтрита'''.
Като администратор на сайта, вие можете да я прегледате.
Допълнителна информация може да се съдържа в [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} Дневника на изтриванията].",
'rev-suppressed-text-view'    => "Тази редация на страницата е била '''прикрита'''.
Като администратор на сайта, вие можете да я прегледате.
Допълнителна информация може да се съдържа в [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Дневника на прикриванията].",
'rev-deleted-no-diff'         => "Нямате достъп до тази разликова препратка, тъй като една от съставящите я редакции е била '''изтрита'''.
Допълнителна информация може да се съдържа в [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} Дневника на изтриванията].",
'rev-suppressed-no-diff'      => "Не можете да прегледате тази разликова връзка, защото една от участващите в нея версии е била '''изтрита'''.",
'rev-deleted-unhide-diff'     => "Една от редакциите в тази разликова препратка е била '''изтрита'''.
Допълнителна информация може да се съдържа в [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} Дневника на изтриванията].
Като администратор на сайта, вие можете да [$1 прегледате разликовата препратка], ако желаете.",
'rev-suppressed-unhide-diff'  => "Една от версиите, съставляващи тази разликова препратка, е била '''прикрита'''.
Допълнителна информация може да се съдържа в [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Дневника на прикриванията].
ато администратор на сайта, вие можете да [$1 прегледате тази разликова препратка], ако желаете да продължите.",
'rev-deleted-diff-view'       => "Една от версиите на тази разлика е била '''изтрита'''.
Като администратор, можете да видите тази разлика; възможно е да има повече информация в [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} дневника на изтриванията].",
'rev-suppressed-diff-view'    => "Една от редакциите беше '''премахната'''.
Като администратор можете да видите тази редакция; може да има информация в [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} дневника за изтриване].",
'rev-delundel'                => 'показване/скриване',
'rev-showdeleted'             => 'показване',
'revisiondelete'              => 'Изтриване/възстановяване на версии',
'revdelete-nooldid-title'     => 'Не е зададена версия',
'revdelete-nooldid-text'      => 'Не сте задали версия или версии за изпълнението на тази функция.',
'revdelete-nologtype-title'   => 'Не е посочен тип на дневника',
'revdelete-nologtype-text'    => 'Не е посочен дневник, в който да бъде изпълнено желаното действие.',
'revdelete-nologid-title'     => 'Невалиден запис в дневника',
'revdelete-nologid-text'      => 'Не сте указали правилната редакция за изпълнение на тази функция, или такава статия не съществува',
'revdelete-no-file'           => 'Посоченият файл не съществува.',
'revdelete-show-file-confirm' => 'Необходимо е потвърждение, че желаете да прегледате изтритата версия на файла „<nowiki>$1</nowiki>“ от $2 $3.',
'revdelete-show-file-submit'  => 'Да',
'revdelete-selected'          => "'''{{PLURAL:$2|Избрана версия|Избрани версии}} от '''$1:''''''",
'logdelete-selected'          => "'''{{PLURAL:$1|Избрано събитие|Избрани събития}}:'''",
'revdelete-text'              => "'''Изтритите версии ше се показват в историята на страницата, но тяхното съдържание ще бъде недостъпно за обикновенните потребители.'''
Администраторите на {{SITENAME}} ще имат достъп до скритото съдържание и ще могат да го възстановят, с изключение на случаите, когато има наложено допълнително ограничение.",
'revdelete-confirm'           => 'Необходимо е да потвърдите, че велаете да извършите действието, разбирате последствията и го правите според [[{{MediaWiki:Policy-url}}|политиката]].',
'revdelete-suppress-text'     => "Премахването трябва да се използва '''само''' при следните случаи:
*Неподходяща или неприемлива лична информация
*: ''домашни адреси и телефонни номера, номера за социално осигуряване и др.''",
'revdelete-legend'            => 'Задаване на ограничения:',
'revdelete-hide-text'         => 'Скриване на текста на версията',
'revdelete-hide-image'        => 'Скриване на файловото съдържание',
'revdelete-hide-name'         => 'Скриване на действието и целта',
'revdelete-hide-comment'      => 'Скриване на коментара',
'revdelete-hide-user'         => 'Скриване на името/IP-адреса на автора',
'revdelete-hide-restricted'   => 'Прилагане на тези ограничения и за администраторите',
'revdelete-radio-same'        => '(да не се променя)',
'revdelete-radio-set'         => 'Да',
'revdelete-radio-unset'       => 'Не',
'revdelete-suppress'          => 'Скриване на причината за изтриването и от администраторите',
'revdelete-unsuppress'        => 'Премахване на ограниченията за възстановените версии',
'revdelete-log'               => 'Причина:',
'revdelete-submit'            => 'Прилагане към {{PLURAL:$1|избраната версия|избраните версии}}',
'revdelete-logentry'          => 'промени видимостта на версия на [[$1]]',
'logdelete-logentry'          => 'промени видимостта на събитие за [[$1]]',
'revdelete-success'           => "'''Видимостта на версията беше променена успешно.'''",
'revdelete-failure'           => "'''Редакцията не може да се актуализира:'''
$1",
'logdelete-success'           => 'Видимостта на събитието беше променена.',
'logdelete-failure'           => "'''Данните не могат да се видят:'''
$1",
'revdel-restore'              => 'Промяна на видимостта',
'revdel-restore-deleted'      => 'изтрити версии',
'revdel-restore-visible'      => 'видими редакции',
'pagehist'                    => 'История на страницата',
'deletedhist'                 => 'Изтрита история',
'revdelete-content'           => 'съдържание',
'revdelete-summary'           => 'резюме',
'revdelete-uname'             => 'потребителско име',
'revdelete-restricted'        => 'добавени ограничения за администраторите',
'revdelete-unrestricted'      => 'премахнати ограничения за администраторите',
'revdelete-hid'               => 'скри $1',
'revdelete-unhid'             => 'разкри $1',
'revdelete-log-message'       => '$1 за $2 {{PLURAL:$2|версия|версии}}',
'logdelete-log-message'       => '$1 за $2 {{PLURAL:$2|събитие|събития}}',
'revdelete-hide-current'      => 'Грешка при скриване на елемента от $2, $1: представлява текущата версия.
Тя не може да бъде скрита.',
'revdelete-show-no-access'    => 'Грешка при показване на обект, датиран към $2, $1: обектът е бил отбелязан като "ограничен".
Нямате достъп до него.',
'revdelete-modify-no-access'  => 'Грешка при промяна на елемент от $2, $1: Този елемент е бил отбелязан като „ограничен“.
Вие нямате достъп до него.',
'revdelete-modify-missing'    => 'Грешка при промяна на елемент с номер $1: липсва в базата данни!',
'revdelete-no-change'         => "'''Внимание:''' елементът от $2, $1 вече притежава поисканите настройки за видимост.",
'revdelete-concurrent-change' => 'Грешка при модифициране на обект, датиран към $2, $1: изглежда, че статутът му е бил променен от някого, докато сте се опитвали да го модифицирате.
Моля, погледнете в съответните дневници.',
'revdelete-only-restricted'   => 'Грешка при прикриване на обекта на $2, $1: не можете да прикривате обекти от администраторите, без същевременно да изберете някоя от другите опции за прозрачност.',
'revdelete-reason-dropdown'   => '* Стандартни причини за изтриване
** Нарушение на авторски права
** Неуместна лична информация
** Потенциално клеветническа информация',
'revdelete-otherreason'       => 'Друга/допълнителна причина:',
'revdelete-reasonotherlist'   => 'Друга причина',
'revdelete-edit-reasonlist'   => 'Редактиране на причините за изтриване',
'revdelete-offender'          => 'Автор на версията:',

# Suppression log
'suppressionlog'     => 'Дневник на прикриванията',
'suppressionlogtext' => 'По-долу е посочен списък на изтривания и блокирания, свързан със съдържание, скрито от администраторите.
За текущите блокирания и забрани, вижте [[Special:IPBlockList|списъка с блокираните IP адреси]].',

# Revision move
'revmove-reasonfield'    => 'Причина:',
'revmove-badparam-title' => 'Сгрешени параметри',

# History merging
'mergehistory'                     => 'Сливане на редакционни истории',
'mergehistory-header'              => 'Тази страница ви позволява да сливате историята на редакциите на дадена изходна страница с историята на нова страница. Уверете се, че тази промяна ще запази целостта на историята.',
'mergehistory-box'                 => 'Сливане на редакционните истории на две страници:',
'mergehistory-from'                => 'Изходна страница:',
'mergehistory-into'                => 'Целева страница:',
'mergehistory-list'                => 'Сливаема редакционна история',
'mergehistory-merge'               => 'Следните версии на [[:$1]] могат да бъдат прехвърлени към [[:$2]]. Използвайте колонката с превключвателите, за да прехвърлите само тези версии, създадени преди или през избрания времеви период. Обърнете внимание, че ползването на навигационните препратки, ще премахне избраното в тази колонка.',
'mergehistory-go'                  => 'Показване на редакциите, които могат да се слеят',
'mergehistory-submit'              => 'Сливане на редакции',
'mergehistory-empty'               => 'Няма редакции, които могат да бъдат слети.',
'mergehistory-success'             => '$3 {{PLURAL:$3|версия|версии}} от [[:$1]] бяха успешно слети с редакционната история на [[:$2]].',
'mergehistory-fail'                => 'Невъзможно е да се извърши сливане на редакционните истории; проверете страницата и времевите параметри.',
'mergehistory-no-source'           => 'Изходната страница $1 не съществува.',
'mergehistory-no-destination'      => 'Целевата страница $1 не съществува.',
'mergehistory-invalid-source'      => 'Изходната страница трябва да притежава коректно име.',
'mergehistory-invalid-destination' => 'Целевата страница трябва да притежава коректно име.',
'mergehistory-autocomment'         => 'Слята [[:$1]] в [[:$2]]',
'mergehistory-comment'             => 'Слята [[:$1]] в [[:$2]]: $3',
'mergehistory-same-destination'    => 'Изходната и целевата страница не могат да съвпадат',
'mergehistory-reason'              => 'Причина:',

# Merge log
'mergelog'           => 'Дневник на сливанията',
'pagemerge-logentry' => 'обедини [[$1]] с [[$2]] (до редакция $3)',
'revertmerge'        => 'Разделяне',
'mergelogpagetext'   => 'Страницата съдържа списък с последните сливания на редакционни истории.',

# Diffs
'history-title'            => 'Преглед на историята на „$1“',
'difference'               => '(Разлики между версиите)',
'lineno'                   => 'Ред $1:',
'compareselectedversions'  => 'Сравнение на избраните версии',
'showhideselectedversions' => 'Показване/скриване на избрани версии',
'editundo'                 => 'връщане',
'diff-multi'               => '({{PLURAL:$1|Една междинна версия не е показана|$1 междинни версии не са показани}}.)',

# Search results
'searchresults'                    => 'Резултати от търсенето',
'searchresults-title'              => 'Резултати от търсенето за „$1“',
'searchresulttext'                 => 'За повече информация относно търсенето в {{SITENAME}}, вижте [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'За заявка „[[:$1]]“ ([[Special:Prefixindex/$1|всички страници, които започват с „$1“]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|всички страници, които сочат към „$1“]])',
'searchsubtitleinvalid'            => 'За заявка „$1“',
'toomanymatches'                   => 'Бяха открити твърде много съвпадения, опитайте с различна заявка',
'titlematches'                     => 'Съответствия в заглавията на страници',
'notitlematches'                   => 'Няма съответствия в заглавията на страници',
'textmatches'                      => 'Съответствия в текста на страници',
'notextmatches'                    => 'Няма съответствия в текста на страници',
'prevn'                            => 'предишни {{PLURAL:$1|$1}}',
'nextn'                            => 'следващи {{PLURAL:$1|$1}}',
'prevn-title'                      => '$1 {{PLURAL:$1|предишен резултат|предишни резултата}}',
'nextn-title'                      => '$1 {{PLURAL:$1|следващ резултат|следващи резултата}}',
'shown-title'                      => 'Показване на $1 {{PLURAL:$1|резултат|резултата}} на страница',
'viewprevnext'                     => 'Преглед ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => 'Настройки на търсенето',
'searchmenu-exists'                => "'''Съществува страница с името „[[:$1]]“ в това уики.'''",
'searchmenu-new'                   => "'''Създаване на страницата „[[:$1]]“ в това уики!'''",
'searchhelp-url'                   => 'Help:Съдържание',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Преглеждане на страниците с тази представка]]',
'searchprofile-articles'           => 'Съдържателни страници',
'searchprofile-project'            => 'Помощни и проектни страници',
'searchprofile-images'             => 'Мултимедия',
'searchprofile-everything'         => 'Всичко',
'searchprofile-advanced'           => 'Разширено търсене',
'searchprofile-articles-tooltip'   => 'Търсене в $1',
'searchprofile-project-tooltip'    => 'Търсене в $1',
'searchprofile-images-tooltip'     => 'Търсене на файлове',
'searchprofile-everything-tooltip' => 'Търсене в цялото съдържание (вкл. беседи)',
'searchprofile-advanced-tooltip'   => 'Търсене в избрани именни пространства',
'search-result-size'               => '$1 ({{PLURAL:$2|една дума|$2 думи}})',
'search-result-score'              => 'Релевантност: $1%',
'search-redirect'                  => '(пренасочване $1)',
'search-section'                   => '(раздел $1)',
'search-suggest'                   => 'Вероятно имахте предвид: $1',
'search-interwiki-caption'         => 'Сродни проекти',
'search-interwiki-default'         => '$1 резултата:',
'search-interwiki-more'            => '(още)',
'search-mwsuggest-enabled'         => 'с предположения',
'search-mwsuggest-disabled'        => 'без предположения',
'search-relatedarticle'            => 'Свързани',
'mwsuggest-disable'                => 'Изключване на AJAX предположенията',
'searcheverything-enable'          => 'Търсене във всички именни пространства',
'searchrelated'                    => 'свързани',
'searchall'                        => 'всички',
'showingresults'                   => "Показване на до {{PLURAL:$1|'''1''' резултат|'''$1''' резултата}}, като се започва от номер '''$2'''.",
'showingresultsnum'                => "Показване на {{PLURAL:$3|'''1''' резултат|'''$3''' резултата}}, като се започва от номер '''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Резултат '''$1''' от '''$3'''|Резултати '''$1 - $2''' от '''$3'''}} за '''$4'''",
'nonefound'                        => "'''Забележка''': Търсенето по подразбиране се свежда само до някои именни пространства.
Опитайте пак, като отбележите в заявката си префикса ''all:'', за търсене из цялото съдържание на базата данни (включително дискусионни страници, шаблони и т.н.) или използвайте желаното именно пространство като префикс.",
'search-nonefound'                 => 'Няма резултати, които да отговарят на заявката.',
'powersearch'                      => 'Търсене',
'powersearch-legend'               => 'Разширено търсене',
'powersearch-ns'                   => 'Търсене в именни пространства:',
'powersearch-redir'                => 'Списък на пренасочванията',
'powersearch-field'                => 'Търсене на',
'powersearch-togglelabel'          => 'Проверка:',
'powersearch-toggleall'            => 'Всички',
'powersearch-togglenone'           => 'Никои',
'search-external'                  => 'Външно търсене',
'searchdisabled'                   => 'Търсенето в {{SITENAME}} е временно изключено. Междувременно можете да търсите чрез Google. Обърнете внимание, че съхранените при тях страници най-вероятно са остарели.',

# Quickbar
'qbsettings'               => 'Лента за бърз избор',
'qbsettings-none'          => 'Без меню',
'qbsettings-fixedleft'     => 'Неподвижно вляво',
'qbsettings-fixedright'    => 'Неподвижно вдясно',
'qbsettings-floatingleft'  => 'Плаващо вляво',
'qbsettings-floatingright' => 'Плаващо вдясно',

# Preferences page
'preferences'                   => 'Настройки',
'mypreferences'                 => 'Моите настройки',
'prefs-edits'                   => 'Брой редакции:',
'prefsnologin'                  => 'Не сте влезли',
'prefsnologintext'              => 'Необходимо е <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} да влезете]</span>, за да може да променяте потребителските си настройки.',
'changepassword'                => 'Смяна на парола',
'prefs-skin'                    => 'Облик',
'skin-preview'                  => 'предварителен преглед',
'prefs-math'                    => 'Математически формули',
'datedefault'                   => 'Без предпочитание',
'prefs-datetime'                => 'Дата и час',
'prefs-personal'                => 'Потребителски данни',
'prefs-rc'                      => 'Последни промени',
'prefs-watchlist'               => 'Списък за наблюдение',
'prefs-watchlist-days'          => 'Брой дни, които да се показват в списъка за наблюдение:',
'prefs-watchlist-days-max'      => '(най-много 7 дни)',
'prefs-watchlist-edits'         => 'Брой редакции, които се показват в разширения списък за наблюдение:',
'prefs-watchlist-edits-max'     => '(максимален брой: 1000)',
'prefs-watchlist-token'         => 'Уникален идентификатор на списъка за наблюдение:',
'prefs-misc'                    => 'Други настройки',
'prefs-resetpass'               => 'Промяна на паролата',
'prefs-email'                   => 'Настройки за електронната поща',
'prefs-rendering'               => 'Изглед',
'saveprefs'                     => 'Съхраняване',
'resetprefs'                    => 'Отмяна на текущите промени',
'restoreprefs'                  => 'Възстановяване на всички настройки по подразбиране',
'prefs-editing'                 => 'Редактиране',
'prefs-edit-boxsize'            => 'Размер на прозореца за редактиране.',
'rows'                          => 'Редове:',
'columns'                       => 'Колони:',
'searchresultshead'             => 'Търсене',
'resultsperpage'                => 'Резултати на страница:',
'contextlines'                  => 'Редове за резултат:',
'contextchars'                  => 'Знаци от контекста на ред:',
'stub-threshold'                => 'Праг за форматиране на <a href="#" class="stub">препратки към мъничета</a>:',
'recentchangesdays'             => 'Брой дни в последни промени:',
'recentchangesdays-max'         => '(най-много $1 {{PLURAL:$1|ден|дни}})',
'recentchangescount'            => 'Брой показвани редакции по подразбиране:',
'prefs-help-recentchangescount' => 'Това включва последните промени, историите на страниците и дневниците.',
'prefs-help-watchlist-token'    => 'Вписването на таен ключ в това поле ще генерира RSS емисия за вашия списък за наблюдение.
Всеки, който знае тази парола, ще може да чете списъка ви за наблюдение, затова изберете някаква сигурна комбинация.
Можете да използвате следната случайно генерирана комбинация: $1',
'savedprefs'                    => 'Настройките ви бяха съхранени.',
'timezonelegend'                => 'Часова зона:',
'localtime'                     => 'Местно време:',
'timezoneuseserverdefault'      => 'По подразбиране от сървъра',
'timezoneuseoffset'             => 'Друга (посочете отместване)',
'timezoneoffset'                => 'Отместване¹:',
'servertime'                    => 'Време на сървъра:',
'guesstimezone'                 => 'Попълване чрез браузъра',
'timezoneregion-africa'         => 'Африка',
'timezoneregion-america'        => 'Америка',
'timezoneregion-antarctica'     => 'Антарктида',
'timezoneregion-arctic'         => 'Арктика',
'timezoneregion-asia'           => 'Азия',
'timezoneregion-atlantic'       => 'Атлантически океан',
'timezoneregion-australia'      => 'Австралия',
'timezoneregion-europe'         => 'Европа',
'timezoneregion-indian'         => 'Индийски океан',
'timezoneregion-pacific'        => 'Тихи океан',
'allowemail'                    => 'Възможност за получаване на писма от други потребители',
'prefs-searchoptions'           => 'Настройки за търсене',
'prefs-namespaces'              => 'Именни пространства',
'defaultns'                     => 'Или търсене в следните именни пространства:',
'default'                       => 'по подразбиране',
'prefs-files'                   => 'Файлове',
'prefs-custom-css'              => 'Личен CSS',
'prefs-custom-js'               => 'Личен JS',
'prefs-common-css-js'           => 'Общи настройки на CSS/JS за всички облици:',
'prefs-reset-intro'             => 'Тази страница може да се използва за възстановяване на потребителските настройки към стандартните за сайта.
Това действие е необратимо.',
'prefs-emailconfirm-label'      => 'Потвърждаване на адрес за е-поща:',
'prefs-textboxsize'             => 'Размер на кутията за редактиране',
'youremail'                     => 'Е-поща:',
'username'                      => 'Потребителско име:',
'uid'                           => 'Потребителски номер:',
'prefs-memberingroups'          => 'Член на {{PLURAL:$1|група|групи}}:',
'prefs-registration'            => 'Регистрация:',
'yourrealname'                  => 'Истинско име:',
'yourlanguage'                  => 'Език:',
'yourvariant'                   => 'Вариант',
'yournick'                      => 'Подпис:',
'prefs-help-signature'          => 'Коментарите в дискусионните страници трябва да се подписват с поредица от четири тилди "<nowiki>~~~~</nowiki>", която при съхранение на редакцията сървърът превръща в подпис с потребителско име, дата и час.',
'badsig'                        => 'Избраният подпис не е валиден. Проверете HTML-етикетите!',
'badsiglength'                  => 'Вашият подпис е твърде дълъг.
Подписите не могат да надвишават $1 {{PLURAL:$1|знак|знака}}.',
'yourgender'                    => 'Пол:',
'gender-unknown'                => 'Не е посочено',
'gender-male'                   => 'Мъж',
'gender-female'                 => 'Жена',
'prefs-help-gender'             => 'По желание: използва се за коректно обръщение по род в системните съобщения на софтуера. Тази информация е публично достъпна.',
'email'                         => 'Е-поща',
'prefs-help-realname'           => '* <strong>Истинско име</strong> <em>(незадължително)</em>: Ако го посочите, на него ще бъдат приписани вашите приноси.',
'prefs-help-email'              => 'Електронната поща е незадължителна, но позволява да ви бъде изпратена нова парола, в случай че забравите текущата. Можете също да изберете дали другите потребители могат да се свързват с вас, без да се налага да им съобщавате адреса си.',
'prefs-help-email-required'     => 'Изисква се адрес за електронна поща.',
'prefs-info'                    => 'Основна информация',
'prefs-i18n'                    => 'Интернационализация',
'prefs-signature'               => 'Подпис',
'prefs-dateformat'              => 'Формат на датата',
'prefs-timeoffset'              => 'Часово отместване',
'prefs-advancedediting'         => 'Разширени настройки',
'prefs-advancedrc'              => 'Разширени настройки',
'prefs-advancedrendering'       => 'Разширени настройки',
'prefs-advancedsearchoptions'   => 'Разширени настройки',
'prefs-advancedwatchlist'       => 'Разширени настройки',
'prefs-displayrc'               => 'Настройки за показване на списъка',
'prefs-displaywatchlist'        => 'Видими настройки',
'prefs-diffs'                   => 'Разлики',

# User rights
'userrights'                   => 'Управление на потребителските права',
'userrights-lookup-user'       => 'Управляване на потребителските групи',
'userrights-user-editname'     => 'Потребителско име:',
'editusergroup'                => 'Редактиране на потребителските групи',
'editinguser'                  => "Промяна на потребителските права на потребител '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'Редактиране на потребителските групи',
'saveusergroups'               => 'Съхраняване на потребителските групи',
'userrights-groupsmember'      => 'Член на:',
'userrights-groupsmember-auto' => 'Подразбиращ се член на:',
'userrights-groups-help'       => 'Може да променяте групите, в които е потребителят:
* Поставена отметка означава, че потребителят е член на групата.
* Поле без отметка означава, че потребителят не е член на групата.
* Знакът * показва, че не можете да премахнете групата, след като е вече добавена (или обратно).',
'userrights-reason'            => 'Причина:',
'userrights-no-interwiki'      => 'Нямате права да редактирате потребителските групи на други уикита.',
'userrights-nodatabase'        => 'Базата данни $1 не съществува или не е на локалния сървър.',
'userrights-nologin'           => 'За управление на потребителските права е необходимо [[Special:UserLogin|влизане]] с администраторска сметка.',
'userrights-notallowed'        => 'Не ви е позволено да променяте потребителски права.',
'userrights-changeable-col'    => 'Групи, които можете да променяте',
'userrights-unchangeable-col'  => 'Групи, които не можете да променяте',

# Groups
'group'               => 'Потребителска група:',
'group-user'          => 'Потребители',
'group-autoconfirmed' => 'Автоматично одобрени потребители',
'group-bot'           => 'Ботове',
'group-sysop'         => 'Администратори',
'group-bureaucrat'    => 'Бюрократи',
'group-suppress'      => 'Ревизори',
'group-all'           => '(всички)',

'group-user-member'          => 'Потребител',
'group-autoconfirmed-member' => 'Автоматично одобрен потребител',
'group-bot-member'           => 'Бот',
'group-sysop-member'         => 'Администратор',
'group-bureaucrat-member'    => 'Бюрократ',
'group-suppress-member'      => 'Ревизор',

'grouppage-user'          => '{{ns:project}}:Потребители',
'grouppage-autoconfirmed' => '{{ns:project}}:Автоматично одобрени потребители',
'grouppage-bot'           => '{{ns:project}}:Ботове',
'grouppage-sysop'         => '{{ns:project}}:Администратори',
'grouppage-bureaucrat'    => '{{ns:project}}:Бюрократи',
'grouppage-suppress'      => '{{ns:project}}:Ревизори',

# Rights
'right-read'                  => 'четене на страници',
'right-edit'                  => 'редактиране на страници',
'right-createpage'            => 'създаване на страници (които не са беседи)',
'right-createtalk'            => 'създаване на дискусионни страници',
'right-createaccount'         => 'създаване на нови потребителски сметки',
'right-minoredit'             => 'отбелязване като малка промяна',
'right-move'                  => 'преместване на страници',
'right-move-subpages'         => 'преместване на страници и техните подстраници',
'right-move-rootuserpages'    => 'Преместване на основните потребителски страници',
'right-movefile'              => 'Преместване на файлове',
'right-suppressredirect'      => 'без създаване на пренасочване от старото име при преместване на страница',
'right-upload'                => 'качване на файлове',
'right-reupload'              => 'презаписване на съществуващ файл',
'right-reupload-own'          => 'Презаписване на съществуващ файл, качен от същия потребител',
'right-reupload-shared'       => 'Предефиниране на едноименните файлове от общото мултимедийно хранилище с локални',
'right-upload_by_url'         => 'качване на файл от URL адрес',
'right-purge'                 => 'изчистване на складираното съдържание на страниците без показване на страница за потвърждение',
'right-autoconfirmed'         => 'редактиране на полузащитени страници',
'right-bot'                   => 'третиране като авоматизиран процес',
'right-nominornewtalk'        => 'Малките промени по дискусионните страници не предизвикват известието за ново съобщение',
'right-apihighlimits'         => 'използване на крайните предели в API заявките',
'right-writeapi'              => 'Употреба на API за писане',
'right-delete'                => 'изтриване на страници',
'right-bigdelete'             => 'изтриване на страници с големи редакционни истории',
'right-deleterevision'        => 'изтриване и възстановяване на отделни версии на страниците',
'right-deletedhistory'        => 'преглеждане на записи от изтрити редакционни истории без асоциирания към тях текст',
'right-deletedtext'           => 'Преглед на изтрития текст и промените между изтрите версии',
'right-browsearchive'         => 'търсене на изтрити страници',
'right-undelete'              => 'възстановяване на страници',
'right-suppressrevision'      => 'преглед и възстановяване на версии, скрити от администраторите',
'right-suppressionlog'        => 'преглеждане на тайните дневници',
'right-block'                 => 'спиране на достъпа до редактиране',
'right-blockemail'            => 'блокиране на потребители да изпращат писма по е-поща',
'right-hideuser'              => 'блокиране и скриване на потребителско име',
'right-ipblock-exempt'        => 'пренебрегване на блокирания по IP blocks, автоматични блокирания и блокирани IP интервали',
'right-proxyunbannable'       => 'пренебрегване на автоматичното блокиране на проксита',
'right-unblockself'           => 'Самоотблокиране',
'right-protect'               => 'променяне на нивото на защита и редактиране на защитени страници',
'right-editprotected'         => 'редактиране на защитени страници (без каскадна защита)',
'right-editinterface'         => 'редактиране на интерфейса',
'right-editusercssjs'         => 'редактиране на CSS и JS файловете на други потребители',
'right-editusercss'           => 'редактиране на CSS файловете на други потребители',
'right-edituserjs'            => 'редактиране на JS файловете на други потребители',
'right-rollback'              => 'Бърза отмяна на промените, направени от последния потребител, редактирал дадена страница',
'right-markbotedits'          => 'отбелязване на възвърнатите редакции като редакции на ботове',
'right-noratelimit'           => 'Пренебрегване на всякакви ограничения',
'right-import'                => 'внасяне на страници от други уикита',
'right-importupload'          => 'внасяне на страници от качен файл',
'right-patrol'                => 'отбелязване на редакциите като проверени',
'right-autopatrol'            => 'автоматично отбелязване на редакции като проверени',
'right-patrolmarks'           => 'Показване на отбелязаните като патрулирани последни промени',
'right-unwatchedpages'        => 'преглеждане на списъка с ненаблюдаваните страници',
'right-trackback'             => 'изпращане на обратна следа',
'right-mergehistory'          => 'сливане на редакционни истории на страници',
'right-userrights'            => 'редактиране на потребителските права',
'right-userrights-interwiki'  => 'редактиране на потребителски права на потребители в други уикита',
'right-siteadmin'             => 'заключване и отключване на базата от данни',
'right-reset-passwords'       => 'Смяна на паролите на други потребители',
'right-override-export-depth' => 'Изнасяне на страници, включително свързаните с тях в дълбочина до пето ниво',
'right-sendemail'             => 'Изпращане на е-писма до другите потребители',

# User rights log
'rightslog'      => 'Дневник на потребителските права',
'rightslogtext'  => 'Това е дневник на промените на потребителски права.',
'rightslogentry' => 'промени потребителската група на $1 от $2 в $3',
'rightsnone'     => '(никакви)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'четене на страницата',
'action-edit'                 => 'редактиране на тази страница',
'action-createpage'           => 'създаване на страници',
'action-createtalk'           => 'създаване на дискусионни страници',
'action-createaccount'        => 'създаване на тази потребителска сметка',
'action-minoredit'            => 'отбелязване на редакцията като малка',
'action-move'                 => 'преместване на страницата',
'action-move-subpages'        => 'преместване на страницата и нейните подстраници',
'action-move-rootuserpages'   => 'премести главните потребителски страници',
'action-movefile'             => 'преместване на този файл',
'action-upload'               => 'качване на файла',
'action-reupload'             => 'съхранение на файл върху вече съществуващ',
'action-reupload-shared'      => 'предефиране на едноименен файл от общото мултимедийно хранилище',
'action-upload_by_url'        => 'качване на този файл от URL адрес',
'action-writeapi'             => 'запис на API',
'action-delete'               => 'изтриване на страницата',
'action-deleterevision'       => 'изтриване на тази версия',
'action-deletedhistory'       => 'преглеждане на изтритата история на тази страница',
'action-browsearchive'        => 'търсене на изтрити страници',
'action-undelete'             => 'възстановяване на тази страница',
'action-suppressrevision'     => 'преглеждане и възстановяване на тази скрита версия',
'action-suppressionlog'       => 'преглеждане на този поверителен дневник',
'action-block'                => 'блокиране на редакциите на този потребител',
'action-protect'              => 'променяне на нивото на защита на тази страница',
'action-import'               => 'внасяне на тази страница от друго уики',
'action-importupload'         => 'внасяне на тази страница от качен файл',
'action-patrol'               => 'отбелязване на чуждите редакции като проверени',
'action-autopatrol'           => 'отбелязване на собствените редакции като автоматично патрулирани',
'action-unwatchedpages'       => 'преглеждане на списъка с ненаблюдавани страници',
'action-trackback'            => 'оставяне на обратна следа',
'action-mergehistory'         => 'сливане на историята на тази страница',
'action-userrights'           => 'редактиране на всички потребителски права',
'action-userrights-interwiki' => 'редактиране на потребителските права на потребители от други уикита',
'action-siteadmin'            => 'заключване и отключване на базата от данни',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|промяна|промени}}',
'recentchanges'                     => 'Последни промени',
'recentchanges-legend'              => 'Опции на списъка с последни промени',
'recentchangestext'                 => "Проследяване на последните промени в {{SITENAME}}.

Легенда: '''тек''' = разлика на текущата версия,
'''ист''' = история на версиите",
'recentchanges-feed-description'    => 'Проследяване на последните промени в {{SITENAME}}.',
'recentchanges-label-legend'        => 'Легенда: $1.',
'recentchanges-legend-newpage'      => '$1 - нова страница',
'recentchanges-label-newpage'       => 'С тази редакция беше създадена нова страница',
'recentchanges-legend-minor'        => '$1 - малка промяна',
'recentchanges-label-minor'         => 'Това е малка промяна',
'recentchanges-legend-bot'          => '$1 - редакция от бот',
'recentchanges-label-bot'           => 'Тази редакция е извършена от робот',
'recentchanges-legend-unpatrolled'  => '$1 - непроверена редакция',
'recentchanges-label-unpatrolled'   => 'Тази редакция все още не е проверена',
'rcnote'                            => "{{PLURAL:$1|Показана е '''1''' промяна|Показани са последните '''$1''' промени}} през {{PLURAL:$2|последния ден|последните '''$2''' дни}}, към $5, $4.",
'rcnotefrom'                        => 'Дадени са промените от <strong>$2</strong> (до <strong>$1</strong> показани).',
'rclistfrom'                        => 'Показване на промени, като се започва от $1.',
'rcshowhideminor'                   => '$1 на малки промени',
'rcshowhidebots'                    => '$1 на ботове',
'rcshowhideliu'                     => '$1 на влезли потребители',
'rcshowhideanons'                   => '$1 на анонимни потребители',
'rcshowhidepatr'                    => '$1 на проверени редакции',
'rcshowhidemine'                    => '$1 на моите приноси',
'rclinks'                           => 'Показване на последните $1 промени за последните $2 дни<br />$3',
'diff'                              => 'разл',
'hist'                              => 'ист',
'hide'                              => 'Скриване',
'show'                              => 'Показване',
'minoreditletter'                   => 'м',
'newpageletter'                     => 'Н',
'boteditletter'                     => 'б',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|наблюдаващ потребител|наблюдаващи потребители}}]',
'rc_categories'                     => 'Само от категории (разделител „|“)',
'rc_categories_any'                 => 'Която и да е',
'newsectionsummary'                 => 'Нова тема /* $1 */',
'rc-enhanced-expand'                => 'Показване на детайли (изисква JavaScript)',
'rc-enhanced-hide'                  => 'Скриване на детайли',

# Recent changes linked
'recentchangeslinked'          => 'Свързани промени',
'recentchangeslinked-feed'     => 'Свързани промени',
'recentchangeslinked-toolbox'  => 'Свързани промени',
'recentchangeslinked-title'    => 'Промени, свързани с „$1“',
'recentchangeslinked-noresult' => 'Няма промени в свързаните страници за дадения период.',
'recentchangeslinked-summary'  => "Тук се показват последните промени на страниците, към които се препраща от дадена страница. При избиране на категория, се показват промените по страниците, влизащи в нея. ''Пример:'' Ако изберете страницата '''А''', която съдържа препратки към '''Б''' и '''В''', тогава ще можете да прегледате промените по '''Б''' и '''В'''.

Ако пък сложите отметка пред '''Обръщане на релацията''', ще можете да прегледате промените в обратна посока: ще се включат тези страници, които съдържат препратки към посочената страница.

Страниците от списъка ви за наблюдение се показват в '''получер'''.",
'recentchangeslinked-page'     => 'Име на страницата:',
'recentchangeslinked-to'       => 'Обръщане на релацията, така че да се показват промените на страниците, сочещи към избраната страница',

# Upload
'upload'                      => 'Качване',
'uploadbtn'                   => 'Качване',
'reuploaddesc'                => 'Връщане към формуляра за качване.',
'upload-tryagain'             => 'Съхраняване на промененото описание на файла',
'uploadnologin'               => 'Не сте влезли',
'uploadnologintext'           => 'Необходимо е да [[Special:UserLogin|влезете]], за да може да качвате файлове.',
'upload_directory_missing'    => 'Директорията за качване ($1) липсва и не може да бъде създадена на сървъра.',
'upload_directory_read_only'  => 'Сървърът няма достъп за писане в директорията за качване „$1“.',
'uploaderror'                 => 'Грешка при качване',
'upload-recreate-warning'     => "'''Внимание: Файл с това име вече е бил изтрит или преместен.'''

За повече информация можете да прегледате записите от дневниците на изтриванията и преместванията:",
'uploadtext'                  => "Формулярът по-долу служи за качване на файлове, които ще могат да се използват в страниците.
За преглеждане и търсене на вече качените файлове може да се използва [[Special:FileList|списъка с качени файлове]]. Качванията и изтриванията се записват в [[Special:Log/upload|дневника на качванията]] и [[Special:Log/delete|дневник на изтриванията]].

За включване на файл в страница, може да се използва една от следните форми: 
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>''' за използване пълната версия на файла
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|alt text]]</nowiki></tt>''' за определяне на широчина от 200 пиксела наляво от 'alt text' за описание
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>''' за директно свързване, без да се показва файла",
'upload-permitted'            => 'Разрешени файлови формати: $1.',
'upload-preferred'            => 'Предпочитани файлови формати: $1.',
'upload-prohibited'           => 'Непозволени файлови формати: $1.',
'uploadlog'                   => 'дневник на качванията',
'uploadlogpage'               => 'Дневник на качванията',
'uploadlogpagetext'           => 'Списък на последните качвания.',
'filename'                    => 'Име на файл',
'filedesc'                    => 'Описание',
'fileuploadsummary'           => 'Описание:',
'filereuploadsummary'         => 'Промени по файла:',
'filestatus'                  => 'Авторско право:',
'filesource'                  => 'Изходен код:',
'uploadedfiles'               => 'Качени файлове',
'ignorewarning'               => 'Съхраняване на файла въпреки предупреждението.',
'ignorewarnings'              => 'Пренебрегване на всякакви предупреждения',
'minlength1'                  => 'Имената на файловете трябва да съдържат поне един знак.',
'illegalfilename'             => 'Името на файла „$1“ съдържа знаци, които не са позволени в заглавия на страници. Преименувайте файла и се опитайте да го качите отново.',
'badfilename'                 => 'Файлът беше преименуван на „$1“.',
'filetype-mime-mismatch'      => 'Файловото разширение не отговаря на MIME типа.',
'filetype-badmime'            => 'Не е разрешено качването на файлове с MIME-тип „$1“.',
'filetype-bad-ie-mime'        => 'Този файл не може да бъде качен, защото Internet Explorer го разпознава като „$1“, който е отхвърлен и потенциално опасен файлов формат.',
'filetype-unwanted-type'      => "'''„.$1“''' е нежелан файлов формат. {{PLURAL:$3|Преопръчителният файлов формат е|Препоръчителните файлови формати са}} $2.",
'filetype-banned-type'        => "'''„.$1“''' не е позволен файлов формат. {{PLURAL:$3|Позволеният файлов формат е|Позволените файлови формати са}} $2.",
'filetype-missing'            => 'Файлът няма разширение (напр. „.jpg“).',
'empty-file'                  => 'Подаденият от вас файл беше празен.',
'file-too-large'              => 'Подаденият от вас файл беше твърде голям.',
'filename-tooshort'           => 'Името на файла е твърде кратко.',
'filetype-banned'             => 'Този тип файл е забранен.',
'verification-error'          => 'Файлът не премина процедурата по верификация.',
'illegal-filename'            => 'Непозволено файлово име.',
'overwrite'                   => 'Презаписването на съществуващ файл е забранено.',
'unknown-error'               => 'Възникна неизвестна грешка.',
'tmp-create-error'            => 'Грешка при създаването на временен файл.',
'tmp-write-error'             => 'Грешка при записа на временен файл.',
'large-file'                  => 'Не се препоръчва файловете да се по-големи от $1; този файл е $2.',
'largefileserver'             => 'Файлът е по-голям от допустимия от сървъра размер.',
'emptyfile'                   => 'Каченият от вас файл е празен. Това може да е предизвикано от грешка в името на файла. Уверете се дали наистина желаете да го качите.',
'fileexists'                  => "Вече съществува файл с това име! Прегледайте '''<tt>[[:$1]]</tt>''', ако не сте сигурни, че желаете да го промените.
[[$1|thumb]]",
'filepageexists'              => "Описателната страница за този файл вече е създадена на '''<tt>[[:$1]]</tt>''', въпреки че файл с това име в момента не съществува. Въведеното от вас резюме няма да се появи на описателната страница. За целта, страницата трябва да бъде редактирана ръчно.
[[$1|thumb]]",
'fileexists-extension'        => "Съществува файл със сходно име: [[$2|thumb]]
* Име на файла за качване: '''<tt>[[:$1]]</tt>'''
* Име на съществуващия файл: '''<tt>[[:$2]]</tt>'''
Моля, изберете друго име на файла за качване.",
'fileexists-thumbnail-yes'    => "Изглежда, че файлът е картинка с намален размер ''(миникартинка)''. [[$1|thumb]]
Проверете файла '''<tt>[[:$1]]</tt>'''.
Ако съществуващият файл представлява оригиналната версия на картинката, няма нужда да се качва неин умален вариант.",
'file-thumbnail-no'           => "Файловото име започва с '''<tt>$1</tt>'''. Изглежда, че е картинка с намален размер ''(миникартинка)''.
Ако разполагате с версия в пълна разделителна способност, качете нея. В противен случай сменете името на този файл.",
'fileexists-forbidden'        => 'Вече съществува файл с това име, който не може да бъде презаписан!
Ако желаете да качите вашия файл, върнете се и го качете с ново име. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'В споделеното хранилище за файлове вече съществува файл с това име.
Ако все още желаете да качите вашия файл, върнете се и качете файла с ново име. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Този файл се повтаря със {{PLURAL:$1|следния файл|следните файлове}}:',
'file-deleted-duplicate'      => 'Идентичен с този файл ([[$1]]) вече е бил изтриван. Историята на изтриването на файла следва да се провери, преди да се пристъпи към повторното му качване.',
'uploadwarning'               => 'Предупреждение при качване',
'uploadwarning-text'          => 'Необходимо е да промените описанието на файла по-долу и да опитате отново.',
'savefile'                    => 'Съхраняване на файл',
'uploadedimage'               => 'качи „[[$1]]“',
'overwroteimage'              => 'качи нова версия на „[[$1]]“',
'uploaddisabled'              => 'Качванията са забранени.',
'copyuploaddisabled'          => 'Спряно е качването на файлове чрез URL.',
'uploadfromurl-queued'        => 'Каченият от вас файл беше добавен в опашката.',
'uploaddisabledtext'          => 'Качването на файлове е забранено.',
'php-uploaddisabledtext'      => 'Качванията на файлове са спрени през PHP. Проверете настройката file_uploads.',
'uploadscripted'              => 'Файлът съдържа HTML или скриптов код, който може да бъде погрешно  интерпретиран от браузъра.',
'uploadvirus'                 => 'Файлът съдържа вирус! Подробности: $1',
'upload-source'               => 'Изходен файл',
'sourcefilename'              => 'Първоначално име:',
'sourceurl'                   => 'Изходен адрес:',
'destfilename'                => 'Целево име:',
'upload-maxfilesize'          => 'Максимален допустим размер на файла: $1',
'upload-description'          => 'Описание на файла',
'upload-options'              => 'Настройки за качване',
'watchthisupload'             => 'Наблюдаване на файла',
'filewasdeleted'              => 'Файл в този име е съществувал преди време, но е бил изтрит. Проверете $1 преди да го качите отново.',
'upload-wasdeleted'           => "'''Внимание: Качвате файл, който вече е бил изтрит.'''

Преценете дали е удачно да продължите с качването на файла. За ваше удобство, ето записа за него в дневника на изтриванията:",
'filename-bad-prefix'         => "Името на файла, който качвате, започва с '''„$1“''', което е неописателно име, типично задавано по автоматичен начин от цифровите камери или апарати. Изберете по-описателно име на файла.",
'upload-success-subj'         => 'Качването беше успешно',

'upload-proto-error'        => 'Неправилен протокол',
'upload-proto-error-text'   => 'Изисква се адрес започващ с <code>http://</code> или <code>ftp://</code>.',
'upload-file-error'         => 'Вътрешна грешка',
'upload-file-error-text'    => 'Вътрешна грешка при опит да се създаде временен файл на сървъра. Обърнете се към [[Special:ListUsers/sysop|администратор]].',
'upload-misc-error'         => 'Неизвестна грешка при качване',
'upload-misc-error-text'    => 'Неизвестна грешка при качване. Убедете се, че адресът е верен и опитайте отново. Ако отново имате проблем, обърнете се към [[Special:ListUsers/sysop|администратор]].',
'upload-too-many-redirects' => 'Адресът съдържа твърде много пренасочвания',
'upload-unknown-size'       => 'Неизвестен размер',
'upload-http-error'         => 'Възникна HTTP грешка: $1',

# img_auth script messages
'img-auth-accessdenied' => 'Достъпът е отказан',
'img-auth-nopathinfo'   => 'Липсва PATH_INFO.
Вашият сървър не е конфигуриран да предава тази информация.
Той може да е базиран на CGI и да не може да поддържа img_auth.
Вижте http://www.mediawiki.org/wiki/Manual:Image_Authorization за повече информация.',
'img-auth-notindir'     => 'Търсеният път не е в настроената директория за качвания.',
'img-auth-badtitle'     => 'Грешка при конструирането на валидно заглавие от "$1".',
'img-auth-nologinnWL'   => 'Не сте влезли в системата и „$1“ не е в белия списък.',
'img-auth-nofile'       => 'Файлът „$1“ не съществува.',
'img-auth-isdir'        => 'Опитвате се да осъществите достъп до директорията „$1“.
Разрешен е само достъп до файловете.',
'img-auth-streaming'    => 'Излъчване "$1"',
'img-auth-public'       => 'Функцията img_auth.php е да извлича файлове от частни уикита.
Това уики е конфигурирано като публично.
С цел оптимална сигурност, функцията img_auth.php е деактивирана.',
'img-auth-noread'       => 'Потребителят няма достъп за четене на „$1“.',

# HTTP errors
'http-invalid-url'      => 'Невалиден адрес: $1',
'http-invalid-scheme'   => 'Не се поддържат URL адреси с префикс "$1".',
'http-request-error'    => 'HTTP заявката пропадна поради неизвестна грешка.',
'http-read-error'       => 'HTTP грешка при четене.',
'http-timed-out'        => 'Пресрочено време за HTTP заявка.',
'http-curl-error'       => 'Грешка при извличането на URL: $1',
'http-host-unreachable' => 'Недостъпен URL.',
'http-bad-status'       => 'Настъпи проблем по време на HTTP заявката: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Не е възможно достигането на указания URL адрес',
'upload-curl-error6-text'  => 'Търсеният адрес не може да бъде достигнат. Проверете дали е написан вярно.',
'upload-curl-error28'      => 'Времето за качване изтече',
'upload-curl-error28-text' => 'Сайтът не отговаря твърде дълго. Убедете се, че сайтът работи, изчакайте малко и опитайте отново. В краен случай опитайте през по-ненатоварено време.',

'license'            => 'Лицензиране:',
'license-header'     => 'Лицензиране',
'nolicense'          => 'Нищо не е избрано',
'license-nopreview'  => '(Не е наличен предварителен преглед)',
'upload_source_url'  => ' (правилен, публично достъпен интернет-адрес)',
'upload_source_file' => ' (файл на вашия компютър)',

# Special:ListFiles
'listfiles-summary'     => 'Тази специална страница показва всички качени файлове.
По подразбиране последно качените файлове се показват най-високо в списъка.
Сортирането се променя с щракване в заглавна клетка на колоната.',
'listfiles_search_for'  => 'Търсене по име на файла:',
'imgfile'               => 'файл',
'listfiles'             => 'Списък на файловете',
'listfiles_date'        => 'Дата',
'listfiles_name'        => 'Име на файла',
'listfiles_user'        => 'Потребител',
'listfiles_size'        => 'Размер',
'listfiles_description' => 'Описание',
'listfiles_count'       => 'Версии',

# File description page
'file-anchor-link'          => 'Файл',
'filehist'                  => 'История на файла',
'filehist-help'             => 'Избирането на дата/час ще покаже как е изглеждал файлът към онзи момент.',
'filehist-deleteall'        => 'изтриване на всички',
'filehist-deleteone'        => 'изтриване',
'filehist-revert'           => 'връщане',
'filehist-current'          => 'текуща',
'filehist-datetime'         => 'Дата/Час',
'filehist-thumb'            => 'Миникартинка',
'filehist-thumbtext'        => 'Миникартинка на версията към $1',
'filehist-nothumb'          => 'Няма миникартинка',
'filehist-user'             => 'Потребител',
'filehist-dimensions'       => 'Размер',
'filehist-filesize'         => 'Размер на файла',
'filehist-comment'          => 'Коментар',
'filehist-missing'          => 'Липсващ файл',
'imagelinks'                => 'Препратки към файла',
'linkstoimage'              => '{{PLURAL:$1|Следната страница сочи|Следните $1 страници сочат}} към файла:',
'linkstoimage-more'         => 'Към този файл {{PLURAL:$1|препраща|препращат}} повече от $1 {{PLURAL:$1|страница|страници}}.
Списъкът по-долу показва само {{PLURAL:$1|първата страница, която препраща|първите $1 страници, които препращат}} към файла.
На разположение е и [[Special:WhatLinksHere/$2|пълният списък]].',
'nolinkstoimage'            => 'Няма страници, сочещи към файла.',
'morelinkstoimage'          => 'Можете да видите [[Special:WhatLinksHere/$1|още препратки]] към този файл.',
'redirectstofile'           => '{{PLURAL:$1|Следният файл пренасочва|Следните $1 файла пренасочват}} към този файл:',
'duplicatesoffile'          => '{{PLURAL:$1|Следният файл се повтаря|Следните $1 файла се повтарят}} с този файл ([[Special:FileDuplicateSearch/$2|повече подробности]]):',
'sharedupload'              => 'Този файл е от $1 и може да бъде използван от други проекти.',
'sharedupload-desc-there'   => 'Този файл е от $1 и може да се използва от други проекти.
За повече информация вижте [$2 описателната му страница].',
'sharedupload-desc-here'    => 'Този файл е от $1 и може да се използва от други проекти.
Следва информация за файла, достъпна през [$2 оригиналната му описателна страница].',
'filepage-nofile'           => 'Не съществува файл с това име.',
'filepage-nofile-link'      => 'Не съществува файл с това име, но можете [$1 да го качите].',
'uploadnewversion-linktext' => 'Качване на нова версия на файла',
'shared-repo-from'          => 'от $1',
'shared-repo'               => 'споделено хранилище',

# File reversion
'filerevert'                => 'Възвръщане на $1',
'filerevert-legend'         => 'Възвръщане на файла',
'filerevert-intro'          => "Възвръщане на '''[[Media:$1|$1]]''' към [$4 версията от $3, $2].",
'filerevert-comment'        => 'Причина:',
'filerevert-defaultcomment' => 'Възвръщане към версия от $2, $1',
'filerevert-submit'         => 'Възвръщане',
'filerevert-success'        => "Файлът '''[[Media:$1|$1]]''' беше възвърнат към [$4 версия от $3, $2].",
'filerevert-badversion'     => 'Не съществува предишна локална версия на файла със зададения времеви отпечатък.',

# File deletion
'filedelete'                  => 'Изтриване на $1',
'filedelete-legend'           => 'Изтриване на файл',
'filedelete-intro'            => "На път сте да изтриете '''[[Media:$1|$1]]''' заедно с цялата му редакционна история.",
'filedelete-intro-old'        => "Изтривате версията на '''[[Media:$1|$1]]''' към [$4 $3, $2].",
'filedelete-comment'          => 'Причина:',
'filedelete-submit'           => 'Изтриване',
'filedelete-success'          => "Файлът '''$1''' беше изтрит.",
'filedelete-success-old'      => "Версията на '''[[Media:$1|$1]]''' към $3, $2 е била изтрита.",
'filedelete-nofile'           => "Файлът '''$1''' не съществува.",
'filedelete-nofile-old'       => "Не съществува архивна версия на '''$1''' с указаните параметри.",
'filedelete-otherreason'      => 'Друга/допълнителна причина:',
'filedelete-reason-otherlist' => 'Друга причина',
'filedelete-reason-dropdown'  => '*Общи причини за изтриване
** Нарушение на авторските права
** Файлът се повтаря',
'filedelete-edit-reasonlist'  => 'Редактиране на причините за изтриване',
'filedelete-maintenance'      => 'Поради поддръжка на сайта, изтриването и възстановяването на файлове е временно ограничено.',

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
'unusedtemplatestext' => 'Тази страница съдържа списък на страниците в именно пространство {{ns:template}}, които не са включени в друга страница. Проверявайте за препратки към отделните шаблони преди да ги изтриете или предложите за изтриване.',
'unusedtemplateswlh'  => 'други препратки',

# Random page
'randompage'         => 'Случайна страница',
'randompage-nopages' => 'В {{PLURAL:$2|следното именно пространство|следните именни пространства}} няма страници: $1.',

# Random redirect
'randomredirect'         => 'Случайно пренасочване',
'randomredirect-nopages' => 'В именно пространство „$1“ няма пренасочвания.',

# Statistics
'statistics'                   => 'Статистика',
'statistics-header-pages'      => 'Статистики за страницата',
'statistics-header-edits'      => 'Статистики за редакциите',
'statistics-header-views'      => 'Преглеждане на статистиките',
'statistics-header-users'      => 'Потребители',
'statistics-header-hooks'      => 'Други статистики',
'statistics-articles'          => 'Съдържателни страници',
'statistics-pages'             => 'Страници',
'statistics-pages-desc'        => 'Всички страници в уикито, включително дискусионни, пренасочващи страници и т.н.',
'statistics-files'             => 'Качени файлове',
'statistics-edits'             => 'Брой редакции по страници от началото на {{SITENAME}}',
'statistics-edits-average'     => 'Средно редакции на страница',
'statistics-views-total'       => 'Общо прегледи',
'statistics-views-peredit'     => 'Прегледи на редакция',
'statistics-users'             => 'Регистрирани [[Special:ListUsers|потребители]]',
'statistics-users-active'      => 'Активни потребители',
'statistics-users-active-desc' => 'Потребители, направили редакция през {{PLURAL:$1|последния ден|последните $1 дни}}',
'statistics-mostpopular'       => 'Най-преглеждани страници',

'disambiguations'      => 'Пояснителни страници',
'disambiguationspage'  => 'Template:Пояснение',
'disambiguations-text' => "Следните страници сочат към '''пояснителна страница''', вместо към истинската тематична страница.<br />Една страница се смята за пояснителна, ако ползва шаблон, към който се препраща от [[MediaWiki:Disambiguationspage]]",

'doubleredirects'            => 'Двойни пренасочвания',
'doubleredirectstext'        => 'Тази страница съдържа списък със страници, които пренасочват към друга пренасочваща страница.
Всеки ред съдържа препратки към първото и второто пренасочване, както и целта на второто пренасочване, която обикновено е „истинската“ целева страница, към която първото пренасочване би трябвало да сочи.
<del>Задрасканите</del> записи са коригирани.',
'double-redirect-fixed-move' => 'Оправяне на двойно пренасочване след преместването на [[$1]] като [[$2]]',
'double-redirect-fixer'      => 'Redirect fixer',

'brokenredirects'        => 'Невалидни пренасочвания',
'brokenredirectstext'    => 'Следните пренасочващи страници сочат към несъществуващи страници:',
'brokenredirects-edit'   => 'редактиране',
'brokenredirects-delete' => 'изтриване',

'withoutinterwiki'         => 'Страници без междуезикови препратки',
'withoutinterwiki-summary' => 'Следните страници не препращат към версии на други езици:',
'withoutinterwiki-legend'  => 'Представка',
'withoutinterwiki-submit'  => 'Показване',

'fewestrevisions' => 'Страници с най-малко версии',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|байт|байта}}',
'ncategories'             => '$1 {{PLURAL:$1|категория|категории}}',
'nlinks'                  => '$1 {{PLURAL:$1|препратка|препратки}}',
'nmembers'                => '$1 {{PLURAL:$1|член|члена}}',
'nrevisions'              => '$1 {{PLURAL:$1|версия|версии}}',
'nviews'                  => '$1 {{PLURAL:$1|преглед|прегледа}}',
'specialpage-empty'       => 'Страницата е празна.',
'lonelypages'             => 'Страници сираци',
'lonelypagestext'         => 'Към следващите страници няма препратки или не са включени в други страници в {{SITENAME}}.',
'uncategorizedpages'      => 'Некатегоризирани страници',
'uncategorizedcategories' => 'Некатегоризирани категории',
'uncategorizedimages'     => 'Некатегоризирани картинки',
'uncategorizedtemplates'  => 'Некатегоризирани шаблони',
'unusedcategories'        => 'Неизползвани категории',
'unusedimages'            => 'Неизползвани файлове',
'popularpages'            => 'Най-посещавани страници',
'wantedcategories'        => 'Желани категории',
'wantedpages'             => 'Желани страници',
'wantedpages-badtitle'    => 'Невалидно заглавие в резултатното множество: $1',
'wantedfiles'             => 'Желани файлове',
'wantedtemplates'         => 'Желани шаблони',
'mostlinked'              => 'Най-препращани страници',
'mostlinkedcategories'    => 'Най-препращани категории',
'mostlinkedtemplates'     => 'Най-препращани шаблони',
'mostcategories'          => 'Страници с най-много категории',
'mostimages'              => 'Най-препращани картинки',
'mostrevisions'           => 'Страници с най-много версии',
'prefixindex'             => 'Всички страници с представка',
'shortpages'              => 'Кратки страници',
'longpages'               => 'Дълги страници',
'deadendpages'            => 'Задънени страници',
'deadendpagestext'        => 'Следните страници нямат препратки към други страници от {{SITENAME}}.',
'protectedpages'          => 'Защитени страници',
'protectedpages-indef'    => 'Само безсрочни защити',
'protectedpages-cascade'  => 'Само каскадни защити',
'protectedpagestext'      => 'Следните страници са защитени против редактиране или преместване',
'protectedpagesempty'     => 'В момента няма защитени страници с тези параметри.',
'protectedtitles'         => 'Защитени заглавия',
'protectedtitlestext'     => 'Следните заглавия са защитени срещу създаване',
'protectedtitlesempty'    => 'В момента няма заглавия, защитени с тези параметри.',
'listusers'               => 'Списък на потребителите',
'listusers-editsonly'     => 'Показване само на потребители с редакции',
'listusers-creationsort'  => 'Сортиране по дата на създаване',
'usereditcount'           => '$1 {{PLURAL:$1|редакция|редакции}}',
'usercreated'             => 'Създаден на $1 в $2',
'newpages'                => 'Нови страници',
'newpages-username'       => 'Потребител:',
'ancientpages'            => 'Стари страници',
'move'                    => 'Преместване',
'movethispage'            => 'Преместване на страницата',
'unusedimagestext'        => 'Следните файлове съществуват, но не са вградени в нито една страница.
Обърнете внимание, че други сайтове могат да сочат към файла чрез пряк адрес и въпреки тази му употреба, той може да се намира в списъка.',
'unusedcategoriestext'    => 'Следните категории съществуват, но никоя страница или категория не ги използва.',
'notargettitle'           => 'Няма цел',
'notargettext'            => 'Не указахте целева страница или потребител, върху която/който да се изпълни действието.',
'nopagetitle'             => 'Няма такава целева страница',
'nopagetext'              => 'Посочената целева страница не съществува.',
'pager-newer-n'           => '{{PLURAL:$1|по-нова 1|по-нови $1}}',
'pager-older-n'           => '{{PLURAL:$1|по-стара 1|по-стари $1}}',
'suppress'                => 'Премахване от публичния архив',

# Book sources
'booksources'               => 'Източници на книги',
'booksources-search-legend' => 'Търсене на информация за книга',
'booksources-go'            => 'Отваряне',
'booksources-text'          => 'По-долу е списъкът от връзки към други сайтове, продаващи нови и използвани книги или имащи повече информация за книгите, които търсите:',
'booksources-invalid-isbn'  => 'Предоставеният ISBN изглежда е невалиден; проверете за грешки и копирайте от оригиналния източник.',

# Special:Log
'specialloguserlabel'  => 'Потребител:',
'speciallogtitlelabel' => 'Заглавие:',
'log'                  => 'Дневници',
'all-logs-page'        => 'Всички публични дневници',
'alllogstext'          => 'Смесено показване на записи от всички налични дневници в {{SITENAME}}.
Можете да ограничите прегледа, като изберете вид на дневника, потребителско име или определена страница.',
'logempty'             => 'Дневникът не съдържа записи, отговарящи на избрания критерий.',
'log-title-wildcard'   => 'Търсене на заглавия, започващи със',

# Special:AllPages
'allpages'          => 'Всички страници',
'alphaindexline'    => 'от $1 до $2',
'nextpage'          => 'Следваща страница ($1)',
'prevpage'          => 'Предходна страница ($1)',
'allpagesfrom'      => 'Показване на страниците, като се започва от:',
'allpagesto'        => 'Показване на страници, завършващи на:',
'allarticles'       => 'Всички страници',
'allinnamespace'    => 'Всички страници (именно пространство $1)',
'allnotinnamespace' => 'Всички страници (без именно пространство $1)',
'allpagesprev'      => 'Предишна',
'allpagesnext'      => 'Следваща',
'allpagessubmit'    => 'Зареждане',
'allpagesprefix'    => 'Показване на страници, започващи със:',
'allpagesbadtitle'  => 'Зададеното име е невалидно. Възможно е да съдържа междуезикова или междупроектна представка или пък знаци, които не могат да се използват в заглавия.',
'allpages-bad-ns'   => 'В {{SITENAME}} не съществува именно пространство „$1“.',

# Special:Categories
'categories'                    => 'Категории',
'categoriespagetext'            => '{{PLURAL:$1|Следната категория съдържа|Следните категории съдържат}} страници или медийни файлове.
[[Special:UnusedCategories|Неизползваните категории]] не са показани тук.
Вижте също списъка с [[Special:WantedCategories|желани категории]].',
'categoriesfrom'                => 'Показване на категориите, като се започне от:',
'special-categories-sort-count' => 'сортиране по брой',
'special-categories-sort-abc'   => 'сортиране по азбучен ред',

# Special:DeletedContributions
'deletedcontributions'             => 'Изтрити приноси на потребител',
'deletedcontributions-title'       => 'Изтрити приноси на потребител',
'sp-deletedcontributions-contribs' => 'приноси',

# Special:LinkSearch
'linksearch'       => 'Външни препратки',
'linksearch-pat'   => 'Търсене по:',
'linksearch-ns'    => 'Именно пространство:',
'linksearch-ok'    => 'Търсене',
'linksearch-text'  => 'Възможна е употребата на заместващи знаци като: „*.wikipedia.org“.<br />Поддържани протоколи: <tt>$1</tt>',
'linksearch-line'  => '$1 с препратка от $2',
'linksearch-error' => 'Заместващите знаци могат да стоят само в началото на името на хоста.',

# Special:ListUsers
'listusersfrom'      => 'Показване на потребителите, започвайки от:',
'listusers-submit'   => 'Показване',
'listusers-noresult' => 'Няма намерени потребители.',
'listusers-blocked'  => '(блокиран)',

# Special:ActiveUsers
'activeusers'            => 'Списък на активните потребители',
'activeusers-intro'      => 'Това е списък на потребителите, които са демонстрирали някаква активност през {{PLURAL:$1|последния|последните}} $1 {{PLURAL:$1|ден|дни}}.',
'activeusers-count'      => '$1 {{PLURAL:$1|редакция|редакции}} за {{PLURAL:$3|последния ден|последните $3 дни}}',
'activeusers-from'       => 'Показване на потребителите, започвайки от:',
'activeusers-hidebots'   => 'Скриване на ботовете',
'activeusers-hidesysops' => 'Скриване на администраторите',
'activeusers-noresult'   => 'Няма намерени потребители.',

# Special:Log/newusers
'newuserlogpage'              => 'Дневник на регистрациите',
'newuserlogpagetext'          => 'В този дневник се записват регистрациите на потребители.',
'newuserlog-byemail'          => 'паролата е изпратена по е-поща',
'newuserlog-create-entry'     => 'Нов потребител',
'newuserlog-create2-entry'    => 'създаде новата сметка $1',
'newuserlog-autocreate-entry' => 'Автоматично създадена сметка',

# Special:ListGroupRights
'listgrouprights'                      => 'Права по потребителски групи',
'listgrouprights-summary'              => 'По-долу на тази страница е показан списък на групите потребители в това уики с асоциираните им права за достъп. Допълнителна информация за отделните права може да бъде намерена [[{{MediaWiki:Listgrouprights-helppage}}|тук]].',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Поверено право</span>
* <span class="listgrouprights-revoked">Отнето право</span>',
'listgrouprights-group'                => 'Група',
'listgrouprights-rights'               => 'Права',
'listgrouprights-helppage'             => 'Help:Права на групите',
'listgrouprights-members'              => '(списък на членовете)',
'listgrouprights-addgroup'             => 'Може да добавя {{PLURAL:$2|група|групи}}: $1',
'listgrouprights-removegroup'          => 'Може да премахва {{PLURAL:$2|група|групи}}: $1',
'listgrouprights-addgroup-all'         => 'Може да добавя всички групи',
'listgrouprights-removegroup-all'      => 'Може да премахва всички групи',
'listgrouprights-addgroup-self'        => 'Може да добавя {{PLURAL:$2|група|групи}} към собствената сметка: $1',
'listgrouprights-removegroup-self'     => 'Може да премахва {{PLURAL:$2|група|групи}} от собствената си сметка: $1',
'listgrouprights-addgroup-self-all'    => 'Може да добавя всички групи към своята сметка',
'listgrouprights-removegroup-self-all' => 'Може да премахва всички групи от собствената сметка',

# E-mail user
'mailnologin'          => 'Няма електронна поща',
'mailnologintext'      => 'Необходимо е да [[Special:UserLogin|влезете]] и да посочите валидна електронна поща в [[Special:Preferences|настройките]] си, за да може да пращате писма на други потребители.',
'emailuser'            => 'Писмо до потребителя',
'emailpage'            => 'Пращане писмо на потребител',
'emailpagetext'        => 'Можете да използвате формуляра по-долу, за да изпратите електронно писмо на този потребител.
Адресът, който се въвели в [[Special:Preferences|настройките си]], ще се появи в полето „От“ на писмото, така че получателят ще е в състояние да ви отговори.',
'usermailererror'      => 'Пощенският обект даде грешка:',
'defemailsubject'      => 'Писмо от {{SITENAME}}',
'usermaildisabled'     => 'Потребителят не е разрешил да получава електронна поща',
'usermaildisabledtext' => 'Не можете да изпращате електронна поща на други потребители от това уики',
'noemailtitle'         => 'Няма електронна поща',
'noemailtext'          => 'Този потребител не е посочил валиден адрес за електронна поща.',
'nowikiemailtitle'     => 'Непозволена електронна поща',
'nowikiemailtext'      => 'Този потребител е избрал да не получава електронна поща от други потребители.',
'email-legend'         => 'Изпращане на електронно писмо до друг потребител на {{SITENAME}}',
'emailfrom'            => 'От:',
'emailto'              => 'До:',
'emailsubject'         => 'Относно:',
'emailmessage'         => 'Съобщение:',
'emailsend'            => 'Изпращане',
'emailccme'            => 'Изпращане на копие на писмото до автора.',
'emailccsubject'       => 'Копие на писмото ви до $1: $2',
'emailsent'            => 'Писмото е изпратено',
'emailsenttext'        => 'Писмото ви беше изпратено.',
'emailuserfooter'      => 'Това писмо беше изпратено от $1 на $2 чрез функцията „Изпращане на писмо до потребителя“ на {{SITENAME}}.',

# Watchlist
'watchlist'            => 'Моят списък за наблюдение',
'mywatchlist'          => 'Моят списък за наблюдение',
'nowatchlist'          => 'Списъкът ви за наблюдение е празен.',
'watchlistanontext'    => 'За преглеждане и редактиране на списъка за наблюдение се изисква $1 в системата.',
'watchnologin'         => 'Не сте влезли',
'watchnologintext'     => 'Необходимо е да [[Special:UserLogin|влезете]], за да може да променяте списъка си за наблюдение.',
'addedwatch'           => 'Добавено в списъка за наблюдение',
'addedwatchtext'       => "Страницата „'''[[:$1]]'''“ беше добавена към [[Special:Watchlist|списъка ви за наблюдение]].
Нейните бъдещи промени, както и на съответната й дискусионна страница, ще се описват там, а тя ще се появява в '''получер''' в [[Special:RecentChanges|списъка на последните промени]], което ще направи по-лесно избирането й.",
'removedwatch'         => 'Премахнато от списъка за наблюдение',
'removedwatchtext'     => 'Страницата „[[:$1]]“ беше премахната от [[Special:Watchlist|списъка ви за наблюдение]].',
'watch'                => 'Наблюдение',
'watchthispage'        => 'Наблюдаване на страницата',
'unwatch'              => 'Спиране на наблюдение',
'unwatchthispage'      => 'Спиране на наблюдение',
'notanarticle'         => 'Не е страница',
'notvisiblerev'        => 'Версията беше изтрита',
'watchnochange'        => 'Никоя от наблюдаваните страници не е била редактирана в показаното време.',
'watchlist-details'    => '{{PLURAL:$1|Една наблюдавана страница|$1 наблюдавани страници}} от списъка ви за наблюдение (без беседи).',
'wlheader-enotif'      => '* Известяването по електронна поща е включено.',
'wlheader-showupdated' => "* Страниците, които са били променени след последния път, когато сте ги посетили, са показани с '''получер''' шрифт.",
'watchmethod-recent'   => 'проверка на последните редакции за наблюдавани страници',
'watchmethod-list'     => 'проверка на наблюдаваните страници за скорошни редакции',
'watchlistcontains'    => 'Списъкът ви за наблюдение съдържа {{PLURAL:$1|една страница|$1 страници}}.',
'iteminvalidname'      => 'Проблем с „$1“, грешно име…',
'wlnote'               => "{{PLURAL:$1|Показана е последната промяна|Показани са последните '''$1''' промени}} през {{PLURAL:$2|последния час|последните '''$2''' часа}}.",
'wlshowlast'           => 'Показване на последните $1 часа $2 дни $3',
'watchlist-options'    => 'Опции на списъка за наблюдение',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Наблюдение…',
'unwatching' => 'Спиране на наблюдение…',

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

Страницата $PAGETITLE в {{SITENAME}} е била $CHANGEDORCREATED на $PAGEEDITDATE от $PAGEEDITOR. За текущата версия на страницата вижте $PAGETITLE_URL.

$NEWPAGE

Резюме на редакцията: $PAGESUMMARY $PAGEMINOREDIT

Връзка с редактора:
* електронна поща: $PAGEEDITOR_EMAIL
* уики страница: $PAGEEDITOR_WIKI

Няма да получавате други известия за последващи промени, докато не посетите страницата.
Можете да актуализиране настройките си за този вид известия за всички страници от вашия списък за наблюдение.

             Системата за известяване на {{SITENAME}}

--
За да промените настройките на списъка си за наблюдение, посетете
{{fullurl:{{#special:Watchlist}}/edit}}

За да изтриете страницата от списъка си за наблюдение, посетете
$UNWATCHURL

За обратна връзка и помощ:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Изтриване',
'confirm'                => 'Потвърждаване',
'excontent'              => 'съдържанието беше: „$1“',
'excontentauthor'        => 'съдържанието беше: „$1“ (като единственият автор беше [[Special:Contributions/$2|$2]])',
'exbeforeblank'          => 'премахнато преди това съдържание: „$1“',
'exblank'                => 'страницата беше празна',
'delete-confirm'         => 'Изтриване на „$1“',
'delete-legend'          => 'Изтриване',
'historywarning'         => "'''Внимание:''' Страницата, която възнамерявате да изтриете, има история с приблизително $1 {{PLURAL:$1|редакция|редакции}}:",
'confirmdeletetext'      => 'На път сте безвъзвратно да изтриете страница или файл, заедно с цялата прилежаща редакционна история, от базата от данни.
Потвърдете, че искате това, разбирате последствията и правите това в съответствие с [[{{MediaWiki:Policy-url}}|линията на поведение]].',
'actioncomplete'         => 'Действието беше изпълнено',
'actionfailed'           => 'Действието не сполучи',
'deletedtext'            => 'Страницата „<nowiki>$1</nowiki>“ беше изтрита. Вижте $2 за запис на последните изтривания.',
'deletedarticle'         => 'изтри „[[$1]]“',
'suppressedarticle'      => 'премахна „[[$1]]“',
'dellogpage'             => 'Дневник на изтриванията',
'dellogpagetext'         => 'Списък на последните изтривания.',
'deletionlog'            => 'дневник на изтриванията',
'reverted'               => 'Възвръщане към предишна версия',
'deletecomment'          => 'Причина:',
'deleteotherreason'      => 'Друга/допълнителна причина:',
'deletereasonotherlist'  => 'Друга причина',
'deletereason-dropdown'  => '*Стандартни причини за изтриване
** По молба на автора
** Нарушение на авторски права
** Вандализъм',
'delete-edit-reasonlist' => 'Редактиране на причините за изтриване',
'delete-toobig'          => 'Тази страница има голяма редакционна история с над $1 {{PLURAL:$1|версия|версии}}. Изтриването на такива страници е ограничено, за да се предотвратят евентуални поражения на {{SITENAME}}.',
'delete-warning-toobig'  => 'Тази страница има голяма редакционна история с над $1 {{PLURAL:$1|версия|версии}}. Възможно е изтриването да наруши някои операции в базата данни на {{SITENAME}}; необходимо е особено внимание при продължаване на действието.',

# Rollback
'rollback'          => 'Отмяна на промените',
'rollback_short'    => 'Отмяна',
'rollbacklink'      => 'отмяна',
'rollbackfailed'    => 'Отмяната не сполучи',
'cantrollback'      => 'Не може да се извърши отмяна на редакциите. Последният редактор е и единствен автор на страницата.',
'alreadyrolled'     => 'Редакцията на [[:$1]], направена от [[User:$2|$2]] ([[User talk:$2|Беседа]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]), не може да бъде отменена. Някой друг вече е редактирал страницата или е отменил промените.

Последната редакция е на [[User:$3|$3]] ([[User talk:$3|Беседа]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "Резюмето на редакцията беше: „''$1''“.",
'revertpage'        => 'Премахване на [[Special:Contributions/$2|редакции на $2]] ([[User talk:$2|беседа]]); възвръщане към последната версия на [[User:$1|$1]]',
'revertpage-nouser' => 'Премахнати редакции на (отстранено потребителско име) и връщане към последната версия на [[User:$1|$1]]',
'rollback-success'  => 'Отменени редакции на $1; възвръщане към последната версия на $2.',

# Edit tokens
'sessionfailure-title' => 'Прекъсната сесия',
'sessionfailure'       => 'Изглежда има проблем със сесията ви; действието беше отказано като предпазна мярка срещу крадене на сесията. Натиснете бутона за връщане на браузъра, презаредете страницата, от която сте дошли, и опитайте отново.',

# Protect
'protectlogpage'              => 'Дневник на защитата',
'protectlogtext'              => 'Списък на защитите и техните сваляния за страницата.
Можете да прегледате и [[Special:ProtectedPages|списъка на текущо защитените страници]].',
'protectedarticle'            => 'защити „[[$1]]“',
'modifiedarticleprotection'   => 'смени нивото на защита на „[[$1]]“',
'unprotectedarticle'          => 'свали защитата на „[[$1]]“',
'movedarticleprotection'      => 'преместване на настройките за защита от „[[$2]]“ на „[[$1]]“',
'protect-title'               => 'Смяна на нивото на защита на "$1"',
'prot_1movedto2'              => '„[[$1]]“ преместена като „[[$2]]“',
'protect-legend'              => 'Потвърждение на защитата',
'protectcomment'              => 'Причина:',
'protectexpiry'               => 'Изтича на:',
'protect_expiry_invalid'      => 'Невалиден срок на изтичане.',
'protect_expiry_old'          => 'Срокът на изтичане е минал.',
'protect-unchain-permissions' => 'Позволяване на по-нататъшни възможности за защита',
'protect-text'                => "Тук можете да прегледате и промените нивото на защита на страницата '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "Не можете да променяте нивата на защита на страниците, докато сте блокиран(а). Текущите настройки за страницата „'''$1'''“ са:",
'protect-locked-dblock'       => "Нивата на защита на страниците не могат да бъдат променяни, защото базата от данни е заключена. Ето текущите настройки за страницата „'''$1'''“:",
'protect-locked-access'       => "Нямате правото да променяте нивата на защита на страниците. Ето текущите настройки за страницата „'''$1'''“:",
'protect-cascadeon'           => 'Тази страница е защитена против редактиране, защото е включена в {{PLURAL:$1|следната страница, която от своя страна има|следните страници, които от своя страна имат}} каскадна защита. Можете да промените нивото на защита на страницата, но това няма да повлияе върху каскадната защита.',
'protect-default'             => 'Позволяване за всички потребители',
'protect-fallback'            => 'Необходими са права на „$1“',
'protect-level-autoconfirmed' => 'Блокиране на нови и нерегистрирани потребители',
'protect-level-sysop'         => 'Само за администратори',
'protect-summary-cascade'     => 'каскадно',
'protect-expiring'            => 'изтича на $1 (UTC)',
'protect-expiry-indefinite'   => 'безсрочно',
'protect-cascade'             => 'Каскадна защита — защита на всички страници, включени в настоящата страница.',
'protect-cantedit'            => 'Не можете да промените нивото на защита на тази страницата, защото нямате права да я редактирате.',
'protect-othertime'           => 'Друг срок:',
'protect-othertime-op'        => 'друг срок',
'protect-existing-expiry'     => 'Оставащо време: $2, $3',
'protect-otherreason'         => 'Друга/допълнителна причина:',
'protect-otherreason-op'      => 'Друга причина',
'protect-dropdown'            => '* Стандартни причини за защита на страници
** Чест обект на вандализъм
** Чест обект на спам
** Редакторска война
** Страница, изискваща много сървърни ресурси',
'protect-edit-reasonlist'     => 'Редактиране на причините за защита',
'protect-expiry-options'      => 'един час:1 hour,един ден:1 day,една седмица:1 week,две седмици:2 weeks,един месец:1 month,три месеца:3 months,шест месеца:6 months,една година:1 year,безсрочно:infinite',
'restriction-type'            => 'Състояние на защитата:',
'restriction-level'           => 'Ниво на защитата:',
'minimum-size'                => 'Минимален размер',
'maximum-size'                => 'Максимален размер',
'pagesize'                    => '(байта)',

# Restrictions (nouns)
'restriction-edit'   => 'Редактиране',
'restriction-move'   => 'Преместване',
'restriction-create' => 'Създаване',
'restriction-upload' => 'Качване',

# Restriction levels
'restriction-level-sysop'         => 'пълна защита',
'restriction-level-autoconfirmed' => 'полузащита',
'restriction-level-all'           => 'всички',

# Undelete
'undelete'                     => 'Преглед на изтрити страници',
'undeletepage'                 => 'Преглед и възстановяване на изтрити страници',
'undeletepagetitle'            => "'''По-долу е показан списък на изтритите версии на [[:$1|$1]]'''.",
'viewdeletedpage'              => 'Преглед на изтрити страници',
'undeletepagetext'             => '{{PLURAL:$1|Следната страница беше изтрита, но все още се намира в архива и може да бъде възстановена|Следните $1 страници бяха изтрити, но все още се намират в архива и могат да бъдат възстановени}}. Архивът може да се почиства от време на време.',
'undelete-fieldset-title'      => 'Възстановяване на версии',
'undeleteextrahelp'            => "За пълно възстановяване на историята на страницата, не слагайте отметки и натиснете '''''Възстановяване'''''.
За частично възстановяване отметнете тези версии на страницата, които трябва да бъдат въстановени, и натиснете '''''Възстановяване'''''.
Натискането на '''''Изчистване''''' ще премахне всички отметки и ще изчисти полето за коментар.",
'undeleterevisions'            => '{{PLURAL:$1|Една версия беше архивирана|$1 версии бяха архивирани}}',
'undeletehistory'              => 'Ако възстановите страницата, всички версии ще бъдат върнати в историята.
Ако след изтриването е създадена страница със същото име, възстановените версии ще се появят като по-ранна история, а текущата версия на страницата няма да бъде заменена автоматично. Също така обърнете внимание, че ограниченията, приложени върху версиите, ще се загубят след възстановяването.',
'undeleterevdel'               => 'Възстановяването няма да бъде изпълнено, ако би довело до частично изтриване на актуалната версия. В такъв случай актуалната версия не трябва да бъде избирана или пък състоянието й трябва да бъде променено на нормална (нескрита) версия. Версиите на файлове, които нямате право да преглеждате, няма да бъдат възстановени.',
'undeletehistorynoadmin'       => 'Тази страница е била изтрита. В резюмето отдолу е посочена причината за това, заедно с информация за потребителите, редактирали страницата преди изтриването й. Конкретното съдържание на изтритите версии е достъпно само за администратори.',
'undelete-revision'            => 'Изтрита версия на $1 (към $4, в $5) от $3:',
'undeleterevision-missing'     => 'Неправилна или липсваща версия. Може да сте последвали грешна препратка или указаната версия да е била възстановена или премахната от архива',
'undelete-nodiff'              => 'Не е открита предишна редакция.',
'undeletebtn'                  => 'Възстановяване',
'undeletelink'                 => 'преглед/възстановяване',
'undeleteviewlink'             => 'преглеждане',
'undeletereset'                => 'Изчистване',
'undeleteinvert'               => 'Обръщане на избора',
'undeletecomment'              => 'Причина:',
'undeletedarticle'             => 'възстанови „[[$1]]“',
'undeletedrevisions'           => '{{PLURAL:$1|Една версия беше възстановена|$1 версии бяха възстановени}}',
'undeletedrevisions-files'     => '{{PLURAL:$1|Една версия|$1 версии}} и {{PLURAL:$1|един файл|$2 файла}} бяха възстановени',
'undeletedfiles'               => '{{PLURAL:$1|Един файл беше възстановен|$1 файла бяха възстановени}}',
'cannotundelete'               => 'Грешка при възстановяването. Възможно е някой друг вече да е възстановил страницата.',
'undeletedpage'                => "'''Страницата „$1“ беше възстановена.'''

Можете да видите последните изтрити и възстановени страници в [[Special:Log/delete|дневника на изтриванията]].",
'undelete-header'              => 'Прегледайте [[Special:Log/delete|дневника на изтриванията]] за текущо изтритите страници.',
'undelete-search-box'          => 'Търсене на изтрити страници',
'undelete-search-prefix'       => 'Показване на страници, започващи със:',
'undelete-search-submit'       => 'Търсене',
'undelete-no-results'          => 'Не са намерени страници, отговарящи на търсения критерий.',
'undelete-filename-mismatch'   => 'Не е възможно възстановяването на файловата версия с времеви отпечатък $1: несъответствие в името на файла',
'undelete-bad-store-key'       => 'Не е възможно възстановяването на файловата версия с времеви отпечатък $1: файлът е липсвал преди изтриването.',
'undelete-cleanup-error'       => 'Грешка при изтриване на неизползвания архивен файл „$1“.',
'undelete-missing-filearchive' => 'Не е възможно възстановяването на файла с ID $1, защото не присъства в базата от данни. Вероятно вече е възстановен.',
'undelete-error-short'         => 'Грешка при възстановяването на изтрития файл: $1',
'undelete-error-long'          => 'Възникнаха грешки при възстановяването на изтрития файл:

$1',
'undelete-show-file-confirm'   => 'Сигурни ли сте, че искате да прегледате изтритата версия на файла „<nowiki>$1</nowiki>“ от $2 в $3?',
'undelete-show-file-submit'    => 'Да',

# Namespace form on various pages
'namespace'      => 'Именно пространство:',
'invert'         => 'Обръщане на избора',
'blanknamespace' => '(Основно)',

# Contributions
'contributions'       => 'Приноси',
'contributions-title' => 'Потребителски приноси за $1',
'mycontris'           => 'Моите приноси',
'contribsub2'         => 'За $1 ($2)',
'nocontribs'          => 'Не са намерени промени, отговарящи на критерия.',
'uctop'               => ' (последна)',
'month'               => 'Месец:',
'year'                => 'Година:',

'sp-contributions-newbies'             => 'Показване само на приносите на нови потребители',
'sp-contributions-newbies-sub'         => 'на нови потребители',
'sp-contributions-newbies-title'       => 'Потребителски приноси за нови сметки',
'sp-contributions-blocklog'            => 'Дневник на блокиранията',
'sp-contributions-deleted'             => 'изтрити приноси на потребител',
'sp-contributions-logs'                => 'дневници',
'sp-contributions-talk'                => 'беседа',
'sp-contributions-userrights'          => 'управление на потребителските права',
'sp-contributions-blocked-notice'      => 'Потребителят понастоящем е блокиран.
За справка по-долу е показан последния запис за него от дневника на блокиранията:',
'sp-contributions-blocked-notice-anon' => 'Този IP адрес понастоящем е блокиран.
За повече информация можете да прегледате последният запис в Дневника на блокиранията:',
'sp-contributions-search'              => 'Търсене на приноси',
'sp-contributions-username'            => 'IP-адрес или потребителско име:',
'sp-contributions-toponly'             => 'Показване само на последните редакции',
'sp-contributions-submit'              => 'Търсене',

# What links here
'whatlinkshere'            => 'Какво сочи насам',
'whatlinkshere-title'      => 'Страници, които сочат към „$1“',
'whatlinkshere-page'       => 'Страница:',
'linkshere'                => "Следните страници сочат към '''[[:$1]]''':",
'nolinkshere'              => "Няма страници, сочещи към '''[[:$1]]'''.",
'nolinkshere-ns'           => "Няма страници, сочещи към '''[[:$1]]''' в избраното именно пространство.",
'isredirect'               => 'пренасочваща страница',
'istemplate'               => 'включване',
'isimage'                  => 'препратка към файла',
'whatlinkshere-prev'       => '{{PLURAL:$1|предишна|предишни $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|следваща|следващи $1}}',
'whatlinkshere-links'      => '← препратки',
'whatlinkshere-hideredirs' => '$1 на пренасочващи страници',
'whatlinkshere-hidetrans'  => '$1 на включени страници',
'whatlinkshere-hidelinks'  => '$1 на препратки',
'whatlinkshere-hideimages' => '$1 препратки към файла',
'whatlinkshere-filters'    => 'Филтри',

# Block/unblock
'blockip'                         => 'Блокиране',
'blockip-title'                   => 'Блокиране на потребител',
'blockip-legend'                  => 'Блокиране на потребител',
'blockiptext'                     => 'Формулярът по-долу се използва за да се забрани правото на писане
на определен IP-адрес или потребител.
Това трябва да се направи само за да се предотвратят прояви на вандализъм
и в съответствие с [[{{MediaWiki:Policy-url}}|политиката за поведение]] в {{SITENAME}}.
Необходимо е да се посочи и причина за блокирането (например заглавия на страници, станали обект на вандализъм).',
'ipaddress'                       => 'IP-адрес:',
'ipadressorusername'              => 'IP-адрес или потребител:',
'ipbexpiry'                       => 'Срок:',
'ipbreason'                       => 'Причина:',
'ipbreasonotherlist'              => 'Друга причина',
'ipbreason-dropdown'              => '* Общи причини за блокиране
** Въвеждане на невярна информация
** Премахване на съдържание от страниците
** Добавяне на спам/нежелани външни препратки
** Въвеждане на безсмислици в страниците
** Заплашително поведение/тормоз
** Злупотреба с няколко потребителски сметки
** Неприемливо потребителско име',
'ipbanononly'                     => 'Блокиране само на анонимни потребители',
'ipbcreateaccount'                => 'Забрана за създаване на потребителски сметки',
'ipbemailban'                     => 'Забрана на потребителя да праща е-поща',
'ipbenableautoblock'              => 'Автоматично блокиране на последния IP-адрес, използван от потребителя, както и на всички останали адреси, от които се опита да редактира',
'ipbsubmit'                       => 'Блокиране на потребителя',
'ipbother'                        => 'Друг срок:',
'ipboptions'                      => 'два часа:2 hours,един ден:1 day,три дни:3 days,една седмица:1 week,две седмици:2 weeks,един месец:1 month,три месеца:3 months,шест месеца:6 months,една година:1 year,безсрочно:infinite',
'ipbotheroption'                  => 'друг',
'ipbotherreason'                  => 'Друга/допълнителна причина:',
'ipbhidename'                     => 'Скриване на потребителското име/IP-адреса от редакциите и дневниците',
'ipbwatchuser'                    => 'Наблюдаване на потребителската страница и беседата на този потребител',
'ipballowusertalk'                => 'Позволяване на потребителя да редактира собствената си беседа докато е блокиран',
'ipb-change-block'                => 'Повторно блокиране на потребителя с тези настройки',
'badipaddress'                    => 'Невалиден IP-адрес',
'blockipsuccesssub'               => 'Блокирането беше успешно',
'blockipsuccesstext'              => 'Потребител [[Special:Contributions/$1|$1]] беше блокиран(а).<br />
Можете да прегледате пълния [[Special:IPBlockList|списък на блокираните потребители]].',
'ipb-edit-dropdown'               => 'Причини за блокиране',
'ipb-unblock-addr'                => 'Отблокиране на $1',
'ipb-unblock'                     => 'Отблокиране на потребителско име IP-адрес',
'ipb-blocklist-addr'              => 'Преглед на текущите блокирания на $1',
'ipb-blocklist'                   => 'Преглед на текущите блокирания',
'ipb-blocklist-contribs'          => 'Приноси за $1',
'unblockip'                       => 'Отблокиране на потребител',
'unblockiptext'                   => 'Използвайте долния формуляр, за да възстановите правото на писане на по-рано блокиран IP-адрес или потребител.',
'ipusubmit'                       => 'Сваляне на блокирането',
'unblocked'                       => '[[User:$1|$1]] беше отблокиран.',
'unblocked-id'                    => 'Блок № $1 беше премахнат',
'ipblocklist'                     => 'Списък на блокирани IP-адреси и потребители',
'ipblocklist-legend'              => 'Откриване на блокиран потребител',
'ipblocklist-username'            => 'Потребителско име или IP адрес:',
'ipblocklist-sh-userblocks'       => '$1 блокирани потребителски сметки',
'ipblocklist-sh-tempblocks'       => '$1 временни блокирания',
'ipblocklist-sh-addressblocks'    => '$1 блокирани отделни IP-адреса',
'ipblocklist-submit'              => 'Търсене',
'ipblocklist-localblock'          => 'Локално блокиране',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|Друго блокиране|Други блокирания}}',
'blocklistline'                   => '$1, $2 е блокирал $3 ($4)',
'infiniteblock'                   => 'неограничено',
'expiringblock'                   => 'изтича на $1 в $2',
'anononlyblock'                   => 'само анон.',
'noautoblockblock'                => 'автоблокировката е изключена',
'createaccountblock'              => 'създаването на сметки е блокирано',
'emailblock'                      => 'е-пощенската услуга е блокирана',
'blocklist-nousertalk'            => 'забрана за редактиране на личната беседа',
'ipblocklist-empty'               => 'Списъкът на блокиранията е празен.',
'ipblocklist-no-results'          => 'Указаният IP-адрес или потребител не е блокиран.',
'blocklink'                       => 'блокиране',
'unblocklink'                     => 'отблокиране',
'change-blocklink'                => 'промяна на параметрите на блокирането',
'contribslink'                    => 'приноси',
'autoblocker'                     => 'Бяхте блокиран автоматично, тъй като неотдавна IP-адресът ви е бил ползван от блокирания в момента потребител [[User:$1|$1]]. Причината за неговото блокиране е: „$2“.',
'blocklogpage'                    => 'Дневник на блокиранията',
'blocklog-showlog'                => 'Потребителят е бил блокиран в миналото.
За справка по-долу е дадено извлечение от дневника на блокиранията:',
'blocklog-showsuppresslog'        => 'Потребителят е бил блокиран и прикриван в миналото.
За справка по-долу е дадено извлечение от дневника на прикриванията:',
'blocklogentry'                   => 'блокира [[$1]] със срок на изтичане $2 $3',
'reblock-logentry'                => 'промени параметрите на блокирането на [[$1]] със срок на изтричане $2 $3',
'blocklogtext'                    => 'Тази страница съдържа дневник на блокиранията и отблокиранията, извършени от този потребител.
Автоматично блокираните IP-адреси не са показани.
Вижте [[Special:IPBlockList|списъка на блокираните IP-адреси]] за текущото състояние на блокиранията.',
'unblocklogentry'                 => 'отблокира $1',
'block-log-flags-anononly'        => 'само анонимни потребители',
'block-log-flags-nocreate'        => 'създаването на сметки е изключено',
'block-log-flags-noautoblock'     => 'автоблокировката е изключена',
'block-log-flags-noemail'         => 'е-пощенската услуга е блокирана',
'block-log-flags-nousertalk'      => 'забрана за редактиране на личната беседа',
'block-log-flags-angry-autoblock' => 'разширената автоблокировка е включена',
'block-log-flags-hiddenname'      => 'скрито потребителско име',
'range_block_disabled'            => 'Възможността на администраторите да задават интервали при IP-адресите е изключена.',
'ipb_expiry_invalid'              => 'Невалиден срок на изтичане.',
'ipb_expiry_temp'                 => 'Скритите потребителски имена трябва да се блокират безсрочно.',
'ipb_hide_invalid'                => 'Тази потребителска сметка не може да бъде прикрита; може би с нея да са правени твърде много редакции.',
'ipb_already_blocked'             => '„$1“ е вече блокиран',
'ipb-needreblock'                 => '== Вече е блокиран ==
$1 е вече блокиран. Желаете ли да промените настройките?',
'ipb-otherblocks-header'          => '{{PLURAL:$1|Друго блокиране|Други блокирания}}',
'ipb_cant_unblock'                => 'Грешка: Не е намерен блок с номер $1. Вероятно потребителят е вече отблокиран.',
'ipb_blocked_as_range'            => 'Грешка: IP-адресът $1 не може да бъде разблокиран, тъй като е част от блокирания регистър $2. Можете да разблокирате адреса, като разблокирате целия регистър.',
'ip_range_invalid'                => 'Невалиден интервал за IP-адреси.',
'ip_range_toolarge'               => 'Забранено е блокиране на диапазони от IP адреси по-големи от /$1.',
'blockme'                         => 'Самоблокиране',
'proxyblocker'                    => 'Блокировач на проксита',
'proxyblocker-disabled'           => 'Тази функция е деактивирана.',
'proxyblockreason'                => 'IP-адресът ви беше блокиран, тъй като е анонимно достъпен междинен сървър. Свържете се с доставчика ви на интернет и го информирайте за този сериозен проблем в сигурността.',
'proxyblocksuccess'               => 'Готово.',
'sorbsreason'                     => 'IP-адресът ви е записан като анонимно достъпен междинен сървър в DNSBL на {{SITENAME}}.',
'sorbs_create_account_reason'     => 'IP-адресът ви е записан като анонимно достъпен междинен сървър в DNSBL на {{SITENAME}}. Не може да създадете сметка.',
'cant-block-while-blocked'        => 'Не можете да блокирате други потребители, докато сам(а) сте блокиран(а).',
'cant-see-hidden-user'            => 'Потребителят, който опитвате да блокирате, вече е блокиран и скрит. Тъй като нямате права да скривате потребители, не можете да видите или редактирате блокирането на потребителя.',
'ipbblocked'                      => 'Не можете да блокирате и разблокирате други потребители, защото вие самият (самата) сте блокиран(а).',
'ipbnounblockself'                => 'Нямате право да се разблокирате сам(а).',

# Developer tools
'lockdb'              => 'Заключване на базата от данни',
'unlockdb'            => 'Отключване на базата от данни',
'lockdbtext'          => 'Заключването на базата от данни ще попречи на всички потребители да редактират страници, да сменят своите настройки, да редактират своите списъци за наблюдение и на всички други техни действия, изискващи промени в базата данни.
Потвърдете, че искате точно това и ще отключите базата от данни, когато привършите с работата по подръжката.',
'unlockdbtext'        => 'Отключването на базата от данни ще възстанови способността на потребителите да редактират страници, да сменят своите настройки, да редактират своите списъци за наблюдение и изпълнението на всички други действия, изискващи промени в базата от данни.
Потвърдете, че искате точно това.',
'lockconfirm'         => 'Да, наистина искам да заключа базата от данни.',
'unlockconfirm'       => 'Да, наистина искам да отключа базата от данни.',
'lockbtn'             => 'Заключване на базата от данни',
'unlockbtn'           => 'Отключване на базата от данни',
'locknoconfirm'       => 'Не сте отметнали кутийката за потвърждение.',
'lockdbsuccesssub'    => 'Заключването на базата от данни беше успешно',
'unlockdbsuccesssub'  => 'Отключването на базата от данни беше успешно',
'lockdbsuccesstext'   => 'Базата данни на {{SITENAME}} беше заключена.
<br />Не забравяйте да я [[Special:UnlockDB|отключите]] когато привършите с работата по поддръжката.',
'unlockdbsuccesstext' => 'Базата от данни на {{SITENAME}} беше отключена.',
'lockfilenotwritable' => 'Няма права за писане върху файла за заключване на базата данни. За да заключи или отключи базата данни, уеб-сървърът трябва да има тези права.',
'databasenotlocked'   => 'Базата от данни не е заключена.',

# Move page
'move-page'                    => 'Преместване на $1',
'move-page-legend'             => 'Преместване на страница',
'movepagetext'                 => "Посредством долния формуляр можете да преименувате страница, премествайки цялата й история на новото име. Старото заглавие ще се превърне в пренасочваща страница.
Препратките към старата страница няма да бъдат променени; затова проверете за двойни или невалидни пренасочвания.
Вие сами би трябвало да се убедите в това, дали препратките продължават да сочат там, където се предполага.

Страницата '''няма''' да бъде преместена, ако вече съществува страница с новото име, освен ако е празна или пренасочване и няма редакционна история.

'''ВНИМАНИЕ!'''
Това може да е голяма и неочаквана промяна за известна страница. Уверете се, че разбирате последствията, преди да продължите.",
'movepagetalktext'             => "Ако съществува, съответната дискусионна страница ще бъде преместена автоматично заедно с нея, '''освен ако:'''
* не местите страницата от едно именно пространство в друго,
* вече съществува непразна дискусионна страница с това име или
* не сте отметнали долната кутийка.

В тези случаи, ако желаете, ще е необходимо да преместите страницата ръчно.",
'movearticle'                  => 'Преместване на страница:',
'moveuserpage-warning'         => "'''Внимание:''' Предприели сте опит да преместите потребителска страница. Забележете, че от преместването на страницата '''няма''' да последва преименуване на потребителя.",
'movenologin'                  => 'Не сте влезли',
'movenologintext'              => 'Необходимо е да [[Special:UserLogin|влезете]], за да може да премествате страници.',
'movenotallowed'               => 'Нямате права за преместване на страници.',
'movenotallowedfile'           => 'Нямате права да премествате файлове.',
'cant-move-user-page'          => 'Нямате нужните права на достъп, за да местите потребителски страници (можете да местите само подстраници).',
'cant-move-to-user-page'       => 'Нямате нужните права на достъп, за да извършвате преместване на страници върху потребителски страници (можете да местите само върху подстраници от потребителското пространство).',
'newtitle'                     => 'Към ново заглавие:',
'move-watch'                   => 'Наблюдаване на страницата',
'movepagebtn'                  => 'Преместване',
'pagemovedsub'                 => 'Преместването беше успешно',
'movepage-moved'               => "'''Страницата „$1“ беше преместена под името „$2“.'''",
'movepage-moved-redirect'      => 'Беше създадено пренасочване.',
'movepage-moved-noredirect'    => 'Създаването на пренасочване беше спряно.',
'articleexists'                => 'Вече съществува страница с това име или името, което сте избрали, е невалидно. Изберете друго име.',
'cantmove-titleprotected'      => 'Страницата не може да бъде преместена под новото заглавие, тъй като то е защитено от създаване',
'talkexists'                   => "'''Страницата беше успешно преместена, но без съответната дискусионна страница, защото под новото име има една съществуваща. Обединете ги ръчно.'''",
'movedto'                      => 'преместена като',
'movetalk'                     => 'Преместване и на дискусионната страница, ако е приложимо.',
'move-subpages'                => 'Преместване на всички подстраници (до $1)',
'move-talk-subpages'           => 'Преместване на всички подстраници на беседата (до $1)',
'movepage-page-exists'         => 'Страницата $1 вече съществува и няма да бъде автоматично презаписана.',
'movepage-page-moved'          => 'Страницата $1 беше преместена като $2.',
'movepage-page-unmoved'        => 'Страницата $1 не може да бъде преместена като $2.',
'movepage-max-pages'           => 'Преместен беше максималният брой от $1 {{PLURAL:$1|страница|страници}} и повече страници няма да бъдат премествани автоматично.',
'1movedto2'                    => 'премести „[[$1]]“ като „[[$2]]“',
'1movedto2_redir'              => 'премести „[[$1]]“ като „[[$2]]“ (върху пренасочване)',
'move-redirect-suppressed'     => 'прикрита пренасочваща страница',
'movelogpage'                  => 'Дневник на преместванията',
'movelogpagetext'              => 'По-долу е показан списък на преместванията.',
'movesubpage'                  => '{{PLURAL:$1|Подстраница|Подстраници}}',
'movesubpagetext'              => 'Тази страница има $1 {{PLURAL:$1|подстраница, показана|подстраници, показани}} по-долу.',
'movenosubpage'                => 'Тази страница няма подстраници.',
'movereason'                   => 'Причина:',
'revertmove'                   => 'връщане',
'delete_and_move'              => 'Изтриване и преместване',
'delete_and_move_text'         => '== Наложително изтриване ==

Целевата страница „[[:$1]]“ вече съществува. Искате ли да я изтриете, за да освободите място за преместването?',
'delete_and_move_confirm'      => 'Да, искам да изтрия тази страница.',
'delete_and_move_reason'       => 'Изтрита, за да се освободи място за преместване',
'selfmove'                     => 'Страницата не може да бъде преместена, тъй като целевото име съвпада с първоначалното й заглавие.',
'immobile-source-namespace'    => 'Не могат да се местят страници в именно пространство „$1“',
'immobile-target-namespace'    => 'Не е възможно преместването на страници в именното пространство „$1“',
'immobile-target-namespace-iw' => 'Страницата не може да бъде преместена под заглавие, оформено като междууики препратка.',
'immobile-source-page'         => 'Тази страница не може да бъде премествана.',
'immobile-target-page'         => 'Не може да се извърши преместване върху това целево заглавие.',
'imagenocrossnamespace'        => 'Невъзможно е да се преместват картинки извън това именно пространство',
'imagetypemismatch'            => 'Новото разширение на файла не съвпада с типа му',
'imageinvalidfilename'         => 'Целевото име на файл е невалидно',
'fix-double-redirects'         => 'Обновяване на всички двойни пренасочвания, които сочат към оригиналното заглавие',
'move-leave-redirect'          => 'Оставяне на пренасочваща страница от старото към новото заглавие',
'protectedpagemovewarning'     => "'''Внимание:''' Тази страница е защитена и само потребители със статут на администратори могат да я преместят.
За справка по-долу е показан последният запис от дневниците.",
'semiprotectedpagemovewarning' => "'''Внимание:''' Тази страница е защитена и само регистрирани потребители могат да я преместят.
За справка по-долу е показан последният запис от дневниците.",
'move-over-sharedrepo'         => '== Файлът вече съществува ==
[[:$1]] вече съществува в споделеното хранилище. Преместване на файл с това заглавие ще замени споделения файл.',
'file-exists-sharedrepo'       => 'Избраното име на файл вече се използва от файл в сопделеното хранилище.
Необходимо е да изберете друго име.',

# Export
'export'            => 'Изнасяне на страници',
'exporttext'        => "Тук можете да изнесете като XML текста и историята на една или повече страници. Получените данни можете да вмъкнете в друг сайт, използващ софтуера МедияУики, чрез [[Special:Import|неговата страница за внaсяне]].

За да изнесете няколко страници, въвеждайте всяко ново заглавие на '''нов ред'''. След това изберете дали искате само текущата версия (заедно с информация за последната редакция) или всички версии (заедно с текущата) на страницата.

Ако желаете само текущата версия, бихте могли да използвате препратка от вида [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] за страницата [[{{MediaWiki:Mainpage}}]].",
'exportcuronly'     => 'Включване само на текущата версия, а не на цялата история',
'exportnohistory'   => "----
'''Важно:''' Изнасянето на пълната история на страниците е забранено, защото много забавя уикито.",
'export-submit'     => 'Изнасяне',
'export-addcattext' => 'Добавяне на страници от категория:',
'export-addcat'     => 'Добавяне',
'export-addnstext'  => 'Добавяне на страници от именно пространство:',
'export-addns'      => 'Добавяне',
'export-download'   => 'Съхраняване като файл',
'export-templates'  => 'Включване на шаблоните',
'export-pagelinks'  => 'Включване на свързаните страници с дълбочина до:',

# Namespace 8 related
'allmessages'                   => 'Системни съобщения',
'allmessagesname'               => 'Име',
'allmessagesdefault'            => 'Текст по подразбиране',
'allmessagescurrent'            => 'Текущ текст',
'allmessagestext'               => 'Тази страница съдържа списък на системните съобщения от именното пространство „МедияУики“.
Посетете [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisation] и [http://translatewiki.net translatewiki.net], ако желаете да допринесете за общата локализация на софтуера МедияУики.',
'allmessagesnotsupportedDB'     => "Тази страница не може да бъде използвана, тъй като е изключена възможността '''\$wgUseDatabaseMessages'''.",
'allmessages-filter-legend'     => 'Филтър',
'allmessages-filter'            => 'Филтриране по ниво на персонализация:',
'allmessages-filter-unmodified' => 'Непроменени',
'allmessages-filter-all'        => 'Всички',
'allmessages-filter-modified'   => 'Променени',
'allmessages-prefix'            => 'Филтриране по представка:',
'allmessages-language'          => 'Език:',
'allmessages-filter-submit'     => 'Отваряне',

# Thumbnails
'thumbnail-more'           => 'Увеличаване',
'filemissing'              => 'Липсващ файл',
'thumbnail_error'          => 'Грешка при създаване на миникартинка: $1',
'djvu_page_error'          => 'Номерът на DjVu-страницата е извън обхвата',
'djvu_no_xml'              => 'Не е възможно вземането на XML за DjVu-файла',
'thumbnail_invalid_params' => 'Параметрите за миникартинка са невалидни',
'thumbnail_dest_directory' => 'Целевата директория не може да бъде създадена',
'thumbnail_image-type'     => 'Типът картинка не се поддържа',
'thumbnail_gd-library'     => 'Непълна конфугурация на библиотеката GD: липсва функцията $1',
'thumbnail_image-missing'  => 'Изглежда следният файл липсва: $1',

# Special:Import
'import'                     => 'Внасяне на страници',
'importinterwiki'            => 'Внасяне чрез Трансуики',
'import-interwiki-text'      => 'Изберете уики и име на страницата.
Датите на редакциите и имената на авторите ще бъдат запазени.
Всички операции при внасянето от друго уики се записват в [[Special:Log/import|дневника на внасянията]].',
'import-interwiki-source'    => 'Изходно уики/страница:',
'import-interwiki-history'   => 'Копиране на всички версии на страницата',
'import-interwiki-templates' => 'Включване на всички шаблони',
'import-interwiki-submit'    => 'Внасяне',
'import-interwiki-namespace' => 'Целево именно пространство:',
'import-upload-filename'     => 'Име на файл:',
'import-comment'             => 'Коментар:',
'importtext'                 => 'Изнесете файла от изходното уики чрез инструмента „[[Special:Export]]“, съхранете го на диска си и го качете тук.',
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
'importuploaderrorsize'      => 'Качването на файла за внасяне беше неуспешно. Файлът е по-голям от максималната допустима за качване големина.',
'importuploaderrorpartial'   => 'Качването на файла за внасяне беше неуспешно. Файлът беше качен частично.',
'importuploaderrortemp'      => 'Качването на файла за внасяне беше неуспешно. Временната директория липсва.',
'import-parse-failure'       => 'Грешка в разбора при внасяне на XML',
'import-noarticle'           => 'Няма страници, които да бъдат внесени!',
'import-nonewrevisions'      => 'Всички версии са били внесени преди.',
'xml-error-string'           => '$1 на ред $2, колона $3 (байт $4): $5',
'import-upload'              => 'Качване на XML данни',
'import-token-mismatch'      => 'Загубени са данните за сесията. Опитайте отново.',
'import-invalid-interwiki'   => 'Не може да бъде извършено внасяне от посоченото уики.',

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
'tooltip-ca-edit'                 => 'Можете да редактирате страницата. Използвайте бутона за предварителен преглед преди да съхраните.',
'tooltip-ca-addsection'           => 'Започване на нов раздел',
'tooltip-ca-viewsource'           => 'Страницата е защитена. Можете да разгледате изходния й код.',
'tooltip-ca-history'              => 'Предишни версии на страницата',
'tooltip-ca-protect'              => 'Защитаване на страницата',
'tooltip-ca-unprotect'            => 'Сваляне на защитата от тази страница',
'tooltip-ca-delete'               => 'Изтриване на страницата',
'tooltip-ca-undelete'             => 'Възстановяване на изтрити редакции на страницата',
'tooltip-ca-move'                 => 'Преместване на страницата',
'tooltip-ca-watch'                => 'Добавяне на страницата към списъка ви за наблюдение',
'tooltip-ca-unwatch'              => 'Премахване на страницата от списъка ви за наблюдение',
'tooltip-search'                  => 'Претърсване на {{SITENAME}}',
'tooltip-search-go'               => 'Отиване на страницата, ако тя съществува с точно това име',
'tooltip-search-fulltext'         => 'Търсене в страниците за този текст',
'tooltip-p-logo'                  => 'Началната страница',
'tooltip-n-mainpage'              => 'Началната страница',
'tooltip-n-mainpage-description'  => 'Посещаване на началната страница',
'tooltip-n-portal'                => 'Информация за проекта — какво, къде, как',
'tooltip-n-currentevents'         => 'Информация за текущите събития по света',
'tooltip-n-recentchanges'         => 'Списък на последните промени в {{SITENAME}}',
'tooltip-n-randompage'            => 'Зареждане на случайна страница',
'tooltip-n-help'                  => 'Помощната страница',
'tooltip-t-whatlinkshere'         => 'Списък на всички страници, сочещи насам',
'tooltip-t-recentchangeslinked'   => 'Последните промени на страници, сочени от тази страница',
'tooltip-feed-rss'                => 'RSS feed за страницата',
'tooltip-feed-atom'               => 'Atom feed за страницата',
'tooltip-t-contributions'         => 'Показване на приносите на потребителя',
'tooltip-t-emailuser'             => 'Изпращане на писмо до потребителя',
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
'tooltip-preview'                 => 'Предварителен преглед, използвайте го преди да съхраните!',
'tooltip-diff'                    => 'Показване на направените от вас промени по текста',
'tooltip-compareselectedversions' => 'Показване на разликите между двете избрани версии на страницата',
'tooltip-watch'                   => 'Добавяне на страницата към списъка ви за наблюдение',
'tooltip-recreate'                => 'Възстановяване на страницата независимо, че е била изтрита',
'tooltip-upload'                  => 'Започване на качването',
'tooltip-rollback'                => 'Чрез „отмяна“ ще премахнете наведнъж всички промени, нанесени от последния редактор',
'tooltip-undo'                    => 'Препратката „връщане“ премахва тази редакция и отваря страницата за редактиране в режим на предварителен преглед.
В полето за резюме може да се впише причина за връщането.',
'tooltip-preferences-save'        => 'Съхраняване на предпочитанията',
'tooltip-summary'                 => 'Въведете кратко резюме',

# Stylesheets
'common.css'   => '/* Чрез редактиране на този файл ще промените всички облици */',
'monobook.css' => '/* Чрез редактиране на този файл можете да промените облика Монобук */',

# Scripts
'common.js'   => '/* Този файл съдържа код на Джаваскрипт и се зарежда при всички потребители. */',
'monobook.js' => '/* Остаряла страница; използвайте [[MediaWiki:Common.js]] */',

# Metadata
'nodublincore'      => 'Метаданните Dublin Core RDF са изключени за този сървър.',
'nocreativecommons' => 'Метаданните Creative Commons RDF са изключени за този сървър.',
'notacceptable'     => 'Сървърът не може да предостави данни във формат, който да се разпознава от клиента ви.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Анонимен потребител|Анонимни потребители}}на {{SITENAME}}',
'siteuser'         => 'потребител на {{SITENAME}} $1',
'anonuser'         => 'Анонимен потребител на {{SITENAME}} $1',
'lastmodifiedatby' => 'Последната промяна на страницата е извършена от $3 на $2, $1.',
'othercontribs'    => 'Основаващо се върху работа на $1.',
'others'           => 'други',
'siteusers'        => '{{PLURAL:$2|потребителят|потребителите}} на {{SITENAME}} $1',
'anonusers'        => '{{PLURAL:$2|Анонимен потребител|Анонимни потребители}} на {{SITENAME}} $1',
'creditspage'      => 'Библиография и източници',
'nocredits'        => 'Няма въведени източници или библиография.',

# Spam protection
'spamprotectiontitle' => 'Филтър за защита от спам',
'spamprotectiontext'  => 'Страницата, която искахте да съхраните, беше блокирана от филтъра против спам. Това обикновено е причинено от препратка към външен сайт.',
'spamprotectionmatch' => 'Следният текст предизвика включването на филтъра: $1',
'spambot_username'    => 'Спамочистач',
'spam_reverting'      => 'Връщане на последната версия, несъдържаща препратки към $1',
'spam_blanking'       => 'Всички версии, съдържащи препратки към $1, изчистване',

# Info page
'infosubtitle'   => 'Информация за страницата',
'numedits'       => 'Брой редакции (страница): $1',
'numtalkedits'   => 'Брой редакции (дискусионна страница): $1',
'numwatchers'    => 'Брой наблюдатели: $1',
'numauthors'     => 'Брой различни автори (страница): $1',
'numtalkauthors' => 'Брой различни автори (дискусионна страница): $1',

# Skin names
'skinname-standard'    => 'Класика',
'skinname-nostalgia'   => 'Носталгия',
'skinname-cologneblue' => 'Кьолнско синьо',
'skinname-monobook'    => 'Монобук',
'skinname-myskin'      => 'Моят облик',
'skinname-chick'       => 'Пиленце',
'skinname-simple'      => 'Семпъл',
'skinname-modern'      => 'Модерен',

# Math options
'mw_math_png'    => 'Използване винаги на PNG',
'mw_math_simple' => 'HTML при опростен TeX, иначе PNG',
'mw_math_html'   => 'HTML по възможност, иначе PNG',
'mw_math_source' => 'Оставяне като TeX (за текстови браузъри)',
'mw_math_modern' => 'Препоръчително за нови браузъри',
'mw_math_mathml' => 'MathML по възможност (експериментално)',

# Math errors
'math_failure'          => 'Неуспех при разбора',
'math_unknown_error'    => 'непозната грешка',
'math_unknown_function' => 'непозната функция',
'math_lexing_error'     => 'лексикална грешка',
'math_syntax_error'     => 'синтактична грешка',
'math_image_error'      => 'Превръщането към PNG не сполучи. Проверете дали latex, dvips, gs и convert са правилно инсталирани.',
'math_bad_tmpdir'       => 'Невъзможно е писането във или създаването на временна директория за математическите операции',
'math_bad_output'       => 'Невъзможно е писането във или създаването на изходяща директория за математическите операции',
'math_notexvc'          => 'Липсва изпълнимият файл на texvc. Прегледайте math/README за информация относно конфигурирането.',

# Patrolling
'markaspatrolleddiff'                 => 'Отбелязване като проверена редакция',
'markaspatrolledtext'                 => 'Отбелязване на редакцията като проверена',
'markedaspatrolled'                   => 'Проверена редакция',
'markedaspatrolledtext'               => 'Избраната редакция на [[:$1]] беше отбелязана като патрулирана.',
'rcpatroldisabled'                    => 'Патрулът е деактивиран',
'rcpatroldisabledtext'                => 'Патрулът на последните промени е деактивиран',
'markedaspatrollederror'              => 'Не е възможно да се отбележи като проверена',
'markedaspatrollederrortext'          => 'Необходимо е да се посочи редакция, която да бъде отбелязана като проверена.',
'markedaspatrollederror-noautopatrol' => 'Не е разрешено да маркирате своите редакции като проверени.',

# Patrol log
'patrol-log-page'      => 'Дневник на патрула',
'patrol-log-header'    => 'Тази страница съдържа дневник на проверените версии.',
'patrol-log-line'      => 'отбеляза $1 от $2 като проверена $3',
'patrol-log-auto'      => '(автоматично)',
'patrol-log-diff'      => 'версия $1',
'log-show-hide-patrol' => '$1 на Дневника на патрула',

# Image deletion
'deletedrevision'                 => 'Изтрита стара версия $1',
'filedeleteerror-short'           => 'Грешка при изтриване на файл: $1',
'filedeleteerror-long'            => 'Възникнаха грешки при изтриването на файла:

$1',
'filedelete-missing'              => 'Файлът „$1“ не съществува и затова не може да бъде изтрит.',
'filedelete-old-unregistered'     => 'Посочената версия на файла „$1“ не беше открита в базата от данни.',
'filedelete-current-unregistered' => 'Указаният файл „$1“ не е в базата данни.',
'filedelete-archive-read-only'    => 'Сървърът няма права за писане в архивната директория „$1“.',

# Browsing diffs
'previousdiff' => '← По-стара редакция',
'nextdiff'     => 'По-нова редакция →',

# Media information
'mediawarning'         => "'''Внимание''': Възможно е файлът да съдържа злонамерен програмен код. Неговото изпълнение може да доведе до повреди в системата ви.",
'imagemaxsize'         => "Ограничение на размерите на картинките:<br />''(само за описателните страници)''",
'thumbsize'            => 'Размери на миникартинките:',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|страница|страници}}',
'file-info'            => '(големина на файла: $1, MIME-тип: $2)',
'file-info-size'       => '($1 × $2 пиксела, големина на файла: $3, MIME-тип: $4)',
'file-nohires'         => '<small>Не е налична версия с по-висока разделителна способност.</small>',
'svg-long-desc'        => '(Файл във формат SVG, основен размер: $1 × $2 пиксела, големина на файла: $3)',
'show-big-image'       => 'Пълна разделителна способност',
'show-big-image-thumb' => '<small>Размер на предварителния преглед: $1 × $2 пиксела</small>',
'file-info-gif-looped' => 'зациклен',
'file-info-gif-frames' => '$1 {{PLURAL:$1|кадър|кадъра}}',
'file-info-png-looped' => 'зациклен',

# Special:NewFiles
'newimages'             => 'Галерия на новите файлове',
'imagelisttext'         => "Списък от {{PLURAL:$1|един файл|'''$1''' файла, сортирани $2}}.",
'newimages-summary'     => 'Тази специална страница показва последно качените файлове.',
'newimages-legend'      => 'Име на файл',
'newimages-label'       => 'Име на файл (или част от него):',
'showhidebots'          => '($1 на ботове)',
'noimages'              => 'Няма нищо.',
'ilsubmit'              => 'Търсене',
'bydate'                => 'по дата',
'sp-newimages-showfrom' => 'Показване на новите файлове, като се започва от $2, $1',

# Bad image list
'bad_image_list' => 'Спазва се следният формат:

Отчитат се само записите в списъчен вид (редове, започващи със *). Първата препратка в реда трябва да сочи към неприемлив файл. Всички последващи препратки на същия ред се считат за изключения, т.е. страници, в които този файл може да се визуализира.',

# Metadata
'metadata'          => 'Метаданни',
'metadata-help'     => 'Файлът съдържа допълнителни данни, обикновено добавяни от цифровите апарати или скенери. Ако файлът е редактиран след създаването си, то някои параметри може да не съответстват на текущото изображение.',
'metadata-expand'   => 'Показване на допълнителните данни',
'metadata-collapse' => 'Скриване на допълнителните данни',
'metadata-fields'   => 'Следните EXIF данни ще бъдат включени на описателната страница на файла, когато информационната таблица е свита. Останалите данни ще са скрити по подразбиране.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Ширина',
'exif-imagelength'                 => 'Височина',
'exif-bitspersample'               => 'Дълбочина на цвета (битове)',
'exif-compression'                 => 'Вид компресия',
'exif-photometricinterpretation'   => 'Състав на пиксела',
'exif-orientation'                 => 'Ориентация',
'exif-samplesperpixel'             => 'Редица от компоненти',
'exif-planarconfiguration'         => 'Принцип на организация на данните',
'exif-ycbcrsubsampling'            => 'Коефициент в интервала от Y до C',
'exif-ycbcrpositioning'            => 'Y и C позициониране',
'exif-xresolution'                 => 'Хоризонтална разделителна способност',
'exif-yresolution'                 => 'Вертикална разделителна способност',
'exif-resolutionunit'              => 'Единица на резолюцията по X и Y',
'exif-stripoffsets'                => 'адрес на картината',
'exif-rowsperstrip'                => 'Брой редове на ивица',
'exif-stripbytecounts'             => 'Байтове на компресирана ивица',
'exif-jpeginterchangeformat'       => 'Начало на JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Байтове в JPEG формат',
'exif-transferfunction'            => 'Функция по пренос',
'exif-whitepoint'                  => 'Хроматичност на бялото',
'exif-primarychromaticities'       => 'Първични цветности',
'exif-ycbcrcoefficients'           => 'Коефициенти в матрицата на трансформацията на цветовото пространство',
'exif-referenceblackwhite'         => 'Двойка референтни стойности за баланса на черното и бялото',
'exif-datetime'                    => 'Дата и час на изменението на файла',
'exif-imagedescription'            => 'Название на изображението',
'exif-make'                        => 'Производител',
'exif-model'                       => 'Модел на фотоапарата',
'exif-software'                    => 'Използван софтуер',
'exif-artist'                      => 'Автор',
'exif-copyright'                   => 'Притежател на авторското право',
'exif-exifversion'                 => 'Exif версия',
'exif-flashpixversion'             => 'Поддържана версия Flashpix',
'exif-colorspace'                  => 'Цветово пространство',
'exif-componentsconfiguration'     => 'Значение на всеки компонент',
'exif-compressedbitsperpixel'      => 'Режим на компресия на образа',
'exif-pixelydimension'             => 'Пълна ширина на изображението',
'exif-pixelxdimension'             => 'Пълна височина на изображението',
'exif-makernote'                   => 'Допълнителни данни на производителя',
'exif-usercomment'                 => 'Допълнителни коментари',
'exif-relatedsoundfile'            => 'Свързан звуков файл',
'exif-datetimeoriginal'            => 'Дата и час на създаване',
'exif-datetimedigitized'           => 'Дата и час на записа',
'exif-subsectime'                  => 'DateTime части от секундата',
'exif-subsectimeoriginal'          => 'DateTimeOriginal части от секундата',
'exif-subsectimedigitized'         => 'DateTimeDigitized части от секундата',
'exif-exposuretime'                => 'Време на експонация',
'exif-exposuretime-format'         => '$1 сек ($2)',
'exif-fnumber'                     => 'F (бленда)',
'exif-exposureprogram'             => 'Програма на експонацията',
'exif-spectralsensitivity'         => 'Спектрална чувствителност',
'exif-isospeedratings'             => 'Светлочувствителност ISO',
'exif-oecf'                        => 'Фактор на оптоелектронно преобразуване',
'exif-shutterspeedvalue'           => 'Скорост на затвора',
'exif-aperturevalue'               => 'Диаметър на обектива',
'exif-brightnessvalue'             => 'Светлосила',
'exif-exposurebiasvalue'           => 'Отклонение от експонацията',
'exif-maxaperturevalue'            => 'Максимално достижима апертура',
'exif-subjectdistance'             => 'Разстояние до обекта',
'exif-meteringmode'                => 'Режим на измерване',
'exif-lightsource'                 => 'Източник на светлина',
'exif-flash'                       => 'Светкавица',
'exif-focallength'                 => 'Фокусно разстояние',
'exif-subjectarea'                 => 'Зона на обекта',
'exif-flashenergy'                 => 'Мощност на светкавицата',
'exif-spatialfrequencyresponse'    => 'Пространствен честотен отклик',
'exif-focalplanexresolution'       => 'Резолюция на фокусната равнина X',
'exif-focalplaneyresolution'       => 'Резолюция на фокусната равнина Y',
'exif-focalplaneresolutionunit'    => 'Единица за разделителна способност на фокалната равнина',
'exif-subjectlocation'             => 'Местоположение на обекта',
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
'exif-scenecapturetype'            => 'Тип на заснетата сцена',
'exif-gaincontrol'                 => 'Увеличение на яркостта',
'exif-contrast'                    => 'Контраст',
'exif-saturation'                  => 'Наситеност',
'exif-sharpness'                   => 'Острота',
'exif-devicesettingdescription'    => 'Описание на настройките на апарата',
'exif-subjectdistancerange'        => 'Разстояние до обекта',
'exif-imageuniqueid'               => 'Уникален идентификатор на изображението',
'exif-gpsversionid'                => 'GPS версия',
'exif-gpslatituderef'              => 'Географска ширина север или юг',
'exif-gpslatitude'                 => 'Географска ширина',
'exif-gpslongituderef'             => 'Географска дължина изток или запад',
'exif-gpslongitude'                => 'Географска дължина',
'exif-gpsaltituderef'              => 'Референтна точка за височината',
'exif-gpsaltitude'                 => 'Височина',
'exif-gpstimestamp'                => 'GPS време (атомен часовник)',
'exif-gpssatellites'               => 'GPS спътници, използвани за измерването',
'exif-gpsstatus'                   => 'Статус на GPS-приемника',
'exif-gpsmeasuremode'              => 'Режим на измерването',
'exif-gpsdop'                      => 'Точност на измерването',
'exif-gpsspeedref'                 => 'Мерна единица за скорост',
'exif-gpsspeed'                    => 'Скорост на GPS-приемника',
'exif-gpstrackref'                 => 'Тип на посоката на движение',
'exif-gpstrack'                    => 'Посока на движение',
'exif-gpsimgdirectionref'          => 'Тип на посоката на заснетото изображение',
'exif-gpsimgdirection'             => 'Посока на заснетото изображение',
'exif-gpsmapdatum'                 => 'Геодезичен датум',
'exif-gpsdestlatituderef'          => 'Географска ширина север или юг на отправната точка',
'exif-gpsdestlatitude'             => 'Географска ширина на отправната точка',
'exif-gpsdestlongituderef'         => 'Географска дължина изток или запад на отправната точка',
'exif-gpsdestlongitude'            => 'Географска дължина на отправната точка',
'exif-gpsdestbearingref'           => 'Тип на посоката към отправната точка',
'exif-gpsdestbearing'              => 'Посока към отправната точка',
'exif-gpsdestdistanceref'          => 'Мерна единица за разстоянието до отправната точка',
'exif-gpsdestdistance'             => 'Разстояние до отправната точка',
'exif-gpsprocessingmethod'         => 'Метод за обработка на данните от GPS',
'exif-gpsareainformation'          => 'Име на GPS зоната',
'exif-gpsdatestamp'                => 'GPS дата',
'exif-gpsdifferential'             => 'Диференциална корекция на GPS',

# EXIF attributes
'exif-compression-1' => 'Некомпресиран',

'exif-unknowndate' => 'Неизвестна дата',

'exif-orientation-1' => 'Нормално',
'exif-orientation-2' => 'Отражение по хоризонталата',
'exif-orientation-3' => 'Обърнато на 180°',
'exif-orientation-4' => 'Отражение по вертикалата',
'exif-orientation-5' => 'Обърнато на 90° срещу часовниковата стрелка и отразено по вертикалата',
'exif-orientation-6' => 'Обърнато на 90° по часовниковата стрелка',
'exif-orientation-7' => 'Обърнато на 90° по часовниковата стрелка и отразено по вертикалата',
'exif-orientation-8' => 'Обърнато на 90° срещу часовниковата стрелка',

'exif-planarconfiguration-1' => 'формат „chunky“',
'exif-planarconfiguration-2' => 'формат „planar“',

'exif-componentsconfiguration-0' => 'не съществува',

'exif-exposureprogram-0' => 'Не е определено',
'exif-exposureprogram-1' => 'Ръчна настройка',
'exif-exposureprogram-2' => 'Нормална програма',
'exif-exposureprogram-3' => 'Приоритет на блендата',
'exif-exposureprogram-4' => 'Приоритет на скоростта',
'exif-exposureprogram-5' => 'Приоритет на дълбочината на рязкостта',
'exif-exposureprogram-6' => 'Приоритет на скоростта на затвора',
'exif-exposureprogram-7' => 'Режим „Портрет“ (за снимки в едър план, фонът не е на фокус)',
'exif-exposureprogram-8' => 'Режим „Пейзаж“ (за пейзажни снимки, в които фонът е на фокус)',

'exif-subjectdistance-value' => '$1 метра',

'exif-meteringmode-0'   => 'Неизвестно',
'exif-meteringmode-1'   => 'Средно',
'exif-meteringmode-2'   => 'Централно измерване на светлината',
'exif-meteringmode-3'   => 'Точково измерване',
'exif-meteringmode-4'   => 'Многоточково измерване',
'exif-meteringmode-5'   => 'Образец',
'exif-meteringmode-6'   => 'Частично измерване',
'exif-meteringmode-255' => 'Друго',

'exif-lightsource-0'   => 'неизвестно',
'exif-lightsource-1'   => 'Дневна светлина',
'exif-lightsource-2'   => 'Флуоресцентно осветление',
'exif-lightsource-3'   => 'Волфрамово осветление',
'exif-lightsource-4'   => 'Светкавица',
'exif-lightsource-9'   => 'хубаво време',
'exif-lightsource-10'  => 'облачно',
'exif-lightsource-11'  => 'Сянка',
'exif-lightsource-12'  => 'Дневна флуоресцентна (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Дневна бяла флуоресцентна (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Студена бяла флуоресцентна (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Бяла флуоресцентна (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Стандартна светлина тип A',
'exif-lightsource-18'  => 'Стандартна светлина тип B',
'exif-lightsource-19'  => 'Стандартна светлина тип C',
'exif-lightsource-23'  => 'D50',
'exif-lightsource-24'  => 'Студийна лампа стандарт ISO',
'exif-lightsource-255' => 'друг източник на светлина',

# Flash modes
'exif-flash-fired-0'    => 'без светкавица',
'exif-flash-fired-1'    => 'със светкавица',
'exif-flash-return-0'   => 'няма функция за улавяне на стробоскопични ефекти',
'exif-flash-return-2'   => 'без стробоскопични  ефекти',
'exif-flash-return-3'   => 'със стробоскопични ефекти',
'exif-flash-mode-1'     => 'задължително със светкавица',
'exif-flash-mode-2'     => 'задължително без светкавица',
'exif-flash-mode-3'     => 'автоматичен режим',
'exif-flash-function-1' => 'Без светкавица',
'exif-flash-redeye-1'   => 'Корекция на ефекта „червени очи“',

'exif-focalplaneresolutionunit-2' => 'инчове',

'exif-sensingmethod-1' => 'Неопределено',
'exif-sensingmethod-2' => 'Едночипов цветови пространствен сензор',
'exif-sensingmethod-3' => 'Двучипов цветови пространствен сензор',
'exif-sensingmethod-4' => 'Тричипов цветови пространствен сензор',
'exif-sensingmethod-5' => 'Цветови последователен пространствен сензор',
'exif-sensingmethod-7' => 'Трилинеен сензор',
'exif-sensingmethod-8' => 'Цветови последователен линеен сензор',

'exif-filesource-3' => 'цифров фотоапарат',

'exif-scenetype-1' => 'Пряко заснето изображение',

'exif-customrendered-0' => 'нормален процес',
'exif-customrendered-1' => 'нестандартна обработка',

'exif-exposuremode-0' => 'автоматична експонация',
'exif-exposuremode-1' => 'ръчна експонация',
'exif-exposuremode-2' => 'Автоматичен клин',

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

'exif-contrast-0' => 'Нормален',
'exif-contrast-1' => 'Слабо повишение',
'exif-contrast-2' => 'Силно повишение',

'exif-saturation-0' => 'Нормална',
'exif-saturation-1' => 'Неголяма наситеност',
'exif-saturation-2' => 'Голяма наситеност',

'exif-sharpness-0' => 'Нормална',
'exif-sharpness-1' => 'по-меко',
'exif-sharpness-2' => 'по-остро',

'exif-subjectdistancerange-0' => 'Неизвестен',
'exif-subjectdistancerange-1' => 'Макро',
'exif-subjectdistancerange-2' => 'Близко',
'exif-subjectdistancerange-3' => 'Далечно',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'северна ширина',
'exif-gpslatitude-s' => 'южна ширина',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'източна дължина',
'exif-gpslongitude-w' => 'западна дължина',

'exif-gpsstatus-a' => 'Измерване в ход',
'exif-gpsstatus-v' => 'Оперативна съвместимост на измерването',

'exif-gpsmeasuremode-2' => 'Двуизмерно измерване',
'exif-gpsmeasuremode-3' => 'Триизмерно измерване',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'км/час',
'exif-gpsspeed-m' => 'мили/час',
'exif-gpsspeed-n' => 'възли',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'истинска',
'exif-gpsdirection-m' => 'магнитна',

# External editor support
'edit-externally'      => 'Редактиране на файла чрез външно приложение',
'edit-externally-help' => '(За повече информация прегледайте [http://www.mediawiki.org/wiki/Manual:External_editors указанията за настройките]).',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'всички',
'imagelistall'     => 'всички',
'watchlistall2'    => 'всички',
'namespacesall'    => 'Всички',
'monthsall'        => 'всички',
'limitall'         => 'всички',

# E-mail address confirmation
'confirmemail'              => 'Потвърждаване на адрес за електронна поща',
'confirmemail_noemail'      => 'Не сте посочили валиден адрес за електронна поща в [[Special:Preferences|настройки си]].',
'confirmemail_text'         => '{{SITENAME}} изисква да потвърдите адреса си за електронна поща преди да използвате възможностите за е-поща. Натиснете долния бутон, за да ви бъде изпратено писмо, съдържащо специално генерирана препратка, чрез която ще можете да потвърдите валидността на адреса си.',
'confirmemail_pending'      => 'Кодът за потвърждение вече е изпратен. Ако току-що сте се регистрирали, изчакайте няколко минути да пристигне писмото, преди да поискате нов код.',
'confirmemail_send'         => 'Изпращане на код за потвърждение',
'confirmemail_sent'         => 'Кодът за потвърждение беше изпратен.',
'confirmemail_oncreate'     => 'Код за потвърждение беше изпратен на електронната ви поща.
Този код не е необходим за влизане, но ще ви трябва при активирането на функциите в {{SITENAME}}, изискващи валидна електронна поща.',
'confirmemail_sendfailed'   => '{{SITENAME}} не можа да изпрати писмо с код за потвърждение. Проверете адреса си за недопустими знаци.
Изпращачът на е-поща отвърна: $1',
'confirmemail_invalid'      => 'Грешен код за потвърждение. Възможно е кодът да е остарял.',
'confirmemail_needlogin'    => 'Необходимо е да $1, за потвърждаване на адреса за електронна поща.',
'confirmemail_success'      => 'Адресът ви за електронна поща беше потвърден. Вече можете да влезете и да се наслаждавате на уикито.',
'confirmemail_loggedin'     => 'Адресът ви за електронна поща беше потвърден.',
'confirmemail_error'        => 'Станала е грешка при потвърждаването на адреса ви.',
'confirmemail_subject'      => '{{SITENAME}} — Потвърждаване на адрес за е-поща',
'confirmemail_body'         => 'Някой, вероятно вие, от IP-адрес $1, е регистрирал потребител „$2“ в {{SITENAME}}, като е посочил този адрес за електронна поща.

За да потвърдите, че сметката в {{SITENAME}} и настоящият пощенски адрес са ваши, заредете долната препратка в браузъра си:

$3

Ако някой друг е направил регистрацията в {{SITENAME}} и не желаете да я потвърждавате, последвайте препратката по-долу:

$5

Кодът за потвърждение ще загуби валидност след $4.',
'confirmemail_body_changed' => 'Някой, вероятно вие, от IP адрес $1,
е променил с този адрес електронната поща, асоциирана с потребителска сметка "$2" в {{SITENAME}}.

За да потвърдите, че тази потребителска сметка ви принадлежи и да активирате отново функциите, свързани с електронната поща в {{SITENAME}}, отворете във вашия браузър следната връзка:

$3

Ако потребителската сметка *не* ви принадлежи, моля, откажете потвърждението, като последвате следната връзка:

$5

Валидността на този код за потвърждение ще важи до $4.',
'confirmemail_invalidated'  => 'Отменено потвърждение за електронна поща',
'invalidateemail'           => 'Отмяна на потвърждението за електронна поща',

# Scary transclusion
'scarytranscludedisabled' => '[Включването между уикита е деактивирано]',
'scarytranscludefailed'   => '[Зареждането на шаблона за $1 не сполучи]',
'scarytranscludetoolong'  => '[Адресът е твърде дълъг]',

# Trackbacks
'trackbackbox'      => 'Обратни следи за статията:<br />
$1',
'trackbackremove'   => '([$1 Изтриване])',
'trackbacklink'     => 'Обратна следа',
'trackbackdeleteok' => 'Обратната следа беше изтрита.',

# Delete conflict
'deletedwhileediting' => "'''Внимание''': Страницата е била изтрита, след като сте започнали да я редактирате!",
'confirmrecreate'     => "Потребителят [[User:$1|$1]] ([[User talk:$1|беседа]]) е изтрил страницата, откакто сте започнали да я редактирате, като е посочил следното обяснение:
: ''$2''
Потвърдете, че наистина желаете да създадете страницата отново.",
'recreate'            => 'Ново създаване',

# action=purge
'confirm_purge_button' => 'Добре',
'confirm-purge-top'    => 'Изчистване на складираното копие на страницата?',
'confirm-purge-bottom' => 'Изчистването на страница опреснява кеша и показва последната й версия.',

# Multipage image navigation
'imgmultipageprev' => '← предишна страница',
'imgmultipagenext' => 'следваща страница →',
'imgmultigo'       => 'Отваряне',
'imgmultigoto'     => 'Отиване на страница $1',

# Table pager
'ascending_abbrev'         => 'възх',
'descending_abbrev'        => 'низх',
'table_pager_next'         => 'Следваща страница',
'table_pager_prev'         => 'Предишна страница',
'table_pager_first'        => 'Първа страница',
'table_pager_last'         => 'Последна страница',
'table_pager_limit'        => 'Показване на $1 записа на страница',
'table_pager_limit_submit' => 'Отваряне',
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
'lag-warn-normal' => 'Промените от {{PLURAL:$1|последната $1 секунда|последните $1 секунди}} вероятно не са показани в списъка.',
'lag-warn-high'   => 'Поради голямото изоставане в сървърната синхронизация, промените от {{PLURAL:$1|последната $1 секунда|последните $1 секунди}} вероятно не са показани в списъка.',

# Watchlist editor
'watchlistedit-numitems'       => 'Списъкът ви за наблюдение съдържа {{PLURAL:$1|1 страница |$1 страници}} (без беседите).',
'watchlistedit-noitems'        => 'Списъкът ви за наблюдение е празен.',
'watchlistedit-normal-title'   => 'Редактиране на списъка за наблюдение',
'watchlistedit-normal-legend'  => 'Премахване на записи от списъка за наблюдение',
'watchlistedit-normal-explain' => 'По-долу са показани заглавията на страниците от списъка ви за наблюдение.
За да премахнете страница, отбележете полето пред нея и щракнете на бутона „{{int:Watchlistedit-normal-submit}}“.
Можете също да редактирате [[Special:Watchlist/raw|необработения списък за наблюдение]].',
'watchlistedit-normal-submit'  => 'Премахване',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 страница беше премахната|$1 страници бяха премахнати}} от списъка ви за наблюдение:',
'watchlistedit-raw-title'      => 'Редактиране на необработения списък за наблюдение',
'watchlistedit-raw-legend'     => 'Редактиране на необработения списък за наблюдение',
'watchlistedit-raw-explain'    => 'По-долу са показани заглавията на страниците от списъка ви за наблюдение. Можете да ги редактирате, като добавяте или премахвате по едно заглавие на ред. Като приключите, щракнете върху бутона „{{int:Watchlistedit-raw-submit}}“.
Можете да използвате и [[Special:Watchlist/edit|стандартния редактор]].',
'watchlistedit-raw-titles'     => 'Страници:',
'watchlistedit-raw-submit'     => 'Обновяване на списъка за наблюдение',
'watchlistedit-raw-done'       => 'Списъкът ви за наблюдение беше обновен.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 страница беше добавена|$1 страници бяха добавени}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|Една страница беше премахната|$1 страници бяха премахнати}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Преглед на списъка за наблюдение',
'watchlisttools-edit' => 'Преглед и редактиране на списъка за наблюдение',
'watchlisttools-raw'  => 'Редактиране на необработения списък за наблюдение',

# Core parser functions
'unknown_extension_tag' => 'Непознат етикет на разширение „$1“',
'duplicate-defaultsort' => 'Внимание: Ключът за сортиране по подразбиране „$2“ отменя по-ранния ключ „$1“.',

# Special:Version
'version'                          => 'Версия',
'version-extensions'               => 'Инсталирани разширения',
'version-specialpages'             => 'Специални страници',
'version-parserhooks'              => 'Куки в парсера',
'version-variables'                => 'Променливи',
'version-other'                    => 'Други',
'version-mediahandlers'            => 'Обработчици на медия',
'version-hooks'                    => 'Куки',
'version-extension-functions'      => 'Допълнителни функции',
'version-parser-extensiontags'     => 'Етикети от парсерни разширения',
'version-parser-function-hooks'    => 'Куки в парсерни функции',
'version-skin-extension-functions' => 'Функции на разширения за облици',
'version-hook-name'                => 'Име на куката',
'version-hook-subscribedby'        => 'Ползвана от',
'version-version'                  => '(Версия $1)',
'version-license'                  => 'Лиценз',
'version-poweredby-others'         => 'други',
'version-software'                 => 'Инсталиран софтуер',
'version-software-product'         => 'Продукт',
'version-software-version'         => 'Версия',

# Special:FilePath
'filepath'         => 'Път към файл',
'filepath-page'    => 'Файл:',
'filepath-submit'  => 'Отваряне',
'filepath-summary' => 'Тази специална страница връща пълния път до даден файл. Изображенията се показват в пълната им разделителна способност, а други типове файлове се отварят направо с приложенията, с които са асоциирани.

Името на файла се изписва без представката „{{ns:file}}:“',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Търсене на повтарящи се файлове',
'fileduplicatesearch-summary'  => 'Въведете име на файл (без представката „{{ns:file}}:“), за да потърсите повтарящи го файлове въз основа на неговата хеш стойност.',
'fileduplicatesearch-legend'   => 'Търсене на повтарящ се файл',
'fileduplicatesearch-filename' => 'Име на файл:',
'fileduplicatesearch-submit'   => 'Търсене',
'fileduplicatesearch-info'     => '$1 × $2 пиксела<br />Размер на файла: $3<br />MIME тип: $4',
'fileduplicatesearch-result-1' => 'Файлът „$1“ няма идентично копие.',
'fileduplicatesearch-result-n' => 'Файлът „$1“ има {{PLURAL:$2|едно идентично копие|$2 идентични копия}}.',

# Special:SpecialPages
'specialpages'                   => 'Специални страници',
'specialpages-note'              => '----
* Обикновени специални страници.
* <strong class="mw-specialpagerestricted">Специални страници с ограничения.</strong>',
'specialpages-group-maintenance' => 'Доклади по поддръжката',
'specialpages-group-other'       => 'Други специални страници',
'specialpages-group-login'       => 'Влизане / регистриране',
'specialpages-group-changes'     => 'Последни промени и дневници',
'specialpages-group-media'       => 'Доклади за файловете и качванията',
'specialpages-group-users'       => 'Потребители и права',
'specialpages-group-highuse'     => 'Широко използвани страници',
'specialpages-group-pages'       => 'Списъци на страниците',
'specialpages-group-pagetools'   => 'Инструменти за страниците',
'specialpages-group-wiki'        => 'Уики данни и инструменти',
'specialpages-group-redirects'   => 'Пренасочващи специални страници',
'specialpages-group-spam'        => 'Инструменти против спам',

# Special:BlankPage
'blankpage'              => 'Празна страница',
'intentionallyblankpage' => 'Тази страница умишлено е оставена празна',

# External image whitelist
'external_image_whitelist' => ' #Оставете този ред така, както го виждате. <pre>
#Поставете долу фрагменти от регулярни изрази (само частта между //).
#Тези фрагменти ще се съпоставят с интернет адресите на външните (hotlinked) картинки.
#Картинките, чиито адреси отговарят на вписаните регулярни изрази, ще се визуализират, за останалите ще се появява само връзка.
#Редовете, започващи с # се възприемат като коментари.
#Командите са чувствителни на малки и главни букви.

#Слагайте всички фрагменти от регулярни изрази НАД този ред. Оставете този ред така, както го виждате. </pre>',

# Special:Tags
'tags'                    => 'Валидни етикети за промени',
'tag-filter'              => 'Филтър на [[Special:Tags|етикета]]:',
'tag-filter-submit'       => 'Филтриране',
'tags-title'              => 'Етикети',
'tags-intro'              => 'Тук са изброени всички етикети, които могат да се ползват за отбелязване на редакциите, както и тяхното значение.',
'tags-tag'                => 'Име на етикета',
'tags-display-header'     => 'Изглед в списъците с промени',
'tags-description-header' => 'Пълно описание на значението',
'tags-hitcount-header'    => 'Отбелязани промени',
'tags-edit'               => 'редактиране',
'tags-hitcount'           => '$1 {{PLURAL:$1|промяна|промени}}',

# Special:ComparePages
'comparepages' => 'Сравнете страниците',

# Database error messages
'dberr-header'      => 'Това уики има проблем',
'dberr-problems'    => 'Съжаляваме! Сайтът изпитва технически затруднения.',
'dberr-again'       => 'Изчакайте няколко минути и опитайте да презаредите.',
'dberr-info'        => '(Няма достъп до сървъра с базата данни: $1)',
'dberr-usegoogle'   => 'Междувременно опитайте да потърсите в Google.',
'dberr-outofdate'   => 'Имайте предвид, че индексираното от Гугъл наше съдържание може вече да е неактуално.',
'dberr-cachederror' => 'Следва складирано копие на поисканата страница. Възможно е складираното копие да не е актуално.',

# HTML forms
'htmlform-invalid-input'       => 'Има проблеми с част от въведения от вас вход',
'htmlform-select-badoption'    => 'Посочената от вас стойност не е валидна алтернатива.',
'htmlform-int-invalid'         => 'Въведената от вас стойност не е цяло число.',
'htmlform-float-invalid'       => 'Посочената стойност не е число.',
'htmlform-int-toolow'          => 'Посочената от вас стойност е под минимално допустимата $1.',
'htmlform-int-toohigh'         => 'Посочената от вас стойност надхвърля максимално допустимата $1.',
'htmlform-required'            => 'Тази стойност се изисква',
'htmlform-submit'              => 'Изпращане',
'htmlform-reset'               => 'Отказване на промените',
'htmlform-selectorother-other' => 'Друга',

);

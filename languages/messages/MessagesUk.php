<?php
/** Ukrainian (Українська)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author A1
 * @author AS
 * @author Ahonc
 * @author Aleksandrit
 * @author AlexSm
 * @author Dubyk
 * @author EugeneZelenko
 * @author Gutsul (Gutsul.ua at Google Mail)
 * @author Ickis
 * @author Ilyaroz
 * @author Innv
 * @author KEL
 * @author Kalan
 * @author NickK
 * @author Prima klasy4na
 * @author Urhixidur
 * @author VolodymyrF
 * @author Тест
 */

/*
 * УВАГА! НЕ РЕДАГУЙТЕ ЦЕЙ ФАЙЛ!
 *
 * Якщо необхідно змінити переклад окремих частин інтерфейсу,
 * то це можна зробити редагуючи сторінки типу «MediaWiki:*».
 * Їх список можна знайти на сторінці «Special:Allmessages».
 */

$separatorTransformTable = array(
	',' => "\xc2\xa0", # nbsp
	'.' => ','
);

$fallback = 'ru';
$fallback8bitEncoding = 'windows-1251';
$linkPrefixExtension = true;

$namespaceNames = array(
	NS_MEDIA            => 'Медіа',
	NS_SPECIAL          => 'Спеціальна',
	NS_TALK             => 'Обговорення',
	NS_USER             => 'Користувач',
	NS_USER_TALK        => 'Обговорення_користувача',
	NS_PROJECT_TALK     => 'Обговорення_{{GRAMMAR:genitive|$1}}',
	NS_FILE             => 'Файл',
	NS_FILE_TALK        => 'Обговорення_файлу',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Обговорення_MediaWiki',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Обговорення_шаблону',
	NS_HELP             => 'Довідка',
	NS_HELP_TALK        => 'Обговорення_довідки',
	NS_CATEGORY         => 'Категорія',
	NS_CATEGORY_TALK    => 'Обговорення_категорії',
);

$namespaceAliases = array(
	'Спеціальні' => NS_SPECIAL,
	'Зображення' => NS_FILE,
	'Обговорення_зображення' => NS_FILE_TALK,
);


$dateFormats = array(
	'mdy time' => 'H:i',
	'mdy date' => 'xg j, Y',
	'mdy both' => 'H:i, xg j, Y',

	'dmy time' => 'H:i',
	'dmy date' => 'j xg Y',
	'dmy both' => 'H:i, j xg Y',

	'ymd time' => 'H:i',
	'ymd date' => 'Y xg j',
	'ymd both' => 'H:i, Y xg j',

	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
);

$bookstoreList = array(
	'Amazon.com' => 'http://www.amazon.com/exec/obidos/ISBN=$1'
);

$magicWords = array(
	'redirect'              => array( '0', '#ПЕРЕНАПРАВЛЕННЯ', '#ПЕРЕНАПР', '#перенапр', '#перенаправление', '#REDIRECT' ),
	'notoc'                 => array( '0', '__БЕЗ_ЗМІСТУ__', '__БЕЗ_ОГЛАВЛЕНИЯ__', '__БЕЗ_ОГЛ__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__БЕЗ_ГАЛЕРЕЇ__', '__БЕЗ_ГАЛЕРЕИ__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__ОБОВ_ЗМІСТ__', '__ОБЯЗАТЕЛЬНОЕ_ОГЛАВЛЕНИЕ__', '__ОБЯЗ_ОГЛ__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__ЗМІСТ__', '__ОГЛАВЛЕНИЕ__', '__ОГЛ__', '__TOC__' ),
	'noeditsection'         => array( '0', '__БЕЗ_РЕДАГУВ_РОЗДІЛУ__', '__БЕЗ_РЕДАКТИРОВАНИЯ_РАЗДЕЛА__', '__NOEDITSECTION__' ),
	'noheader'              => array( '0', '__БЕЗ_ЗАГОЛОВКУ__', '__БЕЗ_ЗАГОЛОВКА__', '__NOHEADER__' ),
	'currentmonth'          => array( '1', 'ПОТОЧНИЙ_МІСЯЦЬ', 'ПОТОЧНИЙ_МІСЯЦЬ_2', 'ТЕКУЩИЙ_МЕСЯЦ', 'ТЕКУЩИЙ_МЕСЯЦ_2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'         => array( '1', 'ПОТОЧНИЙ_МІСЯЦЬ_1', 'ТЕКУЩИЙ_МЕСЯЦ_1', 'CURRENTMONTH1' ),
	'currentmonthname'      => array( '1', 'НАЗВА_ПОТОЧНОГО_МІСЯЦЯ', 'НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'НАЗВА_ПОТОЧНОГО_МІСЯЦЯ_РОД', 'НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА_РОД', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'    => array( '1', 'НАЗВА_ПОТОЧНОГО_МІСЯЦЯ_АБР', 'НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА_АБР', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'ПОТОЧНИЙ_ДЕНЬ', 'ТЕКУЩИЙ_ДЕНЬ', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'ПОТОЧНИЙ_ДЕНЬ_2', 'ТЕКУЩИЙ_ДЕНЬ_2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'НАЗВА_ПОТОЧНОГО_ДНЯ', 'НАЗВАНИЕ_ТЕКУЩЕГО_ДНЯ', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'ПОТОЧНИЙ_РІК', 'ТЕКУЩИЙ_ГОД', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'ПОТОЧНИЙ_ЧАС', 'ТЕКУЩЕЕ_ВРЕМЯ', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'ПОТОЧНА_ГОДИНА', 'ТЕКУЩИЙ_ЧАС', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'ЛОКАЛЬНИЙ_МІСЯЦЬ', 'ЛОКАЛЬНИЙ_МІСЯЦЬ_2', 'МЕСТНЫЙ_МЕСЯЦ', 'МЕСТНЫЙ_МЕСЯЦ_2', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'           => array( '1', 'ЛОКАЛЬНИЙ_МІСЯЦЬ_1', 'МЕСТНЫЙ_МЕСЯЦ_1', 'LOCALMONTH1' ),
	'localmonthname'        => array( '1', 'НАЗВА_ЛОКАЛЬНОГО_МІСЯЦЯ', 'НАЗВАНИЕ_МЕСТНОГО_МЕСЯЦА', 'LOCALMONTHNAME' ),
	'localmonthnamegen'     => array( '1', 'НАЗВА_ЛОКАЛЬНОГО_МІСЯЦЯ_РОД', 'НАЗВАНИЕ_МЕСТНОГО_МЕСЯЦА_РОД', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'      => array( '1', 'НАЗВА_ЛОКАЛЬНОГО_МІСЯЦЯ_АБР', 'НАЗВАНИЕ_МЕСТНОГО_МЕСЯЦА_АБР', 'LOCALMONTHABBREV' ),
	'localday'              => array( '1', 'ЛОКАЛЬНИЙ_ДЕНЬ', 'МЕСТНЫЙ_ДЕНЬ', 'LOCALDAY' ),
	'localday2'             => array( '1', 'ЛОКАЛЬНИЙ_ДЕНЬ_2', 'МЕСТНЫЙ_ДЕНЬ_2', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'НАЗВА_ЛОКАЛЬНОГО_ДНЯ', 'НАЗВАНИЕ_МЕСТНОГО_ДНЯ', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'ЛОКАЛЬНИЙ_РІК', 'МЕСТНЫЙ_ГОД', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'ЛОКАЛЬНИЙ_ЧАС', 'МЕСТНОЕ_ВРЕМЯ', 'LOCALTIME' ),
	'localhour'             => array( '1', 'ЛОКАЛЬНА_ГОДИНА', 'МЕСТНЫЙ_ЧАС', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'КІЛЬКІСТЬ_СТОРІНОК', 'КОЛИЧЕСТВО_СТРАНИЦ', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'КІЛЬКІСТЬ_СТАТЕЙ', 'КОЛИЧЕСТВО_СТАТЕЙ', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'КІЛЬКІСТЬ_ФАЙЛІВ', 'КОЛИЧЕСТВО_ФАЙЛОВ', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'КІЛЬКІСТЬ_КОРИСТУВАЧІВ', 'КОЛИЧЕСТВО_УЧАСТНИКОВ', 'NUMBEROFUSERS' ),
	'numberofactiveusers'   => array( '1', 'КІЛЬКІСТЬ_АКТИВНИХ_КОРИСТУВАЧІВ', 'КОЛИЧЕСТВО_АКТИВНЫХ_УЧАСТНИКОВ', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'         => array( '1', 'КІЛЬКІСТЬ_РЕДАГУВАНЬ', 'КОЛИЧЕСТВО_ПРАВОК', 'NUMBEROFEDITS' ),
	'numberofviews'         => array( '1', 'КІЛЬКІСТЬ_ПЕРЕГЛЯДІВ', 'КОЛИЧЕСТВО_ПРОСМОТРОВ', 'NUMBEROFVIEWS' ),
	'pagename'              => array( '1', 'НАЗВА_СТОРІНКИ', 'НАЗВАНИЕ_СТРАНИЦЫ', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'НАЗВА_СТОРІНКИ_2', 'НАЗВАНИЕ_СТРАНИЦЫ_2', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'ПРОСТІР_НАЗВ', 'ПРОСТРАНСТВО_ИМЁН', 'NAMESPACE' ),
	'namespacee'            => array( '1', 'ПРОСТІР_НАЗВ_2', 'ПРОСТРАНСТВО_ИМЁН_2', 'NAMESPACEE' ),
	'talkspace'             => array( '1', 'ПРОСТІР_ОБГОВОРЕННЯ', 'ПРОСТРАНСТВО_ОБСУЖДЕНИЙ', 'TALKSPACE' ),
	'talkspacee'            => array( '1', 'ПРОСТІР_ОБГОВОРЕННЯ_2', 'ПРОСТРАНСТВО_ОБСУЖДЕНИЙ_2', 'TALKSPACEE' ),
	'subjectspace'          => array( '1', 'ПРОСТІР_СТАТЕЙ', 'ПРОСТРАНСТВО_СТАТЕЙ', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'         => array( '1', 'ПРОСТІР_СТАТЕЙ_2', 'ПРОСТРАНСТВО_СТАТЕЙ_2', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'          => array( '1', 'ПОВНА_НАЗВА_СТОРІНКИ', 'ПОЛНОЕ_НАЗВАНИЕ_СТРАНИЦЫ', 'FULLPAGENAME' ),
	'fullpagenamee'         => array( '1', 'ПОВНА_НАЗВА_СТОРІНКИ_2', 'ПОЛНОЕ_НАЗВАНИЕ_СТРАНИЦЫ_2', 'FULLPAGENAMEE' ),
	'subpagename'           => array( '1', 'НАЗВА_ПІДСТОРІНКИ', 'НАЗВАНИЕ_ПОДСТРАНИЦЫ', 'SUBPAGENAME' ),
	'subpagenamee'          => array( '1', 'НАЗВА_ПІДСТОРІНКИ_2', 'НАЗВАНИЕ_ПОДСТРАНИЦЫ_2', 'SUBPAGENAMEE' ),
	'basepagename'          => array( '1', 'ОСНОВА_НАЗВИ_ПІДСТОРІНКИ', 'ОСНОВА_НАЗВАНИЯ_СТРАНИЦЫ', 'BASEPAGENAME' ),
	'basepagenamee'         => array( '1', 'ОСНОВА_НАЗВИ_ПІДСТОРІНКИ_2', 'ОСНОВА_НАЗВАНИЯ_СТРАНИЦЫ_2', 'BASEPAGENAMEE' ),
	'talkpagename'          => array( '1', 'НАЗВА_СТОРІНКИ_ОБГОВОРЕННЯ', 'НАЗВАНИЕ_СТРАНИЦЫ_ОБСУЖДЕНИЯ', 'TALKPAGENAME' ),
	'talkpagenamee'         => array( '1', 'НАЗВА_СТОРІНКИ_ОБГОВОРЕННЯ_2', 'НАЗВАНИЕ_СТРАНИЦЫ_ОБСУЖДЕНИЯ_2', 'TALKPAGENAMEE' ),
	'subjectpagename'       => array( '1', 'НАЗВА_СТАТТІ', 'НАЗВАНИЕ_СТРАНИЦЫ_СТАТЬИ', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'      => array( '1', 'НАЗВА_СТАТТІ_2', 'НАЗВАНИЕ_СТРАНИЦЫ_СТАТЬИ_2', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                   => array( '0', 'ПОВІД:', 'ПОВІДОМЛЕННЯ:', 'СООБЩЕНИЕ:', 'СООБЩ:', 'MSG:' ),
	'subst'                 => array( '0', 'ПІДСТ:', 'ПІДСТАНОВКА:', 'ПОДСТАНОВКА:', 'ПОДСТ:', 'SUBST:' ),
	'safesubst'             => array( '0', 'БЕЗПЕЧНА_ПІДСТАНОВКА:', 'SAFESUBST:' ),
	'msgnw'                 => array( '0', 'ПОВІД_БЕЗ_ВІКІ:', 'СООБЩ_БЕЗ_ВИКИ:', 'MSGNW:' ),
	'img_thumbnail'         => array( '1', 'міні', 'мініатюра', 'мини', 'миниатюра', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'міні=$1', 'мініатюра=$1', 'мини=$1', 'миниатюра=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'праворуч', 'справа', 'right' ),
	'img_left'              => array( '1', 'ліворуч', 'слева', 'left' ),
	'img_none'              => array( '1', 'без', 'none' ),
	'img_width'             => array( '1', '$1пкс', '$1px' ),
	'img_center'            => array( '1', 'центр', 'center', 'centre' ),
	'img_framed'            => array( '1', 'обрамити', 'рамка', 'обрамить', 'framed', 'enframed', 'frame' ),
	'img_frameless'         => array( '1', 'безрамки', 'frameless' ),
	'img_page'              => array( '1', 'сторінка=$1', 'сторінка $1', 'страница=$1', 'страница $1', 'page=$1', 'page $1' ),
	'img_upright'           => array( '1', 'зверхуправоруч', 'зверхуправоруч=$1', 'зверхуправоруч $1', 'сверхусправа', 'сверхусправа=$1', 'сверхусправа $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'            => array( '1', 'межа', 'граница', 'border' ),
	'img_baseline'          => array( '1', 'основа', 'основание', 'baseline' ),
	'img_sub'               => array( '1', 'під', 'под', 'sub' ),
	'img_super'             => array( '1', 'над', 'super', 'sup' ),
	'img_top'               => array( '1', 'зверху', 'сверху', 'top' ),
	'img_text_top'          => array( '1', 'текст-зверху', 'текст-сверху', 'text-top' ),
	'img_middle'            => array( '1', 'посередині', 'посередине', 'middle' ),
	'img_bottom'            => array( '1', 'знизу', 'снизу', 'bottom' ),
	'img_text_bottom'       => array( '1', 'текст-знизу', 'текст-снизу', 'text-bottom' ),
	'img_link'              => array( '1', 'посилання=$1', 'ссылка=$1', 'link=$1' ),
	'img_alt'               => array( '1', 'альт=$1', 'alt=$1' ),
	'int'                   => array( '0', 'ВНУТР:', 'INT:' ),
	'sitename'              => array( '1', 'НАЗВА_САЙТУ', 'НАЗВАНИЕ_САЙТА', 'SITENAME' ),
	'ns'                    => array( '0', 'ПН:', 'ПИ:', 'NS:' ),
	'nse'                   => array( '0', 'ПН_2:', 'ПИК:', 'NSE:' ),
	'localurl'              => array( '0', 'ЛОКАЛЬНА_АДРЕСА:', 'ЛОКАЛЬНЫЙ_АДРЕС:', 'LOCALURL:' ),
	'localurle'             => array( '0', 'ЛОКАЛЬНА_АДРЕСА_2:', 'ЛОКАЛЬНЫЙ_АДРЕС_2:', 'LOCALURLE:' ),
	'server'                => array( '0', 'СЕРВЕР', 'SERVER' ),
	'servername'            => array( '0', 'НАЗВА_СЕРВЕРА', 'НАЗВАНИЕ_СЕРВЕРА', 'SERVERNAME' ),
	'scriptpath'            => array( '0', 'ШЛЯХ_ДО_СКРИПТУ', 'ПУТЬ_К_СКРИПТУ', 'SCRIPTPATH' ),
	'grammar'               => array( '0', 'ВІДМІНОК:', 'ПАДЕЖ:', 'GRAMMAR:' ),
	'gender'                => array( '0', 'СТАТЬ:', 'ПОЛ:', 'GENDER:' ),
	'notitleconvert'        => array( '0', '__БЕЗ_ПЕРЕТВОРЕННЯ_ЗАГОЛОВКУ__', '__БЕЗ_ПРЕОБРАЗОВАНИЯ_ЗАГОЛОВКА__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'      => array( '0', '__БЕЗ_ПЕРЕТВОРЕННЯ_ТЕКСТУ__', '__БЕЗ_ПРЕОБРАЗОВАНИЯ_ТЕКСТА__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'           => array( '1', 'ПОТОЧНИЙ_ТИЖДЕНЬ', 'ТЕКУЩАЯ_НЕДЕЛЯ', 'CURRENTWEEK' ),
	'currentdow'            => array( '1', 'ПОТОЧНИЙ_ДЕНЬ_ТИЖНЯ', 'ТЕКУЩИЙ_ДЕНЬ_НЕДЕЛИ', 'CURRENTDOW' ),
	'localweek'             => array( '1', 'ЛОКАЛЬНИЙ_ТИЖДЕНЬ', 'МЕСТНАЯ_НЕДЕЛЯ', 'LOCALWEEK' ),
	'localdow'              => array( '1', 'ЛОКАЛЬНИЙ_ДЕНЬ_ТИЖНЯ', 'МЕСТНЫЙ_ДЕНЬ_НЕДЕЛИ', 'LOCALDOW' ),
	'revisionid'            => array( '1', 'ІД_ВЕРСІЇ', 'ИД_ВЕРСИИ', 'REVISIONID' ),
	'revisionday'           => array( '1', 'ДЕНЬ_ВЕРСІЇ', 'ДЕНЬ_ВЕРСИИ', 'REVISIONDAY' ),
	'revisionday2'          => array( '1', 'ДЕНЬ_ВЕРСІЇ_2', 'ДЕНЬ_ВЕРСИИ_2', 'REVISIONDAY2' ),
	'revisionmonth'         => array( '1', 'МІСЯЦЬ_ВЕРСІЇ', 'МЕСЯЦ_ВЕРСИИ', 'REVISIONMONTH' ),
	'revisionyear'          => array( '1', 'РІК_ВЕРСІЇ', 'ГОД_ВЕРСИИ', 'REVISIONYEAR' ),
	'revisiontimestamp'     => array( '1', 'МІТКА_ЧАСУ_ВЕРСІЇ', 'ОТМЕТКА_ВРЕМЕНИ_ВЕРСИИ', 'REVISIONTIMESTAMP' ),
	'revisionuser'          => array( '1', 'ВЕРСІЯ_КОРИСТУВАЧА', 'ВЕРСИЯ_УЧАСНИКА', 'REVISIONUSER' ),
	'plural'                => array( '0', 'МНОЖИНА:', 'МНОЖЕСТВЕННОЕ_ЧИСЛО:', 'PLURAL:' ),
	'fullurl'               => array( '0', 'ПОВНА_АДРЕСА:', 'ПОЛНЫЙ_АДРЕС:', 'FULLURL:' ),
	'fullurle'              => array( '0', 'ПОВНА_АДРЕСА_2:', 'ПОЛНЫЙ_АДРЕС_2:', 'FULLURLE:' ),
	'lcfirst'               => array( '0', 'НР_ПЕРША:', 'ПЕРША_БУКВА_МАЛА:', 'ПЕРВАЯ_БУКВА_МАЛЕНЬКАЯ:', 'LCFIRST:' ),
	'ucfirst'               => array( '0', 'ВР_ПЕРША:', 'ПЕРША_БУКВА_ВЕЛИКА:', 'ПЕРВАЯ_БУКВА_БОЛЬШАЯ:', 'UCFIRST:' ),
	'lc'                    => array( '0', 'НР:', 'НИЖНІЙ_РЕГІСТР:', 'МАЛИМИ_БУКВАМИ:', 'МАЛЕНЬКИМИ_БУКВАМИ:', 'LC:' ),
	'uc'                    => array( '0', 'ВР:', 'ВЕРХНІЙ_РЕГІСТР:', 'ВЕЛИКИМИ_БУКВАМИ:', 'БОЛЬШИМИ_БУКВАМИ:', 'UC:' ),
	'raw'                   => array( '0', 'НЕОБРОБ:', 'НЕОБРАБ:', 'RAW:' ),
	'displaytitle'          => array( '1', 'ПОКАЗАТИ_ЗАГОЛОВОК', 'ПОКАЗАТЬ_ЗАГОЛОВОК', 'DISPLAYTITLE' ),
	'rawsuffix'             => array( '1', 'Н', 'R' ),
	'newsectionlink'        => array( '1', '__ПОСИЛАННЯ_НА_НОВИЙ_РОЗДІЛ__', '__ССЫЛКА_НА_НОВЫЙ_РАЗДЕЛ__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'      => array( '1', '__БЕЗ_ПОСИЛАННЯ_НА_НОВИЙ_РОЗДІЛ__', '__БЕЗ_ССЫЛКИ_НА_НОВЫЙ_РАЗДЕЛ__', '__NONEWSECTIONLINK__' ),
	'currentversion'        => array( '1', 'ПОТОЧНА_ВЕРСІЯ', 'ТЕКУЩАЯ_ВЕРСИЯ', 'CURRENTVERSION' ),
	'urlencode'             => array( '0', 'ЗАКОДОВАНА_АДРЕСА:', 'ЗАКОДИРОВАННЫЙ_АДРЕС:', 'URLENCODE:' ),
	'anchorencode'          => array( '0', 'КОДУВАТИ_МІТКУ', 'КОДИРОВАТЬ_МЕТКУ', 'ANCHORENCODE' ),
	'currenttimestamp'      => array( '1', 'МІТКА_ПОТОЧНОГО_ЧАСУ', 'ОТМЕТКА_ТЕКУЩЕГО_ВРЕМЕНИ', 'CURRENTTIMESTAMP' ),
	'localtimestamp'        => array( '1', 'МІТКА_ЛОКАЛЬНОГО_ЧАСУ', 'ОТМЕТКА_МЕСТНОГО_ВРЕМЕНИ', 'LOCALTIMESTAMP' ),
	'directionmark'         => array( '1', 'НАПРЯМОК_ПИСЬМА', 'НАПРАВЛЕНИЕ_ПИСЬМА', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'              => array( '0', '#МОВА:', '#ЯЗЫК:', '#LANGUAGE:' ),
	'contentlanguage'       => array( '1', 'МОВА_ВМІСТУ', 'ЯЗЫК_СОДЕРЖАНИЯ', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'      => array( '1', 'СТОРІНОК_У_ПРОСТОРІ_НАЗВ:', 'СТРАНИЦ_В_ПРОСТРАНСТВЕ_ИМЁН:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'        => array( '1', 'КІЛЬКІСТЬ_АДМІНІСТРАТОРІВ', 'КОЛИЧЕСТВО_АДМИНИСТРАТОРОВ', 'NUMBEROFADMINS' ),
	'formatnum'             => array( '0', 'ФОРМАТУВАТИ_ЧИСЛО', 'ФОРМАТИРОВАТЬ_ЧИСЛО', 'FORMATNUM' ),
	'padleft'               => array( '0', 'ЗАПОВНИТИ_ЛІВОРУЧ', 'ЗАПОЛНИТЬ_СЛЕВА', 'PADLEFT' ),
	'padright'              => array( '0', 'ЗАПОВНИТИ_ПРАВОРУЧ', 'ЗАПОЛНИТЬ_СПРАВА', 'PADRIGHT' ),
	'special'               => array( '0', 'спеціальна', 'служебная', 'special' ),
	'defaultsort'           => array( '1', 'СТАНДАРТНЕ_СОРТУВАННЯ:', 'СОРТУВАННЯ:', 'СОРТИРОВКА_ПО_УМОЛЧАНИЮ', 'КЛЮЧ_СОРТИРОВКИ', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'              => array( '0', 'ШЛЯХ_ДО_ФАЙЛУ:', 'ПУТЬ_К_ФАЙЛУ:', 'FILEPATH:' ),
	'tag'                   => array( '0', 'тег', 'мітка', 'метка', 'тэг', 'tag' ),
	'hiddencat'             => array( '1', '__ПРИХОВ_КАТ__', '__СКРЫТАЯ_КАТЕГОРИЯ__', '__HIDDENCAT__' ),
	'pagesincategory'       => array( '1', 'СТОР_В_КАТ', 'СТОР_У_КАТ', 'СТРАНИЦ_В_КАТЕГОРИИ', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'              => array( '1', 'РОЗМІР', 'РОЗМІР_СТОРІНКИ', 'РАЗМЕР_СТРАНИЦЫ', 'PAGESIZE' ),
	'index'                 => array( '1', '__ІНДЕКС__', '__ИНДЕКС__', '__INDEX__' ),
	'noindex'               => array( '1', '__БЕЗ_ІНДЕКСУ__', '__БЕЗ_ИНДЕКСА__', '__NOINDEX__' ),
	'numberingroup'         => array( '1', 'КІЛЬКІСТЬ_У_ГРУПІ', 'ЧИСЛО_В_ГРУППЕ', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'        => array( '1', '__СТАТИЧНЕ_ПЕРЕНАПРАВЛЕННЯ__', '__СТАТИЧЕСКОЕ_ПЕРЕНАПРАВЛЕНИЕ__', '__STATICREDIRECT__' ),
	'protectionlevel'       => array( '1', 'РІВЕНЬ_ЗАХИСТУ', 'УРОВЕНЬ_ЗАЩИТЫ', 'PROTECTIONLEVEL' ),
	'formatdate'            => array( '0', 'форматдати', 'форматдаты', 'formatdate', 'dateformat' ),
);

$linkTrail = '/^([a-zабвгґдеєжзиіїйклмнопрстуфхцчшщьєюяёъы“»]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'               => 'Підкреслювати посилання:',
'tog-highlightbroken'         => 'Форматувати неіснуючі посилання <a href="" class="new">ось так</a> (альтернатива: ось так<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Вирівнювати текст по ширині сторінки',
'tog-hideminor'               => 'Ховати незначні редагування у списку останніх змін',
'tog-hidepatrolled'           => 'Приховувати патрульовані редагування у списку нових редагувань',
'tog-newpageshidepatrolled'   => 'Приховувати патрульовані сторінки у списку нових сторінок',
'tog-extendwatchlist'         => 'Розширений список спостереження, що включає всі зміни, а не лише останню',
'tog-usenewrc'                => 'Використовувати покращений список останніх змін (JavaScript)',
'tog-numberheadings'          => 'Автоматично нумерувати заголовки',
'tog-showtoolbar'             => 'Показувати панель інструментів при редагуванні (JavaScript)',
'tog-editondblclick'          => 'Редагувати сторінки при подвійному клацанні мишкою (JavaScript)',
'tog-editsection'             => 'Показувати посилання [ред.] для кожного розділу',
'tog-editsectiononrightclick' => 'Редагувати розділи при клацанні правою кнопкою мишки на заголовку (JavaScript)',
'tog-showtoc'                 => 'Показувати зміст (для сторінок з більш ніж трьома заголовками)',
'tog-rememberpassword'        => "Запам'ятати мій обліковий запис на цьому комп'ютері (на строк не більше $1 {{PLURAL:$1|дня|днів}})",
'tog-watchcreations'          => 'Додавати створені мною сторінки до мого списку спостереження',
'tog-watchdefault'            => 'Додавати змінені мною сторінки до мого списку спостереження',
'tog-watchmoves'              => 'Додавати перейменовані мною сторінки до мого списку спостереження',
'tog-watchdeletion'           => 'Додавати вилучені мною сторінки до мого списку спостереження',
'tog-previewontop'            => 'Показувати попередній перегляд перед вікном редагування, а не після',
'tog-previewonfirst'          => 'Показувати попередній перегляд при першому редагуванні',
'tog-nocache'                 => 'Відключити кешування сторінок браузером',
'tog-enotifwatchlistpages'    => 'Повідомляти електронною поштою, коли сторінка з мого списку спостереження змінилася',
'tog-enotifusertalkpages'     => 'Повідомляти електронною поштою про зміну моєї сторінки обговорення',
'tog-enotifminoredits'        => 'Надсилати мені електронного листа навіть при незначних редагуваннях',
'tog-enotifrevealaddr'        => 'Показувати мою поштову адресу в повідомленнях',
'tog-shownumberswatching'     => 'Показувати кількість користувачів, які додали сторінку до свого списку спостереження',
'tog-oldsig'                  => 'Попередній перегляд вашого підпису:',
'tog-fancysig'                => 'Власна вікі-розмітка підпису (без автоматичного посилання)',
'tog-externaleditor'          => "Використовувати зовнішній редактор за умовчанням (тільки для досвідчених користувачів, вимагає спеціальних налаштувань вашого комп'ютера)",
'tog-externaldiff'            => "За умовчанням використовувати зовнішню програму порівняння версій (тільки для експертів, вимагає спеціальних налаштувань вашого комп'ютера)",
'tog-showjumplinks'           => 'Активізувати допоміжні посилання «перейти до»',
'tog-uselivepreview'          => 'Використовувати швидкий попередній перегляд (JavaScript, експериментально)',
'tog-forceeditsummary'        => 'Попереджати, коли не зазначений короткий опис редагування',
'tog-watchlisthideown'        => 'Ховати мої редагування у списку спостереження',
'tog-watchlisthidebots'       => 'Ховати редагування ботів у списку спостереження',
'tog-watchlisthideminor'      => 'Ховати незначні редагування у списку спостереження',
'tog-watchlisthideliu'        => 'Ховати редагування зареєстрованих користувачів у списку спосетереження',
'tog-watchlisthideanons'      => 'Ховати редагування анонімів у списку спостереження',
'tog-watchlisthidepatrolled'  => 'Приховувати патрульовані редагування у списку спостререження',
'tog-nolangconversion'        => 'Відключити перетворення систем письма',
'tog-ccmeonemails'            => 'Відправляти мені копії листів, які я надсилаю іншим користувачам',
'tog-diffonly'                => 'Не показувати вміст сторінки під різницею версій',
'tog-showhiddencats'          => 'Показувати приховані категорії',
'tog-norollbackdiff'          => 'Не показувати різницю версій після виконання відкоту',

'underline-always'  => 'Завжди',
'underline-never'   => 'Ніколи',
'underline-default' => 'Використати налаштування браузера',

# Font style option in Special:Preferences
'editfont-style'     => 'Тип шрифту в полі редагування:',
'editfont-default'   => 'Шрифт від веб-оглядача',
'editfont-monospace' => 'Шрифт зі сталою шириною',
'editfont-sansserif' => 'Шрифт без засічок',
'editfont-serif'     => 'Шрифт із засічками',

# Dates
'sunday'        => 'неділя',
'monday'        => 'понеділок',
'tuesday'       => 'вівторок',
'wednesday'     => 'середа',
'thursday'      => 'четвер',
'friday'        => "п'ятниця",
'saturday'      => 'субота',
'sun'           => 'Нд',
'mon'           => 'Пн',
'tue'           => 'Вт',
'wed'           => 'Ср',
'thu'           => 'Чт',
'fri'           => 'Пт',
'sat'           => 'Сб',
'january'       => 'січень',
'february'      => 'лютий',
'march'         => 'березень',
'april'         => 'квітень',
'may_long'      => 'травень',
'june'          => 'червень',
'july'          => 'липень',
'august'        => 'серпень',
'september'     => 'вересень',
'october'       => 'жовтень',
'november'      => 'листопад',
'december'      => 'грудень',
'january-gen'   => 'січня',
'february-gen'  => 'лютого',
'march-gen'     => 'березня',
'april-gen'     => 'квітня',
'may-gen'       => 'травня',
'june-gen'      => 'червня',
'july-gen'      => 'липня',
'august-gen'    => 'серпня',
'september-gen' => 'вересня',
'october-gen'   => 'жовтня',
'november-gen'  => 'листопада',
'december-gen'  => 'грудня',
'jan'           => 'січ',
'feb'           => 'лют',
'mar'           => 'бер',
'apr'           => 'квіт',
'may'           => 'травень',
'jun'           => 'чер',
'jul'           => 'лип',
'aug'           => 'сер',
'sep'           => 'вер',
'oct'           => 'жов',
'nov'           => 'лис',
'dec'           => 'груд',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Категорія|Категорії}}',
'category_header'                => 'Сторінки в категорії «$1»',
'subcategories'                  => 'Підкатегорії',
'category-media-header'          => 'Файли в категорії «$1»',
'category-empty'                 => "''Ця категорія зараз порожня.''",
'hidden-categories'              => '{{PLURAL:$1|Прихована категорія|Приховані категорії}}',
'hidden-category-category'       => 'Приховані категорії',
'category-subcat-count'          => '{{PLURAL:$2|Ця категорія містить лише таку підкатегорію.|{{PLURAL:$1|Показана $1 підкатегорія|Показані $1 підкатегорії|Показані $1 підкатегорій}} із $2.}}',
'category-subcat-count-limited'  => 'У цій категорії {{PLURAL:$1|$1 підкатегорія|$1 підкатегорії|$1 підкатегорій}}.',
'category-article-count'         => '{{PLURAL:$2|Ця категорія містить тільки таку сторінку.|{{PLURAL:$1|Показана $1 сторінка|Показані $1 сторінки|Показані $1 сторінок}} цієї категорії з $2.}}',
'category-article-count-limited' => 'У цій категорії {{PLURAL:$1|$1 сторінка|$1 сторінки|$1 сторінок}}.',
'category-file-count'            => '{{PLURAL:$2|Ця категорія містить тільки такий файл.|{{PLURAL:$1|Показаний $1 файл|Показані $1 файли|Показані $1 файлів}} цієї категорії з $2.}}',
'category-file-count-limited'    => 'У цій категорії {{PLURAL:$1|$1 файл|$1 файли|$1 файлів}}.',
'listingcontinuesabbrev'         => '(прод.)',
'index-category'                 => 'Індексовані сторінки',
'noindex-category'               => 'Неіндексовані сторінки',

'linkprefix'        => '/^(.*?)(„|«)$/sD',
'mainpagetext'      => 'Програмне забезпечення «MediaWiki» успішно встановлене.',
'mainpagedocfooter' => 'Інформацію про роботу з цією вікі можна знайти в [http://meta.wikimedia.org/wiki/%D0%9F%D0%BE%D0%BC%D0%BE%D1%89%D1%8C:%D0%A1%D0%BE%D0%B4%D0%B5%D1%80%D0%B6%D0%B0%D0%BD%D0%B8%D0%B5 посібнику користувача].

== Деякі корисні ресурси ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Список налаштувань];
* [http://www.mediawiki.org/wiki/Manual:FAQ Часті питання з приводу MediaWiki];
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Розсилка повідомлень про появу нових версій MediaWiki].',

'about'         => 'Про',
'article'       => 'Стаття',
'newwindow'     => '(відкривається в новому вікні)',
'cancel'        => 'Скасувати',
'moredotdotdot' => 'Детальніше…',
'mypage'        => 'Моя особиста сторінка',
'mytalk'        => 'Моя сторінка обговорення',
'anontalk'      => 'Обговорення для цієї IP-адреси',
'navigation'    => 'Навігація',
'and'           => '&#32;і',

# Cologne Blue skin
'qbfind'         => 'Знайти',
'qbbrowse'       => 'Переглянути',
'qbedit'         => 'Редагувати',
'qbpageoptions'  => 'Налаштування сторінки',
'qbpageinfo'     => 'Інформація про сторінку',
'qbmyoptions'    => 'Мої налаштування',
'qbspecialpages' => 'Спеціальні сторінки',
'faq'            => 'Часті питання',
'faqpage'        => 'Project:Часті питання',

# Vector skin
'vector-action-addsection'       => 'Додати тему',
'vector-action-delete'           => 'Вилучити',
'vector-action-move'             => 'Перейменувати',
'vector-action-protect'          => 'Захистити',
'vector-action-undelete'         => 'Відновити',
'vector-action-unprotect'        => 'Зняти захист',
'vector-namespace-category'      => 'Категорія',
'vector-namespace-help'          => 'Сторінка довідки',
'vector-namespace-image'         => 'Файл',
'vector-namespace-main'          => 'Сторінка',
'vector-namespace-media'         => 'Медіа-сторінка',
'vector-namespace-mediawiki'     => 'Повідомлення',
'vector-namespace-project'       => 'Сторінка проекту',
'vector-namespace-special'       => 'Спеціальна сторінка',
'vector-namespace-talk'          => 'Обговорення',
'vector-namespace-template'      => 'Шаблон',
'vector-namespace-user'          => 'Сторінка користувача',
'vector-simplesearch-preference' => 'Увімкнути розширені пошукові підказки (лише для оформлення "Векторне")',
'vector-view-create'             => 'Створити',
'vector-view-edit'               => 'Редагувати',
'vector-view-history'            => 'Переглянути історію',
'vector-view-view'               => 'Читати',
'vector-view-viewsource'         => 'Переглянути код',
'actions'                        => 'Дії',
'namespaces'                     => 'Простори назв',
'variants'                       => 'Варіанти',

'errorpagetitle'    => 'Помилка',
'returnto'          => 'Повернення до сторінки «$1».',
'tagline'           => 'Матеріал з {{grammar:genitive|{{SITENAME}}}}',
'help'              => 'Довідка',
'search'            => 'Пошук',
'searchbutton'      => 'Пошук',
'go'                => 'Перейти',
'searcharticle'     => 'Перейти',
'history'           => 'Історія сторінки',
'history_short'     => 'Історія',
'updatedmarker'     => 'оновлено після мого останнього перегляду',
'info_short'        => 'Інформація',
'printableversion'  => 'Версія для друку',
'permalink'         => 'Постійне посилання',
'print'             => 'Друк',
'edit'              => 'Редагувати',
'create'            => 'Створити',
'editthispage'      => 'Редагувати цю сторінку',
'create-this-page'  => 'Створити цю сторінку',
'delete'            => 'Вилучити',
'deletethispage'    => 'Вилучити цю сторінку',
'undelete_short'    => 'Відновити $1 {{PLURAL:$1|редагування|редагування|редагувань}}',
'protect'           => 'Захистити',
'protect_change'    => 'змінити',
'protectthispage'   => 'Захистити цю сторінку',
'unprotect'         => 'Зняти захист',
'unprotectthispage' => 'Зняти захист із цієї сторінки',
'newpage'           => 'Нова сторінка',
'talkpage'          => 'Обговорити цю сторінку',
'talkpagelinktext'  => 'обговорення',
'specialpage'       => 'Спеціальна сторінка',
'personaltools'     => 'Особисті інструменти',
'postcomment'       => 'Новий розділ',
'articlepage'       => 'Переглянути статтю',
'talk'              => 'Обговорення',
'views'             => 'Перегляди',
'toolbox'           => 'Інструменти',
'userpage'          => 'Переглянути сторінку користувача',
'projectpage'       => 'Переглянути сторінку проекту',
'imagepage'         => 'Переглянути сторінку файлу',
'mediawikipage'     => 'Переглянути сторінку повідомлення',
'templatepage'      => 'Переглянути сторінку шаблону',
'viewhelppage'      => 'Отримати довідку',
'categorypage'      => 'Переглянути сторінку категорії',
'viewtalkpage'      => 'Переглянути обговорення',
'otherlanguages'    => 'Іншими мовами',
'redirectedfrom'    => '(Перенаправлено з $1)',
'redirectpagesub'   => 'Сторінка-перенаправлення',
'lastmodifiedat'    => 'Остання зміна цієї сторінки: $2, $1.',
'viewcount'         => 'Цю сторінку переглядали $1 {{PLURAL:$1|раз|рази|разів}}.',
'protectedpage'     => 'Захищена сторінка',
'jumpto'            => 'Перейти до:',
'jumptonavigation'  => 'навігація',
'jumptosearch'      => 'пошук',
'view-pool-error'   => 'Вибачте, сервери зараз перевантажені.
Надійшло дуже багато запитів на перегляд цієї сторінки.
Будь ласка, почекайте і повторіть спробу отримати доступ пізніше.

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Про {{grammar:accusative|{{SITENAME}}}}',
'aboutpage'            => 'Project:Про',
'copyright'            => 'Вміст доступний згідно з $1.',
'copyrightpage'        => '{{ns:project}}:Авторське право',
'currentevents'        => 'Поточні події',
'currentevents-url'    => 'Project:Поточні події',
'disclaimers'          => 'Відмова від відповідальності',
'disclaimerpage'       => 'Project:Відмова від відповідальності',
'edithelp'             => 'Довідка про редагування',
'edithelppage'         => 'Help:Редагування',
'helppage'             => 'Help:Довідка',
'mainpage'             => 'Головна сторінка',
'mainpage-description' => 'Головна сторінка',
'policy-url'           => 'Project:Правила',
'portal'               => 'Портал спільноти',
'portal-url'           => 'Project:Портал спільноти',
'privacy'              => 'Політика конфіденційності',
'privacypage'          => 'Project:Політика конфіденційності',

'badaccess'        => 'Помилка доступу',
'badaccess-group0' => 'Вам не дозволено виконувати цю дію.',
'badaccess-groups' => 'Дія, яку ви хотіли зробити, дозволена лише користувачам із {{PLURAL:$2|групи|груп}}: $1.',

'versionrequired'     => 'Потрібна MediaWiki версії $1',
'versionrequiredtext' => 'Для роботи з цією сторінкою потрібна MediaWiki версії $1. Див. [[Special:Version|інформацію про версії програмного забезпечення, яке використовується]].',

'ok'                      => 'Гаразд',
'pagetitle'               => '$1 — {{SITENAME}}',
'retrievedfrom'           => 'Отримано з $1',
'youhavenewmessages'      => 'Ви отримали $1 ($2).',
'newmessageslink'         => 'нові повідомлення',
'newmessagesdifflink'     => 'остання зміна',
'youhavenewmessagesmulti' => 'Ви отримали нові повідомлення на $1',
'editsection'             => 'ред.',
'editold'                 => 'ред.',
'viewsourceold'           => 'переглянути вихідний код',
'editlink'                => 'редагувати',
'viewsourcelink'          => 'переглянути вихідний код',
'editsectionhint'         => 'Редагувати розділ: $1',
'toc'                     => 'Зміст',
'showtoc'                 => 'показати',
'hidetoc'                 => 'сховати',
'thisisdeleted'           => 'Переглянути чи відновити $1?',
'viewdeleted'             => 'Переглянути $1?',
'restorelink'             => '$1 {{PLURAL:$1|вилучене редагування|вилучених редагування|вилучених редагувань}}',
'feedlinks'               => 'У вигляді:',
'feed-invalid'            => 'Неправильний тип каналу для підписки.',
'feed-unavailable'        => 'Стрічки синдикації не доступні',
'site-rss-feed'           => '$1 — RSS-стрічка',
'site-atom-feed'          => '$1 — Atom-стрічка',
'page-rss-feed'           => '«$1» — RSS-стрічка',
'page-atom-feed'          => '«$1» — Atom-стрічка',
'feed-atom'               => 'Atom',
'feed-rss'                => 'RSS',
'red-link-title'          => '$1 (така сторінка не існує)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Стаття',
'nstab-user'      => 'Сторінка користувача',
'nstab-media'     => 'Медіа-сторінка',
'nstab-special'   => 'Спеціальна сторінка',
'nstab-project'   => 'Сторінка проекту',
'nstab-image'     => 'Файл',
'nstab-mediawiki' => 'Повідомлення',
'nstab-template'  => 'Шаблон',
'nstab-help'      => 'Сторінка довідки',
'nstab-category'  => 'Категорія',

# Main script and global functions
'nosuchaction'      => 'Такої дії нема',
'nosuchactiontext'  => 'Дія, зазначена в URL, неправильна.
Ви могли неправильно набрати URL або перейти по некоректному посиланню.
Це також може означати помилку в програмному забезпеченні {{GRAMMAR:genitive|{{SITENAME}}}}.',
'nosuchspecialpage' => 'Такої спеціальної сторінки нема',
'nospecialpagetext' => '<strong>Така спеціальна сторінка не існує.</strong>

Див. [[Special:SpecialPages|список спеціальних сторінок]].',

# General errors
'error'                => 'Помилка',
'databaseerror'        => 'Помилка бази даних',
'dberrortext'          => 'Знайдено синтаксичну помилку в запиті до бази даних.
Це може вказувати на помилку в програмному забезпеченні.
Останній запит до бази даних:
<blockquote><tt>$1</tt></blockquote>
відбувся з функції "<tt>$2</tt>".
База даних виявила помилку "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Знайдено синтаксичну помилку в запиті до бази даних.
Останній запит до бази даних:
«$1»
відбувся з функції «$2».
База даних виявила помилку «$3: $4».',
'laggedslavemode'      => 'Увага: сторінка може не містити останніх редагувань.',
'readonly'             => 'Запис до бази даних заблокований',
'enterlockreason'      => 'Зазначте причину і приблизний термін блокування',
'readonlytext'         => 'Додавання нових статей та інші зміни бази даних у даний момент заблоковані, ймовірно, через планове сервісне обслуговування бази даних, після закінчення якого буде відновлено нормальний стан.

Адміністратор, що заблокував базу, дав наступне пояснення: $1',
'missing-article'      => 'У базі даних не знайдений запитаний текст сторінки «$1» $2.

Подібна ситуація зазвичай виникає при спробі переходу по застарілому посиланню на історію змін сторінки, яка була вилучена.

Якщо справа не в цьому, то, швидше за все, ви виявили помилку у програмному забезпеченні.
Будь ласка, повідомте про це [[Special:ListUsers/sysop|адміністратора]], заначивши URL.',
'missingarticle-rev'   => '(версія № $1)',
'missingarticle-diff'  => '(Різниця: $1, $2)',
'readonly_lag'         => 'База даних автоматично заблокована від змін, доки вторинний сервер БД не синхронізується з первинним.',
'internalerror'        => 'Внутрішня помилка',
'internalerror_info'   => 'Внутрішня помилка: $1',
'fileappenderrorread'  => 'Не вдалося прочитати "$1" під час додавання.',
'fileappenderror'      => 'Не вдалося приєднати «$1» до «$2».',
'filecopyerror'        => 'Неможливо скопіювати файл «$1» в «$2».',
'filerenameerror'      => 'Неможливо перейменувати файл «$1» в «$2».',
'filedeleteerror'      => 'Неможливо вилучити файл «$1».',
'directorycreateerror' => 'Неможливо створити директорію «$1».',
'filenotfound'         => 'Неможливо знайти файл «$1».',
'fileexistserror'      => 'Неможливо записати до файлу «$1»: файл існує.',
'unexpected'           => 'Неочікуване значення: «$1»=«$2».',
'formerror'            => 'Помилка: неможливо передати дані форми',
'badarticleerror'      => 'Ця дія не може бути виконана на цій сторінці.',
'cannotdelete'         => 'Неможливо вилучити сторінку або файл "$1".
Можливо, її (його) вже вилучив хтось інший.',
'badtitle'             => 'Неприпустима назва',
'badtitletext'         => 'Запитана назва сторінки неправильна, порожня, або неправильно зазначена міжмовна чи міжвікі назва.
Можливо, в назві використовуються недопустимі символи.',
'perfcached'           => 'Наступні дані взяті з кешу і можуть бути застарілими:',
'perfcachedts'         => 'Наступні дані взяті з кешу, востаннє він оновлювався о $1.',
'querypage-no-updates' => 'Зміни цієї сторінки зараз заборонені. Дані тут не можуть бути оновлені зараз.',
'wrong_wfQuery_params' => 'Неприпустима параметри функцій wfQuery()<br />
Функція: $1<br />
Запит: $2',
'viewsource'           => 'Перегляд',
'viewsourcefor'        => 'Сторінка «$1»',
'actionthrottled'      => 'Обмеження за швидкістю',
'actionthrottledtext'  => 'Як захід боротьби зі спамом, установлено обмеження на багаторазове застосування цієї дії протягом короткого проміжку часу. Будь ласка, повторіть спробу через кілька хвилин.',
'protectedpagetext'    => 'Ця сторінка закрита для редагування.',
'viewsourcetext'       => 'Ви можете переглянути та скопіювати початковий текст цієї сторінки:',
'protectedinterface'   => 'Ця сторінка є частиною інтерфейсу програмного забезпечення і її можуть редагувати лише адміністратори проекту.',
'editinginterface'     => "'''Увага:''' Ви редагуєте сторінку, що є частиною текстового інтерфейсу. Зміни цієї сторінки викличуть зміну інтерфейсу для інших користувачів. Для перекладу повідомлення використовуйте [http://translatewiki.net/wiki/Main_Page?setlang=uk translatewiki.net] — проект, що займається локалізацією MediaWiki.",
'sqlhidden'            => '(SQL запит приховано)',
'cascadeprotected'     => 'Сторінка захищена від змін, оскільки її включено до {{PLURAL:$1|сторінки, для якої|наступних сторінок, для яких}} установлено каскадний захист: $2',
'namespaceprotected'   => 'У вас нема дозволу редагувати сторінки в просторі назв «$1».',
'customcssjsprotected' => 'У вас нема дозволу редагувати цю сторінку, бо вона містить особисті налаштування іншого користувача.',
'ns-specialprotected'  => 'Спеціальні сторінки не можна редагувати.',
'titleprotected'       => "Створення сторінки з такою назвою було заборонене користувачем [[User:$1|$1]].
Зазначена наступна причина: ''$2''.",

# Virus scanner
'virus-badscanner'     => "Помилка налаштування: невідомий сканер вірусів: ''$1''",
'virus-scanfailed'     => 'помилка сканування (код $1)',
'virus-unknownscanner' => 'невідомий антивірус:',

# Login and logout pages
'logouttext'                 => "'''Тепер ви працюєте в тому ж режимі, який був до вашого входу до системи.'''

Ви можете продовжувати використовувати {{grammar:accusative|{{SITENAME}}}} анонімно або знову [[Special:UserLogin|ввійти до системи]] як той самий або інший користувач. Деякі сторінки можуть відображатися, ніби ви ще представлені системі під іменем, щоб уникнути цього, оновіть кеш браузера.",
'welcomecreation'            => '== Вітаємо вас, $1! ==
Ваш обліковий запис створено.
Не забудьте змінити свої [[Special:Preferences|налаштування для сайту]].',
'yourname'                   => "Ім'я користувача:",
'yourpassword'               => 'Пароль:',
'yourpasswordagain'          => 'Повторний набір пароля:',
'remembermypassword'         => "Запам'ятати мій обліковий запис на цьому комп'ютері (на строк не більше $1 {{PLURAL:$1|дня|днів}})",
'yourdomainname'             => 'Ваш домен:',
'externaldberror'            => 'Сталася помилка при автентифікації за допомогою зовнішньої бази даних, або у вас недостатньо прав для внесення змін до свого зовнішнього облікового запису.',
'login'                      => 'Вхід до системи',
'nav-login-createaccount'    => 'Вхід / реєстрація',
'loginprompt'                => 'Ви повинні активувати куки (cookies) для входу до {{GRAMMAR:genitive|{{SITENAME}}}}.',
'userlogin'                  => 'Вхід / реєстрація',
'userloginnocreate'          => 'Увійти',
'logout'                     => 'Вихід із системи',
'userlogout'                 => 'Вихід із системи',
'notloggedin'                => 'Ви не ввійшли до системи',
'nologin'                    => "Ви ще не зареєструвались? '''$1'''.",
'nologinlink'                => 'Створіть обліковий запис',
'createaccount'              => 'Створити',
'gotaccount'                 => "Ви вже зареєстровані? '''$1'''.",
'gotaccountlink'             => 'Увійдіть',
'createaccountmail'          => 'електронною поштою',
'createaccountreason'        => 'Причина:',
'badretype'                  => 'Уведені вами паролі не збігаються.',
'userexists'                 => "Уведене ім'я користувача вже існує.
Оберіть інше ім'я.",
'loginerror'                 => 'Помилка при вході до системи',
'createaccounterror'         => 'Не в змозі створити обліковий запис: $1',
'nocookiesnew'               => 'Користувач зареєструвався, але не ввійшов до системи.
{{SITENAME}} використовує куки для входу до системи.
У вас куки заборонені.
Будь ласка, дозвольте їх, а потім увійдіть з вашим новим іменем користувача і паролем.',
'nocookieslogin'             => "{{SITENAME}} використовує куки (''cookies'') для входу до системи.
Ви їх вимкнули.
Будь ласка, ввімкніть куки і спробуйте знову.",
'noname'                     => "Ви зазначили неправильне ім'я користувача.",
'loginsuccesstitle'          => 'Успішний вхід до системи',
'loginsuccess'               => "'''Тепер ви працюєте {{grammar:locative|{{SITENAME}}}} під іменем $1.'''",
'nosuchuser'                 => 'Користувач з іменем «$1» не існує.
Імена користувачів регістрозалежні.
Перевірте правильність написання або скористайтеся формою нижче, щоб [[Special:UserLogin/signup|зареєструвати нового користувача]].',
'nosuchusershort'            => 'Користувач з іменем <nowiki>$1</nowiki> не існує.
Перевірте правильність написання імені.',
'nouserspecified'            => "Ви повинні зазначити ім'я користувача.",
'login-userblocked'          => 'Цей користувач заблокований. Вхід в систему не дозволений.',
'wrongpassword'              => 'Ви ввели хибний пароль. Спробуйте ще раз.',
'wrongpasswordempty'         => 'Ви не ввели пароль. Будь ласка, спробуйте ще раз.',
'passwordtooshort'           => 'Ваш пароль закороткий, він має містити принаймні $1 {{PLURAL:$1|символ|символи|символів}}.',
'password-name-match'        => 'Уведений пароль має відрізнятися від імені користувача.',
'mailmypassword'             => 'Надіслати новий пароль',
'passwordremindertitle'      => "Пам'ятка пароля користувача {{grammar:genitive|{{SITENAME}}}}",
'passwordremindertext'       => 'Хтось (можливо, ви, з IP-адреси $1) зробив запит
на надсилання вам нового пароля користувача {{grammar:genitive|{{SITENAME}}}} ($4). Для користувача
«$2» створено новий пароль: <code>$3</code>. Якщо це зробили ви,
то вам слід увійти до системи, ввівши новий пароль.
Ваш тимчасовий пароль втратить силу через {{PLURAL:$5|$5 день|$5 дні|$5 днів}}.

Якщо ви не надсилали запиту на зміну пароля або якщо ви вже згадали свій пароль
і не хочете його змінювати, ви можете ігнорувати це повідомлення і
продовжувати використовувати свій старий пароль.',
'noemail'                    => 'Для користувача "$1" не вказано адресу електронної пошти.',
'noemailcreate'              => 'Вам треба вказати правильну адресу електронної пошти',
'passwordsent'               => 'Новий пароль був надісланий на адресу електронної пошти, зазначену для "$1".
Будь ласка, ввійдіть до системи після отримання пароля.',
'blocked-mailpassword'       => 'Редагування з вашої IP-адреси заборонено, заблокована також функція відновлення пароля.',
'eauthentsent'               => 'На зазначену адресу електронної пошти надісланий лист із запитом на підтвердження зміни адреси.
У листі також описані дії, які потрібно виконати для підтвердження того, що ця адреса електронної пошти справді належить вам.',
'throttled-mailpassword'     => 'Функція нагадування пароля вже використовувалась протягом {{PLURAL:$1|останньої $1 години|останніх $1 годин|останніх $1 годин}}.
Для попередження зловживань дозволено виконувати не більше одного нагадування за $1 {{PLURAL:$1|годину|години|годин}}.',
'mailerror'                  => 'Помилка при відправці пошти: $1',
'acct_creation_throttle_hit' => 'Відвідувачі з вашої IP-адреси вже створили $1 {{PLURAL:$1|обліковий запис|облікових записи|облікових записів}} за останню добу, що є максимумом для цього відрізка часу.
Таким чином, користувачі з цієї IP-адреси не можуть на цей момент створювати нових облікових записів.',
'emailauthenticated'         => 'Адресу вашої електронної пошти підтверджено $2 о $3.',
'emailnotauthenticated'      => 'Адресу вашої електронної пошти <strong>ще не підтверджено</strong>, функції вікі-двигуна роботи з ел. поштою відключені.',
'noemailprefs'               => 'Адресу електронної пошти не вказано, функції вікі роботи з ел. поштою відключені.',
'emailconfirmlink'           => 'Підтвердити адресу вашої електронної пошти',
'invalidemailaddress'        => 'Уведена адреса не може бути прийнята, бо вона не відповідає формату адрес електронної пошти.
Будь ласка, введіть коректну адресу або залиште поле порожнім.',
'accountcreated'             => 'Обліковий запис створено.',
'accountcreatedtext'         => 'Обліковий запис для $1 створено.',
'createaccount-title'        => 'Створення облікового запису для {{SITENAME}}',
'createaccount-text'         => 'Хтось створив обліковий запис «$2» на сервері проекту {{SITENAME}} ($4) з паролем «$3», зазначивши вашу адресу електронної пошти. Вам слід зайти і змінити пароль.

Проігноруйте дане повідомлення, якщо обліковий запис було створено помилково.',
'usernamehasherror'          => "Ім'я користувача не може містити символу «решітка»",
'login-throttled'            => 'Ви зробили надто багато спроб ввійти до системи.
Будь ласка, зачекайте перед повторною спробою.',
'loginlanguagelabel'         => 'Мова: $1',
'suspicious-userlogout'      => 'Ваш запит на завершення сеанса відхилений, оскільки він схожий на запит, відправлений зіпсованим веб-оглядачем або кешуючим проксі-сервером.',

# Password reset dialog
'resetpass'                 => 'Змінити пароль',
'resetpass_announce'        => 'Ви ввійшли, використовуючи тимчасовий пароль, який отримали електронною поштою. Для завершення входу до системи, ви повинні вказати новий пароль тут:',
'resetpass_header'          => 'Змінити пароль облікового запису',
'oldpassword'               => 'Старий пароль:',
'newpassword'               => 'Новий пароль:',
'retypenew'                 => 'Ще раз введіть новий пароль:',
'resetpass_submit'          => 'Установити пароль і ввійти',
'resetpass_success'         => 'Ваш пароль успішно змінено! Виконується вхід до системи…',
'resetpass_forbidden'       => 'Пароль не можна змінювати',
'resetpass-no-info'         => 'Щоб звертатися безпосередньо до цієї сторінки, вам слід увійти до системи.',
'resetpass-submit-loggedin' => 'Змінити пароль',
'resetpass-submit-cancel'   => 'Скасувати',
'resetpass-wrong-oldpass'   => 'Неправильний тимчасовий або поточний пароль.
Можливо, ви вже успішно змінили пароль або зробили запит на новий тимчасовий пароль.',
'resetpass-temp-password'   => 'Тимчасовий пароль:',

# Edit page toolbar
'bold_sample'     => 'Жирний текст',
'bold_tip'        => 'Жирний текст',
'italic_sample'   => 'Курсив',
'italic_tip'      => 'Курсив',
'link_sample'     => 'Назва посилання',
'link_tip'        => 'Внутрішнє посилання',
'extlink_sample'  => 'http://www.example.com назва посилання',
'extlink_tip'     => 'Зовнішнє посилання (не забудьте про префікс http://)',
'headline_sample' => 'Текст заголовка',
'headline_tip'    => 'Заголовок 2-го рівня',
'math_sample'     => 'Вставте сюди формулу',
'math_tip'        => 'Математична формула (LaTeX)',
'nowiki_sample'   => 'Вставляйте сюди невідформатований текст.',
'nowiki_tip'      => 'Ігнорувати вікі-форматування',
'image_tip'       => 'Файл',
'media_tip'       => 'Посилання на медіа-файл',
'sig_tip'         => 'Ваш підпис з часовою міткою',
'hr_tip'          => 'Горизонтальна лінія (використовуйте скупо)',

# Edit pages
'summary'                          => 'Короткий опис змін:',
'subject'                          => 'Тема/заголовок:',
'minoredit'                        => 'Незначна зміна',
'watchthis'                        => 'Спостерігати за цією сторінкою',
'savearticle'                      => 'Зберегти сторінку',
'preview'                          => 'Попередній перегляд',
'showpreview'                      => 'Попередній перегляд',
'showlivepreview'                  => 'Швидкий попередній перегляд',
'showdiff'                         => 'Показати зміни',
'anoneditwarning'                  => "'''Увага''': Ви не увійшли до системи. Ваша IP-адреса буде записана до історії змін цієї сторінки.",
'anonpreviewwarning'               => "''Ви не увійшли в систему. Якщо ви виконаєте збереження, то в історію сторінки буде записана ваша IP-адреса.''",
'missingsummary'                   => "'''Нагадування''': Ви не дали короткого опису змін.
Натиснувши кнопку «Зберегти» ще раз, ви збережете зміни без коментаря.",
'missingcommenttext'               => 'Будь ласка, введіть нижче ваше повідомлення.',
'missingcommentheader'             => "'''Нагадування''': ви не вказали тему/заголовок для цього коментаря.
Натиснувши кнопку «{{int:savearticle}}» ще раз, ви збережете редагування без заголовка.",
'summary-preview'                  => 'Опис буде:',
'subject-preview'                  => 'Заголовок буде:',
'blockedtitle'                     => 'Користувача заблоковано',
'blockedtext'                      => "'''Ваш обліковий запис або IP-адреса заблоковані.'''

Блокування виконане адміністратором $1.
Зазначена наступна причина: ''$2''.

* Початок блокування: $8
* Закінчення блокування: $6
* Блокування виконав: $7

Ви можете надіслати листа користувачеві $1 або будь-якому іншому [[{{MediaWiki:Grouppage-sysop}}|адміністратору]], щоб обговорити блокування.

Зверніть увагу, що ви не зможете надіслати листа адміністратору, якщо ви не зареєстровані або не підтвердили свою електронну адресу в [[Special:Preferences|особистих налаштуваннях]], а також якщо вам було заборонено надсилати листи при блокуванні.

Ваша поточна IP-адреса — $3, ідентифікатор блокування — #$5. Будь ласка, зазначайте ці дані у своїх запитах.",
'autoblockedtext'                  => "Ваша IP-адреса автоматично заблокована у зв'язку з тим, що вона раніше використовувалася кимось із заблокованих користувачів. Адміністратор ($1), що її заблокував, зазначив наступну причину блокування:

:''$2''

* Початок блокування: $8
* Закінчення блокування: $6
* Був заблокований: $7

Ви можете надіслати листа користувачеві $1 або будь-якому іншому [[{{MediaWiki:Grouppage-sysop}}|адміністратору]], щоб обговорити блокування.

Зверніть увагу, що ви не зможете надіслати листа адміністраторові, якщо ви не зареєстровані у проекті або не підтвердили свою електронну адресу в [[Special:Preferences|особистих налаштуваннях]], а також якщо вам було заборонено надсилати листи при блокуванні.

Ваша поточна IP-адреса — $3, ідентифікатор блокування — #$5. Будь ласка, зазначайте його у своїх запитах.",
'blockednoreason'                  => 'не вказано причини',
'blockedoriginalsource'            => 'Зміст сторінки «$1» наведено нижче:',
'blockededitsource'                => "Текст '''ваших редагувань''' сторінки «$1» наведено нижче:",
'whitelistedittitle'               => 'Для редагування необхідно ввійти в систему',
'whitelistedittext'                => 'Ви повинні $1 щоб редагувати сторінки.',
'confirmedittext'                  => 'Ви повинні підтвердити вашу адресу електронної пошти перед редагуванням сторінок.
Будь-ласка зазначте і підтвердіть вашу електронну адресу на [[Special:Preferences|сторінці налаштувань]].',
'nosuchsectiontitle'               => 'Не вдається знайти розділ',
'nosuchsectiontext'                => 'Ви намагаєтесь редагувати розділ, якого не існує.
Можливо, він був перейменований або вилучений, поки ви переглядали сторінку.',
'loginreqtitle'                    => 'Необхідно ввійти до системи',
'loginreqlink'                     => 'ввійти в систему',
'loginreqpagetext'                 => 'Ви повинні $1, щоб переглянути інші сторінки.',
'accmailtitle'                     => 'Пароль надіслано.',
'accmailtext'                      => "Пароль для користувача [[User talk:$1|$1]], згенерований випадковим чином, надісланий на адресу $2.

Після реєстрації в системі ви зможете ''[[Special:ChangePassword|змінити пароль]]''.",
'newarticle'                       => '(Нова)',
'newarticletext'                   => "Ви перейшли на сторінку, яка поки що не існує.

Щоб створити нову сторінку, наберіть текст у вікні нижче (див. [[{{MediaWiki:Helppage}}|довідкову статтю]], щоб отримати більше інформації).
Якщо ви опинились тут помилково, просто натисніть кнопку браузера '''назад'''.",
'anontalkpagetext'                 => "----''Це сторінка обговорення, що належить анонімному користувачу, який ще не зареєструвався або не скористався зареєстрованим ім'ям.
Тому ми вимушені використовувати IP-адресу для його ідентифікації.
Одна IP-адреса може використовуватися декількома користувачами.
Якщо ви — анонімний користувач і вважаєте, що отримали коментарі, адресовані не вам, будь ласка [[Special:UserLogin/signup|зареєструйтесь]] або [[Special:UserLogin|увійдіть до системи]], щоб у майбутньому уникнути можливої плутанини з іншими анонімними користувачами.''",
'noarticletext'                    => 'Зараз на цій сторінці нема тексту.
Ви можете [[Special:Search/{{PAGENAME}}|пошукати цю назву]] в інших сторінках,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} пошукати в журналах]
або [{{fullurl:{{FULLPAGENAME}}|action=edit}} створити сторінку з такою назвою]</span>.',
'noarticletext-nopermission'       => 'Зараз на цій сторінці відсутній текст.
Ви можете [[Special:Search/{{PAGENAME}}|пошукати такий заголовок]] серед інших сторінок,
або <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} пошукати пов\'язані записи в журналах]</span>.',
'userpage-userdoesnotexist'        => 'Користувач під назвою "$1" не зареєстрований. Переконайтеся, що ви хочете створити/редагувати цю сторінку.',
'userpage-userdoesnotexist-view'   => 'Обліковий запис користувача „$1“ не зареєстровано.',
'blocked-notice-logextract'        => 'Цей користувач наразі заблокований.
Останній запис у журналі блокувань такий:',
'clearyourcache'                   => "'''Зауваження:''' Після зберігання ви маєте відновити кеш вашого браузера, щоб побачити зміни. '''Mozilla / Firefox / Safari:''' тримайте ''Shift'', коли натискаєте ''Reload'', або натисніть ''Ctrl-Shift-R'' (''Cmd-Shift-R'' на Apple Mac); '''IE:''' тримайте ''Ctrl'', коли натискаєте ''Refresh'', або натисніть ''Ctrl-F5''; '''Konqueror:''': натисніть кнопку ''Reload'', або натисніть ''F5''; '''Opera:''' користувачам може знадобитись повністю очистити кеш у ''Tools→Preferences''.",
'usercssyoucanpreview'             => "'''Підказка:''' використовуйте кнопку «{{int:showpreview}}», щоб протестувати ваш новий css-файл перед збереженням.",
'userjsyoucanpreview'              => "'''Підказка:''' використовуйте кнопку «{{int:showpreview}}», щоб протестувати ваш новий код JavaScript перед збереженням.",
'usercsspreview'                   => "'''Пам'ятайте, що це лише попередній перегляд вашого css-файлу.'''
'''Його ще не збережено!'''",
'userjspreview'                    => "'''Пам'ятайте, що це тільки попередній перегляд вашого JavaScript-файлу і поки він ще не збережений!'''",
'userinvalidcssjstitle'            => "'''Увага:''' тема оформлення «$1» не знайдена.
Пам'ятайте, що користувацькі .css та .js сторінки повинні мати назву, що складається лише з малих літер, наприклад «{{ns:user}}:Хтось/monobook.css», а не «{{ns:user}}:Хтось/Monobook.css».",
'updated'                          => '(Оновлена)',
'note'                             => "'''Зауваження:'''",
'previewnote'                      => "'''Це лише попередній перегляд,
текст ще не збережений!'''",
'previewconflict'                  => 'Цей попередній перегляд відображає текст з верхнього вікна редагування так, як він буде виглядіти, якщо ви вирішите зберегти його.',
'session_fail_preview'             => "'''Система не може зберегти ваші редагування, оскільки втрачені дані сеансу. Будь ласка, повторіть вашу спробу.
Якщо помилка буде повторюватись, спробуйте [[Special:UserLogout|вийти з системи]] і зайти знову.'''",
'session_fail_preview_html'        => "<strong>Вибачте! Неможливо зберегти ваші зміни через втрату даних HTML-сесії.</strong>

''Оскільки {{SITENAME}} дозволяє використовувати чистий HTML, попередній перегляд відключено, щоб попередити JavaScript-атаки.''

<strong>Якщо це доброякісна спроба редагування, будь ласка, спробуйте ще раз. Якщо не вийде знову, - спробуйте [[Special:UserLogout|завершити сеанс роботи]] й ще раз ввійти до системи.</strong>",
'token_suffix_mismatch'            => "'''Ваше редагування було відхилене, оскільки ваша програма не правильно обробляє знаки пунктуації у вікні редагування. Редагування було скасоване для запобігання спотворенню тексту статті.
Подібні проблеми можуть виникати при використанні анонімізуючих веб-проксі, що містять помилки.'''",
'editing'                          => 'Редагування $1',
'editingsection'                   => 'Редагування $1 (розділ)',
'editingcomment'                   => 'Редагування $1 (новий розділ)',
'editconflict'                     => 'Конфлікт редагування: $1',
'explainconflict'                  => 'Ще хтось змінив цю сторінку з того часу, як ви розпочали її змінювати.
У верхньому вікні показано поточний текст сторінки.
Ваші зміни показані в нижньому вікні.
Вам необхідно перенести ваші зміни в існуючий текст.
Якщо ви натиснете «Зберегти сторінку», то буде збережено <b>тільки</b> текст у верхньому вікні редагування.',
'yourtext'                         => 'Ваш текст',
'storedversion'                    => 'Збережена версія',
'nonunicodebrowser'                => "'''ПОПЕРЕДЖЕННЯ: Ваш [[браузер]] не підтримує кодування [[Юнікод]]. При редагуванні статей всі не-ASCII символи будуть замінені на свої шіснадцяткові коди.'''",
'editingold'                       => "'''ПОПЕРЕДЖЕННЯ: Ви редагуєте застарілу версію даної статті.
Якщо ви збережете її, будь-які редагування, зроблені між версіями, будуть втрачені.'''",
'yourdiff'                         => 'Відмінності',
'copyrightwarning'                 => "Зверніть увагу, що будь-які додавання і зміни до {{grammar:genitive|{{SITENAME}}}} розглядаються як випущені на умовах ліцензії $2 (див. $1).
Якщо ви не бажаєте, щоб написане вами безжалісно редагувалось і розповсюджувалося за бажанням будь-кого, не пишіть тут.<br />
Ви також підтверджуєте, що написане вами тут належить вам або взяте з джерела, що є суспільним надбанням чи подібним вільним джерелом.
'''НЕ ПУБЛІКУЙТЕ ТУТ БЕЗ ДОЗВОЛУ МАТЕРІАЛИ, ЩО ОХОРОНЯЮТЬСЯ АВТОРСЬКИМ ПРАВОМ!'''",
'copyrightwarning2'                => "Будь ласка, зверніть увагу, що всі внесені вами зміни можуть редагуватися, доповнюватися або вилучатися іншими користувачами.
Якщо ви не бажаєте, щоб написане вами безжалісно редагувалось — не пишіть тут.<br />
Ви також зобов'язуєтесь, що написане вами тут належить вам або взяте з джерела, що є суспільним надбанням, або подібного вільного джерела (див. $1).<br />
'''НЕ ПУБЛІКУЙТЕ ТУТ БЕЗ ДОЗВОЛУ МАТЕРІАЛИ, ЩО Є ОБ'ЄКТОМ АВТОРСЬКОГО ПРАВА!'''",
'longpagewarning'                  => "'''ПОПЕРЕДЖЕННЯ: Довжина цієї сторінки $1 кб;
сторінки, розмір яких перевищує 32&nbsp;кб, можуть створювати проблеми для деяких браузерів.
Будь ласка, розгляньте варіанти розбиття сторінки на менші частини.'''",
'longpageerror'                    => "'''ПОМИЛКА: текст, що ви хочете зберегти має $1 кілобайт, що більше ніж встановлену межу $2 кілобайт. Сторінку неможливо зберегти.'''",
'readonlywarning'                  => "'''ПОПЕРЕДЖЕННЯ: База даних заблокована в зв'язку з процедурами обслуговування,
тому, на даний момент, ви не можете записати ваші зміни.
Можливо, вам варто зберегти текст в локальний файл (на своєму диску) й зберегти його пізніше.'''

Адміністратор, що заблокував базу даних, залишив наступне пояснення: $1",
'protectedpagewarning'             => "'''Попередження: Ця сторінка була захищена від змін так, що тільки користувачі з правами адміністратора можуть її редагувати.'''
Останній запис журналу наведений нижче для довідки:",
'semiprotectedpagewarning'         => "'''Зауваження:''' Ця сторінка захищена так, що її можуть редагувати тільки зареєстровані користувачі.
Останній запис журналу наведений нижче для довідки:",
'cascadeprotectedwarning'          => "'''Попередження:''' Цю сторінку можуть редагувати лише користувачі з групи «Адміністратори», оскільки вона включена {{PLURAL:$1|до сторінки, для якої|до наступних сторінок, для яких}} активовано каскадний захист:",
'titleprotectedwarning'            => "'''Попередження. Ця сторінка була захищена так, що для її створення потрібні [[Special:ListGroupRights|особливі права]].'''
Останній запис журналу наведений нижче для довідки:",
'templatesused'                    => '{{PLURAL:$1|Шаблон, використаний|Шаблони, використані}} на цій сторінці:',
'templatesusedpreview'             => '{{PLURAL:$1|Шаблон, використаний|Шаблони, використані}} у цьому попередньому перегляді:',
'templatesusedsection'             => '{{PLURAL:$1|Шаблон, використаний|Шаблони, використані}} у цій секції:',
'template-protected'               => '(захищено)',
'template-semiprotected'           => '(частково захищено)',
'hiddencategories'                 => 'Ця сторінка належить до $1 {{PLURAL:$1|прихованої категорії|прихованих категорій|прихованих категорій}}:',
'edittools'                        => '<!-- Розміщений тут текст буде відображатися під формою редагування і формою завантаження. -->',
'nocreatetitle'                    => 'Створення сторінок обмежено',
'nocreatetext'                     => 'На цьому сайті обмежено можливість створення нових сторінок.
Ви можете повернуться назад й змінити існуючу сторінку, [[Special:UserLogin|ввійти в систему, або створити новий обліковий запис]].',
'nocreate-loggedin'                => 'У вас нема дозволу створювати нові сторінки.',
'sectioneditnotsupported-title'    => 'Редагування окремих розділів не підтримується',
'sectioneditnotsupported-text'     => 'На цій сторінці не підтримується редагування окремих розділів',
'permissionserrors'                => 'Помилки прав доступу',
'permissionserrorstext'            => 'У вас нема прав на виконання цієї операції з {{PLURAL:$1|наступної причини|наступних причин}}:',
'permissionserrorstext-withaction' => 'У вас нема дозволу на $2 з {{PLURAL:$1|такої причини|таких причин}}:',
'recreate-moveddeleted-warn'       => "'''Попередження: ви намагаєтеся створити сторінку, яка раніше вже була вилучена.'''

Перевірте, чи справді вам потрібно знову створювати цю сторінку.
Нижче наведений журнал вилучень і перейменувань:",
'moveddeleted-notice'              => 'Ця сторінка була вилучена.
Для довідки нижче наведені відповідні записи з журналів вилучень і перейменувань.',
'log-fulllog'                      => 'Переглянути весь журнал',
'edit-hook-aborted'                => 'Редагування скасоване процедурою-перехоплювачем.
Додаткові пояснення не наведені.',
'edit-gone-missing'                => 'Неможливо оновити сторінку.
Імовірно, вона була вилучена.',
'edit-conflict'                    => 'Конфлікт редагувань.',
'edit-no-change'                   => 'Ваше редагування відхилене, оскільки в тексті не було зроблено змін.',
'edit-already-exists'              => 'Неможливо створити нову сторінку.
Вона вже існує.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Увага: Ця сторінка містить дуже багато викликів ресурсомістких функцій.

Кількість викликів не повинна перевищувати $2, зараз потрібно зробити $1 {{PLURAL:$1|виклик|виклики|викликів}}.',
'expensive-parserfunction-category'       => 'Сторінки з дуже великою кількістю викликів ресурсомістких функцій',
'post-expand-template-inclusion-warning'  => 'Увага: розмір шаблонів для включення занадто великий.
Деякі шаблони не будуть включені.',
'post-expand-template-inclusion-category' => 'Сторінки з перевищеним розміром включених шаблонів',
'post-expand-template-argument-warning'   => 'Увага: Ця сторінка містить принаймні один аргумент шаблону, який має надто великий розмір для розгортання.
Такі аргументи були опущені.',
'post-expand-template-argument-category'  => 'Сторінки, які містять пропущені аргументи шаблонів',
'parser-template-loop-warning'            => 'Виявлена петля у шаблонах: [[$1]]',
'parser-template-recursion-depth-warning' => 'Перевищена границя глибини рекурсії шаблону ($1)',
'language-converter-depth-warning'        => 'Перевищене обмеження глибини мовного конвертора ($1)',

# "Undo" feature
'undo-success' => 'Редагування може бути скасовано.
Будь ласка, перевірте порівняння нижче, щоб впевнитисьщо це те, що ви хочете зробити, а потім збережіть зміни, щоб закінчити скасування редагування.',
'undo-failure' => 'Неможливо скасувати редагування через несумісність проміжних змін.',
'undo-norev'   => 'Редагування не може бути скасоване, бо воно не існує або було вилучене.',
'undo-summary' => 'Скасування редагування № $1 користувача [[Special:Contributions/$2|$2]] ([[User talk:$2|обговорення]])',

# Account creation failure
'cantcreateaccounttitle' => 'Неможливо створити обліковий запис',
'cantcreateaccount-text' => "Створення облікових записів із цієї IP-адреси ('''$1''') було заблоковане [[User:$3|користувачем $3]].

$3 зазначив таку причину: ''$2''",

# History pages
'viewpagelogs'           => 'Показати журнали для цієї сторінки',
'nohistory'              => 'Для цієї статті відсутній журнал редагувань.',
'currentrev'             => 'Поточна версія',
'currentrev-asof'        => 'Поточна версія на $1',
'revisionasof'           => 'Версія $1',
'revision-info'          => 'Версія від $1; $2',
'previousrevision'       => '← Старіша версія',
'nextrevision'           => 'Новіша версія →',
'currentrevisionlink'    => 'Поточна версія',
'cur'                    => 'поточн.',
'next'                   => 'наст.',
'last'                   => 'ост.',
'page_first'             => 'перша',
'page_last'              => 'остання',
'histlegend'             => "Пояснення: (поточн.) = відмінності від поточної версії,
(ост.) = відмінності від попередньої версії, '''м''' = незначне редагування",
'history-fieldset-title' => 'Переглянути історію',
'history-show-deleted'   => 'Тільки вилучені',
'histfirst'              => 'найстаріші',
'histlast'               => 'останні',
'historysize'            => '($1 {{PLURAL:$1|байт|байти|байтів}})',
'historyempty'           => '(порожньо)',

# Revision feed
'history-feed-title'          => 'Історія редагувань',
'history-feed-description'    => 'Історія редагувань цієї сторінки в вікі',
'history-feed-item-nocomment' => '$1 в $2',
'history-feed-empty'          => 'Такої сторінки не існує.
Її могли вилучити чи перейменувати.
Спробуйте [[Special:Search|знайти]] подібні сторінки.',

# Revision deletion
'rev-deleted-comment'         => '(коментар вилучено)',
'rev-deleted-user'            => "(ім'я автора стерто)",
'rev-deleted-event'           => '(запис журналу вилучений)',
'rev-deleted-user-contribs'   => "[ім'я користувача або IP-адреса вилучені — редагування приховане у внеску]",
'rev-deleted-text-permission' => "Ця версія сторінки '''вилучена'''.
Можливо є пояснення в [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} журналі вилучень].",
'rev-deleted-text-unhide'     => "Ця версія сторінки '''вилучена'''.
Можливо є пояснення в [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} журналі вилучень].
Як адміністратор ви можете [$1 переглянути цю версію].",
'rev-suppressed-text-unhide'  => "Ця версія сторінки була '''прихована'''.
Можливо, є пояснення в [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} журналі приховань].
Як адміністратор ви можете [$1 переглянути цю версію].",
'rev-deleted-text-view'       => "Ця версія сторінки '''вилучена'''.
Ви можете переглянути її, оскільки є адміністратором проекту.
Можливо, є пояснення в [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} журналі вилучень].",
'rev-suppressed-text-view'    => "Ця версія сторінки '''прихована'''.
Як адміністратор ви можете переглянути її. Докладніші дані можуть знаходитися в [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} журналі приховань].",
'rev-deleted-no-diff'         => "Ви не можете переглянути цю різницю версій, оскільки одна з версій сторінки '''вилучена'''.
Можливо, деталі можна знайти в [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} журналі вилучень].",
'rev-suppressed-no-diff'      => "Ви не можете переглянути це порівняння версій, оскільки одна з версій була '''вилучена'''.",
'rev-deleted-unhide-diff'     => "Одна з версій сторінки '''вилучена'''.
Можливо, деталі можна знайти в [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} журналі вилучень].
Ви можете [$1 переглянути цю версію], оскільки є адміністратором.",
'rev-suppressed-unhide-diff'  => 'Ця версія сторінки була прихована.
Можливо, є пояснення в [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} журналі приховань].
Як адміністратор ви можете [$1 переглянути цю різницю версій]',
'rev-deleted-diff-view'       => "Одна з версій цього порівняння версій була '''вилучена'''.
Як адміністратор ви можете переглянути це порівняння, докладніші дані можуть знаходитися в [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} журналі вилучень].",
'rev-suppressed-diff-view'    => "Одна з версій цього порівняння версій була '''прихована'''.
Як адміністратор ви можете переглянути це порівняння, докладніші дані можуть знаходитися в [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} журналі приховань].",
'rev-delundel'                => 'показати/сховати',
'rev-showdeleted'             => 'показати',
'revisiondelete'              => 'Вилучити / відновити версії сторінки',
'revdelete-nooldid-title'     => 'Не вказана цільова версія',
'revdelete-nooldid-text'      => 'Ви не вказали цільову версію (чи версії) для виконання цієї функції.',
'revdelete-nologtype-title'   => 'Не зазначений тип журналу',
'revdelete-nologtype-text'    => 'Ви не зазначили тип журналу, в якому слід виконати дію.',
'revdelete-nologid-title'     => 'Помилковий запис журналу',
'revdelete-nologid-text'      => 'Ви не зазначили цільовий запис журналу для виконання дії або зазначений запис не існує.',
'revdelete-no-file'           => 'Зазначений файл не існує.',
'revdelete-show-file-confirm' => 'Ви впевнені, що хочете переглянути вилучену версію файлу «<nowiki>$1</nowiki>» від $3 $2?',
'revdelete-show-file-submit'  => 'Так',
'revdelete-selected'          => "'''{{PLURAL:$2|Обрана версія|Обрані версії}} сторінки [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Обраний запис|Обрані записи}} журналу:'''",
'revdelete-text'              => "'''Вилучені версії сторінок і подій будуть відображатися в історії сторінки та журналах, але частина їх вмісту не буде доступною звичайним користувачам.'''
Доступ до прихованого змісту матимуть адміністратори проекту {{SITENAME}}, які зможуть відновити його за допомогою цього ж інтерфейсу,
крім випадків, коли були встановлені додаткові обмеження власниками сайту.",
'revdelete-confirm'           => 'Будь ласка, підтвердить, що ви справді бажаєте це здійснити, усвідомлюєте наслідки та робите це згідно з [[{{MediaWiki:Policy-url}}|правилами]].',
'revdelete-suppress-text'     => "Приховування може відбуватися '''лише''' в таких випадках:

* Непотрібна особиста інформація
*: ''домашні адреси, номери телефонів, номер паспорта тощо.''",
'revdelete-legend'            => 'Установити обмеження',
'revdelete-hide-text'         => 'Прихований текст цієї версії сторінки',
'revdelete-hide-image'        => 'Приховати вміст файлу',
'revdelete-hide-name'         => "Приховати дію та її об'єкт",
'revdelete-hide-comment'      => 'Приховати коментар',
'revdelete-hide-user'         => "Приховати ім'я автора",
'revdelete-hide-restricted'   => 'Приховати дані також і від адміністраторів',
'revdelete-radio-same'        => '(не змінювати)',
'revdelete-radio-set'         => 'Так',
'revdelete-radio-unset'       => 'Ні',
'revdelete-suppress'          => 'Приховувати дані також і від адміністраторів',
'revdelete-unsuppress'        => 'Зняти обмеження з відновлених версій',
'revdelete-log'               => 'Причина:',
'revdelete-submit'            => 'Застосувати до {{PLURAL:$1|обраної версії|обраних версій}}',
'revdelete-logentry'          => 'змінив видимість версії сторінки для [[$1]]',
'logdelete-logentry'          => 'змінена видимість події для [[$1]]',
'revdelete-success'           => "'''Видимість версії успішно змінена.'''",
'revdelete-failure'           => "'''Видимість версії неможливо змінити:'''
$1",
'logdelete-success'           => "'''Видимість події успішно змінена.'''",
'logdelete-failure'           => "'''Не вдалося встановити видимість журналу:'''
$1",
'revdel-restore'              => 'Змінити видимість',
'revdel-restore-deleted'      => 'вилучені версії',
'revdel-restore-visible'      => 'видимі версії',
'pagehist'                    => 'Історія сторінки',
'deletedhist'                 => 'Історія вилучень',
'revdelete-content'           => 'вміст',
'revdelete-summary'           => 'коментар до редагування',
'revdelete-uname'             => "ім'я користувача",
'revdelete-restricted'        => 'застосовані обмеження для адміністраторів',
'revdelete-unrestricted'      => 'зняті обмеження для адміністраторів',
'revdelete-hid'               => 'приховано $1',
'revdelete-unhid'             => 'розкрито $1',
'revdelete-log-message'       => '$1 для $2 {{PLURAL:$2|редагування|редагувань|редагувань}}',
'logdelete-log-message'       => '$1 для $2 {{PLURAL:$2|події|подій}}',
'revdelete-hide-current'      => 'Помилка приховування запису від $2, $1: це поточна версія.
Її не можна приховати.',
'revdelete-show-no-access'    => 'Помилка показу запису від $2, $1: він позначений як «з обмеженим доступом».
Ви не маєте доступу до нього.',
'revdelete-modify-no-access'  => 'Помилка редгування запису від $2, $1: його поначено як "з обмеженим доступом".
Ви не маєте доступу до нього.',
'revdelete-modify-missing'    => 'Помилка редагування запису з ID $1: його нема в базі даних!',
'revdelete-no-change'         => "'''Увага:''' запис від $2, $1 вже має запрошені налаштування видимості.",
'revdelete-concurrent-change' => 'Помилка редагування запису від $2, $1: його стан змінений кимось іншим, поки ви робили свої зміни.
Будь ласка, перевірте журнал.',
'revdelete-only-restricted'   => 'Помилка приховання запису від $2, $1: ви не можете приховати записи від перегляду адміністраторів без одночасного вибору однієї з інших опцій приховання.',
'revdelete-reason-dropdown'   => '* Типові причини вилучення
** Порушення авторських прав
** Недоречна особиста інформація',
'revdelete-otherreason'       => 'Інша/додаткова причина:',
'revdelete-reasonotherlist'   => 'Інша причина',
'revdelete-edit-reasonlist'   => 'Редагувати причини вилучень',
'revdelete-offender'          => 'Автор версії:',

# Suppression log
'suppressionlog'     => 'Журнал приховувань',
'suppressionlogtext' => 'Нижче наведений список останніх вилучень та блокувань, які стосуються матеріалів, прихованих від адміністраторів.
Див. [[Special:IPBlockList|список IP-блокувань]], щоб переглянути список поточних блокувань.',

# Revision move
'moverevlogentry'              => 'переніс {{PLURAL:$3|одну версію|$3 версії|$3 версій}} з $1 до $2',
'revisionmove'                 => 'Переміщення версій з "$1"',
'revmove-explain'              => 'Наступні версії сторінок будуть перенесені з $1 до вибраної сторінки. Версії буде додано до історії цільової сторінки. Якщо ж цільової сторінки не існує, її буде створено.',
'revmove-legend'               => 'Вкажіть цільову сторінку та коментар',
'revmove-submit'               => 'Перемістити версії на обрану сторінку',
'revisionmoveselectedversions' => 'Перемістити вибрані версії',
'revmove-reasonfield'          => 'Причина:',
'revmove-titlefield'           => 'Цільова сторінка:',
'revmove-badparam-title'       => 'Неприпустимі параметри',
'revmove-badparam'             => 'Ваш запит містить недопустимі значення параметрів, або параметри не вказані. Будь ласка, натисніть «назад» та спробуйте ще раз.',
'revmove-norevisions-title'    => 'Недопустима цільова версія',
'revmove-norevisions'          => 'Ви не вказали жодної цільової версії для виконання цієї дії, або вказана версія не існує.',
'revmove-nullmove-title'       => 'Неприпустима назва',
'revmove-nullmove'             => 'Назви початкової та цільової сторінок збігаються. Будь ласка, натисніть «назад» та введіть назву, відмінну від «$1».',
'revmove-success-existing'     => '{{PLURAL:$1|Одна версія сторінки [[$2]] була перенесена|$1 версії сторінки [[$2]] були перенесені|$1 версій сторінки [[$2]] було перенесено}} на існуючу сторінку [[$3]].',
'revmove-success-created'      => '{{PLURAL:$1|Одна версія сторінки [[$2]] була перенесена|$1 версії сторінки [[$2]] були перенесені|$1 версій сторінки [[$2]] було перенесено}} на новостворену сторінку [[$3]].',

# History merging
'mergehistory'                     => "Об'єднання історій редагувань",
'mergehistory-header'              => "Ця сторінка дозволяє вам об'єднати історії редагувань двох різних сторінок.
Переконайтеся, що ця зміна збереже цілісність історії сторінки.",
'mergehistory-box'                 => "Об'єднати історії редагувань двох сторінок:",
'mergehistory-from'                => 'Вихідна сторінка:',
'mergehistory-into'                => 'Цільова сторінка:',
'mergehistory-list'                => "Історія редагувань, що об'єднується",
'mergehistory-merge'               => "Наступні версії [[:$1]] можуть бути об'єднані у [[:$2]]. Використайте перемикачі для того, щоб об'єднати тільки вибраний діапазон редагувань. Врахуйте, що при використанні навігаційних посилань дані будуть втрачені.",
'mergehistory-go'                  => "Показати редагування, що об'єднуються",
'mergehistory-submit'              => "Об'єднати редагування",
'mergehistory-empty'               => "Не знайдені редагування для об'єднання.",
'mergehistory-success'             => '$3 {{PLURAL:$3|редагування|редагування|редагувань}} з [[:$1]] успішно перенесені до [[:$2]].',
'mergehistory-fail'                => "Не вдалося здійснити об'єднання історій сторінок, будь ласка, перевірте параметри сторінки й часу.",
'mergehistory-no-source'           => 'Вихідна сторінка «$1» не існує.',
'mergehistory-no-destination'      => 'Цільова сторінка «$1» не існує.',
'mergehistory-invalid-source'      => 'Джерело повинне мати правильний заголовок.',
'mergehistory-invalid-destination' => 'Цільова сторінка повинна мати правильний заголовок.',
'mergehistory-autocomment'         => 'Редагування з [[:$1]] перенесені до [[:$2]]',
'mergehistory-comment'             => 'Редагування [[:$1]] перенесені до [[:$2]]: $3',
'mergehistory-same-destination'    => 'Початкова і цільова сторінки повинні відрізнятися',
'mergehistory-reason'              => 'Причина:',

# Merge log
'mergelog'           => "Журнал об'єднань",
'pagemerge-logentry' => "об'єднані [[$1]] і [[$2]] (версії до $3)",
'revertmerge'        => 'Розділити',
'mergelogpagetext'   => "Нижче наведений список останніх об'єднань історій сторінок.",

# Diffs
'history-title'            => 'Історія змін сторінки «$1»',
'difference'               => '(відмінності між версіями)',
'lineno'                   => 'Рядок $1:',
'compareselectedversions'  => 'Порівняти вибрані версії',
'showhideselectedversions' => 'Показати/приховати обрані версії',
'editundo'                 => 'скасувати',
'diff-multi'               => '($1 {{PLURAL:$1|проміжна версія не показана|проміжні версії не показані|проміжних версій не показані}}.)',

# Search results
'searchresults'                    => 'Результати пошуку',
'searchresults-title'              => 'Результати пошуку для «$1»',
'searchresulttext'                 => 'Для отримання детальнішої інформації про пошук у проекті, див. [[{{MediaWiki:Helppage}}|розділ допомоги]].',
'searchsubtitle'                   => 'Ви шукали «[[:$1]]» ([[Special:Prefixindex/$1|усі сторінки, що починаються на «$1»]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|усі сторінки, що мають посилання на «$1»]])',
'searchsubtitleinvalid'            => 'На запит «$1»',
'toomanymatches'                   => 'Знайдено дуже багато відповідностей, будь ласка, спробуйте інший запит',
'titlematches'                     => 'Збіги в назвах сторінок',
'notitlematches'                   => 'Нема збігів у назвах сторінок',
'textmatches'                      => 'Збіги в текстах сторінок',
'notextmatches'                    => 'Немає збігів у текстах сторінок',
'prevn'                            => '{{PLURAL:$1|попередня $1|попередні $1|попередні $1}}',
'nextn'                            => 'наступні {{PLURAL:$1|$1}}',
'prevn-title'                      => '{{PLURAL:$1|Попередній $1 запис|Попередні $1 записи|Попередні $1 записів}}',
'nextn-title'                      => '{{PLURAL:$1|Наступний $1 запис|Наступні $1 записи|Наступні $1 записів}}',
'shown-title'                      => 'Показувати $1 {{PLURAL:$1|запис|записи|записів}} на сторінці',
'viewprevnext'                     => 'Переглянути ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => 'Параметри пошуку',
'searchmenu-exists'                => "'''У цій вікі є сторінка з назвою «[[$1]]»'''",
'searchmenu-new'                   => "'''Створити сторінку «[[:$1]]» у цій вікі!'''",
'searchhelp-url'                   => 'Help:Довідка',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Показати сторінки з цим префіксом]]',
'searchprofile-articles'           => 'Статті',
'searchprofile-project'            => 'Сторінки довідки і проекту',
'searchprofile-images'             => 'Мультимедіа',
'searchprofile-everything'         => 'Усюди',
'searchprofile-advanced'           => 'Вибірково',
'searchprofile-articles-tooltip'   => 'Пошук у $1',
'searchprofile-project-tooltip'    => 'Пошук у $1',
'searchprofile-images-tooltip'     => 'Пошук файлів',
'searchprofile-everything-tooltip' => 'Пошук на всіх сторінках (включаючи сторінки обговорення)',
'searchprofile-advanced-tooltip'   => 'Шукати в заданих просторах назв',
'search-result-size'               => '$1 ($2 {{PLURAL:$2|слово|слова|слів}})',
'search-result-category-size'      => '{{PLURAL:$1|$1 елемент|$1 елементи|$1 елементів}} ({{PLURAL:$2|$2 підкатегорія|$2 підкатегорії|$2 підкатегорій}}, {{PLURAL:$3|$3 файл|$3 файли|$3 файлів}})',
'search-result-score'              => 'Відповідність: $1 %',
'search-redirect'                  => '(перенаправлення $1)',
'search-section'                   => '(розділ $1)',
'search-suggest'                   => 'Можливо, ви мали на увазі: $1',
'search-interwiki-caption'         => 'Братні проекти',
'search-interwiki-default'         => '$1 результати:',
'search-interwiki-more'            => '(більше)',
'search-mwsuggest-enabled'         => 'з порадами',
'search-mwsuggest-disabled'        => 'без порад',
'search-relatedarticle'            => "Пов'язаний",
'mwsuggest-disable'                => 'Вимкнути поради AJAX',
'searcheverything-enable'          => 'Пошук у всіх просторах назв',
'searchrelated'                    => "пов'язаний",
'searchall'                        => 'усі',
'showingresults'                   => "Нижче {{PLURAL:$1|показане|показані|показані}} '''$1''' {{PLURAL:$1|результат|результати|результатів}}, починаючи з №&nbsp;'''$2'''",
'showingresultsnum'                => 'Нижче показано <strong>$3</strong> {{PLURAL:$3|результат|результати|результатів}}, починаючи з №&nbsp;<strong>$2</strong>.',
'showingresultsheader'             => "{{PLURAL:$5|Результат '''$1''' з '''$3'''|Результати '''$1 — $2''' з '''$3'''}} для '''$4'''",
'nonefound'                        => "'''Зауваження:''' За умовчанням пошук відбувається не в усіх просторах назв. Використовуйте префікс ''all:'', щоб шукати у всіх просторах назв (у т.ч. сторінки обговорень, шаблони тощо), або зазначте потрібний простір назв.",
'search-nonefound'                 => 'Не знайдено результатів, що відповідають запиту.',
'powersearch'                      => 'Розширений пошук',
'powersearch-legend'               => 'Розширений пошук',
'powersearch-ns'                   => 'Пошук у просторах назв:',
'powersearch-redir'                => 'Показувати перенаправлення',
'powersearch-field'                => 'Шукати',
'powersearch-togglelabel'          => 'Позначити:',
'powersearch-toggleall'            => 'Усі',
'powersearch-togglenone'           => 'Жодний',
'search-external'                  => 'Зовнішній пошук',
'searchdisabled'                   => '<p>Вибачте, повнотекстовий пошук тимчасово недоступний через перевантаження сервера; передбачається, що ця функція буде знову включена після установки нового обладнання. Поки що ми пропонуємо вам скористатися Google чи Yahoo!:</p>',

# Quickbar
'qbsettings'               => 'Панель навігації',
'qbsettings-none'          => 'Не показувати панель',
'qbsettings-fixedleft'     => 'Фіксована ліворуч',
'qbsettings-fixedright'    => 'Фіксована праворуч',
'qbsettings-floatingleft'  => 'Плаваюча ліворуч',
'qbsettings-floatingright' => 'Плаваюча праворуч',

# Preferences page
'preferences'                   => 'Налаштування',
'mypreferences'                 => 'Налаштування',
'prefs-edits'                   => 'Кількість редагувань:',
'prefsnologin'                  => 'Ви не ввійшли в систему',
'prefsnologintext'              => 'Щоб змінити налаштування користувача, ви повинні <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} ввійти до системи]</span>.',
'changepassword'                => 'Змінити пароль',
'prefs-skin'                    => 'Оформлення',
'skin-preview'                  => 'Попередній перегляд',
'prefs-math'                    => 'Відображення формул',
'datedefault'                   => 'Стандартний',
'prefs-datetime'                => 'Дата й час',
'prefs-personal'                => 'Особисті дані',
'prefs-rc'                      => 'Сторінка останніх редагувань',
'prefs-watchlist'               => 'Список спостереження',
'prefs-watchlist-days'          => 'Кількість днів, що відображаються у списку спостережень:',
'prefs-watchlist-days-max'      => '(максимум 7 днів)',
'prefs-watchlist-edits'         => 'Кількість редагувань для відображення у розширеному списку спостереження:',
'prefs-watchlist-edits-max'     => '(максимальне число: 1000)',
'prefs-watchlist-token'         => 'Позначка списку спостереження:',
'prefs-misc'                    => 'Інші налаштування',
'prefs-resetpass'               => 'Змінити пароль',
'prefs-email'                   => 'Параметри електронної пошти',
'prefs-rendering'               => 'Зовнішній вигляд',
'saveprefs'                     => 'Зберегти',
'resetprefs'                    => 'Скасувати незбережені зміни',
'restoreprefs'                  => 'Відновити всі стандартні налаштування',
'prefs-editing'                 => 'Редагування',
'prefs-edit-boxsize'            => 'Розмір вікна редагування.',
'rows'                          => 'Рядків:',
'columns'                       => 'Колонок:',
'searchresultshead'             => 'Пошук',
'resultsperpage'                => 'Кількість результатів на сторінку:',
'contextlines'                  => 'Кількість рядків на результат',
'contextchars'                  => 'Кількість символів контексту на рядок',
'stub-threshold'                => 'Поріг для визначення оформлення <a href="#" class="stub">посилань на стаби</a> (у байтах):',
'stub-threshold-disabled'       => 'Вимкнений',
'recentchangesdays'             => 'На скільки днів показувати нові редагування:',
'recentchangesdays-max'         => '(максимум $1 {{PLURAL:$1|день|дні|днів}})',
'recentchangescount'            => 'Кількість редагувань для показу за умовчанням:',
'prefs-help-recentchangescount' => 'Це торкається нових редагувань, історій сторінок і журналів.',
'prefs-help-watchlist-token'    => 'Заповнення цього поля секретним ключем буде створювати RSS-трансляцію для вашого списку спостереження.
Кожен, хто знає ключ в цьому полі, зможе читати ваш список спостереження, тому оберіть секретне значення.
Ви можете використати це випадково згенероване значення: $1',
'savedprefs'                    => 'Ваші налаштування збережено.',
'timezonelegend'                => 'Часовий пояс:',
'localtime'                     => 'Місцевий час:',
'timezoneuseserverdefault'      => 'Використовувати налаштування сервера',
'timezoneuseoffset'             => 'Інше (зазначте зміщення)',
'timezoneoffset'                => 'Зміщення¹:',
'servertime'                    => 'Час сервера:',
'guesstimezone'                 => 'Заповнити з браузера',
'timezoneregion-africa'         => 'Африка',
'timezoneregion-america'        => 'Америка',
'timezoneregion-antarctica'     => 'Антарктика',
'timezoneregion-arctic'         => 'Арктика',
'timezoneregion-asia'           => 'Азія',
'timezoneregion-atlantic'       => 'Атлантичний океан',
'timezoneregion-australia'      => 'Австралія',
'timezoneregion-europe'         => 'Європа',
'timezoneregion-indian'         => 'Індійський океан',
'timezoneregion-pacific'        => 'Тихий океан',
'allowemail'                    => 'Дозволити електронну пошту від інших користувачів',
'prefs-searchoptions'           => 'Параметри пошуку',
'prefs-namespaces'              => 'Простори назв',
'defaultns'                     => 'Інакше шукати в таких просторах назв:',
'default'                       => 'за умовчанням',
'prefs-files'                   => 'Файли',
'prefs-custom-css'              => 'Власний CSS',
'prefs-custom-js'               => 'Власний JS',
'prefs-common-css-js'           => 'CSS/JS спільні для всіх тем оформлення:',
'prefs-reset-intro'             => 'Ця сторінка може бути використана для зміни ваших налаштувань на стандартні.
Після виконання цієї дії ви не зможете відкотити зміни.',
'prefs-emailconfirm-label'      => 'Підтвердження електронної пошти:',
'prefs-textboxsize'             => 'Розмір вікна редагування',
'youremail'                     => 'Адреса електронної пошти:',
'username'                      => "Ім'я користувача:",
'uid'                           => 'Ідентифікатор користувача:',
'prefs-memberingroups'          => 'Член {{PLURAL:$1|групи|груп}}:',
'prefs-registration'            => 'Час реєстрації:',
'yourrealname'                  => "Справжнє ім'я:",
'yourlanguage'                  => 'Мова інтерфейсу:',
'yourvariant'                   => 'Варіант мови:',
'yournick'                      => 'Підпис:',
'prefs-help-signature'          => 'Репліки на сторінках обговорення слід підписувати символами "<nowiki>~~~~</nowiki>", які будуть перетворені у ваш підпис і час.',
'badsig'                        => 'Неправильний підпис.
Перевірте коректність HTML-тегів.',
'badsiglength'                  => 'Ваш підпис дуже довгий.
Повинно бути не більше $1 {{PLURAL:$1|символу|символів|символів}}.',
'yourgender'                    => 'Стать:',
'gender-unknown'                => 'не зазначена',
'gender-male'                   => 'чоловіча',
'gender-female'                 => 'жіноча',
'prefs-help-gender'             => "Необов'язкове поле: використовується в деяких повідомленнях двигуна, які залежать від статі користувача. Значення параметра загальнодоступне.",
'email'                         => 'Електронна пошта',
'prefs-help-realname'           => "Справжнє ім'я необов'язково вказувати.
Якщо ви його зазначите, то воно буде використовуватися, щоб показати, хто редагував сторінку.",
'prefs-help-email'              => "Адреса електронної пошти (необов'язкове поле) дає можливість надіслати вам новий пароль у випадку, якщо ви забули поточний.
Також вона дозволить іншим користувачам за допомогою вашої сторінки у вікі зв'язатися з вами, не знаючи вашої електронної адреси.",
'prefs-help-email-required'     => 'Потрібно зазначити адресу електронної пошти.',
'prefs-info'                    => 'Основні відомості',
'prefs-i18n'                    => 'Інтернаціоналізація',
'prefs-signature'               => 'Підпис',
'prefs-dateformat'              => 'Формат дати',
'prefs-timeoffset'              => 'Зсув часу',
'prefs-advancedediting'         => 'Розширені налаштування',
'prefs-advancedrc'              => 'Розширені налаштування',
'prefs-advancedrendering'       => 'Розширені налаштування',
'prefs-advancedsearchoptions'   => 'Розширені налаштування',
'prefs-advancedwatchlist'       => 'Розширені налаштування',
'prefs-displayrc'               => 'Налаштування показу',
'prefs-displaysearchoptions'    => 'Налаштування показу',
'prefs-displaywatchlist'        => 'Налаштування показу',
'prefs-diffs'                   => 'Різниці версій',

# User rights
'userrights'                   => 'Управління правами користувачів',
'userrights-lookup-user'       => 'Управління групами користувача',
'userrights-user-editname'     => "Введіть ім'я користувача:",
'editusergroup'                => 'Редагувати групи користувача',
'editinguser'                  => "Зміна прав користувача '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'Змінити групи користувача',
'saveusergroups'               => 'Зберегти групи користувача',
'userrights-groupsmember'      => 'Член груп:',
'userrights-groupsmember-auto' => 'Неявний член:',
'userrights-groups-help'       => 'Ви можете змінити групи, до яких належить цей користувач:
* Якщо біля назви групи стоїть позначка, то користувач належить до цієї групи.
* Якщо позначка не стоїть — користувач не належить до відповідної групи.
* Зірочка означає, що ви не можете вилучити користувача з групи, якщо додасте його до неї, і навпаки.',
'userrights-reason'            => 'Причина:',
'userrights-no-interwiki'      => 'У вас нема дозволу змінювати права користувачів на інших вікі.',
'userrights-nodatabase'        => 'База даних $1 не існує або не є локальною.',
'userrights-nologin'           => 'Ви повинні [[Special:UserLogin|ввійти до системи]] з обліковим записом адміністратора, щоб призначати права користувачам.',
'userrights-notallowed'        => 'Із вашого облікового запису не дозволено призначати права користувачам.',
'userrights-changeable-col'    => 'Групи, які ви можете змінити',
'userrights-unchangeable-col'  => 'Групи, які ви не можете змінити',

# Groups
'group'               => 'Група:',
'group-user'          => 'Користувачі',
'group-autoconfirmed' => 'Автопідтверджені користувачі',
'group-bot'           => 'Боти',
'group-sysop'         => 'Адміністратори',
'group-bureaucrat'    => 'Бюрократи',
'group-suppress'      => 'Ревізори',
'group-all'           => '(всі)',

'group-user-member'          => 'користувач',
'group-autoconfirmed-member' => 'автопідтверджений користувач',
'group-bot-member'           => 'бот',
'group-sysop-member'         => 'адміністратор',
'group-bureaucrat-member'    => 'бюрократ',
'group-suppress-member'      => 'ревізор',

'grouppage-user'          => '{{ns:project}}:Користувачі',
'grouppage-autoconfirmed' => '{{ns:project}}:Автопідтверджені користувачі',
'grouppage-bot'           => '{{ns:project}}:Боти',
'grouppage-sysop'         => '{{ns:project}}:Адміністратори',
'grouppage-bureaucrat'    => '{{ns:project}}:Бюрократи',
'grouppage-suppress'      => '{{ns:project}}:Ревізори',

# Rights
'right-read'                  => 'Перегляд сторінок',
'right-edit'                  => 'Редагування сторінок',
'right-createpage'            => 'Створення сторінок (але не обговорень)',
'right-createtalk'            => 'Створення обговорень сторінок',
'right-createaccount'         => 'Створення нових облікових записів',
'right-minoredit'             => 'Позначення редагувань як незначні',
'right-move'                  => 'Перейменування сторінок',
'right-move-subpages'         => 'Перейменування сторінок і їх підсторінок',
'right-move-rootuserpages'    => 'Перейменувати кореневі сторінки користувачів',
'right-movefile'              => 'перейменувати файли',
'right-suppressredirect'      => 'Нестворення перенаправлення зі старої назви на нову при перейменуванні сторінки',
'right-upload'                => 'Завантаження файлів',
'right-reupload'              => 'Перезаписування існуючих файлів',
'right-reupload-own'          => 'Перезаписування існуючих файлів, завантажених тим самим користувачем',
'right-reupload-shared'       => 'Підміна файлів зі спільного сховища локальними',
'right-upload_by_url'         => 'Завантаження файлів за URL-адресами',
'right-purge'                 => 'Очищення кешу для сторінки без сторінки підтвердження',
'right-autoconfirmed'         => 'Редагування частково захищених сторінок',
'right-bot'                   => 'Автоматична обробка',
'right-nominornewtalk'        => 'Відсутність незначних редагувань на сторінках обговорень включає режим нових повідомлень',
'right-apihighlimits'         => 'Розширення обмежень на виконання API-запитів',
'right-writeapi'              => 'Використання API для запису',
'right-delete'                => 'Вилучення сторінок',
'right-bigdelete'             => 'Вилучення сторінок з великою історією',
'right-deleterevision'        => 'Вилучення і відновлення окремих версій сторінок',
'right-deletedhistory'        => 'Перегляд історії вилучених сторінок без перегляду вилученого тексту',
'right-deletedtext'           => 'перегляд вилученого тексту та змін між вилученими версіями',
'right-browsearchive'         => 'Пошук вилучених сторінок',
'right-undelete'              => 'Відновлення сторінок',
'right-suppressrevision'      => 'Перегляд і відновлення версій, прихованих від адміністраторів',
'right-suppressionlog'        => 'Перегляд приватних журналів',
'right-block'                 => 'Блокування інших користувачів від редагувань',
'right-blockemail'            => 'Блокування користувачам надсилання електронної пошти',
'right-hideuser'              => 'Блокування імені користувача і приховування його',
'right-ipblock-exempt'        => 'Уникнення блокування за IP-адресою, автоблокування і блокування діапазонів',
'right-proxyunbannable'       => 'Уникнення автоматичного блокування проксі-серверів',
'right-unblockself'           => 'розблоковувати самого себе',
'right-protect'               => 'Зміна рівнів захисту, редагування захищених сторінок',
'right-editprotected'         => 'Редагування захищених сторінок (без каскадного захисту)',
'right-editinterface'         => 'Редагування інтерфейсу користувача',
'right-editusercssjs'         => 'Редагування CSS- і JS-файлів інших користувачів',
'right-editusercss'           => 'Редагування CSS-файлів інших користувачів',
'right-edituserjs'            => 'Редагування JS-файлів інших користувачів',
'right-rollback'              => 'Швидкий відкіт редагувань останнього користувача, який редагував сторінку',
'right-markbotedits'          => 'Позначення відкинутих редагувань як редагування бота',
'right-noratelimit'           => 'Нема обмежень за швидкістю',
'right-import'                => 'Імпорт сторінок з інших вікі',
'right-importupload'          => 'Імпорт сторінок через завантаження файлів',
'right-patrol'                => 'Позначення редагувань патрульованими',
'right-autopatrol'            => 'Автоматичне позначення редагувань патрульованими',
'right-patrolmarks'           => 'Перегляд патрульованих сторінок у нових редагуваннях',
'right-unwatchedpages'        => 'Перегляд списку сторінок, за якими ніхто не спостерігає',
'right-trackback'             => 'Надсилання Trackback',
'right-mergehistory'          => "Об'єднання історій редагувань сторінок",
'right-userrights'            => 'Зміна всіх прав користувачів',
'right-userrights-interwiki'  => 'Зміна прав користувачів у інших вікі',
'right-siteadmin'             => 'Блокування і розблокування бази даних',
'right-reset-passwords'       => 'скидання паролів інших користувачів',
'right-override-export-depth' => "експорт сторінок, включаючи пов'язані сторінки з глибиною до 5",
'right-sendemail'             => 'відправляти пошту іншим користувачам',
'right-revisionmove'          => 'Перенесення версій',
'right-selenium'              => 'Запуск перевірок Selenium',

# User rights log
'rightslog'      => 'Журнал прав користувача',
'rightslogtext'  => 'Це протокол зміни прав користувачів.',
'rightslogentry' => 'змінив права доступу для користувача $1 з $2 на $3',
'rightsnone'     => '(нема)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'перегляд цієї сторінки',
'action-edit'                 => 'редагування цієї сторінки',
'action-createpage'           => 'створення сторінок',
'action-createtalk'           => 'створення сторінок обговорень',
'action-createaccount'        => 'створення цього облікового запису',
'action-minoredit'            => 'позначення цього редагування незначним',
'action-move'                 => 'перейменування цієї сторінки',
'action-move-subpages'        => 'перейменування цієї сторінки з усіма її підсторінками',
'action-move-rootuserpages'   => 'перейменувати кореневі сторінки користувачів',
'action-movefile'             => 'перейменувати цей файл',
'action-upload'               => 'завантаження цього файлу',
'action-reupload'             => 'перезапис існуючого файлу',
'action-reupload-shared'      => 'перекривання файлу зі спільного сховища',
'action-upload_by_url'        => 'завантаження цього файлу з адреси URL',
'action-writeapi'             => 'використання API для редагувань',
'action-delete'               => 'вилучення цієї сторінки',
'action-deleterevision'       => 'вилучення цієї версії сторінки',
'action-deletedhistory'       => 'перегляд вилученої історії редагувань цієї сторінки',
'action-browsearchive'        => 'пошук вилучених сторінок',
'action-undelete'             => 'відновлення цієї сторінки',
'action-suppressrevision'     => 'перегляд і відновлення цієї прихованої версії',
'action-suppressionlog'       => 'перегляд цього приватного журналу',
'action-block'                => 'блокування цього користувача',
'action-protect'              => 'зміну рівня захисту цієї сторінки',
'action-import'               => 'імпорт цієї сторінки з іншої вікі',
'action-importupload'         => 'імпорт цієї сторінки з файлу',
'action-patrol'               => 'позначення чужих редагувань патрульованими',
'action-autopatrol'           => 'позначення власних редагувань патрульованими',
'action-unwatchedpages'       => 'перегляд списку сторінок, за якими ніхто не спостерігає',
'action-trackback'            => 'надсилання trackback',
'action-mergehistory'         => 'приєднання історії змін цієї сторінки',
'action-userrights'           => 'зміну всіх прав користувача',
'action-userrights-interwiki' => 'зміну прав користувачів у інших вікі',
'action-siteadmin'            => 'блокування і розблоковування баз даних',
'action-revisionmove'         => 'перенесення версій',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|зміна|зміни|змін}}',
'recentchanges'                     => 'Нові редагування',
'recentchanges-legend'              => 'Налаштування нових редагувань',
'recentchangestext'                 => 'На цій сторінці показані останні зміни на сторінках {{grammar:genitive|{{SITENAME}}}}.',
'recentchanges-feed-description'    => 'Відстежувати останні зміни у вікі в цьому потоці.',
'recentchanges-label-legend'        => 'Легенда: $1.',
'recentchanges-legend-newpage'      => '$1 — нова сторінка',
'recentchanges-label-newpage'       => 'Цим редагуванням створена нова сторінка',
'recentchanges-legend-minor'        => '$1 — незначне редагування',
'recentchanges-label-minor'         => 'Це незначна зміна',
'recentchanges-legend-bot'          => '$1 — редагування бота',
'recentchanges-label-bot'           => 'Це редагування зроблене ботом',
'recentchanges-legend-unpatrolled'  => '$1 — невідпатрульоване редагування',
'recentchanges-label-unpatrolled'   => 'Це редагування ще не було відпатрульоване',
'rcnote'                            => "{{PLURAL:$1|Остання '''$1''' зміна|Останні '''$1''' зміни|Останні '''$1''' змін}} за '''$2''' {{PLURAL:$2|день|дні|днів}}, на час $5, $4.",
'rcnotefrom'                        => 'Нижче відображені редагування з <strong>$2</strong> (до <strong>$1</strong>).',
'rclistfrom'                        => 'Показати редагування починаючи з $1.',
'rcshowhideminor'                   => '$1 незначні редагування',
'rcshowhidebots'                    => '$1 ботів',
'rcshowhideliu'                     => '$1 зареєстрованих',
'rcshowhideanons'                   => '$1 анонімів',
'rcshowhidepatr'                    => '$1 перевірені',
'rcshowhidemine'                    => '$1 мої редагування',
'rclinks'                           => 'Показати останні $1 редагувань за $2 днів<br />$3',
'diff'                              => 'різн.',
'hist'                              => 'історія',
'hide'                              => 'сховати',
'show'                              => 'показати',
'minoreditletter'                   => 'м',
'newpageletter'                     => 'Н',
'boteditletter'                     => 'б',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|користувач спостерігає|користувачі спостерігають|користувачів спостерігають}}]',
'rc_categories'                     => 'Тільки з категорій (разділювач «|»)',
'rc_categories_any'                 => 'Будь-який',
'newsectionsummary'                 => '/* $1 */ нова тема',
'rc-enhanced-expand'                => 'Показати деталі (потрібен JavaScript)',
'rc-enhanced-hide'                  => 'Сховати деталі',

# Recent changes linked
'recentchangeslinked'          => "Пов'язані редагування",
'recentchangeslinked-feed'     => "Пов'язані редгування",
'recentchangeslinked-toolbox'  => "Пов'язані редагування",
'recentchangeslinked-title'    => "Пов'язані редагування для «$1»",
'recentchangeslinked-noresult' => "На пов'язаних сторінках не було змін протягом зазначеного періоду.",
'recentchangeslinked-summary'  => "Це список нещодавніх змін на сторінках, на які посилається зазначена сторінка (або на сторінках, що містяться в цій категорії).
Сторінки з [[Special:Watchlist|вашого списку спостереження]] виділені '''жирним шрифтом'''.",
'recentchangeslinked-page'     => 'Назва сторінки:',
'recentchangeslinked-to'       => "Показати зміни на сторінках, пов'язаних з даною",

# Upload
'upload'                      => 'Завантажити файл',
'uploadbtn'                   => 'Завантажити файл',
'reuploaddesc'                => 'Повернутися до форми завантаження',
'upload-tryagain'             => 'Зберегти змінений опис файлу',
'uploadnologin'               => 'Ви не ввійшли в систему',
'uploadnologintext'           => 'Ви повинні [[Special:UserLogin|ввійти до системи]], щоб завантажувати файли.',
'upload_directory_missing'    => 'Директорія для завантажень ($1) відсутня і не може бути створена веб-сервером.',
'upload_directory_read_only'  => 'Веб-сервер не має прав запису в папку ($1), в якій планується зберігати завантажувані файли.',
'uploaderror'                 => 'Помилка завантаження файлу',
'upload-recreate-warning'     => "'''Увага. Файл з такою назвою був раніше вилучений або перейменований.''

Далі наведено журнал вилучень і перейменувань цього файлу:",
'uploadtext'                  => "За допомогою цієї форми ви можете завантажити файли на сервер.

Якщо файл із зазначеною вами назвою вже існує в проекті, то його буде замінено без попередження. Тому, якщо ви не збираєтесь оновлювати файл,
було б непогано перевірити, чи такий файл уже існує.

Щоби переглянути вже завантажені файли,
зайдіть на: [[Special:FileList|список завантажених файлів]].

Завантаження відображаються в [[Special:Log/upload|журналі завантажень]], вилучення – у [[Special:Log/delete|журналі вилучень]].

Для вставки зображень в статті можна використовувати такі рядки:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}:Назва_зображення.jpg<nowiki>]]</nowiki></tt>''', щоб використати повну версію файлу
* '''<tt><nowiki>[[</nowiki>{{ns:file}}:Назва_зображення.png|200px|thumb|left|Підпис під зображенням<nowiki>]]</nowiki></tt>''', щоб використати зображення у рамці зліва сторінки з підписом під зображенням

для інших медіа-файлів використовуйте рядок виду:
* '''<tt><nowiki>[[</nowiki>{{ns:media}}:Назва_файлу.ogg<nowiki>]]</nowiki></tt>'''.",
'upload-permitted'            => 'Дозволені типи файлів: $1.',
'upload-preferred'            => 'Бажані типи файлів: $1.',
'upload-prohibited'           => 'Заборонені типи файлів: $1.',
'uploadlog'                   => 'журнал завантажень',
'uploadlogpage'               => 'Журнал завантажень',
'uploadlogpagetext'           => 'Нижче наведено список останніх завантажених файлів.
Гляньте [[Special:NewFiles|галерею нових зображень]] для більш візуального огляду.',
'filename'                    => 'Назва файлу',
'filedesc'                    => 'Опис файлу',
'fileuploadsummary'           => 'Короткий опис:',
'filereuploadsummary'         => 'Зміни у файлі:',
'filestatus'                  => 'Умови поширення:',
'filesource'                  => 'Джерело:',
'uploadedfiles'               => 'Завантажені файли',
'ignorewarning'               => 'Ігнорувати попередження і зберегти файл',
'ignorewarnings'              => 'Ігнорувати всі попередження',
'minlength1'                  => 'Назва файлу повинна містити щонайменше одну літеру.',
'illegalfilename'             => 'Ім\'я файлу "$1" містить букви, що недозволені в заголовках сторінок. Будь ласка перейменуйте файл і спробуйте завантажити його знову.',
'badfilename'                 => 'Назву файлу було змінено на $1.',
'filetype-mime-mismatch'      => 'Розширення файлу не відповідає MIME-типу.',
'filetype-badmime'            => 'Файли, що мають MIME-тип «$1», не можуть бути завантажені.',
'filetype-bad-ie-mime'        => 'Неможливо завантажити цей файл, оскільки Internet Explorer визначатиме його як «$1», тобто недозволеним і потенційно небезпечним типом файлу.',
'filetype-unwanted-type'      => "'''\".\$1\"''' — небажаний тип файлу.
{{PLURAL:\$3|Бажаний тип файлів|Бажані типи файлів}}: \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' — заборонений тип файлу.
{{PLURAL:\$3|Дозволений тип файлів|Дозволені типи файлів}}: \$2.",
'filetype-missing'            => 'Відсутнє розширення файлу (наприклад, «.jpg»).',
'empty-file'                  => 'Надісланий вами файл порожній.',
'file-too-large'              => 'Файл, який ви надіслали, завеликий.',
'filename-tooshort'           => 'Назва файлу занадто коротка.',
'filetype-banned'             => 'Цей тип файлів заборонений.',
'verification-error'          => 'Файлу не вдалося пройти процедуру перевірки.',
'hookaborted'                 => 'Запропоновану вами зміну перервав обробник розширення.',
'illegal-filename'            => 'Недозволена назва файлу.',
'overwrite'                   => 'Заміну існуючого файлу не дозволено.',
'unknown-error'               => 'Трапилась невідома помилка.',
'tmp-create-error'            => 'Не вдалося створити тимчасовий файл.',
'tmp-write-error'             => 'Помилка запису тимчасового файлу.',
'large-file'                  => 'Рекомендується використовувати зображення, розмір яких не перевищує $1 байтів (размір завантаженого файлу складає $2 байтів).',
'largefileserver'             => 'Розмір файлу більший за максимальнодозволений.',
'emptyfile'                   => 'Завантажений вами файл ймовірно порожній. Можливо, це сталося через помилку при введенні імені файлу. Будь-ласка, перевірте, чи справді ви бажаєте звантажити цей файл.',
'fileexists'                  => "Файл з такою назвою вже існує.
Будь ласка, перевірте '''<tt>[[:$1]]</tt>''', якщо ви не впевнені, чи хочете замінти його.
[[$1|thumb]]",
'filepageexists'              => "Сторінка опису цього файлу вже створена як '''<tt>[[:$1]]</tt>''', але файлу з такою назвою немає. Уведений опис не з'явиться на сторінці опису зображення. Щоб додати новий опис, вам доведеться змінити його вручну. [[$1|thumb]]",
'fileexists-extension'        => "Існує файл зі схожою назвою: [[$2|thumb]]
* Назва завантаженого файлу: '''<tt>[[:$1]]</tt>'''
* Назва існуючого файлу: '''<tt>[[:$2]]</tt>'''
Будьте ласкаві, виберіть іншу назву.",
'fileexists-thumbnail-yes'    => "Можливо, файл є зменшеною копією (мініатюрою). [[$1|thumb]]
Будь ласка, перевірте файл '''<tt>[[:$1]]</tt>'''.
Якщо вказаний файл є тим самим зображенням, не варто окремо завантажувати його зменшену копію.",
'file-thumbnail-no'           => "Назва файлу починається на '''<tt>$1</tt>'''.
Можливо, це зменшена копія зображення ''(мініатюра)''.
Якщо у вас є це зображення в повному розмірі, завантажте його, інакше змініть назву файлу.",
'fileexists-forbidden'        => 'Файл з такою назвою вже існує і не може бути перезаписаний.
Якщо ви все одно хочете завантажити цей файл, будь ласка, поверніться назад і оберіть іншу назву.
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Файл із такою назвою вже існує у спільному сховищі файлів.
Якщо ви все ж хочете завантажити цей файл, будь ласка, поверніться назад і змініть назву файлу. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Цей файл є дублікатом {{PLURAL:$1|файлу|таких файлів}}:',
'file-deleted-duplicate'      => 'Такий самий файл ([[$1]]) уже вилучався раніше. Будь ласка, ознайомтеся з історією вилучень файлу перед тим, як завантажити його знову.',
'uploadwarning'               => 'Попередження',
'uploadwarning-text'          => 'Будь ласка, змініть наданий нижче опис файлу і спробуйте ще раз.',
'savefile'                    => 'Зберегти файл',
'uploadedimage'               => 'завантажив «[[$1]]»',
'overwroteimage'              => 'завантажив нову версію «[[$1]]»',
'uploaddisabled'              => 'Завантаження заборонене',
'copyuploaddisabled'          => 'Завантаження через URL вимкнене.',
'uploadfromurl-queued'        => 'Ваше завантаження поставлене в чергу.',
'uploaddisabledtext'          => 'Можливість завантаження файлів відключена.',
'php-uploaddisabledtext'      => 'Завантаження файлів вимкнене у налаштуваннях PHP. Будь ласка, перевірте значення file_uploads.',
'uploadscripted'              => 'Файл містить HTML-код або скрипт, який може неправильно обробитися браузером.',
'uploadvirus'                 => 'Файл містить вірус! Див. $1',
'upload-source'               => 'Вихідний файл',
'sourcefilename'              => 'Назва початкового файлу:',
'sourceurl'                   => 'Вихідна URL-адреса:',
'destfilename'                => 'Назва завантаженого файлу:',
'upload-maxfilesize'          => 'Максимальний розмір файлу: $1',
'upload-description'          => 'Опис файлу',
'upload-options'              => 'Параметри завантаження',
'watchthisupload'             => 'Спостерігати за цим файлом',
'filewasdeleted'              => 'Файл з такою назвою вже існував, але був вилучений.
Вам слід перевірити $1 перед повторним завантаженням.',
'upload-wasdeleted'           => "'''Попередження: ви хочете завантажити файл, який раніше вилучався.'''

Перевірте, чи справді варто завантажувати файл.
Нижче показано журнал вилучень для цього файлу:",
'filename-bad-prefix'         => "Назва завантажуваного файлу починається на '''«$1»''' і, можливо, є шаблонною назвою, яку цифрова фотокамера дає знімкам. Будь ласка, виберіть назву, яка краще описуватиме вміст файлу.",
'filename-prefix-blacklist'   => ' #<!-- не міняйте цей рядок --> <pre>
# Синтаксис такий:
#   * Все, що починається з символу «#» вважається коментарем (до кінця рядка)
#   * Кожен непорожній рядок - префікс стандартної назви файлу, яку зазвичай дає цифрова камера
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # деякі мобільні телефони
IMG # загальне
JD # Jenoptik
MGP # Pentax
PICT # різні
 #</pre> <!-- не міняйте цей рядок -->',
'upload-success-subj'         => 'Завантаження успішно завершено',
'upload-success-msg'          => 'Ваше завантаження з [$2] було успішним. Воно доступне тут: [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'Проблема із завантаженням',
'upload-failure-msg'          => 'З вашим завантаженням виникла проблема:

$1',
'upload-warning-subj'         => 'Попередження при завантаженні',

'upload-proto-error'        => 'Невірний протокол',
'upload-proto-error-text'   => 'Віддалене завантаження вимагає адресів, що починаються з <code>http://</code> або <code>ftp://</code>.',
'upload-file-error'         => 'Внутрішня помилка',
'upload-file-error-text'    => 'Сталася внутрішня помилка при спробі створити тимчасовий файл на сервері. Будь-ласка, зверніться до [[Special:ListUsers/sysop|адміністратора]].',
'upload-misc-error'         => 'Невідома помилка завантаження',
'upload-misc-error-text'    => 'Невідома помилка завантаження. Будь-ласка, перевірте, що вказана адреса вірна й спробуйте ще. Якщо проблема виникає знову, зверніться до системного адміністратора.',
'upload-too-many-redirects' => 'URL містить надто багато перенаправлень',
'upload-unknown-size'       => 'Невідомий розмір',
'upload-http-error'         => 'Відбулася помилка HTTP: $1',

# img_auth script messages
'img-auth-accessdenied' => 'Відмовлено в доступі',
'img-auth-nopathinfo'   => 'Брак PATH_INFO.
Ваш сервер не налаштований для передачі цих даних.
Можливо, він працює на основі CGI і не підтримує img_auth.
Глядіть http://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir'     => 'Проханий шлях не відноситься до теки завантажень, вказаної в налаштуваннях.',
'img-auth-badtitle'     => 'Не можна побудувати правильний заголовок з «$1».',
'img-auth-nologinnWL'   => 'Ви не ввійшли в систему, а «$1» не входить у білий список.',
'img-auth-nofile'       => 'Файл «$1» не існує.',
'img-auth-isdir'        => 'Ви пробуєте отримати доступ до каталогу «$1».
Дозволений тільки доступ до файлів.',
'img-auth-streaming'    => 'Потокова передача «$1».',
'img-auth-public'       => 'Призначенням img_auth.php є добування файлів із закритих вікі.
Ця вікі налаштована як загальнодоступна.
Для оптимальної безпеки img_auth.php відключено.',
'img-auth-noread'       => 'Користувач не має доступу до перегляду "$1".',

# HTTP errors
'http-invalid-url'      => 'Неправильний URL: $1',
'http-invalid-scheme'   => 'URL-адреси схеми "$1" не підтримуються',
'http-request-error'    => 'HTTP-запит не вдався через невідому помилку.',
'http-read-error'       => 'Помилка читання HTTP.',
'http-timed-out'        => 'Перевищення часу очікування HTTP-запиту.',
'http-curl-error'       => 'Помилка звертання до URL: $1',
'http-host-unreachable' => 'Неможливо досягнути вказану URL-адресу.',
'http-bad-status'       => 'Під час HTTP-запиту виникла проблема: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Неможливо досягнути вказану адресу.',
'upload-curl-error6-text'  => 'Неможливо досягнути вказану адресу. Будь-ласка, перевірте, що вказана адреса вірна, а сайт доступний.',
'upload-curl-error28'      => 'Час виділений на завантаження вичерпано',
'upload-curl-error28-text' => 'Сайт дуже довго не відповідає.
Будь ласка, перевірте, що сайт працює і після невеликої паузи спробуйте ще.
Можливо, операцію слід провести в інший час, коли сайт менш завантажений.',

'license'            => 'Ліцензування:',
'license-header'     => 'Ліцензування',
'nolicense'          => 'Відсутнє',
'license-nopreview'  => '(Попередній перегляд недоступний)',
'upload_source_url'  => ' (вірна, публічно доступна інтернет-адреса)',
'upload_source_file' => " (файл на вашому комп'ютері)",

# Special:ListFiles
'listfiles-summary'     => 'Ця спеціальна сторінка показує всі завантажені файли.
За умовчанням останні завантажені файли показані зверху.
Натисніть на заголовок стовпчика, щоб відсортувати.',
'listfiles_search_for'  => 'Пошук по назві зображення:',
'imgfile'               => 'файл',
'listfiles'             => 'Список файлів',
'listfiles_date'        => 'Дата',
'listfiles_name'        => 'Назва',
'listfiles_user'        => 'Користувач',
'listfiles_size'        => 'Розмір (в байтах)',
'listfiles_description' => 'Опис',
'listfiles_count'       => 'Версії',

# File description page
'file-anchor-link'                  => 'Файл',
'filehist'                          => 'Історія файлу',
'filehist-help'                     => 'Клацніть на дату/час, щоб переглянути, як тоді виглядав файл.',
'filehist-deleteall'                => 'вилучити всі',
'filehist-deleteone'                => 'вилучити',
'filehist-revert'                   => 'повернути',
'filehist-current'                  => 'поточний',
'filehist-datetime'                 => 'Дата/час',
'filehist-thumb'                    => 'Мініатюра',
'filehist-thumbtext'                => 'Мініатюра для версії від $1',
'filehist-nothumb'                  => 'Нема мініатюри',
'filehist-user'                     => 'Користувач',
'filehist-dimensions'               => "Розмір об'єкта",
'filehist-filesize'                 => 'Розмір файлу',
'filehist-comment'                  => 'Коментар',
'filehist-missing'                  => 'Файл відсутній',
'imagelinks'                        => 'Посилання на файл',
'linkstoimage'                      => '{{PLURAL:$1|Наступна сторінка посилається|Наступні сторінки посилаються}} на цей файл:',
'linkstoimage-more'                 => 'Більше $1 {{PLURAL:$1|сторінки|сторінок}} посилаються на цей файл.
У цьому списку {{PLURAL:$1|показане тільки $1 посилання|показані тільки $1 посилання|показані тільки $1 посилань}} на цей файл.
Також доступний [[Special:WhatLinksHere/$2|повний список]].',
'nolinkstoimage'                    => 'Нема сторінок, що посилаються на цей файл.',
'morelinkstoimage'                  => 'Переглянути [[Special:WhatLinksHere/$1|інші посилання]] на цей файл.',
'redirectstofile'                   => 'На цей файл {{PLURAL:$1|перенаправляється файл|перенаправляються такі файли}}:',
'duplicatesoffile'                  => '{{PLURAL:$1|Дублікатом цього файлу є файл|Такі $1 файли є дублікатами цього файлу|Такі $1 файлів є дублікатами цього файлу}}
([[Special:FileDuplicateSearch/$2|докладніше]]):',
'sharedupload'                      => 'Цей файл із $1 і є доступним для інших проектів.',
'sharedupload-desc-there'           => 'Цей файл з $1 і може використовуватися в інших проектах.
Додаткову інформацію можна знайти на [$2 сторінці опису файлу].',
'sharedupload-desc-here'            => 'Цей файл з $1 і може використовуватися в інших проектах.
Далі наведена інформація з його [$2 сторінки опису].',
'filepage-nofile'                   => 'Не існує файлу з такою назвою.',
'filepage-nofile-link'              => 'Не існує файлу з такою назвою, але ви можете [$1 завантажити його].',
'uploadnewversion-linktext'         => 'Завантажити нову версію цього файлу',
'shared-repo-from'                  => 'з $1',
'shared-repo'                       => 'спільного сховища',
'shared-repo-name-wikimediacommons' => 'Вікісховища',

# File reversion
'filerevert'                => 'Повернення до старої версії $1',
'filerevert-legend'         => 'Повернути версію файлу',
'filerevert-intro'          => "Ви повертаєте '''[[Media:$1|$1]]''' до [$4 версії від $3, $2].",
'filerevert-comment'        => 'Причина:',
'filerevert-defaultcomment' => 'Повернення до версії від $2, $1',
'filerevert-submit'         => 'Повернути',
'filerevert-success'        => "'''[[Media:$1|$1]]''' був повернутий до [$4 версії від $3, $2].",
'filerevert-badversion'     => 'Немає локальної версії цього файлу з вказаною поміткою дати і часу.',

# File deletion
'filedelete'                  => 'Вилучення $1',
'filedelete-legend'           => 'Вилучити файл',
'filedelete-intro'            => "Ви збираєтесь вилучити '''[[Media:$1|$1]]''' і всю його історію.",
'filedelete-intro-old'        => "Ви вилучаєте версію '''[[Media:$1|$1]]''' від [$4 $3, $2].",
'filedelete-comment'          => 'Причина',
'filedelete-submit'           => 'Вилучити',
'filedelete-success'          => "'''$1''' було вилучено.",
'filedelete-success-old'      => "Версія '''[[Media:$1|$1]]''' від $3, $2 була вилучена.",
'filedelete-nofile'           => "Файл '''$1''' не існує.",
'filedelete-nofile-old'       => "Не існує архівної версії '''$1''' із зазначеними атрибутами.",
'filedelete-otherreason'      => 'Інша/додаткова причина:',
'filedelete-reason-otherlist' => 'Інша причина',
'filedelete-reason-dropdown'  => '* Поширені причини вилучення
** порушення авторських прав
** файл-дублікат',
'filedelete-edit-reasonlist'  => 'Редагувати причини вилучень',
'filedelete-maintenance'      => 'Вилучення та відновлення файлів відімкнене на час технічних робіт.',

# MIME search
'mimesearch'         => 'Пошук по MIME',
'mimesearch-summary' => 'Ця сторінка дозволяє вибирати файли за їх MIME-типом. Формат вводу: тип_вмісту/підтип, наприклад <tt>image/jpeg</tt>.',
'mimetype'           => 'MIME-тип:',
'download'           => 'завантажити',

# Unwatched pages
'unwatchedpages' => 'Сторінки, за якими ніхто не спостерігає',

# List redirects
'listredirects' => 'Список перенаправлень',

# Unused templates
'unusedtemplates'     => 'Шаблони, що не використовуються',
'unusedtemplatestext' => 'На цій сторінці показані всі сторінки простору назв «{{ns:template}}», які не включені до інших сторінок. Не забувайте перевірити відсутність інших посилань на шаблон, перш ніж вилучити його.',
'unusedtemplateswlh'  => 'інші посилання',

# Random page
'randompage'         => 'Випадкова стаття',
'randompage-nopages' => 'Нема сторінок в {{PLURAL:$2|просторі назв|просторах назв}} $1.',

# Random redirect
'randomredirect'         => 'Випадкове перенаправлення',
'randomredirect-nopages' => 'Простір назв «$1» не містить перенаправлень.',

# Statistics
'statistics'                   => 'Статистика',
'statistics-header-pages'      => 'Статистика сторінок',
'statistics-header-edits'      => 'Статистика редагувань',
'statistics-header-views'      => 'Статистика переглядів',
'statistics-header-users'      => 'Статистика користувачів',
'statistics-header-hooks'      => 'Інша статистика',
'statistics-articles'          => 'Статей',
'statistics-pages'             => 'Сторінок',
'statistics-pages-desc'        => 'Усі сторінки у вікі, включаючи сторінки обговорень, перенаправлення тощо.',
'statistics-files'             => 'Завантажено файлів',
'statistics-edits'             => 'Кількість редагувань з моменту установки {{grammar:genitive|{{SITENAME}}}}',
'statistics-edits-average'     => 'Середня кількість редагувань на сторінку',
'statistics-views-total'       => 'Усього переглядів',
'statistics-views-peredit'     => 'Переглядів на редагування',
'statistics-users'             => 'Зареєстрованих [[Special:ListUsers|користувачів]]',
'statistics-users-active'      => 'Активні користувачі',
'statistics-users-active-desc' => 'Користувачі, які здійснили якусь дію протягом {{PLURAL:$1|минулого дня|минулих $1 днів}}',
'statistics-mostpopular'       => 'Сторінки, які найчастіше переглядають',

'disambiguations'      => 'Багатозначні статті',
'disambiguationspage'  => 'Template:disambig',
'disambiguations-text' => "Наступні сторінки посилаються на '''багатозначні сторінки'''. Однак вони, ймовірно, повинні вказувати на відповідну конкретну статтю.<br />Сторінка вважається багатозначною, якщо на ній розміщений шаблон, назва якого є на сторінці [[MediaWiki:Disambiguationspage]].",

'doubleredirects'            => 'Подвійні перенаправлення',
'doubleredirectstext'        => 'На цій сторінці наведено список перенаправлень на інші перенаправлення.
Кожен рядок містить посилання на перше та друге перенаправлення, а також перший рядок тексту другого перенаправлення, що зазвичай містить «реальне» перенаправлення на необхідну сторінку, куди повинно вказувати й перше перенаправлення.
<del>Закреслені</del> записи були виправлені.',
'double-redirect-fixed-move' => 'Сторінка «[[$1]]» була перейменована, зараз вона є перенаправленням на «[[$2]]»',
'double-redirect-fixer'      => 'Redirect fixer',

'brokenredirects'        => 'Розірвані перенаправлення',
'brokenredirectstext'    => 'Такі перенаправлення вказують на неіснуючі сторінки:',
'brokenredirects-edit'   => 'редагувати',
'brokenredirects-delete' => 'вилучити',

'withoutinterwiki'         => 'Сторінки без міжмовних посилань',
'withoutinterwiki-summary' => 'Такі сторінки не мають інтервікі-посилань:',
'withoutinterwiki-legend'  => 'Префікс',
'withoutinterwiki-submit'  => 'Показати',

'fewestrevisions' => 'Сторінки з найменшою кількістю змін',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|байт|байти|байтів}}',
'ncategories'             => '$1 {{PLURAL:$1|категорія|категорії|категорій}}',
'nlinks'                  => '$1 {{PLURAL:$1|посилання|посилання|посилань}}',
'nmembers'                => "$1 {{PLURAL:$1|об'єкт|об'єкти|об'єктів}}",
'nrevisions'              => '$1 {{PLURAL:$1|версія|версії|версій}}',
'nviews'                  => '$1 {{PLURAL:$1|перегляд|перегляди|переглядів}}',
'specialpage-empty'       => 'Запит не дав результатів.',
'lonelypages'             => 'Статті-сироти',
'lonelypagestext'         => 'На такі сторінки нема посилань з інших сторінок і вони не включаються до інших сторінок {{grammar:genitive|{{SITENAME}}}}.',
'uncategorizedpages'      => 'Некатегоризовані сторінки',
'uncategorizedcategories' => 'Некатегоризовані категорії',
'uncategorizedimages'     => 'Некатегоризовані зображення',
'uncategorizedtemplates'  => 'Некатегоризовані шаблони',
'unusedcategories'        => 'Категорії, що не використовуються',
'unusedimages'            => 'Файли, що не використовуються',
'popularpages'            => 'Популярні статті',
'wantedcategories'        => 'Необхідні категорії',
'wantedpages'             => 'Необхідні статті',
'wantedpages-badtitle'    => 'Неправильний заголовок у результатах запиту: $1',
'wantedfiles'             => 'Необхідні файли',
'wantedtemplates'         => 'Необхідні шаблони',
'mostlinked'              => 'Сторінки, на які найбільше посилань',
'mostlinkedcategories'    => 'Категорії, на які найбільше посилань',
'mostlinkedtemplates'     => 'Найуживаніші шаблони',
'mostcategories'          => 'Статті з найбільшою кількістю категорій',
'mostimages'              => 'Найуживаніші зображення',
'mostrevisions'           => 'Статті з найбільшою кількістю редакцій',
'prefixindex'             => 'Покажчик за початком назв сторінок',
'shortpages'              => 'Короткі статті',
'longpages'               => 'Довгі статті',
'deadendpages'            => 'Сторінки без посилань',
'deadendpagestext'        => 'Наступні сторінки не містять посилань на інші сторінки цієї вікі.',
'protectedpages'          => 'Захищені сторінки',
'protectedpages-indef'    => 'Тільки безстроково захищені',
'protectedpages-cascade'  => 'Тільки каскадний захист',
'protectedpagestext'      => 'Наступні сторінки захищені від перейменування або зміни.',
'protectedpagesempty'     => 'Зараз нема захищених сторінок із зазначеними параметрами',
'protectedtitles'         => 'Заборонені назви',
'protectedtitlestext'     => 'Наступні назви не дозволено використовувати',
'protectedtitlesempty'    => 'Зараз нема захищених назв із зазначеними параметрами.',
'listusers'               => 'Список користувачів',
'listusers-editsonly'     => 'Показати лише користувачів, які зробили принаймні одне редагування',
'listusers-creationsort'  => 'Сортувати за датою створення',
'usereditcount'           => '$1 {{PLURAL:$1|редагування|редагування|редагувань}}',
'usercreated'             => 'Створений $1 о $2',
'newpages'                => 'Нові сторінки',
'newpages-username'       => "Ім'я користувача:",
'ancientpages'            => 'Сторінки, які найдовше не редагувалися',
'move'                    => 'Перейменувати',
'movethispage'            => 'Перейменувати цю сторінку',
'unusedimagestext'        => 'Наступні файли не використовуються на жодній сторінці.
Будь ласка, врахуйте, що інші веб-сайти можуть використовувати прямі посилання (URL) на цей файл, і тому файл може активно використовуватися, не зважаючи на його присутність у цьому списку.',
'unusedcategoriestext'    => 'Існують такі сторінки категорій, що не містять сторінок або інших категорій.',
'notargettitle'           => 'Не вказано ціль',
'notargettext'            => 'Ви не вказали цільову статтю чи користувача, для яких необхідно виконати цю дію.',
'nopagetitle'             => 'Нема такої цільової сторінки',
'nopagetext'              => 'Зазначена цільова сторінка не існує.',
'pager-newer-n'           => '{{PLURAL:$1|новіша|новіші|новіших}} $1',
'pager-older-n'           => '{{PLURAL:$1|старіша|старіші|старіших}} $1',
'suppress'                => 'Ревізор',

# Book sources
'booksources'               => 'Джерела книг',
'booksources-search-legend' => 'Пошук інформації про книгу',
'booksources-go'            => 'Знайти',
'booksources-text'          => 'На цій сторінці наведено список посилань на сайти, де ви, можливо, знайдете додаткову інформацію про книгу. Це інтернет-магазини й системи пошуку в бібліотечних каталогах.',
'booksources-invalid-isbn'  => 'Вказаний номер ISBN, судячи з усього, містить помилку. Будь ласка, перевірте, що при перенесенні номера з першоджерела не виникло спотворень.',

# Special:Log
'specialloguserlabel'  => 'Користувач:',
'speciallogtitlelabel' => 'Назва:',
'log'                  => 'Журнали',
'all-logs-page'        => 'Усі публічні журнали',
'alllogstext'          => 'Комбінований показ журналів {{grammar:genitive|{{SITENAME}}}}.
Ви можете відфільтрувати результати за типом журналу, іменем користувача (враховується регістр) або зазначеною сторінкою (також враховується регістр).',
'logempty'             => 'В журналі немає подібних записів.',
'log-title-wildcard'   => 'Знайти заголовки, що починаються з цих символів',

# Special:AllPages
'allpages'          => 'Усі сторінки',
'alphaindexline'    => 'від $1 до $2',
'nextpage'          => 'Наступна сторінка ($1)',
'prevpage'          => 'Попередня сторінка ($1)',
'allpagesfrom'      => 'Показати сторінки, що починаються з:',
'allpagesto'        => 'Зупинити вивід на:',
'allarticles'       => 'Усі сторінки',
'allinnamespace'    => 'Усі сторінки (простір назв $1)',
'allnotinnamespace' => 'Усі сторінки (крім простору назв $1)',
'allpagesprev'      => 'Попередні',
'allpagesnext'      => 'Наступні',
'allpagessubmit'    => 'Виконати',
'allpagesprefix'    => 'Знайти сторінки, що починаються з:',
'allpagesbadtitle'  => 'Неприпустима назва сторінки: можливо, заголовок містить міжмовний або міжпроектний префікс чи заборонені символи.',
'allpages-bad-ns'   => '{{SITENAME}} не має простору назв «$1».',

# Special:Categories
'categories'                    => 'Категорії',
'categoriespagetext'            => '{{PLURAL:$1|Наступна категорія|Наступні категорії}} містять сторінки або медіа-файли.
Тут не показані [[Special:UnusedCategories|категорії, що не використовуються]].
Див. також [[Special:WantedCategories|список необхідних категорій]].',
'categoriesfrom'                => 'Показати категорії, що починаються з:',
'special-categories-sort-count' => 'упорядкувати за кількістю',
'special-categories-sort-abc'   => 'упорядкувати за алфавітом',

# Special:DeletedContributions
'deletedcontributions'             => 'Вилучений внесок користувача',
'deletedcontributions-title'       => 'Вилучений внесок користувача',
'sp-deletedcontributions-contribs' => 'внесок',

# Special:LinkSearch
'linksearch'       => 'Зовнішні посилання',
'linksearch-pat'   => 'Шаблон для пошуку:',
'linksearch-ns'    => 'Простір назв:',
'linksearch-ok'    => 'Знайти',
'linksearch-text'  => 'Можна використовувати підстановочні символи, наприклад, <code>*.wikipedia.org</code>.<br />Підтримувані протоколи: <tt>$1</tt>',
'linksearch-line'  => 'Посилання на $1 із $2',
'linksearch-error' => 'Підстановочні знаки можуть використовуватися лише на початку адрес.',

# Special:ListUsers
'listusersfrom'      => 'Показати користувачів, починаючи з:',
'listusers-submit'   => 'Показати',
'listusers-noresult' => 'Не знайдено користувачів.',
'listusers-blocked'  => '({{GENDER:$1|заблокований|заблокована|заблокований}})',

# Special:ActiveUsers
'activeusers'            => 'Список активних користувачів',
'activeusers-intro'      => 'Це список користувачів, які здійснювали які-небудь дії за {{PLURAL:$1|останній $1 день|останні $1 дні|останні $1 днів}}.',
'activeusers-count'      => '$1 {{PLURAL:$1|нове редагування|нові редагування|нових редагувань}} за {{PLURAL:$3|останній день|останні $3 дні|останні $3 днів}}',
'activeusers-from'       => 'Показувати користувачів, починаючи з:',
'activeusers-hidebots'   => 'Приховати ботів',
'activeusers-hidesysops' => 'Приховати адміністраторів',
'activeusers-noresult'   => 'Не знайдено користувачів.',

# Special:Log/newusers
'newuserlogpage'              => 'Журнал нових користувачів',
'newuserlogpagetext'          => 'Список нещодавно зареєстрованих користувачів.',
'newuserlog-byemail'          => 'пароль надісланий електронною поштою',
'newuserlog-create-entry'     => 'Новий користувач',
'newuserlog-create2-entry'    => 'створив новий обліковий запис $1',
'newuserlog-autocreate-entry' => 'Обліковий запис створений автоматично',

# Special:ListGroupRights
'listgrouprights'                      => 'Права груп користувачів',
'listgrouprights-summary'              => 'Нижче наведений список груп користувачів у цій вікі і права для кожної групи.
Додаткову інформацію про права користувачів можна знайти [[{{MediaWiki:Listgrouprights-helppage}}|тут]].',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Надані права</span>
* <span class="listgrouprights-revoked">Скасовані права</span>',
'listgrouprights-group'                => 'Група',
'listgrouprights-rights'               => 'Права',
'listgrouprights-helppage'             => 'Help:Права користувачів',
'listgrouprights-members'              => '(список членів)',
'listgrouprights-addgroup'             => 'може додавати в {{PLURAL:$2|групу|групи}}: $1',
'listgrouprights-removegroup'          => 'може виключати з {{PLURAL:$2|групи|груп}}: $1',
'listgrouprights-addgroup-all'         => 'може додавати до всіх груп',
'listgrouprights-removegroup-all'      => 'може виключати зі всіх груп',
'listgrouprights-addgroup-self'        => 'може додавати {{PLURAL:$2|групу|групи}} до свого облікового запису: $1',
'listgrouprights-removegroup-self'     => 'може вилучати {{PLURAL:$2|групу|групи}} зі свого облікового запису: $1',
'listgrouprights-addgroup-self-all'    => 'Може додавати всі групи до свого облікового запису',
'listgrouprights-removegroup-self-all' => 'може вилучати всі групи зі свого облікового запису',

# E-mail user
'mailnologin'          => 'Відсутня адреса для відправки',
'mailnologintext'      => 'Ви повинні [[Special:UserLogin|ввійти до системи]] і мати підтверджену адресу електронної пошти у ваших [[Special:Preferences|налаштуваннях]], щоб мати змогу надсилати електронну пошту іншим користувачам.',
'emailuser'            => 'Надіслати листа цьому користувачеві',
'emailpage'            => 'Лист користувачеві',
'emailpagetext'        => 'Заповнивши наведену нижче форму, можна надіслати повідомлення цьому користувачу.
Електронна адреса, яку ви зазначили у [[Special:Preferences|своїх налаштуваннях]], буде зазначена в полі «Від кого» листа, тому одержувач матиме можливість відповісти безпосередньо вам.',
'usermailererror'      => 'При відправці повідомлення електронної пошти сталася помилка:',
'defemailsubject'      => '{{SITENAME}}: лист',
'usermaildisabled'     => 'Електронне листування між користувачами вимкнене',
'usermaildisabledtext' => 'Ви не можете надсилати електронні листи іншим користувачам цієї вікі',
'noemailtitle'         => 'Відсутня адреса електронної пошти',
'noemailtext'          => 'Цей користувач не вказав коректної адреси електронної пошти.',
'nowikiemailtitle'     => 'Ел. пошти не дозволено',
'nowikiemailtext'      => 'Цей користувач вирішив не отримувати ел. пошту від інших користувачів.',
'email-legend'         => 'Надіслати листа іншому користувачеві {{grammar:genitive|{{SITENAME}}}}',
'emailfrom'            => 'Від кого:',
'emailto'              => 'Кому:',
'emailsubject'         => 'Тема:',
'emailmessage'         => 'Повідомлення:',
'emailsend'            => 'Надіслати',
'emailccme'            => 'Надіслати мені копію повідомлення.',
'emailccsubject'       => 'Копія вашого повідомлення до $1: $2',
'emailsent'            => 'Електронне повідомлення надіслано',
'emailsenttext'        => 'Ваше електронне повідомлення надіслано.',
'emailuserfooter'      => 'Цей лист був надісланий користувачеві $2 від користувача $1 за допомогою функції «Надіслати листа» проекту {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'Залишити системне повідомлення.',
'usermessage-editor'  => 'Системний вісник',

# Watchlist
'watchlist'            => 'Список спостереження',
'mywatchlist'          => 'Список спостереження',
'watchlistfor'         => "(користувача '''$1''')",
'nowatchlist'          => 'Ваш список спостереження порожній.',
'watchlistanontext'    => 'Вам необхідно $1, щоб переглянути чи редагувати список спостереження.',
'watchnologin'         => 'Ви не ввійшли до системи',
'watchnologintext'     => 'Ви повинні [[Special:UserLogin|ввійти до системи]], щоб мати можливість змінювати список спостереження.',
'addedwatch'           => 'Додана до списку спостереження',
'addedwatchtext'       => "Сторінка «[[:$1]]» додана до вашого [[Special:Watchlist|списку спостереження]].
Наступні редагування цієї статті і пов'язаної з нею сторінки обговорення будуть відображатися в цьому списку, а також будуть виділені '''жирним шрифтом''' на сторінці зі [[Special:RecentChanges|списком останніх редагувань]], щоб їх було легше помітити.",
'removedwatch'         => 'Вилучена зі списку спостереження',
'removedwatchtext'     => 'Сторінка «[[:$1]]» вилучена з вашого [[Special:Watchlist|списку спостереження]].',
'watch'                => 'Спостерігати',
'watchthispage'        => 'Спостерігати за цією сторінкою',
'unwatch'              => 'Скас. спостереження',
'unwatchthispage'      => 'Скасувати спостереження',
'notanarticle'         => 'Не стаття',
'notvisiblerev'        => 'Версія була вилучена',
'watchnochange'        => 'За вказаний період в статтях з списку спостереження нічого не змінено.',
'watchlist-details'    => 'У вашому списку спостереження $1 {{PLURAL:$1|сторінка|сторінки|сторінок}} (не враховуючи сторінок обговорення).',
'wlheader-enotif'      => '* Звістка електронною поштою ввімкнена.',
'wlheader-showupdated' => "* Сторінки, що змінилися після вашого останнього їх відвідування, виділені '''жирним''' шрифтом.",
'watchmethod-recent'   => 'перегляд останніх редагувань статей за якими ведеться спостереження',
'watchmethod-list'     => 'перегляд статей за якими ведеться спостереження',
'watchlistcontains'    => 'Ваш список спостереження містить $1 {{PLURAL:$1|сторінку|сторінки|сторінок}}.',
'iteminvalidname'      => 'Проблема з елементом «$1», недопустима назва…',
'wlnote'               => 'Нижче наведені останні $1 {{PLURAL:$1|редагування|редагування|редагувань}} за {{PLURAL:$2|останню|останні|останні}} <strong>$2</strong> {{PLURAL:$2|годину|години|годин}}.',
'wlshowlast'           => 'Показати зміни за останні $1 годин $2 днів $3',
'watchlist-options'    => 'Налаштування списку спостереження',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Додавання до списку спостереження…',
'unwatching' => 'Вилучення зі списку спостереження…',

'enotif_mailer'                => '{{SITENAME}} Служба сповіщення поштою',
'enotif_reset'                 => 'Помітити всі сторінки як переглянуті',
'enotif_newpagetext'           => 'Це нова сторінка.',
'enotif_impersonal_salutation' => 'Користувач {{grammar:genitive|{{SITENAME}}}}',
'changed'                      => 'змінена',
'created'                      => 'створена',
'enotif_subject'               => 'Сторінка проекту «{{SITENAME}}» $PAGETITLE була $CHANGEDORCREATED користувачем $PAGEEDITOR',
'enotif_lastvisited'           => 'Див. $1 для перегляду всіх змін, що відбулися після вашого останнього перегляду.',
'enotif_lastdiff'              => 'Див. $1 для ознайомлення з цією зміною.',
'enotif_anon_editor'           => 'анонімний користувач $1',
'enotif_body'                  => 'Шановний/на $WATCHINGUSERNAME,

$PAGEEDITDATE сторінка проекту «{{SITENAME}}» $PAGETITLE була $CHANGEDORCREATED користувачем $PAGEEDITOR, див. $PAGETITLE_URL, щоб переглянути поточну версію.

$NEWPAGE

Короткий опис змін: $PAGESUMMARY $PAGEMINOREDIT

Звернутися до користувача, що редагував:
ел. пошта: $PAGEEDITOR_EMAIL
вікі: $PAGEEDITOR_WIKI

Якщо Ви не відвідуєте цю сторінку, не буде подальшого сповіщення в разі нових змін.
Ви можете також змінити параметри сповіщення для всіх сторінок у вашому списку спостереження.

             Система сповіщення {{grammar:genitive|{{SITENAME}}}}

--
Змінити налаштування вашого списку спостереження можна на
{{fullurl:{{#special:Watchlist}}/edit}}

Для вилучення сторінки з вашого списку спостереження відвідайте
$UNWATCHURL

Зворотний зв\'язок та допомога:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Вилучити сторінку',
'confirm'                => 'Підтвердження',
'excontent'              => 'зміст: «$1»',
'excontentauthor'        => 'зміст був: «$1» (єдиним автором був [[Special:Contributions/$2|$2]])',
'exbeforeblank'          => 'зміст до очистки: «$1»',
'exblank'                => 'стаття була порожньою',
'delete-confirm'         => 'Вилучення «$1»',
'delete-legend'          => 'Вилучення',
'historywarning'         => "'''Попередження:''' Сторінка, яку ви збираєтеся вилучити, має історію редагувань з приблизно $1 {{PLURAL:$1|версії|версій}}:",
'confirmdeletetext'      => 'Ви збираєтесь вилучити сторінку і всі її журнали редагувань з бази даних.
Будь ласка, підтвердіть, що ви бажаєте зробити це, повністю розумієте наслідки і що робите це у відповідності з [[{{MediaWiki:Policy-url}}|правилами]].',
'actioncomplete'         => 'Дію виконано',
'actionfailed'           => 'Виконати дію не вдалося',
'deletedtext'            => '"<nowiki>$1</nowiki>" було вилучено.
Див. $2 для перегляду списку останніх вилучень.',
'deletedarticle'         => 'вилучив «[[$1]]»',
'suppressedarticle'      => 'прихована «[[$1]]»',
'dellogpage'             => 'Журнал вилучень',
'dellogpagetext'         => 'Нижче наведений список останніх вилучень.',
'deletionlog'            => 'журнал вилучень',
'reverted'               => 'Повернуто до більш ранньої версії',
'deletecomment'          => 'Причина:',
'deleteotherreason'      => 'Інша/додаткова причина:',
'deletereasonotherlist'  => 'Інша причина',
'deletereason-dropdown'  => '* Типові причини вилучення
** вандалізм
** за запитом автора
** порушення авторських прав',
'delete-edit-reasonlist' => 'Редагувати причини вилучення',
'delete-toobig'          => 'У цієї сторінки дуже довга історія редагувань, більше $1 {{PLURAL:$1|версії|версій|версій}}.
Вилучення таких сторінок було заборонене з метою уникнення порушень у роботі сайту {{SITENAME}}.',
'delete-warning-toobig'  => 'У цієї сторінки дуже довга історія редагувань, більше $1 {{PLURAL:$1|версії|версій|версій}}.
Її вилучення може призвести до порушень у роботі бази даних сайту {{SITENAME}};
дійте обережно.',

# Rollback
'rollback'          => 'Відкинути редагування',
'rollback_short'    => 'Відкинути',
'rollbacklink'      => 'відкинути',
'rollbackfailed'    => 'Відкинути зміни не вдалося',
'cantrollback'      => 'Неможливо відкинути редагування, останній, хто редагував, є єдиним автором цієї сторінки.',
'alreadyrolled'     => 'Неможливо відкинути останні редагування [[:$1]], зроблені [[User:$2|$2]] ([[User talk:$2|обговорення]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]); хтось інший уже змінив чи відкинув редагування цієї статті.

Останні редагування зробив [[User:$3|$3]] ([[User talk:$3|обговорення]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "Пояснення редагування було: «''$1''».",
'revertpage'        => 'Редагування користувача [[Special:Contributions/$2|$2]] ([[User talk:$2|обговорення]]) відкинуті до версії користувача [[User:$1|$1]]',
'revertpage-nouser' => "Відкинуто редагування (ім'я користувача приховано) до зробленого [[User:$1|$1]]",
'rollback-success'  => 'Відкинуті редагування користувача $1; повернення до версії користувача $2.',

# Edit tokens
'sessionfailure-title' => 'Помилка сеансу',
'sessionfailure'       => 'Здається, виникли проблеми з поточним сеансом роботи;
ця дія була скасована з метою попередити «захоплення сеансу».
Будь ласка, натисніть кнопку «Назад» і перезавантажте сторінку, з якої ви прийшли.',

# Protect
'protectlogpage'              => 'Журнал захисту',
'protectlogtext'              => 'Нижче наведено список установлень і знять захисту зі сторінки.
Ви також можете переглянути [[Special:ProtectedPages|список захищених сторінок]].',
'protectedarticle'            => 'захист на [[$1]] встановлено',
'modifiedarticleprotection'   => 'змінено рівень захисту сторінки «[[$1]]»',
'unprotectedarticle'          => 'знято захист зі сторінки «[[$1]]»',
'movedarticleprotection'      => 'переніс налаштування захисту з «[[$2]]» на «[[$1]]»',
'protect-title'               => 'Встановлення захисту для «$1»',
'prot_1movedto2'              => '«[[$1]]» перейменована на «[[$2]]»',
'protect-legend'              => 'Підтвердження встановлення захисту',
'protectcomment'              => 'Причина:',
'protectexpiry'               => 'Закінчується:',
'protect_expiry_invalid'      => 'Неправильний час закінчення захисту.',
'protect_expiry_old'          => 'Час закінчення — в минулому.',
'protect-unchain-permissions' => 'Відкрити доступ до додаткових параметрів захисту',
'protect-text'                => "Тут ви можете переглянути та змінити рівень захисту сторінки '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "Ви не можете змінювати рівень захисту сторінки, доки ваш обліковий запис заблокований.
Поточні установки для сторінки '''$1''':",
'protect-locked-dblock'       => "Рівень захисту не може бути зміненим, так як основна база даних тимчасово заблокована.
Поточні установки для сторінки '''$1''':",
'protect-locked-access'       => "У вашого облікового запису недостатньо прав для зміни рівня захисту сторінки.
Поточні установки для сторінки: '''$1''':",
'protect-cascadeon'           => 'Ця сторінка захищена, бо вона включена {{PLURAL:$1|до зазначеної нижче сторінки, на яку|до нижчезазначених сторінок, на які}} встановлено каскадний захист. Ви можете змінити рівень захисту цієї сторінки, але це не вплине на каскадний захист.',
'protect-default'             => 'Дозволити всім користувачам',
'protect-fallback'            => 'Потрібен дозвіл «$1»',
'protect-level-autoconfirmed' => 'Захистити від нових і незареєстрованих користувачів',
'protect-level-sysop'         => 'Тільки адміністратори',
'protect-summary-cascade'     => 'каскадний',
'protect-expiring'            => 'закінчується $1 (UTC)',
'protect-expiry-indefinite'   => 'безстроково',
'protect-cascade'             => 'Захистити сторінки, що включені до цієї сторінки (каскадний захист)',
'protect-cantedit'            => 'Ви не можете змінювати рівень захисту цієї сторінки, тому що ви не маєте прав для її редагування.',
'protect-othertime'           => 'Інший час/термін:',
'protect-othertime-op'        => 'інший час/термін',
'protect-existing-expiry'     => 'Поточний час завершення: $3, $2',
'protect-otherreason'         => 'Інша/додаткова причина:',
'protect-otherreason-op'      => 'Інша причина',
'protect-dropdown'            => '* Типові причини захисту
** частий вандалізм
** надмірний спам
** непродуктивна війна редагувань
** популярна сторінка',
'protect-edit-reasonlist'     => 'Причини захисту від редагувань',
'protect-expiry-options'      => '1 година:1 hour,1 день:1 day,1 тиждень:1 week,2 тижні:2 weeks,1 місяць:1 month,3 місяці:3 months,6 місяців:6 months,1 рік:1 year,назавжди:infinite',
'restriction-type'            => 'Права:',
'restriction-level'           => 'Рівень доступу:',
'minimum-size'                => 'Мінімальний розмір',
'maximum-size'                => 'Максимальний розмір:',
'pagesize'                    => '(байтів)',

# Restrictions (nouns)
'restriction-edit'   => 'Редагування',
'restriction-move'   => 'Перейменування',
'restriction-create' => 'Створення',
'restriction-upload' => 'Завантаження',

# Restriction levels
'restriction-level-sysop'         => 'повний захист',
'restriction-level-autoconfirmed' => 'частковий захист',
'restriction-level-all'           => 'усі рівні',

# Undelete
'undelete'                     => 'Перегляд вилучених сторінок',
'undeletepage'                 => 'Перегляд і відновлення вилучених сторінок',
'undeletepagetitle'            => "'''Нижче наведені вилучені версії [[:$1|$1]]'''.",
'viewdeletedpage'              => 'Переглянути видалені сторінки',
'undeletepagetext'             => '{{PLURAL:$1|Сторінка була вилучена, однак вона все ще знаходиться в архіві, тому може бути відновлена|Такі сторінки були вилучені, але вони все ще знаходяться в архіві і тому можуть бути відновлені}}. Архів періодично очищається.',
'undelete-fieldset-title'      => 'Відновити версії',
'undeleteextrahelp'            => "Для повного відновлення сторінки залиште всі позначки порожніми й натисніть '''«Відновити»'''.
Для часткового відновлення позначте ті версії сторінки, які необхідно відновити та натисніть '''«Відновити»'''.
Щоб прибрати всі позначки й очистити коментар, натисніть '''«Очистити»'''.",
'undeleterevisions'            => 'В архіві $1 {{PLURAL:$1|версія|версії|версій}}',
'undeletehistory'              => 'Якщо ви відновите сторінку, всі версії будуть також відновлені, разом з журналом редагувань.
Якщо з моменту вилучення була створена нова сторінка з такою самою назвою, відновлені версії будуть зазначені в журналі редагувань перед новими записами, але поточна версія існуючої статті не буде замінена автоматично.',
'undeleterevdel'               => 'Відновлення не буде здійснене, якщо воно призведе до часткового вилучення останньої версії сторінки або файлу. У подібному випадку ви повинні зняти позначку або показати останні вилучені версії.',
'undeletehistorynoadmin'       => 'Стаття вилучена. Причина вилучення та список користувачів, що редагували статтю до вилучення, вказані нижче. Текст вилученої статті можуть переглянути лише адміністратори.',
'undelete-revision'            => 'Вилучена версія $1 (від $4 $5) користувача $3:',
'undeleterevision-missing'     => 'Невірна версія. Помилкове посилання, або вказану версію сторінки вилучено з архіву.',
'undelete-nodiff'              => 'Не знайдена попередня версія.',
'undeletebtn'                  => 'Відновити',
'undeletelink'                 => 'переглянути/відновити',
'undeleteviewlink'             => 'переглянути',
'undeletereset'                => 'Очистити',
'undeleteinvert'               => 'Інвертувати виділення',
'undeletecomment'              => 'Причина:',
'undeletedarticle'             => 'відновив «[[$1]]»',
'undeletedrevisions'           => '$1 {{PLURAL:$1|редагування|редагування|редагувань}} відновлено',
'undeletedrevisions-files'     => '$1 {{PLURAL:$1|версія|версії|версій}} та $2 {{PLURAL:$2|файл|файли|файлів}} відновлено',
'undeletedfiles'               => '$1 {{PLURAL:$1|файл|файли|файлів}} відновлено',
'cannotundelete'               => 'Не вдалося скасувати видалення, хтось інший вже міг відмінити видалення сторінки.',
'undeletedpage'                => "'''Сторінка «$1» відновлена'''

Див. [[Special:Log/delete|список вилучень]], щоб дізнатися про останні вилучення та відновлення.",
'undelete-header'              => 'Список нещодавно вилучених сторінок можна переглянути в [[Special:Log/delete|журналі вилучень]].',
'undelete-search-box'          => 'Пошук вилучених сторінок',
'undelete-search-prefix'       => 'Показати сторінки, що починаються з:',
'undelete-search-submit'       => 'Знайти',
'undelete-no-results'          => 'Не знайдено потрібних сторінок серед вилучених.',
'undelete-filename-mismatch'   => 'Неможливо відновити версію файлу з відміткою часу $1: невідповідність назви файлу',
'undelete-bad-store-key'       => 'Неможливо відновити версію файлу з позначкою часу $1: файл був відсутнім до вилучення.',
'undelete-cleanup-error'       => 'Помилка вилучення архівного файлу, що не використовується, «$1».',
'undelete-missing-filearchive' => 'Неможливо відновити файл з архівним ідентифікатором $1, так як він відсутній у базі даних. Можливо, файл уже був відновлений.',
'undelete-error-short'         => 'Помилка відновлення файлу: $1',
'undelete-error-long'          => 'Під час відновлення файлу виникли помилки:

$1',
'undelete-show-file-confirm'   => 'Ви впевнені, що хочете переглянути вилучену версію файлу «<nowiki>$1</nowiki>» від $3 $2?',
'undelete-show-file-submit'    => 'Так',

# Namespace form on various pages
'namespace'      => 'Простір назв:',
'invert'         => 'Крім вибраного',
'blanknamespace' => '(Основний)',

# Contributions
'contributions'       => 'Внесок користувача',
'contributions-title' => 'Внесок користувача $1',
'mycontris'           => 'Мій внесок',
'contribsub2'         => 'Внесок $1 ($2)',
'nocontribs'          => 'Редагувань, що задовольняють заданим умовам не знайдено.',
'uctop'               => ' (остання)',
'month'               => 'Від місяця (і раніше):',
'year'                => 'Від року (і раніше):',

'sp-contributions-newbies'             => 'Показати лише внесок з нових облікових записів',
'sp-contributions-newbies-sub'         => 'Внесок новачків',
'sp-contributions-newbies-title'       => 'Внесок з нових облікових записів',
'sp-contributions-blocklog'            => 'протокол блокувань',
'sp-contributions-deleted'             => 'вилучені редагування користувача',
'sp-contributions-logs'                => 'журнали',
'sp-contributions-talk'                => 'обговорення',
'sp-contributions-userrights'          => 'управління правами користувача',
'sp-contributions-blocked-notice'      => 'Цей користувач наразі заблокований. Останній запис у журналі блокувань такий:',
'sp-contributions-blocked-notice-anon' => 'Доступ з цієї IP-адреси зараз заблокований.
Далі наведено останній запис з журналу блокувань:',
'sp-contributions-search'              => 'Пошук внеску',
'sp-contributions-username'            => "IP-адреса або ім'я користувача:",
'sp-contributions-toponly'             => 'Показувати тільки редагування, що є останніми версіями',
'sp-contributions-submit'              => 'Знайти',

# What links here
'whatlinkshere'            => 'Посилання сюди',
'whatlinkshere-title'      => 'Сторінки, що посилаються на "$1"',
'whatlinkshere-page'       => 'Сторінка:',
'linkshere'                => "Наступні сторінки посилаються на '''[[:$1]]''':",
'nolinkshere'              => "На статтю '''[[:$1]]''' не вказує жодна стаття.",
'nolinkshere-ns'           => "У вибраному просторі назв нема сторінок, що посилаються на '''[[:$1]]'''.",
'isredirect'               => 'сторінка-перенаправлення',
'istemplate'               => 'включення',
'isimage'                  => 'посилання на зображення',
'whatlinkshere-prev'       => '{{PLURAL:$1|попередня|попередні|попередні}} $1',
'whatlinkshere-next'       => '{{PLURAL:$1|наступна|наступні|наступні}} $1',
'whatlinkshere-links'      => '← посилання',
'whatlinkshere-hideredirs' => '$1 перенаправлення',
'whatlinkshere-hidetrans'  => '$1 включення',
'whatlinkshere-hidelinks'  => '$1 посилання',
'whatlinkshere-hideimages' => '$1 посилання на зображення',
'whatlinkshere-filters'    => 'Фільтри',

# Block/unblock
'blockip'                         => 'Заблокувати користувача',
'blockip-title'                   => 'Блокування користувача',
'blockip-legend'                  => 'Блокування користувача',
'blockiptext'                     => 'Наступна форма дозволяє заблокувати можливість редагування із зазначеної IP-адреси або окремого коирстувача.
Робити це можна тільки для попередження [[{{ns:project}}:Вандалізм|вандалізму]] і тільки у відповідності до [[{{MediaWiki:Policy-url}}|правил {{grammar:genitive|{{SITENAME}}}}]].
При блокуванні не забудьте зазначити причину (наприклад, вкажіть деякі сторінки з ознаками вандалізму).',
'ipaddress'                       => 'IP-адреса:',
'ipadressorusername'              => "IP-адреса або ім'я користувача:",
'ipbexpiry'                       => 'Термін:',
'ipbreason'                       => 'Причина:',
'ipbreasonotherlist'              => 'Інша причина',
'ipbreason-dropdown'              => "* Типові причини блокування
** Вставка неправильної інформації
** Видалення змісту сторінок
** Спам, рекламні посилання
** Вставка нісенітниці/лайки в текст
** Залякуюча поведінка/переслідування
** Зловживання кількома обліковими записами
** Неприйнятне ім'я користувача",
'ipbanononly'                     => 'Блокувати тільки анонімних користувачів',
'ipbcreateaccount'                => 'Заборонити створення нових облікових записів',
'ipbemailban'                     => 'Заборонити користувачеві відправляти листи електронною поштою',
'ipbenableautoblock'              => 'Автоматично блокувати IP-адреси, які використовуються цим користувачем та будь-які наступні адреси, з яких він буде редагувати',
'ipbsubmit'                       => 'Заблокувати доступ цьому користувачу',
'ipbother'                        => 'Інший термін',
'ipboptions'                      => '2 години:2 hours,1 день:1 day,3 дні:3 days,1 тиждень:1 week,2 тижні:2 weeks,1 місяць:1 month,3 місяці:3 months,6 місяців:6 months,1 рік:1 year,назавжди:infinite',
'ipbotheroption'                  => 'інший термін',
'ipbotherreason'                  => 'Інша/додаткова причина:',
'ipbhidename'                     => "Приховати ім'я користувача у редагуваннях ті списках",
'ipbwatchuser'                    => 'Додати до списку спостереження сторінку користувача і його обговорення',
'ipballowusertalk'                => 'Дозволити цьому користувачеві редагувати свою сторінку обговорення на час блокування',
'ipb-change-block'                => 'Переблокувати користувача з цими налаштуваннями',
'badipaddress'                    => 'IP-адреса записана в невірному форматі, або користувача з таким іменем не існує.',
'blockipsuccesssub'               => 'Блокування проведено',
'blockipsuccesstext'              => '[[Special:Contributions/$1|«$1»]] заблоковано.<br />
Див. [[Special:IPBlockList|список заблокованих IP-адрес]].',
'ipb-edit-dropdown'               => 'Редагувати причини блокувань',
'ipb-unblock-addr'                => 'Розблокувати $1',
'ipb-unblock'                     => 'Розблокувати користувача або IP-адресу',
'ipb-blocklist-addr'              => 'Діючі блокування для $1',
'ipb-blocklist'                   => 'Показати діючі блокування',
'ipb-blocklist-contribs'          => 'Внесок користувача $1',
'unblockip'                       => 'Розблокувати IP-адресу',
'unblockiptext'                   => 'Використовуйте подану нижче форму, щоб відновити можливість збереження з раніше заблокованої IP-адреси.',
'ipusubmit'                       => 'Зняти це блокування',
'unblocked'                       => '[[User:$1|$1]] розблокований',
'unblocked-id'                    => 'Блокування $1 було зняте',
'ipblocklist'                     => 'Список заблокованих IP-адрес та користувачів',
'ipblocklist-legend'              => 'Пошук заблокованого користувача',
'ipblocklist-username'            => 'Користувач або IP-адреса:',
'ipblocklist-sh-userblocks'       => '$1 блокування облікових записів',
'ipblocklist-sh-tempblocks'       => '$1 тимчасові блокування',
'ipblocklist-sh-addressblocks'    => '$1 блокування окремих IP-адрес',
'ipblocklist-submit'              => 'Пошук',
'ipblocklist-localblock'          => 'Локальне блокування',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|Інше блокування|Інші блокування}}',
'blocklistline'                   => '$1, $2 заблокував $3 ($4)',
'infiniteblock'                   => 'блокування на невизначений термін',
'expiringblock'                   => 'закінчиться $1 $2',
'anononlyblock'                   => 'тільки анонімів',
'noautoblockblock'                => 'автоблокування вимкнене',
'createaccountblock'              => 'Створення облікових записів заблоковане',
'emailblock'                      => 'листи заборонені',
'blocklist-nousertalk'            => 'не може редагувати свою сторінку обговорення',
'ipblocklist-empty'               => 'Список блокувань порожній.',
'ipblocklist-no-results'          => "Запрохані IP-адреса або ім'я користувача не заблоковані.",
'blocklink'                       => 'заблокувати',
'unblocklink'                     => 'розблокувати',
'change-blocklink'                => 'змінити блокування',
'contribslink'                    => 'внесок',
'autoblocker'                     => 'Доступ заблоковано автоматично, тому що ви використовуєте ту саму адресу, що й "[[User:$1|$1]]". Причина блокування $1: "$2".',
'blocklogpage'                    => 'Журнал блокувань',
'blocklog-showlog'                => 'Цього користувача вже заблоковано. Далі наведено журнал блокувань:',
'blocklog-showsuppresslog'        => 'Цього користувача вже заблоковано і приховано. Далі наведено журнал приховань:',
'blocklogentry'                   => 'заблокував [[$1]] на термін $2 $3',
'reblock-logentry'                => 'змінив налаштування блокування для [[$1]] з терміном до $2 $3',
'blocklogtext'                    => 'Журнал блокування й розблокування користувачів.
IP-адреси, що блокуються автоматично тут не вказуються. Див.
[[Special:IPBlockList|список поточних заборон і блокувань]].',
'unblocklogentry'                 => 'розблокував $1',
'block-log-flags-anononly'        => 'тільки анонімні користувачі',
'block-log-flags-nocreate'        => 'заборонена реєстрація облікових записів',
'block-log-flags-noautoblock'     => 'автоблокування вимкнене',
'block-log-flags-noemail'         => 'електронні листи заборонені',
'block-log-flags-nousertalk'      => 'не може редагувати власну сторінку обговорення',
'block-log-flags-angry-autoblock' => 'увімкнене покращене автоблокування',
'block-log-flags-hiddenname'      => "ім'я користувача приховане",
'range_block_disabled'            => 'Адміністраторам заборонено блокувати діапазони.',
'ipb_expiry_invalid'              => 'Невірно вказано термін.',
'ipb_expiry_temp'                 => 'Блокування із приховуванням імені користувача мають бути безстроковими.',
'ipb_hide_invalid'                => 'Неможливо приховати обліковий запис; з нього зроблено дуже багато редагувань.',
'ipb_already_blocked'             => '«$1» уже заблоковано. Для того, щоб призначити новий термін блокування, спочатку розблокуйте його.',
'ipb-needreblock'                 => '== Уже заблокований ==
Користувач $1 вже заблокований. Хочете змінити параметри блокування?',
'ipb-otherblocks-header'          => '{{PLURAL:$1|Інше блокування|Інші блокування}}',
'ipb_cant_unblock'                => 'Помилка: блокування з ID $1 не знайдене.
Можливо, користувач уже розблокований.',
'ipb_blocked_as_range'            => 'Помилка: IP-адреса $1 була заблокована не напряму і не може бути розблокована. Однак, вона належить до заблокованого діапазону $2, який можна розблокувати.',
'ip_range_invalid'                => 'Неприпустимий діапазон IP-адрес.',
'ip_range_toolarge'               => 'Блокування діапазонів, більших за /$1, не дозволені.',
'blockme'                         => 'Заблокуй мене',
'proxyblocker'                    => 'Блокування проксі',
'proxyblocker-disabled'           => 'Функція відключена.',
'proxyblockreason'                => "Ваша IP-адреса заблокована, тому що це — відкритий проксі.
Будь ласка, зв'яжіться з вашим Інтернет-провайдером чи службою підтримки й повідомте їм про цю серйозну проблему безпеки.",
'proxyblocksuccess'               => 'Виконано.',
'sorbsreason'                     => 'Ваша IP-адреса числиться як відкритий проксі в DNSBL.',
'sorbs_create_account_reason'     => 'Ваша IP-адреса числиться як відкритий проксі в DNSBL. Ви не можете створити обліковий запис.',
'cant-block-while-blocked'        => 'Ви не можете блокувати інших користувачів, поки ви самі заблоковані.',
'cant-see-hidden-user'            => 'Користувача, якого ви хочете заблокувати, вже заблоковано та приховано. Оскільки у вас немає прав щодо приховання користувачів, ви не можете переглянути або змінити дане блокування.',
'ipbblocked'                      => 'Ви не можете блокувати чи розблоковувати інших користувачів, оскільки самі заблоковані',
'ipbnounblockself'                => 'Ви не можете розблокувати себе',

# Developer tools
'lockdb'              => 'Заблокувати базу даних (режим "тільки для читання")',
'unlockdb'            => 'Розблокувати базу даних',
'lockdbtext'          => 'Блокування бази даних унеможливить для всіх користувачів редагування сторінок, зміну налаштувань, списків спостереження та виконання інших дій, що вимагають доступу до бази даних.
Будь ласка, підтвердіть, що це — саме те, що ви бажаєте зробити, і що ви знімете блокування, коли закінчите обслуговування бази даних.',
'unlockdbtext'        => 'Розблокування бази даних надасть змогу знову редагувати сторінки, змінювати налаштування, списки спостереження та виконувати інші дії, що вимагають доступу до бази даних.
Будь ласка, підтвердіть, що ви справді хочете це зробити.',
'lockconfirm'         => "Так, я дійсно хочу заблокувати базу даних (перейти в режим ''тільки для читання'').",
'unlockconfirm'       => 'Так, я дійсно хочу розблокувати базу даних.',
'lockbtn'             => "Заблокувати базу даних (режим ''тільки для читання'')",
'unlockbtn'           => 'Розблокувати базу даних',
'locknoconfirm'       => 'Ви не поставили галочку в поле підтвердження.',
'lockdbsuccesssub'    => 'Базу даних заблоковано',
'unlockdbsuccesssub'  => 'Базу даних розблоковано',
'lockdbsuccesstext'   => 'Базу даних проекту заблоковано.<br />
Не забудьте її [[Special:UnlockDB|розблокувати]] після завершення обслуговування.',
'unlockdbsuccesstext' => 'Базу даних проекту розблоковано.',
'lockfilenotwritable' => 'Немає права на запис в файл блокування бази даних. Щоб заблокувати чи розблокувати БД, веб-сервер повинен мати дозвіл на запис в цей файл.',
'databasenotlocked'   => 'База даних не заблокована.',

# Move page
'move-page'                    => 'Перейменування сторінки «$1»',
'move-page-legend'             => 'Перейменування сторінки',
'movepagetext'                 => "Скориставшись формою нижче, ви можете перейменувати сторінку, одночасно перемістивши на нове місце і журнал її редагувань.
Стара назва стане перенаправленням на нову назву.
Ви можете автоматично оновити перенаправлення на стару назву.
Якщо ви цього не зробите, будь ласка, перевірте наявність [[Special:DoubleRedirects|подвійних]] чи [[Special:BrokenRedirects|розірваних]] перенаправлень.
Ви відповідаєте за те, щоб посилання і надалі вказували туди, куди припускалося.

Зверніть увагу, що сторінка '''не''' буде перейменована, якщо сторінка з новою назвою вже існує, окрім випадків, коли вона порожня або є перенаправленням, а журнал її редагувань порожній.
Це означає, що ви можете повернути сторінці стару назву, якщо ви перейменували її помилково, але ви не можете затерти існуючу сторінку.

'''ПОПЕРЕДЖЕННЯ!'''
Ця дія може стати причиною серйозних та неочікуваних змін популярних сторінок.
Будь ласка, перед продовженням переконайтесь, що ви розумієте всі можливі наслідки.",
'movepagetalktext'             => "Приєднана сторінка обговорення також буде автоматично перейменована, '''окрім наступних випадків:'''
* Непорожня сторінка обговорення з такою назвою вже існує або
* Ви не поставили галочку в полі нижче.

У цих випадках ви будете змушені перейменувати чи об'єднати сторінки вручну в разі необхідності",
'movearticle'                  => 'Перейменувати сторінку',
'moveuserpage-warning'         => "'''Увага:''' Ви збираєтеся перейменувати сторінку користувача. Будь ласка, зверніть увагу, що  буде перейменовано тільки сторінку, але користувача '''не''' буде перейменовано.",
'movenologin'                  => 'Ви не ввійшли в систему',
'movenologintext'              => 'Ви повинні [[Special:UserLogin|ввійти до системи]], щоб перейменувати сторінку.',
'movenotallowed'               => 'У вас нема дозволу перейменовувати сторінки.',
'movenotallowedfile'           => 'У вас немає прав перейменовувати файли.',
'cant-move-user-page'          => 'У вас нема дозволу перейменовувати сторінки користувачів.',
'cant-move-to-user-page'       => 'У вас нема дозволу перейменовувати сторінки на сторінки простору «Користувач» (окрім підсторінок)',
'newtitle'                     => 'Нова назва:',
'move-watch'                   => 'Спостерігати за цією сторінкою',
'movepagebtn'                  => 'Перейменувати сторінку',
'pagemovedsub'                 => 'Сторінка перейменована',
'movepage-moved'               => "'''Сторінка «$1» перейменована на «$2»'''",
'movepage-moved-redirect'      => 'Створено перенаправлення.',
'movepage-moved-noredirect'    => 'Створення перенаправлення було заборонене.',
'articleexists'                => 'Сторінка з такою назвою вже існує або зазначена вами назва недопустима.
Будь ласка, оберіть іншу назву.',
'cantmove-titleprotected'      => 'Неможливо перейменувати сторінку, оскільки нова назва входить до списку заборонених.',
'talkexists'                   => "'''Сторінку перейменовано, але її сторінка обговорення не може бути перейменована, бо сторінка з такою назвою вже існує. Будь ласка, об'єднайте їх вручну.'''",
'movedto'                      => 'тепер називається',
'movetalk'                     => 'Перейменувати відповідну сторінку обговорення',
'move-subpages'                => 'Перейменувати підсторінки (до $1)',
'move-talk-subpages'           => 'Перейменувати підсторінки сторінки обговорення (до $1)',
'movepage-page-exists'         => 'Сторінка $1 вже існує і не може бути автоматично перезаписана.',
'movepage-page-moved'          => 'Сторінка $1 перейменована на $2.',
'movepage-page-unmoved'        => 'Сторінка $1 не може бути перейменована на $2.',
'movepage-max-pages'           => '$1 {{PLURAL:$1|сторінка була перейменована|сторінки були перейменовані|сторінок були перейменовані}} — це максимум, більше сторінок не можна перейменувати автоматично.',
'1movedto2'                    => 'перейменував «[[$1]]» на «[[$2]]»',
'1movedto2_redir'              => 'перейменував «[[$1]]» на «[[$2]]» поверх перенаправлення',
'move-redirect-suppressed'     => 'перенаправлення зі старої назви не створене',
'movelogpage'                  => 'Журнал перейменувань',
'movelogpagetext'              => 'Далі подано список перейменованих сторінок.',
'movesubpage'                  => '{{PLURAL:$1|Підсторінка|Підсторінки}}',
'movesubpagetext'              => 'Ця сторінка має $1 {{PLURAL:$1|підсторінку|підсторінки|підсторінок}}.',
'movenosubpage'                => 'Ця сторінка не має підсторінок.',
'movereason'                   => 'Причина:',
'revertmove'                   => 'відкинути',
'delete_and_move'              => 'Вилучити і перейменувати',
'delete_and_move_text'         => '== Потрібне вилучення ==
Сторінка з назвою [[:$1|«$1»]] вже існує.
Бажаєте вилучити її для можливості перейменування?',
'delete_and_move_confirm'      => 'Так, вилучити цю сторінку',
'delete_and_move_reason'       => 'Вилучена для можливості перейменування',
'selfmove'                     => 'Неможливо перейменувати сторінку: поточна й нова назви сторінки співпадають.',
'immobile-source-namespace'    => 'Не можна перейменовувати сторінки з простору назв «$1»',
'immobile-target-namespace'    => 'Не можна перейменовувати сторінки до простору назв «$1»',
'immobile-target-namespace-iw' => 'Інтервікі-посилання не підходить для перейменування сторінки.',
'immobile-source-page'         => 'Цю сторінку не можна перейменувати.',
'immobile-target-page'         => 'Не можна присвоїти сторінці цю назву.',
'imagenocrossnamespace'        => 'Неможливо дати файлові назву з іншого простору назв',
'nonfile-cannot-move-to-file'  => 'Не можна перейменовувати сторінки з інших просторів назв на файли',
'imagetypemismatch'            => 'Нове розширення файлу не співпадає з його типом',
'imageinvalidfilename'         => 'Назва цільового файлу неправильна',
'fix-double-redirects'         => 'Виправити всі перенаправлення на попередню назву',
'move-leave-redirect'          => 'Залишити перенаправлення',
'protectedpagemovewarning'     => "'''Попередження:''' Ця сторінка захищена так, що її можуть перейменовувати тільки адміністратори.
Останній запис журналу наведений нижче для довідки:",
'semiprotectedpagemovewarning' => "'''Зауваження:''' Ця сторінка захищена так, що перейменовувати її можуть тільки зареєстровані користувачі.
Останній запис журналу наведений нижче для довідки:",
'move-over-sharedrepo'         => '== Файл існує ==
У спільному сховищі існує [[:$1]]. Перейменування файлу на цю назву призведе до перекриття файлу зі спільного сховища.',
'file-exists-sharedrepo'       => 'Обрана назва файлу вже використовується у спільному сховищі.
Будь ласка, оберіть іншу назву.',

# Export
'export'            => 'Експорт статей',
'exporttext'        => 'Ви можете експортувати текст та журнал змін конкретної сторінки чи кількох сторінок у XML, який пізніше можна [[Special:Import|імпортувати]] в іншу вікі, що використовує програмне забезпечення MediaWiki.

Щоб експортувати сторінки, введіть їх назви в поле редагування, одну назву на рядок і оберіть, бажаєте ви експортувати всю історію змін сторінок чи тільки останні версії статей.

Ви також можете використовувати спеціальну адресу для експорту тільки останньої версії. Наприклад, для сторінки «[[{{MediaWiki:Mainpage}}]]» ця адреса така: [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]].',
'exportcuronly'     => 'Включати тільки поточну версію, без повної історії',
'exportnohistory'   => "----
'''Зауваження:''' експорт всієї історії змін сторінок вимкнутий через проблеми з ресурсами.",
'export-submit'     => 'Експорт',
'export-addcattext' => 'Додати сторінки з категорії:',
'export-addcat'     => 'Додати',
'export-addnstext'  => 'Додати сторінки з простору назв:',
'export-addns'      => 'Додати',
'export-download'   => 'Зберегти як файл',
'export-templates'  => 'Включити шаблони',
'export-pagelinks'  => "Включити пов'язані сторінки з глибиною:",

# Namespace 8 related
'allmessages'                   => 'Системні повідомлення',
'allmessagesname'               => 'Назва',
'allmessagesdefault'            => 'Стандартний текст',
'allmessagescurrent'            => 'Поточний текст',
'allmessagestext'               => 'Це список усіх системних повідомлень, які доступні в просторі назв «MediaWiki».
Будь ласка, відвідайте [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisation] і [http://translatewiki.net translatewiki.net], якщо ви хочете зробити внесок до спільної локалізації MediaWiki.',
'allmessagesnotsupportedDB'     => "Ця сторінка не може використовуватися, оскільки вимкнена опція '''\$wgUseDatabaseMessages'''.",
'allmessages-filter-legend'     => 'Фільтр',
'allmessages-filter'            => 'Фільтр за внесеними змінами:',
'allmessages-filter-unmodified' => 'Незмінені',
'allmessages-filter-all'        => 'Всі',
'allmessages-filter-modified'   => 'Змінені',
'allmessages-prefix'            => 'Фільтр за префіксом:',
'allmessages-language'          => 'Мова:',
'allmessages-filter-submit'     => 'Виконати',

# Thumbnails
'thumbnail-more'           => 'Збільшити',
'filemissing'              => 'Файл не знайдено',
'thumbnail_error'          => 'Помилка створення мініатюри: $1',
'djvu_page_error'          => 'Номер сторінки DjVu недосяжний',
'djvu_no_xml'              => 'Неможливо отримати XML для DjVu',
'thumbnail_invalid_params' => 'Помилковий параметр мініатюри',
'thumbnail_dest_directory' => 'Неможливо створити цільову директорію',
'thumbnail_image-type'     => 'Тип зображення не підтримується',
'thumbnail_gd-library'     => 'Неповна конфігурація бібліотеки GD, відсутня функція $1',
'thumbnail_image-missing'  => 'Очевидно, відсутній файл $1',

# Special:Import
'import'                     => 'Імпорт статей',
'importinterwiki'            => 'Міжвікі імпорт',
'import-interwiki-text'      => 'Вкажіть вікі й назву імпортованої сторінки.
Дати змін й імена авторів буде збережено.
Всі операції межвікі імпорту реєструються в [[Special:Log/import|відповідному протоколі]].',
'import-interwiki-source'    => 'Вікі/сторінка-джерело',
'import-interwiki-history'   => 'Копіювати всю історію змін цієї сторінки',
'import-interwiki-templates' => 'Включити всі шаблони',
'import-interwiki-submit'    => 'Імпортувати',
'import-interwiki-namespace' => 'Цільовий простір назв:',
'import-upload-filename'     => 'Назва файлу:',
'import-comment'             => 'Примітка:',
'importtext'                 => 'Будь ласка, експортуйте сторінку з іншої вікі, використовуючи [[Special:Export|засіб експорту]], збережіть файл, а потім завантажте його сюди.',
'importstart'                => 'Імпорт сторінок…',
'import-revision-count'      => '$1 {{PLURAL:$1|версія|версії|версій}}',
'importnopages'              => 'Сторінки для імпорту відсутні.',
'imported-log-entries'       => '{{PLURAL:$1|Заімпортований $1 запис журналу|Заімпортовані $1 записи журналу|Заімпортовані $1 записів журналу}}.',
'importfailed'               => 'Не вдалося імпортувати: $1',
'importunknownsource'        => 'Невідомий тип імпортованої сторінки',
'importcantopen'             => 'Неможливо відкрити файл імпорту',
'importbadinterwiki'         => 'Невірне інтервікі-посилання',
'importnotext'               => 'Текст відсутній',
'importsuccess'              => 'Імпорт виконано!',
'importhistoryconflict'      => 'Конфлікт існуючих версій (можливо, цю сторінку вже імпортували)',
'importnosources'            => 'Не було вибране джерело міжвікі-імпорту, пряме завантаження історії змін вимкнуте.',
'importnofile'               => 'Файл імпорту не було завантажено.',
'importuploaderrorsize'      => 'Не вдалося завантажити або імпортувати файл. Розмір файлу перевищує встановлену межу.',
'importuploaderrorpartial'   => 'Не вдалося завантажити або імпортувати файл. Він був завантажений лише частково.',
'importuploaderrortemp'      => 'Не вдалося завантажити або імпортувати файл. Тимчасова папка відсутня.',
'import-parse-failure'       => 'Помилка розбору XML під час імпорту',
'import-noarticle'           => 'Нема сторінки для імпорту!',
'import-nonewrevisions'      => 'Усі версії були раніше імпортовані.',
'xml-error-string'           => '$1 в рядку $2, позиції $3 (байт $4): $5',
'import-upload'              => 'Завантажити XML-дані',
'import-token-mismatch'      => 'Утрачені дані сеансу. Будь ласка, спробуйте ще раз.',
'import-invalid-interwiki'   => 'Неможливо імпортувати із зазначеної вікі.',

# Import log
'importlogpage'                    => 'Журнал імпорту',
'importlogpagetext'                => 'Імпорт адміністраторами сторінок з історією редагувань з інших вікі.',
'import-logentry-upload'           => '«[[$1]]» — імпорт з файлу',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|версія|версії|версій}}',
'import-logentry-interwiki'        => '«$1» — міжвікі імпорт',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|версія|версії|версій}} з $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Ваша сторінка користувача',
'tooltip-pt-anonuserpage'         => 'Сторінка користувача для вашої IP-адреси',
'tooltip-pt-mytalk'               => 'Ваша сторінка обговорення',
'tooltip-pt-anontalk'             => 'Обговорення редагувань з цієї IP-адреси',
'tooltip-pt-preferences'          => 'Ваші налаштування',
'tooltip-pt-watchlist'            => 'Список сторінок, за якими я спостерігаю',
'tooltip-pt-mycontris'            => 'Ваш внесок',
'tooltip-pt-login'                => "Тут можна зареєструватися в системі, але це не обов'язково.",
'tooltip-pt-anonlogin'            => "Тут можна зареєструватися в системі, але це не обов'язково.",
'tooltip-pt-logout'               => 'Вихід із системи',
'tooltip-ca-talk'                 => 'Обговорення змісту сторінки',
'tooltip-ca-edit'                 => 'Цю сторінку можна редагувати. Використовуйте, будь ласка, попередній перегляд перед збереженням.',
'tooltip-ca-addsection'           => 'Створити новий розділ',
'tooltip-ca-viewsource'           => 'Ця сторінка захищена від змін. Ви можете переглянути і скопіювати її вихідний текст.',
'tooltip-ca-history'              => 'Журнал змін сторінки',
'tooltip-ca-protect'              => 'Захистити сторінку від змін',
'tooltip-ca-unprotect'            => 'Зняти захист з цієї сторінки',
'tooltip-ca-delete'               => 'Вилучити цю сторінку',
'tooltip-ca-undelete'             => 'Відновити зміни сторінки, зроблені до її вилучення',
'tooltip-ca-move'                 => 'Перейменувати цю сторінку',
'tooltip-ca-watch'                => 'Додати цю сторінку до вашого списку спостереження',
'tooltip-ca-unwatch'              => 'Вилучити цю сторінку з вашого списку спостереження',
'tooltip-search'                  => 'Шукати',
'tooltip-search-go'               => 'Перейти до сторінки, що має точно таку назву (якщо вона існує)',
'tooltip-search-fulltext'         => 'Знайти сторінки, що містять зазначений текст',
'tooltip-p-logo'                  => 'Головна сторінка',
'tooltip-n-mainpage'              => 'Перейти на Головну сторінку',
'tooltip-n-mainpage-description'  => 'Перейти на головну сторінку',
'tooltip-n-portal'                => 'Про проект, про те, що ви можете зробити, де що знаходиться',
'tooltip-n-currentevents'         => 'Список поточних подій',
'tooltip-n-recentchanges'         => 'Список останніх змін',
'tooltip-n-randompage'            => 'Переглянути випадкову сторінку',
'tooltip-n-help'                  => 'Довідка з проекту',
'tooltip-t-whatlinkshere'         => 'Список усіх сторінок, що посилаються на цю сторінку',
'tooltip-t-recentchangeslinked'   => 'Останні зміни на сторінках, на які посилається ця сторінка',
'tooltip-feed-rss'                => 'Трансляція в RSS для цієї сторінки',
'tooltip-feed-atom'               => 'Трансляція в Atom для цієї сторінки',
'tooltip-t-contributions'         => 'Перегляд внеску цього користувача',
'tooltip-t-emailuser'             => 'Надіслати листа цьому користувачеві',
'tooltip-t-upload'                => 'Завантажити файли',
'tooltip-t-specialpages'          => 'Список спеціальних сторінок',
'tooltip-t-print'                 => 'Версія для друку цієї сторінки',
'tooltip-t-permalink'             => 'Постійне посилання на цю версію сторінки',
'tooltip-ca-nstab-main'           => 'Вміст статті',
'tooltip-ca-nstab-user'           => 'Перегляд сторінки користувача',
'tooltip-ca-nstab-media'          => 'Медіа-файл',
'tooltip-ca-nstab-special'        => 'Це спеціальна сторінка, вона недоступна для редагування',
'tooltip-ca-nstab-project'        => 'Сторінка проекту',
'tooltip-ca-nstab-image'          => 'Сторінка зображення',
'tooltip-ca-nstab-mediawiki'      => 'Сторінка повідомлення MediaWiki',
'tooltip-ca-nstab-template'       => 'Сторінка шаблону',
'tooltip-ca-nstab-help'           => 'Сторінка довідки',
'tooltip-ca-nstab-category'       => 'Сторінка категорії',
'tooltip-minoredit'               => 'Позначити це редагування як незначне',
'tooltip-save'                    => 'Зберегти ваші зміни',
'tooltip-preview'                 => 'Попередній перегляд сторінки, будь ласка, використовуйте перед збереженням!',
'tooltip-diff'                    => 'Показати зміни, що зроблені відносно початкового тексту.',
'tooltip-compareselectedversions' => 'Переглянути різницю між двома вказаними версіями цієї сторінки.',
'tooltip-watch'                   => 'Додати поточну сторінку до списку спостереження',
'tooltip-recreate'                => 'Відновити сторінку недивлячись на те, що її вилучено',
'tooltip-upload'                  => 'Почати завантаження',
'tooltip-rollback'                => 'Одним кліком прибрати зміни, зроблені останнім редактором',
'tooltip-undo'                    => 'Прибрати внесені зміни і показати попередній перегляд. Дозволяє зазначити причину скасування.',
'tooltip-preferences-save'        => 'Зберегти налаштування',
'tooltip-summary'                 => 'Введіть короткий опис',

# Stylesheets
'common.css'   => '/** Розміщений тут CSS буде застосовуватися до всіх тем оформлення */',
'monobook.css' => '/* Розміщений тут CSS буде застосовуватися до всіх тем оформлення Monobook */

/*
Це необхідно щоб в вікні пошуку кнопки не розбивались на два рядки
нажаль в main.css для кнопки Go прописані паддінги .5em.
Але український текст довший ("Перейти") --st0rm
*/

#searchGoButton {
    padding-left: 0em;
    padding-right: 0em;
    font-weight: bold;
}',

# Scripts
'common.js' => '/* Розміщений тут код JavaScript буде завантажений всім користувачам при зверненні до будь-якої сторінки */',

# Metadata
'nodublincore'      => 'Метадані Dublin Core RDF заборонені для цього сервера.',
'nocreativecommons' => 'Метадані Creative Commons RDF заборонені для цього сервера.',
'notacceptable'     => "Вікі-сервер не може подати дані в форматі, який міг би прочитати ваш браузер.<br />
The wiki server can't provide data in a format your client can read.",

# Attribution
'anonymous'        => '{{PLURAL:$1|Анонімний користувач|Анонімні користувачі}} {{grammar:genitive|{{SITENAME}}}}',
'siteuser'         => 'Користувач {{grammar:genitive|{{SITENAME}}}} $1',
'anonuser'         => 'анонімний користувач {{grammar:genitive|{{SITENAME}}}} $1',
'lastmodifiedatby' => 'Остання зміна $2, $1 користувачем $3.',
'othercontribs'    => 'Базується на праці $1.',
'others'           => 'інші',
'siteusers'        => '{{PLURAL:$2|Користувач|Користувачі}} {{grammar:genitive|{{SITENAME}}}} $1',
'anonusers'        => '{{PLURAL:$2|анонімний користувач|анонімні користувачі}} {{grammar:genitive|{{SITENAME}}}} $1',
'creditspage'      => 'Подяки',
'nocredits'        => 'Відсутній список користувачів для цієї статті',

# Spam protection
'spamprotectiontitle' => 'Спам-фільтр',
'spamprotectiontext'  => 'Сторінка, яку ви намагаєтесь зберегти, заблокована спам-фільтром.
Імовірно, це сталося через те, що вона містить посилання на зовнішній сайт, присутній у чорному списку.',
'spamprotectionmatch' => 'Наступне повідомлення отримане від спам-фільтра: $1.',
'spambot_username'    => 'Очистка спаму',
'spam_reverting'      => 'Відкинути до останньої версії, що не містить посилання на $1',
'spam_blanking'       => 'Всі версії містять посилання на $1, очистка',

# Info page
'infosubtitle'   => 'Інформація про сторінку',
'numedits'       => 'Кількість редагувань (сторінка): $1',
'numtalkedits'   => 'Кількість редагувань (сторінка обговорення): $1',
'numwatchers'    => 'Кількість спостерігачів: $1',
'numauthors'     => 'Кількість різних авторів (сторінка): $1',
'numtalkauthors' => 'Кількість авторів (сторінка обговорення): $1',

# Skin names
'skinname-standard'    => 'Стандартне',
'skinname-nostalgia'   => 'Ностальгія',
'skinname-cologneblue' => 'Кельнське синє',
'skinname-monobook'    => 'Моно-книга',
'skinname-myskin'      => 'Своє',
'skinname-chick'       => 'Курча',
'skinname-simple'      => 'Просте',
'skinname-modern'      => 'Сучасне',
'skinname-vector'      => 'Векторне',

# Math options
'mw_math_png'    => 'Завжди генерувати PNG',
'mw_math_simple' => 'HTML в простих випадках, інакше - PNG',
'mw_math_html'   => 'Якщо можливо - HTML, інакше PNG',
'mw_math_source' => 'Залишити в вигляді ТеХ (для текстових браузерів)',
'mw_math_modern' => 'Рекомендовано для сучасних браузерів',
'mw_math_mathml' => 'Якщо можливо - MathML (експериментальна опція)',

# Math errors
'math_failure'          => 'Неможливо розібрати вираз',
'math_unknown_error'    => 'невідома помилка',
'math_unknown_function' => 'невідома функція',
'math_lexing_error'     => 'лексична помилка',
'math_syntax_error'     => 'синтаксична помилка',
'math_image_error'      => 'Перетворення в PNG відбулося з помилкою; перевірте правильність встановлення latex, dvips, gs та convert',
'math_bad_tmpdir'       => 'Не вдається створити чи записати в тимчасовий каталог математики',
'math_bad_output'       => 'Не вдається створити чи записати в вихідний каталог математики',
'math_notexvc'          => 'Не знайдено програму texvc; Див. math/README — довідку про налаштування.',

# Patrolling
'markaspatrolleddiff'                 => 'Позначити як перевірену',
'markaspatrolledtext'                 => 'Позначити цю сторінку як перевірену',
'markedaspatrolled'                   => 'Позначена як перевірена',
'markedaspatrolledtext'               => 'Обрана версія [[:$1]] була позначена як відпатрульована.',
'rcpatroldisabled'                    => 'Патрулювання останніх змін заборонене',
'rcpatroldisabledtext'                => 'Можливість патрулювання останніх змін зараз вимкнена.',
'markedaspatrollederror'              => 'Неможливо позначити як перевірену',
'markedaspatrollederrortext'          => 'Ви повинні зазначити версію, яка буде позначена як перевірена.',
'markedaspatrollederror-noautopatrol' => 'Вам не дозволено позначати власні редагування як перевірені.',

# Patrol log
'patrol-log-page'      => 'Журнал патрулювання',
'patrol-log-header'    => 'Це журнал перевірених змін.',
'patrol-log-line'      => 'перевірив $1 сторінки $2 $3',
'patrol-log-auto'      => '(автоматично)',
'patrol-log-diff'      => 'версію $1',
'log-show-hide-patrol' => '$1 журнал патрулювання',

# Image deletion
'deletedrevision'                 => 'Вилучена стара версія $1',
'filedeleteerror-short'           => 'Помилка вилучення файлу: $1',
'filedeleteerror-long'            => 'Під час вилучення файлу виникли помилки:

$1',
'filedelete-missing'              => 'Файл «$1» не може бути вилучений, бо він не існує.',
'filedelete-old-unregistered'     => 'Зазначена версія файлу «$1» не існує у базі даних.',
'filedelete-current-unregistered' => 'Зазначений файл «$1» не існує в базі даних.',
'filedelete-archive-read-only'    => 'Архівна директорія «$1» не доступна веб-серверу для запису.',

# Browsing diffs
'previousdiff' => '← Попереднє редагування',
'nextdiff'     => 'Наступне редагування →',

# Media information
'mediawarning'         => "'''Увага''': цей файл може містити шкідливий програмний код, виконання якого може бути небезпечним для вашої системи.",
'imagemaxsize'         => "Обмеження розміру зображення:<br />''(для сторінок опису файлів)''",
'thumbsize'            => 'Розмір зменшеної версії зображення:',
'widthheight'          => '$1 × $2',
'widthheightpage'      => '$1 × $2, {{PLURAL:$3|$3 сторінка|$3 сторінки|$3 сторінок}}',
'file-info'            => '(розмір файлу: $1, MIME-тип: $2)',
'file-info-size'       => '($1 × $2 пікселів, розмір файлу: $3, MIME-тип: $4)',
'file-nohires'         => '<small>Нема версії з більшою роздільністю.</small>',
'svg-long-desc'        => '(SVG-файл, номінально $1 × $2 пікселів, розмір файлу: $3)',
'show-big-image'       => 'Повна роздільність',
'show-big-image-thumb' => '<small>Розмір при попередньому перегляді: $1 × $2 пікселів</small>',
'file-info-gif-looped' => 'кільцеве',
'file-info-gif-frames' => '$1 {{PLURAL:$1|кадр|кадри|кадрів}}',
'file-info-png-looped' => 'закільцьований',
'file-info-png-repeat' => 'продемонстрований $1 {{PLURAL:$1|раз|рази|разів}}',
'file-info-png-frames' => '$1 {{PLURAL:$1|кадр|кадри|кадрів}}',

# Special:NewFiles
'newimages'             => 'Галерея нових файлів',
'imagelisttext'         => "Нижче подано список з '''$1''' {{PLURAL:$1|файлу|файлів|файлів}}, відсортованих $2.",
'newimages-summary'     => 'Ця спеціальна сторінка показує останні завантажені файли.',
'newimages-legend'      => 'Фільтр',
'newimages-label'       => 'Назва файлу (або її частина):',
'showhidebots'          => '($1 ботів)',
'noimages'              => 'Файли відсутні.',
'ilsubmit'              => 'Шукати',
'bydate'                => 'за датою',
'sp-newimages-showfrom' => 'Показати нові зображення, починаючи з $2, $1',

# Bad image list
'bad_image_list' => 'Формат має бути наступним:

Будуть враховуватися лише елементи списку (рядки, що починаються з *). Перше посилання рядка має бути посиланням на заборонена для вставки зображення.
Наступні посилання у тому самому рядку будуть розглядатися як винятки, тобто статті, куди зображення може бути включене.',

# Metadata
'metadata'          => 'Метадані',
'metadata-help'     => 'Файл містить додаткові дані, які зазвичай додаються цифровими камерами чи сканерами. Якщо файл редагувався після створення, то деякі параметри можуть не відповідати цьому зображенню.',
'metadata-expand'   => 'Показати додаткові дані',
'metadata-collapse' => 'Приховати додаткові дані',
'metadata-fields'   => 'Поля метаданих, перераховані в цьому списку, будуть автоматично відображені на сторінці зображення, всі інші будуть приховані.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Ширина',
'exif-imagelength'                 => 'Висота',
'exif-bitspersample'               => 'Глибина кольору',
'exif-compression'                 => 'Метод стиснення',
'exif-photometricinterpretation'   => 'Колірна модель',
'exif-orientation'                 => 'Орієнтація кадру',
'exif-samplesperpixel'             => 'Кількість кольорових компонентів',
'exif-planarconfiguration'         => 'Принцип організації даних',
'exif-ycbcrsubsampling'            => 'Відношення розмірів компонентів Y та C',
'exif-ycbcrpositioning'            => 'Порядок розміщення компонентів Y та C',
'exif-xresolution'                 => 'Горизонтальна роздільна здатність',
'exif-yresolution'                 => 'Вертикальна роздільна здатність',
'exif-resolutionunit'              => 'Одиниця вимірювання роздільної здатності',
'exif-stripoffsets'                => 'Положення блоку даних',
'exif-rowsperstrip'                => 'Кількість рядків в 1 блоці',
'exif-stripbytecounts'             => 'Розмір стиснутого блоку',
'exif-jpeginterchangeformat'       => 'Положення початку блоку preview',
'exif-jpeginterchangeformatlength' => 'Розмір даних блоку preview',
'exif-transferfunction'            => 'Функція перетворення колірного простору',
'exif-whitepoint'                  => 'Колірність білої точки',
'exif-primarychromaticities'       => 'Колірність основних кольорів',
'exif-ycbcrcoefficients'           => 'Коефіцієнти перетворення колірної моделі',
'exif-referenceblackwhite'         => 'Положенння білої й чорної точок',
'exif-datetime'                    => 'Дата й час редагування файлу',
'exif-imagedescription'            => 'Назва зображення',
'exif-make'                        => 'Виробник камери',
'exif-model'                       => 'Модель камери',
'exif-software'                    => 'Програмне забезпечення',
'exif-artist'                      => 'Автор',
'exif-copyright'                   => 'Власник авторського права',
'exif-exifversion'                 => 'Версія Exif',
'exif-flashpixversion'             => 'Версія FlashPix, що підтримується',
'exif-colorspace'                  => 'Колірний простір',
'exif-componentsconfiguration'     => 'Конфігурація кольорових компонентів',
'exif-compressedbitsperpixel'      => 'Глибина кольору після стиснення',
'exif-pixelydimension'             => 'Повна висота зображення',
'exif-pixelxdimension'             => 'Повна ширина зображення',
'exif-makernote'                   => 'Додаткові дані виробника',
'exif-usercomment'                 => 'Додатковий коментар',
'exif-relatedsoundfile'            => 'Файл звукового коментаря',
'exif-datetimeoriginal'            => 'Оригинальні дата й час',
'exif-datetimedigitized'           => 'Дата й час оцифровки',
'exif-subsectime'                  => 'Долі секунд часу редагування файлу',
'exif-subsectimeoriginal'          => 'Долі секунд оригінального часу',
'exif-subsectimedigitized'         => 'Долі секунд часу оцифровки',
'exif-exposuretime'                => 'Час експозиції',
'exif-exposuretime-format'         => '$1 з ($2)',
'exif-fnumber'                     => 'Число діафрагми',
'exif-exposureprogram'             => 'Програма експозиції',
'exif-spectralsensitivity'         => 'Спектральна чутливість',
'exif-isospeedratings'             => 'Світлочутливість ISO',
'exif-oecf'                        => 'OECF (коефіцієнт оптикоелектричного перетворення)',
'exif-shutterspeedvalue'           => 'Витримка',
'exif-aperturevalue'               => 'Діафрагма',
'exif-brightnessvalue'             => 'Яскравість',
'exif-exposurebiasvalue'           => 'Компенсація експозиції',
'exif-maxaperturevalue'            => 'Мінімальне число діафрагми',
'exif-subjectdistance'             => "Відстань до об'єкту",
'exif-meteringmode'                => 'Режим вимірювання експозиції',
'exif-lightsource'                 => 'Джерело світла',
'exif-flash'                       => 'Статус спалаху',
'exif-focallength'                 => 'Фокусна відстань',
'exif-focallength-format'          => '$1 мм',
'exif-subjectarea'                 => "Положення й площа об'єкту зйомки",
'exif-flashenergy'                 => 'Енергія спалаху',
'exif-spatialfrequencyresponse'    => 'Просторова частотна характеристика',
'exif-focalplanexresolution'       => 'Роздільна здатність по X в фокальній площині',
'exif-focalplaneyresolution'       => 'Роздільна здатність по Y в фокальній площині',
'exif-focalplaneresolutionunit'    => 'Одиниця вимірювання роздільної здатності в фокальній площині',
'exif-subjectlocation'             => "Положення об'єкту відносно лівого верхнього кута",
'exif-exposureindex'               => 'Індекс експозиції',
'exif-sensingmethod'               => 'Тип сенсора',
'exif-filesource'                  => 'Джерело файлу',
'exif-scenetype'                   => 'Тип сцени',
'exif-cfapattern'                  => 'Тип кольорового фільтра',
'exif-customrendered'              => 'Додаткова обробка',
'exif-exposuremode'                => 'Режим обрання експозиції',
'exif-whitebalance'                => 'Баланс білого',
'exif-digitalzoomratio'            => 'Коефіцієнт цифрового збільшення (цифровий зум)',
'exif-focallengthin35mmfilm'       => 'Еквівалентна фокусна відстань (для 35 мм плівки)',
'exif-scenecapturetype'            => 'Тип сцени при зйомці',
'exif-gaincontrol'                 => 'Підвищення яскравості',
'exif-contrast'                    => 'Контрастність',
'exif-saturation'                  => 'Насиченість',
'exif-sharpness'                   => 'Різкість',
'exif-devicesettingdescription'    => 'Опис налаштування камери',
'exif-subjectdistancerange'        => "Відстань до об'єкту зйомки",
'exif-imageuniqueid'               => 'Номер зображення (ID)',
'exif-gpsversionid'                => 'Версія блоку GPS-інформації',
'exif-gpslatituderef'              => 'Індекс широти',
'exif-gpslatitude'                 => 'Широта',
'exif-gpslongituderef'             => 'Індекс довготи',
'exif-gpslongitude'                => 'Довгота',
'exif-gpsaltituderef'              => 'Індекс висоти',
'exif-gpsaltitude'                 => 'Висота',
'exif-gpstimestamp'                => 'Час за GPS (атомним годинником)',
'exif-gpssatellites'               => 'Опис використаних супутників',
'exif-gpsstatus'                   => 'Статус приймача в момент зйомки',
'exif-gpsmeasuremode'              => 'Метод вимірювання положення',
'exif-gpsdop'                      => 'Точність вимірювання',
'exif-gpsspeedref'                 => 'Одиниці вимірювання швидкості',
'exif-gpsspeed'                    => 'Швидкість руху',
'exif-gpstrackref'                 => 'Тип азимута приймача GPS (справжній, магнітний)',
'exif-gpstrack'                    => 'Азимут приймача GPS',
'exif-gpsimgdirectionref'          => 'Тип азимута зображення (справжній, магнітний)',
'exif-gpsimgdirection'             => 'Азимут зображення',
'exif-gpsmapdatum'                 => 'Використана геодезична система координат',
'exif-gpsdestlatituderef'          => "Індекс довготи об'єкта",
'exif-gpsdestlatitude'             => "Довгота об'єкта",
'exif-gpsdestlongituderef'         => "Індекс широти об'єкта",
'exif-gpsdestlongitude'            => "Широта об'єкта",
'exif-gpsdestbearingref'           => "Тип пеленга об'єкта (справжній, магнітний)",
'exif-gpsdestbearing'              => "Пеленг об'єкта",
'exif-gpsdestdistanceref'          => 'Одиниці вимірювання відстані',
'exif-gpsdestdistance'             => 'Відстань',
'exif-gpsprocessingmethod'         => 'Метод обчислення положення',
'exif-gpsareainformation'          => 'Назва області GPS',
'exif-gpsdatestamp'                => 'Дата',
'exif-gpsdifferential'             => 'Диференціальна поправка',

# EXIF attributes
'exif-compression-1' => 'Нестиснутий',

'exif-unknowndate' => 'Невідома дата',

'exif-orientation-1' => 'Нормальна',
'exif-orientation-2' => 'Відображено по горизонталі',
'exif-orientation-3' => 'Повернуто на 180°',
'exif-orientation-4' => 'Відображено по вертикалі',
'exif-orientation-5' => 'Повернуто на 90° проти годинникової стрілки й відображено по вертикалі',
'exif-orientation-6' => 'Повернуто на 90° за годинниковою стрілкою',
'exif-orientation-7' => 'Повернуто на 90° за годинниковою стрілкою й відображено по вертикалі',
'exif-orientation-8' => 'Повернуто на 90° проти годинникової стрілки',

'exif-planarconfiguration-1' => 'формат «chunky»',
'exif-planarconfiguration-2' => 'формат «planar»',

'exif-xyresolution-i' => '$1 точок на дюйм',
'exif-xyresolution-c' => '$1 точок на сантиметр',

'exif-componentsconfiguration-0' => 'не існує',

'exif-exposureprogram-0' => 'Невідомо',
'exif-exposureprogram-1' => 'Ручний режим',
'exif-exposureprogram-2' => 'Програмний режим (нормальний)',
'exif-exposureprogram-3' => 'Пріоритет діафрагми',
'exif-exposureprogram-4' => 'Пріоритет витримки',
'exif-exposureprogram-5' => 'Художня програма (на основі необхідної глибини різкості)',
'exif-exposureprogram-6' => 'Спортивний режим (з мінімальною витримкою)',
'exif-exposureprogram-7' => 'Портретний режим (для знімків на близькій відстані, з фоном не в фокусі)',
'exif-exposureprogram-8' => 'Пейзажний режим (для пейзажних знімків, з фоном в фокусі)',

'exif-subjectdistance-value' => '$1 метрів',

'exif-meteringmode-0'   => 'Невідомо',
'exif-meteringmode-1'   => 'Середній',
'exif-meteringmode-2'   => 'Центрозважений',
'exif-meteringmode-3'   => 'Точковий',
'exif-meteringmode-4'   => 'Багатоточковий',
'exif-meteringmode-5'   => 'Матричний',
'exif-meteringmode-6'   => 'Частковий',
'exif-meteringmode-255' => 'Інший',

'exif-lightsource-0'   => 'Невідомо',
'exif-lightsource-1'   => 'Денне світло',
'exif-lightsource-2'   => 'Лампа денного світла',
'exif-lightsource-3'   => 'Лампа розжарювання',
'exif-lightsource-4'   => 'Спалах',
'exif-lightsource-9'   => 'Хороша погода',
'exif-lightsource-10'  => 'Хмарно',
'exif-lightsource-11'  => 'Тінь',
'exif-lightsource-12'  => 'Лампа денного світла тип D (5700 − 7100K)',
'exif-lightsource-13'  => 'Лампа денного світла тип N (4600 − 5400K)',
'exif-lightsource-14'  => 'Лампа денного світла тип W (3900 − 4500K)',
'exif-lightsource-15'  => 'Лампа денного світла тип WW (3200 − 3700K)',
'exif-lightsource-17'  => 'Стандартне джерело світла типу A',
'exif-lightsource-18'  => 'Стандартне джерело світла типу B',
'exif-lightsource-19'  => 'Стандартне джерело світла типу C',
'exif-lightsource-24'  => 'Студійна лампа стандарту ISO',
'exif-lightsource-255' => 'Інше джерело світла',

# Flash modes
'exif-flash-fired-0'    => 'Спалах не спрацював',
'exif-flash-fired-1'    => 'Спрацював спалах',
'exif-flash-return-0'   => 'нема режиму попереднього спалаху',
'exif-flash-return-2'   => 'зворотний імпульс попереднього спалаху не отриманий',
'exif-flash-return-3'   => 'отриманий зворотний імпульс попереднього спалаху',
'exif-flash-mode-1'     => 'імпульс примусового спалаху',
'exif-flash-mode-2'     => 'придушення примусового спалаху',
'exif-flash-mode-3'     => 'автоматичний режим',
'exif-flash-function-1' => 'Нема спалаху',
'exif-flash-redeye-1'   => 'режим усунення ефекту червоних очей',

'exif-focalplaneresolutionunit-2' => 'дюймів',

'exif-sensingmethod-1' => 'Невизначений',
'exif-sensingmethod-2' => 'Однокристальний матричний сенсор кольорів',
'exif-sensingmethod-3' => 'Сенсор кольорів з двома матрицями',
'exif-sensingmethod-4' => 'Сенсор кольорів с трьома матрицями',
'exif-sensingmethod-5' => 'Матричний сенсор з послідовною зміною кольору',
'exif-sensingmethod-7' => 'Трьохколірний лінійний сенсор',
'exif-sensingmethod-8' => 'Лінійний сенсор з послідовною зміною кольору',

'exif-filesource-3' => 'Цифровий фотоапарат',

'exif-scenetype-1' => 'Зображення сфотографовано напряму',

'exif-customrendered-0' => 'Не виконувалась',
'exif-customrendered-1' => 'Нестандартна обробка',

'exif-exposuremode-0' => 'Автоматична експозиція',
'exif-exposuremode-1' => 'Ручне налаштування експозиції',
'exif-exposuremode-2' => 'Брекетинг',

'exif-whitebalance-0' => 'Автоматичний баланс білого',
'exif-whitebalance-1' => 'Ручне налаштування балансу білого',

'exif-scenecapturetype-0' => 'Стандартний',
'exif-scenecapturetype-1' => 'Ландшафт',
'exif-scenecapturetype-2' => 'Портрет',
'exif-scenecapturetype-3' => 'Нічна зйомка',

'exif-gaincontrol-0' => 'Немає',
'exif-gaincontrol-1' => 'Невелике збільшення',
'exif-gaincontrol-2' => 'Велике збільшення',
'exif-gaincontrol-3' => 'Невелике зменшення',
'exif-gaincontrol-4' => 'Сильне зменшення',

'exif-contrast-0' => 'Нормальна',
'exif-contrast-1' => "М'яке підвищення",
'exif-contrast-2' => 'Сильне підвищення',

'exif-saturation-0' => 'Нормальна',
'exif-saturation-1' => 'Невелика насиченість',
'exif-saturation-2' => 'Велика насиченість',

'exif-sharpness-0' => 'Нормальна',
'exif-sharpness-1' => "М'яке підвищення",
'exif-sharpness-2' => 'Сильне підвищення',

'exif-subjectdistancerange-0' => 'Невідомо',
'exif-subjectdistancerange-1' => 'Макрозйомка',
'exif-subjectdistancerange-2' => 'Зйомка з близької відстані',
'exif-subjectdistancerange-3' => 'Зйомка здалеку',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'північної широти',
'exif-gpslatitude-s' => 'південної широти',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'східної довготи',
'exif-gpslongitude-w' => 'західної довготи',

'exif-gpsstatus-a' => 'Вимірювання не закінчене',
'exif-gpsstatus-v' => 'Готовий до передачі даних',

'exif-gpsmeasuremode-2' => 'Вимірювання 2-х координат',
'exif-gpsmeasuremode-3' => 'Вимірювання 3-х координат',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'км/год',
'exif-gpsspeed-m' => 'миль/год',
'exif-gpsspeed-n' => 'вузлів',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'справжній',
'exif-gpsdirection-m' => 'магнітний',

# External editor support
'edit-externally'      => 'Редагувати цей файл, використовуючи зовнішню програму',
'edit-externally-help' => '(Подробиці див. на сторінці [http://www.mediawiki.org/wiki/Manual:External_editors Інструкції з установки зовнішніх редакторів])',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'всі',
'imagelistall'     => 'всі',
'watchlistall2'    => 'всі',
'namespacesall'    => 'всі',
'monthsall'        => 'всі',
'limitall'         => 'усі',

# E-mail address confirmation
'confirmemail'              => 'Підтвердження адреси ел. пошти',
'confirmemail_noemail'      => 'Ви не зазначили коректну адресу електронної пошти у ваших [[Special:Preferences|налаштуваннях користувача]].',
'confirmemail_text'         => 'Вікі-двигун потребує підтвердження адреси електронної пошти перед початком роботи. Натисніть на кнопку, щоб за вказаною адресою одержати листа, який міститиме посилання на спеціальну сторінку, після відкриття якої у браузері адреса електронної пошти буде вважатися підтвердженою.',
'confirmemail_pending'      => 'Код підтвердження вже відправлено на адресу вашої електронної пошти.
Якщо ви щойно створили обліковий запис, будь-ласка, перш ніж робити запит нового коду, почекайте декілька хвилин до отримання вже відісланого.',
'confirmemail_send'         => 'Надіслати лист із запитом на підтвердження',
'confirmemail_sent'         => 'Лист із запитом на підтвердження відправлений.',
'confirmemail_oncreate'     => "Код підтвердження відправлено на вашу електронну адресу. Цей код не вимагається для входу в систему, але він вам знадобиться для активації будь-яких можливостей вікі, що пов'язані з використанням електронної пошти.",
'confirmemail_sendfailed'   => '{{SITENAME}} не може надіслати листа із запитом на підтвердження.
Будь ласка, перевірте правильність адреси електронної пошти.

Відповідь сервера: $1',
'confirmemail_invalid'      => 'Невірний код підтвердження, або термін дії коду вичерпався.',
'confirmemail_needlogin'    => 'Для підтвердження вашої адреси електронної пошти вам необхідно $1.',
'confirmemail_success'      => 'Вашу адресу електронної пошти підтверджено.',
'confirmemail_loggedin'     => 'Вашу адресу електронної пошти підтверджено.',
'confirmemail_error'        => 'Під час процедури підтвердження адреси електронної пошти сталася помилка.',
'confirmemail_subject'      => '{{SITENAME}}:Запит на підтвердження адреси ел. пошти',
'confirmemail_body'         => 'Хтось з IP-адресою $1 зареєстрував на сервері проекту {{SITENAME}} обліковий запис
«$2», вказавши вашу адресу електронної пошти.

Щоб підтвердити, що ви дозволяєте використовувати вашу адресу електронної пошти в цьому проекті, відкрийте у браузері наведене нижче посилання:

$3

Якщо ви не реєстрували акаунт, то відкрийте наступне посилання,
щоб скасувати підтвердження електронної адреси:

$5

Цей код підтвердження дійсний до $4.',
'confirmemail_body_changed' => 'Хтось (можливо ви) з IP-адреси $1
вказав дану адресу електронної пошти як нову для облікового запису «$2» в проекті {{SITENAME}}.

Аби підтвердити, що цей обліковий запис справді ваш,
й увімкнути відправлення листів з сайту {{SITENAME}}, відкрийте наступне посилання у веб-оглядачі:

$3

Якщо даний обліковий запис *не* належить вам, то перейдіть за наступним посиланням,
щоб відмінити підтвердження адреси:

$5

Код підтвердження дійсний до $4.',
'confirmemail_invalidated'  => 'Підтвердження адреси електронної пошти скасоване',
'invalidateemail'           => 'Скасувати підтвердження адреси електронної пошти',

# Scary transclusion
'scarytranscludedisabled' => '[«Interwiki transcluding» вимкнено]',
'scarytranscludefailed'   => '[Помилка звертання до шаблону $1]',
'scarytranscludetoolong'  => '[URL дуже довгий]',

# Trackbacks
'trackbackbox'      => 'Trackback для цієї статті:<br />
$1',
'trackbackremove'   => '([$1 вилучити])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => 'Trackback вилучено.',

# Delete conflict
'deletedwhileediting' => "'''Увага:''' ця сторінка була вилучена після того, як ви розпочали редагування!",
'confirmrecreate'     => "!Користувач [[User:$1|$1]] ([[User talk:$1|обговорення]]) вилучив цю сторінку після того, як ви почали редагування і зазначив причиною:
: ''$2''
Будь ласка, підтвердьте, що ви дійсно бажаєте створити цю сторінку заново.",
'recreate'            => 'Повторно створити',

'unit-pixel' => ' пікс.',

# action=purge
'confirm_purge_button' => 'Гаразд',
'confirm-purge-top'    => 'Очистити кеш цієї сторінки?',
'confirm-purge-bottom' => 'Після очищення кешу сторінки буде показана її остання версія.',

# Multipage image navigation
'imgmultipageprev' => '← попередня сторінка',
'imgmultipagenext' => 'наступна сторінка →',
'imgmultigo'       => 'Перейти!',
'imgmultigoto'     => 'Перейти на сторінку $1',

# Table pager
'ascending_abbrev'         => 'зрост',
'descending_abbrev'        => 'спад',
'table_pager_next'         => 'Наступна сторінка',
'table_pager_prev'         => 'Попередня сторінка',
'table_pager_first'        => 'Перша сторінка',
'table_pager_last'         => 'Остання сторінка',
'table_pager_limit'        => 'Показувати $1 елементів на сторінці',
'table_pager_limit_label'  => 'Записів на сторінку:',
'table_pager_limit_submit' => 'Виконати',
'table_pager_empty'        => 'Не знайдено',

# Auto-summaries
'autosumm-blank'   => 'Сторінка очищена',
'autosumm-replace' => 'Замінено вміст на «$1»',
'autoredircomment' => 'Перенаправлено на [[$1]]',
'autosumm-new'     => 'Створена сторінка: $1',

# Size units
'size-bytes'     => '$1 байтів',
'size-kilobytes' => '$1 КБ',
'size-megabytes' => '$1 МБ',
'size-gigabytes' => '$1 ГБ',

# Live preview
'livepreview-loading' => 'Завантаження…',
'livepreview-ready'   => 'Завантаження… Готово!',
'livepreview-failed'  => 'Не вдалося використати швидкий попередній перегляд. Спробуйте скористатися звичайним попереднім переглядом.',
'livepreview-error'   => "Не вдалося встановити з'єднання: $1 «$2». Спробуйте скористатися звичайним попереднім переглядом.",

# Friendlier slave lag warnings
'lag-warn-normal' => 'Зміни, зроблені $1 {{PLURAL:$1|секунду|секунди|секунд}} тому, можуть бути не показані в цьому списку.',
'lag-warn-high'   => 'Через велике відставання у синхронізації серверів баз даних зміни, зроблені менш ніж $1 {{PLURAL:$1|секунду|секунди|секунд}} тому, можуть бути не показані в цьому списку.',

# Watchlist editor
'watchlistedit-numitems'       => 'Ваш список спостереження містить {{PLURAL:$1|$1 запис|$1 записи|$1 записів}}, не включаючи сторінок обговорення.',
'watchlistedit-noitems'        => 'Ваш список спостереження порожній.',
'watchlistedit-normal-title'   => 'Редагування списку спостереження',
'watchlistedit-normal-legend'  => 'Вилучення заголовків зі списку спостереження',
'watchlistedit-normal-explain' => 'Далі наведені заголовки з вашого списку спостереження.
Для вилучення сторінки зі списку необхідно поставити галочку в квадратику біля нього і натиснути «{{int:Watchlistedit-normal-submit}}».
Ви можете також [[Special:Watchlist/raw|редагувати список як текстові рядки]].',
'watchlistedit-normal-submit'  => 'Вилучити заголовки',
'watchlistedit-normal-done'    => '{{PLURAL:$1|$1 заголовок був вилучений|$1 заголовки були вилучені|$1 заголовків були вилучені}} з вашого списку спостереження:',
'watchlistedit-raw-title'      => 'Редагування рядків списку спостереження',
'watchlistedit-raw-legend'     => 'Редагування рядків списку спостереження',
'watchlistedit-raw-explain'    => 'Далі наведені сторінки з вашого списку спостереження. Ви можете редагувати список, додаючи і вилучаючи з нього рядки з назвами. Після закінчення редагувань натисніть «{{int:Watchlistedit-raw-submit}}».
Ви також можете використовувати [[Special:Watchlist/edit|звичайний засіб редагування списку]].',
'watchlistedit-raw-titles'     => 'Заголовки:',
'watchlistedit-raw-submit'     => 'Зберегти список',
'watchlistedit-raw-done'       => 'Ваш список спостереження збережений.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|$1 заголовок був доданий|$1 заголовки були додані|$1 заголовків були додані}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|$1 заголовок був вилучений|$1 заголовки були вилучені|$1 заголовків були вилучені}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Зміни на сторінках зі списку',
'watchlisttools-edit' => 'Переглянути/редагувати список',
'watchlisttools-raw'  => 'Редагувати як текст',

# Core parser functions
'unknown_extension_tag' => 'Невідомий тег доповнення «$1»',
'duplicate-defaultsort' => 'Увага. Ключ сортування «$2» перекриває попередній ключ сортування «$1».',

# Special:Version
'version'                          => 'Версія MediaWiki',
'version-extensions'               => 'Установлені розширення',
'version-specialpages'             => 'Спеціальні сторінки',
'version-parserhooks'              => 'Перехоплювачі синтаксичного аналізатора',
'version-variables'                => 'Змінні',
'version-other'                    => 'Інше',
'version-mediahandlers'            => 'Обробники медіа',
'version-hooks'                    => 'Перехоплювачі',
'version-extension-functions'      => 'Функції розширень',
'version-parser-extensiontags'     => 'Теги розширень синтаксичного аналізатора',
'version-parser-function-hooks'    => 'Перехоплювачі функцій синтаксичного аналізатора',
'version-skin-extension-functions' => 'Функції розширень тем оформлення',
'version-hook-name'                => "Ім'я перехоплювача",
'version-hook-subscribedby'        => 'Підписаний на',
'version-version'                  => '(Версія $1)',
'version-license'                  => 'Ліцензія',
'version-software'                 => 'Установлене програмне забезпечення',
'version-software-product'         => 'Продукт',
'version-software-version'         => 'Версія',

# Special:FilePath
'filepath'         => 'Шлях до файлу',
'filepath-page'    => 'Файл:',
'filepath-submit'  => 'Виконати',
'filepath-summary' => 'Ця спеціальна сторінка повертає повний шлях до файлу в тому вигляді, в якому він зберігається на диску.

Уведіть назву файлу без префіксу <code>{{ns:file}}:</code>.',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Пошук файлів-дублікатів',
'fileduplicatesearch-summary'  => 'Пошук дублікатів файлів базується на їх хеш-функції.

Уведіть назву файлу без префіксу «{{ns:file}}:».',
'fileduplicatesearch-legend'   => 'Пошук дублікатів',
'fileduplicatesearch-filename' => 'Назва файлу:',
'fileduplicatesearch-submit'   => 'Знайти',
'fileduplicatesearch-info'     => '$1 × $2 пікселів<br />Розмір файлу: $3<br />MIME-тип: $4',
'fileduplicatesearch-result-1' => 'Файл «$1» не має ідентичних.',
'fileduplicatesearch-result-n' => 'Файл «$1» має {{PLURAL:$2|1 ідентичний дублікат|$2 ідентичних дублікатів}}.',

# Special:SpecialPages
'specialpages'                   => 'Спеціальні сторінки',
'specialpages-note'              => '----
* Звичайні спеціальні сторінки.
* <strong class="mw-specialpagerestricted">Спеціальні сторінки з обмеженим доступом.</strong>',
'specialpages-group-maintenance' => 'Технічні звіти',
'specialpages-group-other'       => 'Інші',
'specialpages-group-login'       => 'Вхід до системи / реєстрація',
'specialpages-group-changes'     => 'Останні зміни і журнали',
'specialpages-group-media'       => 'Файли',
'specialpages-group-users'       => 'Користувачі і права',
'specialpages-group-highuse'     => 'Часто вживані',
'specialpages-group-pages'       => 'Списки сторінок',
'specialpages-group-pagetools'   => 'Інструменти',
'specialpages-group-wiki'        => 'Вікі-дані та інструменти',
'specialpages-group-redirects'   => 'Перенаправлення',
'specialpages-group-spam'        => 'Інструменти проти спаму',

# Special:BlankPage
'blankpage'              => 'Порожня сторінка',
'intentionallyblankpage' => 'Цю сторінку навмисне залишили порожньою',

# External image whitelist
'external_image_whitelist' => '  #Залиште цей рядок таким, яким він є<pre>
#Записуйте тут фрагменти регулярних виразів (ту частину, що знаходиться між //)
#Вони будуть зіставлені з URL зовнішніх зображень.
#Потрібні будуть показані як зображення, решта будуть показані як посилання на зображення
#Рядки, що починаються з #, вважаються коментарями.
#Рядки чутливі до регістра

#Розміщуйте фрагменти регулярних виразів над цією строчкою. Залиште цей рядок таким, яким він є.</pre>',

# Special:Tags
'tags'                    => 'Діючі мітки змін',
'tag-filter'              => 'Фільтр [[Special:Tags|міток]]:',
'tag-filter-submit'       => 'Відфільтрувати',
'tags-title'              => 'Мітки',
'tags-intro'              => 'На цій сторінці наведений список міток, якими програмне забезпечення помічає редагування, а також значення цих міток.',
'tags-tag'                => 'Назва мітки',
'tags-display-header'     => 'Показ у списках змін',
'tags-description-header' => 'Повний опис значення',
'tags-hitcount-header'    => 'Помічені редагування',
'tags-edit'               => 'редагувати',
'tags-hitcount'           => '$1 {{PLURAL:$1|зміна|зміни|змін}}',

# Special:ComparePages
'comparepages'     => 'Порівняння сторінок',
'compare-selector' => 'Порівняння версій сторінок',
'compare-page1'    => 'Сторінка 1',
'compare-page2'    => 'Сторінка 2',
'compare-rev1'     => 'Версія 1',
'compare-rev2'     => 'Версія 2',
'compare-submit'   => 'Порівняти',

# Database error messages
'dberr-header'      => 'Ця вікі має проблеми',
'dberr-problems'    => 'Вибачте! На цьому сайті виникли технічні труднощі.',
'dberr-again'       => 'Спробуйте оновити сторінку за кілька хвилин.',
'dberr-info'        => "(неможливо з'єднатися з сервером баз даних: $1)",
'dberr-usegoogle'   => 'Можете спробувати пошукати за допомогою Google.',
'dberr-outofdate'   => 'Майте на увазі, що його індекси можуть бути застарілими.',
'dberr-cachederror' => 'Нижче наведена закешована версія запитаної сторінки, можливо, вона не показує останні зміни.',

# HTML forms
'htmlform-invalid-input'       => 'Частина введених вами даних викликала проблеми',
'htmlform-select-badoption'    => 'Зазначене вами значення є недопустимим.',
'htmlform-int-invalid'         => 'Зазначене вами значення не є цілим числом.',
'htmlform-float-invalid'       => 'Вказане вами значення не є числом.',
'htmlform-int-toolow'          => 'Вказане вами значення нижче за мінімальне — $1',
'htmlform-int-toohigh'         => 'Зазначене вами значення вище за максимальне — $1',
'htmlform-required'            => "Це значення обов'язкове",
'htmlform-submit'              => 'Відправити',
'htmlform-reset'               => 'Відкотити зміни',
'htmlform-selectorother-other' => 'Інше',

);

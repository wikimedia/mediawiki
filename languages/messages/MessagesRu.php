<?php
/** Russian (Русский)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Ahonc
 * @author Aleksandrit
 * @author Alessandro
 * @author AlexSm
 * @author Alexander Sigachov (alexander.sigachov@gmail.com)
 * @author Amikeco
 * @author Chilin
 * @author Claymore
 * @author Don Alessandro
 * @author Eleferen
 * @author EugeneZelenko
 * @author Ferrer
 * @author Flrn
 * @author G0rn
 * @author Gazeb
 * @author Grigol
 * @author HalanTul
 * @author Huuchin
 * @author Illusion
 * @author Innv
 * @author JenVan
 * @author Jl
 * @author Kaganer
 * @author Kalan
 * @author Kv75
 * @author Lockal
 * @author MaxSem
 * @author Putnik
 * @author Sk
 * @author Temuri rajavi
 * @author VasilievVV
 * @author Ytsukeng Fyvaprol
 * @author Александр Сигачёв
 * @author לערי ריינהארט
 * @author გიორგიმელა
 */

/*
 * Изменения сделанные в этом файле будут потеряны при обновлении MediaWiki.
 *
 * Если необходимо внести изменения в перевод отдельных строк интерфейса,
 * сделайте это посредством редактирования страниц вида «MediaWiki:*».
 * Их список можно найти на странице «Special:Allmessages».
 */

$separatorTransformTable = array(
	',' => "\xc2\xa0", # nbsp
	'.' => ','
);

$fallback8bitEncoding = 'windows-1251';
$linkPrefixExtension = false;

$namespaceNames = array(
	NS_MEDIA            => 'Медиа',
	NS_SPECIAL          => 'Служебная',
	NS_TALK             => 'Обсуждение',
	NS_USER             => 'Участник',
	NS_USER_TALK        => 'Обсуждение_участника',
	NS_PROJECT_TALK     => 'Обсуждение_{{GRAMMAR:genitive|$1}}',
	NS_FILE             => 'Файл',
	NS_FILE_TALK        => 'Обсуждение_файла',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Обсуждение_MediaWiki',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Обсуждение_шаблона',
	NS_HELP             => 'Справка',
	NS_HELP_TALK        => 'Обсуждение_справки',
	NS_CATEGORY         => 'Категория',
	NS_CATEGORY_TALK    => 'Обсуждение_категории',
);

$namespaceAliases = array(
	'Участница'            => NS_USER,
	'Обсуждение участницы' => NS_USER_TALK,
	'Изображение' => NS_FILE,
	'Обсуждение_изображения' => NS_FILE_TALK,
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
	'Поиск по библиотекам «Сигла»' => 'http://www.sigla.ru/results.jsp?f=7&t=3&v0=$1',
	'Findbook.ru' => 'http://findbook.ru/search/d0?ptype=4&pvalue=$1',
	'Яндекс.Маркет' => 'http://market.yandex.ru/search.xml?text=$1',
	'ОЗОН' => 'http://www.ozon.ru/?context=advsearch_book&isbn=$1',
	'Books.Ru' => 'http://www.books.ru/shop/search?query=$1',
	'Amazon.com' => 'http://www.amazon.com/exec/obidos/ISBN=$1'
);

$magicWords = array(
	'redirect'              => array( '0', '#перенаправление', '#перенапр', '#REDIRECT' ),
	'notoc'                 => array( '0', '__БЕЗ_ОГЛАВЛЕНИЯ__', '__БЕЗ_ОГЛ__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__БЕЗ_ГАЛЕРЕИ__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__ОБЯЗАТЕЛЬНОЕ_ОГЛАВЛЕНИЕ__', '__ОБЯЗ_ОГЛ__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__ОГЛАВЛЕНИЕ__', '__ОГЛ__', '__TOC__' ),
	'noeditsection'         => array( '0', '__БЕЗ_РЕДАКТИРОВАНИЯ_РАЗДЕЛА__', '__NOEDITSECTION__' ),
	'noheader'              => array( '0', '__БЕЗ_ЗАГОЛОВКА__', '__NOHEADER__' ),
	'currentmonth'          => array( '1', 'ТЕКУЩИЙ_МЕСЯЦ', 'ТЕКУЩИЙ_МЕСЯЦ_2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'         => array( '1', 'ТЕКУЩИЙ_МЕСЯЦ_1', 'CURRENTMONTH1' ),
	'currentmonthname'      => array( '1', 'НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА_РОД', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'    => array( '1', 'НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА_АБР', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'ТЕКУЩИЙ_ДЕНЬ', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'ТЕКУЩИЙ_ДЕНЬ_2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'НАЗВАНИЕ_ТЕКУЩЕГО_ДНЯ', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'ТЕКУЩИЙ_ГОД', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'ТЕКУЩЕЕ_ВРЕМЯ', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'ТЕКУЩИЙ_ЧАС', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'МЕСТНЫЙ_МЕСЯЦ', 'МЕСТНЫЙ_МЕСЯЦ_2', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'           => array( '1', 'МЕСТНЫЙ_МЕСЯЦ_1', 'LOCALMONTH1' ),
	'localmonthname'        => array( '1', 'НАЗВАНИЕ_МЕСТНОГО_МЕСЯЦА', 'LOCALMONTHNAME' ),
	'localmonthnamegen'     => array( '1', 'НАЗВАНИЕ_МЕСТНОГО_МЕСЯЦА_РОД', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'      => array( '1', 'НАЗВАНИЕ_МЕСТНОГО_МЕСЯЦА_АБР', 'LOCALMONTHABBREV' ),
	'localday'              => array( '1', 'МЕСТНЫЙ_ДЕНЬ', 'LOCALDAY' ),
	'localday2'             => array( '1', 'МЕСТНЫЙ_ДЕНЬ_2', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'НАЗВАНИЕ_МЕСТНОГО_ДНЯ', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'МЕСТНЫЙ_ГОД', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'МЕСТНОЕ_ВРЕМЯ', 'LOCALTIME' ),
	'localhour'             => array( '1', 'МЕСТНЫЙ_ЧАС', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'КОЛИЧЕСТВО_СТРАНИЦ', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'КОЛИЧЕСТВО_СТАТЕЙ', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'КОЛИЧЕСТВО_ФАЙЛОВ', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'КОЛИЧЕСТВО_УЧАСТНИКОВ', 'NUMBEROFUSERS' ),
	'numberofactiveusers'   => array( '1', 'КОЛИЧЕСТВО_АКТИВНЫХ_УЧАСТНИКОВ', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'         => array( '1', 'КОЛИЧЕСТВО_ПРАВОК', 'NUMBEROFEDITS' ),
	'numberofviews'         => array( '1', 'КОЛИЧЕСТВО_ПРОСМОТРОВ', 'NUMBEROFVIEWS' ),
	'pagename'              => array( '1', 'НАЗВАНИЕ_СТРАНИЦЫ', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'НАЗВАНИЕ_СТРАНИЦЫ_2', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'ПРОСТРАНСТВО_ИМЁН', 'NAMESPACE' ),
	'namespacee'            => array( '1', 'ПРОСТРАНСТВО_ИМЁН_2', 'NAMESPACEE' ),
	'talkspace'             => array( '1', 'ПРОСТРАНСТВО_ОБСУЖДЕНИЙ', 'TALKSPACE' ),
	'talkspacee'            => array( '1', 'ПРОСТРАНСТВО_ОБСУЖДЕНИЙ_2', 'TALKSPACEE' ),
	'subjectspace'          => array( '1', 'ПРОСТРАНСТВО_СТАТЕЙ', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'         => array( '1', 'ПРОСТРАНСТВО_СТАТЕЙ_2', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'          => array( '1', 'ПОЛНОЕ_НАЗВАНИЕ_СТРАНИЦЫ', 'FULLPAGENAME' ),
	'fullpagenamee'         => array( '1', 'ПОЛНОЕ_НАЗВАНИЕ_СТРАНИЦЫ_2', 'FULLPAGENAMEE' ),
	'subpagename'           => array( '1', 'НАЗВАНИЕ_ПОДСТРАНИЦЫ', 'SUBPAGENAME' ),
	'subpagenamee'          => array( '1', 'НАЗВАНИЕ_ПОДСТРАНИЦЫ_2', 'SUBPAGENAMEE' ),
	'basepagename'          => array( '1', 'ОСНОВА_НАЗВАНИЯ_СТРАНИЦЫ', 'BASEPAGENAME' ),
	'basepagenamee'         => array( '1', 'ОСНОВА_НАЗВАНИЯ_СТРАНИЦЫ_2', 'BASEPAGENAMEE' ),
	'talkpagename'          => array( '1', 'НАЗВАНИЕ_СТРАНИЦЫ_ОБСУЖДЕНИЯ', 'TALKPAGENAME' ),
	'talkpagenamee'         => array( '1', 'НАЗВАНИЕ_СТРАНИЦЫ_ОБСУЖДЕНИЯ_2', 'TALKPAGENAMEE' ),
	'subjectpagename'       => array( '1', 'НАЗВАНИЕ_СТРАНИЦЫ_СТАТЬИ', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'      => array( '1', 'НАЗВАНИЕ_СТРАНИЦЫ_СТАТЬИ_2', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                   => array( '0', 'СООБЩЕНИЕ:', 'СООБЩ:', 'MSG:' ),
	'subst'                 => array( '0', 'ПОДСТАНОВКА:', 'ПОДСТ:', 'SUBST:' ),
	'msgnw'                 => array( '0', 'СООБЩ_БЕЗ_ВИКИ:', 'MSGNW:' ),
	'img_thumbnail'         => array( '1', 'мини', 'миниатюра', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'мини=$1', 'миниатюра=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'справа', 'right' ),
	'img_left'              => array( '1', 'слева', 'left' ),
	'img_none'              => array( '1', 'без', 'none' ),
	'img_width'             => array( '1', '$1пкс', '$1px' ),
	'img_center'            => array( '1', 'центр', 'center', 'centre' ),
	'img_framed'            => array( '1', 'обрамить', 'framed', 'enframed', 'frame' ),
	'img_frameless'         => array( '1', 'безрамки', 'frameless' ),
	'img_page'              => array( '1', 'страница=$1', 'страница $1', 'page=$1', 'page $1' ),
	'img_upright'           => array( '1', 'сверхусправа', 'сверхусправа=$1', 'сверхусправа $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'            => array( '1', 'граница', 'border' ),
	'img_baseline'          => array( '1', 'основание', 'baseline' ),
	'img_sub'               => array( '1', 'под', 'sub' ),
	'img_super'             => array( '1', 'над', 'super', 'sup' ),
	'img_top'               => array( '1', 'сверху', 'top' ),
	'img_text_top'          => array( '1', 'текст-сверху', 'text-top' ),
	'img_middle'            => array( '1', 'посередине', 'middle' ),
	'img_bottom'            => array( '1', 'снизу', 'bottom' ),
	'img_text_bottom'       => array( '1', 'текст-снизу', 'text-bottom' ),
	'img_link'              => array( '1', 'ссылка=$1', 'link=$1' ),
	'img_alt'               => array( '1', 'альт=$1', 'alt=$1' ),
	'int'                   => array( '0', 'ВНУТР:', 'INT:' ),
	'sitename'              => array( '1', 'НАЗВАНИЕ_САЙТА', 'SITENAME' ),
	'ns'                    => array( '0', 'ПИ:', 'NS:' ),
	'nse'                   => array( '0', 'ПИК:', 'NSE:' ),
	'localurl'              => array( '0', 'ЛОКАЛЬНЫЙ_АДРЕС:', 'LOCALURL:' ),
	'localurle'             => array( '0', 'ЛОКАЛЬНЫЙ_АДРЕС_2:', 'LOCALURLE:' ),
	'server'                => array( '0', 'СЕРВЕР', 'SERVER' ),
	'servername'            => array( '0', 'НАЗВАНИЕ_СЕРВЕРА', 'SERVERNAME' ),
	'scriptpath'            => array( '0', 'ПУТЬ_К_СКРИПТУ', 'SCRIPTPATH' ),
	'grammar'               => array( '0', 'ПАДЕЖ:', 'GRAMMAR:' ),
	'gender'                => array( '0', 'ПОЛ:', 'GENDER:' ),
	'notitleconvert'        => array( '0', '__БЕЗ_ПРЕОБРАЗОВАНИЯ_ЗАГОЛОВКА__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'      => array( '0', '__БЕЗ_ПРЕОБРАЗОВАНИЯ_ТЕКСТА__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'           => array( '1', 'ТЕКУЩАЯ_НЕДЕЛЯ', 'CURRENTWEEK' ),
	'currentdow'            => array( '1', 'ТЕКУЩИЙ_ДЕНЬ_НЕДЕЛИ', 'CURRENTDOW' ),
	'localweek'             => array( '1', 'МЕСТНАЯ_НЕДЕЛЯ', 'LOCALWEEK' ),
	'localdow'              => array( '1', 'МЕСТНЫЙ_ДЕНЬ_НЕДЕЛИ', 'LOCALDOW' ),
	'revisionid'            => array( '1', 'ИД_ВЕРСИИ', 'REVISIONID' ),
	'revisionday'           => array( '1', 'ДЕНЬ_ВЕРСИИ', 'REVISIONDAY' ),
	'revisionday2'          => array( '1', 'ДЕНЬ_ВЕРСИИ_2', 'REVISIONDAY2' ),
	'revisionmonth'         => array( '1', 'МЕСЯЦ_ВЕРСИИ', 'REVISIONMONTH' ),
	'revisionyear'          => array( '1', 'ГОД_ВЕРСИИ', 'REVISIONYEAR' ),
	'revisiontimestamp'     => array( '1', 'ОТМЕТКА_ВРЕМЕНИ_ВЕРСИИ', 'REVISIONTIMESTAMP' ),
	'revisionuser'          => array( '1', 'ВЕРСИЯ_УЧАСНИКА', 'REVISIONUSER' ),
	'plural'                => array( '0', 'МНОЖЕСТВЕННОЕ_ЧИСЛО:', 'PLURAL:' ),
	'fullurl'               => array( '0', 'ПОЛНЫЙ_АДРЕС:', 'FULLURL:' ),
	'fullurle'              => array( '0', 'ПОЛНЫЙ_АДРЕС_2:', 'FULLURLE:' ),
	'lcfirst'               => array( '0', 'ПЕРВАЯ_БУКВА_МАЛЕНЬКАЯ:', 'LCFIRST:' ),
	'ucfirst'               => array( '0', 'ПЕРВАЯ_БУКВА_БОЛЬШАЯ:', 'UCFIRST:' ),
	'lc'                    => array( '0', 'МАЛЕНЬКИМИ_БУКВАМИ:', 'LC:' ),
	'uc'                    => array( '0', 'БОЛЬШИМИ_БУКВАМИ:', 'UC:' ),
	'raw'                   => array( '0', 'НЕОБРАБ:', 'RAW:' ),
	'displaytitle'          => array( '1', 'ПОКАЗАТЬ_ЗАГОЛОВОК', 'DISPLAYTITLE' ),
	'rawsuffix'             => array( '1', 'Н', 'R' ),
	'newsectionlink'        => array( '1', '__ССЫЛКА_НА_НОВЫЙ_РАЗДЕЛ__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'      => array( '1', '__БЕЗ_ССЫЛКИ_НА_НОВЫЙ_РАЗДЕЛ__', '__NONEWSECTIONLINK__' ),
	'currentversion'        => array( '1', 'ТЕКУЩАЯ_ВЕРСИЯ', 'CURRENTVERSION' ),
	'urlencode'             => array( '0', 'ЗАКОДИРОВАННЫЙ_АДРЕС:', 'URLENCODE:' ),
	'anchorencode'          => array( '0', 'КОДИРОВАТЬ_МЕТКУ', 'ANCHORENCODE' ),
	'currenttimestamp'      => array( '1', 'ОТМЕТКА_ТЕКУЩЕГО_ВРЕМЕНИ', 'CURRENTTIMESTAMP' ),
	'localtimestamp'        => array( '1', 'ОТМЕТКА_МЕСТНОГО_ВРЕМЕНИ', 'LOCALTIMESTAMP' ),
	'directionmark'         => array( '1', 'НАПРАВЛЕНИЕ_ПИСЬМА', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'              => array( '0', '#ЯЗЫК:', '#LANGUAGE:' ),
	'contentlanguage'       => array( '1', 'ЯЗЫК_СОДЕРЖАНИЯ', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'      => array( '1', 'СТРАНИЦ_В_ПРОСТРАНСТВЕ_ИМЁН:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'        => array( '1', 'КОЛИЧЕСТВО_АДМИНИСТРАТОРОВ', 'NUMBEROFADMINS' ),
	'formatnum'             => array( '0', 'ФОРМАТИРОВАТЬ_ЧИСЛО', 'FORMATNUM' ),
	'padleft'               => array( '0', 'ЗАПОЛНИТЬ_СЛЕВА', 'PADLEFT' ),
	'padright'              => array( '0', 'ЗАПОЛНИТЬ_СПРАВА', 'PADRIGHT' ),
	'special'               => array( '0', 'служебная', 'special' ),
	'defaultsort'           => array( '1', 'СОРТИРОВКА_ПО_УМОЛЧАНИЮ', 'КЛЮЧ_СОРТИРОВКИ', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'              => array( '0', 'ПУТЬ_К_ФАЙЛУ:', 'FILEPATH:' ),
	'tag'                   => array( '0', 'метка', 'тег', 'тэг', 'tag' ),
	'hiddencat'             => array( '1', '__СКРЫТАЯ_КАТЕГОРИЯ__', '__HIDDENCAT__' ),
	'pagesincategory'       => array( '1', 'СТРАНИЦ_В_КАТЕГОРИИ', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'              => array( '1', 'РАЗМЕР_СТРАНИЦЫ', 'PAGESIZE' ),
	'index'                 => array( '1', '__ИНДЕКС__', '__INDEX__' ),
	'noindex'               => array( '1', '__БЕЗ_ИНДЕКСА__', '__NOINDEX__' ),
	'numberingroup'         => array( '1', 'ЧИСЛО_В_ГРУППЕ', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'        => array( '1', '__СТАТИЧЕСКОЕ_ПЕРЕНАПРАВЛЕНИЕ__', '__STATICREDIRECT__' ),
	'protectionlevel'       => array( '1', 'УРОВЕНЬ_ЗАЩИТЫ', 'PROTECTIONLEVEL' ),
	'formatdate'            => array( '0', 'форматдаты', 'formatdate', 'dateformat' ),
);

$imageFiles = array(
	'button-bold'   => 'cyrl/button_bold.png',
	'button-italic' => 'cyrl/button_italic.png',
	'button-link'   => 'cyrl/button_link.png',
);

$linkTrail = '/^([a-zабвгдеёжзийклмнопрстуфхцчшщъыьэюя]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'               => 'Подчёркивать ссылки:',
'tog-highlightbroken'         => 'Показывать несуществующие ссылки <a href="" class="new">вот так</a> (иначе вот так<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Выравнивать текст по ширине страницы',
'tog-hideminor'               => 'Скрывать малые правки в списке свежих изменений',
'tog-hidepatrolled'           => 'Скрывать отпатрулированные правки в списке свежих правок',
'tog-newpageshidepatrolled'   => 'Скрывать отпатрулированные страницы в списке новых страниц',
'tog-extendwatchlist'         => 'Расширенный список наблюдения, включающий все изменения, а не только последние',
'tog-usenewrc'                => 'Использовать улучшенный список свежих изменений (требуется JavaScript)',
'tog-numberheadings'          => 'Автоматически нумеровать заголовки',
'tog-showtoolbar'             => 'Показывать верхнюю панель инструментов при редактировании (JavaScript)',
'tog-editondblclick'          => 'Править страницы по двойному щелчку (JavaScript)',
'tog-editsection'             => 'Показывать ссылку «править» для каждой секции',
'tog-editsectiononrightclick' => 'Править секции при правом щелчке мышью на заголовке (JavaScript)',
'tog-showtoc'                 => 'Показывать оглавление (для страниц более чем с 3 заголовками)',
'tog-rememberpassword'        => 'Помнить мою учётную запись на этом компьютере (не более $1 {{PLURAL:$1|дня|дней|дней}})',
'tog-watchcreations'          => 'Добавлять созданные мной страницы в список наблюдения',
'tog-watchdefault'            => 'Добавлять изменённые мной страницы в список наблюдения',
'tog-watchmoves'              => 'Добавлять переименованные мной страницы в список наблюдения',
'tog-watchdeletion'           => 'Добавлять удалённые мной страницы в список наблюдения',
'tog-previewontop'            => 'Помещать предпросмотр перед окном редактирования',
'tog-previewonfirst'          => 'Показывать предпросмотр при переходе к редактированию',
'tog-nocache'                 => 'Запретить кеширование страниц',
'tog-enotifwatchlistpages'    => 'Уведомлять по эл. почте об изменениях страниц из списка наблюдения',
'tog-enotifusertalkpages'     => 'Уведомлять по эл. почте об изменении персональной страницы обсуждения',
'tog-enotifminoredits'        => 'Уведомлять по эл. почте даже при малозначительных изменениях',
'tog-enotifrevealaddr'        => 'Показывать мой почтовый адрес в сообщениях оповещения',
'tog-shownumberswatching'     => 'Показывать число участников, включивших страницу в свой список наблюдения',
'tog-oldsig'                  => 'Предпросмотр существующей подписи:',
'tog-fancysig'                => 'Собственная вики-разметка подписи (без автоматической ссылки)',
'tog-externaleditor'          => 'Использовать внешний редактор (необходима специальная настройка компьютера)',
'tog-externaldiff'            => 'Использовать внешнюю программу сравнения версий (необходима специальная настройка компьютера)',
'tog-showjumplinks'           => 'Включить вспомогательные ссылки «перейти к»',
'tog-uselivepreview'          => 'Использовать быстрый предварительный просмотр (JavaScript, экспериментально)',
'tog-forceeditsummary'        => 'Предупреждать, когда не заполнено поле описания правки',
'tog-watchlisthideown'        => 'Скрывать мои правки из списка наблюдения',
'tog-watchlisthidebots'       => 'Скрывать правки ботов из списка наблюдения',
'tog-watchlisthideminor'      => 'Скрывать малые правки из списка наблюдения',
'tog-watchlisthideliu'        => 'Скрывать правки представившихся участников из списка наблюдения',
'tog-watchlisthideanons'      => 'Скрывать правки анонимных участников из списка наблюдения',
'tog-watchlisthidepatrolled'  => 'Скрывать отпатрулированные правки в списке наблюдения',
'tog-nolangconversion'        => 'Отключить преобразование систем письма',
'tog-ccmeonemails'            => 'Отправлять мне копии писем, которые я посылаю другим участникам.',
'tog-diffonly'                => 'Не показывать содержание страницы под сравнением двух версий',
'tog-showhiddencats'          => 'Показывать скрытые категории',
'tog-norollbackdiff'          => 'Не показывать разницу версий после выполнения отката',

'underline-always'  => 'Всегда',
'underline-never'   => 'Никогда',
'underline-default' => 'Использовать настройки браузера',

# Font style option in Special:Preferences
'editfont-style'     => 'Тип шрифта области редактирования:',
'editfont-default'   => 'Шрифт из настроек браузера',
'editfont-monospace' => 'Моноширинный шрифт',
'editfont-sansserif' => 'Шрифт без засечек',
'editfont-serif'     => 'Шрифт с засечками',

# Dates
'sunday'        => 'воскресенье',
'monday'        => 'понедельник',
'tuesday'       => 'вторник',
'wednesday'     => 'среда',
'thursday'      => 'четверг',
'friday'        => 'пятница',
'saturday'      => 'суббота',
'sun'           => 'Вс',
'mon'           => 'Пн',
'tue'           => 'Вт',
'wed'           => 'Ср',
'thu'           => 'Чт',
'fri'           => 'Пт',
'sat'           => 'Сб',
'january'       => 'январь',
'february'      => 'февраль',
'march'         => 'март',
'april'         => 'апрель',
'may_long'      => 'май',
'june'          => 'июнь',
'july'          => 'июль',
'august'        => 'август',
'september'     => 'сентябрь',
'october'       => 'октябрь',
'november'      => 'ноябрь',
'december'      => 'декабрь',
'january-gen'   => 'января',
'february-gen'  => 'февраля',
'march-gen'     => 'марта',
'april-gen'     => 'апреля',
'may-gen'       => 'мая',
'june-gen'      => 'июня',
'july-gen'      => 'июля',
'august-gen'    => 'августа',
'september-gen' => 'сентября',
'october-gen'   => 'октября',
'november-gen'  => 'ноября',
'december-gen'  => 'декабря',
'jan'           => 'янв',
'feb'           => 'фев',
'mar'           => 'мар',
'apr'           => 'апр',
'may'           => 'мая',
'jun'           => 'июн',
'jul'           => 'июл',
'aug'           => 'авг',
'sep'           => 'сен',
'oct'           => 'окт',
'nov'           => 'ноя',
'dec'           => 'дек',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Категория|Категории}}',
'category_header'                => 'Страницы в категории «$1»',
'subcategories'                  => 'Подкатегории',
'category-media-header'          => 'Файлы в категории «$1»',
'category-empty'                 => "''Эта категория в данный момент пуста.''",
'hidden-categories'              => '{{PLURAL:$1|Скрытая категория|Скрытые категории}}',
'hidden-category-category'       => 'Скрытые категории',
'category-subcat-count'          => '{{PLURAL:$2|Данная категория содержит только следующую подкатегорию.|{{PLURAL:$1|Показана $1 подкатегория|Показано $1 подкатегории|Показано $1 подкатегорий}} из $2.}}',
'category-subcat-count-limited'  => 'В этой категории {{PLURAL:$1|$1 подкатегория|$1 подкатегории|$1 подкатегорий}}.',
'category-article-count'         => '{{PLURAL:$2|Эта категория содержит только одну страницу.|{{PLURAL:$1|Показана $1 страница|Показано $1 страницы|Показано $1 страниц}} этой категории из $2.}}',
'category-article-count-limited' => 'В этой категории {{PLURAL:$1|$1 страница|$1 страницы|$1 страниц}}.',
'category-file-count'            => '{{PLURAL:$2|Эта категория содержит только один файл.|{{PLURAL:$1|Показан $1 файл|Показано $1 файла|Показано $1 файлов}} этой категории  из $2.}}',
'category-file-count-limited'    => 'В этой категории {{PLURAL:$1|$1 файл|$1 файла|$1 файлов}}.',
'listingcontinuesabbrev'         => '(продолжение)',
'index-category'                 => 'Индексируемые страницы',
'noindex-category'               => 'Неиндексируемые страницы',

'mainpagetext'      => "'''Вики-движок «MediaWiki» успешно установлен.'''",
'mainpagedocfooter' => 'Информацию по работе с этой вики можно найти в [http://meta.wikimedia.org/wiki/%D0%9F%D0%BE%D0%BC%D0%BE%D1%89%D1%8C:%D0%A1%D0%BE%D0%B4%D0%B5%D1%80%D0%B6%D0%B0%D0%BD%D0%B8%D0%B5 справочном руководстве].

== Некоторые полезные ресурсы ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Список возможных настроек];
* [http://www.mediawiki.org/wiki/Manual:FAQ Часто задаваемые вопросы и ответы по MediaWiki];
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Рассылка уведомлений о выходе новых версий MediaWiki].',

'about'         => 'Описание',
'article'       => 'Статья',
'newwindow'     => '(в новом окне)',
'cancel'        => 'Отменить',
'moredotdotdot' => 'Далее…',
'mypage'        => 'Личная страница',
'mytalk'        => 'Моя страница обсуждения',
'anontalk'      => 'Обсуждение для этого IP-адреса',
'navigation'    => 'Навигация',
'and'           => '&#32;и',

# Cologne Blue skin
'qbfind'         => 'Поиск',
'qbbrowse'       => 'Просмотреть',
'qbedit'         => 'Править',
'qbpageoptions'  => 'Настройки страницы',
'qbpageinfo'     => 'Сведения о странице',
'qbmyoptions'    => 'Ваши настройки',
'qbspecialpages' => 'Специальные страницы',
'faq'            => 'ЧаВО',
'faqpage'        => 'Project:ЧаВО',

# Vector skin
'vector-action-addsection'       => 'Добавить тему',
'vector-action-delete'           => 'Удалить',
'vector-action-move'             => 'Переименовать',
'vector-action-protect'          => 'Защитить',
'vector-action-undelete'         => 'Восстановить',
'vector-action-unprotect'        => 'Снять защиту',
'vector-namespace-category'      => 'Категория',
'vector-namespace-help'          => 'Справочная страница',
'vector-namespace-image'         => 'Файл',
'vector-namespace-main'          => 'Страница',
'vector-namespace-media'         => 'Медиа-страница',
'vector-namespace-mediawiki'     => 'Сообщение',
'vector-namespace-project'       => 'О проекте',
'vector-namespace-special'       => 'Служебная страница',
'vector-namespace-talk'          => 'Обсуждение',
'vector-namespace-template'      => 'Шаблон',
'vector-namespace-user'          => 'Страница участника',
'vector-simplesearch-preference' => 'Включить расширенные поисковые подсказки (только для оформления «Векторное»)',
'vector-view-create'             => 'Создание',
'vector-view-edit'               => 'Правка',
'vector-view-history'            => 'Просмотр истории',
'vector-view-view'               => 'Чтение',
'vector-view-viewsource'         => 'Просмотр разметки',
'actions'                        => 'Действия',
'namespaces'                     => 'Пространства имён',
'variants'                       => 'Варианты',

'errorpagetitle'    => 'Ошибка',
'returnto'          => 'Возврат к странице $1.',
'tagline'           => 'Материал из {{grammar:genitive|{{SITENAME}}}}',
'help'              => 'Справка',
'search'            => 'Поиск',
'searchbutton'      => 'Найти',
'go'                => 'Перейти',
'searcharticle'     => 'Перейти',
'history'           => 'История',
'history_short'     => 'История',
'updatedmarker'     => 'обновлено после моего последнего посещения',
'info_short'        => 'Информация',
'printableversion'  => 'Версия для печати',
'permalink'         => 'Постоянная ссылка',
'print'             => 'Печать',
'edit'              => 'Править',
'create'            => 'Создать',
'editthispage'      => 'Править эту страницу',
'create-this-page'  => 'Создать эту страницу',
'delete'            => 'Удалить',
'deletethispage'    => 'Удалить эту страницу',
'undelete_short'    => 'Восстановить $1 {{PLURAL:$1|правку|правки|правок}}',
'protect'           => 'Защитить',
'protect_change'    => 'изменить',
'protectthispage'   => 'Защитить эту страницу',
'unprotect'         => 'Снять защиту',
'unprotectthispage' => 'Снять защиту',
'newpage'           => 'Новая страница',
'talkpage'          => 'Обсудить эту страницу',
'talkpagelinktext'  => 'Обсуждение',
'specialpage'       => 'Служебная страница',
'personaltools'     => 'Личные инструменты',
'postcomment'       => 'Новый раздел',
'articlepage'       => 'Просмотреть статью',
'talk'              => 'Обсуждение',
'views'             => 'Просмотры',
'toolbox'           => 'Инструменты',
'userpage'          => 'Просмотреть страницу участника',
'projectpage'       => 'Просмотреть страницу проекта',
'imagepage'         => 'Просмотреть страницу файла',
'mediawikipage'     => 'Показать страницу сообщения',
'templatepage'      => 'Просмотреть страницу шаблона',
'viewhelppage'      => 'Получить справку',
'categorypage'      => 'Просмотреть страницу категории',
'viewtalkpage'      => 'Просмотреть обсуждение',
'otherlanguages'    => 'На других языках',
'redirectedfrom'    => '(Перенаправлено с $1)',
'redirectpagesub'   => 'Страница-перенаправление',
'lastmodifiedat'    => 'Последнее изменение этой страницы: $2, $1.',
'viewcount'         => 'К этой странице обращались $1 {{PLURAL:$1|раз|раза|раз}}.',
'protectedpage'     => 'Защищённая страница',
'jumpto'            => 'Перейти к:',
'jumptonavigation'  => 'навигация',
'jumptosearch'      => 'поиск',
'view-pool-error'   => 'Извините, сейчас серверы перегружены.
Поступило слишком много запросов на просмотр этой страницы.
Пожалуйста, подождите и повторите попытку обращения к странице позже.

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Описание {{grammar:genitive|{{SITENAME}}}}',
'aboutpage'            => 'Project:Описание',
'copyright'            => 'Содержимое доступно в соответствии с $1.',
'copyrightpage'        => '{{ns:project}}:Авторское право',
'currentevents'        => 'Текущие события',
'currentevents-url'    => 'Project:Текущие события',
'disclaimers'          => 'Отказ от ответственности',
'disclaimerpage'       => 'Project:Отказ от ответственности',
'edithelp'             => 'Справка по редактированию',
'edithelppage'         => 'Help:Справка по редактированию',
'helppage'             => 'Help:Справка',
'mainpage'             => 'Заглавная страница',
'mainpage-description' => 'Заглавная страница',
'policy-url'           => 'Project:Правила',
'portal'               => 'Сообщество',
'portal-url'           => 'Project:Портал сообщества',
'privacy'              => 'Политика конфиденциальности',
'privacypage'          => 'Project:Политика конфиденциальности',

'badaccess'        => 'Ошибка доступа',
'badaccess-group0' => 'Вы не можете выполнять запрошенное действие.',
'badaccess-groups' => 'Запрошенное действие могут выполнять только участники из {{PLURAL:$2|группы|групп}} $1.',

'versionrequired'     => 'Требуется MediaWiki версии $1',
'versionrequiredtext' => 'Для работы с этой страницей требуется MediaWiki версии $1. См. [[Special:Version|информацию о версиях используемого ПО]].',

'ok'                      => 'OK',
'pagetitle'               => '$1 — {{SITENAME}}',
'retrievedfrom'           => 'Источник — «$1»',
'youhavenewmessages'      => 'Вы получили $1 ($2).',
'newmessageslink'         => 'новые сообщения',
'newmessagesdifflink'     => 'последнее изменение',
'youhavenewmessagesmulti' => 'Вы получили новые сообщения на $1',
'editsection'             => 'править',
'editold'                 => 'править',
'viewsourceold'           => 'просмотреть исходный код',
'editlink'                => 'править',
'viewsourcelink'          => 'просмотреть исходный код',
'editsectionhint'         => 'Править секцию: $1',
'toc'                     => 'Содержание',
'showtoc'                 => 'показать',
'hidetoc'                 => 'убрать',
'thisisdeleted'           => 'Просмотреть или восстановить $1?',
'viewdeleted'             => 'Просмотреть $1?',
'restorelink'             => '{{PLURAL:$1|$1 удалённую правку|$1 удалённые правки|$1 удалённых правок}}',
'feedlinks'               => 'В виде:',
'feed-invalid'            => 'Неправильный тип канала для подписки.',
'feed-unavailable'        => 'Ленты синдикации недоступны',
'site-rss-feed'           => '$1 — RSS-лента',
'site-atom-feed'          => '$1 — Atom-лента',
'page-rss-feed'           => '«$1» — RSS-лента',
'page-atom-feed'          => '«$1» — Atom-лента',
'red-link-title'          => '$1 (такой страницы не существует)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Статья',
'nstab-user'      => 'Участник',
'nstab-media'     => 'Мультимедиа',
'nstab-special'   => 'Служебная страница',
'nstab-project'   => 'О проекте',
'nstab-image'     => 'Файл',
'nstab-mediawiki' => 'Сообщение',
'nstab-template'  => 'Шаблон',
'nstab-help'      => 'Справка',
'nstab-category'  => 'Категория',

# Main script and global functions
'nosuchaction'      => 'Такого действия нет',
'nosuchactiontext'  => 'Указанное в URL действие ошибочно.
Возможно вы допустили опечатку при наборе URL или перешли по ошибочной ссылке.
Это может также указывать на ошибку в проекте {{SITENAME}}.',
'nosuchspecialpage' => 'Нет такой служебной страницы',
'nospecialpagetext' => '<strong>Запрошенной вами служебной страницы не существует.</strong>

Список существующих служебных страниц: [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Ошибка',
'databaseerror'        => 'Ошибка базы данных',
'dberrortext'          => 'Обнаружена ошибка синтаксиса запроса к базе данных.
Это может означать ошибку в программном обеспечении.
Последний запрос к базе данных:
<blockquote><tt>$1</tt></blockquote>
произошёл из функции <tt>«$2»</tt>.
База данных возвратила ошибку <tt>«$3: $4»</tt>.',
'dberrortextcl'        => 'Обнаружена ошибка синтаксиса запроса к базе данных.
Последний запрос к базе данных:
«$1»
произошёл из функции «$2».
База данных возвратила ошибку «$3: $4».',
'laggedslavemode'      => 'Внимание: страница может не содержать последних обновлений.',
'readonly'             => 'Запись в базу данных заблокирована',
'enterlockreason'      => 'Укажите причину и намеченный срок блокировки.',
'readonlytext'         => 'Добавление новых статей и другие изменения базы данных сейчас заблокированы: вероятно, в связи с плановым обслуживанием.
Заблокировавший оператор оставил следующее разъяснение:
$1',
'missing-article'      => 'В базе данных не найдено запрашиваемого текста страницы, которая должна была быть найдена, «$1» $2.

Подобная ситуация обычно возникает при попытке перехода по устаревшей ссылке на историю изменения страницы, которая была удалена.

Если дело не в этом, то скорее всего, вы обнаружили ошибку в программном обеспечении.
Пожалуйста, сообщите об этом [[Special:ListUsers/sysop|администратору]], указав URL.',
'missingarticle-rev'   => '(версия № $1)',
'missingarticle-diff'  => '(разность: $1, $2)',
'readonly_lag'         => 'База данных автоматически заблокирована от изменений на время, пока вторичный сервер БД не синхронизируется с первичным.',
'internalerror'        => 'Внутренняя ошибка',
'internalerror_info'   => 'Внутренняя ошибка: $1',
'fileappenderrorread'  => 'Не удалось прочитать «$1» во время дополнения.',
'fileappenderror'      => 'Не удалось присоединить «$1» к «$2».',
'filecopyerror'        => 'Невозможно скопировать файл «$1» в «$2».',
'filerenameerror'      => 'Невозможно переименовать файл «$1» в «$2».',
'filedeleteerror'      => 'Невозможно удалить файл «$1».',
'directorycreateerror' => 'Невозможно создать директорию «$1».',
'filenotfound'         => 'Невозможно найти файл «$1».',
'fileexistserror'      => 'Невозможно записать в файл «$1»: файл существует.',
'unexpected'           => 'Неподходящее значение: «$1»=«$2».',
'formerror'            => 'Ошибка: невозможно передать данные формы',
'badarticleerror'      => 'Это действие не может быть выполнено на данной странице.',
'cannotdelete'         => 'Невозможно удалить страницу или файл «$1».
Возможно, его уже удалил кто-то другой.',
'badtitle'             => 'Недопустимое название',
'badtitletext'         => 'Запрашиваемое название страницы неправильно, пусто, либо неправильно указано межъязыковое или интервики название. Возможно, в названии используются недопустимые символы.',
'perfcached'           => 'Следующие данные взяты из кеша и могут не учитывать последних изменений.',
'perfcachedts'         => 'Следующие данные взяты из кеша, последний раз он обновлялся в $1.',
'querypage-no-updates' => 'Обновление этой страницы сейчас отключено.
Представленные здесь данные не будут актуализироваться.',
'wrong_wfQuery_params' => 'Недопустимые параметры для функции wfQuery()<br />
Функция: $1<br />
Запрос: $2',
'viewsource'           => 'Просмотр',
'viewsourcefor'        => 'Страница «$1»',
'actionthrottled'      => 'Ограничение по скорости',
'actionthrottledtext'  => 'В качестве меры борьбы со спамом, установлено ограничение на многократное применение данного действия в течение короткого промежутка времени. Пожалуйста, повторите попытку через несколько минут.',
'protectedpagetext'    => 'Эта страница закрыта для редактирования.',
'viewsourcetext'       => 'Вы можете просмотреть и скопировать исходный текст этой страницы:',
'protectedinterface'   => 'Эта страница содержит интерфейсное сообщение программного обеспечения. Во избежание вандализма её изменение запрещено.',
'editinginterface'     => "'''Внимание.''' Вы редактируете страницу, содержащую текст интерфейса программного обеспечения.
Её изменение повлияет на внешний вид интерфейса для других пользователей.
Для переводов лучше использовать [http://translatewiki.net/wiki/Main_Page?setlang=ru translatewiki.net], проект по локализации MediaWiki.",
'sqlhidden'            => '(SQL запрос скрыт)',
'cascadeprotected'     => 'Страница защищена от изменений, поскольку она включена в {{PLURAL:$1|следующую страницу, для которой|следующие страницы, для которых}} включена каскадная защита:
$2',
'namespaceprotected'   => 'У вас нет разрешения редактировать страницы в пространстве имён «$1».',
'customcssjsprotected' => 'У вас нет разрешения редактировать эту страницу, так как она содержит личные настройки другого участника.',
'ns-specialprotected'  => 'Страницы пространства имён «{{ns:special}}» не могут правиться.',
'titleprotected'       => "Создание страницы с таким заголовком было запрещено участником [[Участник:$1|$1]].
Указана следующая причина: ''$2''.",

# Virus scanner
'virus-badscanner'     => "Ошибка настройки. Неизвестный сканер вирусов: ''$1''",
'virus-scanfailed'     => 'ошибка сканирования (код $1)',
'virus-unknownscanner' => 'неизвестный антивирус:',

# Login and logout pages
'logouttext'                 => "'''Вы завершили сеанс работы.'''

Вы можете продолжить участие в {{grammar:genitive|{{SITENAME}}}} анонимно или [[Special:UserLogin|представиться заново]] под тем же или другим именем.
Некоторые страницы могут продолжать отображаться в том виде, как будто вы всё ещё представлены системе. Для борьбы с этим явлением обновите кеш браузера.",
'welcomecreation'            => '== Добро пожаловать, $1! ==
Ваша учётная запись создана.
Не забудьте провести [[Special:Preferences|персональную настройку]] сайта.',
'yourname'                   => 'Имя участника:',
'yourpassword'               => 'Пароль:',
'yourpasswordagain'          => 'Повторный набор пароля:',
'remembermypassword'         => 'Помнить мою учётную запись на этом компьютере (не более $1 {{PLURAL:$1|дня|дней|дней}})',
'yourdomainname'             => 'Ваш домен:',
'externaldberror'            => 'Произошла ошибка при аутентификации с помощью внешней базы данных, или у вас недостаточно прав для внесения изменений в свою внешнюю учётную запись.',
'login'                      => 'Представиться системе',
'nav-login-createaccount'    => 'Представиться / зарегистрироваться',
'loginprompt'                => 'Вы должны разрешить «cookies», чтобы представиться системе.',
'userlogin'                  => 'Представиться или зарегистрироваться',
'userloginnocreate'          => 'Представиться',
'logout'                     => 'Завершение сеанса',
'userlogout'                 => 'Завершение сеанса',
'notloggedin'                => 'Вы не представились системе',
'nologin'                    => "Нет учётной записи? '''$1'''.",
'nologinlink'                => 'Создать учётную запись',
'createaccount'              => 'Зарегистрировать нового участника',
'gotaccount'                 => "Вы уже зарегистрированы? '''$1'''.",
'gotaccountlink'             => 'Представьтесь',
'createaccountmail'          => 'по эл. почте',
'createaccountreason'        => 'Причина:',
'badretype'                  => 'Введённые вами пароли не совпадают.',
'userexists'                 => 'Введённое имя участника уже используется.
Пожалуйста, выберите другое имя.',
'loginerror'                 => 'Ошибка опознавания участника',
'createaccounterror'         => 'Невозможно создать учётную запись: $1',
'nocookiesnew'               => 'Участник зарегистрирован, но не представлен. {{SITENAME}} использует «cookies» для представления участников. У вас «cookies» запрещены. Пожалуйста, разрешите их, а затем представьтесь с вашим новым именем участника и паролем.',
'nocookieslogin'             => '{{SITENAME}} использует «cookies» для представления участников. Вы их отключили. Пожалуйста, включите их и попробуйте снова.',
'noname'                     => 'Вы не указали допустимого имени участника.',
'loginsuccesstitle'          => 'Опознание прошло успешно',
'loginsuccess'               => 'Теперь вы работаете под именем $1.',
'nosuchuser'                 => 'Участника с именем $1 не существует.
Имена участников чувствительны к регистру букв.
Проверьте правильность написания имени или [[Special:UserLogin/signup|создайте новую учётную запись]].',
'nosuchusershort'            => 'Не существует участника с именем <nowiki>$1</nowiki>. Проверьте написание имени.',
'nouserspecified'            => 'Вы должны указать имя участника.',
'login-userblocked'          => 'Этот участник заблокирован. Вход в систему не разрешён.',
'wrongpassword'              => 'Введённый вами пароль неверен. Попробуйте ещё раз.',
'wrongpasswordempty'         => 'Пожалуйста, введите непустой пароль.',
'passwordtooshort'           => 'Пароль должен состоять не менее чем из $1 {{PLURAL:$1|символа|символов|символов}}.',
'password-name-match'        => 'Введённый пароль должен отличаться от имени участника.',
'mailmypassword'             => 'Получить новый пароль',
'passwordremindertitle'      => 'Напоминание пароля участника {{grammar:genitive|{{SITENAME}}}}',
'passwordremindertext'       => 'Кто-то (вероятно, вы, с IP-адреса $1) запросил создать
новый пароль для {{grammar:genitive|{{SITENAME}}}} ($4). Для участника $2
создан временный пароль: $3. Если это был ваш запрос,
вам следует представиться системе и выбрать новый пароль.
Ваш временный пароль будет действовать в течение $5 {{PLURAL:$5|дня|дней|дней}}.

Если вы не посылали запроса на смену пароля, или если вы уже вспомнили свой пароль,
и не желаете его менять, вы можете проигнорировать данное сообщение и
продолжить использовать свой старый пароль.',
'noemail'                    => 'Для участника с именем $1 электронный адрес указан не был.',
'noemailcreate'              => 'Вам необходимо указать корректный адрес электронной почты',
'passwordsent'               => 'Новый пароль был выслан на адрес электронной почты, указанный для участника $1.

Пожалуйста, представьтесь системе заново после получения пароля.',
'blocked-mailpassword'       => 'Редактирование с вашего IP-адреса запрещено, поэтому заблокирована и функция восстановления пароля.',
'eauthentsent'               => 'На указанный адрес электронной почты отправлено письмо с запросом на подтверждение изменения адреса. В письме также описаны действия, которые нужно выполнить для подтверждения того, что этот адрес электронной почты действительно принадлежит вам.',
'throttled-mailpassword'     => 'Функция напоминания пароля уже использовалась в течение {{PLURAL:$1|последнего $1 часа|последних $1 часов|последних $1 часов}} .
Для предотвращения злоупотреблений, разрешено запрашивать не более одного напоминания за $1 {{PLURAL:$1|час|часа|часов}}.',
'mailerror'                  => 'Ошибка при отправке почты: $1',
'acct_creation_throttle_hit' => 'За сутки с вашего IP-адреса {{PLURAL:$1|была создана $1 учётная запись участника|было создано $1 учётных записей участников|было создано $1 учётных записей участников}}, что является пределом для данного отрезка времени.
Таким образом, пользователи, обладающие данным IP-адресом, в данный момент больше не могут создавать новых учётных записей.',
'emailauthenticated'         => 'Ваш почтовый адрес подтверждён $2 в $3.',
'emailnotauthenticated'      => 'Ваш адрес электронной почты ещё не был подтверждён, функции вики-движка по работе с эл. почтой отключены.',
'noemailprefs'               => 'Адрес электронной почты не был указан, функции вики-движка по работе с эл. почтой отключены.',
'emailconfirmlink'           => 'Подтвердить ваш адрес электронной почты',
'invalidemailaddress'        => 'Адрес электронной почты не может быть принят, так как он не соответствует формату.
Пожалуйста, введите корректный адрес или оставьте поле пустым.',
'accountcreated'             => 'Учётная запись создана',
'accountcreatedtext'         => 'Создана учётная запись участника $1.',
'createaccount-title'        => '{{SITENAME}}: создание учётной записи',
'createaccount-text'         => 'Кто-то создал учётную запись «$2» на сервере проекта {{SITENAME}} ($4) с паролем «$3», указав ваш адрес электронной почты. Вам следует зайти и изменить пароль.

Проигнорируйте данное сообщение, если учётная запись была создана по ошибке.',
'usernamehasherror'          => 'Имя участника не может содержать символ «решётки»',
'login-throttled'            => 'Вы сделали слишком много попыток представиться системе.
Пожалуйста, подождите, перед тем, как попробовать снова.',
'loginlanguagelabel'         => 'Язык: $1',
'suspicious-userlogout'      => 'Ваш запрос на завершение сеанса отклонён, так как он похож на запрос, отправленный некорректным браузером или кэширующим прокси.',

# Password reset dialog
'resetpass'                 => 'Изменение пароля',
'resetpass_announce'        => 'Вы представились с помощью временного пароля, полученного по электронной почте. Для завершения входа в систему, вы должны установить новый пароль.',
'resetpass_text'            => '<!-- Добавьте сюда текст -->',
'resetpass_header'          => 'Изменение пароля учётной записи',
'oldpassword'               => 'Старый пароль:',
'newpassword'               => 'Новый пароль:',
'retypenew'                 => 'Повторите ввод нового пароля:',
'resetpass_submit'          => 'Установить пароль и представиться',
'resetpass_success'         => 'Ваш пароль был успешно изменён! Выполняется вход в систему…',
'resetpass_forbidden'       => 'Пароль не может быть изменён',
'resetpass-no-info'         => 'Чтобы обращаться непосредственно к этой странице, вам следует представиться системе.',
'resetpass-submit-loggedin' => 'Изменить пароль',
'resetpass-submit-cancel'   => 'Отмена',
'resetpass-wrong-oldpass'   => 'Неправильный временный или текущий пароль.
Возможно, вы уже успешно изменили пароль, или запросили новый временный пароль.',
'resetpass-temp-password'   => 'Временный пароль:',

# Edit page toolbar
'bold_sample'     => 'Полужирное начертание',
'bold_tip'        => 'Полужирное начертание',
'italic_sample'   => 'Курсивное начертание',
'italic_tip'      => 'Курсивное начертание',
'link_sample'     => 'Заголовок ссылки',
'link_tip'        => 'Внутренняя ссылка',
'extlink_sample'  => 'http://www.example.com заголовок ссылки',
'extlink_tip'     => 'Внешняя ссылка (помните о префиксе http:// )',
'headline_sample' => 'Текст заголовка',
'headline_tip'    => 'Заголовок 2-го уровня',
'math_sample'     => 'Вставьте сюда формулу',
'math_tip'        => 'Математическая формула (формат LaTeX)',
'nowiki_sample'   => 'Вставляйте сюда неотформатированный текст.',
'nowiki_tip'      => 'Игнорировать вики-форматирование',
'image_tip'       => 'Встроенный файл',
'media_tip'       => 'Ссылка на медиа-файл',
'sig_tip'         => 'Ваша подпись и момент времени',
'hr_tip'          => 'Горизонтальная линия (не используйте часто)',

# Edit pages
'summary'                          => 'Описание изменений:',
'subject'                          => 'Тема/заголовок:',
'minoredit'                        => 'Малое изменение',
'watchthis'                        => 'Включить эту страницу в список наблюдения',
'savearticle'                      => 'Записать страницу',
'preview'                          => 'Предпросмотр',
'showpreview'                      => 'Предварительный просмотр',
'showlivepreview'                  => 'Быстрый предпросмотр',
'showdiff'                         => 'Внесённые изменения',
'anoneditwarning'                  => "'''Внимание''': Вы не представились системе. Ваш IP-адрес будет записан в историю изменений этой страницы.",
'anonpreviewwarning'               => "''Вы не представились системе. Сохранение приведёт к записи вашего IP-адреса в историю изменений страницы.''",
'missingsummary'                   => "'''Напоминание.''' Вы не дали краткого описания изменений. При повторном нажатии на кнопку «Записать страницу», ваши изменения будут сохранены без комментария.",
'missingcommenttext'               => 'Пожалуйста, введите ниже ваше сообщение.',
'missingcommentheader'             => "'''Напоминание.''' Вы не указали тему/заголовок для этого комментария.
При повторном нажатии на кнопку «{{int:savearticle}}», ваша правка будет записана без заголовка.",
'summary-preview'                  => 'Описание будет:',
'subject-preview'                  => 'Заголовок будет:',
'blockedtitle'                     => 'Участник заблокирован',
'blockedtext'                      => "'''Ваша учётная запись или IP-адрес заблокированы.'''

Блокировка произведена администратором $1.
Указана следующая причина: ''«$2»''.

* Начало блокировки: $8
* Окончание блокировки: $6
* Был заблокирован: $7

Вы можете отправить письмо участнику $1 или любому другому [[{{MediaWiki:Grouppage-sysop}}|администратору]], чтобы обсудить блокировку.
Обратите внимание, что вы не сможете отправить письмо администратору, если вы не зарегистрированы и не подтвердили свой адрес электронной почты в [[Special:Preferences|личных настройках]], а также если вам было запрещено отправлять письма при блокировке.
Ваш IP-адрес — $3, идентификатор блокировки — #$5.
Пожалуйста, указывайте эти данные в ваших обращениях.",
'autoblockedtext'                  => 'Ваш IP-адрес автоматически заблокирован в связи с тем, что он ранее использовался кем-то из заблокированных участников. Заблокировавший его администратор ($1) указал следующую причину блокировки:

:«$2»

* Начало блокировки: $8
* Окончание блокировки: $6
* Был заблокирован: $7

Вы можете отправить письмо участнику $1 или любому другому [[{{MediaWiki:Grouppage-sysop}}|администратору]], чтобы обсудить блокировку.

Обратите внимание, что вы не сможете отправить письмо администратору, если вы не зарегистрированы в проекте и не подтвердили свой адрес электронной почты в [[Special:Preferences|личных настройках]], а также если вам было запрещено отправлять письма при блокировке.

Ваш IP-адрес — $3, идентификатор блокировки — #$5.
Пожалуйста, указывайте эти данные в ваших обращениях.',
'blockednoreason'                  => 'причина не указана',
'blockedoriginalsource'            => 'Ниже показан текст страницы «$1».',
'blockededitsource'                => "Ниже показан текст '''ваших изменений''' страницы «$1».",
'whitelistedittitle'               => 'Для изменения требуется авторизация',
'whitelistedittext'                => 'Вы должны $1 для изменения страниц.',
'confirmedittext'                  => 'Вы должны подтвердить ваш адрес электронной почты перед правкой страниц.
Пожалуйста, введите и подтвердите ваш адрес электронной почты на [[Special:Preferences|странице настроек]].',
'nosuchsectiontitle'               => 'Невозможно найти раздел',
'nosuchsectiontext'                => 'Вы пытаетесь редактировать несуществующий раздел.
Возможно, он был перемещён или удалён, пока вы просматривали эту страницу.',
'loginreqtitle'                    => 'Требуется авторизация',
'loginreqlink'                     => 'представиться',
'loginreqpagetext'                 => 'Вы должны $1, чтобы просмотреть другие страницы.',
'accmailtitle'                     => 'Пароль выслан.',
'accmailtext'                      => "Пароль для участника [[User talk:$1|$1]], созданный из случайных символов, выслан на адрес $2.

После регистрации в системе вы сможете ''[[Special:ChangePassword|изменить пароль]]''.",
'newarticle'                       => '(Новая)',
'newarticletext'                   => "Вы перешли по ссылке на страницу, которой пока не существует.
Чтобы её создать, наберите текст в окне, расположенном ниже (подробнее см. [[{{MediaWiki:Helppage}}|справочную страницу]]).
Если вы оказались здесь по ошибке, просто нажмите кнопку '''назад''' вашего браузера.",
'anontalkpagetext'                 => "----''Эта страница обсуждения принадлежит анонимному участнику, который ещё не создал учётной записи, или не использует её.
Поэтому для идентификации используется цифровой IP-адрес.
Этот же адрес может соответствовать нескольким другим участникам.
Если вы анонимный участник и полагаете, что получили сообщения, адресованные не вам, пожалуйста, [[Special:UserLogin/signup|создайте учётную запись]] или [[Special:UserLogin|представьтесь системе]], чтобы впредь избежать возможной путаницы с другими анонимными участниками.''",
'noarticletext'                    => "В настоящий момент текст на данной странице отсутствует.
Вы можете [[Special:Search/{{PAGENAME}}|найти упоминание данного названия]] в других статьях,
<span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} найти соответствующие записи журналов],
или '''[{{fullurl:{{FULLPAGENAME}}|action=edit}} создать страницу с таким названием]'''</span>.",
'noarticletext-nopermission'       => 'В настоящее время на этой странице нет текста.
Вы можете [[Special:Search/{{PAGENAME}}|найти упоминание данного названия]] в других статьях,
или <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} найти соответствующие записи журналов].</span>',
'userpage-userdoesnotexist'        => 'Учётной записи «$1» не существует. Убедитесь, что вы действительно желаете создать или изменить эту страницу.',
'userpage-userdoesnotexist-view'   => 'Не зарегистрировано учётной записи участника «$1».',
'blocked-notice-logextract'        => 'Этот участник в данный момент заблокирован.
Ниже приведена последняя запись из журнала блокировок:',
'clearyourcache'                   => "'''Замечание:''' Чтобы после сохранения сделанные изменения вступили в силу, очистите кеш своего браузера: '''Mozilla / Firefox''': ''Ctrl+Shift+R'', '''IE:''' ''Ctrl+F5'', '''Safari''': ''Cmd+Shift+R'', '''Konqueror''': ''F5'', '''Opera''': через меню ''Tools→Preferences''.",
'usercssyoucanpreview'             => "'''Подсказка.''' Нажмите кнопку «{{int:showpreview}}», чтобы проверить ваш новый CSS-файл перед сохранением.",
'userjsyoucanpreview'              => "'''Подсказка.''' Нажмите кнопку «{{int:showpreview}}», чтобы проверить ваш новый JS-файл перед сохранением.",
'usercsspreview'                   => "'''Помните, что это только предварительный просмотр вашего CSS-файла, он ещё не сохранён!'''",
'userjspreview'                    => "'''Помните, что это только предварительный просмотр вашего javascript-файла, он ещё не сохранён!'''",
'userinvalidcssjstitle'            => "'''Внимание:''' тема оформления «$1» не найдена. Помните, что пользовательские страницы .css и .js должны иметь название, состоящее только из строчных букв, например «{{ns:user}}:Некто/monobook.css», а не «{{ns:user}}:Некто/Monobook.css».",
'updated'                          => '(Обновлена)',
'note'                             => "'''Примечание:'''",
'previewnote'                      => "'''Это только предварительный просмотр, текст ещё не записан!'''",
'previewconflict'                  => 'Этот предварительный просмотр отражает текст в верхнем окне редактирования так, как он будет выглядеть, если вы решите записать его.',
'session_fail_preview'             => "'''К сожалению, сервер не смог обработать вашу правку из-за потери идентификатора сессии.
Пожалуйста, попробуйте ещё раз.
Если эта ошибка повторится, попробуйте [[Special:UserLogout|завершить сеанс]] и заново представиться системе.'''",
'session_fail_preview_html'        => "'''К сожалению, сервер не смог обработать вашу правку из-за потери данных сессии.'''

''Так как {{SITENAME}} разрешает использовать чистый HTML, предварительный просмотр отключён в качестве меры предотвращения JavaScript-атак.''

'''Если это добросовестная попытка редактирования, пожалуйста, попробуйте ещё раз.
Если не получается повторная правка, попробуйте [[Special:UserLogout|завершить сеанс]] работы и заново представиться.'''",
'token_suffix_mismatch'            => "'''Ваша правка была отклонена, так как ваша программа неправильно обрабатывает знаки пунктуации
в окне редактирования. Правка была отменена для предотвращения искажения текста статьи.
Подобные проблемы могут возникать при использовании анонимизирующих веб-прокси, содержащих ошибки.'''",
'editing'                          => 'Редактирование: $1',
'editingsection'                   => 'Редактирование $1 (раздел)',
'editingcomment'                   => 'Редактирование $1 (новый раздел)',
'editconflict'                     => 'Конфликт редактирования: $1',
'explainconflict'                  => 'Пока вы редактировали эту страницу, кто-то внёс в неё изменения. В верхнем окне для редактирования вы видите текущий текст страницы.  В нижнем окне находится ваш вариант. Перенесите сделанные вами изменения из нижнего окна в верхнее. При нажатии на кнопку «Записать страницу» будет сохранён текст верхнего окна.',
'yourtext'                         => 'Ваш текст',
'storedversion'                    => 'Сохранённая версия',
'nonunicodebrowser'                => "'''ПРЕДУПРЕЖДЕНИЕ: Ваш браузер не поддерживает кодировку Юникод. При редактировании статей все не-ASCII символы будут заменены на свои шестнадцатеричные коды.'''",
'editingold'                       => "'''Предупреждение. Вы редактируете устаревшую версию данной страницы.'''
После сохранения будут потеряны изменения, сделанные в последующих версиях.",
'yourdiff'                         => 'Различия',
'copyrightwarning'                 => "Обратите внимание, что все добавления и изменения текста статьи рассматриваются, как выпущенные на условиях лицензии $2 (см. $1).
Если вы не хотите, чтобы ваши тексты свободно распространялись и редактировались любым желающим, не помещайте их сюда.<br />
Вы также подтверждаете, что являетесь автором вносимых дополнений, или скопировали их из
источника, допускающего свободное распространение и изменение своего содержимого.<br />
'''НЕ РАЗМЕЩАЙТЕ БЕЗ РАЗРЕШЕНИЯ МАТЕРИАЛЫ, ОХРАНЯЕМЫЕ АВТОРСКИМ ПРАВОМ!'''",
'copyrightwarning2'                => "Пожалуйста, обратите внимание, что все ваши добавления могут быть отредактированы или удалены другими участниками.
Если вы не хотите, чтобы кто-либо изменял ваши тексты, не помещайте их сюда.<br />
Вы также подтверждаете, что являетесь автором вносимых дополнений, или скопировали их из источника, допускающего свободное распространение и изменение своего содержимого (см. $1).
'''НЕ РАЗМЕЩАЙТЕ БЕЗ РАЗРЕШЕНИЯ ОХРАНЯЕМЫЕ АВТОРСКИМ ПРАВОМ МАТЕРИАЛЫ!'''",
'longpagewarning'                  => "'''ПРЕДУПРЕЖДЕНИЕ: Длина этой страницы составляет $1 килобайт.
Страницы, размер которых приближается к 32 КБ или превышает это значение, могут неверно отображаться в некоторых браузерах.
Пожалуйста, рассмотрите вариант разбиения страницы на меньшие части.'''",
'longpageerror'                    => "'''ОШИБКА: записываемый вами текст имеет размер $1 килобайт, что больше, чем установленный предел $2 килобайт. Страница не может быть сохранена.'''",
'readonlywarning'                  => "'''ПРЕДУПРЕЖДЕНИЕ. База данных заблокирована в связи с процедурами обслуживания, поэтому вы не можете записать ваши изменения прямо сейчас.
Возможно, вам следует сохранить текст в файл, чтобы воспользоваться этим текстом позже.'''

Администратор, заблокировавший базу данных, оставил следующее объяснение: $1",
'protectedpagewarning'             => "'''Предупреждение. Эта страница защищена от изменений, её могут редактировать только участники с полномочиями администраторов.'''
Ниже для справки приведена последняя запись журнала:",
'semiprotectedpagewarning'         => "'''Замечание.''' Эта страница была защищена; редактировать её могут только зарегистрированные участники.
Ниже для справки приведена последняя запись журнала:",
'cascadeprotectedwarning'          => "'''Предупреждение:''' Данную страницу могут редактировать только участники группы «Администраторы», поскольку она включена {{PLURAL:$1|в следующую страницу, для которой|в следующие страницы, для которых}} включена каскадная защита:",
'titleprotectedwarning'            => "'''Предупреждение.  Эта страница защищена от изменений, её могут редактировать только участники с [[Special:ListGroupRights|соответствующими полномочиями]].'''
Ниже для справки приведена последняя запись журнала:",
'templatesused'                    => '{{PLURAL:$1|Шаблон, использованный|Шаблоны, использованные}} на текущей версии страницы:',
'templatesusedpreview'             => '{{PLURAL:$1|Шаблон, используемый|Шаблоны, используемые}} в предпросматриваемой странице:',
'templatesusedsection'             => '{{PLURAL:$1|Шаблон, используемый|Шаблоны, использованные}} в этом разделе:',
'template-protected'               => '(защищено)',
'template-semiprotected'           => '(частично защищено)',
'hiddencategories'                 => 'Эта страница относится к $1 {{PLURAL:$1|скрытой категории|скрытым категориям|скрытым категориям}}:',
'edittools'                        => '<!-- Расположенный здесь текст будет показываться под формой редактирования и формой загрузки. -->',
'nocreatetitle'                    => 'Создание страниц ограничено',
'nocreatetext'                     => 'На этом сайте ограничена возможность создания новых страниц.
Вы можете вернуться назад и отредактировать существующую страницу, [[Special:UserLogin|представиться системе или создать новую учётную запись]].',
'nocreate-loggedin'                => 'У вас нет разрешения создавать новые страницы.',
'sectioneditnotsupported-title'    => 'Редактирование разделов не поддерживается',
'sectioneditnotsupported-text'     => 'На этой странице не поддерживается редактирование разделов',
'permissionserrors'                => 'Ошибки прав доступа',
'permissionserrorstext'            => 'У вас нет прав на выполнение этой операции по {{PLURAL:$1|следующей причине|следующим причинам}}:',
'permissionserrorstext-withaction' => "У вас нет разрешения на действие «'''$2'''» по {{PLURAL:$1|следующей причине|следующим причинам}}:",
'recreate-moveddeleted-warn'       => "'''Внимание. Вы пытаетесь воссоздать страницу, которая ранее удалялась.'''

Проверьте, действительно ли вам нужно воссоздавать эту страницу.
Ниже приведены журналы удалений и переименований этой страницы.",
'moveddeleted-notice'              => 'Эта страница была удалена.
Для справки ниже показаны соответствующие записи из журналов удалений и переименований.',
'log-fulllog'                      => 'Просмотреть журнал целиком',
'edit-hook-aborted'                => 'Правка отменена процедурой-перехватчиком.
Дополнительных разъяснений не приведено.',
'edit-gone-missing'                => 'Невозможно обновить страницу.
Вероятно, она была удалена.',
'edit-conflict'                    => 'Конфликт правок.',
'edit-no-change'                   => 'Ваша правка была отклонена, так как в тексте не было сделано изменений.',
'edit-already-exists'              => 'Невозможно создать новую страницу.
Она уже существует.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Внимание. Эта страница содержит слишком много вызовов ресурсоёмких функций.

Ограничение на количество вызовов установлено на уровне $2 {{PLURAL:$2|вызова|вызовов|вызовов}}, в данном случае требуется сделать {{PLURAL:$1|$1 вызов|$1 вызова|$1 вызовов}}.',
'expensive-parserfunction-category'       => 'Страницы со слишком большим количеством вызовов ресурсоёмких функций',
'post-expand-template-inclusion-warning'  => 'Предупреждение: суммарный размер включаемых шаблонов слишком велик.
Некоторые шаблоны не будут включены.',
'post-expand-template-inclusion-category' => 'Страницы, для которых превышен допустимый размер включаемых шаблонов',
'post-expand-template-argument-warning'   => 'Внимание. Эта страница содержит по крайней мере один аргумент шаблона, имеющий слишком большой размер для развёртывания.
Подобные аргументы были опущены.',
'post-expand-template-argument-category'  => 'Страницы, содержащие пропущенные аргументы шаблонов',
'parser-template-loop-warning'            => 'Обнаружена петля в шаблонах: [[$1]]',
'parser-template-recursion-depth-warning' => 'Превышен предел глубины рекурсии шаблона ($1)',
'language-converter-depth-warning'        => 'Превышен предел глубины преобразователя языков ($1)',

# "Undo" feature
'undo-success' => 'Правка может быть отменена. Пожалуйста, просмотрите сравнение версий, чтобы убедиться, что это именно те изменения, которые вас интересуют, и нажмите «Записать страницу», чтобы изменения вступили в силу.',
'undo-failure' => 'Правка не может быть отменена из-за несовместимости промежуточных изменений.',
'undo-norev'   => 'Правка не может быть отменена, так как её не существует или она была удалена.',
'undo-summary' => 'Отмена правки $1 участника [[Special:Contributions/$2|$2]] ([[User talk:$2|обсуждение]])',

# Account creation failure
'cantcreateaccounttitle' => 'Невозможно создать учётную запись',
'cantcreateaccount-text' => "Создание учётных записей с этого IP-адреса (<b>$1</b>) было заблокировано [[User:$3|участником $3]].

$3 указал следующую причину: ''$2''",

# History pages
'viewpagelogs'           => 'Показать журналы для этой страницы',
'nohistory'              => 'Для этой страницы история изменений отсутствует.',
'currentrev'             => 'Текущая версия',
'currentrev-asof'        => 'Текущая версия на $1',
'revisionasof'           => 'Версия $1',
'revision-info'          => 'Версия от $1; $2',
'previousrevision'       => '← Предыдущая',
'nextrevision'           => 'Следующая →',
'currentrevisionlink'    => 'Текущая версия',
'cur'                    => 'текущ.',
'next'                   => 'след.',
'last'                   => 'пред.',
'page_first'             => 'первая',
'page_last'              => 'последняя',
'histlegend'             => "Пояснения: (текущ.) — отличие от текущей версии; (пред.) — отличие от предшествующей версии; '''м''' — малозначимое изменение",
'history-fieldset-title' => 'Просмотреть историю',
'history-show-deleted'   => 'Только удалённые',
'histfirst'              => 'старейшие',
'histlast'               => 'недавние',
'historysize'            => '($1 {{PLURAL:$1|байт|байта|байт}})',
'historyempty'           => '(пусто)',

# Revision feed
'history-feed-title'          => 'История изменений',
'history-feed-description'    => 'История изменений этой страницы в вики',
'history-feed-item-nocomment' => '$1 в $2',
'history-feed-empty'          => 'Запрашиваемой страницы не существует.
Она могла быть удалена или переименована.
Попробуйте [[Special:Search|найти в вики]] похожие страницы.',

# Revision deletion
'rev-deleted-comment'         => '(комментарий удалён)',
'rev-deleted-user'            => '(имя автора стёрто)',
'rev-deleted-event'           => '(запись удалена)',
'rev-deleted-user-contribs'   => '[имя участника или IP-адрес удалёны — правка скрыта со страницы вклада]',
'rev-deleted-text-permission' => "Эта версия страницы была '''удалена'''.
Возможно, объяснения даны в [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} журнале удалений].",
'rev-deleted-text-unhide'     => "Эта версия страницы была '''удалена'''.
Возможно, объяснения даны в [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} журнале удалений].
Вы можете [$1 просмотреть данную версию], так как являетесь администратором.",
'rev-suppressed-text-unhide'  => "Эта версия страницы была '''скрыта'''.
Возможно, объяснения даны в [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} журнале сокрытий].
Вы можете [$1 просмотреть данную версию], так как являетесь администратором.",
'rev-deleted-text-view'       => "Эта версия страницы была '''удалена'''.
Вы можете просмотреть её, так как являетесь администратором. Возможно, объяснения удаления даны в [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} журнале удалений].",
'rev-suppressed-text-view'    => "Эта версия страницы была '''скрыта'''.
Вы можете просмотреть её, так как являетесь администратором. Возможно, объяснения удаления даны в [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} журнале сокрытий].",
'rev-deleted-no-diff'         => "Вы не можете просмотреть эту разницу версий, так как одна из версий страницы была '''удалена'''.
Возможно, подробности можно найти в [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} журнале удалений].",
'rev-suppressed-no-diff'      => "Вы не можете просмотреть различие между этими версиями страницы, так как одна из них была '''удалена'''.",
'rev-deleted-unhide-diff'     => "Одна из версий страницы была '''удалена'''.
Возможно, подробности можно найти в [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} журнале удалений].
Вы можете [$1 просмотреть данную версию], так как являетесь администратором.",
'rev-suppressed-unhide-diff'  => "Одна из версий этого сравнения версий была '''скрыта'''.
Подробности могут быть приведены в [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} журнале сокрытий].
Как администратор, вы всё-таки можете просмотреть [$1 эту разницу версий], если вы хотите продолжить.",
'rev-deleted-diff-view'       => "Одна из версий этого сравнения версий была '''удалена'''.
Как администратор, вы можете просмотреть это сравнение, подробности могут быть указаны в [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} журнале удаления].",
'rev-suppressed-diff-view'    => "Одна из версий этого сравнения версий была '''скрыта'''.
Как администратор, вы можете просмотреть это сравнение, подробности могут быть указаны в [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} журнале сокрытий].",
'rev-delundel'                => 'показать/скрыть',
'rev-showdeleted'             => 'показать',
'revisiondelete'              => 'Удалить / восстановить версии страницы',
'revdelete-nooldid-title'     => 'Не задана целевая версия',
'revdelete-nooldid-text'      => 'Вы не задали целевую версию (или версии) для выполнения этой функции.',
'revdelete-nologtype-title'   => 'Не указан тип журнала',
'revdelete-nologtype-text'    => 'Вы не указали тип журнала, в котором следует выполнить действие.',
'revdelete-nologid-title'     => 'Ошибочная запись журнала',
'revdelete-nologid-text'      => 'Вы не указали целевую запись журнала для выполнения действия или указанной записи не существует.',
'revdelete-no-file'           => 'Указанный файл не существует.',
'revdelete-show-file-confirm' => 'Вы уверены, что вы хотите просмотреть удалённую версию файла «<nowiki>$1</nowiki>» от $2, $3 ?',
'revdelete-show-file-submit'  => 'Да',
'revdelete-selected'          => "'''{{PLURAL:$2|Выбранная версия|Выбранные версии}} страницы [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Выбранная запись|Выбранные записи}} журнала:'''",
'revdelete-text'              => "'''Удалённые версии страниц и событий будут показываться в истории страницы и журналах, но часть их содержания будет недоступна обычным посетителям.'''
Администраторы проекта {{SITENAME}} будут иметь доступ к скрытому содержанию и смогут восстановить его через этот же интерфейс, за исключением случаев, когда было установлено дополнительное ограничение.",
'revdelete-confirm'           => 'Пожалуйста, подтвердите, что вы действительно желаете совершить это действие, осознаёте последствия, делаете это в соответствии с [[{{MediaWiki:Policy-url}}|правилами]].',
'revdelete-suppress-text'     => "Сокрытие может производиться '''только''' в следующих случаях:

* Неуместная личная информация
*: ''домашний адрес, номера телефонов, номер паспорта и т. д.''",
'revdelete-legend'            => 'Установить ограничения:',
'revdelete-hide-text'         => 'Скрыть текст этой версии страницы',
'revdelete-hide-image'        => 'Скрыть содержимое файла',
'revdelete-hide-name'         => 'Скрыть действие и его объект',
'revdelete-hide-comment'      => 'Скрыть описание изменений',
'revdelete-hide-user'         => 'Скрыть имя автора',
'revdelete-hide-restricted'   => 'Скрыть данные также и от администраторов',
'revdelete-radio-same'        => '(не изменять)',
'revdelete-radio-set'         => 'Да',
'revdelete-radio-unset'       => 'Нет',
'revdelete-suppress'          => 'Скрывать данные также и от администраторов',
'revdelete-unsuppress'        => 'Снять ограничения с восстановленных версий',
'revdelete-log'               => 'Причина:',
'revdelete-submit'            => 'Применить к {{PLURAL:$1|выбранной версии|выбранным версиям}}',
'revdelete-logentry'          => 'изменил видимость версии страницы [[$1]]',
'logdelete-logentry'          => 'Изменена видимость события для [[$1]]',
'revdelete-success'           => "'''Видимость версии успешно изменена.'''",
'revdelete-failure'           => "'''Видимость версии не может быть изменена:'''
$1",
'logdelete-success'           => 'Видимость события изменена.',
'logdelete-failure'           => "'''Видимость журнала не установлена:'''
$1",
'revdel-restore'              => 'изменить видимость',
'revdel-restore-deleted'      => 'удалённые версии',
'revdel-restore-visible'      => 'видимые версии',
'pagehist'                    => 'История страницы',
'deletedhist'                 => 'История удалений',
'revdelete-content'           => 'содержимое',
'revdelete-summary'           => 'описание изменений',
'revdelete-uname'             => 'имя участника',
'revdelete-restricted'        => 'ограничения применяются к администраторам',
'revdelete-unrestricted'      => 'ограничения сняты для администраторов',
'revdelete-hid'               => 'скрыто $1',
'revdelete-unhid'             => 'раскрыто $1',
'revdelete-log-message'       => '$1 для $2 {{PLURAL:$2|версии|версий|версий}}',
'logdelete-log-message'       => '$1 для $2 {{PLURAL:$2|события|событий|событий}}',
'revdelete-hide-current'      => 'Ошибка скрытия записи от $2, $1: это текущая версия.
Она не может быть скрыта.',
'revdelete-show-no-access'    => 'Ошибка раскрытия записи от $2, $1: эта запись отмечена как «ограниченная».
У вас нет доступа к ней.',
'revdelete-modify-no-access'  => 'Ошибка изменения записи от $2, $1: эта запись отмечена как «ограниченная».
У вас нет доступа к ней.',
'revdelete-modify-missing'    => 'Ошибка изменения записи ID $1, она отсутствует в базе данных!',
'revdelete-no-change'         => "'''Внимание:''' запись от $2, $1 уже имеет запрашиваемые настройки видимости.",
'revdelete-concurrent-change' => 'Ошибка изменения записи от $2, $1: её статус был изменён кем-то другим, пока вы пытались изменить его.
Пожалуйста, проверьте журналы.',
'revdelete-only-restricted'   => 'Ошибка сокрытия записи от $2 $1: вы не можете скрыть запись от просмотра администраторами без выбора одной из других настроек сокрытия.',
'revdelete-reason-dropdown'   => '* Стандартные причины удаления
** Нарушение авторских прав
** Неуместные личные сведения',
'revdelete-otherreason'       => 'Другая/дополнительная причина:',
'revdelete-reasonotherlist'   => 'Другая причина',
'revdelete-edit-reasonlist'   => 'Править список причин',
'revdelete-offender'          => 'Автор версии страницы:',

# Suppression log
'suppressionlog'     => 'Журнал сокрытий',
'suppressionlogtext' => 'Ниже представлен список недавних удалений и блокировок, включающих скрытые от администраторов материалы.
См. [[Special:IPBlockList|список IP-блокировок]], чтобы просмотреть список текущих блокировок.',

# Revision move
'moverevlogentry'              => 'перенёс {{PLURAL:$3|$3 версию|$3 версии|$3 версий}} из $1 в $2',
'revisionmove'                 => 'Перенос версий из «$1»',
'revmove-explain'              => 'Следующие версии страниц будут перенесены со страницы $1 на указанную целевую страницу. Если целевой страницы не существует, то она будет создана. Иначе, эти версии будут добавлены в историю изменений страницы.',
'revmove-legend'               => 'Установка целевой страницы и описания',
'revmove-submit'               => 'Перенести версии на выбранную страницу',
'revisionmoveselectedversions' => 'Перенести выбранные версии',
'revmove-reasonfield'          => 'Причина:',
'revmove-titlefield'           => 'Целевая страница:',
'revmove-badparam-title'       => 'Плохие параметры',
'revmove-badparam'             => 'Ваш запрос содержит недопустимые значения параметров, или значения параметров не заданы. Пожалуйста, нажмите кнопку «Назад» и попробуйте ещё раз.',
'revmove-norevisions-title'    => 'Недопустимая целевая версия',
'revmove-norevisions'          => 'Для выполнения этой функции вы не указали одну или несколько целевых версий, или указанные версии не существуют.',
'revmove-nullmove-title'       => 'Плохое название',
'revmove-nullmove'             => 'Исходная и целевая страницы совпадают. Пожалуйста, нажмите кнопку «Назад» и введите название страницы, отличное от «$1».',
'revmove-success-existing'     => '{{PLURAL:$1|$1 версия страницы [[$2]] была перенесена|$1 версии страницы [[$2]] были перенесены|$1 версий страниц [[$2]] были перенесены}} на существующую страницу [[$3]].',
'revmove-success-created'      => '{{PLURAL:$1|$1 версия страницы [[$2]] была перенесена|$1 версии страницы [[$2]] были перенесены|$1 версий страниц [[$2]] были перенесены}} на новую страницу [[$3]].',

# History merging
'mergehistory'                     => 'Объединение историй правок',
'mergehistory-header'              => 'Эта страница позволяет вам объединить историю правок двух различных страниц.
Убедитесь, что это изменение сохранит целостность истории страницы.',
'mergehistory-box'                 => 'Объединить истории правок двух страниц:',
'mergehistory-from'                => 'Исходная страница:',
'mergehistory-into'                => 'Целевая страница:',
'mergehistory-list'                => 'Объединяемая история правок',
'mergehistory-merge'               => 'Следующие версии [[:$1]] могут быть объединены в [[:$2]]. Используйте переключатели для того, чтобы объединить только выбранный диапазон правок. Учтите, что при использовании навигационных ссылок данные будут потерянны.',
'mergehistory-go'                  => 'Показать объединяемые правки',
'mergehistory-submit'              => 'Объединить правки',
'mergehistory-empty'               => 'Не найдены правки для объединения.',
'mergehistory-success'             => '$3 {{PLURAL:$3|правка|правки|правок}} из [[:$1]] успешно {{PLURAL:$3|перенесена|перенесены|перенесены}} в [[:$2]].',
'mergehistory-fail'                => 'Не удалось произвести объединение историй страниц, пожалуйста проверьте параметры страницы и времени.',
'mergehistory-no-source'           => 'Исходная страница «$1» не существует.',
'mergehistory-no-destination'      => 'Целевая страница «$1» не существует.',
'mergehistory-invalid-source'      => 'Источник должен иметь правильный заголовок.',
'mergehistory-invalid-destination' => 'Целевая страница должна иметь правильный заголовок.',
'mergehistory-autocomment'         => 'Перенос [[:$1]] в [[:$2]]',
'mergehistory-comment'             => 'Перенос [[:$1]] в [[:$2]]: $3',
'mergehistory-same-destination'    => 'Исходная и целевая страницы должны различаться',
'mergehistory-reason'              => 'Причина:',

# Merge log
'mergelog'           => 'Журнал объединений',
'pagemerge-logentry' => 'объединена [[$1]] и [[$2]] (версии вплоть до $3)',
'revertmerge'        => 'Разделить',
'mergelogpagetext'   => 'Ниже приведён список последних объединений историй страниц.',

# Diffs
'history-title'            => '$1 — история изменений',
'difference'               => '(Различия между версиями)',
'lineno'                   => 'Строка $1:',
'compareselectedversions'  => 'Сравнить выбранные версии',
'showhideselectedversions' => 'Показать/скрыть выбранные версии',
'editundo'                 => 'отменить',
'diff-multi'               => '({{PLURAL:$1|$1 промежуточная версия не показана|$1 промежуточные версии не показаны|$1 промежуточных версий не показаны.}})',

# Search results
'searchresults'                    => 'Результаты поиска',
'searchresults-title'              => 'Поиск «$1»',
'searchresulttext'                 => 'Для получения более подробной информации о поиске на страницах проекта, см. [[{{MediaWiki:Helppage}}|справочный раздел]].',
'searchsubtitle'                   => 'По запросу «[[:$1]]» ([[Special:Prefixindex/$1|страницы, начинающиеся с этого названия]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|ссылающиеся на это название]])',
'searchsubtitleinvalid'            => 'По запросу «$1»',
'toomanymatches'                   => 'Найдено слишком много соответствий, пожалуйста, попробуйте другой запрос',
'titlematches'                     => 'Совпадения в названиях страниц',
'notitlematches'                   => 'Нет совпадений в названиях страниц',
'textmatches'                      => 'Совпадения в текстах страниц',
'notextmatches'                    => 'Нет совпадений в текстах страниц',
'prevn'                            => '{{PLURAL:$1|предыдущая $1|предыдущие $1|предыдущие $1}}',
'nextn'                            => '{{PLURAL:$1|следующая $1|следующие $1|следующие $1}}',
'prevn-title'                      => '{{PLURAL:$1|Предыдущая $1 запись|Предыдущие $1 записи|Предыдущие $1 записей}}',
'nextn-title'                      => '{{PLURAL:$1|Следующая $1 запись|Следующие $1 записи|Следующие $1 записей}}',
'shown-title'                      => 'Показывать $1 {{PLURAL:$1|запись|записи|записей}} на странице',
'viewprevnext'                     => 'Просмотреть ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Настройки поиска',
'searchmenu-exists'                => "'''В этом вики-проекте есть страница «[[:$1]]»'''",
'searchmenu-new'                   => "'''Создать страницу «[[:$1]]» в этом вики-проекте!'''",
'searchhelp-url'                   => 'Help:Содержание',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Показать страницы с этим префиксом]]',
'searchprofile-articles'           => 'Основные страницы',
'searchprofile-project'            => 'Страницы справки и проектов',
'searchprofile-images'             => 'Мультимедиа',
'searchprofile-everything'         => 'Везде',
'searchprofile-advanced'           => 'Расширенный',
'searchprofile-articles-tooltip'   => 'Поиск в $1',
'searchprofile-project-tooltip'    => 'Поиск в $1',
'searchprofile-images-tooltip'     => 'Поиск файлов',
'searchprofile-everything-tooltip' => 'Поиск на всех страницах (включая страницы обсуждения)',
'searchprofile-advanced-tooltip'   => 'Искать в заданных пространствах имён',
'search-result-size'               => '$1 ({{PLURAL:$2|$2 слово|$2 слова|$2 слов}})',
'search-result-category-size'      => '{{PLURAL:$1|$1 элемент|$1 элемента|$1 элементов}} ({{PLURAL:$2|$2 подкатегория|$2 подкатегории|$2 подкатегорий}}, {{PLURAL:$3|$3 файл|$3 файла|$3 файлов}})',
'search-result-score'              => 'Релевантность: $1 %',
'search-redirect'                  => '(перенаправление $1)',
'search-section'                   => '(раздел $1)',
'search-suggest'                   => 'Возможно, вы имели в виду: $1',
'search-interwiki-caption'         => 'Родственные проекты',
'search-interwiki-default'         => '$1 результ.:',
'search-interwiki-more'            => '(ещё)',
'search-mwsuggest-enabled'         => 'с советами',
'search-mwsuggest-disabled'        => 'без советов',
'search-relatedarticle'            => 'Связанный',
'mwsuggest-disable'                => 'Отключить AJAX-подсказки',
'searcheverything-enable'          => 'Поиск по всем пространствам имён',
'searchrelated'                    => 'связанный',
'searchall'                        => 'все',
'showingresults'                   => 'Ниже {{PLURAL:$1|показан|показаны|показаны}} <strong>$1</strong> {{PLURAL:$1|результат|результата|результатов}}, начиная с №&nbsp;<strong>$2</strong>.',
'showingresultsnum'                => 'Ниже {{PLURAL:$3|показан|показаны|показаны}} <strong>$3</strong> {{PLURAL:$3|результат|результата|результатов}}, начиная с №&nbsp;<strong>$2</strong>.',
'showingresultsheader'             => "{{PLURAL:$5|Результат '''$1''' из '''$3'''|Результаты '''$1 — $2''' из '''$3'''}} для '''$4'''",
'nonefound'                        => "'''Замечание.''' По умолчанию поиск производится не во всех пространствах имён. Используйте приставку ''all:'', чтобы искать во всех пространствах имён (включая обсуждения участников, шаблоны и пр.), или укажите требуемое пространство имён.",
'search-nonefound'                 => 'Соответствий запросу не найдено.',
'powersearch'                      => 'Расширенный поиск',
'powersearch-legend'               => 'Расширенный поиск',
'powersearch-ns'                   => 'Поиск в пространствах имён:',
'powersearch-redir'                => 'Выводить перенаправления',
'powersearch-field'                => 'Поиск',
'powersearch-togglelabel'          => 'Отметить:',
'powersearch-toggleall'            => 'Все',
'powersearch-togglenone'           => 'Ничего',
'search-external'                  => 'Внешний поиск',
'searchdisabled'                   => 'Извините, но встроенный полнотекстовый поиск выключен. Вы можете воспользоваться поиском по сайту через поисковые системы общего назначения, однако имейте в виду, что копия сайта в их кеше может быть несколько устаревшей.',

# Quickbar
'qbsettings'               => 'Панель навигации',
'qbsettings-none'          => 'Не показывать',
'qbsettings-fixedleft'     => 'Неподвижная слева',
'qbsettings-fixedright'    => 'Неподвижная справа',
'qbsettings-floatingleft'  => 'Плавающая слева',
'qbsettings-floatingright' => 'Плавающая справа',

# Preferences page
'preferences'                   => 'Настройки',
'mypreferences'                 => 'Настройки',
'prefs-edits'                   => 'Количество правок:',
'prefsnologin'                  => 'Вы не представились системе',
'prefsnologintext'              => 'Вы должны <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} представиться системе]</span>, чтобы изменять настройки участника.',
'changepassword'                => 'Изменение пароля',
'prefs-skin'                    => 'Тема оформления',
'skin-preview'                  => 'Предпросмотр',
'prefs-math'                    => 'Отображение формул',
'datedefault'                   => 'По умолчанию',
'prefs-datetime'                => 'Дата и время',
'prefs-personal'                => 'Личные данные',
'prefs-rc'                      => 'Свежие правки',
'prefs-watchlist'               => 'Список наблюдения',
'prefs-watchlist-days'          => 'Количество дней:',
'prefs-watchlist-days-max'      => '(не более 7 дней)',
'prefs-watchlist-edits'         => 'Максимальное количество правок, отображаемых в расширенном списке наблюдения:',
'prefs-watchlist-edits-max'     => '(не более 1000)',
'prefs-watchlist-token'         => 'Токен списка наблюдения:',
'prefs-misc'                    => 'Другие настройки',
'prefs-resetpass'               => 'Изменить пароль',
'prefs-email'                   => 'Параметры электронной почты',
'prefs-rendering'               => 'Внешний вид',
'saveprefs'                     => 'Сохранить',
'resetprefs'                    => 'Сбросить',
'restoreprefs'                  => 'Восстановить настройки по умолчанию',
'prefs-editing'                 => 'Редактирование',
'prefs-edit-boxsize'            => 'Размер окна редактирования.',
'rows'                          => 'Строк:',
'columns'                       => 'Столбцов:',
'searchresultshead'             => 'Поиск',
'resultsperpage'                => 'Количество найденных записей на страницу:',
'contextlines'                  => 'Количество показываемых строк для каждой найденной:',
'contextchars'                  => 'Количество символов контекста на строку:',
'stub-threshold'                => 'Порог для определения оформления <a href="#" class="stub">ссылок на заготовки</a> (в байтах):',
'recentchangesdays'             => 'Количество дней, за которые показывать свежие правки:',
'recentchangesdays-max'         => '(не более $1 {{PLURAL:$1|дня|дней|дней}})',
'recentchangescount'            => 'Количество правок, отображаемое по умолчанию:',
'prefs-help-recentchangescount' => 'Включает свежие правки, истории страниц, журналы.',
'prefs-help-watchlist-token'    => 'Заполнение этого поля секретным ключом будет создавать RSS-трансляцию для вашего списка наблюдений.
Каждый, кто знает ключ в этом поле, сможет читать ваш список наблюдения, поэтому выбирайте секретное значение.
Вы можете использовать сгенерированное случайным образом значение: $1',
'savedprefs'                    => 'Ваши настройки сохранены.',
'timezonelegend'                => 'Часовой пояс:',
'localtime'                     => 'Местное время:',
'timezoneuseserverdefault'      => 'Использовать настройки сервера',
'timezoneuseoffset'             => 'Иное (укажите смещение)',
'timezoneoffset'                => 'Смещение¹:',
'servertime'                    => 'Время сервера:',
'guesstimezone'                 => 'Заполнить из браузера',
'timezoneregion-africa'         => 'Африка',
'timezoneregion-america'        => 'Америка',
'timezoneregion-antarctica'     => 'Антарктика',
'timezoneregion-arctic'         => 'Арктика',
'timezoneregion-asia'           => 'Азия',
'timezoneregion-atlantic'       => 'Атлантический океан',
'timezoneregion-australia'      => 'Австралия',
'timezoneregion-europe'         => 'Европа',
'timezoneregion-indian'         => 'Индийский океан',
'timezoneregion-pacific'        => 'Тихий океан',
'allowemail'                    => 'Разрешить приём электронной почты от других участников',
'prefs-searchoptions'           => 'Настройки поиска',
'prefs-namespaces'              => 'Пространства имён',
'defaultns'                     => 'Иначе искать в следующих пространствах имён:',
'default'                       => 'по умолчанию',
'prefs-files'                   => 'Файлы',
'prefs-custom-css'              => 'Собственный CSS',
'prefs-custom-js'               => 'Собственный JS',
'prefs-common-css-js'           => 'Общие CSS/JS для всех тем оформления:',
'prefs-reset-intro'             => 'Эта страница может быть использована для сброса ваших настроек на настройки по умолчанию.
Подтвердив данное действие, вы не сможете его отменить.',
'prefs-emailconfirm-label'      => 'Подтверждение электронной почты:',
'prefs-textboxsize'             => 'Размер окна редактирования',
'youremail'                     => 'Электронная почта:',
'username'                      => 'Регистрационное имя:',
'uid'                           => 'Идентификатор участника:',
'prefs-memberingroups'          => 'Член {{PLURAL:$1|группы|групп}}:',
'prefs-registration'            => 'Время регистрации:',
'yourrealname'                  => 'Ваше настоящее имя:',
'yourlanguage'                  => 'Язык интерфейса:',
'yourvariant'                   => 'Вариант языка',
'yournick'                      => 'Ваш псевдоним (для подписей):',
'prefs-help-signature'          => 'Комментарии на страницах обсуждений должны быть подписаны посредством добавления символов «<nowiki>~~~~</nowiki>», которые будут преобразованы в вашу подпись и время.',
'badsig'                        => 'Неверная подпись. Проверьте корректность HTML-тегов.',
'badsiglength'                  => 'Слишком длинная подпись.
Подпись не должна превышать $1 {{PLURAL:$1|символа|символов|символов}}.',
'yourgender'                    => 'Пол:',
'gender-unknown'                => 'не указан',
'gender-male'                   => 'мужской',
'gender-female'                 => 'женский',
'prefs-help-gender'             => 'Необязательное поле: используется в некоторых сообщениях движка, зависящих от пола участника.
Эта информация будет общедоступной.',
'email'                         => 'Электронная почта',
'prefs-help-realname'           => 'Настоящее имя (необязательное поле).
Если вы укажете его, то оно будет использовано для того, чтобы показать, кем была внесена правка страницы.',
'prefs-help-email'              => 'Адрес электронной почты указывать необязательно, но он будет необходим в том случае, если вы забудете пароль.
Он также позволит другим участникам связаться с вами через вашу страницу в вики без необходимости раскрытия адреса вашей электронной почты.',
'prefs-help-email-required'     => 'Необходимо указать адрес электронной почты.',
'prefs-info'                    => 'Основные сведения',
'prefs-i18n'                    => 'Интернационализация',
'prefs-signature'               => 'Подпись',
'prefs-dateformat'              => 'Формат даты',
'prefs-timeoffset'              => 'Смещение времени',
'prefs-advancedediting'         => 'Расширенные настройки',
'prefs-advancedrc'              => 'Расширенные настройки',
'prefs-advancedrendering'       => 'Расширенные настройки',
'prefs-advancedsearchoptions'   => 'Расширенные настройки',
'prefs-advancedwatchlist'       => 'Расширенные настройки',
'prefs-displayrc'               => 'Настройки отображения',
'prefs-displaysearchoptions'    => 'Настройки отображения',
'prefs-displaywatchlist'        => 'Настройки отображения',
'prefs-diffs'                   => 'Разница версий',

# User rights
'userrights'                   => 'Управление правами участника',
'userrights-lookup-user'       => 'Управление группами участников',
'userrights-user-editname'     => 'Введите имя участника:',
'editusergroup'                => 'Изменить группы участника',
'editinguser'                  => "Изменение прав участника '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'Изменить группы участника',
'saveusergroups'               => 'Сохранить группы участника',
'userrights-groupsmember'      => 'Член групп:',
'userrights-groupsmember-auto' => 'Неявный член:',
'userrights-groups-help'       => 'Вы можете изменить группы, в которые входит этот участник.
* Если около названия группы стоит отметка, значит участник входит в эту группу.
* Если отметка не стоит — участник не относится к соответствующей группе.
* Знак * отмечает, что вы не можете удалить из группы участника, если добавите его в неё или наоборот.',
'userrights-reason'            => 'Причина:',
'userrights-no-interwiki'      => 'У вас нет разрешения изменять права участников на других вики.',
'userrights-nodatabase'        => 'База данных $1 не существует или не является локальной.',
'userrights-nologin'           => 'Вы должны [[Special:UserLogin|представиться системе]] с учётной записи администратора, чтобы присваивать права участникам.',
'userrights-notallowed'        => 'С вашей учётной записи не разрешено присваивать права участникам.',
'userrights-changeable-col'    => 'Группы, которые вы можете изменять',
'userrights-unchangeable-col'  => 'Группы, которые вы не можете изменять',

# Groups
'group'               => 'Группа:',
'group-user'          => 'Участники',
'group-autoconfirmed' => 'Автоподтверждённые участники',
'group-bot'           => 'Боты',
'group-sysop'         => 'Администраторы',
'group-bureaucrat'    => 'Бюрократы',
'group-suppress'      => 'Ревизоры',
'group-all'           => '(все)',

'group-user-member'          => 'участник',
'group-autoconfirmed-member' => 'автоподтверждённый участник',
'group-bot-member'           => 'бот',
'group-sysop-member'         => 'администратор',
'group-bureaucrat-member'    => 'бюрократ',
'group-suppress-member'      => 'ревизор',

'grouppage-user'          => '{{ns:project}}:Участники',
'grouppage-autoconfirmed' => '{{ns:project}}:Автоподтверждённые участники',
'grouppage-bot'           => '{{ns:project}}:Боты',
'grouppage-sysop'         => '{{ns:project}}:Администраторы',
'grouppage-bureaucrat'    => '{{ns:project}}:Бюрократы',
'grouppage-suppress'      => '{{ns:project}}:Ревизоры',

# Rights
'right-read'                  => 'просмотр страниц',
'right-edit'                  => 'правка страниц',
'right-createpage'            => 'создание страниц (не являющихся обсуждениями)',
'right-createtalk'            => 'создание страниц обсуждений',
'right-createaccount'         => 'создание новых учётных записей участников',
'right-minoredit'             => 'простановка отметки «малое изменение»',
'right-move'                  => 'переименование страниц',
'right-move-subpages'         => 'переименование страниц с их подстраницами',
'right-move-rootuserpages'    => 'переименование корневых страниц участников',
'right-movefile'              => 'переименование файлов',
'right-suppressredirect'      => 'не создаётся перенаправление со старого имени при переименовании страницы',
'right-upload'                => 'загрузка файлов',
'right-reupload'              => 'запись файлов поверх существующих',
'right-reupload-own'          => 'перезапись файлов тем же участником',
'right-reupload-shared'       => 'подмена файлов из общих хранилищ локальными',
'right-upload_by_url'         => 'загрузка файлов с адреса URL',
'right-purge'                 => 'очистка кеша страниц без страницы подтверждения',
'right-autoconfirmed'         => 'правка частично защищённых страниц',
'right-bot'                   => 'считаться автоматическим процессом',
'right-nominornewtalk'        => 'отсутствие малых правок на страницах обсуждений включает режим новых сообщений',
'right-apihighlimits'         => 'меньше ограничений на выполнение API-запросов',
'right-writeapi'              => 'использование API для записи',
'right-delete'                => 'удаление страниц',
'right-bigdelete'             => 'удаление страниц с длинными историями',
'right-deleterevision'        => 'удаление и восстановление конкретных версий страниц',
'right-deletedhistory'        => 'просмотр истории удалённых страниц без доступа к удалённому тексту',
'right-deletedtext'           => 'просмотр удалённого текста и изменений между удалёнными версиями страниц',
'right-browsearchive'         => 'поиск удалённых страниц',
'right-undelete'              => 'восстановление страниц',
'right-suppressrevision'      => 'просмотр и восстановление скрытых от администраторов версий страниц',
'right-suppressionlog'        => 'просмотр частных журналов',
'right-block'                 => 'установка запрета на редактирование другим участникам',
'right-blockemail'            => 'установка запрета на отправку электронной почты',
'right-hideuser'              => 'запрет имени участника и его сокрытие',
'right-ipblock-exempt'        => 'обход блокировок по IP, автоблокировок и блокировок диапазонов',
'right-proxyunbannable'       => 'обход автоматической блокировки прокси',
'right-unblockself'           => 'разблокирование себя самого',
'right-protect'               => 'изменение уровня защиты страниц и правка защищённых страниц',
'right-editprotected'         => 'правка защищённых страниц (без каскадной защиты)',
'right-editinterface'         => 'изменение пользовательского интерфейса',
'right-editusercssjs'         => 'правка CSS- и JS-файлов других участников',
'right-editusercss'           => 'правка CSS-файлов других участников',
'right-edituserjs'            => 'правка JS-файлов других участников',
'right-rollback'              => 'быстрый откат правок последнего участника на некоторой странице',
'right-markbotedits'          => 'отметка откатываемых правок как правок бота',
'right-noratelimit'           => 'нет ограничений по скорости',
'right-import'                => 'импорт страниц из других вики',
'right-importupload'          => 'импорт страниц через загрузку файлов',
'right-patrol'                => 'отметка правок как отпатрулированных',
'right-autopatrol'            => 'правки автоматически отмечаются как патрулированные',
'right-patrolmarks'           => 'просмотр отметок о патрулировании в свежих правках',
'right-unwatchedpages'        => 'просмотр списка ненаблюдаемых страниц',
'right-trackback'             => 'отправка Trackback',
'right-mergehistory'          => 'объединение историй страниц',
'right-userrights'            => 'изменение прав всех участников',
'right-userrights-interwiki'  => 'изменение прав участников на других вики-сайтах',
'right-siteadmin'             => 'блокировка и разблокировка базы данных',
'right-reset-passwords'       => 'сбрасывание паролей других участников',
'right-override-export-depth' => 'экспортирование страниц, включая связанные страницы с глубиной до 5',
'right-sendemail'             => 'отправлять электронную почту другим участникам',
'right-revisionmove'          => 'перенос версий страниц',
'right-selenium'              => 'запуск проверок Selenium',

# User rights log
'rightslog'      => 'Журнал прав участника',
'rightslogtext'  => 'Это журнал изменений прав участника.',
'rightslogentry' => 'изменил членство в группах для $1 с $2 на $3',
'rightsnone'     => '(нет)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'чтение этой страницы',
'action-edit'                 => 'исправление этой страницы',
'action-createpage'           => 'создание страниц',
'action-createtalk'           => 'создание страниц обсуждений',
'action-createaccount'        => 'создание этой учётной записи участника',
'action-minoredit'            => 'отметка этой правки как малой',
'action-move'                 => 'переименование этой страницы',
'action-move-subpages'        => 'переименование этой страницы со всеми её подстраницами',
'action-move-rootuserpages'   => 'переименовывать корневые страницы участников',
'action-movefile'             => 'переименовать этот файл',
'action-upload'               => 'загрузка этого файла',
'action-reupload'             => 'перезапись существующего файла',
'action-reupload-shared'      => 'перекрытие файла из общего хранилища',
'action-upload_by_url'        => 'загрузка этого файла с адреса URL',
'action-writeapi'             => 'использование API для правок',
'action-delete'               => 'удаление этой страницы',
'action-deleterevision'       => 'удаление этой версии страницы',
'action-deletedhistory'       => 'просмотр удалённой истории этой страницы',
'action-browsearchive'        => 'поиск удалённых страниц',
'action-undelete'             => 'восстановление этой страницы',
'action-suppressrevision'     => 'просмотр и восстановление этой скрытой версии страницы',
'action-suppressionlog'       => 'просмотр этого частного журнала',
'action-block'                => 'блокировка участника, запрещение ему делать правки',
'action-protect'              => 'изменение уровня защиты этой страницы',
'action-import'               => 'импорт этой страницы из другой вики',
'action-importupload'         => 'импорт этой страницы из загруженного файла',
'action-patrol'               => 'отметка чужих правок как отпатрулированных',
'action-autopatrol'           => 'отметка своих правок как отпатрулированных',
'action-unwatchedpages'       => 'просмотр список страниц, за которыми никто не наблюдает',
'action-trackback'            => 'отправка trackback',
'action-mergehistory'         => 'присоединение истории изменений этой страницы',
'action-userrights'           => 'изменение всех прав участника',
'action-userrights-interwiki' => 'изменение прав участников в других вики',
'action-siteadmin'            => 'блокировка и разблокировка базы данных',
'action-revisionmove'         => 'перенос версий страниц',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|изменение|изменения|изменений}}',
'recentchanges'                     => 'Свежие правки',
'recentchanges-legend'              => 'Настройки свежих правок',
'recentchangestext'                 => 'Ниже в хронологическом порядке перечислены последние изменения на страницах {{grammar:genitive|{{SITENAME}}}}.',
'recentchanges-feed-description'    => 'Отслеживать последние изменения в вики в этом потоке.',
'recentchanges-label-legend'        => 'Легенда: $1.',
'recentchanges-legend-newpage'      => '$1 — новая страница',
'recentchanges-label-newpage'       => 'Этой правкой была создана новая страница.',
'recentchanges-legend-minor'        => '$1 — малое изменение',
'recentchanges-label-minor'         => 'Это малозначимое изменение',
'recentchanges-legend-bot'          => '$1 — правка бота',
'recentchanges-label-bot'           => 'Эта правка сделана ботом',
'recentchanges-legend-unpatrolled'  => '$1 — неотпатрулированная правка',
'recentchanges-label-unpatrolled'   => 'Эту правку ещё не отпатрулировали',
'rcnote'                            => "{{PLURAL:$1|Последнее '''$1''' изменение|Последние '''$1''' изменения|Последние '''$1''' изменений}} за '''$2''' {{PLURAL:$2|день|дня|дней}}, на момент времени $5 $4.",
'rcnotefrom'                        => 'Ниже перечислены изменения с <strong>$2</strong> (по <strong>$1</strong>).',
'rclistfrom'                        => 'Показать изменения с $1.',
'rcshowhideminor'                   => '$1 малые правки',
'rcshowhidebots'                    => '$1 ботов',
'rcshowhideliu'                     => '$1 представившихся участников',
'rcshowhideanons'                   => '$1 анонимов',
'rcshowhidepatr'                    => '$1 проверенные правки',
'rcshowhidemine'                    => '$1 свои правки',
'rclinks'                           => 'Показать последние $1 изменений за $2 дней<br />$3',
'diff'                              => 'разн.',
'hist'                              => 'история',
'hide'                              => 'Скрыть',
'show'                              => 'Показать',
'minoreditletter'                   => 'м',
'newpageletter'                     => 'Н',
'boteditletter'                     => 'б',
'unpatrolledletter'                 => '!',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|наблюдающий участник|наблюдающих участника|наблюдающих участников}}]',
'rc_categories'                     => 'Только из категорий (разделитель «|»)',
'rc_categories_any'                 => 'Любой',
'newsectionsummary'                 => '/* $1 */ Новая тема',
'rc-enhanced-expand'                => 'Показать подробности (используется JavaScript)',
'rc-enhanced-hide'                  => 'Скрыть подробности',

# Recent changes linked
'recentchangeslinked'          => 'Связанные правки',
'recentchangeslinked-feed'     => 'Связанные правки',
'recentchangeslinked-toolbox'  => 'Связанные правки',
'recentchangeslinked-title'    => 'Связанные правки для $1',
'recentchangeslinked-noresult' => 'На связанных страницах не было изменений за указанный период.',
'recentchangeslinked-summary'  => "Это список недавних изменений в страницах, на которые ссылается указанная страница (или входящих в указанную категорию).
Страницы, входящие в [[Special:Watchlist|ваш список наблюдения]] '''выделены'''.",
'recentchangeslinked-page'     => 'Название страницы:',
'recentchangeslinked-to'       => 'Наоборот, показать изменения на страницах, которые ссылаются на указанную страницу',

# Upload
'upload'                      => 'Загрузить файл',
'uploadbtn'                   => 'Загрузить файл',
'reuploaddesc'                => 'Вернуться к форме загрузки',
'upload-tryagain'             => 'Отправить изменённое описание файла',
'uploadnologin'               => 'Вы не представились системе',
'uploadnologintext'           => 'Вы должны [[Special:UserLogin|представиться системе]],
чтобы загружать файлы на сервер.',
'upload_directory_missing'    => 'Директория для загрузок ($1) отсутствует и не может быть создана веб-сервером.',
'upload_directory_read_only'  => 'Веб-сервер не имеет прав записи в папку ($1), в которой предполагается хранить загружаемые файлы.',
'uploaderror'                 => 'Ошибка загрузки файла',
'upload-recreate-warning'     => "'''Внимание. Файл с таким именем был удален или переименован.''

Журнал удалений и переименований для этой странице приведён ниже:",
'uploadtext'                  => "Используя эту форму вы можете загрузить на сервер файлы.
Чтобы просмотреть ранее загруженные файлы, обратитесь к [[Special:FileList|списку загруженных файлов]]. Загрузка файлов также записывается в [[Special:Log/upload|журнал загрузок]], удаления файлов записываются в [[Special:Log/delete|журнал удалений]].

Для включения файла в статью вы можете использовать строки вида:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>''' для вставки полной версии файла;
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|описание]]</nowiki></tt>''' для вставки уменьшенной до 200 пикселей по ширине версии файла слева от текста с выводом под ним указанного описания;
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>''' для вставки ссылки на файл, без отображения его содержимого на странице.",
'upload-permitted'            => 'Разрешённые типы файлов: $1.',
'upload-preferred'            => 'Предпочтительные типы файлов: $1.',
'upload-prohibited'           => 'Запрещённые типы файлов: $1.',
'uploadlog'                   => 'журнал загрузок',
'uploadlogpage'               => 'Журнал загрузок',
'uploadlogpagetext'           => 'Ниже представлен список последних загрузок файлов.
См. также [[Special:NewFiles|галерею новых файлов]], где сведения о новых загрузках представлены в более наглядном виде.',
'filename'                    => 'Имя файла',
'filedesc'                    => 'Краткое описание',
'fileuploadsummary'           => 'Краткое описание:',
'filereuploadsummary'         => 'Изменения в файле:',
'filestatus'                  => 'Условия распространения:',
'filesource'                  => 'Источник:',
'uploadedfiles'               => 'Загруженные файлы',
'ignorewarning'               => 'Игнорировать предупреждения и сохранить файл',
'ignorewarnings'              => 'Игнорировать предупреждения',
'minlength1'                  => 'Название файла должно содержать хотя бы одну букву.',
'illegalfilename'             => 'Имя файла «$1» содержит символы, которые не разрешается использовать в заголовках. Пожалуйста, переименуйте файл и попытайтесь загрузить его снова.',
'badfilename'                 => 'Название файла было изменено на $1.',
'filetype-mime-mismatch'      => 'Расширение файла не соответствует его MIME-типу.',
'filetype-badmime'            => 'Файлы, имеющие MIME-тип «$1», не могут быть загружены.',
'filetype-bad-ie-mime'        => 'Невозможно загрузить этот файл, так как Internet Explorer будет определять его как «$1», то есть неразрешённым и потенциально опасным типом файла.',
'filetype-unwanted-type'      => "'''\".\$1\"''' — нежелательный тип файла.
{{PLURAL:\$3|Предпочтительным типом файла является|Предпочтительные типы файлов:}} \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' — запрещённый тип файла.
{{PLURAL:\$3|Разрешённым типом файла является|Разрешённые типы файлов:}} \$2.",
'filetype-missing'            => 'Отсутствует расширение у файла (например, «.jpg»).',
'empty-file'                  => 'Отправленный вами файл пуст.',
'file-too-large'              => 'Отправленный вами файл слишком велик.',
'filename-tooshort'           => 'Слишком короткое имя файла.',
'filetype-banned'             => 'Этот тип файлов запрещён.',
'verification-error'          => 'Этот файл не прошёл процедуру проверки.',
'hookaborted'                 => 'Предлагаемое вами изменение было отменено в обработчике расширения.',
'illegal-filename'            => 'Недопустимое имя файла.',
'overwrite'                   => 'Не допускается замена существующего файла.',
'unknown-error'               => 'Неизвестная ошибка.',
'tmp-create-error'            => 'Невозможно создать временный файл.',
'tmp-write-error'             => 'Ошибка записи во временный файл.',
'large-file'                  => 'Рекомендуется использовать файлы, размер которых не превышает $1 байт (размер загруженного файла составляет $2 байт).',
'largefileserver'             => 'Размер файла превышает максимально разрешённый.',
'emptyfile'                   => 'Загруженный вами файл вероятно пустой. Возможно, это произошло из-за ошибки при наборе имени файла. Пожалуйста, проверьте, действительно ли вы хотите загрузить этот файл.',
'fileexists'                  => "Файл с этим именем уже существует, пожалуйста, проверьте '''<tt>[[:$1]]</tt>''', если вы не уверены, что хотите заменить его.
[[$1|thumb]]",
'filepageexists'              => "Страница описания для этого файла уже создана как '''<tt>[[:$1]]</tt>''', но файла с таким именем сейчас нет.
Введённое описание не появится на странице описания файла.
Чтобы добавить новое описание, вам придётся изменить его вручную.
[[$1|thumb]]",
'fileexists-extension'        => "Существует файл с похожим именем: [[$2|thumb]]
* Имя загруженного файла: '''<tt>[[:$1]]</tt>'''
* Имя существующего файла: '''<tt>[[:$2]]</tt>'''
Пожалуйста, выберите другое имя.",
'fileexists-thumbnail-yes'    => "Файл, вероятно, является уменьшенной копией (миниатюрой). [[$1|thumb]]
Пожалуйста, проверьте файл '''<tt>[[:$1]]</tt>'''.
Если указанный файл является тем же изображением, не стоит загружать отдельно его уменьшенную копию.",
'file-thumbnail-no'           => "Название файла начинается с '''<tt>$1</tt>'''.
Вероятно, это уменьшенная копия изображения ''(миниатюра)''.
Если у вас есть данное изображение в полном размере, пожалуйста, загрузите его или измените имя файла.",
'fileexists-forbidden'        => 'Файл с этим именем уже существует и не может быть перезаписан.
Если всё равно хотите загрузить данный файл, пожалуйста, вернитесь назад и загрузите его под другим именем. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Файл с этим именем уже существует в общем хранилище файлов.
Если вы всё-таки хотите загрузить этот файл, пожалуйста, вернитесь назад и измените имя файла. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Этот файл является дубликатом {{PLURAL:$1|следующего файла|следующих файлов}}:',
'file-deleted-duplicate'      => 'Подобный файл ([[$1]]) уже удалялся. Пожалуйста, ознакомьтесь с историей удаления файла, прежде чем загружать его снова.',
'uploadwarning'               => 'Предупреждение',
'uploadwarning-text'          => 'Пожалуйста, измените представленное ниже описание файла и попробуйте ещё раз.',
'savefile'                    => 'Записать файл',
'uploadedimage'               => 'загрузил «[[$1]]»',
'overwroteimage'              => 'загружена новая версия «[[$1]]»',
'uploaddisabled'              => 'Загрузка запрещена',
'copyuploaddisabled'          => 'Загрузка по URL отключена.',
'uploadfromurl-queued'        => 'Ваша загрузка поставлена в очередь.',
'uploaddisabledtext'          => 'Загрузка файлов отключена.',
'php-uploaddisabledtext'      => 'Загрузка файлов отключена в настройках PHP. Пожалуйста, проверьте значение свойства file_uploads.',
'uploadscripted'              => 'Файл содержит HTML-код или скрипт, который может быть ошибочно обработан браузером.',
'uploadvirus'                 => 'Файл содержит вирус! См. $1',
'upload-source'               => 'Исходный файл',
'sourcefilename'              => 'Исходный файл:',
'sourceurl'                   => 'URL-адрес источника:',
'destfilename'                => 'Новое имя файла:',
'upload-maxfilesize'          => 'Максимальный размер файла: $1',
'upload-description'          => 'Описание файла',
'upload-options'              => 'Параметры загрузки',
'watchthisupload'             => 'Следить за этим файлом',
'filewasdeleted'              => 'Файл с таким именем уже существовал ранее, но был удалён. Пожалуйста, проверьте $1 перед повторной загрузкой.',
'upload-wasdeleted'           => "'''Внимание: вы пытаетесь загрузить файл, который ранее удалялся.'''

Проверьте, действительно ли вам нужно загружать этот файл.
Ниже приведён журнал удалений:",
'filename-bad-prefix'         => "Имя загружаемого файла начинается с '''«$1»''' и вероятно является шаблонным именем, которое цифровая фотокамера даёт снимкам. Пожалуйста, выберите имя лучше описывающее содержание файла.",
'filename-prefix-blacklist'   => ' #<!-- оставьте эту строчку как есть --> <pre>
# Синтаксис следующий:
#   * Всё, что начинается с символа «#» считается комментарием (до конца строки)
#   * Каждая непустая строка — префикс стандартного названия файла, которое обычно даёт цифровая камера
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # некоторые мобильные телефоны
IMG # общее
JD # Jenoptik
MGP # Pentax
PICT # различные
 #</pre> <!-- оставьте эту строчку как есть -->',
'upload-success-subj'         => 'Загрузка успешно завершена',
'upload-success-msg'          => 'Ваша загрузка [$2] прошла успешно. Вы можете посмотреть результат здесь: [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'Проблема загрузки',
'upload-failure-msg'          => 'Обнаружена проблема с вашей загрузкой:

$1',
'upload-warning-subj'         => 'Предупреждение при загрузке',
'upload-warning-msg'          => 'При загрузке с [$2] произошла ошибка. Для исправления ошибки вернитесь на [[Special:Upload/stash/$1|upload form]].',

'upload-proto-error'        => 'Неправильный протокол',
'upload-proto-error-text'   => 'Для удалённой загрузки требуется адрес, начинающийся с <code>http://</code> или <code>ftp://</code>.',
'upload-file-error'         => 'Внутренняя ошибка',
'upload-file-error-text'    => 'Внутренняя ошибка при попытке создать временный файл на сервере.
Пожалуйста, обратитесь к [[Special:ListUsers/sysop|администратору]].',
'upload-misc-error'         => 'Неизвестная ошибка загрузки',
'upload-misc-error-text'    => 'Неизвестная ошибка загрузки. Пожалуйста, проверьте, что адрес верен, и повторите попытку. Если проблема остаётся, обратитесь к системному администратору.',
'upload-too-many-redirects' => 'URL содержит слишком много перенаправлений',
'upload-unknown-size'       => 'Неизвестный размер',
'upload-http-error'         => 'Произошла ошибка HTTP: $1',

# img_auth script messages
'img-auth-accessdenied' => 'Доступ запрещён',
'img-auth-nopathinfo'   => 'Отсутствует PATH_INFO.
Ваш сервер не настроен, для передачи этих сведений.
Возможно, он работает на основе CGI и не поддерживает img_auth.
См. http://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir'     => 'Запрашиваемый путь не относится к настроенной папке загрузок.',
'img-auth-badtitle'     => 'Невозможно построить правильный заголовок из «$1».',
'img-auth-nologinnWL'   => 'Вы не вошли в систему, а «$1» не входит в белый список.',
'img-auth-nofile'       => 'Файл «$1» не существует.',
'img-auth-isdir'        => 'Вы пытаетесь получить доступ к каталогу «$1».
Разрешён только доступ к файлам.',
'img-auth-streaming'    => 'Потоковая передача «$1».',
'img-auth-public'       => 'Назначением img_auth.php является вывод файлов из закрытой вики.
Эта вики настроена как общедоступная.
Для оптимизации безопасности img_auth.php отключена.',
'img-auth-noread'       => 'Участник не имеет доступа на чтение к «$1».',

# HTTP errors
'http-invalid-url'      => 'Ошибочный URL: $1',
'http-invalid-scheme'   => 'Не поддерживаются адреса со схемой «$1»',
'http-request-error'    => 'Сбой HTTP-запроса из-за неизвестной ошибки.',
'http-read-error'       => 'Ошибка чтения HTTP.',
'http-timed-out'        => 'Истекло время ожидания HTTP-запроса.',
'http-curl-error'       => 'Ошибка обращения к URL: $1',
'http-host-unreachable' => 'Невозможно обратить к URL.',
'http-bad-status'       => 'Во время обработки HTTP-запроса обнаружена проблема: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Невозможно обратить по указанному адресу.',
'upload-curl-error6-text'  => 'Невозможно обратить по указанному адресу. Пожалуйста, проверьте, что адрес верен, а сайт доступен.',
'upload-curl-error28'      => 'Время, отведённое на загрузку, истекло',
'upload-curl-error28-text' => 'Сайт слишком долго не отвечает. Пожалуйста, проверьте что сайт работоспособен и после небольшого перерыва попробуйте ещё раз. Возможно, операцию следует провести в другое время, когда сайт менее нагружен.',

'license'            => 'Лицензирование:',
'license-header'     => 'Лицензирование',
'nolicense'          => 'Отсутствует',
'license-nopreview'  => '(Предпросмотр недоступен)',
'upload_source_url'  => ' (правильный, публично доступный интернет-адрес)',
'upload_source_file' => ' (файл на вашем компьютере)',

# Special:ListFiles
'listfiles-summary'     => 'Эта служебная страница показывает все загруженные файлы.
Недавно загруженные файлы по умолчанию показываются в верху списка.
Щелчок на заголовке колонки изменяет порядок сортировки.',
'listfiles_search_for'  => 'Поиск по имени файла:',
'imgfile'               => 'файл',
'listfiles'             => 'Список файлов',
'listfiles_date'        => 'Дата',
'listfiles_name'        => 'Имя файла',
'listfiles_user'        => 'Участник',
'listfiles_size'        => 'Размер',
'listfiles_description' => 'Описание',
'listfiles_count'       => 'Версий',

# File description page
'file-anchor-link'                  => 'Файл',
'filehist'                          => 'История файла',
'filehist-help'                     => 'Нажмите на дату/время, чтобы просмотреть, как тогда выглядел файл.',
'filehist-deleteall'                => 'удалить все',
'filehist-deleteone'                => 'удалить',
'filehist-revert'                   => 'вернуть',
'filehist-current'                  => 'текущий',
'filehist-datetime'                 => 'Дата/время',
'filehist-thumb'                    => 'Миниатюра',
'filehist-thumbtext'                => 'Миниатюра для версии от $1',
'filehist-nothumb'                  => 'Нет миниатюры',
'filehist-user'                     => 'Участник',
'filehist-dimensions'               => 'Размер объекта',
'filehist-filesize'                 => 'Размер файла',
'filehist-comment'                  => 'Примечание',
'filehist-missing'                  => 'Файл отсутствует',
'imagelinks'                        => 'Ссылки на файл',
'linkstoimage'                      => '{{PLURAL:$1|Следующая $1 страница ссылается|Следующие $1 страницы ссылаются|Следующие $1 страниц ссылаются}} на данный файл:',
'linkstoimage-more'                 => 'Более $1 {{PLURAL:$1|страницы|страниц|страниц}} ссылаются на этот файл.
В данном списке {{PLURAL:$1|представлена только $1 ссылка|представлены только $1 ссылки|представлены только $1 ссылок}} на этот файл.
Доступен также [[Special:WhatLinksHere/$2|полный список]].',
'nolinkstoimage'                    => 'Нет страниц, ссылающихся на данный файл.',
'morelinkstoimage'                  => 'Просмотреть [[Special:WhatLinksHere/$1|остальные ссылки]] на этот файл.',
'redirectstofile'                   => 'Со {{PLURAL:$1|следующего $1 файла установлено перенаправление|следующих $1 файлов установлены перенаправления|следующих $1 файлов установлены перенаправления}} на этот файл:',
'duplicatesoffile'                  => '{{PLURAL:$1|Следующий $1 файл является дубликатом|Следующие $1 файла являются дубликатами|Следующие $1 файлов являются дубликатами}} этого файла ([[Special:FileDuplicateSearch/$2|подробности]]):',
'sharedupload'                      => 'Этот файл из $1 и может использоваться в других проектах.',
'sharedupload-desc-there'           => 'Этот файл из $1 и может использоваться в других проектах.
Дополнительную информацию можно найти на [$2 странице описания файла].',
'sharedupload-desc-here'            => 'Этот файл из $1 и может использоваться в других проектах.
Информация с его [$2 страницы описания] приведена ниже.',
'filepage-nofile'                   => 'Не существует файла с данным именем.',
'filepage-nofile-link'              => 'Не существует файла с данным именем. Вы можете [$1 загрузить его].',
'uploadnewversion-linktext'         => 'Загрузить новую версию этого файла',
'shared-repo-from'                  => 'из $1',
'shared-repo'                       => 'общего хранилища',
'shared-repo-name-wikimediacommons' => 'Викисклада',

# File reversion
'filerevert'                => 'Возврат к старой версии $1',
'filerevert-legend'         => 'Возвратить версию файла',
'filerevert-intro'          => '<span class="plainlinks">Вы возвращаете \'\'\'[[Media:$1|$1]]\'\'\' к [$4 версии от $3, $2].</span>',
'filerevert-comment'        => 'Причина:',
'filerevert-defaultcomment' => 'Возврат к версии от $2, $1',
'filerevert-submit'         => 'Возвратить',
'filerevert-success'        => "'''[[Media:$1|$1]]''' был возвращён к [$4 версии от $3, $2].",
'filerevert-badversion'     => 'Не существует предыдущей локальной версии этого файла с указанной отметкой времени.',

# File deletion
'filedelete'                  => '$1 — удаление',
'filedelete-legend'           => 'Удалить файл',
'filedelete-intro'            => "Вы собираетесь удалить файл '''[[Media:$1|$1]]''' со всей его историей.",
'filedelete-intro-old'        => '<span class="plainlinks">Вы удаляете версию \'\'\'[[Media:$1|$1]]\'\'\' от [$4 $3, $2].</span>',
'filedelete-comment'          => 'Причина:',
'filedelete-submit'           => 'Удалить',
'filedelete-success'          => "'''$1''' был удалён.",
'filedelete-success-old'      => "Версия '''[[Media:$1|$1]]''' от $3 $2 была удалена.",
'filedelete-nofile'           => "'''$1''' не существует.",
'filedelete-nofile-old'       => "Не существует архивной версии '''$1''' с указанными атрибутами.",
'filedelete-otherreason'      => 'Другая причина:',
'filedelete-reason-otherlist' => 'Другая причина',
'filedelete-reason-dropdown'  => '* Распространённые причины удаления
** нарушение авторских прав
** файл-дубликат',
'filedelete-edit-reasonlist'  => 'Править список причин',
'filedelete-maintenance'      => 'Удаление и восстановление файлов временно отключены на время технических работ.',

# MIME search
'mimesearch'         => 'Поиск по MIME',
'mimesearch-summary' => 'Эта страница позволяет отбирать файлы по их MIME-типу. Формат ввода: типсодержимого/подтип, например <tt>image/jpeg</tt>.',
'mimetype'           => 'MIME-тип:',
'download'           => 'загрузить',

# Unwatched pages
'unwatchedpages' => 'Страницы, за которыми никто не следит',

# List redirects
'listredirects' => 'Список перенаправлений',

# Unused templates
'unusedtemplates'     => 'Неиспользуемые шаблоны',
'unusedtemplatestext' => 'На этой странице перечислены все страницы пространства имён «{{ns:template}}», которые не включены в другие страницы.
Не забывайте проверить отсутствие других ссылок на шаблон, перед его удалением.',
'unusedtemplateswlh'  => 'другие ссылки',

# Random page
'randompage'         => 'Случайная статья',
'randompage-nopages' => 'В {{PLURAL:$2|следующем пространстве имён отсутствуют страницы|следующих пространствах имён нет страниц}}: $1.',

# Random redirect
'randomredirect'         => 'Случайное перенаправление',
'randomredirect-nopages' => 'Пространство имён «$1» не содержит перенаправлений.',

# Statistics
'statistics'                   => 'Статистика',
'statistics-header-pages'      => 'Статистика по страницам',
'statistics-header-edits'      => 'Статистика правок',
'statistics-header-views'      => 'Статистика просмотров',
'statistics-header-users'      => 'Статистика по участникам',
'statistics-header-hooks'      => 'Другая статистика',
'statistics-articles'          => 'Статей',
'statistics-pages'             => 'Страниц',
'statistics-pages-desc'        => 'Все страницы в вики, включая страницы обсуждения, перенаправления и прочее.',
'statistics-files'             => 'Загружено файлов',
'statistics-edits'             => 'Число правок с момента установки {{grammar:genitive|{{SITENAME}}}}',
'statistics-edits-average'     => 'Среднее число правок на страницу',
'statistics-views-total'       => 'Всего просмотров',
'statistics-views-peredit'     => 'Просмотров на правку',
'statistics-users'             => 'Зарегистрированные [[Special:ListUsers|участники]]',
'statistics-users-active'      => 'Активные участники',
'statistics-users-active-desc' => 'Участники, совершившие какое-либо действие за {{PLURAL:$1|последний $1 день|последние $1 дня|последние $1 дней}}',
'statistics-mostpopular'       => 'Наиболее часто просматриваемые страницы',

'disambiguations'      => 'Страницы со ссылками на многозначные термины',
'disambiguationspage'  => 'Template:Неоднозначность',
'disambiguations-text' => "Следующие страницы ссылаются на '''многозначные страницы'''.
Вместо этого они, вероятно, должны указывать на соответствующую конкретную статью.<br />
Страница считается многозначной, если на ней размещён шаблон, имя которого указано на странице [[MediaWiki:Disambiguationspage]].",

'doubleredirects'            => 'Двойные перенаправления',
'doubleredirectstext'        => 'На этой странице представлен список перенаправлений на другие перенаправления.
Каждая строка содержит ссылки на первое и второе перенаправления, а также целевую страницу второго перенаправления, в которой обычно указывается название страницы, куда должно ссылаться первое перенаправление.
<del>Зачёркнутые</del> записи были исправлены.',
'double-redirect-fixed-move' => 'Страница [[$1]] была переименована, сейчас она перенаправляет на [[$2]]',
'double-redirect-fixer'      => 'Исправитель перенаправлений',

'brokenredirects'        => 'Разорванные перенаправления',
'brokenredirectstext'    => 'Следующие перенаправления указывают на несуществующие страницы:',
'brokenredirects-edit'   => 'править',
'brokenredirects-delete' => 'удалить',

'withoutinterwiki'         => 'Страницы без межъязыковых ссылок',
'withoutinterwiki-summary' => 'Следующие страницы не имеют интервики-ссылок:',
'withoutinterwiki-legend'  => 'Приставка',
'withoutinterwiki-submit'  => 'Показать',

'fewestrevisions' => 'Страницы с наименьшим количеством версий',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|байт|байта|байт}}',
'ncategories'             => '$1 {{PLURAL:$1|категория|категории|категорий}}',
'nlinks'                  => '$1 {{PLURAL:$1|ссылка|ссылки|ссылок}}',
'nmembers'                => '$1 {{PLURAL:$1|объект|объекта|объектов}}',
'nrevisions'              => '$1 {{PLURAL:$1|версия|версии|версий}}',
'nviews'                  => '$1 {{PLURAL:$1|просмотр|просмотра|просмотров}}',
'specialpage-empty'       => 'Запрос не дал результатов.',
'lonelypages'             => 'Страницы-сироты',
'lonelypagestext'         => 'На следующие страницы нет ссылок с других страниц {{grammar:genitive|{{SITENAME}}}}, и они не включаются в другие страницы.',
'uncategorizedpages'      => 'Некатегоризованные страницы',
'uncategorizedcategories' => 'Некатегоризованные категории',
'uncategorizedimages'     => 'Некатегоризованные файлы',
'uncategorizedtemplates'  => 'Некатегоризованные шаблоны',
'unusedcategories'        => 'Неиспользуемые категории',
'unusedimages'            => 'Неиспользуемые файлы',
'popularpages'            => 'Популярные страницы',
'wantedcategories'        => 'Требуемые категории',
'wantedpages'             => 'Требуемые страницы',
'wantedpages-badtitle'    => 'Ошибочный заголовок в результатах запроса: $1',
'wantedfiles'             => 'Требуемые файлы',
'wantedtemplates'         => 'Требуемые шаблоны',
'mostlinked'              => 'Страницы, на которые больше всего ссылок',
'mostlinkedcategories'    => 'Категории, на которые больше всего ссылок',
'mostlinkedtemplates'     => 'Самые используемые шаблоны',
'mostcategories'          => 'Страницы, включённые в большое количество категорий',
'mostimages'              => 'Самые используемые файлы',
'mostrevisions'           => 'Наиболее часто редактировавшиеся страницы',
'prefixindex'             => 'Указатель по началу названий страниц',
'shortpages'              => 'Короткие статьи',
'longpages'               => 'Длинные страницы',
'deadendpages'            => 'Тупиковые страницы',
'deadendpagestext'        => 'Следующие страницы не содержат ссылок на другие страницы в этой вики.',
'protectedpages'          => 'Защищённые страницы',
'protectedpages-indef'    => 'Только бессрочные защиты',
'protectedpages-cascade'  => 'Только каскадная защита',
'protectedpagestext'      => 'Следующие страницы защищены от переименования или изменения.',
'protectedpagesempty'     => 'В настоящий момент нет защищённых страниц с указанными параметрами',
'protectedtitles'         => 'Запрещённые названия',
'protectedtitlestext'     => 'Следующие названия не разрешается использовать',
'protectedtitlesempty'    => 'В настоящий момент нет запрещённых названий с указанными параметрами.',
'listusers'               => 'Список участников',
'listusers-editsonly'     => 'Показать только тех участников, кто сделал хотя бы одну правку',
'listusers-creationsort'  => 'Упорядочить по дате создания',
'usereditcount'           => '$1 {{PLURAL:$1|правка|правки|правок}}',
'usercreated'             => 'Создана $1 в $2',
'newpages'                => 'Новые страницы',
'newpages-username'       => 'Участник:',
'ancientpages'            => 'Статьи по дате последнего редактирования',
'move'                    => 'Переименовать',
'movethispage'            => 'Переименовать эту страницу',
'unusedimagestext'        => 'Следующие файлы не используются ни на одной странице.
Пожалуйста, учтите, что другие веб-сайты могут непосредственно ссылаться на этот файл (его URL), таким образом он может использоваться несмотря на вхождение в этот список.',
'unusedcategoriestext'    => 'Существуют следующие страницы категорий, не содержащие статей или других категорий.',
'notargettitle'           => 'Не указана цель',
'notargettext'            => 'Вы не указали целевую страницу или участника для этого действия.',
'nopagetitle'             => 'Нет такой целевой страницы',
'nopagetext'              => 'Указанной целевой страницы не существует.',
'pager-newer-n'           => '{{PLURAL:$1|более новая|более новые|более новых}} $1',
'pager-older-n'           => '{{PLURAL:$1|более старая|более старые|более старых}} $1',
'suppress'                => 'Сокрытие',

# Book sources
'booksources'               => 'Источники книг',
'booksources-search-legend' => 'Поиск информации о книге',
'booksources-go'            => 'Найти',
'booksources-text'          => 'На этой странице приведён список ссылок на сайты, где вы, возможно, найдёте дополнительную информацию о книге. Это интернет-магазины и системы поиска в библиотечных каталогах.',
'booksources-invalid-isbn'  => 'Указанный номер ISBN, судя по всему, содержит ошибку. Пожалуйста, проверьте, что при переносе номера из первоисточника не возникло искажений.',

# Special:Log
'specialloguserlabel'  => 'Участник:',
'speciallogtitlelabel' => 'Заголовок:',
'log'                  => 'Журналы',
'all-logs-page'        => 'Все доступные журналы',
'alllogstext'          => 'Общий список журналов сайта {{SITENAME}}.
Вы можете отфильтровать результаты по типу журнала, имени участника (учитывается регистр) или затронутой странице (также учитывается регистр).',
'logempty'             => 'Подходящие записи в журнале отсутствуют.',
'log-title-wildcard'   => 'Найти заголовки, начинающиеся на с данных символов',

# Special:AllPages
'allpages'          => 'Все страницы',
'alphaindexline'    => 'от $1 до $2',
'nextpage'          => 'Следующая страница ($1)',
'prevpage'          => 'Предыдущая страница ($1)',
'allpagesfrom'      => 'Вывести страницы, начинающиеся на:',
'allpagesto'        => 'Остановить вывод на:',
'allarticles'       => 'Все страницы',
'allinnamespace'    => 'Все страницы в пространстве имён «$1»',
'allnotinnamespace' => 'Все страницы (кроме пространства имён «$1»)',
'allpagesprev'      => 'Предыдущие',
'allpagesnext'      => 'Следующие',
'allpagessubmit'    => 'Выполнить',
'allpagesprefix'    => 'Найти страницы, начинающиеся с:',
'allpagesbadtitle'  => 'Недопустимое название страницы. Заголовок содержит интервики, межъязыковой префикс или запрещённые в заголовках символы.',
'allpages-bad-ns'   => '{{SITENAME}} не содержит пространства имён «$1».',

# Special:Categories
'categories'                    => 'Категории',
'categoriespagetext'            => '{{PLURAL:$1|Следующая категория содержит|Следующие категории содержат}} страницы или медиа-файлы.
Здесь не показаны [[Special:UnusedCategories|неиспользуемые категории]].
См. также [[Special:WantedCategories|список требуемых категорий]].',
'categoriesfrom'                => 'Показать категории, начинающиеся с:',
'special-categories-sort-count' => 'упорядочить по количеству',
'special-categories-sort-abc'   => 'упорядочить по алфавиту',

# Special:DeletedContributions
'deletedcontributions'             => 'Удалённый вклад участника',
'deletedcontributions-title'       => 'Удалённый вклад',
'sp-deletedcontributions-contribs' => 'вклад',

# Special:LinkSearch
'linksearch'       => 'Внешние ссылки',
'linksearch-pat'   => 'Шаблон для поиска:',
'linksearch-ns'    => 'Пространство имён:',
'linksearch-ok'    => 'Найти',
'linksearch-text'  => 'Можно использовать подстановочные символы, например, <code>*.wikipedia.org</code>.<br />
Поддерживаемые протоколы: <tt>$1</tt>',
'linksearch-line'  => 'Ссылка на $1 из $2',
'linksearch-error' => 'Подстановочные символы могут использоваться только в начале адресов.',

# Special:ListUsers
'listusersfrom'      => 'Показать участников, начиная с:',
'listusers-submit'   => 'Показать',
'listusers-noresult' => 'Не найдено участников.',
'listusers-blocked'  => '(заблокирован)',

# Special:ActiveUsers
'activeusers'            => 'Список активных участников',
'activeusers-intro'      => 'Это список участников, совершавших какие-либо действия за {{PLURAL:$1|последний $1 день|последние $1 дня|последние $1 дней}}.',
'activeusers-count'      => '$1 {{PLURAL:$1|правка|правки|правок}} за {{PLURAL:$3|последний $3 день|последние $3 дня|последние $3 дней}}',
'activeusers-from'       => 'Показать участников, начиная с:',
'activeusers-hidebots'   => 'Скрыть ботов',
'activeusers-hidesysops' => 'Скрыть администраторов',
'activeusers-noresult'   => 'Не найдено участников.',

# Special:Log/newusers
'newuserlogpage'              => 'Журнал регистрации участников',
'newuserlogpagetext'          => 'Список недавно зарегистрировавшихся участников',
'newuserlog-byemail'          => 'пароль отправлен по эл. почте',
'newuserlog-create-entry'     => 'Новый участник',
'newuserlog-create2-entry'    => 'создал новую учётную запись $1',
'newuserlog-autocreate-entry' => 'Учётная запись создана автоматически',

# Special:ListGroupRights
'listgrouprights'                      => 'Права групп участников',
'listgrouprights-summary'              => 'Ниже представлен список определённых в этой вики групп участников, указаны соответствующие им права доступа.
Возможно, существует [[{{MediaWiki:Listgrouprights-helppage}}|дополнительная информация]] об индивидуальных правах.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Предоставленные права</span>
* <span class="listgrouprights-revoked">Отозванные права</span>',
'listgrouprights-group'                => 'Группа',
'listgrouprights-rights'               => 'Права',
'listgrouprights-helppage'             => 'Help:Права групп',
'listgrouprights-members'              => '(список группы)',
'listgrouprights-addgroup'             => 'может добавлять в {{PLURAL:$2|группу|группы}}: $1',
'listgrouprights-removegroup'          => 'может исключать из {{PLURAL:$2|группы|групп}}: $1',
'listgrouprights-addgroup-all'         => 'может добавлять во все группы',
'listgrouprights-removegroup-all'      => 'может исключать из всех групп',
'listgrouprights-addgroup-self'        => 'может добавить {{PLURAL:$2|группу|группы}} к своей учётной записи: $1',
'listgrouprights-removegroup-self'     => 'может удалить {{PLURAL:$2|группу|группы}} со своей учётной записи: $1',
'listgrouprights-addgroup-self-all'    => 'Может добавлять все группы к своей учётной записи',
'listgrouprights-removegroup-self-all' => 'Может удалять все группы со своей учётной записи',

# E-mail user
'mailnologin'          => 'Адрес для отправки отсутствует',
'mailnologintext'      => 'Вы должны [[Special:UserLogin|представиться системе]] и иметь действительный адрес электронной почты в ваших [[Special:Preferences|настройках]], чтобы иметь возможность отправлять электронную почту другим участникам.',
'emailuser'            => 'Письмо участнику',
'emailpage'            => 'Письмо участнику',
'emailpagetext'        => 'С помощью данной формы можно отправить сообщение на адрес электронной почты этого участника.
В качестве обратного адреса будет указан тот адрес, который вы указали в [[Special:Preferences|своих настройках]], таким образом получатель будет иметь возможность ответить непосредственно вам.',
'usermailererror'      => 'При отправке сообщения электронной почты произошла ошибка:',
'defemailsubject'      => 'Письмо из {{grammar:genitive|{{SITENAME}}}}',
'usermaildisabled'     => 'Электронная почта участника отключена',
'usermaildisabledtext' => 'Вы не можете отправлять электронные письма другим участникам этой вики',
'noemailtitle'         => 'Адрес электронной почты отсутствует',
'noemailtext'          => 'Этот участник не указал действительный адрес электронной почты.',
'nowikiemailtitle'     => 'Нет разрешения отправлять письма',
'nowikiemailtext'      => 'Этот участник указал, что не желает получать письма от других участников.',
'email-legend'         => 'Отправить письмо другому участнику {{grammar:genitive|{{SITENAME}}}}',
'emailfrom'            => 'От кого:',
'emailto'              => 'Кому:',
'emailsubject'         => 'Тема:',
'emailmessage'         => 'Сообщение:',
'emailsend'            => 'Отправить',
'emailccme'            => 'Отправить мне копию письма.',
'emailccsubject'       => 'Копия вашего сообщения для $1: $2',
'emailsent'            => 'Письмо отправлено',
'emailsenttext'        => 'Ваше электронное сообщение отправлено.',
'emailuserfooter'      => 'Это письмо было отправлено участнику $2 от участника $1 с помощью функции «Отправить письмо» проекта {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'Оставить системное сообщение.',
'usermessage-editor'  => 'Системная доставка',

# Watchlist
'watchlist'            => 'Список наблюдения',
'mywatchlist'          => 'Cписок наблюдения',
'watchlistfor'         => "(участника '''$1''')",
'nowatchlist'          => 'Ваш список наблюдения пуст.',
'watchlistanontext'    => 'Вы должны $1, чтобы просмотреть или отредактировать список наблюдения.',
'watchnologin'         => 'Нужно представиться системе',
'watchnologintext'     => 'Вы должны [[Special:UserLogin|представиться системе]], чтобы иметь возможность изменять свой список наблюдения',
'addedwatch'           => 'Добавлена в список наблюдения',
'addedwatchtext'       => 'Страница «[[:$1]]» была добавлена в ваш [[Special:Watchlist|список наблюдения]].
Последующие изменения этой страницы и связанной с ней страницы обсуждения будут отмечаться в этом списке, а также будут выделены жирным шрифтом на странице со [[Special:RecentChanges|списком свежих изменений]], чтобы их было легче заметить.',
'removedwatch'         => 'Удалена из списка наблюдения',
'removedwatchtext'     => 'Страница «[[:$1]]» была удалена из вашего [[Special:Watchlist|списка наблюдения]].',
'watch'                => 'Следить',
'watchthispage'        => 'Наблюдать за этой страницей',
'unwatch'              => 'Не следить',
'unwatchthispage'      => 'Прекратить наблюдение',
'notanarticle'         => 'Не статья',
'notvisiblerev'        => 'Версия была удалена',
'watchnochange'        => 'Ничто из списка наблюдения не изменялось в рассматриваемый период.',
'watchlist-details'    => 'В вашем списке наблюдения $1 {{PLURAL:$1|страница|страницы|страниц}}, не считая страниц обсуждения.',
'wlheader-enotif'      => '* Уведомление по эл. почте включено.',
'wlheader-showupdated' => "* Страницы, изменившиеся с вашего последнего их посещения, выделены '''жирным''' шрифтом.",
'watchmethod-recent'   => 'просмотр последних изменений для наблюдаемых страниц',
'watchmethod-list'     => 'просмотр наблюдаемых страниц для последних изменений',
'watchlistcontains'    => 'Ваш список наблюдения содержит $1 {{PLURAL:$1|страница|страницы|страниц}}.',
'iteminvalidname'      => 'Проблема с элементом «$1», недопустимое название…',
'wlnote'               => 'Ниже {{PLURAL:$1|следует последнее $1 изменение|следуют последние $1 изменения|следуют последние $1 изменений}} за {{PLURAL:$2|последний|последние|последние}} <strong>$2</strong> {{plural:$2|час|часа|часов}}.',
'wlshowlast'           => 'Показать за последние $1 часов $2 дней $3',
'watchlist-options'    => 'Настройки списка наблюдения',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Добавление в список наблюдения…',
'unwatching' => 'Удаление из списка наблюдения…',

'enotif_mailer'                => '{{SITENAME}} Служба извещений по почте',
'enotif_reset'                 => 'Отметить все страницы как просмотренные',
'enotif_newpagetext'           => 'Это новая страница.',
'enotif_impersonal_salutation' => 'Участник {{grammar:genitive|{{SITENAME}}}}',
'changed'                      => 'изменена',
'created'                      => 'создана',
'enotif_subject'               => 'Страница проекта «{{SITENAME}}» $PAGETITLE была $CHANGEDORCREATED участником $PAGEEDITOR',
'enotif_lastvisited'           => 'См. $1 для просмотра всех изменений, произошедших с вашего последнего посещения.',
'enotif_lastdiff'              => 'См. $1 для ознакомления с изменением.',
'enotif_anon_editor'           => 'анонимный участник $1',
'enotif_body'                  => 'Уважаесый(ая) $WATCHINGUSERNAME,

$PAGEEDITDATE страница проекта «{{SITENAME}}» $PAGETITLE была $CHANGEDORCREATED участником $PAGEEDITOR, см. $PAGETITLE_URL для просмотра текущей версии.

$NEWPAGE

Краткое описание изменения: $PAGESUMMARY $PAGEMINOREDIT

Обратиться к изменившему:
эл. почта: $PAGEEDITOR_EMAIL
вики: $PAGEEDITOR_WIKI

Если вы не посетите эту страницу, то в случае её дальнейших изменений уведомлений больше не будет.
Вы можете также отключить опцию уведомления для всех страниц в вашем списке наблюдения.

             Система оповещения {{grammar:genitive|{{SITENAME}}}}

--
Изменить настройки вашего списка наблюдения
{{fullurl:{{#special:Watchlist}}/edit}}

Удалить страницы из вашего списка наблюдения
$UNWATCHURL

Обратная связь и помощь
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Удалить страницу',
'confirm'                => 'Подтвердить',
'excontent'              => 'содержимое: «$1»',
'excontentauthor'        => 'содержимое: «$1» (единственным автором был [[Special:Contributions/$2|$2]])',
'exbeforeblank'          => 'содержимое до очистки: «$1»',
'exblank'                => 'страница была пуста',
'delete-confirm'         => '$1 — удаление',
'delete-legend'          => 'Удаление',
'historywarning'         => "'''Предупреждение'''. Страница, которую вы собираетесь удалить, имеет историю изменений приблизительно из $1 {{PLURAL:$1|версии|версий|версий}}:",
'confirmdeletetext'      => 'Вы запросили полное удаление страницы (или изображения) и всей её истории изменений из базы данных.
Пожалуйста, подтвердите, что вы действительно желаете это сделать, понимаете последствия своих действий,
и делаете это в соответствии с правилами, изложенными в разделе [[{{MediaWiki:Policy-url}}]].',
'actioncomplete'         => 'Действие выполнено',
'actionfailed'           => 'Действие не выполнено',
'deletedtext'            => '«<nowiki>$1</nowiki>» была удалена.
См. $2 для просмотра списка последних удалений.',
'deletedarticle'         => 'удалил «[[$1]]»',
'suppressedarticle'      => 'скрыл «[[$1]]»',
'dellogpage'             => 'Журнал удалений',
'dellogpagetext'         => 'Ниже приведён журнал последних удалений.',
'deletionlog'            => 'журнал удалений',
'reverted'               => 'Откачено к ранней версии',
'deletecomment'          => 'Причина:',
'deleteotherreason'      => 'Другая причина/дополнение:',
'deletereasonotherlist'  => 'Другая причина',
'deletereason-dropdown'  => '* Типовые причины удаления
** вандализм
** по запросу автора
** нарушение авторских прав',
'delete-edit-reasonlist' => 'Править список причин',
'delete-toobig'          => 'У этой страницы очень длинная история изменений, более $1 {{PLURAL:$1|версии|версий|версий}}.
Удаление таких страниц было запрещено во избежание нарушений в работе сайта {{SITENAME}}.',
'delete-warning-toobig'  => 'У этой страницы очень длинная история изменений, более $1 {{PLURAL:$1|версии|версий|версий}}.
Её удаление может привести к нарушению нормальной работы базы данных сайта {{SITENAME}};
действуйте с осторожностью.',

# Rollback
'rollback'          => 'Откатить изменения',
'rollback_short'    => 'Откат',
'rollbacklink'      => 'откатить',
'rollbackfailed'    => 'Ошибка при совершении отката',
'cantrollback'      => 'Невозможно откатить изменения. Последний, кто вносил изменения, является единственным автором этой страницы.',
'alreadyrolled'     => 'Невозможно откатить последние изменения [[:$1]], сделанные [[User:$2|$2]] ([[User talk:$2|Обсуждение]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]);
кто-то другой уже отредактировал или откатил эту страницу.

Последние изменения внёс [[User:$3|$3]] ([[User talk:$3|Обсуждение]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "Изменение было пояснено как ''«$1»''.",
'revertpage'        => 'Правки [[Special:Contributions/$2|$2]] ([[User talk:$2|обсуждение]]) откачены к версии [[User:$1|$1]]',
'revertpage-nouser' => 'Правки (имя участника удалено) откачены к версии [[User:$1|$1]]',
'rollback-success'  => 'Откачены правки $1; возврат к версии $2.',

# Edit tokens
'sessionfailure-title' => 'Ошибка сеанса',
'sessionfailure'       => 'Похоже, возникли проблемы с текущим сеансом работы;
это действие было отменено в целях предотвращения «захвата сеанса».
Пожалуйста, нажмите кнопку «Назад» и перезагрузите страницу, с которой вы пришли.',

# Protect
'protectlogpage'              => 'Журнал защиты',
'protectlogtext'              => 'Ниже приведён журнал установок и снятий защиты со статей. Вы можете также просмотреть [[Special:ProtectedPages|список страниц, которые в данный момент защищены]].',
'protectedarticle'            => 'защищена страница «[[$1]]»',
'modifiedarticleprotection'   => 'изменён уровень защиты страницы «[[$1]]»',
'unprotectedarticle'          => 'снята защита со страницы «[[$1]]»',
'movedarticleprotection'      => 'перенёс настройки защиты с «[[$2]]» на «[[$1]]»',
'protect-title'               => 'Установка уровня защиты для «$1»',
'prot_1movedto2'              => '«[[$1]]» переименована в «[[$2]]»',
'protect-legend'              => 'Подтвердите установку защиты страницы',
'protectcomment'              => 'Причина:',
'protectexpiry'               => 'Истекает:',
'protect_expiry_invalid'      => 'Неправильное время окончания защиты.',
'protect_expiry_old'          => 'Время окончания — в прошлом.',
'protect-unchain-permissions' => 'Открыть дополнительные параметры защиты',
'protect-text'                => "Здесь вы можете просмотреть и изменить уровень защиты для страницы '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "Вы не можете изменять уровень защиты страницы, пока ваша учётная запись заблокирована.
Текущие установки для страницы '''$1''':",
'protect-locked-dblock'       => "Уровень защиты не может быть изменён, так как основная база данных временно заблокирована.
Текущие установки для страницы '''$1''':",
'protect-locked-access'       => "У вашей учётной записи недостаточно прав для изменения уровня защиты страницы. Текущие установки для страницы '''$1''':",
'protect-cascadeon'           => 'Эта страница защищена в связи с тем, что она включена {{PLURAL:$1|в указанную ниже страницу, на которую|в нижеследующие страницы, на которые}} установлена каскадная защита. Вы можете изменить уровень защиты этой страницы, но это не повлияет на каскадную защиту.',
'protect-default'             => 'Без защиты',
'protect-fallback'            => 'Требуется разрешение «$1»',
'protect-level-autoconfirmed' => 'Защитить от новых и незарегистрированных участников',
'protect-level-sysop'         => 'Только администраторы',
'protect-summary-cascade'     => 'каскадная',
'protect-expiring'            => 'истекает $1 (UTC)',
'protect-expiry-indefinite'   => 'бессрочно',
'protect-cascade'             => 'Защищать страницы, включённые в эту страницу (каскадная защита)',
'protect-cantedit'            => 'Вы не можете изменить уровень защиты этой страницы, потому что у вас нет прав для её редактирования.',
'protect-othertime'           => 'Другое время:',
'protect-othertime-op'        => 'другое время',
'protect-existing-expiry'     => 'Текущее время окончания: $2, $3',
'protect-otherreason'         => 'Другая причина/дополнение:',
'protect-otherreason-op'      => 'Другая причина',
'protect-dropdown'            => '* Типовые причины защиты
** заядлый вандализм
** чрезмерный спам
** непродуктивная война правок
** популярная страница',
'protect-edit-reasonlist'     => 'Править список причин',
'protect-expiry-options'      => '1 час:1 hour,1 день:1 day,1 неделя:1 week,2 недели:2 weeks,1 месяц:1 month,3 месяца:3 months,6 месяцев:6 months,1 год:1 year,бессрочно:infinite',
'restriction-type'            => 'Права:',
'restriction-level'           => 'Уровень доступа:',
'minimum-size'                => 'Минимальный размер',
'maximum-size'                => 'Максимальный размер:',
'pagesize'                    => '(байт)',

# Restrictions (nouns)
'restriction-edit'   => 'Редактирование',
'restriction-move'   => 'Переименование',
'restriction-create' => 'Создание',
'restriction-upload' => 'Загрузка',

# Restriction levels
'restriction-level-sysop'         => 'полная защита',
'restriction-level-autoconfirmed' => 'частичная защита',
'restriction-level-all'           => 'все уровни',

# Undelete
'undelete'                     => 'Просмотреть удалённые страницы',
'undeletepage'                 => 'Просмотр и восстановление удалённых страниц',
'undeletepagetitle'            => "'''Ниже перечислены удалённые версии страницы [[:$1]]'''.",
'viewdeletedpage'              => 'Просмотреть удалённые страницы',
'undeletepagetext'             => '{{PLURAL:$1|Следующая $1 страница была удалена|Следующие $1 страницы были удалены|Следующие $1 страниц были удалены}}, однако {{PLURAL:$1|она всё ещё находятся в архиве, и поэтому может быть восстановлена|они всё ещё находятся в архиве, и поэтому могут быть восстановлены}}.
Архив может периодически очищаться.',
'undelete-fieldset-title'      => 'Восстановить версии',
'undeleteextrahelp'            => "Для полного восстановления истории страницы оставьте все отметки пустыми и нажмите '''«Восстановить»'''.
Для частичного восстановления отметьте те версии страницы, которые нужно восстановить, и нажмите '''«Восстановить»'''.
Нажмите '''«Очистить»''', чтобы снять все отметки и очистить поле примечания.",
'undeleterevisions'            => 'в архиве $1 {{PLURAL:$1|версия|версии|версий}}',
'undeletehistory'              => 'При восстановлении страницы восстанавливается и её история правок.
Если после удаления была создана новая страница с тем же названием, то восстановленные версии появятся в истории правок перед новыми версиями.',
'undeleterevdel'               => 'Восстановление не будет произведено, если оно приведёт к частичному удалению последней версии страницы или файла.
В подобном случае вы должны снять отметку или показать последние удалённые версии.',
'undeletehistorynoadmin'       => 'Статья была удалена. Причина удаления и список участников, редактировавших статью до её удаления, показаны ниже. Текст удалённой статьи могут просмотреть только администраторы.',
'undelete-revision'            => 'Удалённая версия $1 (от $4 $5) участника $3:',
'undeleterevision-missing'     => 'Неверная или отсутствующая версия. Возможно, вы перешли по неправильной ссылке, либо версия могла быть удалена из архива.',
'undelete-nodiff'              => 'Не найдено предыдущей версии.',
'undeletebtn'                  => 'Восстановить',
'undeletelink'                 => 'просмотреть/восстановить',
'undeleteviewlink'             => 'просмотреть',
'undeletereset'                => 'Очистить',
'undeleteinvert'               => 'Обратить выделение',
'undeletecomment'              => 'Причина:',
'undeletedarticle'             => 'восстановлена «[[$1]]»',
'undeletedrevisions'           => '$1 {{PLURAL:$1|изменение|изменения|изменений}} восстановлено',
'undeletedrevisions-files'     => '$1 {{PLURAL:$1|версия|версии|версий}} и $2 {{PLURAL:$2|файл|файла|файлов}} восстановлено',
'undeletedfiles'               => '$1 {{PLURAL:$1|файл восстановлен|файла восстановлено|файлов восстановлено}}',
'cannotundelete'               => 'Ошибка восстановления. Возможно, кто-то другой уже восстановил страницу.',
'undeletedpage'                => "'''Страница «$1» была восстановлена.'''

Для просмотра списка последних удалений и восстановлений см. [[Special:Log/delete|журнал удалений]].",
'undelete-header'              => 'Список недавно удалённых страниц можно посмотреть в [[Special:Log/delete|журнале удалений]].',
'undelete-search-box'          => 'Поиск удалённых страниц',
'undelete-search-prefix'       => 'Показать страницы, начинающиеся с:',
'undelete-search-submit'       => 'Найти',
'undelete-no-results'          => 'Не найдено соответствующих страниц в архиве удалений.',
'undelete-filename-mismatch'   => 'Невозможно восстановить версию файла с отметкой времени $1: несоответствие имени файла',
'undelete-bad-store-key'       => 'Невозможно восстановить версию файла с отметкой времени $1: файл отсутствовал до удаления.',
'undelete-cleanup-error'       => 'Ошибка удаления неиспользуемого архивного файла «$1».',
'undelete-missing-filearchive' => 'Невозможно восстановить файл с архивным идентификатором $1, так как он отсутствует в базе данных. Возможно, файл уже был восстановлен.',
'undelete-error-short'         => 'Ошибка восстановления файла: $1',
'undelete-error-long'          => 'Во время восстановления файла возникли ошибки:

$1',
'undelete-show-file-confirm'   => 'Вы уверены, что желаете просмотреть удалённую версию файла <nowiki>$1</nowiki> от $2 $3?',
'undelete-show-file-submit'    => 'Да',

# Namespace form on various pages
'namespace'      => 'Пространство имён:',
'invert'         => 'Обратить выбранное',
'blanknamespace' => '(Основное)',

# Contributions
'contributions'       => 'Вклад участника',
'contributions-title' => 'Вклад участника $1',
'mycontris'           => 'Мой вклад',
'contribsub2'         => 'Вклад $1 ($2)',
'nocontribs'          => 'Изменений, соответствующих заданным условиям, найдено не было.',
'uctop'               => ' (последняя)',
'month'               => 'С месяца (и ранее):',
'year'                => 'С года (и ранее):',

'sp-contributions-newbies'             => 'Показать только вклад, сделанный с новых учётных записей',
'sp-contributions-newbies-sub'         => 'С новых учётных записей',
'sp-contributions-newbies-title'       => 'Вклад с недавно созданных учётных записей',
'sp-contributions-blocklog'            => 'блокировки',
'sp-contributions-deleted'             => 'удалённые правки участника',
'sp-contributions-logs'                => 'журналы',
'sp-contributions-talk'                => 'обсуждение',
'sp-contributions-userrights'          => 'управление правами участника',
'sp-contributions-blocked-notice'      => 'Этот участник в данный момент заблокирован. Ниже приведена последняя запись из журнала блокировок:',
'sp-contributions-blocked-notice-anon' => 'Этот IP-адрес в данный момент заблокирован.
Ниже приведена последняя запись из журнала блокировок:',
'sp-contributions-search'              => 'Поиск вклада',
'sp-contributions-username'            => 'IP-адрес или имя участника:',
'sp-contributions-toponly'             => 'Показывать только последние версии',
'sp-contributions-submit'              => 'Найти',

# What links here
'whatlinkshere'            => 'Ссылки сюда',
'whatlinkshere-title'      => 'Страницы, ссылающиеся на «$1»',
'whatlinkshere-page'       => 'Страница:',
'linkshere'                => "Следующие страницы ссылаются на '''[[:$1]]''':",
'nolinkshere'              => "На страницу '''[[:$1]]''' отсутствуют ссылки с других страниц.",
'nolinkshere-ns'           => "В выбранном пространстве имён нет страниц, ссылающихся на '''[[:$1]]'''.",
'isredirect'               => 'страница-перенаправление',
'istemplate'               => 'включение',
'isimage'                  => 'ссылка для изображения',
'whatlinkshere-prev'       => '{{PLURAL:$1|предыдущая|предыдущие|предыдущие}} $1',
'whatlinkshere-next'       => '{{PLURAL:$1|следующая|следующие|следующие}} $1',
'whatlinkshere-links'      => '← ссылки',
'whatlinkshere-hideredirs' => '$1 перенаправления',
'whatlinkshere-hidetrans'  => '$1 включения',
'whatlinkshere-hidelinks'  => '$1 ссылки',
'whatlinkshere-hideimages' => '$1 ссылки для изображений',
'whatlinkshere-filters'    => 'Фильтры',

# Block/unblock
'blockip'                         => 'Заблокировать',
'blockip-title'                   => 'Блокирование участника',
'blockip-legend'                  => 'Блокировка участника',
'blockiptext'                     => 'Используйте форму ниже, чтобы заблокировать возможность записи с определённого IP-адреса.
Это может быть сделано только для предотвращения вандализма и только в соответствии с [[{{MediaWiki:Policy-url}}|правилами]].
Ниже укажите конкретную причину (к примеру, процитируйте некоторые страницы с признаками вандализма).',
'ipaddress'                       => 'IP-адрес:',
'ipadressorusername'              => 'IP-адрес или имя участника:',
'ipbexpiry'                       => 'Закончится через:',
'ipbreason'                       => 'Причина:',
'ipbreasonotherlist'              => 'Другая причина',
'ipbreason-dropdown'              => '* Стандартные причины блокировок
** Вставка ложной информации
** Удаление содержимого страниц
** Спам-ссылки на внешние сайты
** Добавление бессмысленного текста/мусора
** Угрозы, преследование участников
** Злоупотребление несколькими учётными записями
** Неприемлемое имя участника',
'ipbanononly'                     => 'Блокировать только анонимных участников',
'ipbcreateaccount'                => 'Запретить создание новых учётных записей',
'ipbemailban'                     => 'Запретить участнику отправлять письма по электронной почте',
'ipbenableautoblock'              => 'Автоматически блокировать используемые участником IP-адреса',
'ipbsubmit'                       => 'Заблокировать этот адрес/участника',
'ipbother'                        => 'Другое время:',
'ipboptions'                      => '2 часа:2 hours,1 день:1 day,3 дня:3 days,1 неделя:1 week,2 недели:2 weeks,1 месяц:1 month,3 месяца:3 months,6 месяцев:6 months,1 год:1 year,бессрочно:infinite',
'ipbotheroption'                  => 'другое',
'ipbotherreason'                  => 'Другая причина/дополнение:',
'ipbhidename'                     => 'Скрыть имя участника из правок и списков',
'ipbwatchuser'                    => 'Добавить в список наблюдения личную страницу участника и его страницу обсуждения',
'ipballowusertalk'                => 'Разрешить участнику править свою страницу обсуждения во время блокировки',
'ipb-change-block'                => 'Переблокировать участника с этими настройками',
'badipaddress'                    => 'IP-адрес записан в неправильном формате, или участника с таким именем не существует.',
'blockipsuccesssub'               => 'Блокировка произведена',
'blockipsuccesstext'              => '[[Special:Contributions/$1|«$1»]] заблокирован.<br />
См. [[Special:IPBlockList|список заблокированных IP-адресов]].',
'ipb-edit-dropdown'               => 'Править список причин',
'ipb-unblock-addr'                => 'Разблокировать $1',
'ipb-unblock'                     => 'Разблокировать участника или IP-адрес',
'ipb-blocklist-addr'              => 'Действующие блокировки для $1',
'ipb-blocklist'                   => 'Показать действующие блокировки',
'ipb-blocklist-contribs'          => 'Вклад участника $1',
'unblockip'                       => 'Разблокировать IP-адрес',
'unblockiptext'                   => 'Используйте форму ниже, чтобы восстановить возможность записи с ранее заблокированного IP-адреса или учётной записи.',
'ipusubmit'                       => 'Снять эту блокировку',
'unblocked'                       => '[[User:$1|$1]] разблокирован.',
'unblocked-id'                    => 'Блокировка $1 была снята',
'ipblocklist'                     => 'Заблокированные IP-адреса и учётные записи',
'ipblocklist-legend'              => 'Поиск заблокированного участника',
'ipblocklist-username'            => 'Имя участника или IP-адрес:',
'ipblocklist-sh-userblocks'       => '$1 блокировки учётных записей',
'ipblocklist-sh-tempblocks'       => '$1 временные блокировки',
'ipblocklist-sh-addressblocks'    => '$1 блокировки отдельных IP',
'ipblocklist-submit'              => 'Найти',
'ipblocklist-localblock'          => 'Локальная блокировка',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|Другая блокировка|Другие блокировки}}',
'blocklistline'                   => '$1, $2 заблокировал $3 ($4)',
'infiniteblock'                   => 'бессрочная блокировка',
'expiringblock'                   => 'завершится $1 $2',
'anononlyblock'                   => 'только анонимов',
'noautoblockblock'                => 'автоблокировка отключена',
'createaccountblock'              => 'создание учётных записей запрещено',
'emailblock'                      => 'отправка писем запрещена',
'blocklist-nousertalk'            => 'не может править свою страницу обсуждения',
'ipblocklist-empty'               => 'Список блокировок пуст.',
'ipblocklist-no-results'          => 'Заданный IP-адрес или имя участника не заблокированы.',
'blocklink'                       => 'заблокировать',
'unblocklink'                     => 'разблокировать',
'change-blocklink'                => 'изменить блокировку',
'contribslink'                    => 'вклад',
'autoblocker'                     => 'Автоблокировка из-за совпадения вашего IP-адреса с адресом, который недавно использовал [[User:$1|$1]].
Причина блокировки $1 — «$2».',
'blocklogpage'                    => 'Журнал блокировок',
'blocklog-showlog'                => '{{GENDER:$1|Этот участник уже блокировался|Эта участница уже блокировалась}} ранее.
Ниже приведён журнал блокировок:',
'blocklog-showsuppresslog'        => 'Этот участник уже заблокирован и скрыт. Журнал сокрытий приведён ниже:',
'blocklogentry'                   => 'заблокировал [[$1]] на период $2 $3',
'reblock-logentry'                => 'изменил настройки блокировки для [[$1]], истекает $2 $3',
'blocklogtext'                    => 'Журнал блокирования и разблокирования участников.
Автоматически блокируемые IP-адреса здесь не указываются.
См. [[Special:IPBlockList|Список текущих запретов и блокировок]].',
'unblocklogentry'                 => 'разблокировал $1',
'block-log-flags-anononly'        => 'только анонимные пользователи',
'block-log-flags-nocreate'        => 'запрещена регистрация учётных записей',
'block-log-flags-noautoblock'     => 'автоблокировка отключена',
'block-log-flags-noemail'         => 'отправка писем запрещена',
'block-log-flags-nousertalk'      => 'не может править свою страницу обсуждения',
'block-log-flags-angry-autoblock' => 'включён расширенный автоблок',
'block-log-flags-hiddenname'      => 'имя участника скрыто',
'range_block_disabled'            => 'Администраторам запрещено блокировать диапазоны.',
'ipb_expiry_invalid'              => 'Недопустимый период действия.',
'ipb_expiry_temp'                 => 'Блокировки с сокрытием имени участника должны быть бессрочными.',
'ipb_hide_invalid'                => 'Невозможно скрыть учётную запись, возможно, с неё сделано слишком много правок.',
'ipb_already_blocked'             => '«$1» уже заблокирован.',
'ipb-needreblock'                 => '== Уже заблокирован ==
Участник $1 уже заблокирован. Желаете ли вы изменить параметры блокировки?',
'ipb-otherblocks-header'          => '{{PLURAL:$1|Другая блокировка|Другие блокировки}}',
'ipb_cant_unblock'                => 'Ошибка. Не найдена блокировка с ID $1. Возможно, она уже была снята.',
'ipb_blocked_as_range'            => 'Ошибка: IP-адрес $1 был заблокирован не напрямую и не может быть разблокирован. Однако, он принадлежит к заблокированному диапазону $2, который можно разблокировать.',
'ip_range_invalid'                => 'Недопустимый диапазон IP-адресов.',
'ip_range_toolarge'               => 'Блокировки диапазонов свыше /$1 запрещены.',
'blockme'                         => 'Заблокируй меня',
'proxyblocker'                    => 'Блокировка прокси',
'proxyblocker-disabled'           => 'Функция отключена.',
'proxyblockreason'                => 'Ваш IP-адрес заблокирован потому что это открытый прокси. Пожалуйста, свяжитесь с вашим интернет-провайдером  или службой поддержки и сообщите им об этой серьёзной проблеме безопасности.',
'proxyblocksuccess'               => 'Выполнено.',
'sorbsreason'                     => 'Ваш IP-адрес числится как открытый прокси в DNSBL.',
'sorbs_create_account_reason'     => 'Ваш IP-адрес числится как открытый прокси в DNSBL. Вы не можете создать учётную запись.',
'cant-block-while-blocked'        => 'Вы не можете блокировать других участников, пока вы сами заблокированы.',
'cant-see-hidden-user'            => 'Участник, которого вы пытаетесь заблокировать, уже заблокирован и скрыт. Поскольку у вас нет разрешения на работу по сокрытию участников, вы не можете просмотреть или изменить данную блокировку.',
'ipbblocked'                      => 'Вы не можете блокировать или разблокировать других участников, так как вы сами заблокированы',
'ipbnounblockself'                => 'Вы не можете разблокировать самого себя',

# Developer tools
'lockdb'              => 'Сделать базу данных доступной только для чтения',
'unlockdb'            => 'Восстановить возможность записи в базу данных',
'lockdbtext'          => 'Блокировка базы данных приостановит для всех участников возможность редактировать страницы, изменять настройки,
изменять списки наблюдения и производить другие действия, требующие доступа к базе данных.
Пожалуйста, подтвердите, что это — именно то, что вы хотите сделать, и что вы снимете блокировку как только закончите
процедуру обслуживания базы данных.',
'unlockdbtext'        => 'Разблокирование базы данных восстановит для всех участников
возможность редактировать страницы, изменять настройки, изменять списки наблюдения и производить
другие действия, требующие доступа к базе данных.
Пожалуйста, подтвердите, что вы намерены это сделать.',
'lockconfirm'         => 'Да, я действительно хочу заблокировать базу данных на запись.',
'unlockconfirm'       => 'Да, я действительно хочу снять блокировку базы данных.',
'lockbtn'             => 'Сделать базу данных доступной только для чтения',
'unlockbtn'           => 'Восстановить возможность записи в базу данных',
'locknoconfirm'       => 'Вы не поставили галочку в поле подтверждения.',
'lockdbsuccesssub'    => 'База данных заблокирована',
'unlockdbsuccesssub'  => 'База данных разблокирована',
'lockdbsuccesstext'   => 'База данных проекта была заблокирована.<br />
Не забудьте [[Special:UnlockDB|убрать блокировку]] после завершения процедуры обслуживания.',
'unlockdbsuccesstext' => 'База данных проекта была разблокирована.',
'lockfilenotwritable' => 'Нет права на запись в файл блокировки базы данных. Чтобы заблокировать или разблокировать БД, веб-сервер должен иметь разрешение на запись в этот файл.',
'databasenotlocked'   => 'База данных не была заблокирована.',

# Move page
'move-page'                    => '$1 — переименование',
'move-page-legend'             => 'Переименование страницы',
'movepagetext'                 => "Воспользовавшись формой ниже, вы переименуете страницу, одновременно переместив на новое место её журнал изменений.
Старое название станет перенаправлением на новое название.
Вы можете автоматически обновить перенаправления, которые вели на старое название.
Если вы этого не сделаете, пожалуйста, проверьте наличие [[Special:DoubleRedirects|двойных]] и [[Special:BrokenRedirects|разорванных перенаправлений]].
Вы отвечаете за то, что бы ссылки продолжали и далее указывают туда, куда предполагалось.

Обратите внимание, что страница '''не будет''' переименована, если страница с новым названием уже существует, кроме случаев, если она является перенаправлением или пуста и не имеет истории правок.
Это означает, что вы можете переименовать страницу обратно в то название, которое у него только что было, если вы переименовали по ошибке, но вы не можете случайно затереть существующую страницу.

'''ПРЕДУПРЕЖДЕНИЕ!'''
Переименование может привести к масштабным и неожиданным изменениям для ''популярных'' страниц.
Пожалуйста, прежде чем вы продолжите, убедитесь, что вы понимаете все возможные последствия.",
'movepagetalktext'             => "Присоединённая страница обсуждения будет также автоматически переименована, '''кроме случаев, когда:'''

*Не пустая страница обсуждения уже существует под таким же именем или
*Вы не поставили галочку в поле ниже.

В этих случаях, вы будете вынуждены переместить или объединить страницы вручную, если это нужно.",
'movearticle'                  => 'Переименовать страницу',
'moveuserpage-warning'         => "'''Внимание.''' Вы собираетесь переименовать страницу участника. Пожалуйста, обратите внимание, что переименована будет только страница, участник '''не''' будет переименован.",
'movenologin'                  => 'Вы не представились системе',
'movenologintext'              => 'Вы должны [[Special:UserLogin|представиться системе]],
чтобы иметь возможность переименовать страницы.',
'movenotallowed'               => 'У вас нет разрешения переименовывать страницы.',
'movenotallowedfile'           => 'У вас нет разрешения переименовывать файлы.',
'cant-move-user-page'          => 'У вас нет разрешения переименовывать основные страницы участников.',
'cant-move-to-user-page'       => 'У вас нет разрешения преобразовывать какую-либо страницу в страницу в пространстве участника (за исключением подстраниц участника).',
'newtitle'                     => 'Новое название',
'move-watch'                   => 'Включить эту страницу в список наблюдения',
'movepagebtn'                  => 'Переименовать страницу',
'pagemovedsub'                 => 'Страница переименована',
'movepage-moved'               => "'''Страница «$1» переименована в «$2»'''",
'movepage-moved-redirect'      => 'Было создано перенаправление.',
'movepage-moved-noredirect'    => 'Создание перенаправления было подавлено.',
'articleexists'                => 'Страница с таким именем уже существует или указанное вами название недопустимо.
Пожалуйста, выберите другое название.',
'cantmove-titleprotected'      => 'Невозможно переименовать страницу, так как новое название входит в список запрещённых.',
'talkexists'                   => "'''Страница была переименована, но страница обсуждения не может быть переименована, потому что страница с таким названием уже существует. Пожалуйста, объедините их вручную.'''",
'movedto'                      => 'переименована в',
'movetalk'                     => 'Переименовать соответствующую страницу обсуждения',
'move-subpages'                => 'Переименовать подстраницы (до $1)',
'move-talk-subpages'           => 'Переименовать подстраницы страницы обсуждения (до $1)',
'movepage-page-exists'         => 'Страница $1 уже существует и не может быть автоматически перезаписана.',
'movepage-page-moved'          => 'Страница $1 была переименована в $2.',
'movepage-page-unmoved'        => 'Страница $1 не может быть переименована в $2.',
'movepage-max-pages'           => '$1 {{PLURAL:$1|страница была переименована|страницы было переименовано|страниц было переименовано}} — это максимум, больше страниц нельзя переименовать автоматически.',
'1movedto2'                    => 'переименовал «[[$1]]» в «[[$2]]»',
'1movedto2_redir'              => '«[[$1]]» переименована в «[[$2]]» поверх перенаправления',
'move-redirect-suppressed'     => 'перенаправление подавлено',
'movelogpage'                  => 'Журнал переименований',
'movelogpagetext'              => 'Ниже представлен список переименованных страниц.',
'movesubpage'                  => '{{PLURAL:$1|Подстраница|Подстраницы}}',
'movesubpagetext'              => 'У этой страницы $1 {{PLURAL:$1|подстраница|подстраницы|подстраниц}}.',
'movenosubpage'                => 'У этой страницы нет подстраниц.',
'movereason'                   => 'Причина:',
'revertmove'                   => 'откат',
'delete_and_move'              => 'Удалить и переименовать',
'delete_and_move_text'         => '==Требуется удаление==

Страница с именем [[:$1|«$1»]] уже существует. Вы хотите её удалить, чтобы сделать возможным переименование?',
'delete_and_move_confirm'      => 'Да, удалить эту страницу',
'delete_and_move_reason'       => 'Удалено для возможности переименования',
'selfmove'                     => 'Невозможно переименовать страницу: исходное и новое имя страницы совпадают.',
'immobile-source-namespace'    => 'Невозможно переименовывать страницы в пространстве имён «$1»',
'immobile-target-namespace'    => 'Невозможно переместить страницу в пространство имён «$1»',
'immobile-target-namespace-iw' => 'Ссылка интервики не может быть использована для переименования.',
'immobile-source-page'         => 'Эту страницу нельзя переименовывать.',
'immobile-target-page'         => 'Нельзя присвоить странице это имя.',
'imagenocrossnamespace'        => 'Невозможно дать файлу имя из другого пространства имён',
'nonfile-cannot-move-to-file'  => 'Невозможно переименовывать страницы в файлы',
'imagetypemismatch'            => 'Новое расширение файла не соответствует его типу',
'imageinvalidfilename'         => 'Целевое имя файла ошибочно',
'fix-double-redirects'         => 'Исправить перенаправления, указывающие на прежнее название',
'move-leave-redirect'          => 'Оставить перенаправление',
'protectedpagemovewarning'     => "'''Предупреждение. Эта страница была защищена; переименовать её могут только участники с полномочиями администраторов.'''
Ниже для справки приведена последняя запись журнала:",
'semiprotectedpagemovewarning' => "'''Замечание.''' Эта страница была защищена; переименовать её могут только зарегистрированные участники.
Ниже для справки приведена последняя запись журнала:",
'move-over-sharedrepo'         => '== Файл уже существует ==
В общем хранилище существует [[:$1]]. Переименование файла в это название вызовет перекрытие файла из общего хранилища.',
'file-exists-sharedrepo'       => 'Выбранное имя файла уже используется в общем хранилище.
Пожалуйста, выберите другое имя.',

# Export
'export'            => 'Экспорт страниц',
'exporttext'        => 'Вы можете экспортировать текст и журнал изменений конкретной страницы или набора страниц в XML, который потом может быть [[Special:Import|импортирован]] в другой вики-проект, работающий на программном обеспечении MediaWiki.

Чтобы экспортировать статьи, введите их наименования в поле редактирования, одно название на строку, и выберите хотите ли вы экспортировать всю историю изменений статей или только последние версии статей.

Вы также можете использовать специальный адрес для экспорта только последней версии. Например для страницы [[{{MediaWiki:Mainpage}}]] это будет адрес [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]].',
'exportcuronly'     => 'Включать только текущую версию, без полной предыстории',
'exportnohistory'   => "----
'''Замечание:''' экспорт полной истории изменений страниц отключён из-за проблем с производительностью.",
'export-submit'     => 'Экспортировать',
'export-addcattext' => 'Добавить страницы из категории:',
'export-addcat'     => 'Добавить',
'export-addnstext'  => 'Добавить страницы из пространства имён:',
'export-addns'      => 'Добавить',
'export-download'   => 'Предложить сохранить как файл',
'export-templates'  => 'Включить шаблоны',
'export-pagelinks'  => 'Включить связанные страницы глубиной:',

# Namespace 8 related
'allmessages'                   => 'Системные сообщения',
'allmessagesname'               => 'Сообщение',
'allmessagesdefault'            => 'Текст по умолчанию',
'allmessagescurrent'            => 'Текущий текст',
'allmessagestext'               => 'Это список системных сообщений, доступных в пространстве имён «MediaWiki».
Если вы хотите внести вклад в общую локализацию MediaWiki, пожалуйста, посетите страницу [http://www.mediawiki.org/wiki/Localisation описания локализации] и проект [http://translatewiki.net translatewiki.net].',
'allmessagesnotsupportedDB'     => "Эта страница недоступна, так как отключена опция '''\$wgUseDatabaseMessages'''.",
'allmessages-filter-legend'     => 'Фильтр',
'allmessages-filter'            => 'Фильтр по статусу изменения:',
'allmessages-filter-unmodified' => 'Неизменённые',
'allmessages-filter-all'        => 'Все',
'allmessages-filter-modified'   => 'Изменённые',
'allmessages-prefix'            => 'Фильтр по приставке:',
'allmessages-language'          => 'Язык:',
'allmessages-filter-submit'     => 'Перейти',

# Thumbnails
'thumbnail-more'           => 'Увеличить',
'filemissing'              => 'Файл не найден',
'thumbnail_error'          => 'Ошибка создания миниатюры: $1',
'djvu_page_error'          => 'Номер страницы DjVu вне досягаемости',
'djvu_no_xml'              => 'Невозможно получить XML для DjVu',
'thumbnail_invalid_params' => 'Ошибочный параметр миниатюры',
'thumbnail_dest_directory' => 'Невозможно создать целевую директорию',
'thumbnail_image-type'     => 'Данный тип изображения не поддерживается',
'thumbnail_gd-library'     => 'Неполная конфигурация библиотеки GD, отсутствует функция $1',
'thumbnail_image-missing'  => 'По видимому, отсутствует файл $1',

# Special:Import
'import'                     => 'Импортирование страниц',
'importinterwiki'            => 'Межвики импорт',
'import-interwiki-text'      => 'Укажите вики и название импортируемой страницы.
Даты изменений и имена авторов будут сохранены.
Все операции межвики импорта регистрируются в [[Special:Log/import|соответствующем журнале]].',
'import-interwiki-source'    => 'Вики-источник/страница:',
'import-interwiki-history'   => 'Копировать всю историю изменений этой страницы',
'import-interwiki-templates' => 'Включить все шаблоны',
'import-interwiki-submit'    => 'Импортировать',
'import-interwiki-namespace' => 'Целевое пространство имён:',
'import-upload-filename'     => 'Имя файла:',
'import-comment'             => 'Примечание:',
'importtext'                 => 'Пожалуйста, экспортируйте страницу из исходной вики, используя [[Special:Export|соответствующий инструмент]]. Сохраните файл на диск, а затем загрузите его сюда.',
'importstart'                => 'Импортирование страниц…',
'import-revision-count'      => '$1 {{PLURAL:$1|версия|версии|версий}}',
'importnopages'              => 'Нет страниц для импортирования.',
'imported-log-entries'       => '{{PLURAL:$1|Импортирована $1 запись журнала|Импортировано $1 записи журнала|Импортировано $1 записей журнала}}.',
'importfailed'               => 'Не удалось импортировать: $1',
'importunknownsource'        => 'Неизвестный тип импортируемой страницы',
'importcantopen'             => 'Невозможно открыть импортируемый файл',
'importbadinterwiki'         => 'Неправильная интервики-ссылка',
'importnotext'               => 'Текст отсутствует',
'importsuccess'              => 'Импортирование выполнено!',
'importhistoryconflict'      => 'Конфликт существующих версий (возможно, эта страница уже была импортирована)',
'importnosources'            => 'Не был выбран источник межвики-импорта, прямая загрузка истории изменений отключена.',
'importnofile'               => 'Файл для импорта не был загружен.',
'importuploaderrorsize'      => 'Не удалось загрузить или импортировать файл. Размер файла превышает установленный предел.',
'importuploaderrorpartial'   => 'Не удалось загрузить или импортировать файл. Он был загружен лишь частично.',
'importuploaderrortemp'      => 'Не удалось загрузить или импортировать файл. Временная папка отсутствует.',
'import-parse-failure'       => 'Ошибка разбора XML при импорте',
'import-noarticle'           => 'Нет страницы для импортирования!',
'import-nonewrevisions'      => 'Все редакции были ранее импортированы.',
'xml-error-string'           => '$1 в строке $2, позиции $3 (байт $4): $5',
'import-upload'              => 'Загрузить XML-данные',
'import-token-mismatch'      => 'Потеряны данные сеанса. Пожалуйста, попробуйте ещё раз.',
'import-invalid-interwiki'   => 'Невозможно импортировать из указанной вики.',

# Import log
'importlogpage'                    => 'Журнал импорта',
'importlogpagetext'                => 'Импортирование администраторами страниц с историей изменений из других вики.',
'import-logentry-upload'           => '«[[$1]]» — импорт из файла',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|версия|версии|версий}}',
'import-logentry-interwiki'        => '«$1» — межвики импорт',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|версия|версии|версий}} из $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Ваша страница участника',
'tooltip-pt-anonuserpage'         => 'Страница участника для моего IP',
'tooltip-pt-mytalk'               => 'Ваша страница обсуждений',
'tooltip-pt-anontalk'             => 'Страница обсуждений для моего IP',
'tooltip-pt-preferences'          => 'Ваши настройки',
'tooltip-pt-watchlist'            => 'Список страниц, изменения в которых вы отслеживаете',
'tooltip-pt-mycontris'            => 'Список ваших правок',
'tooltip-pt-login'                => 'Здесь можно зарегистрироваться в системе, но это необязательно.',
'tooltip-pt-anonlogin'            => 'Здесь можно зарегистрироваться в системе, но это необязательно.',
'tooltip-pt-logout'               => 'Завершить сеанс работы',
'tooltip-ca-talk'                 => 'Обсуждение содержания страницы',
'tooltip-ca-edit'                 => 'Эту страницу можно изменять. Используйте, пожалуйста, предварительный просмотр перед сохранением',
'tooltip-ca-addsection'           => 'Создать новый раздел',
'tooltip-ca-viewsource'           => 'Эта страница защищена от изменений, но вы можете посмотреть и скопировать её исходный текст',
'tooltip-ca-history'              => 'Журнал изменений страницы',
'tooltip-ca-protect'              => 'Защитить страницу от изменений',
'tooltip-ca-unprotect'            => 'Снять защиту с этой страницы',
'tooltip-ca-delete'               => 'Удалить эту страницу',
'tooltip-ca-undelete'             => 'Восстановить исправления страницы, сделанные до того, как она была удалена',
'tooltip-ca-move'                 => 'Переименовать страницу',
'tooltip-ca-watch'                => 'Добавить эту страницу в ваш список наблюдения',
'tooltip-ca-unwatch'              => 'Удалить эту страницу из вашего списка наблюдения',
'tooltip-search'                  => 'Искать это слово',
'tooltip-search-go'               => 'Перейти к странице, имеющей в точности такое название',
'tooltip-search-fulltext'         => 'Найти страницы, содержащие указанный текст',
'tooltip-p-logo'                  => 'Заглавная страница',
'tooltip-n-mainpage'              => 'Перейти на заглавную страницу',
'tooltip-n-mainpage-description'  => 'Перейти на заглавную страницу',
'tooltip-n-portal'                => 'О проекте, о том, что вы можете сделать, где что находится',
'tooltip-n-currentevents'         => 'Список текущих событий',
'tooltip-n-recentchanges'         => 'Список последних изменений',
'tooltip-n-randompage'            => 'Посмотреть случайную страницу',
'tooltip-n-help'                  => 'Справочник по проекту «{{SITENAME}}»',
'tooltip-t-whatlinkshere'         => 'Список всех страниц, которые ссылаются на эту страницу',
'tooltip-t-recentchangeslinked'   => 'Последние изменения в страницах, на которые ссылается эта страница',
'tooltip-feed-rss'                => 'Трансляция в RSS для этой страницы',
'tooltip-feed-atom'               => 'Трансляция в Atom для этой страницы',
'tooltip-t-contributions'         => 'Список страниц, которые изменял этот участник',
'tooltip-t-emailuser'             => 'Отправить письмо этому участнику',
'tooltip-t-upload'                => 'Загрузить изображения или мультимедиа-файлы',
'tooltip-t-specialpages'          => 'Список служебных страниц',
'tooltip-t-print'                 => 'Версия этой страницы для печати',
'tooltip-t-permalink'             => 'Постоянная ссылка на эту версию страницы',
'tooltip-ca-nstab-main'           => 'Содержание статьи',
'tooltip-ca-nstab-user'           => 'Персональная страница участника',
'tooltip-ca-nstab-media'          => 'Медиа-файл',
'tooltip-ca-nstab-special'        => 'Это служебная страница, она недоступна для редактирования',
'tooltip-ca-nstab-project'        => 'Страница проекта',
'tooltip-ca-nstab-image'          => 'Страница файла',
'tooltip-ca-nstab-mediawiki'      => 'Страница сообщения MediaWiki',
'tooltip-ca-nstab-template'       => 'Страница шаблона',
'tooltip-ca-nstab-help'           => 'Страница справки',
'tooltip-ca-nstab-category'       => 'Страница категории',
'tooltip-minoredit'               => 'Отметить это изменение как незначительное',
'tooltip-save'                    => 'Сохранить ваши изменения',
'tooltip-preview'                 => 'Предварительный просмотр страницы, пожалуйста, используйте перед сохранением!',
'tooltip-diff'                    => 'Показать изменения, сделанные по отношению к исходному тексту.',
'tooltip-compareselectedversions' => 'Посмотреть разницу между двумя выбранными версиями этой страницы.',
'tooltip-watch'                   => 'Добавить эту страницу в свой список наблюдения',
'tooltip-recreate'                => 'Восстановить страницу несмотря на то, что она была удалена',
'tooltip-upload'                  => 'Начать загрузку',
'tooltip-rollback'                => 'Одним нажатием убрать изменения, сделанные последним редактором',
'tooltip-undo'                    => 'Убрать внесённую правку и показать предпросмотр, с возможностью указать причину отмены',
'tooltip-preferences-save'        => 'Сохранить настройки',
'tooltip-summary'                 => 'Введите краткое описание',

# Stylesheets
'common.css'   => '/** Размещённый здесь CSS будет применяться ко всем темам оформления */',
'monobook.css' => '/* Размещённый здесь CSS будет применяться к теме оформления Monobook */',
'vector.css'   => '/* Размещённый здесь CSS будет применяться к векторной теме оформления  */',
'print.css'    => '/* Размещённый здесь CSS будет применяться к версии для печати */',

# Scripts
'common.js'   => '/* Размещённый здесь JavaScript код будет загружаться всем пользователям при обращении к каждой странице */',
'monobook.js' => '/* Указанный здесь JavaScript будет загружен всем участникам, использующим тему оформления MonoBook  */',
'vector.js'   => '/* Указанный здесь JavaScript будет загружен всем участникам, использующим векторную тему оформления  */',

# Metadata
'nodublincore'      => 'Метаданные Dublin Core RDF запрещены для этого сервера.',
'nocreativecommons' => 'Метаданные Creative Commons RDF запрещены для этого сервера.',
'notacceptable'     => "Вики-сервер не может предоставить данные в формате, который мог бы прочитать ваш браузер.<br />
The wiki server can't provide data in a format your client can read.",

# Attribution
'anonymous'        => '{{PLURAL:$1|Анонимный участник|Анонимные участники}} {{grammar:genitive|{{SITENAME}}}}',
'siteuser'         => 'участник {{grammar:genitive|{{SITENAME}}}} $1',
'anonuser'         => 'анонимный участник {{grammar:genitive|{{SITENAME}}}} $1',
'lastmodifiedatby' => 'Эта страница последний раз была изменена $1 в $2, автор изменения — $3.',
'othercontribs'    => 'В создании приняли участие: $1.',
'others'           => 'другие',
'siteusers'        => '{{PLURAL:$2|участник|участники}} {{grammar:genitive|{{SITENAME}}}} $1',
'anonusers'        => '{{PLURAL:$2|анонимный участник|анонимные участники}} {{grammar:genitive|{{SITENAME}}}} $1',
'creditspage'      => 'Благодарности',
'nocredits'        => 'Нет списка участников для этой статьи',

# Spam protection
'spamprotectiontitle' => 'Спам-фильтр',
'spamprotectiontext'  => 'Страница, которую вы пытаетесь сохранить, заблокирована спам-фильтром.
Вероятно, это произошло из-за того, что она содержит ссылку на занесённый в чёрный список внешний сайт.',
'spamprotectionmatch' => 'Следующая ссылка вызвала срабатывание спам-фильтра: $1.',
'spambot_username'    => 'Чистка спама',
'spam_reverting'      => 'Откат к последней версии, не содержащей ссылки на $1',
'spam_blanking'       => 'Все версии содержат ссылки на $1, очистка',

# Info page
'infosubtitle'   => 'Информация о странице',
'numedits'       => 'Число правок (статья): $1',
'numtalkedits'   => 'Число правок (страница обсуждения): $1',
'numwatchers'    => 'Число наблюдателей: $1',
'numauthors'     => 'Число различных авторов (статья): $1',
'numtalkauthors' => 'Число различных авторов (страница обсуждения): $1',

# Skin names
'skinname-standard'    => 'Классическое',
'skinname-nostalgia'   => 'Ностальгия',
'skinname-cologneblue' => 'Кёльнская тоска',
'skinname-myskin'      => 'Своё',
'skinname-chick'       => 'Цыпа',
'skinname-simple'      => 'Простое',
'skinname-modern'      => 'Современное',
'skinname-vector'      => 'Векторное',

# Math options
'mw_math_png'    => 'Всегда генерировать PNG',
'mw_math_simple' => 'HTML в простых случаях, иначе PNG',
'mw_math_html'   => 'HTML, если возможно, иначе PNG',
'mw_math_source' => 'Оставить в разметке ТеХ (для текстовых браузеров)',
'mw_math_modern' => 'Как рекомендуется для современных браузеров',
'mw_math_mathml' => 'MathML, если возможно (экспериментальная опция)',

# Math errors
'math_failure'          => 'Невозможно разобрать выражение',
'math_unknown_error'    => 'неизвестная ошибка',
'math_unknown_function' => 'неизвестная функция',
'math_lexing_error'     => 'лексическая ошибка',
'math_syntax_error'     => 'синтаксическая ошибка',
'math_image_error'      => 'Преобразование в PNG прошло с ошибкой; проверьте правильность установки latex, dvips, gs и convert',
'math_bad_tmpdir'       => 'Не удаётся создать или записать во временный каталог математики',
'math_bad_output'       => 'Не удаётся создать или записать в выходной каталог математики',
'math_notexvc'          => 'Выполняемый файл texvc не найден; См. math/README — справку по настройке.',

# Patrolling
'markaspatrolleddiff'                 => 'Отметить как проверенную',
'markaspatrolledtext'                 => 'Отметить эту статью как проверенную',
'markedaspatrolled'                   => 'Отмечена как проверенная',
'markedaspatrolledtext'               => 'Выбранная версия статьи [[:$1]] была отмечена как отпатрулированная.',
'rcpatroldisabled'                    => 'Патрулирование последних изменений запрещено',
'rcpatroldisabledtext'                => 'Возможность патрулирования последних изменений в настоящее время отключена.',
'markedaspatrollederror'              => 'Невозможно отметить как проверенную',
'markedaspatrollederrortext'          => 'Вы должны указать версию, которая будет отмечена как проверенная.',
'markedaspatrollederror-noautopatrol' => 'Вам не разрешено отмечать собственные правки как проверенные.',

# Patrol log
'patrol-log-page'      => 'Журнал патрулирования',
'patrol-log-header'    => 'Это журнал патрулированных версий.',
'patrol-log-line'      => 'проверил $1 из $2 $3',
'patrol-log-auto'      => '(автоматически)',
'patrol-log-diff'      => 'версию $1',
'log-show-hide-patrol' => '$1 журнал патрулирования',

# Image deletion
'deletedrevision'                 => 'Удалена старая версия $1',
'filedeleteerror-short'           => 'Ошибка удаления файла: $1',
'filedeleteerror-long'            => 'Во время удаления файла возникли ошибки:

$1',
'filedelete-missing'              => 'Файл «$1» не может быть удалён, так как его не существует.',
'filedelete-old-unregistered'     => 'Указанной версии файла «$1» не существует в базе данных.',
'filedelete-current-unregistered' => 'Указанного файла «$1» не существует в базе данных.',
'filedelete-archive-read-only'    => 'Архивная директория «$1» не доступна для записи веб-серверу.',

# Browsing diffs
'previousdiff' => '← Предыдущая правка',
'nextdiff'     => 'Следующая правка →',

# Media information
'mediawarning'         => "'''Внимание'''. Этот тип файла может содержать вредоносный программный код.
После его запуска, ваша система может быть подвержена риску.",
'imagemaxsize'         => "Ограничение на размер изображения:<br />''(для страницы описания файла)''",
'thumbsize'            => 'Размер уменьшенной версии изображения:',
'widthheight'          => '$1 × $2',
'widthheightpage'      => '$1 × $2, $3 {{PLURAL:$3|страница|страницы|страниц}}',
'file-info'            => '(размер файла: $1, MIME-тип: $2)',
'file-info-size'       => '($1 × $2 пикселов, размер файла: $3, MIME-тип: $4)',
'file-nohires'         => '<small>Нет версии с большим разрешением.</small>',
'svg-long-desc'        => '(SVG-файл, номинально $1 × $2 пикселов, размер файла: $3)',
'show-big-image'       => 'Изображение в более высоком разрешении',
'show-big-image-thumb' => '<small>Размер при предпросмотре: $1 × $2 пикселов</small>',
'file-info-gif-looped' => 'закольцованный',
'file-info-gif-frames' => '$1 {{PLURAL:$1|фрейм|фрейма|фреймов}}',
'file-info-png-looped' => 'закольцованный',
'file-info-png-repeat' => 'проигрывается $1 {{PLURAL:$1|раз|раза|раз}}',
'file-info-png-frames' => '$1 {{PLURAL:$1|кадр|кадра|кадров}}',

# Special:NewFiles
'newimages'             => 'Галерея новых файлов',
'imagelisttext'         => "Ниже представлен список из '''$1''' {{PLURAL:$1|файла|файлов|файлов}}, отсортированных $2.",
'newimages-summary'     => 'Эта служебная страница показывает недавно загруженные файлы.',
'newimages-legend'      => 'Фильтр',
'newimages-label'       => 'Имя файла (или его часть):',
'showhidebots'          => '($1 ботов)',
'noimages'              => 'Изображения отсутствуют.',
'ilsubmit'              => 'Найти',
'bydate'                => 'по дате',
'sp-newimages-showfrom' => 'Показать новые файлы, начиная с $2, $1',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'video-dims'     => '$1, $2 × $3',
'seconds-abbrev' => 'с',
'minutes-abbrev' => 'м',
'hours-abbrev'   => 'ч',

# Bad image list
'bad_image_list' => 'Формат должен быть следующим:

Будут учитываться только элементы списка (строки, начинающиеся на символ *).
Первая ссылка строки должна быть ссылкой на запрещённое для вставки изображение.
Последующие ссылки в той же строке будут рассматриваться как исключения, то есть статьи, куда изображение может быть включено.',

# Metadata
'metadata'          => 'Метаданные',
'metadata-help'     => 'Файл содержит дополнительные данные, обычно добавляемые цифровыми камерами или сканерами. Если файл после создания редактировался, то некоторые параметры могут не соответствовать текущему изображению.',
'metadata-expand'   => 'Показать дополнительные данные',
'metadata-collapse' => 'Скрыть дополнительные данные',
'metadata-fields'   => 'Поля метаданных, перечисленные в этом списке, будут показаны на странице изображения по умолчанию, остальные будут скрыты.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Ширина',
'exif-imagelength'                 => 'Высота',
'exif-bitspersample'               => 'Глубина цвета',
'exif-compression'                 => 'Метод сжатия',
'exif-photometricinterpretation'   => 'Цветовая модель',
'exif-orientation'                 => 'Ориентация кадра',
'exif-samplesperpixel'             => 'Количество цветовых компонентов',
'exif-planarconfiguration'         => 'Принцип организации данных',
'exif-ycbcrsubsampling'            => 'Отношение размеров компонент Y и C',
'exif-ycbcrpositioning'            => 'Порядок размещения компонент Y и C',
'exif-xresolution'                 => 'Горизонтальное разрешение',
'exif-yresolution'                 => 'Вертикальное разрешение',
'exif-resolutionunit'              => 'Единица измерения разрешения',
'exif-stripoffsets'                => 'Положение блока данных',
'exif-rowsperstrip'                => 'Количество строк в 1 блоке',
'exif-stripbytecounts'             => 'Размер сжатого блока',
'exif-jpeginterchangeformat'       => 'Положение начала блока preview',
'exif-jpeginterchangeformatlength' => 'Размер данных блока preview',
'exif-transferfunction'            => 'Функция преобразования цветового пространства',
'exif-whitepoint'                  => 'Цветность белой точки',
'exif-primarychromaticities'       => 'Цветность основных цветов',
'exif-ycbcrcoefficients'           => 'Коэффициенты преобразования цветовой модели',
'exif-referenceblackwhite'         => 'Положение белой и чёрной точек',
'exif-datetime'                    => 'Дата и время изменения файла',
'exif-imagedescription'            => 'Название изображения',
'exif-make'                        => 'Производитель камеры',
'exif-model'                       => 'Модель камеры',
'exif-software'                    => 'Программное обеспечение',
'exif-artist'                      => 'Автор',
'exif-copyright'                   => 'Владелец авторского права',
'exif-exifversion'                 => 'Версия Exif',
'exif-flashpixversion'             => 'Поддерживаемая версия FlashPix',
'exif-colorspace'                  => 'Цветовое пространство',
'exif-componentsconfiguration'     => 'Конфигурация цветовых компонентов',
'exif-compressedbitsperpixel'      => 'Глубина цвета после сжатия',
'exif-pixelydimension'             => 'Полная высота изображения',
'exif-pixelxdimension'             => 'Полная ширина изображения',
'exif-makernote'                   => 'Дополнительные данные производителя',
'exif-usercomment'                 => 'Дополнительный комментарий',
'exif-relatedsoundfile'            => 'Файл звукового комментария',
'exif-datetimeoriginal'            => 'Оригинальные дата и время',
'exif-datetimedigitized'           => 'Дата и время оцифровки',
'exif-subsectime'                  => 'Доли секунд времени изменения файла',
'exif-subsectimeoriginal'          => 'Доли секунд оригинального времени',
'exif-subsectimedigitized'         => 'Доли секунд времени оцифровки',
'exif-exposuretime'                => 'Время экспозиции',
'exif-exposuretime-format'         => '$1 с ($2)',
'exif-fnumber'                     => 'Число диафрагмы',
'exif-exposureprogram'             => 'Программа экспозиции',
'exif-spectralsensitivity'         => 'Спектральная чувствительность',
'exif-isospeedratings'             => 'Светочувствительность ISO',
'exif-oecf'                        => 'OECF (коэффициент оптоэлектрического преобразования)',
'exif-shutterspeedvalue'           => 'Выдержка',
'exif-aperturevalue'               => 'Диафрагма',
'exif-brightnessvalue'             => 'Яркость',
'exif-exposurebiasvalue'           => 'Компенсация экспозиции',
'exif-maxaperturevalue'            => 'Минимальное число диафрагмы',
'exif-subjectdistance'             => 'Расстояние до объекта',
'exif-meteringmode'                => 'Режим замера экспозиции',
'exif-lightsource'                 => 'Источник света',
'exif-flash'                       => 'Статус вспышки',
'exif-focallength'                 => 'Фокусное расстояние',
'exif-focallength-format'          => '$1 мм',
'exif-subjectarea'                 => 'Положение и площадь объекта съёмки',
'exif-flashenergy'                 => 'Энергия вспышки',
'exif-spatialfrequencyresponse'    => 'Пространственная частотная характеристика',
'exif-focalplanexresolution'       => 'Разрешение по X в фокальной плоскости',
'exif-focalplaneyresolution'       => 'Разрешение по Y в фокальной плоскости',
'exif-focalplaneresolutionunit'    => 'Единица измерения разрешения в фокальной плоскости',
'exif-subjectlocation'             => 'Положение объекта относительно левого верхнего угла',
'exif-exposureindex'               => 'Индекс экспозиции',
'exif-sensingmethod'               => 'Тип сенсора',
'exif-filesource'                  => 'Источник файла',
'exif-scenetype'                   => 'Тип сцены',
'exif-cfapattern'                  => 'Тип цветового фильтра',
'exif-customrendered'              => 'Дополнительная обработка',
'exif-exposuremode'                => 'Режим выбора экспозиции',
'exif-whitebalance'                => 'Баланс белого',
'exif-digitalzoomratio'            => 'Коэффициент цифрового увеличения (цифровой зум)',
'exif-focallengthin35mmfilm'       => 'Эквивалентное фокусное расстояние (для 35 мм плёнки)',
'exif-scenecapturetype'            => 'Тип сцены при съёмке',
'exif-gaincontrol'                 => 'Повышение яркости',
'exif-contrast'                    => 'Контрастность',
'exif-saturation'                  => 'Насыщенность',
'exif-sharpness'                   => 'Резкость',
'exif-devicesettingdescription'    => 'Описание предустановок камеры',
'exif-subjectdistancerange'        => 'Расстояние до объекта съёмки',
'exif-imageuniqueid'               => 'Номер изображения (ID)',
'exif-gpsversionid'                => 'Версия блока GPS-информации',
'exif-gpslatituderef'              => 'Индекс широты',
'exif-gpslatitude'                 => 'Широта',
'exif-gpslongituderef'             => 'Индекс долготы',
'exif-gpslongitude'                => 'Долгота',
'exif-gpsaltituderef'              => 'Индекс высоты',
'exif-gpsaltitude'                 => 'Высота',
'exif-gpstimestamp'                => 'Точное время по UTC',
'exif-gpssatellites'               => 'Описание использованных спутников',
'exif-gpsstatus'                   => 'Статус приёмника в момент съёмки',
'exif-gpsmeasuremode'              => 'Метод измерения положения',
'exif-gpsdop'                      => 'Точность измерения',
'exif-gpsspeedref'                 => 'Единицы измерения скорости',
'exif-gpsspeed'                    => 'Скорость движения',
'exif-gpstrackref'                 => 'Тип азимута приёмника GPS (истинный, магнитный)',
'exif-gpstrack'                    => 'Азимут приёмника GPS',
'exif-gpsimgdirectionref'          => 'Тип азимута изображения (истинный, магнитный)',
'exif-gpsimgdirection'             => 'Азимут изображения',
'exif-gpsmapdatum'                 => 'Использованная геодезическая система координат',
'exif-gpsdestlatituderef'          => 'Индекс долготы объекта',
'exif-gpsdestlatitude'             => 'Долгота объекта',
'exif-gpsdestlongituderef'         => 'Индекс широты объекта',
'exif-gpsdestlongitude'            => 'Широта объекта',
'exif-gpsdestbearingref'           => 'Тип пеленга объекта (истинный, магнитный)',
'exif-gpsdestbearing'              => 'Пеленг объекта',
'exif-gpsdestdistanceref'          => 'Единицы измерения расстояния',
'exif-gpsdestdistance'             => 'Расстояние',
'exif-gpsprocessingmethod'         => 'Метод вычисления положения',
'exif-gpsareainformation'          => 'Название области GPS',
'exif-gpsdatestamp'                => 'Дата',
'exif-gpsdifferential'             => 'Дифференциальная поправка',

# EXIF attributes
'exif-compression-1' => 'Несжатый',

'exif-unknowndate' => 'Неизвестная дата',

'exif-orientation-1' => 'Нормальная',
'exif-orientation-2' => 'Отражено по горизонтали',
'exif-orientation-3' => 'Повёрнуто на 180°',
'exif-orientation-4' => 'Отражено по вертикали',
'exif-orientation-5' => 'Повёрнуто на 90° против часовой стрелки и отражено по вертикали',
'exif-orientation-6' => 'Повёрнуто на 90° по часовой стрелке',
'exif-orientation-7' => 'Повёрнуто на 90° по часовой стрелке и отражено по вертикали',
'exif-orientation-8' => 'Повёрнуто на 90° против часовой стрелки',

'exif-planarconfiguration-1' => 'формат «chunky»',
'exif-planarconfiguration-2' => 'формат «planar»',

'exif-xyresolution-i' => '$1 точек на дюйм',
'exif-xyresolution-c' => '$1 точек на сантиметр',

'exif-componentsconfiguration-0' => 'не существует',

'exif-exposureprogram-0' => 'Неизвестно',
'exif-exposureprogram-1' => 'Ручной режим',
'exif-exposureprogram-2' => 'Программный режим (нормальный)',
'exif-exposureprogram-3' => 'Приоритет диафрагмы',
'exif-exposureprogram-4' => 'Приоритет выдержки',
'exif-exposureprogram-5' => 'Художественная программа (на основе нужной глубины резкости)',
'exif-exposureprogram-6' => 'Спортивный режим (с минимальной выдержкой)',
'exif-exposureprogram-7' => 'Портретный режим (для снимков на близком расстоянии, с фоном не в фокусе)',
'exif-exposureprogram-8' => 'Пейзажный режим (для пейзажных снимков, с фоном в фокусе)',

'exif-subjectdistance-value' => '$1 метров',

'exif-meteringmode-0'   => 'Неизвестно',
'exif-meteringmode-1'   => 'Средний',
'exif-meteringmode-2'   => 'Центровзвешенный',
'exif-meteringmode-3'   => 'Точечный',
'exif-meteringmode-4'   => 'Мультиточечный',
'exif-meteringmode-5'   => 'Матричный',
'exif-meteringmode-6'   => 'Частичный',
'exif-meteringmode-255' => 'Другой',

'exif-lightsource-0'   => 'Неизвестно',
'exif-lightsource-1'   => 'Дневной свет',
'exif-lightsource-2'   => 'Лампа дневного света',
'exif-lightsource-3'   => 'Лампа накаливания',
'exif-lightsource-4'   => 'Вспышка',
'exif-lightsource-9'   => 'Хорошая погода',
'exif-lightsource-10'  => 'Облачно',
'exif-lightsource-11'  => 'Тень',
'exif-lightsource-12'  => 'Лампа дневного света тип D (5700 − 7100K)',
'exif-lightsource-13'  => 'Лампа дневного света тип N (4600 − 5400K)',
'exif-lightsource-14'  => 'Лампа дневного света тип W (3900 − 4500K)',
'exif-lightsource-15'  => 'Лампа дневного света тип WW (3200 − 3700K)',
'exif-lightsource-17'  => 'Стандартный источник света типа A',
'exif-lightsource-18'  => 'Стандартный источник света типа B',
'exif-lightsource-19'  => 'Стандартный источник света типа C',
'exif-lightsource-24'  => 'Студийная лампа стандарта ISO',
'exif-lightsource-255' => 'Другой источник света',

# Flash modes
'exif-flash-fired-0'    => 'Вспышка не срабатывала',
'exif-flash-fired-1'    => 'Сработала вспышка',
'exif-flash-return-0'   => 'нет режима предварительной вспышки',
'exif-flash-return-2'   => 'обратный импульс предварительной вспышки не получен',
'exif-flash-return-3'   => 'получен обратный импульс предварительной вспышки',
'exif-flash-mode-1'     => 'импульс заполняющей вспышки',
'exif-flash-mode-2'     => 'подавление заполняющей вспышки',
'exif-flash-mode-3'     => 'автоматический режим',
'exif-flash-function-1' => 'Нет вспышки',
'exif-flash-redeye-1'   => 'режим устранения эффекта красных глаз',

'exif-focalplaneresolutionunit-2' => 'дюймов',

'exif-sensingmethod-1' => 'Неопределённый',
'exif-sensingmethod-2' => 'Однокристальный матричный цветной сенсор',
'exif-sensingmethod-3' => 'Цветной сенсор с двумя матрицами',
'exif-sensingmethod-4' => 'Цветной сенсор с тремя матрицами',
'exif-sensingmethod-5' => 'Матричный сенсор с последовательным измерением цвета',
'exif-sensingmethod-7' => 'Трёхцветный линейный сенсор',
'exif-sensingmethod-8' => 'Линейный сенсор с последовательным измерением цвета',

'exif-filesource-3' => 'Цифровой фотоаппарат',

'exif-scenetype-1' => 'Изображение сфотографировано напрямую',

'exif-customrendered-0' => 'Не производилась',
'exif-customrendered-1' => 'Нестандартная обработка',

'exif-exposuremode-0' => 'Автоматическая экспозиция',
'exif-exposuremode-1' => 'Ручная установка экспозиции',
'exif-exposuremode-2' => 'Брэкетинг',

'exif-whitebalance-0' => 'Автоматический баланс белого',
'exif-whitebalance-1' => 'Ручная установка баланса белого',

'exif-scenecapturetype-0' => 'Стандартный',
'exif-scenecapturetype-1' => 'Ландшафт',
'exif-scenecapturetype-2' => 'Портрет',
'exif-scenecapturetype-3' => 'Ночная съёмка',

'exif-gaincontrol-0' => 'Нет',
'exif-gaincontrol-1' => 'Небольшое увеличение',
'exif-gaincontrol-2' => 'Большое увеличение',
'exif-gaincontrol-3' => 'Небольшое уменьшение',
'exif-gaincontrol-4' => 'Сильное уменьшение',

'exif-contrast-0' => 'Нормальная',
'exif-contrast-1' => 'Мягкое повышение',
'exif-contrast-2' => 'Сильное повышение',

'exif-saturation-0' => 'Нормальная',
'exif-saturation-1' => 'Небольшая насыщенность',
'exif-saturation-2' => 'Большая насыщенность',

'exif-sharpness-0' => 'Нормальная',
'exif-sharpness-1' => 'Мягкое повышение',
'exif-sharpness-2' => 'Сильное повышение',

'exif-subjectdistancerange-0' => 'Неизвестно',
'exif-subjectdistancerange-1' => 'Макросъёмка',
'exif-subjectdistancerange-2' => 'Съёмка с близкого расстояния',
'exif-subjectdistancerange-3' => 'Съёмка издалека',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'северной широты',
'exif-gpslatitude-s' => 'южной широты',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'восточной долготы',
'exif-gpslongitude-w' => 'западной долготы',

'exif-gpsstatus-a' => 'Измерение не закончено',
'exif-gpsstatus-v' => 'Готов к передаче данных',

'exif-gpsmeasuremode-2' => 'Измерение 2-х координат',
'exif-gpsmeasuremode-3' => 'Измерение 3-х координат',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'км/час',
'exif-gpsspeed-m' => 'миль/час',
'exif-gpsspeed-n' => 'узлов',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'истинный',
'exif-gpsdirection-m' => 'магнитный',

# External editor support
'edit-externally'      => 'Редактировать этот файл, используя внешнюю программу',
'edit-externally-help' => '(подробнее см. в [http://www.mediawiki.org/wiki/Manual:External_editors руководстве по установке])',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'все',
'imagelistall'     => 'все',
'watchlistall2'    => 'все',
'namespacesall'    => 'все',
'monthsall'        => 'все',
'limitall'         => 'все',

# E-mail address confirmation
'confirmemail'              => 'Подтверждение адреса электронной почты',
'confirmemail_noemail'      => 'Вы не задали адрес электронной почты в своих [[Special:Preferences|настройках]], либо он некорректен.',
'confirmemail_text'         => 'Вики-движок требует подтверждения адреса электронной почты перед тем, как начать с ним работать.
Нажмите на кнопку, чтобы на указанный адрес было отправлено письмо, содержащее ссылку на специальную страницу, после открытия которой в браузере адрес электронной почты будет считаться подтверждённым.',
'confirmemail_pending'      => 'Письмо с кодом подтверждения уже было отправлено.
Если вы недавно создали учётную запись, то, вероятно,
вам следует подождать несколько минут, пока письмо придёт, перед тем, как запросить код ещё раз.',
'confirmemail_send'         => 'Отправить письмо с запросом на подтверждение',
'confirmemail_sent'         => 'Письмо с запросом на подтверждение отправлено.',
'confirmemail_oncreate'     => 'Письмо с кодом подтверждения было отправлено на указанный вами почтовый ящик.
Данный код не требуется для входа в систему, однако вы должны указать его,
прежде чем будет разрешено использование возможностей электронной почты в этом проекте.',
'confirmemail_sendfailed'   => '{{SITENAME}} не может отправить письмо с запросом на подтверждение.
Пожалуйста, проверьте правильность адреса электронной почты.

Ответ сервера: $1',
'confirmemail_invalid'      => 'Неправильный код подтверждения или срок действия кода истёк.',
'confirmemail_needlogin'    => 'Вы должны $1 для подтверждения вашего адреса электронной почты.',
'confirmemail_success'      => 'Ваш адрес электронной почты подтверждён.',
'confirmemail_loggedin'     => 'Ваш адрес электронной почты подтверждён.',
'confirmemail_error'        => 'Во время процедуры подтверждения адреса электронной почты произошла ошибка.',
'confirmemail_subject'      => '{{SITENAME}}:Запрос на подтверждение адреса электронной почты',
'confirmemail_body'         => 'Кто-то (возможно вы) с IP-адресом $1 зарегистрировал
на сервере проекта {{SITENAME}} учётную запись «$2»,
указав этот адрес электронной почты.

Чтобы подтвердить, что вы эта учётная запись действительно
принадлежит вам и включить возможность отправки электронной почты
с сайта {{SITENAME}}, откройте приведённую ниже ссылку в браузере.

$3

Если вы *не* регистрировали подобной учётной записи, то перейдите
по следующей ссылке, чтобы отменить подтверждение адреса

$5

Код подтверждения действителен до $4.',
'confirmemail_body_changed' => 'Кто-то (возможно вы) с IP-адресом $1
указал данный адрес электронной почты в качестве нового для учётной записи «$2» в проекте {{SITENAME}}.

Чтобы подтвердить, что эта учётная запись действительно принадлежит вам,
и включить возможность отправки писем с сайта {{SITENAME}}, откройте приведённую ниже ссылку в браузере.

$3

Если данная учётная запись *не* относится к вам, то перейдите по следующей ссылке,
чтобы отменить подтверждение адреса

$5

Код подтверждения действителен до $4.',
'confirmemail_invalidated'  => 'Подтверждение адреса электронной почты отменено',
'invalidateemail'           => 'Отменить подтверждение адреса эл. почты',

# Scary transclusion
'scarytranscludedisabled' => '[«Interwiki transcluding» отключён]',
'scarytranscludefailed'   => '[Ошибка обращения к шаблону $1]',
'scarytranscludetoolong'  => '[Слишком длинный URL]',

# Trackbacks
'trackbackbox'      => 'Trackback для этой статьи:<br />
$1',
'trackbackremove'   => '([$1 удалить])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => 'Trackback был удалён.',

# Delete conflict
'deletedwhileediting' => "'''Внимание'''. Эта страница была удалена после того, как вы начали её править!",
'confirmrecreate'     => "Участник [[User:$1|$1]] ([[User talk:$1|обсуждение]]) удалил эту страницу после того, как вы начали её редактировать, причина удаления:
: ''$2''
Пожалуйста, подтвердите, что вы хотите восстановить эту страницу.",
'recreate'            => 'Создать заново',

'unit-pixel' => ' пикс.',

# action=purge
'confirm_purge_button' => 'OK',
'confirm-purge-top'    => 'Очистить кеш этой страницы?',
'confirm-purge-bottom' => 'После очистки кеша страницы будет показана её последняя версия.',

# Multipage image navigation
'imgmultipageprev' => '← предыдущая страница',
'imgmultipagenext' => 'следующая страница →',
'imgmultigo'       => 'Перейти!',
'imgmultigoto'     => 'Перейти на страницу $1',

# Table pager
'ascending_abbrev'         => 'возр',
'descending_abbrev'        => 'убыв',
'table_pager_next'         => 'Следующая страница',
'table_pager_prev'         => 'Предыдущая страница',
'table_pager_first'        => 'Первая страница',
'table_pager_last'         => 'Последняя страница',
'table_pager_limit'        => 'Показать $1 элементов на странице',
'table_pager_limit_label'  => 'Записей на страницу:',
'table_pager_limit_submit' => 'Выполнить',
'table_pager_empty'        => 'Не найдено',

# Auto-summaries
'autosumm-blank'   => 'Удалено содержимое страницы',
'autosumm-replace' => 'Содержимое страницы заменено на «$1»',
'autoredircomment' => 'Перенаправление на [[$1]]',
'autosumm-new'     => 'Новая страница: «$1»',

# Size units
'size-bytes'     => '$1 байт',
'size-kilobytes' => '$1 КБ',
'size-megabytes' => '$1 МБ',
'size-gigabytes' => '$1 ГБ',

# Live preview
'livepreview-loading' => 'Загрузка…',
'livepreview-ready'   => 'Загрузка… Готово!',
'livepreview-failed'  => 'Не удалось использовать быстрый предпросмотр. Попробуйте воспользоваться обычным предпросмотром.',
'livepreview-error'   => 'Не удалось установить соединение: $1 «$2». Попробуйте воспользоваться обычным предпросмотром.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Изменения, сделанные менее чем $1 {{PLURAL:$1|секунду|секунды|секунд}} назад, могут быть не показаны в этом списке.',
'lag-warn-high'   => 'Из-за большого отставания в синхронизации серверов баз данных изменения, сделанные менее чем $1 {{PLURAL:$1|секунду|секунды|секунд}} назад, могут быть не показаны в этом списке.',

# Watchlist editor
'watchlistedit-numitems'       => 'Ваш список наблюдения содержит {{PLURAL:$1|$1 запись|$1 записи|$1 записей}}, исключая страницы обсуждений.',
'watchlistedit-noitems'        => 'Ваш список наблюдения не содержит записей.',
'watchlistedit-normal-title'   => 'Изменение списка наблюдения',
'watchlistedit-normal-legend'  => 'Удаление записей из списка наблюдения',
'watchlistedit-normal-explain' => 'Ниже перечислены страницы, находящиеся в вашем списке наблюдения.
Для удаления записей отметьте соответствующие позиции и нажмите кнопку «{{int:Watchlistedit-normal-submit}}».
Вы также можете [[Special:Watchlist/raw|править список как текст]].',
'watchlistedit-normal-submit'  => 'Удалить записи',
'watchlistedit-normal-done'    => '{{PLURAL:$1|$1 запись была удалена|$1 записи были удалены|$1 записей были удалены}} из вашего списка наблюдения:',
'watchlistedit-raw-title'      => 'Изменение «сырого» списка наблюдения',
'watchlistedit-raw-legend'     => 'Изменение «сырого» списка наблюдения',
'watchlistedit-raw-explain'    => 'Ниже перечислены страницы, находящиеся в вашем списке наблюдения. Вы можете изменять этот список, добавляя и удаляя из него строки с названиями, по одному названию на строке.
После завершения правок нажмите кнопку «{{int:Watchlistedit-raw-submit}}».
Вы также можете [[Special:Watchlist/edit|использовать стандартный редактор]].',
'watchlistedit-raw-titles'     => 'Записи:',
'watchlistedit-raw-submit'     => 'Сохранить список',
'watchlistedit-raw-done'       => 'Ваш список наблюдения сохранён.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|$1 запись была добавлена|$1 записи были добавлены|$1 записей были добавлены}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|$1 запись была удалена|$1 записи были удалены|$1 записей были удалены}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Изменения на страницах из списка',
'watchlisttools-edit' => 'Смотреть/править список',
'watchlisttools-raw'  => 'Править как текст',

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
'iranian-calendar-m10' => 'Дей',
'iranian-calendar-m11' => 'Бахман',
'iranian-calendar-m12' => 'Эсфанд',

# Hebrew month names
'hebrew-calendar-m1'      => 'Тишрей',
'hebrew-calendar-m2'      => 'Хешван',
'hebrew-calendar-m4'      => 'Тевет',
'hebrew-calendar-m5'      => 'Шват',
'hebrew-calendar-m6'      => 'Адар',
'hebrew-calendar-m6a'     => 'Адар I',
'hebrew-calendar-m6b'     => 'Адар II',
'hebrew-calendar-m7'      => 'Нисан',
'hebrew-calendar-m8'      => 'Ияр',
'hebrew-calendar-m9'      => 'Сиван',
'hebrew-calendar-m10'     => 'Таммуз',
'hebrew-calendar-m11'     => 'Ав',
'hebrew-calendar-m12'     => 'Элул',
'hebrew-calendar-m1-gen'  => 'Тишрея',
'hebrew-calendar-m2-gen'  => 'Хешвана',
'hebrew-calendar-m3-gen'  => 'Кислева',
'hebrew-calendar-m4-gen'  => 'Тевета',
'hebrew-calendar-m5-gen'  => 'Швата',
'hebrew-calendar-m6-gen'  => 'Адара',
'hebrew-calendar-m6a-gen' => 'Адара I',
'hebrew-calendar-m6b-gen' => 'Адара II',
'hebrew-calendar-m7-gen'  => 'Нисана',
'hebrew-calendar-m8-gen'  => 'Ияра',
'hebrew-calendar-m9-gen'  => 'Сивана',
'hebrew-calendar-m10-gen' => 'Таммуза',
'hebrew-calendar-m11-gen' => 'Ава',
'hebrew-calendar-m12-gen' => 'Элула',

# Core parser functions
'unknown_extension_tag' => 'Неизвестный тег дополнения «$1»',
'duplicate-defaultsort' => 'Внимание. Ключ сортировки по умолчанию «$2» переопределяет прежний ключ сортировки по умолчанию «$1».',

# Special:Version
'version'                          => 'Версия MediaWiki',
'version-extensions'               => 'Установленные расширения',
'version-specialpages'             => 'Служебные страницы',
'version-parserhooks'              => 'Перехватчики синтаксического анализатора',
'version-variables'                => 'Переменные',
'version-other'                    => 'Иное',
'version-mediahandlers'            => 'Обработчики медиа',
'version-hooks'                    => 'Перехватчики',
'version-extension-functions'      => 'Функции расширений',
'version-parser-extensiontags'     => 'Теги расширений синтаксического анализатора',
'version-parser-function-hooks'    => 'Перехватчики функций синтаксического анализатора',
'version-skin-extension-functions' => 'Функции расширений тем оформления',
'version-hook-name'                => 'Имя перехватчика',
'version-hook-subscribedby'        => 'Подписан на',
'version-version'                  => '(Версия $1)',
'version-license'                  => 'Лицензия',
'version-software'                 => 'Установленное программное обеспечение',
'version-software-product'         => 'Продукт',
'version-software-version'         => 'Версия',

# Special:FilePath
'filepath'         => 'Путь к файлу',
'filepath-page'    => 'Файл:',
'filepath-submit'  => 'Перейти',
'filepath-summary' => 'Данная служебная страница возвращает полный путь к файлу в том виде, в котором он хранится на диске.

Введите имя файла без префикса <code>{{ns:file}}:</code>.',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Поиск одинаковых файлов',
'fileduplicatesearch-summary'  => 'Поиск одинаковых файлов по их хэш-коду.

Введите имя файла без приставки «{{ns:file}}:».',
'fileduplicatesearch-legend'   => 'Поиск дубликатов',
'fileduplicatesearch-filename' => 'Имя файла:',
'fileduplicatesearch-submit'   => 'Найти',
'fileduplicatesearch-info'     => '$1 × $2 пикселов<br />Размер файла: $3<br />MIME-тип: $4',
'fileduplicatesearch-result-1' => 'Файл «$1» не имеет идентичных дубликатов.',
'fileduplicatesearch-result-n' => 'Файл «$1» имеет $2 {{PLURAL:$2|идентичный дубликат|идентичных дубликата|идентичных дубликатов}}.',

# Special:SpecialPages
'specialpages'                   => 'Спецстраницы',
'specialpages-note'              => '----
* Обычные служебные страницы.
* <strong class="mw-specialpagerestricted">Служебные страницы с ограниченным доступом.</strong>',
'specialpages-group-maintenance' => 'Отчёты технического обслуживания',
'specialpages-group-other'       => 'Другие служебные страницы',
'specialpages-group-login'       => 'Представиться / Зарегистрироваться',
'specialpages-group-changes'     => 'Свежие правки и журналы',
'specialpages-group-media'       => 'Отчёты о медиа-материалах и загрузка',
'specialpages-group-users'       => 'Участники и права',
'specialpages-group-highuse'     => 'Интенсивно используемые страницы',
'specialpages-group-pages'       => 'Списки страниц',
'specialpages-group-pagetools'   => 'Инструменты для страниц',
'specialpages-group-wiki'        => 'Вики-данные и инструменты',
'specialpages-group-redirects'   => 'Перенаправляющие служебные страницы',
'specialpages-group-spam'        => 'Инструменты против спама',

# Special:BlankPage
'blankpage'              => 'Пустая страница',
'intentionallyblankpage' => 'Эта страница намеренно оставлена пустой',

# External image whitelist
'external_image_whitelist' => ' #Оставьте эту строчку такой, как она есть<pre>
#Разместите здесь фрагменты регулярных выражений (ту часть, что находится между //)
#они будут соотнесены с URL внешних изображений.
#Подходящие будут показаны как изображения, остальные будут показаны как ссылки на изображения.
#Строки, начинающиеся с # считаются комментариями.
#Строки чувствительны к регистру

#Размещайте фрагменты регулярных выражений над этой строчкой. Оставьте эту строчку такой, как она есть.</pre>',

# Special:Tags
'tags'                    => 'Действующие метки изменений',
'tag-filter'              => 'Фильтр [[Special:Tags|меток]]:',
'tag-filter-submit'       => 'Отфильтровать',
'tags-title'              => 'Метки',
'tags-intro'              => 'На этой странице приведён список меток, которыми программное обеспечение отмечает правки, а также значения этих меток.',
'tags-tag'                => 'Имя метки',
'tags-display-header'     => 'Отображение в списках изменений',
'tags-description-header' => 'Полное описание значения',
'tags-hitcount-header'    => 'Отмеченные правки',
'tags-edit'               => 'править',
'tags-hitcount'           => '$1 {{PLURAL:$1|изменение|изменения|изменений}}',

# Special:ComparePages
'comparepages'     => 'Сравнение страниц',
'compare-selector' => 'Сравнение версий страниц',
'compare-page1'    => 'Первая страница',
'compare-page2'    => 'Вторая страница',
'compare-rev1'     => 'Первая версия',
'compare-rev2'     => 'Вторая версия',
'compare-submit'   => 'Сравнить',

# Database error messages
'dberr-header'      => 'Эта вики испытывает затруднения',
'dberr-problems'    => 'Извините! На данном сайте возникли технические трудности.',
'dberr-again'       => 'Попробуйте обновить страницу через несколько минут.',
'dberr-info'        => '(невозможно соединиться с сервером баз данных: $1)',
'dberr-usegoogle'   => 'Пока вы можете попробовать поискать с помощью Google.',
'dberr-outofdate'   => 'Но имейте в виду, что его индекс может оказаться устаревшим.',
'dberr-cachederror' => 'Ниже представлена закэшированная версия запрашиваемой страницы, возможно, она не отражает последних изменений.',

# HTML forms
'htmlform-invalid-input'       => 'Часть введённых вами данных вызвала проблемы',
'htmlform-select-badoption'    => 'Указанное вами значение не является допустимым.',
'htmlform-int-invalid'         => 'Указанное вами значение не является целым числом.',
'htmlform-float-invalid'       => 'Указанное вами значение не является числом.',
'htmlform-int-toolow'          => 'Указанное вами значение ниже минимального — $1',
'htmlform-int-toohigh'         => 'указанное вами значение выше максимального — $1',
'htmlform-required'            => 'Это значение необходимо',
'htmlform-submit'              => 'Отправить',
'htmlform-reset'               => 'Отменить изменения',
'htmlform-selectorother-other' => 'Иное',

);

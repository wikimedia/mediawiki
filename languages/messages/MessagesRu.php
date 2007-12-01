<?php
/** Russian (русский язык)
  *
  * Based on MessagesEn.php revision 27343, (2007-11-09)
  * and ru.wikipedia MediaWiki namespace (2007-11-09)
  *
  * Maintainer: Alexander Sigachov (alexander.sigachov@gmail.com)
  * 
  * Изменения сделанные в этом файле будут потеряны при обновлении MediaWiki.
  *
  * Если необходимо внести изменения в перевод отдельных строк интерфейса,
  * сделайте это посредством редактирования страниц вида «MediaWiki:*».
  * Их список можно найти на странице «Special:Allmessages».
  *
  */

$separatorTransformTable = array(
	',' => "\xc2\xa0", # nbsp
	'.' => ','
);

$fallback8bitEncoding = 'windows-1251';
$linkPrefixExtension = true;

$namespaceNames = array(
	NS_MEDIA            => 'Медиа',
	NS_SPECIAL          => 'Служебная',
	NS_MAIN             => '',
	NS_TALK             => 'Обсуждение',
	NS_USER             => 'Участник',
	NS_USER_TALK        => 'Обсуждение_участника', 
	#NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => 'Обсуждение_{{grammar:genitive|$1}}',
	NS_IMAGE            => 'Изображение',
	NS_IMAGE_TALK       => 'Обсуждение_изображения',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Обсуждение_MediaWiki',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Обсуждение_шаблона',
	NS_HELP             => 'Справка',
	NS_HELP_TALK        => 'Обсуждение_справки',
	NS_CATEGORY         => 'Категория',
	NS_CATEGORY_TALK    => 'Обсуждение_категории',
);

$skinNames = array(
	'standard' => 'Стандартное',
	'nostalgia' => 'Ностальгия',
	'cologneblue' => 'Кёльнская тоска',
	'davinci' => 'Да Винчи',
	'myskin' => 'Своё',
	'chick' => 'Цыпа',
	'simple' => 'Простое',
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
	'Поиск по библиотекам «Сигла»' => 'http://www.sigla.ru/results.jsp?f=7&t=3&v0=5030030980&f=1003&t=1&v1=&f=4&t=2&v2=&f=21&t=3&v3=&f=1016&t=3&v4=&f=1016&t=3&v5=&bf=4&b=&d=0&ys=&ye=&lng=&ft=&mt=&dt=&vol=&pt=&iss=&ps=&pe=&tr=&tro=&cc=&i=1&v=tagged&s=0&ss=0&st=0&i18n=ru&rlf=&psz=20&bs=20&ce=hJfuypee8JzzufeGmImYYIpZKRJeeOeeWGJIZRrRRrdmtdeee88NJJJJpeeefTJ3peKJJ3UWWPtzzzzzzzzzzzzzzzzzbzzvzzpy5zzjzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzztzzzzzzzbzzzzzzzzzzzzzzzzzzzzzzzzzzzvzzzzzzyeyTjkDnyHzTuueKZePz9decyzzLzzzL*.c8.NzrGJJvufeeeeeJheeyzjeeeeJh*peeeeKJJJJJJJJJJmjHvOJJJJJJJJJfeeeieeeeSJJJJJSJJJ3TeIJJJJ3..E.UEAcyhxD.eeeeeuzzzLJJJJ5.e8JJJheeeeeeeeeeeeyeeK3JJJJJJJJ*s7defeeeeeeeeeeeeeeeeeeeeeeeeeSJJJJJJJJZIJJzzz1..6LJJJJJJtJJZ4....EK*&debug=false',
	'Findbook.ru' => 'http://findbook.ru/search/d0?ptype=4&pvalue=$1',
	'Яндекс.Маркет' => 'http://market.yandex.ru/search.xml?text=$1',
	'ОЗОН' => 'http://www.ozon.ru/?context=advsearch_book&isbn=$1',
	'Books.Ru' => 'http://www.books.ru/shop/search/advanced?as%5Btype%5D=books&as%5Bname%5D=&as%5Bisbn%5D=$1&as%5Bauthor%5D=&as%5Bmaker%5D=&as%5Bcontents%5D=&as%5Binfo%5D=&as%5Bdate_after%5D=&as%5Bdate_before%5D=&as%5Bprice_less%5D=&as%5Bprice_more%5D=&as%5Bstrict%5D=%E4%E0&as%5Bsub%5D=%E8%F1%EA%E0%F2%FC&x=22&y=8',
	'Amazon.com' => 'http://www.amazon.com/exec/obidos/ISBN=$1'
);


# Note to translators:
#   Please include the English words as synonyms.  This allows people
#   from other wikis to contribute more easily.
#
$magicWords = array(
#   ID                                 CASE  SYNONYMS
	'redirect'               => array( 0,    '#REDIRECT', '#ПЕРЕНАПРАВЛЕНИЕ', '#ПЕРЕНАПР'),
	'notoc'                  => array( 0,    '__NOTOC__', '__БЕЗ_ОГЛ__'),
	'nogallery'              => array( 0,    '__NOGALLERY__', '__БЕЗ_ГАЛЕРЕИ__'),
	'forcetoc'               => array( 0,    '__FORCETOC__',  '__ОБЯЗ_ОГЛ__'),
	'toc'                    => array( 0,    '__TOC__', '__ОГЛ__'),
	'noeditsection'          => array( 0,    '__NOEDITSECTION__', '__БЕЗ_РЕДАКТИРОВАНИЯ_РАЗДЕЛА__'),
	'currentmonth'           => array( 1,    'CURRENTMONTH', 'ТЕКУЩИЙ_МЕСЯЦ'),
	'currentmonthname'       => array( 1,    'CURRENTMONTHNAME','НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА'),
	'currentmonthnamegen'    => array( 1,    'CURRENTMONTHNAMEGEN','НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА_РОД'),
	'currentmonthabbrev'     => array( 1,    'CURRENTMONTHABBREV', 'НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА_АБР'),
	'currentday'             => array( 1,    'CURRENTDAY','ТЕКУЩИЙ_ДЕНЬ'),
	'currentday2'            => array( 1,    'CURRENTDAY2','ТЕКУЩИЙ_ДЕНЬ_2'),
	'currentdayname'         => array( 1,    'CURRENTDAYNAME','НАЗВАНИЕ_ТЕКУЩЕГО_ДНЯ'),
	'currentyear'            => array( 1,    'CURRENTYEAR','ТЕКУЩИЙ_ГОД'),
	'currenttime'            => array( 1,    'CURRENTTIME','ТЕКУЩЕЕ_ВРЕМЯ'),
	'currenthour'            => array( 1,    'CURRENTHOUR' , 'ТЕКУЩИЙ_ЧАС' ),
	'localmonth'             => array( 1,    'LOCALMONTH', 'МЕСТНЫЙ_МЕСЯЦ' ),
	'localmonthname'         => array( 1,    'LOCALMONTHNAME', 'НАЗВАНИЕ_МЕСТНОГО_МЕСЯЦА'),
	'localmonthnamegen'      => array( 1,    'LOCALMONTHNAMEGEN', 'НАЗВАНИЕ_МЕСТНОГО_МЕСЯЦА_РОД'),
	'localmonthabbrev'       => array( 1,    'LOCALMONTHABBREV', 'НАЗВАНИЕ_МЕСТНОГОМЕСЯЦА_АБР'),
	'localday'               => array( 1,    'LOCALDAY' , 'МЕСТНЫЙ_ДЕНЬ'),
	'localday2'              => array( 1,    'LOCALDAY2', 'МЕСТНЫЙ_ДЕНЬ_2'),
	'localdayname'           => array( 1,    'LOCALDAYNAME', 'НАЗВАНИЕ_МЕСТНОГО_ДНЯ'),
	'localyear'              => array( 1,    'LOCALYEAR', 'МЕСТНЫЙ_ГОД'),
	'localtime'              => array( 1,    'LOCALTIME', 'МЕСТНОЕ_ВРЕМЯ'),
	'localhour'              => array( 1,    'LOCALHOUR', 'МЕСТНЫЙ_ЧАС'),
	'numberofpages'          => array( 1,    'NUMBEROFPAGES', 'КОЛИЧЕСТВО_СТРАНИЦ'),
	'numberofarticles'       => array( 1,    'NUMBEROFARTICLES','КОЛИЧЕСТВО_СТАТЕЙ'),
	'numberoffiles'          => array( 1,    'NUMBEROFFILES', 'КОЛИЧЕСТВО_ФАЙЛОВ'),
	'numberofusers'          => array( 1,    'NUMBEROFUSERS', 'КОЛИЧЕСТВО_УЧАСТНИКОВ'),
	'numberofedits'          => array( 1,    'NUMBEROFEDITS', 'КОЛИЧЕСТВО_ПРАВОК'),
	'pagename'               => array( 1,    'PAGENAME','НАЗВАНИЕ_СТРАНИЦЫ'),
	'pagenamee'              => array( 1,    'PAGENAMEE','НАЗВАНИЕ_СТРАНИЦЫ_2'),
	'namespace'              => array( 1,    'NAMESPACE','ПРОСТРАНСТВО_ИМЁН'),
	'namespacee'             => array( 1,    'NAMESPACEE','ПРОСТРАНСТВО_ИМЁН_2'),
	'talkspace'              => array( 1,    'TALKSPACE', 'ПРОСТРАНСТВО_ОБСУЖДЕНИЙ'),
	'talkspacee'             => array( 1,    'TALKSPACEE', 'ПРОСТРАНСТВО_ОБСУЖДЕНИЙ_2'),
	'subjectspace'           => array( 1,    'SUBJECTSPACE', 'ARTICLESPACE', 'ПРОСТРАНСТВО_СТАТЕЙ' ),
	'subjectspacee'          => array( 1,    'SUBJECTSPACEE', 'ARTICLESPACEE', 'ПРОСТРАНСТВО_СТАТЕЙ_2' ),
	'fullpagename'           => array( 1,    'FULLPAGENAME', 'ПОЛНОЕ_НАЗВАНИЕ_СТРАНЦЫ' ),
	'fullpagenamee'          => array( 1,    'FULLPAGENAMEE', 'ПОЛНОЕ_НАЗВАНИЕ_СТРАНЦЫ_2' ),
	'subpagename'            => array( 1,    'SUBPAGENAME', 'НАЗВАНИЕ_ПОДСТРАНИЦЫ' ),
	'subpagenamee'           => array( 1,    'SUBPAGENAMEE', 'НАЗВАНИЕ_ПОДСТРАНИЦЫ_2'),
	'basepagename'           => array( 1,    'BASEPAGENAME', 'ОСНОВА_НАЗВАНИЯ_СТРАНИЦЫ'),
	'basepagenamee'          => array( 1,    'BASEPAGENAMEE', 'ОСНОВА_НАЗВАНИЯ_СТРАНИЦЫ_2'),
	'talkpagename'           => array( 1,    'TALKPAGENAME', 'НАЗВАНИЕ_СТРАНИЦЫ_ОБСУЖДЕНИЯ'),
	'talkpagenamee'          => array( 1,    'TALKPAGENAMEE', 'НАЗВАНИЕ_СТРАНИЦЫ_ОБСУЖДЕНИЯ_2'),
	'subjectpagename'        => array( 1,    'SUBJECTPAGENAME', 'ARTICLEPAGENAME', 'НАЗВАНИЕ_СТРАНИЦЫ_СТАТЬИ' ),
	'subjectpagenamee'       => array( 1,    'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE', 'НАЗВАНИЕ_СТРАНИЦЫ_СТАТЬИ_2' ),
	'msg'                    => array( 0,    'MSG:', 'СООБЩ:'),
	'subst'                  => array( 0,    'SUBST:','ПОДСТ:'),
	'msgnw'                  => array( 0,    'MSGNW:', 'СООБЩ_БЕЗ_ВИКИ:'),
	'img_thumbnail'          => array( 1,    'thumbnail', 'thumb', 'мини'),
	'img_manualthumb'        => array( 1,    'thumbnail=$1', 'thumb=$1', 'мини=$1'),
	'img_right'              => array( 1,    'right','справа'),
	'img_left'               => array( 1,    'left','слева'),
	'img_none'               => array( 1,    'none', 'без'),
	'img_width'              => array( 1,    '$1px','$1пкс'),
	'img_center'             => array( 1,    'center', 'centre','центр'),
	'img_framed'             => array( 1,    'framed', 'enframed', 'frame','обрамить'),
	'img_frameless'          => array( 1,    'frameless', 'безрамки'),
	'img_page'               => array( 1,    'page=$1', 'page $1', 'страница=$1', 'страница $1' ),
	'img_upright'            => array( 1,    'upright', 'upright=$1', 'upright $1', 'сверхусправа', 'сверхусправа=$1', 'сверхусправа $1' ),
	'img_border'             => array( 1,    'border', 'граница'),
	'int'                    => array( 0,    'INT:', 'ВНУТР:'),
	'sitename'               => array( 1,    'SITENAME','НАЗВАНИ_ЕСАЙТА'),
	'ns'                     => array( 0,    'NS:','ПИ:'),
	'localurl'               => array( 0,    'LOCALURL:', 'ЛОКАЛЬНЫЙ_АДРЕС:'),
	'localurle'              => array( 0,    'LOCALURLE:', 'ЛОКАЛЬНЫЙ_АДРЕС_2:'),
	'server'                 => array( 0,    'SERVER','СЕРВЕР'),
	'servername'             => array( 0,    'SERVERNAME', 'НАЗВАНИЕ_СЕРВЕРА'),
	'scriptpath'             => array( 0,    'SCRIPTPATH', 'ПУТЬ_К_СКРИПТУ'),
	'grammar'                => array( 0,    'GRAMMAR:', 'ПАДЕЖ:'),
	'notitleconvert'         => array( 0,    '__NOTITLECONVERT__', '__NOTC__', '__БЕЗ_ПРЕОБРАЗОВАНИЯ_ЗАГОЛОВКА__'),
	'nocontentconvert'       => array( 0,    '__NOCONTENTCONVERT__', '__NOCC__', '__БЕЗ_ПРЕОБРАЗОВАНИЯ_ТЕКСТА__'),
	'currentweek'            => array( 1,    'CURRENTWEEK','ТЕКУЩАЯ_НЕДЕЛЯ'),
	'currentdow'             => array( 1,    'CURRENTDOW','ТЕКУЩИЙ_ДЕНЬ_НЕДЕЛИ'),
	'localweek'              => array( 1,    'LOCALWEEK', 'МЕСТНАЯ_НЕДЕЛЯ' ),
	'localdow'               => array( 1,    'LOCALDOW', 'МЕСТНЫЙ_ДЕНЬ_НЕДЕЛИ' ),
	'revisionid'             => array( 1,    'REVISIONID', 'ИД_ВЕРСИИ'),
	'revisionday'            => array( 1,    'REVISIONDAY', 'ДЕНЬ_ВЕРСИИ' ),
	'revisionday2'           => array( 1,    'REVISIONDAY2' , 'ДЕНЬ_ВЕРСИИ_2'),
	'revisionmonth'          => array( 1,    'REVISIONMONTH' , 'МЕСЯЦ_ВЕРСИИ'),
	'revisionyear'           => array( 1,    'REVISIONYEAR' , 'ГОД_ВЕРСИИ'),
	'plural'                 => array( 0,    'PLURAL:', 'МНОЖЕСТВЕННОЕ_ЧИСЛО:' ),
	'fullurl'                => array( 0,    'FULLURL:', 'ПОЛНЫЙ_АДРЕС:' ),
	'fullurle'               => array( 0,    'FULLURLE:', 'ПОЛНЫЙ_АДРЕС_2:' ),
	'lcfirst'                => array( 0,    'LCFIRST:', 'ПЕРВАЯ_БУКВА_МАЛЕНЬКАЯ:' ),
	'ucfirst'                => array( 0,    'UCFIRST:' , 'ПЕРВАЯ_БУКВА_БОЛЬШАЯ:' ),
	'lc'                     => array( 0,    'LC:' , 'МАЛЕНЬКИМИ_БУКВАМИ:' ),
	'uc'                     => array( 0,    'UC:', 'БОЛЬШИМИ_БУКВАМИ:' ),
	'raw'                    => array( 0,    'RAW:', 'НЕОБРАБ:' ),
	'displaytitle'           => array( 1,    'DISPLAYTITLE' , 'ПОКАЗАТЬ_ЗАГОЛОВОК' ),
	'rawsuffix'              => array( 1,    'R' , 'Н' ),
	'newsectionlink'         => array( 1,    '__NEWSECTIONLINK__', '__ССЫЛКА_НА_НОВЫЙ_РАЗДЕЛ__' ),
	'currentversion'         => array( 1,    'CURRENTVERSION' , 'ТЕКУЩАЯ_ВЕРСИЯ' ),
	'urlencode'              => array( 0,    'URLENCODE:' , 'ЗАКОДИРОВАННЫЙ_АДРЕС:' ),
	'anchorencode'           => array( 0,    'ANCHORENCODE', 'КОДИРОВАТЬ_МЕТКУ'),
	'currenttimestamp'       => array( 1,    'CURRENTTIMESTAMP' , 'ОТМЕТКА_ТЕКУЩЕГО_ВРЕМЕНИ' ),
	'localtimestamp'         => array( 1,    'LOCALTIMESTAMP' , 'ОТМЕТКА_МЕСТНОГО_ВРЕМЕНИ'),
	'directionmark'          => array( 1,    'DIRECTIONMARK', 'DIRMARK' , 'НАПРАВЛЕНИЕ_ПИСЬМА' ),
	'language'               => array( 0,    '#LANGUAGE:' , '#ЯЗЫК:' ),
	'contentlanguage'        => array( 1,    'CONTENTLANGUAGE', 'CONTENTLANG', 'ЯЗЫК_СОДЕРЖАНИЯ' ),
	'pagesinnamespace'       => array( 1,    'PAGESINNAMESPACE:', 'PAGESINNS:' , 'СТРАНИЦ_В_ПРОСТРАНСТВЕ_ИМЁН:' ),
	'numberofadmins'         => array( 1,    'NUMBEROFADMINS', 'КОЛИЧЕСТВО_АДМИНИСТРАТОРОВ' ),
	'formatnum'              => array( 0,    'FORMATNUM', 'ФОРМАТИРОВАТЬ_ЧИСЛО' ),
	'padleft'                => array( 0,    'PADLEFT', 'ЗАПОЛНИТЬ_СЛЕВА'),
	'padright'               => array( 0,    'PADRIGHT', 'ЗАПОЛНИТЬ_СПРАВА'),
	'special'                => array( 0,    'special', 'служебная' ),
	'defaultsort'            => array( 1,    'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:', 'СОРТИРОВКА_ПО_УМОЛЧАНИЮ', 'КЛЮЧ_СОРТИРОВКИ'),
	'filepath'               => array( 0,    'FILEPATH:', 'ПУТЬ_К_ФАЙЛУ:'),
);

$linkTrail = '/^([a-zабвгдеёжзийклмнопрстуфхцчшщъыьэюя“»]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'               => 'Подчёркивать ссылки:',
'tog-highlightbroken'         => 'Показывать несуществующие ссылки <a href="" class="new">вот так</a> (иначе вот так<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Выравнивать текст по ширине страницы',
'tog-hideminor'               => 'Скрывать малозначимые правки в списке свежих изменений',
'tog-extendwatchlist'         => 'Улучшенный список наблюдения',
'tog-usenewrc'                => 'Улучшенный список свежих изменений (JavaScript)',
'tog-numberheadings'          => 'Автоматически нумеровать заголовки',
'tog-showtoolbar'             => 'Показывать панель инструментов при редактировании (JavaScript)',
'tog-editondblclick'          => 'Править статьи по двойному щелчку (JavaScript)',
'tog-editsection'             => 'Показывать ссылку «править» для каждой секции',
'tog-editsectiononrightclick' => 'Править секции при правом щелчке мышью на заголовке (JavaScript)',
'tog-showtoc'                 => 'Показывать оглавление (для страниц более чем с 3 заголовками)',
'tog-rememberpassword'        => 'Помнить мою учётную запись на этом компьютере',
'tog-editwidth'               => 'Поле редактирования во всю ширину окна браузера',
'tog-watchcreations'          => 'Добавлять созданные мной страницы в список наблюдения',
'tog-watchdefault'            => 'Добавлять изменённые мной страницы в список наблюдения',
'tog-watchmoves'              => 'Добавлять переименованные мной страницы в список наблюдения',
'tog-watchdeletion'           => 'Добавлять удалённые мной страницы в список наблюдения',
'tog-minordefault'            => 'По умолчанию помечать изменения как малозначимые',
'tog-previewontop'            => 'Показывать предпросмотр статьи до окна редактирования',
'tog-previewonfirst'          => 'Предварительный просмотр по первому изменению',
'tog-nocache'                 => 'Запретить кеширование страниц',
'tog-enotifwatchlistpages'    => 'Уведомлять по эл. почте об изменениях страниц из списка наблюдения',
'tog-enotifusertalkpages'     => 'Уведомлять по эл. почте об изменении персональной страницы обсуждения',
'tog-enotifminoredits'        => 'Уведомлять по эл. почте даже при малозначительных изменениях',
'tog-enotifrevealaddr'        => 'Показывать мой почтовый адрес в сообщениях оповещения',
'tog-shownumberswatching'     => 'Показывать число участников, включивших страницу в свой список наблюдения',
'tog-fancysig'                => 'Собственная вики-разметка подписи',
'tog-externaleditor'          => 'Использовать по умолчанию внешний редактор',
'tog-externaldiff'            => 'Использовать по умолчанию внешнюю программу сравнения версий',
'tog-showjumplinks'           => 'Включить вспомогательные ссылки «перейти к»',
'tog-uselivepreview'          => 'Использовать быстрый предварительный просмотр (JavaScript, экспериментально)',
'tog-forceeditsummary'        => 'Предупреждать, когда не указано краткое описание изменений',
'tog-watchlisthideown'        => 'Скрывать мои правки из списка наблюдения',
'tog-watchlisthidebots'       => 'Скрывать правки ботов из списка наблюдения',
'tog-watchlisthideminor'      => 'Скрывать малые правки из списка наблюдения',
'tog-nolangconversion'        => 'Отключить преобразование систем письма',
'tog-ccmeonemails'            => 'Отправлять мне копии писем, которые я посылаю другим участникам.',
'tog-diffonly'                => 'Не показывать содержание страницы под сравнением двух версий',

'underline-always'  => 'Всегда',
'underline-never'   => 'Никогда',
'underline-default' => 'Использовать настройки браузера',

'skinpreview' => '(Предпросмотр)',

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

# Bits of text used by many pages
'categories'            => 'Категории',
'pagecategories'        => '{{PLURAL:$1|Категория|||Категории}}',
'category_header'       => 'Статьи в категории «$1»',
'subcategories'         => 'Подкатегории',
'category-media-header' => 'Файлы в категории «$1»',
'category-empty'        => "''Эта категория в данный момент пуста.''",

'linkprefix'        => '/^(.*?)(„|«)$/sD',
'mainpagetext'      => '<big>Вики-движок «MediaWiki» успешно установлен.</big>',
'mainpagedocfooter' => 'Информацию по работе с этой вики можно найти в [http://meta.wikimedia.org/wiki/%D0%9F%D0%BE%D0%BC%D0%BE%D1%89%D1%8C:%D0%A1%D0%BE%D0%B4%D0%B5%D1%80%D0%B6%D0%B0%D0%BD%D0%B8%D0%B5 руководстве пользователя].

== Некоторые полезные ресурсы ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Список возможных настроек];
* [http://www.mediawiki.org/wiki/Manual:FAQ Часто задаваемые вопросы и ответы по MediaWiki];
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Рассылка уведомлений о выходе новых версий MediaWiki].',

'about'          => 'Описание',
'article'        => 'Статья',
'newwindow'      => '(в новом окне)',
'cancel'         => 'Отменить',
'qbfind'         => 'Поиск',
'qbbrowse'       => 'Просмотреть',
'qbedit'         => 'Править',
'qbpageoptions'  => 'Настройки страницы',
'qbpageinfo'     => 'Сведения о статье',
'qbmyoptions'    => 'Ваши настройки',
'qbspecialpages' => 'Специальные страницы',
'moredotdotdot'  => 'Далее…',
'mypage'         => 'Личная страница',
'mytalk'         => 'Моя страница обсуждения',
'anontalk'       => 'Обсуждение для этого IP-адреса',
'navigation'     => 'Навигация',

# Metadata in edit box
'metadata_help' => 'Метаданные:',

'errorpagetitle'    => 'Ошибка',
'returnto'          => 'Возврат к странице $1.',
'tagline'           => 'Материал из {{grammar:genitive|{{SITENAME}}}}.',
'help'              => 'Справка',
'search'            => 'Поиск',
'searchbutton'      => 'Найти',
'go'                => 'Перейти',
'searcharticle'     => 'Перейти',
'history'           => 'История',
'history_short'     => 'История',
'updatedmarker'     => 'обновлено с моего последнего посещения',
'info_short'        => 'Информация',
'printableversion'  => 'Версия для печати',
'permalink'         => 'Постоянная ссылка',
'print'             => 'Печать',
'edit'              => 'Править',
'editthispage'      => 'Править эту статью',
'delete'            => 'Удалить',
'deletethispage'    => 'Стереть её',
'undelete_short'    => 'Восстановить $1 {{PLURAL:$1|правку|правки|правок}}',
'protect'           => 'Защитить',
'protect_change'    => 'Изменить уровень защиты',
'protectthispage'   => 'Защитить',
'unprotect'         => 'Снять защиту',
'unprotectthispage' => 'Снять защиту',
'newpage'           => 'Новая статья',
'talkpage'          => 'Обсудить эту страницу',
'talkpagelinktext'  => 'Обсуждение',
'specialpage'       => 'Служебная страница',
'personaltools'     => 'Личные инструменты',
'postcomment'       => 'Прокомментировать',
'articlepage'       => 'Просмотреть статью',
'talk'              => 'Обсуждение',
'views'             => 'Просмотры',
'toolbox'           => 'Инструменты',
'userpage'          => 'Просмотреть страницу участника',
'projectpage'       => 'Просмотреть страницу проекта',
'imagepage'         => 'Просмотреть страницу изображения',
'mediawikipage'     => 'Показать страницу сообщения',
'templatepage'      => 'Просмотреть страницу шаблона',
'viewhelppage'      => 'Получить справку',
'categorypage'      => 'Просмотреть страницу категории',
'viewtalkpage'      => 'Просмотреть обсуждение',
'otherlanguages'    => 'На других языках',
'redirectedfrom'    => '(Перенаправлено с $1)',
'redirectpagesub'   => 'Страница-перенаправление',
'lastmodifiedat'    => 'Последнее изменение этой страницы: $2, $1.', # $1 date, $2 time
'viewcount'         => 'К этой странице обращались $1 {{PLURAL:$1|раз|раза|раз}}.',
'protectedpage'     => 'Защищённая статья',
'jumpto'            => 'Перейти к:',
'jumptonavigation'  => 'навигация',
'jumptosearch'      => 'поиск',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Описание {{grammar:genitive|{{SITENAME}}}}',
'aboutpage'         => 'Project:Описание',
'bugreports'        => 'Отчёт об ошибке',
'bugreportspage'    => 'Project:Отчёт об ошибке',
'copyright'         => 'Содержимое доступно в соответствии с $1.',
'copyrightpagename' => 'Авторские права проекта {{SITENAME}}',
'copyrightpage'     => '{{ns:project}}:Авторское право',
'currentevents'     => 'Текущие события',
'currentevents-url' => 'Project:Текущие события',
'disclaimers'       => 'Отказ от ответственности',
'disclaimerpage'    => 'Project:Отказ от ответственности',
'edithelp'          => 'Справка по редактированию',
'edithelppage'      => 'Help:Справка по редактированию',
'faq'               => 'ЧаВО',
'faqpage'           => 'Project:ЧаВО',
'helppage'          => 'Help:Справка',
'mainpage'          => 'Заглавная страница',
'policy-url'        => 'Project:Правила',
'portal'            => 'Сообщество',
'portal-url'        => 'Project:Портал сообщества',
'privacy'           => 'Политика конфиденциальности',
'privacypage'       => 'Project:Политика конфиденциальности',
'sitesupport'       => 'Пожертвования',
'sitesupport-url'   => 'Project:Пожертвования',

'badaccess'        => 'Ошибка доступа',
'badaccess-group0' => 'Вы не можете выполнять запрошенное действие.',
'badaccess-group1' => 'Запрошенное действие могут выполнять только участники из группы $1.',
'badaccess-group2' => 'Запрошенное действие могут выполнять только участники из групп $1.',
'badaccess-groups' => 'Запрошенное действие могут выполнять только участники из групп $1.',

'versionrequired'     => 'Требуется MediaWiki версии $1',
'versionrequiredtext' => 'Для работы с этой страницей требуется MediaWiki версии $1. См. [[{{ns:special}}:Version|информацию о версиях используемого ПО]].',

'ok'                      => 'OK',
'pagetitle'               => '$1 — {{SITENAME}}',
'retrievedfrom'           => 'Получено с $1',
'youhavenewmessages'      => 'Вы получили $1 ($2).',
'newmessageslink'         => 'новые сообщения',
'newmessagesdifflink'     => 'последнее изменение',
'youhavenewmessagesmulti' => 'Вы получили новые сообщения на $1',
'editsection'             => 'править',
'editold'                 => 'править',
'editsectionhint'         => 'Править секцию: $1',
'toc'                     => 'Содержание',
'showtoc'                 => 'показать',
'hidetoc'                 => 'убрать',
'thisisdeleted'           => 'Просмотреть или восстановить $1?',
'viewdeleted'             => 'Просмотреть $1?',
'restorelink'             => '{{PLURAL:$1|$1 удалённую правку|$1 удалённые правки|$1 удалённых правок}}',
'feedlinks'               => 'В виде:',
'feed-invalid'            => 'Неправильный тип канала для подписки.',
'site-rss-feed'           => '$1 - RSS лента',
'site-atom-feed'          => '$1 - Atom лента',
'page-rss-feed'           => '«$1» - RSS лента',
'page-atom-feed'          => '«$1» - Atom лента',

# Short words for each namespace, by default used in the 'article' tab in monobook
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
'nosuchactiontext'  => 'Действие, указанное в URL, не распознаётся программным обеспечением вики',
'nosuchspecialpage' => 'Такой специальной страницы нет',
'nospecialpagetext' => "<big>'''Запрошенной вами служебной страницы не существует.'''</big>

См. [[{{ns:special}}:Specialpages|список служебных страниц]].",

# General errors
'error'                => 'Ошибка',
'databaseerror'        => 'Ошибка базы данных',
'dberrortext'          => 'Обнаружена ошибка синтаксиса запроса к базе данных.
Последний запрос к базе данных:
<blockquote><tt>$1</tt></blockquote>
произошёл из функции <tt>«$2»</tt>.
MySQL возвратил ошибку <tt>«$3: $4»</tt>.',
'dberrortextcl'        => 'Обнаружена ошибка синтаксиса запроса к базе данных.
Последний запрос к базе данных:
«$1»
произошёл из функции «$2».
MySQL возвратил ошибку «$3: $4».',
'noconnect'            => 'Извините, сейчас невозможно связаться с сервером базы данных из-за технических проблем.<br />
$1',
'nodb'                 => 'Невозможно выбрать базу данных $1',
'cachederror'          => 'Ниже представлена кешированная копия запрошенной страницы; возможно, она устарела.',
'laggedslavemode'      => 'Внимание: страница может не содержать последних обновлений.',
'readonly'             => 'Запись в базу данных заблокирована',
'enterlockreason'      => 'Укажите причину и намеченный срок блокировки.',
'readonlytext'         => 'Добавление новых статей и другие изменения базы данных сейчас заблокированы: вероятно, в связи с плановым обслуживанием.
Заблокировавший оператор оставил следующее разъяснение:
$1',
'missingarticle'       => 'База данных не нашла текста статьи,
хотя должна была найти, по имени «$1».

Обычно это вызвано использованием устаревшей ссылки на журнал изменений или различий для статьи, которая была удалена.

Если дело не в этом, то скорее всего, вы обнаружили ошибку в программном обеспечении вики.
Пожалуйста, сообщите об этом администратору, указав URL.',
'readonly_lag'         => 'База данных автоматически заблокирована от изменений на время пока вторичный сервер БД не синхронизируется с первичным.',
'internalerror'        => 'Внутренняя ошибка',
'internalerror_info'   => 'Внутренняя ошибка: $1',
'filecopyerror'        => 'Невозможно скопировать файл «$1» в «$2».',
'filerenameerror'      => 'Невозможно переименовать файл «$1» в «$2».',
'filedeleteerror'      => 'Невозможно удалить файл «$1».',
'directorycreateerror' => 'Невозможно создать директорию «$1».',
'filenotfound'         => 'Невозможно найти файл «$1».',
'fileexistserror'      => 'Невозможно записать в файл «$1»: файл существует.',
'unexpected'           => 'Неподходящее значение: «$1»=«$2».',
'formerror'            => 'Ошибка: невозможно передать данные формы',
'badarticleerror'      => 'Это действие не может быть выполнено на данной странице.',
'cannotdelete'         => 'Невозможно удалить указанную страницу или файл. Возможно, его уже удалил кто-то другой.',
'badtitle'             => 'Недопустимое название',
'badtitletext'         => 'Запрашиваемое название статьи неправильно, пусто, либо неправильно указано межъязыковое или интервики название. Возможно, в названии используются недопустимые символы.',
'perfdisabled'         => 'К сожалению, эта возможность временно недоступна в связи с загруженностью сервера.',
'perfcached'           => 'Следующие данные взяты из кеша и могут не учитывать последних изменений.',
'perfcachedts'         => 'Следующие данные взяты из кеша, последний раз он обновлялся в $1.',
'querypage-no-updates' => 'Изменение этой страницы в настоящее время запрещено. Эти данные не будут обновлены в настоящее время.',
'wrong_wfQuery_params' => 'Недопустимые параметры для функции wfQuery()<br />
Функция: $1<br />
Запрос: $2',
'viewsource'           => 'Просмотр',
'viewsourcefor'        => 'Страница «$1»',
'protectedpagetext'    => 'Эта страница закрыта для редактирования.',
'viewsourcetext'       => 'Вы можете просмотреть и скопировать исходный текст этой страницы:',
'protectedinterface'   => 'Эта страница содержит интерфейсное сообщение программного обеспечения. Во избежание вандализма её изменение запрещено.',
'editinginterface'     => "'''Внимание:''' Вы редактируете страницу, содержащую системное сообщение MediaWiki. Её изменение повлияет на внешний вид интерфейса для других пользователей.",
'sqlhidden'            => '(SQL запрос скрыт)',
'cascadeprotected'     => 'Страница защищена от изменений, поскольку она включена в {{PLURAL:$1|следующую страницу, для которой|||следующие страницы, для которых}} включена каскадная защита:
$2',
'namespaceprotected'   => 'У вас нет разрешения редактировать страницы в пространстве имён «$1».',
'customcssjsprotected' => 'У вас нет разрешения редактировать эту страницу, так как она содержит личные настройки другого участника.',
'ns-specialprotected'  => 'Страницы пространства имён «{{ns:special}}» не могут правиться.',

# Login and logout pages
'logouttitle'                => 'Стать инкогнито',
'logouttext'                 => 'Вы работаете в том же режиме, который был до вашего представления системе. Вы идентифицируетесь не по имени, а по IP-адресу.
Вы можете продолжить участие в проекте анонимно или начать новый сеанс как тот же самый или другой пользователь. Некоторые страницы могут отображаться, как будто вы ещё представлены системе под именем, для борьбы с этим явлением обновите кеш браузера.',
'welcomecreation'            => '== Добро пожаловать, $1! ==

Вы были зарегистрированы.
Не забудьте провести [[{{ns:special}}:Preferences|персональную настройку сайта]].',
'loginpagetitle'             => 'Представиться системе',
'yourname'                   => 'Имя участника:',
'yourpassword'               => 'Пароль:',
'yourpasswordagain'          => 'Повторный набор пароля:',
'remembermypassword'         => 'Помнить мою учётную запись на этом компьютере',
'yourdomainname'             => 'Ваш домен:',
'externaldberror'            => 'Произошла ошибка при аутентификации с помощью внешней базы данных, или у вас недостаточно прав для внесения изменений в свою внешнюю учётную запись.',
'loginproblem'               => '<span style="color:red">Участник не опознан.</span>',
'login'                      => 'Представиться системе',
'loginprompt'                => 'Вы должны разрешить «cookies», чтобы представиться системе.',
'userlogin'                  => 'Представиться системе',
'logout'                     => 'Завершение сеанса',
'userlogout'                 => 'Завершение сеанса',
'notloggedin'                => 'Вы не представились системе',
'nologin'                    => 'Вы ещё не зарегистрировались? $1.',
'nologinlink'                => 'Создать учётную запись',
'createaccount'              => 'Зарегистрировать нового участника',
'gotaccount'                 => 'Вы уже зарегистрированы? $1.',
'gotaccountlink'             => 'Представьтесь',
'createaccountmail'          => 'по эл. почте',
'badretype'                  => 'Введённые вами пароли не совпадают.',
'userexists'                 => 'Введённое вами имя участника уже занято. Пожалуйста, выберите другое имя.',
'youremail'                  => 'Электронная почта:',
'username'                   => 'Регистрационное имя:',
'uid'                        => 'Идентификатор пользователя:',
'yourrealname'               => 'Ваше настоящее имя:',
'yourlanguage'               => 'Язык интерфейса:',
'yourvariant'                => 'Вариант языка',
'yournick'                   => 'Ваш псевдоним (для подписей):',
'badsig'                     => 'Неверная подпись. Проверьте корректность HTML-тегов.',
'badsiglength'               => 'Слишком длинная подпись, должна быть не более $1 символов.',
'email'                      => 'Эл. почта',
'prefs-help-realname'        => 'Настоящее имя (необязательное поле): если вы укажите его, то оно будет использовано для того чтобы показать кем был внесена правка страницы.',
'loginerror'                 => 'Ошибка опознавания участника',
'prefs-help-email'           => 'Электронная почта (необязательное поле) позволяет другим участникам связаться с вами без раскрытия адреса вашей электронной почты.',
'prefs-help-email-required'  => 'Необходимо указать адрес электронной почты.',
'nocookiesnew'               => 'Участник зарегистрирован, но не представлен. {{SITENAME}} использует «cookies» для представления участников. У вас «cookies» запрещены. Пожалуйста, разрешите их, а затем представьтесь с вашим новым именем участника и паролем.',
'nocookieslogin'             => '{{SITENAME}} использует «cookies» для представления участников. Вы их отключили. Пожалуйста, включите их и попробуйте снова.',
'noname'                     => 'Вы не указали допустимого имени участника.',
'loginsuccesstitle'          => 'Опознание прошло успешно',
'loginsuccess'               => 'Теперь вы работаете под именем $1.',
'nosuchuser'                 => 'Участника с именем $1 не существует.
Проверьте правильность написания или воспользуйтесь формой ниже, чтобы зарегистрировать нового участника.',
'nosuchusershort'            => 'Не существует участника с именем $1. Проверьте написание имени.',
'nouserspecified'            => 'Вы должны указать имя участника.',
'wrongpassword'              => 'Введённый вами пароль неверен. Попробуйте ещё раз.',
'wrongpasswordempty'         => 'Пожалуйста, введите непустой пароль.',
'passwordtooshort'           => 'Введённый пароль недействителен или слишком короткий. Пароль должен состоять не менее чем из $1 символов и отличаться от имени участника.',
'mailmypassword'             => 'Выслать новый пароль',
'passwordremindertitle'      => 'Напоминание пароля участника {{grammar:genitive|{{SITENAME}}}}',
'passwordremindertext'       => 'Кто-то (вероятно, вы) с IP-адреса $1 запросил,
чтобы мы выслали вам новый пароль участника {{grammar:genitive|{{SITENAME}}}} ($4).
Пароль для участника $2 теперь: <code>$3</code>.
Вы должны представиться системе и поменять пароль.

Если вы не посылали запроса на смену пароля, или если вы уже вспомнили свой пароль,
вы можете проигнорировать данное сообщение и продолжить использовать свой старый пароль.',
'noemail'                    => 'Для участника с именем $1 электронный адрес указан не был.',
'passwordsent'               => 'Новый пароль был выслан на адрес электронной почты, указанный для участника $1.

Пожалуйста, представьтесь системе заново после получения пароля.',
'blocked-mailpassword'       => 'Редактирование с вашего IP-адреса запрещено, заблокирована и функция восстановления пароля.',
'eauthentsent'               => 'Временный пароль был отправлен на адрес электронной почты нового участника $1. В письме также описаны действия, которые нужно выполнить, чтобы подтвердить, что этот адрес электронной почты действительно принадлежит вам.',
'throttled-mailpassword'     => 'Функция напоминания пароля уже использовалось в течение последних $1 часов. Для предотвращения злоупотреблений, разрешено запрашивать не более одного напоминания за $1 часов.',
'mailerror'                  => 'Ошибка при отправке почты: $1',
'acct_creation_throttle_hit' => 'К сожалению, вы уже создали $1 учётных записей. Вы не можете создать больше ни одной.',
'emailauthenticated'         => 'Ваш почтовый адрес был подтверждён $1.',
'emailnotauthenticated'      => 'Ваш адрес электронной почты ещё не был подтверждён, функции вики-движка по работе с эл. почтой отключены.',
'noemailprefs'               => 'Адрес электронной почты не был указан, функции вики-движка по работе с эл. почтой отключены.',
'emailconfirmlink'           => 'Подтвердить ваш адрес электронной почты',
'invalidemailaddress'        => 'Введённый адрес не может быть принят, так как он не соответствует формату адресов электронной почты. Пожалуйста, введите корректный адрес или оставьте поле пустым.',
'accountcreated'             => 'Учётная запись создана',
'accountcreatedtext'         => 'Создана учётная запись участника $1.',
'loginlanguagelabel'         => 'Язык: $1',

# Password reset dialog
'resetpass'               => 'Сброс пароля от учётной записи',
'resetpass_announce'      => 'Вы представились с помощью временного пароля, полученного по электронной почте. Для завершения входа в систему, вы должны установить новый пароль.',
'resetpass_text'          => '<!-- Добавьте сюда текст -->',
'resetpass_header'        => 'Сброс пароля',
'resetpass_submit'        => 'Установить пароль и представиться',
'resetpass_success'       => 'Ваш пароль был успешно изменён! Выполняется вход в систему…',
'resetpass_bad_temporary' => 'Недействительный временный пароль. Возможно, вы уже изменили ваш пароль, или попробуйте запросить временный пароль снова.',
'resetpass_forbidden'     => 'Возможность смены пароля в данной вики-системе не предусмотрена',
'resetpass_missing'       => 'Форма не содержит данных.',

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
'image_sample'    => 'Example.jpg',
'image_tip'       => 'Встроенное изображение',
'media_sample'    => 'Example.ogg',
'media_tip'       => 'Ссылка на медиа-файл',
'sig_tip'         => 'Ваша подпись и момент времени',
'hr_tip'          => 'Горизонтальная линия (не используйте часто)',

# Edit pages
'summary'                   => 'Описание изменений',
'subject'                   => 'Тема/заголовок',
'minoredit'                 => 'Малое изменение',
'watchthis'                 => 'Включить эту страницу в список наблюдения',
'savearticle'               => 'Записать страницу',
'preview'                   => 'Предпросмотр',
'showpreview'               => 'Предварительный просмотр',
'showlivepreview'           => 'Быстрый предпросмотр',
'showdiff'                  => 'Внесённые изменения',
'anoneditwarning'           => "'''Внимание''': Вы не представились системе. Ваш IP-адрес будет записан в историю изменений этой страницы.",
'missingsummary'            => "'''Напоминание.''' Вы не дали краткого описания изменений. При повторном нажатии на кнопку «Сохранить», ваши изменения будут сохранены без комментария.",
'missingcommenttext'        => 'Пожалуйста, введите ниже ваше сообщение.',
'missingcommentheader'      => "'''Напоминание:''' Вы не указали заголовок комментария. При повторном нажатии на кнопку «Сохранить», ваш комментарий будет записан без заголовка.",
'summary-preview'           => 'Описание будет',
'subject-preview'           => 'Заголовок будет',
'blockedtitle'              => 'Участник заблокирован',
'blockedtext'               => "<big>'''Ваша учётная запись или IP-адрес заблокированы.'''</big>

Блокировка произведена администратором $1. Указана следующая причина: ''«$2»''.

* Начало блокировки: $8
* Окончание блокировки: $6
* Был заблокирован: $7

Вы можете отправить письмо участнику $1 или любому другому [[{{MediaWiki:Grouppage-sysop}}|администратору]], чтобы обсудить блокировку.

Обратите внимание, что вы не сможете отправить письмо администратору, если вы не зарегистрированы и не подтвердили свой адрес электронной почты в [[{{ns:special}}:Preferences|личных настройках]], а также если вам было запрещено отправлять письма при блокировке.

Ваш IP-адрес — $3, идентификатор блокировки — #$5. Пожалуйста, указывайте эти данные в своих запросах.",
'autoblockedtext'           => 'Ваш IP-адрес автоматически заблокирован в связи с тем, что он ранее использовался кем-то из заблокированных участников. Заблокировавший его администратор ($1) указал следующую причину блокировки:

:«$2»

* Начало блокировки: $8
* Окончание блокировки: $6

Вы можете отправить письмо участнику $1 или любому другому [[{{MediaWiki:Grouppage-sysop}}|администратору]], чтобы обсудить блокировку.

Обратите внимание, что вы не сможете отправить письмо администратору, если вы не зарегистрированы в проекте и не подтвердили свой адрес электронной почты в [[{{ns:special}}:Preferences|личных настройках]], а также если вам было запрещено отправлять письма при блокировке.

Ваш идентификатор блокировки — #$5. Пожалуйста, указывайте его в своих запросах.',
'blockedoriginalsource'     => 'Ниже показан текст страницы «$1».',
'blockededitsource'         => "Ниже показан текст '''ваших изменений''' страницы «$1».",
'whitelistedittitle'        => 'Для изменения требуется авторизация',
'whitelistedittext'         => 'Вы должны $1 для изменения страниц.',
'whitelistreadtitle'        => 'Для чтения требуется авторизация',
'whitelistreadtext'         => 'Вы должны [[{{ns:special}}:Userlogin|зарегистрироваться]] для чтения этих страниц.',
'whitelistacctitle'         => 'У вас нет прав, чтобы создать учётную запись',
'whitelistacctext'          => 'Для того чтобы иметь возможность создавать учётные записи в этой вики, вы должны [[{{ns:special}}:Userlogin|зарегистрироваться]] и иметь соответствующие права.',
'confirmedittitle'          => 'Требуется подтверждение адреса электронной почты',
'confirmedittext'           => 'Вы должны подтвердить ваш адрес электронной почты перед правкой страниц. Пожалуйста, введите и подтвердите ваш адрес эл. почты на [[{{ns:special}}:Preferences|странице настроек]].',
'nosuchsectiontitle'        => 'Нет такой секции',
'nosuchsectiontext'         => 'Вы пытаетесь редактировать подстраницу, которой не существует. Так как не существует подстраницы с названием $1, ваши правки некуда сохранять.',
'loginreqtitle'             => 'Требуется авторизация',
'loginreqlink'              => 'представиться',
'loginreqpagetext'          => 'Вы должны $1, чтобы просмотреть другие страницы.',
'accmailtitle'              => 'Пароль выслан.',
'accmailtext'               => 'Пароль для $1 выслан на $2.',
'newarticle'                => '(Новая)',
'newarticletext'            => "Вы перешли по ссылке на статью, которая пока не существует.

Чтобы создать новую страницу, наберите текст в окне, расположенном ниже
(см. [[{{MediaWiki:Helppage}}|справочную страницу]] чтобы получить больше информации).
Если вы оказались здесь по ошибке, просто нажмите кнопку '''назад''' вашего браузера.",
'anontalkpagetext'          => "----''Эта страница обсуждения принадлежит анонимному участнику, который ещё не зарегистрировался или который не представился регистрированным именем. Для идентификации используется цифровой IP-адрес. Если вы анонимный участник и полагаете, что получили сообщения, адресованные не вам (один IP-адрес может использоваться несколькими пользователями), пожалуйста, [[{{ns:special}}:Userlogin|представьтесь системе]], чтобы впредь избежать возможной путаницы с другими участниками.''",
'noarticletext'             => "В настоящий момент текст на данной странице отсутствует. Вы можете [[{{ns:special}}:Search/{{PAGENAME}}|найти упоминание данного названия]] в других статьях или '''[{{fullurl:{{FULLPAGENAME}}|action=edit}} создать страницу с таким названием]'''.",
'clearyourcache'            => "'''Замечание:''' Чтобы после сохранения увидеть сделанные изменения, очистите кеш своего браузера: '''Mozilla / Firefox''': ''Ctrl+Shift+R'', '''IE:''' ''Ctrl+F5'', '''Safari''': ''Cmd+Shift+R'', '''Konqueror''': ''F5'', '''Opera''': через меню ''Tools→Preferences''.",
'usercssjsyoucanpreview'    => '<strong>Подсказка:</strong> Перед сохранением используйте кнопку предварительного просмотра, чтобы проверить ваш новый css- или js-файл.',
'usercsspreview'            => "'''Помните, что это только предварительный просмотр вашего css-файла, он ещё не сохранён!'''",
'userjspreview'             => "'''Помните, что это только предварительный просмотр вашего javascript-файла, он ещё не сохранён!'''",
'userinvalidcssjstitle'     => "'''Внимание:''' тема оформления «$1» не найдена. Помните, что пользовательские .css и .js страницы должны иметь название состоящее только из маленьких букв, например «{{ns:user}}:Некто/monobook.css», а не «{{ns:user}}:Некто/Monobook.css».",
'updated'                   => '(Обновлена)',
'note'                      => '<strong>Примечание:</strong>',
'previewnote'               => 'Это только предварительный просмотр, текст ещё не записан!',
'previewconflict'           => 'Этот предварительный просмотр отражает текст в верхнем окне редактирования так, как он будет выглядеть, если вы решите записать его.',
'session_fail_preview'      => '<strong>К сожалению, сервер не смог сохранить ваши изменения из-за потери идентификатора сессии. Пожалуйста, попробуйте ещё раз. Если эта ошибка повторится, попробуйте завершить сеанс и заново представиться системе.</strong>',
'session_fail_preview_html' => "<strong>К сожалению, сервер не смог сохранить ваши изменения из-за потери данных сессии.</strong>

''Так как данная вики разрешает использовать чистый HTML, предварительный просмотр отключён в качестве меры предотвращения JavaScript-атак.''

<strong>Если это добросовестная попытка редактирования, пожалуйста, попробуйте ещё раз. Если не получается повторная правка, попробуйте завершить сеанс работы и заново представиться.</strong>",
'token_suffix_mismatch'     => '<strong>Ваша правка была отклонена, так как ваша программа неправильно обрабатывает знаки пунктуации
в окне редактирования. Правка была отменена для предотвращени искажения текста статьи.
Подобные проблемы могут возникать при использовании анонимизирующих веб-прокси, содержащих ошибки.</strong>',
'editing'                   => 'Редактирование: $1',
'editinguser'               => 'Для участника <b>$1</b>',
'editingsection'            => 'Редактирование $1 (секция)',
'editingcomment'            => 'Редактирование $1 (комментарий)',
'editconflict'              => 'Конфликт редактирования: $1',
'explainconflict'           => 'Пока вы редактировали эту статью, кто-то внёс в неё изменения. В верхнем окне для редактирования вы видите тот текст статьи, который будет сохранён при нажатии на кнопку «Записать страницу». В нижнем окне для редактирования находится ваш вариант. Чтобы сохранить ваши изменения, перенесите их из нижнего окна для редактирования в верхнее.<br />',
'yourtext'                  => 'Ваш текст',
'storedversion'             => 'Сохранённая версия',
'nonunicodebrowser'         => '<strong>ПРЕДУПРЕЖДЕНИЕ: Ваш браузер не поддерживает кодировку Юникод. При редактировании статей все не-ASCII символы будут заменены на свои шестнадцатеричные коды.</strong>',
'editingold'                => '<strong>ПРЕДУПРЕЖДЕНИЕ: Вы редактируете устаревшую версию данной страницы. После сохранения страницы будут потеряны изменения, сделанные в последующих версиях.</strong>',
'yourdiff'                  => 'Различия',
'copyrightwarning'          => 'Обратите внимание, что все добавления и изменения текста статьи рассматриваются, как выпущенные на условиях лицензии $2 (см. $1).
Если вы не хотите, чтобы ваши тексты свободно распространялись и редактировались любым желающим, не помещайте их сюда.<br />
Вы также подтверждаете, что являетесь автором вносимых дополнений, или скопировали их из
источника, допускающего свободное распространение и изменение своего содержимого.<br />
<strong>НЕ РАЗМЕЩАЙТЕ БЕЗ РАЗРЕШЕНИЯ МАТЕРИАЛЫ, ОХРАНЯЕМЫЕ АВТОРСКИМ ПРАВОМ!</strong>',
'copyrightwarning2'         => 'Пожалуйста, обратите внимание, что все ваши добавления
могут быть отредактированы или удалены другими участниками.
Если вы не хотите, чтобы кто-либо изменял ваши тексты, не помещайте их сюда.<br />
Вы также подтверждаете, что являетесь автором вносимых дополнений, или скопировали их из источника, допускающего свободное распространение и изменение своего содержимого (см. $1).<br />
<strong>НЕ РАЗМЕЩАЙТЕ БЕЗ РАЗРЕШЕНИЯ ОХРАНЯЕМЫЕ АВТОРСКИМ ПРАВОМ МАТЕРИАЛЫ!</strong>',
'longpagewarning'           => '<strong>ПРЕДУПРЕЖДЕНИЕ: Длина этой страницы составляет $1 килобайт. Страницы, размер которых приближается к 32 КБ или превышает это значение, могут неверно отображаться в некоторых браузерах.
Пожалуйста, рассмотрите вариант разбиения страницы на меньшие части.</strong>',
'longpageerror'             => '<strong>ОШИБКА: записываемый вами текст имеет размер $1 килобайт, что больше, чем установленный предел $2 килобайта. Страница не может быть сохранена.</strong>',
'readonlywarning'           => '<strong>ПРЕДУПРЕЖДЕНИЕ: база данных заблокирована в связи с процедурами обслуживания,
поэтому вы не можете записать ваши изменения прямо сейчас.
Возможно, вам следует сохранить текст в файл на своём диске и поместить его в данный проект позже.</strong>',
'protectedpagewarning'      => '<strong>ПРЕДУПРЕЖДЕНИЕ: эта страница защищена от изменений, её могут редактировать только администраторы.</strong>',
'semiprotectedpagewarning'  => "'''Замечание:''' эта страница была защищена; редактировать её могут только зарегистрированные участники.",
'cascadeprotectedwarning'   => "'''Предупреждение:''' Данную страницу могут редактировать только участники группы «Администраторы», поскольку она включена {{PLURAL:$1|в следующую страницу, для которой|||в следующие страницы, для которых}} включена каскадная защита:",
'templatesused'             => 'Шаблоны, использованные на этой странице:',
'templatesusedpreview'      => 'Шаблоны, используемые в предпросматриваемой странице:',
'templatesusedsection'      => 'Шаблоны, используемые в этой секции:',
'template-protected'        => '(защищено)',
'template-semiprotected'    => '(частично защищено)',
'edittools'                 => '<!-- Расположенный здесь текст будет показываться под формой редактирования и формой загрузки. -->',
'nocreatetitle'             => 'Создание страниц ограничено',
'nocreatetext'              => 'На этом сайте ограничена возможность создания новых страниц.
Вы можете вернуться назад и отредактировать существующую страницу, [[{{ns:special}}:Userlogin|представиться системе или создать новую учётную запись]].',
'nocreate-loggedin'         => 'У вас нет разрешения создавать новые страницы в этой вики.',
'permissionserrors'         => 'Ошибки прав доступа',
'permissionserrorstext'     => 'Вы не имете разрешения делать это по {{PLURAL:$1|следующей причине|следующим причинам|следующим причинам}}:',
'recreate-deleted-warn'     => "'''Внимание: вы пытаетесь воссоздать страницу, которая ранее удалялась.'''

Проверьте, действительно ли вам нужно воссоздавать эту страницу. Ниже приведён журнал удалений.",

# "Undo" feature
'undo-success' => 'Правка может быть отменена. Пожалуйста, просмотрите сравнение версий, чтобы убедиться, что это именно те изменения, которые вас интересуют, и нажмите «Записать страницу», чтобы изменения вступили в силу.',
'undo-failure' => 'Правка не может быть отменена из-за несовместимости промежуточных изменений.',
'undo-summary' => 'Отмена правки № $1 участника [[{{ns:special}}:Contributions/$2|$2]] ([[{{ns:user_talk}}:$2|обсуждение]])',

# Account creation failure
'cantcreateaccounttitle' => 'Невозможно создать учётную запись',
'cantcreateaccount-text' => "Создание учётных записей с этого IP-адреса (<b>$1</b>) было заблокировано [[User:$3|участником $3]].
	 
$3 указал следующую причину: ''$2''",

# History pages
'revhistory'          => 'Журнал изменений',
'viewpagelogs'        => 'Показать журналы для этой страницы',
'nohistory'           => 'Для этой страницы журнал изменений отсутствует.',
'revnotfound'         => 'Версия не найдена',
'revnotfoundtext'     => 'Старая версия страницы не найдена. Пожалуйста, проверьте правильность ссылки, которую вы использовали для доступа к этой странице.',
'loadhist'            => 'Загрузка журнала изменений страницы',
'currentrev'          => 'Текущая версия',
'revisionasof'        => 'Версия $1',
'revision-info'       => 'Версия от $1; $2',
'previousrevision'    => '← Предыдущая',
'nextrevision'        => 'Следующая →',
'currentrevisionlink' => 'Текущая версия',
'cur'                 => 'текущ.',
'next'                => 'след.',
'last'                => 'пред.',
'orig'                => 'перв.',
'page_first'          => 'первая',
'page_last'           => 'последняя',
'histlegend'          => "Пояснения: (текущ.) — отличие от текущей версии; (пред.) — отличие от предшествующей версии; '''м''' — малозначимое изменение",
'deletedrev'          => '[удалена]',
'histfirst'           => 'старейшие',
'histlast'            => 'недавние',
'historysize'         => '($1 {{plural:$1|байт|байта|байтов}})',
'historyempty'        => '(пусто)',

# Revision feed
'history-feed-title'          => 'История изменений',
'history-feed-description'    => 'История изменений этой страницы в вики',
'history-feed-item-nocomment' => '$1 в $2', # user at time
'history-feed-empty'          => 'Запрашиваемой страницы не существует.
Она могла быть удалена или переименована.
Попробуйте [[{{ns:special}}:Search|найти в вики]] похожие страницы.',

# Revision deletion
'rev-deleted-comment'         => '(комментарий удалён)',
'rev-deleted-user'            => '(имя автора стёрто)',
'rev-deleted-event'           => '(запись удалена)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
Эта версия страницы была удалена из общедоступного архива.
Возможно, объяснения даны в [{{fullurl:{{ns:special}}:Log/delete|page={{PAGENAMEE}}}} журнале удалений].
</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">
Эта версия страницы была удалена из общедоступного архива.
Вы можете просмотреть её, так как являетесь администратором сайта.
Возможно, объяснения удаления даны в [{{fullurl:{{ns:special}}:Log/delete|page={{PAGENAMEE}}}} журнале удалений].
</div>',
'rev-delundel'                => 'показать/скрыть',
'revisiondelete'              => 'Удалить / восстановить версии страницы',
'revdelete-nooldid-title'     => 'Не задана целевая версия',
'revdelete-nooldid-text'      => 'Вы не задали целевую версию (или версии) для выполнения этой функции.',
'revdelete-selected'          => "{{PLURAL:$2|Выбранная версия|||Выбранные версии}} страницы '''$1:'''",
'logdelete-selected'          => "{{PLURAL:$2|Выбранная запись|||Выбранные записи}} журнала для страницы '''$1:'''",
'revdelete-text'              => 'Удалённые версии будут показываться в истории страницы и журналах,
но часть их содержания будет недоступна обычным посетителям.

Администраторы будут иметь доступ к скрытому содержанию и смогут восстановить его через этот же интерфейс,
за исключением случаев, когда было установлено дополнительное ограничение.',
'revdelete-legend'            => 'Установить ограничения:',
'revdelete-hide-text'         => 'Скрыть текст этой версии страницы',
'revdelete-hide-name'         => 'Скрыть действие и его объект',
'revdelete-hide-comment'      => 'Скрыть комментарий',
'revdelete-hide-user'         => 'Скрыть имя автора',
'revdelete-hide-restricted'   => 'Применить ограничения также и к администраторам',
'revdelete-suppress'          => 'Скрывать данные также и от администраторов',
'revdelete-hide-image'        => 'Скрыть содержимое файла',
'revdelete-unsuppress'        => 'Снять ограничения с восстановленных версий',
'revdelete-log'               => 'Примечание:',
'revdelete-submit'            => 'Применить к выбранной версии',
'revdelete-logentry'          => 'Изменена видимость версии страницы [[$1]]',
'logdelete-logentry'          => 'Изменена видимость события для [[$1]]',
'revdelete-logaction'         => '$1 {{PLURAL:$1|версия переведена|версии переведены|версий переведены}} в режим $2',
'logdelete-logaction'         => '$1 {{PLURAL:$1|событие|события|событий}} страницы [[$3]] {{plural:$1|переведено|переведены|переведены}} в режим $2',
'revdelete-success'           => 'Видимость версии изменена.',
'logdelete-success'           => 'Видимость события изменена.',

# Oversight log
'oversightlog'    => 'Журнал сокрытия',
'overlogpagetext' => 'Ниже представлен список последних удалений и блокировок, затрагивающих материалы скрытые от администраторов. Просмотреть список действующих блокировок можно на [[Special:Ipblocklist|соответствующей странице]].',

# Diffs
'history-title'             => '$1 — история изменений',
'difference'                => '(Различия между версиями)',
'loadingrev'                => 'загрузка версии для различения',
'lineno'                    => 'Строка $1:',
'editcurrent'               => 'Редактировать текущую версию данной страницы',
'selectnewerversionfordiff' => 'Выберите новую версию для сравнения',
'selectolderversionfordiff' => 'Выберите старую версию для сравнения',
'compareselectedversions'   => 'Сравнить выбранные версии',
'editundo'                  => 'отменить',
'diff-multi'                => '({{PLURAL:$1|$1 промежуточная версия не показана|$1 промежуточные версии не показаны|$1 промежуточных версий не показаны.}})',

# Search results
'searchresults'         => 'Результаты поиска',
'searchresulttext'      => 'Для получения более подробной информации о поиске на страницах проекта, см. [[{{MediaWiki:Helppage}}|справочный раздел]].',
'searchsubtitle'        => 'По запросу «[[:$1]]»',
'searchsubtitleinvalid' => 'По запросу «$1»',
'noexactmatch'          => 'Страницы с названием «$1» не существует.

<span style="display: block; margin: 1.5em 2em">
<strong>[[:$1|Создать страницу]]</strong></span>',
'titlematches'          => 'Совпадения в названиях статей',
'notitlematches'        => 'Нет совпадений в названиях статей',
'textmatches'           => 'Совпадения в текстах статей',
'notextmatches'         => 'Нет совпадения в текстах статей',
'prevn'                 => 'предыдущие $1',
'nextn'                 => 'следующие $1',
'viewprevnext'          => 'Просмотреть ($1) ($2) ($3)',
'showingresults'        => 'Ниже {{plural:$1|показан|показаны|показаны}} <strong>$1</strong> {{plural:$1|результат|результата|результатов}}, начиная с №&nbsp;<strong>$2</strong>.',
'showingresultsnum'     => 'Ниже {{plural:$3|показан|показаны|показаны}} <strong>$3</strong> {{plural:$3|результат|результата|результатов}}, начиная с №&nbsp;<strong>$2</strong>.',
'nonefound'             => 'Неудачный поиск может быть вызван попыткой найти общие слова, которые не подлежат индексированию, например — «тоже» и «чтобы» или употреблением более чем одного ключевого слова поиска (показываются только страницы, содержащие все указанные слова для поиска).',
'powersearch'           => 'Искать',
'powersearchtext'       => 'Искать в пространствах имён:<br />$1<br />$2 Показывать перенаправления<br /> Искать $3 $9',
'searchdisabled'        => 'Извините, но встроенный полнотекстовый поиск выключен. Вы можете воспользоваться поиском по сайту через поисковые системы общего назначения, однако имейте в виду, что копия сайта в их кеше может быть несколько устаревшей.',

# Preferences page
'preferences'              => 'Настройки',
'mypreferences'            => 'Настройки',
'prefs-edits'              => 'Количество правок:',
'prefsnologin'             => 'Вы не представились системе',
'prefsnologintext'         => 'Вы должны [[{{ns:special}}:Userlogin|представиться системе]], чтобы изменять настройки участника.',
'prefsreset'               => 'Восстановлены настройки по умолчанию.',
'qbsettings'               => 'Панель навигации',
'qbsettings-none'          => 'Не показывать',
'qbsettings-fixedleft'     => 'Неподвижная слева',
'qbsettings-fixedright'    => 'Неподвижная справа',
'qbsettings-floatingleft'  => 'Плавающая слева',
'qbsettings-floatingright' => 'Плавающая справа',
'changepassword'           => 'Сменить пароль',
'skin'                     => 'Оформление',
'math'                     => 'Отображение формул',
'dateformat'               => 'Формат даты',
'datedefault'              => 'По умолчанию',
'datetime'                 => 'Дата и время',
'math_failure'             => 'Невозможно разобрать выражение',
'math_unknown_error'       => 'неизвестная ошибка',
'math_unknown_function'    => 'неизвестная функция',
'math_lexing_error'        => 'лексическая ошибка',
'math_syntax_error'        => 'синтаксическая ошибка',
'math_image_error'         => 'Преобразование в PNG прошло с ошибкой; проверьте правильность установки latex, dvips, gs и convert',
'math_bad_tmpdir'          => 'Не удаётся создать или записать во временный каталог математики',
'math_bad_output'          => 'Не удаётся создать или записать в выходной каталог математики',
'math_notexvc'             => 'Выполняемый файл texvc не найден; См. math/README — справку по настройке.',
'prefs-personal'           => 'Личные данные',
'prefs-rc'                 => 'Страница свежих правок',
'prefs-watchlist'          => 'Список наблюдения',
'prefs-watchlist-days'     => 'Максимальное число дней, отображаемых в списке наблюдения:',
'prefs-watchlist-edits'    => 'Максимальное количество правок, отображаемых в улучшенном списке наблюдения:',
'prefs-misc'               => 'Другие настройки',
'saveprefs'                => 'Записать',
'resetprefs'               => 'Сбросить',
'oldpassword'              => 'Старый пароль:',
'newpassword'              => 'Новый пароль:',
'retypenew'                => 'Повторите ввод нового пароля:',
'textboxsize'              => 'Редактирование',
'rows'                     => 'Строк:',
'columns'                  => 'Столбцов:',
'searchresultshead'        => 'Результаты поиска',
'resultsperpage'           => 'Количество найденных записей на страницу:',
'contextlines'             => 'Количество показываемых строк для каждой найденной:',
'contextchars'             => 'Количество символов контекста на строку:',
'stub-threshold'           => 'Порог для определения оформления <a href="#" class="stub">ссылок на заготовки</a>:',
'recentchangesdays'        => 'Количество дней, за которые показывать свежие правки:',
'recentchangescount'       => 'Количество правок, отображаемое в списке:',
'savedprefs'               => 'Ваши настройки сохранены.',
'timezonelegend'           => 'Часовой пояс',
'timezonetext'             => 'Введите смещение (в часах) вашего местного времени
от времени сервера (UTC — гринвичского).',
'localtime'                => 'Местное время',
'timezoneoffset'           => 'Смещение',
'servertime'               => 'Текущее время сервера',
'guesstimezone'            => 'Заполнить из браузера',
'allowemail'               => 'Разрешить приём электронной почты от других участников',
'defaultns'                => 'По умолчанию искать в следующих пространствах имён:',
'default'                  => 'по умолчанию',
'files'                    => 'Файлы',

# User rights
'userrights-lookup-user'      => 'Управление группами участников',
'userrights-user-editname'    => 'Введите имя участника:',
'editusergroup'               => 'Изменить группы участников',
'userrights-editusergroup'    => 'Изменить группы участника',
'saveusergroups'              => 'Сохранить группы участника',
'userrights-groupsmember'     => 'Член групп:',
'userrights-groupsavailable'  => 'Доступные группы:',
'userrights-groupshelp'       => 'Выберите группы, в которые вы хотите включить или из которых хотите исключить участника.
Невыбранные группы не изменятся. Снять выделение с группы можно используя CTRL + левую клавишу мыши.',
'userrights-reason'           => 'Причина изменения:',
'userrights-available-none'   => 'Вы не можете изменять членство в группе.',
'userrights-available-add'    => 'Вы можете добавлять участников в группу $1.',
'userrights-available-remove' => 'Вы можете удалять участников из группы $1.',

# Groups
'group'               => 'Группа:',
'group-autoconfirmed' => 'Автоподтверждённые участники',
'group-bot'           => 'Боты',
'group-sysop'         => 'Администраторы',
'group-bureaucrat'    => 'Бюрократы',
'group-all'           => '(все)',

'group-autoconfirmed-member' => 'Автоподтверждённый участник',
'group-bot-member'           => 'бот',
'group-sysop-member'         => 'администратор',
'group-bureaucrat-member'    => 'бюрократ',

'grouppage-autoconfirmed' => '{{ns:project}}:Автоподтверждённые участники',
'grouppage-bot'           => '{{ns:project}}:Боты',
'grouppage-sysop'         => '{{ns:project}}:Администраторы',
'grouppage-bureaucrat'    => '{{ns:project}}:Бюрократы',

# User rights log
'rightslog'      => 'Журнал прав участника',
'rightslogtext'  => 'Это журнал изменений прав участника.',
'rightslogentry' => 'Для участника $1 изменены права доступа: с $2 на $3',
'rightsnone'     => '(нет)',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|изменение|изменения|изменений}}',
'recentchanges'                     => 'Свежие правки',
'recentchangestext'                 => 'Ниже в хронологическом порядке перечислены последние изменения на страницах {{grammar:genitive|{{SITENAME}}}}.',
'recentchanges-feed-description'    => 'Отслеживать последние изменения в вики в этом потоке.',
'rcnote'                            => "{{PLURAL:$1|Последнее '''$1''' изменение|Последние '''$1''' изменения|Последние '''$1''' изменений}} за '''$2''' {{plural:$2|день|дня|дней}}, на момент времени $3.",
'rcnotefrom'                        => 'Ниже перечислены изменения с <strong>$2</strong> (по <strong>$1</strong>).',
'rclistfrom'                        => 'Показать изменения с $1.',
'rcshowhideminor'                   => '$1 малые правки',
'rcshowhidebots'                    => '$1 ботов',
'rcshowhideliu'                     => '$1 представившихся участников',
'rcshowhideanons'                   => '$1 анонимов',
'rcshowhidepatr'                    => '$1 проверенные правки',
'rcshowhidemine'                    => '$1 свои правки',
'rclinks'                           => 'Показать последние $1 изменений за $2 {{plural:$2|день|дня|дней}};<br />$3.',
'diff'                              => 'разн.',
'hist'                              => 'история',
'hide'                              => 'Скрыть',
'show'                              => 'Показать',
'minoreditletter'                   => 'м',
'newpageletter'                     => 'Н',
'boteditletter'                     => 'б',
'number_of_watching_users_pageview' => '[$1 наблюдающих пользователя]',
'rc_categories'                     => 'Только из категорий (разделитель «|»)',
'rc_categories_any'                 => 'Любой',
'newsectionsummary'                 => '/* $1 */ Новая тема',

# Recent changes linked
'recentchangeslinked'          => 'Связанные правки',
'recentchangeslinked-title'    => 'Связанные правки для $1',
'recentchangeslinked-noresult' => 'На связанных страницах не было изменений за указанный период.',
'recentchangeslinked-summary'  => "На этой служебной странице представлен список последних изменений на связанных страницах. Страницы из списка наблюдения '''выделены'''.",

# Upload
'upload'                      => 'Загрузить файл',
'uploadbtn'                   => 'Загрузить файл',
'reupload'                    => 'Изменить загрузку',
'reuploaddesc'                => 'Вернуться к форме загрузки.',
'uploadnologin'               => 'Вы не представились системе',
'uploadnologintext'           => 'Вы должны [[{{ns:special}}:Userlogin|представиться системе]],
чтобы загружать файлы на сервер.',
'upload_directory_read_only'  => 'Веб-сервер не имеет прав записи в папку ($1), в которой предполагается хранить загружаемые файлы.',
'uploaderror'                 => 'Ошибка загрузки файла',
'uploadtext'                  => "Используя эту форму вы можете загрузить на сервер файлы. Чтобы просмотреть ранее загруженные файлы,
перейдите сюда: [[{{ns:special}}:Imagelist|список загруженных изображений]]. Загрузка и удаление файлов отражаются в [[{{ns:special}}:Log|журнале загрузки файлов]].

Для включения изображения в статью вы можете использовать строки вида:
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:File.jpg]]</nowiki>''',
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:File.png|комментарий]]</nowiki>''' или
'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki>''' для непосредственной ссылки на файл.",
'uploadlog'                   => 'журнал загрузок',
'uploadlogpage'               => 'Журнал загрузок',
'uploadlogpagetext'           => 'Ниже представлен список последних загрузок файлов.
Везде используется время сервера (по Гринвичу, UTC).',
'filename'                    => 'Имя файла',
'filedesc'                    => 'Краткое описание',
'fileuploadsummary'           => 'Краткое описание:',
'filestatus'                  => 'Условия распространения',
'filesource'                  => 'Источник',
'uploadedfiles'               => 'Загруженные файлы',
'ignorewarning'               => 'Игнорировать предупреждения и сохранить файл в любом случае.',
'ignorewarnings'              => 'Игнорировать предупреждения',
'minlength1'                  => 'Название файла должно содержать хотя бы одну букву.',
'illegalfilename'             => 'Имя файла «$1» содержит символы, которые не разрешается использовать в заголовках. Пожалуйста, переименуйте файл и попытайтесь загрузить его снова.',
'badfilename'                 => 'Название файла было изменено на $1.',
'filetype-badmime'            => 'Файлы, имеющие MIME-тип "$1", не могут быть загружены.',
'filetype-badtype'            => "'''«.$1»''' является нежелательным типом файлов
: Список разрешённых типов файлов: $2",
'filetype-missing'            => 'Отсутствует расширение у файла (например, «.jpg»).',
'large-file'                  => 'Рекомендуется использовать изображения, размер которых не превышает $1 байт (размер загруженного файла составляет $2 байт).',
'largefileserver'             => 'Размер файла превышает максимально разрешённый.',
'emptyfile'                   => 'Загруженный вами файл вероятно пустой. Возможно, это произошло из-за ошибки при наборе имени файла. Пожалуйста, проверьте, действительно ли вы хотите загрузить этот файл.',
'fileexists'                  => 'Файл с этим именем уже существует, пожалуйста, проверьте <strong><tt>$1</tt></strong>, если вы не уверены, что хотите заменить его.',
'fileexists-extension'        => 'Существует файл с похожим именем:<br />
Имя загруженного файла: <strong><tt>$1</tt></strong><br />
Имя существующего файла: <strong><tt>$2</tt></strong><br />
Пожалуйста, выберите другое имя.',
'fileexists-thumb'            => "<center>'''Существующее изображение'''</center>",
'fileexists-thumbnail-yes'    => 'Файл, вероятно, является уменьшенной копией (миниатюрой). Пожалуйста, проверьте файл <strong><tt>$1</tt></strong>.<br />
Если указанный файл является тем же изображением, не стоит загружать отдельно его уменьшенную копию.',
'file-thumbnail-no'           => 'Название файла начинается с <strong><tt>$1</tt></strong>. Вероятно, это уменьшенная копия изображения (миниатюра).
Если у вас есть данное изображение в полном размере, пожалуйста, загрузите его или измените имя файла.',
'fileexists-forbidden'        => 'Файл с этим именем уже существует; пожалуйста, вернитесь назад и загрузите файл под другим именем. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Файл с этим именем уже существует в общем хранилище файлов; пожалуйста, вернитесь назад и загрузите файл под другим именем. [[Image:$1|thumb|center|$1]]',
'successfulupload'            => 'Загрузка успешно завершена',
'uploadwarning'               => 'Предупреждение',
'savefile'                    => 'Записать файл',
'uploadedimage'               => 'загружено «[[$1]]»',
'overwroteimage'              => 'загружена новая версия «[[$1]]»',
'uploaddisabled'              => 'Загрузка запрещена.',
'uploaddisabledtext'          => 'На этом вики-сайте загрузка файлов запрещена.',
'uploadscripted'              => 'Файл содержит HTML-код или скрипт, который может быть ошибочно обработан браузером.',
'uploadcorrupt'               => 'Файл либо повреждён, либо имеет неверное расширение. Пожалуйста, проверьте файл и попробуйте загрузить его ещё раз.',
'uploadvirus'                 => 'Файл содержит вирус! См. $1',
'sourcefilename'              => 'Исходное имя файла',
'destfilename'                => 'Целевое имя файла',
'watchthisupload'             => 'Включить этот файл в список наблюдения',
'filewasdeleted'              => 'Файл с таким именем уже существовал ранее, но был удалён. Пожалуйста, проверьте $1 перед повторной загрузкой.',
'upload-wasdeleted'           => "'''Внимание: вы пытаетесь загрузить файл, который ранее удалялся.'''

Проверьте, действительно ли вам нужно загружать этот файл.
Ниже приведён журнал удалений:",
'filename-bad-prefix'             => 'Имя загружаемого файла начинается с <strong>«$1»</strong> и вероятно является шаблонным именем, которое цифровая фотокамера даёт снимкам. Пожалуйста, выберите имя лучше описывающее содержание файла.',

'upload-proto-error'      => 'Неправильный протокол',
'upload-proto-error-text' => 'Для удалённой загрузки требуется адрес, начинающийся с <code>http://</code> или <code>ftp://</code>.',
'upload-file-error'       => 'Внутренняя ошибка',
'upload-file-error-text'  => 'Внутренняя ошибка при попытке создать временный файл на сервере. Пожалуйста, обратитесь к системному администратору.',
'upload-misc-error'       => 'Неизвестная ошибка загрузки',
'upload-misc-error-text'  => 'Неизвестная ошибка загрузки. Пожалуйста, проверьте, что адрес верен, и повторите попытку. Если проблема остаётся, обратитесь к системному администратору.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Невозможно обратить по указанному адресу.',
'upload-curl-error6-text'  => 'Невозможно обратить по указанному адресу. Пожалуйста, проверьте, что адрес верен, а сайт доступен.',
'upload-curl-error28'      => 'Время, отведённое на загрузку, истекло',
'upload-curl-error28-text' => 'Сайт слишком долго не отвечает. Пожалуйста, проверьте что сайт работоспособен и после небольшого перерыва попробуйте ещё раз. Возможно, операцию следует провести в другое время, когда сайт менее нагружен.',

'license'            => 'Лицензирование',
'nolicense'          => 'Отсутствует',
'license-nopreview'  => '(Предпросмотр недоступен)',
'upload_source_url'  => ' (правильный, публично доступный интернет-адрес)',
'upload_source_file' => ' (файл на вашем компьютере)',

# Image list
'imagelist'                 => 'Список файлов',
'imagelisttext'             => "Ниже представлен список из '''$1''' {{PLURAL:$1|файла|файлов|файлов}}, отсортированных $2.",
'getimagelist'              => 'получение списка файлов',
'ilsubmit'                  => 'Искать',
'showlast'                  => 'Показать последние $1 {{plural:$1|файл|файла|файлов}}, {{plural:$1|отсортированные|отсортированные|отсортированных}} $2.',
'byname'                    => 'по имени',
'bydate'                    => 'по дате',
'bysize'                    => 'по размеру',
'imgdelete'                 => 'удал.',
'imgdesc'                   => 'описание',
'imgfile'                   => 'файл',
'filehist'                  => 'История файла',
'filehist-help'             => 'Нажмите на дату/время, чтобы просмотреть как тогда выглядел файл.',
'filehist-deleteall'        => 'удалить все',
'filehist-deleteone'        => 'удалить этот',
'filehist-revert'           => 'вернуть',
'filehist-current'          => 'текущий',
'filehist-datetime'         => 'Дата/время',
'filehist-user'             => 'Участник',
'filehist-dimensions'       => 'Размер объекта',
'filehist-filesize'         => 'Размер файла',
'filehist-comment'          => 'Примечание',
'imagelinks'                => 'Ссылки',
'linkstoimage'              => 'Следующие страницы ссылаются на данный файл:',
'nolinkstoimage'            => 'Нет страниц, ссылающихся на данный файл.',
'sharedupload'              => 'Этот файл загружен в общее для нескольких проектов хранилище.',
'shareduploadwiki'          => 'Дополнительную информацию можно найти на $1.',
'shareduploadwiki-linktext' => 'странице описания файла',
'noimage'                   => 'Файла с таким именем не существует, вы можете $1.',
'noimage-linktext'          => 'загрузить его',
'uploadnewversion-linktext' => 'Загрузить новую версию этого файла',
'imagelist_date'            => 'Дата',
'imagelist_name'            => 'Имя файла',
'imagelist_user'            => 'Участник',
'imagelist_size'            => 'Размер',
'imagelist_description'     => 'Описание',
'imagelist_search_for'      => 'Поиск по имени изображения:',

# File reversion
'filerevert'                => 'Возврат к старой версии $1',
'filerevert-legend'         => 'Возвратить версию файла',
'filerevert-intro'          => '<span class="plainlinks">Вы возвращаете \'\'\'[[Media:$1|$1]]\'\'\' к [$4 версии от $3, $2].</span>',
'filerevert-comment'        => 'Примечание:',
'filerevert-defaultcomment' => 'Возврат к версии от $2, $1',
'filerevert-submit'         => 'Возвратить',
'filerevert-success'        => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\' был возвращён к [$4 версии Filetype-missingот $3, $2].</span>',
'filerevert-badversion'     => 'Не существует предыдущей локальной версии этого файла с указанной отметкой даты и времени.',

# File deletion
'filedelete'             => 'Удаление $1',
'filedelete-legend'      => 'Удалить файл',
'filedelete-intro'       => "Вы удаляете '''[[Media:$1|$1]]'''.",
'filedelete-intro-old'   => '<span class="plainlinks">Вы удаляете версию \'\'\'[[Media:$1|$1]]\'\'\' от [$4 $3, $2].</span>',
'filedelete-comment'     => 'Примечание:',
'filedelete-submit'      => 'Удалить',
'filedelete-success'     => "'''$1''' был удалён.",
'filedelete-success-old' => '<span class="plainlinks">Версия \'\'\'[[Media:$1|$1]]\'\'\' от $3, $2 была удалена.</span>',
'filedelete-nofile'      => "'''$1''' не существует на этом сайте.",
'filedelete-nofile-old'  => "Не существет архивной версии '''$1''' с указанными атрибутами.",
'filedelete-iscurrent'   => 'Вы пытаетесь удалить последнюю версию этого файла. Пожалуйста, верните сначала файл к отдной из старых версий.',

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
'unusedtemplatestext' => 'На этой странице перечислены все страницы пространства имён «Шаблоны», которые не включены в другие страницы. Не забывайте проверить отсутствие других ссылок на шаблон, перед его удалением.',
'unusedtemplateswlh'  => 'другие ссылки',

# Random pages
'randompage'                      => 'Случайная страница',
'randompage-nopages'              => 'В данном пространстве имён отсутствуют страницы.',

# Statistics
'statistics'             => 'Статистика',
'sitestats'              => 'Статистика сайта',
'userstats'              => 'Статистика участников',
'sitestatstext'          => "Всего в базе данных содержится '''$1''' {{plural:$1|страница|страницы|страниц}}.
Это число включает в себя страницы о проекте, страницы обсуждений, незаконченные статьи, перенаправления и другие страницы, которые, не учитываются при подсчёте количества статей.
За исключением них, есть '''$2''' {{plural:$2|страница|страницы|страниц}}, которые считаются полноценными статьями. 

{{plural:$8|Был загружен|Было загружено|Было загружено}} '''$8''' {{plural:$8|файл|файла|файлов}}.

Всего с момента установки вики {{plural:$3|был произведён '''$3''' просмотр|было произведено '''$3''' просмотра|было произведено '''$3''' просмотров}} страниц и '''$4''' {{plural:$4|изменение|изменения|изменений}} страниц. Таким образом, в среднем приходится '''$5''' {{plural:$5|изменение|изменения|изменений}} на одну страницу, и '''$6''' просмотров на одно изменение.

Величина [http://meta.wikimedia.org/wiki/Help:Job_queue очереди заданий] составляет '''$7'''.",
'userstatstext'          => "{{plural:$1|Зарегистрировался|Зарегистрировались|Зарегистрировались}} '''$1''' {{plural:$1|участник|участника|участников}}, из которых '''$2''' ($4 %) имеют права «$5».",
'statistics-mostpopular' => 'Наиболее часто просматриваемые страницы',

'disambiguations'      => 'Страницы, описывающие многозначные термины',
'disambiguationspage'  => '{{ns:template}}:Неоднозначность',
'disambiguations-text' => "Следующие статьи ссылаются на '''многозначные страницы'''. Вместо этого они, вероятно, должны указывать на соответствующую конкретную статью.<br />Страница считается многозначной, если на ней размещён шаблон, имя которого указано на странице [[MediaWiki:disambiguationspage]].",

'doubleredirects'     => 'Двойные перенаправления',
'doubleredirectstext' => 'На этой странице представлен список перенаправлений на другие перенаправления. Каждая строка содержит ссылки на первое и второе перенаправления, а также первую строчку страницы второго перенаправления, в которой обычно указывается название страницы, куда должно ссылаться первое перенаправление.',

'brokenredirects'        => 'Разорванные перенаправления',
'brokenredirectstext'    => 'Следующие перенаправления указывают на несуществующие статьи:',
'brokenredirects-edit'   => '(править)',
'brokenredirects-delete' => '(удалить)',

'withoutinterwiki'        => 'Страницы без межъязыковых ссылок',
'withoutinterwiki-header' => 'Следующие страницы не имеют интервики-ссылок:',

'fewestrevisions' => 'Статьи с наименьшим количеством изменений',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|байт|байта|байтов}}',
'ncategories'             => '$1 {{PLURAL:$1|категория|категории|категорий}}',
'nlinks'                  => '$1 {{PLURAL:$1|ссылка|ссылки|ссылок}}',
'nmembers'                => '$1 {{PLURAL:$1|объект|объекта|объектов}}',
'nrevisions'              => '$1 {{PLURAL:$1|версия|версии|версий}}',
'nviews'                  => '$1 {{PLURAL:$1|просмотр|просмотра|просмотров}}',
'specialpage-empty'       => 'Запрос не дал результатов.',
'lonelypages'             => 'Страницы-сироты',
'lonelypagestext'         => 'На следующие страницы нет ссылок с других страниц данной вики.',
'uncategorizedpages'      => 'Некатегоризованные страницы',
'uncategorizedcategories' => 'Некатегоризованные категории',
'uncategorizedimages'     => 'Некатегоризованные изображения',
'uncategorizedtemplates'  => 'Некатегоризованные шаблоны',
'unusedcategories'        => 'Неиспользуемые категории',
'unusedimages'            => 'Неиспользуемые файлы',
'popularpages'            => 'Популярные страницы',
'wantedcategories'        => 'Требуемые категории',
'wantedpages'             => 'Требуемые страницы',
'mostlinked'              => 'Страницы, на которые больше всего ссылок',
'mostlinkedcategories'    => 'Категории, на которые больше всего ссылок',
'mostlinkedtemplates'     => 'Самые используемые шаблоны',
'mostcategories'          => 'Страницы, включённые в большое количество категорий',
'mostimages'              => 'Самые используемые изображения',
'mostrevisions'           => 'Наиболее часто редактировавшиеся страницы',
'allpages'                => 'Все страницы',
'prefixindex'             => 'Указатель по началу слов',
'shortpages'              => 'Короткие статьи',
'longpages'               => 'Длинные страницы',
'deadendpages'            => 'Тупиковые страницы',
'deadendpagestext'        => 'Следующие страницы не содержат ссылок на другие страницы в этой вики.',
'protectedpages'          => 'Защищённые страницы',
'protectedpagestext'      => 'Следующие страницы защищены от переименования или изменения.',
'protectedpagesempty'     => 'В настоящий момент нет защищённых страниц с указанными параметрами',
'listusers'               => 'Список участников',
'specialpages'            => 'Спецстраницы',
'spheading'               => 'Служебные страницы',
'restrictedpheading'      => 'Служебные страницы с ограниченным доступом',
'rclsub'                  => '(на страницах, ссылки на которые есть на странице «$1»)',
'newpages'                => 'Новые статьи',
'newpages-username'       => 'Участник:',
'ancientpages'            => 'Статьи по дате последнего редактирования',
'intl'                    => 'Межъязыковые ссылки',
'move'                    => 'Переименовать',
'movethispage'            => 'Переименовать эту страницу',
'unusedimagestext'        => 'Пожалуйста, учтите, что другие веб-сайты могут использовать прямую ссылку (URL) на это изображение, и поэтому изображение может активно использоваться несмотря на его вхождение в этот список.',
'unusedcategoriestext'    => 'Существуют следующие страницы категорий, не содержащие статей или других категорий.',
'notargettitle'           => 'Не указана цель',
'notargettext'            => 'Вы не указали целевую страницу или участника для этого действия.',

# Book sources
'booksources'               => 'Источники книг',
'booksources-search-legend' => 'Поиск информации о книге',
'booksources-go'            => 'Найти',
'booksources-text'          => 'На этой странице приведён список ссылок на сайты, где вы, возможно, найдёте дополнительную информацию о книге. Это интернет-магазины и системы поиска в библиотечных каталогах.',

'categoriespagetext' => 'В вики имеются следующие категории.',
'data'               => 'Данные',
'userrights'         => 'Управление правами участников',
'groups'             => 'Группы участников',
'alphaindexline'     => 'от $1 до $2',
'version'            => 'Версия MediaWiki',

# Special:Log
'specialloguserlabel'  => 'Участник:',
'speciallogtitlelabel' => 'Заголовок:',
'log'                  => 'Журналы',
'all-logs-page'        => 'Все журналы',
'log-search-legend'    => 'Поиск журналов',
'log-search-submit'    => 'Найти',
'alllogstext'          => 'Общий список журналов сайта {{SITENAME}}.
Вы можете отфильтровать результаты по типу журнала, имени участника или затронутой странице.',
'logempty'             => 'Совпадающие элементы в журнале отсутствуют.',
'log-title-wildcard'   => 'Найти заголовки, начинающиеся на с данных символов',

# Special:Allpages
'nextpage'          => 'Следующая страница ($1)',
'prevpage'          => 'Предыдущая страница ($1)',
'allpagesfrom'      => 'Вывести страницы, начинающиеся на:',
'allarticles'       => 'Все статьи',
'allinnamespace'    => 'Все страницы (пространство имён «$1»)',
'allnotinnamespace' => 'Все страницы (кроме пространства имён «$1»)',
'allpagesprev'      => 'Предыдущие',
'allpagesnext'      => 'Следующие',
'allpagessubmit'    => 'Выполнить',
'allpagesprefix'    => 'Найти страницы, начинающиеся с:',
'allpagesbadtitle'  => 'Заголовок данной страницы не является допустимым. Он содержит интервики, межъязыковой префикс или запрещённые в заголовках символы.',
'allpages-bad-ns'   => '{{SITENAME}} не содержит пространства имён «$1».',

# Special:Listusers
'listusersfrom'      => 'Показать участников, начиная с:',
'listusers-submit'   => 'Показать',
'listusers-noresult' => 'Не найдено участников.',

# E-mail user
'mailnologin'     => 'Адрес для отправки отсутствует',
'mailnologintext' => 'Вы должны [[{{ns:special}}:Userlogin|представиться системе]]
и иметь действительный адрес электронной почты в ваших [[{{ns:special}}:Preferences|настройках]],
чтобы иметь возможность отправлять электронную почту другим участникам.',
'emailuser'       => 'Письмо участнику',
'emailpage'       => 'Отправить электронное письмо участнику',
'emailpagetext'   => 'Если этот участник указал действительный адрес электронной почты в своих настройках, то, заполнив форму ниже, можно отправить ему сообщение.
Электронный адрес, который вы указали в своих настройках, будет указан в поле «От кого» письма, поэтому получатель будет иметь возможность ответить.',
'usermailererror' => 'При отправке сообщения электронной почты произошла ошибка:',
'defemailsubject' => '{{SITENAME}} e-mail',
'noemailtitle'    => 'Адрес электронной почты отсутствует',
'noemailtext'     => 'Этот участник не указал действительный адрес электронной почты или указал, что не желает получать письма от других участников.',
'emailfrom'       => 'От кого',
'emailto'         => 'Кому',
'emailsubject'    => 'Тема письма',
'emailmessage'    => 'Сообщение',
'emailsend'       => 'Отправить',
'emailccme'       => 'Отправить мне копию письма.',
'emailccsubject'  => 'Копия вашего сообщения для $1: $2',
'emailsent'       => 'Письмо отправлено',
'emailsenttext'   => 'Ваше электронное сообщение отправлено.',

# Watchlist
'watchlist'            => 'Список наблюдения',
'mywatchlist'          => 'Cписок наблюдения',
'watchlistfor'         => "(участника '''$1''')",
'nowatchlist'          => 'Ваш список наблюдения пуст.',
'watchlistanontext'    => 'Вы должны $1, чтобы просмотреть или отредактировать список наблюдения.',
'watchnologin'         => 'Нужно представиться системе',
'watchnologintext'     => 'Вы должны [[{{ns:special}}:Userlogin|представиться системе]], чтобы иметь возможность изменять свой список наблюдения',
'addedwatch'           => 'Добавлена в список наблюдения',
'addedwatchtext'       => 'Страница «[[:$1]]» была добавлена в ваш [[{{ns:special}}:Watchlist|список наблюдения]]. Последующие изменения этой страницы и связанной с ней страницы обсуждения будут отмечаться в этом списке, а также будут выделены жирным шрифтом на странице со [[{{ns:special}}:Recentchanges|списком свежих изменений]], чтобы их было легче заметить.

Если позже вы захотите удалить страницу из списка наблюдения, нажмите кнопку «не следить» в верхней правой части страницы.',
'removedwatch'         => 'Удалена из списка наблюдения',
'removedwatchtext'     => 'Страница «[[:$1]]» была удалена из вашего списка наблюдения.',
'watch'                => 'Следить',
'watchthispage'        => 'Наблюдать за этой страницей',
'unwatch'              => 'Не следить',
'unwatchthispage'      => 'Прекратить наблюдение',
'notanarticle'         => 'Не статья',
'watchnochange'        => 'Ничто из списка наблюдения не изменялось в рассматриваемый период.',
'watchlist-details'    => '$1 {{plural:$1|страница|страницы|страниц}}, не считая страниц обсуждения.',
'wlheader-enotif'      => '* Уведомление по эл. почте включено.',
'wlheader-showupdated' => "* Страницы, изменившиеся с вашего последнего их посещения, выделены '''жирным''' шрифтом.",
'watchmethod-recent'   => 'просмотр последних изменений для наблюдаемых страниц',
'watchmethod-list'     => 'просмотр наблюдаемых страниц для последних изменений',
'watchlistcontains'    => 'Ваш список наблюдения содержит $1 {{plural:$1|страница|страницы|страниц}}.',
'iteminvalidname'      => 'Проблема с элементом «$1», недопустимое название…',
'wlnote'               => 'Ниже следуют последние $1 {{PLURAL:$1|изменение|изменения|изменений}} за {{PLURAL:$2|последний|последние|последние}} <strong>$2</strong> {{plural:$2|час|часа|часов}}.',
'wlshowlast'           => 'Показать за последние $1 часов $2 дней $3',
'watchlist-show-bots'  => 'Показать правки ботов',
'watchlist-hide-bots'  => 'Скрыть правки ботов',
'watchlist-show-own'   => 'Показать мои правки',
'watchlist-hide-own'   => 'Скрыть мои правки',
'watchlist-show-minor' => 'Показать малые правки',
'watchlist-hide-minor' => 'Скрыть малые правки',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Добавление в список наблюдения…',
'unwatching' => 'Удаление из списка наблюдения…',

'enotif_mailer'                => '{{SITENAME}} Служба извещений по почте',
'enotif_reset'                 => 'Отметить все страницы как просмотренные',
'enotif_newpagetext'           => 'Это новая страница.',
'enotif_impersonal_salutation' => 'Участник {{grammar:genitive|{{SITENAME}}}}',
'changed'                      => 'изменена',
'created'                      => 'создана',
'enotif_subject'               => 'Страница проекта «{{SITENAME}}» $PAGETITLE была $CHANGEDORCREATED участником $PAGEEDITOR',
'enotif_lastvisited'           => 'См. $1 для просмотра всех изменений произошедших с вашего последнего посещения.',
'enotif_lastdiff'              => 'См. $1 для ознакомления с изменением.',
'enotif_anon_editor'           => 'анонимный участник $1',
'enotif_body'                  => '$WATCHINGUSERNAME,

$PAGEEDITDATE страница проекта «{{SITENAME}}» $PAGETITLE была $CHANGEDORCREATED участником $PAGEEDITOR, см. $PAGETITLE_URL для просмотра текущей версии.

$NEWPAGE

Краткое описание изменения: $PAGESUMMARY $PAGEMINOREDIT

Обратиться к изменившему:
эл. почта $PAGEEDITOR_EMAIL
вики $PAGEEDITOR_WIKI

Не будет никаких других уведомлений в случае дальнейших изменений, если Вы не посещаете эту страницу. Вы могли также повторно установить флаги уведомления для всех ваших наблюдаемых страниц в вашем списке наблюдения.

             Система оповещения {{grammar:genitive|{{SITENAME}}}}

--
Чтобы изменить настройки вашего списка наблюдения обратитесь к
{{fullurl:{{ns:special}}:Watchlist/edit}}

Обратная связь и помощь:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete/protect/revert
'deletepage'                  => 'Удалить страницу',
'confirm'                     => 'Подтверждение',
'excontent'                   => 'содержимое: «$1»',
'excontentauthor'             => 'содержимое: «$1» (единственным автором был [[{{ns:special}}:Contributions/$2|$2]])',
'exbeforeblank'               => 'содержимое до очистки: «$1»',
'exblank'                     => 'страница была пуста',
'confirmdelete'               => 'Подтвердить удаление',
'deletesub'                   => '(«$1» удаляется)',
'historywarning'              => 'Предупреждение: у страницы, которую вы собираетесь удалить, есть история изменений:',
'confirmdeletetext'           => 'Вы запросили полное удаление страницы (или изображения) и всей её истории изменений из базы данных.
Пожалуйста, подтвердите, что вы действительно желаете это сделать, понимаете последствия своих действий,
и делаете это в соответствии с правилами, изложенными в разделе [[{{MediaWiki:Policy-url}}]].',
'actioncomplete'              => 'Действие выполнено',
'deletedtext'                 => '«$1» была удалена.
См. $2 для просмотра списка последних удалений.',
'deletedarticle'              => 'удалена «[[$1]]»',
'dellogpage'                  => 'Список удалений',
'dellogpagetext'              => 'Ниже приведён список последних удалений.',
'deletionlog'                 => 'список удалений',
'reverted'                    => 'Откачено к ранней версии',
'deletecomment'               => 'Причина удаления',
'rollback'                    => 'Откатить изменения',
'rollback_short'              => 'Откат',
'rollbacklink'                => 'откатить',
'rollbackfailed'              => 'Ошибка при совершении отката',
'cantrollback'                => 'Невозможно откатить изменения; последний, кто вносил изменения, является единственным автором этой статьи.',
'alreadyrolled'               => 'Невозможно откатить последние изменения [[:$1]],
сделанные [[{{ns:user}}:$2|$2]] ([[{{ns:user_talk}}:$2|Обсуждение]]); кто-то другой уже отредактировал или откатил эту страницу.

Последние изменения внёс [[{{ns:user}}:$3|$3]] ([[{{ns:user_talk}}:$3|Обсуждение]]).',
'editcomment'                 => 'Изменение было пояснено так: <i>«$1»</i>.', # only shown if there is an edit comment
'revertpage'                  => 'Правки [[{{ns:special}}:Contributions/$2|$2]] ([[User_talk:$2|обсуждение]]) откачены к версии [[{{ns:user}}:$1|$1]]',
'rollback-success'            => 'Откачены правки $1; возврат к версии $2.',
'sessionfailure'              => 'Похоже, возникли проблемы с текущим сеансом работы;
это действие было отменено в целях предотвращения «захвата сеанса».
Пожалуйста, нажмите кнопку «Назад» и перезагрузите страницу, с которой вы пришли.',
'protectlogpage'              => 'Журнал защиты',
'protectlogtext'              => 'Ниже приведён журнал установок и снятий защиты со статей. Вы можете также просмотреть [[{{ns:special}}:Protectedpages|список страниц, которые в данный момент защищены]].',
'protectedarticle'            => 'защищена страница «[[$1]]»',
'modifiedarticleprotection'   => 'изменён уровень защиты страницы «[[$1]]»',
'unprotectedarticle'          => 'снята защита со страницы «[[$1]]»',
'protectsub'                  => '(Установка уровня защиты для «$1»)',
'confirmprotect'              => 'Подтвердите установку защиты страницы',
'protectcomment'              => 'Причина установки защиты:',
'protectexpiry'               => 'Истекает:',
'protect_expiry_invalid'      => 'Неправильное время окончания защиты.',
'protect_expiry_old'          => 'Время окончания — в прошлом.',
'unprotectsub'                => '(Снятие защиты «$1»)',
'protect-unchain'             => 'Разблокировать переименование страницы',
'protect-text'                => 'Здесь вы можете просмотреть и изменить уровень защиты для страницы <strong>[[:$1]]</strong>.',
'protect-locked-blocked'      => 'Вы не можете изменять уровень защиты страницы пока ваша учётная запись заблокирована. Текущие установки для страницы <strong>[[:$1]]</strong>:',
'protect-locked-dblock'       => 'Уровень защиты не может быть изменён, так как основная база данных временно заблокирована. Текущие установки для страницы <strong>[[:$1]]</strong>:',
'protect-locked-access'       => 'У ваше учётной записи недостаточно прав для изменения уровня защиты страницы. Текущие установки для страницы <strong>[[:$1]]</strong>:',
'protect-cascadeon'           => 'Эта страница защищена в связи с тем, что она включена {{PLURAL:$1|в указанную ниже страницу, на которую|||в нижеследующие страницы, на которые}} установлена каскадная защита. Вы можете изменить уровень защиты этой страницы, но это не повлияет на каскадную защиту.',
'protect-default'             => '(по умолчанию)',
'protect-fallback'            => 'Требуется разрешение «$1»',
'protect-level-autoconfirmed' => 'Защитить от незарегистрированных и новых участников',
'protect-level-sysop'         => 'Только администраторы',
'protect-summary-cascade'     => 'каскадная',
'protect-expiring'            => 'истекает $1 (UTC)',
'protect-cascade'             => 'Защищать страницы, включённые в эту страницу (каскадная защита)',
'restriction-type'            => 'Права:',
'restriction-level'           => 'Уровень доступа:',
'minimum-size'                => 'Минимальный размер',
'maximum-size'                => 'максимальный размер',
'pagesize'                    => '(байт)',

# Restrictions (nouns)
'restriction-edit' => 'Правка',
'restriction-move' => 'Переименование',

# Restriction levels
'restriction-level-sysop'         => 'полная защита',
'restriction-level-autoconfirmed' => 'частичная защита',
'restriction-level-all'           => 'все уровни',

# Undelete
'undelete'                     => 'Просмотреть удалённые страницы',
'undeletepage'                 => 'Просмотр и восстановление удалённых страниц',
'viewdeletedpage'              => 'Просмотреть удалённые страницы',
'undeletepagetext'             => 'Следующие страницы были удалены, однако они всё ещё находятся в архиве, и поэтому могут быть восстановлены. Архив периодически очищается.',
'undeleteextrahelp'            => "Для полного восстановления страницы оставьте все отметки пустыми и нажмите '''«Восстановить»'''. Для частичного восстановления отметьте те версии страницы, которые нужно восстановить, и нажмите '''«Восстановить»'''. Нажмите '''«Очистить»''', чтобы снять все отметки и очистить поле примечания.",
'undeleterevisions'            => 'в архиве $1 {{plural:$1|версия|версии|версий}}',
'undeletehistory'              => 'Если вы восстановите страницу, все её версии будут также восстановлены вместе с журналом изменений.
Если с момента удаления была создана новая страница с таким же названием, восстановленные версии будут указаны в журнале изменений перед новыми записями.
Обратите также внимание, что ограничения на версии файла теряются при восстановлении.',
'undeleterevdel'               => 'Восстановление не будет произведено, если оно произведёт к частичному удалению последней версии. В подобном случает вам следует не отмечать или раскрыть последние удалённые версии. Версии файлов, на просмотр которых у вас нет прав, не будут восстановлены.',
'undeletehistorynoadmin'       => 'Статья была удалена. Причина удаления и список участников, редактировавших статью до её удаления, показаны ниже. Текст удалённой статьи могут просмотреть только администраторы.',
'undelete-revision'            => 'Удалённая версия $1 (от $2, удалил $3):',
'undeleterevision-missing'     => 'Неверная или отсутствующая версия. Возможно, вы перешли по неправильной ссылке, либо версия могла быть удалена из архива.',
'undelete-nodiff'              => 'Не найдено предыдущей версии.',
'undeletebtn'                  => 'Восстановить',
'undeletereset'                => 'Очистить',
'undeletecomment'              => 'Комментарий:',
'undeletedarticle'             => 'восстановлена «[[$1]]»',
'undeletedrevisions'           => '$1 {{PLURAL:$1|изменение|изменения|изменений}} восстановлено',
'undeletedrevisions-files'     => '$1 {{PLURAL:$1|версия|версии|версий}} и $2 {{PLURAL:$2|файл|файла|файлов}} восстановлено',
'undeletedfiles'               => '$1 {{PLURAL:$1|файл восстановлен|файла восстановлено|файлов восстановлено}}',
'cannotundelete'               => 'Ошибка восстановления. Возможно, кто-то другой уже восстановил страницу.',
'undeletedpage'                => "<big>'''Страница «$1» была восстановлена.'''</big>
	 
Для просмотра списка последних удалений и восстановлений см. [[{{ns:special}}:Log/delete|журнал удалений]].",
'undelete-header'              => 'Список недавно удалённых страниц можно посмотреть в [[{{ns:special}}:Log/delete|журнале удалений]].',
'undelete-search-box'          => 'Поиск удалённых страниц',
'undelete-search-prefix'       => 'Показать страницы, начинающиеся с:',
'undelete-search-submit'       => 'Искать',
'undelete-no-results'          => 'Не найдено подходящих страниц среди удалённых.',
'undelete-filename-mismatch'   => 'Невозможно восстановить версию файла с отметкой времени $1: несоответствие имени файла',
'undelete-bad-store-key'       => 'Невозможно восстановить версию файла с отметкой времени $1: файл отсутствовал до удаления.',
'undelete-cleanup-error'       => 'Ошибка удаления неиспользуемого архивного файла «$1».',
'undelete-missing-filearchive' => 'Невозможно восстановить файл с архивным идентификатором $1, так как он отсутствует в базе данных. Возможно, файл уже был восстановлен.',
'undelete-error-short'         => 'Ошибка восстановления файла: $1',
'undelete-error-long'          => "Во время восстановления файла возникли ошибки:\n\n$1",

# Namespace form on various pages
'namespace'      => 'Пространство имён:',
'invert'         => 'Обратить выделенное',
'blanknamespace' => '(Основное)',

# Contributions
'contributions' => 'Вклад участника',
'mycontris'     => 'Мой вклад',
'contribsub2'   => 'Вклад $1 ($2)',
'nocontribs'    => 'Изменений, соответствующих заданным условиям, найдено не было.',
'ucnote'        => 'Ниже приводятся последние <strong>$1</strong> изменений, сделанных этим участником за последние <strong>$2</strong> дня(ей).',
'uclinks'       => 'Просмотреть $1 {{plural:$1|последнее изменение|последних изменения|последних изменений}}; просмотреть за {{plural:$2|последний|последние|последние}} $2 {{plural:$2|день|дня|дней}}.',
'uctop'         => ' (последняя)',
'month'         => 'За год (и ранее):',
'year'          => 'За месяц (и ранее):',

'sp-contributions-newest'      => 'Новейшие',
'sp-contributions-oldest'      => 'Старейшие',
'sp-contributions-newer'       => 'Более новые $1',
'sp-contributions-older'       => 'Более старые $1',
'sp-contributions-newbies'     => 'Показать только вклад, сделанный с новых учётных записей',
'sp-contributions-newbies-sub' => 'С новых учётных записей',
'sp-contributions-blocklog'    => 'Журнал блокировок',
'sp-contributions-search'      => 'Поиск вклада',
'sp-contributions-username'    => 'IP-адрес или имя участника:',
'sp-contributions-submit'      => 'Найти',

'sp-newimages-showfrom' => 'Показать новые изображения, начиная с $1',

# What links here
'whatlinkshere'       => 'Ссылки сюда',
'whatlinkshere-title' => 'Страницы, ссылающиеся на $1',
'whatlinkshere-page'  => 'Страница:',
'linklistsub'         => '(Список ссылок)',
'linkshere'           => "Следующие страницы ссылаются на '''[[:$1]]''':",
'nolinkshere'         => "На страницу '''[[:$1]]''' отсутствуют ссылки с других страниц.",
'nolinkshere-ns'      => "В выбранном пространстве имён нет страниц ссылающихся на '''[[:$1]]'''.",
'isredirect'          => 'страница-перенаправление',
'istemplate'          => 'включение',
'whatlinkshere-prev'  => '{{PLURAL:$1|предыдущая|предыдущие|предыдущие}} $1',
'whatlinkshere-next'  => '{{PLURAL:$1|следующая|следующие|следующие}} $1',
'whatlinkshere-links' => '← ссылки',

# Block/unblock
'blockip'                     => 'Заблокировать',
'blockiptext'                 => 'Используйте форму ниже, чтобы заблокировать возможность записи с определённого IP-адреса.
Это может быть сделано только для предотвращения вандализма и только в соответствии с
правилами изложенными в разделе [[{{MediaWiki:Policy-url}}]].
Ниже укажите конкретную причину (к примеру, процитируйте некоторые страницы с признаками вандализма).',
'ipaddress'                   => 'IP-адрес:',
'ipadressorusername'          => 'IP-адрес или имя участника:',
'ipbexpiry'                   => 'Закончится через:',
'ipbreason'                   => 'Причина:',
'ipbreasonotherlist'          => 'Другая причина',
'ipbreason-dropdown'          => '
* Стандартные причины блокировок 
** Вставка ложной информации
** Удаление содержимого страниц
** Спам-ссылки на внешние сайты
** Добавление бессмысленного текста/мусора
** Угрозы, преследование участников
** Злоупотребление несколькими учётными записями
** Неприемлемое имя участника',
'ipbanononly'                 => 'Блокировать только анонимных участников',
'ipbcreateaccount'            => 'Запретить создание новых учётных записей',
'ipbemailban'                 => 'Запретить участнику отправлять письма по электронной почте',
'ipbenableautoblock'          => 'Автоматически блокировать использованные участником IP-адреса',
'ipbsubmit'                   => 'Заблокировать этот адрес/участника',
'ipbother'                    => 'Другое время:',
'ipboptions'                  => '15 минут:15 minutes,2 часа:2 hours,6 часов:6 hours,12 часов:12 hours,1 день:1 day,3 дня:3 days,1 неделю:1 week,2 недели:2 weeks,1 месяц:1 month,3 месяца:3 months,6 месяцев:6 months,1 год:1 year,бессрочно:infinite',
'ipbotheroption'              => 'иное',
'ipbotherreason'              => 'Другая или дополнительная причина:',
'ipbhidename'                 => 'Скрыть имя участника или IP-адрес из журнала блокировок, списка заблокированных и общего списка участников.',
'badipaddress'                => 'IP-адрес записан в неправильном формате, или участника с таким именем не существует.',
'blockipsuccesssub'           => 'Блокировка произведена',
'blockipsuccesstext'          => '[[{{ns:Special}}:Contributions/$1|«$1»]] заблокирован.
<br />См. [[{{ns:special}}:Ipblocklist|список заблокированных IP-адресов]].',
'ipb-edit-dropdown'           => 'Редактировать список причин блокировки',
'ipb-unblock-addr'            => 'Разблокировать $1',
'ipb-unblock'                 => 'Разблокировать участника или IP-адрес',
'ipb-blocklist-addr'          => 'Показать действующие блокировки для $1',
'ipb-blocklist'               => 'Показать действующие блокировки',
'unblockip'                   => 'Разблокировать IP-адрес',
'unblockiptext'               => 'Используйте форму ниже, чтобы восстановить возможность записи с ранее заблокированного
IP-адреса.',
'ipusubmit'                   => 'Разблокировать этот адрес',
'unblocked'                   => '[[{{ns:user}}:$1|$1]] разблокирован.',
'unblocked-id'                => 'Блокировка $1 была снята',
'ipblocklist'                 => 'Список заблокированных IP-адресов и имён участников',
'ipblocklist-legend'          => 'Поиск заблокированного участника',
'ipblocklist-username'        => 'Имя участника или IP-адрес:',
'ipblocklist-submit'          => 'Найти',
'blocklistline'               => '$1, $2 заблокировал $3 ($4)',
'infiniteblock'               => 'бессрочная блокировка',
'expiringblock'               => 'блокировка завершится $1',
'anononlyblock'               => 'только анонимов',
'noautoblockblock'            => 'автоблокировка отключена',
'createaccountblock'          => 'создание учётных записей заблокировано',
'emailblock'                  => 'письма запрещены',
'ipblocklist-empty'           => 'Список блокировок пуст.',
'ipblocklist-no-results'      => 'Заданный IP-адрес или имя участника не заблокированы.',
'blocklink'                   => 'заблокировать',
'unblocklink'                 => 'разблокировать',
'contribslink'                => 'вклад',
'autoblocker'                 => 'Автоблокировка из-за совпадения вашего IP-адреса с $1. Причина блокировки адреса — «$2».',
'blocklogpage'                => 'Журнал блокировок',
'blocklogentry'               => 'заблокировал [[$1]] на период $2 $3',
'blocklogtext'                => 'Журнал блокирования и разблокирования участников. Автоматически блокируемые IP-адреса здесь не указываются. См. [[{{ns:special}}:Ipblocklist|Список текущих запретов и блокировок]].',
'unblocklogentry'             => 'разблокировал $1',
'block-log-flags-anononly'    => 'только анонимные пользователи',
'block-log-flags-nocreate'    => 'запрещена регистрация учётных записей',
'block-log-flags-noautoblock' => 'автоблокировка отключена',
'block-log-flags-noemail'     => 'письма запрещены',
'range_block_disabled'        => 'Администраторам запрещено блокировать диапазоны.',
'ipb_expiry_invalid'          => 'Недопустимый период действия.',
'ipb_already_blocked'         => '«$1» уже заблокирован.',
'ipb_cant_unblock'            => 'Ошибка. Не найдена блокировка с ID $1. Возможно, она уже была снята.',
'ip_range_invalid'            => 'Недопустимый диапазон IP-адресов.\n',
'blockme'                     => 'Заблокируй меня',
'proxyblocker'                => 'Блокировка прокси',
'proxyblocker-disabled'       => 'Функция отключена.',
'proxyblockreason'            => 'Ваш IP-адрес заблокирован потому что это открытый прокси. Пожалуйста, свяжитесь с вашим интернет-провайдером  или службой поддержки и сообщите им об этой серьёзной проблеме безопасности.',
'proxyblocksuccess'           => 'Выполнено.',
'sorbsreason'                 => 'Ваш IP-адрес числится как открытый прокси в DNSBL.',
'sorbs_create_account_reason' => 'Ваш IP-адрес числится как открытый прокси в DNSBL. Вы не можете создать учётную запись.',

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
'lockdbsuccesstext'   => 'База данных проекта была заблокирована.
<br />Не забудьте [[{{ns:special}}:Unlockdb|убрать блокировку]] после завершения процедуры обслуживания.',
'unlockdbsuccesstext' => 'База данных проекта была разблокирована.',
'lockfilenotwritable' => 'Нет права на запись в файл блокировки базы данных. Чтобы заблокировать или разблокировать БД, веб-сервер должен иметь разрешение на запись в этот файл.',
'databasenotlocked'   => 'База данных не была заблокирована.',

# Move page
'movepage'                => 'Переименовать страницу',
'movepagetext'            => "Воспользовавшись формой ниже, вы переименуете страницу, одновременно переместив на новое место её журнал изменений.
Старое название станет перенаправлением на новое название.
Ссылки на старое название не будут изменены (пожалуйста, проверьте наличие [[{{ns:special}}:DoubleRedirects|двойных]] и [[{{ns:special}}:BrokenRedirects|разорванных]] перенаправлений).
Вы обязаны убедиться в том, что ссылки и далее указывают туда, куда предполагалось.

Обратите внимание, что страница '''не будет''' переименована, если страница с новым названием уже существует (кроме случаев, если она является перенаправлением или пуста и не имеет истории правок). Это означает, что вы можете переименовать страницу обратно в то название, которое у него только что было, если вы переименовали по ошибке, но вы не можете случайно затереть существующую страницу.

'''ПРЕДУПРЕЖДЕНИЕ!'''
Переименование может привести к масштабным и неожиданным изменениям для ''популярных'' страниц. Пожалуйста, прежде чем вы продолжите, убедитесь, что вы понимаете все возможные последствия.",
'movepagetalktext'        => "Присоединённая страница обсуждения, если таковая есть,
будет также автоматически переименована, '''кроме случаев, когда:'''

*Не пустая страница обсуждения уже существует под таким же именем или
*Вы не поставили галочку в поле ниже.

В этих случаях, вы будете вынуждены переместить или объединить страницы вручную,
если это нужно.",
'movearticle'             => 'Переименовать страницу',
'movenologin'             => 'Вы не представились системе',
'movenologintext'         => 'Вы должны [[{{ns:special}}:Userlogin|представиться системе]],
чтобы иметь возможность переименовать страницы.',
'movenotallowed'          => 'У вас не разрешения переименовывать странице в этой вики.',
'newtitle'                => 'Новое название',
'move-watch'              => 'Включить эту страницу в список наблюдения',
'movepagebtn'             => 'Переименовать страницу',
'pagemovedsub'            => 'Страница переименована',
'movepage-moved'          => "<big>'''«$1» переименована «$2»'''</big>", # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'Страница с таким именем уже существует или указанное вами название недопустимо.
Пожалуйста, выберите другое название.',
'talkexists'              => "'''Страница была переименована, но страница обсуждения
не может быть переименована, потому что страница с таким названием уже
существует. Пожалуйста, объедините их вручную.'''",
'movedto'                 => 'переименована в',
'movetalk'                => 'Переименовать соответствующую страницу обсуждения',
'talkpagemoved'           => 'Соответствующая страница обсуждения также переименована.',
'talkpagenotmoved'        => 'Соответствующая страница обсуждения <strong>не</strong> была переименована.',
'1movedto2'               => '«[[$1]]» переименована в «[[$2]]»',
'1movedto2_redir'         => '«[[$1]]» переименована в «[[$2]]» поверх перенаправления',
'movelogpage'             => 'Журнал переименований',
'movelogpagetext'         => 'Ниже представлен список переименованных страниц.',
'movereason'              => 'Причина',
'revertmove'              => 'откат',
'delete_and_move'         => 'Удалить и переименовать',
'delete_and_move_text'    => '==Требуется удаление==

Страница с именем [[$1|«$1»]] уже существует. Вы хотите её удалить, чтобы сделать возможным переименование?',
'delete_and_move_confirm' => 'Да, удалить эту страницу',
'delete_and_move_reason'  => 'Удалено для возможности переименования',
'selfmove'                => 'Невозможно переименовать страницу: исходное и новое имя страницы совпадают.',
'immobile_namespace'      => 'Невозможно переименовать страницу: новое или старое имя содержит зарезервированное служебное слово.',

# Export
'export'            => 'Экспортирование статей',
'exporttext'        => 'Вы можете экспортировать текст и журнал изменений конкретной страницы или набора страниц в XML, который потом может быть [[Special:Import|импортирован]] в другой вики-проект, работающий на программном обеспечении MediaWiki.

Чтобы экспортировать статьи, введите их наименования в поле редактирования, одно название на строку, и выберите хотите ли вы экспортировать всю историю изменений статей или только последние версии статей.

Вы также можете использовать специальный адрес для экспорта только последней версии. Например для страницы [[{{MediaWiki:Mainpage}}]] это будет адрес [[{{ns:Special}}:Export/{{MediaWiki:Mainpage}}]].',
'exportcuronly'     => 'Включать только текущую версию, без полной предыстории',
'exportnohistory'   => "----
'''Замечание:''' экспорт полной истории изменений страниц отключен из-за проблем с производительностью.",
'export-submit'     => 'Экспортировать',
'export-addcattext' => 'Добавить страницы из категории:',
'export-addcat'     => 'Добавить',
'export-download'   => 'Предложить сохранить как файл',

# Namespace 8 related
'allmessages'               => 'Системные сообщения',
'allmessagesname'           => 'Сообщение',
'allmessagesdefault'        => 'Текст по умолчанию',
'allmessagescurrent'        => 'Текущий текст',
'allmessagestext'           => 'Ниже представлен список системных сообщений, доступных в пространстве имён «MediaWiki».',
'allmessagesnotsupportedDB' => "'''{{ns:special}}:Allmessages''' не поддерживается, так как отключена опция '''wgUseDatabaseMessages'''.",
'allmessagesfilter'         => 'Фильтр в формате регулярного выражения:',
'allmessagesmodified'       => 'Показать только изменённые',

# Thumbnails
'thumbnail-more'           => 'Увеличить',
'missingimage'             => '<strong>Изображение не найдено</strong><br /><em>$1</em>\n',
'filemissing'              => 'Файл не найден',
'thumbnail_error'          => 'Ошибка создания миниатюры: $1',
'djvu_page_error'          => 'Номер страницы DjVu вне досягаемости',
'djvu_no_xml'              => 'Невозможно получить XML для DjVu',
'thumbnail_invalid_params' => 'Ошибочный параметр миниатюры',
'thumbnail_dest_directory' => 'Невозможно создать целевую директорию',

# Special:Import
'import'                     => 'Импортирование страниц',
'importinterwiki'            => 'Межвики импорт',
'import-interwiki-text'      => 'Укажите вики и название импортируемой страницы.
Даты изменений и имена авторов будут сохранены.
Все операции межвики импорта регистрируются в [[{{ns:special}}:Log/import|соответствующем журнале]].',
'import-interwiki-history'   => 'Копировать всю историю изменений этой страницы',
'import-interwiki-submit'    => 'Импортировать',
'import-interwiki-namespace' => 'Помещать страницы в пространство имён:',
'importtext'                 => 'Пожалуйста, экспортируйте страницу из исходной вики используя инструмент Special:Export, сохраните файл на диск, а затем загрузите его сюда.',
'importstart'                => 'Импортирование страниц…',
'import-revision-count'      => '$1 {{PLURAL:$1|версия|версии|версий}}',
'importnopages'              => 'Нет страниц для импортирования.',
'importfailed'               => 'Не удалось импортировать: $1',
'importunknownsource'        => 'Неизвестный тип импортируемой страницы',
'importcantopen'             => 'Невозможно открыть импортируемый файл',
'importbadinterwiki'         => 'Неправильная интервики-ссылка',
'importnotext'               => 'Текст отсутствует',
'importsuccess'              => 'Импортировано выполнено!',
'importhistoryconflict'      => 'Конфликт существующих версий (возможно, эта страница уже была импортирована)',
'importnosources'            => 'Не был выбран источник межвики импорта, прямая загрузка истории изменений отключена.',
'importnofile'               => 'Файл для импорта не был загружен.',
'importuploaderror'          => 'Ошибка загрузки файла для импорта, возможно размер файла превышает установленную норму.',

# Import log
'importlogpage'                    => 'Журнал импорта',
'importlogpagetext'                => 'Импортирование администраторами страниц с историей изменений из других вики.',
'import-logentry-upload'           => '«[[$1]]» — импорт из файла',
'import-logentry-upload-detail'    => '$1 версий',
'import-logentry-interwiki'        => '«$1» — межвики импорт',
'import-logentry-interwiki-detail' => '$1 версий из $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Моя страница участника',
'tooltip-pt-anonuserpage'         => 'Страница участника для моего IP',
'tooltip-pt-mytalk'               => 'Моя страница обсуждений',
'tooltip-pt-anontalk'             => 'Страница обсуждений для моего IP',
'tooltip-pt-preferences'          => 'Мои настройки',
'tooltip-pt-watchlist'            => 'Список страниц моего наблюдения',
'tooltip-pt-mycontris'            => 'Мой вклад',
'tooltip-pt-login'                => 'Здесь можно зарегистрироваться в системе, но это необязательно',
'tooltip-pt-anonlogin'            => 'Здесь можно зарегистрироваться в системе, но это необязательно',
'tooltip-pt-logout'               => 'Завершить зарегистрированный сеанс',
'tooltip-ca-talk'                 => 'Обсуждение статьи',
'tooltip-ca-edit'                 => 'Эту страницу можно изменять. Используйте, пожалуйста, предварительный просмотр перед сохранением',
'tooltip-ca-addsection'           => 'Добавить комментарий к обсуждению',
'tooltip-ca-viewsource'           => 'Эта страница защищена от изменений, но вы можете посмотреть и скопировать её исходный текст',
'tooltip-ca-history'              => 'Журнал изменений страницы',
'tooltip-ca-protect'              => 'Защитить страницу от изменений',
'tooltip-ca-delete'               => 'Удалить эту страницу',
'tooltip-ca-undelete'             => 'Восстановить исправления страницы, сделанные до того, как она была удалена',
'tooltip-ca-move'                 => 'Переименовать страницу',
'tooltip-ca-watch'                => 'Добавить эту страницу в ваш список наблюдения',
'tooltip-ca-unwatch'              => 'Удалить эту страницу из вашего списка наблюдения',
'tooltip-search'                  => 'Искать это слово',
'tooltip-search-go'               => 'Перейти к странице, имеющей в точности такое название',
'tooltip-search-fulltext'         => 'Нацти страницу, содержащую указанный текст',
'tooltip-p-logo'                  => 'Заглавная страница',
'tooltip-n-mainpage'              => 'Перейти на заглавную страницу',
'tooltip-n-portal'                => 'О проекте, о том, что вы можете сделать, где что находится',
'tooltip-n-currentevents'         => 'Список текущих событий',
'tooltip-n-recentchanges'         => 'Список последних изменений',
'tooltip-n-randompage'            => 'Посмотреть случайную страницу',
'tooltip-n-help'                  => 'Справочник по проекту «{{SITENAME}}»',
'tooltip-n-sitesupport'           => 'Поддержите проект',
'tooltip-t-whatlinkshere'         => 'Список всех страниц, которые ссылаются на эту страницу',
'tooltip-t-recentchangeslinked'   => 'Последние изменения в страницах, на которые ссылается эта страница',
'tooltip-feed-rss'                => 'Трансляция в RSS для этой страницы',
'tooltip-feed-atom'               => 'Трансляция в Atom для этой страницы',
'tooltip-t-contributions'         => 'Список страниц, которые изменял этот участник',
'tooltip-t-emailuser'             => 'Отправить письмо этому участнику',
'tooltip-t-upload'                => 'Загрузить изображения или мультимедиа-файлы',
'tooltip-t-specialpages'          => 'Список служебных страниц',
'tooltip-t-print'                 => 'Версия для печати этой страницы',
'tooltip-t-permalink'             => 'Постоянная ссылка на эту версию страницы',
'tooltip-ca-nstab-main'           => 'Содержание статьи',
'tooltip-ca-nstab-user'           => 'Персональная страница участника',
'tooltip-ca-nstab-media'          => 'Медиа-файл',
'tooltip-ca-nstab-special'        => 'Это служебная страница, она недоступна для редактирования',
'tooltip-ca-nstab-project'        => 'Страница проекта',
'tooltip-ca-nstab-image'          => 'Страница изображения',
'tooltip-ca-nstab-mediawiki'      => 'Страница сообщения MediaWiki',
'tooltip-ca-nstab-template'       => 'Страница шаблона',
'tooltip-ca-nstab-help'           => 'Страница справки',
'tooltip-ca-nstab-category'       => 'Страница категории',
'tooltip-minoredit'               => 'Отметить это изменение как незначительное',
'tooltip-save'                    => 'Сохранить ваши изменения',
'tooltip-preview'                 => 'Предварительный просмотр страницы, пожалуйста, используйте перед сохранением!',
'tooltip-diff'                    => 'Показать изменения, сделанные по отношению к исходному тексту.',
'tooltip-compareselectedversions' => 'Посмотреть разницу между двумя выбранными версиями этой страницы.',
'tooltip-watch'                   => 'Добавить текущую страницу в список наблюдения',
'tooltip-recreate'                => 'Восстановить страницу несмотря на то, что она была удалена',
'tooltip-upload'                  => 'Начать загрузку',

# Stylesheets
'common.css'   => '/** Размещённый здесь CSS будет применяться ко всем темам оформления */',
'monobook.css' => '/* Размещённый здесь CSS будет применяться к теме оформления Monobook */

/*
Это нужно чтобы в окошке поиска кнопки не разбивались на 2 строки
к сожалению в main.css для кнопки Go прописаны паддинги .5em.
Что хорошо для "Go" плохо для "Перейти" --st0rm
*/

#searchGoButton {
    padding-left: 0em;
    padding-right: 0em;
    font-weight: bold;
}',

# Scripts
'common.js'   => '/* Размещённый здесь код JavaScript будет загружен всем пользователям при обращении к какой-либо странице */',
'monobook.js' => '/* Устарело. Используйте [[MediaWiki:common.js]] */',

# Metadata
'nodublincore'      => 'Метаданные Dublin Core RDF запрещены для этого сервера.',
'nocreativecommons' => 'Метаданные Creative Commons RDF запрещены для этого сервера.',
'notacceptable'     => "Вики-сервер не может предоставить данные в формате, который мог бы прочитать ваш браузер.<br />
The wiki server can't provide data in a format your client can read.",

# Attribution
'anonymous'        => 'Анонимные пользователи {{grammar:genitive|{{SITENAME}}}}',
'siteuser'         => 'Участник {{grammar:genitive|{{SITENAME}}}} $1',
'lastmodifiedatby' => 'Эта страница последний раз была изменена $2, $1 участником $3.', # $1 date, $2 time, $3 user
'and'              => 'и',
'othercontribs'    => 'Основано на работе $1.',
'others'           => 'другие',
'siteusers'        => 'Участник(и) {{grammar:genitive|{{SITENAME}}}} $1',
'creditspage'      => 'Благодарности',
'nocredits'        => 'Нет списка участников для этой статьи',

# Spam protection
'spamprotectiontitle'    => 'Спам-фильтр',
'spamprotectiontext'     => 'Страница, которую вы пытаетесь сохранить, заблокирована спам-фильтром. Вероятнее всего она содержит ссылку на внешний сайт.',
'spamprotectionmatch'    => 'Следующее сообщение было получено от спам-фильтра: $1.',
'subcategorycount'       => 'В этой категории $1 {{PLURAL:$1|подкатегория|подкатегории|подкатегорий}}.',
'categoryarticlecount'   => 'В этой категории $1 {{PLURAL:$1|статья|статьи|статей}}.',
'category-media-count'   => 'В этой категории $1 {{PLURAL:$1|файл|файла|файлов}}.',
'listingcontinuesabbrev' => '(продолжение)',
'spambot_username'       => 'Чистка спама',
'spam_reverting'         => 'Откат к последней версии, не содержащей ссылки на $1',
'spam_blanking'          => 'Все версии содержат ссылки на $1, очистка',

# Info page
'infosubtitle'   => 'Информация о странице',
'numedits'       => 'Число правок (статья): $1',
'numtalkedits'   => 'Число правок (страница обсуждения): $1',
'numwatchers'    => 'Число наблюдателей: $1',
'numauthors'     => 'Число различных авторов (статья): $1',
'numtalkauthors' => 'Число различных авторов (страница обсуждения): $1',

# Math options
'mw_math_png'    => 'Всегда генерировать PNG',
'mw_math_simple' => 'HTML в простых случаях, иначе PNG',
'mw_math_html'   => 'HTML, если возможно, иначе PNG',
'mw_math_source' => 'Оставить в разметке ТеХ (для текстовых браузеров)',
'mw_math_modern' => 'Как рекомендуется для современных браузеров',
'mw_math_mathml' => 'MathML, если возможно (экспериментальная опция)',

# Patrolling
'markaspatrolleddiff'                 => 'Отметить как проверенную',
'markaspatrolledtext'                 => 'Отметить эту статью как проверенную',
'markedaspatrolled'                   => 'Отмечена как проверенная',
'markedaspatrolledtext'               => 'Выбранная версия отмечена как проверенная.',
'rcpatroldisabled'                    => 'Патрулирование последних изменений запрещено',
'rcpatroldisabledtext'                => 'Возможность патрулирования последних изменений в настоящее время отключена.',
'markedaspatrollederror'              => 'Невозможно отметить как проверенную',
'markedaspatrollederrortext'          => 'Вы должны указать версию, которая будет отмечена как проверенная.',
'markedaspatrollederror-noautopatrol' => 'Вам не разрешено отмечать собственные правки как проверенные.',

# Patrol log
'patrol-log-page' => 'Журнал патрулирования',
'patrol-log-line' => 'помечена $1 из $2 патрулирована $3',
'patrol-log-auto' => '(автоматический)',

# Image deletion
'deletedrevision'                 => 'Удалена старая версия $1',
'filedeleteerror-short'           => 'Ошибка удаления файла: $1',
'filedeleteerror-long'            => "Во время удаления файла возникли ошибки:\n\n$1",
'filedelete-missing'              => 'Файл «$1» не может быть удалён, так как его не существует.',
'filedelete-old-unregistered'     => 'Указанной версии файла «$1» не существует в базе данных.',
'filedelete-current-unregistered' => 'Указанного файла «$1» не существует в базе данных.',
'filedelete-archive-read-only'    => 'Архивная директория «$1» не доступна для записи веб-серверу.',

# Browsing diffs
'previousdiff' => '← К предыдущему изменению',
'nextdiff'     => 'К следующему изменению →',

# Media information
'mediawarning'         => "'''Внимание''': этот файл может содержать вредоносный программный код, выполнение которого способно подвергнуть риску вашу систему. <hr />",
'imagemaxsize'         => 'Ограничивать изображения на странице изображений до:',
'thumbsize'            => 'Размер уменьшенной версии изображения:',
'widthheight'          => '$1 × $2',
'widthheightpage'      => '$1 × $2, $3 страницы',
'file-info'            => '(размер файла: $1, MIME-тип: $2)',
'file-info-size'       => '($1 × $2 пикселов, размер файла: $3, MIME-тип: $4)',
'file-nohires'         => '<small>Нет версии с большим разрешением.</small>',
'svg-long-desc'        => '(SVG-файл, номинально $1 × $2 пикселов, размер файла: $3)',
'show-big-image'       => 'Изображение в более высоком разрешении',
'show-big-image-thumb' => '<small>Размер при предпросмотре: $1 × $2 пикселов</small>',

# Special:Newimages
'newimages'    => 'Галерея новых файлов',
'showhidebots' => '($1 ботов)',
'noimages'     => 'Изображения отсутствуют.',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'video-dims'     => '$1, $2 × $3',
'seconds-abbrev' => 'с',
'minutes-abbrev' => 'м',
'hours-abbrev'   => 'ч',

# Bad image list
'bad_image_list' => 'Формат должен быть следующим:

Будут учитываться только элементы списка (строки, начинающиеся на символ *). Первая ссылка строки должна быть ссылкой на запрещённое для вставки изображение.
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

'exif-orientation-1' => 'Нормальная', # 0th row: top; 0th column: left
'exif-orientation-2' => 'Отражено по горизонтали', # 0th row: top; 0th column: right
'exif-orientation-3' => 'Повёрнуто на 180°', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Отражено по вертикали', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Повёрнуто на 90° против часовой стрелки и отражено по вертикали', # 0th row: left; 0th column: top
'exif-orientation-6' => 'Повёрнуто на 90° по часовой стрелке', # 0th row: right; 0th column: top
'exif-orientation-7' => 'Повёрнуто на 90° по часовой стрелке и отражено по вертикали', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Повёрнуто на 90° против часовой стрелки', # 0th row: left; 0th column: bottom

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

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'км/час',
'exif-gpsspeed-m' => 'миль/час',
'exif-gpsspeed-n' => 'узлов',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'истинный',
'exif-gpsdirection-m' => 'магнитный',

# External editor support
'edit-externally'      => 'Редактировать этот файл, используя внешнюю программу',
'edit-externally-help' => 'Подробности см. на странице [http://meta.wikimedia.org/wiki/Help:External_editors Meta:Help:External_editors].',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'все',
'imagelistall'     => 'все',
'watchlistall2'    => 'все',
'namespacesall'    => 'все',
'monthsall'        => 'все',

# E-mail address confirmation
'confirmemail'            => 'Подтверждение адреса электронной почты',
'confirmemail_noemail'    => 'Вы не задали адрес электронной почты в [[{{ns:special}}:Preferences|настройках]], либо он некорректен.',
'confirmemail_text'       => 'Вики-движок требует подтверждения адреса электронной почты перед тем, как начать с ним работать.
Нажмите на кнопку, чтобы на указанный адрес было отправлено письмо, содержащее ссылку на специальную страницу, после открытия которой в браузере адрес электронной почты будет считаться подтверждённым.',
'confirmemail_pending'    => '<div class="error">
Письмо с кодом подтверждения уже было отправлено.
Если вы недавно создали учётную запись, то, вероятно,
вам следует подождать несколько минут пока письмо придёт перед тем, как запросить код ещё раз.
</div>',
'confirmemail_send'       => 'Отправить письмо с запросом на подтверждение',
'confirmemail_sent'       => 'Письмо с запросом на подтверждение отправлено.',
'confirmemail_oncreate'   => 'Письмо с кодом подтверждения было отправлено на указанный вами почтовый ящик.
Данный код не требуется для входа в систему, однако вы должны указать его,
прежде чем будет разрешено использование возможностей электронной почты в этом проекте.',
'confirmemail_sendfailed' => 'Невозможно отправить письмо с запросом на подтверждение. Проверьте правильность адреса электронной почты.

Ответ сервера: $1',
'confirmemail_invalid'    => 'Неправильный код подтверждения или срок действия кода истёк.',
'confirmemail_needlogin'  => 'Вы должны $1 для подтверждения вашего адреса электронной почты.',
'confirmemail_success'    => 'Ваш адрес электронной почты подтверждён.',
'confirmemail_loggedin'   => 'Ваш адрес электронной почты подтверждён.',
'confirmemail_error'      => 'Во время процедуры подтверждения адреса электронной почты произошла ошибка.',
'confirmemail_subject'    => '{{SITENAME}}:Запрос на подтверждение адреса эл. почты',
'confirmemail_body'       => 'Кто-то с IP-адресом $1 зарегистрировал на сервере проекта {{SITENAME}} учётную запись
«$2», указав ваш адрес электронной почты.

Чтобы подтвердить, что вы разрешаете использовать ваш адрес электронной почты  в этом проекте, откройте в браузере приведённую ниже ссылку (это нужно сделать до $4):

$3

Если вы не отправляли подобного запроса, просто проигнорируйте данное письмо.',

# Scary transclusion
'scarytranscludedisabled' => '[«Interwiki transcluding» отключён]',
'scarytranscludefailed'   => '[К сожалению, не удалось обращение к шаблону $1]',
'scarytranscludetoolong'  => '[К сожалению, URL слишком длинный]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">
Trackback для этой статьи:<br />
$1
</div>',
'trackbackremove'   => ' ([$1 удалить])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => 'Trackback был удалён.',

# Delete conflict
'deletedwhileediting' => 'Внимание: пока вы редактировали эту страницу она была удалена!',
'confirmrecreate'     => "Участник [[{{ns:user}}:$1|$1]] ([[{{ns:user_talk}}:$1|обсуждение]]) удалил эту страницу после того, как вы начали её редактировать, причина удаления:
: ''$2''
Пожалуйста, подтвердите, что вы хотите восстановить эту страницу.",
'recreate'            => 'Создать заново',

'unit-pixel' => ' пикс.',

# HTML dump
'redirectingto' => 'Перенаправление на страницу [[$1]]…',

# action=purge
'confirm_purge'        => 'Очистить кеш этой страницы?

$1',
'confirm_purge_button' => 'OK',

# AJAX search
'searchcontaining' => 'Поиск статей, содержащих «$1».',
'searchnamed'      => 'Поиск статей с названием $1.',
'articletitles'    => 'Статьи, начинающиеся с «$1»',
'hideresults'      => 'Скрыть результаты',

# Multipage image navigation
'imgmultipageprev'   => '← предыдущая страница',
'imgmultipagenext'   => 'следующая страница →',
'imgmultigo'         => 'Перейти!',
'imgmultigotopre'    => 'Перейти на страницу',
'imgmultiparseerror' => 'Файл изображения повреждён или ошибочен, невозможно получить список страниц.',

# Table pager
'ascending_abbrev'         => 'возр',
'descending_abbrev'        => 'убыв',
'table_pager_next'         => 'Следующая страница',
'table_pager_prev'         => 'Предыдущая страница',
'table_pager_first'        => 'Первая страница',
'table_pager_last'         => 'Последняя страница',
'table_pager_limit'        => 'Показать $1 элементов на странице',
'table_pager_limit_submit' => 'Выполнить',
'table_pager_empty'        => 'Не найдено',

# Auto-summaries
'autosumm-blank'   => 'Полностью удалено содержимое страницы',
'autosumm-replace' => 'Содержимое страницы заменено на «$1»',
'autoredircomment' => 'Перенаправление на [[$1]]',
'autosumm-new'     => 'Новая: $1',

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
'lag-warn-normal' => 'Изменения, сделанные менее чем $1 {{plural:$1|секунду|секунды|секунд}} назад, могут быть не показаны в этом списке.',
'lag-warn-high'   => 'Из-за большого отставания в синхронизации серверов баз данных изменения, сделанные менее чем $1 {{plural:$1|секунду|секунды|секунд}} назад, могут быть не показаны в этом списке.',

# Watchlist editor
'watchlistedit-numitems'       => 'Ваш список наблюдения содержит {{PLURAL:$1|$1 запись|$1 записи|$1 записей}}, исключая страницы обсуждений.',
'watchlistedit-noitems'        => 'Ваш список наблюдения не содержит записей.',
'watchlistedit-normal-title'   => 'Изменение списка наблюдения',
'watchlistedit-normal-legend'  => 'Удаление записей из списка наблюдения',
'watchlistedit-normal-explain' => 'Ниже перечислены страницы, находящиеся в вашем списке наблюдения. Для удаления записи отметьте
        квадратик рядом с ней и нажмите кнопку «Удалить записи». Вы также можете [[Special:Watchlist/raw|править «сырой» список]],
        или [[Special:Watchlist/clear|удалить все записи]].',
'watchlistedit-normal-submit'  => 'Удалить записи',
'watchlistedit-normal-done'    => '{{PLURAL:$1|$1$1|$1 запись была удалена|$1 записи были удалены|$1 записей были удалены}} из вашего списка наблюдения:',
'watchlistedit-raw-title'      => 'Изменение «сырого» списка наблюдения',
'watchlistedit-raw-legend'     => 'Изменение «сырого» списка наблюдения',
'watchlistedit-raw-explain'    => 'Ниже перечислены страницы, находящиеся в вашем списке наблюдения. Вы можете править список добавляя
	и удаляя из него строчки с названиями. После завершения правок нажмите кнопку «Сохранить список».
	Вы также можете использовать [[Special:Watchlist/edit|обычный способ изменения списка]].',
'watchlistedit-raw-titles'     => 'Записи:',
'watchlistedit-raw-submit'     => 'Сохранить список',
'watchlistedit-raw-done'       => 'Ваш список наблюдения сохранён.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|$1 запись была добавлена|$1 записи были добавлены|$1 записей были добавлены}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|$1 запись была удалена|$1 записи были удалены|$1 записей были удалены}}:',

# Watchlist editing tools
'watchlisttools-view'  => 'Изменения на страницах из списка',
'watchlisttools-edit'  => 'Смотреть/править список',
'watchlisttools-raw'   => 'Править как текст',

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
);

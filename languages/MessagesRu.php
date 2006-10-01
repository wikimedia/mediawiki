<?php
/** Russian (русский язык)
  *
  * Based on MessagesEn.php 16482 (2006-09-13)
  * and ru.wikipedia MediaWiki namespace (2006-09-13)
  *
  * You can contact Alexander Sigachov (alexander.sigachov на Google Mail)
  *
  */

$separatorTransformTable = array(
	',' => "\xc2\xa0",
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


$quickbarSettings = array(
	'Не показывать', 'Неподвижная слева', 'Неподвижная справа', 'Плавающая слева', 'Плавающая справа'
);

$skinNames = array(
	'standard' => 'Стандартное',
	'nostalgia' => 'Ностальгия',
	'cologneblue' => 'Кёльнская тоска',
	'davinci' => 'Да Винчи',
	'mono' => 'Моно',
	'monobook' => 'Моно-книга',
	'myskin' => 'Своё',
	'chick' => 'Цыпа'
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
	'nogallery'  			 => array( 0,    '__NOGALLERY__', '__БЕЗ_ГАЛЕРЕИ__'),
	'forcetoc'               => array( 0,    '__FORCETOC__',  '__ОБЯЗ_ОГЛ__'),
	'toc'                    => array( 0,    '__TOC__', '__ОГЛ__'),
	'noeditsection'          => array( 0,    '__NOEDITSECTION__', '__БЕЗ_РЕДАКТИРОВАНИЯ_РАЗДЕЛА__'),
	'start'                  => array( 0,    '__START__', '__НАЧАЛО__'),
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
	'pagename'               => array( 1,    'PAGENAME','НАЗВАНИЕ_СТРАНИЦЫ'),
	'pagenamee'              => array( 1,    'PAGENAMEE','НАЗВАНИЕ_СТРАНИЦЫ_2'),
	'namespace'              => array( 1,    'NAMESPACE','ПРОСТРАНСТВО_ИМЁН'),
	'namespacee'              => array( 1,    'NAMESPACEE','ПРОСТРАНСТВО_ИМЁН_2'),
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
	'end'                    => array( 0,    '__END__','__КОНЕЦ__'),
	'img_thumbnail'          => array( 1,    'thumbnail', 'thumb', 'мини'),
	'img_manualthumb'        => array( 1,    'thumbnail=$1', 'thumb=$1', 'мини=$1'),
	'img_right'              => array( 1,    'right','справа'),
	'img_left'               => array( 1,    'left','слева'),
	'img_none'               => array( 1,    'none', 'без'),
	'img_width'              => array( 1,    '$1px','$1пкс'),
	'img_center'             => array( 1,    'center', 'centre','центр'),
	'img_framed'             => array( 1,    'framed', 'enframed', 'frame','обрамить'),
	'img_page'               => array( 1,    'page=$1', 'page $1', 'страница=$1', 'страница $1' ),
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
	'anchorencode'                   => array( 0,    'ANCHORENCODE', 'КОДИРОВАТЬ_МЕТКУ'),
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
);

$linkTrail = '/^([a-zабвгдеёжзийклмнопрстуфхцчшщъыьэюя“»]+)(.*)$/sDu';

$messages = array(

# User preference toggles
'tog-underline' => 'Подчёркивать ссылки:',
'tog-highlightbroken' => 'Показывать несуществующие ссылки <a href="" class="new">вот так</a> (иначе вот так<a href="" class="internal">?</a>).',
'tog-justify'   => 'Выравнивать текст по ширине страницы',
'tog-hideminor' => 'Скрывать малозначимые правки в списке свежих изменений',
'tog-extendwatchlist' => 'Улучшенный список наблюдения',
'tog-usenewrc' => 'Улучшенный список свежих изменений (JavaScript)',
'tog-numberheadings' => 'Автоматически нумеровать заголовки',
'tog-showtoolbar'               => 'Показывать панель инструментов при редактировании (JavaScript)',
'tog-editondblclick' => 'Править статьи по двойному щелчку (JavaScript)',
'tog-editsection'               => 'Показывать ссылку «править» для каждой секции',
'tog-editsectiononrightclick'   => 'Править секции при правом щелчке мышью на заголовке (JavaScript)',
'tog-showtoc'                   => 'Показывать оглавление (для страниц более чем с 3 заголовками)',
'tog-rememberpassword' => 'Запоминать пароль между сеансами',
'tog-editwidth' => 'Поле редактирования во всю ширину окна браузера',
'tog-watchcreations' => 'По умолчанию добавлять созданные статьи в список наблюдения',
'tog-watchdefault' => 'По умолчанию добавлять новые и изменённые статьи в список наблюдения',
'tog-minordefault' => 'По умолчанию помечать изменения как малозначимые',
'tog-previewontop' => 'Показывать предпросмотр статьи до окна редактирования',
'tog-previewonfirst' => 'Предварительный просмотр по первому изменению',
'tog-nocache' => 'Запретить кэширование страниц',
'tog-enotifwatchlistpages'      => 'Уведомлять по эл. почте об изменениях страниц из списка наблюдения',
'tog-enotifusertalkpages'       => 'Уведомлять по эл. почте об изменении персональной страницы обсуждения',
'tog-enotifminoredits'          => 'Уведомлять по эл. почте даже при малозначительных изменениях',
'tog-enotifrevealaddr'          => 'Показывать мой почтовый адрес в сообщениях оповещения',
'tog-shownumberswatching'       => 'Показывать число участников, включивших страницу в свой список наблюдения',
'tog-fancysig' => 'Простая подпись (без автоматической ссылки)',
'tog-externaleditor' => 'Использовать по умолчанию внешний редактор',
'tog-externaldiff' => 'Использовать по умолчанию внешнюю программу сравнения версий',
'tog-showjumplinks' => 'Включить вспомогательные ссылки «перейти к»',
'tog-uselivepreview' => 'Использовать быстрый предварительный просмотр (JavaScript, экспериментально)',
'tog-autopatrol' => 'Отмечать сделанные правки как проверенные',
'tog-forceeditsummary' => 'Предупреждать, когда не указано краткое описание изменений',
'tog-watchlisthideown' => 'Скрывать мои правки из списка наблюдения',
'tog-watchlisthidebots' => 'Скрывать правки ботов из списка наблюдения',

'underline-always' => 'Всегда',
'underline-never' => 'Никогда',
'underline-default' => 'Использовать настройки браузера',

'skinpreview' => '(Предпросмотр)',

# dates
'sunday' => 'воскресенье',
'monday' => 'понедельник',
'tuesday' => 'вторник',
'wednesday' => 'среда',
'thursday' => 'четверг',
'friday' => 'пятница',
'saturday' => 'суббота',
'sun' => 'Вс',
'mon' => 'Пн',
'tue' => 'Вт',
'wed' => 'Ср',
'thu' => 'Чт',
'fri' => 'Пт',
'sat' => 'Сб',
'january' => 'январь',
'february' => 'февраль',
'march' => 'март',
'april' => 'апрель',
'may_long' => 'май',
'june' => 'июнь',
'july' => 'июль',
'august' => 'август',
'september' => 'сентябрь',
'october' => 'октябрь',
'november' => 'ноябрь',
'december' => 'декабрь',
'january-gen' => 'января',
'february-gen' => 'февраля',
'march-gen' => 'марта',
'april-gen' => 'апреля',
'may-gen' => 'мая',
'june-gen' => 'июня',
'july-gen' => 'июля',
'august-gen' => 'августа',
'september-gen' => 'сентября',
'october-gen' => 'октября',
'november-gen' => 'ноября',
'december-gen' => 'декабря',
'jan' => 'янв',
'feb' => 'фев',
'mar' => 'мар',
'apr' => 'апр',
'may' => 'мая',
'jun' => 'июн',
'jul' => 'июл',
'aug' => 'авг',
'sep' => 'сен',
'oct' => 'окт',
'nov' => 'ноя',
'dec' => 'дек',
# Bits of text used by many pages:
#
'categories' => '{{PLURAL:$1|Категория|Категории|Категории}}',
'category_header' => 'Статьи в категории «$1»',
'subcategories' => 'Подкатегории',


'linkprefix'            => '/^(.*?)(„|«)$/sD',
'mainpage'              => 'Заглавная страница',
'mainpagetext'  => '<big>Вики-движок «MediaWiki» успешно установлен.</big>',
'mainpagedocfooter' => 'Информацию по работе с этой вики можно найти в [http://meta.wikimedia.org/wiki/%D0%9F%D0%BE%D0%BC%D0%BE%D1%89%D1%8C:%D0%A1%D0%BE%D0%B4%D0%B5%D1%80%D0%B6%D0%B0%D0%BD%D0%B8%D0%B5 руководстве пользователя].

== Некоторые полезные ресурсы ==
* [http://www.mediawiki.org/wiki/Help:Configuration_settings Список возможных настроек];
* [http://www.mediawiki.org/wiki/Help:FAQ Часто задаваемые вопросы и ответы по MediaWiki];
* [http://mail.wikimedia.org/mailman/listinfo/mediawiki-announce Рассылка уведомлений о выходе новых версий MediaWiki].',

'portal'                => 'Сообщество',
'portal-url'            => '{{ns:project}}:Портал сообщества',
'about'                 => 'Описание',
'aboutsite'      => 'Описание {{grammar:genitive|{{SITENAME}}}}',
'aboutpage'           => '{{ns:project}}:Описание',
'article' => 'Статья',
'help'                  => 'Справка',
'helppage'            => '{{ns:project}}:Справка',
'bugreports'  => 'Отчёт об ошибке',
'bugreportspage' => '{{ns:project}}:Отчёт об ошибке',
'sitesupport'   => 'Пожертвования', # To enable, something like 'Donations', '-' to disable
'sitesupport-url' => '{{ns:project}}:Пожертвования',
'faq'                   => 'ЧаВО',
'faqpage'             => '{{ns:project}}:ЧаВО',
'edithelp'            => 'Справка по редактированию',
'newwindow'           => '(в новом окне)',
'edithelppage'        => '{{ns:project}}:Справка по редактированию',
'cancel'                => 'Отменить',
'qbfind'                => 'Поиск',
'qbbrowse'              => 'Просмотреть',
'qbedit'                => 'Править',
'qbpageoptions' => 'Настройки страницы',
'qbpageinfo'    => 'Сведения о статье',
'qbmyoptions'   => 'Ваши настройки',
'qbspecialpages'        => 'Специальные страницы',
'moredotdotdot' => 'Далее…',
'mypage'                => 'Личная страница',
'mytalk'                => 'Моя страница обсуждения',
'anontalk'              => 'Обсуждение для этого IP-адреса',
'navigation' => 'Навигация',

# Metadata in edit box
'metadata_help' => 'Метаданные (пояснения см. на странице [[{{ns:project}}:Метаданные]]):',

'currentevents' => 'Текущие события',
'currentevents-url' => 'Текущие события',

'disclaimers' => 'Отказ от ответственности',
'disclaimerpage' => '{{ns:project}}:Отказ_от_ответственности',
'privacy' => 'Политика конфиденциальности',
'privacypage' => '{{ns:project}}:Политика_конфиденциальности',
'errorpagetitle' => 'Ошибка',
'returnto'            => 'Возврат к странице $1.',
'tagline'             => 'Материал из {{grammar:genitive|{{SITENAME}}}}.',
'help'                  => 'Справка',
'search'                => 'Поиск',
'searchbutton'          => 'Найти',
'go'            => 'Перейти',
'searcharticle'            => 'Перейти',
'history'             => 'История',
'history_short' => 'История',
'updatedmarker' => 'обновлено с моего последнего посещения',
'info_short'    => 'Информация',
'printableversion' => 'Версия для печати',
'permalink'     => 'Постоянная ссылка',
'print' => 'Печать',
'edit' => 'Править',
'editthispage'  => 'Править эту статью',
'delete' => 'Удалить',
'deletethispage' => 'Стереть её',
'undelete_short' => 'Восстановить $1 {{PLURAL:$1|правку|правки|правок}}',
'protect' => 'Защитить',
'protectthispage' => 'Защитить',
'unprotect' => 'Снять защиту',
'unprotectthispage' => 'Снять защиту',
'newpage' => 'Новая статья',
'talkpage'              => 'Обсуждение',
'specialpage' => 'Служебная страница',
'personaltools' => 'Личные инструменты',
'postcomment'   => 'Прокомментировать',
'articlepage'   => 'Просмотреть статью',
'talk' => 'Обсуждение',
'views' => 'Просмотры',
'toolbox' => 'Инструменты',
'userpage' => 'Просмотреть страницу участника',
'projectpage' => 'Просмотреть страницу проекта',
'imagepage' =>       'Просмотреть страницу изображения',
'mediawikipage' =>      'Показать страницу сообщения',
'templatepage' =>       'Просмотреть страницу шаблона',
'viewhelppage' =>       'Получить справку',
'categorypage' =>       'Просмотреть страницу категории',
'viewtalkpage' => 'Просмотреть обсуждение',
'otherlanguages' => 'На других языках',
'redirectedfrom' => '(Перенаправлено с $1)',
 'autoredircomment' => 'Перенаправление на [[$1]]',
'redirectpagesub' => 'Страница-перенаправление',
'lastmodifiedat'  => 'Последнее изменение этой страницы: $2, $1.',
'viewcount'             => 'К этой странице обращались $1 раз(а).',
'copyright'     => 'Содержимое доступно в соответствии с $1.',
'protectedpage' => 'Защищённая статья',
'jumpto' => 'Перейти к:',
'jumptonavigation' => 'навигация',
'jumptosearch' => 'поиск',

'badaccess' => 'Ошибка доступа',
'badaccess-group0' => 'Вы не можете выполнять запрошенное действие.',
'badaccess-group1' => 'Запрошенное действие могут выполнять только участники из группы $1.',
'badaccess-group2' => 'Запрошенное действие могут выполнять только участники из групп $1.',
'badaccess-groups' => 'Запрошенное действие могут выполнять только участники из групп $1.',

'versionrequired' => 'Требуется MediaWiki версии $1',
'versionrequiredtext' => 'Для работы с этой страницей требуется MediaWiki версии $1. См. [[Special:Version]].',

'ok'                    => 'OK',
'pagetitle'             => '$1 — {{SITENAME}}',
'retrievedfrom' => 'Получено с $1',
'youhavenewmessages' => 'Вы получили $1 ($2).',
'newmessageslink' => 'новые сообщения',
'newmessagesdifflink' => 'последнее изменение',
'editsection'=>'править',
'editold'=>'править',
'editsectionhint' => 'Править секцию: $1',
'toc' => 'Содержание',
'showtoc' => 'показать',
'hidetoc' => 'убрать',
'thisisdeleted' => 'Просмотреть или восстановить $1?',
'viewdeleted' => 'Просмотреть $1?',
'restorelink' => '{{PLURAL:$1|$1 удалённую правку|$1 удалённых правки|$1 удалённых правок}}',
'feedlinks' => 'В виде:',
'feed-invalid' => 'Неправильный тип канала для подписки.',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main' => 'Статья',
'nstab-user' => 'Участник',
'nstab-media' => 'Мультимедиа',
'nstab-special' => 'Служебная страница',
'nstab-project' => 'О проекте',
'nstab-image' => 'Файл',
'nstab-mediawiki' => 'Сообщение',
'nstab-template' => 'Шаблон',
'nstab-help' => 'Справка',
'nstab-category' => 'Категория',

# Main script and global functions
#
'nosuchaction'  => 'Такого действия нет',
'nosuchactiontext' => 'Действие, указанное в URL, не распознаётся программным обеспечением вики',
'nosuchspecialpage' => 'Такой специальной страницы нет',
'nospecialpagetext' => 'Запрошенной вами служебной страницы не существует. См. [[{{ns:special}}:Specialpages|список служебных страниц]].',

# General errors
#
'error'                 => 'Ошибка',
'databaseerror' => 'Ошибка базы данных',
'dberrortext'   => 'Обнаружена ошибка синтаксиса запроса к базе данных.
Последний запрос к базе данных:
<blockquote><tt>$1</tt></blockquote>
произошёл из функции <tt>«$2»</tt>.
MySQL возвратил ошибку <tt>«$3: $4»</tt>.',
'dberrortextcl' => 'Обнаружена ошибка синтаксиса запроса к базе данных.
Последний запрос к базе данных:
«$1»
произошёл из функции «$2».
MySQL возвратил ошибку «$3: $4».',
'noconnect'             => 'Извините, сейчас невозможно связаться с сервером базы данных из-за технических проблем.<br />
$1',
'nodb'                  => 'Невозможно выбрать базу данных $1',
'cachederror'           => 'Ниже представлена кэшированная копия запрошенной страницы; возможно, она устарела.',
'laggedslavemode'   => 'Внимание: страница может не содержать последних обновлений.',
'readonly'              => 'Запись в базу данных заблокирована',
'enterlockreason' => 'Укажите причину и намеченный срок блокировки.',
'readonlytext'  => 'Добавление новых статей и другие изменения базы данных сейчас заблокированы: вероятно, в связи с плановым обслуживанием.
Заблокировавший оператор оставил следующее разъяснение:
$1',
'missingarticle' => 'База данных не нашла текста статьи,
хотя должна была найти, по имени «$1».

Обычно это вызвано использованием устаревшей ссылки на журнал изменений или различий для статьи, которая была удалена.

Если дело не в этом, то скорее всего, вы обнаружили ошибку в программном обеспечении вики.
Пожалуйста, сообщите об этом администратору, указав URL.',
'readonly_lag' => 'База данных автоматически заблокирована от изменений на время пока вторичный сервер БД не синхронизируется с первичным.',
'internalerror' => 'Внутренняя ошибка',
'filecopyerror' => 'Невозможно скопировать файл «$1» в «$2».',
'filerenameerror' => 'Невозможно переименовать файл «$1» в «$2».',
'filedeleteerror' => 'Невозможно удалить файл «$1».',
'filenotfound'  => 'Невозможно найти файл «$1».',
'unexpected'    => 'Неподходящее значение: «$1»=«$2».',
'formerror'             => 'Ошибка: невозможно передать данные формы',
'badarticleerror' => 'Это действие не может быть выполнено на данной странице.',
'cannotdelete'  => 'Невозможно удалить указанную страницу или файл. Возможно, его уже удалил кто-то другой.',
'badtitle'              => 'Недопустимое название',
'badtitletext' => 'Запрашиваемое название статьи неправильно, пусто, либо неправильно указано междуязыковое или междувики название. Возможно, в названии используются недопустимые символы.',
'perfdisabled' => 'К сожалению, эта возможность временно недоступна в связи с загруженностью сервера.',
'perfdisabledsub' => 'Это — сохранённая копия от $1:', # obsolete?
'perfcached' => 'Следующие данные взяты из кэша и могут не содержать последних изменений.',
'perfcachedts' => 'Следующие данные взяты из кэша, последний раз он обновлялся в $1.',
'wrong_wfQuery_params' => 'Недопустимые параметры для функции wfQuery()<br />
Функция: $1<br />
Запрос: $2',
'viewsource' => 'Просмотр',
'viewsourcefor' => 'Страница «$1»',
'protectedtext' => 'Эта страница защищена от редактирования.

Вы можете просмотреть и скопировать исходный код этой страницы:',
'protectedinterface' => 'Эта страница содержит системное сообщение MediaWiki, её могут изменять только администраторы проекта.',
'editinginterface' => "'''Внимание:''' Вы редактируете страницу содержащую системное сообщение MediaWiki. Её изменение повлияет на внешний вид интерфейса для других пользователей.",
'sqlhidden' => '(SQL запрос скрыт)',

# Login and logout pages
#
'logouttitle' => 'Стать инкогнито',
'logouttext'          => 'Вы работаете в том же режиме, который был до вашего представления системе. Вы идентифицируетесь не по имени, а по IP-адресу.
Вы можете продолжить участие в проекте анонимно или начать новый сеанс как тот же самый или другой пользователь. Некоторые страницы могут отображаться
как будто вы ещё представлены системе под именем, для борьбы с этим явлением обновите кэш браузера.',

'welcomecreation' => '== Добро пожаловать, $1! ==

Вы были зарегистрированы.
Не забудьте провести [[{{ns:special}}:Preferences|персональную настройку сайта]].',
'loginpagetitle' => 'Представиться системе',
'yourname'              => 'Ваше имя участника',
'yourpassword'  => 'Ваш пароль',
'yourpasswordagain' => 'Повторный набор пароля',
'remembermypassword' => 'Запоминать пароль',
'yourdomainname'       => 'Ваш домен',
'externaldberror'      => 'Произошла ошибка при аутентификации с помощью внешней базы данных, или у вас недостаточно прав для внесения изменений в свою внешнюю учётную запись.',
'loginproblem'  => '<span style="color:red">Участник не опознан.</span>',
'alreadyloggedin' => '<strong>Участник $1, вы уже представились системе!</strong><br />',

'login'                 => 'Представиться системе',
'loginprompt'           => 'Вы должны разрешить «cookies», чтобы представиться системе.',
'userlogin'             => 'Представиться системе',
'logout'                => 'Завершение сеанса',
'userlogout'    => 'Завершение сеанса',
'notloggedin'   => 'Вы не представились системе',
'nologin'       => 'Вы ещё не зарегистрировались? $1.',
'nologinlink'   => 'Создать учётную запись',
'createaccount' => 'Зарегистрировать нового участника',
'gotaccount'    => 'Вы уже зарегистрированы? $1.',
'gotaccountlink'        => 'Представьтесь',
'createaccountmail'     => 'по эл. почте',
'badretype'             => 'Введённые вами пароли не совпадают.',
'userexists'    => 'Введённое вами имя участника уже занято. Пожалуйста, выберите другое имя.',
'youremail'             => 'Электронная почта *',
'username'              => 'Регистрационное имя:',
'uid'                   => 'Идентификатор пользователя:',
'yourrealname'          => 'Ваше настоящее имя (*)',
'yourlanguage'  => 'Язык интерфейса:',
'yourvariant'  => 'Вариант языка',
'yournick'              => 'Ваш псевдоним (для подписей):',
'badsig'                => 'Неверная подпись. Проверьте корректность HTML-тегов.',
'email'                 => 'Эл. почта',
'prefs-help-email-enotif' => 'Этот адрес также используется для отправки по электронной почте оповещений об изменении страниц, если вы активировали соответствующую опцию.',
'prefs-help-realname'   => '* Настоящее имя (необязательное поле): если вы укажите его, то оно будет использовано для того чтобы показать кем был внесена правка страницы.',
'loginerror'    => 'Ошибка опознавания участника',
'prefs-help-email'      => '* Электронная почта (необязательное поле) позволяет другим участникам связаться с вами без раскрытия адреса вашей электронной почты.',
'nocookiesnew'  => 'Участник зарегистрирован, но не представлен. {{SITENAME}} использует «cookies» для представления участников. У вас «cookies» запрещены. Пожалуйста, разрешите их, а затем преставьтесь с вашим новым именем участника и паролем.',
'nocookieslogin'      => '{{SITENAME}} использует «cookies» для представления участников. Вы их отключили. Пожалуйста, включите их и попробуйте снова.',
'noname'                => 'Вы не указали допустимого имени участника.',
'loginsuccesstitle' => 'Опознание прошло успешно',
'loginsuccess'  => 'Теперь вы работаете под именем $1.',
'nosuchuser'    => 'Участника с именем $1 не существует.
Проверьте правильность написания, или воспользуйтесь формой ниже, чтобы зарегистрировать нового участника.',
'nosuchusershort'       => 'Не существует участника с именем $1. Проверьте написание имени.',
'nouserspecified'       => 'Вы должны указать имя участника.',
'wrongpassword'         => 'Введённый вами пароль неверен. Попробуйте ещё раз.',
'wrongpasswordempty'		=> 'Пожалуйста, введите непустой пароль.',
'mailmypassword'        => 'Выслать новый пароль',
'passwordremindertitle' => 'Напоминание пароля участника {{grammar:genitive|{{SITENAME}}}}',
'passwordremindertext' => 'Кто-то (вероятно вы, с IP-адреса $1) запросил,
чтобы мы выслали вам новый пароль участника {{grammar:genitive|{{SITENAME}}}} ($4).
Пароль для участника $2 теперь: <code>$3</code>.
Вы должны представиться системе и поменять пароль.

Если вы не посылали запроса на смену пароля, или если вы уже вспомнили свой пароль,
вы можете проигнорировать данное сообщение и продолжить использовать свой старый пароль.',
'noemail'               => 'Для участника с именем $1 электронный адрес указан не был.',
'passwordsent'          => 'Новый пароль был выслан на адрес электронной почты, указанный для участника $1.

Пожалуйста, представьтесь системе заново после получения пароля.',
'eauthentsent'          =>  'Временный пароль был отправлен на адрес электронной почты нового участника $1. В письме также описаны действия, которые нужно выполнить, чтобы подтвердить, что этот адрес электронной почты действительно принадлежит вам.',
'mailerror' => 'Ошибка при посылке почты: $1',
'acct_creation_throttle_hit' => 'К сожалению, вы уже создали $1 учётных записей. Вы не можете создать больше ни одной.',
'emailauthenticated'    => 'Ваш почтовый адрес был сопоставлен с $1.',
'emailnotauthenticated' => 'Ваш адрес электронной почты <strong>ещё не был подтверждён</strong>, функции вики-движка по работе с эл. почтой отключены.',
'noemailprefs'          => 'Адрес электронной почты не был указан, функции вики-движка по работе с эл. почтой отключены.',
'emailconfirmlink' => 'Подтвердить ваш адрес электронной почты',
'invalidemailaddress'   => 'Введённый адрес не может быть принят, так как он не соответствует формату адресов электронной почты. Пожалуйста введите корректный адрес или оставьте поле пустым.',
'accountcreated' => 'Учётная запись создана',
'accountcreatedtext' => 'Создана учётная запись участника $1.',

# Edit page toolbar
'bold_sample'=>'Жирный шрифт',
'bold_tip'=>'Жирный шрифт',
'italic_sample'=>'Курсивный текст',
'italic_tip'=>'Курсивный текст',
'link_sample'=>'Заголовок ссылки',
'link_tip'=>'Внутренняя ссылка',
'extlink_sample'=>'http://www.example.com заголовок ссылки',
'extlink_tip'=>'Внешняя ссылка (помните о префиксе http:// )',
'headline_sample'=>'Текст заголовка',
'headline_tip'=>'Заголовок 2-го уровня',
'math_sample'=>'Вставляйте сюда формулу',
'math_tip'=>'Математическая формула (формат LaTeX)',
'nowiki_sample'=>'Вставляйте сюда неотформатированный текст.',
'nowiki_tip'=>'Не обрабатывать как размеченный текст',
'image_sample'=>'Example.jpg',
'image_tip'=>'Встроенное изображение',
'media_sample'=>'Example.ogg',
'media_tip'=>'Ссылка на медиа-файл',
'sig_tip'=>'Ваша подпись и момент времени',
'hr_tip'=>'Горизонтальная линия (не используйте часто)',

# Edit pages
#
'summary'               => 'Краткое описание изменений',
'subject'               => 'Тема/заголовок',
'minoredit'             => 'Малое изменение',
'watchthis'             => 'Включить эту страницу в список наблюдения',
'savearticle'   => 'Записать страницу',
'preview'               => 'Предпросмотр',
'showpreview'   => 'Предварительный просмотр',
'showlivepreview'       => 'Быстрый предпросмотр',
'showdiff'      => 'Внесённые изменения',
'anoneditwarning' => "'''Внимание''': Вы не представились системе. Ваш IP-адрес будет записан в историю изменений этой страницы.",
'missingsummary' => "'''Напоминание.''' Вы не дали краткого описания изменений. При повторном нажатии на кнопку «Сохранить», ваши изменения будут сохранены без комментария.",
'missingcommenttext' => 'Пожалуйста, введите ниже ваше сообщение.',
'blockedtitle'  => 'Участник заблокирован',
'blockedtext'   => "<big>'''Ваша учётная запись или IP-адрес заблокированы.'''</big>

Блокировка произведена администратором $1. Указана следующая причина блокировки: ''«$2»''.

Вы можете отправить письмо участнику $1 или любому другому [[{{ns:project}}:Администраторы|администратору]], чтобы обсудить блокировку.
Обратите внимание, что вы не сможете отправить письмо администратору, если вы не зарегистрированы в Википедии и не подтвердили свой адрес электронной почты в [[Special:Preferences|личных настройках]]. 
Ваш IP-адрес — $3. Пожалуйста, указывайте этот адрес во всех своих запросах.",
'blockedoriginalsource' => "Ниже показан текст страницы «$1».",
'blockededitsource' => "Ниже показан текст '''ваших изменений''' страницы «$1».",
'whitelistedittitle' => 'Для изменения требуется авторизация',
'whitelistedittext' => 'Вы должны $1 для изменения страниц.',
'whitelistreadtitle' => 'Для чтения требуется авторизация',
'whitelistreadtext' => 'Вы должны [[Special:Userlogin|зарегистрироваться]] для чтения этих страниц.',
'whitelistacctitle' => 'У вас нет прав, чтобы создать учётную запись',
'whitelistacctext' => 'Для того чтобы иметь возможность создавать учётные записи в этой вики вы должны [[Special:Userlogin|зарегистрироваться]] и иметь соответствующие права.',
'confirmedittitle' => 'Требуется подтверждение адреса электронной почты',
'confirmedittext' => 'Вы должны подтвердить ваш адрес электронной почты перед правкой страниц. Пожалуйста, введите и подтвердите ваш адрес эл. почты на [[Special:Preferences|странице настроек]].',
'loginreqtitle' => 'Требуется авторизация',
'loginreqlink' => 'представиться',
'loginreqpagetext'      => 'Вы должны $1, чтобы просмотреть другие страницы.',
'accmailtitle' => 'Пароль выслан.',
'accmailtext' => 'Пароль для $1 выслан на $2.',
'newarticle'    => '(Новая)',
'newarticletext' =>
'Вы перешли по ссылке на статью, которая пока не существует.
Чтобы создать новую страницу, наберите текст в окне, расположенном ниже
(см. [[{{ns:help}}:Справка|справочную страницу]] чтобы получить больше информации).
Если вы оказались здесь по ошибке, просто нажмите кнопку \'\'\'назад\'\'\' вашего браузера.',
'anontalkpagetext' => "----''Эта страница обсуждения принадлежит анонимному участнику, который ещё не зарегистрировался или который не представился регистрированным именем. Для идентификации используется цифровой IP-адрес. Если вы анонимный участник и полагаете, что получили сообщения, адресованные не вам (один IP-адрес может использоваться несколькими пользователями), пожалуйста, [[Служебная:Userlogin|представьтесь системе]], чтобы впредь избежать возможной путаницы с другими участниками.''",
'noarticletext' => "В настоящий момент текст на данной странице отсутствует. Вы можете [[{{ns:special}}:Search/{{PAGENAME}}|найти упоминание данного названия]] в других статьях или '''[{{fullurl:{{FULLPAGENAME}}|action=edit}} создать страницу с таким названием]'''.",
'clearyourcache' => "'''Замечание:''' Чтобы после сохранения увидеть сделанные изменения, очистите кэш своего браузера: '''Mozilla / Firefox''': ''Ctrl+Shift+R'', '''IE:''' ''Ctrl+F5'', '''Safari''': ''Cmd+Shift+R'', '''Konqueror''': ''F5'', '''Opera''': через меню ''Tools→Preferences''.",
'usercssjsyoucanpreview' => "<strong>Подсказка:</strong> Используйте кнопку предварительного просмотра чтобы протестировать ваш новый css-файл или js-файл перед сохранением.",
'usercsspreview' => '\'\'\'Помните, что это только предварительный просмотр вашего css-файла, он ещё не сохранён!\'\'\'',
'userjspreview' => '\'\'\'Помните, что это только предварительный просмотр вашего javascrpt-файла, он ещё не сохранён!\'\'\'',
'userinvalidcssjstitle' => "'''Внимание:''' тема оформления «$1» не найдена. Помните, что пользовательские .css и .js страницы должны иметь название состоящее только из маленьких букв, например «{{ns:user}}:Некто/monobook.css», а не «{{ns:user}}:Некто/Monobook.css».",
'updated'               => '(Обновлена)',
'note'                  => '<strong>Примечание:</strong>',
'previewnote'   => 'Это только предварительный просмотр, текст ещё не записан!',
'session_fail_preview' => '<strong>К сожалению, сервер не смог сохранить ваши изменения из-за потери идентификатора сессии. Это наша временная проблема, извините за неудобства. Пожалуйста, попробуйте сохранить ещё раз. Если это не поможет, сохраните данные в локальном файле, закройте и вновь откройте браузер.</strong>',
'previewconflict' => 'Этот предварительный просмотр отражает текст в верхнем окне редактирования так, как он будет выглядеть, если вы решите записать его.',
'session_fail_preview_html' => '<sstrong>Извините! Невозможно принять ваше изменение из-за потери данных HTML-сессии.</sstrong>
	  	 
\'\'Так как данная вики разрешает использовать чистый HTML, предварительный просмотр отключён в качестве меры предотвращения JavaScript атак.\'\'
	  	 
<sstrong>Если это добросовестная попытка редактирования, пожалуста, попробуйте ещё раз. Если не получается повторная правка, попробуйте завершить сеанс работы и заново представится.</sstrong>',
'importing'             => 'Импортирование $1',
'editing'               => 'Редактирование $1',
'editinguser'               => 'Редактирование $1',
'editingsection'                => 'Редактирование $1 (секция)',
'editingcomment'                => 'Редактирование $1 (комментарий)',
'editconflict'  => 'Конфликт редактирования: $1',
'explainconflict' => 'Пока вы редактировали эту статью, кто-то внёс в неё изменения. В верхнем окне для редактирования вы видите тот текст статьи, который будет сохранён при нажатии на кнопку «Записать страницу». В нижнем окне для редактирования находится ваш вариант. Чтобы сохранить ваши изменения, перенесите их из нижнего окна для редактирования в верхнее.<br />',
'yourtext'              => 'Ваш текст',
'storedversion' => 'Сохранённая версия',
'nonunicodebrowser' => '<strong>ПРЕДУПРЕЖДЕНИЕ: Ваш [[браузер]] не поддерживает кодировку [[Юникод]]. При редактировании статей все не-ASCII символы будут заменены на свои шестнадцатеричные коды.</strong>',
'editingold'    => '<strong>ПРЕДУПРЕЖДЕНИЕ: Вы редактируете устаревшую версию данной страницы. После сохранения страницы будут потеряны изменения, сделанные в последующих версиях.</strong>',
'yourdiff'              => 'Различия',
'copyrightwarning' => 'Обратите внимание, что все добавления и изменения текста статьи рассматриваются, как выпущенные на условиях лицензии $2 (см. $1).
Если вы не хотите, чтобы ваши тексты свободно распространялись и редактировались кем угодно, не помещайте их сюда.<br />
Вы также подтверждаете, что являетесь автором вносимых дополнений, или скопировали их из
источника допускающего свободное распространение и изменение своего содержимого.<br />
<strong>НЕ РАЗМЕЩАЙТЕ БЕЗ РАЗРЕШЕНИЯ МАТЕРИАЛЫ ОХРАНЯЕМЫЕ АВТОРСКИМ ПРАВОМ!</strong>',
'copyrightwarning2' => 'Пожалуйста, обратите внимание, что все ваши добавления
могут быть отредактированы или удалены другими участниками.
Если вы не хотите, чтобы кто-либо изменял ваши тексты, не помещайте их сюда.<br />
Вы также подтверждаете, что являетесь автором вносимых дополнений, или скопировали их из
источника допускающего свободное распространение и изменение своего содержимого (см. $1).<br />
<strong>НЕ РАЗМЕЩАЙТЕ БЕЗ РАЗРЕШЕНИЯ ОХРАНЯЕМЫЕ АВТОРСКИМ ПРАВОМ МАТЕРИАЛЫ!</strong>',
'longpagewarning' => '<strong>ПРЕДУПРЕЖДЕНИЕ: Длина этой страницы составляет $1 килобайт. Страницы, приближающиеся к или превышающие 32 кБ могут неверно отображаться в некоторых браузерах.
Пожалуйста, рассмотрите вариант разбиения страницы на меньшие части.</strong>',
'longpageerror' => "<strong>ОШИБКА: записываемый вами текст имеет размер $1 килобайт, что больше, чем установленный предел $2 килобайта. Страница не может быть сохранена.</strong>",
'readonlywarning' => '<strong>ПРЕДУПРЕЖДЕНИЕ: база данных заблокирована в связи с процедурами обслуживания,
поэтому вы не можете записать ваши изменения прямо сейчас.
Возможно, вам следует сохранить текст в файл на своём диске и поместить его в данный проект позже.</strong>',
'protectedpagewarning' => '<strong>ПРЕДУПРЕЖДЕНИЕ: эта страница была защищена, только администраторы проекта могут её редактировать.</strong>',
'semiprotectedpagewarning' => "'''Замечание:''' эта статья была защищена, только зарегистрированные пользователи могут её редактировать.",
'templatesused' => 'Шаблоны, использованные на этой странице:',
'edittools' => '<!-- Текс расположенный здесь будет показываться под формой редактирования и формой загрузки. -->',
'nocreatetitle' => 'Создание страниц ограничено',
'nocreatetext' => 'На этом сайте ограничена возможность создания новых страниц.
Вы можете вернуться назад и отредактировать существующую страницу, [[Special:Userlogin|представиться системе или создать новую учётную запись]].',
'cantcreateaccounttitle' => 'Невозможно создать учётную запись',
'cantcreateaccounttext' => 'Регистрация новых участников с этого IP-адреса (<b>$1</b>) запрещена.
Вероятно, это связано с систематическим вандализмом с этого адреса
(который может быть общим, например, для школы или интернет-провайдера).',

# History pages
#
'revhistory'    => 'Журнал изменений',
'viewpagelogs' => 'Показать журналы для этой страницы',
'nohistory'             => 'Для этой страницы журнал изменений отсутствует.',
'revnotfound'   => 'Версия не найдена',
'revnotfoundtext' => 'Старая версия страницы не найдена.
Пожалуйста, проверьте правильность ссылки, которую вы использовали для доступа к этой странице.',
'loadhist'              => 'Загрузка журнала изменений страницы',
'currentrev'    => 'Текущая версия',
'revisionasof'          => 'Версия $1',
'old-revision-navigation'   => 'Версия от $1<br />($6) $3 | $2 | $4 ($7)',
'previousrevision'      => '← Предыдущая',
'nextrevision'          => 'Следующая →',
'currentrevisionlink'   => 'Текущая версия',
'cur'                   => 'текущ.',
'next'                  => 'след.',
'last'                  => 'пред.',
'orig'                  => 'перв.',
'histlegend'    => "Пояснения: (текущ.) — отличие от текущей версии; (пред.) — отличие от предшествующей версии; '''м''' — малозначимое изменение",
'deletedrev' => '[удалена]',
'histfirst' => 'Первый',
'histlast' => 'Последний',
'rev-deleted-comment' => '(комментарий удалён)',
'rev-deleted-user' => '(имя автора стёрто)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
Эта версия страницы была удалена из общедоступного архива.
Возможно, объяснения даны в [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} журнале удалений].
</div>',
'rev-deleted-text-view' => '<div class="mw-warning plainlinks">
Эта версия страницы была удалена из общедоступного архива.
Вы можете просмотреть её, так как являетесь администратором сайта.
Возможно, объяснения удаления даны в [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} журнале удалений].
</div>',
#'rev-delundel' => 'del/undel',
'rev-delundel' => 'показать/скрыть',

'history-feed-title' => 'История изменений',
'history-feed-description'      => 'История изменений этой страницы в вики',
'history-feed-item-nocomment' => '$1 в $2', # user at time
'history-feed-empty' => 'Запрашиваемой страницы не существует.
Она могла быть удалена или переименована.
Попробуйте [[Special:Search|найти в вики]] похожие страницы.',

# Revision deletion
#
'revisiondelete' => 'Удалить / восстановить версии страницы',
'revdelete-nooldid-title' => 'Не задана целевая версия',
'revdelete-nooldid-text' => 'Вы не задали целевую версию (или версии) для выполнения этой функции.',
'revdelete-selected' => 'Выбранная версия [[:$1]]:',
'revdelete-text' => "Удалённые версии будут показываться в истории страницы,
но их содержание будет недоступно обычным посетителям.

Администраторы будут иметь доступ к скрытому содержанию и смогут восстановить его через этот же интерфейс,
за исключением случаев, когда было установлено дополнительное ограничение владельцем сайта.",
'revdelete-legend' => 'Установить ограничения:',
'revdelete-hide-text' => 'Скрыть текст этой версии страницы',
'revdelete-hide-comment' => 'Скрыть комментарий',
'revdelete-hide-user' => 'Скрыть имя автора',
'revdelete-hide-restricted' => 'Применить ограничения также и к администраторам',
'revdelete-log' => 'Комментарий:',
'revdelete-submit' => 'Применить к выбранной версии',
'revdelete-logentry' => 'Изменённая видимость версии страницы для [[$1]]',

# Diffs
#
'difference'    => '(Различия между версиями)',
'loadingrev'    => 'загрузка версии для различения',
'lineno'                => 'Строка $1:',
'editcurrent'   => 'Редактировать текущую версию данной страницы',
'selectnewerversionfordiff' => 'Выберите новую версию для сравнения',
'selectolderversionfordiff' => 'Выберите старую версию для сравнения',
'compareselectedversions' => 'Сравнить выбранные версии',

# Search results
#
'searchresults' => 'Результаты поиска',
'searchresulttext' => 'Для получения более подробной информации о поиске на страницах проекта, см. [[{{ns:project}}:Поиск]].',
'searchsubtitle'   => 'По запросу «[[:$1]]»',
'searchsubtitleinvalid'   => 'По запросу «$1»',
'badquery'              => 'Неправильно сформированный запрос',
'badquerytext'  => 'Невозможно обработать запрос.
Возможно, Вы попытались найти слово короче трёх букв (это пока не поддерживается) либо допустили опечатку в слове.
Попробуйте другой запрос.',
'matchtotals'   => 'Запросу «$1» соответствует(ют) $2 название(я) статьи(ей) и тексты $3 статьи(ей).',
'noexactmatch' => 'Страницы с названием «$1» не существует.

<span style="display: block; margin: 1.5em 2em">
<strong>[[:$1|Создать страницу]]</strong></span>',
'titlematches'  => 'Совпадения в названиях статей',
'notitlematches' => 'Нет совпадений в названиях статей',
'textmatches'   => 'Совпадения в текстах статей',
'notextmatches' => 'Нет совпадения в текстах статей',
'prevn'                 => 'предыдущие $1',
'nextn'                 => 'следующие $1',
'viewprevnext'  => 'Просмотреть ($1) ($2) ($3).',
'showingresults' => 'Ниже показаны <strong>$1</strong> результатов, начиная с №&nbsp;<strong>$2</strong>.',
'showingresultsnum' => 'Ниже показаны <strong>$3</strong> результатов, начиная с №&nbsp;<strong>$2</strong>.',
'nonefound'             => 'Неудачный поиск может быть вызван попыткой найти общие слова, которые не подлежат индексированию, например — «тоже» и «чтобы» или употреблением более чем одного ключевого слова поиска (показываются только страницы, содержащие все указанные слова для поиска).',
'powersearch' => 'Искать',
'powersearchtext' => 'Искать в пространствах имён:<br />$1<br />$2 Показывать перенаправления<br /> Искать $3 $9',
'searchdisabled' => 'Извините, но встроенный полнотекстовый поиск выключен. Вы можете воспользоваться поиском по сайту через поисковые системы общего назначения, однако имейте в виду, что копия сайта в их кэше может быть несколько устаревшей.',

'blanknamespace' => 'Статьи',

# Preferences page
#
'preferences'   => 'Настройки',
'mypreferences' => 'Мои настройки',
'prefsnologin' => 'Вы не представились системе',
'prefsnologintext'      => 'Вы должны [[Special:Userlogin|представиться системе]]
чтобы изменять настройки участника.',
'prefsreset'    => 'Настройки были переустановлены в стандартное состояние.',
'qbsettings'    => 'Панель навигации',
'changepassword' => 'Сменить пароль',
'skin'                  => 'Оформление',
'math'                  => 'Отображение формул',
'dateformat'    => 'Формат даты',
'datedefault'           => 'По умолчанию',
'datetime'              => 'Дата и время',
'math_failure'          => 'Невозможно разобрать выражение',
'math_unknown_error'    => 'неизвестная ошибка',
'math_unknown_function' => 'неизвестная функция',
'math_lexing_error'     => 'лексическая ошибка',
'math_syntax_error'     => 'синтаксическая ошибка',
'math_image_error'      => 'Преобразование в PNG прошло с ошибкой; проверьте правильность установки latex, dvips, gs и convert',
'math_bad_tmpdir'       => 'Не удаётся создать или записать во временный каталог математики',
'math_bad_output'       => 'Не удаётся создать или записать в выходной каталог математики',
'math_notexvc'  => 'Выполняемый файл texvc не найден; См. math/README — справку по настройке.',
'prefs-personal' => 'Личные данные',
'prefs-rc' => 'Страница свежих правок',
'prefs-watchlist' => 'Список наблюдения',
'prefs-watchlist-days' => 'Число дней, отображаемых в списке наблюдения:',
'prefs-watchlist-edits' => 'Количество правок, отображаемых в улучшенном списке наблюдения:',
'prefs-misc' => 'Другие настройки',
'saveprefs'             => 'Записать',
'resetprefs'    => 'Сбросить',
'oldpassword'   => 'Старый пароль:',
'newpassword'   => 'Новый пароль:',
'retypenew'             => 'Повторите ввод нового пароля:',
'textboxsize'   => 'Редактирование',
'rows'                  => 'Строк:',
'columns'               => 'Столбцов:',
'searchresultshead' => 'Результаты поиска',
'resultsperpage' => 'Количество найденных записей на страницу:',
'contextlines'  => 'Количество показываемых строк для каждой найденной:',
'contextchars'  => 'Количество символов контекста на строку:',
'stubthreshold' => 'Порог определения болванки:',
'recentchangescount' => 'Заголовки статей на странице свежих правок:',
'savedprefs'    => 'Ваши настройки сохранены.',
'timezonelegend' => 'Часовой пояс',
'timezonetext'  => 'Введите смещение (в часах) вашего местного времени
от времени сервера (UTC — гринвичского).',
'localtime'     => 'Местное время',
'timezoneoffset' => 'Смещение',
'servertime'    => 'Текущее время сервера',
'guesstimezone' => 'Заполнить из браузера',
'allowemail'             => 'Разрешить приём электронной почты от других участников',
'defaultns'             => 'По умолчанию, искать в следующих пространствах имён:',
'default'               => 'по умолчанию',
'files'                 => 'Файлы',

# User rights

'userrights-lookup-user' => 'Управление группами пользователя',
'userrights-user-editname' => 'Введите имя участника:',
'editusergroup' => 'Изменить группы пользователей',

'userrights-editusergroup' => 'Изменить группы участника',
'saveusergroups' => 'Сохранить группы участника',
'userrights-groupsmember' => 'Член групп:',
'userrights-groupsavailable' => 'Доступные группы:',
'userrights-groupshelp' => 'Выберите группы, в которые вы хотите включить или из которых хотите исключить участника.
Невыбранные группы не изменятся. Группы можно убрать из выборки используя CTRL + левая клавиша мыши',

# Groups
'group'                   => 'Группа:',
'group-bot'               => 'Боты',
'group-sysop'             => 'Администраторы',
'group-bureaucrat'        => 'Бюрократы',
'group-all'               => '(все)',
 
'group-bot-member'        => 'бот',
'group-sysop-member'      => 'администратор',
'group-bureaucrat-member' => 'бюрократ',

'grouppage-bot' => '{{ns:project}}:Боты',
'grouppage-sysop' => '{{ns:project}}:Администраторы',
'grouppage-bureaucrat' => '{{ns:project}}:Бюрократы',

# Recent changes
#
'changes' => 'изменения',
'recentchanges' => 'Свежие правки',
'recentchangestext' => 'Ниже в хронологическом порядке перечислены последние изменения на страницах {{grammar:genitive|{{SITENAME}}}}.',
'rcnote'                => 'Последние <strong>$1</strong> изменений за <strong>$2</strong> дней, на момент времени $3.',
'rcnotefrom'    => 'Ниже перечислены изменения с <strong>$2</strong> (по <strong>$1</strong>).',
'rclistfrom'    => 'Показать изменения с $1.',
'rcshowhideminor' => '$1 малые правки',
'rcshowhidebots' => '$1 ботов',
'rcshowhideliu' => '$1 представившихся участников',
'rcshowhideanons' => '$1 анонимов',
'rcshowhidepatr' => '$1 проверенные правки',
'rcshowhidemine' => '$1 свои правки',
'rclinks'               => 'Показать последние $1 изменений за $2 дней;<br />$3.',
'diff'                  => 'разн.',
'hist'                  => 'журнал',
'hide'                  => 'Скрыть',
'show'                  => 'Показать',
'minoreditletter' => 'м',
'newpageletter' => 'Н',
'boteditletter' => 'б',
'sectionlink' => '→',
'number_of_watching_users_pageview'     => '[$1 наблюдающих пользователя]',
'rc_categories' => 'Только из категорий (разделитель «|»)',
'rc_categories_any'     => 'Любой',

# Upload
#
'upload'                => 'Загрузить файл',
'uploadbtn'             => 'Загрузить файл',
'reupload'              => 'Изменить загрузку',
'reuploaddesc'  => 'Вернуться к форме загрузки.',
'uploadnologin' => 'Вы не представились системе',
'uploadnologintext'     => 'Вы должны [[Special:Userlogin|представиться системе]],
чтобы загружать файлы на сервер.',
'upload_directory_read_only' => 'Веб-сервер не имеет прав записи в папку ($1), в которой предполагается хранить загружаемые файлы.',
'uploaderror'   => 'Ошибка загрузки файла',
'uploadtext'    => "Используя эту форму вы можете загрузить на сервер файлы.

Чтобы просмотреть ранее загруженные файлы,
перейдите сюда: [[Special:Imagelist|список загруженных изображений]].<br />
Загрузка и удаление файлов отражаются в [[Special:Log|журнале загрузки файлов]].

Для включения изображения в статью вы можете использовать строки вида:
*'''<nowiki>[[{{ns:image}}:File.jpg]]</nowiki>'''
*'''<nowiki>[[{{ns:image}}:File.png|thumb|комментарий]]</nowiki>'''

Для ссылки на медиа-файл вы можете использовать строку вида:
*'''<nowiki>[[{{ns:media}}:File.ogg]]</nowiki>'''",
'uploadlog'             => 'журнал загрузок',
'uploadlogpage' => 'Журнал загрузок',
'uploadlogpagetext' => 'Ниже представлен список последних загрузок файлов.
Везде используется время сервера (по Гринвичу, UTC).
<ul>
</ul>',
'filename'              => 'Имя файла',
'filedesc'              => 'Краткое описание',
'fileuploadsummary' => 'Краткое описание:',
'filestatus' => 'Условия распространения',
'filesource' => 'Источник',
'copyrightpage' => '{{ns:project}}:Авторское право',
'copyrightpagename' => 'Авторские права проекта {{SITENAME}}',
'uploadedfiles' => 'Загруженные файлы',
'ignorewarning'        => 'Игнорировать предупреждения и сохранить файл в любом случае.',
'ignorewarnings'        => 'Игнорировать предупреждения',
'minlength'             => 'Название файла должно содержать хотя бы три символа.',
'illegalfilename'       => 'Имя файла «$1» содержит символы, которые не разрешается использовать в заголовках. Пожалуйста, переименуйте файл и попытайтесь загрузить его снова.',
'badfilename'   => 'Название файла было изменено на $1.',
'badfiletype'   => '«.$1» не является рекомендованным форматом для файлов с изображениями.',
'largefile'             => 'Рекомендуется не использовать файлы, размер которых не превышает $1 байт (размер загруженного файла составляет $2 байт).',
'largefileserver' => 'Размер файла больше установленного на сервере предела.',
'emptyfile'             => 'Загруженный вами файл вероятно пустой. Возможно, это произошло из-за ошибки при наборе имени файла. Пожалуйста, проверьте, действительно ли вы хотите загрузить этот файл.',
'fileexists'            => 'Файл с этим именем уже существует, пожалуйста, проверьте $1 если вы не уверены что вы хотите заменить его.',
'fileexists-forbidden' => 'Файл с этим именем уже существует; пожалуйста, вернитесь назад и загрузите файл под другим именем. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Файл с этим именем уже существует в общем хранилище файлов; пожалуйста, вернитесь назад и загрузите файл под другим именем. [[Image:$1|thumb|center|$1]]',
'successfulupload' => 'Загрузка успешно завершена',
'fileuploaded'  => 'Файл «$1» успешно загружен.

Пожалуйста, проследуйте по следующей ссылке: ($2) к странице с описанием и заполните информацию о файле, такую как: источник файла, когда и кем был создан файл, а также любую другую информацию известную вам об этом файле.',
'uploadwarning' => 'Предупреждение',
'savefile'              => 'Записать файл',
'uploadedimage' => 'загружен «[[$1]]»',
'uploaddisabled' => 'Загрузка запрещена.',
'uploaddisabledtext' => 'На этом вики-сайте загрузка файлов запрещена.',
'uploadscripted' => 'Файл содержит HTML-код или скрипт, который может быть ошибочно обработан браузером.',
'uploadcorrupt' => 'Файл либо повреждён, либо имеет неверное расширение. Пожалуйста, проверьте файл и попробуйте загрузить его ещё раз.',
'uploadvirus' => 'Файл содержит вирус! См. $1',
'sourcefilename' => 'Исходное имя файла',
'destfilename' => 'Целевое имя файла',
'watchthisupload'       => 'Включить этот файл в список наблюдения',
'filewasdeleted' => 'Файл с таким именем уже существовал ранее, но был удалён. Пожалуйста, проверьте $1 перед повторной загрузкой.',

'license' => 'Лицензирование',
'nolicense' => 'Отсутствует',
'upload_source_url' => ' (правильный, публично доступный интернет-адрес)',
'upload_source_file' => ' (файл на вашем компьютере)',

# Image list
#
'imagelist'             => 'Список файлов',
'imagelisttext' => "Ниже представлен список из '''$1''' {{plural:$1|файла|файлов|файлов}}, отсортированных $2.",
'imagelistforuser' => "Только изображения загруженные участником $1.",
'getimagelist'  => 'получение списка файлов',
'ilsubmit'              => 'Искать',
'showlast'              => 'Показать последние $1 файлов, отсортированных $2.',
'byname'                => 'по имени',
'bydate'                => 'по дате',
'bysize'                => 'по размеру',
'imgdelete'             => 'удал.',
'imgdesc'               => 'описание',
'imgfile'       => 'файл',
'imglegend'             => 'Пояснения: (описание) — показать/изменить описание файла.',
'imghistory'    => 'Журнал',
'revertimg'             => 'откат.',
'deleteimg'             => 'удал.',
'deleteimgcompletely'           => 'Удалить все версии',
'imghistlegend' => 'Пояснения: (текущ.) — текущее изображение; (удал.) — удалить эту старую версию; (откат.) — откатиться на эту старую версию.
<br /><i>Выберите дату, чтобы посмотреть список изображений, загруженных на эту дату.</i>.',
'imagelinks'    => 'Ссылки',
'linkstoimage'  => 'Следующие страницы ссылаются на данный файл:',
'nolinkstoimage' => 'Нет страниц, ссылающихся на данный файл.',
'sharedupload' => "Этот файл хранится на [[Викисклад]]е, хранилище изображений и мультимедиа для использования в изданиях [[Фонд Викимедиа|Фонда Викимедиа]].тек

На '''[[Commons:Image:{{PAGENAME}}|странице описания]]''' есть сведения об этом изображении и условии его распространения.",
'shareduploadwiki' => 'Дополнительную информацию можно найти на $1.',
'shareduploadwiki-linktext' => 'странице описания файла',
'noimage'       => 'Файла с таким именем не существует, вы можете $1.',
'noimage-linktext'       => 'загрузить его',
'uploadnewversion-linktext' => 'Загрузить новую версию этого файла',
'imagelist_date' => 'Дата',
'imagelist_name' => 'Имя файла',
'imagelist_user' => 'Участник',
'imagelist_size' => 'Размер (в байтах)',
'imagelist_description' => 'Описание',
'imagelist_search_for' => 'Поиск по имени изображения:',

# Mime search
#
'mimesearch' => 'Поиск по MIME',
'mimetype' => 'MIME-тип:',
'download' => 'загрузить',

# Unwatchedpages
#
'unwatchedpages' => 'Страницы, за которыми никто не следит',

# List redirects
'listredirects' => 'Список перенаправлений',

# Unused templates
'unusedtemplates' => 'Неиспользуемые шаблоны',
'unusedtemplatestext' => 'На этой странице перечислены все страницы пространства имён «Шаблоны», которые не включены в другие страницы. Не забывайте проверить отсутствие других ссылок на шаблон, перед его удалением.',
'unusedtemplateswlh' => 'другие ссылки',
 	 
# Random redirect
'randomredirect' => 'Случайное перенаправление',

# Statistics
#
'statistics'    => 'Статистика',
'sitestats'             => 'Статистика сайта',
'userstats'             => 'Статистика участников',
'sitestatstext' => "Суммарно в базе данных содержится '''$1''' страниц.
Это число включает в себя страницы о проекте, страницы обсуждений, незаконченные статьи, перенаправления и другие страницы, которые, не учитываются при подсчёте количества статей.
За исключением них, есть '''$2''' страниц, которые считаются полноценными статьями. 

Было загружено '''$8''' файлов.

Всего с момента установки вики было сделано '''$3''' просмотров страниц и '''$4''' редактирований.
Таким образом, в среднем приходится '''$5''' изменений на одну страницу, и '''$6''' просмотров на одно изменение.

Величина [http://meta.wikimedia.org/wiki/Help:Job_queue очереди заданий] составляет '''$7'''.",
'userstatstext' => "Зарегистрировались '''$1''' участников, из которых '''$2''' ($4 %) имеют права «$5».",
'statistics-mostpopular' => 'Наиболее просматриваемые страницы',

'disambiguations'       => 'Страницы, описывающие многозначные термины',
'disambiguationspage'   => '{{ns:template}}:disambig',
'disambiguationstext'   => 'Следующие статьи ссылаются на <em>многозначные страницы</em>. Вместо этого они, вероятно, должны указывать на соответствующую конкретную статью.<br />Страница считается многозначной, если на ней размещён $1.<br />Ссылки из других пространств имён здесь <em>не</em> приведены.',
'doubleredirects'       => 'Двойные перенаправления',
'doubleredirectstext'   => 'Каждая строка содержит ссылки на первое и второе перенаправления, а также первую строчку страницы второго перенаправления, в которой обычно указывается название страницы куда должно осуществляться перенаправление. Нужно чтобы и первое перенаправление ссылалось на эту страницу.',
'brokenredirects'       => 'Разорванные перенаправления',
'brokenredirectstext'   => 'Следующие перенаправления указывают на несуществующие статьи:',

# Miscellaneous special pages
#
'nbytes'                => '$1 {{PLURAL:$1|байт|байта|байтов}}',
'ncategories'           => '$1 {{PLURAL:$1|категория|категории|категорий}}',
'nlinks'                => '$1 {{PLURAL:$1|ссылка|ссылки|ссылок}}',
'nmembers'              => '$1 {{PLURAL:$1|объект|объекта|объектов}}',
'nrevisions'            => '$1 {{PLURAL:$1|версия|версии|версий}}',
'nviews'                => '$1 {{PLURAL:$1|просмотр|просмотра|просмотров}}',

'lonelypages'   => 'Страницы-сироты',
'uncategorizedpages'    => 'Некатегоризованные страницы',
'uncategorizedcategories'       => 'Некатегоризованные категории',
'uncategorizedimages' => 'Некатегоризованные изображения',
'unusedcategories' => 'Неиспользуемые категории',
'unusedimages'  => 'Неиспользуемые файлы',
'popularpages'  => 'Популярные страницы',
'wantedcategories' => 'Требуемые категории',
'wantedpages'   => 'Требуемые страницы',
'mostlinked'    => 'Страницы, на которые больше всего ссылок',
'mostlinkedcategories' => 'Категории, на которые больше всего ссылок',
'mostcategories' => 'Страницы, включённые в большое количество категорий',
'mostimages'    => 'Самые используемые изображения',
'mostrevisions' => 'Наиболее часто редактировавшиеся страницы',
'allpages'              => 'Все страницы',
'prefixindex'   => 'Указатель по началу слов',
'randompage'    => 'Случайная статья',
'shortpages'    => 'Короткие статьи',
'longpages'             => 'Длинные страницы',
'deadendpages'  => 'Тупиковые статьи',
'listusers'             => 'Список участников',
'specialpages'  => 'Спецстраницы',
'spheading'             => 'Служебные страницы',
'restrictedpheading'    => 'Служебные страницы с ограниченным доступом',
'recentchangeslinked' => 'Связанные правки',
'rclsub'                => '(на страницах, ссылки на которые есть на странице «$1»)',
'newpages'              => 'Новые статьи',
'newpages-username' => 'Участник:',
'ancientpages'          => 'Самые старые статьи',
'intl'          => 'Межъязыковые ссылки',
'move' => 'Переименовать',
'movethispage'  => 'Переименовать эту страницу',
'unusedimagestext' => 'Пожалуйста, учтите, что другие веб-сайты могут использовать прямую ссылку (URL) на это изображение, и поэтому изображение может активно использоваться несмотря на его вхождение в этот список.',
'unusedcategoriestext' => 'Существуют следующие страницы категорий, не содержащие статей или других категорий.',
'booksources'   => 'Источники книг',
'categoriespagetext' => 'В вики имеются следующие категории.',
'data'  => 'Данные',
'userrights' => 'Управление правами участников',
'groups' => 'Группы участников',

# FIXME: Other sites, of course, may have affiliate relations with the booksellers list
'booksourcetext' => 'Ниже приведён список ссылок на другие веб-сайты, на которых продаются новые и бывшие в употреблении книги, а также на них может быть информация о книгах, которые вы ищете.',
'isbn'  => 'ISBN',
'alphaindexline' => 'от $1 до $2',
'version'               => 'Версия MediaWiki',
'log'           => 'Журналы',
'alllogstext'   => 'Комбинированный показ журналов загрузки, удаления, защиты, блокировки и администрирования.
Вы можете отфильтровать результаты по типу журнала, имени пользователя или затронутой странице.',
'logempty' => 'Совпадающие элементы в журнале отсутствуют.',

# Special:Allpages
'nextpage'          => 'Следующая страница ($1)',
'allpagesfrom'          => 'Вывести страницы, начинающиеся на:',
'allarticles'       => 'Все статьи',
'allinnamespace'        => 'Все страницы (пространства имён «$1»)',
'allnotinnamespace'     => 'Все страницы (кроме пространства имён «$1»)',
'allpagesprev'      => 'Предыдущие',
'allpagesnext'      => 'Следующие',
'allinnamespace' => 'Все страницы ($1 пространство имён)',
'allpagessubmit'    => 'Выполнить',
'allpagesprefix'        => 'Отобразить страницы с префиксом:',
'allpagesbadtitle'      => 'Заголовок данной страницы не является допустимым либо содержит интервики или межязыковый префикс. Возможно он содержит символы запрещённые в заголовках.',

# Special:Listusers
'listusersfrom' => 'Показать участников, начиная с:',

# Email this user
#
'mailnologin'   => 'Адрес для отправки отсутствует',
'mailnologintext' => 'Вы должны [[Special:Userlogin|представиться системе]]
и иметь действительный адрес электронной почты в ваших [[Special:Preferences|настройках]],
чтобы иметь возможность отправлять электронную почту другим участникам.',
'emailuser'             => 'Письмо участнику',
'emailpage'             => 'Отправить электронное письмо участнику',
'emailpagetext' => 'Если этот участник указал действительный адрес электронной почты в своих настройках, то заполнив форму ниже, можно отправить ему сообщение.
Электронный адрес, который вы указали в своих настройках, будет указан в поле «От кого» письма, поэтому получатель будет иметь возможность ответить.',
'usermailererror' => 'При посылке e-mail произошла ошибка:',
'defemailsubject'  => '{{SITENAME}} e-mail',
'noemailtitle'  => 'Адрес электронной почты отсутствует',
'noemailtext'   => 'Этот участник не указал действительный адрес электронной почты или указал, что не желает получать письма от других участников.',
'emailfrom'             => 'От кого',
'emailto'               => 'Кому',
'emailsubject'  => 'Тема письма',
'emailmessage'  => 'Сообщение',
'emailsend'             => 'Отправить',
'emailsent'             => 'Письмо отправлено',
'emailsenttext' => 'Ваше электронное сообщение отослано.',

# Watchlist

'watchlist'             => 'Cписок наблюдения',
'nowatchlist'           => 'Ваш список наблюдения пуст.',
'watchlistanontext' => 'Вы должны $1, чтобы просмотреть или отредактировать список наблюдения.',
'watchlistcount'        => "'''В вашем списке наблюдения $1 страниц, включая страницы обсуждения.'''",
'clearwatchlist'        => 'Очистить список наблюдения',
'watchlistcleartext' => 'Вы уверены, что хотите удалить их?',
'watchlistclearbutton' => 'Очистить список наблюдения',
'watchlistcleardone' => 'Ваш список наблюдения очищен. $1 страниц удалено из списка.',
'watchnologin'          => 'Нужно представиться системе',
'watchnologintext'      => 'Вы должны [[Special:Userlogin|представиться системе]], чтобы иметь возможность изменять свой список наблюдения',
'addedwatch'            => 'Добавлена в список наблюдения',
'addedwatchtext'        => "Страница «[[:$1]]» была добавлена в ваш [[{{ns:special}}:Watchlist|список наблюдения]]. Последующие изменения этой страницы и связанной с ней страницы обсуждения будут отмечаться в этом списке, а также будут выделены жирным шрифтом на странице со [[{{ns:special}}:Recentchanges|списком свежих изменений]], чтобы их было легче заметить.

Если позже вы захотите удалить страницу из списка наблюдения, нажмите кнопку «не следить» в верхней правой части страницы.",
'removedwatch'          => 'Удалена из списка наблюдения',
'removedwatchtext'      => 'Страница «[[:$1]]» была удалена из вашего списка наблюдения.',
'watch' => 'Следить',
'watchthispage'         => 'Наблюдать за этой страницей',
'unwatch' => 'Не следить',
'unwatchthispage'       => 'Прекратить наблюдение',
'notanarticle'          => 'Не статья',
'watchnochange'         => 'Ничто из списка наблюдения не изменялось в рассматриваемый период.',
'watchdetails'          => '* В вашем списке наблюдения находится $1 страниц (не считая страниц обсуждения)
* [[Special:Watchlist/edit|Показать и отредактировать полный список]]
* [[Special:Watchlist/clear|Удалить все страницы из списка]]',
'wlheader-enotif'       => '* Уведомление по эл. почте включено.',
'wlheader-showupdated'  => "* Страницы, изменившиеся с вашего последнего их посещения, выделены '''жирным''' шрифтом.",
'watchmethod-recent'=> 'просмотр последних изменений для наблюдаемых страниц',
'watchmethod-list'      => 'просмотр наблюдаемых страниц для последних изменений',
'removechecked'         => 'Удалить выбранные элементы из списка наблюдения',
'watchlistcontains' => 'Ваш список наблюдения содержит $1 страниц.',
'watcheditlist'         => "Ниже представлен упорядоченный по алфавиту список наблюдаемых вами страниц. Отметьте страницы, которые вы хотите удалить из вашего списка наблюдения, и нажмите на кнопку ''«Удалить выбранные элементы из списка наблюдения»'' внизу страницы.",
'removingchecked'       => 'Удаление выбранных элементов из списка наблюдения…',
'couldntremove'         => 'Невозможно удалить элемент «$1»…',
'iteminvalidname'       => 'Проблема с элементом «$1», недопустимое название…',
'wlnote'                => 'Ниже следуют последние $1 изменений за последние <strong>$2</strong> часов.',
'wlshowlast'            => 'Показать за последние $1 часов $2 дней $3',
'wlsaved'               => 'Это сохранённая версия вашего списка наблюдения',
'wlhideshowown'         => '$1 ваши правки.',
'wlhideshowbots'        => '$1 правки ботов.',
'wldone'                => 'Сделано.',

'enotif_mailer'             => '{{SITENAME}} Служба извещений по почте',
'enotif_reset'              => 'Отметить все страницы как просмотренные',
'enotif_newpagetext'        => 'Это новая страница.',
'changed'                       => 'изменена',
'created'                       => 'создана',
'enotif_subject'    => 'Страница проекта «{{SITENAME}}» $PAGETITLE была $CHANGEDORCREATED участником $PAGEEDITOR',
'enotif_lastvisited' => 'См. $1 для просмотра всех изменений произошедших с вашего последнего посещения.',
'enotif_body' => '$WATCHINGUSERNAME,

$PAGEEDITDATE страница проекта «{{SITENAME}}» $PAGETITLE была $CHANGEDORCREATED пользователем $PAGEEDITOR, см. $PAGETITLE_URL для просмотра текущей версии.

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
{{fullurl:{{ns:help}}:Оглавление}}',

# Delete/protect/revert
#
'deletepage'    => 'Удалить страницу',
'confirm'               => 'Подтверждение',
'excontent' => 'содержимое: «$1»',
'excontentauthor' => 'содержимое: «$1» (единственным автором был $2)',
'exbeforeblank' => 'содержимое до очистки: «$1»',
'exblank' => 'страница была пуста',
'confirmdelete' => 'Подтвердить удаление',
'deletesub'             => '(«$1» удаляется)',
'historywarning' => 'Предупреждение: у страницы, которую вы собираетесь удалить, есть история изменений:',
'confirmdeletetext' => 'Вы запросили полное удаление страницы (изображения) и всей её истории изменений из базы данных.
Пожалуйста, подтвердите, что вы действительно желаете это сделать, понимаете последствия своих действий,
и делаете это в соответствии с правилами, изложенными в разделе [[{{ns:project}}:Правила]].',
'actioncomplete' => 'Действие выполнено',
'deletedtext'   => '«$1» была удалена.
См. $2 для просмотра списка последних удалений.',
'deletedarticle' => 'удалена «[[$1]]»',
'dellogpage'    => 'Список удалений',
'dellogpagetext' => 'Ниже приведён список последних удалений.',
'deletionlog'   => 'список удалений',
'reverted'              => 'Откачено к ранней версии',
'deletecomment' => 'Причина удаления',
'imagereverted' => 'Откат к ранней версии осуществлён.',
'rollback'              => 'Откатить изменения',
'rollback_short' => 'Откат',
'rollbacklink'  => 'откатить',
'rollbackfailed' => 'Ошибка при совершении отката',
'cantrollback'  => 'Невозможно откатить изменения; последний, кто вносил изменения, является единственным автором этой статьи.',
'alreadyrolled' => 'Невозможно откатить последние изменения [[$1]],
сделанные [[{{ns:user}}:$2|$2]] ([[{{ns:user_talk}}:$2|Обсуждение]]); кто-то другой уже отредактировал или откатил эту страницу.

Последние изменения внёс [[{{ns:user}}:$3|$3]] ([[{{ns:user_talk}}:$3|Обсуждение]]).',
#   only shown if there is an edit comment
'editcomment' => 'Изменение было пояснено так: <em>«$1»</em>.',
'revertpage'    => 'Правки [[Special:Contributions/$2|$2]] ([[User_talk:$2|обсуждение]]) откачены к версии [[User:$1|$1]]',
'sessionfailure' => 'Похоже, возникли проблемы с текущим сеансом работы;
это действие было отменено в целях предотвращения «захвата сеанса».
Пожалуйста, нажмите кнопку «Назад» и перезагрузите страницу с которой вы пришли.',
'protectlogpage' => 'Журнал защиты',
'protectlogtext' => 'Ниже приведён журнал установок и снятий защиты со статей.',
'protectedarticle' => 'защищена страница «[[$1]]»',
'unprotectedarticle' => 'снята защита со страницы «[[$1]]»',
'protectsub' =>'(Установка защиты для «$1»)',
'confirmprotecttext' => 'Вы действительно хотите установить защиту этой страницы?',
'confirmprotect' => 'Подтвердите установку защиты страницы',
'protectmoveonly' => 'Защитить только от переименования',
'protectcomment' => 'Причина установки защиты',
'unprotectsub' =>'(Снятие защиты «$1»)',
'confirmunprotecttext' => 'Вы действительно хотите снять защиту этой страницы?',
'confirmunprotect' => 'Подтвердите снятие защиты страницы',
'unprotectcomment' => 'Причина снятия защиты',
'protect-unchain' => 'Разблокировать переименование страницы',
'protect-text' => 'Здесь вы можете просмотреть и изменить уровень защиты для страницы <strong>[[:$1]]</strong>.',
'protect-viewtext' => 'У вас недостаточно прав для изменения уровня защиты страницы. Текущие установки для страницы <strong>[[:$1]]</strong>:',
'protect-default' => '(по умолчанию)',
'protect-level-autoconfirmed' => 'Заблокировать незарегистрированных участников',
'protect-level-sysop' => 'Только администраторы',

# restrictions (nouns)
'restriction-edit' => 'Правка',
'restriction-move' => 'Переименование',

# Undelete
'undelete' => 'Просмотреть стёртые страницы',
'undeletepage' => 'Просмотр и восстановление удалённых страниц',
'viewdeletedpage' => 'Просмотреть стёртые страницы',
'undeletepagetext' => 'Следующие страницы были стёрты, но всё ещё находятся в архиве и поэтому могут быть восстановлены. Архив периодически очищается.',
'undeleteextrahelp' => "Для полного восстановления страницы оставьте все отметки пустыми и нажмите '''«Восстановить»'''. Для частичного восстановления отметьте те версии страницы, которые нужно востановить и нажмите '''«Восстановить»'''. Нажмите '''«Очистить»''', чтобы снять все отметки и очистить поле комментария.",
'undeletearticle' => 'Восстановить стёртую статью',
'undeleterevisions' => 'В архиве $1 версий',
'undeletehistory' => 'Если вы восстановите страницу, все версии будут также восстановлены, вместе с журналом изменений.
Если с момента удаления была создана новая страница с таким же названием, восстановленные версии будут указаны в журнале изменений перед новыми записями, и текущая версия существующей страницы автоматически заменена не будет.',
'undeletehistorynoadmin' => 'Статья была удалена. Причина удаления и список участников редактировавших статью до её удаления показаны ниже.
Текст удалённой статьи могут просмотреть только администраторы.',
'undeleterevision' => 'Стёртая версия от $1',
'undeletebtn' => 'Восстановить',
'undeletereset' => 'Очистить',
'undeletecomment' => 'Комментарий:',
'undeletedarticle' => '«[[$1]]» восстановлена',
'undeletedrevisions' => '$1 изменений восстановлено',
'undeletedrevisions-files' => '$1 версий и $2 файл(ов) восстановлено',
'undeletedfiles' => "$1 файл(ов) восстановлено",
'cannotundelete' => 'Ошибка восстановления. Возможно, кто-то другой уже восстановил страницу.',
'undeletedpage' => "<big>'''Страница «$1» была восстановлена.'''</big>
	 
Для просмотра списка последних удалений и восстановлений см. [[Special:Log/delete|журнал удалений]].",

# Namespace form on various pages
'namespace' => 'Пространство имён:',
'invert' => 'Обратить выделенное',

# Contributions
#
'contributions' => 'Вклад участника',
'mycontris'     => 'Мой вклад',
'contribsub'    => 'Вклад $1',
'nocontribs'    => 'Изменений, соответствующих заданным условиям, найдено не было.',
'ucnote'        => 'Ниже приводятся последние <strong>$1</strong> изменений, сделанных этим участником за последние <strong>$2</strong> дня(ей).',
'uclinks'       => 'Просмотреть $1 последних изменений; просмотреть за последние $2 дня(ей).',
'uctop'         => ' (последняя)' ,
'newbies'       => 'новичков',

'sp-newimages-showfrom' => 'Показать новые изображения, начиная с $1',

'sp-contributions-newest' => 'Новейшие',
'sp-contributions-oldest' => 'Старейшие',
'sp-contributions-newer'  => 'Более новые $1',
'sp-contributions-older'  => 'Более старые $1',
'sp-contributions-newbies-sub' => 'Вклад новичков',

# What links here
#
'whatlinkshere' => 'Ссылки сюда',
'notargettitle' => 'Не указана цель',
'notargettext'  => 'Вы не указали целевую страницу или участника для этого действия.',
'linklistsub'   => '(Список ссылок)',
'linkshere'             => "Следующие страницы ссылаются на '''[[:$1]]''':",
'nolinkshere'   => "На страницу '''[[:$1]]''' отсутствуют ссылки с других страниц.",
'isredirect'    => 'страница-перенаправление',
'istemplate'    => 'включение',

# Block/unblock IP
#
'blockip'               => 'Заблокировать участника',
'blockiptext'   => 'Используйте форму ниже, чтобы заблокировать возможность записи с определённого IP-адреса.
Это может быть сделано только для предотвращения вандализма и только в соответствии с
правилами изложенными в разделе [[{{ns:project}}:Правила]].
Ниже укажите конкретную причину (к примеру, процитируйте некоторые страницы с признаками вандализма).',
'ipaddress'             => 'IP-адрес',
'ipadressorusername' => 'IP-адрес или имя участника',
'ipbexpiry'             => 'Закончится через',
'ipbreason'             => 'Причина',
'ipbanononly'   => 'Блокировать только анонимных участников',
'ipbcreateaccount' => 'Запретить создание новых учётных записей',
'ipbsubmit'             => 'Заблокировать этот адрес/участника',
'ipbother'              => 'Другое время',
'ipboptions'            => '15 минут:15 minutes,2 часа:2 hours,6 часов:6 hours,12 часов:12 hours,1 день:1 day,3 дня:3 days,1 неделю:1 week,2 недели:2 weeks,1 месяц:1 month,3 месяца:3 months,6 месяцев:6 months,1 год:1 year,не определено:infinite',
'ipbotheroption'        => 'иное',
'badipaddress'  => 'IP-адрес записан в неправильном формате или участника с таким именем не существует.',
'blockipsuccesssub' => 'Блокировка произведена',
'blockipsuccesstext' => '[[{{ns:Special}}:Contributions/$1|«$1»]] заблокирован.
<br />См. [[{{ns:special}}:Ipblocklist|список заблокированных IP-адресов]].',
'unblockip'             => 'Разблокировать IP-адрес',
'unblockiptext' => 'Используйте форму ниже, чтобы восстановить возможность записи с ранее заблокированного
IP-адреса.',
'ipusubmit'             => 'Разблокировать этот адрес',
'ipblocklist'   => 'Список заблокированных IP-адресов и пользователей',
'blocklistline' => '$1, $2 заблокировал $3 ($4)',
'infiniteblock' => 'бессрочная блокировка',
'expiringblock' => 'блокировка завершится $1',
'anononlyblock' => 'только анонимов',
'createaccountblock' => 'создание учётных записей заблокировано',
'ipblocklistempty'      => 'Список блокировок пуст.',
'blocklink'     => 'заблокировать',
'unblocklink'   => 'разблокировать',
'contribslink'  => 'вклад',
'autoblocker'   => 'Вы автоматически заблокированы из-за совпадения вашего IP-адреса с «$1». Причина его блокировки — «$2».',
'blocklogpage'  => 'Журнал блокировок',
'blocklogentry' => '«[[$1]]» заблокирован на период $2',
'blocklogtext'  => 'Это — журнал блокирования и разблокирования участников. Автоматически блокируемые IP-адреса здесь не указываются. См. [[{{ns:special}}:Ipblocklist|Список текущих запретов и блокировок]].',
'unblocklogentry'       => '«$1» разблокирован',
'range_block_disabled'  => 'Способность администратора создавать диапазон блокирования отключена.',
'ipb_expiry_invalid'    => 'Недопустимый период действия.',
'ipb_already_blocked' => '«$1» уже заблокирован.',
'ip_range_invalid'      => 'Недопустимый диапазон IP-адресов.\n',
'proxyblocker'  => 'Блокировка прокси',
'ipb_cant_unblock' => 'Ошибка. Блокировка с идентификатором «$1» не найдена. Возможно, участника уже разблокировали.',
'proxyblockreason'      => 'Ваш IP-адрес заблокирован потому что это открытый прокси. Пожалуйста, свяжитесь с вашим интернет-провайдером  или службой поддержки и сообщите им об этой серьёзной проблеме безопасности.',
'proxyblocksuccess'     => 'Выполнено.',
'sorbs'         => 'SORBS DNSBL',
'sorbsreason'   => 'Ваш IP-адрес числится как открытый прокси в [http://www.sorbs.net SORBS] DNSBL.',
'sorbs_create_account_reason' => 'Ваш IP-адрес числится как открытый прокси в [http://www.sorbs.net SORBS] DNSBL. Вы не можете создать учётную запись.',

# Developer tools
#
'lockdb'      => 'Сделать базу данных доступной только для чтения',
'unlockdb'    => 'Восстановить возможность записи в базу данных',
'lockdbtext'  => 'Блокировка базы данных приостановит для всех участников возможность редактировать страницы, изменять настройки,
изменять списки наблюдения и производить другие действия, требующие доступа к базе данных.
Пожалуйста, подтвердите, что это — именно то, что вы хотите сделать, и что вы снимете блокировку как только закончите
процедуру обслуживания базы данных.',
'unlockdbtext'        => 'Разблокирование базы данных восстановит для всех участников
возможность редактировать страницы, изменять настройки, изменять списки наблюдения и производить
другие действия, требующие доступа к базе данных.
Пожалуйста, подтвердите, что вы намерены это сделать.',
'lockconfirm' => 'Да, я действительно хочу заблокировать базу данных на запись.',
'unlockconfirm'       => 'Да, я действительно хочу снять блокировку базы данных.',
'lockbtn'     => 'Сделать базу данных доступной только для чтения',
'unlockbtn'   => 'Восстановить возможность записи в базу данных',
'locknoconfirm'       => 'Вы не поставили галочку в поле подтверждения.',
'lockdbsuccesssub'    => 'База данных заблокирована',
'unlockdbsuccesssub'  => 'База данных разблокирована',
'lockdbsuccesstext'   => 'База данных проекта была заблокирована.
<br />Не забудьте [[Special:Unlockdb|убрать блокировку]] после завершения процедуры обслуживания.',
'unlockdbsuccesstext' => 'База данных проекта была разблокирована.',
'lockfilenotwritable' => 'Нет права на запись в файл блокировки базы данных. Чтобы заблокировать или разблокировать БД, веб-сервер должен иметь разрешение на запись в этот файл.',
'databasenotlocked' => 'База данных не была заблокирована.',

# Make sysop
'makesysoptitle'        => 'Сделать пользователя администратором',
'makesysoptext'         => 'Эта форма предназначена для бюрократов, и позволяет присваивать участникам статус администратора.
Наберите имя участника и нажмите кнопку, чтобы сделать участника администратором',
'makesysopname'         => 'Имя участника:',
'makesysopsubmit'       => 'Сделать этого участника администратором',
'makesysopok'           => '<strong>Участнику [[Участник:$1|$1]] присвоен статус администратора</strong>',
'makesysopfail'         => '<strong>Участника $1 невозможно сделать администратором. (Вы уверены, что правильно ввели его имя?)</strong>',
'setbureaucratflag' => 'Установить флаг «Бюрократ»',
'rightslog'             => 'Журнал прав пользователя',
'rightslogtext'         => 'Это журнал изменений прав пользователей.',
'rightslogentry'    => 'Для участника $1 изменены права доступа: с $2 на $3',
'rights'                        => 'Права:',
'set_user_rights'       => 'Установить права пользователя',
'user_rights_set'       => '<strong>Права пользователя $1 обновлены</strong></strong>',
'set_rights_fail'       => '<strong>Невозможно установить права для пользователя $1. (Проверьте, правильно ли введено его имя)</strong>',
'makesysop'         => 'Присвоить участнику статус администратора',
'already_sysop'     => 'Этот участник уже является администратором',
'already_bureaucrat' => 'Этот участник уже является бюрократом',
'rightsnone'            => '(нет)',

# Move page
#
'movepage'              => 'Переименовать страницу',
'movepagetext'  => 'Воспользовавшись формой ниже, вы переименуете страницу, одновременно переместив на новое место её журнал изменений.
Старое название станет перенаправлением на новое название.
Ссылки на старое название не будут изменены (пожалуйста проверьте наличие [[Special:DoubleRedirects|двойных]] и [[Special:BrokenRedirects|разорванных]] перенаправлений).
Вы обязаны убедиться в том, что ссылки и далее указывают туда, куда предполагалось.

Обратите внимание, что страница \'\'\'не будет\'\'\' переименована, если страница с новым названием уже существует (кроме случаев, если она является перенаправлением или пуста и и не имеет истории правок). Это означает, что вы можете переименовать страницу обратно в то название, которое у него только что было, если вы переименовали по ошибке, но вы не можете случайно затереть существующую страницу.

\'\'\'ПРЕДУПРЕЖДЕНИЕ!\'\'\'
Переименование может привести к масштабным и неожиданным изменениям для \'\'популярных\'\' страниц. Пожалуйста, прежде, чем вы продолжите, убедитесь, что вы понимаете все возможные последствия.',
'movepagetalktext' => 'Присоединённая страница обсуждения, если таковая есть,
будет также автоматически переименована, \'\'\'кроме случаев, когда:\'\'\'

*Не пустая страница обсуждения уже существует под таким же именем или
*Вы не поставили галочку в поле ниже.

В этих случаях, вы будете вынуждены переместить или объединить страницы вручную,
если это нужно.',
'movearticle'   => 'Переименовать страницу',
'movenologin'   => 'Вы не представились системе',
'movenologintext' => 'Вы должны [[Special:Userlogin|представиться системе]],
чтобы иметь возможность переименовать страницы.',
'newtitle'              => 'Новое название',
'movepagebtn'   => 'Переименовать страницу',
'pagemovedsub'  => 'Страница переименована',
'pagemovedtext' => 'Страница [[$1|«$1»]] переименована в [[$2|«$2»]].',
'articleexists' => 'Страница с таким именем уже существует или указанное вами название недопустимо.
Пожалуйста, выберите другое название.',
'talkexists'    => "'''Страница была переименована, но страница обсуждения
не может быть переименована, потому что страница с таким названием уже
существует. Пожалуйста, объедините их вручную.'''",
'movedto'               => 'переименована в',
'movetalk'              => 'Переименовать соответствующую страницу обсуждения',
'talkpagemoved' => 'Соответствующая страница обсуждения также переименована.',
'talkpagenotmoved' => 'Соответствующая страница обсуждения <strong>не</strong> была переименована.',
'1movedto2'             => '«[[$1]]» переименована в «[[$2]]»',
'1movedto2_redir' => '«[[$1]]» переименована в «[[$2]]», установлено перенаправление',
'movelogpage' => 'Журнал переименований',
'movelogpagetext' => 'Ниже представлен список переименованных страниц.',
'movereason'    => 'Причина',
'revertmove'    => 'откат',
'delete_and_move' => 'Удалить и переименовать',
'delete_and_move_text'  =>
'==Требуется удаление==

Страница с именем [[$1|«$1»]] уже существует. Хотите ли вы удалить её, чтобы сделать возможным переименование?',
'delete_and_move_reason' => 'Удалено для возможности переименования',
'delete_and_move_confirm' => 'Да, удалить эту страницу',
'selfmove' => 'Невозможно переименовать страницу: исходное и новое имя страницы совпадают.',
'immobile_namespace' => 'Невозможно переименовать страницу: новое или старое имя содержит зарезервированное служебное слово.',

# Export

'export'        => 'Экспортирование статей',
'exporttext'    => 'Вы можете экспортировать текст и журнал изменений конкретной страницы или набора страниц в XML, который потом может быть импортирован в другой вики-проект, работающую на программном обеспечении MediaWiki.

Чтобы экспортировать статьи, введите их наименования в поле редактирования, одно название на строку, и выберите хотите ли вы экспортировать всю историю изменений статей или только последние версии статей.

Вы также можете использовать специальный адрес для экспорта только последней версии. Например для страницы «{{int:mainpage}}» это будет адрес [[{{ns:Special}}:Export/{{int:mainpage}}]].',
'exportcuronly' => 'Включать только текущую версию, без полной предыстории',
'exportnohistory' => "----
'''Замечание:''' экспорт полной истории изменений страниц отключён из-за проблем с производительностью.",
'export-submit' => 'Экспортировать',

# Namespace 8 related

'allmessages'   => 'Системные сообщения',
'allmessagesname' => 'Сообщение',
'allmessagesdefault' => 'Текст по умолчанию',
'allmessagescurrent' => 'Текущий текст',
'allmessagestext'       => 'Ниже представлен список системных сообщений, доступных в пространстве имён «MediaWiki».',
'allmessagesnotsupportedUI' => 'Текущий установленный язык <strong>$1</strong> не поддерживается Special:Allmessages на этом сайте.',
'allmessagesnotsupportedDB' => "'''Special:Allmessages''' не поддерживается так как отключена опция '''wgUseDatabaseMessages'''.",
'allmessagesfilter' => 'Фильтр по имени сообщения:',
'allmessagesmodified' => 'Показать только изменённые',

# Thumbnails

'thumbnail-more'        => 'Увеличить',
'missingimage'          => '<strong>Изображение не найдено</strong><br /><em>$1</em>\n',
'filemissing'           => 'Файл не найден',
'thumbnail_error'   => 'Ошибка создания миниатюры: $1',

# Special:Import
'import'        => 'Импортирование страниц',
'importinterwiki' => 'Межвики импорт',
'import-interwiki-text' => 'Укажите вики и название импортируемой страницы.
Даты изменений и имена авторов будут сохранены.
Все операции межвики импорта регистрируются в [[Special:Log/import|соответствующем журнале]].',
'import-interwiki-history' => 'Копировать всю историю изменений этой страницы',
'import-interwiki-submit' => 'Импортировать',
'import-interwiki-namespace' => 'Помещать страницы в пространство имён:',

'importtext'    => 'Пожалуйста, экспортируйте страницу из исходной вики используя инструмент Special:Export, сохраните файл на диск, а затем загрузите его сюда.',
'importstart'   => "Импортирование страниц…",
'import-revision-count' => '$1 {{PLURAL:$1|версия|версии|версий}}',
'importnopages' => "Нет страниц для импортирования.",
'importfailed'  => 'Не удалось импортировать: $1',
'importunknownsource'   => "Неизвестный тип импортируемой страницы",
'importcantopen'        => "Невозможно открыть импортируемый файл",
'importbadinterwiki'    => "Неправильная интервики-ссылка",
'importnotext'  => 'Текст отсутствует',
'importsuccess' => 'Импортировано выполнено!',
'importhistoryconflict' => 'Конфликт существующих версий (возможно, эта страница уже была импортирована)',
'importnosources' => 'Не был выбран источник межвики импорта, прямая загрузка истории изменений отключена.',
'importnofile' => 'Файл для импорта не был загружен.',
'importuploaderror' => 'Ошибка загрузки файла для импорта, возможно размер файла превышает установленную норму.',

# import log
'importlogpage' => 'Журнал импорта',
'importlogpagetext' => 'Импортирование администраторами страниц с историей изменений из других вики.',
'import-logentry-upload' => '«$1» — импорт из файла',
'import-logentry-upload-detail' => '$1 версий',
'import-logentry-interwiki' => '«$1» — межвики импорт',
'import-logentry-interwiki-detail' => '$1 версий из $2',

# Keyboard access keys for power users
'accesskey-search' => 'f',
'accesskey-minoredit' => 'i',
'accesskey-save' => 's',
'accesskey-preview' => 'p',
'accesskey-diff' => 'v',
'accesskey-compareselectedversions' => 'v',
'accesskey-watch' => 'w',

# tooltip help for some actions, most are in Monobook.js
'tooltip-search' => 'Искать [alt-f]',
'tooltip-minoredit' => 'Отметить это изменение как незначительное [alt-i]',
'tooltip-save' => 'Сохранить ваши изменения [alt-s]',
'tooltip-preview' => 'Предварительный просмотр страницы, пожалуйста, используйте перед сохранением! [alt-p]',
'tooltip-diff' => 'Показать изменения, сделанные по отношению к исходному тексту. [alt-v]',
'tooltip-compareselectedversions' => 'Посмотреть разницу между двумя выбранными версиями этой страницы. [alt-v]',
'tooltip-watch' => 'Добавить текущую страницу в список наблюдения [alt-w]',

# stylesheets
'Common.css' => '/** Размещённый здесь CSS будет применяться ко всем темам оформления */',
'Monobook.css' => '/* Размещённый здесь CSS будет применяться к теме оформления Monobook */

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
#'Monobook.js' => '/* edit this file to change js things in the monobook skin */',

# Metadata
'nodublincore' => 'Метаданные Dublin Core RDF запрещены для этого сервера.',
'nocreativecommons' => 'Метаданные Creative Commons RDF запрещены для этого сервера.',
'notacceptable' => 'Вики-сервер не может предоставить данные в формате, который мог бы прочитать ваш браузер.<br />
The wiki server can\'t provide data in a format your client can read.',

# Attribution

'anonymous' => 'Анонимные пользователи {{grammar:genitive|{{SITENAME}}}}',
'siteuser' => 'Участник {{grammar:genitive|{{SITENAME}}}} $1',
'lastmodifiedatby' => 'Эта страница последний раз была изменена $2, $1 участником $3.',
'and' => 'и',
'othercontribs' => 'Основано на работе $1.',
'others' => 'другие',
'siteusers' => 'Участник(и) {{grammar:genitive|{{SITENAME}}}} $1',
'creditspage' => 'Благодарности',
'nocredits' => 'Нет списка участников для этой статьи',

# Spam protection

'spamprotectiontitle' => 'Спам-фильтр',
'spamprotectiontext' => 'Страница, которую вы пытаетесь сохранить заблокирована спам-фильтром. Вероятнее всего она содержит ссылку на внешний сайт.',
'spamprotectionmatch' => 'Следующее сообщение было получено от спам-фильтра: $1',
'subcategorycount' => 'В этой категории $1 {{PLURAL:$1|подкатегория|подкатегории|подкатегорий}}',
'categoryarticlecount' => 'В этой категории $1 {{PLURAL:$1|статья|статьи|статей}}',
'listingcontinuesabbrev' => ' (продолжение)',
'spambot_username' => 'Чистка спама',
'spam_reverting' => 'Откат к последней версии не содержащей ссылки на $1',
'spam_blanking' => 'Все версии содержат ссылки на $1, очистка',

# Info page
'infosubtitle' => 'Информация о странице',
'numedits' => 'Число правок (статья): $1',
'numtalkedits' => 'Число правок (страница обсуждения): $1',
'numwatchers' => 'Число наблюдателей: $1',
'numauthors' => 'Число различных авторов (статья): $1',
'numtalkauthors' => 'Число различных авторов (страница обсуждения): $1',

# Math options
'mw_math_png' => 'Всегда генерировать PNG',
'mw_math_simple' => 'HTML в простых случаях, иначе PNG',
'mw_math_html' => 'HTML, если возможно, иначе PNG',
'mw_math_source' => 'Оставить в разметке ТеХ (для текстовых браузеров)',
'mw_math_modern' => 'Как рекомендуется для современных браузеров',
'mw_math_mathml' => 'MathML, если возможно (экспериментальная опция)',

# Patrolling
'markaspatrolleddiff'   => 'Отметить как проверенную',
'markaspatrolledtext'   => 'Отметить эту статью как проверенную',
'markedaspatrolled'     => 'Отмечена как проверенная',
'markedaspatrolledtext' => 'Выбранная версия отмечена как проверенная.',
'rcpatroldisabled'      => 'Патрулирование последних изменений запрещено',
'rcpatroldisabledtext'  => 'Возможность патрулирования последних изменений в настоящее время отключена.',
'markedaspatrollederror'  => "Невозможно отметить как проверенную",
'markedaspatrollederrortext' => "Вы должны указать редакцию, которая будет отмечена как проверенная.",

# Monobook.js: tooltips and access keys for monobook
'Monobook.js' => '/* tooltips and access keys */
var ta = new Object();
ta[\'pt-userpage\'] = new Array(\'.\',\'Моя страница пользователя\');
ta[\'pt-anonuserpage\'] = new Array(\'.\',\'Страница пользователя для моего IP\');
ta[\'pt-mytalk\'] = new Array(\'n\',\'Моя страница обсуждений\');
ta[\'pt-anontalk\'] = new Array(\'n\',\'Страница обсуждений для моего IP\');
ta[\'pt-preferences\'] = new Array(\'\',\'Мои настройки\');
ta[\'pt-watchlist\'] = new Array(\'l\',\'Список страниц моего наблюдения\');
ta[\'pt-mycontris\'] = new Array(\'y\',\'Список страниц, которые я редактировал\');
ta[\'pt-login\'] = new Array(\'o\',\'Здесь можно зарегистрироваться в системе, но это необязательно\');
ta[\'pt-anonlogin\'] = new Array(\'o\',\'Здесь можно зарегистрироваться в системе, но это необязательно\');
ta[\'pt-logout\'] = new Array(\'o\',\'Отказаться от регистрации\');
ta[\'ca-talk\'] = new Array(\'t\',\'Обсуждение статьи\');
ta[\'ca-edit\'] = new Array(\'e\',\'Эту статью можно изменять. Перед сохранением изменений, пожалуйста, нажмите кнопку предварительного просмотра для визуальной проверки результата\');
ta[\'ca-addsection\'] = new Array(\'+\',\'Добавить комментарий к обсуждению\');
ta[\'ca-viewsource\'] = new Array(\'e\',\'Эта страница защищена от изменений, но вы можете посмотреть и скопировать её исходный текст\');
ta[\'ca-history\'] = new Array(\'h\',\'Журнал изменений страницы\');
ta[\'ca-protect\'] = new Array(\'=\',\'Защитить страницу от изменений\');
ta[\'ca-delete\'] = new Array(\'d\',\'Удалить эту страницу\');
ta[\'ca-undelete\'] = new Array(\'d\',\'Восстановить исправления страницы, сделанные до того, как она была удалена\');
ta[\'ca-move\'] = new Array(\'m\',\'Переименовать страницу\');
ta[\'ca-watch\'] = new Array(\'w\',\'Добавить эту страницу в ваш список наблюдения\');
ta[\'ca-unwatch\'] = new Array(\'w\',\'Удалить эту страницу из вашего списка наблюдения\');
ta[\'search\'] = new Array(\'f\',\'Искать это слово\');
ta[\'p-logo\'] = new Array(\'\',\'Заглавная страница\');
ta[\'n-mainpage\'] = new Array(\'z\',\'Перейти на заглавную страницу\');
ta[\'n-portal\'] = new Array(\'\',\'О проекте, о том, что вы можете сделать, где что находится\');
ta[\'n-currentevents\'] = new Array(\'\',\'Список текущих событий\');
ta[\'n-recentchanges\'] = new Array(\'r\',\'Список последних изменений\');
ta[\'n-randompage\'] = new Array(\'x\',\'Посмотреть случайную страницу\');
ta[\'n-help\'] = new Array(\'\',\'Справочник по проекту «{{SITENAME}}»\');
ta[\'n-sitesupport\'] = new Array(\'\',\'Поддержите проект\');
ta[\'t-whatlinkshere\'] = new Array(\'j\',\'Список всех страниц, которые ссылаются на эту страницу\');
ta[\'t-recentchangeslinked\'] = new Array(\'k\',\'Последние изменения в страницах, которые ссылаются на эту страницу\');
ta[\'feed-rss\'] = new Array(\'\',\'Трансляция в формате RSS для этой страницы\');
ta[\'feed-atom\'] = new Array(\'\',\'Трансляция в формате Atom для этой страницы\');
ta[\'t-contributions\'] = new Array(\'\',\'Список страниц, которые изменял этот участник\');
ta[\'t-emailuser\'] = new Array(\'\',\'Отправить письмо этому участнику\');
ta[\'t-upload\'] = new Array(\'u\',\'Загрузить изображения или мультимедиа-файлы\');
ta[\'t-specialpages\'] = new Array(\'q\',\'Список служебных страниц\');
ta[\'t-print\']=new Array(\'\', \'Версия без кнопок, пригодная для распечатки\');
ta[\'t-permalink\'] = new Array(\'\', \'Ссылка на текущую версию этой статьи\');
ta[\'ca-nstab-main\'] = new Array(\'c\',\'Содержание статьи\');
ta[\'ca-nstab-user\'] = new Array(\'c\',\'Персональная страница участника\');
ta[\'ca-nstab-media\'] = new Array(\'c\',\'Мультимедиа-файл\');
ta[\'ca-nstab-special\'] = new Array(\'\',\'Это служебная страница, она недоступна для редактирования\');
ta[\'ca-nstab-project\'] = new Array(\'a\',\'Страница проекта\');
ta[\'ca-nstab-image\'] = new Array(\'c\',\'Страница изображения\');
ta[\'ca-nstab-mediawiki\'] = new Array(\'c\',\'Страница сообщения MediaWiki\');
ta[\'ca-nstab-template\'] = new Array(\'c\',\'Страница шаблона\');
ta[\'ca-nstab-help\'] = new Array(\'c\',\'Страница справки\');
ta[\'ca-nstab-category\'] = new Array(\'c\',\'Страница категории\');',

# image deletion
'deletedrevision' => 'Удалена старая версия $1.',

# browsing diffs
'previousdiff' => '← К предыдущему изменению',
'nextdiff' => 'К след. изменению →',

'imagemaxsize' => 'Ограничивать изображения на странице изображений до:',
'thumbsize'    => 'Размер уменьшенной версии изображения:',
'showbigimage' => 'Загрузить с высоким разрешением ($1 × $2, $3 кбайт)',

'newimages' => 'Галерея новых файлов',
'showhidebots' => '($1 ботов)',
'noimages'  => 'Изображения отсутствуют.',

# labels for User: and Title: on Special:Log pages
'specialloguserlabel' => 'Участник:',
'speciallogtitlelabel' => 'Заголовок:',

'passwordtooshort' => 'Введённый пароль слишком короткий. Пароль должен состоять не менее чем из $1 символов.',

# Media Warning
'mediawarning' => '\'\'\'Внимание\'\'\': этот файл может содержать вредоносный программный код, выполнение которого способно подвергнуть риску вашу систему. <hr />',
'fileinfo' => '$1 Кб, MIME-тип: <code>$2</code>',

# Exif data
'metadata' => 'Метаданные',
'metadata-help' => 'Файл содержит дополнительные данные, обычно добавляемые цифровыми камерами или сканерами. Если файл после создания редактировался, то некоторые параметры могут не соответствовать текущему изображению.',
'metadata-expand' => 'Показать дополнительные данные',
'metadata-collapse' => 'Скрыть дополнительные данные',
'metadata-fields' => 'Поля метаданных перечисленные в этом списке будут показаны на странице изображения по умолчанию, остальные будут скрыты.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength',

# Exif tags
'exif-imagewidth' =>'Ширина',
'exif-imagelength' =>'Высота',
'exif-bitspersample' =>'Глубина цвета',
'exif-compression' =>'Метод сжатия',
'exif-photometricinterpretation' =>'Цветовая модель',
'exif-orientation' =>'Ориентация кадра',
'exif-samplesperpixel' =>'Количество цветовых компонентов',
'exif-planarconfiguration' =>'Принцип организации данных',
'exif-ycbcrsubsampling' =>'Отношение размеров компонент Y и C',
'exif-ycbcrpositioning' =>'Порядок размещения компонент Y и C',
'exif-xresolution' =>'Горизонтальное разрешение',
'exif-yresolution' =>'Вертикальное разрешение',
'exif-resolutionunit' =>'Единица измерения разрешения',
'exif-stripoffsets' =>'Положение блока данных',
'exif-rowsperstrip' =>'Количество строк в 1 блоке',
'exif-stripbytecounts' =>'Размер сжатого блока',
'exif-jpeginterchangeformat' =>'Положение начала блока preview',
'exif-jpeginterchangeformatlength' =>'Размер данных блока preview',
'exif-transferfunction' =>'Функция преобразования цветового пространства',
'exif-whitepoint' =>'Цветность белой точки',
'exif-primarychromaticities' =>'Цветность основных цветов',
'exif-ycbcrcoefficients' =>'Коэффициенты преобразования цветовой модели',
'exif-referenceblackwhite' =>'Положение белой и чёрной точек',
'exif-datetime' =>'Дата и время изменения файла',
'exif-imagedescription' =>'Название изображения',
'exif-make' =>'Производитель камеры',
'exif-model' =>'Модель камеры',
'exif-software' =>'Программное обеспечение',
'exif-artist' =>'Автор',
'exif-copyright' =>'Владелец авторского права',
'exif-exifversion' =>'Версия Exif',
'exif-flashpixversion' =>'Поддерживаемая версия FlashPix',
'exif-colorspace' =>'Цветовое пространство',
'exif-componentsconfiguration' =>'Конфигурация цветовых компонентов',
'exif-compressedbitsperpixel' =>'Глубина цвета после сжатия',
'exif-pixelydimension' =>'Полная высота изображения',
'exif-pixelxdimension' =>'Полная ширина изображения',
'exif-makernote' =>'Дополнительные данные производителя',
'exif-usercomment' =>'Дополнительный комментарий',
'exif-relatedsoundfile' =>'Файл звукового комментария',
'exif-datetimeoriginal' =>'Оригинальные дата и время',
'exif-datetimedigitized' =>'Дата и время оцифровки',
'exif-subsectime' =>'Доли секунд времени изменения файла',
'exif-subsectimeoriginal' =>'Доли секунд оригинального времени',
'exif-subsectimedigitized' =>'Доли секунд времени оцифровки',
'exif-exposuretime' =>'Время экспозиции',
'exif-exposuretime-format' => '$1 с ($2)',
'exif-fnumber' =>'Число диафрагмы',
'exif-fnumber-format' =>'f/$1',
'exif-exposureprogram' =>'Программа экспозиции',
'exif-spectralsensitivity' =>'Спектральная чувствительность',
'exif-isospeedratings' =>'Светочувствительность ISO',
'exif-oecf' =>'OECF (коэффициент оптоэлектрического преобразования)',
'exif-shutterspeedvalue' =>'Выдержка',
'exif-aperturevalue' =>'Диафрагма',
'exif-brightnessvalue' =>'Яркость',
'exif-exposurebiasvalue' =>'Компенсация экспозиции',
'exif-maxaperturevalue' =>'Минимальное число диафрагмы',
'exif-subjectdistance' =>'Расстояние до объекта',
'exif-meteringmode' =>'Режим замера экспозиции',
'exif-lightsource' =>'Источник света',
'exif-flash' =>'Статус вспышки',
'exif-focallength' =>'Фокусное расстояние',
'exif-focallength-format' =>'$1 мм',
'exif-subjectarea' =>'Положение и площадь объекта съёмки',
'exif-flashenergy' =>'Энергия вспышки',
'exif-spatialfrequencyresponse' =>'Пространственная частотная характеристика',
'exif-focalplanexresolution' =>'Разрешение по X в фокальной плоскости',
'exif-focalplaneyresolution' =>'Разрешение по Y в фокальной плоскости',
'exif-focalplaneresolutionunit' =>'Единица измерения разрешения в фокальной плоскости',
'exif-subjectlocation' =>'Положение объекта относительно левого верхнего угла',
'exif-exposureindex' =>'Индекс экспозиции',
'exif-sensingmethod' =>'Тип сенсора',
'exif-filesource' =>'Источник файла',
'exif-scenetype' =>'Тип сцены',
'exif-cfapattern' =>'Тип цветового фильтра',
'exif-customrendered' =>'Дополнительная обработка',
'exif-exposuremode' =>'Режим выбора экспозиции',
'exif-whitebalance' =>'Баланс белого',
'exif-digitalzoomratio' =>'Коэффициент цифрового увеличения (цифровой зум)',
'exif-focallengthin35mmfilm' =>'Эквивалентное фокусное расстояние (для 35 мм плёнки)',
'exif-scenecapturetype' =>'Тип сцены при съёмке',
'exif-gaincontrol' =>'Повышение яркости',
'exif-contrast' =>'Контрастность',
'exif-saturation' =>'Насыщенность',
'exif-sharpness' =>'Резкость',
'exif-devicesettingdescription' =>'Описание предустановок камеры',
'exif-subjectdistancerange' =>'Расстояние до объекта съёмки',
'exif-imageuniqueid' =>'Номер изображения (ID)',

'exif-gpsversionid' =>'Версия блока GPS-информации',
'exif-gpslatituderef' =>'Индекс широты',
'exif-gpslatitude' =>'Широта',
'exif-gpslongituderef' =>'Индекс долготы',
'exif-gpslongitude' =>'Долгота',
'exif-gpsaltituderef' =>'Индекс высоты',
'exif-gpsaltitude' =>'Высота',
'exif-gpstimestamp' =>'Точное время по UTC',
'exif-gpssatellites' =>'Описание использованных спутников',
'exif-gpsstatus' =>'Статус приёмника в момент съёмки',
'exif-gpsmeasuremode' =>'Метод измерения положения',
'exif-gpsdop' =>'Точность измерения',
'exif-gpsspeedref' =>'Единицы измерения скорости',
'exif-gpsspeed' =>'Скорость движения',
'exif-gpstrackref' =>'Тип азимута приёмника GPS (истинный, магнитный)',
'exif-gpstrack' =>'Азимут приёмника GPS',
'exif-gpsimgdirectionref' =>'Тип азимута изображения (истинный, магнитный)',
'exif-gpsimgdirection' =>'Азимут изображения',
'exif-gpsmapdatum' =>'Использованная геодезическая система координат',
'exif-gpsdestlatituderef' =>'Индекс долготы объекта',
'exif-gpsdestlatitude' =>'Долгота объекта',
'exif-gpsdestlongituderef' =>'Индекс широты объекта',
'exif-gpsdestlongitude' =>'Широта объекта',
'exif-gpsdestbearingref' =>'Тип пеленга объекта (истинный, магнитный)',
'exif-gpsdestbearing' =>'Пеленг объекта',
'exif-gpsdestdistanceref' =>'Единицы измерения расстояния',
'exif-gpsdestdistance' =>'Расстояние',
'exif-gpsprocessingmethod' =>'Метод вычисления положения',
'exif-gpsareainformation' =>'Название области GPS',
'exif-gpsdatestamp' =>'Дата',
'exif-gpsdifferential' =>'Дифференциальная поправка',

# Exif attributes

'exif-compression-1' => 'Несжатый',
'exif-compression-6' => 'JPEG',

'exif-photometricinterpretation-2' => 'RGB',
'exif-photometricinterpretation-6' => 'YCbCr',

'exif-orientation-1' => 'Нормальная', // 0th row: top; 0th column: left
'exif-orientation-2' => 'Отражено по горизонтали', // 0th row: top; 0th column: right
'exif-orientation-3' => 'Повёрнуто на 180°', // 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Отражено по вертикали', // 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Повёрнуто на 90° против часовой стрелки и отражено по вертикали', // 0th row: left; 0th column: top
'exif-orientation-6' => 'Повёрнуто на 90° по часовой стрелке', // 0th row: right; 0th column: top
'exif-orientation-7' => 'Повёрнуто на 90° по часовой стрелке и отражено по вертикали', // 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Повёрнуто на 90° против часовой стрелки', // 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'chunky format',
'exif-planarconfiguration-2' => 'planar format',

'exif-colorspace-1' => 'sRGB',
'exif-colorspace-ffff.h' => 'FFFF.H',

'exif-componentsconfiguration-0' => 'не существует',
'exif-componentsconfiguration-1' => 'Y',
'exif-componentsconfiguration-2' => 'Cb',
'exif-componentsconfiguration-3' => 'Cr',
'exif-componentsconfiguration-4' => 'R',
'exif-componentsconfiguration-5' => 'G',
'exif-componentsconfiguration-6' => 'B',

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

'exif-meteringmode-0' => 'Неизвестно',
'exif-meteringmode-1' => 'Средний',
'exif-meteringmode-2' => 'Центровзвешенный',
'exif-meteringmode-3' => 'Точечный',
'exif-meteringmode-4' => 'Мультиточечный',
'exif-meteringmode-5' => 'Матричный',
'exif-meteringmode-6' => 'Частичный',
'exif-meteringmode-255' => 'Другой',

'exif-lightsource-0' => 'Неизвестно',
'exif-lightsource-1' => 'Дневной свет',
'exif-lightsource-2' => 'Лампа дневного света',
'exif-lightsource-3' => 'Лампа накаливания',
'exif-lightsource-4' => 'Вспышка',
'exif-lightsource-9' => 'Хорошая погода',
'exif-lightsource-10' => 'Облачно',
'exif-lightsource-11' => 'Тень',
'exif-lightsource-12' => 'Лампа дневного света тип D (5700 − 7100K)',
'exif-lightsource-13' => 'Лампа дневного света тип N (4600 − 5400K)',
'exif-lightsource-14' => 'Лампа дневного света тип W (3900 − 4500K)',
'exif-lightsource-15' => 'Лампа дневного света тип WW (3200 − 3700K)',
'exif-lightsource-17' => 'Стандартный источник света типа A',
'exif-lightsource-18' => 'Стандартный источник света типа B',
'exif-lightsource-19' => 'Стандартный источник света типа C',
'exif-lightsource-20' => 'D55',
'exif-lightsource-21' => 'D65',
'exif-lightsource-22' => 'D75',
'exif-lightsource-23' => 'D50',
'exif-lightsource-24' => 'Студийная лампа стандарта ISO',
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

// Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'северной широты',
'exif-gpslatitude-s' => 'южной широты',

// Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'восточной долготы',
'exif-gpslongitude-w' => 'западной долготы',

'exif-gpsstatus-a' => 'Измерение не закончено',
'exif-gpsstatus-v' => 'Готов к передаче данных',

'exif-gpsmeasuremode-2' => 'Измерение 2-х координат',
'exif-gpsmeasuremode-3' => 'Измерение 3-х координат',

// Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'км/час',
'exif-gpsspeed-m' => 'миль/час',
'exif-gpsspeed-n' => 'узлов',

// Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'истинный',
'exif-gpsdirection-m' => 'магнитный',

# external editor support
'edit-externally' => 'Редактировать этот файл, используя внешнюю программу',
'edit-externally-help' => 'Подробности см. на странице [http://meta.wikimedia.org/wiki/Help:External_editors Meta:Help:External_editors].',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'все',
'imagelistall' => 'все',
'watchlistall1' => 'все',
'watchlistall2' => 'все',
'namespacesall' => 'все',

# E-mail address confirmation
'confirmemail' => 'Подтверждение адреса электронной почты',
'confirmemail_noemail' => 'Вы не задали адрес электронной почты в [[Special:Preferences|настройках]], либо он некорректен.',
'confirmemail_text' => 'Вики-движок требует подтверждения адреса электронной почты перед тем, как начать с ним работать.
Нажмите на кнопку, чтобы на указанный адрес было отправлено письмо, содержащее ссылку на специальную страницу, после открытия которой в браузере адрес электронной почты будет считается подтверждённым.',

'confirmemail_send' => 'Отправить письмо с запросом на подтверждение',
'confirmemail_sent' => 'Письмо с запросом на подтверждение отправлено.',
'confirmemail_sendfailed' => 'Невозможно отправить письмо с запросом на подтверждение. Проверьте правильность адреса электронной почты.',
'confirmemail_invalid' => 'Неправильный код подтверждения, или срок действия кода истёк.',
'confirmemail_needlogin' => 'Вы должны $1 для подтверждения вашего адреса электронной почты.',
'confirmemail_success' => 'Ваш адрес электронной почты подтверждён.',
'confirmemail_loggedin' => 'Ваш адрес электронной почты подтверждён.',
'confirmemail_error' => 'Во время процедуры подтверждения адреса электронной почты произошла ошибка.',

'confirmemail_subject' => '{{SITENAME}}:Запрос на подтверждения адреса эл. почты',
'confirmemail_body' => 'Кто-то с IP-адресом $1, зарегистрировал на сервере проекта {{SITENAME}} учётную запись
«$2», указав ваш адрес электронной почты.

Чтобы подтвердить, что вы разрешаете использовать ваш адрес электронной почты  в этом проекте, откройте в браузере приведённую ниже ссылку (это нужно сделать до $4):

$3

Если вы не отправляли подобного запроса — просто проигнорируйте данное письмо.',

# Inputbox extension, may be useful in other contexts as well
'tryexact' => 'Строгий поиск',
'searchfulltext' => 'Полнотекстовый поиск',
'createarticle' => 'Создать статью',

# Scary transclusion
'scarytranscludedisabled' => '[«Interwiki transcluding» отключён]',
'scarytranscludefailed' => '[К сожалению, не удалось обращение к шаблону $1]',
'scarytranscludetoolong' => '[К сожалению, URL слишком длинный]',

# Trackbacks
'trackbackbox' => '<div id="mw_trackbacks">
Trackback для этой статьи:<br />
$1
</div>',
'trackbackremove' => ' ([$1 удалить])',
'trackbacklink' => 'Trackback',
'trackbackdeleteok' => 'Trackback был удалён.',

# delete conflict

'deletedwhileediting' => 'Внимание: пока вы редактировали эту страницу она была удалена!',
'confirmrecreate' => 'Участник [[User:$1|$1]] ([[User talk:$1|обсуждение]]) удалил эту страницу после того, как вы начали её редактировать, причина удаления:
: \'\'$2\'\'
Пожалуйста, подтвердите, что вы хотите восстановить эту страницу.',
'recreate' => 'Создать заново',
'tooltip-recreate' => 'Восстановить страницу несмотря на то, что она была удалена',

'unit-pixel' => ' пикс.',

# HTML dump
'redirectingto' => 'Перенаправление на страницу [[$1]]…',

# action=purge
'confirm_purge' => "Очистить кэш этой страницы?\n\n$1",
'confirm_purge_button' => 'OK',

'youhavenewmessagesmulti' => "Вы получили новые сообщения на $1",
'newtalkseperator' => ',_',
'searchcontaining' => "Поиск статей, содержащих «$1».",
'searchnamed' => "Поиск статей называющихся «$1».",
'articletitles' => "Статьи начинающиеся с «$1»",
'hideresults' => 'Скрыть результаты',

# DISPLAYTITLE
'displaytitle' => '(Ссылка на эту страницу — $1)',
 
'loginlanguagelabel' => 'Язык: $1',

# Multipage image navigation
'imgmultipageprev' => '← предыдущая страница',
'imgmultipagenext' => 'следующая страница →',
'imgmultigo' => 'Перейти!',
'imgmultigotopre' => 'Перейти на страницу',

# Table pager
'ascending_abbrev' => 'возр',
'descending_abbrev' => 'убыв',
'table_pager_next' => 'Следующая страница',
'table_pager_prev' => 'Предыдущая страница',
'table_pager_first' => 'Первая страница',
'table_pager_last' => 'Последняя страница',
'table_pager_limit' => 'Показать $1 элементов на странице',
'table_pager_limit_submit' => 'Выполнить',
'table_pager_empty' => 'Не найдено',
);

?>

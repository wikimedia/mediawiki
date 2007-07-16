<?php
/**
  * @addtogroup Language
  */
$namespaceNames = array(
	NS_MEDIA            => "Медија",
	NS_SPECIAL          => "Посебно",
	NS_MAIN             => "",
	NS_TALK             => "Разговор",
	NS_USER             => "Корисник",
	NS_USER_TALK        => "Разговор_са_корисником",
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => "Разговор_о_$1",
	NS_IMAGE            => "Слика",
	NS_IMAGE_TALK       => "Разговор_о_слици",
	NS_MEDIAWIKI        => "МедијаВики",
	NS_MEDIAWIKI_TALK   => "Разговор_о_МедијаВикију",
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Разговор_о_шаблону',
	NS_HELP             => 'Помоћ',
	NS_HELP_TALK        => 'Разговор_о_помоћи',
	NS_CATEGORY         => 'Категорија',
	NS_CATEGORY_TALK    => 'Разговор_о_категорији',
);

# Aliases to latin namespaces 
$namespaceAliases = array(
	"Medija"                  => NS_MEDIA,
	"Posebno"                 => NS_SPECIAL,
	"Razgovor"                => NS_TALK,
	"Korisnik"                => NS_USER,
	"Razgovor_sa_korisnikom"  => NS_USER_TALK,
	"Razgovor_o_$1"           => NS_PROJECT_TALK,
	"Slika"                   => NS_IMAGE,
	"Razgovor_o_slici"        => NS_IMAGE_TALK,
	"MedijaViki"              => NS_MEDIAWIKI,
	"Razgovor_o_MedijaVikiju" => NS_MEDIAWIKI_TALK,
	'Šablon'                  => NS_TEMPLATE,
	'Razgovor_o_šablonu'      => NS_TEMPLATE_TALK,
	'Pomoć'                   => NS_HELP,
	'Razgovor_o_pomoći'      => NS_HELP_TALK,
	'Kategorija'              => NS_CATEGORY,
	'Razgovor_o_kategoriji'   => NS_CATEGORY_TALK,
);

$skinNames = array(
 "Обична", "Носталгија", "Келнско плаво"
);

$extraUserToggles = array(
	'nolangconversion',
);

$datePreferenceMigrationMap = array(
	'default',
	'hh:mm d. month y.',
	'hh:mm d month y',
	'hh:mm dd.mm.yyyy',
	'hh:mm d.m.yyyy',
	'hh:mm d. mon y.',
	'hh:mm d mon y',
	'h:mm d. month y.',
	'h:mm d month y',
	'h:mm dd.mm.yyyy',
	'h:mm d.m.yyyy',
	'h:mm d. mon y.',
	'h:mm d mon y',
);

$datePreferences = array(
	'default',
	'hh:mm d. month y.',
	'hh:mm d month y',
	'hh:mm dd.mm.yyyy',
	'hh:mm d.m.yyyy',
	'hh:mm d. mon y.',
	'hh:mm d mon y',
	'h:mm d. month y.',
	'h:mm d month y',
	'h:mm dd.mm.yyyy',
	'h:mm d.m.yyyy',
	'h:mm d. mon y.',
	'h:mm d mon y',
);

$defaultDateFormat = 'hh:mm d. month y.';

$dateFormats = array(
	/*
	'Није битно',
	'06:12, 5. јануар 2001.',
	'06:12, 5 јануар 2001',
	'06:12, 05.01.2001.',
	'06:12, 5.1.2001.',
	'06:12, 5. јан 2001.',
	'06:12, 5 јан 2001',
	'6:12, 5. јануар 2001.',
	'6:12, 5 јануар 2001',
	'6:12, 05.01.2001.',
	'6:12, 5.1.2001.',
	'6:12, 5. јан 2001.',
	'6:12, 5 јан 2001',
	 */
	
	'hh:mm d. month y. time'    => 'H:i',
	'hh:mm d month y time'      => 'H:i',
	'hh:mm dd.mm.yyyy time'     => 'H:i',
	'hh:mm d.m.yyyy time'       => 'H:i',
	'hh:mm d. mon y. time'      => 'H:i',
	'hh:mm d mon y time'        => 'H:i',
	'h:mm d. month y. time'     => 'G:i',
	'h:mm d month y time'       => 'G:i',
	'h:mm dd.mm.yyyy time'      => 'G:i',
	'h:mm d.m.yyyy time'        => 'G:i',
	'h:mm d. mon y. time'       => 'G:i',
	'h:mm d mon y time'         => 'G:i',

	'hh:mm d. month y. date'    => 'j. F Y.',
	'hh:mm d month y date'      => 'j F Y',  
	'hh:mm dd.mm.yyyy date'     => 'd.m.Y',  
	'hh:mm d.m.yyyy date'       => 'j.n.Y',  
	'hh:mm d. mon y. date'      => 'j. M Y.',
	'hh:mm d mon y date'        => 'j M Y',  
	'h:mm d. month y. date'     => 'j. F Y.',
	'h:mm d month y date'       => 'j F Y',  
	'h:mm dd.mm.yyyy date'      => 'd.m.Y',  
	'h:mm d.m.yyyy date'        => 'j.n.Y',  
	'h:mm d. mon y. date'       => 'j. M Y.',
	'h:mm d mon y date'         => 'j M Y',  

	'hh:mm d. month y. both'    =>'H:i, j. F Y.', 
	'hh:mm d month y both'      =>'H:i, j F Y',   
	'hh:mm dd.mm.yyyy both'     =>'H:i, d.m.Y',   
	'hh:mm d.m.yyyy both'       =>'H:i, j.n.Y',   
	'hh:mm d. mon y. both'      =>'H:i, j. M Y.', 
	'hh:mm d mon y both'        =>'H:i, j M Y',   
	'h:mm d. month y. both'     =>'G:i, j. F Y.', 
	'h:mm d month y both'       =>'G:i, j F Y',   
	'h:mm dd.mm.yyyy both'      =>'G:i, d.m.Y',   
	'h:mm d.m.yyyy both'        =>'G:i, j.n.Y',   
	'h:mm d. mon y. both'       =>'G:i, j. M Y.', 
	'h:mm d mon y both'         =>'G:i, j M Y',   
);

/* NOT USED IN STABLE VERSION */
$magicWords = array(
#	ID                                CASE SYNONYMS
	'redirect'               => array( 0, '#Преусмери', '#redirect', '#преусмери', '#ПРЕУСМЕРИ' ),
	'notoc'                  => array( 0, '__NOTOC__', '__БЕЗСАДРЖАЈА__' ),
	'forcetoc'               => array( 0, '__FORCETOC__', '__ФОРСИРАНИСАДРЖАЈ__' ),
	'toc'                    => array( 0, '__TOC__', '__САДРЖАЈ__' ),
	'noeditsection'          => array( 0, '__NOEDITSECTION__', '__БЕЗ_ИЗМЕНА__', '__БЕЗИЗМЕНА__' ),
	'start'                  => array( 0, '__START__', '__ПОЧЕТАК__' ),
	'currentmonth'           => array( 1, 'CURRENTMONTH', 'ТРЕНУТНИМЕСЕЦ' ),
	'currentmonthname'       => array( 1, 'CURRENTMONTHNAME', 'ТРЕНУТНИМЕСЕЦИМЕ' ),
	'currentmonthnamegen'    => array( 1, 'CURRENTMONTHNAMEGEN', 'ТРЕНУТНИМЕСЕЦГЕН' ),
	'currentmonthabbrev'     => array( 1, 'CURRENTMONTHABBREV', 'ТРЕНУТНИМЕСЕЦСКР' ),
	'currentday'             => array( 1, 'CURRENTDAY', 'ТРЕНУТНИДАН' ),
	'currentdayname'         => array( 1, 'CURRENTDAYNAME', 'ТРЕНУТНИДАНИМЕ' ),
	'currentyear'            => array( 1, 'CURRENTYEAR', 'ТРЕНУТНАГОДИНА' ),
	'currenttime'            => array( 1, 'CURRENTTIME', 'ТРЕНУТНОВРЕМЕ' ),
	'numberofarticles'       => array( 1, 'NUMBEROFARTICLES', 'БРОЈЧЛАНАКА' ),
	'numberoffiles'          => array( 1, 'NUMBEROFFILES', 'БРОЈДАТОТЕКА', 'БРОЈФАЈЛОВА' ),
	'pagename'               => array( 1, 'PAGENAME', 'СТРАНИЦА' ),
	'pagenamee'              => array( 1, 'PAGENAMEE', 'СТРАНИЦЕ' ),
	'namespace'              => array( 1, 'NAMESPACE', 'ИМЕНСКИПРОСТОР' ),
	'namespacee'             => array( 1, 'NAMESPACEE', 'ИМЕНСКИПРОСТОРИ' ),
	'fullpagename'           => array( 1, 'FULLPAGENAME', 'ПУНОИМЕСТРАНЕ' ),
	'fullpagenamee'          => array( 1, 'FULLPAGENAMEE', 'ПУНОИМЕСТРАНЕЕ' ),
	'msg'                    => array( 0, 'MSG:', 'ПОР:' ),
	'subst'                  => array( 0, 'SUBST:', 'ЗАМЕНИ:' ),
	'msgnw'                  => array( 0, 'MSGNW:', 'НВПОР:' ),
	'img_thumbnail'          => array( 1, 'thumbnail', 'thumb', 'мини' ),
	'img_manualthumb'        => array( 1, 'thumbnail=$1', 'thumb=$1', 'мини=$1' ),
	'img_right'              => array( 1, 'right', 'десно', 'д' ),
	'img_left'               => array( 1, 'left', 'лево', 'л' ),
	'img_none'               => array( 1, 'none', 'н', 'без' ),
	'img_width'              => array( 1, '$1px', '$1пискел' , '$1п' ),
	'img_center'             => array( 1, 'center', 'centre', 'центар', 'ц' ),
	'img_framed'             => array( 1, 'framed', 'enframed', 'frame', 'оквир', 'рам' ),
	'int'                    => array( 0, 'INT:', 'ИНТ:' ),
	'sitename'               => array( 1, 'SITENAME', 'ИМЕСАЈТА' ),
	'ns'                     => array( 0, 'NS:', 'ИП:' ),
	'localurl'               => array( 0, 'LOCALURL:', 'ЛОКАЛНААДРЕСА:' ),
	'localurle'              => array( 0, 'LOCALURLE:', 'ЛОКАЛНЕАДРЕСЕ:' ),
	'server'                 => array( 0, 'SERVER', 'СЕРВЕР' ),
	'servername'             => array( 0, 'SERVERNAME', 'ИМЕСЕРВЕРА' ),
	'scriptpath'             => array( 0, 'SCRIPTPATH', 'СКРИПТА' ),
	'grammar'                => array( 0, 'GRAMMAR:', 'ГРАМАТИКА:' ),
	'notitleconvert'         => array( 0, '__NOTITLECONVERT__', '__NOTC__', '__БЕЗКН__', '__BEZKN__' ),
	'nocontentconvert'       => array( 0, '__NOCONTENTCONVERT__', '__NOCC__', '__БЕЗЦЦ__' ),
	'currentweek'            => array( 1, 'CURRENTWEEK', 'ТРЕНУТНАНЕДЕЉА' ),
	'currentdow'             => array( 1, 'CURRENTDOW', 'ТРЕНУТНИДОВ' ),
	'revisionid'             => array( 1, 'REVISIONID', 'ИДРЕВИЗИЈЕ' ),
	'plural'                 => array( 0, 'PLURAL:', 'МНОЖИНА:' ),
	'fullurl'                => array( 0, 'FULLURL:', 'ПУНУРЛ:' ),
	'fullurle'               => array( 0, 'FULLURLE:', 'ПУНУРЛЕ:' ),
	'lcfirst'                => array( 0, 'LCFIRST:', 'ЛЦПРВИ:' ),
	'ucfirst'                => array( 0, 'UCFIRST:', 'УЦПРВИ:' ),
	'lc'                     => array( 0, 'LC:', 'ЛЦ:' ),
	'uc'                     => array( 0, 'UC:', 'УЦ:' ),
);
$separatorTransformTable = array(',' => '.', '.' => ',' );


$messages = array(
# User preference toggles
'tog-underline'               => 'Подвуци везе',
'tog-highlightbroken'         => 'Форматирај покварене везе <a href="" class="new">овако</a> (алтернатива: овако<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Уравнај пасусе',
'tog-hideminor'               => 'Сакриј мале измене у списку скорашњих измена',
'tog-extendwatchlist'         => 'Побољшан списак надгледања',
'tog-usenewrc'                => 'Побољшан списак скорашњих измена (захтева JavaScript)',
'tog-numberheadings'          => 'Аутоматски нумериши поднаслове',
'tog-showtoolbar'             => 'Прикажи дугмиће за измене (захтева JavaScript)',
'tog-editondblclick'          => 'Мењај странице двоструким кликом (захтева JavaScript)',
'tog-editsection'             => 'Омогући измену делова [уреди] везама',
'tog-editsectiononrightclick' => 'Омогући измену делова десним кликом<br />на њихове наслове (захтева JavaScript)',
'tog-showtoc'                 => 'Прикажи садржај (у чланцима са више од 3 поднаслова)',
'tog-rememberpassword'        => 'Памти лозинку кроз више сеанси',
'tog-editwidth'               => 'Поље за измене има пуну ширину',
'tog-watchcreations'          => 'Додај странице које правим у мој списак надгледања',
'tog-watchdefault'            => 'Додај странице које мењам у мој списак надгледања',
'tog-minordefault'            => 'Означи све измене малим испрва',
'tog-previewontop'            => 'Прикажи претпреглед пре поља за измену',
'tog-previewonfirst'          => 'Прикажи претпреглед при првој измени',
'tog-nocache'                 => 'Онемогући кеширање страница',
'tog-enotifwatchlistpages'    => 'Пошаљи ми е-пошту када се промени страна коју надгледам',
'tog-enotifusertalkpages'     => 'Пошаљи ми е-пошту када се промени моја корисничка страна за разговор',
'tog-enotifminoredits'        => 'Пошаљи ми е-пошту такође за мале измене страна',
'tog-enotifrevealaddr'        => 'Откриј адресу моје е-поште у пошти обавештења',
'tog-shownumberswatching'     => 'Прикажи број корисника који надгледају',
'tog-fancysig'                => 'Чист потпис (без аутоматских веза)',
'tog-externaleditor'          => 'Користи спољашњи уређивач по подразумеваним подешавањима',
'tog-externaldiff'            => 'Користи спољашњи програм за приказ разлика по подразумеваним подешавањима',
'tog-showjumplinks'           => 'Омогући "скочи на" везе',
'tog-uselivepreview'          => 'Користи живи претпреглед (захтева JavaScript) (експериментално)',
'tog-forceeditsummary'        => 'Упозори ме кад не унесем опис измене',
'tog-watchlisthideown'        => 'Сакриј моје измене са списка надгледања',
'tog-watchlisthidebots'       => 'Сакриј измене ботова са списка надгледања',
'tog-nolangconversion'        => 'Искључи конверзију варијанти',

'underline-always'  => 'Увек',
'underline-never'   => 'Никад',
'underline-default' => 'По подешавањима браузера',

'skinpreview' => '(Преглед)',

# Dates
'sunday'        => 'недеља',
'monday'        => 'понедељак',
'tuesday'       => 'уторак',
'wednesday'     => 'среда',
'thursday'      => 'четвртак',
'friday'        => 'петак',
'saturday'      => 'субота',
'sun'           => 'нед',
'mon'           => 'пон',
'tue'           => 'уто',
'wed'           => 'сре',
'thu'           => 'чет',
'fri'           => 'пет',
'sat'           => 'суб',
'january'       => 'јануар',
'february'      => 'фебруар',
'march'         => 'март',
'april'         => 'април',
'may_long'      => 'мај',
'june'          => 'јун',
'july'          => 'јул',
'august'        => 'август',
'september'     => 'септембар',
'october'       => 'октобар',
'november'      => 'новембар',
'december'      => 'децембар',
'january-gen'   => 'јануара',
'february-gen'  => 'фебруара',
'march-gen'     => 'марта',
'april-gen'     => 'априла',
'may-gen'       => 'маја',
'june-gen'      => 'јуна',
'july-gen'      => 'јула',
'august-gen'    => 'августа',
'september-gen' => 'септембра',
'october-gen'   => 'октобра',
'november-gen'  => 'новембра',
'december-gen'  => 'децембра',
'jan'           => 'јан',
'feb'           => 'феб',
'mar'           => 'мар',
'apr'           => 'апр',
'may'           => 'мај',
'jun'           => 'јун',
'jul'           => 'јул',
'aug'           => 'авг',
'sep'           => 'сеп',
'oct'           => 'окт',
'nov'           => 'нов',
'dec'           => 'дец',

# Bits of text used by many pages
'categories'      => 'Категорије страница',
'pagecategories'  => '{{PLURAL:$1|Категорија|Категорије|Категорије}} страница',
'category_header' => 'Чланака у категорији "$1"',
'subcategories'   => 'Поткатегорије',

'mainpagetext'      => "<big>'''МедијаВики је успешно инсталиран.'''</big>",
'mainpagedocfooter' => 'Молимо видите [http://meta.wikimedia.org/wiki/Help:Contents кориснички водич] за информације о употреби вики софтвера.

== За почетак ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Помоћ у вези са подешавањима]
* [http://www.mediawiki.org/wiki/Manual:FAQ Најчешће постављена питања]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Мејлинг листа о издањима МедијаВикија]',

'about'          => 'О...',
'article'        => 'Чланак',
'newwindow'      => '(нови прозор)',
'cancel'         => 'Поништи',
'qbfind'         => 'Пронађи',
'qbbrowse'       => 'Прелиставај',
'qbedit'         => 'Измени',
'qbpageoptions'  => 'Опције странице',
'qbpageinfo'     => 'Информације о страници',
'qbmyoptions'    => 'Моје опције',
'qbspecialpages' => 'Посебне странице',
'moredotdotdot'  => 'Још...',
'mypage'         => 'Моја страница',
'mytalk'         => 'Мој разговор',
'anontalk'       => 'Разговор за ову ИП адресу',
'navigation'     => 'Навигација',

# Metadata in edit box
'metadata_help' => 'Метаподаци (види [[Project:Метаподаци]] за објашњење):',

'errorpagetitle'    => 'Грешка',
'returnto'          => 'Повратак на $1.',
'tagline'           => 'Из пројекта {{SITENAME}}',
'help'              => 'Помоћ',
'search'            => 'претрага',
'searchbutton'      => 'Тражи',
'go'                => 'Иди',
'searcharticle'     => 'Иди',
'history'           => 'Историја странице',
'history_short'     => 'историја',
'updatedmarker'     => 'ажурирано од моје последње посете',
'info_short'        => 'Информације',
'printableversion'  => 'Верзија за штампу',
'permalink'         => 'Пермалинк',
'print'             => 'Штампа',
'edit'              => 'Уреди',
'editthispage'      => 'Уреди ову страницу',
'delete'            => 'обриши',
'deletethispage'    => 'Обриши ову страницу',
'undelete_short'    => 'врати {{PLURAL:$1|једну обрисану измену|$1 обрисане измене|$1 обрисаних измена}}',
'protect'           => 'заштити',
'protectthispage'   => 'Заштити ову страницу',
'unprotect'         => 'Склони заштиту',
'unprotectthispage' => 'Склони заштиту са ове странице',
'newpage'           => 'Нова страница',
'talkpage'          => 'Разговор о овој страници',
'specialpage'       => 'Посебна страница',
'personaltools'     => 'Лични алати',
'postcomment'       => 'Пошаљи коментар',
'articlepage'       => 'Погледај чланак',
'talk'              => 'Разговор',
'views'             => 'Прегледи',
'toolbox'           => 'алати',
'userpage'          => 'Погледај корисничку страну',
'projectpage'       => 'Погледај страну пројекта',
'imagepage'         => 'Погледај страну слике',
'mediawikipage'     => 'Види страницу поруке',
'templatepage'      => 'Види страницу шаблона',
'viewhelppage'      => 'Види страницу помоћи',
'categorypage'      => 'Види страницу категорије',
'viewtalkpage'      => 'Погледај разговор',
'otherlanguages'    => 'Остали језици',
'redirectedfrom'    => '(Преусмерено са $1)',
'redirectpagesub'   => 'Страна преусмерења',
'lastmodifiedat'    => 'Ова страница је последњи пут измењена $2, $1.', # $1 date, $2 time
'viewcount'         => 'Овој страници је приступљено {{PLURAL:$1|једном|$1 пута|$1 пута}}.',
'protectedpage'     => 'Заштићена страница',
'jumpto'            => 'Скочи на:',
'jumptonavigation'  => 'навигација',
'jumptosearch'      => 'претрага',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'О пројекту {{SITENAME}}',
'aboutpage'         => 'Project:О',
'bugreports'        => 'Пријаве грешака',
'bugreportspage'    => 'Project:Пријаве_грешака',
'copyright'         => 'Садржај је објављен под $1.',
'copyrightpagename' => 'Ауторска права пројекта {{SITENAME}}',
'copyrightpage'     => 'Project:Ауторска права',
'currentevents'     => 'Тренутни догађаји',
'currentevents-url' => 'Тренутни догађаји',
'disclaimers'       => 'Одрицање одговорности',
'disclaimerpage'    => 'Project:Одрицање одговорности',
'edithelp'          => 'Помоћ око уређивања',
'edithelppage'      => '{{ns:help}}:Уређивање',
'faq'               => 'НПП',
'faqpage'           => 'Project:НПП',
'helppage'          => '{{ns:help}}:Садржај',
'mainpage'          => 'Главна страна',
'portal'            => 'Радионица',
'portal-url'        => 'Project:Радионица',
'privacy'           => 'Политика приватности',
'privacypage'       => 'Project:Политика приватности',
'sitesupport'       => 'Донације',
'sitesupport-url'   => 'Project:Донације',

'badaccess'        => 'Грешка у дозволама',
'badaccess-group0' => 'Није вам дозвољено да извршите акцију коју сте покренули.',
'badaccess-group1' => 'Акција коју сте покренули је резеревисана за кориснике у групи $1.',
'badaccess-group2' => 'Акција коју сте покренули је резервисана за кориснике из једне од група $1.',
'badaccess-groups' => 'Акција коју сте покренули је резервисана за кориснике из једне од група $1.',

'versionrequired'     => 'Верзија $1 МедијаВикија је потребна',
'versionrequiredtext' => 'Верзија $1 МедијаВикија је потребна да би се користила ова страна. Погледајте [[{{ns:special}}:Version|верзију]]',

'ok'                  => 'да',
'pagetitle'           => '$1 - {{SITENAME}}',
'retrievedfrom'       => 'Добављено из "$1"',
'youhavenewmessages'  => 'Имате $1 ($2).',
'newmessageslink'     => 'нових порука',
'newmessagesdifflink' => 'најсвежије измене',
'editsection'         => 'уреди',
'editold'             => 'уреди',
'editsectionhint'     => 'Уреди део: $1',
'toc'                 => 'Садржај',
'showtoc'             => 'прикажи',
'hidetoc'             => 'сакриј',
'thisisdeleted'       => 'Погледај или врати $1?',
'viewdeleted'         => 'Погледај $1?',
'restorelink'         => '{{PLURAL:$1|једна обрисана измена|$1 обрисане измене|$1 обрисаних измена}}',
'feedlinks'           => 'Фид:',
'feed-invalid'        => 'Лош тип фида пријаве.',
'feed-atom'           => 'Атом',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Чланак',
'nstab-user'      => 'Корисничка страна',
'nstab-media'     => 'Медиј',
'nstab-special'   => 'Посебна',
'nstab-project'   => 'Страна пројекта',
'nstab-image'     => 'Слика',
'nstab-mediawiki' => 'Порука',
'nstab-template'  => 'Шаблон',
'nstab-help'      => 'Помоћ',
'nstab-category'  => 'Категорија',

# Main script and global functions
'nosuchaction'      => 'Нема такве акције',
'nosuchactiontext'  => 'Акцију наведену у УРЛ-у вики софтвер
није препознао.',
'nosuchspecialpage' => 'Нема такве посебне странице',
'nospecialpagetext' => 'Тражили сте непостојећу посебну страницу. Списак свих посебних страница се може наћи на [[{{ns:special}}:Specialpages]].',

# General errors
'error'                => 'Грешка',
'databaseerror'        => 'Грешка у бази',
'dberrortext'          => 'Десила се синтаксна грешка упита базе.
Ово можда указује на грешке у софтверу.
Последњи покушани упит је био:
<blockquote><tt>$1</tt></blockquote>
из функције "<tt>$2</tt>".
MySQL је вратио грешку "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Десила се синтаксна грешка упита базе.
Последњи покушани упит је био:
"$1"
из функције "$2".
MySQL је вратио грешку "$3: $4".',
'noconnect'            => 'Жао нам је! Вики има техничке потешкоће, и не може да се повеже се сервером базе.',
'nodb'                 => 'Не могу да изаберем базу $1',
'cachederror'          => 'Ово је кеширана копија захтеване странице, и можда није ажурирана.',
'laggedslavemode'      => 'Упозорење: могуће је да страна није скоро ажурирана.',
'readonly'             => 'База је закључана',
'enterlockreason'      => 'Унесите разлог за закључавање, укључујући процену
времена откључавања',
'readonlytext'         => 'База података је тренутно закључана за нове
уносе и остале измене, вероватно због рутинског одржавања,
после чега ће бити враћена у уобичајено стање.
Администратор који ју је закључао дао је ово објашњење: $1',
'missingarticle'       => 'База није нашла текст странице
који је требало, назван "$1".

Ово је обично изазвано праћењем застареле "разл" везе или везе ка историји
странице која је обрисана.

Ако ово није случај, можда сте пронашли грешку у софтверу.
Молимо вас пријавите ово једном од [[Project:Администратори|администратора]], заједно са УРЛ-ом.',
'readonly_lag'         => 'База података је аутоматски закључана док слејв сервери не сустигну мастер',
'internalerror'        => 'Интерна грешка',
'filecopyerror'        => 'Не могу да ископирам фајл "$1" на "$2".',
'filerenameerror'      => 'Не могу да преименујем фајл "$1" у "$2".',
'filedeleteerror'      => 'Не могу да обришем фајл "$1".',
'filenotfound'         => 'Не могу да нађем фајл "$1".',
'unexpected'           => 'Неочекивана вредност: "$1"="$2".',
'formerror'            => 'Грешка: не могу да пошаљем упитник',
'badarticleerror'      => 'Ова акција не може бити извршена на овој страници.',
'cannotdelete'         => 'Не могу да обришем наведену страницу или фајл. (Могуће је да је неко други већ обрисао.)',
'badtitle'             => 'Лош наслов',
'badtitletext'         => 'Захтевани наслов странице је био неисправан, празан или
неисправно повезан међујезички или интервики наслов. Можда садржи један или више карактера који не могу да се употребљавају у насловима.',
'perfdisabled'         => 'Жао нам је! Ова могућност је привремено онемогућена јер успорава базу до те мере да више нико не може да користи вики.',
'perfcached'           => 'Следећи подаци су кеширани и не морају бити у потпуности ажурирани.',
'perfcachedts'         => 'Следећи подаци су кеширани и последњи пут су ажурирани: $1',
'wrong_wfQuery_params' => 'Нетачни параметри за wfQuery()<br />
Функција: $1<br />
Претрага: $2',
'viewsource'           => 'погледај код',
'viewsourcefor'        => 'за $1',
'protectedinterface'   => "'''Упозорење:''' Мењате страну која се користи да пружи текст интерфејса за софтвер. Измене на овој страни ће утицати на изглед корисничког интерфејса за остале кориснике.",
'editinginterface'     => "'''Упозорење:''' Уређујете страницу чија је намена уписивање текста за интерфејс софтвера. Измене у овој страници ће променити изглед корисничког интефејса свих корисника.",
'sqlhidden'            => '(SQL претрага сакривена)',

# Login and logout pages
'logouttitle'                => 'Одјави се',
'logouttext'                 => '<strong>Сада сте одјављени.</strong><br />
Можете да наставите да користите пројекат {{SITENAME}} анонимно, или се поново пријавити као други корисник. Обратите пажњу да неке странице могу наставити да се приказују као да сте још увек пријављени, док не очистите кеш свог браузера.',
'welcomecreation'            => '== Добродошли, $1! ==

Ваш налог је направљен.
Не заборавите да прилагодите себи своја {{SITENAME}} подешавања.',
'loginpagetitle'             => 'Пријављивање',
'yourname'                   => 'Корисничко име',
'yourpassword'               => 'Ваша лозинка',
'yourpasswordagain'          => 'Поновите лозинку',
'remembermypassword'         => 'Запамти ме',
'yourdomainname'             => 'Ваш домен',
'externaldberror'            => 'Дошло је или до грешке при спољашњој аутентификацији базе података или вам није дозвољено да ажурирате свој спољашњи налог.',
'loginproblem'               => '<b>Било је проблема са вашим пријављивањем.</b><br />Покушајте поново!',
'alreadyloggedin'            => '<strong>Корисниче $1, већ сте пријављени!</strong><br />',
'login'                      => 'Пријави се',
'loginprompt'                => "Морате да имате омогућене колачиће (''cookies'') да бисте се пријавили на {{SITENAME}}.",
'userlogin'                  => 'Региструј се / Пријави се',
'logout'                     => 'Одјави се',
'userlogout'                 => 'Одјави се',
'notloggedin'                => 'Нисте пријављени',
'nologin'                    => 'Немате налог? $1.',
'nologinlink'                => 'Направите налог',
'createaccount'              => 'Направи налог',
'gotaccount'                 => 'Већ имате налог? $1.',
'gotaccountlink'             => 'Пријави се',
'createaccountmail'          => 'е-поштом',
'badretype'                  => 'Лозинке које сте унели се не поклапају.',
'userexists'                 => 'Корисничко име које сте унели већ је у употреби. Молимо изаберите друго име.',
'youremail'                  => 'Адреса ваше е-поште *',
'username'                   => 'Корисничко име:',
'uid'                        => 'Кориснички ИД:',
'yourrealname'               => 'Ваше право име *',
'yourlanguage'               => 'Језик:',
'yourvariant'                => 'Варијанта:',
'yournick'                   => 'Надимак:',
'badsig'                     => 'Грешка у потпису; проверите HTML тагове.',
'email'                      => 'Е-пошта',
'prefs-help-realname'        => '* Право име (опционо): ако изаберете да дате име, ово ће бити коришћено за приписивање за ваш рад.',
'loginerror'                 => 'Грешка при пријављивању',
'prefs-help-email'           => '* Е-пошта (опционо): Омогућује осталима да вас контактирају преко ваше корисничке стране или стране за разговора без потребе да одајете свој идентитет.',
'nocookiesnew'               => "Кориснички налог је направљен, али нисте пријављени. {{SITENAME}} користи колачиће (''cookies'') да би се корисници пријавили. Ви сте онемогућили колачиће на свом рачунару. Молимо омогућите их, а онда се пријавите са својим новим корисничким именом и лозинком.",
'nocookieslogin'             => "{{SITENAME}} користи колачиће (''cookies'') да би се корисници пријавили. Ви сте онемогућили колачиће на свом рачунару. Молимо омогућите их и покушајте поново са пријавом.",
'noname'                     => 'Нисте изабрали исправно корисничко име.',
'loginsuccesstitle'          => 'Пријављивање успешно',
'loginsuccess'               => "'''Сада сте пријављени на {{SITENAME}} као \"\$1\".'''",
'nosuchuser'                 => 'Не постоји корисник са именом "$1". Проверите да ли сте добро написали или направите нови кориснички налог.',
'nosuchusershort'            => 'Не постоји корисник са именом "$1". Проверите да ли сте добро написали.',
'nouserspecified'            => 'Морате да назначите корисничко име.',
'wrongpassword'              => 'Лозинка коју сте унели је неисправна. Молимо покушајте поново.',
'wrongpasswordempty'         => 'Лозинка коју сте унели је празна. Молимо покушајте поново.',
'mailmypassword'             => 'Пошаљи ми лозинку',
'passwordremindertitle'      => '{{SITENAME}} подсетник за шифру',
'passwordremindertext'       => 'Неко (вероватно ви, са ИП адресе $1)
је захтевао да вам пошаљемо нову лозинку за {{SITENAME}} ($4).
Лозинка за корисника "$2" је сада "$3".
Сада треба да се пријавите и промените своју лозинку.

Ако је неко други поднео овај захтев или уколико сте се сетили своје лозинке и више не желите да је мењате, можете да игноришете ову поруку и наставите да користите своју стару шифру.',
'noemail'                    => 'Не постоји адреса е-поште за корисника "$1".',
'passwordsent'               => 'Нова шифра је послата на адресу е-поште корисника "$1".
Молимо пријавите се пошто је примите.',
'blocked-mailpassword'       => 'Вашој ИП адреси је блокиран приступ уређивању, из ког разлога није могуће користити функцију подсећања лозинке, ради превенције извршења недозвољене акције.',
'eauthentsent'               => 'Е-пошта за потврду је послата на назначену адресу е-поште. Пре него што се било која друга е-пошта пошаље на налог, мораћете да пратите упутства у е-пошти, да бисте потврдили да је налог заиста ваш.',
'throttled-mailpassword'     => 'Подсетник лозинке вам је већ послао једну поруку у задњих $1 сати. Ради превенције извршења недозвољене акције, подсетник шаље само једну поруку у року од $1 сати.',
'mailerror'                  => 'Грешка при слању е-поште: $1',
'acct_creation_throttle_hit' => 'Жао нам је, већ сте направили $1 корисничка имена. Више није дозвољено.',
'emailauthenticated'         => 'Ваша адреса е-поште је потврђена: $1.',
'emailnotauthenticated'      => 'Ваша адреса е-поште још увек није потврђена. Е-пошта неће бити послата ни за једну од следећих могућности.',
'noemailprefs'               => 'Назначите адресу е-поште како би ове могућности радиле.',
'emailconfirmlink'           => 'Потврдите вашу адресу е-поште',
'invalidemailaddress'        => 'Адреса е-поште не може бити примљена јер изгледа није правилног формата. Молимо унесите добро-форматирану адресу или испразните то поље.',
'accountcreated'             => 'Налог је направљен',
'accountcreatedtext'         => 'Кориснички налог за $1 је направљен.',

# Edit page toolbar
'bold_sample'     => 'подебљан текст',
'bold_tip'        => 'подебљан текст',
'italic_sample'   => 'курзиван текст',
'italic_tip'      => 'курзиван текст',
'link_sample'     => 'наслов везе',
'link_tip'        => 'унутрашња веза',
'extlink_sample'  => 'http://www.adresa.com опис адресе',
'extlink_tip'     => 'спољашња веза (не заборавите префикс http://)',
'headline_sample' => 'Наслов',
'headline_tip'    => 'Наслов другог нивоа',
'math_sample'     => 'Овде унесите формулу',
'math_tip'        => 'Математичка формула (LaTeX)',
'nowiki_sample'   => 'Додај неформатирани текст овде',
'nowiki_tip'      => 'Игнориши вики форматирање',
'image_sample'    => 'име_слике.jpg',
'image_tip'       => 'Уклопљена слика',
'media_sample'    => 'име_медија_фајла.mp3',
'media_tip'       => 'Путања ка мултимедијалном фајлу',
'sig_tip'         => 'Ваш потпис са тренутним временом',
'hr_tip'          => 'Хоризонтална линија',

# Edit pages
'summary'                   => 'Опис измене',
'subject'                   => 'Тема/наслов',
'minoredit'                 => 'Ово је мала измена',
'watchthis'                 => 'Надгледај овај чланак',
'savearticle'               => 'Сними страницу',
'preview'                   => 'Претпреглед',
'showpreview'               => 'Прикажи претпреглед',
'showlivepreview'           => 'Живи претпреглед',
'showdiff'                  => 'Прикажи промене',
'anoneditwarning'           => 'Нисте пријављени. Ваша ИП адреса ће бити забележена у историји измена ове стране.',
'missingsummary'            => "'''Подсетник:''' Нисте унели опис измене. Уколико кликнете Сними страницу поново, ваше измене ће бити снимљене без описа.",
'missingcommenttext'        => 'Молимо унестите коментар испод.',
'missingcommentheader'      => "'''Подсетник:''' Нисте навели наслов овог коментара. Уколико кликнете ''Сними поново'', ваш коментар ће бити снимљен без наслова.",
'blockedtitle'              => 'Корисник је блокиран',
'blockedtext'               => "<big>'''Ваше корисничко име или ИП адреса је блокирано.'''</big>

Блокирао вас је корисник \$1. Разлог за блокирање је ''\$2''. 

Можете контактирати корисника \$1 или неког другог [[{{ns:project}}:Администратори|администратора]] како бисте разговарали о блокади. Не можете да користите опцију \"Пошаљи е-пошту овом кориснику\" уколико немате ваљану адресу е-поште наведену у вашим [[Special:Preferences|подешавањима]]. Ваша тренутна ИП адреса је \$3. Молимо укључите ово у сваки ваш захтев.",
'blockedoriginalsource'     => "Извор '''$1''' је приказан испод:",
'blockededitsource'         => "Текст '''ваших измена''' за '''$1''' је приказан испод:",
'whitelistedittitle'        => 'Обавезно је пријављивање за уређивање',
'whitelistedittext'         => 'Морате да се [[{{ns:special}}:Userlogin|пријавите]] да бисте мењали чланке.',
'whitelistreadtitle'        => 'Обавезно је пријављивање за читање',
'whitelistreadtext'         => 'Морате да се [[{{ns:special}}:Userlogin|пријавите]] да бисте читали чланке.',
'whitelistacctitle'         => 'Није вам дозвољено да направите налог',
'whitelistacctext'          => 'Да би вам било дозвољено да правите налоге на овом викију морате да се [[{{ns:special}}:Userlogin|пријавите]] и имате одговарајућа овлашћења.',
'confirmedittitle'          => 'Потребна је потврда адресе е-поштe за уређивање',
'confirmedittext'           => 'Морате потврдити вашу адресу е-поште пре уређивања страна. Молимо поставите и потврдите адресу ваше е-поште преко ваших [[{{ns:special}}:Preferences|корисничких подешавања]].',
'loginreqtitle'             => 'Потребно [[{{ns:special}}:Userlogin|пријављивање]]',
'loginreqlink'              => 'пријава',
'loginreqpagetext'          => 'Морате $1 да бисте видели остале стране.',
'accmailtitle'              => 'Лозинка је послата.',
'accmailtext'               => 'Лозинка за налог "$1" је послата на адресу $2.',
'newarticle'                => '(Нови)',
'newarticletext'            => "Пратили сте везу ка страници која још не постоји.
Да бисте је направили, почните да куцате у пољу испод
(погледајте [[{{ns:help}}:Садржај|помоћ]] за више информација).
Ако сте дошли овде грешком, само кликните дугме '''back''' дугме вашег браузера.",
'anontalkpagetext'          => "---- ''Ово је страница за разговор за анонимног корисника који још није направио налог или га не користи. Због тога морамо да користимо бројчану ИП адресу како бисмо идентификовали њега или њу. Такву адресу може делити више корисника. Ако сте анонимни корисник и мислите да су вам упућене небитне примедбе, молимо вас да [[{{ns:special}}:Userlogin|направите налог или се пријавите]] да бисте избегли будућу забуну са осталим анонимним корисницима.''",
'noarticletext'             => 'Тренутно нема текста на овој страници. Можете [[{{ns:special}}:Search/{{PAGENAME}}|претражити овај назив]] у осталим страницама или [{{fullurl:{{FULLPAGENAME}}|action=edit}} уредити ову страницу].',
'clearyourcache'            => "'''Запамтите:''' Након снимања, можда морате очистити кеш вашег браузера да бисте видели промене. '''Mozilla / Firefox / Safari:''' држите ''Shift'' док кликћете ''Reload'' или притисните  ''Shift+Ctrl+R'' (''Cmd-Shift-R'' на ''Apple Mac'' машини); '''IE:''' држите ''Ctrl'' док кликћете ''Refresh'' или притисните ''Ctrl-F5''; '''Konqueror:''': само кликните ''Reload'' дугме или притисните ''F5''; корисници '''Оpera''' браузера можда морају да у потпуности очисте свој кеш преко ''Tools→Preferences''.",
'usercssjsyoucanpreview'    => "<strong>Савет:</strong> Кориситите 'Прикажи претпреглед' дугме да тестирате свој нови CSS/JS пре снимања.",
'usercsspreview'            => "'''Запамтите ово је само претпреглед вашег CSS и да још увек није снимљен!'''",
'userjspreview'             => "'''Запамтите ово је само претпреглед ваше JavaScript-е и да још увек није снимљен!'''",
'userinvalidcssjstitle'     => "'''Пажња:''' Не постоји кожа \"\$1\". Запамтите да личне .css и .js користе мала почетна слова, нпр. Корисник:Петар/monobook.css а не Корисник:Петар/Monobook.css.",
'updated'                   => '(Ажурирано)',
'note'                      => '<strong>Напомена:</strong>',
'previewnote'               => '<strong>Ово само претпреглед; измене још нису сачуване!</strong>',
'previewconflict'           => 'Овај претпреглед осликава како ће текст у
текстуалном пољу изгледати ако се одлучите да га снимите.',
'session_fail_preview'      => '<strong>Жао нам је! Нисмо могли да обрадимо вашу измену због губитка података сеансе. Молимо покушајте касније. Ако и даље не ради, покушајте да се одјавите и поново пријавите.</strong>',
'session_fail_preview_html' => "<strong>Жао нам је! Нисмо могли да обрадимо вашу измену због губитка података сеансе.</strong>

''Због тога што ова вики има омогућен сиров HTML, претпреглед је сакривен као предострожност против JavaScript напада.''

<strong>Ако сте покушали да направите праву измену, молимо покушајте поново. Ако и даље не ради, покушајте да се одјавите и поново пријавите.</strong>",
'importing'                 => 'Увоз у току: $1',
'editing'                   => 'Уређујете $1',
'editinguser'               => 'Уређујете $1',
'editingsection'            => 'Уређујете $1 (део)',
'editingcomment'            => 'Уређујете $1 (коментар)',
'editconflict'              => 'Сукобљене измене: $1',
'explainconflict'           => 'Неко други је променио ову страницу откад сте ви почели да је мењате.
Горње текстуално поље садржи текст странице какав тренутно постоји.
Ваше измене су приказане у доњем тексту.
Мораћете да унесете своје промене у постојећи текст.
<b>Само</b> текст у горњем текстуалном пољу ће бити снимљен када
притиснете "Сними страницу".<br />',
'yourtext'                  => 'Ваш текст',
'storedversion'             => 'Ускладиштена верзија',
'nonunicodebrowser'         => '<strong>УПОЗОРЕЊЕ: Ваш браузер не подржава уникод. Молимо промените га пре него што почнете са уређивањем чланка.</strong>',
'editingold'                => '<strong>ПАЖЊА: Ви мењате старију ревизију ове странице.
Ако је снимите, све промене учињене од ове ревизије биће изгубљене.</strong>',
'yourdiff'                  => 'Разлике',
'copyrightwarning'          => 'Напомена: За све ваше доприносе се сматра да су издати под $2 (видите $1 за детаље). Ако не желите да се ваши доприноси немилосрдно мењају, не шаљите их овде.<br />
Такође нам обећавате да сте ово сами написали или прекопирали из извора у јавном власништву или сличног слободног извора.
<strong>НЕ ШАЉИТЕ РАДОВЕ ЗАШТИЋЕНЕ АУТОРСКИМ ПРАВИМА БЕЗ ДОЗВОЛЕ!</strong>',
'copyrightwarning2'         => 'Напомена: Све ваше доприносе остали корисници могу да мењају или уклоне. Ако не желите да се ваши доприноси немилосрдно мењају, не шаљите их овде.<br />
Такође нам обећавате да сте ово сами написали или прекопирали из извора у јавном власништву или сличног слободног извора (видите $1 за детаље).
<strong>НЕ ШАЉИТЕ РАДОВЕ ЗАШТИЋЕНЕ АУТОРСКИМ ПРАВИМА БЕЗ ДОЗВОЛЕ!</strong>',
'longpagewarning'           => '<strong>ПАЖЊА: Ова страница има $1 килобајта; неки браузери имају проблема са уређивањем страна које имају близу или више од 32 килобајта. Молимо вас да размотрите разбијање странице на мање делове.</strong>',
'longpageerror'             => '<strong>ГРЕШКА: Текст који снимате је велик $1 килобајта, што је веће од максимално дозвољене величине која износи $2 килобајта. Немогуће је снимити страницу.</strong>',
'readonlywarning'           => '<strong>ПАЖЊА: База је управо закључана због одржавања,
тако да сада нећете моћи да снимите своје измене. Можда би било добро да ископирате текст у неки едитор текста и снимите га за касније.</strong>',
'protectedpagewarning'      => '<strong>ПАЖЊА: Ова страница је закључана тако да само корисници са
администраторским привилегијама могу да је мењају. Уверите се
да пратите [[{{ns:project}}:Правила о заштити страница|правила о заштити страница]].</strong>',
'semiprotectedpagewarning'  => "'''Напомена:''' Ова страница је закључана тако да је само регистровани корисници могу уређивати.",
'templatesused'             => 'Шаблони који се користе на овој страници:',
'edittools'                 => '<!-- Текст одавде ће бити показан испод формулара за уређивање и слање слика. -->',
'nocreatetitle'             => 'Прављење странице ограничено',
'nocreatetext'              => 'На овом сајту је ограничено прављење нових страница. Можете се вратити и уредити већ постојећу страну или [[Посебно:Userlogin|се пријавити или направити налог]].',

# Account creation failure
'cantcreateaccounttitle' => 'Не може да се направи налог',
'cantcreateaccounttext'  => 'Прављење налога са ове ИП адресе (<b>$1</b>) је блокирано. 
Ово је вероватно због учесталог вандализма из ваше школе или Интернет сервис провајдера.',

# History pages
'revhistory'          => 'Историја измена',
'viewpagelogs'        => 'Погледај протоколе за ову страну',
'nohistory'           => 'Не постоји историја измена за ову страницу.',
'revnotfound'         => 'Ревизија није пронађена',
'revnotfoundtext'     => 'Старија ревизија ове странице коју сте затражили није нађена.
Молимо вас да проверите УРЛ који сте употребили да бисте приступили овој страници.',
'loadhist'            => 'Учитавам историју странице',
'currentrev'          => 'Тренутна ревизија',
'revisionasof'        => 'Ревизија од $1',
'revision-info'       => 'Ревизија од $1; $2',
'previousrevision'    => '← Претходна ревизија',
'nextrevision'        => 'Следећа ревизија →',
'currentrevisionlink' => 'Тренутна ревизија',
'cur'                 => 'трен',
'next'                => 'след',
'last'                => 'посл',
'orig'                => 'ориг',
'histlegend'          => 'Одабирање разлика: одаберите кутијице ревизија за упоређивање и притисните ентер или дугме на дну.<br />
Објашњење: (трен) = разлика са тренутном верзијом,
(посл) = разлика са претходном верзијом, М = мала измена',
'deletedrev'          => '[обрисан]',
'histfirst'           => 'Најраније',
'histlast'            => 'Последње',

# Revision feed
'history-feed-title'          => 'Историја ревизија',
'history-feed-description'    => 'Историја ревизија за ову страну на викију',
'history-feed-item-nocomment' => '$1, $2', # user at time
'history-feed-empty'          => 'Тражена страница не постоји.
Могуће да је обрисана из викија или преименована.
Покушајте [[Посебно:Search|да претражите вики]] за релевантне нове стране.',

# Revision deletion
'rev-deleted-comment'         => '(коментар уклоњен)',
'rev-deleted-user'            => '(корисничко име уклоњено)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
Ревизија ове странице је уклоњена из јавних архива.
Могуће да има више детаља у [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} историји брисања].
</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">
Ревизија ове странице је уклоњена из јавних архива.
Као администратор, можете да је погледате;
Могуће да има више детаља у [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} историји брисања].
</div>',
'rev-delundel'                => 'покажи/сакриј',
'revisiondelete'              => 'Обриши/врати ревизију',
'revdelete-nooldid-title'     => 'Нема одабране ревизије',
'revdelete-nooldid-text'      => 'Нисте одабрали жељену ревизију или ревизије како бисте укључили ове функције.',
'revdelete-selected'          => 'Изабрано ревизија од [[:$1]]:',
'revdelete-text'              => 'Обрисане ревизије ће се и даље појављивати на историји странице,
али ће њихов садржај бити скривен јавности.

Остали администратори на овој Википедији ће и даље имати могућност да виде скривени садржај и моћи ће да га врате поново путем ове исте команде, све уколико нису примењене додатне рестрикције оператора сајта.',
'revdelete-legend'            => 'Постави рестрикције ревизија:',
'revdelete-hide-text'         => 'Сакриј текст ревизије',
'revdelete-hide-comment'      => 'Сакриј опис измене',
'revdelete-hide-user'         => 'Сакриј корисничко име/ИП адресу корисника који је уређивао страницу',
'revdelete-hide-restricted'   => 'Примени ове рестрикције за администраторе исто као и за остале',
'revdelete-log'               => 'Коментар протокола:',
'revdelete-submit'            => 'Примени на изабране ревизије',
'revdelete-logentry'          => 'промењен приказ ревизије за [[$1]]',

# Diffs
'difference'                => '(Разлика између ревизија)',
'loadingrev'                => 'учитавам ревизију за разлику',
'lineno'                    => 'Линија $1:',
'editcurrent'               => 'Измени тренутну верзију ове странице',
'selectnewerversionfordiff' => 'Изабери новију верзију за упоређивање',
'selectolderversionfordiff' => 'Изабери старију верзију за упоређивање',
'compareselectedversions'   => 'Упореди означене верзије',

# Search results
'searchresults'         => 'Резултати претраге',
'searchresulttext'      => 'За више информација о претраживању сајта {{SITENAME}}, погледајте [[{{ns:project}}:Претраживање|Претраживање сајта {{SITENAME}}]].',
'searchsubtitle'        => "Тражили сте '''[[:$1]]'''",
'searchsubtitleinvalid' => "Тражили сте '''$1'''",
'badquery'              => 'Лоше обликован упит за претрагу',
'badquerytext'          => 'Нисмо могли да обрадимо ваш упит.
Ово је вероватно због тога што сте покушали да тражите
реч краћу од три слова, што тренутно није подржано.
Такође је могуће да сте погрешно укуцали израз, на
пример "риба има има крљушти".
Молимо вас покушајте неким другим упитом.',
'matchtotals'           => 'Упит "$1" је нађен у $2 наслова чланака
и текст $3 чланака.',
'noexactmatch'          => 'Не постоји страница са насловом "$1". Можете [[$1|направити ову страницу]].',
'titlematches'          => 'Наслов странице одговара',
'notitlematches'        => 'Ниједан наслов странице не одговара',
'textmatches'           => 'Текст странице одговара',
'notextmatches'         => 'Ниједан текст странице не одговара',
'prevn'                 => 'претходних $1',
'nextn'                 => 'следећих $1',
'viewprevnext'          => 'Погледај ($1) ($2) ($3).',
'showingresults'        => 'Приказујем испод <b>$1</b> резултата почев од #<b>$2</b>.',
'showingresultsnum'     => 'Приказујем испод <b>$3</b> резултате почев од #<b>$2</b>.',
'nonefound'             => "'''Напомена''': неуспешне претраге су
често изазване тражењем честих речи као \"је\" или \"од\",
које нису индексиране, или навођењем више од једног израза за тражење (само странице
које садрже све изразе који се траже ће се појавити у резултату).",
'powersearch'           => 'Тражи',
'powersearchtext'       => 'Претрага у именским просторима:<br />$1<br />$2 Излистај преусмерења<br />Тражи $3 $9',
'searchdisabled'        => 'Претрага за сајт {{SITENAME}} је онемогућена. У међувремену, можете користити Гугл претрагу. Имајте на уму да индекси Гугла за сајт {{SITENAME}} могу бити застарели.',
'blanknamespace'        => '(Главно)',

# Preferences page
'preferences'             => 'Подешавања',
'mypreferences'           => 'Моја подешавања',
'prefsnologin'            => 'Нисте пријављени',
'prefsnologintext'        => 'Морате бити [[{{ns:special}}:Userlogin|пријављени]]
да бисте подешавали корисничка подешавања.',
'prefsreset'              => 'Враћена су ускладиштена подешавања.',
'qbsettings'              => 'Брза палета',
'qbsettings-none'         => 'Никаква',
'qbsettings-fixedleft'    => 'Причвршћена лево',
'qbsettings-fixedright'   => 'Причвршћена десно',
'qbsettings-floatingleft' => 'Плутајућа лево',
'changepassword'          => 'Промени лозинку',
'skin'                    => 'Кожа',
'math'                    => 'Математике',
'dateformat'              => 'Формат датума',
'datedefault'             => 'Није битно',
'datetime'                => 'Датум и време',
'math_failure'            => 'Неуспех при парсирању',
'math_unknown_error'      => 'непозната грешка',
'math_unknown_function'   => 'непозната функција',
'math_lexing_error'       => 'речничка грешка',
'math_syntax_error'       => 'синтаксна грешка',
'math_image_error'        => 'PNG конверзија неуспешна; проверите тачну инсталацију latex-а, dvips-а, gs-а и convert-а',
'math_bad_tmpdir'         => 'Не могу да напишем или направим привремени math директоријум',
'math_bad_output'         => 'Не могу да напишем или направим директоријум за math излаз.',
'math_notexvc'            => 'Недостаје извршно texvc; молимо погледајте math/README да бисте подесили.',
'prefs-personal'          => 'Корисничка подешавања',
'prefs-rc'                => 'Скорашње измене',
'prefs-watchlist'         => 'Списак надгледања',
'prefs-watchlist-days'    => 'Број дана који треба да се види на списку надгледања:',
'prefs-watchlist-edits'   => 'Број измена који треба да се види на проширеном списку надгледања:',
'prefs-misc'              => 'Разно',
'saveprefs'               => 'Сачувај',
'resetprefs'              => 'Врати',
'oldpassword'             => 'Стара лозинка:',
'newpassword'             => 'Нова лозинка:',
'retypenew'               => 'Поново откуцајте нову лозинку:',
'textboxsize'             => 'Величине текстуалног поља',
'rows'                    => 'Редова',
'columns'                 => 'Колона',
'searchresultshead'       => 'Претрага',
'resultsperpage'          => 'Погодака по страници:',
'contextlines'            => 'Линија по поготку:',
'contextchars'            => 'Карактера контекста по линији:',
'recentchangescount'      => 'Број наслова у скорашњим изменама:',
'savedprefs'              => 'Ваша подешавања су сачувана.',
'timezonelegend'          => 'Временска зона',
'timezonetext'            => 'Број сати за који се ваше локално време разликује од серверског времена (UTC).',
'localtime'               => 'Локално време',
'timezoneoffset'          => 'Одступање¹',
'servertime'              => 'Време на серверу',
'guesstimezone'           => 'Попуни из браузера',
'allowemail'              => 'Омогући е-пошту од других корисника',
'defaultns'               => 'По стандарду тражи у овим именским просторима:',
'default'                 => 'стандард',
'files'                   => 'Фајлови',

# User rights
'userrights-lookup-user'     => 'Управљај корисничким групама',
'userrights-user-editname'   => 'Унесите корисничко име:',
'editusergroup'              => 'Мењај групе корисника',
'userrights-editusergroup'   => 'Промени корисничке групе',
'saveusergroups'             => 'Сачувај корисничке групе',
'userrights-groupsmember'    => 'Члан:',
'userrights-groupsavailable' => 'Доступне групе:',
'userrights-groupshelp'      => 'Одабране групе од којих желите да се уклони корисник или да се дода.
Неодабране групе неће бити промењене. Можете да деселектујете групу користећи CTRL + леви клик',

# Groups
'group'            => 'Група:',
'group-bot'        => 'ботови',
'group-sysop'      => 'администратори',
'group-bureaucrat' => 'бирократе',
'group-all'        => '(сви)',

'group-bot-member'        => 'бот',
'group-sysop-member'      => 'администратор',
'group-bureaucrat-member' => 'бирократа',

'grouppage-bot'        => 'Project:Ботови',
'grouppage-sysop'      => 'Project:Списак администратора',
'grouppage-bureaucrat' => 'Project:Бирократе',

# User rights log
'rightslog'      => 'историја корисничких права',
'rightslogtext'  => 'Ово је историја измена корисничких права.',
'rightslogentry' => 'је променио права за $1 са $2 на $3',
'rightsnone'     => '(нема)',

# Recent changes
'recentchanges'                     => 'Скорашње измене',
'recentchangestext'                 => 'Овде пратите најскорије измене на викију.',
'rcnote'                            => 'Испод је последњих <strong>$1</strong> промена у последњих <strong>$2</strong> дана.',
'rcnotefrom'                        => 'Испод су промене од <b>$2</b> (до <b>$1</b> приказано).',
'rclistfrom'                        => 'Покажи нове промене почев од $1',
'rcshowhideminor'                   => '$1 мале измене',
'rcshowhidebots'                    => '$1 ботове',
'rcshowhideliu'                     => '$1 пријављене кориснике',
'rcshowhideanons'                   => '$1 анонимне кориснике',
'rcshowhidepatr'                    => '$1 патролиране измене',
'rcshowhidemine'                    => '$1 сопствене измене',
'rclinks'                           => 'Покажи последњих $1 промена у последњих $2 дана<br />$3',
'diff'                              => 'разл',
'hist'                              => 'ист',
'hide'                              => 'сакриј',
'show'                              => 'покажи',
'minoreditletter'                   => 'м',
'newpageletter'                     => 'Н',
'boteditletter'                     => 'б',
'number_of_watching_users_pageview' => '[$1 корисник/а који надгледа/ју]',
'rc_categories'                     => 'Ограничи на категорије (раздвоји са "|")',
'rc_categories_any'                 => 'Било који',

# Recent changes linked
'recentchangeslinked' => 'Сродне промене',

# Upload
'upload'                      => 'Пошаљи фајл',
'uploadbtn'                   => 'Пошаљи фајл',
'reupload'                    => 'Поново пошаљи',
'reuploaddesc'                => 'Врати се на упитник за слање.',
'uploadnologin'               => 'Нисте пријављени',
'uploadnologintext'           => 'Морате бити [[{{ns:special}}:Userlogin|пријављени]]
да бисте слали фајлове.',
'upload_directory_read_only'  => 'На директоријум за слање ($1) сервер не може да пише.',
'uploaderror'                 => 'Грешка при слању',
'uploadtext'                  => "Користите доњи образац да пошаљете фајлове. За гледање или претраживање већ послатих слика, идите на [[{{ns:special}}:Imagelist|списак послатих фајлова]]. Слања и брисања се бележе у [[{{ns:special}}:Log/upload|историји слања]]

Да бисте убацили слику на страну, користите везу у облику
'''<nowiki>[[{{ns:image}}:Фајл.jpg]]</nowiki>''',
'''<nowiki>[[{{ns:image}}:Фајл.png|опис слике]]</nowiki>''' или
'''<nowiki>[[{{ns:media}}:Фајл.ogg]]</nowiki>''' за директно повезивање на фајл.",
'uploadlog'                   => 'историја слања',
'uploadlogpage'               => 'историја слања',
'uploadlogpagetext'           => 'Испод је списак најскоријих слања.',
'filename'                    => 'Име фајла',
'filedesc'                    => 'Опис',
'fileuploadsummary'           => 'Опис:',
'filestatus'                  => 'Статус ауторског права',
'filesource'                  => 'Извор',
'uploadedfiles'               => 'Послати фајлови',
'ignorewarning'               => 'Игнориши упозорења и сними датотеку.',
'ignorewarnings'              => 'Игнориши сва упозорења',
'illegalfilename'             => 'Фајл "$1" садржи карактере који нису дозвољени у називима страница. Молимо Вас промените име фајла и поново га пошаљите.',
'badfilename'                 => 'Име слике је промењено у "$1".',
'largefileserver'             => 'Овај фајл је већи него што је подешено да сервер дозволи.',
'emptyfile'                   => 'Фајл који сте послали делује да је празан. Ово је могуће због грешке у имену фајла. Молимо проверите да ли стварно желите да пошаљете овај фајл.',
'fileexists'                  => 'Фајл са овим именом већ постоји. Молимо проверите $1 ако нисте сигурни да ли желите да га промените.',
'fileexists-forbidden'        => 'Фајл са овим именом већ постоји; молимо вратите се и пошаљите овај фајл под новим именом. [[{{ns:image}}:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Фајл са овим именом већ постоји у заједничкој остави; молимо вратите се и пошаљите овај фајл под новим именом. [[{{ns:image}}:$1|thumb|center|$1]]',
'successfulupload'            => 'Успешно слање',
'uploadwarning'               => 'Упозорење при слању',
'savefile'                    => 'Сними фајл',
'uploadedimage'               => 'послао "[[$1]]"',
'uploaddisabled'              => 'Слање фајлова је искључено.',
'uploaddisabledtext'          => 'Слања фајлова су онемогућена на овом викију.',
'uploadscripted'              => 'Овај фајл садржи ХТМЛ или код скрипте које интернет браузер може погрешно да интерпретира.',
'uploadcorrupt'               => 'Фајл је неисправан или има нетачну екстензију. Молимо проверите фајл и пошаљите га поново.',
'uploadvirus'                 => 'Фајл садржи вирус! Детаљи: $1',
'sourcefilename'              => 'Име фајла извора',
'destfilename'                => 'Циљано име фајла',
'watchthisupload'             => 'Надгледај страницу',
'filewasdeleted'              => 'Фајл са овим именом је раније послат, а касније обрисан. Требало би да проверите $1 пре него што наставите са поновним слањем.',

'upload-proto-error'      => 'Некоректни протокол',
'upload-proto-error-text' => 'Слање екстерних фајлова захтева УРЛове који почињу са <code>http://</code> или <code>ftp://</code>.',
'upload-file-error'       => 'Интерна грешка',
'upload-file-error-text'  => 'Десила се интерна грешка при покушају прављења привременог фајла на серверу. Контактирајте систем администратора.',
'upload-misc-error'       => 'Непозната грешка при слању фајла',
'upload-misc-error-text'  => 'Непозната грешка при слању фајла. Проверите да ли је УРЛ исправан и покушајте поново. Ако проблем остане, контактирајте систем администратора.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'УРЛ није доступан',
'upload-curl-error6-text'  => 'УРЛ који сте унели није доступан. Урадите дупли клик на УРЛ да проверите да ли је адреса доступна.',
'upload-curl-error28'      => 'Тајмаут грешка',
'upload-curl-error28-text' => 'Сајту је требало превише времена да одговори. Проверите да ли сајт ради, или сачекајте мало и покушајте поново.',

'license'            => 'Лиценца',
'nolicense'          => 'Нема',
'upload_source_url'  => ' (валидан, јавно доступан УРЛ)',
'upload_source_file' => ' (фајл на вашем рачунару)',

# Image list
'imagelist'                 => 'Списак слика',
'imagelisttext'             => "Испод је списак од '''$1''' {{plural:$1|фајла|фајла|фајлова}} поређаних $2.",
'imagelistforuser'          => 'Ово је списак слика које је послао корисник $1.',
'getimagelist'              => 'прибављам списак слика',
'ilsubmit'                  => 'Тражи',
'showlast'                  => 'Прикажи последњих $1 слика поређаних $2.',
'byname'                    => 'по имену',
'bydate'                    => 'по датуму',
'bysize'                    => 'по величини',
'imgdelete'                 => 'обр',
'imgdesc'                   => 'опис',
'imgfile'                   => 'фајл',
'imglegend'                 => 'Објашњење: (опис) = прикажи/измени опис слике.',
'imghistory'                => 'историја слике',
'revertimg'                 => 'врт',
'deleteimg'                 => 'обр',
'deleteimgcompletely'       => 'Обриши све ревизије овог фајла',
'imghistlegend'             => 'Објашњење: (трен) = ово је тренутна слика, (обр) = обриши
ову стару верзију, (врт) = врати на ову стару верзију.
<br /><i>Кликните на датум да видите слику послату тог датума</i>.',
'imagelinks'                => 'Употреба слике',
'linkstoimage'              => 'Следеће странице користе овај фајл:',
'nolinkstoimage'            => 'Нема страница које користе овај фајл.',
'sharedupload'              => 'Ова слика је са заједничке оставе и можда је користе остали пројекти.',
'shareduploadwiki'          => 'Молимо погледајте $1 за даље информације.',
'shareduploadwiki-linktext' => 'страна за опис фајла',
'noimage'                   => 'Не постоји фајл са овим именом, можете га $1',
'noimage-linktext'          => 'послати',
'uploadnewversion-linktext' => 'Пошаљите новију верзију овог фајла',
'imagelist_date'            => 'Датум',
'imagelist_name'            => 'Име',
'imagelist_user'            => 'Корисник',
'imagelist_size'            => 'Величина (бајтови)',
'imagelist_description'     => 'Опис слике',
'imagelist_search_for'      => 'Тражи име слике:',

# MIME search
'mimesearch' => 'МИМЕ претрага',
'mimetype'   => 'МИМЕ тип:',
'download'   => 'Преузми',

# Unwatched pages
'unwatchedpages' => 'Ненадгледане странице',

# List redirects
'listredirects' => 'Списак преусмерења',

# Unused templates
'unusedtemplates'     => 'Неискоришћени шаблони',
'unusedtemplatestext' => 'Ова страна наводи све странице у именском простору шаблона које нису укључене ни на једној другој страни. Не заборавите да проверите остале везе ка шаблонима пре него што их обришете.',
'unusedtemplateswlh'  => 'остале везе',

# Random redirect
'randomredirect' => 'Случајно преусмерење',

# Statistics
'statistics'             => 'Статистике',
'sitestats'              => 'Статистике сајта',
'userstats'              => 'Статистике корисника',
'sitestatstext'          => "Постоји укупно '''$1''' страница у бази података. Овај број укључује стране за разговор, странице о сајту, преусмерења, чланке без иједне повезнице и остале странице које се не могу рачунати као чланци. Не рачунајући њих, постоји '''$2''' страница које су вероватно легитимни чланци.

На овај сајт је послато '''$8''' слика.

Странице су укупно погледане '''$3''' пута и '''$4''' измена од постављања викија. Ово значи да је било у просеку '''$5''' измена по страници и '''$6''' погледа по страници.

Дужина реда за послове износи '''$7'''",
'userstatstext'          => "Постоји '''$1''' регистрованих корисника, од којих су '''$2''' (или $4%) администратори (погледајте $3).",
'statistics-mostpopular' => 'Најпосећеније странице',

'disambiguations'     => 'Странице за вишезначне одреднице',
'disambiguationspage' => '{{ns:template}}:Вишезначна одредница',

'doubleredirects'     => 'Двострука преусмерења',
'doubleredirectstext' => 'Сваки ред садржи везе на прво и друго преусмерење, као и на прву линију текста другог преусмерења, што обично даје "прави" циљни чланак, на који би прво преусмерење и требало да показује.',

'brokenredirects'     => 'Покварена преусмерења',
'brokenredirectstext' => 'Следећа преусмерења су повезана на непостојећи чланак.',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|бајт|бајта|бајтова}}',
'ncategories'             => '$1 {{PLURAL:$1|категорија|категорије|категорија}}',
'nlinks'                  => '$1 {{PLURAL:$1|веза|везе|веза}}',
'nmembers'                => '$1 {{PLURAL:$1|чланак|чланка|чланака}}',
'nrevisions'              => '$1 {{PLURAL:$1|ревизија|ревизије|ревизија}}',
'nviews'                  => '$1 пута погледано',
'lonelypages'             => 'Сирочићи',
'lonelypagestext'         => 'Следеће странице нису повезане са других страница на овом викију.',
'uncategorizedpages'      => 'Странице без категорије',
'uncategorizedcategories' => 'Категорије без категорија',
'uncategorizedimages'     => 'Слике без категорија',
'unusedcategories'        => 'Неискоришћене категорије',
'unusedimages'            => 'Неискоришћени фајлови',
'popularpages'            => 'Популарне странице',
'wantedcategories'        => 'Тражене категорије',
'wantedpages'             => 'Тражене странице',
'mostlinked'              => 'Највише повезане стране',
'mostlinkedcategories'    => 'Највише повезане категорије',
'mostcategories'          => 'Чланци са највише категорија',
'mostimages'              => 'Највише повезане слике',
'mostrevisions'           => 'Чланци са највише ревизија',
'allpages'                => 'Све странице',
'prefixindex'             => 'Списак префикса',
'randompage'              => 'Случајна страница',
'shortpages'              => 'Кратке странице',
'longpages'               => 'Дугачке странице',
'deadendpages'            => 'Странице без интерних веза',
'deadendpagestext'        => 'Следеће странице не вежу на друге странице на овом викију.',
'listusers'               => 'Списак корисника',
'specialpages'            => 'Посебне странице',
'spheading'               => 'Посебне странице за све кориснике',
'restrictedpheading'      => 'Заштићене посебне странице',
'rclsub'                  => '(на странице повезане од "$1")',
'newpages'                => 'Нове странице',
'newpages-username'       => 'Корисничко име:',
'ancientpages'            => 'Најстарији чланци',
'intl'                    => 'Међујезичке везе',
'move'                    => 'премести',
'movethispage'            => 'премести ову страницу',
'unusedimagestext'        => '<p>Обратите пажњу да се други веб сајтови
могу повезивати на слику директним УРЛ-ом, и тако могу још увек бити приказани овде упркос
активној употреби.',
'unusedcategoriestext'    => 'Наредне стране категорија постоје иако их ни један други чланак или категорија не користе.',

# Book sources
'booksources' => 'Штампани извори',

'categoriespagetext' => 'Следеће категорије већ постоје на викију',
'data'               => 'Подаци',
'userrights'         => 'Управљање корисничким правима',
'groups'             => 'Корисничке групе',
'alphaindexline'     => '$1 у $2',
'version'            => 'Верзија',

# Special:Log
'specialloguserlabel'  => 'Корисник:',
'speciallogtitlelabel' => 'Наслов:',
'log'                  => 'Протоколи',
'alllogstext'          => 'Комбиновани приказ историја слања, брисања, заштите, блокирања и администраторских права.
Можете сузити преглед одабиром типа историје, корисничког имена или тражене странице.',
'logempty'             => 'Протокол је празан.',

# Special:Allpages
'nextpage'          => 'Следећа страница ($1)',
'allpagesfrom'      => 'Прикажи странице почетно са:',
'allarticles'       => 'Сви чланци',
'allinnamespace'    => 'Све странице ($1 именски простор)',
'allnotinnamespace' => 'Све странице (које нису у $1 именском простору)',
'allpagesprev'      => 'Претходна',
'allpagesnext'      => 'Следећа',
'allpagessubmit'    => 'Иди',
'allpagesprefix'    => 'Прикажи стране са префиксом:',
'allpagesbadtitle'  => 'Дати назив странице није добар или садржи међујезички или интервики префикс. Могуће је да садржи карактере који не могу да се користе у називима.',

# Special:Listusers
'listusersfrom' => 'Прикажи кориснике почевши од:',

# E-mail user
'mailnologin'     => 'Нема адресе за слање',
'mailnologintext' => 'Морате бити [[{{ns:special}}:Userlogin|пријављени]]
и имати исправну адресу е-поште у вашим [[Special:Preferences|подешавањима]]
да бисте слали е-пошту другим корисницима.',
'emailuser'       => 'Пошаљи е-пошту овом кориснику',
'emailpage'       => 'Пошаљи е-писмо кориснику',
'emailpagetext'   => 'Ако је овај корисник унео исправну адресу е-поште у
своја корисничка подешавања, упитник испод ће послати једну поруку.
Адреса е-поште коју сте ви унели у својим корисничким подешавањима ће се појавити
као "From" адреса поруке, тако да ће прималац моћи да одговори.',
'usermailererror' => 'Објекат поште је вратио грешку:',
'defemailsubject' => '{{SITENAME}} е-пошта',
'noemailtitle'    => 'Нема адресе е-поште',
'noemailtext'     => 'Овај корисник није навео исправну адресу е-поште,
или је изабрао да не прима е-пошту од других корисника.',
'emailfrom'       => 'Од',
'emailto'         => 'За',
'emailsubject'    => 'Тема',
'emailmessage'    => 'Порука',
'emailsend'       => 'Пошаљи',
'emailccme'       => 'Пошаљи ми копију моје поруке у моје сандуче е-поште.',
'emailccsubject'  => 'Копија ваше поруке на $1: $2',
'emailsent'       => 'Порука послата',
'emailsenttext'   => 'Ваша порука је послата електронском поштом.',

# Watchlist
'watchlist'            => 'Мој списак надгледања',
'mywatchlist'          => 'Мој списак надгледања',
'watchlistfor'         => "(за '''$1''')",
'nowatchlist'          => 'Немате ништа на свом списку надгледања.',
'watchlistanontext'    => 'Молимо $1 да бисте гледали или мењали ставке на вашем списку надгледања.',
'watchlistcount'       => "'''Имате $1 {{plural:$1|ставку|ставке|ставки}} на вашем списку надгледања, укључујући стране за разговор.'''",
'watchnologin'         => 'Нисте пријављени',
'watchnologintext'     => 'Морате бити [[{{ns:special}}:Userlogin|пријављени]] да бисте мењали списак надгледања.',
'addedwatch'           => 'Додато списку надгледања',
'addedwatchtext'       => "Страница \"[[:\$1]]\" је додата вашем [[{{ns:special}}:Watchlist|списку надгледања]] .
Будуће промене на овој страници и њој придруженој страници за разговор биће наведене овде, и страница ће бити '''подебљана''' у [[{{ns:special}}:Recentchanges|списку скорашњих измена]] да би се лакше уочила.

Ако касније желите да уклоните страницу са вашег списка надгледања, кликните на \"не надгледај\" на бочној палети.",
'removedwatch'         => 'Уклоњено са списка надгледања',
'removedwatchtext'     => 'Страница "[[:$1]]" је уклоњена са вашег списка надгледања.',
'watch'                => 'надгледај',
'watchthispage'        => 'Надгледај ову страницу',
'unwatch'              => 'Прекини надгледање',
'unwatchthispage'      => 'Прекини надгледање',
'notanarticle'         => 'Није чланак',
'watchnochange'        => 'Ништа што надгледате није промењено у приказаном времену.',
'watchlist-details'    => '$1 страница надгледано не рачунајући странице за разговор.',
'wlheader-enotif'      => '* Обавештавање е-поштом је омогућено.',
'wlheader-showupdated' => "* Странице које су измењене од када сте их последњи пут посетили су приказане '''подебљано'''",
'watchmethod-recent'   => 'проверавам има ли надгледаних страница у скорашњим изменама',
'watchmethod-list'     => 'проверавам има ли скорашњих измена у надгледаним страницама',
'watchlistcontains'    => 'Ваш списак надгледања садржи $1 страница.',
'iteminvalidname'      => "Проблем са ставком '$1', неисправно име...",
'wlnote'               => 'Испод је последњих $1 измена у последњих <b>$2</b> сати.',
'wlshowlast'           => 'Прикажи последњих $1 сати $2 дана $3',
'wlsaved'              => 'Ово је сачувана верзија вашег списка надгледања.',

'enotif_mailer'      => '{{SITENAME}} пошта обавештења',
'enotif_reset'       => 'Означи све стране као посећене',
'enotif_newpagetext' => 'Ово је нови чланак.',
'changed'            => 'промењена',
'created'            => 'направљена',
'enotif_subject'     => '{{SITENAME}} страница $PAGETITLE је била $CHANGEDORCREATED од стране $PAGEEDITOR',
'enotif_lastvisited' => 'Погледајте $1 за све промене од ваше последње посете.',
'enotif_body'        => 'Драги $WATCHINGUSERNAME,

{{SITENAME}} страницаа $PAGETITLE је била $CHANGEDORCREATED ($PAGEEDITDATE) од стране $PAGEEDITOR,
погледајте $PAGETITLE_URL за тренутну верзију.

$NEWPAGE

Опис измене уредника: $PAGESUMMARY $PAGEMINOREDIT

Контактирајте уредника:
пошта: $PAGEEDITOR_EMAIL
вики: $PAGEEDITOR_WIKI

Неће бити других обавештења у случају даљих промена уколико не посетите ову страну.
Такође можете да ресетујете заставице за обавештења за све ваше надгледане стране на вашем списку надгледања.

             Ваш пријатељски {{SITENAME}} систем обавештавања

--
Да промените подешавања везана за списак надгледања посетите
{{fullurl:{{ns:special}}:Watchlist/edit}}

Фидбек и даља помоћ:
{{fullurl:{{ns:help}}:Садржај}}',

# Delete/protect/revert
'deletepage'                  => 'Обриши страницу',
'confirm'                     => 'Потврди',
'excontent'                   => "садржај је био: '$1'",
'excontentauthor'             => "садржај је био: '$1' (а једину измену је направио '$2')",
'exbeforeblank'               => "садржај пре брисања је био: '$1'",
'exblank'                     => 'страница је била празна',
'confirmdelete'               => 'Потврди брисање',
'deletesub'                   => '(Бришем "$1")',
'historywarning'              => 'Пажња: страница коју желите да обришете има историју:',
'confirmdeletetext'           => 'На путу сте да трајно обришете страницу
или слику заједно са њеном историјом из базе података.
Молимо вас потврдите да намеравате да урадите ово, да разумете
последице, и да ово радите у складу са
[[{{ns:project}}:Правила и смернице|правилима]].',
'actioncomplete'              => 'Акција завршена',
'deletedtext'                 => 'Чланак "$1" је обрисан.
Погледајте $2 за запис о скорашњим брисањима.',
'deletedarticle'              => 'обрисан "[[$1]]"',
'dellogpage'                  => 'историја брисања',
'dellogpagetext'              => 'Испод је списак најскоријих брисања.',
'deletionlog'                 => 'историја брисања',
'reverted'                    => 'Враћено на ранију ревизију',
'deletecomment'               => 'Разлог за брисање',
'imagereverted'               => 'Враћање на ранију верзију је успешно.',
'rollback'                    => 'Врати измене',
'rollback_short'              => 'Врати',
'rollbacklink'                => 'врати',
'rollbackfailed'              => 'Враћање није успело',
'cantrollback'                => 'Не могу да вратим измену; последњи аутор је уједно и једини.',
'alreadyrolled'               => 'Не могу да вратим последњу измену [[:$1]]
од корисника [[{{ns:user}}:$2|$2]] ([[{{ns:user_talk}}:$2|разговор]]); неко други је већ изменио или вратио чланак.

Последњу измену је направио корисник [[{{ns:user}}:$3|$3]] ([[{{ns:user_talk}}:$3|разговор]]).',
'editcomment'                 => 'Коментар измене је: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'                  => 'Враћене измене од [[{{ns:special}}:Contributions/$2|$2]] ([[{{ns:user_talk}}:$2|разговор]]) на последњу измену од корисника [[{{ns:user}}:$1|$1]]',
'sessionfailure'              => 'Изгледа да постоји проблем са вашом сеансом пријаве;
ова акција је прекинута као предострожност против преотимања сеанси.
Молимо кликните "back" и поново учитајте страну одакле сте дошли, а онда покушајте поново.',
'protectlogpage'              => 'историја закључавања',
'protectlogtext'              => 'Испод је списак закључавања и откључавања страница.',
'protectedarticle'            => 'заштитио $1',
'unprotectedarticle'          => 'скинуо заштиту са $1',
'protectsub'                  => '(стављање заштите "$1")',
'confirmprotect'              => 'Потврдите заштиту',
'protectcomment'              => 'Разлог заштите',
'unprotectsub'                => '(скидање заштите "$1")',
'protect-unchain'             => 'Откључај дозволе премештања',
'protect-text'                => 'Овде можете погледати и мењати ниво заштите за страницу <strong>$1</strong>.',
'protect-default'             => '(стандард)',
'protect-level-autoconfirmed' => 'Блокирај нерегистроване кориснике',
'protect-level-sysop'         => 'Само за администраторе',

# Restrictions (nouns)
'restriction-edit' => 'Уређивање',
'restriction-move' => 'Премештање',

# Undelete
'undelete'                 => 'Погледај обрисане странице',
'undeletepage'             => 'Погледај и врати обрисане странице',
'viewdeletedpage'          => 'Погледај обрисане стране',
'undeletepagetext'         => 'Следеће странице су обрисане али су још увек у архиви и
могу бити враћене. Архива може бити периодично чишћена.',
'undeleteextrahelp'        => "Да вратите целу страну, оставите све кућице неоткаченим и кликните на '''''Врати'''''. Да извршите селективно враћање, откачите кућице које одговарају ревизији која треба да се врати и кликните на '''''Врати'''''. Кликом на '''''Поништи''''' ћете обрисати поље за коментар и све кућице.",
'undeleterevisions'        => '$1 ревизија архивирано',
'undeletehistory'          => 'Ако вратите страницу, све ревизије ће бити враћене њеној историји.
Ако је нова страница истог имена направљена од брисања, враћене
ревизије ће се појавити у ранијој историји, а тренутна ревизија садашње странице
неће бити аутоматски замењена.',
'undeletehistorynoadmin'   => 'Ова страна је обрисана. Разлог за брисање се налази у опису испод, заједно са детаљима о кориснику који је мењао ову страну пре брисања. Стварни текст ових обрисаних ревизија је доступан само администраторима.',
'undeleterevision-missing' => 'Некоректна или непостојећа ревизија. Можда је ваш линк погрешан, или је ревизија рестаурирана, или обрисана из архиве.',
'undeletebtn'              => 'Врати!',
'undeletereset'            => 'Поништи',
'undeletecomment'          => 'Коментар:',
'undeletedarticle'         => 'вратио "[[$1]]"',
'undeletedrevisions'       => '$1 ревизија враћено',
'undeletedrevisions-files' => '$1 {{plural:$1|ревизија|ревизије|ревизија}} и $2 {{plural:$2|фајл|фајла|фајлова}} враћено',
'undeletedfiles'           => '$1 {{plural:$1|фајл враћен|фајла враћена|фајлова враћено}}',
'cannotundelete'           => 'Враћање обрисане верзије није успело; неко други је вратио страницу пре вас.',
'undeletedpage'            => "<big>'''Страна $1 је враћена'''</big>

Погледајте [[{{ns:special}}:Log/delete|историју брисања]] за списак скорашњих брисања и враћања.",

# Namespace form on various pages
'namespace' => 'Именски простор:',
'invert'    => 'Обрни селекцију',

# Contributions
'contributions' => 'Прилози корисника',
'mycontris'     => 'Моји прилози',
'contribsub2'   => 'За $1 ($2)',
'nocontribs'    => 'Нису нађене промене које задовољавају ове услове.',
'ucnote'        => 'Испод је последњих <b>$1</b> промена у последњих <b>$2</b> дана.',
'uclinks'       => 'Гледај последњих $1 промена; гледај последњих $2 дана.',
'uctop'         => ' (врх)',

'sp-contributions-newest'      => 'Најновијих',
'sp-contributions-oldest'      => 'Најстаријих',
'sp-contributions-newer'       => 'Новијих $1',
'sp-contributions-older'       => 'Старијих $1',
'sp-contributions-newbies-sub' => 'За новајлије',

'sp-newimages-showfrom' => 'Прикажи нове слике почевши од $1',

# What links here
'whatlinkshere' => 'Шта је повезано овде',
'notargettitle' => 'Нема циља',
'notargettext'  => 'Нисте навели циљну страницу или корисника
на коме би се извела ова функција.',
'linklistsub'   => '(списак веза)',
'linkshere'     => 'Следеће странице су повезане овде:',
'nolinkshere'   => 'Ни једна страница није повезана овде.',
'isredirect'    => 'преусмеривач',
'istemplate'    => 'укључивање',

# Block/unblock
'blockip'                     => 'Блокирај корисника',
'blockiptext'                 => 'Употребите доњи упитник да бисте уклонили право писања
са одређене ИП адресе или корисничког имена.
Ово би требало да буде урађено само да би се спречио вандализам, и у складу
са [[{{ns:project}}:Политика|политиком]].
Унесите конкретан разлог испод (на пример, наводећи које
странице су вандализоване).',
'ipaddress'                   => 'ИП адреса',
'ipadressorusername'          => 'ИП адреса или корисничко име',
'ipbexpiry'                   => 'Трајање',
'ipbreason'                   => 'Разлог',
'ipbanononly'                 => 'Блокирај само анонимне кориснике',
'ipbcreateaccount'            => 'Спречи прављење налога',
'ipbenableautoblock'          => 'Аутоматски блокирај последњу ИП адресу овог корисника, и сваку следећу адресу са које се покуша уређивање.',
'ipbsubmit'                   => 'Блокирај овог корисника',
'ipbother'                    => 'Остало време',
'ipboptions'                  => '2 сата:2 hours,1 дан:1 day,3 дана:3 days,1 недеља:1 week,2 недеље:2 weeks,1 месец:1 month,3 месеца:3 months,6 месеци:6 months,1 година:1 year,бесконачно:infinite',
'ipbotheroption'              => 'остало',
'badipaddress'                => 'Лоша ИП адреса',
'blockipsuccesssub'           => 'Блокирање је успело',
'blockipsuccesstext'          => '[[{{ns:special}}:Contributions/$1|$1]] је блокиран.
<br />Видите [[{{ns:special}}:Ipblocklist|списак блокирања]] да бисте прегледали блокирања.',
'unblockip'                   => 'Одблокирај корисника',
'unblockiptext'               => 'Употребите доњи упитник да бисте вратили право писања
раније блокираној ИП адреси или корисничком имену.',
'ipusubmit'                   => 'Одблокирај ову адресу',
'unblocked'                   => '[[{{ns:user}}:$1|$1]] је одблокиран',
'ipblocklist'                 => 'Списак блокираних ИП адреса и корисника',
'blocklistline'               => '$1, $2 блокирао корисника [[{{ns:user}}:$3|$3]], (истиче $4)',
'infiniteblock'               => 'бесконачан',
'expiringblock'               => 'истиче $1',
'anononlyblock'               => 'само анонимни',
'noautoblockblock'            => 'Аутоблокирање је онемогућено',
'createaccountblock'          => 'блокирано прављење налога',
'blocklink'                   => 'блокирај',
'unblocklink'                 => 'одблокирај',
'contribslink'                => 'прилози',
'autoblocker'                 => 'Аутоматски сте блокирани јер је вашу ИП адресу скоро користио "[[{{ns:user}}:$1|$1]]". Разлог за блокирање корисника $1 је: "\'\'\'$2\'\'\'".',
'blocklogpage'                => 'историја блокирања',
'blocklogentry'               => 'је блокирао "[[$1]]" са временом истицања блокаде од $2',
'blocklogtext'                => 'Ово је историја блокирања и одблокирања корисника. Аутоматски
блокиране ИП адресе нису наведене. Погледајте [[{{ns:special}}:Ipblocklist|списак блокираних ИП адреса]] за списак тренутних забрана и блокирања.',
'unblocklogentry'             => 'одблокирао "$1"',
'range_block_disabled'        => 'Администраторска могућност да блокира блокове ИП адреса је искључена.',
'ipb_expiry_invalid'          => 'Погрешно време трајања.',
'ipb_already_blocked'         => '"$1" је већ блокиран',
'ip_range_invalid'            => 'Нетачан блок ИП адреса.',
'proxyblocker'                => 'Блокер проксија',
'ipb_cant_unblock'            => 'Грешка: ИД блока $1 није нађен. Могуће је да је већ одблокиран.',
'proxyblockreason'            => 'Ваша ИП адреса је блокирана јер је она отворени прокси. Молимо контактирајте вашег Интернет сервис провајдера или техничку подршку и обавестите их о овом озбиљном сигурносном проблему.',
'proxyblocksuccess'           => 'Урађено.',
'sorbs'                       => 'SORBS DNSBL',
'sorbsreason'                 => 'Ваша ИП адреса је на списку као отворен прокси на [http://www.sorbs.net SORBS] DNSBL.',
'sorbs_create_account_reason' => 'Ваша ИП адреса се налази на списку као отворени прокси на [http://www.sorbs.net SORBS] DNSBL. Не можете да направите налог',

# Developer tools
'lockdb'              => 'Закључај базу',
'unlockdb'            => 'Откључај базу',
'lockdbtext'          => 'Закључавање базе ће свим корисницима укинути могућност измене страница,
промене корисничких подешавања, измене списка надгледања, и свега осталог
што захтева промене у бази.
Молимо потврдите да је ово заиста оно што намеравате да урадите и да ћете
откључати базу када завршите посао око њеног одржавања.',
'unlockdbtext'        => 'Откључавање базе ће свим корисницима вратити могућност измене страница,
промене корисничких подешавања, измене списка надгледања, и свега осталог
што захтева промене у бази.
Молимо потврдите да је ово заиста оно што намеравате да урадите.',
'lockconfirm'         => 'Да, заиста желим да закључам базу.',
'unlockconfirm'       => 'Да, заиста желим да откључам базу.',
'lockbtn'             => 'Закључај базу',
'unlockbtn'           => 'Откључај базу',
'locknoconfirm'       => 'Нисте потврдили своју намеру.',
'lockdbsuccesssub'    => 'База је закључана',
'unlockdbsuccesssub'  => 'База је откључана',
'lockdbsuccesstext'   => 'База података је закључана.
<br />Не заборавите да је [[{{ns:special}}:Unlockdb|откључате]] када завршите са одржавањем.',
'unlockdbsuccesstext' => 'База података је откључана.',
'lockfilenotwritable' => 'По фајлу за закључавање базе података не може да се пише. Да бисте закључали или откључали базу, по овом фајлу мора да буде омогућено писање од стране веб сервера.',
'databasenotlocked'   => 'База података није закључана.',

# Move page
'movepage'                => 'Премештање странице',
'movepagetext'            => "Доњи упитник ће преименовати страницу, премештајући сву
њену историју на ново име.
Стари наслов ће постати преусмерење на нови наслов.
Везе ка старом наслову неће бити промењене; обавезно
потражите [[{{ns:special}}:DoubleRedirects|двострука]] или [[{{ns:special}}:BrokenRedirects|покварена преусмерења]].
На вама је одговорност да везе и даље иду тамо где би и требало да иду.

Обратите пажњу да страница '''неће''' бити померена ако већ постоји
страница са новим насловом, осим ако је она празна или преусмерење и нема
историју промена. Ово значи да не можете преименовати страницу на оно име
са кога сте је преименовали ако погрешите, и не можете преписати
постојећу страницу.

<b>ПАЖЊА!</b>
Ово може бити драстична и неочекивана промена за популарну страницу;
молимо да будете сигурни да разумете последице овога пре него што
наставите.",
'movepagetalktext'        => "Одговарајућа страница за разговор, ако постоји, биће аутоматски премештена истовремено '''осим ако:'''
*Непразна страница за разговор већ постоји под новим именом, или
*Одбележите доњу кућицу.

У тим случајевима, мораћете ручно да преместите или спојите страницу уколико то желите.",
'movearticle'             => 'Премести страницу',
'movenologin'             => 'Нисте пријављени',
'movenologintext'         => 'Морате бити регистровани корисник и [[Special:Userlogin|пријављени]]
да бисте преместили страницу.',
'newtitle'                => 'Нови наслов',
'movepagebtn'             => 'премести страницу',
'pagemovedsub'            => 'Премештање успело',
'articleexists'           => 'Страница под тим именом већ постоји, или је
име које сте изабрали неисправно.
Молимо изаберите друго име.',
'talkexists'              => "'''Сама страница је успешно премештена, али
страница за разговор није могла бити премештена јер таква већ постоји на новом наслову. Молимо вас да их спојите ручно.'''",
'movedto'                 => 'премештена на',
'movetalk'                => 'Премести "страницу за разговор" такође, ако је могуће.',
'talkpagemoved'           => 'Одговарајућа страница за разговор је такође премештена.',
'talkpagenotmoved'        => 'Одговарајућа страница за разговор <strong>није</strong> премештена.',
'1movedto2'               => 'је променио име чланку [[$1]] у [[$2]]',
'1movedto2_redir'         => 'је променио име чланку [[$1]] у [[$2]] путем преусмерења',
'movelogpage'             => 'историја премештања',
'movelogpagetext'         => 'Испод је списак премештања чланака.',
'movereason'              => 'Разлог',
'revertmove'              => 'врати',
'delete_and_move'         => 'Обриши и премести',
'delete_and_move_text'    => '==Потребно брисање==

Циљани чланак "[[$1]]" већ постоји. Да ли желите да га обришете да бисте направили место за премештање?',
'delete_and_move_confirm' => 'Да, обриши страницу',
'delete_and_move_reason'  => 'Обрисано како би се направило место за премештање',
'selfmove'                => 'Изворни и циљани назив су исти; страна не може да се премести преко саме себе.',
'immobile_namespace'      => 'Циљани назив је посебног типа; не могу да преместе стране у тај именски простор.',

# Export
'export'          => 'Извези странице',
'exporttext'      => 'Можете извозити текст и историју промена одређене
странице или групе страница у XML формату. Ово онда може бити увезено у други
вики који користи МедијаВики софтвер преко {{ns:special}}:Import странице.

Да бисте извозили странице, унесите називе у текстуалном пољу испод, са једним насловом по реду, и одаберите да ли желите тренутну верзију са свим старим верзијама или само тренутну верзију са информацијама о последњој измени.

У другом случају, можете такође користити везу, нпр. [[{{ns:special}}:Export/{{int:mainpage}}]] за страницу {{int:mainpage}}.',
'exportcuronly'   => 'Укључи само тренутну ревизију, не целу историју',
'exportnohistory' => "----
'''Напомена:''' извожење пуне историје страна преко овог формулара је онемогућено због серверских разлога.",
'export-submit'   => 'Извоз',

# Namespace 8 related
'allmessages'               => 'Системске поруке',
'allmessagesname'           => 'Име',
'allmessagesdefault'        => 'Стандардни текст',
'allmessagescurrent'        => 'Тренутни текст',
'allmessagestext'           => 'Ово је списак свих порука које су у {{ns:MediaWiki}} именском простору',
'allmessagesnotsupportedUI' => 'Страница {{ns:special}}:Allmessages не подржава вВаш тренутни језик интерфејса <b>$1</b> на овој вики.',
'allmessagesnotsupportedDB' => "Страница {{ns:special}}:Allmessages не може да се користи зато што је '''\$wgUseDatabaseMessages''' искључен.",
'allmessagesfilter'         => 'Филтер за имена порука:',
'allmessagesmodified'       => 'Прикажи само измењене',

# Thumbnails
'thumbnail-more'  => 'увећај',
'missingimage'    => '<b>Овде недостаје слика</b><br /><i>$1</i>',
'filemissing'     => 'Недостаје фајл',
'thumbnail_error' => 'Грешка при прављењу умањене слике: $1',

# Special:Import
'import'                     => 'Увоз страница',
'importinterwiki'            => 'Трансвики увожење',
'import-interwiki-text'      => 'Одаберите вики и назив стране за увоз.
Датуми ревизије и имена уредника ће бити сачувани.
Сви трансвики увози су забележени у [[Посебно:Log/import|историји увоза]].',
'import-interwiki-history'   => 'Копирај све ревизије ове стране',
'import-interwiki-submit'    => 'Увези',
'import-interwiki-namespace' => 'Пребаци странице у именски простор:',
'importtext'                 => 'Молимо извезите фајл из изворног викија користећи {{ns:special}}:Export, сачувајте га код себе и пошаљите овде.',
'importstart'                => 'Увожење страна у току...',
'import-revision-count'      => '$1 {{plural:$1|ревизија|ревизије|ревизија}}',
'importnopages'              => 'Нема страна за увоз.',
'importfailed'               => 'Увоз није успео: $1',
'importunknownsource'        => 'Непознати тип извора уноса',
'importcantopen'             => 'Неуспешно отварање фајла за увоз',
'importbadinterwiki'         => 'Лоша интервики веза',
'importnotext'               => 'Страница је празна или без текста.',
'importsuccess'              => 'Успешан увоз!',
'importhistoryconflict'      => 'Постоји конфликтна историја ревизија (можда је ова страница већ увезена раније)',
'importnosources'            => 'Није дефинисан ниједан извор трансвики увожења и директна слања историја су онемогућена.',
'importnofile'               => 'Није послат ниједан увозни фајл.',
'importuploaderror'          => 'Слање увозног фајла није било успешно; могуће је да је фајл већи од дозвољене величине за слање.',

# Import log
'importlogpage'                    => 'историја увоза',
'importlogpagetext'                => 'Административни увози страница са историјама измена са других викија.',
'import-logentry-upload'           => 'увезао [[$1]] путем слања фајла',
'import-logentry-upload-detail'    => '$1 ревизија/е',
'import-logentry-interwiki'        => 'преместио са другог викија: $1',
'import-logentry-interwiki-detail' => '$1 ревизија/е од $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Моја корисничка страница',
'tooltip-pt-anonuserpage'         => 'Корисничка страница ИП адресе са које уређујете',
'tooltip-pt-mytalk'               => 'Моја страница за разговор',
'tooltip-pt-anontalk'             => 'Разговор о прилозима са ове ИП адресе',
'tooltip-pt-preferences'          => 'Моја корисничка подешавања',
'tooltip-pt-watchlist'            => 'Списак чланака које надгледате',
'tooltip-pt-mycontris'            => 'Списак мојих прилога',
'tooltip-pt-login'                => 'Препоручује се да се пријавите, али није обавезно',
'tooltip-pt-anonlogin'            => 'Препоручује се да се пријавите, али није обавезно',
'tooltip-pt-logout'               => 'Одјави се',
'tooltip-ca-talk'                 => 'Разговор о чланку',
'tooltip-ca-edit'                 => 'Можете уредити ову страницу. Молимо користите претпреглед пре сачувавања.',
'tooltip-ca-addsection'           => 'Додајте коментар на ову дискусију',
'tooltip-ca-viewsource'           => 'Ова страница је закључана. Можете видети њен извор',
'tooltip-ca-history'              => 'Претходне верзије ове странице',
'tooltip-ca-protect'              => 'Заштити ову страницу',
'tooltip-ca-delete'               => 'Обриши ову страницу',
'tooltip-ca-undelete'             => 'Враћати измене које су начињене пре брисања странице',
'tooltip-ca-move'                 => 'Премести ову страницу',
'tooltip-ca-watch'                => 'Додајте ову страницу на Ваш списак надгледања',
'tooltip-ca-unwatch'              => 'Уклоните ову страницу са Вашег списка надгледања',
'tooltip-search'                  => 'Претражите овај вики',
'tooltip-p-logo'                  => 'Главна страна',
'tooltip-n-mainpage'              => 'Посетите главну страну',
'tooltip-n-portal'                => 'О пројекту, шта можете да радите и где да пронађете ствари',
'tooltip-n-currentevents'         => 'Сазнајте више о актуелностима',
'tooltip-n-recentchanges'         => 'Списак скорашњих измена на викију',
'tooltip-n-randompage'            => 'Учитавај случајну страницу',
'tooltip-n-help'                  => 'Место где можете да научите нешто',
'tooltip-n-sitesupport'           => 'Подржите нас',
'tooltip-t-whatlinkshere'         => 'Списак свих страница које везују на ову',
'tooltip-t-recentchangeslinked'   => 'Скорашње измене на чланцима повезаним са ове странице',
'tooltip-feed-rss'                => 'RSS фид за ову страницу',
'tooltip-feed-atom'               => 'Atom фид за ову страницу',
'tooltip-t-contributions'         => 'Погледај списак прилога овог корисника',
'tooltip-t-emailuser'             => 'Пошаљи електронску пошту овом кориснику',
'tooltip-t-upload'                => 'Пошаљи слике и медија фајлове',
'tooltip-t-specialpages'          => 'Списак свих посебних страница',
'tooltip-ca-nstab-main'           => 'Погледајте чланак',
'tooltip-ca-nstab-user'           => 'Погледајте корисничку страницу',
'tooltip-ca-nstab-media'          => 'Погледајте медија страницу',
'tooltip-ca-nstab-special'        => 'Ово је посебна страница, не можете је мењати',
'tooltip-ca-nstab-image'          => 'Погледајте страницу слике',
'tooltip-ca-nstab-mediawiki'      => 'Погледајте системску поруку',
'tooltip-ca-nstab-template'       => 'Погледајте шаблон',
'tooltip-ca-nstab-help'           => 'Погледајте страницу за помоћ',
'tooltip-ca-nstab-category'       => 'Погледајте страницу категорије',
'tooltip-minoredit'               => 'Назначите да се ради о малој измени',
'tooltip-save'                    => 'Снимите Ваше измене',
'tooltip-preview'                 => 'Претпреглед Ваших измена, молимо користите ово пре снимања!',
'tooltip-diff'                    => 'Прикажи које промене сте направили на тексту.',
'tooltip-compareselectedversions' => 'Погледаj разлике између две одабране верзије ове странице.',
'tooltip-watch'                   => 'Додајте ову страницу на Ваш списак надгледања',
'tooltip-recreate'                => 'Поново направите ову страну упркос томе што је обрисана',

# Stylesheets
'common.css'   => '/** CSS стављен овде ће се односити на све коже */',
'monobook.css' => '/* CSS стављен овде ће се односити на кориснике Монобук коже */',

# Scripts
'monobook.js' => '/* Deprecated; use [[MediaWiki:common.js]] */',

# Metadata
'nodublincore'      => 'Dublin Core RDF метаподаци онемогућени за овај сервер.',
'nocreativecommons' => 'Creative Commons RDF метаподаци онемогућени за овај сервер.',
'notacceptable'     => 'Вики сервер не може да пружи податке у оном формату који ваш клијент може да прочита.',

# Attribution
'anonymous'        => 'Анонимни корисник сајта {{SITENAME}}',
'siteuser'         => '{{SITENAME}} корисник $1',
'lastmodifiedatby' => 'Ову страницу је последњи пут променио $3 у $2, $1.', # $1 date, $2 time, $3 user
'and'              => 'и',
'othercontribs'    => 'Базирано на раду корисника $1.',
'others'           => 'остали',
'siteusers'        => '{{SITENAME}} корисник (корисници) $1',
'creditspage'      => 'Заслуге за страницу',
'nocredits'        => 'Нису доступне информације о заслугама за ову страницу.',

# Spam protection
'spamprotectiontitle'    => 'Филтер за заштиту од нежељених порука',
'spamprotectiontext'     => 'Страна коју желите да сачувате је блокирана од стране филтера за нежељене поруке. Ово је вероватно изазвано везом ка спољашњем сајту.',
'spamprotectionmatch'    => 'Следећи текст је изазвао наш филтер за нежељене поруке: $1',
'subcategorycount'       => 'У овој категорији се налази {{plural:$1|једна поткатегорија|$1 поткатегорије|$1 поткатегорија}}.',
'categoryarticlecount'   => 'У овој категорији се налази {{plural:$1|Један чланак|$1 чланка|$1 чланака}}.',
'listingcontinuesabbrev' => 'наст.',
'spambot_username'       => 'Чишћење нежељених порука у МедијаВикију',
'spam_reverting'         => 'Враћање на стару ревизију која не садржи везе ка $1',
'spam_blanking'          => 'Све ревизије су садржале везе ка $1, пражњење',

# Info page
'infosubtitle'   => 'Информације за страницу',
'numedits'       => 'Број промена (чланак): $1',
'numtalkedits'   => 'Број промена (страница за разговор): $1',
'numwatchers'    => 'Број корисника који надгледају: $1',
'numauthors'     => 'Број различитих аутора (чланак): $1',
'numtalkauthors' => 'Број различитих аутора (страница за разговор): $1',

# Math options
'mw_math_png'    => 'Увек прикажи PNG',
'mw_math_simple' => 'HTML ако је врло једноставно, иначе PNG',
'mw_math_html'   => 'HTML ако је могуће, иначе PNG',
'mw_math_source' => 'Остави као ТеХ (за текстуалне браузере)',
'mw_math_modern' => 'Препоручено за савремене браузере',
'mw_math_mathml' => 'MathML ако је могуће (експериментално)',

# Patrolling
'markaspatrolleddiff'        => 'Означи као патролиран',
'markaspatrolledtext'        => 'Означи овај чланак као патролиран',
'markedaspatrolled'          => 'Означен као патролиран',
'markedaspatrolledtext'      => 'Изабрана ревизија је означена као патролирана.',
'rcpatroldisabled'           => 'Патрола скорашњих измена онемогућена',
'rcpatroldisabledtext'       => 'Патрола скорашњих измена је тренутно онемогућена.',
'markedaspatrollederror'     => 'Немогуће означити као патролирано',
'markedaspatrollederrortext' => 'Морате изабрати ревизију да бисте означили као патролирано.',

# Image deletion
'deletedrevision' => 'Обрисана стара ревизија $1.',

# Browsing diffs
'previousdiff' => '← Претходна измена',
'nextdiff'     => 'Следећа измена →',

# Media information
'mediawarning' => "'''Упозорење''': Овај фајл садржи лош код, његовим извршавањем можете да угрозите ваш систем.<hr />",
'imagemaxsize' => 'Ограничи слике на странама за разговор о сликама на:',
'thumbsize'    => 'Величина умањеног приказа :',

'newimages'    => 'Галерија нових слика',
'showhidebots' => '($1 ботове)',
'noimages'     => 'Нема ништа да се види',

/*
Short names for language variants used for language conversion links.
To disable showing a particular link, set it to 'disable', e.g.
'variantname-zh-sg' => 'disable',
Variants for Chinese language
*/

# Variants for Serbian language
'variantname-sr-ec' => 'ћирилица',
'variantname-sr-el' => 'latinica',
'variantname-sr-jc' => 'јекав',
'variantname-sr-jl' => 'jekav',
'variantname-sr'    => 'disable',

'passwordtooshort' => 'Ваша шифра је превише кратка. Мора да има бар $1 карактера.',

# Metadata
'metadata'          => 'Метаподаци',
'metadata-help'     => 'Овај фајл садржи додатне информације, које су вероватно додали дигитални фотоапарат или скенер који су коришћени да би се направила или дигитализовала слика. Ако је првобитно стање фајла промењено, могуће је да неки детаљи не описују у потпуности измењену слику.',
'metadata-expand'   => 'Покажи детаље',
'metadata-collapse' => 'Сакриј детаље',
'metadata-fields'   => 'Поља EXIF метаподатака наведена у овој поруци ће бити убачена на страну о слици када се рашири табела за метаподатке. Остала ће бити сакривена по подразумеваном.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Ширина',
'exif-imagelength'                 => 'Висина',
'exif-bitspersample'               => 'Битова по компоненти',
'exif-compression'                 => 'Шема компресије',
'exif-photometricinterpretation'   => 'Композиција пиксела',
'exif-orientation'                 => 'Оријентација',
'exif-samplesperpixel'             => 'Број компоненти',
'exif-planarconfiguration'         => 'Принцип распореда података',
'exif-ycbcrsubsampling'            => 'Однос компоненте Y према C',
'exif-ycbcrpositioning'            => 'Размештај компонената Y и C',
'exif-xresolution'                 => 'Хоризонатална резолуција',
'exif-yresolution'                 => 'Вертикална резолуција',
'exif-resolutionunit'              => 'Јединица резолуције',
'exif-stripoffsets'                => 'Положај блока података',
'exif-rowsperstrip'                => 'Број редова у блоку',
'exif-stripbytecounts'             => 'Величина компресованог блока',
'exif-jpeginterchangeformat'       => 'Удаљеност ЈПЕГ прегледа од почетка фајла',
'exif-jpeginterchangeformatlength' => 'Количина бајтова ЈПЕГ прегледа',
'exif-transferfunction'            => 'Функција преобликовања колор простора',
'exif-whitepoint'                  => 'Хромацитет беле тачке',
'exif-primarychromaticities'       => 'Хромацитет примарних боја',
'exif-ycbcrcoefficients'           => 'Матрични коефицијенти трансформације колор простора',
'exif-referenceblackwhite'         => 'Место беле и црне тачке',
'exif-datetime'                    => 'Датум последње промене фајла',
'exif-imagedescription'            => 'Име слике',
'exif-make'                        => 'Произвођач камере',
'exif-model'                       => 'Модел камере',
'exif-software'                    => 'Коришћен софтвер',
'exif-artist'                      => 'Аутор',
'exif-copyright'                   => 'Носилац права',
'exif-exifversion'                 => 'Exif верзија',
'exif-flashpixversion'             => 'Подржана верзија Флешпикса',
'exif-colorspace'                  => 'Простор боје',
'exif-componentsconfiguration'     => 'Значење сваке од компоненти',
'exif-compressedbitsperpixel'      => 'Мод компресије слике',
'exif-pixelydimension'             => 'Пуна висина слике',
'exif-pixelxdimension'             => 'Пуна ширина слике',
'exif-makernote'                   => 'Напомене произвођача',
'exif-usercomment'                 => 'Кориснички коментар',
'exif-relatedsoundfile'            => 'Повезани звучни запис',
'exif-datetimeoriginal'            => 'Датум и време сликања',
'exif-datetimedigitized'           => 'Датум и време дигитализације',
'exif-subsectime'                  => 'Део секунде у којем је сликано',
'exif-subsectimeoriginal'          => 'Део секунде у којем је фотографисано',
'exif-subsectimedigitized'         => 'Део секунде у којем је дигитализовано',
'exif-exposuretime'                => 'Експозиција',
'exif-exposuretime-format'         => '$1 сек ($2)',
'exif-fnumber'                     => 'F број отвора бленде',
'exif-exposureprogram'             => 'Програм експозиције',
'exif-spectralsensitivity'         => 'Спектрална осетљивост',
'exif-isospeedratings'             => 'ИСО вредност',
'exif-oecf'                        => 'Оптоелектронски фактор конверзије',
'exif-shutterspeedvalue'           => 'Брзина затварача',
'exif-aperturevalue'               => 'Отвор бленде',
'exif-brightnessvalue'             => 'Светлост',
'exif-exposurebiasvalue'           => 'Компензација експозиције',
'exif-maxaperturevalue'            => 'Минимални број отвора бленде',
'exif-subjectdistance'             => 'Удаљеност до објекта',
'exif-meteringmode'                => 'Режим мерача времена',
'exif-lightsource'                 => 'Извор светлости',
'exif-flash'                       => 'Блиц',
'exif-focallength'                 => 'Фокусна даљина сочива',
'exif-subjectarea'                 => 'Положај и површина објекта снимка',
'exif-flashenergy'                 => 'Енергија блица',
'exif-spatialfrequencyresponse'    => 'Просторна фреквенцијска карактеристика',
'exif-focalplanexresolution'       => 'Водоравна резолуција фокусне равни',
'exif-focalplaneyresolution'       => 'Хоризонатлна резолуција фокусне равни',
'exif-focalplaneresolutionunit'    => 'Јединица резолуције фокусне равни',
'exif-subjectlocation'             => 'Положај субјекта',
'exif-exposureindex'               => 'Индекс експозиције',
'exif-sensingmethod'               => 'Тип сензора',
'exif-filesource'                  => 'Изворни фајл',
'exif-scenetype'                   => 'Тип сцене',
'exif-cfapattern'                  => 'CFA шаблон',
'exif-customrendered'              => 'Додатна обрада слике',
'exif-exposuremode'                => 'Режим избора експозиције',
'exif-whitebalance'                => 'Баланс беле боје',
'exif-digitalzoomratio'            => 'Однос дигиталног зума',
'exif-focallengthin35mmfilm'       => 'Еквивалент фокусне даљине за 35 mm филм',
'exif-scenecapturetype'            => 'Тип сцене на снимку',
'exif-gaincontrol'                 => 'Контрола осветљености',
'exif-contrast'                    => 'Контраст',
'exif-saturation'                  => 'Сатурација',
'exif-sharpness'                   => 'Оштрина',
'exif-devicesettingdescription'    => 'Опис подешавања уређаја',
'exif-subjectdistancerange'        => 'Распон удаљености субјеката',
'exif-imageuniqueid'               => 'Јединствени идентификатор слике',
'exif-gpsversionid'                => 'Верзија блока ГПС-информације',
'exif-gpslatituderef'              => 'Северна или јужна ширина',
'exif-gpslatitude'                 => 'Ширина',
'exif-gpslongituderef'             => 'Источна или западна дужина',
'exif-gpslongitude'                => 'Дужина',
'exif-gpsaltituderef'              => 'Висина испод или изнад мора',
'exif-gpsaltitude'                 => 'Висина',
'exif-gpstimestamp'                => 'Време по ГПС-у (атомски сат)',
'exif-gpssatellites'               => 'Употребљени сателити',
'exif-gpsstatus'                   => 'Статус пријемника',
'exif-gpsmeasuremode'              => 'Режим мерења',
'exif-gpsdop'                      => 'Прецизност мерења',
'exif-gpsspeedref'                 => 'Јединица брзине',
'exif-gpsspeed'                    => 'Брзина ГПС пријемника',
'exif-gpstrackref'                 => 'Тип азимута пријемника (прави или магнетни)',
'exif-gpstrack'                    => 'Азимут пријемника',
'exif-gpsimgdirectionref'          => 'Тип азимута слике (прави или магнетни)',
'exif-gpsimgdirection'             => 'Азимут слике',
'exif-gpsmapdatum'                 => 'Коришћени геодетски координатни систем',
'exif-gpsdestlatituderef'          => 'Индекс географске ширине објекта',
'exif-gpsdestlatitude'             => 'Географска ширина објекта',
'exif-gpsdestlongituderef'         => 'Индекс географске дужине објекта',
'exif-gpsdestlongitude'            => 'Географска дужина објекта',
'exif-gpsdestbearingref'           => 'Индекс азимута објекта',
'exif-gpsdestbearing'              => 'Азимут објекта',
'exif-gpsdestdistanceref'          => 'Мерне јединице удаљености објекта',
'exif-gpsdestdistance'             => 'Удаљеност објекта',
'exif-gpsprocessingmethod'         => 'Име методе обраде ГПС података',
'exif-gpsareainformation'          => 'Име ГПС подручја',
'exif-gpsdatestamp'                => 'ГПС датум',
'exif-gpsdifferential'             => 'ГПС диференцијална корекција',

# EXIF attributes
'exif-compression-1' => 'Некомпресован',
'exif-compression-6' => 'ЈПЕГ',

'exif-orientation-1' => 'Нормално', # 0th row: top; 0th column: left
'exif-orientation-2' => 'Обрнуто по хоризонтали', # 0th row: top; 0th column: right
'exif-orientation-3' => 'Заокренуто 180°', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Обрнуто по вертикали', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Заокренуто 90° супротно од смера казаљке на сату и обрнуто по вертикали', # 0th row: left; 0th column: top
'exif-orientation-6' => 'Заокренуто 90° у смеру казаљке на сату', # 0th row: right; 0th column: top
'exif-orientation-7' => 'Заокренуто 90° у смеру казаљке на сату и обрнуто по вертикали', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Заокренуто 90° супротно од смера казаљке на сату', # 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'делимични формат',
'exif-planarconfiguration-2' => 'планарни формат',

'exif-componentsconfiguration-0' => 'не постоји',

'exif-exposureprogram-0' => 'Непознато',
'exif-exposureprogram-1' => 'Ручно',
'exif-exposureprogram-2' => 'Нормални програм',
'exif-exposureprogram-3' => 'Приоритет отвора бленде',
'exif-exposureprogram-4' => 'Приоритет затварача',
'exif-exposureprogram-5' => 'Уметнички програм (на бази нужне дубине поља)',
'exif-exposureprogram-6' => 'Спортски програм (на бази што бржег затварача)',
'exif-exposureprogram-7' => 'Портретни режим (за крупне кадрове са неоштром позадином)',
'exif-exposureprogram-8' => 'Режим пејзажа (за слике пејзажа са оштром позадином)',

'exif-subjectdistance-value' => '$1 метара',

'exif-meteringmode-0'   => 'Непознато',
'exif-meteringmode-1'   => 'Просек',
'exif-meteringmode-2'   => 'Просек са тежиштем на средини',
'exif-meteringmode-3'   => 'Тачка',
'exif-meteringmode-4'   => 'Више тачака',
'exif-meteringmode-5'   => 'Матрични',
'exif-meteringmode-6'   => 'Делимични',
'exif-meteringmode-255' => 'Друго',

'exif-lightsource-0'   => 'Непознато',
'exif-lightsource-1'   => 'Дневна светлост',
'exif-lightsource-2'   => 'Флуоресцентно',
'exif-lightsource-3'   => 'Волфрам (светло)',
'exif-lightsource-4'   => 'Блиц',
'exif-lightsource-9'   => 'Лепо време',
'exif-lightsource-10'  => 'Облачно време',
'exif-lightsource-11'  => 'Сенка',
'exif-lightsource-12'  => 'Флуоресцентна светлост (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Флуоресцентна светлост (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Флуоресцентна светлост (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Бела флуоресценција (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Стандардно светло А',
'exif-lightsource-18'  => 'Стандардно светло Б',
'exif-lightsource-19'  => 'Стандардно светло Ц',
'exif-lightsource-24'  => 'ИСО студијски волфрам',
'exif-lightsource-255' => 'Други извор светла',

'exif-focalplaneresolutionunit-2' => 'инчи',

'exif-sensingmethod-1' => 'Недефинисано',
'exif-sensingmethod-2' => 'Једнокристални матрични сензор',
'exif-sensingmethod-3' => 'Двокристални матрични сензор',
'exif-sensingmethod-4' => 'Трокристални матрични сензор',
'exif-sensingmethod-5' => 'Секвенцијални матрични сензор',
'exif-sensingmethod-7' => 'Тробојни линеарни сензор',
'exif-sensingmethod-8' => 'Секвенцијални линеарни сензор',

'exif-filesource-3' => 'Дигитални фотоапарат',

'exif-scenetype-1' => 'Директно фотографисана слика',

'exif-customrendered-0' => 'Нормални процес',
'exif-customrendered-1' => 'Нестадардни процес',

'exif-exposuremode-0' => 'Аутоматски',
'exif-exposuremode-1' => 'Ручно',
'exif-exposuremode-2' => 'Аутоматски са задатим распоном',

'exif-whitebalance-0' => 'Аутоматски',
'exif-whitebalance-1' => 'Ручно',

'exif-scenecapturetype-0' => 'Стандардно',
'exif-scenecapturetype-1' => 'Пејзаж',
'exif-scenecapturetype-2' => 'Портрет',
'exif-scenecapturetype-3' => 'Ноћно',

'exif-gaincontrol-0' => 'Нема',
'exif-gaincontrol-1' => 'Мало повећање',
'exif-gaincontrol-2' => 'Велико повећање',
'exif-gaincontrol-3' => 'Мало смањење',
'exif-gaincontrol-4' => 'Велико смањење',

'exif-contrast-0' => 'Нормално',
'exif-contrast-1' => 'Меко',
'exif-contrast-2' => 'Тврдо',

'exif-saturation-0' => 'Нормално',
'exif-saturation-1' => 'Ниска сатурација',
'exif-saturation-2' => 'Висока сатурација',

'exif-sharpness-0' => 'Нормално',
'exif-sharpness-1' => 'Меко',
'exif-sharpness-2' => 'Тврдо',

'exif-subjectdistancerange-0' => 'Непознато',
'exif-subjectdistancerange-1' => 'Крупни кадар',
'exif-subjectdistancerange-2' => 'Блиски кадар',
'exif-subjectdistancerange-3' => 'Далеки кадар',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Север',
'exif-gpslatitude-s' => 'Југ',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Исток',
'exif-gpslongitude-w' => 'Запад',

'exif-gpsstatus-a' => 'Мерење у току',
'exif-gpsstatus-v' => 'Спреман за пренос',

'exif-gpsmeasuremode-2' => 'Дводимензионално мерење',
'exif-gpsmeasuremode-3' => 'Тродимензионално мерење',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Километри на час',
'exif-gpsspeed-m' => 'Миље на час',
'exif-gpsspeed-n' => 'Чворови',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Прави правац',
'exif-gpsdirection-m' => 'Магнетни правац',

# External editor support
'edit-externally'      => 'Измените овај фајл користећи спољашњу апликацију',
'edit-externally-help' => 'Погледајте [http://meta.wikimedia.org/wiki/Help:External_editors упутство за подешавање] за више информација.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'све',
'imagelistall'     => 'све',
'watchlistall2'    => 'све',
'namespacesall'    => 'сви',

# E-mail address confirmation
'confirmemail'            => 'Потврдите адресу е-поште',
'confirmemail_noemail'    => 'Немате потврђену адресу ваше е-поште у вашим [[Special:Preferences|корисничким подешавањима интерфејса]].',
'confirmemail_text'       => 'Ова вики захтева да потврдите адресу ваше е-поште пре него што користите могућности е-поште. Активирајте дугме испод како бисте послали пошту за потврду на вашу адресу. Пошта укључује везу која садржи код; учитајте ту везу у ваш браузер да бисте потврдили да је адреса ваше е-поште валидна.',
'confirmemail_send'       => 'Пошаљи код за потврду',
'confirmemail_sent'       => 'Е-пошта за потврђивање послата.',
'confirmemail_sendfailed' => 'Пошта за потврђивање није послата. Проверита адресу због неправилних карактера.',
'confirmemail_invalid'    => 'Нетачан код за потврду. Могуће је да је код истекао.',
'confirmemail_needlogin'  => 'Морате да се $1 да бисте потврдили адресу ваше е-поште.',
'confirmemail_success'    => 'Адреса ваше е-поште је потврђена. Можете сада да се пријавите и уживате у викију.',
'confirmemail_loggedin'   => 'Адреса ваше е-поште је сада потврђена.',
'confirmemail_error'      => 'Нешто је пошло по злу приликом снимања ваше потврде.',
'confirmemail_subject'    => '{{SITENAME}} адреса е-поште за потврђивање',
'confirmemail_body'       => 'Неко, вероватно ви, је са ИП адресе $1 регистровао налог "$2" са овом адресом е-поште на сајту {{SITENAME}}.

Да потврдите да овај налог стварно припада вама и да активирате могућност е-поште на сајту {{SITENAME}}, отворите ову везу у вашем браузеру:

$3

Ако ово *нисте* ви, не пратите везу. Овај код за потврду ће истећи у $4.',

# Inputbox extension, may be useful in other contexts as well
'tryexact'       => 'Покушај тачно',
'searchfulltext' => 'Претражи цео текст',
'createarticle'  => 'Направи чланак',

# Scary transclusion
'scarytranscludedisabled' => '[Интервики укључивање је онемогућено]',
'scarytranscludefailed'   => '[Доношење шаблона неуспешно; жао нам је]',
'scarytranscludetoolong'  => '[УРЛ је предугачак; жао нам је]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">
Враћања за овај чланак:<br />
$1
</div>',
'trackbackremove'   => '([$1 Брисање])',
'trackbacklink'     => 'Враћање',
'trackbackdeleteok' => 'Враћање је успешно обрисано.',

# Delete conflict
'deletedwhileediting' => 'Упозорење: Ова страна је обрисана пошто сте почели уређивање!',
'confirmrecreate'     => "Корисник [[{{ns:user}}:$1|$1]] ([[{{ns:user_talk}}:$1|разговор]]) је обрисао овај чланак пошто сте почели уређивање са разлогом:
: ''$2''
Молимо потврдите да стварно желите да поново направите овај чланак.",
'recreate'            => 'Поново направи',

# HTML dump
'redirectingto' => 'Преусмеравам на [[$1]]...',

# action=purge
'confirm_purge'        => 'Да ли желите очистити кеш ове странице?

$1',
'confirm_purge_button' => 'Да',

'youhavenewmessagesmulti' => 'Имате нових порука на $1',

'searchcontaining' => "Претражи чланке који садрже ''$1''.",
'searchnamed'      => "Претражи чланке који се зову ''$1''.",
'articletitles'    => "Чланци почевши од ''$1''",
'hideresults'      => 'Сакриј резултате',

'loginlanguagelabel' => 'Језик: $1',

# Multipage image navigation
'imgmultipageprev' => '&larr; претходна страница',
'imgmultipagenext' => 'следећа страница &rarr;',
'imgmultigo'       => 'Иди!',
'imgmultigotopre'  => 'Иди на страницу',

# Table pager
'ascending_abbrev'         => 'раст',
'descending_abbrev'        => 'опад',
'table_pager_next'         => 'Следећа страница',
'table_pager_prev'         => 'Претходна страница',
'table_pager_first'        => 'Прва страница',
'table_pager_last'         => 'Последња страница',
'table_pager_limit'        => 'Прикажи $1 делова информације по страници',
'table_pager_limit_submit' => 'Иди',
'table_pager_empty'        => 'Без резултата',

# Auto-summaries
'autoredircomment' => 'Преусмерење на [[$1]]', # This should be changed to the new naming convention, but existed beforehand

);



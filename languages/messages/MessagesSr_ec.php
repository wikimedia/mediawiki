<?php
/** Serbian Cyrillic ekavian (Српски (ћирилица))
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Bjankuloski06
 * @author CERminator
 * @author Kale
 * @author Meno25
 * @author Millosh
 * @author Nikola Smolenski
 * @author Red Baron
 * @author Sasa Stefanovic
 * @author Slaven Kosanovic
 * @author Јованвб
 * @author Михајло Анђелковић
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Медија',
	NS_SPECIAL          => 'Посебно',
	NS_TALK             => 'Разговор',
	NS_USER             => 'Корисник',
	NS_USER_TALK        => 'Разговор_са_корисником',
	NS_PROJECT_TALK     => 'Разговор_о_$1',
	NS_FILE             => 'Слика',
	NS_FILE_TALK        => 'Разговор_о_слици',
	NS_MEDIAWIKI        => 'МедијаВики',
	NS_MEDIAWIKI_TALK   => 'Разговор_о_МедијаВикију',
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
	"Slika"                   => NS_FILE,
	"Razgovor_o_slici"        => NS_FILE_TALK,
	"MedijaViki"              => NS_MEDIAWIKI,
	"Razgovor_o_MedijaVikiju" => NS_MEDIAWIKI_TALK,
	'Šablon'                  => NS_TEMPLATE,
	'Razgovor_o_šablonu'      => NS_TEMPLATE_TALK,
	'Pomoć'                   => NS_HELP,
	'Razgovor_o_pomoći'      => NS_HELP_TALK,
	'Kategorija'              => NS_CATEGORY,
	'Razgovor_o_kategoriji'   => NS_CATEGORY_TALK,
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

$specialPageAliases = array(
	'CreateAccount'             => array( 'ОтвориНалог' ),
	'Watchlist'                 => array( 'СписакНадгледања' ),
	'Recentchanges'             => array( 'СкорашњеИзмене' ),
	'Upload'                    => array( 'Пошаљи' ),
	'Newimages'                 => array( 'НовиФајлови', 'НовеСлике' ),
	'Listusers'                 => array( 'СписакКорисника', 'КорисничкиСписак' ),
	'Statistics'                => array( 'Статистика' ),
	'Specialpages'              => array( 'СпецијалнеСтране' ),
	'Contributions'             => array( 'Доприноси' ),
	'Confirmemail'              => array( 'ПотврдиЕ-пошту' ),
	'Movepage'                  => array( 'Преусмери' ),
	'Blockme'                   => array( 'БлокирајМе' ),
	'Categories'                => array( 'Категорије' ),
	'Version'                   => array( 'Верзија' ),
	'Allmessages'               => array( 'СвеПоруке' ),
	'Blockip'                   => array( 'Блокирај', 'БлокирајИП', 'БлокирајКорисника' ),
	'Lockdb'                    => array( 'ЗакључајБазу' ),
	'Unlockdb'                  => array( 'ОткључајБазу' ),
	'Listredirects'             => array( 'СписакПреусмерења' ),
	'Mypage'                    => array( 'МојаСтраница' ),
	'Mytalk'                    => array( 'МојРазговор' ),
	'Mycontributions'           => array( 'МојиДоприноси' ),
	'Listadmins'                => array( 'ПописАдминистратора' ),
	'Listbots'                  => array( 'ПописБотова' ),
	'Search'                    => array( 'Претражи' ),
	'Activeusers'               => array( 'АктивниКорисници' ),
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
	'redirect'              => array( '0', '#Преусмери', '#redirect', '#преусмери', '#ПРЕУСМЕРИ', '#REDIRECT' ),
	'notoc'                 => array( '0', '__БЕЗСАДРЖАЈА__', '__NOTOC__' ),
	'forcetoc'              => array( '0', '__ФОРСИРАНИСАДРЖАЈ__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__САДРЖАЈ__', '__TOC__' ),
	'noeditsection'         => array( '0', '__БЕЗ_ИЗМЕНА__', '__БЕЗИЗМЕНА__', '__NOEDITSECTION__' ),
	'currentmonth'          => array( '1', 'ТРЕНУТНИМЕСЕЦ', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'      => array( '1', 'ТРЕНУТНИМЕСЕЦИМЕ', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'ТРЕНУТНИМЕСЕЦГЕН', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'    => array( '1', 'ТРЕНУТНИМЕСЕЦСКР', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'ТРЕНУТНИДАН', 'CURRENTDAY' ),
	'currentdayname'        => array( '1', 'ТРЕНУТНИДАНИМЕ', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'ТРЕНУТНАГОДИНА', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'ТРЕНУТНОВРЕМЕ', 'CURRENTTIME' ),
	'numberofarticles'      => array( '1', 'БРОЈЧЛАНАКА', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'БРОЈДАТОТЕКА', 'БРОЈФАЈЛОВА', 'NUMBEROFFILES' ),
	'pagename'              => array( '1', 'СТРАНИЦА', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'СТРАНИЦЕ', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'ИМЕНСКИПРОСТОР', 'NAMESPACE' ),
	'namespacee'            => array( '1', 'ИМЕНСКИПРОСТОРИ', 'NAMESPACEE' ),
	'fullpagename'          => array( '1', 'ПУНОИМЕСТРАНЕ', 'FULLPAGENAME' ),
	'fullpagenamee'         => array( '1', 'ПУНОИМЕСТРАНЕЕ', 'FULLPAGENAMEE' ),
	'msg'                   => array( '0', 'ПОР:', 'MSG:' ),
	'subst'                 => array( '0', 'ЗАМЕНИ:', 'SUBST:' ),
	'msgnw'                 => array( '0', 'НВПОР:', 'MSGNW:' ),
	'img_thumbnail'         => array( '1', 'мини', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'мини=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'десно', 'д', 'right' ),
	'img_left'              => array( '1', 'лево', 'л', 'left' ),
	'img_none'              => array( '1', 'н', 'без', 'none' ),
	'img_width'             => array( '1', '$1пискел', '$1п', '$1px' ),
	'img_center'            => array( '1', 'центар', 'ц', 'center', 'centre' ),
	'img_framed'            => array( '1', 'оквир', 'рам', 'framed', 'enframed', 'frame' ),
	'int'                   => array( '0', 'ИНТ:', 'INT:' ),
	'sitename'              => array( '1', 'ИМЕСАЈТА', 'SITENAME' ),
	'ns'                    => array( '0', 'ИП:', 'NS:' ),
	'localurl'              => array( '0', 'ЛОКАЛНААДРЕСА:', 'LOCALURL:' ),
	'localurle'             => array( '0', 'ЛОКАЛНЕАДРЕСЕ:', 'LOCALURLE:' ),
	'server'                => array( '0', 'СЕРВЕР', 'SERVER' ),
	'servername'            => array( '0', 'ИМЕСЕРВЕРА', 'SERVERNAME' ),
	'scriptpath'            => array( '0', 'СКРИПТА', 'SCRIPTPATH' ),
	'grammar'               => array( '0', 'ГРАМАТИКА:', 'GRAMMAR:' ),
	'notitleconvert'        => array( '0', '__БЕЗКН__', '__BEZKN__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'      => array( '0', '__БЕЗЦЦ__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'           => array( '1', 'ТРЕНУТНАНЕДЕЉА', 'CURRENTWEEK' ),
	'currentdow'            => array( '1', 'ТРЕНУТНИДОВ', 'CURRENTDOW' ),
	'revisionid'            => array( '1', 'ИДРЕВИЗИЈЕ', 'REVISIONID' ),
	'plural'                => array( '0', 'МНОЖИНА:', 'PLURAL:' ),
	'fullurl'               => array( '0', 'ПУНУРЛ:', 'FULLURL:' ),
	'fullurle'              => array( '0', 'ПУНУРЛЕ:', 'FULLURLE:' ),
	'lcfirst'               => array( '0', 'ЛЦПРВИ:', 'LCFIRST:' ),
	'ucfirst'               => array( '0', 'УЦПРВИ:', 'UCFIRST:' ),
	'lc'                    => array( '0', 'ЛЦ:', 'LC:' ),
	'uc'                    => array( '0', 'УЦ:', 'UC:' ),
);
$separatorTransformTable = array(',' => '.', '.' => ',' );

$messages = array(
# User preference toggles
'tog-underline'               => 'Подвуци везе:',
'tog-highlightbroken'         => 'Форматирај покварене везе <a href="" class="new">овако</a> (алтернатива: овако<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Уравнај пасусе',
'tog-hideminor'               => 'Сакриј мале измене у списку скорашњих измена',
'tog-hidepatrolled'           => 'Сакриј патролиране измене у списку скорашњих измена',
'tog-newpageshidepatrolled'   => 'Сакриј патролиране странице са списка нових страница',
'tog-extendwatchlist'         => 'Проширује списак надгледања тако да показује све измене, не само најсвежије',
'tog-usenewrc'                => 'Користи побољшан списак скорашњих измена (захтева JavaScript)',
'tog-numberheadings'          => 'Аутоматски нумериши поднаслове',
'tog-showtoolbar'             => 'Прикажи дугмиће за измене (захтева JavaScript)',
'tog-editondblclick'          => 'Мењај странице двоструким кликом (захтева JavaScript)',
'tog-editsection'             => 'Омогући измену делова [уреди] везама',
'tog-editsectiononrightclick' => 'Омогући измену делова десним кликом<br />на њихове наслове (захтева JavaScript)',
'tog-showtoc'                 => 'Прикажи садржај (у страницама са више од 3 поднаслова)',
'tog-rememberpassword'        => 'Памти лозинку кроз више сеанси',
'tog-watchcreations'          => 'Додај странице које правим у мој списак надгледања',
'tog-watchdefault'            => 'Додај странице које мењам у мој списак надгледања',
'tog-watchmoves'              => 'Додај странице које премештам у мој списак надгледања',
'tog-watchdeletion'           => 'Додај странице које бришем у мој списак надгледања',
'tog-minordefault'            => 'Означи све измене малим испрва',
'tog-previewontop'            => 'Прикажи претпреглед пре поља за измену',
'tog-previewonfirst'          => 'Прикажи претпреглед при првој измени',
'tog-nocache'                 => 'Онемогући кеширање страница',
'tog-enotifwatchlistpages'    => 'Пошаљи ми е-пошту када се промени страна коју надгледам',
'tog-enotifusertalkpages'     => 'Пошаљи ми е-пошту када се промени моја корисничка страна за разговор',
'tog-enotifminoredits'        => 'Пошаљи ми е-пошту такође за мале измене страна',
'tog-enotifrevealaddr'        => 'Откриј адресу моје е-поште у пошти обавештења',
'tog-shownumberswatching'     => 'Прикажи број корисника који надгледају',
'tog-oldsig'                  => 'Претпреглед постојећег потписа:',
'tog-fancysig'                => 'Третирај потпис као викитекст (без аутоматског повезивања)',
'tog-externaleditor'          => 'Користи спољашњи уређивач по подразумеваним подешавањима (само за експерте, пошто су неопходна посебна подешавања у вашем рачунару)',
'tog-externaldiff'            => 'Користи спољашњи програм за приказ разлика (само за експерте, пошто су неопходна посебна подешавања у вашем рачунару)',
'tog-showjumplinks'           => 'Омогући "скочи на" повезнице',
'tog-uselivepreview'          => 'Користи претпреглед уживо (JavaScript) (експериментално)',
'tog-forceeditsummary'        => 'Упозори ме кад не унесем опис измене',
'tog-watchlisthideown'        => 'Сакриј моје измене са списка надгледања',
'tog-watchlisthidebots'       => 'Сакриј измене ботова са списка надгледања',
'tog-watchlisthideminor'      => 'Сакриј мале измене са списка надгледања',
'tog-watchlisthideliu'        => 'Сакриј измене пријављених корисника са списка надгледања',
'tog-watchlisthideanons'      => 'Сакриј измене непријављених корисника са списка надгледања',
'tog-watchlisthidepatrolled'  => 'Сакриј патролиране измене у списку надгледања',
'tog-nolangconversion'        => 'Искључи конверзију варијанти',
'tog-ccmeonemails'            => 'Пошаљи ми копије порука које шаљем другим корисницима путем е-поште',
'tog-diffonly'                => 'Не приказуј садржај странице испод разлике странице',
'tog-showhiddencats'          => 'Прикажи скривене категорије',
'tog-norollbackdiff'          => 'Сакриј разлике верзија након враћања',

'underline-always'  => 'Увек',
'underline-never'   => 'Никад',
'underline-default' => 'По подешавањима браузера',

# Font style option in Special:Preferences
'editfont-style'     => 'Измени стил фонта за овај део:',
'editfont-default'   => 'Подразумеван из браузера',
'editfont-monospace' => 'Фонт са једнаким размацима',
'editfont-sansserif' => 'Sans-serif фонт',
'editfont-serif'     => 'Serif фонт',

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

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Категорија|Категорије|Категорије}} страница',
'category_header'                => 'Чланака у категорији "$1"',
'subcategories'                  => 'Поткатегорије',
'category-media-header'          => 'Мултимедијалних фајлова у категорији "$1"',
'category-empty'                 => "''Ова категорија тренутно не садржи чланке нити медије.''",
'hidden-categories'              => '{{PLURAL:$1|Скривена категорија|Скривене категорије|Скривених категорија}}',
'hidden-category-category'       => 'Скривене категорије',
'category-subcat-count'          => '{{PLURAL:$2|Ова категорија има само следећу поткатегорију.|Ова категорија има {{PLURAL:$1|следећу поткатегорију|$1 следеће поткатегорије|$1 следећих поткатегорија}}, од укупно $2.}}',
'category-subcat-count-limited'  => 'Ова категорија садржи {{PLURAL:$1|следећу поткатегорију|$1 следеће поткатегорије}}.',
'category-article-count'         => '{{PLURAL:$2|Ова категорија садржи само следећу страну.|{{PLURAL:$1|страна је|$1 стране је|$1 страна је}} у овој категорији од укупно $2.}}',
'category-article-count-limited' => '{{PLURAL:$1|Следећа страна је|$1 следеће стране су|$1 следећих страна је}} у овој категорији.',
'category-file-count'            => '{{PLURAL:$2|Ова категорија садржи само следећи фајл.|{{PLURAL:$1|Следећи фајл је|$1 следећа фајла су|$1 следећих фајлова су}} у овој категорији, од укупно $2.}}',
'category-file-count-limited'    => 'Следећи {{PLURAL:$1|фајл је|$1 фајлови су}} у овој категорији.',
'listingcontinuesabbrev'         => 'наст.',
'index-category'                 => 'Индексиране странице',
'noindex-category'               => 'Неиндексиране странице',

'mainpagetext'      => "'''МедијаВики је успешно инсталиран.'''",
'mainpagedocfooter' => 'Молимо видите [http://meta.wikimedia.org/wiki/Help:Contents кориснички водич] за информације о употреби вики софтвера.

== За почетак ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Помоћ у вези са подешавањима]
* [http://www.mediawiki.org/wiki/Manual:FAQ Најчешће постављена питања]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Мејлинг листа о издањима МедијаВикија]',

'about'         => 'О...',
'article'       => 'Чланак',
'newwindow'     => '(нови прозор)',
'cancel'        => 'Одустани',
'moredotdotdot' => 'Још...',
'mypage'        => 'Моја страница',
'mytalk'        => 'Мој разговор',
'anontalk'      => 'Разговор за ову ИП адресу',
'navigation'    => 'Навигација',
'and'           => '&#32;и',

# Cologne Blue skin
'qbfind'         => 'Пронађи',
'qbbrowse'       => 'Прелиставај',
'qbedit'         => 'Уреди',
'qbpageoptions'  => 'Опције странице',
'qbpageinfo'     => 'Информације о страници',
'qbmyoptions'    => 'Моје опције',
'qbspecialpages' => 'Посебне странице',
'faq'            => 'НПП',
'faqpage'        => 'Project:НПП',

# Vector skin
'vector-action-addsection'   => 'Додај тему',
'vector-action-delete'       => 'Обриши',
'vector-action-move'         => 'Премести',
'vector-action-protect'      => 'Заштити',
'vector-action-undelete'     => 'Врати',
'vector-action-unprotect'    => 'Скини заштиту',
'vector-namespace-category'  => 'Категорија',
'vector-namespace-help'      => 'Страна помоћи',
'vector-namespace-image'     => 'Фајл',
'vector-namespace-main'      => 'Страна',
'vector-namespace-media'     => 'Страница медија',
'vector-namespace-mediawiki' => 'Порука',
'vector-namespace-project'   => 'Страна пројекта',
'vector-namespace-special'   => 'Посебна страна',
'vector-namespace-talk'      => 'Разговор',
'vector-namespace-template'  => 'Шаблон',
'vector-namespace-user'      => 'Корисничка страна',
'vector-view-create'         => 'Направи',
'vector-view-edit'           => 'Измени',
'vector-view-history'        => 'Види историју',
'vector-view-view'           => 'Читај',
'vector-view-viewsource'     => 'Погледај код',
'actions'                    => 'Акције',
'namespaces'                 => 'Именски простори',
'variants'                   => 'Варијанте',

'errorpagetitle'    => 'Грешка',
'returnto'          => 'Повратак на $1.',
'tagline'           => 'Из {{SITENAME}}',
'help'              => 'Помоћ',
'search'            => 'Претрага',
'searchbutton'      => 'Претрага',
'go'                => 'Иди',
'searcharticle'     => 'Иди',
'history'           => 'Историја странице',
'history_short'     => 'Историја',
'updatedmarker'     => 'ажурирано од моје последње посете',
'info_short'        => 'Информације',
'printableversion'  => 'Верзија за штампу',
'permalink'         => 'Пермалинк',
'print'             => 'Штампа',
'edit'              => 'Уреди',
'create'            => 'Креирај',
'editthispage'      => 'Уреди ову страницу',
'create-this-page'  => 'Направи ову страницу',
'delete'            => 'Обриши',
'deletethispage'    => 'Обриши ову страницу',
'undelete_short'    => 'врати {{PLURAL:$1|једну обрисану измену|$1 обрисане измене|$1 обрисаних измена}}',
'protect'           => 'заштити',
'protect_change'    => 'промени',
'protectthispage'   => 'Заштити ову страницу',
'unprotect'         => 'Склони заштиту',
'unprotectthispage' => 'Склони заштиту са ове странице',
'newpage'           => 'Нова страница',
'talkpage'          => 'Разговор о овој страници',
'talkpagelinktext'  => 'Разговор',
'specialpage'       => 'Посебна страница',
'personaltools'     => 'Лични алати',
'postcomment'       => 'Нова секција',
'articlepage'       => 'Погледај чланак',
'talk'              => 'Разговор',
'views'             => 'Прегледи',
'toolbox'           => 'алати',
'userpage'          => 'Погледај корисничку страну',
'projectpage'       => 'Погледај страну пројекта',
'imagepage'         => 'Погледај страну датотеке',
'mediawikipage'     => 'Види страницу поруке',
'templatepage'      => 'Види страницу шаблона',
'viewhelppage'      => 'Види страницу помоћи',
'categorypage'      => 'Види страницу категорије',
'viewtalkpage'      => 'Погледај разговор',
'otherlanguages'    => 'Остали језици',
'redirectedfrom'    => '(Преусмерено са $1)',
'redirectpagesub'   => 'Страна преусмерења',
'lastmodifiedat'    => 'Ова страница је последњи пут измењена $2, $1.',
'viewcount'         => 'Овој страници је приступљено {{PLURAL:$1|једном|$1 пута|$1 пута}}.',
'protectedpage'     => 'Заштићена страница',
'jumpto'            => 'Скочи на:',
'jumptonavigation'  => 'навигација',
'jumptosearch'      => 'претрага',
'view-pool-error'   => 'Жао нам је, сервери су тренутно презаузети.
Превише корисника покушава да приступи овој страници.
Молимо вас да сачекате неко време пре него покушате опет да јој приступите.

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'О пројекту {{SITENAME}}',
'aboutpage'            => 'Project:О',
'copyright'            => 'Садржај је објављен под $1.',
'copyrightpage'        => '{{ns:project}}:Ауторска права',
'currentevents'        => 'Тренутни догађаји',
'currentevents-url'    => 'Project:Тренутни догађаји',
'disclaimers'          => 'Одрицање одговорности',
'disclaimerpage'       => 'Project:Услови коришћења, правне напомене и одрицање одговорности',
'edithelp'             => 'Помоћ око уређивања',
'edithelppage'         => 'Help:Како се мења страна',
'helppage'             => 'Help:Садржај',
'mainpage'             => 'Главна страна',
'mainpage-description' => 'Главна страна',
'policy-url'           => 'Project:Политика приватности',
'portal'               => 'Радионица',
'portal-url'           => 'Project:Радионица',
'privacy'              => 'Политика приватности',
'privacypage'          => 'Project:Политика_приватности',

'badaccess'        => 'Грешка у дозволама',
'badaccess-group0' => 'Није вам дозвољено да извршите акцију коју сте покренули.',
'badaccess-groups' => 'Акција коју сте покренули је резервисана за кориснике у {{PLURAL:$2|групи|једној од група}}:  $1.',

'versionrequired'     => 'Верзија $1 МедијаВикија је потребна',
'versionrequiredtext' => 'Верзија $1 МедијаВикија је потребна да би се користила ова страна. Погледајте [[Special:Version|верзију]]',

'ok'                      => 'да',
'retrievedfrom'           => 'Добављено из "$1"',
'youhavenewmessages'      => 'Имате $1 ($2).',
'newmessageslink'         => 'нових порука',
'newmessagesdifflink'     => 'најсвежије измене',
'youhavenewmessagesmulti' => 'Имате нових порука на $1',
'editsection'             => 'уреди',
'editold'                 => 'уреди',
'viewsourceold'           => 'погледај код',
'editlink'                => 'уреди',
'viewsourcelink'          => 'погледај код',
'editsectionhint'         => 'Уреди део: $1',
'toc'                     => 'Садржај',
'showtoc'                 => 'прикажи',
'hidetoc'                 => 'сакриј',
'thisisdeleted'           => 'Погледај или врати $1?',
'viewdeleted'             => 'Погледај $1?',
'restorelink'             => '{{PLURAL:$1|једна обрисана измена|$1 обрисане измене|$1 обрисаних измена}}',
'feedlinks'               => 'Фид:',
'feed-invalid'            => 'Лош тип фида пријаве.',
'feed-unavailable'        => 'Фидови нису доступни',
'site-rss-feed'           => '$1 RSS фид',
'site-atom-feed'          => '$1 Atom фид',
'page-rss-feed'           => '"$1" RSS фид',
'page-atom-feed'          => '"$1" Atom фид',
'feed-atom'               => 'Атом',
'red-link-title'          => '$1 (страница не постоји)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Чланак',
'nstab-user'      => 'Корисничка страна',
'nstab-media'     => 'Страница медија',
'nstab-special'   => 'Посебна страница',
'nstab-project'   => 'Страна пројекта',
'nstab-image'     => 'Слика',
'nstab-mediawiki' => 'Порука',
'nstab-template'  => 'Шаблон',
'nstab-help'      => 'Помоћ',
'nstab-category'  => 'Категорија',

# Main script and global functions
'nosuchaction'      => 'Нема такве акције',
'nosuchactiontext'  => 'Акцију наведену у УРЛ-у вики софтвер није препознао.
Могуће је да сте укуцалчи погрешан УРЛ, или следили застарелу везу.
Такође је могуће да се ради о грешци у вики софтверу.',
'nosuchspecialpage' => 'Нема такве посебне странице',
'nospecialpagetext' => '<strong>Тражили сте непостојећу посебну страницу.</strong>

Списак свих посебних страница се може наћи на [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Грешка',
'databaseerror'        => 'Грешка у бази',
'dberrortext'          => 'Дошло је до синтаксне грешке у бази.
Ово може да означи баг у софтверу.
Последњи послати упит бази био је:
<blockquote><tt>$1</tt></blockquote>
унутар функције "<tt>$2</tt>".
База података је вратила грешку "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Дошло је до синтаксне грешке у бази.
Последњи послати упит бази био је:
"$1"
унутар функције "$2".
База података је вратила грешку "$3: $4"',
'laggedslavemode'      => 'Упозорење: могуће је да страна није скоро ажурирана.',
'readonly'             => 'База података је закључана',
'enterlockreason'      => 'Унесите разлог за закључавање, укључујући процену времена до откључавања',
'readonlytext'         => 'База података је тренутно закључана за нове
уносе и остале измене, вероватно због рутинског одржавања,
после чега ће бити враћена у уобичајено стање.

Администратор који ју је закључао понудио је ово објашњење: $1',
'missing-article'      => 'Текст стране под именом "$1" ($2) није пронађен.

Узрок за ову грешку је обично застарели diff или веза ка обрисаној верзији чланка.

Ако то није случај, можда сте пронашли баг у софтверу. 
У том случају, пријавите грешку [[Special:ListUsers/sysop|администратору]] уз одговарајући линк.',
'missingarticle-rev'   => '(ревизија#: $1)',
'missingarticle-diff'  => '(Раз: $1, $2)',
'readonly_lag'         => 'База података је аутоматски закључана док помоћни сервери не сустигну мастер',
'internalerror'        => 'Интерна грешка',
'internalerror_info'   => 'Интерна грешка: $1',
'fileappenderrorread'  => 'Није било могуће прочитати "$1" за време ажурирања.',
'fileappenderror'      => 'Није било могуће ажурирати "$1" на "$2".',
'filecopyerror'        => 'Не могу да ископирам фајл "$1" на "$2".',
'filerenameerror'      => 'Не могу да преименујем фајл "$1" у "$2".',
'filedeleteerror'      => 'Не могу да обришем фајл "$1".',
'directorycreateerror' => 'Не могу да направим директоријум "$1".',
'filenotfound'         => 'Не могу да нађем фајл "$1".',
'fileexistserror'      => 'Не могу да пишем по фајлу "$1": фајл постоји',
'unexpected'           => 'Неочекивана вредност: "$1"="$2".',
'formerror'            => 'Грешка: не могу да пошаљем упитник',
'badarticleerror'      => 'Ова акција не може бити извршена на овој страници.',
'cannotdelete'         => 'Ову страну или фајл није било могуће обрисати: "$1".
Вероватно ју је неко раније обрисао.',
'badtitle'             => 'Лош наслов',
'badtitletext'         => 'Захтевани наслов странице је био неисправан, празан или
неисправно повезан међујезички или интервики наслов. Можда садржи један или више карактера који не могу да се употребљавају у насловима.',
'perfcached'           => 'Следећи подаци су кеширани и не морају бити у потпуности ажурирани.',
'perfcachedts'         => 'Следећи подаци су кеширани и последњи пут су ажурирани $1.',
'querypage-no-updates' => 'Ажурирање ове странице је тренутно онемогућено. Подаци одавде неће бити одмах освежени.',
'wrong_wfQuery_params' => 'Нетачни параметри за wfQuery()<br />
Функција: $1<br />
Претрага: $2',
'viewsource'           => 'погледај код',
'viewsourcefor'        => 'за $1',
'actionthrottled'      => 'Акцији је смањена брзина.',
'actionthrottledtext'  => 'У циљу борбе против спама, нисте у могућности да то учините више пута у кратком времену, а управо сте прешли тај лимит. Покушајте поново за пар минута.',
'protectedpagetext'    => 'Ова страница је закључана како се не би вршиле измене на њој.',
'viewsourcetext'       => 'Можете да прегледате и копирате садржај ове стране:',
'protectedinterface'   => 'Ова страна пружа текст интерфејса за софтвер и закључана је како би се спречила злоупотреба.',
'editinginterface'     => "'''Упозорење:''' Уређујете страну која се користи да пружи текст за интерфејс овог софтвера. 
Измене на овој страни ће утицати на приказ изгледа корисничког интерфејса за све кориснике.
За преводе, посетите [http://translatewiki.net/wiki/Main_Page?setlang=sr_ec translatewiki.net], пројекат локализације МедијаВики софтвера.",
'sqlhidden'            => '(SQL претрага сакривена)',
'cascadeprotected'     => 'Ова страница је закључана и њено уређивање је онемогућено јер је укључена у садржај {{PLURAL:$1|следеће стране|следећих страна}}, који је заштићен преко опције "преносива" заштита:
$2',
'namespaceprotected'   => "Немате овлашћења да уређујете странице у '''$1''' именском простору.",
'customcssjsprotected' => 'Немате овлашћења да уређујете ову страницу јер садржи лична подешавања другог корисника.',
'ns-specialprotected'  => 'Странице у именском простору {{ns:special}} није могуће уређивати.',
'titleprotected'       => "Овај наслов је блокиран за прављење.
Блокирао га је [[User:$1|$1]] а дати разлог је ''$2''.",

# Virus scanner
'virus-badscanner'     => "Лоша конфигурација због неодговарајућег скенера за вирус: ''$1''",
'virus-scanfailed'     => 'скенирање пропало (код $1)',
'virus-unknownscanner' => 'непознати антивирус:',

# Login and logout pages
'logouttext'                 => "'''Сада сте одјављени.'''

Можете да наставите да користите пројекат {{SITENAME}} анонимно, или се [[Special:UserLogin|поново пријавити]] као други корисник.
Обратите пажњу да неке странице могу наставити да се приказују као да сте још увек пријављени, док не очистите кеш свог браузера.",
'welcomecreation'            => '== Добродошли, $1! ==

Ваш налог је креиран.
Не заборавите да прилагодите себи своја [[Special:Preferences|{{SITENAME}} подешавања]].',
'yourname'                   => 'Корисничко име:',
'yourpassword'               => 'Ваша лозинка:',
'yourpasswordagain'          => 'Поновите лозинку:',
'remembermypassword'         => 'Запамти ме на овом рачунару',
'yourdomainname'             => 'Ваш домен:',
'externaldberror'            => 'Дошло је или до грешке при спољашњој аутентификацији базе података, или вам није дозвољено да ажурирате свој спољашњи налог.',
'login'                      => 'Пријави се',
'nav-login-createaccount'    => 'Региструј се / Пријави се',
'loginprompt'                => "Морате да имате омогућене колачиће (''cookies'') да бисте се пријавили на {{SITENAME}}.",
'userlogin'                  => 'Региструј се / Пријави се',
'userloginnocreate'          => 'Пријави се',
'logout'                     => 'Одјави се',
'userlogout'                 => 'Одјави се',
'notloggedin'                => 'Нисте пријављени',
'nologin'                    => "Немате налог? '''$1'''.",
'nologinlink'                => 'Направите налог',
'createaccount'              => 'Направи налог',
'gotaccount'                 => "Имате налог? '''$1'''.",
'gotaccountlink'             => 'Пријавите се',
'createaccountmail'          => 'е-поштом',
'badretype'                  => 'Лозинке које сте унели се не поклапају.',
'userexists'                 => 'Корисничко име које сте унели већ је у употреби.
Молимо изаберите друго име.',
'loginerror'                 => 'Грешка при пријављивању',
'createaccounterror'         => 'Није могуће направити налог: $1',
'nocookiesnew'               => "Кориснички налог је направљен, али нисте пријављени. {{SITENAME}} користи колачиће (''cookies'') да би се корисници пријавили. Ви сте онемогућили колачиће на свом рачунару. Молимо омогућите их, а онда се пријавите са својим новим корисничким именом и лозинком.",
'nocookieslogin'             => "{{SITENAME}} користи колачиће (''cookies'') да би се корисници пријавили. Ви сте онемогућили колачиће на свом рачунару. Молимо омогућите их и покушајте поново са пријавом.",
'noname'                     => 'Нисте изабрали исправно корисничко име.',
'loginsuccesstitle'          => 'Пријављивање успешно',
'loginsuccess'               => "'''Сада сте пријављени на {{SITENAME}} као \"\$1\".'''",
'nosuchuser'                 => 'Не постоји корисник под именом "$1".
Код корисничких имена се прави разлика између малог и великог слова.
Проверите да ли сте га добро укуцали, или [[Special:UserLogin/signup|направите нови кориснички налог]].',
'nosuchusershort'            => 'Не постоји корисник са именом "<nowiki>$1</nowiki>". Проверите да ли сте добро написали.',
'nouserspecified'            => 'Морате да назначите корисничко име.',
'login-userblocked'          => 'Овај корисник је блокиран. Логовање није дозвољено.',
'wrongpassword'              => 'Лозинка коју сте унели је неисправна. Молимо покушајте поново.',
'wrongpasswordempty'         => 'Лозинка коју сте унели је празна. Молимо покушајте поново.',
'passwordtooshort'           => 'Ваша лозинка је прекратка.
Мора имати најмање {{PLURAL:$1|1 карактер|$1 карактера}}.',
'password-name-match'        => 'Ваша лозинка мора бити другачија од вашег корисничког имена.',
'mailmypassword'             => 'Пошаљи ми нову лозинку',
'passwordremindertitle'      => '{{SITENAME}} подсетник за шифру',
'passwordremindertext'       => 'Неко (вероватно ви, са ИП адресе $1) је захтевао да вам пошаљемо нову
шифру за пријављивање на {{SITENAME}} ($4). Привремена шифра за корисника 
„$2“ је генерисана и сада је „$3“. Уколико је ово
Ваш захтев, сада се пријавите и изаберите нову шифу.
Ваша привремена шифра истиче за {{PLURAL:$5|једна дан|$5 дана}}.

Уколико је неко други захтевао промену шифре, или сте ви заборавили вашу 
шифру и више не желите да је мењате, можете игнорисати ову поруку и
наставити користити вашу стару.',
'noemail'                    => 'Не постоји адреса е-поште за корисника "$1".',
'noemailcreate'              => 'Морате унети исправну адресу е-поште',
'passwordsent'               => 'Нова шифра је послата на адресу е-поште корисника "$1".
Молимо пријавите се пошто је примите.',
'blocked-mailpassword'       => 'Вашој ИП адреси је блокиран приступ уређивању, из ког разлога није могуће користити функцију подсећања лозинке, ради превенције извршења недозвољене акције.',
'eauthentsent'               => 'Е-пошта за потврду је послата на назначену адресу е-поште. Пре него што се било која друга е-пошта пошаље на налог, мораћете да пратите упутства у е-пошти, да бисте потврдили да је налог заиста ваш.',
'throttled-mailpassword'     => 'Подсетник лозинке вам је већ послао једну поруку у {{PLURAL:$1|протеклом сату|последњих $1 сата|последњих $1 сати}}. 
Ради превенције извршења недозвољене акције, подсетник шаље само једну поруку у року од {{PLURAL:$1|једног сата|$1 сата|$1 сати}}.',
'mailerror'                  => 'Грешка при слању е-поште: $1',
'acct_creation_throttle_hit' => 'Посетиоци овог викија су, користећи Вашу IP адресу, већ направили {{PLURAL:$1|један налог|$1 налога}} током задњег дана, што је дозвољени максимум за овај временски период.
За последицу, посетиоци који користе ову IP адресу тренутно не могу да отворе још налога.',
'emailauthenticated'         => 'Ваша адреса е-поште је потврђена: $2 на $3.',
'emailnotauthenticated'      => 'Ваша адреса е-поште још увек није потврђена. Е-пошта неће бити послата ни за једну од следећих могућности.',
'noemailprefs'               => 'Назначите адресу е-поште како би ове могућности радиле.',
'emailconfirmlink'           => 'Потврдите вашу адресу е-поште',
'invalidemailaddress'        => 'Адреса е-поште не може бити примљена јер изгледа није правилног формата. 
Молимо унесите добро-форматирану адресу или испразните то поље.',
'accountcreated'             => 'Налог је направљен',
'accountcreatedtext'         => 'Кориснички налог за $1 је направљен.',
'createaccount-title'        => 'Прављење корисничког налога за {{SITENAME}}',
'createaccount-text'         => 'Неко је направио налог са вашом адресом е-поште на {{SITENAME}} ($4) под именом „$2”, са лозинком „$3”.
Пријавите се и промените вашу лозинку.

Можете игронисати ову поруку, уколико је налог направљен грешком.',
'usernamehasherror'          => 'Корисничко име не може садржати знаке тарабе (#).',
'login-throttled'            => 'Урадили сте превише скорих покушаја да се улогујете.
Молимо вас да сачекате пар минута и покушате опет.',
'loginlanguagelabel'         => 'Језик: $1',
'suspicious-userlogout'      => 'Ваш захтев за излоговање није извршен зато што изгледа да је послат из неисправног браузера или преко кешираног проксија.',

# Password reset dialog
'resetpass'                 => 'Промените корисничку лозинку',
'resetpass_announce'        => 'Пријавили сте се са привременом лозинком послатом електронском поштом. Да бисте завршили са пријавом, морате подесити нову лозинку овде:',
'resetpass_header'          => 'Промените лозинку налога',
'oldpassword'               => 'Стара лозинка:',
'newpassword'               => 'Нова лозинка:',
'retypenew'                 => 'Поново откуцајте нову лозинку:',
'resetpass_submit'          => 'Подеси лозинку и пријави се',
'resetpass_success'         => 'Ваша лозинка је успешно промењена! Пријављивање у току...',
'resetpass_forbidden'       => 'Лозинке не могу бити промењене',
'resetpass-no-info'         => 'Морате бити улоговани да бисте овој страни приступили директно.',
'resetpass-submit-loggedin' => 'Измени лозинку',
'resetpass-submit-cancel'   => 'Поништи',
'resetpass-wrong-oldpass'   => 'Неисправна привремена или актуелна лозинка.
Можда сте већ успешно променили лозинку или затражили нову привремену.',
'resetpass-temp-password'   => 'Привремена шифра:',

# Edit page toolbar
'bold_sample'     => 'подебљан текст',
'bold_tip'        => 'подебљан текст',
'italic_sample'   => 'курзиван текст',
'italic_tip'      => 'курзиван текст',
'link_sample'     => 'наслов везе',
'link_tip'        => 'унутрашња веза',
'extlink_sample'  => 'http://www.example.com опис адресе',
'extlink_tip'     => 'спољашња веза (не заборавите префикс http://)',
'headline_sample' => 'Наслов',
'headline_tip'    => 'Наслов другог нивоа',
'math_sample'     => 'Овде унесите формулу',
'math_tip'        => 'Математичка формула (LaTeX)',
'nowiki_sample'   => 'Додај неформатирани текст овде',
'nowiki_tip'      => 'Игнориши вики форматирање',
'image_sample'    => 'Пример.jpg',
'image_tip'       => 'Уклопљена слика',
'media_sample'    => 'име_медија_фајла.ogg',
'media_tip'       => 'Путања ка мултимедијалном фајлу',
'sig_tip'         => 'Ваш потпис са тренутним временом',
'hr_tip'          => 'Хоризонтална линија',

# Edit pages
'summary'                          => 'Опис измене:',
'subject'                          => 'Тема/Наслов:',
'minoredit'                        => 'Ово је мала измена',
'watchthis'                        => 'Надгледај овај чланак',
'savearticle'                      => 'Сними страницу',
'preview'                          => 'Претпреглед',
'showpreview'                      => 'Прикажи претпреглед',
'showlivepreview'                  => 'Претпреглед уживо',
'showdiff'                         => 'Прикажи промене',
'anoneditwarning'                  => "'''Пажња:''' Нисте пријављени. Ваша ИП адреса ће бити забележена у историји измена ове стране.",
'missingsummary'                   => "'''Подсетник:''' Нисте унели опис измене. Уколико кликнете Сними страницу поново, ваше измене ће бити снимљене без описа.",
'missingcommenttext'               => 'Унестите коментар доле.',
'missingcommentheader'             => "'''Подсетник:''' Нисте навели наслов овог коментара. Уколико кликнете ''Сними поново'', ваш коментар ће бити снимљен без наслова.",
'summary-preview'                  => 'Претпреглед описа измене:',
'subject-preview'                  => 'Претпреглед предмета/одељка:',
'blockedtitle'                     => 'Корисник је блокиран',
'blockedtext'                      => "'''Ваше корисничко име или ИП адреса је блокирана.'''

Блокирање је извршеио $1. 
Дати разлог је следећи: ''$2''.

* Почетак блока: $8
* Истек блока: $6
* Блокирани: $7

Можете контактирати $1 или другог [[{{MediaWiki:Grouppage-sysop}}|администратора]] да бисте разговарали о блокади.
Не можете користити „Пошаљи е-пошту овом кориснику“ функцију уколико нисте регистровали важећу адресу за е-пошту у вашим [[Special:Preferences|подешавањима]].
Ваша ИП адреса је $3, и ИД број блока је #$5. 
При сваком захтеву наведите оба, или само један податак.",
'autoblockedtext'                  => 'Ваша IP адреса је аутоматски блокирана јер ју је употребљавао други корисник, кога је блокирао $1.
Дат разлог је:

:\'\'$2\'\'

* Почетак блокаде: $8
* Блокада истиче: $6
* Блокирани: $7

Можете контактирати $1 или неког другог
[[{{MediaWiki:Grouppage-sysop}}|администратора]] да бисте разјаснили ову блокаду.

Имајте у виду да не можете да користите опцију "пошаљи е-пошту овом кориснику" уколико нисте приложили исправну адресу електронске поште
у вашим [[Special:Preferences|корисничким подешавањима]] и уколико вам блокадом није онемогућена употреба ове опције.

ИП адреса која је блокирана је $3, а ID ваше блокаде је $5. 
Молимо вас наведите овај ID број приликом прављења било каквих упита.',
'blockednoreason'                  => 'није дат разлог',
'blockedoriginalsource'            => "Извор '''$1''' је приказан испод:",
'blockededitsource'                => "Текст '''ваших измена''' за '''$1''' је приказан испод:",
'whitelistedittitle'               => 'Обавезно је пријављивање за мењање',
'whitelistedittext'                => 'Морате да се $1 да бисте мењали странице.',
'confirmedittext'                  => 'Морате потврдити вашу адресу е-поште пре уређивања страна. Молимо поставите и потврдите адресу ваше е-поште преко ваших [[Special:Preferences|корисничких подешавања]].',
'nosuchsectiontitle'               => 'Не постоји такав одељак',
'nosuchsectiontext'                => 'Покушали сте да уредите одељак који не постоји.
Можда је био премештен или обрисан док сте прегледали страну.',
'loginreqtitle'                    => 'Потребно пријављивање',
'loginreqlink'                     => 'пријава',
'loginreqpagetext'                 => 'Морате $1 да бисте видели остале стране.',
'accmailtitle'                     => 'Лозинка је послата.',
'accmailtext'                      => "Случајно генерисана лозинка за [[User talk:$1|$1]] је послата на $2.

Лозинка за овај нови налог може бити промењена на ''[[Special:ChangePassword|change password]]'', након пријављивања.",
'newarticle'                       => '(Нови)',
'newarticletext'                   => 'Следили сте линк ка страни која још увек не постоји.
Да бисте је направили, започните је у кутији испод (види [[{{MediaWiki:Helppage}}|страну помоћи]] за више информација).
Ако сте овде дошли грешком, притисните у Вашем браузеру дугме за повратак на претходну страну.',
'anontalkpagetext'                 => '---- Ово је страница за разговор за анонимног корисника који још није направио налог, или га не користи. 
Због тога морамо да користимо бројчану ИП адресу како бисмо идентификовали њега или њу. 
Такву адресу може делити више корисника. 
Ако сте анонимни корисник и мислите да су вам упућене небитне примедбе, молимо вас да [[Special:UserLogin/signup|направите налог]] или [[Special:UserLogin|се пријавите]] да бисте избегли будућу забуну са осталим анонимним корисницима.',
'noarticletext'                    => 'Тренутно не постоји чланак под тим именом.
Можете [[Special:Search/{{PAGENAME}}|тражити ову страницу]] у другим чланцима,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} претражити сродне историје записа], или је [{{fullurl:{{FULLPAGENAME}}|action=edit}} уредити].',
'userpage-userdoesnotexist'        => 'Налог "$1" није регистрован. Проверите да ли желите да правите/уређујете ову страницу.',
'userpage-userdoesnotexist-view'   => 'Кориснички налог "$1" није регистрован.',
'blocked-notice-logextract'        => 'Овај корисник је тренутно блокриан.
Подаци о последњем блокирању су приложени испод као додатна информација:',
'clearyourcache'                   => "'''Запамтите:''' Након снимања, можда морате очистити кеш вашег браузера да бисте видели промене. '''Mozilla / Firefox / Safari:''' држите ''Shift'' док кликћете ''Reload'' или притисните  ''Shift+Ctrl+R'' (''Cmd-Shift-R'' на ''Apple Mac'' машини); '''IE:''' држите ''Ctrl'' док кликћете ''Refresh'' или притисните ''Ctrl-F5''; '''Konqueror:''': само кликните ''Reload'' дугме или притисните ''F5''; корисници '''Оpera''' браузера можда морају да у потпуности очисте свој кеш преко ''Tools→Preferences''.",
'usercssyoucanpreview'             => "'''Савет:''' Кориситите 'Прикажи претпреглед' дугме да тестирате свој нови CSS пре снимања.",
'userjsyoucanpreview'              => "'''Савет:''' Кориситите 'Прикажи претпреглед' дугме да тестирате свој нови JS пре снимања.",
'usercsspreview'                   => "'''Запамтите ово је само претпреглед вашег CSS.'''
'''Још увек није снимљен!'''",
'userjspreview'                    => "'''Запамтите ово је само претпреглед ваше JavaScript-е и да још увек није снимљен!'''",
'userinvalidcssjstitle'            => "'''Пажња:''' Не постоји кожа \"\$1\". Запамтите да личне .css и .js странице користе мала почетна слова, нпр. {{ns:user}}:Петар/monobook.css а не {{ns:user}}:Петар/Monobook.css.",
'updated'                          => '(Ажурирано)',
'note'                             => "'''Напомена:'''",
'previewnote'                      => "'''Ово је само претпреглед.'''
Ваше измене још увек нису сачуване!",
'previewconflict'                  => 'Овај претпреглед осликава како ће текст у
текстуалном пољу изгледати ако се одлучите да га снимите.',
'session_fail_preview'             => "'''Жао нам је! Нисмо могли да обрадимо вашу измену због губитка података сеансе. Молимо покушајте касније. Ако и даље не ради, покушајте да се одјавите и поново пријавите.'''",
'session_fail_preview_html'        => "'''Жао нам је! Нисмо могли да обрадимо вашу измену због губитка података сесије.'''

''Због тога што {{SITENAME}} има омогућен сиров HTML, претпреглед је сакривен као предострожност против JavaScript напада.''

'''Ако сте покушали да направите праву измену, молимо покушајте поново. 
Ако и даље не ради, покушајте да се [[Special:UserLogout|одјавите]] и поново пријавите.'''",
'token_suffix_mismatch'            => "'''Ваша измена је одбијена зато што је ваш клијент окрњио интерпункцијске знаке на крају токена. Ова измена је одбијена због заштите конзистентности текста стране. Понекад се ово догађа кад се користи баговит прокси сервис.'''",
'editing'                          => 'Уређујете $1',
'editingsection'                   => 'Уређујете $1 (део)',
'editingcomment'                   => 'Уређујете $1 (нову секцију)',
'editconflict'                     => 'Сукобљене измене: $1',
'explainconflict'                  => 'Неко други је променио ову страницу откад сте ви почели да је мењате.
Горње текстуално поље садржи текст странице какав тренутно постоји.
Ваше измене су приказане у доњем тексту.
Мораћете да унесете своје промене у постојећи текст.
<b>Само</b> текст у горњем текстуалном пољу ће бити снимљен када
притиснете "Сними страницу".<br />',
'yourtext'                         => 'Ваш текст',
'storedversion'                    => 'Ускладиштена верзија',
'nonunicodebrowser'                => "'''УПОЗОРЕЊЕ: Ваш браузер не подржава уникод. Молимо промените га пре него што почнете са уређивањем чланка.'''",
'editingold'                       => "'''ПАЖЊА: Ви мењате старију ревизију ове странице.
Ако је снимите, све промене учињене од ове ревизије биће изгубљене.'''",
'yourdiff'                         => 'Разлике',
'copyrightwarning'                 => "Молимо вас да обратите пажњу да се за сваки допринос {{SITENAME}} сматра да је објављен под $2 лиценцом (погледајте $1 за детаље). Ако не желите да се ваше писање мења и редистрибуира без ограничења, онда га немојте слати овде.<br />
Такође нам обећавате да сте га сами написали, или прекопирали из извора који је у јавном власништву или сличног слободног извора.
'''НЕ ШАЉИТЕ РАДОВЕ ЗАШТИЋЕНЕ АУТОРСКИМ ПРАВИМА БЕЗ ДОЗВОЛЕ!'''",
'copyrightwarning2'                => "Напомена: Сви доприноси {{SITENAME}} могу да се мењају или уклоне од стране других корисника. Ако не желите да се ваши доприноси немилосрдно мењају, не шаљите их овде.<br />
Такође нам обећавате да сте ово сами написали или прекопирали из извора у јавном власништву или сличног слободног извора (видите $1 за детаље).
'''НЕ ШАЉИТЕ РАДОВЕ ЗАШТИЋЕНЕ АУТОРСКИМ ПРАВИМА БЕЗ ДОЗВОЛЕ!'''",
'longpagewarning'                  => "'''ПАЖЊА: Ова страница има $1 килобајта; неки браузери имају проблема са уређивањем страна које имају близу или више од 32 килобајта. Молимо вас да размотрите разбијање странице на мање делове.'''",
'longpageerror'                    => "'''ГРЕШКА: Текст који снимате је велик $1 килобајта, што је веће од максимално дозвољене величине која износи $2 килобајта. Немогуће је снимити страницу.'''",
'readonlywarning'                  => "'''ПАЖЊА: База је управо закључана због одржавања, тако да сада нећете моћи да снимите своје измене. 
Можда би било добро да ископирате текст у неки едитор текста и снимите га за касније.'''

Администратор који је закључао базу је дао следеће објашњење: $1",
'protectedpagewarning'             => "'''Напомена: Ова страница је закључана тако да само корисници са администраторским правима могу да је мењају.'''
Историја најскоријих измена је приказана испод:",
'semiprotectedpagewarning'         => "'''Напомена:''' Ова страна је закључана тако да је само регистровани корисници могу уређивати.",
'cascadeprotectedwarning'          => "'''Упозорење:''' Ова страница је заштићена тако да је могу уређивати само корисници са администраторским привилегијама јер је укључена у преносиву заштиту {{PLURAL:$1|следеће стране|следећих страна}}:",
'titleprotectedwarning'            => "'''Напомена: Ова страна је закључана тако да само корисници са [[Special:ListGroupRights|одређеним правима]] могу да је направе.'''",
'templatesused'                    => '{{PLURAL:$1|Шаблон коришћен|Шаблони коришћени}} на овој страни:',
'templatesusedpreview'             => '{{PLURAL:$1|Шаблон коришћен|Шаблони коришћени}} у овом претпрегледу:',
'templatesusedsection'             => '{{PLURAL:$1|Шаблон коришћен|Шаблони коришћени}} у овом одељку:',
'template-protected'               => '(заштићено)',
'template-semiprotected'           => '(полузаштићено)',
'hiddencategories'                 => 'Ова страна је члан {{PLURAL:$1|1 скривене категорије|$1 скривене категорије|$1 скривених категорија}}:',
'edittools'                        => '<!-- Текст одавде ће бити показан испод формулара за уређивање и слање слика. -->',
'nocreatetitle'                    => 'Прављење странице лимитирано',
'nocreatetext'                     => 'На {{SITENAME}} је забрањено прављење нових чланака.
Можете се вратити назад и уређивати постојећи чланак, или [[Special:UserLogin|се пријавите или направите налог]].',
'nocreate-loggedin'                => 'Немате овлашћења да правите нове стране.',
'sectioneditnotsupported-title'    => 'Мењање делова странице није подржано.',
'sectioneditnotsupported-text'     => 'Мењање делова странице није подржано на овој страници.',
'permissionserrors'                => 'Грешке у овлашћењима',
'permissionserrorstext'            => 'Немате овлашћење да урадите то из {{PLURAL:$1|следећег|следећих}} разлога:',
'permissionserrorstext-withaction' => 'Немате дозволу да $2, због следећег: {{PLURAL:$1|разлога|разлога}}:',
'recreate-moveddeleted-warn'       => "'''Упозорење: Поново правите страницу која је претходно обрисана.'''

Требалo би да размотрите да ли је прикладно да наставите са уређивањем ове странице.
Историје брисања и премештања ове стране су приложени испод:",
'moveddeleted-notice'              => 'Ова страна је обрисана.
Историје њеног брисања и премештања се налазе испод, као информација.',
'log-fulllog'                      => 'Види целу историју',
'edit-hook-aborted'                => 'Измена је спречена закаченом функцијом.
Није дато никакво образложење.',
'edit-gone-missing'                => 'Страница није могла бити измењена.
Изгледа да је у међувремену била обрисана.',
'edit-conflict'                    => 'Сукоб измена',
'edit-no-change'                   => 'Ваша измена је игнорисана јер није било никаквих измена у тексту.',
'edit-already-exists'              => 'Неможе се направити нова страница.
Она већ постоји.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Упозорење: Ова страна садржи превише позива функције парсирања.

Требало би да има мање од $2 {{PLURAL:$2|позив|позива}}, а сада {{PLURAL:$1|постоји $1 позив|постоје $1 позива}}.',
'expensive-parserfunction-category'       => 'Стране са превише скупих позива функција парсирања.',
'post-expand-template-inclusion-warning'  => 'Упозорење: Величина укљученог шаблона је превелика. Неки шаблони неће бити укључени.',
'post-expand-template-inclusion-category' => 'Стране на којима је прекорачена величина укључивања шаблона.',
'post-expand-template-argument-warning'   => 'Упозорење: Ова страна садржи бар један превелики аргумент шаблона, који ће бити изостављени.',
'post-expand-template-argument-category'  => 'Стране са изостављеним аргументима шаблона.',
'parser-template-loop-warning'            => 'Откривено је самоукључивање шаблона: [[$1]]',
'parser-template-recursion-depth-warning' => 'Премашена је дозвољена дубина рекурзије за шаблоне ($1)',

# "Undo" feature
'undo-success' => 'Ова измена може да се врати. Проверите разлике испод како би проверили да је ово то што желите да урадите, тада снимите измене како би завршили враћање измене.',
'undo-failure' => 'Измена не може бити опорављена услед сукобљених међуизмена.',
'undo-norev'   => 'Измена не може бити опорављена зато што не постоји или је обрисана.',
'undo-summary' => 'Вратите ревизију $1 корисника [[Special:Contributions/$2|$2]] ([[User talk:$2|разговор]])',

# Account creation failure
'cantcreateaccounttitle' => 'Не може да се направи налог',
'cantcreateaccount-text' => "Прављење налога са ове ИП адресе ('''$1''') је блокирао [[User:$3|$3]].

Разлог који је дао $3 је ''$2''",

# History pages
'viewpagelogs'           => 'Протоколи за ову страну',
'nohistory'              => 'Не постоји историја измена за ову страницу.',
'currentrev'             => 'Тренутна ревизија',
'currentrev-asof'        => 'Тренутна ревизија од $1',
'revisionasof'           => 'Ревизија од $1',
'revision-info'          => 'Ревизија од $1; $2',
'previousrevision'       => '← Претходна ревизија',
'nextrevision'           => 'Следећа ревизија →',
'currentrevisionlink'    => 'Тренутна ревизија',
'cur'                    => 'трен',
'next'                   => 'след',
'last'                   => 'посл',
'page_first'             => 'прво',
'page_last'              => 'последње',
'histlegend'             => 'Одабирање разлика: одаберите кутијице ревизија за упоређивање и притисните ентер или дугме на дну.<br />
Објашњење: (трен) = разлика са тренутном верзијом,
(посл) = разлика са претходном верзијом, М = мала измена',
'history-fieldset-title' => 'Прегледајте историју',
'history-show-deleted'   => 'Само обрисане',
'histfirst'              => 'Најраније',
'histlast'               => 'Последње',
'historysize'            => '({{PLURAL:$1|1 бајт|$1 бајта|$1 бајтова}})',
'historyempty'           => '(празно)',

# Revision feed
'history-feed-title'          => 'Контролна историја',
'history-feed-description'    => 'Историја ревизија за ову страну на викију',
'history-feed-item-nocomment' => '$1, $2',
'history-feed-empty'          => 'Тражена страна не постоји.
Могуће да је обрисана из викија или преименована.
Покушајте [[Special:Search|да претражите вики]] за релевантне нове стране.',

# Revision deletion
'rev-deleted-comment'         => '(коментар уклоњен)',
'rev-deleted-user'            => '(корисничко име уклоњено)',
'rev-deleted-event'           => '(историја уклоњена)',
'rev-deleted-user-contribs'   => '[корисничко име или IP адреса су обрисани - измена је сакривена из списка доприноса]',
'rev-deleted-text-permission' => "Ова ревизија странице је '''обрисана'''.
Детаљи везани за ово брисање би се могли налазити у [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} историји брисања].",
'rev-deleted-text-unhide'     => "Ова ревизија странице је '''обрисана'''.
Детаљи везани за ово брисање би се могли налазити [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} историји брисања].
Пошто сте администратор, такође можете [$1 погледати ову ревизију], уколико желите.",
'rev-deleted-text-view'       => "Ова ревизија странице је '''обрисана'''.
Пошто сте администратор, можете је видети; Детаљи везани за ово брисање би се могли налазити у [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} историји брисања].",
'rev-deleted-no-diff'         => "Не можете видети ову разлику измена зато што је једна од ревизија '''обрисана'''.
Детаљи везани за ово брисање би се могли налазити у [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} историји брисања].",
'rev-suppressed-no-diff'      => "Не можете да видите овај диф зато што је једна од ревизија '''обрисана'''.",
'rev-deleted-unhide-diff'     => "Једна од ревизија за овај диф је '''обрисана'''.
Детаљи везани за ово брисање би се могли налазити у [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} историји брисања].
Пошто сте администратор, ипак можете [$1 видети овај диф], ако желите да наставите.",
'rev-delundel'                => 'покажи/сакриј',
'rev-showdeleted'             => 'покажи',
'revisiondelete'              => 'Обриши/врати ревизије',
'revdelete-nooldid-title'     => 'Нема одабране ревизије',
'revdelete-nooldid-text'      => 'Нисте одабрали жељену ревизију или ревизије како бисте укључили ове функције.',
'revdelete-nologtype-title'   => 'Није дат тип историје',
'revdelete-nologtype-text'    => 'Нисте навели тип историје над којим желите да изведете ову акцију.',
'revdelete-nologid-title'     => 'Неисправан унос у историју',
'revdelete-nologid-text'      => 'Или нисте назначили циљани унос историје, зарад извођења ове функције, или унос који сте навели не постоји.',
'revdelete-no-file'           => 'Тражени фајл не постоји.',
'revdelete-show-file-confirm' => 'Да ли сте сигурни да желите да видите обрисану ревизију фајла "<nowiki>$1</nowiki>" од $2 у $3?',
'revdelete-show-file-submit'  => 'Да',
'revdelete-selected'          => "'''{{PLURAL:$2|Одабрана ревизија|Одабране ревизије}} за '''[[:$1]]''''''",
'logdelete-selected'          => "'''{{PLURAL:$1|Изабрани догађај из историје|Изабрани догађаји из историје}}:'''",
'revdelete-text'              => "'''Обрисане ревизије и догађаји ће још увек бити приказани у историји страна и протокола, али делове њиховог садржаја неће бити јавно доступни.'''
Други администратори на {{SITENAME}} ће још увек имати приступ овом скривеном садржају и моћи ће да га врате преко истог овог интерфејса, осим ако се поставе додатна ограничења.",
'revdelete-suppress-text'     => "Сакривање налога би требало да се користи '''само''' у следећим случајевима:
* Вероватно злонамерну информацију
* Неодговарајуће личне податке
*: ''кућне адресе и телефонске бројеве, бројеве социјалних услуга, итд.''",
'revdelete-legend'            => 'Постави видне рестрикције',
'revdelete-hide-text'         => 'Сакриј текст ревизије',
'revdelete-hide-image'        => 'Сакриј садржај фајла',
'revdelete-hide-name'         => 'Сакриј акцију и циљ.',
'revdelete-hide-comment'      => 'Сакриј опис измене',
'revdelete-hide-user'         => 'Сакриј корисничко име/ИП адресу корисника који је уређивао страницу',
'revdelete-hide-restricted'   => 'Склони податке како од администратора, тако и од свих осталих',
'revdelete-radio-same'        => '(не мењај)',
'revdelete-radio-set'         => 'Да',
'revdelete-radio-unset'       => 'Не',
'revdelete-suppress'          => 'Сакриј податке од сисопа и осталих.',
'revdelete-unsuppress'        => 'Уклони забране над опорављеним верзијама.',
'revdelete-log'               => 'Разлог за брисање:',
'revdelete-submit'            => 'Примени на {{PLURAL:$1|изабрану ревизију|изабране ревизије}}',
'revdelete-logentry'          => 'промењен приказ ревизије за [[$1]]',
'logdelete-logentry'          => 'промењена видност догађаја за страну [[$1]]',
'revdelete-success'           => "'''Видљивост ревизије је успешно подешена.'''",
'revdelete-failure'           => "'''Видљивост ревизије није могла бити ажурирана:'''
$1",
'logdelete-success'           => "'''Видност лога је успешно подешена.'''",
'revdel-restore'              => 'Промена видности',
'pagehist'                    => 'Историја стране',
'deletedhist'                 => 'Обрисана историја',
'revdelete-content'           => 'садржај',
'revdelete-summary'           => 'опис измене',
'revdelete-uname'             => 'корисничко име',
'revdelete-restricted'        => 'ограничења за сисопе су примењена',
'revdelete-unrestricted'      => 'ограничења за сисопе су уклоњена',
'revdelete-hid'               => 'сакривено: $1',
'revdelete-unhid'             => 'откривено: $1',
'revdelete-log-message'       => '$1 за $2 {{PLURAL:$2|ревизију|ревизије|ревизија}}',
'logdelete-log-message'       => '$1 за $2 {{PLURAL:$2|догађај|догађаја}}',
'revdelete-reason-dropdown'   => '*Уобичајени разлози за брисање
** Кршење ауторског права
** Неодговарајуће личне информације
** Потенцијално увредљиве информације',
'revdelete-otherreason'       => 'Други/додатни разлог:',
'revdelete-reasonotherlist'   => 'Други разлог',
'revdelete-edit-reasonlist'   => 'Уреди разлоге за брисање',
'revdelete-offender'          => 'Аутор ревизије:',

# Suppression log
'suppressionlog'     => 'Лог сакривања',
'suppressionlogtext' => 'Испод се налази списак блокова и обрисаних страна који су сакривени од сисопа. Погледај [[Special:IPBlockList|списак блокираних ИП адреса]] за списак тренутно важећих банова и блокова.',

# History merging
'mergehistory'                     => 'Уједини историје страна.',
'mergehistory-header'              => 'Ова страна омогућава спајање верзија једне стране у другу. Уверите се претходно да ће ова измена одржати континуитет историје стране.',
'mergehistory-box'                 => 'Спој верзије две стране:',
'mergehistory-from'                => 'Изворна страница:',
'mergehistory-into'                => 'Жељена страница:',
'mergehistory-list'                => 'Историја измена која се може спојити.',
'mergehistory-merge'               => 'Следеће верзије стране [[:$1]] могу се спојити са [[:$2]]. Користи колону с "радио дугмићима" за спајање само оних верзија које су направљене пре датог времена. Коришћење навигационих линкова ће поништити ову колону.',
'mergehistory-go'                  => 'Прикажи измене које се могу спојити.',
'mergehistory-submit'              => 'Спој измене.',
'mergehistory-empty'               => 'Нема измена које се могу спојити.',
'mergehistory-success'             => '$3 {{PLURAL:$3|ревизија|ревизије|ревизија}} стране [[:$1]] успешно спојено у [[:$2]].',
'mergehistory-fail'                => 'Није могуће спојити верзије; провери параметре стране и времена.',
'mergehistory-no-source'           => 'Изворна страница $1 не постоји.',
'mergehistory-no-destination'      => 'Жељена страница $1 не постоји.',
'mergehistory-invalid-source'      => 'Име изворне странице мора бити исправно.',
'mergehistory-invalid-destination' => 'Име жељене странице мора бити исправно.',
'mergehistory-autocomment'         => 'Спојена страна [[:$1]] у страну [[:$2]].',
'mergehistory-comment'             => 'Спојена страна [[:$1]] у страну [[:$2]]: $3',
'mergehistory-same-destination'    => 'Изворна и циљана страна не могу бити исте',
'mergehistory-reason'              => 'Разлог:',

# Merge log
'mergelog'           => 'Лог спајања',
'pagemerge-logentry' => 'спојена страна [[$1]] у страну [[$2]] (број верзија до: $3)',
'revertmerge'        => 'растављање',
'mergelogpagetext'   => 'Испод се налази списак скорашњих спајања верзија једне стране у другу.',

# Diffs
'history-title'            => 'Историја верзија за "$1"',
'difference'               => '(Разлика између ревизија)',
'lineno'                   => 'Линија $1:',
'compareselectedversions'  => 'Упореди означене верзије',
'showhideselectedversions' => 'Прикажи/сакриј одабране ревизије',
'editundo'                 => 'врати',
'diff-multi'               => '({{PLURAL:$1|Једна ревизија није приказана|$1 ревизије нису приказане|$1 ревизија није приказано}}.)',

# Search results
'searchresults'                    => 'Резултати претраге',
'searchresults-title'              => 'Резултати претраге за „$1”',
'searchresulttext'                 => 'За више информација о претраживању {{SITENAME}}, погледајте [[{{MediaWiki:Helppage}}|Претраживање {{SITENAME}}]].',
'searchsubtitle'                   => 'Тражили сте \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|све странице које почињу са "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|све странице које повезују на "$1"]])',
'searchsubtitleinvalid'            => "Тражили сте '''$1'''",
'toomanymatches'                   => 'Превише погодака је врећно. Измените упит.',
'titlematches'                     => 'Наслов странице одговара',
'notitlematches'                   => 'Ниједан наслов странице не одговара',
'textmatches'                      => 'Текст странице одговара',
'notextmatches'                    => 'Ниједан текст странице не одговара',
'prevn'                            => 'претходних {{PLURAL:$1|$1}}',
'nextn'                            => 'следећих {{PLURAL:$1|$1}}',
'prevn-title'                      => '{{PLURAL:$1|Претходни $1 резултат|Претходних $1 резултата}}',
'nextn-title'                      => '{{PLURAL:$1|Следећи $1 резултат|Следећих $1 резултата}}',
'shown-title'                      => 'Прикажи $1 {{PLURAL:$1|резултат|резултата}} по страни',
'viewprevnext'                     => 'Погледај ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => 'Опције претраге',
'searchmenu-exists'                => "'''Већ постоји чланак под именом \"[[:\$1]]\" на овом Викију'''",
'searchmenu-new'                   => "'''Направи чланак \"[[:\$1]]\" на овом Викију!'''",
'searchhelp-url'                   => 'Help:Садржај',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Претражи стране са овим префиксом]]',
'searchprofile-articles'           => 'Странице са садржајем',
'searchprofile-project'            => 'Стране помоћи и пројекта',
'searchprofile-images'             => 'Мултимедија',
'searchprofile-everything'         => 'Све',
'searchprofile-advanced'           => 'Напредна',
'searchprofile-articles-tooltip'   => 'Тражи у $1',
'searchprofile-project-tooltip'    => 'Тражи у $1',
'searchprofile-images-tooltip'     => 'Претражуј фајлове',
'searchprofile-everything-tooltip' => 'Претражи сав садржај (укључујући стране за разговор)',
'searchprofile-advanced-tooltip'   => 'Претражи у сопственим именским просторима',
'search-result-size'               => '$1 ({{PLURAL:$2|1 реч|$2 речи}})',
'search-result-score'              => 'Релевантност: $1%',
'search-redirect'                  => '(преусмерење $1)',
'search-section'                   => '(наслов $1)',
'search-suggest'                   => 'Да ли сте мислили: $1',
'search-interwiki-caption'         => 'Братски пројекти',
'search-interwiki-default'         => '$1 резултати:',
'search-interwiki-more'            => '(више)',
'search-mwsuggest-enabled'         => 'са сугестијама',
'search-mwsuggest-disabled'        => 'без сугестија',
'search-relatedarticle'            => 'Сродно',
'mwsuggest-disable'                => 'Искључи АЈАКС сугестије',
'searcheverything-enable'          => 'Тражи у свим именским просторима',
'searchrelated'                    => 'сродно',
'searchall'                        => 'све',
'showingresults'                   => "Приказујем испод до {{PLURAL:$1|'''1''' резултат|'''$1''' резултата}} почев од #'''$2'''.",
'showingresultsnum'                => "Приказујем испод до {{PLURAL:$3|'''1''' резултат|'''$3''' резултата}} почев од #'''$2'''.",
'nonefound'                        => "'''Напомена''': Само неколико именских простора се претражују по основном подешавању.
Покушајте са префиксом '''све:''' да претражите цео садржај (укључујући странице за разговор, шаблоне итд.), или изаберите жељени именски простор као префикс.",
'search-nonefound'                 => 'Није било резултата који одговарају упиту.',
'powersearch'                      => 'Тражи',
'powersearch-legend'               => 'Напредна претрага',
'powersearch-ns'                   => 'Тражи у именским просторима:',
'powersearch-redir'                => 'Списак преусмерења',
'powersearch-field'                => 'Претражи за',
'powersearch-togglelabel'          => 'Одабери:',
'powersearch-toggleall'            => 'Све',
'powersearch-togglenone'           => 'Ништа',
'search-external'                  => 'Спољашња претрага',
'searchdisabled'                   => '<p>Извињавамо се! Пуна претрага текста је привремено онемогућена, због бржег рада {{SITENAME}}. У међувремену, можете користити Гугл претрагу испод, која може бити застарела.</p>',

# Quickbar
'qbsettings'               => 'Брза палета',
'qbsettings-none'          => 'Никаква',
'qbsettings-fixedleft'     => 'Причвршћена лево',
'qbsettings-fixedright'    => 'Причвршћена десно',
'qbsettings-floatingleft'  => 'Плутајућа лево',
'qbsettings-floatingright' => 'Плутајућа десно',

# Preferences page
'preferences'                 => 'Подешавања',
'mypreferences'               => 'Моја подешавања',
'prefs-edits'                 => 'Број измена:',
'prefsnologin'                => 'Нисте пријављени',
'prefsnologintext'            => 'Морате бити <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} пријављени]</span> да бисте подешавали корисничка подешавања.',
'changepassword'              => 'Промени лозинку',
'prefs-skin'                  => 'Кожа',
'skin-preview'                => 'Преглед',
'prefs-math'                  => 'Математике',
'datedefault'                 => 'Није битно',
'prefs-datetime'              => 'Датум и време',
'prefs-personal'              => 'Корисничка подешавања',
'prefs-rc'                    => 'Скорашње измене',
'prefs-watchlist'             => 'Списак надгледања',
'prefs-watchlist-days'        => 'Број дана који треба да се види на списку надгледања:',
'prefs-watchlist-days-max'    => '(максимум 7 дана)',
'prefs-watchlist-edits'       => 'Број измена који треба да се види на проширеном списку надгледања:',
'prefs-watchlist-edits-max'   => '(максималан број: 1000)',
'prefs-watchlist-token'       => 'Печат списка надгледања:',
'prefs-misc'                  => 'Разно',
'prefs-resetpass'             => 'Промени лозинку',
'prefs-email'                 => 'Опције електронске поште',
'prefs-rendering'             => 'Изглед',
'saveprefs'                   => 'Сачувај',
'resetprefs'                  => 'Очисти измене',
'restoreprefs'                => 'Врати сва подразумевана подешавања',
'prefs-editing'               => 'Величине текстуалног поља',
'prefs-edit-boxsize'          => 'Величина прозора за писање измене.',
'rows'                        => 'Редова',
'columns'                     => 'Колона',
'searchresultshead'           => 'Претрага',
'resultsperpage'              => 'Погодака по страници:',
'contextlines'                => 'Линија по поготку:',
'contextchars'                => 'Карактера контекста по линији:',
'stub-threshold'              => 'Праг за форматирање <a href="#" class="stub">линка као клице</a> (у бајтовима):',
'recentchangesdays'           => 'Број дана за приказ у скорашњим изменема:',
'recentchangesdays-max'       => '(mмаксимум $1 {{PLURAL:$1|дан|дана}})',
'recentchangescount'          => 'Подразумевани број измена, који ће бити приказан:',
'savedprefs'                  => 'Ваша подешавања су сачувана.',
'timezonelegend'              => 'Часовна зона:',
'localtime'                   => 'Локално време:',
'timezoneuseserverdefault'    => 'Користи основна подешавања',
'timezoneuseoffset'           => 'Друго (одреди одступање)',
'timezoneoffset'              => 'Одступање¹:',
'servertime'                  => 'Време на серверу:',
'guesstimezone'               => 'Попуни из браузера',
'timezoneregion-africa'       => 'Африка',
'timezoneregion-america'      => 'Америка',
'timezoneregion-antarctica'   => 'Антарктик',
'timezoneregion-arctic'       => 'Арктик',
'timezoneregion-asia'         => 'Азија',
'timezoneregion-atlantic'     => 'Атлантски океан',
'timezoneregion-australia'    => 'Аустралија',
'timezoneregion-europe'       => 'Европа',
'timezoneregion-indian'       => 'Индијски океан',
'timezoneregion-pacific'      => 'Пацифички океан',
'allowemail'                  => 'Омогући е-пошту од других корисника',
'prefs-searchoptions'         => 'Опције претраге',
'prefs-namespaces'            => 'Именски простори',
'defaultns'                   => 'У супротном, тражи у овим именским просторима:',
'default'                     => 'стандард',
'prefs-files'                 => 'Фајлови',
'prefs-custom-css'            => 'Кориснички CSS',
'prefs-custom-js'             => 'Кориснички JS',
'prefs-emailconfirm-label'    => 'Потврда е-поште:',
'prefs-textboxsize'           => 'Величина прозора за писање измене',
'youremail'                   => 'Адреса ваше е-поште *',
'username'                    => 'Корисничко име:',
'uid'                         => 'Кориснички ИД:',
'prefs-memberingroups'        => 'Члан {{PLURAL:$1|групе|група}}:',
'prefs-registration'          => 'Време регистрације:',
'yourrealname'                => 'Ваше право име *',
'yourlanguage'                => 'Језик:',
'yourvariant'                 => 'Варијанта:',
'yournick'                    => 'Надимак:',
'badsig'                      => 'Грешка у потпису, проверите HTML тагове.',
'badsiglength'                => 'Ваш потпис је предугачак. 
Мора бити краћи од $1 {{PLURAL:$1|карактер|карактера}}.',
'yourgender'                  => 'Пол:',
'gender-unknown'              => 'Неназначен',
'gender-male'                 => 'Мушки',
'gender-female'               => 'Женски',
'prefs-help-gender'           => 'Необавезно: користи се за исправно обраћање софтвера корисницима, зависно од њиховог пола.
Ова информација ће бити јавна.',
'email'                       => 'Е-пошта',
'prefs-help-realname'         => '* Право име (опционо): ако изаберете да дате име, ово ће бити коришћено за приписивање за ваш рад.',
'prefs-help-email'            => 'Е-пошта је опциона, али омогућује осталима да вас контактирају преко ваше корисничке стране или стране разговора са корисником без потребе да одајете свој идентитет.',
'prefs-help-email-required'   => 'Адреса е-поште је потребна.',
'prefs-info'                  => 'Основне информације',
'prefs-i18n'                  => 'Интернационализација',
'prefs-signature'             => 'Потпис',
'prefs-dateformat'            => 'Формат датума',
'prefs-timeoffset'            => 'Временска разлика',
'prefs-advancedediting'       => 'Напредне опције',
'prefs-advancedrc'            => 'Напредне опције',
'prefs-advancedrendering'     => 'Напредне опције',
'prefs-advancedsearchoptions' => 'Напредне опције',
'prefs-advancedwatchlist'     => 'Напредне опције',
'prefs-display'               => 'Опције приказа',
'prefs-diffs'                 => 'Ревизије',

# User rights
'userrights'                   => 'Управљање корисничким правима',
'userrights-lookup-user'       => 'Управљај корисничким групама',
'userrights-user-editname'     => 'Унесите корисничко име:',
'editusergroup'                => 'Мењај групе корисника',
'editinguser'                  => "Мењате корисничка права корисника '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'Промени корисничке групе',
'saveusergroups'               => 'Сачувај корисничке групе',
'userrights-groupsmember'      => 'Члан:',
'userrights-groupsmember-auto' => 'Имплицитни члан од:',
'userrights-groups-help'       => 'Можете контролисати групе у којима се овај корисник налази.
* Штиклирани квадратић означава да се корисник налази у групи.
* Квадратић који није штиклиран означава да се корисник не налази у групи.
* Звездица (*) означава да ви не можете уклонити групу уколико сте је додали, или обратно.',
'userrights-reason'            => 'Разлог:',
'userrights-no-interwiki'      => 'Немате овлашћења да мењате корисничка права на осталим викијима.',
'userrights-nodatabase'        => 'База података $1 не постоји или је локална.',
'userrights-nologin'           => 'Морате се [[Special:UserLogin|пријавити]] са администраторским налогом да додате корисничка права.',
'userrights-notallowed'        => 'Ваш налог нема овлашћења да додаје корисника права.',
'userrights-changeable-col'    => 'Групе које можете мењати',
'userrights-unchangeable-col'  => 'Групе које не можете мењати',

# Groups
'group'               => 'Група:',
'group-user'          => 'Корисници',
'group-autoconfirmed' => 'аутоматски потврђени сарадници',
'group-bot'           => 'Ботови',
'group-sysop'         => 'Администратори',
'group-bureaucrat'    => 'Бирократе',
'group-suppress'      => 'оверсајти',
'group-all'           => '(сви)',

'group-user-member'          => 'Корисник',
'group-autoconfirmed-member' => 'аутоматски потврђен сарадник',
'group-bot-member'           => 'Бот',
'group-sysop-member'         => 'Администратор',
'group-bureaucrat-member'    => 'Бирократа',
'group-suppress-member'      => 'оверсајт',

'grouppage-user'          => '{{ns:project}}:Корисници',
'grouppage-autoconfirmed' => '{{ns:project}}:Аутоматски потврђени сарадници',
'grouppage-bot'           => '{{ns:project}}:Ботови',
'grouppage-sysop'         => '{{ns:project}}:Администратори',
'grouppage-bureaucrat'    => '{{ns:project}}:Бирократе',
'grouppage-suppress'      => '{{ns:project}}:Оверсајт',

# Rights
'right-read'                  => 'Прегледање страница',
'right-edit'                  => 'Уређивање страница',
'right-createpage'            => 'Прављење страница (које нису странице за разговор)',
'right-createtalk'            => 'Прављење страница за разговор',
'right-createaccount'         => 'Прављење нових корисничких налога',
'right-minoredit'             => 'Означавање измена малом',
'right-move'                  => 'Премештање страница',
'right-move-subpages'         => 'Премештање страница са њиховим подстраницама',
'right-move-rootuserpages'    => 'Премештање базних корисничких страна',
'right-movefile'              => 'Премести фајлове',
'right-suppressredirect'      => 'Нестварање преусмерења од старог имена по преименовању стране.',
'right-upload'                => 'Слање фајлова',
'right-reupload'              => 'Преснимавање постојећег фајла',
'right-reupload-own'          => 'Преснимавање сопственог постојећег фајла',
'right-reupload-shared'       => 'локално преписивање фајлова на дељеном складишту медија',
'right-upload_by_url'         => 'слање фајла са URL адресе',
'right-purge'                 => 'чишчење кеша сајта за страну без потврде',
'right-autoconfirmed'         => 'мењање полузаштићених страна',
'right-bot'                   => 'сарадник је, заправо, аутоматски процес (бот)',
'right-nominornewtalk'        => 'непоседовање малих измена на странама за разговор окида промпт за нову поруку',
'right-apihighlimits'         => 'коришћење виших лимита за упите из API-ја',
'right-writeapi'              => 'писање API-ја',
'right-delete'                => 'Брисање страница',
'right-bigdelete'             => 'Брисање страница са великом историјом',
'right-deleterevision'        => 'брисање и враћање посебних верзија страна',
'right-deletedhistory'        => 'гледање обрисаних верзија страна без текста који је везан за њих',
'right-browsearchive'         => 'Претраживање обрисаних страница',
'right-undelete'              => 'Враћање обрисане странице',
'right-suppressrevision'      => 'прегледање и враћање верзија сакривених за сисопе',
'right-suppressionlog'        => 'преглед приватних логова',
'right-block'                 => 'забрана мењења страна другим сарадницима',
'right-blockemail'            => 'забрана слања имејла сарадницима',
'right-hideuser'              => 'забрана сарадничког имена скривањем од јавности',
'right-ipblock-exempt'        => 'пролазак ИП блокова, аутоматских блокова и блокова опсега',
'right-proxyunbannable'       => 'пролазак аутоматских блокова проксија',
'right-protect'               => 'промена степена заштите и измена заштићених страна',
'right-editprotected'         => 'измена заштићених страна (без могућности измене степена заштите)',
'right-editinterface'         => 'Уреди кориснички интерфејс',
'right-editusercssjs'         => 'мењање туђих CSS и JS фајлова',
'right-editusercss'           => 'мењање туђих CSS фајлова',
'right-edituserjs'            => 'мењање туђих JS фајлова',
'right-rollback'              => 'брзо враћање измена последњег сарадника који је мењао конкретну страну',
'right-markbotedits'          => 'означавање враћених страна као измена које је направио бот',
'right-noratelimit'           => 'не бити погођен лимитима',
'right-import'                => 'увожење страна с других викија',
'right-importupload'          => 'увожење страна из послатог фајла',
'right-patrol'                => 'маркирање туђих измена као патролираних',
'right-autopatrol'            => 'аутоматско маркирање својих измена као патролираних',
'right-patrolmarks'           => 'виђење ознака за патролирање унутар скорашњих измена',
'right-unwatchedpages'        => 'виђење списка ненадгледаних страна',
'right-trackback'             => 'пошаљи извештај',
'right-mergehistory'          => 'спајање историја страна',
'right-userrights'            => 'измена свих права сарадника',
'right-userrights-interwiki'  => 'измена права сарадника на другим викијима',
'right-siteadmin'             => 'закључавање и откључавање базе података',
'right-reset-passwords'       => 'Обнављање лозинки других корисника',
'right-override-export-depth' => 'Извези стране, укључујући повезане стране, до дубине 5',
'right-sendemail'             => 'Пошаљи е-пошту осталим корисницима',

# User rights log
'rightslog'      => 'Историја корисничких права',
'rightslogtext'  => 'Ово је историја измена корисничких права.',
'rightslogentry' => 'је променио права за $1 од $2 на $3',
'rightsnone'     => '(нема)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'читање ове стране',
'action-edit'                 => 'уреди ову страницу',
'action-createpage'           => 'прављење страна',
'action-createtalk'           => 'прављење стране за разговор',
'action-createaccount'        => 'направи налог за овог корисника',
'action-minoredit'            => 'означи ову измену малом',
'action-move'                 => 'премести ову страницу',
'action-move-subpages'        => 'премести ову страну и њене подстране',
'action-move-rootuserpages'   => 'премести базне корисничке стране',
'action-movefile'             => 'премести овај фајл',
'action-upload'               => 'пошаљи овај фајл',
'action-reupload'             => 'поништи овај постојећи фајл',
'action-reupload-shared'      => 'пиши преко верзије овог фајла на дељеном складишту',
'action-upload_by_url'        => 'пошаљи овај фајл са URL адресе',
'action-writeapi'             => 'користи API за писање',
'action-delete'               => 'обриши ову страницу',
'action-deleterevision'       => 'обриши ову ревизију',
'action-deletedhistory'       => 'прегледај обрисану историју ове стране',
'action-browsearchive'        => 'претрага обрисаних страница',
'action-undelete'             => 'врати ову страну',
'action-suppressrevision'     => 'прегледај и врати ову скривену ревизију',
'action-suppressionlog'       => 'прегледај ову приватну историју',
'action-block'                => 'блокирај даље измене овог корисника',
'action-protect'              => 'мењање нивоа заштите за ову страну',
'action-import'               => 'увези ову страну са друге Вики',
'action-importupload'         => 'увези ову страну преко послатог фајла',
'action-patrol'               => 'означавање туђих измена као патролираних',
'action-autopatrol'           => 'аутоматско патролирање сопствених измена',
'action-unwatchedpages'       => 'преглед списка ненадгледаних страна',
'action-mergehistory'         => 'припоји историју ове стране',
'action-userrights'           => 'измени сва корисничка права',
'action-userrights-interwiki' => 'измени права корисника са других Викија',
'action-siteadmin'            => 'закључавање или откључавање базе података',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|измена|измене|измена}}',
'recentchanges'                     => 'Скорашње измене',
'recentchanges-legend'              => 'Подешавања скорашњих измена',
'recentchangestext'                 => 'Пратите најскорије измене на викију на овој страници.',
'recentchanges-feed-description'    => 'Пратите скорашње измене уз помоћ овог фида.',
'recentchanges-label-legend'        => 'Легенда: $1.',
'recentchanges-legend-newpage'      => '$1 - нова страница',
'recentchanges-label-newpage'       => 'Овом изменом је направљена нова страна.',
'recentchanges-legend-minor'        => '$1 - мала измена',
'recentchanges-label-minor'         => 'Ово је мала измена',
'recentchanges-legend-bot'          => '$1 - измена бота',
'recentchanges-label-bot'           => 'Ову измену је направио бот',
'recentchanges-legend-unpatrolled'  => '$1 - непатролирана измена',
'recentchanges-label-unpatrolled'   => 'Ова измена још увек није патролирана',
'rcnote'                            => "Испод {{PLURAL:$1|је '''1''' промена|су последње '''$1''' промене|су последњих '''$1''' промена}} у {{PLURAL:$2|последњем дану|последњa '''$2''' дана|последњих '''$2''' дана}}, од $5, $4.",
'rcnotefrom'                        => 'Испод су промене од <b>$2</b> (до <b>$1</b> приказано).',
'rclistfrom'                        => 'Покажи нове промене почев од $1',
'rcshowhideminor'                   => '$1 мале измене',
'rcshowhidebots'                    => '$1 ботове',
'rcshowhideliu'                     => '$1 пријављене кориснике',
'rcshowhideanons'                   => '$1 анонимне кориснике',
'rcshowhidepatr'                    => '$1 означене измене',
'rcshowhidemine'                    => '$1 сопствене измене',
'rclinks'                           => 'Покажи последњих $1 промена у последњих $2 дана<br />$3',
'diff'                              => 'разл',
'hist'                              => 'ист',
'hide'                              => 'сакриј',
'show'                              => 'покажи',
'minoreditletter'                   => 'м',
'newpageletter'                     => 'Н',
'boteditletter'                     => 'б',
'number_of_watching_users_pageview' => '[$1 надгледа {{PLURAL:$1|корисник|корисника}}]',
'rc_categories'                     => 'Ограничи на категорије (раздвоји са "|")',
'rc_categories_any'                 => 'Било који',
'newsectionsummary'                 => '/* $1 */ нова секција',
'rc-enhanced-expand'                => 'Прикажи детаље (захтева JavaScript)',
'rc-enhanced-hide'                  => 'Сакриј детаље',

# Recent changes linked
'recentchangeslinked'          => 'Сродне промене',
'recentchangeslinked-feed'     => 'Сродне промене',
'recentchangeslinked-toolbox'  => 'Сродне промене',
'recentchangeslinked-title'    => 'Сродне промене за "$1"',
'recentchangeslinked-noresult' => 'Нема измена на повезаним страницама за одабрани период.',
'recentchangeslinked-summary'  => "Ова посебна страница показује списак поселењих промена на страницама које су повезане (или чланови одређене категорије). 
Странице са [[Special:Watchlist|вашег списка надгледања]] су '''подебљане'''.",
'recentchangeslinked-page'     => 'Име странице:',
'recentchangeslinked-to'       => 'приказивање измена према странама повезаних са датом страном',

# Upload
'upload'                      => 'Пошаљи фајл',
'uploadbtn'                   => 'Пошаљи фајл',
'reuploaddesc'                => 'Врати се на упитник за слање.',
'upload-tryagain'             => 'Пошаљи измењени опис фајла',
'uploadnologin'               => 'Нисте пријављени',
'uploadnologintext'           => 'Морате бити [[Special:UserLogin|пријављени]]
да бисте слали фајлове.',
'upload_directory_missing'    => 'Директоријум за прихват фајлова ($1) недостаје, а веб сервер га не може направити.',
'upload_directory_read_only'  => 'На директоријум за слање ($1) сервер не може да пише.',
'uploaderror'                 => 'Грешка при слању',
'uploadtext'                  => "Користите формулар доле да бисте послали фајлове.
Да бисте видели или тражили претходно послате фајлове идите на [[Special:FileList|списак послатих фајлова]], поновна слања су записани у [[Special:Log/upload|историји слања]], а брисања у [[Special:Log/delete|историји брисања]].

Слику додајете у погодне чланке користећи синтаксу:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Слика.jpg]]</nowiki></tt>''' да бисте користили пуну верзију фајла
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Слика.png|200п|мини|лево|опис]]</nowiki></tt>''' да висте користили 200 пиксела широку уоквирену слику са леве стране и са \"опис\" као описом слике.
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Фајл.ogg]]</nowiki></tt>''' да директно повежете ка фајлу без приказивања истог",
'upload-permitted'            => 'Дозвољени типови фајлова су: $1.',
'upload-preferred'            => 'Пожељни типови фајлова су: $1.',
'upload-prohibited'           => 'Забрањени типови фајлова су: $1.',
'uploadlog'                   => 'историја слања',
'uploadlogpage'               => 'историја слања',
'uploadlogpagetext'           => 'Испод је списак најскоријих слања.',
'filename'                    => 'Име фајла',
'filedesc'                    => 'Опис',
'fileuploadsummary'           => 'Опис:',
'filereuploadsummary'         => 'Измене фајла:',
'filestatus'                  => 'Статус ауторског права:',
'filesource'                  => 'Извор:',
'uploadedfiles'               => 'Послати фајлови',
'ignorewarning'               => 'Игнориши упозорење и сними датотеку',
'ignorewarnings'              => 'Игнориши сва упозорења',
'minlength1'                  => 'Имена фајлова морају имати најмање један карактер.',
'illegalfilename'             => 'Фајл "$1" садржи карактере који нису дозвољени у називима страница. Молимо Вас промените име фајла и поново га пошаљите.',
'badfilename'                 => 'Име слике је промењено у "$1".',
'filetype-mime-mismatch'      => 'Екстензија фајла не одговара MIME типу.',
'filetype-badmime'            => 'Није дозвољено слати фајлове MIME типа "$1".',
'filetype-bad-ie-mime'        => 'Овај фајл не може бити послат зато што би Интернет Експлорер могао да га детектује "$1", што је онемогућен и потенцијално опасан тип фајла.',
'filetype-unwanted-type'      => "'''\".\$1\"''' није пожељан тип фајла. 
Пожељни {{PLURAL:\$3|тип фајла је|типови фајлова су}} \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' је забрањен тип фајла. 
Пожељни {{PLURAL:\$3|тип фајла је|типови фајлова су}} \$2.",
'filetype-missing'            => 'Овај фајл нема екстензију (нпр ".jpg").',
'large-file'                  => 'Препоручљиво је да фајлови не буду већи од $1; овај фајл је $2.',
'largefileserver'             => 'Овај фајл је већи него што је подешено да сервер дозволи.',
'emptyfile'                   => 'Фајл који сте послали делује да је празан. Ово је могуће због грешке у имену фајла. Молимо проверите да ли стварно желите да пошаљете овај фајл.',
'fileexists'                  => "Фајл са овим именом већ постоји.
Молимо проверите '''<tt>[[:$1]]</tt>''' ако нисте сигурни да ли желите да га промените.
[[$1|thumb]]",
'filepageexists'              => "Страна описа овог фајла је направљена као '''<tt>[[:$1]]</tt>''', иако сам фајл не постоји.
Опис кога уносите се дакле неће појавити на страни описа.
Да бисте учинили да се Ваш опис ипак појави, требало би да га измените ручно.
[[$1|преглед]]",
'fileexists-extension'        => "Фајл са сличним именом већ постоји: [[$2|thumb]]
* Име фајла који шаљете: '''<tt>[[:$1]]</tt>'''
* Име постојећег фајла: '''<tt>[[:$2]]</tt>'''
Молимо изаберите друго име.",
'fileexists-thumbnail-yes'    => "Овај фајл је највероватније умањена верзија слике. [[$1|thumb]]
Молимо вас проверите фајл '''<tt>[[:$1]]</tt>'''.
Уколико је дати фајл иста слика или оригинална слика, није потребно да шаљете додатно умањену верзију исте.",
'file-thumbnail-no'           => "Фајл почиње са '''<tt>$1</tt>'''. 
Претпоставља се да је ово умањена верзија слике.
Уколико имате ову слику у пуној резолицуји, пошаљите је, а уколико немате, промените име фајла.",
'fileexists-forbidden'        => 'Фајл са овим именом већ постоји, и преко њега се не може писати.
Ако ипак желите да пошаљете Ваш фајл, молимо Вас да се вратите назад и употребите друго име. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Фајл са овим именом већ постоји у заједничкој остави. 
Молимо вратите се и пошаљите овај фајл под новим именом. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Овај фајл је дупликат {{PLURAL:$1|следећег фајла|следеђих фајлова}}:',
'file-deleted-duplicate'      => 'Фајл идентичан овом ([[$1]]) је већ био обрисан.
Требало би да проверите историју брисања фајла пре поновног слања.',
'successfulupload'            => 'Успешно слање',
'uploadwarning'               => 'Упозорење при слању',
'uploadwarning-text'          => 'Молимо Вас да измените опис фајла испод и покушате поново.',
'savefile'                    => 'Сними фајл',
'uploadedimage'               => 'послао "[[$1]]"',
'overwroteimage'              => 'послата нова верзија "[[$1]]"',
'uploaddisabled'              => 'Слање фајлова је искључено.',
'uploaddisabledtext'          => 'Слања фајлова су онемогућена.',
'php-uploaddisabledtext'      => 'Слање фајлова је онемогућено у самом PHP-у.
Молимо, проверите подешавања file_uploads.',
'uploadscripted'              => 'Овај фајл садржи ХТМЛ или код скрипте које интернет браузер може погрешно да интерпретира.',
'uploadvirus'                 => 'Фајл садржи вирус! Детаљи: $1',
'upload-source'               => 'Изворни фајл',
'sourcefilename'              => 'Име фајла извора:',
'sourceurl'                   => 'Изворна адреса:',
'destfilename'                => 'Циљано име фајла:',
'upload-maxfilesize'          => 'Максимална величина фајла: $1',
'upload-description'          => 'Опис фајла',
'upload-options'              => 'Опције слања',
'watchthisupload'             => 'Надгледај овај фајл',
'filewasdeleted'              => 'Фајл са овим именом је раније послат, а касније обрисан. Требало би да проверите $1 пре него што наставите са поновним слањем.',
'upload-wasdeleted'           => "'''Паћња: Шаљете фајл који је претходно обрисан.'''

Проверите да ли сте сигурно да желите послати овај фајл.
Разлог брисања овог фајла раније је:",
'filename-bad-prefix'         => "Име овог фајла почиње са '''\"\$1\"''', што није описно име, најчешће је назван аутоматски са дигиталним фотоапаратом. Молимо изаберите описније име за ваш фајл.",

'upload-proto-error'        => 'Некоректни протокол',
'upload-proto-error-text'   => 'Слање екстерних фајлова захтева УРЛове који почињу са <code>http://</code> или <code>ftp://</code>.',
'upload-file-error'         => 'Интерна грешка',
'upload-file-error-text'    => 'Дошло је до интерне грешке при покушају отварања привременог фајла на серверу.
Контактирајте [[Special:ListUsers/sysop|администратора]].',
'upload-misc-error'         => 'Непозната грешка при слању фајла',
'upload-misc-error-text'    => 'Непозната грешка при слању фајла. Проверите да ли је УРЛ исправан и покушајте поново. Ако проблем остане, контактирајте систем администратора.',
'upload-too-many-redirects' => 'URL је садржао превише преусмерења',
'upload-unknown-size'       => 'Непозната величина',
'upload-http-error'         => 'Дошло је до HTTP грешке: $1',

# img_auth script messages
'img-auth-accessdenied' => 'Приступ онемогућен',
'img-auth-nofile'       => 'Фајл "$1" не постоји.',

# HTTP errors
'http-invalid-url'      => 'Неисправан URL: $1',
'http-request-error'    => 'HTTP захтев није прошао због непознате грешке.',
'http-read-error'       => 'HTTP грешка при читању.',
'http-timed-out'        => 'HTTP захтев је прекорачио време за испуњење.',
'http-curl-error'       => 'Грешка при отварању URL: $1',
'http-host-unreachable' => 'URL је био недоступан.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'УРЛ није доступан',
'upload-curl-error6-text'  => 'УРЛ који сте унели није доступан. Урадите дупли клик на УРЛ да проверите да ли је адреса доступна.',
'upload-curl-error28'      => 'Тајмаут грешка',
'upload-curl-error28-text' => 'Сајту је требало превише времена да одговори. Проверите да ли сајт ради, или сачекајте мало и покушајте поново.',

'license'            => 'Лиценца:',
'license-header'     => 'Лиценца:',
'nolicense'          => 'Нема',
'license-nopreview'  => '(приказ није доступан)',
'upload_source_url'  => '  (исправан, јавно доступан URL)',
'upload_source_file' => ' (фајл на вашем рачунару)',

# Special:ListFiles
'listfiles-summary'     => 'Ова посебна страна приказује све послате фајлове. Подразумева се да је последњи послат фајл приказан на врху списка. Кликом на заглавље колоне мења се принцип сортирања.',
'listfiles_search_for'  => 'Тражи име слике:',
'imgfile'               => 'фајл',
'listfiles'             => 'Списак слика',
'listfiles_date'        => 'Датум',
'listfiles_name'        => 'Име',
'listfiles_user'        => 'Корисник',
'listfiles_size'        => 'Величина (бајтови)',
'listfiles_description' => 'Опис слике',
'listfiles_count'       => 'Верзије',

# File description page
'file-anchor-link'          => 'Слика',
'filehist'                  => 'Историја фајла',
'filehist-help'             => 'Кликните на датум/време да видите верзију фајла из тог времена.',
'filehist-deleteall'        => 'обриши све',
'filehist-deleteone'        => 'обриши',
'filehist-revert'           => 'врати',
'filehist-current'          => 'тренутно',
'filehist-datetime'         => 'Датум/Време',
'filehist-thumb'            => 'Умањени приказ',
'filehist-thumbtext'        => 'Умањени приказ за верзију од $1',
'filehist-nothumb'          => 'Без прегледа слика',
'filehist-user'             => 'Корисник',
'filehist-dimensions'       => 'Димензије',
'filehist-filesize'         => 'Величина фајла',
'filehist-comment'          => 'Коментар',
'filehist-missing'          => 'Нема фајла',
'imagelinks'                => 'Везе ка фајлу',
'linkstoimage'              => '{{PLURAL:$1|Следећа страница користи|$1 Следеће странице користе}} овај фајл:',
'linkstoimage-more'         => 'Више од $1 {{PLURAL:$1|странице се веше|страница се вежу}} за овај фајл.
Следећи списак показује странице које се вежу за овај фајл
[[Special:WhatLinksHere/$2|Потпуни списак]] је доступан такође.',
'nolinkstoimage'            => 'Нема страница које користе овај фајл.',
'morelinkstoimage'          => 'Види [[Special:WhatLinksHere/$1|више веза]] према овом фајлу.',
'redirectstofile'           => 'Следећи {{PLURAL:$1|фајл се преусмерава|$1 фајла се преусмеравају|$1 фајлова се преусмерава}} на овај фајл:',
'duplicatesoffile'          => 'Следећи {{PLURAL:$1|фајл је дупликат|$1 фајла су дупликати|$1 фајлова су дупликати}} овог фајла ([[Special:FileDuplicateSearch/$2|више детаља]]):',
'sharedupload'              => 'Овај фајл је са $1, и може се користити на другим пројектима.',
'filepage-nofile'           => 'Не постоји фајл под тим именом.',
'filepage-nofile-link'      => 'Не постоји фајл са овим именом, али га можете [$1 послати].',
'uploadnewversion-linktext' => 'Пошаљите новију верзију ове датотеке',
'shared-repo-from'          => 'од $1',

# File reversion
'filerevert'                => 'Врати $1',
'filerevert-legend'         => 'Врати фајл',
'filerevert-intro'          => "Враћате '''[[Media:$1|$1]]''' на [$4 верзију од $3, $2].",
'filerevert-comment'        => 'Разлог:',
'filerevert-defaultcomment' => 'Враћено на верзију од $2, $1',
'filerevert-submit'         => 'Врати',
'filerevert-success'        => "'''[[Media:$1|$1]]''' је враћен на [$4 верзију од $3, $2].",
'filerevert-badversion'     => 'Не постоји претходна локална верзија фајла са унесеним временом.',

# File deletion
'filedelete'                  => 'Обриши $1',
'filedelete-legend'           => 'Обриши фајл',
'filedelete-intro'            => "На путу сте да обришете фајл '''[[Media:$1|$1]]''' заједно са његовом историјом.",
'filedelete-intro-old'        => "Бришете верзију фајла '''[[Media:$1|$1]]''' од [$4 $3, $2].",
'filedelete-comment'          => 'Коментар:',
'filedelete-submit'           => 'Обриши',
'filedelete-success'          => "'''$1''' је обрисан.",
'filedelete-success-old'      => "Верзија фајла '''[[Media:$1|$1]]''' од $3, $2 је обрисана.",
'filedelete-nofile'           => "'''$1''' не постоји.",
'filedelete-nofile-old'       => "Не постоји складиштена верзија фајла '''$1''' са датим особинама.",
'filedelete-otherreason'      => 'Други/додатни разлог:',
'filedelete-reason-otherlist' => 'Други разлог',
'filedelete-reason-dropdown'  => '*Најчешћи разлози брисања
** Кршење ауторских права
** Дупликат',
'filedelete-edit-reasonlist'  => 'Уреди разлоге за брисање',
'filedelete-maintenance'      => 'Брисање и враћање фајлова је темпорално онемогућено због одржавања.',

# MIME search
'mimesearch'         => 'МИМЕ претрага',
'mimesearch-summary' => 'Ова страна омогућава филтерисање фајлова за свој MIME-тип. Улаз: contenttype/subtype, тј. <tt>image/jpeg</tt>.',
'mimetype'           => 'МИМЕ тип:',
'download'           => 'Преузми',

# Unwatched pages
'unwatchedpages' => 'Ненадгледане стране',

# List redirects
'listredirects' => 'Списак преусмерења',

# Unused templates
'unusedtemplates'     => 'Неискоришћени шаблони',
'unusedtemplatestext' => 'Ова страна наводи све странице у {{ns:template}} именском простору које нису укључене ни на једној другој страни.
Не заборавите да проверите остале повезнице ка шаблонима пре него што их обришете.',
'unusedtemplateswlh'  => 'остале повезнице',

# Random page
'randompage'         => 'Случајна страница',
'randompage-nopages' => 'Нема страна у {{PLURAL:$2|следећем именском простору|следећим именским просторима}}: $1.',

# Random redirect
'randomredirect'         => 'Случајно преусмерење',
'randomredirect-nopages' => 'Нема преусмерења у именском простору „$1”.',

# Statistics
'statistics'                   => 'Статистике',
'statistics-header-pages'      => 'Статистике стране',
'statistics-header-edits'      => 'Статистике измена',
'statistics-header-views'      => 'Види статистике',
'statistics-header-users'      => 'Статистике корисника',
'statistics-header-hooks'      => 'Друге статистике',
'statistics-articles'          => 'Стране са садржајем',
'statistics-pages'             => 'Странице',
'statistics-pages-desc'        => 'Све стране на овој Вики, укључујући стране за разговор, преусмерења, итд.',
'statistics-files'             => 'Послати фајлови',
'statistics-edits'             => 'Број измена страна од кад {{SITENAME}} постоји',
'statistics-edits-average'     => 'Просечан број измена по страни',
'statistics-views-total'       => 'Укупан број прегледа',
'statistics-views-peredit'     => 'Прегледи по измени',
'statistics-users'             => 'Регистровани [[Special:ListUsers|корисници]]',
'statistics-users-active'      => 'Активни корисници',
'statistics-users-active-desc' => 'Корисници који су извршили макар једну акцију током {{PLURAL:$1|задњег дана|$1 задњих дана}}',
'statistics-mostpopular'       => 'Најпосећеније странице',

'disambiguations'      => 'Странице за вишезначне одреднице',
'disambiguationspage'  => 'Template:Вишезначна одредница',
'disambiguations-text' => "Следеће стране имају везе ка '''вишезначним одредницама'''. Потребно је да упућују на одговарајући чланак.

Страна се сматра вишезначном одредницом ако користи шаблон који је упућен са стране [[MediaWiki:Disambiguationspage]].",

'doubleredirects'            => 'Двострука преусмерења',
'doubleredirectstext'        => 'Ова страна показује списак страна које преусмеравају на друге стране преусмерења.
Сваки ред садржи везе према првом и другом редиректу, као и циљану страну другог редиректа, која је обично „прави“ чланак, на кога прво преусмерење треба да показује.
<s>Прецртани уноси</s> су већ решени.',
'double-redirect-fixed-move' => '[[$1]] је премештен, сада је преусмерење на [[$2]]',
'double-redirect-fixer'      => 'Поправљач преусмерења',

'brokenredirects'        => 'Покварена преусмерења',
'brokenredirectstext'    => 'Следећа преусмерења повезују на непостојеће стране:',
'brokenredirects-edit'   => 'уреди',
'brokenredirects-delete' => 'обриши',

'withoutinterwiki'         => 'Странице без интервики веза',
'withoutinterwiki-summary' => 'Следеће странице не вежу ка другим језицима (међувики):',
'withoutinterwiki-legend'  => 'префикс',
'withoutinterwiki-submit'  => 'Прикажи',

'fewestrevisions' => 'Странице са најмање ревизија',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|бајт|бајтова}}',
'ncategories'             => '$1 {{PLURAL:$1|категорија|категорије}}',
'nlinks'                  => '$1 {{PLURAL:$1|веза|везе}}',
'nmembers'                => '$1 {{PLURAL:$1|члан|члана|чланова}}',
'nrevisions'              => '$1 {{PLURAL:$1|ревизија|ревизије}}',
'nviews'                  => '$1 {{PLURAL:$1|преглед|прегледа}}',
'specialpage-empty'       => 'Нема резултата за овај извештај.',
'lonelypages'             => 'Сирочићи',
'lonelypagestext'         => 'Следеће странице нису повезане са других страница на овом викију.',
'uncategorizedpages'      => 'Странице без категорије',
'uncategorizedcategories' => 'Категорије без категорија',
'uncategorizedimages'     => 'Фајлови без категорија',
'uncategorizedtemplates'  => 'Шаблони без категорија',
'unusedcategories'        => 'Неискоришћене категорије',
'unusedimages'            => 'Неискоришћени фајлови',
'popularpages'            => 'Популарне странице',
'wantedcategories'        => 'Тражене категорије',
'wantedpages'             => 'Тражене странице',
'wantedpages-badtitle'    => 'Неиспаван наслов у низу резултата: $1',
'wantedfiles'             => 'Тражени фајлови',
'wantedtemplates'         => 'Тражени шаблони',
'mostlinked'              => 'Највише повезане стране',
'mostlinkedcategories'    => 'Чланци са највише категорија',
'mostlinkedtemplates'     => 'Најповезанији шаблони',
'mostcategories'          => 'Чланци са највише категорија',
'mostimages'              => 'Највише повезани фајлови',
'mostrevisions'           => 'Чланци са највише ревизија',
'prefixindex'             => 'Све странице са префиксима',
'shortpages'              => 'Кратке странице',
'longpages'               => 'Дугачке странице',
'deadendpages'            => 'Странице без интерних веза',
'deadendpagestext'        => 'Следеће странице не вежу на друге странице на овом викију.',
'protectedpages'          => 'Заштићене странице',
'protectedpages-indef'    => 'само неограничене заштите',
'protectedpages-cascade'  => 'Само преносиве заштите',
'protectedpagestext'      => 'Следеће странице су заштићене од премештања или уређивања',
'protectedpagesempty'     => 'Нема заштићених страница са овим параметрима.',
'protectedtitles'         => 'Заштићени наслови',
'protectedtitlestext'     => 'Следећи наслови су заштићени од стварања:',
'protectedtitlesempty'    => 'Нема наслова који су тренутно заштићени помоћу ових параметара.',
'listusers'               => 'Списак корисника',
'listusers-editsonly'     => 'Прикажи кориснике који имају измене',
'listusers-creationsort'  => 'Сортирај по датуму прављења',
'usereditcount'           => '$1 {{PLURAL:$1|измена|измена}}',
'usercreated'             => 'Направљено $1, у $2',
'newpages'                => 'Нове странице',
'newpages-username'       => 'Корисничко име:',
'ancientpages'            => 'Најстарији чланци',
'move'                    => 'премести',
'movethispage'            => 'премести ову страницу',
'unusedimagestext'        => 'Следећи фајлови постоје, али нису угнеждени ни у једну страницу.
Обратите пажњу да се други веб сајтови могу повезивати на слику директним УРЛ-ом, и тако могу још увек бити приказани овде упркос чињеници да више нису у активној употреби.',
'unusedcategoriestext'    => 'Наредне стране категорија постоје иако их ни један други чланак или категорија не користе.',
'notargettitle'           => 'Нема циља',
'notargettext'            => 'Нисте навели циљну страницу или корисника
на коме би се извела ова функција.',
'nopagetitle'             => 'Не постоји таква страна',
'nopagetext'              => 'Циљана страна не постоји.',
'pager-newer-n'           => '{{PLURAL:$1|новији 1|новија $1|новијих $1}}',
'pager-older-n'           => '{{PLURAL:$1|старији 1|старија $1|старијих $1}}',
'suppress'                => 'оверсајт',

# Book sources
'booksources'               => 'Штампани извори',
'booksources-search-legend' => 'Претражите изворе књига',
'booksources-go'            => 'Иди',
'booksources-text'          => 'Испод се налази списак линкова ка сајтовима који се баве продајом нових и коришћених књига, а који би могли садржати додатне информације о књигама за које се интересујете:',
'booksources-invalid-isbn'  => 'Наведен ISBN не изгледа исправно; проверите да није дошло до грешке приликом копирања из оригиналног извора.',

# Special:Log
'specialloguserlabel'  => 'Корисник:',
'speciallogtitlelabel' => 'Наслов:',
'log'                  => 'Протоколи',
'all-logs-page'        => 'Све јавне историје',
'alllogstext'          => 'Комбиновани приказ свих доступних историја за {{SITENAME}}.
Можете сузити преглед одабиром типа историје, корисничког имена или тражене странице.',
'logempty'             => 'Протокол је празан.',
'log-title-wildcard'   => 'Тражи наслове који почињу са овим текстом',

# Special:AllPages
'allpages'          => 'Све странице',
'alphaindexline'    => '$1 у $2',
'nextpage'          => 'Следећа страна ($1)',
'prevpage'          => 'Претходна страна ($1)',
'allpagesfrom'      => 'Прикажи странице почетно са:',
'allpagesto'        => 'Приказује странице које се завршавају са:',
'allarticles'       => 'Сви чланци',
'allinnamespace'    => 'Све странице ($1 именски простор)',
'allnotinnamespace' => 'Све странице (које нису у $1 именском простору)',
'allpagesprev'      => 'Претходна',
'allpagesnext'      => 'Следећа',
'allpagessubmit'    => 'Иди',
'allpagesprefix'    => 'Прикажи стране са префиксом:',
'allpagesbadtitle'  => 'Дати назив странице није добар или садржи међујезички или интервики префикс. Могуће је да садржи карактере који не могу да се користе у називима.',
'allpages-bad-ns'   => '{{SITENAME}} нема именски простор "$1".',

# Special:Categories
'categories'                    => 'Категоријe',
'categoriespagetext'            => '{{PLURAL:$1|Следећа категорија садржи|Следеће категорије садрже}} стране или фајлове.
[[Special:UnusedCategories|Некоришћене категорије]] нису приказане овде.
Такође погледајте [[Special:WantedCategories|тражене категорије]].',
'categoriesfrom'                => 'Прикажи категорије на:',
'special-categories-sort-count' => 'сортирај по броју',
'special-categories-sort-abc'   => 'сортирај азбучно',

# Special:DeletedContributions
'deletedcontributions'             => 'Обрисане измене',
'deletedcontributions-title'       => 'Обрисане измене',
'sp-deletedcontributions-contribs' => 'доприноси',

# Special:LinkSearch
'linksearch'       => 'Веб линкови',
'linksearch-pat'   => 'Образац претраге:',
'linksearch-ns'    => 'Именски простор:',
'linksearch-ok'    => 'Претрага',
'linksearch-text'  => 'Џокери попут „*.wikipedia.org“ могу бити коришћени.<br />
Подржани протоколи: <tt>$1</tt>',
'linksearch-line'  => 'страна $1 је повезана са стране $2',
'linksearch-error' => 'Џокери се могу појавити само на почетку имена хоста.',

# Special:ListUsers
'listusersfrom'      => 'Прикажи кориснике почевши од:',
'listusers-submit'   => 'Прикажи',
'listusers-noresult' => 'Није пронађен корисник.',
'listusers-blocked'  => '(блокиран)',

# Special:ActiveUsers
'activeusers'            => 'Списак активних корисника',
'activeusers-from'       => 'Прикажи кориснике почевши од:',
'activeusers-hidebots'   => 'Сакриј ботове',
'activeusers-hidesysops' => 'Сакриј администраторе',

# Special:Log/newusers
'newuserlogpage'              => 'историја креирања корисника',
'newuserlogpagetext'          => 'Ово је историја скорашњих креација корисника',
'newuserlog-byemail'          => 'лозинка послата имејлом',
'newuserlog-create-entry'     => 'Нови корисник',
'newuserlog-create2-entry'    => 'направио нови налог за $1',
'newuserlog-autocreate-entry' => 'налог аутоматски направљен',

# Special:ListGroupRights
'listgrouprights'                      => 'права сарадничких група',
'listgrouprights-summary'              => 'Следи списак корисничких група дефинисаних на овом Викију, са њиховим придруженим правима приступа.
Могле би Вас интересовати [[{{MediaWiki:Listgrouprights-helppage}}|додатне информације]] о појединачним правима приступа.',
'listgrouprights-group'                => 'Група',
'listgrouprights-rights'               => 'Права',
'listgrouprights-helppage'             => 'Help:Права',
'listgrouprights-members'              => '(списак чланова)',
'listgrouprights-addgroup'             => 'Ноже да дода {{PLURAL:$2|следећу групу|следеће групе}}: $1',
'listgrouprights-removegroup'          => 'Може да обриже {{PLURAL:$2|следећу групу|следеће групе}}: $1',
'listgrouprights-addgroup-all'         => 'Може да дода све групе',
'listgrouprights-removegroup-all'      => 'Може да обрише све групе',
'listgrouprights-addgroup-self-all'    => 'Додај све групе у свој налог',
'listgrouprights-removegroup-self-all' => 'Уклони све групе са свог налога',

# E-mail user
'mailnologin'      => 'Нема адресе за слање',
'mailnologintext'  => 'Морате бити [[Special:UserLogin|пријављени]] и имати исправну адресу е-поште у вашим [[Special:Preferences|подешавањима]]
да бисте слали е-пошту другим корисницима.',
'emailuser'        => 'Пошаљи е-пошту овом кориснику',
'emailpage'        => 'Пошаљи е-писмо кориснику',
'emailpagetext'    => 'Можете користити овај формулар да пошаљете е-пошту овом кориснику.
Адреса е-поште коју сте ви унели у својим [[Special:Preferences|корисничким подешавањима]] ће се појавити као "From" адреса поруке, тако да ће прималац моћи директно да Вам одговори.',
'usermailererror'  => 'Објекат поште је вратио грешку:',
'defemailsubject'  => '{{SITENAME}} е-пошта',
'noemailtitle'     => 'Нема адресе е-поште',
'noemailtext'      => 'Овај корисник није навео исправну адресу е-поште.',
'nowikiemailtitle' => 'Није омогућено слање мејлова',
'nowikiemailtext'  => 'Овај корисник је онемогућио слање имејлова од других корисника.',
'email-legend'     => 'Пошаљите мејл другом кориснику на {{SITENAME}}',
'emailfrom'        => 'Од:',
'emailto'          => 'За:',
'emailsubject'     => 'Наслов:',
'emailmessage'     => 'Порука:',
'emailsend'        => 'Пошаљи',
'emailccme'        => 'Пошаљи ми копију моје поруке у моје сандуче е-поште.',
'emailccsubject'   => 'Копија ваше поруке на $1: $2',
'emailsent'        => 'Порука послата',
'emailsenttext'    => 'Ваша порука је послата електронском поштом.',
'emailuserfooter'  => 'Овај имејл посла $1 сараднику $2 помоћу "Пошаљи имејл" функције на сајту "{{SITENAME}}".',

# Watchlist
'watchlist'            => 'Мој списак надгледања',
'mywatchlist'          => 'Мој списак надгледања',
'watchlistfor'         => "(за '''$1''')",
'nowatchlist'          => 'Немате ништа на свом списку надгледања.',
'watchlistanontext'    => 'Молимо $1 да бисте гледали или мењали ставке на вашем списку надгледања.',
'watchnologin'         => 'Нисте пријављени',
'watchnologintext'     => 'Морате бити [[Special:UserLogin|пријављени]] да бисте мењали списак надгледања.',
'addedwatch'           => 'Додато списку надгледања',
'addedwatchtext'       => 'Страница "[[:$1]]" је додата вашем [[Special:Watchlist|списку надгледања]].
Будуће промене ове странице и њој придружене странице за разговор ће бити наведене овде, и страница ће бити <b>подебљана</b> у [[Special:RecentChanges|списку]] скорашњих измена да би се лакше уочила.

Ако касније желите да уклоните страницу са вашег списка надгледања, кликните на "прекини надгледање" на бочној палети.',
'removedwatch'         => 'Уклоњено са списка надгледања',
'removedwatchtext'     => 'Страна "[[:$1]]" је обрисана са [[Special:Watchlist|Вашег списка надгледања]].',
'watch'                => 'надгледај',
'watchthispage'        => 'Надгледај ову страницу',
'unwatch'              => 'Прекини надгледање',
'unwatchthispage'      => 'Прекини надгледање',
'notanarticle'         => 'Није чланак',
'notvisiblerev'        => 'Ревизија је обрисана',
'watchnochange'        => 'Ништа што надгледате није промењено у приказаном времену.',
'watchlist-details'    => '{{PLURAL:$1|$1 страна|$1 стране|$1 страна}} на вашем списку надгледања, не рачунајући странице за разговор.',
'wlheader-enotif'      => '* Обавештавање е-поштом је омогућено.',
'wlheader-showupdated' => "* Странице које су измењене од када сте их последњи пут посетили су приказане '''подебљано'''",
'watchmethod-recent'   => 'проверавам има ли надгледаних страница у скорашњим изменама',
'watchmethod-list'     => 'проверавам има ли скорашњих измена у надгледаним страницама',
'watchlistcontains'    => 'Ваш списак надгледања садржи $1 {{PLURAL:$1|страну|стране|страна}}.',
'iteminvalidname'      => "Проблем са ставком '$1', неисправно име...",
'wlnote'               => "Испод {{PLURAL:$1|је последња измена|су последње '''$1''' измене|последњих '''$1''' измена}} у {{PLURAL:$2|последњем сату|последња '''$2''' сата|последњих '''$2''' сати}}.",
'wlshowlast'           => 'Прикажи последњих $1 сати $2 дана $3',
'watchlist-options'    => 'Подешавања списка надгледања',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Надгледам...',
'unwatching' => 'Уклањање надгледања...',

'enotif_mailer'                => '{{SITENAME}} пошта обавештења',
'enotif_reset'                 => 'Означи све стране као посећене',
'enotif_newpagetext'           => 'Ово је нови чланак.',
'enotif_impersonal_salutation' => '{{SITENAME}} корисник',
'changed'                      => 'промењена',
'created'                      => 'направљена',
'enotif_subject'               => '{{SITENAME}} страница $PAGETITLE је била $CHANGEDORCREATED од стране $PAGEEDITOR',
'enotif_lastvisited'           => 'Погледајте $1 за све промене од ваше последње посете.',
'enotif_lastdiff'              => 'Погледајте $1 да видите ову измену.',
'enotif_anon_editor'           => 'анонимни корисник $1',
'enotif_body'                  => 'Драги $WATCHINGUSERNAME,


Страна $PAGETITLE на {{SITENAME}} је била $CHANGEDORCREATED дана $PAGEEDITDATE од стране $PAGEEDITOR,
погледајте $PAGETITLE_URL за тренутну верзију.

$NEWPAGE

Резиме уредника: $PAGESUMMARY $PAGEMINOREDIT

Контактирајте уредника:
пошта $PAGEEDITOR_EMAIL
вики $PAGEEDITOR_WIKI

Неће бити других обавештења у случају даљих промена уколико не посетите ову страну.
Такође можете да ресетујете заставице за обавештења за све ваше надгледане стране на вашем списку надгледања.

             Срдачно, {{SITENAME}} систем обавештавања

--
Да бисте променили подешавања везана за списак надгледања, посетите
{{fullurl:{{#special:Watchlist}}/edit}}

Да бисте уклонили ову страну са Вашег списка надгледања, посетите
$UNWATCHURL

Подршка и даља помоћ:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Обриши страницу',
'confirm'                => 'Потврди',
'excontent'              => "садржај је био: '$1'",
'excontentauthor'        => "садржај је био: '$1' (а једину измену је направио '$2')",
'exbeforeblank'          => "садржај пре брисања је био: '$1'",
'exblank'                => 'страница је била празна',
'delete-confirm'         => 'Обриши „$1“',
'delete-legend'          => 'Обриши',
'historywarning'         => "'''Упозорење:''' Страна коју желите да обришете има историју са приближно $1 {{PLURAL:$1|ревизијом|ревизија}}:",
'confirmdeletetext'      => 'На путу сте да трајно обришете страницу
или слику заједно са њеном историјом из базе података.
Молимо вас потврдите да намеравате да урадите ово, да разумете
последице, и да ово радите у складу са
[[{{MediaWiki:Policy-url}}|правилима]] {{SITENAME}}.',
'actioncomplete'         => 'Акција завршена',
'actionfailed'           => 'Акција неуспела',
'deletedtext'            => 'Чланак "<nowiki>$1</nowiki>" је обрисан.
Погледајте $2 за запис о скорашњим брисањима.',
'deletedarticle'         => 'обрисан "[[$1]]"',
'suppressedarticle'      => 'сактивено: "[[$1]]"',
'dellogpage'             => 'историја брисања',
'dellogpagetext'         => 'Испод је списак најскоријих брисања.',
'deletionlog'            => 'историја брисања',
'reverted'               => 'Враћено на ранију ревизију',
'deletecomment'          => 'Разлог за брисање',
'deleteotherreason'      => 'Други/додатни разлог:',
'deletereasonotherlist'  => 'Други разлог',
'deletereason-dropdown'  => '*Најчешћи разлози брисања
** Захтев аутора
** Кршење ауторских права
** Вандализам',
'delete-edit-reasonlist' => 'Уреди разлоге за брисање',
'delete-toobig'          => 'Ова страница има велику историју странице, преко $1 {{PLURAL:$1|ревизије|ревизије|ревизија}}.
Брисање таквих страница је забрањено ради превентиве од случајног оштећења сајта.',
'delete-warning-toobig'  => 'Ова страна има велику историју измена, преко $1 {{PLURAL:$1|измене|измена}}.
Њено брисање би могло да омете операције анд базом {{SITENAME}};
продужите опрезно.',

# Rollback
'rollback'         => 'Врати измене',
'rollback_short'   => 'Врати',
'rollbacklink'     => 'врати',
'rollbackfailed'   => 'Враћање није успело',
'cantrollback'     => 'Не могу да вратим измену; последњи аутор је уједно и једини.',
'alreadyrolled'    => 'Не могу да вратим последњу измену [[:$1]] од корисника [[User:$2|$2]] ([[User talk:$2|разговор]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]); неко други је већ изменио или вратио чланак.

Последња измена од корисника [[User:$3|$3]] ([[User talk:$3|разговор]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'      => "Коментар измене је: \"''\$1''\".",
'revertpage'       => 'Враћене измене корисника [[Special:Contributions/$2|$2]] ([[User talk:$2|Разговор]]) на последњу измену корисника [[User:$1|$1]]',
'rollback-success' => 'Враћене измене од стране $1; на последњу измену од стране $2.',

# Edit tokens
'sessionfailure' => 'Изгледа да постоји проблем са вашом сеансом пријаве;
ова акција је прекинута као предострожност против преотимања сеанси.
Молимо кликните "back" и поново учитајте страну одакле сте дошли, а онда покушајте поново.',

# Protect
'protectlogpage'              => 'историја закључавања',
'protectlogtext'              => 'Испод је списак заштићених страница.<br />
Погледајте [[Special:ProtectedPages|правила о заштити страница]] за више информација.',
'protectedarticle'            => 'заштитио $1',
'modifiedarticleprotection'   => 'промењен ниво заштите за „[[$1]]“',
'unprotectedarticle'          => 'скинуо заштиту са $1',
'movedarticleprotection'      => 'преместио подешавања заштите са "[[$2]]" на "[[$1]]"',
'protect-title'               => 'стављање заштите "$1"',
'prot_1movedto2'              => 'је променио име чланку [[$1]] у [[$2]]',
'protect-legend'              => 'Потврдите заштиту',
'protectcomment'              => 'Разлог:',
'protectexpiry'               => 'Истиче:',
'protect_expiry_invalid'      => 'Време истека није одговарајуће.',
'protect_expiry_old'          => 'Време истека је у прошлости.',
'protect-text'                => "Овде можете погледати и мењати ниво заштите за страницу '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "Не можете мењати нивое заштите док сте блокирани.
Ово су тренутна подешавања за страницу '''$1''':",
'protect-locked-dblock'       => "Нивои заштите не могу бити промењени због активног закључавања базе.
Ово су тренутна подешавања за страну '''$1''':",
'protect-locked-access'       => "Ваш налог нема дозволе за измену нивоа заштите странице.
Ово су тренутна подешавања за страницу '''$1''':",
'protect-cascadeon'           => 'Ова страница је тренутно заштићена јер се налази на {{PLURAL:$1|страници, која је заштићена|стране, које су заштићене|страна, које су заштићене}} са опцијом "преносиво". Можете изменити степен заштите ове странице, али он неће утицати на преносиву заштиту.',
'protect-default'             => 'Дозволи све кориснике',
'protect-fallback'            => 'Захтева "$1" овлашћења',
'protect-level-autoconfirmed' => 'Блокирај нове и нерегистроване кориснике',
'protect-level-sysop'         => 'Само администратори',
'protect-summary-cascade'     => 'преносива заштита',
'protect-expiring'            => 'истиче $1 (UTC)',
'protect-expiry-indefinite'   => 'бесконачно',
'protect-cascade'             => 'Заштићене странице укључене у ову страницу (преносива заштита)
Protect pages included in this page (cascading protection)',
'protect-cantedit'            => 'Не можете мењати нивое заштите за ову страницу, због тога што немате овлашћења да је уређујете.',
'protect-othertime'           => 'Друго време:',
'protect-othertime-op'        => 'друго време',
'protect-existing-expiry'     => 'Тренутно време истека: $3, $2',
'protect-otherreason'         => 'Други/додатни разлог:',
'protect-otherreason-op'      => 'Други разлог',
'protect-dropdown'            => '*Разлози заштите
** Вандализам
** Нежењене поруке
** Контра-продуктивне измене
** Страница са великим бројем посета',
'protect-edit-reasonlist'     => 'Измените разлоге заштите',
'protect-expiry-options'      => '1 сат:1 hour,1 дан:1 day,1 недеља:1 week,2 недеље:2 weeks,1 месец:1 month,3 месеца:3 months,6 месеци:6 months,1 година:1 year,бесконачно:infinite',
'restriction-type'            => 'Овлашћење:',
'restriction-level'           => 'Ниво заштите:',
'minimum-size'                => 'Мин величина',
'maximum-size'                => 'Макс величина:',
'pagesize'                    => '(бајта)',

# Restrictions (nouns)
'restriction-edit'   => 'Уреди',
'restriction-move'   => 'Премештање',
'restriction-create' => 'Направи',
'restriction-upload' => 'Пошаљи фајл',

# Restriction levels
'restriction-level-sysop'         => 'пуна заштита',
'restriction-level-autoconfirmed' => 'полу-заштита',
'restriction-level-all'           => 'било који ниво',

# Undelete
'undelete'                     => 'Погледај обрисане странице',
'undeletepage'                 => 'Погледај и врати обрисане странице',
'undeletepagetitle'            => "'''Следеће садржи обрисане измене чланка: [[:$1|$1]]'''.",
'viewdeletedpage'              => 'Погледај обрисане стране',
'undeletepagetext'             => '{{PLURAL:$1|Следећа страна је обрисана али је|Следеће $1 стране су обрисане али су|Следећих $1 страна је обрисано али су}} још увек у архиви и
могу бити враћене. 
Архива може бити периодично чишћена.',
'undelete-fieldset-title'      => 'враћање верзија',
'undeleteextrahelp'            => "Да бисте вратили историју целе стране, оставите све кућице неоткаченим и кликните на '''''Врати'''''. 
Да извршите селективно враћање, откачите кућице које одговарају ревизији која треба да се врати и кликните на '''''Врати'''''. 
Кликом на '''''Поништи''''' ћете обрисати поље за коментар и све кућице.",
'undeleterevisions'            => '$1 {{PLURAL:$1|ревизија|ревизије|ревизија}} архивирано',
'undeletehistory'              => 'Ако вратите страницу, све ревизије ће бити враћене њеној историји.
Ако је нова страница истог имена направљена од брисања, враћене ревизије ће се појавити у ранијој историји.',
'undeleterevdel'               => 'Враћање неће бити изведену уколико би резултовало делимичним брисањем ревизије фајла или врха странице.
У оваквим случајевима морате скинути ознаку са или поново приказати најновију обрисану ревизију.',
'undeletehistorynoadmin'       => 'Ова страна је обрисана.
Испод се налази део историје брисања и историја ревизија обрисане стране.
Питајте администратора ако желите да се страница врати.',
'undelete-revision'            => 'Обрисана ревизија од $1 (у време $4, на $5) од стране корисника $3:',
'undeleterevision-missing'     => 'Некоректна или непостојећа ревизија. Можда је ваш линк погрешан, или је ревизија рестаурирана, или обрисана из архиве.',
'undelete-nodiff'              => 'Нема претходних измена.',
'undeletebtn'                  => 'Врати',
'undeletelink'                 => 'погледај/врати',
'undeleteviewlink'             => 'погледај',
'undeletereset'                => 'Поништи',
'undeleteinvert'               => 'Инвертујте избор',
'undeletecomment'              => 'Разлог:',
'undeletedarticle'             => 'вратио "[[$1]]"',
'undeletedrevisions'           => '{{PLURAL:$1|1 ревизија враћена|$1 ревизије врећене|$1 ревизија враћено}}',
'undeletedrevisions-files'     => '$1 {{PLURAL:$1|ревизија|ревизије|ревизија}} и $2 {{PLURAL:$2|фајл|фајла|фајлова}} враћено',
'undeletedfiles'               => '$1 {{PLURAL:$1|фајл|фајла|фајлова}} {{PLURAL:$1|враћен|враћена|враћено}}',
'cannotundelete'               => 'Враћање обрисане верзије није успело; неко други је вратио страницу пре вас.',
'undeletedpage'                => "'''$1 је враћен'''

Прегледај [[Special:Log/delete|историју брисања]] за информацију о скорашњим брисањима и враћањима.",
'undelete-header'              => 'Види [[Special:Log/delete|лог брисања]] за скоро обрисане стране.',
'undelete-search-box'          => 'Претражи обрисане странице',
'undelete-search-prefix'       => 'Прикажи стране које почињу са:',
'undelete-search-submit'       => 'Претрага',
'undelete-no-results'          => 'Нема таквих страна у складишту обрисаних.',
'undelete-filename-mismatch'   => 'Није могуће обрисати верзију фајла од времена $1: име фајла се не поклапа.',
'undelete-bad-store-key'       => 'Није могуће вратити измену верзије фајла времена $1: фајл је недеостајао пре брисања.',
'undelete-cleanup-error'       => 'Грешка приликом брисања некоришћеног фајла из архиве "$1".',
'undelete-missing-filearchive' => 'Није могуће вратити архиву фајлова ID $1 зато што није у бази.
Можда је већ била враћена.',
'undelete-error-short'         => 'Грешка при враћању фајла: $1',
'undelete-error-long'          => 'Десила се грешка при враћању фајла:

$1',
'undelete-show-file-confirm'   => 'Да ли сте сигурни да желите да видите обрисану ревизију фајла "<nowiki>$1</nowiki>" од $2 на $3?',
'undelete-show-file-submit'    => 'Да',

# Namespace form on various pages
'namespace'      => 'Именски простор:',
'invert'         => 'Обрни селекцију',
'blanknamespace' => '(Главно)',

# Contributions
'contributions'       => 'Прилози корисника',
'contributions-title' => 'Прилози корисника за $1',
'mycontris'           => 'Моји прилози',
'contribsub2'         => 'За $1 ($2)',
'nocontribs'          => 'Нису нађене промене које задовољавају ове услове.',
'uctop'               => ' (врх)',
'month'               => 'За месец (и раније):',
'year'                => 'Од године (и раније):',

'sp-contributions-newbies'        => 'Прикажи само прилоге нових налога',
'sp-contributions-newbies-sub'    => 'За новајлије',
'sp-contributions-newbies-title'  => 'Доприноси корисника са новим налозима',
'sp-contributions-blocklog'       => 'Историја блокирања',
'sp-contributions-deleted'        => 'обрисане измене корисника',
'sp-contributions-logs'           => 'историје',
'sp-contributions-talk'           => 'разговор',
'sp-contributions-userrights'     => 'подешавање корисничких права',
'sp-contributions-blocked-notice' => 'Овај корисник је тренутно блокиран.
Последњи унос у дневник блокирања је понуђен испод као референца:',
'sp-contributions-search'         => 'Претрага прилога',
'sp-contributions-username'       => 'ИП адреса или корисничко име:',
'sp-contributions-submit'         => 'Претрага',

# What links here
'whatlinkshere'            => 'Шта је повезано овде',
'whatlinkshere-title'      => 'Странице које су повезане на „$1“',
'whatlinkshere-page'       => 'Страна:',
'linkshere'                => "Следеће странице су повезане на '''[[:$1]]''':",
'nolinkshere'              => "Ни једна страница није повезана на: '''[[:$1]]'''.",
'nolinkshere-ns'           => "Ни једна страница у одабраном именском простору се не веже за '''[[:$1]]'''",
'isredirect'               => 'преусмеривач',
'istemplate'               => 'укључивање',
'isimage'                  => 'линк ка слици',
'whatlinkshere-prev'       => '{{PLURAL:$1|претходни|претходних $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|следећи|следећих $1}}',
'whatlinkshere-links'      => '← везе',
'whatlinkshere-hideredirs' => '$1 преусмерења',
'whatlinkshere-hidetrans'  => '$1 укључења',
'whatlinkshere-hidelinks'  => '$1 везе',
'whatlinkshere-hideimages' => 'број веза према сликама: $1',
'whatlinkshere-filters'    => 'Филтери',

# Block/unblock
'blockip'                         => 'Блокирај корисника',
'blockip-title'                   => 'Блокирај корисника',
'blockip-legend'                  => 'Блокирај корисника',
'blockiptext'                     => 'Употребите доњи упитник да бисте уклонили право писања
са одређене ИП адресе или корисничког имена.
Ово би требало да буде урађено само да би се спречио вандализам, и у складу
са [[{{MediaWiki:Policy-url}}|смерницама]].
Унесите конкретан разлог испод (на пример, наводећи које
странице су вандализоване).',
'ipaddress'                       => 'ИП адреса',
'ipadressorusername'              => 'ИП адреса или корисничко име',
'ipbexpiry'                       => 'Трајање',
'ipbreason'                       => 'Разлог:',
'ipbreasonotherlist'              => 'Други разлог',
'ipbreason-dropdown'              => '*Најчешћи разлози блокирања
** Уношење лажних информација
** Уклањање садржаја са страница
** Постављање веза ка спољашњим сајтовима
** Унос бесмислица у странице
** Непожељно понашање
** Употреба више налога
** Непожељно корисничко име',
'ipbanononly'                     => 'Блокирај само анонимне кориснике',
'ipbcreateaccount'                => 'Спречи прављење налога',
'ipbemailban'                     => 'Забраните кориснику да шаље е-пошту',
'ipbenableautoblock'              => 'Аутоматски блокирај последњу ИП адресу овог корисника, и сваку следећу адресу са које се покуша уређивање.',
'ipbsubmit'                       => 'Блокирај овог корисника',
'ipbother'                        => 'Остало време',
'ipboptions'                      => '2 сата:2 hours,1 дан:1 day,3 дана:3 days,1 недеља:1 week,2 недеље:2 weeks,1 месец:1 month,3 месеца:3 months,6 месеци:6 months,1 година:1 year,бесконачно:infinite',
'ipbotheroption'                  => 'остало',
'ipbotherreason'                  => 'Други/додатни разлог:',
'ipbhidename'                     => 'Сакрој корисничко име са измена и спискова',
'ipbwatchuser'                    => 'надгледање сарадничке стране и стране за разговор овог сарадника',
'ipballowusertalk'                => 'Омогућите овом кориснику да мења сопствену страну за разговор током блока',
'ipb-change-block'                => 'Блокирајте корисника поново са овим подешавањима',
'badipaddress'                    => 'Лоша ИП адреса',
'blockipsuccesssub'               => 'Блокирање је успело',
'blockipsuccesstext'              => '"[[Special:Contributions/$1|$1]]" је блокиран.
<br />Погледајте [[Special:IPBlockList|ИП списак блокираних корисника]] за преглед блокирања.',
'ipb-edit-dropdown'               => 'Мењајте разлоге блока',
'ipb-unblock-addr'                => 'Одблокирај $1',
'ipb-unblock'                     => 'Одблокирај корисничко име или ИП адресу',
'ipb-blocklist-addr'              => 'Постојећи блокови за $1',
'ipb-blocklist'                   => 'Погледајте постојеће блокове',
'ipb-blocklist-contribs'          => 'Доприноси за $1',
'unblockip'                       => 'Одблокирај корисника',
'unblockiptext'                   => 'Употребите доњи упитник да бисте вратили право писања
раније блокираној ИП адреси или корисничком имену.',
'ipusubmit'                       => 'Уклони овај блок',
'unblocked'                       => '[[User:$1|$1]] је деблокиран',
'unblocked-id'                    => 'Блок $1 је уклоњен',
'ipblocklist'                     => 'Блокиране ИП адресе и корисничка имена',
'ipblocklist-legend'              => 'Пронађи блокираног корисника',
'ipblocklist-username'            => 'Корисник или ИП адреса:',
'ipblocklist-sh-userblocks'       => '$1 блокирања налога',
'ipblocklist-sh-tempblocks'       => '$1 привремене блокове',
'ipblocklist-sh-addressblocks'    => '$1 појединачне IP блокове',
'ipblocklist-submit'              => 'Претрага',
'ipblocklist-localblock'          => 'Локални блок',
'ipblocklist-otherblocks'         => 'Други {{PLURAL:$1|блок|блокови}}',
'blocklistline'                   => '$1, $2 блокирао корисника $3, (истиче $4)',
'infiniteblock'                   => 'бесконачан',
'expiringblock'                   => 'Истиче на $1 у $2',
'anononlyblock'                   => 'само анонимни',
'noautoblockblock'                => 'Аутоблокирање је онемогућено',
'createaccountblock'              => 'блокирано прављење налога',
'emailblock'                      => 'е-пошта блокираном',
'blocklist-nousertalk'            => 'не може да измени сопствену страну за разговор',
'ipblocklist-empty'               => 'Списак блокова је празан.',
'ipblocklist-no-results'          => 'Унешена ИП адреса или корисничко име није блокирано.',
'blocklink'                       => 'блокирај',
'unblocklink'                     => 'одблокирај',
'change-blocklink'                => 'промени блок',
'contribslink'                    => 'прилози',
'autoblocker'                     => 'Аутоматски сте блокирани јер делите ИП адресу са "[[User:$1|$1]]". Дати разлог за блокирање корисника $1 је: "\'\'\'$2\'\'\'"',
'blocklogpage'                    => 'историја блокирања',
'blocklog-showlog'                => 'Овај корисник је већ био блокиран.
Дневник блокирања је понуђен испод као референца:',
'blocklogentry'                   => 'је блокирао "[[$1]]" са временом истицања блокаде од $2 $3',
'reblock-logentry'                => 'промењена подешавања блока за [[$1]] са временом истека $2 ($3)',
'blocklogtext'                    => 'Ово је историја блокирања и деблокирања корисника. Аутоматски
блокиране ИП адресе нису исписане овде. Погледајте [[Special:IPBlockList|блокиране ИП адресе]] за списак тренутних забрана и блокирања.',
'unblocklogentry'                 => 'одблокирао "$1"',
'block-log-flags-anononly'        => 'само анонимни корисници',
'block-log-flags-nocreate'        => 'забрањено прављење налога',
'block-log-flags-noautoblock'     => 'искључено аутоматско блокирање',
'block-log-flags-noemail'         => 'блокирано слање е-поште',
'block-log-flags-nousertalk'      => 'не може да измени сопствену страну за разговор',
'block-log-flags-angry-autoblock' => 'омогућен је побољшани аутоблок',
'block-log-flags-hiddenname'      => 'корисничко име сакривено',
'range_block_disabled'            => 'Администраторска могућност да блокира блокове ИП адреса је искључена.',
'ipb_expiry_invalid'              => 'Погрешно време трајања.',
'ipb_expiry_temp'                 => 'Сакривени блокови сарадничких имена морају бити стални.',
'ipb_hide_invalid'                => 'Није било могуће сакрити овај налог; Мора да има превише измена.',
'ipb_already_blocked'             => '"$1" је већ блокиран',
'ipb-needreblock'                 => '== Већ блокиран ==
$1 је већ блокиран. Да ли желите да промените подешавања?',
'ipb-otherblocks-header'          => 'Други {{PLURAL:$1|блок|блокови}}',
'ipb_cant_unblock'                => 'Грешка: ИД блока $1 није нађен. Могуће је да је већ одблокиран.',
'ipb_blocked_as_range'            => 'Грешка: IP $1 није директно блокиран и не може бити одблокиран.
Међутим, блокиран је као део опсега $2, који може бити одблокиран.',
'ip_range_invalid'                => 'Нетачан блок ИП адреса.',
'ip_range_toolarge'               => 'Опсези блокирања шири од /$1 нису дозвољени.',
'blockme'                         => 'Блокирај ме',
'proxyblocker'                    => 'Блокер проксија',
'proxyblocker-disabled'           => 'Ова фукција је искључена.',
'proxyblockreason'                => 'Ваша ИП адреса је блокирана јер је она отворени прокси. Молимо контактирајте вашег Интернет сервис провајдера или техничку подршку и обавестите их о овом озбиљном сигурносном проблему.',
'proxyblocksuccess'               => 'Урађено.',
'sorbsreason'                     => 'Ваша ИП адреса је на списку као отворен прокси на DNSBL.',
'sorbs_create_account_reason'     => 'Ваша ИП адреса се налази на списку као отворени прокси на DNSBL. Не можете да направите налог',
'cant-block-while-blocked'        => 'Не можете да блокирате друге кориснике док сте блокирани.',

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
'lockdbsuccesstext'   => 'База података је закључана.<br />
Сетите се да је [[Special:UnlockDB|откључате]] када завршите са одржавањем.',
'unlockdbsuccesstext' => 'База података је откључана.',
'lockfilenotwritable' => 'По фајлу за закључавање базе података не може да се пише. Да бисте закључали или откључали базу, по овом фајлу мора да буде омогућено писање од стране веб сервера.',
'databasenotlocked'   => 'База података није закључана.',

# Move page
'move-page'                    => 'Премести $1',
'move-page-legend'             => 'Премештање странице',
'movepagetext'                 => "Доњи упитник ће преименовати страницу, премештајући сву њену историју на ново име.
Стари наслов ће постати преусмерење на нови наслов.
Повезнице према старом наслову неће бити промењене, обавезно потражите [[Special:DoubleRedirects|двострука]] или [[Special:BrokenRedirects|покварена]] преусмерења.
На вама је одговорност да везе и даље иду тамо где би и требало да иду.

Обратите пажњу да страница '''неће''' бити померена ако већ постоји страница са новим насловом, осим ако је она празна или преусмерење и нема историју промена.
Ово значи да не можете преименовати страницу на оно име са кога сте је преименовали ако погрешите, и не можете преписати постојећу страницу.

'''ПАЖЊА!'''
Ово може бити драстична и неочекивана промена за популарну страницу;
молимо да будете сигурни да разумете последице овога пре него што наставите.",
'movepagetalktext'             => "Одговарајућа страница за разговор, ако постоји, биће аутоматски премештена истовремено '''осим ако:'''
*Непразна страница за разговор већ постоји под новим именом, или
*Одбележите доњу кућицу.

У тим случајевима, мораћете ручно да преместите или спојите страницу уколико то желите.",
'movearticle'                  => 'Премести страницу',
'movenologin'                  => 'Нисте пријављени',
'movenologintext'              => 'Морате бити регистровани корисник и [[Special:UserLogin|пријављени]]
да бисте преместили страницу.',
'movenotallowed'               => 'Немате облашћења за премештање страница.',
'movenotallowedfile'           => 'Немате потребна права, да бисте премештали фајлове.',
'cant-move-user-page'          => 'Немате права потребна за премештање корисничких страна (искључујући подстране).',
'cant-move-to-user-page'       => 'Немате права потребна за премештање неке стране на место корисничке стране (изузевши корисничке подстране).',
'newtitle'                     => 'Нови наслов',
'move-watch'                   => 'Надгледај ову страницу',
'movepagebtn'                  => 'премести страницу',
'pagemovedsub'                 => 'Премештање успело',
'movepage-moved'               => '\'\'\'Страна "$1" је преименована у "$2"!\'\'\'',
'movepage-moved-redirect'      => 'Преусмерење је напревљено.',
'movepage-moved-noredirect'    => 'Прављење преусмерења је задржано.',
'articleexists'                => 'Страница под тим именом већ постоји, или је
име које сте изабрали неисправно.
Молимо изаберите друго име.',
'cantmove-titleprotected'      => 'Не можете преместити страницу на ову локацију, зато што је нови наслов заштићен за прављење',
'talkexists'                   => "'''Сама страница је успешно премештена, али
страница за разговор није могла бити премештена јер таква већ постоји на новом наслову. Молимо вас да их спојите ручно.'''",
'movedto'                      => 'премештена на',
'movetalk'                     => 'Премести "страницу за разговор" такође, ако је могуће.',
'move-subpages'                => 'Премести подстране (до $1)',
'move-talk-subpages'           => 'Премести подстране стране за разговор (до $1)',
'movepage-page-exists'         => 'Страна $1 већ постоји не може се аутоматски преписати.',
'movepage-page-moved'          => 'Страна $1 је преименована у $2.',
'movepage-page-unmoved'        => 'Страна $1 не може бити преименована у $2.',
'movepage-max-pages'           => 'Максимум од $1 {{PLURAL:$1|стране|страна}} је био премештен, и више од тога неће бити аутоматски премештено.',
'1movedto2'                    => 'је променио име чланку [[$1]] у [[$2]]',
'1movedto2_redir'              => 'је променио име чланку [[$1]] у [[$2]] путем преусмерења',
'move-redirect-suppressed'     => 'преусмерење је задржано',
'movelogpage'                  => 'историја премештања',
'movelogpagetext'              => 'Испод је списак премештања чланака.',
'movesubpage'                  => '{{PLURAL:$1|Подстрана|Подстрана}}',
'movesubpagetext'              => 'Ова страна има $1 {{PLURAL:$1|подстрану приказану|подстрана приказаних}} испод.',
'movenosubpage'                => 'Ова страна нема подстрана.',
'movereason'                   => 'Разлог:',
'revertmove'                   => 'врати',
'delete_and_move'              => 'Обриши и премести',
'delete_and_move_text'         => '==Потребно брисање==

Циљани чланак "[[:$1]]" већ постоји. Да ли желите да га обришете да бисте направили место за премештање?',
'delete_and_move_confirm'      => 'Да, обриши страницу',
'delete_and_move_reason'       => 'Обрисано како би се направило место за премештање',
'selfmove'                     => 'Изворни и циљани назив су исти; страна не може да се премести преко саме себе.',
'immobile-source-namespace'    => 'Стране из именског простора "$1" нису могле бити премештене',
'immobile-target-namespace'    => 'Не може да премести стране у именски простор "$1"',
'immobile-target-namespace-iw' => 'Међувики веза није исправна мета при премештању стране.',
'immobile-source-page'         => 'Ова страна се не може преместити.',
'immobile-target-page'         => 'Не може да се преметси на циљани наслов.',
'imagenocrossnamespace'        => 'Фајл се не може преименовати у именски простор који не припада фајловима.',
'imagetypemismatch'            => 'Нови наставак за фајлове се не поклапа са својим типом.',
'imageinvalidfilename'         => 'Циљано име фајла је погрешно.',
'fix-double-redirects'         => 'Освежава било које преусмерење које веже на оригинални наслов',
'move-leave-redirect'          => 'Остави преусмерење након премештања',
'protectedpagemovewarning'     => "'''Напомена:''' Ова страница је закључана тако да само корисници са администраторским привилегијама могу да је преместе.
Најскорија забелешка историје је приложена испод као додатна информација:",

# Export
'export'            => 'Извези странице',
'exporttext'        => 'Можете извозити текст и историју промена одређене
странице или групе страница у XML формату. Ово онда може бити увезено у други
вики који користи МедијаВики софтвер преко {{ns:special}}:Import странице.

Да бисте извозили странице, унесите називе у текстуалном пољу испод, са једним насловом по реду, и одаберите да ли желите тренутну верзију са свим старим верзијама или само тренутну верзију са информацијама о последњој измени.

У другом случају, можете такође користити везу, нпр. [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] за страницу [[{{MediaWiki:Mainpage}}]].',
'exportcuronly'     => 'Укључи само тренутну ревизију, не целу историју',
'exportnohistory'   => "----
'''Напомена:''' извожење пуне историје страна преко овог формулара је онемогућено због серверских разлога.",
'export-submit'     => 'Извоз',
'export-addcattext' => 'Додај странице из категорије:',
'export-addcat'     => 'Додај',
'export-addnstext'  => 'Додај стране из именског простора:',
'export-addns'      => 'Додај',
'export-download'   => 'Сачувај као фајл',
'export-templates'  => 'Укључује шаблоне',
'export-pagelinks'  => 'Укључи повезане стране до дубине од:',

# Namespace 8 related
'allmessages'                   => 'Системске поруке',
'allmessagesname'               => 'Име',
'allmessagesdefault'            => 'Стандардни текст',
'allmessagescurrent'            => 'Тренутни текст',
'allmessagestext'               => 'Ово је списак системских порука које су у МедијаВики именском простору.
Посетите [http://translatewiki.net translatewiki.net] уколико желите да помогнете у локализацији.',
'allmessagesnotsupportedDB'     => "Ова страница не може бити употребљена зато што је '''\$wgUseDatabaseMessages''' искључен.",
'allmessages-filter-legend'     => 'Филтер',
'allmessages-filter'            => 'Филтрирај по стању прилагођености:',
'allmessages-filter-unmodified' => 'Неизмењене',
'allmessages-filter-all'        => 'Све',
'allmessages-filter-modified'   => 'Измењене',
'allmessages-prefix'            => 'Филтрирај по префиксу:',
'allmessages-language'          => 'Језик:',
'allmessages-filter-submit'     => 'Иди',

# Thumbnails
'thumbnail-more'           => 'увећај',
'filemissing'              => 'Недостаје фајл',
'thumbnail_error'          => 'Грешка при прављењу умањене слике: $1',
'djvu_page_error'          => 'DjVu страна је ван опсега.',
'djvu_no_xml'              => 'Не могу преузети XML за DjVu фајл.',
'thumbnail_invalid_params' => 'Погрешни параметри за малу слику.',
'thumbnail_dest_directory' => 'Не могу направити одредишни директоријум.',
'thumbnail_image-type'     => 'Тип слике није подржан',
'thumbnail_image-missing'  => 'Изгледа да фајл недостаје: $1',

# Special:Import
'import'                     => 'Увоз страница',
'importinterwiki'            => 'Трансвики увожење',
'import-interwiki-text'      => 'Одаберите вики и назив стране за увоз. Датуми ревизије и имена уредника ће бити сачувани. Сви трансвики увози су забележени у [[Special:Log/import|историји увоза]].',
'import-interwiki-source'    => 'Изворни вики/страна:',
'import-interwiki-history'   => 'Копирај све ревизије ове стране',
'import-interwiki-templates' => 'Укључи све шаблоне',
'import-interwiki-submit'    => 'Увези',
'import-interwiki-namespace' => 'Именски простор:',
'import-upload-filename'     => 'Име фајла:',
'import-comment'             => 'Коментар:',
'importtext'                 => 'Молимо извезите фајл из изворног викија користећи [[Special:Export|извоз]].
Сачувајте га код себе и пошаљите овде.',
'importstart'                => 'Увожење страна у току...',
'import-revision-count'      => '$1 {{PLURAL:$1|ревизија|ревизије|ревизија}}',
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
'importuploaderrorsize'      => 'Слање и унос фајла нису успели. Фајл је већи него што је дозвољено.',
'importuploaderrorpartial'   => 'Слање фајла за унос података није успело. Фајл је делимично стигао.',
'importuploaderrortemp'      => 'Слање фајла за унос није успело. Привремени директоријум недостаје.',
'import-parse-failure'       => 'Неуспешно парсирање унесеног XML-а.',
'import-noarticle'           => 'Нема страница за увоз!',
'import-nonewrevisions'      => 'Све верзије су претходно унесене.',
'xml-error-string'           => '$1 на линији $2, колона $3 (бајт $4): $5',
'import-upload'              => 'слање XML података',
'import-token-mismatch'      => 'Губитак података о сесији.
Молимо Вас да опет покушате.',
'import-invalid-interwiki'   => 'Увоз са назначеног Викија не може бити обављен.',

# Import log
'importlogpage'                    => 'историја увоза',
'importlogpagetext'                => 'Административни увози страна са историјама измена са других викија.',
'import-logentry-upload'           => 'увезен $1 преко слања фајла',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|ревизија|ревизије|ревизија}}',
'import-logentry-interwiki'        => 'премештено са другог викија: $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|ревизија|ревизије|ревизија}} од $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Ваша корисничка страница',
'tooltip-pt-anonuserpage'         => 'Корисничка страница ИП адресе са које уређујете',
'tooltip-pt-mytalk'               => 'Ваша страница за разговор',
'tooltip-pt-anontalk'             => 'Разговор о прилозима са ове ИП адресе',
'tooltip-pt-preferences'          => 'Моја корисничка подешавања',
'tooltip-pt-watchlist'            => 'Списак чланака које надгледате',
'tooltip-pt-mycontris'            => 'Списак ваших прилога',
'tooltip-pt-login'                => 'Препоручује се да се пријавите, али није обавезно',
'tooltip-pt-anonlogin'            => 'Препоручује се да се пријавите, али није обавезно',
'tooltip-pt-logout'               => 'Одјави се',
'tooltip-ca-talk'                 => 'Разговор о чланку',
'tooltip-ca-edit'                 => 'Можете уредити ову страницу. Молимо користите претпреглед пре сачувавања.',
'tooltip-ca-addsection'           => 'Почните нову секцију',
'tooltip-ca-viewsource'           => 'Ова страница је закључана. Можете видети њен извор',
'tooltip-ca-history'              => 'Претходне верзије ове странице',
'tooltip-ca-protect'              => 'Заштити ову страницу',
'tooltip-ca-unprotect'            => 'Откључај ову страницу',
'tooltip-ca-delete'               => 'Обриши ову страницу',
'tooltip-ca-undelete'             => 'Враћати измене које су начињене пре брисања странице',
'tooltip-ca-move'                 => 'Премести ову страницу',
'tooltip-ca-watch'                => 'Додајте ову страницу на Ваш списак надгледања',
'tooltip-ca-unwatch'              => 'Уклоните ову страницу са Вашег списка надгледања',
'tooltip-search'                  => 'Претражите {{SITENAME}}',
'tooltip-search-go'               => 'Иди на страну с тачним именом ако постоји.',
'tooltip-search-fulltext'         => 'Претражите стране са овим текстом',
'tooltip-p-logo'                  => 'Главна страна',
'tooltip-n-mainpage'              => 'Посетите главну страну',
'tooltip-n-mainpage-description'  => 'Посетите главну страну',
'tooltip-n-portal'                => 'О пројекту, шта можете да радите и где да пронађете ствари',
'tooltip-n-currentevents'         => 'Сазнајте више о актуелностима',
'tooltip-n-recentchanges'         => 'Списак скорашњих измена на викију',
'tooltip-n-randompage'            => 'Учитавај случајну страницу',
'tooltip-n-help'                  => 'Место где можете да научите нешто',
'tooltip-t-whatlinkshere'         => 'Списак свих страница које везују на ову',
'tooltip-t-recentchangeslinked'   => 'Скорашње измене на чланцима повезаним са ове странице',
'tooltip-feed-rss'                => 'RSS фид за ову страницу',
'tooltip-feed-atom'               => 'Atom фид за ову страницу',
'tooltip-t-contributions'         => 'Погледај списак прилога овог корисника',
'tooltip-t-emailuser'             => 'Пошаљи електронску пошту овом кориснику',
'tooltip-t-upload'                => 'Пошаљи слике и медија фајлове',
'tooltip-t-specialpages'          => 'Списак свих посебних страница',
'tooltip-t-print'                 => 'Верзија за штампање ове стране',
'tooltip-t-permalink'             => 'стални линк ка овој верзији стране',
'tooltip-ca-nstab-main'           => 'Погледајте чланак',
'tooltip-ca-nstab-user'           => 'Погледајте корисничку страницу',
'tooltip-ca-nstab-media'          => 'Погледајте медија страницу',
'tooltip-ca-nstab-special'        => 'Ово је посебна страница, не можете је мењати',
'tooltip-ca-nstab-project'        => 'Преглед странице пројекта',
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
'tooltip-recreate'                => 'Направи поново страницу без обзира да је била обрисана',
'tooltip-upload'                  => 'Почни слање',
'tooltip-rollback'                => '"Врати" враћа последње измене корнисика у једном кораку (клику)',
'tooltip-undo'                    => '"Врати" враћа измену и отвара форму за измене за преглед. Дозвољава додавање разлога у опис измене.',

# Stylesheets
'common.css'   => '/** CSS стављен овде ће се односити на све коже */',
'monobook.css' => '/* CSS стављен овде ће се односити на кориснике Монобук коже */',

# Metadata
'nodublincore'      => 'Dublin Core RDF метаподаци онемогућени за овај сервер.',
'nocreativecommons' => 'Creative Commons RDF метаподаци онемогућени за овај сервер.',
'notacceptable'     => 'Вики сервер не може да пружи податке у оном формату који ваш клијент може да прочита.',

# Attribution
'anonymous'        => 'Анонимни {{PLURAL:$1|корисник|корисници}} на {{SITENAME}}',
'siteuser'         => '{{SITENAME}} корисник $1',
'lastmodifiedatby' => 'Ову страницу је последњи пут променио $3 у $2, $1.',
'othercontribs'    => 'Базирано на раду корисника $1.',
'others'           => 'остали',
'siteusers'        => '{{SITENAME}} {{PLURAL:$2|корисник|корисници}} $1',
'creditspage'      => 'Заслуге за страницу',
'nocredits'        => 'Нису доступне информације о заслугама за ову страницу.',

# Spam protection
'spamprotectiontitle' => 'Филтер за заштиту од нежељених порука',
'spamprotectiontext'  => 'Страна коју желите да сачувате је блокирана од стране филтера за нежељене поруке.
Ово је вероватно изазвано блокираном везом ка спољашњем сајту.',
'spamprotectionmatch' => 'Следећи текст је изазвао наш филтер за нежељене поруке: $1',
'spambot_username'    => 'Чишћење нежељених порука у МедијаВикију',
'spam_reverting'      => 'Враћање на стару ревизију која не садржи повезнице ка $1',
'spam_blanking'       => 'Све ревизије су садржале повезнице ка $1, пражњење',

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

# Math errors
'math_failure'          => 'Неуспех при парсирању',
'math_unknown_error'    => 'непозната грешка',
'math_unknown_function' => 'непозната функција',
'math_lexing_error'     => 'речничка грешка',
'math_syntax_error'     => 'синтаксна грешка',
'math_image_error'      => 'PNG конверзија неуспешна; проверите тачну инсталацију latex-а, dvips-а, gs-а и convert-а',
'math_bad_tmpdir'       => 'Не могу да напишем или направим привремени math директоријум',
'math_bad_output'       => 'Не могу да напишем или направим директоријум за math излаз.',
'math_notexvc'          => 'Недостаје извршно texvc; молимо погледајте math/README да бисте подесили.',

# Patrolling
'markaspatrolleddiff'                 => 'Означи као патролиран',
'markaspatrolledtext'                 => 'Означи овај чланак као патролиран',
'markedaspatrolled'                   => 'Означен као патролиран',
'markedaspatrolledtext'               => 'Изабрана ревизија од [[:$1]] је означена као патролирана.',
'rcpatroldisabled'                    => 'Патрола скорашњих измена онемогућена',
'rcpatroldisabledtext'                => 'Патрола скорашњих измена је тренутно онемогућена.',
'markedaspatrollederror'              => 'Немогуће означити као патролирано',
'markedaspatrollederrortext'          => 'Морате изабрати ревизију да бисте означили као патролирано.',
'markedaspatrollederror-noautopatrol' => 'Није ти дозвољено да обележиш своје измене патролираним.',

# Patrol log
'patrol-log-page'      => 'Историја патролирања',
'patrol-log-header'    => 'Ово је историја патролираних ревизија.',
'patrol-log-line'      => 'обележена верзија $1 стране $2 као патролирана ($3)',
'patrol-log-auto'      => '(аутоматски)',
'patrol-log-diff'      => 'ревизија $1',
'log-show-hide-patrol' => '$1 историја патролирања',

# Image deletion
'deletedrevision'                 => 'Обрисана стара ревизија $1.',
'filedeleteerror-short'           => 'Грешка при брисању фајла: $1',
'filedeleteerror-long'            => 'Појавиле су се грешке приликом брисања фајла:

$1',
'filedelete-missing'              => 'Фајл „$1” се не може обрисати, зато што не постоји.',
'filedelete-old-unregistered'     => 'Дата верзија фајла "$1" не постоји у бази.',
'filedelete-current-unregistered' => 'Дати фајл "$1" не постоји у бази.',
'filedelete-archive-read-only'    => 'Веб сервер не може писати по кладишном директоријуму "$1".',

# Browsing diffs
'previousdiff' => '← Старија измена',
'nextdiff'     => 'Новија измена →',

# Media information
'mediawarning'         => "'''Упозорење''': Овај тип фајла би могао да садржи штетан код.
Његовим извршавањем бисте могли да оштетите Ваш систем.<hr />",
'imagemaxsize'         => "Ограничење величине слике:<br />''(за стране описа фајлова)''",
'thumbsize'            => 'Величина умањеног приказа :',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|страна|стране|страна}}',
'file-info'            => '(величина фајла: $1, MIME тип: $2)',
'file-info-size'       => '($1 × $2 пиксела, величина фајла: $3, MIME тип: $4)',
'file-nohires'         => '<small>Није доступна већа резолуција</small>',
'svg-long-desc'        => '(SVG фајл, номинално $1 × $2 пиксела, величина фајла: $3)',
'show-big-image'       => 'Пуна резолуција',
'show-big-image-thumb' => '<small>Величина овог приказа: $1 × $2 пиксела</small>',

# Special:NewFiles
'newimages'             => 'Галерија нових слика',
'imagelisttext'         => "Испод је списак од '''$1''' {{PLURAL:$1|фајла|фајла|фајлова}} поређаних $2.",
'newimages-summary'     => 'Ова посебна страна приказује последње послате фајлове.',
'newimages-legend'      => 'Филтер',
'newimages-label'       => 'Име фајла (или његов део):',
'showhidebots'          => '($1 ботове)',
'noimages'              => 'Нема ништа да се види',
'ilsubmit'              => 'Тражи',
'bydate'                => 'по датуму',
'sp-newimages-showfrom' => 'Прикажи нове фајлове почевши од $2, $1',

# Bad image list
'bad_image_list' => 'Формат је следећи:

Разматрају се само ставке у списку (линије које почињу са *). 
Прва веза у линији мора бити веза на високо ризичну слику. 
Све друге везе у истој линији се сматрају изузецима тј. чланци у којима се слика може приказати.',

# Variants for Serbian language
'variantname-sr-ec' => 'ћирилица',
'variantname-sr-el' => 'latinica',
'variantname-sr'    => 'disable',

# Metadata
'metadata'          => 'Метаподаци',
'metadata-help'     => 'Овај фајл садржи додатне информације, које су вероватно додали дигитални фотоапарат или скенер који су коришћени да би се направила слика. 
Ако је првобитно стање фајла промењено, могуће је да неки детаљи не описују у потпуности измењен фајл.',
'metadata-expand'   => 'Покажи детаље',
'metadata-collapse' => 'Сакриј детаље',
'metadata-fields'   => 'Поља EXIF метаподатака наведена у овој поруци ће бити убачена на страну о слици када се рашири табела за метаподатке. Остала ће бити сакривена по подразумеваном.
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

'exif-unknowndate' => 'Непознат датум',

'exif-orientation-1' => 'Нормално',
'exif-orientation-2' => 'Обрнуто по хоризонтали',
'exif-orientation-3' => 'Заокренуто 180°',
'exif-orientation-4' => 'Обрнуто по вертикали',
'exif-orientation-5' => 'Заокренуто 90° супротно од смера казаљке на сату и обрнуто по вертикали',
'exif-orientation-6' => 'Заокренуто 90° у смеру казаљке на сату',
'exif-orientation-7' => 'Заокренуто 90° у смеру казаљке на сату и обрнуто по вертикали',
'exif-orientation-8' => 'Заокренуто 90° супротно од смера казаљке на сату',

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

# Flash modes
'exif-flash-fired-0'    => 'Блиц није коришћен',
'exif-flash-fired-1'    => 'Блиц је коришћен',
'exif-flash-mode-3'     => 'ауто мод',
'exif-flash-function-1' => 'Без блица',
'exif-flash-redeye-1'   => 'мод за редукцију црвених очију',

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

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Километри на час',
'exif-gpsspeed-m' => 'Миље на час',
'exif-gpsspeed-n' => 'Чворови',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Прави правац',
'exif-gpsdirection-m' => 'Магнетни правац',

# External editor support
'edit-externally'      => 'Измените овај фајл користећи спољашњу апликацију',
'edit-externally-help' => '(Погледајте [http://www.mediawiki.org/wiki/Manual:External_editors упутство за подешавање] за више информација)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'све',
'imagelistall'     => 'све',
'watchlistall2'    => 'све',
'namespacesall'    => 'сви',
'monthsall'        => 'све',
'limitall'         => 'све',

# E-mail address confirmation
'confirmemail'             => 'Потврдите адресу е-поште',
'confirmemail_noemail'     => 'Немате потврђену адресу ваше е-поште у вашим [[Special:Preferences|корисничким подешавањима интерфејса]].',
'confirmemail_text'        => 'Ова вики захтева да потврдите адресу ваше е-поште пре него што користите могућности е-поште. Активирајте дугме испод како бисте послали пошту за потврду на вашу адресу. Пошта укључује везу која садржи код; учитајте ту везу у ваш браузер да бисте потврдили да је адреса ваше е-поште валидна.',
'confirmemail_pending'     => 'Код потврде је већ послат на Вашу е-пошру;
Ако сте скоро направили Ваш налог, вероватно би требало да одчекате неколико минута, како би код стигао, пре него што затражите нови.',
'confirmemail_send'        => 'Пошаљи код за потврду',
'confirmemail_sent'        => 'Е-пошта за потврђивање послата.',
'confirmemail_oncreate'    => 'Код за потврду је послат на вашу имејл адресу.
Овај код није потребан да бисте се улоговали, али ће од Вас бити тражено да га приложите да би омогућили погодности Викија везане за коришћење мејлова.',
'confirmemail_sendfailed'  => '{{SITENAME}} није успела да пошање е-пошту. 
Проверита адресу због неправилних карактера.

Враћено: $1',
'confirmemail_invalid'     => 'Нетачан код за потврду. Могуће је да је код истекао.',
'confirmemail_needlogin'   => 'Морате да $1 да бисте потврдили адресу ваше е-поште.',
'confirmemail_success'     => 'Адреса ваше е-поште је потврђена. Можете сада да се пријавите и уживате у викију.',
'confirmemail_loggedin'    => 'Адреса ваше е-поште је сада потврђена.',
'confirmemail_error'       => 'Нешто је пошло по злу приликом снимања ваше потврде.',
'confirmemail_subject'     => '{{SITENAME}} адреса е-поште за потврђивање',
'confirmemail_body'        => 'Неко, вероватно ви, са ИП адресе $1 
је регистровао налог „$2” са овом адресом е-поште на сајту {{SITENAME}}.

Да потврдите да овај налог стварно припада вама и да активирате 
могућност е-поште на сајту {{SITENAME}}, отворите ову везу у вашем браузеру:

$3

Ако ово *нисте* ви, пратите ову везу како бисте прекинули регистрацију:

$5 

Овај код за потврду ће истећи у $4.',
'confirmemail_invalidated' => 'Овера електронске адресе је поништена.',
'invalidateemail'          => 'поништавање потврде путем имејла',

# Scary transclusion
'scarytranscludedisabled' => '[Интервики укључивање је онемогућено]',
'scarytranscludefailed'   => '[Доношење шаблона за $1 неуспешно]',
'scarytranscludetoolong'  => '[УРЛ је предугачак]',

# Trackbacks
'trackbackbox'      => 'Враћања за овај чланак:<br />
$1',
'trackbackremove'   => '([$1 Брисање])',
'trackbacklink'     => 'Враћање',
'trackbackdeleteok' => 'Враћање је успешно обрисано.',

# Delete conflict
'deletedwhileediting' => "'''Упозорење''': Ова страна је обрисана пошто сте почели уређивање!",
'confirmrecreate'     => "Корисник [[User:$1|$1]] ([[User talk:$1|разговор]]) је обрисао овај чланак пошто сте почели уређивање са разлогом:
: ''$2''

Молимо потврдите да стварно желите да поново направите овај чланак.",
'recreate'            => 'Поново направи',

# action=purge
'confirm_purge_button' => 'Да',
'confirm-purge-top'    => 'Да ли желите очистити кеш ове странице?',
'confirm-purge-bottom' => 'Чишћење кеша стране приморава софтвер да прикаже њену најновију верзију.',

# Multipage image navigation
'imgmultipageprev' => '&larr; претходна страна',
'imgmultipagenext' => 'следећа страна &rarr;',
'imgmultigo'       => 'Иди!',
'imgmultigoto'     => 'Иди на страну $1',

# Table pager
'ascending_abbrev'         => 'раст',
'descending_abbrev'        => 'опад',
'table_pager_next'         => 'Следећа страна',
'table_pager_prev'         => 'Претходна страна',
'table_pager_first'        => 'Прва страница',
'table_pager_last'         => 'Последња страница',
'table_pager_limit'        => 'Прикажи $1 делова информације по страници',
'table_pager_limit_submit' => 'Иди',
'table_pager_empty'        => 'Без резултата',

# Auto-summaries
'autosumm-blank'   => 'Обрисао садржај стране',
'autosumm-replace' => "Замена странице са '$1'",
'autoredircomment' => 'Преусмерење на [[$1]]',
'autosumm-new'     => "Направио страну са '$1'",

# Live preview
'livepreview-loading' => 'Учитавање…',
'livepreview-ready'   => 'Учитавање… Готово!',
'livepreview-failed'  => 'Брзи приказ неуспешан! Покушајте нормални приказ.',
'livepreview-error'   => 'Неуспешна конекција: $1 "$2". Пробајте нормални приказ.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Измене новије од $1 {{PLURAL:$1|секунде|секунде|секунди}} се неће приказати у списку.',
'lag-warn-high'   => 'Због великог лага базе података, измене новије од $1 {{PLURAL:$1|секунде|секунде|секунди}} се неће приказати на списку.',

# Watchlist editor
'watchlistedit-numitems'       => 'Ваш списак надгледања садржи {{PLURAL:$1|1 наслов|$1 наслова}}, искључујући странице за разговор.',
'watchlistedit-noitems'        => 'Нема наслова у вашем списку надгледања.',
'watchlistedit-normal-title'   => 'Уреди списак надгледања',
'watchlistedit-normal-legend'  => 'Уклони наслове са списка надгледања',
'watchlistedit-normal-explain' => 'Списак страница које надгледате је приказан испод.
Да уклоните страницу, обележите квадратић поред, и кликните на дугме "{{int:Watchlistedit-normal-submit}}".
Такође можете да [[Special:Watchlist/raw|измените списак у простом формату]].',
'watchlistedit-normal-submit'  => 'Уклони наслове',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 чланак је уклоњен|$1 чланка су уклоњена|$1 чланака је уклоњено}} са вашег списка надгледања:',
'watchlistedit-raw-title'      => 'мењање сировог списка надгледања',
'watchlistedit-raw-legend'     => 'мењање сировог списка надгледања',
'watchlistedit-raw-explain'    => 'Наслови са Вашег списка надгледања су приказани испод и могу се мењати додавањем или одузимањем;
Пишите један наслов по линији.
Када завршите, кликните "{{int:Watchlistedit-raw-submit}}".
Такође, можете [[Special:Watchlist/edit|користити стандардан уређивач списка]].',
'watchlistedit-raw-titles'     => 'Наслови:',
'watchlistedit-raw-submit'     => 'Освежите списак надгледања',
'watchlistedit-raw-done'       => 'Ваш списак надгледања је освежен.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 наслов је додат|$1 наслова су додата|$1 наслова је додато}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 наслов је уклоњен|$1 наслова су уклоњена|$1 наслова је уклоњено}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Преглед сродних промена',
'watchlisttools-edit' => 'Преглед и измена списка надгледања',
'watchlisttools-raw'  => 'Измена списка надгледања',

# Core parser functions
'unknown_extension_tag' => 'Непознати таг за екстензију: "$1".',
'duplicate-defaultsort' => "'''Упозорење:''' Подразумевани кључ сортирања „$2“ преписује раније подразумевани кључ сортирања „$1“.",

# Special:Version
'version'                          => 'Верзија',
'version-extensions'               => 'Инсталисане екстензије',
'version-specialpages'             => 'Посебне странице',
'version-parserhooks'              => 'закачке парсера',
'version-variables'                => 'Варијабле',
'version-other'                    => 'Остало',
'version-mediahandlers'            => 'руковаоци медијима',
'version-hooks'                    => 'закачке',
'version-extension-functions'      => 'Функције додатка',
'version-parser-extensiontags'     => 'тагови екстензије Парсер',
'version-parser-function-hooks'    => 'закачке парсерове функције',
'version-skin-extension-functions' => 'екстензије функције коже',
'version-hook-name'                => 'име закачке',
'version-hook-subscribedby'        => 'пријављени',
'version-version'                  => '(Верзија $1)',
'version-license'                  => 'Лиценца',
'version-software'                 => 'Инсталиран софтвер',
'version-software-product'         => 'Производ',
'version-software-version'         => 'Верзија',

# Special:FilePath
'filepath'         => 'Путања фајла',
'filepath-page'    => 'Фајл:',
'filepath-submit'  => 'Пошаљи',
'filepath-summary' => 'Ова специјална страна враћа комплетну путању за фајл.
Слике бивају приказане у пуној резолуцији, други типови фајлова бивају директно стартовани помоћу њима придружених прогама.

Унесите назив фајла без префикса "{{ns:file}}:".',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Претражите дупликате фајлова',
'fileduplicatesearch-summary'  => 'Претрага за дупликатима фајлова на бази њихових хеш вредности.

Унесите име фајла без префикса "{{ns:file}}:".',
'fileduplicatesearch-legend'   => 'Претражите дупликате',
'fileduplicatesearch-filename' => 'Име фајла:',
'fileduplicatesearch-submit'   => 'Претрага',
'fileduplicatesearch-info'     => '$1 × $2 поксел<br />Величина фајла: $3<br />MIME тип: $4',
'fileduplicatesearch-result-1' => 'Датотека „$1“ нема идентичних дупликата.',
'fileduplicatesearch-result-n' => 'Датотека "$1" има {{PLURAL:$2|1 идентичан дупликат|$2 идентична дупликата|$2 идентичних дупликата}}.',

# Special:SpecialPages
'specialpages'                   => 'Посебне странице',
'specialpages-note'              => '----
* Обичне посебне странице
* <strong class="mw-specialpagerestricted">Заштићене посебне странице.</strong>',
'specialpages-group-maintenance' => 'Извештаји',
'specialpages-group-other'       => 'Остале посебне странице',
'specialpages-group-login'       => 'Пријави се / региструј се',
'specialpages-group-changes'     => 'Скорашње измене и историје',
'specialpages-group-media'       => 'Мултимедијални извештаји и записи слања',
'specialpages-group-users'       => 'Корисници и корисничка права',
'specialpages-group-highuse'     => 'Највише коришћене стране',
'specialpages-group-pages'       => 'Списак страница',
'specialpages-group-pagetools'   => 'Алатке са странице',
'specialpages-group-wiki'        => 'подаци и оруђа викија',
'specialpages-group-redirects'   => 'преусмерење посебних страна',
'specialpages-group-spam'        => 'оруђа против спама',

# Special:BlankPage
'blankpage'              => 'празна страна',
'intentionallyblankpage' => 'Ова страна је намерно остављена празном.',

# External image whitelist
'external_image_whitelist' => ' #Оставите ову линију тачно онаквом каква јесте<pre>
#Додајте фрагменте регуларних израза (само део који се налази између //) испод
#Они ће бити упоређено са URL-овима спољашњих (hot-линкованих) слика
#Оне које одговарају ће бити приказане као слике, а преостале као везе ка сликама
#Линије које почињу са # се третирају као коментари
#Сви уноси су осетљиви на величину слова

#Додајте све фрагменте регуларних израза испод ове линије. Оставите ову линију тачно онаквом каква јесте</pre>',

# Special:Tags
'tags'                    => 'Дозвољени тагови измене',
'tag-filter'              => 'Филтер за [[Special:Tags|тагове]]:',
'tag-filter-submit'       => 'Филтрирај',
'tags-title'              => 'Тагови',
'tags-intro'              => 'Ова страна даје списак и значење тагова којима софтвер може да означи неку измену.',
'tags-tag'                => 'Интерно име тага',
'tags-display-header'     => 'Изглед на списковима промена',
'tags-description-header' => 'Пуни опис значења',
'tags-hitcount-header'    => 'Таговане измене',
'tags-edit'               => 'измени',
'tags-hitcount'           => '$1 {{PLURAL:$1|измена|измена}}',

# Database error messages
'dberr-header'      => 'Овај Вики има проблем',
'dberr-problems'    => 'Жао нам је! Овај сајт има техничких потешкоћа.',
'dberr-again'       => 'Покушајте да причекате неколико минута, пре него што покушате да поново учитате страну.',
'dberr-info'        => '(Сервер базе података не може да се контактира: $1)',
'dberr-usegoogle'   => 'У међувремену Вам од користи може бити Гуглова претрага.',
'dberr-outofdate'   => 'Приметите да Гуглов кеш нашег садржаја може бити неажуран.',
'dberr-cachederror' => 'Ово је кеширана копија захтеване стране, и можда није ажурна.',

# HTML forms
'htmlform-invalid-input'       => 'Има проблема са делом Вашег уноса',
'htmlform-select-badoption'    => 'Вредност коју сте навели није исправна опција.',
'htmlform-int-invalid'         => 'Вредност који сте навели није цели број.',
'htmlform-float-invalid'       => 'Вредност коју сте задали није број.',
'htmlform-int-toolow'          => 'Вредности коју сте навели је испод минимума од $1',
'htmlform-int-toohigh'         => 'Вредност коју сте навели је изнад максимума од $1',
'htmlform-submit'              => 'Пошаљи',
'htmlform-reset'               => 'Врати измене',
'htmlform-selectorother-other' => 'Друго',

# Add categories per AJAX
'ajax-add-category'            => 'Додај категорију',
'ajax-add-category-submit'     => 'Додај',
'ajax-confirm-title'           => 'Потврди акцију',
'ajax-confirm-prompt'          => 'Испод можете да додате опис измене.
Притисните "Сними" да бисте снимили своју измену.',
'ajax-confirm-save'            => 'Сачувај',
'ajax-add-category-summary'    => 'Додај категорију "$1"',
'ajax-remove-category-summary' => 'Уклони категорију "$1"',
'ajax-confirm-actionsummary'   => 'Акција за извршење:',
'ajax-error-title'             => 'Грешка',
'ajax-error-dismiss'           => 'У реду',
'ajax-remove-category-error'   => 'Није било могуће уклонити ову категорију.
То се обично дешава када је категорија додата страници преко шаблона.',

);

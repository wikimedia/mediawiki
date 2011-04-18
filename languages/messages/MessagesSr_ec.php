<?php
/** Serbian Cyrillic ekavian (‪Српски (ћирилица)‬)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Bjankuloski06
 * @author CERminator
 * @author Charmed94
 * @author FriedrickMILBarbarossa
 * @author Helios13
 * @author Kale
 * @author Meno25
 * @author Millosh
 * @author Nikola Smolenski
 * @author Rancher
 * @author Red Baron
 * @author Reedy
 * @author Sasa Stefanovic
 * @author Slaven Kosanovic
 * @author Јованвб
 * @author Жељко Тодоровић
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
	'DoubleRedirects'           => array( 'Двострука_преусмерења' ),
	'BrokenRedirects'           => array( 'Покварена_преусмерења' ),
	'Disambiguations'           => array( 'Вишезначне_одреднице' ),
	'CreateAccount'             => array( 'ОтвориНалог' ),
	'Preferences'               => array( 'Подешавања' ),
	'Watchlist'                 => array( 'СписакНадгледања' ),
	'Recentchanges'             => array( 'СкорашњеИзмене' ),
	'Upload'                    => array( 'Пошаљи' ),
	'Listfiles'                 => array( 'СписакСлика' ),
	'Newimages'                 => array( 'НовиФајлови', 'НовеСлике' ),
	'Listusers'                 => array( 'СписакКорисника', 'КорисничкиСписак' ),
	'Listgrouprights'           => array( 'СписакКорисничкихПрава' ),
	'Statistics'                => array( 'Статистике' ),
	'Randompage'                => array( 'СлучајнаСтрана' ),
	'Lonelypages'               => array( 'Сирочићи' ),
	'Uncategorizedpages'        => array( 'ЧланциБезКатегорија' ),
	'Uncategorizedcategories'   => array( 'КатегоријеБезКатегорија' ),
	'Uncategorizedimages'       => array( 'СликеБезКатегорија', 'ФајловиБезКатегорија' ),
	'Uncategorizedtemplates'    => array( 'ШаблониБезКатегорија' ),
	'Unusedcategories'          => array( 'НеискоришћенеКатегорије' ),
	'Unusedimages'              => array( 'НеискоришћенеСлике', 'НеискоришћениФајлови' ),
	'Wantedpages'               => array( 'ТраженеСтране' ),
	'Wantedcategories'          => array( 'ТраженеКатегорије' ),
	'Wantedfiles'               => array( 'ТраженеСлике' ),
	'Wantedtemplates'           => array( 'ТражениШаблони' ),
	'Mostlinked'                => array( 'НајповезанијеСтране' ),
	'Mostlinkedcategories'      => array( 'НајповезанијеКатегорије' ),
	'Mostlinkedtemplates'       => array( 'НајповезанијиШаблони' ),
	'Mostimages'                => array( 'НајповезанијеСлике' ),
	'Mostcategories'            => array( 'ЧланциСаНајвишеКатегорија' ),
	'Mostrevisions'             => array( 'ЧланциСаНајвишеРевизија' ),
	'Fewestrevisions'           => array( 'ЧланциСаНајмањеРевизија' ),
	'Shortpages'                => array( 'КраткиЧланци' ),
	'Longpages'                 => array( 'ДугачкеСтране' ),
	'Newpages'                  => array( 'НовеСтране' ),
	'Ancientpages'              => array( 'НајстаријиЧланци' ),
	'Protectedpages'            => array( 'ЗаштићенеСтранице' ),
	'Specialpages'              => array( 'СпецијалнеСтране' ),
	'Contributions'             => array( 'Доприноси' ),
	'Confirmemail'              => array( 'ПотврдиЕ-пошту' ),
	'Movepage'                  => array( 'Преусмери' ),
	'Blockme'                   => array( 'БлокирајМе' ),
	'Categories'                => array( 'Категорије' ),
	'Export'                    => array( 'Извези' ),
	'Version'                   => array( 'Верзија' ),
	'Allmessages'               => array( 'СвеПоруке' ),
	'Block'                     => array( 'Блокирај', 'БлокирајИП', 'БлокирајКорисника' ),
	'Import'                    => array( 'Увези' ),
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

	'hh:mm d. month y. both'    => 'H:i, j. F Y.',
	'hh:mm d month y both'      => 'H:i, j F Y',
	'hh:mm dd.mm.yyyy both'     => 'H:i, d.m.Y',
	'hh:mm d.m.yyyy both'       => 'H:i, j.n.Y',
	'hh:mm d. mon y. both'      => 'H:i, j. M Y.',
	'hh:mm d mon y both'        => 'H:i, j M Y',
	'h:mm d. month y. both'     => 'G:i, j. F Y.',
	'h:mm d month y both'       => 'G:i, j F Y',
	'h:mm dd.mm.yyyy both'      => 'G:i, d.m.Y',
	'h:mm d.m.yyyy both'        => 'G:i, j.n.Y',
	'h:mm d. mon y. both'       => 'G:i, j. M Y.',
	'h:mm d mon y both'         => 'G:i, j M Y',
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
$separatorTransformTable = array( ',' => '.', '.' => ',' );

$messages = array(
# User preference toggles
'tog-underline'               => 'Подвуци везе:',
'tog-highlightbroken'         => 'Обликуј неисправне везе <a href="" class="new">овако</a> (или овако<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Уравнај пасусе',
'tog-hideminor'               => 'Сакриј мање измене у списку скорашњих измена',
'tog-hidepatrolled'           => 'Сакриј прегледане измене у списку скорашњих измена',
'tog-newpageshidepatrolled'   => 'Сакриј прегледане странице са списка нових страница',
'tog-extendwatchlist'         => 'Прошири списак надгледања за приказ свих измена, не само скорашњих',
'tog-usenewrc'                => 'Проширени списак скорашњих измена (јаваскрипт)',
'tog-numberheadings'          => 'Аутоматски нумериши поднаслове',
'tog-showtoolbar'             => 'Трака с алаткама за уређивање (јаваскрипт)',
'tog-editondblclick'          => 'Уређивање страница двоструким кликом (јаваскрипт)',
'tog-editsection'             => 'Везе за уређивање појединачних одељака',
'tog-editsectiononrightclick' => 'Уређивање одељака десним кликом на њихове наслове (јаваскрипт)',
'tog-showtoc'                 => 'Прикажи садржај за странице које имају више од три поднаслова',
'tog-rememberpassword'        => 'Запамти ме на овом прегледачу (најдуже $1 {{PLURAL:$1|дан|дана|дана}})',
'tog-watchcreations'          => 'Додај странице које направим у списак надгледања',
'tog-watchdefault'            => 'Додај странице које изменим у списак надгледања',
'tog-watchmoves'              => 'Додај странице које преместим у списак надгледања',
'tog-watchdeletion'           => 'Додај странице које обришем у списак надгледања',
'tog-minordefault'            => 'Означи све измене као мање',
'tog-previewontop'            => 'Преглед пре оквира за уређивање',
'tog-previewonfirst'          => 'Преглед на првој измени',
'tog-nocache'                 => 'Онемогући привремено меморисање страница',
'tog-enotifwatchlistpages'    => 'Пошаљи ми е-поруку када се промени страница коју надгледам',
'tog-enotifusertalkpages'     => 'Пошаљи ми е-поруку када се промени моја страница за разговор',
'tog-enotifminoredits'        => 'Пошаљи ми е-поруку и код мањих измена',
'tog-enotifrevealaddr'        => 'Прикажи моју е-адресу у порукама обавештења',
'tog-shownumberswatching'     => 'Прикажи број корисника који надгледају',
'tog-oldsig'                  => 'Преглед потписа:',
'tog-fancysig'                => 'Сматрај потпис као викитекст (без самоповезивања)',
'tog-externaleditor'          => 'Увек користи спољни уређивач (само за напредније кориснике, потребне су посебне поставке на рачунару).',
'tog-externaldiff'            => 'Увек користи спољни програм за упоређивање (само за напредније кориснике, потребне су посебне поставке на рачунару).',
'tog-showjumplinks'           => 'Омогући везе „Иди на“',
'tog-uselivepreview'          => 'Тренутни преглед (јаваскрипт, пробна фаза)',
'tog-forceeditsummary'        => 'Опомени ме при уносу празног сажетка',
'tog-watchlisthideown'        => 'Сакриј моје измене са списка надгледања',
'tog-watchlisthidebots'       => 'Сакриј измене ботова са списка надгледања',
'tog-watchlisthideminor'      => 'Сакриј мање измене са списка надгледања',
'tog-watchlisthideliu'        => 'Сакриј измене пријављених корисника са списка надгледања',
'tog-watchlisthideanons'      => 'Сакриј измене анонимних корисника са списка надгледања',
'tog-watchlisthidepatrolled'  => 'Сакриј прегледане измене са списку надгледања',
'tog-nolangconversion'        => 'Искључи конверзију варијанти',
'tog-ccmeonemails'            => 'Пошаљи ми примерке е-порука које пошаљем другим корисницима',
'tog-diffonly'                => 'Не приказуј садржај странице при упоређивању измена',
'tog-showhiddencats'          => 'Скривене категорије',
'tog-norollbackdiff'          => 'Изостави разлику измена након враћања',

'underline-always'  => 'увек',
'underline-never'   => 'никад',
'underline-default' => 'по поставкама прегледача',

# Font style option in Special:Preferences
'editfont-style'     => 'Изглед фонта:',
'editfont-default'   => 'по поставкама прегледача',
'editfont-monospace' => 'фонт с једнаким размаком',
'editfont-sansserif' => 'бесерифни фонт',
'editfont-serif'     => 'серифни фонт',

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
'pagecategories'                 => '{{PLURAL:$1|Категорија|Категорије}}',
'category_header'                => 'Странице у категорији „$1“',
'subcategories'                  => 'Поткатегорије',
'category-media-header'          => 'Датотеке у категорији „$1“',
'category-empty'                 => "''Ова категорија је тренутно празна.''",
'hidden-categories'              => '{{PLURAL:$1|Сакривена категорија|Сакривене категорије|Сакривених категорија}}',
'hidden-category-category'       => 'Сакривене категорије',
'category-subcat-count'          => '{{PLURAL:$2|Ова категорија садржи само следећу поткатегорију.|Ова категорија има {{PLURAL:$1|следећу поткатегорију|следеће $1 поткатегорије|следећих $1 поткатегорија}}, од укупно $2.}}',
'category-subcat-count-limited'  => 'Ова категорија садржи {{PLURAL:$1|следећу поткатегорију|следеће $1 поткатегорије|следећих $1 поткатегорија}}.',
'category-article-count'         => '{{PLURAL:$2|Ова категорија садржи само следећу страницу.|{{PLURAL:$1|Следећа страница је|Следеће $1 странице су|Следећих $1 страница је}} у овој категорији, од укупно $2.}}',
'category-article-count-limited' => '{{PLURAL:$1|Следећа страница је|Следеће $1 странице су|Следећих $1 страница је}} у овој категорији.',
'category-file-count'            => '{{PLURAL:$2|Ова категорија садржи само следећу датотеку.|{{PLURAL:$1|Следећа датотека је|Следеће $1 датотеке су|Следећих $1 датотека је}} у овој категорији, од укупно $2.}}',
'category-file-count-limited'    => '{{PLURAL:$1|Следећа датотека је|Следеће $1 датотеке су|Следећих $1 датотека је}} у овој категорији.',
'listingcontinuesabbrev'         => 'наст.',
'index-category'                 => 'Пописане странице',
'noindex-category'               => 'Непописане странице',

'mainpagetext'      => "'''Медијавики је успешно инсталиран.'''",
'mainpagedocfooter' => 'Погледајте [http://meta.wikimedia.org/wiki/Help:Contents кориснички водич] за коришћење програма.

== Увод ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Помоћ у вези са подешавањима]
* [http://www.mediawiki.org/wiki/Manual:FAQ Често постављена питања]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Дописна листа о издањима Медијавикија]',

'about'         => 'О нама',
'article'       => 'Чланак',
'newwindow'     => '(отвара у новом прозору)',
'cancel'        => 'Откажи',
'moredotdotdot' => 'Више…',
'mypage'        => 'Моја страница',
'mytalk'        => 'Разговор',
'anontalk'      => 'Разговор за ову IP адресу',
'navigation'    => 'Навигација',
'and'           => '&#32;и',

# Cologne Blue skin
'qbfind'         => 'Пронађи',
'qbbrowse'       => 'Потражи',
'qbedit'         => 'Уреди',
'qbpageoptions'  => 'Поставке странице',
'qbpageinfo'     => 'Подаци о страници',
'qbmyoptions'    => 'Моје странице',
'qbspecialpages' => 'Посебне странице',
'faq'            => 'НПП',
'faqpage'        => 'Project:НПП',

# Vector skin
'vector-action-addsection'       => '+',
'vector-action-delete'           => 'Обриши',
'vector-action-move'             => 'Премести',
'vector-action-protect'          => 'Заштити',
'vector-action-undelete'         => 'Врати',
'vector-action-unprotect'        => 'Скини заштиту',
'vector-simplesearch-preference' => 'Проширени предлози за претрагу (само за тему Вектор)',
'vector-view-create'             => 'Направи',
'vector-view-edit'               => 'Уреди',
'vector-view-history'            => 'Историја',
'vector-view-view'               => 'Читај',
'vector-view-viewsource'         => 'Изворник',
'actions'                        => 'Акције',
'namespaces'                     => 'Именски простори',
'variants'                       => 'Варијанте',

'errorpagetitle'    => 'Грешка',
'returnto'          => 'Повратак на $1.',
'tagline'           => 'Извор: {{SITENAME}}',
'help'              => 'Помоћ',
'search'            => 'Претрага',
'searchbutton'      => 'Претражи',
'go'                => 'Иди',
'searcharticle'     => 'Иди',
'history'           => 'Историја странице',
'history_short'     => 'историја',
'updatedmarker'     => 'ажурирано од моје последње посете',
'info_short'        => 'Подаци',
'printableversion'  => 'Издање за штампу',
'permalink'         => 'Трајна веза',
'print'             => 'Штампај',
'view'              => 'Прикажи',
'edit'              => 'Уреди',
'create'            => 'Направи',
'editthispage'      => 'Уреди ову страницу',
'create-this-page'  => 'Направи ову страницу',
'delete'            => 'Обриши',
'deletethispage'    => 'Обриши ову страницу',
'undelete_short'    => 'Врати {{PLURAL:$1|једну обрисану измену|$1 обрисане измене|$1 обрисаних измена}}',
'viewdeleted_short' => 'Прикажи {{PLURAL:$1|обрисану измену|$1 обрисане измене|$1 обрисаних измена}}',
'protect'           => 'Заштити',
'protect_change'    => 'измени',
'protectthispage'   => 'Заштити ову страницу',
'unprotect'         => 'Скини заштиту',
'unprotectthispage' => 'Скини заштиту с ове странице',
'newpage'           => 'Нова страница',
'talkpage'          => 'Разговор о овој страници',
'talkpagelinktext'  => 'разговор',
'specialpage'       => 'Посебна страница',
'personaltools'     => 'Личне алатке',
'postcomment'       => 'Нови одељак',
'articlepage'       => 'Прикажи чланак',
'talk'              => 'Разговор',
'views'             => 'Прегледи',
'toolbox'           => 'Алатке',
'userpage'          => 'Погледај корисничку страницу',
'projectpage'       => 'Погледај страницу пројекта',
'imagepage'         => 'Погледај страницу датотеке',
'mediawikipage'     => 'Погледај страницу поруке',
'templatepage'      => 'Погледај страницу за шаблоне',
'viewhelppage'      => 'Погледај страницу за помоћ',
'categorypage'      => 'Погледај страницу категорија',
'viewtalkpage'      => 'Погледај разговор',
'otherlanguages'    => 'Други језици',
'redirectedfrom'    => '(преусмерено са $1)',
'redirectpagesub'   => 'Преусмерење',
'lastmodifiedat'    => 'Ова страница је последњи пут измењена $1 у $2',
'viewcount'         => 'Ова страница је посећена {{PLURAL:$1|једанпут|$1 пута|$1 пута}}.',
'protectedpage'     => 'Заштићена страница',
'jumpto'            => 'Иди на:',
'jumptonavigation'  => 'навигација',
'jumptosearch'      => 'претрага',
'view-pool-error'   => 'Жао нам је, сервери су тренутно преоптерећени.
Превише корисника покушава да прегледа ову страницу.
Сачекате неко време пре него што поново покушате да јој приступите.

$1',
'pool-timeout'      => 'Истек времена чека на закључавање',
'pool-queuefull'    => 'Скуп процеса је пун',
'pool-errorunknown' => 'Непозната грешка',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'О пројекту {{SITENAME}}',
'aboutpage'            => 'Project:О нама',
'copyright'            => 'Садржај је објављен под лиценцом $1.',
'copyrightpage'        => '{{ns:project}}:Ауторска права',
'currentevents'        => 'Актуелности',
'currentevents-url'    => 'Project:Новости',
'disclaimers'          => 'Одрицање одговорности',
'disclaimerpage'       => 'Project:Одрицање одговорности',
'edithelp'             => 'Помоћ при уређивању',
'edithelppage'         => 'Help:Уређивање',
'helppage'             => 'Help:Садржај',
'mainpage'             => 'Главна страна',
'mainpage-description' => 'Главна страна',
'policy-url'           => 'Project:Политика приватности',
'portal'               => 'Радионица',
'portal-url'           => 'Project:Радионица',
'privacy'              => 'Политика приватности',
'privacypage'          => 'Project:Политика приватности',

'badaccess'        => 'Грешке у овлашћењима',
'badaccess-group0' => 'Није вам дозвољено да извршите захтевану радњу.',
'badaccess-groups' => 'Радња је доступна само корисницима у {{PLURAL:$2|следећој групи|следећим групама}}:  $1.',

'versionrequired'     => 'Потребно је издање $1 Медијавикија',
'versionrequiredtext' => 'Потребно је издање $1 Медијавикија да би се користила ова страница.
Погледајте страницу за [[Special:Version|издање]].',

'ok'                      => 'У реду',
'retrievedfrom'           => 'Добављено из „$1“',
'youhavenewmessages'      => 'Имате $1 ($2).',
'newmessageslink'         => 'нових порука',
'newmessagesdifflink'     => 'последња измена',
'youhavenewmessagesmulti' => 'Имате нових порука на $1',
'editsection'             => 'уреди',
'editold'                 => 'уреди',
'viewsourceold'           => 'изворник',
'editlink'                => 'уреди',
'viewsourcelink'          => 'Изворник',
'editsectionhint'         => 'Уредите одељак „$1“',
'toc'                     => 'Садржај',
'showtoc'                 => 'прикажи',
'hidetoc'                 => 'сакриј',
'collapsible-collapse'    => 'Сакриј',
'collapsible-expand'      => 'Прикажи',
'thisisdeleted'           => 'Погледати или вратити $1?',
'viewdeleted'             => 'Погледати $1?',
'restorelink'             => '{{PLURAL:$1|обрисану измену|$1 обрисане измене|$1 обрисаних измена}}',
'feedlinks'               => 'Довод:',
'feed-invalid'            => 'Неисправна врста довода.',
'feed-unavailable'        => 'Доводи нису доступни',
'site-rss-feed'           => '$1 RSS довод',
'site-atom-feed'          => '$1 Атом довод',
'page-rss-feed'           => '„$1“ RSS довод',
'page-atom-feed'          => '„$1“ Атом довод',
'feed-atom'               => 'Атом',
'red-link-title'          => '$1 (страница не постоји)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Чланак',
'nstab-user'      => 'Корисник',
'nstab-media'     => 'Медији',
'nstab-special'   => 'Посебно',
'nstab-project'   => 'Пројекат',
'nstab-image'     => 'Датотека',
'nstab-mediawiki' => 'Порука',
'nstab-template'  => 'Шаблон',
'nstab-help'      => 'Помоћ',
'nstab-category'  => 'Категорија',

# Main script and global functions
'nosuchaction'      => 'Нема такве радње',
'nosuchactiontext'  => 'Радња наведена у адреси није исправна.
Можда сте погрешили при уносу адресе или сте следили застарелу везу.
Могуће је и да се ради о грешци у софтверу викија.',
'nosuchspecialpage' => 'Нема такве посебне странице',
'nospecialpagetext' => '<strong>Посебна страница не постоји.</strong>

Списак свих посебних страница налази се [[Special:SpecialPages|овде]].',

# General errors
'error'                => 'Грешка',
'databaseerror'        => 'Грешка у бази података',
'dberrortext'          => 'Дошло је до синтаксне грешке у бази.
Можда се ради о грешци у софтверу.
Последњи покушај упита је гласио:
<blockquote><tt>$1</tt></blockquote>
унутар функције „<tt>$2</tt>“.
База података је вратила грешку „<tt>$3: $4</tt>“.',
'dberrortextcl'        => 'Дошло је до синтаксне грешке у бази.
Последњи покушај упита је гласио:
„$1“
унутар функције „$2“.
База података је вратила грешку „$3: $4“',
'laggedslavemode'      => "'''Упозорење:''' страница је можда застарела.",
'readonly'             => 'База података је закључана',
'enterlockreason'      => 'Унесите разлог за закључавање, укључујући и време откључавања',
'readonlytext'         => 'База података је тренутно закључана, што значи да је није могуће мењати.

Разлог: $1',
'missing-article'      => 'Текст странице под називом „$1“ ($2) није пронађен.

Узрок ове грешке је обично застарела измена или веза до обрисане странице.

Ако то није случај, онда сте вероватно пронашли грешку у софтверу.
Пријавите је [[Special:ListUsers/sysop|администратору]] уз одговарајућу везу.',
'missingarticle-rev'   => '(измена#: $1)',
'missingarticle-diff'  => '(разлика: $1, $2)',
'readonly_lag'         => 'База података је закључана док се споредни базни сервери не ускладе с главним.',
'internalerror'        => 'Унутрашња грешка',
'internalerror_info'   => 'Унутрашња грешка: $1',
'fileappenderrorread'  => 'Читање датотеке „$1“ није могуће током качења.',
'fileappenderror'      => 'Качење датотеке „$1“ на „$2“ није успело.',
'filecopyerror'        => 'Умножавање датотеке „$1“ на „$2“ није успело.',
'filerenameerror'      => 'Преименовање датотеке „$1“ у „$2“ није успело.',
'filedeleteerror'      => 'Брисање датотеке „$1“ није успело.',
'directorycreateerror' => 'Прављење фасцикле „$1“ није успело.',
'filenotfound'         => 'Датотека „$1“ није пронађена.',
'fileexistserror'      => 'Писање по датотеци „$1“ није успело: датотека већ постоји',
'unexpected'           => 'Неочекивана вредност: „$1“=„$2“.',
'formerror'            => 'Грешка: слање обрасца није успело',
'badarticleerror'      => 'Ова радња се не може извршити на овој страници.',
'cannotdelete'         => 'Страницу или датотеку „$1“ није могуће обрисати.
Могуће је да ју је неко други већ обрисао.',
'badtitle'             => 'Лош наслов',
'badtitletext'         => 'Наслов странице је неисправан, празан или неисправно повезан међујезички или међувики наслов.
Можда садржи знакове који се не могу користити у насловима.',
'perfcached'           => 'Следећи подаци су привремено меморисани и могу бити застарели.',
'perfcachedts'         => 'Следећи подаци су привремено меморисани и последњи пут су ажурирани $2 у $3.',
'querypage-no-updates' => 'Ажурирање ове странице је тренутно онемогућено.
Подаци који се овде налазе могу бити застарели.',
'wrong_wfQuery_params' => 'Неисправни параметри за wfQuery()<br />
Функција: $1<br />
Упит: $2',
'viewsource'           => 'Изворник',
'viewsourcefor'        => 'за $1',
'actionthrottled'      => 'Акција је успорена',
'actionthrottledtext'  => 'У циљу борбе против непожељних порука, ограничене су вам измене у одређеном времену, а управо сте прешли то ограничење. Покушајте поново за неколико минута.',
'protectedpagetext'    => 'Ова страница је закључана да би се спречило уређивање.',
'viewsourcetext'       => 'Можете да погледате и умножите изворни текст ове странице:',
'protectedinterface'   => 'Ова страница је заштићена јер садржи текст корисничког сучеља програма.',
'editinginterface'     => "'''Упозорење:''' уређујете страницу која се користи за приказивање текста сучеља.
Измене на овој страници ће утицати на све кориснике.
Посетите [http://translatewiki.net/wiki/Main_Page?setlang=sr_ec translatewiki.net], пројекат намењен за превођење Медијавикија.",
'sqlhidden'            => '(SQL упит је сакривен)',
'cascadeprotected'     => 'Ова страница је закључана јер садржи {{PLURAL:$1|следећу страницу која је заштићена|следеће странице које су заштићене}} „преносивом“ заштитом:
$2',
'namespaceprotected'   => "Немате овлашћења да уређујете странице у именском простору '''$1'''.",
'customcssjsprotected' => 'Немате овлашћења да уређујете ову страницу јер она садржи личне поставке другог корисника.',
'ns-specialprotected'  => 'Посебне странице се не могу уређивати.',
'titleprotected'       => "Овај наслов је {{GENDER:$1|заштитио корисник|заштитила корисница|заштитио корисник}} [[User:$1|$1]].
Наведени разлог: ''$2''.",

# Virus scanner
'virus-badscanner'     => "Лоше подешавање због непознатог антивирусног програма: ''$1''",
'virus-scanfailed'     => 'скенирање није успело (кôд $1)',
'virus-unknownscanner' => 'непознат антивирусни програм:',

# Login and logout pages
'logouttext'                 => "'''Одјављени сте.'''

Можете да наставите с коришћењем овог викија као гост, или се [[Special:UserLogin|поново пријавите]] као други корисник.
Обратите пажњу да неке странице могу наставити да се приказују као да сте још увек пријављени, све док не очистите привремену меморију свог прегледача.",
'welcomecreation'            => '== Добро дошли, $1! ==

Ваш налог је отворен.
Не заборавите да прилагодите своја [[Special:Preferences|подешавања]].',
'yourname'                   => 'Корисничко име:',
'yourpassword'               => 'Лозинка:',
'yourpasswordagain'          => 'Потврдите лозинку:',
'remembermypassword'         => 'Запамти ме на овом прегледачу (најдуже $1 {{PLURAL:$1|дан|дана|дана}})',
'securelogin-stick-https'    => 'Останите повезани на HTTPS након пријаве',
'yourdomainname'             => 'Домен:',
'externaldberror'            => 'Дошло је до грешке при идентификацији базе података или немате овлашћења да ажурирате свој спољни налог.',
'login'                      => 'Пријави ме',
'nav-login-createaccount'    => 'Пријави се/региструј се',
'loginprompt'                => 'Омогућите колачиће да бисте се пријавили на овај вики.',
'userlogin'                  => 'Пријави се/региструј се',
'userloginnocreate'          => 'Пријава',
'logout'                     => 'Одјава',
'userlogout'                 => 'Одјави ме',
'notloggedin'                => 'Нисте пријављени',
'nologin'                    => "Немате налог? Идите на страницу ''$1''.",
'nologinlink'                => 'Отварање налога',
'createaccount'              => 'Отвори налог',
'gotaccount'                 => "Имате налог? Идите на страницу ''$1''.",
'gotaccountlink'             => 'Пријава',
'createaccountmail'          => 'Е-поштом',
'createaccountreason'        => 'Разлог:',
'badretype'                  => 'Лозинке које сте унели се не поклапају.',
'userexists'                 => 'Корисничко име је заузето. Изаберите друго.',
'loginerror'                 => 'Грешка при пријављивању',
'createaccounterror'         => 'Отварање налога није успело: $1',
'nocookiesnew'               => 'Кориснички налог је отворен, али нисте пријављени.
Овај вики користи колачиће за пријаву. Вама су колачићи онемогућени.
Омогућите их, па се онда пријавите са својим корисничким именом и лозинком.',
'nocookieslogin'             => 'Овај вики користи колачиће за пријављивање чланова.
Вама су колачићи онемогућени. Омогућите их и покушајте поново.',
'nocookiesfornew'            => 'Кориснички налог није направљен јер није потврђен његов извор.
Омогућите колачиће на прегледачу и поново учитајте страницу.',
'noname'                     => 'Нисте изабрали исправно корисничко име.',
'loginsuccesstitle'          => 'Успешно пријављивање',
'loginsuccess'               => "'''Пријављени сте као „$1“.'''",
'nosuchuser'                 => 'Не постоји корисник с именом „$1“.
Корисничка имена су осетљива на мала и велика слова.
Проверите да ли сте га добро унели или [[Special:UserLogin/signup|отворите нови налог]].',
'nosuchusershort'            => 'Корисник с именом „<nowiki>$1</nowiki>“ не постоји.
Проверите да ли сте га добро унели.',
'nouserspecified'            => 'Морате навести корисничко име.',
'login-userblocked'          => '{{GENDER:$1|Овај корисник је блокиран|Ова корисница је блокирана|Овај корисник је блокиран}}. Пријава није дозвољена.',
'wrongpassword'              => 'Унели сте неисправну лозинку. Покушајте поново.',
'wrongpasswordempty'         => 'Нисте унели лозинку. Покушајте поново.',
'passwordtooshort'           => 'Лозинка мора имати најмање {{PLURAL:$1|један знак|$1 знака|$1 знакова}}.',
'password-name-match'        => 'Лозинка се мора разликовати од корисничког имена.',
'password-login-forbidden'   => 'Коришћење овог корисничког имена и лозинке је забрањено.',
'mailmypassword'             => 'Пошаљи ми нову лозинку',
'passwordremindertitle'      => '{{SITENAME}} – подсетник за лозинку',
'passwordremindertext'       => 'Неко, вероватно ви, са IP адресе $1 је затражио нову лозинку на викију {{SITENAME}} ($4).
Створена је привремена лозинка за {{GENDER:$2|корисника|корисницу|корисника}} $2 ($3).
Уколико је ово ваш захтев, пријавите се и поставите нову лозинку.
Привремена лозинка истиче за {{PLURAL:$5|један дан|$5 дана|$5 дана}}.

Ако је неко други затражио промену лозинке а ви не желите да је мењате, занемарите ову поруку.',
'noemail'                    => 'Не постоји е-адреса за {{GENDER:$1|корисника|корисницу|корисника}} $1.',
'noemailcreate'              => 'Морате навести исправну е-адресу',
'passwordsent'               => 'Нова лозинка је послата на е-адресу {{GENDER:$1|корисника|кориснице|корисника}} $1.
Пријавите се пошто је примите.',
'blocked-mailpassword'       => 'Вашој IP адреси је онемогућено уређивање страница, као и могућност захтевања нове лозинке.',
'eauthentsent'               => 'На наведену е-адресу је послат потврдни код.
Пре него што пошаљемо даљње поруке, пратите упутства с е-поште да бисте потврдили да сте ви отворили налог.',
'throttled-mailpassword'     => 'Подсетник за лозинку је послат {{PLURAL:$1|пре сат времена|у последња $1 сата|у последњих $1 сати}}.
Да бисмо спречили злоупотребу, поседник шаљемо само једном у року од {{PLURAL:$1|једног сата|$1 сата|$1 сати}}.',
'mailerror'                  => 'Грешка при слању е-поруке: $1',
'acct_creation_throttle_hit' => 'Посетиоци овог викија који користе вашу IP адресу су већ отворили {{PLURAL:$1|један налог|$1 налога|$1 налога}} претходни дан, што је највећи дозвољени број у том временском периоду.
Због тога посетиоци с ове IP адресе тренутно не могу отворити више налога.',
'emailauthenticated'         => 'Ваша е-адреса је потврђена $2 у $3.',
'emailnotauthenticated'      => 'Ваша е-адреса још увек није потврђена.
Е-поруке неће бити послате ни за једну од следећих могућности.',
'noemailprefs'               => 'Унесите е-адресу како би ове могућности радиле.',
'emailconfirmlink'           => 'Потврдите своју е-адресу',
'invalidemailaddress'        => 'Е-адреса не може бити прихваћена јер је неисправног облика.
Унесите исправну адресу или оставите празно поље.',
'accountcreated'             => 'Налог је отворен',
'accountcreatedtext'         => 'Налог {{GENDER:$1|корисника|кориснице|корисника}} $1 је отворен.',
'createaccount-title'        => 'Отварање корисничког налога за {{SITENAME}}',
'createaccount-text'         => 'Неко је отворио налог с вашом е-адресом на {{SITENAME}} ($4) под именом $2 и лозинком $3.
Пријавите се и промените своју лозинку.

Ако је ово грешка, занемарите ову поруку.',
'usernamehasherror'          => 'Корисничко име не може садржати знакове тарабе',
'login-throttled'            => 'Превише пута сте покушали да се пријавите.
Сачекајте неколико минута и покушајте поново.',
'login-abort-generic'        => 'Пријављивање није успело.',
'loginlanguagelabel'         => 'Језик: $1',
'suspicious-userlogout'      => 'Ваш захтев за одјаву је одбијен јер је послат од стране неисправног прегледача или посредника.',

# E-mail sending
'php-mail-error-unknown' => 'Непозната грешка у функцији PHP mail()',

# Password reset dialog
'resetpass'                 => 'Промена лозинке',
'resetpass_announce'        => 'Пријављени сте с привременом лозинком.
Да бисте завршили пријаву, подесите нову лозинку овде:',
'resetpass_header'          => 'Промена лозинке налога',
'oldpassword'               => 'Стара лозинка:',
'newpassword'               => 'Нова лозинка:',
'retypenew'                 => 'Потврдите лозинку:',
'resetpass_submit'          => 'Постави лозинку и пријави ме',
'resetpass_success'         => 'Ваша лозинка је промењена.
Пријављивање је у току…',
'resetpass_forbidden'       => 'Лозинка не може бити промењена',
'resetpass-no-info'         => 'Морате бити пријављени да бисте приступили овој страници.',
'resetpass-submit-loggedin' => 'Промени лозинку',
'resetpass-submit-cancel'   => 'Откажи',
'resetpass-wrong-oldpass'   => 'Неисправна привремена или текућа лозинка.
Можда сте већ променили лозинку или сте затражили нову привремену лозинку.',
'resetpass-temp-password'   => 'Привремена лозинка:',

# Edit page toolbar
'bold_sample'     => 'Подебљан текст',
'bold_tip'        => 'Подебљан текст',
'italic_sample'   => 'Курзивни текст',
'italic_tip'      => 'Курзивни текст',
'link_sample'     => 'Наслов везе',
'link_tip'        => 'Унутрашња веза',
'extlink_sample'  => 'http://www.primer.com наслов везе',
'extlink_tip'     => "Спољна веза (не заборавите префикс ''http://'')",
'headline_sample' => 'Наслов',
'headline_tip'    => 'Поднаслов',
'nowiki_sample'   => 'Убаците необликован текст овде',
'nowiki_tip'      => 'Занемари вики обликовање',
'image_sample'    => 'Пример.jpg',
'image_tip'       => 'Уграђена датотека',
'media_sample'    => 'Пример.ogg',
'media_tip'       => 'Веза',
'sig_tip'         => 'Потпис с тренутним временом',
'hr_tip'          => 'Водоравна линија (користити ретко)',

# Edit pages
'summary'                          => 'Опис измене:',
'subject'                          => 'Тема/наслов:',
'minoredit'                        => 'мања измена',
'watchthis'                        => 'надгледај ову страницу',
'savearticle'                      => 'Сачувај страницу',
'preview'                          => 'Претпреглед',
'showpreview'                      => 'Прикажи претпреглед',
'showlivepreview'                  => 'Тренутни преглед',
'showdiff'                         => 'Прикажи измене',
'anoneditwarning'                  => "'''Пажња:''' Нисте пријављени.
Ваша IP адреса ће бити забележена у историји ове странице.",
'anonpreviewwarning'               => "''Нисте пријављени. Ваша IP адреса ће бити забележена у историји ове странице.''",
'missingsummary'                   => "'''Напомена:''' нисте унели сажетак измене.
Ако поново кликнете на „{{int:savearticle}}“, ваша измена ће бити сачувана без сажетка.",
'missingcommenttext'               => 'Унесите коментар испод.',
'missingcommentheader'             => "'''Напомена:''' нисте унели тему/наслов овог коментара.
Ако поново кликнете на „{{int:savearticle}}“, ваша измена ће бити сачувана без теме/наслова.",
'summary-preview'                  => 'Преглед сажетка:',
'subject-preview'                  => 'Преглед теме/наслова:',
'blockedtitle'                     => 'Корисник је блокиран.',
'blockedtext'                      => "'''Ваше корисничко име или IP адреса је блокирана.'''

Блокирање је {{GENDER:$1|извршио|извршила|извршио}} $1.
Разлог: ''$2''.

* Датум блокирања: $8
* Блокирање истиче: $6
* Име корисника: $7

Контактирајте {{GENDER:$1|корисника|корисницу|корисника}} $1 или другог [[{{MediaWiki:Grouppage-sysop}}|администратора]] да бисте разговарали о блокирању.
Не можете користити могућност „Пошаљи е-поруку овом кориснику“ ако нисте унели исправну е-адресу у [[Special:Preferences|поставкама]].
Ваша блокирана IP адреса је $3, а ИБ $5.
Наведите све податке изнад при прављењу било каквих упита.",
'autoblockedtext'                  => "Ваша IP адреса је блокирана јер ју је употребљавао други корисник, кога је {{GENDER:$1|блокирао|блокирала|блокирао}} $1.
Разлог:

:''$2''

* Датум блокирања: $8
* Блокирање истиче: $6
* Име корисника: $7

Контактирајте {{GENDER:$1|корисника|корисницу|корисника}} $1 или неког другог [[{{MediaWiki:Grouppage-sysop}}|администратора]] да бисте разјаснили ствар.

Не можете користити могућност „Пошаљи е-поруку овом кориснику“ ако нисте унели исправну е-адресу у [[Special:Preferences|поставкама]].

Ваша блокирана IP адреса је $3, а ИБ $5.
Наведите све податке изнад при прављењу било каквих упита.",
'blockednoreason'                  => 'разлог није наведен',
'blockedoriginalsource'            => "Изворник странице '''$1''' је приказан испод:",
'blockededitsource'                => "Текст '''ваших измена''' на страници '''$1''' је приказан испод:",
'whitelistedittitle'               => 'За уређивање је потребна пријава',
'whitelistedittext'                => 'За уређивање странице је потребно да будете $1.',
'confirmedittext'                  => 'Морате потврдити своју е-адресу пре уређивања страница.
Поставите и потврдите је путем [[Special:Preferences|подешавања]].',
'nosuchsectiontitle'               => 'Одељак није пронађен',
'nosuchsectiontext'                => 'Покушали сте да уредите одељак који не постоји.
Можда је премештен или обрисан док сте прегледали страницу.',
'loginreqtitle'                    => 'Потребна је пријава',
'loginreqlink'                     => 'пријављени',
'loginreqpagetext'                 => 'Морате бити $1 да бисте видели друге странице.',
'accmailtitle'                     => 'Лозинка је послата.',
'accmailtext'                      => 'Лозинка за {{GENDER:$1|корисника|корисницу|корисника}} [[User talk:$1|$1]] је послата на $2.

Након пријаве, лозинка се може променити [[Special:ChangePassword|овде]].',
'newarticle'                       => '(Нови)',
'newarticletext'                   => 'Дошли сте на страницу која још увек не постоји.
Да бисте је направили, почните куцати у прозор испод овог текста (погледајте [[{{MediaWiki:Helppage}}|страницу за помоћ]]).
Ако сте овде дошли грешком, вратите се на претходну страницу.',
'anontalkpagetext'                 => '---- Ово је страница за разговор с анонимним корисником који још увек нема налог или га не користи.
Због тога морамо да користимо бројчану IP адресу како бисмо га идентификовали.
Такву адресу може делити више корисника.
Ако сте анонимни корисник и мислите да су вам упућене небитне примедбе, [[Special:UserLogin/signup|отворите налог]] или се [[Special:UserLogin|пријавите]] да бисте избегли будућу забуну с осталим анонимним корисницима.',
'noarticletext'                    => 'На овој страници тренутно нема садржаја.
Можете [[Special:Search/{{PAGENAME}}|потражити овај наслов]] на другим страницама,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} претражити сродне извештаје] или [{{fullurl:{{FULLPAGENAME}}|action=edit}} уредити страницу]</span>.',
'noarticletext-nopermission'       => 'На овој страници тренутно нема садржаја.
Можете [[Special:Search/{{PAGENAME}}|потражити овај наслов]] на другим страницама или <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} претражити сродне извештаје]</span>.',
'userpage-userdoesnotexist'        => 'Налог „$1“ није отворен.
Размислите да ли желите да направите или измените ову страницу.',
'userpage-userdoesnotexist-view'   => 'Кориснички налог „$1“ није отворен.',
'blocked-notice-logextract'        => 'Овај корисник је тренутно блокиран.
Подаци о последњем блокирању можете погледати испод:',
'clearyourcache'                   => "'''Напомена:''' након чувања, можда ћете морати да очистите привремену меморију прегледача.
'''Фајерфокс и Сафари:''' држите ''шифт'' и кликните на ''Освежи'', или притисните ''шифт+ктрл+Р'' (''Command-R'' на Мекинтошу);
'''К-освајач: '''кликните на ''Освежи'' или притисните ''F5'';
'''Опера:''' очистите привремену меморију преко менија ''Алатке → Поставке'';
'''Интернет експлорер: '''држите ''ктрл'' и кликните на ''Освежи'', или притисните ''ктрл-F5'';",
'usercssyoucanpreview'             => "'''Савет:''' кориситите дугме „{{int:showpreview}}“ да бисте тестирали свој нови CSS пре него што га сачувате.",
'userjsyoucanpreview'              => "'''Савет:''' кориситите дугме „{{int:showpreview}}“ да бисте тестирали свој нови јаваскрипт пре него што га сачувате.",
'usercsspreview'                   => "'''Ово је само преглед CSS-а.'''
'''Страница још увек није сачувана.'''",
'userjspreview'                    => "'''Ово је само преглед јаваскрипта.'''
'''Страница још увек није сачувана.'''",
'sitecsspreview'                   => "'''Ово је само преглед CSS-а.'''
'''Страница још увек није сачувана.'''",
'sitejspreview'                    => "'''Ово је само преглед јаваскрипта.'''
'''Страница још увек није сачувана.'''",
'userinvalidcssjstitle'            => "'''Упозорење:''' не постоји тема „$1“.
Прилагођене странице CSS и јаваскрипт почињу малим словом, нпр. {{ns:user}}:Foo/vector.css, а не {{ns:user}}:Foo/Vector.css.",
'updated'                          => '(Ажурирано)',
'note'                             => "'''Напомена:'''",
'previewnote'                      => "'''Ово је само преглед.'''
Страница још увек није сачувана.",
'previewconflict'                  => 'Овај преглед осликава како ће текст у текстуалном оквиру изгледати.',
'session_fail_preview'             => "'''Нисмо могли да обрадимо вашу измену због губитка података сесије.'''
Покушајте поново.
Ако и даље не ради, покушајте да се [[Special:UserLogout|одјавите]] и поново пријавите.",
'session_fail_preview_html'        => "'''Нисмо могли да обрадимо вашу измену због губитка података сесије.'''

''Будући да је на овом викију омогућен унос HTML ознака, преглед је сакривен као мера предострожности против напада преко јаваскрипта.''

'''Ако сте покушали да направите праву измену, покушајте поново.
Ако и даље не ради, покушајте да се [[Special:UserLogout|одјавите]] и поново пријавите.'''",
'token_suffix_mismatch'            => "'''Ваша измена је одбачена јер је ваш прегледач убацио знакове интерпункције у новчић уређивања.
То се понекад догађа када се користи неисправан посредник.'''",
'edit_form_incomplete'             => "'''Неки делови обрасца за уређивање нису достигли до сервера. Проверите да ли су измене промењене и покушајте поново.'''",
'editing'                          => 'Уређујете $1',
'editingsection'                   => 'Уређујете $1 (одељак)',
'editingcomment'                   => 'Уређујете $1 (нови одељак)',
'editconflict'                     => 'Сукобљене измене: $1',
'explainconflict'                  => "Неко други је у међувремену променио ову страницу.
Горњи оквир садржи текст странице.
Ваше измене су приказане у доњем пољу.
Мораћете да унесете своје промене у постојећи текст.
'''Само''' ће текст у горњем текстуалном оквиру бити сачуван када кликнете на „{{int:savearticle}}“.",
'yourtext'                         => 'Ваш текст',
'storedversion'                    => 'Ускладиштена измена',
'nonunicodebrowser'                => "'''Упозорење: ваш прегледач не подржава уникод.'''
Промените га пре него што почнете с уређивањем.",
'editingold'                       => "'''Упозорење: уређујете застарелу измену ове странице.
Ако је сачувате, све новије измене ће бити изгубљене.'''",
'yourdiff'                         => 'Разлике',
'copyrightwarning'                 => "Обратите пажњу да се сви прилози на овом викију сматрају да су објављени под лиценцом $2 (погледајте $1 за детаље).
Ако не желите да се ваш рад мења и расподељује без ограничења, онда га не шаљите овде.<br />
Такође нам обећавате да сте га сами написали или умножили с извора који је у јавном власништву.
'''Не шаљите радове заштићене ауторским правима без дозволе!'''",
'copyrightwarning2'                => "Сви прилози на овом викију могу да се мењају, враћају или бришу од стране других корисника.
Ако не желите да се ваши прилози немилосрдно мењају, не шаљите их овде.<br />
Такође нам обећавате да сте ово сами написали или умножили с извора у јавном власништву (погледајте $1 за детаље).
'''Не шаљите радове заштићене ауторским правима без дозволе!'''",
'longpageerror'                    => "'''Грешка: текст који сте унели је величине $1 килобајта, што је веће од дозвољених $2 килобајта.
Страница не може бити сачувана.'''",
'readonlywarning'                  => "'''Упозорење: база података је закључана ради одржавања, тако да нећете моћи да сачувате измене.
Најбоље би било да умножите текст у уређивач текста и сачувате га за касније.'''

Администратор који је закључао базу података је навео следеће објашњење: $1",
'protectedpagewarning'             => "'''Упозорење: ова страница је заштићена, тако да само администратори могу да је мењају.'''
Последња ставка у историји је приказана испод:",
'semiprotectedpagewarning'         => "'''Напомена:''' ова страница је заштићена, тако да је само учлањени корисници могу уређивати.
Последња ставка у историји је приказана испод:",
'cascadeprotectedwarning'          => "'''Упозорење:''' ова страница је заштићена тако да је могу уређивати само администратори, јер је она укључена у {{PLURAL:$1|следећу страницу која је|следеће странице које су}} заштићене „преносивом“ заштитом:",
'titleprotectedwarning'            => "'''Упозорење: ова страница је заштићена тако да је могу направити само корисници [[Special:ListGroupRights|с одређеним правима]].'''",
'templatesused'                    => '{{PLURAL:$1|Шаблон|Шаблони}} на овој страници:',
'templatesusedpreview'             => '{{PLURAL:$1|Шаблон|Шаблони}} у овом прегледу:',
'templatesusedsection'             => '{{PLURAL:$1|Шаблон|Шаблони}} у овом одељку:',
'template-protected'               => '(заштићено)',
'template-semiprotected'           => '(полузаштићено)',
'hiddencategories'                 => 'Ова страница је члан {{PLURAL:$1|једне скривене категорије|$1 скривене категорије|$1 скривених категорија}}:',
'edittools'                        => '<!-- Текст одавде ће бити показан испод формулара за уређивање и слање слика. -->',
'nocreatetitle'                    => 'Прављење странице је ограничено',
'nocreatetext'                     => 'На овом викију је ограничено прављење нових страница.
Можете се вратити и уредити постојећу страницу, или се [[Special:UserLogin|пријавите или отворите налог]].',
'nocreate-loggedin'                => 'Немате овлашћења да правите нове странице.',
'sectioneditnotsupported-title'    => 'Уређивање одељка није подржано',
'sectioneditnotsupported-text'     => 'Уређивање одељка није подржано на овој страници.',
'permissionserrors'                => 'Грешке у овлашћењима',
'permissionserrorstext'            => 'Немате овлашћење за ту радњу из {{PLURAL:$1|следећег|следећих}} разлога:',
'permissionserrorstext-withaction' => 'Немате овлашћења да $2 због {{PLURAL:$1|следећег|следећих}} разлога:',
'recreate-moveddeleted-warn'       => "'''Упозорење: поново правите страницу која је претходно обрисана.'''

Размотрите да ли је прикладно да наставите с уређивањем ове странице.
Овде је наведена историја брисања и премештања с образложењем:",
'moveddeleted-notice'              => 'Ова страница је обрисана.
Историја њеног брисања и премештања налази се испод:',
'log-fulllog'                      => 'Погледај целу историју',
'edit-hook-aborted'                => 'Измена је прекинута куком.
Образложење није понуђено.',
'edit-gone-missing'                => 'Ажурирање странице није успело.
Изгледа да је обрисана.',
'edit-conflict'                    => 'Сукоб измена.',
'edit-no-change'                   => 'Ваша измена је занемарена јер није било никаквих измена у тексту.',
'edit-already-exists'              => 'Прављење нове странице није успело.
Изгледа да она већ постоји.',

# Parser/template warnings
'expensive-parserfunction-warning'        => "'''Упозорење:''' ова страница садржи превише позива за рашчлањивање.

Требало би да има мање од $2 {{PLURAL:$2|позив|позива|позива}}, а сада има $1.",
'expensive-parserfunction-category'       => 'Странице с превише позива за рашчлањивање',
'post-expand-template-inclusion-warning'  => "'''Упозорење:''' величина укљученог шаблона је превелика.
Неки шаблони неће бити укључени.",
'post-expand-template-inclusion-category' => 'Странице где су укључени шаблони превелики',
'post-expand-template-argument-warning'   => "'''Упозорење:''' ова страница садржи најмање један аргумент у шаблону који има превелику величину.
Овакве аргументе би требало избегавати.",
'post-expand-template-argument-category'  => 'Странице које садрже изостављене аргументе у шаблону',
'parser-template-loop-warning'            => 'Откривена је петља шаблона: [[$1]]',
'parser-template-recursion-depth-warning' => 'Дубина укључивања шаблона је прекорачена ($1)',
'language-converter-depth-warning'        => 'Прекорачена је граница дубине језичког претварача ($1)',

# "Undo" feature
'undo-success' => 'Измена се може вратити.
Проверите разлике испод, па сачувајте измене.',
'undo-failure' => 'Измена се не може вратити услед сукобљених међуизмена.',
'undo-norev'   => 'Измена се не може вратити јер не постоји или је обрисана.',
'undo-summary' => 'Измена $1 је враћена од стране {{GENDER:$2|корисника|кориснице|корисника}} [[Special:Contributions/$2|$2]] ([[User talk:$2|разговор]])',

# Account creation failure
'cantcreateaccounttitle' => 'Отварање налога није могуће',
'cantcreateaccount-text' => "Отварање налога с ове IP адресе ('''$1''') је {{GENDER:$3|блокирао|блокирала|блокирао}} [[User:$3|$3]].

Разлог који је навео {{GENDER:$3|корисник|корисница|корисник}} $3 је ''$2''",

# History pages
'viewpagelogs'           => 'Историја ове странице',
'nohistory'              => 'Не постоји историја измена ове странице.',
'currentrev'             => 'Текућа измена',
'currentrev-asof'        => 'Текућа измена од $2 у $3',
'revisionasof'           => 'Измена од $2 у $3',
'revision-info'          => 'Измена од $1; $2',
'previousrevision'       => '← Старија измена',
'nextrevision'           => 'Новија измена →',
'currentrevisionlink'    => 'Текућа измена',
'cur'                    => 'трен',
'next'                   => 'след',
'last'                   => 'претх',
'page_first'             => 'прва',
'page_last'              => 'последња',
'histlegend'             => "Избор разлика: изаберите кутијице измена за упоређивање и притисните ентер или дугме на дну.<br />
Објашњење: '''({{int:cur}})''' – разлика с тренутном изменом,
'''({{int:last}})''' – разлика с претходном изменом, '''{{int:minoreditletter}}''' – мала измена",
'history-fieldset-title' => 'Преглед историје',
'history-show-deleted'   => 'само обрисано',
'histfirst'              => 'најстарије',
'histlast'               => 'најновије',
'historysize'            => '({{PLURAL:$1|1 бајт|$1 бајта|$1 бајтова}})',
'historyempty'           => '(празно)',

# Revision feed
'history-feed-title'          => 'Историја измена',
'history-feed-description'    => 'Историја измена ове странице',
'history-feed-item-nocomment' => '$1 у $2',
'history-feed-empty'          => 'Тражена страница не постоји.
Могуће да је обрисана с викија или је преименована.
Покушајте да [[Special:Search|претражите вики]] за сличне странице.',

# Revision deletion
'rev-deleted-comment'         => '(сажетак измене је уклоњен)',
'rev-deleted-user'            => '(корисничко име је уклоњено)',
'rev-deleted-event'           => '(историја је уклоњена)',
'rev-deleted-user-contribs'   => '[корисничко име или IP адреса су уклоњени – измена је сакривена са списка прилога]',
'rev-deleted-text-permission' => "Измена ове странице је '''обрисана'''.
Детаље можете видети у [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} историји брисања].",
'rev-deleted-text-unhide'     => "Измена ове странице је '''обрисана'''.
Детаље можете видети у [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} историји брисања].
Пошто сте администратор, још увек можете да [$1 видите ову измену].",
'rev-suppressed-text-unhide'  => "Измена ове странице је '''сакривена'''.
Детаље можете видети у [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} историји сакривања].
Пошто сте администратор, још увек можете да [$1 видите ову измену].",
'rev-deleted-text-view'       => "Измена ове странице је '''обрисана'''.
Пошто сте администратор, можете је видети. Детаљи се налазе у [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} историји брисања].",
'rev-suppressed-text-view'    => "Измена ове странице је '''сакривена'''.
Пошто сте администратор, можете је видети. Детаљи се налазе у [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} историји сакривања].",
'rev-deleted-no-diff'         => "Не можете видети ову разлику јер је једна од измена '''обрисана'''.
Детаљи се налазе у [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} историји брисања].",
'rev-suppressed-no-diff'      => "Не можете видети ову разлику јер је једна од измена '''обрисана'''.",
'rev-deleted-unhide-diff'     => "Једна од измена у овом прегледу разлика је '''обрисана'''.
Детаљи се налазе у [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} историји брисања].
Пошто сте администратор, можете [$1 видети ову разлику].",
'rev-suppressed-unhide-diff'  => "Једна од измена ове разлике је '''сакривена'''.
Детаљи се налазе у [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} историји сакривања].
Пошто сте администратор, још увек можете да [$1 видите ову разлику].",
'rev-deleted-diff-view'       => "Једна од измена ове разлике је '''обрисана'''.
Пошто сте администратор, можете видети ову разлику. Детаљи се налазе у [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} историји брисања].",
'rev-suppressed-diff-view'    => "Једна од измена ове разлике је '''сакривена'''.
Пошто сте администратор, можете видети ову разлику. Детаљи се налазе у [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} историји сакривања].",
'rev-delundel'                => 'прикажи/сакриј',
'rev-showdeleted'             => 'прикажи',
'revisiondelete'              => 'Обриши/врати измене',
'revdelete-nooldid-title'     => 'Нема тражене измене',
'revdelete-nooldid-text'      => 'Нисте навели жељену измену, она не постоји или покушавате да је сакријете.',
'revdelete-nologtype-title'   => 'Није наведена врста историје',
'revdelete-nologtype-text'    => 'Нисте навели врсту историје над којом желите да извршите ову радњу.',
'revdelete-nologid-title'     => 'Неисправан унос у историју',
'revdelete-nologid-text'      => 'Нисте одредили одредишну историју или наведени унос не постоји.',
'revdelete-no-file'           => 'Тражена датотека не постоји.',
'revdelete-show-file-confirm' => 'Желите ли да видите обрисану измену датотеке „<nowiki>$1</nowiki>“ од $2; $3?',
'revdelete-show-file-submit'  => 'Да',
'revdelete-selected'          => "'''{{PLURAL:$2|Изабрана измена|Изабране измене}} странице '''[[:$1]]''''''",
'logdelete-selected'          => "'''{{PLURAL:$1|Изабрана ставка у историји|Изабране ставке у историји}}:'''",
'revdelete-text'              => "'''Обрисане измене ће и даље бити приказане у историји страница и записима, али делови њиховог садржаја неће бити доступни јавности.'''
Други администратори на овом викију ће и даље имати приступ сакривеном садржају, а они ће тај садржај моћи да врате путем овог сучеља, осим ако нису постављена додатна ограничења.",
'revdelete-confirm'           => 'Потврдите да немеравате ово урадити, да разумете последице и да то чините у складу с [[{{MediaWiki:Policy-url}}|правилима]].',
'revdelete-suppress-text'     => "Сакривање измена би требало користити '''само''' у следећим случајевима:
* Злонамерни или погрдни подаци
* Неприкладни лични подаци
*: ''кућна адреса и број телефона, број банковне картице итд.''",
'revdelete-legend'            => 'Ограничења видљивости',
'revdelete-hide-text'         => 'сакриј текст измене',
'revdelete-hide-image'        => 'Сакриј садржај датотеке',
'revdelete-hide-name'         => 'Сакриј радњу и одредиште',
'revdelete-hide-comment'      => 'сакриј сажетак измене',
'revdelete-hide-user'         => 'сакриј име уређивача',
'revdelete-hide-restricted'   => 'Сакриј податке од администратора и других корисника',
'revdelete-radio-same'        => '(не мењај)',
'revdelete-radio-set'         => 'да',
'revdelete-radio-unset'       => 'не',
'revdelete-suppress'          => 'Сакриј податке од администратора и других корисника',
'revdelete-unsuppress'        => 'Уклони ограничења на враћеним изменама',
'revdelete-log'               => 'Разлог:',
'revdelete-submit'            => 'Примени на {{PLURAL:$1|изабрану измену|изабране измене}}',
'revdelete-logentry'          => 'је променио приказ измена за „[[$1]]”',
'logdelete-logentry'          => 'промењена видност догађаја за страну [[$1]]',
'revdelete-success'           => "'''Видљивост ревизије је успешно подешена.'''",
'revdelete-failure'           => "'''Видљивост ревизије није могла бити ажурирана:'''
$1",
'logdelete-success'           => "'''Видљивост извештаја је успешно подешена.'''",
'logdelete-failure'           => "'''Видљивост извештаја није подешена:'''
$1",
'revdel-restore'              => 'промени видљивост',
'revdel-restore-deleted'      => 'обрисане измене',
'revdel-restore-visible'      => 'видљиве измене',
'pagehist'                    => 'Историја странице',
'deletedhist'                 => 'Обрисана историја',
'revdelete-content'           => 'садржај',
'revdelete-summary'           => 'опис измене',
'revdelete-uname'             => 'корисничко име',
'revdelete-restricted'        => 'ограничења за администраторе су примењена',
'revdelete-unrestricted'      => 'ограничења за администраторе су уклоњена',
'revdelete-hid'               => 'сакривено: $1',
'revdelete-unhid'             => 'откривено: $1',
'revdelete-log-message'       => '$1 за $2 {{PLURAL:$2|ревизију|ревизије|ревизија}}',
'logdelete-log-message'       => '$1 за $2 {{PLURAL:$2|догађај|догађаја}}',
'revdelete-hide-current'      => 'Грешка при скривању ставке с датумом $1, $2: ово је тренутна измена.
Не може се сакрити.',
'revdelete-show-no-access'    => 'Грешка при приказивању ставке с датумом $1, $2: означена је као „ограничена“.
Немате приступ ставки.',
'revdelete-modify-no-access'  => 'Грешка при измењивању ставке с датумом $1, $2: означена је као „ограничена“.
Немате приступ ставки.',
'revdelete-modify-missing'    => 'Грешка при мењању ID ставке $1: она не постоји у бази података!',
'revdelete-no-change'         => "'''Напомена:''' ставка с датумом $1, $2 је већ имала затражене поставке видљивости.",
'revdelete-concurrent-change' => 'Грешка при измењивању ставке с датумом $1, $2: њено стање је промењено од стране другог корисника.
Погледајте извештаје.',
'revdelete-only-restricted'   => 'Грешка при скривању ставке с датумом $1, $2: не можете потиснути ставке од администратора без избора осталих поставки видљивости.',
'revdelete-reason-dropdown'   => '*Уобичајени разлози за брисање
** Кршење ауторског права
** Неодговарајуће личне информације
** Потенцијално увредљиве информације',
'revdelete-otherreason'       => 'Други/додатни разлог:',
'revdelete-reasonotherlist'   => 'Други разлог',
'revdelete-edit-reasonlist'   => 'Уреди разлоге за брисање',
'revdelete-offender'          => 'Аутор измене:',

# Suppression log
'suppressionlog'     => 'Извештај сакривања',
'suppressionlogtext' => 'Испод се налази списак блокова и обрисаних страница који су сакривени од администратора. Погледај [[Special:IPBlockList|списак блокираних ИП адреса]] за списак тренутно важећих банова и блокова.',

# History merging
'mergehistory'                     => 'Споји историје страница',
'mergehistory-header'              => 'Ова страница омогућава спајање верзија једне странице у другу. Уверите се претходно да ће ова измена одржати континуитет историје странице.',
'mergehistory-box'                 => 'Споји измене две странице:',
'mergehistory-from'                => 'Изворна страница:',
'mergehistory-into'                => 'Жељена страница:',
'mergehistory-list'                => 'Историја измена које се могу спојити',
'mergehistory-merge'               => 'Следеће верзије стране [[:$1]] могу се спојити са [[:$2]]. Користи колону с "радио дугмићима" за спајање само оних верзија које су направљене пре датог времена. Коришћење навигационих линкова ће поништити ову колону.',
'mergehistory-go'                  => 'Прикажи измене које се могу спојити.',
'mergehistory-submit'              => 'Споји измене',
'mergehistory-empty'               => 'Нема измена које се могу спојити.',
'mergehistory-success'             => '$3 {{PLURAL:$3|ревизија|ревизије|ревизија}} стране [[:$1]] успешно спојено у [[:$2]].',
'mergehistory-fail'                => 'Није могуће спојити верзије; провери параметре стране и времена.',
'mergehistory-no-source'           => 'Изворна страница $1 не постоји.',
'mergehistory-no-destination'      => 'Жељена страница $1 не постоји.',
'mergehistory-invalid-source'      => 'Име изворне странице мора бити исправно.',
'mergehistory-invalid-destination' => 'Име жељене странице мора бити исправно.',
'mergehistory-autocomment'         => 'Спојена страница [[:$1]] у страницу [[:$2]].',
'mergehistory-comment'             => 'Спојена страница [[:$1]] у страницу [[:$2]]: $3',
'mergehistory-same-destination'    => 'Изворна и циљана страница не могу бити исте',
'mergehistory-reason'              => 'Разлог:',

# Merge log
'mergelog'           => 'Историја спајања',
'pagemerge-logentry' => 'спојена страница [[$1]] у страницу [[$2]] (број верзија до: $3)',
'revertmerge'        => 'растави',
'mergelogpagetext'   => 'Испод се налази списак скорашњих спајања верзија једне стране у другу.',

# Diffs
'history-title'            => 'Историја измена за „$1“',
'difference'               => '(разлике између измена)',
'difference-multipage'     => '(разлике између страница)',
'lineno'                   => 'Линија $1:',
'compareselectedversions'  => 'Упореди изабране измене',
'showhideselectedversions' => 'Прикажи/сакриј изабране измене',
'editundo'                 => 'поништи',
'diff-multi'               => '({{PLURAL:$1|Измена|$1 измене|$1 измена}} од стране {{PLURAL:$2|једног корисника|$2 корисника|$2 корисника}} није приказана)',
'diff-multi-manyusers'     => '({{PLURAL:$1|једна напредна измена|$1 напредне измене|$1 напредних измена}} од више од $2 {{PLURAL:$2|корисника|корисника|корисника}} није приказано)',

# Search results
'searchresults'                    => 'Резултати претраге',
'searchresults-title'              => 'Резултати претраге за „$1”',
'searchresulttext'                 => 'За више информација о претраживању {{SITENAME}}, погледајте [[{{MediaWiki:Helppage}}|Претраживање {{SITENAME}}]].',
'searchsubtitle'                   => 'Тражили сте \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|све странице које почињу са "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|све странице које повезују на "$1"]])',
'searchsubtitleinvalid'            => "Тражили сте '''$1'''",
'toomanymatches'                   => 'Превише погодака је враћено. Измените упит.',
'titlematches'                     => 'Поклапајући наслови страница',
'notitlematches'                   => 'Ниједан наслов странице се не поклапа',
'textmatches'                      => 'Поклапајући текст страница',
'notextmatches'                    => 'Ниједан текст странице се не поклапа',
'prevn'                            => 'претходних {{PLURAL:$1|$1}}',
'nextn'                            => 'следећих {{PLURAL:$1|$1}}',
'prevn-title'                      => '{{PLURAL:$1|Претходни $1 резултат|Претходних $1 резултата}}',
'nextn-title'                      => '{{PLURAL:$1|Следећи $1 резултат|Следећих $1 резултата}}',
'shown-title'                      => 'Прикажи $1 {{PLURAL:$1|резултат|резултата}} по страни',
'viewprevnext'                     => 'Прикажи ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => 'Поставке претраге',
'searchmenu-exists'                => "'''Постоји и чланак под називом „[[:$1]]“.'''",
'searchmenu-new'                   => "'''Направите страницу „[[:$1]]“.'''",
'searchmenu-new-nocreate'          => '„$1“ је неисправан назив странице или је ви не можете направити.',
'searchhelp-url'                   => 'Help:Садржај',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Потражи странице с овим префиксом]]',
'searchprofile-articles'           => 'Чланци',
'searchprofile-project'            => 'Странице помоћи и пројеката',
'searchprofile-images'             => 'Мултимедија',
'searchprofile-everything'         => 'Све',
'searchprofile-advanced'           => 'Напредно',
'searchprofile-articles-tooltip'   => 'Тражи у $1',
'searchprofile-project-tooltip'    => 'Тражи у $1',
'searchprofile-images-tooltip'     => 'Претражуј датотеке',
'searchprofile-everything-tooltip' => 'Претражи сав садржај (укључујући странице за разговор)',
'searchprofile-advanced-tooltip'   => 'Претражи у сопственим именским просторима',
'search-result-size'               => '$1 ({{PLURAL:$2|1 реч|$2 речи}})',
'search-result-category-size'      => '{{PLURAL:$1|1 члан|$1 члана|$1 чланова}}, ({{PLURAL:$2|1 поткатегорија|$2 поткатегорије|$2 поткатегорија}}, {{PLURAL:$3|1 датотека|$3 датотеке|$3 датотека}})',
'search-result-score'              => 'Релевантност: $1%',
'search-redirect'                  => '(преусмерење $1)',
'search-section'                   => '(наслов $1)',
'search-suggest'                   => 'Да ли сте мислили: $1',
'search-interwiki-caption'         => 'Братски пројекти',
'search-interwiki-default'         => '$1 резултати:',
'search-interwiki-more'            => '(више)',
'search-mwsuggest-enabled'         => 'са предлозима',
'search-mwsuggest-disabled'        => 'без предлога',
'search-relatedarticle'            => 'Сродно',
'mwsuggest-disable'                => 'Онемогући AJAX предлоге',
'searcheverything-enable'          => 'сви именски простори',
'searchrelated'                    => 'сродно',
'searchall'                        => 'све',
'showingresults'                   => "Испод {{PLURAL:$1|је приказан '''1''' резултат|су приказана '''$1''' резултата|је приказано '''$1''' резултата}} почев од броја '''$2'''.",
'showingresultsnum'                => "Испод {{PLURAL:$3|је приказан '''1''' резултат|су приказана '''$3''' резултата|је приказано '''$3''' резултата}} почев од броја '''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Резултат '''$1''' од '''$3'''|Резултата '''$1–$2''' од '''$3'''}} за '''$4'''",
'nonefound'                        => "'''Напомена''': Само неколико именских простора се претражују по основном подешавању.
Покушајте са префиксом '''све:''' да претражите цео садржај (укључујући странице за разговор, шаблоне итд.), или изаберите жељени именски простор као префикс.",
'search-nonefound'                 => 'Нема поклапања.',
'powersearch'                      => 'Тражи',
'powersearch-legend'               => 'Напредна претрага',
'powersearch-ns'                   => 'Тражи у именским просторима:',
'powersearch-redir'                => 'Списак преусмерења',
'powersearch-field'                => 'Тражи',
'powersearch-togglelabel'          => 'Избор:',
'powersearch-toggleall'            => 'све',
'powersearch-togglenone'           => 'ништа',
'search-external'                  => 'Спољашња претрага',
'searchdisabled'                   => '<p>Извињавамо се! Пуна претрага текста је привремено онемогућена, због бржег рада {{SITENAME}}. У међувремену, можете користити Гугл претрагу испод, која може бити застарела.</p>',

# Quickbar
'qbsettings'               => 'Брза палета',
'qbsettings-none'          => 'Ништа',
'qbsettings-fixedleft'     => 'Причвршћена лево',
'qbsettings-fixedright'    => 'Причвршћена десно',
'qbsettings-floatingleft'  => 'Плутајућа лево',
'qbsettings-floatingright' => 'Плутајућа десно',

# Preferences page
'preferences'                   => 'Подешавања',
'mypreferences'                 => 'Подешавања',
'prefs-edits'                   => 'Број измена:',
'prefsnologin'                  => 'Нисте пријављени',
'prefsnologintext'              => 'Морате бити <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} пријављени]</span> да бисте подешавали корисничка подешавања.',
'changepassword'                => 'Промени лозинку',
'prefs-skin'                    => 'Тема',
'skin-preview'                  => 'Прегледај',
'datedefault'                   => 'Свеједно',
'prefs-datetime'                => 'Датум и време',
'prefs-personal'                => 'Профил',
'prefs-rc'                      => 'Скорашње измене',
'prefs-watchlist'               => 'Списак надгледања',
'prefs-watchlist-days'          => 'Број дана у списку надгледања:',
'prefs-watchlist-days-max'      => 'Највише седам дана',
'prefs-watchlist-edits'         => 'Број измена у проширеном списку надгледања:',
'prefs-watchlist-edits-max'     => 'Највећа вредност је хиљаду',
'prefs-watchlist-token'         => 'Печат списка надгледања:',
'prefs-misc'                    => 'Разно',
'prefs-resetpass'               => 'Промени лозинку',
'prefs-email'                   => 'Поставке е-поште',
'prefs-rendering'               => 'Изглед',
'saveprefs'                     => 'Сачувај',
'resetprefs'                    => 'Очисти измене',
'restoreprefs'                  => 'Врати подразумевана подешавања',
'prefs-editing'                 => 'Уређивање',
'prefs-edit-boxsize'            => 'Величина оквира за уређивање.',
'rows'                          => 'Редова:',
'columns'                       => 'Колона',
'searchresultshead'             => 'Претрага',
'resultsperpage'                => 'Погодака по страници:',
'contextlines'                  => 'Линија по поготку:',
'contextchars'                  => 'Знакова по линији:',
'stub-threshold'                => 'Праг за форматирање <a href="#" class="stub">линка као клице</a> (у бајтовима):',
'stub-threshold-disabled'       => 'Онемогућено',
'recentchangesdays'             => 'Број дана у скорашњим изменама:',
'recentchangesdays-max'         => '(највише $1 {{PLURAL:$1|дан|дана|дана}})',
'recentchangescount'            => 'Број измена:',
'prefs-help-recentchangescount' => 'Ово укључује скорашње измене, историје и извештаје.',
'prefs-help-watchlist-token'    => 'Попуњавањем овог поља с тајном шифром направиће RSS довод вашег списка надгледања.
Свако ко зна ту шифру биће у могућности да види ваша надгледања, зато изаберите безбедну.
На пример: $1',
'savedprefs'                    => 'Ваша подешавања су сачувана.',
'timezonelegend'                => 'Временска зона:',
'localtime'                     => 'Локално време:',
'timezoneuseserverdefault'      => 'подразумеване вредности',
'timezoneuseoffset'             => 'друго (унесите одступање)',
'timezoneoffset'                => 'Одступање¹:',
'servertime'                    => 'Време на серверу:',
'guesstimezone'                 => 'попуни из прегледача',
'timezoneregion-africa'         => 'Африка',
'timezoneregion-america'        => 'Америка',
'timezoneregion-antarctica'     => 'Антарктик',
'timezoneregion-arctic'         => 'Арктик',
'timezoneregion-asia'           => 'Азија',
'timezoneregion-atlantic'       => 'Атлантски океан',
'timezoneregion-australia'      => 'Аустралија',
'timezoneregion-europe'         => 'Европа',
'timezoneregion-indian'         => 'Индијски океан',
'timezoneregion-pacific'        => 'Тихи океан',
'allowemail'                    => 'Омогући примање е-порука од других корисника',
'prefs-searchoptions'           => 'Претрага',
'prefs-namespaces'              => 'Именски простори',
'defaultns'                     => 'У супротном, тражи у овим именским просторима:',
'default'                       => 'подразумевано',
'prefs-files'                   => 'Датотеке',
'prefs-custom-css'              => 'Прилагођени CSS',
'prefs-custom-js'               => 'Прилагођени јаваскрипт',
'prefs-common-css-js'           => 'Дељени CSS/јаваскрипт за све теме:',
'prefs-reset-intro'             => 'Можете да користите ову страницу како бисте поништили своје поставке на подразумеване вредности.
Ова радња се не може вратити.',
'prefs-emailconfirm-label'      => 'Потврда е-поште:',
'prefs-textboxsize'             => 'Величина оквира за уређивање',
'youremail'                     => 'Е-пошта:',
'username'                      => 'Корисничко име:',
'uid'                           => 'Кориснички ИД:',
'prefs-memberingroups'          => 'Члан {{PLURAL:$1|групе|групâ}}:',
'prefs-memberingroups-type'     => '$1',
'prefs-registration'            => 'Време уписа:',
'yourrealname'                  => 'Право име:',
'yourlanguage'                  => 'Језик:',
'yourvariant'                   => 'Варијанта:',
'yournick'                      => 'Нови потпис:',
'prefs-help-signature'          => "Коментаре на корисничким страницама потпишите са ''<nowiki>~~~~</nowiki>''. Ови знакови биће преведени у ваш потпис и тренутно време.",
'badsig'                        => 'Неисправан потпис.
Проверите HTML ознаке.',
'badsiglength'                  => 'Ваш потпис је предугачак.
Мора бити краћи од $1 {{PLURAL:$1|карактер|карактера}}.',
'yourgender'                    => 'Пол:',
'gender-unknown'                => 'неназначен',
'gender-male'                   => 'мушки',
'gender-female'                 => 'женски',
'prefs-help-gender'             => 'Необавезно: користи се за исправно обраћање софтвера корисницима, зависно од њиховог пола.
Ова информација ће бити јавна.',
'email'                         => 'Е-пошта',
'prefs-help-realname'           => 'Право име није обавезно.
Ако изаберете да га унесете, оно ће бити коришћено за приписивање вашег рада.',
'prefs-help-email'              => 'Е-адреса није обавезна, али је потребна за обнављање лозинке.',
'prefs-help-email-others'       => ' Можете је користити и да омогућите другима да вас контактирају преко корисничке странице или странице за разговор, без откривања свог идентитета.',
'prefs-help-email-required'     => 'Адреса е-поште је потребна.',
'prefs-info'                    => 'Основни подаци',
'prefs-i18n'                    => 'Интернационализација',
'prefs-signature'               => 'Потпис',
'prefs-dateformat'              => 'Формат датума',
'prefs-timeoffset'              => 'Временска разлика',
'prefs-advancedediting'         => 'Напредне поставке',
'prefs-advancedrc'              => 'Напредне поставке',
'prefs-advancedrendering'       => 'Напредне поставке',
'prefs-advancedsearchoptions'   => 'Напредне поставке',
'prefs-advancedwatchlist'       => 'Напредне поставке',
'prefs-displayrc'               => 'Поставке приказа',
'prefs-displaysearchoptions'    => 'Поставке приказа',
'prefs-displaywatchlist'        => 'Поставке приказа',
'prefs-diffs'                   => 'Разлике',

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => 'Е-адреса је исправна',
'email-address-validity-invalid' => 'Унесите исправну е-адресу',

# User rights
'userrights'                   => 'Управљање корисничким правима',
'userrights-lookup-user'       => 'Управљај корисничким групама',
'userrights-user-editname'     => 'Унесите корисничко име:',
'editusergroup'                => 'Промени корисничке групе',
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
'userrights-nodatabase'        => 'База података $1 не постоји или није локална.',
'userrights-nologin'           => 'Морате се [[Special:UserLogin|пријавити]] са администраторским налогом да додате корисничка права.',
'userrights-notallowed'        => 'Ваш налог нема овлашћења да додаје корисника права.',
'userrights-changeable-col'    => 'Групе које можете мењати',
'userrights-unchangeable-col'  => 'Групе које не можете мењати',

# Groups
'group'               => 'Група:',
'group-user'          => 'Корисници',
'group-autoconfirmed' => 'Аутоматски потврђени корисници',
'group-bot'           => 'Ботови',
'group-sysop'         => 'Администратори',
'group-bureaucrat'    => 'Бирократе',
'group-suppress'      => 'Ревизори',
'group-all'           => '(сви)',

'group-user-member'          => 'Корисник',
'group-autoconfirmed-member' => 'Аутоматски потврђен корисник',
'group-bot-member'           => 'Бот',
'group-sysop-member'         => 'Администратор',
'group-bureaucrat-member'    => 'Бирократа',
'group-suppress-member'      => 'ревизор',

'grouppage-user'          => '{{ns:project}}:Корисници',
'grouppage-autoconfirmed' => '{{ns:project}}:Аутоматски потврђени корисници',
'grouppage-bot'           => '{{ns:project}}:Ботови',
'grouppage-sysop'         => '{{ns:project}}:Администратори',
'grouppage-bureaucrat'    => '{{ns:project}}:Бирократе',
'grouppage-suppress'      => '{{ns:project}}:Ревизори',

# Rights
'right-read'                  => 'прегледање страница',
'right-edit'                  => 'уређивање страница',
'right-createpage'            => 'прављење страница (изузев страница за разговор)',
'right-createtalk'            => 'прављење страница за разговор',
'right-createaccount'         => 'прављење нових корисничких налога',
'right-minoredit'             => 'означавање измена као мале',
'right-move'                  => 'премештање страница',
'right-move-subpages'         => 'премештање страница с њиховим подстраницама',
'right-move-rootuserpages'    => 'премештање базних корисничких страница',
'right-movefile'              => 'премештање датотека',
'right-suppressredirect'      => 'прескакање стварања преусмерења при премештању страница',
'right-upload'                => 'слање датотека',
'right-reupload'              => 'мењање постојећих датотека',
'right-reupload-own'          => 'мењање сопствених датотека',
'right-reupload-shared'       => 'мењање датотека на дељеном складишту мултимедије',
'right-upload_by_url'         => 'слање датотека преко URL адресе',
'right-purge'                 => 'чишћење кеша странице без потврде',
'right-autoconfirmed'         => 'мењање полузаштићених страница',
'right-bot'                   => 'корисник је заправо аутоматски процес (бот)',
'right-nominornewtalk'        => 'непоседовање малих измена на страницама за разговор отвара прозор за нове поруке',
'right-apihighlimits'         => 'коришћење виших граница за упите из API-ја',
'right-writeapi'              => 'писање API-ја',
'right-delete'                => 'брисање страница',
'right-bigdelete'             => 'брисање страница са великом историјом',
'right-deleterevision'        => 'брисање и враћање одређених измена страница',
'right-deletedhistory'        => 'прегледање обрисаних издања страница без текста који је везан за њих',
'right-deletedtext'           => 'приказ избрисаног текста и измена између обрисаних ревизија',
'right-browsearchive'         => 'тражење обрисаних страница',
'right-undelete'              => 'враћање обрисаних страница',
'right-suppressrevision'      => 'прегледање и враћање измена које су сакривене од стране администратора',
'right-suppressionlog'        => 'прегледање личне историје',
'right-block'                 => 'забрана мењања страница другим корисницима',
'right-blockemail'            => 'забрана слања е-порука',
'right-hideuser'              => 'забрана корисничког имена скривањем од јавности',
'right-ipblock-exempt'        => 'прескакање аутоматских, IP и забрана опсега',
'right-proxyunbannable'       => 'прескакање самосталних забрана посредника',
'right-unblockself'           => 'деблокирање самог себе',
'right-protect'               => 'измена заштићених страница и степена заштите',
'right-editprotected'         => 'уређивање заштићених страница (без могућности измене степена заштите)',
'right-editinterface'         => 'уређивање корисничког сучеља',
'right-editusercssjs'         => 'уређивање туђих CSS и јаваскрипт датотека',
'right-editusercss'           => 'уређивање туђих CSS датотека',
'right-edituserjs'            => 'уређивање туђих јаваскрипт датотека',
'right-rollback'              => 'брзо враћање измена последњег корисника који је мењао одређену страницу',
'right-markbotedits'          => 'означавање враћених страница као измене које је направио бот',
'right-noratelimit'           => 'отпорност на ограничења',
'right-import'                => 'увожење страница из других викија',
'right-importupload'          => 'увожење страница из отпремљене датотеке',
'right-patrol'                => 'означавање туђих измена као патролиране',
'right-autopatrol'            => 'самоозначавање измена као патролиране',
'right-patrolmarks'           => 'прегледање ознака за патролирање унутар скорашњих измена',
'right-unwatchedpages'        => 'прегледање списка ненадгледаних страница',
'right-trackback'             => 'слање извештаја',
'right-mergehistory'          => 'спајање историја страница',
'right-userrights'            => 'уређивање свих корисничких права',
'right-userrights-interwiki'  => 'измена права корисника на другим викијима',
'right-siteadmin'             => 'закључавање и откључавање базе података',
'right-reset-passwords'       => 'поништавање туђих лозинки',
'right-override-export-depth' => 'извожење страница заједно с повезаним до дубине петог нивоа',
'right-sendemail'             => 'слање е-порука другим корисницима',

# User rights log
'rightslog'      => 'Историја корисничких права',
'rightslogtext'  => 'Ово је историја измена корисничких права.',
'rightslogentry' => '{{GENDER:|је променио|је променила|промени}} права за члана $1 из $2 у $3',
'rightsnone'     => '(ништа)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'читање ове стране',
'action-edit'                 => 'уређујете ову страницу',
'action-createpage'           => 'прављење страница',
'action-createtalk'           => 'прављење страница за разговор',
'action-createaccount'        => 'направи налог за овог корисника',
'action-minoredit'            => 'означи ову измену малом',
'action-move'                 => 'преместите ову страницу',
'action-move-subpages'        => 'премести ову страну и њене подстране',
'action-move-rootuserpages'   => 'премести базне корисничке стране',
'action-movefile'             => 'премести ову датотеку',
'action-upload'               => 'пошаљи ову датотеку',
'action-reupload'             => 'замени постојећу датотеку',
'action-reupload-shared'      => 'замени датотеку на дељеном складишту',
'action-upload_by_url'        => 'пошаљи ову датотеку преко URL адресе',
'action-writeapi'             => 'писање API-ја',
'action-delete'               => 'обриши ову страницу',
'action-deleterevision'       => 'обриши ову ревизију',
'action-deletedhistory'       => 'прегледај обрисану историју ове странице',
'action-browsearchive'        => 'претрага обрисаних страница',
'action-undelete'             => 'врати ову страну',
'action-suppressrevision'     => 'прегледај и врати ову скривену ревизију',
'action-suppressionlog'       => 'прегледај ову приватну историју',
'action-block'                => 'блокирај даље измене овог корисника',
'action-protect'              => 'мењање нивоа заштите за ову страну',
'action-import'               => 'увези ову страну са друге Вики',
'action-importupload'         => 'увези ову страну преко отпремљене датотеке',
'action-patrol'               => 'означавање туђих измена као патролиране',
'action-autopatrol'           => 'самоозначавање измена као патролиране',
'action-unwatchedpages'       => 'преглед списка ненадгледаних страница',
'action-trackback'            => 'пошаљи извештај',
'action-mergehistory'         => 'споји историју ове странице',
'action-userrights'           => 'измени сва корисничка права',
'action-userrights-interwiki' => 'измени права корисника са других Викија',
'action-siteadmin'            => 'закључавање или откључавање базе података',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|измена|измене|измена}}',
'recentchanges'                     => 'Скорашње измене',
'recentchanges-legend'              => 'Поставке скорашњих измена',
'recentchangestext'                 => 'Пратите скорашње измене на овој страници.',
'recentchanges-feed-description'    => 'Пратите скорашње измене уз помоћ овог довода.',
'recentchanges-label-newpage'       => 'Нова страница',
'recentchanges-label-minor'         => 'Мала измена',
'recentchanges-label-bot'           => 'Ову измену је направио бот',
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
'rclinks'                           => 'Прикажи последњих $1 измена у року од $2 дана<br />$3',
'diff'                              => 'разл',
'hist'                              => 'ист',
'hide'                              => 'сакриј',
'show'                              => 'прикажи',
'minoreditletter'                   => ' м',
'newpageletter'                     => 'Н',
'boteditletter'                     => 'б',
'number_of_watching_users_pageview' => '[$1 надгледа {{PLURAL:$1|корисник|корисника|корисника}}]',
'rc_categories'                     => 'Ограничи на категорије (раздвоји са "|")',
'rc_categories_any'                 => 'Све',
'newsectionsummary'                 => '/* $1 */ нови одељак',
'rc-enhanced-expand'                => 'Прикажи детаље (захтева јаваскрипту)',
'rc-enhanced-hide'                  => 'Сакриј детаље',

# Recent changes linked
'recentchangeslinked'          => 'Сродне измене',
'recentchangeslinked-feed'     => 'Сродне измене',
'recentchangeslinked-toolbox'  => 'Сродне измене',
'recentchangeslinked-title'    => 'Сродне измене за „$1“',
'recentchangeslinked-noresult' => 'Нема измена на повезаним страницама за одабрани период.',
'recentchangeslinked-summary'  => "Ова посебна страница показује списак последњих промена на страницама које су повезане (или чланови одређене категорије).
Странице са [[Special:Watchlist|вашег списка надгледања]] су '''подебљане'''.",
'recentchangeslinked-page'     => 'Назив странице:',
'recentchangeslinked-to'       => 'приказивање измена према странама повезаних са датом страном',

# Upload
'upload'                      => 'Пошаљи датотеку',
'uploadbtn'                   => 'Пошаљи датотеку',
'reuploaddesc'                => 'Врати ме на образац за слање',
'upload-tryagain'             => 'Пошаљи измењени опис датотеке',
'uploadnologin'               => 'Нисте пријављени',
'uploadnologintext'           => 'Морате бити [[Special:UserLogin|пријављени]] да бисте слали датотеке.',
'upload_directory_missing'    => 'Фасцикла за слање ($1) недостаје и не може се направити од стране сервера.',
'upload_directory_read_only'  => 'Сервер не може да пише по фасцикли за слање ($1).',
'uploaderror'                 => 'Грешка при слању',
'upload-recreate-warning'     => "'''Упозорење: датотека тог назива је обрисана или премештена.'''

Извештај о брисању и премештању се налази испод:",
'uploadtext'                  => "Користите образац испод да бисте послали датотеке.
Постојеће датотеке можете пронаћи у [[Special:FileList|списку послатих датотека]], поновна слања су записана у [[Special:Log/upload|историји слања]], а брисања у [[Special:Log/delete|историји брисања]].

Датотеку додајете на жељену страницу користећи следеће обрасце:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Слика.jpg]]</nowiki></tt>''' за коришћење целог издања датотеке
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Слика.png|200п|мини|лево|опис]]</nowiki></tt>''' за коришћење широке уоквирене слике на левој страни, величине 200 пиксела, заједно с описом.
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Датотека.ogg]]</nowiki></tt>''' за директно повезивање до датотеке без приказивања",
'upload-permitted'            => 'Дозвољене врсте датотека: $1.',
'upload-preferred'            => 'Пожељне врсте датотека су: $1.',
'upload-prohibited'           => 'Забрањене врсте датотека су: $1.',
'uploadlog'                   => 'историја слања',
'uploadlogpage'               => 'Историја слања',
'uploadlogpagetext'           => 'Испод је списак скорашњих отпремања.
Погледајте [[Special:NewFiles|галерију нових датотека]] за лепши преглед.',
'filename'                    => 'Назив датотеке',
'filedesc'                    => 'Сажетак',
'fileuploadsummary'           => 'Сажетак:',
'filereuploadsummary'         => 'Измене датотеке:',
'filestatus'                  => 'Статус ауторског права:',
'filesource'                  => 'Извор:',
'uploadedfiles'               => 'Послате датотеке',
'ignorewarning'               => 'Игнориши упозорење и сними датотеку',
'ignorewarnings'              => 'Занемари сва упозорења',
'minlength1'                  => 'Називи датотека морају имати најмање један знак.',
'illegalfilename'             => 'Датотека „$1“ садржи знакове који нису дозвољени у називима страница.
Промените назив датотеке и поново је пошаљите.',
'badfilename'                 => 'Назив датотеке је промењен у „$1“.',
'filetype-mime-mismatch'      => 'Проширење „.$1“ не одговара препознатој врсти MIME датотеке ($2).',
'filetype-badmime'            => 'Није дозвољено слати датотеке MIME типа „$1“.',
'filetype-bad-ie-mime'        => 'Ова датотека се не може отпремити зато што би је Интернет Експлорер уочио као „$1“, а то је онемогућен и потенцијално опасан тип датотеке.',
'filetype-unwanted-type'      => '„.$1“ је непожељан тип датотеке.
{{PLURAL:$3|Пожељна врста датотеке је|Пожељне врсте датотека су}} $2.',
'filetype-banned-type'        => '\'\'\'".$1"\'\'\' {{PLURAL:$4|је забрањена врста датотеке|су забрањене врсте датотека}}.
{{PLURAL:$3|Дозвољена врста датотеке је|Дозвољене врсте датотека су}} $2.',
'filetype-missing'            => 'Ова датотека нема екстензију.',
'empty-file'                  => 'Послата датотека је празна.',
'file-too-large'              => 'Послата датотека је превелика.',
'filename-tooshort'           => 'Назив датотеке је прекратак.',
'filetype-banned'             => 'Врста датотеке је забрањена.',
'verification-error'          => 'Ова датотека није прошла проверу.',
'hookaborted'                 => 'Измена је одбачена од стране куке прикључка.',
'illegal-filename'            => 'Назив датотеке није дозвољен.',
'overwrite'                   => 'Мењање постојеће датотеке није дозвољено.',
'unknown-error'               => 'Дошло је до непознате грешке.',
'tmp-create-error'            => 'Привремена датотека није направљена.',
'tmp-write-error'             => 'Грешка при писању привремене датотеке.',
'large-file'                  => 'Препоручљиво је да датотеке не буду веће од $1; ова датотека је $2.',
'largefileserver'             => 'Ова датотека прелази ограничење величине.',
'emptyfile'                   => 'Датотека коју сте послали делује празно.
Узрок може бити грешка у називу датотеке.
Проверите да ли заиста желите да је пошаљете.',
'fileexists'                  => "Датотека с овим називом већ постоји. Погледајте '''<tt>[[:$1]]</tt>''' ако нисте сигурни да ли желите да је промените.
[[$1|thumb]]",
'filepageexists'              => "Страница описа овог фајла је направљена као '''<tt>[[:$1]]</tt>''', иако сам фајл не постоји.
Опис кога уносите се дакле неће појавити на страници описа.
Да бисте учинили да се Ваш опис ипак појави, требало би да га измените ручно.
[[$1|преглед]]",
'fileexists-extension'        => "Датотека са сличним именом већ постоји: [[$2|thumb]]
* Назив датотеке коју шаљете: '''<tt>[[:$1]]</tt>'''
* Назив постојеће датотеке: '''<tt>[[:$2]]</tt>'''
Изаберите другачије име.",
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
'file-exists-duplicate'       => 'Ово је дупликат {{PLURAL:$1|следеће датотеке|следећих датотека}}:',
'file-deleted-duplicate'      => 'Датотека идентична овој ([[:$1]]) је претходно обрисана.
Погледајте историју брисања датотека пре поновног слања.',
'uploadwarning'               => 'Упозорење при слању',
'uploadwarning-text'          => 'Измените опис датотеке и покушајте поново.',
'savefile'                    => 'Сачувај датотеку',
'uploadedimage'               => '{{GENDER:|је послао|је послала|је послао}} „[[$1]]“',
'overwroteimage'              => '{{GENDER:|је послао|је послала|посла}} ново издање „[[$1]]“',
'uploaddisabled'              => 'Слање је онемогућено.',
'copyuploaddisabled'          => 'Отпремање путем URL адресе је онемогућено.',
'uploadfromurl-queued'        => 'Слање је стављено на списак чекања.',
'uploaddisabledtext'          => 'Слање је онемогућено.',
'php-uploaddisabledtext'      => 'Слање датотека је онемогућено у PHP-у.
Проверите file_uploads поставке.',
'uploadscripted'              => 'Ова датотека садржи HTML или кôд скрипте који може да се погрешно протумачи од стране прегледача.',
'uploadvirus'                 => 'Датотека садржи вирус!
Детаљи: $1',
'uploadjava'                  => 'Датотека је формата ZIP који садржи јава .class елемент.
Слање јава датотека није дозвољено јер оне могу изазвати заобилажење сигурносних ограничења.',
'upload-source'               => 'Изворна датотека',
'sourcefilename'              => 'Изворна датотека:',
'sourceurl'                   => 'Изворна адреса:',
'destfilename'                => 'Назив:',
'upload-maxfilesize'          => 'Највећа величина датотеке: $1',
'upload-description'          => 'Опис датотеке',
'upload-options'              => 'Поставке слања',
'watchthisupload'             => 'Надгледај ову датотеку',
'filewasdeleted'              => 'Датотека с овим називом је раније отпремљена, али је обрисана.
Требало би да проверите $1 пре него што наставите с отпремањем.',
'upload-wasdeleted'           => "'''Пажња: Шаљете фајл који је претходно обрисан.'''

Размислите да ли сте сигурни да желите послати овај фајл.
Разлог брисања овог фајла раније је:",
'filename-bad-prefix'         => "Име овог фајла почиње са '''\"\$1\"''', што није описно име, најчешће је назван аутоматски са дигиталним фотоапаратом. Молимо изаберите описније име за ваш фајл.",
'upload-success-subj'         => 'Успешно слање',
'upload-success-msg'          => 'Слање из [$2] је завршено. Доступно је овде: [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'Грешка при слању',
'upload-failure-msg'          => 'Дошло је до проблема при отпремању из [$2]:

$1',
'upload-warning-subj'         => 'Упозорење при слању',
'upload-warning-msg'          => 'Дошло је до грешке при слању из [$2]. Вратите се на [[Special:Upload/stash/$1|страницу за слање датотека]] да бисте решили проблем.',

'upload-proto-error'        => 'Неисправни протокол',
'upload-proto-error-text'   => 'Слање екстерних фајлова захтева УРЛове који почињу са <code>http://</code> или <code>ftp://</code>.',
'upload-file-error'         => 'Унутрашња грешка',
'upload-file-error-text'    => 'Дошло је до интерне грешке при покушају отварања привременог фајла на серверу.
Контактирајте [[Special:ListUsers/sysop|администратора]].',
'upload-misc-error'         => 'Непозната грешка при слању фајла',
'upload-misc-error-text'    => 'Непозната грешка при слању фајла. Проверите да ли је УРЛ исправан и покушајте поново. Ако проблем остане, контактирајте систем администратора.',
'upload-too-many-redirects' => 'URL је садржао превише преусмерења',
'upload-unknown-size'       => 'Непозната величина',
'upload-http-error'         => 'Дошло је до HTTP грешке: $1',

# ZipDirectoryReader
'zip-file-open-error' => 'Дошло је до грешке при отварању датотеке за проверу ZIP архиве.',
'zip-wrong-format'    => 'Наведена датотека није формата ZIP.',
'zip-bad'             => 'Датотека је оштећена или је нечитљива ZIP датотека.
Не може се исправно проверити у вези са сигурношћу.',
'zip-unsupported'     => 'Датотека је формата ZIP који користи могућности које не подржава Медијавики.
Она се не може исправно проверити у вези са сигурношћу.',

# Special:UploadStash
'uploadstash'          => 'Тајно складиште',
'uploadstash-summary'  => 'Ова страница пружа приступ датотекама које су отпремљене (или се отпремају), а још увек нису објављене. Ове датотеке су видљиве само кориснику који га је отпремио.',
'uploadstash-clear'    => 'Очисти сакривене датотеке',
'uploadstash-nofiles'  => 'Немате сакривене датотеке.',
'uploadstash-badtoken' => 'Извршење дате радње није успело. Разлог томе може бити истек времена за уређивање. Покушајте поново.',
'uploadstash-errclear' => 'Чишћење датотека је неуспешно.',
'uploadstash-refresh'  => 'Освежи списак датотека',

# img_auth script messages
'img-auth-accessdenied' => 'Приступ је одбијен',
'img-auth-nopathinfo'   => 'Недостаје PATH_INFO.
Ваш сервер није подешен да прослеђује податке.
Могуће да је заснован на CGI-ју који не подржава img_auth.
Погледајте http://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir'     => 'Затражена путања није у подешеној фасцикли за слање.',
'img-auth-badtitle'     => 'Стварање исправног наслова за „$1“ није успело.',
'img-auth-nologinnWL'   => 'Нисте пријављени и „$1“ није у белој листи.',
'img-auth-nofile'       => 'Датотека „$1“ не постоји.',
'img-auth-isdir'        => 'Покушавате да приступите фасцикли „$1“.
Дозвољен је само приступ датотекама.',
'img-auth-streaming'    => 'Учитавање „$1“.',
'img-auth-public'       => 'Сврха img_auth.php је да прослеђује датотеке из приватних викија.
Овај вики је постављен као јавни.
Ради сигурности, img_auth.php је онемогућен.',
'img-auth-noread'       => 'Корисник нема приступ за читање „$1“.',

# HTTP errors
'http-invalid-url'      => 'Неисправан URL: $1',
'http-invalid-scheme'   => 'URL адресе са „$1“ шемом нису подржане.',
'http-request-error'    => 'HTTP захтев није прошао због непознате грешке.',
'http-read-error'       => 'HTTP грешка при читању.',
'http-timed-out'        => 'HTTP захтев је прекорачио време за испуњење.',
'http-curl-error'       => 'Грешка при отварању URL: $1',
'http-host-unreachable' => 'URL адреса је недоступна.',
'http-bad-status'       => 'Дошло је до проблема при HTTP захтеву: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL адреса је недоступна',
'upload-curl-error6-text'  => 'URL адреса коју сте унели није доступна.
Проверите да ли сте је добро унели и да ли сајт ради.',
'upload-curl-error28'      => 'Слање је истекло.',
'upload-curl-error28-text' => 'Сајту је требало превише времена да одговори. Проверите да ли сајт ради, или сачекајте мало и покушајте поново.',

'license'            => 'Лиценца:',
'license-header'     => 'Лиценца:',
'nolicense'          => 'није изабрано',
'license-nopreview'  => '(приказ није доступан)',
'upload_source_url'  => ' (исправна и јавно доступна URL адреса)',
'upload_source_file' => ' (датотека на вашем рачунару)',

# Special:ListFiles
'listfiles-summary'     => 'Ова страница приказује све послате датотеке.
Према подразумеваним поставкама, најновије датотеке су приказане на врху списка.
Кликом на заглавље колоне мења се начин сврставања.',
'listfiles_search_for'  => 'Назив датотеке:',
'imgfile'               => 'датотека',
'listfiles'             => 'Списак датотека',
'listfiles_thumb'       => 'Умањени приказ',
'listfiles_date'        => 'Датум',
'listfiles_name'        => 'Име',
'listfiles_user'        => 'Корисник',
'listfiles_size'        => 'Величина',
'listfiles_description' => 'Опис',
'listfiles_count'       => 'Издања',

# File description page
'file-anchor-link'          => 'Датотека',
'filehist'                  => 'Историја датотеке',
'filehist-help'             => 'Кликните на датум/време да видите тадашње издање датотеке.',
'filehist-deleteall'        => 'обриши све',
'filehist-deleteone'        => 'обриши',
'filehist-revert'           => 'врати',
'filehist-current'          => 'тренутно',
'filehist-datetime'         => 'Датум/време',
'filehist-thumb'            => 'Умањени приказ',
'filehist-thumbtext'        => 'Умањени приказ за издање од $1',
'filehist-nothumb'          => 'Без умањеног приказа',
'filehist-user'             => 'Корисник',
'filehist-dimensions'       => 'Димензије',
'filehist-filesize'         => 'Величина датотеке',
'filehist-comment'          => 'Коментар',
'filehist-missing'          => 'Датотека недостаје',
'imagelinks'                => 'Везе',
'linkstoimage'              => '{{PLURAL:$1|Следећа страница користи|$1 Следеће странице користе}} овај фајл:',
'linkstoimage-more'         => 'Више од $1 {{PLURAL:$1|странице се веше|страница се вежу}} за овај фајл.
Следећи списак показује странице које се вежу за овај фајл
[[Special:WhatLinksHere/$2|Потпуни списак]] је доступан такође.',
'nolinkstoimage'            => 'Нема страница које користе овај фајл.',
'morelinkstoimage'          => 'Види [[Special:WhatLinksHere/$1|више веза]] према овом фајлу.',
'redirectstofile'           => '{{PLURAL:$1|Следећа датотека се преусмерава|Следеће $1 датотеке се преусмеравају|Следећих $1 датотека се преусмерава}} на ову датотеку:',
'duplicatesoffile'          => 'Следећи {{PLURAL:$1|фајл је дупликат|$1 фајла су дупликати|$1 фајлова су дупликати}} овог фајла ([[Special:FileDuplicateSearch/$2|више детаља]]):',
'sharedupload'              => 'Ова датотека је из $1 и може се користити на другим пројектима.',
'sharedupload-desc-there'   => 'Ова датотека је са $1 и могу је користити други пројекти.
Погледајте [$2 страницу за опис датотека] за више информација.',
'sharedupload-desc-here'    => 'Ова датотека је из $1 и може се користити на другим пројектима.
Опис на [$2 страници датотеке] је приказан испод.',
'filepage-nofile'           => 'Не постоји датотека под тим именом.',
'filepage-nofile-link'      => 'Не постоји фајл са овим именом, али га можете [$1 послати].',
'uploadnewversion-linktext' => 'Пошаљи ново издање ове датотеке',
'shared-repo-from'          => 'од $1',
'shared-repo'               => 'дељено складиште',

# File reversion
'filerevert'                => 'Врати $1',
'filerevert-legend'         => 'Врати датотеку',
'filerevert-intro'          => "Враћате '''[[Media:$1|$1]]''' на [$4 верзију од $3, $2].",
'filerevert-comment'        => 'Разлог:',
'filerevert-defaultcomment' => 'Враћено на издање од $1 у $2',
'filerevert-submit'         => 'Врати',
'filerevert-success'        => "'''[[Media:$1|$1]]''' је враћен на [$4 издање од $2 у $3].",
'filerevert-badversion'     => 'Не постоји претходно локално издање датотеке с унесеним временом.',

# File deletion
'filedelete'                  => 'Обриши $1',
'filedelete-legend'           => 'Обриши датотеку',
'filedelete-intro'            => "На путу сте да обришете фајл '''[[Media:$1|$1]]''' заједно са његовом историјом.",
'filedelete-intro-old'        => "Управо ћете да обришете '''[[Media:$1|$1]]''' издање од [$4 $2 у $3].",
'filedelete-comment'          => 'Разлог:',
'filedelete-submit'           => 'Обриши',
'filedelete-success'          => "Датотека '''$1''' је обрисана.",
'filedelete-success-old'      => "Издање '''[[Media:$1|$1]]''' од $2, $3 је обрисано.",
'filedelete-nofile'           => "'''$1''' не постоји.",
'filedelete-nofile-old'       => "Не постоји ускладиштено издање датотеке '''$1''' с датим особинама.",
'filedelete-otherreason'      => 'Други/додатни разлог:',
'filedelete-reason-otherlist' => 'Други разлог',
'filedelete-reason-dropdown'  => '*Најчешћи разлози брисања
** Кршење ауторских права
** Дупликат',
'filedelete-edit-reasonlist'  => 'Уреди разлоге за брисање',
'filedelete-maintenance'      => 'Брисање и враћање фајлова је темпорално онемогућено због одржавања.',

# MIME search
'mimesearch'         => 'MIME претрага',
'mimesearch-summary' => 'Ова страница омогућава филтерисање фајлова за свој MIME-тип. Улаз: contenttype/subtype, тј. <tt>image/jpeg</tt>.',
'mimetype'           => 'MIME тип:',
'download'           => 'Преузми',

# Unwatched pages
'unwatchedpages' => 'Ненадгледане странице',

# List redirects
'listredirects' => 'Списак преусмерења',

# Unused templates
'unusedtemplates'     => 'Неискоришћени шаблони',
'unusedtemplatestext' => 'Ова страница наводи све странице у {{ns:template}} именском простору које нису укључене ни на једној другој страници.
Не заборавите да проверите остале повезнице ка шаблонима пре него што их обришете.',
'unusedtemplateswlh'  => 'остале везе',

# Random page
'randompage'         => 'Случајна страница',
'randompage-nopages' => 'Нема страница у {{PLURAL:$2|следећем именском простору|следећим именским просторима}}: $1.',

# Random redirect
'randomredirect'         => 'Случајно преусмерење',
'randomredirect-nopages' => 'Нема преусмерења у именском простору „$1”.',

# Statistics
'statistics'                   => 'Статистика',
'statistics-header-pages'      => 'Странице',
'statistics-header-edits'      => 'Измене',
'statistics-header-views'      => 'Прикажи статистику',
'statistics-header-users'      => 'Корисници',
'statistics-header-hooks'      => 'Остало',
'statistics-articles'          => 'Страница са садржајем',
'statistics-pages'             => 'Страница',
'statistics-pages-desc'        => 'Све странице на викију, укључујући странице за разговор, преусмерења итд.',
'statistics-files'             => 'Послато датотека',
'statistics-edits'             => 'Број измена откад постоји {{SITENAME}}',
'statistics-edits-average'     => 'Просечан број измена по страници',
'statistics-views-total'       => 'Укупан број прегледа',
'statistics-views-total-desc'  => 'Прегледи непостојећих и посебних страница нису укључени',
'statistics-views-peredit'     => 'Прегледи по измени',
'statistics-users'             => 'Регистровани корисници ([[Special:ListUsers|списак чланова]])',
'statistics-users-active'      => 'Активни корисници',
'statistics-users-active-desc' => 'Корисници који су извршили бар једну радњу у року од {{PLURAL:$1|један дан|$1 дана|$1 дана}}',
'statistics-mostpopular'       => 'Најпосећеније странице',

'disambiguations'      => 'Странице за вишезначне одреднице',
'disambiguationspage'  => 'Template:Вишезначна одредница',
'disambiguations-text' => "Следеће странице имају везе ка '''вишезначним одредницама'''. Потребно је да упућују на одговарајући чланак.

Страница се сматра вишезначном одредницом ако користи шаблон који је упућен са странице [[MediaWiki:Disambiguationspage]].",

'doubleredirects'                   => 'Двострука преусмерења',
'doubleredirectstext'               => 'Ова страница показује списак страница које преусмеравају на друге странице преусмерења.
Сваки ред садржи везе према првом и другом преусмерењу, као и циљану страницу другог преусмерења, која је обично „прави“ чланак, на кога прво преусмерење треба да показује.
<del>Прецртани уноси</del> су већ решени.',
'double-redirect-fixed-move'        => '[[$1]] је премештен.
Преусмерење је сада на [[$2]].',
'double-redirect-fixed-maintenance' => 'Исправљање двоструких преусмерења из [[$1]] у [[$2]].',
'double-redirect-fixer'             => 'Исправљач преусмерења',

'brokenredirects'        => 'Покварена преусмерења',
'brokenredirectstext'    => 'Следећа преусмерења повезују на непостојеће стране:',
'brokenredirects-edit'   => 'уреди',
'brokenredirects-delete' => 'обриши',

'withoutinterwiki'         => 'Странице без језичких веза',
'withoutinterwiki-summary' => 'Следеће странице не вежу ка другим језицима (међувики):',
'withoutinterwiki-legend'  => 'префикс',
'withoutinterwiki-submit'  => 'Прикажи',

'fewestrevisions' => 'Странице с најмање измена',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|бајт|бајтова}}',
'ncategories'             => '$1 {{PLURAL:$1|категорија|категорије}}',
'nlinks'                  => '$1 {{PLURAL:$1|веза|везе|веза}}',
'nmembers'                => '$1 {{PLURAL:$1|члан|члана|чланова}}',
'nrevisions'              => '$1 {{PLURAL:$1|ревизија|ревизије}}',
'nviews'                  => '$1 {{PLURAL:$1|преглед|прегледа|прегледа}}',
'nimagelinks'             => 'Користи се на $1 {{PLURAL:$1|страници|странице|страница}}',
'ntransclusions'          => 'користи се на $1 {{PLURAL:$1|страници|странице|страница}}',
'specialpage-empty'       => 'Нема резултата за овај извештај.',
'lonelypages'             => 'Сирочићи',
'lonelypagestext'         => 'Следеће странице нису повезане са других страница на овом викију.',
'uncategorizedpages'      => 'Странице без категорије',
'uncategorizedcategories' => 'Категорије без категорија',
'uncategorizedimages'     => 'Датотеке без категорија',
'uncategorizedtemplates'  => 'Шаблони без категорија',
'unusedcategories'        => 'Некоришћене категорије',
'unusedimages'            => 'Некоришћене датотеке',
'popularpages'            => 'Популарне странице',
'wantedcategories'        => 'Тражене категорије',
'wantedpages'             => 'Тражене странице',
'wantedpages-badtitle'    => 'Неиспаван наслов у низу резултата: $1',
'wantedfiles'             => 'Тражене датотеке',
'wantedtemplates'         => 'Тражени шаблони',
'mostlinked'              => 'Највише повезане стране',
'mostlinkedcategories'    => 'Чланци са највише категорија',
'mostlinkedtemplates'     => 'Најповезанији шаблони',
'mostcategories'          => 'Чланци са највише категорија',
'mostimages'              => 'Највише повезане датотеке',
'mostrevisions'           => 'Странице с највише измена',
'prefixindex'             => 'Све странице с префиксима',
'shortpages'              => 'Кратке странице',
'longpages'               => 'Дугачке странице',
'deadendpages'            => 'Странице без унутрашњих веза',
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
'listusers-creationsort'  => 'Поређај по датуму стварања',
'usereditcount'           => '$1 {{PLURAL:$1|измена|измена}}',
'usercreated'             => 'Створено $1 у $2',
'newpages'                => 'Нове странице',
'newpages-username'       => 'Корисничко име:',
'ancientpages'            => 'Најстарији чланци',
'move'                    => 'премести',
'movethispage'            => 'премести ову страницу',
'unusedimagestext'        => 'Следећи фајлови постоје, али нису угнеждени ни у једну страницу.
Обратите пажњу да се други веб сајтови могу повезивати на слику директним УРЛ-ом, и тако могу још увек бити приказани овде упркос чињеници да више нису у активној употреби.',
'unusedcategoriestext'    => 'Наредне стране категорија постоје иако их ни један други чланак или категорија не користе.',
'notargettitle'           => 'Нема одредишта',
'notargettext'            => 'Нисте навели циљну страницу или корисника на коме би се извела ова радња.',
'nopagetitle'             => 'Не постоји таква страница',
'nopagetext'              => 'Тражена страница не постоји.',
'pager-newer-n'           => '{{PLURAL:$1|новији 1|новија $1|новијих $1}}',
'pager-older-n'           => '{{PLURAL:$1|старији 1|старија $1|старијих $1}}',
'suppress'                => 'Ревизор',
'querypage-disabled'      => 'Ова посебна страница је онемогућена ради побољшања перформанси.',

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
'logempty'             => 'Историја је празна.',
'log-title-wildcard'   => 'тражи наслове који почињу с овим текстом',

# Special:AllPages
'allpages'          => 'Све странице',
'alphaindexline'    => '$1 у $2',
'nextpage'          => 'Следећа страница ($1)',
'prevpage'          => 'Претходна страница ($1)',
'allpagesfrom'      => 'Прикажи странице почетно са:',
'allpagesto'        => 'Приказује странице које се завршавају са:',
'allarticles'       => 'Све странице',
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
'special-categories-sort-count' => 'поређај по броју',
'special-categories-sort-abc'   => 'поређај по азбучном реду',

# Special:DeletedContributions
'deletedcontributions'             => 'Обрисани прилози',
'deletedcontributions-title'       => 'Обрисани прилози',
'sp-deletedcontributions-contribs' => 'прилози',

# Special:LinkSearch
'linksearch'       => 'Спољашње везе',
'linksearch-pat'   => 'Образац претраге:',
'linksearch-ns'    => 'Именски простор:',
'linksearch-ok'    => 'Претрага',
'linksearch-text'  => 'Џокери попут „*.wikipedia.org“ могу се користити.<br />
Подржани протоколи: <tt>$1</tt>',
'linksearch-line'  => 'страница $1 је повезана са странице $2',
'linksearch-error' => 'Џокери се могу појавити само на почетку имена хоста.',

# Special:ListUsers
'listusersfrom'      => 'Прикажи кориснике почевши од:',
'listusers-submit'   => 'Прикажи',
'listusers-noresult' => 'Није пронађен корисник.',
'listusers-blocked'  => '(забрањен приступ)',

# Special:ActiveUsers
'activeusers'            => 'Списак активних корисника',
'activeusers-intro'      => 'Ово је списак корисника који су били активни {{PLURAL:$1|последњи $1 дан|последња $1 дана|последњих $1 дана}}.',
'activeusers-count'      => '$1 {{PLURAL:$1|измена|измене|измена}} {{PLURAL:$3|за последњи дан|у последња $3 дана|у последњих $3 дана}}',
'activeusers-from'       => 'Прикажи кориснике почевши од:',
'activeusers-hidebots'   => 'Сакриј ботове',
'activeusers-hidesysops' => 'Сакриј администраторе',
'activeusers-noresult'   => 'Корисник није пронађен.',

# Special:Log/newusers
'newuserlogpage'              => 'Историја отварања налога',
'newuserlogpagetext'          => 'Ово је историја стварања корисника.',
'newuserlog-byemail'          => 'Лозинка је послата е-поштом',
'newuserlog-create-entry'     => 'Нови корисник',
'newuserlog-create2-entry'    => '{{GENDER:|је отворио|је отворила|отвори}} налог за $1',
'newuserlog-autocreate-entry' => 'Налог је аутоматски направљен.',

# Special:ListGroupRights
'listgrouprights'                      => 'Права корисничких група',
'listgrouprights-summary'              => 'Следи списак корисничких група на овом викију, заједно с правима приступа.
Погледајте [[{{MediaWiki:Listgrouprights-helppage}}|детаљније информације]] о појединачним правима.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Додељено право</span>
* <span class="listgrouprights-revoked">Укинуто право</span>',
'listgrouprights-group'                => 'Група',
'listgrouprights-rights'               => 'Права',
'listgrouprights-helppage'             => 'Help:Права',
'listgrouprights-members'              => '(списак чланова)',
'listgrouprights-addgroup'             => 'додаје {{PLURAL:$2|следећу групу|следеће групе}}: $1',
'listgrouprights-removegroup'          => 'брише {{PLURAL:$2|следећу групу|следеће групе}}: $1',
'listgrouprights-addgroup-all'         => 'додавање свих група',
'listgrouprights-removegroup-all'      => 'брисање свих група',
'listgrouprights-addgroup-self'        => 'додавање {{PLURAL:$2|групе|група}} на свој налог: $1',
'listgrouprights-removegroup-self'     => 'уклањање {{PLURAL:$2|групе|група}} из налога: $1',
'listgrouprights-addgroup-self-all'    => 'Додај све групе у свој налог',
'listgrouprights-removegroup-self-all' => 'Уклони све групе са свог налога',

# E-mail user
'mailnologin'          => 'Нема адресе за слање',
'mailnologintext'      => 'Морате бити [[Special:UserLogin|пријављени]] и имати исправну адресу е-поште у вашим [[Special:Preferences|подешавањима]]
да бисте слали е-пошту другим корисницима.',
'emailuser'            => 'Пошаљи е-поруку кориснику',
'emailpage'            => 'Слање е-поруке',
'emailpagetext'        => 'Можете користити овај формулар да пошаљете е-пошту овом кориснику.
Адреса е-поште коју сте ви унели у својим [[Special:Preferences|корисничким подешавањима]] ће се појавити као "From" адреса поруке, тако да ће прималац моћи директно да Вам одговори.',
'usermailererror'      => 'Објекат поште је вратио грешку:',
'defemailsubject'      => '{{SITENAME}} е-пошта',
'usermaildisabled'     => 'Корисникова е-пошта је онемогућена',
'usermaildisabledtext' => 'Не можете да шаљете е-поруке другим корисницима ове енциклопедије.',
'noemailtitle'         => 'Нема е-адресе',
'noemailtext'          => 'Овај корисник није навео исправну адресу е-поште.',
'nowikiemailtitle'     => 'Није омогућено слање мејлова',
'nowikiemailtext'      => 'Овај корисник је онемогућио слање имејлова од других корисника.',
'email-legend'         => 'Пошаљите мејл другом кориснику на {{SITENAME}}',
'emailfrom'            => 'Од:',
'emailto'              => 'За:',
'emailsubject'         => 'Наслов:',
'emailmessage'         => 'Порука:',
'emailsend'            => 'Пошаљи',
'emailccme'            => 'Пошаљи ми копију моје поруке у моје сандуче е-поште.',
'emailccsubject'       => 'Копија ваше поруке на $1: $2',
'emailsent'            => 'Порука послата',
'emailsenttext'        => 'Ваша порука је послата електронском поштом.',
'emailuserfooter'      => 'Овај имејл посла $1 сараднику $2 помоћу "Пошаљи имејл" функције на сајту "{{SITENAME}}".',

# User Messenger
'usermessage-summary' => 'Слање системске поруке.',
'usermessage-editor'  => 'Системски гласник',

# Watchlist
'watchlist'            => 'Списак надгледања',
'mywatchlist'          => 'Списак надгледања',
'watchlistfor2'        => 'За $1 $2',
'nowatchlist'          => 'Немате ништа на свом списку надгледања.',
'watchlistanontext'    => 'Морате бити $1 да бисте гледали или мењали ставке на вашем списку надгледања.',
'watchnologin'         => 'Нисте пријављени',
'watchnologintext'     => 'Морате бити [[Special:UserLogin|пријављени]] да бисте мењали списак надгледања.',
'addedwatch'           => 'Додато на списак надгледања',
'addedwatchtext'       => 'Страница „[[:$1]]“ је додата на ваш [[Special:Watchlist|списак надгледања]].
Будуће измене ове странице и њене странице за разговор биће наведене овде, а страница ће бити <b>подебљана</b> у [[Special:RecentChanges|списку скорашњих измена]] да би се лакше уочила.

Уколико будете желели да уклоните страницу са списка надгледања, кликните опет на звездицу у горњој палети.',
'removedwatch'         => 'Уклоњено са списка надгледања',
'removedwatchtext'     => 'Страница „[[:$1]]“ је уклоњена с вашег [[Special:Watchlist|списка надгледања]].',
'watch'                => 'Надгледај',
'watchthispage'        => 'Надгледај ову страницу',
'unwatch'              => 'Прекини надгледање',
'unwatchthispage'      => 'Прекини надгледање',
'notanarticle'         => 'Није чланак',
'notvisiblerev'        => 'Ревизија је обрисана',
'watchnochange'        => 'Ништа што надгледате није промењено у приказаном времену.',
'watchlist-details'    => '{{PLURAL:$1|$1 страница|$1 странице|$1 страница}} на вашем списку надгледања, не рачунајући странице за разговор.',
'wlheader-enotif'      => '* Обавештавање е-поштом је омогућено.',
'wlheader-showupdated' => "* Странице које су измењене од када сте их последњи пут посетили су приказане '''подебљано'''",
'watchmethod-recent'   => 'проверавам има ли надгледаних страница у скорашњим изменама',
'watchmethod-list'     => 'проверавам има ли скорашњих измена у надгледаним страницама',
'watchlistcontains'    => 'Ваш списак надгледања садржи $1 {{PLURAL:$1|страницу|странице|страница}}.',
'iteminvalidname'      => 'Проблем са ставком „$1“. Неисправан назив.',
'wlnote'               => "Испод {{PLURAL:$1|је последња измена|су последње '''$1''' измене|је последњих '''$1''' измена}} у року од {{PLURAL:$2|сат времена|'''$2''' сата|'''$2''' сати}}.",
'wlshowlast'           => 'Прикажи последњих $1 сати $2 дана $3',
'watchlist-options'    => 'Опције списка надгледања',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Надгледање…',
'unwatching' => 'Прекидање надгледања…',

'enotif_mailer'                => '{{SITENAME}} пошта обавештења',
'enotif_reset'                 => 'Означи све странице као посећене',
'enotif_newpagetext'           => 'Ово је нова страница.',
'enotif_impersonal_salutation' => '{{SITENAME}} корисник',
'changed'                      => 'измењена',
'created'                      => 'направљена',
'enotif_subject'               => '{{SITENAME}} страница $PAGETITLE је $CHANGEDORCREATED од стране $PAGEEDITOR',
'enotif_lastvisited'           => 'Погледајте $1 за све промене од ваше последње посете.',
'enotif_lastdiff'              => 'Погледајте $1 да видите ову измену.',
'enotif_anon_editor'           => 'анониман корисник $1',
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
'historywarning'         => "'''Упозорење:''' Страница коју желите да обришете има историју са приближно $1 {{PLURAL:$1|ревизијом|ревизија}}:",
'confirmdeletetext'      => 'На путу сте да трајно обришете страницу
или слику заједно са њеном историјом из базе података.
Молимо вас потврдите да намеравате да урадите ово, да разумете
последице, и да ово радите у складу са
[[{{MediaWiki:Policy-url}}|правилима]] {{SITENAME}}.',
'actioncomplete'         => 'Акција је завршена',
'actionfailed'           => 'Акција није успела',
'deletedtext'            => 'Чланак "<nowiki>$1</nowiki>" је обрисан.
Погледајте $2 за запис о скорашњим брисањима.',
'deletedarticle'         => 'је обрисао „[[$1]]“',
'suppressedarticle'      => 'сактивено: "[[$1]]"',
'dellogpage'             => 'Историја брисања',
'dellogpagetext'         => 'Испод је списак најскоријих брисања.',
'deletionlog'            => 'историја брисања',
'reverted'               => 'Враћено на ранију ревизију',
'deletecomment'          => 'Разлог:',
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
'rollback'          => 'Врати измене',
'rollback_short'    => 'Врати',
'rollbacklink'      => 'врати',
'rollbackfailed'    => 'Враћање није успело',
'cantrollback'      => 'Не могу да вратим измену; последњи аутор је уједно и једини.',
'alreadyrolled'     => 'Не могу да вратим последњу измену [[:$1]] од корисника [[User:$2|$2]] ([[User talk:$2|разговор]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]); неко други је већ изменио или вратио чланак.

Последња измена од корисника [[User:$3|$3]] ([[User talk:$3|разговор]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "Коментар измене је: \"''\$1''\".",
'revertpage'        => 'Враћене су измене корисника [[Special:Contributions/$2|$2]] ([[User talk:$2|разговор]]) на последњу измену члана [[User:$1|$1]]',
'revertpage-nouser' => 'Враћене су измене корисника (корисничко име је уклоњено) на последњу измену члана [[User:$1|$1]]',
'rollback-success'  => 'Враћене измене од стране $1; на последњу измену од стране $2.',

# Edit tokens
'sessionfailure-title' => 'Сесија је окончана',
'sessionfailure'       => 'Изгледа да постоји проблем са вашом сеансом пријаве;
ова акција је прекинута као предострожност против преотимања сеанси.
Молимо кликните "back" и поново учитајте страну одакле сте дошли, а онда покушајте поново.',

# Protect
'protectlogpage'              => 'Историја закључавања',
'protectlogtext'              => 'Испод је списак заштићених страница.<br />
Погледајте [[Special:ProtectedPages|правила о заштити страница]] за више информација.',
'protectedarticle'            => '{{GENDER:|је заштитио|је заштитила|је заштитио}} „[[$1]]“',
'modifiedarticleprotection'   => '{{GENDER:|је променио|је променила|промени}} ниво заштите за „[[$1]]“',
'unprotectedarticle'          => '{{GENDER:|је скинуо|је скинула|је скинуо}} заштиту са „[[$1]]“',
'movedarticleprotection'      => '{{GENDER:|је преместио|је преместила|премести}} поставке заштите са „[[$2]]“ на „[[$1]]“',
'protect-title'               => 'Ниво заштите за „$1“',
'prot_1movedto2'              => '{{GENDER:|је преместио|је преместила|премести}} [[$1]] у [[$2]]',
'protect-legend'              => 'Потврдите заштиту',
'protectcomment'              => 'Разлог:',
'protectexpiry'               => 'Истиче:',
'protect_expiry_invalid'      => 'Време истека није одговарајуће.',
'protect_expiry_old'          => 'Време истека је у прошлости.',
'protect-unchain-permissions' => 'Откључај поставке заштите',
'protect-text'                => "Овде можете погледати и мењати ниво заштите за страницу '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "Не можете мењати нивое заштите док сте блокирани.
Ово су тренутна подешавања за страницу '''$1''':",
'protect-locked-dblock'       => "Нивои заштите не могу бити промењени због активног закључавања базе.
Ово су тренутна подешавања за страну '''$1''':",
'protect-locked-access'       => "Ваш налог нема дозволе за измену нивоа заштите странице.
Ово су тренутна подешавања за страницу '''$1''':",
'protect-cascadeon'           => 'Ова страница је тренутно заштићена јер се налази на {{PLURAL:$1|страници, која је заштићена|странице, које су заштићене|страница, које су заштићене}} са опцијом „преносиво“. Можете изменити степен заштите ове странице, али он неће утицати на преносиву заштиту.',
'protect-default'             => 'Дозволи све кориснике',
'protect-fallback'            => 'Захтева "$1" овлашћења',
'protect-level-autoconfirmed' => 'Блокирај нове и нерегистроване кориснике',
'protect-level-sysop'         => 'Само администратори',
'protect-summary-cascade'     => 'преносива заштита',
'protect-expiring'            => 'истиче $1 (UTC)',
'protect-expiry-indefinite'   => 'бесконачно',
'protect-cascade'             => 'Заштићене странице укључене у ову страницу (преносива заштита)',
'protect-cantedit'            => 'Не можете мењати нивое заштите за ову страницу, због тога што немате овлашћења да је уређујете.',
'protect-othertime'           => 'Друго време:',
'protect-othertime-op'        => 'друго време',
'protect-existing-expiry'     => 'Тренутно време истека: $3, $2',
'protect-otherreason'         => 'Други/додатни разлог:',
'protect-otherreason-op'      => 'Други разлог',
'protect-dropdown'            => '* Разлози заштите
** Вандализам
** Нежељене поруке
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
'restriction-upload' => 'Пошаљи',

# Restriction levels
'restriction-level-sysop'         => 'пуна заштита',
'restriction-level-autoconfirmed' => 'полузаштићено',
'restriction-level-all'           => 'било који ниво',

# Undelete
'undelete'                     => 'Приказ обрисаних страница',
'undeletepage'                 => 'Погледај и врати обрисане странице',
'undeletepagetitle'            => "'''Следеће садржи обрисане измене чланка: [[:$1|$1]]'''.",
'viewdeletedpage'              => 'Приказ обрисаних страница',
'undeletepagetext'             => '{{PLURAL:$1|Следећа страница је обрисана, али је|Следеће $1 странице су обрисане, али су|Следећих $1 страница је обрисано, али су}} још увек у архиви и могу бити враћене.
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
'undeletehistorynoadmin'       => 'Ова страница је обрисана.
Разлог за брисање се налази у опису испод, заједно са детаљима о кориснику који је мењао ову страницу пре брисања. Стварни текст ових обрисаних ревизија је доступан само администраторима.',
'undelete-revision'            => 'Обрисана ревизија од $1 (у време $4, на $5) од стране корисника $3:',
'undeleterevision-missing'     => 'Некоректна или непостојећа ревизија. Можда је ваш линк погрешан, или је ревизија рестаурирана, или обрисана из архиве.',
'undelete-nodiff'              => 'Нема претходних измена.',
'undeletebtn'                  => 'Врати',
'undeletelink'                 => 'прикажи/поврати',
'undeleteviewlink'             => 'прикажи',
'undeletereset'                => 'Поништи',
'undeleteinvert'               => 'Обрни избор',
'undeletecomment'              => 'Разлог:',
'undeletedarticle'             => '{{GENDER:|је повратио|је повратила|поврати}} „[[$1]]“',
'undeletedrevisions'           => '{{PLURAL:$1|Ревизија је враћена|$1 ревизије су враћене|$1 ревизија је враћено}}',
'undeletedrevisions-files'     => '$1 {{PLURAL:$1|ревизија|ревизије|ревизија}} и $2 {{PLURAL:$2|фајл|фајла|фајлова}} враћено',
'undeletedfiles'               => '{{PLURAL:$1|Датотека је враћена|датотеке су враћене|датотека је враћено}}',
'cannotundelete'               => 'Враћање обрисане верзије није успело; неко други је вратио страницу пре вас.',
'undeletedpage'                => "'''$1 је враћен'''

Прегледај [[Special:Log/delete|историју брисања]] за информацију о скорашњим брисањима и враћањима.",
'undelete-header'              => 'Погледајте [[Special:Log/delete|историјат брисања]] за недавно обрисане странице.',
'undelete-search-box'          => 'Претражи обрисане странице',
'undelete-search-prefix'       => 'Прикажи странице које почињу са:',
'undelete-search-submit'       => 'Претражи',
'undelete-no-results'          => 'Нема таквих страница у архиви обрисаних страница.',
'undelete-filename-mismatch'   => 'Није могуће обрисати верзију фајла од времена $1: име фајла се не поклапа.',
'undelete-bad-store-key'       => 'Није могуће вратити измену верзије фајла времена $1: фајл је недеостајао пре брисања.',
'undelete-cleanup-error'       => 'Грешка приликом брисања некоришћеног фајла из архиве "$1".',
'undelete-missing-filearchive' => 'Није могуће вратити архиву фајлова ID $1 зато што није у бази.
Можда је већ била враћена.',
'undelete-error-short'         => 'Грешка при враћању фајла: $1',
'undelete-error-long'          => 'Десила се грешка при враћању фајла:

$1',
'undelete-show-file-confirm'   => 'Желите ли да видите обрисану измену датотеке „<nowiki>$1</nowiki>“ од $2; $3?',
'undelete-show-file-submit'    => 'Да',

# Namespace form on various pages
'namespace'             => 'Именски простор:',
'invert'                => 'Обрни избор',
'namespace_association' => 'Повезани именски простор',
'blanknamespace'        => '(Главно)',

# Contributions
'contributions'       => 'Прилози корисника',
'contributions-title' => 'Прилози {{GENDER:$1|корисника|кориснице|члана}} $1',
'mycontris'           => 'Прилози',
'contribsub2'         => 'За $1 ($2)',
'nocontribs'          => 'Нису нађене промене које задовољавају ове услове.',
'uctop'               => '(врх)',
'month'               => 'од месеца (и раније):',
'year'                => 'од године (и раније):',

'sp-contributions-newbies'             => 'Прикажи само прилоге нових налога',
'sp-contributions-newbies-sub'         => 'За новајлије',
'sp-contributions-newbies-title'       => 'Прилози корисника с новим налозима',
'sp-contributions-blocklog'            => 'историја блокирања',
'sp-contributions-deleted'             => 'обрисани прилози',
'sp-contributions-uploads'             => 'слања',
'sp-contributions-logs'                => 'историје',
'sp-contributions-talk'                => 'разговор',
'sp-contributions-userrights'          => 'подешавање корисничких права',
'sp-contributions-blocked-notice'      => 'Овај корисник је тренутно блокиран.
Последњи унос у дневник блокирања је понуђен испод као референца:',
'sp-contributions-blocked-notice-anon' => 'Овој IP адреси је тренутно забрањен приступ.
Извештај о блокираним корисницима се налази испод:',
'sp-contributions-search'              => 'Претрага прилога',
'sp-contributions-username'            => 'IP адреса или корисничко име:',
'sp-contributions-toponly'             => 'Прикажи само најновије измене',
'sp-contributions-submit'              => 'Претражи',

# What links here
'whatlinkshere'            => 'Шта је повезано овде',
'whatlinkshere-title'      => 'Странице које су повезане са „$1“',
'whatlinkshere-page'       => 'Страница:',
'linkshere'                => "Следеће странице су повезане на '''[[:$1]]''':",
'nolinkshere'              => "Ниједна страница није повезана са: '''[[:$1]]'''.",
'nolinkshere-ns'           => "Ниједна страница у одабраном именском простору се не веже за '''[[:$1]]'''",
'isredirect'               => 'преусмерење',
'istemplate'               => 'укључивање',
'isimage'                  => 'веза ка слици',
'whatlinkshere-prev'       => '{{PLURAL:$1|претходни|претходних $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|следећи|следећих $1}}',
'whatlinkshere-links'      => '← везе',
'whatlinkshere-hideredirs' => '$1 преусмерења',
'whatlinkshere-hidetrans'  => '$1 укључења',
'whatlinkshere-hidelinks'  => '$1 везе',
'whatlinkshere-hideimages' => 'број веза према сликама: $1',
'whatlinkshere-filters'    => 'Филтери',

# Block/unblock
'autoblockid'                     => 'Самоблокирање #$1',
'block'                           => 'Блокирај корисника',
'unblock'                         => 'Деблокирај корисника',
'blockip'                         => 'Блокирај корисника',
'blockip-title'                   => 'Блокирај корисника',
'blockip-legend'                  => 'Блокирај корисника',
'blockiptext'                     => 'Употребите доњи упитник да бисте уклонили право писања
са одређене ИП адресе или корисничког имена.
Ово би требало да буде урађено само да би се спречио вандализам, и у складу
са [[{{MediaWiki:Policy-url}}|смерницама]].
Унесите конкретан разлог испод (на пример, наводећи које
странице су вандализоване).',
'ipadressorusername'              => 'IP адреса или корисничко име:',
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
'ipb-hardblock'                   => 'Забрани пријављеним корисницима да уређују с ове IP адресе',
'ipbcreateaccount'                => 'Спречи прављење налога',
'ipbemailban'                     => 'Забрани члану слање е-порука',
'ipbenableautoblock'              => 'Аутоматски блокирај последњу ИП адресу овог корисника, и сваку следећу адресу са које се покуша уређивање.',
'ipbsubmit'                       => 'Блокирај овог корисника',
'ipbother'                        => 'Остало време',
'ipboptions'                      => '2 сата:2 hours,1 дан:1 day,3 дана:3 days,1 недеља:1 week,2 недеље:2 weeks,1 месец:1 month,3 месеца:3 months,6 месеци:6 months,1 година:1 year,бесконачно:infinite',
'ipbotheroption'                  => 'остало',
'ipbotherreason'                  => 'Други/додатни разлог:',
'ipbhidename'                     => 'Сакриј корисничко име са измена и спискова',
'ipbwatchuser'                    => 'надгледање корисничке странице и странице за разговор овог корисника',
'ipb-disableusertalk'             => 'Забрани овом кориснику да уређује своју страницу за разговор док је блокиран',
'ipb-change-block'                => 'Блокирајте корисника поново са овим подешавањима',
'ipb-confirm'                     => 'Потврди блокирање',
'badipaddress'                    => 'Неисправна IP адреса',
'blockipsuccesssub'               => 'Приступ је забрањен',
'blockipsuccesstext'              => '"[[Special:Contributions/$1|$1]]" је блокиран.
<br />Погледајте [[Special:IPBlockList|ИП списак блокираних корисника]] за преглед блокирања.',
'ipb-blockingself'                => 'Овом радњом ћете блокирати себе! Јесте ли сигурни да то желите?',
'ipb-confirmhideuser'             => 'Управо ћете блокирати корисника с укљученом могућношћу „сакриј корисника“. Овим ће корисничко име бити сакривено у свим списковима и извештајима. Желите ли то да урадите?',
'ipb-edit-dropdown'               => 'Мењајте разлоге блока',
'ipb-unblock-addr'                => 'Деблокирај $1',
'ipb-unblock'                     => 'Деблокирај корисничко име или IP адресу',
'ipb-blocklist'                   => 'Прикажи постојеће забране',
'ipb-blocklist-contribs'          => 'Прилози за $1',
'unblockip'                       => 'Деблокирај корисника',
'unblockiptext'                   => 'Користите образац испод да бисте вратили право писања раније забрањеној IP адреси или корисничком имену.',
'ipusubmit'                       => 'Уклони ову забрану',
'unblocked'                       => '[[User:$1|$1]] је деблокиран',
'unblocked-range'                 => '$1 је {{GENDER:|деблокиран|деблокирана|деблокиран}}',
'unblocked-id'                    => 'Забрана $1 је уклоњена',
'blocklist'                       => 'Блокирани корисници',
'ipblocklist'                     => 'Блокирани корисници',
'ipblocklist-legend'              => 'Проналажење блокираног корисника',
'blocklist-userblocks'            => 'Сакриј блокирања налога',
'blocklist-tempblocks'            => 'Сакриј привремена блокирања',
'blocklist-addressblocks'         => 'Сакриј појединачна блокирања IP адресе',
'blocklist-timestamp'             => 'Време',
'blocklist-target'                => 'Корисник',
'blocklist-expiry'                => 'Истиче',
'blocklist-by'                    => 'Блокирао',
'blocklist-params'                => 'Забрањене радње',
'blocklist-reason'                => 'Разлог',
'ipblocklist-submit'              => 'Претражи',
'ipblocklist-localblock'          => 'Локална забрана',
'ipblocklist-otherblocks'         => 'Други {{PLURAL:$1|блок|блокови}}',
'infiniteblock'                   => 'никада',
'expiringblock'                   => 'истиче дана $1 у $2',
'anononlyblock'                   => 'само анонимни',
'noautoblockblock'                => 'искључено аутоматско блокирање',
'createaccountblock'              => 'забрањено отварање налога',
'emailblock'                      => 'забрањена е-пошта',
'blocklist-nousertalk'            => 'забрањено уређивање сопствене странице за разговор',
'ipblocklist-empty'               => 'Списак блокова је празан.',
'ipblocklist-no-results'          => 'Унесена IP адреса или корисничко име није забрањено.',
'blocklink'                       => 'блокирај',
'unblocklink'                     => 'деблокирај',
'change-blocklink'                => 'промени блок',
'contribslink'                    => 'прилози',
'autoblocker'                     => 'Аутоматски сте блокирани јер делите ИП адресу са "[[User:$1|$1]]". Дати разлог за блокирање корисника $1 је: "\'\'\'$2\'\'\'"',
'blocklogpage'                    => 'Историја блокирања',
'blocklog-showlog'                => 'Овом члану је претходно забрањен приступ.
Извештај блокирања се налази испод:',
'blocklog-showsuppresslog'        => 'Овај члан је блокиран и претходно сакривен.
Извештај се налази испод:',
'blocklogentry'                   => 'је блокирао „[[$1]]” са временом истицања блокаде од $2 $3',
'reblock-logentry'                => 'промењена подешавања блока за [[$1]] са временом истека $2 ($3)',
'blocklogtext'                    => 'Ово је историја блокирања корисника.
Аутоматски забрањене IP адресе нису исписане овде.
Погледајте [[Special:IPBlockList|забрањене IP адресе]] за списак тренутних блокова.',
'unblocklogentry'                 => '{{GENDER:|је деблокирао|је деблокирала|је деблокирао}} „$1“',
'block-log-flags-anononly'        => 'само анонимни корисници',
'block-log-flags-nocreate'        => 'онемогућено отварање налога',
'block-log-flags-noautoblock'     => 'искључено аутоматско блокирање',
'block-log-flags-noemail'         => 'забрањена е-пошта',
'block-log-flags-nousertalk'      => 'забрањено уређивање сопствене странице за разговор',
'block-log-flags-angry-autoblock' => 'омогућен је побољшани аутоблок',
'block-log-flags-hiddenname'      => 'корисничко име сакривено',
'range_block_disabled'            => 'Администраторска могућност да блокира блокове ИП адреса је искључена.',
'ipb_expiry_invalid'              => 'Погрешно време трајања.',
'ipb_expiry_temp'                 => 'Сакривени блокови корисничких имена морају бити стални.',
'ipb_hide_invalid'                => 'Није било могуће сакрити овај налог; Мора да има превише измена.',
'ipb_already_blocked'             => '"$1" је већ блокиран',
'ipb-needreblock'                 => '$1 је већ блокиран. Да ли желите да промените подешавања?',
'ipb-otherblocks-header'          => 'Други {{PLURAL:$1|блок|блокови}}',
'unblock-hideuser'                => 'Не можете деблокирати овог корисника јер је његово корисничко име сакривено.',
'ipb_cant_unblock'                => 'Грешка: ID блока $1 није нађен.
Могуће је да је већ деблокиран.',
'ipb_blocked_as_range'            => 'Грешка: IP $1 није директно блокиран и не може бити деблокиран.
Међутим, блокиран је као део опсега $2, који може бити деблокиран.',
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
'cant-see-hidden-user'            => 'Члан коме желите да забраните приступ је већ блокиран и сакривен.
С обзиром на то да немате права за сакривање корисника, не можете да видите нити измените забрану.',
'ipbblocked'                      => 'Не можете забранити или вратити приступ другим корисницима јер сте и сами блокирани',
'ipbnounblockself'                => 'Није вам дозвољено да деблокирате себе',

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
'lockconfirm'         => 'Желим да закључам базу.',
'unlockconfirm'       => 'Желим да откључам базу.',
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
'move-page'                    => 'Премештање „$1“',
'move-page-legend'             => 'Премештање странице',
'movepagetext'                 => "Доњи образац ће преименовати страницу, премештајући целу историју на ново име.
Стари наслов постаће преусмерење на нови наслов.
Можете аутоматски изменити преусмерење до изворног наслова.
Погледајте [[Special:DoubleRedirects|двострука]] или [[Special:BrokenRedirects|неисправна]] преусмерења.
На вама је одговорност да везе и даље иду тамо где би требало да иду.

Страница '''неће''' бити премештена ако већ постоји страница с тим именом, осим ако је она празна, садржи преусмерење или нема историју измена.
То значи да можете вратити страницу на претходно место ако погрешите, али не можете заменити постојећу страницу.

'''Пажња!'''
Ово може представљати драстичну и неочекивану измену за популарну страницу;
добро размислите о последицама пре него што наставите.",
'movepagetext-noredirectfixer' => "Доњи образац ће преименовати страницу, премештајући целу историју на ново име.
Стари наслов постаће преусмерење на нови наслов.
Погледајте [[Special:DoubleRedirects|двострука]] или [[Special:BrokenRedirects|неисправна]] преусмерења.
На вама је одговорност да везе и даље иду тамо где би требало да иду.

Страница '''неће''' бити премештена ако већ постоји страница с тим именом, осим ако је она празна, садржи преусмерење или нема историју измена.
То значи да можете вратити страницу на претходно место ако погрешите, али не можете заменити постојећу страницу.

'''Пажња!'''
Ово може представљати драстичну и неочекивану измену за популарну страницу;
добро размислите о последицама пре него што наставите.",
'movepagetalktext'             => "Одговарајућа страница за разговор, ако постоји, биће аутоматски премештена истовремено '''осим ако:'''
*Непразна страница за разговор већ постоји под новим именом, или
*Одбележите доњу кућицу.

У тим случајевима, мораћете ручно да преместите или спојите страницу уколико то желите.",
'movearticle'                  => 'Премести страницу',
'moveuserpage-warning'         => "'''Упозорење:'''Спремате се да преместите корисничку страницу. Упамтите да ће се само страница преместити, док корисник ''неће'' бити преименован.",
'movenologin'                  => 'Нисте пријављени',
'movenologintext'              => 'Морате бити регистровани корисник и [[Special:UserLogin|пријављени]]
да бисте преместили страницу.',
'movenotallowed'               => 'Немате овлашћење за премештање страница.',
'movenotallowedfile'           => 'Немате потребна права, да бисте премештали фајлове.',
'cant-move-user-page'          => 'Немате права потребна за премештање корисничких страница (искључујући подстранице).',
'cant-move-to-user-page'       => 'Немате права потребна за премештање неке стране на место корисничке стране (изузевши корисничке подстране).',
'newtitle'                     => 'Нови наслов:',
'move-watch'                   => 'Надгледај ову страницу',
'movepagebtn'                  => 'Премести страницу',
'pagemovedsub'                 => 'Премештање успело',
'movepage-moved'               => "'''Страница „$1“ је преименована у „$2“!'''",
'movepage-moved-redirect'      => 'Преусмерење је направљено.',
'movepage-moved-noredirect'    => 'Преусмерење није направљено.',
'articleexists'                => 'Страница под тим именом већ постоји, или је
име које сте изабрали неисправно.
Молимо изаберите друго име.',
'cantmove-titleprotected'      => 'Не можете преместити страницу на ову локацију, зато што је нови наслов заштићен за прављење',
'talkexists'                   => "'''Сама страница је успешно премештена, али
страница за разговор није могла бити премештена јер таква већ постоји на новом наслову. Молимо вас да их спојите ручно.'''",
'movedto'                      => 'премештена на',
'movetalk'                     => 'Премести "страницу за разговор" такође, ако је могуће.',
'move-subpages'                => 'Премести подстране (до $1)',
'move-talk-subpages'           => 'Премести подстранице странице за разговор (до $1)',
'movepage-page-exists'         => 'Страница $1 већ постоји и не може се заменити.',
'movepage-page-moved'          => 'Страница $1 је премештена у $2.',
'movepage-page-unmoved'        => 'Страница $1 се не може преместити у $2.',
'movepage-max-pages'           => 'Максимум од $1 {{PLURAL:$1|стране|страна}} је био премештен, и више од тога неће бити аутоматски премештено.',
'1movedto2'                    => 'је променио име чланку [[$1]] у [[$2]]',
'1movedto2_redir'              => 'је променио име чланку [[$1]] у [[$2]] путем преусмерења',
'move-redirect-suppressed'     => 'преусмерење није направљено',
'movelogpage'                  => 'Историја премештања',
'movelogpagetext'              => 'Испод је списак премештања чланака.',
'movesubpage'                  => '{{PLURAL:$1|Подстраница|Подстранице|Подстраница}}',
'movesubpagetext'              => 'Ова страница има $1 {{PLURAL:$1|подстраницу приказану|подстраница приказаних}} испод.',
'movenosubpage'                => 'Ова страница нема подстрана.',
'movereason'                   => 'Разлог:',
'revertmove'                   => 'врати',
'delete_and_move'              => 'Обриши и премести',
'delete_and_move_text'         => '==Потребно брисање==

Циљани чланак "[[:$1]]" већ постоји. Да ли желите да га обришете да бисте направили место за премештање?',
'delete_and_move_confirm'      => 'Да, обриши страницу',
'delete_and_move_reason'       => 'Обрисано како би се направило место за премештање',
'selfmove'                     => 'Изворни и циљани назив су исти; страница не може да се премести преко саме себе.',
'immobile-source-namespace'    => 'Стране из именског простора "$1" нису могле бити премештене',
'immobile-target-namespace'    => 'Не може да премести странице у именски простор „$1”',
'immobile-target-namespace-iw' => 'Међувики веза није исправна мета при премештању стране.',
'immobile-source-page'         => 'Ова страница се не може преместити.',
'immobile-target-page'         => 'Не може да се премести на циљани наслов.',
'imagenocrossnamespace'        => 'Датотека се не може преместити у именски простор који не припада датотекама.',
'nonfile-cannot-move-to-file'  => 'Не-датотеке не можете преместити у именски простор за датотеке',
'imagetypemismatch'            => 'Нови наставак за фајлове се не поклапа са својим типом.',
'imageinvalidfilename'         => 'Циљано име фајла је погрешно.',
'fix-double-redirects'         => 'Освежава било које преусмерење које веже на оригинални наслов',
'move-leave-redirect'          => 'Остави преусмерење након премештања',
'protectedpagemovewarning'     => "'''Напомена:''' Ова страница је закључана тако да само корисници са администраторским привилегијама могу да је преместе.
Најскорија забелешка историје је приложена испод као додатна информација:",
'semiprotectedpagemovewarning' => "'''Напомена:''' Ова страница је закључана тако да само регистровани корисници могу да је преместе.
Најновији извештај налази се испод:",
'move-over-sharedrepo'         => '== Датотека постоји ==
[[:$1]] се налази на дељеном складишту. Ако преместите датотеку на овај наслов, то ће заменити дељену датотеку.',
'file-exists-sharedrepo'       => 'Наведени назив датотеке се већ користи у дељеном складишту.
Изаберите други назив.',

# Export
'export'            => 'Извоз страница',
'exporttext'        => 'Можете извозити текст и историју промена одређене
странице или групе страница у XML формату. Ово онда може бити увезено у други
вики који користи МедијаВики софтвер преко {{ns:special}}:Import странице.

Да бисте извозили странице, унесите називе у текстуалном пољу испод, са једним насловом по реду, и одаберите да ли желите тренутну верзију са свим старим верзијама или само тренутну верзију са информацијама о последњој измени.

У другом случају, можете такође користити везу, нпр. [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] за страницу [[{{MediaWiki:Mainpage}}]].',
'exportcuronly'     => 'Укључи само текућу измену, не целу историју',
'exportnohistory'   => "----
'''Напомена:''' Извожење пуне историје страница преко овог формулара је онемогућено због серверских разлога.",
'export-submit'     => 'Извези',
'export-addcattext' => 'Додај странице из категорије:',
'export-addcat'     => 'Додај',
'export-addnstext'  => 'Додај стране из именског простора:',
'export-addns'      => 'Додај',
'export-download'   => 'Сачувај као датотеку',
'export-templates'  => 'Укључи шаблоне',
'export-pagelinks'  => 'Укључи повезане странице до дубине од:',

# Namespace 8 related
'allmessages'                   => 'Системске поруке',
'allmessagesname'               => 'Име',
'allmessagesdefault'            => 'Стандардни текст',
'allmessagescurrent'            => 'Текст поруке',
'allmessagestext'               => 'Ово је списак системских порука доступних у именском простору Медијавикија.
Посетите [http://translatewiki.net TranslateWiki] уколико желите да помогнете у превођењу.',
'allmessagesnotsupportedDB'     => "Ова страница не може бити употребљена зато што је '''\$wgUseDatabaseMessages''' искључен.",
'allmessages-filter-legend'     => 'Филтер',
'allmessages-filter'            => 'Сврстај по стању прилагођености:',
'allmessages-filter-unmodified' => 'неизмењене',
'allmessages-filter-all'        => 'све',
'allmessages-filter-modified'   => 'измењене',
'allmessages-prefix'            => 'Сврстај по префиксу:',
'allmessages-language'          => 'Језик:',
'allmessages-filter-submit'     => 'Иди',

# Thumbnails
'thumbnail-more'           => 'увећај',
'filemissing'              => 'Датотека недостаје',
'thumbnail_error'          => 'Грешка при прављењу умањене слике: $1',
'djvu_page_error'          => 'DjVu страница је ван опсега',
'djvu_no_xml'              => 'Не могу преузети XML за DjVu фајл.',
'thumbnail_invalid_params' => 'Погрешни параметри за малу слику.',
'thumbnail_dest_directory' => 'Не могу направити одредишни директоријум.',
'thumbnail_image-type'     => 'Врста слике није подржана',
'thumbnail_gd-library'     => 'Недовршене поставке GD библиотеке: недостаје $1 функција',
'thumbnail_image-missing'  => 'Датотека недостаје: $1',

# Special:Import
'import'                     => 'Увоз страница',
'importinterwiki'            => 'Трансвики увожење',
'import-interwiki-text'      => 'Одаберите вики и назив странице за увоз. Датуми измене и имена уредника ће бити сачувани. Сви трансвики увози су забележени у [[Special:Log/import|историји увоза]].',
'import-interwiki-source'    => 'Изворни вики/страна:',
'import-interwiki-history'   => 'Умножи све измене ове странице',
'import-interwiki-templates' => 'Укључи све шаблоне',
'import-interwiki-submit'    => 'Увези',
'import-interwiki-namespace' => 'Именски простор:',
'import-upload-filename'     => 'Назив датотеке:',
'import-comment'             => 'Коментар:',
'importtext'                 => 'Извезите датотеку с изворног викија користећи [[Special:Export|извоз]].
Сачувајте је на рачунар и пошаљите овде.',
'importstart'                => 'Увожење страница у току...',
'import-revision-count'      => '$1 {{PLURAL:$1|ревизија|ревизије|ревизија}}',
'importnopages'              => 'Нема страница за увоз.',
'imported-log-entries'       => '{{PLURAL:$1|Увезена је $1 ставка извештаја|Увезене су $1 ставке извештаја|Увезено је $1 ставки извештаја}}.',
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
'importuploaderrortemp'      => 'Слање датотеке за увоз није успело.
Привремена фасцикла недостаје.',
'import-parse-failure'       => 'Неуспешно парсирање унесеног XML-а.',
'import-noarticle'           => 'Нема страница за увоз!',
'import-nonewrevisions'      => 'Све верзије су претходно унесене.',
'xml-error-string'           => '$1 на линији $2, колона $3 (бајт $4): $5',
'import-upload'              => 'Слање XML података',
'import-token-mismatch'      => 'Губитак података о сесији.
Покушајте поново.',
'import-invalid-interwiki'   => 'Увоз са назначене енциклопедије није могућ.',

# Import log
'importlogpage'                    => 'Историја увоза',
'importlogpagetext'                => 'Административни увози страница са историјама измена са других викија.',
'import-logentry-upload'           => '{{GENDER:|је увезао|је увезла|уведе}} „[[$1]]“ отпремањем датотеке',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|ревизија|ревизије|ревизија}}',
'import-logentry-interwiki'        => 'премештено са другог викија: $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|ревизија|ревизије|ревизија}} од $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Ваша корисничка страница',
'tooltip-pt-anonuserpage'         => 'Корисничка страница ИП адресе са које уређујете',
'tooltip-pt-mytalk'               => 'Ваша страница за разговор',
'tooltip-pt-anontalk'             => 'Разговор о прилозима са ове ИП адресе',
'tooltip-pt-preferences'          => 'Ваша подешавања',
'tooltip-pt-watchlist'            => 'Списак страница које надгледате',
'tooltip-pt-mycontris'            => 'Списак ваших прилога',
'tooltip-pt-login'                => 'Препоручује се да се пријавите, али није обавезно',
'tooltip-pt-anonlogin'            => 'Препоручује се да се пријавите, али није обавезно',
'tooltip-pt-logout'               => 'Одјавите се',
'tooltip-ca-talk'                 => 'Разговор о чланку',
'tooltip-ca-edit'                 => 'Можете да уређујете ову страницу. Користите претпреглед пре снимања',
'tooltip-ca-addsection'           => 'Започните нови одељак',
'tooltip-ca-viewsource'           => 'Ова страница је закључана. Можете видети њен извор',
'tooltip-ca-history'              => 'Претходна издања ове странице',
'tooltip-ca-protect'              => 'Заштити ову страницу',
'tooltip-ca-unprotect'            => 'Откључај ову страницу',
'tooltip-ca-delete'               => 'Обриши ову страницу',
'tooltip-ca-undelete'             => 'Враћати измене које су начињене пре брисања странице',
'tooltip-ca-move'                 => 'Премести ову страницу',
'tooltip-ca-watch'                => 'Додај ову страницу на списак надгледања',
'tooltip-ca-unwatch'              => 'Уклони ову страницу са списка надгледања',
'tooltip-search'                  => 'Претрага',
'tooltip-search-go'               => 'Иди на страну с тачним именом ако постоји.',
'tooltip-search-fulltext'         => 'Претражите странице с овим текстом',
'tooltip-p-logo'                  => 'Посетите насловну страну',
'tooltip-n-mainpage'              => 'Посетите насловну страну',
'tooltip-n-mainpage-description'  => 'Посетите насловну страну',
'tooltip-n-portal'                => 'О пројекту, шта можете да радите и где да пронађете ствари',
'tooltip-n-currentevents'         => 'Сазнајте више о актуелностима',
'tooltip-n-recentchanges'         => 'Списак скорашњих измена на викију',
'tooltip-n-randompage'            => 'Учитавај случајну страницу',
'tooltip-n-help'                  => 'Место где можете да научите нешто',
'tooltip-t-whatlinkshere'         => 'Списак свих страница које везују на ову',
'tooltip-t-recentchangeslinked'   => 'Скорашње измене на чланцима повезаним са ове странице',
'tooltip-feed-rss'                => 'RSS довод ове странице',
'tooltip-feed-atom'               => 'Атом довод ове странице',
'tooltip-t-contributions'         => 'Погледај списак прилога овог корисника',
'tooltip-t-emailuser'             => 'Пошаљи електронску пошту овом кориснику',
'tooltip-t-upload'                => 'Пошаљите датотеке',
'tooltip-t-specialpages'          => 'Списак свих посебних страница',
'tooltip-t-print'                 => 'Издање за штампање ове странице',
'tooltip-t-permalink'             => 'Стална веза ка овој измени странице',
'tooltip-ca-nstab-main'           => 'Погледајте чланак',
'tooltip-ca-nstab-user'           => 'Погледајте корисничку страницу',
'tooltip-ca-nstab-media'          => 'Погледајте медија страницу',
'tooltip-ca-nstab-special'        => 'Ово је посебна страница. Не можете је мењати.',
'tooltip-ca-nstab-project'        => 'Преглед странице пројекта',
'tooltip-ca-nstab-image'          => 'Прикажи страну датотеке',
'tooltip-ca-nstab-mediawiki'      => 'Погледајте системску поруку',
'tooltip-ca-nstab-template'       => 'Погледајте шаблон',
'tooltip-ca-nstab-help'           => 'Погледајте страницу за помоћ',
'tooltip-ca-nstab-category'       => 'Погледајте страницу категорија',
'tooltip-minoredit'               => 'Назначите да се ради о малој измени',
'tooltip-save'                    => 'Сачувајте измене које сте направили',
'tooltip-preview'                 => 'Прегледајте своје измене. Пожељно је да користите ово дугме пре чувања',
'tooltip-diff'                    => 'Погледајте све измене које сте направили на тексту',
'tooltip-compareselectedversions' => 'Погледаj разлике између две одабране верзије ове странице.',
'tooltip-watch'                   => 'Додајте ову страницу на списак надгледања',
'tooltip-recreate'                => 'Направи поново страницу без обзира да је била обрисана',
'tooltip-upload'                  => 'Почни слање',
'tooltip-rollback'                => '„Врати“ враћа последње измене корисника у једном кораку (клику)',
'tooltip-undo'                    => 'Враћа ову измену и отвара образац за уређивање.',
'tooltip-preferences-save'        => 'Сачувај поставке',
'tooltip-summary'                 => 'Унесите кратак сажетак',

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
'anonuser'         => '{{SITENAME}} анонимни корисник $1',
'lastmodifiedatby' => 'Ову страницу {{GENDER:$4|је последњи пут променио|је последњи пут променила|последњи пут промени}} $3, $1 у $2.',
'othercontribs'    => 'Засновано на раду корисникâ $1.',
'others'           => 'остали',
'siteusers'        => '{{PLURAL:$2|следећих чланова}} ове енциклопедије: $1',
'anonusers'        => '{{SITENAME}} {{PLURAL:$2|анонимни корисник|анонимних корисника}} $1',
'creditspage'      => 'Заслуге за страницу',
'nocredits'        => 'Нису доступне информације о заслугама за ову страницу.',

# Spam protection
'spamprotectiontitle' => 'Филтер за заштиту од нежељених порука',
'spamprotectiontext'  => 'Страница коју желите да сачувате је блокирана од стране филтера за нежељене поруке.
Ово је вероватно изазвано блокираном везом ка спољашњем сајту.',
'spamprotectionmatch' => 'Следећи текст је изазвао наш филтер за нежељене поруке: $1',
'spambot_username'    => 'Чишћење непожељних порука у Медијавикији',
'spam_reverting'      => 'Враћање на стару ревизију која не садржи повезнице ка $1',
'spam_blanking'       => 'Све ревизије су садржале повезнице ка $1, пражњење',

# Info page
'infosubtitle'   => 'Информације за страницу',
'numedits'       => 'Број промена (чланак): $1',
'numtalkedits'   => 'Број промена (страница за разговор): $1',
'numwatchers'    => 'Број корисника који надгледају: $1',
'numauthors'     => 'Број различитих аутора (чланак): $1',
'numtalkauthors' => 'Број различитих аутора (страница за разговор): $1',

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
'filedelete-missing'              => 'Датотека „$1“ се не може обрисати јер не постоји.',
'filedelete-old-unregistered'     => 'Дата верзија фајла "$1" не постоји у бази.',
'filedelete-current-unregistered' => 'Дати фајл "$1" не постоји у бази.',
'filedelete-archive-read-only'    => 'Сервер не може да пише по складишној фасцикли ($1).',

# Browsing diffs
'previousdiff' => '← Старија измена',
'nextdiff'     => 'Новија измена →',

# Media information
'mediawarning'           => "'''Упозорење''': Овај тип фајла би могао да садржи штетан код.
Његовим извршавањем бисте могли да оштетите Ваш систем.",
'imagemaxsize'           => "Ограничење величине слике:<br />''(за стране описа фајлова)''",
'thumbsize'              => 'Величина умањеног приказа :',
'widthheightpage'        => '$1×$2, $3 {{PLURAL:$3|страница|странице|страница}}',
'file-info'              => 'величина: $1, MIME тип: $2',
'file-info-size'         => '$1×$2 пиксела, величина: $3, MIME тип: $4',
'file-nohires'           => '<small>Није доступна већа величина.</small>',
'svg-long-desc'          => 'SVG датотека, номинално $1×$2 тачака, величина: $3',
'show-big-image'         => 'Пуна величина',
'show-big-image-preview' => '<small>Величина овог приказа: $1.</small>',
'show-big-image-other'   => '<small>Остале величине: $1.</small>',
'show-big-image-size'    => '$1×$2 пиксела',
'file-info-gif-looped'   => 'петља',
'file-info-gif-frames'   => '$1 {{PLURAL:$1|кадар|кадра|кадрова}}',
'file-info-png-looped'   => 'петља',
'file-info-png-repeat'   => 'поновљено $1 {{PLURAL:$1|пут|пута|пута}}',
'file-info-png-frames'   => '$1 {{PLURAL:$1|кадар|кадра|кадрова}}',

# Special:NewFiles
'newimages'             => 'Галерија нових слика',
'imagelisttext'         => "Испод је списак од '''$1''' {{PLURAL:$1|датотеке|датотеке|датотека}} поређаних $2.",
'newimages-summary'     => 'Ова посебна страница приказује последње послате фајлове.',
'newimages-legend'      => 'Филтер',
'newimages-label'       => 'Назив датотеке (или њен део):',
'showhidebots'          => '($1 ботове)',
'noimages'              => 'Нема ништа да се види',
'ilsubmit'              => 'Претражи',
'bydate'                => 'по датуму',
'sp-newimages-showfrom' => 'прикажи нове датотеке почевши од $1, $2',

# Bad image list
'bad_image_list' => 'Формат је следећи:

Разматрају се само ставке у списку (линије које почињу са *).
Прва веза у линији мора бити веза на високо ризичну слику.
Све друге везе у истој линији се сматрају изузецима тј. чланци у којима се слика може приказати.',

# Variants for Serbian language
'variantname-sr-ec' => 'Ћирилица',
'variantname-sr-el' => 'Latinica',
'variantname-sr'    => 'disable',

# Metadata
'metadata'          => 'Метаподаци',
'metadata-help'     => 'Овај фајл садржи додатне информације, које су вероватно додали дигитални фотоапарат или скенер који су коришћени да би се направила слика.
Ако је првобитно стање фајла промењено, могуће је да неки детаљи не описују у потпуности измењен фајл.',
'metadata-expand'   => 'Прикажи детаље',
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
'exif-rowsperstrip'                => 'Број редова по линији',
'exif-stripbytecounts'             => 'Величина компресованог блока',
'exif-jpeginterchangeformat'       => 'Удаљеност ЈПЕГ прегледа од почетка фајла',
'exif-jpeginterchangeformatlength' => 'Бајтови JPEG података',
'exif-whitepoint'                  => 'Хромацитет беле тачке',
'exif-primarychromaticities'       => 'Хромацитет примарних боја',
'exif-ycbcrcoefficients'           => 'Матрични коефицијенти трансформације колор простора',
'exif-referenceblackwhite'         => 'Место беле и црне тачке',
'exif-datetime'                    => 'Датум последње промене фајла',
'exif-imagedescription'            => 'Назив слике',
'exif-make'                        => 'Произвођач камере',
'exif-model'                       => 'Модел камере',
'exif-software'                    => 'Коришћен софтвер',
'exif-artist'                      => 'Аутор',
'exif-copyright'                   => 'Носилац права',
'exif-exifversion'                 => 'Exif издање',
'exif-flashpixversion'             => 'Подржано издање FlashPix-а',
'exif-colorspace'                  => 'Простор боје',
'exif-componentsconfiguration'     => 'Значење сваке од компоненти',
'exif-compressedbitsperpixel'      => 'Мод компресије слике',
'exif-pixelydimension'             => 'Пуна висина слике',
'exif-pixelxdimension'             => 'Пуна ширина слике',
'exif-usercomment'                 => 'Кориснички коментар',
'exif-relatedsoundfile'            => 'Повезани звучни запис',
'exif-datetimeoriginal'            => 'Датум и време сликања',
'exif-datetimedigitized'           => 'Датум и време дигитализације',
'exif-subsectime'                  => 'Део секунде у којем је сликано',
'exif-subsectimeoriginal'          => 'Део секунде у којем је фотографисано',
'exif-subsectimedigitized'         => 'Део секунде у којем је дигитализовано',
'exif-exposuretime'                => 'Експозиција',
'exif-exposuretime-format'         => '$1 сек. ($2)',
'exif-fnumber'                     => 'F број отвора бленде',
'exif-exposureprogram'             => 'Програм експозиције',
'exif-spectralsensitivity'         => 'Спектрална осетљивост',
'exif-isospeedratings'             => 'ИСО вредност',
'exif-shutterspeedvalue'           => 'Брзина затварача',
'exif-aperturevalue'               => 'Отвор бленде',
'exif-brightnessvalue'             => 'Светлост',
'exif-exposurebiasvalue'           => 'Компензација експозиције',
'exif-maxaperturevalue'            => 'Минимални број отвора бленде',
'exif-subjectdistance'             => 'Удаљеност до објекта',
'exif-meteringmode'                => 'Режим мерача времена',
'exif-lightsource'                 => 'Извор светлости',
'exif-flash'                       => 'Блиц',
'exif-focallength'                 => 'Жаришна даљина сочива',
'exif-subjectarea'                 => 'Положај и површина објекта снимка',
'exif-flashenergy'                 => 'Енергија блица',
'exif-focalplanexresolution'       => 'Водоравна резолуција фокусне равни',
'exif-focalplaneyresolution'       => 'Хоризонатлна резолуција фокусне равни',
'exif-focalplaneresolutionunit'    => 'Јединица резолуције фокусне равни',
'exif-subjectlocation'             => 'Положај субјекта',
'exif-exposureindex'               => 'Попис експозиције',
'exif-sensingmethod'               => 'Тип сензора',
'exif-filesource'                  => 'Изворна датотека',
'exif-scenetype'                   => 'Тип сцене',
'exif-customrendered'              => 'Прилагођена обрада слика',
'exif-exposuremode'                => 'Режим експозиције',
'exif-whitebalance'                => 'Баланс беле боје',
'exif-digitalzoomratio'            => 'Однос дигиталног увеличања',
'exif-focallengthin35mmfilm'       => 'Жаришна даљина за филм од 35 мм',
'exif-scenecapturetype'            => 'Тип сцене на снимку',
'exif-gaincontrol'                 => 'Контрола осветљености',
'exif-contrast'                    => 'Контраст',
'exif-saturation'                  => 'Засићење',
'exif-sharpness'                   => 'Оштрина',
'exif-devicesettingdescription'    => 'Опис поставки уређаја',
'exif-subjectdistancerange'        => 'Распон удаљености субјеката',
'exif-imageuniqueid'               => 'Јединствени идентификатор слике',
'exif-gpsversionid'                => 'Издање GPS ознаке',
'exif-gpslatituderef'              => 'Северна или јужна ширина',
'exif-gpslatitude'                 => 'Ширина',
'exif-gpslongituderef'             => 'Источна или западна дужина',
'exif-gpslongitude'                => 'Дужина',
'exif-gpsaltituderef'              => 'Висина испод или изнад мора',
'exif-gpsaltitude'                 => 'Висина',
'exif-gpstimestamp'                => 'GPS време (атомски сат)',
'exif-gpssatellites'               => 'Употребљени сателити',
'exif-gpsstatus'                   => 'Стање пријемника',
'exif-gpsmeasuremode'              => 'Режим мерења',
'exif-gpsdop'                      => 'Прецизност мерења',
'exif-gpsspeedref'                 => 'Јединица брзине',
'exif-gpsspeed'                    => 'Брзина GPS пријемника',
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
'exif-gpsprocessingmethod'         => 'Име начина обраде GPS података',
'exif-gpsareainformation'          => 'Име GPS подручја',
'exif-gpsdatestamp'                => 'GPS датум',
'exif-gpsdifferential'             => 'GPS диференцијална исправка',
'exif-objectname'                  => 'Кратак наслов',

# EXIF attributes
'exif-compression-1' => 'Несажето',
'exif-compression-6' => 'JPEG',

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
'exif-flash-return-0'   => 'без функције повратног светла',
'exif-flash-return-2'   => 'повратно светло није уочено',
'exif-flash-return-3'   => 'уочено је повратно светло',
'exif-flash-mode-1'     => 'обавезно флеш испаљивање',
'exif-flash-mode-2'     => 'обавезно флеш сузбијање',
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
'edit-externally'      => 'Измени ову датотеку користећи спољашњи програм',
'edit-externally-help' => '(Погледајте [http://www.mediawiki.org/wiki/Manual:External_editors упутство за подешавање] за више информација)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'све',
'imagelistall'     => 'све',
'watchlistall2'    => 'све',
'namespacesall'    => 'сви',
'monthsall'        => 'све',
'limitall'         => 'све',

# E-mail address confirmation
'confirmemail'              => 'Потврда е-адресе',
'confirmemail_noemail'      => 'Немате потврђену адресу ваше е-поште у вашим [[Special:Preferences|корисничким подешавањима интерфејса]].',
'confirmemail_text'         => '{{SITENAME}} захтева да потврдите е-адресу пре него што почнете да користите могућности е-поште.
Кликните на дугме испод за слање поруке на вашу е-адресу.
У поруци ће се налазити веза с потврдним кодом;
унесите је у прегледач да бисте потврдили да је ваша е-адреса исправна.',
'confirmemail_pending'      => 'Код потврде је већ послат на Вашу е-пошру;
Ако сте скоро направили Ваш налог, вероватно би требало да одчекате неколико минута, како би код стигао, пре него што затражите нови.',
'confirmemail_send'         => 'Пошаљи кôд за потврду',
'confirmemail_sent'         => 'Е-пошта за потврђивање послата.',
'confirmemail_oncreate'     => 'Код за потврду је послат на вашу имејл адресу.
Овај код није потребан да бисте се улоговали, али ће од Вас бити тражено да га приложите да би омогућили погодности Викија везане за коришћење мејлова.',
'confirmemail_sendfailed'   => '{{SITENAME}} није успела да пошање е-пошту.
Проверита адресу због неправилних карактера.

Враћено: $1',
'confirmemail_invalid'      => 'Потврдни кôд је неисправан. Вероватно је истекао.',
'confirmemail_needlogin'    => 'Морате бити $1 да бисте потврдили е-адресу.',
'confirmemail_success'      => 'Адреса ваше е-поште је потврђена. Можете сада да се пријавите и уживате у викију.',
'confirmemail_loggedin'     => 'Адреса ваше е-поште је сада потврђена.',
'confirmemail_error'        => 'Нешто је пошло по злу приликом снимања ваше потврде.',
'confirmemail_subject'      => '{{SITENAME}} – потврда е-адресе',
'confirmemail_body'         => 'Неко са IP адресе $1 отвори налог „$2“ на сајту {{SITENAME}}, наводећи ову е-адресу.

Да потврдите да овај налог стварно припада вама, као и да
омогућите могућности е-поште, отворите ову везу у прегледачу:

$3

Уколико ви нисте отворили налог, пратите везу
испод како бисте прекинули поступак уписа:

$5

Овај потврдни кôд истиче у $4.',
'confirmemail_body_changed' => 'Неко, вероватно ви, са IP адресе $1 је променио е-адресу налога „$2“ у ову адресу на викију {{SITENAME}}.

Да бисте потврдили да овај налог припада вама и поново активирали могућности е-поште, отворите следећу везу у прегледачу:

$3

Ако налог *не* припада вама, пратите следећу везу да бисте отказали потврду е-адресе:

$5

Овај потврдни кôд истиче $6 у $7.',
'confirmemail_body_set'     => 'Неко, вероватно ви, са IP адресе $1 је променио е-адресу налога „$2“ у ову адресу викију {{SITENAME}}.

Да бисте потврдили да овај налог припада вама и поново активирали могућности е-поште, отворите следећу везу у прегледачу:

$3

Ако налог *не* припада вама, пратите следећу везу да бисте отказали потврду е-адресе:

$5

Овај потврдни кôд истиче $6 у $7.',
'confirmemail_invalidated'  => 'Потврда е-поште је отказана',
'invalidateemail'           => 'Отказивање потврде е-поште',

# Scary transclusion
'scarytranscludedisabled' => '[Интервики укључивање је онемогућено]',
'scarytranscludefailed'   => '[Добављање шаблона за $1 није успело]',
'scarytranscludetoolong'  => '[URL адреса је предугачка]',

# Trackbacks
'trackbackbox'      => 'Повратне тачке за ову страницу:<br />
$1',
'trackbackremove'   => '([$1 брисање])',
'trackbacklink'     => 'Повратне тачке',
'trackbackdeleteok' => 'Повратна тачка је успешно обрисана.',

# Delete conflict
'deletedwhileediting'      => "'''Упозорење''': ова страница је обрисана након што сте почели с уређивањем!",
'confirmrecreate'          => "[[User:$1|$1]] ([[User talk:$1|разговор]]) {{GENDER:$1|је обрисао|је обрисала|обриса}} ову страницу након што сте почели да је уређујете, са следећим разлогом:
: ''$2''
Потврдите да стварно желите да направите страницу.",
'confirmrecreate-noreason' => 'Корисник [[User:$1|$1]] ([[User talk:$1|разговор]]) је обрисао ову страницу након што сте почели да га уређујете. Потврдите да стварно желите да поново направите ову страницу.',
'recreate'                 => 'Поново направи',

# action=purge
'confirm_purge_button' => 'У реду',
'confirm-purge-top'    => 'Очистити привремену меморију ове стране?',
'confirm-purge-bottom' => 'Чишћење привремене меморије стране приморава софтвер да прикаже њену најновију ревизију.',

# Multipage image navigation
'imgmultipageprev' => '← претходна страница',
'imgmultipagenext' => 'следећа страница →',
'imgmultigo'       => 'Иди!',
'imgmultigoto'     => 'Пређи на страну $1',

# Table pager
'ascending_abbrev'         => 'раст.',
'descending_abbrev'        => 'опад.',
'table_pager_next'         => 'Следећа страница',
'table_pager_prev'         => 'Претходна страница',
'table_pager_first'        => 'Прва страница',
'table_pager_last'         => 'Последња страница',
'table_pager_limit'        => 'Прикажи $1 ставки по страници',
'table_pager_limit_label'  => 'Ставки по страници:',
'table_pager_limit_submit' => 'Иди',
'table_pager_empty'        => 'Нема резултата',

# Auto-summaries
'autosumm-blank'   => 'Обрисан је садржај странице',
'autosumm-replace' => 'Замена садржаја са „$1“',
'autoredircomment' => 'Преусмерење на [[$1]]',
'autosumm-new'     => 'Направљена је страница са „$1“',

# Live preview
'livepreview-loading' => 'Учитавање…',
'livepreview-ready'   => 'Учитавање… спремно!',
'livepreview-failed'  => 'Брзи приказ није могућ!
Пробајте обичан приказ.',
'livepreview-error'   => 'Повезивање није успело: $1 „$2“.
Пробајте обичан приказ.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Измене новије од $1 {{PLURAL:$1|секунде|секунде|секунди}} неће бити приказане.',
'lag-warn-high'   => 'Због преоптерећења базе података, измене новије од $1 {{PLURAL:$1|секунде|секунде|секунди}} неће бити приказане.',

# Watchlist editor
'watchlistedit-numitems'       => 'Ваш списак надгледања садржи {{PLURAL:$1|једну страницу|$1 странице|$1 страница}}, не рачунајући странице за разговор.',
'watchlistedit-noitems'        => 'Ваш списак надгледања не садржи странице.',
'watchlistedit-normal-title'   => 'Уређивање списка надгледања',
'watchlistedit-normal-legend'  => 'Уклањање наслова са списка надгледања',
'watchlistedit-normal-explain' => 'Наслови на вашем списку надгледања су приказани испод.
Да бисте уклонили наслов, означите кућицу до њега и кликните на „{{int:Watchlistedit-normal-submit}}“.
Можете и да [[Special:EditWatchlist/raw|измените сиров списак]].',
'watchlistedit-normal-submit'  => 'Уклони наслове',
'watchlistedit-normal-done'    => '{{PLURAL:$1|Једна страница је уклоњена|$1 странице су уклоњене|$1 страница је уклоњено}} с вашег списка надгледања:',
'watchlistedit-raw-title'      => 'Измена сировог списка надгледања',
'watchlistedit-raw-legend'     => 'Измена сировог списка надгледања',
'watchlistedit-raw-explain'    => 'Наслови са списка надгледања су приказани испод и могу се мењати додавањем или уклањањем;
Уносите један наслов по линији.
Када завршите, кликните на „{{int:Watchlistedit-raw-submit}}“.
Можете и да [[Special:EditWatchlist|користите стандардан уређивач списка]].',
'watchlistedit-raw-titles'     => 'Наслови:',
'watchlistedit-raw-submit'     => 'Ажурирај списак надгледања',
'watchlistedit-raw-done'       => 'Ваш списак надгледања је ажуриран.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|Додат је један наслов|Додата су $1 наслова|Додато је $1 наслова}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|Уклоњен је један наслов|Уклоњена су $1 наслова|Уклоњено је $1 наслова}}:',

# Watchlist editing tools
'watchlisttools-view' => 'прикажи сродне измене',
'watchlisttools-edit' => 'прикажи и уреди списак надгледања',
'watchlisttools-raw'  => 'измени сиров списак надгледања',

# Core parser functions
'unknown_extension_tag' => 'Непозната ознака прикључка: „$1“.',
'duplicate-defaultsort' => "'''Упозорење:''' подразумевани кључ сврставања „$2“ мења некадашњи кључ „$1“.",

# Special:Version
'version'                          => 'Издање',
'version-extensions'               => 'Инсталирана проширења',
'version-specialpages'             => 'Посебне странице',
'version-parserhooks'              => 'Куке рашчлањивача',
'version-variables'                => 'Променљиве',
'version-antispam'                 => 'Спречавање непожељних порука',
'version-skins'                    => 'Теме',
'version-other'                    => 'Остало',
'version-mediahandlers'            => 'Руководиоци медијима',
'version-hooks'                    => 'Куке',
'version-extension-functions'      => 'Функције',
'version-parser-extensiontags'     => 'Ознаке',
'version-parser-function-hooks'    => 'Куке',
'version-skin-extension-functions' => 'Функције прикључка теме',
'version-hook-name'                => 'Назив куке',
'version-hook-subscribedby'        => 'Пријављен од',
'version-version'                  => '(издање $1)',
'version-license'                  => 'Лиценца',
'version-poweredby-credits'        => "Овај вики покреће '''[http://www.mediawiki.org/ Медијавики]''', ауторска права © 2001–$1 $2.",
'version-poweredby-others'         => 'остали',
'version-license-info'             => 'Медијавики је слободан софтвер; можете га расподељивати и мењати под условима ГНУ-ове опште јавне лиценце (ОЈЛ) коју је објавила Задужбина за слободан софтвер, било да је у питању друго или новије издање лиценце.

Медијавики се расподељује у нади да ће бити користан, али БЕЗ ИКАКВЕ ГАРАНЦИЈЕ; чак и без имплицитне гаранције о ПРОДАЈИ или ПОГОДНОСТИ ЗА ОДРЕЂЕНЕ НАМЕНЕ. Погледајте ГНУ-ову општу јавну лиценцу за више детаља.

Требало би да сте примили [{{SERVER}}{{SCRIPTPATH}}/COPYING примерак ГНУ-ове опште јавне лиценце] заједно с овим програмом. Ако нисте, пишите Задужбини за слободан софтвер, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA или [http://www.gnu.org/licenses/old-licenses/gpl-2.0.html прочитајте на мрежи].',
'version-software'                 => 'Инсталиран софтвер',
'version-software-product'         => 'Производ',
'version-software-version'         => 'Издање',

# Special:FilePath
'filepath'         => 'Путања датотеке',
'filepath-page'    => 'Датотека:',
'filepath-submit'  => 'Пошаљи',
'filepath-summary' => 'Ова страница приказује потпуну путању датотеке.
Слике бивају приказане у пуној величини, а друге врсте датотека се покрећу помоћу њима придруженим програмима.

Унесите назив датотеке без „{{ns:file}}:“ префикса.',

# Special:FileDuplicateSearch
'fileduplicatesearch'           => 'Претрага дупликата',
'fileduplicatesearch-summary'   => 'Тражење дупликата датотека према њихових вредностима дисперзије.',
'fileduplicatesearch-legend'    => 'Претрага дупликата',
'fileduplicatesearch-filename'  => 'Назив датотеке:',
'fileduplicatesearch-submit'    => 'Претражи',
'fileduplicatesearch-info'      => '$1×$2 пиксела<br />Величина: $3<br />MIME тип: $4',
'fileduplicatesearch-result-1'  => 'Датотека „$1“ нема идентичних дупликата.',
'fileduplicatesearch-result-n'  => 'Датотека „$1“ има {{PLURAL:$2|идентичан дупликат|$2 идентична дупликата|$2 идентичних дупликата}}.',
'fileduplicatesearch-noresults' => 'Датотека под називом „$1“ није пронађена.',

# Special:SpecialPages
'specialpages'                   => 'Посебне странице',
'specialpages-note'              => '----
* обичне посебне странице
* <strong class="mw-specialpagerestricted">привремено меморисане посебне странице</strong>',
'specialpages-group-maintenance' => 'Извештаји одржавања',
'specialpages-group-other'       => 'Остале посебне странице',
'specialpages-group-login'       => 'Отварање налога и пријављивање',
'specialpages-group-changes'     => 'Скорашње измене и историје',
'specialpages-group-media'       => 'Мултимедијални извештаји и слања',
'specialpages-group-users'       => 'Корисници и корисничка права',
'specialpages-group-highuse'     => 'Највише коришћене странице',
'specialpages-group-pages'       => 'Списак страница',
'specialpages-group-pagetools'   => 'Алатке',
'specialpages-group-wiki'        => 'Подаци и алати енциклопедије',
'specialpages-group-redirects'   => 'Преусмеравање посебних страница',
'specialpages-group-spam'        => 'Алати против непожељних порука',

# Special:BlankPage
'blankpage'              => 'Празна страница',
'intentionallyblankpage' => 'Ова страница је намерно остављена празном.',

# External image whitelist
'external_image_whitelist' => ' #Оставите ову линију онаквом каква јесте<pre>
#Додајте фрагменте регуларних израза (само део који се налази између //) испод
#Они ће бити упоређени с URL адресама спољашњих слика
#Оне које се поклапају биће приказане као слике, а преостале као везе до слика
#Линије које почињу с тарабом се третирају као коментари
#Сви уноси су осетљиви на величину слова

#Додајте све фрагменте регуларних израза изнад ове линије. Не мењајте ову линију</pre>',

# Special:Tags
'tags'                    => 'Дозвољене ознаке измена',
'tag-filter'              => 'Филтер за [[Special:Tags|ознаке]]:',
'tag-filter-submit'       => 'Сврстај',
'tags-title'              => 'Ознаке',
'tags-intro'              => 'Списак и значење ознака којима софтвер може да означи неку измену.',
'tags-tag'                => 'Назив ознаке',
'tags-display-header'     => 'Изглед на списковима измена',
'tags-description-header' => 'Опис значења',
'tags-hitcount-header'    => 'Означене измене',
'tags-edit'               => 'уреди',
'tags-hitcount'           => '$1 {{PLURAL:$1|измена|измене|измена}}',

# Special:ComparePages
'comparepages'     => 'Упоређивање страница',
'compare-selector' => 'Упоређивање измена странице',
'compare-page1'    => 'Страна 1:',
'compare-page2'    => 'Страна 2:',
'compare-rev1'     => 'Ревизија 1:',
'compare-rev2'     => 'Ревизија 2:',
'compare-submit'   => 'Упореди',

# Database error messages
'dberr-header'      => 'Овај вики има проблем',
'dberr-problems'    => 'Дошло је до техничких проблема.',
'dberr-again'       => 'Сачекајте неколико минута пре него што поново учитате страницу.',
'dberr-info'        => '(приступ серверу базе података није успостављен: $1)',
'dberr-usegoogle'   => 'У међувремену, покушајте да претражите помоћу Гугла.',
'dberr-outofdate'   => 'Имајте на уму да њихови примерци нашег садржаја могу бити застарели.',
'dberr-cachederror' => 'Ово је привремено меморисан примерак стране који можда није ажуран.',

# HTML forms
'htmlform-invalid-input'       => 'Пронађени су проблеми у вашем уносу',
'htmlform-select-badoption'    => 'Наведена вредност није исправна опција.',
'htmlform-int-invalid'         => 'Наведена вредност није цели број.',
'htmlform-float-invalid'       => 'Наведена вредност није број.',
'htmlform-int-toolow'          => 'Наведена вредност је испод минимума од $1',
'htmlform-int-toohigh'         => 'Наведена вредност је изнад максимума од $1',
'htmlform-required'            => 'Ова вредност се мора навести',
'htmlform-submit'              => 'Пошаљи',
'htmlform-reset'               => 'Врати измене',
'htmlform-selectorother-other' => 'Друго',

# SQLite database support
'sqlite-has-fts' => '$1 с подршком претраге пуног текста',
'sqlite-no-fts'  => '$1 без подршке претраге пуног текста',

);

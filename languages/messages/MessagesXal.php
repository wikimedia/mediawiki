<?php
/** Kalmyk (Хальмг)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Huuchin
 * @author ОйЛ
 * @author לערי ריינהארט
 */

$fallback = 'ru';

$fallback8bitEncoding = "windows-1251";

$namespaceNames = array(
	NS_MEDIA            => 'Аһар',
	NS_SPECIAL          => 'Көдлхнә',
	NS_TALK             => 'Меткән',
	NS_USER             => 'Демнч',
	NS_USER_TALK        => 'Демнчна_туск_меткән',
	NS_PROJECT_TALK     => '$1_туск_меткән',
	NS_FILE             => 'Боомг',
	NS_FILE_TALK        => 'Боомгин_туск_меткән',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_туск_меткән',
	NS_TEMPLATE         => 'Кевләр',
	NS_TEMPLATE_TALK    => 'Зуран_туск_меткән',
	NS_HELP             => 'Цәәлһлһн',
	NS_HELP_TALK        => 'Цәәлһлһин_туск_меткән',
	NS_CATEGORY         => 'Әәшл',
	NS_CATEGORY_TALK    => 'Әәшлин_туск_меткән',
);

$namespaceAliases = array(
	'Көдлхнə'                 => NS_SPECIAL,
	'Ухалвр'                  => NS_TALK,
	'Орлцач'                  => NS_USER,
	'Орлцачна_тускар_ухалвр'  => NS_USER_TALK,
	'$1_тускар_ухалвр'        => NS_PROJECT_TALK,
	'Зург'                    => NS_FILE,
	'Зургин_тускар_ухалвр'    => NS_FILE_TALK,
	'MediaWiki_тускар_ухалвр' => NS_MEDIAWIKI_TALK,
	'Зура'     => NS_TEMPLATE,
	'Зуран_тускар_ухалвр'     => NS_TEMPLATE_TALK,
	'Цəəлһлһн'                => NS_HELP,
	'Цəəлһлһин_тускар_ухалвр' => NS_HELP_TALK,
	'Янз'                     => NS_CATEGORY,
	'Янзин_тускар_ухалвр'     => NS_CATEGORY_TALK,
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Заалһиг татас татх:',
'tog-highlightbroken'         => 'Бәәдг уга заалһс <a href="" class="new">иигәд</a> үзүлх (оңданар иигәд<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Зүүл тегшлтн',
'tog-hideminor'               => 'Шидрә сольлһна сеткүлд баһ хүврлһиг бултулх',
'tog-hidepatrolled'           => 'Шидрә сольлһна сеткүлд шүүсн хүврлһиг бултулх',
'tog-newpageshidepatrolled'   => 'Шин халхна сеткүләс шүүсн хүврлһиг бултулх',
'tog-extendwatchlist'         => 'Хар шидрә сольлһн биш, цуг сольлһн үзүлдг, өргдүлсн шинҗллһнә сеткүл',
'tog-usenewrc'                => 'Ясрулсн шидрә сольлһна сеткүл олзлх (JavaScript кергтә)',
'tog-numberheadings'          => 'То-диг чикән даах',
'tog-showtoolbar'             => 'Ора зер-зев үзлх (JavaScript кергтә)',
'tog-editondblclick'          => 'Давхр индстлһар чиклх (JavaScript кергтә)',
'tog-editsection'             => '«Чиклх» заавр болвчн хүвд үзүлх',
'tog-editsectiononrightclick' => 'Һарчига барун индстлһар хүвиг чиклх (JavaScript кергтә)',
'tog-showtoc'                 => 'Һарг үзүлх (3 икәр толһата халхсд)',
'tog-rememberpassword'        => 'Намаг эн тоолдврд тодлх',
'tog-watchcreations'          => 'Би эврәннь немгдсн халхс шинҗллһнә сеткүлд немх',
'tog-watchdefault'            => 'Би эврәннь чиклсн халхс шинҗллһнә сеткүлд немх',
'tog-watchmoves'              => 'Би эврәннь көндсн халхс шинҗллһнә сеткүлд немх',
'tog-watchdeletion'           => 'Би эврәннь һарһсн халхс шинҗллһнә сеткүлд немх',
'tog-minordefault'            => 'Цуг сольлһн баһ чинртә таасн болулх',
'tog-previewontop'            => 'Сольлһна теегин өмн хәләвр үзүлх',
'tog-previewonfirst'          => 'Сольхла, хәләвр үзүлх.',
'tog-nocache'                 => 'Халхин кешлһн унтрах',
'tog-enotifwatchlistpages'    => 'Шинҗлсн халх сольхла, нанд e-mail бичг йовулх',
'tog-enotifusertalkpages'     => 'Мини ухалвр халх сольхла, нанд e-mail бичг йовулх',
'tog-enotifminoredits'        => 'Баһ сольлһн болв чигн болхла, нанд e-mail бичг йовулх',
'tog-enotifrevealaddr'        => 'Мини e-mail хайг зәңгллһнә бичгт үзүлх',
'tog-shownumberswatching'     => 'Тер халх шинҗлдг демнчнрин то үзүлх',
'tog-oldsig'                  => 'Бәәдг тәвсн һарна хәләвр:',
'tog-fancysig'                => 'Эврән тәвсн һарна бики темдлһн (авто заалһта уга)',
'tog-externaleditor'          => 'Һаза чикллгч олзлх (һанцхн эрдмчнрт, тана тоолцврт шишлң көг кергтә)',
'tog-externaldiff'            => 'Һаза йилһән үзүлдг програм олзлх (һанцхн эрдмчнрт, тана тоолцврт шишлң көг кергтә)',
'tog-showjumplinks'           => 'Туслмҗ заалһуд «-д/-т һарх» йовулх',
'tog-uselivepreview'          => 'Шамдһа хәләвр олзлх (JavaScript кергтә, амслһн)',
'tog-forceeditsummary'        => 'Учр-утх хоосн бәәхлә медүлх',
'tog-watchlisthideown'        => 'Шинҗллһнә сеткүлд мини сольлһиг бултулх',
'tog-watchlisthidebots'       => 'Шинҗллһнә сеткүлд көдлврин сольлһиг бултулх',
'tog-watchlisthideminor'      => 'Шинҗллһнә сеткүлд баһ сольлһиг бултулх',
'tog-watchlisthideliu'        => 'Шинҗллһнә сеткүлд демнчнрин сольлһиг бултулх',
'tog-watchlisthideanons'      => 'Шинҗллһнә сеткүлд далдурин сольлһиг бултулх',
'tog-watchlisthidepatrolled'  => 'Шинҗллһнә сеткүлд шүүсн сольлһиг бултулх',
'tog-ccmeonemails'            => 'Миниһәр талдан демнчнрт йовулсн бичглә әдл буулһавр нанд йовулх',
'tog-diffonly'                => 'Йилһәнә хөөн халхиг бичә үзүлх',
'tog-showhiddencats'          => 'Бултулсн әәшлүд үзүлх',
'tog-norollbackdiff'          => 'Хәрү кехлә йилһән бичә үзүлх',

'underline-always'  => 'Даңгин болх',
'underline-never'   => 'Кезәчн болшго',
'underline-default' => 'Хәләгчин таасн',

# Font style option in Special:Preferences
'editfont-style'     => 'Чикллһнә цаасна үзг-кевин янз:',
'editfont-default'   => 'Хәләлгчин көгәс',
'editfont-monospace' => 'Даңгин уудмта үзг-кев',
'editfont-sansserif' => 'Онь уга үзг-кев',
'editfont-serif'     => 'Оньта үзг-кев',

# Dates
'sunday'        => 'Нарн',
'monday'        => 'Сарң',
'tuesday'       => 'Мигмр',
'wednesday'     => 'Үлмҗ',
'thursday'      => 'Пүрвә',
'friday'        => 'Басң',
'saturday'      => 'Бембә',
'sun'           => 'Нрн',
'mon'           => 'Срң',
'tue'           => 'Мгр',
'wed'           => 'Үлм',
'thu'           => 'Прв',
'fri'           => 'Бсң',
'sat'           => 'Бмб',
'january'       => 'Туула сар',
'february'      => 'Лу сар',
'march'         => 'Моһа сар',
'april'         => 'Мөрн сар',
'may_long'      => 'Хөн сар',
'june'          => 'Мөчн сар',
'july'          => 'Така сар',
'august'        => 'Ноха сар',
'september'     => 'Һаха сар',
'october'       => 'Хулһн сар',
'november'      => 'Үкр сар',
'december'      => 'Бар сар',
'january-gen'   => 'Туула сарин',
'february-gen'  => 'Лу сарин',
'march-gen'     => 'Моһа сарин',
'april-gen'     => 'Мөрн сарин',
'may-gen'       => 'Хөн  сарин',
'june-gen'      => 'Мөчн сарин',
'july-gen'      => 'Така сарин',
'august-gen'    => 'Ноха сарин',
'september-gen' => 'Һаха сарин',
'october-gen'   => 'Хулһн сарин',
'november-gen'  => 'Үкр сарин',
'december-gen'  => 'Бар сарин',
'jan'           => 'Туу',
'feb'           => 'Лу',
'mar'           => 'Моһ',
'apr'           => 'Мөр',
'may'           => 'Хөн',
'jun'           => 'Мөч',
'jul'           => 'Так',
'aug'           => 'Нох',
'sep'           => 'Һах',
'oct'           => 'Хул',
'nov'           => 'Үкр',
'dec'           => 'Бар',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Әәшл|Әәшлүд}}',
'category_header'                => '«$1» әәшлд бәәдг халхс',
'subcategories'                  => 'Баһар әәшлүд',
'category-media-header'          => '«$1» әәшлд бәәдг боомгуд',
'category-empty'                 => "''Тер әәшл хоосн болҗана.''",
'hidden-categories'              => '{{PLURAL:$1|Бултулсн әәшл|Бултулсн әәшлүд}}',
'hidden-category-category'       => 'Бултулсн әәшлүд',
'category-subcat-count'          => '{{PLURAL:$2|Тер әәшл эн һанцхн баһар әәшлтә.|{{PLURAL:$1|$1 баһар әәшл үзүлв|$1 баһар әәшлүд үзүлв|$1 баһар әәшлүд}} $2 ут туршдан үзүлв.}}',
'category-subcat-count-limited'  => 'Тер әәшлд {{PLURAL:$1|нег баһар әәшл|$1 баһар әәшлүд}} болҗана.',
'category-article-count'         => '{{PLURAL:$2|Тер әәшл һанцхн халхта.|{{PLURAL:$1|$1 халхиг үзүлв|$1 халхсиг үзүлв|$1 халхсиг үзүлв}}, $2 ут туршдан.}}',
'category-article-count-limited' => 'Тер әәшлд {{PLURAL:$1|нег халх|$1 халхс}} болҗана.',
'category-file-count'            => '{{PLURAL:$2|Тер әәшлд һанцхн халх болҗана.|Терүнәс {{PLURAL:$1|нег боомг үзүлсн|$1 боомгуд үзүлсн}} $2 ут туршдан.}}',
'category-file-count-limited'    => 'Эн {{PLURAL:$1|боомг|$1 боомгуд}} тер әәшлд болҗана.',
'listingcontinuesabbrev'         => '(цааранднь)',
'index-category'                 => 'Индекссн халхс',
'noindex-category'               => 'Индекссн биш халхс',

'mainpagetext'      => "Йовудта Mediawiki гүүлһүдә тәвллһн.'''",
'mainpagedocfooter' => 'Тер бики закллһна теткүл ю кеһәд олзлх туск [http://meta.wikimedia.org/wiki/Help:Contents көтлвр] дастн.

== Туста заавр ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Көгүдә бүрткл]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki туск ЮмБи]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki шинҗллһнә бүрткл]',

'about'         => 'Тодлҗ бичлһн',
'article'       => 'Зүүл',
'newwindow'     => '(шин терзд)',
'cancel'        => 'Уга кех',
'moredotdotdot' => 'Цааранднь...',
'mypage'        => 'Мини эврә халх',
'mytalk'        => 'Күүндлһн бәәрм',
'anontalk'      => 'IP хайгна күндллһн',
'navigation'    => 'Орм медлһн',
'and'           => '&#32;болн',

# Cologne Blue skin
'qbfind'         => 'Хәәлһн',
'qbbrowse'       => 'Гүүһәд хәләх',
'qbedit'         => 'Чиклх',
'qbpageoptions'  => 'Тер халх',
'qbpageinfo'     => 'Халхин туск',
'qbmyoptions'    => 'Тана халхс',
'qbspecialpages' => 'Көдлхнә халхс',
'faq'            => 'Юм би',
'faqpage'        => 'Project:Юм би',

# Vector skin
'vector-action-addsection'   => 'Төриг немх',
'vector-action-delete'       => 'Һарһх',
'vector-action-move'         => 'Көндәх',
'vector-action-protect'      => 'Харсх',
'vector-action-undelete'     => 'Һарһх биш',
'vector-action-unprotect'    => 'Харсх биш',
'vector-namespace-category'  => 'Әәшл',
'vector-namespace-help'      => 'Цәәлһлһнә халх',
'vector-namespace-image'     => 'Боомг',
'vector-namespace-main'      => 'Халх',
'vector-namespace-media'     => 'Аһарин халх',
'vector-namespace-mediawiki' => 'Зәңг',
'vector-namespace-project'   => 'Проектин туск',
'vector-namespace-special'   => 'Көдлхнә халх',
'vector-namespace-talk'      => 'Меткән',
'vector-namespace-template'  => 'Кевләр',
'vector-namespace-user'      => 'Демнчна халх',
'vector-view-create'         => 'Бүтәх',
'vector-view-edit'           => 'Чиклх',
'vector-view-history'        => 'Тууҗин хәләвр',
'vector-view-view'           => 'Умшлһн',
'vector-view-viewsource'     => 'Темдгллһнә хәләвр',
'actions'                    => 'Үүлд',
'namespaces'                 => 'Нернә ус',
'variants'                   => 'Суңһлтс',

'errorpagetitle'    => 'Эндү',
'returnto'          => '«$1» тал хәрү ирх.',
'tagline'           => '{{grammar:genitive|{{SITENAME}}}} гидг һазрас өггцн',
'help'              => 'Цәәлһлһн',
'search'            => 'Хәәлһн',
'searchbutton'      => 'Хәәлһн',
'go'                => 'Ор',
'searcharticle'     => 'Ор',
'history'           => 'тууҗ',
'history_short'     => 'Тууҗ',
'updatedmarker'     => 'мини шидрә орлһна хөөн шинрүлсн',
'info_short'        => 'Өггцн',
'printableversion'  => 'Барин бәәдл',
'permalink'         => 'Даңгин заалһ',
'print'             => 'Барлх',
'edit'              => 'Чиклх',
'create'            => 'Бүтәх',
'editthispage'      => 'Эн халхиг чиклх',
'create-this-page'  => 'Эн халхиг бүтәх',
'delete'            => 'Һарһх',
'deletethispage'    => 'Эн халхиг һарһх',
'undelete_short'    => '$1 {{PLURAL:$1|сольлһиг|сольлһиг|сольлһиг}} босхҗ тохрар',
'protect'           => 'Харсх',
'protect_change'    => 'сольх',
'protectthispage'   => 'Эн халхиг харсх',
'unprotect'         => 'Харсх уга',
'unprotectthispage' => 'Тер халхиг харсх уга',
'newpage'           => 'Шин халх',
'talkpage'          => 'Тер халхин туск келх',
'talkpagelinktext'  => 'Меткән',
'specialpage'       => 'Көдлхнә халх',
'personaltools'     => 'Эврән зер-зев',
'postcomment'       => 'Шин хүв',
'articlepage'       => 'Зүүл үзх',
'talk'              => 'Меткән',
'views'             => 'Хәләврүд',
'toolbox'           => 'Зер-зев',
'userpage'          => 'Демнчна халх үзх',
'projectpage'       => 'Төсвин халх үзх',
'imagepage'         => 'Боомгин халх үзх',
'mediawikipage'     => 'Зәңгин халх үзх',
'templatepage'      => 'Кевләр халх үзх',
'viewhelppage'      => 'Цәәлһлһиг узх',
'categorypage'      => 'Әәшлин халх үзх',
'viewtalkpage'      => 'Меткән халх узх',
'otherlanguages'    => 'Талдан келәр',
'redirectedfrom'    => '($1 гидг һазрас авч одсмн)',
'redirectpagesub'   => 'Авч оддг халх',
'lastmodifiedat'    => 'Тер халх эн цагт сүл чикләд болҗ: $2, $1.',
'viewcount'         => 'Тер халхд $1 {{PLURAL:$1|дәкҗ|дәкҗ|дәкҗ}} орҗ.',
'protectedpage'     => 'Харссн халх',
'jumpto'            => 'Тал ирх:',
'jumptonavigation'  => 'Һазр медлһн',
'jumptosearch'      => 'хәәлһн',
'view-pool-error'   => 'Гемим тәвтн, ода серверүд хар-хату көдлмштә.
Дегд дала күн тер халх үзхәр бәәнә.
Буйн болтха, бәәҗәһәд дәкәд арһ хәәтн.

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} тускар',
'aboutpage'            => 'Project:Тодлҗ бичлһн',
'copyright'            => 'Өггцн $1 йоста орҗ болм',
'copyrightpage'        => '{{ns:project}}:Бичсн күүнә зөв',
'currentevents'        => 'Ода болсн йовдл',
'currentevents-url'    => 'Project:Ода болсн йовдл',
'disclaimers'          => 'Дааврас эс зөвшәрлһн',
'disclaimerpage'       => 'Project:Даарас эс зөвшәрлһн',
'edithelp'             => 'Чикллһнә дөң',
'edithelppage'         => 'Help:Чикллһн',
'helppage'             => 'Help:Һарг',
'mainpage'             => 'Нүр халх',
'mainpage-description' => 'Нүүр халх',
'policy-url'           => 'Project:Бодлһн',
'portal'               => 'Бүрдәцин хург',
'portal-url'           => 'Project:Бүрдәцин хург',
'privacy'              => 'Нууцин бодлһн',
'privacypage'          => 'Project:Нууцин бодлһн',

'badaccess'        => 'Зөвәнә эндү',
'badaccess-group0' => 'Та сурсн үүл кеҗ болшго.',
'badaccess-groups' => 'Эн үүл һанцхн {{PLURAL:$2|багас|багудас}} $1 кеҗ чадна.',

'versionrequired'     => "MediaWiki'н $1 һарц кергтә",
'versionrequiredtext' => "Тер халх олзхар, MediaWiki'н $1 һарц кергтә. 
[[Special:Version|Һарца халх]] хәләтн.",

'ok'                      => 'Тиим',
'retrievedfrom'           => '"$1" гидг халхас йовулсн',
'youhavenewmessages'      => 'Та $1та бәәнәт ($2).',
'newmessageslink'         => 'шин зәңгс',
'newmessagesdifflink'     => 'шидрә сольлһн',
'youhavenewmessagesmulti' => 'Таньд $1 деер шин зәңг ирсн бәәнә.',
'editsection'             => 'чиклх',
'editold'                 => 'чиклх',
'viewsourceold'           => 'ишиг үзх',
'editlink'                => 'чиклх',
'viewsourcelink'          => 'ишиг хәләх',
'editsectionhint'         => '«$1» гидг хүвиг чиклх',
'toc'                     => 'Һарг',
'showtoc'                 => 'үзүлх',
'hidetoc'                 => 'бултулх',
'thisisdeleted'           => '$1 гүүһәд хәләхү аль хәрүлхү?',
'viewdeleted'             => '$1 үзүлхү?',
'restorelink'             => '{{PLURAL:$1|$1 һарһсн сольлһн|$1 һарһсн сольлһн}}',
'feedlinks'               => 'Тер бәәдлтә',
'feed-invalid'            => 'Буру бичгдлһнә төлә сүвин янз.',
'feed-unavailable'        => 'Синдикацин сүв орлһта биш',
'site-rss-feed'           => '$1 — RSS-зәңг',
'site-atom-feed'          => '$1 — Atom-зәңг',
'page-rss-feed'           => '«$1» — RSS-зәнгллһн',
'page-atom-feed'          => '«$1» — Atom зәнгллһн',
'red-link-title'          => '$1 (халх бәәшго)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Зүүл',
'nstab-user'      => 'Демнч',
'nstab-media'     => 'Аһарин халх',
'nstab-special'   => 'Көдлхнә халх',
'nstab-project'   => 'Төслин халх',
'nstab-image'     => 'Боомг',
'nstab-mediawiki' => 'Зәңг',
'nstab-template'  => 'Кевләр',
'nstab-help'      => 'Цәәлһлһн',
'nstab-category'  => 'Әәшл',

# Main script and global functions
'nosuchaction'      => 'Иим үүл бәәшго',
'nosuchactiontext'  => "URL'д бичсн үүл буру болҗана.
Та URL бичәд эндү кеҗ болвза аль буру заалһас дахҗ.
Дәкәд, тер йовдл {{SITENAME}} төслин эндү болвза.",
'nosuchspecialpage' => 'Иим көдлхнә халх бәәшго',
'nospecialpagetext' => '<strong>Та сурсн көдлхнә халх бәәшго.</strong>

Чик көдлхнә халхин буулһавр: [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'               => 'Эндү',
'databaseerror'       => 'Өггцнә базин эндү',
'dberrortext'         => 'Өггцнә базд сурврин синтаксисин эндү аҗглв.
Эн заклһна теткүлин эндү болвза.
Шидрә өггцнә базд сурвр:
<blockquote><tt>$1</tt></blockquote>
<tt>«$2»</tt> функцас һарад бәәнә.
Өггцнә баз <tt>«$3: $4»</tt> эндү хәрү өгв.',
'dberrortextcl'       => 'Өггцнә базд сурврин синтаксисин эндү аҗглв.
Шидрә өггцнә базд сурвр:
«$1»
«$2» функцас һарад бәәнә.
Өггцнә баз «$3: $4» эндү хәрү өгв.',
'missing-article'     => 'Өггцнә халһлд сурсн халхин бичг олв уга. Эн халх олх йоста: "$1" нертә $2. 

Тер йовдл һарһсн халхна тууҗин өңгрсн заалһиг дахлһна арһ болад бәәнә. 

Эс гиҗ, тиим болх зөвтә, та заклһна теткүлин эндүһиг олв. 
Буйн болтха, URL заалһ бичәд, тер йовдлин туск [[Special:ListUsers/sysop|закрачд]] келтн.',
'missingarticle-rev'  => '($1 тойгта халхна янз)',
'missingarticle-diff' => '(йилһән: $1, $2)',
'internalerror'       => 'Дотрнь эндү',
'internalerror_info'  => 'Дотрнь эндү: $1',
'filerenameerror'     => 'Боомгин нериг «$1»-с «$2» болһн сольҗ чаддго',
'filedeleteerror'     => '«$1» боомг һарһҗ чаддго.',
'unexpected'          => 'Таалһта уга кемҗә: «$1» = «$2».',
'badtitle'            => 'Буру нернь',
'badtitletext'        => 'Сурсн нерн буру, хоосн, аль му бичсн келн хоорнд нертә. Тиим чигн биз, нерн зөв уга үзгтә.',
'viewsource'          => 'Ишиг хәләх',
'viewsourcefor'       => '$1 халх',
'actionthrottled'     => 'Хурдна заг',
'sqlhidden'           => '(SQL сурвр бултулсн)',
'ns-specialprotected' => 'Шишлң халх чиклсн бәәх болшго.',

# Virus scanner
'virus-unknownscanner' => 'медгдго антивирус:',

# Login and logout pages
'logouttext'              => "'''Та һарад бәәнәт.'''

Та {{SITENAME}} гидг ормиг нертә уга олзлҗ чаднат, аль та [[Special:UserLogin|дәкәд орҗ]] цацу аль талдан нертә чаднат.
Зәрм халхс цааранднь та ода чигн орсн мет үзүлҗ чаддг тускар темдглтн (та хәләчин санлиг цеврлтл).",
'welcomecreation'         => '== Ирхитн эрҗәнәвидн, $1! ==
Таднар шин бичгдлһн бүтв.
Тадна [[Special:Preferences|{{SITENAME}} preferences]] сольҗ бичә мартн.',
'yourname'                => 'Демнчна нернь:',
'yourpassword'            => 'Нууц үг:',
'yourpasswordagain'       => 'Нууц үгиг давтн:',
'remembermypassword'      => 'Мини нерн эн тоолдврд тодлх',
'yourdomainname'          => 'Тана домен:',
'login'                   => 'Орлһн',
'nav-login-createaccount' => 'Харһх / бичгдлһн кех',
'loginprompt'             => '{{SITENAME}} тал орлһна төлә, та «cookies» олзлдг кергтә.',
'userlogin'               => 'Орх аль бичгдлһиг бүтәх',
'userloginnocreate'       => 'Харһх',
'logout'                  => 'Һарх',
'userlogout'              => 'Һарх',
'notloggedin'             => 'Та орв биш',
'nologin'                 => "Бичгдлһта уга? '''$1'''.",
'nologinlink'             => 'Бичгдлһиг бүтәх',
'createaccount'           => 'Бичгдлһиг бүтәх',
'gotaccount'              => "Бичгдлһтә? '''$1'''.",
'gotaccountlink'          => 'Харһтн',
'createaccountmail'       => 'электрона улаһар',
'userexists'              => 'Эн нер олзлдг юмн. 
Буйн болтха, талдан нернь автн.',
'loginerror'              => 'Орлһна эндү',
'createaccounterror'      => 'Бичгдлһиг бүтәх болшго: $1',
'noname'                  => 'Та зөвтә демнчна нернь бичв уга.',
'loginsuccesstitle'       => 'Йовудта орлһн',
'loginsuccess'            => "''' Тадн ода «$1» нертә {{SITENAME}} гидг нерәдлһтә төсвд бәәнәт.'''",
'nosuchuser'              => '«$1» гидг нерәдлһтә демнч бәәшго. 
Демнчна нерт баһ болн ик үзгүд әдл биш болна. 
«<nowiki>$1</nowiki>» гидг нерәдлһтә демнч бәәшго.
Бичлһиг шүүтн аль [[Special:UserLogin/signup|бигчдлһиг бүтәтн]].',
'nosuchusershort'         => '«<nowiki>$1</nowiki>» гидг нерәдлһтә демнч бәәшго.
Бичлһиг шүүтн.',
'nouserspecified'         => 'Та демнчна нернь бичх йостав.',
'login-userblocked'       => 'Тер демнч бүслсн, харһад орҗ болшго бәәнә.',
'wrongpassword'           => 'Та буру нууц үг бичв.
Дәкәд арһ хәәтн.',
'wrongpasswordempty'      => 'Та хоосн нууц үгиг бичв. 
Дәкәд арһ хәәтн.',
'passwordtooshort'        => 'Нууц үг баһар биш $1 {{PLURAL:$1|үзгтә|үзгүдта|үзгүдта}} бәәх йоста.',
'password-name-match'     => 'Нууц үг денмнчна нертә әдл биш бәәх йоста.',
'mailmypassword'          => 'Шин нууц үгиг E-mail бичгәр йовулҗ',
'accountcreated'          => 'Бичгдллһн бүтәв.',
'loginlanguagelabel'      => 'Келн: $1',

# Password reset dialog
'resetpass'                 => 'Нууц үгиг сольх',
'resetpass_header'          => 'Бичгдллһнә нууц үгиг сольх',
'oldpassword'               => 'Көгшн нууц үг:',
'newpassword'               => 'Шин нууц үг:',
'retypenew'                 => 'Шин нууц үгиг дәкәд бичтн:',
'resetpass_success'         => 'Тана нууц үгиг йовудта сольв! Та ода орнат...',
'resetpass-submit-loggedin' => 'Нууц үгиг сольх',
'resetpass-submit-cancel'   => 'Уга кех',

# Edit page toolbar
'bold_sample'     => 'Тарһн бичг',
'bold_tip'        => 'Тарһн бичг',
'italic_sample'   => 'Өкәсн бичг',
'italic_tip'      => 'Өкәсн бичг',
'link_sample'     => 'Заалһна нерн',
'link_tip'        => 'Өвр заалһ',
'extlink_sample'  => 'http://www.example.com заалһна нернь',
'extlink_tip'     => 'Һаза заалһ (http:// гидг эклц бичә мартн)',
'headline_sample' => 'Толһа нерн',
'headline_tip'    => 'Дү толһа нерн',
'math_sample'     => 'Энд тегштклиг бичтн',
'math_tip'        => 'Тегшткл (LaTeX)',
'nowiki_sample'   => 'Энд темдглһтә уга бичгиг бичтн',
'nowiki_tip'      => 'Бики темдглһиг басх',
'image_tip'       => 'Орцулсн боомг',
'media_tip'       => 'Боомгин заалһ',
'sig_tip'         => 'Тана тәвсн һар цагин темдгтә',
'hr_tip'          => 'Кевтдг татасн (дундин бәәдлтә олзлтн)',

# Edit pages
'summary'                          => 'Учр-утх:',
'subject'                          => 'Төр/нерәдлһн:',
'minoredit'                        => 'Баһ чикллһн',
'watchthis'                        => 'Шинҗлх',
'savearticle'                      => 'Хадһлх',
'preview'                          => 'Хәләвр',
'showpreview'                      => 'Хәләвр',
'showdiff'                         => 'Йилһән',
'anoneditwarning'                  => "'''Урдаснь зәңг:''' та орв биш.
Тадна IP хайг эн халхна чикллһнә сеткүлд бичҗ авх.",
'summary-preview'                  => 'Эн учр-утхта болх:',
'subject-preview'                  => 'Тер һарчиг болх:',
'blockedtitle'                     => 'Демнч бүслгдәд бәәнә.',
'loginreqlink'                     => 'харһх',
'accmailtitle'                     => 'Нууц үгтә бичг йовулла.',
'newarticle'                       => '(Шин)',
'newarticletext'                   => "Та заалһиг дахад бәәдг уга халхд ирв. 
Терүг бүтәҗ болхла, дораһар терзд бичтн (дәкәд өггцнә төлә [[{{MediaWiki:Helppage}}|тәәлвр]] хәләтн). 
Та эн һазрт эндүһәр бәәхлә, '''Хәрү''' дарциг дартн.",
'noarticletext'                    => "Эн халх хоосн. Та [[Special:Search/{{PAGENAME}}|эн нернә сананд орулһна хәәх]] , <span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} бүртклин бичгт хәәх], аль '''[{{fullurl:{{FULLPAGENAME}}|action=edit}} бүтәх]'''</span>.",
'clearyourcache'                   => "'''Оньган өгтн:''' Кесн сольлһн үзхәр, тана хәләлгчин кеш цеврүлтн: '''Mozilla / Firefox''': ''Ctrl+Shift+R'', '''IE:''' ''Ctrl+F5'', '''Safari''': ''Cmd+Shift+R'', '''Konqueror''': ''F5'', '''Opera''': ''Tools→Preferences'' менүһәс.",
'usercssyoucanpreview'             => "'''Селвг:''' тана шин CSS боомг шүүҗ хадһлар, «Хәләвр» товч олзлтн.",
'userjsyoucanpreview'              => "'''Селвг:''' тана шин JS боомг шүүҗ хадһлар, «Хәләвр» товч олзлтн.",
'usercsspreview'                   => "'''Тана CSS боомгин мел хәләвр бәәдг тускар тодлтн, тер ода чигн хадһлсн уга!'''",
'userjspreview'                    => "'''Тана JavaScript боомгин мел хәләвр бәәдг тускар тодлтн. Тана сольлһн ода чигн хадһлсн уга!'''",
'userinvalidcssjstitle'            => "'''Оньг өгтн:''' «$1» гидг нерәдлһтә хувцнь олҗ биш. Күүнә .css болн .js халхс һанцхн бичкн үзгүдтә бичсн кергтә, үлгүрнь «{{ns:user}}:Болвчн/monobook.css»; «{{ns:user}}:Болвчн/Monobook.css» - буру.",
'updated'                          => '(Шинрүлсн)',
'note'                             => "'''Аҗгллһн:'''",
'previewnote'                      => "'''Эн мел хәләвр бәәдг тускар тодлтн.'''
Тана сольлһн ода чигн хадһлсн уга!",
'previewconflict'                  => 'Тер хәләвр деегүрк чикллһнә теегт бәәдг бичг хадлһҗ бичсн мет үзүлнә.',
'session_fail_preview'             => "'''Гемим тәвтн, сервер тана сольлһта даңдад болв. Юнгад гихлә, тана харһлһна медүллһн геев.
Буйн болтха, дәкәд арһ хәәтн.
Тер эндү давтхла, [[Special:UserLogout|һартн]] тегәд бас харһтн.'''",
'editing'                          => 'Чикллһн: $1',
'editingsection'                   => '«$1» гидг халхна чикллһн (хүв)',
'editconflict'                     => 'Чикллһнә керүл: $1',
'yourtext'                         => 'Тана бичсн',
'yourdiff'                         => 'Йилһән',
'copyrightwarning'                 => "Буйн болтха, цуг өгүллһн {{SITENAME}} төлә $2 гидг закаһар кесн, тоолсн бәәдг тускар тодлтн (Дәкәд өггцд төлә $1 хәләтн).  Та тана бичсн чилклсн аль делгрңсн бәәҗ седхлә биш, эн ормд бичә бичтн.<br /> Дәкәд та маднд эн эврәнь бичсн, күмн әмтнә хазас аль цацу сул медснәс бәәдг үгән өгнәт. '''Зөвән авхла уга, харссн бичсн күүнә көдлмш бичә тәвтн!'''",
'copyrightwarning2'                => "Буйн болтха, цуг өгүллһн {{SITENAME}} төлә чиклсн аль һарһсн бәәдг чадта тускар тодлтн.  Та тана бичсн чилклсн аль делгрңсн бәәҗ седхлә биш, эн ормд бичә бичтн.<br /> Дәкәд та маднд эн эврәнь бичсн, күмн әмтнә хазас аль цацу сул медснәс бәәдг үгән өгнәт ($1 хәләтн). '''Зөвән авхла уга, харссн бичсн күүнә көдлмш бичә тәвтн!'''",
'semiprotectedpagewarning'         => "'''Оньган өгтн:''' тер халх харссн болҗана, тер учрар эниг бичгдлһтә демнчнр һанцхн чиклҗ чадна. 
Нөкд төлә, эн шидрә сеткүлин бичвр:",
'templatesused'                    => 'Эн халхд олзлсн {{PLURAL:$1|зурас|зурас}}:',
'templatesusedpreview'             => '{{PLURAL:$1|Кевләр|Кевләрмүд}} эн хәләврт олзлсн:',
'template-protected'               => '(харссн)',
'template-semiprotected'           => '(зәрм харссн)',
'hiddencategories'                 => 'Эн халх тер $1 {{PLURAL:$1|бултулсн әәшләс|бултулсн әәшлүдәс|бултулсн әәшлүдәс}}:',
'permissionserrorstext-withaction' => 'Та $2 кеҗ болшго. Юнгад гихлә, эн {{PLURAL:$1|учрар|учрар}}:',
'edit-conflict'                    => 'Чикллһнә керүл.',

# Parser/template warnings
'parser-template-loop-warning' => 'Зуран бүтү нүдлв: [[$1]]',

# History pages
'viewpagelogs'           => 'Тер халхна сеткүлдүд үзүлх',
'currentrev-asof'        => 'Ода болсн янз ($1)',
'revisionasof'           => 'Тер цагин янз: $1',
'previousrevision'       => '← Урдк янз',
'nextrevision'           => 'Дарук янз →',
'currentrevisionlink'    => 'Ода болсн янз',
'cur'                    => 'ода',
'next'                   => 'дарук',
'last'                   => 'урдк',
'page_first'             => 'түрүн',
'page_last'              => 'кенз',
'histlegend'             => "Тәәлвр: (ода) — одачн янзас йилһән; (урдк) — урдк янзас йилһән; '''б''' — баһ сольлһн",
'history-fieldset-title' => 'Тууҗиг хәләх',
'histfirst'              => 'Эрт',
'histlast'               => 'Шидрә',
'historyempty'           => '(хоосн)',

# Revision deletion
'rev-delundel'               => 'үзүлх/бултулх',
'rev-showdeleted'            => 'үзүлх',
'revdelete-show-file-submit' => 'Тиим',
'revdelete-radio-set'        => 'Ээ',
'revdelete-radio-unset'      => 'Уга',
'revdel-restore'             => 'Үзгдллһиг сольх',
'pagehist'                   => 'Халхна тууҗ',
'revdelete-otherreason'      => 'Талдан/дәкәд учр:',

# History merging
'mergehistory-reason' => 'Учр:',

# Merge log
'revertmerge' => 'Хувах',

# Diffs
'history-title'           => '$1 — сольлһна тууҗ',
'difference'              => '(Йилһән)',
'lineno'                  => '$1 мөр:',
'compareselectedversions' => 'Суңһсн янзс әдлцүлх',
'editundo'                => 'уга кех',

# Search results
'searchresults'                  => 'Хәәлһнә ашуд',
'searchresults-title'            => 'Хәәлһнә ашуд "$1" төлә',
'searchresulttext'               => 'Дәкәд өггцна төлә,  [[{{MediaWiki:Helppage}}|дөң өггдг һазрт]] хәләтн.',
'searchsubtitle'                 => '«[[:$1]]» сурвра ([[Special:Prefixindex/$1|эн нертә эклсн халхс]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|эн нерт заадг]])',
'searchsubtitleinvalid'          => "Тадн '''$1''' төлә хәәләт",
'notitlematches'                 => 'Нернә ирлцлһн уга',
'notextmatches'                  => 'Әдл бичг халхд уга',
'prevn'                          => 'урдк {{PLURAL:$1|$1}}',
'nextn'                          => 'дарук {{PLURAL:$1|$1}}',
'viewprevnext'                   => 'Гүүһәд хәләх ($1 {{int:pipe-separator}} $2) ($3)',
'searchprofile-articles'         => 'Зүүлс',
'searchprofile-project'          => 'Цәәлһлһнә болн төсвин халхс',
'searchprofile-images'           => 'Үзгдл-соңсвр',
'searchprofile-everything'       => 'Цуһар',
'searchprofile-articles-tooltip' => '$1 гидг зүүлд хәәх',
'searchprofile-project-tooltip'  => '$1 гидг төсвд хәәх',
'searchprofile-images-tooltip'   => 'Боомг хәәх',
'search-result-size'             => '$1 ({{PLURAL:$2|$2 үг|$2 үгмүд|$2 үгмүд}})',
'search-result-score'            => 'Әдлцән: $1 %',
'search-redirect'                => '(авч одлһн $1)',
'search-section'                 => '($1 хүв)',
'search-suggest'                 => 'Та эниг таанат: $1 ?',
'search-interwiki-caption'       => 'Садта проектмуд',
'search-interwiki-default'       => '$1 ашуд:',
'search-interwiki-more'          => '(дәкәд)',
'search-mwsuggest-enabled'       => 'селвгтә',
'search-mwsuggest-disabled'      => 'селвг уга',
'mwsuggest-disable'              => 'AJAX селвг унтрах',
'searcheverything-enable'        => 'Цуг нернә ууд хәәх',
'searchall'                      => 'цуг',
'nonefound'                      => "'''Нүдлтн''': Мел зәрм нернә у талд урдаснь хәәсмн.
''all:'' гидг эклц немтн та һазр болһнд хәәх.",
'powersearch'                    => 'Күчн хәәлһн',
'powersearch-legend'             => 'Күчн хәәлһн',
'powersearch-ns'                 => 'Эн нернә у дотран хәәх:',
'powersearch-redir'              => 'Авч одлһуд үзүлх',
'powersearch-field'              => 'Хәәх',
'powersearch-togglenone'         => 'Уга',

# Quickbar
'qbsettings' => 'Ормин самбр',

# Preferences page
'preferences'                 => 'Дурллһн',
'mypreferences'               => 'Көгүд',
'prefs-edits'                 => 'Чикллһнә то:',
'prefsnologin'                => 'Та харһв биш',
'prefsnologintext'            => 'Та <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} харһх]</span> кергтә,  тегәд көгүдиг сольҗ чаднат.',
'changepassword'              => 'Нууц үгиг сольҗ',
'prefs-skin'                  => 'Хувцнь',
'skin-preview'                => 'Хәләвр',
'prefs-math'                  => 'Тетшкүлүд',
'datedefault'                 => 'Келхлә уга',
'prefs-datetime'              => 'Цаг хуһцан',
'prefs-personal'              => 'Демнчна көгүд',
'prefs-rc'                    => 'Шидрә сольлһн',
'prefs-watchlist'             => 'Шинҗллһнә сеткүл',
'prefs-watchlist-days'        => 'Шинҗллһнә седкүлд үзүлсн ик гисн өдрин то:',
'prefs-watchlist-days-max'    => '(ик гисн 7 хонг)',
'prefs-misc'                  => 'Талдан',
'prefs-resetpass'             => 'Нууц угиг сольҗ',
'prefs-email'                 => "E-mail'ын көгүд",
'prefs-rendering'             => 'Һазад бәәдл',
'saveprefs'                   => 'Хадһлх',
'restoreprefs'                => 'Цуг эклцин көгүдиг босхҗ тохрар',
'prefs-editing'               => 'Чикллһн',
'rows'                        => 'Мөрд:',
'columns'                     => 'Бахд:',
'resultsperpage'              => 'Халхд бәәдг олсн бичврин то:',
'savedprefs'                  => 'Тана көгүдиг хадһлв.',
'timezonelegend'              => 'Часин бүс:',
'localtime'                   => 'Бәәрн һазра цаг:',
'timezoneuseserverdefault'    => 'Серверинь олзлх',
'timezoneuseoffset'           => 'Талдан (көндллһн заатн)',
'timezoneoffset'              => 'Көндллһн¹:',
'servertime'                  => 'Серверин цаг:',
'guesstimezone'               => 'Хәләлгчәс авх',
'timezoneregion-africa'       => 'Априк',
'timezoneregion-america'      => 'Америк',
'timezoneregion-antarctica'   => 'Антарктик',
'timezoneregion-arctic'       => 'Арктик',
'timezoneregion-asia'         => 'Азь',
'timezoneregion-atlantic'     => 'Атлантин дала',
'timezoneregion-australia'    => 'Австрал',
'timezoneregion-europe'       => 'Европ',
'timezoneregion-indian'       => 'Энетекгин дала',
'timezoneregion-pacific'      => 'Номһн дала',
'prefs-searchoptions'         => 'Хәәлһнә көг',
'prefs-namespaces'            => 'Нернә ус',
'prefs-custom-css'            => 'Онц CSS',
'prefs-custom-js'             => 'Онц JS',
'prefs-emailconfirm-label'    => 'E-mail батлһн:',
'youremail'                   => 'E-mail хайг:',
'username'                    => 'Демнчна нер:',
'uid'                         => 'Демнчна тойг (ID):',
'prefs-memberingroups'        => '{{PLURAL:$1|Багин|Багдудин}} хүв:',
'prefs-registration'          => 'Темдглҗ  бүртклһнә цаг:',
'yourrealname'                => 'Үнн нерн:',
'yourlanguage'                => 'Бәәдлин келн:',
'yournick'                    => 'Тәвсн һар:',
'prefs-help-signature'        => 'Меткән халхна бичсн бичгт «<nowiki>~~~~</nowiki>» немәд һаран тәвх кергтә. Тер үзгүд тана тәвсн һарт болн цагин бичлгт болулх.',
'yourgender'                  => 'Киисн:',
'gender-unknown'              => 'Бичсн уга',
'gender-male'                 => 'Эр',
'gender-female'               => 'Эм',
'prefs-help-gender'           => 'Эн дәкәд бәәдг: чик күндллһн тоолвртар төлә. Эн өггцн цуг әмтнә болх.',
'email'                       => 'E-mail хайг',
'prefs-help-realname'         => 'Үнн нернь та эврә дурар бичнәт. Бичлхлә, эн тәвсн һарт элзлдг бәәх.',
'prefs-help-email'            => 'E-mail хайг та эврә дурар бичнәт. Бичхлә, тадн шин түлкүр үгиг бичгәр йовулсн өгҗ чаднат (мартхла). Тадн дәкәд талдан улсд тана күндллһнә халхар күндлҗ зөв өгҗ чаднат, тана E-mail үзүләд уга.',
'prefs-i18n'                  => 'Олн орни бәәлһн',
'prefs-signature'             => 'Тәвсн һаран',
'prefs-advancedediting'       => 'Дәкәд көгүд',
'prefs-advancedrc'            => 'Дәкәд көгүд',
'prefs-advancedrendering'     => 'Дәкәд көгүд',
'prefs-advancedsearchoptions' => 'Дәкәд көгүд',
'prefs-advancedwatchlist'     => 'Дәкәд көгүд',
'prefs-diffs'                 => 'Йилһәс',

# User rights
'userrights-reason' => 'Учр:',

# Groups
'group'               => 'Баг:',
'group-user'          => 'Демнчнр',
'group-autoconfirmed' => 'Эврә батлсн демнчнр',
'group-bot'           => 'Көдлврүд',
'group-sysop'         => 'Закрачуд',
'group-bureaucrat'    => 'Нойнчуд',
'group-all'           => '(цуг)',

'group-user-member'          => 'Демнч',
'group-autoconfirmed-member' => 'Эврә батлсн демнчнр',
'group-bot-member'           => 'Көдлвр',
'group-sysop-member'         => 'Закрач',
'group-bureaucrat-member'    => 'Нойнч',

'grouppage-user'          => '{{ns:project}}:Демнч',
'grouppage-autoconfirmed' => '{{ns:project}}:Эврә батлсн демнчнр',
'grouppage-bot'           => '{{ns:project}}:Көдлврүд',
'grouppage-sysop'         => '{{ns:project}}:Закрачуд',
'grouppage-bureaucrat'    => '{{ns:project}}:Нойнчуд',

# User rights log
'rightslog'  => 'Демнчна зөвәнә сеткүл',
'rightsnone' => '(уга)',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'эн халхиг чиклх',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|сольлһн|сольлһн}}',
'recentchanges'                  => 'Шидрә сольлһн',
'recentchanges-legend'           => 'Шидрә сольлһна көгүд',
'recentchangestext'              => 'Эн цагин дараһар бичсн шидрә сольлһн',
'recentchanges-feed-description' => 'Эн зәңгллһд шидрә хүврһд шинҗлх.',
'recentchanges-label-legend'     => 'Тәәлвр: $1.',
'recentchanges-legend-newpage'   => '$1 — шин халх',
'recentchanges-label-newpage'    => 'Тер үүләр шин халх бүтәв',
'recentchanges-legend-minor'     => '$1 — баһ сольлһн',
'recentchanges-label-minor'      => 'Эн баһ чинртә сольлһн',
'recentchanges-legend-bot'       => '$1 — көдлврә сольлһн',
'recentchanges-label-bot'        => 'Эн сольлһн көдлвр (робот) кехв',
'rcnote'                         => "{{PLURAL:$1|'''$1''' шидрә сольлһн|'''$1''' шидрә сольлһн|'''$1''' шидрә сольлһн}}, '''$2''' өдрә,  $5 $4 цагин.",
'rclistfrom'                     => 'Тер цагас авн сольлһн үзүлх: $1.',
'rcshowhideminor'                => 'баһ чикллһиг $1',
'rcshowhidebots'                 => 'көдлврүдиг $1',
'rcshowhideliu'                  => 'демнчнриг $1',
'rcshowhideanons'                => 'нер уга демнчнриг $1',
'rcshowhidemine'                 => 'мини чикллһиг $1',
'rclinks'                        => 'Кенз $1 сольлһн, кенз $2 өдрмүдт үзүлх<br />$3',
'diff'                           => 'йилһ',
'hist'                           => 'тууҗ',
'hide'                           => 'бултулх',
'show'                           => 'үзүлх',
'minoreditletter'                => 'б',
'newpageletter'                  => 'Ш',
'boteditletter'                  => 'к',
'newsectionsummary'              => '/* $1 */ Шин хүв',
'rc-enhanced-expand'             => 'Тодрхасиг үзүлх (JavaScript кергтә)',
'rc-enhanced-hide'               => 'Тодрхасиг бултулх',

# Recent changes linked
'recentchangeslinked'          => 'Садн чикллһн',
'recentchangeslinked-feed'     => 'Садта чикллһн',
'recentchangeslinked-toolbox'  => 'Садта чикллһн',
'recentchangeslinked-title'    => '$1 садта сольлһн',
'recentchangeslinked-noresult' => 'Садта халх заасн цагт сольсн уга',
'recentchangeslinked-summary'  => "Эн тер халх заалдг халхсин (аль тер янзин халхсин) шидрә сольлһн.
Тана [[Special:Watchlist|шинҗллһнә сеткүлин]] халхс '''тарһн''' бичәтә.",
'recentchangeslinked-page'     => 'Халхна нернь:',
'recentchangeslinked-to'       => 'Зөрүһәр, эн халхд заалдг халхсин хүврлһиг үзүлх',

# Upload
'upload'            => 'Боомгиг тәвх',
'uploadbtn'         => 'Боомгиг тәвх',
'uploadnologintext' => 'Та [[Special:UserLogin|харһх]] кергтә.',
'uploaderror'       => 'Тәвллһнә эндү',
'uploadlogpage'     => 'Тәвллһнә сеткүл',
'filename'          => 'Боомгна нернь',
'filedesc'          => 'Учр-утх',
'fileuploadsummary' => 'Учр-утх:',
'successfulupload'  => 'Йовудта тәвллһн',
'savefile'          => 'Хадһлх',
'uploadedimage'     => '«[[$1]]» тәвв',

'license'        => 'Закан:',
'license-header' => 'Закан:',

# Special:ListFiles
'imgfile'               => 'боомг',
'listfiles'             => 'Боомгин буулһавр',
'listfiles_date'        => 'Өдр',
'listfiles_name'        => 'Нернь',
'listfiles_user'        => 'Демнч',
'listfiles_size'        => 'Кемҗән',
'listfiles_description' => 'Тодлҗ бичлһн',
'listfiles_count'       => 'Янзс',

# File description page
'file-anchor-link'          => 'Боомг',
'filehist'                  => 'Боомгин тууҗ',
'filehist-help'             => 'Боомгин өңгрсн  цагин янз хәләх, цагиг дартн.',
'filehist-deleteall'        => 'цуг һарһх',
'filehist-deleteone'        => 'һарһх',
'filehist-current'          => 'ода цагин',
'filehist-datetime'         => 'Өдр/цаг',
'filehist-thumb'            => 'Зураллһн',
'filehist-thumbtext'        => '$1 янзин зураллһн',
'filehist-user'             => 'Демнч',
'filehist-dimensions'       => 'Юмна кир',
'filehist-comment'          => 'Аҗгллһн',
'imagelinks'                => 'Боомгд заалһуд',
'linkstoimage'              => '{{PLURAL:$1|Эн $1 халх|Эн $1 халхс|Эн $1 халхс}} тер боомгд заалдг бәәнә:',
'sharedupload'              => 'Эн боомг $1 ормас. Териг талдан төсвд олзлҗ болх.',
'uploadnewversion-linktext' => 'Тер боомгин шин һарц тәвх',

# Random page
'randompage' => 'Уршг зүүл',

# Statistics
'statistics'                   => 'То бүрткл',
'statistics-header-pages'      => 'Халхарн то бүрткл',
'statistics-header-edits'      => 'Сольлһна то бүрткл',
'statistics-header-views'      => 'Хәләврин то бүрткл',
'statistics-header-users'      => 'Демнчәрн то бүрткл',
'statistics-articles'          => 'Зүүлс',
'statistics-pages'             => 'Халхс',
'statistics-pages-desc'        => 'Цуг бикид бәәдг халхс, тер тоот меткән халхс, авч одлһн, болв нань чигн.',
'statistics-files'             => 'Тәвсн боомгуд',
'statistics-edits'             => '{{SITENAME}} эклсн цагас авн сольлһна то',
'statistics-edits-average'     => 'Халхарн сольлһна то',
'statistics-views-total'       => 'Цуг хәләврин то',
'statistics-views-peredit'     => 'Сольлһарн хәләврин то',
'statistics-users'             => 'Бичгдлһтә [[Special:ListUsers|демнчнр]]',
'statistics-users-active'      => 'Үүлтә демнчнр',
'statistics-users-active-desc' => '{{PLURAL:$1|$1 өдрт|$1 өдрмүдт|$1 өдрмүдт}} болв чигн үүл кесн демнчнр',
'statistics-mostpopular'       => 'Маш хәләсн халхс',

'brokenredirects-edit'   => 'чиклх',
'brokenredirects-delete' => 'һарһх',

# Miscellaneous special pages
'nbytes'        => '$1 {{PLURAL:$1|байд|байдуд|байдуд}}',
'nmembers'      => '$1 {{PLURAL:$1|мөч|мөчин|мөчүд}}',
'prefixindex'   => 'Цуг халхс эн эклцтә',
'newpages'      => 'Шин халхс',
'move'          => 'Көндәх',
'movethispage'  => 'Эн халхд шин нер аль шин орм өгх',
'pager-newer-n' => '{{PLURAL:$1|шинәр 1|шинәр $1}}',
'pager-older-n' => '{{PLURAL:$1|көгшәр 1|көгшәр $1}}',

# Book sources
'booksources'               => 'Дегтрин делгүрс',
'booksources-search-legend' => 'Дегтр туск хәәх',
'booksources-go'            => 'Ор',

# Special:Log
'log' => 'Сеткүлс',

# Special:AllPages
'allpages'       => 'Цуг халхс',
'alphaindexline' => '$1 хөөн, $2 күртл',
'prevpage'       => 'Урдк халх ($1)',
'allpagesfrom'   => 'Эн эклцта халхс асрх:',
'allpagesto'     => 'Энд асрлһиг зогсх:',
'allarticles'    => 'Цуг халхс',
'allpagessubmit' => 'Орх',

# Special:LinkSearch
'linksearch' => 'Һаза заалһуд',

# Special:Log/newusers
'newuserlogpage'          => 'Бичгдлһнә сеткүл',
'newuserlog-create-entry' => 'Шин демнч',

# Special:ListGroupRights
'listgrouprights-members' => '(мөчүдин сеткүл)',

# E-mail user
'emailuser' => 'Энд E-mail йовулх',

# Watchlist
'watchlist'         => 'Шинҗллһнә сеткүл',
'mywatchlist'       => 'Шинҗллһнә сеткүл',
'watchlistfor'      => "('''$1''' төлә)",
'addedwatch'        => 'Шинҗллһнә сеткүлд немв.',
'addedwatchtext'    => "«[[:$1]]» гидг нерәдлһтә халх тана [[Special:Watchlist|шинҗллһнә сеткүлд]] немв.
Тегәд тер халхна болн терүнә ухалврин сольлһн энд шиҗлсн болх. Эн халх '''тарһн'' үзгәр [[Special:RecentChanges|шидрә сольлһна]] халхд бичсн (амр умшхар) болх.",
'removedwatch'      => 'Шинҗллһнә сеткүләс һарһв.',
'removedwatchtext'  => '«[[:$1]]» халх тана [[Special:Watchlist|шинҗллһнә сеткүләс]] һарһв.',
'watch'             => 'Шинҗлх',
'watchthispage'     => 'Эн халхиг шинҗлх',
'unwatch'           => 'Шинҗлх биш',
'watchlist-details' => '$1 {{PLURAL:$1|халх|халхс|халхс}} ухалвр угаһар тана шиҗллһнә сеткүлд.',
'wlshowlast'        => 'Кенз $1 часд $2 өдрт $3 үзүлх',
'watchlist-options' => 'Шинҗллһнә сеткүлин көгүд',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Шинҗллһнә бүтлклд немлһн...',
'unwatching' => 'Шинҗлһнә бүрткләс һарһлһн...',

'changed'     => 'сольв',
'created'     => 'бүтәв',
'enotif_body' => 'Мендвт, күндтә $WATCHINGUSERNAME,

$PAGEEDITDATE цагт {{SITENAME}} төсвин $PAGETITLE халхиг $PAGEEDITOR $CHANGEDORCREATED. Ода болсн халхна янз үзҗ седхлә, $PAGETITLE_URL хәләтн.

$NEWPAGE

Сольлһнә учр-утх: $PAGESUMMARY $PAGEMINOREDIT

Сольлчд бичг йовуллһн:
e-mail\'ар $PAGEEDITOR_EMAIL
бикиһәр $PAGEEDITOR_WIKI

Эн халх орхла биш, терүнә дәкәд сольлһн болхла, медүллһн бәәх уга. Тааһар шинҗлсн халхс сольлһна туск медүллһн унтраҗ чаднат.

             {{grammar:genitive|{{SITENAME}}}} зәңгллһнә церглт

--
Тана шинҗллһнә сеткүлин көгүдиг сольҗ седхлә, эниг дахтн:
{{fullurl:{{#special:Watchlist}}/edit}}

Хәрү холва болн тус:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'            => 'Эн халхиг һарһҗ',
'confirm'               => 'Батлх',
'excontent'             => 'дотрнь: «$1»',
'excontentauthor'       => 'дотрнь: «$1» (һанц бичәч [[Special:Contributions/$2|$2]] бәәҗ)',
'exbeforeblank'         => 'цеврүллһнә өмн дотр: «$1»',
'exblank'               => 'хоосн халх бәәҗ',
'delete-confirm'        => '$1 һарһх',
'delete-legend'         => 'Һарһлһн',
'confirmdeletetext'     => 'Та цуг халхиг аль зургиг һарһҗ орхар бәәнәт.
Буйн болтха, та үнәр тана үүлдин ашуд болн [[{{MediaWiki:Policy-url}}]] әңгин зокал медәд, эн батлҗ кетн.',
'actioncomplete'        => 'Үүлд кев',
'deletedtext'           => '«<nowiki>$1</nowiki>» һарһҗ болв.
$2 шидрә һарһлһна төлә хәләтн.',
'deletedarticle'        => '«[[$1]]» халхиг һарһв',
'dellogpage'            => 'Һарһллһна сеткүл',
'deletecomment'         => 'Һарһллһна учр:',
'deleteotherreason'     => 'Талдан аль дәкәд учр:',
'deletereasonotherlist' => 'Талдан учр',

# Rollback
'rollback_short' => 'Хәрүллһн',
'rollbacklink'   => 'хәрүлх',

# Protect
'protectlogpage'              => 'Харсллһна сеткүл',
'protectedarticle'            => '«[[$1]]» халхиг харсв',
'modifiedarticleprotection'   => '[[$1]] халхна харсллһна кемҗән хүврлх',
'protectcomment'              => 'Учр:',
'protectexpiry'               => 'Өңгрнә:',
'protect_expiry_invalid'      => 'Буру өңгрллһнә цаг',
'protect_expiry_old'          => 'Өңгрллһнә цаг бәәв.',
'protect-text'                => "Энд та '''<nowiki>$1</nowiki>''' халхин харсллһна кемҗән хәләҗ,  хүврлҗ чаднат.",
'protect-locked-access'       => "Эн халхна харсллһна кемҗән сольҗ, тана бичгдлһна зөв тату.
Ода болсн '''$1''' халхна көгүд:",
'protect-cascadeon'           => 'Эн халх харссн. Юнгад гихлә, тер халх {{PLURAL:$1|эн халхд|тенд халхсд}} каскад харсллһта. Тадн эн халхна харсллһна кемҗән сольх чаднат, болв тер үүл каскад харлсһиг цокҗ чадшго.',
'protect-default'             => 'Цуг демнчнрд зөвән өгҗ',
'protect-fallback'            => '$1 зөв кергтә',
'protect-level-autoconfirmed' => 'Шин болн нер уга демнчнрас харсх',
'protect-level-sysop'         => 'Дарһас һанцхн',
'protect-summary-cascade'     => 'каскад',
'protect-expiring'            => '$1 (UTC) гидг цагт өңгрнә',
'protect-cascade'             => 'Халхсиг эн халхд дотр харсх (каскад)',
'protect-cantedit'            => 'Та эн халхна харсллһна кемҗән сольҗ чадхшв. Юнгад гихлә, та зөвән авв уга',
'restriction-type'            => 'Зөв:',
'restriction-level'           => 'Зөвән кемҗән:',

# Restrictions (nouns)
'restriction-edit' => 'Сольлһн',
'restriction-move' => 'Көндлһн',

# Undelete
'undeletelink'     => 'гүүһәд хәләх/босхҗ тохрах',
'undeleteinvert'   => 'Зөрү суңһлт',
'undeletedarticle' => '«[[$1]]» хәрү кехв',

# Namespace form on various pages
'namespace'      => 'Нернә у:',
'invert'         => 'Зөрү суңһлт',
'blanknamespace' => '(Һол)',

# Contributions
'contributions'       => 'Демнчна өгүллһн',
'contributions-title' => '$1 демнчна тус',
'mycontris'           => 'Мини демнлһн',
'contribsub2'         => '$1 төлә ($2)',
'uctop'               => '(отхн)',
'month'               => 'Эн сарас (болн эртәр):',
'year'                => 'Эн җиләс (болн эртәр):',

'sp-contributions-newbies'  => 'Шин бичгдлһтә кесн демнлһн һанцхн үзүлх',
'sp-contributions-blocklog' => 'бүсллһнә сеткүл',
'sp-contributions-deleted'  => 'һарһсн демнчна сольлһн',
'sp-contributions-talk'     => 'меткән',
'sp-contributions-search'   => 'Демнлһиг хәәлһн',
'sp-contributions-username' => 'IP хайг аль нернь:',
'sp-contributions-submit'   => 'Хәәлһн',

# What links here
'whatlinkshere'            => 'Эн һазрур заалһуд',
'whatlinkshere-title'      => '«$1» гидг нерәдлһтә халхд заалдг халхс',
'whatlinkshere-page'       => 'Халх:',
'linkshere'                => "Тер халхс '''[[:$1]]''' халхд заалдг:",
'isredirect'               => 'авч оддг халх',
'istemplate'               => 'оруллһн',
'isimage'                  => 'зургин  заалһ',
'whatlinkshere-prev'       => '{{PLURAL:$1|урдк|урдк $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|дарук|дарук|дарук}} $1',
'whatlinkshere-links'      => '← заалһуд',
'whatlinkshere-hideredirs' => '$1 авч одлһн',
'whatlinkshere-hidetrans'  => '$1 оруллһн',
'whatlinkshere-hidelinks'  => 'заалһудиг $1',
'whatlinkshere-filters'    => 'Шүрс',

# Block/unblock
'blockip'                  => 'Демнчиг бүслх',
'ipaddress'                => 'IP хайг:',
'ipadressorusername'       => 'IP хайг аль демнчна нернь:',
'ipbreason'                => 'Учр:',
'ipbreasonotherlist'       => 'Талдан учр',
'ipboptions'               => '2 часуд:2 hours,1 өдр:1 day,3 өдрмүд:3 days,1 долан хонг:1 week,2 долан хонгуд:2 weeks,1 сар:1 month,3 сармуд:3 months,6 сармуд:6 months,1 җил:1 year,мөнк:infinite',
'ipblocklist'              => 'Бүслсн IP хайгуд болн демнчнр',
'blocklink'                => 'бүслх',
'unblocklink'              => 'бүслх биш',
'change-blocklink'         => 'бүслһиг сольх',
'contribslink'             => 'демнлһн',
'blocklogpage'             => 'Бүсллһнә сеткүл',
'blocklogentry'            => '[[$1]] бүслсн $2 күртл, $3 учрта',
'unblocklogentry'          => '$1-г бүслсн биш болулв',
'block-log-flags-nocreate' => 'бичгдлһиг бүтәҗ болшго',
'blockme'                  => 'Намаг бүслчк',

# Move page
'movepagetext'     => "Та дораһар цаасар, халхин сольлһна тууҗ көндәд, терүнә нериг сольх. 
Хуучн нерн шин нерд авч оддг болх. 
Та хуучн нерд эврәр авч одлһн шинрүлҗ чаднат. 
Эн кехлә уга, буйн болтха, [[Special:DoubleRedirects|давхр]] болн [[Special:BrokenRedirects|татасн]] авч одлһн шүүтн. 
Та заалһуд чик үлдг даавртә бәәнәт. 

Шинҗлтн: тер нертә халх бәәдг (авч оддг, хоосн, тууҗта уга йовдлас биш) бәәхлә, халх '''көндх уга'''. 
Тер учрар, эндүһәр көндлһн кехлә, та халхиг хәрү көндҗ чаднат, болв бәәдг халхиг зүлгхшт. 

'''УРДАСНЬ ЗӘҢГ!'''
Көндллһн «ачта» халхин ик-генткн хүврлһиг кеҗ чадна. Цаараньдн кехәр, тадна үүлдин ашуд медтн.",
'movepagetalktext' => "Терүнә ухалвр халх әврәр көндәх. '''Эс гиҗ:'''

*Тер нертә хоосн уга ухалвр халх бәәнә.
*Та дораһар ховдиг сунһв уга.

Тер учрар, седхлә, та эврә һарар көндәтн аль нер сольтн.",
'movearticle'      => 'Халхиг йовулх:',
'newtitle'         => 'Шин нернь:',
'move-watch'       => 'Эн халхиг шинҗлх',
'movepagebtn'      => 'Халхиг йовулх',
'pagemovedsub'     => 'Йовудта йовуллһн',
'movepage-moved'   => "'''«$1» халх шин нернь («$2»)  өгв'''",
'articleexists'    => 'Тер нерәдлһтә халх бәәнә, аль та буру нернь суңһвт.
Буйн болтха, талдан нернь өгтн.',
'talkexists'       => "'''Халхин йовудта көндллһн. Болв, ухалвр халх көндәх болшго. Юнгад гихлә, эн нерәдлһтә халх бәәнә. Буйн болтха, териг һарар неҗәлтн.'''",
'movedto'          => 'көндсн:',
'movetalk'         => 'Өөр ухалвр халхиг көндәх.',
'1movedto2'        => '«[[$1]]» халхас «[[$2]]» халхд көндв',
'1movedto2_redir'  => '«[[$1]]» халхас «[[$2]]» халхд көндв (авч одлһта уга).',
'movelogpage'      => 'Нернә сольлһна сеткүл',
'movereason'       => 'Учр:',
'revertmove'       => 'хәрүлһн',

# Export
'export'        => 'Халхин һазадлт',
'export-addcat' => 'Немх',
'export-addns'  => 'Немх',

# Namespace 8 related
'allmessages-filter-all' => 'Цуг',
'allmessages-language'   => 'Келн:',

# Thumbnails
'thumbnail-more' => 'Икдүлх',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Тана демнчна халх',
'tooltip-pt-mytalk'               => 'Тадна күндллһнә халх',
'tooltip-pt-preferences'          => 'Тана көгүд',
'tooltip-pt-watchlist'            => 'Халхс та шинҗлдг бәәнәт',
'tooltip-pt-mycontris'            => 'Тана демнлһнә сеткүл',
'tooltip-pt-login'                => 'Та орсн күцх бәәнәт, болв кергтә биш.',
'tooltip-pt-logout'               => 'Һарх',
'tooltip-ca-talk'                 => 'Халхин дотрин туск меткән',
'tooltip-ca-edit'                 => 'Та эн халхиг чиклҗ чаднат.
Буйн болтха, хадһлһна күртл хәләвр олзлтн.',
'tooltip-ca-addsection'           => 'Шин хүв эклх',
'tooltip-ca-viewsource'           => 'Эн халх харссн бәәнә.
Та энүнә медсн үзҗ чаднат.',
'tooltip-ca-history'              => 'Эн халхна шидрә чикллһн',
'tooltip-ca-protect'              => 'Эн халхиг харсх',
'tooltip-ca-delete'               => 'Эн халхиг һарһх',
'tooltip-ca-move'                 => 'Эн халхиг көндәх',
'tooltip-ca-watch'                => 'Эн халхиг тана шинҗллһнә сеткүлд немх',
'tooltip-ca-unwatch'              => 'Эн халхиг мини шинҗллһнә сеткүләс һарһх',
'tooltip-search'                  => '{{SITENAME}} төлә хәәх',
'tooltip-search-go'               => 'Эн чик нертә халхд, эн бәәхлә, орх',
'tooltip-search-fulltext'         => 'Эн бичәтә халхс хәәх',
'tooltip-p-logo'                  => 'Нүр халхд орх',
'tooltip-n-mainpage'              => 'Һол халхд орх',
'tooltip-n-mainpage-description'  => 'Нүр халхд орх',
'tooltip-n-portal'                => 'Проектин туск; та ю кеҗ чаднат; орм медлһн',
'tooltip-n-currentevents'         => 'Ода болсн зәңгсин бурткл',
'tooltip-n-recentchanges'         => 'Шидрә сольлһна бүрткл',
'tooltip-n-randompage'            => 'Болв чигн халхиг үзүлх',
'tooltip-n-help'                  => 'Дөң өггдг һазр',
'tooltip-t-whatlinkshere'         => 'Цуг вики халхс эн халхд заадг',
'tooltip-t-recentchangeslinked'   => 'Шидрә сольлһн халхсд эн халх заадг',
'tooltip-feed-rss'                => 'Эн халхна RSS зәңһллһн',
'tooltip-feed-atom'               => 'Эн халхна Atom зәңгллһн',
'tooltip-t-contributions'         => 'Эн демнчна өгүллһнә бүрткл үзүлх',
'tooltip-t-emailuser'             => 'Эн демнчд E-mail бичг йовулх',
'tooltip-t-upload'                => 'Зургиг, әгиг, болв нань чигн тәвх',
'tooltip-t-specialpages'          => 'Цуг көдлхнә халхс',
'tooltip-t-print'                 => 'Эн халхна барин бәәдл',
'tooltip-t-permalink'             => 'Эн халхна янзд даңгин заалһ',
'tooltip-ca-nstab-main'           => 'Халхнь',
'tooltip-ca-nstab-user'           => 'Демнчна халхиг үзүлх',
'tooltip-ca-nstab-media'          => 'Боомгин халх үзх',
'tooltip-ca-nstab-special'        => 'Эн көдлхнә халх. Та эниг чиклҗ чадхшв.',
'tooltip-ca-nstab-project'        => 'Төслин халх',
'tooltip-ca-nstab-image'          => 'Боомгин халхиг',
'tooltip-ca-nstab-mediawiki'      => 'MediaWiki зәңгин халх',
'tooltip-ca-nstab-template'       => 'Зуран халх',
'tooltip-ca-nstab-help'           => 'Цәәлһлһиг үзх',
'tooltip-ca-nstab-category'       => 'Әәшлин халхиг үзүлх',
'tooltip-minoredit'               => 'Эн хүврлһиг баһ чинртә темдглх',
'tooltip-save'                    => 'Тана сольлһиг хадһлтн',
'tooltip-preview'                 => 'Урдаснь хәләвр. Буйн болтха, энгиг олзлад, тегәд хадһлтн!',
'tooltip-diff'                    => 'Эн бичгәс хүврлһиг үзүлх',
'tooltip-compareselectedversions' => 'Тер халхин хойр янзин йилһән үзулх',
'tooltip-watch'                   => 'Эн халхиг тана шинҗллһнә сеткүлд немх',
'tooltip-rollback'                => 'Шидрә демнчна сольлһн нег дарцар уга кех',
'tooltip-undo'                    => 'Эн хүврлһиг уга келһн, хәләвртә болн  учрта.',

# Browsing diffs
'previousdiff' => '← Урдк сольлһн',
'nextdiff'     => 'Дарук сольлһн →',

# Media information
'file-info'            => '(боомгин кемҗә: $1, MIME төрл: $2)',
'file-info-size'       => '($1 × $2 цегтә, боомгин кемҗән: $3, MIME янз: $4)',
'file-nohires'         => '<small>Икәр чинртә янз уга.</small>',
'svg-long-desc'        => '(SVG боомг, $1 × $2 мет цегтә, боомгин кемҗән: $3)',
'show-big-image'       => 'Күцц чинр',
'show-big-image-thumb' => '<small>Урдаснь хәләврин кемҗән: $1 × $2 цегтә</small>',
'file-info-gif-looped' => 'билцгсн',

# Bad image list
'bad_image_list' => 'Эн темдглһн кергтә:

Бүртклин мөчүд һанцхн оньгтан авх (мөрәд * эклцта).
Түрүн мөрәнә заалһ - тәвх хөрсн зургин заалһ.
Дарук заалһуд эн мөрәд хаҗилһн болх (халхс зургиг орулҗ болх).',

# Metadata
'metadata'          => 'Мета өггцн',
'metadata-help'     => 'Эн боомг дәкәд өггцтә. Тер өггцн то камерар аль сканерар немсмн. Боомг бүтәлһнә хөөн чиклсн бәәхлә, зәрм кемҗәд одахн зургд әдл биш болх.',
'metadata-expand'   => 'Ик тодрхасиг үзүлх',
'metadata-collapse' => 'Ик тодрхасиг бултулх',
'metadata-fields'   => 'Эн җигсәмҗд нерлгдсн мета өггцин аһу, дүрслгч халхд герәсләр үзүлгдх, наадкснь бултулгдх. 
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'               => 'Өргн',
'exif-imagelength'              => 'Өндр',
'exif-bitspersample'            => 'Өңгин гүн',
'exif-datetime'                 => 'Боомгин сольлһна цаг',
'exif-imagedescription'         => 'Зургин нерн',
'exif-make'                     => 'Камерин зокъялч',
'exif-model'                    => 'Камерин һарц',
'exif-software'                 => 'Заклһна теткүл',
'exif-artist'                   => 'Зокъялч',
'exif-copyright'                => 'Зокъялчин зөвәнә эзн',
'exif-exifversion'              => "Exif'ин һарц",
'exif-pixelydimension'          => 'Күцц зургин өндр',
'exif-pixelxdimension'          => 'Күцц зургин өргн',
'exif-exposuretime'             => 'Дәврдгсн цаг',
'exif-exposuretime-format'      => '$1 с ($2)',
'exif-contrast'                 => 'Зөрү',
'exif-saturation'               => 'Дүүрслт',
'exif-sharpness'                => 'Шүвр',
'exif-devicesettingdescription' => 'Камерин көгүдин бичлһн',
'exif-subjectdistancerange'     => 'Цоксн зургин юмна турш',
'exif-imageuniqueid'            => 'Зургин тойг (ID)',
'exif-gpslatitude'              => 'Өрглт',
'exif-gpslongitude'             => 'Утлт',
'exif-gpsaltitude'              => 'Теңгсәс өндр',

'exif-orientation-1' => 'Кирин',
'exif-orientation-2' => 'Теңгрин хормаһар туссн',

# External editor support
'edit-externally'      => 'Эн боомгиг һаза заклһар чиклх',
'edit-externally-help' => '([http://www.mediawiki.org/wiki/Manual:External_editors Тәвллһнә заалт]  икәр өггцнә төлә хәләтн)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'цуг',
'imagelistall'     => 'цуг',
'watchlistall2'    => 'цуг',
'namespacesall'    => 'цуг',
'monthsall'        => 'цуг',
'limitall'         => 'цуг',

# action=purge
'confirm_purge_button' => 'Тиим',

# Multipage image navigation
'imgmultipageprev' => '← урдк халх',
'imgmultipagenext' => 'дарук халх →',
'imgmultigo'       => 'Орх!',
'imgmultigoto'     => '$1 халхд орх',

# Table pager
'table_pager_next'         => 'Дарук халх',
'table_pager_prev'         => 'Урдк халх',
'table_pager_first'        => 'Түрүн халх',
'table_pager_last'         => 'Кенз халх',
'table_pager_limit_submit' => 'Кех',
'table_pager_empty'        => 'Ашнь уга',

# Auto-summaries
'autosumm-blank' => 'Халх цеврүлв',
'autosumm-new'   => 'Шин халх: «$1»',

# Live preview
'livepreview-loading' => 'Белднә...',
'livepreview-ready'   => 'Белднә... Болһсн!',

# Watchlist editor
'watchlistedit-numitems'     => 'Тана шинҗллһнә сеткүл {{PLURAL:$1|1 гешүтә|$1 гешүдтә}}, меткән халхста.',
'watchlistedit-noitems'      => 'Тана шинҗллһнә сеткүл хоосн бәәнә.',
'watchlistedit-normal-title' => 'Шинҗллһнә сеткүлиг чиклх',

# Watchlist editing tools
'watchlisttools-view' => 'Бүртклин халхна сольлһн',
'watchlisttools-edit' => 'Сеткүлиг хәләх аль чиклх',
'watchlisttools-raw'  => 'Бичг мет чиклх',

# Special:Version
'version-software-product' => 'Һарц',
'version-software-version' => 'Һарц',

# Special:FilePath
'filepath'        => 'Боомгд хаалһ',
'filepath-page'   => 'Боомг:',
'filepath-submit' => 'Орх',

# Special:FileDuplicateSearch
'fileduplicatesearch-filename' => 'Боомгин нерн:',
'fileduplicatesearch-submit'   => 'Хәәх',

# Special:SpecialPages
'specialpages'                   => 'Көдлхнә халхс',
'specialpages-group-maintenance' => 'Өргмҗин тооцаң',
'specialpages-group-other'       => 'Талдан көлдхнә халхс',
'specialpages-group-login'       => 'Харһх / бүртклх',
'specialpages-group-changes'     => 'Шидрә сольлһн болн сеткүлс',
'specialpages-group-media'       => 'Боомгин тооцан болн тәвлһн',
'specialpages-group-users'       => 'Демнчнр болн эрктн',
'specialpages-group-highuse'     => 'Күчтә олзлсн халхс',
'specialpages-group-pages'       => 'Халхин буулһавруд',
'specialpages-group-pagetools'   => 'Халхин зер-зев',
'specialpages-group-wiki'        => 'Бики өггцн болн зер-зев',
'specialpages-group-redirects'   => 'Авч оддг көдлхнә халхс',
'specialpages-group-spam'        => 'Спамас зевсг',

# Special:BlankPage
'blankpage'              => 'Хоосн халх',
'intentionallyblankpage' => 'Тер  халх хоосн күслтә бәәнә.',

# HTML forms
'htmlform-reset'               => 'Сольлһиг уга кех',
'htmlform-selectorother-other' => 'Талдан',

# Add categories per AJAX
'ajax-add-category'        => 'Әәшлиг немх',
'ajax-add-category-submit' => 'Немх',
'ajax-confirm-save'        => 'Хадһлх',
'ajax-error-title'         => 'Эндү',
'ajax-error-dismiss'       => 'Тиим',

);

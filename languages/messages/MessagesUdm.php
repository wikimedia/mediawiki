<?php
/** Udmurt (Удмурт)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Andrewboltachev
 * @author Kaganer
 * @author Udmwiki
 * @author ОйЛ
 * @author לערי ריינהארט
 */

$fallback = 'ru';

$namespaceNames = array(
	NS_MEDIA            => 'Медиа',
	NS_SPECIAL          => 'Панель',
	NS_TALK             => 'Вераськон',
	NS_USER             => 'Викиавтор',
	NS_USER_TALK        => 'Викиавтор_сярысь_вераськон',
	NS_PROJECT_TALK     => '$1_сярысь_вераськон',
	NS_FILE             => 'Суред',
	NS_FILE_TALK        => 'Суред_сярысь_вераськон',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_сярысь_вераськон',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Шаблон_сярысь_вераськон',
	NS_HELP             => 'Валэктон',
	NS_HELP_TALK        => 'Валэктон_сярысь_вераськон',
	NS_CATEGORY         => 'Категория',
	NS_CATEGORY_TALK    => 'Категория_сярысь_вераськон',
);

$linkTrail = '/^([a-zа-яёӝӟӥӧӵ“»]+)(.*)$/sDu';
$fallback8bitEncoding = 'windows-1251';
$separatorTransformTable = array( ',' => ' ', '.' => ',' );

$messages = array(
# User preference toggles
'tog-underline' => 'Линкъёс ултӥз гожен сызоно',

'underline-always'  => 'Котьку',
'underline-never'   => 'Ноку',
'underline-default' => 'Браузерысь настройкаосты уже кутоно',

# Font style option in Special:Preferences
'editfont-style'     => 'Тупатон бусыысь шрифтлэн стилез',
'editfont-default'   => 'Браузерлэн настройкаосысьтыз шрифтэз уже кутоно',
'editfont-monospace' => 'Огпасьтала пусъёсын шрифт',
'editfont-sansserif' => 'Засечкатэк шрифт',
'editfont-serif'     => 'Засечкаен шрифт',

# Dates
'sunday'        => 'арнянунал',
'monday'        => 'вордӥськон',
'tuesday'       => 'пуксён',
'wednesday'     => 'вирнунал',
'thursday'      => 'покчиарня',
'friday'        => 'удмуртарня',
'saturday'      => 'кӧснунал',
'sun'           => 'Арн',
'mon'           => 'Врд',
'tue'           => 'Пкс',
'wed'           => 'Врн',
'thu'           => 'Пкч',
'fri'           => 'Удм',
'sat'           => 'Ксн',
'january'       => 'толшор',
'february'      => 'тулыспал',
'march'         => 'южтолэзь',
'april'         => 'оштолэзь',
'may_long'      => 'куартолэзь',
'june'          => 'инвожо',
'july'          => 'пӧсьтолэзь',
'august'        => 'гудырикошкон',
'september'     => 'куарусён',
'october'       => 'коньывуон',
'november'      => 'шуркынмон',
'december'      => 'толсур',
'january-gen'   => 'толшоре',
'february-gen'  => 'тулыспалэ',
'march-gen'     => 'южтолэзе',
'april-gen'     => 'оштолэзе',
'may-gen'       => 'куартолэзе',
'june-gen'      => 'инвожое',
'july-gen'      => 'пӧсьтолэзе',
'august-gen'    => 'гудырикошконэ',
'september-gen' => 'куарусёнэ',
'october-gen'   => 'коньывуонэ',
'november-gen'  => 'шуркынмонэ',
'december-gen'  => 'толсурэ',
'jan'           => 'тшт',
'feb'           => 'тпт',
'mar'           => 'южт',
'apr'           => 'ошт',
'may'           => 'южт',
'jun'           => 'ивт',
'jul'           => 'пст',
'aug'           => 'гкт',
'sep'           => 'кст',
'oct'           => 'квт',
'nov'           => 'шкт',
'dec'           => 'тст',

# Categories related messages
'pagecategories'                 => '$1 категория',
'category_header'                => '«$1» категориысь бамъёс',
'subcategories'                  => 'Подкатегориос',
'category-media-header'          => '«$1» категориысь файлъёс',
'category-empty'                 => "''Та категориын али бамъёс но, файлъёс но ӧвӧл.''",
'hidden-categories'              => '{{PLURAL:$1|Ватэм категория|Ватэм категориос}}',
'hidden-category-category'       => 'Ватэм категориос',
'category-subcat-count'          => '{{PLURAL:$2|Со категориын одӥг подкатегория гинэ.|Возьматэмын $1 подкатегория $2 пӧлысь.}}',
'category-subcat-count-limited'  => 'Со категориын $1 подкатегория.',
'category-article-count'         => '{{PLURAL:$2|Со категориын одӥг бам гинэ.|Возьматэмын $1 бам $2 пӧлысь.}}',
'category-article-count-limited' => 'Со категориын $1 бам.',
'category-file-count'            => '{{PLURAL:$2|Со категориын одӥг файл гинэ.|Возьматэмын $1 файл $2 пӧлысь.}}',
'category-file-count-limited'    => 'Со категориын $1 файл.',
'listingcontinuesabbrev'         => 'азьлань',
'index-category'                 => 'Индексировать кароно бамъёс',
'noindex-category'               => 'Индексировать каронтэм бамъёс',

'linkprefix'   => '/^(.*?)(„|«)$/sDu',
'mainpagetext' => "'''MediaWiki движок азинлыко пуктэмын.'''",

'about'      => 'Та сярысь',
'article'    => 'Статья',
'mypage'     => 'Ас бам',
'mytalk'     => 'викиавтор сярысь вераськон',
'anontalk'   => 'Со IP-адрес сярысь вераськон',
'navigation' => 'Навигация',

# Cologne Blue skin
'qbpageoptions'  => 'Бамлэн настройкаосыз',
'qbspecialpages' => 'Ваньмыз панельёс',
'faq'            => 'Юан-веран',
'faqpage'        => 'Project:Юан-веран',

# Vector skin
'vector-action-addsection' => 'Выль темаез ватсано',
'vector-action-delete'     => 'Быдтоно',
'vector-action-move'       => 'Мукет интые выжтыны',
'vector-action-protect'    => 'Утьыны',
'vector-view-create'       => 'Кылдытоно',
'vector-view-edit'         => 'Тупатоно',
'vector-view-history'      => 'История',
'vector-view-view'         => 'Лыдӟоно',
'vector-view-viewsource'   => 'Кодзэ учкыны',

'errorpagetitle'   => 'Янгыш',
'tagline'          => '{{SITENAME}}-ысь материал',
'help'             => 'Валэктонъёс',
'search'           => 'Утчан',
'searchbutton'     => 'Утчано',
'searcharticle'    => 'Мыноно',
'history'          => 'Бамлэн историез',
'history_short'    => 'история',
'printableversion' => 'Печатламон версия',
'permalink'        => 'Ӵапак та версиезлы линк',
'print'            => 'Печатлано',
'edit'             => 'тупатыны',
'delete'           => 'Быдтыны',
'protect'          => 'Утьыны',
'talkpagelinktext' => 'Вераськон',
'talk'             => 'Вераськон',
'toolbox'          => 'Инструментъёс',
'jumptonavigation' => 'навигация',
'jumptosearch'     => 'утчан',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'currentevents'        => 'Выль иворъёс',
'currentevents-url'    => 'Project:Выль иворъёс',
'helppage'             => 'Help:Валэктон',
'mainpage'             => 'Кутскон бам',
'mainpage-description' => 'Кутскон бам',
'portal'               => 'Сообщество',
'portal-url'           => 'Project:Портал сообщества',

'retrievedfrom'   => '«$1»-лэсь басьтэмын',
'editsection'     => 'тупатыны',
'editsectionhint' => '$1 секциез тупатоно',
'site-rss-feed'   => '$1 — RSS-лента',
'site-atom-feed'  => '$1 — Atom-лента',
'red-link-title'  => '$1 (со бам ӧвӧл на)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-user'      => 'Викиавтор',
'nstab-mediawiki' => 'Ивортон',

# General errors
'viewsource' => 'Кодзэ учкыны',

# Login and logout pages
'login'                   => 'Википедие пырон',
'nav-login-createaccount' => 'Нимдэс вераны / Регистрациез ортчытыны',
'userlogin'               => 'Регистрациез ортчытыны яке Википедие пырыны',
'logout'                  => 'Кошкыны',
'userlogout'              => 'Кошкыны',
'createaccount'           => 'выль вики-авторлэн регистрациез',

# Edit pages
'summary'       => 'Мар но малы тупатэмын? (вакчияк):',
'minoredit'     => 'Ичи воштон',
'noarticletext' => "В настоящий момент текст на данной странице отсутствует.
Вы можете [[Special:Search/{{PAGENAME}}|найти упоминание данного названия]] на других страницах,
<span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} найти соответствующие записи журналов],
или '''[{{fullurl:{{FULLPAGENAME}}|action=edit}} создать страницу с таким названием]'''</span>.",

# Revision deletion
'revdelete-radio-set'   => 'Бен',
'revdelete-radio-unset' => 'Ӧвӧл',

# Search results
'searchresults'      => 'Шедьтэмын',
'searchresulttext'   => 'Проектлэн бамъёстӥз утчан сярысь тыро-быдогес тодэмды потӥз ке, учке [[{{MediaWiki:Helppage}}|«Юан-веран» люкетэз]].',
'searchhelp-url'     => 'Help:Валэктон',
'search-result-size' => '$1 кыл',

# Preferences page
'preferences'     => 'настройкаос',
'mypreferences'   => 'Настройкаос',
'prefs-watchlist' => 'Чаклан список',

# Recent changes
'recentchanges' => 'Выль тупатонъёс',
'hist'          => 'история',

# Recent changes linked
'recentchangeslinked'         => 'Герӟаськем тупатонъёс',
'recentchangeslinked-feed'    => 'Герӟаськем тупатонъёс',
'recentchangeslinked-toolbox' => 'Герӟаськем тупатонъёс',

# Upload
'upload' => 'Файл поныны',

# File description page
'sharedupload' => 'Та файл — $1-ысь, сое мукет проектъёсын но уже кутыны луэ.',

# Random page
'randompage' => 'Олокыӵе статья',

# Miscellaneous special pages
'nbytes' => '$1 байт',
'move'   => 'Мукет интые выжтыны',

# E-mail user
'emailmessage' => 'Ивортон:',

# Watchlist
'watchlist'   => 'Чаклано статьяос',
'mywatchlist' => 'Чаклан список',
'watch'       => 'Чаклано',
'unwatch'     => 'Чакламысь дугдыны',

# Contributions
'mycontris' => 'Мынам гожтэмъёсы',

# What links here
'whatlinkshere' => 'Татчы линкъёс',

# Move page
'movearticle'     => 'Статьяез мукет интые выжтыны',
'move-watch'      => 'Та бамез чаклан списоке пыртыны',
'delete_and_move' => 'Быдтыны но мукет интые выжтыны',

# Namespace 8 related
'allmessagesname' => 'Ивортон',

# Tooltip help for the actions
'tooltip-ca-talk'                => 'Бамлэн контентэз сярысь вераськон',
'tooltip-search'                 => 'Утчано {{SITENAME}}',
'tooltip-n-mainpage'             => 'Кутскон баме мыноно',
'tooltip-n-mainpage-description' => 'Кутскон баме мыноно',
'tooltip-n-portal'               => 'Проект сярысь, мар карыны быгатоды, ужлы кулэ луэмзэ кытысь шедьтоно',
'tooltip-n-recentchanges'        => 'Берпуметӥ тупатонъёслэн списоксы',
'tooltip-n-randompage'           => 'Олокыӵе бамез учконо',
'tooltip-t-whatlinkshere'        => 'Ваньмыз бамъёс, кудъёсаз та бамлы линксы вань',
'tooltip-t-specialpages'         => 'Специальной бамъёслэн списоксы',

# Special:SpecialPages
'specialpages' => 'Ваньмыз панельёс',

);

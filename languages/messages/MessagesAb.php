<?php
/** Abkhazian (Аҧсуа)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Comp1089
 * @author Mzhiba
 * @author Temuri rajavi
 * @author ОйЛ
 */

$fallback = 'ru';

$namespaceNames = array(
	NS_MEDIA            => 'Амедиа',
	NS_TALK             => 'Ахцәажәара',
	NS_USER             => 'Иалахә',
	NS_PROJECT_TALK     => '$1_ахцәажәара',
	NS_FILE             => 'Афаил',
	NS_MEDIAWIKI        => 'Амедиавики',
	NS_TEMPLATE         => 'Ашаблон',
	NS_HELP             => 'Ацхыраара',
	NS_CATEGORY         => 'Акатегориа',
);

$namespaceAliases = array(
	// Backward compat. Fallbacks from 'ru'.
	'Медиа'                => NS_MEDIA,
	'Служебная'            => NS_SPECIAL,
	'Обсуждение'           => NS_TALK,
	'Участник'             => NS_USER,
	'Обсуждение_участника' => NS_USER_TALK,
	'Обсуждение_$1'        => NS_PROJECT_TALK,
	'Файл'                 => NS_FILE,
	'Обсуждение_файла'     => NS_FILE_TALK,
	'MediaWiki'            => NS_MEDIAWIKI,
	'Обсуждение_MediaWiki' => NS_MEDIAWIKI_TALK,
	'Шаблон'               => NS_TEMPLATE,
	'Обсуждение_шаблона'   => NS_TEMPLATE_TALK,
	'Справка'              => NS_HELP,
	'Обсуждение_справки'   => NS_HELP_TALK,
	'Категория'            => NS_CATEGORY,
	'Обсуждение_категории' => NS_CATEGORY_TALK
);

$specialPageAliases = array(
	'Recentchanges'             => array( 'АрҽеираҾыцқәа' ),
	'Upload'                    => array( 'Аҭагалара' ),
	'Newimages'                 => array( 'АфаилқәаҾыц' ),
	'Randompage'                => array( 'Машәырлатәи' ),
	'Newpages'                  => array( 'АдаҟьақәаҾыц' ),
	'Specialpages'              => array( 'ЦастәиАдаҟьақәа' ),
	'Categories'                => array( 'Акатегориақәа' ),
	'Mypage'                    => array( 'Садаҟьа' ),
	'Mytalk'                    => array( 'Сахцәажәара' ),
	'Mycontributions'           => array( 'Архиарақәа' ),
	'Search'                    => array( 'Аҧшаара' ),
);

$magicWords = array(
	'language'              => array( '0', '#АБЫЗШӘА:', '#ЯЗЫК:', '#LANGUAGE:' ),
	'special'               => array( '0', 'цастәи', 'служебная', 'special' ),
	'index'                 => array( '1', '__АИНДЕКС__', '__ИНДЕКС__', '__INDEX__' ),
);

$messages = array(
# Dates
'sunday'        => 'амҽыш',
'monday'        => 'ашәахь',
'tuesday'       => 'аҩаш',
'wednesday'     => 'ахаш',
'thursday'      => 'аҧшьаш',
'friday'        => 'ахәуаш',
'saturday'      => 'асабш',
'january'       => 'ажьырныҳәа',
'february'      => 'жәабран',
'march'         => 'хәажәкыр',
'april'         => 'мшаҧы',
'may_long'      => 'лаҵара',
'june'          => 'рашәара',
'july'          => 'ҧхынгәы',
'august'        => 'нанҳәа',
'september'     => 'цәыббра',
'october'       => 'жьҭаара',
'november'      => 'абҵара',
'december'      => 'ҧхынҷкәын',
'january-gen'   => 'ажьырныҳәа',
'february-gen'  => 'жәабран',
'march-gen'     => 'хәажәкыра',
'april-gen'     => 'мшаҧы',
'may-gen'       => 'лаҵара',
'june-gen'      => 'рашәара',
'july-gen'      => 'ҧхынгәы',
'august-gen'    => 'нанҳәа',
'september-gen' => 'цәыббра',
'october-gen'   => 'жьҭаара',
'november-gen'  => 'абҵара',
'december-gen'  => 'ҧхынҷкәын',
'jan'           => 'ажь',
'feb'           => 'жәа',
'mar'           => 'хәа',
'apr'           => 'мша',
'may'           => 'лаҵ',
'jun'           => 'раш',
'jul'           => 'ҧхг',
'aug'           => 'нан',
'sep'           => 'цәы',
'oct'           => 'жьҭ',
'nov'           => 'абҵ',
'dec'           => 'ҧхҷ',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Акатегориа|Акатегориақәа}}',

'article'    => 'Адаҟьа',
'mytalk'     => 'Сахцәажәара',
'navigation' => 'Анавигациа',

# Cologne Blue skin
'qbfind'         => 'Аҧшаара',
'qbedit'         => 'Ариашара',
'qbpageoptions'  => 'Ари адаҟьа',
'qbspecialpages' => 'Цастәи адаҟьақәа',

# Vector skin
'vector-action-move'        => 'Ахьӡ аҧсахра',
'vector-namespace-category' => 'Акатегориа',
'vector-namespace-help'     => 'Ацхыраара адаҟьа',
'vector-namespace-image'    => 'Афаил',
'vector-namespace-main'     => 'Адаҟьа',
'vector-namespace-project'  => 'Апроект',
'vector-namespace-special'  => 'Цастәи адаҟьа',
'vector-namespace-talk'     => 'Ахцәажәара',
'vector-namespace-template' => 'Ашаблон',
'vector-namespace-user'     => 'Алахәыла Адаҟьа',
'vector-view-create'        => 'Арҿиара',
'vector-view-edit'          => 'Ариашамҭа',
'vector-view-history'       => 'Аҭоурых',
'vector-view-view'          => 'Аҧхьара',
'vector-view-viewsource'    => 'Ахәаҧшра',

'help'             => 'Ацхыраара',
'search'           => 'Аҧшаара',
'searchbutton'     => 'Аҧшаара',
'searcharticle'    => 'Адаҟьа',
'history'          => 'Аҭоурых',
'history_short'    => 'Аҭоурых',
'printableversion' => 'Акьыҧхьразы аверсиа',
'permalink'        => 'Еисымшатәи ахьарҧш',
'edit'             => 'Ариашамҭа',
'create'           => 'Арҿиара',
'create-this-page' => 'Ариашара ари адаҟьа',
'newpage'          => 'Адаҟьа Ҿыц',
'talkpagelinktext' => 'Ахцәажәара',
'specialpage'      => 'Цастәи адаҟьа',
'talk'             => 'Ахцәажәара',
'toolbox'          => 'Амаҵыругақәа',
'otherlanguages'   => 'Абызшәақәа',
'lastmodifiedat'   => 'Ацыхәтәантәи аҧсахрақәа абри адаҟьа аҟны: $2, $1.',
'jumptonavigation' => 'Анавигациа',
'jumptosearch'     => 'Аҧшаара',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} иазкны',
'currentevents'        => 'Ииасуа ахҭысқәа',
'mainpage'             => 'Ихадоу Адаҟьа',
'mainpage-description' => 'Ихадоу адаҟьа',
'privacy'              => 'Аконфиденциалтә аполитика',
'privacypage'          => 'Project: Аконфиденциалтә аполитика',

'youhavenewmessages'  => 'Уара $1 уауит ($2).',
'newmessageslink'     => 'адырра ҿыцқәа',
'newmessagesdifflink' => 'аҵыхәтәатәи аҽеиҭакра',
'editsection'         => 'ариашамҭа',
'editold'             => 'ариашара',
'editlink'            => 'ариашара',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'Адаҟьа',
'nstab-user'     => 'Алахәыла',
'nstab-special'  => 'Цастәи адаҟьа',
'nstab-image'    => 'Афаил',
'nstab-template' => 'Ашаблон',
'nstab-help'     => 'Ацхыраара',
'nstab-category' => 'Акатегориа',

# General errors
'viewsource' => 'Ахәаҧшра',

# Login and logout pages
'login'                   => 'Урдырра',
'nav-login-createaccount' => 'Урдырра / Арегистрациара',
'userlogin'               => 'Урдырра/Арегистрациара',
'logout'                  => 'Асеанс дәылҵра',
'userlogout'              => 'Асеанс дәылҵра',
'loginlanguagelabel'      => 'Абызшәа: $1',

# Edit pages
'summary'     => 'Описание изменений:',
'savearticle' => 'Адаҟьа ахьчара',
'editing'     => 'Ариашамҭа: $1',

# Search results
'searchresults-title' => 'Аҧшаара «$1»',

# Preferences page
'preferences'       => 'Архиарақәа',
'mypreferences'     => 'Архиарақәа',
'searchresultshead' => 'Аҧшаара',
'uid'               => 'Алахәыла ID:',
'yourlanguage'      => 'Абызшәа:',

# Groups
'group-user' => 'Алахәылацәа',

'group-user-member' => 'алахәыла',

'grouppage-sysop' => '{{ns:project}}:Администраторцәа',

# Recent changes
'recentchanges'                => 'Арҽеира ҿыцқәа',
'recentchanges-legend-newpage' => '$1 — адаҟьа ҿыц',
'newpageletter'                => 'Ҿ',

# Recent changes linked
'recentchangeslinked'         => 'Еимадоу ариашарақәа',
'recentchangeslinked-feed'    => 'Еимадоу ариашарақәа',
'recentchangeslinked-toolbox' => 'Еимадоу ариашарақәа',

# Upload
'upload' => 'Афаил аҭагалара',

# File description page
'filehist-user' => 'Алахәыла',

# Random page
'randompage' => 'Машәырлатәи аҩымҭа',

'brokenredirects-edit' => 'ариашамҭа',

# Miscellaneous special pages
'newpages-username' => 'Алахәыла:',
'move'              => 'Ахьӡ аҧсахра',
'movethispage'      => 'Ахьӡ аҧсахра ари адаҟьа',

# Special:Log
'specialloguserlabel' => 'Алахәыла:',

# Special:AllPages
'alphaindexline' => '$1 ... $2',

# Special:LinkSearch
'linksearch-ok' => 'Аҧшаара',

# Watchlist
'watchlist'   => 'Ашклаҧшра асна',
'mywatchlist' => 'Ашклаҧшра асна',

# Restrictions (nouns)
'restriction-edit' => 'Ариашамҭа',

# Contributions
'mycontris'   => 'Салагала',
'contribsub2' => 'Алагала $1 ($2)',

'sp-contributions-talk'   => 'ахцәажәара',
'sp-contributions-submit' => 'Аҧшаара',

# What links here
'whatlinkshere'      => 'Арахьтәи ахьарҧшқәа',
'whatlinkshere-page' => 'Адаҟьа:',

# Block/unblock
'contribslink' => 'алагала',

# Move page
'movearticle' => 'Ахьӡ аҧсахра:',

# Namespace 8 related
'allmessages-language' => 'Абызшәа:',

# Special:NewFiles
'ilsubmit' => 'Аҧшаара',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'зегьы',
'namespacesall' => 'зегьы',
'monthsall'     => 'зегьы',

# Special:FileDuplicateSearch
'fileduplicatesearch-submit' => 'Аҧшаара',

# Special:SpecialPages
'specialpages'             => 'Цастәи адаҟьақәа',
'specialpages-group-login' => 'Урдырра / Арегистрациара',

);

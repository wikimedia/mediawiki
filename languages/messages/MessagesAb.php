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

'article'        => 'Адаҟьа',
'qbfind'         => 'Аҧшаара',
'qbedit'         => 'Ариашара',
'qbpageoptions'  => 'Ари адаҟьа',
'qbspecialpages' => 'Цастәи адаҟьақәа',
'mytalk'         => 'Сахцәажәара',
'navigation'     => 'Анавигациа',

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
'lastmodifiedat'   => 'Ацыхәтәантәи аҧсахрақәа абри адаҟьа аҟны: $2, $1.', # $1 date, $2 time
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
'nstab-user'     => 'Иалахә',
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
'uid'                     => 'Иалоу ID:',
'yourlanguage'            => 'Абызшәа:',
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

# Groups
'group-user' => 'Иалоуқәа',

'group-user-member' => 'Иалахә',

'grouppage-sysop' => '{{ns:project}}:Администраторцәа',

# Recent changes
'recentchanges' => 'Арҽеира ҿыцқәа',
'newpageletter' => 'Ҿ',

# Recent changes linked
'recentchangeslinked' => 'Еимадоу ариашарақәа',

# Upload
'upload' => 'Афаил аҭагалара',

# File description page
'filehist-user' => 'Иалоу',

# Random page
'randompage' => 'Машәырлатәи аҩымҭа',

# Miscellaneous special pages
'newpages-username' => 'Иалахә:',

# Special:Log
'specialloguserlabel' => 'Иалахә:',

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

'sp-contributions-submit' => 'Аҧшаара',

# What links here
'whatlinkshere'      => 'Арахьтәи ахьарҧшқәа',
'whatlinkshere-page' => 'Адаҟьа:',

# Block/unblock
'contribslink' => 'алагала',

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

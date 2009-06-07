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

$namespaceNames = array(
	NS_MEDIA            => 'Аһар',
	NS_SPECIAL          => 'Көдлхнə',
	NS_MAIN             => '',
	NS_TALK             => 'Ухалвр',
	NS_USER             => 'Орлцач',
	NS_USER_TALK        => 'Орлцачна_тускар_ухалвр',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_тускар_ухалвр',
	NS_FILE             => 'Зург',
	NS_FILE_TALK        => 'Зургин_тускар_ухалвр',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_тускар_ухалвр',
	NS_TEMPLATE         => 'Зура',
	NS_TEMPLATE_TALK    => 'Зуран_тускар_ухалвр',
	NS_HELP             => 'Цəəлһлһн',
	NS_HELP_TALK        => 'Цəəлһлһин_тускар_ухалвр',
	NS_CATEGORY         => 'Янз',
	NS_CATEGORY_TALK    => 'Янзин_тускар_ухалвр',
);

$fallback8bitEncoding = "windows-1251";

$messages = array(
# Dates
'sunday'        => 'Нарн өдр',
'monday'        => 'Сарң өдр',
'tuesday'       => 'Мигмр өдр',
'wednesday'     => 'Үлмҗ өдр',
'thursday'      => 'Пүрвә өдр',
'friday'        => 'Басң өдр',
'saturday'      => 'Бембә өдр',
'january'       => 'Туула',
'february'      => 'Лу',
'march'         => 'Моһа',
'april'         => 'Мөрн',
'may_long'      => 'Хөн',
'june'          => 'Мөчн',
'july'          => 'Така',
'august'        => 'Ноха',
'september'     => 'Һаха',
'october'       => 'Хулһн',
'november'      => 'Үкр',
'december'      => 'Бар',
'january-gen'   => 'Туула',
'february-gen'  => 'Лу',
'march-gen'     => 'Моһа',
'april-gen'     => 'Мөрн',
'may-gen'       => 'Хөн',
'june-gen'      => 'Мөчн',
'july-gen'      => 'Така',
'august-gen'    => 'Ноха',
'september-gen' => 'Һаха',
'october-gen'   => 'Хулһн',
'november-gen'  => 'Үкр',
'december-gen'  => 'Бар',
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

'article'        => 'Халх',
'cancel'         => 'Уга кех',
'qbspecialpages' => 'Көдлхнә халхс',
'mytalk'         => 'Мини күүндлһн бəəрм',
'navigation'     => 'Орм медлһн',

'errorpagetitle'   => 'Эндү',
'help'             => 'Дөң',
'search'           => 'Хәәлһн',
'searchbutton'     => 'Хәәлһн',
'go'               => 'Орх',
'searcharticle'    => 'Орх',
'history'          => 'Чикллһнə бүрткл',
'history_short'    => 'Чикллһнə бүрткл',
'printableversion' => 'Барин бәәдл',
'permalink'        => 'Даңгин заалһ',
'edit'             => 'Чиклх',
'create'           => 'Бүтәх',
'editthispage'     => 'Эн халхиг чиклҗ',
'delete'           => 'Һарһх',
'newpage'          => 'Шин халх',
'talkpage'         => 'Ухалвр',
'talkpagelinktext' => 'Ухалвр',
'personaltools'    => 'Күүнә зер-зев',
'talk'             => 'Ухалвр',
'toolbox'          => 'Зер-зев',
'otherlanguages'   => 'Талдан келнд',
'redirectedfrom'   => '($1 гидг һазрас авч одсмн)',
'jumptonavigation' => 'Һазр медлһн',
'jumptosearch'     => 'хәәлһн',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'currentevents'        => 'Ода болсн йовдл',
'mainpage'             => 'Эклц',
'mainpage-description' => 'Эклц',
'portal'               => 'Бүрдәцин хурал',

'ok'                  => 'Чик',
'retrievedfrom'       => '"$1" гидг халхас йовулсн',
'youhavenewmessages'  => 'Та $1 ($2)-та бәәнәт.',
'newmessageslink'     => 'шин зәңгс',
'newmessagesdifflink' => 'кенз сольлһн',
'editsection'         => 'чиклх',
'editsectionhint'     => '$1 гидг хүвиг чиклх',
'showtoc'             => 'үзүлх',
'hidetoc'             => 'бултулх',
'red-link-title'      => '$1 (халх бәәшго)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'Халх',
'nstab-user'     => 'Орлцач',
'nstab-special'  => 'Көдлхнә халх',
'nstab-image'    => 'Боомг',
'nstab-template' => 'Зура',
'nstab-help'     => 'Цəəлһлһн',
'nstab-category' => 'Янз',

# Login and logout pages
'welcomecreation'         => '== Ирхитн эрҗәнәвидн, $1! ==
Таднар шин бичгдлһн бүтв.
Тадна [[Special:Preferences|{{SITENAME}} preferences]] сольҗ бичә мартн.',
'yourname'                => 'Орлцачна нернь:',
'yourpassword'            => 'Түлкүр үг:',
'yourpasswordagain'       => 'Түлкүр үгиг давтн:',
'remembermypassword'      => 'Мини нерн эн тоолвртд тодлх',
'login'                   => 'Оруллһн',
'nav-login-createaccount' => 'Орх аль бичгдлһиг бүтәх',
'userlogin'               => 'Орх аль бичгдлһиг бүтәх',
'logout'                  => 'Һарх',
'userlogout'              => 'Һарх',
'nologinlink'             => 'Бичгдлһиг бүтәх',
'createaccount'           => 'Выль вики-авторлэн регистрациез',
'loginsuccesstitle'       => 'Йовудта оруллһн',
'wrongpassword'           => 'Буру түлкүр үг бичв.
Дәкәд арһ хәәтн.',

# Edit page toolbar
'bold_sample'   => 'Тарһн текст',
'bold_tip'      => 'Тарһн текст',
'italic_sample' => 'Өкәсн текст',
'italic_tip'    => 'Өкәсн текст',
'link_tip'      => 'Өвр заалһ',
'extlink_tip'   => 'Һаза заалһ (http:// гидг эклц бичә мартн)',
'math_tip'      => 'Эсвин бичг (LaTeX)',
'image_tip'     => 'Орцулсн боомг',
'media_tip'     => 'Боомгин заалһ',
'sig_tip'       => 'Тана тәвсн һар цагин темдгтә',

# Edit pages
'minoredit'       => 'Эн баһ чикллһн бәәнә',
'watchthis'       => 'Эн халхиг хәләх',
'savearticle'     => 'Халхиг хадһлх',
'preview'         => 'Хәләвр',
'showpreview'     => 'Хәләвриг үзүлх',
'showdiff'        => 'Сольлһдудиг үзүлх',
'anoneditwarning' => "'''Урдаснь зәңг:''' та орв биш.
Тадна IP хайг эн халха чикллһнә бүртклд бичҗ авх.",
'newarticle'      => '(Шин)',
'previewnote'     => "'''Эн хәләвр бәәҗ тускар тодлтн.'''
Тана сольлһдуд ода чигн хадһлсн уга!",
'editing'         => '$1 гидг халхиг чикллһн',
'templatesused'   => 'Зурад эн халхд олзлсн:',

# Diffs
'editundo' => 'уга кех',

# Search results
'searchresults'       => 'Хәәлһнә ашуд',
'searchresults-title' => 'Хәәлһнә ашуд "$1" төлә',
'search-suggest'      => 'Та эниг таанат: $1 ?',
'powersearch'         => 'Күчн хәәлһн',
'powersearch-legend'  => 'Күчн хәәлһн',
'powersearch-field'   => 'Хәәх',

# Preferences page
'preferences'   => 'Дурллһн',
'mypreferences' => 'Мини көгүд',

# Groups
'group-sysop' => 'Дарһас',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'эн халхиг чиклҗ',

# Recent changes
'recentchanges'   => 'Кенз чикллһдүд',
'rcshowhideminor' => 'баһ чиклһдүдиг $1',
'rcshowhidebots'  => 'ботмудиг $1',
'rcshowhideliu'   => 'орлцачнриг $1',
'rcshowhideanons' => 'нер уга орлцачнриг $1',
'rcshowhidemine'  => 'мини чиклһдүд $1',
'hide'            => 'бултулх',
'show'            => 'үзүлх',

# Recent changes linked
'recentchangeslinked-page' => 'Халхна нернь:',

# Upload
'upload' => 'Боомгиг тәвх',

# File description page
'filehist-user' => 'Орлцач',

# Miscellaneous special pages
'newpages' => 'Шин халхс',
'move'     => 'Көндәх',

# Book sources
'booksources-go' => 'Ор',

# Special:AllPages
'allpages'       => 'Цуг халхс',
'allarticles'    => 'Цуг халхс',
'allpagessubmit' => 'Орх',

# Special:LinkSearch
'linksearch' => 'Һаза заалһуд',

# Special:Log/newusers
'newuserlog-create-entry' => 'Шин орлцачна бичгдлһн',

# Watchlist
'watchlist'     => 'Шинҗллһнә бүрткл бичг',
'mywatchlist'   => 'Мини шинҗллһнә бүрткл бичг',
'watch'         => 'Хәләх',
'watchthispage' => 'Эн халхиг хәләҗ',
'unwatch'       => 'Хәләх биш',

# Delete
'deletepage' => 'Эн халхиг һарһҗ',

# Namespace form on various pages
'namespace'      => 'Нернә у:',
'blanknamespace' => '(Һол)',

# Contributions
'contributions' => 'Орлцачна өгүллһдүд',
'mycontris'     => 'Мини өгүллһдүд',

'sp-contributions-username' => 'IP хайг аль нернь:',
'sp-contributions-submit'   => 'Хәәлһн',

# What links here
'whatlinkshere'      => 'Эн һазрур заалһуд',
'whatlinkshere-page' => 'Халх:',

# Block/unblock
'contribslink' => 'өгллһн',

# Move page
'movearticle'  => 'Халхиг йовулх:',
'newtitle'     => 'Шин нернь:',
'move-watch'   => 'Эн халхиг хәләх',
'movepagebtn'  => 'Халхиг йовулх',
'pagemovedsub' => 'Йовудта йовуллһн',
'movereason'   => 'Учр:',

# Tooltip help for the actions
'tooltip-pt-userpage'    => 'Тана орлцачна халх',
'tooltip-pt-mytalk'      => 'Тадна күндллһнә халх',
'tooltip-pt-preferences' => 'Тана көгүд',
'tooltip-pt-login'       => 'Та орсн күцх бәәнәт, болв кергтә биш.',
'tooltip-pt-logout'      => 'Һарх',
'tooltip-ca-edit'        => 'Та эн халхиг чиклҗ чаднат.
Хадһлһна күртл хәләвр олзлтн эрҗәнә.',
'tooltip-ca-delete'      => 'Эн халхиг һарһҗ',
'tooltip-ca-move'        => 'Эн халхиг көндәҗ',
'tooltip-ca-unwatch'     => 'Эн халхиг мини шинҗллһнә бүрткл бичгәс һарһх',
'tooltip-search-go'      => 'Эн чик нертә халхд, эн бәәхлә, орх',
'tooltip-n-mainpage'     => 'Һол халхд орх',
'tooltip-n-randompage'   => 'Болв чигн халхиг үзүлх',
'tooltip-n-help'         => 'Дөң өггдг һазр',
'tooltip-t-upload'       => 'Зургиг, әгиг, болв нань чигн тәвх',
'tooltip-t-specialpages' => 'Цуг көдлхнә халхс',
'tooltip-t-print'        => 'Эн халхна барин бәәдл',
'tooltip-ca-nstab-user'  => 'Орлцачна халхиг үзүлх',
'tooltip-save'           => 'Тана сольлһдудиг хадһлтн',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'цуг',
'monthsall'     => 'цуг',

# action=purge
'confirm_purge_button' => 'Чик',

# Special:SpecialPages
'specialpages' => 'Көдлхнә халхс',

);

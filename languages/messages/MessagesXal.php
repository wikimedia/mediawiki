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

$namespaceNames = array(
	NS_MEDIA            => 'Аһар',
	NS_SPECIAL          => 'Көдлхнə',
	NS_TALK             => 'Ухалвр',
	NS_USER             => 'Орлцач',
	NS_USER_TALK        => 'Орлцачна_тускар_ухалвр',
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
# User preference toggles
'tog-rememberpassword' => 'Намаг эн тоолвчд тодлх',

# Dates
'sunday'        => 'Нарн',
'monday'        => 'Сарң',
'tuesday'       => 'Мигмр',
'wednesday'     => 'Үлмҗ',
'thursday'      => 'Пүрвә',
'friday'        => 'Басң',
'saturday'      => 'Бембә',
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
'pagecategories' => '{{PLURAL:$1|Янз|Янзс}}',
'subcategories'  => 'Баһар янзс',

'article'    => 'Бичг',
'newwindow'  => '(шин терзд)',
'cancel'     => 'Уга кех',
'mytalk'     => 'Күүндлһн бәәрм',
'anontalk'   => 'IP хайгна күндллһн',
'navigation' => 'Орм медлһн',

# Cologne Blue skin
'qbspecialpages' => 'Көдлхнә халхс',

'errorpagetitle'   => 'Эндү',
'returnto'         => '«$1» тал хәрү ирх.',
'tagline'          => '{{grammar:genitive|{{SITENAME}}}} гидг һазрас өггцн',
'help'             => 'Цәәлһлһн',
'search'           => 'Хәәлһн',
'searchbutton'     => 'Хәәлһн',
'go'               => 'Ор',
'searcharticle'    => 'Ор',
'history'          => 'Чикллһнə бүрткл',
'history_short'    => 'Чикллһнə бүрткл',
'printableversion' => 'Барин бәәдл',
'permalink'        => 'Даңгин заалһ',
'edit'             => 'Чиклх',
'create'           => 'Бүтәх',
'editthispage'     => 'Эн халхиг чиклх',
'create-this-page' => 'Эн халхиг бүтәх',
'delete'           => 'Һарһх',
'deletethispage'   => 'Эн халхиг һарһх',
'protect'          => 'Харсх',
'protect_change'   => 'сольх',
'protectthispage'  => 'Эн халхиг харсх',
'unprotect'        => 'Харсх уга',
'newpage'          => 'Шин халх',
'talkpage'         => 'Ухалвр',
'talkpagelinktext' => 'Ухалвр',
'personaltools'    => 'Эврән зер-зев',
'talk'             => 'Ухалвр',
'views'            => 'Хәләврүд',
'toolbox'          => 'Зер-зев',
'otherlanguages'   => 'Талдан келнд',
'redirectedfrom'   => '($1 гидг һазрас авч одсмн)',
'redirectpagesub'  => 'Авч оддг халх',
'lastmodifiedat'   => 'Эн халхна кенз чиклһн: $2, $1.',
'jumpto'           => 'Тал ирх:',
'jumptonavigation' => 'Һазр медлһн',
'jumptosearch'     => 'хәәлһн',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} тускар',
'copyright'            => 'Өггцн $1 йоста орҗ болм',
'currentevents'        => 'Ода болсн йовдл',
'disclaimers'          => 'Дааврас эс зөвшәрлһн',
'edithelp'             => 'Чикллһнә дөң',
'mainpage'             => 'Эклц',
'mainpage-description' => 'Эклц',
'portal'               => 'Бүрдәцин хург',
'privacy'              => 'Нууцин бодлһн',

'ok'                  => 'Чик',
'retrievedfrom'       => '"$1" гидг халхас йовулсн',
'youhavenewmessages'  => 'Та $1та бәәнәт ($2).',
'newmessageslink'     => 'шин зәңгс',
'newmessagesdifflink' => 'кенз сольлһн',
'editsection'         => 'чиклх',
'editold'             => 'чиклх',
'editlink'            => 'чиклх',
'viewsourcelink'      => 'медсн үзүлх',
'editsectionhint'     => '«$1» гидг хүвиг чиклх',
'showtoc'             => 'үзүлх',
'hidetoc'             => 'бултулх',
'site-rss-feed'       => '$1 — RSS-зәңг',
'site-atom-feed'      => '$1 — Atom-зәңһ',
'red-link-title'      => '$1 (халх бәәшго)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'Халх',
'nstab-user'     => 'Орлцач',
'nstab-special'  => 'Көдлхнә халх',
'nstab-project'  => 'Проектин халх',
'nstab-image'    => 'Боомг',
'nstab-template' => 'Зура',
'nstab-help'     => 'Цәәлһлһн',
'nstab-category' => 'Янз',

# General errors
'viewsource' => 'Медсн үзүлх',

# Login and logout pages
'logouttext'              => "'''Та һарв.'''

Та {{SITENAME}} гидг ормиг нертә уга олзлҗ чаднат, аль та [[Special:UserLogin|дәкәд орҗ]] цацу аль талдан нертә чаднат.
Зәрм халхс цааранднь та ода чигн орсн мет үзүлҗ чаддг тускар темдглтн (та хәләчин санлиг цеврлтл).",
'welcomecreation'         => '== Ирхитн эрҗәнәвидн, $1! ==
Таднар шин бичгдлһн бүтв.
Тадна [[Special:Preferences|{{SITENAME}} preferences]] сольҗ бичә мартн.',
'yourname'                => 'Орлцачна нернь:',
'yourpassword'            => 'Нууц үг:',
'yourpasswordagain'       => 'Нууц үгиг давтн:',
'remembermypassword'      => 'Мини нерн эн тоолвчд тодлх',
'login'                   => 'Орлһн',
'nav-login-createaccount' => 'Орх аль бичгдлһиг бүтәх',
'loginprompt'             => '{{SITENAME}} тал орлһна төлә, та «cookies» олзлдг кергтә.',
'userlogin'               => 'Орх аль бичгдлһиг бүтәх',
'logout'                  => 'Һарх',
'userlogout'              => 'Һарх',
'nologin'                 => "Бичгдлһта уга? '''$1'''.",
'nologinlink'             => 'Бичгдлһиг бүтәх',
'createaccount'           => 'Бичгдлһиг бүтәх',
'gotaccount'              => "Бичгдлһтә? '''$1'''.",
'loginsuccesstitle'       => 'Йовудта оруллһн',
'wrongpassword'           => 'Буру нууц үг бичв.
Дәкәд арһ хәәтн.',
'mailmypassword'          => 'Шин нууц үгиг E-mail бичгәр йовулҗ',
'loginlanguagelabel'      => 'Келн: $1',

# Password reset dialog
'resetpass'                 => 'Нууц үгиг сольх',
'resetpass_header'          => 'Бичгдллһнә нууц үгиг сольх',
'oldpassword'               => 'Көгшн нууц үг:',
'newpassword'               => 'Шин нууц үг:',
'retypenew'                 => 'Шин нууц үгиг дәкәд бичтн:',
'resetpass-submit-loggedin' => 'Нууц үгиг сольх',

# Edit page toolbar
'bold_sample'     => 'Тарһн бичг',
'bold_tip'        => 'Тарһн бичг',
'italic_sample'   => 'Өкәсн бичг',
'italic_tip'      => 'Өкәсн бичг',
'link_sample'     => 'Заалһна нерн',
'link_tip'        => 'Өвр заалһ',
'extlink_sample'  => 'http://www.example.com заалһна_нернь',
'extlink_tip'     => 'Һаза заалһ (http:// гидг эклц бичә мартн)',
'headline_sample' => 'Толһа нерн',
'headline_tip'    => 'Дү толһа нерн',
'math_sample'     => 'Эсвин бичг',
'math_tip'        => 'Эсвин бичг (LaTeX)',
'nowiki_sample'   => 'чинр уга бичг',
'image_tip'       => 'Орцулсн боомг',
'media_tip'       => 'Боомгин заалһ',
'sig_tip'         => 'Тана тәвсн һар цагин темдгтә',
'hr_tip'          => 'Кевтдг татасн (дундин бәәдлтә олзлтн)',

# Edit pages
'summary'                          => 'Учр-утх:',
'minoredit'                        => 'Баһ чиклһн',
'watchthis'                        => 'Хәләх',
'savearticle'                      => 'Хадһлх',
'preview'                          => 'Хәләвр',
'showpreview'                      => 'Хәләвр',
'showdiff'                         => 'Йилһән',
'anoneditwarning'                  => "'''Урдаснь зәңг:''' та орв биш.
Тадна IP хайг эн халха чикллһнә бүртклд бичҗ авх.",
'accmailtitle'                     => 'Нууц үгтә бичг йовулла.',
'newarticle'                       => '(Шин)',
'noarticletext'                    => "Эн халх хоосн. Та [[Special:Search/{{PAGENAME}}|эн нернә сананд орулһна хәәх]] , <span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{urlencode:{{FULLPAGENAME}}}}}} бүртклин бичгт хәәх], аль '''[{{fullurl:{{FULLPAGENAME}}|action=edit}} бүтәх]'''</span>.",
'previewnote'                      => "'''Эн мел хәләвр бәәдг тускар тодлтн.'''
Тана сольлһдуд ода чигн хадһлсн уга!",
'editing'                          => '«$1» гидг халхиг чикллһн',
'editingsection'                   => '«$1» гидг халхна чикллһн (хүв)',
'copyrightwarning'                 => "Буйн болтха, цуг өгүллһдүд {{SITENAME}} төлә $2 гидг закаһар кесн, тоолсн бәәдг тускар тодлтн (Дәкәд өггцд төлә $1 хәләтн).  Та тана бичсн чилклсн аль делгрңсн бәәҗ седхлә биш, эн ормд бичә бичтн.<br /> Дәкәд та маднд эн эврәнь бичсн, күмн әмтнә хазас аль цацу сул медснәс бәәдг үгән өгнәт. '''Зөвән авхла уга, харссн бичсн күүнә көдлмш бичә тәвтн!'''",
'copyrightwarning2'                => "Буйн болтха, цуг өгүллһдүд {{SITENAME}} төлә чиклсн аль һарһсн бәәдг чадта тускар тодлтн.  Та тана бичсн чилклсн аль делгрңсн бәәҗ седхлә биш, эн ормд бичә бичтн.<br /> Дәкәд та маднд эн эврәнь бичсн, күмн әмтнә хазас аль цацу сул медснәс бәәдг үгән өгнәт. '''Зөвән авхла уга, харссн бичсн күүнә көдлмш бичә тәвтн!'''",
'templatesused'                    => 'Зурад эн халхд олзлсн:',
'templatesusedpreview'             => 'Зурад эн хәләврт олзлсн:',
'template-protected'               => '(харссн)',
'permissionserrorstext-withaction' => 'Та $2 кеҗ болшго. Юнгад гихлә, эн {{PLURAL:$1|учрар|учрар}}:',
'edit-conflict'                    => 'Чикллһнә керүл.',

# History pages
'currentrev-asof'     => 'Ода болсн янз ($1)',
'previousrevision'    => '← хуучн чикллһн',
'nextrevision'        => 'шинәр чикллһн →',
'currentrevisionlink' => 'Ода цагин чикллһн',
'cur'                 => 'ода',
'last'                => 'урдк',
'histfirst'           => 'Эрт',
'histlast'            => 'Кенз',

# Revision deletion
'rev-delundel'   => 'үзүлх/бултулх',
'revdel-restore' => 'Үзгдллһиг сольх',

# History merging
'mergehistory-reason' => 'Учр:',

# Merge log
'revertmerge' => 'Хувах',

# Diffs
'difference' => '(Йилһән)',
'lineno'     => '$1 мөр:',
'editundo'   => 'уга кех',

# Search results
'searchresults'             => 'Хәәлһнә ашуд',
'searchresults-title'       => 'Хәәлһнә ашуд "$1" төлә',
'searchsubtitle'            => '«[[:$1]]» сурвра ([[Special:Prefixindex/$1|эн нертә эклсн халхс]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|эн нерт заадг]])',
'searchsubtitleinvalid'     => "Тадн '''$1''' төлә хәәләт",
'noexactmatch'              => "'''\"\$1\" гидг халх бәәшго.'''
Та энгиг бүтәҗ чаднат.",
'noexactmatch-nocreate'     => "'''\"\$1\" гидг нертә халх бәәшго.'''",
'viewprevnext'              => 'Гүүһәд хәләх ($1 {{int:pipe-separator}} $2) ($3)',
'search-result-size'        => '$1 ({{PLURAL:$2|$2 үг|$2 үгмүд|$2 үгмүд}})',
'search-suggest'            => 'Та эниг таанат: $1 ?',
'search-interwiki-more'     => '(дәкәд)',
'search-mwsuggest-enabled'  => 'селвгтә',
'search-mwsuggest-disabled' => 'селвг уга',
'powersearch'               => 'Күчн хәәлһн',
'powersearch-legend'        => 'Күчн хәәлһн',
'powersearch-redir'         => 'Авч одлһуд үзүлх',
'powersearch-field'         => 'Хәәх',

# Preferences page
'preferences'          => 'Дурллһн',
'mypreferences'        => 'Көгүд',
'prefs-edits'          => 'Чикллһдүднә то:',
'changepassword'       => 'Нууц үгиг сольҗ',
'prefs-datetime'       => 'Лит, өдр, цаг.',
'prefs-personal'       => 'Орлцачна көгүд',
'prefs-rc'             => 'Кенз чикллһдүд',
'prefs-watchlist'      => 'Шинҗллһнә бүрткл',
'prefs-resetpass'      => 'Нууц угиг сольҗ',
'prefs-email'          => "E-mail'ын көгүд",
'saveprefs'            => 'Хадһлх',
'restoreprefs'         => 'Цуг эклцин көгүдиг босхҗ тохрар',
'savedprefs'           => 'Тана көгүд хадһлсмн.',
'timezonelegend'       => 'Часин бүс:',
'youremail'            => 'E-mail хайг:',
'username'             => 'Орлцачна нер:',
'uid'                  => 'Орлцачна даран-кемҗән (ID):',
'prefs-memberingroups' => '{{PLURAL:$1|Багин|Багдудин}} хүв:',
'prefs-registration'   => 'Даранднь бичлһнә цаг:',
'yourrealname'         => 'Үнн нерн:',
'yourlanguage'         => 'Бәәдлин келн:',
'yournick'             => 'Тәвсн һар:',
'yourgender'           => 'Эр аль эм:',
'gender-unknown'       => 'Бичсн уга',
'gender-male'          => 'Эр',
'gender-female'        => 'Эм',
'prefs-help-gender'    => 'Эн дәкәд бәәдг: чик күндллһн тоолвртар төлә. Эн өггцн цуг әмтнә болх.',
'email'                => 'E-mail хайг',
'prefs-help-realname'  => 'Үнн нернь та эврә дурар бичнәт. Бичлхлә, эн тәвсн һарт элзлдг бәәх.',
'prefs-help-email'     => 'E-mail хайг та эврә дурар бичнәт. Бичхлә, тадн шин түлкүр үгиг бичгәр йовулсн өгҗ чаднат (мартхла). Тадн дәкәд талдан улсд тана күндллһнә халхар күндлҗ зөв өгҗ чаднат, тана E-mail үзүләд уга.',
'prefs-i18n'           => 'Улс-келн хоорнд бәәдл',
'prefs-signature'      => 'Тәвсн һаран',

# Groups
'group'               => 'Баг:',
'group-user'          => 'Орлцачнр',
'group-autoconfirmed' => 'Эврәр чик гисн орлцачнр',
'group-bot'           => 'Көдлврүд',
'group-sysop'         => 'Дарһас',
'group-bureaucrat'    => 'Сегләтрс',
'group-all'           => '(цуг)',

'group-user-member'          => 'Орлцач',
'group-autoconfirmed-member' => 'Эврән чик гисн орлцачнр',
'group-bot-member'           => 'Көдлвр',
'group-sysop-member'         => 'Дарһа',
'group-bureaucrat-member'    => 'Сегләтр',

'grouppage-sysop' => '{{ns:project}}:Дарһас',

# User rights log
'rightslog' => 'Орлцачна зөвәнә бүрткл',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'эн халхиг чиклх',

# Recent changes
'recentchanges'        => 'Кенз чикллһдүд',
'recentchanges-legend' => 'Кенз чикллһдүднә көгүд',
'rcshowhideminor'      => 'баһ чиклһдүдиг $1',
'rcshowhidebots'       => 'көдлврүдиг $1',
'rcshowhideliu'        => 'орлцачнриг $1',
'rcshowhideanons'      => 'нер уга орлцачнриг $1',
'rcshowhidemine'       => 'мини чиклһдүд $1',
'diff'                 => 'йилһ',
'hist'                 => 'тууҗ',
'hide'                 => 'бултулх',
'show'                 => 'үзүлх',
'minoreditletter'      => 'б',
'boteditletter'        => 'к',
'rc-enhanced-expand'   => 'Тодрхасиг үзүлх (JavaScript кергтә)',
'rc-enhanced-hide'     => 'Тодрхасиг бултулх',

# Recent changes linked
'recentchangeslinked'      => 'Садн чикллһдүд',
'recentchangeslinked-page' => 'Халхна нернь:',

# Upload
'upload'            => 'Боомгиг тәвх',
'uploadlogpage'     => 'Тәвллһнә бүрткл',
'filedesc'          => 'Учр-утх',
'fileuploadsummary' => 'Учр-утх:',
'savefile'          => 'Хадһлх',

'license'        => 'Закан:',
'license-header' => 'Закан:',

# File description page
'filehist'          => 'Боомгин тууҗ',
'filehist-current'  => 'ода цагин',
'filehist-datetime' => 'Өдр/цаг',
'filehist-user'     => 'Орлцач',
'imagelinks'        => 'Боомгд заалһуд',

# Random page
'randompage' => 'Генткн халх',

# Miscellaneous special pages
'nbytes'        => '$1 {{PLURAL:$1|байд|байдуд|байдуд}}',
'prefixindex'   => 'Цуг халхс эн эклцтә',
'newpages'      => 'Шин халхс',
'move'          => 'Көндәх',
'movethispage'  => 'Эн халхд шин нер аль шин орм өгх',
'pager-newer-n' => '{{PLURAL:$1|шинәр 1|шинәр $1}}',
'pager-older-n' => '{{PLURAL:$1|көгшәр 1|көгшәр $1}}',

# Book sources
'booksources-go' => 'Ор',

# Special:AllPages
'allpages'       => 'Цуг халхс',
'alphaindexline' => '$1 хөөн, $2 күртл',
'allarticles'    => 'Цуг халхс',
'allpagessubmit' => 'Орх',

# Special:LinkSearch
'linksearch' => 'Һаза заалһуд',

# Special:Log/newusers
'newuserlogpage'          => 'Бичгдлһн бүтәлһнә бүрткл',
'newuserlog-create-entry' => 'Шин орлцачна бичгдлһн',

# Special:ListGroupRights
'listgrouprights-members' => '(мөчүдин бүрткл)',

# E-mail user
'emailuser' => 'Энд E-mail йовулх',

# Watchlist
'watchlist'         => 'Шинҗллһнә бүрткл бичг',
'mywatchlist'       => 'Шинҗллһнә бүрткл',
'watchlistfor'      => "('''$1''' төлә)",
'watch'             => 'Хәләх',
'watchthispage'     => 'Эн халхиг хәләх',
'unwatch'           => 'Хәләх биш',
'watchlist-options' => 'Шинҗллһнә бүртклин көгүд',

# Delete
'deletepage'            => 'Эн халхиг һарһҗ',
'deletedarticle'        => '«[[$1]]» халхиг һарһв',
'deletecomment'         => 'Һарһллһна учр:',
'deleteotherreason'     => 'Талдан аль дәкәд учр:',
'deletereasonotherlist' => 'Талдан учр',

# Rollback
'rollbacklink' => 'хәрү кех',

# Protect
'protectlogpage'      => 'Харсллһна бүрткл',
'protectcomment'      => 'Учр:',
'protect-default'     => 'Цуг орлцачнрд зөв өгҗ',
'protect-level-sysop' => 'Дарһас һанцхн',
'protect-cantedit'    => 'Та эн халхна харсллһна кемҗән сольҗ чадхшв. Юнгад гихлә, та зөвән авв уга',
'restriction-type'    => 'Зөв:',
'restriction-level'   => 'Зөвән кемҗән:',

# Undelete
'undeletelink' => 'гүүһәд хәләх/босхҗ тохрах',

# Namespace form on various pages
'namespace'      => 'Нернә у:',
'blanknamespace' => '(Һол)',

# Contributions
'contributions' => 'Орлцачна өгүллһдүд',
'mycontris'     => 'Мини өгүллһдүд',
'uctop'         => '(ора)',
'month'         => 'Эн сарас (болн эртәр):',
'year'          => 'Эн җиләс (болн эртәр):',

'sp-contributions-blocklog' => 'бүсллһнә бүрткл',
'sp-contributions-talk'     => 'ухалвр',
'sp-contributions-username' => 'IP хайг аль нернь:',
'sp-contributions-submit'   => 'Хәәлһн',

# What links here
'whatlinkshere'      => 'Эн һазрур заалһуд',
'whatlinkshere-page' => 'Халх:',
'isredirect'         => 'авч оддг халх',

# Block/unblock
'blockip'          => 'Орлцачнриг бүслҗ',
'ipblocklist'      => 'Бүслсн IP хайгуд болн орлцачнр',
'blocklink'        => 'бүслх',
'unblocklink'      => 'бүслх биш',
'change-blocklink' => 'бүслһиг сольх',
'contribslink'     => 'өгллһн',
'blocklogpage'     => 'Бүсллһнә бүрткл',

# Move page
'movearticle'  => 'Халхиг йовулх:',
'newtitle'     => 'Шин нернь:',
'move-watch'   => 'Эн халхиг хәләх',
'movepagebtn'  => 'Халхиг йовулх',
'pagemovedsub' => 'Йовудта йовуллһн',
'movereason'   => 'Учр:',
'revertmove'   => 'хәрүлһн',

# Tooltip help for the actions
'tooltip-pt-userpage'            => 'Тана орлцачна халх',
'tooltip-pt-mytalk'              => 'Тадна күндллһнә халх',
'tooltip-pt-preferences'         => 'Тана көгүд',
'tooltip-pt-login'               => 'Та орсн күцх бәәнәт, болв кергтә биш.',
'tooltip-pt-logout'              => 'Һарх',
'tooltip-ca-talk'                => 'Халхин өггцнә ухалвр',
'tooltip-ca-edit'                => 'Та эн халхиг чиклҗ чаднат.
Буйн болтха, хадһлһна күртл хәләвр олзлтн.',
'tooltip-ca-addsection'          => 'Шин хүв эклх',
'tooltip-ca-viewsource'          => 'Эн халх харссн бәәнә.
Та энүнә медсн үзҗ чаднат.',
'tooltip-ca-history'             => 'Эн халха кенз чикллһдүд',
'tooltip-ca-protect'             => 'Эн халхиг харсх',
'tooltip-ca-delete'              => 'Эн халхиг һарһх',
'tooltip-ca-move'                => 'Эн халхиг көндәх',
'tooltip-ca-unwatch'             => 'Эн халхиг мини шинҗллһнә бүрткл бичгәс һарһх',
'tooltip-search'                 => '{{SITENAME}} төлә хәәх',
'tooltip-search-go'              => 'Эн чик нертә халхд, эн бәәхлә, орх',
'tooltip-search-fulltext'        => 'Эн бичәтә халхс хәәх',
'tooltip-n-mainpage'             => 'Һол халхд орх',
'tooltip-n-mainpage-description' => 'Нүр халхд орх',
'tooltip-n-randompage'           => 'Болв чигн халхиг үзүлх',
'tooltip-n-help'                 => 'Дөң өггдг һазр',
'tooltip-t-whatlinkshere'        => 'Цуг вики халхс эн халхд заадг',
'tooltip-feed-rss'               => 'Эн халхна RSS зәңһллһн',
'tooltip-feed-atom'              => 'Эн халхна Atom зәңгллһн',
'tooltip-t-emailuser'            => 'Эн орлцачнрт E-mail бичг йовулх',
'tooltip-t-upload'               => 'Зургиг, әгиг, болв нань чигн тәвх',
'tooltip-t-specialpages'         => 'Цуг көдлхнә халхс',
'tooltip-t-print'                => 'Эн халхна барин бәәдл',
'tooltip-t-permalink'            => 'Эн халхна янзд даңгин заалһ',
'tooltip-ca-nstab-main'          => 'Халхнь',
'tooltip-ca-nstab-user'          => 'Орлцачна халхиг үзүлх',
'tooltip-ca-nstab-special'       => 'Эн көдлхнә халх. Та эниг чиклҗ чадхшв.',
'tooltip-ca-nstab-project'       => 'Проектин халх',
'tooltip-save'                   => 'Тана сольлһдудиг хадһлтн',

# Media information
'file-info-size' => '($1 × $2 цегтә, боомгин кемҗән: $3, MIME янз: $4)',

# Metadata
'metadata-expand'   => 'Ик тодрхасиг үзүлх',
'metadata-collapse' => 'Ик тодрхасиг бултулх',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'цуг',
'namespacesall' => 'цуг',
'monthsall'     => 'цуг',

# action=purge
'confirm_purge_button' => 'Чик',

# Special:SpecialPages
'specialpages' => 'Көдлхнә халхс',

# HTML forms
'htmlform-reset' => 'Сольлһдудиг уга кех',

);

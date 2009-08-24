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
'tog-rememberpassword' => 'Намаг эн тоолчд тодлх',

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

# Categories related messages
'subcategories' => 'Баһар янзс',

'article'    => 'Бичг',
'cancel'     => 'Уга кех',
'mytalk'     => 'Күүндлһн бәәрм',
'anontalk'   => 'IP хайгна күндллһн',
'navigation' => 'Орм медлһн',

# Cologne Blue skin
'qbspecialpages' => 'Көдлхнә халхс',

'errorpagetitle'   => 'Эндү',
'returnto'         => '«$1» тал хәрү ирх.',
'help'             => 'Цәәлһлһн',
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
'editthispage'     => 'Эн халхиг чиклх',
'create-this-page' => 'Эн халхиг бүтәх',
'delete'           => 'Һарһх',
'deletethispage'   => 'Эн халхиг һарһх',
'protect'          => 'Харсх',
'protectthispage'  => 'Эн халхиг харсх',
'unprotect'        => 'Харсх уга',
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
'edithelp'             => 'Чикллһнә дөң',
'mainpage'             => 'Эклц',
'mainpage-description' => 'Эклц',
'portal'               => 'Бүрдәцин хурал',

'ok'                  => 'Чик',
'retrievedfrom'       => '"$1" гидг халхас йовулсн',
'youhavenewmessages'  => 'Та $1та бәәнәт ($2).',
'newmessageslink'     => 'шин зәңгс',
'newmessagesdifflink' => 'кенз сольлһн',
'editsection'         => 'чиклх',
'editold'             => 'чиклх',
'viewsourcelink'      => 'медсн үзүлх',
'editsectionhint'     => '«$1» гидг хүвиг чиклх',
'showtoc'             => 'үзүлх',
'hidetoc'             => 'бултулх',
'red-link-title'      => '$1 (халх бәәшго)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'Халх',
'nstab-user'     => 'Орлцач',
'nstab-special'  => 'Көдлхнә халх',
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
'login'                   => 'Оруллһн',
'nav-login-createaccount' => 'Орх аль бичгдлһиг бүтәх',
'loginprompt'             => '{{SITENAME}} тал оруллһна төлә, та «cookies» олзлдг кергтә.',
'userlogin'               => 'Орх аль бичгдлһиг бүтәх',
'logout'                  => 'Һарх',
'userlogout'              => 'Һарх',
'nologin'                 => 'Бичгдлһта уга? $1.',
'nologinlink'             => 'Бичгдлһиг бүтәх',
'createaccount'           => 'Бичгдлһиг бүтәх',
'gotaccount'              => 'Бичгдлһтә? $1.',
'loginsuccesstitle'       => 'Йовудта оруллһн',
'wrongpassword'           => 'Буру ннуц үг бичв.
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
'bold_sample'   => 'Тарһн бичг',
'bold_tip'      => 'Тарһн бичг',
'italic_sample' => 'Өкәсн бичг',
'italic_tip'    => 'Өкәсн бичг',
'link_tip'      => 'Өвр заалһ',
'extlink_tip'   => 'Һаза заалһ (http:// гидг эклц бичә мартн)',
'math_tip'      => 'Эсвин бичг (LaTeX)',
'image_tip'     => 'Орцулсн боомг',
'media_tip'     => 'Боомгин заалһ',
'sig_tip'       => 'Тана тәвсн һар цагин темдгтә',
'hr_tip'        => 'Кевтдг татасн (дундин бәәдлтә олзлтн)',

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
'previousrevision'    => '← хуучн чикллһн',
'nextrevision'        => 'шинәр чикллһн →',
'currentrevisionlink' => 'Ода цагин чикллһн',
'cur'                 => 'ода',
'last'                => 'урдк',
'histfirst'           => 'Эрт',
'histlast'            => 'Кенз',

# History merging
'mergehistory-reason' => 'Учр:',

# Diffs
'editundo' => 'уга кех',

# Search results
'searchresults'         => 'Хәәлһнә ашуд',
'searchresults-title'   => 'Хәәлһнә ашуд "$1" төлә',
'searchsubtitleinvalid' => "Тадн '''$1''' төлә хәәләт",
'noexactmatch'          => "'''\"\$1\" гидг халх бәәшго.'''
Та энгиг бүтәҗ чаднат.",
'noexactmatch-nocreate' => "'''\"\$1\" гидг нертә халх бәәшго.'''",
'search-suggest'        => 'Та эниг таанат: $1 ?',
'powersearch'           => 'Күчн хәәлһн',
'powersearch-legend'    => 'Күчн хәәлһн',
'powersearch-field'     => 'Хәәх',

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
'prefs-email'          => 'E-mail-н көгүд',
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
'yourlanguage'         => 'Келн:',
'yournick'             => 'Тәвсн һар:',
'yourgender'           => 'Эр-эм:',
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
'hide'                 => 'бултулх',
'show'                 => 'үзүлх',
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
'filehist-current' => 'ода цагин',
'filehist-user'    => 'Орлцач',

# Random page
'randompage' => 'Генткн халх',

# Miscellaneous special pages
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
'deletecomment'         => 'Һарһллһна учр:',
'deleteotherreason'     => 'Талдан аль дәкәд учр:',
'deletereasonotherlist' => 'Талдан учр',

# Rollback
'rollbacklink' => 'хәрү кех',

# Protect
'protectlogpage'      => 'Харсллһна бүрткл',
'protect-default'     => 'Цуг орлцачнрд зөв өгҗ',
'protect-level-sysop' => 'Дарһас һанцхн',
'restriction-type'    => 'Зөв:',
'restriction-level'   => 'Зөвән кемҗән:',

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

# Block/unblock
'blockip'      => 'Орлцачнриг бүслҗ',
'ipblocklist'  => 'Бүслсн IP хайгуд болн орлцачнр',
'contribslink' => 'өгллһн',
'blocklogpage' => 'Бүсллһнә бүрткл',

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
Буйн болтха, хадһлһна күртл хәләвр олзлтн.',
'tooltip-ca-addsection'  => 'Шин хүв эклх',
'tooltip-ca-viewsource'  => 'Эн халх харссн бәәнә.
Та энүнә медсн үзҗ чаднат.',
'tooltip-ca-protect'     => 'Эн халхиг харсх',
'tooltip-ca-delete'      => 'Эн халхиг һарһх',
'tooltip-ca-move'        => 'Эн халхиг көндәх',
'tooltip-ca-unwatch'     => 'Эн халхиг мини шинҗллһнә бүрткл бичгәс һарһх',
'tooltip-search'         => '{{SITENAME}} төлә хәәх',
'tooltip-search-go'      => 'Эн чик нертә халхд, эн бәәхлә, орх',
'tooltip-n-mainpage'     => 'Һол халхд орх',
'tooltip-n-randompage'   => 'Болв чигн халхиг үзүлх',
'tooltip-n-help'         => 'Дөң өггдг һазр',
'tooltip-t-emailuser'    => 'Эн орлцачнрт E-mail бичг йовулх',
'tooltip-t-upload'       => 'Зургиг, әгиг, болв нань чигн тәвх',
'tooltip-t-specialpages' => 'Цуг көдлхнә халхс',
'tooltip-t-print'        => 'Эн халхна барин бәәдл',
'tooltip-ca-nstab-user'  => 'Орлцачна халхиг үзүлх',
'tooltip-save'           => 'Тана сольлһдудиг хадһлтн',

# Metadata
'metadata-expand'   => 'Ик тодрхасиг үзүлх',
'metadata-collapse' => 'Ик тодрхасиг бултулх',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'цуг',
'monthsall'     => 'цуг',

# action=purge
'confirm_purge_button' => 'Чик',

# Special:SpecialPages
'specialpages' => 'Көдлхнә халхс',

# HTML forms
'htmlform-reset' => 'Сольлһдудиг уга кех',

);

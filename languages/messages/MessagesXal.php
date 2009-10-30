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
'tog-rememberpassword' => 'Намаг эн тоолдврд тодлх',

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
'pagecategories'         => '{{PLURAL:$1|Янз|Янзс}}',
'category_header'        => '«$1» гидг нерәдлһтә халхс',
'subcategories'          => 'Баһар янзс',
'hidden-categories'      => '{{PLURAL:$1|Бултулсн янз|Бултулсн янзс}}',
'listingcontinuesabbrev' => '(цааранднь)',

'article'    => 'Бичг',
'newwindow'  => '(шин терзд)',
'cancel'     => 'Уга кех',
'mytalk'     => 'Күүндлһн бәәрм',
'anontalk'   => 'IP хайгна күндллһн',
'navigation' => 'Орм медлһн',

# Cologne Blue skin
'qbfind'         => 'Хәәлһн',
'qbspecialpages' => 'Көдлхнә халхс',

'errorpagetitle'   => 'Эндү',
'returnto'         => '«$1» тал хәрү ирх.',
'tagline'          => '{{grammar:genitive|{{SITENAME}}}} гидг һазрас өггцн',
'help'             => 'Цәәлһлһн',
'search'           => 'Хәәлһн',
'searchbutton'     => 'Хәәлһн',
'go'               => 'Ор',
'searcharticle'    => 'Ор',
'history'          => 'тууҗ',
'history_short'    => 'Тууҗ',
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
'aboutpage'            => 'Project:Тодлҗ бичлһн',
'copyright'            => 'Өггцн $1 йоста орҗ болм',
'copyrightpage'        => '{{ns:project}}:Бичсн күүнә зөв',
'currentevents'        => 'Ода болсн йовдл',
'disclaimers'          => 'Дааврас эс зөвшәрлһн',
'disclaimerpage'       => 'Project:Даарас эс зөвшәрлһн',
'edithelp'             => 'Чикллһнә дөң',
'edithelppage'         => 'Help:Чикллһн',
'helppage'             => 'Help:Һарг',
'mainpage'             => 'Эклц',
'mainpage-description' => 'Эклц',
'portal'               => 'Бүрдәцин хург',
'privacy'              => 'Нууцин бодлһн',
'privacypage'          => 'Project:Нууцин бодлһн',

'badaccess' => 'Зөвәнә эндү',

'ok'                  => 'Чик',
'retrievedfrom'       => '"$1" гидг халхас йовулсн',
'youhavenewmessages'  => 'Та $1та бәәнәт ($2).',
'newmessageslink'     => 'шин зәңгс',
'newmessagesdifflink' => 'кенз сольлһн',
'editsection'         => 'чиклх',
'editold'             => 'чиклх',
'editlink'            => 'чиклх',
'viewsourcelink'      => 'ишиг үзүлх',
'editsectionhint'     => '«$1» гидг хүвиг чиклх',
'toc'                 => 'Һарг',
'showtoc'             => 'үзүлх',
'hidetoc'             => 'бултулх',
'site-rss-feed'       => '$1 — RSS-зәңг',
'site-atom-feed'      => '$1 — Atom-зәңг',
'page-rss-feed'       => '«$1» — RSS-зәнгллһн',
'page-atom-feed'      => '«$1» — Atom зәнгллһн',
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
'missingarticle-rev' => '($1 тойгта халхна янз)',
'viewsource'         => 'Ишиг үзүлх',

# Login and logout pages
'logouttext'              => "'''Та һарад бәәнәт.'''

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
'notloggedin'             => 'Та орв биш',
'nologin'                 => "Бичгдлһта уга? '''$1'''.",
'nologinlink'             => 'Бичгдлһиг бүтәх',
'createaccount'           => 'Бичгдлһиг бүтәх',
'gotaccount'              => "Бичгдлһтә? '''$1'''.",
'userexists'              => 'Эн нер олзлдг юмн. 
Буйн болтха, талдан нернь автн.',
'loginerror'              => 'Орлһна эндү',
'createaccounterror'      => 'Бичгдлһиг бүтах болхго: $1',
'loginsuccesstitle'       => 'Йовудта оруллһн',
'wrongpassword'           => 'Та буру нууц үг бичв.
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
'extlink_sample'  => 'http://www.example.com заалһна нернь',
'extlink_tip'     => 'Һаза заалһ (http:// гидг эклц бичә мартн)',
'headline_sample' => 'Толһа нерн',
'headline_tip'    => 'Дү толһа нерн',
'math_sample'     => 'Эсвин бичг',
'math_tip'        => 'Эсвин бичг (LaTeX)',
'nowiki_sample'   => 'чинр уга бичг',
'nowiki_tip'      => 'Вики темдглһиг басх',
'image_tip'       => 'Орцулсн боомг',
'media_tip'       => 'Боомгин заалһ',
'sig_tip'         => 'Тана тәвсн һар цагин темдгтә',
'hr_tip'          => 'Кевтдг татасн (дундин бәәдлтә олзлтн)',

# Edit pages
'summary'                          => 'Учр-утх:',
'subject'                          => 'Төр/нерәдлһн:',
'minoredit'                        => 'Баһ чиклһн',
'watchthis'                        => 'Хәләх',
'savearticle'                      => 'Хадһлх',
'preview'                          => 'Хәләвр',
'showpreview'                      => 'Хәләвр',
'showdiff'                         => 'Йилһән',
'anoneditwarning'                  => "'''Урдаснь зәңг:''' та орв биш.
Тадна IP хайг эн халхна чикллһнә сеткүлд бичҗ авх.",
'summary-preview'                  => 'Эн учр-утх болх:',
'accmailtitle'                     => 'Нууц үгтә бичг йовулла.',
'newarticle'                       => '(Шин)',
'noarticletext'                    => "Эн халх хоосн. Та [[Special:Search/{{PAGENAME}}|эн нернә сананд орулһна хәәх]] , <span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{urlencode:{{FULLPAGENAME}}}}}} бүртклин бичгт хәәх], аль '''[{{fullurl:{{FULLPAGENAME}}|action=edit}} бүтәх]'''</span>.",
'previewnote'                      => "'''Эн мел хәләвр бәәдг тускар тодлтн.'''
Тана сольлһдуд ода чигн хадһлсн уга!",
'editing'                          => '«$1» гидг халхиг чикллһн',
'editingsection'                   => '«$1» гидг халхна чикллһн (хүв)',
'copyrightwarning'                 => "Буйн болтха, цуг өгүллһдүд {{SITENAME}} төлә $2 гидг закаһар кесн, тоолсн бәәдг тускар тодлтн (Дәкәд өггцд төлә $1 хәләтн).  Та тана бичсн чилклсн аль делгрңсн бәәҗ седхлә биш, эн ормд бичә бичтн.<br /> Дәкәд та маднд эн эврәнь бичсн, күмн әмтнә хазас аль цацу сул медснәс бәәдг үгән өгнәт. '''Зөвән авхла уга, харссн бичсн күүнә көдлмш бичә тәвтн!'''",
'copyrightwarning2'                => "Буйн болтха, цуг өгүллһдүд {{SITENAME}} төлә чиклсн аль һарһсн бәәдг чадта тускар тодлтн.  Та тана бичсн чилклсн аль делгрңсн бәәҗ седхлә биш, эн ормд бичә бичтн.<br /> Дәкәд та маднд эн эврәнь бичсн, күмн әмтнә хазас аль цацу сул медснәс бәәдг үгән өгнәт. '''Зөвән авхла уга, харссн бичсн күүнә көдлмш бичә тәвтн!'''",
'templatesused'                    => 'Эн халхд олзлсн {{PLURAL:$1|зурас|зурас}}:',
'templatesusedpreview'             => '{{PLURAL:$1|Зура|Зурас}} эн хәләврт олзлсн:',
'template-protected'               => '(харссн)',
'template-semiprotected'           => '(зәрм харссн)',
'hiddencategories'                 => 'Эн халх тер $1 {{PLURAL:$1|бултулсн янзас|бултулсн янздас|бултулсн янздас}}:',
'permissionserrorstext-withaction' => 'Та $2 кеҗ болшго. Юнгад гихлә, эн {{PLURAL:$1|учрар|учрар}}:',
'edit-conflict'                    => 'Чикллһнә керүл.',

# History pages
'viewpagelogs'           => 'Эн халхна сеткүлдүд үзүлх',
'currentrev-asof'        => 'Ода болсн янз ($1)',
'revisionasof'           => 'Эн цагин янз: $1',
'previousrevision'       => '← хуучн чикллһн',
'nextrevision'           => 'шинәр чикллһн →',
'currentrevisionlink'    => 'Ода цагин чикллһн',
'cur'                    => 'ода',
'last'                   => 'урдк',
'histlegend'             => "Тәәлвр: (ода) — одачн янзас йилһән; (урдк) — урдк янзас йилһән; '''б''' — баһ хүврһн",
'history-fieldset-title' => 'Тууҗиг хәләх',
'histfirst'              => 'Эрт',
'histlast'               => 'Кенз',

# Revision deletion
'rev-delundel'   => 'үзүлх/бултулх',
'revdel-restore' => 'Үзгдллһиг сольх',

# History merging
'mergehistory-reason' => 'Учр:',

# Merge log
'revertmerge' => 'Хувах',

# Diffs
'history-title'           => '$1 — хүврһнә тууҗ',
'difference'              => '(Йилһән)',
'lineno'                  => '$1 мөр:',
'compareselectedversions' => 'Суңһсн янзс әдлцүлх',
'editundo'                => 'уга кех',

# Search results
'searchresults'             => 'Хәәлһнә ашуд',
'searchresults-title'       => 'Хәәлһнә ашуд "$1" төлә',
'searchresulttext'          => 'Дәкәд өггцна төлә,  [[{{MediaWiki:Helppage}}|дөң өггдг һазрт]] хәләтн.',
'searchsubtitle'            => '«[[:$1]]» сурвра ([[Special:Prefixindex/$1|эн нертә эклсн халхс]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|эн нерт заадг]])',
'searchsubtitleinvalid'     => "Тадн '''$1''' төлә хәәләт",
'notitlematches'            => 'Нернә ирлцлһн уга',
'notextmatches'             => 'Әдл бичг халхд уга',
'prevn'                     => 'урдк {{PLURAL:$1|$1}}',
'nextn'                     => 'дарук {{PLURAL:$1|$1}}',
'viewprevnext'              => 'Гүүһәд хәләх ($1 {{int:pipe-separator}} $2) ($3)',
'search-result-size'        => '$1 ({{PLURAL:$2|$2 үг|$2 үгмүд|$2 үгмүд}})',
'search-redirect'           => '(авч одлһн $1)',
'search-section'            => '($1 хүв)',
'search-suggest'            => 'Та эниг таанат: $1 ?',
'search-interwiki-caption'  => 'Садта проектмуд',
'search-interwiki-default'  => '$1 ашуд:',
'search-interwiki-more'     => '(дәкәд)',
'search-mwsuggest-enabled'  => 'селвгтә',
'search-mwsuggest-disabled' => 'селвг уга',
'nonefound'                 => "'''Нүдлтн''': Мел зәрм нернә у талд урдаснь хәәсмн.
''all:'' гидг эклц немтн та һазр болһнд хәәх.",
'powersearch'               => 'Күчн хәәлһн',
'powersearch-legend'        => 'Күчн хәәлһн',
'powersearch-ns'            => 'Эн нернә у дотран хәәх:',
'powersearch-redir'         => 'Авч одлһуд үзүлх',
'powersearch-field'         => 'Хәәх',

# Preferences page
'preferences'          => 'Дурллһн',
'mypreferences'        => 'Көгүд',
'prefs-edits'          => 'Чикллһдүднә то:',
'prefsnologin'         => 'Та харһв биш',
'prefsnologintext'     => 'Та <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} харһх]</span> кергтә,  тегәд көгүдиг сольҗ чаднат.',
'changepassword'       => 'Нууц үгиг сольҗ',
'prefs-skin'           => 'Хувцнь',
'prefs-datetime'       => 'Лит, өдр, цаг',
'prefs-personal'       => 'Орлцачна көгүд',
'prefs-rc'             => 'Кенз хүврлһд',
'prefs-watchlist'      => 'Шинҗллһнә сеткүл',
'prefs-misc'           => 'Талдан',
'prefs-resetpass'      => 'Нууц угиг сольҗ',
'prefs-email'          => "E-mail'ын көгүд",
'prefs-rendering'      => 'Һазад бәәдл',
'saveprefs'            => 'Хадһлх',
'restoreprefs'         => 'Цуг эклцин көгүдиг босхҗ тохрар',
'prefs-editing'        => 'Чикллһн',
'savedprefs'           => 'Тана көгүдиг хадһлв.',
'timezonelegend'       => 'Часин бүс:',
'youremail'            => 'E-mail хайг:',
'username'             => 'Орлцачна нер:',
'uid'                  => 'Орлцачна тойг (ID):',
'prefs-memberingroups' => '{{PLURAL:$1|Багин|Багдудин}} хүв:',
'prefs-registration'   => 'Даранднь бичлһнә цаг:',
'yourrealname'         => 'Үнн нерн:',
'yourlanguage'         => 'Бәәдлин келн:',
'yournick'             => 'Тәвсн һар:',
'yourgender'           => 'Киисн:',
'gender-unknown'       => 'Бичсн уга',
'gender-male'          => 'Эр',
'gender-female'        => 'Эм',
'prefs-help-gender'    => 'Эн дәкәд бәәдг: чик күндллһн тоолвртар төлә. Эн өггцн цуг әмтнә болх.',
'email'                => 'E-mail хайг',
'prefs-help-realname'  => 'Үнн нернь та эврә дурар бичнәт. Бичлхлә, эн тәвсн һарт элзлдг бәәх.',
'prefs-help-email'     => 'E-mail хайг та эврә дурар бичнәт. Бичхлә, тадн шин түлкүр үгиг бичгәр йовулсн өгҗ чаднат (мартхла). Тадн дәкәд талдан улсд тана күндллһнә халхар күндлҗ зөв өгҗ чаднат, тана E-mail үзүләд уга.',
'prefs-i18n'           => 'Интернационализация',
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

'grouppage-user'          => '{{ns:project}}:Орлцачнр',
'grouppage-autoconfirmed' => '{{ns:project}}:Эврәр чик гисн орлцачнр',
'grouppage-bot'           => '{{ns:project}}:Көдлврүд',
'grouppage-sysop'         => '{{ns:project}}:Дарһас',
'grouppage-bureaucrat'    => '{{ns:project}}:Сегләтрс',

# User rights log
'rightslog' => 'Орлцачна зөвәнә сеткүл',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'эн халхиг чиклх',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|хүврлһн|хүврлһд}}',
'recentchanges'                  => 'Кенз хүврлһд',
'recentchanges-legend'           => 'Кенз хүврлһдә көгүд',
'recentchangestext'              => 'Эн цагин дараһар бичсн кенз хүврлһд',
'recentchanges-feed-description' => 'Эн зәңгллһд кенз хүврһд шинҗлх.',
'recentchanges-label-legend'     => 'Тәәлвр: $1.',
'recentchanges-legend-newpage'   => '$1 — шин халх',
'recentchanges-label-newpage'    => 'Эн үүлдәр шин халх бүтәл',
'recentchanges-legend-minor'     => '$1 — баһ хүврлһн',
'recentchanges-label-minor'      => 'Эн баһ чинртә хуврлһн',
'recentchanges-legend-bot'       => '$1 — көдлврә хүврлһн',
'recentchanges-label-bot'        => 'Эн хүврһн көдлвр (робот) кехв',
'rcnote'                         => "{{PLURAL:$1|'''$1''' кенз хүврлһн|'''$1''' кенз хүврлһд|'''$1''' кенз хүврлһд}}, '''$2''' өдрә,  $5 $4 цагин.",
'rclistfrom'                     => 'Эн цагас хүврлһүдиг үзүлг: $1.',
'rcshowhideminor'                => 'баһ чиклһдүдиг $1',
'rcshowhidebots'                 => 'көдлврүдиг $1',
'rcshowhideliu'                  => 'орлцачнриг $1',
'rcshowhideanons'                => 'нер уга орлцачнриг $1',
'rcshowhidemine'                 => 'мини чиклһдүд $1',
'rclinks'                        => 'Кенз $1 хүврлһдиг, кенз $2 өдрмүдт үзүлх<br />$3',
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
'recentchangeslinked'       => 'Садн чикллһдүд',
'recentchangeslinked-title' => '$1 садта хүврлһд',
'recentchangeslinked-page'  => 'Халхна нернь:',
'recentchangeslinked-to'    => 'Зөрүһәр, эн халхд заалдг халхсин хүврлһдиг үзүлх',

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

# File description page
'filehist'                  => 'Боомгин тууҗ',
'filehist-help'             => 'Боомгин өңгрсн  цагин янз хәләх, цагиг дартн.',
'filehist-current'          => 'ода цагин',
'filehist-datetime'         => 'Өдр/цаг',
'filehist-thumb'            => 'Зураллһн',
'filehist-thumbtext'        => '$1 янзин зураллһн',
'filehist-user'             => 'Орлцач',
'filehist-dimensions'       => 'Юмна кир',
'filehist-comment'          => 'Аҗгллһн',
'imagelinks'                => 'Боомгд заалһуд',
'linkstoimage'              => '{{PLURAL:$1|Эн $1 халх|Эн $1 халхс|Эн $1 халхс}} тер боомгд заалдг бәәнә:',
'sharedupload'              => 'Эн боомг $1 ормас. Териг талдан төсвд олзлҗ болх.',
'uploadnewversion-linktext' => 'Эн боомгин шин янз тәвх.',

# Random page
'randompage' => 'Уршг халх',

# Statistics
'statistics' => 'То бүрткл',

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
'newuserlog-create-entry' => 'Шин орлцачна бичгдлһн',

# Special:ListGroupRights
'listgrouprights-members' => '(мөчүдин сеткүл)',

# E-mail user
'emailuser' => 'Энд E-mail йовулх',

# Watchlist
'watchlist'         => 'Шинҗллһнә бүрткл бичг',
'mywatchlist'       => 'Шинҗллһнә бүрткл',
'watchlistfor'      => "('''$1''' төлә)",
'addedwatch'        => 'Шинҗллһнә сеткүлд немв.',
'addedwatchtext'    => "«[[:$1]]» гидг нерәдлһтә халх тана [[Special:Watchlist|шинҗллһнә сеткүлд]] немв.
Тегәд тер халхна болн терүнә ухалврин хүврлһд энд шиҗлсн болх. Эн халх '''тарһн'' үзгәр [[Special:RecentChanges|кенз хүврлһнә]] халхд бичсн (амр умшхар) болх.",
'removedwatch'      => 'Шинҗллһнә сеткүләс һарһв.',
'removedwatchtext'  => '«[[:$1]]» гидг нерәдлһтә халх тана [[Special:Watchlist|шинҗллһнә сеткүләс]] һарһв.',
'watch'             => 'Хәләх',
'watchthispage'     => 'Эн халхиг хәләх',
'unwatch'           => 'Хәләх биш',
'watchlist-details' => '$1 {{PLURAL:$1|халх|халхс|халхс}} ухалвр угаһар тана шиҗллһнә сеткүлд.',
'wlshowlast'        => 'Кенз $1 часд $2 өдрт $3 үзүлх',
'watchlist-options' => 'Шинҗллһнә сеткүлин көгүд',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Шинҗллһнә бүтлклд немлһн...',
'unwatching' => 'Шинҗлһнә бүрткләс һарһлһн...',

# Delete
'deletepage'            => 'Эн халхиг һарһҗ',
'confirmdeletetext'     => 'Та цуг халхиг аль зургиг һарһҗ орхар бәәнәт.
Буйн болтха, та үнәр тана үүлдин ашуд болн [[{{MediaWiki:Policy-url}}]] әңгин зокал медәд, эн батлҗ кетн.',
'actioncomplete'        => 'Үүлд кев',
'deletedtext'           => '«<nowiki>$1</nowiki>» һарһҗ болв.
$2 кенз һарһлһда төлә хәләтн.',
'deletedarticle'        => '«[[$1]]» халхиг һарһв',
'dellogpage'            => 'Һарһллһна сеткүл',
'deletecomment'         => 'Һарһллһна учр:',
'deleteotherreason'     => 'Талдан аль дәкәд учр:',
'deletereasonotherlist' => 'Талдан учр',

# Rollback
'rollbacklink' => 'хәрү кех',

# Protect
'protectlogpage'              => 'Харсллһна сеткүл',
'protectedarticle'            => '«[[$1]]» халх харсв',
'modifiedarticleprotection'   => '[[$1]] халхна харсллһна кемҗән хүврлх',
'protectcomment'              => 'Учр:',
'protectexpiry'               => 'Өңгрнә:',
'protect_expiry_invalid'      => 'Буру өңгрллһнә цаг',
'protect_expiry_old'          => 'Өңгрллһнә цаг бәәв.',
'protect-unchain'             => 'Көндллһиг зөвшәх.',
'protect-text'                => "Энд та '''<nowiki>$1</nowiki>''' халхин харсллһна кемҗән хәләҗ,  хүврлҗ чаднат.",
'protect-locked-access'       => "Эн халхна харсллһна кемҗән сольҗ, тана бичгдлһна зөв тату.
Ода болсн '''$1''' халхна көгүд:",
'protect-default'             => 'Цуг орлцачнрд зөв өгҗ',
'protect-fallback'            => '$1 зөв кергтә',
'protect-level-autoconfirmed' => 'Шин болн нер уга орлцачнрас  харсх',
'protect-level-sysop'         => 'Дарһас һанцхн',
'protect-summary-cascade'     => 'каскад',
'protect-expiring'            => '$1 (UTC) гидг цагт өңгрнә',
'protect-cascade'             => 'Халхсиг эн халхд дотр харсх (каскад)',
'protect-cantedit'            => 'Та эн халхна харсллһна кемҗән сольҗ чадхшв. Юнгад гихлә, та зөвән авв уга',
'restriction-type'            => 'Зөв:',
'restriction-level'           => 'Зөвән кемҗән:',

# Undelete
'undeletelink'     => 'гүүһәд хәләх/босхҗ тохрах',
'undeleteinvert'   => 'Зөрү суңһлт',
'undeletedarticle' => '«[[$1]]» хәрү кехв',

# Namespace form on various pages
'namespace'      => 'Нернә у:',
'invert'         => 'Зөрү суңһлт',
'blanknamespace' => '(Һол)',

# Contributions
'contributions'       => 'Орлцачна өгүллһдүд',
'contributions-title' => '$1 орлцачна демнлһн',
'mycontris'           => 'Мини өгүллһдүд',
'contribsub2'         => '$1 төлә ($2)',
'uctop'               => '(ора)',
'month'               => 'Эн сарас (болн эртәр):',
'year'                => 'Эн җиләс (болн эртәр):',

'sp-contributions-newbies'  => 'Шин бичгдлһтә орлцачна демнлһд һанцхн үзүлх',
'sp-contributions-blocklog' => 'бүсллһнә сеткүл',
'sp-contributions-talk'     => 'ухалвр',
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
'whatlinkshere-hideredirs' => '$1 авч одлһд',
'whatlinkshere-hidetrans'  => '$1 оруллһд',
'whatlinkshere-hidelinks'  => 'заалһудиг $1',
'whatlinkshere-filters'    => 'Шүрс',

# Block/unblock
'blockip'                  => 'Орлцачнриг бүслҗ',
'ipboptions'               => '2 часуд:2 hours,1 өдр:1 day,3 өдрмүд:3 days,1 долан хонг:1 week,2 долан хонгуд:2 weeks,1 сар:1 month,3 сармуд:3 months,6 сармуд:6 months,1 җил:1 year,мөнк:infinite',
'ipblocklist'              => 'Бүслсн IP хайгуд болн орлцачнр',
'blocklink'                => 'бүслх',
'unblocklink'              => 'бүслх биш',
'change-blocklink'         => 'бүслһиг сольх',
'contribslink'             => 'өгллһн',
'blocklogpage'             => 'Бүсллһнә сеткүл',
'blocklogentry'            => '[[$1]] бүслсн $2 күртл, $3 учрта',
'unblocklogentry'          => '$1 бүслсн биш',
'block-log-flags-nocreate' => 'бичгдлһиг бүтәҗ болшго',

# Move page
'movearticle'     => 'Халхиг йовулх:',
'newtitle'        => 'Шин нернь:',
'move-watch'      => 'Эн халхиг хәләх',
'movepagebtn'     => 'Халхиг йовулх',
'pagemovedsub'    => 'Йовудта йовуллһн',
'movepage-moved'  => '<big>\'\'\'"$1" шин нернь "$2" өгв\'\'\'</big>',
'articleexists'   => 'Тер нерәдлһтә халх бәәнә, аль та буру нернь суңһвт.
Буйн болтха, талдан нернь өгтн.',
'talkexists'      => "'''Халхин йовудта көндллһн. Болв, ухалвр халх көндәх болшго. Юнгад гихлә, эн нерәдлһтә халх бәәнә. Буйн болтха, териг һарар неҗәлтн.'''",
'movedto'         => 'көндсн:',
'movetalk'        => 'Өөр ухалвр халхиг көндәх.',
'1movedto2'       => '«[[$1]]» «[[$2]]» халхд көндв',
'1movedto2_redir' => '«[[$1]]»  «[[$2]]» халхд көндв (авч одлһн уга).',
'movelogpage'     => 'Нернә хүврлһнә сеткүл',
'movereason'      => 'Учр:',
'revertmove'      => 'хәрүлһн',

# Export
'export' => 'Халхин экспорт',

# Thumbnails
'thumbnail-more' => 'Икдүлх',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Тана орлцачна халх',
'tooltip-pt-mytalk'               => 'Тадна күндллһнә халх',
'tooltip-pt-preferences'          => 'Тана көгүд',
'tooltip-pt-watchlist'            => 'Халхс та шинҗлдг бәәнәт',
'tooltip-pt-mycontris'            => 'Тана хүврлһдә сеткүл',
'tooltip-pt-login'                => 'Та орсн күцх бәәнәт, болв кергтә биш.',
'tooltip-pt-logout'               => 'Һарх',
'tooltip-ca-talk'                 => 'Халхин өггцнә ухалвр',
'tooltip-ca-edit'                 => 'Та эн халхиг чиклҗ чаднат.
Буйн болтха, хадһлһна күртл хәләвр олзлтн.',
'tooltip-ca-addsection'           => 'Шин хүв эклх',
'tooltip-ca-viewsource'           => 'Эн халх харссн бәәнә.
Та энүнә медсн үзҗ чаднат.',
'tooltip-ca-history'              => 'Эн халха кенз чикллһдүд',
'tooltip-ca-protect'              => 'Эн халхиг харсх',
'tooltip-ca-delete'               => 'Эн халхиг һарһх',
'tooltip-ca-move'                 => 'Эн халхиг көндәх',
'tooltip-ca-watch'                => 'Эн халхиг тана шинҗллһнә сеткүлд немх',
'tooltip-ca-unwatch'              => 'Эн халхиг мини шинҗллһнә сеткүләс һарһх',
'tooltip-search'                  => '{{SITENAME}} төлә хәәх',
'tooltip-search-go'               => 'Эн чик нертә халхд, эн бәәхлә, орх',
'tooltip-search-fulltext'         => 'Эн бичәтә халхс хәәх',
'tooltip-n-mainpage'              => 'Һол халхд орх',
'tooltip-n-mainpage-description'  => 'Нүр халхд орх',
'tooltip-n-portal'                => 'Проектин туск; та ю кеҗ чаднат; орм медлһн',
'tooltip-n-currentevents'         => 'Ода болсн зәңгсин бурткл',
'tooltip-n-recentchanges'         => 'Кенз хүврлһнә сеткүл',
'tooltip-n-randompage'            => 'Болв чигн халхиг үзүлх',
'tooltip-n-help'                  => 'Дөң өггдг һазр',
'tooltip-t-whatlinkshere'         => 'Цуг вики халхс эн халхд заадг',
'tooltip-t-recentchangeslinked'   => 'Кенз хүврлһд халхсд эн халх заадг',
'tooltip-feed-rss'                => 'Эн халхна RSS зәңһллһн',
'tooltip-feed-atom'               => 'Эн халхна Atom зәңгллһн',
'tooltip-t-contributions'         => 'Эн орлцачна демнлһнә бүрткл үзүлх',
'tooltip-t-emailuser'             => 'Эн орлцачнрт E-mail бичг йовулх',
'tooltip-t-upload'                => 'Зургиг, әгиг, болв нань чигн тәвх',
'tooltip-t-specialpages'          => 'Цуг көдлхнә халхс',
'tooltip-t-print'                 => 'Эн халхна барин бәәдл',
'tooltip-t-permalink'             => 'Эн халхна янзд даңгин заалһ',
'tooltip-ca-nstab-main'           => 'Халхнь',
'tooltip-ca-nstab-user'           => 'Орлцачна халхиг үзүлх',
'tooltip-ca-nstab-special'        => 'Эн көдлхнә халх. Та эниг чиклҗ чадхшв.',
'tooltip-ca-nstab-project'        => 'Проектин халх',
'tooltip-ca-nstab-image'          => 'Боомгин халхиг',
'tooltip-ca-nstab-template'       => 'Зуран халх',
'tooltip-ca-nstab-category'       => 'Янзин халхиг үзүлх',
'tooltip-minoredit'               => 'Эн хүврлһдиг баһ чинртә темдглх',
'tooltip-save'                    => 'Тана сольлһдудиг хадһлтн',
'tooltip-preview'                 => 'Урдаснь хәләвр. Буйн болтха, энгиг олзлад, тегәд хадһлтн!',
'tooltip-diff'                    => 'Эн бичгәс хүврлһдиг үзүлх',
'tooltip-compareselectedversions' => 'Тер халхин хойр янзин йилһән үзулх',
'tooltip-watch'                   => 'Эн халхиг тана шинҗллһнә сеткүлд немх',
'tooltip-rollback'                => 'Кенз орлцачна хүврлһд нег дарцар хәрү кех',
'tooltip-undo'                    => 'Эн хүврлһиг уга келһн, хәләвртә болн  учрта.',

# Browsing diffs
'previousdiff' => '← Урдк хүврлһн',
'nextdiff'     => 'Дарук хүврлһн →',

# Media information
'file-info-size'       => '($1 × $2 цегтә, боомгин кемҗән: $3, MIME янз: $4)',
'file-nohires'         => '<small>Икәр чинртә янз уга.</small>',
'svg-long-desc'        => '(SVG боомг, $1 × $2 мет цегтә, боомгин кемҗән: $3)',
'show-big-image'       => 'Күцц чинр',
'show-big-image-thumb' => '<small>Урдаснь хәләврин кемҗән: $1 × $2 цегтә</small>',

# Bad image list
'bad_image_list' => 'Эн темдглһн кергтә:

Бүртклин мөчүд һанцхн оньгтан авх (мөрәд * эклцта).
Түрүн мөрәнә заалһ - тәвх хөрсн зургин заалһ.
Дарук заалһуд эн мөрәд хаҗилһн болх (халхс зургиг орулҗ болх).',

# Metadata
'metadata'          => 'Мета өггцн',
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

# External editor support
'edit-externally'      => 'Эн боомгиг һаза заклһар чиклх',
'edit-externally-help' => '([http://www.mediawiki.org/wiki/Manual:External_editors Тәвллһнә заалт]  икәр өггцнә төлә хәләтн)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'цуг',
'namespacesall' => 'цуг',
'monthsall'     => 'цуг',

# action=purge
'confirm_purge_button' => 'Чик',

# Auto-summaries
'autosumm-blank' => 'Халх цеврүлв',
'autosumm-new'   => 'Шин халх: «$1»',

# Watchlist editing tools
'watchlisttools-view' => 'Бүртклин халхна хүврлһд',
'watchlisttools-edit' => 'Сеткүлиг хәләх аль чиклх',
'watchlisttools-raw'  => 'Бичг мет чиклх',

# Special:SpecialPages
'specialpages' => 'Көдлхнә халхс',

# HTML forms
'htmlform-reset' => 'Сольлһдудиг уга кех',

);

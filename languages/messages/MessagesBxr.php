<?php
/** буряад (буряад)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Amire80
 * @author Bjargal
 * @author Elvonudinium
 * @author Korol Bumi
 * @author Soul Train
 * @author Губин Михаил
 * @author ОйЛ
 */

$fallback = 'ru';

$namespaceNames = array(
	NS_MEDIA            => 'Меди',
	NS_SPECIAL          => 'Тусхай',
	NS_TALK             => 'Хэлэлсэхэ',
	NS_USER             => 'Хэрэглэгшэ',
	NS_USER_TALK        => 'Хэрэглэгшые_хэлэлсэхэ',
	NS_PROJECT_TALK     => '$1_тухай_хэлэлсэхэ',
	NS_FILE             => 'Файл',
	NS_FILE_TALK        => 'Файл_хэлэлсэхэ',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_хэлэлсэхэ',
	NS_TEMPLATE         => 'Загбар',
	NS_TEMPLATE_TALK    => 'Загбар_хэлэлсэхэ',
	NS_HELP             => 'Туһаламжа',
	NS_HELP_TALK        => 'Туһаламжа_хэлэлсэл',
	NS_CATEGORY         => 'Категори',
	NS_CATEGORY_TALK    => 'Категори_хэлэлсэхэ',
);

$namespaceAliases = array(
	# Russian namespaces
	'Обсуждение'                         => NS_TALK,
	'Участник'                           => NS_USER,
	'Обсуждение_участника'               => NS_USER_TALK,
	'Обсуждение_{{GRAMMAR:genitive|$1}}' => NS_PROJECT_TALK,
	'Обсуждение_файла'                   => NS_FILE_TALK,
	'Обсуждение_MediaWiki'               => NS_MEDIAWIKI_TALK,
	'Обсуждение_шаблона'                 => NS_TEMPLATE_TALK,
	'Справка'                            => NS_HELP,
	'Обсуждение_справки'                 => NS_HELP_TALK,
	'Категория'                          => NS_CATEGORY,
	'Обсуждение_категории'               => NS_CATEGORY_TALK,
);

// Remove Russian gender aliases
$namespaceGenderAliases = array();

$specialPageAliases = array(
	'Activeusers'               => array( 'Эдэбхитэй_хэрэглэгшэд' ),
	'Allmessages'               => array( 'Бүхы_зурбас' ),
	'Allpages'                  => array( 'Соохи_бүхы_хуудаһан' ),
	'Ancientpages'              => array( 'Хуушарһан_хуудаһан' ),
	'Categories'                => array( 'Категоринууд' ),
	'ComparePages'              => array( 'Хуудаһа_харисуулха' ),
	'Confirmemail'              => array( 'Сахим_хаяг_баталха' ),
	'CreateAccount'             => array( 'Данса_үүсхэхэ' ),
	'Mypage'                    => array( 'Минии_хуудаһан' ),
	'Mytalk'                    => array( 'Минии_хэлэлсэл' ),
	'Myuploads'                 => array( 'Минии_ашаалһан_зүйл' ),
	'Newpages'                  => array( 'Шэнэ_хуудаһан' ),
	'Popularpages'              => array( 'Оло_уншагдаһан_хуудаһан' ),
	'Protectedpages'            => array( 'Хамгаалалтатай_хуудаһан' ),
	'Protectedtitles'           => array( 'Хамгаалалтатай_гаршаг' ),
	'Recentchanges'             => array( 'Сайтдахи_хубилалтанууд' ),
	'Upload'                    => array( 'Ашаалха' ),
	'Userlogin'                 => array( 'Нэбтэрхэ' ),
	'Userlogout'                => array( 'Гараха' ),
);

$messages = array(
# User preference toggles
'tog-underline' => 'Холбооһо доогуурнь зураха:',
'tog-watchcreations' => 'Минии үүдхэһэн хуудаһа болон ашаалһан файлыем хинаха жагсаалтада оруула',
'tog-watchdefault' => 'Минии заһаһан хуудаһа болон файлыем хинаха жагсаалтада оруула',
'tog-watchmoves' => 'Минии зөөһэн хуудаһа болон файлыем хинаха жагсаалтада оруула',
'tog-watchdeletion' => 'Минии усадхаһан хуудаһа болон файлыем хинаха жагсаалтада оруула',
'tog-minordefault' => 'Бүхы заһабариие бага зэргын гэжэ үгэгдэмэлөөр тэмдэглэ',
'tog-previewontop' => 'Уридшалан харахые заһабарилха талбарай урда үзүүлэ',
'tog-previewonfirst' => 'Уридшалан харахые эхилжэ заһаха үедэ үзүүлэ',

'underline-always' => 'хододоол',
'underline-never' => 'хэзээшье',

# Dates
'sunday' => 'Няма',
'monday' => 'Дабаа',
'tuesday' => 'Мягмар',
'wednesday' => 'Һагба',
'thursday' => 'Пүрбэ',
'friday' => 'Баасан',
'saturday' => 'Бямба',
'sun' => 'Ня',
'mon' => 'Да',
'tue' => 'Мя',
'wed' => 'Һа',
'thu' => 'Пү',
'fri' => 'Ба',
'sat' => 'Бя',
'january' => 'Нэгэдүгээр һара',
'february' => 'Хоёрдугаар һара',
'march' => 'Гурбадугаар һара',
'april' => 'Дүрбэдүгээр һара',
'may_long' => 'Табадугаар һара',
'june' => 'Зургадугаар һара',
'july' => 'Долодугаар һара',
'august' => 'Наймадугаар һара',
'september' => 'Юһэдүгээр һара',
'october' => 'Арбадугаар һара',
'november' => 'Арбаннэгэдүгээр һара',
'december' => 'Арбанхоёрдугаар һара',
'january-gen' => 'Нэгэдүгээр һарын',
'february-gen' => 'Хоёрдугаар һарын',
'march-gen' => 'Гурбадугаар һарын',
'april-gen' => 'Дүрбэдүгээр һарын',
'may-gen' => 'Табадугаар һарын',
'june-gen' => 'Зургадугаар һарын',
'july-gen' => 'Долодугаар һарын',
'august-gen' => 'Наймадугаар һарын',
'september-gen' => 'Юһэдүгээр һарын',
'october-gen' => 'Арбадугаар һарын',
'november-gen' => 'Арбаннэгэдүгээр һарын',
'december-gen' => 'Арбанхоёрдугаар һарын',
'jan' => '1 һара',
'feb' => '2 һара',
'mar' => '3 һара',
'apr' => '4 һара',
'may' => '5 һара',
'jun' => '6 һара',
'jul' => '7 һара',
'aug' => '8 һара',
'sep' => '9 һара',
'oct' => '10 һара',
'nov' => '11 һара',
'dec' => '12 һара',
'january-date' => '1 һарын $1',
'february-date' => '2 һарын $1',
'march-date' => '3 һарын $1',
'april-date' => '4 һарын $1',
'may-date' => '5 һарын $1',
'june-date' => '6 һарын $1',
'july-date' => '7 һарын $1',
'august-date' => '8 һарын $1',
'september-date' => '9 һарын $1',
'october-date' => '10 һарын $1',
'november-date' => '11 һарын $1',
'december-date' => '12 һарын $1',

# Categories related messages
'category_header' => '"$1" категориин үгүүлэлнүүд',

'cancel' => 'Болихо',
'moredotdotdot' => 'Үшөө...',
'morenotlisted' => 'Энэ жагсаалта дүүргэһэнгүй.',
'mypage' => 'Хуудаһан',
'mytalk' => 'Хэлэлсэл',
'anontalk' => 'Энэ IP адресаарнь хэлэхэ',
'navigation' => 'Залуурдалга',
'and' => '&#32;ба',

# Cologne Blue skin
'qbfind' => 'Хайха',
'qbedit' => 'Заһабарилха',

# Vector skin
'vector-action-addsection' => 'Һэдэб нэмэхэ',
'vector-action-delete' => 'Усадхаха',
'vector-view-create' => 'Үүдхэхэ',
'vector-view-edit' => 'Заһабарилха',
'vector-view-history' => 'Түүхые хараха',
'vector-view-view' => 'Уншаха',
'vector-view-viewsource' => 'эшэ үндэһэндэнь хандаха',
'actions' => 'γйлэ',
'variants' => 'Хубилбари',

'navigation-heading' => 'Тамаралгын меню',
'errorpagetitle' => 'Алдуу',
'tagline' => '{{SITENAME}} сайтһаа мэдээлэл',
'help' => 'Туһаламжа',
'search' => 'Хайха',
'searchbutton' => 'Хайлта',
'go' => 'Ябаха',
'searcharticle' => 'Ябаха',
'history' => 'Хуудаһанай түүхэ',
'history_short' => 'Түүхэ',
'printableversion' => 'Хэблэхэ хубилбари',
'permalink' => 'Үргэлжын холбооһон',
'print' => 'Хэблэхэ',
'view' => 'Харуулха',
'edit' => 'Заһабарилха',
'create' => 'Үүдхэхэ',
'delete' => 'Усадхаха',
'protect' => 'Хамгаалха',
'protect_change' => 'Хубилалга',
'newpage' => 'Шэнэ хуудаһан',
'talkpage' => 'Тус хуудаһа хэлэлсэхэ',
'talkpagelinktext' => 'Хэлэлсэхэ',
'specialpage' => 'Тусхай хуудаһан',
'personaltools' => 'Хубиин хэрэгсэлнүүд',
'postcomment' => 'Шэнэ бүлэг',
'talk' => 'Хэлэлсэхэ',
'views' => 'Үзэһэн',
'toolbox' => 'Багажа зэбсэг',
'projectpage' => 'Түлэблэлгын хуудаһые хараха',
'otherlanguages' => 'Бусад хэлээр',
'jumpto' => 'Шууд ошохо:',
'jumptonavigation' => 'тамаралга',
'jumptosearch' => 'хайха',
'pool-errorunknown' => 'Танигдаагүй алдуу',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage).
'aboutsite' => '{{SITENAME}} тухай',
'aboutpage' => 'Project:Түлэблэлгын тухай',
'currentevents' => 'Мүнөө боложо байгаа үйлэ ябадал',
'currentevents-url' => 'Project:Һонин мэдээн',
'disclaimers' => 'Татагалзалнууд',
'disclaimerpage' => 'Project:Ниитэ татагалзал',
'helppage' => 'Help:Агуулга',
'mainpage' => 'Нюур хуудаһан',
'mainpage-description' => 'Нюур хуудаһан',
'portal' => 'Хурал',
'portal-url' => 'Project:Хурал',
'privacy' => 'Хубиин мэдээлэлэй талаар баримталал',
'privacypage' => 'Project:Хубиин мэдээлэлэй талаар баримталал',

'ok' => 'За',
'retrievedfrom' => '"$1" холбооһоо абагдаһан',
'editsection' => 'заһабарилха',
'editold' => 'заһабарилха',
'viewsourceold' => 'эшэ үндэһэндэнь хандаха',
'editlink' => 'заһабарилха',
'viewsourcelink' => 'эшэ үндэһэндэнь хандаха',
'editsectionhint' => '$1 гэһэн бүлэг заһаха',
'toc' => 'Агуулга',
'showtoc' => 'харуулха',
'hidetoc' => 'нюуха',
'collapsible-collapse' => 'Нюуха',
'collapsible-expand' => 'Дэлгээхэ',
'site-atom-feed' => '$1 Атом фиид',
'red-link-title' => '$1 (хуудаһан үгы байна)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Хуудаһан',
'nstab-user' => 'Хэрэглэгшын хуудаһан',
'nstab-media' => 'Медиагай хуудаһан',
'nstab-special' => 'Тусхай хуудаһан',
'nstab-project' => 'Түлэблэлгын хуудаһан',
'nstab-image' => 'Файл',
'nstab-mediawiki' => 'Бэшэг',
'nstab-template' => 'Загбар',
'nstab-help' => 'Туһаламжын хуудаһан',
'nstab-category' => 'Категори',

# General errors
'error' => 'Алдуу',
'internalerror' => 'Доторой алдуу',
'internalerror_info' => 'Доторой алдуу: $1',

# Login and logout pages
'welcomeuser' => 'Морилжо хайрлыт, $1!',
'welcomecreation-msg' => 'Танай данса үүдхэһэн байна.
Та танай {{SITENAME}} [[Special:Preferences|preferences]]-ые өөршэлхэ боломжотойт.',
'yourname' => 'Хэрэглэгшын нэрэ:',
'userlogin-yourname' => 'Хэрэглэгшын нэрэ:',
'userlogin-yourname-ph' => 'Танай хэрэглэгшын нэрые оруулагты',
'createacct-another-username-ph' => 'Хэрэглэгшын нэрые оруулагты',
'yourpassword' => 'Нюуса үгэ:',
'login' => 'Нэбтэрхэ',
'nav-login-createaccount' => 'Нэбтэрхэ / данса үүдхэхэ',
'userlogin' => 'Нэбтэрхэ / данса үүдхэхэ',
'logout' => 'Гараха',
'userlogout' => 'Гараха',
'createaccount' => 'Данса үүдхэхэ',
'gotaccountlink' => 'Нэбтэрхэ',
'loginlanguagelabel' => 'Хэлэн: $1',

# Edit pages
'savearticle' => 'Хуудаһые хадагалха',
'showpreview' => 'Уридшалан хараха',
'showdiff' => 'Хубилалтые харуул',
'newarticle' => '(Шэнэ)',
'template-protected' => '(хамгаалалтатай)',
'permissionserrorstext-withaction' => 'Та доро тодорхойлһон $1 ушар шалтагаанһаа боложо, $2 эрхэгүйт.',

# Revision deletion
'rev-delundel' => 'харуулха/нюуха',
'revdel-restore' => 'харагдасыень хубилгаха',

# Diffs
'editundo' => 'болюулха',

# Search results
'searchmenu-new' => "'''Энэ викидэ \"[[:\$1]]\" гэһэн хуудаһа үүсхэхэ!''' Мүн олдоһон ондоо хуудаһа харагты.\"",
'searchprofile-articles' => 'Агуулгын хуудаһанууд',
'searchprofile-images-tooltip' => 'Файл хайха',
'search-result-size' => '$1 (ниитэ $2 үгэ�)',

# Preferences page
'mypreferences' => 'Тааруулга',
'prefs-datetime' => 'Огноо ба саг',
'youremail' => 'Сахим шуудан:',
'yourrealname' => 'Бодото нэрэ:',
'yourlanguage' => 'Хэлэн:',
'yourgender' => 'Хүйһыетнай хэн гэжэ заабал болохоб?',
'gender-male' => 'Эрэ хүн',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'энэ хуудаһа заһабарилха',

# Recent changes
'recentchanges' => 'Һүүлшын хубилалта',
'diff' => 'илгаа',
'hist' => 'түүхэ',
'hide' => 'Нюуха',

# Recent changes linked
'recentchangeslinked' => 'Холбогдохо хубилалта',
'recentchangeslinked-toolbox' => 'Холбогдохо хубилалта',

# Upload
'upload' => 'Файл ашаалха',

'license-header' => 'Лицензи',

# File description page
'file-anchor-link' => 'Файл',
'filehist' => 'Файлын түүхэ',
'filehist-datetime' => 'Огноо/Саг',
'filehist-user' => 'Хэрэглэгшэ',

# Random page
'randompage' => 'Һанамсаргүй хуудаһан',

# Statistics
'statistics' => 'Тоо бүридхэл',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|байт|байтууд}}',
'newpages' => 'Шэнэ хуудаһанууд',
'ancientpages' => 'Хуушарһан хуудаһан',
'move' => 'Шэлжүүлхэ',

# Special:Log
'log' => 'Логнууд',

# Special:AllPages
'allpages' => 'Бүхы хуудаһан',
'allarticles' => 'Бүхы хуудаһан',

# Special:Categories
'categories' => 'Категоринууд',

# Watchlist
'mywatchlist' => 'Ажаглаха зүйл',
'watch' => 'Ажаглаха',

# Undelete
'undeletelink' => 'хараха/һэргээхэ',

# Namespace form on various pages
'blanknamespace' => '(Гол)',

# Contributions
'mycontris' => 'Минии оруулһан зүйл',

'sp-contributions-talk' => 'Хэлэлсэл',

# What links here
'whatlinkshere' => 'Эндэ холбогдоһон хуудаһанууд',
'whatlinkshere-page' => 'Хуудаһан:',

# Block/unblock
'block' => 'Блок',
'change-blocklink' => 'багса хубилгаха',
'contribslink' => 'γйлэ',

# Move page
'revertmove' => 'һэргээхэ',

# Namespace 8 related
'allmessages' => 'Бүхы зурбас',
'allmessagesname' => 'Нэрэ',
'allmessages-language' => 'Хэлэн:',

# Thumbnails
'thumbnail-more' => 'Томоруулха',

# Tooltip help for the actions
'tooltip-pt-login' => 'Бидэ та нэбтэрхые хүсэнэбди; гэбэшье, та заататай байна.',
'tooltip-pt-logout' => 'Гараха',
'tooltip-ca-talk' => 'Агуулгын хуудаһанай хэлэлсэл',
'tooltip-ca-edit' => 'Та энэ хуудаһа заһабарилжа боломжотой. "Уридшалан үзэлхэ" гэһэн тобшые хэрэглээрэй.',
'tooltip-ca-addsection' => 'Шэнэ хэһэг эхилүүлхэ',
'tooltip-ca-history' => 'Энэ хуудаһанай үмэнэхи заһабаринууд',
'tooltip-ca-delete' => 'энэ хуудаһые усадхаха',
'tooltip-search' => '{{SITENAME}} сайтһаа бэдэрхэ',
'tooltip-search-fulltext' => 'Хуудаһанһаа бэдэрхэ бэшэбэри',
'tooltip-p-logo' => 'Нюур хуудаһанда ошохо',
'tooltip-n-mainpage' => 'Нюур хуудаһанда ошохо',
'tooltip-n-mainpage-description' => 'Нюур хуудаһанда ошохо',
'tooltip-n-portal' => 'Түһэл, өөрын оруулалта, туһалбари тухай мэдээлэл',
'tooltip-n-currentevents' => 'Мүнөө боложо байгаа үйлэ ябадал тухай һониниие дуулаха',
'tooltip-n-recentchanges' => 'Тус Викиин һүүлшын хубилалтанууд',
'tooltip-n-randompage' => 'Гэнтын хуудаһые нээхэ',
'tooltip-n-help' => 'Туһалалсалгые олохо газар',
'tooltip-t-whatlinkshere' => 'Эндэ холбогдоһон хуудаһануудай жагсаалта',
'tooltip-t-recentchangeslinked' => 'Энэ хуудаһаһаа холбоогдоһон хуудаһуудай шэнэ хубилалтууд',
'tooltip-feed-atom' => 'Тус хуудаһанай Атом фиид',
'tooltip-t-upload' => 'Файл ашаалха',
'tooltip-t-specialpages' => 'Бүхы тусхай хуудаһанай жагсаалта',
'tooltip-t-print' => 'Энэ хуудаһанай хэблэхэ хубилбари',
'tooltip-ca-nstab-main' => 'Үгүүлэлэй хуудаһые үзэхэ',

# Exif tags
'exif-languagecode' => 'Хэлэн',

# Special:SpecialPages
'specialpages' => 'Тусхай хуудаһан',

);

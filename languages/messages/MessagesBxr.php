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
'tog-justify' => 'Мүр тэгшэлхэ',
'tog-watchcreations' => 'Минии үүсхэһэн хуудаһа болон ашаалһан файлыем хинаха жагсаалтада оруула',
'tog-watchdefault' => 'Минии заһаһан хуудаһа болон файлыем хинаха жагсаалтада оруула',
'tog-watchmoves' => 'Минии зөөһэн хуудаһа болон файлыем хинаха жагсаалтада оруула',
'tog-watchdeletion' => 'Минии усадхаһан хуудаһа болон файлыем хинаха жагсаалтада оруула',
'tog-minordefault' => 'Бүхы заһабариие бага зэргын гэжэ үгэгдэмэлөөр тэмдэглэ',
'tog-previewontop' => 'Уридшалан харахые заһабарилха талбарай урда үзүүлэ',
'tog-previewonfirst' => 'Уридшалан харахые эхилжэ заһаха үедэ үзүүлэ',

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

# Categories related messages
'category_header' => 'Категори "$1" үгүүллүүд',

'cancel' => 'Болихо',
'mytalk' => 'Минии хэлэлсэл',
'navigation' => 'Залуур',

# Vector skin
'vector-action-addsection' => 'Шэнэ хэсэг',
'vector-action-delete' => 'Усадхаха',
'vector-view-create' => 'Үүсхэхэ',
'vector-view-edit' => 'Заһаха',
'vector-view-history' => 'Түүхэ',
'vector-view-view' => 'Уншаха',
'actions' => 'γйлэ',

'navigation-heading' => 'Залуур',
'errorpagetitle' => 'Алдуу',
'help' => 'Туһаламжа',
'search' => 'Бэдэрхэ',
'searchbutton' => 'Бэдэрхэ',
'history_short' => 'Түүхэ',
'edit' => 'Заһаха',
'create' => 'Үүсхэхэ',
'protect' => 'Түһэл',
'protect_change' => 'Хубилалга',
'newpage' => 'Шэнэ хуудаһан',
'talkpage' => 'Тус хуудаһа хэлэлсэхэ',
'talkpagelinktext' => 'Хэлэлсэхэ',
'specialpage' => 'Тусхай хуудаһан',
'personaltools' => 'Хубиин хэрэгсэлнүүд',
'postcomment' => 'Шэнэ бүлэг',
'talk' => 'Хэлэлсэхэ',
'views' => 'Үзэһэн',
'toolbox' => 'Багажын хайрсаг',
'projectpage' => 'Түһэлэй хуудаһан',
'otherlanguages' => 'Бусад хэлээр',
'jumptosearch' => 'бэдэрхэ',
'pool-errorunknown' => 'Танигдаагүй алдуу',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage).
'aboutsite' => '{{SITENAME}} тухай',
'aboutpage' => 'Project:Түһэл тухай',
'currentevents' => 'Мүнөө боложо байгаа үйлэ ябадал',
'currentevents-url' => 'Project:Һонин мэдээн',
'helppage' => 'Help:Агуулга',
'mainpage' => 'Нюур хуудаһан',
'mainpage-description' => 'Нюур хуудаһан',
'portal' => 'Хурал',
'portal-url' => 'Project:Хурал',

'ok' => 'За',
'editsection' => 'заһаха',
'editold' => 'заһаха',
'viewsourcelink' => 'эхэ үүсэбэрииень үзэхэ',
'editsectionhint' => '$1 гэһэн бүлэг заһаха',
'toc' => 'Агуулга',
'red-link-title' => '$1 (хуудаһан үгы байна)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Хуудаһан',
'nstab-special' => 'Тусхай хуудаһан',
'nstab-project' => 'Түһэлэй хуудаһан',
'nstab-image' => 'Файл',
'nstab-mediawiki' => 'Бэшэг',
'nstab-template' => 'Загвар',
'nstab-category' => 'Ангилал',

# Login and logout pages
'login' => 'Орохо',
'nav-login-createaccount' => 'Нэбтэржэ орохо / дансатай болохо',
'userlogin' => 'Нэбтэржэ орохо / дансатай болохо',
'logout' => 'Гараха',
'userlogout' => 'Гараха',
'createaccount' => 'Данса үүсхэхэ',
'gotaccountlink' => 'Нэбтэржэ орохо',
'loginlanguagelabel' => 'Хэлэн: $1',

# Edit pages
'savearticle' => 'Хуудаһа хадагалха',
'showpreview' => 'Уридшалан үзүүлхэ',
'showdiff' => 'Хубилалта харуулха',
'newarticle' => '(Шэнэ)',
'template-protected' => '(хамгаалалтатай)',
'permissionserrorstext-withaction' => 'Та доро тодорхойлһон $1 ушар шалтагаанһаа боложо, $2 эрхэгүйт.',

# Revision deletion
'rev-delundel' => 'харуулха/нюуха',
'revdel-restore' => 'харагдахыень ондоо болгохо',

# Diffs
'editundo' => 'болюулха',

# Search results
'searchmenu-new' => "'''Байгуулха үгүүлэл \"[[:\$1]]\"!'''",
'searchprofile-articles' => 'Үгүүллүүд',
'searchprofile-images-tooltip' => 'Файл бэдэрхэ',
'search-result-size' => '$1 ({{PLURAL:$2|1 word|$2 words}})',

# Preferences page
'mypreferences' => 'Минии тааруулга',
'prefs-datetime' => 'Огноо болон саг',
'youremail' => 'Сахим шуудан:',
'yourrealname' => 'Бодото нэрэ:',
'yourlanguage' => 'Хэлэн:',
'yourgender' => 'Хүйһэн:',
'gender-male' => 'Эрэ',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'Хубилалга энэ үгүүлэл',

# Recent changes
'recentchanges' => 'Һүүлшын хубилалта',
'diff' => 'хубилалга',
'hist' => 'Түүхэ',
'hide' => 'Нюуха',

# Recent changes linked
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
'randompage' => 'Санамсаргүй хуудас',

# Statistics
'statistics' => 'Тоо бүридхэл',

# Miscellaneous special pages
'newpages' => 'Шэнэ үгүүллүүд',
'ancientpages' => 'Хуушарһан хуудаһан',
'move' => 'Нэрэмжэ',

# Special:Log
'log' => 'Логууд',

# Special:AllPages
'allpages' => 'Соохи бүхы хуудаһан',
'allarticles' => 'Үгүүллүүд',

# Special:Categories
'categories' => 'Категори',

# Watchlist
'mywatchlist' => 'Ажаглаха зүйл',
'watch' => 'Хаража байха',

# Undelete
'undeletelink' => 'хараха/һэргээхэ',

# Contributions
'mycontris' => 'Минии оруулһан зүйл',

'sp-contributions-talk' => 'Хэлэлсэл',

# What links here
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
'tooltip-pt-logout' => 'Гараха',
'tooltip-ca-talk' => 'Үгүүлэлынь хэлэлсэл',
'tooltip-ca-addsection' => 'Шэнэ хэсэг',
'tooltip-search' => 'Бэдэрхэ {{SITENAME}}',
'tooltip-search-fulltext' => 'Бэдэрхэ үгүүллүүд',
'tooltip-p-logo' => 'Нюур хуудаһан',
'tooltip-n-mainpage' => 'Нюур хуудаһа руу шэлжэхэ',
'tooltip-n-mainpage-description' => 'Нюур хуудаһа руу шэлжэхэ',
'tooltip-n-recentchanges' => 'Энэ Википеэдийн сайтдахи хубилалтанууд',
'tooltip-feed-atom' => 'Атом',
'tooltip-t-upload' => 'Файл ашаалха',
'tooltip-t-specialpages' => 'Бүхы тусхай хуудаһанай жагсаалта',

# Exif tags
'exif-languagecode' => 'Хэлэн',

# Special:SpecialPages
'specialpages' => 'Тусхай хуудаһан',

);

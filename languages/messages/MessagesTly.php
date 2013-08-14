<?php
/** толышә зывон (толышә зывон)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Erdemaslancan
 * @author Ganbarzada
 * @author Tuzkozbir
 * @author Гусейн
 */

$namespaceNames = array(
	NS_MEDIA            => 'Medja',
	NS_SPECIAL          => 'Xususi',
	NS_TALK             => 'Nopegət',
	NS_USER             => 'Okoədə',
	NS_USER_TALK        => 'Okoədəj_nopegət',
	NS_PROJECT_TALK     => '$1_Nopegət',
	NS_FILE             => 'Fajl',
	NS_FILE_TALK        => 'Fajl_nopegət',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_nopegət',
	NS_TEMPLATE         => 'Numunə',
	NS_TEMPLATE_TALK    => 'Numunə_nopegət',
	NS_HELP             => 'Koməg',
	NS_HELP_TALK        => 'Koməg_nopegət',
	NS_CATEGORY         => 'Tispir',
	NS_CATEGORY_TALK    => 'Tispir_nopegət',
);

$namespaceAliases = array(
	'$1_Nopegətəti'    => NS_PROJECT_TALK,
	'Fajli_nopegət'    => NS_FILE_TALK,
	'Koməgi_nopegət'   => NS_HELP_TALK,
	'Tispiron_nopegət' => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Allpages'                  => array( 'Һәммәј_сәһифон' ),
	'Blankpage'                 => array( 'Тәјлијә_сәһифә' ),
	'ChangeEmail'               => array( 'Е-номә_дәгиш_кардеј' ),
	'ChangePassword'            => array( 'Пароли_дәгиш_кардеј' ),
	'Emailuser'                 => array( 'Бә_иштирокәкә_номә_вығандеј' ),
	'Longpages'                 => array( 'Дырозә_сәһифон' ),
	'Movepage'                  => array( 'Сәһифә_номи_дәгиш_кардеј' ),
	'Mypage'                    => array( 'Чымы_сәһифә' ),
	'Mytalk'                    => array( 'Чымы_мызокирә' ),
	'Myuploads'                 => array( 'Чымы_бо_жә_быә_чијон' ),
	'Newimages'                 => array( 'Нујә_фајлон' ),
	'Newpages'                  => array( 'Нујә_сәһифон' ),
	'PasswordReset'             => array( 'Пароли_ләғв_кардеј' ),
	'Protectedpages'            => array( 'Мыдофијә_кардә_быә_сәһифон' ),
	'Protectedtitles'           => array( 'Мыдофијә_кардә_быә_номон' ),
	'Randompage'                => array( 'Рајрастә_сәһифә._Рајрастә' ),
	'Recentchanges'             => array( 'Ән_нујә_дәгишон' ),
	'Recentchangeslinked'       => array( 'Ангыл_кардә_быә_дәгишон' ),
	'Revisiondelete'            => array( 'Рәдд_кардә_быә_дәгишон' ),
	'Search'                    => array( 'Нәве' ),
	'Shortpages'                => array( 'Кыртә_сәһифон' ),
	'Tags'                      => array( 'Нышонон' ),
	'Undelete'                  => array( 'Бәрпо_кардеј' ),
	'Version'                   => array( 'Рәвојәт' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#ТОЖӘДӘН_ИСТИҒОМӘТ_ДОЈ', '#REDIRECT' ),
	'notoc'                     => array( '0', '__БЕМЫНДӘРИҸОТ__', '__NOTOC__' ),
	'forcetoc'                  => array( '0', '__МӘҸБУРИЈӘ_МЫНДӘРИҸОТ__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__МЫНДӘРИҸОТ__', '__TOC__' ),
	'currentmonth'              => array( '1', 'ЕСӘТНӘ_МАНГ', 'ЕСӘТНӘ_МАНГ_2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'             => array( '1', 'ЕСӘТНӘ_МАНГ_1', 'CURRENTMONTH1' ),
	'currentmonthname'          => array( '1', 'ЕСӘТНӘ_МАНГИ_НОМ', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'       => array( '1', 'ЕСӘТНӘ_МАНГИ_НОМ_ҸИНС', 'CURRENTMONTHNAMEGEN' ),
	'currentday'                => array( '1', 'ЕСӘТНӘ_РУЖ', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'ЕСӘТНӘ_РУЖ_2', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'ЕСӘТНӘ_РУЖИ_НОМ', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'ЕСӘТНӘ_СОР', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'ЕСӘТНӘ_ВАХТ', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'ЕСӘТНӘ_СААТ', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'БУМИНӘ_МАНГ', 'БУМИНӘ_МАНГ_2', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'               => array( '1', 'БУМИНӘ_МАНГ_1', 'LOCALMONTH1' ),
	'localmonthname'            => array( '1', 'БУМИНӘ_МАНГИ_НОМ', 'LOCALMONTHNAME' ),
	'localmonthnamegen'         => array( '1', 'БУМИНӘ_МАНГИ_НОМ_ҸИНС', 'LOCALMONTHNAMEGEN' ),
	'localday'                  => array( '1', 'БУМИНӘ_РУЖ', 'LOCALDAY' ),
	'localday2'                 => array( '1', 'БУМИНӘ_РУЖ_2', 'LOCALDAY2' ),
	'localdayname'              => array( '1', 'БУМИНӘ_РУЖИ_НОМ', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', 'БУМИНӘ_СОР', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'БУМИНӘ_ВАХТ', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'БУМИНӘ_СААТ', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'СӘҺИФОН_ҒӘДӘР', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'МӘҒОЛОН_ҒӘДӘР', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'ФАЈЛОН_ҒӘДӘР', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'ИШТИРОКӘКОН_ҒӘДӘР', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', 'ТИЛИКӘ_ИШТИРОКӘКОН_ҒӘДӘР', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'             => array( '1', 'ДӘГИШОН_ҒӘДӘР', 'NUMBEROFEDITS' ),
	'numberofviews'             => array( '1', 'ДИЈӘ_КАРДЕ_ҒӘДӘР', 'NUMBEROFVIEWS' ),
	'pagename'                  => array( '1', 'СӘҺИФӘ_НОМ', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'СӘҺИФӘ_НОМ_2', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'НОМОН_МӘКОН', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'НОМОН_МӘКОН_2', 'NAMESPACEE' ),
	'namespacenumber'           => array( '1', 'НОМОН_МӘКОН_ҒӘДӘР', 'NAMESPACENUMBER' ),
	'talkspace'                 => array( '1', 'МЫЗОКИРОН_МӘКОН', 'TALKSPACE' ),
	'talkspacee'                => array( '1', 'МЫЗОКИРОН_МӘКОН_2', 'TALKSPACEE' ),
	'subjectspace'              => array( '1', 'МӘҒОЛОН_МӘКОН', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'             => array( '1', 'МӘҒОЛОН_МӘКОН_2', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'              => array( '1', 'СӘҺИФӘ_ПУРӘ_НОМ', 'FULLPAGENAME' ),
	'fullpagenamee'             => array( '1', 'СӘҺИФӘ_ПУРӘ_НОМ_2', 'FULLPAGENAMEE' ),
	'subpagename'               => array( '1', 'ЖИНТОНӘДӘ_СӘҺИФӘ_НОМ', 'SUBPAGENAME' ),
	'subpagenamee'              => array( '1', 'ЖИНТОНӘДӘ_СӘҺИФӘ_НОМ_2', 'SUBPAGENAMEE' ),
	'basepagename'              => array( '1', 'СӘҺИФӘ_НОМИ_ӘСОС', 'BASEPAGENAME' ),
	'basepagenamee'             => array( '1', 'СӘҺИФӘ_НОМИ_ӘСОС_2', 'BASEPAGENAMEE' ),
	'talkpagename'              => array( '1', 'МЫЗОКИРӘ_СӘҺИФӘ_НОМ', 'TALKPAGENAME' ),
	'talkpagenamee'             => array( '1', 'МЫЗОКИРӘ_СӘҺИФӘ_НОМ_2', 'TALKPAGENAMEE' ),
	'subjectpagename'           => array( '1', 'МӘҒОЛӘ_СӘҺИФӘ_НОМ', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'          => array( '1', 'МӘҒОЛӘ_СӘҺИФӘ_НОМ_2', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                       => array( '0', 'ХӘБӘ:', 'MSG:' ),
	'subst'                     => array( '0', 'ӘВӘЗ_КАРДЕ:', 'SUBST:' ),
	'msgnw'                     => array( '0', 'БЕВИКИ_ХӘБӘ:', 'MSGNW:' ),
	'img_thumbnail'             => array( '1', 'миниатјур', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', 'миниатјур=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'росто', 'right' ),
	'img_left'                  => array( '1', 'чәпо', 'left' ),
	'img_none'                  => array( '1', 'бе', 'none' ),
	'img_width'                 => array( '1', '$1пкс', '$1px' ),
	'img_center'                => array( '1', 'мәрәнго', 'center', 'centre' ),
	'img_page'                  => array( '1', 'сәһифә=$1', 'сәһифә_$1', 'page=$1', 'page $1' ),
	'sitename'                  => array( '1', 'САЈТИ_НОМ', 'SITENAME' ),
	'localurl'                  => array( '0', 'БУМИНӘ_УНВОН:', 'LOCALURL:' ),
	'localurle'                 => array( '0', 'БУМИНӘ_УНВОН_2:', 'LOCALURLE:' ),
	'currentweek'               => array( '1', 'ЕСӘТНӘ_ҺАФТӘ', 'CURRENTWEEK' ),
	'currentdow'                => array( '1', 'ЕСӘТНӘ_ҺАФТӘ_РУЖ', 'CURRENTDOW' ),
	'localweek'                 => array( '1', 'БУМИНӘ_ҺАФТӘ', 'LOCALWEEK' ),
	'localdow'                  => array( '1', 'БУМИНӘ_ҺАФТӘ_РУЖ', 'LOCALDOW' ),
	'revisionid'                => array( '1', 'РӘВОЈӘТИ_ID', 'REVISIONID' ),
	'revisionday'               => array( '1', 'РӘВОЈӘТИ_РУЖ', 'REVISIONDAY' ),
	'revisionday2'              => array( '1', 'РӘВОЈӘТИ_РУЖ_2', 'REVISIONDAY2' ),
	'revisionmonth'             => array( '1', 'РӘВОЈӘТИ_МАНГ', 'REVISIONMONTH' ),
	'revisionmonth1'            => array( '1', 'РӘВОЈӘТИ_МАНГ_2', 'REVISIONMONTH1' ),
	'revisionyear'              => array( '1', 'РӘВОЈӘТИ_СОР', 'REVISIONYEAR' ),
	'revisiontimestamp'         => array( '1', 'РӘВОЈӘТИ_ВАХТИ_ҒЕЈД', 'REVISIONTIMESTAMP' ),
	'revisionuser'              => array( '1', 'ИШТИРОКӘКӘ_РӘВОЈӘТ', 'REVISIONUSER' ),
	'fullurl'                   => array( '0', 'ПУРӘ_УНВОН:', 'FULLURL:' ),
	'fullurle'                  => array( '0', 'ПУРӘ_УНВОН_2:', 'FULLURLE:' ),
	'currentversion'            => array( '1', 'ЕСӘТНӘ_РӘВОЈӘТ', 'CURRENTVERSION' ),
	'currenttimestamp'          => array( '1', 'ЕСӘТНӘ_ВАХТИ_ҒЕЈД', 'CURRENTTIMESTAMP' ),
	'localtimestamp'            => array( '1', 'БУМИНӘ_ВАХТИ_ҒЕЈД', 'LOCALTIMESTAMP' ),
	'directionmark'             => array( '1', 'НОМӘ_ИСТИҒОМӘТ', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'                  => array( '0', '#ЗЫВОН:', '#LANGUAGE:' ),
	'contentlanguage'           => array( '1', 'МЫҒДОРИ_ЗЫВОН', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'          => array( '1', 'СӘҺИФОН_БӘ_НОМОН_МӘКОНӘДӘ:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'pagesize'                  => array( '1', 'СӘҺИФӘ_ПАМЈӘ', 'PAGESIZE' ),
	'url_wiki'                  => array( '0', 'ВИКИ', 'WIKI' ),
);

$messages = array(
# User preference toggles
'tog-underline' => 'Линки жинтоно ријә быкәш:',
'tog-justify' => 'Мәтни бә сәһифә кәно бәробәр быкә.',
'tog-hideminor' => 'Охоминә дәгишонәдә гәдә дәгишон нишо мәдә.',
'tog-hidepatrolled' => 'Нујә дәгишон сијоһијәдә дәвинә кардә быә дәгишон нишо мәкә.',
'tog-newpageshidepatrolled' => 'Нијони огәтеј ноғо доә быә сәһифон бә тожә сәһифон сијоһиәдә',
'tog-usenewrc' => 'Охоминә дәгишон сәһифәдә ијән ноғо доә сијоһијәдә дәгишон бә дәстон ҹо кардеј (гәрәке JavaScript)',
'tog-numberheadings' => 'Автоматик башлығон нумрәләмиш быкә',
'tog-showtoc' => 'Мындәриҹоти сијоһи нишо быдә (3 сәрловһәсә веј быә сәһифон)',
'tog-watchcreations' => 'Зијод кардеј чымы офәјә быә сәһифон ијән фајлон бә ноғо доә сијоһи',
'tog-watchdefault' => 'Зијод кардеј демы дәгиш кардә быә сәһифон ијән фајлон бә ноғо доә сијоһи',
'tog-watchmoves' => 'Зијод кардеј фајлон ијән ном дәгиш кардә быә сәһифон бә ноғо доә сијоһи',
'tog-watchdeletion' => 'Зијод кардеј фајлон ијән сәһифон, комон аз рәдд кардәме бә ноғо доә сијоһи',
'tog-enotifwatchlistpages' => 'Ноғо доә сијоһиәдә кејнә сәһифон ја фајлон дәгиш бәбен бәмы е-номә бывығанд',
'tog-watchlisthideown' => 'Чымы дәгишон ноғо доә сијһиәдә нијо кардеј',
'tog-watchlisthidebots' => 'Нијо кардеј ботон дәгишон ноғо доә сијоһиәдә',
'tog-watchlisthideminor' => 'Нијо кардеј гәдә дәгишон ноғо доә сијоһиәдә',

'underline-always' => 'Һежо',

# Dates
'sunday' => 'Ишамбә',
'monday' => 'Дышанбә',
'tuesday' => 'Сешанбә',
'wednesday' => 'Чошанбә',
'thursday' => 'Ҹымә шәв',
'friday' => 'Әjнә',
'saturday' => 'Шанбә',
'sun' => 'Иша',
'mon' => 'Дыш',
'tue' => 'Сеш',
'wed' => 'Чош',
'thu' => 'Ҹым',
'fri' => 'Әјн',
'sat' => 'Шан',
'january' => 'Yanvar',
'february' => 'Fevral',
'march' => 'Mart',
'april' => 'Aprel',
'may_long' => 'May',
'june' => 'İyun',
'july' => 'İyul',
'august' => 'Avqust',
'september' => 'Sentyabr',
'october' => 'Oktyabr',
'november' => 'Noyabr',
'december' => 'Dekabr',
'january-gen' => 'Јанварә манги',
'february-gen' => 'Февралә манги',
'march-gen' => 'Мартә манги',
'april-gen' => 'Апрелә манги',
'may-gen' => 'Мајә манги',
'june-gen' => 'Ијунә манги',
'july-gen' => 'Ијулә манги',
'august-gen' => 'Августә манги',
'september-gen' => 'Сентјабрә манги',
'october-gen' => 'Октјабрә манги',
'november-gen' => 'Нојабрә манги',
'december-gen' => 'Декабрә манги',
'jan' => 'Јан',
'feb' => 'Фев',
'mar' => 'Мар',
'apr' => 'Апр',
'may' => 'Мај',
'jun' => 'Ијун',
'jul' => 'Ијул',
'aug' => 'Авг',
'sep' => 'Сен',
'oct' => 'Окт',
'nov' => 'Ној',
'dec' => 'Дек',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Категоријә|Категоријон}}',
'category_header' => 'Сәһифон бы категоријәдә "$1"',
'subcategories' => 'Категоријон жинтон',
'category-media-header' => 'Медијә бы категоријәдә "$1"',
'category-empty' => "''Ын категоријә бы дәғиғәдә тәјлије.''",
'hidden-categories' => '{{PLURAL:$1|Нијони категоријә|Нијони категоријон}}',
'hidden-category-category' => 'Нијони категоријон',
'category-subcat-count' => '{{PLURAL:$2|Ым категоријә әнчәх жинтонә жинә категоријонку ибарәте.|Ҹәми $2 категоријонку {{PLURAL:$1|жинә категоријә|$1 жинә категоријә}} нишо доә быә.}}',
'category-article-count' => '{{PLURAL:$2|Бы категоријәдә әнҹәх иглә сәһифәје.|Ҹәми $2 сәһифонку нишо доә быә {{PLURAL:$1|сәһифә|$1 сәһифә}} бы категоријәдә.}}',
'category-article-count-limited' => 'Ын категоријәдә {{PLURAL:$1|$1 сәһифә|}} һесте.',
'category-file-count' => '{{PLURAL:$2|Бы категоријәдә әнҹәх иглә фајле.|Ҹәми $2 фајлонку нишо доә быә {{PLURAL:$1|фајл|$1 фајл}} бы категоријәдә.}}',
'category-file-count-limited' => 'Ын категоријәдә  {{PLURAL:$1|$1 фајл}} һесте.',
'listingcontinuesabbrev' => '(дәвом)',
'index-category' => 'Индекс быә сәһифон.',
'noindex-category' => 'İndeks nibıə səhifon',
'broken-file-category' => 'Сәһифон де ко ныкардә фајлинә сәбонон',

'about' => 'Тәсвир',
'article' => 'Мәғолә',
'newwindow' => '(нујә пенҹәдә окардеј)',
'cancel' => 'Ләғв кардеј',
'moredotdotdot' => 'Веј...',
'mypage' => 'Сәһифә',
'mytalk' => 'Мызокирон',
'anontalk' => 'Бо ын IP-унвони мызокирә',
'navigation' => 'Navigasiyə',
'and' => '&#32;ијән',

# Cologne Blue skin
'qbfind' => 'Нәве',
'qbbrowse' => 'Дијә кардеј',
'qbedit' => 'Сәрост кардеј',
'qbpageoptions' => 'Ым сәһифә',
'qbmyoptions' => 'Чымы сәһифон',
'qbspecialpages' => 'Хысусијә сәһифон',
'faq' => 'РАП',
'faqpage' => 'Project:РАП',

# Vector skin
'vector-action-addsection' => 'Мывзу зијод кардеј',
'vector-action-delete' => 'Рәдд кардеј',
'vector-action-move' => 'Номи дәгиш кардеј',
'vector-action-protect' => 'Мыдофијә кардеј',
'vector-action-undelete' => 'Бәрпо кардеј',
'vector-action-unprotect' => 'Мыдофијә дәгиш кардеј',
'vector-view-create' => 'Офәјеј',
'vector-view-edit' => 'Сәрост кардеј',
'vector-view-history' => 'Тарых',
'vector-view-view' => 'Һандемон',
'vector-view-viewsource' => 'Дијә кардеј',
'actions' => 'Һәрәкәтон',
'namespaces' => 'Номон мәконон',
'variants' => 'Вариантон',

'errorpagetitle' => 'Сәһв',
'returnto' => 'Бә сәһифә огәрдеј $1.',
'tagline' => 'Материал че {{SITENAME}}',
'help' => 'Арајиш',
'search' => 'Nəve',
'searchbutton' => 'Нәве',
'go' => 'Давардеј',
'searcharticle' => 'Давардеј',
'history' => 'Сәһифә тарых',
'history_short' => 'Тарых',
'printableversion' => 'Чап кардејро рәвојәт',
'permalink' => 'Еғрорә сәбон',
'print' => 'Чап',
'view' => 'Тәмшо кардеј',
'edit' => 'Сәрост кардеј',
'create' => 'Офәјеј',
'editthispage' => 'Ым сәһифә сәрост кардеј',
'create-this-page' => 'Ым сәһифә офәјеј',
'delete' => 'Рәдд кардеј',
'deletethispage' => 'Ым сәһифә рәдд кәрдеј',
'undelete_short' => 'Бәрпо кардеј $1 {{PLURAL:$1|дәгиши|дәгишон|}}',
'viewdeleted_short' => 'Дијә карде {{PLURAL:$1|иглә рәдд кардә быә дәгиши|$1 рәдд кардә быә дәгишон}}',
'protect' => 'Мыдофијә кардеј',
'protect_change' => 'дәгиш кардеј',
'protectthispage' => 'Ым сәһифә мыдофијә кардеј',
'unprotect' => 'Мыдофијә дәгиш кардеј',
'unprotectthispage' => 'Ын сәһифә мыдофијә дәгиш кардеј',
'newpage' => 'Тожә сәһифә',
'talkpage' => 'Ым сәһифә мызокирә кардеј',
'talkpagelinktext' => 'Mızokirə',
'specialpage' => 'Хысусијә сәһифә',
'personaltools' => 'Шәхси диләгон',
'postcomment' => 'Нујә ғысм',
'articlepage' => 'Мәғолә дијә кардеј',
'talk' => 'Mızokirə',
'views' => 'Тәмшо кардеј',
'toolbox' => 'Диләгон',
'userpage' => 'Иштирокәкә сәһифә дијә кардеј',
'projectpage' => 'Нәхши сәһифә дијә кардеј',
'imagepage' => 'Фајли сәһифә дијә кардеј',
'mediawikipage' => 'Мәктуби сәһифә нишо быдә.',
'templatepage' => 'Ғәлиби сәһифә нишо быдә.',
'viewhelppage' => 'Араијш сәј',
'categorypage' => 'Категоријон сәһифә дијә кардеј',
'viewtalkpage' => 'Мызокирә дијә кардеј',
'otherlanguages' => 'Ҹо зывононәдә',
'redirectedfrom' => '($1 чыјо унвон дәгиш кардә быә)',
'redirectpagesub' => 'Увони дәгиш кардә сәһифәје',
'lastmodifiedat' => 'Ын сәһифә охонә кәрә дәгиш беј: $2, $1.',
'protectedpage' => 'Мыдофијә кардә быә сәһифә',
'jumpto' => 'Дәвардеј бә:',
'jumptonavigation' => 'навигасијә',
'jumptosearch' => 'нәве',
'pool-timeout' => 'Че блоки чәш кардә вахт сәбе.',
'pool-errorunknown' => 'Номәлумә сәһв',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage).
'aboutsite' => 'Təsvir {{SITENAME}}',
'aboutpage' => 'Project: Тәсвир',
'copyrightpage' => '{{ns:project}}:Мыәллифә һуғуғ',
'currentevents' => 'Есәтнә һодисон',
'currentevents-url' => 'Project: Есәтнә һодисон',
'disclaimers' => 'Çe məsuliyyətiku imtino.',
'disclaimerpage' => 'Project:Дејни бә гиј ныгәтеј',
'edithelp' => 'Арајиш бо редактә кардеј',
'helppage' => 'Help:Мындәриҹот',
'mainpage' => 'Əsosə səhifə',
'mainpage-description' => 'Әсосә сәһифә',
'policy-url' => 'Project:Ғајдон',
'portal' => 'Ҹәмјәт',
'portal-url' => 'Project:Ҹәмјәти портал',
'privacy' => 'Məxfiyəti siyosət',
'privacypage' => 'Project:Мәхфијәти сијосәт',

'badaccess' => 'Дастрәси ғәләт',
'badaccess-group0' => 'Ын фәалијјәти ичра карде әзынишон.',

'ok' => 'OK',
'retrievedfrom' => 'Сәвон "$1"',
'youhavenewmessages' => 'Шымә сәјоне $1 ($2).',
'newmessageslink' => 'нујә хәбон',
'newmessagesdifflink' => 'охонә дәгиши',
'newmessagesdifflinkplural' => '$1 {{PLURAL:$1|охонә дәгиши|охонә дәгишон}}',
'editsection' => 'Sərost kardey',
'editold' => 'Сәрост кардеј',
'viewsourceold' => 'бешемонә коди дијә кардеј',
'editlink' => 'Сәрост кардеј',
'viewsourcelink' => 'Бешемонә коди дијә кардеј',
'editsectionhint' => 'Im semonə sərost karde: $1',
'toc' => 'Мындәриҹот',
'showtoc' => 'нишо дој',
'hidetoc' => 'нијо кардеј',
'collapsible-collapse' => 'Бурмә кардеј',
'collapsible-expand' => 'Һовуж кардеј',
'thisisdeleted' => 'Дијә кардеј јаанки бәрпо кардеј $1?',
'viewdeleted' => 'Дијә кардеј $1?',
'restorelink' => '{{PLURAL:$1|иглә рәдд кардә быә дәгиши|$1 рәдд кардә быә дәгишон}}',
'site-atom-feed' => '$1 Atom лента',
'page-atom-feed' => '"$1" Atom лента',
'red-link-title' => '$1 (jıqo səhifə ni)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Məğolə',
'nstab-user' => 'Иштирокәкә сәһифә',
'nstab-media' => 'Медијә сәһифә',
'nstab-special' => 'Хысусијә сәһифә',
'nstab-project' => 'Нахшә бәрәдә',
'nstab-image' => 'Фајл',
'nstab-template' => 'Ғәлиб',
'nstab-category' => 'Тиспир',

# General errors
'error' => 'Сәһв',
'readonly' => 'Бә база нывыште блок быә.',
'missing-article' => 'База мәлумотон дыләдә бә ахтар кардә быә саһифон «$1» $2 барәдә мәлумот пәјдо карде ныбе.
Жыго вәзијјәт бе бәзне бә вахтики, ым сәһифә че рәдд кардә быә сәһифә канә рәвојәте.
Гирәм ым жыго ни, жәгәдә шымә програм тәминатијәдә сәһв пәјдо кардәјоне.
Хаһиш кардәмон че сәһифә URL-и бә [[Special:ListUsers/sysop|администратори]] бывғандәнән.',
'missingarticle-rev' => '(рәвојәт#: $1)',
'missingarticle-diff' => '(Фәрг: $1, $2)',
'internalerror' => 'Дыләтонә ғәләт',
'internalerror_info' => 'Дыләтонә ғәләт: $1',
'fileappenderrorread' => 'Әловон ғејд карде быәдә"$1" һанде ныбе.',
'formerror' => 'Сәһв: Че формә мәлумотон әкс карде ғерри мымкуне.',
'cannotdelete-title' => 'Сәһифә әбыни рәдд кардеј "$1"',
'badtitle' => 'Роныдоә ном',
'badtitletext' => 'Ахтар кардә быә сәһифә ном сәһве, тәјлије, јаанки сәрост доә быәнин мијонзывонон ја мијонвики номон.
Бе бәзне ки кали рәмзон сәрловһәдә око дој әбыни.',
'viewsource' => 'Дијә кардеј',
'exception-nologin' => 'Ыштәни едаштәнијоне',

# Login and logout pages
'yourname' => 'Иштирокәкә ном:',
'yourpassword' => 'Парол:',
'yourpasswordagain' => 'Пароли сәнибәтон гырдә карде:',
'remembermypassword' => 'Мыни ым компутерәдә јодәдә огәт (максимум $1 {{PLURAL:$1|руж|руж}})',
'login' => 'Ыштәни едаштеј',
'nav-login-createaccount' => 'Ыштәни едаштеј / ыштәни ғејд кардовнијеј',
'loginprompt' => '{{SITENAME}}-әдә ыштәни едәште горнә, шымә бәбе бә «cookies» иҹозә быдән.',
'userlogin' => 'Ыштәни едаштеј / ыштәни ғејд кардовнијеј',
'userloginnocreate' => 'Ыштәни едаштеј',
'logout' => 'Системәдә кој орохнијеј',
'userlogout' => 'Системәдә кој орохнијеј',
'notloggedin' => 'Ыштәни едаштәнијоне',
'nologin' => "Иштирок кардәкәси сәһифә ни? '''$1'''.",
'nologinlink' => 'Иштирокәкә сәһифә офәје',
'createaccount' => 'Нујә иштирокәкә ғејд кардеј',
'gotaccount' => 'Шымә ыштәни ғејд кардәјоне? $1.',
'gotaccountlink' => 'Ыштәни едаштеј',
'userlogin-resetlink' => 'Бә системә дәше мәлумоти јодәдә бекардәјоне?',
'createaccountmail' => 'бә е-номә',
'createaccountreason' => 'Сәбәб:',
'mailmypassword' => 'Нујә парол вығандеј бә Е-номә.',
'loginlanguagelabel' => 'Зывон: $1',

# Change password dialog
'resetpass' => 'Пароли дәгиш карде',
'resetpass_header' => 'Иштирокәкә пароли дәгиш карде',
'oldpassword' => 'Канә парол:',
'newpassword' => 'Нујә парол:',
'resetpass_forbidden' => 'Парол әзыни дәгиш бе',
'resetpass-submit-loggedin' => 'Пароли дәгиш кардеј',
'resetpass-submit-cancel' => 'Ләғв кардеј',

# Special:PasswordReset
'passwordreset-username' => 'Иштирокәкә ном:',
'passwordreset-email' => 'Е-номә унвон:',

# Special:ChangeEmail
'changeemail' => 'Е-номә унвони дәгиш кардеј',
'changeemail-newemail' => 'Е-номә тожә унвон:',
'changeemail-none' => '(ни)',
'changeemail-submit' => 'Е-номә дәгиш кардеј',
'changeemail-cancel' => 'Ләғв карде',

# Edit page toolbar
'bold_sample' => 'Нимәтындә шрифт',
'bold_tip' => 'Нимәтындә шрифт',
'italic_sample' => 'Курсивә мәтн',
'italic_tip' => 'Курсивә мәтн',
'link_sample' => 'Сәбони сәрловһә',
'link_tip' => 'Дыләтонә сәбон',
'extlink_sample' => 'http://www.example.com сәбони сәрловһә',
'extlink_tip' => 'Хариҹә сәбон (сыханәсә http:// јодәдә огәтән)',
'headline_sample' => 'Сәрловһә мәтн',
'headline_tip' => '2-нә сәвијјә сәрловһә',
'nowiki_sample' => 'Формат кардә ныбә мәтн дәғандән ијо',
'nowiki_tip' => 'Вики формат кардеј бә нәзә ныстәнеј',
'image_tip' => 'Дахыл кардә быә фајл',
'media_tip' => 'Сәбон бә медијә-фајл',
'sig_tip' => 'Шымә ғол ијән вахт',
'hr_tip' => 'Уфуғијә ријә (рә-рә истифодә мәкән)',

# Edit pages
'summary' => 'Дәгишон тәсвир:',
'subject' => 'Мывзу/сәрловһә:',
'minoredit' => 'Ым гадә дәгишије',
'watchthis' => 'Ым сәһифә тәмшо кардеј',
'savearticle' => 'Сәһифә огәтеј',
'preview' => 'Сыфтәнә нишо дој',
'showpreview' => 'Сыфтәнә нишо дој',
'showlivepreview' => 'Товинә сыфтәнә нишо дој',
'showdiff' => 'Дәғандә быә дәгишон',
'anoneditwarning' => "'''Дығғәт.''' Шымә ыштәни едәштәнијоне системәдә.
Шымә IP-унвон бә ым сәһифә дәгишон тарых ғејд бәбе.",
'loginreqlink' => 'ыштәни едаштеј',
'newarticle' => '(Нујә)',
'newarticletext' => 'Шымә давардијон де сәбони бә сәһифә, әмма жыго сәһифә ни.
Бо сәһифә офәјеј мәтн бынывыштән жиннә пенҹәдә (мыффәссәл дијә быкән [[{{MediaWiki:Helppage}}|араијшә сәһифә]]).
Гирәм шымә ијо де сәһви бешијон, һиччекәни ыштә браузери "думо" егәтән.',
'noarticletext' => "Есәт бы сәһифәдә мәтн ни.
Шымә бәзынешон [[Special:Search/{{PAGENAME}}|пәјдо кардеј конҹо ым ном һесте]] бә ҹо мәғолонәдә,
<span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} журналон ујғунә нывыштәјон пәјдо кардеј],
јаанки '''[{{fullurl:{{FULLPAGENAME}}|action=edit}}де жыго номи сәһифә офәјеј ]'''</span>.",
'noarticletext-nopermission' => 'Есәт бы сәһифәдә мәтн ни. 
Шымә бәзынејон [[Special:Search/{{PAGENAME}}|пәјдо кардеј конҹо ым ном һесте]] бә ҹо мәғолонәдә,
јаанки <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} журналон ујғунә нывыштәјон пәјдо кардеј].</span>, интаси шымәку изн ни ым сәһифә офәје.',
'previewnote' => "'''Јодәдә огәтән ки ым һәлә сыфтәнә нишо доје.'''
Шымә дәгишон һәлә огәтә быәнин!",
'editing' => 'Редәктә кардеј $1',
'editingsection' => 'Редактә кардеј $1 (ғысм)',
'yourtext' => 'Шымә мәтн',
'templatesused' => '{{PLURAL:$1|Ғәлиб:|Ғәлибон}} есәтнә сәһифә истифодә кардејдә:',
'template-protected' => '(Мыдофиә кардә быә)',
'template-semiprotected' => 'tiki muhafizə bıə',
'hiddencategories' => 'Ын сәһифә аидијотыш һесте бә {{PLURAL:$1|1 нијони категоријә|$1 нијони категоријон}}:',
'permissionserrorstext-withaction' => "Шымәку ни иҹозә ба ым һәрәкәти «'''$2'''», бә жыго {{PLURAL:$1|сәбәби|сәбәбон}} горнә:",
'recreate-moveddeleted-warn' => "''Дыггәт! Шымә нафко позулмуш быә сәһифон бәрпа кардеон пидә.'''

Ым сәһифә чоәдәнә дуз карде зәруријјәти јохләмишкәнән.
Жинтоно нышу доә быә бычи ым сәһифә позулмуш быә.",
'moveddeleted-notice' => 'Ым сәһифә молә быә.
Арајиши горнә жинтоно нишо доә быән че сәһифә молә ијән ном дәгиш кардә нывыштәјон.',

# Parser/template warnings
'post-expand-template-inclusion-warning' => "'''Дығғәт:''' Дахыл кардә быә ғәлибон сәкыштә памјә ве јоле.
Хәјли ғәлибон дахыл ныбабен.",
'post-expand-template-inclusion-category' => 'Бо сәһифон дахыл кардә быә ғәлибон рәво зынә быә улгу бә кәно бешә',
'post-expand-template-argument-warning' => "'''Дығғәт:''' Ым сәһифәдә ән ками иглә аргумент һесте, ә аргумент ки һәддиндән зијодә памјә һестыше бо окарде.
Жыго аргументон вадоә быән.",
'post-expand-template-argument-category' => 'Вадо быә ғәлибон аргументон огәтә сәһифон',

# History pages
'viewpagelogs' => 'Бо ым сәһифә журналон нишо дој',
'currentrev-asof' => 'Есәтнә рәвојәт бә $1',
'revisionasof' => 'Рәвојәт $1',
'revision-info' => 'Рәвојәти мәлумот: $1; $2',
'previousrevision' => '← Навынәни',
'nextrevision' => 'Думотоно шә →',
'currentrevisionlink' => 'Есәтнә рәвојәт',
'cur' => 'есәт.',
'next' => 'думотоно шә',
'last' => 'навы.',
'page_first' => 'иминә',
'page_last' => 'охонә',
'histlegend' => "Рәвојәтон выжнијеј: кон сәһифә рәвојәтон фәрғи виндеј пидәјоне нышон быжәнән ијән егәтән \"Enter\".<br />
Изоһ: '''(исә)''' = де исәтнә рәвојәти фәрғ, '''(охо)''' = де навконә рәвојәти фәрғ, '''(г)''' = гәдә дәгиш.",
'history-fieldset-title' => 'Тарыхи дијә кардеј',
'history-show-deleted' => 'Әнҹәх рәдд кардә быән',
'histfirst' => 'Ән канә',
'histlast' => 'Охонәни',
'historyempty' => '(тәјли)',

# Revision feed
'history-feed-title' => 'Дәгишон тарых',
'history-feed-description' => 'Ым сәһифә дәгишон тарых викиәдә',
'history-feed-item-nocomment' => '$1 бә $2-дә',

# Revision deletion
'rev-delundel' => 'нишо дој/нијо кардеј',
'rev-showdeleted' => 'нишо дој',
'revdelete-show-file-submit' => 'Бәле',
'revdelete-radio-set' => 'Бәле',
'revdelete-radio-unset' => 'Не',
'revdelete-log' => 'Сәбәб:',
'revdel-restore' => 'Винде дәрәҹә дәгиш карде',
'revdel-restore-deleted' => 'Рәдд кардә быә рәвојәтон',
'revdel-restore-visible' => 'Чијә рәвојәтон',
'pagehist' => 'Сәһифә тарых',
'revdelete-reasonotherlist' => 'Ҹо сәбәб',

# History merging
'mergehistory-reason' => 'Сәбәб:',

# Merge log
'revertmerge' => 'Бахш кардеј',

# Diffs
'history-title' => '$1: Дәгишон тарых',
'lineno' => 'Сәтыр $1:',
'compareselectedversions' => 'Сәчын кардә быә рәвојәтон мығојисә кардеј.',
'editundo' => 'ләғв кардеј',
'diff-multi' => '({{PLURAL:$2|Иглә истифадәчи|$2 истифадәчи}} тәрәфәдә кардә быә {{PLURAL:$1|иглә арә редактә|$1 арә редактә}} нушо додәни)',

# Search results
'searchresults' => 'Нәве нәтиҹон',
'searchresults-title' => 'Нәве «$1»',
'prevn' => 'навынәни {{PLURAL:$1|$1}}',
'nextn' => 'думотоно шә {{PLURAL:$1|$1}}',
'prevn-title' => 'Навынәни $1 {{PLURAL:$1|нывыштәј|нывыштәјон}}',
'nextn-title' => 'Думотоно шә $1 {{PLURAL:$1|нывыштәј|нывыштәјон}}',
'shown-title' => 'Нишо дој $1 {{PLURAL:$1|нывыштәј|нывыштәјон}} сәһифәдә',
'viewprevnext' => 'Дијә кардеј ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend' => 'Бо нәве кукон',
'searchmenu-exists' => "'''Бы вики-нәхшәдә һесте сәһифә «[[:$1]]»'''",
'searchmenu-new' => "'''Сәһифә офәјеј «[[:$1]]» бә ым вики-нахшәдә!'''",
'searchprofile-articles' => 'Әсосә сәһифон',
'searchprofile-project' => 'Че араијшон ијән нахшон сәһифон',
'searchprofile-images' => 'Мултимедијә',
'searchprofile-everything' => 'Һар вырәдә',
'searchprofile-advanced' => 'һовуж',
'searchprofile-articles-tooltip' => 'Нәве бә $1',
'searchprofile-project-tooltip' => 'Нәве бә $1',
'searchprofile-images-tooltip' => 'Фајлон нәве',
'searchprofile-everything-tooltip' => 'Һәммәј сәһифонәдә нәве (мызокирә сәһифонәдән)',
'searchprofile-advanced-tooltip' => 'Бә асбардә быә номон мәкононәдә нәве',
'search-result-size' => '$1 ({{PLURAL:$2|1 sıxan|$2 sıxanon}})',
'search-result-category-size' => '{{PLURAL:$1|$1 елемент|$1 елементон}} ({{PLURAL:$2|$2 жинә категоријә$2 жинә категоријон }}, {{PLURAL:$3|$3 фајл|$3 фајлон}})',
'search-redirect' => '(Унвони дәгиш кардеј  $1)',
'search-section' => '(семонә $1)',
'search-suggest' => 'Еһтимол шымә нәзәрәдә ым гәтејдәбијон: $1',
'search-interwiki-more' => '(һәнијән)',
'searchrelated' => 'ангыл кардә быә',
'searchall' => 'Һәммәј',
'showingresultsheader' => "{{PLURAL:$5|Нәтиҹә'''$1''' из '''$3'''|Нәтиҹон '''$1 — $2''' че '''$3'''}} бо '''$4'''",
'search-nonefound' => 'Бә шымә хәбәсә ујғун омә сәкыштә пәјдо ныбе.',
'powersearch-field' => 'Нәве',
'powersearch-toggleall' => 'Һәммәј',

# Preferences page
'preferences' => 'Кукон',
'mypreferences' => 'Кукон',
'prefsnologin' => 'Ыштәни едаштәнијоне',
'prefsnologintext' => 'Шымә бәбе <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} ыштәни едәштән]</span> бо иштирокәкә пеғәндон дәгиш кардеј.',
'changepassword' => 'Пароли дәгиш кардеј',
'skin-preview' => 'Сыфтәнә нишо дој',
'prefs-user-pages' => 'Иштирокәкә сәһифон',
'prefs-rc' => 'Ән нујә дәгишон',
'prefs-changeemail' => 'Е-номә дәгиш кардеј',
'prefs-email' => 'Е-номә кукон',
'saveprefs' => 'Огәтеј',
'rows' => 'Сәтырон:',
'searchresultshead' => 'Нәве',
'timezoneregion-america' => 'Америка',
'timezoneregion-europe' => 'Авропа',
'prefs-namespaces' => 'Номон мәконон',
'prefs-files' => 'Фајлон',
'prefs-custom-css' => 'Хысуси CSS',
'youremail' => 'E-номә:',
'username' => '{{GENDER:$1|Иштирокәкә ном}}:',
'uid' => '{{GENDER:$1|Иштирокәкә}} ID:',
'yourrealname' => 'Шымә әсыл ном:',
'yourlanguage' => 'Зывон:',
'email' => 'E-номә',
'prefs-help-email' => 'Е-номә унвони нывыштеј һукман ни, интаси ав бә шымә гәрәк бәбе гирам шымә пароли виро бебәкардејон.',
'prefs-help-email-others' => 'Комәг бәка бә ҹо иштироәкон шымә е-номә унвони оныкарде, че шымә шәхси сәһифәдә быә линки де шымә әлогә огәтеј.',

# User rights
'userrights-reason' => 'Сәбәб:',

# Groups
'group-user' => 'Иштирокәкон',

# Special:Log/newusers
'newuserlogpage' => 'Иштирокәкон ғеидијоти журнал',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'Ым сәһифә сәрост кардеј',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|дәгиши|дәгишон}}',
'recentchanges' => 'Ән нујә дәгишон',
'recentchanges-legend' => 'Ән нујә дәгишон кукон',
'recentchanges-summary' => 'Тәмшо быкән бы сәһифәдә че вики охоминә дәгишон нишо доә быән.',
'recentchanges-feed-description' => 'Ым каналәдә быә охонә дәгишон дығғәтәдә огәт.',
'recentchanges-label-newpage' => 'Де ым дәгиши тожә сәһифә сохтә бе',
'recentchanges-label-minor' => 'Ым гадә дәгишије',
'recentchanges-label-bot' => 'Ым дәгиши бот кардәше',
'recentchanges-label-unpatrolled' => 'Im redaktə hələ nəzərədə dəvardəni',
'rcnote' => "Бә жиј нишо доә быә {{PLURAL:$1|'''1''' дәгиши|'''$1''' дәгиши}}, бә охонә {{PLURAL:$2|ружәдә|'''$2''' ружәдә}}, саат $5, $4.",
'rcnotefrom' => "Бә жиј доә быән дәгишон че вахтику '''$2''' (тосә '''$1''').",
'rclistfrom' => '$1 вахтику дәгишон нишо быдә',
'rcshowhideminor' => '$1 гәдәлијә дәгишон',
'rcshowhidebots' => '$1 ботон',
'rcshowhideliu' => '$1 ыштәни едаштә иштирокәкон',
'rcshowhideanons' => '$1 әнәномә иштирокәкон',
'rcshowhidepatr' => '$1 осә кардә быә дәгишон',
'rcshowhidemine' => '$1 ыштә дәгишон',
'rclinks' => 'Нишо дој охонә $1 дәгишон бә охонәни $2 ружон<br />$3',
'diff' => 'фәрғ.',
'hist' => 'тарых',
'hide' => 'Нијо кардеј',
'show' => 'Нишо дој',
'minoreditletter' => 'г',
'newpageletter' => 'Т',
'boteditletter' => 'б',
'rc_categories_any' => 'Һар гылә',
'newsectionsummary' => '/* $1 */ нујә мывзу',
'rc-enhanced-expand' => 'Тәфсилотон нишо дој (JavaScript истифодә бедә)',
'rc-enhanced-hide' => 'Тәфсилотон нијо кардеј',

# Recent changes linked
'recentchangeslinked' => 'Ангыл кардә быә дәгишон',
'recentchangeslinked-toolbox' => 'Ангыл кардә быә дәгишон',
'recentchangeslinked-title' => 'Ангыл кардә быә дәгишон бо "$1"',
'recentchangeslinked-summary' => "Бә ым сәһифонәдә охонә дәгишон сијоһије, бә кон сәһифон сәбон вардә ын сәһифә (јаанки дахыл кардә быән бә нишо доә быә категоријә).
[[Special:Watchlist|Шымә ноғо доә сијоһиәдә]] быә сәһифон, де '''ғалинә''' шрифти нишо доә быән.",
'recentchangeslinked-page' => 'Сәһифә ном:',
'recentchangeslinked-to' => 'Бә нишо доә быә сәһифә сәбон вардә сәһифонәдә дәгишон нишо дој',

# Upload
'upload' => 'Фајли бо жәј',
'uploadlogpage' => 'Бо кардә быә чијон журнал',
'filedesc' => 'Кыртә тәсвир',
'uploadedimage' => 'бо жәше "[[$1]]"',

'license' => 'Лисензијә:',
'license-header' => 'Лисензијә',

# Special:ListFiles
'imgfile' => 'фајл',
'listfiles' => 'Фајлон сијоһи',
'listfiles_thumb' => 'Гәдә шикил',
'listfiles_name' => 'Фајли ном',
'listfiles_user' => 'Иштирокәкә',
'listfiles_size' => 'Улгу',
'listfiles_description' => 'Тәсвир',
'listfiles_count' => 'Рәвојәт',

# File description page
'file-anchor-link' => 'Фајл',
'filehist' => 'Фајли тарых',
'filehist-help' => 'Фајли сыфтә рәвојәти виндеј горә бә тарых/вахт егәтән.',
'filehist-deleteall' => 'һәммәј рәдд кардеј',
'filehist-revert' => 'Огард',
'filehist-current' => 'есәтнә',
'filehist-datetime' => 'Тарых/Вахт',
'filehist-thumb' => 'Гәдә шикил',
'filehist-thumbtext' => 'Миниатјур бо рәвојәти че вахтику $1',
'filehist-user' => 'Иштирокәкә',
'filehist-dimensions' => 'Објекти улгу',
'filehist-comment' => 'Ғејд',
'imagelinks' => 'Фајли око доје',
'linkstoimage' => '{{PLURAL:$1|сәһифә|$1 сәһифә}} сәбон вардә бә ын фајл:',
'nolinkstoimage' => 'Бә ым фајли сәбон вардә сәһифон нин.',
'sharedupload-desc-here' => 'Ым фајл чыјо пегәтә быә $1 ијән бәзыне истифодә бе бә ҹо нәхшонәдә.
Мәлумот чн әчәј [$2 тәсвири сәһифәку] бә жиј доә быә.',

# File deletion
'filedelete-comment' => 'Сәбәб:',
'filedelete-submit' => 'Рәдд кардеј',

# Random page
'randompage' => 'Рајрастә мәғолә',

# Statistics
'statistics' => 'Статистика',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|бајт|бајтон}}',
'nmembers' => '$1 {{PLURAL:$1|узв|узвон}}',
'prefixindex' => 'Һәммәј сәһифон де префикси',
'usercreated' => '{{GENDER:$3|Офәјеј быә}} $1 $2',
'newpages' => 'Тожә сәһифон',
'move' => 'Ном дәгиш кардеј',
'pager-newer-n' => '{{PLURAL:$1|ән нујә1|ән нујә $1}}',
'pager-older-n' => '{{PLURAL:$1|1 тикиән канә|$1 ән канә}}',

# Book sources
'booksources' => 'Китобон сәвонон',
'booksources-search-legend' => 'Китоби барәдә мәлумоти нәве',
'booksources-go' => 'Нәве',

# Special:Log
'log' => 'Журналон',

# Special:AllPages
'allpages' => 'Һәммәј сәһифон',
'alphaindexline' => 'че $1 тоса $2',
'allarticles' => 'Һәммәј сәһифон',
'allpagessubmit' => 'Бә вырә роснијеј',

# Special:Categories
'categories' => 'Категоријон',

# Special:LinkSearch
'linksearch-line' => '$2-ку сәбон вардә бә $1',

# Special:ListGroupRights
'listgrouprights-members' => '(иштирокәкон сијоһи)',

# Email user
'emailuser' => 'Номә бә иштирокәкә',

# Watchlist
'watchlist' => 'Ноғо доә сијоһи',
'mywatchlist' => 'Чәшәвәно кардә сијоһи',
'watchlistfor2' => 'Бо $1 $2',
'watch' => 'Думотоно егыниеј',
'unwatch' => 'Думотоно ныегыниеј',
'watchlist-details' => 'Мызокирә сәһифон ныашмардеј, шымә ноғо доә сијоһиәдә {{PLURAL:$1|$1 сәһифәје|$1 сәһифәје}}.',
'wlshowlast' => 'Нишо дој бә охонә $1 саат $2 руж $3',
'watchlist-options' => 'Ноғо доә сијоһи пеғандон',

# Delete
'actioncomplete' => 'Һәрәкәт иҹро кардә быә',
'actionfailed' => 'Һәрәкәт иҹро кардә бәни',
'dellogpage' => 'Рәдд кардә быә чијон журнал',
'deletecomment' => 'Сәбәб:',

# Rollback
'rollbacklink' => 'Окырнијеј',

# Protect
'protectlogpage' => 'Мыдофијә журнал',
'protectedarticle' => 'мыдофијә быә "[[$1]]"',
'protectcomment' => 'Сәбәб:',

# Undelete
'undeletelink' => 'чәшику дәвонијеј/бәрпо кардеј',
'undeleteviewlink' => 'тәмшо кардеј',
'undeletecomment' => 'Сәбәб:',
'undelete-search-submit' => 'Нәве',
'undelete-show-file-submit' => 'Бәле',

# Namespace form on various pages
'namespace' => 'Номон мәкон:',
'invert' => 'Выжнијә быәгылон дәгишкә',
'blanknamespace' => '(Әсос)',

# Contributions
'contributions' => '{{GENDER:$1|Иштирокәкә}} гәнҹ',
'contributions-title' => 'Иштирокәкә гәнҹ $1',
'mycontris' => 'Гәнҹ',
'contribsub2' => 'Гәнҹ $1 ($2)',
'uctop' => '(есәтнә)',
'month' => 'Че мангику (һәнијән рә):',
'year' => 'Че сорику (һәнијән рә):',

'sp-contributions-newbies' => 'Әнҹәх нујә иштирокәкон гәнҹи нишо дој',
'sp-contributions-blocklog' => 'бастә быә чијон',
'sp-contributions-uploads' => 'бо жә быә чијон',
'sp-contributions-logs' => 'журналон',
'sp-contributions-talk' => 'мызокирә',
'sp-contributions-search' => 'Гәнҹи нәве',
'sp-contributions-username' => 'IP-унвон јаанки иштироәкә ном:',
'sp-contributions-toponly' => 'Нишо дој дәгишон, ком гылә ән охонә рәвојәтонин',
'sp-contributions-submit' => 'Нәве',

# What links here
'whatlinkshere' => 'Сәбонон ијо',
'whatlinkshere-title' => 'Сәһифон, сәбон вардән бә "$1"',
'whatlinkshere-page' => 'Сәһифә:',
'linkshere' => "Ым сәһифон сәбон вардән ијо ''[[:$1]]''':",
'nolinkshere' => "Бә ым сәһифә ҹо сәһифонку сәбонон нин '''[[:$1]]'''.",
'isredirect' => 'унвони дәгиш кардә сәһифәје',
'istemplate' => 'әловә',
'isimage' => 'фајлинә сәбон',
'whatlinkshere-prev' => '{{PLURAL:$1|навынәни|навынәни $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|думотоно шә|думотоно шә $1}}',
'whatlinkshere-links' => '← сәбонон',
'whatlinkshere-hideredirs' => '$1 бә тожә унвон вығандеј',
'whatlinkshere-hidetrans' => '$1 әловон',
'whatlinkshere-hidelinks' => '$1 сәбонон',
'whatlinkshere-hideimages' => '$1 фајлинә сәбонон',
'whatlinkshere-filters' => 'Филтрон',

# Block/unblock
'ipbreason' => 'Сәбәб:',
'ipboptions' => '2 саат:2 hours,1 руж:1 day,3 руж:3 days,1 һафтә:1 week,2 һафтә:2 weeks,1 манг:1 month,3 манг:3 months,6 манг:6 months,1 сор:1 year,бемыһләт:infinite',
'ipbotheroption' => 'ҹо',
'ipblocklist' => 'Бастә быә иштирокәкон',
'blocklist-reason' => 'Сәбәб',
'ipblocklist-submit' => 'Нәве',
'blocklink' => 'Бә гырд гәтеј',
'unblocklink' => 'Ошко кардеј',
'change-blocklink' => 'Блок быә ҹо дәгиш кардеј',
'contribslink' => 'Koməqon',
'blocklogpage' => 'Блок быәјон',
'blocklogentry' => 'бастәше [[$1]] бә ын мыддәт $2 $3',
'block-log-flags-nocreate' => 'нујә иштирокәкон ғејд карде ғәдәғәне',

# Move page
'move-page-legend' => 'Сәһифә номи дәгиш карде',
'newtitle' => 'Нујә ном:',
'movepagebtn' => 'Сәһифә номи дәгиш кардеј',
'movelogpage' => 'Ном дәгиш кардә быә чијон журнал',
'revertmove' => 'Бә кәно окырније',

# Export
'export' => 'Сәһифон ихроҹ кардеј',
'export-addcat' => 'Зијод кардеј',
'export-addns' => 'Зијод кардеј',

# Namespace 8 related
'allmessagesname' => 'Хәбә',
'allmessagesdefault' => 'Иминә огәтә быә мәтн',
'allmessages-filter-all' => 'Һәммәј',
'allmessages-filter-submit' => 'Давард',

# Thumbnails
'thumbnail-more' => 'Һејве кардеј',
'thumbnail_error' => 'Гәдә шикили туму кардејәдә сәһв: $1',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Шымә иштирокәкә сәһифә',
'tooltip-pt-mytalk' => 'Шымә мызокирон сәһифә',
'tooltip-pt-preferences' => 'Шымә кукон',
'tooltip-pt-watchlist' => 'Сәһифон сијоһи, конҹо шымә де дығғәти дијә кардејдәјон бә дәгишон',
'tooltip-pt-mycontris' => 'Шымә гәнҹон сијоһи',
'tooltip-pt-login' => 'Ијо бәбе ыштәни ғејд кардовнијеј системәдә, интаси ым һукман ни',
'tooltip-pt-logout' => 'Системәдә кој орохнијеј',
'tooltip-ca-talk' => 'Сәһифә мығдори бәрәдә мызокирә',
'tooltip-ca-edit' => 'Ым сәһифә бәбе дагиш кардеј. Быһамијән, огәтеј бә нав, "сыфтәнә нишо дој" истифодә быкән',
'tooltip-ca-addsection' => 'Нујә ғысм офәјеј',
'tooltip-ca-viewsource' => 'Ым сәһифә мыдофијә быә дагиш кардеку, әммә шымә бәзынешон дијә кардеј ијән сурәт бекардеј әчәј бешемонә мәтни',
'tooltip-ca-history' => 'Сәһифә дәгишон журнал',
'tooltip-ca-protect' => 'Ым сәһифә дагиш кардеку мыдофијә кардеј',
'tooltip-ca-delete' => 'Ым сәһифә рәдд кәрдеј',
'tooltip-ca-move' => 'Сәһифә номи дәгиш кардеј',
'tooltip-ca-watch' => 'Ым сәһифә зијод кардеј бә шымә нығо доә сијоһи',
'tooltip-ca-unwatch' => 'Рәдд кардеј ым сәһифә шымә ноғо доә сијоһиәдә',
'tooltip-search' => 'Nəve {{SITENAME}}',
'tooltip-search-go' => 'Гирәм һесте дырыст бәнә бы номи сәһифә бәврә дәвардеј',
'tooltip-search-fulltext' => 'Səhifon pəydo kardey de ın mətni',
'tooltip-p-logo' => 'Dəvardey bə əsosə səhifə',
'tooltip-n-mainpage' => 'Дәвардеј бә әсосә сәһифә',
'tooltip-n-mainpage-description' => 'Дәвардеј бә әсосә сәһифә',
'tooltip-n-portal' => 'Naxşə barədə, çiç şımə bəzneyon ıyo kardey, iyən konco çiç heste',
'tooltip-n-currentevents' => 'Есәтнә һодисон сијоһи',
'tooltip-n-recentchanges' => 'Oxonə dəqişon siyohi',
'tooltip-n-randompage' => 'Rayrastə səhifə diyə kardey',
'tooltip-n-help' => 'Arayişə kitobçə bo ın naxşə',
'tooltip-t-whatlinkshere' => 'Бә ым сәһифә сәбон вардә һәммәј вики сәһифон сијоһи',
'tooltip-t-recentchangeslinked' => 'Охонә дәгишон сәһифонәдә, бә ком сәһифон сәбон вардә ым сәһифә',
'tooltip-feed-atom' => 'Транслјасијә кардеј бә Atom бо ым сәһифә',
'tooltip-t-contributions' => 'Че иштирок кардәкәси дагиш кардә быә сәһифон сијоһи',
'tooltip-t-emailuser' => 'Бы иштироәкә номә вығәнде',
'tooltip-t-upload' => 'Шикилон јаанки мултимедијә фајлон бо жај',
'tooltip-t-specialpages' => 'Xıdmətə səhifon siyohi',
'tooltip-t-print' => 'Ым сәһифә рәвојәт бо чап кардеј',
'tooltip-t-permalink' => 'Бә ым сәһифә рәвојәти еғрорә сәбон',
'tooltip-ca-nstab-main' => 'Мәғолә мығдор',
'tooltip-ca-nstab-user' => 'Иштирок кардәкәси сәһифә',
'tooltip-ca-nstab-media' => 'Медиа-фајл',
'tooltip-ca-nstab-special' => 'Ым хыдмәтә сәһифәје бычыми горә дәгиш кардеј әбыни',
'tooltip-ca-nstab-project' => 'Нәхши сәһифә',
'tooltip-ca-nstab-image' => 'Фајли сәһифә',
'tooltip-ca-nstab-template' => 'Ғәлиби сәһифә',
'tooltip-ca-nstab-category' => 'Категоријон сәһифә',
'tooltip-minoredit' => 'Ым дәгиши бәнә беәһмијәт ғејд кардеј.',
'tooltip-save' => 'Шымә дәгишон огәтеј',
'tooltip-preview' => 'Сәһифә сыфтәнә нишо дој, быһамијән огәтеј бә нав истифодә быкән!',
'tooltip-diff' => 'Бешемонә мәтни һәхәдә сохтә быә дәгишон нишо дој.',
'tooltip-compareselectedversions' => 'Че ым сәһифә ды гылә выжнијә быә рәвојәтон мијонәдә фәрғи едјәсеј.',
'tooltip-watch' => 'Ым сәһифә зијод кардеј бә ыштә нығо доә сијоһи',
'tooltip-upload' => 'Бо жәј бино кардеј',
'tooltip-rollback' => 'Охонә редактори дәгиш кардә быә чијон де и гылә егәте ләғв кардеј',
'tooltip-undo' => 'Дәғандә дәгиши рәдд кардеј ијән "сыфтәнә нишо дој" окардеј, де ләғви сәбәби нышон дој имкони.',
'tooltip-summary' => 'Кыртә тәсвир бынывыштән',

# Info page
'pageinfo-header-edits' => 'Дәгиш кардә быә чијон тарых',
'pageinfo-redirects-value' => '$1',

# Browsing diffs
'previousdiff' => '← Навынәни дәгиши',
'nextdiff' => 'Думотоно шә дәгиши →',

# Media information
'file-info-size' => '$1 × $2 пиксел, фајли памјә: $3, MIME тип: $4',
'file-nohires' => 'Ән барзә рәвојәт ни.',
'svg-long-desc' => 'SVG фајл, номинәләдә $1 × $2 пиксел, фајли памјә: $3',
'show-big-image' => 'Тикәјән јолә кејфијјәтинә шикил',

# Special:NewFiles
'ilsubmit' => 'Нәве',

# Bad image list
'bad_image_list' => 'Формат бәпе быбу жыго:

Бә һисоб сә быәбен әнҹәх сијоһи әсосон (де * рәмзи бино быә сәтырон).
Сәтыри иминә сәбон бәпе быбу сәбон бә ғәдәғән кардә быә бо дәғанде шикили.
Пешонә сәбонон бә һамонә сәтырәдә бәнә истино дијә кардә бәбен, јәни мәғолон, бә коврә шикил дахыл карде бәзне бе.',

# Metadata
'metadata' => 'Метамәлумотон',
'metadata-help' => 'Ым фајләдә фотоапарати јаанки сканери әловә кардә быә мәлумотон һестин. Гирәм фајл сохте бә пешто сәрост кардә быә, бе бәзнеки кали мәлумотон ијо нишо дојәдә фәрғ бәдон.',
'metadata-fields' => 'Кејнә шикили сәһифәдә метадата ҹәдвәл гырдә карда быә бәвәдә бы сијоһиәдә гылә-гылә ашмардә быә метадата шикили мәрон виндеј бәбе. 
Әмандәј мәрон нијони бәманден.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude',

# Exif tags
'exif-imagewidth' => 'Һовужи',
'exif-imagelength' => 'Былынди',
'exif-source' => 'Сәвон',
'exif-languagecode' => 'Зывон',

'exif-gaincontrol-0' => 'Ни',

'exif-saturation-0' => 'Ади',

'exif-dc-publisher' => 'Нәшрәкә',

# External editor support
'edit-externally' => 'Редактә кардеј ым фајли де заһири програм',
'edit-externally-help' => '(Bo mıffəssələ məlumoton bə [//www.mediawiki.org/wiki/Manual:External_editors dərsəvon bo soxtəy] diyə bıkən)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'һәммәј',
'namespacesall' => 'һәммәј',
'monthsall' => 'һәммәј',

# Table pager
'table_pager_limit_submit' => 'Давард',

# Watchlist editing tools
'watchlisttools-view' => 'Сәһифонәдә дәгишон сијоһику',
'watchlisttools-edit' => 'Дијә кардеј/сәрост кардеј сијоһи',
'watchlisttools-raw' => 'Сәрост кардеј бәнә мәтни',

# Core parser functions
'duplicate-defaultsort' => '\'\'\'Дыггәт:\'\'\' Еһтимал кардә быә "$2" классификасијә ачари нафконә "$1" классификасијә ачари етиборсоз кардә',

# Special:Version
'version' => 'Рәвојәт',
'version-specialpages' => 'Хысусијә сәһифон',
'version-entrypoints-header-url' => 'URL',

# Special:FileDuplicateSearch
'fileduplicatesearch-submit' => 'Нәве',

# Special:SpecialPages
'specialpages' => 'Хысусијә сәһифон',

# External image whitelist
'external_image_whitelist' => ' #Ым сәтри огәтәнән чокнәј әв һесте<pre>
#Рә рә истифадә быә фрагментон ијо быдәнән (ә һиссә, че // мијонәдә бедә )
#Әвон ды харичи шикили URL и дуз бәбен.
#Дуз омә гылә бәнә шикили нишо бәбе, амандәни бәнә шикили линк нишо бәбе.
#Сәтрон де # комментариј һисоб бедән.
#Сәтрон бә регистри һәссос нин.

#Рә рә око доә быә фрагментон че сәтри пентоно ијо быдәнән. Ым сәтри огәтәнән чокнәј һесте.</pre>',

# Special:Tags
'tag-filter' => '[[Special:Tags|нышонон]] филтр:',
'tags-title' => 'Нышонон',

);

<?php
/** Pashto (پښتو)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Ahmed-Najib-Biabani-Ibrahimkhel
 */

$namespaceNames = array(
	NS_MEDIA            => 'رسنۍ',
	NS_SPECIAL          => 'ځانګړی',
	NS_TALK             => 'خبرې_اترې',
	NS_USER             => 'کارن',
	NS_USER_TALK        => 'د_کارن_خبرې_اترې',
	NS_PROJECT_TALK     => 'د_$1_خبرې_اترې',
	NS_FILE             => 'دوتنه',
	NS_FILE_TALK        => 'د_دوتنې_خبرې_اترې',
	NS_MEDIAWIKI        => 'ميډياويکي',
	NS_MEDIAWIKI_TALK   => 'د_ميډياويکي_خبرې_اترې',
	NS_TEMPLATE         => 'کينډۍ',
	NS_TEMPLATE_TALK    => 'د_کينډۍ_خبرې_اترې',
	NS_HELP             => 'لارښود',
	NS_HELP_TALK        => 'د_لارښود_خبرې_اترې',
	NS_CATEGORY         => 'وېشنيزه',
	NS_CATEGORY_TALK    => 'د_وېشنيزې_خبرې_اترې',
);

$namespaceAliases = array(
	'کارونکی' => NS_USER,
	'د_کارونکي_خبرې_اترې' => NS_USER_TALK,
	'انځور' => NS_FILE,
	'د_انځور_خبرې_اترې' => NS_FILE_TALK,
);

$specialPageAliases = array(
	'Userlogin'                 => array( 'ننوتل' ),
	'Userlogout'                => array( 'وتل' ),
	'CreateAccount'             => array( 'کارن-حساب جوړول' ),
	'Preferences'               => array( 'غوره توبونه' ),
	'Watchlist'                 => array( 'کتنلړليک' ),
	'Recentchanges'             => array( 'اوسني بدلونونه' ),
	'Upload'                    => array( 'پورته کول' ),
	'Listfiles'                 => array( 'د انځورونو لړليک' ),
	'Newimages'                 => array( 'نوي انځورونه' ),
	'Listusers'                 => array( 'د کارونکو لړليک' ),
	'Randompage'                => array( 'ناټاکلی، ناټاکلی مخ' ),
	'Lonelypages'               => array( 'يتيم مخونه' ),
	'Uncategorizedpages'        => array( 'ناوېشلي مخونه' ),
	'Uncategorizedcategories'   => array( 'ناوېشلې وېشنيزې' ),
	'Uncategorizedimages'       => array( 'ناوېشلي انځورونه، ناوېشلې دوتنې' ),
	'Uncategorizedtemplates'    => array( 'ناوېشلې کينډۍ' ),
	'Unusedcategories'          => array( 'ناکارېدلي وېشنيزې' ),
	'Unusedimages'              => array( 'ناکارېدلې دوتنې' ),
	'Wantedcategories'          => array( 'غوښتلې وېشنيزې' ),
	'Wantedfiles'               => array( 'غوښتلې دوتنې' ),
	'Wantedtemplates'           => array( 'غوښتلې کينډۍ' ),
	'Shortpages'                => array( 'لنډ مخونه' ),
	'Longpages'                 => array( 'اوږده مخونه' ),
	'Newpages'                  => array( 'نوي مخونه' ),
	'Ancientpages'              => array( 'لرغوني مخونه' ),
	'Protectedpages'            => array( 'ژغورلي مخونه' ),
	'Protectedtitles'           => array( 'ژغورلي سرليکونه' ),
	'Allpages'                  => array( 'ټول مخونه' ),
	'Specialpages'              => array( 'ځانګړي مخونه' ),
	'Contributions'             => array( 'ونډې' ),
	'Booksources'               => array( 'د کتاب سرچينې' ),
	'Categories'                => array( 'وېشنيزې' ),
	'Export'                    => array( 'صادرول' ),
	'Version'                   => array( 'بڼه' ),
	'Allmessages'               => array( 'ټول-پيغامونه' ),
	'Log'                       => array( 'يادښتونه، يادښت' ),
	'Undelete'                  => array( 'ناړنګول' ),
	'Unwatchedpages'            => array( 'ناکتلي مخونه' ),
	'Unusedtemplates'           => array( 'ناکارېدلې کينډۍ' ),
	'Mypage'                    => array( 'زما پاڼه' ),
	'Mytalk'                    => array( 'زما خبرې اترې' ),
	'Mycontributions'           => array( 'زماونډې' ),
	'Popularpages'              => array( 'نامتومخونه' ),
	'Search'                    => array( 'پلټنه' ),
	'Resetpass'                 => array( 'پټنوم بدلول، پټنوم بيا پر ځای کول، د بيا پر ځای کولو پاسپورټ' ),
	'Blankpage'                 => array( 'تش مخ' ),
	'LinkSearch'                => array( 'د تړنې پلټنه' ),
	'DeletedContributions'      => array( 'ړنګې شوي ونډې' ),
);

$magicWords = array(
	'notoc'                 => array( '0', '__بی‌نيولک__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__بی‌نندارتونه__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__نيوليکداره__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__نيوليک__', '__TOC__' ),
	'noeditsection'         => array( '0', '__بی‌برخې__', '__NOEDITSECTION__' ),
	'currentmonth'          => array( '1', 'روانه_مياشت', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'      => array( '1', 'دروانې_مياشت_نوم', 'CURRENTMONTHNAME' ),
	'currentmonthabbrev'    => array( '1', 'دروانې_مياشت_لنډون', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'نن', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'نن۲', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'دننۍورځې_نوم', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'سږکال', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'داوخت', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'دم_ګړۍ', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'سيمه_يزه_مياشت', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonthname'        => array( '1', 'دسيمه_يزې_مياشت_نوم', 'LOCALMONTHNAME' ),
	'localmonthabbrev'      => array( '1', 'دسيمه_يزې_مياشت_لنډون', 'LOCALMONTHABBREV' ),
	'localday'              => array( '1', 'سيمه_يزه_ورځ', 'LOCALDAY' ),
	'localday2'             => array( '1', 'سيمه_يزه_ورځ۲', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'دسيمه_يزې_ورځ_نوم', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'سيمه_يزکال', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'سيمه_يزوخت', 'LOCALTIME' ),
	'localhour'             => array( '1', 'سيمه_يزه_ګړۍ', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'دمخونوشمېر', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'دليکنوشمېر', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'ددوتنوشمېر', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'دکارونکوشمېر', 'NUMBEROFUSERS' ),
	'pagename'              => array( '1', 'دمخ_نوم', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'دمخ_نښه', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'نوم_تشيال', 'NAMESPACE' ),
	'namespacee'            => array( '1', 'د_نوم_تشيال_نښه', 'NAMESPACEE' ),
	'talkspace'             => array( '1', 'دخبرواترو_تشيال', 'TALKSPACE' ),
	'talkspacee'            => array( '1', 'دخبرواترو_تشيال_نښه', 'TALKSPACEE' ),
	'subjectspace'          => array( '1', 'دسکالوتشيال', 'دليکنې_تشيال', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'         => array( '1', 'دسکالوتشيال_نښه', 'دليکنې_تشيال_نښه', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'          => array( '1', 'دمخ_بشپړنوم', 'FULLPAGENAME' ),
	'fullpagenamee'         => array( '1', 'دمخ_بشپړنوم_نښه', 'FULLPAGENAMEE' ),
	'msg'                   => array( '0', 'پیغام:', 'پ:', 'MSG:' ),
	'img_thumbnail'         => array( '1', 'بټنوک', 'thumbnail', 'thumb' ),
	'img_right'             => array( '1', 'ښي', 'right' ),
	'img_left'              => array( '1', 'کيڼ', 'left' ),
	'img_none'              => array( '1', 'هېڅ', 'none' ),
	'img_center'            => array( '1', 'مېنځ، center', 'center', 'centre' ),
	'sitename'              => array( '1', 'دوېبځي_نوم', 'SITENAME' ),
	'server'                => array( '0', 'پالنګر', 'SERVER' ),
	'servername'            => array( '0', 'دپالنګر_نوم', 'SERVERNAME' ),
	'grammar'               => array( '0', 'ګرامر:', 'GRAMMAR:' ),
	'currentweek'           => array( '1', 'روانه_اوونۍ', 'CURRENTWEEK' ),
	'currentdow'            => array( '1', 'داوونۍورځ', 'CURRENTDOW' ),
	'localweek'             => array( '1', 'سيمه_يزه_اوونۍ', 'LOCALWEEK' ),
	'plural'                => array( '0', 'جمع:', 'PLURAL:' ),
	'language'              => array( '0', '#ژبه:', '#LANGUAGE:' ),
	'special'               => array( '0', 'ځانګړی', 'special' ),
	'hiddencat'             => array( '1', '__پټه_وېشنيزه__', '__HIDDENCAT__' ),
	'pagesize'              => array( '1', 'مخکچه', 'PAGESIZE' ),
	'index'                 => array( '1', '__ليکلړ__', '__INDEX__' ),
	'noindex'               => array( '1', '__بې ليکلړ__', '__NOINDEX__' ),
	'protectionlevel'       => array( '1', 'ژغورکچه', 'PROTECTIONLEVEL' ),
);

$rtl = true;
$defaultUserOptionOverrides = array(
	# Swap sidebar to right side by default
	'quickbar' => 2,
	# Underlines seriously harm legibility. Force off:
	'underline' => 0,
);

$messages = array(
# User preference toggles
'tog-underline'              => 'کرښنې تړنې:',
'tog-hideminor'              => 'په وروستيو بدلونو کې وړې سمادېدنې پټول',
'tog-hidepatrolled'          => 'په وروستيو بدلونونو کې څارل شوې سمونې پټول',
'tog-showtoolbar'            => 'د سمادولو توکپټه ښکاره کول (جاواسکرېپټ)',
'tog-rememberpassword'       => 'زما پټنوم پدې کمپيوټر په ياد ولره!',
'tog-watchcreations'         => 'هغه مخونه چې زه يې جوړوم، زما کتنلړليک کې ورګډ کړه',
'tog-watchdefault'           => 'هغه مخونه چې زه يې سموم، زما کتنلړليک کې ورګډ کړه',
'tog-watchmoves'             => 'هغه مخونه چې زه يې لېږدوم، زما کتنلړليک کې ورګډ کړه',
'tog-watchdeletion'          => 'هغه مخونه چې زه يې ړنګوم، زما کتنلړليک کې ورګډ کړه',
'tog-previewontop'           => 'د سمون بکس نه دمخه مخکتنه ښکاره کول',
'tog-previewonfirst'         => 'په لومړي سمون کې مخکتنه ښکاره کول',
'tog-enotifwatchlistpages'   => 'کله چې زما کتنلړليک کې يو مخ بدلون مومي نو ما ته دې برېښليک راشي',
'tog-enotifusertalkpages'    => 'کله چې زما د خبرو اترو په مخ کې بدلون پېښېږي نو ما ته دې يو برېښليک ولېږلی شي.',
'tog-enotifminoredits'       => 'کله چې په مخونو کې وړې سمونې کېږي نو ماته دې برېښليک ولېږل شي',
'tog-watchlisthideown'       => 'په کتنلړليک کې زما سمونې پټول',
'tog-watchlisthidebots'      => 'په کتنلړليک کې د باټ سمونې پټول',
'tog-watchlisthideminor'     => 'په کتنلړليک کې وړې سمونې پټول',
'tog-watchlisthideliu'       => 'په کتنلړليک کې د ثبت شويو کارنانو سمونې پټول',
'tog-watchlisthideanons'     => 'په کتنلړليک کې د ورکنومو کارنانو سمونې پټول',
'tog-watchlisthidepatrolled' => 'په کتنلړليک کې څارل شوې سمونې پټول',
'tog-ccmeonemails'           => 'هغه برېښليکونه چې زه يې نورو ته لېږم، د هغو يوه کاپي دې ماته هم راشي',
'tog-diffonly'               => 'د توپيرونو نه لاندې د مخ مېنځپانګه پټول',
'tog-showhiddencats'         => 'پټې وېشنيزې ښکاره کول',

'underline-always' => 'تل',
'underline-never'  => 'هېڅکله',

# Dates
'sunday'        => 'اتوار',
'monday'        => 'ګل',
'tuesday'       => 'نهي',
'wednesday'     => 'شورو',
'thursday'      => 'زيارت',
'friday'        => 'جمعه',
'saturday'      => 'خالي',
'sun'           => 'اتوار',
'mon'           => 'ګل',
'tue'           => 'نهي',
'wed'           => 'شورو',
'thu'           => 'زيارت',
'fri'           => 'جمعه',
'sat'           => 'خالي',
'january'       => 'جنوري',
'february'      => 'فبروري',
'march'         => 'مارچ',
'april'         => 'اپرېل',
'may_long'      => 'می',
'june'          => 'جون',
'july'          => 'جولای',
'august'        => 'اګسټ',
'september'     => 'سېپتمبر',
'october'       => 'اکتوبر',
'november'      => 'نومبر',
'december'      => 'ډيسمبر',
'january-gen'   => 'جنوري',
'february-gen'  => 'فبروري',
'march-gen'     => 'مارچ',
'april-gen'     => 'اپرېل',
'may-gen'       => 'می',
'june-gen'      => 'جون',
'july-gen'      => 'جولای',
'august-gen'    => 'اګسټ',
'september-gen' => 'سېپتمبر',
'october-gen'   => 'اکتوبر',
'november-gen'  => 'نومبر',
'december-gen'  => 'ډيسمبر',
'jan'           => 'جنوري',
'feb'           => 'فبروري',
'mar'           => 'مارچ',
'apr'           => 'اپرېل',
'may'           => 'می',
'jun'           => 'جون',
'jul'           => 'جولای',
'aug'           => 'اګسټ',
'sep'           => 'سېپتمبر',
'oct'           => 'اکتوبر',
'nov'           => 'نومبر',
'dec'           => 'ډيسمبر',

# Categories related messages
'pagecategories'              => '{{PLURAL:$1|وېشنيزه|وېشنيزې}}',
'category_header'             => 'د "$1" په وېشنيزه کې شته مخونه',
'subcategories'               => 'وړې-وېشنيزې',
'category-media-header'       => '"$1" رسنۍ په وېشنيزه کې',
'category-empty'              => "''تر اوسه پورې همدا وېشنيزه هېڅ کوم مخ يا کومه رسنيزه دوتنه نلري.''",
'hidden-categories'           => '{{PLURAL:$1|پټه وېشنيزه|پټې وېشنيزې}}',
'hidden-category-category'    => 'پټې وېشنيزې',
'category-article-count'      => '{{PLURAL:$2|په همدې وېشنيزه کې يواځې دغه لاندينی مخ شته.|دا {{PLURAL:$1|لاندينی مخ|$1 لانديني مخونه}}، له ټولټال $2 مخونو نه په دې وېشنيزه کې شته.}}',
'category-file-count-limited' => 'په اوسنۍ وېشنيزه کې {{PLURAL:$1|يوه دوتنه ده|$1 دوتنې دي}}.',
'listingcontinuesabbrev'      => 'پرله پسې',

'mainpagetext'      => "<big>'''MediaWiki په برياليتوب سره نصب شو.'''</big>",
'mainpagedocfooter' => "Consult the [http://meta.wikimedia.org/wiki/Help:Contents User's Guide] for information on using the wiki software.

== پيلول ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Manual:FAQ د ميډياويکي ډېرځليزې پوښتنې]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]",

'about'         => 'په اړه',
'article'       => 'د منځپانګې مخ',
'newwindow'     => '(په نوې کړکۍ کې پرانيستل کېږي)',
'cancel'        => 'کوره کول',
'moredotdotdot' => 'نور ...',
'mypage'        => 'زما پاڼه',
'mytalk'        => 'زما خبرې اترې',
'anontalk'      => 'ددې IP لپاره خبرې اترې',
'navigation'    => 'ګرځښت',
'and'           => '&#32;او',

# Cologne Blue skin
'qbfind'         => 'موندل',
'qbedit'         => 'سمون',
'qbpageoptions'  => 'همدا مخ',
'qbpageinfo'     => 'متن',
'qbmyoptions'    => 'زما پاڼې',
'qbspecialpages' => 'ځانګړي مخونه',
'faq'            => 'ډ-ځ-پ',

# Vector skin
'vector-action-delete'       => 'ړنګول',
'vector-action-move'         => 'لېږدول',
'vector-action-protect'      => 'پروژه',
'vector-action-undelete'     => 'ناړنګول',
'vector-action-unprotect'    => 'ناژغورل',
'vector-namespace-category'  => 'وېشنيزه',
'vector-namespace-help'      => 'لارښود مخ',
'vector-namespace-image'     => 'دوتنه',
'vector-namespace-main'      => 'مخ',
'vector-namespace-media'     => 'د رسنۍ مخ',
'vector-namespace-mediawiki' => 'پيغام',
'vector-namespace-project'   => 'د پروژې مخ',
'vector-namespace-special'   => 'ځانګړی مخ',
'vector-namespace-talk'      => 'خبرې اترې',
'vector-namespace-template'  => 'کينډۍ',
'vector-namespace-user'      => 'کارن مخ',
'vector-view-create'         => 'جوړول',
'vector-view-edit'           => 'سمون',
'vector-view-history'        => 'پېښليک کتل',
'vector-view-view'           => 'لوستل',
'vector-view-viewsource'     => 'سرچينه کتل',
'actions'                    => 'کړنې',
'namespaces'                 => 'نوم-تشيالونه',

# Metadata in edit box
'metadata_help' => 'مېټاډاټا:',

'errorpagetitle'    => 'تېروتنه',
'returnto'          => 'بېرته $1 ته وګرځه.',
'tagline'           => 'د {{SITENAME}} لخوا',
'help'              => 'لارښود',
'search'            => 'پلټنه',
'searchbutton'      => 'پلټل',
'go'                => 'ورځه',
'searcharticle'     => 'ورځه',
'history'           => 'د مخ پېښليک',
'history_short'     => 'پېښليک',
'updatedmarker'     => 'زما د وروستي راتګ نه راپدېخوا اوسمهاله شوی',
'info_short'        => 'مالومات',
'printableversion'  => 'د چاپ بڼه',
'permalink'         => 'تلپاتې تړنه',
'print'             => 'چاپ',
'edit'              => 'سمون',
'create'            => 'جوړول',
'editthispage'      => 'همدا مخ سمول',
'create-this-page'  => 'همدا مخ ليکل',
'delete'            => 'ړنګول',
'deletethispage'    => 'دا مخ ړنګ کړه',
'undelete_short'    => '{{PLURAL:$1|يو سمون|$1 سمونې}} ناړنګول',
'protect'           => 'ژغورل',
'protect_change'    => 'بدلون',
'protectthispage'   => 'همدا مخ ژغورل',
'unprotect'         => 'نه ژغورل',
'unprotectthispage' => 'همدا مخ نه ژغورل',
'newpage'           => 'نوی مخ',
'talkpage'          => 'په همدې مخ خبرې اترې کول',
'talkpagelinktext'  => 'خبرې اترې',
'specialpage'       => 'ځانګړې پاڼه',
'personaltools'     => 'شخصي اوزار',
'postcomment'       => 'نوې برخه',
'articlepage'       => 'د مخ مېنځپانګه ښکاره کول',
'talk'              => 'خبرې اترې',
'views'             => 'کتنې',
'toolbox'           => 'اوزاربکس',
'userpage'          => 'د کاروونکي پاڼه ښکاره کول',
'projectpage'       => 'د پروژې مخ ښکاره کول',
'imagepage'         => 'د دوتنې مخ کتل',
'mediawikipage'     => 'د پيغامونو مخ کتل',
'templatepage'      => 'د کينډۍ مخ ښکاره کول',
'viewhelppage'      => 'د لارښود مخ کتل',
'categorypage'      => 'د وېشنيزې مخ کتل',
'viewtalkpage'      => 'خبرې اترې کتل',
'otherlanguages'    => 'په نورو ژبو کې',
'redirectedfrom'    => '(له $1 نه راګرځول شوی)',
'redirectpagesub'   => 'ورګرځېدلی مخ',
'lastmodifiedat'    => 'دا مخ وروستی ځل په $2، $1 بدلون موندلی.',
'viewcount'         => 'همدا مخ {{PLURAL:$1|يو وار|$1 واره}} کتل شوی.',
'protectedpage'     => 'ژغورلی مخ',
'jumpto'            => 'ورټوپ کړه:',
'jumptonavigation'  => 'ګرځښت',
'jumptosearch'      => 'پلټل',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'د {{SITENAME}} په اړه',
'aboutpage'            => 'Project:په اړه',
'copyright'            => 'دا مېنځپانګه د $1 له مخې ستاسو لاس رسي لپاره دلته ده.',
'copyrightpage'        => '{{ns:project}}:رښتې',
'currentevents'        => 'اوسنۍ پېښې',
'currentevents-url'    => 'Project:تازه پېښې',
'disclaimers'          => 'ردادعاليکونه',
'disclaimerpage'       => 'Project:ټولګړی ردادعاليک',
'edithelp'             => 'د لارښود سمون',
'edithelppage'         => 'Help:سمونه',
'helppage'             => 'Help:نيوليک',
'mainpage'             => 'لومړی مخ',
'mainpage-description' => 'لومړی مخ',
'policy-url'           => 'Project:تګلاره',
'portal'               => 'ټولګړی ورټک',
'portal-url'           => 'Project:د ټولنې ورټک',
'privacy'              => 'د محرميت تګلاره',
'privacypage'          => 'Project:د محرميت_تګلاره',

'badaccess'        => 'د لاسرسۍ تېروتنه',
'badaccess-group0' => 'تاسو د غوښتل شوې کړنې د ترسره کولو اجازه نه لرۍ.',
'badaccess-groups' => 'د کومې کړنې غوښتنه چې تاسو کړې د هغو کارونکو پورې محدوده ده چې {{PLURAL:$2|په ډله د|په ډلو د}}: $1 کې دي.',

'versionrequired' => 'د ميډياويکي $1 بڼې ته اړتيا ده',

'ok'                      => 'هو',
'retrievedfrom'           => 'همدا مخ له "$1" څخه رااخيستل شوی',
'youhavenewmessages'      => 'تاسو $1 لری  ($2).',
'newmessageslink'         => 'نوي پيغامونه',
'newmessagesdifflink'     => 'وروستی بدلون',
'youhavenewmessagesmulti' => 'ستاسو لپاره په $1 کې نوي پېغام راغلي.',
'editsection'             => 'سمول',
'editold'                 => 'سمول',
'viewsourceold'           => 'سرچينې کتل',
'editlink'                => 'سمول',
'viewsourcelink'          => 'سرچينه کتل',
'editsectionhint'         => 'د سمادلو برخه: $1',
'toc'                     => 'نيوليک',
'showtoc'                 => 'ښکاره کول',
'hidetoc'                 => 'پټول',
'viewdeleted'             => '$1 کتل؟',
'feedlinks'               => 'کتنه:',
'site-rss-feed'           => '$1 د آر اس اس کتنه',
'site-atom-feed'          => '$1 د اټوم کتنه',
'page-rss-feed'           => '"$1" د آر اس اس کتنه',
'page-atom-feed'          => 'د "$1" د اټوم کتنې',
'feed-rss'                => 'آر اس اس',
'red-link-title'          => '$1 (تر اوسه پورې نه شته)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'ليکنه',
'nstab-user'      => 'د کارونکي پاڼه',
'nstab-media'     => 'د رسنۍ مخ',
'nstab-special'   => 'ځانګړی مخ',
'nstab-project'   => 'د پروژې مخ',
'nstab-image'     => 'دوتنه',
'nstab-mediawiki' => 'پيغام',
'nstab-template'  => 'کينډۍ',
'nstab-help'      => 'لارښود',
'nstab-category'  => 'وېشنيزه',

# Main script and global functions
'nosuchaction'      => 'هېڅ داسې کومه کړنه نشته',
'nosuchspecialpage' => 'داسې هېڅ کوم ځانګړی مخ نشته',
'nospecialpagetext' => '<strong>تاسو د يو ناسم ځانګړي مخ غوښتنه کړې.</strong>

تاسو کولای شی چې د سمو ځانګړو مخونو لړليک په [[Special:SpecialPages|{{int:specialpages}}]] کې ومومۍ.',

# General errors
'error'                => 'تېروتنه',
'databaseerror'        => 'د ډاټابېز تېروتنه',
'missingarticle-diff'  => '(توپير: $1، $2)',
'internalerror'        => 'کورنۍ تېروتنه',
'internalerror_info'   => 'کورنۍ تېروتنه: $1',
'filecopyerror'        => 'د "$1" په نامه دوتنه مو "$2" ته و نه لمېسلای شوه.',
'filerenameerror'      => 'د "$1" په نامه د دوتنې نوم "$2" ته بدل نه شو.',
'filedeleteerror'      => 'د "$1" دوتنه ړنګه نه شوه.',
'directorycreateerror' => 'د "$1" په نامه ليکلړ جوړ نه شو.',
'filenotfound'         => '"$1" په نوم دوتنه مو و نه شوه موندلای.',
'fileexistserror'      => 'د "$1" په نامه دوتنه نه ليکل کېږي: دوتنه د پخوا نه دلته شته',
'badarticleerror'      => 'دا کړنه پدې مخ نه شي ترسره کېدلای.',
'cannotdelete'         => 'د اړونده مخ يا دوتنې ړنګېدنه ترسره نه شوه.  (کېدای شي چې دا د بل چا لخوا نه پخوا ړنګه شوې وي.)',
'badtitle'             => 'ناسم سرليک',
'badtitletext'         => 'يا خو ستاسو د غوښتل شوي مخ سرليک سم نه وو، د سرليک ځای مو تش وو او يا هم د ژبو خپلمنځي تړنې څخه يا د ويکي ګانو خپلمنځي سرليکونو څخه يو ناسم توری پکې کارول شوی.
کېدای شي چې ستاسو په ورکړ شوي سرليک کې يو يا ګڼ شمېر داسې توري وي چې د سرليک په توګه بايد و نه کارېږي.',
'viewsource'           => 'سرچينه کتل',
'viewsourcefor'        => 'د $1 لپاره',
'protectedpagetext'    => 'همدا مخ د سمادولو د مخنيوي په تکل تړل شوی دی.',
'viewsourcetext'       => 'تاسو د همدغه مخ توکي او سرچينې کتلی او لمېسلی شی:',
'protectedinterface'   => 'په همدې مخ کې د پوستکالي د ليدنمخ متن دی او دا متن د ناسمو کارولو د مخنيوي په تکل تړل شوی.',
'editinginterface'     => "'''ګواښنه:''' تاسو په يوه داسې مخ کې بدلون راولی کوم چې د يوې پوستکالی د ليدنمخ متن په توګه کارېږي.
په همدې مخ کې بدلون راوستل به د نورو کارونکو د ليدنمخ بڼه اغېزمنه کړي.
د ژباړې لپاره، مهرباني وکړی د [http://translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net]، وېبځي ته ولاړ شی. دا وېبځی د ميډياويکي د ځايتابه پروژه ده او د همدې پر کارولو غور وکړی.",
'namespaceprotected'   => "تاسو ته د '''$1''' په نوم-تشيال کې د مخونو د سمادولو اجازه نشته.",
'ns-specialprotected'  => 'ځانګړې مخونه د سمادولو وړ نه دي.',

# Login and logout pages
'logouttext'                 => "'''تاسو اوس د غونډال نه ووتلی.'''

تاسو کولای شی چې پرته د کارن-نوم نه {{SITENAME}} په ورکنومي توګه وکاروی، او يا هم تاسو کولای شی چې په همدې کارن-نوم يا په کوم بل کارن-نوم خپلې ليکنې خپرې کړی. 
يادونه دې وي چې ځينې مخونو کې به تاسو لا تر اوسه پورې غونډال کې ننوتي ښکاری، تر څو تاسو د خپل کتنمل حافظه نه وي سپينه کړی.",
'welcomecreation'            => '==$1 ښه راغلاست! ==

ستاسو کارن-حساب جوړ شو. لطفاً د [[Special:Preferences|{{SITENAME}} غوره توبونو]] بدلول مو مه هېروی.',
'yourname'                   => 'کارن-نوم:',
'yourpassword'               => 'پټنوم:',
'yourpasswordagain'          => 'پټنوم بيا وليکه',
'remembermypassword'         => 'زما پټنوم پدې کمپيوټر په ياد ولره!',
'yourdomainname'             => 'ستاسې شپول:',
'login'                      => 'ننوتل',
'nav-login-createaccount'    => 'ننوتل / کارن-حساب جوړول',
'loginprompt'                => 'ددې لپاره چې {{SITENAME}} کې ننوځۍ نو بايد چې ستاسو د کمپيوټر کوکيز چارن وي.',
'userlogin'                  => 'ننوتل / کارن-حساب جوړول',
'logout'                     => 'وتل',
'userlogout'                 => 'وتل',
'notloggedin'                => 'غونډال کې نه ياست ننوتي',
'nologin'                    => "کارن-نوم نه لرې؟ '''$1'''.",
'nologinlink'                => 'يو کارن-حساب جوړول',
'createaccount'              => 'کارن-حساب جوړول',
'gotaccount'                 => "آيا وار دمخې يو کارن-حساب لری؟ '''$1'''.",
'gotaccountlink'             => 'ننوتل',
'createaccountmail'          => 'د برېښليک له مخې',
'badretype'                  => 'دا پټنوم چې تاسو ليکلی د پخواني پټنوم سره ورته نه دی.',
'userexists'                 => 'کوم کارن نوم چې تاسو ورکړ هغه بل چا کارولی.
لطفاً يو بل نوم وټاکۍ.',
'loginerror'                 => 'د ننوتنې ستونزه',
'nocookiesnew'               => 'ستاسو کارن-حساب جوړ شو، خو تاسو غونډال ته نه ياست ورننوتلي.
د {{SITENAME}} وېبځی د کارونکو د ننوتلو لپاره کوکيز کاروي.
ستاسو کوکيز ناچارن دي.
مهرباني وکړی خپل کوکيز چارن کړی او بيا د خپل کارن-نوم او پټنوم په کارولو سره غونډال ته ورننوځی.',
'nocookieslogin'             => 'د {{SITENAME}} وېبځی د کارونکو د ننوتلو لپاره کوکيز کاروي.
ستاسو کوکيز ناچارن دي.
مهرباني وکړی خپل کوکيز چارن کړی او يو ځل بيا د ننوتلو هڅه وکړی.',
'noname'                     => 'تاسو تر اوسه پورې کوم کره کارن نوم نه دی ځانګړی کړی.',
'loginsuccesstitle'          => 'ننوتل مو برياليتوب سره ترسره شوه',
'loginsuccess'               => "'''تاسو اوس {{SITENAME}} کې د \"\$1\" په نوم ننوتي ياست.'''",
'nosuchuser'                 => 'د "$1" په نوم هېڅ کارونکی نشته.
د کارونکو نومونه د غټو او واړو تورو سره حساس دي.
خپل حجا وڅارۍ، او يا هم [[Special:UserLogin/signup|يو نوی کارن-حساب جوړ کړی]].',
'nosuchusershort'            => 'د "<nowiki>$1</nowiki>" په نوم هېڅ کوم کارن-حساب نشته. لطفاً خپل د نوم ليکلې بڼې ته ځير شی چې پکې تېروتنه نه وي.',
'nouserspecified'            => 'تاسو ځان ته کوم کارن نوم نه دی ځانګړی کړی.',
'wrongpassword'              => 'ناسم پټنوم مو ليکلی. لطفاً يو ځل بيا يې وليکۍ.',
'wrongpasswordempty'         => 'تاسو پټنوم نه دی ليکلی. لطفاً سر له نوي يې وليکۍ.',
'passwordtooshort'           => 'بايد چې پټنوم مو لږ تر لږه {{PLURAL:$1|1 توری|$1 توري}} وي.',
'password-name-match'        => 'ستاسې پټنوم بايد ستاسې د کارن-نوم سره توپير ولري.',
'mailmypassword'             => 'نوی پټنوم برېښليک کول',
'passwordremindertitle'      => 'د {{SITENAME}} لپاره نوی لنډمهاله پټنوم',
'passwordremindertext'       => 'يو چا (کېدای شي چې ستاسو، د  IPپتې $1 نه)
د {{SITENAME}} ($4) وېبځي لپاره د يوه نوي پټنوم د ورلېږلو غوښتنه کړې.
د "$2" لپاره يو نوی لنډمهاله پټنوم اوس "$3" دی.
که چېرته همدا غوښتنه ستاسو لخوا شوي وي نو اوس تاسو غونډال ته په همدغه پټنوم ورننوځی او بيا خپل پټنوم په خپله خوښه بدل کړی.

که چېرته ستاسو نه پرته کوم بل چا دغه غوښتنه کړې وي او يا هم تاسو ته بېرته خپل پټنوم در پزړه شوی وي او تاسو د خپل پټنوم بدلول نه غواړۍ، نو تاسو همدا پيغام بابېزه وګڼۍ او د پخوا په څېر خپل هماغه پخوانی پټنوم وکاروی.',
'noemail'                    => 'د "$1" کارونکي په نامه هېڅ کومه برېښليک پته نه ده ثبته شوې.',
'noemailcreate'              => 'تاسې ته پکار ده چې يوه سمه برېښليک پته وليکۍ',
'passwordsent'               => 'د "$1" لپاره يو نوی پټنوم د هغه/هغې د برېښليک پتې ته ولېږل شو.
لطفاً کله چې پټنوم مو ترلاسه کړ نو بيا غونډال ته ننوځۍ.',
'blocked-mailpassword'       => 'ستاسو په IP پتې بنديز لګېدلی او تاسو نه شی کولای چې ليکنې وکړی، په همدې توګه تاسو نه شی کولای چې د پټنوم د پرځای کولو کړنې وکاروی دا ددې لپاره چې د وراني مخنيوی وشي.',
'eauthentsent'               => 'ستاسو ورکړ شوې برېښليک پتې ته مو يو تاييدي برېښليک درولېږی.
تر دې دمخه چې ستاسو کارن-حساب ته کوم بل برېښليک درولېږو، پکار ده چې تاسو په برېښليک کې درلېږل شوې لارښوونې پلي کړی او ددې پخلی وکړی چې همدا کارن-حساب په رښتيا ستاسو دی.',
'mailerror'                  => 'د برېښليک د لېږلو ستونزه: $1',
'acct_creation_throttle_hit' => 'د همدې ويکي کارونکو په وروستيو ورځو کې ستاسې د IP پتې په کارولو سره {{PLURAL:$1|1 کارن-حساب|$1 کارن-حسابونه}} جوړ کړي، چې دا په همدې مودې کې د کارن-حسابونو د جوړولو تر ټولو ډېر شمېر دی چې اجازه يې ورکړ شوې.
نو په همدې خاطر د اوس لپاره د همدې IP پتې کارونکي نه شي کولای چې نور کارن-حسابونه جوړ کړي.',
'emailauthenticated'         => 'ستاسو برېښليک پته په $2 نېټه په $3 بجو د منلو وړ وګرځېده.',
'emailnotauthenticated'      => 'ستاسو د برېښليک پته لا تر اوسه پورې د منلو وړ نه ده ګرځېدلې. د اړوندو بېلوونکو نښو په هکله تاسو ته هېڅ کوم برېښليک نه لېږل کېږي.',
'noemailprefs'               => 'ددې لپاره چې دا کړنې کار وکړي نو تاسو يو برېښليک وټاکۍ.',
'emailconfirmlink'           => 'د خپل د برېښليک پتې پخلی وکړی',
'accountcreated'             => 'کارن-حساب مو جوړ شو.',
'accountcreatedtext'         => 'د $1 لپاره يو کارن-حساب جوړ شو.',
'createaccount-title'        => 'د {{SITENAME}} د کارن-حساب جوړېدنه',
'loginlanguagelabel'         => 'ژبه: $1',

# Password reset dialog
'resetpass'                 => 'پټنوم بدلول',
'resetpass_header'          => 'د کارن-حساب پټنوم بدلول',
'oldpassword'               => 'زوړ پټنوم:',
'newpassword'               => 'نوی پټنوم:',
'retypenew'                 => 'نوی پټنوم بيا وليکه:',
'resetpass_forbidden'       => 'پټنومونه مو نه شي بدلېدلای',
'resetpass-no-info'         => 'همدې مخ ته د لاسرسي موندلو پخاطر تاسې ته پکار ده چې لومړی غونډال ته ورننوځۍ.',
'resetpass-submit-loggedin' => 'پټنوم بدلول',
'resetpass-temp-password'   => 'لنډمهالی پټنوم:',

# Edit page toolbar
'bold_sample'     => 'روڼ ليک',
'bold_tip'        => 'روڼ ليک',
'italic_sample'   => 'کوږ ليک',
'italic_tip'      => 'کوږ ليک',
'link_sample'     => 'د تړن سرليک',
'link_tip'        => 'کورنيزه تړنه',
'extlink_sample'  => 'http://www.example.com د تړنې سرليک',
'extlink_tip'     => 'باندنۍ تړنې (د http:// مختاړی مه هېروی)',
'headline_sample' => 'سرليک',
'headline_tip'    => 'د ۲ کچې سرليک',
'math_sample'     => 'فورمول دلته ځای کړی',
'math_tip'        => 'شمېرپوهنيز فورمول (LaTeX)',
'nowiki_sample'   => 'دلته دې بې بڼې متن ځای پر ځای شي',
'nowiki_tip'      => 'د ويکي بڼه نيونه بابېزه ګڼل',
'image_tip'       => 'خښه شوې دوتنه',
'media_tip'       => 'د دوتنې تړنه',
'sig_tip'         => 'ستاسو لاسليک د وخت د ټاپې سره',
'hr_tip'          => 'څنډيزه ليکه (ددې په کارولو کې سپما وکړۍ)',

# Edit pages
'summary'                          => 'لنډيز:',
'subject'                          => 'سکالو/سرليک:',
'minoredit'                        => 'دا يوه وړه سمونه ده',
'watchthis'                        => 'همدا مخ کتل',
'savearticle'                      => 'مخ خوندي کول',
'preview'                          => 'مخکتنه',
'showpreview'                      => 'مخکتنه',
'showlivepreview'                  => 'ژوندۍ مخکتنه',
'showdiff'                         => 'بدلونونه ښکاره کول',
'anoneditwarning'                  => "'''يادونه:''' تاسو غونډال ته نه ياست ننوتي. ستاسو IP پته به د دې مخ د سمونونو په پېښليک کې ثبت شي.",
'missingcommenttext'               => 'لطفاً تبصره لاندې وليکۍ.',
'summary-preview'                  => 'د لنډيز مخکتنه:',
'subject-preview'                  => 'موضوع/سرليک مخکتنه:',
'blockedtitle'                     => 'د کارونکي مخه نيول شوې',
'blockedtext'                      => "<big>'''ستاسو د کارن-نوم يا آی پي پتې مخنيوی شوی.'''</big>

همدا بنديز د $1 له خوا پر تاسو لږېدلی. او د همدې کړنې سبب دی ''$2''.

* د مخنيوي د پېل نېټه: $8
* د مخنيوي د پای نېټه: $6
* بنديزونه دي پر: $7

تاسو کولای شی چې د $1 او يا هم د يو بل [[{{MediaWiki:Grouppage-sysop}}|پازوال]] سره اړيکې ټينګې کړی او د بنديز ستونزې مو هوارې کړی.
تاسو نه شی کولای چې د 'همدې کارونکي ته برېښلک لېږل ' کړنې نه ګټه پورته کړی تر څو چې تاسو د خپل کارن-حساب په [[Special:Preferences|غوره توبونو]] کې يوه کره برېښليک پته نه وي ځانګړې کړې او تر دې بريده چې پر تاسو د هغې د کارولو بنديز نه وي لګېدلی.
ستاسو د دم مهال آی پي پته ده $3، او ستاسو د مخنيوي پېژند #$5 دی. مهرباني وکړۍ د خپلې يادونې پر مهال د دغو دوو څخه د يوه او يا هم د دواړو ورکول مه هېروۍ.",
'blockednoreason'                  => 'هېڅ سبب نه دی ورکړ شوی',
'blockedoriginalsource'            => "د '''$1''' سرچينې لاندې ښودل شوي:",
'whitelistedittitle'               => 'که د سمادولو تکل لری نو بايد غونډال ته ورننوځۍ.',
'whitelistedittext'                => 'ددې لپاره چې سمادول ترسره کړی تاسو بايد $1.',
'loginreqtitle'                    => 'غونډال کې ننوتنه پکار ده',
'loginreqlink'                     => 'ننوتل',
'loginreqpagetext'                 => 'د نورو مخونو د کتلو لپاره تاسو بايد $1 وکړۍ.',
'accmailtitle'                     => 'پټنوم ولېږل شو.',
'newarticle'                       => '(نوی)',
'newarticletext'                   => "تاسو د يوه داسې تړنې څارنه کړې چې لا تر اوسه پورې شتون نه لري.
که همدا مخ ليکل غواړۍ، نو په لانديني چوکاټ کې خپل متن وټاپۍ (د لا نورو مالوماتو لپاره د [[{{MediaWiki:Helppage}}|لارښود مخ]] وګورۍ).
که چېرته تاسو دلته په غلطۍ سره راغلي ياست، نو يواځې د خپل د کتنمل '''مخ پر شا''' تڼۍ مو وټوکۍ.",
'anontalkpagetext'                 => "----''دا د بې نومه کارونکو لپاره چې کارن نوم يې نه دی جوړ کړی او يا هم خپل کارن نوم نه دی کارولی، د سکالو پاڼه ده. نو ددې پخاطر مونږ د هغه کارونکي/هغې کارونکې د انټرنېټ شمېره يا IP پته د نوموړي/نوموړې د پېژندلو لپاره کاروو. داسې يوه IP پته د ډېرو کارونکو لخوا هم کارېدلی شي. که تاسو يو بې نومه کارونکی ياست او تاسو ته نااړونده پېغامونه او تبصرې اشاره شوي، نو لطفاً د نورو بې نومو کارونکو او ستاسو ترمېنځ د ټکنتوب مخ نيونې لپاره [[Special:UserLogin|کارن-حساب جوړول يا ننوتنه]] وټوکۍ.''",
'noarticletext'                    => 'دم مهال په دې مخ کې څه نشته.
تاسې کولای شی چې په نورو مخونو کې [[Special:Search/{{PAGENAME}}|د دې مخ د سرليک پلټنه]]،
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{urlencode:{{FULLPAGENAME}}}}}} د اړوندو يادښتونو پلټنه]،
او يا [{{fullurl:{{FULLPAGENAME}}|action=edit}} همدا مخ سم کړی]</span>.',
'clearyourcache'                   => "'''يادونه:''' د غوره توبونو د خوندي کولو وروسته، ددې لپاره چې تاسو خپل سر ته رسولي ونجونه وګورۍ نو پکار ده چې د خپل بروزر ساتل شوې حافظه تازه کړی. د '''Mozilla / Firefox / Safari:''' لپاره د ''Shift'' تڼۍ نيولې وساتی کله مو چې په ''Reload''، ټک واهه، او يا هم ''Ctrl-Shift-R'' تڼۍ کېښکاږۍ (په Apple Mac کمپيوټر باندې ''Cmd-Shift-R'' کېښکاږۍ); '''IE:''' د ''Ctrl'' تڼۍ کېښکاږۍ کله مو چې په ''Refresh'' ټک واهه، او يا هم د ''Ctrl-F5'' تڼۍ کېښکاږۍ; '''Konqueror:''' بروزر کې يواځې ''Reload'' ته ټک ورکړۍ، او يا په ''F5''; د '''Opera''' کارونکو ته پکار ده چې په بشپړه توګه د خپل کمپيوټر ساتل شوې حافظه تازه کړي چې پدې توګه کېږي ''Tools→Preferences''.",
'updated'                          => '(تازه)',
'note'                             => "'''يادونه:'''",
'previewnote'                      => "'''دا يواځې مخکتنه ده، تاسو چې کوم بدلونونه ترسره کړي، لا تر اوسه پورې نه دي خوندي شوي!'''",
'editing'                          => 'د $1 سمونه',
'editingsection'                   => 'سمونه $1 (برخه)',
'editconflict'                     => 'په سمادولو کې خنډ: $1',
'yourtext'                         => 'ستاسو متن',
'yourdiff'                         => 'توپيرونه',
'copyrightwarning'                 => "لطفاً په پام کې وساتۍ چې ټولې هغه ونډې چې تاسو يې {{SITENAME}} کې ترسره کوی هغه د $2 له مخې د خپرولو لپاره ګڼل کېږي (د لانورو تفصيلاتو لپاره $1 وګورۍ). که تاسو نه غواړۍ چې ستاسې په ليکنو کې په بې رحمۍ سره لاسوهنې (سمونې) وشي او د نورو په غوښتنه پسې لانورې هم خپرې شي، نو دلته يې مه ځای پر ځای کوی..<br />
تاسو زمونږ سره دا ژمنه هم کوی چې تاسو پخپله دا ليکنه کښلې، او يا مو د ټولګړو پاڼو او يا هم ورته وړيا سرچينو نه کاپي کړې ده '''لطفاً د ليکوال د اجازې نه پرته د خوندي حقونو ليکنې مه خپروی!'''",
'longpagewarning'                  => "'''پاملرنه: همدا مخ $1 کيلوبايټه اوږد دی؛ کېدای شي چې ځينې کتنملونه د ۳۲ کيلوبايټ نه د اوږدو مخونو په سمونه کې ستونزه رامېنځ ته کړي.
لطفاً د مخ په لنډولو او په وړو برخو وېشلو باندې غور وکړی.'''",
'longpageerror'                    => "'''ستونزه: کوم متن چې دلته تاسو ليکلی، $1 کيلوبايټه اوږد دی او دا د همدې مخ د لوړترين ټاکلي بريده، $2 کيلوبايټه، څخه اوږد دی.
ستاسو متن نه شي خوندي کېدلای.'''",
'semiprotectedpagewarning'         => "'''يادونه:''' همدا مخ تړل شوی دی او يواځې ثبت شوي کارونکي کولای شي چې په دې مخ کې بدلونونه راولي.",
'titleprotectedwarning'            => "'''ګواښنه: همدا مخ تړل شوی دی او د دې د جوړولو لپاره تاسې ته د [[Special:ListGroupRights|ځانګړو رښتو]] د ترلاسه کولو اړتيا ده.'''",
'templatesused'                    => 'په دې مخ کارېدلې کينډۍ:',
'templatesusedpreview'             => 'په دې مخکتنه کې کارېدلې کينډۍ:',
'templatesusedsection'             => 'په دې برخه کارېدلي کينډۍ:',
'template-protected'               => '(ژغورل شوی)',
'template-semiprotected'           => '(نيم-ژغورلی)',
'nocreatetitle'                    => 'د مخ جوړول بريد ټاکلی دی',
'nocreatetext'                     => '{{SITENAME}} د نوو مخونو د جوړولو وړتيا محدوده کړې.
تاسو بېرته پر شا تللای شی او په شته مخونو کې سمونې ترسره کولای شی، او يا هم [[Special:UserLogin|غونډال ته ننوتلای او يو کارن-حساب جوړولای شی]].',
'nocreate-loggedin'                => 'تاسو د نوو مخونو د جوړولو اجازه نه لری.',
'permissionserrors'                => 'د اجازې ستونزې',
'permissionserrorstext-withaction' => 'تاسو د $2 اجازه نه لری، دا د دغو {{PLURAL:$1|سبب|سببونو}} پخاطر:',
'recreate-moveddeleted-warn'       => "'''ګواښنه: تاسو د يو داسې مخ بياجوړونه کوی کوم چې يو ځل پخوا ړنګ شوی وو.'''

پکار ده چې تاسو په دې ځان پوه کړی چې ايا دا تاسو ته وړ ده چې د همدې مخ سمونه په پرله پسې توګه وکړی.
ستاسو د اسانتياوو لپاره د همدې مخ د ړنګېدلو يادښت هم ورکړ شوی:",
'edit-conflict'                    => 'د سمولو خنډ',
'edit-already-exists'              => 'په دې نوم يو نوی مخ جوړ نه شو.
پدې نوم د پخوا نه يو مخ شته.',

# Account creation failure
'cantcreateaccounttitle' => 'کارن-حساب نه شي جوړېدای',

# History pages
'viewpagelogs'        => 'د همدغه مخ يادښتونه کتل',
'nohistory'           => 'ددې مخ لپاره د سمادېدنې هېڅ کوم پېښليک نه شته.',
'currentrev'          => 'اوسنۍ بڼه',
'revisionasof'        => 'د $1 پورې شته مخليدنه',
'revision-info'       => 'د $1 پورې شته مخليدنه، د $2 لخوا ترسره شوې',
'previousrevision'    => '← زړه بڼه',
'nextrevision'        => '← نوې بڼه',
'currentrevisionlink' => 'اوسنۍ بڼه',
'cur'                 => 'اوسنی',
'next'                => 'راتلونکي',
'last'                => 'وروستنی',
'page_first'          => 'لومړنی',
'page_last'           => 'وروستنی',
'histlegend'          => 'د توپير ټاکنه: د هرې هغې بڼې پرتلنه چې تاسو غواړۍ نو د هماغې بڼې چوکاټک په نښه کړی او بيا په لاندينۍ تڼۍ وټوکۍ.<br />
لنډيز: (اوس) = د اوسنۍ بڼې سره توپير،
(وروست) = د وروستۍ بڼې سره توپير، و = وړه سمونه.',
'histfirst'           => 'پخواني',
'histlast'            => 'تازه',
'historysize'         => '({{PLURAL:$1|1 بايټ|$1 بايټونه}})',
'historyempty'        => '(تش)',

# Revision feed
'history-feed-item-nocomment' => '$1 په $2',

# Revision deletion
'rev-delundel'               => 'ښکاره کول/ پټول',
'revdelete-show-file-submit' => 'هو',
'revdelete-log'              => 'د ړنګولو سبب:',
'pagehist'                   => 'د مخ پېښليک',
'deletedhist'                => 'د ړنګولو پېښليک',
'revdelete-content'          => 'مېنځپانګه',
'revdelete-summary'          => 'لنډيز سمول',
'revdelete-uname'            => 'کارن-نوم',
'revdelete-otherreason'      => 'بل/اضافي سبب:',
'revdelete-reasonotherlist'  => 'بل سبب',

# History merging
'mergehistory-from'   => 'د سرچينې مخ:',
'mergehistory-reason' => 'سبب:',

# Diffs
'history-title'           => 'د "$1" د پېښليک مخليدنه',
'difference'              => '(د بڼو تر مېنځ توپير)',
'lineno'                  => '$1 کرښه:',
'compareselectedversions' => 'ټاکلې بڼې سره پرتله کول',
'editundo'                => 'ناکړ',
'diff-multi'              => '({{PLURAL:$1|يوه منځګړې مخليدنه نه ده ښکاره شوې|$1 منځګړې مخليدنې نه دي ښکاره شوي}}.)',
'diff-movedto'            => '$1 ته ولېږدېده',
'diff-changedto'          => '$1 ته بدل شو',
'diff-src'                => 'سرچينه',
'diff-table'              => "يو '''لښتليک'''",
'diff-img'                => "يو '''انځور'''",
'diff-font'               => "'''ليکبڼه'''",

# Search results
'searchresults'                  => 'د لټون پايلې',
'searchresults-title'            => 'د "$1" د پلټنې پايلې',
'searchsubtitle'                 => 'تاسو د \'\'\'[[:$1]]\'\'\' لپاره پلټنه کړې ([[Special:Prefixindex/$1|ټول هغه مخونه چې په "$1" پېلېږي]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|ټول هغه مخونه چې "$1" سره تړنې لري]])',
'searchsubtitleinvalid'          => "تاسو د '''$1''' لپاره لټون کړی",
'noexactmatch'                   => "'''تر اوسه پورې د \"\$1\" په نوم هېڅ کوم مخ نشته.''' تاسو کولای شی چې [[:\$1|همدا مخ جوړ کړی]].",
'noexactmatch-nocreate'          => "'''د \"\$1\" په سرليک هېڅ کوم مخ نشته.'''",
'notitlematches'                 => 'د هېڅ يوه مخ سرليک ورسره ورته نه دی',
'notextmatches'                  => 'د هېڅ کوم مخ متن ورسره سمون نه خوري',
'prevn'                          => 'تېر {{PLURAL:$1|$1}}',
'nextn'                          => 'راتلونکي {{PLURAL:$1|$1}}',
'viewprevnext'                   => '($1 {{int:pipe-separator}} $2) ($3) ښکاره کول',
'searchmenu-legend'              => 'د پلټلو خوښنې',
'searchhelp-url'                 => 'Help:لړليک',
'searchprofile-images'           => 'Multimedia',
'searchprofile-articles-tooltip' => 'په $1 کې پلټل',
'searchprofile-project-tooltip'  => 'په $1 کې پلټل',
'searchprofile-images-tooltip'   => 'د دوتنو پلټنه',
'search-result-size'             => '$1 ({{PLURAL:$2|1 ويی|$2 وييونه}})',
'search-section'                 => '(برخه $1)',
'search-suggest'                 => 'آيا همدا ستاسو موخه ده: $1',
'search-interwiki-caption'       => 'خورلڼې پروژې',
'search-interwiki-default'       => '$1 پايلې:',
'search-interwiki-more'          => '(نور)',
'search-mwsuggest-enabled'       => 'د وړانديزونو سره',
'search-mwsuggest-disabled'      => 'له وړانديزونو نه پرته',
'search-relatedarticle'          => 'اړونده',
'searchrelated'                  => 'اړونده',
'searchall'                      => 'ټول',
'powersearch'                    => 'پرمختللې پلټنه',
'powersearch-legend'             => 'پرمختللې پلټنه',
'powersearch-ns'                 => 'په نوم-تشيالونو کې پلټل:',
'powersearch-field'              => 'پلټنه د',
'powersearch-toggleall'          => 'ټول',
'search-external'                => 'باندنۍ پلټنه',

# Quickbar
'qbsettings-none' => 'هېڅ',

# Preferences page
'preferences'                 => 'غوره توبونه',
'mypreferences'               => 'زما غوره توبونه',
'prefs-edits'                 => 'د سمادونو شمېر:',
'prefsnologin'                => 'غونډال کې نه ياست ننوتي',
'prefsnologintext'            => 'د دې لپاره چې خپل غوره توبونه مو وټاکی، نو پکار ده چې لومړی تاسو غونډال کې <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} ننوځی]</span>.',
'changepassword'              => 'پټنوم بدلول',
'prefs-skin'                  => 'بڼه',
'skin-preview'                => 'مخکتنه',
'prefs-math'                  => 'شمېرپوهنه',
'datedefault'                 => 'هېڅ نه ټاکل',
'prefs-datetime'              => 'نېټه او وخت',
'prefs-personal'              => 'د کارونکي پېژنليک',
'prefs-rc'                    => 'وروستي بدلونونه',
'prefs-watchlist'             => 'کتلی لړليک',
'prefs-watchlist-days'        => 'د ورځو شمېر چې په کتلي لړليک کې به ښکاري:',
'prefs-misc'                  => 'بېلابېل',
'prefs-resetpass'             => 'پټنوم بدلول',
'prefs-email'                 => 'د برېښليک خوښنې',
'prefs-rendering'             => 'ښکارېدنه',
'saveprefs'                   => 'خوندي کول',
'resetprefs'                  => 'بيا سمول',
'restoreprefs'                => 'ټولې تلواليزې امستنې پرځای کول',
'prefs-editing'               => 'سمادېدنه',
'prefs-edit-boxsize'          => 'د سمون کړکۍ کچه.',
'searchresultshead'           => 'پلټل',
'recentchangesdays'           => 'د هغو ورځو شمېر وټاکی چې په وروستي بدلونو کې يې ليدل غواړی:',
'recentchangescount'          => 'د هغو سمادونو شمېر چې په وروستي بدلونو کې يې ليدل غواړی:',
'savedprefs'                  => 'ستاسو غوره توبونه خوندي شوه.',
'timezonelegend'              => 'د وخت سيمه:',
'localtime'                   => 'سيمه ايز وخت:',
'servertime'                  => 'د پالنګر وخت:',
'timezoneregion-africa'       => 'افريقا',
'timezoneregion-america'      => 'امريکا',
'timezoneregion-antarctica'   => 'انټارکټيکا',
'timezoneregion-arctic'       => 'آرکټيک',
'timezoneregion-asia'         => 'آسيا',
'timezoneregion-atlantic'     => 'د اطلس سمندر',
'timezoneregion-australia'    => 'آسټراليا',
'timezoneregion-europe'       => 'اروپا',
'timezoneregion-indian'       => 'هندی سمندر',
'timezoneregion-pacific'      => 'آرام سمندر',
'allowemail'                  => 'د نورو کارونکو لخوا د برېښليک رالېږل چارن کړه',
'prefs-searchoptions'         => 'د پلټلو خوښنې',
'prefs-namespaces'            => 'نوم-تشيالونه',
'defaultns'                   => 'او يا هم په دغو نوم-تشيالونو کې پلټل:',
'default'                     => 'تلواليز',
'prefs-files'                 => 'دوتنې',
'youremail'                   => 'برېښليک *',
'username'                    => 'کارن-نوم:',
'uid'                         => 'د کارونکي پېژندنه:',
'prefs-memberingroups'        => 'د {{PLURAL:$1|ډلې|ډلو}} غړی:',
'prefs-registration'          => 'د نومليکنې وخت:',
'yourrealname'                => 'اصلي نوم:',
'yourlanguage'                => 'ژبه:',
'yournick'                    => 'کورنی نوم:',
'badsiglength'                => 'ستاسو لاسليک ډېر اوږد دی.
بايد چې لاسليک مو له $1 {{PLURAL:$1|توري|تورو}} نه لږ وي.',
'yourgender'                  => 'جنس:',
'gender-unknown'              => 'ناڅرګنده',
'gender-male'                 => 'نارينه',
'gender-female'               => 'ښځه',
'email'                       => 'برېښليک',
'prefs-help-realname'         => 'د اصلي نوم ليکل ستاسو په خوښه دی خو که تاسو خپل اصلي نوم وټاکۍ پدې سره به ستاسو ټول کارونه او ونډې ستاسو د نوم په اړوندولو کې وکارېږي.',
'prefs-help-email'            => 'د برېښليک ليکل ستاسو په خوښه دی، خو په ورکړې سره به يې د يوه نوي پټنوم درلېږلو چار آسانه کړي هغه هم کله چې ستاسو نه خپل پټنوم هېر شوی وي.
دا هم ستاسو خپله خوښه ده چې نور کارونکو ته اجازه ورکړی چې ستاسو سره د کارن-نوم او يا هم د کارونکي خبرې اترې لخوا، پرته له دې چې ستاسو پېژندنه وشي، اړيکې ټينګې کړي.',
'prefs-help-email-required'   => 'ستاسو د برېښليک پته پکار ده.',
'prefs-info'                  => 'بنسټيزه مالومات',
'prefs-i18n'                  => 'نړېوالتوب',
'prefs-signature'             => 'لاسليک',
'prefs-dateformat'            => 'د نېټې بڼه',
'prefs-advancedediting'       => 'پرمختللې خوښنې',
'prefs-advancedrc'            => 'پرمختللې خوښنې',
'prefs-advancedrendering'     => 'پرمختللې خوښنې',
'prefs-advancedsearchoptions' => 'پرمختللې خوښنې',
'prefs-advancedwatchlist'     => 'پرمختللې خوښنې',
'prefs-diffs'                 => 'توپيرونه',

# User rights
'userrights-user-editname' => 'يو کارن نوم وليکۍ:',
'userrights-editusergroup' => 'د کاروونکو ډلې سمادول',
'saveusergroups'           => 'د کارونکي ډلې خوندي کول',
'userrights-groupsmember'  => 'غړی د:',
'userrights-reason'        => 'د بدلون سبب:',

# Groups
'group'       => 'ډله:',
'group-user'  => 'کارونکي',
'group-sysop' => 'پازوالان',
'group-all'   => '(ټول)',

'group-user-member'  => 'کارونکی',
'group-bot-member'   => 'باټ',
'group-sysop-member' => 'پازوال',

'grouppage-sysop' => '{{ns:project}}:پازوالان',

# Rights
'right-read'          => 'مخونه لوستل',
'right-delete'        => 'مخونه ړنګول',
'right-browsearchive' => 'ړنګ شوي مخونه پلټل',

# User rights log
'rightslog'  => 'د کارونکي د رښتو يادښت',
'rightsnone' => '(هېڅ)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'          => 'همدا مخ لوستل',
'action-edit'          => 'دا مخ سمول',
'action-createpage'    => 'مخونه جوړول',
'action-move'          => 'همدا مخ لېږدول',
'action-delete'        => 'همدا مخ ړنګول',
'action-browsearchive' => 'ړنګ مخونه پلټل',
'action-undelete'      => 'همدا مخ ناړنګول',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|بدلون|بدلونونه}}',
'recentchanges'                  => 'وروستي بدلونونه',
'recentchanges-legend'           => 'د ورستي بدلونو خوښنې',
'recentchangestext'              => 'په همدې مخ باندې د ويکي ترټولو تازه وروستي بدلونونه وڅارۍ.',
'recentchanges-feed-description' => 'همدلته د ويکي ترټولو تازه وروستي بدلونونه وڅارۍ او وګورۍ چې څه پېښ شوي.',
'recentchanges-legend-newpage'   => '$1 - نوی مخ',
'recentchanges-legend-minor'     => '$1 - وړوکی سمون',
'recentchanges-label-minor'      => 'دا يوه وړه سمونه ده',
'recentchanges-legend-bot'       => '$1 - د باټ سمون',
'rcnote'                         => "دلته لاندې {{PLURAL:$1|وروستی '''1''' بدلون دی|وروستي '''$1''' بدلونونه دي}} چې په  {{PLURAL:$2| يوې ورځ|'''$2''' ورځو}} کې تر $4 نېټې او $5 بجو پېښ شوي.",
'rcnotefrom'                     => "په همدې ځای کې لاندې هغه بدلونونه دي چې د '''$2''' نه راپدېخوا پېښ شوي (تر '''$1''' پورې ښکاره شوي).",
'rclistfrom'                     => 'هغه بدلونونه ښکاره کړی چې له $1 نه پيلېږي',
'rcshowhideminor'                => 'وړې سمونې $1',
'rcshowhidebots'                 => 'باټس $1',
'rcshowhideliu'                  => 'غونډال کې ننوتي کارونکي $1',
'rcshowhideanons'                => 'بې نومه کارونکي $1',
'rcshowhidepatr'                 => '$1 څارلې سمونې',
'rcshowhidemine'                 => 'زما سمادېدنې $1',
'rclinks'                        => 'هغه وروستي $1 بدلونونه ښکاره کړی چې په $2 ورځو کې پېښ شوي<br />$3',
'diff'                           => 'توپير',
'hist'                           => 'پېښليک',
'hide'                           => 'پټول',
'show'                           => 'ښکاره کول',
'minoreditletter'                => 'و',
'newpageletter'                  => 'نوی',
'boteditletter'                  => 'باټ',
'rc_categories_any'              => 'هر يو',
'newsectionsummary'              => '/* $1 */ نوې برخه',
'rc-enhanced-hide'               => 'تفصيل پټول',

# Recent changes linked
'recentchangeslinked'          => 'اړونده بدلونونه',
'recentchangeslinked-feed'     => 'اړونده بدلونونه',
'recentchangeslinked-toolbox'  => 'اړونده بدلونونه',
'recentchangeslinked-title'    => '"$1" ته اړونده بدلونونه',
'recentchangeslinked-noresult' => 'په دې موده، په تړل شويو مخونو کې هېڅ کوم بدلونونه نه دي راپېښ شوي.',
'recentchangeslinked-summary'  => "دا د هغه بدلونونو لړليک دی چې وروستۍ ځل په تړن لرونکيو مخونو کې د يوه ځانګړي مخ (او يا هم د يوې ځانګړې وېشنيزې غړو) نه رامېنځ ته شوي.
[[Special:Watchlist|ستاسو د کتنلړليک]] مخونه په '''روڼ ليک''' کې ښکاري.",
'recentchangeslinked-page'     => 'د مخ نوم:',

# Upload
'upload'                => 'دوتنه پورته کول',
'uploadbtn'             => 'دوتنه پورته کول',
'reupload'              => 'بيا پورته کول',
'uploadnologin'         => 'غونډال کې نه ياست ننوتي',
'uploadnologintext'     => 'ددې لپاره چې دوتنې پورته کړای شۍ، تاسو ته پکار ده چې لومړی غونډال کې [[Special:UserLogin|ننوتنه]] ترسره کړی.',
'uploaderror'           => 'د پورته کولو ستونزه',
'uploadtext'            => "د دوتنو د پورته کولو لپاره د لانديني چوکاټ نه کار واخلۍ، که چېرته غواړۍ چې د پخوانيو پورته شوو انځورونو په اړه لټون وکړۍ او يا يې وکتلای شۍ نو بيا د [[Special:FileList|پورته شوو دوتنو لړليک]] ته لاړ شی، د پورته شوو دوتنو او ړنګ شوو دوتنو يادښتونه په [[Special:Log/upload|پورته شوي يادښت]] کې کتلای شی.

ددې لپاره چې يوه مخ ته انځور ورواچوی، نو بيا پدې ډول تړن (لېنک) وکاروی
'''<nowiki>[[</nowiki>Image:File.jpg<nowiki>]]</nowiki>''',
'''<nowiki>[[</nowiki>Image:File.png|alt text<nowiki>]]</nowiki>''' او يا هم د رسنيزو دوتنو لپاره د راساً تړن (لېنک) چې په دې ډول دی
'''<nowiki>[[</nowiki>Media:File.ogg<nowiki>]]</nowiki>''' وکاروی.",
'uploadlogpage'         => 'د پورته شويو دوتنو يادښت',
'uploadlogpagetext'     => 'دا لاندې د نوو پورته شوو دوتنو لړليک دی.',
'filename'              => 'د دوتنې نوم',
'filedesc'              => 'لنډيز',
'fileuploadsummary'     => 'لنډيز:',
'filereuploadsummary'   => 'د دوتنې بدلونونه:',
'filestatus'            => 'د رښتو دريځ:',
'filesource'            => 'سرچينه:',
'uploadedfiles'         => 'پورته شوې دوتنې',
'ignorewarnings'        => 'هر ډول ګواښونه له پامه غورځول',
'minlength1'            => 'پکار ده چې د دوتنو نومونه لږ تر لږه يو حرف ولري.',
'badfilename'           => 'ددغې دوتنې نوم "$1" ته واوړېده.',
'filetype-badmime'      => 'د MIME بڼې "$1" د دوتنو د پورته کولو اجازه نشته.',
'fileexists'            => "د پخوا نه پدې نوم يوه دوتنه شته، که تاسو ډاډه نه ياست او يا هم که تاسو غواړۍ چې بدلون پکې راولۍ، لطفاً '''<tt>[[:$1]]</tt>''' وګورۍ.
[[$1|thumb]]",
'fileexists-extension'  => "په همدې نوم يوه بله دوتنه د پخوا نه شته: [[$2|thumb]]
* د پورته کېدونکې دوتنې نوم: '''<tt>[[:$1]]</tt>'''
* د پخوا نه شته دوتنه: '''<tt>[[:$2]]</tt>'''
لطفاً يو داسې نوم وټاکی چې د پخوانۍ دوتنې سره توپير ولري.",
'fileexists-forbidden'  => 'د پخوا نه پدې نوم يوه دوتنه شته؛ لطفاً بېرته وګرځۍ او همدغه دوتنه بيا په يوه نوي نوم پورته کړی. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate' => 'همدا دوتنه د {{PLURAL:$1|لاندينۍ دوتنې|لاندينيو دوتنو}} غبرګه لمېسه ده:',
'savefile'              => 'دوتنه خوندي کړه',
'uploadedimage'         => '"[[$1]]" پورته شوه',
'uploaddisabled'        => 'پورته کول ناچارن شوي',
'uploadvirus'           => 'دا دوتنه ويروس لري! تفصيل: $1',
'sourcefilename'        => 'د سرچينيزې دوتنې نوم:',
'upload-maxfilesize'    => 'د دوتنې تر ټولو لويه کچه: $1',
'watchthisupload'       => 'همدا دوتنه کتل',

'upload-file-error' => 'کورنۍ ستونزه',

'nolicense'          => 'هېڅ نه دي ټاکل شوي',
'upload_source_file' => '(ستاسو په کمپيوټر کې يوه دوتنه)',

# Special:ListFiles
'listfiles_search_for'  => 'د انځور د نوم لټون:',
'imgfile'               => 'دوتنه',
'listfiles'             => 'د دوتنو لړليک',
'listfiles_date'        => 'نېټه',
'listfiles_name'        => 'نوم',
'listfiles_user'        => 'کارونکی',
'listfiles_size'        => 'کچه (bytes)',
'listfiles_description' => 'څرګندونه',

# File description page
'file-anchor-link'          => 'دوتنه',
'filehist'                  => 'د دوتنې پېښليک',
'filehist-help'             => 'په يوې نېټې/يوه وخت وټوکۍ چې د هماغه وخت او نېټې دوتنه چې په هماغه وخت کې څنګه ښکارېده هماغسې درښکاره شي.',
'filehist-deleteall'        => 'ټول ړنګول',
'filehist-deleteone'        => 'همدا ړنګول',
'filehist-revert'           => 'په څټ ګرځول',
'filehist-current'          => 'اوسنی',
'filehist-datetime'         => 'نېټه/وخت',
'filehist-thumb'            => 'بټنوک',
'filehist-user'             => 'کارونکی',
'filehist-dimensions'       => 'ډډې',
'filehist-filesize'         => 'د دوتنې کچه',
'filehist-comment'          => 'تبصره',
'imagelinks'                => 'د دوتنې تړنې',
'linkstoimage'              => 'دا {{PLURAL:$1|لاندينی مخ|$1 لانديني مخونه}} د همدې دوتنې سره تړنې لري:',
'nolinkstoimage'            => 'داسې هېڅ کوم مخ نه شته چې د دغې دوتنې سره تړنې ولري.',
'duplicatesoffile'          => 'دا لاندينۍ {{PLURAL:$1| دوتنه د همدې دوتنې غبرګونې لمېسه ده|$1 دوتنې د همدې دوتنې غبرګونې لمېسې دي}}:',
'sharedupload'              => 'دا دوتنه د $1 لخوا نه ده او کېدای شي چې نورې پروژې به يې هم کاروي.',
'uploadnewversion-linktext' => 'د همدغې دوتنې نوې بڼه پورته کول',

# File reversion
'filerevert-comment' => 'تبصره:',
'filerevert-submit'  => 'په څټ ګرځول',

# File deletion
'filedelete'                  => '$1 ړنګول',
'filedelete-legend'           => 'دوتنه ړنګول',
'filedelete-comment'          => 'تبصره:',
'filedelete-submit'           => 'ړنګول',
'filedelete-success'          => "'''$1''' ړنګ شو.",
'filedelete-otherreason'      => 'بل/اضافه سبب:',
'filedelete-reason-otherlist' => 'بل سبب',
'filedelete-reason-dropdown'  => '*د ړنګولو ټولګړی سبب
** د رښتو نه غاړه غړونه
** کټ مټ دوه ګونې دوتنه',

# MIME search
'mimesearch' => 'MIME پلټنه',
'mimetype'   => 'MIME بڼه:',
'download'   => 'ښکته کول',

# Unwatched pages
'unwatchedpages' => 'ناکتلي مخونه',

# List redirects
'listredirects' => 'د ورګرځېدنو لړليک',

# Unused templates
'unusedtemplates'    => 'نه کارېدلي کينډۍ',
'unusedtemplateswlh' => 'نور تړنونه',

# Random page
'randompage'         => 'ناټاکلی مخ',
'randompage-nopages' => 'د "$1" په نوم-تشيال کې هېڅ کوم مخ نشته.',

# Random redirect
'randomredirect' => 'ناټاکلی ورګرځېدنه',

# Statistics
'statistics'             => 'شمار',
'statistics-pages'       => 'مخونه',
'statistics-mostpopular' => 'تر ټولو ډېر کتل شوي مخونه',

'disambiguations' => 'د څرګندونې مخونه',

'doubleredirects' => 'دوه ځلي ورګرځېدنې',

'brokenredirects'        => 'ماتې ورګرځېدنې',
'brokenredirects-edit'   => 'سمول',
'brokenredirects-delete' => 'ړنګول',

'withoutinterwiki'        => 'د ژبې د تړنو بې برخې مخونه',
'withoutinterwiki-legend' => 'مختاړی',
'withoutinterwiki-submit' => 'ښکاره کول',

'fewestrevisions' => 'لږ مخليدل شوي مخونه',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|بايټ|بايټونه}}',
'ncategories'             => '$1 {{PLURAL:$1|وېشنيزه|وېشنيزې}}',
'nlinks'                  => '$1 {{PLURAL:$1|تړنه|تړنې}}',
'nmembers'                => '$1 {{PLURAL:$1|غړی|غړي}}',
'lonelypages'             => 'يتيم مخونه',
'uncategorizedpages'      => 'په وېشنيزو ناوېشلي مخونه',
'uncategorizedcategories' => 'په وېشنيزو ناوېشلې وېشنيزې',
'uncategorizedimages'     => 'په وېشنيزو ناوېشلي انځورنه',
'uncategorizedtemplates'  => 'په وېشنيزو ناوېشلې کينډۍ',
'unusedcategories'        => 'ناکارېدلې وېشنيزې',
'unusedimages'            => 'ناکارېدلې دوتنې',
'popularpages'            => 'نامتو مخونه',
'wantedcategories'        => 'غوښتلې وېشنيزې',
'wantedpages'             => 'غوښتل شوې پاڼې',
'wantedfiles'             => 'غوښتلې دوتنې',
'wantedtemplates'         => 'غوښتلې کينډۍ',
'mostlinked'              => 'د ډېرو تړنو مخونه',
'mostlinkedcategories'    => 'د ګڼو تړنو وېشنيزې',
'mostlinkedtemplates'     => 'د ډېرو تړنو کينډۍ',
'mostcategories'          => 'د ګڼو وېشنيزو مخونه',
'mostimages'              => 'د ډېرو تړنو انځورونه',
'mostrevisions'           => 'ډېر کتلي مخونه',
'prefixindex'             => 'د مختاړيو ټول مخونه',
'shortpages'              => 'لنډ مخونه',
'longpages'               => 'اوږده مخونه',
'deadendpages'            => 'بې پايه مخونه',
'deadendpagestext'        => 'همدا لانديني مخونه په دغه ويکي کې د نورو مخونو سره تړنې نه لري.',
'protectedpages'          => 'ژغورلي مخونه',
'protectedtitles'         => 'ژغورلي سرليکونه',
'listusers'               => 'د کارونکو لړليک',
'newpages'                => 'نوي مخونه',
'newpages-username'       => 'کارن-نوم:',
'ancientpages'            => 'تر ټولو زاړه مخونه',
'move'                    => 'لېږدول',
'movethispage'            => 'دا مخ ولېږدوه',
'pager-newer-n'           => '{{PLURAL:$1|نوی 1|نوي $1}}',
'pager-older-n'           => '{{PLURAL:$1|زوړ 1|زاړه $1}}',

# Book sources
'booksources'               => 'د کتاب سرچينې',
'booksources-search-legend' => 'د کتابي سرچينو لټون وکړۍ',
'booksources-go'            => 'ورځه',
'booksources-text'          => 'دا لاندې د هغه وېبځايونو د تړنو لړليک دی چېرته چې نوي او زاړه کتابونه پلورل کېږي، او يا هم کېدای شي چې د هغه کتاب په هکله مالومات ولري کوم چې تاسو ورپسې لټېږۍ:',

# Special:Log
'specialloguserlabel'  => 'کارونکی:',
'speciallogtitlelabel' => 'سرليک:',
'log'                  => 'يادښتونه',
'all-logs-page'        => 'ټول عام يادښتونه',

# Special:AllPages
'allpages'          => 'ټول مخونه',
'alphaindexline'    => '$1 نه تر $2 پورې',
'nextpage'          => 'بل مخ ($1)',
'prevpage'          => 'تېر مخ ($1)',
'allpagesfrom'      => 'ښکاره دې شي هغه مخونه چې پېلېږي په:',
'allpagesto'        => 'هغه مخونه ښکاره کول چې پای يې وي:',
'allarticles'       => 'ټول مخونه',
'allinnamespace'    => 'ټول مخونه ($1 نوم-تشيال)',
'allnotinnamespace' => 'ټولې پاڼې (د $1 په نوم-تشيال کې نشته)',
'allpagesprev'      => 'پخواني',
'allpagesnext'      => 'راتلونکي',
'allpagessubmit'    => 'ورځه',
'allpagesprefix'    => 'هغه مخونه ښکاره کړه چې مختاړی يې وي:',
'allpagesbadtitle'  => 'ورکړ شوی سرليک سم نه دی او يا هم د ژبو او يا د بېلابېلو ويکي ګانو مختاړی لري. ستاسو په سرليک کې يو يا څو داسې ابېڅې دي کوم چې په سرليک کې نه شي کارېدلی.',
'allpages-bad-ns'   => '{{SITENAME}} د "$1" په نامه هېڅ کوم نوم-تشيال نه لري.',

# Special:Categories
'categories'                  => 'وېشنيزې',
'categoriespagetext'          => 'دا لاندينۍ {{PLURAL:$1|وېشنيزه|وېشنيزې}} مخونه يا رسنيزې دوتنې لري.
دلته [[Special:UnusedCategories|ناکارېدلې وېشنيزې]] نه دي ښکاره شوي.
[[Special:WantedCategories|غوښتلې وېشنيزې]] هم وګورۍ.',
'categoriesfrom'              => 'هغه وېشنيزې دې ښکاره شي چې پېلېږي په:',
'special-categories-sort-abc' => 'د ابېڅو له مخې اوډل',

# Special:DeletedContributions
'deletedcontributions'             => 'د کارونکي ونډې ړنګې شوې',
'deletedcontributions-title'       => 'د کارونکي ونډې ړنګې شوې',
'sp-deletedcontributions-contribs' => 'ونډې',

# Special:LinkSearch
'linksearch'      => 'باندنۍ تړنې',
'linksearch-pat'  => 'د پلټنې مخبېلګه:',
'linksearch-ns'   => 'نوم-تشيال:',
'linksearch-ok'   => 'پلټل',
'linksearch-line' => '$1 د $2 سره تړل شوی',

# Special:ListUsers
'listusersfrom'      => 'هغه کارونکي ښکاره کړه چې نومونه يې پېلېږي په:',
'listusers-submit'   => 'ښکاره کول',
'listusers-noresult' => 'هېڅ کوم کارونکی و نه موندل شو.',

# Special:ActiveUsers
'activeusers' => 'د فعالو کارنانو لړليک',

# Special:Log/newusers
'newuserlogpage'              => 'د کارن-نوم د جوړېدو يادښت',
'newuserlogpagetext'          => 'دا د کارن-نوم د جوړېدو يادښت دی',
'newuserlog-byemail'          => 'پټنوم مو برېښليک ته درولېږه',
'newuserlog-create-entry'     => 'نوی کارونکی',
'newuserlog-create2-entry'    => 'نوی جوړ شوی کارن-حساب $1',
'newuserlog-autocreate-entry' => 'کارن-حساب په اتوماتيک ډول جوړ شو',

# Special:ListGroupRights
'listgrouprights-group'        => 'ډله',
'listgrouprights-rights'       => 'رښتې',
'listgrouprights-members'      => '(د غړو لړليک)',
'listgrouprights-addgroup-all' => 'ټولې ډلې ورګډول',

# E-mail user
'mailnologin'     => 'هېڅ کومه لېږل شوې پته نشته',
'emailuser'       => 'دغه کارونکي ته برېښليک لېږل',
'emailpage'       => 'کارونکي ته برېښليک ولېږه',
'defemailsubject' => 'د {{SITENAME}} برېښليک',
'noemailtitle'    => 'هېڅ کومه برېښليک پته نشته.',
'emailfrom'       => 'لېږونکی',
'emailto'         => 'اخيستونکی',
'emailsubject'    => 'سکالو:',
'emailmessage'    => 'پيغام:',
'emailsend'       => 'لېږل',
'emailccme'       => 'زما د پيغام يوه بېلګه دې ماته هم برېښليک شي.',
'emailccsubject'  => '$1 ته ستاسو د پيغام لمېسه: $2',
'emailsent'       => 'برېښليک مو ولېږل شو',
'emailsenttext'   => 'ستاسو برېښليکي پيغام ولېږل شو.',
'emailuserfooter' => 'همدا برېښليک د $1 لخوا $2 ته د {{SITENAME}} په وېبځي کې د "همدې کارونکي ته برېښليک لېږل" د کړنې په مرسته لېږل شوی دی.',

# Watchlist
'watchlist'            => 'زما کتنلړليک',
'mywatchlist'          => 'زما کتنلړليک',
'watchlistfor'         => "(د '''$1''')",
'nowatchlist'          => 'ستاسو په کتلي لړليک کې هېڅ نه شته.',
'watchnologin'         => 'غونډال کې نه ياست ننوتي.',
'watchnologintext'     => 'ددې لپاره چې خپل کتل شوي لړليک کې بدلون راولی نو تاسو ته پکار ده چې لومړی غونډال کې [[Special:UserLogin|ننوتنه]] ترسره کړی.',
'addedwatch'           => 'په کتنلړليک کې ورګډ شو.',
'addedwatchtext'       => "د \"[[:\$1]]\" په نوم يو مخ ستاسو [[Special:Watchlist|کتنلړليک]] کې ورګډ شو.
په راتلونکې کې چې په دغه مخ او د ده د خبرواترو مخ کې کوم بدلونونه راځي نو هغه به ستاسو په کتنلړليک کې ښکاره شي،
او په همدې توګه هغه مخونه به د [[Special:RecentChanges|وروستي بدلونونو]] په لړليک کې په '''روڼ''' ليک ښکاري ترڅو په اسانۍ سره څوک وپوهېږي چې په کوم کوم مخونو کې بدلونونه ترسره شوي.

که چېرته تاسو بيا وروسته غواړۍ چې کوم مخ د خپل کتنلړليک نه ليرې کړۍ، نو په \"نه کتل\" تڼۍ باندې ټک ورکړۍ.",
'removedwatch'         => 'د کتنلړليک نه لرې شو',
'removedwatchtext'     => 'د "[[:$1]]" په نامه مخ ستاسو له کتنلړليک نه لرې شو.',
'watch'                => 'کتل',
'watchthispage'        => 'همدا مخ کتل',
'unwatch'              => 'نه کتل',
'watchlist-details'    => 'ستاسو په کتنلړليک کې {{PLURAL:$1|$1 مخ دی|$1 مخونه دي}}، د خبرو اترو مخونه مو پکې نه دي شمېرلي.',
'wlheader-enotif'      => 'د برېښليک له لارې خبرول چارن شوی.*',
'wlheader-showupdated' => "* هغه مخونه چې وروستی ځل ستاسو د کتلو نه وروسته بدلون موندلی په '''روڼ''' ليک نښه شوي.",
'wlshowlast'           => 'وروستي $1 ساعتونه $2 ورځې $3 ښکاره کړه',
'watchlist-options'    => 'د کتنلړليک خوښنې',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'د کتلو په حال کې...',
'unwatching' => 'د نه کتلو په حال کې...',

'enotif_newpagetext'           => 'دا يوه نوې پاڼه ده.',
'enotif_impersonal_salutation' => '{{SITENAME}} کارونکی',
'changed'                      => 'بدل شو',
'created'                      => 'جوړ شو',
'enotif_lastvisited'           => 'د ټولو هغو بدلونونو د کتلو لپاره چې ستاسو د وروستي ځل راتګ نه وروسته پېښې شوي، $1 وګورۍ.',
'enotif_lastdiff'              => 'د همدغه بدلون د کتلو لپاره $1 وګورۍ.',
'enotif_anon_editor'           => 'ورکنومی کارونکی $1',

# Delete
'deletepage'            => 'پاڼه ړنګول',
'confirm'               => 'تاييد',
'exblank'               => 'دا مخ تش وه',
'delete-confirm'        => '"$1" ړنګوول',
'delete-legend'         => 'ړنګول',
'historywarning'        => 'پاملرنه: کومه پاڼه چې تاسو يې د ړنګولو هڅه کوی يو پېښليک لري:',
'confirmdeletetext'     => 'تاسو د تل لپار يو مخ يا انځور د هغه ټول پېښليک سره د دغه ډېټابېز نه ړنګوۍ. که چېرته تاسو ددغې کړنې په پايلې پوه ياست او د دغې پاڼې د [[{{MediaWiki:Policy-url}}|تګلارې]] سره سمون خوري نو لطفاً ددغې کړنې تاييد وکړی .',
'actioncomplete'        => 'بشپړه کړنه',
'deletedtext'           => '"<nowiki>$1</nowiki>" ړنګ شوی.
د نوو ړنګ شوو سوانحو لپاره $2 وګورۍ.',
'deletedarticle'        => 'ړنګ شو "[[$1]]"',
'dellogpage'            => 'د ړنګولو يادښت',
'dellogpagetext'        => 'دا لاندې د نوو ړنګ شوو کړنو لړليک دی.',
'deletionlog'           => 'د ړنګولو يادښت',
'deletecomment'         => 'د ړنګولو سبب',
'deleteotherreason'     => 'بل/اضافه سبب:',
'deletereasonotherlist' => 'بل سبب',
'deletereason-dropdown' => '*د ړنګولو ټولګړی سبب
** د ليکوال غوښتنه
** د رښتو تېری
** د پوهې سره دښمني',

# Rollback
'rollback_short' => 'په شابېول',
'rollbacklink'   => 'په شابېول',

# Protect
'protectlogpage'              => 'د ژغورنې يادښت',
'protectedarticle'            => '"[[$1]]" وژغورلی شو',
'modifiedarticleprotection'   => 'د "[[$1]]" لپاره د ژغورنې کچه بدله شوه',
'protect-title'               => 'د "$1" لپاره د ژغورنې کچه بدلول',
'prot_1movedto2'              => '[[$1]]، [[$2]] ته ولېږدېده',
'protect-legend'              => 'د ژغورلو پخلی کول',
'protectcomment'              => 'سبب:',
'protectexpiry'               => 'د پای نېټه:',
'protect_expiry_invalid'      => 'د پای وخت ناسم دی.',
'protect_expiry_old'          => 'د پای وخت په تېرمهال کې دی.',
'protect-unchain'             => 'د لېږدون اجازې ناتړل',
'protect-text'                => "تاسو کولای شی چې د '''<nowiki>$1</nowiki>''' مخ لپاره د ژغورلو کچه همدلته وګورۍ او بدلون پکې راولی.",
'protect-locked-access'       => "ستاسو کارن-حساب دا اجازه نه لري چې د پاڼو د ژغورنې په کچه کې بدلون راولي.
دلته د '''$1''' مخ لپاره اوسني شته امستنې دي:",
'protect-cascadeon'           => 'د اوسمهال لپاره همدا مخ ژغورل شوی دا ځکه چې همدا مخ په {{PLURAL:$1|لانديني مخ|لانديني مخونو}} کې ورګډ دی چې {{PLURAL:$1|ځوړاوبيزه ژغورنه يې چارنه ده|ځوړاوبيزې ژغورنې يې چارنې دي}}.
تاسو د همدې مخ د ژغورنې په کچه کې بدلون راوستلای شی، خو دا به په ځوړاوبيزه ژغورنه اغېزمنه نه کړي.',
'protect-default'             => 'ټول کارونکي پرېښودل',
'protect-fallback'            => 'د "$1" اجازه پکار ده',
'protect-level-autoconfirmed' => 'د نويو او ناثبته کارونکو مخه نيول',
'protect-level-sysop'         => 'يواځې پازوالان',
'protect-summary-cascade'     => 'ځوړاوبيز',
'protect-expiring'            => 'په $1 (UTC) پای ته رسېږي',
'protect-expiry-indefinite'   => 'لامحدوده',
'protect-cascade'             => 'په همدې مخ کې د ټولو ګډو مخونو نه ژغورنه کېږي (ځوړاوبيزه ژغورنه)',
'protect-cantedit'            => 'تاسو نه شی کولای چې د همدغه مخ د ژغورنې په کچه کې بدلون راولی، دا ځکه چې تاسو د همدغه مخ د سمولو اجازه نه لری.',
'protect-othertime'           => 'بل وخت:',
'protect-othertime-op'        => 'بل وخت',
'protect-otherreason'         => 'بل/اضافي سبب:',
'protect-otherreason-op'      => 'بل/اضافي سبب',
'protect-dropdown'            => '*د ژغورلو عام سببونه
** ډېره زياته ورانکاري
** ډېره زياته سپام خپرونه
** بې ګټې سمونې او خپرونې
** ډېر لوستونکی مخ',
'protect-expiry-options'      => '1 ساعت:1 hour,1 ورځ:1 day,1 اوونۍ:1 week,2 اوونۍ:2 weeks,1 مياشت:1 month,3 مياشتې:3 months,6 مياشتې:6 months,1 کال:1 year,لامحدوده:infinite',
'restriction-type'            => 'اجازه:',
'restriction-level'           => 'د بنديز کچه:',
'minimum-size'                => 'وړه کچه',
'pagesize'                    => '(بايټونه)',

# Restrictions (nouns)
'restriction-edit'   => 'سمون',
'restriction-move'   => 'لېږدول',
'restriction-create' => 'جوړول',

# Restriction levels
'restriction-level-sysop'         => 'بشپړ ژغورلی',
'restriction-level-autoconfirmed' => 'نيم ژغورلی',

# Undelete
'undelete'                  => 'ړنګ شوي مخونه کتل',
'undeletepage'              => 'ړنګ شوي مخونه کتل او بېرته پرځای کول',
'viewdeletedpage'           => 'ړنګ شوي مخونه کتل',
'undeletebtn'               => 'بېرته پرځای کول',
'undeletelink'              => 'کتل/بيا پر ځای کول',
'undeleteviewlink'          => 'کتل',
'undeletereset'             => 'بياايښودل',
'undeletecomment'           => 'تبصره:',
'undeletedarticle'          => '"[[$1]]" بېرته پرځای شو',
'undelete-search-box'       => 'ړنګ شوي مخونه لټول',
'undelete-search-prefix'    => 'هغه مخونه ښکاره کړه چې پېلېږي په:',
'undelete-search-submit'    => 'پلټل',
'undelete-show-file-submit' => 'هو',

# Namespace form on various pages
'namespace'      => 'نوم-تشيال:',
'invert'         => 'خوښونې سرچپه کول',
'blanknamespace' => '(اصلي)',

# Contributions
'contributions'       => 'د کارونکي ونډې',
'contributions-title' => 'د $1 کارن ونډې',
'mycontris'           => 'زما ونډې',
'contribsub2'         => 'د $1 لپاره ($2)',
'uctop'               => '(سرپاڼه)',
'month'               => 'له ټاکلې مياشتې نه راپدېخوا (او تر دې پخواني):',
'year'                => 'له ټاکلي کال نه راپدېخوا (او تر دې پخواني):',

'sp-contributions-newbies'     => 'د نوو کارن-حسابونو ونډې ښکاره کول',
'sp-contributions-newbies-sub' => 'د نوو کارن-حسابونو لپاره',
'sp-contributions-blocklog'    => 'د مخنيوي يادښت',
'sp-contributions-deleted'     => 'د کارن ونډې ړنګې شوې',
'sp-contributions-logs'        => 'يادښتونه',
'sp-contributions-talk'        => 'خبرې اترې',
'sp-contributions-search'      => 'د ونډو لټون',
'sp-contributions-username'    => 'IP پته يا کارن-نوم:',
'sp-contributions-submit'      => 'پلټل',

# What links here
'whatlinkshere'           => 'د همدې پاڼې تړنې',
'whatlinkshere-title'     => 'هغه مخونه چې د "$1" سره تړنې لري',
'whatlinkshere-page'      => 'مخ:',
'linkshere'               => "دغه لانديني مخونه د '''[[:$1]]''' سره تړنې لري:",
'nolinkshere'             => "د '''[[:$1]]''' سره هېڅ يو مخ هم تړنې نه لري .",
'isredirect'              => 'ورګرځېدلی مخ',
'istemplate'              => 'ورګډېدنه',
'isimage'                 => 'د انځور تړنه',
'whatlinkshere-prev'      => '{{PLURAL:$1|پخوانی|پخواني $1}}',
'whatlinkshere-next'      => '{{PLURAL:$1|راتلونکی|راتلونکي $1}}',
'whatlinkshere-links'     => '← تړنې',
'whatlinkshere-hidelinks' => '$1 تړنې',
'whatlinkshere-filters'   => 'چاڼګرونه',

# Block/unblock
'blockip'                  => 'د کاروونکي مخه نيول',
'ipaddress'                => 'IP پته',
'ipadressorusername'       => 'IP پته يا کارن نوم',
'ipbexpiry'                => 'د پای نېټه:',
'ipbreason'                => 'سبب',
'ipbreasonotherlist'       => 'بل لامل',
'ipbother'                 => 'بل وخت:',
'ipboptions'               => '2 ساعتونه:2 hours,1 ورځ:1 day,3 ورځې:3 days,1 اوونۍ:1 week,2 اوونۍ:2 weeks,1 مياشت:1 month,3 مياشتې:3 months,6 مياشتې:6 months,1 کال:1 year,لامحدوده:infinite',
'ipbotheroption'           => 'نور',
'ipbotherreason'           => 'بل/اضافه سبب:',
'badipaddress'             => 'ناسمه IP پته',
'blockipsuccesssub'        => 'مخنيوی په برياليتوب سره ترسره شو',
'blockipsuccesstext'       => 'د [[Special:Contributions/$1|$1]] مخه نيول شوې.
<br />د مخنيول شويو خلکو د کتنې لپاره، د [[Special:IPBlockList|مخنيول شويو IP لړليک]] وګورۍ.',
'ipblocklist'              => 'د مخنيول شويو آی پي پتو او کارن نومونو لړليک',
'ipblocklist-username'     => 'کارن-نوم يا IP پته:',
'ipblocklist-submit'       => 'پلټل',
'infiniteblock'            => 'لامحدوده',
'anononlyblock'            => 'يواځې ورکنومی',
'blocklink'                => 'مخه نيول',
'unblocklink'              => 'نامخنيول',
'contribslink'             => 'ونډې',
'autoblocker'              => 'په اتوماتيک ډول ستاسو مخنيوی شوی دا ځکه چې ستاسو IP پته وروستی ځل د "[[User:$1|$1]]" له خوا کارېدلې. او د $1 د مخنيوي سبب دا دی: "$2"',
'blocklogpage'             => 'د مخنيوي يادښت',
'blocklogentry'            => 'د [[$1]] مخنيوی شوی چې د بنديز د پای وخت يې $2 $3 دی',
'block-log-flags-anononly' => 'يواځې ورکنومي کارونکي',
'block-log-flags-nocreate' => 'د کارن-حساب جوړول ناچارن شوې',
'block-log-flags-noemail'  => 'ددې برېښليک مخه نيول شوی',
'proxyblocksuccess'        => 'ترسره شو.',

# Move page
'move-page-legend'        => 'مخ لېږدول',
'movepagetext'            => "د لاندينۍ فورمې په کارولو سره تاسو د يوه مخ نوم بدلولی شی، چې په همدې توګه به د يوه مخ ټول پېښليک د هغه د نوي نوم سرليک ته ولېږدېږي.
د يوه مخ، پخوانی نوم به د نوي نوم ورګرځونکی مخ وګرځي او نوي سرليک ته به وګرځولی شي.
هغه تړنې چې په زاړه مخ کې دي په هغو کې به هېڅ کوم بدلون را نه شي;
[[Special:BrokenRedirects|د ماتو ورګرځونو]] يا [[Special:DoubleRedirects|دوه ځله ورګرځونو]] د ستونزو د پېښېدو په خاطر ځان ډاډه کړی چې ستاسې ورګرځونې ماتې يا دوه ځله نه وي.
دا ستاسو پازه ده چې ځان په دې هم ډاډمن کړی چې آيا هغه تړنې کوم چې د يو مخ سره پکار دي چې وي، همداسې په پرله پسې توګه پېيلي او خپل موخن ځايونو سره اړونده دي.

په ياد مو اوسه چې يو مخ به '''هېڅکله''' و نه لېږدېږي که چېرته د پخوا نه په هماغه نوم يو مخ شتون ولري، خو که چېرته يو مخ تش وه او يا هم يو ورګرځېدلی مخ وه چې پېښليک کې يې بدلون نه وي راغلی. نو دا په دې مانا ده چې تاسو کولای شی چې د يو مخ نوم بدل کړی بېرته هماغه پخواني نوم ته چې د پخوا نه يې درلوده، که چېرته تاسو تېرووځۍ نو په داسې حال کې تاسو نه شی کولای چې د يوه مخ پر سر يو څه وليکۍ.

'''ګواښنه!'''
يوه نوي نوم ته د مخونو د نوم بدلون کېدای شي چې په نامتو مخونو کې بنسټيزه او نه اټکل کېدونکی بدلونونه رامېنځ ته کړي;
مخکې له دې نه چې پرمخ ولاړ شی، مهرباني وکړۍ لومړی خپل ځان په دې ډاډه کړی چې تاسو ددغې کړنې په پايلو ښه پوهېږۍ.",
'movepagetalktext'        => "همدې مخ ته اړونده د خبرواترو مخ هم په اتوماتيک ډول لېږدول کېږي '''خو که چېرته:'''
*په نوي نوم د پخوا نه د خبرواترو يو مخ شتون ولري، او يا هم
*تاسو ته لاندې ورکړ شوی څلورڅنډی په نښه شوی وي.

نو په هغه وخت کې پکار ده چې د خبرواترو د مخ لېږدونه او د نوي مخ سره د يوځای کولو کړنه په لاسي توګه ترسره کړی.",
'movearticle'             => 'مخ لېږدول',
'movenologin'             => 'غونډال کې نه ياست ننوتي',
'movenologintext'         => 'ددې لپاره چې يو مخ ولېږدوی، نو تاسو بايد يو ثبت شوی کارونکی او غونډال کې [[Special:UserLogin|ننوتي]] اوسۍ.',
'newtitle'                => 'يو نوي سرليک ته:',
'move-watch'              => 'همدا مخ کتل',
'movepagebtn'             => 'مخ لېږدول',
'pagemovedsub'            => 'لېږدول په برياليتوب سره ترسره شوه',
'movepage-moved'          => '<big>\'\'\'د "$1" په نامه دوتنه، "$2" ته ولېږدېده\'\'\'</big>',
'articleexists'           => 'په همدې نوم يوه بله پاڼه د پخوا نه شته او يا خو دا نوم چې تاسو ټاکلی سم نه دی. لطفاً يو بل نوم وټاکۍ.',
'talkexists'              => "'''همدا مخ په برياليتوب سره نوي سرليک ته ولېږدېده، خو د خبرواترو مخ يې و نه لېږدول شو دا ځکه چې نوی سرليک له پخوا نه ځانته د خبرواترو يو مخ لري.
مهرباني وکړۍ د خبرواترو دا دواړه مخونه په لاسي توګه سره يو ځای کړی.'''",
'movedto'                 => 'ته ولېږدول شو',
'movetalk'                => 'د خبرو اترو اړونده مخ ورسره لېږدول',
'1movedto2'               => '[[$1]]، [[$2]] ته ولېږدېده',
'movelogpage'             => 'د لېږدولو يادښت',
'movelogpagetext'         => 'دا لاندې د لېږدول شوو مخونو لړليک دی.',
'movereason'              => 'سبب',
'revertmove'              => 'په څټ ګرځول',
'delete_and_move'         => 'ړنګول او لېږدول',
'delete_and_move_confirm' => 'هو, دا مخ ړنګ کړه',
'immobile-source-page'    => 'دا مخ نه لېږدېدنونکی دی',
'imageinvalidfilename'    => 'د موخنې دوتنې نوم سم نه دی',

# Export
'export'            => 'مخونه صادرول',
'export-addcattext' => 'مخونو د ورګډولو وېشنيزه:',
'export-addcat'     => 'ورګډول',
'export-addnstext'  => 'د نوم-تشيال نه مخونه ورګډول:',
'export-addns'      => 'ورګډول',
'export-download'   => 'د دوتنې په بڼه خوندي کول',

# Namespace 8 related
'allmessages'               => 'د غونډال پيغامونه',
'allmessagesname'           => 'نوم',
'allmessagesdefault'        => 'ټاکل شوی متن',
'allmessagescurrent'        => 'اوسنی متن',
'allmessagestext'           => 'دا د مېډياويکي په نوم-تشيال کې د غونډال د پيغامونو لړليک دی.
که چېرته تاسو د ميډياويکي په ځايتابه کې ونډې ترسره کول غواړۍ نو لطفاً [http://www.mediawiki.org/wiki/Localisation د ويډياويکي ځايتابه] او [http://translatewiki.net translatewiki.net] نه ليدنه وکړۍ.',
'allmessagesnotsupportedDB' => "'''Special:Allmessages''' ترېنه کار نه اخيستل کېږي ځکه چې '''\$wgUseDatabaseMessages''' مړ دی.",
'allmessages-filter-legend' => 'چاڼګر',
'allmessages-filter-all'    => 'ټول',
'allmessages-language'      => 'ژبه:',
'allmessages-filter-submit' => 'ورځه',

# Thumbnails
'thumbnail-more'  => 'لويول',
'filemissing'     => 'دوتنه ورکه ده',
'thumbnail_error' => 'د  بټنوک د جوړېدنې ستونزه: $1',

# Special:Import
'import-interwiki-source'    => 'سرچينيز ويکي/مخ:',
'import-interwiki-templates' => 'ټولې کينډۍ پکې شمېرل',
'import-upload-filename'     => 'د دوتنې نوم:',
'import-comment'             => 'تبصره:',

# Import log
'importlogpage' => 'د واردولو يادښت',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'ستاسو کارن مخ',
'tooltip-pt-mytalk'               => 'ستاسو د خبرواترو مخ',
'tooltip-pt-preferences'          => 'زما غوره توبونه',
'tooltip-pt-watchlist'            => 'د هغه مخونو لړليک چې تاسو يې د بدلون لپاره څاری',
'tooltip-pt-mycontris'            => 'ستاسو د ونډو لړليک',
'tooltip-pt-login'                => 'تاسو ته په غونډال کې د ننوتلو سپارښتنه کوو، که څه هم چې دا يو اړين کار نه دی.',
'tooltip-pt-anonlogin'            => 'تاسو ته په غونډال کې د ننوتنې سپارښتنه کوو، که څه هم چې دا يو اړين کار نه دی.',
'tooltip-pt-logout'               => 'وتل',
'tooltip-ca-talk'                 => 'د مخ د مېنځپانګې په اړه خبرې اترې',
'tooltip-ca-edit'                 => 'تاسو همدا مخ سمولای شی. مهرباني وکړی د ليکنې د خوندي کولو دمخه مو د همدې ليکنې مخکتنه وګورۍ.',
'tooltip-ca-addsection'           => 'يوه نوې برخه پيلول',
'tooltip-ca-viewsource'           => 'همدا مخ ژغورل شوی. تاسو کولای شی چې د همدې مخ سرجينه وګورۍ.',
'tooltip-ca-history'              => 'د دې مخ پخوانۍ مخليدنې',
'tooltip-ca-protect'              => 'همدا مخ ژغورل',
'tooltip-ca-unprotect'            => 'همدا مخ ناژغورل',
'tooltip-ca-delete'               => 'همدا مخ ړنګول',
'tooltip-ca-move'                 => 'همدا مخ لېږدول',
'tooltip-ca-watch'                => 'دا مخ پخپل کتنلړليک کې ګډول',
'tooltip-ca-unwatch'              => 'همدا مخ خپل کتنلړليک نه لرې کول',
'tooltip-search'                  => 'د {{SITENAME}} لټون',
'tooltip-search-go'               => 'که په همدې نوم کټ مټ مخ وي، نو هماغه يوه مخ ته ورځه',
'tooltip-search-fulltext'         => 'په مخونو کې دا متن وپلټه',
'tooltip-p-logo'                  => 'لومړی مخ',
'tooltip-n-mainpage'              => 'لومړي مخ ته ورتلل',
'tooltip-n-mainpage-description'  => 'اصلي مخ کتل',
'tooltip-n-portal'                => 'د پروژې په اړه، تاسو څه کولای شی، چېرته کولای شی چې شيان ومومۍ',
'tooltip-n-currentevents'         => 'د اوسنيو پېښو اړونده د هغوی د شاليد مالومات موندل',
'tooltip-n-recentchanges'         => 'په ويکي کې د وروستي بدلونو لړليک.',
'tooltip-n-randompage'            => 'يو ناټاکلی مخ ښکاره کوي',
'tooltip-n-help'                  => 'هغه ځای چېرته چې راڅرګندولای شو.',
'tooltip-t-whatlinkshere'         => 'د ويکي د ټولو هغو مخونو لړليک چې دلته تړنې لري',
'tooltip-feed-rss'                => 'د همدې مخ د آر اس اس کتنه',
'tooltip-feed-atom'               => 'د دې مخ د اټوم کتنې',
'tooltip-t-contributions'         => 'د همدې کارونکي د ونډو لړليک کتل',
'tooltip-t-emailuser'             => 'همدې کارونکي ته يو برېښليک لېږل',
'tooltip-t-upload'                => 'دوتنې پورته کول',
'tooltip-t-specialpages'          => 'د ټولو ځانګړو پاڼو لړليک',
'tooltip-t-print'                 => 'د همدې مخ چاپي بڼه',
'tooltip-t-permalink'             => 'د دې مخ د مخليدنې تلپاتې تړنه',
'tooltip-ca-nstab-main'           => 'د مخ مېنځپانګه کتل',
'tooltip-ca-nstab-user'           => 'د کارونکي مخ کتل',
'tooltip-ca-nstab-media'          => 'د رسنۍ مخ کتل',
'tooltip-ca-nstab-special'        => 'همدا يو ځانګړی مخ دی، تاسو نه شی کولای چې دا مخ سماد کړی.',
'tooltip-ca-nstab-project'        => 'د پروژې مخ کتل',
'tooltip-ca-nstab-image'          => 'د دوتنې مخ کتل',
'tooltip-ca-nstab-mediawiki'      => 'د غونډال پيغامونه ښکاره کول',
'tooltip-ca-nstab-template'       => 'کينډۍ ښکاره کول',
'tooltip-ca-nstab-help'           => 'د لارښود مخ کتل',
'tooltip-ca-nstab-category'       => 'د وېشنيزې مخ ښکاره کول',
'tooltip-minoredit'               => 'دا لکه يوه وړه سمونه په نښه کوي[alt-i]',
'tooltip-save'                    => 'ستاسو بدلونونه خوندي کوي',
'tooltip-preview'                 => 'ستاسو بدلونونه ښکاره کوي, لطفاً دا کړنه د خوندي کولو دمخه وکاروۍ! [alt-p]',
'tooltip-diff'                    => 'دا هغه بدلونونه چې تاسو په متن کې ترسره کړي، ښکاره کوي. [alt-v]',
'tooltip-compareselectedversions' => 'د همدې مخ د دوو ټاکل شويو بڼو تر مېنځ توپيرونه وګورۍ.',
'tooltip-watch'                   => 'همدا مخ ستاسو کتنلړليک کې ورګډوي [alt-w]',

# Attribution
'siteuser'         => 'د {{SITENAME}} کارن $1',
'lastmodifiedatby' => 'دا مخ وروستی ځل د $3 لخوا په $2، $1 بدلون موندلی.',
'others'           => 'نور',
'siteusers'        => 'د {{SITENAME}} {{PLURAL:$2|کارن|کارنان}} $1',

# Info page
'infosubtitle' => 'د مخ مالومات',

# Skin names
'skinname-standard'    => 'کلاسيک',
'skinname-nostalgia'   => 'نوستالژي',
'skinname-cologneblue' => 'شين کلون',
'skinname-monobook'    => 'مونوبوک',
'skinname-myskin'      => 'زمابڼه',
'skinname-chick'       => 'شيک',
'skinname-simple'      => 'ساده',
'skinname-modern'      => 'نوی',

# Math errors
'math_unknown_error'    => 'ناجوته ستونزه',
'math_unknown_function' => 'ناجوته کړنه',

# Patrol log
'patrol-log-auto' => '(خپلسر)',

# Image deletion
'filedeleteerror-short' => 'د دوتنې د ړنګولو ستونزه: $1',

# Browsing diffs
'previousdiff' => 'تېر توپير ←',
'nextdiff'     => 'بل توپير →',

# Media information
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|مخ|مخونه}}',
'file-info-size'       => '($1 × $2 پېکسل, د دوتنې کچه: $3, MIME بڼه: $4)',
'file-nohires'         => '<small>تر دې کچې لوړې بېلن نښې نشته.</small>',
'svg-long-desc'        => '(SVG دوتنه، نومېنلي $1 × $2 پېکسل، د دوتنې کچه: $3)',
'show-big-image'       => 'بشپړه بېلن نښې',
'show-big-image-thumb' => '<small>د همدې مخکتنې کچه: $1 × $2 pixels</small>',

# Special:NewFiles
'newimages'             => 'د نوو دوتنو نندارتون',
'imagelisttext'         => "دلته لاندې د '''$1''' {{PLURAL:$1|دوتنه|دوتنې}} يو لړليک دی چې اوډل شوي $2.",
'newimages-summary'     => 'همدا ځانګړی مخ، وروستنۍ پورته شوې دوتنې ښکاره کوي.',
'newimages-legend'      => 'چاڼګر',
'showhidebots'          => '($1 باټس)',
'noimages'              => 'د کتلو لپاره څه نشته.',
'ilsubmit'              => 'پلټل',
'bydate'                => 'د نېټې له مخې',
'sp-newimages-showfrom' => 'هغه نوې دوتنې چې په $1 په $2 بجو پيلېږي ښکاره کول',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'hours-abbrev' => 'ساعتونه',

# Bad image list
'bad_image_list' => 'بڼه يې په لاندې توګه ده:

يواځې د هغو توکيو لړليک راوړل (هغه کرښې چې پېلېږي پر *) کوم چې ګڼل کېږي.
بايد چې په يوه کرښه کې لومړنۍ تړنه د يوې خرابې دوتنې سره وي.
په يوې کرښې باندې هر ډول وروستۍ تړنې به د استثنا په توګه وګڼلای شي، د ساري په توګه هغه مخونو کې چې يوه دوتنه پرليکه پرته وي.',

# Metadata
'metadata'          => 'مېټاډاټا',
'metadata-help'     => 'همدا دوتنه نور اضافه مالومات هم لري، چې کېدای شي ستاسو د ګڼياليزې کامرې او يا هم د ځيرڅار په کارولو سره د ګڼيالېدنې په وخت کې ورسره مل شوي.
که همدا دوتنه د خپل آرني دريځ څخه بدله شوې وي نو ځينې تفصيلونه به په بدل شوي دوتنه کې په بشپړه توګه نه وي.',
'metadata-expand'   => 'غځېدلی تفصيل ښکاره کړی',
'metadata-collapse' => 'غځېدلی تفصيل پټ کړی',
'metadata-fields'   => 'د EXIF ميټاډاټا ډګرونه چې لړليک يې په همدې پيغام کې په لاندې توګه راغلی د انځوريز مخ په ښکارېدنه کې به هغه وخت ورګډ شي کله چې د مېټاډاټا چوکاټ پرانيستل کېږي.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'         => 'سوروالی',
'exif-imagelength'        => 'لوړوالی',
'exif-datetime'           => 'د دوتنې د بدلون وخت او نېټه',
'exif-imagedescription'   => 'د انځور سرليک',
'exif-make'               => 'د کامرې جوړونکی',
'exif-model'              => 'د کامرې ماډل',
'exif-software'           => 'کارېدلې ساوترۍ',
'exif-artist'             => 'ليکوال',
'exif-usercomment'        => 'د کارونکي تبصرې',
'exif-flash'              => 'فلش',
'exif-filesource'         => 'د دوتنې سرچينه',
'exif-gpsareainformation' => 'د جي پي اس د سيمې نوم',

'exif-unknowndate' => 'نامالومه نېټه',

'exif-orientation-1' => 'نورمال',

'exif-componentsconfiguration-0' => 'نشته دی',

'exif-subjectdistance-value' => '$1 متره',

'exif-meteringmode-0'   => 'ناجوت',
'exif-meteringmode-255' => 'نور',

'exif-lightsource-0'  => 'ناجوت',
'exif-lightsource-4'  => 'فلش',
'exif-lightsource-11' => 'سيوری',

'exif-focalplaneresolutionunit-2' => 'انچه',

'exif-sensingmethod-1' => 'ناڅرګنده',

'exif-gaincontrol-0' => 'هېڅ',

'exif-contrast-0' => 'نورمال',

'exif-saturation-0' => 'نورمال',

'exif-sharpness-0' => 'نورمال',

'exif-subjectdistancerange-0' => 'ناجوت',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'کيلومتره په يوه ساعت کې',

# External editor support
'edit-externally'      => 'د باندنيو پروګرامونو په کارولو سره دا دوتنه سمول',
'edit-externally-help' => 'د نورو مالوماتو لپاره [http://www.mediawiki.org/wiki/Manual:External_editors د امستنو لارښوونې] وګورۍ.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'ټول',
'imagelistall'     => 'ټول',
'watchlistall2'    => 'ټول',
'namespacesall'    => 'ټول',
'monthsall'        => 'ټول',
'limitall'         => 'ټول',

# E-mail address confirmation
'confirmemail'           => 'د برېښليک پتې پخلی وکړی',
'confirmemail_noemail'   => 'تاسو يوه سمه برېښناليک پته نه ده ثبته کړې مهرباني وکړی [[Special:Preferences|د کارونکي غوره توبونه]] کې مو بدلون راولی.',
'confirmemail_oncreate'  => 'ستاسو د برېښناليک پتې ته يو تاييدي کوډ درولېږل شو.
ددې لپاره چې تاسو غونډال ته ورننوځی تاسو ته د همدغه کوډ اړتيا نشته، خو تاسو ته د همدغه کوډ اړتيا په هغه وخت کې پکارېږي کله چې په ويکي کې خپلې برېښناليکي کړنې چارن کول غواړی.',
'confirmemail_needlogin' => 'ددې لپاره چې ستاسې د برېښليک پتې پخلی وشي، تاسې ته پکار ده چې $1.',
'confirmemail_loggedin'  => 'اوس ستاسې د برېښناليک پتې پخلی وشو.',
'confirmemail_error'     => 'ستاسې د برېښليک پتې د تاييد په خوندي کولو کې يوه ستونزه رامېنڅ ته شوه.',
'confirmemail_body'      => 'يو چا او يا هم کيدای شي چې تاسې پخپله، د $1 IP پتې نه،
د "$2" په نامه يو کارن-حساب په همدې بريښليک پتې د {{SITENAME}} په وېبځي کې ثبت کړی.

دا چې موږ د دې پخلی وکړو چې آيا همدا کارن-حساب په رښتيا ستاسې دی او د دې لپاره چې د همدې برېښليک لپاره په {{SITENAME}} وېبځي کې کړنې فعاله کړو، نو پخپل کتنمل کې لاندينۍ تړنه پرانيزۍ:

$3

که چېرته تاسې همدا کارن-حساب *نه وي ثبت کړی*، نو د برېښليک پتې د پخلي د لغوه کولو لپاره همدا لاندې تړنه وڅارۍ:

$5

همدا تاييدي شفر به په $4 پای ته ورسېږي او تر همدې مودې وروسته به نور و نه چلېږي.',

# Scary transclusion
'scarytranscludetoolong' => '[URL مو ډېر اوږد دی]',

# Trackbacks
'trackbackremove' => '([$1 ړنګول])',

# action=purge
'confirm_purge_button' => 'ښه/هو',
'confirm-purge-top'    => 'په رښتيا د همدې مخ حافظه سپينول غواړۍ؟',

# Multipage image navigation
'imgmultipageprev' => '← پخوانی مخ',
'imgmultipagenext' => 'راتلونکی مخ →',
'imgmultigo'       => 'ورځه!',
'imgmultigoto'     => 'د $1 مخ ته ورځه',

# Table pager
'ascending_abbrev'         => 'ختند',
'descending_abbrev'        => 'مخښکته',
'table_pager_next'         => 'بل مخ',
'table_pager_prev'         => 'تېر مخ',
'table_pager_first'        => 'لومړی مخ',
'table_pager_last'         => 'وروستی مخ',
'table_pager_limit'        => 'په يوه مخ $1 توکي ښکاره کړی',
'table_pager_limit_submit' => 'ورځه',
'table_pager_empty'        => 'هېڅ پايلې نه شته',

# Auto-summaries
'autosumm-blank'   => 'د مخ مېنځپانګه ليرې شوه',
'autosumm-replace' => "دا مخ د '$1' پرځای راوستل",
'autoredircomment' => '[[$1]] ته وګرځولی شو',
'autosumm-new'     => "Created page with '$1'",

# Live preview
'livepreview-loading' => 'د برسېرېدلو په حال کې...',
'livepreview-ready'   => 'برسېرېدنه ... چمتو ده!',

# Watchlist editor
'watchlistedit-noitems'       => 'ستاسې کتنلړليک کې هېڅ کوم سرليک نشته.',
'watchlistedit-normal-title'  => 'کتنلړليک سمول',
'watchlistedit-normal-legend' => 'د کتنلړليک نه سرليکونه ليرې کول',
'watchlistedit-raw-title'     => 'خام کتنلړليک سمول',
'watchlistedit-raw-legend'    => 'خام کتنلړليک سمول',
'watchlistedit-raw-titles'    => 'سرليکونه:',
'watchlistedit-raw-submit'    => 'کتنلړليک اوسمهاله کول',
'watchlistedit-raw-done'      => 'ستاسې کتنلړليک اوسمهاله شو.',

# Watchlist editing tools
'watchlisttools-view' => 'اړونده بدلونونه کتل',
'watchlisttools-edit' => 'کتنلړليک ليدل او سمول',
'watchlisttools-raw'  => 'خام کتنلړليک سمول',

# Iranian month names
'iranian-calendar-m1'  => 'وری',
'iranian-calendar-m2'  => 'غويی',
'iranian-calendar-m3'  => 'غبرګولی',
'iranian-calendar-m4'  => 'چنګاښ',
'iranian-calendar-m5'  => 'زمری',
'iranian-calendar-m6'  => 'وږی',
'iranian-calendar-m7'  => 'تله',
'iranian-calendar-m8'  => 'لړم',
'iranian-calendar-m9'  => 'ليندۍ',
'iranian-calendar-m10' => 'مرغومی',
'iranian-calendar-m11' => 'سلواغه',
'iranian-calendar-m12' => 'کب',

# Special:Version
'version'                  => 'بڼه',
'version-specialpages'     => 'ځانګړي مخونه',
'version-other'            => 'بل',
'version-software'         => 'نصب شوی ساوتری',
'version-software-version' => 'بڼه',

# Special:FilePath
'filepath-page' => 'دوتنه:',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'د دوه ګونو دوتنو پلټنه',
'fileduplicatesearch-legend'   => 'د دوه ګونو دوتنو پلټنه',
'fileduplicatesearch-filename' => 'د دوتنې نوم:',
'fileduplicatesearch-submit'   => 'پلټل',

# Special:SpecialPages
'specialpages'                 => 'ځانګړي مخونه',
'specialpages-group-other'     => 'نور ځانګړي مخونه',
'specialpages-group-login'     => 'ننوتل / کارن-حساب جوړول',
'specialpages-group-changes'   => 'وروستي بدلونونه او يادښتونه',
'specialpages-group-users'     => 'کارونکي او رښتې',
'specialpages-group-highuse'   => 'ډېر کارېدونکي مخونه',
'specialpages-group-pages'     => 'د مخونو لړليک',
'specialpages-group-pagetools' => 'د مخ اوزارونه',
'specialpages-group-wiki'      => 'ويکيډاټا او اوزارونه',

# Special:BlankPage
'blankpage'              => 'تش مخ',
'intentionallyblankpage' => 'همدا مخ په لوی لاس تش پرېښودل شوی دی',

# Special:Tags
'tag-filter-submit' => 'چاڼګر',
'tags-edit'         => 'سمول',

# Database error messages
'dberr-header' => 'دا ويکي يوه ستونزه لري',

# HTML forms
'htmlform-reset'               => 'بدلونونه ناکړل',
'htmlform-selectorother-other' => 'بل',

# Add categories per AJAX
'ajax-add-category'            => 'وېشنيزه ورګډول',
'ajax-add-category-submit'     => 'ورګډول',
'ajax-confirm-save'            => 'خوندي کول',
'ajax-add-category-summary'    => 'د "$1" وېشنيزه ورګډول',
'ajax-remove-category-summary' => 'د "$1" وېشنيزه ليرې کول',
'ajax-error-title'             => 'ستونزه',

);

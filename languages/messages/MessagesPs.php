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
	'CreateAccount'             => array( 'کارن-حساب_جوړول' ),
	'Preferences'               => array( 'غوره_توبونه' ),
	'Watchlist'                 => array( 'کتنلړ' ),
	'Recentchanges'             => array( 'اوسني_بدلونونه' ),
	'Upload'                    => array( 'پورته_کول' ),
	'Listfiles'                 => array( 'د_انځورونو_لړليک' ),
	'Newimages'                 => array( 'نوي_انځورونه' ),
	'Listusers'                 => array( 'د_کارنانو_لړليک' ),
	'Statistics'                => array( 'شمار' ),
	'Randompage'                => array( 'ناټاکلی،_ناټاکلی_مخ' ),
	'Lonelypages'               => array( 'يتيم_مخونه' ),
	'Uncategorizedpages'        => array( 'ناوېشلي_مخونه' ),
	'Uncategorizedcategories'   => array( 'ناوېشلې_وېشنيزې' ),
	'Uncategorizedimages'       => array( 'ناوېشلي_انځورونه،_ناوېشلې_دوتنې' ),
	'Uncategorizedtemplates'    => array( 'ناوېشلې_کينډۍ' ),
	'Unusedcategories'          => array( 'ناکارېدلي_وېشنيزې' ),
	'Unusedimages'              => array( 'ناکارېدلې_دوتنې' ),
	'Wantedcategories'          => array( 'غوښتلې_وېشنيزې' ),
	'Wantedfiles'               => array( 'غوښتلې_دوتنې' ),
	'Wantedtemplates'           => array( 'غوښتلې_کينډۍ' ),
	'Shortpages'                => array( 'لنډ_مخونه' ),
	'Longpages'                 => array( 'اوږده_مخونه' ),
	'Newpages'                  => array( 'نوي_مخونه' ),
	'Ancientpages'              => array( 'لرغوني_مخونه' ),
	'Protectedpages'            => array( 'ژغورلي_مخونه' ),
	'Protectedtitles'           => array( 'ژغورلي_سرليکونه' ),
	'Allpages'                  => array( 'ټول_مخونه' ),
	'Prefixindex'               => array( 'د_مختاړيو_ليکلړ' ),
	'Ipblocklist'               => array( 'د_بنديزلړليک' ),
	'Unblock'                   => array( 'بنديز_لرې_کول' ),
	'Specialpages'              => array( 'ځانګړي_مخونه' ),
	'Contributions'             => array( 'ونډې' ),
	'Booksources'               => array( 'د_کتاب_سرچينې' ),
	'Categories'                => array( 'وېشنيزې' ),
	'Export'                    => array( 'صادرول' ),
	'Version'                   => array( 'بڼه' ),
	'Allmessages'               => array( 'ټول-پيغامونه' ),
	'Log'                       => array( 'يادښتونه،_يادښت' ),
	'Blockip'                   => array( 'بنديز،_د_آی_پي_بنديز،_بنديز_لګېدلی_کارن_Block' ),
	'Undelete'                  => array( 'ناړنګول' ),
	'Unwatchedpages'            => array( 'ناکتلي_مخونه' ),
	'Unusedtemplates'           => array( 'ناکارېدلې_کينډۍ' ),
	'Mypage'                    => array( 'زما_پاڼه' ),
	'Mytalk'                    => array( 'زما_خبرې_اترې' ),
	'Mycontributions'           => array( 'زماونډې' ),
	'Popularpages'              => array( 'نامتومخونه' ),
	'Search'                    => array( 'پلټنه' ),
	'Resetpass'                 => array( 'پټنوم_بدلول،_پټنوم_بيا_پر_ځای_کول،_د_بيا_پر_ځای_کولو_پاسپورټ' ),
	'Blankpage'                 => array( 'تش_مخ' ),
	'LinkSearch'                => array( 'د_تړنې_پلټنه' ),
	'DeletedContributions'      => array( 'ړنګې_شوي_ونډې' ),
	'Badtitle'                  => array( 'ناسم_سرليک،_Badtitle' ),
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
	'img_center'            => array( '1', 'مېنځ،_center', 'center', 'centre' ),
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
	'noindex'               => array( '1', '__بې_ليکلړ__', '__NOINDEX__' ),
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
'tog-underline'               => 'کرښنې تړنې:',
'tog-justify'                 => 'پاراګرافونه همجوليزول',
'tog-hideminor'               => 'په وروستيو بدلونو کې واړه سمونونه پټول',
'tog-hidepatrolled'           => 'په وروستيو بدلونونو کې څارل شوې سمونونه پټول',
'tog-newpageshidepatrolled'   => 'د نوؤ مخونو په لړليک کې کتل شوي مخونه پټول',
'tog-extendwatchlist'         => 'يوازې د وروستني بدلونونو د ښکاره کولو لپاره نه بلکه د ټولو بدلونونو د ښکاره کولو لپاره کتنلړ غځول',
'tog-usenewrc'                => 'د وروستي بدلونو پرمختللې بڼه کارول (جاوا سکرېپټ ته اړتيا ده)',
'tog-numberheadings'          => 'د سرليکونو خپلکاره شمېرايښودنه',
'tog-showtoolbar'             => 'د سمولو توکپټه ښکاره کول (جاواسکرېپټ)',
'tog-editondblclick'          => 'په دوه کلېک سره د مخونو سمون (د جاواسکرېپټ اړتيا ده)',
'tog-editsection'             => 'د [سمول] تړنې له لوري د يوې ليکنې يوه برخه د سمون وړ ګرځول',
'tog-editsectiononrightclick' => 'د ښي کلېک سره د سرليکونو د برخې سمون چارنول (جاواسکرېپټ ته اړتيا)',
'tog-showtoc'                 => 'نيوليک ښکاره کول (د هغو مخونو لپاره چې له ۳ نه ډېر سرليکونه لري)',
'tog-rememberpassword'        => 'زما کارن-نوم په دې کتنمل (تر $1 {{PLURAL:$1|ورځې|ورځو}}) په ياد وساته!',
'tog-watchcreations'          => 'هغه مخونه چې زه يې جوړوم، زما کتنلړ کې ورګډ کړه',
'tog-watchdefault'            => 'هغه مخونه چې زه يې سموم، زما کتنلړ کې ورګډ کړه',
'tog-watchmoves'              => 'هغه مخونه چې زه يې لېږدوم، زما کتنلړ کې ورګډ کړه',
'tog-watchdeletion'           => 'هغه مخونه چې زه يې ړنګوم، زما کتنلړ کې ورګډ کړه',
'tog-minordefault'            => 'په تلواليزه توګه ټول سمونونه واړه په نخښه کول',
'tog-previewontop'            => 'د سمون بکس نه دمخه مخکتنه ښکاره کول',
'tog-previewonfirst'          => 'په لومړي سمون کې مخکتنه ښکاره کول',
'tog-nocache'                 => 'د کتنمل د مخ ياده ساتنې چار ناچارندول',
'tog-enotifwatchlistpages'    => 'کله چې زما کتنلړ کې يو مخ بدلون مومي نو ما ته دې برېښليک راشي',
'tog-enotifusertalkpages'     => 'کله چې زما د خبرو اترو په مخ کې بدلون پېښېږي نو ما ته دې يو برېښليک ولېږلی شي.',
'tog-enotifminoredits'        => 'کله چې په مخونو کې وړې سمونې کېږي نو ماته دې برېښليک ولېږل شي',
'tog-enotifrevealaddr'        => 'په يادښت برېښليک کې زما برېښليک پته ښکاره کول',
'tog-shownumberswatching'     => 'د کتونکو کارنانو شمېر ښکاره کول',
'tog-oldsig'                  => 'د شته لاسليک مخليدنه:',
'tog-fancysig'                => 'لاسليک د ويکي متن په توګه په پام کې نيول (د خپلکاره تړن د تړلو پرته)',
'tog-uselivepreview'          => 'ژوندۍ مخليدنه کارول (جاوا سکرېپټ ته اړتيا) (آزمېښتي)',
'tog-forceeditsummary'        => 'د يوه تش سمون لنډيز په ورکولو سره دې خبر راکړل شي',
'tog-watchlisthideown'        => 'په کتنلړ کې زما سمونې پټول',
'tog-watchlisthidebots'       => 'په کتنلړ کې د روباټ سمونې پټول',
'tog-watchlisthideminor'      => 'په کتنلړ کې وړې سمونې پټول',
'tog-watchlisthideliu'        => 'په کتنلړ کې د ثبت شويو کارنانو سمونې پټول',
'tog-watchlisthideanons'      => 'په کتنلړ کې د ورکنومو کارنانو سمونې پټول',
'tog-watchlisthidepatrolled'  => 'په کتنلړ کې څارل شوې سمونې پټول',
'tog-ccmeonemails'            => 'هغه برېښليکونه چې زه يې نورو ته لېږم، د هغو يوه کاپي دې ماته هم راشي',
'tog-diffonly'                => 'د توپيرونو نه لاندې د مخ مېنځپانګه پټول',
'tog-showhiddencats'          => 'پټې وېشنيزې ښکاره کول',

'underline-always'  => 'تل',
'underline-never'   => 'هېڅکله',
'underline-default' => 'د کتنمل تلواليزې چارې',

# Font style option in Special:Preferences
'editfont-style'     => 'د سيمه ايزې ليکبڼې سمول:',
'editfont-default'   => 'د کتنمل تلواليزې چارې',
'editfont-monospace' => 'يو واټنيزه ليکبڼه',
'editfont-sansserif' => 'سان سېرېف ليکبڼه',
'editfont-serif'     => 'سېرېف ليکبڼه',

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
'pagecategories'                 => '{{PLURAL:$1|وېشنيزه|وېشنيزې}}',
'category_header'                => 'د "$1" په وېشنيزه کې شته مخونه',
'subcategories'                  => 'وړې-وېشنيزې',
'category-media-header'          => 'د "$1" په وېشنيزه کې شته رسنۍ',
'category-empty'                 => "''تر اوسه پورې همدا وېشنيزه هېڅ کوم مخ يا کومه رسنيزه دوتنه نلري.''",
'hidden-categories'              => '{{PLURAL:$1|پټه وېشنيزه|پټې وېشنيزې}}',
'hidden-category-category'       => 'پټې وېشنيزې',
'category-subcat-count'          => '{{PLURAL:$2|په دې وېشنيزه کې دا لاندې وړه وېشنيزه ده.|په دې وېشنيزه کې له ټولټال $2 نه {{PLURAL:$1|وړه وېشنيزه ده|$1 وړې وېشنيزې دي}}.}}',
'category-subcat-count-limited'  => 'دا وېشنيزه دا لاندې {{PLURAL:$1|يوه څېرمه وېشنيزه|$1 څېرمه وېشنيزې}} لري.',
'category-article-count'         => '{{PLURAL:$2|په همدې وېشنيزه کې يواځې دغه لاندينی مخ شته.|دا {{PLURAL:$1|لاندينی مخ|$1 لانديني مخونه}}، له ټولټال $2 مخونو نه په دې وېشنيزه کې شته.}}',
'category-article-count-limited' => 'په دې وېشنيزه کې {{PLURAL:$1|يوه دوتنه ده|$1 دوتنې دي}}.',
'category-file-count'            => '{{PLURAL:$2|په همدې وېشنيزه کې يواځې دغه لاندينی مخ شته.|دا {{PLURAL:$1|لاندينی مخ|$1 لانديني مخونه}}، له ټولټال $2 مخونو نه په دې وېشنيزه کې شته.}}',
'category-file-count-limited'    => 'په اوسنۍ وېشنيزه کې {{PLURAL:$1|يوه دوتنه ده|$1 دوتنې دي}}.',
'listingcontinuesabbrev'         => 'پرله پسې',
'index-category'                 => 'ليکلړلرونکي مخونه',
'noindex-category'               => 'بې ليکلړه مخونه',

'mainpagetext'      => "'''MediaWiki په برياليتوب سره نصب شو.'''",
'mainpagedocfooter' => 'د ويکي ساوترې د کارولو د  مالوماتو په اړه [http://meta.wikimedia.org/wiki/Help:Contents د کارن لارښود] سره سلا وکړۍ.

== پيلول ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings د امستنو د سازونې لړليک]
* [http://www.mediawiki.org/wiki/Manual:FAQ د ميډياويکي ډېرځليزې پوښتنې]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce د مېډياويکي د برېښليکونو لړليک]',

'about'         => 'په اړه',
'article'       => 'مېنځپانګيز مخ',
'newwindow'     => '(په نوې کړکۍ کې پرانيستل کېږي)',
'cancel'        => 'ناګارل',
'moredotdotdot' => 'نور ...',
'mypage'        => 'زما پاڼه',
'mytalk'        => 'زما خبرې اترې',
'anontalk'      => 'ددې IP لپاره خبرې اترې',
'navigation'    => 'ګرځښت',
'and'           => '&#32;او',

# Cologne Blue skin
'qbfind'         => 'موندل',
'qbbrowse'       => 'سپړل',
'qbedit'         => 'سمول',
'qbpageoptions'  => 'همدا مخ',
'qbpageinfo'     => 'متن',
'qbmyoptions'    => 'زما پاڼې',
'qbspecialpages' => 'ځانګړي مخونه',
'faq'            => 'ډ-ځ-پ',
'faqpage'        => 'Project:ډ-ځ-پ',

# Vector skin
'vector-action-addsection' => 'سرليکونه ورګډول',
'vector-action-delete'     => 'ړنګول',
'vector-action-move'       => 'لېږدول',
'vector-action-protect'    => 'پروژه',
'vector-action-undelete'   => 'ناړنګول',
'vector-action-unprotect'  => 'ناژغورل',
'vector-view-create'       => 'جوړول',
'vector-view-edit'         => 'سمول',
'vector-view-history'      => 'پېښليک کتل',
'vector-view-view'         => 'لوستل',
'vector-view-viewsource'   => 'سرچينه کتل',
'actions'                  => 'کړنې',
'namespaces'               => 'نوم-تشيالونه',

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
'view'              => 'کتل',
'edit'              => 'سمول',
'create'            => 'جوړول',
'editthispage'      => 'همدا مخ سمول',
'create-this-page'  => 'همدا مخ ليکل',
'delete'            => 'ړنګول',
'deletethispage'    => 'دا مخ ړنګ کړه',
'undelete_short'    => '{{PLURAL:$1|يو سمون|$1 سمونې}} ناړنګول',
'viewdeleted_short' => '{{PLURAL:$1|يو ړنګ شوی سمون|$1 ړنګ شوي سمونونه}} کتل',
'protect'           => 'ژغورل',
'protect_change'    => 'بدلون',
'protectthispage'   => 'همدا مخ ژغورل',
'unprotect'         => 'نه ژغورل',
'unprotectthispage' => 'همدا مخ نه ژغورل',
'newpage'           => 'نوی مخ',
'talkpage'          => 'د دې مخ په اړه خبرې اترې کول',
'talkpagelinktext'  => 'خبرې اترې',
'specialpage'       => 'ځانګړې پاڼه',
'personaltools'     => 'شخصي اوزار',
'postcomment'       => 'نوې برخه',
'articlepage'       => 'د مخ مېنځپانګه ښکاره کول',
'talk'              => 'خبرې اترې',
'views'             => 'کتنې',
'toolbox'           => 'اوزاربکس',
'userpage'          => 'د کارن پاڼه کتل',
'projectpage'       => 'د پروژې مخ کتل',
'imagepage'         => 'د دوتنې مخ کتل',
'mediawikipage'     => 'د پيغامونو مخ کتل',
'templatepage'      => 'د کينډۍ مخ کتل',
'viewhelppage'      => 'د لارښود مخ کتل',
'categorypage'      => 'د وېشنيزې مخ کتل',
'viewtalkpage'      => 'خبرې اترې کتل',
'otherlanguages'    => 'په نورو ژبو کې',
'redirectedfrom'    => '(له $1 نه مخ ګرځېدلی)',
'redirectpagesub'   => 'د مخ ګرځونې مخ',
'lastmodifiedat'    => 'دا مخ وروستی ځل په $2، $1 بدلون موندلی.',
'viewcount'         => 'همدا مخ {{PLURAL:$1|يو وار|$1 واره}} کتل شوی.',
'protectedpage'     => 'ژغورلی مخ',
'jumpto'            => 'ورټوپ کړه:',
'jumptonavigation'  => 'ګرځښت',
'jumptosearch'      => 'پلټل',
'view-pool-error'   => 'اوبخښۍ، دم ګړۍ پالنګران د ډېر بارېدو ستونزې سره مخامخ شوي.
ډېر زيات کارنان د همدې مخ د کتلو په هڅه کې دي.
لطفاً د دې مخ د کتلو د بيا هڅې نه دمخه يو څو شېبې صبر وکړۍ.

$1',
'pool-errorunknown' => 'ناجوته ستونزه',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'د {{SITENAME}} په اړه',
'aboutpage'            => 'Project:په اړه',
'copyright'            => 'دا مېنځپانګه د $1 اجازتليک له مخې ستاسې لاسرسي ته پرته ده.',
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
'portal'               => 'د ټولنې تانبه',
'portal-url'           => 'Project:د ټولنې تانبه',
'privacy'              => 'د محرميت تګلاره',
'privacypage'          => 'Project:د محرميت_تګلاره',

'badaccess'        => 'د لاسرسۍ تېروتنه',
'badaccess-group0' => 'تاسې د غوښتل شوې کړنې د ترسره کولو اجازه نه لرۍ.',
'badaccess-groups' => 'د کومې کړنې غوښتنه چې تاسې کړې د هغو کارنانو پورې محدوده ده چې {{PLURAL:$2|په ډله د|په ډلو د}}: $1 کې دي.',

'versionrequired'     => 'د ميډياويکي $1 بڼې ته اړتيا ده',
'versionrequiredtext' => 'د دې مخ په ليدلو کې د مېډياويکي $1 بڼې ته اړتيا ده. 
[[Special:Version|د بڼې مخ وګورۍ]].',

'ok'                      => 'ښه',
'retrievedfrom'           => '"$1" نه اخيستل شوی',
'youhavenewmessages'      => 'تاسې $1 لری  ($2).',
'newmessageslink'         => 'نوي پيغامونه',
'newmessagesdifflink'     => 'وروستی بدلون',
'youhavenewmessagesmulti' => 'تاسې په $1 کې نوي پېغامونه لرۍ',
'editsection'             => 'سمول',
'editold'                 => 'سمول',
'viewsourceold'           => 'سرچينې کتل',
'editlink'                => 'سمول',
'viewsourcelink'          => 'سرچينه کتل',
'editsectionhint'         => 'د سمولو برخه: $1',
'toc'                     => 'نيوليک',
'showtoc'                 => 'ښکاره کول',
'hidetoc'                 => 'پټول',
'collapsible-collapse'    => 'پرزول',
'collapsible-expand'      => 'غځول',
'thisisdeleted'           => '$1 کتل او يا بيازېرمل؟',
'viewdeleted'             => '$1 کتل؟',
'restorelink'             => '{{PLURAL:$1|يو ړنګ شوی سمون|$1 ړنګ شوي سمونونه}}',
'feedlinks'               => 'کتنه:',
'site-rss-feed'           => '$1 د آر اس اس کتنه',
'site-atom-feed'          => '$1 د اټوم کتنه',
'page-rss-feed'           => '"$1" د آر اس اس کتنه',
'page-atom-feed'          => 'د "$1" د اټوم کتنې',
'feed-rss'                => 'آر اس اس',
'red-link-title'          => '$1 (تر اوسه پورې نه شته)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'ليکنه',
'nstab-user'      => 'کارن مخ',
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
'nosuchactiontext'  => 'کومه کړنه چې د URL لخوا ځانګړې شوې سمه نه ده.
کېدای شي چې URL مو سم نه وي ټايپ کړی، او يا مو يوه ناسمه تړنه څارلې وي.
دا د دې هم ښکارندويي کوي چې کېدای شي چې د {{SITENAME}} لخوا کارېدونکې ساوترې کې يوه تېروتنه وي.',
'nosuchspecialpage' => 'داسې هېڅ کوم ځانګړی مخ نشته',
'nospecialpagetext' => '<strong>تاسې د يو ناسم ځانګړي مخ غوښتنه کړې.</strong>

تاسې کولای شی چې د سمو ځانګړو مخونو لړليک په [[Special:SpecialPages|{{int:specialpages}}]] کې ومومۍ.',

# General errors
'error'                => 'تېروتنه',
'databaseerror'        => 'د ډاټابېز تېروتنه',
'laggedslavemode'      => "'''ګواښنه:''' په دې مخ کې کېدای شي تازه اوسمهالېدنې نه وي.",
'readonly'             => 'توکبنسټ تړل شوی',
'enterlockreason'      => 'د بنديز يو سبب وليکۍ، او همداراز د بنديز د ليرې کېدلو يوه اټکليزه نېټه هم څرګنده کړۍ',
'missing-article'      => 'توکبنسټ د "$1" $2 په نامه د ورکړ شوي مخ متن چې بايد موندلی يې وای، و نه موند.

دا ستونزه اکثراً د يوه ړنګ شوي مخ د پېښليک يا توپير د تړنو په څارلو کې رامېنځ ته کېږي.

که چېرته داسې نه وي، نو بيا کېدای شي چې په ساوترې کې کومه تېروتنه رابرسېره شوې وي.
لطفاً د دې چارې راپور د URL په نښه کولو سره يوه [[Special:ListUsers/sysop|پازوال]] ته ورکړۍ.',
'missingarticle-rev'   => '(مخليدنه#: $1)',
'missingarticle-diff'  => '(توپير: $1، $2)',
'internalerror'        => 'کورنۍ تېروتنه',
'internalerror_info'   => 'کورنۍ تېروتنه: $1',
'fileappenderrorread'  => 'د پايملون په وخت کې "$1" و نه لوستل شو.',
'fileappenderror'      => 'د "$1" پايملون "$2" ته ترسره نه شو..',
'filecopyerror'        => 'د "$1" په نامه دوتنه مو "$2" ته و نه لمېسلای شوه.',
'filerenameerror'      => 'د "$1" په نامه د دوتنې نوم "$2" ته بدل نه شو.',
'filedeleteerror'      => 'د "$1" دوتنه ړنګه نه شوه.',
'directorycreateerror' => 'د "$1" په نامه ليکلړ جوړ نه شو.',
'filenotfound'         => '"$1" په نوم دوتنه مو و نه شوه موندلای.',
'fileexistserror'      => 'د "$1" په نامه دوتنه نه ليکل کېږي: دوتنه د پخوا نه دلته شته',
'unexpected'           => 'نا اټکله شمېره: "$1"="$2".',
'formerror'            => 'ستونزه: فورمه مو و نه سپارل شوه',
'badarticleerror'      => 'دا کړنه پدې مخ نه شي ترسره کېدلای.',
'cannotdelete'         => 'د "$1" مخ يا دوتنې ړنګېدنه ترسره نه شوه.
کېدای شي چې وار دمخې دا کوم بل چا ړنګه کړې وي.',
'badtitle'             => 'ناسم سرليک',
'badtitletext'         => 'ستاسې د غوښتل شوي مخ سرليک سم نه وو، يا مو د سرليک ځای تش وو او يا هم د ژبو خپلمنځي تړنې څخه يا د ويکي ګانو خپلمنځي سرليکونو څخه يو ناسم توری مو پکې کارولی وي.
کېدای شي چې ستاسې په ورکړ شوي سرليک کې يو يا څو داسې توري وي چې د سرليک په توګه بايد و نه کارېږي.',
'viewsource'           => 'سرچينه کتل',
'viewsourcefor'        => 'د $1 لپاره',
'actionthrottled'      => 'د دې کړنې مخنيوی وشو',
'protectedpagetext'    => 'دا مخ د بدلون او سمون د مخنيوي په تکل تړل شوی دی.',
'viewsourcetext'       => 'تاسې د دې مخ سرچينه کتلی او لمېسلی شی:',
'protectedinterface'   => 'په همدې مخ کې د پوستکالي د ليدنمخ متن دی او دا متن د ناسمو کارولو د مخنيوي په تکل تړل شوی.',
'editinginterface'     => "'''ګواښنه:''' تاسو په يوه داسې مخ کې بدلون راولی کوم چې د يوې پوستکالی د ليدنمخ متن په توګه کارېږي.
په همدې مخ کې بدلون راوستل به د نورو کارنانو د ليدنمخ بڼه اغېزمنه کړي.
د ژباړې لپاره، مهرباني وکړی د [http://translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net]، وېبځي ته ولاړ شی. دا وېبځی د ميډياويکي د ځايتابه پروژه ده او د همدې پر کارولو غور وکړی.",
'sqlhidden'            => '(د SQL پوښتن پټ دی)',
'namespaceprotected'   => "تاسې د '''$1''' په نوم-تشيال کې د مخونو د سمولو اجازه نه لرۍ.",
'customcssjsprotected' => 'تاسې د دې مخ د سمولو اجازه نه لرۍ، دا ځکه چې دا مخ د بل کارن شخصي امستنې لري.',
'ns-specialprotected'  => 'ځانګړي مخونو کې سمون او بدلون نه شی راوستلای.',
'titleprotected'       => 'د [[User:$1|$1]] لخوا د دې سرليک د جوړېدلو مخنيوی شوی.
او د دې کړنې سبب "\'\'$2\'\'" ورکړ شوی.',

# Virus scanner
'virus-badscanner'     => "بده سازېدنه: د ويروس ناڅرګنده ځيرڅار: ''$1''",
'virus-unknownscanner' => 'ناڅرګند ضدويروس:',

# Login and logout pages
'logouttext'                 => "'''تاسې اوس د غونډال نه ووتلی.'''

تاسې کولای شی چې د کارن-نوم نه پرته په ورکنومي توګه {{SITENAME}} وکاروی، او يا هم په همدې او يا کوم بل کارن-نوم، يو ځل [[Special:UserLogin|بيا غونډال ته ورننوځۍ]].
دا په پام کې وساتۍ چې تر څو تاسې د خپل کتنمل حافظه نه وي سپينه کړې، نو ځينې مخونو کې به لا تر اوسه پورې په غونډال کې ننوتي ښکارۍ.",
'welcomecreation'            => '==$1 ښه راغلاست! ==

ستاسې ګڼون جوړ شو. لطفاً د [[Special:Preferences|{{SITENAME}} غوره توبونو]] ټاکل مو مه هېروی.',
'yourname'                   => 'کارن-نوم:',
'yourpassword'               => 'پټنوم:',
'yourpasswordagain'          => 'پټنوم بيا وليکه',
'remembermypassword'         => 'زما پټنوم په دې کمپيوټر (تر $1 {{PLURAL:$1|ورځې|ورځو}}) په ياد وساته!',
'yourdomainname'             => 'ستاسې شپول:',
'login'                      => 'ننوتل',
'nav-login-createaccount'    => 'ننوتل / ګڼون جوړول',
'loginprompt'                => 'ددې لپاره چې {{SITENAME}} کې ننوځۍ نو بايد ستاسې د کمپيوټر کوکيز چارن وي.',
'userlogin'                  => 'ننوتل / ګڼون جوړول',
'userloginnocreate'          => 'ننوتل',
'logout'                     => 'وتل',
'userlogout'                 => 'وتل',
'notloggedin'                => 'غونډال کې نه ياست ننوتي',
'nologin'                    => 'کارن-نوم نه لرې؟ $1.',
'nologinlink'                => 'يو ګڼون جوړول',
'createaccount'              => 'ګڼون جوړول',
'gotaccount'                 => 'آيا وار دمخې يو ګڼون لری؟ $1.',
'gotaccountlink'             => 'ننوتل',
'createaccountmail'          => 'د برېښليک له مخې',
'createaccountreason'        => 'سبب:',
'badretype'                  => 'دا پټنوم چې تاسې ليکلی د مخکني پټنوم سره ورته نه دی.',
'userexists'                 => 'کوم کارن نوم چې تاسې ورکړ هغه بل چا کارولی.
لطفاً يو بل نوم وټاکۍ.',
'loginerror'                 => 'د ننوتنې ستونزه',
'createaccounterror'         => 'ګڼون مو جوړ نه شو: $1',
'nocookiesnew'               => 'ستاسې ګڼون جوړ شو، خو تاسې لا غونډال ته نه ياست ورننوتلي.
{{SITENAME}} کې د ننوتلو لپاره کوکيز کارېږي.
او ستاسې د کتنمل کوکيز ناچارن دي.
لطفاً خپل د کتنمل کوکيز چارن کړۍ او بيا د خپل کارن-نوم او پټنوم په کارولو سره غونډال ته ورننوځی.',
'nocookieslogin'             => '{{SITENAME}} کې د ننوتلو لپاره کوکيز کارېږي.
او ستاسې د کتنمل کوکيز ناچارن دي.
لطفاً خپل د کتنمل کوکيز چارن کړۍ او بيا د خپل کارن-نوم او پټنوم په کارولو سره غونډال ته ورننوځی.',
'noname'                     => 'تاسې تر اوسه پورې کوم کره کارن نوم نه دی ځانګړی کړی.',
'loginsuccesstitle'          => 'غونډال کې بريالی ورننوتلۍ',
'loginsuccess'               => "'''تاسې اوس {{SITENAME}} کې د \"\$1\" په نوم ننوتي ياست.'''",
'nosuchuser'                 => 'د "$1" په نوم هېڅ کارن نشته.
د کارنانو نومونه د غټو او واړو تورو سره حساس دي.
خپل حجا وڅارۍ، او يا هم [[Special:UserLogin/signup|يو نوی ګڼون جوړ کړی]].',
'nosuchusershort'            => 'د "<nowiki>$1</nowiki>" په نوم هېڅ کوم ګڼون نشته. لطفاً خپل د نوم ليکلې بڼې ته ځير شی چې پکې تېروتنه نه وي.',
'nouserspecified'            => 'تاسې ځان ته کوم کارن نوم نه دی ځانګړی کړی.',
'login-userblocked'          => 'په دې کارن بنديز لګېدلی. غونډال کې ننوتلو ته پرې نه ښودلی شو.',
'wrongpassword'              => 'ناسم پټنوم مو ليکلی. لطفاً يو ځل بيا يې وليکۍ.',
'wrongpasswordempty'         => 'تاسې پټنوم نه دی ليکلی. لطفاً سر له نوي يې وليکۍ.',
'passwordtooshort'           => 'بايد چې پټنوم مو لږ تر لږه {{PLURAL:$1|1 توری|$1 توري}} وي.',
'password-name-match'        => 'ستاسې پټنوم بايد ستاسې د کارن-نوم سره توپير ولري.',
'mailmypassword'             => 'نوی پټنوم برېښليک کول',
'passwordremindertitle'      => 'د {{SITENAME}} لپاره نوی لنډمهاله پټنوم',
'passwordremindertext'       => 'يو چا (کېدای شي چې تاسې پخپله، د $1 IP پتې نه)
د {{SITENAME}} ($4) وېبځي لپاره د يوه نوي پټنوم د ورلېږلو غوښتنه کړې.
دم مهال د "$2" کارن لپاره يو نوی لنډمهاله پټنوم "$3" دی.
که چېرته همدا غوښتنه ستاسې لخوا شوي وي، نو تاسې غونډال ته په همدې پټنوم ورننوځی او بيا خپل نوی پټنوم په خپله خوښه وټاکۍ.
ستاسې لنډمهاله پټنوم په {{PLURAL:$5|يوه ورځ|$5 ورځو}} کې بې اعتباره کېدونکی دی.

که چېرته تاسې نه پرته کوم بل چا دغه غوښتنه کړې وي او يا هم تاسې ته خپل پټنوم در پزړه شوی وي او تاسې خپل اصلي پټنوم بدلول نه غواړۍ، نو تاسې همدا پيغام بابېزه وګڼۍ او د پخوا په څېر خپل اصلي پټنوم وکاروی.',
'noemail'                    => 'د "$1" کارن لپاره هېڅ کومه برېښليک پته نه ده ثبته شوې.',
'noemailcreate'              => 'تاسې ته پکار ده چې يوه سمه برېښليک پته وليکۍ',
'passwordsent'               => 'د "$1" لپاره يو نوی پټنوم د هغه/هغې د برېښليک پتې ته ولېږل شو.
لطفاً کله چې پټنوم مو ترلاسه کړ نو بيا غونډال ته ننوځۍ.',
'blocked-mailpassword'       => 'ستاسې په IP پتې بنديز لګېدلی او تاسې نه شی کولای چې ليکنې وکړی، په همدې توګه تاسې نه شی کولای چې د پټنوم د پرځای کولو کړنې وکاروی دا ددې لپاره چې د وراني مخنيوی وشي.',
'eauthentsent'               => 'ستاسې ورکړ شوې برېښليک پتې ته مو يو تاييدي برېښليک درولېږه.
تر دې دمخه چې ستاسې ګڼون ته کوم بل برېښليک درولېږو، پکار ده چې تاسې په برېښليک کې درلېږل شوې لارښوونې پلي کړی او ددې پخلی وکړی چې همدا ګڼون په رښتيا ستاسې خپل دی.',
'mailerror'                  => 'د برېښليک د لېږلو ستونزه: $1',
'acct_creation_throttle_hit' => 'د همدې ويکي کارنانو په وروستيو ورځو کې ستاسې د IP پتې په کارولو سره {{PLURAL:$1|1 ګڼون|$1 ګڼونونه}} جوړ کړي، چې دا په همدې مودې کې د ګڼونونو د جوړولو تر ټولو ډېر شمېر دی چې اجازه يې ورکړ شوې.
نو په همدې خاطر د اوس لپاره د همدې IP پتې کارنان نه شي کولای چې نور ګڼونونه جوړ کړي.',
'emailauthenticated'         => 'ستاسو برېښليک پته په $2 نېټه په $3 بجو د منلو وړ وګرځېده.',
'emailnotauthenticated'      => 'ستاسو د برېښليک پته لا تر اوسه پورې د منلو وړ نه ده ګرځېدلې. د اړوندو بېلوونکو نښو په هکله تاسو ته هېڅ کوم برېښليک نه لېږل کېږي.',
'noemailprefs'               => 'ددې لپاره چې دا کړنې کار وکړي نو تاسو يو برېښليک وټاکۍ.',
'emailconfirmlink'           => 'د خپل د برېښليک پتې پخلی وکړی',
'accountcreated'             => 'ګڼون مو جوړ شو.',
'accountcreatedtext'         => 'د $1 لپاره يو ګڼون جوړ شو.',
'createaccount-title'        => 'د {{SITENAME}} د ګڼون جوړېدنه',
'createaccount-text'         => 'يو چا د {{SITENAME}} په وېبځي ($4) کې ستاسې د برېښليک پتې لپاره د "$2" په نامه يو ګڼون جوړ کړی چې پټنوم يې "$3" دی.
تاسې بايد غونډال ته ورننوځۍ او همدا اوس خپل پټنوم بدل کړی.

که چېرته دا کړنه په تېروتنه کې شوی وي نو تاسې کولای شی چې دا پيغام بابېزه وګڼۍ.',
'usernamehasherror'          => 'کارن-نوم نشي کېدلای چې کرښکې لوښې ولري',
'loginlanguagelabel'         => 'ژبه: $1',

# JavaScript password checks
'password-strength'            => 'د پټنوم اټکليز سېک: $1',
'password-strength-bad'        => 'بد',
'password-strength-mediocre'   => 'منځګوړی',
'password-strength-acceptable' => 'د منلو وړ',
'password-strength-good'       => 'ښه',
'password-retype'              => 'پټنوم بيا وليکه',
'password-retype-mismatch'     => 'پټنوم مو کټ مټ د يو بل سره سمون نه خوري',

# Password reset dialog
'resetpass'                 => 'پټنوم بدلول',
'resetpass_header'          => 'د ګڼون پټنوم بدلول',
'oldpassword'               => 'زوړ پټنوم:',
'newpassword'               => 'نوی پټنوم:',
'retypenew'                 => 'نوی پټنوم بيا وليکه:',
'resetpass_submit'          => 'پټنوم مو وټاکۍ او بيا غونډال ته ورننوځۍ',
'resetpass_success'         => 'ستاسې پټنوم په برياليتوب سره بدل شو!
اوس غونډال کې د ورننوتلو په حال کې يو ...',
'resetpass_forbidden'       => 'پټنومونه مو نه شي بدلېدلای',
'resetpass-no-info'         => 'همدې مخ ته د لاسرسي موندلو پخاطر تاسې ته پکار ده چې لومړی غونډال ته ورننوځۍ.',
'resetpass-submit-loggedin' => 'پټنوم بدلول',
'resetpass-submit-cancel'   => 'ناګارل',
'resetpass-temp-password'   => 'لنډمهالی پټنوم:',

# Edit page toolbar
'bold_sample'     => 'زغرد متن',
'bold_tip'        => 'زغرد متن',
'italic_sample'   => 'کوږ ليک',
'italic_tip'      => 'کوږ ليک',
'link_sample'     => 'د تړن سرليک',
'link_tip'        => 'کورنۍ تړنه',
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
'sig_tip'         => 'ستاسې لاسليک د وخت د ټاپې سره',
'hr_tip'          => 'څنډيزه ليکه (ددې په کارولو کې سپما وکړۍ)',

# Edit pages
'summary'                          => 'لنډيز:',
'subject'                          => 'سکالو/سرليک:',
'minoredit'                        => 'دا يوه وړه سمونه ده',
'watchthis'                        => 'همدا مخ کتل',
'savearticle'                      => 'مخ خوندي کول',
'preview'                          => 'مخليدنه',
'showpreview'                      => 'مخليدنه',
'showlivepreview'                  => 'ژوندۍ مخکتنه',
'showdiff'                         => 'بدلونونه ښکاره کول',
'anoneditwarning'                  => "'''يادونه:''' تاسې غونډال ته نه ياست ننوتي. ستاسې IP پته به د دې مخ د سمونونو په پېښليک کې ثبت شي.",
'anonpreviewwarning'               => "''تاسې غونډال ته نه ياست ننوتي. خوندي کولو سره به ستاسې IP پته به د دې مخ د سمونونو په پېښليک کې ثبت شي.''",
'missingcommenttext'               => 'لطفاً تبصره لاندې وليکۍ.',
'summary-preview'                  => 'د لنډيز مخليدنه:',
'subject-preview'                  => 'موضوع/سرليک مخکتنه:',
'blockedtitle'                     => 'پر کارن بنديز لګېدلی',
'blockedtext'                      => "'''ستاسې د کارن-نوم يا آی پي پتې مخنيوی شوی.'''

همدا بنديز د $1 له خوا پر تاسې لږېدلی. او د همدې کړنې سبب ''$2'' دی.

* د بنديز د پېل نېټه: $8
* د بنديز د پای نېټه: $6
* بنديزونه دي پر: $7

تاسې کولای شی چې د $1 او يا هم د يو بل [[{{MediaWiki:Grouppage-sysop}}|پازوال]] سره اړيکې ټينګې کړی او د بنديز ستونزې مو هوارې کړی.
تاسې نه شی کولای چې د 'کارن ته برېښلک لېږل' کړنې نه ګټه پورته کړی تر څو چې تاسې د خپل ګڼون په [[Special:Preferences|غوره توبونو]] کې يوه کره برېښليک پته نه وي ځانګړې کړې او تر دې بريده چې پر تاسې د هغې د کارولو بنديز نه وي لګېدلی.
ستاسې د دم مهال آی پي پته $3 ده، او ستاسې د بنديز پېژند #$5 دی. مهرباني وکړۍ د خپلې يادونې پر مهال د دغو دوو څخه د يوه او يا هم د دواړو ورکول مه هېروۍ.",
'autoblockedtext'                  => 'په خپلکاريزه توګه ستاسې پر IP پتې بنديز لګېدلی، دا د دې په خاطر چې ستاسې پته د بل چا له خوا چې $1 پرې بنديز لګولی، کارېدلې.
او د بنديز سبب يې دا دی:

:\'\'$2\'\'

* د بنديز د پيل نېټه: $8
* د بنديز د پای نېټه: $6
* د بنديز د موخې سړی: $7

تاسې کولای شی چې د $1 سره او يا هم د [[{{MediaWiki:Grouppage-sysop}}|پازوالانو]]  له ډلې نه يو چا سره اړيکې ټينګې کړی او د بنديز په اړه مو ورسره خبرې وکړۍ.

دا مه هېروۍ چې تاسې د "کارن ته برېښليک لېږل" له اسانتياوؤ نه ګټه نه شی اخيستلای تر څو چې ستاسې د نومليکنې په وخت کې يا [[Special:Preferences|ستاسې د غوره توبونو په امستنو]] کې يوه کره برېښليک پته نه وي ځانګړې شوې، او يا هم د برېښليک لېږلو د چارو په کارولو مو بنديز نه وي لګېدلی.

ستاسې IP پته $3 ده او ستاسې د بنديز پېژند #$5 دی.
د بنديز اړونده د اړيکو نيولو په وخت کې لطفاً د پورتني مالوماتو يادونه وکړۍ.',
'blockednoreason'                  => 'هېڅ سبب نه دی ورکړ شوی',
'blockedoriginalsource'            => "د '''$1''' سرچينې لاندې ښودل شوي:",
'whitelistedittitle'               => 'که د سمادولو تکل لری نو بايد غونډال ته ورننوځۍ.',
'whitelistedittext'                => 'ددې لپاره چې سمادول ترسره کړی تاسو بايد $1.',
'nosuchsectiontitle'               => 'برخه و نه موندل شوه',
'nosuchsectiontext'                => 'تاسې د يوې داسې برخې د سمون هڅه کړې چې تر اوسه پورې نشته.
کېدای هغه مهال چې تاسې د دې مخ نه کتنه کوله، همدا برخه کوم بل ځای ته لېږدل شوې او يا هم ړنګه شوې وي.',
'loginreqtitle'                    => 'غونډال کې ننوتنه پکار ده',
'loginreqlink'                     => 'ننوتل',
'loginreqpagetext'                 => 'د نورو مخونو د کتلو لپاره تاسو بايد $1 وکړۍ.',
'accmailtitle'                     => 'پټنوم ولېږل شو.',
'newarticle'                       => '(نوی)',
'newarticletext'                   => "تاسې د يوې داسې تړنې څارنه کړې چې لا تر اوسه پورې نه شته.
که همدا مخ ليکل غواړۍ، نو په لانديني چوکاټ کې خپل متن وټاپۍ (د لا نورو مالوماتو لپاره د [[{{MediaWiki:Helppage}}|لارښود مخ]] وګورۍ).
که چېرته تاسې دلته په تېروتنه راغلي ياست، نو يواځې د خپل د کتنمل '''مخ پر شا''' تڼۍ مو وټوکۍ.",
'anontalkpagetext'                 => "----''دا د يوه ورکنومي کارن چې کارن-نوم نه لري او يا خپل کارن-نوم نه کاروي، د سکالو يوه پاڼه ده. نو د يوه کس د پېژندلو پخاطر موږ د هماغه کارن د انټرنېټ شمېره يا IP پته دلته ثبتوؤ. داسې يوه IP پته د ډېرو کارنانو لخوا هم کارېدلی شي. که تاسې يو ورکنومی کارن ياست او تاسې ته دا څرګندېږي چې تاسې ته نااړونده پېغامونه او تبصرې اشاره شوي، نو د نورو بې نومو کارنانو او ستاسې ترمېنځ د ټکنتوب د مخ نيونې لپاره لطفاً [[Special:UserLogin/signup|يو ګڼون جوړ کړۍ]] او يا هم [[Special:UserLogin|غونډال ته ورننوځۍ]].''",
'noarticletext'                    => 'دم مهال په دې مخ کې څه نشته.
تاسې کولای شی چې په نورو مخونو کې [[Special:Search/{{PAGENAME}}|د دې مخ د سرليک پلټنه]] يا
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} د اړوندو يادښتونو پلټنه] وکړی.
او يا [{{fullurl:{{FULLPAGENAME}}|action=edit}} همدا مخ سم کړی]</span>.',
'userpage-userdoesnotexist'        => 'د "$1" ګڼون نه دی ثبت شوی.
لطفاً ځان ډاډه کړۍ چې آيا تاسې په رښتيا همدا مخ جوړول که سمول غواړۍ.',
'userpage-userdoesnotexist-view'   => 'د "$1" ګڼون نه دی ثبت شوی.',
'blocked-notice-logextract'        => 'دم مهال په دې کارن بنديز لګېدلی.
دلته لاندې د بنديز تازه يادښت د سرچينې په توګه ورکړ شوی:',
'clearyourcache'                   => "'''يادونه:''' د غوره توبونو د خوندي کولو وروسته، ددې لپاره چې تاسو خپل سر ته رسولي ونجونه وګورۍ نو پکار ده چې د خپل بروزر ساتل شوې حافظه تازه کړی. د '''Mozilla / Firefox / Safari:''' لپاره د ''Shift'' تڼۍ نيولې وساتی کله مو چې په ''Reload''، ټک واهه، او يا هم ''Ctrl-Shift-R'' تڼۍ کېښکاږۍ (په Apple Mac کمپيوټر باندې ''Cmd-Shift-R'' کېښکاږۍ); '''IE:''' د ''Ctrl'' تڼۍ کېښکاږۍ کله مو چې په ''Refresh'' ټک واهه، او يا هم د ''Ctrl-F5'' تڼۍ کېښکاږۍ; '''Konqueror:''' بروزر کې يواځې ''Reload'' ته ټک ورکړۍ، او يا په ''F5''; د '''Opera''' کارنانو ته پکار ده چې په بشپړه توګه د خپل کمپيوټر ساتل شوې حافظه تازه کړي چې پدې توګه کېږي ''Tools→Preferences''.",
'usercsspreview'                   => "'''هېر مو نشي چې دا يوازې ستاسې د کارن CSS مخليدنه ده.'''
'''تر اوسه پورې لا ستاسې بدلونونه نه دي خوندي شوي!'''",
'userjspreview'                    => "'''هېر مو نشي چې دا يوازې ستاسې د کارن د جاوا سکرېپټ آزمېيل/مخليدنه ده.'''
'''تر اوسه پورې لا ستاسې بدلونونه نه دي خوندي شوي!'''",
'updated'                          => '(تازه)',
'note'                             => "'''يادونه:'''",
'previewnote'                      => "'''دا يواځې مخليدنه ده، تاسې چې کوم بدلونونه ترسره کړي، لا تر اوسه پورې نه دي خوندي شوي!'''",
'editing'                          => 'د $1 سمونه',
'editingsection'                   => 'سمونه $1 (برخه)',
'editingcomment'                   => 'د $1 سمون (نوې برخه)',
'editconflict'                     => 'په سمادولو کې خنډ: $1',
'yourtext'                         => 'ستاسو متن',
'storedversion'                    => 'زېرمه شوې مخکتنه',
'yourdiff'                         => 'توپيرونه',
'copyrightwarning'                 => "لطفاً په پام کې وساتۍ چې ټولې هغه ونډې چې تاسې يې {{SITENAME}} کې ترسره کوی هغه د $2 له مخې د خپرولو لپاره ګڼل کېږي (د لانورو تفصيلاتو لپاره $1 وګورۍ). که تاسې نه غواړۍ چې په ليکنو کې مو په بې رحمۍ سره لاسوهنې (سمونې) وشي او د نورو په غوښتنه پسې لانورې هم خپرې شي، نو دلته يې مه ځای پر ځای کوی..<br />
تاسې زمونږ سره دا ژمنه هم کوی چې تاسې پخپله دا ليکنه کښلې، او يا مو د ټولګړو پاڼو او يا ورته وړيا سرچينو نه کاپي کړې ده '''لطفاً د ليکوال د اجازې نه پرته د خوندي رښتو ليکنې مه خپروی!'''",
'longpageerror'                    => "'''ستونزه: کوم متن چې دلته تاسو ليکلی، $1 کيلوبايټه اوږد دی او دا د همدې مخ د لوړترين ټاکلي بريده، $2 کيلوبايټه، څخه اوږد دی.
ستاسو متن نه شي خوندي کېدلای.'''",
'protectedpagewarning'             => "'''ګواښنه: همدا مخ تړل شوی او يوازې هغه کارنان په دې مخ کې بدلونونه راوستلای شي چې د پازوالۍ د آسانتياوو نه برخمن دي.'''
ستاسې د مالوماتو لپاره د وروستني يادښت متن دلته په دې توګه راوړل شوی:",
'semiprotectedpagewarning'         => "'''پاملرنه:''' دا مخ تړل شوی او يواځې ثبت شوي کارنان کولای شي چې په دې مخ کې بدلونونه راولي.
ستاسې د مالوماتو لپاره د وروستني يادښت متن دلته په دې توګه راوړل شوی:",
'cascadeprotectedwarning'          => "'''ګواښنه:''' همدا مخ تړل شوی دی او يوازې هغه کارنان په دې مخ کې بدلونونه راوستلای شي چې د پازوالۍ د آسانتياوو نه برخمن دي، دا په دې خاطر چې همدا مخ د {{PLURAL:$1|لانديني مخ|لاندينيو مخونو}} په ځوړاوبيزې ژغورنې کې ورګډ دی:",
'titleprotectedwarning'            => "'''ګواښنه: همدا مخ تړل شوی دی او د دې د جوړولو لپاره تاسې ته د [[Special:ListGroupRights|ځانګړو رښتو]] د ترلاسه کولو اړتيا ده.'''
ستاسې د مالوماتو لپاره د وروستني يادښت متن دلته په دې توګه راوړل شوی:",
'templatesused'                    => 'په دې مخ کارېدلې {{PLURAL:$1|کينډۍ|کينډۍ}}:',
'templatesusedpreview'             => 'يه دې مخليدنه کارېدلې {{PLURAL:$1|کينډۍ|کينډۍ}}:',
'templatesusedsection'             => 'په دې برخه کې کارېدلي {{PLURAL:$1|کينډۍ|کينډۍ}}:',
'template-protected'               => '(ژغورلی)',
'template-semiprotected'           => '(نيم-ژغورلی)',
'hiddencategories'                 => 'دا مخ د {{PLURAL:$1|1 پټې وېشنيزې|$1 پټو وېشنيزو}} يو غړی دی:',
'nocreatetitle'                    => 'د مخ جوړول بريد ټاکلی دی',
'nocreatetext'                     => '{{SITENAME}} د نوو مخونو د جوړولو وړتيا محدوده کړې.
تاسو بېرته پر شا تللای شی او په شته مخونو کې سمونې ترسره کولای شی، او يا هم [[Special:UserLogin|غونډال ته ننوتلای او يو ګڼون جوړولای شی]].',
'nocreate-loggedin'                => 'تاسو د نوو مخونو د جوړولو اجازه نه لری.',
'sectioneditnotsupported-title'    => 'د برخې د سمون ملاتړ نه کېږي',
'sectioneditnotsupported-text'     => 'په دې مخ د برخې د سمون ملاتړ نه کېږي.',
'permissionserrors'                => 'د اجازې ستونزې',
'permissionserrorstext'            => 'تاسې د لاندې {{PLURAL:$1|سبب|سببونو}} پخاطر د دې کړنې اجازه نه لرۍ:',
'permissionserrorstext-withaction' => 'تاسې د $2 اجازه نه لری، دا د {{PLURAL:$1|دغه سبب|دغو سببونو}} پخاطر:',
'recreate-moveddeleted-warn'       => "'''ګواښنه: تاسې د يوه داسې مخ بياجوړونه کوۍ کوم چې يو ځل پخوا ړنګ شوی وو.'''

پکار ده چې تاسې په دې ځان پوه کړۍ چې ايا دا تاسې ته وړ ده چې د همدې مخ جوړول په پرله پسې توګه وکړۍ.
ستاسې د اسانتياوو لپاره د همدې مخ د ړنګېدلو يادښت هم ورکړ شوی:",
'moveddeleted-notice'              => 'دا مخ ړنګ شوی.
دلته لاندې د دې مخ د ړنګېدنې او لېږدېدنې يادښت د سرچينې په توګه ورکړ شوی.',
'log-fulllog'                      => 'بشپړ يادښت کتل',
'edit-gone-missing'                => 'د دې مخ اوسمهالول و نه کړای شول.
داسې ښکاري چې دا مخ ړنګ شوی.',
'edit-conflict'                    => 'د سمولو خنډ',
'edit-no-change'                   => 'ستاسې سمون بابېزه وګڼل شو، دا ځکه چې تاسې په متن کې کوم بدلون نه دی راوستلی.',
'edit-already-exists'              => 'په دې نوم يو نوی مخ جوړ نه شو.
پدې نوم د پخوا نه يو مخ شته.',

# "Undo" feature
'undo-norev' => 'دا سمون ناکړ کېدلای نه شي دا ځکه چې دا سمون نشته او يا هم ړنګ شوی.',

# Account creation failure
'cantcreateaccounttitle' => 'ګڼون نه شي جوړېدای',

# History pages
'viewpagelogs'           => 'د دې مخ يادښتونه کتل',
'nohistory'              => 'ددې مخ لپاره د سمادېدنې هېڅ کوم پېښليک نه شته.',
'currentrev'             => 'اوسنۍ بڼه',
'currentrev-asof'        => 'د $1 پورې تازه بڼه',
'revisionasof'           => 'د $1 بڼه',
'revision-info'          => 'د $1 پورې شته مخليدنه، د $2 لخوا ترسره شوې',
'previousrevision'       => '← زړه بڼه',
'nextrevision'           => '← نوې بڼه',
'currentrevisionlink'    => 'اوسنۍ بڼه',
'cur'                    => 'اوسنی',
'next'                   => 'راتلونکي',
'last'                   => 'وروستنی',
'page_first'             => 'لومړنی',
'page_last'              => 'وروستنی',
'histlegend'             => 'د توپير ټاکنه: د هرې هغې بڼې پرتلنه چې تاسې غواړۍ نو د هماغې بڼې چوکاټک په نښه کړی او بيا په لاندينۍ تڼۍ وټوکۍ.<br />
لنډيز: (اوس) = د اوسنۍ بڼې سره توپير،
(وروست) = د وروستۍ بڼې سره توپير، و = وړه سمونه.',
'history-fieldset-title' => 'پېښليک سپړل',
'history-show-deleted'   => 'يواځې ړنګ شوي',
'histfirst'              => 'پخواني',
'histlast'               => 'تازه',
'historysize'            => '({{PLURAL:$1|1 بايټ|$1 بايټونه}})',
'historyempty'           => '(تش)',

# Revision feed
'history-feed-title'          => 'د مخکتنو پېښليک',
'history-feed-item-nocomment' => '$1 په $2',
'history-feed-empty'          => 'ستاسې غوښتلی مخ نه شته.
کېدای شي چې دا له ويکي نه ړنګ شوی وي، او يا هم په بل نوم بدل شوی وي.
تاسې په دې ويکي د اړوندو نوؤ مخونو لپاره [[Special:Search|د پلټنې هڅه وکړۍ]].',

# Revision deletion
'rev-deleted-comment'        => '(تبصره ليري شوې)',
'rev-deleted-user'           => '(کارن-نوم ليري شوی)',
'rev-delundel'               => 'ښکاره کول/ پټول',
'rev-showdeleted'            => 'ښکاره کول',
'revisiondelete'             => 'د ړنګولو/ناړنګولو مخکتنې',
'revdelete-nologtype-title'  => 'د يادښت ډول نه دی ځانګړی شوی',
'revdelete-no-file'          => 'ځانګړې شوې دوتنه نشته.',
'revdelete-show-file-submit' => 'هو',
'revdelete-selected'         => "'''د [[:$1]] {{PLURAL:$2|ټاکلې بڼه|ټاکلې بڼې}}:'''",
'revdelete-hide-text'        => 'د مخکتنې متن پټول',
'revdelete-hide-image'       => 'د دوتنې مېنځپانګه پټول',
'revdelete-hide-name'        => 'کړنه او موخه پټول',
'revdelete-hide-comment'     => 'د سمون لنډيز پټول',
'revdelete-radio-same'       => '(مه بدلوه)',
'revdelete-radio-set'        => 'هو',
'revdelete-radio-unset'      => 'نه',
'revdelete-log'              => 'سبب:',
'revdel-restore'             => 'ښکارېدنه بدلول',
'revdel-restore-deleted'     => 'ړنګې شوې بڼې',
'revdel-restore-visible'     => 'ښکاره بڼې',
'pagehist'                   => 'د مخ پېښليک',
'deletedhist'                => 'د ړنګولو پېښليک',
'revdelete-content'          => 'مېنځپانګه',
'revdelete-summary'          => 'لنډيز سمول',
'revdelete-uname'            => 'کارن-نوم',
'revdelete-hid'              => '$1 پټول',
'revdelete-unhid'            => '$1 ښکاره کول',
'revdelete-reason-dropdown'  => '*د ړنګولو ټولګړي سببونه
** د خپرېدو د رښتو سرغړونه
** ناسم شخصي مالومات
** Potentially libelous information',
'revdelete-otherreason'      => 'بل/اضافي سبب:',
'revdelete-reasonotherlist'  => 'بل سبب',
'revdelete-edit-reasonlist'  => 'د ړنګولو سببونه سمول',
'revdelete-offender'         => 'د مخکتنې ليکوال:',

# Revision move
'revmove-submit'               => 'بڼې ټاکلي مخ ته لېږدول',
'revisionmoveselectedversions' => 'ټاکلې بڼې لېږدول',
'revmove-reasonfield'          => 'سبب:',
'revmove-titlefield'           => 'د موخې مخ:',
'revmove-badparam-title'       => 'بد پاراميترونه',
'revmove-nullmove-title'       => 'بد سرليک',

# History merging
'mergehistory'                     => 'د مخ پېښليکونه سره يوځای کول',
'mergehistory-from'                => 'د سرچينې مخ:',
'mergehistory-into'                => 'د موخې مخ:',
'mergehistory-submit'              => 'بڼې سره يوځای کول',
'mergehistory-no-source'           => 'د سرچينې مخ $1 نشته.',
'mergehistory-no-destination'      => 'د $1 موخنيز مخ نشته.',
'mergehistory-invalid-source'      => 'د سرچينې مخ بايد يو سم سرليک وي.',
'mergehistory-invalid-destination' => 'د موخې مخ بايد يو سم سرليک وي.',
'mergehistory-reason'              => 'سبب:',

# Merge log
'revertmerge' => 'بېلول',

# Diffs
'history-title'            => 'د "$1" د پېښليک بڼه',
'difference'               => '(د بڼو تر مېنځ توپير)',
'difference-multipage'     => '(د مخونو تر مېنځ توپير)',
'lineno'                   => '$1 کرښه:',
'compareselectedversions'  => 'ټاکلې بڼې سره پرتلل',
'showhideselectedversions' => 'ټاکلې بڼې ښکاره کول/پټول',
'editundo'                 => 'ناکړ',
'diff-multi'               => ' د ({{PLURAL:$2| يو کارن|$2 کارنانو}} لخوا {{PLURAL:$1|يوه منځګړې بڼه|$1 منځګړې بڼې}}د  نه ده ښکاره شوې)',

# Search results
'searchresults'                    => 'د پلټنې پايلې',
'searchresults-title'              => 'د "$1" د پلټنې پايلې',
'searchresulttext'                 => 'په {{SITENAME}} کې د لټون د نورو مالوماتو لپاره، [[{{MediaWiki:Helppage}}|{{int:لارښود}}]] وګورۍ.',
'searchsubtitle'                   => 'تاسې د \'\'\'[[:$1]]\'\'\' لپاره پلټنه کړې ([[Special:Prefixindex/$1|ټول هغه مخونه چې په "$1" پېلېږي]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|ټول هغه مخونه چې "$1" سره تړنې لري]])',
'searchsubtitleinvalid'            => "تاسې د '''$1''' لټون کړی",
'titlematches'                     => 'د مخ سرليک ورسره ورته دی',
'notitlematches'                   => 'د هېڅ يوه مخ سرليک ورسره ورته نه دی',
'textmatches'                      => 'د مخ متن ورسره ورته دی',
'notextmatches'                    => 'د هېڅ کوم مخ متن ورسره سمون نه خوري',
'prevn'                            => 'تېر {{PLURAL:$1|$1}}',
'nextn'                            => 'راتلونکي {{PLURAL:$1|$1}}',
'prevn-title'                      => 'تېر $1 {{PLURAL:$1|پايله|پايلې}}',
'nextn-title'                      => 'راتلونکې $1 {{PLURAL:$1|پايله|پايلې}}',
'shown-title'                      => 'په هر مخ $1 {{PLURAL:$1|پايله|پايلې}} ښکاره کول',
'viewprevnext'                     => '($1 {{int:pipe-separator}} $2) ($3) کتل',
'searchmenu-legend'                => 'د پلټلو خوښنې',
'searchmenu-exists'                => "'''په دې ويکي يو مخ د \"[[:\$1]]\" په نامه دی'''",
'searchmenu-new'                   => "'''په دې ويکي د \"[[:\$1]]\" مخ جوړول!'''",
'searchhelp-url'                   => 'Help:لړليک',
'searchprofile-articles'           => 'مېنځپانګيز مخونه',
'searchprofile-project'            => 'د لارښود او پروژې مخونه',
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'هرڅه',
'searchprofile-advanced'           => 'پرمختللی',
'searchprofile-articles-tooltip'   => 'په $1 کې پلټل',
'searchprofile-project-tooltip'    => 'په $1 کې پلټل',
'searchprofile-images-tooltip'     => 'د دوتنو پلټنه',
'searchprofile-everything-tooltip' => 'د ټولې مېنځپانګې پلټنه (د خبرو اترو مخونو سره)',
'search-result-size'               => '$1 ({{PLURAL:$2|1 ويی|$2 وييونه}})',
'search-result-category-size'      => '{{PLURAL:$1|1 غړی|$1 غړي}} ({{PLURAL:$2|1 څېرمه وېشنيزه|$2 څېرمه وېشنيزې}}، {{PLURAL:$3|1 دوتنه|$3 دوتنې}})',
'search-result-score'              => 'اړوندتوب: $1%',
'search-redirect'                  => '(د $1 مخ ګرځونه)',
'search-section'                   => '(برخه $1)',
'search-suggest'                   => 'آيا همدا مو موخه وه: $1',
'search-interwiki-caption'         => 'خورلڼې پروژې',
'search-interwiki-default'         => '$1 پايلې:',
'search-interwiki-more'            => '(نور)',
'search-mwsuggest-enabled'         => 'د وړانديزونو سره',
'search-mwsuggest-disabled'        => 'له وړانديزونو نه پرته',
'search-relatedarticle'            => 'اړونده',
'mwsuggest-disable'                => 'د AJAX وړانديزونه ناچارن کول',
'searcheverything-enable'          => 'په ټولو نوم-تشيالونو کې پلټل',
'searchrelated'                    => 'اړونده',
'searchall'                        => 'ټول',
'showingresultsheader'             => "د «'''$4'''» لپاره {{PLURAL:$5|له '''$1''' نه تر '''$3''' پايله|له '''$1 نه تر $2''' پايلې، ټولې پايلې '''$3''' }}",
'nonefound'                        => "'''يادښت''': يوازې يو څو نوم-تشيالونو په تلواليزه توګه پلټل کېږي.
د ''ټول:'' مختاړي په کارولو سره به ستاسې د پلټنې لپاره، په ټوله مېنځپانګه کې پلټنه وشي (د خبرواترو، کينډۍ او نورو مخونو په ګډون), او يا هم د خپلې خوښې نوم-تشيال د مختاړي په توګه وکاروۍ.",
'powersearch'                      => 'ژوره پلټنه',
'powersearch-legend'               => 'ژوره پلټنه',
'powersearch-ns'                   => 'په نوم-تشيالونو کې پلټنه:',
'powersearch-redir'                => 'مخ ګرځونې په لړليک کې اوډل',
'powersearch-field'                => 'پلټنه د',
'powersearch-togglelabel'          => 'کره کتل:',
'powersearch-toggleall'            => 'ټول',
'powersearch-togglenone'           => 'هېڅ',
'search-external'                  => 'باندنۍ پلټنه',

# Quickbar
'qbsettings-none'          => 'هېڅ',
'qbsettings-fixedleft'     => 'ثابته کيڼ',
'qbsettings-fixedright'    => 'ثابته ښي',
'qbsettings-floatingleft'  => 'کيڼه لامبا',
'qbsettings-floatingright' => 'ښي لامبا',

# Preferences page
'preferences'                   => 'غوره توبونه',
'mypreferences'                 => 'زما غوره توبونه',
'prefs-edits'                   => 'د سمونو شمېر:',
'prefsnologin'                  => 'غونډال کې نه ياست ننوتي',
'prefsnologintext'              => 'د دې لپاره چې خپل غوره توبونه مو وټاکی، نو پکار ده چې لومړی تاسو غونډال کې <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} ننوځی]</span>.',
'changepassword'                => 'پټنوم بدلول',
'prefs-skin'                    => 'بڼه',
'skin-preview'                  => 'مخکتنه',
'prefs-math'                    => 'شمېرپوهنه',
'datedefault'                   => 'هېڅ نه ټاکل',
'prefs-datetime'                => 'نېټه او وخت',
'prefs-personal'                => 'د کارن پېژنليک',
'prefs-rc'                      => 'وروستي بدلونونه',
'prefs-watchlist'               => 'کتنلړ',
'prefs-watchlist-days'          => 'د ورځو شمېر چې په کتلي لړليک کې به ښکاري:',
'prefs-watchlist-days-max'      => 'اکثر بريد 7 ورځې',
'prefs-watchlist-edits-max'     => 'د شمېر اکثر بريد: 1000',
'prefs-misc'                    => 'بېلابېل',
'prefs-resetpass'               => 'پټنوم بدلول',
'prefs-email'                   => 'د برېښليک خوښنې',
'prefs-rendering'               => 'ښکارېدنه',
'saveprefs'                     => 'خوندي کول',
'resetprefs'                    => 'بيا سمول',
'restoreprefs'                  => 'ټولې تلواليزې امستنې پرځای کول',
'prefs-editing'                 => 'د سمولو په حال کې',
'prefs-edit-boxsize'            => 'د سمون کړکۍ کچه.',
'rows'                          => 'ليکې:',
'columns'                       => 'ستنې:',
'searchresultshead'             => 'پلټل',
'resultsperpage'                => 'په هر مخ کې د پايلو شمېر:',
'stub-threshold-disabled'       => 'ناچارند شوی',
'recentchangesdays'             => 'د هغو ورځو شمېر وټاکی چې په وروستي بدلونو کې يې ليدل غواړی:',
'recentchangescount'            => 'د هغو سمونو شمېر چې په تلواليزه بڼه ښکاره بايد شي:',
'prefs-help-recentchangescount' => 'پدې کې د وروستني بدلونونو، د مخونو د پېښليکونو او يادښتونه شامل دي.',
'savedprefs'                    => 'ستاسو غوره توبونه خوندي شوه.',
'timezonelegend'                => 'د وخت سيمه:',
'localtime'                     => 'سيمه ايز وخت:',
'timezoneuseserverdefault'      => 'د پالنګر تلواليزه بڼه کارول',
'timezoneuseoffset'             => 'بل (توپير ځانګړی کړی)',
'timezoneoffset'                => 'توپير¹:',
'servertime'                    => 'د پالنګر وخت:',
'guesstimezone'                 => 'له کتنمل نه ډکول',
'timezoneregion-africa'         => 'افريقا',
'timezoneregion-america'        => 'امريکا',
'timezoneregion-antarctica'     => 'انټارکټيکا',
'timezoneregion-arctic'         => 'آرکټيک',
'timezoneregion-asia'           => 'آسيا',
'timezoneregion-atlantic'       => 'د اطلس سمندر',
'timezoneregion-australia'      => 'آسټراليا',
'timezoneregion-europe'         => 'اروپا',
'timezoneregion-indian'         => 'هندی سمندر',
'timezoneregion-pacific'        => 'آرام سمندر',
'allowemail'                    => 'د نورو کارنانو لخوا د برېښليک رالېږل چارن کړه',
'prefs-searchoptions'           => 'د پلټلو خوښنې',
'prefs-namespaces'              => 'نوم-تشيالونه',
'defaultns'                     => 'او يا هم په دغو نوم-تشيالونو کې پلټل:',
'default'                       => 'تلواليز',
'prefs-files'                   => 'دوتنې',
'prefs-custom-css'              => 'ځاني CSS',
'prefs-custom-js'               => 'ځاني جاواسکرېپټ',
'prefs-common-css-js'           => 'د ټولو پوښونو لپاره د CSS/جاواسکرېپټ دوتنه:',
'prefs-emailconfirm-label'      => 'د برېښليک باورتيا:',
'prefs-textboxsize'             => 'د سمون کړکۍ کچه',
'youremail'                     => 'برېښليک *',
'username'                      => 'کارن-نوم:',
'uid'                           => 'د کارن پېژندنه:',
'prefs-memberingroups'          => 'د {{PLURAL:$1|ډلې|ډلو}} غړی:',
'prefs-registration'            => 'د نومليکنې وخت:',
'yourrealname'                  => 'اصلي نوم:',
'yourlanguage'                  => 'ژبه:',
'yournick'                      => 'کورنی نوم:',
'badsiglength'                  => 'ستاسو لاسليک ډېر اوږد دی.
بايد چې لاسليک مو له $1 {{PLURAL:$1|توري|تورو}} نه لږ وي.',
'yourgender'                    => 'جنس:',
'gender-unknown'                => 'ناڅرګنده',
'gender-male'                   => 'نارينه',
'gender-female'                 => 'ښځه',
'email'                         => 'برېښليک',
'prefs-help-realname'           => 'د اصلي نوم ليکل ستاسو په خوښه دی خو که تاسو خپل اصلي نوم وټاکۍ پدې سره به ستاسو ټول کارونه او ونډې ستاسو د نوم په اړوندولو کې وکارېږي.',
'prefs-help-email'              => 'د برېښليک ورکړه ستاسې په خوښه ده، خو په ورکړې سره به يې د يوه نوي پټنوم د لېږلو چار آسانه کړي هغه هم کله چې تاسې نه خپل پټنوم هېر شوی وي.',
'prefs-help-email-required'     => 'ستاسو د برېښليک پته پکار ده.',
'prefs-info'                    => 'بنسټيزه مالومات',
'prefs-i18n'                    => 'نړېوالتوب',
'prefs-signature'               => 'لاسليک',
'prefs-dateformat'              => 'د نېټې بڼه',
'prefs-timeoffset'              => 'د وخت واټن',
'prefs-advancedediting'         => 'پرمختللې خوښنې',
'prefs-advancedrc'              => 'پرمختللې خوښنې',
'prefs-advancedrendering'       => 'پرمختللې خوښنې',
'prefs-advancedsearchoptions'   => 'پرمختللې خوښنې',
'prefs-advancedwatchlist'       => 'پرمختللې خوښنې',
'prefs-displayrc'               => 'د ښکارېدنې خوښنې',
'prefs-displaysearchoptions'    => 'د ښکارېدنې خوښنې',
'prefs-displaywatchlist'        => 'د ښکارېدنې خوښنې',
'prefs-diffs'                   => 'توپيرونه',

# User rights
'userrights'                  => 'د کارن رښتو سمبالښت',
'userrights-lookup-user'      => 'کارن ډلې سمبالول',
'userrights-user-editname'    => 'يو کارن نوم وليکۍ:',
'editusergroup'               => 'کارن ډلې سمول',
'userrights-editusergroup'    => 'کارن ډلې سمول',
'saveusergroups'              => 'کارن ډلې خوندي کول',
'userrights-groupsmember'     => 'غړی د:',
'userrights-reason'           => 'سبب:',
'userrights-changeable-col'   => 'هغه ډلې چې تاسې يې بدلولی شی',
'userrights-unchangeable-col' => 'هغه ډلې چې تاسې يې نه شی بدلولی',

# Groups
'group'            => 'ډله:',
'group-user'       => 'کارنان',
'group-bot'        => 'روباټونه',
'group-sysop'      => 'پازوالان',
'group-bureaucrat' => 'بيوروکراټان',
'group-suppress'   => 'څارونکي',
'group-all'        => '(ټول)',

'group-user-member'       => 'کارن',
'group-bot-member'        => 'روباټ',
'group-sysop-member'      => 'پازوال',
'group-bureaucrat-member' => 'بيوروکراټ',
'group-suppress-member'   => 'څارن',

'grouppage-user'       => '{{ns:project}}:کارنان',
'grouppage-bot'        => '{{ns:project}}:روباټان',
'grouppage-sysop'      => '{{ns:project}}:پازوالان',
'grouppage-bureaucrat' => '{{ns:project}}:بيوروکراټان',
'grouppage-suppress'   => '{{ns:project}}:څارن',

# Rights
'right-read'                 => 'مخونه لوستل',
'right-edit'                 => 'مخونه سمول',
'right-createpage'           => 'مخونه جوړول (هغه چې د خبرو اترو مخونه نه دي)',
'right-createtalk'           => 'د خبرو اترو مخونه جوړول',
'right-createaccount'        => 'نوي کارن حسابونه جوړول',
'right-minoredit'            => 'سمونونه واړه په نخښه کول',
'right-move'                 => 'مخونه لېږدول',
'right-move-subpages'        => 'مخونه د خپلو څېرمه مخونو سره لېږدول',
'right-movefile'             => 'دوتنې لېږدول',
'right-upload'               => 'دوتنې پورته کول',
'right-delete'               => 'مخونه ړنګول',
'right-bigdelete'            => 'د اوږدو پېښليکونو مخونه ړنګول',
'right-browsearchive'        => 'ړنګ شوي مخونه پلټل',
'right-undelete'             => 'يو مخ ناړنګول',
'right-suppressionlog'       => 'شخصي يادښتونه کتل',
'right-block'                => 'پر نورو کارنانو د سمون د آسانتياوؤ بنديز لګول',
'right-blockemail'           => 'پر يوه کارن د برېښليک لېږلو بنديز لګول',
'right-hideuser'             => 'پر يوه کارن-نوم بنديز لګول او له خلکو نه يې پټول',
'right-protect'              => 'د ژغورنې کچه بدلول او ژغورلي مخونه سمول',
'right-editinterface'        => 'د کارن ليدنمخ سمول',
'right-editusercssjs'        => 'د نورو کارنانو د CSS او JS (جاوا سکرېپټ) دوتنې سمول',
'right-editusercss'          => 'د نورو کارنانو د CSS دوتنې سمول',
'right-edituserjs'           => 'د نورو کارنانو د JS (جاوا سکرېپټ) دوتنې سمول',
'right-unwatchedpages'       => 'د ناکتلو مخونو يو لړليک کتل',
'right-userrights'           => 'د کارن ټولې رښتې سمول',
'right-userrights-interwiki' => 'په نورو ويکي ګانو د نورو کارنانو  کارن-رښتې سمول',
'right-reset-passwords'      => 'د نورو کارنانو پټتوري بياامستل',
'right-sendemail'            => 'نورو کارنانو ته برېښليک لېږل',
'right-disableaccount'       => 'ګڼونونه ناچارنول',

# User rights log
'rightslog'     => 'د کارن د رښتو يادښت',
'rightslogtext' => 'دا د کارن رښتو د بدلونونو يو يادښت دی',
'rightsnone'    => '(هېڅ)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'           => 'همدا مخ لوستل',
'action-edit'           => 'دا مخ سمول',
'action-createpage'     => 'مخونه جوړول',
'action-createtalk'     => 'د خبرو اترو مخونه جوړول',
'action-createaccount'  => 'دا ګڼون جوړول',
'action-minoredit'      => 'دا سمون وړوکی په نخښه کول',
'action-move'           => 'همدا مخ لېږدول',
'action-movefile'       => 'همدا دوتنه لېږدول',
'action-upload'         => 'همدا دوتنه پورته کول',
'action-delete'         => 'همدا مخ ړنګول',
'action-deleterevision' => 'دا مخکتنه ړنګول',
'action-deletedhistory' => 'د دې مخ ړنګ شوی پېښليک کتل',
'action-browsearchive'  => 'ړنګ مخونه پلټل',
'action-undelete'       => 'همدا مخ ناړنګول',
'action-block'          => 'پر دې کارن د سمون د آسانتياوؤ بنديز لګول',
'action-protect'        => 'د دې مخ د ژغورنې کچه بدلول',
'action-userrights'     => 'د کارن ټولې رښتې سمول',

# Recent changes
'nchanges'                        => '$1 {{PLURAL:$1|بدلون|بدلونونه}}',
'recentchanges'                   => 'وروستي بدلونونه',
'recentchanges-legend'            => 'د ورستي بدلونو خوښنې',
'recentchangestext'               => 'په همدې مخ باندې د ويکي ترټولو تازه وروستي بدلونونه وڅارۍ.',
'recentchanges-feed-description'  => 'همدلته د ويکي ترټولو تازه وروستي بدلونونه وڅارۍ او وګورۍ چې څه پېښ شوي.',
'recentchanges-label-newpage'     => 'دغه سمون يو نوی مخ جوړ کړی',
'recentchanges-label-minor'       => 'دا يوه وړه سمونه ده',
'recentchanges-label-bot'         => 'دغه سمون د يو روباټ لخوا ترسره شوی',
'recentchanges-label-unpatrolled' => 'دغه سمون تر اوسه پورې نه دی څارل شوی',
'rcnote'                          => "دلته لاندې {{PLURAL:$1|وروستی '''1''' بدلون دی|وروستي '''$1''' بدلونونه دي}} چې په  {{PLURAL:$2| يوې ورځ|'''$2''' ورځو}} کې تر $4 نېټې او $5 بجو پېښ شوي.",
'rcnotefrom'                      => "په همدې ځای کې لاندې هغه بدلونونه دي چې د '''$2''' نه راپدېخوا پېښ شوي (تر '''$1''' پورې ښکاره شوي).",
'rclistfrom'                      => 'هغه بدلونونه ښکاره کړی چې له $1 نه پيلېږي',
'rcshowhideminor'                 => 'وړې سمونې $1',
'rcshowhidebots'                  => 'روباټ $1',
'rcshowhideliu'                   => 'غونډال کې ننوتي کارنان $1',
'rcshowhideanons'                 => 'بې نومه کارنان $1',
'rcshowhidepatr'                  => '$1 څارلې سمونې',
'rcshowhidemine'                  => 'زما سمونې $1',
'rclinks'                         => 'هغه وروستي $1 بدلونونه ښکاره کړی چې په $2 ورځو کې پېښ شوي<br />$3',
'diff'                            => 'توپير',
'hist'                            => 'پېښليک',
'hide'                            => 'پټول',
'show'                            => 'ښکاره کول',
'minoreditletter'                 => 'و',
'newpageletter'                   => 'نوی',
'boteditletter'                   => 'روباټ',
'rc_categories_any'               => 'هر يو',
'newsectionsummary'               => '/* $1 */ نوې برخه',
'rc-enhanced-expand'              => 'تفصيل ښکاره کول (د دې لپاره د JavaScript اړتيا ده)',
'rc-enhanced-hide'                => 'تفصيل پټول',

# Recent changes linked
'recentchangeslinked'          => 'اړونده بدلونونه',
'recentchangeslinked-feed'     => 'اړونده بدلونونه',
'recentchangeslinked-toolbox'  => 'اړونده بدلونونه',
'recentchangeslinked-title'    => '"$1" ته اړونده بدلونونه',
'recentchangeslinked-noresult' => 'په دې موده، په تړل شويو مخونو کې هېڅ کوم بدلونونه نه دي راپېښ شوي.',
'recentchangeslinked-summary'  => "دا د هغه بدلونونو لړليک دی چې وروستۍ ځل په تړن لرونکيو مخونو کې د يوه ځانګړي مخ (او يا هم د يوې ځانګړې وېشنيزې غړو) نه رامېنځ ته شوي.
[[Special:Watchlist|ستاسې د کتنلړ]] مخونه په '''زغرد ليک''' کې ښکاري.",
'recentchangeslinked-page'     => 'د مخ نوم:',
'recentchangeslinked-to'       => 'د ورکړل شوي مخ پر ځای د اړونده تړلي مخونو بدلونونه ښکاره کول',

# Upload
'upload'                => 'دوتنه پورته کول',
'uploadbtn'             => 'دوتنه پورته کول',
'uploadnologin'         => 'غونډال کې نه ياست ننوتي',
'uploadnologintext'     => 'ددې لپاره چې دوتنې پورته کړای شۍ، تاسو ته پکار ده چې لومړی غونډال کې [[Special:UserLogin|ننوتنه]] ترسره کړی.',
'uploaderror'           => 'د پورته کولو ستونزه',
'uploadtext'            => "د دوتنې د پورته کېدو لپاره لاندينی چوکاټ وکاروۍ.
که چېرته د پخونيو پورته شويو دوتنو کتل او پلټل غواړۍ نو [[Special:FileList|د پورته شويو دوتنو لړليک]] ته ورشۍ، [[Special:Log/upload|د (بيا) پورته شويو دوتنو يادښتونه]] او [[Special:Log/delete|د ړنګېدو يادښتونه]] هم کتلای شی.

ددې لپاره چې يوه مخ ته انځور ورواچوی، نو بيا پدې ډول تړنې (لېنک) وکاروی
* د يوې دوتنې د بشپړې بڼې د کارولو په موخه د '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>''' کوډ وکاروۍ.
* د '۲۰۰ پېکسل' په کچه د 'بټنوک' په توګه د يوې دوتنې کارول چې د مخ کيڼې څنډې کې او ترلاندې 'د انځور څرګندونې' ولري، نو د دې موخې لپاره د '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|بټنوک|کيڼ|د انځور څرګندونې]]</nowiki></tt>''' کوډ وکاروۍ.
* د انځور د ښودلو نه پرته، د دوتنې سره د سيخې تړنې لپاره د '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>''' کوډ وکاروۍ.",
'uploadlog'             => 'د پورته شويو دوتنو يادښت',
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
'empty-file'            => 'کومه دوتنه چې تاسې دلته سپارلې هغه تشه ده.',
'file-too-large'        => 'کومه دوتنه چې تاسې دلته سپارلې ډېره لويه ده.',
'filename-tooshort'     => 'د دوتنې نوم ډېر لنډ دی',
'filetype-banned'       => 'د دې بڼې په دوتنې بنديز دی',
'tmp-create-error'      => 'لنډمهاله دوتنه جوړېدای نه شي',
'fileexists'            => "د پخوا نه پدې نوم يوه دوتنه شته، که تاسو ډاډه نه ياست او يا هم که تاسو غواړۍ چې بدلون پکې راولۍ، لطفاً '''<tt>[[:$1]]</tt>''' وګورۍ.
[[$1|thumb]]",
'fileexists-extension'  => "په همدې نوم يوه بله دوتنه د پخوا نه شته: [[$2|thumb]]
* د پورته کېدونکې دوتنې نوم: '''<tt>[[:$1]]</tt>'''
* د پخوا نه شته دوتنه: '''<tt>[[:$2]]</tt>'''
لطفاً يو داسې نوم وټاکی چې د پخوانۍ دوتنې سره توپير ولري.",
'fileexists-forbidden'  => 'د پخوا نه پدې نوم يوه دوتنه شته، او په دې نوم بله دوتنه نه پورته کېږي.
که تاسې بيا هم د خپلې دوتنې پورته کول غواړۍ، نو لطفاً بېرته وګرځۍ او همدغه دوتنه بيا په يوه نوي نوم پورته کړی.
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate' => 'همدا دوتنه د {{PLURAL:$1|لاندينۍ دوتنې|لاندينيو دوتنو}} غبرګه لمېسه ده:',
'savefile'              => 'دوتنه خوندي کړه',
'uploadedimage'         => '"[[$1]]" پورته شوه',
'uploaddisabled'        => 'پورته کول ناچارن شوي',
'uploadvirus'           => 'دا دوتنه ويروس لري! تفصيل: $1',
'upload-source'         => 'سرچينيزه دوتنه',
'sourcefilename'        => 'د سرچينيزې دوتنې نوم:',
'sourceurl'             => 'د URL سرچينه:',
'destfilename'          => 'د موخنيزې دوتنې نوم:',
'upload-maxfilesize'    => 'د دوتنې تر ټولو لويه کچه: $1',
'upload-description'    => 'د دوتنې څرګندونې',
'upload-options'        => 'د پورته کولو خوښنې',
'watchthisupload'       => 'همدا دوتنه کتل',
'upload-success-subj'   => 'دوتنه پورته کېدل په برياليتوب سره ترسره شو',
'upload-failure-subj'   => 'د پورته کېدو ستونزه',

'upload-file-error'   => 'کورنۍ ستونزه',
'upload-unknown-size' => 'ناڅرګنده کچه',
'upload-http-error'   => 'د HTTP يوه ستونزه رامېنځ ته شوې: $1',

# img_auth script messages
'img-auth-accessdenied' => 'لاسرسی رد شو',
'img-auth-nofile'       => 'د $1 په نوم کومه دوتنه نشته.',

# HTTP errors
'http-invalid-url' => 'ناسم URL: $1',
'http-read-error'  => 'د HTTP د لوستلو ستونزه.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'URL ته لاسرسی و نه شو',

'license'            => 'منښتليک:',
'license-header'     => 'منښتليک:',
'nolicense'          => 'هېڅ نه دي ټاکل شوي',
'license-nopreview'  => '(مخليدنه نشته)',
'upload_source_file' => '(ستاسو په کمپيوټر کې يوه دوتنه)',

# Special:ListFiles
'listfiles_search_for'  => 'د انځور د نوم لټون:',
'imgfile'               => 'دوتنه',
'listfiles'             => 'د دوتنو لړليک',
'listfiles_thumb'       => 'بټنوک',
'listfiles_date'        => 'نېټه',
'listfiles_name'        => 'نوم',
'listfiles_user'        => 'کارن',
'listfiles_size'        => 'کچه (بايټونه)',
'listfiles_description' => 'څرګندونه',
'listfiles_count'       => 'بڼې',

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
'filehist-thumbtext'        => 'د $1 پورې د بټنوک بڼه',
'filehist-nothumb'          => 'بې بټنوکه',
'filehist-user'             => 'کارن',
'filehist-dimensions'       => 'ډډې',
'filehist-filesize'         => 'د دوتنې کچه',
'filehist-comment'          => 'تبصره',
'filehist-missing'          => 'دوتنه ورکه ده',
'imagelinks'                => 'د دوتنې تړنې',
'linkstoimage'              => 'دا {{PLURAL:$1|لاندينی مخ|$1 لانديني مخونه}} د همدې دوتنې سره تړنې لري:',
'nolinkstoimage'            => 'داسې هېڅ کوم مخ نه شته چې د دغې دوتنې سره تړنې ولري.',
'duplicatesoffile'          => 'دا لاندينۍ {{PLURAL:$1| دوتنه د همدې دوتنې غبرګونې لمېسه ده|$1 دوتنې د همدې دوتنې غبرګونې لمېسې دي}} ([[Special:FileDuplicateSearch/$2|نور تفصيل]]):',
'sharedupload'              => 'دا دوتنه د $1 لخوا نه ده او کېدای شي چې نورې پروژې به يې هم کاروي.',
'filepage-nofile'           => 'په دې نوم کومه دوتنه نشته.',
'uploadnewversion-linktext' => 'د همدغې دوتنې نوې بڼه پورته کول',
'shared-repo-from'          => 'د $1 لخوا',

# File reversion
'filerevert-comment' => 'سبب:',
'filerevert-submit'  => 'په څټ ګرځول',

# File deletion
'filedelete'                  => '$1 ړنګول',
'filedelete-legend'           => 'دوتنه ړنګول',
'filedelete-comment'          => 'سبب:',
'filedelete-submit'           => 'ړنګول',
'filedelete-success'          => "'''$1''' ړنګ شو.",
'filedelete-nofile'           => "'''$1''' نشته.",
'filedelete-otherreason'      => 'بل/اضافه سبب:',
'filedelete-reason-otherlist' => 'بل سبب',
'filedelete-reason-dropdown'  => '*د ړنګولو ټولګړی سبب
** د رښتو نه غاړه غړونه
** کټ مټ دوه ګونې دوتنه',
'filedelete-edit-reasonlist'  => 'د ړنګولو سببونه سمول',

# MIME search
'mimesearch' => 'MIME پلټنه',
'mimetype'   => 'MIME بڼه:',
'download'   => 'ښکته کول',

# Unwatched pages
'unwatchedpages' => 'ناکتلي مخونه',

# List redirects
'listredirects' => 'د ورګرځېدنو لړليک',

# Unused templates
'unusedtemplates'    => 'ناکارېدلې کينډۍ',
'unusedtemplateswlh' => 'نور تړنونه',

# Random page
'randompage'         => 'ناټاکلی مخ',
'randompage-nopages' => 'په لانديني {{PLURAL:$2|نوم-تشيال|نوم-تشيالونو}} کې هېڅ کوم مخ نشته: $1.',

# Random redirect
'randomredirect' => 'ناټاکلی ورګرځېدنه',

# Statistics
'statistics'               => 'شمار',
'statistics-header-pages'  => 'د مخونو شمار',
'statistics-header-edits'  => 'د سمونو شمار',
'statistics-header-views'  => 'د کتنو شمار',
'statistics-header-users'  => 'د کارنانو شمار',
'statistics-header-hooks'  => 'بل شمار',
'statistics-articles'      => 'مېنځپانګيز مخونه',
'statistics-pages'         => 'مخونه',
'statistics-files'         => 'پورته شوې دوتنې',
'statistics-edits'         => 'د {{SITENAME}} د جوړېدو راهيسې د مخونو سمون',
'statistics-edits-average' => 'پر يوه مخ د سمون منځوۍ کچه',
'statistics-views-total'   => 'ټولټال کتنې',
'statistics-users'         => 'ثبت شوي [[Special:ListUsers|کارنان]]',
'statistics-users-active'  => 'فعاله کارنان',
'statistics-mostpopular'   => 'تر ټولو ډېر کتل شوي مخونه',

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
'nrevisions'              => '$1 {{PLURAL:$1|بڼه|بڼې}}',
'nviews'                  => '$1 {{PLURAL:$1|کتنه|کتنې}}',
'nimagelinks'             => 'په $1 {{PLURAL:$1|کارېدلی مخ|کارېدلي مخونه}}',
'specialpage-empty'       => 'د دې راپور لپاره کومې پايلې نشته.',
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
'protectedpages-indef'    => 'يوازې بې پايه ژغورنې',
'protectedpages-cascade'  => 'يوازې ځوړاوبيزې ژغورنې',
'protectedtitles'         => 'ژغورلي سرليکونه',
'listusers'               => 'د کارن لړليک',
'listusers-editsonly'     => 'يوازې هغه کارنان چې سمونونه يې کړي ښکاره کول',
'listusers-creationsort'  => 'د جوړېدو د نېټې له مخې اوډل',
'usereditcount'           => '{{PLURAL:$1|سمون|سمونونه}}',
'usercreated'             => 'په $1 نېټه په $2 بجو جوړ شو',
'newpages'                => 'نوي مخونه',
'newpages-username'       => 'کارن-نوم:',
'ancientpages'            => 'تر ټولو زاړه مخونه',
'move'                    => 'لېږدول',
'movethispage'            => 'دا مخ ولېږدوه',
'notargettitle'           => 'بې موخې',
'pager-newer-n'           => '{{PLURAL:$1|نوی 1|نوي $1}}',
'pager-older-n'           => '{{PLURAL:$1|زوړ 1|زاړه $1}}',
'suppress'                => 'څارن',

# Book sources
'booksources'               => 'د کتاب سرچينې',
'booksources-search-legend' => 'د کتابي سرچينو لټون وکړۍ',
'booksources-go'            => 'ورځه',
'booksources-text'          => 'دا لاندې د هغه وېبځايونو د تړنو لړليک دی چېرته چې نوي او زاړه کتابونه پلورل کېږي، او يا هم کېدای شي چې د هغه کتاب په هکله مالومات ولري کوم چې تاسو ورپسې لټېږۍ:',

# Special:Log
'specialloguserlabel'  => 'کارن:',
'speciallogtitlelabel' => 'سرليک:',
'log'                  => 'يادښتونه',
'all-logs-page'        => 'ټول عام يادښتونه',
'log-title-wildcard'   => 'هغه سرليکونه پلټل چې په دې متن پيلېږي',

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
'categories'                    => 'وېشنيزې',
'categoriespagetext'            => 'دا لاندينۍ {{PLURAL:$1|وېشنيزه|وېشنيزې}} مخونه يا رسنيزې دوتنې لري.
دلته [[Special:UnusedCategories|ناکارېدلې وېشنيزې]] نه دي ښکاره شوي.
[[Special:WantedCategories|غوښتلې وېشنيزې]] هم وګورۍ.',
'categoriesfrom'                => 'هغه وېشنيزې دې ښکاره شي چې پېلېږي په:',
'special-categories-sort-count' => 'د شمېر له مخې اوډل',
'special-categories-sort-abc'   => 'د ابېڅو له مخې اوډل',

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
'listusersfrom'      => 'هغه کارنان ښکاره کړه چې نومونه يې پېلېږي په:',
'listusers-submit'   => 'ښکاره کول',
'listusers-noresult' => 'هېڅ کوم کارن و نه موندل شو.',
'listusers-blocked'  => '(بنديز لګېدلی)',

# Special:ActiveUsers
'activeusers'            => 'د فعالو کارنانو لړليک',
'activeusers-count'      => 'په {{PLURAL:$2|تېرې|تېرو}} {{PLURAL:$3|ورځ|$3 ورځو}} کې $1 {{PLURAL:$1|سمون|سمونونه}}',
'activeusers-from'       => 'هغه کارنان ښکاره کړه چې نومونه يې پېلېږي په:',
'activeusers-hidesysops' => 'پازوالان پټول',
'activeusers-noresult'   => 'کارن و نه موندل شو.',

# Special:Log/newusers
'newuserlogpage'              => 'د کارن-نوم د جوړېدو يادښت',
'newuserlogpagetext'          => 'دا د کارن-نوم د جوړېدو يادښت دی',
'newuserlog-byemail'          => 'پټنوم مو برېښليک ته درولېږه',
'newuserlog-create-entry'     => 'نوی کارن',
'newuserlog-create2-entry'    => 'نوی جوړ شوی ګڼون $1',
'newuserlog-autocreate-entry' => 'ګڼون په اتوماتيک ډول جوړ شو',

# Special:ListGroupRights
'listgrouprights'                      => 'د کارن ډلو رښتې',
'listgrouprights-group'                => 'ډله',
'listgrouprights-rights'               => 'رښتې',
'listgrouprights-helppage'             => 'Help:د ډلې رښتې',
'listgrouprights-members'              => '(د غړو لړليک)',
'listgrouprights-addgroup'             => '{{PLURAL:$2|ډله|ډلې}} ورګډول: $1',
'listgrouprights-removegroup'          => '{{PLURAL:$2|ډله|ډلې}} ليري کول: $1',
'listgrouprights-addgroup-all'         => 'ټولې ډلې ورګډول',
'listgrouprights-removegroup-all'      => 'ټولې ډلې ليري کول',
'listgrouprights-addgroup-self'        => 'خپل ګڼون کې د {{PLURAL:$2|ډله|ډلې}} ورګډول: $1',
'listgrouprights-removegroup-self'     => 'خپل ګڼون نه د {{PLURAL:$2|ډله|ډلې}} ليري کول: $1',
'listgrouprights-addgroup-self-all'    => 'خپل ګڼون کې ټولې ډلې ورګډول',
'listgrouprights-removegroup-self-all' => 'خپل ګڼون نه ټولې ډلې ليري کول',

# E-mail user
'mailnologin'          => 'هېڅ کومه لېږل شوې پته نشته',
'emailuser'            => 'کارن ته برېښليک لېږل',
'emailpage'            => 'کارن ته برېښليک لېږل',
'defemailsubject'      => 'د {{SITENAME}} برېښليک',
'usermaildisabled'     => 'د کارن برېښليک ناچارند دی',
'usermaildisabledtext' => 'په دې ويکي تاسې نورو کارنانو ته برېښليک نه شی ورلېږلی',
'noemailtitle'         => 'هېڅ کومه برېښليک پته نشته.',
'nowikiemailtitle'     => 'د برېښليک لېږلو اجازه نشته',
'nowikiemailtext'      => 'دې کارن د نورو کارنانو لخوا د برېښليک د نه ترلاسه کولو چاره خوښه کړې.',
'email-legend'         => 'د {{SITENAME}} يو بل کارن ته يو برېښليک ورلېږل',
'emailfrom'            => 'لېږونکی',
'emailto'              => 'اخيستونکی',
'emailsubject'         => 'سکالو:',
'emailmessage'         => 'پيغام:',
'emailsend'            => 'لېږل',
'emailccme'            => 'زما د پيغام يوه بېلګه دې ماته هم برېښليک شي.',
'emailccsubject'       => '$1 ته ستاسو د پيغام لمېسه: $2',
'emailsent'            => 'برېښليک مو ولېږل شو',
'emailsenttext'        => 'ستاسو برېښليکي پيغام ولېږل شو.',
'emailuserfooter'      => 'همدا برېښليک د $1 لخوا $2 ته د {{SITENAME}} په وېبځي کې د "همدې کارونکي ته برېښليک لېږل" د کړنې په مرسته لېږل شوی دی.',

# User Messenger
'usermessage-summary' => 'د غونډال پيغام پرېښودل.',
'usermessage-editor'  => 'د غونډال پيغام رسونکی',

# Watchlist
'watchlist'            => 'زما کتنلړ',
'mywatchlist'          => 'زما کتنلړ',
'watchlistfor2'        => 'د $1 لپاره $2',
'nowatchlist'          => 'ستاسو په کتلي لړليک کې هېڅ نه شته.',
'watchlistanontext'    => 'د خپل کتنلړ د توکو د سمولو او کتلو لپاره $1 ترسره کړۍ.',
'watchnologin'         => 'غونډال کې نه ياست ننوتي.',
'watchnologintext'     => 'ددې لپاره چې خپل کتل شوي لړليک کې بدلون راولی نو تاسو ته پکار ده چې لومړی غونډال کې [[Special:UserLogin|ننوتنه]] ترسره کړی.',
'addedwatch'           => 'په کتنلړ کې ورګډ شو.',
'addedwatchtext'       => "د \"[[:\$1]]\" په نوم يو مخ ستاسې [[Special:Watchlist|کتنلړ]] کې ورګډ شو.
په راتلونکې کې چې په دغه مخ او د ده د خبرواترو مخ کې کوم بدلونونه راځي نو هغه به ستاسې کتنلړ کې ښکاره شي،
او په همدې توګه هغه مخونه به د [[Special:RecentChanges|وروستي بدلونونو]] په لړليک کې په '''روڼ''' ليک ښکاري ترڅو په اسانۍ سره څوک وپوهېږي چې په کوم کوم مخونو کې بدلونونه ترسره شوي.

که چېرته تاسې بيا وروسته غواړۍ چې کوم مخ د خپل کتنلړ نه ليرې کړۍ، نو په \"نه کتل\" تڼۍ باندې ټک ورکړۍ.",
'removedwatch'         => 'د کتنلړ نه لرې شو',
'removedwatchtext'     => 'د "[[:$1]]" مخ [[Special:Watchlist|ستاسې کتنلړ]] نه لرې شو.',
'watch'                => 'کتل',
'watchthispage'        => 'همدا مخ کتل',
'unwatch'              => 'نه کتل',
'unwatchthispage'      => 'څارنې په ټپه درول',
'notanarticle'         => 'يو منځپانګيز مخ نه دی',
'watchlist-details'    => 'ستاسې کتنلړ کې {{PLURAL:$1|$1 مخ دی|$1 مخونه دي}}، د خبرو اترو مخونه مو پکې نه دي شمېرلي.',
'wlheader-enotif'      => 'د برېښليک له لارې خبرول چارن شوی.*',
'wlheader-showupdated' => "* هغه مخونه چې وروستی ځل ستاسو د کتلو نه وروسته بدلون موندلی په '''روڼ''' ليک نښه شوي.",
'watchlistcontains'    => 'ستاسې کتنلړ $1 {{PLURAL:$1|مخ|مخونه}} لري.',
'wlshowlast'           => 'وروستي $1 ساعتونه $2 ورځې $3 ښکاره کړه',
'watchlist-options'    => 'د کتنلړ خوښنې',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'د کتلو په حال کې...',
'unwatching' => 'د نه کتلو په حال کې...',

'enotif_mailer'                => 'د {{SITENAME}} خبرتيايي برېښليک',
'enotif_reset'                 => 'ټول مخونه کتل شوي نخښه کول',
'enotif_newpagetext'           => 'دا يوه نوې پاڼه ده.',
'enotif_impersonal_salutation' => '{{SITENAME}} کارن',
'changed'                      => 'بدل شو',
'created'                      => 'جوړ شو',
'enotif_subject'               => 'د {{SITENAME}} مخ $PAGETITLE د  $PAGEEDITOR لخوا $CHANGEDORCREATED',
'enotif_lastvisited'           => 'د ټولو هغو بدلونونو د کتلو لپاره چې ستاسو د وروستي ځل راتګ نه وروسته پېښې شوي، $1 وګورۍ.',
'enotif_lastdiff'              => 'د همدغه بدلون د کتلو لپاره $1 وګورۍ.',
'enotif_anon_editor'           => 'ورکنومی کارن $1',
'enotif_body'                  => 'قدرمن/قدرمنې $WATCHINGUSERNAME,


په $PAGEEDITDATE نېټه، د  $PAGEEDITOR لخوا د {{SITENAME}} مخ $PAGETITLE ته $CHANGEDORCREATED، د اوسنۍ بڼې لپاره $PAGETITLE_URL وګورۍ.

$NEWPAGE

د سمونګر لنډيز: $PAGESUMMARY $PAGEMINOREDIT

Contact the editor:
برېښليک: $PAGEEDITOR_EMAIL
ويکي: $PAGEEDITOR_WIKI

د لا نورو بدلونونو په پېښېدو سره به تاسې ته د خبراوي بل برېښليک نه درلېږل کېږي، تر څو چې تاسې د همدې مخ نه کتنه و نه کړۍ.
تاسې دا هم کولای شی چې په خپل کتنلړ کې د ټولو کتل شويو مخونو د خبراوي بيرغونه بيا له سره پرځای کړۍ.

             ستاسې ملګری

د {{SITENAME}} د خبرولو غونډال

--
د خپل کتنلړ د امستنو د بدلون لپاره،
{{fullurl:{{#special:Watchlist}}/edit}} نه ليدنه وکړۍ

د خپل کتنلړ د مخونو د ړنګولو لپاره،
$UNWATCHURL  نه ليدنه وکړۍ

انګېرنې او نورې مرستې:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'پاڼه ړنګول',
'confirm'                => 'تاييد',
'excontent'              => 'د مخ مېنځپانګه دا وه: "$1"',
'excontentauthor'        => 'د مخ مېنځپانګه دا وه: "$1" (او يواځينی ونډه وال "[[Special:Contributions/$2|$2]]" وه)',
'exblank'                => 'دا مخ تش وه',
'delete-confirm'         => '"$1" ړنګوول',
'delete-legend'          => 'ړنګول',
'historywarning'         => "ګواښنه:''' تاسې چې د کوم مخ د ړنګېدو تکل لری، هغه د نژدې $1 {{PLURAL:$1|بڼې|بڼو}} يو پېښليک لري:",
'confirmdeletetext'      => 'تاسې د تل لپار يو مخ يا انځور د هغه ټول پېښليک سره د دغه توکبنسټ نه ړنګوۍ. که چېرته تاسې ددې کړنې په پايله پوه ياست او يا ستاسو همدا کړنه د دې پاڼې د [[{{MediaWiki:Policy-url}}|تګلارې]] سره سمون خوري نو لطفاً د دې تاييد وکړی.',
'actioncomplete'         => 'بشپړه کړنه',
'deletedtext'            => '"<nowiki>$1</nowiki>" ړنګ شوی.
د نوو ړنګ شوو سوانحو لپاره $2 وګورۍ.',
'deletedarticle'         => '"[[$1]]" ړنګ شو',
'dellogpage'             => 'د ړنګولو يادښت',
'dellogpagetext'         => 'دا لاندې د نوو ړنګ شوو کړنو لړليک دی.',
'deletionlog'            => 'د ړنګولو يادښت',
'deletecomment'          => 'سبب:',
'deleteotherreason'      => 'بل/اضافه سبب:',
'deletereasonotherlist'  => 'بل سبب',
'deletereason-dropdown'  => '*د ړنګولو ټولګړی سبب
** د ليکوال غوښتنه
** د رښتو تېری
** د پوهې سره دښمني',
'delete-edit-reasonlist' => 'د ړنګولو سببونه سمول',

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
'protect-text'                => "تاسې کولای شی چې د '''<nowiki>$1</nowiki>''' مخ لپاره د ژغورلو کچه همدلته وګورۍ او بدلون پکې راولی.",
'protect-locked-access'       => "ستاسې ګڼون دا اجازه نه لري چې د پاڼو د ژغورنې په کچه کې بدلون راولي.
دلته د '''$1''' مخ لپاره اوسني شته امستنې دي:",
'protect-cascadeon'           => 'د اوسمهال لپاره همدا مخ ژغورل شوی دا ځکه چې همدا مخ په {{PLURAL:$1|لانديني مخ|لانديني مخونو}} کې ورګډ دی چې {{PLURAL:$1|ځوړاوبيزه ژغورنه يې چارنه ده|ځوړاوبيزې ژغورنې يې چارنې دي}}.
تاسې د همدې مخ د ژغورنې په کچه کې بدلون راوستلای شی، خو دا به په ځوړاوبيزه ژغورنه اغېزمنه نه کړي.',
'protect-default'             => 'ټول کارنان پرېښودل',
'protect-fallback'            => 'د "$1" اجازه پکار ده',
'protect-level-autoconfirmed' => 'پر نوؤ او ناثبته کارنانو بنديز لګول',
'protect-level-sysop'         => 'يواځې پازوالان',
'protect-summary-cascade'     => 'ځوړاوبيز',
'protect-expiring'            => 'په $1 (UTC) پای ته رسېږي',
'protect-expiry-indefinite'   => 'لامحدوده',
'protect-cascade'             => 'په همدې مخ کې د ټولو ګډو مخونو نه ژغورنه کېږي (ځوړاوبيزه ژغورنه)',
'protect-cantedit'            => 'تاسې نه شی کولای چې د دې مخ د ژغورنې په کچه کې بدلون راولی، دا ځکه چې تاسې د دې مخ د سمولو اجازه نه لری.',
'protect-othertime'           => 'بل وخت:',
'protect-othertime-op'        => 'بل وخت',
'protect-otherreason'         => 'بل/اضافي سبب:',
'protect-otherreason-op'      => 'بل سبب',
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
'restriction-upload' => 'پورته کول',

# Restriction levels
'restriction-level-sysop'         => 'بشپړ ژغورلی',
'restriction-level-autoconfirmed' => 'نيم ژغورلی',

# Undelete
'undelete'                  => 'ړنګ شوي مخونه کتل',
'undeletepage'              => 'ړنګ شوي مخونه کتل او بېرته پرځای کول',
'viewdeletedpage'           => 'ړنګ شوي مخونه کتل',
'undeletebtn'               => 'بېرته پرځای کول',
'undeletelink'              => 'کتل/بيازېرمل',
'undeleteviewlink'          => 'کتل',
'undeletereset'             => 'بياايښودل',
'undeletecomment'           => 'سبب:',
'undeletedarticle'          => '"[[$1]]" بېرته پرځای شو',
'undeletedfiles'            => '{{PLURAL:$1|1 دوتنه بيازېرمه شوه|$1 دوتنې بيازېرمه شوې}}',
'undelete-search-box'       => 'ړنګ شوي مخونه لټول',
'undelete-search-prefix'    => 'هغه مخونه ښکاره کړه چې پېلېږي په:',
'undelete-search-submit'    => 'پلټل',
'undelete-show-file-submit' => 'هو',

# Namespace form on various pages
'namespace'      => 'نوم-تشيال:',
'invert'         => 'ټاکنې سرچپه کول',
'blanknamespace' => '(آرنی)',

# Contributions
'contributions'       => 'د کارن ونډې',
'contributions-title' => 'د $1 کارن ونډې',
'mycontris'           => 'زما ونډې',
'contribsub2'         => 'د $1 لپاره ($2)',
'uctop'               => '(سرپاڼه)',
'month'               => 'له ټاکلې مياشتې نه راپدېخوا (او تر دې پخواني):',
'year'                => 'له ټاکلي کال نه راپدېخوا (او تر دې پخواني):',

'sp-contributions-newbies'     => 'د نوو ګڼونونو ونډې ښکاره کول',
'sp-contributions-newbies-sub' => 'د نوو ګڼونونو لپاره',
'sp-contributions-blocklog'    => 'د بنديز يادښت',
'sp-contributions-deleted'     => 'د کارن ونډې ړنګې شوې',
'sp-contributions-logs'        => 'يادښتونه',
'sp-contributions-talk'        => 'خبرې اترې',
'sp-contributions-search'      => 'د ونډو لټون',
'sp-contributions-username'    => 'IP پته يا کارن-نوم:',
'sp-contributions-submit'      => 'پلټل',

# What links here
'whatlinkshere'            => 'د دې مخ تړنې',
'whatlinkshere-title'      => 'هغه مخونه چې د "$1" سره تړنې لري',
'whatlinkshere-page'       => 'مخ:',
'linkshere'                => "دغه لانديني مخونه د '''[[:$1]]''' سره تړنې لري:",
'nolinkshere'              => "د '''[[:$1]]''' سره هېڅ يو مخ هم تړنې نه لري .",
'isredirect'               => 'د مخ ګرځونې مخ',
'istemplate'               => 'ورګډېدنه',
'isimage'                  => 'د انځور تړنه',
'whatlinkshere-prev'       => '{{PLURAL:$1|پخوانی|پخواني $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|راتلونکی|راتلونکي $1}}',
'whatlinkshere-links'      => '← تړنې',
'whatlinkshere-hideredirs' => 'مخ ګرځونې $1',
'whatlinkshere-hidetrans'  => 'پايلې $1',
'whatlinkshere-hidelinks'  => 'تړنې $1',
'whatlinkshere-hideimages' => '$1 د انځور تړنې',
'whatlinkshere-filters'    => 'چاڼګرونه',

# Block/unblock
'blockip'                    => 'په کارن بنديز لګول',
'blockip-title'              => 'په کارن بنديز لګول',
'blockip-legend'             => 'په کارن بنديز لګول',
'ipaddress'                  => 'IP پته',
'ipadressorusername'         => 'IP پته يا کارن نوم',
'ipbexpiry'                  => 'د پای نېټه:',
'ipbreason'                  => 'سبب:',
'ipbreasonotherlist'         => 'بل لامل',
'ipbreason-dropdown'         => '*د بنديز ټولګړي سببونه
** د ناسمو مالوماتو خپرول
** د مخونو د مېنځپانګې ړنګول
** په مخونو کې د باندنيو وېبځايونو بېکاره سپام تړنې ځايول
** په مخونو کې بې مانا/چټياټ ځايول
** په مخونو کې ناندرۍ راپارېدنې/د تاوتريخوالي خپرېدو ته هڅول
** د ګڼ شمېر ګڼونونو نه ناوړه ګټه اخيستل
** نه مننونکی کارن-نوم کارول',
'ipbcreateaccount'           => 'د ګڼون جوړولو مخنيوی',
'ipbsubmit'                  => 'په دې کارن بنديز لګول',
'ipbother'                   => 'بل وخت:',
'ipboptions'                 => '2 ساعتونه:2 hours,1 ورځ:1 day,3 ورځې:3 days,1 اوونۍ:1 week,2 اوونۍ:2 weeks,1 مياشت:1 month,3 مياشتې:3 months,6 مياشتې:6 months,1 کال:1 year,لامحدوده:infinite',
'ipbotheroption'             => 'نور',
'ipbotherreason'             => 'بل/اضافه سبب:',
'ipbhidename'                => 'کارن-نوم له سمون او لړليکونو پټول',
'badipaddress'               => 'ناسمه IP پته',
'blockipsuccesssub'          => 'بنديز په برياليتوب سره ولګېده',
'blockipsuccesstext'         => 'د [[Special:Contributions/$1|$1]] مخه نيول شوې.
<br />د مخنيول شويو خلکو د کتنې لپاره، د [[Special:IPBlockList|مخنيول شويو IP لړليک]] وګورۍ.',
'ipb-edit-dropdown'          => 'د بنديز د سببونو سمول',
'ipb-unblock-addr'           => 'له $1 بنديز ليرې کول',
'ipb-unblock'                => 'له يوه کارن-نوم يا IP پتې بنديز ليري کول',
'ipb-blocklist'              => 'شته بنديزونه کتل',
'ipb-blocklist-contribs'     => 'د $1 ونډې',
'unblockip'                  => 'کارن له بنديزه وېستل',
'ipusubmit'                  => 'دا بنديز ليرې کول',
'unblocked'                  => 'له [[User:$1|$1]] بنديز ليري شو',
'ipblocklist'                => 'د بنديز لګېدلي آی پي پتو او کارن نومونو لړليک',
'ipblocklist-legend'         => 'يو بنديز شوی کارن موندل',
'ipblocklist-username'       => 'کارن-نوم يا IP پته:',
'ipblocklist-sh-userblocks'  => 'د ګڼون بنديزونه $1',
'ipblocklist-sh-tempblocks'  => 'لنډمهاله بنديزونه $1',
'ipblocklist-submit'         => 'پلټل',
'ipblocklist-localblock'     => 'سيمه ايز بنديز',
'ipblocklist-otherblocks'    => '{{PLURAL:$1|بل بنديز|نور بنديزونه}}',
'blocklistline'              => '$1, $2 په $3 بنديز ولګاوه ($4)',
'infiniteblock'              => 'لامحدوده',
'expiringblock'              => 'په $1 نېټه، $2 بجو پای ته رسېږي',
'anononlyblock'              => 'يواځې ورکنومی',
'createaccountblock'         => 'په ګڼون جوړولو بنديز لګېدلی',
'emailblock'                 => 'پر برېښليک بنديز ولګېد',
'blocklist-nousertalk'       => 'د خبرواترو خپل مخ نه شی سمولای',
'ipblocklist-empty'          => 'د بنديز لړليک تش دی',
'blocklink'                  => 'بنديز لګول',
'unblocklink'                => 'بنديز لرې کول',
'change-blocklink'           => 'د بنديز بدلون',
'contribslink'               => 'ونډې',
'autoblocker'                => 'په اتوماتيک ډول ستاسو مخنيوی شوی دا ځکه چې ستاسو IP پته وروستی ځل د "[[User:$1|$1]]" له خوا کارېدلې. او د $1 د مخنيوي سبب دا دی: "$2"',
'blocklogpage'               => 'د مخنيوي يادښت',
'blocklogentry'              => 'په [[$1]] بنديز لګېدلی چې د بنديز د پای وخت يې $2 $3 دی',
'unblocklogentry'            => 'بنديز ليرې شو $1',
'block-log-flags-anononly'   => 'يواځې ورکنومي کارنان',
'block-log-flags-nocreate'   => 'د ګڼون جوړول ناچارن شوی',
'block-log-flags-noemail'    => 'ددې برېښليک مخه نيول شوی',
'block-log-flags-hiddenname' => 'پټ کارن-نوم',
'ipb_already_blocked'        => 'پر "$1" د پخوا نه بنديز دی',
'ipb-needreblock'            => '== د پخوا نه بنديز لګېدلی ==
پر $1 د پخوا نه بنديز لګېدلی.
آيا تاسې د امستنو بدلول غواړۍ؟',
'ipb-otherblocks-header'     => '{{PLURAL:$1|بل بنديز|نور بنديزونه}}',
'blockme'                    => 'پر ما بنديز لګول',
'proxyblocksuccess'          => 'ترسره شو.',

# Developer tools
'lockdb'      => 'توکبنسټ تړل',
'lockconfirm' => 'هو، زه د توکبنسټ تړل غواړم.',
'lockbtn'     => 'توکبنسټ تړل',
'unlockbtn'   => 'توکبنسټ پرانيستل',

# Move page
'move-page'               => '$1 لېږدول',
'move-page-legend'        => 'مخ لېږدول',
'movepagetext'            => "د لاندينۍ فورمې په کارولو سره تاسې د يوه مخ نوم بدلولی شی، چې په همدې توګه به د يوه مخ ټول پېښليک د هغه د نوي نوم سرليک ته ولېږدېږي.
د يوه مخ، پخوانی نوم به د نوي نوم ورګرځونکی مخ وګرځي او نوي سرليک ته به وګرځولی شي.
هغه تړنې چې په زاړه مخ کې دي په هغو کې به هېڅ کوم بدلون را نه شي;
[[Special:BrokenRedirects|د ماتو مخ ګرځونو]] يا [[Special:DoubleRedirects|دوه ځلي مخ ګرځونو]] د ستونزو د پېښېدو په خاطر ځان ډاډه کړی چې ستاسې مخ ګرځونې ماتې يا دوه ځله نه وي.
دا ستاسې پازه ده چې ځان په دې هم ډاډمن کړی چې آيا هغه تړنې کوم چې د يو مخ سره پکار دي چې وي، همداسې په پرله پسې توګه پېيلي او خپل موخن ځايونو سره اړونده دي.

په ياد مو اوسه چې يو مخ به '''هېڅکله''' و نه لېږدېږي که چېرته د پخوا نه په هماغه نوم يو مخ شتون ولري، خو که چېرته يو مخ تش وه او يا هم يوه مخ ګرځونه چې پېښليک کې يې بدلون نه وي راغلی. نو دا په دې مانا ده چې تاسې کولای شی چې د يو مخ نوم بېرته هماغه پخواني نوم ته بدل کړی چې د پخوا نه يې درلوده، که چېرته تاسې تېرووځۍ نو په داسې حال کې تاسې نه شی کولای چې د يوه مخ پر سر يو څه وليکۍ.

'''ګواښنه!'''
يوه نوي نوم ته د مخونو د نوم بدلون کېدای شي چې په نامتو مخونو کې بنسټيزه او نه اټکل کېدونکی بدلونونه رامېنځ ته کړي;
مخکې له دې نه چې پرمخ ولاړ شی، لطفاُ لومړی خپل ځان په دې ډاډه کړی چې تاسې ددغې کړنې په پايلو ښه پوهېږۍ.",
'movepagetalktext'        => "همدې مخ ته اړونده د خبرواترو مخ هم په اتوماتيک ډول لېږدول کېږي '''خو که چېرته:'''
*په نوي نوم د پخوا نه د خبرواترو يو مخ شتون ولري، او يا هم
*تاسې ته لاندې ورکړ شوی څلورڅنډی په نښه شوی وي.

نو په هغه وخت کې پکار ده چې د خبرواترو د مخ لېږدونه او د نوي مخ سره د يوځای کولو کړنه په لاسي توګه ترسره کړی.",
'movearticle'             => 'مخ لېږدول',
'movenologin'             => 'غونډال کې نه ياست ننوتي',
'movenologintext'         => 'ددې لپاره چې يو مخ ولېږدوی، نو تاسې بايد يو ثبت شوی کارن او غونډال کې [[Special:UserLogin|ننوتي]] اوسۍ.',
'newtitle'                => 'يو نوي سرليک ته:',
'move-watch'              => 'همدا مخ کتل',
'movepagebtn'             => 'مخ لېږدول',
'pagemovedsub'            => 'لېږدول په برياليتوب سره ترسره شوه',
'movepage-moved'          => '\'\'\'د "$1" په نامه دوتنه، "$2" ته ولېږدېده\'\'\'',
'articleexists'           => 'په همدې نوم يوه بله پاڼه د پخوا نه شته او يا خو دا نوم چې تاسې ټاکلی سم نه دی. لطفاً يو بل نوم وټاکۍ.',
'talkexists'              => "'''همدا مخ په برياليتوب سره نوي سرليک ته ولېږدېده، خو د خبرواترو مخ يې و نه لېږدول شو دا ځکه چې نوی سرليک له پخوا نه ځانته د خبرواترو يو مخ لري.
لطفاُ د خبرواترو دا دواړه مخونه په لاسي توګه سره يو ځای کړی.'''",
'movedto'                 => 'ته ولېږدول شو',
'movetalk'                => 'د خبرو اترو اړونده مخ ورسره لېږدول',
'1movedto2'               => '[[$1]]، [[$2]] ته ولېږدېده',
'1movedto2_redir'         => '[[$1]] د [[$2]] مخ ته د مخ ګرځونې په توګه ولېږدېده',
'movelogpage'             => 'د لېږدولو يادښت',
'movelogpagetext'         => 'دا لاندې د لېږدول شوو مخونو لړليک دی.',
'movesubpage'             => '{{PLURAL:$1|څېرمه مخ|څېرمه مخونه}}',
'movenosubpage'           => 'دا مخ کوم څېرمه مخونه نه لري.',
'movereason'              => 'سبب:',
'revertmove'              => 'په څټ ګرځول',
'delete_and_move'         => 'ړنګول او لېږدول',
'delete_and_move_confirm' => 'هو, دا مخ ړنګ کړه',
'immobile-source-page'    => 'دا مخ نه لېږدېدنونکی دی',
'imageinvalidfilename'    => 'د موخنې دوتنې نوم سم نه دی',
'move-leave-redirect'     => 'يو ورګرځونکی مخ پر ځای پرېښودل',
'move-over-sharedrepo'    => '== دوتنه شته ==
د [[:$1]] دوتنه په يوه ګډ زېرمتون کې شته. دې نوم ته د يوې دوتنې لېږدون به د ګډې دوتنې د باطلېدلو سبب شي.',

# Export
'export'            => 'مخونه صادرول',
'export-addcattext' => 'مخونو د ورګډولو وېشنيزه:',
'export-addcat'     => 'ورګډول',
'export-addnstext'  => 'د نوم-تشيال نه مخونه ورګډول:',
'export-addns'      => 'ورګډول',
'export-download'   => 'د دوتنې په بڼه خوندي کول',

# Namespace 8 related
'allmessages'                   => 'د غونډال پيغامونه',
'allmessagesname'               => 'نوم',
'allmessagesdefault'            => 'ټاکل شوی متن',
'allmessagescurrent'            => 'اوسنی متن',
'allmessagestext'               => 'دا د مېډياويکي په نوم-تشيال کې د غونډال د پيغامونو لړليک دی.
که چېرته تاسو د ميډياويکي په ځايتابه کې ونډې ترسره کول غواړۍ نو لطفاً [http://www.mediawiki.org/wiki/Localisation د ويډياويکي ځايتابه] او [http://translatewiki.net translatewiki.net] نه ليدنه وکړۍ.',
'allmessagesnotsupportedDB'     => "'''Special:Allmessages''' ترېنه کار نه اخيستل کېږي ځکه چې '''\$wgUseDatabaseMessages''' مړ دی.",
'allmessages-filter-legend'     => 'چاڼګر',
'allmessages-filter-unmodified' => 'نابدلېدلي',
'allmessages-filter-all'        => 'ټول',
'allmessages-filter-modified'   => 'بدلېدلي',
'allmessages-prefix'            => 'د مختاړي پر بنسټ اړونده چاڼګر:',
'allmessages-language'          => 'ژبه:',
'allmessages-filter-submit'     => 'ورځه',

# Thumbnails
'thumbnail-more'  => 'لويول',
'filemissing'     => 'دوتنه ورکه ده',
'thumbnail_error' => 'د  بټنوک د جوړېدنې ستونزه: $1',

# Special:Import
'import-interwiki-source'    => 'سرچينيز ويکي/مخ:',
'import-interwiki-templates' => 'ټولې کينډۍ پکې شمېرل',
'import-interwiki-namespace' => 'د موخې نوم-تشيال:',
'import-upload-filename'     => 'د دوتنې نوم:',
'import-comment'             => 'تبصره:',

# Import log
'importlogpage' => 'د واردولو يادښت',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'ستاسې کارن مخ',
'tooltip-pt-mytalk'               => 'ستاسې د خبرواترو مخ',
'tooltip-pt-preferences'          => 'زما غوره توبونه',
'tooltip-pt-watchlist'            => 'د هغه مخونو لړليک چې تاسې يې د بدلون لپاره څاری',
'tooltip-pt-mycontris'            => 'ستاسې د ونډو لړليک',
'tooltip-pt-login'                => 'تاسې ته په غونډال کې د ننوتلو سپارښتنه کوو، که څه هم چې دا يو اړين کار نه دی.',
'tooltip-pt-anonlogin'            => 'تاسو ته په غونډال کې د ننوتنې سپارښتنه کوو، که څه هم چې دا يو اړين کار نه دی.',
'tooltip-pt-logout'               => 'وتل',
'tooltip-ca-talk'                 => 'د مخ د مېنځپانګې په اړه خبرې اترې',
'tooltip-ca-edit'                 => 'تاسې همدا مخ سمولای شی. لطفاً د ليکنې د خوندي کولو دمخه، د همدې ليکنې مخليدنه وګورۍ.',
'tooltip-ca-addsection'           => 'يوه نوې برخه پيلول',
'tooltip-ca-viewsource'           => 'دا مخ ژغورل شوی. تاسې کولای شی چې د دې مخ سرجينه وګورۍ.',
'tooltip-ca-history'              => 'د دې مخ پخوانۍ بڼې',
'tooltip-ca-protect'              => 'دا مخ ژغورل',
'tooltip-ca-unprotect'            => 'همدا مخ ناژغورل',
'tooltip-ca-delete'               => 'دا مخ ړنګول',
'tooltip-ca-move'                 => 'همدا مخ لېږدول',
'tooltip-ca-watch'                => 'دا مخ په خپل کتنلړکې ګډول',
'tooltip-ca-unwatch'              => 'همدا مخ خپل کتنلړ نه لرې کول',
'tooltip-search'                  => 'د {{SITENAME}} لټون',
'tooltip-search-go'               => 'په دې نوم د کټ مټ ورته مخ شتون په صورت کې، هماغه مخ ته ورځه',
'tooltip-search-fulltext'         => 'په مخونو کې دا متن وپلټه',
'tooltip-p-logo'                  => 'لومړی مخ',
'tooltip-n-mainpage'              => 'لومړي مخ ته ورتلل',
'tooltip-n-mainpage-description'  => 'آرنی مخ کتل',
'tooltip-n-portal'                => 'د پروژې په اړه، تاسې څه شيان او چېرته کولای شی چې وې مومۍ',
'tooltip-n-currentevents'         => 'د اوسنيو پېښو اړونده د هغوی د شاليد مالومات موندل',
'tooltip-n-recentchanges'         => 'په ويکي کې د وروستي بدلونو لړليک.',
'tooltip-n-randompage'            => 'يو ناټاکلی مخ ښکاره کوي',
'tooltip-n-help'                  => 'د موندلو ځای',
'tooltip-t-whatlinkshere'         => 'د ويکي د ټولو هغو مخونو لړليک چې همدې مخ سره تړنې لري',
'tooltip-t-recentchangeslinked'   => 'له دې مخ سره د تړل شويو مخونو وروستي بدلونونه',
'tooltip-feed-rss'                => 'د همدې مخ د آر اس اس کتنه',
'tooltip-feed-atom'               => 'د دې مخ د اټوم کتنې',
'tooltip-t-contributions'         => 'د دې کارن د ونډو لړليک کتل',
'tooltip-t-emailuser'             => 'دې کارن ته يو برېښليک لېږل',
'tooltip-t-upload'                => 'دوتنې پورته کول',
'tooltip-t-specialpages'          => 'د ټولو ځانګړو پاڼو لړليک',
'tooltip-t-print'                 => 'د دې مخ چاپي بڼه',
'tooltip-t-permalink'             => 'د دې مخ د همدې بڼې تلپاتې تړنه',
'tooltip-ca-nstab-main'           => 'د مخ مېنځپانګه کتل',
'tooltip-ca-nstab-user'           => 'د کارن پاڼه کتل',
'tooltip-ca-nstab-media'          => 'د رسنۍ مخ کتل',
'tooltip-ca-nstab-special'        => 'دا يو ځانګړی مخ دی، تاسې په دې مخ کې سمون نه شی کولای.',
'tooltip-ca-nstab-project'        => 'د پروژې مخ کتل',
'tooltip-ca-nstab-image'          => 'د دوتنې مخ کتل',
'tooltip-ca-nstab-mediawiki'      => 'د غونډال پيغامونه ښکاره کول',
'tooltip-ca-nstab-template'       => 'کينډۍ کتل',
'tooltip-ca-nstab-help'           => 'د لارښود مخ کتل',
'tooltip-ca-nstab-category'       => 'د وېشنيزې مخ ښکاره کول',
'tooltip-minoredit'               => 'دا لکه يوه وړه سمونه په نښه کوي[alt-i]',
'tooltip-save'                    => 'ستاسې بدلونونه خوندي کوي',
'tooltip-preview'                 => 'ستاسې بدلونونه ښکاره کوي, لطفاً دا کړنه د خوندي کولو دمخه وکاروۍ! [alt-p]',
'tooltip-diff'                    => 'دا هغه بدلونونه چې تاسې په متن کې ترسره کړي، ښکاره کوي. [alt-v]',
'tooltip-compareselectedversions' => 'د همدې مخ د دوو ټاکل شويو بڼو تر مېنځ توپيرونه وګورۍ.',
'tooltip-watch'                   => 'دا مخ ستاسې کتنلړ کې ورګډوي [alt-w]',
'tooltip-rollback'                => 'په همدې مخ کې "په شابېول" د وروستني ونډوال سمون (سمونونه) په يوه کلېک په څټ ورګرځوي.',
'tooltip-undo'                    => '"ناکړ" همدا سمون پر شا ګرځوي او د سمون کړکۍ د مخکتنې په بڼه پرانيزي.
دا کړنه د لنډيز په برخه کې د سمونونو د سببونو د ورګډولو آسانتيا برابروي.',
'tooltip-preferences-save'        => 'غوره توبونه خوندي کول',
'tooltip-summary'                 => 'يو لنډ لنډيز کښل',

# Attribution
'anonymous'        => 'د {{SITENAME}} {{PLURAL:$1|ورکنومی کارن|ورکنومي کارنان}}',
'siteuser'         => 'د {{SITENAME}} کارن $1',
'anonuser'         => 'د {{SITENAME}} ورکنومی کارن $1',
'lastmodifiedatby' => 'دا مخ وروستی ځل د $3 لخوا په $2، $1 بدلون موندلی.',
'others'           => 'نور',
'siteusers'        => 'د {{SITENAME}} {{PLURAL:$2|کارن|کارنان}} $1',
'anonusers'        => 'د {{SITENAME}} {{PLURAL:$2|ورکنومی کارن|ورکنومي کارنان}} $1',

# Info page
'infosubtitle' => 'د مخ مالومات',
'numedits'     => 'د سمونونو شمېر (مخ): $1',
'numtalkedits' => 'د سمونونو شمېر (د خبرو اترو مخ): $1',
'numwatchers'  => 'د کتونکو شمېر: $1',

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

# Patrolling
'markaspatrolledtext' => 'دا مخ څارل شوی په نخښه کول',

# Patrol log
'patrol-log-auto' => '(خپلسر)',
'patrol-log-diff' => 'بڼه $1',

# Image deletion
'filedeleteerror-short' => 'د دوتنې د ړنګولو ستونزه: $1',

# Browsing diffs
'previousdiff' => 'تېر توپير ←',
'nextdiff'     => 'بل توپير →',

# Media information
'thumbsize'            => 'د بټنوک کچه:',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|مخ|مخونه}}',
'file-info-size'       => '$1 × $2 پېکسل, د دوتنې کچه: $3, MIME بڼه: $4',
'file-nohires'         => '<small>تر دې کچې لوړې بېلن نښې نشته.</small>',
'svg-long-desc'        => 'SVG دوتنه، نومېنلي $1 × $2 پېکسل، د دوتنې کچه: $3',
'show-big-image'       => 'بشپړ بېلن نښې',
'show-big-image-thumb' => '<small>د دې مخليدنې کچه: $1 × $2 pixels</small>',

# Special:NewFiles
'newimages'             => 'د نوو دوتنو نندارتون',
'imagelisttext'         => "دلته لاندې د '''$1''' {{PLURAL:$1|دوتنه|دوتنې}} يو لړليک دی چې اوډل شوي $2.",
'newimages-summary'     => 'همدا ځانګړی مخ، وروستنۍ پورته شوې دوتنې ښکاره کوي.',
'newimages-legend'      => 'چاڼګر',
'newimages-label'       => 'د دوتنې نوم (يا د دې برخه):',
'showhidebots'          => '($1 روباټ)',
'noimages'              => 'د کتلو لپاره څه نشته.',
'ilsubmit'              => 'پلټل',
'bydate'                => 'د نېټې له مخې',
'sp-newimages-showfrom' => 'هغه نوې دوتنې چې په $1 په $2 بجو پيلېږي ښکاره کول',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'hours-abbrev' => 'ساعتونه',

# Bad image list
'bad_image_list' => 'بڼه يې په لاندې توګه ده:

يواځې د لړليک توکي (هغه کرښې چې پېلېږي پر *) په پام کې نيول شوي.
بايد چې په يوه کرښه کې لومړنۍ تړنه د يوې خرابې دوتنې سره وي.
په يوې کرښې باندې هر ډول وروستۍ تړنې به د استثنا په توګه وګڼلای شي، د ساري په توګه هغه مخونو کې چې يوه دوتنه پر کرښه پرته وي.',

# Metadata
'metadata'          => 'مېټاډاټا',
'metadata-help'     => 'همدا دوتنه نور اضافه مالومات هم لري، چې کېدای شي ستاسې د ګڼياليزې کامرې او يا هم د ځيرڅار په کارولو سره د ګڼيالېدنې په وخت کې ورسره مل شوي.
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
'exif-datetimedigitized'  => 'د ګڼياليز کېدنې وخت او نېټه',
'exif-fnumber'            => 'F شمېره',
'exif-flash'              => 'فلش',
'exif-filesource'         => 'د دوتنې سرچينه',
'exif-gpsaltituderef'     => 'د لوړوالي سرچينه',
'exif-gpsaltitude'        => 'لوړوالی',
'exif-gpsimgdirection'    => 'د انځور لوری',
'exif-gpsareainformation' => 'د جي پي اس د سيمې نوم',
'exif-gpsdatestamp'       => 'د جي پي اس نېټه',

'exif-unknowndate' => 'نامالومه نېټه',

'exif-orientation-1' => 'نورمال',

'exif-componentsconfiguration-0' => 'نشته دی',

'exif-exposureprogram-2' => 'نورماله پروګرام',

'exif-subjectdistance-value' => '$1 متره',

'exif-meteringmode-0'   => 'ناجوت',
'exif-meteringmode-1'   => 'منځالی',
'exif-meteringmode-5'   => 'مخبېلګه',
'exif-meteringmode-255' => 'نور',

'exif-lightsource-0'   => 'ناجوت',
'exif-lightsource-1'   => 'د ورځې رڼا',
'exif-lightsource-4'   => 'فلش',
'exif-lightsource-9'   => 'ښه هوا',
'exif-lightsource-11'  => 'سيوری',
'exif-lightsource-255' => 'د رڼا بله سرچينه',

# Flash modes
'exif-flash-fired-0' => 'فلش و نه ځلېده',

'exif-focalplaneresolutionunit-2' => 'انچه',

'exif-sensingmethod-1' => 'ناڅرګنده',

'exif-customrendered-0' => 'نورماله بهير',

'exif-scenecapturetype-0' => 'معيار',

'exif-gaincontrol-0' => 'هېڅ',

'exif-contrast-0' => 'نورمال',
'exif-contrast-1' => 'پوست',

'exif-saturation-0' => 'نورمال',

'exif-sharpness-0' => 'نورمال',
'exif-sharpness-1' => 'پوست',

'exif-subjectdistancerange-0' => 'ناجوت',
'exif-subjectdistancerange-1' => 'ماکرو',
'exif-subjectdistancerange-2' => 'نژدې ليدون',
'exif-subjectdistancerange-3' => 'لرې ليدون',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'کيلومتره په يوه ساعت کې',
'exif-gpsspeed-m' => 'مايل په ساعت کې',
'exif-gpsspeed-n' => 'غوټې',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'سم لوری',
'exif-gpsdirection-m' => 'مقناطيسي لوری',

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
'confirmemail_send'      => 'يو تاييدي کوډ لېږل',
'confirmemail_sent'      => 'تاييدي برېښليک ولېږل شو.',
'confirmemail_oncreate'  => 'ستاسو د برېښناليک پتې ته يو تاييدي کوډ درولېږل شو.
ددې لپاره چې تاسو غونډال ته ورننوځی تاسو ته د همدغه کوډ اړتيا نشته، خو تاسو ته د همدغه کوډ اړتيا په هغه وخت کې پکارېږي کله چې په ويکي کې خپلې برېښناليکي کړنې چارن کول غواړی.',
'confirmemail_needlogin' => 'ددې لپاره چې ستاسې د برېښليک پتې پخلی وشي، تاسې ته پکار ده چې $1.',
'confirmemail_loggedin'  => 'اوس ستاسې د برېښناليک پتې پخلی وشو.',
'confirmemail_error'     => 'ستاسې د برېښليک پتې د تاييد په خوندي کولو کې يوه ستونزه رامېنڅ ته شوه.',
'confirmemail_subject'   => 'د {{SITENAME}} د برېښليک پتې تاييد',
'confirmemail_body'      => 'يو چا او يا هم کيدای شي چې تاسې پخپله، د $1 IP پتې نه،
د "$2" په نامه يو ګڼون په همدې بريښليک پتې د {{SITENAME}} په وېبځي کې ثبت کړی.

دا چې موږ د دې پخلی وکړو چې آيا همدا ګڼون په رښتيا ستاسې دی او د دې لپاره چې د همدې برېښليک لپاره په {{SITENAME}} وېبځي کې کړنې فعاله کړو، نو پخپل کتنمل کې لاندينۍ تړنه پرانيزۍ:

$3

که چېرته تاسې همدا ګڼون *نه وي ثبت کړی*، نو د برېښليک پتې د پخلي د لغوه کولو لپاره همدا لاندې تړنه وڅارۍ:

$5

همدا تاييدي شفر به په $4 پای ته ورسېږي او تر همدې مودې وروسته به نور و نه چلېږي.',

# Scary transclusion
'scarytranscludetoolong' => '[URL مو ډېر اوږد دی]',

# Trackbacks
'trackbackremove' => '([$1 ړنګول])',

# Delete conflict
'recreate' => 'بياجوړول',

# action=purge
'confirm_purge_button' => 'ښه',
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
'table_pager_limit_label'  => 'په هر مخ د توکو شمېر:',
'table_pager_limit_submit' => 'ورځه',
'table_pager_empty'        => 'بې پايلو',

# Auto-summaries
'autosumm-blank'   => 'د مخ مېنځپانګه ليرې شوه',
'autosumm-replace' => "دا مخ د '$1' پرځای راوستل",
'autoredircomment' => '[[$1]] ته وګرځولی شو',
'autosumm-new'     => 'د "$1" تورو مخ جوړ شو',

# Live preview
'livepreview-loading' => 'د برسېرېدلو په حال کې...',
'livepreview-ready'   => 'برسېرېدنه ... چمتو ده!',

# Watchlist editor
'watchlistedit-noitems'       => 'ستاسې کتنلړ کې هېڅ کوم سرليک نشته.',
'watchlistedit-normal-title'  => 'کتنلړ سمول',
'watchlistedit-normal-legend' => 'د کتنلړ نه سرليکونه لرې کول',
'watchlistedit-normal-submit' => 'سرليکونه لرکول',
'watchlistedit-normal-done'   => '{{PLURAL:$1|1 سرليک ستاسې له کتنلړ نه ليري شو|$1 سرليکونه ستاسې له کتنلړ نه ليري شوه}}:',
'watchlistedit-raw-title'     => 'خام کتنلړ سمول',
'watchlistedit-raw-legend'    => 'خام کتنلړ سمول',
'watchlistedit-raw-titles'    => 'سرليکونه:',
'watchlistedit-raw-submit'    => 'کتنلړ اوسمهاله کول',
'watchlistedit-raw-done'      => 'ستاسې کتنلړ اوسمهاله شو.',
'watchlistedit-raw-added'     => '{{PLURAL:$1|1 سرليک ورګډ شو|$1 سرليکونه ورګډ شوه}}:',
'watchlistedit-raw-removed'   => '{{PLURAL:$1|1 سرليک ليرې شو|$1 سرليکونه ليري شوه}}:',

# Watchlist editing tools
'watchlisttools-view' => 'اړونده بدلونونه کتل',
'watchlisttools-edit' => 'کتنلړ ليدل او سمول',
'watchlisttools-raw'  => 'خام کتنلړ سمول',

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
'version'                   => 'بڼه',
'version-extensions'        => 'لګېدلي شاتاړي',
'version-specialpages'      => 'ځانګړي مخونه',
'version-other'             => 'بل',
'version-version'           => '(بڼه $1)',
'version-license'           => 'منښتليک',
'version-poweredby-credits' => "دا ويکي د '''[http://www.mediawiki.org/ مېډياويکي]''' په سېک چلېږي، ټولې رښتې خوندي دي © 2001-$1 $2.",
'version-poweredby-others'  => 'نور',
'version-license-info'      => 'مېډياويکي يو وړيا ساوتری دی؛ تاسې يې په ډاډه زړه د GNU د ټولګړو کارېدنو د منښتليک چې د وړيا ساوتريو د بنسټ له مخې خپور شوی، خپرولی او/يا بدلولی شی؛ د منښتليک ۲ بڼه او يا (ستاسې د خوښې) هر يوه وروستۍ بڼه.

مېډياويکي د ښه کارېدنې په نيت خپور شوی، خو د ضمني سوداګريز او يا د کوم ځانګړي کار د ضمانت نه پرته. د نورو مالوماتو لپاره د GNU د ټولګړو کارېدنو منښتليک وګورۍ.

تاسې بايد د دې پروګرام سره يو [{{SERVER}}{{SCRIPTPATH}}/COPYING a copy of the GNU General Public License] ترلاسه کړی وي؛ که داسې نه وي، نو د وړيا ساوتريو بنسټ، Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA ته يو ليک وليکۍ، او يا يې [http://www.gnu.org/licenses/old-licenses/gpl-2.0.html پرليکه ولولۍ].',
'version-software'          => 'نصب شوی ساوتری',
'version-software-product'  => 'اېبره',
'version-software-version'  => 'بڼه',

# Special:FilePath
'filepath-page'   => 'دوتنه:',
'filepath-submit' => 'ورځه',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'د دوه ګونو دوتنو پلټنه',
'fileduplicatesearch-legend'   => 'د دوه ګونو دوتنو پلټنه',
'fileduplicatesearch-filename' => 'د دوتنې نوم:',
'fileduplicatesearch-submit'   => 'پلټل',
'fileduplicatesearch-result-1' => '"$1" بله کټ مټ ورته غبرګونې دوتنه نلري.',

# Special:SpecialPages
'specialpages'                 => 'ځانګړي مخونه',
'specialpages-note'            => '----
* نورماله ځانګړي مخونه.
* <strong class="mw-specialpagerestricted">محدوده ځانګړي مخونه.</strong>',
'specialpages-group-other'     => 'نور ځانګړي مخونه',
'specialpages-group-login'     => 'ننوتل / ګڼون جوړول',
'specialpages-group-changes'   => 'وروستي بدلونونه او يادښتونه',
'specialpages-group-users'     => 'کارنان او رښتې',
'specialpages-group-highuse'   => 'ډېر کارېدونکي مخونه',
'specialpages-group-pages'     => 'د مخونو لړليک',
'specialpages-group-pagetools' => 'د مخ اوزارونه',
'specialpages-group-wiki'      => 'ويکيډاټا او اوزارونه',

# Special:BlankPage
'blankpage'              => 'تش مخ',
'intentionallyblankpage' => 'همدا مخ په لوی لاس تش پرېښودل شوی دی',

# Special:Tags
'tag-filter-submit'       => 'چاڼګر',
'tags-display-header'     => 'د بدلون په لړليکونو کې ښکارېدنه',
'tags-description-header' => 'د مانا بشپړه څرګندونه',
'tags-edit'               => 'سمول',
'tags-hitcount'           => '$1 {{PLURAL:$1|بدلون|بدلونونه}}',

# Special:ComparePages
'comparepages'     => 'مخونه پرتلل',
'compare-selector' => 'د مخ بڼې سره پرتلل',
'compare-page1'    => '۱ مخ',
'compare-page2'    => '۲ مخ',
'compare-rev1'     => '۱ بڼه',
'compare-rev2'     => '۲ بڼه',
'compare-submit'   => 'پرتلل',

# Database error messages
'dberr-header'    => 'دا ويکي يوه ستونزه لري',
'dberr-problems'  => 'اوبخښۍ!
دم مهال دا وېبپاڼه د تخنيکي ستونزو سره مخامخ شوې.',
'dberr-usegoogle' => 'تاسې کولای شی چې هم مهاله د ګووګل له لخوا هم د پلټنې هڅه وکړۍ.',

# HTML forms
'htmlform-invalid-input'       => 'ستاسې ځينې ورکړېينې ستونزې لري',
'htmlform-select-badoption'    => 'کوم څه چې تاسو ځانګړي کړي هغه د منلو وړ خوښه نه ده.',
'htmlform-int-invalid'         => 'کوم څه چې تاسو ځانګړي کړي هغه يوه سمه شمېره نه ده.',
'htmlform-float-invalid'       => 'کوم څه چې تاسو ځانګړي کړي هغه يوه شمېره نه ده.',
'htmlform-int-toolow'          => 'کوم ارزښت چې تاسې ځانګړی کړی هغه تر $1 لږ دی',
'htmlform-int-toohigh'         => 'کوم ارزښت چې تاسې ځانګړی کړی هغه تر $1 ډېر  دی',
'htmlform-required'            => 'دې ارزښت ته اړتيا ده',
'htmlform-submit'              => 'سپارل',
'htmlform-reset'               => 'بدلونونه ناکړل',
'htmlform-selectorother-other' => 'بل',

# Special:DisableAccount
'disableaccount'             => 'د يو کارن ګڼون ناچارنول',
'disableaccount-user'        => 'کارن-نوم:',
'disableaccount-reason'      => 'سبب:',
'disableaccount-confirm'     => "د کارن دا ګڼون ناچارنول.
د دې ګڼون کارن به و نه توانېږي چې غونډال کې ننوځي، خپل پټنوم پرځاي کړي، او يا د خبراوي برېښليک ترلاسه کړي.
که دم مهال د دې ګڼون کارن له هر ځای نه پرليکه وي، هغه به سمدلاسه د غونډال نه ووځي.
''دا مه هېروۍ چې د يوه ګڼون ناچارنولو چاره بېرته پرشا نه ګرځي تر هاغه پورې چې د غونډال د پازوال منځګړتوب پکې نه وي.''",
'disableaccount-mustconfirm' => 'تاسې بايد د دې چارې پخلی وکړی چې تاسې همدا ګڼون ناچارنول غواړۍ.',
'disableaccount-nosuchuser'  => 'د "$1" کارن ګڼون نشته.',
'disableaccount-success'     => 'د "$1" کارن ګڼون د تل لپاره ناچارن شو.',
'disableaccount-logentry'    => 'د تل لپاره د [[$1]] د کارن ګڼون ناچارنول',

);

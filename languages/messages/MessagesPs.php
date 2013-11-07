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
 * @author Kaganer
 * @author Umherirrender
 */

$rtl = true;

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
	'Allmessages'               => array( 'ټول-پيغامونه' ),
	'Allpages'                  => array( 'ټول_مخونه' ),
	'Ancientpages'              => array( 'لرغوني_مخونه' ),
	'Blankpage'                 => array( 'تش_مخ' ),
	'Block'                     => array( 'بنديز،_د_آی_پي_بنديز،_بنديز_لګېدلی_کارن_Block' ),
	'Booksources'               => array( 'د_کتاب_سرچينې' ),
	'Categories'                => array( 'وېشنيزې' ),
	'ChangePassword'            => array( 'پټنوم_بدلول،_پټنوم_بيا_پر_ځای_کول،_د_بيا_پر_ځای_کولو_پاسپورټ' ),
	'Contributions'             => array( 'ونډې' ),
	'CreateAccount'             => array( 'کارن-حساب_جوړول' ),
	'DeletedContributions'      => array( 'ړنګې_شوي_ونډې' ),
	'Export'                    => array( 'صادرول' ),
	'BlockList'                 => array( 'د_بنديزلړليک' ),
	'LinkSearch'                => array( 'د_تړنې_پلټنه' ),
	'Listfiles'                 => array( 'د_انځورونو_لړليک' ),
	'Listusers'                 => array( 'د_کارنانو_لړليک' ),
	'Log'                       => array( 'يادښتونه،_يادښت' ),
	'Lonelypages'               => array( 'يتيم_مخونه' ),
	'Longpages'                 => array( 'اوږده_مخونه' ),
	'Mycontributions'           => array( 'زماونډې' ),
	'Mypage'                    => array( 'زما_پاڼه' ),
	'Mytalk'                    => array( 'زما_خبرې_اترې' ),
	'Newimages'                 => array( 'نوي_انځورونه' ),
	'Newpages'                  => array( 'نوي_مخونه' ),
	'Popularpages'              => array( 'نامتومخونه' ),
	'Preferences'               => array( 'غوره_توبونه' ),
	'Prefixindex'               => array( 'د_مختاړيو_ليکلړ' ),
	'Protectedpages'            => array( 'ژغورلي_مخونه' ),
	'Protectedtitles'           => array( 'ژغورلي_سرليکونه' ),
	'Randompage'                => array( 'ناټاکلی،_ناټاکلی_مخ' ),
	'Recentchanges'             => array( 'اوسني_بدلونونه' ),
	'Search'                    => array( 'پلټنه' ),
	'Shortpages'                => array( 'لنډ_مخونه' ),
	'Specialpages'              => array( 'ځانګړي_مخونه' ),
	'Statistics'                => array( 'شمار' ),
	'Unblock'                   => array( 'بنديز_لرې_کول' ),
	'Uncategorizedcategories'   => array( 'ناوېشلې_وېشنيزې' ),
	'Uncategorizedimages'       => array( 'ناوېشلي_انځورونه،_ناوېشلې_دوتنې' ),
	'Uncategorizedpages'        => array( 'ناوېشلي_مخونه' ),
	'Uncategorizedtemplates'    => array( 'ناوېشلې_کينډۍ' ),
	'Undelete'                  => array( 'ناړنګول' ),
	'Unusedcategories'          => array( 'ناکارېدلي_وېشنيزې' ),
	'Unusedimages'              => array( 'ناکارېدلې_دوتنې' ),
	'Unusedtemplates'           => array( 'ناکارېدلې_کينډۍ' ),
	'Unwatchedpages'            => array( 'ناکتلي_مخونه' ),
	'Upload'                    => array( 'پورته_کول' ),
	'Userlogin'                 => array( 'ننوتل' ),
	'Userlogout'                => array( 'وتل' ),
	'Version'                   => array( 'بڼه' ),
	'Wantedcategories'          => array( 'غوښتلې_وېشنيزې' ),
	'Wantedfiles'               => array( 'غوښتلې_دوتنې' ),
	'Wantedtemplates'           => array( 'غوښتلې_کينډۍ' ),
	'Watchlist'                 => array( 'کتنلړ' ),
);

$magicWords = array(
	'notoc'                     => array( '0', '__بی‌نيولک__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__بی‌نندارتونه__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__نيوليکداره__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__نيوليک__', '__TOC__' ),
	'noeditsection'             => array( '0', '__بی‌برخې__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'روانه_مياشت', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'          => array( '1', 'دروانې_مياشت_نوم', 'CURRENTMONTHNAME' ),
	'currentmonthabbrev'        => array( '1', 'دروانې_مياشت_لنډون', 'CURRENTMONTHABBREV' ),
	'currentday'                => array( '1', 'نن', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'نن۲', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'دننۍورځې_نوم', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'سږکال', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'داوخت', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'دم_ګړۍ', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'سيمه_يزه_مياشت', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonthname'            => array( '1', 'دسيمه_يزې_مياشت_نوم', 'LOCALMONTHNAME' ),
	'localmonthabbrev'          => array( '1', 'دسيمه_يزې_مياشت_لنډون', 'LOCALMONTHABBREV' ),
	'localday'                  => array( '1', 'سيمه_يزه_ورځ', 'LOCALDAY' ),
	'localday2'                 => array( '1', 'سيمه_يزه_ورځ۲', 'LOCALDAY2' ),
	'localdayname'              => array( '1', 'دسيمه_يزې_ورځ_نوم', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', 'سيمه_يزکال', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'سيمه_يزوخت', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'سيمه_يزه_ګړۍ', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'دمخونوشمېر', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'دليکنوشمېر', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'ددوتنوشمېر', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'دکارونکوشمېر', 'NUMBEROFUSERS' ),
	'pagename'                  => array( '1', 'دمخ_نوم', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'دمخ_نښه', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'نوم_تشيال', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'د_نوم_تشيال_نښه', 'NAMESPACEE' ),
	'talkspace'                 => array( '1', 'دخبرواترو_تشيال', 'TALKSPACE' ),
	'talkspacee'                => array( '1', 'دخبرواترو_تشيال_نښه', 'TALKSPACEE' ),
	'subjectspace'              => array( '1', 'دسکالوتشيال', 'دليکنې_تشيال', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'             => array( '1', 'دسکالوتشيال_نښه', 'دليکنې_تشيال_نښه', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'              => array( '1', 'دمخ_بشپړنوم', 'FULLPAGENAME' ),
	'fullpagenamee'             => array( '1', 'دمخ_بشپړنوم_نښه', 'FULLPAGENAMEE' ),
	'msg'                       => array( '0', 'پیغام:', 'پ:', 'MSG:' ),
	'img_thumbnail'             => array( '1', 'بټنوک', 'thumbnail', 'thumb' ),
	'img_right'                 => array( '1', 'ښي', 'right' ),
	'img_left'                  => array( '1', 'کيڼ', 'left' ),
	'img_none'                  => array( '1', 'هېڅ', 'none' ),
	'img_center'                => array( '1', 'مېنځ،_center', 'center', 'centre' ),
	'sitename'                  => array( '1', 'دوېبځي_نوم', 'SITENAME' ),
	'server'                    => array( '0', 'پالنګر', 'SERVER' ),
	'servername'                => array( '0', 'دپالنګر_نوم', 'SERVERNAME' ),
	'grammar'                   => array( '0', 'ګرامر:', 'GRAMMAR:' ),
	'currentweek'               => array( '1', 'روانه_اوونۍ', 'CURRENTWEEK' ),
	'currentdow'                => array( '1', 'داوونۍورځ', 'CURRENTDOW' ),
	'localweek'                 => array( '1', 'سيمه_يزه_اوونۍ', 'LOCALWEEK' ),
	'plural'                    => array( '0', 'جمع:', 'PLURAL:' ),
	'language'                  => array( '0', '#ژبه:', '#LANGUAGE:' ),
	'special'                   => array( '0', 'ځانګړی', 'special' ),
	'hiddencat'                 => array( '1', '__پټه_وېشنيزه__', '__HIDDENCAT__' ),
	'pagesize'                  => array( '1', 'مخکچه', 'PAGESIZE' ),
	'index'                     => array( '1', '__ليکلړ__', '__INDEX__' ),
	'noindex'                   => array( '1', '__بې_ليکلړ__', '__NOINDEX__' ),
	'protectionlevel'           => array( '1', 'ژغورکچه', 'PROTECTIONLEVEL' ),
);

$messages = array(
# User preference toggles
'tog-underline' => 'کرښنې تړنې:',
'tog-justify' => 'پاراگرافونه همجوليزول',
'tog-hideminor' => 'په وروستيو بدلونو کې واړه سمونونه پټول',
'tog-hidepatrolled' => 'په وروستيو بدلونونو کې څارل شوې سمونونه پټول',
'tog-newpageshidepatrolled' => 'د نوؤ مخونو په لړليک کې کتل شوي مخونه پټول',
'tog-extendwatchlist' => 'يوازې د وروستني بدلونونو د ښکاره کولو لپاره نه بلکه د ټولو بدلونونو د ښکاره کولو لپاره کتنلړ غځول',
'tog-usenewrc' => 'په کتنلړ او وروستي بدلونو مخ باندې ډله ايز بدلونونه',
'tog-numberheadings' => 'د سرليکونو خپلکاره شمېرايښودنه',
'tog-showtoolbar' => 'د سمون اوزارپټه ښکاره کول',
'tog-editondblclick' => 'په دوه کلېک سره د مخونو سمون',
'tog-editsection' => 'د [سمول] تړنې له لوري د يوې ليکنې يوه برخه د سمون وړ گرځول',
'tog-editsectiononrightclick' => 'د ليکنې د يوې برخې په سرليک ښي کلېک کول د هغې برخې سمون چارنوي',
'tog-showtoc' => 'نيوليک ښکاره کول (د هغو مخونو لپاره چې له ۳ نه ډېر سرليکونه لري)',
'tog-rememberpassword' => 'زما کارن-نوم په دې کتنمل (تر $1 {{PLURAL:$1|ورځې|ورځو}}) په ياد وساته!',
'tog-watchcreations' => 'زما کتنلړ کې دې هغه مخونه چې زه يې جوړوم او هغه دوتنې چې زه يې پورته کوم ورگډې شي',
'tog-watchdefault' => 'زما کتنلړ کې دې هغه مخونه او دوتنې ورگډې شي چې زه يې سموم',
'tog-watchmoves' => 'زما کتنلړ کې دې هغه مخونه او دوتنې ورگډې شي چې زه يې لېږدوم',
'tog-watchdeletion' => 'زما کتنلړ کې دې هغه مخونه او دوتنې ورگډې شي چې زه يې ړنگوم',
'tog-minordefault' => 'په تلواليزه توگه ټول سمونونه واړه په نخښه کول',
'tog-previewontop' => 'د سمون بکس نه دمخه مخکتنه ښکاره کول',
'tog-previewonfirst' => 'په لومړي سمون کې مخکتنه ښکاره کول',
'tog-nocache' => 'د کتنمل د مخ ياده ساتنې چار ناچارندول',
'tog-enotifwatchlistpages' => 'کله چې زما د کتنلړ په يوې دوتنې يا يو مخ کې بدلون راځي نو ما ته دې د بدلون په اړه برېښليک راشي',
'tog-enotifusertalkpages' => 'کله چې زما د خبرو اترو په مخ کې بدلون پېښېږي نو ما ته دې يو برېښليک ولېږلی شي.',
'tog-enotifminoredits' => 'کله چې په مخونو او دوتنو کې وړې سمونې کېږي نو ماته دې د بدلون په اړه برېښليک راشي',
'tog-enotifrevealaddr' => 'په يادښت برېښليک کې زما برېښليک پته ښکاره کول',
'tog-shownumberswatching' => 'د کتونکو کارنانو شمېر ښکاره کول',
'tog-oldsig' => 'اوسنی لاسليک:',
'tog-fancysig' => 'لاسليک د ويکي متن په توگه په پام کې نيول (د خپلکاره تړن د تړلو پرته)',
'tog-uselivepreview' => 'ژوندۍ مخليدنه کارول (آزمېښتي)',
'tog-forceeditsummary' => 'د يوه تش سمون لنډيز په ورکولو سره دې خبر راکړل شي',
'tog-watchlisthideown' => 'په کتنلړ کې زما سمونې پټول',
'tog-watchlisthidebots' => 'په کتنلړ کې د روباټ سمونې پټول',
'tog-watchlisthideminor' => 'په کتنلړ کې وړې سمونې پټول',
'tog-watchlisthideliu' => 'په کتنلړ کې د ثبت شويو کارنانو سمونې پټول',
'tog-watchlisthideanons' => 'په کتنلړ کې د ورکنومو کارنانو سمونې پټول',
'tog-watchlisthidepatrolled' => 'په کتنلړ کې څارل شوې سمونې پټول',
'tog-ccmeonemails' => 'هغه برېښليکونه چې زه يې نورو ته لېږم، د هغو يوه کاپي دې ماته هم راشي',
'tog-diffonly' => 'د توپيرونو نه لاندې د مخ مېنځپانگه پټول',
'tog-showhiddencats' => 'پټې وېشنيزې ښکاره کول',
'tog-norollbackdiff' => 'پرشاتمبولو وروسته توپيرونه نه ښودل',
'tog-useeditwarning' => 'کله چې يو سمون مخ څخه د بدلونونو د خوندي کولو پرته وځم خبر دې شم',
'tog-prefershttps' => 'د ننوتلو پر مهال تل يوه خوندي اړيکتيا کارول',

'underline-always' => 'تل',
'underline-never' => 'هېڅکله',
'underline-default' => 'د کتنمل تلواليزې چارې',

# Font style option in Special:Preferences
'editfont-style' => 'د سيمه ايزې ليکبڼې سمول:',
'editfont-default' => 'د کتنمل تلواليزې چارې',
'editfont-monospace' => 'يو واټنيزه ليکبڼه',
'editfont-sansserif' => 'سان سېرېف ليکبڼه',
'editfont-serif' => 'سېرېف ليکبڼه',

# Dates
'sunday' => 'يونۍ',
'monday' => 'دونۍ',
'tuesday' => 'درې نۍ',
'wednesday' => 'څلرنۍ',
'thursday' => 'پينځنۍ',
'friday' => 'جمعه',
'saturday' => 'اونۍ',
'sun' => 'يونۍ',
'mon' => 'دونۍ',
'tue' => 'درې نۍ',
'wed' => 'څلرنۍ',
'thu' => 'پينځه نۍ',
'fri' => 'جمعه',
'sat' => 'اونۍ',
'january' => 'جنوري',
'february' => 'فبروري',
'march' => 'مارچ',
'april' => 'اپرېل',
'may_long' => 'می',
'june' => 'جون',
'july' => 'جولای',
'august' => 'اگسټ',
'september' => 'سېپتمبر',
'october' => 'اکتوبر',
'november' => 'نومبر',
'december' => 'ډيسمبر',
'january-gen' => 'جنوري',
'february-gen' => 'فبروري',
'march-gen' => 'مارچ',
'april-gen' => 'اپرېل',
'may-gen' => 'می',
'june-gen' => 'جون',
'july-gen' => 'جولای',
'august-gen' => 'اگسټ',
'september-gen' => 'سېپتمبر',
'october-gen' => 'اکتوبر',
'november-gen' => 'نومبر',
'december-gen' => 'ډيسمبر',
'jan' => 'جنوري',
'feb' => 'فبروري',
'mar' => 'مارچ',
'apr' => 'اپرېل',
'may' => 'می',
'jun' => 'جون',
'jul' => 'جولای',
'aug' => 'اگسټ',
'sep' => 'سېپتمبر',
'oct' => 'اکتوبر',
'nov' => 'نومبر',
'dec' => 'ډيسمبر',
'january-date' => 'جنوري $1',
'february-date' => 'فېبروري $1',
'march-date' => 'مارچ $1',
'april-date' => 'اپريل $1',
'may-date' => 'مۍ $1',
'june-date' => 'جون $1',
'july-date' => 'جولای $1',
'august-date' => 'اگست $1',
'september-date' => 'سېپتمبر $1',
'october-date' => 'اکتوبر $1',
'november-date' => 'نومبر $1',
'december-date' => 'دېسمبر $1',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|وېشنيزه|وېشنيزې}}',
'category_header' => 'د "$1" په وېشنيزه کې شته مخونه',
'subcategories' => 'څېرمه وېشنيزې',
'category-media-header' => 'د "$1" په وېشنيزه کې شته رسنۍ',
'category-empty' => "''دا وېشنيزه تر اوسه پورې کوم مخ يا رسنيزه دوتنه نلري.''",
'hidden-categories' => '{{PLURAL:$1|پټه وېشنيزه|پټې وېشنيزې}}',
'hidden-category-category' => 'پټې وېشنيزې',
'category-subcat-count' => '{{PLURAL:$2|په دې وېشنيزه کې دا لاندې وړه وېشنيزه ده.|په دې وېشنيزه کې له ټولټال $2 نه {{PLURAL:$1|وړه وېشنيزه ده|$1 وړې وېشنيزې دي}}.}}',
'category-subcat-count-limited' => 'دا وېشنيزه دا لاندې {{PLURAL:$1|يوه څېرمه وېشنيزه|$1 څېرمه وېشنيزې}} لري.',
'category-article-count' => '{{PLURAL:$2|په همدې وېشنيزه کې يواځې دغه لاندينی مخ شته.|دا {{PLURAL:$1|لاندينی مخ|$1 لانديني مخونه}}، له ټولټال $2 مخونو نه په دې وېشنيزه کې شته.}}',
'category-article-count-limited' => 'په دې وېشنيزه کې {{PLURAL:$1|يوه دوتنه ده|$1 دوتنې دي}}.',
'category-file-count' => '{{PLURAL:$2|په همدې وېشنيزه کې يواځې دغه لاندينی مخ شته.|دا {{PLURAL:$1|لاندينی مخ|$1 لانديني مخونه}}، له ټولټال $2 مخونو نه په دې وېشنيزه کې شته.}}',
'category-file-count-limited' => 'په اوسنۍ وېشنيزه کې {{PLURAL:$1|يوه دوتنه ده|$1 دوتنې دي}}.',
'listingcontinuesabbrev' => 'پرله پسې',
'index-category' => 'ليکلړلرونکي مخونه',
'noindex-category' => 'بې ليکلړه مخونه',
'broken-file-category' => 'د دوتنو د ماتو تړنو مخونه',

'about' => 'په اړه',
'article' => 'مېنځپانگيز مخ',
'newwindow' => '(په نوې کړکۍ کې پرانيستل کېږي)',
'cancel' => 'ناگارل',
'moredotdotdot' => 'نور ...',
'morenotlisted' => 'دا لړليک بشپړ نه دی',
'mypage' => 'زما مخ',
'mytalk' => 'خبرې اترې',
'anontalk' => 'ددې IP خبرې اترې',
'navigation' => 'گرځښت',
'and' => '&#32;او',

# Cologne Blue skin
'qbfind' => 'موندل',
'qbbrowse' => 'سپړل',
'qbedit' => 'سمول',
'qbpageoptions' => 'همدا مخ',
'qbmyoptions' => 'زما پاڼې',
'qbspecialpages' => 'ځانگړي مخونه',
'faq' => 'ډ-ځ-پ',
'faqpage' => 'Project:ډ-ځ-پ',

# Vector skin
'vector-action-addsection' => 'سرليکونه ورگډول',
'vector-action-delete' => 'ړنگول',
'vector-action-move' => 'لېږدول',
'vector-action-protect' => 'ژغورل',
'vector-action-undelete' => 'ناړنگول',
'vector-action-unprotect' => 'ژغورنه بدلول',
'vector-simplesearch-preference' => 'د پلټنې ساده پټه چارنول (يوازې په وېکټور پوښۍ کار کوي)',
'vector-view-create' => 'جوړول',
'vector-view-edit' => 'سمول',
'vector-view-history' => 'پېښليک کتل',
'vector-view-view' => 'لوستل',
'vector-view-viewsource' => 'سرچينه کتل',
'actions' => 'کړنې',
'namespaces' => 'نوم-تشيالونه',
'variants' => 'ډولونه',

'navigation-heading' => 'گرځښت غورنۍ',
'errorpagetitle' => 'تېروتنه',
'returnto' => 'بېرته $1 ته وگرځه.',
'tagline' => 'د {{SITENAME}} لخوا',
'help' => 'لارښود',
'search' => 'پلټنه',
'searchbutton' => 'پلټل',
'go' => 'ورځه',
'searcharticle' => 'ورځه',
'history' => 'د مخ پېښليک',
'history_short' => 'پېښليک',
'updatedmarker' => 'زما د وروستي راتگ نه راپدېخوا اوسمهاله شوی',
'printableversion' => 'چاپي بڼه',
'permalink' => 'تلپاتې تړنه',
'print' => 'چاپ',
'view' => 'کتل',
'edit' => 'سمول',
'create' => 'جوړول',
'editthispage' => 'همدا مخ سمول',
'create-this-page' => 'همدا مخ ليکل',
'delete' => 'ړنگول',
'deletethispage' => 'دا مخ ړنگول',
'undeletethispage' => 'دا مخ ناړنگول',
'undelete_short' => '{{PLURAL:$1|يو سمون|$1 سمونې}} ناړنگول',
'viewdeleted_short' => '{{PLURAL:$1|يو ړنگ شوی سمون|$1 ړنگ شوي سمونونه}} کتل',
'protect' => 'ژغورل',
'protect_change' => 'بدلون',
'protectthispage' => 'همدا مخ ژغورل',
'unprotect' => 'ژغورنه بدلول',
'unprotectthispage' => 'د دې مخ ژغورنه بدلول',
'newpage' => 'نوی مخ',
'talkpage' => 'د دې مخ په اړه خبرې اترې کول',
'talkpagelinktext' => 'خبرې اترې',
'specialpage' => 'ځانگړې پاڼه',
'personaltools' => 'شخصي اوزار',
'postcomment' => 'نوې برخه',
'articlepage' => 'د مخ مېنځپانگه ښکاره کول',
'talk' => 'خبرې اترې',
'views' => 'کتنې',
'toolbox' => 'اوزاربکس',
'userpage' => 'د کارن پاڼه کتل',
'projectpage' => 'د پروژې مخ کتل',
'imagepage' => 'د دوتنې مخ کتل',
'mediawikipage' => 'پيغام مخ کتل',
'templatepage' => 'د کينډۍ مخ کتل',
'viewhelppage' => 'د لارښود مخ کتل',
'categorypage' => 'د وېشنيزې مخ کتل',
'viewtalkpage' => 'خبرې اترې کتل',
'otherlanguages' => 'په نورو ژبو کې',
'redirectedfrom' => '(له $1 نه مخ گرځېدلی)',
'redirectpagesub' => 'د مخ گرځونې مخ',
'lastmodifiedat' => 'دا مخ وروستی ځل په $2، $1 بدلون موندلی.',
'viewcount' => 'همدا مخ {{PLURAL:$1|يو وار|$1 واره}} کتل شوی.',
'protectedpage' => 'ژغورلی مخ',
'jumpto' => 'ورټوپ کړه:',
'jumptonavigation' => 'گرځښت',
'jumptosearch' => 'پلټل',
'view-pool-error' => 'اوبخښۍ، دم ګړۍ پالنگران د ډېر بارېدو ستونزې سره مخامخ شوي.
ډېر زيات کارنان د همدې مخ د کتلو په هڅه کې دي.
لطفاً د دې مخ د کتلو د بيا هڅې نه دمخه يو څو شېبې صبر وکړۍ.

$1',
'pool-queuefull' => 'د بهير صف ډک دی',
'pool-errorunknown' => 'ناجوته ستونزه',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage).
'aboutsite' => 'د {{SITENAME}} په اړه',
'aboutpage' => 'Project:په اړه',
'copyright' => 'دا مېنځپانگه د $1 له مخې ستاسې لاسرسي ته پرته ده، خو هغه څه چې په خلاف يې وييل شوي.',
'copyrightpage' => '{{ns:project}}:رښتې',
'currentevents' => 'اوسنۍ پېښې',
'currentevents-url' => 'Project:تازه پېښې',
'disclaimers' => 'ردادعاليکونه',
'disclaimerpage' => 'Project:ټولگړی ردادعاليک',
'edithelp' => 'د لارښود سمون',
'helppage' => 'Help:نيوليک',
'mainpage' => 'لومړی مخ',
'mainpage-description' => 'لومړی مخ',
'policy-url' => 'Project:تگلاره',
'portal' => 'د ټولنې تانبه',
'portal-url' => 'Project:د ټولنې تانبه',
'privacy' => 'د پټنتيا تگلاره',
'privacypage' => 'Project:د پټنتيا تگلاره',

'badaccess' => 'د لاسرسۍ تېروتنه',
'badaccess-group0' => 'تاسې د غوښتل شوې کړنې د ترسره کولو اجازه نه لرۍ.',
'badaccess-groups' => 'د کومې کړنې غوښتنه چې تاسې کړې د هغو کارنانو پورې محدوده ده چې {{PLURAL:$2|په ډله د|په ډلو د}}: $1 کې دي.',

'versionrequired' => 'د ميډياويکي $1 بڼې ته اړتيا ده',
'versionrequiredtext' => 'د دې مخ په ليدلو کې د مېډياويکي $1 بڼې ته اړتيا ده. 
[[Special:Version|د بڼې مخ وگورۍ]].',

'ok' => 'ښه',
'retrievedfrom' => '"$1" نه اخيستل شوی',
'youhavenewmessages' => 'تاسې $1 لری  ($2).',
'newmessageslink' => 'نوي پيغامونه',
'newmessagesdifflink' => 'وروستی بدلون',
'youhavenewmessagesfromusers' => 'تاسې د {{PLURAL:$3|يو بل کارن|$3 کارنانو}} لخوا $1 لرۍ ($2).',
'youhavenewmessagesmanyusers' => 'تاسې د يو شمېر کارنانو لخوا $1 لرۍ ($2).',
'newmessageslinkplural' => '{{PLURAL:$1|يو نوی پيغام|نوي پيغامونه}}',
'newmessagesdifflinkplural' => 'وروستي {{PLURAL:$1|بدلون|بدلونونه}}',
'youhavenewmessagesmulti' => 'تاسې په $1 کې نوي پېغامونه لرۍ',
'editsection' => 'سمول',
'editold' => 'سمول',
'viewsourceold' => 'سرچينې کتل',
'editlink' => 'سمول',
'viewsourcelink' => 'سرچينه کتل',
'editsectionhint' => 'د سمولو برخه: $1',
'toc' => 'نيوليک',
'showtoc' => 'ښکاره کول',
'hidetoc' => 'پټول',
'collapsible-collapse' => 'پرځول',
'collapsible-expand' => 'غځول',
'thisisdeleted' => '$1 کتل او يا بيازېرمل؟',
'viewdeleted' => '$1 کتل؟',
'restorelink' => '{{PLURAL:$1|يو ړنگ شوی سمون|$1 ړنگ شوي سمونونه}}',
'feedlinks' => 'کتنه:',
'site-rss-feed' => '$1 د آر اس اس کتنه',
'site-atom-feed' => '$1 د اټوم کتنه',
'page-rss-feed' => '"$1" د آر اس اس کتنه',
'page-atom-feed' => 'د "$1" د اټوم کتنې',
'feed-atom' => 'اټوم',
'feed-rss' => 'آر اس اس',
'red-link-title' => '$1 (تر اوسه پورې نه شته)',
'sort-descending' => 'مخښکته اوډل',
'sort-ascending' => 'مخپورته اوډل',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'مخ',
'nstab-user' => 'کارن مخ',
'nstab-media' => 'د رسنۍ مخ',
'nstab-special' => 'ځانگړی مخ',
'nstab-project' => 'د پروژې مخ',
'nstab-image' => 'دوتنه',
'nstab-mediawiki' => 'پيغام',
'nstab-template' => 'کينډۍ',
'nstab-help' => 'لارښود مخ',
'nstab-category' => 'وېشنيزه',

# Main script and global functions
'nosuchaction' => 'هېڅ داسې کومه کړنه نشته',
'nosuchactiontext' => 'کومه کړنه چې د URL لخوا ځانگړې شوې سمه نه ده.
کېدای شي چې URL مو سم نه وي ټايپ کړی، او يا مو يوه ناسمه تړنه څارلې وي.
دا د دې هم ښکارندويي کوي چې کېدای شي چې د {{SITENAME}} لخوا کارېدونکې ساوترې کې يوه تېروتنه وي.',
'nosuchspecialpage' => 'داسې هېڅ کوم ځانگړی مخ نشته',
'nospecialpagetext' => '<strong>تاسې د يو ناسم ځانگړي مخ غوښتنه کړې.</strong>

تاسې کولای شی چې د سمو ځانگړو مخونو لړليک په [[Special:SpecialPages|{{int:specialpages}}]] کې ومومۍ.',

# General errors
'error' => 'تېروتنه',
'databaseerror' => 'د ډاټابېز تېروتنه',
'laggedslavemode' => "'''گواښنه:''' په دې مخ کې کېدای شي تازه اوسمهالېدنې نه وي.",
'readonly' => 'توکبنسټ تړل شوی',
'enterlockreason' => 'د بنديز يو سبب وليکۍ، او همداراز د بنديز د ليرې کېدلو يوه اټکليزه نېټه هم څرگنده کړۍ',
'missing-article' => 'توکبنسټ د "$1" $2 په نامه د ورکړ شوي مخ متن چې بايد موندلی يې وای، و نه موند.

دا ستونزه اکثراً د يوه ړنگ شوي مخ د پېښليک يا توپير د تړنو په څارلو کې رامېنځ ته کېږي.

که چېرته داسې نه وي، نو بيا کېدای شي چې په ساوترې کې کومه تېروتنه رابرسېره شوې وي.
لطفاً د دې چارې راپور د URL په نښه کولو سره يوه [[Special:ListUsers/sysop|پازوال]] ته ورکړۍ.',
'missingarticle-rev' => '(مخليدنه#: $1)',
'missingarticle-diff' => '(توپير: $1، $2)',
'internalerror' => 'کورنۍ تېروتنه',
'internalerror_info' => 'کورنۍ تېروتنه: $1',
'fileappenderrorread' => 'د پايملون په وخت کې "$1" و نه لوستل شو.',
'fileappenderror' => 'د "$1" پايملون "$2" ته ترسره نه شو..',
'filecopyerror' => 'د "$1" په نامه دوتنه مو "$2" ته و نه لمېسلای شوه.',
'filerenameerror' => 'د "$1" په نامه د دوتنې نوم "$2" ته بدل نه شو.',
'filedeleteerror' => 'د "$1" دوتنه ړنگه نه شوه.',
'directorycreateerror' => 'د "$1" په نامه ليکلړ جوړ نه شو.',
'filenotfound' => 'د "$1" دوتنه مو و نه موندله.',
'fileexistserror' => 'د "$1" په نامه دوتنه نه ليکل کېږي: دوتنه د پخوا نه دلته شته',
'unexpected' => 'نا اټکله شمېره: "$1"="$2".',
'formerror' => 'ستونزه: فورمه مو و نه سپارل شوه',
'badarticleerror' => 'په دې مخ دا کړنه نه شي ترسره کېدلای.',
'cannotdelete' => 'د "$1" مخ يا دوتنې ړنگېدنه ترسره نه شوه.
کېدای شي چې وار دمخې دا کوم بل چا ړنگه کړې وي.',
'cannotdelete-title' => 'د "$1" مخ نشي ړنگېدای',
'badtitle' => 'ناسم سرليک',
'badtitletext' => 'ستاسې د غوښتل شوي مخ سرليک سم نه وو، يا مو د سرليک ځای تش وو او يا هم د ژبو خپلمنځي تړنې څخه يا د ويکي گانو خپلمنځي سرليکونو څخه يو ناسم توری مو پکې کارولی وي.
کېدای شي چې ستاسې په ورکړ شوي سرليک کې يو يا څو داسې توري وي چې د سرليک په توگه بايد و نه کارېږي.',
'querypage-no-updates' => 'د دې مخ اوسمهالېدنې ناچارن شوي.
په ښکاره توگه د دې ځای اومتوک به نه وي تازه شوي.',
'viewsource' => 'سرچينه کتل',
'viewsource-title' => 'د $1 سرچينه کتل',
'actionthrottled' => 'د دې کړنې مخنيوی وشو',
'protectedpagetext' => 'دا مخ د سمون او نورو کړنو د ترسره کولو په تکل ژغورل شوی.',
'viewsourcetext' => 'تاسې د دې مخ سرچينه کتلی او لمېسلی شی:',
'viewyourtext' => "تاسې په دې مخ کې د '''خپلو سمونونو''' سرچينه کتلی او لمېسلی شی:",
'protectedinterface' => 'دا مخ د دې ويکي د ساوترې د ليدنمخ متن لري، او د ورانکارۍ په خاطر ژغورل شوی.
په ټولو ويکي گانو کې د ژباړې د ورگډولو او يا هم د ژباړې د سمون او بدلون لپاره د مېډياويکي د ځايتابه پروژه [//translatewiki.net/ translatewiki.net] وکاروۍ.',
'editinginterface' => "'''گواښنه:''' تاسو په يوه داسې مخ کې بدلون راولی کوم چې د يوې پوستکالی د ليدنمخ متن په توگه کارېږي.
په همدې مخ کې بدلون راوستل به د نورو کارنانو د ليدنمخ بڼه اغېزمنه کړي.
د ژباړې د ورگډولو او بدلون لپاره، مهرباني وکړی د [//translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net]، وېبځي ته ولاړ شی. دا وېبځی د ميډياويکي د ځايتابه پروژه ده.",
'namespaceprotected' => "تاسې د '''$1''' په نوم-تشيال کې د مخونو د سمولو اجازه نه لرۍ.",
'customcssprotected' => 'تاسې د دې CSS مخ د سمولو اجازه نه لرۍ، دا ځکه چې دا مخ د بل کارن شخصي امستنې لري.',
'customjsprotected' => 'تاسې د دې جاواسکرېپټ مخ د سمولو اجازه نه لرۍ، دا ځکه چې دا مخ د بل کارن شخصي امستنې لري.',
'mycustomcssprotected' => 'تاسې د دې CSS مخ د سمولو اجازه نلرۍ.',
'mycustomjsprotected' => 'تاسې د دې جاوا سكريپټ مخ د سمولو اجازه نلرۍ.',
'myprivateinfoprotected' => 'تاسې د دې شخصي مالوماتو د سمولو اجازه نلرۍ.',
'mypreferencesprotected' => 'تاسې د خپلو غوره توبونو د سمولو اجازه نلرۍ.',
'ns-specialprotected' => 'ځانگړي مخونو کې سمون او بدلون نه شی راوستلای.',
'titleprotected' => 'د [[User:$1|$1]] لخوا د دې سرليک د جوړېدلو مخنيوی شوی.
او د دې کړنې سبب "\'\'$2\'\'" ورکړ شوی.',
'exception-nologin' => 'غونډال کې نه ياست ننوتي',

# Virus scanner
'virus-badscanner' => "بده سازېدنه: د ويروس ناڅرگنده ځيرڅار: ''$1''",
'virus-scanfailed' => 'ځيرڅارنه بريالۍ نه شوه (کوډ $1)',
'virus-unknownscanner' => 'ناڅرگند ضدويروس:',

# Login and logout pages
'logouttext' => "'''اوس تاسې د غونډال څخه ووتلئ.'''

دا په پام کې وساتۍ چې تر څو تاسې د خپل کتنمل حافظه نه وي سپينه کړې، نو ځينې مخونو کې به لا تر اوسه پورې په غونډال کې ننوتي ښکارۍ.",
'welcomeuser' => '$1، ښه راغلې!',
'welcomecreation-msg' => 'گڼون مو جوړ شو.
د [[Special:Preferences|{{SITENAME}} غوره توبونه]] بدلول مو مه هېروۍ.',
'yourname' => 'کارن-نوم:',
'userlogin-yourname' => 'کارن-نوم',
'userlogin-yourname-ph' => 'کارن-نوم مو وليکۍ',
'createacct-another-username-ph' => 'كارن نوم مو وركړۍ',
'yourpassword' => 'پټنوم:',
'userlogin-yourpassword' => 'پټنوم',
'userlogin-yourpassword-ph' => 'پټنوم مو وليکۍ',
'createacct-yourpassword-ph' => 'پټنوم مو وټاپۍ',
'yourpasswordagain' => 'پټنوم بيا وليکه',
'createacct-yourpasswordagain' => 'پټنوم مو تاييد کړۍ',
'createacct-yourpasswordagain-ph' => 'پټنوم مو بيا وټاپۍ',
'remembermypassword' => 'زما پټنوم په دې کمپيوټر (تر $1 {{PLURAL:$1|ورځې|ورځو}}) په ياد وساته!',
'userlogin-remembermypassword' => 'غونډال کې مې ننوتلی وساته',
'userlogin-signwithsecure' => 'خوندي اړيکتيا کارول',
'yourdomainname' => 'ستاسې شپول:',
'password-change-forbidden' => 'تاسې په دې ويکي باندې خپل پټنوم نه شی بدلولی.',
'login' => 'ننوتل',
'nav-login-createaccount' => 'ننوتل / گڼون جوړول',
'loginprompt' => 'ددې لپاره چې {{SITENAME}} کې ننوځۍ نو بايد ستاسې د کمپيوټر کوکيز چارن وي.',
'userlogin' => 'ننوتل / گڼون جوړول',
'userloginnocreate' => 'ننوتل',
'logout' => 'وتل',
'userlogout' => 'وتل',
'notloggedin' => 'غونډال کې نه ياست ننوتي',
'userlogin-noaccount' => 'گڼون نه لرې؟',
'userlogin-joinproject' => 'د {{SITENAME}} سره يوځای شه',
'nologin' => 'کارن-نوم نه لرې؟ $1.',
'nologinlink' => 'يو گڼون جوړول',
'createaccount' => 'گڼون جوړول',
'gotaccount' => 'آيا وار دمخې يو گڼون لری؟ $1.',
'gotaccountlink' => 'ننوتل',
'userlogin-resetlink' => 'د ننوتلو مالومات مو هېر شوي؟',
'userlogin-resetpassword-link' => 'پټنوم مو بياپرځايول',
'createacct-join' => 'خپل مالومات لاندې ورکړۍ',
'createacct-emailrequired' => 'برېښليک پته',
'createacct-emailoptional' => 'برېښليک پته (اختياري)',
'createacct-email-ph' => 'برېښليک پته مو وټاپۍ',
'createacct-another-email-ph' => 'برېښليک پته مو ورکړۍ',
'createaccountmail' => 'يو لنډمهاله ناټاکلی پټنوم کارول او په لاندې ورکړل شوې برېښليک پته کې ورلېږل',
'createacct-realname' => 'آر نوم (اختياري)',
'createaccountreason' => 'سبب:',
'createacct-reason' => 'سبب',
'createacct-reason-ph' => 'ولې تاسې بل گڼون جوړول غوااړۍ',
'createacct-captcha' => 'امنيتي تدبير',
'createacct-imgcaptcha-ph' => 'پورته تاسې ته ښکاره شوی متن وټاپۍ',
'createacct-submit' => 'گڼون مو جوړ کړۍ',
'createacct-another-submit' => 'بل گڼون جوړول',
'createacct-benefit-heading' => '{{SITENAME}} ستاسې په شان خلکو لخوا جوړ شوی.',
'createacct-benefit-body1' => '{{PLURAL:$1|سمون|سمونونه}}',
'createacct-benefit-body2' => '{{PLURAL:$1|مخ|مخونه}}',
'createacct-benefit-body3' => '{{PLURAL:$1|وروستنی ونډه وال|وروستني ونډه وال}}',
'badretype' => 'دا پټنوم چې تاسې ليکلی د مخکني پټنوم سره ورته نه دی.',
'userexists' => 'کوم کارن نوم چې تاسې ورکړی هغه بل چا کارولی.
لطفاً يو بل نوم وټاکۍ.',
'loginerror' => 'د ننوتنې ستونزه',
'createacct-error' => 'د گڼون جوړېدنې ستونزه',
'createaccounterror' => 'گڼون مو جوړ نه شو: $1',
'nocookiesnew' => 'ستاسې گڼون جوړ شو، خو تاسې لا غونډال ته نه ياست ورننوتلي.
{{SITENAME}} کې د ننوتلو لپاره کوکيز کارېږي.
او ستاسې د کتنمل کوکيز ناچارن دي.
لطفاً خپل د کتنمل کوکيز چارن کړۍ او بيا د خپل کارن-نوم او پټنوم په کارولو سره غونډال ته ورننوځی.',
'nocookieslogin' => '{{SITENAME}} کې د ننوتلو لپاره کوکيز کارېږي.
او ستاسې د کتنمل کوکيز ناچارن دي.
لطفاً خپل د کتنمل کوکيز چارن کړۍ او بيا د خپل کارن-نوم او پټنوم په کارولو سره غونډال ته ورننوځی.',
'noname' => 'تاسې تر اوسه پورې کوم کره کارن نوم نه دی ځانگړی کړی.',
'loginsuccesstitle' => 'غونډال کې بريالی ورننوتلۍ',
'loginsuccess' => "'''تاسې اوس {{SITENAME}} کې د \"\$1\" په نوم ننوتي ياست.'''",
'nosuchuser' => 'د "$1" په نوم هېڅ کارن نشته.
د کارنانو نومونه د غټو او واړو تورو سره حساس دي.
خپل حجا وڅارۍ، او يا هم [[Special:UserLogin/signup|يو نوی گڼون جوړ کړی]].',
'nosuchusershort' => 'د "$1" په نوم هېڅ کوم گڼون نشته. لطفاً خپل د نوم ليکلې بڼې ته ځير شی چې پکې تېروتنه نه وي.',
'nouserspecified' => 'تاسې ځان ته کوم کارن نوم نه دی ځانگړی کړی.',
'login-userblocked' => 'په دې کارن بنديز لگېدلی. غونډال کې ننوتلو ته پرې نه ښودلی شو.',
'wrongpassword' => 'ناسم پټنوم مو ليکلی. لطفاً يو ځل بيا يې وليکۍ.',
'wrongpasswordempty' => 'تاسې پټنوم نه دی ليکلی. لطفاً سر له نوي يې وليکۍ.',
'passwordtooshort' => 'بايد چې پټنوم مو لږ تر لږه {{PLURAL:$1|1 توری|$1 توري}} وي.',
'password-name-match' => 'ستاسې پټنوم بايد ستاسې د کارن-نوم سره توپير ولري.',
'password-login-forbidden' => 'د دې کارن-نوم او پټنوم په کارېدنې بنديز دی.',
'mailmypassword' => 'نوی پټنوم برېښليک کول',
'passwordremindertitle' => 'د {{SITENAME}} لپاره نوی لنډمهاله پټنوم',
'passwordremindertext' => 'يو چا (کېدای شي چې تاسې پخپله، د $1 IP پتې نه)
د {{SITENAME}} ($4) وېبځي لپاره د يوه نوي پټنوم د ورلېږلو غوښتنه کړې.
دم مهال د "$2" کارن لپاره يو نوی لنډمهاله پټنوم "$3" دی.
که چېرته همدا غوښتنه ستاسې لخوا شوي وي، نو تاسې غونډال ته په همدې پټنوم ورننوځی او بيا خپل نوی پټنوم په خپله خوښه وټاکۍ.
ستاسې لنډمهاله پټنوم په {{PLURAL:$5|يوه ورځ|$5 ورځو}} کې بې اعتباره کېدونکی دی.

که چېرته تاسې نه پرته کوم بل چا دغه غوښتنه کړې وي او يا هم تاسې ته خپل پټنوم در پزړه شوی وي او تاسې خپل اصلي پټنوم بدلول نه غواړۍ، نو تاسې همدا پيغام بابېزه وګڼۍ او د پخوا په څېر خپل اصلي پټنوم وکاروی.',
'noemail' => 'د "$1" کارن لپاره هېڅ کومه برېښليک پته نه ده ثبته شوې.',
'noemailcreate' => 'تاسې ته پکار ده چې يوه سمه برېښليک پته وليکۍ',
'passwordsent' => 'د "$1" لپاره يو نوی پټنوم د اړونده کارن برېښليک پتې ته ولېږل شو.
لطفاً کله چې پټنوم مو ترلاسه کړ نو بيا غونډال ته ننوځۍ.',
'blocked-mailpassword' => 'ستاسې په IP پتې بنديز لگېدلی او تاسې نه شی کولای چې ليکنې وکړی، په همدې توگه تاسې نه شی کولای چې د پټنوم د پرځای کولو کړنې وکاروی دا ددې لپاره چې د وراني مخنيوی وشي.',
'eauthentsent' => 'ستاسې ورکړ شوې برېښليک پتې ته مو يو تاييدي برېښليک درولېږه.
تر دې دمخه چې ستاسې گڼون ته کوم بل برېښليک درولېږو، پکار ده چې تاسې په برېښليک کې درلېږل شوې لارښوونې پلي کړی او ددې پخلی وکړی چې همدا گڼون په رښتيا ستاسې خپل دی.',
'mailerror' => 'د برېښليک د لېږلو ستونزه: $1',
'acct_creation_throttle_hit' => 'د همدې ويکي کارنانو په وروستيو ورځو کې ستاسې د IP پتې په کارولو سره {{PLURAL:$1|1 گڼون|$1 گڼونونه}} جوړ کړي، چې دا په همدې مودې کې د گڼونونو د جوړولو تر ټولو ډېر شمېر دی چې اجازه يې ورکړ شوې.
نو په همدې خاطر د اوس لپاره د همدې IP پتې کارنان نه شي کولای چې نور گڼونونه جوړ کړي.',
'emailauthenticated' => 'ستاسې برېښليک پته په $2 نېټه په $3 بجو د منلو وړ وگرځېده.',
'emailnotauthenticated' => 'لا تر اوسه ستاسې برېښليک پته د منلو وړ نه ده ګرځېدلې. د لاندې ځانګړتياو لپاره به تاسې ته هېڅ کوم برېښليک و نه لېږل شي.',
'noemailprefs' => 'ددې لپاره چې دا کړنې کار وکړي نو تاسو يو برېښليک وټاکۍ.',
'emailconfirmlink' => 'د خپل د برېښليک پتې پخلی وکړی',
'invalidemailaddress' => 'دا برېښليک پته نه منل کېږي، دا ځکه چې دا پته يوه ناکره بڼه لري.
لطفاً د يوې کره بڼې پته وليکۍ او يا هم دا ځای تش پرېږدۍ.',
'cannotchangeemail' => 'پدې ويکي کې د گڼون برېښليک پتې نشي بدلېدلی.',
'emaildisabled' => 'دا وېبځی د برېښليک لېږلو چارو څخه برخمن نه دی.',
'accountcreated' => 'گڼون مو جوړ شو.',
'accountcreatedtext' => 'د [[{{ns:User}}:$1|$1]] ([[{{ns:User talk}}:$1|خبرې اترې]]) لپاره يو گڼون جوړ شو.',
'createaccount-title' => 'د {{SITENAME}} د گڼون جوړېدنه',
'createaccount-text' => 'يو چا د {{SITENAME}} په وېبځي ($4) کې ستاسې د برېښليک پتې لپاره د "$2" په نامه يو گڼون جوړ کړی چې پټنوم يې "$3" دی.
تاسې بايد غونډال ته ورننوځۍ او همدا اوس خپل پټنوم بدل کړی.

که چېرته دا کړنه په تېروتنه کې شوی وي نو تاسې کولای شی چې دا پيغام بابېزه وگڼۍ.',
'usernamehasherror' => 'کارن-نوم نشي کېدلای چې کرښکې لوښې ولري',
'login-throttled' => 'تاسې څو واره هڅه کړې چې غونډال ته ورننوځۍ.
لطفاً د بيا هڅې نه مخکې يو څو شېبې تم شۍ.',
'login-abort-generic' => 'غونډال کې مو ننوتل نابريالی شو - ناڅاپي بند شو',
'loginlanguagelabel' => 'ژبه: $1',

# Email sending
'user-mail-no-addy' => 'د يوې برېښليک پتې پرته د برېښليک لېږلو هڅه شوې.',

# Change password dialog
'resetpass' => 'پټنوم بدلول',
'resetpass_header' => 'د گڼون پټنوم بدلول',
'oldpassword' => 'زوړ پټنوم:',
'newpassword' => 'نوی پټنوم:',
'retypenew' => 'نوی پټنوم بيا وليکه:',
'resetpass_submit' => 'پټنوم مو وټاکۍ او بيا غونډال ته ورننوځۍ',
'changepassword-success' => 'ستاسې پټنوم په برياليتوب سره بدل شو!
اوس غونډال کې د ورننوتلو په حال کې يو ...',
'resetpass_forbidden' => 'پټنومونه مو نه شي بدلېدلای',
'resetpass-no-info' => 'دې مخ ته د لاسرسي لپاره بايد غونډال کې ورننوځۍ.',
'resetpass-submit-loggedin' => 'پټنوم بدلول',
'resetpass-submit-cancel' => 'ناگارل',
'resetpass-wrong-oldpass' => 'لنډمهال او يا هم اوسنی پټنوم مو ناسم دی',
'resetpass-temp-password' => 'لنډمهالی پټنوم:',

# Special:PasswordReset
'passwordreset' => 'پټنوم بياپرځايول',
'passwordreset-legend' => 'پټنوم بياپرځايول',
'passwordreset-disabled' => 'په دې ويکي پټنوم بياپرځای کولو کړنه ناچارنه شوې.',
'passwordreset-username' => 'کارن-نوم:',
'passwordreset-domain' => 'شپول:',
'passwordreset-capture' => 'د پايلې برېښليک کتل غواړې؟',
'passwordreset-email' => 'برېښليک پته:',
'passwordreset-emailtitle' => 'د {{SITENAME}} د گڼون څرگندنې',
'passwordreset-emailelement' => 'کارن-نوم: $1
لنډمهاله پټنوم: $2',
'passwordreset-emailsent' => 'د پټنوم بيا پرځای کېدنې لپاره برېښليک درولېږل شو.',
'passwordreset-emailsent-capture' => 'د پټنوم بياپرځای کېدنې لپار مو يو برېښليک درولېږه، برېښليک په لاندې توگه ښودل شوی.',

# Special:ChangeEmail
'changeemail' => 'برېښليک پته بدلول',
'changeemail-header' => 'د گڼون برېښليک پته بدلول',
'changeemail-no-info' => 'دې مخ ته د لاسرسي لپاره بايد غونډال کې ورننوځۍ.',
'changeemail-oldemail' => 'اوسنۍ برېښليک پته:',
'changeemail-newemail' => 'نوې برېښليک پته:',
'changeemail-none' => '(هېڅ)',
'changeemail-password' => 'ستاسې د{{SITENAME}} پټنوم:',
'changeemail-submit' => 'برېښليک بدلول',
'changeemail-cancel' => 'ناگارل',

# Edit page toolbar
'bold_sample' => 'زغرد متن',
'bold_tip' => 'زغرد متن',
'italic_sample' => 'رېوند متن',
'italic_tip' => 'رېوند متن',
'link_sample' => 'د تړن سرليک',
'link_tip' => 'کورنۍ تړنه',
'extlink_sample' => 'http://www.example.com د تړنې سرليک',
'extlink_tip' => 'باندنۍ تړنې (د http:// مختاړی مه هېروی)',
'headline_sample' => 'د سرليک متن',
'headline_tip' => 'د ۲ کچې سرليک',
'nowiki_sample' => 'دلته دې بې بڼې متن ځای پر ځای شي',
'nowiki_tip' => 'د ويکي بڼه نيونه بابېزه گڼل',
'image_tip' => 'خښه شوې دوتنه',
'media_tip' => 'د دوتنې تړنه',
'sig_tip' => 'ستاسې لاسليک د وخت د ټاپې سره',
'hr_tip' => 'څنډيزه ليکه (ددې په کارولو کې سپما وکړۍ)',

# Edit pages
'summary' => 'لنډيز:',
'subject' => 'سکالو/سرليک:',
'minoredit' => 'دا يوه وړه سمونه ده',
'watchthis' => 'همدا مخ کتل',
'savearticle' => 'مخ خوندي کول',
'preview' => 'مخليدنه',
'showpreview' => 'مخليدنه',
'showlivepreview' => 'ژوندۍ مخکتنه',
'showdiff' => 'بدلونونه ښکاره کول',
'anoneditwarning' => "'''يادونه:''' تاسې غونډال ته نه ياست ننوتي. ستاسې IP پته به د دې مخ د سمونونو په پېښليک کې ثبت شي.",
'anonpreviewwarning' => "''تاسې غونډال ته نه ياست ننوتي. خوندي کولو سره به ستاسې IP پته به د دې مخ د سمونونو په پېښليک کې ثبت شي.''",
'missingcommenttext' => 'لطفاً تبصره لاندې وليکۍ.',
'summary-preview' => 'د لنډيز مخليدنه:',
'subject-preview' => 'موضوع/سرليک مخکتنه:',
'blockedtitle' => 'پر کارن بنديز لگېدلی',
'blockedtext' => "'''ستاسې د کارن-نوم يا آی پي پتې مخنيوی شوی.'''

همدا بنديز د $1 له خوا پر تاسې لږېدلی. او د همدې کړنې سبب ''$2'' دی.

* د بنديز د پېل نېټه: $8
* د بنديز د پای نېټه: $6
* بنديزونه دي پر: $7

تاسې کولای شی چې د $1 او يا هم د يو بل [[{{MediaWiki:Grouppage-sysop}}|پازوال]] سره اړيکې ټينگې کړی او د بنديز ستونزې مو هوارې کړی.
تاسې نه شی کولای چې د 'کارن ته برېښلک لېږل' کړنې نه گټه پورته کړی تر څو چې تاسې د خپل گڼون په [[Special:Preferences|غوره توبونو]] کې يوه کره برېښليک پته نه وي ځانگړې کړې او تر دې بريده چې پر تاسې د هغې د کارولو بنديز نه وي لگېدلی.
ستاسې د دم مهال آی پي پته $3 ده، او ستاسې د بنديز پېژند #$5 دی. مهرباني وکړۍ د خپلې يادونې پر مهال د دغو دوو څخه د يوه او يا هم د دواړو ورکول مه هېروۍ.",
'autoblockedtext' => 'په خپلکاريزه توگه ستاسې پر IP پتې بنديز لگېدلی، دا د دې په خاطر چې ستاسې پته د بل چا له خوا چې $1 پرې بنديز لگولی، کارېدلې.
او د بنديز سبب يې دا دی:

:\'\'$2\'\'

* د بنديز د پيل نېټه: $8
* د بنديز د پای نېټه: $6
* د بنديز د موخې سړی: $7

تاسې کولای شی چې د $1 سره او يا هم د [[{{MediaWiki:Grouppage-sysop}}|پازوالانو]]  له ډلې نه يو چا سره اړيکې ټينگې کړی او د بنديز په اړه مو ورسره خبرې وکړۍ.

دا مه هېروۍ چې تاسې د "کارن ته برېښليک لېږل" له اسانتياوؤ نه ګټه نه شی اخيستلای تر څو چې ستاسې د نومليکنې په وخت کې يا [[Special:Preferences|ستاسې د غوره توبونو په امستنو]] کې يوه کره برېښليک پته نه وي ځانگړې شوې، او يا هم د برېښليک لېږلو د چارو په کارولو مو بنديز نه وي لگېدلی.

ستاسې IP پته $3 ده او ستاسې د بنديز پېژند #$5 دی.
د بنديز اړونده د اړيکو نيولو په وخت کې لطفاً د پورتني مالوماتو يادونه وکړۍ.',
'blockednoreason' => 'هېڅ سبب نه دی ورکړ شوی',
'whitelistedittext' => 'د مخونو د سمون لپاره بايد $1 کېښکاږۍ.',
'nosuchsectiontitle' => 'برخه و نه موندل شوه',
'nosuchsectiontext' => 'تاسې د يوې داسې برخې د سمون هڅه کړې چې تر اوسه پورې نشته.
کېدای هغه مهال چې تاسې د دې مخ نه کتنه کوله، همدا برخه کوم بل ځای ته لېږدل شوې او يا هم ړنګه شوې وي.',
'loginreqtitle' => 'لومړی غونډال ته ورننوځۍ',
'loginreqlink' => 'ننوتل',
'loginreqpagetext' => 'د نورو مخونو د کتلو لپاره تاسو بايد $1 وکړۍ.',
'accmailtitle' => 'پټنوم ولېږل شو.',
'newarticle' => '(نوی)',
'newarticletext' => "تاسې د يوې داسې تړنې څارنه کړې چې لا تر اوسه پورې نه شته.
که همدا مخ ليکل غواړۍ، نو په لانديني چوکاټ کې خپل متن وټاپۍ (د لا نورو مالوماتو لپاره د [[{{MediaWiki:Helppage}}|لارښود مخ]] وگورۍ).
که چېرته تاسې دلته په تېروتنه راغلي ياست، نو يواځې د خپل د کتنمل '''مخ پر شا''' تڼۍ مو وټوکۍ.",
'anontalkpagetext' => "----''دا د يوه ورکنومي کارن چې کارن-نوم نه لري او يا خپل کارن-نوم نه کاروي، د سکالو يوه پاڼه ده. نو د يوه کس د پېژندلو پخاطر موږ د هماغه کارن د انټرنېټ شمېره يا IP پته دلته ثبتوؤ. داسې يوه IP پته د ډېرو کارنانو لخوا هم کارېدلی شي. که تاسې يو ورکنومی کارن ياست او تاسې ته دا څرگندېږي چې تاسې ته نااړونده پېغامونه او تبصرې اشاره شوي، نو د نورو بې نومو کارنانو او ستاسې ترمېنځ د ټکنتوب د مخ نيونې لپاره لطفاً [[Special:UserLogin/signup|يو گڼون جوړ کړۍ]] او يا هم [[Special:UserLogin|غونډال ته ورننوځۍ]].''",
'noarticletext' => 'دم مهال په دې مخ کې څه نشته.
تاسې کولای شی چې په نورو مخونو کې [[Special:Search/{{PAGENAME}}|د دې مخ د سرليک پلټنه]] يا
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} د اړوندو يادښتونو پلټنه] وکړی.
او يا [{{fullurl:{{FULLPAGENAME}}|action=edit}} همدا مخ سم کړی]</span>.',
'noarticletext-nopermission' => 'دم مهال په دې مخ کې متن نشته.
تاسې کولای شی چې [[Special:Search/{{PAGENAME}}|همدا سرليک په نورو مخونو کې وپلټۍ]], يا هم <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} اړونده يادښتونه وپلټۍ]</span>، خو تاسې د دې مخ د جوړولو اجازه نه لرۍ.',
'userpage-userdoesnotexist' => 'د "<nowiki>$1</nowiki>" گڼون نه دی ثبت شوی.
لطفاً ځان ډاډه کړۍ چې آيا تاسې په رښتيا همدا مخ جوړول که سمول غواړۍ.',
'userpage-userdoesnotexist-view' => 'د "$1" گڼون نه دی ثبت شوی.',
'blocked-notice-logextract' => 'دم مهال په دې کارن بنديز لگېدلی.
د بنديز يادښت تازه مالومات په لاندې توگه دي:',
'clearyourcache' => "'''يادښت:''' د غوره توبونو د خوندي کولو وروسته، خپل د کتنمل (بروزر) ساتل شوې حافظه تازه کړی.
* '''فايرفاکس/ سفري:''' په دې کتنمل کې د ''Reload'' د ټکوهلو په وخت د ''Shift'' تڼۍ نيولې وساتی، او يا هم ''Ctrl-F5'' يا ''Ctrl-R''تڼۍ کېښکاږۍ (په Apple Mac کمپيوټر باندې ''⌘-R'' کېښکاږۍ)
* '''گووگل کروم:''' په دې کتنمل کې د ''Ctrl-Shift-R'' تڼۍ کېښکاږۍ (د مک لپاره ''⌘-Shift-R'')
* '''انټرنټ اېکسپلورر:''' په دې کتنمل کې د ''Refresh'' د ټکوهلو په وخت کې د ''Ctrl'' تڼۍ کېښکاږلې ونيسۍ، او يا هم د ''Ctrl-F5'' تڼۍ کېښکاږۍ
* '''اوپرا''': په دې کتنمل کې د خپل براوزر ساتل شوې حافظه پدې توگه سپينولی شی ''Tools→Preferences''",
'usercsspreview' => "'''هېر مو نشي چې دا يوازې ستاسې د کارن CSS مخليدنه ده.'''
'''تر اوسه پورې لا ستاسې بدلونونه نه دي خوندي شوي!'''",
'userjspreview' => "'''هېر مو نشي چې دا يوازې ستاسې د کارن د جاوا سکرېپټ آزمېيل/مخليدنه ده.'''
'''تر اوسه پورې لا ستاسې بدلونونه نه دي خوندي شوي!'''",
'sitecsspreview' => "'''په پام کې دې وي چې دا يوازې ستاسې د CSS مخليدنه ده.'''
'''تر اوسه پورې لا ستاسې بدلونونه نه دي خوندي شوي!'''",
'sitejspreview' => "'''په پام کې مو اوسه چې تاسې يوازې د دغه جاواسکرېپټ کوډ مخليدنه کوۍ.'''
'''تر اوسه پورې دا نه دی خوندي شوی!'''",
'updated' => '(تازه)',
'note' => "'''يادونه:'''",
'previewnote' => "'''هېر مو نه شي چې دا يواځې يوه مخليدنه ده.'''
ستاسې لخوا ترسره شوي بدلونونه لا تر اوسه پورې نه دي خوندي شوي!!",
'continue-editing' => 'د سمولو سيمې ته ورتلل',
'editing' => 'د $1 سمونه',
'creating' => '$1 جوړېدنې کې دی',
'editingsection' => '$1 (برخه) په سمېدنې کې دی',
'editingcomment' => 'د $1 سمون (نوې برخه)',
'editconflict' => 'په سمادولو کې خنډ: $1',
'yourtext' => 'ستاسې متن',
'storedversion' => 'زېرمه شوې مخکتنه',
'yourdiff' => 'توپيرونه',
'copyrightwarning' => "لطفاً په پام کې وساتۍ چې ټولې هغه ونډې چې تاسې يې {{SITENAME}} کې ترسره کوی هغه د $2 له مخې د خپرولو لپاره گڼل کېږي (د لانورو تفصيلاتو لپاره $1 وگورۍ). که تاسې نه غواړۍ چې په ليکنو کې مو په بې رحمۍ سره لاسوهنې (سمونې) وشي او د نورو په غوښتنه پسې لانورې وغځېږي او يا هم خپرې شي، نو دلته يې مه ځای پر ځای کوی..<br />
تاسې زموږ سره دا ژمنه هم کوئ چې تاسې پخپله دا ليکنه کښلې، او يا مو د ټولگړو پاڼو او يا ورته وړيا سرچينو څخه لمېسلې ده '''لطفاً د ليکوال د اجازې څخه پرته د خوندي رښتو ليکنې مه خپروی!'''",
'longpageerror' => "'''تېروتنه: کوم متن چې مو ليکلی {{PLURAL:$1|يو کيلوبايټه|$1 کيلوبايټه}} اوږد دی، چې دا پخپله د حد اکثر نه {{PLURAL:$2|يو کيلوبايټه|$2 کيلوبايټه}} اوږد دی.'''
ستاسې متن نه شي خوندي کېدلای.",
'protectedpagewarning' => "'''گواښنه: همدا مخ تړل شوی او يوازې هغه کارنان په دې مخ کې بدلونونه راوستلای شي چې د پازوالۍ د آسانتياوو نه برخمن دي.'''
ستاسې د مالوماتو لپاره د وروستني يادښت متن دلته په دې توگه راوړل شوی:",
'semiprotectedpagewarning' => "'''پاملرنه:''' دا مخ تړل شوی او يواځې ثبت شوي کارنان کولای شي چې په دې مخ کې بدلونونه راولي.
ستاسې د مالوماتو لپاره د وروستني يادښت متن دلته په دې توگه راوړل شوی:",
'cascadeprotectedwarning' => "'''گواښنه:''' همدا مخ تړل شوی دی او يوازې هغه کارنان په دې مخ کې بدلونونه راوستلای شي چې د پازوالۍ د آسانتياوو نه برخمن دي، دا په دې خاطر چې همدا مخ د {{PLURAL:$1|لانديني مخ|لاندينيو مخونو}} په ځوړاوبيزې ژغورنې کې ورگډ دی:",
'titleprotectedwarning' => "'''گواښنه: همدا مخ تړل شوی دی او د دې د جوړولو لپاره تاسې ته د [[Special:ListGroupRights|ځانگړو رښتو]] د ترلاسه کولو اړتيا ده.'''
ستاسې د مالوماتو لپاره د وروستني يادښت متن دلته په دې توگه راوړل شوی:",
'templatesused' => 'په دې مخ کارېدلې {{PLURAL:$1|کينډۍ|کينډۍ}}:',
'templatesusedpreview' => 'يه دې مخليدنه کارېدلې {{PLURAL:$1|کينډۍ|کينډۍ}}:',
'templatesusedsection' => 'په دې برخه کې کارېدلي {{PLURAL:$1|کينډۍ|کينډۍ}}:',
'template-protected' => '(ژغورلی)',
'template-semiprotected' => '(نيم-ژغورلی)',
'hiddencategories' => 'دا مخ د {{PLURAL:$1|1 پټې وېشنيزې|$1 پټو وېشنيزو}} يو غړی دی:',
'nocreatetext' => '{{SITENAME}} د نوو مخونو د جوړولو وړتيا محدوده کړې.
تاسو بېرته پر شا تللای شی او په شته مخونو کې سمونې ترسره کولای شی، او يا هم [[Special:UserLogin|غونډال ته ننوتلای او يو گڼون جوړولای شی]].',
'nocreate-loggedin' => 'تاسې د نوو مخونو د جوړولو پرېښله نلرۍ.',
'sectioneditnotsupported-title' => 'د برخې د سمون ملاتړ نه کېږي',
'sectioneditnotsupported-text' => 'په دې مخ د برخې د سمون ملاتړ نه کېږي.',
'permissionserrors' => 'د پرېښې تېروتنه',
'permissionserrorstext' => 'تاسې د لاندې {{PLURAL:$1|سبب|سببونو}} پخاطر د دې کړنې اجازه نه لرۍ:',
'permissionserrorstext-withaction' => 'تاسې د $2 اجازه نه لری، دا د {{PLURAL:$1|دغه سبب|دغو سببونو}} پخاطر:',
'recreate-moveddeleted-warn' => "'''گواښنه: تاسې د يوه داسې مخ بياجوړونه کوۍ کوم چې يو ځل پخوا ړنگ شوی وو.'''

پکار ده چې تاسې په دې ځان پوه کړۍ چې ايا دا تاسې ته وړ ده چې د همدې مخ جوړول په پرله پسې توگه وکړۍ.
ستاسې د اسانتياوو لپاره د همدې مخ د ړنگېدلو يادښت هم ورکړ شوی:",
'moveddeleted-notice' => 'دا مخ ړنگ شوی.
دلته لاندې د دې مخ د ړنگېدنې او لېږدېدنې يادښت د سرچينې په توگه ورکړ شوی.',
'log-fulllog' => 'بشپړ يادښت کتل',
'edit-gone-missing' => 'د دې مخ اوسمهالول و نه کړای شول.
داسې ښکاري چې دا مخ ړنگ شوی.',
'edit-conflict' => 'د سمولو خنډ',
'edit-no-change' => 'ستاسې سمون بابېزه وګڼل شو، دا ځکه چې تاسې په متن کې کوم بدلون نه دی راوستلی.',
'postedit-confirmation' => 'ستاسې سمون خوندي شو.',
'edit-already-exists' => 'په دې نوم يو نوی مخ جوړ نه شو.
پدې نوم د پخوا نه يو مخ شته.',
'defaultmessagetext' => 'تلواليزه پيغام متن',

# Content models
'content-model-wikitext' => 'ويکي متن',
'content-model-text' => 'ساده متن',
'content-model-javascript' => 'جاواسکرېپټ',
'content-model-css' => 'CSS',

# Parser/template warnings
'post-expand-template-inclusion-warning' => "'''گواښنه:''' دا کينډۍ د خپل ټاکلي بريد نه ډېره لويه ده.
ځينې کينډۍ به په کې گډې نه شي.",
'post-expand-template-inclusion-category' => 'هغه مخونه چې په کې د کارېدلو کينډيو شمېر له ټاکلې کچې ډېر دی',
'post-expand-template-argument-warning' => "'''گواښنه:''' دا مخ لږ تر لږه د يوې کينډۍ عاملين لري چې بې حده لوی دی.
دا عاملين ړنگ شول.",
'post-expand-template-argument-category' => 'هغه مخونه چې د کينډۍ ړنگ شوي عاملين لري.',

# "Undo" feature
'undo-norev' => 'دا سمون ناکړل کېدای نه شي دا ځکه چې دا سمون نشته او يا هم ړنگ شوی.',

# Account creation failure
'cantcreateaccounttitle' => 'گڼون نه شي جوړېدای',

# History pages
'viewpagelogs' => 'د دې مخ يادښتونه کتل',
'nohistory' => 'ددې مخ د سمون کوم پېښليک نه شته.',
'currentrev' => 'اوسنۍ بڼه',
'currentrev-asof' => 'د $1 پورې تازه بڼه',
'revisionasof' => 'د $1 بڼه',
'revision-info' => 'د $1 پورې شته مخليدنه، د $2 لخوا ترسره شوې',
'previousrevision' => '← زړه بڼه',
'nextrevision' => '← نوې بڼه',
'currentrevisionlink' => 'اوسنۍ بڼه',
'cur' => 'اوسنی',
'next' => 'راتلونکي',
'last' => 'وروستنی',
'page_first' => 'لومړنی',
'page_last' => 'وروستنی',
'histlegend' => 'د توپير ټاکنه: د هرې هغې بڼې پرتلنه چې تاسې غواړۍ نو د هماغې بڼې چوکاټک په نښه کړی او بيا په لاندينۍ تڼۍ وټوکۍ.<br />
لنډيز: (اوس) = د اوسنۍ بڼې سره توپير،
(وروست) = د وروستۍ بڼې سره توپير، و = وړه سمونه.',
'history-fieldset-title' => 'پېښليک سپړل',
'history-show-deleted' => 'يواځې ړنگ شوي',
'histfirst' => 'تر ټولو زاړه',
'histlast' => 'تر ټولو نوي',
'historysize' => '({{PLURAL:$1|1 بايټ|$1 بايټونه}})',
'historyempty' => '(تش)',

# Revision feed
'history-feed-title' => 'د مخکتنو پېښليک',
'history-feed-item-nocomment' => '$1 په $2',
'history-feed-empty' => 'ستاسې غوښتلی مخ نه شته.
کېدای شي چې دا له ويکي نه ړنگ شوی وي، او يا هم په بل نوم بدل شوی وي.
تاسې په دې ويکي د اړوندو نوؤ مخونو لپاره [[Special:Search|د پلټنې هڅه وکړۍ]].',

# Revision deletion
'rev-deleted-comment' => '(د سمون لنډيز لرې شو)',
'rev-deleted-user' => '(کارن-نوم ليري شوی)',
'rev-delundel' => 'ښکاره کول/ پټول',
'rev-showdeleted' => 'ښکاره کول',
'revisiondelete' => 'د ړنگولو/ناړنگولو مخکتنې',
'revdelete-nologtype-title' => 'د يادښت ډول نه دی ځانگړی شوی',
'revdelete-no-file' => 'ځانگړې شوې دوتنه نشته.',
'revdelete-show-file-submit' => 'هو',
'revdelete-selected' => "'''د [[:$1]] {{PLURAL:$2|ټاکلې بڼه|ټاکلې بڼې}}:'''",
'revdelete-legend' => 'د ښکارېدنې محدوديتونه ټاکل',
'revdelete-hide-text' => 'د مخکتنې متن پټول',
'revdelete-hide-image' => 'د دوتنې مېنځپانگه پټول',
'revdelete-hide-name' => 'کړنه او موخه پټول',
'revdelete-hide-comment' => 'د سمون لنډيز پټول',
'revdelete-hide-user' => 'د سمونگر کارن-نوم/آی پي پته پټول',
'revdelete-radio-same' => '(مه بدلوه)',
'revdelete-radio-set' => 'هو',
'revdelete-radio-unset' => 'نه',
'revdelete-log' => 'سبب:',
'revdel-restore' => 'ښکارېدنه بدلول',
'revdel-restore-deleted' => 'ړنګې شوې بڼې',
'revdel-restore-visible' => 'ښکاره بڼې',
'pagehist' => 'د مخ پېښليک',
'deletedhist' => 'د ړنگولو پېښليک',
'revdelete-reason-dropdown' => '*د ړنگولو ټولگړي سببونه
** د خپرېدو د رښتو سرغړونه
** ناسم شخصي مالومات
** پارونکي او بلواگر مالومات',
'revdelete-otherreason' => 'بل/اضافي سبب:',
'revdelete-reasonotherlist' => 'بل سبب',
'revdelete-edit-reasonlist' => 'د ړنگولو سببونه سمول',
'revdelete-offender' => 'د مخکتنې ليکوال:',

# History merging
'mergehistory' => 'د مخ پېښليکونه سره يوځای کول',
'mergehistory-from' => 'د سرچينې مخ:',
'mergehistory-into' => 'د موخې مخ:',
'mergehistory-submit' => 'بڼې سره يوځای کول',
'mergehistory-no-source' => 'د سرچينې مخ $1 نشته.',
'mergehistory-no-destination' => 'د $1 موخنيز مخ نشته.',
'mergehistory-invalid-source' => 'د سرچينې مخ بايد يو سم سرليک وي.',
'mergehistory-invalid-destination' => 'د موخې مخ بايد يو سم سرليک وي.',
'mergehistory-reason' => 'سبب:',

# Merge log
'revertmerge' => 'بېلول',

# Diffs
'history-title' => 'د "$1" د مخليدنې پېښليک',
'difference-title' => 'د "$1" د بڼو تر مېنځ توپير',
'difference-multipage' => '(د مخونو تر مېنځ توپير)',
'lineno' => '$1 کرښه:',
'compareselectedversions' => 'ټاکلې بڼې سره پرتلل',
'showhideselectedversions' => 'ټاکلې بڼې ښکاره کول/پټول',
'editundo' => 'ناکړ',
'diff-empty' => '(بې توپيره)',
'diff-multi' => ' د ({{PLURAL:$2| يو کارن|$2 کارنانو}} لخوا {{PLURAL:$1|يوه منځګړې بڼه|$1 منځګړې بڼې}}د  نه ده ښکاره شوې)',

# Search results
'searchresults' => 'د پلټنې پايلې',
'searchresults-title' => 'د "$1" د پلټنې پايلې',
'searchresulttext' => 'په {{SITENAME}} کې د لټون د نورو مالوماتو لپاره، [[{{MediaWiki:Helppage}}|{{int:لارښود}}]] وگورۍ.',
'searchsubtitle' => 'تاسې د \'\'\'[[:$1]]\'\'\' لپاره پلټنه کړې ([[Special:Prefixindex/$1|ټول هغه مخونه چې په "$1" پېلېږي]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|ټول هغه مخونه چې "$1" سره تړنې لري]])',
'searchsubtitleinvalid' => "تاسې د '''$1''' لټون کړی",
'titlematches' => 'د مخ سرليک ورسره ورته دی',
'notitlematches' => 'د هېڅ يوه مخ سرليک ورسره ورته نه دی',
'textmatches' => 'د مخ متن ورسره ورته دی',
'notextmatches' => 'د هېڅ کوم مخ متن ورسره سمون نه خوري',
'prevn' => 'تېر {{PLURAL:$1|$1}}',
'nextn' => 'راتلونکي {{PLURAL:$1|$1}}',
'prevn-title' => 'تېر $1 {{PLURAL:$1|پايله|پايلې}}',
'nextn-title' => 'راتلونکې $1 {{PLURAL:$1|پايله|پايلې}}',
'shown-title' => 'په هر مخ $1 {{PLURAL:$1|پايله|پايلې}} ښکاره کول',
'viewprevnext' => '($1 {{int:pipe-separator}} $2) ($3) کتل',
'searchmenu-legend' => 'د پلټلو خوښنې',
'searchmenu-exists' => "'''په دې ويکي يو مخ د \"[[:\$1]]\" په نامه دی'''",
'searchmenu-new' => "'''په دې ويکي د \"[[:\$1]]\" مخ جوړول!'''",
'searchprofile-articles' => 'مېنځپانگيز مخونه',
'searchprofile-project' => 'د لارښود او پروژې مخونه',
'searchprofile-images' => 'گڼرسنۍ',
'searchprofile-everything' => 'هرڅه',
'searchprofile-advanced' => 'پرمختللی',
'searchprofile-articles-tooltip' => 'په $1 کې پلټل',
'searchprofile-project-tooltip' => 'په $1 کې پلټل',
'searchprofile-images-tooltip' => 'د دوتنو پلټنه',
'searchprofile-everything-tooltip' => 'د ټولې مېنځپانگې پلټنه (د خبرو اترو مخونو سره)',
'searchprofile-advanced-tooltip' => 'د خپل خوښې په نوم-تشيالونو کې پلټل',
'search-result-size' => '$1 ({{PLURAL:$2|1 ويی|$2 وييونه}})',
'search-result-category-size' => '{{PLURAL:$1|1 غړی|$1 غړي}} ({{PLURAL:$2|1 څېرمه وېشنيزه|$2 څېرمه وېشنيزې}}، {{PLURAL:$3|1 دوتنه|$3 دوتنې}})',
'search-result-score' => 'اړوندتوب: $1%',
'search-redirect' => '(د $1 مخ ګرځونه)',
'search-section' => '(برخه $1)',
'search-suggest' => 'آيا همدا مو موخه وه: $1',
'search-interwiki-caption' => 'خورلڼې پروژې',
'search-interwiki-default' => '$1 پايلې:',
'search-interwiki-more' => '(نور)',
'search-relatedarticle' => 'اړونده',
'mwsuggest-disable' => 'د پلټنې وړانديزونه ناچارنول',
'searcheverything-enable' => 'په ټولو نوم-تشيالونو کې پلټل',
'searchrelated' => 'اړونده',
'searchall' => 'ټول',
'showingresults' => "دلته لاندې تر {{PLURAL:$1|'''1''' پايله|'''$1''' پايلې}} ښکاره شوي پيل له #'''$2''' شوی.",
'showingresultsheader' => "د «'''$4'''» لپاره {{PLURAL:$5|له '''$1''' نه تر '''$3''' پايله|له '''$1 نه تر $2''' پايلې، ټولې پايلې '''$3''' }}",
'nonefound' => "'''يادښت''': يوازې يو څو نوم-تشيالونو په تلواليزه توگه پلټل کېږي.
د ''ټول:'' مختاړي په کارولو سره به ستاسې د پلټنې لپاره، په ټوله مېنځپانگه کې پلټنه وشي (د خبرواترو، کينډۍ او نورو مخونو په گډون), او يا هم د خپلې خوښې نوم-تشيال د مختاړي په توگه وکاروۍ.",
'search-nonefound' => 'ستاسې دغوښتنې اړونده پايلې و نه موندل شوې.',
'powersearch' => 'ژوره پلټنه',
'powersearch-legend' => 'ژوره پلټنه',
'powersearch-ns' => 'په نوم-تشيالونو کې پلټنه:',
'powersearch-redir' => 'مخ گرځونې په لړليک کې اوډل',
'powersearch-field' => 'پلټنه د',
'powersearch-togglelabel' => 'نښه کول:',
'powersearch-toggleall' => 'ټول',
'powersearch-togglenone' => 'هېڅ',
'search-external' => 'باندنۍ پلټنه',

# Preferences page
'preferences' => 'غوره توبونه',
'mypreferences' => 'غوره توبونه',
'prefs-edits' => 'د سمونو شمېر:',
'prefsnologin' => 'غونډال کې نه ياست ننوتي',
'prefsnologintext' => 'د دې لپاره چې خپل غوره توبونه مو وټاکی، نو پکار ده چې لومړی تاسو غونډال کې <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} ننوځی]</span>.',
'changepassword' => 'پټنوم بدلول',
'prefs-skin' => 'پوښۍ',
'skin-preview' => 'مخکتنه',
'datedefault' => 'هېڅ نه ټاکل',
'prefs-beta' => 'د آزمېښتي بڼې ځانگړنې',
'prefs-datetime' => 'نېټه او وخت',
'prefs-labs' => 'د آزمېښتون ځانگړنې',
'prefs-user-pages' => 'کارن مخونه',
'prefs-personal' => 'د کارن پېژنليک',
'prefs-rc' => 'وروستي بدلونونه',
'prefs-watchlist' => 'کتنلړ',
'prefs-watchlist-days' => 'د ورځو شمېر چې په کتنلړ کې به ښکاري:',
'prefs-watchlist-days-max' => 'حد اکثر $1 {{PLURAL:$1|ورځ|ورځې}}',
'prefs-watchlist-edits-max' => 'د شمېر اکثر بريد: 1000',
'prefs-misc' => 'بېلابېل',
'prefs-resetpass' => 'پټنوم بدلول',
'prefs-changeemail' => 'برېښليک بدلول',
'prefs-setemail' => 'يوه برېښليک پته ورکړۍ',
'prefs-email' => 'د برېښليک خوښنې',
'prefs-rendering' => 'ښکارېدنه',
'saveprefs' => 'خوندي کول',
'resetprefs' => 'بيا سمول',
'restoreprefs' => 'ټولې تلواليزې امستنې پرځای کول',
'prefs-editing' => 'سمېدنې کې دی',
'rows' => 'ليکې:',
'columns' => 'ستنې:',
'searchresultshead' => 'پلټل',
'resultsperpage' => 'په هر مخ کې د پايلو شمېر:',
'stub-threshold-disabled' => 'ناچارن',
'recentchangesdays' => 'د هغو ورځو شمېر وټاکی چې په وروستي بدلونو کې يې ليدل غواړی:',
'recentchangesdays-max' => 'حد اکثر $1 {{PLURAL:$1|ورځ|ورځې}}',
'recentchangescount' => 'د هغو سمونو شمېر چې په تلواليزه بڼه ښکاره بايد شي:',
'prefs-help-recentchangescount' => 'پدې کې د وروستني بدلونونو، د مخونو د پېښليکونو او يادښتونه شامل دي.',
'savedprefs' => 'غوره توبونه مو خوندي شول.',
'timezonelegend' => 'د وخت سيمه:',
'localtime' => 'سيمه ايز وخت:',
'timezoneuseserverdefault' => 'د ويکي تلواليزه بڼه کارول ($1)',
'timezoneuseoffset' => 'بل (توپير ځانگړی کړی)',
'timezoneoffset' => 'توپير¹:',
'servertime' => 'د پالنگر وخت:',
'guesstimezone' => 'له کتنمل نه ډکول',
'timezoneregion-africa' => 'افريقا',
'timezoneregion-america' => 'امريکا',
'timezoneregion-antarctica' => 'انټارکټيکا',
'timezoneregion-arctic' => 'آرکټيک',
'timezoneregion-asia' => 'آسيا',
'timezoneregion-atlantic' => 'اطلس سمندر',
'timezoneregion-australia' => 'آسټراليا',
'timezoneregion-europe' => 'اروپا',
'timezoneregion-indian' => 'هندی سمندر',
'timezoneregion-pacific' => 'غلی سمندر',
'allowemail' => 'د نورو کارنانو لخوا د برېښليک رالېږل چارن کړه',
'prefs-searchoptions' => 'پلټنه',
'prefs-namespaces' => 'نوم-تشيالونه',
'defaultns' => 'او يا هم په دغو نوم-تشيالونو کې پلټل:',
'default' => 'تلواليز',
'prefs-files' => 'دوتنې',
'prefs-custom-css' => 'ځاني CSS',
'prefs-custom-js' => 'ځاني جاواسکرېپټ',
'prefs-common-css-js' => 'د ټولو پوښونو لپاره د CSS/جاواسکرېپټ دوتنه:',
'prefs-emailconfirm-label' => 'د برېښليک باورتيا:',
'youremail' => 'برېښليک *',
'username' => '{{GENDER:$1|کارن نوم}}:',
'uid' => '{{GENDER:$1|کارن}} پېژندنه:',
'prefs-memberingroups' => 'د {{PLURAL:$1|ډله|ډلې}} {{GENDER:$2|غړی}}:',
'prefs-registration' => 'د نومليکنې وخت:',
'yourrealname' => 'اصلي نوم:',
'yourlanguage' => 'ژبه:',
'yournick' => 'کورنی نوم:',
'badsiglength' => 'ستاسو لاسليک ډېر اوږد دی.
بايد چې لاسليک مو له $1 {{PLURAL:$1|توري|تورو}} نه لږ وي.',
'yourgender' => 'جنس:',
'gender-unknown' => 'ناڅرگنده',
'gender-male' => 'نارينه',
'gender-female' => 'ښځينه',
'email' => 'برېښليک',
'prefs-help-realname' => 'د آر نوم ليکل ستاسې په خوښه دی خو که تاسې خپل آر نوم وټاکۍ پدې سره به ستاسې ټول کارونه او ونډې ستاسې د نوم په اړوندولو کې وکارېږي.',
'prefs-help-email' => 'د برېښليک ورکړه ستاسې په خوښه ده، خو په ورکړې سره به يې د يوه نوي پټنوم د لېږلو چار آسانه کړي هغه هم کله چې تاسې نه خپل پټنوم هېر شوی وي.',
'prefs-help-email-others' => 'تاسې دا هم ټاکلی شی چې نور کارنان ستاسې د خبرو اترو او يا د کارن مخ يوې تړنې له لارې له تاسې سره برېښليکي اړيکه ونيسي.
د اړيکو ټينگولو په وخت کې به ستاسې برېښليک پته نورو کارنانو ته نه ښکاري.',
'prefs-help-email-required' => 'ستاسو د برېښليک پته پکار ده.',
'prefs-info' => 'بنسټيز مالومات',
'prefs-i18n' => 'نړېوالتوب',
'prefs-signature' => 'لاسليک',
'prefs-dateformat' => 'د نېټې بڼه',
'prefs-timeoffset' => 'د وخت واټن',
'prefs-advancedediting' => 'ټولگړی',
'prefs-editor' => 'سمونگر',
'prefs-preview' => 'مخليدنه',
'prefs-advancedrc' => 'پرمختللې خوښنې',
'prefs-advancedrendering' => 'پرمختللې خوښنې',
'prefs-advancedsearchoptions' => 'پرمختللې خوښنې',
'prefs-advancedwatchlist' => 'پرمختللې خوښنې',
'prefs-displayrc' => 'د ښکارېدنې خوښنې',
'prefs-displaysearchoptions' => 'د ښکارېدنې خوښنې',
'prefs-displaywatchlist' => 'د ښکارېدنې خوښنې',
'prefs-diffs' => 'توپيرونه',

# User preference: email validation using jQuery
'email-address-validity-valid' => 'برېښليک پته سمه ښکاري',
'email-address-validity-invalid' => 'يوه سمه برېښليک پته وليکۍ',

# User rights
'userrights' => 'د کارن رښتو سمبالښت',
'userrights-lookup-user' => 'کارن ډلې سمبالول',
'userrights-user-editname' => 'يو کارن نوم وليکۍ:',
'editusergroup' => 'کارن ډلې سمول',
'userrights-editusergroup' => 'کارن ډلې سمول',
'saveusergroups' => 'کارن ډلې خوندي کول',
'userrights-groupsmember' => 'غړی د:',
'userrights-groups-help' => 'تاسې هغه ډلې چې همدا کارن يې غړی دی بدلولی شی:
* يو په نښه شوی بکس د دې مانا لري چې کارن د هغې ډلې غړيتوب لري.
* يو نانښه شوی بکس د دې مانا لري چې کارن د هغې ډلې غړيتوب نلري.
* د * يوه نښه په دې مانا ده چې هر کله تاسې څوک په همدې ډلې کې غړی کړی بيا يې ترې نشی وېستلی او د دې برعکس هم.',
'userrights-reason' => 'سبب:',
'userrights-no-interwiki' => 'په همدې ويکي باندې تاسې د کارن رښتو د سمولو اجازه نه لرۍ.',
'userrights-changeable-col' => 'هغه ډلې چې تاسې يې بدلولی شی',
'userrights-unchangeable-col' => 'هغه ډلې چې تاسې يې نه شی بدلولی',

# Groups
'group' => 'ډله:',
'group-user' => 'کارنان',
'group-autoconfirmed' => 'تاييد شوي کارنان',
'group-bot' => 'روباټونه',
'group-sysop' => 'پازوالان',
'group-bureaucrat' => 'بيوروکراټان',
'group-suppress' => 'څارونکي',
'group-all' => '(ټول)',

'group-user-member' => '{{GENDER:$1|کارن}}',
'group-autoconfirmed-member' => '{{GENDER:$1|تاييد شوی کارن}}',
'group-bot-member' => '{{GENDER:$1|روباټ}}',
'group-sysop-member' => '{{GENDER:$1|پازوال}}',
'group-bureaucrat-member' => '{{GENDER:$1|بيوروکراټ}}',
'group-suppress-member' => '{{GENDER:$1|څارن}}',

'grouppage-user' => '{{ns:project}}:کارنان',
'grouppage-autoconfirmed' => '{{ns:project}}:تاييد شوي کارنان',
'grouppage-bot' => '{{ns:project}}:روباټان',
'grouppage-sysop' => '{{ns:project}}:پازوالان',
'grouppage-bureaucrat' => '{{ns:project}}:بيوروکراټان',
'grouppage-suppress' => '{{ns:project}}:څارن',

# Rights
'right-read' => 'مخونه لوستل',
'right-edit' => 'مخونه سمول',
'right-createpage' => 'مخونه جوړول (هغه چې د خبرو اترو مخونه نه دي)',
'right-createtalk' => 'د خبرو اترو مخونه جوړول',
'right-createaccount' => 'نوي کارن حسابونه جوړول',
'right-minoredit' => 'سمونونه واړه په نخښه کول',
'right-move' => 'مخونه لېږدول',
'right-move-subpages' => 'مخونه د خپلو څېرمه مخونو سره لېږدول',
'right-movefile' => 'دوتنې لېږدول',
'right-upload' => 'دوتنې پورته کول',
'right-upload_by_url' => 'د يو URL نه دوتنې پورته کول',
'right-writeapi' => 'د API کښنې کارېدنه',
'right-delete' => 'مخونه ړنگول',
'right-bigdelete' => 'د اوږدو پېښليکونو مخونه ړنگول',
'right-browsearchive' => 'ړنگ شوي مخونه پلټل',
'right-undelete' => 'يو مخ ناړنګول',
'right-suppressionlog' => 'شخصي يادښتونه کتل',
'right-block' => 'پر نورو کارنانو د سمون د آسانتياوؤ بنديز لگول',
'right-blockemail' => 'پر يوه کارن د برېښليک لېږلو بنديز لگول',
'right-hideuser' => 'پر يوه کارن-نوم بنديز لگول او له خلکو نه يې پټول',
'right-protect' => 'د ژغورنې کچه بدلول او ژغورلي مخونه سمول',
'right-editinterface' => 'د کارن ليدنمخ سمول',
'right-editusercssjs' => 'د نورو کارنانو د CSS او JS (جاوا سکرېپټ) دوتنې سمول',
'right-editusercss' => 'د نورو کارنانو د CSS دوتنې سمول',
'right-edituserjs' => 'د نورو کارنانو د JS (جاوا سکرېپټ) دوتنې سمول',
'right-unwatchedpages' => 'د ناکتلو مخونو يو لړليک کتل',
'right-userrights' => 'د کارن ټولې رښتې سمول',
'right-userrights-interwiki' => 'په نورو ويکي گانو د نورو کارنانو  کارن-رښتې سمول',
'right-sendemail' => 'نورو کارنانو ته برېښليک لېږل',

# Special:Log/newusers
'newuserlogpage' => 'د کارن-نوم د جوړېدو يادښت',
'newuserlogpagetext' => 'دا د کارن-نوم د جوړېدو يادښت دی',

# User rights log
'rightslog' => 'د کارن د رښتو يادښت',
'rightslogtext' => 'دا د کارن رښتو د بدلونونو يو يادښت دی',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'همدا مخ لوستل',
'action-edit' => 'دا مخ سمول',
'action-createpage' => 'مخونه جوړول',
'action-createtalk' => 'د خبرو اترو مخونه جوړول',
'action-createaccount' => 'دا گڼون جوړول',
'action-minoredit' => 'دا سمون وړوکی په نخښه کول',
'action-move' => 'همدا مخ لېږدول',
'action-movefile' => 'همدا دوتنه لېږدول',
'action-upload' => 'همدا دوتنه پورته کول',
'action-upload_by_url' => 'دا دوتنه له يوه URL نه پورته کول',
'action-writeapi' => 'د API کښنه کارول',
'action-delete' => 'همدا مخ ړنگول',
'action-deleterevision' => 'دا مخکتنه ړنگول',
'action-deletedhistory' => 'د دې مخ ړنگ شوی پېښليک کتل',
'action-browsearchive' => 'ړنگ مخونه پلټل',
'action-undelete' => 'همدا مخ ناړنګول',
'action-suppressionlog' => 'دا شخصي يادښت کتل',
'action-block' => 'پر دې کارن د سمون د آسانتياوؤ بنديز لگول',
'action-protect' => 'د دې مخ د ژغورنې کچه بدلول',
'action-unwatchedpages' => 'د ناکتلو مخونو لړليک کتل',
'action-mergehistory' => 'د دې مخ پېښليک سره اخږل',
'action-userrights' => 'د کارن ټولې رښتې سمول',
'action-userrights-interwiki' => 'په نورو ويکي گانو د کارنانو رښتې سمول',
'action-siteadmin' => 'توکبنسټ کولپول يا نه کولپول',
'action-sendemail' => 'برېښليکونه لېږل',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|بدلون|بدلونونه}}',
'recentchanges' => 'وروستي بدلونونه',
'recentchanges-legend' => 'د ورستي بدلونو خوښنې',
'recentchanges-summary' => 'په دې مخ د ويکي ترټولو تازه وروستي بدلونونه وڅارۍ.',
'recentchanges-feed-description' => 'همدلته د ويکي ترټولو تازه وروستي بدلونونه وڅارۍ او وگورۍ چې څه پېښ شوي.',
'recentchanges-label-newpage' => 'دغه سمون يو نوی مخ جوړ کړی',
'recentchanges-label-minor' => 'دا يوه وړه سمونه ده',
'recentchanges-label-bot' => 'دغه سمون د يو روباټ لخوا ترسره شوی',
'recentchanges-label-unpatrolled' => 'دغه سمون تر اوسه پورې نه دی څارل شوی',
'rcnote' => "دلته لاندې {{PLURAL:$1|وروستی '''1''' بدلون دی|وروستي '''$1''' بدلونونه دي}} چې په  {{PLURAL:$2| يوې ورځ|'''$2''' ورځو}} کې تر $4 نېټې او $5 بجو پېښ شوي.",
'rcnotefrom' => "په همدې ځای کې لاندې هغه بدلونونه دي چې د '''$2''' نه راپدېخوا پېښ شوي (تر '''$1''' پورې ښکاره شوي).",
'rclistfrom' => 'هغه نوي بدلونونه ښکاره کول چې له $1 نه پيلېږي',
'rcshowhideminor' => 'وړې سمونې $1',
'rcshowhidebots' => 'روباټ $1',
'rcshowhideliu' => 'غونډال کې ننوتي کارنان $1',
'rcshowhideanons' => 'بې نومه کارنان $1',
'rcshowhidepatr' => '$1 څارلې سمونې',
'rcshowhidemine' => 'زما سمونې $1',
'rclinks' => 'هغه وروستي $1 بدلونونه ښکاره کړی چې په $2 ورځو کې پېښ شوي<br />$3',
'diff' => 'توپير',
'hist' => 'پېښليک',
'hide' => 'پټول',
'show' => 'ښکاره کول',
'minoreditletter' => 'و',
'newpageletter' => 'نوی',
'boteditletter' => 'روباټ',
'number_of_watching_users_pageview' => '[$1  {{PLURAL:$1|کارن|کارنان}} يې ګوري]',
'rc_categories_any' => 'هر يو',
'rc-change-size-new' => '$1 {{PLURAL:$1|بايټ|بايټونه}} د بدلون وروسته',
'newsectionsummary' => '/* $1 */ نوې برخه',
'rc-enhanced-expand' => 'تفصيل ښکاره کول',
'rc-enhanced-hide' => 'تفصيل پټول',
'rc-old-title' => 'اصلاً د "$1" په توگه جوړ شو',

# Recent changes linked
'recentchangeslinked' => 'اړونده بدلونونه',
'recentchangeslinked-feed' => 'اړونده بدلونونه',
'recentchangeslinked-toolbox' => 'اړونده بدلونونه',
'recentchangeslinked-title' => '"$1" ته اړونده بدلونونه',
'recentchangeslinked-summary' => "دا د هغه بدلونونو لړليک دی چې وروستۍ ځل په تړن لرونکيو مخونو کې د يوه ځانگړي مخ (او يا هم د يوې ځانگړې وېشنيزې غړو) نه رامېنځ ته شوي.
[[Special:Watchlist|ستاسې د کتنلړ]] مخونه په '''زغرد ليک''' کې ښکاري.",
'recentchangeslinked-page' => 'د مخ نوم:',
'recentchangeslinked-to' => 'د ورکړل شوي مخ پر ځای د اړونده تړلي مخونو بدلونونه ښکاره کول',

# Upload
'upload' => 'دوتنه پورته کول',
'uploadbtn' => 'دوتنه پورته کول',
'reuploaddesc' => 'پورته کېدنه ناگارل او بېرته د پورته کېدنې فورمې ته ورگرځېدل',
'upload-tryagain' => 'د بدلون موندلې دوتنې څرگندونې سپارل',
'uploadnologin' => 'غونډال کې نه ياست ننوتي',
'uploadnologintext' => 'د دوتنې پورته کولو لپاره بايد $1',
'uploaderror' => 'د پورته کولو ستونزه',
'uploadtext' => "د دوتنې د پورته کېدو لپاره لاندينی چوکاټ وکاروۍ.
که چېرته د پخونيو پورته شويو دوتنو کتل او پلټل غواړۍ نو [[Special:FileList|د پورته شويو دوتنو لړليک]] ته ورشۍ، [[Special:Log/upload|د (بيا) پورته شويو دوتنو يادښتونه]] او [[Special:Log/delete|د ړنگېدو يادښتونه]] هم کتلای شی.

ددې لپاره چې يوه مخ ته انځور ورواچوی، نو بيا پدې ډول تړنې (لېنک) وکاروی
* د يوې دوتنې د بشپړې بڼې د کارولو په موخه د '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></code>''' کوډ وکاروۍ.
* د '۲۰۰ پېکسل' په کچه د 'بټنوک' په توگه د يوې دوتنې کارول چې د مخ کيڼې څنډې کې او ترلاندې 'د انځور څرگندونې' ولري، نو د دې موخې لپاره د '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|بټنوک|کيڼ|د انځور څرگندونې]]</nowiki></code>''' کوډ وکاروۍ.
* د انځور د ښودلو نه پرته، د دوتنې سره د سيخې تړنې لپاره د '''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></code>''' کوډ وکاروۍ.",
'upload-permitted' => 'د پرېښودلو دوتنو ډولونه: $1.',
'upload-preferred' => 'د غوره دوتنو ډولونه: $1.',
'upload-prohibited' => 'د منع شويو دوتنو ډولونه: $1.',
'uploadlog' => 'د پورته شويو دوتنو يادښت',
'uploadlogpage' => 'د پورته شويو دوتنو يادښت',
'uploadlogpagetext' => 'دا لاندې د نوو پورته شوو دوتنو لړليک دی.',
'filename' => 'د دوتنې نوم',
'filedesc' => 'لنډيز',
'fileuploadsummary' => 'لنډيز:',
'filereuploadsummary' => 'د دوتنې بدلونونه:',
'filestatus' => 'د رښتو دريځ:',
'filesource' => 'سرچينه:',
'uploadedfiles' => 'پورته شوې دوتنې',
'ignorewarning' => 'گواښنه بې پامه گڼل او دوتنه خوندي کول',
'ignorewarnings' => 'هر ډول ګواښونه له پامه غورځول',
'minlength1' => 'پکار ده چې د دوتنو نومونه لږ تر لږه يو حرف ولري.',
'illegalfilename' => 'د دوتنې نوم "$1" په داسې تورو ليکلی دی چې د یو مخ د سرليک په توگه يې پرېښه نه ده شوې.
مهرباني وکړۍ د دوتنې نوم مو بدل کړۍ او بيا مو د دوتنې د پورته کولو هڅه وکړۍ.',
'badfilename' => 'ددغې دوتنې نوم "$1" ته واوړېده.',
'filetype-badmime' => 'د MIME بڼې "$1" د دوتنو د پورته کولو اجازه نشته.',
'empty-file' => 'کومه دوتنه چې تاسې دلته سپارلې هغه تشه ده.',
'file-too-large' => 'کومه دوتنه چې تاسې دلته سپارلې ډېره لويه ده.',
'filename-tooshort' => 'د دوتنې نوم ډېر لنډ دی',
'filetype-banned' => 'په دې ډول دوتنې بنديز دی.',
'illegal-filename' => 'د دوتنې نوم نه دی پرېښل شوی.',
'unknown-error' => 'يوه ناڅرګنده تېروتنه رامېنځته شوه.',
'tmp-create-error' => 'لنډمهاله دوتنه جوړېدای نه شي',
'fileexists' => 'د پخوا نه پدې نوم يوه دوتنه شته، که تاسو ډاډه نه ياست او يا هم که تاسو غواړۍ چې بدلون پکې راولۍ، لطفاً <strong>[[:$1]]</strong> وگورۍ.
[[$1|بټنوک]]',
'fileexists-extension' => 'په همدې نوم يوه بله دوتنه د پخوا نه شته: [[$2|thumb]]
* د پورته کېدونکې دوتنې نوم: <strong>[[:$1]]</strong>
* د پخوا نه شته دوتنه: <strong>[[:$2]]</strong>
لطفاً يو داسې نوم وټاکی چې د پخوانۍ دوتنې سره توپير ولري.',
'fileexists-forbidden' => 'د پخوا نه پدې نوم يوه دوتنه شته، او په دې نوم بله دوتنه نه پورته کېږي.
که تاسې بيا هم د خپلې دوتنې پورته کول غواړۍ، نو لطفاً بېرته وګرځۍ او همدغه دوتنه بيا په يوه نوي نوم پورته کړی.
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate' => 'همدا دوتنه د {{PLURAL:$1|لاندينۍ دوتنې|لاندينيو دوتنو}} غبرګه لمېسه ده:',
'uploadwarning' => 'د پورته کولو ګواښ',
'savefile' => 'دوتنه خوندي کړه',
'uploadedimage' => '"[[$1]]" پورته شوه',
'uploaddisabled' => 'پورته کول ناچارن شوي',
'uploaddisabledtext' => 'د دوتنې پورته کولو آسانتياوې ناچارن شوي.',
'uploadvirus' => 'دا دوتنه ويروس لري! تفصيل: $1',
'upload-source' => 'سرچينيزه دوتنه',
'sourcefilename' => 'د سرچينيزې دوتنې نوم:',
'sourceurl' => 'د URL سرچينه:',
'destfilename' => 'د موخيزې دوتنې نوم:',
'upload-maxfilesize' => 'د دوتنې تر ټولو لويه کچه: $1',
'upload-description' => 'د دوتنې څرگندونې',
'upload-options' => 'د پورته کولو خوښنې',
'watchthisupload' => 'همدا دوتنه کتل',
'upload-success-subj' => 'دوتنه پورته کېدل په برياليتوب سره ترسره شو',
'upload-failure-subj' => 'د پورته کېدو ستونزه',
'upload-warning-subj' => 'د پورته کولو ګواښ',

'upload-proto-error' => 'ناسم پروتوکول',
'upload-file-error' => 'کورنۍ ستونزه',
'upload-unknown-size' => 'ناڅرګنده کچه',
'upload-http-error' => 'د HTTP يوه ستونزه رامېنځ ته شوې: $1',

# File backend
'backend-fail-notexists' => 'د $1 په نوم دوتنه نشته.',
'backend-fail-delete' => 'د "$1" دوتنه ړنګه نه شوه.',
'backend-fail-alreadyexists' => 'د $1 دوتنه له پخوا نه شته.',
'backend-fail-read' => 'د "$1" دوتنه نه شي لوستل کېدای.',
'backend-fail-create' => 'د "$1" په دوتنه کې نور څه و نه ليکل شول.',

# ZipDirectoryReader
'zip-wrong-format' => 'ځانگړې شوې دوتنه يوه ZIP دوتنه نه وه.',

# img_auth script messages
'img-auth-accessdenied' => 'لاسرسی رد شو',
'img-auth-nofile' => 'د $1 په نوم کومه دوتنه نشته.',

# HTTP errors
'http-invalid-url' => 'ناسم URL: $1',
'http-read-error' => 'د HTTP د لوستلو ستونزه.',
'http-curl-error' => 'د URL د راوستلو تېروتنه: $1',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'URL ته لاسرسی و نه شو',
'upload-curl-error28' => 'د پورته کېدلو د مودې پای',

'license' => 'منښتليک:',
'license-header' => 'منښتليک:',
'nolicense' => 'هېڅ نه دي ټاکل شوي',
'license-nopreview' => '(مخليدنه نشته)',
'upload_source_file' => '(ستاسو په کمپيوټر کې يوه دوتنه)',

# Special:ListFiles
'listfiles_search_for' => 'د انځور د نوم لټون:',
'imgfile' => 'دوتنه',
'listfiles' => 'د دوتنو لړليک',
'listfiles_thumb' => 'بټنوک',
'listfiles_date' => 'نېټه',
'listfiles_name' => 'نوم',
'listfiles_user' => 'کارن',
'listfiles_size' => 'کچه (بايټونه)',
'listfiles_description' => 'څرگندونه',
'listfiles_count' => 'بڼې',

# File description page
'file-anchor-link' => 'دوتنه',
'filehist' => 'د دوتنې پېښليک',
'filehist-help' => 'په يوې نېټې/يوه وخت وټوکۍ چې د هماغه وخت او نېټې دوتنه چې څنگه ښکارېده هماغسې درښکاره شي.',
'filehist-deleteall' => 'ټول ړنگول',
'filehist-deleteone' => 'ړنگول',
'filehist-revert' => 'په څټ گرځول',
'filehist-current' => 'اوسنی',
'filehist-datetime' => 'نېټه/وخت',
'filehist-thumb' => 'بټنوک',
'filehist-thumbtext' => 'د $1 پورې د بټنوک بڼه',
'filehist-nothumb' => 'بې بټنوکه',
'filehist-user' => 'کارن',
'filehist-dimensions' => 'ډډې',
'filehist-filesize' => 'د دوتنې کچه',
'filehist-comment' => 'تبصره',
'filehist-missing' => 'دوتنه ورکه ده',
'imagelinks' => 'د دوتنې کارېدنه',
'linkstoimage' => 'دا {{PLURAL:$1|لاندينی مخ|$1 لانديني مخونه}} د همدې دوتنې سره تړنې لري:',
'nolinkstoimage' => 'داسې هېڅ کوم مخ نه شته چې د دغې دوتنې سره تړنې ولري.',
'duplicatesoffile' => 'دا لاندينۍ {{PLURAL:$1| دوتنه د همدې دوتنې غبرګونې لمېسه ده|$1 دوتنې د همدې دوتنې غبرګونې لمېسې دي}} ([[Special:FileDuplicateSearch/$2|نور تفصيل]]):',
'sharedupload' => 'دا دوتنه د $1 لخوا نه ده او کېدای شي چې نورې پروژې به يې هم کاروي.',
'sharedupload-desc-here' => 'دا دوتنه د $1 لخوا خپرېږې او کېدای شي چې دا په نورو پروژو هم کارېدلې وي.
د دوتنې د کارېدنې لا نور مالومات د [$2 دوتنې د څرگندنو په مخ] کې لاندې ښودل شوی.',
'filepage-nofile' => 'په دې نوم کومه دوتنه نشته.',
'filepage-nofile-link' => 'په دې نوم کومه دوتنه نشته، خو تاسې يې [$1 پورته کولی شی].',
'uploadnewversion-linktext' => 'د همدغې دوتنې نوې بڼه پورته کول',
'shared-repo-from' => 'د $1 لخوا',
'upload-disallowed-here' => 'تاسې د دې دوتنې دپاسه نشی ليکلی.',

# File reversion
'filerevert-comment' => 'سبب:',
'filerevert-submit' => 'په څټ گرځول',

# File deletion
'filedelete' => '$1 ړنگول',
'filedelete-legend' => 'دوتنه ړنگول',
'filedelete-intro' => "تاسې د '''[[Media:$1|$1]]''' دوتنې او د ورسره ټول پېښليک د ړنگولو په حال کې ياست.",
'filedelete-comment' => 'سبب:',
'filedelete-submit' => 'ړنگول',
'filedelete-success' => "'''$1''' ړنگ شو.",
'filedelete-nofile' => "'''$1''' نشته.",
'filedelete-otherreason' => 'بل/اضافه سبب:',
'filedelete-reason-otherlist' => 'بل سبب',
'filedelete-reason-dropdown' => '*د ړنگولو ټولگړی سبب
** د رښتو نه غاړه غړونه
** کټ مټ دوه گونې دوتنه',
'filedelete-edit-reasonlist' => 'د ړنگولو سببونه سمول',
'filedelete-maintenance-title' => 'دوتنه نه شي ړنګېدی',

# MIME search
'mimesearch' => 'MIME پلټنه',
'mimetype' => 'MIME بڼه:',
'download' => 'ښکته کول',

# Unwatched pages
'unwatchedpages' => 'ناکتلي مخونه',

# List redirects
'listredirects' => 'د ورگرځېدنو لړليک',

# Unused templates
'unusedtemplates' => 'ناکارېدلې کينډۍ',
'unusedtemplateswlh' => 'نور تړنونه',

# Random page
'randompage' => 'ناټاکلی مخ',
'randompage-nopages' => 'په لانديني {{PLURAL:$2|نوم-تشيال|نوم-تشيالونو}} کې هېڅ کوم مخ نشته: $1.',

# Random page in category
'randomincategory-selectcategory' => 'يو ناټاکلی مخ له وېشنيزې موندل: $1 $2.',

# Random redirect
'randomredirect' => 'ناټاکلی ورگرځېدنه',

# Statistics
'statistics' => 'شمار',
'statistics-header-pages' => 'د مخونو شمار',
'statistics-header-edits' => 'د سمونو شمار',
'statistics-header-views' => 'د کتنو شمار',
'statistics-header-users' => 'د کارنانو شمار',
'statistics-header-hooks' => 'بل شمار',
'statistics-articles' => 'مېنځپانگيز مخونه',
'statistics-pages' => 'مخونه',
'statistics-pages-desc' => 'د ويکي ټول مخونه، د خبرو اترو، مخ گرځېدنو، او لا نورو مخونو په گډون.',
'statistics-files' => 'پورته شوې دوتنې',
'statistics-edits' => 'د {{SITENAME}} د جوړېدو راهيسې د مخونو سمون',
'statistics-edits-average' => 'پر يوه مخ د سمون منځوۍ کچه',
'statistics-views-total' => 'ټولټال کتنې',
'statistics-views-peredit' => 'د هر سمون په سر کتنې',
'statistics-users' => 'ثبت شوي [[Special:ListUsers|کارنان]]',
'statistics-users-active' => 'فعاله کارنان',
'statistics-users-active-desc' => 'هغه کارنان چې په {{PLURAL:$1|وروستۍ ورځ|وروستيو $1 ورځو}} کې فعاله ونډه لرلې',
'statistics-mostpopular' => 'ډېر کتل شوي مخونه',

'pageswithprop-submit' => 'ورځه',

'doubleredirects' => 'دوه ځلي ورگرځېدنې',

'brokenredirects' => 'ماتې ورگرځېدنې',
'brokenredirects-edit' => 'سمول',
'brokenredirects-delete' => 'ړنگول',

'withoutinterwiki' => 'د ژبې د تړنو بې برخې مخونه',
'withoutinterwiki-summary' => 'لانديني مخونه د نورو ژبو بڼو سره تړنې نه لري.',
'withoutinterwiki-legend' => 'مختاړی',
'withoutinterwiki-submit' => 'ښکاره کول',

'fewestrevisions' => 'لږ مخليدل شوي مخونه',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|بايټ|بايټونه}}',
'ncategories' => '$1 {{PLURAL:$1|وېشنيزه|وېشنيزې}}',
'nlinks' => '$1 {{PLURAL:$1|تړنه|تړنې}}',
'nmembers' => '$1 {{PLURAL:$1|غړی|غړي}}',
'nrevisions' => '$1 {{PLURAL:$1|بڼه|بڼې}}',
'nviews' => '$1 {{PLURAL:$1|کتنه|کتنې}}',
'nimagelinks' => 'په $1 {{PLURAL:$1|کارېدلی مخ|کارېدلي مخونه}}',
'ntransclusions' => 'په $1 {{PLURAL:$1|مخ|مخونو}} کارېدلی',
'specialpage-empty' => 'د دې راپور لپاره کومې پايلې نشته.',
'lonelypages' => 'يتيم مخونه',
'uncategorizedpages' => 'ناوېشلي مخونه',
'uncategorizedcategories' => 'ناوېشلې وېشنيزې',
'uncategorizedimages' => 'ناوېشلي انځورنه',
'uncategorizedtemplates' => 'ناوېشلې کينډۍ',
'unusedcategories' => 'ناکارېدلې وېشنيزې',
'unusedimages' => 'ناکارېدلې دوتنې',
'popularpages' => 'نامتو مخونه',
'wantedcategories' => 'غوښتلې وېشنيزې',
'wantedpages' => 'غوښتلي مخونه',
'wantedfiles' => 'غوښتلې دوتنې',
'wantedtemplates' => 'غوښتلې کينډۍ',
'mostlinked' => 'د ډېرو تړنو مخونه',
'mostlinkedcategories' => 'د گڼو تړنو وېشنيزې',
'mostlinkedtemplates' => 'د ډېرو تړنو کينډۍ',
'mostcategories' => 'د گڼو وېشنيزو مخونه',
'mostimages' => 'د ډېرو تړنو انځورونه',
'mostinterwikis' => 'د ډېرو خپلمنځي تړنو مخونه',
'mostrevisions' => 'ډېر کتلي مخونه',
'prefixindex' => 'د مختاړيو ټول مخونه',
'prefixindex-namespace' => 'د مختاړي ټول مخونه ($1 نومتشيال)',
'shortpages' => 'لنډ مخونه',
'longpages' => 'اوږده مخونه',
'deadendpages' => 'بې پايه مخونه',
'deadendpagestext' => 'همدا لانديني مخونه په دغه ويکي کې د نورو مخونو سره تړنې نه لري.',
'protectedpages' => 'ژغورلي مخونه',
'protectedpages-indef' => 'يوازې بې پايه ژغورنې',
'protectedpages-cascade' => 'يوازې ځوړاوبيزې ژغورنې',
'protectedtitles' => 'ژغورلي سرليکونه',
'listusers' => 'کارن لړليک',
'listusers-editsonly' => 'يوازې هغه کارنان چې سمونونه يې کړي ښکاره کول',
'listusers-creationsort' => 'د جوړېدو د نېټې له مخې اوډل',
'usereditcount' => '{{PLURAL:$1|سمون|سمونونه}}',
'usercreated' => 'په $1 نېټه په $2 بجو {{GENDER:$3|جوړ شو}}',
'newpages' => 'نوي مخونه',
'newpages-username' => 'کارن-نوم:',
'ancientpages' => 'تر ټولو زاړه مخونه',
'move' => 'لېږدول',
'movethispage' => 'دا مخ ولېږدوه',
'unusedimagestext' => 'دا لاندينۍ دوتنې په هېڅ کوم مخ کې نه دي ټومبېدلي. لطفاً په پام کې وساتۍ چې نور وېبځايونه به د دغو دوتنو له يو دوتنې سره يو راسن يو آر ال (URL) ولري او لا تر اوسه به دوتنه د فعالې کارېدنې سره سره دلته پرته وي.',
'notargettitle' => 'بې موخې',
'pager-newer-n' => '{{PLURAL:$1|نوی 1|نوي $1}}',
'pager-older-n' => '{{PLURAL:$1|زوړ 1|زاړه $1}}',
'suppress' => 'څارن',

# Book sources
'booksources' => 'د کتاب سرچينې',
'booksources-search-legend' => 'د کتابي سرچينو پلټنه',
'booksources-go' => 'ورځه',
'booksources-text' => 'دا لاندې د هغه وېبځايونو د تړنو لړليک دی چېرته چې نوي او زاړه کتابونه پلورل کېږي، او يا هم کېدای شي چې د هغه کتاب په هکله مالومات ولري کوم چې تاسو ورپسې لټېږۍ:',

# Special:Log
'specialloguserlabel' => 'ترسره کوونکی:',
'speciallogtitlelabel' => 'موخه (سرليک يا کارن):',
'log' => 'يادښتونه',
'all-logs-page' => 'ټول عام يادښتونه',
'logempty' => 'په يادښت کې ورته څه نشته.',
'log-title-wildcard' => 'هغه سرليکونه پلټل چې په دې متن پيلېږي',
'showhideselectedlogentries' => 'د ټاکلو يادښتونو ښکارېدنه بدلول',

# Special:AllPages
'allpages' => 'ټول مخونه',
'alphaindexline' => '$1 تر $2',
'nextpage' => 'بل مخ ($1)',
'prevpage' => 'تېر مخ ($1)',
'allpagesfrom' => 'هغه مخونه کتل چې پېلېږي په:',
'allpagesto' => 'هغه مخونه کتل چې پای يې وي:',
'allarticles' => 'ټول مخونه',
'allinnamespace' => 'ټول مخونه ($1 نوم-تشيال)',
'allnotinnamespace' => 'ټول مخونه (د $1 نوم-تشيال پرته)',
'allpagesprev' => 'پخواني',
'allpagesnext' => 'راتلونکي',
'allpagessubmit' => 'ورځه',
'allpagesprefix' => 'هغه مخونه ښکاره کړه چې مختاړی يې وي:',
'allpagesbadtitle' => 'ورکړ شوی سرليک سم نه دی او يا هم د ژبو او يا د بېلابېلو ويکي گانو مختاړی لري. ستاسو په سرليک کې يو يا څو داسې ابېڅې دي کوم چې په سرليک کې نه شي کارېدلی.',
'allpages-bad-ns' => '{{SITENAME}} د "$1" په نامه هېڅ کوم نوم-تشيال نه لري.',
'allpages-hide-redirects' => 'مخ گرځونې پټول',

# SpecialCachedPage
'cachedspecial-refresh-now' => 'تر ټولو تازه کتل.',

# Special:Categories
'categories' => 'وېشنيزې',
'categoriespagetext' => 'دا لاندينۍ {{PLURAL:$1|وېشنيزه|وېشنيزې}} مخونه يا رسنيزې دوتنې لري.
دلته [[Special:UnusedCategories|ناکارېدلې وېشنيزې]] نه دي ښکاره شوي.
[[Special:WantedCategories|غوښتلې وېشنيزې]] هم وگورۍ.',
'categoriesfrom' => 'هغه وېشنيزې کتل چې پېلېږي په:',
'special-categories-sort-count' => 'د شمېر له مخې اوډل',
'special-categories-sort-abc' => 'د ابېڅو له مخې اوډل',

# Special:DeletedContributions
'deletedcontributions' => 'ړنګې شوې ونډې',
'deletedcontributions-title' => 'ړنګې شوې ونډې',
'sp-deletedcontributions-contribs' => 'ونډې',

# Special:LinkSearch
'linksearch' => 'د باندنيو تړنو پلټنه',
'linksearch-pat' => 'د پلټنې مخبېلگه:',
'linksearch-ns' => 'نوم-تشيال:',
'linksearch-ok' => 'پلټل',
'linksearch-line' => '$1 د $2 سره تړل شوی',

# Special:ListUsers
'listusersfrom' => 'هغه کارنان کتل چې نومونه يې پېلېږي په:',
'listusers-submit' => 'ښکاره کول',
'listusers-noresult' => 'هېڅ کوم کارن و نه موندل شو.',
'listusers-blocked' => '(بنديز لگېدلی)',

# Special:ActiveUsers
'activeusers' => 'د فعالو کارنانو لړليک',
'activeusers-intro' => 'دا د هغو کارنانو لړليک دی چې په {{PLURAL:$1|تېرې|تېرو}} $1 {{PLURAL:$1|ورځ|ورځو}} کې يې ونډې ترسره کړي.',
'activeusers-count' => 'په {{PLURAL:$3|تېرې ورځ|تېرو $3 ورځو}} کې $1 {{PLURAL:$1|سمون|سمونونه}}',
'activeusers-from' => 'هغه کارنان کتل چې نومونه يې پېلېږي په:',
'activeusers-hidebots' => 'روباټونه پټول',
'activeusers-hidesysops' => 'پازوالان پټول',
'activeusers-noresult' => 'کارن و نه موندل شو.',

# Special:ListGroupRights
'listgrouprights' => 'د کارن ډلو رښتې',
'listgrouprights-group' => 'ډله',
'listgrouprights-rights' => 'رښتې',
'listgrouprights-helppage' => 'Help:د ډلې رښتې',
'listgrouprights-members' => '(د غړو لړليک)',
'listgrouprights-addgroup' => '{{PLURAL:$2|ډله|ډلې}} ورگډول: $1',
'listgrouprights-removegroup' => '{{PLURAL:$2|ډله|ډلې}} ليري کول: $1',
'listgrouprights-addgroup-all' => 'ټولې ډلې ورگډول',
'listgrouprights-removegroup-all' => 'ټولې ډلې ليري کول',
'listgrouprights-addgroup-self' => 'خپل گڼون کې د {{PLURAL:$2|ډله|ډلې}} ورگډول: $1',
'listgrouprights-removegroup-self' => 'خپل گڼون نه د {{PLURAL:$2|ډله|ډلې}} ليري کول: $1',
'listgrouprights-addgroup-self-all' => 'خپل گڼون کې ټولې ډلې ورگډول',
'listgrouprights-removegroup-self-all' => 'خپل گڼون نه ټولې ډلې ليري کول',

# Email user
'mailnologin' => 'هېڅ کومه لېږل شوې پته نشته',
'emailuser' => 'کارن ته برېښليک لېږل',
'emailuser-title-target' => 'دې {{GENDER:$1|کارن}} ته برېښليک لېږل',
'emailuser-title-notarget' => 'کارن ته برېښليک لېږل',
'emailpage' => 'کارن ته برېښليک لېږل',
'defemailsubject' => 'د "$1" کارن لخوا د {{SITENAME}} برېښليک',
'usermaildisabled' => 'د کارن برېښليک ناچارند دی',
'usermaildisabledtext' => 'په دې ويکي تاسې نورو کارنانو ته برېښليک نه شی ورلېږلی',
'noemailtitle' => 'هېڅ کومه برېښليک پته نشته.',
'nowikiemailtitle' => 'د برېښليک لېږلو اجازه نشته',
'nowikiemailtext' => 'دې کارن د نورو کارنانو لخوا د برېښليک د نه ترلاسه کولو چاره خوښه کړې.',
'emailtarget' => 'د ترلاسه کوونکي کارن-نوم وټاپۍ',
'emailusername' => 'کارن-نوم:',
'emailusernamesubmit' => 'سپارل',
'email-legend' => 'د {{SITENAME}} يو بل کارن ته يو برېښليک ورلېږل',
'emailfrom' => 'لېږونکی',
'emailto' => 'اخيستونکی',
'emailsubject' => 'سکالو:',
'emailmessage' => 'پيغام:',
'emailsend' => 'لېږل',
'emailccme' => 'زما د پيغام يوه بېلگه دې ماته هم برېښليک شي.',
'emailccsubject' => '$1 ته ستاسو د پيغام لمېسه: $2',
'emailsent' => 'برېښليک مو ولېږل شو',
'emailsenttext' => 'ستاسو برېښليکي پيغام ولېږل شو.',
'emailuserfooter' => 'دا برېښليک د $1 لخوا $2 ته د {{SITENAME}} په وېبځي کې د "کارن ته برېښليک لېږل" د کړنې په مرسته لېږل شوی.',

# User Messenger
'usermessage-summary' => 'د غونډال پيغام پرېښودل.',
'usermessage-editor' => 'د غونډال پيغام رسونکی',

# Watchlist
'watchlist' => 'کتنلړ',
'mywatchlist' => 'کتنلړ',
'watchlistfor2' => 'د $1 لپاره $2',
'nowatchlist' => 'ستاسې کتنلړ کې څه نه شته.',
'watchlistanontext' => 'د خپل کتنلړ د توکو د سمولو او کتلو لپاره $1 ترسره کړۍ.',
'watchnologin' => 'غونډال کې نه ياست ننوتي.',
'watchnologintext' => 'ددې لپاره چې خپل کتنلړ کې بدلون راولی نو تاسو ته پکار ده چې لومړی غونډال کې [[Special:UserLogin|ورننوځۍ]].',
'addwatch' => 'کتنلړ کې ورگډول',
'addedwatchtext' => 'د "[[:$1]]" په نوم يو مخ ستاسې [[Special:Watchlist|کتنلړ]] کې ورگډ شو.
په راتلونکې کې چې په دغه مخ او د دې د خبرواترو مخ کې کوم بدلونونه راځي نو هغه به ستاسې کتنلړ کې ښکاري.',
'removewatch' => 'له کتنلړ نه غورځول',
'removedwatchtext' => 'د "[[:$1]]" مخ [[Special:Watchlist|ستاسې کتنلړ]] نه لرې شو.',
'watch' => 'کتل',
'watchthispage' => 'همدا مخ کتل',
'unwatch' => 'نه کتل',
'unwatchthispage' => 'څارنې په ټپه درول',
'notanarticle' => 'يو منځپانګيز مخ نه دی',
'watchlist-details' => 'ستاسې کتنلړ کې {{PLURAL:$1|$1 مخ دی|$1 مخونه دي}}، د خبرو اترو مخونه مو پکې نه دي شمېرلي.',
'wlheader-enotif' => 'د برېښليک له لارې خبرول چارن شوی.*',
'wlheader-showupdated' => "هغه مخونه چې وروستی ځل ستاسو د کتلو نه وروسته بدلون موندلی په '''روڼ''' ليک نښه شوي.",
'watchlistcontains' => 'ستاسې کتنلړ $1 {{PLURAL:$1|مخ|مخونه}} لري.',
'iteminvalidname' => "د '$1' توکي سره ستونزه، ناسم نوم ...",
'wlnote' => "دلته لاندې {{PLURAL:$1|وروستی بدلون دی|وروستي '''$1''' بدلونونه دي}} چې په {{PLURAL:$2|تېر ساعت|تېرو '''$2''' ساعتونو}} کې تر $3 نېټې او $4 بجو پېښ شوي.",
'wlshowlast' => 'وروستي $1 ساعتونه $2 ورځې $3 ښکاره کړه',
'watchlist-options' => 'د کتنلړ خوښنې',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'د کتلو په حال کې...',
'unwatching' => 'د نه کتلو په حال کې...',

'enotif_mailer' => 'د {{SITENAME}} خبرتيايي برېښليک',
'enotif_reset' => 'ټول مخونه کتل شوي نخښه کول',
'enotif_impersonal_salutation' => '{{SITENAME}} کارن',
'enotif_subject_deleted' => 'د {{SITENAME}} مخ $1 د {{gender:$2|$2}} لخوا ړنگ شوی',
'enotif_subject_created' => 'د {{SITENAME}} مخ $1 د {{gender:$2|$2}} لخوا جوړ شوی',
'enotif_subject_moved' => 'د {{SITENAME}} مخ $1 د {{gender:$2|$2}} لخوا لېږدول شوی',
'enotif_subject_restored' => 'د {{SITENAME}} مخ $1 د {{gender:$2|$2}} لخوا بيازېرمل شوی',
'enotif_subject_changed' => 'د {{SITENAME}} مخ $1 د {{gender:$2|$2}} لخوا بدل شوی',
'enotif_body_intro_deleted' => 'د {{SITENAME}} مخ $1 په $ د {{gender:$2|$2}} لخوا ړنگ شوی، $3 وگورۍ.',
'enotif_body_intro_created' => 'د {{SITENAME}} مخ $1 په $PAGEEDITDATE د {{gender:$2|$2}} لخوا جوړ شوی، د اوسنۍ بڼې کتلو لپاره $3 وگورۍ.',
'enotif_body_intro_moved' => 'د {{SITENAME}} مخ $1 په $PAGEEDITDATE د {{gender:$2|$2}} لخوا لېږدول شوی، د اوسنۍ بڼې کتلو لپاره $3 وگورۍ.',
'enotif_body_intro_restored' => 'د {{SITENAME}} مخ $1 په $PAGEEDITDATE د {{gender:$2|$2}} لخوا بيازېرمل شوی، د اوسنۍ بڼې کتلو لپاره $3 وگورۍ.',
'enotif_body_intro_changed' => 'د {{SITENAME}} مخ $1 په $PAGEEDITDATE د {{gender:$2|$2}} لخوا بدل شوی، د اوسنۍ بڼې کتلو لپاره $3 وگورۍ.',
'enotif_lastvisited' => 'د ټولو هغو بدلونونو د کتلو لپاره چې ستاسې د وروستي ځل راتگ نه وروسته پېښې شوي، $1 وگورۍ.',
'enotif_lastdiff' => 'د همدغه بدلون د کتلو لپاره $1 وگورۍ.',
'enotif_anon_editor' => 'ورکنومی کارن $1',
'enotif_body' => 'قدرمن/قدرمنې $WATCHINGUSERNAME,


په $PAGEEDITDATE نېټه، د  $PAGEEDITOR لخوا د {{SITENAME}} مخ $PAGETITLE ته $CHANGEDORCREATED، د اوسنۍ بڼې لپاره $PAGETITLE_URL وگورۍ.

$NEWPAGE

د سمونگر لنډيز: $PAGESUMMARY $PAGEMINOREDIT

Contact the editor:
برېښليک: $PAGEEDITOR_EMAIL
ويکي: $PAGEEDITOR_WIKI

د لا نورو بدلونونو په پېښېدو سره به تاسې ته د خبراوي بل برېښليک نه درلېږل کېږي، تر څو چې تاسې د همدې مخ نه کتنه و نه کړۍ.
تاسې دا هم کولای شی چې په خپل کتنلړ کې د ټولو کتل شويو مخونو د خبراوي بيرغونه بيا له سره پرځای کړۍ.

             ستاسې ملگری

د {{SITENAME}} د خبرولو غونډال

--
د خپل کتنلړ د امستنو د بدلون لپاره،
{{canonicalurl:{{#special:EditWatchlist}}}} نه ليدنه وکړۍ

د خپل کتنلړ د مخونو د ړنگولو لپاره،
$UNWATCHURL  نه ليدنه وکړۍ

انگېرنې او نورې مرستې:
{{canonicalurl:{{MediaWiki:Helppage}}}}',
'created' => 'جوړ شو',
'changed' => 'بدلېدلی',

# Delete
'deletepage' => 'مخ ړنگول',
'confirm' => 'تاييد',
'excontent' => 'د مخ مېنځپانگه دا وه: "$1"',
'excontentauthor' => 'د مخ مېنځپانگه دا وه: "$1" (او يواځينی ونډه وال "[[Special:Contributions/$2|$2]]" وه)',
'exblank' => 'مخ تش وه',
'delete-confirm' => '"$1" ړنگول',
'delete-legend' => 'ړنگول',
'historywarning' => "گواښنه:''' دا مخ چې تاسې يې د ړنگېدو تکل لرئ نژدې $1 {{PLURAL:$1|بڼه|بڼې}} پېښليک لري:",
'confirmdeletetext' => 'تاسې د تل لپار يو مخ يا انځور د هغه ټول پېښليک سره د دغه توکبنسټ نه ړنگوئ. که چېرته تاسې ددې کړنې په پايله پوه ياست او يا ستاسې همدا کړنه د دې پاڼې د [[{{MediaWiki:Policy-url}}|تگلارې]] سره سمون خوري نو لطفاً د دې تاييد وکړی.',
'actioncomplete' => 'بشپړه کړنه',
'actionfailed' => 'کړنه نابريالۍ شوه',
'deletedtext' => '"$1" ړنگ شوی.
د نوو ړنگ شوو سوانحو لپاره $2 وگورۍ.',
'dellogpage' => 'د ړنگولو يادښت',
'dellogpagetext' => 'دا لاندې د نوو ړنگ شوو کړنو لړليک دی.',
'deletionlog' => 'د ړنگولو يادښت',
'deletecomment' => 'سبب:',
'deleteotherreason' => 'بل/اضافه سبب:',
'deletereasonotherlist' => 'بل سبب',
'deletereason-dropdown' => '*د ړنگولو ټولگړی سبب
** د ليکوال غوښتنه
** د رښتو تېری
** د پوهې سره دښمني',
'delete-edit-reasonlist' => 'د ړنگولو سببونه سمول',

# Rollback
'rollback_short' => 'په شابېول',
'rollbacklink' => 'په شابېول',

# Protect
'protectlogpage' => 'د ژغورنې يادښت',
'protectedarticle' => '"[[$1]]" وژغورل شو',
'modifiedarticleprotection' => 'د "[[$1]]" لپاره د ژغورنې کچه بدله شوه',
'movedarticleprotection' => 'د ژغورنې امستنې له "[[$2]]" څخه "[[$1]]" ته ولېږدېدې',
'protect-title' => 'د "$1" لپاره د ژغورنې کچه بدلول',
'prot_1movedto2' => '[[$1]]، [[$2]] ته ولېږدېده',
'protect-legend' => 'د ژغورلو پخلی کول',
'protectcomment' => 'سبب:',
'protectexpiry' => 'د پای نېټه:',
'protect_expiry_invalid' => 'د پای وخت ناسم دی.',
'protect_expiry_old' => 'د پای وخت په تېرمهال کې دی.',
'protect-unchain-permissions' => 'د لا ژغورلو خوښنې پرانيستل',
'protect-text' => "تاسې کولای شی چې د '''$1''' مخ لپاره د ژغورلو کچه همدلته وگورۍ او بدلون پکې راولی.",
'protect-locked-access' => "ستاسې گڼون دا اجازه نه لري چې د پاڼو د ژغورنې په کچه کې بدلون راولي.
دلته د '''$1''' مخ لپاره اوسني شته امستنې دي:",
'protect-cascadeon' => 'د اوسمهال لپاره همدا مخ ژغورل شوی دا ځکه چې همدا مخ په {{PLURAL:$1|لانديني مخ|لانديني مخونو}} کې ورگډ دی چې {{PLURAL:$1|ځوړاوبيزه ژغورنه يې چارنه ده|ځوړاوبيزې ژغورنې يې چارنې دي}}.
تاسې د همدې مخ د ژغورنې په کچه کې بدلون راوستلای شی، خو دا به ځوړاوبيزه ژغورنه اغېزمنه نه کړي.',
'protect-default' => 'ټول کارنان پرېښودل',
'protect-fallback' => 'يوازې د "$1" اجازې لرونکي کارنان پرېښودل',
'protect-level-autoconfirmed' => 'يوازې تاييد شوي کارنان',
'protect-level-sysop' => 'يواځې پازوالان پرېښودل',
'protect-summary-cascade' => 'ځوړاوبيز',
'protect-expiring' => 'په $1 (UTC) پای ته رسېږي',
'protect-expiring-local' => 'پای نېټه $1',
'protect-expiry-indefinite' => 'لامحدوده',
'protect-cascade' => 'په همدې مخ کې د ټولو گډو مخونو څخه ژغورنه کېږي (ځوړاوبيزه ژغورنه)',
'protect-cantedit' => 'تاسې نه شی کولای چې د دې مخ د ژغورنې په کچه کې بدلون راولی، دا ځکه چې تاسې د دې مخ د سمولو اجازه نه لری.',
'protect-othertime' => 'بل وخت:',
'protect-othertime-op' => 'بل وخت',
'protect-otherreason' => 'بل/اضافي سبب:',
'protect-otherreason-op' => 'بل سبب',
'protect-dropdown' => '*د ژغورلو عام سببونه
** ډېره زياته ورانکاري
** ډېره زياته سپام خپرونه
** بې گټې سمونې او خپرونې
** ډېر لوستونکی مخ',
'protect-edit-reasonlist' => 'د ژغورنې سببونه سمول',
'protect-expiry-options' => '1 ساعت:1 hour,1 ورځ:1 day,1 اوونۍ:1 week,2 اوونۍ:2 weeks,1 مياشت:1 month,3 مياشتې:3 months,6 مياشتې:6 months,1 کال:1 year,لامحدوده:infinite',
'restriction-type' => 'اجازه:',
'restriction-level' => 'د بنديز کچه:',
'minimum-size' => 'وړه کچه',
'maximum-size' => 'د حد اکثر کچه:',
'pagesize' => '(بايټونه)',

# Restrictions (nouns)
'restriction-edit' => 'سمول',
'restriction-move' => 'لېږدول',
'restriction-create' => 'جوړول',
'restriction-upload' => 'پورته کول',

# Restriction levels
'restriction-level-sysop' => 'بشپړ ژغورلی',
'restriction-level-autoconfirmed' => 'نيم ژغورلی',
'restriction-level-all' => 'هر يو پوړ',

# Undelete
'undelete' => 'ړنگ شوي مخونه کتل',
'undeletepage' => 'ړنگ شوي مخونه کتل او بيا پرځای کول',
'viewdeletedpage' => 'ړنگ شوي مخونه کتل',
'undeletebtn' => 'بيازېرمل',
'undeletelink' => 'کتل/بيازېرمل',
'undeleteviewlink' => 'کتل',
'undeletereset' => 'بياايښودل',
'undeleteinvert' => 'ټاکنې سرچپه کول',
'undeletecomment' => 'سبب:',
'undeletedfiles' => '{{PLURAL:$1|1 دوتنه بيازېرمه شوه|$1 دوتنې بيازېرمه شوې}}',
'undelete-header' => 'د وروستيو ړنگو شوو مخونو لپاره [[Special:Log/delete|د ړنگولو يادښت]] وگورۍ.',
'undelete-search-box' => 'ړنگ شوي مخونه لټول',
'undelete-search-prefix' => 'هغه مخونه ښکاره کړه چې پېلېږي په:',
'undelete-search-submit' => 'پلټل',
'undelete-show-file-submit' => 'هو',

# Namespace form on various pages
'namespace' => 'نوم-تشيال:',
'invert' => 'ټاکنې سرچپه کول',
'namespace_association' => 'مل نومتشيال',
'blanknamespace' => '(آرنی)',

# Contributions
'contributions' => '{{GENDER:$1|کارن}} ونډې',
'contributions-title' => 'د $1 کارن ونډې',
'mycontris' => 'ونډې',
'contribsub2' => 'د $1 لپاره ($2)',
'nocontribs' => 'دې شرطونو سره سم بدلونونه و نه موندل شول.',
'uctop' => '(اوسنی)',
'month' => 'له مياشتې د (او پخواني):',
'year' => 'له کال د (او پخواني):',

'sp-contributions-newbies' => 'د نوو گڼونونو ونډې ښکاره کول',
'sp-contributions-newbies-sub' => 'د نوو گڼونونو لپاره',
'sp-contributions-blocklog' => 'د بنديز يادښت',
'sp-contributions-deleted' => 'ړنګې شوې ونډې',
'sp-contributions-uploads' => 'پورته کېدنې',
'sp-contributions-logs' => 'يادښتونه',
'sp-contributions-talk' => 'خبرې اترې',
'sp-contributions-blocked-notice' => 'دم مهال په دې کارن بنديز لگېدلی.
د بنديز يادښت تازه مالومات په لاندې توگه دي:',
'sp-contributions-search' => 'د ونډو پلټنه',
'sp-contributions-username' => 'IP پته يا کارن-نوم:',
'sp-contributions-toponly' => 'يوازې هغه سمونونه چې تر ټولو تازه بڼې لري ښکاره کول',
'sp-contributions-submit' => 'پلټل',

# What links here
'whatlinkshere' => 'د دې مخ تړنې',
'whatlinkshere-title' => 'هغه مخونه چې د "$1" سره تړنې لري',
'whatlinkshere-page' => 'مخ:',
'linkshere' => "دغه لانديني مخونه د '''[[:$1]]''' سره تړنې لري:",
'nolinkshere' => "د '''[[:$1]]''' سره هېڅ يو مخ هم تړنې نه لري .",
'isredirect' => 'د مخ گرځونې مخ',
'istemplate' => 'ورګډېدنه',
'isimage' => 'د دوتنې تړنه',
'whatlinkshere-prev' => '{{PLURAL:$1|پخوانی|پخواني $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|راتلونکی|راتلونکي $1}}',
'whatlinkshere-links' => '← تړنې',
'whatlinkshere-hideredirs' => 'مخ گرځونې $1',
'whatlinkshere-hidetrans' => 'پايلې $1',
'whatlinkshere-hidelinks' => 'تړنې $1',
'whatlinkshere-hideimages' => 'د دوتنې تړنې $1',
'whatlinkshere-filters' => 'چاڼگرونه',

# Block/unblock
'block' => 'په کارن بنديز لگول',
'unblock' => 'کارن له بنديزه وېستل',
'blockip' => 'په کارن بنديز لگول',
'blockip-title' => 'په کارن بنديز لگول',
'blockip-legend' => 'په کارن بنديز لگول',
'ipadressorusername' => 'IP پته يا کارن نوم',
'ipbexpiry' => 'د پای نېټه:',
'ipbreason' => 'سبب:',
'ipbreasonotherlist' => 'بل لامل',
'ipbreason-dropdown' => '*د بنديز ټولگړي سببونه
** د ناسمو مالوماتو خپرول
** د مخونو د مېنځپانگې ړنگول
** په مخونو کې د باندنيو وېبځايونو بېکاره سپام تړنې ځايول
** په مخونو کې بې مانا/چټياټ ځايول
** په مخونو کې ناندرۍ راپارېدنې/د تاوتريخوالي خپرېدو ته هڅول
** د گڼ شمېر گڼونونو نه ناوړه گټه اخيستل
** نه مننونکی کارن-نوم کارول',
'ipbcreateaccount' => 'د گڼون جوړولو مخنيول',
'ipbsubmit' => 'په دې کارن بنديز لگول',
'ipbother' => 'بل وخت:',
'ipboptions' => '2 ساعتونه:2 hours,1 ورځ:1 day,3 ورځې:3 days,1 اوونۍ:1 week,2 اوونۍ:2 weeks,1 مياشت:1 month,3 مياشتې:3 months,6 مياشتې:6 months,1 کال:1 year,لامحدوده:infinite',
'ipbotheroption' => 'نور',
'ipbotherreason' => 'بل/اضافه سبب:',
'ipbhidename' => 'کارن-نوم له سمون او لړليکونو پټول',
'ipb-confirm' => 'د بنديز تاييد',
'badipaddress' => 'ناسمه IP پته',
'blockipsuccesssub' => 'بنديز په برياليتوب سره ولگېده',
'blockipsuccesstext' => 'په [[Special:Contributions/$1|$1]] بنديز لگېدلی.<br />
د بنديزونو د څارلو لپاره [[Special:BlockList|بنديز لړليک]] وگورۍ.',
'ipb-edit-dropdown' => 'د بنديز سببونه سمول',
'ipb-unblock-addr' => 'له $1 بنديز ليرې کول',
'ipb-unblock' => 'له يوه کارن-نوم يا IP پتې بنديز ليري کول',
'ipb-blocklist' => 'شته بنديزونه کتل',
'ipb-blocklist-contribs' => 'د $1 ونډې',
'unblockip' => 'کارن له بنديزه وېستل',
'ipusubmit' => 'دا بنديز ليرې کول',
'unblocked' => 'له [[User:$1|$1]] بنديز ليري شو',
'unblocked-range' => 'له $1 بنديز ليرې شو',
'blocklist' => 'بنديز لگېدلي کارنان',
'ipblocklist' => 'بنديز لگېدلي کارنان',
'ipblocklist-legend' => 'يو بنديز شوی کارن موندل',
'blocklist-userblocks' => 'گڼون بنديزونه پټول',
'blocklist-tempblocks' => 'لنډمهاله بنديزونه پټول',
'blocklist-addressblocks' => 'يواځې آی پي بنديزونه پټول',
'blocklist-timestamp' => 'وخت ټاپه',
'blocklist-target' => 'موخه',
'blocklist-expiry' => 'پای نېټه',
'blocklist-by' => 'بنديز لگونکی پازوال',
'blocklist-reason' => 'سبب',
'ipblocklist-submit' => 'پلټل',
'ipblocklist-localblock' => 'سيمه ايز بنديز',
'ipblocklist-otherblocks' => '{{PLURAL:$1|بل بنديز|نور بنديزونه}}',
'infiniteblock' => 'نامحدوده',
'expiringblock' => 'په $1 نېټه، $2 بجو پای ته رسېږي',
'anononlyblock' => 'يواځې ورکنومی',
'createaccountblock' => 'په گڼون جوړولو بنديز لگېدلی',
'emailblock' => 'پر برېښليک بنديز ولګېد',
'blocklist-nousertalk' => 'د خبرواترو خپل مخ نه شی سمولای',
'ipblocklist-empty' => 'د بنديز لړليک تش دی',
'blocklink' => 'بنديز لگول',
'unblocklink' => 'بنديز لرې کول',
'change-blocklink' => 'د بنديز بدلون',
'contribslink' => 'ونډې',
'emaillink' => 'برېښليک لېږل',
'autoblocker' => 'په اتوماتيک ډول ستاسو مخنيوی شوی دا ځکه چې ستاسو IP پته وروستی ځل د "[[User:$1|$1]]" له خوا کارېدلې. او د $1 د مخنيوي سبب دا دی: "$2"',
'blocklogpage' => 'د بنديز يادښت',
'blocklogentry' => 'په [[$1]] بنديز لگېدلی چې د بنديز د پای وخت يې $2 $3 دی',
'unblocklogentry' => 'بنديز ليرې شو $1',
'block-log-flags-anononly' => 'يواځې ورکنومي کارنان',
'block-log-flags-nocreate' => 'د گڼون جوړول ناچارن شوی',
'block-log-flags-noemail' => 'ددې برېښليک مخه نيول شوی',
'block-log-flags-nousertalk' => 'خپل د خبرو اترو مخ نه شي سمولای',
'block-log-flags-hiddenname' => 'پټ کارن-نوم',
'ipb_already_blocked' => 'پر "$1" د پخوا نه بنديز دی',
'ipb-needreblock' => 'پر $1 د پخوا نه بنديز لگېدلی.
آيا تاسې د امستنو بدلول غواړۍ؟',
'ipb-otherblocks-header' => '{{PLURAL:$1|بل بنديز|نور بنديزونه}}',

# Developer tools
'lockdb' => 'توکبنسټ تړل',
'unlockdb' => 'توکبنسټ پرانيستل',
'lockconfirm' => 'هو، زه د توکبنسټ تړل غواړم.',
'unlockconfirm' => 'هو، زه د توکبنسټ پرانيستل غواړم.',
'lockbtn' => 'توکبنسټ تړل',
'unlockbtn' => 'توکبنسټ پرانيستل',
'databasenotlocked' => 'توکبنسټ نه دی تړل شوی.',

# Move page
'move-page' => '$1 لېږدول',
'move-page-legend' => 'مخ لېږدول',
'movepagetext' => "د لاندينۍ فورمې په کارولو سره تاسې د يوه مخ نوم بدلولی شی، چې په همدې توگه به د يوه مخ ټول پېښليک د هغه د نوي نوم سرليک ته ولېږدېږي.
د يوه مخ، پخوانی نوم به د نوي نوم ورگرځونکی مخ وگرځي او نوي سرليک ته به وگرځولی شي.
هغه تړنې چې په زاړه مخ کې دي په هغو کې به هېڅ کوم بدلون را نه شي;
[[Special:BrokenRedirects|د ماتو مخ گرځونو]] يا [[Special:DoubleRedirects|دوه ځلي مخ گرځونو]] د ستونزو د پېښېدو په خاطر ځان ډاډه کړی چې ستاسې مخ گرځونې ماتې يا دوه ځله نه وي.
دا ستاسې پازه ده چې ځان په دې هم ډاډمن کړی چې آيا هغه تړنې کوم چې د يو مخ سره پکار دي چې وي، همداسې په پرله پسې توگه پېيلي او خپل موخن ځايونو سره اړونده دي.

په ياد مو اوسه چې يو مخ به '''هېڅکله''' و نه لېږدېږي که چېرته د پخوا نه په هماغه نوم يو مخ شتون ولري، خو که چېرته يو مخ تش وه او يا هم يوه مخ گرځونه چې پېښليک کې يې بدلون نه وي راغلی. نو دا په دې مانا ده چې تاسې کولای شی چې د يو مخ نوم بېرته هماغه پخواني نوم ته بدل کړی چې د پخوا نه يې درلوده، که چېرته تاسې تېرووځۍ نو په داسې حال کې تاسې نه شی کولای چې د يوه مخ پر سر يو څه وليکۍ.

'''گواښنه!'''
يوه نوي نوم ته د مخونو د نوم بدلون کېدای شي چې په نامتو مخونو کې بنسټيزه او نه اټکل کېدونکی بدلونونه رامېنځ ته کړي;
مخکې له دې نه چې پرمخ ولاړ شی، لطفاُ لومړی خپل ځان په دې ډاډه کړی چې تاسې ددغې کړنې په پايلو ښه پوهېږۍ.",
'movepagetext-noredirectfixer' => "د لاندينۍ فورمې په کارولو سره تاسې د يوه مخ نوم بدلولی شی، چې په همدې توگه به د يوه مخ ټول پېښليک د هغه د نوي نوم سرليک ته ولېږدېږي.
د يوه مخ، پخوانی نوم به د نوي نوم ورگرځونکی مخ وگرځي او نوي سرليک ته به وگرځولی شي.

[[Special:BrokenRedirects|د ماتو مخ گرځونو]] يا [[Special:DoubleRedirects|دوه ځلي مخ گرځونو]] د ستونزو د پېښېدو په خاطر ځان ډاډه کړی چې ستاسې مخ گرځونې ماتې يا دوه ځله نه وي.
دا ستاسې پازه ده چې ځان په دې هم ډاډمن کړی چې آيا هغه تړنې کوم چې د يو مخ سره پکار دي چې وي، همداسې په پرله پسې توگه پېيلي او خپل د موخې ځايونو سره اړونده دي که نه.

په ياد مو اوسه چې يو مخ به '''هېڅکله''' و نه لېږدېږي که چېرته د پخوا نه په هماغه نوم يو بل مخ شتون ولري، خو که چېرته يو مخ تش وه او يا هم يوه مخ گرځونه چې پېښليک کې يې بدلون نه وي راغلی. نو دا په دې مانا ده چې تاسې کولای شی چې د يو مخ نوم بېرته هماغه پخواني نوم ته بدل کړی چې د پخوا نه يې درلوده، که چېرته تاسې تېرووځۍ نو په داسې حال کې تاسې نه شی کولای چې د يوه مخ پر سر يو څه وليکۍ.

'''گواښنه!'''
يوه نوي نوم ته د مخونو د نوم بدلون کېدای شي چې په نامتو مخونو کې بنسټيزه او نه اټکل کېدونکي بدلونونه رامېنځ ته کړي; مخکې له دې نه چې پرمخ ولاړ شی، لطفاُ لومړی خپل ځان په دې ډاډه کړی چې تاسې ددغې کړنې په پايلو ښه پوهېږۍ.",
'movepagetalktext' => "همدې مخ ته اړونده د خبرواترو مخ هم په اتوماتيک ډول لېږدول کېږي '''خو که چېرته:'''
*په نوي نوم د پخوا نه د خبرواترو يو مخ شتون ولري، او يا هم
*تاسې ته لاندې ورکړ شوی څلورڅنډی په نښه شوی وي.

نو په هغه وخت کې پکار ده چې د خبرواترو د مخ لېږدونه او د نوي مخ سره د يوځای کولو کړنه په لاسي توگه ترسره کړی.",
'movearticle' => 'مخ لېږدول',
'moveuserpage-warning' => "'''گواښنه:''' تاسې د يو کارن مخ د لېږدولو په حال کې ياست. لطفاً دا مه هېروۍ چې يوازې همدا مخ به ولېږدول شي او د کارن نوم به ''نه'' بدلېږي.",
'movenologin' => 'غونډال کې نه ياست ننوتي',
'movenologintext' => 'ددې لپاره چې يو مخ ولېږدوی، نو تاسې بايد يو ثبت شوی کارن او غونډال کې [[Special:UserLogin|ننوتي]] اوسۍ.',
'movenotallowed' => 'تاسې د مخونو د لېږدولو پرېښله نلرۍ.',
'movenotallowedfile' => 'تاسې د دوتنو د لېږدولو پرېښله نلرۍ.',
'cant-move-user-page' => 'تاسې د کارن مخونو د لېږدولو پرېښله نلرۍ (د څېرمه مخونو نه پرته).',
'cant-move-to-user-page' => 'تاسې د يو کارن مخ ته د يوه بل مخ د لېږدولو پرېښله نلرۍ (د يو کارن د څېرمه مخ نه پرته).',
'newtitle' => 'يو نوي سرليک ته:',
'move-watch' => 'همدا مخ کتل',
'movepagebtn' => 'مخ لېږدول',
'pagemovedsub' => 'لېږدول په برياليتوب سره ترسره شوه',
'movepage-moved' => '\'\'\'د "$1" په نامه دوتنه، "$2" ته ولېږدېده\'\'\'',
'movepage-moved-redirect' => 'يو مخ گرځونی جوړ شو.',
'articleexists' => 'په همدې نوم يوه بله پاڼه د پخوا نه شته او يا خو دا نوم چې تاسې ټاکلی سم نه دی. لطفاً يو بل نوم وټاکۍ.',
'talkexists' => "'''همدا مخ په برياليتوب سره نوي سرليک ته ولېږدېده، خو د خبرواترو مخ يې و نه لېږدول شو دا ځکه چې نوی سرليک له پخوا نه ځانته د خبرواترو يو مخ لري.
لطفاً د خبرواترو دا دواړه مخونه په لاسي توگه سره يو ځای کړی.'''",
'movedto' => 'ته ولېږدول شو',
'movetalk' => 'د خبرو اترو اړونده مخ ورسره لېږدول',
'movelogpage' => 'د لېږدولو يادښت',
'movelogpagetext' => 'دا لاندې د لېږدول شوو مخونو لړليک دی.',
'movesubpage' => '{{PLURAL:$1|څېرمه مخ|څېرمه مخونه}}',
'movesubpagetext' => 'همدا مخ $1 {{PLURAL:$1|څېرمه مخ لري چې لاندې ښودل شوی|څېرمه مخونه لري چې لاندې ښودل شوي}}.',
'movenosubpage' => 'دا مخ کوم څېرمه مخونه نه لري.',
'movereason' => 'سبب:',
'revertmove' => 'په څټ گرځول',
'delete_and_move' => 'ړنگول او لېږدول',
'delete_and_move_confirm' => 'هو, دا مخ ړنگ کړه',
'immobile-source-page' => 'دا مخ نه لېږدېدنونکی دی',
'imageinvalidfilename' => 'د موخنې دوتنې نوم سم نه دی',
'move-leave-redirect' => 'يو ورگرځونکی مخ پر ځای پرېښودل',
'move-over-sharedrepo' => '== دوتنه شته ==
د [[:$1]] دوتنه په يو گډ زېرمتون کې شته. دې نوم ته د يوې دوتنې لېږدون به د گډې دوتنې د باطلېدلو سبب شي.',

# Export
'export' => 'مخونه صادرول',
'exportall' => 'ټول مخونه صادرول',
'export-submit' => 'صادرول',
'export-addcattext' => 'مخونو د ورگډولو وېشنيزه:',
'export-addcat' => 'ورگډول',
'export-addnstext' => 'د نوم-تشيال نه مخونه ورگډول:',
'export-addns' => 'ورگډول',
'export-download' => 'د دوتنې په بڼه خوندي کول',
'export-templates' => 'کينډۍ نغاړل',

# Namespace 8 related
'allmessages' => 'د غونډال پيغامونه',
'allmessagesname' => 'نوم',
'allmessagesdefault' => 'تلواليزه پيغام متن',
'allmessagescurrent' => 'اوسنی پيغام متن',
'allmessagestext' => 'دا د مېډياويکي په نوم-تشيال کې د غونډال د پيغامونو لړليک دی.
که چېرته تاسې د ميډياويکي په ځايتابه کې ونډې ترسره کول غواړۍ نو لطفاً [//www.mediawiki.org/wiki/Localisation د ميډياويکي ځايتابه] او [//translatewiki.net translatewiki.net] څخه ليدنه وکړۍ.',
'allmessagesnotsupportedDB' => "'''Special:Allmessages''' ترېنه کار نه اخيستل کېږي ځکه چې '''\$wgUseDatabaseMessages''' مړ دی.",
'allmessages-filter-legend' => 'چاڼگر',
'allmessages-filter-unmodified' => 'نابدلېدلي',
'allmessages-filter-all' => 'ټول',
'allmessages-filter-modified' => 'بدلېدلي',
'allmessages-prefix' => 'د مختاړي پر بنسټ اړونده چاڼگر:',
'allmessages-language' => 'ژبه:',
'allmessages-filter-submit' => 'ورځه',

# Thumbnails
'thumbnail-more' => 'لويول',
'filemissing' => 'دوتنه ورکه ده',
'thumbnail_error' => 'د  بټنوک د جوړېدنې ستونزه: $1',

# Special:Import
'import-interwiki-source' => 'سرچينيز ويکي/مخ:',
'import-interwiki-history' => 'د دې مخ د پېښليک ټولې بڼې لمېسل',
'import-interwiki-templates' => 'ټولې کينډۍ نغاړل',
'import-interwiki-namespace' => 'د موخې نوم-تشيال:',
'import-upload-filename' => 'د دوتنې نوم:',
'import-comment' => 'تبصره:',
'import-revision-count' => '$1 {{PLURAL:$1|بڼه|بڼې}}',

# Import log
'importlogpage' => 'د واردولو يادښت',
'import-logentry-upload-detail' => '$1 {{PLURAL:$1|بڼه|بڼې}}',

# JavaScriptTest
'javascripttest' => 'د جاوا سکرېپټ آزمېښت',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'ستاسې کارن مخ',
'tooltip-pt-mytalk' => 'ستاسې د خبرواترو مخ',
'tooltip-pt-preferences' => 'زما غوره توبونه',
'tooltip-pt-watchlist' => 'د هغه مخونو لړليک چې تاسې يې د بدلون لپاره څاری',
'tooltip-pt-mycontris' => 'ستاسې د ونډو لړليک',
'tooltip-pt-login' => 'تاسې ته په غونډال کې د ننوتلو سپارښتنه کوو، که څه هم چې دا يو اړين کار نه دی.',
'tooltip-pt-anonlogin' => 'تاسو ته په غونډال کې د ننوتلو سپارښتنه کوو، که څه هم چې دا يو اړين کار نه دی.',
'tooltip-pt-logout' => 'وتل',
'tooltip-ca-talk' => 'د مخ د مېنځپانگې په اړه خبرې اترې',
'tooltip-ca-edit' => 'تاسې همدا مخ سمولای شی. لطفاً د ليکنې د خوندي کولو دمخه، د همدې ليکنې مخليدنه وگورۍ.',
'tooltip-ca-addsection' => 'يوه نوې برخه پيلول',
'tooltip-ca-viewsource' => 'دا مخ ژغورل شوی. تاسې کولای شی چې د دې مخ سرجينه وگورۍ.',
'tooltip-ca-history' => 'د دې مخ پخوانۍ بڼې',
'tooltip-ca-protect' => 'دا مخ ژغورل',
'tooltip-ca-unprotect' => 'د دې مخ ژغورنه بدلول',
'tooltip-ca-delete' => 'دا مخ ړنگول',
'tooltip-ca-move' => 'همدا مخ لېږدول',
'tooltip-ca-watch' => 'دا مخ په خپل کتنلړکې گډول',
'tooltip-ca-unwatch' => 'همدا مخ خپل کتنلړ نه لرې کول',
'tooltip-search' => '{{SITENAME}} پلټل',
'tooltip-search-go' => 'په دې نوم د کټ مټ ورته مخ شتون په صورت کې، هماغه مخ ته ورځه',
'tooltip-search-fulltext' => 'په مخونو کې دا متن وپلټه',
'tooltip-p-logo' => 'لومړي مخ ته ورتلل',
'tooltip-n-mainpage' => 'لومړي مخ ته ورتلل',
'tooltip-n-mainpage-description' => 'آرنی مخ کتل',
'tooltip-n-portal' => 'د پروژې په اړه، تاسې څه شيان او چېرته کولای شی چې وې مومۍ',
'tooltip-n-currentevents' => 'د اوسنيو پېښو اړونده د هغوی د شاليد مالومات موندل',
'tooltip-n-recentchanges' => 'په ويکي کې د وروستي بدلونو لړليک.',
'tooltip-n-randompage' => 'يو ناټاکلی مخ ښکاره کوي',
'tooltip-n-help' => 'د موندلو ځای',
'tooltip-t-whatlinkshere' => 'د ويکي د ټولو هغو مخونو لړليک چې همدې مخ سره تړنې لري',
'tooltip-t-recentchangeslinked' => 'له دې مخ سره د تړل شويو مخونو وروستي بدلونونه',
'tooltip-feed-rss' => 'د همدې مخ د آر اس اس کتنه',
'tooltip-feed-atom' => 'د دې مخ د اټوم کتنې',
'tooltip-t-contributions' => 'د دې کارن د ونډو لړليک کتل',
'tooltip-t-emailuser' => 'دې کارن ته يو برېښليک لېږل',
'tooltip-t-upload' => 'دوتنې پورته کول',
'tooltip-t-specialpages' => 'د ټولو ځانگړو پاڼو لړليک',
'tooltip-t-print' => 'د دې مخ چاپي بڼه',
'tooltip-t-permalink' => 'د دې مخ د همدې بڼې تلپاتې تړنه',
'tooltip-ca-nstab-main' => 'د مخ مېنځپانگه کتل',
'tooltip-ca-nstab-user' => 'د کارن پاڼه کتل',
'tooltip-ca-nstab-media' => 'د رسنۍ مخ کتل',
'tooltip-ca-nstab-special' => 'دا يو ځانگړی مخ دی، تاسې په دې مخ کې سمون نه شی کولای.',
'tooltip-ca-nstab-project' => 'د پروژې مخ کتل',
'tooltip-ca-nstab-image' => 'د دوتنې مخ کتل',
'tooltip-ca-nstab-mediawiki' => 'د غونډال پيغامونه کتل',
'tooltip-ca-nstab-template' => 'کينډۍ کتل',
'tooltip-ca-nstab-help' => 'د لارښود مخ کتل',
'tooltip-ca-nstab-category' => 'د وېشنيزې مخ ښکاره کول',
'tooltip-minoredit' => 'دا لکه يوه وړه سمونه په نښه کوي[alt-i]',
'tooltip-save' => 'ستاسې بدلونونه خوندي کوي',
'tooltip-preview' => 'ستاسې بدلونونه ښکاره کوي, لطفاً دا کړنه د خوندي کولو دمخه وکاروۍ! [alt-p]',
'tooltip-diff' => 'دا هغه بدلونونه چې تاسې په متن کې ترسره کړي، ښکاره کوي. [alt-v]',
'tooltip-compareselectedversions' => 'د همدې مخ د دوو ټاکل شويو بڼو تر مېنځ توپيرونه وگورۍ.',
'tooltip-watch' => 'دا مخ ستاسې کتنلړ کې ورگډوي [alt-w]',
'tooltip-upload' => 'د پورته کولو پيل',
'tooltip-rollback' => 'په همدې مخ کې "په شابېول" د وروستني ونډوال سمون (سمونونه) په يوه کلېک په څټ ورګرځوي.',
'tooltip-undo' => '"ناکړ" همدا سمون پر شا گرځوي او د سمون کړکۍ د مخکتنې په بڼه پرانيزي.
دا کړنه د لنډيز په برخه کې د سمونونو د سببونو د ورگډولو آسانتيا برابروي.',
'tooltip-preferences-save' => 'غوره توبونه خوندي کول',
'tooltip-summary' => 'يو لنډ لنډيز کښل',

# Stylesheets
'vector.css' => '/* د CSS هره بڼه چې دلته ځای پر ځای کېږي هغه به د وېکټور د پوښ ټولو کارنانو لپاره کار کوي */',

# Scripts
'vector.js' => '/* د جاوا هر يو سکرېپټ چې دلته ځای پر ځای کېږي هغه به د وېکټور د پوښ ټولو کارنانو لپاره کار کوي */',

# Attribution
'anonymous' => 'د {{SITENAME}} {{PLURAL:$1|ورکنومی کارن|ورکنومي کارنان}}',
'siteuser' => 'د {{SITENAME}} کارن $1',
'anonuser' => 'د {{SITENAME}} ورکنومی کارن $1',
'lastmodifiedatby' => 'دا مخ وروستی ځل د $3 لخوا په $2، $1 بدلون موندلی.',
'others' => 'نور',
'siteusers' => 'د {{SITENAME}} {{PLURAL:$2|کارن|کارنان}} $1',
'anonusers' => 'د {{SITENAME}} {{PLURAL:$2|ورکنومی کارن|ورکنومي کارنان}} $1',
'creditspage' => 'د دې مخ کرېډټونه',

# Info page
'pageinfo-title' => 'د "$1" مالومات',
'pageinfo-header-basic' => 'بنسټيز مالومات',
'pageinfo-header-edits' => 'د سمون پېښليک',
'pageinfo-header-restrictions' => 'مخ ژغورنه',
'pageinfo-header-properties' => 'د مخ ځانتياوې',
'pageinfo-display-title' => 'ښکارېدونکی سرليک',
'pageinfo-length' => 'مخ اوږدوالی (په بايټونو)',
'pageinfo-article-id' => 'د مخ پېژند',
'pageinfo-language' => 'د مخ د مېنځپانگې ژبه',
'pageinfo-robot-policy' => 'د پلټن ماشين دريځ',
'pageinfo-robot-index' => 'ليکلړوړ',
'pageinfo-robot-noindex' => 'ليکلړوړ نه',
'pageinfo-views' => 'د کتنو شمېر',
'pageinfo-watchers' => 'د مخ د کتونکو شمېر',
'pageinfo-redirects-name' => 'دې مخ ته د ورگرځونو شمېر',
'pageinfo-subpages-name' => 'دې مخ ته څېرمه مخونه',
'pageinfo-firstuser' => 'مخ جوړونکی',
'pageinfo-firsttime' => 'د مخ جوړېدنې نېټه',
'pageinfo-lastuser' => 'وروستنی سمونگر',
'pageinfo-edits' => 'د ټولو سمونونو شمېر',
'pageinfo-toolboxlink' => 'د مخ مالومات',
'pageinfo-redirectsto-info' => 'مالومات',
'pageinfo-contentpage' => 'مېنځپانگيز مخ کې شمېرل شوی',
'pageinfo-contentpage-yes' => 'هو',
'pageinfo-protect-cascading-yes' => 'هو',
'pageinfo-category-files' => 'د دوتنو شمېر',

# Skin names
'skinname-cologneblue' => 'شين کلون',
'skinname-monobook' => 'مونوبوک',
'skinname-modern' => 'نوی',
'skinname-vector' => 'وېکټور',

# Patrolling
'markaspatrolleddiff' => 'دا مخ څارل شوی په نخښه کول',
'markaspatrolledtext' => 'دا مخ څارل شوی په نخښه کول',
'markedaspatrolled' => 'دا مخ څارل شوی په نخښه کول',

# Image deletion
'filedeleteerror-short' => 'د دوتنې د ړنگولو ستونزه: $1',
'filedeleteerror-long' => 'د دوتنې په ړنگولو کې تېروتنې پېښې شوې:

$1',

# Browsing diffs
'previousdiff' => 'تېر توپير ←',
'nextdiff' => 'بل توپير →',

# Media information
'thumbsize' => 'د بټنوک کچه:',
'widthheightpage' => '$1 × $2, $3 {{PLURAL:$3|مخ|مخونه}}',
'file-info-size' => '$1 × $2 پېکسل, د دوتنې کچه: $3, MIME بڼه: $4',
'file-nohires' => 'تر دې کچې لوړې بېلن نښې نشته.',
'svg-long-desc' => 'SVG دوتنه، نومېنلي $1 × $2 پېکسل، د دوتنې کچه: $3',
'show-big-image' => 'بشپړ بېلن نښې',
'file-info-gif-frames' => '$1 {{PLURAL:$1|چوکاټ|چوکاټونه}}',
'file-info-png-repeat' => '$1 {{PLURAL:$1|ځل|ځله}} وغږېده',
'file-info-png-frames' => '$1 {{PLURAL:$1|چوکاټ|چوکاټونه}}',

# Special:NewFiles
'newimages' => 'د نوو دوتنو انځورتون',
'imagelisttext' => "دلته لاندې د '''$1''' {{PLURAL:$1|دوتنه|دوتنې}} يو لړليک دی چې اوډل شوي $2.",
'newimages-summary' => 'همدا ځانگړی مخ، وروستنۍ پورته شوې دوتنې ښکاره کوي.',
'newimages-legend' => 'چاڼگر',
'newimages-label' => 'د دوتنې نوم (يا د دې برخه):',
'showhidebots' => '($1 روباټ)',
'noimages' => 'د کتلو لپاره څه نشته.',
'ilsubmit' => 'پلټل',
'bydate' => 'د نېټې له مخې',
'sp-newimages-showfrom' => 'هغه نوې دوتنې چې په $1 په $2 بجو پيلېږي ښکاره کول',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'hours-abbrev' => '$1ګ',
'seconds' => '{{PLURAL:$1|$1 ثانيه|$1 ثانيې}}',
'minutes' => '{{PLURAL:$1|$1 دقيقه|$1 دقيقې}}',
'hours' => '{{PLURAL:$1|$1 ساعت|$1 ساعتونه}}',
'days' => '{{PLURAL:$1|$1 ورځ|$1 ورځې}}',
'months' => '{{PLURAL:$1|$1 مياشت|$1 مياشتې}}',
'years' => '{{PLURAL:$1|$1 کال|$1 کالونه}}',
'ago' => '$1 دمخه',
'just-now' => 'همدا اوس',

# Bad image list
'bad_image_list' => 'بڼه يې په لاندې توگه ده:

يواځې د لړليک توکي (هغه کرښې چې پېلېږي پر *) په پام کې نيول شوي.
بايد چې په يوه کرښه کې لومړنۍ تړنه د يوې خرابې دوتنې سره وي.
په يوې کرښې باندې هر ډول وروستۍ تړنې به د استثنا په توگه وگڼلای شي، د ساري په توگه هغه مخونو کې چې يوه دوتنه پر کرښه پرته وي.',

# Metadata
'metadata' => 'مېټاډاټا',
'metadata-help' => 'همدا دوتنه نور اضافه مالومات هم لري، چې کېدای شي ستاسې د گڼياليزې کامرې او يا هم د ځيرڅار په کارولو سره د گڼيالېدنې په وخت کې ورسره مل شوي.
که همدا دوتنه د خپل آرني دريځ څخه بدله شوې وي نو ځينې تفصيلونه به په بدل شوي دوتنه کې په بشپړه توگه نه وي.',
'metadata-expand' => 'غځېدلی تفصيل ښکاره کړی',
'metadata-collapse' => 'غځېدلی تفصيل پټ کړی',
'metadata-fields' => 'د انځور مېټاډاټا ډگرونه چې لړليک يې په همدې پيغام کې په لاندې توگه راغلی د انځور مخ په ښکارېدنه کې به هغه وخت ورگډ شي کله چې د مېټاډاټا لښتيال غځېږي.
نور څه به په تلواليزه توگه پټ پاتې وي.
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
'exif-imagewidth' => 'سوروالی',
'exif-imagelength' => 'لوړوالی',
'exif-datetime' => 'د دوتنې د بدلون وخت او نېټه',
'exif-imagedescription' => 'انځور سرليک',
'exif-make' => 'د کامرې جوړونکی',
'exif-model' => 'د کامرې ماډل',
'exif-software' => 'کارېدلې ساوترۍ',
'exif-artist' => 'ليکوال',
'exif-copyright' => 'د رښتو خاوند',
'exif-colorspace' => 'رنگ تشيال',
'exif-pixelydimension' => 'د انځور سور',
'exif-pixelxdimension' => 'د انځور جګوالی',
'exif-usercomment' => 'د کارن تبصرې',
'exif-relatedsoundfile' => 'اړونده غږيزه دوتنه',
'exif-datetimedigitized' => 'د گڼياليز کېدنې وخت او نېټه',
'exif-fnumber' => 'F شمېره',
'exif-lightsource' => 'د رڼا سرچينه',
'exif-flash' => 'فلش',
'exif-focallength' => 'د عدسيې کانوني واټن',
'exif-flashenergy' => 'د فلش انرژي',
'exif-filesource' => 'د دوتنې سرچينه',
'exif-whitebalance' => 'د سپين رنگ توازن',
'exif-gpsaltituderef' => 'د لوړوالي سرچينه',
'exif-gpsaltitude' => 'لوړوالی',
'exif-gpsspeedref' => 'د سرعت يوون',
'exif-gpsimgdirection' => 'د انځور لوری',
'exif-gpsareainformation' => 'د جي پي اس د سيمې نوم',
'exif-gpsdatestamp' => 'د جي پي اس نېټه',
'exif-jpegfilecomment' => 'د JPEG دوتنې تبصرې',
'exif-keywords' => 'آروييونه',
'exif-worldregiondest' => 'د نړۍ ښکاره شوې سيمه',
'exif-countrydest' => 'ښکاره شوی هېواد',
'exif-countrycodedest' => 'هېوادنی کوډ ښوول شوی',
'exif-provinceorstatedest' => 'ولايت يا ايالت ښوول شوی',
'exif-citydest' => 'ښکاره شوی ښار',
'exif-objectname' => 'لنډ سرليک',
'exif-headline' => 'سرليک',
'exif-source' => 'سرچينه',
'exif-contact' => 'د اړيکو مالومات',
'exif-writer' => 'ليکوال',
'exif-languagecode' => 'ژبه',
'exif-iimcategory' => 'وېشنيزه',
'exif-datetimeexpires' => 'مه يې کاروۍ وروسته له',
'exif-datetimereleased' => 'خپرېدلی په',
'exif-identifier' => 'پېژندنه',
'exif-lens' => 'کارېدلې لېنز',
'exif-serialnumber' => 'د کامرې پرله پسې شمېره',
'exif-cameraownername' => 'د کامرې خاوند',
'exif-label' => 'نښکه',
'exif-copyrighted' => 'د رښتو دريځ',
'exif-copyrightowner' => 'د رښتو خاوند',
'exif-usageterms' => 'د کارولو شرايط',
'exif-pngfilecomment' => 'د PNG دوتنې تبصره',
'exif-disclaimer' => 'ردادعاليک',
'exif-giffilecomment' => 'د GIF دوتنې تبصره',

'exif-copyrighted-true' => 'په رښتو سمبال',
'exif-copyrighted-false' => 'د خپراوي د رښتو دريځ نه دی ټاکل شوی',

'exif-unknowndate' => 'نامالومه نېټه',

'exif-orientation-1' => 'نورمال',

'exif-componentsconfiguration-0' => 'نشته دی',

'exif-exposureprogram-2' => 'نورماله پروګرام',

'exif-subjectdistance-value' => '$1 متره',

'exif-meteringmode-0' => 'ناجوت',
'exif-meteringmode-1' => 'منځالی',
'exif-meteringmode-5' => 'مخبېلگه',
'exif-meteringmode-255' => 'نور',

'exif-lightsource-0' => 'ناجوت',
'exif-lightsource-1' => 'د ورځې رڼا',
'exif-lightsource-4' => 'فلش',
'exif-lightsource-9' => 'ښه هوا',
'exif-lightsource-10' => 'ورېځ پوښلې هوا',
'exif-lightsource-11' => 'سيوری',
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

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'کيلومتر',
'exif-gpsdestdistance-m' => 'مايلونه',
'exif-gpsdestdistance-n' => 'سمندري مايلونه',

'exif-gpsdop-good' => 'ښه ($1)',
'exif-gpsdop-moderate' => 'منځوی ($1)',
'exif-gpsdop-fair' => 'نه ښه نه بد ($1)',
'exif-gpsdop-poor' => 'خراب ($1)',

'exif-objectcycle-a' => 'يوازې ګهيځ',
'exif-objectcycle-p' => 'يوازې ماښام',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'سم لوری',
'exif-gpsdirection-m' => 'مقناطيسي لوری',

'exif-ycbcrpositioning-1' => 'منځنی',

'exif-dc-contributor' => 'ونډه وال',
'exif-dc-date' => 'نېټه (نېټې)',
'exif-dc-publisher' => 'خپرونکی',
'exif-dc-relation' => 'اړونده رسنۍ',
'exif-dc-rights' => 'رښتې',
'exif-dc-source' => 'د سرچينې رسنۍ',
'exif-dc-type' => 'د رسنۍ ډول',

'exif-rating-rejected' => 'رد شوی',

'exif-isospeedratings-overflow' => 'له 65535 لوی',

'exif-iimcategory-ace' => 'هنر، فرهنګ او تفريح',
'exif-iimcategory-clj' => 'جنايت او قانون',
'exif-iimcategory-dis' => 'غميزې او پېښې',
'exif-iimcategory-fin' => 'وټپوهنه او سوداګري',
'exif-iimcategory-edu' => 'زده کړې',
'exif-iimcategory-evn' => 'چاپېريال',
'exif-iimcategory-hth' => 'روغتيا',
'exif-iimcategory-hum' => 'بشري لېوالتيا',
'exif-iimcategory-lab' => 'کار',
'exif-iimcategory-lif' => 'ژوندتوګه او فارغ وختونه',
'exif-iimcategory-pol' => 'سياست',
'exif-iimcategory-rel' => 'دين او ګروهه',
'exif-iimcategory-sci' => 'ساينس او تخنيک',
'exif-iimcategory-soi' => 'ټولنيزې چارې',
'exif-iimcategory-spo' => 'سپورت',
'exif-iimcategory-war' => 'جګړه، تاوتريخوالی، او نارامي',
'exif-iimcategory-wea' => 'هوا',

'exif-urgency-normal' => 'نورمال ($1)',
'exif-urgency-low' => 'لږ ($1)',
'exif-urgency-high' => 'ډېر ($1)',

# External editor support
'edit-externally' => 'د باندنيو پروګرامونو په کارولو سره دا دوتنه سمول',
'edit-externally-help' => 'د نورو مالوماتو لپاره [//www.mediawiki.org/wiki/Manual:External_editors د امستنو لارښوونې] وگورۍ.',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'ټول',
'namespacesall' => 'ټول',
'monthsall' => 'ټول',
'limitall' => 'ټول',

# Email address confirmation
'confirmemail' => 'د برېښليک پتې پخلی وکړی',
'confirmemail_noemail' => 'تاسې يوه سمه برېښليک پته نه ده ثبته کړې مهرباني وکړی [[Special:Preferences|د کارن غوره توبونه]] مو بدل کړۍ.',
'confirmemail_send' => 'يو تاييدي کوډ لېږل',
'confirmemail_sent' => 'تاييدي برېښليک ولېږل شو.',
'confirmemail_oncreate' => 'ستاسې برېښليک پتې ته يو تاييدي کوډ درولېږل شو.
که تاسې غونډال ته ورننوځی نو تاسې دې کوډ ته اړتيا نه لرۍ، خو تاسې هغه وخت همدې درلېږل شوي کوډ ته اړتيا لرۍ، کله چې په ويکي کې د برېښليک پر بنسټ نورې کړنې چارنول غواړی.',
'confirmemail_needlogin' => 'ددې لپاره چې ستاسې د برېښليک پتې پخلی وشي، تاسې ته پکار ده چې $1.',
'confirmemail_loggedin' => 'اوس ستاسې د برېښليک پتې پخلی وشو.',
'confirmemail_error' => 'ستاسې د برېښليک پتې د تاييد په خوندي کولو کې يوه ستونزه رامېنڅ ته شوه.',
'confirmemail_subject' => 'د {{SITENAME}} د برېښليک پتې تاييد',
'confirmemail_body' => 'يو چا او يا هم کيدای شي چې تاسې پخپله، د $1 IP پتې نه،
د "$2" په نامه يو گڼون په همدې بريښليک پتې د {{SITENAME}} په وېبځي کې ثبت کړی.

دا چې موږ د دې پخلی وکړو چې آيا همدا گڼون په رښتيا ستاسې دی او د دې لپاره چې د همدې برېښليک لپاره په {{SITENAME}} وېبځي کې کړنې فعاله کړو، نو پخپل کتنمل کې لاندينۍ تړنه پرانيزۍ:

$3

که چېرته تاسې همدا گڼون *نه وي ثبت کړی*، نو د برېښليک پتې د پخلي د ناگارلو لپاره همدا لاندې تړنه وڅارۍ:

$5

همدا تاييدي شفر به په $4 پای ته ورسېږي او تر همدې مودې وروسته به نور و نه چلېږي.',

# Scary transclusion
'scarytranscludetoolong' => '[URL مو ډېر اوږد دی]',

# Delete conflict
'recreate' => 'بياجوړول',

# action=purge
'confirm_purge_button' => 'ښه',
'confirm-purge-top' => 'په رښتيا د همدې مخ حافظه سپينول غواړۍ؟',

# action=watch/unwatch
'confirm-watch-button' => 'ښه',
'confirm-unwatch-button' => 'ښه',

# Separators for various lists, etc.
'percent' => '$1%',
'parentheses' => '($1)',
'brackets' => '[$1]',

# Multipage image navigation
'imgmultipageprev' => '← پخوانی مخ',
'imgmultipagenext' => 'راتلونکی مخ →',
'imgmultigo' => 'ورځه!',
'imgmultigoto' => 'د $1 مخ ته ورځه',

# Table pager
'ascending_abbrev' => 'ختند',
'descending_abbrev' => 'مخښکته',
'table_pager_next' => 'بل مخ',
'table_pager_prev' => 'تېر مخ',
'table_pager_first' => 'لومړی مخ',
'table_pager_last' => 'وروستی مخ',
'table_pager_limit' => 'په يوه مخ $1 توکي ښکاره کړی',
'table_pager_limit_label' => 'په هر مخ د توکو شمېر:',
'table_pager_limit_submit' => 'ورځه',
'table_pager_empty' => 'بې پايلو',

# Auto-summaries
'autosumm-blank' => 'مخ تش شو',
'autosumm-replace' => "دا مخ د '$1' پرځای راوستل",
'autoredircomment' => '[[$1]] ته وګرځولی شو',
'autosumm-new' => 'د "$1" تورو مخ جوړ شو',

# Size units
'size-bytes' => '$1 بايټ',
'size-kilobytes' => '$1 کيلوبايټ',
'size-megabytes' => '$1 مېگابايټ',
'size-gigabytes' => '$1 ګېګابايټ',
'size-terabytes' => '$1 ټېرابايټ',
'size-petabytes' => '$1 پېبي بايټ',
'size-exabytes' => '$1 اېکسبي بايټ',
'size-zetabytes' => '$1 زېبي بايټ',
'size-yottabytes' => '$1 يوبي بايټ',

# Live preview
'livepreview-loading' => 'برسېرېدنې کې دی...',
'livepreview-ready' => 'برسېرېدنه ... چمتو ده!',

# Watchlist editor
'watchlistedit-noitems' => 'ستاسې کتنلړ کې هېڅ کوم سرليک نشته.',
'watchlistedit-normal-title' => 'کتنلړ سمول',
'watchlistedit-normal-legend' => 'د کتنلړ نه سرليکونه لرې کول',
'watchlistedit-normal-submit' => 'سرليکونه لرې کول',
'watchlistedit-normal-done' => '{{PLURAL:$1|1 سرليک ستاسې له کتنلړ نه ليري شو|$1 سرليکونه ستاسې له کتنلړ نه ليري شوه}}:',
'watchlistedit-raw-title' => 'خام کتنلړ سمول',
'watchlistedit-raw-legend' => 'خام کتنلړ سمول',
'watchlistedit-raw-titles' => 'سرليکونه:',
'watchlistedit-raw-submit' => 'کتنلړ اوسمهاله کول',
'watchlistedit-raw-done' => 'ستاسې کتنلړ اوسمهاله شو.',
'watchlistedit-raw-added' => '{{PLURAL:$1|1 سرليک ورگډ شو|$1 سرليکونه ورگډ شوه}}:',
'watchlistedit-raw-removed' => '{{PLURAL:$1|1 سرليک ليرې شو|$1 سرليکونه ليري شوه}}:',

# Watchlist editing tools
'watchlisttools-view' => 'اړونده بدلونونه کتل',
'watchlisttools-edit' => 'کتنلړ ليدل او سمول',
'watchlisttools-raw' => 'خام کتنلړ سمول',

# Iranian month names
'iranian-calendar-m1' => 'وری',
'iranian-calendar-m2' => 'غويی',
'iranian-calendar-m3' => 'غبرګولی',
'iranian-calendar-m4' => 'چنګاښ',
'iranian-calendar-m5' => 'زمری',
'iranian-calendar-m6' => 'وږی',
'iranian-calendar-m7' => 'تله',
'iranian-calendar-m8' => 'لړم',
'iranian-calendar-m9' => 'ليندۍ',
'iranian-calendar-m10' => 'مرغومی',
'iranian-calendar-m11' => 'سلواغه',
'iranian-calendar-m12' => 'کب',

# Hijri month names
'hijri-calendar-m1' => 'محرم',
'hijri-calendar-m2' => 'صفر',
'hijri-calendar-m3' => 'ربيع الاول',
'hijri-calendar-m4' => 'ربيع الثاني',
'hijri-calendar-m5' => 'جمادى الاولى',
'hijri-calendar-m6' => 'جمادى الثانية',
'hijri-calendar-m7' => 'رجب',
'hijri-calendar-m8' => 'شعبان',
'hijri-calendar-m9' => 'رمضان',
'hijri-calendar-m10' => 'شوال',
'hijri-calendar-m11' => 'ذو القعدة',
'hijri-calendar-m12' => 'ذو الحجة',

# Hebrew month names
'hebrew-calendar-m1' => 'تيشري',
'hebrew-calendar-m2' => 'حشوان',
'hebrew-calendar-m3' => 'كيسليف',
'hebrew-calendar-m4' => 'تيفيت',
'hebrew-calendar-m5' => 'شيفات',
'hebrew-calendar-m6' => 'آدار',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|خبرې اترې]])',

# Core parser functions
'duplicate-defaultsort' => '\'\'\'گواښنه:\'\'\'د "$2" تلواليزه اوډون تڼۍ تر دې پخوا ټاکلې تلواليزه اوډون تڼۍ "$1" پر ځای چارنه کېږي.',

# Special:Version
'version' => 'بڼه',
'version-extensions' => 'لگېدلي شاتاړي',
'version-specialpages' => 'ځانگړي مخونه',
'version-skins' => 'پوښۍ',
'version-other' => 'بل',
'version-version' => '(بڼه $1)',
'version-license' => 'منښتليک',
'version-poweredby-credits' => "دا ويکي د '''[//www.mediawiki.org/ مېډياويکي]''' په سېک چلېږي، ټولې رښتې خوندي دي © 2001-$1 $2.",
'version-poweredby-others' => 'نور',
'version-license-info' => 'مېډياويکي يو وړيا ساوتری دی؛ تاسې يې په ډاډه زړه د GNU د ټولگړو کارېدنو د منښتليک چې د وړيا ساوتريو د بنسټ له مخې خپور شوی، خپرولی او/يا بدلولی شی؛ د منښتليک ۲ بڼه او يا (ستاسې د خوښې) هر يوه وروستۍ بڼه.

مېډياويکي د ښه کارېدنې په نيت خپور شوی، خو د ضمني سوداگريز او يا د کوم ځانگړي کار د ضمانت نه پرته. د نورو مالوماتو لپاره د GNU د ټولگړو کارېدنو منښتليک وگورۍ.

تاسې بايد د دې پروگرام سره يو [{{SERVER}}{{SCRIPTPATH}}/COPYING a copy of the GNU General Public License] ترلاسه کړی وي؛ که داسې نه وي، نو د وړيا ساوتريو بنسټ، Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA ته يو ليک وليکۍ، او يا يې [//www.gnu.org/licenses/old-licenses/gpl-2.0.html پرليکه ولولۍ].',
'version-software' => 'نصب شوی ساوتری',
'version-software-product' => 'اېبره',
'version-software-version' => 'بڼه',

# Special:FileDuplicateSearch
'fileduplicatesearch' => 'د دوه گونو دوتنو پلټنه',
'fileduplicatesearch-legend' => 'د دوه گونو دوتنو پلټنه',
'fileduplicatesearch-filename' => 'د دوتنې نوم:',
'fileduplicatesearch-submit' => 'پلټل',
'fileduplicatesearch-info' => '<span dir="ltr">$1 × $2</span> پېکسل<br />د دوتنې کچه: $3<br />ډول MIME: $4',
'fileduplicatesearch-result-1' => '"$1" بله کټ مټ ورته غبرګونې دوتنه نلري.',
'fileduplicatesearch-noresults' => 'د "$1" په نوم دوتنه و نه موندل شوه.',

# Special:SpecialPages
'specialpages' => 'ځانگړي مخونه',
'specialpages-note' => '----
* نورماله ځانگړي مخونه.
* <strong class="mw-specialpagerestricted">محدوده ځانگړي مخونه.</strong>
* <span class="mw-specialpagecached">رانيولي ځانگړي مخونه (کېدای شي منسوخ شوی وي).</span>',
'specialpages-group-maintenance' => 'د څارنې راپورونه',
'specialpages-group-other' => 'نور ځانگړي مخونه',
'specialpages-group-login' => 'ننوتل / گڼون جوړول',
'specialpages-group-changes' => 'وروستي بدلونونه او يادښتونه',
'specialpages-group-media' => 'د رسنۍ راپورونه او پورته کېدنې',
'specialpages-group-users' => 'کارنان او رښتې',
'specialpages-group-highuse' => 'ډېر کارېدونکي مخونه',
'specialpages-group-pages' => 'د مخونو لړليک',
'specialpages-group-pagetools' => 'د مخ اوزارونه',
'specialpages-group-wiki' => 'توکي او اوزارونه',

# Special:BlankPage
'blankpage' => 'تش مخ',
'intentionallyblankpage' => 'همدا مخ په لوی لاس تش پرېښودل شوی دی',

# External image whitelist
'external_image_whitelist' => ' #دا کرښه چې څنگه ده، همداسې پرېږدۍ<pre>
#لاندې د منظمو اصطلاحگانو ټوټې (يوازې هغه برخه چې د // په مېنځ کې ليکلې) ځای پر ځای کړی
#دا به د باندنيو انځورونو د يو آر اېل (hotlinked) سره مطابقه شي 
#هغه څه چې مطابقت لري هغه به د انځورونو په توگه ښکاره شي، کوم چې مطابقت نلري نو يوازې د انځور تړنه به ښکاره کېږي
#هغه کرښې چې په # پيل کېږي د تبصرو په توگه په نظر کې نيول کېږي
#دا کرښې د غټو تورو او وړو تورو سره حساسې نه دي

#ټولې regex ټوټې د دغې کرښې نه پورته ځای پر ځای کړی. دا کرښه چې څنگه ده، همداسې يې پرېږدۍ</pre>',

# Special:Tags
'tag-filter' => '[[Special:Tags|نښلن]] چاڼگر:',
'tag-filter-submit' => 'چاڼگر',
'tags-display-header' => 'د بدلون په لړليکونو کې ښکارېدنه',
'tags-description-header' => 'د مانا بشپړه څرگندونه',
'tags-active-header' => 'فعال؟',
'tags-active-yes' => 'هو',
'tags-active-no' => 'نه',
'tags-edit' => 'سمول',
'tags-hitcount' => '$1 {{PLURAL:$1|بدلون|بدلونونه}}',

# Special:ComparePages
'comparepages' => 'مخونه پرتلل',
'compare-selector' => 'د مخ بڼې سره پرتلل',
'compare-page1' => '۱ مخ',
'compare-page2' => '۲ مخ',
'compare-rev1' => '۱ بڼه',
'compare-rev2' => '۲ بڼه',
'compare-submit' => 'پرتلل',

# Database error messages
'dberr-header' => 'دا ويکي يوه ستونزه لري',
'dberr-problems' => 'اوبخښۍ! دم مهال دا وېبپاڼه د تخنيکي ستونزو سره مخامخ شوې.',
'dberr-usegoogle' => 'تاسې کولای شی چې هم مهاله د گووگل له لخوا هم د پلټنې هڅه وکړۍ.',

# HTML forms
'htmlform-invalid-input' => 'ستاسې ځينې ورکړېينې ستونزې لري',
'htmlform-select-badoption' => 'څه چې تاسې ځانگړي کړل هغه د منلو وړ خوښنه نه ده.',
'htmlform-int-invalid' => 'کوم څه چې تاسو ځانگړي کړي هغه يوه سمه شمېره نه ده.',
'htmlform-float-invalid' => 'کوم څه چې تاسو ځانگړي کړي هغه يوه شمېره نه ده.',
'htmlform-int-toolow' => 'کوم ارزښت چې تاسې ځانگړی کړی هغه تر $1 لږ دی',
'htmlform-int-toohigh' => 'کوم ارزښت چې تاسې ځانگړی کړی هغه تر $1 ډېر  دی',
'htmlform-required' => 'دې ارزښت ته اړتيا ده',
'htmlform-submit' => 'سپارل',
'htmlform-reset' => 'بدلونونه ناکړل',
'htmlform-selectorother-other' => 'بل',
'htmlform-no' => 'نه',
'htmlform-yes' => 'هو',

# New logging system
'logentry-delete-delete' => '$1 د $3 مخ {{GENDER:$2|ړنگ کړ}}',
'revdelete-content-hid' => 'مېنځپانگه پټېدلې',
'revdelete-uname-hid' => 'کارن نوم پټ شوی',
'revdelete-content-unhid' => 'مېنځپانگه ښکاره شوی',
'revdelete-uname-unhid' => 'ښکاره کارن-نوم',
'logentry-move-move' => '$1 د $3 مخ $4 ته {{GENDER:$2|ولېږداوه}}',
'logentry-move-move-noredirect' => '$1 پرته له دې چې يو مخ گرځونی پرېږدي له $3 څخه $4 ته مخ {{GENDER:$2|ولېږداوه}}',
'logentry-move-move_redir-noredirect' => '$1 پرته له دې چې يو مخ گرځونی پرېږدي له $3 څخه $4 ته مخ {{GENDER:$2|ولېږداوه}}',
'logentry-newusers-newusers' => 'د $1 کارن گڼون {{GENDER:$2|جوړ شو}}',
'logentry-newusers-create' => 'د $1 کارن گڼون {{GENDER:$2|جوړ شو}}',
'logentry-newusers-autocreate' => 'د $1 گڼون په اتوماتيک ډول {{GENDER:$2|جوړ شو}}',
'rightsnone' => '(هېڅ)',

# Feedback
'feedback-subject' => 'سکالو:',
'feedback-message' => 'پيغام:',
'feedback-cancel' => 'ناگارل',
'feedback-close' => 'ترسره شو',

# Search suggestions
'searchsuggest-search' => 'پلټل',

# API errors
'api-error-duplicate-popup-title' => 'غبرګونې {{PLURAL:$1|دوتنه|دوتنې}}.',
'api-error-empty-file' => 'کومه دوتنه چې تاسې دلته سپارلې هغه تشه ده.',
'api-error-file-too-large' => 'کومه دوتنه چې تاسې دلته سپارلې ډېره لويه ده.',
'api-error-filename-tooshort' => 'د دوتنې نوم ډېر لنډ دی.',
'api-error-filetype-banned' => 'په دې ډول دوتنې بنديز دی.',
'api-error-illegal-filename' => 'د دوتنې نوم نه دی پرېښل شوی.',
'api-error-mustbeloggedin' => 'د دوتنو د پورته کولو لپاره بايد تاسې غونډال کې ننوتلی اوسۍ.',
'api-error-unclassified' => 'يوه ناڅرګنده تېروتنه رامېنځته شوه.',
'api-error-unknown-code' => 'ناڅرګنده تېروتنه: "$1"',
'api-error-unknown-warning' => 'ناڅرگنده گواښنه: "$1".',
'api-error-unknownerror' => 'ناڅرګنده تېروتنه: "$1".',

# Durations
'duration-seconds' => '$1 {{PLURAL:$1|ثانيه|ثانيې}}',
'duration-minutes' => '$1 {{PLURAL:$1|دقيقه|دقيقې}}',
'duration-hours' => '$1 {{PLURAL:$1|ساعت|ساعتونه}}',
'duration-days' => '$1 {{PLURAL:$1|ورځ|ورځې}}',
'duration-weeks' => '$1 {{PLURAL:$1|اونۍ|اونۍ}}',
'duration-years' => '$1 {{PLURAL:$1|کال|کالونه}}',
'duration-decades' => '$1 {{PLURAL:$1|لسيزه|لسيزې}}',
'duration-centuries' => '$1 {{PLURAL:$1|پېړۍ|پېړۍ}}',
'duration-millennia' => '$1 {{PLURAL:$1|زرمه|زرمې}}',

);

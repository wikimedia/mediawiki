<?php
/** Pashto (پښتو)
 *
 * @addtogroup Language
 *
 * @author SPQRobin
 * @author Ahmed-Najib-Biabani-Ibrahimkhel
 * @author לערי ריינהארט
 * @author Siebrand
 */

$specialPageAliases = array(
	'Shortpages'                => array( 'لنډ_مخونه' ),
	'Longpages'                 => array( 'اوږده_مخونه' ),
	'Newpages'                  => array( 'نوي_مخونه' ),
	'Protectedpages'            => array( 'ژغورلي_مخونه' ),
	'Protectedtitles'           => array( 'ژغورلي_سرليکونه' ),
	'Allpages'                  => array( 'ټول_مخونه' ),
	'Categories'                => array( 'وېشنيزې' ),
	'Mypage'                    => array( 'زما_پاڼه' ),
	'Search'                    => array( 'لټون' ),
);

$skinNames = array(
	'standard'    => 'کلاسيک',
	'nostalgia'   => 'نوستالژي',
	'cologneblue' => 'شين کلون',
	'monobook'    => 'مونوبوک',
	'myskin'      => 'زمابڼه',
	'chick'       => 'شيک',
	'simple'      => 'ساده',
	'modern'      => 'نوی',
);

$namespaceNames = array(
	NS_MEDIA          => 'رسنۍ',
	NS_SPECIAL        => 'ځانګړی',
	NS_TALK           => 'خبرې_اترې',
	NS_USER           => 'کارونکی',
	NS_USER_TALK      => 'د_کارونکي_خبرې_اترې',
	# NS_PROJECT set by \$wgMetaNamespace
	NS_PROJECT_TALK   => 'د_$1_خبرې_اترې',
	NS_IMAGE          => 'انځور',
	NS_IMAGE_TALK     => 'د_انځور_خبرې_اترې',
	NS_MEDIAWIKI      => 'ميډياويکي',
	NS_MEDIAWIKI_TALK => 'د_ميډياويکي_خبرې_اترې',
	NS_TEMPLATE       => 'کينډۍ',
	NS_TEMPLATE_TALK  => 'د_کينډۍ_خبرې_اترې',
	NS_HELP           => 'لارښود',
	NS_HELP_TALK      => 'د_لارښود_خبرې_اترې',
	NS_CATEGORY       => 'وېشنيزه',
	NS_CATEGORY_TALK  => 'د_وېشنيزې_خبرې_اترې',
);

$magicWords = array(
	'currentmonth'        => array( 'اوسنۍمياشت' ),
	'currentmonthname'    => array( 'داوسنۍمياشت-نوم' ),
	'currentday'          => array( 'ننۍورځ' ),
	'currentday2'         => array( 'ننۍورځ۲' ),
	'currentdayname'      => array( 'دننۍورځې-نوم' ),
	'currentyear'         => array( 'سږکال' ),
	'currenttime'         => array( 'دم-مهال' ),
	'currenthour'         => array( 'دم-ګړۍ' ),
	'localmonth'          => array( 'سيمه-يزه-مياشت' ),
	'localmonthname'      => array( 'دسيمه-يزې-مياشتې-نوم' ),
	'pagename'            => array( 'دپاڼې-نوم' ),
	'pagenamee'           => array( 'دپاڼې-نومم' ),
	'namespace'           => array( 'نوم-تشيال' ),
	'namespacee'          => array( 'نوم-تشيالل' ),
	'img_right'           => array( 'ښي' ),
	'img_left'            => array( 'کيڼ' ),
	'server'              => array( 'پالنګر' ),
	'servername'          => array( 'دپالنګرنوم' ),
	'language'            => array( '#ژبه:' ),
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
'tog-hideminor'            => 'په وروستيو بدلونو کې وړې سمادېدنې پټول',
'tog-rememberpassword'     => 'زما پټنوم پدې کمپيوټر په ياد ولره!',
'tog-watchcreations'       => 'هغه مخونه چې زه يې جوړوم، زما کتلي لړليک کې ورګډ کړه',
'tog-watchdefault'         => 'هغه مخونه چې زه يې سمادوم، زما کتلي لړليک کې ورګډ کړه',
'tog-watchmoves'           => 'هغه مخونه چې زه يې لېږدوم، زما کتلي لړليک کې ورګډ کړه',
'tog-watchdeletion'        => 'هغه مخونه چې زه يې ړنګوم، زما کتلي لړليک کې ورګډ کړه',
'tog-enotifwatchlistpages' => 'په مخونو کې د بدلون سره دې ما ته برېښليک ولېږلای شي.',
'tog-enotifusertalkpages'  => 'کله چې زما د خبرو اترو په مخ کې بدلون پېښېږي نو ما ته دې يو برېښليک ولېږلی شي.',
'tog-enotifminoredits'     => 'که په مخونو کې وړې سمادېدنې هم کېږي نو ماته دې برېښليک ولېږل شي.',
'tog-ccmeonemails'         => 'هغه برېښليکونه چې زه يې نورو ته لېږم، د هغو يوه کاپي دې ماته هم راشي',
'tog-showhiddencats'       => 'پټې وېشنيزې ښکاره کول',

'underline-always' => 'تل',
'underline-never'  => 'هېڅکله',

'skinpreview' => '(مخکتنه)',

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

# Bits of text used by many pages
'categories'               => 'وېشنيزې',
'pagecategories'           => '{{PLURAL:$1|وېشنيزه|وېشنيزې}}',
'category_header'          => 'د "$1" په وېشنيزه کې شته مخونه',
'subcategories'            => 'وړې-وېشنيزې',
'category-media-header'    => '"$1" رسنۍ په وېشنيزه کې',
'category-empty'           => "''تر اوسه پورې همدا وېشنيزه هېڅ کوم مخ يا کومه رسنيزه دوتنه نلري.''",
'hidden-categories'        => '{{PLURAL:$1|پټه وېشنيزه|پټې وېشنيزې}}',
'hidden-category-category' => 'پټې وېشنيزې', # Name of the category where hidden categories will be listed

'mainpagetext' => "<big>'''MediaWiki په برياليتوب سره نصب شو.'''</big>",

'about'          => 'په اړه',
'article'        => 'د منځپانګې مخ',
'newwindow'      => '(په نوې کړکۍ کې پرانيستل کېږي)',
'cancel'         => 'کوره کول',
'qbfind'         => 'موندل',
'qbedit'         => 'سمادول',
'qbpageoptions'  => 'همدا مخ',
'qbmyoptions'    => 'زما پاڼې',
'qbspecialpages' => 'ځانګړي پاڼې',
'moredotdotdot'  => 'نور ...',
'mypage'         => 'زما پاڼه',
'mytalk'         => 'زما خبرې اترې',
'anontalk'       => 'ددې IP لپاره خبرې اترې',
'navigation'     => 'ګرځېدنه',
'and'            => 'او',

'errorpagetitle'    => 'تېروتنه',
'returnto'          => 'بېرته $1 ته وګرځه.',
'tagline'           => 'د {{SITENAME}} لخوا',
'help'              => 'لارښود',
'search'            => 'لټون',
'searchbutton'      => 'لټون',
'go'                => 'ورځه',
'searcharticle'     => 'ورځه',
'history'           => 'د مخ پېښليک',
'history_short'     => 'پېښليک',
'info_short'        => 'مالومات',
'printableversion'  => 'د چاپ بڼه',
'permalink'         => 'تلپاتې تړن',
'print'             => 'چاپ',
'edit'              => 'سمادول',
'editthispage'      => 'دا مخ سماد کړی',
'delete'            => 'ړنګول',
'deletethispage'    => 'دا مخ ړنګ کړه',
'protect'           => 'ژغورل',
'protect_change'    => 'د ژغورنې بدلون',
'protectthispage'   => 'همدا مخ ژغورل',
'unprotect'         => 'نه ژغورل',
'unprotectthispage' => 'همدا مخ نه ژغورل',
'newpage'           => 'نوی مخ',
'talkpage'          => 'په همدې مخ خبرې اترې کول',
'talkpagelinktext'  => 'خبرې اترې',
'specialpage'       => 'ځانګړې پاڼه',
'personaltools'     => 'شخصي اوزار',
'postcomment'       => 'يوه تبصره ليکل',
'articlepage'       => 'د مخ مېنځپانګه ښکاره کول',
'talk'              => 'خبرې اترې',
'views'             => 'کتنې',
'toolbox'           => 'اوزاربکس',
'userpage'          => 'د کاروونکي پاڼه ښکاره کول',
'projectpage'       => 'د پروژې مخ ښکاره کول',
'imagepage'         => 'د انځورونو مخ کتل',
'mediawikipage'     => 'د پيغامونو مخ کتل',
'templatepage'      => 'د کينډۍ مخ ښکاره کول',
'viewhelppage'      => 'د لارښود مخ کتل',
'categorypage'      => 'د وېشنيزې مخ کتل',
'viewtalkpage'      => 'خبرې اترې کتل',
'otherlanguages'    => 'په نورو ژبو کې',
'lastmodifiedat'    => 'دا مخ وروستی ځل په $2، $1 بدلون موندلی.', # $1 date, $2 time
'viewcount'         => 'همدا مخ {{PLURAL:$1|يو وار|$1 واره}} کتل شوی.',
'protectedpage'     => 'ژغورلی مخ',
'jumpto'            => 'ورټوپ کړه:',
'jumptonavigation'  => 'ګرځېدنه',
'jumptosearch'      => 'لټون',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'د {{SITENAME}} په اړه',
'aboutpage'         => 'پروژه:په اړه',
'copyright'         => 'دا مېنځپانګه د $1 له مخې ستاسو لاس رسي لپاره دلته ده.',
'copyrightpage'     => 'Project:د رښتو حق',
'currentevents'     => 'اوسنۍ پېښې',
'currentevents-url' => 'اوسنۍ پېښې',
'disclaimers'       => 'ردادعاليکونه',
'disclaimerpage'    => 'پروژه:ټولګړی ردادعاليک',
'edithelp'          => 'د لارښود سماد',
'helppage'          => 'لارښود:لړليک',
'mainpage'          => 'لومړی مخ',
'policy-url'        => 'پروژه:تګلاره',
'portal'            => 'ټولګړی ورټک',
'portal-url'        => 'Project:ټولګړی ورټک',
'privacy'           => 'د محرميت تګلاره',
'privacypage'       => 'پروژه:د محرميت_تګلاره',
'sitesupport'       => 'بسپنې',

'badaccess'        => 'د لاسرسۍ تېروتنه',
'badaccess-group0' => 'تاسو د غوښتل شوې کړنې د ترسره کولو اجازه نه لرۍ.',
'badaccess-group1' => 'د کومې کړنې غوښتنه چې تاسو کړې د $1 د ډلې کارونکو پورې محدوده ده.',
'badaccess-group2' => 'د کومې کړنې غوښتنه چې تاسو کړې د هغو کارونکو پورې محدوده ده کوم چې يو د $1 د ډلې څخه دي.',
'badaccess-groups' => 'د کومې کړنې غوښتنه چې تاسو کړې د هغو کارونکو پورې محدوده ده کوم چې يو د $1 د ډلې څخه دي.',

'ok'                      => 'ښه/هو',
'youhavenewmessages'      => 'تاسو $1 لری  ($2).',
'newmessageslink'         => 'نوي پيغامونه',
'newmessagesdifflink'     => 'وروستی بدلون',
'youhavenewmessagesmulti' => 'ستاسو لپاره په $1 کې نوي پېغام راغلي.',
'editsection'             => 'سمادول',
'editold'                 => 'سمادول',
'editsectionhint'         => 'د سمادلو برخه: $1',
'toc'                     => 'نيوليک',
'showtoc'                 => 'ښکاره کول',
'hidetoc'                 => 'پټول',
'feed-rss'                => 'آر اس اس',
'red-link-title'          => '$1 (تر اوسه پورې نه دی ليکل شوی)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'ليکنه',
'nstab-user'      => 'د کارونکي پاڼه',
'nstab-media'     => 'د رسنۍ مخ',
'nstab-special'   => 'ځانګړی',
'nstab-project'   => 'د پروژې مخ',
'nstab-image'     => 'دوتنه',
'nstab-mediawiki' => 'پيغام',
'nstab-template'  => 'کينډۍ',
'nstab-help'      => 'لارښود',
'nstab-category'  => 'وېشنيزه',

# Main script and global functions
'nosuchaction'      => 'هېڅ داسې کومه کړنه نشته',
'nosuchspecialpage' => 'داسې هېڅ کوم ځانګړی مخ نشته',
'nospecialpagetext' => "<big>'''تاسو د يو ناسم ځانګړي مخ غوښتنه کړې.'''</big>

تاسو کولای شی چې د سمو ځانګړو مخونو لړليک په [[ځانګړی:ځانګړې پاڼې]] کې ومومۍ.",

# General errors
'error'                => 'تېروتنه',
'databaseerror'        => 'د ډاټابېز تېروتنه',
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
'viewsource'           => 'سرچينې کتل',
'viewsourcefor'        => 'د $1 لپاره',
'protectedpagetext'    => 'همدا مخ د سمادولو د مخنيوي په تکل تړل شوی دی.',
'viewsourcetext'       => 'تاسو د همدغه مخ توکي او سرچينې کتلی او لمېسلی شی:',
'namespaceprotected'   => "تاسو ته د '''$1''' په نوم-تشيال کې د مخونو د سمادولو اجازه نشته.",

# Login and logout pages
'logouttitle'                => 'کارن-حساب نه وتنه',
'logouttext'                 => '<strong>تاسو اوس د غونډال نه ووتلی.</strong><br />
تاسو کولای شی چې پرته د کارن-نوم نه {{SITENAME}} په ورکنومي توګه وکاروی، او يا هم تاسو کولای شی چې په همدې کارن-نوم يا په کوم بل کارن-نوم خپلې ليکنې خپرې کړی. يادونه دې وي چې ځينې مخونو کې به تاسو لا تر اوسه پورې غونډال کې ننوتي ښکاری، تر څو تاسو د خپل کتنمل حافظه نه وي سپينه کړی.',
'welcomecreation'            => '==$1 ښه راغلاست! ==

ستاسو کارن-حساب جوړ شو. لطفاً د {{SITENAME}} لپاره خپلې خوښونې او غورتوبونه بدلول مه هېروی.',
'loginpagetitle'             => 'کارن-حساب ته ننوتنه',
'yourname'                   => 'کارن-نوم:',
'yourpassword'               => 'پټنوم:',
'yourpasswordagain'          => 'پټنوم بيا وليکه',
'remembermypassword'         => 'زما پټنوم پدې کمپيوټر په ياد ولره!',
'loginproblem'               => '<b>همدې غونډال ته ستاسو په ننوتنه کې يوه ستونزه راپېښه شوه!</b><br />بيا يې وآزمويۍ!',
'login'                      => 'ننوتنه',
'loginprompt'                => 'په {{SITENAME}} کې د ننوتنې لپاره، تاسو بايد خپل د کمپيوټر کوکيز (cookies) <br>چارن کړۍ.',
'userlogin'                  => 'ننوتنه / کارن-نوم جوړول',
'logout'                     => 'وتنه',
'userlogout'                 => 'وتنه',
'notloggedin'                => 'غونډال کې نه ياست ننوتي',
'nologin'                    => 'کارن نوم نه لرې ؟ $1.',
'nologinlink'                => 'يو کارن-حساب جوړول',
'createaccount'              => 'کارن-حساب جوړول',
'gotaccount'                 => 'آيا وار دمخې يو کارن-حساب لری؟ $1.',
'gotaccountlink'             => 'ننوتنه',
'createaccountmail'          => 'د برېښليک له مخې',
'badretype'                  => 'دا پټنوم چې تاسو ليکلی د پخواني پټنوم سره ورته نه دی.',
'userexists'                 => 'کوم کارن نوم چې تاسو ورکړی هغه بل چا کارولی. لطفاً يو بل ډول نوم وټاکۍ.',
'youremail'                  => 'برېښليک *',
'username'                   => 'کارن نوم:',
'uid'                        => 'د کارونکي پېژندنه:',
'yourrealname'               => 'اصلي نوم:',
'yourlanguage'               => 'ژبه:',
'yournick'                   => 'کورنی نوم:',
'email'                      => 'برېښليک',
'prefs-help-realname'        => 'د اصلي نوم ليکل ستاسو په خوښه دی خو که تاسو خپل اصلي نوم وټاکۍ پدې سره به ستاسو ټول کارونه او ونډې ستاسو د نوم په اړوندولو کې وکارېږي.',
'loginerror'                 => 'د ننوتنې ستونزه',
'prefs-help-email'           => 'د برېښليک ليکل ستاسو په خوښه دی، خو په ورکړې سره به يې نور کارونکي پدې وتوانېږي چې ستاسو سره د کارن-نوم او يا هم د کارونکي خبرې اترې لخوا، پرته له دې چې ستاسو پېژندنه وشي، اړيکې ټينګې کړي.',
'prefs-help-email-required'  => 'ستاسو د برېښليک پته پکار ده.',
'noname'                     => 'تاسو تر اوسه پورې کوم کره کارن نوم نه دی ځانګړی کړی.',
'loginsuccesstitle'          => 'ننوتنه برياليتوب سره ترسره شوه',
'loginsuccess'               => "'''تاسو اوس {{SITENAME}} کې د \"\$1\" په نوم ننوتي ياست.'''",
'nosuchuser'                 => 'د "$1" په نامه هېڅ کوم کارونکی نه شته. مهرباني وکړی خپل ټاپ کړی نوم وګوری چې سم مو ليکلی او که نه، او يا هم  که تاسو غواړی نو په همدې نوم يو نوی کارن-حساب جوړ کړی.',
'nosuchusershort'            => 'د "<nowiki>$1</nowiki>" په نوم هېڅ کوم کارن-حساب نشته. لطفاً خپل د نوم ليکلې بڼې ته ځير شی چې پکې تېروتنه نه وي.',
'nouserspecified'            => 'تاسو ځان ته کوم کارن نوم نه دی ځانګړی کړی.',
'wrongpassword'              => 'ناسم پټنوم مو ليکلی. لطفاً يو ځل بيا يې وليکۍ.',
'wrongpasswordempty'         => 'تاسو پټنوم نه دی ليکلی. لطفاً سر له نوي يې وليکۍ.',
'passwordtooshort'           => 'ستاسو پټنوم ډېر لنډ دی. دا بايد لږ تر لږه $1 توري ولري.',
'mailmypassword'             => 'پټنوم برېښليک کې رالېږل',
'passwordremindertitle'      => 'د {{SITENAME}} لپاره نوی لنډمهاله پټنوم',
'passwordremindertext'       => 'يو چا (کېدای شي چې تاسو، د $1 IP پتې نه)
د {{SITENAME}} ($4) وېبځي لپاره د يوه نوي پټنوم د ورلېږلو غوښتنه کړې.
د "$2" په نوم کارونکي لپاره نوی پټنوم اوس "$3" دی.
تاسو بايد چې اوس غونډال ته په همدغه پټنوم ورننوځی او بيا وروسته خپل پټنوم په خپله خوښه بدل کړی.

که چېرته ستاسو نه پرته کوم بل چا دغه غوښتنه کړې وي او يا هم تاسو ته بېرته خپل پټنوم در پزړه شوی وي او تاسو د خپل د پټنوم د بدلولو هيله نه لری، نو تاسو همدا پيغام بابېزه وګڼی او د پخوا په څېر خپل هماغه پخوانی پټنوم وکاروی.',
'noemail'                    => 'د "$1" کارونکي په نامه هېڅ کومه برېښليک پته نه ده ثبته شوې.',
'passwordsent'               => 'د "$1" په نوم ثبت شوي غړي/غړې لپاره يو نوی پټنوم د هغه/هغې د برېښليک پتې ته ولېږل شو.
لطفاً کله چې پټنوم مو ترلاسه کړ نو بيا غونډال ته ننوځۍ.',
'blocked-mailpassword'       => 'ستاسو په IP پتې بنديز لګېدلی او تاسو نه شی کولای چې ليکنې وکړی، په همدې توګه تاسو نه شی کولای چې د پټنوم د پرځای کولو کړنې وکاروی دا ددې لپاره چې د وراني مخنيوی وشي.',
'mailerror'                  => 'د برېښليک د لېږلو ستونزه: $1',
'acct_creation_throttle_hit' => 'اوبښۍ، تاسو وار دمخې پدغه $1 نوم کارن-حساب جوړ کړی. تاسو نه شی کولای چې نور جوړ کړی.',
'emailauthenticated'         => 'ستاسو برېښليک پته په $1 د منلو وړ وګرځېده.',
'emailnotauthenticated'      => 'ستاسو د برېښليک پته لا تر اوسه پورې د منلو وړ نه ده ګرځېدلې. د اړوندو بېلوونکو نښو په هکله تاسو ته هېڅ کوم برېښليک نه لېږل کېږي.',
'noemailprefs'               => 'ددې لپاره چې دا کړنې کار وکړي نو تاسو يو برېښليک وټاکۍ.',
'emailconfirmlink'           => 'د خپل د برېښليک پتې پخلی وکړی',
'accountcreated'             => 'کارن-حساب مو جوړ شو.',
'accountcreatedtext'         => 'د $1 لپاره يو کارن-حساب جوړ شو.',
'createaccount-title'        => 'د {{SITENAME}} د کارن-حساب جوړېدنه',
'loginlanguagelabel'         => 'ژبه: $1',

# Password reset dialog
'resetpass_bad_temporary' => 'لنډمهالی پټنوم مو سم نه دی. کېدای شي تاسو وار دمخې خپل پټنوم برياليتوب سره بدل کړی وي او يا هم د نوي لنډمهالي پټنوم غوښتنه مو کړې وي.',

# Edit page toolbar
'bold_sample'     => 'روڼ ليک',
'bold_tip'        => 'روڼ ليک',
'italic_sample'   => 'کوږ ليک',
'italic_tip'      => 'کوږ ليک',
'link_sample'     => 'د تړن سرليک',
'link_tip'        => 'کورنی تړن',
'extlink_tip'     => 'باندنۍ تړنې (د http:// مختاړی مه هېروی)',
'headline_sample' => 'سرليک',
'headline_tip'    => 'د ۲ کچې سرليک',
'math_sample'     => 'فورمول دلته ځای کړی',
'math_tip'        => 'شمېرپوهنيز فورمول (LaTeX)',
'image_tip'       => 'خښه شوې دوتنه',
'media_tip'       => 'د رسنيزې دوتنې تړنه',
'sig_tip'         => 'ستاسو لاسليک د وخت د ټاپې سره',

# Edit pages
'summary'                  => 'لنډيز',
'subject'                  => 'سکالو/سرليک',
'minoredit'                => 'دا يوه وړوکې سمادېدنه ده',
'watchthis'                => 'همدا مخ کتل',
'savearticle'              => 'مخ خوندي کول',
'preview'                  => 'مخکتنه',
'showpreview'              => 'مخکتنه',
'showlivepreview'          => 'ژوندۍ مخکتنه',
'showdiff'                 => 'بدلونونه ښکاره کول',
'anoneditwarning'          => "'''يادونه:''' تاسو غونډال ته نه ياست ننوتي. ستاسو IP پته به د دې مخ د سمادولو په پېښليک کې ثبت شي.",
'missingcommenttext'       => 'لطفاً تبصره لاندې وليکۍ.',
'summary-preview'          => 'د لنډيز مخکتنه',
'subject-preview'          => 'موضوع/سرليک مخکتنه',
'blockedtitle'             => 'د کارونکي مخه نيول شوې',
'blockednoreason'          => 'هېڅ سبب نه دی ورکړ شوی',
'blockedoriginalsource'    => "د '''$1''' سرچينې لاندې ښودل شوي:",
'whitelistedittitle'       => 'که د سمادولو تکل لری نو بايد غونډال ته ورننوځۍ.',
'whitelistedittext'        => 'ددې لپاره چې سمادول ترسره کړی تاسو بايد $1.',
'whitelistreadtitle'       => 'د همدغې ليکنې د لوستلو لپاره بايد تاسو غونډال ته ننوځۍ.',
'whitelistreadtext'        => 'که د پاڼو د لوستلو تکل لری نو بايد غونډال کې [[Special:Userlogin|ننوتنه]] ترسره کړۍ.',
'whitelistacctitle'        => 'تاسو د کارن نوم جوړولو اجازه نه لرۍ',
'loginreqtitle'            => 'غونډال کې ننوتنه پکار ده',
'loginreqlink'             => 'ننوتنه',
'loginreqpagetext'         => 'د نورو مخونو د کتلو لپاره تاسو بايد $1 وکړۍ.',
'accmailtitle'             => 'پټنوم ولېږل شو.',
'accmailtext'              => 'د "$1" لپاره پټنوم $2 ته ولېږل شو.',
'newarticle'               => '(نوی)',
'anontalkpagetext'         => "----''دا د بې نومه کارونکو لپاره چې کارن نوم يې نه دی جوړ کړی او يا هم خپل کارن نوم نه دی کارولی، د سکالو پاڼه ده. نو ددې پخاطر مونږ د هغه کارونکي/هغې کارونکې د انټرنېټ شمېره يا IP پته د نوموړي/نوموړې د پېژندلو لپاره کاروو. داسې يوه IP پته د ډېرو کارونکو لخوا هم کارېدلی شي. که تاسو يو بې نومه کارونکی ياست او تاسو ته نااړونده پېغامونه او تبصرې اشاره شوي، نو لطفاً د نورو بې نومو کارونکو او ستاسو ترمېنځ د ټکنتوب مخ نيونې لپاره [[Special:Userlogin|کارن-حساب جوړول يا ننوتنه]] وټوکۍ.''",
'clearyourcache'           => "'''يادونه:''' د غوره توبونو د خوندي کولو وروسته، ددې لپاره چې تاسو خپل سر ته رسولي ونجونه وګورۍ نو پکار ده چې د خپل بروزر ساتل شوې حافظه تازه کړی. د '''Mozilla / Firefox / Safari:''' لپاره د ''Shift'' تڼۍ نيولې وساتی کله مو چې په ''Reload''، ټک واهه، او يا هم ''Ctrl-Shift-R'' تڼۍ کېښکاږۍ (په Apple Mac کمپيوټر باندې ''Cmd-Shift-R'' کېښکاږۍ); '''IE:''' د ''Ctrl'' تڼۍ کېښکاږۍ کله مو چې په ''Refresh'' ټک واهه، او يا هم د ''Ctrl-F5'' تڼۍ کېښکاږۍ; '''Konqueror:''' بروزر کې يواځې ''Reload'' ته ټک ورکړۍ، او يا په ''F5''; د '''Opera''' کارونکو ته پکار ده چې په بشپړه توګه د خپل کمپيوټر ساتل شوې حافظه تازه کړي چې پدې توګه کېږي ''Tools→Preferences''.",
'updated'                  => '(تازه)',
'note'                     => '<strong>يادونه:</strong>',
'previewnote'              => '<strong>دا يواځې مخکتنه ده، تاسو چې کوم بدلونونه ترسره کړي، لا تر اوسه پورې نه دي خوندي شوي!</strong>',
'editing'                  => 'سمادېدنه $1',
'editingsection'           => 'سمادېدنه $1 (برخه)',
'yourtext'                 => 'ستاسو متن',
'yourdiff'                 => 'توپيرونه',
'copyrightwarning'         => 'لطفاً په پام کې وساتۍ چې ټولې هغه ونډې چې تاسو يې {{SITENAME}} کې ترسره کوی هغه د $2 له مخې د خپرولو لپاره ګڼل کېږي (د لانورو تفصيلاتو لپاره $1 وګورۍ). که تاسو نه غواړۍ چې ستاسې په ليکنو کې په بې رحمۍ سره لاسوهنې (سمادېدنې) وشي او د نورو په غوښتنه پسې لانورې هم خپرې شي، نو دلته يې مه ځای پر ځای کوی..<br />
تاسو زمونږ سره دا ژمنه هم کوی چې تاسو پخپله دا ليکنه کښلې، او يا مو د ټولګړو پاڼو او يا هم ورته وړيا سرچينو نه کاپي کړې ده <strong>لطفاً د ليکوال د اجازې نه پرته د خوندي حقونو ليکنې مه خپروی!</strong>',
'longpagewarning'          => '<strong>پاملرنه: همدا مخ $1 کيلوبايټه اوږد دی؛ کېدای شي چې ځينې براوزرونه د ۳۲ کيلوبايټ نه د اوږدو مخونو په سمادونه کې ستونزه رامېنځ ته کړي.
لطفاً د مخ په لنډولو او په وړو برخو وېشلو باندې غور وکړی.</strong>',
'semiprotectedpagewarning' => "'''يادونه:''' همدا مخ تړل شوی دی او يواځې ثبت شوي کارونکي کولای شي چې په دې مخ کې بدلونونه راولي.",
'templatesused'            => 'په دې مخ کارېدلي کينډۍ:',
'templatesusedpreview'     => 'په دې مخکتنه کې کارېدلي کينډۍ:',
'templatesusedsection'     => 'په دې برخه کارېدلي کينډۍ:',
'template-protected'       => '(ژغورل شوی)',
'template-semiprotected'   => '(نيم-ژغورلی)',

# Account creation failure
'cantcreateaccounttitle' => 'کارن-حساب نه شي جوړېدای',

# History pages
'viewpagelogs'        => 'د همدغه مخ يادښتونه کتل',
'nohistory'           => 'ددې مخ لپاره د سمادېدنې هېڅ کوم پېښليک نه شته.',
'currentrev'          => 'اوسنۍ بڼه',
'revisionasof'        => 'د $1 پورې شته مخليدنه',
'previousrevision'    => '← زړه بڼه',
'nextrevision'        => 'نوې بڼه →',
'currentrevisionlink' => 'اوسنۍ بڼه',
'cur'                 => 'اوسنی',
'next'                => 'راتلونکي',
'last'                => 'وروستنی',
'page_first'          => 'لومړنی',
'page_last'           => 'وروستنی',
'deletedrev'          => '[ړنګ شو]',
'histfirst'           => 'پخواني',
'histlast'            => 'تازه',
'historysize'         => '({{PLURAL:$1|1 بايټ|$1 بايټونه}})',
'historyempty'        => '(تش)',

# Revision feed
'history-feed-item-nocomment' => '$1 په $2', # user at time

# Revision deletion
'rev-delundel' => 'ښکاره کول/ پټول',

# Diffs
'history-title'           => 'د "$1" د پېښليک مخليدنه',
'difference'              => '(د بڼو تر مېنځ توپير)',
'lineno'                  => '$1 کرښه:',
'compareselectedversions' => 'ټاکلې بڼې سره پرتله کول',
'editundo'                => 'ناکړ',

# Search results
'searchresults'         => 'د لټون پايلې',
'searchsubtitle'        => "تاسو د '''[[:$1]]''' لپاره لټون کړی",
'searchsubtitleinvalid' => "تاسو د '''$1''' لپاره لټون کړی",
'noexactmatch'          => "'''تر اوسه پورې د \"\$1\" په نوم هېڅ کوم مخ نشته.''' تاسو کولای شی چې [[:\$1|همدا مخ جوړ کړی]].",
'prevn'                 => 'تېر $1',
'nextn'                 => 'راتلونکي $1',
'viewprevnext'          => '($1) ($2) ($3) ښکاره کول',
'powersearch'           => 'لټون',
'powersearchtext'       => 'په نوم-تشيالونو کې لټون:<br />$1<br />$2 لړليک بياګرځول شوی<br />د $3 $9 لټون',

# Preferences page
'preferences'           => 'غوره توبونه',
'mypreferences'         => 'زما غوره توبونه',
'prefs-edits'           => 'د سمادونو شمېر:',
'prefsnologin'          => 'غونډال کې نه ياست ننوتي',
'prefsnologintext'      => 'ددې لپاره چې د کارونکي غوره توبونه وټاکۍ نو تاسو ته پکار ده چې لومړی غونډال کې [[Special:Userlogin|ننوتنه]] ترسره کړی.',
'changepassword'        => 'پټنوم بدلول',
'skin'                  => 'بڼه',
'math'                  => 'شمېرپوهنه',
'dateformat'            => 'د نېټې بڼه',
'datedefault'           => 'هېڅ نه ټاکل',
'datetime'              => 'نېټه او وخت',
'math_unknown_error'    => 'ناجوته ستونزه',
'math_unknown_function' => 'ناجوته کړنه',
'prefs-personal'        => 'د کارونکي پېژنليک',
'prefs-rc'              => 'وروستي بدلونونه',
'prefs-watchlist'       => 'کتلی لړليک',
'prefs-watchlist-days'  => 'د ورځو شمېر چې په کتلي لړليک کې به ښکاري:',
'prefs-misc'            => 'بېلابېل',
'saveprefs'             => 'خوندي کول',
'resetprefs'            => 'بيا سمول',
'oldpassword'           => 'زوړ پټنوم:',
'newpassword'           => 'نوی پټنوم:',
'retypenew'             => 'نوی پټنوم بيا وليکه:',
'textboxsize'           => 'سمادېدنه',
'searchresultshead'     => 'لټون',
'recentchangesdays'     => 'د هغو ورځو شمېر وټاکی چې په وروستي بدلونو کې يې ليدل غواړی:',
'recentchangescount'    => 'د هغو سمادونو شمېر چې په وروستي بدلونو کې يې ليدل غواړی:',
'savedprefs'            => 'ستاسو غوره توبونه خوندي شوه.',
'timezonelegend'        => 'د وخت سيمه',
'localtime'             => 'سيمه ايز وخت',
'servertime'            => 'د پالنګر وخت',
'allowemail'            => 'د نورو کارونکو لخوا د برېښليک رالېږل چارن کړه',
'defaultns'             => 'په دغو نوم-تشيالونو کې د ټاکل شوو سمونونو له مخې لټون وکړی:',
'files'                 => 'دوتنې',

# User rights
'userrights-user-editname'   => 'يو کارن نوم وليکۍ:',
'userrights-editusergroup'   => 'د کاروونکو ډلې سمادول',
'saveusergroups'             => 'د کارونکي ډلې خوندي کول',
'userrights-groupsmember'    => 'غړی د:',
'userrights-groupsavailable' => 'شته ډلې:',
'userrights-reason'          => 'د بدلون سبب:',

# Groups
'group'     => 'ډله:',
'group-all' => '(ټول)',

'grouppage-sysop' => '{{ns:project}}:پازوالان',

# User rights log
'rightslog'  => 'د کارونکي د رښتو يادښت',
'rightsnone' => '(هېڅ نه)',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|بدلون|بدلونونه}}',
'recentchanges'                  => 'وروستي بدلونونه',
'recentchangestext'              => 'په همدې مخ باندې د ويکي ترټولو تازه وروستي بدلونونه وڅارۍ.',
'recentchanges-feed-description' => 'همدلته د ويکي ترټولو تازه وروستي بدلونونه وڅارۍ او وګورۍ چې څه پېښ شوي.',
'rcnote'                         => "دلته لاندې {{PLURAL:$1|وروستی '''1''' بدلون دی|وروستي '''$1''' بدلونونه دي}} چې په {{PLURAL:$2|يوې ورځ|'''$2''' ورځو}}، کې تر $3 پېښ شوي.",
'rcnotefrom'                     => 'په همدې ځای کې لاندې هغه بدلونونه دي چې د <b>$2</b> نه راپدېخوا پېښ شوي(تر <b>$1</b> پورې ښکاره شوي).',
'rclistfrom'                     => 'هغه بدلونونه ښکاره کړی چې له $1 نه پيلېږي',
'rcshowhideminor'                => 'وړې سمادېدنې $1',
'rcshowhidebots'                 => 'bots $1',
'rcshowhideliu'                  => 'غونډال ته ننوتي $1 کارونکي',
'rcshowhideanons'                => 'بې نومه کارونکي $1',
'rcshowhidemine'                 => 'زما سمادېدنې $1',
'rclinks'                        => 'هغه وروستي $1 بدلونونه ښکاره کړی چې په $2 ورځو کې پېښ شوي<br />$3',
'diff'                           => 'توپير',
'hist'                           => 'پېښليک',
'hide'                           => 'پټول',
'show'                           => 'ښکاره کول',
'minoreditletter'                => 'وړه سمادېدنه',
'newpageletter'                  => 'نوی',
'newsectionsummary'              => '/* $1 */ نوې برخه',

# Recent changes linked
'recentchangeslinked'       => 'اړونده بدلونونه',
'recentchangeslinked-title' => '$1 ته اړونده بدلونونه',

# Upload
'upload'               => 'دوتنه پورته کول',
'uploadbtn'            => 'دوتنه پورته کول',
'reupload'             => 'بيا پورته کول',
'uploadnologin'        => 'غونډال کې نه ياست ننوتي',
'uploadnologintext'    => 'ددې لپاره چې دوتنې پورته کړای شۍ، تاسو ته پکار ده چې لومړی غونډال کې [[Special:Userlogin|ننوتنه]] ترسره کړی.',
'uploaderror'          => 'د پورته کولو ستونزه',
'uploadtext'           => "د دوتنو د پورته کولو لپاره د لانديني چوکاټ نه کار واخلۍ، که چېرته غواړۍ چې د پخوانيو پورته شوو انځورونو په اړه لټون وکړۍ او يا يې وکتلای شۍ نو بيا د [[Special:Imagelist|پورته شوو دوتنو لړليک]] ته لاړ شی، د پورته شوو دوتنو او ړنګ شوو دوتنو يادښتونه په [[Special:Log/upload|پورته شوي يادښت]] کې کتلای شی.

ددې لپاره چې يوه مخ ته انځور ورواچوی، نو بيا پدې ډول تړن (لېنک) وکاروی
'''<nowiki>[[Image:File.jpg]]</nowiki>''',
'''<nowiki>[[Image:File.png|alt text]]</nowiki>''' او يا هم د رسنيزو دوتنو لپاره د راساً تړن (لېنک) چې په دې ډول دی
'''<nowiki>[[Media:File.ogg]]</nowiki>''' وکاروی.",
'uploadlogpage'        => 'د پورته شويو دوتنو يادښت',
'uploadlogpagetext'    => 'دا لاندې د نوو پورته شوو دوتنو لړليک دی.',
'filename'             => 'د دوتنې نوم',
'filedesc'             => 'لنډيز',
'fileuploadsummary'    => 'لنډيز:',
'filesource'           => 'سرچينه:',
'uploadedfiles'        => 'پورته شوې دوتنې',
'ignorewarnings'       => 'هر ډول ګواښونه له پامه غورځول',
'minlength1'           => 'پکار ده چې د دوتنو نومونه لږ تر لږه يو حرف ولري.',
'badfilename'          => 'ددغې دوتنې نوم "$1" ته واوړېده.',
'filetype-badmime'     => 'د MIME بڼې "$1" د دوتنو د پورته کولو اجازه نشته.',
'fileexists'           => 'د پخوا نه پدې نوم يوه دوتنه شته، که تاسو ډاډه نه ياست او يا هم که تاسو غواړۍ چې بدلون پکې راولۍ، لطفاً $1 وګورۍ.',
'fileexists-extension' => 'په همدې نوم يوه بله دوتنه د پخوا نه شته:<br />
د پورته کېدونکې دوتنې نوم: <strong><tt>$1</tt></strong><br />
د پخوا نه شته دوتنه: <strong><tt>$2</tt></strong><br />
لطفاً يو داسې نوم وټاکی چې د پخوانۍ دوتنې سره توپير ولري.',
'fileexists-forbidden' => 'د پخوا نه پدې نوم يوه دوتنه شته؛ لطفاً بېرته وګرځۍ او همدغه دوتنه بيا په يوه نوي نوم پورته کړی. [[Image:$1|thumb|center|$1]]',
'savefile'             => 'دوتنه خوندي کړه',
'uploadedimage'        => '"[[$1]]" پورته شوه',
'uploaddisabled'       => 'پورته کول ناچارن شوي',
'uploadvirus'          => 'دا دوتنه ويروس لري! تفصيل: $1',
'sourcefilename'       => 'د سرچينيزې دوتنې نوم:',
'watchthisupload'      => 'همدا مخ کتل',

'upload-file-error' => 'کورنۍ ستونزه',

'nolicense'          => 'هېڅ نه دي ټاکل شوي',
'upload_source_file' => '(ستاسو په کمپيوټر کې يوه دوتنه)',

# Image list
'imagelist'                 => 'د دوتنو لړليک',
'ilsubmit'                  => 'لټون',
'showlast'                  => 'وروستي $1 دوتنې چې د $2 له مخې اوډل شوي راښکاره کول.',
'byname'                    => 'د نوم له مخې',
'bydate'                    => 'د نېټې له مخې',
'bysize'                    => 'د مېچ له مخې',
'imgdelete'                 => 'ړنګول',
'imgfile'                   => 'دوتنه',
'filehist'                  => 'د دوتنې پېښليک',
'filehist-deleteall'        => 'ټول ړنګول',
'filehist-deleteone'        => 'همدا ړنګول',
'filehist-current'          => 'اوسنی',
'filehist-datetime'         => 'نېټه/وخت',
'filehist-user'             => 'کارونکی',
'filehist-filesize'         => 'د دوتنې کچه',
'filehist-comment'          => 'تبصره',
'imagelinks'                => 'تړنونه',
'linkstoimage'              => 'دغه لانديني مخونه د همدې دوتنې سره تړنې لري:',
'nolinkstoimage'            => 'داسې هېڅ کوم مخ نه شته چې د دغې دوتنې سره تړنې ولري.',
'shareduploadwiki'          => 'لطفاً د لا نورو مالوماتو لپاره $1 وګورۍ.',
'shareduploadwiki-linktext' => 'د دوتنې د څرګندونې مخ',
'noimage'                   => 'په دې نوم هېڅ کومه دوتنه نه شته، تاسو کولای شی چې $1.',
'noimage-linktext'          => 'همدا غونډال ته پورته کول',
'uploadnewversion-linktext' => 'د همدغې دوتنې نوې بڼه پورته کول',
'imagelist_date'            => 'نېټه',
'imagelist_name'            => 'نوم',
'imagelist_user'            => 'کارونکی',
'imagelist_size'            => 'کچه (bytes)',
'imagelist_description'     => 'څرګندونه',
'imagelist_search_for'      => 'د انځور د نوم لټون:',

# File reversion
'filerevert-comment' => 'تبصره:',

# File deletion
'filedelete'                  => '$1 ړنګول',
'filedelete-legend'           => 'دوتنه ړنګول',
'filedelete-comment'          => 'تبصره:',
'filedelete-submit'           => 'ړنګول',
'filedelete-success'          => "'''$1''' ړنګ شو.",
'filedelete-otherreason'      => 'بل/اضافه سبب:',
'filedelete-reason-otherlist' => 'بل سبب',

# MIME search
'mimesearch' => 'MIME لټون',
'mimetype'   => 'MIME بڼه:',
'download'   => 'ښکته کول',

# Unwatched pages
'unwatchedpages' => 'ناکتلي مخونه',

# Unused templates
'unusedtemplates'    => 'نه کارېدلي کينډۍ',
'unusedtemplateswlh' => 'نور تړنونه',

# Random page
'randompage'         => 'ناټاکلی مخ',
'randompage-nopages' => 'په همدغه نوم-تشيال کې هېڅ کوم مخ نشته.',

# Statistics
'statistics'             => 'شمار',
'statistics-mostpopular' => 'تر ټولو ډېر کتل شوي مخونه',

'brokenredirects-delete' => '(ړنګول)',

'withoutinterwiki'        => 'د ژبې د تړنو بې برخې مخونه',
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
'mostlinked'              => 'د ډېرو تړنو مخونه',
'mostlinkedcategories'    => 'د ګڼ شمېر تړنو وېشنيزې',
'mostlinkedtemplates'     => 'د ډېرو تړنو کينډۍ',
'mostcategories'          => 'د ګڼ شمېر وېشنيزو لرونکي مخونه',
'mostimages'              => 'د ډېرو تړنو انځورونه',
'mostrevisions'           => 'ډېر کتل شوي مخونه',
'allpages'                => 'ټول مخونه',
'prefixindex'             => 'د مختاړيو ليکلړ',
'shortpages'              => 'لنډ مخونه',
'longpages'               => 'اوږده مخونه',
'deadendpages'            => 'بې پايه مخونه',
'deadendpagestext'        => 'همدا لانديني مخونه په دغه ويکي کې د نورو مخونو سره تړنې نه لري.',
'protectedpages'          => 'ژغورلي مخونه',
'protectedtitles'         => 'ژغورلي سرليکونه',
'listusers'               => 'د کارونکو لړليک',
'specialpages'            => 'ځانګړې پاڼې',
'restrictedpheading'      => 'بنديز لګېدلي ځانګړي مخونه',
'newpages'                => 'نوي مخونه',
'newpages-username'       => 'کارن نوم:',
'ancientpages'            => 'تر ټولو زاړه مخونه',
'move'                    => 'لېږدول',
'movethispage'            => 'دا مخ ولېږدوه',

# Book sources
'booksources'               => 'د کتاب سرچينې',
'booksources-search-legend' => 'د کتابي سرچينو لټون وکړۍ',
'booksources-go'            => 'ورځه',
'booksources-text'          => 'دا لاندې د هغه وېبځايونو د تړنو لړليک دی چېرته چې نوي او زاړه کتابونه پلورل کېږي، او يا هم کېدای شي چې د هغه کتاب په هکله مالومات ولري کوم چې تاسو ورپسې لټېږۍ:',

'categoriespagetext' => 'په دغه ويکي (wiki) کې همدا لاندينۍ وېشنيزې دي.',
'groups'             => 'د کارونکو ډلې',
'alphaindexline'     => '$1 نه تر $2 پورې',
'version'            => 'بڼه',

# Special:Log
'specialloguserlabel'  => 'کارونکی:',
'speciallogtitlelabel' => 'سرليک:',
'log'                  => 'يادښتونه',
'all-logs-page'        => 'ټول يادښتونه',
'log-search-legend'    => 'د يادښتونو لپاره لټون',
'log-search-submit'    => 'ورځه',

# Special:Allpages
'nextpage'          => 'بل مخ ($1)',
'prevpage'          => 'تېر مخ ($1)',
'allpagesfrom'      => 'ښکاره دې شي هغه مخونه چې پېلېږي په:',
'allarticles'       => 'ټول مخونه',
'allinnamespace'    => 'ټول مخونه ($1 نوم-تشيال)',
'allnotinnamespace' => 'ټولې پاڼې (د $1 په نوم-تشيال کې نشته)',
'allpagesprev'      => 'پخواني',
'allpagesnext'      => 'راتلونکي',
'allpagessubmit'    => 'ورځه',
'allpagesprefix'    => 'هغه مخونه ښکاره کړه چې مختاړی يې داسې وي:',
'allpagesbadtitle'  => 'ورکړ شوی سرليک سم نه دی او يا هم د ژبو او يا د بېلابېلو ويکي ګانو مختاړی لري. ستاسو په سرليک کې يو يا څو داسې ابېڅې دي کوم چې په سرليک کې نه شي کارېدلی.',

# Special:Listusers
'listusersfrom'      => 'هغه کارونکي ښکاره کړه چې نومونه يې پېلېږي په:',
'listusers-submit'   => 'ښکاره کول',
'listusers-noresult' => 'هېڅ کوم کارونکی و نه موندل شو.',

# E-mail user
'mailnologin'     => 'هېڅ کومه لېږل شوې پته نشته',
'emailuser'       => 'همدې کارونکي ته برېښليک لېږل',
'emailpage'       => 'کارونکي ته برېښليک ولېږه',
'defemailsubject' => 'د {{SITENAME}} برېښليک',
'noemailtitle'    => 'هېڅ کومه برېښليک پته نشته.',
'emailfrom'       => 'پيغام لېږونکی',
'emailto'         => 'پيغام اخيستونکی',
'emailsubject'    => 'موضوع',
'emailmessage'    => 'پيغام',
'emailsend'       => 'لېږل',
'emailccme'       => 'زما د پيغام يوه بېلګه دې ماته هم برېښليک شي.',
'emailccsubject'  => '$1 ته ستاسو د پيغام لمېسه: $2',
'emailsent'       => 'برېښليک مو ولېږل شو',
'emailsenttext'   => 'ستاسو برېښليکي پيغام ولېږل شو.',

# Watchlist
'watchlist'            => 'زما کتلی لړليک',
'mywatchlist'          => 'زما کتلی لړليک',
'watchlistfor'         => "(د '''$1''')",
'nowatchlist'          => 'ستاسو په کتلي لړليک کې هېڅ نه شته.',
'watchnologin'         => 'غونډال کې نه ياست ننوتي.',
'watchnologintext'     => 'ددې لپاره چې خپل کتل شوي لړليک کې بدلون راولی نو تاسو ته پکار ده چې لومړی غونډال کې [[Special:Userlogin|ننوتنه]] ترسره کړی.',
'addedwatch'           => 'په کتلي لړليک کې ورګډ شو.',
'addedwatchtext'       => "د \"[[:\$1]]\" په نوم يو مخ ستاسو [[Special:Watchlist|کتلي لړليک]] کې ورګډ شو.
په راتلونکې کې چې په دغه مخ او ددغه مخ په اړونده بحث کې کوم بدلونونه راځي نو هغه به ستاسو کتلي لړليک کې وښوولی شي,
او په همدې توګه هغه مخونه به د [[Special:Recentchanges|وروستي بدلونونو]] په لړليک کې په '''روڼ''' ليک ليکل شوی وي ترڅو په اسانۍ سره څوک وپوهېږي چې په کوم کوم مخونو کې بدلونونه ترسره شوي.

که چېرته تاسو بيا وروسته غواړۍ چې کومه پاڼه د خپل کتلي لړليک نه ليرې کړۍ، نو په \"نه کتل\" تڼۍ باندې ټک ورکړۍ.",
'removedwatch'         => 'د کتلي لړليک نه لرې شو',
'removedwatchtext'     => 'د "[[:$1]]" په نامه مخ ستاسو له کتلي لړليک نه لرې شو.',
'watch'                => 'کتل',
'watchthispage'        => 'همدا مخ کتل',
'unwatch'              => 'نه کتل',
'wlheader-enotif'      => 'د برېښليک له لارې خبرول چارن شوی.*',
'wlheader-showupdated' => "* هغه مخونه چې وروستی ځل ستاسو د کتلو نه وروسته بدلون موندلی په '''روڼ''' ليک نښه شوي.",
'wlshowlast'           => 'وروستي $1 ساعتونه $2 ورځې $3 ښکاره کړه',
'watchlist-hide-own'   => 'زما سمادونه پټول',
'watchlist-hide-minor' => 'وړې سمادېدنې پټول',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'د کتلو په حال کې...',
'unwatching' => 'د نه کتلو په حال کې...',

'enotif_newpagetext'           => 'دا يوه نوې پاڼه ده.',
'enotif_impersonal_salutation' => '{{SITENAME}} کارونکی',
'changed'                      => 'بدل شو',
'created'                      => 'جوړ شو',
'enotif_lastvisited'           => 'د ټولو هغو بدلونونو د کتلو لپاره چې ستاسو د وروستي ځل راتګ نه وروسته پېښې شوي، $1 وګورۍ.',
'enotif_lastdiff'              => 'د همدغه بدلون د کتلو لپاره $1 وګورۍ.',
'enotif_anon_editor'           => 'ورکنومی کارونکی $1',

# Delete/protect/revert
'deletepage'                  => 'پاڼه ړنګول',
'confirm'                     => 'تاييد',
'exblank'                     => 'دا مخ تش وه',
'delete-confirm'              => '"$1" ړنګوول',
'delete-legend'               => 'ړنګول',
'historywarning'              => 'پاملرنه: کومه پاڼه چې تاسو يې د ړنګولو هڅه کوی يو پېښليک لري:',
'confirmdeletetext'           => 'تاسو د تل لپار يو مخ يا انځور د هغه ټول پېښليک سره سره د دغه ډېټابېز نه ړنګوۍ. که چېرته تاسو ددغې کړنې په پايلې پوه ياست او د دغې پاڼې د [[پروژې:تګلارې]] سره سمون خوري نو لطفاً ددغې کړنې تاييد وکړی .',
'actioncomplete'              => 'بشپړه کړنه',
'deletedtext'                 => '"<nowiki>$1</nowiki>" ړنګ شوی.
د نوو ړنګ شوو سوانحو لپاره $2 وګورۍ.',
'deletedarticle'              => 'ړنګ شو "[[$1]]"',
'dellogpage'                  => 'د ړنګولو يادښت',
'dellogpagetext'              => 'دا لاندې د نوو ړنګ شوو کړنو لړليک دی.',
'deletionlog'                 => 'د ړنګولو يادښت',
'deletecomment'               => 'د ړنګولو سبب',
'deleteotherreason'           => 'بل/اضافه سبب:',
'deletereasonotherlist'       => 'بل سبب',
'protectlogpage'              => 'د ژغورنې يادښت',
'protectedarticle'            => '"[[$1]]" وژغورلی شو',
'protect-legend'              => 'د ژغورلو پخلی کول',
'protectcomment'              => 'تبصره:',
'protectexpiry'               => 'د پای نېټه:',
'protect-default'             => '(اصلي بڼه)',
'protect-fallback'            => 'د "$1" اجازه پکار ده',
'protect-level-autoconfirmed' => 'د ناثبته کارونکو مخنيوی کول',
'protect-level-sysop'         => 'يواځې پازوالان',
'protect-cantedit'            => 'تاسو نه شی کولای چې د همدغه مخ د ژغورنې په کچه کې بدلون راولی، دا ځکه چې تاسو د همدغه مخ د سمادولو اجازه نه لری.',
'restriction-type'            => 'اجازه:',
'restriction-level'           => 'د بنديز کچه:',
'minimum-size'                => 'وړه کچه',

# Restrictions (nouns)
'restriction-edit'   => 'سمادول',
'restriction-move'   => 'لېږدول',
'restriction-create' => 'جوړول',

# Undelete
'undelete'               => 'ړنګ شوي مخونه کتل',
'viewdeletedpage'        => 'ړنګ شوي مخونه کتل',
'undeletebtn'            => 'بيا پرځای کول',
'undeletecomment'        => 'تبصره:',
'undeletedarticle'       => '"[[$1]]" بېرته پرځای شو',
'undelete-search-box'    => 'ړنګ شوي مخونه لټول',
'undelete-search-prefix' => 'هغه مخونه ښکاره کړه چې پېلېږي په:',
'undelete-search-submit' => 'لټون',

# Namespace form on various pages
'namespace'      => 'نوم-تشيال:',
'invert'         => 'خوښونې سرچپه کول',
'blanknamespace' => '(اصلي)',

# Contributions
'contributions' => 'د کارونکي ونډې',
'mycontris'     => 'زما ونډې',
'contribsub2'   => 'د $1 لپاره ($2)',
'uctop'         => '(سرپاڼه)',
'month'         => 'له ټاکلې مياشتې نه راپدېخوا (او تر دې پخواني):',
'year'          => 'له ټاکلي کال نه راپدېخوا (او تر دې پخواني):',

'sp-contributions-newbies'     => 'د نوو کارن-حسابونو ونډې ښکاره کول',
'sp-contributions-newbies-sub' => 'د نوو کارن-حسابونو لپاره',
'sp-contributions-blocklog'    => 'د مخنيوي يادښت',
'sp-contributions-search'      => 'د ونډو لټون',
'sp-contributions-username'    => 'IP پته يا کارن-نوم:',
'sp-contributions-submit'      => 'لټون',

'sp-newimages-showfrom' => 'هغه نوي انځورونه چې په $1 پيلېږي ښکاره کول',

# What links here
'whatlinkshere'       => 'د همدې پاڼې تړنونه',
'whatlinkshere-title' => 'هغه سره تړنې لري مخونه چې $1',
'whatlinkshere-page'  => 'مخ:',
'linklistsub'         => '(د تړنونو لړليک)',
'linkshere'           => "دغه لانديني مخونه د '''[[:$1]]''' سره تړنې لري:",
'nolinkshere'         => "د '''[[:$1]]''' سره هېڅ يو مخ هم تړنې نه لري .",
'whatlinkshere-prev'  => '{{PLURAL:$1|پخوانی|پخواني $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|راتلونکی|راتلونکي $1}}',
'whatlinkshere-links' => '← تړنې',

# Block/unblock
'blockip'                  => 'د کاروونکي مخه نيول',
'ipaddress'                => 'IP پته',
'ipadressorusername'       => 'IP پته يا کارن نوم',
'ipbreason'                => 'لامل',
'ipbreasonotherlist'       => 'بل لامل',
'ipbother'                 => 'بل وخت:',
'ipboptions'               => '2 ساعتونه:2 hours,1 ورځ:1 day,3 ورځې:3 days,1 اوونۍ:1 week,2 اوونۍ:2 weeks,1 مياشت:1 month,3 مياشتې:3 months,6 مياشتې:6 months,1 کال:1 year,لامحدوده:infinite', # display1:time1,display2:time2,...
'ipbotherreason'           => 'بل/اضافه سبب:',
'badipaddress'             => 'ناسمه IP پته',
'blockipsuccesssub'        => 'مخنيوی په برياليتوب سره ترسره شو',
'blockipsuccesstext'       => 'د [[Special:Contributions/$1|$1]] مخه نيول شوې.
<br />د مخنيول شويو خلکو د کتنې لپاره، د [[Special:Ipblocklist|مخنيول شويو IP لړليک]] وګورۍ.',
'ipblocklist'              => 'د مخنيول شويو IP پتو او کارن نومونو لړليک',
'ipblocklist-username'     => 'کارن-نوم يا IP پته:',
'ipblocklist-submit'       => 'لټون',
'infiniteblock'            => 'لامحدوده',
'anononlyblock'            => 'يواځې ورکنومی',
'blocklink'                => 'مخه نيول',
'unblocklink'              => 'نامخنيول',
'contribslink'             => 'ونډې',
'autoblocker'              => 'په اتوماتيک ډول ستاسو مخنيوی شوی دا ځکه چې ستاسو IP پته وروستی ځل د "[[User:$1|$1]]" له خوا کارېدلې. او د $1 د مخنيوي سبب دا دی: "$2"',
'blocklogpage'             => 'د مخنيوي يادښت',
'block-log-flags-anononly' => 'يواځې ورکنومي کارونکي',
'block-log-flags-nocreate' => 'د کارن-حساب جوړول ناچارن شوې',
'block-log-flags-noemail'  => 'ددې برېښليک مخه نيول شوی',
'proxyblocksuccess'        => 'ترسره شو.',

# Move page
'move-page-legend'        => 'مخ لېږدول',
'movearticle'             => 'مخ لېږدول',
'movenologin'             => 'غونډال کې نه ياست ننوتي',
'movenologintext'         => 'ددې لپاره چې يو مخ ولېږدوی، نو تاسو بايد يو ثبت شوی کارونکی او غونډال کې [[Special:Userlogin|ننوتي]] اوسۍ.',
'newtitle'                => 'يو نوي سرليک ته:',
'move-watch'              => 'همدا مخ کتل',
'movepagebtn'             => 'مخ لېږدول',
'pagemovedsub'            => 'لېږدېدنه په برياليتوب سره ترسره شوه',
'movepage-moved'          => '<big>\'\'\'د "1$" په نامه دوتنه، "1$" ته ولېږدېده\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'په همدې نوم يوه بله پاڼه د پخوا نه شته او يا خو دا نوم چې تاسو ټاکلی سم نه دی. لطفاً يو بل نوم وټاکۍ.',
'movedto'                 => 'ته ولېږل شو',
'1movedto2'               => '[[$1]]، [[$2]] ته ولېږدېده',
'movelogpage'             => 'د لېږدولو يادښت',
'movelogpagetext'         => 'دا لاندې د لېږدول شوو مخونو لړليک دی.',
'movereason'              => 'لامل',
'delete_and_move'         => 'ړنګول او لېږدول',
'delete_and_move_confirm' => 'هو, دا مخ ړنګ کړه',

# Export
'export'            => 'مخونه صادرول',
'export-addcattext' => 'مخونو د ورګډولو وېشنيزه:',
'export-addcat'     => 'ورګډول',

# Namespace 8 related
'allmessages'               => 'د غونډال پيغامونه',
'allmessagesname'           => 'نوم',
'allmessagesdefault'        => 'ټاکل شوی متن',
'allmessagescurrent'        => 'اوسنی متن',
'allmessagestext'           => 'دا د مېډيا ويکي په نوم-تشيال کې د غونډال د شته پيغامونو لړليک دی.',
'allmessagesnotsupportedDB' => "'''Special:Allmessages''' ترېنه کار نه اخيستل کېږي ځکه چې '''\$wgUseDatabaseMessages''' مړ دی.",
'allmessagesfilter'         => 'د پيغامونو د نوم فلتر:',
'allmessagesmodified'       => 'يواځې بدلون خوړلي توکي ښکاره کول',

# Thumbnails
'thumbnail-more' => 'لويول',
'filemissing'    => 'دوتنه ورکه ده',

# Import log
'importlogpage' => 'د واردولو يادښت',

# Tooltip help for the actions
'tooltip-pt-userpage'        => 'زما کارن مخ',
'tooltip-pt-mytalk'          => 'زما د خبرواترو مخ',
'tooltip-pt-preferences'     => 'زما غوره توبونه',
'tooltip-pt-watchlist'       => 'د هغه مخونو لړليک چې تاسو يې د بدلون لپاره څاری',
'tooltip-pt-mycontris'       => 'زما د ونډو لړليک',
'tooltip-pt-login'           => 'تاسو ته په غونډال کې د ننوتنې سپارښتنه کوو، که څه هم چې دا يو اړين کار نه دی.',
'tooltip-pt-anonlogin'       => 'تاسو ته په غونډال کې د ننوتنې سپارښتنه کوو، که څه هم چې دا يو اړين کار نه دی.',
'tooltip-pt-logout'          => 'وتنه',
'tooltip-ca-talk'            => 'د مخ د مېنځپانګې په اړه خبرې اترې',
'tooltip-ca-edit'            => 'تاسو همدا مخ سمادولای شی. مهرباني وکړی د ليکنې د خوندي کولو دمخه مو د همدې ليکنې مخکتنه وګورۍ.',
'tooltip-ca-addsection'      => 'د خبرواترو همدغه مخ کې يوه تبصره ورګډول.',
'tooltip-ca-viewsource'      => 'همدا مخ ژغورل شوی. تاسو کولای شی چې د همدې مخ سرجينه وګورۍ.',
'tooltip-ca-protect'         => 'همدا مخ ژغورل',
'tooltip-ca-delete'          => 'همدا مخ ړنګول',
'tooltip-ca-move'            => 'همدا مخ لېږدول',
'tooltip-ca-watch'           => 'همدا مخ پخپل کتلي لړليک کې ګډول',
'tooltip-ca-unwatch'         => 'همدا مخ خپل د کتلي لړليک نه لرې کول',
'tooltip-search'             => 'د {{SITENAME}} لټون',
'tooltip-p-logo'             => 'لومړی مخ',
'tooltip-n-mainpage'         => 'لومړي مخ ته ورتلل',
'tooltip-n-portal'           => 'د پروژې په اړه، تاسو څه کولای شی، چېرته کولای شی چې شيان ومومۍ',
'tooltip-n-recentchanges'    => 'په ويکي کې د وروستي بدلونو لړليک.',
'tooltip-n-randompage'       => 'يو ناټاکلی مخ ښکاره کوي',
'tooltip-n-help'             => 'هغه ځای چېرته چې راڅرګندولای شو.',
'tooltip-n-sitesupport'      => 'زموږ ملاتړ وکړی',
'tooltip-t-whatlinkshere'    => 'د ويکي د ټولو هغو مخونو لړليک چې دلته تړنې لري',
'tooltip-t-contributions'    => 'د همدې کارونکي د ونډو لړليک کتل',
'tooltip-t-emailuser'        => 'همدې کارونکي ته يو برېښليک لېږل',
'tooltip-t-upload'           => 'دوتنې پورته کول',
'tooltip-t-specialpages'     => 'د ټولو ځانګړو پاڼو لړليک',
'tooltip-t-print'            => 'د همدې مخ چاپي بڼه',
'tooltip-ca-nstab-user'      => 'د کارونکي مخ کتل',
'tooltip-ca-nstab-special'   => 'همدا يو ځانګړی مخ دی، تاسو نه شی کولای چې دا مخ سماد کړی.',
'tooltip-ca-nstab-project'   => 'د پروژې مخ کتل',
'tooltip-ca-nstab-image'     => 'د دوتنې مخ کتل',
'tooltip-ca-nstab-mediawiki' => 'د غونډال پيغامونه ښکاره کول',
'tooltip-ca-nstab-template'  => 'کينډۍ ښکاره کول',
'tooltip-ca-nstab-help'      => 'د لارښود مخ کتل',
'tooltip-ca-nstab-category'  => 'د وېشنيزې مخ ښکاره کول',
'tooltip-minoredit'          => 'دا لکه يوه وړه سمادېدنه په نښه کوي[alt-i]',
'tooltip-save'               => 'ستاسو بدلونونه خوندي کوي',
'tooltip-preview'            => 'ستاسو بدلونونه ښکاره کوي, لطفاً دا کړنه د خوندي کولو دمخه وکاروۍ! [alt-p]',
'tooltip-diff'               => 'دا هغه بدلونونه چې تاسو په متن کې ترسره کړي، ښکاره کوي. [alt-v]',
'tooltip-watch'              => 'همدا مخ ستاسو کتلي لړليک کې ورګډوي [alt-w]',

# Attribution
'lastmodifiedatby' => 'دا مخ وروستی ځل د $3 لخوا په $2، $1 بدلون موندلی.', # $1 date, $2 time, $3 user

# Spam protection
'subcategorycount'       => 'په همدې وېشنيزه کې {{PLURAL:$1|يوازې يوه بله وړه-وېشنيزه ده|$1 نورې وړې-وېشنيزې دي}}.',
'categoryarticlecount'   => 'په همدې وېشنيزه کې  {{PLURAL:$1|يو مخ دی|$1 مخونه دي}}.',
'category-media-count'   => 'په دې وېشنيزه کې {{PLURAL:$1|يوه دوتنه ده|$1 دوتنې دي}}.',
'listingcontinuesabbrev' => 'پرله پسې',

# Info page
'infosubtitle' => 'د مخ مالومات',

# Patrol log
'patrol-log-auto' => '(خپلسر)',

# Image deletion
'filedeleteerror-short' => 'د دوتنې د ړنګولو ستونزه: $1',

# Browsing diffs
'previousdiff' => '← تېر توپير',
'nextdiff'     => 'بل توپير →',

# Media information
'widthheightpage'      => '$1×$2, $3 مخونه',
'file-info-size'       => '($1 × $2 پېکسل, د دوتنې کچه: $3, MIME بڼه: $4)',
'show-big-image-thumb' => '<small>د همدې مخکتنې کچه: $1 × $2 pixels</small>',

# Special:Newimages
'newimages' => 'د نوو دوتنو نندارتون',
'noimages'  => 'د کتلو لپاره څه نشته.',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'hours-abbrev' => 'ساعتونه',

# Metadata
'metadata'          => 'مېټاډاټا',
'metadata-expand'   => 'غځېدلی تفصيل ښکاره کړی',
'metadata-collapse' => 'غځېدلی تفصيل پټ کړی',

# EXIF tags
'exif-imagedescription' => 'د انځور سرليک',
'exif-artist'           => 'ليکوال',
'exif-usercomment'      => 'د کارونکي تبصرې',
'exif-filesource'       => 'د دوتنې سرچينه',

'exif-unknowndate' => 'نامالومه نېټه',

'exif-orientation-1' => 'نورمال', # 0th row: top; 0th column: left

'exif-componentsconfiguration-0' => 'نشته دی',

'exif-subjectdistance-value' => '$1 متره',

'exif-meteringmode-0'   => 'ناجوت',
'exif-meteringmode-255' => 'نور',

'exif-lightsource-0' => 'ناجوت',

'exif-focalplaneresolutionunit-2' => 'انچه',

'exif-gaincontrol-0' => 'هېڅ',

'exif-contrast-0' => 'نورمال',

'exif-saturation-0' => 'نورمال',

'exif-subjectdistancerange-0' => 'ناجوت',

# External editor support
'edit-externally' => 'د باندنيو پروګرامونو په کارولو سره دا دوتنه سمادول',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'ټول',
'imagelistall'     => 'ټول',
'watchlistall2'    => 'ټول',
'namespacesall'    => 'ټول',
'monthsall'        => 'ټول',

# E-mail address confirmation
'confirmemail'           => 'د برېښليک پتې پخلی وکړی',
'confirmemail_noemail'   => 'تاسو يوه سمه برېښناليک پته نه ده ثبته کړې مهرباني وکړی [[Special:Preferences|د کارونکي غوره توبونه]] کې مو بدلون راولی.',
'confirmemail_oncreate'  => 'ستاسو د برېښناليک پتې ته يو تاييدي کوډ درولېږل شو.
ددې لپاره چې تاسو غونډال ته ورننوځی تاسو ته د همدغه کوډ اړتيا نشته، خو تاسو ته د همدغه کوډ اړتيا په هغه وخت کې پکارېږي کله چې په ويکي کې خپلې برېښناليکي کړنې چارن کول غواړی.',
'confirmemail_needlogin' => 'تاسو ته پکار ده چې $1 ددې لپاره چې ستاسو د برېښليک پتې پخلی وشي.',
'confirmemail_loggedin'  => 'اوس ستاسو د برېښناليک د پتې پخلی وشو.',
'confirmemail_error'     => 'ستاسو د برېښليک پتې د تاييد په خوندي کولو کې يوه ستونزه رامېنڅ ته شوه.',

# Scary transclusion
'scarytranscludetoolong' => '[اوبخښۍ؛ URL مو ډېر اوږد دی]',

# Trackbacks
'trackbackremove' => '([$1 ړنګول])',

# action=purge
'confirm_purge'        => 'په رښتيا د همدې مخ حافظه سپينول غواړۍ؟

$1',
'confirm_purge_button' => 'ښه/هو',

# AJAX search
'searchcontaining' => "د هغو ليکنو لټون چې ''$1'' په کې شته.",
'searchnamed'      => "د هغې ليکنې لټون چې نوم يې ''$1'' دی.",
'articletitles'    => "هغه ليکنې چې په ''$1'' پيلېږي",
'hideresults'      => 'پايلې پټول',

# Multipage image navigation
'imgmultigo'      => 'ورځه!',
'imgmultigotopre' => 'مخ ته ورځه',

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
'autosumm-blank'   => 'د مخ ټوله مېنځپانګه ليرې کول',
'autosumm-replace' => "دا مخ د '$1' پرځای راوستل",
'autoredircomment' => '[[$1]] ته وګرځولی شو',
'autosumm-new'     => 'نوی مخ: $1',

# Watchlist editor
'watchlistedit-raw-titles' => 'سرليکونه:',

# Watchlist editing tools
'watchlisttools-view' => 'اړونده بدلونونه کتل',
'watchlisttools-edit' => 'کتلی لړليک ليدل او سمادول',

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

# Special:Filepath
'filepath-page' => 'دوتنه:',

);

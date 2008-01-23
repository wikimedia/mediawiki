<?php
/** Pashto (پښتو)
 *
 * @addtogroup Language
 *
 * @author SPQRobin
 * @author Ahmed-Najib-Biabani-Ibrahimkhel
 * @author לערי ריינהארט
 */

$rtl = true;
$defaultUserOptionOverrides = array(
	# Swap sidebar to right side by default
	'quickbar' => 2,
	# Underlines seriously harm legibility. Force off:
	'underline' => 0,
);

$messages = array(
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
'april-gen'     => 'اپريل',
'may-gen'       => 'می',
'june-gen'      => 'جون',
'july-gen'      => 'جولای',
'august-gen'    => 'اګسټ',
'september-gen' => 'سېپټمبر',
'october-gen'   => 'اکتوبر',
'november-gen'  => 'نومبر',
'december-gen'  => 'ډيسمبر',
'jan'           => 'جنوري',
'feb'           => 'فبروري',
'mar'           => 'مارچ',
'apr'           => 'اپريل',
'may'           => 'می',
'jun'           => 'جون',
'jul'           => 'جولای',
'aug'           => 'اګسټ',
'sep'           => 'سېپتمبر',
'oct'           => 'اکتوبر',
'nov'           => 'نومبر',
'dec'           => 'ډيسمبر',

# Bits of text used by many pages
'categories'     => 'وېشنيزې',
'pagecategories' => '{{PLURAL:$1|وېشنيزه|وېشنيزې}}',
'subcategories'  => 'وړې-وېشنيزې',

'about'          => 'په اړه',
'qbpageoptions'  => 'همدا مخ',
'qbmyoptions'    => 'زما پاڼې',
'qbspecialpages' => 'ځانګړي پاڼې',
'mypage'         => 'زما پاڼه',
'mytalk'         => 'زما خبرې اترې',
'navigation'     => 'ګرځېدنه',

'tagline'          => 'د {{SITENAME}} لخوا',
'help'             => 'لارښود',
'search'           => 'لټون',
'searchbutton'     => 'لټون',
'go'               => 'ورځه',
'searcharticle'    => 'ورځه',
'history'          => 'د مخ پېښليک',
'history_short'    => 'پېښليک',
'info_short'       => 'مالومات',
'printableversion' => 'د چاپ بڼه',
'permalink'        => 'تلپاتې تړن',
'edit'             => 'سمادول',
'delete'           => 'ړنګول',
'protect'          => 'ژغورل',
'protectthispage'  => 'همدا مخ ژغورل',
'unprotect'        => 'نه ژغورل',
'newpage'          => 'نوی مخ',
'talkpagelinktext' => 'خبرې اترې',
'specialpage'      => 'ځانګړې پاڼه',
'personaltools'    => 'شخصي اوزار',
'articlepage'      => 'د مخ مېنځپانګه ښکاره کول',
'talk'             => 'خبرې اترې',
'toolbox'          => 'اوزاربکس',
'userpage'         => 'د کاروونکي پاڼه ښکاره کول',
'otherlanguages'   => 'په نورو ژبو کې',
'jumptonavigation' => 'ګرځېدنه',
'jumptosearch'     => 'لټون',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'     => 'د {{SITENAME}} په اړه',
'currentevents' => 'اوسنۍ پېښې',
'mainpage'      => 'لومړی مخ',
'portal'        => 'ټولګړی ورټک',
'sitesupport'   => 'بسپنې',

'newmessageslink'     => 'نوي پيغامونه',
'newmessagesdifflink' => 'وروستنی ونج',
'toc'                 => 'نيوليک',
'showtoc'             => 'ښکاره کول',
'hidetoc'             => 'پټول',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'ليکنه',
'nstab-image'     => 'دوتنه',
'nstab-mediawiki' => 'پيغام',
'nstab-template'  => 'کينډۍ',
'nstab-category'  => 'ټولۍ',

# General errors
'viewsource' => 'سرچينې کتل',

# Login and logout pages
'yourname'          => 'کارن-نوم:',
'yourpassword'      => 'پټنوم:',
'login'             => 'ننوتنه',
'userlogin'         => 'ننوتنه / کارن-نوم جوړول',
'logout'            => 'وتنه',
'userlogout'        => 'وتنه',
'nologinlink'       => 'يو کارن-حساب جوړول',
'createaccount'     => 'کارن-حساب جوړول',
'gotaccount'        => 'آيا وار دمخې يو کارن-حساب لری؟ $1.',
'gotaccountlink'    => 'ننوتنه',
'yourrealname'      => 'اصلي نوم:',
'loginsuccesstitle' => 'ننوتنه برياليتوب سره ترسره شوه',
'loginsuccess'      => "'''تاسو اوس {{SITENAME}} کې د \"\$1\" په نوم ننوتي ياست.'''",
'mailmypassword'    => 'پټنوم برېښناليک کې لېږل',

# Edit page toolbar
'math_tip' => 'شمېرپوهنيز فورمول (LaTeX)',

# Edit pages
'summary'          => 'لنډيز',
'minoredit'        => 'دا يوه وړوکې سمادېدنه ده',
'savearticle'      => 'مخ خوندي کول',
'preview'          => 'مخکتنه',
'showpreview'      => 'مخکتنه',
'showdiff'         => 'ونجونه ښکاره کول',
'anoneditwarning'  => "'''يادونه:''' تاسو غونډال ته نه ياست ننوتي. ستاسو IP پته به د دې مخ د سمادولو په پېښليک کې ثبت شي.",
'newarticle'       => '(نوی)',
'copyrightwarning' => 'لطفاً په پام کې وساتۍ چې ټولې هغه ونډې چې تاسو يې {{SITENAME}} کې ترسره کوی هغه د $2 له مخې د خپرولو لپاره ګڼل کېږي (د لانورو تفصيلاتو لپاره $1 وګورۍ). که تاسو نه غواړۍ چې ستاسې په ليکنو کې په بې رحمۍ سره لاسوهنې (سمادېدنې) وشي او د نورو په غوښتنه پسې لانورې هم خپرې شي، نو دلته يې مه ځای پر ځای کوی..<br />
تاسو زمونږ سره دا ژمنه هم کوی چې تاسو پخپله دا ليکنه کښلې، او يا مو د ټولګړو پاڼو او يا هم ورته وړيا سرچينو نه کاپي کړې ده <strong>لطفاً د ليکوال د اجازې نه پرته د خوندي حقونو ليکنې مه خپروی!</strong>',

# History pages
'cur'  => 'اوسنی',
'last' => 'وروستنی',

# Search results
'powersearch' => 'لټون',

# Preferences page
'preferences'   => 'غوره توبونه',
'mypreferences' => 'زما غوره توبونه',
'retypenew'     => 'نوی پټنوم بيا وليکه:',

# Recent changes
'recentchanges'   => 'وروستني ونجونه',
'rcshowhideminor' => 'وړې سمادېدنې $1',
'rcshowhideliu'   => 'غونډال ته ننوتي $1 کارونکي',
'rclinks'         => 'هغه وروستي $1 ونجونه ښکاره کړی چې په $2 ورځو کې پېښ شوی<br />$3',
'diff'            => 'توپير',
'hist'            => 'پېښليک',
'hide'            => 'پټول',
'newpageletter'   => 'نوی',

# Recent changes linked
'recentchangeslinked'       => 'اړونده ونجونه',
'recentchangeslinked-title' => '$1 ته اړونده ونجونه',

# Upload
'upload'    => 'لمېسه پورته کول',
'uploadbtn' => 'دوتنه پورته کول',

# Image list
'imagelist'         => 'د دوتنو لړليک',
'filehist'          => 'د دوتنې پېښليک',
'filehist-datetime' => 'نېټه/وخت',

# Random page
'randompage' => 'ناټاکلی مخ',

# Miscellaneous special pages
'nmembers'     => '$1 {{PLURAL:$1|غړی|غړي}}',
'unusedimages' => 'ناکارېدلې دوتنې',
'allpages'     => 'ټول مخونه',
'shortpages'   => 'لنډ مخونه',
'longpages'    => 'اوږده مخونه',
'listusers'    => 'د کارونکو لړليک',
'specialpages' => 'ځانګړې پاڼې',
'newpages'     => 'نوي مخونه',
'ancientpages' => 'تر ټولو زاړه مخونه',
'move'         => 'لېږدول',

# Special:Log
'specialloguserlabel'  => 'کارونکی:',
'speciallogtitlelabel' => 'سرليک:',

# Special:Allpages
'allarticles'    => 'ټول مخونه',
'allpagessubmit' => 'ورځه',

# E-mail user
'emailuser' => 'همدې کارونکي ته برېښناليک لېږل',

# Watchlist
'watchlist'   => 'زما کتلی لړليک',
'mywatchlist' => 'زما کتلی لړليک',
'watch'       => 'کتل',
'unwatch'     => 'نه کتل',

# Namespace form on various pages
'namespace' => 'نوم-تشيال:',

# Contributions
'contributions' => 'د کارونکي ونډې',
'mycontris'     => 'زما ونډې',

# What links here
'whatlinkshere' => 'د همدې پاڼې تړنونه',

# Block/unblock
'contribslink' => 'ونډې',

# Namespace 8 related
'allmessages' => 'د غونډال پيغامونه',

# Tooltip help for the actions
'tooltip-pt-mytalk'      => 'زما د خبرواترو مخ',
'tooltip-pt-preferences' => 'زما غوره توبونه',
'tooltip-pt-mycontris'   => 'زما د ونډو لړليک',
'tooltip-pt-logout'      => 'وتنه',
'tooltip-ca-protect'     => 'همدا مخ ژغورل',
'tooltip-search'         => 'د {{SITENAME}} لټون',
'tooltip-n-mainpage'     => 'لومړي مخ ته ورتلل',
'tooltip-n-randompage'   => 'يو ناټاکلی مخ ښکاره کوي',
'tooltip-n-sitesupport'  => 'زموږ ملاتړ وکړی',
'tooltip-t-upload'       => 'انځورونه يا رسنيزې دوتنې پورته کول',
'tooltip-t-specialpages' => 'د ټولو ځانګړو پاڼو لړليک',
'tooltip-save'           => 'ستاسو ونجونه خوندي کوي',

# Special:Newimages
'newimages' => 'د نوو دوتنو نندارتون',

# 'all' in various places, this might be different for inflected languages
'namespacesall' => 'ټول',
'monthsall'     => 'ټول',

# Watchlist editor
'watchlistedit-raw-titles' => 'سرليکونه:',

);

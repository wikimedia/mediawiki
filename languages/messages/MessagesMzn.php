<?php
/** Mazanderani (مَزِروني)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Ali1986
 * @author Firuz
 * @author Spacebirdy
 */

$fallback = 'fa';

$linkPrefixExtension = true;
$fallback8bitEncoding = 'windows-1256';

$rtl = true;
$defaultUserOptionOverrides = array(
	# Swap sidebar to right side by default
	'quickbar' => 2,
	# Underlines seriously harm legibility. Force off:
	'underline' => 0,
);

$namespaceNames = array(
	NS_MEDIA            => 'مه‌دیا',
	NS_SPECIAL          => 'شا',
	NS_TALK             => 'گپ',
	NS_USER             => 'کارور',
	NS_USER_TALK        => 'کارور گپ',
	NS_PROJECT_TALK     => '$1 گپ',
	NS_FILE             => 'پرونده',
	NS_FILE_TALK        => 'پرونده گپ',
	NS_MEDIAWIKI        => 'مه‌دیا ویکی',
	NS_MEDIAWIKI_TALK   => 'مه‌دیا ویکی گپ',
	NS_TEMPLATE         => 'شابلون',
	NS_TEMPLATE_TALK    => 'شابلون گپ',
	NS_HELP             => 'رانه‌ما',
	NS_HELP_TALK        => 'رانه‌مای گپ',
	NS_CATEGORY         => 'رج',
	NS_CATEGORY_TALK    => 'رج گپ',
);

$namespaceAliases = array(
	'مدیا' => NS_MEDIA,
	'ویژه' => NS_SPECIAL,
	'بحث' => NS_TALK,
	'کاربر' => NS_USER,
	'بحث_کاربر' => NS_USER_TALK,
	'بحث_$1' => NS_PROJECT_TALK,
	'تصویر' => NS_FILE,
	'بحث_تصویر' => NS_FILE_TALK,
	'پرونده' => NS_FILE,
	'بحث_پرونده' => NS_FILE_TALK,
	'مدیاویکی' => NS_MEDIAWIKI,
	'بحث_مدیاویکی' => NS_MEDIAWIKI_TALK,
	'الگو' => NS_TEMPLATE,
	'بحث_الگو' => NS_TEMPLATE_TALK,
	'راهنما' => NS_HELP,
	'بحث_راهنما' => NS_HELP_TALK,
	'رده' => NS_CATEGORY,
	'بحث_رده' => NS_CATEGORY_TALK
);

$messages = array(
# User preference toggles
'tog-underline' => 'پیوندون جیر خط دأکشی بأوو',
'tog-justify'   => 'بندون ته‌موم چین هاکردن',

# Dates
'friday'        => 'جومه',
'january'       => 'جـانـویـه',
'february'      => 'فـه‌وریـه',
'march'         => 'مـارچ',
'april'         => 'ئـه‌وریـل',
'may_long'      => 'مـه‌ی',
'june'          => 'جـوئـه‌ن',
'july'          => 'جـولای',
'august'        => 'ئـوگـه‌سـت',
'september'     => 'سـه‌پـتـه‌مـبـر',
'october'       => 'ئـوکـتـوبـر',
'november'      => 'نـووه‌مـبـر',
'december'      => 'ده‌سـه‌مـبـر',
'january-gen'   => 'جـانـویـه',
'february-gen'  => 'فـه‌وریـه',
'march-gen'     => 'مـارس',
'april-gen'     => 'آوریـل',
'may-gen'       => 'مـه‌ی',
'june-gen'      => 'جـون',
'july-gen'      => 'جـولای',
'august-gen'    => 'ئوگـه‌سـت',
'september-gen' => 'سـه‌پـتـه‌مـبـر',
'october-gen'   => 'ئـوکـتـوبـر',
'november-gen'  => 'نـووه‌مـبـر',
'december-gen'  => 'ده‌سـه‌مـبـر',
'jan'           => 'جانویه',
'feb'           => 'فه‌وریه',
'mar'           => 'مارچ',
'apr'           => 'ئه‌وریل',
'may'           => 'مه‌ی',
'jun'           => 'جون',
'jul'           => 'جولای',
'aug'           => 'ئوگوست',
'sep'           => 'سه‌پته‌مبر',
'oct'           => 'ئوکتوبر',
'nov'           => 'نووه‌مبه‌ر',
'dec'           => 'ده‌سه‌مبر',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|رج|رج‌ئون}}',
'subcategories'  => 'جیر رج‌ئون',

'about'      => 'درباره',
'newwindow'  => 'ته‌رنه‌ روجین ده‌له‌ وا بونه',
'cancel'     => 'وه‌ل هـه‌کـارده‌ن',
'mypage'     => 'مه ولگ',
'mytalk'     => 'مه گپ',
'navigation' => 'چـأرخـه‌سـه‌ن',

# Cologne Blue skin
'qbedit'         => 'دچی ین',
'qbspecialpages' => 'شا ولگ ئون',

'errorpagetitle'   => 'شه‌ت!',
'returnto'         => 'وأرگه‌رده‌سه‌ن تا $1',
'tagline'          => '{{SITENAME}} جه',
'help'             => 'رانه‌مایی',
'search'           => 'چـأرخـه تـو',
'searchbutton'     => 'چـأرخـه‌تـو',
'go'               => 'بور',
'searcharticle'    => 'بور',
'history'          => 'ولـگ ره چـه‌کـوت',
'history_short'    => 'چه‌كوت / تاریخ',
'printableversion' => 'په‌رینت ده‌لـماج',
'permalink'        => 'مـونـده‌نـه‌سـی لـیـنـک',
'edit'             => 'دأچـیـه‌ن',
'create'           => 'بـأئـیـتـه‌ن',
'editthispage'     => 'ای ولـگ ره دأچـیـه‌ن',
'delete'           => 'وربـأئـیـتـه‌ن',
'protect_change'   => 'ده‌گـه‌ره‌سـه‌ن',
'newpage'          => 'نـه ولـگ',
'talkpage'         => 'ای ولـگ پـألـی گـب بـأزوئـه‌ن',
'talkpagelinktext' => 'گپ',
'specialpage'      => 'شا ولگ',
'personaltools'    => 'مه‌شه ابزار',
'talk'             => 'گپ',
'toolbox'          => 'أبـزار جـا',
'userpage'         => 'کارور ولگ نه‌شون هدائن',
'viewtalkpage'     => 'گپ ئون ره نشون هدائن',
'otherlanguages'   => 'دیـگـه زیوون‌ئون',
'redirectedfrom'   => '(به‌مونه   $1   جه)',
'lastmodifiedat'   => 'ای ولـگ ره پایانی جور هکاردن ره بنه وخت ره وند بونه:
$2، $1',
'jumpto'           => 'کـأپـتـه تـا:',
'jumptonavigation' => 'چـأرخـه‌سـه‌ن',
'jumptosearch'     => 'چـأرخـه‌تـو',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'      => '{{SITENAME}} ده‌له‌واره',
'aboutpage'      => 'په‌روجه:ده‌له‌واره',
'copyright'      => 'ای ولـگ ره بأنـویـشـتـه‌ئون  $1  ره جـیـر شـه‌مـه دسـت دأره‌نـه.',
'copyrightpage'  => '{{ns:project}}:کـوپـی‌راسـت‌ئون',
'disclaimers'    => 'خواهان فه‌رو نیشته‌نه‌ن',
'disclaimerpage' => 'په‌روجه:خواهان فه‌رو نیشته‌نه‌ن',
'edithelp'       => 'دأچـیه‌ن ره رانـه‌ما',
'edithelppage'   => 'رانه‌ما:دأچیه‌ن',
'mainpage'       => 'گت ولـگ',
'privacy'        => 'کاری رول',
'privacypage'    => 'په‌روجه:کاری رول',

'ok'                      => 'خا',
'retrievedfrom'           => '"$1" جـه بأئـیـتـه بـأیـه',
'youhavenewmessages'      => 'شـه‌مـا اتـا $1 دارنـه‌نـی ($2).',
'newmessageslink'         => 'تـه‌رنـه پـه‌یـخـوم‌ئـون',
'newmessagesdifflink'     => 'پایانی ده‌گارده‌سه‌ن',
'youhavenewmessagesmulti' => 'شه مه وسه ترنه پیغوم بی یه موئه ای جه $1',
'editsection'             => 'دأچیـه‌ن',
'editold'                 => 'دأچیه‌ن',
'editlink'                => 'دأچیه‌ن',
'viewsourcelink'          => 'چه‌شـمـه بأویـنه‌ن',
'editsectionhint'         => 'تـیـکـه: $1 ره دأچـیـه‌ن',
'toc'                     => 'بـه‌تـیـم',
'showtoc'                 => 'نه‌شون  هـاده',
'hidetoc'                 => 'فه‌رو  بـور',
'site-rss-feed'           => '$1 ره  آراس‌اس خه‌راک',
'site-atom-feed'          => '$1 ره اتم خه‌راک',
'page-rss-feed'           => '"$1" RSS خه‌راک',
'page-atom-feed'          => '"$1" Atom خه‌راک',
'red-link-title'          => '$1 (ای ولـگ دأنـیـه)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'ولـگ',
'nstab-user'     => 'کارور گپ',
'nstab-special'  => 'شـا ولـگ',
'nstab-image'    => 'فایل',
'nstab-template' => 'شابلون',
'nstab-category' => 'رج',

# General errors
'badtitle'      => 'نخش عونوان',
'viewsource'    => 'چـه‌شـمـه بـأویـنـه‌ن',
'viewsourcefor' => '$1 ره وسه',

# Login and logout pages
'welcomecreation'         => '<h2>$1، خش بیه موئی!</h2><p>شه مه حساب وا بایه! یاد نکاندنین که شه خواستنی ئون ره {{SITENAME}} ده رست هاکنین.',
'yourname'                => 'کاروری‌نوم:',
'yourpassword'            => 'پـأس‌واجـه',
'remembermypassword'      => 'مـه کاروری نوم ئو پـأس‌واجه ره، ای کـامـپـیـوتـه‌ر ده‌لـه وه‌سـه، شـه یـاد بیـه‌ل',
'login'                   => 'ده‌لـه بـوری',
'nav-login-createaccount' => 'ده‌لـه‌بـوری / ئـه‌کـانـت بـأئـیـتـه‌ن',
'loginprompt'             => '{{SITENAME}} ره دله ئه نه ن ونه cookie ئون فعال بوئه.',
'userlogin'               => 'ده‌لـه‌بـوری',
'userlogout'              => 'دأیابـوری',
'notloggedin'             => 'سیستم ره دله نی یه موئین',
'nologinlink'             => 'أتـا  ئـه‌کـانـت  وا هـه‌کـارده‌ن',
'createaccount'           => 'ترنه حساب وا هکاردن',
'createaccountmail'       => 'Email ره همرا',
'loginerror'              => 'ده‌له بوری إشه‌ت',
'nocookiesnew'            => 'کاروری إکانت به‌سات بئی بیه. ولی شه‌ما ده‌له نأشینی. {{SITENAME}} کوکی‌ئون ره کارورون ده‌له بوری سر کار زننه. شه‌ما کوکی‌ئون ره پاک هأکه‌نین. شه‌ما جا خائه‌ش دارمی که کوکی‌ئون ره کار به‌لین ئو سیسته‌م ره نو کاروری نوم ئو پاس واجه جا ده‌له بورین.',
'nocookieslogin'          => '‏{{SITENAME}} کوکی‌ئون ره کارورون دله بوردن سر کار زأننه. شه‌ما جا خائه‌ش دارمی که وه‌شون ره کار به‌لین ئو ده‌باره سأئی هکه‌نین.‎‎',
'nouserspecified'         => 'شما ونه اتا کارور نوم هادی.',
'mailmypassword'          => 'اتـا نـه پـأس‌واجـه بـه‌سـاتـه‌ن ئو بـأره‌سـه‌نـده‌ن',

# Edit page toolbar
'bold_sample'    => 'کأفتال ته‌کست',
'bold_tip'       => 'کأفتال ته‌کست',
'italic_sample'  => 'کأج ته‌کست',
'italic_tip'     => 'کأج ته‌کـست',
'link_sample'    => 'لـیـنـک سـأرنـوم',
'link_tip'       => 'درونی لینک',
'extlink_sample' => 'http://www.example.com لینک ره نوم',
'extlink_tip'    => 'دأیـا لـیـنـک (شـه‌مـه یـاد بـوئـه <span dir="ltr">http://</span> ره بـیـه‌لـیـن)',
'math_tip'       => 'ریاضی فورمول',
'nowiki_sample'  => 'شـه فـورمـأت‌نـه‌دار تـه‌کـسـت ره ایـجـه دأکـه‌نـیـن',
'nowiki_tip'     => 'ویـکـی فـورمـأت ره نـابـأدیـه‌ن',
'media_tip'      => 'فایل لینک',

# Edit pages
'summary'                          => 'چه‌کیده:',
'minoredit'                        => 'ایـنـتـا أتـا پـه‌چوک دأچـیـه‌ن هـأسـه',
'watchthis'                        => 'ای ولـگ ره ده‌مـبـال هـه‌کـارده‌ن',
'savearticle'                      => 'جـا دأکه‌ته‌ن ولـگ',
'preview'                          => 'پیش نه‌مایه‌ش',
'showpreview'                      => 'پیش‌هاره‌شا نه‌شون هـه‌دائه‌ن',
'whitelistedittitle'               => 'جور هکاردن ره وسه ونه سیستم ره دله ئه نین',
'newarticle'                       => '(ته‌رنه)',
'previewnote'                      => "'''شه‌مه یاد بوئه که اینتا أتا پیش‌نه‌مایه‌ش هأسه.'''
 شه‌مه ده‌گه‌ره‌سه‌ن‌ئون جانأکه‌فته که وه‌نه، جادأکه‌فته‌ن تگمه ره بأزه‌نین!",
'editing'                          => 'دچیه‌ن => $1',
'editingsection'                   => 'دچیه‌ن $1 (تیکه)',
'copyrightwarning'                 => 'خـاهـه‌ش بـونـه شـه یـاد ده‌لـه دأکـه‌نـیـن کـه هـأمـه کـایـه‌رئونی کـه {{SITENAME}} ده‌لـه بـونـه، $2 جـیـر ره‌هـا بـونـه. (ویـشـتـه‌ر وه‌سـه $1 ره بـأویـنـیـن)<br>
أگـه نـه‌خـانـه‌نـی شـه‌مـه بـأنـویـشـتـه‌ئون ایـجـه دسـت بـأخـوره ئو أتـا جـا دیـگـه پـخـش بـأوه، بـه‌تـه‌ر هـأسـه کـه وه‌شـون ره ایـجـه نـیـه‌لـیـن.',
'templatesused'                    => 'شـابـلـون‌ئـونی که ای ولـگ ده‌له کـار بـورده‌نـه:',
'templatesusedpreview'             => 'شـابـلـون‌ئونی کی ای پـیـش‌نـه‌مـایـه‌ش ده‌لـه کـار بـورده‌نـه:',
'permissionserrorstext-withaction' => 'شه‌ما نه‌تونده‌نی $2 هاکه‌نی، اینتا وه‌سه که:',

# History pages
'revisionasof'     => 'دأچـیـه‌نی کـه  $1  ده‌لـه جـا دأکـه‌تـه',
'previousrevision' => '→ پـیـشـیـن ده‌گه‌ره‌سه‌ن',
'cur'              => 'ئه‌سا',
'last'             => 'چه‌کوت',
'histfirst'        => 'کـوهـنـه تـه‌ریـن',
'histlast'         => 'نـه تـه‌ریـن',

# Merge log
'revertmerge' => 'سـه‌وا  هـه‌کارده‌ن',

# Diffs
'lineno'   => 'بند  $1:',
'editundo' => 'واچیه‌ن',

# Search results
'searchsubtitle'           => 'شـه‌مـا \'\'\'[[:$1]]\'\'\' ره ده‌مـبـال بـورده‌نـی ([[Special:Prefixindex/$1|هـأمـه ولـگ‌ئونـی کـه وه‌شـون نـوم  "$1" هـه‌مـرا سـأر گـیـرنـه ره بـأویـنـه‌ن]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|هـأمه ولـگ‌ئونـی که  "$1" ره لـیـنـک وه‌شـون ده‌لـه دأره]])',
'notitlematches'           => 'هـیـچ ولـگـی شه‌مه گـب ره نـه‌مـاسـتـه',
'prevn'                    => 'پـیـشـیـن {{PLURAL:$1|$1}}',
'nextn'                    => 'پـأسـیـن {{PLURAL:$1|$1}}',
'viewprevnext'             => 'بـأویـنـه‌ن ($1 {{int:pipe-separator}} $2) ($3)',
'search-result-size'       => '$1 ({{PLURAL:$2|1 واجه|$2 واجه}})',
'search-redirect'          => '(بـأره‌سـیـه $1 جـه)',
'search-section'           => '(تیکه $1)',
'search-suggest'           => 'شه‌مـا ایـنـتـا ره نـه‌خـاسـه‌نی؟ $1',
'search-interwiki-caption' => 'خاخه‌ر په‌روجه‌ئون',
'search-interwiki-more'    => '(ویشته‌ر)',
'powersearch'              => 'سه‌ره‌ک به‌نه‌ک  (پیـش‌بـورده چـأرخـه‌تو)',
'powersearch-legend'       => 'سه‌ره‌ک به‌نه‌ک  (پیـش‌بـورده چـأرخـه‌تو)',
'powersearch-ns'           => 'سه‌ره‌ک به‌نه‌ک، نوم‌جائون ده‌له:',
'powersearch-field'        => 'سه‌ره‌ک به‌نه‌ک',

# Preferences page
'mypreferences'             => 'مـه خـاسـته‌نی‌ئون',
'prefsnologin'              => 'سیستم ره ديله نی یه مویین',
'youremail'                 => 'شه مه Email *',
'username'                  => 'کاروری نوم:',
'uid'                       => 'کاروری إشماره:',
'yourrealname'              => 'شیمه راستین ره نوم :',
'yourlanguage'              => 'زیوون:',
'badsig'                    => 'ایمضا بی اعتبار هسه. html کودون ره أی هارشین.',
'email'                     => 'رایانومه',
'prefs-help-realname'       => 'اصلی نوم اختیاری هسه. اگه شه‌ما بنویسین شمه کارون ونه جا ثبت بونه.',
'prefs-help-email'          => 'ایمیل اختیاری هسه. ولی أگه شه‌ما شه پاس واجه ره یات بکه‌رده‌نی نو پاس واژه شه‌مه ایمیل سر راهی بونه. شما همچه‌نین تونه‌نی به‌لین که دیگه کارورون شمه سر  کاروری ولگ ئو کاروری گپ جاایمیل بأزه‌نه‌ن بی اونکه شه‌مه ایمیل سو دأکه‌فه.',
'prefs-help-email-required' => 'ایمیل نه‌شونی لازم هسه.',

# User rights
'userrights-user-editname' => 'کارور نوم ره بنویش هاکنین',

# Groups
'group-sysop' => 'کـاره‌ئون',

'grouppage-sysop' => '{{ns:project}}:کـاره‌ئون',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'ای ولـگ ره دأچـیـه‌ن',

# Recent changes
'recentchanges'   => 'نـه ده‌گـه‌ره‌سـه‌ئون',
'rcnote'          => "ایجه هأمه {{PLURAL:$1| '''اتا''' ده‌گـه‌ره‌سـه‌نـی|هأمه '''$1''' ده‌گـه‌ره‌سـه‌ئـونـی}} ده‌گه‌ره‌سونی کـه $4، $5 جـه، '''$2''' روز پـیـش‌تـه‌ر دأکـه‌تـه‌نـه ره ویـنـده‌نـی",
'rclinks'         => 'نـه‌شـون هـه‌دائـه‌ن  $1 پـایـانـی دأچـیـه‌ن‌ئون، $2 ئـه‌سـا روز ره ده‌لـه؛ $3',
'diff'            => 'ئه‌سا',
'hist'            => 'چـه‌كـوت',
'hide'            => 'فـه‌رو بـأبـه‌ردن',
'show'            => 'نـه‌شـون هـاده',
'minoreditletter' => 'پچک',
'newpageletter'   => 'نه',
'boteditletter'   => 'ربوت',

# Recent changes linked
'recentchangeslinked'         => 'واری دأچیـه‌ن‌ئون',
'recentchangeslinked-feed'    => 'واری دچیه‌ن‌ئون',
'recentchangeslinked-toolbox' => 'واری دچیه‌ن‌ئون',
'recentchangeslinked-page'    => 'ولـگ نـوم:',

# Upload
'upload'        => 'بـاربیـه‌شـتـه‌ن فـایـل',
'uploadlogpage' => 'بـاربـیـه‌شـتـه‌ن گوزاره‌ش',
'uploadedimage' => 'بـاربـیـه‌شـتـه بـأیـه "[[$1]]"',

# Special:ListFiles
'listfiles' => 'هارشی ئون ره لیست',

# File description page
'file-anchor-link'  => 'فایل',
'filehist'          => 'فایل چه‌کوت',
'filehist-current'  => 'ئـه‌سـا',
'filehist-datetime' => 'تاریخ/زأمون',
'filehist-user'     => 'کارور',
'imagelinks'        => 'لینک‌ئون',
'linkstoimage'      => 'ای {{PLURAL:$1|ولـگ|$1 ولـگ‌ئون}} لـیـنـک هـه‌دانه ای فـایـل ره:',

# Statistics
'statistics' => 'آمار',

'disambiguations' => 'گجگجی بایری ولگ ئون',

# Miscellaneous special pages
'nbytes'            => '$1 بایت',
'popularpages'      => 'خاسگار هدار ولگ ئون',
'wantedpages'       => 'ولگ ئون ری که خامبی',
'prefixindex'       => 'هـأمـه ولـگ‌ئونی کـه وه‌شـون سـأرنـوم هـأسـه',
'shortpages'        => 'پیس ولگ ئون',
'longpages'         => 'بیلند ولگ ئون',
'listusers'         => 'کارور ئون ره لیست',
'newpages'          => 'نـه به‌ساجه ولـگ‌ئون',
'newpages-username' => 'کارور نوم:',
'ancientpages'      => 'كوهنه ولگ ئون',
'pager-newer-n'     => '{{PLURAL:$1|أتـا نـه‌ته‌ر|$1 تـا نـه‌ته‌ر}}',
'pager-older-n'     => '{{PLURAL:$1|أتـا کـوهـنـه‌ته‌ر|$1 تـا کوهـنـه‌ته‌ر}}',

# Book sources
'booksources-go' => 'بـور',

# Special:Log
'specialloguserlabel' => 'کارور:',

# Special:AllPages
'allpages'       => 'هـأمـه ولـگ‌ئون',
'alphaindexline' => '$1 تا  $2',
'prevpage'       => 'پـیـشـیـن ولـگ ($1)',
'allarticles'    => 'هأمه ولـگ ئون',
'allpagessubmit' => 'بـور',

# Special:Categories
'categories' => 'دسته ئون',

# Special:LinkSearch
'linksearch' => 'دأیا لـیـنـک‌ئون',

# Special:ListGroupRights
'listgrouprights-members' => '(کارورئون ره لیست)',

# E-mail user
'emailuser' => 'ئـه‌لـه‌کـتـه‌ریـکـی‌نـومـه ای کـارور وه‌سه',

# Watchlist
'watchlist'     => 'مـه ده‌مـبـالـه‌ئون ره لـیـسـت',
'mywatchlist'   => 'مـه ده‌مـبـال‌هـه‌کـاردن لـیـسـت',
'watchnologin'  => 'سیستم ره دله نی ئه موئین',
'watch'         => 'ده‌مـبال هـاکه‌ردن',
'watchthispage' => 'ای ولـگ ره ده‌مـبـال هـه‌کـارده‌ن',
'unwatch'       => 'ده‌مـبـال نـه‌کـارده‌ن',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'ده‌مـبـال هـه‌کـارده‌ن...',
'unwatching' => 'ده‌مـبـال نـه‌کـارده‌ن...',

'created' => 'وا بایه',

# Delete
'deletepage'     => 'ولـگ وربـأئـیـتـه‌ن',
'deletedarticle' => 'وربـأئـیـتـه بأیه "[[$1]]"',
'dellogpage'     => 'وربأئیته‌نه‌ئون گوزارش',

# Rollback
'rollbacklink' => 'واچیه‌ن',

# Undelete
'undeletelink'     => 'بـأویـنـه‌ن / ده‌واره جـا بـیـه‌شـتـه‌ن',
'undeletedarticle' => 'جـا دأکـه‌فـتـه "[[$1]]"',

# Namespace form on various pages
'namespace'      => 'نوم‌جا:',
'blanknamespace' => '(مـار)',

# Contributions
'contributions'       => 'کارور کایه‌رئون',
'contributions-title' => 'کارور کایه‌رئون $1 وه‌سه',
'mycontris'           => 'مـه کـایـه‌رئون',
'contribsub2'         => '$1 ($2) وه‌سه',
'uctop'               => '(سه‌ر)',

'sp-contributions-talk'     => 'گپ',
'sp-contributions-username' => 'IP نـه‌شـونـی یا کـاروری‌نوم',
'sp-contributions-submit'   => 'چـأرخـه‌تـو',

# What links here
'whatlinkshere'       => 'کـه‌جـه‌ لـیـنـک هـه‌دائـه‌ ایـجـه ره؟',
'whatlinkshere-page'  => 'ولـگ:',
'linkshere'           => "ولـگ‌ئـونی کـه لـیـنـک هـه‌دائـه‌نـه '''[[:$1]]''' ره:",
'whatlinkshere-prev'  => '{{PLURAL:$1|پـیـشـیـن|$1 تـای پـیـشـیـن}}',
'whatlinkshere-next'  => '{{PLURAL:$1|پـأسـیـن|$1 تـای پـأسـیـن}}',
'whatlinkshere-links' => '← لـیـنـک‌ئون',

# Block/unblock
'blockip'       => 'کارور دأبه‌سته‌ن',
'ipbsubmit'     => 'ای کارور دابس باوه',
'ipblocklist'   => 'IP نـه‌شـونـی‌ئون ئو کـارورنـوم‌ئونی کـه دأبـه‌سـتـوونـه',
'blocklink'     => 'دأبـه‌سـتـه‌ن',
'unblocklink'   => 'وا هـه‌کـارده‌ن',
'contribslink'  => 'کایه‌رئون',
'blocklogentry' => '[[$1]] دأبـه‌سـتـو بـأیـه ئو وه‌نـه دأبه‌ستو بوئه‌ن زأمـون، تـا  $2 $3 هـأسـه',

# Move page
'newtitle'                => 'ته‌رنـه نـوم:',
'movepage-moved'          => "<big>'''ای «$1» ولـگ،  بورده «$2» ره.'''</big>",
'movetalk'                => '«گپ» ولـگ هم، اگه بونه، بوره.',
'1movedto2'               => '[[$1]] بـورده [[$2]] ره',
'revertmove'              => 'واچـیـه‌ن',
'delete_and_move_confirm' => 'اره، پاک هاکن وه ره',

# Export
'export' => 'دأیابیاردن ولـگ‌ئون',

# Thumbnails
'thumbnail-more' => 'گت بأوه',

# Special:Import
'import-interwiki-submit' => 'بیاردن',

# Tooltip help for the actions
'tooltip-pt-userpage'           => 'مه کاروری ولـگ',
'tooltip-pt-mytalk'             => 'مه گب ولـگ',
'tooltip-pt-preferences'        => 'مه خواسته‌نی‌ئون',
'tooltip-pt-watchlist'          => 'لیست ولـگ‌ئونی که وه‌شون ره دچیه‌ن‌ئون وه‌سه ده‌مـبـال که‌نده‌نی',
'tooltip-pt-mycontris'          => 'مه کایه‌رئون ره لیست',
'tooltip-pt-login'              => 'شه‌ما به‌ته‌ر هـأسـه که سـیـسـتـه‌م ده‌لـه بـیـه‌ئی، هـرچـأن زوری نـیـه',
'tooltip-pt-logout'             => 'سیستم جه دأیابـوری',
'tooltip-ca-talk'               => 'ولـگ ده‌له‌واره گب بأزوئه‌ن',
'tooltip-ca-edit'               => 'شه‌ما به‌تونده‌نی ای ولـگ ره دأچیه‌نی.
خوائه‌ش که‌مبی  پیش‌نه‌مایه‌ش  تگمه ره ته‌له‌مبار پیش بأزه‌نین.',
'tooltip-ca-addsection'         => 'أتـا نـه گـب را دأکـه‌تـه‌ن',
'tooltip-ca-viewsource'         => 'ای ولـگ ره نه‌تونده‌نی دأچیه‌نی.
شه‌ما به‌تونده‌نی وه‌نه چه‌شمه ره بأوینی.',
'tooltip-ca-history'            => 'کـوهـنـه ده‌گـه‌ره‌سـه‌ئـونی کـه ای ولـگ ده‌لـه دأکـه‌تـه',
'tooltip-ca-delete'             => 'ای ولـگ ره وربـأئـیـتـه‌ن',
'tooltip-ca-watch'              => 'ای ولـگ ره شه هارشالیست بأبه‌رده‌ن',
'tooltip-search'                => '{{SITENAME}} ره چـأرخـه‌تـو',
'tooltip-search-go'             => 'بـور اتـا ولـگـی کـه وه‌نـه نـوم هـأمـیـنـتـا بـوئـه',
'tooltip-search-fulltext'       => 'ولـگ‌ئـون ره ایـنـتـا تـه‌کـسـت وه‌سـه چـأرخ بـأزوئـه‌ن',
'tooltip-n-mainpage'            => 'بأویـنـه‌ن گـت ولـگ',
'tooltip-n-portal'              => 'په‌روجه ده‌له‌واره، چه‌شی به‌توده‌نی هاکه‌نی ئو که‌جه چیزئون ره بأره‌سی',
'tooltip-n-currentevents'       => 'تازه چی‌ئون ده‌له‌واره دونه‌سه‌ن',
'tooltip-n-recentchanges'       => 'ای ویکی ده‌له، ئه‌سا دچیه‌نون ره لیست',
'tooltip-n-randompage'          => 'أتـا شـانـسـی ولـگ بـیـارده‌ن',
'tooltip-n-help'                => 'أتـا جـا کـه...',
'tooltip-t-whatlinkshere'       => 'هأمو ولـگ‌ئونی که ایجه ره لینک هه‌دانه',
'tooltip-t-recentchangeslinked' => 'ئـه‌سـائـی  ده‌گـه‌ره‌سـه‌ئون  ولـگ‌ئونی ده‌له، کـه ای ولـگ جـه لـیـنـک دارنـه‌نـه',
'tooltip-feed-rss'              => 'RSS خه‌راک ای ولـگ وه‌سه',
'tooltip-feed-atom'             => 'Atom خه‌راک ای ولـگ وه‌سه',
'tooltip-t-emailuser'           => 'ای کـارور ره اتـا ئـه‌لـه‌کـتـه‌رونـیـکـی‌نـومـه راهـی هـه‌کـارده‌ن',
'tooltip-t-upload'              => 'بـاربـیـه‌شـتـه‌ن فـایـل‌ئون',
'tooltip-t-specialpages'        => 'هأمـه شـا ولـگ‌ئون ره لـیـسـت',
'tooltip-t-print'               => 'پـه‌ریـنـت هـه‌کـارده‌نـی ولـگ ده‌گـه‌ره‌سـه‌ن',
'tooltip-t-permalink'           => 'مـونـده‌سـه‌نـی لـیـنـک ای ولـگ ره ایـنـتـا بـه‌تـیـم وه‌سـه',
'tooltip-ca-nstab-main'         => 'بـأویـنـه‌ن ولـگ',
'tooltip-ca-nstab-user'         => 'کاروری ولـگ بأویـنه‌ن',
'tooltip-ca-nstab-special'      => 'اینتا أتا شـا ولـگ هأسه که شه‌ما نه‌تونده‌نی وه‌نه به‌تیم ره دأچیه‌نی',
'tooltip-ca-nstab-image'        => 'وه‌نـه وألـگ ره بـأویـنـه‌ن',
'tooltip-ca-nstab-template'     => 'شـابـلـون بـأویـنـه‌ن',
'tooltip-preview'               => 'شـه ده‌گـه‌ره‌سـه‌ئون ره پـیـشـاپـیـش بـأویـنـه‌ن،
 خـا‌هـه‌ش بـونـه، شـه کـارئون ره جـا دأکـه‌تـه‌ن پـیـش، ای ره کـار بـأزه‌نـی.',

# Browsing diffs
'previousdiff' => 'کوهنه‌ته‌ر دچیه‌ن ←',
'nextdiff'     => 'ته‌رنه دأچیه‌ن ←',

# Media information
'file-info-size' => '($1 × $2 پـیـکـسه‌ل, فـایـل گـأتـی: $3, MIME مـونـد: $4)',

# Special:NewFiles
'ilsubmit' => 'سرک بنک',

# EXIF tags
'exif-gpsareainformation' => 'جی پی اس ناحیه نوم',
'exif-gpsdatestamp'       => 'جی پی اس تاریخ',
'exif-gpsdifferential'    => 'جی پی اس په‌چه‌ک درس هأکه‌ردن',

# EXIF attributes
'exif-compression-1' => 'فه‌شورده نئی',

'exif-unknowndate' => 'نه‌شناسی چه‌کوت',

'exif-orientation-1' => 'معمولی',
'exif-orientation-3' => '180 درجه چرخ بزوئن',
'exif-orientation-4' => 'عمودی په‌شت ئو روبئی',

# External editor support
'edit-externally' => 'ای فـایـل ره، أتـا دأیـا بـه‌رنـومـه هـه‌مـرا، دأچـیـه‌نـیـن',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'هـأمـه',
'namespacesall' => 'هأمه',
'monthsall'     => 'هـأمـه',

# Multipage image navigation
'imgmultigo' => 'بور!',

# Special:SpecialPages
'specialpages' => 'شـا ولـگ‌ئون',

);

<?php
/** Bishnupriya Manipuri (বিষ্ণুপ্রিয়া মণিপুরী)
 *
 * @addtogroup Language
 *
 * @author Uttam Singha, Dec 2006
 */
 
$digitTransformTable = array(
	'0' => '০',
	'1' => '১',
	'2' => '২',
	'3' => '৩',
	'4' => '৪',
	'5' => '৫',
	'6' => '৬',
	'7' => '৭',
	'8' => '৮',
	'9' => '৯'
);

$namespaceNames = array(
	NS_MEDIA          => 'মিডিয়া',
	NS_SPECIAL        => 'বিশেষ',
	NS_MAIN           => '',
	NS_TALK           => 'য়্যারী',
	NS_USER           => 'আতাকুরা',
	NS_USER_TALK      => 'আতাকুরার_য়্যারী',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => '$1_য়্যারী',
	NS_IMAGE          => 'ছবি',
	NS_IMAGE_TALK     => 'ছবি_য়্যারী',
	NS_MEDIAWIKI      => 'মিডিয়াউইকি',
	NS_MEDIAWIKI_TALK => 'মিডিয়াউইকির_য়্যারী',
	NS_TEMPLATE       => 'মডেল',
	NS_TEMPLATE_TALK  => 'মডেলর_য়্যারী',
	NS_HELP           => 'পাংলাক',
	NS_HELP_TALK      => 'পাংলাকর_য়্যারী',
	NS_CATEGORY       => 'থাক',
	NS_CATEGORY_TALK  => 'থাকর_য়্যারী',
);

$messages = array(
# User preference toggles
'tog-underline'               => 'লিঙ্কর তলে দুরগ দিক:',
'tog-highlightbroken'         => 'বাগা লিঙ্ক অতারে<a href="" class="new">এসারে</a> দেখাদে (নাইলে: এসারে<a href="" class="internal">?</a>).',
'tog-justify'                 => 'অনুচ্ছেদহানির দুরগি দ্বিয়পারাদেত্ত মান্নাকরিক',
'tog-hideminor'               => 'হুরু পতানি গুর',
'tog-extendwatchlist'         => 'পতাসি অতা দেখা দেনারকা আহিরফঙে থসি তালিকাহান সালকরানি অক',
'tog-usenewrc'                => 'পতাসি অতারমা হাব্বিত্ত ঙালসেতা (জাভাস্ক্রিপ্ট)',
'tog-numberheadings'          => 'নিজেলত্ত পাজালার চিঙনাঙ',
'tog-showtoolbar'             => 'পতানির আতিয়ার দেহাদে (জাভাস্ক্রিপ্ট)',
'tog-editondblclick'          => 'দ্বিমাউ যাতিয়া পতাহান পতিক (জাভাস্ক্রিপ্ট)',
'tog-editsection'             => '[পতিক] লিঙ্ক এহান্ন পরিচ্ছদ পতানি অক',
'tog-editsectiononrightclick' => 'পরিচ্ছদ পতানির য়্যাথাঙহান বাতেদের গোথামগ <br /> পরিচ্ছদর চিঙনাঙর গজে যাতিলে দে (জাভাস্ক্রিপ্ট)',
'tog-showtoc'                 => 'বিষয়র মাঠেলহানি দেহাদে (যে পাতারতা ৩হানর গজে চিঙনাঙ আসে)',
'tog-rememberpassword'        => 'কম্পিউটার এহাত মর লগইন নিঙশিঙে থ',
'tog-editwidth'               => 'পতিক উপুগর দীঘালাহান পুরা ইসে',
'tog-watchcreations'          => 'যে পতাহানি মি ইকরিসু অতা মর তালাবির তালিকাত থ',
'tog-watchdefault'            => 'যে পতাহানি মি পতাসু অতা মর তালাবির তালিকাত থ',
'tog-watchmoves'              => 'যে পতাহানি মি থেইকরিসু অতা মর তালাবির তালিকাত থ',
'tog-watchdeletion'           => 'যে পতাহানি মি পুসিসু অতা মর তালাবির তালিকাত থ',
'tog-minordefault'            => 'অকরাতই হাব্বি পতা ফাঙনেই বুলিয়া দেহাদে',
'tog-previewontop'            => 'পতা উপুগর গজে লেহার মিল্লেখ দেহাদে',
'tog-previewonfirst'          => 'পয়লা পতানিহাত মিল্লেখ দেহাদে',
'tog-nocache'                 => 'পাতা য়মকরানি থা নাদি',
'tog-enotifwatchlistpages'    => 'মরে ইমেইল কর যদি মর মিল্লেঙে থসু অতা পতিলে',
'tog-enotifusertalkpages'     => 'মরে ইমেইল কর যদি মর য়্যারির পাতা পতিলে',
'tog-enotifminoredits'        => 'মরে ইমেইল কর পাতা আহানর পতানিহান হুরু ইলেউ',
'tog-enotifrevealaddr'        => 'জানানি মেইল অতাত মর ইমেইলর ঠিকানাহান ফঙকর',
'tog-shownumberswatching'     => 'চাকুরার সংখ্যাহান দেহাদে',
'tog-fancysig'                => 'দস্তখত তিলকরানি (নিজেত্ত লিঙ্ক নেইকরিয়া)',
'tog-externaleditor'          => 'পয়লাকাত্তই বারেদের পতানির আতিয়ার আতা',
'tog-externaldiff'            => 'পয়লাকাত্ত বারেদের ফারাকহান আতা',
'tog-showjumplinks'           => '"চঙদে" বুলতারা মিলাপর য়্যাথাঙদে',
'tog-uselivepreview'          => 'লগে লগে মিল্লেঙ আহান দেহাদে (জাভাস্ক্রিপ্ট) (লইনাসে)',
'tog-forceeditsummary'        => 'খালি পতা সারমর্ম হমিলে মরে হারপুৱাদে',
'tog-watchlisthideown'        => 'মি পতাসু অতা গুর মর তালাবিত্ত',
'tog-watchlisthidebots'       => 'বটল পতাসি অতা গুর মর তালাবিত্ত',
'tog-watchlisthideminor'      => 'হুরু করে পতাসি অতা গুর মর তালাবিত্ত',
'tog-nolangconversion'        => 'সারুকর সিলপা থেপকর',
'tog-ccmeonemails'            => 'আরতারে দিয়াপেঠাউরি ইমেইল মরাঙউ কপি আহান যাকগা',
'tog-diffonly'                => 'ফারাকর তলে পাতাহানর বিষয়বস্তু নাদেখাদি',

'underline-always'  => 'হারি সময়',
'underline-never'   => 'সুপৌনা',
'underline-default' => 'বাউজারগত যেসারে আসিল',

'skinpreview' => '(মিল্লেখ)',

# Dates
'sunday'        => 'লামুইসিং',
'monday'        => 'নিংথৌকাপা',
'tuesday'       => 'লেইপাকপা',
'wednesday'     => 'ইনসাইনসা',
'thursday'      => 'সাকলসেন',
'friday'        => 'ইরেই',
'saturday'      => 'থাংচা',
'sun'           => 'লামু',
'mon'           => 'নিং',
'tue'           => 'লেই',
'wed'           => 'ইন',
'thu'           => 'সাকল',
'fri'           => 'ইরে',
'sat'           => 'থাং',
'january'       => 'জানুয়ারী',
'february'      => 'ফেব্রুয়ারী',
'march'         => 'মার্চ',
'april'         => 'এপ্রিল',
'may_long'      => 'মে',
'june'          => 'জুন',
'july'          => 'জুলাই',
'august'        => 'আগস্ট',
'september'     => 'সেপ্টেম্বর',
'october'       => 'অক্টোবর',
'november'      => 'নভেম্বর',
'december'      => 'ডিসেম্বর',
'january-gen'   => 'জানুয়ারী',
'february-gen'  => 'ফেব্রুয়ারী',
'march-gen'     => 'মার্চ',
'april-gen'     => 'এপ্রিল',
'may-gen'       => 'মে',
'june-gen'      => 'জুন',
'july-gen'      => 'জুলাই',
'august-gen'    => 'আগষ্ট',
'september-gen' => 'সেপ্টেম্বর',
'october-gen'   => 'অক্টোবর',
'november-gen'  => 'নভেম্বর',
'december-gen'  => 'ডিসেম্বর',
'jan'           => 'জানু',
'feb'           => 'ফেব্রু',
'mar'           => 'মার্চ',
'apr'           => 'এপ্রিল',
'may'           => 'মে',
'jun'           => 'জুন',
'jul'           => 'জুলাই',
'aug'           => 'আগস্ট',
'sep'           => 'সেপ্টে',
'oct'           => 'অক্টো',
'nov'           => 'নভে',
'dec'           => 'ডিসে',

# Bits of text used by many pages
'categories'            => 'বিষয়রথাকহানি',
'pagecategories'        => '{{PLURAL:$1|থাক|থাকহানি}}',
'category_header'       => '"$1" বিষয়রথাকে আসে নিবন্ধহানি',
'subcategories'         => 'উপথাক',
'category-media-header' => '"$1" থাকর মিডিয়া',
'category-empty'        => "''এরে থাক এহাত এবাকা কোন পাতা বা মিডিয়া নেই''",

'mainpagetext'      => "<big>'''মিডিয়াউইকি হবাবালা ইয়া ইন্সটল ইল'''</big>",
'mainpagedocfooter' => 'উইকি সফটৱ্যার এহান আতানির বারে দরকার ইলে [http://meta.wikimedia.org/wiki/Help:Contents আতাকুরার গাইড]হানর পাঙলাক নেগা।

== অকরানিহান ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings কনফিগারেশন সেটিংর তালিকাহান]
* [http://www.mediawiki.org/wiki/Manual:FAQ মিডিয়া উইকি আঙলাক]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce মিডিয়া উইকির ফঙপার বারে মেইলর তালিকাহান]',

'about'          => 'বারে',
'article'        => 'মেথেলর পাতা',
'newwindow'      => '(নুৱা উইন্ডত নিকুলতই)',
'cancel'         => 'বাতিল করেদে',
'qbfind'         => 'বিসারিয়া চা',
'qbbrowse'       => 'বুলিয়া চা',
'qbedit'         => 'পতানি',
'qbpageoptions'  => 'পাতা এহানর সারুক',
'qbpageinfo'     => 'পাতা এহানর পৌ',
'qbmyoptions'    => 'মর পছন',
'qbspecialpages' => 'বিশেষ পাতাহানি',
'moredotdotdot'  => 'আরাকউ...',
'mypage'         => 'মর পাতাহান',
'mytalk'         => 'মর য়্যারি-পরি',
'anontalk'       => 'অচিনা এগর য়্যারির পাতা',
'navigation'     => 'দিশা-ধরুনী',

# Metadata in edit box
'metadata_help' => 'মেটাডাটা:',

'errorpagetitle'    => 'লাল',
'returnto'          => '$1-ত আলথকে যাগা।',
'tagline'           => 'মুক্ত বিশ্বকোষ উইকিপিডিয়াত্ত',
'help'              => 'পাংলাক',
'search'            => 'বিসারিয়া চা',
'searchbutton'      => 'বিসারানি',
'go'                => 'হাত',
'searcharticle'     => 'হাত',
'history'           => 'পতাহানর ইতিহাসহান',
'history_short'     => 'ইতিহাসহান',
'updatedmarker'     => 'লমিলগা চানাহাত্ত বদলিসেতা',
'info_short'        => 'পৌ',
'printableversion'  => 'ছাপানি একরব সংস্করণ',
'permalink'         => 'আকুবালা মিলাপ',
'print'             => 'ছাপা',
'edit'              => 'পতানি',
'editthispage'      => 'পাতা এহান পতিক',
'delete'            => 'পুসানি',
'deletethispage'    => 'পাতা এহান পুসে বেলিক',
'undelete_short'    => 'পুসানিহান আলকর {{PLURAL:$1|পতাহান|$1 পতাহানি}}',
'protect'           => 'লুকর',
'protect_change'    => 'লুকরানিহান সিলকর',
'protectthispage'   => 'পাতা এহান লু কর',
'unprotect'         => 'লু নাকরি',
'unprotectthispage' => 'পাতা এহানর লুপাহান এরাদিক',
'newpage'           => 'নুৱা পাতা',
'talkpage'          => 'পাতা এহান্ন য়্যারি দিক',
'talkpagelinktext'  => 'য়্যারি',
'specialpage'       => 'বিশেষ পাতাহান',
'personaltools'     => 'নিজস্ব আতিয়ার',
'postcomment'       => 'নিজর মতহান থ',
'articlepage'       => 'নিবন্ধ চেইক',
'talk'              => 'য়্যারী',
'views'             => 'চা',
'toolbox'           => 'আতিয়ার',
'userpage'          => 'আতাকুরার পাতাহান চেইক',
'projectpage'       => 'প্রকল্পর পাতাহান',
'imagepage'         => 'ছবির পাতাহান চেইক',
'mediawikipage'     => 'পৌর পাতাহান চা',
'templatepage'      => 'মডেলর পাতাহান চা',
'viewhelppage'      => 'পাঙলাকর পাতাহান চা',
'categorypage'      => 'বিষয়থাকর পাতাহানি চা',
'viewtalkpage'      => 'য়্যারীর পাতাহান চেইক',
'otherlanguages'    => 'আরআর ঠারে',
'redirectedfrom'    => '($1 -ত্ত পাকদিয়া আহিল)',
'redirectpagesub'   => 'কুইপা পাতা',
'lastmodifiedat'    => 'পাতা এহানর লমিলগা পতানিহান $2, $1.', # $1 date, $2 time
'viewcount'         => 'পাতা এহান $1 মাউ চানা ইল।',
'protectedpage'     => 'লুকরা পাতা',
'jumpto'            => 'চঙদে:',
'jumptonavigation'  => 'দিশা ধরানি',
'jumptosearch'      => 'বিসারা',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => '{{SITENAME}}র বারে',
'aboutpage'         => '{{ns:project}}:বারে',
'bugreports'        => 'লাল বিবরণী',
'bugreportspage'    => '{{ns:project}}:লাল_বিবরণী',
'copyright'         => '$1-র মাতুঙে এহানর মেথেলহানি পানা একরের।',
'copyrightpagename' => '{{SITENAME}} স্বত্তাধিকারহান',
'copyrightpage'     => '{{ns:project}}:স্বত্তাধিকারহানি',
'currentevents'     => 'হাদি এহানর ঘটনা',
'currentevents-url' => 'হাদি এহানর ঘটনাহানি',
'disclaimers'       => 'দাবি বেলানি',
'disclaimerpage'    => '{{ns:project}}:ইজ্জু দাবি বেলানি',
'edithelp'          => 'পতানি পাংলাক',
'edithelppage'      => '{{ns:project}}:কিসাদে_পাতা_আহান_পতানি',
'faq'               => 'আঙলাক',
'faqpage'           => '{{ns:project}}:আঙলাক',
'helppage'          => '{{ns:project}}:পাংলাক',
'mainpage'          => 'পয়লা পাতা',
'policy-url'        => '{{ns:project}}:নীতিহান',
'portal'            => 'শিংলুপ',
'portal-url'        => '{{ns:project}}:শিংলুপ',
'privacy'           => 'লুকরানির নীতিহান',
'privacypage'       => '{{ns:project}}:লুকরানির নীতিহান',
'sitesupport'       => 'দান দেনা',
'sitesupport-url'   => '{{ns:project}}:দান দেনা',

'badaccess'        => 'য়্যাথাঙে লালসে',
'badaccess-group0' => 'তি যে কামহানর হেইচা করিসত, তরতা অহান করানির য়্যাথাং নেই।',
'badaccess-group1' => 'তি যে কামহানর হেইচা করিসত, অহান করানির য়্যাথাং হুদ্দা $1 গ্রুপরতা আসে।',
'badaccess-group2' => 'তি যে কামহানর হেইচা করিসত, অহান করানির য়্যাথাং হুদ্দা $1 গ্রুপর আতাকুরারতা আসে।',
'badaccess-groups' => 'তি যে কামহানর হেইচা করিসত, অহান করানির য়্যাথাং হুদ্দা $1 গ্রুপরতা আসে।',

'ok'              => 'চুমিসে',
'retrievedfrom'   => "'$1' -ত্ত আনানি অসে",
'newmessageslink' => 'নুৱা পৌ',
'editsection'     => 'পতিক',
'editold'         => 'পতিক',
'toc'             => 'মেথেল',
'showtoc'         => 'ফংকর',
'hidetoc'         => 'মেথেল আরুম কর',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'নিবন্ধ',
'nstab-user'      => 'আতাকুরার পাতা',
'nstab-special'   => 'বিশেষ',
'nstab-project'   => 'প্রকল্প পাতা',
'nstab-mediawiki' => 'পৌ',

# General errors
'error'              => 'লালুইসে',
'cachederror'        => 'এরে পাতা এহান বা লাতলগ পুছানি নাকরল। (নিঙকরুরিতাঃ আগেদে কুঙগ আগই পুছে বেলাসিসাত)',
'badarticleerror'    => 'এরে পাতা এহান কাম এহান করানি সম্ভব নেই।',
'badtitle'           => 'শিরোনাঙহান গ্রহনযোগ্য নাইসে।',
'viewsource'         => 'উৎসহান চা',
'protectedinterface' => 'পাতা এহানর মেথেল উইকি সফটওয়্যারর ইন্টারফেসর পৌহান দের, অহানে এহানরে ইতু করিয়া থনা অসে এবিউসেত্ত ঙাক্করানির কাজে।',

# Login and logout pages
'logouttitle'                => 'আতাকুরার নিকুলানি',
'welcomecreation'            => '== সম্ভাষা, $1! ==

তর একাউন্টহান মুকিল। তর {{SITENAME}} পছনহান পতানি না পাহুরিস।',
'loginpagetitle'             => 'আতাকুরার হমানি',
'yourname'                   => 'আতাকুরার নাংহান (Username)',
'yourpassword'               => 'খন্তাচাবিগ (password)',
'yourpasswordagain'          => 'খন্তাচাবিগ (password) আরাকমু ইকর',
'remembermypassword'         => 'এরে কম্পিউটার এহাত্ত সাইট এহাত মর হমানিহান মনে থ',
'yourdomainname'             => 'তর ডোমেইনগ',
'login'                      => 'হমানি',
'userlogin'                  => 'হমানি / নৱা একাউন্ট খুলানি',
'logout'                     => 'নিকুলানি',
'userlogout'                 => 'নিকুলানি',
'badretype'                  => 'খন্তাচাবি (password) দ্বিয়গি না মিলের।',
'youremail'                  => 'ই-মেইল *:',
'yourrealname'               => 'আৱৈপা নাংহান *:',
'yourlanguage'               => 'ঠারহান:',
'yournick'                   => 'দাহানির নাংহান:',
'acct_creation_throttle_hit' => 'ঙাক্করেদিবাং, তি এবাকাপেয়া $1হান অ্যাকাউন্ট হংকরেবেলাসত৷ অতাত্ত বপ হঙকরানির য়্যাথাং নেই।৷',
'accountcreated'             => 'একাউন্টহান হঙকরানি ইল',
'accountcreatedtext'         => 'আতাকুরা $1 -র কা একাউন্টহান হঙকরানি ইল।',

# Edit page toolbar
'bold_sample'     => 'গাঢ়পা ৱাহি',
'bold_tip'        => 'গাঢ়পা ৱাহি',
'headline_sample' => 'চিঙনাঙর খন্তাহানি',
'nowiki_tip'      => 'উইকির পাজালানিহান লালুয়া যাগা',

# Edit pages
'minoredit'        => 'এহান হুরু-মুরু সম্পাদনাহানহে।',
'watchthis'        => 'পাতাএহান খিয়ালে থ',
'anoneditwarning'  => "'''সিঙুইসঃ''' তি লগইন নাকরিসত। পতানির ইতিহাসহাত তর IP addressহান সিজিল ইতই।",
'blockedtitle'     => 'আতাকুরাগরে থেপ করানি অসে',
'blockedtext'      => "তর আতাকুরা নাঙহান নাইলেউ আইপি ঠিকানাহানরে $1 থেপকরানি অসে। এহানর কারণহান অসেতাইঃ:<br />''$2''<p>তি $1 নাইলেউ [[{{MediaWiki:grouppage-sysop}}|প্রশাসকর]] মা যে কোন আগর লগে বিষয় এহান্ন য়্যারি পরি দে পারর। বিশেষ মাতিলতাঃ তর ই-মেইল ঠিকানাহান যদি [[Special:Preferences|তর পছন তালিকাত]] বরিয়া নাথার, অতা ইলে তি উইকিপিডিয়াত হের আতাকুরারে ই-মেইল করানি নুৱারবে। তর আইপি ঠিকানাহান ইলতাই $3। কৃপা করিয়া যে কোন যোগাযোগর সময়ত এরে ঠিকানা এহান যেসাদেউ বরিস।",
'confirmedittitle' => 'সম্পাদনা করানির কা ই-মেইল লেপকানি থকিতই',
'confirmedittext'  => 'যেহানউ সম্পাদনা করানির আগে তর ই-মেইল ঠিকানাহন যেসাদেউ লেপকরানি লাগতই। কৃপাকরিয়া তর ই-মেইল ঠিকানাহান [[Special:Preferences|আতাকুরার পছনতালিকা]]ত চুমকরে বরা।',
'loginreqtitle'    => 'লগইন দরকার ইসে',
'accmailtitle'     => 'খন্তাচাবি(password) দিয়াপেঠৱা দিলাং।',
'accmailtext'      => '"$1"-র খন্তাচাবি(password) $2-রাঙ দিয়াপেঠৱাদেনা ইল।',
'anontalkpagetext' => "''এহান অচিনা অতার য়্যারির পাতাহান। এরে আইপি ঠিকানা (IP Address) এহানাত্ত লগ-ইন নাকরিয়া পতানিত মেইক্ষু অসিল। আক্কুস ক্ষেন্তামে আইপি ঠিকানা হামেসা বদল অর, বিশেষ করিয়া ডায়াল-আপ ইন্টারনেট, প্রক্সি সার্ভার মাহি ক্ষেত্র এতা সিলরতা, বারো আগত্ত বপ ব্যবহারকারেকুরার ক্ষেত্রত প্রযোজ্য ইতে পারে। অহানে তি নিশ্চকে এরে আইপি এহাত্ত উইকিপিডিয়াত হমিয়া কোন য়্যারী দেখর, অহান তরে নিঙকরিয়া নাউ ইতে পারে। অহানে হাবিত্ত হবা অর, তি যদি [[Special:Userlogin|লগ-ইন করর, বা নৱা একাউন্ট খুলর]] অহানবুলতেউ লগ-ইন করলে কুঙগউ তর আইপি ঠিকানাহান, বারো অহানর মাতুঙে তর অবস্থানহান সুপকরেউ হার না পেইবা।''",
'clearyourcache'   => "'''খিয়াল থ:''' তর পছনহানি রক্ষা করানির থাঙনাত পতাহানি চানার কা তর ব্রাউজারর ক্যাশ লালুয়া যানা লাগতে পারে। '''মোজিলা/ফায়ারফক্স/সাফারি:''' শিফট কী চিপিয়া থয়া রিলোড-এ ক্লিক কর, নাইলে ''কন্ট্রোল-শিফট-R''(এপল ম্যাক-এ ''কমান্ড-শিফট-R'') আকপাকে চিপা; '''ইন্টারনেট এক্সপ্লোরার:''' ''কন্ট্রোল'' চিপিয়া থয়া রিফ্রেশ-এ ক্লিক কর, নাইলে ''কন্ট্রোল-F5'' চিপা; '''কংকারার:''' হুদ্দা রিলোড ক্লিক করলে বা F5 চিপিলে চলতই; '''অপেরা''' আতাকুরাই ''Tools→Preferences''-এ গিয়া কাশ সম্পূর্ণ ঙক্ষি করানি লাগতে পারে।",
'yourtext'         => 'তর ইকরা বিষয়হানি',
'yourdiff'         => 'ফারাকহানি',

# History pages
'currentrev'          => 'হাদিএহানর পতানি',
'currentrevisionlink' => 'হাদি এহানর পতানি',
'histlegend'          => 'ফারাক (Diff) বাছানি: যে সংস্করণহানি তুলনা করানি চার, অহান লেপকরিয়া এন্টার বা তলর খুথামগত যাতা।<br />
নির্দেশিকা: (এব) = এবাকার সংস্করণহানর লগে ফারাক,(আ) =  জানে আগে-আগে গেলগা সংস্করণহানর লগে ফারাক, হ = হুরু-মুরু (নামাতলেউ একরব অসারে) সম্পাদনাহান।',
'histfirst'           => 'হাব্বিত্ত পুরানা',
'histlast'            => 'হাব্বিত্ত নুৱা',

# Diffs
'compareselectedversions' => 'বাসাইল সংস্করণহানি তুলনা কর',

# Preferences page
'mypreferences'  => 'মর পছন',
'changepassword' => 'খন্তাচাবি(password) পতা',
'saveprefs'      => 'ইতু',
'columns'        => 'দুরগিঃ',
'allowemail'     => 'আরতা(ব্যবহার করেকুরা)ই ইমেইল করানির য়্যাথাং দে।',

# Recent changes
'recentchanges'   => 'হাদিএহান পতাসিতা',
'diff'            => 'ফারাক',
'hist'            => 'ইতিহাসহান',
'hide'            => 'আরুম',
'minoreditletter' => 'হ',
'newpageletter'   => 'নু',
'boteditletter'   => 'ব',

# Recent changes linked
'recentchangeslinked' => 'সাকেই আসে পতা',

# Upload
'upload'          => 'আপলোড ফাইল',
'uploadbtn'       => 'আপলোড',
'badfilename'     => 'ফাইলগর নাঙহান পতিয়া $1" করানি ইল।',
'savefile'        => 'ফাইল ইতু',
'watchthisupload' => 'পাতাএহান খিয়ালে থ',

# Image list
'imagelist'  => 'ছবির তালিকা',
'ilsubmit'   => 'বিসারা',
'byname'     => 'নাঙর সিজিলন',
'bydate'     => 'তারিখর সিজিলন',
'bysize'     => 'আকারহানর সিজিলন',
'imagelinks' => 'জুরিসিতা',

'brokenredirects' => 'বারো-নির্দেশ কামনাকরের',

# Miscellaneous special pages
'nbytes'       => '$1 বাইট',
'ncategories'  => '$1 {{PLURAL:$1|থাক|থাকহানি}}',
'allpages'     => 'হাবি পাতাহানি',
'randompage'   => 'খাংদা পাতা',
'specialpages' => 'বিশেষ পাতাহানি',
'ancientpages' => 'পুরানা পাতাহানি',
'move'         => 'থেইকরানি',

# Book sources
'booksources' => 'লেরিকর উৎসহান',

'categoriespagetext' => 'ইমারঠারর উইকিপিডিয়াত এবাকার বিষয়রথাক:',
'alphaindexline'     => '$1 ত $2',

# Special:Log
'specialloguserlabel' => 'আতাকুরাগ:',

# Special:Allpages
'allpagesfrom'   => 'যেহাত্ত অকরিসি অহাত্ত পাতাহানি দেহাদেঃ',
'allarticles'    => 'নিবন্ধহাবি',
'allinnamespace' => 'পাতাহানি হাবি ($1 নাঙরজাগা)',
'allpagesprev'   => 'আলথকে',
'allpagesnext'   => 'থাঙনাত',
'allpagessubmit' => 'হাত',
'allpagesprefix' => 'মেয়েক এগন অকরিসি ৱাহির পাতাহানি দেহাদেঃ',

# Watchlist
'watchlist'       => 'মর তালাবি',
'mywatchlist'     => 'মর তালাবি',
'addedwatch'      => 'তালাবির তালিকাহাত থনা ইল',
'addedwatchtext'  => "\"\$1\" পাতা এহান তর [[Special:Watchlist|আহির-আরুম তালিকা]]-ত তিলকরানি ইল। পিসেদে এরে পাতা এহান বারো পাতা এহানর লগে সাকেই আসে য়্যারী পাতাত অইতই হারি জাতর পতানি এহানাত তিলকরানি অইতই। অতাবাদেউ [[Special:Recentchanges|হাদি এহানর পতানিহানি]]-ত পাতা এহানরে '''গাঢ়করা''' মেয়েকে দেহা দেনা অইতই যাতে তি নুঙিকরে পাতা এহান চিনে পারবেতা। <p>পিসেদে তি পাতা এহানরে থেইকরানি মনেইলে \"আহির-আরুমেত্ত থেইকরেদে\" ট্যাবগত ক্লিক করিস৷",
'watch'           => 'তালাবি',
'watchthispage'   => 'পাতাএহান খিয়ালে থ',
'unwatch'         => 'তালাবি নেই',
'unwatchthispage' => 'তালাবি এরাদেনা',

'changed' => 'পতেসে',

# Delete/protect/revert
'confirm'        => 'লেপকরানি',
'confirmdelete'  => 'পুসানিহান লেপকর',
'actioncomplete' => 'কামহান লমিল।',
'cantrollback'   => 'আগেকার সঙস্করনহাত আলথকে যানা নুৱারলু, লমিলগা সম্পদনাকরেকুরা অগ পাতা অহানর আকখুলা লেখকগ।',

# Restrictions (nouns)
'restriction-edit' => 'পতানিহান_চিয়ৌকর',

# Namespace form on various pages
'blanknamespace' => '(গুরি)',

# Contributions
'mycontris' => 'মর অবদান',

# What links here
'whatlinkshere' => 'যে পাতাহানিত্ত এহানাত মিলাপ আসে',

# Block/unblock
'blockip'            => 'আতাকুরাগরে থেপকর',
'badipaddress'       => 'আইপি ঠিকানাহান গ্রহনযোগ্যনাইসে',
'blockipsuccesssub'  => 'থেপকরানিহান চুমিল',
'blockipsuccesstext' => '[[Special:Contributions/$1|$1]] রে থেপকরিয়া থসি <br />থেপকরানিহান খাল করানি থকিলে,[[Special:Ipblocklist| থেপকরিয়া থসি আইপি ঠিকানার তালিকাহান]] চা।',
'blocklistline'      => '$1 তারিখে $2, $3 ($4) রে থেপকরানি অসে।',
'blocklink'          => 'থেপ কর',
'contribslink'       => 'অবদান',
'blocklogpage'       => 'থেপকরানির log',
'blocklogentry'      => '"[[$1]]"-রে $2 মেয়াদর কা থেপকরানি অসে। $3',

# Move page
'articleexists'           => 'ইতে পারে এরে শিরোনাঙর নিবন্ধহান হঙপরসেগা, নাইলে তি দিয়াসত শিরোনাং এহান দেনার য়্যাথাং নেই। কৃপা করিয়া আরাক শিরোনাং আহান দেনার হৎনা কর।',
'1movedto2'               => '[[$1]]-রে [[$2]]-ত গুসানি ইল',
'1movedto2_redir'         => '[[$1]]-রে [[$2]]-ত বারো-র্নির্দেশনার মা থেইকরানি ইল',
'delete_and_move'         => 'পুসানি বারো থেইকরানি',
'delete_and_move_confirm' => 'হায়, পাতা এহান পুস',

# Namespace 8 related
'allmessages'         => 'সিস্টেমর পৌহানি',
'allmessagesname'     => 'নাং',
'allmessagescurrent'  => 'হাদি এহানর ৱাহি',
'allmessagestext'     => 'তলে মিডিয়াউইকি: নাঙরজাগাত পানা একরের সিস্টেম পৌহানির তালিকাহান দেনা ইল।',
'allmessagesmodified' => 'পতাসি অতা হুদ্দা দেহাদে',

# Tooltip help for the actions
'tooltip-p-logo' => 'পয়লা পাতা',

# Attribution
'anonymous' => '{{SITENAME}}র বেনাঙর আতাকুরা(গি)',
'and'       => 'বারো',

# Spam protection
'categoryarticlecount' => 'এরে বিষয়রথাকে $1হান নিবন্ধ আসে।',

# E-mail address confirmation
'confirmemail'            => 'ই-মেইল ঠিকানাহান লেপকর',
'confirmemail_send'       => 'লেপকরেকুরা কোডগ দিয়াপেঠাদে',
'confirmemail_sent'       => 'লেপকরেকুরা ই-মেইলহান দিয়াপেঠা দিলাং।',
'confirmemail_sendfailed' => 'লেপকরেকুরা ই-মেইলহান দিয়াপেঠাদে নুৱাররাং। ইমেইল ঠিকানাহান চুমকরে ইকরিসত্তানাকিতা আরাক আকমু খিয়াল করিয়া চা। আলথকে আহিলঃ $1',
'confirmemail_invalid'    => 'লেপকরেকুরা কোডগ চুম নাইসে। সম্ভবতঃ এগ পুরানা ইয়া পরসেগা।',
'confirmemail_success'    => 'তর ই-মেইল ঠিকানাহার লেপ্পাহান চুমিল। তি এবাকা হমানি(log in) পারর।',
'confirmemail_loggedin'   => 'তর ই-মেইল ঠিকানাহার লেপকরানিহান চুমিল।',

# action=purge
'confirm_purge'        => 'পাতা এহানর ক্যাশহান ঙক্ষি করানি মনারতা? 

$1',
'confirm_purge_button' => 'চুমিসে',

# AJAX search
'articletitles' => "যে পাতাহানি ''$1'' ন অকরাগ, অতার তালিকা",

# Auto-summaries
'autoredircomment' => '[[$1]]-ত যানার বারো-র্নিদেশ করানি ইল',

);

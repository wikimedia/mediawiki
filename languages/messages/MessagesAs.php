<?php
/** Assamese (অসমীয়া)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Chaipau
 * @author Priyankoo
 * @author Psneog
 * @author Rajuonline
 * @author Urhixidur
 */

$fallback = 'bn';

$namespaceNames = array(
	NS_MEDIA            => 'মাধ্যম',
	NS_SPECIAL          => 'বিশেষ',
	NS_TALK             => 'বার্তা',
	NS_USER             => 'সদস্য',
	NS_USER_TALK        => 'সদস্য বার্তা',
	NS_PROJECT_TALK     => '$1 বার্তা',
	NS_FILE             => 'চিত্র',
	NS_FILE_TALK        => 'চিত্র বার্তা',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki বার্তা',
	NS_TEMPLATE         => 'সাঁচ',
	NS_TEMPLATE_TALK    => 'সাঁচ বার্তা',
	NS_HELP             => 'সহায়',
	NS_HELP_TALK        => 'সহায় বার্তা',
	NS_CATEGORY         => 'শ্রেণী',
	NS_CATEGORY_TALK    => 'শ্রেণী বার্তা',
);

$namespaceAliases = array(
	'विशेष' => NS_SPECIAL,
	'वार्ता' => NS_TALK,
	'सदस्य' => NS_USER,
	'सदस्य_वार्ता' => NS_USER_TALK,
	'$1_वार्ता' => NS_PROJECT_TALK,
	'चित्र' => NS_FILE,
	'चित्र_वार्ता' => NS_FILE_TALK,
	'साँचा' => NS_TEMPLATE,
	'साँचा_वार्ता' => NS_TEMPLATE_TALK,
	'श्रेणी' => NS_CATEGORY,
	'श्रेणी_वार्ता' => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Userlogin'                 => array( 'সদস্যৰ_প্রবেশ' ),
	'Userlogout'                => array( 'সদস্যৰ_প্রস্থান' ),
	'CreateAccount'             => array( 'সদস্যভুক্তি' ),
	'Preferences'               => array( 'পচন্দ' ),
	'Watchlist'                 => array( 'লক্ষ্যতালিকা' ),
	'Recentchanges'             => array( 'শেহতীয়া_কাম' ),
	'Upload'                    => array( 'বোজাই' ),
	'Listfiles'                 => array( 'চিত্র-তালিকা' ),
	'Newimages'                 => array( 'নতুন_চিত্র' ),
	'Listusers'                 => array( 'সদস্য-তালিকা' ),
	'Listgrouprights'           => array( 'গোটৰ_অধিকাৰসমুহ' ),
	'Statistics'                => array( 'পৰিসংখ্যা' ),
	'Randompage'                => array( 'আকস্মিক' ),
	'Lonelypages'               => array( 'অকলশৰীয়া_পৃষ্ঠা' ),
	'Uncategorizedpages'        => array( 'অবিন্যস্ত_পৃষ্ঠাসমুহ' ),
	'Uncategorizedcategories'   => array( 'অবিন্যস্ত_শ্ৰেণীসমূহ' ),
	'Uncategorizedimages'       => array( 'অবিন্যস্ত_চিত্ৰবোৰ' ),
	'Uncategorizedtemplates'    => array( 'অবিন্যস্ত_সাঁচবোৰ' ),
	'Unusedcategories'          => array( 'অব্যৱহৃত_শ্ৰেণীসমূহ' ),
	'Unusedimages'              => array( 'অব্যৱহৃত_চিত্ৰবোৰ' ),
	'Wantedpages'               => array( 'আকাংক্ষিত_পৃষ্ঠাসমূহ' ),
	'Wantedcategories'          => array( 'আকাংক্ষিত_শ্ৰেণীসমূহ' ),
	'Allpages'                  => array( 'সকলোবোৰ_পৃষ্ঠা' ),
	'Specialpages'              => array( 'বিশেষ_পৃষ্ঠাবোৰ' ),
	'Contributions'             => array( 'অৱদানবোৰ' ),
	'Mypage'                    => array( 'মোৰ_পৃষ্ঠা' ),
	'Mytalk'                    => array( 'মোৰ_কথোপকথন' ),
	'Mycontributions'           => array( 'মোৰ_অৱদান' ),
	'Popularpages'              => array( 'জনপ্ৰিয়_পৃষ্ঠাসমূহ' ),
);

$digitTransformTable = array(
	'0' => '০', # &#x09e6;
	'1' => '১', # &#x09e7;
	'2' => '২', # &#x09e8;
	'3' => '৩', # &#x09e9;
	'4' => '৪', # &#x09ea;
	'5' => '৫', # &#x09eb;
	'6' => '৬', # &#x09ec;
	'7' => '৭', # &#x09ed;
	'8' => '৮', # &#x09ee;
	'9' => '৯', # &#x09ef;
);

$messages = array(
# User preference toggles
'tog-underline'               => 'সংযোগ সমূহ অধোৰেখিত কৰক:',
'tog-justify'                 => 'লেখা বিলাকৰ দুয়োকাষ সমান কৰা হওক',
'tog-hideminor'               => 'সাম্প্রতিক সাল-সলনিত অগুৰুত্বপূর্ণ সম্পাদনা নেদেখুৱাব',
'tog-hidepatrolled'           => 'সাম্প্রতিক সাল-সলনিত তহলদাৰী সম্পাদনা নেদেখুৱাব',
'tog-newpageshidepatrolled'   => 'নতুন পৃষ্ঠা তালিকাত তহলদাৰী পৃষ্ঠাসমূহ নেদেখুৱাব',
'tog-extendwatchlist'         => 'কেৱল সাম্প্ৰতিকেই নহয, লক্ষ্য-তালিকাৰ সকলো সাল-সলনি বহলাই দেখুৱাওক',
'tog-usenewrc'                => 'বর্দ্ধিত সাম্প্রতিক সাল-সলনি ব্যবহাৰ কৰক (জাভাস্ক্ৰিপ্টৰ দৰকাৰ)',
'tog-numberheadings'          => 'শীর্ষকত স্বয়ংক্রীয়ভাৱে ক্রমিক নং দিয়ক',
'tog-showtoolbar'             => 'সম্পাদনা দণ্ডিকা দেখুৱাওক (জাভাস্ক্ৰিপ্টৰ দৰকাৰ)',
'tog-editondblclick'          => 'একেলগে দুবাৰ টিপা মাৰিলে পৃষ্ঠা সম্পদনা কৰক (জাভাস্ক্ৰিপ্টৰ দৰকাৰ)',
'tog-editsection'             => '[সম্পাদনা কৰক] সংযোগৰ দ্বাৰা সম্পাদনা কৰা সক্রীয় কৰক',
'tog-editsectiononrightclick' => 'বিষয়ৰ শিৰোণামাত সো-বুটাম টিপা মাৰি সম্পাদনা কৰাতো সক্রীয় কৰক (JavaScript)',
'tog-showtoc'                 => 'শিৰোণামাৰ সুচী দেখুৱাওক (যিবোৰ পৃষ্ঠাত তিনিতাতকৈ বেছি শিৰোণামা আছে)',
'tog-rememberpassword'        => 'মোৰ প্রৱেশ এই কম্পিউটাৰত মনত ৰাখক (for a maximum of $1 {{PLURAL:$1|day|days}})',
'tog-watchcreations'          => 'মই বনোৱা সকলো পৃষ্ঠা মোৰ লক্ষ্য-তালিকাত যোগ কৰক',
'tog-watchdefault'            => 'মই সম্পাদনা কৰা সকলো পৃষ্ঠা মোৰ লক্ষ্য-তালিকাত যোগ কৰক',
'tog-watchmoves'              => 'মই স্থানান্তৰ কৰা সকলো পৃষ্ঠা মোৰ লক্ষ্য-তালিকাত যোগ কৰক',
'tog-watchdeletion'           => 'মই বিলোপ কৰা সকলো পৃষ্ঠা মোৰ লক্ষ্য-তালিকাত যোগ কৰক',
'tog-minordefault'            => 'সকলো সম্পাদনা অগুৰুত্বপূর্ণ বুলি নিজে নিজে চিহ্নিত কৰক',
'tog-previewontop'            => 'সম্পাদনা বাকছৰ ওপৰত খচৰা দেখুৱাওক',
'tog-previewonfirst'          => 'প্রথম সম্পাদনাৰ পিছ্ত খচৰা দেখুৱাওক',
'tog-nocache'                 => 'পৃষ্ঠা Caching নিষ্ক্রীয় কৰক',
'tog-enotifwatchlistpages'    => 'মোৰ লক্ষ্য-তালিকাত থকা পৃষ্ঠা সলনি হলে মোলৈ ই-মেইল পঠাব',
'tog-enotifusertalkpages'     => 'মোৰ বার্তা পৃষ্ঠা সলনি হলে মোলৈ ই-মেইল পঠাব',
'tog-enotifminoredits'        => 'অগুৰুত্বপূর্ণ সম্পাদনা হলেও মোলৈ ই-মেইল পঠাব',
'tog-enotifrevealaddr'        => 'জাননী ই-মেইল বোৰত মোৰ ই-মেইল ঠিকনা দেখুৱাব',
'tog-shownumberswatching'     => 'লক্ষ্য কৰি থকা সদস্য সমুহৰ সংখ্যা দেখুৱাওক',
'tog-oldsig'                  => 'স্বাক্ষৰৰ খচৰা:',
'tog-fancysig'                => 'স্বাক্ষৰ ৱিকিটেক্সট হিচাপে ব্যৱহাৰ কৰক (স্বয়ংক্রীয় সংযোগ অবিহনে)',
'tog-externaleditor'          => 'সদায়ে বাহ্যিক সম্পাদক ব্যৱহাৰ কৰিব (কেৱল জনা সকলৰ বাবে, ইয়াৰ বাবে আপোনাৰ কম্পিউটাৰত বিশেষ ব্যৱস্থা থাকিব লাগিব)',
'tog-showjumplinks'           => '"জপিয়াই যাওক" সংযোগ সক্রীয় কৰক',
'tog-uselivepreview'          => 'সম্পাদনাৰ লগে লগে খচৰা দেখুৱাওক (JavaScript) (পৰীক্ষামূলক)',
'tog-forceeditsummary'        => 'সম্পাদনাৰ সাৰাংশ নিদিলে মোক জনাব',
'tog-watchlisthideown'        => 'মোৰ লক্ষ্য-তালিকাত মোৰ সম্পাদনা নেদেখুৱাব',
'tog-watchlisthidebots'       => 'মোৰ লক্ষ্য-তালিকাত বটে কৰা সম্পাদনা নেদেখুৱাব',
'tog-watchlisthideminor'      => 'মোৰ লক্ষ্য-তালিকাত অগুৰুত্বপূর্ণ সম্পাদনা নেদেখুৱাব',
'tog-watchlisthideliu'        => 'প্ৰবেশ কৰা সদস্যৰ সম্পাদনাসমূহ আঁতৰাই অনুসৰণ-তালিকা দেখোৱাওক',
'tog-watchlisthideanons'      => 'বেনামী সদস্যৰ সম্পাদনাসমূহ আঁতৰাই অনুসৰণ-তালিকা দেখোৱাওক',
'tog-watchlisthidepatrolled'  => 'পৰীক্ষিত সম্পাদনাসমূহ আঁতৰাই অনুসৰণ-তালিকা দেখোৱাওক',
'tog-ccmeonemails'            => 'মই অন্য সদস্যলৈ পঠোৱা ই-মেইলৰ প্রতিলিপী এটা মোলৈও পঠাব',
'tog-diffonly'                => 'তফাৎৰ তলত পৃষ্ঠাৰ বিষয়বস্তু নেদেখোৱাব',
'tog-showhiddencats'          => 'গোপন শ্রেণী সমুহ দেখুৱাওক',

'underline-always'  => 'সদায়',
'underline-never'   => 'কেতিয়াও নহয়',
'underline-default' => 'ব্রাউজাৰ ডিফল্ট',

# Font style option in Special:Preferences
'editfont-style'     => 'সম্পাদনাৰ ফন্ট ষ্টাইল',
'editfont-default'   => "ব্ৰাউজাৰ ডিফ'ল্ট",
'editfont-monospace' => 'মনোস্পেচ ফন্ট',
'editfont-sansserif' => 'চেৰিফ-বিহীন ফন্ট',
'editfont-serif'     => 'চেৰিফ ফন্ট',

# Dates
'sunday'        => 'দেওবাৰ',
'monday'        => 'সোমবাৰ',
'tuesday'       => 'মঙ্গলবাৰ',
'wednesday'     => 'বুধবাৰ',
'thursday'      => 'বৃহস্পতিবাৰ',
'friday'        => 'শুক্রবাৰ',
'saturday'      => 'শণিবাৰ',
'sun'           => 'দেও',
'mon'           => 'সোম',
'tue'           => 'মংগল',
'wed'           => 'বুধ',
'thu'           => 'বৃহস্পতি',
'fri'           => 'শুক্র',
'sat'           => 'শনি',
'january'       => 'জানুৱাৰী',
'february'      => 'ফেব্রুৱাৰী',
'march'         => 'মাৰ্চ',
'april'         => 'এপ্ৰিল',
'may_long'      => "মে'",
'june'          => 'জুন',
'july'          => 'জুলাই',
'august'        => 'আগষ্ট',
'september'     => 'চেপ্তেম্বৰ',
'october'       => 'অক্টোবৰ',
'november'      => 'নৱেম্বৰ',
'december'      => 'ডিচেম্বৰ',
'january-gen'   => 'জানুৱাৰী',
'february-gen'  => 'ফেব্রুৱাৰী',
'march-gen'     => 'মার্চ',
'april-gen'     => 'এপ্ৰিল',
'may-gen'       => 'মে’',
'june-gen'      => 'জুন',
'july-gen'      => 'জুলাই',
'august-gen'    => 'আগষ্ট',
'september-gen' => 'চেপ্তেম্বৰ',
'october-gen'   => 'অক্টোবৰ',
'november-gen'  => 'নবেম্বৰ',
'december-gen'  => 'ডিচেম্বৰ',
'jan'           => 'জানু:',
'feb'           => 'ফেব্রু:',
'mar'           => 'মার্চ',
'apr'           => 'এপ্ৰিল',
'may'           => 'মে',
'jun'           => 'জুন',
'jul'           => 'জুলাই',
'aug'           => 'আগষ্ট',
'sep'           => 'চেপ্ত:',
'oct'           => 'অক্টো:',
'nov'           => 'নৱে:',
'dec'           => 'ডিচে:',

# Categories related messages
'pagecategories'                => '{{PLURAL:$1|শ্রেণী|শ্রেণী}}',
'category_header'               => '"$1" শ্ৰেণীৰ পৃষ্ঠাসমূহ',
'subcategories'                 => 'অপবিভাগ',
'category-media-header'         => '"$1" শ্রেণীৰ মেডিয়া',
'category-empty'                => "''এই শ্রেণীত বর্তমান কোনো লিখনী বা মাধ্যম নাই''",
'hidden-categories'             => '{{PLURAL:$1|গোপন শ্রেণী|গোপন শ্রেণী}}',
'hidden-category-category'      => 'গোপন শ্রেণী সমুহ',
'category-subcat-count'         => '{{PLURAL:$2|এই শ্রেণীত নিম্নলিখিত উপশ্রেণী আছে । এই শ্রেণীত নিম্নলিখিত {{PLURAL:$1|উপশ্রেণীটো|$1 উপশ্রেণীসমূহ}} আছে, মুঠতে $2  তা উপশ্রেণী।}}',
'category-subcat-count-limited' => 'এই শ্রেণীত নিম্নলিখিত {{PLURAL:$1|উপশ্রেণী আছে|$1 উপশ্রেণী আছে}}.',
'category-article-count'        => '{{PLURAL:$2|এই শ্রেণীটোত কেবল তলত দিয়া লিখনীটোহে আছে । এই শ্ৰেণীটোত তলৰ  {{PLURAL:$1|এটা লিখনী আছে|$1 টা লিখনী আছে}}, মুঠ লিখনী $2 টা।}}',
'listingcontinuesabbrev'        => 'আগলৈ',
'index-category'                => 'সূচীকৃত পৃষ্ঠাসমূহ',
'noindex-category'              => 'অসূচীকৃত পৃষ্ঠাসমূহ',

'about'         => 'বিষয়ে',
'article'       => 'লিখনী',
'newwindow'     => '(নতুন উইণ্ডোত খোল খায়)',
'cancel'        => 'ৰদ কৰা হওক',
'moredotdotdot' => 'ক্রমশ:...',
'mypage'        => 'মোৰ ব্যক্তিগত পৃষ্ঠা',
'mytalk'        => 'মোৰ কথাবতৰা',
'anontalk'      => 'এই IP-ত যোগাযোগ কৰক',
'navigation'    => 'দিকদৰ্শন',
'and'           => '&#32;আৰু',

# Cologne Blue skin
'qbfind'         => 'বিচৰা হওক',
'qbbrowse'       => 'বিচৰণ',
'qbedit'         => 'সম্পাদনা',
'qbpageoptions'  => 'এই পৃষ্ঠা',
'qbpageinfo'     => 'প্রসংগ',
'qbmyoptions'    => 'মোৰ পৃষ্ঠাসমুহ',
'qbspecialpages' => 'বিশেষ পৃষ্ঠাসমুহ',
'faq'            => 'প্রায়ে উঠা প্রশ্ন',
'faqpage'        => 'Project:প্রায়ে উঠা প্রশ্ন',

# Vector skin
'vector-action-addsection' => 'বিষয় যোগ',
'vector-action-delete'     => 'মচি পেলাওক',
'vector-action-move'       => 'স্থানান্তৰ কৰক',
'vector-action-protect'    => 'সংৰক্ষিত কৰক',
'vector-action-undelete'   => 'মচি পেলাওঁক',
'vector-action-unprotect'  => 'অসংৰক্ষিত কৰক',
'vector-view-create'       => 'সৃষ্টি কৰক',
'vector-view-edit'         => 'সম্পাদনা',
'vector-view-history'      => 'ইতিহাস চাওঁক',
'vector-view-view'         => 'পঢ়ক',
'vector-view-viewsource'   => 'উৎস চাওঁক',
'actions'                  => 'কাৰ্য্যসমূহ',
'namespaces'               => 'নামস্থান',
'variants'                 => 'বিকল্পসমূহ',

'errorpagetitle'    => 'ভুল',
'returnto'          => '$1 লৈ ঘুৰি যাঁওক ।',
'tagline'           => '{{SITENAME}} -ৰ পৰা',
'help'              => 'সহায়',
'search'            => 'সন্ধান',
'searchbutton'      => 'সন্ধান কৰক',
'go'                => 'গমন',
'searcharticle'     => 'গমন',
'history'           => 'খতিয়ান',
'history_short'     => 'খতিয়ান',
'updatedmarker'     => 'মোৰ শেহতীয়া আগমনৰ পাছৰ পৰিবৰ্তনবিলাক',
'info_short'        => 'বিবৰণ',
'printableversion'  => 'প্রিণ্ট কৰাৰ উপযোগী',
'permalink'         => 'স্থায়ী সুত্র(লিংক)',
'print'             => 'প্রিন্ট কৰিবলৈ',
'edit'              => 'সম্পাদন',
'create'            => 'প্রাৰম্ভন(ক্রিয়েট)',
'editthispage'      => 'বৰ্তমান পৃষ্ঠাটো সম্পাদন কৰিবলৈ',
'create-this-page'  => 'নতুন পৃষ্ঠা সৃষ্টি কৰক',
'delete'            => 'বিলোপন(ডিলিট)',
'deletethispage'    => 'বৰ্তমান পৃষ্ঠাৰ বিলোপন(ডিলিট)',
'undelete_short'    => '{{PLURAL:$1|বিলোপিত পৃষ্ঠাৰ|$1 সংখ্যক বিলোপিত পৃষ্ঠাৰ}} পূৰ্ববৎকৰণ',
'protect'           => 'সংৰক্ষ(প্রটেক্ট)',
'protect_change'    => 'শলনি কৰক',
'protectthispage'   => 'বৰ্তমান পৃষ্ঠাৰ সংৰক্ষণবিধিৰ পৰিবৰ্তন',
'unprotect'         => 'সংৰক্ষণমুক্ত কৰক',
'unprotectthispage' => 'এই পৃষ্ঠা সংৰক্ষণমুক্ত কৰক',
'newpage'           => 'নতুন পৃষ্ঠা',
'talkpage'          => 'এই পৃষ্ঠা সম্পৰ্কে কথা-বতৰা',
'talkpagelinktext'  => 'আলোচনা',
'specialpage'       => 'বিশেষ পৃষ্ঠা',
'personaltools'     => 'ব্যক্তিগত সৰঞ্জাম',
'postcomment'       => 'নতুন অনুচ্ছেদ',
'articlepage'       => 'প্রবন্ধ',
'talk'              => 'বাৰ্তালাপ',
'views'             => 'দৰ্শ(ভিউ)',
'toolbox'           => 'সাজ-সৰঞ্জাম',
'userpage'          => 'ভোক্তাৰ(ইউজাৰ) পৃষ্ঠা',
'projectpage'       => 'প্রকল্প পৃষ্ঠা',
'imagepage'         => 'ফাইল পৃষ্ঠা চাওক',
'mediawikipage'     => 'বার্তা পৃষ্ঠা চাওক',
'templatepage'      => 'সাঁচ পৃষ্ঠা চাওক',
'viewhelppage'      => 'সহায় পৃষ্ঠা চাওক',
'categorypage'      => 'শ্রেণী পৃষ্ঠা চাওক',
'viewtalkpage'      => 'কথা-বতৰা চাওক',
'otherlanguages'    => 'আন ভাষাত',
'redirectedfrom'    => '($1 ৰ পৰা)',
'redirectpagesub'   => 'পূণঃনির্দেশিত পৃষ্ঠা',
'lastmodifiedat'    => 'এই পৃষ্ঠাটো শেষবাৰৰ কাৰণে $1 তাৰিখে $2 বজাত সলনি কৰা হৈছিল',
'viewcount'         => 'এই পৃষ্ঠাটো {{PLURAL:$1|এবাৰ|$1}} বাৰ চোৱা হৈছে',
'protectedpage'     => 'সুৰক্ষিত পৃষ্ঠা',
'jumpto'            => 'গম্যাৰ্থে',
'jumptonavigation'  => 'দিকদৰ্শন',
'jumptosearch'      => 'সন্ধানাৰ্থে',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}}ৰ বৃত্তান্ত',
'aboutpage'            => 'Project:ইতিবৃত্ত',
'copyright'            => 'এই লিখনী $1 ৰ অন্তর্গত উপলব্ধ।',
'copyrightpage'        => '{{ns:project}}:স্বত্ব',
'currentevents'        => 'সাম্প্রতিক ঘটনাৱলী',
'currentevents-url'    => 'Project:শেহতীয়া ঘটনাৱলী',
'disclaimers'          => 'ঘোষণা',
'disclaimerpage'       => 'Project:সাধাৰণ দায়লুপ্তি',
'edithelp'             => 'সম্পাদনাৰ বাবে সহায়',
'edithelppage'         => 'Help:সম্পাদনা',
'helppage'             => 'Help:সুচী',
'mainpage'             => 'বেটুপাত',
'mainpage-description' => 'বেটুপাত',
'policy-url'           => 'Project:নীতি',
'portal'               => 'সদন',
'portal-url'           => 'Project:সমজুৱা পৃষ্ঠা',
'privacy'              => 'গোপনীয়তা নীতি',
'privacypage'          => 'Project:গোপনীয়তাৰ নীতি',

'badaccess'        => 'অনুমতি ভুল',
'badaccess-group0' => 'আপুনি কৰিব বিছৰা কামতো কৰাৰ আধিকাৰ আপোনাৰ নাই।',
'badaccess-groups' => 'আপুনি অনুৰোধ কৰা কায্য কেৱল {{plural:$2|গোটৰ|গোটৰ}} সদস্যলৈ সীমিত: $1',

'versionrequired'     => 'মেডিয়াৱিকিৰ $1 সংকলন থাকিব লাগিব ।',
'versionrequiredtext' => 'এই পৃষ্ঠাটো ব্যৱহাৰ কৰিবলৈ মেডিয়াৱিকিৰ $1 সংকলন থাকিব লাগিব । [[Special:Version|সংকলন সুচী]] চাওক।',

'ok'                      => 'ওকে',
'retrievedfrom'           => '"$1" -ৰ পৰা সংকলিত',
'youhavenewmessages'      => 'আপোনাৰ কাৰণে $1 আছে। ($2)',
'newmessageslink'         => 'নতুন বার্তা',
'newmessagesdifflink'     => 'শেহতিয়া সাল-সলনি',
'youhavenewmessagesmulti' => '$1 ত আপোনাৰ কাৰণে নতুন বার্তা আছে',
'editsection'             => 'লিখক',
'editold'                 => 'সম্পাদনা',
'viewsourceold'           => 'অক্ষৰ-মূল দেখুওৱা হওক',
'editlink'                => 'সম্পাদনা',
'viewsourcelink'          => 'উৎস চাওঁক',
'editsectionhint'         => '$1 খণ্ডৰ সম্পাদনা',
'toc'                     => 'সূচী',
'showtoc'                 => 'দেখুৱাব লাগে',
'hidetoc'                 => 'দেখুৱাব নালাগে',
'thisisdeleted'           => '$1 চাওক বা সলনি কৰক?',
'viewdeleted'             => '$1 চাওক?',
'feedlinks'               => 'ফিড:',
'feed-unavailable'        => 'সিন্ডিকেশন ফিড মজুত নাই',
'site-rss-feed'           => '$1 আৰ এস এস ফিড',
'site-atom-feed'          => '$1 এটম ফিড',
'page-rss-feed'           => '"$1" আৰ-এচ-এচ ফীড',
'page-atom-feed'          => '"$1" এটম ফিড',
'red-link-title'          => '$1 (পাতটি নাই)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'পৃষ্ঠা',
'nstab-user'      => 'সদস্য পৃষ্ঠা',
'nstab-media'     => 'মেডিয়া পৃষ্ঠা',
'nstab-special'   => 'বিশেষ পৃষ্ঠা',
'nstab-project'   => 'প্রকল্প পৃষ্ঠা',
'nstab-image'     => 'চিত্র',
'nstab-mediawiki' => 'বার্তা',
'nstab-template'  => 'সাঁচ',
'nstab-help'      => 'সাহায্য পৃষ্ঠা',
'nstab-category'  => 'শ্রেণী',

# Main script and global functions
'nosuchaction'      => 'এনে কাৰ্য্য নাই',
'nosuchactiontext'  => "এই ইউআৰএল-এ নিৰ্ধাৰিত কৰা কাৰ্য্য অবৈধ।
আপুনি বোধহয়  ইউআৰএল ভুলজৈ লিখিছে বা এটা ভুল লিঙ্ক অনুকৰণ কৰিছে ।
হ'বও পাৰে যে {{SITENAME}}-ত ব্যবহাৰ হুৱা চফ্টৱেৰত ক্ৰুটি আছে ।",
'nosuchspecialpage' => 'এনেকুৱা কোনো বিশেষ পৃষ্ঠা নাই',
'nospecialpagetext' => '<strong>আপুনি অস্তিত্বত নথকা বিশেষ পৃষ্ঠা এটা বিচাৰিছে </strong>

   বিশেষ পৃষ্ঠাহমুহৰ তালিকা ইয়াত পাব [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'ভুল',
'databaseerror'        => 'তথ্যকোষৰ ভুল',
'laggedslavemode'      => 'সাবধান: ইয়াত সাম্প্রতিক সাল-সলনি নাথাকিব পাৰে',
'readonly'             => 'তথ্যকোষ বন্ধ কৰা আছে',
'enterlockreason'      => 'বন্ধ কৰাৰ কাৰণ দিয়ক, লগতে কেতিয়ামানে খোলা হব তাকো জনাব।',
'readonlytext'         => 'নতুন সম্পাদন আৰু আন সাল-সলনিৰ কাৰণে তথ্যকোষ বর্তমানে বন্ধ আছে, হয়তো নিয়মিয়া চোৱ-চিতা কৰিবলৈ, কিছু সময় পিছ্ত এয়া সধাৰণ অৱস্থালৈ আহিব।

যিজন প্রৱন্ধকে বন্ধ কৰিছে তেও কাৰণ দিছে: $1',
'missingarticle-rev'   => '(সংস্কৰণ#: $1)',
'missingarticle-diff'  => '(তফাৎ: $1, $2)',
'internalerror'        => 'ভিতৰুৱা গণ্ডোগোল',
'internalerror_info'   => 'ভিতৰুৱা গণ্ডোগোল: $1',
'filecopyerror'        => '"$1" ফাইলটো "$2" লৈ প্রতিলিপী কৰিব পৰা নগল।',
'filerenameerror'      => '"$1" ফাইলৰ নাম সলনি কৰি "$2" কৰিব পৰা নগল ।',
'filedeleteerror'      => '"$1" ফাইলতো বিলোপ কৰিব পৰা নগল।',
'directorycreateerror' => '"$1" ডাইৰেক্টৰি বনাব পৰা নগল।',
'filenotfound'         => '"$1" নামৰ ফাইলটো বিচাৰি পোৱা নগল।',
'fileexistserror'      => '"$1" ফাইলটোত লিখিব নোৱাৰি: ফাইলটো আগৰ পৰাই আছে',
'unexpected'           => 'অনাকাংক্ষিত মুল্য: "$1"="$2".',
'formerror'            => 'ভুল: ফর্ম খন জমা দিব পৰা নগল',
'badarticleerror'      => 'এই পৃষ্ঠাটোত এই কামটো কৰিব নোৱাৰি ।',
'cannotdelete'         => '"$1" পৃষ্ঠা বা ফাইল মচা সম্ভব নহয় ।
সম্ভৱ আনে আগেই মচী থৈছে ।',
'badtitle'             => 'অনভিপ্রেত শিৰোণামা',
'badtitletext'         => 'আপুনি বিচৰা পৃষ্ঠাটোৰ শিৰোণামা অযোগ্য, খালী বা ভুলকে জৰিত আন্তর্ভাষিক বা আন্তর্ৱিকি শিৰোণামা। ইয়াত এক বা ততোধিক বর্ণ থাকিব পাৰে যাক শিৰোণামাত ব্যৱহাৰ কৰিব নোৱাৰি।',
'perfcached'           => 'তলত দিয়া তথ্য খিনি আগতে জমা কৰি থোৱা (cached) আৰু সাম্প্রতিক নহব পাৰে।',
'perfcachedts'         => 'তলত দিয়া তথ্য খিনি আগতে জমা কৰি থোৱা (cached) আৰু শেষবাৰৰ কাৰণে $1 ত নৱীকৰণ কৰা হৈছিল।',
'querypage-no-updates' => 'এই পৃষ্ঠাটো নৱীকৰণ কৰা ৰোধ কৰা হৈছে। ইয়াৰ তথ্য এতিয়া সতেজ কৰিব নোৱাৰি।',
'wrong_wfQuery_params' => 'wfQuery() ৰ কাৰণে ভুল মাপদণ্ড দিয়া হৈছে <br />
কার্য্য: $1<br />পৃষ্ঠা: $2',
'viewsource'           => 'উৎস চাবলৈ',
'viewsourcefor'        => '$1 ৰ কাৰণে',
'actionthrottled'      => 'কাৰ্য্য লেহেম কৰা হৈছে',
'actionthrottledtext'  => 'স্পাম ৰোধ কৰিবলৈ এই ক্রিয়াতো কম সময়ৰ ভিতৰত বহু বেছি বাৰ কৰাতো ৰোধ কৰা হৈছে, আৰু আপুনি ইতিমধ্যে সেই সীমা অতিক্রম কৰিলে।
অনুগ্রহ কৰি কিছু সময় পাছত চেষ্টা কৰক।',
'protectedpagetext'    => 'এই পৃষ্ঠাটোৰ সম্পাদনা ৰোধ কৰিবলৈ সুৰক্ষিত কৰা হৈছে।',
'viewsourcetext'       => 'আপুনি এই পৃষ্ঠাটোৰ উত্‍স চাব আৰু নকল কৰিব পাৰে',
'sqlhidden'            => '(নিহিত SQL query)',
'namespaceprotected'   => "আপোনাৰ '''$1''' নামস্থানৰ পৃষ্ঠাহমুহ সম্পাদনা কৰাৰ অধিকাৰ নাই।",
'customcssjsprotected' => 'এই পৃষ্ঠা সম্পাদনা কৰাৰ আধিকাৰ আপোনাৰ নাই, কাৰণ ইয়াত আন সদস্যৰ ব্যক্তিগত চেটিংচ আছে।',
'ns-specialprotected'  => 'বিশেষ পৃষ্ঠা সম্পাদিত কৰিব নোৱাৰি।',
'titleprotected'       => "[[User:$1|$1]] সদস্যজনে এই শিৰোণামাৰ লিখনী লিখা ৰোধ কৰিছে ।
ইয়াৰ কাৰণ হৈছে ''$2'' ।",

# Virus scanner
'virus-scanfailed'     => 'স্কেন অসফল (কোড $1)',
'virus-unknownscanner' => 'অজ্ঞাত এন্টিভাইৰাচ:',

# Login and logout pages
'logouttext'                 => "'''আপুনি প্রস্থান কৰিলে ।'''

আপুনি বেনামী ভাবেও {{SITENAME}} ব্যৱহাৰ কৰিব পাৰে, অথবা আকৌ সেই একে বা বেলেগ নামেৰে [[Special:UserLogin|প্রৱেশ]] কৰিব পাৰে।
মন কৰিব যে যেতিয়ালৈকে আপোনাৰ ব্রাউজাৰৰ অস্থায়ী-স্মৃতি (cache memory) খালী নকৰে, তেতিয়ালৈকে কিছুমান পৃষ্ঠাত আপুনি প্রৱেশ কৰা বুলি দেখুৱাই থাকিব পাৰে।",
'welcomecreation'            => '== স্বাগতম, $1! ==
আপোনাৰ সদস্যভুক্তি হৈ গল ।
[[Special:Preferences|{{SITENAME}}  পছন্দসমূহ]]ত আপোনাৰ পচন্দমতে ব্যক্তিগতকৰণ কৰি লবলৈ নাপাহৰে যেন|',
'yourname'                   => 'সদস্যনাম:',
'yourpassword'               => 'আপোনাৰ গুপ্তশব্দ',
'yourpasswordagain'          => 'গুপ্তশব্দ আকৌ এবাৰ লিখক',
'remembermypassword'         => 'মোৰ প্রৱেশ এই কম্পিউটাৰত মনত ৰাখিব (for a maximum of $1 {{PLURAL:$1|day|days}})',
'yourdomainname'             => 'আপোনাৰ দমেইন:',
'login'                      => 'প্রৱেশ',
'nav-login-createaccount'    => 'প্রৱেশ/সদস্যভুক্তি',
'loginprompt'                => '{{SITENAME}}ত প্রৱেশ কৰিবলৈ আপুনি কুকী সক্রীয় কৰিব লাগিব',
'userlogin'                  => 'প্রৱেশ/সদস্যভুক্তি',
'userloginnocreate'          => 'প্রৱেশ',
'logout'                     => 'প্রস্থান',
'userlogout'                 => 'প্রস্থান',
'notloggedin'                => 'প্রৱেশ কৰা নাই',
'nologin'                    => 'আপুনি কি সদস্য নহয়? $1',
'nologinlink'                => 'নতুন সদস্যভুক্তি কৰক',
'createaccount'              => 'সভ্যভুক্ত হবলৈ',
'gotaccount'                 => "আপুনি সদস্য হয়নে? '''$1'''",
'gotaccountlink'             => 'প্রবেশ',
'createaccountmail'          => 'ই-মেইলেৰে',
'badretype'                  => 'আপুনি দিয়া গুপ্ত শব্দ দুটা মিলা নাই।',
'userexists'                 => 'আপুনি দিয়া সদস্যনাম আগৰে পৰাই ব্যৱহাৰ হৈ আছে।
অনুগ্রহ কৰি বেলেগ সদস্যনাম এটা বাচনী কৰক।',
'loginerror'                 => 'প্রৱেশ সমস্যা',
'createaccounterror'         => "একাউন্ট সৃষ্টি কৰা নহ'ল: $1",
'nocookiesnew'               => 'আপোনাৰ সদস্যভুক্তি হৈ গৈছে, কিন্তু আপুনি প্রৱেশ কৰা নাই।
{{SITENAME}} ত প্রৱেশ কৰিবলৈ কুকী সক্রিয় থাকিব লাগিব।
আপুনি কুকী নিস্ক্রিয় কৰি থৈছে।
অনুগ্রহ কৰি কুকী সক্রীয় কৰক, আৰু তাৰ পাছত আপোনাৰ সদস্যনামেৰে প্রৱেশ কৰক।',
'nocookieslogin'             => '{{SITENAME}} ত প্রৱেশ কৰিবলৈ কুকী সক্রিয় থাকিব লাগিব।
আপুনি কুকী নিস্ক্রিয় কৰি থৈছে।
অনুগ্রহ কৰি কুকী সক্রীয় কৰক, আৰু তাৰ পাছত চেষ্টা কৰক।',
'noname'                     => 'আপুনি বৈধ সদস্যনাম এটা দিয়া নাই।',
'loginsuccesstitle'          => "প্রবেশ অনুমোদিত হ'ল",
'loginsuccess'               => "''' আপুনি {{SITENAME}}ত \"\$1\" নামেৰে প্রবেশ কৰিলে '''",
'nosuchuser'                 => '"$1" নামৰ কোনো সদস্য নাই।
সদস্য নাম আকাৰ সংবেদনশীল।
আপোনাৰ বানানতো চাওক, বা  [[Special:UserLogin/signup|নতুন সদস্যভুক্তি কৰক]]।',
'nosuchusershort'            => '"<nowiki>$1</nowiki>" এই নামৰ কোনো সদস্য নাই ।
বানানতো আকৌ এবাৰ ভালদৰে চাওক ।',
'nouserspecified'            => 'অপুনি সদস্যনাম এটা দিবই লাগিব।',
'wrongpassword'              => 'আপুনি ভুল গুপ্তশব্দ দিছে। অনুগ্রহ কৰি আকৌ এবাৰ চেষ্টা কৰক।',
'wrongpasswordempty'         => 'দিয়া গুপ্তশব্দতো খালী; অনুগ্রহ কৰি আকৌ এবাৰ চেষ্টা কৰক। ।',
'passwordtooshort'           => "গুপ্তশব্দ কমেও {{PLURAL:$1|১ তা|$1 তা}} আখৰ হ'ব লাগিব ।",
'password-name-match'        => "আপুনাৰ গুপ্তশব্দ আৰু আপুনাৰ সদস্যনাম বেলেগ হ'ব লাগিব",
'mailmypassword'             => 'ই-মেইলত গুপ্তশব্দ পঠাওক',
'passwordremindertitle'      => '{{SITENAME}} ৰ কাৰণে নতুন অস্থায়ী গুপ্তশব্দ',
'passwordremindertext'       => 'কোনোবাই (হয়তো আপুনি, $1 আই-পি ঠিকনাৰ পৰা)
{{SITENAME}} ত ব্যৱহাৰ কৰিবলৈ ’নতুন গুপ্তশব্দ’ বিছাৰিছে ($4) ।
"$2" সদস্যজনৰ কাৰনে এতিয়া নতুন গুপ্তশব্দ হৈছে "$3" ।
আপুনি এতিয়া প্রবেশ কৰক আৰু গুপ্তশব্দতো সলনি কৰক।
আপুনাৰ অস্থায়ী গুপ্তশব্দ {{PLURAL:$5|১ দিনৰ|$5 দিনৰ}} ভিতৰত ৰদ কৰা হ\'ব ।

যদি আপুনি এই অনুৰোধ কৰা নাছিল অথবা যদি আপোনাৰ গুপ্তশব্দতো মনত আছে আৰু তাক সলাব নিবিছাৰে, তেনেহলে আপুনি এই বার্তাতো অবজ্ঞা কৰিব পাৰে আৰু আপোনাৰ আগৰ গুপ্তশব্দতোকে ব্যৱহাৰ কৰি থাকিব পাৰে।',
'noemail'                    => '"$1" সদস্যজনৰ কোনো ই-মেইল ঠিকনা সঞ্চিত কৰা নাই।',
'noemailcreate'              => 'আপুনি এটা সঠিক ইমেইল ঠিকানা দিব লাগে',
'passwordsent'               => '"$1" ৰ ই-মেইল ঠিকনাত নতুন গুপ্তশব্দ এটা পঠোৱা হৈছে। অনুগ্রহ কৰি সেয়া পোৱাৰ পাছত পুনৰ প্রবেশ কৰক।',
'blocked-mailpassword'       => 'আপোনাৰ IP ঠিকনাৰ পৰা সম্পাদনা কৰা বাৰণ কৰা হৈছে, এনে অৱস্থাত দুর্ব্যৱহাৰ ৰোধ কৰিবলৈ গুপ্তশব্দ পুনঃউদ্ধাৰ কৰা সুবিধাতো বাতিল কৰা হৈছে।',
'eauthentsent'               => 'সঞ্চিত ই-মেইল ঠিকনাত নিশ্বিতকৰণ ই-মেইল এখন পঠোৱা হৈছে।
আৰু অন্যান্য ই-মেইল পঠোৱাৰ আগতে, আপোনাৰ সদস্যতাৰ নিশ্বিত কৰিবলৈ সেই ই-মেইলত দিয়া নির্দেশনা আপুনি অনুসৰন কৰিব লাগিব।',
'throttled-mailpassword'     => 'যোৱা {{PLURAL:$1|ঘণ্টাত|$1 ঘণ্টাত}} গুপ্তশব্দ পুনৰুদ্ধাৰ সুচনা পঠিওৱা হৈছে ।
অবৈধ ব্যৱহাৰ ৰোধ কৰিবলৈ $1 ঘণ্টাত এবাৰহে গুপ্তশব্দ পুনৰুদ্ধাৰ সুচনা পঠিওৱা হয়।',
'mailerror'                  => 'ই-মেইল পঠোৱাত সমস্যা হৈছে: $1',
'acct_creation_throttle_hit' => 'যোৱা ২৪ ঘন্টাত আপুনাৰ আই-পি ঠিকনাৰ পৰা কেউজনে {{PLURAL:$1|১-তা একাউন্ট|$1-তা একাউন্ট}} সৃষ্টি কৰিলে, যোনতো সর্বোচ্চ অনুমোদনকৃত ।
এতেকে, এই আই-পি ঠিকনাৰ পৰা এই খন্তেকত একাউন্ট সৃষ্টি কৰিব নোৱাৰিব ।',
'emailauthenticated'         => 'আপোনাৰ ই-মেইল ঠিকনাটো $2 তাৰিখৰ $3 বজাত নিশ্চিত কৰা হৈছিল ।',
'emailnotauthenticated'      => 'আপোনাৰ ই-মেইল ঠিকনাতো এতিয়ালৈ প্রমনিত হোৱা নাই ।
আপুনি তলৰ বিষয়বোৰৰ কাৰণে মেইল পঠাব নোৱাৰে ।',
'noemailprefs'               => 'এই সুবিধাবোৰ ব্যৱহাৰ কৰিবলৈ এটা ই-মেইল ঠিকনা দিয়ক।',
'emailconfirmlink'           => 'আপোনাৰ ই-মেইল ঠিকনতো প্রমানিত কৰক',
'invalidemailaddress'        => 'এই ই-মেইল ঠিকনাতো গ্রহনযোগ্য নহয়, কাৰণ ই অবৈধ প্রকাৰৰ যেন লাগিছে।
অনুগ্রহ কৰি এটা বৈধ ই-মেইল ঠিকনা লিখক অথবা একো নিলিখিব।',
'accountcreated'             => 'সদস্যতা সৃষ্টি কৰা হল',
'accountcreatedtext'         => '$1 ৰ কাৰণে সদস্যভুক্তি কৰা হল।',
'createaccount-title'        => '{{SITENAME}} ৰ কাৰণে সদস্যভুক্তি কৰক।',
'createaccount-text'         => 'আপোনাৰ ই-মেইল ঠিকণাৰ কাৰণে {{SITENAME}} ($4) ত "$2" নামৰ কোনোবাই, "$3" গুপ্তশব্দ দি সদস্যভুক্তি কৰিছে। আনুগ্রহ কৰি আপুনি প্রৱেশ কৰক আৰু গুপ্তশব্দটো সলনি কৰক।

যদি এ্য়া ভুলতে হৈছে, তেনেহলে আপুনি এই বার্তাটো অবজ্ঞা কৰিব পাৰে ।',
'usernamehasherror'          => 'সদস্যনামত হেচ আখৰ থাকিব নোৱাৰে',
'loginlanguagelabel'         => 'ভাষা: $1',

# Password reset dialog
'resetpass'                 => 'গুপ্তশব্দ শলনি',
'resetpass_announce'        => 'আপুনি ই-মেইলত পোৱা অস্থায়ী গুপ্তশব্দৰে প্রৱেশ কৰিছে।
প্রৱেশ সম্পুর্ণ কৰিবলৈ, আপুনি এটা নতুন গুপ্তশব্দ দিব লাগিব:',
'resetpass_header'          => 'গুপ্তশব্দ শলনি কৰক',
'oldpassword'               => 'পূৰণি গুপ্তশব্দ:',
'newpassword'               => 'নতুন গুপ্তশব্দ:',
'retypenew'                 => 'নতুন গুপ্তশব্দ আকৌ টাইপ কৰক',
'resetpass_submit'          => 'গুপ্তশব্দ বনাওক আৰু প্রৱেশ কৰক',
'resetpass_success'         => 'আপোনাৰ গুপ্তশব্দ সফলতাৰে সলনি কৰা হৈছে, এতিয়া আপুনি প্রৱেশ কৰি আছে...',
'resetpass_forbidden'       => 'গুপ্তশব্দ সলনি কৰিব নোৱাৰি',
'resetpass-submit-loggedin' => 'গুপ্তশব্দ সলনি কৰক',
'resetpass-temp-password'   => 'অস্থায়ী গুপ্তশব্দ:',

# Edit page toolbar
'bold_sample'     => 'শকত পাঠ্য',
'bold_tip'        => 'গুৰুলেখ',
'italic_sample'   => 'তীৰ্যকলেখ',
'italic_tip'      => 'বেঁকা পাঠ্য',
'link_sample'     => 'শিৰোণামা সংযোগ',
'link_tip'        => 'ভিতৰুৱা সংযোগ',
'extlink_sample'  => 'http://www.example.com শীর্ষক সংযোগ',
'extlink_tip'     => 'বাহিৰৰ সংযোগ (http:// নিশ্বয় ব্যৱহাৰ কৰিব)',
'headline_sample' => 'শিৰোণামা পাঠ্য',
'headline_tip'    => 'দ্বিতীয় স্তৰৰ শিৰোণামা',
'math_sample'     => 'ইয়াত গণিতীয় সুত্র সুমুৱাওক',
'math_tip'        => 'গণিতীয় সুত্র (LaTeX)',
'nowiki_sample'   => 'নসজোৱা পাঠ্য ইয়াত অন্তর্ভুক্ত কৰক',
'nowiki_tip'      => 'ৱিকি-সম্মত সাজ-সজ্জা অৱজ্ঞা কৰক',
'image_tip'       => 'এম্বেডেড ফাইল',
'media_tip'       => 'ফাইল সংযোগ',
'sig_tip'         => 'সময়ৰ সৈতে আপোনাৰ স্বাক্ষৰ',
'hr_tip'          => 'পথালী ৰেখা (কমকৈ ব্যৱহাৰ কৰিব)',

# Edit pages
'summary'                          => 'সাৰাংশ:',
'subject'                          => 'বিষয় / শীর্ষক:',
'minoredit'                        => 'এইটো নগন্য সম্পদনা',
'watchthis'                        => 'এই পৃষ্ঠাটো অনুসৰণ-সূচীভুক্ত কৰক',
'savearticle'                      => 'পৃষ্ঠা সংৰাক্ষিত কৰক',
'preview'                          => 'খচৰা',
'showpreview'                      => 'খচৰা',
'showlivepreview'                  => 'জীৱন্ত খছৰা',
'showdiff'                         => 'সালসলনিবোৰ দেখুৱাওক',
'anoneditwarning'                  => "'''সাৱধান:''' আপুনি প্রৱেশ কৰা নাই, এই পৃষ্ঠাৰ ইতিসাহত আপোনাৰ আই পি ঠিকনা সংৰক্ষিত কৰা হব।",
'missingsummary'                   => "'''স্মাৰক:''' আপুনি সম্পাদনা সাৰাংশ দিয়া নাই।
আপুনি আৰু এবাৰ সংৰক্ষণৰ বাবে ক্লীক কৰিলে সাৰাংশৰ অবিহনে সংৰক্ষিত হব।",
'missingcommenttext'               => 'অনুগ্রহ কৰি তলত মন্তব্য এটা দিয়্ক।',
'missingcommentheader'             => "'''স্মাৰক:''' আপুনি এই মন্তব্যটোত শিৰোণামা দিয়া নাই।
যদি আকৌ এবাৰ সংৰক্ষিত কৰে, তেনেহলে শিৰোণামা অবিহনে সংৰক্ষিত হব।",
'summary-preview'                  => 'সাৰাংশৰ খচৰা:',
'subject-preview'                  => 'বিষয়/শিৰোণামাৰ খচৰা:',
'blockedtitle'                     => 'সদস্যজনক অবৰোধ কৰা হৈছে',
'blockedtext'                      => "'''আপোনাৰ সদস্যনাম অথবা আই-পি ঠিকণা অবৰোধ কৰা হৈছে ।'''

$1ৰ দ্বাৰ এই অবৰোধ কৰা হৈছে ।
ইয়াৰ বাবে দিয়া কাৰণ হৈছে ''$2'' ।

* অবৰোধ আৰম্ভনী: $8
* অবৰোধ সমাপ্তি: $6
* অবৰোধ কৰা হৈছে: $7

আপুনি এই অবৰোধৰ বিষয়ে আলোচনা কৰিবলৈ $1 বা [[{{MediaWiki:Grouppage-sysop}}|প্রবন্ধকৰ]] লগত সম্পর্ক স্থাপন কৰিব পাৰে ।
আপুনি যেতিয়ালৈ [[Special:Preferences|সদস্য পছন্দ]] পৃষ্ঠাত আপোনাৰ ই-মেইল ঠিকনা নিদিয়ে তেতিয়ালৈ ’সদস্যক ই-মেইল পঠাওক’ সুবিধাতো ব্যৱহাৰ কৰিব নোৱাৰিব, আৰু আপোনাক এয়া কৰিবলৈ ৰোধ কৰা হোৱা নাই ।
আপোনাৰ এতিয়াৰ আই-পি ঠিকনা হল $3, আৰু আপোনাৰ অবৰোধ ক্রমিক হৈছে #$5 ।
এই বিষয়ে হোৱা আলোচনাত ইয়াৰ সবিশেষ সদৰী কৰে যেন।",
'autoblockedtext'                  => "আপোনাৰ আই-পি ঠিকনা নিজে নিজে অবৰোধিত হৈ গৈছে, কাৰণ ইয়াক কোনোবাই ব্যৱহাৰ কৰি থাকোতে $1 ৰ দ্বাৰা অবৰোধ কৰা হৈছে।
ইয়াৰ বাবে দিয়া কাৰণ হৈছে:

:''$2''

* অবৰোধ আৰম্ভনী:  $8
* অবৰোধ সমাপ্তি: $6
* অৱৰোধ কৰা হৈছে: $7

আপুনি এই অবৰোধৰ বিষয়ে আলোচনা কৰিবলৈ $1 বা [[{{MediaWiki:Grouppage-sysop}}|প্রবন্ধক]]ৰ লগত সম্পর্ক স্থাপন কৰিব পাৰে ।

আপুনি যেতিয়ালৈ [[Special:Preferences|সদস্য পছন্দ]] পৃষ্ঠাত আপোনাৰ ই-মেইল ঠিকনা নিদিয়ে তেতিয়ালৈ ’সদস্যক ই-মেইল পঠাওক’ সুবিধাতো ব্যৱহাৰ কৰিব নোৱাৰে। আপোনাক এয়া কৰিবলৈ ৰোধ কৰা হোৱা নাই ।
অপোনাৰ এতিয়াৰ IP ঠিকনা হৈছে $3, অৰু আপোনাৰ অবৰোধ ক্রমিক হৈছে $5 ।
এই বিষয়ে হোৱা আলোচনাত ইয়াক ব্যৱহাৰ কৰিবলৈ অনুৰোধ কৰা হল।",
'blockednoreason'                  => 'কাৰণ দিয়া নাই',
'blockedoriginalsource'            => "'''$1''' ৰ উত্‍স তলত দিয়া হৈছে।",
'blockededitsource'                => "'''$1''' ৰ '''আপুনি কৰা সাল-সলনি''' ৰ পাঠ্য তলত দিয়া হৈছে:",
'whitelistedittitle'               => 'সম্পাদনা কৰিবলৈ প্রবেশ কৰিব লাগিব।',
'whitelistedittext'                => 'সম্পাদনা কৰিবলৈ $1 কৰক ।',
'confirmedittext'                  => 'সম্পাদনা কৰাৰ আগতে আপুনি আপোনাৰ ই-মেইল ঠিকনাটো প্রমানিত কৰিব লাগিব।
অনুগ্রহ কৰি [[Special:Preferences|মোৰ পচন্দ]] ত গৈ আপোনাৰ ই-মেইল ঠিকনা দিয়ক আৰু তাক প্রমানিত কৰক।',
'nosuchsectiontitle'               => 'এনেকুৱা কোনো বিভাগ নাই',
'nosuchsectiontext'                => 'অপুনি এনে এটা বিভাগ সম্পাদিত কৰিব বিচাৰিছে যাৰ কোনো অস্তিত্ব নাই।',
'loginreqtitle'                    => 'প্রবেশ আৱশ্যক',
'loginreqlink'                     => 'প্রবেশ',
'loginreqpagetext'                 => 'অন্যান্য পৃষ্ঠা চাবলৈ আপুনি $1 কৰিব লাগিব।',
'accmailtitle'                     => 'গুপ্তশব্দ পঠোৱা হৈছে।',
'accmailtext'                      => '"$1"ৰ পাছৱৰ্ড $2 লৈ পঠোৱা হ\'ল|',
'newarticle'                       => '(নতুন)',
'newarticletext'                   => 'আপুনি বিচৰা প্রবন্ধটো বিচাৰি পোৱা নগল।

ইচ্ছা কৰিলে আপুনিয়েই এই প্রবন্ধটো লিখা আৰম্ভ কৰিব পাৰে। [[{{MediaWiki:Helppage}}|ইয়াত]] সহায় পাব।

আপুনি যদি ইয়ালৈ ভুলতে আহিছে, তেনেহলে আপোনাৰ ব্রাওজাৰত (BACK) বুতামত টিপা মাৰক।',
'noarticletext'                    => 'এই পৃষ্ঠাত বর্তমান কোনো পাঠ্য নাই ।
আপুনি আন পৃষ্ঠাত [[Special:Search/{{PAGENAME}}| এই শিৰোণামা অনুসন্ধান কৰিব পাৰে]],
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} সম্পৰ্কিয় অভিলেখ অনুসন্ধান কৰিব পাৰে],
বা [{{fullurl:{{FULLPAGENAME}}|action=edit}} এই পৃষ্ঠা সম্পাদনা কৰিব পাৰে] ।',
'note'                             => "'''টোকা:'''",
'previewnote'                      => "'''মনত ৰাখিব যে এয়া কেৱল খচৰা হে, সাল-সলনিবোৰ এতিয়াও সংৰক্ষিত কৰা হোৱা নাই!'''",
'editing'                          => '$1 সম্পাদনা',
'editingsection'                   => '$1 (বিভাগ) সম্পদনা কৰি থকা হৈছে',
'editingcomment'                   => '$1 (নতুন বিভাগ) সম্পদনা কৰি থকা হৈছে',
'editconflict'                     => 'সম্পাদনা দ্বন্দ্ব: $1',
'yourtext'                         => 'আপুনাৰ লিখা পাঠ',
'storedversion'                    => 'যমা সংস্কৰণ',
'editingold'                       => "'''সাৱধান: আপুনি এই পৃষ্ঠাৰ এটি পুৰণি সংস্কৰণ সম্পাদনা কৰি আছে ।
যদি আপুনি আপুনাৰ সম্পাদনাসমূহ যমা কৰে, সেই পৰবৰ্তি সংস্কৰণসমূহ হেৰাই যাব ‌‌।'''",
'yourdiff'                         => 'তফাৎ',
'copyrightwarning'                 => "অনুগ্ৰহ কৰি মন কৰক যে {{SITENAME}}লৈ কৰা সকলো অৱদান $2 ৰ চর্তাৱলীৰ মতে প্রদান কৰা বুলি ধৰি লোৱা হব (আৰু অধিক জানিবলৈ $1 চাঁওক)। যদি আপুনি আপোনাৰ লিখনি নিৰ্দয়ভাৱে সম্পাদনা কৰা আৰু ইচ্ছামতে পুনৰ্বিতৰণ কৰা ভাল নাপায়, তেনেহ'লে নিজৰ লিখনি ইয়াত নিদিব।
<br />

ইয়াত আপোনাৰ লিখনি দিয়াৰ লগে লগে আপুনি আপোনা-আপুনি প্ৰতিশ্ৰুতি দিছে যে এই লিখনিটো আপোনাৰ মৌলিক লিখনি, বা কোনো স্বত্বাধিকাৰ নথকা বা কোনো ৰাজহুৱা ৱেবছাইট বা তেনে কোনো মুকলি উৎসৰ পৰা আহৰণ কৰা।
'''স্বত্বাধিকাৰযুক্ত কোনো সমল অনুমতি অবিহনে দাখিল নকৰে যেন!'''",
'copyrightwarning2'                => "অনুগ্ৰহ কৰি মন কৰক যে {{SITENAME}}লৈ কৰা সকলো অৱদান আন সদস্যই সম্পাদনা কৰিব, সলনি কৰিব অথবা মচি পেলাব পাৰে ।
আপুনি যদি আপোনাৰ লিখনি নিৰ্দয়ভাৱে সম্পাদনা কৰা ভাল নাপায়, তেনেহলে নিজৰ লিখনি ইয়াত নিদিব ।<br />
ইয়াত আপোনাৰ লিখনি দিয়াৰ লগে লগে আপুনি আপোনা-আপুনি প্ৰতিশ্ৰুতি দিছে যে এই লিখনিটো আপোনাৰ মৌলিক লিখনি, বা কোনো স্বত্বাধিকাৰ নথকা বা কোনো ৰাজহুৱা ৱেবছাইট বা তেনে কোনো মুকলি উৎসৰ পৰা আহৰণ কৰা| (অধিক জানিবলৈ $1 চাঁওক)

'''স্বত্বাধিকাৰযুক্ত কোনো সমল অনুমতি অবিহনে দাখিল নকৰে যেন!'''",
'longpagewarning'                  => "'''সাবধান: এই পৃষ্ঠাটো $1 কিলোবাইট আকাৰৰ; কিছুমান ব্রাউজাৰে 32 kb বা তাতকৈ বেছি আকাৰৰ পৃষ্ঠা দেখুৱাবলৈ বা সম্পাদনা কৰিবলৈ অসুবিধা পাব পাৰে ।
অনুগ্রহ কৰি এই পৃষ্ঠাটোক সৰু সৰু খণ্ডত বিভক্ত কৰাৰ কথা বিবেচনা কৰক ।'''",
'longpageerror'                    => "'''ভুল: আপুনি দিয়া লিখনী $1 কিলো-বাইট আকাৰৰ, যি $2 কিলো-বাইট সীমাটকৈ বেছি।
ইয়াক সঞ্চিত কৰিব পৰা নাযাব।'''",
'protectedpagewarning'             => "'''সকীয়নি: এই পৃষ্ঠা বন্ধ ৰখা হৈছে; কেৱল এডমিনিষ্ট্ৰেটৰ মৰ্যদাৰ সদস্যই হে সম্পাদনা কৰিব পাৰিব ।'''",
'semiprotectedpagewarning'         => "'''নোট: এই পৃষ্ঠা বন্ধ ৰখা হৈছে; কেৱল পঞ্জীভূত সদস্যই হে সম্পাদনা কৰিব পাৰিব ।'''",
'templatesused'                    => 'এই পৃষ্ঠাত ব্যৱহৃত {{PLURAL:$1|ঠাঁচ॥ঠাঁচ সমূহ}}:',
'templatesusedpreview'             => 'এই খচৰাত ব্যৱহৃত {{PLURAL:$1|ঠাঁচ|ঠাঁচ সমূহ}}:',
'template-protected'               => '(সুৰক্ষিত)',
'template-semiprotected'           => '(অর্ধ-সুৰক্ষিত)',
'hiddencategories'                 => 'এই পৃষ্ঠা {{PLURAL:$1|১-টা নিহিত বিষয়শ্রেণীৰ|$1-টা নিহিত বিষয়শ্রেণীৰ}} সদস্য:',
'nocreatetitle'                    => 'পৃষ্ঠা সৃষ্টি সিমিত',
'nocreatetext'                     => '{{SITENAME}} ত নতুন লিখনী লিখা ৰদ কৰা হৈছে।
আপুনি ঘুৰি গৈ অস্তিত্বত থকা পৃষ্ঠা এটা সম্পাদনা কৰিব পাৰে, বা [[Special:UserLogin| নতুন সদস্যভর্তি হওক/ প্রবেশ কৰক]] ।',
'nocreate-loggedin'                => 'নতুন পৃষ্ঠা সৃষ্টি কৰিবলৈ আপুনাৰ অনুমতি নাই ।',
'sectioneditnotsupported-title'    => 'অনুচ্ছেদ সম্পাদনাৰ সমর্থন নাই',
'sectioneditnotsupported-text'     => 'এই পৃষ্ঠাত অনুচ্ছেদ সম্পাদনাৰ সমর্থন নাই',
'permissionserrors'                => 'অনুমতি ভুলসমূহ',
'permissionserrorstext-withaction' => "আপুনাৰ $2 কৰিবলৈ অনুমতি নাই, যাৰ {{PLURAL:$1|কাৰণ|কাৰণসমূহ}} হ'ল:",
'recreate-moveddeleted-warn'       => "'''সাৱধান: আগতে বিলোপিত কৰা পৃষ্ঠা এটা আপুনি পূণঃনির্মান কৰি আছে। '''

এই পৄষ্ঠাটো সম্পাদনা কৰা উচিত হব নে নহয় আপুনি বিবেচনা কৰি চাওক।
এই পৃষ্ঠাটো বিলোপ আৰু স্থানান্তৰ কৰাৰ অভিলেখ আপোনাৰ সুবিধার্থে ইয়াত দিয়া হৈছে।",
'log-fulllog'                      => 'সম্পূৰ্ণ অভিলেখ চাওঁক',
'edit-conflict'                    => 'সম্পাদনা দ্বন্দ ।',
'edit-no-change'                   => 'আপুননাৰ সম্পাদনা আওকাণ কৰা হৈছে, কাৰণ লেখাত কোনো তফাৎ নাই',
'edit-already-exists'              => "নতুন পৃষ্ঠা সৃষ্টি কৰা নহ'ল ।
পৃষ্ঠাখন ইতিমধ্যেই আছেই ।",

# Account creation failure
'cantcreateaccounttitle' => "একাউন্ট সৃষ্টি কৰা নহ'ব",

# History pages
'viewpagelogs'           => 'এই পৃষ্ঠাৰ অভিলেখ চাঁওক ।',
'nohistory'              => 'এই পৃষ্ঠাৰ কোন সম্পাদনাৰ ইতিহাস নাই।',
'currentrev'             => 'শেহতীয়া ভাষ্য',
'currentrev-asof'        => '$1 অনুযায়ী বর্তমান সংস্কৰণ',
'revisionasof'           => '$1 তম ভাষ্য',
'revision-info'          => '$1-লৈ $2-এ কৰা সংশোধন',
'previousrevision'       => '← আগৰ সংশোধন',
'nextrevision'           => 'সদ্যসংশোধিত',
'currentrevisionlink'    => 'শেহতীয়া ভাষ্য',
'cur'                    => 'বর্তমান',
'next'                   => 'পৰবর্তী',
'last'                   => 'পুর্ববর্তি',
'page_first'             => 'প্রথম',
'page_last'              => 'অন্তিম',
'histlegend'             => 'পার্থক্য বাচনী: পার্থক্য চাবলৈ সংকলনবোৰৰ সম্মুখত থকা ৰেডিও বুটামবোৰ বাচনী কৰি এণ্টাৰ টিপক অথবা একেবাৰে তলত দিয়া বুটামতো ক্লীক কৰক <br />
লিজেণ্ড: (বর্তমান) = বর্তমানৰ সংকলনৰ লগত পার্থক্য,
(অন্তিম) = আগৰ সংকলনৰ লগত পার্থক্য, M = অগুৰুত্বপুর্ণ সম্পাদনা।',
'history-fieldset-title' => 'ইতিহাসত অনুসন্ধান কৰক',
'history-show-deleted'   => 'মাথোঁ মচি পেলোৱা',
'histfirst'              => 'আটাইতকৈ পূৰণি',
'histlast'               => 'শেহতীয়া',
'historysize'            => '({{PLURAL:$1|১ বাইট|$1 বাইট}})',
'historyempty'           => '(খালী)',

# Revision feed
'history-feed-title'          => 'সংকলন ইতিহাস',
'history-feed-description'    => 'ৱিকিত উপলব্ধ এই পৃষ্ঠাৰ সংকলন ইতিহাস',
'history-feed-item-nocomment' => '$1-য়ে $2',
'history-feed-empty'          => 'এই পৃষ্ঠা বা লিখনীটো নাই।
হয়তো ইয়াক বিলোপিত কৰা হৈছে অথবা ইয়াৰ নাম সলনী কৰা হৈছে।
[[Special:Search|সন্ধান]] ব্যৱহাৰ কৰি চাওক।',

# Revision deletion
'rev-deleted-comment'       => '(মন্তব্য আতৰোৱা হৈছে)',
'rev-deleted-user'          => '(সদস্যনাম আতৰোৱা হৈছে)',
'rev-delundel'              => 'দেখোৱা হওক / লুকুওৱা হওক',
'rev-showdeleted'           => 'দেখোৱাওক',
'revisiondelete'            => 'সংকলন বিলোপন কৰক / পুণর্স্থাপিত কৰক',
'revdelete-hide-text'       => 'সংশোধিত পাঠ আঁতৰাওক',
'revdelete-hide-image'      => 'ফাইলৰ বিষয়বস্তু আঁতৰাওক',
'revdelete-hide-name'       => 'কাৰ্য্য আৰু লক্ষ্য আতৰাই থওঁক',
'revdelete-hide-comment'    => 'সম্পাদনা মন্তব্য আতৰাই থওঁক',
'revdelete-hide-user'       => 'সম্পাদকৰ সদস্যনাম/আই-পি টিকনা আতৰাই থওঁক',
'revdelete-radio-set'       => 'অঁ',
'revdelete-radio-unset'     => 'না',
'revdel-restore'            => 'দৃষ্টিপাত সালসলনি কৰক',
'pagehist'                  => 'পৃষ্ঠা ইতিহাস',
'deletedhist'               => 'মচি পেলোৱা ইতিহাস',
'revdelete-content'         => 'বিষয়বস্তু',
'revdelete-summary'         => 'সম্পাদনাৰ সাৰমৰ্ম',
'revdelete-uname'           => 'সদস্যনাম',
'revdelete-otherreason'     => 'অন্য/অতিৰিক্ত কাৰণ:',
'revdelete-reasonotherlist' => 'অন্য কাৰণ',

# History merging
'mergehistory-go'     => 'একত্রীকৰণযোগ্য সম্পাদনাসমূহ দেখোৱাওঁক',
'mergehistory-reason' => 'কাৰণ:',

# Merge log
'mergelog'    => 'অভিলেখ একত্ৰিকৰণ',
'revertmerge' => 'একত্ৰিকৰণ পন্ড',

# Diffs
'history-title'           => '"$1" ৰ সাল-সলনিৰ ইতিহাস',
'difference'              => 'বিভিন্ন সংস্কৰণৰ প্রভেদ',
'lineno'                  => 'পংক্তি $1:',
'compareselectedversions' => 'নির্বাচিত কৰা সংকলন সমূহৰ মাজত পার্থক্য চাঁওক ।',
'editundo'                => 'পূৰ্ববত কৰক',

# Search results
'searchresults'                  => 'অনুসন্ধানৰ ফলাফল',
'searchresults-title'            => '"$1" অনুসন্ধানৰ ফলাফল',
'searchresulttext'               => '{{SITENAME}}ৰ বিষয়ে আৰু জানিবলৈ [[{{MediaWiki:Helppage}}|{{int:help}}]] চাওঁক ।',
'searchsubtitle'                 => 'আপুনি অনুসন্ধান কৰিছে \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|"$1" দি আৰম্ভ হোৱা পৃষ্ঠাসমূহ]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|"$1" লগত সংযুক্ত পৃষ্ঠাসমূহ]])',
'searchsubtitleinvalid'          => "আপুনাৰ অনুসন্ধান হ'ল '''$1'''",
'toomanymatches'                 => 'বহুত বেছি মিল পোৱা গৈছে, সন্ধাণ-শব্দ সলনি কৰিবলৈ অনুৰোধ কৰা হল',
'titlematches'                   => 'পৃষ্ঠাৰ শিৰোণামা মিলিছে',
'notitlematches'                 => 'এটাও পৃষ্ঠাৰ শিৰোণামা মিলা নাই',
'textmatches'                    => 'লিখনীৰ পাঠ্য মিলিছে',
'notextmatches'                  => 'এটাও লিখনীৰ পাঠ্য মিলা নাই',
'prevn'                          => 'পুর্ববর্তি {{PLURAL:$1|$1}}',
'nextn'                          => 'পৰৱর্তি {{PLURAL:$1|$1}}',
'viewprevnext'                   => 'চাওক ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-new'                 => "'''এই ৱিকিত \"[[:\$1]]\" পৃষ্ঠাখন সৃষ্টি কৰক!'''",
'searchhelp-url'                 => 'Help:সুচী',
'searchprofile-articles'         => 'সূচিপত্ৰসমূহ',
'searchprofile-images'           => 'মাল্টিমিডিয়া',
'searchprofile-everything'       => 'সকলো',
'searchprofile-articles-tooltip' => '$1-ট অনুসন্ধান কৰক',
'searchprofile-project-tooltip'  => '$1-ত অনুসন্ধান',
'search-result-size'             => '$1 ({{PLURAL:$2|1 শব্দ|$2 শব্দসমূহ}})',
'search-redirect'                => '(পুনৰ্নিদেশনা $1)',
'search-section'                 => '(অনুচ্ছেদ $1)',
'search-suggest'                 => 'আপুনি বুজাব খোজিছে নেকি: $1',
'search-interwiki-caption'       => 'সহপ্ৰকল্পসমূহ',
'search-interwiki-default'       => '$1 ফলাফলসমূহ:',
'search-interwiki-more'          => '(আৰু)',
'search-mwsuggest-enabled'       => 'উপদেশ সহ',
'search-mwsuggest-disabled'      => 'উপদেশ নাই',
'search-relatedarticle'          => 'সম্পৰ্কিত',
'searchrelated'                  => 'সম্পৰ্কিত',
'searchall'                      => 'সকলো',
'showingresults'                 => "তলত #'''$2'''ৰ পৰা {{PLURAL:$1|'''1''' ফলাফল|'''$1''' ফলাফল}} দেখুওৱা হৈছে।",
'search-nonefound'               => 'এই অনুসন্ধানৰ কোনো ফলাফল নাই ।',
'powersearch'                    => 'অতিসন্ধান',
'powersearch-legend'             => 'শক্তিশালী সন্ধান',
'powersearch-ns'                 => 'নামস্থানবোৰত সন্ধান:',
'powersearch-redir'              => 'পূণঃনির্দেশনা বোৰৰ তালিকা',
'powersearch-field'              => 'ৰ কাৰণে সন্ধান কৰক',
'powersearch-togglelabel'        => 'চেক:',
'powersearch-toggleall'          => 'সকলো',
'search-external'                => 'বাহ্যিক সন্ধান',
'searchdisabled'                 => '{{SITENAME}} ত অনুসন্ধান কৰা সাময়িক ভাবে নিষ্ক্রিয় কৰা হৈছে।
তেতিয়ালৈকে গুগলত অনুসন্ধান কৰক।
মনত ৰাখিব যে তেঁওলোকৰ {{SITENAME}}ৰ ইণ্ডেক্স পুৰণি হব পাৰে।',

# Quickbar
'qbsettings'               => 'শীঘ্রদণ্ডিকা',
'qbsettings-none'          => 'একেবাৰে নহয়',
'qbsettings-fixedleft'     => 'বাঁওফাল স্থিৰ',
'qbsettings-fixedright'    => 'সোঁফাল স্থিৰ',
'qbsettings-floatingleft'  => 'বাঁওফাল অস্থিৰ',
'qbsettings-floatingright' => 'সোঁফাল অস্থিৰ',

# Preferences page
'preferences'                 => 'ৰুচি',
'mypreferences'               => 'মোৰ পচন্দ',
'prefs-edits'                 => 'সম্পাদনা সমুহৰ সংখ্যা:',
'prefsnologin'                => 'প্রৱেশ কৰা নাই',
'prefsnologintext'            => 'আপোনাৰ পচন্দ সলনী কৰিবলৈ হলে <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} প্ৰৱেশ]</span> কৰাতো আৱশ্যক।',
'changepassword'              => 'গুপ্তশব্দ সলনী কৰক',
'prefs-skin'                  => 'আৱৰন',
'skin-preview'                => 'খচৰা',
'prefs-math'                  => 'গণিত',
'datedefault'                 => 'বিশেষ পচন্দ নাই',
'prefs-datetime'              => 'তাৰিখ আৰু সময়',
'prefs-personal'              => 'সদস্যৰ বিৱৰণ',
'prefs-rc'                    => 'শেহতীয়া সাল-সলনী',
'prefs-watchlist'             => 'লক্ষ্য তালিকা',
'prefs-watchlist-days'        => 'লক্ষ্য তালিকাত দেখুৱাব লগা দিন:',
'prefs-watchlist-days-max'    => 'সৰ্বোচ্চ ৭ দিন',
'prefs-watchlist-edits'       => 'বর্ধিত লক্ষ্যসুচীত দেখুৱাব লগা সর্বোচ্চ সাল-সলনী:',
'prefs-watchlist-edits-max'   => 'সৰ্বোচ্চ নম্বৰ: ১০০০',
'prefs-watchlist-token'       => 'লক্ষ্যতালিকা টোকেন:',
'prefs-misc'                  => 'অন্যান্য',
'prefs-resetpass'             => 'গুপ্তশব্দ শলনি কৰক',
'saveprefs'                   => 'সঞ্চিত কৰক',
'resetprefs'                  => 'অসঞ্চিত সাল-সলনী বাতিল কৰক',
'prefs-editing'               => 'সম্পাদন',
'rows'                        => 'পথালী শাৰী:',
'columns'                     => 'ঠিয় শাৰী:',
'searchresultshead'           => 'অনুসন্ধান',
'resultsperpage'              => 'প্রতি পৃষ্ঠা দর্শন:',
'contextlines'                => 'প্রতি শাৰী দর্শন:',
'contextchars'                => 'প্রতি শাৰীত সন্দর্ভ:',
'stub-threshold'              => '<a href="#" class="stub">আধাৰ সংযোগ</a> ৰ সর্বোচ্চ আকাৰ (বাইটত):',
'recentchangesdays'           => 'শেহতীয়া সাল-সলনীত দেখুৱাব লগা দিন:',
'recentchangesdays-max'       => 'সৰ্বোচ্চ $1 {{PLURAL:$1|দিন|দিন}}',
'recentchangescount'          => 'শেহতীয়া সাল-সলনী, ইতিহাস আৰু লগ পৃষ্ঠাত দেখুৱাব লগা সম্পাদনাৰ সংখ্যা:',
'savedprefs'                  => 'আপোনাৰ পচন্দসমুহ সংৰক্ষিত কৰা হল।',
'timezonelegend'              => 'সময় স্থান',
'localtime'                   => 'স্থানীয় সময়:',
'timezoneuseoffset'           => 'অন্য (অফচেট ধাৰ্য কৰক)',
'timezoneoffset'              => 'অফচেট¹:',
'servertime'                  => 'চার্ভাৰৰ সময়:',
'guesstimezone'               => 'ব্রাউজাৰৰ পৰা ভৰাওক',
'timezoneregion-africa'       => 'আফ্ৰিকা',
'timezoneregion-america'      => 'আমেৰিকা',
'timezoneregion-antarctica'   => 'এন্টাৰ্টিকা',
'timezoneregion-arctic'       => 'আৰ্কটিক',
'timezoneregion-asia'         => 'এচিয়া',
'timezoneregion-atlantic'     => 'আটলান্টিক মহাসাগৰ',
'timezoneregion-australia'    => 'অস্ট্ৰেলিয়া',
'timezoneregion-europe'       => 'ইউৰোপ',
'timezoneregion-indian'       => 'ভাৰত মহাসাগৰ',
'timezoneregion-pacific'      => 'প্ৰশান্ত মহাসাগৰ',
'allowemail'                  => 'অন্য সদস্যৰ পৰা ই-মেইল সমর্থ কৰক',
'prefs-searchoptions'         => 'সন্ধান বিকল্পসমুহ',
'prefs-namespaces'            => 'নামস্থান',
'defaultns'                   => 'অন্যথা এই নামস্থান সমূহত অনুসন্ধান কৰিব:',
'default'                     => 'অবিচল',
'prefs-files'                 => 'ফাইলসমুহ',
'prefs-emailconfirm-label'    => 'ইমেইল নিশ্চিতকৰণ:',
'youremail'                   => 'আপোনাৰ ই-মেইল *',
'username'                    => 'সদস্যনাম:',
'uid'                         => 'সদস্য চিহ্ন:',
'prefs-memberingroups'        => 'এই {{PLURAL:$1|গোটৰ|গোটবোৰৰ}} সদস্য:',
'yourrealname'                => 'আপোনাৰ আচল নাম*',
'yourlanguage'                => 'ভাষা:',
'yournick'                    => 'আপোনাৰ স্বাক্ষ্যৰ:',
'badsig'                      => 'অনুপোযোগী স্বাক্ষ্যৰ, HTML টেগ পৰীক্ষা কৰি লওক।',
'badsiglength'                => 'আপুনাৰ স্বাক্ষৰ অত্যাধিক দীঘলিয়া ।
আপুনাৰ স্বাক্ষৰ {{PLURAL:$1| বা| বা}} তাতকৈ কম আখৰৰ হব লাগে ।',
'yourgender'                  => 'লিঙ্গ:',
'gender-unknown'              => 'অনিধাৰ্য্য',
'gender-male'                 => 'পুৰুষ',
'gender-female'               => 'মহিলা',
'email'                       => 'ই-মেইল',
'prefs-help-realname'         => 'আপোনাৰ আচল নাম দিয়াতো জৰুৰি নহয়, কিন্তু দিলে আপোনাৰ কামবোৰ আপোনাৰ নামত দেখুওৱা হব।',
'prefs-help-email'            => 'ই-মেইল ঠিকন দিয়া বৈকল্পিক, কিন্তু দিলে আন সদস্যই আপোনাৰ চিনাকি নোপোৱাকৈয়ে আপোনাৰ লগত সম্পর্ক স্থাপন কৰিব পাৰিব।',
'prefs-help-email-required'   => 'ই-মেইল ঠিকনা দিবই লাগিব',
'prefs-info'                  => 'সাধাৰণ তথ্য',
'prefs-i18n'                  => 'আন্তঃৰাষ্ট্ৰীয়কিকৰণ',
'prefs-signature'             => 'স্বাক্ষৰ',
'prefs-dateformat'            => 'তাৰিখ বিন্যাস',
'prefs-advancedediting'       => 'উচ্চতৰ উপায়ান্তৰ সমূহ',
'prefs-advancedrc'            => 'উচ্চতৰ উপায়ান্তৰ সমূহ',
'prefs-advancedrendering'     => 'উচ্চতৰ উপায়ান্তৰ সমূহ',
'prefs-advancedsearchoptions' => 'উচ্চতৰ উপায়ান্তৰ সমূহ',
'prefs-advancedwatchlist'     => 'উচ্চতৰ উপায়ান্তৰ সমূহ',
'prefs-displayrc'             => 'প্ৰদৰ্শনী উপায়ান্তৰ সমূহ',
'prefs-diffs'                 => 'পাৰ্থক্য',

# User rights
'userrights'               => 'সদস্যৰ অধিকাৰ ব্যৱস্থাপনা',
'userrights-lookup-user'   => 'সদস্য গোটবোৰ ব্যৱস্থাপনা কৰক',
'userrights-user-editname' => 'সদস্যনাম দিয়ক:',
'editusergroup'            => 'সদস্য গোটবোৰ সম্পাদনা কৰক',
'editinguser'              => "'''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]]) সদস্যজনৰ অধিকাৰ সলনী কৰি থকা হৈছে।",
'userrights-editusergroup' => 'সদস্য গোট সম্পাদনা কৰক',
'saveusergroups'           => 'সদস্য গোট সংৰক্ষিত কৰক',
'userrights-groupsmember'  => 'এই গোটবোৰৰ সদস্য:',
'userrights-reason'        => 'কাৰণ:',

# Groups
'group'       => 'গোট:',
'group-sysop' => 'এডমিনিষ্ট্ৰেটৰসকল',

'grouppage-sysop' => '{{ns:project}}:প্রবন্ধক',

# Rights
'right-read'       => 'পৃষ্ঠাসমূহ পঢ়ক',
'right-edit'       => 'পৃষ্ঠাসমূহ সম্পাদনা কৰক',
'right-createpage' => 'পৃষ্ঠাসমূহ সৃষ্টি কৰক (কধাবতৰা পৃষ্ঠা নহয়)',
'right-createtalk' => 'কথাবতৰা পৃষ্ঠা সৃষ্টি কৰক',

# User rights log
'rightslog' => 'সভ্যৰ অধিকাৰৰ লেখ',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'       => 'এই পৃষ্ঠা পঢ়ক',
'action-edit'       => 'এই পৃষ্ঠা সম্পাদনা কৰক',
'action-createpage' => 'পৃষ্ঠা সৃষ্টি কৰক',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|সাল-সলনি|সাল-সলনি}}',
'recentchanges'                  => 'শেহতীয়া কাম',
'recentchanges-legend'           => 'সাম্প্রতিক সালসলনিৰ পছন্দসমূহ',
'recentchanges-feed-description' => 'ৱিকিত হোৱা শেহতিয়া সাল-সলনি এই ফীডত অনুসৰন কৰক।',
'rcnote'                         => "যোৱা {{PLURAL:$2|দিনত|'''$2''' দিনত}} সংঘটিত {{PLURAL:$1|'''১'''|'''$1'''}}টা সালসলনি, $5, $4 পৰ্যন্ত ।",
'rcnotefrom'                     => "তলত '''$2''' ৰ পৰা হোৱা ('''$1''' লৈকে) পৰিৱর্তন দেখুওৱা হৈছে ।",
'rclistfrom'                     => '$1 ৰ নতুন সাল-সলনি দেখুওৱাওক',
'rcshowhideminor'                => '$1 -সংখ্যক নগণ্য সম্পাদনা',
'rcshowhidebots'                 => 'বট $1',
'rcshowhideliu'                  => 'প্রবিষ্ট সভ্যৰ সাল-সলনি $1',
'rcshowhideanons'                => 'বেনাম সদস্যৰ সাল-সলনি $1',
'rcshowhidepatr'                 => '$1 পহৰা দিয়া সম্পাদনা',
'rcshowhidemine'                 => 'মোৰ সম্পাদনা $1',
'rclinks'                        => 'যোৱা $2 দিনত হোৱা $1 টা সাল-সলনি চাঁওক ।<br />$3',
'diff'                           => 'পার্থক্য',
'hist'                           => 'ইতিবৃত্ত',
'hide'                           => 'দেখুৱাব নালাগে',
'show'                           => 'দেখুওৱাওক',
'minoreditletter'                => 'ন:',
'newpageletter'                  => 'ন:',
'boteditletter'                  => 'য:',
'rc_categories_any'              => 'যিকোনো',
'rc-enhanced-expand'             => 'সবিশেষ দেকোৱাওক (জাভাস্ক্ৰিপ্টৰ প্ৰয়োজন)',
'rc-enhanced-hide'               => 'সবিশেষ  লুকাওঁক',

# Recent changes linked
'recentchangeslinked'          => 'প্রাসংগিক সালসলনিসমূহ',
'recentchangeslinked-feed'     => 'প্রাসংগিক সম্পাদনানমূহ',
'recentchangeslinked-toolbox'  => 'প্রাসংগিক সম্পাদনানমূহ',
'recentchangeslinked-title'    => '"$1"ৰ লগত জৰিত সাল-সলনি',
'recentchangeslinked-noresult' => 'দিয়া সময়ৰ ভিতৰত সংযোজিত পৃষ্ঠা সমূহত সাল-সলনি হোৱা নাই |',
'recentchangeslinked-page'     => 'পৃষ্ঠাৰ নাম:',
'recentchangeslinked-to'       => 'অন্যথা নিৰ্দিষ্ট পৃষ্ঠাৰ লগত সংযুক্ত পৃষ্ঠাসমূহৰ সালসলনি দেখোৱাওক',

# Upload
'upload'              => "ফাইল আপল'ড",
'uploadbtn'           => 'ফাইল আপলোড কৰক',
'uploadlogpage'       => 'আপলোড সুচী',
'uploadwarning'       => 'আপলোড সতৰ্কবাণী',
'savefile'            => 'সংৰক্ষণ',
'uploadedimage'       => '"[[$1]]" আপলোড কৰা হ’ল',
'upload-options'      => "আপল'ড বিকল্পসমুহ",
'upload-success-subj' => "আপলোড সফল হ'ল",

# Special:ListFiles
'listfiles'             => 'ফাইলৰ তালিকা',
'listfiles_date'        => 'তাৰিখ',
'listfiles_name'        => 'নাম',
'listfiles_user'        => 'সদস্য',
'listfiles_size'        => 'মাত্ৰা',
'listfiles_description' => 'বিৱৰণ',
'listfiles_count'       => 'সংস্কৰণ',

# File description page
'file-anchor-link'          => 'চিত্র',
'filehist'                  => 'ফাইলৰ ইতিবৃত্ত',
'filehist-help'             => 'ফাইলৰ আগৰ অৱ্স্থা চাবলৈ সেই তাৰিখ/সময়ত টিপা মাৰক ।',
'filehist-deleteone'        => 'মচি পেলাওঁক',
'filehist-current'          => 'বর্তমান',
'filehist-datetime'         => 'তাৰিখ/সময়',
'filehist-thumb'            => 'ক্ষুদ্রাকৃতি প্ৰতিকৃতি',
'filehist-thumbtext'        => '$1 পৰ্যন্ত ক্ষুদ্রাকৃতি প্ৰতিকৃতি সংস্কৰণ',
'filehist-user'             => 'সদস্য',
'filehist-dimensions'       => 'আকাৰ',
'filehist-filesize'         => 'ফাইলৰ আকাৰ (বাইট)',
'filehist-comment'          => 'মন্তব্য',
'filehist-missing'          => 'ফাইল সন্ধানহীন',
'imagelinks'                => 'ফাইল সংযোগসমূহ',
'linkstoimage'              => 'তলত দিয়া পৃষ্ঠাবোৰ এই চিত্র খনৰ লগত জৰিত :{{PLURAL:$1|page links|$1 pages link}}',
'nolinkstoimage'            => 'এই চিত্রখনলৈ কোনো পৃষ্ঠা সংযোজিত নহয়',
'sharedupload'              => 'এই ফাইলখন $1-ৰ পৰা লোৱা হৈছে আৰু অন্যান্য প্ৰকল্পতো ব্যৱহাৰ হব পাৰে ।',
'uploadnewversion-linktext' => 'এই ফাইলতোৰ নতুন সংশোধন এটা বোজাই কৰক',
'shared-repo-from'          => '$1 পৰা',

# File reversion
'filerevert-comment' => 'মন্তব্য:',

# List redirects
'listredirects' => 'পূণঃনির্দেশিত তালিকা',

# Random page
'randompage' => 'আকস্মিক পৃষ্ঠা',

# Statistics
'statistics' => 'পৰিসংখ্যা',

'doubleredirects' => 'দ্বি-পূণঃনির্দেশিত',

'brokenredirects-edit' => 'সম্পাদনা কৰক',

# Miscellaneous special pages
'nbytes'         => '$1 {{PLURAL:$1|বাইট|বাইট}}',
'nlinks'         => '$1 {{PLURAL:$1|সংযোগ|সংযোগ}}',
'nmembers'       => '{{PLURAL:$1|সদস্য|$1 সদস্যবৃন্দ}}',
'prefixindex'    => 'উপসর্গ সহ সকলো পৃষ্ঠা',
'longpages'      => 'দিঘলীয়া পৃষ্ঠাসমুহ',
'deadendpages'   => 'ডেড এণ্ড পৃষ্ঠাসমুহ',
'protectedpages' => 'সুৰক্ষিত পৃষ্ঠাসমুহ',
'listusers'      => 'সদস্য-সুচী',
'newpages'       => 'নতুন পৃষ্ঠা',
'ancientpages'   => 'আটাইটকৈ পুৰণি পৃষ্ঠাসমুহ',
'move'           => 'স্থানান্তৰন',
'movethispage'   => 'এই পৃষ্ঠাটো স্থানান্তৰিত কৰক',
'pager-newer-n'  => '{{PLURAL:$1|নতুনতৰ ১টি|নতুনতৰ $1টি}}',
'pager-older-n'  => '{{PLURAL:$1|পুৰণতৰ ১|পুৰণতৰ $1}}',

# Book sources
'booksources'               => 'গ্রন্থৰ উৎস সমূহ',
'booksources-search-legend' => 'গ্ৰন্থ উৎস অনুসন্ধান',
'booksources-go'            => 'যাওঁক',

# Special:Log
'specialloguserlabel'  => 'সভ্য:',
'speciallogtitlelabel' => 'শিৰোণামা:',
'log'                  => 'আলেখ',
'all-logs-page'        => 'সকলো সুচী',

# Special:AllPages
'allpages'       => 'সকলোবোৰ পৃষ্ঠা',
'alphaindexline' => '$1 -ৰ পৰা $2 -লৈ',
'nextpage'       => 'পৰৱর্তী পৃষ্ঠা ($1)',
'prevpage'       => 'পিছৰ পৃষ্ঠা($1)',
'allpagesfrom'   => 'ইয়াৰে আৰম্ভ হোৱা পৃষ্ঠাবোৰ দেখুৱাওক:',
'allpagesto'     => 'সেই পৃষ্ঠা দেখোৱাওক যাৰ শেষ:',
'allarticles'    => 'সকলো পৃষ্ঠা',
'allpagesprev'   => 'আগৰ',
'allpagessubmit' => 'যাওক',
'allpagesprefix' => 'এই উপশব্দৰে আৰম্ভ হোৱা পৃষ্ঠা দেখুৱাওক:',

# Special:Categories
'categories' => 'শ্রেণী',

# Special:LinkSearch
'linksearch' => 'বহিঃ-সংযোগ',

# Special:Log/newusers
'newuserlogpage'              => 'সদস্যৰ সৃষ্টি অভিলেখ',
'newuserlog-byemail'          => 'গুপ্তশব্দ ই-মেইল কৰি পঠোৱা হৈছে',
'newuserlog-create-entry'     => 'নতুন সদস্য',
'newuserlog-create2-entry'    => '$1 ক নতুন সদস্যভুক্তি কৰা হল',
'newuserlog-autocreate-entry' => 'স্বয়ংক্রীয়ভাবে নতুন সদস্যভুক্তি কৰা হল',

# Special:ListGroupRights
'listgrouprights-members' => '(সদস্যবৃন্দ তালিকা)',

# E-mail user
'emailuser' => 'এই সদস্যজনলৈ ই-মেইল পথাওক',

# Watchlist
'watchlist'         => 'মই অনুসৰণ কৰা পৃষ্ঠাবিলাকৰ তালিকা',
'mywatchlist'       => 'মোৰ অনুসৰণ-তালিকা',
'addedwatch'        => 'লক্ষ্য তালিকাত অন্তর্ভুক্তি কৰা হল',
'addedwatchtext'    => 'আপোনাৰ [[Special:Watchlist|লক্ষ্য তালিকাত ]]  "<nowiki>$1</nowiki>" অন্তর্ভুক্তি কৰা হল ।
ভৱিশ্যতে ইয়াত হোৱা সাল-সলনি আপুনি আপোনাৰ লক্ষ্য তালিকাত দেখিব, লগতে [[Special:RecentChanges|সম্প্রতিক সাল-সলনিৰ তালিকাত]] এই পৃষ্ঠাটো শকট আখৰত দেখিব যাতে আপুনি সহজে ধৰিব পাৰে ।',
'removedwatch'      => 'লক্ষ্য-তালিকাৰ পৰা আতৰোৱা হল',
'removedwatchtext'  => '"[[:$1]]" পৃষ্ঠাখন [[Special:Watchlist|আপোনাৰ লক্ষ্য-তালিকা]]ৰ পৰা আতৰোৱা হৈছে ।',
'watch'             => 'অনুসৰণাৰ্থে',
'watchthispage'     => 'এই পৃষ্ঠাটো লক্ষ্য কৰক',
'unwatch'           => 'অনুসৰণ কৰিব নালাগে',
'watchlist-details' => 'আলোচনা পৃষ্ঠা সমূহ লেখত নধৰি {{PLURAL:$1|$1 খন পৃষ্ঠা|$1 খন পৃষ্ঠা}} আপোনাৰ লক্ষ্য-তালিকাত আছে।',
'wlshowlast'        => 'যোৱা $1 ঘণ্টা $2 দিন $3 চাওক',
'watchlist-options' => 'লক্ষ্য-তালিকা পছন্দসমূহ',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'অনুসৰণভুক্ত কৰা হৈ আছে.....',
'unwatching' => 'অনুসৰণমুক্ত কৰা হৈ আছে.....',

'enotif_impersonal_salutation' => '{{SITENAME}} সডস্য',
'enotif_anon_editor'           => 'বেনামী সডস্য $1',

# Delete
'deletepage'            => 'পৃষ্ঠা বিলোপ কৰক',
'confirm'               => 'নিশ্চিত কৰক',
'delete-confirm'        => '"$1" বিলোপ কৰক',
'delete-legend'         => 'বিলোপ কৰক',
'historywarning'        => "'''সাবধান:''' আপুনি বিলোপ কৰিব বিছৰা পৃষ্ঠাখনৰ ইতিহাসত প্ৰায় {{PLURAL:$1|সংস্কৰণ|সংস্কৰণ}} আছে:",
'confirmdeletetext'     => 'আপুনি পৃষ্ঠা এটা তাৰ ইতিহাসৰ সৈতে বিলোপ কৰিব ওলাইছে।
অনুগ্রহ কৰি নিশ্বিত কৰক যে এয়া [[{{MediaWiki:Policy-url}}|নীতিসম্মত]] । লগতে আপুনি ইয়াৰ পৰিণাম জানে আৰু আপুনি এয়া কৰিব বিছাৰিছে।',
'actioncomplete'        => 'কার্য্য সম্পূর্ণ',
'deletedtext'           => '"<nowiki>$1</nowiki>" ক বিলোপন কৰা হৈছে।
সাম্প্রতিক বিলোপনসমূহৰ তালিকা চাবলৈ $2 চাঁওক।',
'deletedarticle'        => '"[[$1]]" ক বাতিল কৰা হৈছে।',
'dellogpage'            => 'বাতিল কৰা সুচী',
'deletecomment'         => 'কাৰণ:',
'deleteotherreason'     => 'আন/অতিৰিক্ত কাৰণ:',
'deletereasonotherlist' => 'আন কাৰণ:',

# Rollback
'rollbacklink' => 'পূৰ্ববৎ কৰিবলৈ',

# Protect
'protectlogpage'              => 'সুৰক্ষা সুচী',
'protectedarticle'            => 'সুৰক্ষিত "[[$1]]"',
'modifiedarticleprotection'   => '"[[$1]]"-ৰ সুৰক্ষাৰ স্তৰ শলনি কৰা হৈছে',
'prot_1movedto2'              => '$1 ক $2 লৈ স্থানান্তৰিত কৰা হল',
'protectcomment'              => 'কাৰণ:',
'protectexpiry'               => 'সময় শেষ:',
'protect_expiry_invalid'      => 'শেষ সময় ভুল ।',
'protect_expiry_old'          => 'শেষ সময় পাৰ হৈ গৈছে।',
'protect-text'                => "'''<nowiki>$1</nowiki>''' পৃষ্ঠাটোৰ সুৰক্ষা-স্তৰ আপুনি চাব আৰু সলনি কৰিব পাৰে।",
'protect-locked-access'       => "এই পৃষ্ঠাটোৰ সুৰক্ষা-স্তৰ সলনি কৰাৰ অনুমতি আপোনাক দিয়া হোৱা নাই ।
'''$1''' এই পৃষ্ঠাটোৰ সুৰক্ষা-স্তৰৰ গাঠনী ইয়াত আছে:",
'protect-default'             => 'সকলো ব্যবহাৰকাৰীক অনুমতি দিয়ক',
'protect-fallback'            => '"$1" অনুমতি লাগিব',
'protect-level-autoconfirmed' => 'নতুন বা নথিভুক্ত নোহোৱা সদস্যক বাৰণ কৰক',
'protect-level-sysop'         => 'কেবল প্ৰশাসকবৃন্দৰ বাবে',
'protect-expiring'            => ' $1 (UTC) ত সময় শেষ হব',
'protect-cascade'             => 'এই পৃষ্ঠাটোৰ লগত জৰিত সকলো পৃষ্ঠা সুৰক্ষিত কৰক (সুৰক্ষা জখলা)',
'protect-cantedit'            => 'আপুনি এই পৃষ্ঠাটোৰ সুৰক্ষা-স্তৰ সলনি কৰিব নোৱৰে, কাৰণ আপোনাক সেই অনুমতি দিয়া হোৱা নাই।',
'protect-expiry-options'      => '‌১ ঘণ্টা:1 hour,১ দিন:1 day,১ সপ্তাহ:1 week,২ সপ্তাহ:2 weeks,১ মাহ:1 month,৩ মাহ:3 months,৬ মাহ:6 months,১ বছৰ:1 year,অনির্দিস্ট কাল:infinite',
'restriction-type'            => 'অনুমতি:',
'restriction-level'           => 'সুৰক্ষা-স্তৰ:',

# Undelete
'undeletebtn'               => 'পূণঃসংস্থাপন কৰক',
'undeletelink'              => 'লক্ষ্য কৰক/ঘূৰাই আনক',
'undeletedarticle'          => '"[[$1]]"-ক পূৰ্বস্থানলৈ ঘূৰাই অনা হ\'ল',
'undelete-search-submit'    => 'সন্ধান',
'undelete-show-file-submit' => 'অঁ',

# Namespace form on various pages
'namespace'      => 'নামস্থান:',
'invert'         => 'নির্বাচন ওলোটা কৰক',
'blanknamespace' => '(মুখ্য)',

# Contributions
'contributions'       => 'সদস্যৰ অৱদান',
'contributions-title' => '$1-ৰ অবদানসমূহ',
'mycontris'           => 'মোৰ অৱদানসমূহ',
'contribsub2'         => '$1 ৰ কাৰণে($2)',
'uctop'               => '(ওপৰত)',
'month'               => 'এই মাহৰ পৰা (আৰু আগৰ):',
'year'                => 'এই বছৰৰ পৰা (আৰু আগৰ):',

'sp-contributions-newbies'     => 'কেৱল নতুন একাউন্টৰ অবদানসমূহ দেখোৱাওঁক',
'sp-contributions-newbies-sub' => 'নতুন সভ্যৰ কাৰণে',
'sp-contributions-blocklog'    => 'বাৰণ সুচী',
'sp-contributions-logs'        => 'অভিলেখ',
'sp-contributions-talk'        => 'আলোচনা',
'sp-contributions-userrights'  => 'সদস্যৰ অধিকাৰ ব্যৱস্থাপনা',
'sp-contributions-search'      => 'অবদানসমূহৰ কাৰণে অনুসন্ধান কৰক',
'sp-contributions-username'    => 'আইপি ঠিকনা অথবা ব্যৱহাৰকৰ্তাৰ নাম:',
'sp-contributions-submit'      => 'সন্ধান কৰক',

# What links here
'whatlinkshere'            => 'এই পৃষ্ঠা ব্যৱ্হাৰ কৰিছে...',
'whatlinkshere-title'      => '"$1"-লৈ সংযোগ কৰা পৃষ্ঠাসমূহ',
'whatlinkshere-page'       => 'পৃষ্ঠা:',
'linkshere'                => "এই পৃষ্ঠাটো '''[[:$1]]''' ৰ লগত সংযোজিত:",
'nolinkshere'              => "'''[[:$1]]''' ৰ লগত কোনো পৃষ্ঠা সংযোজিত নহয়।",
'isredirect'               => 'পূণঃনির্দেশন পৃষ্ঠা',
'istemplate'               => 'অন্তর্ভুক্ত কৰক',
'isimage'                  => 'চিত্ৰ সংযোগ',
'whatlinkshere-prev'       => '{{PLURAL:$1|পিছৰ|পিছৰ $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|আগৰ|আগৰ $1}}',
'whatlinkshere-links'      => '← সূত্রসমূহ',
'whatlinkshere-hideredirs' => '$1 পুননিৰ্দেশনাসমূহ',
'whatlinkshere-hidetrans'  => '$1 ট্ৰেন্সক্লুস্বন-সমূহ',
'whatlinkshere-hidelinks'  => '$1 টি সংযোগ',
'whatlinkshere-filters'    => 'ছাকনী',

# Block/unblock
'blockip'                  => 'সদস্য বাৰণ কৰক',
'ipbreason'                => 'কাৰণ:',
'ipbreasonotherlist'       => 'অন্য কাৰণ',
'ipboptions'               => '২ ঘ্ণ্টা:2 hours,১ দিন:1 day,৩ দিন:3 days,১ সপ্তাহ:1 week,২ সপ্তাহ:2 weeks,১ মাহ:1 month,৩ মাহ:3 months,৬ মাহ:6 months,১ বছৰ:1 year,অনির্দিস্ট কাল:infinite',
'ipblocklist'              => 'বাৰণ কৰা আই-পি ঠিকনা আৰু সদস্যৰ তালিকা',
'blocklink'                => 'সদস্যভুক্তি ৰদ',
'unblocklink'              => 'প্ৰতিৰোধ উঠাই লওঁক',
'change-blocklink'         => 'ব্লক শলনি কৰক',
'contribslink'             => 'অবদান',
'blocklogpage'             => 'বাৰণ কৰা সুচী',
'blocklogentry'            => '"[[$1]]" ক $2 $3 লৈ সাল-সলনি কৰাৰ পৰা বাৰণ কৰা হৈছে।',
'unblocklogentry'          => "$1 বাৰণ পন্ড কৰা হ'ল",
'block-log-flags-nocreate' => 'একাউন্ট সৃষ্টি নিষ্ক্ৰিয় কৰা হৈছে',

# Move page
'movepagetext'    => "তলৰ ফৰ্ম ব্যবহাৰ কৰিলে এই পৃষ্ঠাৰ শিৰোনামা সলনি হ'ব, লগতে সমগ্ৰ ইতিহাস নতুন শিৰোনামালৈ স্থানান্তৰ কৰা হ'ব ।
পুৰণা শিৰোনামাটো নতুন শিৰোনামালৈ এটা পুনৰ্নিৰ্দেশনা হৈ ৰ'ব ।
সমগ্ৰ পুনৰ্নিৰ্দেশনাসমূহ যি পুৰণা শিৰোনামালৈ পোনায়, আপুনি  স্বয়ংক্ৰিয় ভাবে আপডেট কৰিব পাৰিব ।
যদি এই কৰিব নিবিচাৰে তেনেহলে  [[Special:DoubleRedirects|দুনা পুনৰ্নিৰ্দেশনসমূহ]] বা [[Special:BrokenRedirects|ভঙা পুনৰ্নিৰ্দেশনসমূহ]] চয়ন কৰে যেন ।
যে সকলো সংযোগ সঠিক দিশলৈ পোনাই, আপুনিয়েই জবাবদিহি ।

মন কৰিব যে নতুন শিৰোণামাতো যদি প্ৰচলিত, এই পৃষ্ঠা নতুন শিৰোনামালৈ শলনি কৰা '''নহ'ব''' যদিহে সেই পৃষ্ঠা খালি বা কোনো পুনৰ্নিৰ্দেশনৰ পুৰ্ব ইতিহাস নাই ।
ইয়াৰ অর্থ এয়ে যে ভুল হলে পৃষ্ঠাখন আগৰ ঠাইতে থাকিব, আৰু আপুনি প্ৰচলিত পৃষ্ঠা এখনক আন পৃষ্ঠা এখনেৰে সলনি কৰিব নোৱাৰে।

'''সাৱধান!'''
জনপ্রীয় পৃষ্ঠা এখনৰ বাবে এয়া এক ডাঙৰ আৰু অনাপেক্ষিত সাল-সলনি হব পাৰে;
এই কাৰ্য্যৰ পৰিণাম ভালদৰে বিবেচনা কৰি লই যেন।",
'movearticle'     => 'পৃস্থা স্থানান্তৰ কৰক',
'newtitle'        => 'নতুন শিৰোণামালৈ:',
'move-watch'      => 'এই পৃষ্ঠাটো লক্ষ্য কৰক',
'movepagebtn'     => 'পৃষ্ঠাটো স্থানান্তৰ কৰক',
'pagemovedsub'    => 'স্থানান্তৰ সফল হল',
'movepage-moved'  => "'''“$1” ক “$2” লৈ স্থানান্তৰ কৰা হৈছে'''",
'articleexists'   => 'সেই নামৰ পৃষ্ঠা এটা আগৰ পৰাই আছে, বা সেই নামতো অযোগ্য।
বেলেগ নাম এটা বাছি লওক।',
'talkexists'      => "'''পৃষ্ঠাটো স্থানান্তৰ কৰা হৈছে, কিন্তু ইয়াৰ লগত জৰিত বার্তা পৃষ্ঠাটো স্থানান্তৰ কৰা নহল, কাৰণ নতুন ঠাইত বার্তা পৃষ্ঠা এটা আগৰ পৰাই আছে।
অনুগ্রহ কৰি আপুনি নিজে স্থানান্তৰ কৰক ।'''",
'movedto'         => 'লৈ স্থানান্তৰ কৰা হল',
'movetalk'        => 'সংলগ্ন বার্তা পৃষ্ঠা স্থানান্তৰ কৰক',
'1movedto2'       => '[[$1]]ক [[$2]] লৈ স্থানান্তৰিত কৰা হল',
'1movedto2_redir' => "[[$1]]-ক [[$2]]-লৈ পুনৰ্নিৰ্দেশনাৰ সহায়েৰে স্থানান্তৰ কৰা হ'ল",
'movelogpage'     => 'স্থানান্তৰন সুচী',
'movereason'      => 'কাৰণ:',
'revertmove'      => 'আগৰ অৱ্স্থালৈ ঘুৰি যাওক',

# Export
'export' => 'পৃষ্ঠা নিষ্কাষন',

# Namespace 8 related
'allmessages'               => 'ব্যৱস্থাৰ বতৰা',
'allmessages-filter-all'    => 'সকলো',
'allmessages-language'      => 'ভাষা:',
'allmessages-filter-submit' => 'যাওঁক',

# Thumbnails
'thumbnail-more'  => 'বিবৰ্ধনাৰ্থে',
'thumbnail_error' => 'থাম্বনেইল বনাব অসুবিধা হৈছে: $1',

# Import log
'importlogpage' => 'আমদানী সুচী',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'আপুনাৰ সদস্য পৃষ্ঠা',
'tooltip-pt-mytalk'               => 'আপুনাৰ আলোচনা পৃষ্ঠা',
'tooltip-pt-preferences'          => 'মোৰ পচন্দ',
'tooltip-pt-watchlist'            => 'আপুনি সালসলনিৰ গতিবিধি লক্ষ কৰি থকা পৃষ্ঠাসমূহৰ সুচী',
'tooltip-pt-mycontris'            => 'আপুনাৰ আৰিহনাৰ তালিকা',
'tooltip-pt-login'                => 'অত্যাবশ্যক নহলেও লগ-ইন কৰা বাঞ্চনীয়',
'tooltip-pt-logout'               => 'লগ-আউট',
'tooltip-ca-talk'                 => 'সংশ্লিষ্ট প্রৱন্ধ সম্পৰ্কীয় আলোচনা',
'tooltip-ca-edit'                 => 'আপুনি এই পৃষ্ঠাটো সালসলনি কৰিব পাৰে, অনুগ্রহ কৰি সালসলনি সাচী থোৱাৰ আগতে খচৰা চাই লব',
'tooltip-ca-addsection'           => 'নতুন অনুচ্ছেদ আৰম্ভ কৰক',
'tooltip-ca-viewsource'           => 'এই পৃষ্ঠাটো সংৰক্ষিত কৰা হৈছে, আপুনি ইয়াৰ উত্‍স চাব পাৰে।',
'tooltip-ca-history'              => 'এই পৃষ্ঠাৰ যোৱা সংস্কৰণসমূহ',
'tooltip-ca-protect'              => 'এই পৃষ্ঠাটো সুৰক্ষিত কৰক',
'tooltip-ca-delete'               => 'এই পৃষ্ঠাটো বিলোপ কৰক',
'tooltip-ca-move'                 => 'এই পৃষ্ঠাটো স্থানান্তৰিত কৰক',
'tooltip-ca-watch'                => 'এই পৃষ্ঠাটো আপোনাৰ অনুসৰণ-তালিকাত যোগ কৰক',
'tooltip-ca-unwatch'              => 'এই পৃষ্ঠাটো আপোনাৰ লক্ষ-সূচীৰ পৰা আতৰোৱাওক',
'tooltip-search'                  => '{{SITENAME}} -ত সন্ধানাৰ্থে',
'tooltip-search-go'               => 'যদি আছে, তেহে ঠিক সেই নামৰ পৃষ্ঠালৈ যাওঁক',
'tooltip-search-fulltext'         => 'এই পাঠ পৃষ্ঠাসমূহট অনুসন্ধান কৰক',
'tooltip-p-logo'                  => 'বেটুপাত খুলিবৰ কাৰণে',
'tooltip-n-mainpage'              => 'বেটুপাত খুলিবৰ কাৰণে',
'tooltip-n-mainpage-description'  => 'প্ৰথম পৃষ্ঠা পৰিদৰ্শন কৰক',
'tooltip-n-portal'                => "এই প্রকল্পৰ ইতিবৃত্ত, আপুনি কেনেকৈ সহায় কৰিব পাৰে, ইত্যাদি (কি, ক'ত কিয় বিখ্যাত!!)।",
'tooltip-n-currentevents'         => 'এতিয়াৰ ঘটনাৰাজীৰ পটভূমী',
'tooltip-n-recentchanges'         => 'শেহতীয়া সালসলনিসমূহৰ সূচী',
'tooltip-n-randompage'            => 'অ-পূৰ্বনিৰ্ধাৰিতভাবে যিকোনো এটা পৃষ্ঠা দেখুৱাবৰ কাৰণে',
'tooltip-n-help'                  => 'সহায়ৰ বাবে ইয়াত ক্লিক কৰক',
'tooltip-t-whatlinkshere'         => 'ইয়ালৈ সংযোজিত সকলো পৃষ্ঠাৰ সুচী',
'tooltip-t-recentchangeslinked'   => 'সংযুক্ত পৃষ্ঠাসমূহৰ শেহতিয়া সালসলনিসমূহ',
'tooltip-feed-rss'                => 'এই পৃষ্ঠাৰ বাবে আৰ-এচ-এচ ফিড',
'tooltip-feed-atom'               => 'এই পৃষ্ঠাৰ বাবে এটম ফিড',
'tooltip-t-contributions'         => 'এই সদস্যজনৰ অৰিহনাসমূহৰ সূচী চাঁওক ।',
'tooltip-t-emailuser'             => 'এই সদস্যজনলৈ ই-মেইল পঠাওক',
'tooltip-t-upload'                => "ফাইল আপল'ড-অৰ অৰ্থে",
'tooltip-t-specialpages'          => 'বিশেষ পৃষ্ঠাসমূ্হৰ সূচী',
'tooltip-t-print'                 => 'এ পৃষ্ঠাৰ ছপা উপযোগী সংস্কৰণ',
'tooltip-t-permalink'             => 'পৃষ্ঠাৰ এই সংস্কৰণৰ স্থায়ী সংযোগ',
'tooltip-ca-nstab-main'           => 'এই ৱিকিৰ সূচি চাঁওক',
'tooltip-ca-nstab-user'           => 'সভ্যৰ ব্যক্তিগত পৃষ্ঠালৈ',
'tooltip-ca-nstab-special'        => 'এইখন এখন বিশেষ পৃষ্ঠা, আপুনি সম্পাদনা কৰিব নোৱাৰে',
'tooltip-ca-nstab-project'        => 'আচনী পৃষ্ঠা চাঁওক।',
'tooltip-ca-nstab-image'          => 'নথি পৃষ্ঠা চাওক',
'tooltip-ca-nstab-template'       => 'সাঁচ চাওক',
'tooltip-ca-nstab-help'           => 'সহায় পৃষ্ঠা চাওক',
'tooltip-ca-nstab-category'       => 'শ্রেণী পৃষ্ঠা চাঁওক ।',
'tooltip-minoredit'               => 'ইয়াক অগুৰুত্বপূর্ণ সম্পাদনা ৰূপে চিহ্নিত কৰক।',
'tooltip-save'                    => 'আপুনি কৰা সালসলনি সাচী থঁওক',
'tooltip-preview'                 => 'অপুনি কৰা সালসলনিবোৰৰ খচৰা চাওক, আনুগ্রহ কৰি সালসলনি সাচী থোৱাৰ আগতে ব্যৱহাৰ কৰক!',
'tooltip-diff'                    => 'ইয়াত আপুনি কৰা সালসলনিবোৰ দেখুৱাওক',
'tooltip-compareselectedversions' => 'এই পৃষ্ঠাত নির্বাচিত কৰা দুটা অৱতৰৰ মাজত পার্থক্য দেখুৱাওক ।',
'tooltip-watch'                   => 'এই পৃষ্ঠাটো আপোনাৰ অনুসৰণতালিকাভুক্ত কৰক',

# Info page
'numedits' => 'সম্পাদনাৰ সংখ্যা (পৃষ্ঠা): $1',

# Math errors
'math_failure'          => 'পার্চ কৰিব অসমর্থ',
'math_unknown_error'    => 'অপৰিচিত সমস্যা',
'math_unknown_function' => 'অজ্ঞাত কার্য্য',

# Browsing diffs
'previousdiff' => 'প্ৰবীণ সম্পাদনা',
'nextdiff'     => 'নতুনতৰ সম্পাদনা →',

# Media information
'file-info-size'       => '($1 × $2 পিক্সেল, ফাইলৰ মাত্ৰা: $3, MIME প্ৰকাৰ: $4)',
'file-nohires'         => '<small>ইয়াতকৈ ডাঙৰকৈ দেখুৱাব নোৱাৰি ।</small>',
'svg-long-desc'        => '(SVG ফাইল, সাধাৰণতঃ $1 × $2 পিক্সেল, ফাইল মাত্ৰা: $3)',
'show-big-image'       => 'সম্পূর্ণ দৃশ্য',
'show-big-image-thumb' => '<small>এই খচৰাৰ আকাৰ: $1 × $2 পিক্সেল </small>',

# Special:NewFiles
'newimages' => 'নতুন ফাইলৰ বিথীকা',
'ilsubmit'  => 'সন্ধান কৰক',
'bydate'    => 'তাৰিখ অনুযায়ী',

# Metadata
'metadata'          => 'মেটাডাটা',
'metadata-help'     => 'এই ফাইলত অতিৰিক্ত খবৰ আছে, হয়তো ডিজিটেল কেমেৰা বা স্কেনাৰ ব্যৱহাৰ কৰি সৃস্তি বা পৰিৱর্তন কৰা হৈছে|
এই ফাইলটো আচলৰ পৰা পৰিৱর্তন  কৰা হৈছে, সেয়েহে পৰিৱর্তিত ফাইলটোৰ সৈতে নিমিলিব পাৰে|',
'metadata-expand'   => 'বহলাই ইয়াৰ বিষয়ে জনাওক',
'metadata-collapse' => 'বিষয় বৰ্ণনা নেদেখুৱাবলৈ',
'metadata-fields'   => 'এই সুচীত থকা বিষয়বোৰ চিত্রৰ পৃষ্ঠাৰ তলত সদায় দেখা যাব ।
বাকী বিষয়বোৰ গুপ্ত থাকিব ।
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'কিলোমিটাৰ প্ৰতি ঘন্টা',
'exif-gpsspeed-m' => 'মাইল প্ৰতি ঘন্টা',

# External editor support
'edit-externally'      => 'বাহিৰা আহিলা ব্যৱহাৰ কৰি এই ফাইলটো সম্পাদনা কৰক ।',
'edit-externally-help' => 'অধিক তথ্যৰ কাৰণে [http://www.mediawiki.org/wiki/Manual:External_editors প্ৰস্তুত কৰা নির্দেশনা] চাঁওক ।',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'সকলোবোৰ',
'imagelistall'     => 'সকলোবোৰ',
'watchlistall2'    => 'সকলো',
'namespacesall'    => 'সকলোবোৰ',
'monthsall'        => 'সকলো',
'limitall'         => 'সকলোবোৰ',

# Delete conflict
'recreate' => 'পুনৰ সৃষ্টি কৰক',

# action=purge
'confirm_purge_button' => "অ'কে",

# Multipage image navigation
'imgmultipageprev' => '← পূৰ্ববৰ্তী পৃষ্ঠা',
'imgmultipagenext' => 'পৰবৰ্তী পৃষ্ঠা →',
'imgmultigo'       => 'যাওঁক',
'imgmultigoto'     => '$1 পৃষ্ঠালৈ যাওঁক',

# Table pager
'table_pager_next'         => 'পৰৱৰ্তী পৃষ্ঠা',
'table_pager_prev'         => 'পূৰ্ববৰ্তী পৃষ্ঠা',
'table_pager_first'        => 'প্ৰথম পৃষ্ঠা',
'table_pager_last'         => 'শেষ পৃষ্ঠা',
'table_pager_limit_submit' => 'যাওঁক',
'table_pager_empty'        => 'ফলাফল নাই',

# Live preview
'livepreview-loading' => 'লোডিং…',
'livepreview-ready'   => 'লোডিং… প্ৰস্তুত!',

# Watchlist editor
'watchlistedit-numitems'     => 'কথাবতৰা পৃষ্ঠাসমূহ বাদ দি, আপুনাৰ অনুসৰণ-তালিকাত {{PLURAL:$1|১-খন|$1-খন}} ঘাই পৃষ্ঠা আছে ।',
'watchlistedit-noitems'      => 'আপুনাৰ অনুসৰণ-তালিকাত এখনো ঘাই পৃষ্ঠা নাই ।',
'watchlistedit-normal-title' => 'অনুসৰণ-তালিকা সম্পাদন কৰক',
'watchlistedit-raw-title'    => 'অশোধিত অনুসৰণ-তালিকা সম্পাদন কৰক',
'watchlistedit-raw-legend'   => 'অশোধিত অনুসৰণ-তালিকা সম্পাদন কৰক',
'watchlistedit-raw-titles'   => 'শীৰ্ষক:',

# Watchlist editing tools
'watchlisttools-view' => 'সংগতি থকা সাল-সলনিবোৰ চাওক',
'watchlisttools-edit' => 'লক্ষ্য-তালিকা চাওক আৰু সম্পাদনা কৰক',
'watchlisttools-raw'  => 'কেঁচা লক্ষ্য-তালিকা সম্পাদনা কৰক',

# Special:Version
'version'                  => 'সংস্কৰণ',
'version-specialpages'     => 'বিশেষ পৃষ্ঠাসমূহ',
'version-other'            => 'অন্য',
'version-license'          => 'লাইচেঞ্চ',
'version-software-product' => 'পণ্য',

# Special:FilePath
'filepath'        => 'ফাইল পথ',
'filepath-page'   => 'ফাইল:',
'filepath-submit' => 'পথ',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'প্ৰতিলিপি পৃষ্ঠাসমূহ অনুসন্ধান কৰক',
'fileduplicatesearch-legend'   => 'প্ৰতিলিপিৰ বাবে অনুসন্ধান কৰক',
'fileduplicatesearch-filename' => 'ফাইলনাম:',
'fileduplicatesearch-submit'   => 'সন্ধান কৰক',

# Special:SpecialPages
'specialpages'                 => 'বিশেষ পৃষ্ঠাসমূহ',
'specialpages-group-other'     => 'অন্যান্য বিশেষ পৃষ্ঠাসমূহ',
'specialpages-group-pages'     => 'পৃষ্ঠাৰ তালিকাসমূহ',
'specialpages-group-pagetools' => 'পৃষ্ঠা সা-সঁজুলি',

# Special:BlankPage
'blankpage'              => 'খালী পৃষ্ঠা',
'intentionallyblankpage' => 'এই পৃষ্ঠা ইচ্ছাকৃত ভাবে খালি ৰখা হৈছে',

# Special:Tags
'tag-filter-submit' => 'সংশোধন',
'tags-title'        => 'টেগসমূহ',
'tags-edit'         => 'সম্পাদনা',

# HTML forms
'htmlform-submit'              => 'দাখিল কৰক',
'htmlform-reset'               => 'সাল-সলনি পণ্ড কৰক',
'htmlform-selectorother-other' => 'অন্য',

);

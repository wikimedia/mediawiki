<?php
/** Bengali (বাংলা)
 *
 * @addtogroup Language
 *
 * @author Bellayet
 * @author Zaheen
 * @author G - ג
 * @author Siebrand
 * @author Nike
 */

$namespaceNames = array(
	NS_SPECIAL        => 'বিশেষ',
	NS_MAIN           => '',
	NS_TALK           => 'আলাপ',
	NS_USER           => 'ব্যবহারকারী',
	NS_USER_TALK      => 'ব্যবহারকারী_আলাপ',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => '$1_আলাপ',
	NS_IMAGE          => 'চিত্র',
	NS_IMAGE_TALK     => 'চিত্র_আলাপ',
	NS_MEDIAWIKI_TALK => 'MediaWiki_আলাপ'
);
$datePreferences = false;
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

$messages = array(
# User preference toggles
'tog-underline'               => 'সংযোগগুলির নিচে আন্ডারলাইন করুন:',
'tog-highlightbroken'         => 'ভাঙা সংযোগগুলোকে <a href="" class="new">এই ভাবে</a> দেখাও (নতুবা <a href="" class="internal">?</a> হিসেবে দেখাবে)।',
'tog-justify'                 => 'অনুচ্ছেদগুলি পর্যালোচনা করা হোক',
'tog-hideminor'               => 'অনুল্লেখ্য সম্পাদনাগুলো দেখিও না',
'tog-extendwatchlist'         => 'সমস্ত প্রয়োগযোগ্য পরিবর্তন দেখানোর জন্য নজরতালিকা সম্প্রসারিত করা হোক।',
'tog-usenewrc'                => 'উন্নততর সাম্প্রতিক পরিবর্তনসমূহ (জাভাস্ক্রিপ্ট সংস্করণ)',
'tog-numberheadings'          => 'শিরোনামগুলোকে স্বয়ংক্রিয়ভাবে ক্রমাঙ্কিত করো',
'tog-showtoolbar'             => 'সম্পাদনা টুলবার দেখানো হোক (জাভাস্ক্রিপ্ট)',
'tog-editondblclick'          => 'দুই ক্লিক করে পাতা সম্পাদনা করো (জাভা স্ক্রিপ্ট)',
'tog-editsection'             => '[সম্পাদনা] সংযোগের সাহায্যে পরিচ্ছেদ সম্পাদনা করা হোক',
'tog-editsectiononrightclick' => 'পরিচ্ছেদের শিরোনামে ডান ক্লিকের মাধ্যমে <br /> পরিচ্ছেদ সম্পাদনার ক্ষমতা প্রদান করো (JavaScript)',
'tog-showtoc'                 => 'বিষয়বস্তুর ছক দেখাও (৩টি পরিচ্ছেদ শিরোনাম আছে এমন পাতার জন্য)',
'tog-rememberpassword'        => 'একাধিক সেশনের জন্য শব্দচাবি মনে রাখো',
'tog-editwidth'               => 'সম্পাদনা বাক্সটি তার পূর্ণ দৈর্ঘ্যে আছে',
'tog-watchcreations'          => 'আমার তৈরি পাতাসমূহ আমার নজরতালিকায় যোগ করো',
'tog-watchdefault'            => 'আমার সম্পাদনাগুলো আমার নজরতালিকায় যোগ কর',
'tog-watchmoves'              => 'আমার সরিয়ে ফেলা নিবন্ধগুলো আমার নজরতালিকায় যোগ কর',
'tog-watchdeletion'           => 'আমার মুছে ফেলা নিবন্ধগুলো আমার নজর তালিকায় যোগ কর',
'tog-minordefault'            => 'শুরুতেই সব সম্পাদনাকে অনুল্লেখ্য বলে চিহ্নিত কর',
'tog-previewontop'            => 'সম্পাদনা বাক্সের আগে প্রাকদর্শন দেখাও',
'tog-previewonfirst'          => 'প্রথম সম্পাদনার উপর প্রাকদর্শন দেখাও',
'tog-nocache'                 => 'পাতা ক্যাশ করার ক্ষমতা নিষ্ক্রিয় করা হোক',
'tog-enotifwatchlistpages'    => 'আমার নজরে আছে এমন পাতার পরিবর্তনে আমাকে ই-মেইল করো',
'tog-enotifusertalkpages'     => 'আমার আলাপের পাতার পরিবর্তনে আমাকে ই-মেইল করো',
'tog-enotifminoredits'        => 'পাতার অনুল্লেখ্য সম্পাদনার জন্যও আমাকে ই-মেইল করো',
'tog-enotifrevealaddr'        => 'বিজ্ঞপ্তি মেইলে আমার ই-মেইল ঠিকানা প্রকাশ করুন',
'tog-shownumberswatching'     => 'নজরকারীর সংখ্যা দেখাও',
'tog-fancysig'                => 'আপনার স্বাক্ষরে স্বয়ংক্রিয়ভাবে সংযোগ দিতে না চাইলে টিক দিন',
'tog-externaleditor'          => 'শুরুতেই বহিঃস্থ সম্পাদনা হাতিয়ার ব্যবহার করো',
'tog-externaldiff'            => 'শুরুতেই বহিঃস্থ পার্থক্য ব্যবহার করো',
'tog-showjumplinks'           => '"ঝাঁপ দিন" বৈশিষ্টের সংযোগ চালু করো',
'tog-uselivepreview'          => 'তাৎক্ষণিক প্রাকদর্শন ব্যবহার করো (JavaScript) (পরীক্ষামূলক)',
'tog-forceeditsummary'        => 'খালি সম্পাদনা সারাংশ দেওয়ার সময় আমাকে জানাও',
'tog-watchlisthideown'        => 'আমার সম্পাদনাগুলো আমার নজরতালিকায় দেখিও না',
'tog-watchlisthidebots'       => 'বট দ্বারা সম্পাদনাগুলো নজরতালিকায় দেখিও না',
'tog-watchlisthideminor'      => 'অনুল্লেখ্য সম্পাদনাগুলো নজর তালিকায় দেখিও না',
'tog-nolangconversion'        => 'বিকল্প রুপান্তর রোধ করো',
'tog-ccmeonemails'            => 'অন্য ব্যবহারকারীর কাছে আমার পাঠানো ইমেইলের একটি অনুলিপি আমাকে পাঠাও',
'tog-diffonly'                => 'পার্থক্যের নিচে পাতার বিষয়বস্তু দেখিও না',

'underline-always'  => 'সব সময়',
'underline-never'   => 'কখনও না',
'underline-default' => 'ব্রাউজারে যেমনটি ছিল',

'skinpreview' => '(প্রাশদর্শন)',

# Dates
'sunday'        => 'রবিবার',
'monday'        => 'সোমবার',
'tuesday'       => 'মঙ্গলবার',
'wednesday'     => 'বুধবার',
'thursday'      => 'বৃহস্পতিবার',
'friday'        => 'শুক্রবার',
'saturday'      => 'শনিবার',
'sun'           => 'রবিবার',
'mon'           => 'সোমবার',
'tue'           => 'মঙ্গলবার',
'wed'           => 'বুধবার',
'thu'           => 'রৃহস্পতিবার',
'fri'           => 'শক্রবার',
'sat'           => 'শনিবার',
'january'       => 'জানুয়ারি',
'february'      => 'ফেব্রুয়ারি',
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
'january-gen'   => 'জানুয়ারি',
'february-gen'  => 'ফেব্রুয়ারি',
'march-gen'     => 'মার্চ',
'april-gen'     => 'এপ্রিল',
'may-gen'       => 'মে',
'june-gen'      => 'জুন',
'july-gen'      => 'জুলাই',
'august-gen'    => 'আগস্ট',
'september-gen' => 'সেপ্টেম্বর',
'october-gen'   => 'অক্টোবর',
'november-gen'  => 'নভেম্বর',
'december-gen'  => 'ডিসেম্বর',
'jan'           => 'জানুয়ারি',
'feb'           => 'ফেব্রুয়ারি',
'mar'           => 'মার্চ',
'apr'           => 'এপ্রিল',
'may'           => 'মে',
'jun'           => 'জুন',
'jul'           => 'জুলাই',
'aug'           => 'আগস্ট',
'sep'           => 'সেপ্টেম্বর',
'oct'           => 'অক্টোবর',
'nov'           => 'নভেম্বর',
'dec'           => 'ডিসেম্বর',

# Bits of text used by many pages
'categories'            => 'বিষয়শ্রেণীসমূহ',
'pagecategories'        => '{{PLURAL:$1|বিষয়শ্রেণী|বিষয়শ্রেণীসমূহ}}',
'category_header'       => '"$1" বিষয়শ্রেণীতে অন্তর্ভুক্ত নিবন্ধসমূহ',
'subcategories'         => 'উপশ্রেণীসমূহ',
'category-media-header' => '"$1" বিষয়শ্রেণীতে অন্তর্ভুক্ত মিডিয়া ফাইলসমূহ',
'category-empty'        => "''এই বিষয়শ্রণীতে বর্তমানে কোন নিবন্ধ বা মিডিয়া ফাইল নাই।''",

'mainpagetext'      => "<big>'''মিডিয়াউইকি সফল ভাবে ইন্সটল হয়েছে।'''</big>",
'mainpagedocfooter' => 'কিভাবে উইকি সফটওয়্যারটি ব্যবহারকার করবে জানতে দেখুন [http://meta.wikimedia.org/wiki/Help:Contents ব্যবহারবিধি]।

== শুরু করুন ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]',

'about'          => 'বৃত্তান্ত',
'article'        => 'আধেয় পাতা',
'newwindow'      => '(নতুন উইন্ডোতে খুলবে)',
'cancel'         => 'বাতিল কর',
'qbfind'         => 'অনুসন্ধান',
'qbbrowse'       => 'ঘুরে দেখ',
'qbedit'         => 'সম্পাদনা কর',
'qbpageoptions'  => 'এ পাতার বিকল্পসমূহ',
'qbpageinfo'     => 'পাতা-সংক্রান্ত তথ্য',
'qbmyoptions'    => 'আমার পছন্দ',
'qbspecialpages' => 'বিশেষ পাতাসমূহ',
'moredotdotdot'  => 'আরও...',
'mypage'         => 'আমার পাতা',
'mytalk'         => 'আমার আলাপ',
'anontalk'       => 'এই বেনামী ব্যবহারকারীর আলাপের পাতা',
'navigation'     => 'পরিভ্রমন',

'errorpagetitle'    => 'ভুল',
'returnto'          => '$1 শিরোনামের পাতায় ফেরত যান।',
'tagline'           => '{{SITENAME}} থেকে',
'help'              => 'সহায়িকা',
'search'            => 'অনুসন্ধান',
'searchbutton'      => 'অনুসন্ধান',
'go'                => 'চলো',
'searcharticle'     => 'চলো',
'history'           => 'এ পাতার ইতিহাস',
'history_short'     => 'ইতিহাস',
'updatedmarker'     => 'আমার শেষ পরিদর্শনের পর থেকে হালনাগাদকৃত',
'info_short'        => 'তথ্য',
'printableversion'  => 'ছাপার যোগ্য সংস্করণ',
'permalink'         => 'স্থায়ী সংযোগ',
'print'             => 'ছাপাও',
'edit'              => 'সম্পাদনা করুন',
'editthispage'      => 'সম্পাদনা করুন',
'delete'            => 'মুছে ফেলুন',
'deletethispage'    => 'মুছে ফেলুন',
'undelete_short'    => 'পুনঃস্থাপন {{PLURAL:$1|১টি সম্পাদনা|$1টি সম্পাদনাসমূহ}}',
'protect'           => 'সুরক্ষিত করুন',
'protect_change'    => 'সুরক্ষা পরিবর্তন করুন',
'protectthispage'   => 'সংরক্ষণ করুন',
'unprotect'         => 'সুরক্ষা সরিয়ে নিন',
'unprotectthispage' => 'সুরক্ষা সরিয়ে নিন',
'newpage'           => 'নতুন পাতা',
'talkpage'          => 'আলোচনা করুন',
'talkpagelinktext'  => 'আলোচনা',
'specialpage'       => 'বিশেষ পাতা',
'personaltools'     => 'ব্যক্তিগত হাতিয়ারসমূহ',
'postcomment'       => 'মন্তব্য করুন',
'articlepage'       => 'নিবন্ধ দেখুন',
'talk'              => 'আলোচনা',
'views'             => 'দৃষ্টিকোনসমূহ',
'toolbox'           => 'প্রয়োজনীয় সংযোগসমূহ',
'userpage'          => 'ব্যাবহারকারীর পাতা দেখুন',
'projectpage'       => 'মেটা-পাতা দেখুন',
'imagepage'         => 'ছবির পাতা দেখুন',
'mediawikipage'     => 'বার্তার পাতা দেখুন',
'templatepage'      => 'টেম্পলেট পাতা দেখুন',
'viewhelppage'      => 'সহায়িকা পাতা দেখুন',
'categorypage'      => 'বিষয়শ্রেণীর পাতাটি দেখুন',
'viewtalkpage'      => 'আলোচনা দেখুন',
'otherlanguages'    => 'অন্যান্য ভাষাসমূহ',
'redirectedfrom'    => '($1 থেকে ঘুরে এসেছে)',
'redirectpagesub'   => 'পুনর্নির্দেশ পাতা',
'lastmodifiedat'    => 'এ পাতায় শেষ পরিবর্তন হয়েছিল $2টার সময়, $1 তারিখে।', # $1 date, $2 time
'viewcount'         => 'এ পাতাটি $1 বার দেখা হয়েছে।',
'protectedpage'     => 'সুরক্ষিত পাতা',
'jumpto'            => 'ঝাঁপ দাও:',
'jumptonavigation'  => 'পরিভ্রমন',
'jumptosearch'      => 'অনুসন্ধান',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => '{{SITENAME}} বৃত্তান্ত',
'aboutpage'         => 'Project:বৃত্তান্ত',
'bugreports'        => 'ত্রুটি বিবরণী',
'bugreportspage'    => 'Project:ত্রুটি বিবরণী',
'copyright'         => '$1 এর আওতায় প্রাপ্য।',
'copyrightpagename' => '{{SITENAME}} কপিরাইট',
'copyrightpage'     => '{{ns:project}}:কপিরাইটসমূহ',
'currentevents'     => 'সমসাময়িক ঘটনা',
'currentevents-url' => 'সমসাময়িক ঘটনাসমূহ',
'disclaimers'       => 'দাবিত্যাগ',
'disclaimerpage'    => 'Project:সাধারণ দাবিত্যাগ',
'edithelp'          => 'সম্পাদনা সহায়িকা',
'edithelppage'      => 'Help:কিভাবে একটি পাতা সম্পাদনা করবেন',
'faq'               => 'সম্ভাব্য প্রশ্নসমূহ',
'faqpage'           => 'Project:সম্ভাব্য প্রশ্নসমূহ',
'helppage'          => 'Help:সহায়িকা',
'mainpage'          => 'প্রধান পাতা',
'policy-url'        => 'Project:নীতি',
'portal'            => 'সম্প্রদায়ের প্রবেশদ্বার',
'portal-url'        => 'Project:সম্প্রদায়ের প্রবেশদ্বার',
'privacy'           => 'গোপনীয়তার নীতি',
'privacypage'       => 'Project:গোপনীয়তার নীতি',
'sitesupport'       => 'দান করুন',
'sitesupport-url'   => 'Project:দান করুন',

'badaccess'        => 'অনুমোদন ত্রুটি',
'badaccess-group0' => 'আপনি যে কাজের জন্য অনুরোধ করেছেন, যে কাজটি সম্পন্ন করার অনুমতি নাই',
'badaccess-group1' => 'আপনার অনুরোধকৃত কাজের করার অনুমতি শুধু $1 গ্রুপের ব্যবহারকারীদেরই আছে।',

'versionrequired' => 'মিডিয়াউইকির $1 সংস্করণ প্রয়োজন',

'ok'                      => 'ঠিক আছে',
'retrievedfrom'           => "'$1' থেকে আনীত",
'youhavenewmessages'      => 'আপনার $1 ($2) এসেছে৷',
'newmessageslink'         => 'নতুন বার্তা',
'newmessagesdifflink'     => 'সর্বশেষ পরিবর্তন',
'youhavenewmessagesmulti' => 'আপনার $1টি নতুন বার্তা এসেছে',
'editsection'             => 'সম্পাদনা',
'editold'                 => 'সম্পাদনা করুন',
'editsectionhint'         => 'পরিচ্ছেদ সম্পাদনা: $1',
'toc'                     => 'পরিচ্ছেদসমূহ',
'showtoc'                 => 'দেখাও',
'hidetoc'                 => 'আড়ালে রাখো',
'restorelink'             => '{{PLURAL:$1|একটি মুছে ফেলা সম্পাদনা|$1 মুছে ফেলা সম্পাদনাসমূহ}}',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'নিবন্ধ',
'nstab-user'      => 'ব্যবহারকারীর পাতা',
'nstab-media'     => 'মিডিয়া পাতা',
'nstab-special'   => 'বিশেষ পাতা',
'nstab-project'   => 'প্রকল্প পাতা',
'nstab-image'     => 'ফাইল',
'nstab-mediawiki' => 'বার্তা',
'nstab-template'  => 'টেম্পলেট',
'nstab-help'      => 'সহায়িকা',
'nstab-category'  => 'বিষয়শ্রেণী',

# Main script and global functions
'nosuchaction'      => 'এমন কোন কাজ নেই',
'nosuchactiontext'  => 'ইউআরএল টি দ্বারা নির্দিষ্ট কাজ এই উইকি শনাক্ত করতে পারেনি',
'nosuchspecialpage' => 'এমন কোন বিশেষ পাতা নেই',

# General errors
'error'                => 'ত্রুটি',
'databaseerror'        => 'ডাটাবেস ত্রুটি',
'noconnect'            => 'দুঃখিত! এই উইকিতে কিছু কারিগরি সমস্যা দেখা দিয়েছে, এবং ডাটাবেসের সাথে যোগাযোগ করতে পারছে না। <br />
$1',
'nodb'                 => 'ডাটাবেস $1 নির্বাচন করা যায়নি',
'laggedslavemode'      => 'সতর্কীকরণ: পাতাটি সম্ভবত সাম্প্রতি হালনাগাদকৃত নয়।',
'readonly'             => 'ডাটাবেসের ব্যবহার সীমাবদ্ধ',
'enterlockreason'      => 'তালাবদ্ধ করার কারণ কি তা বলুন, সাথে কখন তালা খুলবেন তার আনুমানিক সময় উল্লখ্য করুন',
'internalerror'        => 'আভ্যন্তরীণ ত্রুটি',
'internalerror_info'   => 'আভ্যন্তরীণ ত্রুটি: $1',
'filecopyerror'        => '"$1" থেকে "$2" ফাইল কপি করা যায়নি',
'filerenameerror'      => '"$1" ফাইলটির নাম বদলে "$2" করা সম্ভব হচ্ছে না।',
'filedeleteerror'      => '"$1" ফাইলটি মুছে ফেলা সম্ভব হচ্ছে না।',
'directorycreateerror' => '"$1" ডাইরেক্টরি তৈরি করা যায়নি।',
'filenotfound'         => '"$1" ফাইলটি খুঁজে পাওয়া যাচ্ছে না।',
'fileexistserror'      => '"$1" ফাইলে লেখা যাচ্ছে না: ফাইলটি আগেই আছে',
'unexpected'           => 'অপ্রত্যাশিত মান: "$1"="$2"।',
'formerror'            => 'ত্রুটি: ফরমটি জমা দেওয়া যায়নি',
'badarticleerror'      => 'এই পাতায় এই কাজটি করা সম্ভব নয়।',
'cannotdelete'         => 'এই পাতা বা ফাইলটি মোছা সম্ভব হল না। (সম্ভবতঃ অন্য কেউ আগেই এটিকে মুছে ফেলেছে)',
'badtitle'             => 'শিরোনামটি গ্রহনযোগ্য নয়।',
'perfcached'           => 'নিচের উপাত্তগুলো ক্যাশ থেকে নেয়া এবং সম্পূর্ণ হালনাগাদকৃত না-ও হতে পারে:',
'perfcachedts'         => 'নিচের উপাত্তগুলো ক্যাশ থেকে নেয়া এবং $1 তারিখে হালনাগাদ করা হয়েছে।',
'viewsource'           => 'উৎস দেখুন',
'viewsourcefor'        => '$1 এর জন্য',
'protectedpagetext'    => 'সম্পাদনা এড়াতে এ পাতাটির ব্যবহার নিয়ন্ত্রণ করা হয়েছে।',
'viewsourcetext'       => 'এ পাতাটি আপনি দেখতে এবং উৎসের অনুলিপি নিতে পারবেন:',
'protectedinterface'   => 'এই পাতার বিষয়বস্তু উইকি সফটওয়্যারের একটি ইন্টারফেস বার্তা প্রদান করে, তাই এটিকে সুরক্ষিত করে রাখা হয়েছে।',
'namespaceprotected'   => "'''$1''' নামস্থানে কোন পাতা আপনার সম্পাদনা করার অনুমতি নাই।",
'customcssjsprotected' => 'আপনার এ পাতা সম্পাদনা করার অনুমতি নাই, কারণ এ পাতায় অন্য ব্যবহারকারীর ব্যক্তিগত বিষয়বস্তু আছে।',
'ns-specialprotected'  => '{{ns:special}} নামস্থানে পাতাসমূহ সম্পাদনা করা যাবে না।',

# Login and logout pages
'logouttitle'                => 'ব্যবহারকারীর প্রস্থান (logout)',
'logouttext'                 => '<strong>আপনি এইমাত্র আপনার একাউন্ট থেকে বেরিয়ে গেছেন।</strong><br />
এ পরিস্থিতিতে আপনি বেনামে {{SITENAME}} ব্যবহার করতে পারেন, কিংবা আবার আপনার একাউন্টে (বা নতুন কোন একাউন্টে) প্রবেশ করতে পারেন। লক্ষ্য করুন যে {{SITENAME}} এর কিছু কিছু পাতা এখনও এমনভাবে পরিবেশিত হতে পারে যাতে মনে হবে আপনি এখনও আপনার একাউন্ট থেকে বেরিয়ে যান নি। এক্ষেত্রে আপনাকে আপনার ব্রাওজারের ক্যাশ পরিষ্কার (clear browser cache) করে নিতে হবে।',
'welcomecreation'            => '== স্বাগতম $1! ==

আপনার অ্যাকাউন্ট তৈরী হয়েছে। আপনার {{SITENAME}} পছন্দ স্থির করে নিতে ভুলবেন না কিন্তু।',
'loginpagetitle'             => 'ব্যবহারকারী লগ ইন',
'yourname'                   => 'ব্যবহারকারীর নাম (Username)',
'yourpassword'               => 'শব্দচাবি (Password)',
'yourpasswordagain'          => 'শব্দচাবিটি (password) আবার লিখুন',
'remembermypassword'         => 'আমাকে পরবর্তীতে মনে রাখো',
'yourdomainname'             => 'আপনার ডোমেইন',
'loginproblem'               => '<b>আপনার লগ্‌-ইন করতে সমস্যা হচ্ছে।</b><br />আবার চেষ্টা করুন!',
'login'                      => 'প্রবেশ করুন',
'loginprompt'                => '{{SITENAME}}-তে সংযুক্ত হতে চাইলে আপনার ব্রাওজারের কুকি (cookies) অবশ্যই সক্রিয় (enabled) করতে হবে|',
'userlogin'                  => 'প্রবেশ/নতুন অ্যাকাউন্ট',
'logout'                     => 'প্রস্থান করুন',
'userlogout'                 => 'প্রস্থান',
'notloggedin'                => 'আপনি সংযুক্ত নন',
'nologin'                    => 'প্রবেশাধিকার নেই? $1।',
'nologinlink'                => 'নতুন অ্যাকাউন্ট খুলুন',
'createaccount'              => 'নতুন অ্যাকাউন্ট খুলুন',
'gotaccount'                 => 'আপনার কি ইতিমধ্যে একটি অ্যাকাউন্ট তৈরি করা আছে? $1 করুন।',
'gotaccountlink'             => 'প্রবেশ',
'createaccountmail'          => 'ই-মেইলের মাধ্যমে',
'badretype'                  => 'শব্দচাবি (password) দুটি মিলছেনা।',
'userexists'                 => 'এই ব্যবহারকারী নামটি অন্য কেঊ আগেই ব্যবহার করেছে। দয়া করে অন্য নাম বেছে নিন।',
'youremail'                  => 'ইমেইল *',
'username'                   => 'ব্যবহারকারীর নাম:',
'uid'                        => 'ব্যবহারকারী নং (ID):',
'yourrealname'               => 'আসল নাম *',
'yourlanguage'               => 'ভাষা:',
'yourvariant'                => 'বিকল্প',
'yournick'                   => 'ডাক নাম:',
'badsiglength'               => 'ডাকনামটি অনেক লম্বা; যা অবশ্যই $1 অক্ষরের কম হতে হবে।',
'email'                      => 'ই-মেইল',
'loginerror'                 => 'লগ-ইন করতে সমস্যা হয়েছে',
'prefs-help-email'           => '* ই-মেইল (ঐচ্ছিক): এটি দেয়া থাকলে অন্যরা আপনার ব্যবহারকারী পাতার মাধ্যমে আপনার সাথে যোগাযোগ করতে পারবে।  সেজন্য আপনার পরিচয় তাদের জানা থাকা লাগবেনা।',
'nocookieslogin'             => '{{SITENAME}} এ কুকি (cookies) এর মাধ্যমে ব্যবহারকারীদের লগ-ইন সম্পন্ন করা হয়। আপনার ব্রাঊজারে কুকি বন্ধ করে দেওয়া আছে। কুকি চালু করে আবার চেষ্টা করুন।',
'noname'                     => 'আপনি সঠিক ব্যবহারকারী নাম নির্দিষ্ট করেননি।',
'loginsuccesstitle'          => 'প্রবেশ সফল',
'loginsuccess'               => "'''আপনি এইমাত্র \"\$1\" নামে {{SITENAME}}-তে প্রবেশ করেছেন।'''",
'nosuchuser'                 => '"$1" নামের কোন ব্যবহারকারী নেই। নামের বানান পরীক্ষা করুন, অথবা নতুন অ্যাকাউন্ট তৈরি করুন।',
'nosuchusershort'            => '"$1" নামের কোন ব্যবহারকারী নেই। নামের বানান পরীক্ষা করুন।',
'nouserspecified'            => 'আপনাকে অবশ্যই ব্যবহারকারী নাম নির্দিষ্ট করতে হবে।',
'wrongpassword'              => 'আপনি ভুল শব্দচাবি (password) ব্যবহার করেছেন। অনুগ্রহ করে আবার চেষ্টা করুন।',
'wrongpasswordempty'         => 'শব্দচাবি (password) প্রবেশের ঘরটি খালি ছিল। দয়াকরে আবার চেষ্টা করুন।',
'passwordtooshort'           => 'আপনার শব্দচাবি সঠিক নয় অথবা অনেক ছোট। শব্দচাবি অবশ্যই অন্তত $1 অক্ষরের এবং ব্যবহারকারী নামের থেকে পৃথক হতে হবে।',
'mailmypassword'             => 'নতুন শব্দচাবি আমার ইমেইলে পাঠাও',
'passwordremindertitle'      => '{{SITENAME}} থেকে শব্দচাবি মনে রাখো',
'noemail'                    => '"$1" ব্যবহারকারীর জন্য কোন ই-মেইল ঠিকানা সংরক্ষিত নাই।',
'passwordsent'               => 'একটি নতুন শব্দচাবি "$1" ব্যবহারকারীর ই-মেইল ঠিকানায় পাঠানো হয়েছে। দয়াকরে তা পাওয়ার পর আবার লগ্‌-ইন করুন।',
'mailerror'                  => 'ইমেইল পাঠাতে সমস্যা: $1',
'acct_creation_throttle_hit' => 'দুঃখিত, আপনি ইতিমধ্যে $1টি অ্যাকাউন্ট তৈরী করেছেন৷ এর বেশী আপনি তৈরী করতে পারবেন না৷',
'emailauthenticated'         => 'আপনার ই-মেইল ঠিকানাটি $1 তারিখে নিশ্চিত করা হয়েছে।',
'emailnotauthenticated'      => 'আপনার ই-মেইলের ঠিকানা <strong>এখনও যাচাই করা হয়নি</strong>। নিচের বৈশিষ্ট্যগুলোর (features) জন্য কোনো ই-মেইল পাঠানো হবে না।',
'noemailprefs'               => 'এই বৈশিষ্টটি কাজ করাতে হলে একটি ই-মেইল ঠিকানা নির্ধারণ করতে হবে।',
'emailconfirmlink'           => 'আপনার ই-মেইলের ঠিকানা নিশ্চিত করুন',
'accountcreated'             => 'অ্যাকাউন্ট তৈরি করা হয়েছে',
'accountcreatedtext'         => '$1 এর জন্য ব্যবহারকারী অ্যাকাউন্ট তৈরি করা হয়েছে।',
'loginlanguagelabel'         => 'ভাষা: $1',

# Password reset dialog
'resetpass'               => 'অ্যাকাউন্টের শব্দচাবি নতুন (reset) করে দাও',
'resetpass_announce'      => 'আপন ই-মেইলকৃত সংকেত দ্বারা লগ-ইন আছেন। লগ-ইন পদ্ধতি সম্পূর্ণ করতে আপনাকে অবশ্যই একটি নতুন শব্দচাবি গ্রহণ করতে হবে:',
'resetpass_text'          => '<!-- এখানে লেখা যোগ করুন -->',
'resetpass_header'        => 'নতুন (reset) শব্দচাবি দাও',
'resetpass_submit'        => 'শব্দচাবি দাও এবং লগ্‌-ইন করো',
'resetpass_success'       => 'আপনার শব্দচাবি সাফল্যের সাথে পরিবর্তীত হয়েছে! এখন আপনি তে লগ-ইন হচ্ছেন...',
'resetpass_bad_temporary' => 'অস্থায়ী শব্দচাবিটি ভুল। আপনি হয়তো ইতিমধ্যে সফলভাবে শব্দচাবি পরিবর্তন করেছেন অথবা নতুন অস্থায়ী শব্দচাবির জন্য অনুরোধ করেছেন।',
'resetpass_forbidden'     => 'এই উইকিতে শব্দচাবি পরিবর্তন করা যাবে না',

# Edit page toolbar
'bold_sample'     => 'গাঢ় লেখা',
'bold_tip'        => 'গাঢ় লেখা',
'italic_sample'   => 'তীর্যক লেখা',
'italic_tip'      => 'তীর্যক লেখা',
'link_sample'     => 'সংযোগের শিরোনাম',
'link_tip'        => 'আভ্যন্তরীণ সংযোগ',
'extlink_sample'  => 'http://www.example.com সংযোগ শিরোনাম',
'extlink_tip'     => 'বহিঃসংযোগ (মনে রাখবেন http:// উপসর্গ)',
'headline_sample' => 'শিরোনাম',
'headline_tip'    => '২য় স্তরের শিরোনাম',
'math_sample'     => 'সূত্র এখানে লিখুন',
'math_tip'        => 'গাণিতিক সূত্র (LaTeX)',

# Edit pages
'summary'                  => 'সম্পাদনা সারাংশ',
'subject'                  => 'বিষয়/শিরোনাম',
'minoredit'                => 'অনুল্লেখ্য',
'watchthis'                => 'নজরে রাখুন',
'savearticle'              => 'সংরক্ষণ',
'preview'                  => 'প্রাকদর্শন',
'showpreview'              => 'প্রাকদর্শন',
'showlivepreview'          => 'তাৎক্ষণিক প্রাকদর্শন',
'showdiff'                 => 'পরিবর্তনসমূহ',
'anoneditwarning'          => 'আপনি লগ ইন করেননি। এই পাতার সম্পাদনার ইতিহাসে আপনার আইপি সংখ্যা সংরক্ষিত হবে।',
'missingsummary'           => "'''খেয়াল করুন''':  আপনি কিন্তু সম্পাদনার সারাংশ দেননি। আবার যদি \"সংরক্ষণ\" বোতামে ক্লিক করেন, তাহলে ঐ সারাংশ বাক্যটি ছাড়াই আপনার সম্পাদনা সংরক্ষিত হবে।",
'missingcommenttext'       => 'দয়াকরে নিচে মন্তব্য যোগ করুন।',
'missingcommentheader'     => "'''মনে রাখবেন:''' আপনি এই মন্তব্যের জন্য কোন বিষয়/শিরোনাম দেননি। আপনি যদি সংরক্ষণ বোতাম ক্লিক করেন, তাহলে আপনার সম্পাদনা কোন বিষয়/শিরোনাম ছাড়াই সংরক্ষিত হবে।",
'summary-preview'          => 'সারাংশ প্রাকদর্শন',
'subject-preview'          => 'বিষয়/শিরোনাম প্রাকদর্শন',
'blockedtitle'             => 'ব্যবহারকারীকে বাধা দেয়া হয়েছে',
'blockedtext'              => "আপনার ব্যবহারকারী নাম অথবা আইপি ঠিকানাকে $1 বাধা দিয়েছেন। 
এর কারণ হিসেবে বলা হয়েছেঃ:<br />''$2''

আপনি  $1 অথবা [[{{MediaWiki:Grouppage-sysop}}|প্রশাসকদের]] মধ্যে অন্য কারো সাথে এই বাধাদান সংক্রান্ত বিষয়ে আলোচনা করতে পারেন।

বিশেষ দ্রস্টব্যঃ আপনার ই-মেইল ঠিকানা যদি [[Special:Preferences|আপনার পছন্দ তালিকাতে]] লিপিবদ্ধ করা না থাকে, তাহলে আপনি {{SITENAME}} হতে অন্য ব্যবহারকারীকে ই-মেইল করতে পারবেন না।

আপনার আইপি ঠিকানা হল $3। দয়া করে যে কোন যোগাযোগের সময় এই ঠিকানাটি উল্লেখ করবেন।",
'blockedoriginalsource'    => "'''$1''' এর উৎস নিচে দেখানো হল:",
'blockededitsource'        => "'''$1''' এ '''আপনার সম্পাদনা''' করা লেখাগুলো নিচে দেখানো হল:",
'whitelistedittitle'       => 'সম্পাদনা করতে লগ্‌-ইন করতে হবে',
'whitelistedittext'        => 'পাতায় সম্পাদনা করতে আপশ্যই $1 করতে হবে।',
'whitelistreadtitle'       => 'পড়ার জন্য লগ্‌-ইন করতে হবে',
'whitelistreadtext'        => 'পাতাসমূহ পড়তে আপনাকে অবশই [[Special:Userlogin|লগ্‌-ইন]] করতে হবে।',
'whitelistacctitle'        => 'আপনার অ্যাকাউন্ট তৈরি করার অনুমতি নাই',
'confirmedittitle'         => 'সম্পাদনা করার জন্য ই-মেইল নিশ্চিতকরণ প্রয়োজন',
'confirmedittext'          => 'কোন সম্পাদনা করার আগে আপনার ই-মেইল ঠিকানাটি অবশ্যই নিশ্চিত করতে হবে। দয়া করে আপনার ই-মেইল ঠিকানাটি [[special:Preferences|ব্যবহারকারীর পছন্দতালিকায়]] ঠিকমত দিন।',
'nosuchsectiontitle'       => 'এমন কোন অনুচ্ছেদ নাই',
'loginreqtitle'            => 'লগ্‌-ইন প্রয়োজন',
'loginreqlink'             => 'লগ-ইন',
'loginreqpagetext'         => 'অন্যান্য পাতা দেখতে হলে আপনাকে অবশ্যই লগ-ইন করতে হবে।',
'accmailtitle'             => 'শব্দচাবি পাঠানো হয়েছে৷',
'accmailtext'              => '"$1"-এর শব্দচাবি(password) $2-এর কাছে পাঠানো হয়েছে৷',
'newarticle'               => '(নতুন)',
'newarticletext'           => 'এই নিবন্ধটি এখনো {{SITENAME}} এ সংযুক্ত হয়নি। আপনি চাইলে নীচের বক্সে বিষয়টি নিয়ে কিছু লিখে ও রক্ষা করে এই নিবন্ধটি শুরু করতে পারেন । যদি ভুলবশত এখানে এসে থাকেন তাহলে ব্রাউজারের ব্যাক বোতামে ক্লিক করে আগের পাতায় ফিরে যান।',
'clearyourcache'           => "'''লক্ষ্য করুন:''' আপনার পছন্দগুলো রক্ষা করার পর পরিবর্তনগুলো দেখার জন্য আপনাকে ব্রাউজারের ক্যাশ এড়াতে হতে পারে। '''মোজিলা/ফায়ারফক্স/সাফারি:''' শিফট কী চেপে ধরে রিলোড-এ ক্লিক করুন, কিংবা ''কন্ট্রোল-শিফট-R''(এপল ম্যাক-এ ''কমান্ড-শিফট-R'') একসাথে চাপুন; '''ইন্টারনেট এক্সপ্লোরার:''' ''কন্ট্রোল'' চেপে ধরে রিফ্রেশ-এ ক্লিক করুন, কিংবা ''কন্ট্রোল-F5'' চাপুন; '''কংকারার:''' কেবল রিলোড ক্লিক করলেই বা F5 চাপলেই চলবে; '''অপেরা''' ব্যবহারকারীদেরকে ''Tools→Preferences''-এ গিয়ে কাশ সম্পূর্ণ পরিষ্কার করে নিতে হতে পারে।",
'updated'                  => '(হালনাগাদ)',
'note'                     => '<strong>নোট:</strong>',
'previewnote'              => '<strong>এটি প্রাকদর্শন মাত্র। কোনো পরিবর্তন এখনও সংরক্ষণ করা হয়নি!</strong>',
'session_fail_preview'     => '<strong>দুঃখিত! সেশন ডাটা হারিয়ে যাওয়ার কারণে আপনার সম্পাদনাটি সংরক্ষণ করা সম্ভব হয়নি। দয়া করে লেখাটি আবার জমা দেয়ার চেষ্টা করুন। যদি এতেও কাজ না হয়, তবে অ্যাকাউন্ট থেকে বেরিয়ে গিয়ে আবার অ্যাকাউন্টে প্রবেশ করে চেষ্টা করুন।</strong>',
'editing'                  => 'সম্পাদনা করছেন: $1',
'editinguser'              => 'ব্যবহারকারী সম্পাদনা করছেন <b>$1</b>',
'editingsection'           => 'সম্পাদনা করছেন $1 (অনুচ্ছেদ)',
'editingcomment'           => 'সম্পাদনা করছেন $1 (মন্তব্য)',
'editconflict'             => 'সম্পাদনা দ্বন্দ্ব: $1',
'yourtext'                 => 'আপনার লেখা বিষয়বস্তু',
'storedversion'            => 'সংরক্ষিত সংস্করণ',
'yourdiff'                 => 'পার্থক্য',
'copyrightwarning2'        => 'দয়া করে লক্ষ্য করুন: {{SITENAME}}-এর এই ভুক্তিতে আপনার লেখা বা অবদান অন্যান্য ব্যবহারকারীরা পরিবর্তন বা পরিবর্ধন করতে, এমনকি মুছে ফেলতে পারবেন। {{SITENAME}} এ আপনার সকল লেখালেখি/অবদান গনু ফ্রি ডকুমেন্টেশনের ($1) আওতায় বিনামূল্যে প্রাপ্য ও হস্তান্তরযোগ্য। আপনার জমা দেয়া লেখা যে কেউ হৃদয়হীনভাবে সম্পাদনা করতে এবং যথেচ্ছভাবে ব্যবহার করতে পারেন। আপনি যদি এ ব্যাপারে একমত না হন, তাহলে এখানে আপনার লেখা জমা দেবেন না। আপনি আরো প্রতিজ্ঞা করছেন যে, এই লেখাগুলো আপনি নিজে লিখেছেন (তবে কোন মৌলিক গবেষণা নয়) বা সাধারণের ব্যবহারের জন্য উন্মুক্ত কোন উৎস থেকে সংগ্রহ করেছেন। <strong>স্বত্ব সংরক্ষিত কোন লেখা স্বত্বাধিকারীর অনুমতি ছাড়া এখানে জমা দেবেন না।</strong>',
'longpagewarning'          => '<strong>সতর্কীকরণ: এই পাতাটি $1 কিলোবাইট দীর্ঘ; কিছু ব্রাউজারে ৩২ কিলোবাইটের চেয়ে দীর্ঘ পাতা সম্পাদনা করতে সমস্যা হতে পারে।
অনুগ্রহ করে পাতাটিকে একাধিক ক্ষুদ্রতর অংশে ভাগ করার চেষ্টা করুন।</strong>',
'semiprotectedpagewarning' => "'''নোট:''' এই পাতাটির ব্যবহার নিয়ন্ত্রণ করা হয়েছে তাই নিবন্ধনকৃত ব্যবহারকারী এটি সম্পাদনা করতে পারবেন।",
'templatesused'            => 'এই পাতায় ব্যবহৃত টেম্পলেট:',
'templatesusedpreview'     => 'এই প্রাকদর্শনে ব্যবহৃত টেম্পলেটসমূহ:',
'templatesusedsection'     => 'এই অনুচ্ছেদে ব্যবহৃত টেম্পলেটসমূহ:',
'template-protected'       => '(সুরক্ষিত)',
'template-semiprotected'   => '(অর্ধ-সুরক্ষিত)',
'nocreatetitle'            => 'পাতা তৈরি নিয়ন্ত্রণ করা হয়েছে',
'nocreate-loggedin'        => 'এই উইকিতে আপনার নতুন পাতা তৈরি করার অনুমতি নাই।',
'permissionserrors'        => 'অনুমতি ত্রুটিসমূহ',

# "Undo" feature
'undo-failure' => 'এ সম্পাদনা মধ্যবর্তী সম্পাদনাসমূহের কারণে পূর্বাবস্থায় ফিরিয়ে নেওয়া যাবে না।',

# Account creation failure
'cantcreateaccounttitle' => 'অ্যাকাউন্ট তৈরি করা যাবে না',

# History pages
'revhistory'          => 'সংশোধনের ইতিহাস',
'viewpagelogs'        => 'এই পাতার জন্য লগ্‌গুলো দেখুন',
'nohistory'           => 'এই পাতার কোন সম্পাদনার ইতিহাস নাই।',
'revnotfound'         => 'সংশোধন খুজে পাওয়া যাচ্ছে না',
'loadhist'            => 'পাতার ইতিহাস লোড হচ্ছে',
'currentrev'          => 'বর্তমান সংশোধন',
'revisionasof'        => '$1 তারিখের সংশোধন',
'previousrevision'    => '←পুর্বের সংস্করণ',
'nextrevision'        => 'পরবর্তী সংস্করণ→',
'currentrevisionlink' => 'বর্তমান সংশোধন',
'cur'                 => 'বর্তমান',
'next'                => 'পরবর্তী',
'last'                => 'পূর্ববর্তী',
'orig'                => 'মূল',
'page_first'          => 'প্রথম',
'page_last'           => 'শেষ',
'histlegend'          => 'পার্থক্য (Diff) নির্বাচন: যে সংস্করণগুলো তুলনা করতে চান, সেগুলো চিহ্নিত করে এন্টার বা নিচের বোতামটি টিপুন।<br />
নির্দেশিকা: (বর্তমান) = বর্তমান সংস্করণের সাথে পার্থক্য,(পূর্ববর্তী) =  পূর্বের সংস্করণের সাথে পার্থক্য, অ = অনুল্লেখ্য সম্পাদনা।',
'deletedrev'          => '[অবলুপ্ত]',
'histfirst'           => 'সবচেয়ে পুরনো',
'histlast'            => 'সাম্প্রতিক',
'historysize'         => '($1 বাইট)',
'historyempty'        => '(খালি)',

# Revision feed
'history-feed-title'       => 'সংশোধন ইতিহাস',
'history-feed-description' => 'এ উইকিতে এই পাতার সংশোধনের ইতিহাস',

# Revision deletion
'rev-deleted-comment'       => '(মন্তব্য সরিয়ে নেওয়া হয়েছে)',
'rev-deleted-user'          => '(ব্যবহারকারীর নাম সরিয়ে নেওয়া হয়েছে)',
'rev-deleted-event'         => '(ভুক্তি সরিয়ে নেওয়া হয়েছে)',
'rev-delundel'              => 'দেখাও/আড়াল করো',
'revisiondelete'            => 'অবলুপ্ত/পুনঃস্থাপন সংশোধনসমূহ',
'revdelete-nooldid-title'   => 'কোন লক্ষ্য সংশোধন নাই',
'revdelete-legend'          => 'সীমাবদ্ধ করো:',
'revdelete-hide-text'       => 'সংশোধিত লেখা আড়াল করো',
'revdelete-hide-name'       => 'কাজ এবং লক্ষ্য আড়াল করো',
'revdelete-hide-comment'    => 'সম্পাদনা মন্তব্য আড়াল করো',
'revdelete-hide-user'       => 'সম্পাদকে ব্যবহারকারীর নাম/আইপি আড়াল করো',
'revdelete-hide-restricted' => 'এই সীমাবদ্ধতা প্রশাসক সহ সবার ক্ষেত্রে প্রয়োগ করো',
'revdelete-hide-image'      => 'ফাইলের বিষয়বস্তু আড়াল করো',
'revdelete-unsuppress'      => 'সংশোধন পুনঃস্থাপনের উপর সীমাবদ্ধতা দূর করো',
'revdelete-log'             => 'লগ্‌ মন্তব্য:',
'revdelete-submit'          => 'নির্বাচিত সংশোধনে প্রয়োগ করো',

# Diffs
'history-title'             => '"$1" এর সংশোধনের ইতিহাস',
'difference'                => '(সংশোধনগুলোর মধ্যে পার্থক্য)',
'lineno'                    => '$1 নং লাইন:',
'editcurrent'               => 'এই পাতার বর্তমান সংস্করণ সম্পাদনা করো',
'selectnewerversionfordiff' => 'পার্থক্য করার জন্য একটি নতুন সংস্করণ নির্বাচন করুন',
'selectolderversionfordiff' => 'পার্থক্য করার জন্য একটি পুরাতন সংস্করণ নির্বাচন করুন',
'compareselectedversions'   => 'নির্বাচিত সংস্করণগুলো তুলনা করো',

# Search results
'searchresults'         => 'অনুসন্ধানের ফলাফল',
'searchresulttext'      => '{{SITENAME}} এ অনুসন্ধানের ব্যাপারে আরও তথ্যের জন্য [[{{MediaWiki:Helppage}}|{{int:help}}]] দেখুন।',
'searchsubtitle'        => "আপনি অনুসন্ধান করেছেন '''[[:$1]]'''",
'searchsubtitleinvalid' => "আপনি অনুসন্ধান করেছেন '''$1'''",
'noexactmatch'          => "'''\"\$1\" শিরোনামের কোন পাতা নেই।''' আপনি [[:\$1|পাতাটি সৃষ্টি করতে পারেন]]।",
'titlematches'          => 'নিবন্ধের শিরোনাম মিলেছে',
'notitlematches'        => 'কোন পাতার শিরোনামের সাথে মিলে নাই',
'textmatches'           => 'পাতার লেখার সাথে মিলেছে',
'notextmatches'         => 'কোন পাতার লেখার সাথে মিলে নাই',
'prevn'                 => 'পূর্ববর্তী $1টি',
'nextn'                 => 'পরবর্তী $1টি',
'viewprevnext'          => '($1) ($2) ($3) দেখুও।',
'showingresults'        => 'নিচে <b>$2</b> নং থেকে শুরু করে প্রথম <b>$1</b>টি ফলাফল দেখানো হল।',
'showingresultsnum'     => "নিম্নে {{PLURAL:$3|'''1''' ফলাফল|'''$3''' ফলাফলসমূহ}} দেখানো হয়েছে যা শুরু হয়েছে #'''$2''' দিয়ে।",
'powersearch'           => 'অনুসন্ধান করো',

# Preferences page
'preferences'           => 'আমার পছন্দ',
'mypreferences'         => 'আমার পছন্দ',
'prefs-edits'           => 'সম্পাদনার সংখ্যা:',
'prefsnologin'          => 'আপনি লগ-ইন করেননি',
'prefsnologintext'      => 'ব্যবহারকারীর পছন্দ ঠিক করতে আপনাকে অবশ্যই [[Special:Userlogin|লগ্‌-ইন]] থাকতে হবে।',
'qbsettings-none'       => 'কিছুই না',
'changepassword'        => 'শব্দচাবি (password) পরিবর্তন',
'skin'                  => 'আবরণ (Skin)',
'math'                  => 'গণিত',
'datetime'              => 'তারিখ ও সময়',
'math_unknown_error'    => 'অজানা ত্রুটি',
'math_unknown_function' => 'অজানা ফাংশন',
'prefs-personal'        => 'ব্যবহারকারীর প্রোফাইল',
'prefs-rc'              => 'সাম্প্রতিক পরিবর্তনসমূহ',
'prefs-watchlist'       => 'নজর তালিকা',
'prefs-watchlist-days'  => 'সর্বোচ্চ দিনের নজর তালিকা দেখানোর জন্য:',
'prefs-watchlist-edits' => 'সম্প্রসারিত নজর তালিকায় সর্বোচ্চ সংখ্যার পরিবর্তন দেখানোর জন্য:',
'prefs-misc'            => 'বিবিধ',
'saveprefs'             => 'সংরক্ষণ করো',
'resetprefs'            => 'আবার শুরু করো',
'oldpassword'           => 'পুরনো শব্দচাবি',
'newpassword'           => 'নতুন শব্দচাবি:',
'retypenew'             => 'নতুন শব্দচাবি আবার টাইপ করুন:',
'textboxsize'           => 'সম্পাদনা',
'rows'                  => 'সারি:',
'columns'               => 'কলাম:',
'searchresultshead'     => 'অনুসন্ধান',
'resultsperpage'        => 'প্রতি পাতায় হিট:',
'contextlines'          => 'লাইন প্রতি হিটে:',
'recentchangesdays'     => 'সাম্প্রতিক পরিবর্তনে দিনসমূহ দেখানোর জন্য:',
'recentchangescount'    => 'সাম্প্রতিক পরিবর্তনে প্রদর্শিত সম্পাদনার সংখ্যা:',
'savedprefs'            => 'আপনার পছন্দগুলো সংরক্ষণ করা হয়েছে।',
'timezonelegend'        => 'সময় বলয়',
'timezonetext'          => 'আপনার স্থানীয় সময় থেকে সার্ভারের সময়ের (UTC) পার্থক্য (ঘন্টায়)।',
'localtime'             => 'স্থানীয় সময়',
'timezoneoffset'        => 'সময়পার্থক্য¹',
'servertime'            => 'সার্ভারের সময়',
'guesstimezone'         => 'ব্রাউজার থেকে পূরণ করো',
'allowemail'            => 'অন্য ব্যবহারকারীদেরকে আপনাকে ই-মেইল পাঠানোর অনুমতি দিন।',
'files'                 => 'ফাইল',

# User rights
'userrights-user-editname'    => 'ব্যবহারকারীর নাম লিখুন:',
'editusergroup'               => 'ব্যবহারকারীর দল সম্পাদনা করো',
'userrights-editusergroup'    => 'ব্যবহারকারীর দল সম্পাদনা করো',
'saveusergroups'              => 'ব্যবহারকারীর দল সংরক্ষণ করো',
'userrights-groupsmember'     => 'সদস্য:',
'userrights-groupsavailable'  => 'বিদ্যমান দলসমূহ:',
'userrights-reason'           => 'পরিবর্তনের কারণ:',
'userrights-available-add'    => 'আপনি $1 এ ব্যবহারকারী যোগ করতে পারবেন।',
'userrights-available-remove' => 'আপনি $1 থেকে ব্যবহারকারী বাদ দিতে পারবেন।',

# Groups
'group-bot'        => 'বট',
'group-sysop'      => 'প্রশাসক',
'group-bureaucrat' => 'নীতি নির্ধারক',
'group-all'        => '(সমস্ত)',

'group-bot-member'        => 'বট',
'group-sysop-member'      => 'প্রশাসক',
'group-bureaucrat-member' => 'নীতি নির্ধারক',

# Recent changes
'nchanges'                          => '$1 টি পরিবর্তন',
'recentchanges'                     => 'সাম্প্রতিক পরিবর্তনসমূহ',
'rcnote'                            => 'বিগত <strong>$2</strong> দিনে সংঘটিত <strong>$1</strong>টি পরিবর্তন নীচে দেখানো হল (যেখানে বর্তমান সময় ও তারিখ $3)।',
'rcnotefrom'                        => '<b>$2</b>-এর পরে সংঘটিত পরিবর্তনগুলো নিচে দেখানো হল (<b>$1</b>টি)।',
'rclistfrom'                        => '$1-এর পর সংঘটিত নতুন পরিবর্তনগুলো দেখাও।',
'rcshowhideminor'                   => 'অনুল্লেখ্য পরিবর্তনগুলো $1',
'rcshowhidebots'                    => 'বটগুলো $1',
'rcshowhideliu'                     => 'প্রবেশ করেছেন এমন ব্যবহারকারীদের $1',
'rcshowhideanons'                   => 'বেনামী ব্যবহারকারীদের $1',
'rcshowhidepatr'                    => '$1 পরীক্ষিত সম্পাদনা',
'rcshowhidemine'                    => 'আমার সম্পাদনাগুলো $1',
'rclinks'                           => "'''প্রদর্শনের ধরন'''<br />
* বিগত ($2) দিনের শেষ ($1)টি পরিবর্তন দেখাও
* $3",
'diff'                              => 'পরিবর্তন',
'hist'                              => 'ইতিহাস',
'hide'                              => 'দেখিও না',
'show'                              => 'দেখাও',
'minoreditletter'                   => 'অ',
'newpageletter'                     => 'ন',
'boteditletter'                     => 'ব',
'number_of_watching_users_pageview' => '[$1 জন নজরকারী]',
'rc_categories_any'                 => 'যেকোনো',
'newsectionsummary'                 => '/* $1 */ নতুন অনুচ্ছেদ',

# Recent changes linked
'recentchangeslinked' => 'সম্পর্কিত পরিবর্তন',

# Upload
'upload'            => 'আপলোড',
'uploadbtn'         => 'ফাইল আপলোড করুন',
'uploadnologin'     => 'আপনি লগ-ইন করেননি।',
'uploadnologintext' => 'আপলোড করতে হলে আপনাকে অবশ্যই আগে [[Special:Userlogin|লগ-ইন]] করতে হবে।',
'uploaderror'       => 'আপলোড এ সমস্যা হয়েছে',
'uploadtext'        => "ফাইল আপলোড করতে নিচের ফরমটি ব্যবহার করুন। পূর্বে আপলোড করা ফাইল দেখতে বা খুঁজতে হলে [[Special:Imagelist|পূর্বে আপলোড করা ফাইল এর তালিকা]] দেখুন। আপলোড করা ফাইল এর নাম  [[Special:Log/upload|আপলোডের ইতিহাস তালিকায়]] যোগ হয়ে থাকে।

কোনো নিবন্ধে ছবি যোগ করতে হলে নিচের উদাহরণ অনুযায়ী সংযোগ দিনঃ
'''<nowiki>[[{{ns:image}}:file.jpg]]</nowiki>''',
'''<nowiki>[[{{ns:image}}:file.png|alt text]]</nowiki>''' অথবা
'''<nowiki>[[{{ns:media}}:file.ogg]]</nowiki>'''",
'uploadlog'         => 'আপলোড এর ইতিহাস তালিকা',
'uploadlogpage'     => 'আপলোড লগ',
'uploadlogpagetext' => 'নিচে সবচেয়ে সম্প্রতি আপলোড করা ফাইলগুলোর তালিকা দেখুন।',
'filedesc'          => 'সারাংশ',
'fileuploadsummary' => 'সারাংশ:',
'filesource'        => 'উৎস',
'badfilename'       => 'ফাইলের নামটি পরিবর্তন করে $1" করা হয়েছে।',
'successfulupload'  => 'আপলোড সফল হয়েছে',
'uploadedimage'     => '"[[$1]]" আপলোড করা হয়েছে।',
'uploadvirus'       => 'এই ফাইলটিতে ভাইরাস আছে! ব্যাখ্যা: $1',
'sourcefilename'    => 'উৎস ফাইলের নাম',
'destfilename'      => 'লক্ষ্য ফাইলের নাম',

'license' => 'লাইসেন্সকরণ',

# Image list
'imagelist'                 => 'ছবির তালিকা',
'imagelisttext'             => 'নিচে $1টি ফাইলের একটি তালিকা $2 সাজিয়ে দেখানো হল।',
'ilsubmit'                  => 'অনুসন্ধান করো',
'byname'                    => 'নাম অনুযায়ী',
'bydate'                    => 'তারিখ অনুযায়ী',
'bysize'                    => 'আকার অনুযায়ী',
'imagelinks'                => 'সংযোগসমূহ',
'linkstoimage'              => 'নিচের পাতা(গুলো) থেকে এই ছবিতে সংযোগ আছে:',
'shareduploadwiki'          => 'বিস্তারিত তথ্যের জন্য $1 দেখুন।',
'noimage'                   => 'এই নামে কোনো ফাইল নেই, আপনি যা করতে পারেন, তা হলো $1।',
'noimage-linktext'          => 'এই ফাইলটিকে আপলোড করুন।',
'uploadnewversion-linktext' => 'এই ফাইলটির একটি নতুন সংস্করণ আপলোড করুন',

# MIME search
'download' => 'ডাউনলোড',

# Random page
'randompage' => 'অজানা যেকোনো পাতা',

# Statistics
'statistics'             => 'পরিসংখ্যান',
'sitestats'              => 'সাইট পরিসংখ্যান',
'userstats'              => 'ব্যবহারকারীর পরিসংখ্যান',
'sitestatstext'          => "ডাটাবেসে সর্বমোট {{PLURAL:\$1|'''1''' টি|'''\$1''' গুলো}} পাতা আছে।এগুলোর মধ্যে রয়েছে \"আলাপ\" পাতাগুলো, {{SITENAME}} বিষয়ক পাতাগুলো, '''অসম্পূর্ণ''' (stub) পাতাগুলো, পুনর্নিদেশগুলো, এবং অন্যান্য আরও পাতা যেগুলোতে সম্ভবত বিষয়বস্তুর অভাব রয়েছে। এগুলো বাদে এ উইকিতে সম্ভবত {{PLURAL:\$2|'''1''' টি|'''\$2''' গুলো}} পাতা আছে যেগুলোতে যথেষ্ট পরিমাণ বিষয়বস্তু সংযোজিত হয়েছে। '''\$8'''টি ফাইল আপলোড করা হয়েছে। এ {{SITENAME}} স্থাপন করার পর থেকে মোট '''\$3''' বার দেখা হয়েছে, এবং '''\$4''' বার সম্পাদনা করা হয়েছে। তাতে প্রতি পাতা গড়ে '''\$5''' বার সম্পাদিত এবং প্রতি সম্পাদনায় '''\$6''' বার দেখা হয়েছে।

[http://meta.wikimedia.org/wiki/Help:Job_queue কাজের সারির] দৈর্ঘ্য '''\$7'''।",
'userstatstext'          => "'''$1''' জন নিবন্ধিত ব্যবহারকারী আছেন। এঁদের মধ্যে '''$2''' (বা '''$4%''') জন প্রশাসক ($3 দেখুন)।",
'statistics-mostpopular' => 'সবচেয়ে বেশী বার দেখা পাতাসমূহ',

'disambiguations'     => 'দ্ব্যর্থতা-দূরীকরণ পাতাসমূহ',
'disambiguationspage' => 'Template:দ্ব্যর্থতা_নিরসন',

'brokenredirects' => 'অকার্যকর পুনর্নির্দেশনাসমূহ',

'withoutinterwiki'        => 'ভাষার সংযোগহীন পাতাসমূহ',
'withoutinterwiki-header' => 'এই পাতা সমূহ অন্য ভাষার সংস্করণের সাথে সংযুক্ত নয়:',

# Miscellaneous special pages
'nbytes'                  => '$1 বাইট',
'ncategories'             => '$1 টি বিষয়শ্রেণী',
'nlinks'                  => '$1টি সংযোগ',
'nmembers'                => '$1টি নিবন্ধ',
'nrevisions'              => '$1 বার সম্পাদিত',
'uncategorizedpages'      => 'যেসব পাতা শ্রেণীকরণ করা হয়নি',
'uncategorizedcategories' => 'যেসব বিষয়শ্রেণীর শ্রেণীকরণ প্রয়োজন',
'uncategorizedimages'     => 'যেসব চিত্রের শ্রেণীকরণ প্রয়োজন',
'unusedcategories'        => 'অব্যবহৃত বিষয়শ্রেণীসমূহ',
'unusedimages'            => 'অব্যবহৃত ফাইলসমূহ',
'popularpages'            => 'জনপ্রিয় পাতাসমূহ',
'mostcategories'          => 'সবচেয়ে বেশী বিষয়শ্রেণী-সমৃদ্ধ নিবন্ধসমূহ',
'mostrevisions'           => 'সবচেয়ে বেশী বার সম্পাদিত নিবন্ধসমূহ',
'allpages'                => 'সব পাতা',
'shortpages'              => 'সংক্ষিপ্ত পাতাসমূহ',
'longpages'               => 'দীর্ঘ পাতাসমূহ',
'deadendpages'            => 'যেসব পাতা থেকে কোনো সংযোগ নেই',
'protectedpages'          => 'সুরক্ষিত পাতাসমূহ',
'listusers'               => 'ব্যবহারকারীর তালিকা',
'specialpages'            => 'বিশেষ পাতাসমূহ',
'spheading'               => 'বিশেষ পাতাসমূহ',
'rclsub'                  => '(যে সব পাতায় "$1" থেকে সংযোগ আছে)',
'newpages'                => 'নতুন পাতাসমূহ',
'ancientpages'            => 'পুরানো নিবন্ধ',
'move'                    => 'সরিয়ে ফেলুন',
'movethispage'            => 'সরিয়ে ফেলুন',
'unusedcategoriestext'    => 'নিচের বিষয়শ্রেণীগুলোর অস্তিত্ব আছে, যদিও কোনো নিবন্ধ বা অন্য কোনো বিষয়শ্রেণী এগুলোকে ব্যবহার করে না।',

# Book sources
'booksources' => 'বইয়ের উৎস',

'categoriespagetext' => 'এ উইকিতে বর্তমান বিষয়শ্রেণীসমূহ:',
'data'               => 'উপাত্ত (Data)',
'alphaindexline'     => '$1 হতে $2',
'version'            => 'সংস্করণ',

# Special:Log
'specialloguserlabel'  => 'ব্যবহারকারী:',
'speciallogtitlelabel' => 'শিরোনাম:',

# Special:Allpages
'nextpage'          => 'পরবর্তী পাতা ($1)',
'prevpage'          => 'পূর্ববর্তী পাতা ($1)',
'allpagesfrom'      => 'এই অক্ষর দিয়ে শুরু হওয়া পাতাগুলো দেখাও:',
'allarticles'       => 'সমস্ত নিবন্ধ',
'allinnamespace'    => 'সমস্ত পাতা ($1 নামস্থান)',
'allnotinnamespace' => 'সমস্ত পাতা ($1 নামস্থান ব্যতিত)',
'allpagesprev'      => 'পূর্ববর্তী',
'allpagesnext'      => 'পরবর্তী',
'allpagessubmit'    => 'চলো',
'allpagesprefix'    => 'এই উপসর্গবিশিষ্ট পাতাগুলো দেখাও:',

# E-mail user
'mailnologintext' => "অন্য ব্যবহারকারীদেরকে ই-মেইল পাঠাতে হলে আপনাকে অবশ্যই আগে [[Special:Userlogin|লগ-ইন]] করতে হবে এবং ''[[Special:Preferences|আপনার পছন্দ তালিকায়]] আপনার ই-মেইল ঠিকানাটি ঠিকমত দিতে হবে।",
'emailuser'       => 'ইমেইল করো',
'emailpage'       => 'ব্যবহারকারীকে ই-মেইল করুন',
'emailto'         => 'প্রাপক',
'emailsubject'    => 'বিষয়',
'emailmessage'    => 'বার্তা',
'emailsend'       => 'প্রেরণ করো',
'emailsent'       => 'ই-মেইল প্রেরণ করা হয়েছে',
'emailsenttext'   => 'আপনার ই-মেইল বার্তা প্রেরণ করা হয়েছে।',

# Watchlist
'watchlist'            => 'আমার নজর তালিকা',
'mywatchlist'          => 'আমার নজর তালিকা',
'watchlistfor'         => "('''$1''' এর জন্য)",
'watchnologin'         => 'আপনি এখনও লগ-ইন করেননি।',
'watchnologintext'     => 'আপনার নজর তালিকা পরিবর্তনের জন্য আপনাকে অবশ্যই অ্যাকাউন্টে [[Special:Userlogin|প্রবেশ করতে হবে]]।',
'addedwatch'           => 'নজর তালিকায় যুক্ত হয়েছে',
'addedwatchtext'       => "\"\$1\" পাতাটি আপনার [[Special:Watchlist|নজরতালিকা]]-তে যোগ করা হয়েছে৷

ভবিষ্যতে এই পাতা ও এই পাতার সাথে সম্পর্কিত আলোচনা পাতায় সংঘটিত যাবতীয় পরিবর্তন এখানে তালিকাভুক্ত হবে৷ 
এছাড়া [[Special:Recentchanges|সাম্প্রতিক পরিবর্তনসমূহ]]তালিকায় এই পাতাটিকে '''গাঢ়''' অক্ষরে দেখানো হবে যাতে আপনি সহজেই পাতাটি শনাক্ত করতে পারেন৷

পরবর্তীতে আপনি যদি পাতাটিকে আপনার নজরতালিকা থেকে সরিয়ে ফেলতে চান, তবে \"নজর সরিয়ে নিন\" ট্যাবটিতে ক্লিক করবেন৷",
'removedwatch'         => 'নজর তালিকা থেকে সরিয়ে ফেলা হয়েছে',
'removedwatchtext'     => '"$1" পাতাটি আপনার নজরতালিকা থেকে সরিয়ে ফেলা হয়েছে৷',
'watch'                => 'নজরে রাখুন',
'watchthispage'        => 'নজরে রাখুন',
'unwatch'              => 'নজর সরিয়ে নিন',
'unwatchthispage'      => 'নজর সরিয়ে নিন',
'watchnochange'        => 'প্রদর্শিত সময়সীমার মধ্যে আপনার নজরতালিকায় রাখা কোন পাতায় কোন রকম সম্পাদনা ঘটেনি।',
'wlheader-enotif'      => '* ই-মেইল এর মাধমে নির্দেশনার ব্যবস্থা চালু করা আছে।',
'watchlistcontains'    => 'আপনার নজরতালিকায় $1 টি পাতা রয়েছে।',
'wlshowlast'           => 'দেখাও সর্বশেষ  $1 ঘন্টা $2 দিন $3',
'watchlist-show-bots'  => 'বট দ্বারা সম্পাদনাগুলো দেখাও',
'watchlist-hide-bots'  => 'বট দ্বারা সম্পাদনাগুলো দেখিও না',
'watchlist-show-own'   => 'আমার সম্পাদনাগুলো দেখাও',
'watchlist-hide-own'   => 'আমার সম্পাদনাগুলো দেখিও না',
'watchlist-show-minor' => 'অনুল্লেখ্য সম্পাদনাগুলো দেখাও',
'watchlist-hide-minor' => 'অনুল্লেখ্য সম্পাদনাগুলো দেখিও না',

'changed' => 'পরিবর্তিত',
'created' => 'তৈরী হয়েছিল',

# Delete/protect/revert
'confirm'                     => 'নিশ্চিত করুন',
'excontent'                   => "বিষয়বস্তু ছিল: '$1'",
'excontentauthor'             => "বিষয়বস্তু ছিল: '$1' (এবং একমাত্র অবদানকারী ছিলেন '$2')",
'exbeforeblank'               => "মুছে ফেলার আগে বিষয়বস্তু ছিল: '$1'",
'exblank'                     => 'পাতাটি খালি ছিল',
'confirmdelete'               => 'মোছা সুনিশ্চিত করুন।',
'deletesub'                   => '("$1" মুছে ফেলা হচ্ছে)',
'actioncomplete'              => 'কাজটি নিষ্পন্ন হয়েছে',
'deletedtext'                 => '"$1" মুছে ফেলা হয়েছে। সাম্প্রতিক মুছে ফেলার ঘটনাগুলো $2-এ দেখুন।',
'deletedarticle'              => '"[[$1]]" মুছে ফেলা হয়েছে।',
'dellogpage'                  => 'পাতা অবলুপ্তি লগ্',
'dellogpagetext'              => 'নিচে সবচেয়ে সাম্প্রতিক অবলুপ্তিগুলোর একাটি তালিকা দেওয়া হল।',
'deletionlog'                 => 'পাতা অবলুপ্তি লগ্',
'reverted'                    => 'পূর্ববর্তী সংস্করণে ফিরে যাওয়া সফল হয়েছে।',
'deletecomment'               => 'মুছে ফেলার কারণ',
'rollback'                    => 'সম্পাদনা ফিরিয়ে নিন',
'rollback_short'              => 'ফিরিয়ে নিন',
'cantrollback'                => 'পূর্বের সংস্করণে ফেরত যাওয়া সম্ভব হল না, সর্বশেষ সম্পাদনাকারী এই নিবন্ধটির একমাত্র লেখক।',
'revertpage'                  => '[[Special:Contributions/$2|$2]] ([[User_talk:$2|আলাপ]]) এর সম্পাদিত সংস্করণ হতে [[User:$1|$1]] এর সম্পাদিত সর্বশেষ সংস্করণে ফেরত যাওয়া হয়েছে।',
'protectlogpage'              => 'সুরক্ষা লগ্‌',
'protectedarticle'            => 'সুরক্ষিত "[[$1]]"',
'unprotectedarticle'          => '"[[$1]]"-এর সুরক্ষা সরিয়ে নেওয়া হয়েছে',
'protectsub'                  => '("$1" সুরক্ষিত করা হচ্ছে)',
'confirmprotect'              => 'সুরক্ষা নিশ্চিত করুন',
'protectcomment'              => 'সুরক্ষার কারণ',
'unprotectsub'                => '("$1"-এর সুরক্ষা সরিয়ে নিচ্ছি)',
'protect-level-autoconfirmed' => 'বেনামী ব্যবহারকারীদের বাধা দাও',
'restriction-type'            => 'অনুমতি:',

# Restrictions (nouns)
'restriction-edit' => 'সম্পাদনা',
'restriction-move' => 'সরিয়ে নেওয়া',

# Undelete
'viewdeletedpage' => 'মুছে ফেলা হয়েছে, এমন পাতাগুলো দেখুন',

# Namespace form on various pages
'namespace'      => 'নামস্থান:',
'invert'         => 'বিপরীত নির্বাচন',
'blanknamespace' => '(প্রধান)',

# Contributions
'contributions' => 'ব্যবহারকারীর অবদান',
'mycontris'     => 'আমার অবদান',
'ucnote'        => 'এই ব্যবহারকারীর গত <b>$2</b>  দিনের সর্বশেষ <b>$1</b> টি সম্পাদনা নিচে দেখুন।',
'uclinks'       => 'বিগত $1 টি পরিবর্তন দেখুন; গত  $2 দিনের পরিবর্তন দেখুন।',

'sp-contributions-newest'   => 'সাম্প্রতিকতম',
'sp-contributions-oldest'   => 'প্রাচীনতম',
'sp-contributions-newer'    => 'সাম্প্রতিকতর $1',
'sp-contributions-older'    => 'পুর্বতন $1',
'sp-contributions-newbies'  => 'শুধু নতুন অ্যাকাউন্টের অবদানসমূহ দেখাও',
'sp-contributions-search'   => 'অবদানসমূহের জন্য অনুসন্ধান',
'sp-contributions-username' => 'আইপি (IP) ঠিকানা অথবা ব্যবহারকারীর নাম:',
'sp-contributions-submit'   => 'অনুসন্ধান',

'sp-newimages-showfrom' => '$1 হতে শুরু করে নতুন ছবিগুলো দেখাও',

# What links here
'whatlinkshere' => 'সংযোগকারী পাতাসমূহ',
'linklistsub'   => '(সংযোগসমূহের তালিকা)',
'linkshere'     => 'নিচের পাতা(গুলো) থেকে এই পাতায় সংযোগ আছে:',
'nolinkshere'   => 'কোনো পাতা থেকে এখানে সংযোগ নেই।',

# Block/unblock
'blockip'            => 'ব্যবহারকারীকে বাধা দাও',
'badipaddress'       => 'আইপি (IP) ঠিকানাটি অগ্রহনযোগ্য',
'blockipsuccesssub'  => 'বাধা সফল',
'blockipsuccesstext' => '[[Special:Contributions/$1|$1]] কে বাধা দেয়া হয়েছে
<br />বাধা দেয়া পুনর্বিবেচনা করতে হলে [[Special:Ipblocklist|বাধা দেয়া আইপি ঠিকানার তালিকা]] দেখুন।',
'ipblocklist'        => 'নিষিদ্ধ ঘোষিত আইপি ঠিকানা ও ব্যবহারকারী নামের তালিকা',
'blocklistline'      => '$1 তারিখে  $2,  $3 ($4) কে বাধা দিয়েছেন।',
'expiringblock'      => 'শেষ হবে $1',
'blocklink'          => 'বাধা দাও',
'contribslink'       => 'অবদান',
'blocklogpage'       => 'বাধা দানের লগ্‌',
'blocklogentry'      => '"[[$1]]"-কে $2 মেয়াদের জন্য বাধা দেওয়া হয়েছে।',

# Move page
'movepage'         => 'পাতাটি সরিয়ে ফেলুন',
'movearticle'      => 'যে পাতা সরিয়ে ফেলা হবে',
'movenologintext'  => 'কোন পাতা সরিয়ে ফেলতে চাইলে আপনাকে অবশ্যই একজন নিবন্ধিত ব্যবহারকারী হতে হবে ও অ্যাকাউন্টে [[Special:Userlogin|প্রবেশ]] করতে হবে।',
'newtitle'         => 'এই নতুন শিরোনামে',
'move-watch'       => 'এই পাতাটি নজরে রাখুন',
'pagemovedsub'     => 'সরিয়ে নেওয়া হয়েছে',
'articleexists'    => 'হয় এই শিরোনামের একটি নিবন্ধ ইতোমধ্যে সৃষ্টি হযে গেছে, অথবা আপনি যে শিরোনামটি পছন্দ করেছেন তা গ্রহণযোগ্য নয়। দয়া করে অন্য একটি শিরোনাম দিয়ে চেষ্টা করুন।',
'movetalk'         => 'সংশ্লিষ্ট আলাপের পাতা সরিয়ে নাও',
'talkpagemoved'    => 'সংশ্লিষ্ট আলাপের পাতাকেও সরানো হয়েছে।',
'talkpagenotmoved' => 'সংশ্লিষ্ট আলাপের পাতাকে <strong>সরিয়ে নেওয়া হয়নি</strong>।',
'1movedto2'        => '[[$1]]-কে [[$2]]-এ সরিয়ে নেওয়া হয়েছে',
'1movedto2_redir'  => '[[$1]]-কে [[$2]]-তে পুনর্নির্দেশনার সাহায্যে সরিয়ে নেওয়া হয়েছে',
'movelogpage'      => 'পাতা স্থানান্তর লগ্',
'movereason'       => 'কারণ',

# Namespace 8 related
'allmessages'         => 'সিস্টেম বার্তাসমূহ',
'allmessagesname'     => 'নাম',
'allmessagescurrent'  => 'বর্তমান ভাষা',
'allmessagestext'     => 'নিচে মিডিয়াউইকি: নামস্থানে অন্তর্ভুক্ত সিস্টেম বার্তাগুলোর তালিকা দেওয়া হল।',
'allmessagesmodified' => 'শুধু পরিবর্তিত অংশগুলো দেখাও',

# Thumbnails
'thumbnail-more' => 'বড় করো',

# Tooltip help for the actions
'tooltip-pt-userpage'    => 'আমার ব্যবহারকারী পাতা',
'tooltip-pt-mytalk'      => 'আমার আলাপের পাতা',
'tooltip-pt-preferences' => 'আমার পছন্দ',
'tooltip-pt-mycontris'   => 'আমার অবদানের তালিকা',
'tooltip-pt-logout'      => 'প্রস্থান',
'tooltip-ca-addsection'  => 'এই আলোচনায় একটি মন্তব্য যোগ করো।',
'tooltip-ca-protect'     => 'এই পাতাকে সুরক্ষিত করো',
'tooltip-ca-delete'      => 'পাতাটি মুছে ফেলো',
'tooltip-ca-move'        => 'এই পাতাকে সরিয়ে ফেলো',
'tooltip-ca-watch'       => 'এই পাতাটিকে আপনার নজর তালিকায় যোগ করুন',
'tooltip-ca-unwatch'     => 'এই পাতাটি আপনার নজর তালিকা থেকে সরিয়ে ফেলুন',
'tooltip-search'         => 'অনুসন্ধান {{SITENAME}}',
'tooltip-p-logo'         => 'প্রধান পাতা',
'tooltip-t-print'        => 'এ পাতার ছাপানোর উপযোগী সংস্করণ',
'tooltip-watch'          => 'এই পাতাটি আমার নজর তালিকায় যোগ করো',
'tooltip-upload'         => 'আপলোড শুরু করো',

# Attribution
'anonymous'        => '{{SITENAME}} এর বেনামী ব্যবহারকারীবৃন্দ',
'siteuser'         => '{{SITENAME}} ব্যবহারকারী $1',
'lastmodifiedatby' => 'এই পাতাটিতে শেষ পরিবর্তন হয়েছিল $2, $1 by $3।', # $1 date, $2 time, $3 user
'and'              => 'এবং',
'others'           => 'অন্যান্য',

# Spam protection
'subcategorycount'     => 'এই বিষয়শ্রেণীতে $1 টি উপবিষয়শ্রেণী রয়েছে।',
'categoryarticlecount' => 'এই বিষয়শ্রেণীতে $1টি নিবন্ধ রয়েছে।',

# Info page
'infosubtitle'   => 'পাতার তথ্য',
'numedits'       => 'সম্পাদনার সংখ্যা (পাতা): $1',
'numtalkedits'   => 'সম্পাদনার সংখ্যা (আলাপের পাতা): $1',
'numwatchers'    => 'নজরকারীর সংখ্যা: $1',
'numauthors'     => 'পৃথক (নিবন্ধ) লেখকের সংখ্যা: $1',
'numtalkauthors' => 'পৃথক লেখকের সংখ্যা(আলাপের পাতা): $1',

# Math options
'mw_math_png'    => 'সবসময় পিএনজি (PNG) দেখাও',
'mw_math_simple' => 'খুব সরল হলে এইচটিএমএল (HTML), নতুবা পিএনজি (PNG)',
'mw_math_html'   => 'সম্ভব হলে এইচটিএমএল (HTML), নতুবা পিএনজি (PNG)',
'mw_math_source' => 'টেক (TeX) আকারে রেখে দাও (টেক্সট ব্রাউজারগুলোর জন্য)',
'mw_math_modern' => 'আধুনিক ব্রাউজারগুলোর জন্য সুপারিশকৃত',
'mw_math_mathml' => 'সম্ভব হলে ম্যাথএমএল (MathML) (পরীক্ষামূলক)',

# Patrolling
'markaspatrolleddiff'                 => 'পরীক্ষিত বলে চিহ্নিত করুন',
'markaspatrolledtext'                 => 'এই নিবন্ধটিকে পরীক্ষিত বলে চিহ্নিত করুন',
'markedaspatrolled'                   => 'পরীক্ষিত বলে চিহ্নিত করুন',
'markedaspatrolledtext'               => 'আপনার নির্বাচিত সংস্করণ পরীক্ষিত বলে চিহ্নিত করা হয়েছে।',
'markedaspatrollederror'              => 'পরীক্ষিত বলে চিহ্নিত করা যাবে না',
'markedaspatrollederrortext'          => 'পরীক্ষিত বলে চিহ্নিত করতে আপনাকে একটি সংস্করণ নির্দিষ্ট  করতে হবে।',
'markedaspatrollederror-noautopatrol' => 'আপনার নিজের পাতাকে পরীক্ষিত বলে চিহ্নিত করার অনুমতি আপনার নাই।',

# Patrol log
'patrol-log-page' => 'পরীক্ষণ লগ',
'patrol-log-line' => '$2 গুলোর $1 কে পরীক্ষিত বলে চিহ্নিত করা হয়েছে $3',
'patrol-log-auto' => '(স্বয়ংক্রিয়)',

# Image deletion
'deletedrevision'                 => 'মুছে ফেলা পুরাতন সংশোধন $1',
'filedeleteerror-long'            => 'ফাইলটি মুছার সময় ত্রুটি দেখা দিয়েছে:

$1',
'filedelete-missing'              => 'ফাইল "$1" মুছে ফেলা যাবে না, কারণ ফাইলটি ডাটাবেজে নেই।',
'filedelete-old-unregistered'     => 'নির্ধারিত ফাইলের সংশোধন "$1" ডাটাবেজে নেই।',
'filedelete-current-unregistered' => 'নির্ধারিত ফাইল "$1" ডাটাবেজে নেই।',

# Browsing diffs
'previousdiff' => '← পূর্বের পার্থক্য',
'nextdiff'     => 'পরবর্তী পার্থক্য →',

# Media information
'widthheightpage'      => '$1×$2, $3 পাতাসমূহ',
'file-info'            => '(ফাইলের আকার: $1, MIME ধরণ: $2)',
'file-info-size'       => '($1 × $2 pixel, ফাইলের আকার: $3, MIME ধরণ: $4)',
'file-nohires'         => '<small>বেশি রেজুলেশন বিদ্যমান নয়।</small>',
'svg-long-desc'        => '(SVG ফাইল, সাধারণত $1 × $2 pixels, ফাইলের আকার: $3)',
'show-big-image'       => 'পূর্ণ রেজুলেশন',
'show-big-image-thumb' => '<small>আকারের প্রাকদর্শন: $1 × $2 pixels</small>',

# Special:Newimages
'newimages'    => 'নতুন ফাইলের গ্যালারি',
'showhidebots' => '($1 বট)',
'noimages'     => 'দেখার মত কিছই নাই।',

# Metadata
'metadata-expand'   => 'সম্প্রসারিত সবিস্তারে দেখাও',
'metadata-collapse' => 'সম্প্রসারিত সবিস্তারে দেখিও না',

# EXIF tags
'exif-imagewidth'       => 'চওড়া',
'exif-imagelength'      => 'লম্বা',
'exif-datetime'         => 'ফাইল পরিবর্তনের তারিখ ও সময়',
'exif-imagedescription' => 'ছবির শিরোনাম',
'exif-make'             => 'ক্যামেরার তৈরিকারক',
'exif-model'            => 'ক্যামেরা মডেল',
'exif-software'         => 'ব্যবহৃত সফটওয়্যার',
'exif-artist'           => 'স্রষ্টা',
'exif-makernote'        => 'প্রস্তুতকারকের নোট',
'exif-usercomment'      => 'ব্যবহারকারীর মন্তব্য',
'exif-lightsource'      => 'বাতির উৎস',

'exif-unknowndate' => 'অজানা তারিখ',

'exif-orientation-1' => 'সাধারণ', # 0th row: top; 0th column: left

'exif-componentsconfiguration-0' => 'বিদ্যমান নয়',

'exif-exposureprogram-0' => 'অসংজ্ঞায়িত',

'exif-subjectdistance-value' => '$1 মিটার',

'exif-meteringmode-0'   => 'অজানা',
'exif-meteringmode-1'   => 'গড়',
'exif-meteringmode-6'   => 'আংশিক',
'exif-meteringmode-255' => 'অন্য',

'exif-lightsource-0'  => 'অজানা',
'exif-lightsource-1'  => 'দিনের আলো',
'exif-lightsource-9'  => 'চমৎকার আবহাওয়া',
'exif-lightsource-10' => 'মেঘাচ্ছন্ন আবহাওয়া',

'exif-focalplaneresolutionunit-2' => 'ইঞ্চি',

'exif-sensingmethod-1' => 'অসংজ্ঞায়িত',

'exif-gaincontrol-0' => 'কিছুই না',

'exif-contrast-0' => 'সাধারণ',
'exif-contrast-1' => 'নরম',
'exif-contrast-2' => 'কঠিন',

'exif-saturation-0' => 'সাধারণ',

'exif-sharpness-0' => 'সাধারণ',
'exif-sharpness-1' => 'নরম',
'exif-sharpness-2' => 'কঠিন',

'exif-subjectdistancerange-0' => 'অজানা',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'কিলোমিটার প্রতি ঘন্টা',
'exif-gpsspeed-m' => 'মাইল প্রতি ঘন্টা',

# External editor support
'edit-externally' => 'ফাইলটি অন্য কোন সফটওয়্যার দিয়ে সম্পাদনা করুন',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'সমস্ত',
'imagelistall'     => 'সমস্ত',
'watchlistall2'    => 'সবগুলো',
'namespacesall'    => 'সমস্ত',
'monthsall'        => 'সমস্ত',

# E-mail address confirmation
'confirmemail'            => 'ই-মেইলের ঠিকানা নিশ্চিত করুন',
'confirmemail_send'       => 'নিশ্চিতকরণ ই-মেইল /কোড পাঠাও।',
'confirmemail_sent'       => 'নিশ্চিতকরণ ই-মেইল পাঠানো হয়েছে।',
'confirmemail_sendfailed' => 'নিশ্চিতকরণ ই-মেইলটি পাঠানো সম্ভব হলো না। ইমেইল ঠিকানাটি ঠিকভাবে লিখেছেন কিনা, সেটি যাচাই করে দেখুন।',
'confirmemail_invalid'    => 'নিশ্চিতকরণের কোডটি সঠিক নয়। সম্ভবতঃ এটি পুরানো হয়ে গেছে।',
'confirmemail_needlogin'  => 'আপনার ই-মেইল ঠিকানা নিশ্চিত করতে আপনার $1 প্রয়োজন।',
'confirmemail_success'    => 'আপনার ই-মেইল ঠিকানাটি নিশ্চিত করা হয়েছে। আপনি এখন লগ-ইন করতে পারেন।',
'confirmemail_loggedin'   => 'আপনার ই-মেইল ঠিকানাটি নিশ্চিত করা হয়েছে।',
'confirmemail_error'      => 'আপনার নিশ্চিতকরণ সংরক্ষণ করতে হয়তো কিছু সমস্যা হয়েছিল',
'confirmemail_subject'    => '{{SITENAME}} ই-মেইল ঠিকানা নিশ্চিতকরণ',

# Scary transclusion
'scarytranscludefailed'  => '[$1 এর জন্য টেম্পলেট আনা অসফল হয়েছে; দুঃখিত]',
'scarytranscludetoolong' => '[URL টি বেশ লম্বা; দুঃখিত]',

# Trackbacks
'trackbackremove' => ' ([$1 অবলুপ্ত])',

# Delete conflict
'deletedwhileediting' => 'সতর্কীকরণ: আপনি পাতাটি সম্পাদনা শুরু করার পর পাতাটিকে মুছে ফেলা হয়েছে!',
'recreate'            => 'পুনরায় তৈরি করো',

# HTML dump
'redirectingto' => '[[$1]] পাতায় পুনঃনির্দেশিত হচ্ছে...',

# action=purge
'confirm_purge'        => 'এই পাতার ক্যাশে পরিষ্কার করতে চান?

$1',
'confirm_purge_button' => 'ঠিক আছে',

# AJAX search
'searchcontaining' => "''$1'' আছে এমন নিবন্ধগুলো অনুসন্ধান করো।",
'searchnamed'      => "''$1'' শিরোনামের পাতা অনুসন্ধান করো।",
'articletitles'    => "যেসব পাতা ''$1'' দিয়ে শুরু হয়েছে, তাদের তালিকা",
'hideresults'      => 'ফলাফলগুলো দেখিও না',

# Multipage image navigation
'imgmultipageprev' => '← পূর্ববর্তী পাতা',
'imgmultipagenext' => 'পরবর্তী পাতা →',
'imgmultigo'       => 'চলো!',
'imgmultigotopre'  => 'পাতায় চলো',

# Table pager
'ascending_abbrev'         => 'আরোহণ',
'descending_abbrev'        => 'অবতরণ',
'table_pager_next'         => 'পরবর্তী পাতা',
'table_pager_prev'         => 'পূর্ববর্তী পাতা',
'table_pager_first'        => 'প্রথম পাতা',
'table_pager_last'         => 'শেষ পাতা',
'table_pager_limit'        => 'প্রতি পাতায় $1 গুলো বিষয়বস্তু দেখাও',
'table_pager_limit_submit' => 'চলো',
'table_pager_empty'        => 'ফলাফল শূন্য',

# Auto-summaries
'autosumm-blank'   => 'পাতার সমস্ত বিষয়বস্তু মুছে ফেলা হল',
'autosumm-replace' => "পাতাকে '$1' দিয়ে প্রতিস্থাপিত করা হল",
'autoredircomment' => '[[$1]]-এ পুনর্নির্দেশ করা হল',
'autosumm-new'     => 'নতুন পাতা: $1',

# Size units
'size-bytes'     => '$1 বাইট',
'size-kilobytes' => '$1 কিলোবাইট',
'size-megabytes' => '$1 মেগাবাইট',
'size-gigabytes' => '$1 গিগাবাইট',

# Live preview
'livepreview-loading' => 'লোডিং…',
'livepreview-ready'   => 'লোডিং… প্রস্তুত!',
'livepreview-failed'  => 'তাৎক্ষণিক প্রাকদর্শন কাজ করছে না! সাধারণ প্রাকদর্শন চেষ্টা করুন।',
'livepreview-error'   => 'সংযোগ প্রদানে সম্ভব নয়: $1 "$2"। সাধারণ প্রাকদর্শন চেষ্টা করুণ।',

# Friendlier slave lag warnings
'lag-warn-normal' => '$1 সেকেন্ড আগের পরিবর্তন হয়তো তালিকায় দেখনো হয়নি।',

# Watchlist editor
'watchlistedit-numitems'      => 'আপনার নজর তালিকায় আলাপের পাতা ছাড়া {{PLURAL:$1|1 শিরোনাম|$1 শিরোনাম}} রয়েছে।',
'watchlistedit-noitems'       => 'আপনার নজর তালিকায় কোন পাতার শিরোনাম নাই।',
'watchlistedit-normal-title'  => 'নজর তালিকা সম্পাদনা করো',
'watchlistedit-normal-legend' => 'নজর তালিকা থেকে শিরোনামসমূহ মুছে ফেলো',
'watchlistedit-normal-submit' => 'শিরোনাম মুছে ফেলো',
'watchlistedit-normal-done'   => '{{PLURAL:$1|1 শিরোনাম|$1 শিরোনামসমূহ}} আপনার নজর তালিকা থেকে মুছে ফেলা হয়েছে:',
'watchlistedit-raw-title'     => 'অশোধিত নজর তালিকা সম্পাদনা করো',
'watchlistedit-raw-legend'    => 'অশোধিত নজর তালিকা সম্পাদনা করো',
'watchlistedit-raw-titles'    => 'শিরোনাম:',
'watchlistedit-raw-submit'    => 'নজর তালিকা হালনাগাদ করো',
'watchlistedit-raw-done'      => 'আপনার নজর তালিকা হালনাগাদ করা হয়েছে।',
'watchlistedit-raw-added'     => '{{PLURAL:$1|1 শিরোনাম|$1 শিরোনামসমূহ}} যোগ করা হয়েছে:',
'watchlistedit-raw-removed'   => '{{PLURAL:$1|1 শিরোনাম|$1 শিরোনামসমূহ}} মুছে ফেলা হয়েছে:',

# Watchlist editing tools
'watchlisttools-view' => 'সম্পর্কিত পরিবর্তনসমূহ দেখুন',
'watchlisttools-edit' => 'নজর তালিকা দেখুন এবং সম্পাদনা করুন',
'watchlisttools-raw'  => 'অশোধিত নজর তালিকা সম্পাদনা করো',

);

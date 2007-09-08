<?php
/** Bengali (বাংলা)
  *
  * @addtogroup Language
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
'tog-underline'            => 'সংযোগগুলির নিচে আন্ডারলাইন করুন:',
'tog-highlightbroken'      => 'ভাঙা সংযোগগুলোকে <a href="" class="new">এই ভাবে</a> দেখাও (নতুবা <a href="" class="internal">?</a> হিসেবে দেখাবে)।',
'tog-justify'              => 'অনুচ্ছেদগুলি জাস্টিফাই করা হোক',
'tog-hideminor'            => 'অনুল্লেখ্য সম্পাদনাগুলো দেখিও না',
'tog-extendwatchlist'      => 'সমস্ত প্রয়োগযোগ্য পরিবর্তন দেখানোর জন্য নজরতালিকা সম্প্রসারিত করা হোক।',
'tog-usenewrc'             => 'উন্নততর সাম্প্রতিক পরিবর্তনসমূহ (জাভাস্ক্রিপ্ট সংস্করণ)',
'tog-numberheadings'       => 'শিরোনামগুলোকে স্বয়ংক্রিয়ভাবে ক্রমাঙ্কিত করা হোক',
'tog-showtoolbar'          => 'সম্পাদনা টুলবার দেখানো হোক (জাভাস্ক্রিপ্ট)',
'tog-editondblclick'       => 'দুই ক্লিক করে পাতা সম্পাদনা করো (জাভা স্ক্রিপ্ট)',
'tog-editsection'          => '[সম্পাদনা] সংযোগের সাহায্যে পরিচ্ছেদ সম্পাদনা করা হোক',
'tog-rememberpassword'     => 'একাধিক সেশনের জন্য শব্দচাবি মনে রাখো',
'tog-editwidth'            => 'সম্পাদনা বাক্সটি তার পূর্ণ দৈর্ঘ্যে আছে',
'tog-watchcreations'       => 'আমার তৈরি পাতাসমূহ আমার নজরতালিকায় যোগ করো',
'tog-watchdefault'         => 'আমার সম্পাদনাগুলো আমার নজরতালিকায় যোগ কর',
'tog-watchmoves'           => 'আমার সরিয়ে ফেলা নিবন্ধগুলো আমার নজরতালিকায় যোগ কর',
'tog-watchdeletion'        => 'আমার মুছে ফেলা নিবন্ধগুলো আমার নজর তালিকায় যোগ কর',
'tog-minordefault'         => 'শুরুতেই সব সম্পাদনাকে অনুল্লেখ্য বলে চিহ্নিত কর',
'tog-previewontop'         => 'সম্পাদনা বাক্সের আগে প্রাকদর্শন দেখাও',
'tog-previewonfirst'       => 'প্রথম সম্পাদনার উপর প্রাকদর্শন দেখাও',
'tog-nocache'              => 'পৃষ্ঠা ক্যাশ করার ক্ষমতা নিষ্ক্রিয় করা হোক',
'tog-enotifwatchlistpages' => 'আমার নজরে আছে এমন পাতার পরিবর্তনে আমাকে ই-মেইল করো',
'tog-enotifusertalkpages'  => 'আমার আলাপের পাতার পরিবর্তনে আমাকে ই-মেইল করো',
'tog-enotifminoredits'     => 'পাতার অনুল্লেখ্য সম্পাদনার জন্যও আমাকে ই-মেইল করো',
'tog-enotifrevealaddr'     => 'বিজ্ঞপ্তি মেইলে আমার ই-মেইল ঠিকানা প্রকাশ করুন',
'tog-shownumberswatching'  => 'নজরকারীর সংখ্যা দেখাও',
'tog-fancysig'             => 'আপনার স্বাক্ষরে স্বয়ংক্রিয়ভাবে সংযোগ দিতে না চাইলে টিক দিন',
'tog-externaleditor'       => 'শুরুতেই বহিঃস্থ সম্পাদনা হাতিয়ার ব্যবহার করো',
'tog-externaldiff'         => 'শুরুতেই বহিঃস্থ পার্থক্য ব্যবহার করো',
'tog-watchlisthideown'     => 'আমার সম্পাদনাগুলো আমার নজরতালিকায় দেখিও না',
'tog-watchlisthidebots'    => 'বট দ্বারা সম্পাদনাগুলো নজরতালিকায় দেখিও না',
'tog-watchlisthideminor'   => 'অনুল্লেখ্য সম্পাদনাগুলো নজর তালিকায় দেখিও না',

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
'subcategories'         => 'উপবিষয়শ্রেণীসমূহ',
'category-media-header' => '"$1" বিষয়শ্রেণীতে অন্তর্ভুক্ত মিডিয়া ফাইলসমূহ',

'about'          => 'বৃত্তান্ত',
'newwindow'      => '(নতুন উইন্ডোতে খুলবে)',
'cancel'         => 'বাতিল কর',
'qbfind'         => 'অনুসন্ধান',
'qbbrowse'       => 'ঘুরে দেখ',
'qbedit'         => 'সম্পাদনা কর',
'qbpageoptions'  => 'এ পৃষ্ঠার বিকল্পসমূহ',
'qbpageinfo'     => 'পৃষ্ঠা-সংক্রান্ত তথ্য',
'qbmyoptions'    => 'আমার পছন্দ',
'qbspecialpages' => 'বিশেষ পৃষ্ঠাসমূহ',
'moredotdotdot'  => 'আরও...',
'mypage'         => 'আমার পাতা',
'mytalk'         => 'আমার আলাপ',
'anontalk'       => 'এই অজানা ব্যবহারকারীর আলাপের পৃষ্ঠা',
'navigation'     => 'পরিভ্রমন',

'errorpagetitle'    => 'ভুল',
'returnto'          => '$1 শিরোনামের পৃষ্ঠাতে ফেরত যান।',
'tagline'           => 'উইকিপিডিয়া, মুক্ত বিশ্বকোষ থেকে',
'help'              => 'সহায়িকা',
'search'            => 'অনুসন্ধান',
'searchbutton'      => 'অনুসন্ধান',
'go'                => 'চলো',
'searcharticle'     => 'চল',
'history'           => 'এ পৃষ্ঠার ইতিহাস',
'history_short'     => 'ইতিহাস',
'printableversion'  => 'ছাপার যোগ্য সংস্করণ',
'permalink'         => 'স্থায়ী সংযোগ',
'edit'              => 'সম্পাদনা করুন',
'editthispage'      => 'সম্পাদনা করুন',
'delete'            => 'মুছে ফেলুন',
'deletethispage'    => 'মুছে ফেলুন',
'protect'           => 'সুরক্ষিত করুন',
'protectthispage'   => 'সংরক্ষণ করুন',
'unprotect'         => 'সুরক্ষা সরিয়ে নিন',
'unprotectthispage' => 'সংরক্ষণ ছেড়ে দিন',
'newpage'           => 'নতুন পাতা',
'talkpage'          => 'আলোচনা করুন',
'specialpage'       => 'বিশেষ পৃষ্ঠা',
'postcomment'       => 'মন্তব্য করুন',
'articlepage'       => 'নিবন্ধ দেখুন',
'talk'              => 'আলোচনা',
'toolbox'           => 'হাতিয়ার',
'userpage'          => 'ব্যাবহারকারীর পাতা দেখুন',
'projectpage'       => 'মেটা-পাতা দেখুন',
'imagepage'         => 'ছবির পাতা দেখুন',
'categorypage'      => 'বিষয়শ্রেণীর পাতাটি দেখুন',
'viewtalkpage'      => 'আলোচনা দেখুন',
'otherlanguages'    => 'অন্যান্য ভাষা',
'redirectedfrom'    => '($1 থেকে ঘুরে এসেছে)',
'redirectpagesub'   => 'পুনর্নির্দেশ পৃষ্ঠা',
'lastmodifiedat'    => 'এ পৃষ্ঠায় শেষ পরিবর্তন হয়েছিল $2টার সময়, $1 তারিখে।', # $1 date, $2 time
'viewcount'         => 'এ পৃষ্ঠা $1 বার দেখা হয়েছে।',
'protectedpage'     => 'সুরক্ষিত পাতা',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'উইকিপিডিয়ার বৃত্তান্ত',
'aboutpage'         => '{{ns:project}}:বৃত্তান্ত',
'bugreports'        => 'ত্রুটি বিবরণী',
'bugreportspage'    => 'উইকিপেডিয়া:ত্রুটি_বিবরণী',
'copyright'         => '$1 এর আওতায় প্রাপ্য।',
'currentevents'     => 'সমসাময়িক ঘটনা',
'currentevents-url' => 'সমসাময়িক ঘটনাসমূহ',
'disclaimers'       => 'দাবিত্যাগ',
'edithelp'          => 'সম্পাদনা সহায়িকা',
'edithelppage'      => 'উইকিপেডিয়া:কিভাবে_একটি_পৃষ্ঠা_সম্পাদনা_করবেন',
'faq'               => 'প্রশ্নোত্তর',
'faqpage'           => 'উইকিপেডিয়া:প্রশ্নোত্তর',
'helppage'          => '{{ns:project}}:সহায়িকা',
'mainpage'          => 'প্রধান পাতা',
'portal'            => 'বাংলা উইকিপিডিয়া সম্প্রদায়',
'privacy'           => 'গোপনীয়তার নীতি',
'sitesupport'       => 'দান করুন',

'badaccess' => 'অনুমোদন ত্রুটি',

'versionrequired' => 'মিডিয়াউইকির $1 সংস্করণ প্রয়োজন',

'ok'                  => 'ঠিক আছে',
'retrievedfrom'       => "'$1' থেকে আনীত",
'youhavenewmessages'  => 'আপনার $1 ($2) এসেছে৷',
'newmessageslink'     => 'নতুন বার্তা',
'newmessagesdifflink' => 'সর্বশেষ সংশোধনের সাথে পার্থক্য',
'editsection'         => 'সম্পাদনা',
'editold'             => 'সম্পাদনা করুন',
'toc'                 => 'সূচিপত্র',
'showtoc'             => 'দেখাও',
'hidetoc'             => 'আড়ালে রাখো',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'নিবন্ধ',
'nstab-user'      => 'ব্যবহারকারীর পৃষ্ঠা',
'nstab-special'   => 'বিশেষ',
'nstab-project'   => 'প্রকল্প পাতা',
'nstab-image'     => 'ফাইল',
'nstab-mediawiki' => 'বার্তা',
'nstab-template'  => 'টেম্পলেট',
'nstab-help'      => 'সহায়িকা',
'nstab-category'  => 'বিষয়শ্রেণী',

# General errors
'databaseerror'      => 'ডাটাবেস ত্রুটি',
'filerenameerror'    => '"$1" ফাইলটির নাম বদলে "$2" করা সম্ভব হচ্ছে না।',
'filedeleteerror'    => '"$1" ফাইলটি মুছে ফেলা সম্ভব হচ্ছে না।',
'filenotfound'       => '"$1" ফাইলটি খুঁজে পাওয়া যাচ্ছে না।',
'badarticleerror'    => 'এই পৃষ্ঠাতে এই কাজটি করা সম্ভব নয়।',
'cannotdelete'       => 'এই পৃষ্ঠাটি বা ফাইলটি মোছা সম্ভব হল না। (সম্ভবতঃ অন্য কেউ আগেই এটিকে মুছে ফেলেছে)',
'badtitle'           => 'শিরোনামটি গ্রহনযোগ্য নয়।',
'perfcached'         => 'নিচের উপাত্তগুলো ক্যাশ থেকে নেয়া এবং সম্পূর্ণ হালনাগাদকৃত না-ও হতে পারে:',
'perfcachedts'       => 'নিচের উপাত্তগুলো ক্যাশ থেকে নেয়া এবং $1 তারিখে হালনাগাদ করা হয়েছে।',
'viewsource'         => 'উৎস দেখুন',
'viewsourcefor'      => '$1 এর জন্য',
'protectedinterface' => '[[Image:Padlock.svg|right|60px|]]এই পৃষ্ঠাটির বিষয়বস্তু উইকি সফটওয়্যারের একটি ইন্টারফেস বার্তা প্রদান করে, তাই এটিকে সুরক্ষিত করে রাখা হয়েছে।',

# Login and logout pages
'logouttitle'                => 'ব্যবহারকারীর প্রস্থান (logout)',
'logouttext'                 => '<strong>আপনি এইমাত্র আপনার একাউন্ট থেকে বেরিয়ে গেছেন।</strong><br />
এ পরিস্থিতিতে আপনি বেনামে {{SITENAME}} ব্যবহার করতে পারেন, কিংবা আবার আপনার একাউন্টে (বা নতুন কোন একাউন্টে) প্রবেশ করতে পারেন। লক্ষ্য করুন যে উকিপিডিয়ার কিছু কিছু পৃষ্ঠা এখনও এমনভাবে পরিবেশিত হতে পারে যাতে মনে হবে আপনি এখনও আপনার একাউন্ট থেকে বেরিয়ে যান নি। এক্ষেত্রে আপনাকে আপনার ব্রাওজারের ক্যাশ পরিষ্কার (clear browser cache) করে নিতে হবে।',
'welcomecreation'            => '== স্বাগতম $1! ==

আপনার অ্যাকাউন্ট তৈরী হয়েছে। আপনার {{SITENAME}} পছন্দ স্থির করে নিতে ভুলবেন না কিন্তু।',
'loginpagetitle'             => 'ব্যবহারকারী লগ ইন',
'yourname'                   => 'ব্যবহারকারীর নাম (Username)',
'yourpassword'               => 'শব্দচাবি (Password)',
'yourpasswordagain'          => 'শব্দচাবিটি (password) আবার লিখুন',
'remembermypassword'         => 'আমাকে মনে রাখো',
'yourdomainname'             => 'আপনার ডোমেইন',
'login'                      => 'প্রবেশ করুন',
'loginprompt'                => '{{SITENAME}}-তে সংযুক্ত হতে চাইলে আপনার ব্রাওজারের কুকি (cookies) অবশ্যই সক্রিয় (enabled) করতে হবে|',
'userlogin'                  => 'প্রবেশ/নতুন অ্যাকাউন্ট',
'logout'                     => 'বেরিয়ে যান',
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
'yournick'                   => 'ডাক নাম:',
'email'                      => 'ই-মেইল',
'loginerror'                 => 'লগ-ইন করতে সমস্যা হয়েছে',
'prefs-help-email'           => '* ই-মেইল (ঐচ্ছিক): এটি দেয়া থাকলে অন্যরা আপনার ব্যবহারকারী পৃষ্ঠার মাধ্যমে আপনার সাথে যোগাযোগ করতে পারবে।  সেজন্য আপনার পরিচয় তাদের জানা থাকা লাগবেনা।',
'nocookieslogin'             => '{{SITENAME}} এ কুকি (cookies) এর মাধ্যমে ব্যবহারকারীদের লগ-ইন সম্পন্ন করা হয়। আপনার ব্রাঊজারে কুকি বন্ধ করে দেওয়া আছে। কুকি চালু করে আবার চেষ্টা করুন।',
'loginsuccesstitle'          => 'প্রবেশ সফল',
'loginsuccess'               => "'''আপনি এইমাত্র \"\$1\" নামে {{SITENAME}}-তে প্রবেশ করেছেন।'''",
'wrongpassword'              => 'আপনি ভুল শব্দচাবি (password) ব্যবহার করেছেন। অনুগ্রহ করে আবার চেষ্টা করুন।',
'wrongpasswordempty'         => 'শব্দচাবি (password) প্রবেশের ক্ষেত্রটি খালি ছিল। অনুগ্রহপূর্বক আবার চেষ্টা করুন।',
'mailmypassword'             => 'নতুন শব্দচাবি ইমেইলে পাঠাও',
'acct_creation_throttle_hit' => 'দুঃখিত, আপনি ইতিমধ্যে $1টি অ্যাকাউন্ট তৈরী করেছেন৷ এর বেশী আপনি তৈরী করতে পারবেন না৷',
'emailauthenticated'         => 'আপনার ই-মেইল ঠিকানাটি $1 তারিখে নিশ্চিত করা হয়েছে।',
'emailnotauthenticated'      => 'আপনার ই-মেইলের ঠিকানা <strong>এখনও যাচাই করা হয়নি</strong>। নিচের বৈশিষ্ট্যগুলোর (features) জন্য কোনো ই-মেইল পাঠানো হবে না।',
'emailconfirmlink'           => 'আপনার ই-মেইলের ঠিকানা নিশ্চিত করুন',
'accountcreated'             => 'একাউন্ট তৈরি করা হয়েছে',
'accountcreatedtext'         => '$1 এর জন্য ব্যবহারকারী একাউন্ট তৈরি করা হয়েছে।',

# Edit page toolbar
'bold_sample'     => 'গাঢ় লেখা',
'bold_tip'        => 'গাঢ় লেখা',
'italic_sample'   => 'তীর্যক লেখা',
'italic_tip'      => 'তীর্যক লেখা',
'headline_sample' => 'শিরোনাম',
'headline_tip'    => '২য় স্তরের শিরোনাম',

# Edit pages
'summary'              => 'সম্পাদনা সারাংশ',
'subject'              => 'বিষয়/শিরোনাম',
'minoredit'            => 'অনুল্লেখ্য',
'watchthis'            => 'নজর রাখুন',
'savearticle'          => 'সংরক্ষণ',
'preview'              => 'প্রাকদর্শন',
'showpreview'          => 'প্রাকদর্শন',
'showdiff'             => 'পরিবর্তন দেখাও',
'anoneditwarning'      => 'আপনি লগ ইন করেননি। এই পৃষ্ঠার সম্পাদনার ইতিহাসে আপনার আইপি সংখ্যা সংরক্ষিত হবে।',
'missingsummary'       => "'''খেয়াল করুন''':  আপনি কিন্তু সম্পাদনার সারাংশ দেননি। আবার যদি \"সংরক্ষণ\" বোতামে ক্লিক করেন, তাহলে ঐ সারাংশ বাক্যটি ছাড়াই আপনার সম্পাদনা সংরক্ষিত হবে।",
'blockedtitle'         => 'ব্যবহারকারীকে বাধা দেয়া হয়েছে',
'blockedtext'          => "আপনার ব্যবহারকারী নাম অথবা আইপি ঠিকানাকে $1 বাধা দিয়েছেন। 
এর কারণ হিসেবে বলা হয়েছেঃ:<br />''$2''<p>আপনি  $1 অথবা [[Project:প্রশাসকবৃন্দ|প্রশাসকদের]] মধ্যে অন্য কারো সাথে এই বাধাদান সংক্রান্ত বিষয়ে আলোচনা করতে পারেন।

বিশেষ দ্রস্টব্যঃ আপনার ই-মেইল ঠিকানা যদি [[Special:Preferences|আপনার পছন্দ তালিকাতে]] লিপিবদ্ধ করা না থাকে, তাহলে আপনি উইকিপিডিয়া হতে অন্য ব্যবহারকারীকে ই-মেইল করতে পারবেন না।

আপনার আইপি ঠিকানা হল $3। দয়া করে যে কোন যোগাযোগের সময় এই ঠিকানাটি উল্লেখ করবেন।",
'confirmedittitle'     => 'সম্পাদনা করার জন্য ই-মেইল নিশ্চিতকরণ প্রয়োজন',
'confirmedittext'      => 'কোন সম্পাদনা করার আগে আপনার ই-মেইল ঠিকানাটি অবশ্যই নিশ্চিত করতে হবে। দয়া করে আপনার ই-মেইল ঠিকানাটি [[special:Preferences|ব্যবহারকারীর পছন্দতালিকা]]য় ঠিকমত প্রবেশ করান।',
'loginreqlink'         => 'লগ-ইন',
'loginreqpagetext'     => 'অন্যান্য পৃষ্ঠা দেখতে হলে আপনাকে অবশ্যই লগ-ইন করতে হবে।',
'accmailtitle'         => 'শব্দচাবি পাঠানো হয়েছে৷',
'accmailtext'          => '"$1"-এর শব্দচাবি(password) $2-এর কাছে পাঠানো হয়েছে৷',
'newarticle'           => '(নতুন)',
'newarticletext'       => 'এই নিবন্ধটি এখনো উইকিপিডিয়ায় সংযুক্ত হয়নি। আপনি চাইলে নীচের বক্সে বিষয়টি নিয়ে কিছু লিখে ও রক্ষা করে এই নিবন্ধটি শুরু করতে পারেন । যদি ভুলবশত এখানে এসে থাকেন তাহলে ব্রাউজারের ব্যাক বোতামে ক্লিক করে আগের পৃষ্ঠায় ফিরে যান।',
'clearyourcache'       => "'''লক্ষ্য করুন:''' আপনার পছন্দগুলো রক্ষা করার পর পরিবর্তনগুলো দেখার জন্য আপনাকে ব্রাউজারের ক্যাশ এড়াতে হতে পারে। '''মোজিলা/ফায়ারফক্স/সাফারি:''' শিফট কী চেপে ধরে রিলোড-এ ক্লিক করুন, কিংবা ''কন্ট্রোল-শিফট-R''(এপল ম্যাক-এ ''কমান্ড-শিফট-R'') একসাথে চাপুন; '''ইন্টারনেট এক্সপ্লোরার:''' ''কন্ট্রোল'' চেপে ধরে রিফ্রেশ-এ ক্লিক করুন, কিংবা ''কন্ট্রোল-F5'' চাপুন; '''কংকারার:''' কেবল রিলোড ক্লিক করলেই বা F5 চাপলেই চলবে; '''অপেরা''' ব্যবহারকারীদেরকে ''Tools&rarr;Preferences''-এ গিয়ে কাশ সম্পূর্ণ পরিষ্কার করে নিতে হতে পারে।",
'previewnote'          => '<strong>এটি প্রাকদর্শন মাত্র। কোনো পরিবর্তন এখনও রক্ষা করা হয়নি!</strong>',
'session_fail_preview' => '<strong>দুঃখিত! সেশন ডাটা হারিয়ে যাওয়ার কারণে আপনার সম্পাদনাটি রক্ষা করা সম্ভব হয়নি। দয়া করে লেখাটি আবার জমা দেয়ার চেষ্টা করুন। যদি এতেও কাজ না হয়, তবে অ্যাকাউন্ট থেকে বেরিয়ে গিয়ে আবার অ্যাকাউন্টে প্রবেশ করে চেষ্টা করুন।</strong>',
'editing'              => 'সম্পাদনা করছেন: $1',
'editingsection'       => 'সম্পাদনা করছেন $1 (অনুচ্ছেদ)',
'editingcomment'       => 'সম্পাদনা করছেন $1 (মন্তব্য)',
'yourtext'             => 'আপনার লেখা বিষয়বস্তু',
'yourdiff'             => 'পার্থক্য',
'copyrightwarning2'    => 'দয়া করে লক্ষ্য করুন: {{SITENAME}}-এর এই ভুক্তিতে আপনার লেখা বা অবদান অন্যান্য ব্যবহারকারীরা পরিবর্তন বা পরিবর্ধন করতে, এমনকি মুছে ফেলতে পারবেন। উইকিপিডিয়ায় আপনার সকল লেখালেখি/অবদান গনু ফ্রি ডকুমেন্টেশনের ($1) আওতায় বিনামূল্যে প্রাপ্য ও হস্তান্তরযোগ্য। আপনার জমা দেয়া লেখা যে কেউ হৃদয়হীনভাবে সম্পাদনা করতে এবং যথেচ্ছভাবে ব্যবহার করতে পারেন। আপনি যদি এ ব্যাপারে একমত না হন, তাহলে এখানে আপনার লেখা জমা দেবেন না। আপনি আরো প্রতিজ্ঞা করছেন যে, এই লেখাগুলো আপনি নিজে লিখেছেন (তবে কোন মৌলিক গবেষণা নয়) বা সাধারণের ব্যবহারের জন্য উন্মুক্ত কোন উত্‍স থেকে সংগ্রহ করেছেন। <strong>স্বত্ব সংরক্ষিত কোন লেখা স্বত্বাধিকারীর অনুমতি ছাড়া এখানে জমা দেবেন না।</strong>',
'longpagewarning'      => '<strong>সতর্কীকরণ: এই পৃষ্ঠাটি $1 কিলোবাইট দীর্ঘ; কিছু ব্রাউজারে ৩২ কিলোবাইটের চেয়ে দীর্ঘ পৃষ্ঠা সম্পাদনা করতে সমস্যা হতে পারে।
অনুগ্রহ করে পৃষ্ঠাটিকে একাধিক ক্ষুদ্রতর অংশে ভাগ করার চেষ্টা করুন।</strong>',
'templatesused'        => 'এই পৃষ্ঠায় ব্যবহৃত টেম্পলেট:',

# History pages
'revhistory'       => 'সংশোধনের ইতিহাস',
'viewpagelogs'     => 'এই পাতার জন্য লগ্‌গুলো দেখুন',
'nohistory'        => 'এই পাতার কোন সম্পাদনার ইতিহাস নাই।',
'currentrev'       => 'বর্তমান সংশোধন',
'revisionasof'     => '$1 তারিখের সংশোধন',
'previousrevision' => '←পুর্বের সংস্করণ',
'nextrevision'     => 'পরবর্তী সংস্করণ→',
'cur'              => 'বর্ত',
'next'             => 'পরবর্তী',
'last'             => 'পূর্ব',
'histlegend'       => 'পার্থক্য (Diff) নির্বাচন: যে সংস্করণগুলো তুলনা করতে চান, সেগুলো চিহ্নিত করে এন্টার বা নিচের বোতামটি টিপুন।<br />
নির্দেশিকা: (বর্ত) = বর্তমান সংস্করণের সাথে পার্থক্য,(পূর্ব) =  অব্যবহিত পূর্বের সংস্করণের সাথে পার্থক্য, অ = অনুল্লেখ্য সম্পাদনা।',
'histfirst'        => 'সবচেয়ে পুরনো',
'histlast'         => 'সবচেয়ে সাম্প্রতিক',

# Diffs
'difference'                => '(সংশোধনগুলোর মধ্যে পার্থক্য)',
'lineno'                    => '$1 নং লাইন:',
'selectnewerversionfordiff' => 'পার্থক্য করার জন্য একটি নতুন সংস্করণ নির্বাচন করুন',
'selectolderversionfordiff' => 'পার্থক্য করার জন্য একটি পুরাতন সংস্করণ নির্বাচন করুন',
'compareselectedversions'   => 'নির্বাচিত সংস্করণগুলো তুলনা করুন',

# Search results
'searchresults'         => 'অনুসন্ধানের ফলাফল',
'searchresulttext'      => 'উইকিপিডিয়ায় অনুসন্ধানের ব্যাপারে আরও তথ্যের জন্য [[Project:Searching|{{SITENAME}} অনুসন্ধান]] দেখুন।',
'searchsubtitle'        => "আপনি খোঁজ করেছেন '''[[:$1]]'''",
'searchsubtitleinvalid' => "আপনি খোঁজ করেছেন '''$1'''",
'noexactmatch'          => "'''\"\$1\" শিরোনামের কোন পৃষ্ঠা নেই।''' আপনি [[:\$1|পৃষ্ঠাটি সৃষ্টি করতে পারেন]]।",
'prevn'                 => 'পূর্ববর্তী $1টি',
'nextn'                 => 'পরবর্তী $1টি',
'viewprevnext'          => '($1) ($2) ($3) দেখুন।',
'showingresults'        => 'নীচে <b>$2</b> নং থেকে শুরু করে প্রথম <b>$1</b>টি ফলাফল দেখানো হল।',
'powersearch'           => 'খোঁজো',

# Preferences page
'preferences'       => 'আমার পছন্দ',
'mypreferences'     => 'আমার পছন্দ',
'prefsnologin'      => 'আপনি লগ-ইন করেননি',
'changepassword'    => 'শব্দচাবি (password) পরিবর্তন',
'skin'              => 'আবরণ (Skin)',
'math'              => 'গণিত',
'datetime'          => 'তারিখ ও সময়',
'prefs-personal'    => 'ব্যবহারকারীর প্রোফাইল',
'prefs-rc'          => 'সাম্প্রতিক পরিবর্তনসমূহ',
'prefs-watchlist'   => 'নজরতালিকা',
'prefs-misc'        => 'বিবিধ',
'saveprefs'         => 'রক্ষা করুন',
'resetprefs'        => 'পুনরায় আরম্ভ করুন',
'oldpassword'       => 'পুরনো শব্দচাবি',
'newpassword'       => 'নতুন শব্দচাবি:',
'retypenew'         => 'নতুন শব্দচাবিটি আবার টাইপ করুন:',
'textboxsize'       => 'সম্পাদনা',
'rows'              => 'সারি:',
'columns'           => 'কলাম:',
'searchresultshead' => 'অনুসন্ধান',
'savedprefs'        => 'আপনার পছন্দগুলো রক্ষা করা হয়েছে।',
'timezonelegend'    => 'সময় বলয়',
'timezonetext'      => 'আপনার স্থানীয় সময় থেকে সার্ভারের সময়ের (UTC) পার্থক্য (ঘন্টায়)।',
'localtime'         => 'স্থানীয় সময়',
'timezoneoffset'    => 'সময়পার্থক্য¹',
'servertime'        => 'সার্ভার সময়',
'guesstimezone'     => 'ব্রাউজার থেকে পূরণ করে নিন',
'allowemail'        => 'অন্য ব্যবহারকারীদেরকে আপনাকে ই-মেইল পাঠানোর অনুমতি দিন।',
'files'             => 'ফাইল',

# Recent changes
'nchanges'                          => '$1 টি পরিবর্তন',
'recentchanges'                     => 'সাম্প্রতিক পরিবর্তনসমূহ',
'rcnote'                            => 'বিগত <strong>$2</strong> দিনে সংঘটিত <strong>$1</strong>টি পরিবর্তন নীচে দেখানো হল (যেখানে বর্তমান সময় ও তারিখ $3)।',
'rcnotefrom'                        => '<b>$2</b>-এর পরে সংঘটিত পরিবর্তনগুলো নিচে দেখানো হল (<b>$1</b>টি)।',
'rclistfrom'                        => '* $1-এর পর সংঘটিত নতুন পরিবর্তনগুলো দেখাও।</div></div><br />',
'rcshowhideminor'                   => 'অনুল্লেখ্য পরিবর্তনগুলো $1',
'rcshowhidebots'                    => 'বটগুলো $1',
'rcshowhideliu'                     => 'প্রবেশ করেছেন এমন ব্যবহারকারীদের $1',
'rcshowhideanons'                   => 'বেনামী ব্যবহারকারীদের $1',
'rcshowhidepatr'                    => '$1 পরীক্ষিত সম্পাদনা',
'rcshowhidemine'                    => 'আমার সম্পাদনাগুলো $1',
'rclinks'                           => '<div style="border:1px solid #e7eef7; background:#fff; margin-bottom:5px;"> <div style="border:2px solid #fff; background:#e7eef7; padding:4px;">\'\'\'প্রদর্শনের ধরন\'\'\'<br />
* বিগত ($2) দিনের শেষ ($1)টি পরিবর্তন দেখাও
* $3',
'diff'                              => 'পরিবর্তন',
'hist'                              => 'ইতিহাস',
'hide'                              => 'দেখিও না',
'show'                              => 'দেখাও',
'minoreditletter'                   => 'অ',
'newpageletter'                     => 'ন',
'boteditletter'                     => 'ব',
'number_of_watching_users_pageview' => '[$1 জন নজরকারী]',
'rc_categories_any'                 => 'যেকোনো',

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
'''<nowiki>[[{{ns:6}}:file.jpg]]</nowiki>''',
'''<nowiki>[[{{ns:6}}:file.png|alt text]]</nowiki>''' অথবা
'''<nowiki>[[{{ns:-2}}:file.ogg]]</nowiki>'''",
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
'ilsubmit'                  => 'খোঁজো',
'byname'                    => 'নাম অনুযায়ী',
'bydate'                    => 'তারিখ অনুযায়ী',
'bysize'                    => 'আকার অনুযায়ী',
'imagelinks'                => 'সংযোগসমূহ',
'linkstoimage'              => 'নিচের পৃষ্ঠা(গুলো) থেকে এই ছবিতে সংযোগ আছে:',
'sharedupload'              => "<div style=\"clear:both;\"></div>
{| align=center border=0 cellpadding=2 cellspacing=2 style=\"border: solid #aaa 1px; background: #f9f9f9; font-size: 90%; margin: .2em auto .2em auto;\"
|- 
| [[Image:Commons-logo.svg|20px|Wikimedia Commons logo]]
|এই ফাইলটি [[Commons:Main Page|উইকিমিডিয়া কমন্‌স]] থেকে নেয়া। '''[[Commons:Image:{{PAGENAMEE}}|সেখান থেকে নেয়া]]''' ছবিটির বিশদ বর্ণনা নিম্নে প্রদর্শিত হল।
|}",
'shareduploadwiki'          => 'বিস্তারিত তথ্যের জন্য $1 দেখুন।',
'noimage'                   => 'এই নামে কোনো ফাইল নেই, আপনি যা করতে পারেন, তা হলো $1।',
'noimage-linktext'          => 'এই ফাইলটিকে আপলোড করুন।',
'uploadnewversion-linktext' => 'এই ফাইলটির একটি নতুন সংস্করণ আপলোড করুন',

# MIME search
'download' => 'ডাউনলোড',

# Statistics
'statistics'             => 'পরিসংখ্যান',
'sitestats'              => 'সাইট পরিসংখ্যান',
'userstats'              => 'ব্যবহারকারীর পরিসংখ্যান',
'sitestatstext'          => "ডাটাবেসে মোট '''\$1''' টি পৃষ্ঠা রয়েছে।
এগুলোর মধ্যে রয়েছে \"আলাপ\" পৃষ্ঠাগুলো, {{SITENAME}} বিষয়ক পৃষ্ঠাগুলো, '''অসম্পূর্ণ''' (stub) 
পৃষ্ঠাগুলো, পুনর্নিদেশগুলো, এবং অন্যান্য আরও পৃষ্ঠা যেগুলোতে সম্ভবত বিষয়বস্তুর অভাব রয়েছে।
এগুলো বাদে উইকিপিডিয়ায় সম্ভবত '''\$2'''টি  পৃষ্ঠা আছে যেগুলোতে যথেষ্ট পরিমাণ বিষয়বস্তু সংযোজিত হয়েছে। 

'''\$8'''টি ফাইল আপলোড করা হয়েছে।

There have been a total of '''\$3''' page views, and '''\$4''' page edits
since the wiki was setup.
That comes to '''\$5''' average edits per page, and '''\$6''' views per edit.

The [http://meta.wikimedia.org/wiki/Help:Job_queue job queue] length is '''\$7'''.",
'userstatstext'          => "'''$1''' জন নিবন্ধিত ব্যবহারকারী আছেন। এঁদের মধ্যে '''$2''' (বা '''$4%''') জন প্রশাসক ($3 দেখুন)।",
'statistics-mostpopular' => 'সবচেয়ে বেশী বার দেখা পৃষ্ঠাসমূহ',

'disambiguations'     => 'দ্ব্যর্থতা-দূরীকরণ পৃষ্ঠাসমূহ',
'disambiguationspage' => 'Template:দ্ব্যর্থতা_নিরসন',

'brokenredirects' => 'অকার্যকর পুনর্নির্দেশনাগুলো',

'withoutinterwiki'        => 'ভাষার সংযোগহীন পাতাসমূহ',
'withoutinterwiki-header' => 'এই পাতা সমূহ অন্য ভাষার সংস্করণের সাথে সংযুক্ত নয়:',

# Miscellaneous special pages
'nbytes'                  => '$1 বাইট',
'ncategories'             => '$1 টি বিষয়শ্রেণী',
'nlinks'                  => '$1টি সংযোগ',
'nmembers'                => '$1টি নিবন্ধ',
'nrevisions'              => '$1 বার সম্পাদিত',
'uncategorizedpages'      => 'যেসব পৃষ্ঠা শ্রেণীকরণ করা হয়নি',
'uncategorizedcategories' => 'যেসব বিষয়শ্রেণীর শ্রেণীকরণ করা হয় নি',
'uncategorizedimages'     => 'যেসব চিত্রের শ্রেণীকরণ করা হয় নি',
'unusedcategories'        => 'অব্যবহৃত বিষয়শ্রেণীগুলো',
'unusedimages'            => 'অব্যবহৃত ফাইলগুলো',
'popularpages'            => 'জনপ্রিয় পাতাসমূহ',
'mostcategories'          => 'সবচেয়ে বেশী বিষয়শ্রেণী-সমৃদ্ধ নিবন্ধসমূহ',
'mostrevisions'           => 'সবচেয়ে বেশী বার সম্পাদিত নিবন্ধসমূহ',
'allpages'                => 'সব পৃষ্ঠা',
'randompage'              => 'অজানা যেকোনো পৃষ্ঠা',
'shortpages'              => 'সংক্ষিপ্ত পৃষ্ঠাসমূহ',
'longpages'               => 'দীর্ঘ পৃষ্ঠাসমূহ',
'deadendpages'            => 'যেসব পৃষ্ঠা থেকে কোনো সংযোগ নেই',
'protectedpages'          => 'সুরক্ষিত পাতাসমূহ',
'listusers'               => 'ব্যবহারকারী তালিকা',
'specialpages'            => 'বিশেষ পৃষ্ঠাসমূহ',
'spheading'               => 'বিশেষ পৃষ্ঠাসমূহ',
'rclsub'                  => '(যে সব পৃষ্ঠায় "$1" থেকে সংযোগ আছে)',
'newpages'                => 'নতুন পৃষ্ঠাসমূহ',
'ancientpages'            => 'পুরানো নিবন্ধ',
'move'                    => 'সরিয়ে ফেলুন',
'movethispage'            => 'স্থানান্তর করুন',
'unusedcategoriestext'    => 'নিচের বিষয়শ্রেণীগুলোর অস্তিত্ব আছে, যদিও কোনো নিবন্ধ বা অন্য কোনো বিষয়শ্রেণী এগুলোকে ব্যবহার করে না।',

# Book sources
'booksources' => 'বইয়ের উৎস',

'categoriespagetext' => 'বাংলা উইকিপিডিয়ার বর্তমান বিষয়শ্রেণীসমূহ:',
'data'               => 'উপাত্ত (Data)',
'alphaindexline'     => '$1 হতে $2',
'version'            => 'সংস্করণ',

# Special:Log
'specialloguserlabel'  => 'ব্যবহারকারী:',
'speciallogtitlelabel' => 'শিরোনাম:',

# Special:Allpages
'nextpage'          => 'পরবর্তী পাতা ($1)',
'prevpage'          => 'পূর্ববর্তী পাতা ($1)',
'allpagesfrom'      => 'এই অক্ষর দিয়ে শুরু হওয়া পৃষ্ঠাগুলো দেখাও:',
'allarticles'       => 'সমস্ত নিবন্ধ',
'allinnamespace'    => 'সমস্ত পৃষ্ঠা ($1 নামস্থান)',
'allnotinnamespace' => 'সমস্ত পাতা ($1 নামস্থানে নয়)',
'allpagesprev'      => 'পূর্ববর্তী',
'allpagesnext'      => 'পরবর্তী',
'allpagessubmit'    => 'চলো',
'allpagesprefix'    => 'এই উপসর্গবিশিষ্ট পৃষ্ঠাগুলো দেখাও:',

# E-mail user
'mailnologintext' => "অন্য ব্যবহারকারীদেরকে ই-মেইল পাঠাতে হলে আপনাকে অবশ্যই আগে [[Special:Userlogin|লগ-ইন]] করতে হবে এবং ''[[Special:Preferences|আপনার পছন্দ তালিকায়]] আপনার ই-মেইল ঠিকানাটি ঠিকমত প্রবেশ করাতে হবে।",
'emailuser'       => 'ইমেইল করুন',
'emailpage'       => 'ব্যবহারকারীকে ই-মেইল করুন',
'emailto'         => 'প্রাপক',
'emailsubject'    => 'বিষয়',
'emailmessage'    => 'বার্তা',
'emailsend'       => 'প্রেরণ করুন',
'emailsent'       => 'ই-মেইল প্রেরণ করা হয়েছে',
'emailsenttext'   => 'আপনার ই-মেইল বার্তা প্রেরণ করা হয়েছে।',

# Watchlist
'watchlist'            => 'আমার নজরতালিকা',
'mywatchlist'          => 'আমার নজরতালিকা',
'watchlistfor'         => "('''$1''' এর জন্য)",
'watchnologin'         => 'আপনি এখনও লগ-ইন করেননি।',
'watchnologintext'     => 'আপনার নজরতালিকা পরিবর্তনের জন্য আপনাকে অবশ্যই অ্যাকাউন্টে [[Special:Userlogin|প্রবেশ করতে হবে]]।',
'addedwatch'           => 'নজরতালিকায় যুক্ত হয়েছে',
'addedwatchtext'       => "\"\$1\" পৃষ্ঠাটি আপনার [[Special:Watchlist|নজরতালিকা]]-তে যোগ করা হয়েছে৷

ভবিষ্যতে এই পৃষ্ঠা ও এই পৃষ্ঠার সাথে সম্পর্কিত আলোচনা পাতায় সংঘটিত যাবতীয় পরিবর্তন এখানে তালিকাভুক্ত করা হবে৷ 
এছাড়া [[Special:Recentchanges|সাম্প্রতিক পরিবর্তনসমূহ]]-তে এই পৃষ্ঠাটিকে '''গাঢ়''' অক্ষরে দেখানো হবে যাতে আপনি সহজেই পৃষ্ঠাটি শনাক্ত করতে পারেন৷

<p>পরবর্তীতে আপনি যদি পৃষ্ঠাটিকে আপনার নজরতালিকা থেকে সরিয়ে ফেলতে চান, তবে \"নজর সরিয়ে নিন\" ট্যাবটিতে ক্লিক করবেন৷",
'removedwatch'         => 'নজরতালিকা থেকে সরিয়ে ফেলা হয়েছে',
'removedwatchtext'     => '"$1" পৃষ্ঠাটি আপনার নজরতালিকা থেকে সরিয়ে ফেলা হয়েছে৷',
'watch'                => 'নজরে রাখুন',
'watchthispage'        => 'নজর রাখুন',
'unwatch'              => 'নজর সরিয়ে নিন',
'unwatchthispage'      => 'নজরদারী ছেড়ে দিন',
'watchnochange'        => 'প্রদর্শিত সময়সীমার মধ্যে আপনার নজরতালিকায় রাখা কোন পৃষ্ঠাতেই কোনরকম সম্পাদনা ঘটেনি।',
'wlheader-enotif'      => '* ই-মেইল এর মাধমে নির্দেশনার ব্যবস্থা চালু করা আছে।',
'watchlistcontains'    => 'আপনার নজরতালিকায় $1 টি পৃষ্ঠা রয়েছে।',
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
'exblank'                     => 'পৃষ্ঠাটি খালি ছিল',
'confirmdelete'               => 'মোছা সুনিশ্চিত করুন।',
'deletesub'                   => '("$1" মুছে ফেলা হচ্ছে)',
'actioncomplete'              => 'কাজটি নিষ্পন্ন হয়েছে',
'deletedtext'                 => '"$1" মুছে ফেলা হয়েছে। সাম্প্রতিক মুছে ফেলার ঘটনাগুলো $2-এ দেখুন।',
'deletedarticle'              => '"[[$1]]" মুছে ফেলা হয়েছে।',
'dellogpage'                  => 'পৃষ্ঠা_অবলুপ্তি_লগ্',
'dellogpagetext'              => 'নিচে সবচেয়ে সাম্প্রতিক অবলুপ্তিগুলোর একাটি তালিকা দেওয়া হল।',
'deletionlog'                 => 'পৃষ্ঠা অবলুপ্তি লগ্',
'reverted'                    => 'পূর্ববর্তী সংস্করণে ফিরে যাওয়া সফল হয়েছে।',
'deletecomment'               => 'মুছে ফেলার কারন',
'rollback'                    => 'সম্পাদনা ফিরিয়ে নিন',
'rollback_short'              => 'ফিরিয়ে নিন',
'cantrollback'                => 'পূর্বের সংস্করণে ফেরত যাওয়া সম্ভব হল না, সর্বশেষ সম্পাদনাকারী এই নিবন্ধটির একমাত্র লেখক।',
'revertpage'                  => '[[Special:Contributions/$2|$2]] ([[User_talk:$2|আলাপ]]) এর সম্পাদিত সংস্করণ হতে [[User:$1|$1]] এর সম্পাদিত সর্বশেষ সংস্করণে ফেরত যাওয়া হয়েছে।',
'protectlogpage'              => 'সুরক্ষা_লগ',
'protectedarticle'            => 'সুরক্ষিত "[[$1]]"',
'unprotectedarticle'          => '"[[$1]]"-এর সুরক্ষা সরিয়ে নেওয়া হয়েছে',
'protectsub'                  => '("$1" সুরক্ষিত করা হচ্ছে)',
'confirmprotect'              => 'সুরক্ষা নিশ্চিত করুন',
'protectcomment'              => 'সুরক্ষার কারণ',
'unprotectsub'                => '("$1"-এর সুরক্ষা সরিয়ে নিচ্ছি)',
'protect-level-autoconfirmed' => 'অনিবন্ধীকৃত ব্যবহারকারীদের বাধা দাও',
'restriction-type'            => 'অনুমতি:',

# Restrictions (nouns)
'restriction-edit' => 'সম্পাদনা',
'restriction-move' => 'সরিয়ে নেয়া',

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
'sp-contributions-newbies'  => 'শুধু নতুন একাউন্টের অবদানসমূহ দেখাও',
'sp-contributions-search'   => 'অবদানসমূহের জন্য অনুসন্ধান',
'sp-contributions-username' => 'IP Address  অথবা ব্যবহারকারীর নাম:',
'sp-contributions-submit'   => 'অনুসন্ধান',

'sp-newimages-showfrom' => '$1 হতে শুরু করে নতুন ছবিগুলো দেখাও',

# What links here
'whatlinkshere' => 'সংযোগকারী পৃষ্ঠাসমূহ',
'linklistsub'   => '(সংযোগসমূহের তালিকা)',
'linkshere'     => 'নিচের পৃষ্ঠা(গুলো) থেকে এই পৃষ্ঠায় সংযোগ আছে:',
'nolinkshere'   => 'কোনো পৃষ্ঠা থেকে এখানে সংযোগ নেই।',

# Block/unblock
'blockip'            => 'ব্যবহারকারীকে বাধা দাও',
'badipaddress'       => 'আইপি ঠিকানাটি অগ্রহনযোগ্য',
'blockipsuccesssub'  => 'বাধা সফল',
'blockipsuccesstext' => '[[{{ns:Special}}:Contributions/$1|$1]] কে বাধা দেয়া হয়েছে
<br />বাধা দেয়া পুনর্বিবেচনা করতে হলে [[{{ns:Special}}:Ipblocklist|বাধা দেয়া আইপি ঠিকানার তালিকা]] দেখুন।',
'ipblocklist'        => 'নিষিদ্ধ ঘোষিত আইপি ঠিকানা ও ব্যবহারকারী নামের তালিকা',
'blocklistline'      => '$1 তারিখে  $2,  $3 ($4) কে বাধা দিয়েছেন।',
'expiringblock'      => 'শেষ হবে $1',
'blocklink'          => 'বাধা দাও',
'contribslink'       => 'অবদান',
'blocklogpage'       => 'বাধা দানের লগ',
'blocklogentry'      => '"[[$1]]"-কে $2 মেয়াদের জন্য বাধা দেওয়া হয়েছে।',

# Move page
'movepage'         => 'পৃষ্ঠাটি সরান',
'movearticle'      => 'যে পৃষ্ঠা সরানো হবে',
'movenologintext'  => 'কোন পৃষ্ঠা সরাতে চাইলে আপনাকে অবশ্যই একজন নিবন্ধিত ব্যবহারকারী হতে হবে ও অ্যাকাউন্টে [[Special:Userlogin|প্রবেশ]] করতে হবে।',
'newtitle'         => 'এই নতুন শিরোনামে',
'move-watch'       => 'এই পৃষ্ঠাটি নজরে রাখুন',
'pagemovedsub'     => 'স্থানান্তর সফল',
'articleexists'    => 'হয় এই শিরোনামের একটি নিবন্ধ ইতোমধ্যে সৃষ্টি হযে গেছে, অথবা আপনি যে শিরোনামটি পছন্দ করেছেন তা গ্রহণযোগ্য নয়। দয়া করে অন্য একটি শিরোনাম দিয়ে চেষ্টা করুন।',
'movetalk'         => 'সংশ্লিষ্ট আলাপ পাতা সরাও',
'talkpagemoved'    => 'সংশ্লিষ্ট আলাপ পাতাটিকেও সরানো হয়েছে।',
'talkpagenotmoved' => 'সংশ্লিষ্ট আলাপ পাতাটিকে <strong>সরানো হয়নি</strong>।',
'1movedto2'        => '[[$1]]-কে [[$2]]-এ সরানো হয়েছে',
'1movedto2_redir'  => '[[$1]]-কে [[$2]]-তে পুনর্নির্দেশনার সাহায্যে সরানো হয়েছে',
'movelogpage'      => 'পৃষ্ঠা স্থানান্তর লগ্',
'movereason'       => 'কারণ',

# Namespace 8 related
'allmessages'         => 'সিস্টেম বার্তাসমূহ',
'allmessagesname'     => 'নাম',
'allmessagescurrent'  => 'বর্তমানের ভাষা',
'allmessagestext'     => 'নিচে মিডিয়াউইকি: নামস্থানে অন্তর্ভুক্ত সিস্টেম বার্তাগুলোর তালিকা দেওয়া হল।',
'allmessagesmodified' => 'শুধু পরিবর্তিত অংশগুলো দেখাও',

# Thumbnails
'thumbnail-more' => 'বড় করুন',

# Tooltip help for the actions
'tooltip-ca-delete' => 'পাতাটি মুছে ফেলুন',
'tooltip-t-print'   => 'এ পাতার ছাপানোর উপযোগী সংস্করণ',

# Attribution
'anonymous' => '{{SITENAME}} এর বেনামী ব্যবহারকারীবৃন্দ',
'and'       => 'এবং',

# Spam protection
'subcategorycount'     => 'এই বিষয়শ্রেণীতে $1 টি উপবিষয়শ্রেণী রয়েছে।',
'categoryarticlecount' => 'এই বিষয়শ্রেণীতে $1টি নিবন্ধ রয়েছে।',

# Info page
'numauthors' => 'পৃথক (নিবন্ধ) লেখকের সংখ্যা: $1',

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
'patrol-log-auto' => '(স্বয়ংক্রিয়)',

# Browsing diffs
'previousdiff' => '← পূর্বের পার্থক্য',
'nextdiff'     => 'পরবর্তী পার্থক্য →',

# Special:Newimages
'newimages' => 'নতুন ফাইলের গ্যালারি',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'সব',
'watchlistall2'    => 'সবগুলো',
'namespacesall'    => 'সমস্ত',

# E-mail address confirmation
'confirmemail'            => 'ই-মেইলের ঠিকানা নিশ্চিত করুন',
'confirmemail_send'       => 'নিশ্চিতকরণ ই-মেইল /কোড পাঠান।',
'confirmemail_sent'       => 'নিশ্চিতকরণ ই-মেইল পাঠানো হয়েছে।',
'confirmemail_sendfailed' => 'নিশ্চিতকরণ ই-মেইলটি পাঠানো সম্ভব হলো না। ইমেইল ঠিকানাটি ঠিকভাবে লিখেছেন কিনা, সেটি যাচাই করে দেখুন।',
'confirmemail_invalid'    => 'নিশ্চিতকরণের কোডটি সঠিক নয়। সম্ভবতঃ এটি পুরানো হয়ে গেছে।',
'confirmemail_success'    => 'আপনার ই-মেইল ঠিকানাটি নিশ্চিত করা হয়েছে। আপনি এখন লগ-ইন করতে পারেন।',
'confirmemail_loggedin'   => 'আপনার ই-মেইল ঠিকানাটি নিশ্চিত করা হয়েছে।',

# Delete conflict
'deletedwhileediting' => 'সতর্কীকরণ: আপনি পৃষ্ঠাটি সম্পাদনা শুরু করার পর পৃষ্ঠাটিকে মুছে ফেলা হয়েছে!',

# action=purge
'confirm_purge'        => 'এই পাতার ক্যাশে পরিষ্কার করতে চান?

$1',
'confirm_purge_button' => 'ঠিক আছে',

# AJAX search
'searchcontaining' => "''$1'' আছে এমন নিবন্ধগুলো অনুসন্ধান কর।",
'articletitles'    => "যেসব পৃষ্ঠা ''$1'' দিয়ে শুরু হয়েছে, তাদের তালিকা",
'hideresults'      => 'ফলাফলগুলো দেখিও না',

# Auto-summaries
'autosumm-blank'   => 'পৃষ্ঠার সমস্ত বিষয়বস্তু মুছে ফেলা হল',
'autosumm-replace' => "পৃষ্ঠাকে '$1' দিয়ে প্রতিস্থাপিত করা হল",
'autoredircomment' => '[[$1]]-এ পুনর্নির্দেশ করা হল',
'autosumm-new'     => 'নতুন পৃষ্ঠা: $1',

# Size units
'size-bytes' => '$1 বাইট',

# Watchlist editing tools
'watchlisttools-clear' => 'নজরতালিকা পরিস্কার কর',

);

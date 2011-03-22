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
 * @author Gahori
 * @author Jaminianurag
 * @author Priyankoo
 * @author Psneog
 * @author Rajuonline
 * @author Reedy
 * @author Urhixidur
 */

$fallback = 'bn';

$namespaceNames = array(
	NS_MEDIA            => 'মাধ্যম',
	NS_SPECIAL          => 'বিশেষ',
	NS_TALK             => 'বাৰ্তা',
	NS_USER             => 'সদস্য',
	NS_USER_TALK        => 'সদস্য_বাৰ্তা',
	NS_PROJECT_TALK     => '$1_বাৰ্তা',
	NS_FILE             => 'চিত্ৰ',
	NS_FILE_TALK        => 'চিত্ৰ_বাৰ্তা',
	NS_MEDIAWIKI        => 'মেডিয়াৱিকি',
	NS_MEDIAWIKI_TALK   => 'মেডিয়াৱিকি_বাৰ্তা',
	NS_TEMPLATE         => 'সাঁচ',
	NS_TEMPLATE_TALK    => 'সাঁচ_বাৰ্তা',
	NS_HELP             => 'সহায়',
	NS_HELP_TALK        => 'সহায়_বাৰ্তা',
	NS_CATEGORY         => 'শ্ৰেণী',
	NS_CATEGORY_TALK    => 'শ্ৰেণী_বাৰ্তা',
);

$namespaceAliases = array(
	'विशेष' => NS_SPECIAL,
	'वार्ता' => NS_TALK,
	'বার্তা' => NS_TALK,
	'सदस्य' => NS_USER,
	'सदस्य_वार्ता' => NS_USER_TALK,
	'সদস্য বার্তা' => NS_USER_TALK,
	'$1_वार्ता' => NS_PROJECT_TALK,
	'$1 বার্তা' => NS_PROJECT_TALK,
	'चित्र' => NS_FILE,
	'चित्र_वार्ता' => NS_FILE_TALK,
	'চিত্র' => NS_FILE,
	'চিত্র বার্তা' => NS_FILE_TALK,
	'MediaWiki বার্তা' => NS_MEDIAWIKI_TALK,
	'साँचा' => NS_TEMPLATE,
	'साँचा_वार्ता' => NS_TEMPLATE_TALK,
	'সাঁচ বার্তা' => NS_TEMPLATE_TALK,
	'সহায় বার্তা' => NS_HELP_TALK,
	'श्रेणी' => NS_CATEGORY,
	'श्रेणी_वार्ता' => NS_CATEGORY_TALK,
	'শ্রেণী' => NS_CATEGORY,
	'শ্রেণী বার্তা' => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Userlogin'                 => array( 'সদস্যৰ_প্ৰবেশ' ),
	'Userlogout'                => array( 'সদস্যৰ_প্ৰস্থান' ),
	'CreateAccount'             => array( 'সদস্যভুক্তি' ),
	'Preferences'               => array( 'পচন্দ' ),
	'Watchlist'                 => array( 'লক্ষ্যতালিকা' ),
	'Recentchanges'             => array( 'শেহতীয়া_কাম' ),
	'Upload'                    => array( 'বোজাই' ),
	'Listfiles'                 => array( 'চিত্ৰ-তালিকা' ),
	'Newimages'                 => array( 'নতুন_চিত্ৰ' ),
	'Listusers'                 => array( 'সদস্য-তালিকা' ),
	'Listgrouprights'           => array( 'গোটৰ_অধিকাৰসমূহ' ),
	'Statistics'                => array( 'পৰিসংখ্যা' ),
	'Randompage'                => array( 'আকস্মিক' ),
	'Lonelypages'               => array( 'অকলশৰীয়া_পৃষ্ঠা' ),
	'Uncategorizedpages'        => array( 'অবিন্যস্ত_পৃষ্ঠাসমূহ' ),
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
'tog-highlightbroken'         => 'ভঙা সংযোগসমূহ <a href="" class="new">এনেকৈ</a> (বা এনেকৈ<a href="" class="internal">?</a>) দেখুৱাওক ।',
'tog-justify'                 => 'দফাৰ সীমা সমান কৰাক',
'tog-hideminor'               => 'সাম্প্ৰতিক সাল-সলনিত অগুৰুত্বপূৰ্ণ সম্পাদনা নেদেখুৱাব',
'tog-hidepatrolled'           => 'সাম্প্ৰতিক সাল-সলনিত তহলদাৰী সম্পাদনা নেদেখুৱাব',
'tog-newpageshidepatrolled'   => 'নতুন পৃষ্ঠা তালিকাত তহলদাৰী পৃষ্ঠাসমূহ নেদেখুৱাব',
'tog-extendwatchlist'         => 'কেৱল সাম্প্ৰতিকেই নহয, লক্ষ্য-তালিকাৰ সকলো সাল-সলনি বহলাই দেখুৱাওক',
'tog-usenewrc'                => 'বৰ্দ্ধিত সাম্প্ৰতিক সাল-সলনি ব্যবহাৰ কৰক (জাভাস্ক্ৰিপ্টৰ দৰকাৰ)',
'tog-numberheadings'          => 'শীৰ্ষকত স্বয়ংক্ৰীয়ভাৱে ক্ৰমিক নং দিয়ক',
'tog-showtoolbar'             => 'সম্পাদনা দণ্ডিকা দেখুৱাওক (জাভাস্ক্ৰিপ্টৰ দৰকাৰ)',
'tog-editondblclick'          => 'একেলগে দুবাৰ টিপা মাৰিলে পৃষ্ঠা সম্পদনা কৰক (জাভাস্ক্ৰিপ্টৰ দৰকাৰ)',
'tog-editsection'             => '[সম্পাদনা কৰক] সংযোগৰ দ্বাৰা সম্পাদনা কৰা সক্ৰীয় কৰক',
'tog-editsectiononrightclick' => 'বিষয়ৰ শিৰোণামাত সো-বুটাম টিপা মাৰি সম্পাদনা কৰাতো সক্ৰীয় কৰক (JavaScript)',
'tog-showtoc'                 => 'শিৰোণামাৰ সুচী দেখুৱাওক (যিবোৰ পৃষ্ঠাত তিনিতাতকৈ বেছি শিৰোণামা আছে)',
'tog-rememberpassword'        => 'মোৰ প্ৰৱেশ এই কম্পিউটাৰত মনত ৰাখক (সৰ্বাধিক $1 {{PLURAL:$1|দিনলৈ|দিনলৈ}})',
'tog-watchcreations'          => 'মই বনোৱা সকলো পৃষ্ঠা মোৰ লক্ষ্য-তালিকাত যোগ কৰক',
'tog-watchdefault'            => 'মই সম্পাদনা কৰা সকলো পৃষ্ঠা মোৰ লক্ষ্য-তালিকাত যোগ কৰক',
'tog-watchmoves'              => 'মই স্থানান্তৰ কৰা সকলো পৃষ্ঠা মোৰ লক্ষ্য-তালিকাত যোগ কৰক',
'tog-watchdeletion'           => 'মই বিলোপ কৰা সকলো পৃষ্ঠা মোৰ লক্ষ্য-তালিকাত যোগ কৰক',
'tog-minordefault'            => 'সকলো সম্পাদনা অগুৰুত্বপূৰ্ণ বুলি নিজে নিজে চিহ্নিত কৰক',
'tog-previewontop'            => 'সম্পাদনা বাকছৰ ওপৰত খচৰা দেখুৱাওক',
'tog-previewonfirst'          => 'প্ৰথম সম্পাদনাৰ পিছত খচৰা দেখুৱাওক',
'tog-nocache'                 => 'ব্ৰাউজাৰ পৃষ্ঠা কেশ্বিং নিষ্ক্ৰীয় কৰক',
'tog-enotifwatchlistpages'    => 'মোৰ লক্ষ্য-তালিকাত থকা পৃষ্ঠা সলনি হলে মোলৈ ই-মেইল পঠাব',
'tog-enotifusertalkpages'     => 'মোৰ বাৰ্তা পৃষ্ঠা সলনি হলে মোলৈ ই-মেইল পঠাব',
'tog-enotifminoredits'        => 'অগুৰুত্বপূৰ্ণ সম্পাদনা হলেও মোলৈ ই-মেইল পঠাব',
'tog-enotifrevealaddr'        => 'জাননী ই-মেইল বোৰত মোৰ ই-মেইল ঠিকনা দেখুৱাব',
'tog-shownumberswatching'     => 'লক্ষ্য কৰি থকা সদস্য সমূহৰ সংখ্যা দেখুৱাওক',
'tog-oldsig'                  => 'স্বাক্ষৰৰ খচৰা:',
'tog-fancysig'                => 'স্বাক্ষৰ ৱিকিটেক্সট হিচাপে ব্যৱহাৰ কৰক (স্বয়ংক্ৰীয় সংযোগ অবিহনে)',
'tog-externaleditor'          => 'সদায়ে বাহ্যিক সম্পাদক ব্যৱহাৰ কৰিব (কেৱল জনা সকলৰ বাবে, ইয়াৰ বাবে আপোনাৰ কম্পিউটাৰত বিশেষ ব্যৱস্থা থাকিব লাগিব)',
'tog-externaldiff'            => 'ডিফ’ল্ট ভাবে বাহ্যিক তফাৎ (diff) ব্যৱহাৰ কৰক (দক্ষ্য জনৰ বাবেহে, আপোনাৰ কম্পিউটাৰত বিশেষ ব্যৱস্থা থাকিব লাগিব । [http://www.mediawiki.org/wiki/Manual:External_editors সবিশেষ ।])',
'tog-showjumplinks'           => '"জপিয়াই যাওক" সংযোগ সক্ৰীয় কৰক',
'tog-uselivepreview'          => 'সম্পাদনাৰ লগে লগে খচৰা দেখুৱাওক (JavaScript) (পৰীক্ষামূলক)',
'tog-forceeditsummary'        => 'সম্পাদনাৰ সাৰাংশ নিদিলে মোক জনাব',
'tog-watchlisthideown'        => 'মোৰ লক্ষ্য-তালিকাত মোৰ সম্পাদনা নেদেখুৱাব',
'tog-watchlisthidebots'       => 'মোৰ লক্ষ্য-তালিকাত বটে কৰা সম্পাদনা নেদেখুৱাব',
'tog-watchlisthideminor'      => 'মোৰ লক্ষ্য-তালিকাত অগুৰুত্বপূৰ্ণ সম্পাদনা নেদেখুৱাব',
'tog-watchlisthideliu'        => 'প্ৰবেশ কৰা সদস্যৰ সম্পাদনাসমূহ আঁতৰাই অনুসৰণ-তালিকা দেখোৱাওক',
'tog-watchlisthideanons'      => 'বেনামী সদস্যৰ সম্পাদনাসমূহ আঁতৰাই অনুসৰণ-তালিকা দেখোৱাওক',
'tog-watchlisthidepatrolled'  => 'পৰীক্ষিত সম্পাদনাসমূহ আঁতৰাই অনুসৰণ-তালিকা দেখোৱাওক',
'tog-ccmeonemails'            => 'মই অন্য সদস্যলৈ পঠোৱা ই-মেইলৰ প্ৰতিলিপী এটা মোলৈও পঠাব',
'tog-diffonly'                => 'তফাৎৰ তলত পৃষ্ঠাৰ বিষয়বস্তু নেদেখোৱাব',
'tog-showhiddencats'          => 'নিহিত শ্ৰেণী সমূহ দেখুৱাওক',
'tog-norollbackdiff'          => 'ৰোলবেক্ কৰা পাচত পাৰ্থক্য নেদেখুৱাব',

'underline-always'  => 'সদায়',
'underline-never'   => 'কেতিয়াও নহয়',
'underline-default' => 'ব্ৰাউজাৰ ডিফল্ট',

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
'friday'        => 'শুক্ৰবাৰ',
'saturday'      => 'শণিবাৰ',
'sun'           => 'দেও',
'mon'           => 'সোম',
'tue'           => 'মংগল',
'wed'           => 'বুধ',
'thu'           => 'বৃহস্পতি',
'fri'           => 'শুক্ৰ',
'sat'           => 'শনি',
'january'       => 'জানুৱাৰী',
'february'      => 'ফেব্ৰুৱাৰী',
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
'february-gen'  => 'ফেব্ৰুৱাৰী',
'march-gen'     => 'মাৰ্চ',
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
'feb'           => 'ফেব্ৰু:',
'mar'           => 'মাৰ্চ',
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
'pagecategories'                 => '{{PLURAL:$1|শ্ৰেণী|শ্ৰেণী}}',
'category_header'                => '"$1" শ্ৰেণীৰ পৃষ্ঠাসমূহ',
'subcategories'                  => 'উপবিভাগ',
'category-media-header'          => '"$1" শ্ৰেণীৰ মেডিয়া',
'category-empty'                 => "''এই শ্ৰেণীত বৰ্তমান কোনো লিখনী বা মাধ্যম নাই''",
'hidden-categories'              => '{{PLURAL:$1|নিহিত শ্ৰেণী|নিহিত শ্ৰেণীসমূহ}}',
'hidden-category-category'       => 'নিহিত শ্ৰেণী সমূহ',
'category-subcat-count'          => '{{PLURAL:$2|এই শ্ৰেণীত নিম্নলিখিত উপশ্ৰেণী আছে । এই শ্ৰেণীত নিম্নলিখিত {{PLURAL:$1|উপশ্ৰেণীটো|$1 উপশ্ৰেণীসমূহ}} আছে, মুঠতে $2  তা উপশ্ৰেণী।}}',
'category-subcat-count-limited'  => 'এই শ্ৰেণীত নিম্নলিখিত {{PLURAL:$1|উপশ্ৰেণী আছে|$1 উপশ্ৰেণী আছে}}.',
'category-article-count'         => '{{PLURAL:$2|এই শ্ৰেণীটোত কেবল তলত দিয়া লিখনীটোহে আছে । এই শ্ৰেণীটোত তলৰ  {{PLURAL:$1|এটা লিখনী আছে|$1 টা লিখনী আছে}}, মুঠ লিখনী $2 টা।}}',
'category-article-count-limited' => 'এই {{PLURAL:$1|পৃষ্ঠা|$1 পৃষ্ঠাসমূহ}} সাম্প্ৰতিক শ্ৰেণিত আছে ।',
'category-file-count'            => '{{PLURAL:$2|এই শ্ৰেণীটোত কেবল তলত দিয়া ফাইলটোহে আছে । এই শ্ৰেণীটোত তলৰ  {{PLURAL:$1|এটা ফাইল|$1 টা ফাইল}} আছে, মুঠ $2টাৰ ভিতৰত।}}',
'category-file-count-limited'    => 'তলৰ {{PLURAL:$1|ফাইলটি|$1 ফাইলকেইখন}} সাম্প্ৰতিক শ্ৰেণিত আছে ।',
'listingcontinuesabbrev'         => 'আগলৈ',
'index-category'                 => 'সূচীকৃত পৃষ্ঠাসমূহ',
'noindex-category'               => 'অসূচীকৃত পৃষ্ঠাসমূহ',

'mainpagetext'      => "'''মেডিয়াৱিকি সফলভাবে ইন্সটল কৰা হ'ল ।'''",
'mainpagedocfooter' => "ৱিকি চ'ফটৱেৰ কেনেকৈ ব্যৱহাৰ কৰিব [http://meta.wikimedia.org/wiki/Help:Contents সদস্যৰ সহায়িকা] চাওঁক ।

== আৰম্ভণি কৰিবলৈ ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]",

'about'         => 'বিষয়ে',
'article'       => 'লিখনি',
'newwindow'     => '(নতুন উইণ্ডোত খোল খায়)',
'cancel'        => 'ৰদ কৰা হওক',
'moredotdotdot' => 'ক্ৰমশ:...',
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
'qbpageinfo'     => 'প্ৰসংগ',
'qbmyoptions'    => 'মোৰ পৃষ্ঠাসমূহ',
'qbspecialpages' => 'বিশেষ পৃষ্ঠাসমূহ',
'faq'            => 'প্ৰায়ে উঠা প্ৰশ্ন',
'faqpage'        => 'Project:প্ৰায়ে উঠা প্ৰশ্ন',

# Vector skin
'vector-action-addsection'       => 'বিষয় যোগ',
'vector-action-delete'           => 'মচি পেলাওক',
'vector-action-move'             => 'স্থানান্তৰ কৰক',
'vector-action-protect'          => 'সংৰক্ষিত কৰক',
'vector-action-undelete'         => 'মচি পেলাওঁক',
'vector-action-unprotect'        => 'অসংৰক্ষিত কৰক',
'vector-simplesearch-preference' => 'উৎকৃষ্ট সন্ধানৰ দিহা-পোহা সক্ৰিয় কৰক (ভেক্টৰ স্কিনৰ বাবেহে)',
'vector-view-create'             => 'সৃষ্টি কৰক',
'vector-view-edit'               => 'সম্পাদনা',
'vector-view-history'            => 'ইতিহাস চাওঁক',
'vector-view-view'               => 'পঢ়ক',
'vector-view-viewsource'         => 'উৎস চাওঁক',
'actions'                        => 'কাৰ্য্যসমূহ',
'namespaces'                     => 'নামস্থান',
'variants'                       => 'বিকল্পসমূহ',

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
'printableversion'  => 'প্ৰিণ্ট কৰাৰ উপযোগী',
'permalink'         => 'স্থায়ী সুত্ৰ(লিংক)',
'print'             => 'প্ৰিন্ট কৰিবলৈ',
'view'              => 'দেখুৱাওক',
'edit'              => 'সম্পাদন',
'create'            => 'সৃষ্টি কৰক',
'editthispage'      => 'বৰ্তমান পৃষ্ঠাটো সম্পাদন কৰিবলৈ',
'create-this-page'  => 'নতুন পৃষ্ঠা সৃষ্টি কৰক',
'delete'            => 'বিলোপন(ডিলিট)',
'deletethispage'    => 'বৰ্তমান পৃষ্ঠাৰ বিলোপন(ডিলিট)',
'undelete_short'    => '{{PLURAL:$1|বিলোপিত পৃষ্ঠাৰ|$1 সংখ্যক বিলোপিত পৃষ্ঠাৰ}} পূৰ্ববৎকৰণ',
'viewdeleted_short' => '{{PLURAL:$1| এটা বিলুপ্ত সম্পাদনা|$1 টা বিলুপ্ত সম্পাদনা}} দেখুৱাওক',
'protect'           => 'সংৰক্ষ(প্ৰটেক্ট)',
'protect_change'    => 'সলাওক',
'protectthispage'   => 'বৰ্তমান পৃষ্ঠাৰ সংৰক্ষণবিধিৰ পৰিবৰ্তন',
'unprotect'         => 'সংৰক্ষণমুক্ত কৰক',
'unprotectthispage' => 'এই পৃষ্ঠা সংৰক্ষণমুক্ত কৰক',
'newpage'           => 'নতুন পৃষ্ঠা',
'talkpage'          => 'এই পৃষ্ঠা সম্পৰ্কে কথা-বতৰা',
'talkpagelinktext'  => 'আলোচনা',
'specialpage'       => 'বিশেষ পৃষ্ঠা',
'personaltools'     => 'ব্যক্তিগত সৰঞ্জাম',
'postcomment'       => 'নতুন অনুচ্ছেদ',
'articlepage'       => 'প্ৰবন্ধ',
'talk'              => 'কথাবতৰা',
'views'             => 'দৰ্শ(ভিউ)',
'toolbox'           => 'সাজ-সৰঞ্জাম',
'userpage'          => 'ভোক্তাৰ(ইউজাৰ) পৃষ্ঠা',
'projectpage'       => 'প্ৰকল্প পৃষ্ঠা',
'imagepage'         => 'ফাইল পৃষ্ঠা চাওক',
'mediawikipage'     => 'বাৰ্তা পৃষ্ঠা চাওক',
'templatepage'      => 'সাঁচ পৃষ্ঠা চাওক',
'viewhelppage'      => 'সহায় পৃষ্ঠা চাওক',
'categorypage'      => 'শ্ৰেণী পৃষ্ঠা চাওক',
'viewtalkpage'      => 'কথা-বতৰা চাওক',
'otherlanguages'    => 'আন ভাষাত',
'redirectedfrom'    => '($1 ৰ পৰা)',
'redirectpagesub'   => 'পূনঃনিৰ্দেশিত পৃষ্ঠা',
'lastmodifiedat'    => 'এই পৃষ্ঠাটো শেষবাৰৰ কাৰণে $1 তাৰিখে $2 বজাত সলনি কৰা হৈছিল',
'viewcount'         => 'এই পৃষ্ঠাটো {{PLURAL:$1|এবাৰ|$1}} বাৰ চোৱা হৈছে',
'protectedpage'     => 'সুৰক্ষিত পৃষ্ঠা',
'jumpto'            => 'গম্যাৰ্থে',
'jumptonavigation'  => 'দিকদৰ্শন',
'jumptosearch'      => 'সন্ধানাৰ্থে',
'view-pool-error'   => 'দুঃখিত, এই মুহূৰ্তত চাৰ্ভাৰত অতিৰিক্ত চাপ পৰিছে ।
অজস্ৰ সদস্যই এই পৃষ্ঠা চাব বিচাৰিছে ।
অনুগ্ৰহ কৰি অলপ পাচত এই পৃষ্ঠা চাব প্ৰয়াস কৰক ।

$1',
'pool-queuefull'    => 'পোল কিউ (pool queue) সমূল',
'pool-errorunknown' => 'অপৰিচিত ত্ৰুটি',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}}ৰ বৃত্তান্ত',
'aboutpage'            => 'Project:ইতিবৃত্ত',
'copyright'            => 'এই লিখনী $1 ৰ অন্তৰ্গত উপলব্ধ।',
'copyrightpage'        => '{{ns:project}}:স্বত্ব',
'currentevents'        => 'সাম্প্ৰতিক ঘটনাৱলী',
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
'newmessageslink'         => 'নতুন বাৰ্তা',
'newmessagesdifflink'     => 'শেহতীয়া সাল-সলনি',
'youhavenewmessagesmulti' => '$1 ত আপোনাৰ কাৰণে নতুন বাৰ্তা আছে',
'editsection'             => 'সম্পাদনা কৰক',
'editold'                 => 'সম্পাদনা',
'viewsourceold'           => 'অক্ষৰ-মূল দেখুওৱা হওক',
'editlink'                => 'সম্পাদনা',
'viewsourcelink'          => 'উৎস চাওঁক',
'editsectionhint'         => '$1 খণ্ডৰ সম্পাদনা',
'toc'                     => 'সূচী',
'showtoc'                 => 'দেখুওৱাওক',
'hidetoc'                 => 'দেখুৱাব নালাগে',
'collapsible-collapse'    => 'সংকোচন',
'collapsible-expand'      => 'বহলাওক',
'thisisdeleted'           => '$1 চাওক বা সলনি কৰক?',
'viewdeleted'             => '$1 চাওক?',
'restorelink'             => '{{PLURAL:$1| এটা বিলুপ্ত সম্পাদনা|$1 টা বিলুপ্ত সম্পাদনা}}',
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
'nstab-project'   => 'প্ৰকল্প পৃষ্ঠা',
'nstab-image'     => 'চিত্ৰ',
'nstab-mediawiki' => 'বাৰ্তা',
'nstab-template'  => 'সাঁচ',
'nstab-help'      => 'সাহায্য পৃষ্ঠা',
'nstab-category'  => 'শ্ৰেণী',

# Main script and global functions
'nosuchaction'      => 'এনে কাৰ্য্য নাই',
'nosuchactiontext'  => "এই ইউআৰএল-এ নিৰ্ধাৰিত কৰা কাৰ্য্য অবৈধ।
আপুনি বোধহয়  ইউআৰএল ভুলকৈ লিখিছে বা এটা ভুল লিঙ্ক অনুকৰণ কৰিছে ।
হ'বও পাৰে যে {{SITENAME}}-ত ব্যবহাৰ হোৱা চফ্টৱেৰত ক্ৰুটি আছে ।",
'nosuchspecialpage' => 'এনেকুৱা কোনো বিশেষ পৃষ্ঠা নাই',
'nospecialpagetext' => '<strong>আপুনি অস্তিত্বত নথকা বিশেষ পৃষ্ঠা এটা বিচাৰিছে </strong>

   বিশেষ পৃষ্ঠাহমুহৰ তালিকা ইয়াত পাব [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'ভুল',
'databaseerror'        => 'তথ্যকোষৰ ভুল',
'laggedslavemode'      => 'সাবধান: ইয়াত সাম্প্ৰতিক সাল-সলনি নাথাকিব পাৰে',
'readonly'             => 'তথ্যকোষ বন্ধ কৰা আছে',
'enterlockreason'      => 'বন্ধ কৰাৰ কাৰণ দিয়ক, লগতে কেতিয়ামানে খোলা হব তাকো জনাব।',
'readonlytext'         => 'নতুন সম্পাদন আৰু আন সাল-সলনিৰ কাৰণে তথ্যকোষ বৰ্তমানে বন্ধ আছে, হয়তো নিয়মিয়া চোৱ-চিতা কৰিবলৈ, কিছু সময় পিছ্ত এয়া সধাৰণ অৱস্থালৈ আহিব।

যিজন প্ৰৱন্ধকে বন্ধ কৰিছে তেও কাৰণ দিছে: $1',
'missingarticle-rev'   => '(সংস্কৰণ#: $1)',
'missingarticle-diff'  => '(তফাৎ: $1, $2)',
'internalerror'        => 'ভিতৰুৱা গণ্ডোগোল',
'internalerror_info'   => 'ভিতৰুৱা গণ্ডোগোল: $1',
'fileappenderrorread'  => 'জোৰা দিয়াৰ সময়ত "$1" পাঠ্য কৰা নহ\'ল ।',
'fileappenderror'      => '"$2"ৰ লগত "$1"ৰ সংযোগ কৰা নহ\'ল ।',
'filecopyerror'        => '"$1" ফাইলটো "$2" লৈ প্ৰতিলিপী কৰিব পৰা নগল।',
'filerenameerror'      => '"$1" ফাইলৰ নাম সলনি কৰি "$2" কৰিব পৰা নগল ।',
'filedeleteerror'      => '"$1" ফাইলতো বিলোপ কৰিব পৰা নগল।',
'directorycreateerror' => '"$1" ডাইৰেক্টৰি বনাব পৰা নগল।',
'filenotfound'         => '"$1" নামৰ ফাইলটো বিচাৰি পোৱা নগল।',
'fileexistserror'      => '"$1" ফাইলটোত লিখিব নোৱাৰি: ফাইলটো আগৰ পৰাই আছে',
'unexpected'           => 'অনাকাংক্ষিত মুল্য: "$1"="$2".',
'formerror'            => 'ভুল: ফৰ্ম খন জমা দিব পৰা নগল',
'badarticleerror'      => 'এই পৃষ্ঠাটোত এই কামটো কৰিব নোৱাৰি ।',
'cannotdelete'         => '"$1" পৃষ্ঠা বা ফাইল মচা সম্ভব নহয় ।
সম্ভৱ আনে আগেই মচী থৈছে ।',
'badtitle'             => 'অনভিপ্ৰেত শিৰোণামা',
'badtitletext'         => 'আপুনি বিচৰা পৃষ্ঠাটোৰ শিৰোণামা অযোগ্য, খালী বা ভুলকে জৰিত আন্তৰ্ভাষিক বা আন্তৰ্ৱিকি শিৰোণামা। ইয়াত এক বা ততোধিক বৰ্ণ থাকিব পাৰে যাক শিৰোণামাত ব্যৱহাৰ কৰিব নোৱাৰি।',
'perfcached'           => 'তলত দিয়া তথ্য খিনি আগতে জমা কৰি থোৱা (cached) আৰু সাম্প্ৰতিক নহব পাৰে।',
'perfcachedts'         => 'তলত দিয়া তথ্য খিনি আগতে জমা কৰি থোৱা (cached) আৰু শেষবাৰৰ কাৰণে $1 ত নৱীকৰণ কৰা হৈছিল।',
'querypage-no-updates' => 'এই পৃষ্ঠাটো নৱীকৰণ কৰা ৰোধ কৰা হৈছে। ইয়াৰ তথ্য এতিয়া সতেজ কৰিব নোৱাৰি।',
'wrong_wfQuery_params' => 'wfQuery() ৰ কাৰণে ভুল মাপদণ্ড দিয়া হৈছে <br />
কাৰ্য্য: $1<br />পৃষ্ঠা: $2',
'viewsource'           => 'উৎস চাবলৈ',
'viewsourcefor'        => '$1 ৰ কাৰণে',
'actionthrottled'      => 'কাৰ্য্য লেহেম কৰা হৈছে',
'actionthrottledtext'  => 'স্পাম ৰোধ কৰিবলৈ এই ক্ৰিয়াতো কম সময়ৰ ভিতৰত বহু বেছি বাৰ কৰাতো ৰোধ কৰা হৈছে, আৰু আপুনি ইতিমধ্যে সেই সীমা অতিক্ৰম কৰিলে।
অনুগ্ৰহ কৰি কিছু সময় পাছত চেষ্টা কৰক।',
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
'logouttext'                 => "'''আপুনি প্ৰস্থান কৰিলে ।'''

আপুনি বেনামী ভাবেও {{SITENAME}} ব্যৱহাৰ কৰিব পাৰে, অথবা আকৌ সেই একে বা বেলেগ নামেৰে [[Special:UserLogin|প্ৰৱেশ]] কৰিব পাৰে।
মন কৰিব যে যেতিয়ালৈকে আপোনাৰ ব্ৰাউজাৰৰ অস্থায়ী-স্মৃতি (cache memory) খালী নকৰে, তেতিয়ালৈকে কিছুমান পৃষ্ঠাত আপুনি প্ৰৱেশ কৰা বুলি দেখুৱাই থাকিব পাৰে।",
'welcomecreation'            => '== স্বাগতম, $1! ==
আপোনাৰ সদস্যভুক্তি হৈ গল ।
[[Special:Preferences|{{SITENAME}}  পছন্দসমূহ]]ত আপোনাৰ পচন্দমতে ব্যক্তিগতকৰণ কৰি লবলৈ নাপাহৰে যেন|',
'yourname'                   => 'সদস্যনাম:',
'yourpassword'               => 'আপোনাৰ গুপ্তশব্দ',
'yourpasswordagain'          => 'গুপ্তশব্দ আকৌ এবাৰ লিখক',
'remembermypassword'         => 'মোৰ প্ৰৱেশ এই কম্পিউটাৰত মনত ৰাখিব (সৰ্বাধিক $1 {{PLURAL:$1|দিনলৈ|দিনলৈ}})',
'securelogin-stick-https'    => 'প্ৰৱেশ কৰা পাছত HTTPS-ৰ দ্বাৰা সংযোগ ৰাখক',
'yourdomainname'             => 'আপোনাৰ দমেইন:',
'login'                      => 'প্ৰৱেশ',
'nav-login-createaccount'    => 'প্ৰৱেশ/সদস্যভুক্তি',
'loginprompt'                => '{{SITENAME}}ত প্ৰৱেশ কৰিবলৈ আপুনি কুকী সক্ৰীয় কৰিব লাগিব',
'userlogin'                  => 'প্ৰৱেশ/সদস্যভুক্তি',
'userloginnocreate'          => 'প্ৰৱেশ',
'logout'                     => 'প্ৰস্থান',
'userlogout'                 => 'প্ৰস্থান',
'notloggedin'                => 'প্ৰৱেশ কৰা নাই',
'nologin'                    => 'আপুনি কি সদস্য নহয়? $1',
'nologinlink'                => 'নতুন সদস্যভুক্তি কৰক',
'createaccount'              => 'সভ্যভুক্ত হবলৈ',
'gotaccount'                 => "আপুনি সদস্য হয়নে? '''$1'''",
'gotaccountlink'             => 'প্ৰবেশ',
'createaccountmail'          => 'ই-মেইলেৰে',
'createaccountreason'        => 'কাৰণ:',
'badretype'                  => 'আপুনি দিয়া গুপ্ত শব্দ দুটা মিলা নাই।',
'userexists'                 => 'আপুনি দিয়া সদস্যনাম আগৰে পৰাই ব্যৱহাৰ হৈ আছে।
অনুগ্ৰহ কৰি বেলেগ সদস্যনাম এটা বাচনী কৰক।',
'loginerror'                 => 'প্ৰৱেশ সমস্যা',
'createaccounterror'         => "একাউন্ট সৃষ্টি কৰা নহ'ল: $1",
'nocookiesnew'               => 'আপোনাৰ সদস্যভুক্তি হৈ গৈছে, কিন্তু আপুনি প্ৰৱেশ কৰা নাই।
{{SITENAME}} ত প্ৰৱেশ কৰিবলৈ কুকী সক্ৰিয় থাকিব লাগিব।
আপুনি কুকী নিস্ক্ৰিয় কৰি থৈছে।
অনুগ্ৰহ কৰি কুকী সক্ৰীয় কৰক, আৰু তাৰ পাছত আপোনাৰ সদস্যনামেৰে প্ৰৱেশ কৰক।',
'nocookieslogin'             => '{{SITENAME}} ত প্ৰৱেশ কৰিবলৈ কুকী সক্ৰিয় থাকিব লাগিব।
আপুনি কুকী নিস্ক্ৰিয় কৰি থৈছে।
অনুগ্ৰহ কৰি কুকী সক্ৰীয় কৰক, আৰু তাৰ পাছত চেষ্টা কৰক।',
'nocookiesfornew'            => 'সদস্য একাউন্ট সৃষ্টি কৰা নহল, কাৰণ তাৰ উৎস অনিশ্চিত ।
আপুনাৰ কুকি সক্ৰিয় ৰাখক, এই পৃষ্ঠা ৰি-লোড কৰি পুনৰ চেষ্টা কৰক ।',
'noname'                     => 'আপুনি বৈধ সদস্যনাম এটা দিয়া নাই।',
'loginsuccesstitle'          => "প্ৰবেশ অনুমোদিত হ'ল",
'loginsuccess'               => "''' আপুনি {{SITENAME}}ত \"\$1\" নামেৰে প্ৰবেশ কৰিলে '''",
'nosuchuser'                 => '"$1" নামৰ কোনো সদস্য নাই।
সদস্য নাম আকাৰ সংবেদনশীল।
আপোনাৰ বানানতো চাওক, বা  [[Special:UserLogin/signup|নতুন সদস্যভুক্তি কৰক]]।',
'nosuchusershort'            => '"<nowiki>$1</nowiki>" এই নামৰ কোনো সদস্য নাই ।
বানানতো আকৌ এবাৰ ভালদৰে চাওক ।',
'nouserspecified'            => 'অপুনি সদস্যনাম এটা দিবই লাগিব।',
'login-userblocked'          => 'এই সদস্যক নিষেধ কৰা হৈছে । লগ্ইন্ অসম্ভৱ ।',
'wrongpassword'              => 'আপুনি ভুল গুপ্তশব্দ দিছে। অনুগ্ৰহ কৰি আকৌ এবাৰ চেষ্টা কৰক।',
'wrongpasswordempty'         => 'দিয়া গুপ্তশব্দতো খালী; অনুগ্ৰহ কৰি আকৌ এবাৰ চেষ্টা কৰক। ।',
'passwordtooshort'           => "গুপ্তশব্দ কমেও {{PLURAL:$1|১ তা|$1 তা}} আখৰ হ'ব লাগিব ।",
'password-name-match'        => "আপুনাৰ গুপ্তশব্দ আৰু আপুনাৰ সদস্যনাম বেলেগ হ'ব লাগিব",
'password-login-forbidden'   => 'এই সদস্যনাম আৰু গুপ্তসব্দৰ ব্যৱহাৰ নিষিদ্ধ কৰা হৈছে ।',
'mailmypassword'             => 'ই-মেইলত গুপ্তশব্দ পঠাওক',
'passwordremindertitle'      => '{{SITENAME}} ৰ কাৰণে নতুন অস্থায়ী গুপ্তশব্দ',
'passwordremindertext'       => 'কোনোবাই (হয়তো আপুনি, $1 আই-পি ঠিকনাৰ পৰা)
{{SITENAME}} ত ব্যৱহাৰ কৰিবলৈ ’নতুন গুপ্তশব্দ’ বিছাৰিছে ($4) ।
"$2" সদস্যজনৰ কাৰনে এতিয়া নতুন গুপ্তশব্দ হৈছে "$3" ।
আপুনি এতিয়া প্ৰবেশ কৰক আৰু গুপ্তশব্দতো সলনি কৰক।
আপুনাৰ অস্থায়ী গুপ্তশব্দ {{PLURAL:$5|১ দিনৰ|$5 দিনৰ}} ভিতৰত ৰদ কৰা হ\'ব ।

যদি আপুনি এই অনুৰোধ কৰা নাছিল অথবা যদি আপোনাৰ গুপ্তশব্দতো মনত আছে আৰু তাক সলাব নিবিছাৰে, তেনেহলে আপুনি এই বাৰ্তাতো অবজ্ঞা কৰিব পাৰে আৰু আপোনাৰ আগৰ গুপ্তশব্দতোকে ব্যৱহাৰ কৰি থাকিব পাৰে।',
'noemail'                    => '"$1" সদস্যজনৰ কোনো ই-মেইল ঠিকনা সঞ্চিত কৰা নাই।',
'noemailcreate'              => 'আপুনি এটা সঠিক ইমেইল ঠিকানা দিব লাগে',
'passwordsent'               => '"$1" ৰ ই-মেইল ঠিকনাত নতুন গুপ্তশব্দ এটা পঠোৱা হৈছে। অনুগ্ৰহ কৰি সেয়া পোৱাৰ পাছত পুনৰ প্ৰবেশ কৰক।',
'blocked-mailpassword'       => 'আপোনাৰ IP ঠিকনাৰ পৰা সম্পাদনা কৰা বাৰণ কৰা হৈছে, এনে অৱস্থাত দুৰ্ব্যৱহাৰ ৰোধ কৰিবলৈ গুপ্তশব্দ পুনঃউদ্ধাৰ কৰা সুবিধাতো বাতিল কৰা হৈছে।',
'eauthentsent'               => 'সঞ্চিত ই-মেইল ঠিকনাত নিশ্বিতকৰণ ই-মেইল এখন পঠোৱা হৈছে।
আৰু অন্যান্য ই-মেইল পঠোৱাৰ আগতে, আপোনাৰ সদস্যতাৰ নিশ্বিত কৰিবলৈ সেই ই-মেইলত দিয়া নিৰ্দেশনা আপুনি অনুসৰন কৰিব লাগিব।',
'throttled-mailpassword'     => 'যোৱা {{PLURAL:$1|ঘণ্টাত|$1 ঘণ্টাত}} গুপ্তশব্দ পুনৰুদ্ধাৰ সুচনা পঠিওৱা হৈছে ।
অবৈধ ব্যৱহাৰ ৰোধ কৰিবলৈ $1 ঘণ্টাত এবাৰহে গুপ্তশব্দ পুনৰুদ্ধাৰ সুচনা পঠিওৱা হয়।',
'mailerror'                  => 'ই-মেইল পঠোৱাত সমস্যা হৈছে: $1',
'acct_creation_throttle_hit' => 'যোৱা ২৪ ঘন্টাত আপুনাৰ আই-পি ঠিকনাৰ পৰা কেউজনে {{PLURAL:$1|১-তা একাউন্ট|$1-তা একাউন্ট}} সৃষ্টি কৰিলে, যোনতো সৰ্বোচ্চ অনুমোদনকৃত ।
এতেকে, এই আই-পি ঠিকনাৰ পৰা এই খন্তেকত একাউন্ট সৃষ্টি কৰিব নোৱাৰিব ।',
'emailauthenticated'         => 'আপোনাৰ ই-মেইল ঠিকনাটো $2 তাৰিখৰ $3 বজাত নিশ্চিত কৰা হৈছিল ।',
'emailnotauthenticated'      => 'আপোনাৰ ই-মেইল ঠিকনাতো এতিয়ালৈ প্ৰমনিত হোৱা নাই ।
আপুনি তলৰ বিষয়বোৰৰ কাৰণে মেইল পঠাব নোৱাৰে ।',
'noemailprefs'               => 'এই সুবিধাবোৰ ব্যৱহাৰ কৰিবলৈ এটা ই-মেইল ঠিকনা দিয়ক।',
'emailconfirmlink'           => 'আপোনাৰ ই-মেইল ঠিকনতো প্ৰমানিত কৰক',
'invalidemailaddress'        => 'এই ই-মেইল ঠিকনাতো গ্ৰহনযোগ্য নহয়, কাৰণ ই অবৈধ প্ৰকাৰৰ যেন লাগিছে।
অনুগ্ৰহ কৰি এটা বৈধ ই-মেইল ঠিকনা লিখক অথবা একো নিলিখিব।',
'accountcreated'             => 'সদস্যতা সৃষ্টি কৰা হল',
'accountcreatedtext'         => '$1 ৰ কাৰণে সদস্যভুক্তি কৰা হল।',
'createaccount-title'        => '{{SITENAME}} ৰ কাৰণে সদস্যভুক্তি কৰক।',
'createaccount-text'         => 'আপোনাৰ ই-মেইল ঠিকনাৰ কাৰণে {{SITENAME}} ($4) ত "$2" নামৰ কোনোবাই, "$3" গুপ্তশব্দ দি সদস্যভুক্তি কৰিছে। অনুগ্ৰহ কৰি আপুনি প্ৰৱেশ কৰক আৰু গুপ্তশব্দটো সলনি কৰক।

যদি এইয়া ভুলতে হৈছে, তেনেহলে আপুনি এই বাৰ্তাটো অবজ্ঞা কৰিব পাৰে ।',
'usernamehasherror'          => 'সদস্যনামত হেচ আখৰ থাকিব নোৱাৰে',
'login-throttled'            => 'আপুনি সাম্প্ৰতিক অজস্ৰবাৰ লগইন প্ৰয়াস কৰিছে ।
অনুগ্ৰহ কৰি কিছু সময় অপেক্ষা কৰি আকৌ প্ৰয়াস কৰক ।',
'login-abort-generic'        => 'আপোনাৰ প্ৰৱেশ অসফল-বাতিল কৰা হ’ল',
'loginlanguagelabel'         => 'ভাষা: $1',

# E-mail sending
'php-mail-error-unknown' => 'পি-এইছ-পি mail() ফলনত অজ্ঞাত ত্ৰুটি',

# JavaScript password checks
'password-strength'            => 'গুপ্তশব্দৰ শক্তি অনুমান: $1',
'password-strength-bad'        => 'অতিকৈ দুৰ্বল',
'password-strength-mediocre'   => 'দুৰ্বল',
'password-strength-acceptable' => 'গ্ৰহণযোগ্য',
'password-strength-good'       => 'শ্ৰেষ্ঠ',
'password-retype'              => 'গুপ্তশব্দ আকৌ এবাৰ লিখক',
'password-retype-mismatch'     => 'গুপ্তশব্দকেইটা মিলা নাই',

# Password reset dialog
'resetpass'                 => 'গুপ্তশব্দ শলনি',
'resetpass_announce'        => 'আপুনি ই-মেইলত পোৱা অস্থায়ী গুপ্তশব্দৰে প্ৰৱেশ কৰিছে।
প্ৰৱেশ সম্পুৰ্ণ কৰিবলৈ, আপুনি এটা নতুন গুপ্তশব্দ দিব লাগিব:',
'resetpass_header'          => 'গুপ্তশব্দ শলনি কৰক',
'oldpassword'               => 'পূৰণি গুপ্তশব্দ:',
'newpassword'               => 'নতুন গুপ্তশব্দ:',
'retypenew'                 => 'নতুন গুপ্তশব্দ আকৌ টাইপ কৰক',
'resetpass_submit'          => 'গুপ্তশব্দ বনাওক আৰু প্ৰৱেশ কৰক',
'resetpass_success'         => 'আপোনাৰ গুপ্তশব্দ সফলতাৰে সলনি কৰা হৈছে, এতিয়া আপুনি প্ৰৱেশ কৰি আছে...',
'resetpass_forbidden'       => 'গুপ্তশব্দ সলনি কৰিব নোৱাৰি',
'resetpass-no-info'         => 'এই পৃষ্ঠা প্ৰতক্ষ্য ভাবে ঢুকি পাবলৈ আপুনি প্ৰৱেশ কৰিব লাগিব ।',
'resetpass-submit-loggedin' => 'গুপ্তশব্দ সলনি কৰক',
'resetpass-submit-cancel'   => 'বাতিল কৰক',
'resetpass-wrong-oldpass'   => 'অস্থায়ী বা সাম্প্ৰতিক গুপ্তশব্দ গ্ৰহণযোগ্য নহয় ।
হয়টো আপুনি ইতিমধ্যেই সফলভাবে আপুনাৰ গুপ্তশব্দ সলনি কৰিছিল বা এটা নতুন অস্থায়ী গুপ্তশব্দৰ বাবে অনুৰোধ কৰিছিল ।',
'resetpass-temp-password'   => 'অস্থায়ী গুপ্তশব্দ:',

# Edit page toolbar
'bold_sample'     => 'শকত পাঠ্য',
'bold_tip'        => 'গুৰুলেখ',
'italic_sample'   => 'তীৰ্যকলেখ',
'italic_tip'      => 'বেঁকা পাঠ্য',
'link_sample'     => 'শিৰোণামা সংযোগ',
'link_tip'        => 'ভিতৰুৱা সংযোগ',
'extlink_sample'  => 'http://www.example.com শীৰ্ষক সংযোগ',
'extlink_tip'     => 'বাহিৰৰ সংযোগ (http:// নিশ্বয় ব্যৱহাৰ কৰিব)',
'headline_sample' => 'শিৰোণামা পাঠ্য',
'headline_tip'    => 'দ্বিতীয় স্তৰৰ শিৰোণামা',
'math_sample'     => 'ইয়াত গণিতীয় সুত্ৰ সুমুৱাওক',
'math_tip'        => 'গণিতীয় সুত্ৰ (LaTeX)',
'nowiki_sample'   => 'নসজোৱা পাঠ্য ইয়াত অন্তৰ্ভুক্ত কৰক',
'nowiki_tip'      => 'ৱিকি-সম্মত সাজ-সজ্জা অৱজ্ঞা কৰক',
'image_tip'       => 'এম্বেডেড ফাইল',
'media_tip'       => 'ফাইল সংযোগ',
'sig_tip'         => 'সময়ৰ সৈতে আপোনাৰ স্বাক্ষৰ',
'hr_tip'          => 'পথালি ৰেখা (কমকৈ ব্যৱহাৰ কৰিব)',

# Edit pages
'summary'                          => 'সাৰাংশ:',
'subject'                          => 'বিষয় / শীৰ্ষক:',
'minoredit'                        => 'এইটো নগন্য সম্পাদনা',
'watchthis'                        => 'এই পৃষ্ঠাটো অনুসৰণ-সূচীভুক্ত কৰক',
'savearticle'                      => 'পৃষ্ঠা সাঁচক',
'preview'                          => 'খচৰা',
'showpreview'                      => 'খচৰা চাওঁক',
'showlivepreview'                  => 'জীৱন্ত খচৰা',
'showdiff'                         => 'সালসলনিবোৰ দেখুৱাওক',
'anoneditwarning'                  => "'''সাৱধান:''' আপুনি প্ৰৱেশ কৰা নাই । 
এই পৃষ্ঠাৰ ইতিহাসত আপোনাৰ আই পি ঠিকনা সংৰক্ষিত কৰা হ'ব।",
'anonpreviewwarning'               => "''আপুনি প্ৰৱেশ কৰা নাই । আপুনাৰ সম্পদনা সাঁচিলে আপুনাৰ আই-পি ঠিকনা এই পৃষ্ঠাৰ ইতিহাসত সংৰক্ষিত কৰা হব।\"",
'missingsummary'                   => "'''স্মাৰক:''' আপুনি সম্পাদনা সাৰাংশ দিয়া নাই।
আপুনি আৰু এবাৰ সংৰক্ষণৰ বাবে ক্লীক কৰিলে সাৰাংশৰ অবিহনে সংৰক্ষিত হব।",
'missingcommenttext'               => 'অনুগ্ৰহ কৰি তলত মন্তব্য এটা দিয়্ক।',
'missingcommentheader'             => "'''স্মাৰক:''' আপুনি এই মন্তব্যটোত শিৰোণামা দিয়া নাই।
যদি আকৌ এবাৰ যদি \"{{int:savearticle}}\" টিপে, তেনেহলে সম্পাদনা শিৰোণামা অবিহনে সংৰক্ষিত হব।",
'summary-preview'                  => 'সাৰাংশৰ খচৰা:',
'subject-preview'                  => 'বিষয়/শিৰোণামাৰ খচৰা:',
'blockedtitle'                     => 'সদস্যজনক অবৰোধ কৰা হৈছে',
'blockedtext'                      => "'''আপোনাৰ সদস্যনাম অথবা আই-পি ঠিকণা অবৰোধ কৰা হৈছে ।'''

$1ৰ দ্বাৰ এই অবৰোধ কৰা হৈছে ।
ইয়াৰ বাবে দিয়া কাৰণ হৈছে ''$2'' ।

* অবৰোধ আৰম্ভনী: $8
* অবৰোধ সমাপ্তি: $6
* অবৰোধ কৰা হৈছে: $7

আপুনি এই অবৰোধৰ বিষয়ে আলোচনা কৰিবলৈ $1 বা [[{{MediaWiki:Grouppage-sysop}}|প্ৰবন্ধকৰ]] লগত সম্পৰ্ক স্থাপন কৰিব পাৰে ।
আপুনি যেতিয়ালৈ [[Special:Preferences|সদস্য পছন্দ]] পৃষ্ঠাত আপোনাৰ ই-মেইল ঠিকনা নিদিয়ে তেতিয়ালৈ ’সদস্যক ই-মেইল পঠাওক’ সুবিধাতো ব্যৱহাৰ কৰিব নোৱাৰিব, আৰু আপোনাক এয়া কৰিবলৈ ৰোধ কৰা হোৱা নাই ।
আপোনাৰ এতিয়াৰ আই-পি ঠিকনা হল $3, আৰু আপোনাৰ অবৰোধ ক্ৰমিক হৈছে #$5 ।
এই বিষয়ে হোৱা আলোচনাত ইয়াৰ সবিশেষ সদৰী কৰে যেন।",
'autoblockedtext'                  => "আপোনাৰ আই-পি ঠিকনা নিজে নিজে অবৰোধিত হৈ গৈছে, কাৰণ ইয়াক কোনোবাই ব্যৱহাৰ কৰি থাকোতে $1 ৰ দ্বাৰা অবৰোধ কৰা হৈছে।
ইয়াৰ বাবে দিয়া কাৰণ হৈছে:

:''$2''

* অবৰোধ আৰম্ভনী:  $8
* অবৰোধ সমাপ্তি: $6
* অৱৰোধ কৰা হৈছে: $7

আপুনি এই অবৰোধৰ বিষয়ে আলোচনা কৰিবলৈ $1 বা [[{{MediaWiki:Grouppage-sysop}}|প্ৰবন্ধক]]ৰ লগত সম্পৰ্ক স্থাপন কৰিব পাৰে ।

আপুনি যেতিয়ালৈ [[Special:Preferences|সদস্য পছন্দ]] পৃষ্ঠাত আপোনাৰ ই-মেইল ঠিকনা নিদিয়ে তেতিয়ালৈ ’সদস্যক ই-মেইল পঠাওক’ সুবিধাতো ব্যৱহাৰ কৰিব নোৱাৰে। আপোনাক এয়া কৰিবলৈ ৰোধ কৰা হোৱা নাই ।
অপোনাৰ এতিয়াৰ IP ঠিকনা হৈছে $3, অৰু আপোনাৰ অবৰোধ ক্ৰমিক হৈছে $5 ।
এই বিষয়ে হোৱা আলোচনাত ইয়াক ব্যৱহাৰ কৰিবলৈ অনুৰোধ কৰা হল।",
'blockednoreason'                  => 'কাৰণ দিয়া নাই',
'blockedoriginalsource'            => "'''$1''' ৰ উত্‍স তলত দিয়া হৈছে।",
'blockededitsource'                => "'''$1''' ৰ '''আপুনি কৰা সাল-সলনি''' ৰ পাঠ্য তলত দিয়া হৈছে:",
'whitelistedittitle'               => 'সম্পাদনা কৰিবলৈ প্ৰবেশ কৰিব লাগিব।',
'whitelistedittext'                => 'সম্পাদনা কৰিবলৈ $1 কৰক ।',
'confirmedittext'                  => 'সম্পাদনা কৰাৰ আগতে আপুনি আপোনাৰ ই-মেইল ঠিকনাটো প্ৰমানিত কৰিব লাগিব।
অনুগ্ৰহ কৰি [[Special:Preferences|মোৰ পচন্দ]] ত গৈ আপোনাৰ ই-মেইল ঠিকনা দিয়ক আৰু তাক প্ৰমানিত কৰক।',
'nosuchsectiontitle'               => 'এনেকুৱা কোনো বিভাগ নাই',
'nosuchsectiontext'                => 'অপুনি এনে এটা বিভাগ সম্পাদিত কৰিব বিচাৰিছে যাৰ কোনো অস্তিত্ব নাই।',
'loginreqtitle'                    => 'প্ৰবেশ আৱশ্যক',
'loginreqlink'                     => 'প্ৰবেশ',
'loginreqpagetext'                 => 'অন্যান্য পৃষ্ঠা চাবলৈ আপুনি $1 কৰিব লাগিব।',
'accmailtitle'                     => 'গুপ্তশব্দ পঠোৱা হৈছে।',
'accmailtext'                      => "[[User talk:$1|$1]]-ৰ কাৰণে দৈব ভাবে উৎপন্ন কৰা গুপ্তশব্দ $2-লৈ পঠোৱা হ'ল । 
এই নতুন একাউন্টত প্ৰবেশ কৰি ''[[Special:ChangePassword|change password]]'' পৃষ্ঠাখনত গুপ্তশব্দতো সলনি কৰি লব পাৰিব ।",
'newarticle'                       => '(নতুন)',
'newarticletext'                   => 'আপুনি বিচৰা প্ৰবন্ধটো বিচাৰি পোৱা নগল।

ইচ্ছা কৰিলে আপুনিয়েই এই প্ৰবন্ধটো লিখা আৰম্ভ কৰিব পাৰে। [[{{MediaWiki:Helppage}}|ইয়াত]] সহায় পাব।

আপুনি যদি ইয়ালৈ ভুলতে আহিছে, তেনেহলে আপোনাৰ ব্ৰাওজাৰত (BACK) বুতামত টিপা মাৰক।',
'anontalkpagetext'                 => "----''এইখন আলোচনা পৃষ্ঠা বেনামী সদস্যৰ বাবে, যোনে নিজা একাউন্ট  সৃষ্টি কৰা নাই বা যোনে সেই একাউন্ট ব্যৱহাৰ নকৰে ।
এতেকে আমি তেখেতসকলক খেতসকলৰ আই-পি ঠিকনাৰে চিনাক্ত কৰিবলৈ বাধ্য ।
সেই একেই আই-পি ঠিকনা অনেকেই ব্যবহাৰ কৰিব পাৰে ।
আপুনি যদি এজন বেনামী সদস্য আৰু যদি আপুনি অনুভৱ কৰে যে আপুনাৰ প্ৰতি অপ্ৰাসঙ্গিক মন্তব্য কৰা হৈছে, তেনেহলে আন বেনামী সদস্যৰ পৰা পৃথক কৰিবলৈ 
[[Special:UserLogin/signup|একাউন্ট সৃষ্টি কৰক]] বা [[Special:UserLogin|প্ৰৱেশ কৰক]] ।''",
'noarticletext'                    => 'এই পৃষ্ঠাত বৰ্তমান কোনো পাঠ্য নাই ।
আপুনি আন পৃষ্ঠাত [[Special:Search/{{PAGENAME}}| এই শিৰোণামা অনুসন্ধান কৰিব পাৰে]],
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} সম্পৰ্কিয় অভিলেখ অনুসন্ধান কৰিব পাৰে],
বা [{{fullurl:{{FULLPAGENAME}}|action=edit}} এই পৃষ্ঠা সম্পাদনা কৰিব পাৰে]</span>',
'noarticletext-nopermission'       => 'এই পৃষ্ঠাত বৰ্তমান কোনো পাঠ্য নাই ।
আপুনি আন পৃষ্ঠাত [[Special:Search/{{PAGENAME}}| এই শিৰোণামা অনুসন্ধান কৰিব পাৰে]],
বা <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} সম্পৰ্কিয় লগ অনুসন্ধান কৰিব পাৰে ।]</span>',
'userpage-userdoesnotexist'        => '"$1" নামৰ সদস্য একাউন্ট নিবন্ধিত নহয় ।
অনুগ্ৰ কৰি চাওক আপুনি এই পৃষ্ঠা সৃষ্টি/সম্পাদনা কৰিব বিচাৰিছে নেকি ।',
'userpage-userdoesnotexist-view'   => "সনস্য একাউন্ট ''$1'' পঞ্জীভূত নহয়",
'blocked-notice-logextract'        => "বৰ্তমানে এই সদস্যক বাৰণ কৰা হৈছে ।
প্ৰসংগক্ৰমে সাম্প্ৰতিক বাৰণ সূচি তলত দিয়া হ'ল ।",
'updated'                          => "(আপডেট কৰা হ'ল)",
'note'                             => "'''টোকা:'''",
'previewnote'                      => "'''মনত ৰাখিব যে এয়া কেৱল খচৰা হে, সাল-সলনিবোৰ এতিয়াও সংৰক্ষিত কৰা হোৱা নাই!'''",
'session_fail_preview'             => "'''দুঃখিত! চেচন ডাটা হেৰাইযোৱাৰ কাৰণে আপুনাৰ সম্পাদনা কৃতকাৰ্য্য কৰা নহ'ল ।'''
অনুগ্ৰহ কৰি পুনৰ চেষ্টা কৰক ।
তথাপি যদি নহয় [[Special:UserLogout|প্ৰস্থান]] কৰি আকৌ প্ৰৱেশ কৰক ।",
'session_fail_preview_html'        => "'''দুঃখিত! চেচন ডাটা হেৰাইযোৱাৰ কাৰণে আপুনাৰ সম্পাদনা কৃতকাৰ্য্য কৰা নহ'ল ।'''

''যি হেতু {{SITENAME}}-ত নগ্ন এইচ-টি-এম-এল (raw HTML) সক্ৰিয় কৰা আছে, জাভাস্ক্ৰিপ্ট (Javasccript) আক্ৰমণৰ বিৰুদ্ধে সতৰ্কতাৰ খাতিৰত খচৰা আঁৰ কৰা হৈছে ।''

'''এইয়া যদি এটা বৈধ সম্পাদনা আছিল, তেনে আকৌ চেষ্টা কৰক ।'''
তথাপি যদি নহয় [[Special:UserLogout|প্ৰস্থান]] কৰি আকৌ প্ৰৱেশ কৰক ।",
'editing'                          => '$1 সম্পাদনা',
'editingsection'                   => '$1 (বিভাগ) সম্পদনা কৰি থকা হৈছে',
'editingcomment'                   => '$1 (নতুন বিভাগ) সম্পদনা কৰি থকা হৈছে',
'editconflict'                     => 'সম্পাদনা দ্বন্দ্ব: $1',
'explainconflict'                  => "আপুনি সম্পাদনা আৰম্ভ কৰাৰ পাছত আন কোনোবাই এই পৃষ্ঠাখন সমলি কৰিলে ।
পাঠ্য-স্থানৰ উপৰ ভাগত এই পৃষ্ঠাৰ প্ৰচলিত পাঠ্য দিয়া হৈছে ।
আপুনাৰ সলনিসমূহ পাঠ্য-স্থানৰ তলৰ ভাগত দেখেৱা হৈছে ।
আপুনি আপুনাৰ সালসলনিসমূহ প্ৰচলিত পাঠ্যত অন্তৰভুক্ত কৰিব পাৰে ।
আপুনি \"{{int:savearticle}}\" টিপিলে '''কেৱল''' পাঠ্য-স্থানৰ উপৰ ভাগৰ অংশ খিনিহে সংৰক্ষিত হ'ব ।",
'yourtext'                         => 'আপুনাৰ লিখা পাঠ',
'storedversion'                    => 'জমা সংস্কৰণ',
'editingold'                       => "'''সাৱধান: আপুনি এই পৃষ্ঠাৰ এটি পুৰণি সংস্কৰণ সম্পাদনা কৰি আছে ।
যদি আপুনি আপোনাৰ সম্পাদনাসমূহ জমা কৰে, সেই পৰৱৰ্তী সংস্কৰণসমূহ হেৰাই যাব ‌‌।'''",
'yourdiff'                         => 'তফাৎ',
'copyrightwarning'                 => "অনুগ্ৰহ কৰি মন কৰক যে {{SITENAME}}লৈ কৰা সকলো বৰঙণি $2 ৰ চৰ্তাৱলীৰ মতে প্ৰদান কৰা বুলি ধৰি লোৱা হব (আৰু অধিক জানিবলৈ $1 চাঁওক)। যদি আপুনি আপোনাৰ লিখনি নিৰ্দয়ভাৱে সম্পাদনা কৰা আৰু ইচ্ছামতে পুনৰ্বিতৰণ কৰা ভাল নাপায়, তেনেহ'লে নিজৰ লিখনি ইয়াত নিদিব।
<br />

ইয়াত আপোনাৰ লিখনি দিয়াৰ লগে লগে আপুনি আপোনা-আপুনি প্ৰতিশ্ৰুতি দিছে যে এই লিখনিটো আপোনাৰ মৌলিক লিখনি, বা কোনো স্বত্বাধিকাৰ নথকা বা কোনো ৰাজহুৱা ৱেবছাইট বা তেনে কোনো মুকলি উৎসৰ পৰা আহৰণ কৰা।
'''স্বত্বাধিকাৰযুক্ত কোনো সমল অনুমতি অবিহনে দাখিল নকৰে যেন!'''",
'copyrightwarning2'                => "অনুগ্ৰহ কৰি মন কৰক যে {{SITENAME}}লৈ কৰা সকলো বৰঙণি আন সদস্যই সম্পাদনা কৰিব, সলনি কৰিব অথবা মচি পেলাব পাৰে ।
আপুনি যদি আপোনাৰ লিখনি নিৰ্দয়ভাৱে সম্পাদনা কৰা ভাল নাপায়, তেনেহলে নিজৰ লিখনি ইয়াত নিদিব ।<br />
ইয়াত আপোনাৰ লিখনি দিয়াৰ লগে লগে আপুনি আপোনা-আপুনি প্ৰতিশ্ৰুতি দিছে যে এই লিখনিটো আপোনাৰ মৌলিক লিখনি, বা কোনো স্বত্বাধিকাৰ নথকা বা কোনো ৰাজহুৱা ৱেবছাইট বা তেনে কোনো মুকলি উৎসৰ পৰা আহৰণ কৰা| (অধিক জানিবলৈ $1 চাঁওক)

'''স্বত্বাধিকাৰযুক্ত কোনো সমল অনুমতি অবিহনে দাখিল নকৰে যেন!'''",
'longpageerror'                    => "'''ভুল: আপুনি দিয়া লিখনী $1 কিলো-বাইট আকাৰৰ, যি $2 কিলো-বাইট সীমাটকৈ বেছি।
ইয়াক সঞ্চিত কৰিব পৰা নাযাব।'''",
'protectedpagewarning'             => "সকিয়নি: এই পৃষ্ঠা বন্ধ ৰখা হৈছে; কেৱল এডমিনিষ্ট্ৰেটৰ মৰ্যদাৰ সদস্যই হে সম্পাদনা কৰিব পাৰিব ।'''
আপোনাৰ সুবিধাৰ বাবে পৃষ্ঠাৰ সাম্প্ৰতিক ল'গ সংৰক্ষণ তলত ডিয়া হ'ল ।",
'semiprotectedpagewarning'         => "টোকা: এই পৃষ্ঠা বন্ধ ৰখা হৈছে; কেৱল পঞ্জীভূত সদস্যই হে সম্পাদনা কৰিব পাৰিব ।
আপোনাৰ সুবিধাৰ বাবে পৃষ্ঠাৰ সাম্প্ৰতিক ল'গ সংৰক্ষণ তলত দিয়া হ'ল ।",
'templatesused'                    => 'এই পৃষ্ঠাত ব্যৱহৃত {{PLURAL:$1|ঠাঁচ॥ঠাঁচ সমূহ}}:',
'templatesusedpreview'             => 'এই খচৰাত ব্যৱহৃত {{PLURAL:$1|ঠাঁচ|ঠাঁচ সমূহ}}:',
'templatesusedsection'             => 'এই দফাত ব্যৱহৃত {{PLURAL:$1|ঠাঁচ॥ঠাঁচ সমূহ}}:',
'template-protected'               => '(সুৰক্ষিত)',
'template-semiprotected'           => '(অৰ্ধ-সুৰক্ষিত)',
'hiddencategories'                 => 'এই পৃষ্ঠা {{PLURAL:$1|১-টা নিহিত শ্ৰেণীৰ|$1-টা নিহিত শ্ৰেণীৰ}} সদস্য:',
'nocreatetitle'                    => 'পৃষ্ঠা সৃষ্টি সিমিত',
'nocreatetext'                     => '{{SITENAME}} ত নতুন লিখনী লিখা ৰদ কৰা হৈছে।
আপুনি ঘুৰি গৈ অস্তিত্বত থকা পৃষ্ঠা এটা সম্পাদনা কৰিব পাৰে, বা [[Special:UserLogin| নতুন সদস্যভৰ্তি হওক/ প্ৰবেশ কৰক]] ।',
'nocreate-loggedin'                => 'নতুন পৃষ্ঠা সৃষ্টি কৰিবলৈ আপুনাৰ অনুমতি নাই ।',
'sectioneditnotsupported-title'    => 'অনুচ্ছেদ সম্পাদনাৰ সমৰ্থন নাই',
'sectioneditnotsupported-text'     => 'এই পৃষ্ঠাত অনুচ্ছেদ সম্পাদনাৰ সমৰ্থন নাই',
'permissionserrors'                => 'অনুমতি ভুলসমূহ',
'permissionserrorstext'            => "আপুনাৰ এই কৰিবলৈ অনুমতি নাই, যাৰ {{PLURAL:$1|কাৰণ|কাৰণসমূহ}} হ'ল:",
'permissionserrorstext-withaction' => "আপুনাৰ $2 কৰিবলৈ অনুমতি নাই, যাৰ {{PLURAL:$1|কাৰণ|কাৰণসমূহ}} হ'ল:",
'recreate-moveddeleted-warn'       => "'''সাৱধান: আগতে বিলোপিত কৰা পৃষ্ঠা এটা আপুনি পূণঃনিৰ্মান কৰি আছে। '''

এই পৄষ্ঠাটো সম্পাদনা কৰা উচিত হব নে নহয় আপুনি বিবেচনা কৰি চাওক।
এই পৃষ্ঠাটো বিলোপ আৰু স্থানান্তৰ কৰাৰ অভিলেখ আপোনাৰ সুবিধাৰ্থে ইয়াত দিয়া হৈছে।",
'moveddeleted-notice'              => "এই পৃষ্ঠা বাতিল কৰা হৈছে ।
পৃষ্ঠাটিৰ বাতিল আৰু স্থানান্তৰ কৰা ল'গ তলত দিয়া হ'ল ।",
'log-fulllog'                      => 'সম্পূৰ্ণ অভিলেখ চাওঁক',
'edit-conflict'                    => 'সম্পাদনা দ্বন্দ ।',
'edit-no-change'                   => 'আপোনাৰ সম্পাদনা আওকাণ কৰা হৈছে, কাৰণ লেখাত কোনো তফাৎ নাই',
'edit-already-exists'              => "নতুন পৃষ্ঠা সৃষ্টি কৰা নহ'ল ।
পৃষ্ঠাখন ইতিমধ্যেই আছেই ।",

# "Undo" feature
'undo-success' => 'এই সমপাদনা পূৰ্ববৎ কৰিব পাৰি ।
অনুগ্ৰহ কৰি তলৰ তুলনাটি পৰীক্ষা কৰি ঠাৱৰ কৰক যে এইয়েই আপুনি কৰিব বিচাৰিছে, আৰু তলত সালসলনীসমূহ যমা কৰি এই কাৰ্য্য সম্পন্ন কৰক ।',
'undo-failure' => "এই সমপাদনা দ্বন্দিক মধ্যবৰ্তী সম্পাদনাসমূহৰ কাৰণে পূৰ্ববৎ কৰা নহ'ব ।",
'undo-norev'   => "এই সম্পাদনাটি ৰদ কৰিব নোৱাৰি, কাৰণ ই আৰু নাই বা ইয়াক বাতিল কৰা হ'ল ।",
'undo-summary' => '[[Special:Contributions/$2|$2]]ৰ ([[User talk:$2|আলাপ]]) সম্পাদিত $1 সংশোধনটি বাতিল কৰক',

# Account creation failure
'cantcreateaccounttitle' => "একাউন্ট সৃষ্টি কৰা নহ'ব",

# History pages
'viewpagelogs'           => 'এই পৃষ্ঠাৰ অভিলেখ চাঁওক ।',
'nohistory'              => 'এই পৃষ্ঠাৰ কোন সম্পাদনাৰ ইতিহাস নাই।',
'currentrev'             => 'শেহতীয়া ভাষ্য',
'currentrev-asof'        => '$1 অনুযায়ী বৰ্তমান সংস্কৰণ',
'revisionasof'           => '$1 ৰ সংস্কৰণ',
'revision-info'          => '$1-লৈ $2-এ কৰা সংশোধন',
'previousrevision'       => '← আগৰ সংশোধন',
'nextrevision'           => 'সদ্যসংশোধিত',
'currentrevisionlink'    => 'শেহতীয়া ভাষ্য',
'cur'                    => 'বৰ্তমান',
'next'                   => 'পৰবৰ্তী',
'last'                   => 'পুৰ্ববৰ্তি',
'page_first'             => 'প্ৰথম',
'page_last'              => 'অন্তিম',
'histlegend'             => "পাৰ্থক্য বাচনী: পাৰ্থক্য চাবলৈ সংকলনবোৰৰ সম্মুখত থকা ৰেডিও বুটামবোৰ বাচনী কৰি এণ্টাৰ টিপক অথবা একেবাৰে তলত দিয়া বুটামতো ক্লীক কৰক <br />
লিজেণ্ড: '''({{int:cur}})''' = বৰ্তমানৰ সংকলনৰ লগত পাৰ্থক্য,
'''({{int:last}})''' = আগৰ সংকলনৰ লগত পাৰ্থক্য, '''{{int:minoreditletter}}'' = অগুৰুত্বপুৰ্ণ সম্পাদনা।",
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
'rev-deleted-comment'         => "(সম্পাদন সাৰাংশ আতৰোৱা হ'ল)",
'rev-deleted-user'            => '(সদস্যনাম আতৰোৱা হৈছে)',
'rev-deleted-text-permission' => "পৃষ্ঠাৰ এই সংশোধনটি '''বাতিল''' কৰা হ'ল ।
সবিশেষ পাব [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} অবলুপ্তি ল'গত]",
'rev-delundel'                => 'দেখুৱাওক/নেদেখুৱাব',
'rev-showdeleted'             => 'দেখুওৱাওক',
'revisiondelete'              => 'সংকলন বিলোপন কৰক / পুণৰ্স্থাপিত কৰক',
'revdelete-no-file'           => 'নিৰ্ধাৰিত ফাইলটি নাই ।',
'revdelete-show-file-submit'  => 'হয়',
'revdelete-selected'          => "'''[[:$1]]-ৰ {{PLURAL:$2|নিৰ্বাচিত সংশোধন|নিৰ্বাচিত সংশোধনসমূহ}}:'''",
'revdelete-legend'            => 'দৃষ্টিপাত সীমাবদ্ধ কৰক',
'revdelete-hide-text'         => 'সংশোধিত পাঠ আঁতৰাওক',
'revdelete-hide-image'        => 'ফাইলৰ বিষয়বস্তু আঁতৰাওক',
'revdelete-hide-name'         => 'কাৰ্য্য আৰু লক্ষ্য আতৰাই থওঁক',
'revdelete-hide-comment'      => 'সম্পাদনা মন্তব্য আতৰাই থওঁক',
'revdelete-hide-user'         => 'সম্পাদকৰ সদস্যনাম/আই-পি টিকনা আতৰাই থওঁক',
'revdelete-hide-restricted'   => 'প্রশাসকবৃন্দৰ লগতে আনৰ পৰাও তথ্য ৰোধ কৰক',
'revdelete-radio-same'        => '(সলনি নকৰিব)',
'revdelete-radio-set'         => 'অঁ',
'revdelete-radio-unset'       => 'না',
'revdelete-suppress'          => 'প্রশাসকবৃন্দৰ লগতে আনৰ পৰাও তথ্য ৰোধ কৰক',
'revdelete-unsuppress'        => 'পুনৰ্স্থাপন কৰা সংশোধনসমূহৰ সীমাবদ্ধতা আতৰাওঁক',
'revdelete-log'               => 'কাৰণ:',
'revdel-restore'              => 'দৃষ্টিপাত সালসলনি কৰক',
'revdel-restore-deleted'      => 'বাতিল কৰা সংশোধনসমূহ',
'revdel-restore-visible'      => 'দৃশ্যমান সংশোধনসমূহ',
'pagehist'                    => 'পৃষ্ঠা ইতিহাস',
'deletedhist'                 => 'মচি পেলোৱা ইতিহাস',
'revdelete-content'           => 'বিষয়বস্তু',
'revdelete-summary'           => 'সম্পাদনাৰ সাৰমৰ্ম',
'revdelete-uname'             => 'সদস্যনাম',
'revdelete-hid'               => '$1 আঁৰ কৰক',
'revdelete-unhid'             => '$1 দেখোৱাওক',
'revdelete-log-message'       => '$2 {{PLURAL:$2|সংশোধন|সংশোধনসমূহ}}ৰ বাবে $1',
'revdelete-otherreason'       => 'অন্য/অতিৰিক্ত কাৰণ:',
'revdelete-reasonotherlist'   => 'অন্য কাৰণ',
'revdelete-edit-reasonlist'   => 'অপসাৰণৰ কাৰণ সম্পাদনা',
'revdelete-offender'          => 'সংশোধন লেখক:',

# Revision move
'revmove-legend'         => 'লক্ষ্য পৃষ্ঠা আৰু সাৰাংশ ধায্য কৰক',
'revmove-reasonfield'    => 'কাৰণ:',
'revmove-titlefield'     => 'লক্ষ্য পৃষ্ঠা:',
'revmove-nullmove-title' => 'শিৰোনামাটি গ্ৰহণযোগ্য নহয় ।',

# History merging
'mergehistory-from'             => 'উৎস পৃষ্ঠা',
'mergehistory-into'             => 'গন্তব্য পৃষ্ঠা',
'mergehistory-go'               => 'একত্ৰীকৰণযোগ্য সম্পাদনাসমূহ দেখোৱাওঁক',
'mergehistory-no-source'        => '$1 নামৰ কোনো উৎস পৃষ্ঠৰ অস্তিত্ব নাই ।',
'mergehistory-no-destination'   => '$1 নামৰ কোকো গন্তব্য পৃষ্ঠাৰ অস্তিত্ব নাই ।',
'mergehistory-autocomment'      => "[[:$1]]ক [[:$2]]ত অন্তৰ্ভুক্ত কৰা হ'ল",
'mergehistory-comment'          => "[[:$1]]ক [[:$2]]ত অন্তৰ্ভুক্ত কৰা হ'ল: $3",
'mergehistory-same-destination' => "উৎস আৰু গন্তব্য পৃষ্ঠা একে হ'ব নোৱাৰে",
'mergehistory-reason'           => 'কাৰণ:',

# Merge log
'mergelog'    => 'অভিলেখ একত্ৰিকৰণ',
'revertmerge' => 'একত্ৰিকৰণ বাতিল কৰক',

# Diffs
'history-title'           => '"$1" ৰ সাল-সলনিৰ ইতিহাস',
'difference'              => 'বিভিন্ন সংস্কৰণৰ প্ৰভেদ',
'lineno'                  => '$1 নং শাৰীঃ',
'compareselectedversions' => 'নিৰ্বাচিত কৰা সংকলন সমূহৰ মাজত পাৰ্থক্য চাঁওক ।',
'editundo'                => 'পূৰ্ববত কৰক',

# Search results
'searchresults'                  => 'অনুসন্ধানৰ ফলাফল',
'searchresults-title'            => '"$1" অনুসন্ধানৰ ফলাফল',
'searchresulttext'               => '{{SITENAME}}ৰ বিষয়ে বিতংকৈ জানিবলৈ [[{{MediaWiki:Helppage}}|{{int:help}}]] চাওঁক ।',
'searchsubtitle'                 => 'আপুনি অনুসন্ধান কৰিছে \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|"$1" দি আৰম্ভ হোৱা পৃষ্ঠাসমূহ]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|"$1" লগত সংযুক্ত পৃষ্ঠাসমূহ]])',
'searchsubtitleinvalid'          => "আপুনাৰ অনুসন্ধান হ'ল '''$1'''",
'toomanymatches'                 => 'বহুত বেছি মিল পোৱা গৈছে, সন্ধাণ-শব্দ সলনি কৰিবলৈ অনুৰোধ কৰা হল',
'titlematches'                   => 'পৃষ্ঠাৰ শিৰোণামা মিলিছে',
'notitlematches'                 => 'এটাও পৃষ্ঠাৰ শিৰোনামা মিলা নাই',
'textmatches'                    => 'লিখনীৰ পাঠ্য মিলিছে',
'notextmatches'                  => 'এটা লিখনিৰো পাঠ্য মিলা নাই',
'prevn'                          => 'পূৰ্ববৰ্ত্তী {{PLURAL:$1|$1}}টা',
'nextn'                          => 'পৰবৰ্ত্তী {{PLURAL:$1|$1}}টা',
'prevn-title'                    => 'আগৰ $1 {{PLURAL:$1|ফলাফল|ফলাফলবোৰ}}',
'nextn-title'                    => 'পিছৰ $1 {{PLURAL:$1|ফলাফল|ফলাফলবোৰ}}',
'shown-title'                    => 'প্ৰতি পৃষ্ঠায় $1 {{PLURAL:$1|ফলাফল|ফলাফল}} দেখুৱাওক',
'viewprevnext'                   => '($1 {{int:pipe-separator}} $2) ($3) চাওক।',
'searchmenu-legend'              => 'সন্ধান বিকল্পসমূহ',
'searchmenu-exists'              => 'এই ৱিকিত "[[:$1]]" নামৰ পৃষ্ঠা এখন আছে ।',
'searchmenu-new'                 => "'''এই ৱিকিত \"[[:\$1]]\" পৃষ্ঠাখন সৃষ্টি কৰক!'''",
'searchhelp-url'                 => 'Help:সুচী',
'searchprofile-articles'         => 'সূচিপত্ৰসমূহ',
'searchprofile-project'          => 'সহায় আৰু প্ৰকল্প পৃষ্ঠাসমূহ',
'searchprofile-images'           => 'মাল্টিমিডিয়া',
'searchprofile-everything'       => 'সকলো',
'searchprofile-advanced'         => 'উচ্চতৰ',
'searchprofile-articles-tooltip' => '$1-ট অনুসন্ধান কৰক',
'searchprofile-project-tooltip'  => '$1-ত অনুসন্ধান',
'searchprofile-images-tooltip'   => 'ফাইলৰ বাবে অনুসন্ধান',
'search-result-size'             => '$1 ({{PLURAL:$2|1 শব্দ|$2 শব্দসমূহ}})',
'search-result-score'            => 'যথাৰ্থতা: $1%',
'search-redirect'                => '(পুনৰ্নিদেশনা $1)',
'search-section'                 => '(অনুচ্ছেদ $1)',
'search-suggest'                 => 'আপুনি $1 বুজাব খুজিছে নেকি?',
'search-interwiki-caption'       => 'সহপ্ৰকল্পসমূহ',
'search-interwiki-default'       => '$1 ফলাফলসমূহ:',
'search-interwiki-more'          => '(আৰু)',
'search-mwsuggest-enabled'       => 'উপদেশ সহ',
'search-mwsuggest-disabled'      => 'উপদেশ নাই',
'search-relatedarticle'          => 'সম্পৰ্কিত',
'searchrelated'                  => 'সম্পৰ্কিত',
'searchall'                      => 'সকলো',
'showingresults'                 => "তলত #'''$2'''ৰ পৰা {{PLURAL:$1|'''1''' ফলাফল|'''$1''' ফলাফল}} দেখুওৱা হৈছে।",
'nonefound'                      => "'''টোকা:''' ডিফ’ল্ট অনুযায়ী মাথোঁ কেইটামান হে নামস্থান অনুসন্ধান কৰা হয় ।
আপোনাৰ অনুসন্ধানত ''all:'' ব্যবহাৰ কৰি সকলো সমল (কথা-বতৰা, শ্ৰেনী ইত্যদি) অনুসন্ধান কৰিব পাৰে, নতুবা আকাংক্ষিত নামস্থান প্ৰিফিক্স হিচাবে ব্যবহাৰ কৰিব পাৰে ।",
'search-nonefound'               => 'এই অনুসন্ধানৰ কোনো ফলাফল নাই ।',
'powersearch'                    => 'অতিসন্ধান',
'powersearch-legend'             => 'শক্তিশালী সন্ধান',
'powersearch-ns'                 => 'নামস্থানবোৰত সন্ধান:',
'powersearch-redir'              => 'পুননিৰ্দেশকৰ তালিকা',
'powersearch-field'              => 'ৰ কাৰণে সন্ধান কৰক',
'powersearch-togglelabel'        => 'চেক:',
'powersearch-toggleall'          => 'সকলো',
'powersearch-togglenone'         => 'একো নাই',
'search-external'                => 'বাহ্যিক সন্ধান',
'searchdisabled'                 => '{{SITENAME}} ত অনুসন্ধান কৰা সাময়িক ভাবে নিষ্ক্ৰিয় কৰা হৈছে।
তেতিয়ালৈকে গুগলত অনুসন্ধান কৰক।
মনত ৰাখিব যে তেঁওলোকৰ {{SITENAME}}ৰ ইণ্ডেক্স পুৰণি হব পাৰে।',

# Quickbar
'qbsettings'               => 'শীঘ্ৰদণ্ডিকা',
'qbsettings-none'          => 'একেবাৰে নহয়',
'qbsettings-fixedleft'     => 'বাঁওফাল স্থিৰ',
'qbsettings-fixedright'    => 'সোঁফাল স্থিৰ',
'qbsettings-floatingleft'  => 'বাঁওফাল অস্থিৰ',
'qbsettings-floatingright' => 'সোঁফাল অস্থিৰ',

# Preferences page
'preferences'                 => 'ৰুচি',
'mypreferences'               => 'মোৰ পচন্দ',
'prefs-edits'                 => 'সম্পাদনা সমূহৰ সংখ্যা:',
'prefsnologin'                => 'প্ৰৱেশ কৰা নাই',
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
'prefs-watchlist-edits'       => 'বৰ্ধিত লক্ষ্যসুচীত দেখুৱাব লগা সৰ্বোচ্চ সাল-সলনী:',
'prefs-watchlist-edits-max'   => 'সৰ্বোচ্চ নম্বৰ: ১০০০',
'prefs-watchlist-token'       => 'লক্ষ্যতালিকা টোকেন:',
'prefs-misc'                  => 'অন্যান্য',
'prefs-resetpass'             => 'গুপ্তশব্দ শলনি কৰক',
'prefs-email'                 => 'ই-মেইল বিকল্প',
'prefs-rendering'             => 'ৰূপ',
'saveprefs'                   => 'সঞ্চিত কৰক',
'resetprefs'                  => 'অসঞ্চিত সাল-সলনী বাতিল কৰক',
'prefs-editing'               => 'সম্পাদন',
'prefs-edit-boxsize'          => 'সম্পাদনা ৱিন্ডোৰ আকাৰ',
'rows'                        => 'পথালী শাৰী:',
'columns'                     => 'ঠিয় শাৰী:',
'searchresultshead'           => 'অনুসন্ধান',
'resultsperpage'              => 'প্ৰতি পৃষ্ঠা দৰ্শন:',
'contextlines'                => 'প্ৰতি শাৰী দৰ্শন:',
'contextchars'                => 'প্ৰতি শাৰীত সন্দৰ্ভ:',
'stub-threshold'              => '<a href="#" class="stub">আধাৰ সংযোগ</a> ৰ সৰ্বোচ্চ আকাৰ (বাইটত):',
'stub-threshold-disabled'     => 'নিস্ক্ৰিয়',
'recentchangesdays'           => 'শেহতীয়া সাল-সলনীত দেখুৱাব লগা দিন:',
'recentchangesdays-max'       => 'সৰ্বোচ্চ $1 {{PLURAL:$1|দিন|দিন}}',
'recentchangescount'          => 'শেহতীয়া সাল-সলনী, ইতিহাস আৰু লগ পৃষ্ঠাত দেখুৱাব লগা সম্পাদনাৰ সংখ্যা:',
'savedprefs'                  => 'আপোনাৰ পচন্দসমূহ সংৰক্ষিত কৰা হল।',
'timezonelegend'              => 'সময় স্থান',
'localtime'                   => 'স্থানীয় সময়:',
'timezoneuseserverdefault'    => 'চাৰ্ভাৰ ডিফ’ল্ট ব্যবহাৰ কৰক',
'timezoneuseoffset'           => 'অন্য (অফচেট ধাৰ্য কৰক)',
'timezoneoffset'              => 'অফচেট¹:',
'servertime'                  => 'চাৰ্ভাৰৰ সময়:',
'guesstimezone'               => 'ব্ৰাউজাৰৰ পৰা ভৰাওক',
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
'allowemail'                  => 'অন্য সদস্যৰ পৰা ই-মেইল সমৰ্থ কৰক',
'prefs-searchoptions'         => 'সন্ধান বিকল্পসমূহ',
'prefs-namespaces'            => 'নামস্থান',
'defaultns'                   => 'অন্যথা এই নামস্থান সমূহত অনুসন্ধান কৰিব:',
'default'                     => 'অবিচল',
'prefs-files'                 => 'ফাইলসমূহ',
'prefs-emailconfirm-label'    => 'ইমেইল নিশ্চিতকৰণ:',
'youremail'                   => 'আপোনাৰ ই-মেইল *',
'username'                    => 'সদস্যনাম:',
'uid'                         => 'সদস্য চিহ্ন:',
'prefs-memberingroups'        => 'এই {{PLURAL:$1|গোটৰ|গোটবোৰৰ}} সদস্য:',
'prefs-registration'          => 'ভক্তিকৰণৰ সময়:',
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
'prefs-help-realname'         => 'আপোনাৰ আচল নাম দিয়াতো জৰুৰী নহয়, কিন্তু দিলে আপোনাৰ কামবোৰ আপোনাৰ নামত দেখুওৱা হব।',
'prefs-help-email'            => 'ই-মেইল ঠিকনা দিয়া বৈকল্পিক, কিন্তু দিলে আন সদস্যই আপোনাৰ চিনাকি নোপোৱাকৈয়ে আপোনাৰ লগত সম্পৰ্ক স্থাপন কৰিব পাৰিব।',
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
'prefs-displaysearchoptions'  => 'বিকল্প প্ৰদৰ্শন কৰক',
'prefs-displaywatchlist'      => 'বিকল্প প্ৰদৰ্শন কৰক',
'prefs-diffs'                 => 'পাৰ্থক্য',

# User rights
'userrights'                  => 'সদস্যৰ অধিকাৰ ব্যৱস্থাপনা',
'userrights-lookup-user'      => 'সদস্য গোটবোৰ ব্যৱস্থাপনা কৰক',
'userrights-user-editname'    => 'সদস্যনাম দিয়ক:',
'editusergroup'               => 'সদস্য গোটবোৰ সম্পাদনা কৰক',
'editinguser'                 => "'''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]]) সদস্যজনৰ অধিকাৰ সলনী কৰি থকা হৈছে।",
'userrights-editusergroup'    => 'সদস্য গোট সম্পাদনা কৰক',
'saveusergroups'              => 'সদস্য গোট সংৰক্ষিত কৰক',
'userrights-groupsmember'     => 'এই গোটবোৰৰ সদস্য:',
'userrights-reason'           => 'কাৰণ:',
'userrights-changeable-col'   => 'আপুনি সলনি কৰিব পৰা গোটসমূহ',
'userrights-unchangeable-col' => 'আপুনি সলনি কৰিব নোৱাৰা গোটসমূহ',

# Groups
'group'            => 'গোট:',
'group-user'       => 'সদস্যসকল',
'group-bot'        => 'বট',
'group-sysop'      => 'এডমিনিষ্ট্ৰেটৰসকল',
'group-bureaucrat' => 'ব্যুৰোক্ৰেটসকল',
'group-all'        => '(সকলো)',

'group-user-member'       => 'সদস্য',
'group-bot-member'        => 'বট',
'group-sysop-member'      => 'এডমিনিষ্ট্ৰেটৰ',
'group-bureaucrat-member' => 'ব্যুৰোক্ৰেট',

'grouppage-user'       => '{{ns:project}}:সদস্যসকল',
'grouppage-bot'        => '{{ns:project}}:বটসমূহ',
'grouppage-sysop'      => '{{ns:project}}:প্ৰবন্ধক',
'grouppage-bureaucrat' => '{{ns:project}}:ব্যুৰোক্ৰেটসকল',

# Rights
'right-read'               => 'পৃষ্ঠাসমূহ পঢ়ক',
'right-edit'               => 'পৃষ্ঠাসমূহ সম্পাদনা কৰক',
'right-createpage'         => 'পৃষ্ঠাসমূহ সৃষ্টি কৰক (কথাবতৰা পৃষ্ঠা নহয়)',
'right-createtalk'         => 'কথাবতৰা পৃষ্ঠা সৃষ্টি কৰক',
'right-createaccount'      => 'নতুন সদস্য একাউন্ট সৃষ্টি কৰক',
'right-move'               => 'পৃস্থাসমূহ স্থানান্তৰ কৰক',
'right-move-rootuserpages' => 'ৰুট সদস্যৰ পৃষ্ঠাসমূহ স্থানান্তৰ কৰক',
'right-movefile'           => 'ফাইল স্থানান্তৰ কৰক',
'right-upload'             => 'ফাইল আপলোড কৰক',
'right-delete'             => 'পৃষ্ঠাসমূহ বিলোপ কৰক',

# User rights log
'rightslog' => 'সভ্যৰ অধিকাৰৰ লেখ',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'               => 'এই পৃষ্ঠা পঢ়ক',
'action-edit'               => 'এই পৃষ্ঠা সম্পাদনা কৰক',
'action-createpage'         => 'পৃষ্ঠা সৃষ্টি কৰক',
'action-createtalk'         => 'আলোচনা পৃষ্ঠা সৃষ্টি কৰক',
'action-createaccount'      => 'এই সদস্য একাউন্ট  সৃষ্টি কৰক',
'action-move'               => 'এই পৃষ্ঠা স্থানান্তৰ কৰক',
'action-move-rootuserpages' => 'ৰুট সদস্যৰ পৃষ্ঠাসমূহ স্থানান্তৰ কৰক',
'action-movefile'           => 'এই ফাইল স্থানান্তৰ কৰক',
'action-upload'             => 'এই ফাইল আপলোড কৰক',
'action-upload_by_url'      => 'এই ফাইল ইউ-আৰ-এল-ৰ পৰা আপলোড কৰক',
'action-delete'             => 'এই পৃষ্ঠা বিলোপ কৰক',
'action-deleterevision'     => 'এই সংশোধন বিলোপ কৰক',
'action-browsearchive'      => 'বিলোপ কৰা পৃষ্ঠা অনুসন্ধান কৰক',
'action-undelete'           => 'এই পৃষ্ঠা পুনৰুদ্ধাৰ কৰক',
'action-block'              => 'এই সদস্যক সম্পাদনা কৰাৰ পৰা বাৰণ কৰক',
'action-userrights'         => 'সকলো সদস্য অধিকাৰ সম্পাদনা কৰক',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|সাল-সলনি|সাল-সলনি}}',
'recentchanges'                  => 'শেহতীয়া কাম',
'recentchanges-legend'           => 'সাম্প্ৰতিক সালসলনিৰ পছন্দসমূহ',
'recentchanges-feed-description' => 'ৱিকিত হোৱা শেহতিয়া সাল-সলনি এই ফীডত অনুসৰন কৰক।',
'recentchanges-label-minor'      => 'এইটো নগন্য সম্পাদনা',
'recentchanges-label-bot'        => 'এইয়া বট দ্বাৰা সম্পাদিত',
'rcnote'                         => "যোৱা {{PLURAL:$2|দিনত|'''$2''' দিনত}} সংঘটিত {{PLURAL:$1|'''১'''|'''$1'''}}টা সালসলনি, $5, $4 পৰ্যন্ত ।",
'rcnotefrom'                     => "তলত '''$2''' ৰ পৰা হোৱা ('''$1''' লৈকে) পৰিৱৰ্তন দেখুওৱা হৈছে ।",
'rclistfrom'                     => '$1 ৰ নতুন সাল-সলনি দেখুওৱাওক',
'rcshowhideminor'                => '$1 -সংখ্যক নগণ্য সম্পাদনা',
'rcshowhidebots'                 => 'বট $1',
'rcshowhideliu'                  => 'প্ৰবিষ্ট সভ্যৰ সাল-সলনি $1',
'rcshowhideanons'                => 'বেনাম সদস্যৰ সাল-সলনি $1',
'rcshowhidepatr'                 => '$1 পহৰা দিয়া সম্পাদনা',
'rcshowhidemine'                 => 'মোৰ সম্পাদনা $1',
'rclinks'                        => 'যোৱা $2 দিনত হোৱা $1 টা সাল-সলনি চাঁওক ।<br />$3',
'diff'                           => 'পাৰ্থক্য',
'hist'                           => 'ইতিবৃত্ত',
'hide'                           => 'দেখুৱাব নালাগে',
'show'                           => 'দেখুওৱাওক',
'minoreditletter'                => 'ম',
'newpageletter'                  => 'ন:',
'boteditletter'                  => 'য:',
'rc_categories_any'              => 'যিকোনো',
'rc-enhanced-expand'             => 'সবিশেষ দেখুৱাওক (জাভাস্ক্ৰিপ্টৰ প্ৰয়োজন)',
'rc-enhanced-hide'               => 'সবিশেষ  লুকাওঁক',

# Recent changes linked
'recentchangeslinked'          => 'প্ৰাসংগিক সালসলনিসমূহ',
'recentchangeslinked-feed'     => 'প্ৰাসংগিক সালসলনিসমূহ',
'recentchangeslinked-toolbox'  => 'প্ৰাসংগিক সম্পাদনানমূহ',
'recentchangeslinked-title'    => '"$1"ৰ লগত জড়িত সাল-সলনি',
'recentchangeslinked-noresult' => 'দিয়া সময়ৰ ভিতৰত সংযোজিত পৃষ্ঠা সমূহত সাল-সলনি হোৱা নাই |',
'recentchangeslinked-page'     => 'পৃষ্ঠাৰ নাম:',
'recentchangeslinked-to'       => 'অন্যথা নিৰ্দিষ্ট পৃষ্ঠাৰ লগত সংযুক্ত পৃষ্ঠাসমূহৰ সালসলনি দেখোৱাওক',

# Upload
'upload'              => "ফাইল আপল'ড",
'uploadbtn'           => 'ফাইল আপলোড কৰক',
'uploadnologin'       => 'প্ৰৱেশ কৰা নাই',
'upload-permitted'    => 'অনুমোদিত ফাইল ধৰণ: $1',
'upload-preferred'    => 'বাঞ্ছিত ফাইল ধৰণ: $1',
'upload-prohibited'   => 'বঞ্চিত ফাইল ধৰণ: $1',
'uploadlog'           => 'আপলোড সুচী',
'uploadlogpage'       => 'আপলোড সুচী',
'filename'            => 'ফাইলনাম',
'filedesc'            => 'সাৰাংশ',
'fileuploadsummary'   => 'সাৰাংশ:',
'filereuploadsummary' => 'ফাইলত সালসলনিসমূহ',
'filestatus'          => 'কপিৰাইট স্থিতি:',
'filesource'          => 'উৎস:',
'uploadedfiles'       => 'আপলোড কৰা ফাইলসমূহ',
'ignorewarning'       => 'সতৰ্কবাণী আওকাণ কৰি ফাইল সংৰক্ষন কৰক',
'ignorewarnings'      => 'সকলো সতৰ্কবাণী আওকাণ কৰক',
'minlength1'          => "ফাইলনাম কমেও এটা আখৰৰ হ'ব লাগে ।",
'badfilename'         => 'ফাইলনাম "$1"-লৈ সলনি কৰা হ\'ল ।',
'empty-file'          => 'আপুনি দাখিল কৰা ফাইলখন খালী ।',
'illegal-filename'    => 'ফাইলৰ এই নামটো গ্ৰহনযোগ্য নহয় ।',
'uploadwarning'       => 'আপলোড সতৰ্কবাণী',
'savefile'            => 'সংৰক্ষণ',
'uploadedimage'       => '"[[$1]]" আপলোড কৰা হ’ল',
'upload-source'       => 'উৎস ফাইল',
'sourcefilename'      => 'উৎস ফাইল নাম',
'sourceurl'           => 'উৎস ইউ-আৰ-এল',
'upload-options'      => "আপল'ড বিকল্পসমূহ",
'watchthisupload'     => 'এই ফাইল লক্ষ্য কৰক',
'upload-success-subj' => "আপলোড সফল হ'ল",

# Special:ListFiles
'imgfile'               => 'ফাইল',
'listfiles'             => 'ফাইলৰ তালিকা',
'listfiles_date'        => 'তাৰিখ',
'listfiles_name'        => 'নাম',
'listfiles_user'        => 'সদস্য',
'listfiles_size'        => 'মাত্ৰা',
'listfiles_description' => 'বিৱৰণ',
'listfiles_count'       => 'সংস্কৰণ',

# File description page
'file-anchor-link'          => 'চিত্ৰ',
'filehist'                  => 'ফাইলৰ ইতিবৃত্ত',
'filehist-help'             => 'ফাইলৰ আগৰ অৱ্স্থা চাবলৈ সেই তাৰিখ/সময়ত টিপা মাৰক ।',
'filehist-deleteone'        => 'মচি পেলাওঁক',
'filehist-current'          => 'বৰ্তমান',
'filehist-datetime'         => 'তাৰিখ/সময়',
'filehist-thumb'            => 'ক্ষুদ্ৰাকৃতি প্ৰতিকৃতি',
'filehist-thumbtext'        => '$1 পৰ্যন্ত ক্ষুদ্ৰাকৃতি প্ৰতিকৃতি সংস্কৰণ',
'filehist-user'             => 'সদস্য',
'filehist-dimensions'       => 'আকাৰ',
'filehist-filesize'         => 'ফাইলৰ আকাৰ (বাইট)',
'filehist-comment'          => 'মন্তব্য',
'filehist-missing'          => 'ফাইল সন্ধানহীন',
'imagelinks'                => 'ফাইল সংযোগসমূহ',
'linkstoimage'              => 'তলত দিয়া পৃষ্ঠাবোৰ এই চিত্ৰ খনৰ লগত জৰিত :{{PLURAL:$1|page links|$1 pages link}}',
'nolinkstoimage'            => 'এই চিত্ৰখনলৈ কোনো পৃষ্ঠা সংযোজিত নহয়',
'sharedupload'              => 'এই ফাইলখন $1-ৰ পৰা লোৱা হৈছে আৰু অন্যান্য প্ৰকল্পতো ব্যৱহাৰ হব পাৰে ।',
'uploadnewversion-linktext' => 'এই ফাইলতোৰ নতুন সংশোধন এটা বোজাই কৰক',
'shared-repo-from'          => '$1 পৰা',

# File reversion
'filerevert-comment' => 'কাৰণ:',

# File deletion
'filedelete-legend'           => 'ফাইল বিলোপ কৰক',
'filedelete-submit'           => 'বিলোপ কৰক',
'filedelete-reason-otherlist' => 'অন্য কাৰণ',

# List redirects
'listredirects' => 'পূণঃনিৰ্দেশিত তালিকা',

# Random page
'randompage' => 'আকস্মিক পৃষ্ঠা',

# Statistics
'statistics'              => 'পৰিসংখ্যা',
'statistics-header-pages' => 'পৃষ্ঠা পৰিসংখ্যা',
'statistics-header-hooks' => 'অন্য পৰিসংখ্যা',
'statistics-pages'        => 'পৃষ্ঠাসমূহ',

'doubleredirects' => 'দ্বি-পূণঃনিৰ্দেশিত',

'brokenredirects-edit'   => 'সম্পাদনা কৰক',
'brokenredirects-delete' => 'বাতিল কৰক',

'withoutinterwiki-submit' => 'দেখোৱাওক',

# Miscellaneous special pages
'nbytes'            => '$1 {{PLURAL:$1|বাইট|বাইট}}',
'ncategories'       => '$1টা {{PLURAL:$1|শ্ৰেণী|শ্ৰেণী}}',
'nlinks'            => '$1 {{PLURAL:$1|সংযোগ|সংযোগ}}',
'nmembers'          => '{{PLURAL:$1|সদস্য|$1 সদস্যবৃন্দ}}',
'nrevisions'        => '$1টা {{PLURAL:$1|সংশোধন|সংশোধন}}',
'lonelypages'       => 'অনাথ পৃষ্ঠা',
'unusedimages'      => 'অব্যৱহৃত ফাইলসমূহ',
'popularpages'      => 'জনপ্ৰিয় পৃষ্ঠাসমূহ',
'wantedcategories'  => 'কাম্য শ্ৰেণীসমূহ',
'wantedpages'       => 'কাম্য পৃষ্ঠাসমূহ',
'wantedfiles'       => 'কাম্য ফাইলসমূহ',
'prefixindex'       => 'উপসৰ্গ সহ সকলো পৃষ্ঠা',
'longpages'         => 'দিঘলীয়া পৃষ্ঠাসমূহ',
'deadendpages'      => 'ডেড এণ্ড পৃষ্ঠাসমূহ',
'protectedpages'    => 'সুৰক্ষিত পৃষ্ঠাসমূহ',
'listusers'         => 'সদস্য-সুচী',
'usereditcount'     => '$1 {{PLURAL:$1|টা সম্পাদনা|টা সম্পাদনা}}',
'newpages'          => 'নতুন পৃষ্ঠা',
'newpages-username' => 'সদস্যনাম:',
'ancientpages'      => 'আটাইটকৈ পুৰণি পৃষ্ঠাসমূহ',
'move'              => 'স্থানান্তৰন',
'movethispage'      => 'এই পৃষ্ঠাটো স্থানান্তৰিত কৰক',
'pager-newer-n'     => '{{PLURAL:$1|নতুনতৰ ১টি|নতুনতৰ $1টি}}',
'pager-older-n'     => '{{PLURAL:$1|পুৰণতৰ ১|পুৰণতৰ $1}}',

# Book sources
'booksources'               => 'গ্ৰন্থৰ উৎস সমূহ',
'booksources-search-legend' => 'গ্ৰন্থ উৎস অনুসন্ধান',
'booksources-go'            => 'যাওঁক',

# Special:Log
'specialloguserlabel'  => 'সভ্য:',
'speciallogtitlelabel' => 'শিৰোণামা:',
'log'                  => 'আলেখ',
'all-logs-page'        => 'সকলোবোৰ ৰাজহুৱা সূচী',

# Special:AllPages
'allpages'       => 'সকলোবোৰ পৃষ্ঠা',
'alphaindexline' => '$1 -ৰ পৰা $2 -লৈ',
'nextpage'       => 'পৰৱৰ্তী পৃষ্ঠা ($1)',
'prevpage'       => 'পিছৰ পৃষ্ঠা($1)',
'allpagesfrom'   => 'ইয়াৰে আৰম্ভ হোৱা পৃষ্ঠাবোৰ দেখুৱাওক:',
'allpagesto'     => 'সেই পৃষ্ঠা দেখোৱাওক যাৰ শেষ:',
'allarticles'    => 'সকলো পৃষ্ঠা',
'allinnamespace' => 'সকলোবোৰ পৃষ্ঠা ($1 নামস্থান)',
'allpagesprev'   => 'আগৰ',
'allpagesnext'   => 'পৰবৰ্তী',
'allpagessubmit' => 'যাওক',
'allpagesprefix' => 'এই উপশব্দৰে আৰম্ভ হোৱা পৃষ্ঠা দেখুৱাওক:',

# Special:Categories
'categories' => 'শ্ৰেণী',

# Special:DeletedContributions
'deletedcontributions'             => 'ৰদ কৰা সদস্যৰ বৰঙণিসমূহ',
'deletedcontributions-title'       => 'ৰদ কৰা সদস্যৰ বৰঙণিসমূহ',
'sp-deletedcontributions-contribs' => 'বৰঙণিসমূহ',

# Special:LinkSearch
'linksearch'      => 'বহিঃ-সংযোগ',
'linksearch-ns'   => 'নামস্থান:',
'linksearch-ok'   => 'অনুসন্ধান',
'linksearch-line' => '$2 পৰা $1 সংযোগ কৰা হৈছে',

# Special:ListUsers
'listusersfrom'      => 'ইয়াৰে আৰম্ভ হোৱা ব্যৱহাৰকাৰী সকল দেখুৱাওক:',
'listusers-submit'   => 'দেখুৱাওক',
'listusers-noresult' => 'ব্যৱহাৰ কাৰী বিচাৰি পোৱা নগল',
'listusers-blocked'  => '(বাৰণ কৰা)',

# Special:ActiveUsers
'activeusers'            => 'সক্রিয় ব্যবহাৰকাৰীৰ তালিকা',
'activeusers-count'      => 'যোৱা {{PLURAL:$3|দিনত|$3 দিনত}} সর্বমুঠ {{PLURAL:$1|সম্পাদনাৰ|সম্পাদনাৰ}} সংখ্যা $1',
'activeusers-from'       => 'ইয়াৰে আৰম্ভ হোৱা ব্যৱহাৰকাৰী সকল দেখুৱাওক:',
'activeusers-hidebots'   => 'বট নেদেখুৱাব',
'activeusers-hidesysops' => 'প্ৰশাসক নেদেখুৱাব',
'activeusers-noresult'   => 'কোনো সদস্য পোৱা নগল ।',

# Special:Log/newusers
'newuserlogpage'              => 'সদস্যৰ সৃষ্টি অভিলেখ',
'newuserlog-byemail'          => 'গুপ্তশব্দ ই-মেইল কৰি পঠোৱা হৈছে',
'newuserlog-create-entry'     => 'নতুন সদস্য',
'newuserlog-create2-entry'    => '$1 ক নতুন সদস্যভুক্তি কৰা হল',
'newuserlog-autocreate-entry' => 'স্বয়ংক্ৰীয়ভাবে নতুন সদস্যভুক্তি কৰা হল',

# Special:ListGroupRights
'listgrouprights-group'           => 'গোট',
'listgrouprights-rights'          => 'অধিকাৰসমূহ',
'listgrouprights-members'         => '(সদস্যবৃন্দ তালিকা)',
'listgrouprights-removegroup'     => '{{PLURAL:$2|গোট|গোটসমূহ}} আঁতৰাওক: $1',
'listgrouprights-addgroup-all'    => 'সমস্ত গোট সংযোগ কৰক',
'listgrouprights-removegroup-all' => 'সমস্ত গোট আঁতৰাওক',

# E-mail user
'emailuser' => 'এই সদস্যজনলৈ ই-মেইল পথাওক',

# Watchlist
'watchlist'         => 'মই অনুসৰণ কৰা পৃষ্ঠাবিলাকৰ তালিকা',
'mywatchlist'       => 'মই চকু ৰখা পৃষ্ঠাবোৰৰ তালিকা',
'watchnologin'      => 'প্ৰৱেশ কৰা নাই',
'addedwatch'        => 'লক্ষ্য তালিকাত অন্তৰ্ভুক্তি কৰা হল',
'addedwatchtext'    => 'আপোনাৰ [[Special:Watchlist|লক্ষ্য তালিকাত ]] "[[:$1]]" অন্তৰ্ভুক্তি কৰা হ\'ল ।
ভৱিষ্যতে ইয়াত হোৱা সাল-সলনি আপুনি আপোনাৰ লক্ষ্য তালিকাত দেখিব, লগতে [[Special:RecentChanges|সাম্প্ৰতিক সাল-সলনিৰ তালিকাত]] এই পৃষ্ঠাটো শকট আখৰত দেখিব যাতে আপুনি সহজে ধৰিব পাৰে ।',
'removedwatch'      => 'লক্ষ্য-তালিকাৰ পৰা আতৰোৱা হল',
'removedwatchtext'  => '"[[:$1]]" পৃষ্ঠাখন [[Special:Watchlist|আপোনাৰ লক্ষ্য-তালিকা]]ৰ পৰা আতৰোৱা হৈছে ।',
'watch'             => 'চকু ৰাখক',
'watchthispage'     => 'এই পৃষ্ঠাটো লক্ষ্য কৰক',
'unwatch'           => 'চকু দিব নালাগে',
'watchlist-details' => 'আলোচনা পৃষ্ঠা সমূহ লেখত নধৰি {{PLURAL:$1|$1 খন পৃষ্ঠা|$1 খন পৃষ্ঠা}} আপোনাৰ লক্ষ্য-তালিকাত আছে।',
'wlshowlast'        => 'যোৱা $1 ঘণ্টা $2 দিন $3 চাওক',
'watchlist-options' => 'লক্ষ্য-তালিকা পছন্দসমূহ',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'চকু দিয়া হৈছে.....',
'unwatching' => 'আঁতৰোৱা হৈ আছে.....',

'enotif_impersonal_salutation' => '{{SITENAME}} সডস্য',
'enotif_anon_editor'           => 'বেনামী সদস্য $1',

# Delete
'deletepage'             => 'পৃষ্ঠা বিলোপ কৰক',
'confirm'                => 'নিশ্চিত কৰক',
'excontent'              => 'বিষয়বস্তু আছিল: "$1"',
'excontentauthor'        => 'বিষয়বস্তু আছিল: "$1" (আৰু একমাত্ৰ অৱদানকাৰী আছিল "[[Special:Contributions/$2|$2]]")',
'exbeforeblank'          => 'খালী কৰাৰ আগেয়ে বিষয়বস্তু আছিল: $1',
'exblank'                => 'পৃষ্ঠা খালী আছিল',
'delete-confirm'         => '"$1" বিলোপ কৰক',
'delete-legend'          => 'বিলোপ কৰক',
'historywarning'         => "'''সাবধান:''' আপুনি বিলোপ কৰিব বিছৰা পৃষ্ঠাখনৰ ইতিহাসত প্ৰায় {{PLURAL:$1|সংস্কৰণ|সংস্কৰণ}} আছে:",
'confirmdeletetext'      => 'আপুনি পৃষ্ঠা এটা তাৰ ইতিহাসৰ সৈতে বিলোপ কৰিব ওলাইছে।
অনুগ্ৰহ কৰি নিশ্চিত কৰক যে এয়া [[{{MediaWiki:Policy-url}}|নীতিসম্মত]]। লগতে আপুনি ইয়াৰ পৰিণাম জানে আৰু আপুনি এয়া কৰিব বি্চাৰিছে।',
'actioncomplete'         => 'কাৰ্য্য সম্পূৰ্ণ',
'actionfailed'           => "কাৰ্য্য বিফল হ'ল",
'deletedtext'            => '"<nowiki>$1</nowiki>" ক বিলোপন কৰা হৈছে।
সাম্প্ৰতিক বিলোপনসমূহৰ তালিকা চাবলৈ $2 চাঁওক।',
'deletedarticle'         => '"[[$1]]" ক বাতিল কৰা হৈছে।',
'suppressedarticle'      => 'দমন কৰা হ\'ল "[[$1]]"',
'dellogpage'             => 'বাতিল কৰা সুচী',
'dellogpagetext'         => "তলত সাপ্ৰতিক বিলোপতিৰ তালিকা দিয়া হ'ল ।",
'deletionlog'            => 'বাতিল কৰা সূচী',
'reverted'               => "পূৰ্ববৰ্তী সংস্কৰণলৈ উভতি যযোৱা হ'ল",
'deletecomment'          => 'কাৰণ:',
'deleteotherreason'      => 'আন/অতিৰিক্ত কাৰণ:',
'deletereasonotherlist'  => 'আন কাৰণ:',
'delete-edit-reasonlist' => 'অপসাৰণ কৰা কাৰণ সম্পাদনা কৰক',

# Rollback
'rollback'       => 'সম্পাদনা পূৰ্ববৎ কৰক',
'rollback_short' => 'পূৰ্ববৎ কৰক',
'rollbacklink'   => 'পূৰ্ববৎ কৰিবলৈ',
'rollbackfailed' => 'পূৰ্ববৎ ব্যৰ্থ',
'editcomment'    => "সম্পাদনাৰ সাৰাংশ আছিল: \"''\$1''\"।",

# Protect
'protectlogpage'              => 'সুৰক্ষা সুচী',
'protectedarticle'            => 'সুৰক্ষিত "[[$1]]"',
'modifiedarticleprotection'   => '"[[$1]]"-ৰ সুৰক্ষাৰ স্তৰ সলনি কৰা হৈছে',
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
'protect-summary-cascade'     => 'কেছকেডইং',
'protect-expiring'            => ' $1 (UTC) ত সময় শেষ হব',
'protect-cascade'             => 'এই পৃষ্ঠাটোৰ লগত জৰিত সকলো পৃষ্ঠা সুৰক্ষিত কৰক (সুৰক্ষা জখলা)',
'protect-cantedit'            => 'আপুনি এই পৃষ্ঠাটোৰ সুৰক্ষা-স্তৰ সলনি কৰিব নোৱৰে, কাৰণ আপোনাক সেই অনুমতি দিয়া হোৱা নাই।',
'protect-expiry-options'      => '‌১ ঘণ্টা:1 hour,১ দিন:1 day,১ সপ্তাহ:1 week,২ সপ্তাহ:2 weeks,১ মাহ:1 month,৩ মাহ:3 months,৬ মাহ:6 months,১ বছৰ:1 year,অনিৰ্দিস্ট কাল:infinite',
'restriction-type'            => 'অনুমতি:',
'restriction-level'           => 'সুৰক্ষা-স্তৰ:',

# Undelete
'undeletebtn'               => 'পূণঃসংস্থাপন কৰক',
'undeletelink'              => 'লক্ষ্য কৰক/ঘূৰাই আনক',
'undeletedarticle'          => '"[[$1]]"-ক পূৰ্বস্থানলৈ ঘূৰাই অনা হ\'ল',
'undelete-search-submit'    => 'সন্ধান',
'undelete-show-file-submit' => 'অঁ',

# Namespace form on various pages
'namespace'             => 'নামস্থান:',
'invert'                => 'নিৰ্বাচন ওলোটা কৰক',
'namespace_association' => 'সাংসৰ্গিক নামস্থান',
'blanknamespace'        => '(মুখ্য)',

# Contributions
'contributions'       => 'সদস্যৰ বৰঙণিসমূহ',
'contributions-title' => '$1-ৰ বৰঙণিসমূহ',
'mycontris'           => 'মোৰ বৰঙণিসমূহ',
'contribsub2'         => '$1 ৰ কাৰণে($2)',
'uctop'               => '(ওপৰত)',
'month'               => 'এই মাহৰ পৰা (আৰু আগৰ):',
'year'                => 'এই বছৰৰ পৰা (আৰু আগৰ):',

'sp-contributions-newbies'       => 'কেৱল নতুন একাউন্টৰ বৰঙণিসমূহ দেখোৱাওঁক',
'sp-contributions-newbies-sub'   => 'নতুন একাউন্টৰ কাৰণে',
'sp-contributions-newbies-title' => 'সদস্যৰ বৰঙণি নতুন একাউন্টৰ বাবে',
'sp-contributions-blocklog'      => 'বাৰণ সূচী',
'sp-contributions-deleted'       => 'ৰদ কৰা সদস্যৰ বৰঙণিসমূহ',
'sp-contributions-uploads'       => 'আপলোডসমূহ',
'sp-contributions-logs'          => 'অভিলেখ',
'sp-contributions-talk'          => 'আলোচনা',
'sp-contributions-userrights'    => 'সদস্যৰ অধিকাৰ ব্যৱস্থাপনা',
'sp-contributions-search'        => 'বৰঙণিসমূহৰ কাৰণে অনুসন্ধান কৰক',
'sp-contributions-username'      => 'আইপি ঠিকনা অথবা ব্যৱহাৰকৰ্তাৰ নাম:',
'sp-contributions-toponly'       => 'কেৱল সামপ্ৰতিক সংশোধনসমূহ দেখোৱাওঁক',
'sp-contributions-submit'        => 'সন্ধান কৰক',

# What links here
'whatlinkshere'            => 'এই পৃষ্ঠা ব্যৱহাৰ কৰিছে...',
'whatlinkshere-title'      => '"$1"-লৈ সংযোগ কৰা পৃষ্ঠাসমূহ',
'whatlinkshere-page'       => 'পৃষ্ঠা:',
'linkshere'                => "এই পৃষ্ঠাটো '''[[:$1]]''' ৰ লগত সংযোজিত:",
'nolinkshere'              => "'''[[:$1]]''' ৰ লগত কোনো পৃষ্ঠা সংযোজিত নহয়।",
'nolinkshere-ns'           => 'নিৰ্বাচিত নামস্থানৰ কোনো পৃষ্ঠাৰ পৰা [[:$1]]-লৈ সংযোগ নাই ।',
'isredirect'               => 'পূণঃনিৰ্দেশন পৃষ্ঠা',
'istemplate'               => 'অন্তৰ্ভুক্ত কৰক',
'isimage'                  => 'চিত্ৰ সংযোগ',
'whatlinkshere-prev'       => '{{PLURAL:$1|পিছৰ|পিছৰ $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|আগৰ|আগৰ $1}}',
'whatlinkshere-links'      => '← সূত্ৰসমূহ',
'whatlinkshere-hideredirs' => '$1 পুননিৰ্দেশনাসমূহ',
'whatlinkshere-hidetrans'  => '$1 ট্ৰেন্সক্লুস্বন-সমূহ',
'whatlinkshere-hidelinks'  => '$1 টি সংযোগ',
'whatlinkshere-hideimages' => '$1 চিত্ৰ সংযোগসমূহ',
'whatlinkshere-filters'    => 'ছাকনী',

# Block/unblock
'autoblockid'              => '#$1-ক স্বয়ংক্ৰিয় বাৰণ কৰক',
'block'                    => 'সদস্য/আই-পি ঠিকনা বাৰণ কৰক',
'unblock'                  => 'সদস্যৰ/আই-পি ঠিকনাৰ বাৰণ উঠাই লওঁক',
'blockip'                  => 'সদস্য বাৰণ কৰক',
'blockip-title'            => 'সদস্য বাৰণ কৰক',
'blockip-legend'           => 'সদস্য বাৰণ কৰক',
'ipadressorusername'       => 'আই-পি ঠিকনা বা সদস্যনাম:',
'ipbreason'                => 'কাৰণ:',
'ipbreasonotherlist'       => 'অন্য কাৰণ',
'ipbsubmit'                => 'এই সদস্যক বাৰণ কৰক',
'ipbother'                 => 'অন্য সময়:',
'ipboptions'               => '২ ঘ্ণ্টা:2 hours,১ দিন:1 day,৩ দিন:3 days,১ সপ্তাহ:1 week,২ সপ্তাহ:2 weeks,১ মাহ:1 month,৩ মাহ:3 months,৬ মাহ:6 months,১ বছৰ:1 year,অনিৰ্দিস্ট কাল:infinite',
'ipbotheroption'           => 'অন্যান্য',
'ipbotherreason'           => 'অন্য/অতিৰিক্ত কাৰণ:',
'ipbwatchuser'             => 'এই সদস্যৰ সদস্য আৰু আলোচনা পৃষ্ঠা লক্ষ্য কৰক',
'badipaddress'             => 'অগ্ৰহনযোগ্য আই-পি ঠিকনা',
'blockipsuccesssub'        => "বাৰণ কৰা সফল হ'ল",
'ipusubmit'                => 'এই বাৰণ উঠাই লওঁক',
'blocklist'                => 'বাৰণ কৰা আই-পি ঠিকনা আৰু সদস্যনাম',
'ipblocklist'              => 'বাৰণ কৰা আই-পি ঠিকনা আৰু সদস্যৰ তালিকা',
'ipblocklist-legend'       => 'বাৰণ কৰা সদস্য অনুসন্ধান কৰক',
'blocklist-userblocks'     => 'একাউন্ট বাৰণ আবৰণ কৰক',
'blocklist-target'         => 'লক্ষ্য',
'blocklist-reason'         => 'কাৰণ:',
'ipblocklist-submit'       => 'অনুসন্ধান',
'ipblocklist-localblock'   => 'স্থানীয় বাৰণ',
'ipblocklist-otherblocks'  => 'অন্যান্য {{PLURAL:$1|বাৰণ|বাৰণসমূহ}}',
'blocklink'                => 'বাৰণ কৰক',
'unblocklink'              => 'বাৰণ উঠাই লওঁক',
'change-blocklink'         => 'বাৰণ সলনি কৰক',
'contribslink'             => 'বৰঙণি',
'blocklogpage'             => 'বাৰণ কৰা সুচী',
'blocklogentry'            => '"[[$1]]" ক $2 $3 লৈ সাল-সলনি কৰাৰ পৰা বাৰণ কৰা হৈছে।',
'unblocklogentry'          => '$1 বাৰণ উঠাই লোৱা হ’ল',
'block-log-flags-nocreate' => 'একাউন্ট সৃষ্টি নিষ্ক্ৰিয় কৰা হৈছে',

# Move page
'movepagetext'              => "তলৰ ফৰ্ম ব্যবহাৰ কৰিলে এই পৃষ্ঠাৰ শিৰোনামা সলনি হ'ব, লগতে সমগ্ৰ ইতিহাস নতুন শিৰোনামালৈ স্থানান্তৰ কৰা হ'ব ।
পুৰণা শিৰোনামাটো নতুন শিৰোনামালৈ এটা পুনৰ্নিৰ্দেশনা হৈ ৰ'ব ।
সমগ্ৰ পুনৰ্নিৰ্দেশনাসমূহ যি পুৰণা শিৰোনামালৈ পোনায়, আপুনি  স্বয়ংক্ৰিয় ভাবে আপডেট কৰিব পাৰিব ।
যদি এই কৰিব নিবিচাৰে তেনেহলে  [[Special:DoubleRedirects|দুনা পুনৰ্নিৰ্দেশনসমূহ]] বা [[Special:BrokenRedirects|ভঙা পুনৰ্নিৰ্দেশনসমূহ]] চয়ন কৰে যেন ।
যে সকলো সংযোগ সঠিক দিশলৈ পোনাই, আপুনিয়েই জবাবদিহি ।

মন কৰিব যে নতুন শিৰোণামাতো যদি প্ৰচলিত, এই পৃষ্ঠা নতুন শিৰোনামালৈ সলনি কৰা '''নহ'ব''' যদিহে সেই পৃষ্ঠা খালী বা কোনো পুনৰ্নিৰ্দেশনৰ পুৰ্ব ইতিহাস নাই ।
ইয়াৰ অৰ্থ এয়ে যে ভুল হলে পৃষ্ঠাখন আগৰ ঠাইতে থাকিব, আৰু আপুনি প্ৰচলিত পৃষ্ঠা এখনক আন পৃষ্ঠা এখনেৰে সলনি কৰিব নোৱাৰে।

'''সাৱধান!'''
জনপ্ৰিয় পৃষ্ঠা এখনৰ বাবে এয়া এক ডাঙৰ আৰু অনাপেক্ষিত সাল-সলনি হব পাৰে;
এই কাৰ্য্যৰ পৰিণাম ভালদৰে বিবেচনা কৰি লই যেন।",
'movearticle'               => 'পৃস্থা স্থানান্তৰ কৰক',
'movenologin'               => 'প্ৰৱেশ কৰা নাই',
'movenologintext'           => 'পৃষ্ঠা স্থানান্তৰ কৰিবলৈ আপুনি ভুক্ত সদস্য হৈ [[Special:UserLogin|পৱেশ]] কৰিব লাগিব ।',
'movenotallowed'            => 'পৃষ্ঠা স্থানান্তৰ কৰিবলৈ আপুনাৰ অনুমতি নাই ।',
'movenotallowedfile'        => 'ফাইল স্থানান্তৰ কৰিবলৈ আপুনাৰ অনুমতি নাই ।',
'cant-move-user-page'       => 'সদস্য পৃষ্ঠা স্থানান্তৰ কৰিবলৈ আপুনাৰ অনুমতি নাই (উপ-পৃষ্ঠাৰ বাহিৰে)।',
'newtitle'                  => 'নতুন শিৰোণামালৈ:',
'move-watch'                => 'এই পৃষ্ঠাটো লক্ষ্য কৰক',
'movepagebtn'               => 'পৃষ্ঠাটো স্থানান্তৰ কৰক',
'pagemovedsub'              => 'স্থানান্তৰ সফল হল',
'movepage-moved'            => "'''“$1” ক “$2” লৈ স্থানান্তৰ কৰা হৈছে'''",
'movepage-moved-redirect'   => 'এটি পুনৰ্নিদেশনা সৃষ্টি কৰা হৈছে',
'movepage-moved-noredirect' => 'পুনৰ্নিদেশনা সৃষ্টি কৰা দমন কৰা হৈছে',
'articleexists'             => 'সেই নামৰ পৃষ্ঠা এটা আগৰ পৰাই আছে, বা সেই নামতো অযোগ্য।
বেলেগ নাম এটা বাছি লওক।',
'talkexists'                => "'''পৃষ্ঠাটো স্থানান্তৰ কৰা হৈছে, কিন্তু ইয়াৰ লগত জৰিত বাৰ্তা পৃষ্ঠাটো স্থানান্তৰ কৰা নহল, কাৰণ নতুন ঠাইত বাৰ্তা পৃষ্ঠা এটা আগৰ পৰাই আছে।
অনুগ্ৰহ কৰি আপুনি নিজে স্থানান্তৰ কৰক ।'''",
'movedto'                   => 'লৈ স্থানান্তৰ কৰা হল',
'movetalk'                  => 'সংলগ্ন বাৰ্তা পৃষ্ঠা স্থানান্তৰ কৰক',
'move-subpages'             => 'উপ-পৃষ্ঠাসমূহ স্থানান্তৰ কৰক ($1-লৈ)',
'move-talk-subpages'        => 'আলোচনা পৃষ্ঠাৰ উপ-পৃষ্ঠাসমূহ স্থানান্তৰ কৰক ($1-লৈ)',
'movepage-page-moved'       => "$1 পৃষ্ঠাখন $2-লৈ স্থানান্তৰ কৰা হ'ল ।",
'movepage-page-unmoved'     => '$1 পৃষ্ঠাখন $2-লৈ স্থানান্তৰ কৰা সম্ভৱ নহয়',
'1movedto2'                 => '[[$1]]ক [[$2]] লৈ স্থানান্তৰিত কৰা হল',
'1movedto2_redir'           => "[[$1]]-ক [[$2]]-লৈ পুনৰ্নিৰ্দেশনাৰ সহায়েৰে স্থানান্তৰ কৰা হ'ল",
'movelogpage'               => 'স্থানান্তৰন সূচী',
'movelogpagetext'           => 'সকলো পৃষ্ঠা স্থানান্তৰৰ এখন তালিকা তলত দিয়া হৈছে ।',
'movereason'                => 'কাৰণ:',
'revertmove'                => 'আগৰ অৱ্স্থালৈ ঘুৰি যাওক',

# Export
'export'            => 'পৃষ্ঠা নিষ্কাষন',
'export-submit'     => 'ৰপ্তানি কৰক',
'export-addcattext' => 'এই শ্ৰেণীকেইতাৰ পৰা পৃষ্ঠা যোগ কৰক:',
'export-addcat'     => 'যোগ কৰক',
'export-addnstext'  => 'এই নামস্থানৰ পৰা পৃষ্ঠা যোগ কৰক',
'export-addns'      => 'যোগ কৰক',
'export-download'   => 'ফাইল হিচাবে সংৰক্ষণ কৰক',

# Namespace 8 related
'allmessages'                   => 'ব্যৱস্থাৰ বতৰা',
'allmessagesname'               => 'নাম',
'allmessagesdefault'            => "ডিফ'ল্ট বাৰ্তা পাঠ্য",
'allmessages-filter-unmodified' => 'অপৰিবৰ্তিত',
'allmessages-filter-all'        => 'সকলো',
'allmessages-filter-modified'   => 'পৰিবৰ্তিত',
'allmessages-language'          => 'ভাষা:',
'allmessages-filter-submit'     => 'যাওঁক',

# Thumbnails
'thumbnail-more'          => 'ডাঙৰকৈ চাওক',
'thumbnail_error'         => 'থাম্বনেইল বনাব অসুবিধা হৈছে: $1',
'thumbnail_image-missing' => 'ফাইল সম্ভৱতঃ নাই: $1',

# Special:Import
'import'                     => 'পৃষ্ঠা আমদানি কৰক',
'import-interwiki-source'    => 'উৎস ৱিকি/পৃষ্ঠা:',
'import-interwiki-templates' => 'সকলো সাঁচ অন্তৰ্ভুক্ত কৰক',
'import-interwiki-submit'    => 'আমদানি',
'import-interwiki-namespace' => 'গন্তব্য নামস্থান:',
'import-upload-filename'     => 'ফাইলনাম:',
'import-comment'             => 'মন্তব্য:',
'importnotext'               => 'খালী বা পাঠ বিহীন',

# Import log
'importlogpage' => 'আমদানী সুচী',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'আপোনাৰ সদস্য পৃষ্ঠা',
'tooltip-pt-mytalk'               => 'আপোনাৰ আলোচনা পৃষ্ঠা',
'tooltip-pt-preferences'          => 'মোৰ পচন্দ',
'tooltip-pt-watchlist'            => 'আপুনি সালসলনিৰ গতিবিধি লক্ষ্য কৰি থকা পৃষ্ঠাসমূহৰ সুচী',
'tooltip-pt-mycontris'            => 'আপোনাৰ আৰিহনাৰ তালিকা',
'tooltip-pt-login'                => 'অত্যাবশ্যক নহলেও লগ-ইন কৰা বাঞ্চনীয়',
'tooltip-pt-logout'               => 'লগ-আউট',
'tooltip-ca-talk'                 => 'সংশ্লিষ্ট প্ৰৱন্ধ সম্পৰ্কীয় আলোচনা',
'tooltip-ca-edit'                 => 'আপুনি এই পৃষ্ঠাটো সালসলনি কৰিব পাৰে, অনুগ্ৰহ কৰি সালসলনি সাচী থোৱাৰ আগতে খচৰা চাই লব',
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
'tooltip-search-fulltext'         => 'এই পাঠ পৃষ্ঠাসমূহত বিচাৰক',
'tooltip-p-logo'                  => 'বেটুপাত খুলিবৰ কাৰণে',
'tooltip-n-mainpage'              => 'বেটুপাত খুলিবৰ কাৰণে',
'tooltip-n-mainpage-description'  => 'প্ৰথম পৃষ্ঠা পৰিদৰ্শন কৰক',
'tooltip-n-portal'                => "এই প্ৰকল্পৰ ইতিবৃত্ত, আপুনি কেনেকৈ সহায় কৰিব পাৰে, ইত্যাদি (কি, ক'ত কিয় বিখ্যাত!!)।",
'tooltip-n-currentevents'         => 'সাম্প্ৰতিক ঘটনাৱলীৰ পটভূমি',
'tooltip-n-recentchanges'         => 'শেহতীয়া সালসলনিসমূহৰ সূচী',
'tooltip-n-randompage'            => 'অ-পূৰ্বনিৰ্ধাৰিতভাবে যিকোনো এটা পৃষ্ঠা দেখুৱাবৰ কাৰণে',
'tooltip-n-help'                  => 'সহায়ৰ বাবে ইয়াত ক্লিক কৰক',
'tooltip-t-whatlinkshere'         => 'ইয়ালৈ সংযোজিত সকলো পৃষ্ঠাৰ সুচী',
'tooltip-t-recentchangeslinked'   => 'সংযুক্ত পৃষ্ঠাসমূহৰ শেহতীয়া সালসলনিসমূহ',
'tooltip-feed-rss'                => 'এই পৃষ্ঠাৰ বাবে আৰ-এচ-এচ ফিড',
'tooltip-feed-atom'               => 'এই পৃষ্ঠাৰ বাবে এটম ফিড',
'tooltip-t-contributions'         => 'এই সদস্যজনৰ অৰিহনাসমূহৰ সূচী চাঁওক ।',
'tooltip-t-emailuser'             => 'এই সদস্যজনলৈ ই-মেইল পঠাওক',
'tooltip-t-upload'                => "ফাইল আপল'ডৰ বাবে",
'tooltip-t-specialpages'          => 'বিশেষ পৃষ্ঠাসমূহৰ সূচী',
'tooltip-t-print'                 => 'এ পৃষ্ঠাৰ ছপা উপযোগী সংস্কৰণ',
'tooltip-t-permalink'             => 'পৃষ্ঠাৰ এই সংস্কৰণৰ স্থায়ী সংযোগ',
'tooltip-ca-nstab-main'           => 'এই ৱিকিৰ সূচী চাওক',
'tooltip-ca-nstab-user'           => 'সভ্যৰ ব্যক্তিগত পৃষ্ঠালৈ',
'tooltip-ca-nstab-special'        => 'এইখন এখন বিশেষ পৃষ্ঠা, আপুনি সম্পাদনা কৰিব নোৱাৰে',
'tooltip-ca-nstab-project'        => 'আচনী পৃষ্ঠা চাঁওক।',
'tooltip-ca-nstab-image'          => 'নথি পৃষ্ঠা চাওক',
'tooltip-ca-nstab-template'       => 'সাঁচ চাওক',
'tooltip-ca-nstab-help'           => 'সহায় পৃষ্ঠা চাওক',
'tooltip-ca-nstab-category'       => 'শ্ৰেণী পৃষ্ঠা চাঁওক ।',
'tooltip-minoredit'               => 'ইয়াক অগুৰুত্বপূৰ্ণ সম্পাদনা ৰূপে চিহ্নিত কৰক।',
'tooltip-save'                    => 'আপুনি কৰা সালসলনি সাঁচি থঁওক',
'tooltip-preview'                 => 'অপুনি কৰা সালসলনিবোৰৰ খচৰা চাওক, আনুগ্ৰহ কৰি সালসলনি সাচী থোৱাৰ আগতে ব্যৱহাৰ কৰক!',
'tooltip-diff'                    => 'ইয়াত আপুনি কৰা সালসলনিবোৰ দেখুৱাওক',
'tooltip-compareselectedversions' => 'এই পৃষ্ঠাত নিৰ্বাচিত কৰা দুটা অৱতৰৰ মাজত পাৰ্থক্য দেখুৱাওক ।',
'tooltip-watch'                   => 'এই পৃষ্ঠাটো আপোনাৰ অনুসৰণতালিকাভুক্ত কৰক',

# Attribution
'anonymous'     => '{{SITENAME}}ৰ বেনামী {{PLURAL:$1|সদস্য|সদস্যসকল}}',
'siteuser'      => '{{SITENAME}} সদস্য $1',
'anonuser'      => '{{SITENAME}} বেনামী সদস্য $1',
'othercontribs' => '$1 ৰ কাৰ্য্যৰ উপৰত ভিত্তি কৰি',
'others'        => 'অন্যান্য',
'siteusers'     => '{{SITENAME}} {{PLURAL:$2|সদস্য|সদস্যসমূহ}} $1',
'anonusers'     => '{{SITENAME}} বেনামী {{PLURAL:$2|সদস্য|সদস্যসকল}} $1',

# Info page
'infosubtitle'   => 'পৃষ্ঠাৰ তথ্য',
'numedits'       => 'সম্পাদনাৰ সংখ্যা (পৃষ্ঠা): $1',
'numtalkedits'   => 'সম্পাদনাৰ সংখ্যা (আলোচনা পৃষ্ঠা): $1',
'numwatchers'    => 'দৃষ্টিত ৰাখিছে: $1-জনে',
'numauthors'     => 'আছুতীয়া স্ৰষ্টা (পৃষ্ঠা): $1-জন',
'numtalkauthors' => 'আছুতীয়া স্ৰষ্টা (কথাবতৰা): $1-জন',

# Math errors
'math_failure'          => 'পাৰ্চ কৰিব অসমৰ্থ',
'math_unknown_error'    => 'অপৰিচিত সমস্যা',
'math_unknown_function' => 'অজ্ঞাত কাৰ্য্য',

# Patrol log
'patrol-log-diff' => 'সংশোধন $1',

# Browsing diffs
'previousdiff' => 'প্ৰবীণ সম্পাদনা',
'nextdiff'     => 'নতুনতৰ সম্পাদনা →',

# Media information
'file-info'            => 'ফাইল আকাৰ: $1, MIME ধৰণ: $2',
'file-info-size'       => '$1 × $2 পিক্সেল, ফাইলৰ মাত্ৰা: $3, MIME প্ৰকাৰ: $4',
'file-nohires'         => '<small>ইয়াতকৈ ডাঙৰকৈ দেখুৱাব নোৱাৰি ।</small>',
'svg-long-desc'        => 'SVG ফাইল, সাধাৰণতঃ $1 × $2 পিক্সেল, ফাইল মাত্ৰা: $3',
'show-big-image'       => 'সম্পূৰ্ণ দৃশ্য',
'show-big-image-size'  => '$1 × $2 পিক্সেল',
'file-info-png-repeat' => "$1 {{PLURAL:$1|বাৰ|বাৰ}} চলোৱা হ'ল",

# Special:NewFiles
'newimages'             => 'নতুন ফাইলৰ বিথীকা',
'newimages-legend'      => 'ছাকনী',
'newimages-label'       => 'ফাইলনাম (বা তাৰ এটা অংশ)',
'showhidebots'          => '(বট $1)',
'noimages'              => 'চাবলৈ একো নাই ।',
'ilsubmit'              => 'সন্ধান কৰক',
'bydate'                => 'তাৰিখ অনুযায়ী',
'sp-newimages-showfrom' => '$2, $1 পৰা নতুন চিত্ৰসমূহ দেখোৱাওঁক',

# Metadata
'metadata'          => 'মেটাডাটা',
'metadata-help'     => 'এই ফাইলত অতিৰিক্ত খবৰ আছে, হয়তো ডিজিটেল কেমেৰা বা স্কেনাৰ ব্যৱহাৰ কৰি সৃষ্টি বা পৰিৱৰ্তন কৰা হৈছে ।
এই ফাইলটো আচলৰ পৰা পৰিৱৰ্তন  কৰা হৈছে, সেয়েহে পৰিৱৰ্তিত ফাইলটোৰ সৈতে নিমিলিব পাৰে ।',
'metadata-expand'   => 'বহলাই ইয়াৰ বিষয়ে জনাওক',
'metadata-collapse' => 'বিষয় বৰ্ণনা নেদেখুৱাবলৈ',
'metadata-fields'   => 'এই সুচীত থকা বিষয়বোৰ চিত্ৰৰ পৃষ্ঠাৰ তলত সদায় দেখা যাব ।
বাকী বিষয়বোৰ গুপ্ত থাকিব ।
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'       => 'বহল',
'exif-datetime'         => 'ফাইল সলনিৰ তাৰিখ আৰু সময়',
'exif-imagedescription' => 'চিত্ৰ শিৰোনামা',
'exif-artist'           => 'স্ৰষ্টা',
'exif-objectname'       => 'চমু শীৰ্ষক',

'exif-orientation-1' => 'সাধাৰণ',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'কিলোমিটাৰ প্ৰতি ঘন্টা',
'exif-gpsspeed-m' => 'মাইল প্ৰতি ঘন্টা',

# External editor support
'edit-externally'      => 'বাহিৰা আহিলা ব্যৱহাৰ কৰি এই ফাইলটো সম্পাদনা কৰক ।',
'edit-externally-help' => 'অধিক তথ্যৰ কাৰণে [http://www.mediawiki.org/wiki/Manual:External_editors প্ৰস্তুত কৰা নিৰ্দেশনা] চাঁওক ।',

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
'ascending_abbrev'         => 'আৰোহণ',
'descending_abbrev'        => 'অবতৰণ',
'table_pager_next'         => 'পৰৱৰ্তী পৃষ্ঠা',
'table_pager_prev'         => 'পূৰ্ববৰ্তী পৃষ্ঠা',
'table_pager_first'        => 'প্ৰথম পৃষ্ঠা',
'table_pager_last'         => 'শেষ পৃষ্ঠা',
'table_pager_limit_submit' => 'যাওঁক',
'table_pager_empty'        => 'ফলাফল নাই',

# Auto-summaries
'autosumm-blank'   => "পৃষ্ঠাখন খালি কৰা হ'ল",
'autoredircomment' => "[[$1]]-ক পুনৰ্নিৰ্দেশ কৰা হ'ল",
'autosumm-new'     => '"$1" দি পৃষ্ঠা সৃষ্টি কৰা হ\'ল',

# Live preview
'livepreview-loading' => 'লোডিং…',
'livepreview-ready'   => 'লোডিং… প্ৰস্তুত!',

# Watchlist editor
'watchlistedit-numitems'       => 'কথাবতৰা পৃষ্ঠাসমূহ বাদ দি, আপুনাৰ অনুসৰণ-তালিকাত {{PLURAL:$1|১-খন|$1-খন}} ঘাই পৃষ্ঠা আছে ।',
'watchlistedit-noitems'        => 'আপুনাৰ লক্ষ্য-তালিকাত এখনো ঘাই পৃষ্ঠা নাই ।',
'watchlistedit-normal-title'   => 'লক্ষ্য-তালিকা সম্পাদন কৰক',
'watchlistedit-normal-legend'  => 'লক্ষ্য-তালিকাৰ পৰা শীৰ্ষক আতৰোৱাওক',
'watchlistedit-normal-explain' => 'Titles on your watchlist are shown below.
To remove a title, check the box next to it, and click "{{int:Watchlistedit-normal-submit}}".
You can also [[Special:Watchlist/raw|edit the raw list]].',
'watchlistedit-normal-submit'  => 'শিৰোনামা আঁতৰাৱক',
'watchlistedit-normal-done'    => "{{PLURAL:$1|1 শিৰোনামা|$1 শিৰোনামাসমূহ}} আপুনাৰ লক্ষ্যতালিকাৰ পৰা আতৰোৱা হ'ল:",
'watchlistedit-raw-title'      => 'অশোধিত অনুসৰণ-তালিকা সম্পাদন কৰক',
'watchlistedit-raw-legend'     => 'অশোধিত অনুসৰণ-তালিকা সম্পাদন কৰক',
'watchlistedit-raw-titles'     => 'শীৰ্ষক:',
'watchlistedit-raw-submit'     => 'লক্ষ্য-তালিকা আপডেট কৰক',
'watchlistedit-raw-done'       => "আপুনাৰ লক্ষ্য-তালিকা আপডেট কৰা হ'ল",
'watchlistedit-raw-added'      => "{{PLURAL:$1|এটা শিৰোনামা|$1-টা শিৰোনামা}} যোগ কৰা হ'ল:",
'watchlistedit-raw-removed'    => "{{PLURAL:$1|এটা শিৰোনামা|$1-টা শিৰোনামা}} আঁতৰোৱা হ'ল:",

# Watchlist editing tools
'watchlisttools-view' => 'সংগতি থকা সাল-সলনিবোৰ চাওক',
'watchlisttools-edit' => 'লক্ষ্য-তালিকা চাওক আৰু সম্পাদনা কৰক',
'watchlisttools-raw'  => 'কেঁচা লক্ষ্য-তালিকা সম্পাদনা কৰক',

# Special:Version
'version'                   => 'সংস্কৰণ',
'version-specialpages'      => 'বিশেষ পৃষ্ঠাসমূহ',
'version-other'             => 'অন্য',
'version-version'           => '(সংস্কৰণ $1)',
'version-license'           => 'লাইচেঞ্চ',
'version-poweredby-credits' => "এই ৱিকি '''[http://www.mediawiki.org/ মিডিয়াৱিকিৰ]''' দ্বাৰা প্ৰচলিত , কপিৰাইট © ২০০১-$1 $2.",
'version-poweredby-others'  => 'অন্য',
'version-software-product'  => 'পণ্য',
'version-software-version'  => 'সংস্কৰণ',

# Special:FilePath
'filepath'        => 'ফাইল পথ',
'filepath-page'   => 'ফাইল:',
'filepath-submit' => 'যাওঁক',

# Special:FileDuplicateSearch
'fileduplicatesearch'           => 'প্ৰতিলিপি পৃষ্ঠাসমূহ অনুসন্ধান কৰক',
'fileduplicatesearch-legend'    => 'প্ৰতিলিপিৰ বাবে অনুসন্ধান কৰক',
'fileduplicatesearch-filename'  => 'ফাইলনাম:',
'fileduplicatesearch-submit'    => 'সন্ধান কৰক',
'fileduplicatesearch-noresults' => 'কোনো "$1" নামৰ ফাইল সন্ধান পোৱা নগল ।',

# Special:SpecialPages
'specialpages'                   => 'বিশেষ পৃষ্ঠাসমূহ',
'specialpages-note'              => '* সাধাৰণ বিশেষ পৃষ্ঠাসমূহ।
* <span class="mw-specialpagerestricted">সীমাবদ্ধ বিশেষ পৃষ্ঠাসমূহ।</span>
* <span class="mw-specialpagecached">কেশ্ব কৰা বিশেষ পৃষ্ঠাসমূহ।</span>',
'specialpages-group-maintenance' => 'তত্বাৱধানৰ কাৰ্যবিবৰণীসমূহ',
'specialpages-group-other'       => 'অন্যান্য বিশেষ পৃষ্ঠাসমূহ',
'specialpages-group-login'       => 'প্ৰৱেশ/সদস্যভুক্তি',
'specialpages-group-changes'     => 'সাম্প্ৰতিক সালসলনি আৰু লগসমূহ',
'specialpages-group-media'       => 'মিডিয়া বিবৰণী আৰু আপলোডসমূহ',
'specialpages-group-users'       => 'সদস্যবৃন্দ আৰু অধিকাৰসমূহ',
'specialpages-group-highuse'     => 'অধিক ব্যবহৃত পৃষ্ঠাসমূহ',
'specialpages-group-pages'       => 'পৃষ্ঠাৰ তালিকাসমূহ',
'specialpages-group-pagetools'   => 'পৃষ্ঠা সা-সঁজুলি',
'specialpages-group-wiki'        => 'ৱিকি তথ্য আৰু সা-সঁজুলি',
'specialpages-group-redirects'   => 'পুনৰ্নিৰ্দেশ কৰা বিশেষ পৃষ্ঠাসমূহ',
'specialpages-group-spam'        => 'স্পেম সা-সঁজুলি',

# Special:BlankPage
'blankpage'              => 'খালী পৃষ্ঠা',
'intentionallyblankpage' => 'এই পৃষ্ঠা ইচ্ছাকৃত ভাবে খালি ৰখা হৈছে',

# Special:Tags
'tag-filter-submit'       => 'সংশোধন',
'tags-title'              => 'টেগসমূহ',
'tags-tag'                => 'টেগ নাম',
'tags-description-header' => 'অৰ্থৰ পূৰ্ণ বৰ্ণনা',
'tags-edit'               => 'সম্পাদনা',
'tags-hitcount'           => '$1 {{PLURAL:$1|সাল-সলনি|সাল-সলনিসমূহ}}',

# Special:ComparePages
'comparepages'     => 'পৃষ্ঠা তুলনা কৰক',
'compare-selector' => 'পৃষ্ঠা পুনৰীক্ষন তুলনা কৰক',
'compare-page1'    => 'পৃষ্ঠা ১',
'compare-page2'    => 'পৃষ্ঠা ২',
'compare-rev1'     => 'পুনৰীক্ষন ১',
'compare-rev2'     => 'পুনৰীক্ষন ২',
'compare-submit'   => 'তুলনা কৰক',

# Database error messages
'dberr-header' => 'এই ৱিকিট কেতবোৰ জেং লাগিছে',

# HTML forms
'htmlform-select-badoption'    => 'আপুনি ধায্য কৰা মান উপযুক্ত বিকল্প নহয়',
'htmlform-int-invalid'         => 'অপুনি ধায্য কৰা মান ইন্টেজাৰ (integer) নহয়',
'htmlform-float-invalid'       => 'অপুনি ধায্য কৰা মান সংখ্যা নহয়',
'htmlform-int-toolow'          => 'আপুনি ধায্য কৰা মান ন্যূনতম $1 তকৈ তলত',
'htmlform-int-toohigh'         => 'আপুনি ধায্য কৰা মান অধিকতম $1 তকৈ ওপৰত',
'htmlform-required'            => 'এই মান আৱশ্যক',
'htmlform-submit'              => 'দাখিল কৰক',
'htmlform-reset'               => 'সাল-সলনি পণ্ড কৰক',
'htmlform-selectorother-other' => 'অন্য',

# Special:DisableAccount
'disableaccount'            => 'সদস্য একাউন্ট প্ৰতিবন্ধ কৰক',
'disableaccount-user'       => 'সদস্যনাম:',
'disableaccount-reason'     => 'কাৰণ:',
'disableaccount-nosuchuser' => '"$1" নামৰ সদস্য একাউন্টৰ অস্তিত্ব নাই ।',

);

<?php
/** Assamese (অসমীয়া)
 *
 * @ingroup Language
 * @file
 *
 * @author Priyankoo
 * @author Siebrand
 */

$fallback='hi';

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
# Dates
'sun'         => 'দেও',
'mon'         => 'সোম',
'tue'         => 'মংগল',
'wed'         => 'বুধ',
'thu'         => 'বৃহস্পতি',
'fri'         => 'শুক্র',
'sat'         => 'শনি',
'january'     => 'জানুৱাৰী',
'february'    => 'ফেব্রুৱাৰী',
'march'       => 'মাৰ্চ',
'april'       => 'এপ্ৰিল',
'may_long'    => "মে'",
'june'        => 'জুন',
'july'        => 'জুলাই',
'august'      => 'আগষ্ট',
'september'   => 'চেপ্তেম্বৰ',
'october'     => 'অক্টোবৰ',
'november'    => 'নৱেম্বৰ',
'december'    => 'ডিচেম্বৰ',
'january-gen' => 'জানুৱাৰী',
'april-gen'   => 'এপ্ৰিল',
'august-gen'  => 'আগষ্ট',
'jan'         => 'জানু:',
'feb'         => 'ফেব্রু:',
'apr'         => 'এপ্ৰিল',
'may'         => 'মে',
'jun'         => 'জুন',
'jul'         => 'জুলাই',
'aug'         => 'আগষ্ট',
'sep'         => 'চেপ্ত:',
'oct'         => 'অক্টো:',
'nov'         => 'নৱে:',
'dec'         => 'ডিচে:',

'cancel'        => 'ৰদ কৰক',
'qbfind'        => 'সন্ধান কৰক',
'qbbrowse'      => 'চাবলৈ(ব্রাওজ)',
'qbedit'        => 'সম্পাদনা',
'qbpageoptions' => 'এই পৃষ্ঠা',
'qbpageinfo'    => 'প্রসংগ(কনটেক্স্ট)',
'moredotdotdot' => 'ক্রমশ:...',
'mypage'        => 'মোৰ পৃষ্ঠা',
'mytalk'        => 'মোৰ কথা-বতৰা',
'anontalk'      => 'এই IP-ত যোগাযোগ কৰক',
'navigation'    => 'দিকদৰ্শন',

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
'protect_change'    => 'সংৰক্ষণবিধিৰ পৰিবৰ্তন',
'protectthispage'   => 'বৰ্তমান পৃষ্ঠাৰ সংৰক্ষণবিধিৰ পৰিবৰ্তন',
'unprotect'         => 'সংৰক্ষণমুক্ত কৰক',
'unprotectthispage' => 'এই পৃষ্ঠা সংৰক্ষণমুক্ত কৰক',
'newpage'           => 'নতুন পৃষ্ঠা',
'talkpage'          => 'এই পৃষ্ঠা সম্পৰ্কে আলোচনা',
'talkpagelinktext'  => 'বাৰ্তালাপ',
'specialpage'       => 'বিশেষ পৃষ্ঠা',
'personaltools'     => 'ব্যক্তিগত সৰঞ্জাম',
'postcomment'       => 'আপোনাৰ মতামত দিয়ক',
'articlepage'       => 'প্রবন্ধ',
'talk'              => 'বাৰ্তালাপ',
'views'             => 'দৰ্শ(ভিউ)',
'toolbox'           => 'সাজ-সৰঞ্জাম',
'userpage'          => 'ভোক্তাৰ(ইউজাৰ) পৃষ্ঠা',
'projectpage'       => 'প্রকল্প পৃষ্ঠা',
'redirectedfrom'    => '($1 ৰ পৰা)',
'jumpto'            => 'গম্যাৰ্থে',
'jumptonavigation'  => 'দিকদৰ্শন',
'jumptosearch'      => 'সন্ধানাৰ্থে',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}}ৰ ইতিবৃত্ত',
'aboutpage'            => 'Project:ইতিবৃত্ত',
'copyrightpage'        => '{{ns:project}}:স্বত্ব',
'edithelp'             => 'সম্পাদনাৰ বাবে সহায়',
'edithelppage'         => 'Help:সম্পাদনা',
'mainpage'             => 'বেটুপাত',
'mainpage-description' => 'বেটুপাত',
'privacypage'          => 'Project:গোপনীয়তাৰ নীতি',

'retrievedfrom'   => '"$1" -ৰ পৰা সংকলিত',
'editsection'     => 'সম্পাদ',
'editsectionhint' => '$1 খণ্ডৰ সম্পাদনা',
'toc'             => 'সূচী',
'showtoc'         => 'দেখুৱাব লাগে',
'hidetoc'         => 'দেখুৱাব নালাগে',

# Login and logout pages
'nav-login-createaccount' => 'প্রৱেশ/সদস্যভুক্তি',
'userlogin'               => 'প্রৱেশ/সদস্যভুক্তি',

# Edit pages
'summary'     => 'সাৰাংশ',
'watchthis'   => 'এই পৃষ্ঠাটো অনুসৰণ-সূচীভুক্ত কৰক',
'showpreview' => 'খচৰা',
'accmailtext' => '"$1"ৰ পাছৱৰ্ড $2 লৈ পঠোৱা হ\'ল|',

# History pages
'revisionasof' => '$1 তম ভাষ্য',

# Diffs
'lineno'   => 'পংক্তি $1:',
'editundo' => 'পূৰ্ববতাৰ্থে',

# Search results
'powersearch' => 'অতিসন্ধান',

# Preferences page
'mypreferences' => 'মোৰ পচন্দ',

# Recent changes
'rcshowhideminor' => '$1 -সংখ্যক নগণ্য সম্পাদনা',
'hist'            => 'ইতিবৃত্ত',
'hide'            => 'দেখুৱাব নালাগে',
'minoreditletter' => 'ন:',
'newpageletter'   => 'ন:',
'boteditletter'   => 'য:',

# Recent changes linked
'recentchangeslinked' => 'প্রাসংগিক সম্পাদনানমূহ',

# Upload
'upload' => "ফাইল আপল'ড",

# Image description page
'filehist'   => 'ফাইলৰ ইতিবৃত্ত',
'imagelinks' => 'সূত্ৰসমূহ',

# Random page
'randompage' => 'আকস্মিক পৃষ্ঠা',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|বাইট|বাইট}}',

# Special:Allpages
'alphaindexline' => '$1 -ৰ পৰা $2 -লৈ',
'allpagesprev'   => 'আগৰ',

# Watchlist
'watchlist'   => 'মই অনুসৰণ কৰা পৃষ্ঠাবিলাকৰ তালিকা',
'mywatchlist' => 'মোৰ অনুসৰণ-তালিকা',
'watch'       => 'অনুসৰণাৰ্থে',
'unwatch'     => 'অনুসৰণ কৰিব নালাগে',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'অনুসৰণভুক্ত কৰা হৈ আছে.....',
'unwatching' => 'অনুসৰণমুক্ত কৰা হৈ আছে.....',

# Namespace form on various pages
'blanknamespace' => '(মুখ্য)',

# Contributions
'mycontris' => 'মোৰ অৱদানসমুহ',

# What links here
'whatlinkshere'       => 'এই পৃষ্ঠা ব্যৱ্হাৰ কৰিছে...',
'whatlinkshere-links' => '← সূত্রসমূহ',

# Block/unblock
'blocklink'    => 'সদস্যভুক্তি ৰদ',
'contribslink' => 'অবদান',

# Thumbnails
'thumbnail-more' => 'বিবৰ্ধনাৰ্থে',

# Tooltip help for the actions
'tooltip-pt-mytalk'       => 'মোৰ বাৰ্তালাপ-পৃষ্ঠা',
'tooltip-pt-login'        => 'অত্যাবশ্যক নহলেও লগ-ইন কৰা বাঞ্চনীয়',
'tooltip-ca-talk'         => 'সংশ্লিষ্ট প্রৱন্ধ সম্পৰ্কীয় আলোচনা',
'tooltip-ca-watch'        => 'এই পৃষ্ঠাটো আপোনাৰ অনুসৰণ-তালিকাত যোগ কৰক',
'tooltip-search'          => '{{SITENAME}} -ত সন্ধানাৰ্থে',
'tooltip-n-mainpage'      => 'বেটুপাত খুলিবৰ কাৰণে',
'tooltip-n-portal'        => "এই প্রকল্পৰ ইতিবৃত্ত, আপুনি কেনেকৈ সহায় কৰিব পাৰে, ইত্যাদি (কি, ক'ত কিয় বিখ্যাত!!)।",
'tooltip-n-recentchanges' => 'শেহতীয়া সালসলনিসমূহৰ সূচী',
'tooltip-n-randompage'    => 'অ-পূৰ্বনিৰ্ধাৰিতভাবে যিকোনো এটা পৃষ্ঠা দেখুৱাবৰ কাৰণে',
'tooltip-n-help'          => 'সহায়ৰ বাবে ইয়াত ক্লিক কৰক',
'tooltip-n-sitesupport'   => 'আমাক সহায় কৰক!',
'tooltip-t-upload'        => "ফাইল আপল'ড-অৰ অৰ্থে",
'tooltip-t-specialpages'  => 'বিশেষ পৃষ্ঠাসমূ্হৰ সূচী',

# Special:SpecialPages
'specialpages' => 'বিশেষ পৃষ্ঠাসমূহ',

);

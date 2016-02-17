<?php
/** Assamese (অসমীয়া)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$namespaceNames = [
	NS_MEDIA            => 'মাধ্যম',
	NS_SPECIAL          => 'বিশেষ',
	NS_TALK             => 'বাৰ্তা',
	NS_USER             => 'সদস্য',
	NS_USER_TALK        => 'সদস্য_বাৰ্তা',
	NS_PROJECT_TALK     => '$1_বাৰ্তা',
	NS_FILE             => 'চিত্ৰ',
	NS_FILE_TALK        => 'চিত্ৰ_বাৰ্তা',
	NS_MEDIAWIKI        => 'মিডিয়াৱিকি',
	NS_MEDIAWIKI_TALK   => 'মিডিয়াৱিকি_আলোচনা',
	NS_TEMPLATE         => 'সাঁচ',
	NS_TEMPLATE_TALK    => 'সাঁচ_বাৰ্তা',
	NS_HELP             => 'সহায়',
	NS_HELP_TALK        => 'সহায়_বাৰ্তা',
	NS_CATEGORY         => 'শ্ৰেণী',
	NS_CATEGORY_TALK    => 'শ্ৰেণী_বাৰ্তা',
];

$namespaceAliases = [
	'विशेष' => NS_SPECIAL,
	'वार्ता' => NS_TALK,
	'বার্তা' => NS_TALK,
	'सदस्य' => NS_USER,
	'सदस्य_वार्ता' => NS_USER_TALK,
	'সদস্য_বার্তা' => NS_USER_TALK,
	'$1_वार्ता' => NS_PROJECT_TALK,
	'$1_বার্তা' => NS_PROJECT_TALK,
	'चित्र' => NS_FILE,
	'चित्र_वार्ता' => NS_FILE_TALK,
	'চিত্র' => NS_FILE,
	'চিত্র_বার্তা' => NS_FILE_TALK,
	'মেডিয়াৱিকি' => NS_MEDIAWIKI,
	'মেডিয়াৱিকি_বাৰ্তা' => NS_MEDIAWIKI_TALK,
	'MediaWiki_বার্তা' => NS_MEDIAWIKI_TALK,
	'साँचा' => NS_TEMPLATE,
	'साँचा_वार्ता' => NS_TEMPLATE_TALK,
	'সাঁচ_বার্তা' => NS_TEMPLATE_TALK,
	'সহায়_বার্তা' => NS_HELP_TALK,
	'श्रेणी' => NS_CATEGORY,
	'श्रेणी_वार्ता' => NS_CATEGORY_TALK,
	'শ্রেণী' => NS_CATEGORY,
	'শ্রেণী_বার্তা' => NS_CATEGORY_TALK,
];

$specialPageAliases = [
	'Activeusers'               => [ 'সক্ৰিয়_সদস্যসকল' ],
	'Allmessages'               => [ 'সকলোবোৰ_বাৰ্তা' ],
	'Allpages'                  => [ 'সকলোবোৰ_পৃষ্ঠা' ],
	'Ancientpages'              => [ 'পুৰণি_পৃষ্ঠা' ],
	'Badtitle'                  => [ 'ভুল_শিৰোনাম' ],
	'Blankpage'                 => [ 'উকা_পৃষ্ঠা' ],
	'Block'                     => [ 'অৱৰোধ', 'আই_পি_অৱৰোধ', 'সদস্য_অৱৰোধ' ],
	'Booksources'               => [ 'গ্ৰন্থৰ_উত্স' ],
	'BrokenRedirects'           => [ 'ভঙা_পূণঃনিৰ্দেশনাসমূহ' ],
	'Categories'                => [ 'শ্ৰেণীসমূহ' ],
	'ChangeEmail'               => [ 'ইমেইল_সলনি_কৰক' ],
	'ChangePassword'            => [ 'গুপ্তশব্দ_সলনি_কৰক', 'গুপ্তশব্দ_সলনি' ],
	'ComparePages'              => [ 'তুলনা_কৰক' ],
	'Confirmemail'              => [ 'ইমেইল_নিশ্চিত_কৰক' ],
	'Contributions'             => [ 'বৰঙনিসমূহ', 'বৰঙনিদাতাসকল', 'বৰঙনি' ],
	'CreateAccount'             => [ 'সদস্যভুক্তি' ],
	'DeletedContributions'      => [ 'বিলোপ_কৰা_বৰঙনিসমূহ' ],
	'DoubleRedirects'           => [ 'দ্বি_পুনৰ্নিৰ্দেশনাসমূহ' ],
	'EditWatchlist'             => [ 'লক্ষ্যতালিকা_সম্পাদনা_কৰক' ],
	'Emailuser'                 => [ 'সদস্যলৈ_ই-মেইল_পঠিয়াওক' ],
	'ExpandTemplates'           => [ 'সাঁচবোৰ_প্ৰসাৰ_কৰক' ],
	'Export'                    => [ 'ৰপ্তানি' ],
	'Fewestrevisions'           => [ 'নূন্যতম_সংস্কৰণসমূহ' ],
	'FileDuplicateSearch'       => [ 'প্ৰতিলিপি_সনিবিষ্ট_নথি_অনুসন্ধান' ],
	'Filepath'                  => [ 'ফাইলৰ_পথ' ],
	'Import'                    => [ 'আমদানি' ],
	'Invalidateemail'           => [ 'অবৈধ_ই-মেইল' ],
	'JavaScriptTest'            => [ 'জাভাস্ক্ৰীপ্ত_পৰীক্ষা' ],
	'BlockList'                 => [ 'অৱৰোধৰ_তালিকা' ],
	'LinkSearch'                => [ 'সংযোগ_সন্ধান' ],
	'Listadmins'                => [ 'প্ৰশাসকৰ_তালিকা' ],
	'Listbots'                  => [ 'বটৰ_তালিকা' ],
	'Listfiles'                 => [ 'চিত্ৰ-তালিকা' ],
	'Listgrouprights'           => [ 'গোটৰ_অধিকাৰসমূহ' ],
	'Listredirects'             => [ 'পুনৰ্নিৰ্দেশনাসমূহৰ_তালিকা' ],
	'Listusers'                 => [ 'সদস্য-তালিকা' ],
	'Lockdb'                    => [ 'তথ্যকোষ_বন্ধ_কৰক' ],
	'Log'                       => [ 'অভিলেখ', 'অভিলেখসমূহ' ],
	'Lonelypages'               => [ 'অকলশৰীয়া_পৃষ্ঠা' ],
	'Longpages'                 => [ 'দীঘলীয়া_পৃষ্ঠাসমূহ' ],
	'MergeHistory'              => [ 'একত্ৰীকৰণ_ইতিহাস' ],
	'MIMEsearch'                => [ 'MIMEMIMEmmmgM_অনুসন্ধান' ],
	'Movepage'                  => [ 'পৃষ্ঠা_স্থানান্তৰ' ],
	'Mycontributions'           => [ 'মোৰ_বৰঙনি' ],
	'MyLanguage'                => [ 'মোৰ_ভাষা' ],
	'Mypage'                    => [ 'মোৰ_পৃষ্ঠা' ],
	'Mytalk'                    => [ 'মোৰ_কথোপকথন' ],
	'Myuploads'                 => [ 'মোৰ_আপল’ডসমূহ' ],
	'Newimages'                 => [ 'নতুন_চিত্ৰ' ],
	'Newpages'                  => [ 'পৰৱৰ্তী_পৃষ্ঠা' ],
	'PasswordReset'             => [ 'গুপ্তশব্দ_ঘূৰাই_আনক' ],
	'PermanentLink'             => [ 'স্থায়ী_সংযোগ' ],
	'Preferences'               => [ 'পচন্দ' ],
	'Protectedpages'            => [ 'সুৰক্ষিত_পৃষ্ঠাসমূহ' ],
	'Protectedtitles'           => [ 'সুৰক্ষিত_শিৰোনামসমূহ' ],
	'Randompage'                => [ 'আকস্মিক' ],
	'Randomredirect'            => [ 'আকস্মিক_পুনৰ্নিৰ্দেশনা' ],
	'Recentchanges'             => [ 'শেহতীয়া_সালসলনি' ],
	'Recentchangeslinked'       => [ 'সম্পৰ্কিত_সালসলনিসমূহ' ],
	'Revisiondelete'            => [ 'সংস্কৰণ_বিলোপ' ],
	'Search'                    => [ 'সন্ধান' ],
	'Shortpages'                => [ 'চমু_পৃষ্ঠা' ],
	'Specialpages'              => [ 'বিশেষ_পৃষ্ঠাসমূহ' ],
	'Statistics'                => [ 'পৰিসংখ্যা' ],
	'Tags'                      => [ 'টেগসমূহ' ],
	'Unblock'                   => [ 'অৱৰোধ_বাৰণ' ],
	'Uncategorizedcategories'   => [ 'অবিন্যস্ত_শ্ৰেণীসমূহ' ],
	'Uncategorizedimages'       => [ 'অবিন্যস্ত_চিত্ৰসমূহ' ],
	'Uncategorizedpages'        => [ 'অবিন্যস্ত_পৃষ্ঠাসমূহ' ],
	'Uncategorizedtemplates'    => [ 'অবিন্যস্ত_সাঁচসমূহ' ],
	'Undelete'                  => [ 'বিলোপ_ঘূৰাই_আনক' ],
	'Unlockdb'                  => [ 'তথ্যকোষ_খোলক' ],
	'Unusedcategories'          => [ 'অব্যৱহৃত_শ্ৰেণীসমূহ' ],
	'Unusedimages'              => [ 'অব্যৱহৃত_চিত্ৰসমূহ' ],
	'Unusedtemplates'           => [ 'অব্যৱহৃত_সাঁচসমূহ' ],
	'Unwatchedpages'            => [ 'লক্ষ্য_নৰখা_পৃষ্ঠাসমূহ' ],
	'Upload'                    => [ 'আপল’ড', 'বোজাই' ],
	'Userlogin'                 => [ 'সদস্যৰ_প্ৰৱেশ' ],
	'Userlogout'                => [ 'সদস্যৰ_প্ৰস্থান' ],
	'Userrights'                => [ 'সদস্যৰ_অধিকাৰ', 'প্ৰশাসক_সৃষ্টি', 'বট_সৃষ্টি' ],
	'Version'                   => [ 'সংস্কৰণ' ],
	'Wantedcategories'          => [ 'আকাংক্ষিত_শ্ৰেণীসমূহ' ],
	'Wantedfiles'               => [ 'কাম্য_ফাইলসমূহ' ],
	'Wantedpages'               => [ 'কাম্য_পৃষ্ঠাসমূহ', 'ভগা_সংযোগসমূহ' ],
	'Wantedtemplates'           => [ 'কাম্য_সাঁচসমূহ' ],
	'Watchlist'                 => [ 'লক্ষ্যতালিকা' ],
	'Whatlinkshere'             => [ 'পৃষ্ঠালৈ_থকা_সংযোগসমূহ' ],
	'Withoutinterwiki'          => [ 'আন্তঃৱিকিবিহীন' ],
];

$magicWords = [
	'special'                   => [ '0', 'বিশেষ', 'special' ],
	'hiddencat'                 => [ '1', '__গোপন_শ্ৰেণী__', '__HIDDENCAT__' ],
	'pagesize'                  => [ '1', 'পৃষ্ঠাৰ_আকাৰ', 'PAGESIZE' ],
	'index'                     => [ '1', '__সূচী__', '__INDEX__' ],
];

$digitTransformTable = [
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
];

$digitGroupingPattern = "##,##,###";


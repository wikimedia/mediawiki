<?php
/** Assamese (অসমীয়া)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$namespaceNames = array(
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
);

$namespaceAliases = array(
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
);

$specialPageAliases = array(
	'Activeusers'               => array( 'সক্ৰিয়_সদস্যসকল' ),
	'Allmessages'               => array( 'সকলোবোৰ_বাৰ্তা' ),
	'Allpages'                  => array( 'সকলোবোৰ_পৃষ্ঠা' ),
	'Ancientpages'              => array( 'পুৰণি_পৃষ্ঠা' ),
	'Badtitle'                  => array( 'ভুল_শিৰোনাম' ),
	'Blankpage'                 => array( 'উকা_পৃষ্ঠা' ),
	'Block'                     => array( 'অৱৰোধ', 'আই_পি_অৱৰোধ', 'সদস্য_অৱৰোধ' ),
	'Booksources'               => array( 'গ্ৰন্থৰ_উত্স' ),
	'BrokenRedirects'           => array( 'ভঙা_পূণঃনিৰ্দেশনাসমূহ' ),
	'Categories'                => array( 'শ্ৰেণীসমূহ' ),
	'ChangeEmail'               => array( 'ইমেইল_সলনি_কৰক' ),
	'ChangePassword'            => array( 'গুপ্তশব্দ_সলনি_কৰক', 'গুপ্তশব্দ_সলনি' ),
	'ComparePages'              => array( 'তুলনা_কৰক' ),
	'Confirmemail'              => array( 'ইমেইল_নিশ্চিত_কৰক' ),
	'Contributions'             => array( 'বৰঙনিসমূহ', 'বৰঙনিদাতাসকল', 'বৰঙনি' ),
	'CreateAccount'             => array( 'সদস্যভুক্তি' ),
	'DeletedContributions'      => array( 'বিলোপ_কৰা_বৰঙনিসমূহ' ),
	'DoubleRedirects'           => array( 'দ্বি_পুনৰ্নিৰ্দেশনাসমূহ' ),
	'EditWatchlist'             => array( 'লক্ষ্যতালিকা_সম্পাদনা_কৰক' ),
	'Emailuser'                 => array( 'সদস্যলৈ_ই-মেইল_পঠিয়াওক' ),
	'ExpandTemplates'           => array( 'সাঁচবোৰ_প্ৰসাৰ_কৰক' ),
	'Export'                    => array( 'ৰপ্তানি' ),
	'Fewestrevisions'           => array( 'নূন্যতম_সংস্কৰণসমূহ' ),
	'FileDuplicateSearch'       => array( 'প্ৰতিলিপি_সনিবিষ্ট_নথি_অনুসন্ধান' ),
	'Filepath'                  => array( 'ফাইলৰ_পথ' ),
	'Import'                    => array( 'আমদানি' ),
	'Invalidateemail'           => array( 'অবৈধ_ই-মেইল' ),
	'JavaScriptTest'            => array( 'জাভাস্ক্ৰীপ্ত_পৰীক্ষা' ),
	'BlockList'                 => array( 'অৱৰোধৰ_তালিকা' ),
	'LinkSearch'                => array( 'সংযোগ_সন্ধান' ),
	'Listadmins'                => array( 'প্ৰশাসকৰ_তালিকা' ),
	'Listbots'                  => array( 'বটৰ_তালিকা' ),
	'Listfiles'                 => array( 'চিত্ৰ-তালিকা' ),
	'Listgrouprights'           => array( 'গোটৰ_অধিকাৰসমূহ' ),
	'Listredirects'             => array( 'পুনৰ্নিৰ্দেশনাসমূহৰ_তালিকা' ),
	'Listusers'                 => array( 'সদস্য-তালিকা' ),
	'Lockdb'                    => array( 'তথ্যকোষ_বন্ধ_কৰক' ),
	'Log'                       => array( 'অভিলেখ', 'অভিলেখসমূহ' ),
	'Lonelypages'               => array( 'অকলশৰীয়া_পৃষ্ঠা' ),
	'Longpages'                 => array( 'দীঘলীয়া_পৃষ্ঠাসমূহ' ),
	'MergeHistory'              => array( 'একত্ৰীকৰণ_ইতিহাস' ),
	'MIMEsearch'                => array( 'MIMEMIMEmmmgM_অনুসন্ধান' ),
	'Movepage'                  => array( 'পৃষ্ঠা_স্থানান্তৰ' ),
	'Mycontributions'           => array( 'মোৰ_বৰঙনি' ),
	'MyLanguage'                => array( 'মোৰ_ভাষা' ),
	'Mypage'                    => array( 'মোৰ_পৃষ্ঠা' ),
	'Mytalk'                    => array( 'মোৰ_কথোপকথন' ),
	'Myuploads'                 => array( 'মোৰ_আপল’ডসমূহ' ),
	'Newimages'                 => array( 'নতুন_চিত্ৰ' ),
	'Newpages'                  => array( 'পৰৱৰ্তী_পৃষ্ঠা' ),
	'PasswordReset'             => array( 'গুপ্তশব্দ_ঘূৰাই_আনক' ),
	'PermanentLink'             => array( 'স্থায়ী_সংযোগ' ),
	'Preferences'               => array( 'পচন্দ' ),
	'Protectedpages'            => array( 'সুৰক্ষিত_পৃষ্ঠাসমূহ' ),
	'Protectedtitles'           => array( 'সুৰক্ষিত_শিৰোনামসমূহ' ),
	'Randompage'                => array( 'আকস্মিক' ),
	'Randomredirect'            => array( 'আকস্মিক_পুনৰ্নিৰ্দেশনা' ),
	'Recentchanges'             => array( 'শেহতীয়া_সালসলনি' ),
	'Recentchangeslinked'       => array( 'সম্পৰ্কিত_সালসলনিসমূহ' ),
	'Revisiondelete'            => array( 'সংস্কৰণ_বিলোপ' ),
	'Search'                    => array( 'সন্ধান' ),
	'Shortpages'                => array( 'চমু_পৃষ্ঠা' ),
	'Specialpages'              => array( 'বিশেষ_পৃষ্ঠাসমূহ' ),
	'Statistics'                => array( 'পৰিসংখ্যা' ),
	'Tags'                      => array( 'টেগসমূহ' ),
	'Unblock'                   => array( 'অৱৰোধ_বাৰণ' ),
	'Uncategorizedcategories'   => array( 'অবিন্যস্ত_শ্ৰেণীসমূহ' ),
	'Uncategorizedimages'       => array( 'অবিন্যস্ত_চিত্ৰসমূহ' ),
	'Uncategorizedpages'        => array( 'অবিন্যস্ত_পৃষ্ঠাসমূহ' ),
	'Uncategorizedtemplates'    => array( 'অবিন্যস্ত_সাঁচসমূহ' ),
	'Undelete'                  => array( 'বিলোপ_ঘূৰাই_আনক' ),
	'Unlockdb'                  => array( 'তথ্যকোষ_খোলক' ),
	'Unusedcategories'          => array( 'অব্যৱহৃত_শ্ৰেণীসমূহ' ),
	'Unusedimages'              => array( 'অব্যৱহৃত_চিত্ৰসমূহ' ),
	'Unusedtemplates'           => array( 'অব্যৱহৃত_সাঁচসমূহ' ),
	'Unwatchedpages'            => array( 'লক্ষ্য_নৰখা_পৃষ্ঠাসমূহ' ),
	'Upload'                    => array( 'আপল’ড', 'বোজাই' ),
	'Userlogin'                 => array( 'সদস্যৰ_প্ৰৱেশ' ),
	'Userlogout'                => array( 'সদস্যৰ_প্ৰস্থান' ),
	'Userrights'                => array( 'সদস্যৰ_অধিকাৰ', 'প্ৰশাসক_সৃষ্টি', 'বট_সৃষ্টি' ),
	'Version'                   => array( 'সংস্কৰণ' ),
	'Wantedcategories'          => array( 'আকাংক্ষিত_শ্ৰেণীসমূহ' ),
	'Wantedfiles'               => array( 'কাম্য_ফাইলসমূহ' ),
	'Wantedpages'               => array( 'কাম্য_পৃষ্ঠাসমূহ', 'ভগা_সংযোগসমূহ' ),
	'Wantedtemplates'           => array( 'কাম্য_সাঁচসমূহ' ),
	'Watchlist'                 => array( 'লক্ষ্যতালিকা' ),
	'Whatlinkshere'             => array( 'পৃষ্ঠালৈ_থকা_সংযোগসমূহ' ),
	'Withoutinterwiki'          => array( 'আন্তঃৱিকিবিহীন' ),
);

$magicWords = array(
	'special'                   => array( '0', 'বিশেষ', 'special' ),
	'hiddencat'                 => array( '1', '__গোপন_শ্ৰেণী__', '__HIDDENCAT__' ),
	'pagesize'                  => array( '1', 'পৃষ্ঠাৰ_আকাৰ', 'PAGESIZE' ),
	'index'                     => array( '1', '__সূচী__', '__INDEX__' ),
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

$digitGroupingPattern = "##,##,###";


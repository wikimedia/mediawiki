<?php
/** Punjabi (ਪੰਜਾਬੀ)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author AS Alam
 * @author Aalam
 * @author Amire80
 * @author Anjalikaushal
 * @author Babanwalia
 * @author Gman124
 * @author Guglani
 * @author Jimidar
 * @author Kaganer
 * @author Raj Singh
 * @author Satdeep gill
 * @author Saurabh123
 * @author Sukh
 * @author Surinder.wadhawan
 * @author TariButtar
 * @author VibhasKS
 * @author Xqt
 * @author Ævar Arnfjörð Bjarmason
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'ਮੀਡੀਆ',
	NS_SPECIAL          => 'ਖ਼ਾਸ',
	NS_TALK             => 'ਗੱਲ-ਬਾਤ',
	NS_USER             => 'ਵਰਤੋਂਕਾਰ',
	NS_USER_TALK        => 'ਵਰਤੋਂਕਾਰ_ਗੱਲ-ਬਾਤ',
	NS_PROJECT_TALK     => '$1_ਗੱਲ-ਬਾਤ',
	NS_FILE             => 'ਤਸਵੀਰ',
	NS_FILE_TALK        => 'ਤਸਵੀਰ_ਗੱਲ-ਬਾਤ',
	NS_MEDIAWIKI        => 'ਮੀਡੀਆਵਿਕੀ',
	NS_MEDIAWIKI_TALK   => 'ਮੀਡੀਆਵਿਕੀ_ਗੱਲ-ਬਾਤ',
	NS_TEMPLATE         => 'ਫਰਮਾ',
	NS_TEMPLATE_TALK    => 'ਫਰਮਾ_ਗੱਲ-ਬਾਤ',
	NS_HELP             => 'ਮਦਦ',
	NS_HELP_TALK        => 'ਮਦਦ_ਗੱਲ-ਬਾਤ',
	NS_CATEGORY         => 'ਸ਼੍ਰੇਣੀ',
	NS_CATEGORY_TALK    => 'ਸ਼੍ਰੇਣੀ_ਗੱਲ-ਬਾਤ',
);

$namespaceAliases = array(
	'ਖਾਸ' => NS_SPECIAL,
	'ਚਰਚਾ' => NS_TALK,
	'ਮੈਂਬਰ' => NS_USER,
	'ਮੈਂਬਰ_ਚਰਚਾ' => NS_USER_TALK,
	'ਵਰਤੌਂਕਾਰ' => NS_USER,
	'ਵਰਤੌਂਕਾਰ_ਗੱਲ-ਬਾਤ' => NS_USER_TALK,
	'$1_ਚਰਚਾ' => NS_PROJECT_TALK,
	'ਤਸਵੀਰ_ਚਰਚਾ' => NS_FILE_TALK,
	'ਮੀਡੀਆਵਿਕਿ' => NS_MEDIAWIKI,
	'ਮੀਡੀਆਵਿਕਿ_ਚਰਚਾ' => NS_MEDIAWIKI_TALK,
	'ਨਮੂਨਾ' => NS_TEMPLATE,
	'ਨਮੂਨਾ_ਚਰਚਾ' => NS_TEMPLATE_TALK,
	'ਮਦਦ_ਚਰਚਾ' => NS_HELP_TALK,
	'ਸ਼੍ਰੇਣੀ_ਚਰਚਾ' => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'ਸਰਗਰਮ_ਵਰਤੋਂਕਾਰ' ),
	'Allmessages'               => array( 'ਸਾਰੇ_ਸਨੇਹੇ' ),
	'AllMyUploads'              => array( 'ਮੇਰੇ_ਸਾਰੇ_ਅੱਪਲੋਡ' ),
	'Allpages'                  => array( 'ਸਾਰੇ_ਸਫ਼ੇ' ),
	'Ancientpages'              => array( 'ਪੁਰਾਣੇ_ਸਫ਼ੇ' ),
	'Badtitle'                  => array( 'ਖ਼ਰਾਬ_ਸਿਰਲੇਖ' ),
	'Blankpage'                 => array( 'ਖ਼ਾਲੀ_ਸਫ਼ਾ' ),
	'Block'                     => array( 'ਪਾਬੰਦੀ_ਲਾਓ', 'IP_’ਤੇ_ਪਾਬੰਦੀ_ਲਾਓ', 'ਵਰਤੋਂਕਾਰ_’ਤੇ_ਪਾਬੰਦੀ_ਲਾਓ' ),
	'Booksources'               => array( 'ਕਿਤਾਬ_ਸਰੋਤ' ),
	'BrokenRedirects'           => array( 'ਟੁੱਟੇ_ਰੀਡਿਰੈਕਟ' ),
	'Categories'                => array( 'ਸ਼੍ਰੇਣੀਆਂ', 'ਵਰਗ' ),
	'ChangeEmail'               => array( 'ਈ-ਮੇਲ_ਬਦਲੋ', 'ਈਮੇਲ_ਬਦਲੋ' ),
	'ChangePassword'            => array( 'ਪਾਸਵਰਡ_ਬਦਲੋ', 'ਪਾਸਵਰਡ_ਰੀਸੈੱਟ_ਕਰੋ' ),
	'ComparePages'              => array( 'ਸਫ਼ਿਆਂ_ਦੀ_ਤੁਲਨਾ_ਕਰੋ' ),
	'Confirmemail'              => array( 'ਈ-ਮੇਲ_ਤਸਦੀਕ_ਕਰੋ', 'ਈਮੇਲ_ਤਸਦੀਕ_ਕਰੋ' ),
	'Contributions'             => array( 'ਯੋਗਦਾਨ', 'ਹਿੱਸੇਦਾਰੀ' ),
	'CreateAccount'             => array( 'ਖਾਤਾ_ਬਣਾਓ' ),
	'Deadendpages'              => array( 'ਬੰਦ_ਸਫ਼ੇ' ),
	'DeletedContributions'      => array( 'ਮਿਟਾਏ_ਯੋਗਦਾਨ' ),
	'Diff'                      => array( 'ਫ਼ਰਕ' ),
	'DoubleRedirects'           => array( 'ਦੂਹਰੇ_ਰੀਡਿਰੈਕਟ' ),
	'EditWatchlist'             => array( 'ਨਿਗਰਾਨੀ-ਲਿਸਟ_ਸੋਧੋ', 'ਨਿਗਰਾਨੀਲਿਸਟ_ਸੋਧੋ' ),
	'Emailuser'                 => array( 'ਵਰਤੋਂਕਾਰ_ਨੂੰ_ਈ-ਮੇਲ_ਕਰੋ' ),
	'ExpandTemplates'           => array( 'ਫਰਮੇ_ਖੋਲ੍ਹੋ' ),
	'Export'                    => array( 'ਨਿਰਯਾਤ' ),
	'Fewestrevisions'           => array( 'ਸਭ_ਤੋਂ_ਘੱਟ_ਰੀਵਿਜ਼ਨਾਂ' ),
	'FileDuplicateSearch'       => array( 'ਨਕਲੀ_ਫ਼ਾਈਲ_ਖੋਜੋ', 'ਨਕਲੀ_ਫ਼ਾਇਲ_ਖੋਜੋ' ),
	'Filepath'                  => array( 'ਫ਼ਾਈਲ_ਪਥ', 'ਫ਼ਾਇਲ_ਪਥ' ),
	'Import'                    => array( 'ਆਯਾਤ' ),
	'Invalidateemail'           => array( 'ਗਲਤ_ਈ-ਮੇਲ_ਪਤਾ' ),
	'JavaScriptTest'            => array( 'ਜਾਵਾਸਕ੍ਰਿਪਟ_ਪਰਖ' ),
	'BlockList'                 => array( 'ਪਾਬੰਦੀਆਂ_ਦੀ_ਲਿਸਟ' ),
	'LinkSearch'                => array( 'ਲਿੰਕ_ਖੋਜੋ', 'ਕੜੀ_ਖੋਜੋ' ),
	'Listadmins'                => array( 'ਪ੍ਰਬੰਧਕਾਂ_ਦੀ_ਲਿਸਟ' ),
	'Listbots'                  => array( 'ਬੋਟ_ਲਿਸਟ' ),
	'Listfiles'                 => array( 'ਫ਼ਾਈਲ_ਲਿਸਟ', 'ਫ਼ਾਇਲ_ਲਿਸਟ', 'ਤਸਵੀਰ_ਲਿਸਟ' ),
	'Listgrouprights'           => array( 'ਵਰਤੋਂਕਾਰ_ਹੱਕਾਂ_ਦੀ_ਲਿਸਟ' ),
	'Listredirects'             => array( 'ਰੀਡਿਰੈਕਟਾਂ_ਦੀ_ਲਿਸਟ' ),
	'ListDuplicatedFiles'       => array( 'ਨਕਲੀ_ਫ਼ਾਇਲ_ਲਿਸਟ' ),
	'Listusers'                 => array( 'ਵਰਤੋਂਕਾਰਾਂ_ਦੀ_ਲਿਸਟ' ),
	'Lockdb'                    => array( 'ਡੈਟਾਬੇਸ_’ਤੇ_ਤਾਲਾ_ਲਗਾਓ' ),
	'Log'                       => array( 'ਚਿੱਠਾ', 'ਚਿੱਠੇ' ),
	'Lonelypages'               => array( 'ਇਕੱਲੇ_ਸਫ਼ੇ' ),
	'Longpages'                 => array( 'ਲੰਬੇ_ਸਫ਼ੇ' ),
	'MergeHistory'              => array( 'ਰਲਾਉਣ_ਦਾ_ਅਤੀਤ', 'ਰਲ਼ਾਉਣ_ਦਾ_ਅਤੀਤ' ),
	'MIMEsearch'                => array( 'MIME_ਖੋਜੋ' ),
	'Mostcategories'            => array( 'ਸਭ_ਤੋਂ_ਵੱਧ_ਸ਼੍ਰੇਣੀਆਂ' ),
	'Mostimages'                => array( 'ਸਭ_ਤੋਂ_ਵੱਧ_ਜੁੜੀਆਂ_ਫ਼ਾਈਲਾਂ' ),
	'Mostinterwikis'            => array( 'ਸਭ_ਤੋਂ_ਵੱਧ_ਇੰਟਰਵਿਕੀ' ),
	'Mostlinked'                => array( 'ਸਭ_ਤੋਂ_ਵੱਧ_ਜੁੜੇ_ਸਫ਼ੇ' ),
	'Mostlinkedcategories'      => array( 'ਸਭ_ਤੋਂ_ਵੱਧ_ਜੁੜੀਆਂ_ਸ਼੍ਰੇਣੀਆਂ' ),
	'Mostlinkedtemplates'       => array( 'ਸਭ_ਤੋਂ_ਵੱਧ_ਜੁੜੇ_ਫਰਮੇ' ),
	'Mostrevisions'             => array( 'ਸਭ_ਤੋਂ_ਵੱਧ_ਰੀਵਿਜ਼ਨ' ),
	'Movepage'                  => array( 'ਸਿਰਲੇਖ_ਬਦਲੋ' ),
	'Mycontributions'           => array( 'ਮੇਰੇ_ਯੋਗਦਾਨ', 'ਮੇਰੀ_ਹਿੱਸੇਦਾਰੀ' ),
	'MyLanguage'                => array( 'ਮੇਰੀ_ਭਾਸ਼ਾ', 'ਮੇਰੀ_ਬੋਲੀ' ),
	'Mypage'                    => array( 'ਮੇਰਾ_ਸਫ਼ਾ' ),
	'Mytalk'                    => array( 'ਮੇਰੀ_ਚਰਚਾ', 'ਮੇਰੀ_ਗੱਲ-ਬਾਤ' ),
	'Myuploads'                 => array( 'ਮੇਰੇ_ਅੱਪਲੋਡ', 'ਮੇਰੀਆਂ_ਫ਼ਾਇਲਾਂ' ),
	'Newimages'                 => array( 'ਨਵੀਆਂ_ਫ਼ਾਈਲਾਂ', 'ਨਵੀਆਂ_ਤਸਵੀਰਾਂ' ),
	'Newpages'                  => array( 'ਨਵੇਂ_ਸਫ਼ੇ' ),
	'PageLanguage'              => array( 'ਸਫ਼ੇ_ਦੀ_ਭਾਸ਼ਾ' ),
	'PasswordReset'             => array( 'ਪਾਸਵਰਡ_ਰੀਸੈੱਟ' ),
	'PermanentLink'             => array( 'ਪੱਕਾ_ਲਿੰਕ', 'ਪੱਕੀ_ਕੜੀ' ),
	'Preferences'               => array( 'ਪਸੰਦਾਂ' ),
	'Prefixindex'               => array( 'ਅਗੇਤਰ_ਤਤਕਰਾ' ),
	'Protectedpages'            => array( 'ਸੁਰੱਖਿਅਤ_ਸਫ਼ੇ' ),
	'Protectedtitles'           => array( 'ਸੁਰੱਖਿਅਤ_ਸਿਰਲੇਖ' ),
	'Randompage'                => array( 'ਰਲਵਾਂ_ਸਫ਼ਾ' ),
	'RandomInCategory'          => array( 'ਰਲਵੀਂ_ਸ਼੍ਰੇਣੀ' ),
	'Randomredirect'            => array( 'ਸੁਰੱਖਿਅਤ_ਰੀਡਿਰੈਕਟ' ),
	'Recentchanges'             => array( 'ਤਾਜ਼ਾ_ਤਬਦੀਲੀਆਂ' ),
	'Recentchangeslinked'       => array( 'ਜੁੜੀਆਂ_ਹਾਲੀਆ_ਤਬਦੀਲੀਆਂ', 'ਸਬੰਧਤ_ਹਾਲੀਆ_ਤਬਦੀਲੀਆਂ' ),
	'Redirect'                  => array( 'ਰੀਡਿਰੈਕਟ' ),
	'Revisiondelete'            => array( 'ਰੀਵਿਜ਼ਨ_ਮਿਟਾਓ' ),
	'Search'                    => array( 'ਖੋਜੋ' ),
	'Shortpages'                => array( 'ਛੋਟੇ_ਸਫ਼ੇ' ),
	'Specialpages'              => array( 'ਖ਼ਾਸ_ਸਫ਼ੇ' ),
	'Statistics'                => array( 'ਅੰਕੜੇ' ),
	'Tags'                      => array( 'ਟੈਗ' ),
	'Unblock'                   => array( 'ਪਾਬੰਦੀ_ਹਟਾਓ' ),
	'Uncategorizedcategories'   => array( 'ਸ਼੍ਰੇਣੀਹੀਣ_ਸ਼੍ਰੇਣੀਆਂ' ),
	'Uncategorizedimages'       => array( 'ਸ਼੍ਰੇਣੀਹੀਣ_ਫ਼ਾਈਲਾਂ' ),
	'Uncategorizedpages'        => array( 'ਸ਼੍ਰੇਣੀਹੀਣ_ਸਫ਼ੇ' ),
	'Uncategorizedtemplates'    => array( 'ਸ਼੍ਰੇਣੀਹੀਣ_ਫਰਮੇ' ),
	'Undelete'                  => array( 'ਅਣ-ਹਟਾਓਣ' ),
	'Unlockdb'                  => array( 'ਡੈਟਾਬੇਸ_ਖੋਲ੍ਹੋ' ),
	'Unusedcategories'          => array( 'ਅਣਵਰਤੀਆਂ_ਸ਼੍ਰੇਣੀਆਂ' ),
	'Unusedimages'              => array( 'ਅਣਵਰਤੀਆਂ_ਫ਼ਾਈਲਾਂ' ),
	'Unusedtemplates'           => array( 'ਅਣਵਰਤੇ_ਫਰਮੇ' ),
	'Unwatchedpages'            => array( 'ਬੇ-ਨਿਗਰਾਨ_ਸਫ਼ੇ' ),
	'Upload'                    => array( 'ਅੱਪਲੋਡ_ਕਰੋ' ),
	'Userlogin'                 => array( 'ਵਰਤੋਂਕਾਰ_ਲਾਗਇਨ' ),
	'Userlogout'                => array( 'ਵਰਤੋਂਕਾਰ_ਲਾਗਆਊਟ' ),
	'Userrights'                => array( 'ਵਰਤੋਂਕਾਰ_ਹੱਕ', 'ਪ੍ਰਬੰਧਕ_ਬਣਾਓ', 'ਬੋਟ_ਬਣਾਓ' ),
	'Version'                   => array( 'ਰੂਪ', 'ਵਰਜਨ' ),
	'Wantedcategories'          => array( 'ਚਾਹੀਦੀਆਂ_ਸ਼੍ਰੇਣੀਆਂ' ),
	'Wantedfiles'               => array( 'ਚਾਹੀਦੀਆਂ_ਫ਼ਾਈਲਾਂ' ),
	'Wantedpages'               => array( 'ਚਾਹੀਦੇ_ਸਫ਼ੇ', 'ਟੁੱਟੇ_ਜੋੜ' ),
	'Wantedtemplates'           => array( 'ਚਾਹੀਦੇ_ਫਰਮੇ' ),
	'Watchlist'                 => array( 'ਨਿਗਰਾਨੀ-ਲਿਸਟ' ),
	'Whatlinkshere'             => array( 'ਕਿਹੜੇ_ਸਫ਼ੇ_ਇੱਥੇ_ਜੋੜਦੇ_ਹਨ' ),
	'Withoutinterwiki'          => array( 'ਬਿਨਾਂ_ਇੰਟਰਵਿਕੀਆਂ_ਵਾਲੇ' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#ਰੀਡਿਰੈਕਟ', '#REDIRECT' ),
	'url_wiki'                  => array( '0', 'ਵਿਕੀ', 'WIKI' ),
	'defaultsort_noerror'       => array( '0', 'ਗਲਤੀ_ਨਹੀਂ', 'noerror' ),
	'pagesincategory_all'       => array( '0', 'ਸਬ', 'all' ),
	'pagesincategory_pages'     => array( '0', 'ਪੰਨੇ', 'pages' ),
	'pagesincategory_subcats'   => array( '0', 'ਉਪਸ਼੍ਰੇਣੀਆਂ', 'subcats' ),
	'pagesincategory_files'     => array( '0', 'ਫ਼ਾਈਲਾਂ', 'files' ),
);

$digitTransformTable = array(
	'0' => '੦', # &#x0a66;
	'1' => '੧', # &#x0a67;
	'2' => '੨', # &#x0a68;
	'3' => '੩', # &#x0a69;
	'4' => '੪', # &#x0a6a;
	'5' => '੫', # &#x0a6b;
	'6' => '੬', # &#x0a6c;
	'7' => '੭', # &#x0a6d;
	'8' => '੮', # &#x0a6e;
	'9' => '੯', # &#x0a6f;
);
$linkTrail = '/^([ਁਂਃਅਆਇਈਉਊਏਐਓਔਕਖਗਘਙਚਛਜਝਞਟਠਡਢਣਤਥਦਧਨਪਫਬਭਮਯਰਲਲ਼ਵਸ਼ਸਹ਼ਾਿੀੁੂੇੈੋੌ੍ਖ਼ਗ਼ਜ਼ੜਫ਼ੰੱੲੳa-z]+)(.*)$/sDu';

$digitGroupingPattern = "##,##,###";


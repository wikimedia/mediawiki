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

$namespaceNames = [
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
];

$namespaceAliases = [
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
];

$specialPageAliases = [
	'Activeusers'               => [ 'ਸਰਗਰਮ_ਵਰਤੋਂਕਾਰ' ],
	'Allmessages'               => [ 'ਸਾਰੇ_ਸਨੇਹੇ' ],
	'AllMyUploads'              => [ 'ਮੇਰੇ_ਸਾਰੇ_ਅੱਪਲੋਡ' ],
	'Allpages'                  => [ 'ਸਾਰੇ_ਸਫ਼ੇ' ],
	'Ancientpages'              => [ 'ਪੁਰਾਣੇ_ਸਫ਼ੇ' ],
	'Badtitle'                  => [ 'ਖ਼ਰਾਬ_ਸਿਰਲੇਖ' ],
	'Blankpage'                 => [ 'ਖ਼ਾਲੀ_ਸਫ਼ਾ' ],
	'Block'                     => [ 'ਪਾਬੰਦੀ_ਲਾਓ', 'IP_’ਤੇ_ਪਾਬੰਦੀ_ਲਾਓ', 'ਵਰਤੋਂਕਾਰ_’ਤੇ_ਪਾਬੰਦੀ_ਲਾਓ' ],
	'Booksources'               => [ 'ਕਿਤਾਬ_ਸਰੋਤ' ],
	'BrokenRedirects'           => [ 'ਟੁੱਟੇ_ਰੀਡਿਰੈਕਟ' ],
	'Categories'                => [ 'ਸ਼੍ਰੇਣੀਆਂ', 'ਵਰਗ' ],
	'ChangeEmail'               => [ 'ਈ-ਮੇਲ_ਬਦਲੋ', 'ਈਮੇਲ_ਬਦਲੋ' ],
	'ChangePassword'            => [ 'ਪਾਸਵਰਡ_ਬਦਲੋ', 'ਪਾਸਵਰਡ_ਰੀਸੈੱਟ_ਕਰੋ' ],
	'ComparePages'              => [ 'ਸਫ਼ਿਆਂ_ਦੀ_ਤੁਲਨਾ_ਕਰੋ' ],
	'Confirmemail'              => [ 'ਈ-ਮੇਲ_ਤਸਦੀਕ_ਕਰੋ', 'ਈਮੇਲ_ਤਸਦੀਕ_ਕਰੋ' ],
	'Contributions'             => [ 'ਯੋਗਦਾਨ', 'ਹਿੱਸੇਦਾਰੀ' ],
	'CreateAccount'             => [ 'ਖਾਤਾ_ਬਣਾਓ' ],
	'Deadendpages'              => [ 'ਬੰਦ_ਸਫ਼ੇ' ],
	'DeletedContributions'      => [ 'ਮਿਟਾਏ_ਯੋਗਦਾਨ' ],
	'Diff'                      => [ 'ਫ਼ਰਕ' ],
	'DoubleRedirects'           => [ 'ਦੂਹਰੇ_ਰੀਡਿਰੈਕਟ' ],
	'EditWatchlist'             => [ 'ਨਿਗਰਾਨੀ-ਲਿਸਟ_ਸੋਧੋ', 'ਨਿਗਰਾਨੀਲਿਸਟ_ਸੋਧੋ' ],
	'Emailuser'                 => [ 'ਵਰਤੋਂਕਾਰ_ਨੂੰ_ਈ-ਮੇਲ_ਕਰੋ' ],
	'ExpandTemplates'           => [ 'ਫਰਮੇ_ਖੋਲ੍ਹੋ' ],
	'Export'                    => [ 'ਨਿਰਯਾਤ' ],
	'Fewestrevisions'           => [ 'ਸਭ_ਤੋਂ_ਘੱਟ_ਰੀਵਿਜ਼ਨਾਂ' ],
	'FileDuplicateSearch'       => [ 'ਨਕਲੀ_ਫ਼ਾਈਲ_ਖੋਜੋ', 'ਨਕਲੀ_ਫ਼ਾਇਲ_ਖੋਜੋ' ],
	'Filepath'                  => [ 'ਫ਼ਾਈਲ_ਪਥ', 'ਫ਼ਾਇਲ_ਪਥ' ],
	'Import'                    => [ 'ਆਯਾਤ' ],
	'Invalidateemail'           => [ 'ਗਲਤ_ਈ-ਮੇਲ_ਪਤਾ' ],
	'JavaScriptTest'            => [ 'ਜਾਵਾਸਕ੍ਰਿਪਟ_ਪਰਖ' ],
	'BlockList'                 => [ 'ਪਾਬੰਦੀਆਂ_ਦੀ_ਲਿਸਟ' ],
	'LinkSearch'                => [ 'ਲਿੰਕ_ਖੋਜੋ', 'ਕੜੀ_ਖੋਜੋ' ],
	'Listadmins'                => [ 'ਪ੍ਰਬੰਧਕਾਂ_ਦੀ_ਲਿਸਟ' ],
	'Listbots'                  => [ 'ਬੋਟ_ਲਿਸਟ' ],
	'Listfiles'                 => [ 'ਫ਼ਾਈਲ_ਲਿਸਟ', 'ਫ਼ਾਇਲ_ਲਿਸਟ', 'ਤਸਵੀਰ_ਲਿਸਟ' ],
	'Listgrouprights'           => [ 'ਵਰਤੋਂਕਾਰ_ਹੱਕਾਂ_ਦੀ_ਲਿਸਟ' ],
	'Listredirects'             => [ 'ਰੀਡਿਰੈਕਟਾਂ_ਦੀ_ਲਿਸਟ' ],
	'ListDuplicatedFiles'       => [ 'ਨਕਲੀ_ਫ਼ਾਇਲ_ਲਿਸਟ' ],
	'Listusers'                 => [ 'ਵਰਤੋਂਕਾਰਾਂ_ਦੀ_ਲਿਸਟ' ],
	'Lockdb'                    => [ 'ਡੈਟਾਬੇਸ_’ਤੇ_ਤਾਲਾ_ਲਗਾਓ' ],
	'Log'                       => [ 'ਚਿੱਠਾ', 'ਚਿੱਠੇ' ],
	'Lonelypages'               => [ 'ਇਕੱਲੇ_ਸਫ਼ੇ' ],
	'Longpages'                 => [ 'ਲੰਬੇ_ਸਫ਼ੇ' ],
	'MergeHistory'              => [ 'ਰਲਾਉਣ_ਦਾ_ਅਤੀਤ', 'ਰਲ਼ਾਉਣ_ਦਾ_ਅਤੀਤ' ],
	'MIMEsearch'                => [ 'MIME_ਖੋਜੋ' ],
	'Mostcategories'            => [ 'ਸਭ_ਤੋਂ_ਵੱਧ_ਸ਼੍ਰੇਣੀਆਂ' ],
	'Mostimages'                => [ 'ਸਭ_ਤੋਂ_ਵੱਧ_ਜੁੜੀਆਂ_ਫ਼ਾਈਲਾਂ' ],
	'Mostinterwikis'            => [ 'ਸਭ_ਤੋਂ_ਵੱਧ_ਇੰਟਰਵਿਕੀ' ],
	'Mostlinked'                => [ 'ਸਭ_ਤੋਂ_ਵੱਧ_ਜੁੜੇ_ਸਫ਼ੇ' ],
	'Mostlinkedcategories'      => [ 'ਸਭ_ਤੋਂ_ਵੱਧ_ਜੁੜੀਆਂ_ਸ਼੍ਰੇਣੀਆਂ' ],
	'Mostlinkedtemplates'       => [ 'ਸਭ_ਤੋਂ_ਵੱਧ_ਜੁੜੇ_ਫਰਮੇ' ],
	'Mostrevisions'             => [ 'ਸਭ_ਤੋਂ_ਵੱਧ_ਰੀਵਿਜ਼ਨ' ],
	'Movepage'                  => [ 'ਸਿਰਲੇਖ_ਬਦਲੋ' ],
	'Mycontributions'           => [ 'ਮੇਰੇ_ਯੋਗਦਾਨ', 'ਮੇਰੀ_ਹਿੱਸੇਦਾਰੀ' ],
	'MyLanguage'                => [ 'ਮੇਰੀ_ਭਾਸ਼ਾ', 'ਮੇਰੀ_ਬੋਲੀ' ],
	'Mypage'                    => [ 'ਮੇਰਾ_ਸਫ਼ਾ' ],
	'Mytalk'                    => [ 'ਮੇਰੀ_ਚਰਚਾ', 'ਮੇਰੀ_ਗੱਲ-ਬਾਤ' ],
	'Myuploads'                 => [ 'ਮੇਰੇ_ਅੱਪਲੋਡ', 'ਮੇਰੀਆਂ_ਫ਼ਾਇਲਾਂ' ],
	'Newimages'                 => [ 'ਨਵੀਆਂ_ਫ਼ਾਈਲਾਂ', 'ਨਵੀਆਂ_ਤਸਵੀਰਾਂ' ],
	'Newpages'                  => [ 'ਨਵੇਂ_ਸਫ਼ੇ' ],
	'PageLanguage'              => [ 'ਸਫ਼ੇ_ਦੀ_ਭਾਸ਼ਾ' ],
	'PasswordReset'             => [ 'ਪਾਸਵਰਡ_ਰੀਸੈੱਟ' ],
	'PermanentLink'             => [ 'ਪੱਕਾ_ਲਿੰਕ', 'ਪੱਕੀ_ਕੜੀ' ],
	'Preferences'               => [ 'ਪਸੰਦਾਂ' ],
	'Prefixindex'               => [ 'ਅਗੇਤਰ_ਤਤਕਰਾ' ],
	'Protectedpages'            => [ 'ਸੁਰੱਖਿਅਤ_ਸਫ਼ੇ' ],
	'Protectedtitles'           => [ 'ਸੁਰੱਖਿਅਤ_ਸਿਰਲੇਖ' ],
	'Randompage'                => [ 'ਰਲਵਾਂ_ਸਫ਼ਾ' ],
	'RandomInCategory'          => [ 'ਰਲਵੀਂ_ਸ਼੍ਰੇਣੀ' ],
	'Randomredirect'            => [ 'ਸੁਰੱਖਿਅਤ_ਰੀਡਿਰੈਕਟ' ],
	'Recentchanges'             => [ 'ਤਾਜ਼ਾ_ਤਬਦੀਲੀਆਂ' ],
	'Recentchangeslinked'       => [ 'ਜੁੜੀਆਂ_ਹਾਲੀਆ_ਤਬਦੀਲੀਆਂ', 'ਸਬੰਧਤ_ਹਾਲੀਆ_ਤਬਦੀਲੀਆਂ' ],
	'Redirect'                  => [ 'ਰੀਡਿਰੈਕਟ' ],
	'Revisiondelete'            => [ 'ਰੀਵਿਜ਼ਨ_ਮਿਟਾਓ' ],
	'Search'                    => [ 'ਖੋਜੋ' ],
	'Shortpages'                => [ 'ਛੋਟੇ_ਸਫ਼ੇ' ],
	'Specialpages'              => [ 'ਖ਼ਾਸ_ਸਫ਼ੇ' ],
	'Statistics'                => [ 'ਅੰਕੜੇ' ],
	'Tags'                      => [ 'ਟੈਗ' ],
	'Unblock'                   => [ 'ਪਾਬੰਦੀ_ਹਟਾਓ' ],
	'Uncategorizedcategories'   => [ 'ਸ਼੍ਰੇਣੀਹੀਣ_ਸ਼੍ਰੇਣੀਆਂ' ],
	'Uncategorizedimages'       => [ 'ਸ਼੍ਰੇਣੀਹੀਣ_ਫ਼ਾਈਲਾਂ' ],
	'Uncategorizedpages'        => [ 'ਸ਼੍ਰੇਣੀਹੀਣ_ਸਫ਼ੇ' ],
	'Uncategorizedtemplates'    => [ 'ਸ਼੍ਰੇਣੀਹੀਣ_ਫਰਮੇ' ],
	'Undelete'                  => [ 'ਅਣ-ਹਟਾਓਣ' ],
	'Unlockdb'                  => [ 'ਡੈਟਾਬੇਸ_ਖੋਲ੍ਹੋ' ],
	'Unusedcategories'          => [ 'ਅਣਵਰਤੀਆਂ_ਸ਼੍ਰੇਣੀਆਂ' ],
	'Unusedimages'              => [ 'ਅਣਵਰਤੀਆਂ_ਫ਼ਾਈਲਾਂ' ],
	'Unusedtemplates'           => [ 'ਅਣਵਰਤੇ_ਫਰਮੇ' ],
	'Unwatchedpages'            => [ 'ਬੇ-ਨਿਗਰਾਨ_ਸਫ਼ੇ' ],
	'Upload'                    => [ 'ਅੱਪਲੋਡ_ਕਰੋ' ],
	'Userlogin'                 => [ 'ਵਰਤੋਂਕਾਰ_ਲਾਗਇਨ' ],
	'Userlogout'                => [ 'ਵਰਤੋਂਕਾਰ_ਲਾਗਆਊਟ' ],
	'Userrights'                => [ 'ਵਰਤੋਂਕਾਰ_ਹੱਕ', 'ਪ੍ਰਬੰਧਕ_ਬਣਾਓ', 'ਬੋਟ_ਬਣਾਓ' ],
	'Version'                   => [ 'ਰੂਪ', 'ਵਰਜਨ' ],
	'Wantedcategories'          => [ 'ਚਾਹੀਦੀਆਂ_ਸ਼੍ਰੇਣੀਆਂ' ],
	'Wantedfiles'               => [ 'ਚਾਹੀਦੀਆਂ_ਫ਼ਾਈਲਾਂ' ],
	'Wantedpages'               => [ 'ਚਾਹੀਦੇ_ਸਫ਼ੇ', 'ਟੁੱਟੇ_ਜੋੜ' ],
	'Wantedtemplates'           => [ 'ਚਾਹੀਦੇ_ਫਰਮੇ' ],
	'Watchlist'                 => [ 'ਨਿਗਰਾਨੀ-ਲਿਸਟ' ],
	'Whatlinkshere'             => [ 'ਕਿਹੜੇ_ਸਫ਼ੇ_ਇੱਥੇ_ਜੋੜਦੇ_ਹਨ' ],
	'Withoutinterwiki'          => [ 'ਬਿਨਾਂ_ਇੰਟਰਵਿਕੀਆਂ_ਵਾਲੇ' ],
];

$magicWords = [
	'redirect'                  => [ '0', '#ਰੀਡਿਰੈਕਟ', '#REDIRECT' ],
	'url_wiki'                  => [ '0', 'ਵਿਕੀ', 'WIKI' ],
	'defaultsort_noerror'       => [ '0', 'ਗਲਤੀ_ਨਹੀਂ', 'noerror' ],
	'pagesincategory_all'       => [ '0', 'ਸਬ', 'all' ],
	'pagesincategory_pages'     => [ '0', 'ਪੰਨੇ', 'pages' ],
	'pagesincategory_subcats'   => [ '0', 'ਉਪਸ਼੍ਰੇਣੀਆਂ', 'subcats' ],
	'pagesincategory_files'     => [ '0', 'ਫ਼ਾਈਲਾਂ', 'files' ],
];

$linkTrail = '/^([ਁਂਃਅਆਇਈਉਊਏਐਓਔਕਖਗਘਙਚਛਜਝਞਟਠਡਢਣਤਥਦਧਨਪਫਬਭਮਯਰਲਲ਼ਵਸ਼ਸਹ਼ਾਿੀੁੂੇੈੋੌ੍ਖ਼ਗ਼ਜ਼ੜਫ਼ੰੱੲੳa-z]+)(.*)$/sDu';

$digitGroupingPattern = "##,##,###";


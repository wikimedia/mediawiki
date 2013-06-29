<?php
/** Punjabi (ਪੰਜਾਬੀ)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
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
 * @author Kaganer
 * @author Raj Singh
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
	NS_USER             => 'ਵਰਤੌਂਕਾਰ',
	NS_USER_TALK        => 'ਵਰਤੌਂਕਾਰ_ਗੱਲ-ਬਾਤ',
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
	'Activeusers'               => array( 'ਸਰਗਰਮ_ਮੈਂਬਰ' ),
	'Allmessages'               => array( 'ਸਾਰੇ_ਸਨੇਹੇ' ),
	'Allpages'                  => array( 'ਸਾਰੇ_ਪੰਨੇ' ),
	'Ancientpages'              => array( 'ਪੁਰਾਣੇ_ਪੰਨੇ' ),
	'Badtitle'                  => array( 'ਖਰਾਬ_ਸਿਰਲੇਖ' ),
	'Blankpage'                 => array( 'ਖਾਲੀ_ਪੰਨਾ' ),
	'Block'                     => array( 'ਪਾਬੰਦੀ_ਲਾਓ', 'IP_’ਤੇ_ਪਾਬੰਦੀ_ਲਾਓ', 'ਮੈਂਬਰ_’ਤੇ_ਪਾਬੰਦੀ_ਲਾਓ' ),
	'Blockme'                   => array( 'ਮੇਰੇ_’ਤੇ_ਪਾਬੰਦੀ_ਲਾਓ' ),
	'Booksources'               => array( 'ਕਿਤਾਬ_ਸਰੋਤ' ),
	'BrokenRedirects'           => array( 'ਟੁੱਟੇ_ਰੀਡਿਰੈਕਟ' ),
	'Categories'                => array( 'ਸ਼੍ਰੇਣੀਆਂ' ),
	'ChangeEmail'               => array( 'ਈ-ਮੇਲ_ਬਦਲੋ' ),
	'ChangePassword'            => array( 'ਪਾਸਵਰਡ_ਬਦਲੋ', 'ਪਾਸਵਰਡ_ਰੀਸੈੱਟ_ਕਰੋ' ),
	'ComparePages'              => array( 'ਪੰਨਿਆਂ_ਦੀ_ਤੁਲਨਾ_ਕਰੋ' ),
	'Confirmemail'              => array( 'ਈ-ਮੇਲ_ਤਸਦੀਕ_ਕਰੋ' ),
	'Contributions'             => array( 'ਯੋਗਦਾਨ' ),
	'CreateAccount'             => array( 'ਖਾਤਾ_ਬਣਾਓ' ),
	'Deadendpages'              => array( 'ਬੰਦ_ਪੰਨੇ' ),
	'DeletedContributions'      => array( 'ਮਿਟਾਏ_ਯੋਗਦਾਨ' ),
	'Disambiguations'           => array( 'ਗੁੰਝਲਖੋਲ੍ਹ' ),
	'DoubleRedirects'           => array( 'ਦੂਹਰੇ_ਰੀਡਿਰੈਕਟ' ),
	'EditWatchlist'             => array( 'ਨਿਗਰਾਨੀ-ਲਿਸਟ_ਸੋਧੋ' ),
	'Emailuser'                 => array( 'ਮੈਂਬਰ_ਨੂੰ_ਈ-ਮੇਲ_ਕਰੋ' ),
	'Export'                    => array( 'ਨਿਰਯਾਤ' ),
	'Fewestrevisions'           => array( 'ਸਭ_ਤੋਂ_ਘੱਟ_ਰੀਵਿਜ਼ਨਾਂ' ),
	'FileDuplicateSearch'       => array( 'ਨਕਲੀ_ਫ਼ਾਈਲ_ਖੋਜੋ' ),
	'Filepath'                  => array( 'ਫ਼ਾਈਲ_ਪਥ' ),
	'Import'                    => array( 'ਆਯਾਤ' ),
	'Invalidateemail'           => array( 'ਗਲਤ_ਈ-ਮੇਲ_ਪਤਾ' ),
	'JavaScriptTest'            => array( 'ਜਾਵਾਸਕ੍ਰਿਪਟ_ਪਰਖ' ),
	'BlockList'                 => array( 'ਪਾਬੰਦੀਆਂ_ਦੀ_ਸੂਚੀ' ),
	'LinkSearch'                => array( 'ਲਿੰਕ_ਖੋਜੋ' ),
	'Listadmins'                => array( 'ਪ੍ਰਬੰਧਕਾਂ_ਦੀ_ਸੂਚੀ' ),
	'Listbots'                  => array( 'ਬੋਟਾਂ_ਦੀ_ਸੂਚੀ' ),
	'Listfiles'                 => array( 'ਫ਼ਾਈਲਾਂ_ਦੀ_ਸੂਚੀ' ),
	'Listgrouprights'           => array( 'ਵਰਤੋਂਕਾਰ_ਹੱਕ_ਸੂਚੀ' ),
	'Listredirects'             => array( 'ਰੀਡਿਰੈਕਟਾਂ_ਦੀ_ਸੂਚੀ' ),
	'Listusers'                 => array( 'ਵਰਤੋਂਕਾਰਾਂ_ਦੀ_ਸੂਚੀ' ),
	'Lockdb'                    => array( 'ਡੈਟਾਬੇਸ_’ਤੇ_ਤਾਲਾ_ਲਗਾਓ' ),
	'Log'                       => array( 'ਚਿੱਠਾ', 'ਚਿੱਠੇ' ),
	'Lonelypages'               => array( 'ਇਕੱਲੇ_ਪੰਨੇ' ),
	'Longpages'                 => array( 'ਲੰਬੇ_ਪੰਨੇ' ),
	'MergeHistory'              => array( 'ਰਲਾਉਣ_ਦਾ_ਅਤੀਤ' ),
	'MIMEsearch'                => array( 'MIME_ਖੋਜੋ' ),
	'Mostcategories'            => array( 'ਸਭ_ਤੋਂ_ਵੱਧ_ਸ਼੍ਰੇਣੀਆਂ' ),
	'Mostimages'                => array( 'ਸਭ_ਤੋਂ_ਵੱਧ_ਜੁੜੀਆਂ_ਫ਼ਾਈਲਾਂ' ),
	'Mostinterwikis'            => array( 'ਸਭ_ਤੋਂ_ਵੱਧ_ਇੰਟਰਵਿਕੀ' ),
	'Mostlinked'                => array( 'ਸਭ_ਤੋਂ_ਵੱਧ_ਜੁੜੇ_ਪੰਨੇ' ),
	'Mostlinkedcategories'      => array( 'ਸਭ_ਤੋਂ_ਵੱਧ_ਜੁੜੀਆਂ_ਸ਼੍ਰੇਣੀਆਂ' ),
	'Mostlinkedtemplates'       => array( 'ਸਭ_ਤੋਂ_ਵੱਧ_ਜੁੜੇ_ਫਰਮੇ' ),
	'Mostrevisions'             => array( 'ਸਭ_ਤੋਂ_ਵੱਧ_ਰੀਵਿਜ਼ਨ' ),
	'Movepage'                  => array( 'ਸਿਰਲੇਖ_ਬਦਲੋ' ),
	'Mycontributions'           => array( 'ਮੇਰੇ_ਯੋਗਦਾਨ' ),
	'Mypage'                    => array( 'ਮੇਰਾ_ਪੰਨਾ' ),
	'Mytalk'                    => array( 'ਮੇਰੀ_ਚਰਚਾ' ),
	'Myuploads'                 => array( 'ਮੇਰੇ_ਅੱਪਲੋਡ' ),
	'Newimages'                 => array( 'ਨਵੀਆਂ_ਫ਼ਾਈਲਾਂ' ),
	'Newpages'                  => array( 'ਨਵੇਂ_ਪੰਨੇ' ),
	'PasswordReset'             => array( 'ਪਾਸਵਰਡ_ਰੀਸੈੱਟ' ),
	'PermanentLink'             => array( 'ਪੱਕਾ_ਲਿੰਕ' ),
	'Popularpages'              => array( 'ਮਸ਼ਹੂਰ_ਪੰਨੇ' ),
	'Preferences'               => array( 'ਪਸੰਦਾਂ' ),
	'Prefixindex'               => array( 'ਅਗੇਤਰ_ਤਤਕਰਾ' ),
	'Protectedpages'            => array( 'ਸੁਰੱਖਿਅਤ_ਪੰਨੇ' ),
	'Protectedtitles'           => array( 'ਸੁਰੱਖਿਅਤ_ਸਿਰਲੇਖ' ),
	'Randompage'                => array( 'ਰਲਵਾਂ_ਪੰਨਾ' ),
	'Randomredirect'            => array( 'ਸੁਰੱਖਿਅਤ_ਰੀਡਿਰੈਕਟ' ),
	'Recentchanges'             => array( 'ਹਾਲ_\'ਚ_ਹੋਈਆਂ_ਤਬਦੀਲੀਆਂ' ),
	'Recentchangeslinked'       => array( 'ਜੁੜੀਆਂ_ਤਾਜ਼ਾ_ਤਬਦੀਲੀਆਂ' ),
	'Revisiondelete'            => array( 'ਰੀਵਿਜਨ_ਮਿਟਾਓ' ),
	'Search'                    => array( 'ਖੋਜੋ' ),
	'Shortpages'                => array( 'ਛੋਟੇ_ਪੰਨੇ' ),
	'Specialpages'              => array( 'ਖਾਸ_ਪੰਨੇ' ),
	'Statistics'                => array( 'ਅੰਕੜੇ' ),
	'Tags'                      => array( 'ਟੈਗ' ),
	'Unblock'                   => array( 'ਪਾਬੰਦੀ_ਹਟਾਓ' ),
	'Uncategorizedcategories'   => array( 'ਸ਼੍ਰੇਣੀਹੀਣ_ਸ਼੍ਰੇਣੀਆਂ' ),
	'Uncategorizedimages'       => array( 'ਸ਼੍ਰੇਣੀਹੀਣ_ਫ਼ਾਈਲਾਂ' ),
	'Uncategorizedpages'        => array( 'ਸ਼੍ਰੇਣੀਹੀਣ_ਪੰਨੇ' ),
	'Uncategorizedtemplates'    => array( 'ਸ਼੍ਰੇਣੀਹੀਣ_ਸਾਂਚੇ' ),
	'Undelete'                  => array( 'ਅਣ-ਹਟਾਓਣ' ),
	'Unlockdb'                  => array( 'ਡੈਟਾਬੇਸ_ਖੋਲ੍ਹੋ' ),
	'Unusedcategories'          => array( 'ਅਣਵਰਤੀਆਂ_ਸ਼੍ਰੇਣੀਆਂ' ),
	'Unusedimages'              => array( 'ਅਣਵਰਤੀਆਂ_ਫ਼ਾਈਲਾਂ' ),
	'Unusedtemplates'           => array( 'ਅਣਵਰਤੇ_ਫਰਮੇ' ),
	'Unwatchedpages'            => array( 'ਬੇ-ਨਿਗਰਾਨ_ਪੰਨੇ' ),
	'Upload'                    => array( 'ਅੱਪਲੋਡ' ),
	'Userlogin'                 => array( 'ਮੈਂਬਰ_ਲਾਗਇਨ' ),
	'Userlogout'                => array( 'ਮੈਂਬਰ_ਲਾਗਆਊਟ' ),
	'Userrights'                => array( 'ਮੈਂਬਰ_ਹੱਕ', 'ਪ੍ਰਬੰਧਕ_ਬਣਾਓ', 'ਬੋਟ_ਬਣਾਓ' ),
	'Version'                   => array( 'ਰੂਪ' ),
	'Wantedcategories'          => array( 'ਚਾਹੀਦੀਆਂ_ਸ਼੍ਰੇਣੀਆਂ' ),
	'Wantedfiles'               => array( 'ਚਾਹੀਦੀਆਂ_ਫ਼ਾਈਲਾਂ' ),
	'Wantedpages'               => array( 'ਚਾਹੀਦੇ_ਪੰਨੇ', 'ਟੁੱਟੇ_ਜੋੜ' ),
	'Wantedtemplates'           => array( 'ਚਾਹੀਦੇ_ਫਰਮੇ' ),
	'Watchlist'                 => array( 'ਨਿਗਰਾਨੀ-ਲਿਸਟ' ),
	'Whatlinkshere'             => array( 'ਕਿਹੜੇ_ਪੰਨੇ_ਇੱਥੇ_ਜੋੜਦੇ_ਹਨ' ),
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

$messages = array(
# User preference toggles
'tog-underline' => 'ਲਿੰਕ ਹੇਠ-ਲਾਈਨ:',
'tog-justify' => 'ਪੈਰਾਗਰਾਫ਼ ਇਕਸਾਰ',
'tog-hideminor' => 'ਤਾਜ਼ਾ ਤਬਦੀਲੀਆਂ ਵਿੱਚੋਂ ਛੋਟੀਆਂ ਸੋਧਾਂ ਲੁਕਾਓ',
'tog-hidepatrolled' => 'ਤਾਜ਼ਾ ਤਬਦੀਲੀਆਂ ਵਿੱਚੋਂ ਜਾਂਚੀਆਂ ਸੋਧਾਂ ਲੁਕਾਓ',
'tog-newpageshidepatrolled' => 'ਨਵੀਂ ਸਫ਼ਾ ਸੂਚੀ ਵਿੱਚੋਂ ਨਿਗਰਾਨੀ ਸਫ਼ੇ ਓਹਲੇ ਕਰੋ',
'tog-extendwatchlist' => 'ਸਿਰਫ਼ ਤਾਜ਼ਾ ਹੀ ਨਹੀਂ, ਸਗੋਂ ਸਾਰੀਆਂ ਤਬਦੀਲੀਆਂ ਨੂੰ ਵਖਾਉਣ ਲਈ ਨਿਗਰਾਨੀ-ਲਿਸਟ ਨੂੰ ਵਧਾਓ',
'tog-usenewrc' => 'ਹਾਲ ’ਚ ਹੋਈਆਂ ਤਬਦੀਲੀਆਂ ਅਤੇ ਨਿਗਰਾਨੀ-ਲਿਸਟ ਵਿੱਚ ਪੰਨੇ ਮੁਤਾਬਕ ਤਬਦੀਲੀਆਂ ਦੇ ਗਰੁੱਪ ਬਣਾਓ (ਜਾਵਾਸਕ੍ਰਿਪਟ ਲੋੜੀਂਦੀ ਹੈ)',
'tog-numberheadings' => 'ਆਟੋ-ਨੰਬਰ ਹੈਡਿੰਗ',
'tog-showtoolbar' => 'ਸੋਧ ਟੂਲਬਾਰ ਵੇਖੋ (JavaScript ਚਾਹੀਦੀ ਹੈ)',
'tog-editondblclick' => 'ਦੋ ਵਾਰ ਕਲਿੱਕ ਕਰਨ ਨਾਲ ਸਫ਼ੇ ਸੋਧੋ (ਜਾਵਾਸਕ੍ਰਿਪਟ ਚਾਹੀਦੀ ਹੈ)',
'tog-editsection' => '[ਸੋਧੋ] ਲਿੰਕਾਂ ਦੁਆਰਾ ਸੈਕਸ਼ਨ ਸੋਧਣਾ ਚਾਲੂ ਕਰੋ',
'tog-editsectiononrightclick' => 'ਭਾਗ ਸਿਰਲੇਖਾਂ ਤੇ ਸੱਜੀ ਕਲਿੱਕ ਦੁਆਰਾ ਸੋਧ ਯੋਗ ਕਰੋ (ਜਾਵਾ ਸਕ੍ਰਿਪਟ ਲੋੜੀਂਦੀ ਹੈ)',
'tog-showtoc' => 'ਤਤਕਰਾ ਵਖਾਓ (3 ਤੋਂ ਵੱਧ ਸਿਰਲੇਖਾਂ ਵਾਲੇ ਪੰਨਿਆਂ ਲਈ)',
'tog-rememberpassword' => 'ਇਸ ਬਰਾਊਜ਼ਰ ਉੱਤੇ ਮੇਰਾ ਲਾਗਇਨ ਯਾਦ ਰੱਖੋ ($1 {{PLURAL:$1|ਦਿਨ|ਦਿਨਾਂ}} ਲਈ ਵੱਧ ਤੋਂ ਵੱਧ)',
'tog-watchcreations' => 'ਮੇਰੇ ਵਲੋਂ ਬਣਾਏ ਗਏ ਪੰਨੇ ਅਤੇ ਅੱਪਲੋਡ ਕੀਤੀਆਂ ਫਾਈਲਾਂ ਮੇਰੀ ਨਿਗਰਾਨੀ-ਲਿਸਟ ਵਿੱਚ ਪਾਓ',
'tog-watchdefault' => 'ਮੇਰੇ ਵੱਲੋਂ ਸੋਧੇ ਗਏ ਪੰਨੇ ਅਤੇ ਫ਼ਾਈਲਾਂ ਮੇਰੀ ਨਿਗਰਾਨੀ-ਲਿਸਟ ਵਿੱਚ ਪਾਓ',
'tog-watchmoves' => 'ਮੇਰੇ ਵੱਲੋਂ ਬਦਲੇ ਸਿਰਲੇਖਾਂ ਵਾਲ਼ੇ ਪੰਨੇ ਅਤੇ ਫ਼ਾਈਲਾਂ ਮੇਰੀ ਨਿਗਰਾਨੀ-ਲਿਸਟ ਵਿੱਚ ਪਾਓ',
'tog-watchdeletion' => 'ਮੇਰੇ ਵਲੋਂ ਮਿਟਾਏ ਗਏ ਪੰਨੇ ਅਤੇ ਫ਼ਾਈਲਾਂ ਮੇਰੀ ਨਿਗਰਾਨੀ-ਲਿਸਟ ਵਿੱਚ ਪਾਓ',
'tog-minordefault' => 'ਸਾਰੇ ਫੇਰ-ਬਦਲਾਂ ’ਤੇ ਮੂਲ ਰੂਪ ਵਿਚ ਛੋਟੇ ਹੋਣ ਦਾ ਨਿਸ਼ਾਨ ਲਾਓ',
'tog-previewontop' => 'ਸੋਧ ਬਾਕਸ ਤੋਂ ਪਹਿਲਾਂ ਝਲਕ ਵੇਖਾਓ',
'tog-previewonfirst' => 'ਪਹਿਲੀ ਸੋਧ ਉੱਤੇ ਝਲਕ ਵੇਖਾਓ',
'tog-nocache' => 'ਬਰਾਊਜ਼ਰ ਸਫ਼ਾ ਕੈਸ਼ ਕਰਨਾ ਬੰਦ ਕਰੋ',
'tog-enotifwatchlistpages' => 'ਜਦੋਂ ਮੇਰੀ ਨਿਗਰਾਨ-ਸੂਚੀ ਵਿਚ ਦਰਜ ਕੋਈ ਸਫ਼ਾ ਬਦਲਿਆ ਜਾਵੇ ਜਾਂ ਫਾਇਲ ਬਦਲੀ ਜਾਵੇ ਤਾਂ ਮੈਨੂੰ ਈਮੇਲ ਭੇਜੋ',
'tog-enotifusertalkpages' => 'ਜਦੋਂ ਮੇਰਾ ਗੱਲ-ਬਾਤ ਸਫ਼ਾ ਬਦਲਿਆ ਜਾਵੇ ਤਾਂ ਮੈਨੂੰ ਈ-ਮੇਲ ਭੇਜੋ',
'tog-enotifminoredits' => 'ਸਫ਼ਿਆਂ ਅਤੇ ਫਾਇਲਾਂ ਦੀਆਂ ਛੋਟੀਆਂ ਤਬਦੀਲੀਆਂ ਲਈ ਵੀ ਮੈਨੂੰ ਈ-ਮੇਲ ਭੇਜੋ',
'tog-enotifrevealaddr' => 'ਇਤਲਾਹ ਦੇਣ ਵਾਲੀਆਂ ਈ-ਮੇਲਾਂ ਵਿਚ ਮੇਰਾ ਈ-ਮੇਲ ਪਤਾ ਜ਼ਾਹਰ ਕਰੋ',
'tog-shownumberswatching' => 'ਨਜ਼ਰ ਰੱਖ ਰਹੇ ਮੈਂਬਰਾਂ ਦੀ ਗਿਣਤੀ ਵੇਖਾਓ',
'tog-oldsig' => 'ਮੌਜੂਦਾ ਦਸਤਖਤ:',
'tog-fancysig' => 'ਦਸਤਖ਼ਤ ਨੂੰ ਬਤੌਰ ਵਿਕਿਲਿਖਤ ਵਰਤੋਂ (ਬਿਨਾਂ ਆਟੋਮੈਟਿਕ ਲਿੰਕ)',
'tog-showjumplinks' => '"ਇਸ ਤੇ ਜਾਓ" ਅਸੈਸਬਿਲਟੀ ਲਿੰਕ ਚਾਲੂ ਕਰੋ',
'tog-uselivepreview' => 'ਸਿੱਧੀ ਝਲਕ ਵਰਤੋਂ (ਜਾਵਾਸਕ੍ਰਿਪਟ ਲੋੜੀਂਦੀ ਹੈ) (ਤਜਰਬੇ-ਅਧੀਨ)',
'tog-forceeditsummary' => 'ਜਦੋਂ ਮੈਂ ਖ਼ਾਲੀ ਸੋਧ ਸਾਰ ਦੇਵਾਂ ਤਾਂ ਮੈਨੂੰ ਪੁੱਛੋ',
'tog-watchlisthideown' => 'ਨਿਗਰਾਨ-ਸੂਚੀ ਵਿੱਚੋਂ ਮੇਰੇ ਸੋਧ ਓਹਲੇ ਕਰੋ',
'tog-watchlisthidebots' => 'ਨਿਗਰਾਨ-ਸੂਚੀ ਵਿੱਚੋਂ ਬੋਟ ਸੋਧਾਂ ਓਹਲੇ ਕਰੋ',
'tog-watchlisthideminor' => 'ਨਿਗਰਾਨ-ਸੂਚੀ ਵਿੱਚੋਂ ਛੋਟੀਆਂ ਸੋਧਾਂ ਓਹਲੇ ਕਰੋ',
'tog-watchlisthideliu' => 'ਨਿਗਰਾਨੀ-ਸੂਚੀ ਵਿਚੋਂ ਲਾਗ ਇਨ ਮੈਂਬਰਾਂ ਦੀਆਂ ਸੋਧਾਂ ਓਹਲੇ ਕਰੋ',
'tog-watchlisthideanons' => 'ਨਿਗਰਾਨੀ-ਲਿਸਟ ਵਿਚੋਂ ਗੁਮਨਾਮ ਮੈਂਬਰਾਂ ਦੇ ਕੀਤੇ ਫੇਰ-ਬਦਲ ਲੁਕਾਓ',
'tog-watchlisthidepatrolled' => 'ਵੇਖੀਆਂ ਜਾ ਚੁੱਕੀਆਂ ਸੋਧਾਂ ਨਿਗਰਾਨੀ-ਲਿਸਟ ਵਿਚੋਂ ਲੁਕਾਓ',
'tog-ccmeonemails' => 'ਜੋ ਈ-ਮੇਲਾਂ ਮੈਂ ਦੂਜੇ ਮੈਂਬਰਾਂ ਨੂੰ ਭੇਜਦਾ ਹਾਂ ਓਹਨਾਂ ਦੀਆਂ ਨਕਲਾਂ ਮੈਨੂੰ ਭੇਜੋ',
'tog-diffonly' => 'ਫ਼ਰਕ ਤੋਂ ਹੇਠ ਸਫ਼ੇ ਦੀ ਸਮੱਗਰੀ ਨਾ ਵੇਖਾਓ',
'tog-showhiddencats' => 'ਲੁਕਵੀਆਂ ਸ਼੍ਰੇਣੀਆਂ ਵੇਖਾਓ',
'tog-norollbackdiff' => '"ਵਾਪਸ ਮੋੜੌ"ਅਮਲ ਵਿਚ ਲਿਆਣ ਬਾਦ ਫ਼ਰਕ ਨਾ ਦਿਖਾਓ',

'underline-always' => 'ਹਮੇਸ਼ਾਂ',
'underline-never' => 'ਕਦੇ ਨਹੀਂ',
'underline-default' => 'ਸਕਿਨ ਜਾਂ ਬਰਾਊਜ਼ਰ ਮੂਲ',

# Font style option in Special:Preferences
'editfont-style' => 'ਸੋਧ ਖੇਤਰ ਫੋਂਟ ਸਟਾਇਲ:',
'editfont-default' => 'ਬਰਾਊਜ਼ਰ ਮੂਲ',
'editfont-monospace' => 'ਮੋਨੋਸਪੇਸ ਫੋਂਟ',
'editfont-sansserif' => 'Sans-serif ਫੋਂਟ',
'editfont-serif' => 'ਨੋਕਦਾਰ ਫੋਂਟ',

# Dates
'sunday' => 'ਐਤਵਾਰ',
'monday' => 'ਸੋਮਵਾਰ',
'tuesday' => 'ਮੰਗਲਵਾਰ',
'wednesday' => 'ਬੁੱਧਵਾਰ',
'thursday' => 'ਵੀਰਵਾਰ',
'friday' => 'ਸ਼ੁੱਕਰਵਾਰ',
'saturday' => 'ਸ਼ਨਿੱਚਰਵਾਰ',
'sun' => 'ਐਤ',
'mon' => 'ਸੋਮ',
'tue' => 'ਮੰਗਲ',
'wed' => 'ਬੁੱਧ',
'thu' => 'ਵੀਰ',
'fri' => 'ਸ਼ੁੱਕਰ',
'sat' => 'ਸ਼ਨਿੱਚਰ',
'january' => 'ਜਨਵਰੀ',
'february' => 'ਫਰਵਰੀ',
'march' => 'ਮਾਰਚ',
'april' => 'ਅਪ੍ਰੈਲ',
'may_long' => 'ਮਈ',
'june' => 'ਜੂਨ',
'july' => 'ਜੁਲਾਈ',
'august' => 'ਅਗਸਤ',
'september' => 'ਸਤੰਬਰ',
'october' => 'ਅਕਤੂਬਰ',
'november' => 'ਨਵੰਬਰ',
'december' => 'ਦਸੰਬਰ',
'january-gen' => 'ਜਨਵਰੀ',
'february-gen' => 'ਫ਼ਰਵਰੀ',
'march-gen' => 'ਮਾਰਚ',
'april-gen' => 'ਅਪ੍ਰੈਲ',
'may-gen' => 'ਮਈ',
'june-gen' => 'ਜੂਨ',
'july-gen' => 'ਜੁਲਾਈ',
'august-gen' => 'ਅਗਸਤ',
'september-gen' => 'ਸਤੰਬਰ',
'october-gen' => 'ਅਕਤੂਬਰ',
'november-gen' => 'ਨਵੰਬਰ',
'december-gen' => 'ਦਸੰਬਰ',
'jan' => 'ਜਨ',
'feb' => 'ਫ਼ਰ',
'mar' => 'ਮਾਰ',
'apr' => 'ਅਪ',
'may' => 'ਮਈ',
'jun' => 'ਜੂਨ',
'jul' => 'ਜੁਲਾ',
'aug' => 'ਅਗ',
'sep' => 'ਸਤੰ',
'oct' => 'ਅਕਤੂ',
'nov' => 'ਨਵੰ',
'dec' => 'ਦਸੰ',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|ਸ਼੍ਰੇਣੀ|ਸ਼੍ਰੇਣੀਆਂ}}',
'category_header' => 'ਸ਼੍ਰੇਣੀ "$1" ਵਿੱਚ ਲੇਖ',
'subcategories' => 'ਉਪਸ਼੍ਰੇਣੀਆਂ',
'category-media-header' => 'ਸ਼੍ਰੇਣੀ "$1" ਵਿੱਚ ਮੀਡੀਆ',
'category-empty' => "''ਇਸ ਸ਼੍ਰੇਣੀ ਵਿੱਚ ਇਸ ਵੇਲੇ ਕੋਈ ਵੀ ਪੰਨਾ ਜਾਂ ਮੀਡੀਆ ਨਹੀਂ ਹੈ।''",
'hidden-categories' => '{{PLURAL:$1|ਲੁਕਵੀਂ ਸ਼੍ਰੇਣੀ|ਲੁਕਵੀਂਆਂ ਸ਼੍ਰੇਣੀਆਂ}}',
'hidden-category-category' => 'ਲੁਕਵੀਆਂ ਕੈਟੇਗਰੀਆਂ',
'category-subcat-count' => 'ਇਸ ਸ਼੍ਰੇਣੀ ਵਿੱਚ, ਕੁੱਲ $2 ਵਿੱਚੋਂ, {{PLURAL:$2|ਕੇਵਲ ਇਹ ਉਪਸ਼੍ਰੇਣੀ ਹੈ|ਇਹ {{PLURAL:$1|ਉਪਸ਼੍ਰੇਣੀ ਹੈ|$1 ਉਪਸ਼੍ਰੇਣੀਆਂ ਹਨ}}}}।',
'category-subcat-count-limited' => 'ਇਸ ਕੈਟੇਗਰੀ ਵਿਚ {{PLURAL:$1|ਸਬ-ਕੈਟੇਗਰੀ ਹੈ|$1 ਸਬ-ਕੈਟੇਗਰੀਆਂ ਹਨ}}।',
'category-article-count' => '{{PLURAL:$2|ਇਸ ਸ਼੍ਰੇਣੀ ਵਿੱਚ ਕੇਵਲ ਇਹ ਪੰਨਾ ਹੈ।| ਇਸ ਸ਼੍ਰੇਣੀ ਵਿੱਚ, ਕੁੱਲ $2 ਵਿੱਚੋਂ, ਇਹ {{PLURAL:$1|ਪੰਨਾ ਹੈ|$1 ਪੰਨੇ ਹਨ}}}}।',
'category-article-count-limited' => 'ਮੌਜੂਦਾ ਕੈਟੇਗਰੀ ਵਿਚ ਇਹ {{PLURAL:$1|ਸਫ਼ਾ ਹੈ|$1 ਸਫ਼ੇ ਹਨ}}।',
'category-file-count' => '{{PLURAL:$2|ਇਸ ਸ਼੍ਰੇਣੀ ਵਿੱਚ ਕੇਵਲ ਇਹ ਫ਼ਾਈਲ ਹੈ|ਇਸ ਸ਼੍ਰੇਣੀ ਵਿੱਚ {{PLURAL:$1|ਫ਼ਾਈਲ ਹੈ|$1 ਫ਼ਾਈਲਾਂ ਹਨ}}}}।',
'category-file-count-limited' => 'ਮੌਜੂਦਾ ਕੈਟੇਗਰੀ ਵਿਚ ਇਹ {{PLURAL:$1|ਫ਼ਾਈਲ ਹੈ|$1 ਫ਼ਾਈਲਾਂ ਹਨ}}।',
'listingcontinuesabbrev' => 'ਜਾਰੀ',
'index-category' => 'ਤਤਕਰਾ ਸਫ਼ੇ',
'noindex-category' => 'ਬਿਨਾਂ ਤਤਕਰੇ ਵਾਲੇ ਪੰਨੇ',
'broken-file-category' => 'ਟੁੱਟੇ ਹੋਏ ਫ਼ਾਈਲ ਜੋੜਾਂ ਵਾਲ਼ੇ ਸਫ਼ੇ',

'about' => 'ਇਸ ਬਾਰੇ',
'article' => 'ਸਮੱਗਰੀ ਸਫ਼ਾ',
'newwindow' => '(ਨਵੀਂ ਵਿੰਡੋ ਵਿੱਚ ਖੁੱਲ੍ਹਦੀ ਹੈ)',
'cancel' => 'ਰੱਦ ਕਰੋ',
'moredotdotdot' => '...ਹੋਰ',
'morenotlisted' => '....ਹੋਰ ਸੂਚੀਬੱਧ ਨਹੀਂ',
'mypage' => 'ਸਫ਼ਾ',
'mytalk' => 'ਚਰਚਾ',
'anontalk' => 'ਇਸ IP ਲਈ ਗੱਲ-ਬਾਤ',
'navigation' => 'ਨੇਵੀਗੇਸ਼ਨ',
'and' => '&#32;ਅਤੇ',

# Cologne Blue skin
'qbfind' => 'ਖੋਜ',
'qbbrowse' => 'ਝਲਕ',
'qbedit' => 'ਸੋਧ',
'qbpageoptions' => 'ਇਹ ਸਫ਼ਾ',
'qbmyoptions' => 'ਮੇਰੇ ਸਫ਼ੇ',
'qbspecialpages' => 'ਖ਼ਾਸ ਸਫ਼ੇ',
'faq' => 'ਸਵਾਲ-ਜਵਾਬ',
'faqpage' => 'Project:ਸਵਾਲ-ਜਵਾਬ',

# Vector skin
'vector-action-addsection' => 'ਵਿਸ਼ਾ ਜੋੜੋ',
'vector-action-delete' => 'ਹਟਾਓ',
'vector-action-move' => 'ਭੇਜੋ',
'vector-action-protect' => 'ਸੁਰੱਖਿਆ',
'vector-action-undelete' => 'ਹਟਾਉਣਾ-ਵਾਪਸ',
'vector-action-unprotect' => 'ਸੁਰੱਖਿਆ ਬਦਲੋ',
'vector-simplesearch-preference' => 'ਸਧਾਰਨ ਖੋਜ ਸਲਾਹ ਪੱਟੀ ਯੋਗ ਕਰੋ (ਸਿਰਫ਼ ਵਿਕਟਰ ਸਕਿੰਨ ਵਿਚ)',
'vector-view-create' => 'ਬਣਾਓ',
'vector-view-edit' => 'ਸੋਧ',
'vector-view-history' => 'ਅਤੀਤ ਵੇਖੋ',
'vector-view-view' => 'ਪੜ੍ਹੋ',
'vector-view-viewsource' => 'ਸਰੋਤ ਵੇਖੋ',
'actions' => 'ਕਾਰਵਾਈਆਂ',
'namespaces' => 'ਨਾਮਸਥਾਨ',
'variants' => 'ਬਦਲ',

'navigation-heading' => 'ਨੇਵੀਗੇਸ਼ਨ ਮੇਨੂ',
'errorpagetitle' => 'ਗਲਤੀ',
'returnto' => '$1 ’ਤੇ ਵਾਪਸ ਜਾਓ।',
'tagline' => '{{SITENAME}} ਤੋਂ',
'help' => 'ਮਦਦ',
'search' => 'ਖੋਜ',
'searchbutton' => 'ਖੋਜ',
'go' => 'ਜਾਓ',
'searcharticle' => 'ਜਾਓ',
'history' => 'ਸਫ਼ੇ ਦਾ ਅਤੀਤ',
'history_short' => 'ਅਤੀਤ',
'updatedmarker' => 'ਮੇਰੀ ਆਖਰੀ ਫੇਰੀ ਤੋਂ ਬਾਅਦ ਦੇ ਅੱਪਡੇਟ',
'printableversion' => 'ਛਪਣਯੋਗ ਵਰਜਨ',
'permalink' => 'ਪੱਕਾ ਲਿੰਕ',
'print' => 'ਛਾਪੋ',
'view' => 'ਵੇਖੋ',
'edit' => 'ਸੋਧੋ',
'create' => 'ਬਣਾਓ',
'editthispage' => 'ਇਹ ਸਫ਼ਾ ਸੋਧੋ',
'create-this-page' => 'ਇਹ ਸਫ਼ਾ ਬਣਾਓ',
'delete' => 'ਹਟਾਓ',
'deletethispage' => 'ਇਹ ਸਫ਼ਾ ਹਟਾਓ',
'undelete_short' => '{{PLURAL:$1|ਇੱਕ ਸੋਧ|$1 ਸੋਧਾਂ}} ਹਟਾਉਣਾ-ਵਾਪਸ',
'viewdeleted_short' => '{{PLURAL:$1|ਹਟਾਈ ਸੋਧ|$1 ਹਟਾਈਆਂ ਸੋਧਾਂ}} ਵੇਖੋ',
'protect' => 'ਸੁਰੱਖਿਆ',
'protect_change' => 'ਬਦਲੋ',
'protectthispage' => 'ਇਹ ਸਫ਼ਾ ਸੁਰੱਖਿਅਤ ਕਰੋ',
'unprotect' => 'ਸੁਰੱਖਿਆ ਬਦਲੋ',
'unprotectthispage' => 'ਇਹ ਸਫ਼ੇ ਦੀ ਸੁਰੱਖਿਆ ਬਦਲੋ',
'newpage' => 'ਨਵਾਂ ਸਫ਼ਾ',
'talkpage' => 'ਇਸ ਸਫ਼ੇ ਬਾਰੇ ਚਰਚਾ ਕਰੋ',
'talkpagelinktext' => 'ਚਰਚਾ',
'specialpage' => 'ਖ਼ਾਸ ਸਫ਼ਾ',
'personaltools' => 'ਨਿੱਜੀ ਸੰਦ',
'postcomment' => 'ਨਵਾਂ ਭਾਗ',
'articlepage' => 'ਸਮੱਗਰੀ ਸਫ਼ਾ ਵੇਖੋ',
'talk' => 'ਚਰਚਾ',
'views' => 'ਵੇਖੋ',
'toolbox' => 'ਸੰਦ ਬਕਸਾ',
'userpage' => 'ਵਰਤੋਂਕਾਰ ਪੰਨਾ ਵੇਖੋ',
'projectpage' => 'ਪ੍ਰੋਜੈਕਟ ਸਫ਼ਾ ਵੇਖੋ',
'imagepage' => 'ਫਾਇਲ ਸਫ਼ਾ ਵੇਖੋ',
'mediawikipage' => 'ਸੁਨੇਹਾ ਪੇਜ ਵੇਖੋ',
'templatepage' => 'ਫਰਮਾ ਪੰਨਾ ਵੇਖੋ',
'viewhelppage' => 'ਮੱਦਦ ਸਫ਼ਾ ਵੇਖੋ',
'categorypage' => 'ਕੈਟੈਗਰੀ ਸਫ਼ਾ ਵੇਖੋ',
'viewtalkpage' => 'ਚਰਚਾ ਵੇਖੋ',
'otherlanguages' => 'ਹੋਰ ਭਾਸ਼ਾਵਾਂ ਵਿੱਚ',
'redirectedfrom' => '($1 ਤੋਂ ਰੀਡਿਰੈਕਟ)',
'redirectpagesub' => 'ਰੀਡਿਰੈਕਟ ਸਫ਼ਾ',
'lastmodifiedat' => 'ਇਸ ਸਫ਼ੇ ਵਿੱਚ ਆਖ਼ਰੀ ਸੋਧ $1 ਨੂੰ $2 ਵਜੇ ਹੋਈ।',
'viewcount' => 'ਇਹ ਸਫ਼ਾ {{PLURAL:$1|ਇੱਕ ਵਾਰ|$1 ਵਾਰ}} ਵੇਖਿਆ ਗਿਆ।',
'protectedpage' => 'ਸੁਰੱਖਿਅਤ ਪੇਜ',
'jumpto' => 'ਇਸ ’ਤੇ ਜਾਓ:',
'jumptonavigation' => 'ਨੇਵੀਗੇਸ਼ਨ',
'jumptosearch' => 'ਖੋਜ',
'view-pool-error' => 'ਅਫ਼ਸੋਸ, ਸਰਵਰ ਇਸ ਵੇਲੇ ਓਵਰਲੋਡ ਹੈ।
ਬਹੁਤ ਸਾਰੇ ਮੈਂਬਰ ਇਸ ਸਫ਼ੇ ਨੂੰ ਵੇਖਣ ਦੀ ਕੋਸ਼ਿਸ਼ ਕਰ ਰਹੇ ਹਨ।
ਫੇਰ ਕੋਸ਼ਿਸ਼ ਕਰਨ ਤੋਂ ਪਹਿਲਾਂ ਥੋੜੀ ਉਡੀਕ ਕਰੋ ਜੀ।
$1',
'pool-timeout' => 'ਲਾਕ ਲਈ ਉਡੀਕ ਦਾ ਵਕਤ ਖ਼ਤਮ ਹੋ ਗਿਆ ਹੈ',
'pool-queuefull' => 'ਪੂਲ ਕਤਾਰ ਭਰੀ ਹੈ',
'pool-errorunknown' => 'ਅਣਜਾਣ ਗਲਤੀ',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => '{{SITENAME}} ਬਾਰੇ',
'aboutpage' => 'Project:ਬਾਰੇ',
'copyright' => 'ਸਮੱਗਰੀ $1 ਹੇਠ ਉਪਲੱਬਧ ਹੈ।',
'copyrightpage' => '{{ns:project}}:ਕਾਪੀਰਾਈਟ',
'currentevents' => 'ਹਾਲ ਦੀਆਂ ਘਟਨਾਵਾਂ',
'currentevents-url' => 'Project:ਹਾਲ ਦੀਆਂ ਘਟਨਾਵਾਂ',
'disclaimers' => 'ਦਾਅਵੇ',
'disclaimerpage' => 'Project:ਆਮ ਦਾਅਵੇ',
'edithelp' => 'ਸੋਧ ਮੱਦਦ',
'helppage' => 'Help:ਸਮੱਗਰੀ',
'mainpage' => 'ਮੁੱਖ ਸਫ਼ਾ',
'mainpage-description' => 'ਮੁੱਖ ਸਫ਼ਾ',
'policy-url' => 'Project:ਨੀਤੀ',
'portal' => 'ਭਾਈਚਾਰਕ ਸੱਥ',
'portal-url' => 'Project:ਸਮਾਜ ਸੱਥ',
'privacy' => 'ਪਰਾਈਵੇਸੀ ਨੀਤੀ',
'privacypage' => 'Project:ਪਰਾਈਵੇਸੀ ਨੀਤੀ',

'badaccess' => 'ਅਧਿਕਾਰ ਗਲਤੀ',
'badaccess-group0' => 'ਤੁਹਾਨੂੰ ਉਹ ਐਕਸ਼ਨ ਕਰਨ ਦੀ ਮਨਜ਼ੂਰੀ ਨਹੀਂ, ਜਿਸ ਦੀ ਤੁਸੀਂ ਮੰਗ ਕੀਤੀ ਹੈ।',
'badaccess-groups' => 'ਜੋ ਕੰਮ ਤੁਸੀਂ ਕਰਨਾ ਚਾਹਿਆ ਹੈ ਓਹ {{PLURAL:$2|ਇਸ ਗਰੁੱਪ|ਇਹਨਾਂ ਗਰੁੱਪਾਂ}} ਦੇ ਮੈਂਬਰ ਹੀ ਕਰ ਸਕਦੇ ਹਨ: $1',

'versionrequired' => 'ਮੀਡੀਆਵਿਕੀ ਦੇ $1 ਵਰਜਨ ਦੀ ਲੋੜ ਹੈ',
'versionrequiredtext' => 'ਇਸ ਸਫ਼ੇ ਦੀ ਵਰਤੋਂ ਕਰਨ ਲਈ ਮੀਡੀਆਵਿਕੀ ਦੇ $1 ਵਰਜਨ ਦੀ ਲੋੜ ਹੈ।
ਵੇਖੋ [[Special:Version|ਵਰਜਨ ਸਫ਼ੇ]]।',

'ok' => 'ਠੀਕ ਹੈ',
'retrievedfrom' => '"$1" ਤੋਂ ਲਿਆ',
'youhavenewmessages' => 'ਤੁਹਾਡੇ ਲਈ $1। ($2)',
'newmessageslink' => 'ਨਵੇਂ ਸੁਨੇਹੇ',
'newmessagesdifflink' => 'ਆਖ਼ਰੀ ਤਬਦੀਲੀ',
'youhavenewmessagesfromusers' => '{{PLURAL:$3|ਇੱਕ ਵਰਤੋਂਕਾਰ|$3 ਵਰਤੋਂਕਾਰਾਂ}} ਵੱਲੋਂ ਤੁਹਾਨੂੰ $1 ($2)।',
'youhavenewmessagesmanyusers' => 'ਕਈ ਯੂਜ਼ਰ ਵੱਲੋਂ ਤੁਹਾਨੂੰ $1 ($2)।',
'newmessageslinkplural' => '{{PLURAL:$1|ਇੱਕ ਨਵਾਂ ਸੁਨੇਹਾ|ਨਵੇਂ ਸੁਨੇਹੇ}} {{PLURAL:$1|ਹੈ|ਹਨ}}',
'newmessagesdifflinkplural' => 'ਆਖ਼ਰੀ {{PLURAL:$1|ਤਬਦੀਲੀ|ਤਬਦੀਲੀਆਂ}}',
'youhavenewmessagesmulti' => '$1 ’ਤੇ ਤੁਹਾਡੇ ਲਈ ਨਵੇਂ ਸੁਨੇਹੇ ਹਨ',
'editsection' => 'ਸੋਧ',
'editold' => 'ਸੋਧ',
'viewsourceold' => 'ਸਰੋਤ ਵੇਖੋ',
'editlink' => 'ਸੋਧ',
'viewsourcelink' => 'ਸਰੋਤ ਵੇਖੋ',
'editsectionhint' => 'ਭਾਗ ਸੋਧ: $1',
'toc' => 'ਵਿਸ਼ਾ ਸੂਚੀ',
'showtoc' => 'ਵੇਖਾਓ',
'hidetoc' => 'ਓਹਲੇ',
'collapsible-collapse' => 'ਸਮੇਟੋ',
'collapsible-expand' => 'ਫੈਲਾਓ',
'thisisdeleted' => '$1 ਵੇਖੋ ਜਾਂ ਮੁੜ ਸਟੋਰ ਕਰੋ',
'viewdeleted' => '$1 ਵੇਖਣੀਆਂ ਹਨ?',
'restorelink' => '{{PLURAL:$1|ਇਕ ਮਿਟਾਈ ਹੋਈ ਸੋਧ|$1 ਮਿਟਾਈਆਂ ਹੋਈਆਂ ਸੋਧਾਂ}}',
'feedlinks' => 'ਫੀਡ:',
'feed-invalid' => 'ਸਬਸਕ੍ਰਿਪਸ਼ਨ ਫ਼ੀਡ ਦੀ ਗ਼ਲਤ ਕਿਸਮ',
'feed-unavailable' => 'ਸੰਸਥਾਵਾਂ  ਸਮੱਗਰੀ ਦਾ ਆਧੁਨਕੀਕਰਣ ਉਪਲਬਧ ਨਹੀਂ',
'site-rss-feed' => '$1 RSS ਫੀਡ',
'site-atom-feed' => '$1 ਐਟਮ ਫੀਡ',
'page-rss-feed' => '"$1" RSS ਫੀਡ',
'page-atom-feed' => '"$1" ਐਟਮ ਫੀਡ',
'red-link-title' => '$1 (ਸਫ਼ਾ ਮੌਜੂਦ ਨਹੀਂ ਹੈ)',
'sort-descending' => 'ਘੱਟਦਾ ਕ੍ਰਮ',
'sort-ascending' => 'ਵੱਧਦਾ ਕ੍ਰਮ',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'ਸਫ਼ਾ',
'nstab-user' => 'ਯੂਜ਼ਰ ਸਫ਼ਾ',
'nstab-media' => 'ਮੀਡਿਆ ਸਫ਼ਾ',
'nstab-special' => 'ਖਾਸ ਸਫ਼ਾ',
'nstab-project' => 'ਪਰੋਜੈਕਟ ਸਫ਼ਾ',
'nstab-image' => 'ਫਾਇਲ',
'nstab-mediawiki' => 'ਸੁਨੇਹਾ',
'nstab-template' => 'ਟੈਪਲੇਟ',
'nstab-help' => 'ਮੱਦਦ ਸਫ਼ਾ',
'nstab-category' => 'ਸ਼੍ਰੇਣੀ',

# Main script and global functions
'nosuchaction' => 'ਐਸਾ ਕੋਈ ਐਕਸ਼ਨ ਨਹੀਂ ਹੈ',
'nosuchactiontext' => 'URL ਦੁਆਰਾ ਦੱਸਿਆ ਕੰਮ ਗ਼ਲਤ ਹੈ।
ਸ਼ਾਇਦ ਤੁਸੀਂ URL ਸਹੀ ਨਹੀਂ ਲਿਖਿਆ ਜਾਂ ਕਿਸੇ ਗ਼ਲਤ ਲਿੰਕ ਤੇ ਆਏ ਹੋ।
ਇਹ ਵੀ ਹੋ ਸਕਦਾ ਹੈ ਕਿ ਇਹ {{SITENAME}} ਦੁਆਰੇ ਵਰਤੇ ਜਾਂਦੇ ਸਾਫ਼ਟਵੇਅਰ ਵਿਚਲੀ ਗ਼ਲਤੀ ਵੱਲ ਇਸ਼ਾਰਾ ਹੋਵੇ।',
'nosuchspecialpage' => 'ਐਸਾ ਕੋਈ ਖ਼ਾਸ ਸਫ਼ਾ ਨਹੀਂ ਹੈ',
'nospecialpagetext' => '<strong>ਤੁਸੀਂ ਇੱਕ ਗ਼ਲਤ ਖ਼ਾਸ ਸਫ਼ੇ ਲਈ ਬੇਨਤੀ ਕੀਤੀ ਹੈ।</strong>
ਸਹੀ ਖ਼ਾਸ ਸਫ਼ਿਆਂ ਦੀ ਲਿਸਟ [[Special:SpecialPages|{{int:specialpages}}]] ’ਤੇ ਵੇਖੀ ਜਾ ਸਕਦੀ ਹੈ।',

# General errors
'error' => 'ਗ਼ਲਤੀ',
'databaseerror' => 'ਡਾਟਾਬੇਸ ਗਲਤੀ',
'dberrortext' => 'ਡੈਟਾਬੇਸ ਪੁ੍ਛ ਗਿੱਛ ਵਿਚ ਹਿਦਾਇਤਾਂ ਦੀ ਤਰੁੱਟੀ ਮਿਲੀ ਹੈ।
ਹੋ ਸਕਦਾ ਹੈ ਇਹ ਤਰੁ੍ੱਟੀ ਸਾਫ਼ਟਵੇਅਰ ਦੀ ਹੋਵੇ।
ਇਸ ਗਣਿਤਫ਼ਲ "<tt>$2</tt>" ਵਿਚੌਂ ਪਿਛਲੀ ਡੈਟਬਾਸ ਪੁੱਛ ਗਿੱਛ ਸੀ: <blockquote><tt>$1</tt></blockquote.
ਡੈਟਾਬੇਸ ਨੇ ਇਹ ਤਰੁੱਟੀ "<tt>$3: $4</tt>"ਜਵਾਬ ਵਿਚ ਦਿੱਤੀ।',
'dberrortextcl' => 'ਡੈਟਾਬੇਸ ਪੁੱਛਗਿੱਛ ਵਿਚ ਹਿਦਾਇਤ ਗਲਤੀ ਮਿਲੀ ਹੈ।
ਫੰਕਸ਼ਨ "$2" ਤੋਂ ਪਿਛਲੀ ਡਾਟਬਾਸ ਪੁੱਛ ਗਿੱਛ ਸੀ: "$1".
ਡੈਟਾਬੇਸ ਨੇ ਇਹ ਗਲਤੀ "$3:$4" ਦਿੱਤੀ',
'laggedslavemode' => "'''ਖ਼ਬਰਦਾਰ:''' ਹੋ ਸਕਦਾ ਹੈ ਸਫ਼ੇ ਵਿਚ ਤਾਜ਼ਾ ਤਬਦੀਲੀਆਂ ਸ਼ਾਮਲ ਨਾ ਹੋਣ।",
'readonly' => 'ਡਾਟਾਬੇਸ ਲਾਕ ਹੈ',
'enterlockreason' => 'ਤਾਲਾ-ਬੰਦੀ ਲਈ ਕਾਰਨ ਦਾਖ਼ਲ ਕਰੋ, ਨਾਲ਼ ਹੀ ਤਾਲਾ-ਬੰਦੀ ਦੇ ਰਿਲੀਜ਼ ਹੋਣ ਦਾ ਅੰਦਾਜ਼ਨ ਵਕਤ',
'readonlytext' => 'ਡੈਟਾਬੇਸ ਨੂੰ ਇਸ ਵੇਲ਼ੇ ਤਾਲਾ ਲੱਗਾ ਹੋਇਆ ਹੈ, ਸ਼ਾਇਦ ਆਮ ਰੱਖ-ਰਖਾਵ ਲਈ, ਇਸਤੋਂ ਬਾਅਦ ਇਹ ਆਮ ਵਾਂਗ ਉਪਲੱਬਧ ਹੋਵੇਗਾ।
ਜਿਸ ਪ੍ਰਬੰਧਕ ਨੇ ਇਸਨੂੰ ਤਾਲਾ ਲਾਇਆ ਹੈ ਉਸਦਾ ਕਹਿਣਾ ਹੈ ਕਿ: $1',
'missing-article' => "ਡਾਟਾਬੇਸ ਨੂੰ ''$1'' $2 ਨਾਮ ਦਾ ਕੋਈ ਪੰਨਾ ਨਹੀਂ ਮਿਲਿਆ।
ਆਮ ਤੌਰ ਤੇ ਹਟਾਈ ਜਾ ਚੁੱਕੇ ਪੰਨੇ ਦਾ ਇਤਿਹਾਸ ਕੜੀ ਦੀ ਵਰਤੋਂ ਕਰਨ ਨਾਲ ਇੰਝ ਹੁੰਦਾ ਹੈ।
ਜੇ ਇਹ ਗੱਲ ਨਹੀਂ ਤਾਂ ਹੋ ਸਕਦਾ ਹੈ ਤੁਹਾਨੂੰ ਸਾਫ਼ਟਵੇਅਰ ਵਿਚ ਖਾਮੀ ਮਿਲ ਗਈ ਹੈ। ਕਿਰਪਾ ਕਰਕੇ ਪੰਨੇ ਦੇ ਪਤੇ ਸਮੇਤ [[Special:ListUsers/sysop|administrator]] ਨੂੰ ਇਤਲਾਹ ਦਿਓ।",
'missingarticle-rev' => '(ਰੀਵਿਜ਼ਨ#: $1)',
'missingarticle-diff' => '(ਅੰਤਰ: $1, $2)',
'readonly_lag' => 'ਜਦੌਂ ਤਕ ਅਧੀਨ ਡੇਟਾਬੇਸ ਸਰਵਰ ਸੁਤੰਤਰ ਡੈਟਾਬੇਸ ਸਰਵਰ ਦੀ ਪਕੜ ਵਿਚ ਨਹੀਂ ਆ ਜਾਂਦੇ ਡੈਟਾਬੇਸ ਸਵੈ ਜਕੜਿਆ ਗਿਆ ਹੈ।',
'internalerror' => 'ਅੰਦਰੂਨੀ ਗ਼ਲਤੀ',
'internalerror_info' => 'ਅੰਦਰੂਨੀ ਗ਼ਲਤੀ: $1',
'fileappenderrorread' => 'ਅੰਤਕਾ ਜੋੜਨ ਲਗਿਆਂ "$1"ਪੜ੍ਹਿਆ ਨਹੀਂ ਜਾ ਸਕਿਆ।',
'fileappenderror' => "''$1'' ''$2'' ਨਾਲ਼ ਜੋੜਿਆ ਨਹੀ ਜਾ ਸਕਦਾ",
'filecopyerror' => "ਫਾਇਲ '''$1'' '$2''' ਵਿੱਚ ਕਾਪੀ ਨਹੀਂ ਕੀਤੀ ਜਾ ਸਕੀ।",
'filerenameerror' => "ਫਾਇਲ ''$1'' ਦਾ ਨਾਂ ''$2'' ਬਦਲਿਆ ਨਹੀਂ ਜਾ ਸਕਿਆ।",
'filedeleteerror' => "''$1'' ਫਾਇਲ ਹਟਾਈ ਨਹੀਂ ਜਾ ਸਕੀ।",
'directorycreateerror' => "ਡਾਇਰੈਕਟਰੀ ''$1'' ਬਣਾਈ ਨਹੀਂ ਜਾ ਸਕੀ।",
'filenotfound' => "ਫਾਇਲ ''$1'' ਲੱਭੀ ਨਹੀਂ ਜਾ ਸਕੀ।",
'fileexistserror' => 'ਫਾਇਲ "$1" ਉੱਤੇ ਲਿਖ ਨਹੀਂ ਸਕਦੇ: ਫਾਇਲ ਮੌਜੂਦ ਹੈ।',
'unexpected' => 'ਅਣਉਮੀਦਿਆ ਮੁੱਲ: "$1"="$2"।',
'formerror' => 'ਗ਼ਲਤੀ: ਫ਼ਾਰਮ ਪੇਸ਼ ਨਹੀਂ ਕੀਤਾ ਜਾ ਸਕਿਆ',
'badarticleerror' => 'ਇਹ ਕਾਰਵਾਈ ਇਸ ਸਫ਼ੇ ਤੇ ਨਹੀਂ ਕੀਤੀ ਜਾ ਸਕਦੀ।',
'cannotdelete' => "ਫ਼ਾਈਲ ਜਾਂ ਸਫ਼ਾ ''$1'' ਨੂੰ ਮਿਟਾਇਆ ਨਹੀਂ ਜਾ ਸਕਿਆ।
ਸ਼ਾਇਦ ਕੋਈ ਪਹਿਲਾਂ ਹੀ ਇਸਨੂੰ ਮਿਟਾ ਚੁੱਕਾ ਹੈ।",
'cannotdelete-title' => "ਸਫ਼ਾ ''$1'' ਨੂੰ ਹਟਾਇਆ ਨਹੀਂ ਜਾ ਸਕਿਆ",
'delete-hook-aborted' => 'ਹੁੱਕ ਨੇ ਮਿਟਾਉਣਾ ਨਾਕਾਮ ਕੀਤਾ।
ਇਸਨੇ ਕੋਈ ਕਾਰਨ ਨਹੀਂ ਦੱਸਿਆ।',
'badtitle' => 'ਗਲਤ ਸਿਰਲੇਖ',
'badtitletext' => 'ਤੁਹਾਡਾ ਦਰਖਾਸਤਸ਼ੁਦਾ ਸਿਰਲੇਖ ਨਾਕਾਬਿਲ, ਖਾਲੀ ਜਾਂ ਗਲਤ ਜੁੜਿਆ ਹੋਇਆ inter-languagd ਜਾਂ inter-wiki ਸਿਰਲੇਖ ਹੈ। ਇਹ ਵੀ ਹੋ ਸਕਦਾ ਹੈ ਕਿ ਇਸ ਵਿੱਚ ਇਕ-ਦੋ ਅੱਖਰ ਐਸੇ ਹੋਣ ਜੋ ਸਿਰਲੇਖ ਵਿੱਚ ਵਰਤੇ ਨਹੀਂ ਜਾ ਸਕਦੇ।',
'querypage-no-updates' => 'ਇਸ ਪੇਜ  ਦਾ ਆਧੁਨੀਕਰਣ ਵਰਜਿਤ ਹੈ।
ਆਂਕੜੇ ਹੱਲੇ ਤਾਜ਼ੇ ਨਹੀ ਹੋ ਸਕਦੇ ।',
'wrong_wfQuery_params' => ' wfQuery()<br /> ਨੂ ਲਤ ਰਾਸ਼ੀ ਮਿਲੇ ਹੋਯੇ ਨੇ
 ਫੁਨ੍ਕ੍ਤਿਓਂ:$1<br />
 ਪ੍ਰਸ਼ਨ: $2',
'viewsource' => 'ਸਰੋਤ ਵੇਖੋ',
'viewsource-title' => '$1 ਲਈ ਸਰੋਤ ਵੇਖੋ',
'actionthrottled' => 'ਕਾਰਵਾਈ ਬੰਦ ਕੀਤੀ ਗਈ।',
'actionthrottledtext' => 'ਸਪੈਮ ਦੀ ਰੋਕਥਾਮ ਲਈ, ਇਹ ਕਰੀਆ ਇਨ੍ਹੇ ਘੱਟ ਸਮੇਂ ਵਿੱਚ ਇੱਕ ਸੀਮਾ ਤੋਂ ਜਿਆਦਾ ਵਾਰ ਕਰਨ ਤੋਂ ਮਨਾਹੀ ਹੈ, ਅਤੇ ਤੁਸੀਂ ਇਸ ਸੀਮਾ ਨੂੰ ਪਾਰ ਕਰ ਚੁੱਕੇ ਹੋ।
ਕਿਰਪਾ ਕਰਕੇ ਕੁਝ ਸਮੇਂ ਬਾਅਦ ਪੁੰਨ: ਜਤਨ ਕਰੋ।',
'protectedpagetext' => 'ਇਹ ਪੰਨੇ ਸੰਪਾਦਨ ਅਤੇ ਹੋਰ ਕੰਮ ਤੋਂ ਸੁਰੱਖਿਅਤ ਕੀਤਾ ਹੋਇਆ ਹੈ।',
'viewsourcetext' => 'ਤੁਸੀਂ ਇਸ ਸਫ਼ੇ ਦਾ ਸਰੋਤ ਵੇਖ ਅਤੇ ਕਾਪੀ ਕਰ ਸਕਦੇ ਹੋ:',
'viewyourtext' => 'ਤੁਸੀਂ ਇਸ ਸਫ਼ੇ ’ਤੇ ਕੀਤੀਆਂ "ਆਪਣੀਆਂ ਸੋਧਾਂ" ਦਾ ਸਰੋਤ ਵੇਖ ਅਤੇ ਨਕਲ ਕਰ ਸਕਦੇ ਹੋ:',
'protectedinterface' => 'ਇਹ ਪੰਨਾ ਸਾਫ਼ਟਵੇਅਰ ਇੰਟਰਫ਼ੇਸ ਦਾ ਮੂਲ ਪਾਠ ਹੈ ,ਅਤੇ ਦੁਰਵਰਤੌਂ ਤੌਂ ਬਚਾਅ ਲਈ ਰਾਖਵਾਂ ਕੀਤਾ ਗਿਆ ਹੈ।',
'editinginterface' => "'''ਚਿਤਾਵਨੀ''' ਤੁਸੀਂ ਐਸੇ ਪੰਨੇ ਨੂੰ ਬਦਲ ਰਹੇ ਹੋ ਜੋ ਸਾਫ਼ਟਵੇਅਰ ਇੰਟਰਫ਼ੇਸ ਦੇ ਮੂਲ ਪਾਠ ਲਈ ਵਰਤਿਆ ਗਿਆ ਹੈ।
ਇਸ ਪੰਨੇ ਦੇ ਬਦਲਾਅ ਦੁਸਰੇ ਵਰਤੋਂ ਕਰਣ ਵਾਲਿਆਂ ਲਈ ਵਰਤੇ ਜਾਣ ਵਾਲੇ ਇੰਟਰਫਲੇਸ ਦੀ ਸ਼ਕਲ ਤੇ ਅਸਰ ਪਾ ਦੇਣਗੇ।ਅਨੁਵਾਦ ਕਰਣ ਲਈ ,ਕਿਰਪਾ ਕਰਕੇ [//translatewiki.net/wiki/Main_Page?setlang=pa ਟ੍ਰਾਂਸਲੇਟਵਿਕੀ.ਨੈਟ] ਦੀ ਵਰਤੌਂ ਕਰੋ,ਇਹ ਮੀਡੀਆਵਿਕੀ ਦੀ ਸਥਾਨਕੀਕਰਣ ਯੋਜਨਾ ਹੈ।",
'sqlhidden' => '(SQL ਪ੍ਰਸ਼ਨ ਚੁਪ੍ਪੇ ਹੁਏ ਨੇ)',
'cascadeprotected' => 'ਇਹ ਪੰਨਾ ਸੁਰੱਖਿਅਤ ਹੈ, ਕਿਉਂਕਿ ਇਹ ਨਿੱਚੇ ਲਿਖੇ {{PLURAL:$1|ਪੰਨਾ|ਪੰਨੇ}} ਦੀ ਸੁਰੱਖਿਆ-ਸੀੜੀ ਵਿੱਚ ਸ਼ਾਮਲ ਹੈ:
$2',
'namespaceprotected' => "ਤੁਹਾਨੂੰ '''$1''' ਥਾਂ-ਨਾਮ ਵਾਲ਼ੇ ਸਫ਼ਿਆਂ ਵਿਚ ਫੇਰ-ਬਦਲ ਕਰਨ ਦੀ ਇਜਾਜ਼ਤ ਨਹੀਂ ਹੈ।",
'customcssprotected' => 'ਤੁਹਾਨੂੰ ਇਸ CSS ਸਫ਼ੇ ਵਿਚ ਸੋਧ ਕਰਨ ਦੀ ਇਜਾਜ਼ਤ ਨਹੀਂ ਹੈ ਕਿਉਂਕਿ ਇਸ ਵਿਚ ਕਿਸੇ ਹੋਰ ਮੈਂਬਰ ਦੀਆਂ ਨਿੱਜੀ ਸੈਟਿੰਗਾਂ ਹਨ।',
'customjsprotected' => 'ਤੁਹਾਨੂੰ ਇਸ CSS ਸਫ਼ੇ ਵਿਚ ਸੋਧ ਕਰਨ ਦੀ ਇਜਾਜ਼ਤ ਨਹੀਂ ਹੈ ਕਿਉਂਕਿ ਇਸ ਵਿਚ ਕਿਸੇ ਹੋਰ ਮੈਂਬਰ ਦੀਆਂ ਨਿੱਜੀ ਸੈਟਿੰਗਾਂ ਹਨ।',
'ns-specialprotected' => 'ਖ਼ਾਸ ਸਫ਼ਿਆਂ ’ਚ ਫੇਰ-ਬਦਲ ਨਹੀਂ ਹੋ ਸਕਦੇ।',
'titleprotected' => 'ਇਹ ਸਿਰਲੇਖ [[User:$1|$1]] ਵੱਲੋਂ ਸੁਰੱਖਿਅਤ ਕੀਤਾ ਗਿਆ ਹੈ ਅਤੇ ਵਰਤਿਆ ਨਹੀਂ ਜਾ ਸਕਦਾ। ਦਿੱਤਾ ਹੋਇਆ ਕਾਰਨ ਹੈ, "\'\'$2\'\'"।',
'invalidtitle-knownnamespace' => 'ਥਾਂ-ਨਾਮ "$2" ਅਤੇ ਲਿਖਤ "$3" ਵਾਲ਼ਾ ਗ਼ਲਤ ਸਿਰਲੇਖ',
'exception-nologin' => 'ਲਾਗਇਨ ਨਹੀਂ ਕੀਤਾ',
'exception-nologin-text' => 'ਇਹ ਸਫ਼ਾ ਜਾਂ ਕਾਰਵਾਈ ਤੁਹਾਡਾ ਇਸ ਵਿਕੀ ’ਤੇ ਲਾਗਇਨ ਕੀਤਾ ਹੋਣਾ ਲੋੜਦੀ ਹੈ।',

# Virus scanner
'virus-scanfailed' => 'ਸਕੈਨ ਫੇਲ੍ਹ ਹੈ (ਕੋਡ $1)',
'virus-unknownscanner' => 'ਅਣਪਛਾਤਾ ਐਂਟੀਵਾਇਰਸ:',

# Login and logout pages
'logouttext' => "'''ਹੁਣ ਤੁਸੀਂ ਲਾਗਆਉਟ ਹੋ ਗਏ ਹੋ।'''

You can continue to use {{SITENAME}} anonymously, or you can log in again as the same or as a different user.
Note that some pages may continue to be displayed as if you were still logged in, until you clear your browser cache.",
'welcomeuser' => '$1 ਜੀ ਆਇਆਂ ਨੂੰ!',
'welcomecreation-msg' => 'ਤੁਹਾਡਾ ਖਾਤਾ ਬਣ ਚੁੱਕਾ ਹੈ। ਆਪਣੀਆਂ [[Special:Preferences|{{SITENAME}} ਪਸੰਦ]] ਬਦਲਣੀ ਨਾ ਭੁੱਲੋ।',
'yourname' => 'ਯੂਜ਼ਰ-ਨਾਂ:',
'userlogin-yourname' => 'ਯੂਜ਼ਰ ਨਾਂ',
'userlogin-yourname-ph' => 'ਆਪਣਾ ਯੂਜਰ-ਨਾਂ ਦਿਉ',
'yourpassword' => 'ਪਾਸਵਰਡ:',
'userlogin-yourpassword' => 'ਪਾਸਵਰਡ',
'userlogin-yourpassword-ph' => 'ਆਪਣਾ ਪਾਸਵਰਡ ਦਿਉ',
'createacct-yourpassword-ph' => 'ਪਾਸਵਰਡ ਦਿਉ',
'yourpasswordagain' => 'ਪਾਸਵਰਡ ਮੁੜ ਲਿਖੋ:',
'createacct-yourpasswordagain' => 'ਪਾਸਵਰਡ ਪੁਸ਼ਟੀ',
'createacct-yourpasswordagain-ph' => 'ਪਾਸਵਰਡ ਫੇਰ ਦਿਉ',
'remembermypassword' => 'ਇਸ ਕੰਪਿਊਟਰ ’ਤੇ ਮੇਰਾ ਲਾਗਇਨ ਯਾਦ ਰੱਖੋ (ਵੱਧ ਤੋਂ ਵੱਧ $1 {{PLURAL:$1|ਦਿਨ|ਦਿਨਾਂ}} ਲਈ)',
'userlogin-remembermypassword' => 'ਮੈਨੂੰ ਯਾਦ ਰੱਖੋ',
'userlogin-signwithsecure' => 'ਸੁਰੱਖਿਅਤ ਸਰਵਰ ਨਾਲ ਸਾਇਨ ਕਰੋ',
'securelogin-stick-https' => 'ਲਾਗਇਨ ਕਰਨ ਦੇ ਬਾਅਦ HTTPS ਨਾਲ ਕੁਨੈਕਟ ਰਹੋ',
'yourdomainname' => 'ਤੁਹਾਡੀ ਡੋਮੇਨ:',
'password-change-forbidden' => 'ਇਸ ਵਿਕੀ ਤੇ ਤੁਸੀਂ ਪਾਸਵਰਡ ਨਹੀਂ ਬਦਲ ਸਕਦੇ।',
'login' => 'ਲਾਗ ਇਨ',
'nav-login-createaccount' => 'ਲਾਗ ਇਨ/ਖਾਤਾ ਬਣਾਓ',
'loginprompt' => 'ਤੁਹਾਨੂੰ {{SITENAME}} ’ਤੇ ਲਾਗਇਨ ਕਰਨ ਲਈ ਕੂਕੀਸ ਯੋਗ ਕਰਨੇ ਜ਼ਰੂਰੀ ਹਨ।',
'userlogin' => 'ਲਾਗ ਇਨ/ਖਾਤਾ ਬਣਾਓ',
'userloginnocreate' => 'ਲਾਗ ਇਨ',
'logout' => 'ਲਾਗ ਆਉਟ',
'userlogout' => 'ਲਾਗ ਆਉਟ',
'notloggedin' => 'ਲਾਗਇਨ ਨਹੀਂ',
'userlogin-noaccount' => 'ਖਾਤਾ ਨਹੀਂ ਹੈ?',
'userlogin-joinproject' => '{{SITENAME}} ਦਾ ਹਿੱਸਾ ਬਣੋ',
'nologin' => 'ਖਾਤਾ ਨਹੀਂ ਹੈ? $1।',
'nologinlink' => 'ਖਾਤਾ ਬਣਾਓ',
'createaccount' => 'ਖਾਤਾ ਬਣਾਓ',
'gotaccount' => 'ਖਾਤਾ ਪਹਿਲਾਂ ਹੀ ਹੈ? $1',
'gotaccountlink' => 'ਲਾਗ ਇਨ',
'userlogin-resetlink' => 'ਆਪਣੀ ਲਾਗਇਨ ਜਾਣਕਾਰੀ ਭੁੱਲ ਗਏ ਹੋ?',
'helplogin-url' => 'Help: ਲਾਗਇਨ ਕਰਨਾ',
'userlogin-helplink' => '[[{{MediaWiki:helplogin-url}}|ਲਾਗਇਨ ਕਰਨ ਬਾਰੇ ਮੱਦਦ]]',
'createacct-join' => 'ਆਪਣੀ ਜਾਣਕਾਰੀ ਹੇਠਾਂ ਦਿਉ।',
'createacct-emailrequired' => 'ਈਮੇਲ ਐਡਰੈਸ',
'createacct-emailoptional' => 'ਈਮੇਲ ਐਡਰੈਸ (ਚੋਣਵਾਂ)',
'createacct-email-ph' => 'ਆਪਣਾ ਈਮੇਲ ਐਡਰੈਸ ਦਿਉ',
'createaccountmail' => 'ਆਰਜ਼ੀ ਰਲਵਾਂ ਪਾਸਵਰਡ ਵਰਤੋਂ ਅਤੇ ਇਸ ਨੂੰ ਹੇਠ ਦਿੱਤੇ ਈਮੇਲ ਸਿਰਨਾਵੇਂ ਉੱਤੇ ਭੇਜ ਦਿਉ',
'createacct-realname' => 'ਅਸਲੀ ਨਾਂ (ਚੋਣਵਾਂ)',
'createaccountreason' => 'ਕਾਰਨ:',
'createacct-reason' => 'ਕਾਰਨ',
'createacct-captcha' => 'ਸੁਰੱਖਿਆ ਜਾਂਚ',
'createacct-imgcaptcha-ph' => 'ਉੱਤੇ ਵੇਖਾਈ ਦੇ ਰਿਹਾ ਸ਼ਬਦ ਦਿਉ',
'createacct-benefit-heading' => '{{SITENAME}} ਨੂੰ ਤੁਹਾਡੇ ਵਰਗੇ ਲੋਕਾਂ ਵਲੋਂ ਹੀ ਬਣਾਇਆ ਗਿਆ ਹੈ।',
'createacct-benefit-body1' => 'ਸੋਧਾਂ',
'createacct-benefit-body2' => 'ਸਫ਼ੇ',
'createacct-benefit-body3' => 'ਇਹ ਮਹੀਨੇ ਲਈ ਯੋਗਦਾਨ',
'badretype' => 'ਤੁਹਾਡੇ ਵਲੋਂ ਦਿੱਤੇ ਪਾਸਵਰਡ ਮਿਲਦੇ ਨਹੀਂ ਹਨ।',
'userexists' => 'ਯੂਜ਼ਰ-ਨਾਂ ਪਹਿਲਾਂ ਹੀ ਮੌਜੂਦ ਹੈ। ਵੱਖਰਾ ਨਾਂ ਚੁਣੋ ਜੀ।',
'loginerror' => 'ਲਾਗਇਨ ਗ਼ਲਤੀ',
'createacct-error' => 'ਖਾਤਾ ਬਣਾਉਣ ਗਲਤੀ',
'createaccounterror' => 'ਖਾਤਾ ਬਣਾਇਆ ਨਹੀਂ ਜਾ ਸਕਿਆ: $1',
'nocookiesnew' => 'ਯੂਜ਼ਰ ਅਕਾਊਂਟ ਬਣਾਇਆ ਗਿਆ ਹੈ, ਪਰ ਤੁਸੀਂ ਲਾਗਇਨ ਨਹੀਂ ਕੀਤਾ ਹੈ।{{SITENAME}} uses cookies to log in users. You have cookies disabled. Please enable them, then log in with your new username and password.',
'nocookieslogin' => '{{SITENAME}} ਯੂਜ਼ਰਾਂ ਨੂੰ ਲਾਗਇਨ ਕਰਨ ਲਈ ਕੂਕੀਜ਼ ਵਰਤਦੀ ਹੈ। ਤੁਹਾਡੇ ਕੂਕੀਜ਼ ਆਯੋਗ ਕੀਤੇ ਹੋਏ ਹਨ। ਉਨ੍ਹਾਂ ਨੂੰ ਯੋਗ ਕਰਕੇ ਮੁੜ ਟਰਾਈ ਕਰੋ।',
'noname' => 'ਤੁਸੀਂ ਇੱਕ ਸਹੀ ਯੂਜ਼ਰ-ਨਾਂ ਨਹੀਂ ਦਿੱਤਾ ਹੈ।',
'loginsuccesstitle' => 'ਲਾਗਇਨ ਸਫ਼ਲ',
'loginsuccess' => "'''ਤੁਸੀਂ {{SITENAME}} ਉੱਤੇ \"\$1\" ਵਜੋਂ ਲਾਗਇਨ ਕਰ ਚੁੱਕੇ ਹੋ।'''",
'nosuchuser' => '!"$1" ਨਾਂ ਨਾਲ ਕੋਈ ਯੂਜ਼ਰ ਨਹੀਂ ਹੈ। ਆਪਣੇ ਸ਼ਬਦ ਜੋੜ ਧਿਆਨ ਨਾਲ ਚੈਕ ਕਰੋ ਉਪਰ ਹੇਠਾਂ ਦਾ ਕੇਸ ਵਰਤਣ ਨਾਲ ਫ਼ਰਕ ਪੈਂਦਾ ਹੈ ਜਾਂ [[Special:UserLogin/signup|ਨਵਾਂ ਖਾਤਾ ਬਣਾਓ]]',
'nosuchusershort' => '"$1" ਨਾਂ ਨਾਲ ਕੋਈ ਵੀ ਯੂਜ਼ਰ ਨਹੀਂ ਹੈ। ਆਪਣੇ ਸ਼ਬਦ ਧਿਆਨ ਨਾਲ ਚੈੱਕ ਕਰੋ।',
'nouserspecified' => 'ਤੁਹਾਨੂੰ ਇੱਕ ਯੂਜ਼ਰ-ਨਾਂ ਦੇਣਾ ਪਵੇਗਾ।',
'login-userblocked' => 'ਇਹ ਯੂਜ਼ਰ ਪਾਬੰਦੀਸ਼ੁਦਾ ਹੈ। ਲਾਗਇਨ ਦੀ ਇਜਾਜ਼ਤ ਨਹੀਂ ਹੈ।',
'wrongpassword' => 'ਗਲਤ ਪਾਸਵਰਡ ਦਿੱਤਾ ਹੈ। ਮੁੜ-ਕੋਸ਼ਿਸ਼ ਕਰੋ ਜੀ।',
'wrongpasswordempty' => 'ਖਾਲੀ ਪਾਸਵਰਡ ਦਿੱਤਾ ਹੈ। ਮੁੜ-ਕੋਸ਼ਿਸ਼ ਕਰੋ ਜੀ।',
'passwordtooshort' => 'ਪਾਸਵਰਡ {{PLURAL:$1|1 ਅੱਖਰ|$1 ਅੱਖਰਾਂ}} ਦਾ ਹੋਣਾ ਲਾਜ਼ਮੀ ਹੈ।',
'password-name-match' => 'ਤੁਹਾਡਾ ਪਾਸਵਰਡ ਤੁਹਾਡੇ ਯੂਜ਼ਰ ਨਾਂ ਤੋਂ ਵੱਖਰਾ ਹੋਣਾ ਚਾਹੀਦਾ ਹੈ।',
'password-login-forbidden' => 'ਇਹ ਯੂਜ਼ਰ-ਨਾਂ ਅਤੇ ਪਾਸਵਰਡ ਵਰਤਣ ਦੀ ਮਨਾਹੀ ਹੈ।',
'mailmypassword' => 'ਨਵਾਂ ਪਾਸਵਰਡ ਈ-ਮੇਲ ਕਰੋ',
'passwordremindertitle' => '{{SITENAME}} ਲਈ ਪਾਸਵਰਡ ਯਾਦ ਰੱਖੋ',
'passwordremindertext' => 'ਕਿਸੇ ਨੇ (ਸ਼ਾਇਦ ਤੁਸੀਂ, IP ਪਤਾ $1 ਤੋਂ) {{SITENAME}} ਲਈ ਪਾਸਵਰਡ ਬਦਲਣ ਦੀ ਬੇਨਤੀ ਕੀਤੀ ਹੈ ($4)।
ਮੈਂਬਰ "$2" ਲਈ ਆਰਜ਼ੀ ਪਾਸਵਰਡ ਬਣਾ ਕੇ "$3" ਤੇ ਭੇਜ ਦਿੱਤਾ ਗਿਆ ਹੈ।
ਜੇ ਤੁਹਾਡਾ ਇਹੀ ਇਰਾਦਾ ਸੀ ਤਾਂ ਤੁਹਾਨੂੰ ਚਾਹੀਦਾ ਹੈ ਹੁਣੇ ਲਾਗਇਨ ਕਰਕੇ ਆਪਣਾ ਪਾਸਵਰਡ ਲਓ।
ਤੁਹਾਡਾ ਆਰਜ਼ੀ ਪਾਸਵਰਡ {{PLURAL:$5|ਇਕ ਦਿਨ|$5 ਦਿਨਾਂ}} ਵਿਚ ਖ਼ਤਮ ਹੋ ਜਾਵੇਗਾ।

ਜੇ ਕਿਸੇ ਹੋਰ ਨੇ ਇਹ ਬੇਨਤੀ ਕੀਤੀ ਸੀ ਜਾਂ ਜੇ ਤੁਹਾਨੂੰ ਆਪਣਾ ਪਾਸਵਰਡ ਯਾਦ ਹੈ ਅਤੇ ਤੁਸੀਂ ਇਸਨੂੰ ਬਦਲਣਾ ਨਹੀਂ ਚਾਹੁੰਦੇ ਤਾਂ ਤੁਸੀਂ ਇਸ ਸੁਨੇਹੇ ਨੂੰ ਨਜ਼ਰਅੰਦਾਜ਼ ਕਰ ਕੇ ਆਪਣਾ ਪੁਰਾਣਾ ਪਾਸਵਰਡ ਵਰਤਣਾ ਜਾਰੀ ਰੱਖ ਸਕਦੇ ਹੋ।',
'noemail' => 'ਯੂਜ਼ਰ "$1" ਲਈ ਰਿਕਾਰਡ ਵਿੱਚ ਕੋਈ ਈਮੇਲ ਐਡਰੈੱਸ ਨਹੀਂ ਹੈ।',
'noemailcreate' => 'ਤੁਹਾਨੂੰ ਠੀਕ ਈਮੇਲ ਐਡਰੈੱਸ ਦੇਣਾ ਪਵੇਗਾ',
'passwordsent' => '"$1" ਨਾਲ ਰਜਿਸਟਰ ਕੀਤੇ ਈਮੇਲ ਐਡਰੈੱਸ ਉੱਤੇ ਈਮੇਲ ਭੇਜੀ ਗਈ ਹੈ।
ਇਹ ਮਿਲ ਦੇ ਬਾਅਦ ਮੁੜ ਲਾਗਇਨ ਕਰੋ ਜੀ।',
'blocked-mailpassword' => 'ਤੁਹਾਡੇ IP ਪਤੇ ਤੇ ਸੋਧ ਕਰਨ ਤੇ ਪਾਬੰਦੀ ਹੈ ਅਤੇ ਇਸੇ ਕਰਕੇ, ਗ਼ਲਤ ਵਰਤੋਂ ਤੋਂ ਬਚਣ ਲਈ, ਪਾਸਵਰਡ ਹਾਸਲ ਕਰਨ ਵਾਲ਼ੀ ਸਹੂਲਤ ਦੀ ਵਰਤੋਂ ਦੀ ਇਜਾਜ਼ਤ ਨਹੀਂ ਹੈ।',
'eauthentsent' => 'ਇਕ ਤਸਦੀਕੀ ਈ-ਮੇਲ ਨਾਮਜ਼ਦ ਕੀਤੇ ਈ-ਮੇਲ ਪਤੇ ਤੇ ਭੇਜੀ ਜਾ ਚੁੱਕੀ ਹੈ।
ਤੁਹਾਡੇ ਪਤੇ ਤੇ ਕੋਈ ਹੋਰ ਈ-ਮੇਲ ਭੇਜਣ ਤੋਂ ਪਹਿਲਾਂ, ਇਹ ਤਸਦੀਕ ਕਰਨ ਲਈ ਕਿ ਖਾਤਾ ਅਸਲ ਵਿਚ ਤੁਹਾਡਾ ਹੀ ਹੈ, ਤੁਹਾਨੂੰ ਉਸ ਈ-ਮੇਲ ਵਿਚਲੀਆਂ ਹਦਾਇਤਾਂ ਤੇ ਅਮਲ ਕਰਨਾ ਹੋਵੇਗਾ।',
'throttled-mailpassword' => 'ਆਖ਼ਰੀ {{PLURAL:$1|ਇੱਕ ਘੰਟੇ|$1 ਘੰਟਿਆਂ}} ਵਿਚ ਇੱਕ ਪਾਸਵਰਡ ਯਾਦ-ਦਹਾਨੀ ਪਹਿਲਾਂ ਹੀ ਭੇਜੀ ਜਾ ਚੁੱਕੀ ਹੈ।
ਗ਼ਲਤ ਵਰਤੋਂ ਤੋਂ ਬਚਣ ਲਈ, {{PLURAL:$1|ਇੱਕ ਘੰਟੇ|$1 ਘੰਟਿਆਂ}} ਵਿੱਚ ਸਿਰਫ਼ ਇੱਕ ਹੀ ਪਾਸਵਰਡ ਯਾਦ-ਦਹਾਨੀ ਭੇਜੀ ਜਾਂਦੀ ਹੈ।',
'mailerror' => 'ਈਮੇਲ ਭੇਜਣ ਦੌਰਾਨ ਗਲਤੀ: $1',
'acct_creation_throttle_hit' => 'ਤੁਹਾਡਾ IP ਪਤਾ ਵਰਤ ਕੇ ਆਉਣ ਵਾਲ਼ਿਆਂ ਨੇ ਆਖ਼ਰੀ ਦਿਨਾਂ ਵਿਚ ਇਸ ਵਿਕੀ ’ਤੇ {{PLURAL:$1|੧ ਖਾਤਾ ਬਣਾਇਆ ਹੈ|$1 ਖਾਤੇ ਬਣਾਏ ਹਨ}} ਜੋ ਕਿ, ਹਾਲ ਦੀ ਘੜੀ, ਖਾਤੇ ਬਣਾਉਣ ਦੀ ਆਖ਼ਰੀ ਹੱਦ ਹੈ।
ਨਤੀਜੇ ਵਜੋਂ ਇਸ IP ਪਤੇ ਨੂੰ ਵਰਤਣ ਵਾਲ਼ੇ ਫ਼ਿਲਹਾਲ ਹੋਰ ਖਾਤੇ ਨਹੀਂ ਬਣਾ ਸਕਦੇ।',
'emailauthenticated' => 'ਤੁਹਾਡਾ ਈ-ਮੇਲ ਪਤਾ $2 ਨੂੰ $1 ’ਤੇ ਤਸਦੀਕ ਕੀਤਾ ਗਿਆ।',
'emailnotauthenticated' => 'ਤੁਹਾਡਾ ਈਮੇਲ ਐਡਰੈੱਸ ਹਾਲੇ ਪਰਮਾਣਿਤ ਨਹੀਂ ਹੈ। ਹੇਠ ਦਿੱਤੇ ਫੀਚਰਾਂ ਲਈ ਕੋਈ ਵੀ ਈਮੇਲ ਨਹੀਂ ਭੇਜੀ ਜਾਵੇਗੀ।',
'noemailprefs' => 'ਇਹਨਾਂ ਸਹੂਲਤਾਂ ਦੀ ਵਰਤੋਂ ਲਈ ਆਪਣੀਆਂ ਪਸੰਦਾਂ ਵਿਚ ਇਕ ਈ-ਮੇਲ ਪਤਾ ਦਿਓ।',
'emailconfirmlink' => 'ਆਪਣੇ ਈਮੇਲ ਐਡਰੈਸ ਦੀ ਪੁਸ਼ਟੀ ਕਰੋ',
'invalidemailaddress' => 'ਈ-ਮੇਲ ਪਤਾ ਕਬੂਲ ਨਹੀਂ ਕੀਤਾ ਜਾ ਸਕਦਾ ਹੈ ਕਿਉਂਕਿ ਇਹ ਸਹੀ ਅੰਦਾਜ਼ ਵਿਚ ਲਿਖਿਆ ਨਹੀਂ ਜਾਪਦਾ ਹੈ।
ਸਹੀ ਅੰਦਾਜ਼ ਵਿਚ ਦਿਓ ਜਾਂ ਇਹ ਖ਼ਾਨਾ ਖ਼ਾਲੀ ਛੱਡ ਦਿਓ।',
'cannotchangeemail' => 'ਇਸ ਵਿਕੀ ਤੇ ਈ-ਮੇਲ ਪਤੇ ਬਦਲੇ ਨਹੀਂ ਜਾ ਸਕਦੇ।',
'emaildisabled' => 'ਇਹ ਸਾਈਟ ਈ-ਮੇਲਾਂ ਨਹੀਂ ਭੇਜ ਸਕਦੀ।',
'accountcreated' => 'ਖਾਤਾ ਬਣਾਇਆ',
'accountcreatedtext' => '$1 ਲਈ ਯੂਜ਼ਰ ਖਾਤਾ ਬਣਾਇਆ ਗਿਆ।',
'createaccount-title' => '{{SITENAME}} ਲਈ ਅਕਾਊਂਟ ਬਣਾਉਣਾ',
'createaccount-text' => 'ਕਿਸੇ ਨੇ "$2" ਮੈਂਬਰ-ਨਾਮ ਅਤੇ "$3" ਪਾਸਵਰਡ ਨਾਲ਼ {{SITENAME}} ($4) ਤੇ, ਤੁਹਾਡਾ ਈ-ਮੇਲ ਪਤਾ ਵਰਤਦੇ ਹੋਏ, ਖਾਤਾ ਬਣਾਇਆ ਹੈ।
ਤੁਹਾਨੂੰ ਹੁਣੇ ਲਾਗਇਨ ਕਰਕੇ ਆਪਣਾ ਪਾਸਵਰਡ ਬਦਲਣਾ ਚਾਹੀਦਾ ਹੈ।

ਜੇ ਇਹ ਖਾਤਾ ਗ਼ਲਤੀ ਨਾਲ਼ ਬਣ ਗਿਆ ਹੈ ਤਾਂ ਤੁਸੀਂ ਇਸ ਸੁਨੇਹੇ ਨੂੰ ਨਜ਼ਰਅੰਦਾਜ਼ ਕਰ ਸਕਦੇ ਹੋ।',
'usernamehasherror' => 'ਮੈਂਬਰ-ਨਾਮ ਵਿਚ ਹੈਸ਼ ਅੱਖਰ ਨਹੀਂ ਹੋ ਸਕਦੇ',
'login-throttled' => 'ਤੁਸੀਂ ਬਹੁਤ ਸਾਰੀਆਂ ਤਾਜ਼ਾ ਲਾਗਇਨ ਕੋਸ਼ਿਸ਼ਾਂ ਕੀਤੀਆਂ ਹਨ।
ਮਿਹਰਬਾਨੀ ਕਰਕੇ ਦੁਬਾਰਾ ਕੋਸ਼ਿਸ਼ ਕਰਨ ਤੋਂ ਪਹਿਲਾਂ ਥੋੜੀ ਉਡੀਕ ਕਰੋ।',
'login-abort-generic' => 'ਤੁਹਾਡੀ ਲਾਗਇਨ ਨਾਕਾਮ ਸੀ - ਅਧੂਰਾ ਛੱਡਿਆ',
'loginlanguagelabel' => 'ਭਾਸ਼ਾ: $1',

# Email sending
'user-mail-no-addy' => 'ਬਿਨਾਂ ਈ-ਮੇਲ ਪਤਾ ਦਿੱਤੇ ਈ-ਮੇਲ ਭੇਜਣ ਦੀ ਕੋਸ਼ਿਸ਼ ਕੀਤੀ।',

# Change password dialog
'resetpass' => 'ਪਾਸਵਰਡ ਬਦਲੋ',
'resetpass_announce' => 'ਤੁਸੀਂ ਇੱਕ ਆਰਜ਼ੀ ਈ-ਮੇਲ ਕੀਤੇ ਕੋਡ ਨਾਲ ਲਾਗਇਨ ਕੀਤਾ ਹੈ। ਲਾਗਇਨ ਪੂਰਾ ਕਰਨ ਲਈ, ਤੁਹਾਨੂੰ ਇੱਥੇ ਨਵਾਂ ਪਾਸਵਰਡ ਦੇਣਾ ਪਵੇਗਾ:',
'resetpass_header' => 'ਅਕਾਊਂਟ ਪਾਸਵਰਡ ਬਦਲੋ',
'oldpassword' => 'ਪੁਰਾਣਾ ਪਾਸਵਰਡ:',
'newpassword' => 'ਨਵਾਂ ਪਾਸਵਰਡ:',
'retypenew' => 'ਨਵਾਂ ਪਾਸਵਰਡ ਮੁੜ-ਲਿਖੋ:',
'resetpass_submit' => 'ਪਾਸਵਰਡ ਸੈੱਟ ਕਰੋ ਅਤੇ ਲਾਗਇਨ ਕਰੋ',
'resetpass_success' => 'ਤੁਹਾਡਾ ਪਾਸਵਰਡ ਠੀਕ ਤਰਾਂ ਬਦਲਿਆ ਗਿਆ ਹੈ! ਹੁਣ ਤੁਸੀਂ ਲਾਗਇਨ ਕਰ ਸਕਦੇ ਹੋ...',
'resetpass_forbidden' => 'ਪਾਸਵਰਡ ਬਦਲਿਆ ਨਹੀਂ ਜਾ ਸਕਦਾ',
'resetpass-no-info' => 'ਇਸ ਸਫ਼ੇ ਨੂੰ ਸਿੱਧੇ ਹੀ ਵੇਖਣ ਲਈ ਤੁਹਾਨੂੰ ਲਾਗਇਨ ਕਰਨਾ ਪਵੇਗਾ।',
'resetpass-submit-loggedin' => 'ਪਾਸਵਰਡ ਬਦਲੋ',
'resetpass-submit-cancel' => 'ਰੱਦ ਕਰੋ',
'resetpass-wrong-oldpass' => 'ਗ਼ਲਤ ਆਰਜ਼ੀ ਜਾਂ ਚਾਲੂ ਪਾਸਵਰਡ।
ਸ਼ਾਇਦ ਤੁਸੀਂ ਕਾਮਯਾਬੀ ਨਾਲ਼ ਆਪਣਾ ਪਾਸਵਰਡ ਬਦਲ ਚੁੱਕੇ ਹੋ ਜਾਂ ਆਰਜ਼ੀ ਪਾਸਵਰਡ ਲਈ ਬੇਨਤੀ ਕੀਤੀ ਸੀ।',
'resetpass-temp-password' => 'ਆਰਜ਼ੀ ਪਾਸਵਰਡ:',

# Special:PasswordReset
'passwordreset' => 'ਪਾਸਵਰਡ ਮੁੜ-ਸੈੱਟ ਕਰੋ',
'passwordreset-legend' => 'ਪਾਸਵਰਡ ਮੁੜ-ਸੈੱਟ ਕਰੋ',
'passwordreset-disabled' => 'ਇਸ ਵਿਕੀ ਤੇ ਪਾਸਵਰਡ ਰੀਸੈੱਟ ਬੰਦ ਕੀਤੇ ਗਏ ਹਨ।',
'passwordreset-emaildisabled' => 'ਇਹ ਵਿਕਿ ਉੱਤੇ ਈਮੇਲ ਫੀਚਰ ਬੰਦ ਕੀਤਾ ਹੋਇਆ ਹੈ।',
'passwordreset-username' => 'ਯੂਜ਼ਰ-ਨਾਂ:',
'passwordreset-domain' => 'ਡੋਮੇਨ:',
'passwordreset-email' => 'ਈ-ਮੇਲ ਸਿਰਨਾਵਾਂ:',
'passwordreset-emailtitle' => '{{SITENAME}} ਤੇ ਖਾਤੇ ਦੀ ਜਾਣਕਾਰੀ',
'passwordreset-emailtext-ip' => 'ਕਿਸੇ ਨੇ (ਸ਼ਾਇਦ ਤੁਸੀਂ, IP ਪਤਾ $1 ਤੋਂ) {{SITENAME}}
($4) ਲਈ ਖਾਤਾ ਤਫ਼ਸੀਲ ਯਾਦ-ਦਹਾਨੀ ਦੀ ਬੇਨਤੀ ਕੀਤੀ ਹੈ। ਇਹ {{PLURAL:
$3|ਖਾਤਾ ਇਸ ਈ-ਮੇਲ ਪਤੇ ਨਾਲ਼ ਜੁੜਿਆ ਹੈ|ਖਾਤੇ ਇਸ ਈ-ਮੇਲ ਪਤੇ ਨਾਲ਼ ਜੁੜੇ ਹਨ}}:
$2

ਇਹ ਆਰਜ਼ੀ ਪਾਸਵਰਡ
{{PLURAL:$5|ਇੱਕ ਦਿਨ|$5 ਦਿਨਾਂ}} ਵਿਚ ਖ਼ਤਮ ਹੋ {{PLURAL:$3|ਜਾਵੇਗਾ|ਜਾਣਗੇ}}।
ਤੁਹਾਨੂੰ ਹੁਣੇ ਲਾਗਇਨ ਕਰਕੇ ਨਵਾਂ ਪਾਸਵਰਡ ਬਣਾਉਣਾ ਚਾਹੀਦਾ ਹੈ। ਜੇ ਕਿਸੇ ਹੋਰ ਨੇ ਇਹ ਬੇਨਤੀ ਕੀਤੀ ਸੀ ਜਾਂ ਜੇ ਤੁਹਾਨੂੰ ਆਪਣਾ ਪਾਸਵਰਡ ਯਾਦ ਹੈ ਅਤੇ ਤੁਸੀਂ ਇਸਨੂੰ ਬਦਲਣਾ ਨਹੀਂ ਚਾਹੁੰਦੇ ਤਾਂ ਤੁਸੀਂ ਇਸ ਸੁਨੇਹੇ ਨੂੰ ਨਜ਼ਰਅੰਦਾਜ਼ ਕਰ ਕੇ ਆਪਣਾ ਪੁਰਾਣਾ ਪਾਸਵਰਡ ਵਰਤਣਾ ਜਾਰੀ ਰੱਖ ਸਕਦੇ ਹੋ।',
'passwordreset-emailelement' => 'ਯੂਜ਼ਰ-ਨਾਂ: $1
ਆਰਜ਼ੀ ਪਾਸਵਰਡ: $2',
'passwordreset-emailsent' => 'ਇੱਕ ਪਾਸਵਰਡ ਮੁੜ-ਸੈੱਟ ਈ-ਮੇਲ ਭੇਜੀ ਜਾ ਚੁੱਕੀ ਹੈ।',
'passwordreset-emailsent-capture' => 'ਇੱਕ ਯਾਦ-ਦਹਾਨੀ ਈ-ਮੇਲ, ਜਿਹੜੀ ਕਿ ਹੇਠਾਂ ਦਿੱਸ ਰਹੀ ਹੈ, ਭੇਜੀ ਜਾ ਚੁੱਕੀ ਹੈ।',

# Special:ChangeEmail
'changeemail' => 'ਈ-ਮੇਲ ਸਿਰਨਾਵਾਂ ਬਦਲੋ',
'changeemail-header' => 'ਖਾਤੇ ਵਾਲਾ ਈ-ਮੇਲ ਸਿਰਨਾਵਾਂ ਬਦਲੋ',
'changeemail-text' => 'ਆਪਣਾ ਈ-ਮੇਲ ਪਤਾ ਬਦਲਣ ਲਈ ਇਹ ਫ਼ਾਰਮ ਮੁਕੰਮਲ ਕਰੋ। ਇਸ ਤਬਦੀਲੀ ਨੂੰ ਤਸਦੀਕ ਕਰਨ ਲਈ ਤੁਹਾਨੂੰ ਆਪਣਾ ਪਾਸਵਰਡ ਦਾਖ਼ਲ ਕਰਨਾ ਪਵੇਗਾ।',
'changeemail-no-info' => 'ਇਸ ਸਫ਼ੇ ਨੂੰ ਸਿੱਧੇ ਹੀ ਵੇਖਣ ਲਈ ਤੁਹਾਨੂੰ ਲਾਗਇਨ ਕਰਨਾ ਪਵੇਗਾ।',
'changeemail-oldemail' => 'ਮੌਜੂਦਾ ਈਮੇਲ ਸਿਰਨਾਵਾਂ:',
'changeemail-newemail' => 'ਨਵਾਂ ਈ-ਮੇਲ ਸਿਰਨਾਵਾਂ:',
'changeemail-none' => '(ਕੋਈ ਨਹੀਂ)',
'changeemail-password' => 'ਤੁਹਾਡਾ {{SITENAME}} ਪਾਸਵਰਡ:',
'changeemail-submit' => 'ਈ-ਮੇਲ ਬਦਲੋ',
'changeemail-cancel' => 'ਰੱਦ ਕਰੋ',

# Edit page toolbar
'bold_sample' => 'ਗੂੜ੍ਹੇ ਅੱਖਰ',
'bold_tip' => 'ਗੂੜ੍ਹੇ ਅੱਖਰ',
'italic_sample' => 'ਟੇਡੇ ਅੱਖਰ',
'italic_tip' => 'ਟੇਢੀ ਅੱਖਰ',
'link_sample' => 'ਲਿੰਕ ਨਾਂ',
'link_tip' => 'ਅੰਦਰੂਨੀ ਲਿੰਕ',
'extlink_sample' => 'http://www.example.com ਲਿੰਕ ਨਾਂ',
'extlink_tip' => 'ਬਾਹਰੀ ਲਿੰਕ (ਅਗੇਤਰ http:// ਯਾਦ ਰੱਖੋ)',
'headline_sample' => 'ਸਿਰਲੇਖ ਸ਼ਬਦ',
'headline_tip' => 'ਦੂਜੇ ਦਰਜੇ ਦਾ ਸਿਰਲੇਖ',
'nowiki_sample' => 'ਅਸੰਗਠਿਤ ਪਾਠ (NON -FORMATTED) ਇੱਥੇ ਰਖੋ।',
'nowiki_tip' => 'ਵਿਕੀ ਫਾਰਮੈਟਿੰਗ ਨਜ਼ਰਅੰਦਾਜ਼ ਕਰੋ',
'image_tip' => 'ਇੰਬੈੱਡ ਫਾਇਲ',
'media_tip' => 'ਫਾਇਲ ਲਿੰਕ',
'sig_tip' => 'ਤੁਹਾਡੇ ਦਸਤਖਤ ਸਮੇਂ ਸਮੇਤ',
'hr_tip' => 'ਲੇਟਵੀਂ ਲਾਈਨ (use sparingly)',

# Edit pages
'summary' => 'ਸਾਰ:',
'subject' => 'ਵਿਸ਼ਾ/ਹੈੱਡਲਾਈਨ:',
'minoredit' => 'ਇਹ ਇੱਕ ਛੋਟੀ ਸੋਧ ਹੈ',
'watchthis' => 'ਇਸ ਸਫ਼ੇ ਤੇ ਨਜ਼ਰ ਰੱਖੋ',
'savearticle' => 'ਸਫ਼ਾ ਸੰਭਾਲੋ',
'preview' => 'ਝਲਕ',
'showpreview' => 'ਝਲਕ ਵੇਖਾਓ',
'showlivepreview' => 'ਲਾਈਵ ਝਲਕ',
'showdiff' => 'ਬਦਲਾਅ ਵੇਖੋ',
'anoneditwarning' => "'''ਚੇਤਾਵਨੀ:''' ਤੁਸੀਂ ਲਾਗਇਨ ਨਹੀਂ ਕੀਤਾ ਹੈ। ਤੁਹਾਡਾ IP ਐਡਰੈੱਸ ਇਸ ਸਫ਼ੇ ਦੇ ਅਤੀਤ ਵਿੱਚ ਰਿਕਾਰਡ ਕੀਤਾ ਜਾਵੇਗਾ।",
'anonpreviewwarning' => "''ਤੁਸੀਂ ਲਾਗਇਨ ਨਹੀਂ ਕੀਤਾ। ਤਬਦੀਲੀ ਸਾਂਭਣ ਨਾਲ਼ ਤੁਹਾਡਾ IP ਪਤਾ ਸਫ਼ੇ ਦੇ ਸੋਧ ਅਤੀਤ ਵਿਚ ਰਿਕਾਰਡ ਹੋ ਜਾਵੇਗਾ।''",
'missingsummary' => "'''ਯਾਦ-ਦਹਾਨੀ:''' ਤੁਸੀਂ ਸੋਧ ਸਾਰ ਮੁਹੱਈਆ ਨਹੀਂ ਕਰਵਾਇਆ। ਜੇ ਤੁਸੀਂ \"{{int:savearticle}}\" ਤੇ ਦੁਬਾਰਾ ਕਲਿੱਕ ਕੀਤਾ ਤਾਂ ਤੁਹਾਡਾ ਸਫ਼ਾ ਇਸਦੇ ਬਿਨਾਂ ਹੀ ਸਾਂਭਿਆ ਜਾਵੇਗਾ।",
'missingcommenttext' => 'ਹੇਠਾਂ ਇੱਕ ਟਿੱਪਣੀ ਦਿਓ।',
'summary-preview' => 'ਸੋਧ ਸਾਰ ਦੀ ਝਲਕ:',
'subject-preview' => 'ਵਿਸ਼ਾ/ਹੈੱਡਲਾਈਨ ਝਲਕ:',
'blockedtitle' => 'ਯੂਜ਼ਰ ਉੱਤੇ ਪਾਬੰਦੀ ਲਗਾਈ',
'blockedtext' => "'''ਤੁਹਾਡੇ ਮੌਮਬਰ ਨਾਮ ਜਾਂ IP ਪਤੇ ’ਤੇ ਪਾਬੰਦੀ ਲੱਗ ਚੁੱਕੀ ਹੈ।'''

ਪਾਬੰਦੀ $1 ਨੇ ਲਾਈ ਹੈ।
ਦਿੱਤਾ ਗਿਆ ਕਾਰਨ ਇਹ ਹੈ, ''$2''।

* ਪਾਬੰਦੀ ਸ਼ੁਰੂ: $8
* ਪਾਬੰਦੀ ਖਤਮ: $6
* ਪਾਬੰਦੀ ਲਾਉਣ ਵਾਲੇ ਦਾ ਇਰਾਦਾ: $7

ਪਾਬੰਦੀ ਬਾਰੇ ਚਰਚਾ ਕਰਨ ਲਈ ਤੁਸੀਂ $1 ਜਾਂ ਕਿਸੇ ਹੋਰ
[[{{MediaWiki:Grouppage-
sysop}}|administrator]] ਨਾਲ ਰਾਬਤਾ ਕਰ ਸਕਦੇ ਹੋ।
ਤੁਸੀਂ 'ਇਸ ਮੈਂਬਰ ਨੂੰ ਈ-ਮੇਲ ਭੇਜੋ' ਸਹੂਲਤ ਦੀ ਵਰਤੋਂ ਨਹੀਂ ਕਰ ਸਕਦੇ ਜੇ ਤੁਹਾਡੀਆਂ [[Special:Preferences|ਖਾਤਾ ਪਸੰਦਾਂ]] ਵਿੱਚ ਇੱਕ ਸਹੀ ਈ-ਮੇਲ ਪਤਾ ਨਹੀਂ ਦਿੱਤਾ ਗਿਆ ਜਾਂ ਜੇ ਤੁਹਾਡੇ ਇਸਨੂੰ ਵਰਤਣ ਤੇ ਪਾਬੰਦੀ ਹੈ।
ਤੁਹਾਡਾ ਚਾਲੂ IP ਪਤਾ $3 ਹੈ,
ਅਤੇ ਪਾਬੰਦੀ ਪਤਾ #$5 ਹੈ।
ਮਿਹਰਬਾਨੀ ਕਰਕੇ ਆਪਣੇ ਕਿਸੇ ਵੀ ਸਵਾਲ ਜਾਂ ਪੁੱਛ-ਗਿੱਛ ਵਿਚ ਇਹ ਉੱਪਰਲੀ ਤਫ਼ਸੀਲ ਜ਼ਰੂਰ ਸ਼ਾਮਲ ਕਰੋ।",
'blockednoreason' => 'ਕੋਈ ਕਾਰਨ ਨਹੀਂ ਦੱਸਿਆ ਗਿਆ',
'whitelistedittext' => 'ਪੇਜ ਸੋਧਣ ਲਈ ਤੁਹਾਨੂੰ $1 ਕਰਨਾ ਪਵੇਗਾ।',
'confirmedittext' => 'ਸਫ਼ਿਆਂ ਨੂੰ ਸੋਧਣ ਤੋਂ ਪਹਿਲਾਂ ਤੁਹਾਨੂੰ ਆਪਣਾ ਈ-ਮੇਲ ਪਤਾ ਤਸਦੀਕ ਕਰਨਾ ਪਵੇਗਾ।
ਮਿਹਰਬਾਨੀ ਕਰਕੇ ਆਪਣੀਆਂ [[Special:Preferences|ਖਾਤਾ ਪਸੰਦਾ]] ਜ਼ਰੀਏ ਸਹੀ ਈ-ਮੇਲ ਪਤਾ ਦਿਓ ਅਤੇ ਤਸਦੀਕ ਕਰੋ।',
'nosuchsectiontitle' => 'ਸੈਕਸ਼ਨ ਲੱਭਿਆ ਨਹੀਂ ਜਾ ਸਕਦਾ',
'nosuchsectiontext' => 'ਤੁਸੀਂ ਨਾ-ਮੌਜੂਦ ਸੈਕਸ਼ਨ ਨੂੰ ਸੋਧਣ ਦੀ ਕੋਸ਼ਿਸ਼ ਕੀਤੀ ਹੈ।
ਸ਼ਾਇਦ ਤੁਹਾਡੇ ਸਫ਼ੇ ਨੂੰ ਵੇਖਣ ਦੇ ਦੌਰਾਨ ਇਹ ਮਿਟਾਇਆ ਜਾਂ ਇਸਦਾ ਸਿਰਲੇਖ ਬਦਲਿਆ ਜਾ ਚੁੱਕਾ ਹੈ।',
'loginreqtitle' => 'ਲਾਗਇਨ ਚਾਹੀਦਾ ਹੈ',
'loginreqlink' => 'ਲਾਗਇਨ',
'loginreqpagetext' => 'ਹੋਰ ਪੇਜ ਵੇਖਣ ਲਈ ਤੁਹਾਨੂੰ $1 ਕਰਨਾ ਪਵੇਗਾ।',
'accmailtitle' => 'ਪਾਸਵਰਡ ਭੇਜਿਆ।',
'accmailtext' => "[[User talk:$1|$1]] ਲਈ ਰਲ਼ਵੇਂ ਤੌਰ ’ਤੇ ਬਣਿਆ ਪਾਸਵਰਡ $2 ਨੂੰ ਭੇਜਿਆ ਜਾ ਚੁੱਕਾ ਹੈ।
ਇਸ ਨਵੇਂ ਖਾਤੇ ਲਈ ਲਾਗਇਨ ਕਰਨ ਤੋਂ ਬਾਅਦ ''[[Special:ChangePassword|ਪਾਸਵਰਡ ਬਦਲੋ]]'' ’ਤੇ ਜਾ ਕੇ ਪਾਸਵਰਡ ਬਦਲਿਆ ਜਾ ਸਕਦਾ ਹੈ।",
'newarticle' => '(ਨਵਾਂ)',
'newarticletext' => "ਤੁਸੀਂ ਕਿਸੇ ਐਸੇ ਪੰਨੇ ਦੇ ਕੜੀ ’ਤੇ ਹੋ ਜੋ ਹਾਲੇ ਬਣਾਇਆ ਨਹੀਂ ਗਿਆ।
ਸਫ਼ਾ ਬਣਾਉਣ ਲਈ ਹੇਠ ਦਿੱਤੇ ਖਾਨੇ ਵਿੱਚ ਲਿਖਣਾ ਸ਼ੁਰੂ ਕਰੋ। (ਹੋਰ ਮਦਦ ਲਈ [[{{MediaWiki:Helppage}}|ਮਦਦ ਪੰਨਾ]] ਵੇਖੋ।)
ਜੇ ਤੁਸੀਂ ਗਲਤੀ ਨਾਲ ਇੱਥੇ ਆਏ ਹੋ ਤਾਂ ਆਪਣੇ ਬ੍ਰਾਊਜ਼ਰ ਦੇ '''ਪਿੱਛੇ''' ਬਟਨ ’ਤੇ ਕਲਿੱਕ ਕਰੋ।",
'anontalkpagetext' => "----''ਇਹ ਇਕ ਗੁਮਨਾਮ ਮੈਂਬਰ ਲਈ ਇਕ ਚਰਚਾ ਸਫ਼ਾ ਹੈ ਜਿਸਨੇ ਹਾਲੇ ਖਾਤਾ ਨਹੀ ਬਣਾਇਆ ਜਾਂ ਉਸਨੂੰ ਵਰਤ ਨਹੀਂ ਰਿਹਾ।
ਇਸ ਵਾਸਤੇ ਸਾਡੇ ਕੋਲ ਉਸਨੂੰ ਪਛਾਨਣ ਲਈ IP ਪਤਾ ਹੈ।
ਇਕ IP ਪਤਾ ਕਈ ਵਰਤਣ ਵਾਲ਼ਿਆਂ ਦੁਆਰਾ ਸਾਂਝਾ ਕੀਤਾ ਜਾ ਸਕਦਾ ਹੈ।
ਜੇ ਤੁਸੀਂ ਇੱਕ ਗੁਮਨਾਮ ਮੈਂਬਰ ਹੋ ਅਤੇ ਸਮਝਦੇ ਹੋ ਕਿ ਇਹ ਟਿੱਪਣੀਆਂ ਤੁਹਾਡੇ ਲਈ ਹਨ ਤਾਂ ਮਿਹਰਬਾਨੀ ਕਰਕੇ ਹੋਰਾਂ ਗੁਮਨਾਮ ਮੈਂਬਰਾਂ ਨਾਲ਼ ਪੈਦਾ ਹੋਣ ਵਾਲ਼ੀ ਉਲਝਣ ਤੋਂ ਬਚਣ ਲਈ [[Special:UserLogin/signup|ਖਾਤਾ ਬਣਾਓ]] ਜਾਂ [[Special:UserLogin|ਲਾਗਇਨ ਕਰੋ]]।''",
'noarticletext' => 'ਫ਼ਿਲਹਾਲ ਇਸ ਪੰਨੇ ’ਤੇ ਕੋਈ ਲਿਖਤ ਨਹੀਂ ਹੈ। ਤੁਸੀਂ ਦੂਜੇ ਪੰਨਿਆਂ ’ਤੇ [[Special:Search/{{PAGENAME}}|ਇਸ ਸਿਰਲੇਖ ਦੀ ਖੋਜ]] ਕਰ ਸਕਦੇ ਹੋ, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} ਸਬੰਧਿਤ ਚਿੱਠੇ ਖੋਜ] ਸਕਦੇ ਹੋ ਜਾਂ ਇਸ [{{fullurl:{{FULLPAGENAME}}|action=edit}} ਪੰਨੇ ਵਿੱਚ ਲਿਖ] ਸਕਦੇ ਹੋ</span>।',
'noarticletext-nopermission' => 'ਫ਼ਿਲਹਾਲ ਇਸ ਪੰਨੇ ’ਤੇ ਕੋਈ ਲਿਖਤ ਨਹੀਂ ਹੈ। ਤੁਸੀਂ ਦੂਸਰੇ ਪੰਨਿਆਂ ’ਤੇ [[Special:Search/{{PAGENAME}}|ਇਸ ਸਿਰਲੇਖ ਦੀ ਖੋਜ]] ਕਰ ਸਕਦੇ ਹੋ, ਸਬੰਧਤ <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} ਚਿੱਠੇ] ਖੋਜ ਸਕਦੇ ਹੋ ਜਾਂ [{{fullurl:{{FULLPAGENAME}}|action=edit}} ਇਸ ਪੰਨੇ ਵਿੱਚ ਲਿਖ] ਸਕਦੇ ਹੋ।</span>',
'userpage-userdoesnotexist' => 'ਮੈਂਬਰ ਖਾਤਾ "$1" ਰਜਿਸਟਰ ਨਹੀਂ ਹੈ।
ਜੇ ਤੁਸੀਂ ਇਸਨੂੰ ਬਣਾਉਣਾ/ਸੋਧਣਾ ਚਾਹੁੰਦੇ ਹੋ ਤਾਂ ਮਿਰਬਾਨੀ ਕਰਕੇ ਜਾਂਚ ਕਰ ਲਓ।',
'userpage-userdoesnotexist-view' => 'ਯੂਜ਼ਰ ਖਾਤਾ "$1" ਰਜਿਸਟਰ ਨਹੀਂ ਹੈ।',
'blocked-notice-logextract' => 'ਇਹ ਮੈਂਬਰ ਇਸ ਵੇਲ਼ੇ ਪਾਬੰਦੀਸ਼ੁਦਾ ਹੈ।
ਹਵਾਲੇ ਲਈ ਪਾਬੰਦੀ ਚਿੱਠੇ ਦਾ ਨਵਾਂ ਦਾਖ਼ਲਾ ਹੇਠ ਦਿੱਤਾ ਗਿਆ ਹੈ:',
'usercssyoucanpreview' => "'''ਟੋਟਕਾ:''' ਆਪਣੇ ਨਵੇਂ CSS ਸਫ਼ੇ ਨੂੰ ਸਾਂਭਣ ਤੋਂ ਪਹਿਲਾਂ ਪਰਖ ਕਰਨ ਲਈ \"{{int:showpreview}}\" ਬਟਨ ਵਰਤੋ।",
'userjsyoucanpreview' => "'''ਟੋਟਕਾ:''' ਆਪਣੀ ਜਾਵਾਸਕ੍ਰਿਪਟ ਨੂੰ ਸਾਂਭਣ ਤੋਂ ਪਹਿਲਾਂ ਪਰਖ ਕਰਨ ਲਈ \"{{int:showpreview}}\" ਬਟਨ ਵਰਤੋ।",
'usercsspreview' => "'''ਯਾਦ ਰੱਖੋ ਤੁਸੀਂ ਆਪਣੀ ਮੈਂਬਰ CSS ਦੀ ਸਿਰਫ਼ ਇਕ ਝਲਕ ਵੇਖ ਰਹੇ ਹੋ।'''
'''ਇਹ ਹਾਲੇ ਸਾਂਭੀ ਨਹੀਂ ਗਈ ਹੈ!'''",
'userjspreview' => "'''ਯਾਦ ਰੱਖੋ ਤੁਸੀਂ ਆਪਣੀ ਮੈਂਬਰ ਜਾਵਾਸਕ੍ਰਿਪਟ ਦੀ ਸਿਰਫ਼ ਇਕ ਪਰਖ/ਝਲਕ ਵੇਖ ਰਹੇ ਹੋ।'''
'''ਇਹ ਹਾਲੇ ਸਾਂਭੀ ਨਹੀਂ ਗਈ ਹੈ!'''",
'sitecsspreview' => "'''ਯਾਦ ਰੱਖੋ ਤੁਸੀਂ ਇਸ CSS ਦੀ ਸਿਰਫ਼ ਇਕ ਝਲਕ ਵੇਖ ਰਹੇ ਹੋ।'''
'''ਇਹ ਹਾਲੇ ਸਾਂਭੀ ਨਹੀਂ ਗਈ ਹੈ!'''",
'sitejspreview' => "'''ਯਾਦ ਰੱਖੋ ਤੁਸੀਂ ਇਸ ਜਾਵਾਸਕ੍ਰਿਪਟ ਕੋਡ ਦੀ ਸਿਰਫ਼ ਇਕ ਝਲਕ ਵੇਖ ਰਹੇ ਹੋ।'''
'''ਇਹ ਹਾਲੇ ਸਾਂਭੀ ਨਹੀਂ ਗਈ ਹੈ!'''",
'updated' => '(ਅੱਪਡੇਟ)',
'note' => "'''ਨੋਟ:'''",
'previewnote' => "'''ਯਾਦ ਰੱਖੋ ਇਹ ਸਿਰਫ਼ ਇੱਕ ਝਲਕ ਹੈ।'''
ਤੁਹਾਡੀਆਂ ਤਬਦੀਲੀਆਂ ਹਾਲੇ ਸਾਂਭੀਆਂ ਨਹੀਂ ਗਈਆਂ!",
'continue-editing' => 'ਸੋਧ ਖੇਤਰ ਤੇ ਜਾਓ',
'previewconflict' => 'ਇਹ ਝਲਕ ਲਿਖਤ ਦਾ ਓਹ ਅਕਸ ਪੇਸ਼ ਕਰਦੀ ਹੈ ਜਿਵੇਂ ਓਹ ਤੁਹਾਡੇ ਸਾਂਭੇ ਜਾਣ ਤੋਂ ਬਾਅਦ ਦਿੱਸੇਗਾ।',
'editing' => '$1 ਸੋਧ ਜਾਰੀ',
'creating' => '$1 ਬਣਾਇਆ ਜਾ ਰਿਹਾ ਹੈ',
'editingsection' => '$1 (ਭਾਗ) ਸੋਧ ਜਾਰੀ',
'editingcomment' => '$1 (ਨਵਾਂ ਭਾਗ) ਸੋਧ ਜਾਰੀ',
'editconflict' => 'ਅਪਵਾਦ ਟਿੱਪਣੀ: $1',
'yourtext' => 'ਤੁਹਾਡਾ ਟੈਕਸਟ',
'storedversion' => 'ਸੰਭਾਲਿਆ ਵਰਜਨ',
'yourdiff' => 'ਅੰਤਰ',
'longpageerror' => "'''ਗ਼ਲਤੀ: ਤੁਹਾਡੀ ਪੇਸ਼ ਕੀਤੀ ਲਿਖਤ {{PLURAL:$1|ਇੱਕ ਕਿਲੋਬਾਈਟ|$1 ਕਿਲੋਬਾਈਟ}} ਦੀ ਹੈ ਜੋ ਕਿ {{PLURAL:$2|ਇੱਕ ਕਿਲੋਬਾਈਟ|$2 ਕਿਲੋਬਾਈਟ}} ਦੇ ਵੱਧ ਤੋਂ ਵੱਧ ਅਕਾਰ ਤੋਂ ਜ਼ਿਆਦਾ ਹੈ।'''
ਇਹ ਸਾਂਭੀ ਨਹੀਂ ਜਾ ਸਕਦੀ।",
'readonlywarning' => "'''ਖ਼ਬਰਦਾਰ: ਡੈਟਾਬੇਸ ਰੱਖ-ਰਖਾਵ ਦੇ ਕਰਕੇ ਤਾਲਾ-ਬੱਧ ਹੈ ਇਸ ਕਰਕੇ ਤੁਸੀਂ ਹੁਣੇ ਆਪਣੀ ਤਬਦੀਲੀ ਨਹੀਂ ਸਾਂਭ ਸਕਦੇ।'''
ਸ਼ਾਇਦ ਤੁਸੀਂ ਇਸ ਲਿਖਤ ਨੂੰ ਕੱਟ ਅਤੇ ਪੇਸਟ ਕਰ ਕੇ ਇਕ ਫ਼ਾਈਲ ਵਜੋਂ ਬਾਅਦ ਵਿਚ ਵਰਤਣ ਲਈ ਸਾਂਭਣਾ ਚਾਹੋਗੇ।

ਜਿਹੜੇ ਪ੍ਰਬੰਧਕ ਨੇ ਇਸਨੂੰ ਤਾਲਾ ਲਾਇਆ ਹੈ ਉਸਦਾ ਕਹਿਣਾ ਹੈ ਕਿ: $1",
'protectedpagewarning' => "'''ਖ਼ਬਰਦਾਰ: ਇਹ ਸਫ਼ਾ ਸੁਰੱਖਿਅਤ ਹੈ ਜਿਸ ਕਰਕੇ ਸਿਰਫ਼ ਐਡਮਨਿਸਟ੍ਰੇਟਰ ਹੱਕ ਵਾਲ਼ੇ ਮੈਂਬਰ ਹੀ ਇਸ ਨੂੰ ਸੋਧ ਸਕਦੇ ਹਨ।'''
ਚਿੱਠੇ ਦਾ ਨਵਾਂ ਦਾਖ਼ਲਾ ਹਵਾਲੇ ਲਈ ਹੇਠਾਂ ਦਿੱਤਾ ਗਿਆ ਹੈ:",
'semiprotectedpagewarning' => "'''ਨੋਟ:''' ਇਹ ਸਫ਼ਾ ਸੁਰੱਖਿਅਤ ਹੈ ਤਾਂ ਕਿ ਸਿਰਫ਼ ਰਜਿਸਟਰ ਹੋਏ ਮੈਂਬਰ ਹੀ ਇਸ ਨੂੰ ਸੋਧ ਸਕਣ।
ਚਿੱਠੇ ਵਿਚਲਾ ਨਵਾਂ ਦਾਖ਼ਲਾ ਹਵਾਲੇ ਲਈ ਹੇਠਾਂ ਦਿੱਤਾ ਗਿਆ ਹੈ:",
'titleprotectedwarning' => "'''ਖ਼ਬਰਦਾਰ: ਇਹ ਸਫ਼ਾ ਸੁਰੱਖਿਅਤ ਹੈ ਸੋ ਇਸਨੂੰ ਬਣਾਉਣ ਲਈ [[Special:ListGroupRights|ਖ਼ਾਸ ਹੱਕਾਂ]] ਦੀ ਲੋੜ ਹੈ।'''
ਚਿੱਠੇ ਦਾ ਨਵਾਂ ਦਾਖ਼ਲਾ ਹਵਾਲੇ ਲਈ ਹੇਠਾਂ ਦਿੱਤਾ ਗਿਆ ਹੈ:",
'templatesused' => 'ਇਸ ਪੰਨੇ ’ਤੇ {{PLURAL:$1|ਵਰਤਿਆ ਸਾਂਚਾ|ਵਰਤੇ ਸਾਂਚੇ}}:',
'templatesusedpreview' => "{{PLURAL:$1|ਟੈਪਲੇਟ|ਟੈਪਲੇਟ}} ਇਹ ਝਲਕ 'ਚ ਵਰਤੇ ਜਾਂਦੇ ਹਨ:",
'templatesusedsection' => 'ਇਹ ਭਾਗ ਵਿੱਚ {{PLURAL:$1|ਸਾਂਚਾ|ਸਾਂਚੇ}} ਵਰਤਿਆ ਜਾਂਦਾ ਹੈ:',
'template-protected' => '(ਸੁਰੱਖਿਅਤ)',
'template-semiprotected' => '(ਨੀਮ-ਸੁਰੱਖਿਅਤ)',
'hiddencategories' => 'ਇਹ ਪੰਨਾ {{PLURAL:$1|੧ ਲੁਕਵੀਂ ਸ਼੍ਰੇਣੀ|
$1 ਲੁਕਵੀਆਂ ਸ਼੍ਰੇਣੀਆਂ}} ਦਾ ਮੈਂਬਰ ਹੈ:',
'nocreatetext' => '{{SITENAME}} ਨੇ ਨਵੇਂ ਸਫ਼ੇ ਬਣਾਉਣ ਤੇ ਰੋਕ ਲਾਈ ਹੋਈ ਹੈ।
ਤੁਸੀਂ ਵਾਪਸ ਜਾ ਕੇ ਮੌਜੂਦਾ ਸਫ਼ੇ ਸੋਧ ਸਕਦੇ ਹੋ ਜਾਂ [[Special:UserLogin|ਲਾਗਇਨ ਜਾਂ ਖਾਤਾ ਬਣਾ]] ਸਕਦੇ ਹੋ।',
'nocreate-loggedin' => 'ਤੁਹਾਨੂੰ ਨਵੇਂ ਸਫ਼ੇ ਬਣਾਉਣ ਦੀ ਇਜਾਜ਼ਤ ਨਹੀਂ ਹੈ।',
'permissionserrors' => 'ਅਧਿਕਾਰ ਗਲਤੀਆਂ',
'permissionserrorstext' => 'ਤੁਹਾਨੂੰ ਇੰਝ ਕਰਨ ਦੇ ਅਧਿਕਾਰ ਨਹੀਂ ਹਨ। ਹੇਠ ਦਿੱਤੇ {{PLURAL:$1|ਕਾਰਨ|ਕਾਰਨ}} ਨੇ:',
'permissionserrorstext-withaction' => 'ਅੱਗੇ ਦਿੱਤੇ {{PLURAL:$1|ਕਾਰਨ|ਕਾਰਨਾਂ}} ਕਰਕੇ ਤੁਹਾਡੇ ਕੋਲ $2 ਲਈ ਹੱਕ ਨਹੀਂ ਹਨ:',
'recreate-moveddeleted-warn' => "'''ਚਿਤਾਵਣੀ:
ਤੁਸੀਂ ਐਸਾ ਪੰਨਾ ਬਣਾ ਰਹੇ ਹੋ ਜੋ ਪਹਿਲਾਂ ਹਟਾਇਆ ਜਾ ਚੁੱਕਾ ਹੈ।'''

ਖਿਆਲ ਕਰੋ ਕਿ ਕੀ ਇਸ ਪੰਨੇ ਦਾ ਕਾਇਮ ਰਹਿਣਾ ਠੀਕ ਹੈ।
ਇਸਨੂੰ ਮਿਟਾਉਣ ਜਾਂ ਸਿਰਲੇਖ ਬਦਲੀ ਦਾ ਚਿੱਠਾ ਹੇਠਾਂ ਦਿੱਤਾ ਗਿਆ ਹੈ।",
'moveddeleted-notice' => 'ਇਹ ਪੰਨਾ ਹਟਾ ਦਿੱਤਾ ਗਿਆ ਹੈ।
ਇਸਦੇ ਹਟਾਉਣ ਜਾਂ ਸਿਰਲੇਖ ਬਦਲੀ ਦਾ ਚਿੱਠਾ ਹਵਾਲੇ ਲਈ ਹੇਠ ਦਿੱਤਾ ਗਿਆ ਹੈ।',
'log-fulllog' => 'ਪੂਰਾ ਲਾਗ ਵੇਖਾਓ',
'edit-gone-missing' => 'ਇਹ ਸਫ਼ਾ ਅੱਪਡੇਟ ਨਹੀਂ ਹੋ ਸਕਿਆ।
ਜਾਪਦਾ ਹੈ ਕਿ ਇਹ ਹਟਾਇਆ ਜਾ ਚੁੱਕਾ ਹੈ।',
'edit-no-change' => 'ਤੁਹਾਡੀ ਸੋਧ ਨਜ਼ਰਅੰਦਾਜ਼ ਕਰ ਦਿੱਤੀ ਗਈ ਹੈ ਕਿਉਂਕਿ ਲਿਖਤ ਵਿਚ ਕੋਈ ਤਬਦੀਲੀ ਨਹੀਂ ਕੀਤੀ ਗਈ।',
'edit-already-exists' => 'ਨਵਾਂ ਸਫ਼ਾ ਨਹੀਂ ਬਣਾਇਆ ਜਾ ਸਕਿਆ।
ਇਹ ਪਹਿਲਾਂ ਹੀ ਮੌਜੂਦ ਹੈ।',

# Content models
'content-model-wikitext' => 'ਵਿਕਿਟੈਕਸਟ',
'content-model-text' => 'ਆਮ ਟੈਕਸਟ',
'content-model-javascript' => 'ਜਾਵਾਸਕ੍ਰਿਪਟ',
'content-model-css' => 'ਸੀਐਸਐਸ',

# Parser/template warnings
'post-expand-template-inclusion-warning' => "'''ਖਬਰਦਾਰ:''' ਸਾਂਚਾ ਦਾ ਅਕਾਰ ਬਹੁਤ ਵੱਡਾ ਹੈ। ਕੁਝ ਟੈਂਪਲੇਟ ਸ਼ਾਮਲ ਨਹੀਂ ਹੋਣਗੇ।",
'post-expand-template-inclusion-category' => 'ਓਹ ਪੰਨੇ ਜਿੱਥੇ ਟੈਂਪਲੇਟਾਂ ਦੇ ਸ਼ਾਮਲ ਕਰਨ ਦਾ ਅਕਾਰ ਹੱਦੋਂ ਵਧ ਗਿਆ ਹੈ',
'post-expand-template-argument-warning' => "'''ਚੇਤਾਵਨੀ:'''
ਇਸ ਪੰਨੇ ਤੇ ਘੱਟੋ ਘੱਟ ਇੱਕ ਐਸੀ ਸਾਂਚਾ ਬਹਿਸ ਹੈ ਜਿਸ ਦਾ ਅਕਾਰ ਬਹੁਤ ਵੱਡਾ ਹੈ। ਅਜਿਹੀਆਂ ਬਹਿਸਾਂ ਨੂੰ ਛੱਡ ਦਿੱਤਾ ਗਿਆ ਹੈ।",
'post-expand-template-argument-category' => 'ਐਸੇ ਪੰਨੇ ਜਿਨ੍ਹਾਂ ਵਿੱਚ ਸਾਂਚੇ ਦੇ ਸਁਘਟਕ ਛੁੱਟ ਗਏ ਹਨ ।',
'parser-template-loop-warning' => 'ਸਾਂਚੇ ਦਾ ਲੂਪ ਲੱਭਿਆ: [[$1]]',

# "Undo" feature
'undo-success' => 'ਇਹ ਸੋਧ ਨਕਾਰੀ ਜਾ ਸਕਦੀ ਹੈ।
ਮਿਹਰਬਾਨੀ ਕਰਕੇ ਇਹ ਤਸਦੀਕ ਕਰਨ ਲਈ ਹੇਠਲੀ ਤੁਲਨਾ ਜਾਂਚੋ ਕਿ ਇਹ ਓਹੀ ਹੈ ਜੋ ਤੁਸੀਂ ਕਰਨਾ ਚਾਹੁੰਦੇ ਹੋ ਅਤੇ ਫਿਰ ਸੋਧ ਨਕਾਰਨ ਲਈ ਤਬਦੀਲੀਆਂ ਸਾਂਭ ਦਿਓ।',
'undo-norev' => 'ਸੋਧ ਨਕਾਰੀ ਨਹੀਂ ਜਾ ਸਕਦੀ ਕਿਉਂਕਿ ਇਹ ਮੌਜੂਦ ਨਹੀਂ ਜਾਂ ਮਿਟਾ ਦਿੱਤੀ ਗਈ ਹੈ।',
'undo-summary' => '[[Special:Contributions/$2|$2]] ([[User talk:$2|ਗੱਲ-ਬਾਤ]]) ਦੀ ਸੋਧ $1 ਨਕਾਰੀ',

# Account creation failure
'cantcreateaccounttitle' => 'ਖਾਤਾ ਬਣਾਇਆ ਨਹੀਂ ਜਾ ਸਕਦਾ',
'cantcreateaccount-text' => "[[User:$3|$3]] ਨੇ ਇਸ IP ਪਤੇ ('''$1''') ਤੋਂ ਖਾਤਾ ਬਣਾਉਣ ਤੇ ਪਾਬੰਦੀ ਲਾਈ ਹੈ।

$3 ਨੇ ਕਾਰਨ ਇਹ ਦੱਸਿਆ ਹੈ, ''$2''",

# History pages
'viewpagelogs' => 'ਇਹ ਸਫ਼ੇ ਲਈ ਲਾਗ ਵੇਖੋ',
'nohistory' => 'ਇਸ ਸਫ਼ੇ ਦਾ ਕੋਈ ਸੋਧ ਅਤੀਤ ਨਹੀਂ ਹੈ।',
'currentrev' => 'ਮੌਜੂਦਾ ਰੀਵਿਜ਼ਨ',
'currentrev-asof' => '$1 ਮੁਤਾਬਕ ਸਭ ਤੋਂ ਨਵਾਂ ਰੀਵਿਜਨ',
'revisionasof' => '$1 ਦਾ ਰੀਵਿਜਨ',
'revision-info' => '$2 ਦਾ ਬਣਾਇਆ $1 ਦਾ ਰੀਵਿਜਨ',
'previousrevision' => '←ਪੁਰਾਣਾ ਰੀਵਿਜਨ',
'nextrevision' => 'ਨਵਾਂ ਰੀਵਿਜਨ →',
'currentrevisionlink' => 'ਸਭ ਤੋ ਨਵਾਂ ਰੀਵਿਜਨ',
'cur' => 'ਮੌਜੂਦਾ',
'next' => 'ਅੱਗੇ',
'last' => 'ਪਿੱਛੇ',
'page_first' => 'ਪਹਿਲਾਂ',
'page_last' => 'ਆਖਰੀ',
'histlegend' => "ਫ਼ਰਕ ਵੇਖੋ:
ਮੁਕਾਬਲਾ ਕਰਨ ਲਈ ਰੀਵਿਜਨਾਂ ਦੇ ਰੇਡੀਓ ਬਟਨਾਂ ਵਿੱਚ ਨਿਸ਼ਾਨ ਲਾਓ ਅਤੇ \"ਜਾਓ\" ਜਾਂ ਸਭ ਤੋਂ ਥੱਲੇ ਵਾਲੇ ਬਟਨ ਤੇ ਕਲਿੱਕ ਕਰੋ। <br />
ਲੈਜਅੰਡ:
'''({{int:cur}})''' = ਨਵੇਂ ਰੀਵਿਜਨ ਨਾਲੋਂ ਫ਼ਰਕ, '''({{int:last}})''' = ਪਿਛਲੇ ਰੀਵਿਜਨ ਨਾਲੋਂ ਫ਼ਰਕ, '''({{int:minoreditletter}})''' = ਛੋਟੀ ਤਬਦੀਲੀ।",
'history-fieldset-title' => 'ਬਰਾਊਜ਼ਰ ਅਤੀਤ',
'history-show-deleted' => 'ਕੇਵਲ ਹਟਾਏ ਗਏ',
'histfirst' => 'ਸਭ ਤੋਂ ਪਹਿਲਾਂ ਦੇ',
'histlast' => 'ਸਭ ਤੋਂ ਨਵਾਂ',
'historysize' => '({{PLURAL:$1|1 ਬਾਈਟ|$1 ਬਾਈਟ}})',
'historyempty' => '(ਖਾਲੀ)',

# Revision feed
'history-feed-title' => 'ਰੀਵਿਜ਼ਨ ਅਤੀਤ',
'history-feed-description' => 'ਵਿਕੀ ’ਤੇ ਇਸ ਸਫ਼ੇ ਦਾ ਰੀਵਿਜ਼ਨ ਅਤੀਤ',
'history-feed-item-nocomment' => '$1 ਤੋਂ $2 ’ਤੇ',
'history-feed-empty' => 'ਦਰਖ਼ਾਸਤਸ਼ੁਦਾ ਸਫ਼ਾ ਮੌਜੂਦ ਨਹੀਂ ਹੈ।
ਸ਼ਾਇਦ ਇਸਨੂੰ ਵਿਕੀ ਤੋਂ ਮਿਟਾ ਦਿੱਤਾ ਗਿਆ ਹੈ ਜਾਂ ਨਾਮ ਬਦਲ ਦਿੱਤਾ ਗਿਆ ਹੈ।
ਵਿਕੀ ਦੇ ਨਵੇਂ ਮੁਨਾਸਿਬ ਸਫ਼ਿਆਂ ਵਿਚ [[Special:Search|ਲੱਭਣ]] ਦੀ ਕੋਸ਼ਿਸ਼ ਕਰੋ।',

# Revision deletion
'rev-deleted-comment' => '(ਸੋਧ ਸਾਰ ਹਟਾਇਆ)',
'rev-deleted-user' => '(ਯੂਜ਼ਰ ਨਾਂ ਹਟਾਇਆ)',
'rev-deleted-event' => '(ਲਾਗ ਕਾਰਵਾਈ ਹਟਾਈ ਗਈ)',
'rev-deleted-user-contribs' => '[ਮੈਂਬਰ-ਨਾਂ ਜਾਂ IP ਪਤਾ ਹਟਾਇਆ - ਸੋਧ ਯੋਗਦਾਨਾਂ ਵਿਚੋਂ ਓਹਲੇ ਕੀਤੀ]',
'rev-deleted-text-permission' => "ਸਫ਼ੇ ਦੀ ਇਹ ਰੀਵਿਜ਼ਨ '''ਮਿਟਾਈ''' ਜਾ ਚੁੱਕੀ ਹੈ।
ਤਫ਼ਸੀਲ [{{fullurl:{{#Special:Log}}/delete|
page={{FULLPAGENAMEE}}}} ਮਿਟਾਉਣ ਦੇ ਚਿੱਠੇ] ਵਿਚ ਵੇਖੀ ਜਾ ਸਕਦੀ ਹੈ।",
'rev-deleted-text-unhide' => "ਸਫ਼ੇ ਦੀ ਇਹ ਰੀਵਿਜ਼ਨ '''ਮਿਟਾਈ''' ਜਾ ਚੁੱਕੀ ਹੈ।
ਤਫ਼ਸੀਲ [{{fullurl:{{#Special:Log}}/delete|
page={{FULLPAGENAMEE}}}} ਮਿਟਾਉਣ ਦੇ ਚਿੱਠੇ] ਵਿਚ ਵੇਖੀ ਜਾ ਸਕਦੀ ਹੈ।
ਜੇ ਤੁਸੀਂ ਅੱਗੇ ਵਧਣਾ ਚਾਹੋ ਤਾਂ ਹਾਲੇ ਵੀ [$1 ਇਹ ਰੀਵਿਜ਼ਨ ਵੇਖ] ਸਕਦੇ ਹੋ।",
'rev-deleted-no-diff' => "ਤੁਸੀਂ ਇਹ ਫ਼ਰਕ ਨਹੀਂ ਵੇਖ ਸਕਦੇ ਕਿਉਂਕਿ ਇਹਨਾਂ ਵਿੱਚੋਂ ਇੱਕ ਰੀਵਿਜ਼ਨ '''ਮਿਟਾਈ''' ਜਾ ਚੁੱਕੀ ਹੈ।
ਤਫ਼ਸੀਲ [{{fullurl:{{#Special:Log}}/delete|
page={{FULLPAGENAMEE}}}} ਮਿਟਾਉਣ ਦੇ ਚਿੱਠੇ] ਵਿਚ ਵੇਖੀ ਜਾ ਸਕਦੀ ਹੈ।",
'rev-suppressed-no-diff' => "ਤੁਸੀਂ ਇਹ ਫ਼ਰਕ ਨਹੀਂ ਵੇਖ ਸਕਦੇ ਕਿਉਂਕਿ ਇਹਨਾਂ ਵਿੱਚੋਂ ਇੱਕ ਰੀਵਿਜ਼ਨ '''ਮਿਟਾਈ''' ਜਾ ਚੁੱਕੀ ਹੈ।",
'rev-deleted-unhide-diff' => "ਇਸ ਫ਼ਰਕ ਵਿੱਚੋਂ ਇੱਕ ਰੀਵਿਜ਼ਨ '''ਮਿਟਾਈ''' ਜਾ ਚੁੱਕੀ ਹੈ।
ਤਫ਼ਸੀਲ [{{fullurl:{{#Special:Log}}/delete|
page={{FULLPAGENAMEE}}}} ਮਿਟਾਉਣ ਦੇ ਚਿੱਠੇ] ਵਿਚ ਵੇਖੀ ਜਾ ਸਕਦੀ ਹੈ।
ਜੇ ਤੁਸੀਂ ਅੱਗੇ ਵਧਣਾ ਚਾਹੋ ਤਾਂ ਹਾਲੇ ਵੀ [$1 ਇਹ ਰੀਵਿਜ਼ਨ ਵੇਖ] ਸਕਦੇ ਹੋ।",
'rev-suppressed-diff-view' => "ਇਸ ਫ਼ਰਕ ਵਿੱਚੋਂ ਇੱਕ ਰੀਵਿਜ਼ਨ '''ਜ਼ਬਤ''' ਕੀਤੀ ਜਾ ਚੁੱਕੀ ਹੈ।
ਤਫ਼ਸੀਲ [{{fullurl:{{#Special:Log}}/delete|
page={{FULLPAGENAMEE}}}} ਜ਼ਬਤੀ ਦੇ ਚਿੱਠੇ] ਵਿਚ ਵੇਖੀ ਜਾ ਸਕਦੀ ਹੈ।",
'rev-delundel' => 'ਵੇਖਾਓ/ਓਹਲੇ',
'rev-showdeleted' => 'ਵੇਖਾਓ',
'revisiondelete' => 'ਰੀਵਿਜ਼ਨ ਹਟਾਓ/ਹਟਾਇਆ-ਵਾਪਸ',
'revdelete-nooldid-title' => 'ਕੋਈ ਟਾਰਗੇਟ ਰੀਵਿਜ਼ਨ ਨਹੀਂ',
'revdelete-nologtype-title' => 'ਚਿੱਠੇ ਦੀ ਕਿਸਮ ਨਹੀਂ ਦੱਸੀ ਗਈ',
'revdelete-nologtype-text' => 'ਇਹ ਕਾਰਵਾਈ ਕਰਨ ਲਈ ਤੁਸੀਂ ਚਿੱਠੇ ਦੀ ਕਿਸਮ ਨਹੀਂ ਦੱਸੀ।',
'revdelete-no-file' => 'ਦਿੱਤੀ ਗਈ ਫਾਇਲ ਮੌਜੂਦ ਨਹੀਂ ਹੈ।',
'revdelete-show-file-confirm' => 'ਤੁਹਾਨੂੰ ਯਕੀਨ ਹੈ ਤੁਸੀਂ $2 ਨੂੰ $3 ਦੀ ਫ਼ਾਈਲ "<nowiki>$1</nowiki>" ਦੀ ਮਿਟਾਈ ਗਈ ਰੀਵਿਜ਼ਨ ਵੇਖਣਾ ਚਾਹੁੰਦੇ ਹੋ?',
'revdelete-show-file-submit' => 'ਹਾਂ',
'revdelete-selected' => "'''[[:$1]] {{PLURAL:$2|ਦੀ ਚੁਣੀ ਹੋਈ ਰੀਵਿਜ਼ਨ|ਦੀਆਂ ਚੁਣੀਆਂ ਹੋਈਆਂ ਰੀਵਿਜ਼ਨਾਂ}}:'''",
'revdelete-legend' => 'ਵੇਖਣ ਪਾਬੰਦੀਆਂ ਸੈੱਟ ਕਰੋ:',
'revdelete-hide-text' => 'ਰੀਵਿਜ਼ਨ ਟੈਕਸਟ ਓਹਲੇ',
'revdelete-hide-image' => 'ਫਾਇਲ ਸਮੱਗਰੀ ਓਹਲੇ',
'revdelete-hide-name' => 'ਕਾਰਵਾਈ ਅਤੇ ਟਾਰਗੇਟ ਓਹਲੇ',
'revdelete-hide-comment' => 'ਸੋਧ ਸਾਰ ਓਹਲੇ',
'revdelete-hide-user' => 'ਸੋਧਣ ਵਾਲ਼ੇ ਦਾ ਮੈਂਬਰ-ਨਾਂ/IP ਪਤਾ ਲੁਕਾਓ',
'revdelete-radio-same' => '(ਨਾ ਬਦਲੋ)',
'revdelete-radio-set' => 'ਹਾਂ',
'revdelete-radio-unset' => 'ਨਹੀਂ',
'revdelete-unsuppress' => 'ਮੁੜ ਬਹਾਲ ਕੀਤੀਆਂ ਰੀਵਿਜ਼ਨਾਂ ਤੋਂ ਰੋਕਾਂ ਹਟਾਓ',
'revdelete-log' => 'ਕਾਰਨ:',
'revdelete-submit' => 'ਚੁਣੇ ਰੀਵਿਜਨ ਉੱਤੇ ਲਾਗੂ ਕਰੋ',
'logdelete-success' => "'''ਚਿੱਠੇ ਦੀ ਦਿੱਖ ਕਾਮਯਾਬੀ ਨਾਲ਼ ਸੈੱਟ ਕੀਤੀ।'''",
'logdelete-failure' => "'''ਚਿੱਠੇ ਦੀ ਦਿੱਖ ਸੈੱਟ ਨਹੀਂ ਕੀਤੀ ਜਾ ਸਕਦੀ:''' $1",
'revdel-restore' => 'ਦਿੱਖ ਬਦਲੋ',
'revdel-restore-deleted' => 'ਹਟਾਏ ਗਏ ਰੀਵੀਜਨ',
'revdel-restore-visible' => 'ਦਿਸਣਯੋਗ ਰੀਵੀਜਨ',
'pagehist' => 'ਸਫ਼ਾ ਅਤੀਤ',
'deletedhist' => 'ਹਟਾਇਆ ਗਿਆ ਅਤੀਤ',
'revdelete-hide-current' => 'ਤਾਰੀਖ &2, $1 ਦੀ ਚੀਜ਼ ਲੁਕਾਉਣ ਵਿਚ ਗਲਤੀ: ਇਹ ਮੌਜੂਦਾ ਰੀਵਿਜ਼ਨ ਹੈ।
ਇਹ ਲੁਕਾਈ ਨਹੀਂ ਜਾ ਸਕਦੀ।',
'revdelete-otherreason' => 'ਹੋਰ/ਵਾਧੂ ਕਾਰਨ:',
'revdelete-reasonotherlist' => 'ਹੋਰ ਕਾਰਨ',
'revdelete-edit-reasonlist' => 'ਮਿਟਾਏ ਜਾਣ ਦੇ ਕਾਰਨ ਸੋਧੋ',
'revdelete-offender' => 'ਰੀਵਿਜ਼ਨ ਲੇਖਕ:',

# History merging
'mergehistory' => 'ਸਫ਼ਿਆਂ ਦੇ ਅਤੀਤ ਰਲ਼ਾਓ',
'mergehistory-from' => 'ਸਰੋਤ ਸਫ਼ਾ:',
'mergehistory-list' => 'ਰਲ਼ਾਉਣਯੋਗ ਸੋਧ ਅਤੀਤ',
'mergehistory-go' => 'ਰਲ਼ਾਉਣਯੋਗ ਸੋਧਾਂ ਵਖਾਓ',
'mergehistory-submit' => 'ਰੀਵਿਜ਼ਨਾਂ ਰਲ਼ਾਓ',
'mergehistory-empty' => 'ਕੋਈ ਰੀਵਿਜ਼ਨ ਰਲ਼ਾਈ ਨਹੀ ਜਾ ਸਕਦੀ।',
'mergehistory-success' => '[[:$1]] {{PLURAL:|ਦੀ|ਦੀਆਂ}} $3 {{PLURAL:$3|ਰੀਵਿਜ਼ਨ|ਰੀਵਿਜ਼ਨਾਂ}} ਕਾਮਯਾਬੀ ਨਾਲ਼ [[:$2]] ਵਿਚ {{PLURAL:$3|ਰਲ਼ਾਈ|ਰਲ਼ਾਈਆਂ}}।',
'mergehistory-no-source' => 'ਸਰੋਤ ਸਫ਼ਾ $1 ਮੌਜੂਦ ਨਹੀਂ ਹੈ।',
'mergehistory-autocomment' => '[[:$1]] ਨੂੰ [[:$2]] ਵਿੱਚ ਰਲ਼ਾਇਆ',
'mergehistory-comment' => '[[:$1]] ਨੂੰ [[:$2]] ਵਿੱਚ ਰਲ਼ਾਇਆ: $3',
'mergehistory-same-destination' => 'ਸਰੋਤ ਸਫ਼ਾ ਅਤੇ ਮੰਜ਼ਿਲ ਸਫ਼ਾ ਇੱਕੋ ਜਿਹੇ ਨਹੀਂ ਹੋ ਸਕਦੇ',
'mergehistory-reason' => 'ਕਾਰਨ:',

# Merge log
'mergelog' => 'ਰਲ਼ਾਉਣ ਦਾ ਚਿੱਠਾ',
'pagemerge-logentry' => '[[$1]] ਨੂੰ [[$2]] ਵਿੱਚ ਰਲ਼ਾਇਆ ($3 ਤੱਕ ਦੀਆ ਰੀਵਿਜ਼ਨਾਂ)',
'revertmerge' => 'ਅਨ-ਮਰਜ',
'mergelogpagetext' => 'ਹੇਠਾਂ ਇੱਕ ਸਫ਼ੇ ਦੇ ਅਤੀਤ ਨੂੰ ਦੂਜੇ ਦੇ ਅਤੀਤ ਵਿਚ ਰਲ਼ਾਉਣ ਦੀ ਸਭ ਤੋਂ ਤਾਜ਼ਾ ਲਿਸਟ ਹੈ।',

# Diffs
'history-title' => '"$1" ਦੇ ਰੀਵਿਜਨ ਦਾ ਅਤੀਤ',
'difference-title' => '"$1" ਦੇ ਰੀਵਿਜ਼ਨਾਂ ਵਿਚ ਫ਼ਰਕ',
'difference-title-multipage' => 'ਸਫ਼ਿਆਂ "$1" ਅਤੇ "$2" ਵਿਚ ਫ਼ਰਕ',
'difference-multipage' => '(ਦੋ ਸਫ਼ਿਆਂ ਵਿਚਕਾਰ ਫ਼ਰਕ)',
'lineno' => 'ਲਾਈਨ $1:',
'compareselectedversions' => 'ਚੁਣੇ ਵਰਜਨਾਂ ਦੀ ਤੁਲਨਾ',
'showhideselectedversions' => 'ਚੁਣੇ ਰੀਵਿਜ਼ਨ ਵਖਾਓ/ਲੁਕਾਓ',
'editundo' => 'ਵਾਪਸ',
'diff-multi' => '({{PLURAL:$2|ਵਰਤੋਂਕਾਰ ਦੀ|$2 ਵਰਤੋਂਕਾਰਾਂ ਦੀਆਂ}} {{PLURAL:$1|ਵਿਚਕਾਰਲੀ ਰੀਵਿਜਨ ਨਹੀਂ ਦਿਖਾਈ ਜਾ ਰਹੀ|ਵਿਚਕਾਰਲੀਆਂ $1 ਰੀਵਿਜਨਾਂ ਨਹੀਂ ਦਿਖਾਈਆਂ ਜਾ ਰਹੀਆਂ}})',

# Search results
'searchresults' => 'ਖੋਜ ਨਤੀਜੇ',
'searchresults-title' => '"$1" ਲਈ ਖੋਜ ਨਤੀਜੇ',
'searchresulttext' => '{{SITENAME}} ਖੋਜ ਬਾਰੇ ਹੋਰ ਜਾਣਕਾਰੀ ਲਵੋ, ਵੇਖੋ [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle' => 'ਤੁਸੀਂ \'\'\'[[:$1]]\'\'\' ਲਈ ਖੋਜ ਕੀਤੀ ([[Special:Prefixindex/$1|"$1" ਨਾਲ ਸ਼ੁਰੂ ਹੁੰਦੇ ਸਭ ਸਫ਼ੇ]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|"$1" ਨਾਲ ਲਿੰਕ ਹੋਏ ਸਭ ਸਫ਼ੇ]])',
'searchsubtitleinvalid' => "ਤੁਸੀਂ'''$1''' ਲਈ ਖੋਜ ਕੀਤੀ।",
'titlematches' => 'ਆਰਟੀਕਲ ਟੈਕਸਟ ਮਿਲਦਾ',
'notitlematches' => 'ਕੋਈ ਪੇਜ ਟਾਇਟਲ ਨਹੀਂ ਮਿਲਦਾ',
'textmatches' => 'ਸਫ਼ਾ ਟੈਕਸਟ ਮਿਲਦਾ',
'notextmatches' => 'ਕੋਈ ਪੇਜ ਟੈਕਸਟ ਨਹੀਂ ਮਿਲਦਾ',
'prevn' => 'ਪਿੱਛੇ {{PLURAL:$1|$1}}',
'nextn' => 'ਅੱਗੇ {{PLURAL:$1|$1}}',
'prevn-title' => 'ਪਿਛਲੇ $1 {{PLURAL:$1|ਨਤੀਜਾ|ਨਤੀਜੇ}}',
'nextn-title' => 'ਅਗਲੇ $1 {{PLURAL:$1|ਨਤੀਜਾ|ਨਤੀਜੇ}}',
'shown-title' => 'ਪ੍ਰਤੀ ਪੰਨਾ $1 {{PLURAL:$1|ਨਤੀਜਾ|ਨਤੀਜੇ}} ਵਖਾਓ',
'viewprevnext' => 'ਵੇਖੋ ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend' => 'ਖੋਜ ਚੋਣਾਂ',
'searchmenu-exists' => "'''ਇਸ ਵਿਕੀ ’ਤੇ \"[[:\$1]]\" ਨਾਮ ਦਾ ਸਫਾ ਹੈ।'''",
'searchmenu-new' => "'''ਇਸ ਵਿਕੀ ’ਤੇ \"[[:\$1]]\" ਪੰਨਾ ਬਣਾਓ!'''",
'searchmenu-prefix' => '[[Special:PrefixIndex/$1|ਇਸ ਅਗੇਤਰ ਵਾਲ਼ੇ ਸਫ਼ੇ ਵੇਖੋ]]',
'searchprofile-articles' => 'ਸਮੱਗਰੀ ਪੰਨੇ',
'searchprofile-project' => 'ਮੱਦਦ ਤੇ ਪਰੋਜੈਕਟ ਸਫ਼ੇ',
'searchprofile-images' => 'ਮਲਟੀਮੀਡੀਆ',
'searchprofile-everything' => 'ਸਭ ਕੁਝ',
'searchprofile-advanced' => 'ਤਕਨੀਕੀ',
'searchprofile-articles-tooltip' => '$1 ਵਿੱਚ ਖੋਜ',
'searchprofile-project-tooltip' => '$1 ਵਿੱਚ ਖੋਜ',
'searchprofile-images-tooltip' => 'ਫਾਇਲ ਦੀ ਖੋਜ',
'searchprofile-everything-tooltip' => 'ਸਭ ਚੀਜ਼ਾਂ ਖੋਜੋ (ਚਰਚਾ ਪੰਨਿਆਂ ਸਮੇਤ)',
'searchprofile-advanced-tooltip' => 'ਆਪਣੇ ਬਣਾਏ ਨਾਮ-ਥਾਂਵਾਂ ਵਿੱਚ ਖੋਜੋ',
'search-result-size' => '$1 ({{PLURAL:$2|1 ਸ਼ਬਦ|$2 ਸ਼ਬਦ}})',
'search-result-category-size' => '{{PLURAL:$1|1 ਮੈਂਬਰ|$1 ਮੈਂਬਰ}} ({{PLURAL:$2|1 ਉਪਸ਼੍ਰੇਣੀ|$2 ਉਪਸ਼੍ਰੇਣੀਆਂ}}, {{PLURAL:$3|1 ਫ਼ਾਈਲ|$3 ਫ਼ਾਈਲਾਂ}})',
'search-result-score' => 'ਸਹੀ: $1%',
'search-redirect' => '($1 ਰੀਡਿਰੈਕਟ)',
'search-section' => '(ਭਾਗ $1)',
'search-suggest' => 'ਕੀ ਤੁਹਾਡਾ ਮਤਲਬ ਸੀ: $1',
'search-interwiki-caption' => 'ਸਾਥੀ ਪ੍ਰੋਜੈਕਟ',
'search-interwiki-default' => '$1 ਨਤੀਜੇ:',
'search-interwiki-more' => '(ਹੋਰ)',
'search-relatedarticle' => 'ਸਬੰਧਿਤ',
'mwsuggest-disable' => 'AJAX ਸਲਾਹਾਂ ਬੰਦ ਕਰੋ',
'searcheverything-enable' => 'ਸਾਰੇ ਥਾਂ-ਨਾਂਵਾਂ ਵਿਚ ਖੋਜੋ',
'searchrelated' => 'ਸਬੰਧਿਤ',
'searchall' => 'ਸਭ',
'showingresults' => "ਹੇਠਾਂ #'''$2''' ਨਾਲ਼ ਸ਼ੁਰੂ ਹੋਣ ਵਾਲ਼ੇ {{PLURAL:
$1|'''1''' ਨਤੀਜਾ|'''$1''' ਤੱਕ ਨਤੀਜੇ}} ਵਖਾਓ।",
'showingresultsnum' => "ਹੇਠਾਂ #'''$2''' ਨਾਲ਼ ਸ਼ੁਰੂ ਹੋਣ ਵਾਲ਼ੇ {{PLURAL:
$3|'''1''' ਨਤੀਜਾ|'''$3''' ਨਤੀਜੇ}} ਵਖਾਓ।",
'showingresultsheader' => "'''$4''' ਵਾਸਤੇ {{PLURAL:$5|'''$3''' ਵਿੱਚੋਂ '''$1''' ਨਤੀਜੇ|'''$3''' ਵਿੱਚੋਂ '''$1 - $2''' ਨਤੀਜੇ}}",
'search-nonefound' => 'ਤੁਹਾਡੀ ਖੋਜ ਨਾਲ ਮੇਲ ਖਾਂਦੇ ਕੋਈ ਨਤੀਜੇ ਨਹੀਂ ਮਿਲੇ।',
'powersearch' => 'ਤਕਨੀਕੀ ਖੋਜ',
'powersearch-legend' => 'ਤਕਨੀਕੀ ਖੋਜ',
'powersearch-ns' => 'ਨੇਮ-ਸਪੇਸ ਵਿੱਚ ਖੋਜ:',
'powersearch-redir' => 'ਰੀ-ਡਿਰੈਕਟ ਲਿਸਟ',
'powersearch-field' => 'ਇਸ ਲਈ ਖੋਜ',
'powersearch-togglelabel' => 'ਜਾਂਚੋ:',
'powersearch-toggleall' => 'ਸਭ',
'powersearch-togglenone' => 'ਕੋਈ ਨਹੀਂ',
'search-external' => 'ਬਾਹਰੀ ਖੋਜ',

# Preferences page
'preferences' => 'ਮੇਰੀ ਪਸੰਦ',
'mypreferences' => 'ਪਸੰਦ',
'prefs-edits' => 'ਸੋਧਾਂ ਦੀ ਗਿਣਤੀ:',
'prefsnologin' => 'ਲਾਗਇਨ ਨਹੀਂ',
'prefsnologintext' => 'ਵਰਤੋਂਕਾਰ ਪਸੰਦਾਂ ਸੈੱਟ ਕਰਨ ਲਈ ਤੁਹਾਨੂੰ <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} ਲਾਗਇਨ]</span> ਕਰਨਾ ਪਵੇਗਾ।',
'changepassword' => 'ਪਾਸਵਰਡ ਬਦਲੋ',
'prefs-skin' => 'ਸਕਿਨ',
'skin-preview' => 'ਝਲਕ',
'datedefault' => 'ਕੋਈ ਪਸੰਦ ਨਹੀਂ',
'prefs-beta' => 'ਬੀਟਾ ਫੀਚਰ',
'prefs-datetime' => 'ਮਿਤੀ ਅਤੇ ਸਮਾਂ',
'prefs-labs' => 'ਲੈਬ ਫੀਚਰ',
'prefs-user-pages' => 'ਯੂਜ਼ਰ ਸਫ਼ੇ',
'prefs-personal' => 'ਯੂਜ਼ਰ ਪਰੋਫਾਇਲ',
'prefs-rc' => 'ਤਾਜ਼ਾ ਬਦਲਾਅ',
'prefs-watchlist' => 'ਨਿਗਰਾਨ-ਸੂਚੀ',
'prefs-watchlist-days' => 'ਨਿਗਰਾਨੀ-ਲਿਸਟ ਵਿਚ ਦਿਖਾਉਣ ਲਈ ਦਿਨ:',
'prefs-watchlist-days-max' => 'ਵੱਧ ਤੋਂ ਵੱਧ $1 {{PLURAL:$1|ਦਿਨ|ਦਿਨ}}',
'prefs-watchlist-edits' => 'ਵਧਾਈ ਹੋਈ ਨਿਗਰਾਨੀ-ਲਿਸਟ ਵਿਚ ਦਿਖਾਉਣ ਲਈ ਵੱਧ ਤੋਂ ਵੱਧ ਤਬਦੀਲੀਆਂ:',
'prefs-watchlist-edits-max' => 'ਵੱਧ ਤੋਂ ਵੱਧ ਨੰਬਰ: ੧੦੦੦',
'prefs-watchlist-token' => 'ਨਿਗਰਾਨੀ-ਲਿਸਟ ਟੋਕਨ:',
'prefs-misc' => 'ਫੁਟਕਲ',
'prefs-resetpass' => 'ਪਾਸਵਰਡ ਬਦਲੋ',
'prefs-changeemail' => 'ਈ-ਮੇਲ ਪਤਾ ਬਦਲੋ',
'prefs-setemail' => 'ਈ-ਮੇਲ ਪਤਾ ਸੈੱਟ ਕਰੋ',
'prefs-email' => 'ਈਮੇਲ ਚੋਣਾਂ',
'prefs-rendering' => 'ਦਿੱਖ',
'saveprefs' => 'ਸੰਭਾਲੋ',
'resetprefs' => 'ਰੀ-ਸੈੱਟ',
'restoreprefs' => 'ਸਭ ਮੂਲ ਸੈਟਿੰਗ ਮੁੜ-ਸਟੋਰ ਕਰੋ',
'prefs-editing' => 'ਸੋਧ ਜਾਰੀ',
'prefs-edit-boxsize' => 'ਸੋਧ ਖਿੜਕੀ ਦਾ ਅਕਾਰ',
'rows' => 'ਕਤਾਰਾਂ:',
'columns' => 'ਕਾਲਮ:',
'searchresultshead' => 'ਖੋਜ',
'resultsperpage' => 'ਪ੍ਰਤੀ ਪੇਜ ਹਿੱਟ:',
'stub-threshold-disabled' => 'ਬੰਦ ਹੈ',
'recentchangesdays' => 'ਤਾਜ਼ਾ ਤਬਦੀਲੀਆਂ ਵਿਚ ਦਿਖਾਉਣ ਲਈ ਦਿਨ:',
'recentchangesdays-max' => 'ਵੱਧ ਤੋਂ ਵੱਧ $1 {{PLURAL:$1|ਦਿਨ|ਦਿਨ}}',
'prefs-help-recentchangescount' => 'ਇਸ ਵਿਚ ਤਾਜ਼ਾ ਤਬਦੀਲੀਆਂ, ਸਫ਼ਿਆਂ ਦੇ ਅਤੀਤ ਅਤੇ ਚਿੱਠੇ ਸ਼ਾਮਲ ਹਨ।',
'savedprefs' => 'ਤੁਹਾਡੀ ਪਸੰਦ ਸੰਭਾਲੀ ਗਈ ਹੈ।',
'timezonelegend' => 'ਸਮਾਂ ਖੇਤਰ:',
'localtime' => 'ਲੋਕਲ ਸਮਾਂ:',
'timezoneuseserverdefault' => 'ਸਰਵਰ ਡਿਫਾਲਟ ਵਰਤੋਂ',
'servertime' => 'ਸਰਵਰ ਦਾ ਟਾਈਮ:',
'guesstimezone' => 'ਬਰਾਊਜ਼ਰ ਤੋਂ ਭਰੋ',
'timezoneregion-africa' => 'ਅਫ਼ਰੀਕਾ',
'timezoneregion-america' => 'ਅਮਰੀਕਾ',
'timezoneregion-antarctica' => 'ਅੰਟਾਰਕਟਿਕਾ',
'timezoneregion-arctic' => 'ਆਰਕਟਿਕ',
'timezoneregion-asia' => 'ਏਸ਼ੀਆ',
'timezoneregion-atlantic' => 'ਅੰਧ ਮਹਾਂਸਾਗਰ',
'timezoneregion-australia' => 'ਆਸਟਰੇਲੀਆ',
'timezoneregion-europe' => 'ਯੂਰਪ',
'timezoneregion-indian' => 'ਹਿੰਦ ਮਹਾਂਸਾਗਰ',
'timezoneregion-pacific' => 'ਪ੍ਰਸ਼ਾਂਤ ਮਹਾਂਸਾਗਰ',
'allowemail' => 'ਹੋਰ ਯੂਜ਼ਰਾਂ ਤੋਂ ਈਮੇਲ ਯੋਗ ਕਰੋ',
'prefs-searchoptions' => 'ਖੋਜ',
'prefs-namespaces' => 'ਥਾਂ-ਨਾਮ',
'defaultns' => 'ਨਹੀਂ ਤਾਂ ਇਹਨਾਂ ਥਾਂ-ਨਾਂਵਾਂ ਵਿਚ ਖੋਜੋ:',
'default' => 'ਮੂਲ',
'prefs-files' => 'ਫਾਇਲਾਂ',
'prefs-emailconfirm-label' => 'ਈ-ਮੇਲ ਪੁਸ਼ਟੀ:',
'prefs-textboxsize' => 'ਸੋਧ ਖਿੜਕੀ ਦਾ ਅਕਾਰ',
'youremail' => 'ਈ-ਮੇਲ:',
'username' => '{{GENDER:$1|ਯੂਜ਼ਰਨਾਂ}}:',
'uid' => '{{GENDER:$1|User}} ਆਈਡੀ:',
'prefs-memberingroups' => '{{PLURAL:$1|ਗਰੁੱਪ|ਗਰੁੱਪਾਂ}} ਦਾ ਮੈਂਬਰ:',
'prefs-registration' => 'ਰਜਿਸਟਰੇਸ਼ਨ ਸਮਾਂ:',
'yourrealname' => 'ਅਸਲੀ ਨਾਮ:',
'yourlanguage' => 'ਭਾਸ਼ਾ:',
'yourvariant' => 'ਸਮੱਗਰੀ ਭਾਸ਼ਾ ਰੂਪ:',
'yournick' => 'ਨਵੇਂ ਦਸਤਖ਼ਤ:',
'prefs-help-signature' => 'ਗੱਲ-ਬਾਤ ਸਫ਼ਿਆਂ ਉੱਤੇ ਟਿੱਪਣੀਆਂ ਦੇ ਆਖ਼ਰ ਵਿਚ "<nowiki>~~~~</nowiki>" ਲਾਓ ਜੋ ਤੁਹਾਡੇ ਦਸਤਖ਼ਤ ਅਤੇ ਵਕਤ ਦੀ ਮੋਹਰ ਵਿਚ ਤਬਦੀਲ ਹੋ ਜਾਵੇਗਾ।',
'badsiglength' => 'ਦਸਤਖ਼ਤ ਬਹੁਤ ਲੰਬਾ ਹੋ ਗਿਆ ਹੈ। ਇਹ {{PLURAL:$1|ਅੱਖਰ|ਅੱਖਰਾਂ}} ਤੋਂ ਲੰਬਾ ਨਹੀਂ ਹੋਣਾ ਚਾਹੀਦਾ।',
'yourgender' => 'ਲਿੰਗ:',
'gender-unknown' => 'ਜ਼ਾਹਿਰ ਨਹੀਂ ਕੀਤਾ',
'gender-male' => 'ਮਰਦ',
'gender-female' => 'ਔਰਤ',
'email' => 'ਈਮੇਲ',
'prefs-help-realname' => 'ਅਸਲੀ ਨਾਂ ਚੋਣਵਾਂ ਹੈ, ਅਤੇ ਜੇ ਤੁਸੀਂ ਇਹ ਦਿੱਤਾ ਹੈ ਤਾਂ ਤੁਹਾਡੇ ਕੰਮ ਵਾਸਤੇ ਗੁਣ ਦੇ ਤੌਰ ਉੱਤੇ ਵਰਤਿਆ ਜਾਵੇਗਾ।',
'prefs-help-email' => 'ਤੁਹਾਡੀ ਮਰਜੀ ਹੈ ਈਮੇਲ ਪਤਾ ਦਿਓ ਜਾਂ ਨਾ ਦਿਓ ਪਰ ਪਾਸਵਰਡ ਭੁੱਲ ਜਾਣ ਤੇ ਨਵਾਂ ਪਾਸਵਰਡ ਹਾਸਲ ਕਰਨ ਲਈ ਇਹ ਜਰੂਰੀ ਹੈ।',
'prefs-help-email-others' => 'ਤੁਸੀਂ ਇਹ ਵੀ ਚੁਣ ਸਕਦੇ ਹੋ ਕਿ ਤੁਹਾਡੇ ਵਰਤੋਂਕਾਰ ਜਾਂ ਚਰਚਾ ਪੰਨੇ ਤੋਂ ਹੋਰ ਵਰਤੋਂਕਾਰ ਤੁਹਾਨੂੰ ਈ-ਮੇਲ ਭੇਜ ਸਕਣ?
ਜਦੋਂ ਹੋਰ ਵਰਤੋਂਕਾਰ ਤੁਹਾਨੂੰ ਈ-ਮੇਲ ਭੇਜਦੇ ਹਨ ਤਾਂ ਤੁਹਾਡਾ ਈ-ਮੇਲ ਪਤਾ ਜ਼ਾਹਰ ਨਹੀਂ ਕੀਤਾ ਜਾਂਦਾ।',
'prefs-help-email-required' => 'ਈ-ਮੇਲ ਪਤਾ ਚਾਹੀਦਾ ਹੈ।',
'prefs-info' => 'ਮੁੱਢਲੀ ਜਾਣਕਾਰੀ',
'prefs-i18n' => 'ਅੰਤਰਰਾਸ਼ਟਰੀਕਰਨ',
'prefs-signature' => 'ਦਸਤਖ਼ਤ',
'prefs-dateformat' => 'ਮਿਤੀ ਦਾ ਅੰਦਾਜ਼',
'prefs-advancedediting' => 'ਤਕਨੀਕੀ ਚੋਣਾਂ',
'prefs-advancedrc' => 'ਤਕਨੀਕੀ ਚੋਣਾਂ',
'prefs-advancedrendering' => 'ਤਕਨੀਕੀ ਚੋਣਾਂ',
'prefs-advancedsearchoptions' => 'ਤਕਨੀਕੀ ਚੋਣਾਂ',
'prefs-advancedwatchlist' => 'ਤਕਨੀਕੀ ਚੋਣਾਂ',
'prefs-displayrc' => 'ਵੇਖਾਉਣ ਚੋਣਾਂ',
'prefs-displaysearchoptions' => 'ਵੇਖਾਉਣ ਚੋਣਾਂ',
'prefs-displaywatchlist' => 'ਵੇਖਾਉਣ ਚੋਣਾਂ',
'prefs-diffs' => 'ਫ਼ਰਕ',

# User preference: email validation using jQuery
'email-address-validity-valid' => 'ਈ-ਮੇਲ ਪਤਾ ਸਹੀ ਲਗਦਾ ਹੈ',
'email-address-validity-invalid' => 'ਸਹੀ ਈ-ਮੇਲ ਪਤਾ ਦਾਖ਼ਲ ਕਰੋ',

# User rights
'userrights' => 'ਵਰਤੋਂਕਾਰ ਦੇ ਅਧਿਕਾਰਾਂ ਦਾ ਰੱਖ-ਰਖਾਓ',
'userrights-lookup-user' => 'ਯੂਜ਼ਰ ਗਰੁੱਪ ਦੇਖਭਾਲ',
'userrights-user-editname' => 'ਇੱਕ ਯੂਜ਼ਰ ਨਾਂ ਦਿਓ:',
'editusergroup' => 'ਯੂਜ਼ਰ ਗਰੁੱਪ ਸੋਧ',
'editinguser' => "'''[[User:$1|$1]]''' $2 ਯੂਜ਼ਰ ਦੇ ਯੂਜ਼ਰ ਹੱਕ ਬਦਲੇ ਜਾ ਰਹੇ ਹਨ",
'userrights-editusergroup' => 'ਯੂਜ਼ਰ ਗਰੁੱਪ ਸੋਧ',
'saveusergroups' => 'ਯੂਜ਼ਰ ਗਰੁੱਪ ਸੰਭਾਲੋ',
'userrights-groupsmember' => 'ਇਸ ਦਾ ਮੈਂਬਰ:',
'userrights-reason' => 'ਕਾਰਨ:',
'userrights-no-interwiki' => 'ਤੁਹਾਨੂੰ ਦੂਜੇ ਵਿਕੀਆਂ ਤੇ ਮੈਂਬਰਾਂ ਦੇ ਹੱਕਾਂ ਵਿਚ ਤਬਦੀਲੀ ਕਰਨ ਦੀ ਇਜਾਜ਼ਤ ਨਹੀਂ ਹੈ।',
'userrights-nodatabase' => 'ਡੈਟਾਬੇਸ $1 ਮੌਜੂਦ ਨਹੀਂ ਜਾਂ ਮਕਾਮੀ ਨਹੀਂ ਹੈ।',
'userrights-notallowed' => 'ਤੁਹਾਡੇ ਖਾਤੇ ਨੂੰ ਮੈਂਬਰ ਨੂੰ ਹੱਕ ਦੇਣ ਜਾਂ ਖੋਹਣ ਦੀ ਇਜਾਜ਼ਤ ਨਹੀਂ ਹੈ।',

# Groups
'group' => 'ਗਰੁੱਪ:',
'group-user' => 'ਵਰਤੋਂਕਾਰ',
'group-autoconfirmed' => 'ਖ਼ੁਦ-ਤਸਦੀਕਸ਼ੁਦਾ ਮੈਂਬਰ',
'group-bot' => 'ਬੋਟ',
'group-sysop' => 'ਪਰਸ਼ਾਸ਼ਕ',
'group-all' => '(ਸਭ)',

'group-user-member' => '{{GENDER:$1|ਵਰਤੋਂਕਾਰ}}',
'group-bot-member' => 'ਬੋਟ',

'grouppage-user' => '{{ns:project}}:ਵਰਤੋਂਕਾਰ',

# Rights
'right-read' => 'ਸਫ਼ੇ ਪੜ੍ਹਨਾ',
'right-edit' => 'ਸਫ਼ੇ ਸੋਧ',
'right-createpage' => 'ਸਫ਼ੇ ਬਣਾਉਣਾ (ਜੋ ਚਰਚਾ ਸਫ਼ੇ ਨਾ ਹੋਣ)',
'right-createtalk' => 'ਚਰਚਾ ਸਫ਼ੇ ਬਣਾਉਣਾ',
'right-createaccount' => 'ਨਵੇਂ ਖਾਤੇ ਬਣਾਉਣਾ',
'right-minoredit' => 'ਸੋਧਾਂ ਦੇ ਛੋਟਾ ਹੋਣ ਲਈ ਨਿਸ਼ਾਨ ਲਾਉਣਾ',
'right-move' => 'ਸਫ਼ੇ ਭੇਜਣਾ',
'right-movefile' => 'ਫਾਇਲਾਂ ਭੇਜਣੀਆਂ',
'right-upload' => 'ਫਾਇਲਾਂ ਅੱਪਲੋਡ',
'right-upload_by_url' => 'URL ਤੋਂ ਫਾਇਲਾਂ ਅੱਪਲੋਡ ਕਰੋ',
'right-autoconfirmed' => 'ਨੀਮ-ਸੁਰੱਖਿਅਤ ਸਫ਼ਿਆਂ ਨੂੰ ਸੋਧਣਾ',
'right-delete' => 'ਸਫ਼ੇ ਹਟਾਓ',
'right-bigdelete' => 'ਵੱਡੇ ਅਤੀਤ ਵਾਲੇ ਪੰਨੇ ਮਿਟਾਉਣੇ',
'right-browsearchive' => 'ਮਿਟਾਏ ਹੋਏ ਸਫ਼ੇ ਖੋਜੋ',
'right-undelete' => 'ਸਫ਼ੇ ਨੂੰ ਅਣ-ਮਿਟਾਇਆ ਕਰਨਾ',
'right-suppressionlog' => 'ਪ੍ਰਾਈਵੇਟ ਚਿੱਠੇ ਵੇਖਣਾ',
'right-block' => 'ਦੂਜੇ ਮੈਂਬਰਾਂ ਦੇ ਸੋਧ ਕਰਨ ਤੇ ਪਾਬੰਦੀ ਲਾਉਣੀ',
'right-blockemail' => 'ਮੈਂਬਰ ਦੇ ਈ-ਮੇਲ ਭੇਜਣ ਤੇ ਪਾਬੰਦੀ ਲਾਉਣੀ',
'right-hideuser' => 'ਮੈਂਬਰ-ਨਾਂ ਤੇ ਪਾਬੰਦੀ ਲਾਉਣੀ ਅਤੇ ਇਸਨੂੰ ਲੋਕਾਂ ਤੋਂ ਲੁਕਾਉਣਾ',
'right-unwatchedpages' => 'ਨਜ਼ਰ ਨਾ ਰੱਖੇ ਜਾ ਰਹੇ ਸਫ਼ਿਆਂ ਦੀ ਲਿਸਟ ਵੇਖਣੀ',
'right-mergehistory' => 'ਸਫ਼ਿਆਂ ਦੇ ਅਤੀਤਾਂ ਨੂੰ ਰਲ਼ਾਉਣਾ',
'right-userrights' => 'ਸਾਰੇ ਮੈਂਬਰ ਹੱਕਾਂ ਵਿਚ ਸੋਧ ਕਰਨਾ',
'right-userrights-interwiki' => 'ਦੂਜੇ ਵਿਕੀਆਂ ਤੇ ਮੈਂਬਰਾਂ ਦੇ ਮੈਂਬਰ ਹੱਕਾਂ ਵਿਚ ਸੋਧ ਕਰਨਾ',
'right-siteadmin' => 'ਡੈਟਾਬੇਸ ਨੂੰ ਤਾਲਾ ਲਾਉਣਾ ਤੇ ਖੋਲ੍ਹਣਾ',
'right-sendemail' => 'ਦੂਜੇ ਮੈਂਬਰਾਂ ਨੂੰ ਈ-ਮੇਲ ਭੇਜਣਾ',
'right-passwordreset' => 'ਪਾਸਵਰਡ ਮੁੜ-ਸੈੱਟ ਈਮੇਲ ਵੇਖੋ',

# Special:Log/newusers
'newuserlogpage' => 'ਬਣਾਏ ਖਾਤਿਆਂ ਦਾ ਚਿੱਠਾ',
'newuserlogpagetext' => 'ਇਹ ਬਣੇ ਮੈਂਬਰਾਂ ਦਾ ਚਿੱਠਾ ਹੈ।',

# User rights log
'rightslog' => 'ਮੈਂਬਰ ਹੱਕਾਂ ਦਾ ਚਿੱਠਾ',
'rightslogtext' => 'ਇਹ ਮੈਂਬਰ ਹੱਕਾਂ ਵਿਚ ਹੋਈਆਂ ਤਬਦੀਲੀਆਂ ਦਾ ਚਿੱਠਾ ਹੈ।',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'ਇਹ ਸਫ਼ਾ ਪੜ੍ਹੋ',
'action-edit' => 'ਇਹ ਪੰਨੇ ਨੂੰ ਸੰਪਾਦਿਤ ਕਰਨ',
'action-createpage' => 'ਸਫ਼ੇ ਬਣਾਓ',
'action-createtalk' => 'ਚਰਚਾ ਸਫ਼ੇ ਬਣਾਉਣ',
'action-createaccount' => 'ਇਹ ਵਰਤੋਂਕਾਰ ਖਾਤਾ ਬਣਾਓ',
'action-minoredit' => 'ਇਹ ਸੋਧ ਨੂੰ ਛੋਟੀ ਤੌਰ ਉੱਤੇ ਮੰਨੋ',
'action-move' => 'ਇਹ ਸਫ਼ਾ ਭੇਜੋ',
'action-move-subpages' => 'ਇਹ ਸਫ਼ਾ ਤੇ ਇਸ ਦੇ ਅਧੀਨ-ਸਫ਼ਿਆਂ ਨੂੰ ਭੇਜੋ',
'action-movefile' => 'ਇਹ ਫਾਇਲ ਭੇਜੋ',
'action-upload' => 'ਇਹ ਫਾਇਲ ਅੱਪਲੋਡ',
'action-reupload' => 'ਇਹ ਮੌਜੂਦਾ ਫਾਇਲ ਉੱਤੇ ਲਿਖੋ',
'action-reupload-shared' => 'ਇਹ ਫਾਇਲ ਨੂੰ ਸਾਂਝੀ ਕੀਤੀ ਰਿਪੋਜ਼ਟਰੀ ਉੱਤੇ ਲਿਖੋ',
'action-upload_by_url' => 'ਇਹ ਫਾਇਲ ਨੂੰ URL ਤੋਂ ਅੱਪਲੋਡ ਕਰੋ',
'action-writeapi' => 'ਲਿਖਣ API ਵਰਤੋਂ',
'action-delete' => 'ਇਹ ਪੰਨਾ ਮਿਟਾਓ',
'action-deleterevision' => 'ਇਹ ਰੀਵਿਜਨ ਮਿਟਾਓ',
'action-deletedhistory' => 'ਇਸ ਸਫ਼ੇ ਦਾ ਮਿਟਾਇਆ ਅਤੀਤ ਵੇਖਣ',
'action-browsearchive' => 'ਮਿਟਾਏ ਸਫ਼ੇ ਖੋਜਣ',
'action-undelete' => 'ਇਹ ਸਫ਼ਾ ਅਣ-ਮਿਟਿਆ ਕਰਨ',
'action-suppressrevision' => 'ਇਹ ਲੁਕਵਾਂ ਰੀਵਿਜ਼ਨ ਜਾਂਚੋ ਅਤੇ ਮੁੜ-ਸਟੋਰ ਕਰੋ',
'action-suppressionlog' => 'ਇਹ ਪ੍ਰਾਈਵੇਟ ਲਾਗ ਵੇਖੋ',
'action-block' => 'ਇਸ ਮੈਂਬਰ ਦੇ ਸੋਧ ਕਰਨ ਤੇ ਪਾਬੰਦੀ ਲਾਉਣ',
'action-protect' => 'ਇਸ ਸਫ਼ੇ ਦੀ ਸੁਰੱਖਿਆ ਬਦਲਣ',
'action-import' => 'ਹੋਰ ਵਿਕਿ ਤੋਂ ਇਹ ਸਫ਼ਾ ਇੰਪੋਰਟ ਕਰੋ',
'action-importupload' => 'ਫਾਇਲ ਅੱਪਲੋਡ ਤੋਂ ਇਹ ਸਫ਼ਾ ਇੰਪੋਰਟ ਕਰੋ',
'action-unwatchedpages' => 'ਨਜ਼ਰ ਨਾ ਰੱਖੇ ਜਾ ਰਹੇ ਸਫ਼ਿਆਂ ਦੀ ਲਿਸਟ ਵੇਖਣ',
'action-mergehistory' => 'ਇਸ ਸਫ਼ੇ ਦੇ ਅਤੀਤ ਨੂੰ ਰਲ਼ਾਉਣ',
'action-userrights' => 'ਸਾਰੇ ਮੈਂਬਰ ਹੱਕ ਸੋਧਣ',
'action-userrights-interwiki' => 'ਦੂਜੇ ਵਿਕੀਆਂ ਤੇ ਮੈਂਬਰਾਂ ਦੇ ਮੈਂਬਰ ਹੱਕ ਸੋਧਣ',
'action-siteadmin' => 'ਡੈਟਾਬੇਸ ਨੂੰ ਤਾਲਾ ਲਾਉਣ ਜਾਂ ਖੋਲ੍ਹਣ',
'action-sendemail' => 'ਈ-ਮੇਲਾਂ ਭੇਜੋ',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|ਤਬਦੀਲੀ|ਤਬਦੀਲੀਆਂ}}',
'recentchanges' => 'ਹਾਲ ’ਚ ਹੋਈਆਂ ਤਬਦੀਲੀਆਂ',
'recentchanges-legend' => 'ਹਾਲ ਦੀਆਂ ਤਬਦੀਲੀਆਂ ਸਬੰਧੀ ਚੋਣਾਂ',
'recentchanges-summary' => 'ਇਸ ਵਿਕੀ ’ਤੇ ਹੋਈਆਂ ਸਭ ਤੋਂ ਨਵੀਆਂ ਤਬਦੀਲੀਆਂ ਇਸ ਸਫ਼ੇ ’ਤੇ ਵੇਖੋ।',
'recentchanges-feed-description' => 'ਇਸ ਵਿਕੀ ’ਤੇ ਹਾਲ ‘ਚ ਹੋਈਆਂ ਤਬਦੀਲੀਆਂ ਇਸ ਫ਼ੀਡ ’ਚ ਵੇਖੀਆਂ ਜਾ ਸਕਦੀਆਂ ਹਨ।',
'recentchanges-label-newpage' => 'ਇਸ ਸੋਧ ਨੇ ਨਵਾਂ ਪੰਨਾ ਬਣਾਇਆ ਹੈ',
'recentchanges-label-minor' => 'ਇਹ ਇੱਕ ਛੋਟੀ ਸੋਧ ਹੈ',
'recentchanges-label-bot' => 'ਇਹ ਸੋਧ ਇੱਕ ਬੋਟ ਦੁਆਰਾ ਕੀਤੀ ਗਈ ਸੀ',
'recentchanges-label-unpatrolled' => 'ਇਹ ਫੇਰ-ਬਦਲ ਹਾਲੇ ਵੇਖਿਆ ਨਹੀਂ ਗਿਆ',
'rcnote' => "$4, $5 ਤੱਕ ਆਖਰੀ {{PLURAL:$2|ਦਿਨ|'''$2''' ਦਿਨਾਂ}} ਵਿੱਚ {{PLURAL:$1|'''1''' ਬਦਲੀ ਹੋਈ ਹੈ।|'''$1''' ਬਦਲੀਆਂ ਹੋਈਆਂ ਹਨ।}}",
'rcnotefrom' => "'''$2''' ਤੱਕ ('''$1''' ਤੱਕ ਦਿੱਸਦੇ) ਬਦਲਾਵ ਹੇਠ ਦਿੱਤੀਆਂ ਹਨ।",
'rclistfrom' => '$1 ਤੋਂ ਸ਼ੁਰੂ ਕਰਕੇ ਨਵੀਆਂ ਸੋਧਾਂ ਵਖਾਓ',
'rcshowhideminor' => 'ਛੋਟੀਆਂ ਤਬਦੀਲੀਆਂ $1',
'rcshowhidebots' => '$1 ਬੋਟ',
'rcshowhideliu' => '$1 ਲਾਗਇਨ ਹੋਏ ਵਰਤੋਂਕਾਰ',
'rcshowhideanons' => 'ਅਣਜਾਣ ਵਰਤੋਂਕਾਰ $1',
'rcshowhidepatr' => 'ਜਾਂਚੀਆਂ ਹੋਈਆਂ ਤਬਦੀਲੀਆਂ $1',
'rcshowhidemine' => 'ਮੇਰੀਆਂ ਤਬਦੀਲੀਆਂ  $1',
'rclinks' => 'ਪਿਛਲੇ $2 ਦਿਨਾਂ ਵਿੱਚ ਹੋਈਆਂ $1 ਤਬਦੀਲੀਆਂ ਵਖਾਓ<br /> $3',
'diff' => 'ਫ਼ਰਕ',
'hist' => 'ਅਤੀਤ',
'hide' => 'ਲੁਕਾਓ',
'show' => 'ਵੇਖਾਓ',
'minoreditletter' => 'ਛੋ',
'newpageletter' => 'ਨ',
'boteditletter' => 'ਬੋ',
'number_of_watching_users_pageview' => '[$1 ਵੇਖ ਰਹੇ ਹਨ {{PLURAL:$1|ਯੂਜ਼ਰ}}]',
'rc_categories_any' => 'ਕੋਈ ਵੀ',
'newsectionsummary' => '/* $1 */ ਨਵਾਂ ਭਾਗ',
'rc-enhanced-expand' => 'ਵੇਰਵਾ ਵੇਖਾਓ (ਜਾਵਾਸਕ੍ਰਿਪਟ ਲੋੜੀਂਦੀ ਹੈ)',
'rc-enhanced-hide' => 'ਵੇਰਵਾ ਲੁਕਾਓ',
'rc-old-title' => 'ਅਸਲ ਵਿੱਚ "$1" ਵਜੋਂ ਬਣਾਇਆ',

# Recent changes linked
'recentchangeslinked' => 'ਸਬੰਧਤ ਬਦਲਾਅ',
'recentchangeslinked-feed' => 'ਸਬੰਧਤ ਤਬਦੀਲੀਆਂ',
'recentchangeslinked-toolbox' => 'ਸਬੰਧਤ ਬਦਲਾਅ',
'recentchangeslinked-title' => '"$1" ਨਾਲ ਸਬੰਧਿਤ ਬਦਲਾਵ',
'recentchangeslinked-noresult' => 'ਜੁੜੇ ਪੰਨਿਆਂ ’ਤੇ, ਦਿੱਤੇ ਸਮੇਂ ’ਚ ਕੋਈ ਬਦਲਾਵ ਨਹੀਂ ਹੋਈ।',
'recentchangeslinked-summary' => 'ਇਹ ਸੂਚੀ ਇੱਕ ਵਿਸ਼ੇਸ਼ ਪੰਨੇ ਨਾਲ ਸਬੰਧਿਤ ਪੰਨਿਆਂ ਜਾਂ ਕਿਸੇ ਵਿਸ਼ੇਸ਼ ਸ਼੍ਰੇਣੀ ਦੇ ਮੈਂਬਰਾਂ ਦੇ ਹਾਲ ‘ਚ ਹੋਏ ਬਦਲਾਵਾਂ ਨੂੰ ਦਰਸਾਂਉਦੀ ਹੈ। [[Special:Watchlist|ਤੁਹਾਡੀ ਧਿਆਨਸੂਚੀ]] ਵਿੱਚ ਮੌਜੂਦ ਪੰਨੇ ਮੋਟੇ ਅੱਖਰਾਂ ਵਿੱਚ ਦਿਖਾਈ ਦੇਣਗੇ।',
'recentchangeslinked-page' => 'ਪੰਨੇ ਦਾ ਨਾਮ:',
'recentchangeslinked-to' => 'ਇਸਦੇ ਬਦਲੇ ਇਸ ਪੰਨੇ ਨਾਲ ਜੁੜੇ ਪੰਨਿਆਂ ਵਿੱਚ ਹੋਏ ਬਦਲਾਅ ਵਿਖਾਓ',

# Upload
'upload' => 'ਫ਼ਾਈਲ ਅਪਲੋਡ ਕਰੋ',
'uploadbtn' => 'ਫਾਇਲ ਅੱਪਲੋਡ ਕਰੋ',
'reuploaddesc' => 'ਅੱਪਲੋਡ ਫਾਰਮ ਉੱਤੇ ਜਾਓ।',
'uploadnologin' => 'ਲਾਗਇਨ ਨਹੀਂ ਹੋ',
'uploadnologintext' => 'ਤੁਹਾਨੂੰ[[Special:UserLogin|logged in] ਕਰਨਾ ਪਵੇਗਾ]
to upload files.',
'uploaderror' => 'ਅੱਪਲੋਡ ਗਲਤੀ',
'upload-recreate-warning' => "'''ਖ਼ਬਰਦਾਰ: ਇਸ ਨਾਮ ਦੀ ਫ਼ਾਈਲ ਮਿਟਾਈ ਜਾਂ ਹੋਰ ਨਾਮ ਤੇ ਭੇਜੀ ਜਾ ਚੁੱਕੀ ਹੈ।'''
ਮਿਟਾਉਣ ਅਤੇ ਭੇਜੇ ਜਾਣ ਦਾ ਚਿੱਠਾ ਸਹੂਲਤ ਲਈ ਇੱਥੇ ਦਿੱਤਾ ਗਿਆ ਹੈ:",
'uploadlog' => 'ਅੱਪਲੋਡ ਲਾਗ',
'uploadlogpage' => 'ਅਪਲੋਡਾਂ ਦਾ ਚਿੱਠਾ',
'filename' => 'ਫਾਇਲ ਨਾਂ',
'filedesc' => 'ਸਾਰ',
'fileuploadsummary' => 'ਸੰਖੇਪ:',
'filereuploadsummary' => 'ਫਾਇਲ ਬਦਲਾਅ',
'filestatus' => 'ਕਾਪੀਰਾਈਟ ਹਾਲਤ:',
'filesource' => 'ਸੋਰਸ:',
'uploadedfiles' => 'ਅੱਪਲੋਡ ਕੀਤੀਆਂ ਫਾਇਲਾਂ',
'ignorewarning' => 'ਚੇਤਾਵਨੀ ਅਣਡਿੱਠੀ ਕਰਕੇ ਕਿਵੇਂ ਵੀ ਫਾਇਲ ਸੰਭਾਲੋ।',
'ignorewarnings' => 'ਕੋਈ ਚੇਤਾਵਨੀ ਹੋਈ ਤਾਂ ਨਜ਼ਰਅੰਦਾਜ਼ ਕਰੋ',
'minlength1' => 'ਫਾਇਲ ਨਾਂ ਵਿੱਚ ਘੱਟੋ-ਘੱਟ ਇੱਕ ਅੱਖਰ ਹੋਣਾ ਚਾਹੀਦਾ ਹੈ।',
'badfilename' => 'ਫਾਇਲ ਨਾਂ "$1" ਬਦਲਿਆ ਗਿਆ ਹੈ।',
'filetype-missing' => 'ਫਾਇਲ ਦੀ ਕੋਈ ਐਕਸ਼ਟੇਸ਼ਨ ਨਹੀਂ ਹੈ (ਜਿਵੇਂ ".jpg").',
'empty-file' => 'ਤੁਹਾਡੇ ਵਲੋਂ ਦਿੱਤੀ ਫਾਇਲ ਖਾਲੀ ਸੀ।',
'file-too-large' => 'ਤੁਹਾਡੇ ਵਲੋਂ ਦਿੱਤੀ ਫਾਇਲ ਬਹੁਤ ਵੱਡੀ ਸੀ।',
'filename-tooshort' => 'ਫ਼ਾਈਲ ਦਾ ਨਾਂ ਬਹੁਤ ਛੋਟਾ ਹੈ।',
'filetype-banned' => 'ਇਸ ਕਿਸਮ ਦੀ ਫਾਈਲ ਦੀ ਮਨਾਹੀ ਹੈ।',
'verification-error' => 'ਇਹ ਫਾਇਲ ਫਾਇਲ ਜਾਂਚ ਪੂਰੀ ਨਹੀਂ ਕਰਦੀ ਹੈ।',
'illegal-filename' => 'ਇਸ ਫਾਈਲ-ਨਾਮ ਦੀ ਇਜਾਜ਼ਤ ਨਹੀਂ ਹੈ।',
'unknown-error' => 'ਅਣਜਾਣ ਗਲਤੀ ਆਈ ਹੈ।',
'tmp-create-error' => 'ਆਰਜ਼ੀ ਫਾਇਲ ਬਣਾਉਣ ਲਈ ਅਸਮਰੱਥ ਹੈ।',
'tmp-write-error' => 'ਆਰਜ਼ੀ ਫਾਇਲ ਲਿਖਣ ਲਈ ਗਲਤੀ ਹੈ।',
'fileexists' => 'ਇਹ ਫਾਇਲ ਨਾਂ ਪਹਿਲਾਂ ਹੀ ਮੌਜੂਦ ਹੈ। ਜੇ ਤੁਸੀਂ ਇਹ ਬਦਲਣ ਬਾਰੇ ਦ੍ਰਿੜ ਨਹੀਂ ਹੋ ਤਾਂ  <strong>[[:$1]]</strong> ਵੇਖੋ ਜੀ। [[$1|thumb]]',
'fileexists-extension' => 'ਇਸ ਨਾਂ ਨਾਲ ਰਲਦੀ ਫਾਇਲ ਮੌਜੂਦ ਹੈ: [[$2|thumb]]
* ਅੱਪਲੋਡ ਕੀਤੀ ਜਾਂਦੀ ਫਾਇਲ ਦਾ ਨਾਂ: <strong>[[:$1]]</strong>
* ਮੌਜੂਦ ਫਾਇਲ ਦਾ ਨਾਂ: <strong>[[:$2]]</strong>
ਇੱਕ ਵੱਖਰਾ ਨਾਂ ਚੁਣੋ ਜੀ',
'file-exists-duplicate' => 'ਇਹ ਫ਼ਾਈਲ {{PLURAL:$1|ਇਸ ਫ਼ਾਈਲ|ਇਹਨਾਂ ਫ਼ਾਈਲਾਂ}} ਦੀ ਨਕਲ ਹੈ:',
'uploadwarning' => 'ਅੱਪਲੋਡ ਚੇਤਾਵਨੀ',
'savefile' => 'ਫਾਇਲ ਸੰਭਾਲੋ',
'uploadedimage' => '"[[$1]]" ਅੱਪਲੋਡ ਕੀਤੀ',
'overwroteimage' => '"[[$1]]" ਦਾ ਨਵਾਂ ਰੂਪ ਅੱਪਲੋਡ ਕਰੋ',
'uploaddisabled' => 'ਅੱਪਲੋਡ ਆਯੋਗ ਹੈ',
'uploadvirus' => 'ਇਹ ਫਾਇਲ ਵਿੱਚ ਵਾਇਰਸ ਹੈ! ਵੇਰਵੇ ਲਈ ਵੇਖੋ: $1',
'upload-source' => 'ਸਰੋਤ ਫਾਇਲ',
'sourcefilename' => 'ਸੋਰਸ ਫਾਇਲ ਨਾਂ:',
'sourceurl' => 'ਸਰੋਤ URL:',
'destfilename' => 'ਟਿਕਾਣਾ ਫਾਇਲ-ਨਾਂ:',
'upload-maxfilesize' => 'ਫਾਈਲ ਦਾ ਵੱਧ ਤੋਂ ਵੱਧ ਅਕਾਰ: $1',
'upload-description' => 'ਫਾਈਲ ਵੇਰਵਾ',
'upload-options' => 'ਅੱਪਲੋਡ ਚੋਣਾਂ',
'watchthisupload' => 'ਇਸ ਫ਼ਾਈਲ ’ਤੇ ਨਜ਼ਰ ਰੱਖੋ',
'upload-success-subj' => 'ਠੀਕ ਤਰ੍ਹਾਂ ਅੱਪਲੋਡ',
'upload-failure-subj' => 'ਅੱਪਲੋਡ ਸਮੱਸਿਆ',
'upload-warning-subj' => 'ਅੱਪਲੋਡ ਚੇਤਾਵਨੀ',

'upload-proto-error' => 'ਗਲਤ ਪਰੋਟੋਕਾਲ',
'upload-file-error' => 'ਅੰਦਰੂਨੀ ਗਲਤੀ',
'upload-misc-error' => 'ਅਣਜਾਣ ਅੱਪਲੋਡ ਗਲਤੀ',
'upload-unknown-size' => 'ਅਣਜਾਣ ਆਕਾਰ',

# File backend
'backend-fail-notexists' => 'ਫ਼ਾਈਲ $1 ਮੌਜੂਦ ਨਹੀਂ ਹੈ।',
'backend-fail-delete' => 'ਫ਼ਾਈਲ "$1" ਮਿਟਾਈ ਨਹੀਂ ਜਾ ਸਕੀ।',
'backend-fail-alreadyexists' => 'ਫ਼ਾਈਲ "$1" ਪਹਿਲਾਂ ਹੀ ਮੌਜੂਦ ਹੈ।',
'backend-fail-store' => 'ਫ਼ਾਈਲ "$1", "$2" ਵਿਚ ਸਾਂਭੀ ਨਹੀਂ ਜਾ ਸਕੀ।',
'backend-fail-copy' => 'ਫ਼ਾਈਲ "$1", "$2" ਵਿਚ ਨਕਲ ਨਹੀਂ ਕੀਤੀ ਜਾ ਸਕੀ।',
'backend-fail-move' => 'ਫ਼ਾਈਲ "$1", "$2" ਤੇ ਭੇਜੀ ਨਹੀਂ ਜਾ ਸਕੀ।',
'backend-fail-opentemp' => 'ਆਰਜ਼ੀ ਫ਼ਾਈਲ ਖੋਲ੍ਹੀ ਨਹੀਂ ਜਾ ਸਕੀ।',

# Special:UploadStash
'uploadstash-refresh' => 'ਫ਼ਾਈਲਾਂ ਦੀ ਲਿਸਟ ਨੂੰ ਤਾਜ਼ਾ ਕਰੋ',

# img_auth script messages
'img-auth-nofile' => 'ਫ਼ਾਈਲ "$1" ਮੌਜੂਦ ਨਹੀਂ ਹੈ।',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'URL ’ਤੇ ਪਹੁੰਚਿਆ ਨਹੀਂ ਜਾ ਸਕਿਆ।',
'upload-curl-error6-text' => 'ਦਿੱਤੇ ਹੋਏ URL ’ਤੇ ਪਹੁੰਚਿਆ ਨਹੀਂ ਜਾ ਸਕਿਆ।
ਮਿਹਰਬਾਨੀ ਕਰਕੇ ਦੁਬਾਰਾ ਜਾਂਚ ਕਰੋ ਕਿ URL ਸਹੀ ਹੈ ਅਤੇ ਸਾਈਟ ਉਪਲੱਬਧ ਹੈ।',
'upload-curl-error28' => 'ਅੱਪਲੋਡ ਟਾਈਮ-ਆਉਟ',

'license' => 'ਲਾਈਸੈਂਸ:',
'license-header' => 'ਲਾਈਸੈਂਸ',
'nolicense' => 'ਕੁਝ ਵੀ ਚੁਣਿਆ',
'license-nopreview' => '(ਝਲਕ ਉਪਲੱਬਧ ਨਹੀਂ)',
'upload_source_file' => ' (ਤੁਹਾਡੇ ਕੰਪਿਊਟਰ ਉੱਤੇ ਇੱਕ ਫਾਇਲ)',

# Special:ListFiles
'listfiles_search_for' => 'ਇਸ ਮੀਡੀਆ ਨਾਮ ਨੂੰ ਖੋਜੋ:',
'imgfile' => 'ਫਾਇਲ',
'listfiles' => 'ਫਾਇਲ ਲਿਸਟ',
'listfiles_thumb' => 'ਨਮੂਨਾ-ਤਸਵੀਰ',
'listfiles_date' => 'ਮਿਤੀ',
'listfiles_name' => 'ਨਾਂ',
'listfiles_user' => 'ਯੂਜ਼ਰ',
'listfiles_size' => 'ਆਕਾਰ',
'listfiles_description' => 'ਵੇਰਵਾ',
'listfiles_count' => 'ਵਰਜਨ',

# File description page
'file-anchor-link' => 'ਫਾਈਲ',
'filehist' => 'ਫ਼ਾਈਲ ਦਾ ਅਤੀਤ',
'filehist-help' => 'ਤਾਰੀਖ/ਸਮੇਂ ’ਤੇ ਕਲਿੱਕ ਕਰੋ ਤਾਂ ਉਸ ਸਮੇਂ ਦੀ ਫਾਈਲ ਪੇਸ਼ ਹੋ ਜਾਵੇਗੀ।',
'filehist-deleteall' => 'ਸਭ ਹਟਾਓ',
'filehist-deleteone' => 'ਇਹ ਹਟਾਓ',
'filehist-revert' => 'ਉਲਟਾਓ',
'filehist-current' => 'ਮੌਜੂਦਾ',
'filehist-datetime' => 'ਮਿਤੀ/ਸਮਾਂ',
'filehist-thumb' => 'ਨਮੂਨਾ',
'filehist-thumbtext' => '$1 ਦੇ ਸਮੇਂ ਦੇ ਸੰਸਕਰਨ ਦਾ ਅੰਗੂਠਾਕਾਰ ਪ੍ਰਤੀਰੂਪ',
'filehist-nothumb' => 'ਕੋਈ ਨਮੂਨਾ-ਤਸਵੀਰ ਨਹੀਂ',
'filehist-user' => 'ਵਰਤੋਂਕਾਰ',
'filehist-dimensions' => 'ਨਾਪ',
'filehist-filesize' => 'ਫਾਇਲ ਆਕਾਰ',
'filehist-comment' => 'ਟਿੱਪਣੀ',
'filehist-missing' => 'ਫਾਈਲ ਗੁੰਮ',
'imagelinks' => 'ਫਾਈਲ ਵਰਤੋਂ',
'linkstoimage' => 'ਇਹ {{PLURAL:$1|ਪੰਨੇ ਦੇ ਲਿੰਕ|$1 ਪੰਨੇ}} ਇਸ ਫ਼ਾਈਲ ਨਾਲ ਜੋੜਦੇ ਹਨੇ:',
'nolinkstoimage' => 'ਕੋਈ ਵੀ ਪੰਨਾ ਇਸ ਫ਼ਾਈਲ ਨਾਲ ਨਹੀਂ ਜੋੜਦਾ।',
'morelinkstoimage' => 'ਇਸ ਫ਼ਾਈਲ ਨਾਲ਼ ਜੋੜਦੇ [[Special:WhatLinksHere/$1|ਹੋਰ ਲਿੰਕ]] ਵੇਖੋ।',
'sharedupload' => 'ਇਹ ਫ਼ਾਈਲ $1 ਤੋਂ ਹੈ ਅਤੇ ਸ਼ਾਇਦ ਦੂਜੇ ਪ੍ਰੋਜੈਕਟਾਂ ਤੇ ਵਰਤੀ ਜਾ ਸਕਦੀ ਹੈ।',
'sharedupload-desc-there' => 'ਇਹ ਫ਼ਾਈਲ $1 ਤੋਂ ਹੈ ਅਤੇ ਸ਼ਾਇਦ ਦੂਜੇ ਪ੍ਰੋਜੈਕਟਾਂ ਦੁਆਰਾ ਵਰਤੀ ਜਾ ਸਕਦੀ ਹੈ।
ਜ਼ਿਆਦਾ ਜਾਣਕਾਰੀ ਲਈ ਮਿਹਰਬਾਨੀ ਕਰਕੇ [$2 ਫ਼ਾਈਲ ਦਾ ਵੇਰਵਾ ਸਫ਼ਾ] ਵੇਖੋ।',
'sharedupload-desc-here' => 'ਇਹ ਫ਼ਾਈਲ $1 ਦੀ ਹੈ ਅਤੇ ਹੋਰ ਪਰਿਯੋਜਨਾਵਾਂ ਵਿੱਚ ਵੀ ਵਰਤੀ ਜਾ ਸਕਦੀ ਹੈ । ਇਸ [$2 ਫ਼ਾਈਲ ਦੇ ਵੇਰਵਾ ਪੰਨੇ] ਵਿੱਚ ਮੌਜੂਦ ਵੇਰਵਾ ਹੇਠ ਦਿਸ ਰਿਹਾ ਹੈ।',
'sharedupload-desc-edit' => 'ਇਹ ਫ਼ਾਈਲ $1 ਤੋਂ ਹੈ ਅਤੇ ਸ਼ਾਇਦ ਦੂਜੇ ਪ੍ਰੋਜੈਕਟਾਂ ਦੁਆਰਾ ਵਰਤੀ ਜਾ ਸਕਦੀ ਹੈ।
ਸ਼ਾਇਦ ਤੁਸੀਂ [$2 ਫ਼ਾਈਲ ਦੇ ਵੇਰਵੇ ਸਫ਼ੇ] ਤੇ ਇਸਦਾ ਵੇਰਵਾ ਬਦਲਣਾ ਚਾਹੋ।',
'sharedupload-desc-create' => 'ਇਹ ਫ਼ਾਈਲ $1 ਤੋਂ ਹੈ ਅਤੇ ਸ਼ਾਇਦ ਦੂਜੇ ਪ੍ਰੋਜੈਕਟਾਂ ਦੁਆਰਾ ਵਰਤੀ ਜਾ ਸਕਦੀ ਹੈ।
ਸ਼ਾਇਦ ਤੁਸੀਂ [$2 ਫ਼ਾਈਲ ਦੇ ਵੇਰਵੇ ਸਫ਼ੇ] ਤੇ ਇਸਦਾ ਵੇਰਵਾ ਬਦਲਣਾ ਚਾਹੋ।',
'filepage-nofile' => 'ਇਸ ਨਾਮ ਦੀ ਕੋਈ ਫ਼ਾਈਲ ਮੌਜੂਦ ਨਹੀਂ ਹੈ।',
'filepage-nofile-link' => 'ਇਸ ਨਾਮ ਦੀ ਕੋਈ ਫ਼ਾਈਲ ਮੌਜੂਦ ਨਹੀਂ ਹੈ ਪਰ ਤੁਸੀਂ [$1 ਇਸਨੂੰ ਅੱਪਲੋਡ ਕਰ] ਸਕਦੇ ਹੋ।',
'uploadnewversion-linktext' => 'ਇਸ ਫਾਇਲ ਦਾ ਇੱਕ ਨਵਾਂ ਵਰਜਨ ਅੱਪਲੋਡ ਕਰੋ',
'shared-repo-from' => '$1 ਤੋਂ',

# File reversion
'filerevert' => '$1 ਰੀਵਰਟ',
'filerevert-legend' => 'ਫਾਇਲ ਰੀਵਰਟ',
'filerevert-comment' => 'ਕਾਰਨ:',
'filerevert-submit' => 'ਰੀਵਰਟ',

# File deletion
'filedelete' => '$1 ਹਟਾਓ',
'filedelete-legend' => 'ਫਾਇਲ ਹਟਾਓ',
'filedelete-intro' => "ਤੁਸੀਂ ਸਾਰੇ ਅਤੀਤ ਸਮੇਤ ਫ਼ਾਈਲ '''[[Media:$1|$1]]''' ਨੂੰ ਮਿਟਾਉਣ ਵਾਲ਼ੇ ਹੋ।",
'filedelete-intro-old' => "ਤੁਸੀਂ ਫ਼ਾਈਲ '''[[Media:$1|$1]]''' ਦਾ [$4 $2, $3] ਵਾਲ਼ਾ ਰੂਪ ਮਿਟਾ ਰਹੇ ਹੋ।",
'filedelete-comment' => 'ਕਾਰਨ:',
'filedelete-submit' => 'ਹਟਾਓ',
'filedelete-success' => "'''$1''' ਨੂੰ ਹਟਾਇਆ ਗਿਆ।",
'filedelete-success-old' => "ਫ਼ਾਈਲ '''[[Media:$1|$1]]''' ਦਾ $2, $3 ਵਾਲ਼ਾ ਰੂਪ ਮਿਟਾਇਆ ਜਾ ਚੁੱਕਾ ਹੈ।",
'filedelete-nofile' => "'''$1''' ਮੌਜੂਦ ਨਹੀਂ ਹੈ।",
'filedelete-otherreason' => 'ਹੋਰ/ਵਾਧੂ ਕਾਰਨ:',
'filedelete-reason-otherlist' => 'ਹੋਰ ਕਾਰਨ',
'filedelete-reason-dropdown' => '* ਮਿਟਾਉਣ ਦੇ ਆਮ ਕਾਰਨ
** ਡੁਪਲੀਕੇਟ ਫ਼ਾਈਲ
** ਕਾਪੀਰਾਈਟ ਦੀ ਉਲੰਘਣਾ',
'filedelete-edit-reasonlist' => 'ਮਿਟਾਉਣ ਦੇ ਕਾਰਨ ਸੋਧੋ',
'filedelete-maintenance-title' => 'ਫ਼ਾਈਲ ਮਿਟਾ ਨਹੀਂ ਸਕਦੇ',

# MIME search
'mimesearch' => 'MIME ਖੋਜ',
'mimetype' => 'MIME ਕਿਸਮ:',
'download' => 'ਡਾਊਨਲੋਡ',

# Unwatched pages
'unwatchedpages' => 'ਨਜ਼ਰ ਹੇਠ ਨਾ ਰੱਖੇ ਗਏ ਸਫ਼ੇ',

# List redirects
'listredirects' => 'ਰੀਡਾਇਰੈਕਟਾਂ ਦੀ ਲਿਸਟ',

# Unused templates
'unusedtemplates' => 'ਅਣ-ਵਰਤੇ ਗਏ ਸਾਂਚੇ',
'unusedtemplateswlh' => 'ਹੋਰ ਲਿੰਕ',

# Random page
'randompage' => 'ਰਲਵਾਂ ਪੰਨਾ',
'randompage-nopages' => '{{PLURAL:$2|ਇਸ ਥਾਂ-ਨਾਮ|ਇਹਨਾਂ ਥਾਂ-ਨਾਂਵਾ}} ਵਿਚ ਕੋਈ ਸਫ਼ਾ ਨਹੀਂ ਹੈ: $1।',

# Statistics
'statistics' => 'ਅੰਕੜੇ',
'statistics-header-pages' => 'ਸਫ਼ਾ ਅੰਕੜੇ',
'statistics-header-edits' => 'ਸੋਧ ਅੰਕੜੇ',
'statistics-header-views' => 'ਵੇਖਣ ਅੰਕੜੇ',
'statistics-header-users' => 'ਯੂਜ਼ਰ ਅੰਕੜੇ',
'statistics-header-hooks' => 'ਹੋਰ ਅੰਕੜੇ',
'statistics-articles' => 'ਸਮੱਗਰੀ ਸਫ਼ੇ',
'statistics-pages' => 'ਸਫ਼ੇ',
'statistics-pages-desc' => 'ਇਸ ਵਿਕੀ ਦੇ ਸਾਰੇ ਸਫ਼ੇ, ਗੱਲ-ਬਾਤ ਸਫ਼ਿਆਂ, ਰੀਡਾਇਰੈਕਟਾਂ ਇਤਿਆਦਿ ਨੂੰ ਸ਼ਾਮਲ ਕਰਦੇ ਹੋਏ',
'statistics-files' => 'ਅਪਲੋਡ ਕੀਤੀਆਂ ਗਈਆਂ ਫਾਈਲਾਂ',
'statistics-edits-average' => 'ਪ੍ਰਤੀ ਸਫ਼ਾ ਔਸਤਨ ਸੋਧਾਂ',
'statistics-users' => 'ਰਜਿਸਟ੍ਰਡ [[Special:ListUsers|ਵਰਤੋਂਕਾਰ]]',
'statistics-users-active' => 'ਸਰਗਰਮ ਯੂਜ਼ਰ',
'statistics-users-active-desc' => 'ਮੈਂਬਰ, ਜਿੰਨ੍ਹਾ ਨੇ ਆਖ਼ਰੀ {{PLURAL:$1|ਦਿਨ|$1 ਦਿਨਾਂ}} ਵਿਚ ਕੋਈ ਕੰਮ ਕੀਤਾ ਹੈ।',
'statistics-mostpopular' => 'ਸਭ ਤੋਂ ਵੱਧ ਵੇਖੇ ਸਫ਼ੇ',

'disambiguationspage' => 'Template:ਗੁੰਝਲ ਖੋਲ੍ਹ',

'pageswithprop-submit' => 'ਜਾਉ',

'doubleredirects' => 'ਦੋਹਰੇ ਰੀਡਿਰੈਕਟ',

'brokenredirectstext' => 'ਇਹ ਰਿਡਿਰੈਕਟ ਨਾ-ਮੌਜੂਦ ਸਫ਼ਿਆਂ ’ਤੇ ਜੋੜਦੇ ਹਨ:',
'brokenredirects-edit' => 'ਸੋਧ',
'brokenredirects-delete' => 'ਹਟਾਓ',

'withoutinterwiki' => 'ਬਿਨਾਂ ਬੋਲੀ ਲਿੰਕਾਂ ਦੇ ਸਫ਼ੇ',
'withoutinterwiki-summary' => 'ਇਹ ਸਫ਼ੇ ਹੋਰ ਬੋਲੀਆਂ ਵਾਲ਼ੇ ਵਰਜਨਾਂ ਨਾਲ਼ ਨਹੀਂ ਜੁੜਦੇ।',
'withoutinterwiki-legend' => 'ਅਗੇਤਰ',
'withoutinterwiki-submit' => 'ਵੇਖਾਓ',

'fewestrevisions' => 'ਸਭ ਤੋਂ ਘੱਟ ਰੀਵਿਜ਼ਨਾਂ ਵਾਲ਼ੇ ਸਫ਼ੇ',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|ਬਾਈਟ|ਬਾਈਟ}}',
'ncategories' => '$1 {{PLURAL:$1|ਕੈਟੇਗਰੀ|ਕੈਟੇਗਰੀਆਂ}}',
'nlinks' => '$1 {{PLURAL:$1|ਲਿੰਕ|ਲਿੰਕ}}',
'nmembers' => '$1 {{PLURAL:$1|ਮੈਂਬਰ|ਮੈਂਬਰਾਂ}}',
'nrevisions' => '$1 {{PLURAL:$1|ਰੀਵਿਜ਼ਨ|ਰੀਵਿਜ਼ਨਾਂ}}',
'nviews' => '$1 {{PLURAL:$1|ਝਲਕ|ਝਲਕ}}',
'nimagelinks' => '$1 {{PLURAL:$1|ਸਫ਼ੇ|ਸਫ਼ਿਆਂ}} ’ਤੇ ਵਰਤਿਆ ਹੋਇਆ',
'ntransclusions' => '$1 {{PLURAL:$1|ਸਫ਼ੇ|ਸਫ਼ਿਆਂ}} ’ਤੇ ਵਰਤਿਆ ਹੋਇਆ',
'specialpage-empty' => 'ਇਸ ਰਿਪੋਟ ਦਾ ਕੋਈ ਨਤੀਜਾ ਨਹੀਂ ਹੈ।',
'lonelypages' => 'ਯਤੀਮ ਸਫ਼ੇ',
'uncategorizedpages' => 'ਬਗ਼ੈਰ ਕੈਟੇਗਰੀਆਂ ਵਾਲ਼ੇ ਸਫ਼ੇ',
'uncategorizedcategories' => 'ਬਗ਼ੈਰ ਕੈਟੇਗਰੀਆਂ ਵਾਲ਼ੀਆਂ ਕੈਟੇਗਰੀਆਂ',
'uncategorizedimages' => 'ਬਗ਼ੈਰ ਕੈਟੇਗਰੀਆਂ ਵਾਲ਼ੀਆਂ ਫ਼ਾਈਲਾਂ',
'uncategorizedtemplates' => 'ਬਗ਼ੈਰ ਕੈਟੇਗਰੀਆਂ ਵਾਲ਼ੇ ਸਾਂਚੇ',
'unusedcategories' => 'ਅਣਵਰਤੀਆਂ ਕੈਟਾਗਰੀਆਂ',
'unusedimages' => 'ਅਣਵਰਤੀਆਂ ਫਾਇਲਾਂ',
'popularpages' => 'ਹਰਮਨਪਿਆਰੇ ਸਫ਼ੇ',
'wantedcategories' => 'ਚਾਹੀਦੀਆਂ ਕੈਟੇਗਰੀਆਂ',
'wantedpages' => 'ਚਾਹੀਦੇ ਸਫ਼ੇ',
'wantedfiles' => 'ਚਾਹੀਦੀਆਂ ਫਾਇਲਾਂ',
'wantedtemplates' => 'ਚਾਹੀਦੇ ਟੈਪਲੇਟ',
'mostcategories' => 'ਸਭ ਤੋਂ ਵੱਧ ਕੈਟੇਗਰੀਆਂ ਵਾਲ਼ੇ ਸਫ਼ੇ',
'prefixindex' => 'ਇਸ ਅਗੇਤਰ ਵਾਲੇ ਸਾਰੇ ਪੰਨੇ',
'shortpages' => 'ਛੋਟੇ ਸਫ਼ੇ',
'listusers' => 'ਯੂਜ਼ਰ ਲਿਸਟ',
'usercreated' => '$1 ਨੂੰ $2 ’ਤੇ {{GENDER:$3|ਬਣਾਇਆ}}',
'newpages' => 'ਨਵੇਂ ਸਫ਼ੇ',
'newpages-username' => 'ਯੂਜ਼ਰ-ਨਾਂ:',
'ancientpages' => 'ਸਭ ਤੋਂ ਪੁਰਾਣੇ ਪੇਜ',
'move' => 'ਸਿਰਲੇਖ ਬਦਲੋ',
'movethispage' => 'ਇਹ ਸਫ਼ਾ ਭੇਜੋ',
'unusedcategoriestext' => 'ਇਹ ਕੈਟੇਗਰੀ ਸਫ਼ੇ ਮੌਜੂਦ ਹਨ ਹਾਲਾਂਕਿ ਕਿਸੇ ਵੀ ਸਫ਼ੇ ਜਾਂ ਕੈਟੇਗਰੀ ਨੇ ਇਹਨਾਂ ਦੀ ਵਰਤੋਂ ਨਹੀਂ ਕੀਤੀ।',
'notargettitle' => 'ਟਾਰਗੇਟ ਨਹੀਂ',
'pager-newer-n' => '{{PLURAL:$1|1 ਨਵਾਂ|$1 ਨਵੇਂ}}',
'pager-older-n' => '{{PLURAL:$1|1 ਪੁਰਾਣਾ|$1 ਪੁਰਾਣੇ}}',

# Book sources
'booksources' => 'ਪੁਸਤਕਾਂ ਦੇ ਸਰੋਤ',
'booksources-search-legend' => 'ਪੁਸਤਕਾਂ ਦੇ ਸਰੋਤ ਖੋਜੋ',
'booksources-go' => 'ਜਾਓ',
'booksources-invalid-isbn' => 'ਦਿੱਤਾ ਗਿਆ ISBN ਸਹੀ ਨਹੀਂ ਲਗਦਾ, ਅਸਲੀ ਸਰੋਤ ਤੋਂ ਨਕਲ ਕਰਦੇ ਵਕਤ ਹੋਈਆਂ ਗ਼ਲਤੀਆਂ ਜਾਂਚੋ।',

# Special:Log
'specialloguserlabel' => 'ਕਰਤਾ:',
'speciallogtitlelabel' => 'ਸਿਰਲੇਖ:',
'log' => 'ਚਿੱਠੇ',
'all-logs-page' => 'ਸਾਰੇ ਆਮ ਚਿੱਠੇ',
'logempty' => 'ਚਿੱਠੇ ’ਚ ਮੇਲ ਖਾਂਦੀ ਕੋਈ ਚੀਜ਼ ਨਹੀਂ ਹੈ।',
'log-title-wildcard' => 'ਇਸ ਲਿਖਤ ਨਾਲ਼ ਸ਼ੁਰੂ ਹੋਣ ਵਾਲ਼ੇ ਸਿਰਲੇਖ ਖੋਜੋ',
'showhideselectedlogentries' => 'ਚਿੱਠੇ ਦੇ ਚੁਣੇ ਹੋਏ ਦਾਖ਼ਲੇ ਵਖਾਓ/ਲੁਕਾਓ',

# Special:AllPages
'allpages' => 'ਸਭ ਸਫ਼ੇ',
'alphaindexline' => '$1 ਤੋਂ $2',
'nextpage' => 'ਅੱਗੇ ਸਫ਼ਾ ($1)',
'prevpage' => 'ਪਿੱਛੇ ਸਫ਼ਾ ($1)',
'allpagesfrom' => 'ਇਸਤੋਂ ਸ਼ੁਰੂ ਹੋਣ ਵਾਲ਼ੇ ਸਫ਼ੇ ਵਖਾਓ:',
'allpagesto' => 'ਇਸਤੇ ਖ਼ਤਮ ਹੋਣ ਵਾਲ਼ੇ ਸਫ਼ੇ ਵਖਾਓ:',
'allarticles' => 'ਸਭ ਸਫ਼ੇ',
'allinnamespace' => 'ਸਭ ਪੇਜ ($1 ਨੇਮਸਪੇਸ)',
'allnotinnamespace' => 'ਸਭ ਪੇਜ ($1 ਨੇਮਸਪੇਸ ਵਿੱਚ ਨਹੀਂ)',
'allpagesprev' => 'ਪਿੱਛੇ',
'allpagesnext' => 'ਅੱਗੇ',
'allpagessubmit' => 'ਜਾਓ',
'allpagesprefix' => 'ਇਸ ਅਗੇਤਰ ਵਾਲ਼ੇ ਸਫ਼ੇ ਵਖਾਓ:',
'allpages-bad-ns' => '{{SITENAME}} ’ਤੇ "$1" ਥਾਂ-ਨਾਮ ਨਹੀਂ ਹੈ।',
'allpages-hide-redirects' => 'ਰੀਡਿਰੈਕਟ ਲੁਕਾਓ',

# SpecialCachedPage
'cachedspecial-refresh-now' => 'ਸਭ ਤੋਂ ਨਵਾਂ ਵੇਖੋ।',

# Special:Categories
'categories' => 'ਸ਼੍ਰੇਣੀਆਂ',
'categoriesfrom' => 'ਇਸਤੋਂ ਸ਼ੁਰੂ ਹੋਣ ਵਾਲ਼ੀਆਂ ਕੈਟੇਗਰੀਆਂ ਵਖਾਓ:',
'special-categories-sort-count' => 'ਗਿਣਤੀ ਮੁਤਾਬਕ ਤਰਤੀਬ ਦੇਵੋ',
'special-categories-sort-abc' => 'ਅੱਖਰਾਂ ਮੁਤਾਬਕ ਤਰਤੀਬ ਦੇਵੋ',

# Special:DeletedContributions
'deletedcontributions' => 'ਮਿਟਾਏ ਹੋਏ ਮੈਂਬਰ ਯੋਗਦਾਨ',
'deletedcontributions-title' => 'ਮਿਟਾਏ ਹੋਏ ਮੈਂਬਰ ਯੋਗਦਾਨ',
'sp-deletedcontributions-contribs' => 'ਯੋਗਦਾਨ',

# Special:LinkSearch
'linksearch' => 'ਬਾਹਰੀ ਲਿੰਕ ਖੋਜ',
'linksearch-ns' => 'ਥਾਂ-ਨਾਮ:',
'linksearch-ok' => 'ਖੋਜ',
'linksearch-line' => '$2 ਵਿੱਚ $1 ਬਾਹਰੀ ਸਿਰਨਾਵਾਂ ਹੈ',

# Special:ListUsers
'listusersfrom' => 'ਇਸਤੋਂ ਸ਼ੁਰੂ ਹੋਣ ਵਾਲ਼ੇ ਮੈਂਬਰ ਵਖਾਓ:',
'listusers-submit' => 'ਵੇਖਾਓ',
'listusers-noresult' => 'ਕੋਈ ਯੂਜ਼ਰ ਨਹੀਂ ਲੱਭਿਆ।',
'listusers-blocked' => '(ਪਾਬੰਦੀਸ਼ੁਦਾ)',

# Special:ActiveUsers
'activeusers' => 'ਚੁਸਤ ਮੈਂਬਰਾਂ ਦੀ ਲਿਸਟ',
'activeusers-intro' => 'ਇਹ ਓਹਨਾਂ ਮੈਂਬਰਾਂ ਦੀ ਲਿਸਟ ਹੈ ਜਿੰਨ੍ਹਾਂ ਨੇ ਆਖ਼ਰੀ $1 {{PLURAL:$1|ਦਿਨ|ਦਿਨਾਂ}} ਵਿਚ ਕਿਸੇ ਤਰ੍ਹਾਂ ਦਾ ਕੋਈ ਕੰਮ ਕੀਤਾ ਹੈ।',
'activeusers-count' => 'ਆਖ਼ਰੀ {{PLURAL:$3|ਦਿਨ|$3 ਦਿਨਾਂ}} ਵਿਚ $1 {{PLURAL:$1|ਸੋਧ|ਸੋਧਾਂ}}',
'activeusers-from' => 'ਇਸਤੋਂ ਸ਼ੁਰੂ ਹੋਣ ਵਾਲ਼ੇ ਮੈਂਬਰ ਵਖਾਓ:',
'activeusers-hidebots' => 'ਬੋਟਾਂ ਨੂੰ ਲੁਕਾਓ',
'activeusers-hidesysops' => 'ਐਡਮਨਿਸਟ੍ਰੇਟਰ ਲੁਕਾਓ',
'activeusers-noresult' => 'ਕੋਈ ਮੈਂਬਰ ਨਹੀਂ ਲੱਭਿਆ।',

# Special:ListGroupRights
'listgrouprights-group' => 'ਗਰੁੱਪ',
'listgrouprights-rights' => 'ਹੱਕ',
'listgrouprights-helppage' => 'Help:ਗਰੁੱਪ ਹੱਕ',
'listgrouprights-members' => '(ਮੈਂਬਰਾਂ ਦੀ ਸੂਚੀ)',
'listgrouprights-addgroup-all' => 'ਸਾਰੇ ਗਰੁੱਪ ਜੋੜੋ',
'listgrouprights-removegroup-all' => 'ਸਾਰੇ ਗਰੁੱਪ ਹਟਾਓ',

# Email user
'mailnologin' => 'ਕੋਈ ਭੇਜਣ ਐਡਰੈੱਸ ਨਹੀਂ',
'mailnologintext' => 'ਦੂਜੇ ਮੈਂਬਰਾਂ ਨੂੰ ਈ-ਮੇਲ ਭੇਜਣ ਲਈ ਤੁਹਾਨੂੰ [[Special:UserLogin|ਲਾਗਇਨ]] ਹੋਣਾ ਅਤੇ ਆਪਣੀਆਂ [[Special:Preferences|ਪਸੰਦਾਂ]] ਵਿਚ ਇਕ ਸਹੀ ਈ-ਮੇਲ ਪਤਾ ਦੇਣਾ ਪਵੇਗਾ।',
'emailuser' => 'ਇਸ ਵਰਤੋਂਕਾਰ ਨੂੰ ਈ-ਮੇਲ ਭੇਜੋ',
'emailuser-title-target' => 'ਇਹ {{GENDER:$1|ਯੂਜ਼ਰ}} ਨੂੰ ਈਮੇਲ ਭੇਜੋ',
'emailuser-title-notarget' => 'ਯੂਜ਼ਰ ਨੂੰ ਈਮੇਲ',
'emailpage' => 'ਯੂਜ਼ਰ ਨੂੰ ਈਮੇਲ ਕਰੋ',
'defemailsubject' => '{{SITENAME}} ਈਮੇਲ',
'usermaildisabled' => 'ਮੈਂਬਰ ਈ-ਮੇਲ ਬੰਦ ਹੈ',
'usermaildisabledtext' => 'ਇਸ ਵਿਕੀ ’ਤੇ ਤੁਸੀਂ ਦੂਜੇ ਮੈਂਬਰਾਂ ਨੂੰ ਈ-ਮੇਲ ਨਹੀਂ ਭੇਜ ਸਕਦੇ',
'noemailtitle' => 'ਕੋਈ ਈਮੇਲ ਐਡਰੈੱਸ ਨਹੀਂ',
'noemailtext' => 'ਇਸ ਮੈਂਬਰ ਨੇ ਸਹੀ ਈ-ਮੇਲ ਪਤਾ ਨਹੀਂ ਦਿੱਤਾ ਹੋਇਆ।',
'nowikiemailtitle' => 'ਈ-ਮੇਲ ਦੀ ਇਜਾਜ਼ਤ ਨਹੀਂ ਹੈ',
'nowikiemailtext' => 'ਇਸ ਮੈਂਬਰ ਨੇ ਦੂਜੇ ਮੈਂਬਰਾਂ ਤੋਂ ਈ-ਮੇਲ ਨਾ ਪ੍ਰਾਪਤ ਕਰਨਾ ਚੁਣ ਰੱਖਿਆ ਹੈ।',
'emailnotarget' => 'ਪ੍ਰਾਪਤ ਕਰਤਾ ਦਾ ਨਾ-ਮੌਜੂਦ ਜਾਂ ਗ਼ਲਤ ਮੈਂਬਰ-ਨਾਂ।',
'emailtarget' => 'ਪ੍ਰਾਪਤ ਕਰਤਾ ਦਾ ਮੈਂਬਰ-ਨਾਂ ਦਾਖ਼ਲ ਕਰੋ',
'emailusername' => 'ਯੂਜ਼ਰ-ਨਾਂ:',
'emailusernamesubmit' => 'ਭੇਜੋ',
'email-legend' => 'ਕਿਸੇ ਦੂਜੇ {{SITENAME}} ਮੈਂਬਰ ਨੂੰ ਈ-ਮੇਲ ਭੇਜੋ',
'emailfrom' => 'ਵਲੋਂ:',
'emailto' => 'ਵੱਲ:',
'emailsubject' => 'ਵਿਸ਼ਾ:',
'emailmessage' => 'ਸੁਨੇਹਾ:',
'emailsend' => 'ਭੇਜੋ',
'emailccme' => 'ਸੁਨੇਹੇ ਦੀ ਇੱਕ ਕਾਪੀ ਮੈਨੂੰ ਵੀ ਭੇਜੋ।',
'emailccsubject' => '$1 ਨੂੰ ਭੇਜੇ ਤੁਹਾਡੇ ਸੁਨੇਹੇ ਦੀ ਨਕਲ: $2',
'emailsent' => 'ਈਮੇਲ ਭੇਜੀ ਗਈ',
'emailsenttext' => 'ਤੁਹਾਡੀ ਈਮੇਲ ਭੇਜੀ ਗਈ ਹੈ।',
'emailuserfooter' => 'ਇਹ ਈ-ਮੇਲ $1 ਨੇ {{SITENAME}} ’ਤੇ "ਇਸ ਮੈਂਬਰ ਨੂੰ ਈ-ਮੇਲ ਭੇਜੋ" ਸਹੂਲਤ ਜ਼ਰੀਏ $2 ਨੂੰ ਭੇਜੀ ਸੀ।',

# Watchlist
'watchlist' => 'ਨਿਗਰਾਨ-ਸੂਚੀ',
'mywatchlist' => 'ਨਿਗਰਾਨੀ-ਲਿਸਟ',
'watchlistfor2' => '$1 $2 ਲਈ',
'nowatchlist' => 'ਤੁਹਾਡੀ ਨਿਗਰਾਨੀ-ਲਿਸਟ ਵਿਚ ਕੋਈ ਚੀਜ਼ ਨਹੀਂ ਹੈ।',
'watchlistanontext' => 'ਆਪਣੀ ਨਿਗਰਾਨੀ-ਲਿਸਟ ਵਿਚਲੀਆਂ ਚੀਜ਼ਾਂ ਵੇਖਣ ਜਾਂ ਸੋਧਣ ਲਈ ਮਿਹਰਬਾਨੀ ਕਰਕੇ $1।',
'watchnologin' => 'ਲਾਗਇਨ ਨਹੀਂ',
'watchnologintext' => 'ਆਪਣੀ ਨਿਗਰਾਨੀ-ਲਿਸਟ ਵਿਚ ਫੇਰ-ਬਦਲ ਕਰਨ ਲਈ ਤੁਹਾਨੂੰ [[Special:UserLogin|ਲਾਗਇਨ]] ਕਰਨਾ ਪਵੇਗਾ।',
'addwatch' => 'ਨਿਗਰਾਨੀ-ਲਿਸਟ ’ਚ ਜੋੜੋ',
'addedwatchtext' => "ਪੰਨਾ \"[[:\$1]]\" ਤੁਹਾਡੀ [[Special:Watchlist|ਧਿਆਨਸੂਚੀ]] ’ਚ ਜੁੜ ਚੁੱਕਾ ਹੈ।
ਇਸ ਅਤੇ ਇਸਦੇ ਚਰਚਾ ਪੰਨੇ ’ਚ ਹੋਈਆਂ ਬਦਲੀਆਂ ਓਥੇ ਵਖਾਈ ਦੇਣਗੀਆਂ ਅਤੇ ਵੇਖਣ ਦੀ ਸੌਖ ਲਈ [[Special:RecentChanges|ਹਾਲ ਹੀ ’ਚ ਹੋਈਆਂ ਬਦਲੀਆਂ]] ਵਿੱਚ ਇਹ ਪੰਨਾ '''ਗੂੜ੍ਹਾ''' ਦਿਖਾਈ ਦੇਵੇਗਾ।",
'removewatch' => 'ਨਿਗਰਾਨੀ-ਲਿਸਟ ’ਚੋਂ ਹਟਾਓ',
'removedwatchtext' => 'ਸਫ਼ਾ "[[:$1]]" [[Special:Watchlist|ਤੁਹਾਡੀ ਨਿਗਰਾਨੀ-ਲਿਸਟ]] ’ਚੋਂ ਹਟ ਚੁੱਕਾ ਹੈ।',
'watch' => 'ਧਿਆਨ ਰੱਖੋ',
'watchthispage' => 'ਇਹ ਪੇਜ ਵਾਚ ਕਰੋ',
'unwatch' => 'ਧਿਆਨ ਹਟਾਓ',
'unwatchthispage' => 'ਨਜ਼ਰ ਰੱਖਣੀ ਬੰਦ ਕਰੋ',
'notanarticle' => 'ਕੋਈ ਸਮੱਗਰੀ ਸਫ਼ਾ ਨਹੀਂ ਹੈ',
'notvisiblerev' => 'ਇੱਕ ਵੱਖਰੇ ਮੈਂਬਰ ਦੀ ਬਣਾਈ ਆਖ਼ਰੀ ਰੀਵਿਜ਼ਨ ਮਿਟਾਈ ਜਾ ਚੁੱਕੀ ਹੈ',
'watchnochange' => 'ਵਖਾਏ ਜਾ ਰਹੇ ਸਮੇਂ ਅੰਦਰ ਤੁਹਾਡੀ ਨਿਗਰਾਨੀ-ਲਿਸਟ ਵਿਚਲਾ ਕੋਈ ਵੀ ਸਫ਼ਾ ਸੋਧਿਆ ਨਹੀਂ ਗਿਆ।',
'watchlist-details' => 'ਚਰਚਾ ਪੰਨੇ ਨਾ ਗਿਣਦੇ ਹੋਏ, ਤੁਹਾਡੀ ਧਿਆਨਸੂਚੀ ਵਿੱਚ{{PLURAL:$1|$1 ਪੰਨਾ ਹੈ|$1 ਪੰਨੇ ਹਨ}}।',
'watchlistcontains' => 'ਤੁਹਾਡੀ ਨਿਗਰਾਨੀ-ਲਿਸਟ ਵਿਚ $1 {{PLURAL:$1|ਸਫ਼ਾ ਹੈ|ਸਫ਼ੇ ਹਨ}}।',
'wlnote' => "$3, $4 ਮੁਤਾਬਕ ਆਖ਼ਰੀ {{PLURAL:$2|ਘੰਟੇ|'''$2''' ਘੰਟਿਆਂ}} ਵਿਚ {{PLURAL:
$1|ਤਬਦੀਲੀ ਹੋਈ|'''$1''' ਤਬਦੀਲੀਆਂ ਹੋਈਆਂ}}, ਹੇਠਾਂ ਵੇਖੋ।",
'wlshowlast' => 'ਪਿਛਲੇ $1 ਘੰਟੇ $2 ਦਿਨ $3 ਵਖਾਓ',
'watchlist-options' => 'ਨਿਗਰਾਨੀ-ਲਿਸਟ ਚੋਣਾਂ',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'ਨਿਗ੍ਹਾ (ਵਾਚ) ਰੱਖੀ ਜਾ ਰਹੀ ਹੈ...',
'unwatching' => 'ਨਿਗ੍ਹਾ ਰੱਖਣੀ (ਵਾਚ) ਬੰਦ ਕੀਤੀ ਜਾ ਰਹੀ ਹੈ..',

'enotif_impersonal_salutation' => '{{SITENAME}} ਯੂਜ਼ਰ',
'enotif_lastvisited' => 'ਤੁਹਾਡੀ ਆਖ਼ਰੀ ਆਮਦ ਤੋਂ ਲੈ ਕੇ ਹੋਈਆਂ ਤਬਦੀਲੀਆਂ ਵੇਖਣ ਲਈ $1 ਵੇਖੋ।',
'enotif_lastdiff' => 'ਇਸ ਤਬਦੀਲੀ ਨੂੰ ਵੇਖਣ ਲਈ $1 ਵੇਖੋ।',
'enotif_anon_editor' => 'ਅਗਿਆਤ ਯੂਜ਼ਰ $1',
'created' => 'ਬਣਾਇਆ',
'changed' => 'ਬਦਲਿਆ',

# Delete
'deletepage' => 'ਸਫ਼ਾ ਹਟਾਓ',
'confirm' => 'ਪੁਸ਼ਟੀ',
'excontent' => "ਸਮੱਗਰੀ ਸੀ: '$1'",
'exbeforeblank' => 'ਖ਼ਾਲੀ ਕਰਨ ਤੋਂ ਪਹਿਲਾਂ ਸਮੱਗਰੀ ਸੀ: "$1"',
'exblank' => 'ਪੇਜ ਖਾਲੀ ਹੈ',
'delete-confirm' => '"$1" ਹਟਾਓ',
'delete-legend' => 'ਹਟਾਓ',
'historywarning' => "'''ਖ਼ਬਰਦਾਰ:''' ਜੋ ਸਫ਼ਾ ਤੁਸੀਂ ਮਿਟਾਉਣ ਜਾ ਰਹੇ ਹੋ ਉਸਦਾ ਅਤੀਤ ਤਕਰੀਬਨ $1 {{PLURAL:$1|ਰੀਵਿਜ਼ਨ|ਰੀਵਿਜ਼ਨਾਂ}} ਦਾ ਹੈ:",
'actioncomplete' => 'ਕਾਰਵਾਈ ਪੂਰੀ ਹੋਈ',
'actionfailed' => 'ਕਾਰਵਾਈ ਨਾਕਾਮ',
'deletedtext' => '"$1" ਮਿਟਾਇਆ ਜਾ ਚੁੱਕਾ ਹੈ।
ਤਾਜ਼ੀਆਂ ਮਿਟਾਉਣਾਂ ਦੇ ਰਿਕਾਰਡ ਲਈ $2 ਵੇਖੋ।',
'dellogpage' => 'ਹਟਾਉਣ ਦਾ ਚਿੱਠਾ',
'dellogpagetext' => 'ਹੇਠਾਂ ਸਭ ਤੋਂ ਤਾਜ਼ਾ ਮਿਟਾਉਣਾਂ ਦੀ ਲਿਸਟ ਹੈ।',
'deletionlog' => 'ਮਿਟਾਉਣਾਂ ਦਾ ਚਿੱਠਾ',
'deletecomment' => 'ਕਾਰਨ:',
'deleteotherreason' => 'ਹੋਰ/ਵਾਧੂ ਕਾਰਨ:',
'deletereasonotherlist' => 'ਹੋਰ ਕਾਰਨ',
'deletereason-dropdown' => '*ਮਿਟਾਉਣ ਦੇ ਆਮ ਕਾਰਨ
**ਲੇਖਕ ਦੇ ਕਹਿਣ ’ਤੇ
**ਕਾਪੀਰਾਈਟ ਦੀ ਉਲੰਘਣਾ
**ਵੰਦਾਲਿਜ਼ਮ',
'delete-edit-reasonlist' => 'ਮਿਟਾਉਣ ਦੇ ਕਾਰਨ ਸੋਧੋ',

# Rollback
'rollback' => 'ਸੋਧਾਂ ਵਾਪਸ ਮੋੜੋ',
'rollback_short' => 'ਰੋਲਬੈਕ',
'rollbacklink' => 'ਵਾਪਸ ਮੋੜੋ',
'rollbacklinkcount' => '$1 {{PLURAL:$1|ਸੋਧ|ਸੋਧਾਂ}} ਵਾਪਸ ਮੋੜੋ',
'rollbacklinkcount-morethan' => '$1 ਤੋਂ ਜ਼ਿਆਦਾ {{PLURAL:$1|ਸੋਧ|ਸੋਧਾਂ}} ਵਾਪਸ ਮੋੜੋ',
'rollbackfailed' => 'ਰੋਲਬੈਕ ਫੇਲ੍ਹ',
'editcomment' => "ਸੋਧ ਸਾਰ ਸੀ: \"''\$1''\"",

# Protect
'protectlogpage' => 'ਸੁਰੱਖਿਆ ਚਿੱਠਾ',
'protectedarticle' => '"[[$1]]" ਸੁਰੱਖਿਅਤ ਕੀਤਾ',
'modifiedarticleprotection' => '"[[$1]]" ਦੀ ਸੁਰੱਖਿਆ ਬਦਲੀ',
'unprotectedarticle' => '"[[$1]]" ਤੋਂ ਸੁਰੱਖਿਆ ਹਟਾਈ',
'protect-title' => '"$1" ਦੀ ਸੁਰੱਖਿਆ ਬਦਲੋ',
'protect-title-notallowed' => '"$1" ਦਾ ਸੁਰੱਖਿਆ ਦਰਜਾ ਵੇਖੋ',
'prot_1movedto2' => '[[$1]] ਨੂੰ [[$2]] ’ਤੇ ਭੇਜਿਆ',
'protect-badnamespace-title' => 'ਨਾ-ਸੁਰੱਖਿਆਯੋਗ ਥਾਂ-ਨਾਮ',
'protect-badnamespace-text' => 'ਇਸ ਥਾਂ-ਨਾਮ ਵਿਚਲੇ ਸਫ਼ੇ ਸੁਰੱਖਿਅਤ ਨਹੀਂ ਕੀਤੇ ਜਾ ਸਕਦੇ।',
'protect-legend' => 'ਸੁਰੱਖਿਆ ਪੁਸ਼ਟੀ',
'protectcomment' => 'ਕਾਰਨ:',
'protectexpiry' => 'ਮਿਆਦ:',
'protect_expiry_invalid' => 'ਖ਼ਤਮ ਹੋਣ ਦਾ ਸਮਾਂ ਗ਼ਲਤ ਹੈ।',
'protect_expiry_old' => 'ਖ਼ਤਮ ਹੋਣ ਦਾ ਸਮਾਂ ਗੁਜ਼ਰਿਆ ਹੋਇਆ ਹੈ।',
'protect-text' => "ਇੱਥੇ ਸ਼ਾਇਦ ਤੁਸੀਂ ਸਫ਼ਾ '''$1''' ਦਾ ਸੁਰੱਖਿਆ ਦਰਜਾ ਵੇਖ ਅਤੇ ਬਦਲ ਸਕਦੇ ਹੋ।",
'protect-default' => 'ਸਭ ਯੂਜ਼ਰ ਮਨਜ਼ੂਰ',
'protect-fallback' => '"$1" ਅਧਿਕਾਰ ਲੋੜੀਦਾ ਹੈ',
'protect-level-autoconfirmed' => 'ਨਵੇਂ ਤੇ ਗੈਰ-ਰਜਿਸਟਰ ਵਰਤੋਂਕਾਰਾਂ ਉੱਤੇ ਪਾਬੰਦੀ',
'protect-level-sysop' => 'ਕੇਵਲ ਪਰਸ਼ਾਸ਼ਕ ਹੀ ਮਨਜ਼ੂਰ',
'protect-summary-cascade' => 'ਕਾਸਕੇਡਿੰਗ',
'protect-cascade' => 'ਇਸ ਸਫ਼ੇ ਵਿਚ ਸ਼ਾਮਲ ਸਫ਼ੇ ਸੁਰੱਖਿਅਤ ਕਰੋ (ਕਾਸਕੇਡਿੰਗ ਸੁਰੱਖਿਆ)',
'protect-cantedit' => 'ਤੁਸੀਂ ਇਸ ਸਫ਼ੇ ਦਾ ਸੁਰੱਖਿਆ ਦਰਜਾ ਨਹੀਂ ਬਦਲ ਸਕਦੇ ਕਿਉਂਕਿ ਤੁਹਾਨੂੰ ਇਸਨੂੰ ਸੋਧਣ ਦੀ ਇਜਾਜ਼ਤ ਨਹੀਂ ਹੈ।',
'protect-othertime' => 'ਹੋਰ ਸਮਾਂ:',
'protect-othertime-op' => 'ਹੋਰ ਸਮਾਂ',
'protect-existing-expiry' => 'ਖ਼ਤਮ ਹੋਣ ਦਾ ਮੌਜੂਦਾ ਸਮਾਂ: $2, $3',
'protect-otherreason' => 'ਹੋਰ/ਵਾਧੂ ਕਾਰਨ:',
'protect-otherreason-op' => 'ਹੋਰ ਕਾਰਨ',
'protect-edit-reasonlist' => 'ਸੁਰੱਖਿਆ ਦੇ ਕਾਰਨ ਸੋਧੋ',
'protect-expiry-options' => '੧ ਘੰਟਾ:1 hour,੧ ਦਿਨ:1 day,੧ ਹਫ਼ਤਾ:1 week,੨ ਹਫ਼ਤੇ:2 weeks,੧ ਮਹੀਨਾ:1 month,੩ ਮਹੀਨੇ:3 months,੬ ਮਹੀਨੇ:6 months,੧ ਸਾਲ:1 year,ਬੇਹੱਦ:infinite',
'restriction-type' => 'ਅਧਿਕਾਰ:',
'restriction-level' => 'ਪਾਬੰਦੀ ਪੱਧਰ:',
'minimum-size' => 'ਘੱਟੋ-ਘੱਟ ਆਕਾਰ',
'maximum-size' => 'ਵੱਧੋ-ਵੱਧ ਆਕਾਰ:',
'pagesize' => '(ਬਾਈਟ)',

# Restrictions (nouns)
'restriction-edit' => 'ਸੋਧ',
'restriction-move' => 'ਭੇਜੋ',
'restriction-create' => 'ਬਣਾਓ',
'restriction-upload' => 'ਅੱਪਲੋਡ',

# Restriction levels
'restriction-level-sysop' => 'ਪੂਰਾ ਸੁਰੱਖਿਅਤ',
'restriction-level-autoconfirmed' => 'ਅਰਧ-ਸੁਰੱਖਿਅਤ',
'restriction-level-all' => 'ਕੋਈ ਲੈਵਲ',

# Undelete
'undelete' => 'ਮਿਟਾਏ ਹੋਏ ਸਫ਼ੇ ਵੇਖੋ',
'undeletepage' => 'ਮਿਟਾਏ ਹੋਏ ਸਫ਼ੇ ਵੇਖੋ ਅਤੇ ਮੁੜ ਬਹਾਲ ਕਰੋ',
'viewdeletedpage' => 'ਮਿਟਾਏ ਹੋਏ ਸਫ਼ੇ ਵੇਖੋ',
'undelete-fieldset-title' => 'ਰੀਵਿਜ਼ਨਾਂ ਮੁੜ ਬਹਾਲ ਕਰੋ',
'undelete-nodiff' => 'ਕੋਈ ਪਿਛਲੀ ਰੀਵਿਜ਼ਨ ਨਹੀਂ ਲੱਭੀ',
'undeletebtn' => 'ਮੁੜ-ਸਟੋਰ',
'undeletelink' => 'ਵੇਖੋ/ਮੁੜ ਬਹਾਲ ਕਰੋ',
'undeleteviewlink' => 'ਵੇਖੋ',
'undeletereset' => 'ਮੁੜ-ਸੈੱਟ',
'undeletecomment' => 'ਕਾਰਨ:',
'undelete-header' => 'ਤਾਜ਼ੇ ਹਟਾਏ ਗਏ ਪੰਨਿਆਂ ਲਈ [[Special:Log/
delete|ਹਟਾਉਣ ਦਾ ਚਿੱਠਾ]] ਵੇਖੋ।',
'undelete-search-title' => 'ਮਿਟਾਏ ਹੋਏ ਸਫ਼ੇ ਖੋਜੋ',
'undelete-search-box' => 'ਮਿਟਾਏ ਹੋਏ ਸਫ਼ੇ ਖੋਜੋ',
'undelete-search-submit' => 'ਖੋਜ',
'undelete-show-file-submit' => 'ਹਾਂ',

# Namespace form on various pages
'namespace' => 'ਥਾਂ-ਨਾਮ:',
'invert' => 'ਉਲਟ ਚੋਣ',
'blanknamespace' => '(ਮੁੱਖ)',

# Contributions
'contributions' => '{{GENDER:$1|ਮੈਂਬਰ}} ਯੋਗਦਾਨ',
'contributions-title' => '$1 ਦੇ ਯੋਗਦਾਨ',
'mycontris' => 'ਯੋਗਦਾਨ',
'contribsub2' => '$1 ($2) ਲਈ',
'uctop' => '(ਟੀਸੀ)',
'month' => 'ਇਸ (ਅਤੇ ਪਿਛਲੇ) ਮਹੀਨੇ ਤੋਂ :',
'year' => 'ਇਸ (ਅਤੇ ਪਿਛਲੇ) ਸਾਲ ਤੋਂ :',

'sp-contributions-newbies' => 'ਸਿਰਫ਼ ਨਵੇਂ ਵਰਤੋਂਕਾਰਾਂ ਦੇ ਯੋਗਦਾਨ ਵਖਾਓ',
'sp-contributions-newbies-sub' => 'ਨਵੇਂ ਖਾਤਿਆਂ ਲਈ',
'sp-contributions-blocklog' => 'ਪਾਬੰਦੀ ਚਿੱਠਾ',
'sp-contributions-uploads' => 'ਅੱਪਲੋਡ',
'sp-contributions-logs' => 'ਲਾਗ',
'sp-contributions-talk' => 'ਚਰਚਾ',
'sp-contributions-blocked-notice' => 'ਇਹ ਮੈਂਬਰ ਇਸ ਵੇਲ਼ੇ ਪਾਬੰਦੀਸ਼ੁਦਾ ਹੈ।
ਪਾਬੰਦੀ ਚਿੱਠੇ ਦਾ ਤਾਜ਼ਾ ਦਾਖ਼ਲਾ ਹਵਾਲੇ ਲਈ ਹੇਠਾਂ ਦਿੱਤਾ ਗਿਆ ਹੈ:',
'sp-contributions-blocked-notice-anon' => 'ਇਹ IP ਪਤਾ ਇਸ ਵੇਲ਼ੇ ਪਾਬੰਦੀਸ਼ੁਦਾ ਹੈ।
ਪਾਬੰਦੀ ਚਿੱਠੇ ਦਾ ਤਾਜ਼ਾ ਦਾਖ਼ਲਾ ਹਵਾਲੇ ਲਈ ਹੇਠਾਂ ਦਿੱਤਾ ਗਿਆ ਹੈ:',
'sp-contributions-search' => 'ਯੋਗਦਾਨ ਖੋਜੋ',
'sp-contributions-username' => 'IP ਪਤਾ ਜਾਂ ਵਰਤੋਂਕਾਰਨਾਮ:',
'sp-contributions-toponly' => 'ਕੇਵਲ ਉਨ੍ਹਾਂ ਸੰਪਾਦਨਾਂ ਨੂੰ ਵਖਾਓ ਜੋ ਨਵੀਨਤਮ ਸੰਸ਼ੋਧਨ ਹਨ',
'sp-contributions-submit' => 'ਖੋਜ',

# What links here
'whatlinkshere' => 'ਕਿਹੜੇ (ਪੰਨੇ) ਇੱਥੇ ਜੋੜਦੇ ਹਨ',
'whatlinkshere-title' => '$1 ਨਾਲ ਜੋੜਨ ਵਾਲੇ ਪੰਨੇ',
'whatlinkshere-page' => 'ਪੰਨਾ:',
'linkshere' => "ਇਹ ਪੰਨੇ '''[[:$1]]''' ਨਾਲ ਜੋੜਦੇ ਹਨ:",
'nolinkshere' => "ਕੋਈ ਵੀ ਪੰਨਾ '''[[:$1]]''' ਨਾਲ ਨਹੀਂ ਜੋੜਦਾ।",
'isredirect' => 'ਰੀਡਿਰੈਕਟ ਪੰਨਾ',
'istemplate' => 'ਟਾਕਰਾ ਕਰੋ',
'isimage' => 'ਫਾਈਲ ਲਿੰਕ',
'whatlinkshere-prev' => '{{PLURAL:$1|ਪਿਛਲਾ|ਪਿਛਲੇ $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|ਅਗਲਾ|ਅਗਲੇ $1}}',
'whatlinkshere-links' => '← ਲਿੰਕ',
'whatlinkshere-hideredirs' => 'ਅਸਿੱਧੇ ਰਾਹ $1',
'whatlinkshere-hidetrans' => '$1 ਇੱਥੇ ਕੀ ਕੀ ਜੁੜਦਾ ਹੈ।',
'whatlinkshere-hidelinks' => '$1 ਲਿੰਕ',
'whatlinkshere-hideimages' => 'ਫ਼ਾਈਲ ਲਿੰਕ $1',
'whatlinkshere-filters' => 'ਫਿਲਟਰ',

# Block/unblock
'blockip' => 'ਪਾਬੰਦੀਸ਼ੁਦਾ ਵਰਤੋਂਕਾਰ',
'ipadressorusername' => 'IP ਐਡਰੈਸ ਜਾਂ ਯੂਜ਼ਰ ਨਾਂ:',
'ipbexpiry' => 'ਮਿਆਦ:',
'ipbreason' => 'ਕਾਰਨ:',
'ipbreasonotherlist' => 'ਹੋਰ ਕਾਰਨ',
'ipbsubmit' => 'ਇਹ ਯੂਜ਼ਰ ਲਈ ਪਾਬੰਦੀ',
'ipbother' => 'ਹੋਰ ਟਾਈਮ:',
'ipboptions' => '2 ਘੰਟੇ:2 hours, 1 ਦਿਨ:1 day, 3 ਦਿਨ:3 days, 1 ਹਫ਼ਤਾ:1 week, 2 ਹਫ਼ਤੇ:2 weeks, 2 ਮਹੀਨਾ:1 month, 3 ਮਹੀਨੇ:3 months, 6 ਮਹੀਨੇ:6 months, 1 ਸਾਲ:1 year, ਹਮੇਸ਼ਾ ਲਈ:infinite',
'ipbotheroption' => 'ਹੋਰ',
'ipbotherreason' => 'ਹੋਰ/ਆਮ ਕਾਰਨ:',
'ipbwatchuser' => 'ਇਸ ਮੈਂਬਰ ਦੇ ਮੈਂਬਰ ਅਤੇ ਗੱਲ-ਬਾਤ ਸਫ਼ਿਆਂ ਤੇ ਨਜ਼ਰ ਰੱਖੋ',
'ipb-confirm' => 'ਪਾਬੰਦੀ ਤਸਦੀਕ ਕਰੋ',
'badipaddress' => 'ਗਲਤ IP ਐਡਰੈੱਸ',
'blockipsuccesssub' => 'ਪਾਬੰਦੀ ਕਾਮਯਾਬ',
'ipb-edit-dropdown' => 'ਪਾਬੰਦੀ ਲਾਉਣ ਦੇ ਕਾਰਨ ਸੋਧੋ',
'ipb-unblock-addr' => '$1 ਤੋਂ ਪਾਬੰਦੀ ਹਟਾਓ',
'ipb-unblock' => 'ਇੱਕ ਯੂਜ਼ਰ ਨਾਂ ਜਾਂ IP ਐਡਰੈੱਸ ਅਣ-ਬਲਾਕ ਕਰੋ',
'ipb-blocklist' => 'ਮੌਜੂਦਾ ਪਾਬੰਦੀਆਂ ਵੇਖੋ',
'ipb-blocklist-contribs' => '$1 ਦੇ ਯੋਗਦਾਨ',
'unblockip' => 'ਯੂਜ਼ਰ ਅਣ-ਬਲਾਕ ਕਰੋ',
'ipusubmit' => 'ਇਹ ਪਾਬੰਦੀ ਹਟਾਓ',
'unblocked' => '[[User:$1|$1]] ਪਾਬੰਦੀ ਮੁਕਤ ਹੋ ਚੁੱਕਾ ਹੈ',
'unblocked-range' => '$1 ਪਾਬੰਦੀ ਮੁਕਤ ਹੋ ਚੁੱਕੀ ਹੈ',
'unblocked-id' => 'ਪਾਬੰਦੀ $1 ਹਟ ਚੁੱਕੀ ਹੈ',
'blocklist' => 'ਪਾਬੰਦੀਸ਼ੁਦਾ ਮੈਂਬਰ',
'ipblocklist' => 'ਪਾਬੰਦੀਸ਼ੁਦਾ ਵਰਤੋਂਕਾਰ',
'ipblocklist-legend' => 'ਪਾਬੰਦੀਸ਼ੁਦਾ ਮੈਂਬਰ ਲੱਭੋ',
'blocklist-userblocks' => 'ਖਾਤਾ ਪਾਬੰਦੀਆਂ ਲੁਕਾਓ',
'blocklist-tempblocks' => 'ਆਰਜ਼ੀ ਪਾਬੰਦੀਆਂ ਲੁਕਾਓ',
'blocklist-timestamp' => 'ਵਕਤ ਦੀ ਮੋਹਰ',
'blocklist-target' => 'ਨਿਸ਼ਾਨਾ',
'blocklist-by' => 'ਪਾਬੰਦੀ ਲਾਉਣ ਵਾਲ਼ਾ ਐਡਮਿਨ',
'blocklist-reason' => 'ਕਾਰਨ',
'ipblocklist-submit' => 'ਖੋਜ',
'ipblocklist-otherblocks' => 'ਹੋਰ {{PLURAL:$1|ਪਾਬੰਦੀ|ਪਾਬੰਦੀਆਂ}}',
'infiniteblock' => 'ਬੇਅੰਤ',
'expiringblock' => 'ਮਿਆਦ ਖ਼ਤਮ $1 ਨੂੰ $2 ’ਤੇ',
'anononlyblock' => 'anon. ਹੀ',
'createaccountblock' => 'ਖਾਤਾ ਬਣਾਉਣਾ ’ਤੇ ਪਾਬੰਦੀ ਹੈ',
'emailblock' => 'ਈਮੇਲ ਬਲਾਕ ਹੈ',
'blocklist-nousertalk' => 'ਆਪਣਾ ਗੱਲ-ਬਾਤ ਸਫ਼ਾ ਨਹੀਂ ਸੋਧ ਸਕਦਾ',
'ipblocklist-empty' => 'ਪਾਬੰਦੀ ਲਿਸਟ ਖ਼ਾਲੀ ਹੈ।',
'ipblocklist-no-results' => 'ਦਿੱਤੇ ਗਏ IP ਪਤੇ ਜਾਂ ਮੈਂਬਰ-ਨਾਂ ’ਤੇ ਪਾਬੰਦੀ ਨਹੀਂ ਹੈ।',
'blocklink' => 'ਪਾਬੰਦੀ ਲਾਓ',
'unblocklink' => 'ਪਾਬੰਦੀ ਰੱਦ ਕਰੋ',
'change-blocklink' => 'ਪਾਬੰਦੀ ਬਦਲੋ',
'contribslink' => 'ਯੋਗਦਾਨ',
'emaillink' => 'ਈ-ਮੇਲ ਭੇਜੋ',
'blocklogpage' => 'ਪਾਬੰਦੀ ਚਿੱਠਾ',
'blocklog-showlog' => 'ਇਸ ਮੈਂਬਰ ’ਤੇ ਪਹਿਲਾਂ ਪਾਬੰਦੀ ਲਾਈ ਗਈ ਸੀ।
ਪਾਬੰਦੀ ਦਾ ਚਿੱਠਾ ਹਵਾਲੇ ਲਈ ਹੇਠਾਂ ਦਿੱਤਾ ਗਿਆ ਹੈ:',
'blocklogentry' => '[[$1]] ’ਤੇ $2 ਲਈ ਪਾਬੰਦੀ ਲਾਈ। $3',
'unblocklogentry' => '$1 ਤੋਂ ਪਾਬੰਦੀ ਹਟਾਈ',
'block-log-flags-anononly' => 'ਸਿਰਫ਼ ਗੁੰਮਨਾਮ ਮੈਂਬਰ',
'block-log-flags-nocreate' => 'ਖਾਤਾ ਬਣਾਉਣ ’ਤੇ ਪਾਬੰਦੀ ਹੈ',
'block-log-flags-nousertalk' => 'ਆਪਣਾ ਗੱਲ-ਬਾਤ ਸਫ਼ਾ ਨਹੀਂ ਸੋਧ ਸਕਦਾ',
'block-log-flags-hiddenname' => 'ਮੈਂਬਰ-ਨਾਂ ਲੁਕਾਇਆ',
'ipb_expiry_invalid' => 'ਖ਼ਤਮ ਹੋਣ ਦਾ ਸਮਾਂ ਗ਼ਲਤ।',
'ipb_already_blocked' => '"$1" ਪਹਿਲਾਂ ਹੀ ਪਾਬੰਦੀਸ਼ੁਦਾ ਹੈ',
'ipb-needreblock' => '$1 ਪਹਿਲਾਂ ਹੀ ਪਾਬੰਦੀਸ਼ੁਦਾ ਹੈ। ਕੀ ਤੁਸੀਂ ਸੈਟਿੰਗਾਂ ਬਦਲਣੀਆਂ ਚਾਹੁੰਦੇ ਹੋ?',
'ipb-otherblocks-header' => 'ਹੋਰ {{PLURAL:$1|ਪਾਬੰਦੀ|ਪਾਬੰਦੀਆਂ}}',
'unblock-hideuser' => 'ਤੁਸੀਂ ਇਸ ਮੈਂਬਰ ’ਤੇ ਪਾਬੰਦੀ ਨਹੀਂ ਲਾ ਸਕਦੇ ਕਿਉਂਕਿ ਇਸਦਾ ਮੈਂਬਰ-ਨਾਂ ਲੁਕਾਇਆ ਹੋਇਆ ਹੈ।',
'ipb_cant_unblock' => 'ਗ਼ਲਤੀ: ਪਾਬੰਦੀ ਪਤਾ $1 ਨਹੀਂ ਲੱਭਿਆ। ਸ਼ਾਇਦ ਇਹ ਪਹਿਲਾਂ ਹੀ ਪਾਬੰਦੀ-ਮੁਕਤ ਹੋ ਚੁੱਕਾ ਹੈ।',
'blockme' => 'ਮੇਰੇ ’ਤੇ ਪਾਬੰਦੀ ਲਾਓ',
'proxyblocksuccess' => 'ਪੂਰਾ ਹੋਇਆ',
'cant-block-while-blocked' => 'ਤੁਸੀਂ ਦੂਜੇ ਮੈਂਬਰਾਂ ’ਤੇ ਪਾਬੰਦੀ ਨਹੀਂ ਲਾ ਸਕਦੇ ਜਦੋਂ ਤੁਸੀਂ ਖ਼ੁਦ ਪਾਬੰਦੀਸ਼ੁਦਾ ਹੋ।',
'ipbblocked' => 'ਤੁਸੀਂ ਦੂਜੇ ਮੈਂਬਰਾਂ ਨੂੰ ਪਾਬੰਦੀਸ਼ੁਦਾ ਜਾਂ ਪਾਬੰਦੀ-ਮੁਕਤ ਨਹੀਂ ਕਰ ਸਕਦੇ ਕਿਉਂਕਿ ਤੁਸੀਂ ਖ਼ੁਦ ਪਾਬੰਦੀਸ਼ੁਦਾ ਹੋ',
'ipbnounblockself' => 'ਤੁਹਾਨੂੰ ਖ਼ੁਦ ਨੂੰ ਪਾਬੰਦੀ-ਮੁਕਤ ਕਰਨ ਦੀ ਇਜਾਜ਼ਤ ਨਹੀਂ ਹੈ',

# Developer tools
'lockdb' => 'ਡਾਟਾਬੇਸ ਲਾਕ',

# Move page
'move-page-legend' => 'ਸਫ਼ਾ ਭੇਜੋ',
'movearticle' => 'ਸਫ਼ਾ ਭੇਜੋ:',
'movenologin' => 'ਲਾਗਇਨ ਨਹੀਂ ਹੋ',
'movenologintext' => 'ਇਕ ਸਫ਼ੇ ਦਾ ਸਿਰਲੇਖ ਬਦਲਣ ਲਈ ਤੁਸੀਂ ਰਜਿਸਟਰਡ ਮੈਂਬਰ ਹੋਣੇ ਚਾਹੀਦੇ ਹੋ ਅਤੇ [[Special:UserLogin|ਲਾਗਇਨ]] ਕੀਤਾ ਹੋਣਾ ਚਾਹੀਦਾ ਹੈ।',
'newtitle' => 'ਨਵੇਂ ਟਾਈਟਲ ਲਈ:',
'move-watch' => 'ਸਰੋਤ ਤੇ ਟਾਰਗੇਟ ਸਫ਼ੇ ਉੱਤੇ ਨਿਗਰਾਨੀ ਰੱਖੋ',
'movepagebtn' => 'ਸਫ਼ਾ ਭੇਜੋ',
'pagemovedsub' => 'ਭੇਜਣਾ ਸਫ਼ਲ ਰਿਹਾ',
'movepage-moved' => '\'\'\'"$1" ਨੂੰ  "$2"\'\'\' ਉੱਤੇ ਭੇਜਿਆ',
'movepage-moved-redirect' => 'ਇੱਕ ਰੀਡਿਰੈਕਟ ਬਣਾ ਦਿੱਤਾ ਗਿਆ।',
'articleexists' => 'ਇਸ ਨਾਮ ਦਾ ਸਫ਼ਾ ਪਹਿਲਾਂ ਹੀ ਮੌਜੂਦ ਹੈ ਜਾਂ ਤੁਹਾਡਾ ਚੁਣਿਆ ਹੋਇਆ ਨਾਮ ਸਹੀ ਨਹੀਂ ਹੈ।
ਮਿਹਰਬਾਨੀ ਕਰਕੇ ਕੋਈ ਹੋਰ ਨਾਮ ਚੁਣੋ।',
'movedto' => 'ਭੇਜਿਆ',
'movepage-page-moved' => 'ਸਫ਼ਾ $1 ਨੂੰ $2 ’ਤੇ ਭੇਜਿਆ ਜਾ ਚੁੱਕਾ ਹੈ।',
'movelogpage' => 'ਸਿਰਲੇਖ ਬਦਲੀ ਦਾ ਚਿੱਠਾ',
'movereason' => 'ਕਾਰਨ:',
'revertmove' => 'ਉਲਟਾਓ',
'delete_and_move' => 'ਹਟਾਓ ਅਤੇ ਮੂਵ ਕਰੋ',
'delete_and_move_confirm' => 'ਹਾਂ, ਸਫ਼ਾ ਮਿਟਾ ਦੇਵੋ',
'move-leave-redirect' => 'ਪਿੱਛੇ ਇਕ ਰੀਡਿਰੈਕਟ ਛੱਡੋ',

# Export
'export' => 'ਪੰਨੇ ਐਕਸਪੋਰਟ ਕਰੋ',
'exportcuronly' => 'ਸਿਰਫ਼ ਮੌਜੂਦਾ ਰੀਵਿਜ਼ਨ ਸ਼ਾਮਲ ਕਰੋ, ਸਾਰਾ ਅਤੀਤ ਨਹੀਂ',
'export-submit' => 'ਐਕਸਪੋਰਟ',
'export-addcat' => 'ਸ਼ਾਮਲ',
'export-addns' => 'ਸ਼ਾਮਲ',
'export-download' => 'ਫਾਇਲ ਵਜੋਂ ਸੰਭਾਲੋ',
'export-templates' => 'ਸਾਂਚੇ ਸ਼ਾਮਲ ਕਰੋ',

# Namespace 8 related
'allmessages' => 'ਸਿਸਟਮ ਸੁਨੇਹੇ',
'allmessagesname' => 'ਨਾਮ',
'allmessagesdefault' => 'ਡਿਫਾਲਟ ਪਾਠ',
'allmessagescurrent' => 'ਮੌਜੂਦਾ ਟੈਕਸਟ',
'allmessages-filter-legend' => 'ਫਿਲਟਰ',
'allmessages-filter-all' => 'ਸਭ',
'allmessages-language' => 'ਭਾਸ਼ਾ:',
'allmessages-filter-submit' => 'ਜਾਓ',

# Thumbnails
'thumbnail-more' => 'ਵਧਾਓ',
'filemissing' => 'ਫਾਇਲ ਗੁੰਮ ਹੈ',
'thumbnail_error' => 'ਨਮੂਨਾ ਬਣਾਉਣ ਵਿੱਚ ਗਲਤੀ ਹੋਈ ਹੈ: $1',

# Special:Import
'import' => 'ਪੇਜ ਇੰਪੋਰਟ ਕਰੋ',
'import-interwiki-submit' => 'ਇੰਪੋਰਟ',
'import-comment' => 'ਟਿੱਪਣੀ:',
'importstart' => 'ਪੇਜ ਇੰਪੋਰਟ ਕੀਤੇ ਜਾ ਰਹੇ ਹਨ...',
'import-revision-count' => '$1 {{PLURAL:$1|ਰੀਵਿਜ਼ਨ|ਰੀਵਿਜ਼ਨਾਂ}}',
'importfailed' => 'ਇੰਪੋਰਟ ਫੇਲ੍ਹ: $1',
'importnotext' => 'ਖਾਲੀ ਜਾਂ ਕੋਈ ਟੈਕਸਟ ਨਹੀਂ',
'importsuccess' => 'ਇੰਪੋਰਟ ਸਫ਼ਲ!',
'importnofile' => 'ਕੋਈ ਇੰਪੋਰਟ ਫਾਇਲ ਅੱਪਲੋਡ ਨਹੀਂ ਕੀਤੀ।',

# Import log
'importlogpage' => 'ਇੰਪੋਰਟ ਲਾਗ',
'import-logentry-upload-detail' => '$1 ਰੀਵਿਜਨ',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'ਤੁਹਾਡਾ ਵਰਤੋਂਕਾਰ ਪੰਨਾ',
'tooltip-pt-mytalk' => 'ਤੁਹਾਡਾ ਚਰਚਾ ਪੰਨਾ',
'tooltip-pt-preferences' => 'ਤੁਹਾਡੀਆਂ ਪਸੰਦਾਂ',
'tooltip-pt-watchlist' => 'ਓਹਨਾਂ ਪੰਨਿਆਂ ਦੀ ਸੂਚੀ ਜੋ ਤੁਸੀਂ ਤਬਦੀਲੀਆਂ ਲਈ ਵੇਖ ਰਹੇ ਹੋ',
'tooltip-pt-mycontris' => 'ਤੁਹਾਡੇ ਯੋਗਦਾਨਾਂ ਦੀ ਲਿਸਟ',
'tooltip-pt-login' => 'ਤੁਹਾਨੂੰ ਲਾਗਇਨ ਕਰਨ ਲਈ ਉਤਸ਼ਾਹਿਤ ਕੀਤਾ ਜਾਂਦਾ ਹੈ; ਪਰ ਇਹ ਕੋਈ ਲਾਜ਼ਮੀ ਨਹੀਂ',
'tooltip-pt-anonlogin' => 'ਤੁਹਾਨੂੰ ਲਾਗਇਨ ਕਰਨ ਲਈ ਉਤਸ਼ਾਹਿਤ ਕੀਤਾ ਜਾਂਦਾ ਹੈ; ਪਰ ਇਹ ਕੋਈ ਲਾਜ਼ਮੀ ਨਹੀਂ ਹੈ',
'tooltip-pt-logout' => 'ਲਾਗ ਆਉਟ',
'tooltip-ca-talk' => 'ਸਮਗੱਰੀ ਪੰਨੇ ਬਾਰੇ ਚਰਚਾ',
'tooltip-ca-edit' => 'ਤੁਸੀ ਇਹ ਪੰਨਾ ਬਦਲ ਸਕਦੇ ਹੋ। ਕਿਰਪਾ ਕਰਕੇ ਤਬਦੀਲੀ ਸੰਜੋਣ ਤੋਂ ਪਹਿਲਾਂ ਝਲਕ ਵੇਖੋ।',
'tooltip-ca-addsection' => 'ਨਵਾਂ ਭਾਗ ਸ਼ੁਰੂ ਕਰੋ',
'tooltip-ca-viewsource' => 'ਇਹ ਪੰਨਾ ਸੁਰੱਖਿਅਤ ਹੈ।
ਤੁਸੀਂ ਇਸਦਾ ਸਰੋਤ ਵੇਖ ਸਕਦੇ ਹੋ।',
'tooltip-ca-history' => 'ਇਸ ਪੰਨੇ ਦੇ ਪਿਛਲੇ ਰੀਵਿਜਨ',
'tooltip-ca-protect' => 'ਇਹ ਪੰਨਾ ਸੁਰੱਖਿਅਤ ਕਰੋ',
'tooltip-ca-unprotect' => 'ਇਸ ਸਫ਼ੇ ਦੀ ਸੁਰੱਖਿਆ ਬਦਲੋ',
'tooltip-ca-delete' => 'ਇਹ ਪੰਨਾ ਨੂੰ ਹਟਾਓ',
'tooltip-ca-move' => 'ਇਹ ਪੰਨਾ ਮੁੰਤਕਿਲ ਕਰੋ',
'tooltip-ca-watch' => 'ਇਹ ਪੰਨਾ ਆਪਣੀ ਧਿਆਨਸੂਚੀ ਵਿੱਚ ਸ਼ਾਮਲ ਕਰੋ',
'tooltip-ca-unwatch' => 'ਇਹ ਪੰਨਾ ਆਪਣੀ ਧਿਆਨਸੂਚੀ ’ਚੋਂ ਹਟਾਓ',
'tooltip-search' => '{{SITENAME}} ’ਤੇ ਖੋਜੋ',
'tooltip-search-go' => 'ਠੀਕ ਇਸ ਨਾਮ ਵਾਲੇ ਪੰਨੇ ’ਤੇ ਜਾਉ, ਜੇ ਮੌਜੂਦ ਹੈ ਤਾਂ',
'tooltip-search-fulltext' => 'ਇਸ ਲਿਖਤ ਲਈ ਪੰਨੇ ਲੱਭੋ',
'tooltip-p-logo' => 'ਮੁੱਖ ਪੰਨੇ ’ਤੇ ਜਾਓ',
'tooltip-n-mainpage' => 'ਮੁੱਖ ਪੰਨੇ ’ਤੇ ਜਾਓ',
'tooltip-n-mainpage-description' => 'ਮੁੱਖ ਪੰਨੇ ’ਤੇ ਜਾਓ',
'tooltip-n-portal' => 'ਪ੍ਰੋਜੈਕਟ ਬਾਰੇ, ਤੁਸੀਂ ਕੀ ਕਰ ਸਕਦੇ ਹੋ, ਕਿੱਥੇ ਕੁਝ ਲੱਭਣਾ ਹੈ',
'tooltip-n-currentevents' => 'ਮੌਜੂਦਾ ਸਮਾਗਮ ਬਾਰੇ ਪਿਛਲੀ ਜਾਣਕਾਰੀ ਲੱਭੋ',
'tooltip-n-recentchanges' => 'ਵਿਕੀ ਵਿੱਚ ਹਾਲ ’ਚ ਹੋਈਆਂ ਬਦਲੀਆਂ ਦੀ ਸੂਚੀ',
'tooltip-n-randompage' => 'ਇੱਕ ਰਲਵਾਂ ਪੰਨਾ ਲੋਡ ਕਰੋ',
'tooltip-n-help' => 'ਖੋਜਣ ਲਈ ਥਾਂ',
'tooltip-t-whatlinkshere' => 'ਵਿਕੀ ਦੇ ਸਾਰੇ ਪੰਨਿਆਂ ਦੀ ਸੂਚੀ, ਜੋ ਇੱਥੇ ਜੋੜਦੇ ਹਨ',
'tooltip-t-recentchangeslinked' => 'ਇਸ ਪੰਨੇ ਤੋਂ ਲਿੰਕ ਕੀਤੇ ਪੰਨਿਆਂ ਵਿੱਚ ਤਾਜ਼ਾ ਤਬਦੀਲੀਆਂ',
'tooltip-feed-atom' => 'ਇਸ ਪੰਨੇ ਦੀ ਐਟਮ ਫ਼ੀਡ',
'tooltip-t-contributions' => 'ਇਸ ਵਰਤੋਂਕਾਰ ਦੇ ਯੋਗਦਾਨ ਦੀ ਸੂਚੀ',
'tooltip-t-emailuser' => 'ਇਸ ਵਰਤੋਂਕਾਰ ਨੂੰ ਈ-ਮੇਲ ਭੇਜੋ',
'tooltip-t-upload' => 'ਤਸਵੀਰ ਜਾਂ ਮੀਡੀਆ ਫ਼ਾਈਲਾਂ ਅਪਲੋਡ ਕਰੋ',
'tooltip-t-specialpages' => 'ਸਾਰੇ ਖ਼ਾਸ ਪੰਨਿਆਂ ਦੀ ਲਿਸਟ',
'tooltip-t-print' => 'ਇਹ ਪੰਨੇ ਦਾ ਛਪਣਯੋਗ ਵਰਜਨ',
'tooltip-t-permalink' => 'ਪੰਨੇ ਦੇ ਇਸ ਰੀਵਿਜਨ ਲਈ ਪੱਕਾ ਲਿੰਕ',
'tooltip-ca-nstab-main' => 'ਸਮੱਗਰੀ ਪੰਨਾ ਵੇਖੋ',
'tooltip-ca-nstab-user' => 'ਵਰਤੋਂਕਾਰ ਪੰਨਾ ਵੇਖੋ',
'tooltip-ca-nstab-media' => 'ਮੀਡਿਆ ਪੇਜ ਵੇਖੋ',
'tooltip-ca-nstab-special' => 'ਇਹ ਵਿਸ਼ੇਸ਼ ਪੰਨਾ ਹੈ, ਤੁਸੀਂ ਇਸ ਪੰਨੇ ਨੂੰ ਬਦਲ ਨਹੀਂ ਸਕਦੇ।',
'tooltip-ca-nstab-project' => 'ਪ੍ਰੋਜੈਕਟ ਪੰਨਾ ਵੇਖੋ',
'tooltip-ca-nstab-image' => 'ਫਾਈਲ ਪੰਨਾ ਵੇਖੋ',
'tooltip-ca-nstab-mediawiki' => 'ਸਿਸਟਮ ਸੁਨੇਹੇ ਵੇਖੋ',
'tooltip-ca-nstab-template' => 'ਸਾਂਚਾ ਵੇਖੋ',
'tooltip-ca-nstab-help' => 'ਮਦਦ ਪੰਨਾ ਵੇਖੋ',
'tooltip-ca-nstab-category' => 'ਸ਼੍ਰੇਣੀ ਪੰਨਾ ਵੇਖੋ',
'tooltip-minoredit' => 'ਇਸ ’ਤੇ ਬਤੌਰ ਛੋਟਾ ਬਦਲਾਵ ਨਿਸ਼ਾਨ ਲਾਓ',
'tooltip-save' => 'ਆਪਣੀਆਂ ਤਬਦੀਲੀਆਂ ਸਾਂਭੋ',
'tooltip-preview' => 'ਆਪਣੇ ਬਦਲਾਵ ਦੀ ਝਲਕ ਵੇਖੋ, ਸਾਂਭਣ ਤੋਂ ਪਹਿਲਾਂ ਇਹ ਵਰਤੋਂ!',
'tooltip-diff' => 'ਤੁਹਾਡੇ ਦੁਆਰਾ ਲਿਖਤ ਵਿੱਚ ਕੀਤੀਆਂ ਤਬਦੀਲੀਆਂ ਵਖਾਉਂਦਾ ਹੈ',
'tooltip-compareselectedversions' => 'ਇਸ ਪੰਨੇ ਦੇ ਦੋ ਚੁਣੇ ਹੋਏ ਸੋਧਾਂ ਵਿੱਚ ਫ਼ਰਕ ਵੇਖੋ',
'tooltip-watch' => 'ਇਸ ਪੰਨੇ ਨੂੰ ਆਪਣੀ ਧਿਆਨਸੂਚੀ ਵਿੱਚ ਪਾਓ',
'tooltip-watchlistedit-normal-submit' => 'ਸਿਰਲੇਖ ਹਟਾਓ',
'tooltip-watchlistedit-raw-submit' => 'ਨਿਗਰਾਨੀ-ਲਿਸਟ ਤਾਜ਼ਾ ਕਰੋ',
'tooltip-upload' => 'ਅਪਲੋਡ ਸ਼ੁਰੂ ਕਰੋ',
'tooltip-rollback' => "''ਵਾਪਸ ਮੋੜੋ'' ਇੱਕ ਹੀ ਕਲਿੱਕ ਨਾਲ ਆਖਰੀ ਯੋਗਦਾਨ ਨੂੰ ਰੱਦ ਕਰ ਦਿੰਦਾ ਹੈ",
'tooltip-undo' => '"ਉਧੇੜਨਾ" ਇਸ ਬਦਲਾਵ ਨੂੰ ਰੱਦ ਕਰਕੇ ਸੋਧ ਫ਼ਾਰਮ ਨੂੰ ਝਲਕ ਦੇ ਸ਼ੈਲੀ ਵਿੱਚ ਦਿਖਾਉਂਦਾ ਹੈ।
ਇੰਝ "ਸਾਰ" ਵਿੱਚ ਬਦਲਾਵ ਨਕਾਰਨ ਦਾ ਕਾਰਨ ਲਿਖਿਆ ਜਾ ਸਕਦਾ ਹੈ।',
'tooltip-preferences-save' => 'ਪਸੰਦ ਸੰਭਾਲੋ',
'tooltip-summary' => 'ਸੰਖੇਪ ਸਾਰ ਦਰਜ ਕਰੋ',

# Attribution
'lastmodifiedatby' => 'ਇਹ ਸਫ਼ਾ ਆਖ਼ਰੀ ਵਾਰ $1 ਨੂੰ $2 ’ਤੇ $3 ਨੇ ਸੋਧਿਆ ਸੀ।',
'others' => 'ਹੋਰ',
'siteusers' => '{{SITENAME}} {{PLURAL:$2|ਵਰਤੋਂਕਾਰ|ਵਰਤੋਂਕਾਰਾਂ}} $1',
'creditspage' => 'ਪੰਨਾ ਮਾਣ',

# Spam protection
'spamprotectiontitle' => 'Spam ਸੁਰੱਖਿਆ ਫਿਲਟਰ',

# Info page
'pageinfo-header-edits' => 'ਸੋਧਾਂ ਦਾ ਅਤੀਤ',
'pageinfo-article-id' => 'ਪੰਨਾ ਆਈ.ਡੀ',
'pageinfo-watchers' => 'ਸਫ਼ੇ ’ਤੇ ਨਜ਼ਰ ਰੱਖਣ ਵਾਲਿਆਂ ਦੀ ਗਿਣਤੀ',
'pageinfo-edits' => 'ਕੁੱਲ ਸੋਧਾਂ',

# Skin names
'skinname-monobook' => 'ਮੋਨੋਬੁੱਕ',

# Browsing diffs
'previousdiff' => '← ਪੁਰਾਣੀ ਤਬਦੀਲੀ',
'nextdiff' => 'ਨਵੀਂ ਤਬਦੀਲੀ →',

# Media information
'thumbsize' => 'ਥੰਮਨੇਲ ਆਕਾਰ:',
'widthheightpage' => '$1 × $2, $3 {{PLURAL:$3|ਪੰਨਾ|ਪੰਨੇ}}',
'file-info' => 'ਫਾਇਲ ਆਕਾਰ: $1, MIME ਕਿਸਮ: $2',
'file-info-size' => '$1 × $2 ਪਿਕਸਲ, ਫ਼ਾਈਲ ਆਕਾਰ: $3, MIME ਕਿਸਮ: $4',
'file-nohires' => 'ਇਸ ਤੋਂ ਵੱਡੀ ਚਿੱਤਰ ਮੌਜੂਦ ਨਹੀਂ ਹੈ।',
'svg-long-desc' => 'SVG ਫ਼ਾਈਲ, ਆਮ ਤੌਰ ’ਤੇ $1 × $2 ਪਿਕਸਲ, ਫ਼ਾਈਲ ਦਾ ਅਕਾਰ: $3',
'show-big-image' => 'ਪੂਰਾ ਰੈਜੋਲੇਸ਼ਨ',

# Special:NewFiles
'newimages' => 'ਨਵੀਆਂ ਫ਼ਾਈਲਾਂ ਦੀ ਗੈਲਰੀ',
'noimages' => 'ਵੇਖਣ ਲਈ ਕੁਝ ਨਹੀਂ',
'ilsubmit' => 'ਖੋਜ',
'bydate' => 'ਮਿਤੀ ਨਾਲ',

# Bad image list
'bad_image_list' => 'ਤਰਤੀਬ ਇਸ ਤਰਾਂ ਹੈ:
ਸਿਰਫ਼ ਸੂਚੀ ਵਿਚਲੀਆਂ ਚੀਜ਼ਾਂ (* ਨਾਲ ਸ਼ੁਰੂ ਹੋਣ ਵਾਲੀਆਂ ਕਤਾਰਾਂ) ’ਤੇ ਹੀ ਗ਼ੌਰ ਕੀਤਾ ਜਾਵੇਗਾ।
ਲਾਈਨ ਵਿਚ ਪਹਿਲੀ ਕੜੀ ਗ਼ਲਤ ਫ਼ਾਈਲ ਦੀ ਕੜੀ ਹੋਣੀ ਚਾਹੀਦੀ ਹੈ। ਉਸ ਲਾਈਨ ’ਚ ਅੱਗੇ ਦਿਤੀਆਂ ਕੜੀਆਂ ਨੂੰ ਇਤਰਾਜ਼ਯੋਗ ਮੰਨਿਆ ਜਾਵੇਗਾ, ਭਾਵ ਉਹ ਪੰਨੇ ਜਿਨ੍ਹਾਂ ਵਿਚ ਫ਼ਾਈਲ ਕਿਸੇ ਲਾਈਨ ਵਿਚ ਸਥਿਤ ਹੋ ਸਕਦੀ ਹੈ।',

# Metadata
'metadata' => 'ਮੀਟਾਡੈਟਾ',
'metadata-help' => 'ਇਸ ਫ਼ਾਈਲ ਵਿੱਚ ਵਾਧੂ ਜਾਣਕਾਰੀਆਂ ਹਨ, ਜੋ ਸ਼ਾਇਦ ਉਸ ਕੈਮਰੇ ਜਾਂ ਸਕੈਨਰ ਦੀ ਦੇਣ ਹਨ ਜੋ ਇਸਨੂੰ ਬਣਾਉਣ ਲਈ ਵਰਤਿਆ ਗਿਆ। ਜੇ ਇਸ ਫ਼ਾਈਲ ਵਿੱਚ ਕੋਈ ਤਬਦੀਲੀ ਕੀਤੀ ਗਈ ਹੈ ਤਾਂ ਹੋ ਸਕਦਾ ਹੈ ਕੁਝ ਵੇਰਵੇ ਬਦਲੀ ਫ਼ਾਈਲ ਦਾ ਸਹੀ ਰੂਪਮਾਨ ਨਾ ਹੋਣ।',
'metadata-fields' => 'ਇਸ ਸੁਨੇਹੇ ਵਿੱਚ ਸੂਚੀਬੱਧ ਖੇਤਰ ਚਿੱਤਰ ਪੰਨੇ ’ਚ ਸ਼ਾਮਲ ਕੀਤੇ ਜਾਣਗੇ ਜੋ ਉਦੋਂ ਦਿੱਸਦੇ ਹਨ ਜਦੋ ਮੈਟਾਡੈਟਾ ਖਾਕਾ ਬੰਦ ਹੋਵੇ। ਬਾਕੀ ਉਂਞ ਹੀ ਲੁਕੇ ਹੋਣਗੇ।',

# Exif tags
'exif-imagewidth' => 'ਚੌੜਾਈ',
'exif-imagelength' => 'ਉਚਾਈ',
'exif-samplesperpixel' => 'ਭਾਗਾਂ ਦੀ ਗਿਣਤੀ',
'exif-imagedescription' => 'ਚਿੱਤਰ ਟਾਇਟਲ',
'exif-make' => 'ਕੈਮਰਾ ਨਿਰਮਾਤਾ',
'exif-model' => 'ਕੈਮਰਾ ਮਾਡਲ',
'exif-software' => 'ਵਰਤਿਆ ਸਾਫਟਵੇਅਰ',
'exif-artist' => 'ਲੇਖਕ',
'exif-copyright' => 'ਕਾਪੀਰਾਈਟ ਟਾਇਟਲ',
'exif-subjectarea' => 'ਵਿਸ਼ਾ ਖੇਤਰ',
'exif-gpsdatestamp' => 'GPS ਮਿਤੀ',

'exif-unknowndate' => 'ਅਣਜਾਣ ਮਿਤੀ',

'exif-exposureprogram-2' => 'ਸਧਾਰਨ ਪਰੋਗਰਾਮ',

'exif-meteringmode-0' => 'ਅਣਜਾਣ',
'exif-meteringmode-1' => 'ਔਸਤ',
'exif-meteringmode-5' => 'ਪੈਟਰਨ',
'exif-meteringmode-255' => 'ਹੋਰ',

'exif-lightsource-0' => 'ਅਣਜਾਣ',
'exif-lightsource-9' => 'ਵਧੀਆ ਮੌਸਮ',
'exif-lightsource-10' => 'ਬੱਦਲ ਵਾਲਾ ਮੌਸਮ',

'exif-focalplaneresolutionunit-2' => 'ਇੰਚ',

'exif-scenecapturetype-0' => 'ਸਟੈਂਡਰਡ',
'exif-scenecapturetype-1' => 'ਲੈਂਡਸਕੇਪ',
'exif-scenecapturetype-2' => 'ਪੋਰਟਰੇਟ',

'exif-subjectdistancerange-0' => 'ਅਣਜਾਣ',
'exif-subjectdistancerange-1' => 'ਮਾਈਕਰੋ',
'exif-subjectdistancerange-2' => 'ਝਲਕ ਬੰਦ ਕਰੋ',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'ਕਿਲੋਮੀਟਰ ਪ੍ਰਤੀ ਘੰਟਾ',
'exif-gpsspeed-m' => 'ਮੀਲ ਪ੍ਰਤੀ ਘੰਟਾ',

'exif-iimcategory-war' => 'ਯੁੱਧ, ਸੰਘਰਸ਼ ਅਤੇ ਅਸ਼ਾਂਤੀ',
'exif-iimcategory-wea' => 'ਮੌਸਮ',

'exif-urgency-normal' => 'ਸਧਾਰਨ ($1)',

# External editor support
'edit-externally' => 'ਬਾਹਰੀ ਐਪਲੀਕੇਸ਼ਨ ਵਰਤਦੇ ਹੋਏ ਇਸ ਫਾਈਲ ਨੂੰ ਸੋਧੋ',
'edit-externally-help' => '(ਹੋਰ ਜਾਣਕਾਰੀ ਲਈ [//www.mediawiki.org/wiki/Manual:External_editors setup instructions] ਵੇਖੋ)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'ਸਭ',
'namespacesall' => 'ਸਭ',
'monthsall' => 'ਸਭ',
'limitall' => 'ਸਭ',

# Email address confirmation
'confirmemail' => 'ਈ-ਮੇਲ ਪਤਾ ਤਸਦੀਕ ਕਰੋ',
'confirmemail_send' => 'ਇੱਕ ਪੁਸ਼ਟੀ ਕੋਡ ਭੇਜੋ',
'confirmemail_sent' => 'ਪੁਸ਼ਟੀ ਈਮੇਲ ਭੇਜੀ ਗਈ।',
'confirmemail_invalid' => 'ਗਲਤ ਪੁਸ਼ਟੀ ਕੋਡ ਹੈ। ਕੋਡ ਦੀ ਮਿਆਦ ਪੁੱਗੀ ਹੋ ਸਕਦੀ ਹੈ।',
'confirmemail_loggedin' => 'ਤੁਹਾਡਾ ਈ-ਮੇਲ ਪਤਾ ਹੁਣ ਤਸਦੀਕ ਹੋ ਚੁੱਕਾ ਹੈ।',
'confirmemail_subject' => '{{SITENAME}} ਈ-ਮੇਲ ਪਤਾ ਤਸਦੀਕ',

# Scary transclusion
'scarytranscludetoolong' => '[URL ਬਹੁਤ ਲੰਬਾ ਹੈ]',

# Delete conflict
'recreate' => 'ਮੁੜ-ਬਣਾਓ',

# action=purge
'confirm_purge_button' => 'ਠੀਕ ਹੈ',

# Multipage image navigation
'imgmultipageprev' => '← ਪਿਛਲਾ ਪੰਨਾ',
'imgmultipagenext' => 'ਅਗਲਾ ਪੰਨਾ →',
'imgmultigo' => 'ਜਾਓ!',
'imgmultigoto' => '$1 ਸਫ਼ੇ ਉੱਤੇ ਜਾਓ',

# Table pager
'table_pager_next' => 'ਅਗਲਾ ਪੰਨਾ',
'table_pager_prev' => 'ਪਿਛਲਾ ਪੰਨਾ',
'table_pager_first' => 'ਪਹਿਲਾ ਪੰਨਾ',
'table_pager_last' => 'ਆਖ਼ਰੀ ਪੰਨਾ',
'table_pager_limit' => 'ਹਰੇਕ ਪੇਜ ਲਈ $1 ਆਈਟਮਾਂ',
'table_pager_limit_label' => 'ਪ੍ਰਤੀ ਸਫ਼ਾ ਆਈਟਮਾਂ:',
'table_pager_limit_submit' => 'ਜਾਓ',
'table_pager_empty' => 'ਕੋਈ ਨਤੀਜਾ ਨਹੀਂ',

# Auto-summaries
'autosumm-blank' => 'ਸਫ਼ੇ ਨੂੰ ਖ਼ਾਲੀ ਕੀਤਾ',
'autosumm-new' => '"$1" ਨਾਲ਼ ਸਫ਼ਾ ਬਣਾਇਆ',

# Live preview
'livepreview-loading' => '…ਲੋਡ ਕੀਤਾ ਜਾ ਰਿਹਾ ਹੈ',
'livepreview-ready' => '…ਲੋਡ ਕੀਤਾ ਜਾ ਰਿਹਾ ਹੈ। ਤਿਆਰ!',

# Watchlist editor
'watchlistedit-normal-title' => 'ਨਿਗਰਾਨੀ-ਲਿਸਟ ਸੋਧੋ',
'watchlistedit-raw-titles' => 'ਟਾਇਟਲ:',
'watchlistedit-raw-submit' => 'ਨਿਗਰਾਨੀ-ਲਿਸਟ ਤਾਜ਼ੀ ਕਰੋ',
'watchlistedit-raw-added' => '{{PLURAL:$1|1 title was|$1 titles were}} ਸ਼ਾਮਲ:',
'watchlistedit-raw-removed' => '{{PLURAL:$1|1 title was|$1 titles were}} ਹਟਾਓ:',

# Watchlist editing tools
'watchlisttools-view' => 'ਸਬੰਧਤ ਤਬਦਲੀਆਂ ਵੇਖੋ',
'watchlisttools-edit' => 'ਧਿਆਨਸੂਚੀ ਵੇਖੋ ’ਤੇ ਸੰਪਾਦਨ ਕਰੋ',
'watchlisttools-raw' => 'ਕੱਚੀ ਧਿਆਨਸੂਚੀ ਸੰਪਾਦਨ ਕਰੋ',

# Core parser functions
'duplicate-defaultsort' => 'ਪੁਰਾਣੀ ਮੂਲ ਕਰਮਾਂਕਨ ਕੁੰਜੀ $1 ਦੇ ਬਜਾਏ ਹੁਣ ਮੂਲ ਕਰਮਾਂਕਨ ਕੁੰਜੀ $2 ਹੋਵੇਗੀ।',

# Special:Version
'version' => 'ਵਰਜਨ',
'version-other' => 'ਹੋਰ',

# Special:SpecialPages
'specialpages' => 'ਖ਼ਾਸ ਪੰਨੇ',
'specialpages-group-login' => 'ਲਾਗਇਨ / ਖਾਤਾ ਬਣਾਓ',
'specialpages-group-users' => 'ਵਰਤੋਂਕਾਰ ਅਤੇ ਹੱਕ',

# Special:BlankPage
'blankpage' => 'ਖ਼ਾਲੀ ਸਫ਼ਾ',

# External image whitelist
'external_image_whitelist' => " #ਇਸ ਲਾਈਨ ਨੂੰ ਇੰਝ ਹੀ ਰਹਿਣ ਦਿਓ <pre>
#ਹੇਠਾਂ ਓਹੀ ਐਕਸਪ੍ਰੈਸ਼ਨ ਪਾਓ (ਜਿਹੜਾ ਹਿੱਸਾ // ਦੇ ਵਿਚਾਲੇ ਹੈ)
#ਇਹ ਬਾਹਰੀ ਤਸਵੀਰਾਂ ਦੇ URLs (ਹੌਟਲਿੰਕਡ) ਨਾਲ ਮਿਲਣਗੀਆਂ
#ਜਿਹੜੀਆਂ ਮਿਲਣਗੀਆਂ ਓਹ ਬਤੌਰ ਤਸਵੀਰਾਂ ਦਿੱਸਣਗੀਆਂ ਨਹੀਂ ਤਾਂ ਤਸਵੀਰ ਦਾ ਸਿਰਫ਼ ਲਿੰਕ ਨਜ਼ਰ ਆਵੇਗਾ
#'#' ਨਾਲ਼ ਸ਼ੁਰੂ ਹੋਣ ਵਾਲ਼ੀਆਂ ਲਾਈਨਾਂ ਟਿੱਪਣੀਆਂ ਵਾਂਗ ਲਈਆਂ ਜਾਂਦੀਆਂ ਹਨ
#ਇਹ ਕੇਸ-ਇਨਸੈਂਸਟਿਵ ਹੈ

#ਸਾਰੇ ਰੈਜੈਕਸ ਫ਼ਰੈਗਮੈਂਟ ਇਸ ਲਾਈਨ ਤੋਂ ਉੱਪਰ ਪਾਓ। ਇਸ ਲਾਈਨ ਨੂੰ ਇੰਝ ਹੀ ਰਹਿਣ ਦਿਓ </pre>",

# Special:Tags
'tag-filter' => '[[Special:Tags|ਟੈਗ]] ਫਿਲਟਰ:',
'tags-tag' => 'ਟੈਗ ਦਾ ਨਾਮ',
'tags-edit' => 'ਸੋਧੋ',

# HTML forms
'htmlform-submit' => 'ਭੇਜੋ',
'htmlform-reset' => 'ਬਦਲਾਅ ਵਾਪਸ ਲਵੋ',
'htmlform-selectorother-other' => 'ਹੋਰ',

# New logging system
'logentry-move-move' => '$1 ਨੇ ਸਫ਼ਾ $3 ਨੂੰ $4 ’ਤੇ ਭੇਜਿਆ',
'logentry-newusers-newusers' => 'ਮੈਂਬਰ ਖਾਤਾ $1 ਬਣਾਇਆ ਗਿਆ',
'logentry-newusers-create' => 'ਵਰਤੋਂਕਾਰ ਖਾਤਾ $1 ਬਣਾਇਆ ਗਿਆ',
'logentry-newusers-create2' => 'ਵਰਤੋਂਕਾਰ ਖਾਤਾ $3 $1 ਦੁਆਰਾ ਬਣਾਇਆ ਗਿਆ ਸੀ',
'rightsnone' => '(ਕੋਈ ਨਹੀਂ)',

# Feedback
'feedback-subject' => 'ਵਿਸ਼ਾ:',
'feedback-message' => 'ਸੁਨੇਹਾ:',
'feedback-cancel' => 'ਰੱਦ ਕਰੋ',

# Search suggestions
'searchsuggest-search' => 'ਖੋਜ',

# Durations
'duration-seconds' => '$1 {{PLURAL:$1|ਸਕਿੰਟ|ਸਕਿੰਟ}}',
'duration-minutes' => '$1 {{PLURAL:$1|ਮਿੰਟ |ਮਿੰਟ }}',
'duration-hours' => '$1 {{PLURAL:$1|ਘੰਟਾ |ਘੰਟੇ }}',
'duration-days' => '$1 {{PLURAL:$1|ਦਿਨ |ਦਿਨ }}',
'duration-weeks' => '$1 {{PLURAL:$1|ਹਫ਼ਤਾ |ਹਫ਼ਤੇ }}',
'duration-years' => '$1 {{PLURAL:$1|ਸਾਲ |ਸਾਲ }}',
'duration-decades' => '$1 {{PLURAL:$1|ਦਹਾਕਾ  |ਦਹਾਕੇ }}',
'duration-centuries' => '$1 {{PLURAL:$1|ਸੌ |ਸੌ }}',
'duration-millennia' => '$1 {{PLURAL:$1|ਸਾਹਸ਼ਤਾਬਦੀ  |ਵਧੇਰੇ ਸਾਹਸ਼ਤਾਬਦੀ  }}',

);

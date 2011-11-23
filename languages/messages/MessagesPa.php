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
 * @author Gman124
 * @author Sukh
 * @author Ævar Arnfjörð Bjarmason
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'ਮੀਡੀਆ',
	NS_SPECIAL          => 'ਖਾਸ',
	NS_TALK             => 'ਚਰਚਾ',
	NS_USER             => 'ਮੈਂਬਰ',
	NS_USER_TALK        => 'ਮੈਂਬਰ_ਚਰਚਾ',
	NS_PROJECT_TALK     => '$1_ਚਰਚਾ',
	NS_FILE             => 'ਤਸਵੀਰ',
	NS_FILE_TALK        => 'ਤਸਵੀਰ_ਚਰਚਾ',
	NS_MEDIAWIKI        => 'ਮੀਡੀਆਵਿਕਿ',
	NS_MEDIAWIKI_TALK   => 'ਮੀਡੀਆਵਿਕਿ_ਚਰਚਾ',
	NS_TEMPLATE         => 'ਨਮੂਨਾ',
	NS_TEMPLATE_TALK    => 'ਨਮੂਨਾ_ਚਰਚਾ',
	NS_HELP             => 'ਮਦਦ',
	NS_HELP_TALK        => 'ਮਦਦ_ਚਰਚਾ',
	NS_CATEGORY         => 'ਸ਼੍ਰੇਣੀ',
	NS_CATEGORY_TALK    => 'ਸ਼੍ਰੇਣੀ_ਚਰਚਾ',
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
'tog-underline'            => 'ਅੰਡਰ-ਲਾਈਨ ਲਿੰਕ:',
'tog-numberheadings'       => 'ਆਟੋ-ਨੰਬਰ ਹੈਡਿੰਗ',
'tog-showtoolbar'          => 'ਐਡਿਟ ਟੂਲਬਾਰ ਵੇਖੋ (JavaScript)',
'tog-showtoc'              => 'ਟੇਬਲ ਆਫ਼ ਕੰਨਟੈੱਟ ਵੇਖਾਓ (for pages with more than 3 headings)',
'tog-rememberpassword'     => 'ਇਸ ਬਰਾਊਜ਼ਰ ਉੱਤੇ ਮੇਰਾ ਲਾਗਇਨ ਯਾਦ ਰੱਖੋ ($1 {{PLURAL:$1|ਦਿਨ|ਦਿਨਾਂ}} ਲਈ ਵੱਧ ਤੋਂ ਵੱਧ)',
'tog-watchcreations'       => 'ਮੇਰੇ ਵਲੋਂ ਬਣਾਏ ਗਏ ਨਵੇਂ ਸਫ਼ੇ ਮੇਰੀ ਵਾਚ-ਲਿਸਟ ਵਿੱਚ ਪਾਓ',
'tog-watchdefault'         => 'ਜੋ ਸਫ਼ੇ ਮੈਂ ਸੋਧਦਾ ਹਾਂ, ਓਹ ਪੇਜ ਮੇਰੀ ਵਾਚ-ਲਿਸਟ ਵਿੱਚ ਪਾਓ',
'tog-watchmoves'           => 'ਮੇਰੇ ਵਲੋਂ ਭੇਜੇ ਕਿਤੇ ਸਫ਼ੇ ਨੂੰ ਮੇਰੀ ਵਾਚ-ਲਿਸਟ ਵਿੱਚ ਪਾਓ',
'tog-watchdeletion'        => 'ਮੇਰੇ ਵਲੋਂ ਹਟਾਏ ਗਏ ਸਫ਼ੇ ਮੇਰੀ ਵਾਚ-ਲਿਸਟ ਵਿੱਚ ਪਾਓ',
'tog-previewontop'         => 'ਐਡਿਟ ਬਕਸੇ ਤੋਂ ਪਹਿਲਾਂ ਝਲਕ ਵੇਖਾਓ',
'tog-previewonfirst'       => 'ਪਹਿਲੇ ਐਡਿਟ ਉੱਤੇ ਝਲਕ ਵੇਖਾਓ',
'tog-nocache'              => 'ਬਰਾਊਜ਼ਰ ਸਫ਼ਾ ਕੈਸ਼ ਕਰਨਾ ਬੰਦ ਕਰੋ',
'tog-enotifwatchlistpages' => 'ਜਦੋਂ ਮੇਰੀ ਵਾਚ-ਲਿਸਟ ਵਿੱਚੋਂ ਸਫ਼ਾ ਬਦਲਿਆ ਜਾਵੇ ਤਾਂ ਮੈਨੂੰ ਈਮੇਲ ਭੇਜੋ',
'tog-watchlisthideown'     => 'ਮੇਰੀ ਵਾਚ-ਲਿਸਟ ਵਿੱਚੋਂ ਮੇਰੀਆਂ ਸੋਧਾਂ ਹਟਾਓ',
'tog-watchlisthidebots'    => 'ਮੇਰੀ ਵਾਚ-ਲਿਸਟ ਵਿੱਚੋਂ ਰੋਬਾਟ ਦਿਆਂ ਸੋਧਾਂ ਹਟਾਓ',
'tog-watchlisthideminor'   => 'ਛੋਟੇ ਸੋਧ ਵਾਚ-ਲਿਸਟ ਤੋਂ ਓਹਲੇ ਰੱਖੋ',

'underline-always'  => 'ਹਮੇਸ਼ਾਂ',
'underline-never'   => 'ਕਦੇ ਨਹੀਂ',
'underline-default' => 'ਬਰਾਊਜ਼ਰ ਡਿਫਾਲਟ',

# Dates
'sunday'        => 'ਐਤਵਾਰ',
'monday'        => 'ਸੋਮਵਾਰ',
'tuesday'       => 'ਮੰਗਲਵਾਰ',
'wednesday'     => 'ਬੁੱਧਵਾਰ',
'thursday'      => 'ਵੀਰਵਾਰ',
'friday'        => 'ਸ਼ੁੱਕਰਵਾਰ',
'saturday'      => 'ਸ਼ਨਿੱਚਰਵਾਰ',
'sun'           => 'ਐਤ',
'mon'           => 'ਸੋਮ',
'tue'           => 'ਮੰਗਲ',
'wed'           => 'ਬੁੱਧ',
'thu'           => 'ਵੀਰ',
'fri'           => 'ਸ਼ੁੱਕਰ',
'sat'           => 'ਸ਼ਨਿੱਚਰ',
'january'       => 'ਜਨਵਰੀ',
'february'      => 'ਫ਼ਰਵਰੀ',
'march'         => 'ਮਾਰਚ',
'april'         => 'ਅਪਰੈਲ',
'may_long'      => 'ਮਈ',
'june'          => 'ਜੂਨ',
'july'          => 'ਜੁਲਾਈ',
'august'        => 'ਅਗਸਤ',
'september'     => 'ਸਤੰਬਰ',
'october'       => 'ਅਕਤੂਬਰ',
'november'      => 'ਨਵੰਬਰ',
'december'      => 'ਦਸੰਬਰ',
'january-gen'   => 'ਜਨਵਰੀ',
'february-gen'  => 'ਫ਼ਰਵਰੀ',
'march-gen'     => 'ਮਾਰਚ',
'april-gen'     => 'ਅਪਰੈਲ',
'may-gen'       => 'ਮਈ',
'june-gen'      => 'ਜੂਨ',
'july-gen'      => 'ਜੁਲਾਈ',
'august-gen'    => 'ਅਗਸਤ',
'september-gen' => 'ਸਤੰਬਰ',
'october-gen'   => 'ਅਕਤੂਬਰ',
'november-gen'  => 'ਨਵੰਬਰ',
'december-gen'  => 'ਦਸੰਬਰ',
'jan'           => 'ਜਨ',
'feb'           => 'ਫ਼ਰ',
'mar'           => 'ਮਾਰ',
'apr'           => 'ਅਪ',
'may'           => 'ਮਈ',
'jun'           => 'ਜੂਨ',
'jul'           => 'ਜੁਲਾਈ',
'aug'           => 'ਅਗ',
'sep'           => 'ਸਤੰ',
'oct'           => 'ਅਕ',
'nov'           => 'ਨਵੰ',
'dec'           => 'ਦਸੰ',

# Categories related messages
'pagecategories'        => '{{PLURAL:$1|ਕੈਟਾਗਰੀ|ਕੈਟਾਗਰੀਆਂ}}',
'category_header'       => 'ਕੈਟਾਗਰੀ "$1" ਵਿੱਚ ਲੇਖ',
'subcategories'         => 'ਸਬ-ਕੈਟਾਗਰੀਆਂ',
'category-media-header' => 'ਕੈਟਾਗਰੀ "$1" ਵਿੱਚ ਮੀਡਿਆ',
'category-empty'        => "''ਇਹ ਕੈਟਾਗਰੀ ਵਿੱਚ ਇਸ ਵੇਲੇ ਕੋਈ ਲੇਖ (ਆਰਟੀਕਲ) ਜਾਂ ਮੀਡਿਆ ਨਹੀਂ ਹੈ।''",

'about'         => 'ਇਸ ਬਾਰੇ',
'article'       => 'ਸਮੱਗਰੀ ਪੇਜ',
'newwindow'     => '(ਨਵੀਂ ਵਿੰਡੋ ਵਿੱਚ ਖੋਲ੍ਹੋ)',
'cancel'        => 'ਰੱਦ ਕਰੋ',
'moredotdotdot' => 'ਹੋਰ...',
'mypage'        => 'ਮੇਰਾ ਪੇਜ',
'mytalk'        => 'ਮੇਰੀ ਗੱਲਬਾਤ',
'anontalk'      => 'ਇਹ IP ਲਈ ਗੱਲਬਾਤ',
'navigation'    => 'ਨੇਵੀਗੇਸ਼ਨ',
'and'           => '&#32;ਅਤੇ',

# Cologne Blue skin
'qbfind'         => 'ਖੋਜ',
'qbbrowse'       => 'ਬਰਾਊਜ਼',
'qbedit'         => 'ਸੋਧ',
'qbpageoptions'  => 'ਇਹ ਪੇਜ',
'qbpageinfo'     => 'ਭਾਗ',
'qbmyoptions'    => 'ਮੇਰੇ ਪੇਜ',
'qbspecialpages' => 'ਖਾਸ ਪੇਜ',
'faq'            => 'ਸਵਾਲ-ਜਵਾਬ',
'faqpage'        => 'Project:ਸਵਾਲ-ਜਵਾਬ',

# Vector skin
'vector-action-delete'    => 'ਹਟਾਓ',
'vector-action-move'      => 'ਭੇਜੋ',
'vector-action-protect'   => 'ਸੁਰੱਖਿਅਤ',
'vector-action-undelete'  => 'ਹਟਾਉਣਾ ਵਾਪਸ',
'vector-action-unprotect' => 'ਅਣ-ਸੁਰੱਖਿਅਤ',
'vector-view-create'      => 'ਬਣਾਓ',
'vector-view-edit'        => 'ਸੋਧ',
'vector-view-history'     => 'ਅਤੀਤ ਵੇਖੋ',
'vector-view-view'        => 'ਪੜ੍ਹੋ',
'vector-view-viewsource'  => 'ਸਰੋਤ ਵੇਖੋ',
'actions'                 => 'ਕਾਰਵਾਈ',
'namespaces'              => 'ਨਾਂ-ਥਾਂ:',

'errorpagetitle'    => 'ਗਲਤੀ',
'returnto'          => '$1 ਤੇ ਵਾਪਸ ਜਾਓ',
'tagline'           => '{{SITENAME}} ਤੋਂ',
'help'              => 'ਮੱਦਦ',
'search'            => 'ਖੋਜ',
'searchbutton'      => 'ਖੋਜ',
'go'                => 'ਜਾਓ',
'searcharticle'     => 'ਜਾਓ',
'history'           => 'ਸਫ਼ਾ ਅਤੀਤ',
'history_short'     => 'ਅਤੀਤ',
'updatedmarker'     => 'ਮੇਰੇ ਆਖਰੀ ਖੋਲ੍ਹਣ ਬਾਦ ਅੱਪਡੇਟ',
'printableversion'  => 'ਪਰਿੰਟਯੋਗ ਵਰਜਨ',
'permalink'         => 'ਪੱਕਾ ਲਿੰਕ',
'print'             => 'ਪਰਿੰਟ ਕਰੋ',
'edit'              => 'ਬਦਲੋ',
'create'            => 'ਬਣਾਓ',
'editthispage'      => 'ਇਹ ਪੇਜ ਸੋਧੋ',
'create-this-page'  => 'ਇਹ ਸਫ਼ਾ ਬਣਾਓ',
'delete'            => 'ਹਟਾਓ',
'deletethispage'    => 'ਇਹ ਪੇਜ ਹਟਾਓ',
'undelete_short'    => 'ਅਣ-ਹਟਾਓ {{PLURAL:$1|one edit|$1 edits}}',
'protect'           => 'ਸੁਰੱਖਿਆ',
'protect_change'    => 'ਬਦਲੋ',
'protectthispage'   => 'ਇਹ ਪੇਜ ਸੁਰੱਖਿਅਤ ਕਰੋ',
'unprotect'         => 'ਅਣ-ਸੁਰੱਖਿਅਤ',
'unprotectthispage' => 'ਇਹ ਪੇਜ ਅਣ-ਸੁਰੱਖਿਅਤ ਬਣਾਓ',
'newpage'           => 'ਨਵਾਂ ਪੇਜ',
'talkpage'          => 'ਇਸ ਪੇਜ ਬਾਰੇ ਚਰਚਾ',
'talkpagelinktext'  => 'ਗੱਲਬਾਤ',
'specialpage'       => 'ਖਾਸ ਪੇਜ',
'personaltools'     => 'ਨਿੱਜੀ ਟੂਲ',
'postcomment'       => 'ਇੱਕ ਟਿੱਪਣੀ ਦਿਓ',
'articlepage'       => 'ਸਮੱਗਰੀ ਪੇਜ ਵੇਖੋ',
'talk'              => 'ਚਰਚਾ',
'views'             => 'ਵੇਖੋ',
'toolbox'           => 'ਟੂਲਬਾਕਸ',
'userpage'          => 'ਯੂਜ਼ਰ ਪੇਜ ਵੇਖੋ',
'projectpage'       => 'ਪਰੋਜੈਕਟ ਪੇਜ ਵੇਖੋ',
'imagepage'         => 'ਫਾਇਲ ਪੇਜ ਵੇਖੋ',
'mediawikipage'     => 'ਸੁਨੇਹਾ ਪੇਜ ਵੇਖੋ',
'templatepage'      => 'ਟੈਪਲੇਟ ਪੇਜ ਵੇਖੋ',
'viewhelppage'      => 'ਮੱਦਦ ਪੇਜ ਵੇਖੋ',
'categorypage'      => 'ਕੈਟਾਗਰੀ ਪੇਜ ਵੇਖੋ',
'viewtalkpage'      => 'ਚਰਚਾ ਵੇਖੋ',
'otherlanguages'    => 'ਹੋਰ ਭਾਸ਼ਾਵਾਂ ਵਿੱਚ',
'redirectedfrom'    => '($1 ਤੋਂ ਰੀ-ਡਿਰੈਕਟ)',
'redirectpagesub'   => 'ਰੀ-ਡਿਰੈਕਟ ਪੇਜ',
'lastmodifiedat'    => 'ਇਹ ਪੇਜ ਆਖਰੀ ਵਾਰ $2, $1 ਨੂੰ ਸੋਧਿਆ ਗਿਆ ਸੀ।',
'viewcount'         => 'ਇਹ ਪੇਜ ਅਸੈੱਸ ਕੀਤਾ ਗਿਆ {{PLURAL:$1|ਇੱਕਵਾਰ|$1 ਵਾਰ}}.',
'protectedpage'     => 'ਸੁਰੱਖਿਅਤ ਪੇਜ',
'jumpto'            => 'ਜੰਪ ਕਰੋ:',
'jumptonavigation'  => 'ਨੇਵੀਗੇਸ਼ਨ',
'jumptosearch'      => 'ਖੋਜ',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} ਬਾਰੇ',
'aboutpage'            => 'Project:ਬਾਰੇ',
'copyright'            => 'ਸਮੱਗਰੀ $1 ਹੇਠ ਉਪਲੱਬਧ ਹੈ।',
'copyrightpage'        => '{{ns:project}}:ਕਾਪੀਰਾਈਟ',
'currentevents'        => 'ਮੌਜੂਦਾ ਇਵੈਂਟ',
'currentevents-url'    => 'Project:ਮੌਜੂਦਾ ਈਵੈਂਟ',
'disclaimers'          => 'ਦਾਅਵੇ',
'disclaimerpage'       => 'Project:ਆਮ ਦਾਅਵਾ',
'edithelp'             => 'ਮੱਦਦ ਐਡੀਟਿੰਗ',
'edithelppage'         => 'Help:ਐਡਟਿੰਗ',
'helppage'             => 'Help:ਸਮੱਗਰੀ',
'mainpage'             => 'ਮੁੱਖ ਪੇਜ',
'mainpage-description' => 'ਮੁੱਖ ਪੇਜ',
'policy-url'           => 'Project:ਪਾਲਸੀ',
'portal'               => 'ਕਮਿਊਨਟੀ ਪੋਰਟਲ',
'portal-url'           => 'Project:ਕਮਿਊਨਟੀ ਪੋਰਟਲ',
'privacy'              => 'ਪਰਾਈਵੇਸੀ ਪਾਲਸੀ',
'privacypage'          => 'Project:ਪਰਾਈਵੇਸੀ ਪਾਲਸੀ',

'badaccess'        => 'ਅਧਿਕਾਰ ਗਲਤੀ',
'badaccess-group0' => 'ਤੁਹਾਨੂੰ ਉਹ ਐਕਸ਼ਨ ਕਰਨ ਦੀ ਮਨਜ਼ੂਰੀ ਨਹੀਂ, ਜਿਸ ਦੀ ਤੁਸੀਂ ਮੰਗ ਕੀਤੀ ਹੈ।',

'ok'                      => 'ਠੀਕ ਹੈ',
'retrievedfrom'           => '"$1" ਤੋਂ ਲਿਆ',
'youhavenewmessages'      => 'ਤੁਹਾਨੂੰ $1 ($2).',
'newmessageslink'         => 'ਨਵੇਂ ਸੁਨੇਹੇ',
'newmessagesdifflink'     => 'ਆਖਰੀ ਬਦਲਾਅ',
'youhavenewmessagesmulti' => 'ਤੁਹਾਨੂੰ ਨਵੇਂ ਸੁਨੇਹੇ $1 ਉੱਤੇ ਹਨ',
'editsection'             => 'ਸੋਧ',
'editold'                 => 'ਸੋਧ',
'viewsourceold'           => 'ਸਰੋਤ ਵੇਖੋ',
'editlink'                => 'ਸੋਧ',
'viewsourcelink'          => 'ਸਰੋਤ ਵੇਖੋ',
'editsectionhint'         => 'ਸ਼ੈਕਸ਼ਨ ਸੋਧ: $1',
'toc'                     => 'ਸਮਗੱਰੀ',
'showtoc'                 => 'ਵੇਖੋ',
'hidetoc'                 => 'ਓਹਲੇ',
'thisisdeleted'           => 'ਵੇਖੋ ਜਾਂ $1 ਰੀਸਟੋਰ?',
'viewdeleted'             => '$1 ਵੇਖਣਾ?',
'feedlinks'               => 'ਫੀਡ:',
'site-rss-feed'           => '$1 RSS ਫੀਡ',
'site-atom-feed'          => '$1 ਐਟਮ ਫੀਡ',
'red-link-title'          => '$1 (ਇਸ ਨਾਂ ਦਾ ਪੇਜ ਨਹੀਂ ਹੈ)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'ਲੇਖ',
'nstab-user'      => 'ਯੂਜ਼ਰ ਪੇਜ',
'nstab-media'     => 'ਮੀਡਿਆ ਪੇਜ',
'nstab-special'   => 'ਖਾਸ ਸਫ਼ਾ',
'nstab-project'   => 'ਪਰੋਜੈਕਟ ਪੇਜ',
'nstab-image'     => 'ਫਾਇਲ',
'nstab-mediawiki' => 'ਸੁਨੇਹਾ',
'nstab-template'  => 'ਟੈਪਲੇਟ',
'nstab-help'      => 'ਮੱਦਦ ਪੇਜ',
'nstab-category'  => 'ਕੈਟਾਗਰੀ',

# Main script and global functions
'nosuchaction'      => 'ਕੋਈ ਇੰਝ ਦਾ ਐਕਸ਼ਨ ਨਹੀਂ',
'nosuchspecialpage' => 'ਕੋਈ ਇੰਝ ਦਾ ਖਾਸ ਪੇਜ ਨਹੀਂ',
'nospecialpagetext' => '<strong>ਤੁਸੀਂ ਇੱਕ ਅਵੈਧ ਖਾਸ ਪੇਜ ਦੀ ਮੰਗ ਕੀਤੀ ਹੈ।</strong>

A list of valid special pages can be found at [[Special:SpecialPages]].',

# General errors
'error'              => 'ਗਲਤੀ',
'databaseerror'      => 'ਡਾਟਾਬੇਸ ਗਲਤੀ',
'readonly'           => 'ਡਾਟਾਬੇਸ ਲਾਕ ਹੈ',
'internalerror'      => 'ਅੰਦਰੂਨੀ ਗਲਤੀ',
'internalerror_info' => 'ਅੰਦਰੂਨੀ ਗਲਤੀ: $1',
'badtitle'           => 'ਗਲਤ ਟਾਇਟਲ',
'viewsource'         => 'ਸਰੋਤ ਵੇਖੋ',

# Login and logout pages
'logouttext'                 => "'''ਹੁਣ ਤੁਸੀਂ ਲਾਗਆਉਟ ਹੋ ਗਏ ਹੋ।'''

You can continue to use {{SITENAME}} anonymously, or you can log in again as the same or as a different user.
Note that some pages may continue to be displayed as if you were still logged in, until you clear your browser cache.",
'welcomecreation'            => '== $1 ਜੀ ਆਇਆਂ ਨੂੰ! ==

ਤੁਹਾਡਾ ਅਕਾਊਂਟ ਬਣਾਇਆ ਗਿਆ ਹੈ। ਆਪਣੀ [[Special:ਪਸੰਦ|{{SITENAME}} ਪਸੰਦ]] ਬਦਲਣੀ ਨਾ ਭੁੱਲੋ।',
'yourname'                   => 'ਯੂਜ਼ਰ ਨਾਂ:',
'yourpassword'               => 'ਪਾਸਵਰਡ:',
'yourpasswordagain'          => 'ਪਾਸਵਰਡ ਮੁੜ-ਲਿਖੋ:',
'remembermypassword'         => 'ਇਸ ਕੰਪਿਊਟਰ ਉੱਤੇ ਮੇਰਾ ਲਾਗਇਨ ਯਾਦ ਰੱਖੋ ($1 {{PLURAL:$1|ਦਿਨ|ਦਿਨਾਂ}} ਲਈ ਵੱਧ ਤੋਂ ਵੱਧ)',
'yourdomainname'             => 'ਤੁਹਾਡੀ ਡੋਮੇਨ:',
'login'                      => 'ਲਾਗ ਇਨ',
'nav-login-createaccount'    => 'ਲਾਗ ਇਨ / ਅਕਾਊਂਟ ਬਣਾਓ',
'loginprompt'                => 'ਤੁਹਾਨੂੰ {{SITENAME}} ਉੱਤੇ ਲਾਗਇਨ ਕਰਨ ਲਈ ਕੂਕੀਜ਼ ਯੋਗ ਕਰਨੇ ਜ਼ਰੂਰੀ ਹਨ।',
'userlogin'                  => 'ਲਾਗ ਇਨ / ਅਕਾਊਂਟ ਬਣਾਓ',
'userloginnocreate'          => 'ਲਾਗ ਇਨ',
'logout'                     => 'ਲਾਗ ਆਉਟ',
'userlogout'                 => 'ਲਾਗ ਆਉਟ',
'notloggedin'                => 'ਲਾਗਇਨ ਨਹੀਂ',
'nologin'                    => 'ਅਕਾਊਂਟ ਨਹੀਂ ਹੈ? $1',
'nologinlink'                => 'ਇੱਕ ਅਕਾਊਂਟ ਬਣਾਓ',
'createaccount'              => 'ਅਕਾਊਂਟ ਬਣਾਓ',
'gotaccount'                 => "ਪਹਿਲਾਂ ਹੀ ਇੱਕ ਅਕਾਊਂਟ ਹੈ? '''$1'''.",
'gotaccountlink'             => 'ਲਾਗਇਨ',
'createaccountmail'          => 'ਈਮੇਲ ਨਾਲ',
'createaccountreason'        => 'ਕਾਰਨ:',
'badretype'                  => 'ਤੁਹਾਡੇ ਵਲੋਂ ਦਿੱਤੇ ਪਾਸਵਰਡ ਮਿਲਦੇ ਨਹੀਂ ਹਨ।',
'userexists'                 => 'ਯੂਜ਼ਰ ਨਾਂ ਪਹਿਲਾਂ ਹੀ ਵਰਤੋਂ ਅਧੀਨ ਹੈ।
ਵੱਖਰਾ ਯੂਜ਼ਰ ਨਾਂ ਵਰਤੋਂ ਜੀ।',
'loginerror'                 => 'ਲਾਗਇਨ ਗਲਤੀ',
'createaccounterror'         => 'ਅਕਾਊਂਟ ਬਣਾਇਆ ਨਹੀਂ ਜਾ ਸਕਿਆ: $1',
'nocookiesnew'               => 'ਯੂਜ਼ਰ ਅਕਾਊਂਟ ਬਣਾਇਆ ਗਿਆ ਹੈ, ਪਰ ਤੁਸੀਂ ਲਾਗਇਨ ਨਹੀਂ ਕੀਤਾ ਹੈ।{{SITENAME}} uses cookies to log in users. You have cookies disabled. Please enable them, then log in with your new username and password.',
'nocookieslogin'             => '{{SITENAME}} ਯੂਜ਼ਰਾਂ ਨੂੰ ਲਾਗਇਨ ਕਰਨ ਲਈ ਕੂਕੀਜ਼ ਵਰਤਦੀ ਹੈ। ਤੁਹਾਡੇ ਕੂਕੀਜ਼ ਆਯੋਗ ਕੀਤੇ ਹੋਏ ਹਨ। ਉਨ੍ਹਾਂ ਨੂੰ ਯੋਗ ਕਰਕੇ ਮੁੜ ਟਰਾਈ ਕਰੋ।',
'noname'                     => 'ਤੁਸੀਂ ਇੱਕ ਵੈਧ ਯੂਜ਼ਰ ਨਾਂ ਨਹੀਂ ਦਿੱਤਾ ਹੈ।',
'loginsuccesstitle'          => 'ਲਾਗਇਨ ਸਫ਼ਲ ਰਿਹਾ',
'loginsuccess'               => "'''ਤੁਸੀਂ {{SITENAME}} ਉੱਤੇ \"\$1\" ਵਾਂਗ ਲਾਗਇਨ ਕਰ ਚੁੱਕੇ ਹੋ।'''",
'nosuchuser'                 => '"$1" ਨਾਂ ਨਾਲ ਕੋਈ ਯੂਜ਼ਰ ਨਹੀਂ ਹੈ। ਆਪਣੇ ਸ਼ਬਦ ਧਿਆਨ ਨਾਲ ਚੈੱਕ ਕਰੋ ਜਾਂ ਨਵਾਂ ਅਕਾਊਂਟ ਬਣਾਓ।',
'nosuchusershort'            => '"$1" ਨਾਂ ਨਾਲ ਕੋਈ ਵੀ ਯੂਜ਼ਰ ਨਹੀਂ ਹੈ। ਆਪਣੇ ਸ਼ਬਦ ਧਿਆਨ ਨਾਲ ਚੈੱਕ ਕਰੋ।',
'nouserspecified'            => 'ਤੁਹਾਨੂੰ ਇੱਕ ਯੂਜ਼ਰ-ਨਾਂ ਦੇਣਾ ਪਵੇਗਾ।',
'wrongpassword'              => 'ਗਲਤ ਪਾਸਵਰਡ ਦਿੱਤਾ ਹੈ। ਮੁੜ-ਟਰਾਈ ਕਰੋ ਜੀ।',
'wrongpasswordempty'         => 'ਖਾਲੀ ਪਾਸਵਰਡ ਦਿੱਤਾ ਹੈ। ਮੁੜ-ਟਰਾਈ ਕਰੋ ਜੀ।',
'passwordtooshort'           => 'ਪਾਸਵਰਡ {{PLURAL:$1|1 ਅੱਖਰ|$1 ਅੱਖਰਾਂ}} ਦਾ ਹੋਣਾ ਲਾਜ਼ਮੀ ਹੈ।',
'password-name-match'        => 'ਤੁਹਾਡਾ ਪਾਸਵਰਡ ਤੁਹਾਡੇ ਯੂਜ਼ਰ ਨਾਂ ਤੋਂ ਵੱਖਰਾ ਹੋਣਾ ਚਾਹੀਦਾ ਹੈ।',
'mailmypassword'             => 'ਨਵਾਂ ਪਾਸਵਰਡ ਈਮੇਲ ਕਰੋ',
'passwordremindertitle'      => '{{SITENAME}} ਲਈ ਪਾਸਵਰਡ ਯਾਦ ਰੱਖੋ',
'passwordremindertext'       => 'ਕਿਸੇ ਨੇ (ਸ਼ਾਇਦ ਤੁਸੀਂ, IP ਐਡਰੈੱਸ $1 ਤੋਂ)
ਮੰਗ ਕੀਤੀ ਸੀ ਕਿ ਅਸੀਂ ਤੁਹਾਨੂੰ {{SITENAME}} ($4) ਲਈ ਪਾਸਵਰਡ ਭੇਜੀਏ।
ਯੂਜ਼ਰ "$2" ਲਈ ਹੁਣ ਪਾਸਵਰਡ "$3" ਹੈ।
ਤੁਹਾਨੂੰ ਹੁਣ ਲਾਗਇਨ ਕਰਕੇ ਆਪਣਾ ਪਾਸਵਰਡ ਹੁਣੇ ਬਦਲਣਾ ਚਾਹੀਦਾ ਹੈ।

If someone else made this request or if you have remembered your password and
you no longer wish to change it, you may ignore this message and continue using
your old password.',
'noemail'                    => 'ਯੂਜ਼ਰ "$1" ਲਈ ਰਿਕਾਰਡ ਵਿੱਚ ਕੋਈ ਈਮੇਲ ਐਡਰੈੱਸ ਨਹੀਂ ਹੈ।',
'noemailcreate'              => 'ਤੁਹਾਨੂੰ ਠੀਕ ਈਮੇਲ ਐਡਰੈੱਸ ਦੇਣਾ ਪਵੇਗਾ',
'passwordsent'               => '"$1" ਨਾਲ ਰਜਿਸਟਰ ਕੀਤੇ ਈਮੇਲ ਐਡਰੈੱਸ ਉੱਤੇ ਈਮੇਲ ਭੇਜੀ ਗਈ ਹੈ।
ਇਹ ਮਿਲ ਦੇ ਬਾਅਦ ਮੁੜ ਲਾਗਇਨ ਕਰੋ ਜੀ।',
'throttled-mailpassword'     => 'ਇੱਕ ਪਾਸਵਰਡ ਰੀਮਾਈਡਰ ਪਹਿਲਾਂ ਹੀ ਭੇਜਿਆ ਗਿਆ ਹੈ, ਆਖਰੀ
$1 ਘੰਟੇ ਵਿੱਚ। ਨੁਕਸਾਨ ਤੋਂ ਬਚਣ ਲਈ, $1 ਘੰਟਿਆਂ ਵਿੱਚ ਇੱਕ ਹੀ ਪਾਸਵਰਡ ਰੀਮਾਈਡਰ ਭੇਜਿਆ ਜਾਂਦਾ ਹੈ।',
'mailerror'                  => 'ਈਮੇਲ ਭੇਜਣ ਦੌਰਾਨ ਗਲਤੀ: $1',
'acct_creation_throttle_hit' => 'ਅਫਸੋਸ ਹੈ, ਪਰ ਤੁਸੀਂ ਪਹਿਲਾਂ ਹੀ $1 ਅਕਾਊਂਟ ਬਣਾ ਚੁੱਕੇ ਹੋ। ਤੁਸੀਂ ਹੋਰ ਨਹੀਂ ਬਣਾ ਸਕਦੇ।',
'emailauthenticated'         => 'ਤੁਹਾਡਾ ਈਮੇਲ ਐਡਰੈੱਸ $1 ਉੱਤੇ ਪਰਮਾਣਿਤ ਕੀਤਾ ਗਿਆ ਹੈ।',
'emailnotauthenticated'      => 'ਤੁਹਾਡਾ ਈਮੇਲ ਐਡਰੈੱਸ ਹਾਲੇ ਪਰਮਾਣਿਤ ਨਹੀਂ ਹੈ। ਹੇਠ ਦਿੱਤੇ ਫੀਚਰਾਂ ਲਈ ਕੋਈ ਵੀ ਈਮੇਲ ਨਹੀਂ ਭੇਜੀ ਜਾਵੇਗੀ।',
'noemailprefs'               => 'ਇਹ ਫੀਚਰ ਵਰਤਣ ਲਈ ਇੱਕ ਈਮੇਲ ਐਡਰੈੱਸ ਦਿਓ।।',
'emailconfirmlink'           => 'ਆਪਣਾ ਈ-ਮੇਲ ਐਡਰੈੱਸ ਕਨਫਰਮ ਕਰੋ।',
'invalidemailaddress'        => 'ਈਮੇਲ ਐਡਰੈੱਸ ਮਨਜ਼ੂਰ ਨਹੀਂ ਕੀਤਾ ਜਾ ਸਕਦਾ ਹੈ ਕਿਉਂਕਿ ਇਹ ਠੀਕ ਫਾਰਮੈਟ ਨਹੀਂ ਜਾਪਦਾ ਹੈ। ਇੱਕ ਠੀਕ ਫਾਰਮੈਟ ਵਿੱਚ ਦਿਓ ਜਾਂ ਇਹ ਖੇਤਰ ਖਾਲੀ ਛੱਡ ਦਿਓ।',
'accountcreated'             => 'ਅਕਾਊਂਟ ਬਣਾਇਆ',
'accountcreatedtext'         => '$1 ਲਈ ਯੂਜ਼ਰ ਅਕਾਊਂਟ ਬਣਾਇਆ ਗਿਆ।',
'createaccount-title'        => '{{SITENAME}} ਲਈ ਅਕਾਊਂਟ ਬਣਾਉਣਾ',
'loginlanguagelabel'         => 'ਭਾਸ਼ਾ: $1',

# Change password dialog
'resetpass'                 => 'ਪਾਸਵਰਡ ਬਦਲੋ',
'resetpass_announce'        => 'ਤੁਸੀਂ ਇੱਕ ਆਰਜ਼ੀ ਈ-ਮੇਲ ਕੀਤੇ ਕੋਡ ਨਾਲ ਲਾਗਇਨ ਕੀਤਾ ਹੈ। ਲਾਗਇਨ ਪੂਰਾ ਕਰਨ ਲਈ, ਤੁਹਾਨੂੰ ਇੱਥੇ ਨਵਾਂ ਪਾਸਵਰਡ ਦੇਣਾ ਪਵੇਗਾ:',
'resetpass_header'          => 'ਅਕਾਊਂਟ ਪਾਸਵਰਡ ਬਦਲੋ',
'oldpassword'               => 'ਪੁਰਾਣਾ ਪਾਸਵਰਡ:',
'newpassword'               => 'ਨਵਾਂ ਪਾਸਵਰਡ:',
'retypenew'                 => 'ਨਵਾਂ ਪਾਸਵਰਡ ਮੁੜ-ਲਿਖੋ:',
'resetpass_submit'          => 'ਪਾਸਵਰਡ ਸੈੱਟ ਕਰੋ ਅਤੇ ਲਾਗਇਨ ਕਰੋ',
'resetpass_success'         => 'ਤੁਹਾਡਾ ਪਾਸਵਰਡ ਠੀਕ ਤਰਾਂ ਬਦਲਿਆ ਗਿਆ ਹੈ! ਹੁਣ ਤੁਸੀਂ ਲਾਗਇਨ ਕਰ ਸਕਦੇ ਹੋ...',
'resetpass_forbidden'       => 'ਪਾਸਵਰਡ ਬਦਲਿਆ ਨਹੀਂ ਜਾ ਸਕਦਾ',
'resetpass-submit-loggedin' => 'ਪਾਸਵਰਡ ਬਦਲੋ',
'resetpass-submit-cancel'   => 'ਰੱਦ ਕਰੋ',
'resetpass-temp-password'   => 'ਆਰਜ਼ੀ ਪਾਸਵਰਡ:',

# Edit page toolbar
'bold_sample'     => 'ਗੂੜਾ ਟੈਕਸਟ',
'bold_tip'        => 'ਬੋਲਡ ਟੈਕਸਟ',
'italic_sample'   => 'ਤਿਰਛਾ ਟੈਕਸਟ',
'italic_tip'      => 'ਤਿਰਛਾ ਟੈਕਸਟ',
'link_sample'     => 'ਲਿੰਕ ਟਾਇਟਲ',
'link_tip'        => 'ਅੰਦਰੂਨੀ ਲਿੰਕ',
'extlink_sample'  => 'http://www.example.com ਲਿੰਕ ਟਾਈਟਲ',
'headline_sample' => 'ਹੈੱਡਲਾਈਨ ਟੈਕਸਟ',
'image_tip'       => 'ਇੰਬੈੱਡ ਚਿੱਤਰ',
'media_tip'       => 'ਮੀਡਿਆ ਫਾਇਲ ਲਿੰਕ',
'sig_tip'         => 'ਟਾਈਮ-ਸਟੈਂਪ ਨਾਲ ਤੁਹਾਡੇ ਦਸਤਖਤ',
'hr_tip'          => 'ਹਰੀਜੱਟਲ ਲਾਈਨ (use sparingly)',

# Edit pages
'summary'                => 'ਸੰਖੇਪ:',
'subject'                => 'ਵਿਸ਼ਾ/ਹੈੱਡਲਾਈਨ:',
'minoredit'              => 'ਇਹ ਛੋਟੀ ਸੋਧ ਹੈ',
'watchthis'              => 'ਇਹ ਪੇਜ ਵਾਚ ਕਰੋ',
'savearticle'            => 'ਪੇਜ ਸੰਭਾਲੋ',
'preview'                => 'ਝਲਕ',
'showpreview'            => 'ਝਲਕ ਵੇਖੋ',
'showlivepreview'        => 'ਲਾਈਵ ਝਲਕ',
'showdiff'               => 'ਬਦਲਾਅ ਵੇਖਾਓ',
'anoneditwarning'        => "'''ਚੇਤਾਵਨੀ:''' ਤੁਸੀਂ ਲਾਗਇਨ ਨਹੀਂ ਕੀਤਾ ਹੈ। ਤੁਹਾਡਾ IP ਐਡਰੈੱਸ ਇਸ ਪੇਜ ਦੇ ਐਡਿਟ ਅਤੀਤ ਵਿੱਚ ਰਿਕਾਰਡ ਕੀਤਾ ਜਾਵੇਗਾ।",
'missingcommenttext'     => 'ਹੇਠਾਂ ਇੱਕ ਟਿੱਪਣੀ ਦਿਓ।',
'summary-preview'        => 'ਸੰਖੇਪ ਝਲਕ:',
'subject-preview'        => 'ਵਿਸ਼ਾ/ਹੈੱਡਲਾਈਨ ਝਲਕ:',
'blockedtitle'           => 'ਯੂਜ਼ਰ ਬਲਾਕ ਕੀਤਾ ਗਿਆ',
'whitelistedittext'      => 'ਪੇਜ ਸੋਧਣ ਲਈ ਤੁਹਾਨੂੰ $1 ਕਰਨਾ ਪਵੇਗਾ।',
'nosuchsectiontitle'     => 'ਇੰਝ ਦਾ ਕੋਈ ਸ਼ੈਕਸ਼ਨ ਨਹੀਂ ਹੈ।',
'loginreqtitle'          => 'ਲਾਗਇਨ ਚਾਹੀਦਾ ਹੈ',
'loginreqlink'           => 'ਲਾਗਇਨ',
'loginreqpagetext'       => 'ਹੋਰ ਪੇਜ ਵੇਖਣ ਲਈ ਤੁਹਾਨੂੰ $1 ਕਰਨਾ ਪਵੇਗਾ।',
'accmailtitle'           => 'ਪਾਸਵਰਡ ਭੇਜਿਆ।',
'accmailtext'            => '"$1" ਲਈ ਪਾਸਵਰਡ $2 ਨੂੰ ਭੇਜਿਆ ਗਿਆ।',
'newarticle'             => '(ਨਵਾਂ)',
'updated'                => '(ਅੱਪਡੇਟ)',
'note'                   => "'''ਨੋਟ:'''",
'previewnote'            => "'''ਇਹ ਸਿਰਫ਼ ਇੱਕ ਝਲਕ ਹੈ; ਬਦਲਾਅ ਹਾਲੇ ਸੰਭਾਲੇ ਨਹੀਂ ਗਏ ਹਨ!'''",
'editing'                => '$1 ਸੋਧਿਆ ਜਾ ਰਿਹਾ ਹੈ',
'editingsection'         => '$1 (ਸ਼ੈਕਸ਼ਨ) ਸੋਧ',
'editingcomment'         => '$1 (ਟਿੱਪਣੀ) ਸੋਧ',
'editconflict'           => 'ਅਪਵਾਦ ਟਿੱਪਣੀ: $1',
'yourtext'               => 'ਤੁਹਾਡਾ ਟੈਕਸਟ',
'storedversion'          => 'ਸੰਭਾਲਿਆ ਵਰਜਨ',
'yourdiff'               => 'ਅੰਤਰ',
'templatesused'          => 'ਇਸ ਪੇਜ ਉੱਤੇ ਟੈਪਲੇਟ ਵਰਤਿਆ ਜਾਂਦਾ ਹੈ:',
'templatesusedpreview'   => "{{PLURAL:$1|ਟੈਪਲੇਟ|ਟੈਪਲੇਟ}} ਇਹ ਝਲਕ 'ਚ ਵਰਤੇ ਜਾਂਦੇ ਹਨ:",
'templatesusedsection'   => 'ਇਹ ਸ਼ੈਕਸ਼ਨ ਵਿੱਚ ਟੈਪਲੇਟ ਵਰਤਿਆ ਜਾਂਦਾ ਹੈ:',
'template-protected'     => '(ਸੁਰੱਖਿਅਤ)',
'template-semiprotected' => '(ਅਰਧ-ਸੁਰੱਖਿਅਤ)',
'permissionserrors'      => 'ਅਧਿਕਾਰ ਗਲਤੀਆਂ',
'permissionserrorstext'  => 'ਤੁਹਾਨੂੰ ਇੰਝ ਕਰਨ ਦੇ ਅਧਿਕਾਰ ਨਹੀਂ ਹਨ। ਹੇਠ ਦਿੱਤੇ {{PLURAL:$1|ਕਾਰਨ|ਕਾਰਨ}} ਨੇ:',

# Account creation failure
'cantcreateaccounttitle' => 'ਅਕਾਊਂਟ ਬਣਾਇਆ ਨਹੀਂ ਜਾ ਸਕਦਾ',

# History pages
'viewpagelogs'        => 'ਇਸ ਪੇਜ ਦੇ ਲਈ ਲਾਗ ਵੇਖੋ',
'currentrev'          => 'ਮੌਜੂਦਾ ਰੀਵਿਜ਼ਨ',
'revisionasof'        => '$1 ਦੇ ਰੀਵਿਜ਼ਨ ਵਾਂਗ',
'previousrevision'    => '←ਪੁਰਾਣਾ ਰੀਵਿਜ਼ਨ',
'nextrevision'        => 'ਨਵਾਂ ਰੀਵਿਜ਼ਨ→',
'currentrevisionlink' => 'ਮੌਜੂਦਾ ਰੀਵਿਜ਼ਨ',
'cur'                 => 'ਮੌਜੂਦਾ',
'next'                => 'ਅੱਗੇ',
'last'                => 'ਆਖਰੀ',
'page_first'          => 'ਪਹਿਲਾਂ',
'page_last'           => 'ਆਖਰੀ',
'histfirst'           => 'ਸਭ ਤੋਂ ਪਹਿਲਾਂ',
'histlast'            => 'ਸਭ ਤੋਂ ਨਵਾਂ',
'historysize'         => '($1 ਬਾਈਟ)',
'historyempty'        => '(ਖਾਲੀ)',

# Revision feed
'history-feed-title' => 'ਰੀਵਿਜ਼ਨ ਅਤੀਤ',

# Revision deletion
'rev-deleted-comment'     => '(ਟਿੱਪਣੀ ਹਟਾਈ)',
'rev-deleted-user'        => '(ਯੂਜ਼ਰ ਨਾਂ ਹਟਾਇਆ)',
'rev-deleted-event'       => '(ਐਂਟਰੀ ਹਟਾਈ)',
'rev-delundel'            => 'ਵੇਖਾਓ/ਓਹਲੇ',
'revdelete-nooldid-title' => 'ਕੋਈ ਟਾਰਗੇਟ ਰੀਵਿਜ਼ਨ ਨਹੀਂ',
'revdelete-legend'        => 'ਪਾਬੰਦੀਆਂ ਸੈੱਟ ਕਰੋ:',
'revdelete-hide-text'     => 'ਰੀਵਿਜ਼ਨ ਟੈਕਸਟ ਓਹਲੇ',
'revdelete-hide-image'    => 'ਫਾਇਲ ਸਮੱਗਰੀ ਓਹਲੇ',
'revdelete-hide-name'     => 'ਐਕਸ਼ਨ ਅਤੇ ਟਾਰਗੇਟ ਓਹਲੇ',
'revdelete-radio-set'     => 'ਹਾਂ',
'revdelete-log'           => 'ਕਾਰਨ:',
'revdelete-submit'        => 'ਚੁਣੇ ਰੀਵਿਜ਼ਨ ਉੱਤੇ ਲਾਗੂ ਕਰੋ',
'revdel-restore'          => 'ਦਿੱਖ ਬਦਲੋ',
'pagehist'                => 'ਪੇਜ ਦਾ ਅਤੀਤ',
'deletedhist'             => 'ਹਟਾਇਆ ਗਿਆ ਅਤੀਤ',

# Merge log
'revertmerge' => 'ਬਿਨ-ਮਿਲਾਨ',

# Diffs
'difference'              => '(ਰੀਵਿਜ਼ਨ ਵਿੱਚ ਅੰਤਰ)',
'lineno'                  => 'ਲਾਈਨ $1:',
'compareselectedversions' => 'ਚੁਣੇ ਵਰਜਨਾਂ ਦੀ ਤੁਲਨਾ',
'editundo'                => 'ਵਾਪਸ(undo)',

# Search results
'searchresults'                  => 'ਖੋਜ ਨਤੀਜੇ',
'searchresults-title'            => '"$1" ਲਈ ਖੋਜ ਨਤੀਜੇ',
'searchresulttext'               => '{{SITENAME}} ਖੋਜ ਬਾਰੇ ਹੋਰ ਜਾਣਕਾਰੀ ਲਵੋ, ਵੇਖੋ [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                 => 'ਤੁਸੀਂ \'\'\'[[:$1]]\'\'\' ਲਈ ਖੋਜ ਕੀਤੀ ([[Special:Prefixindex/$1|"$1" ਨਾਲ ਸ਼ੁਰੂ ਹੁੰਦੇ ਸਭ ਸਫ਼ੇ]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|"$1" ਨਾਲ ਲਿੰਕ ਹੋਏ ਸਭ ਸਫ਼ੇ]])',
'searchsubtitleinvalid'          => "ਤੁਸੀਂ'''$1''' ਲਈ ਖੋਜ ਕੀਤੀ।",
'titlematches'                   => 'ਆਰਟੀਕਲ ਟੈਕਸਟ ਮਿਲਦਾ',
'notitlematches'                 => 'ਕੋਈ ਪੇਜ ਟਾਇਟਲ ਨਹੀਂ ਮਿਲਦਾ',
'textmatches'                    => 'ਪੇਜ ਟੈਕਸਟ ਮਿਲਦਾ',
'notextmatches'                  => 'ਕੋਈ ਪੇਜ ਟੈਕਸਟ ਨਹੀਂ ਮਿਲਦਾ',
'prevn'                          => 'ਪਿੱਛੇ {{PLURAL:$1|$1}}',
'nextn'                          => 'ਅੱਗੇ {{PLURAL:$1|$1}}',
'viewprevnext'                   => 'ਵੇਖੋ ($1 {{int:pipe-separator}} $2) ($3)',
'searchhelp-url'                 => 'Help:ਸਮੱਗਰੀ',
'searchprofile-advanced'         => 'ਤਕਨੀਕੀ',
'searchprofile-articles-tooltip' => "$1 'ਚ ਖੋਜ",
'search-result-size'             => '$1 ({{PLURAL:$2|੧ ਸ਼ਬਦ|$2 ਸ਼ਬਦ}})',
'search-redirect'                => '($1 ਰੀ-ਡਿਰੈਕਟ)',
'search-section'                 => '(ਭਾਗ $1)',
'search-suggest'                 => 'ਕੀ ਤੁਹਾਡਾ ਮਤਲਬ ਸੀ: $1',
'search-interwiki-default'       => '$1 ਨਤੀਜੇ:',
'search-interwiki-more'          => '(ਹੋਰ)',
'search-mwsuggest-enabled'       => 'ਸੁਝਾਆਵਾਂ ਨਾਲ',
'search-mwsuggest-disabled'      => 'ਕੋਈ ਸੁਝਾਅ ਨਹੀਂ',
'searchall'                      => 'ਸਭ',
'powersearch'                    => 'ਖੋਜ',
'powersearch-legend'             => 'ਤਕਨੀਕੀ ਖੋਜ',
'powersearch-ns'                 => 'ਨੇਮ-ਸਪੇਸ ਵਿੱਚ ਖੋਜ:',
'powersearch-redir'              => 'ਰੀ-ਡਿਰੈਕਟ ਲਿਸਟ',
'powersearch-field'              => 'ਇਸ ਲਈ ਖੋਜ',

# Quickbar
'qbsettings'      => 'ਤੁਰੰਤ ਬਾਰ',
'qbsettings-none' => 'ਕੋਈ ਨਹੀਂ',

# Preferences page
'preferences'                 => 'ਮੇਰੀ ਪਸੰਦ',
'mypreferences'               => 'ਮੇਰੀ ਪਸੰਦ',
'prefs-edits'                 => 'ਸੋਧਾਂ ਦੀ ਗਿਣਤੀ:',
'prefsnologin'                => 'ਲਾਗਇਨ ਨਹੀਂ',
'prefsnologintext'            => 'ਯੂਜ਼ਰ ਪਸੰਦ ਸੈੱਟ ਕਰਨ ਲਈ ਤੁਹਾਨੂੰ [[Special:UserLogin|logged in]] ਕਰਨਾ ਪਵੇਗਾ।',
'changepassword'              => 'ਪਾਸਵਰਡ ਬਦਲੋ',
'prefs-skin'                  => 'ਸਕਿਨ',
'skin-preview'                => 'ਝਲਕ',
'datedefault'                 => 'ਕੋਈ ਪਸੰਦ ਨਹੀਂ',
'prefs-datetime'              => 'ਮਿਤੀ ਅਤੇ ਸਮਾਂ',
'prefs-personal'              => 'ਯੂਜ਼ਰ ਪਰੋਫਾਇਲ',
'prefs-rc'                    => 'ਤਾਜ਼ਾ ਬਦਲਾਅ',
'prefs-watchlist'             => 'ਵਾਚ-ਲਿਸਟ',
'prefs-misc'                  => 'ਫੁਟਕਲ',
'prefs-resetpass'             => 'ਪਾਸਵਰਡ ਬਦਲੋ',
'prefs-email'                 => 'ਈਮੇਲ ਚੋਣਾਂ',
'prefs-rendering'             => 'ਦਿੱਖ',
'saveprefs'                   => 'ਸੰਭਾਲੋ',
'resetprefs'                  => 'ਰੀ-ਸੈੱਟ',
'prefs-editing'               => 'ਸੰਪਾਦਨ',
'rows'                        => 'ਕਤਾਰਾਂ:',
'columns'                     => 'ਕਾਲਮ:',
'searchresultshead'           => 'ਖੋਜ',
'resultsperpage'              => 'ਪ੍ਰਤੀ ਪੇਜ ਹਿੱਟ:',
'savedprefs'                  => 'ਤੁਹਾਡੀ ਪਸੰਦ ਸੰਭਾਲੀ ਗਈ ਹੈ।',
'timezonelegend'              => 'ਸਮਾਂ ਖੇਤਰ:',
'localtime'                   => 'ਲੋਕਲ ਸਮਾਂ:',
'timezoneuseserverdefault'    => 'ਸਰਵਰ ਡਿਫਾਲਟ ਵਰਤੋਂ',
'servertime'                  => 'ਸਰਵਰ ਟਾਈਮ',
'guesstimezone'               => 'ਬਰਾਊਜ਼ਰ ਤੋਂ ਭਰੋ',
'allowemail'                  => 'ਹੋਰ ਯੂਜ਼ਰਾਂ ਤੋਂ ਈਮੇਲ ਯੋਗ ਕਰੋ',
'default'                     => 'ਡਿਫਾਲਟ',
'prefs-files'                 => 'ਫਾਇਲਾਂ',
'youremail'                   => 'ਈਮੇਲ:',
'username'                    => 'ਯੂਜ਼ਰ ਨਾਂ:',
'uid'                         => 'ਯੂਜ਼ਰ ID:',
'yourrealname'                => 'ਅਸਲੀ ਨਾਂ:',
'yourlanguage'                => 'ਭਾਸ਼ਾ:',
'yournick'                    => 'ਛੋਟਾ ਨਾਂ:',
'badsiglength'                => 'ਛੋਟਾ ਨਾਂ (Nickname) ਬਹੁਤ ਲੰਮਾ ਹੋ ਗਿਆ ਹੈ, ਇਹ $1 ਅੱਖਰਾਂ ਤੋਂ ਘੱਟ ਚਾਹੀਦਾ ਹੈ।',
'email'                       => 'ਈਮੇਲ',
'prefs-help-realname'         => 'ਅਸਲੀ ਨਾਂ ਚੋਣਵਾਂ ਹੈ, ਅਤੇ ਜੇ ਤੁਸੀਂ ਇਹ ਦਿੱਤਾ ਹੈ ਤਾਂ ਤੁਹਾਡੇ ਕੰਮ ਵਾਸਤੇ ਗੁਣ ਦੇ ਤੌਰ ਉੱਤੇ ਵਰਤਿਆ ਜਾਵੇਗਾ।',
'prefs-help-email'            => 'ਈਮੇਲ ਐਡਰੈੱਸ ਚੋਣਵਾਂ ਹੈ, ਪਰ ਇਹ ਤੁਹਾਨੂੰ ਹੋਰਾਂ ਵਲੋਂ ਤੁਹਾਡੇ ਨਾਲ ਤੁਹਾਡੇ ਯੂਜ਼ਰ ਜਾਂ ਯੂਜ਼ਰ_ਗੱਲਬਾਤ ਰਾਹੀਂ ਬਿਨਾਂ ਤੁਹਾਡੇ ਪਛਾਣ ਦੇ ਸੰਪਰਕ ਲਈ ਮੱਦਦ ਦਿੰਦਾ ਹੈ।',
'prefs-advancedediting'       => 'ਤਕਨੀਕੀ ਚੋਣਾਂ',
'prefs-advancedrc'            => 'ਤਕਨੀਕੀ ਚੋਣਾਂ',
'prefs-advancedrendering'     => 'ਤਕਨੀਕੀ ਚੋਣਾਂ',
'prefs-advancedsearchoptions' => 'ਤਕਨੀਕੀ ਚੋਣਾਂ',
'prefs-advancedwatchlist'     => 'ਤਕਨੀਕੀ ਚੋਣਾਂ',

# User rights
'userrights-lookup-user'   => 'ਯੂਜ਼ਰ ਗਰੁੱਪ ਦੇਖਭਾਲ',
'userrights-user-editname' => 'ਇੱਕ ਯੂਜ਼ਰ ਨਾਂ ਦਿਓ:',
'editusergroup'            => 'ਯੂਜ਼ਰ ਗਰੁੱਪ ਸੋਧ',
'editinguser'              => '<b>$1</b> ਯੂਜ਼ਰ ਸੋਧਿਆ ਜਾ ਰਿਹਾ ਹੈ ([[User talk:$1|{{int:talkpagelinktext}}]] | [[Special:Contributions/$1|{{int:contribslink}}]])',
'userrights-editusergroup' => 'ਯੂਜ਼ਰ ਗਰੁੱਪ ਸੋਧ',
'saveusergroups'           => 'ਯੂਜ਼ਰ ਗਰੁੱਪ ਸੰਭਾਲੋ',
'userrights-groupsmember'  => 'ਇਸ ਦਾ ਮੈਂਬਰ:',
'userrights-reason'        => 'ਕਾਰਨ:',

# Groups
'group'      => 'ਗਰੁੱਪ:',
'group-user' => 'ਮੈਂਬਰ',
'group-all'  => '(ਸਭ)',

'group-user-member' => 'ਮੈਂਬਰ',

# Rights
'right-edit'   => 'ਸਫ਼ੇ ਸੋਧ',
'right-delete' => 'ਸਫ਼ੇ ਹਟਾਓ',

# User rights log
'rightsnone' => '(ਕੋਈ ਨਹੀਂ)',

# Recent changes
'recentchanges'      => 'ਤਾਜ਼ਾ ਬਦਲਾਅ',
'rcshowhideminor'    => '$1 ਛੋਟੀਆਂ ਸੋਧਾਂ',
'rcshowhidebots'     => '$1 ਬੋਟ',
'rcshowhideliu'      => '$1 ਲਾਗਇਨ ਹੋਏ ਯੂਜ਼ਰ',
'rcshowhideanons'    => '$1 ਅਗਿਆਤ ਯੂਜ਼ਰ',
'rcshowhidemine'     => '$1 ਮੇਰਾ ਐਡਿਟ',
'diff'               => 'ਅੰਤਰ',
'hist'               => 'ਅਤੀਤ',
'hide'               => 'ਓਹਲੇ',
'show'               => 'ਵੇਖੋ',
'minoreditletter'    => 'ਛ',
'newpageletter'      => 'ਨ',
'boteditletter'      => 'ਬ',
'rc_categories_any'  => 'ਕੋਈ ਵੀ',
'rc-enhanced-expand' => 'ਵੇਰਵਾ ਵੇਖੋ (ਜਾਵਾਸਕ੍ਰਿਪਟ ਲੋੜੀਦੀ ਹੈ)',
'rc-enhanced-hide'   => 'ਵੇਰਵਾ ਓਹਲੇ',

# Recent changes linked
'recentchangeslinked'         => 'ਸਬੰਧਿਤ ਬਦਲਾਅ',
'recentchangeslinked-feed'    => 'ਸਬੰਧਿਤ ਬਦਲਾਅ',
'recentchangeslinked-toolbox' => 'ਸਬੰਧਿਤ ਬਦਲਾਅ',
'recentchangeslinked-page'    => 'ਸਫ਼ਾ ਨਾਂ:',

# Upload
'upload'               => 'ਫਾਇਲ ਅੱਪਲੋਡ ਕਰੋ',
'uploadbtn'            => 'ਫਾਇਲ ਅੱਪਲੋਡ ਕਰੋ',
'reuploaddesc'         => 'ਅੱਪਲੋਡ ਫਾਰਮ ਉੱਤੇ ਜਾਓ।',
'uploadnologin'        => 'ਲਾਗਇਨ ਨਹੀਂ ਹੋ',
'uploadnologintext'    => 'ਤੁਹਾਨੂੰ[[Special:UserLogin|logged in] ਕਰਨਾ ਪਵੇਗਾ]
to upload files.',
'uploaderror'          => 'ਅੱਪਲੋਡ ਗਲਤੀ',
'uploadlog'            => 'ਅੱਪਲੋਡ ਲਾਗ',
'uploadlogpage'        => 'ਅੱਪਲੋਡ ਲਾਗ',
'filename'             => 'ਫਾਇਲ ਨਾਂ',
'filedesc'             => 'ਸੰਖੇਪ',
'fileuploadsummary'    => 'ਸੰਖੇਪ:',
'filestatus'           => 'ਕਾਪੀਰਾਈਟ ਹਾਲਤ:',
'filesource'           => 'ਸੋਰਸ:',
'uploadedfiles'        => 'ਅੱਪਲੋਡ ਕੀਤੀਆਂ ਫਾਇਲਾਂ',
'ignorewarning'        => 'ਚੇਤਾਵਨੀ ਅਣਡਿੱਠੀ ਕਰਕੇ ਕਿਵੇਂ ਵੀ ਫਾਇਲ ਸੰਭਾਲੋ।',
'minlength1'           => 'ਫਾਇਲ ਨਾਂ ਵਿੱਚ ਘੱਟੋ-ਘੱਟ ਇੱਕ ਅੱਖਰ ਹੋਣਾ ਚਾਹੀਦਾ ਹੈ।',
'badfilename'          => 'ਫਾਇਲ ਨਾਂ "$1" ਬਦਲਿਆ ਗਿਆ ਹੈ।',
'filetype-missing'     => 'ਫਾਇਲ ਦੀ ਕੋਈ ਐਕਸ਼ਟੇਸ਼ਨ ਨਹੀਂ ਹੈ (ਜਿਵੇਂ ".jpg").',
'fileexists'           => "ਇਹ ਫਾਇਲ ਨਾਂ ਪਹਿਲਾਂ ਹੀ ਮੌਜੂਦ ਹੈ। ਜੇ ਤੁਸੀਂ ਇਹ ਬਦਲਣ ਬਾਰੇ ਜਾਣਦੇ ਨਹੀਂ ਹੋ ਤਾਂ  '''<tt>[[:$1]]</tt>''' ਵੇਖੋ ਜੀ। [[$1|thumb]]",
'fileexists-extension' => "ਇਸ ਨਾਂ ਨਾਲ ਰਲਦੀ ਫਾਇਲ ਮੌਜੂਦ ਹੈ: [[$2|thumb]]
* ਅੱਪਲੋਡ ਕੀਤੀ ਫਾਇਲ ਦਾ ਨਾਂ: '''<tt>[[:$1]]</tt>'''
* ਮੌਜੂਦ ਫਾਇਲ ਦਾ ਨਾਂ: '''<tt>[[:$2]]</tt>'''
ਇੱਕ ਵੱਖਰਾ ਨਾਂ ਚੁਣੋ ਜੀ",
'uploadwarning'        => 'ਅੱਪਲੋਡ ਚੇਤਾਵਨੀ',
'savefile'             => 'ਫਾਇਲ ਸੰਭਾਲੋ',
'uploadedimage'        => '"[[$1]]" ਅੱਪਲੋਡ',
'uploaddisabled'       => 'ਅੱਪਲੋਡ ਆਯੋਗ ਹੈ',
'uploadvirus'          => 'ਇਹ ਫਾਇਲ ਵਿੱਚ ਵਾਇਰਸ ਹੈ! ਵੇਰਵੇ ਲਈ ਵੇਖੋ: $1',
'sourcefilename'       => 'ਸੋਰਸ ਫਾਇਲ ਨਾਂ:',
'watchthisupload'      => 'ਇਸ ਫਾਇਲ ਨੂੰ ਵਾਚ ਕਰੋ',
'upload-success-subj'  => 'ਠੀਕ ਤਰ੍ਹਾਂ ਅੱਪਲੋਡ',
'upload-warning-subj'  => 'ਅੱਪਲੋਡ ਚੇਤਾਵਨੀ',

'upload-file-error' => 'ਅੰਦਰੂਨੀ ਗਲਤੀ',
'upload-misc-error' => 'ਅਣਜਾਣ ਅੱਪਲੋਡ ਗਲਤੀ',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error28' => 'ਅੱਪਲੋਡ ਟਾਈਮ-ਆਉਟ',

'license'            => 'ਲਾਈਸੈਂਸਿੰਗ:',
'license-header'     => 'ਲਾਈਸੈਂਸਿੰਗ:',
'nolicense'          => 'ਕੁਝ ਵੀ ਚੁਣਿਆ',
'license-nopreview'  => '(ਝਲਕ ਉਪਲੱਬਧ ਨਹੀਂ)',
'upload_source_file' => ' (ਤੁਹਾਡੇ ਕੰਪਿਊਟਰ ਉੱਤੇ ਇੱਕ ਫਾਇਲ)',

# Special:ListFiles
'imgfile'               => 'ਫਾਇਲ',
'listfiles'             => 'ਫਾਇਲ ਲਿਸਟ',
'listfiles_date'        => 'ਮਿਤੀ',
'listfiles_name'        => 'ਨਾਂ',
'listfiles_user'        => 'ਯੂਜ਼ਰ',
'listfiles_size'        => 'ਆਕਾਰ',
'listfiles_description' => 'ਵੇਰਵਾ',
'listfiles_count'       => 'ਵਰਜਨ',

# File description page
'file-anchor-link'          => 'ਫਾਇਲ',
'filehist'                  => 'ਫਾਇਲ ਅਤੀਤ',
'filehist-deleteall'        => 'ਸਭ ਹਟਾਓ',
'filehist-deleteone'        => 'ਇਹ ਹਟਾਓ',
'filehist-revert'           => 'ਰੀਵਰਟ',
'filehist-current'          => 'ਮੌਜੂਦਾ',
'filehist-datetime'         => 'ਮਿਤੀ/ਸਮਾਂ',
'filehist-user'             => 'ਯੂਜ਼ਰ',
'filehist-dimensions'       => 'ਮਾਪ',
'filehist-filesize'         => 'ਫਾਇਲ ਆਕਾਰ',
'filehist-comment'          => 'ਟਿੱਪਣੀ',
'imagelinks'                => 'ਫਾਇਲ ਲਿੰਕ',
'uploadnewversion-linktext' => 'ਇਸ ਫਾਇਲ ਦਾ ਇੱਕ ਨਵਾਂ ਵਰਜਨ ਅੱਪਲੋਡ ਕਰੋ',

# File reversion
'filerevert'         => '$1 ਰੀਵਰਟ',
'filerevert-legend'  => 'ਫਾਇਲ ਰੀਵਰਟ',
'filerevert-comment' => 'ਟਿੱਪਣੀ:',
'filerevert-submit'  => 'ਰੀਵਰਟ',

# File deletion
'filedelete'         => '$1 ਹਟਾਓ',
'filedelete-legend'  => 'ਫਾਇਲ ਹਟਾਓ',
'filedelete-comment' => 'ਕਾਰਨ:',
'filedelete-submit'  => 'ਹਟਾਓ',
'filedelete-success' => "'''$1''' ਨੂੰ ਹਟਾਇਆ ਗਿਆ।",

# MIME search
'mimesearch' => 'MIME ਖੋਜ',
'mimetype'   => 'MIME ਕਿਸਮ:',
'download'   => 'ਡਾਊਨਲੋਡ',

# Statistics
'statistics'              => 'ਅੰਕੜੇ',
'statistics-header-pages' => 'ਸਫ਼ਾ ਅੰਕੜੇ',
'statistics-header-edits' => 'ਸੋਧ ਅੰਕੜੇ',
'statistics-header-views' => 'ਵੇਖਣ ਅੰਕੜੇ',
'statistics-header-users' => 'ਯੂਜ਼ਰ ਅੰਕੜੇ',
'statistics-mostpopular'  => 'ਸਭ ਤੋਂ ਵੱਧ ਵੇਖੇ ਪੇਜ',

'brokenredirects-edit'   => 'ਸੋਧ',
'brokenredirects-delete' => 'ਹਟਾਓ',

# Miscellaneous special pages
'nbytes'            => '$1 {{PLURAL:$1|ਬਾਈਟ|ਬਾਈਟ}}',
'nmembers'          => '$1 {{PLURAL:$1|ਮੈਂਬਰ|ਮੈਂਬਰ}}',
'unusedcategories'  => 'ਅਣਵਰਤੀਆਂ ਕੈਟਾਗਰੀਆਂ',
'unusedimages'      => 'ਅਣਵਰਤੀਆਂ ਫਾਇਲਾਂ',
'popularpages'      => 'ਪਾਪੂਲਰ ਪੇਜ',
'shortpages'        => 'ਛੋਟੇ ਪੇਜ',
'listusers'         => 'ਯੂਜ਼ਰ ਲਿਸਟ',
'newpages'          => 'ਨਵੇਂ ਪੇਜ',
'newpages-username' => 'ਯੂਜ਼ਰ ਨਾਂ:',
'ancientpages'      => 'ਸਭ ਤੋਂ ਪੁਰਾਣੇ ਪੇਜ',
'move'              => 'ਭੇਜੋ',
'movethispage'      => 'ਇਹ ਪੇਜ ਭੇਜੋ',
'notargettitle'     => 'ਟਾਰਗੇਟ ਨਹੀਂ',
'pager-newer-n'     => '{{PLURAL:$1|੧ ਨਵਾਂ|$1 ਨਵੇਂ}}',

# Book sources
'booksources'    => 'ਕਿਤਾਬ ਸਰੋਤ',
'booksources-go' => 'ਜਾਓ',

# Special:Log
'specialloguserlabel'  => 'ਯੂਜ਼ਰ:',
'speciallogtitlelabel' => 'ਟਾਇਟਲ:',
'log'                  => 'ਲਾਗ',
'all-logs-page'        => 'ਸਭ ਲਾਗ',

# Special:AllPages
'allpages'          => 'ਸਭ ਪੇਜ',
'alphaindexline'    => '$1 ਤੋਂ $2',
'nextpage'          => 'ਅੱਗੇ ਪੇਜ ($1)',
'prevpage'          => 'ਪਿੱਛੇ ਪੇਜ ($1)',
'allarticles'       => 'ਸਭ ਲੇਖ',
'allinnamespace'    => 'ਸਭ ਪੇਜ ($1 ਨੇਮਸਪੇਸ)',
'allnotinnamespace' => 'ਸਭ ਪੇਜ ($1 ਨੇਮਸਪੇਸ ਵਿੱਚ ਨਹੀਂ)',
'allpagesprev'      => 'ਪਿੱਛੇ',
'allpagesnext'      => 'ਅੱਗੇ',
'allpagessubmit'    => 'ਜਾਓ',

# Special:Categories
'categories' => 'ਕੈਟਾਗਰੀਆਂ',

# Special:LinkSearch
'linksearch' => 'ਬਾਹਰੀ ਲਿੰਕ',

# Special:ListUsers
'listusers-submit'   => 'ਵੇਖੋ',
'listusers-noresult' => 'ਕੋਈ ਯੂਜ਼ਰ ਨਹੀਂ ਲੱਭਿਆ।',

# Special:ListGroupRights
'listgrouprights-group'   => 'ਗਰੁੱਪ',
'listgrouprights-members' => '(ਮੈਂਬਰਾਂ ਦੀ ਲਿਸਟ)',

# E-mail user
'mailnologin'     => 'ਕੋਈ ਭੇਜਣ ਐਡਰੈੱਸ ਨਹੀਂ',
'emailuser'       => 'ਇਹ ਯੂਜ਼ਰ ਨੂੰ ਈਮੇਲ ਕਰੋ',
'emailpage'       => 'ਯੂਜ਼ਰ ਨੂੰ ਈਮੇਲ ਕਰੋ',
'defemailsubject' => '{{SITENAME}} ਈਮੇਲ',
'noemailtitle'    => 'ਕੋਈ ਈਮੇਲ ਐਡਰੈੱਸ ਨਹੀਂ',
'emailfrom'       => 'ਵਲੋਂ:',
'emailto'         => 'ਵੱਲ:',
'emailsubject'    => 'ਵਿਸ਼ਾ:',
'emailmessage'    => 'ਸੁਨੇਹਾ:',
'emailsend'       => 'ਭੇਜੋ',
'emailccme'       => 'ਸੁਨੇਹੇ ਦੀ ਇੱਕ ਕਾਪੀ ਮੈਨੂੰ ਵੀ ਭੇਜੋ।',
'emailsent'       => 'ਈਮੇਲ ਭੇਜੀ ਗਈ',
'emailsenttext'   => 'ਤੁਹਾਡੀ ਈਮੇਲ ਭੇਜੀ ਗਈ ਹੈ।',

# Watchlist
'watchlist'     => 'ਮੇਰੀ ਵਾਚ-ਲਿਸਟ',
'mywatchlist'   => 'ਮੇਰੀ ਵਾਚ-ਲਿਸਟ',
'watchnologin'  => 'ਲਾਗਇਨ ਨਹੀਂ',
'watch'         => 'ਵਾਚ',
'watchthispage' => 'ਇਹ ਪੇਜ ਵਾਚ ਕਰੋ',
'unwatch'       => 'ਅਣ-ਵਾਚ',
'wlshowlast'    => 'ਆਖਰੀ $1 ਦਿਨ $2 ਘੰਟੇ $3 ਵੇਖੋ',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'ਨਿਗ੍ਹਾ (ਵਾਚ) ਰੱਖੀ ਜਾ ਰਹੀ ਹੈ...',
'unwatching' => 'ਨਿਗ੍ਹਾ ਰੱਖਣੀ (ਵਾਚ) ਬੰਦ ਕੀਤੀ ਜਾ ਰਹੀ ਹੈ..',

'enotif_newpagetext'           => 'ਇਹ ਨਵਾਂ ਪੇਜ ਹੈ।',
'enotif_impersonal_salutation' => '{{SITENAME}} ਯੂਜ਼ਰ',
'changed'                      => 'ਬਦਲਿਆ',
'created'                      => 'ਬਣਾਇਆ',
'enotif_anon_editor'           => 'ਅਗਿਆਤ ਯੂਜ਼ਰ $1',

# Delete
'deletepage'            => 'ਪੇਜ ਹਟਾਓ',
'confirm'               => 'ਪੁਸ਼ਟੀ',
'excontent'             => "ਸਮੱਗਰੀ ਸੀ: '$1'",
'exblank'               => 'ਪੇਜ ਖਾਲੀ ਹੈ',
'delete-confirm'        => '"$1" ਹਟਾਓ',
'delete-legend'         => 'ਹਟਾਓ',
'actioncomplete'        => 'ਐਕਸ਼ਨ ਪੂਰਾ ਹੋਇਆ',
'dellogpage'            => 'ਹਟਾਉਣ ਲਾਗ',
'deletecomment'         => 'ਕਾਰਨ:',
'deleteotherreason'     => 'ਹੋਰ/ਵਾਧੂ ਕਾਰਨ:',
'deletereasonotherlist' => 'ਹੋਰ ਕਾਰਨ',

# Rollback
'rollback_short' => 'ਰੋਲਬੈਕ',
'rollbacklink'   => 'ਰੋਲਬੈਕ',
'rollbackfailed' => 'ਰੋਲਬੈਕ ਫੇਲ੍ਹ',

# Protect
'protectlogpage'              => 'ਸੁਰੱਖਿਆ ਲਾਗ',
'protect-legend'              => 'ਸੁਰੱਖਿਆ ਕਨਫਰਮ',
'protectcomment'              => 'ਕਾਰਨ:',
'protectexpiry'               => 'ਮਿਆਦ:',
'protect-default'             => 'ਸਭ ਯੂਜ਼ਰ ਮਨਜ਼ੂਰ',
'protect-fallback'            => '"$1" ਅਧਿਕਾਰ ਲੋੜੀਦਾ ਹੈ',
'protect-level-autoconfirmed' => 'ਨਵੇਂ ਤੇ ਗ਼ੈਰ-ਰਜਿਸਟਰ ਯੂਜ਼ਰਾਂ ਉੱਤੇ ਪਾਬੰਦੀ',
'protect-level-sysop'         => 'ਕੇਵਲ ਪਰਸ਼ਾਸ਼ਕ',
'restriction-type'            => 'ਅਧਿਕਾਰ:',
'minimum-size'                => 'ਘੱਟੋ-ਘੱਟ ਆਕਾਰ',
'maximum-size'                => 'ਵੱਧੋ-ਵੱਧ ਆਕਾਰ',
'pagesize'                    => '(ਬਾਈਟ)',

# Restrictions (nouns)
'restriction-edit'   => 'ਸੋਧ',
'restriction-move'   => 'ਭੇਜੋ',
'restriction-upload' => 'ਅੱਪਲੋਡ',

# Restriction levels
'restriction-level-sysop'         => 'ਪੂਰਾ ਸੁਰੱਖਿਅਤ',
'restriction-level-autoconfirmed' => 'ਅਰਧ-ਸੁਰੱਖਿਅਤ',
'restriction-level-all'           => 'ਕੋਈ ਲੈਵਲ',

# Undelete
'undeletebtn'               => 'ਰੀਸਟੋਰ',
'undeletelink'              => 'ਵੇਖੋ/ਰੀਸਟੋਰ',
'undeletereset'             => 'ਰੀ-ਸੈੱਟ',
'undeletecomment'           => 'ਟਿੱਪਣੀ:',
'undelete-show-file-submit' => 'ਹਾਂ',

# Namespace form on various pages
'namespace'      => 'ਨਾਂ-ਥਾਂ:',
'invert'         => 'ਉਲਟ ਚੋਣ',
'blanknamespace' => '(ਮੁੱਖ)',

# Contributions
'contributions' => 'ਯੂਜ਼ਰ ਯੋਗਦਾਨ',
'mycontris'     => 'ਮੇਰਾ ਯੋਗਦਾਨ',
'contribsub2'   => '$1 ($2) ਲਈ',

'sp-contributions-newbies-sub' => 'ਨਵੇਂ ਅਕਾਊਂਟਾਂ ਲਈ',
'sp-contributions-talk'        => 'ਗੱਲਬਾਤ',
'sp-contributions-username'    => 'IP ਐਡਰੈੱਸ ਜਾਂ ਯੂਜ਼ਰ ਨਾਂ:',
'sp-contributions-submit'      => 'ਖੋਜ',

# What links here
'whatlinkshere'           => 'ਇੱਥੇ ਕਿਹੜੇ ਲਿੰਕ',
'whatlinkshere-page'      => 'ਸਫਾ:',
'whatlinkshere-links'     => '← ਲਿੰਕ',
'whatlinkshere-hidelinks' => '$1 ਲਿੰਕ',
'whatlinkshere-filters'   => 'ਫਿਲਟਰ',

# Block/unblock
'blockip'            => 'ਯੂਜ਼ਰ ਬਲਾਕ ਕਰੋ',
'ipadressorusername' => 'IP ਐਡਰੈਸ ਜਾਂ ਯੂਜ਼ਰ ਨਾਂ:',
'ipbexpiry'          => 'ਮਿਆਦ:',
'ipbreason'          => 'ਕਾਰਨ:',
'ipbreasonotherlist' => 'ਹੋਰ ਕਾਰਨ',
'ipbsubmit'          => 'ਇਹ ਯੂਜ਼ਰ ਲਈ ਪਾਬੰਦੀ',
'ipbother'           => 'ਹੋਰ ਟਾਈਮ:',
'ipbotheroption'     => 'ਹੋਰ',
'ipbotherreason'     => 'ਹੋਰ/ਆਮ ਕਾਰਨ:',
'badipaddress'       => 'ਗਲਤ IP ਐਡਰੈੱਸ',
'ipb-unblock-addr'   => '$1 ਅਣ-ਬਲਾਕ',
'ipb-unblock'        => 'ਇੱਕ ਯੂਜ਼ਰ ਨਾਂ ਜਾਂ IP ਐਡਰੈੱਸ ਅਣ-ਬਲਾਕ ਕਰੋ',
'unblockip'          => 'ਯੂਜ਼ਰ ਅਣ-ਬਲਾਕ ਕਰੋ',
'ipblocklist-submit' => 'ਖੋਜ',
'infiniteblock'      => 'ਬੇਅੰਤ',
'expiringblock'      => '$1 $2 ਮਿਆਦ ਖਤਮ',
'anononlyblock'      => 'anon. ਹੀ',
'emailblock'         => 'ਈਮੇਲ ਬਲਾਕ ਹੈ',
'blocklink'          => 'ਬਲਾਕ',
'unblocklink'        => 'ਅਣ-ਬਲਾਕ',
'change-blocklink'   => 'ਬਲਾਕ ਬਦਲੋ',
'contribslink'       => 'ਯੋਗਦਾਨ',
'unblocklogentry'    => '$1 ਤੋਂ ਪਾਬੰਦੀ ਹਟਾਈ',
'proxyblocksuccess'  => 'ਪੂਰਾ ਹੋਇਆ',

# Developer tools
'lockdb' => 'ਡਾਟਾਬੇਸ ਲਾਕ',

# Move page
'move-page-legend' => 'ਪੇਜ ਮੂਵ ਕਰੋ',
'movearticle'      => 'ਸਫ਼ਾ ਭੇਜੋ:',
'movenologin'      => 'ਲਾਗਇਨ ਨਹੀਂ ਹੋ',
'movenologintext'  => 'ਇੱਕ ਪੇਜ ਮੂਵ ਕਰਨ ਲਈ ਤੁਸੀਂ ਰਜਿਸਟਰਡ ਮੈਂਬਰ ਹੋਣੇ ਚਾਹੀਦੇ ਹੋ ਅਤੇ [[Special:UserLogin|ਲਾਗਡ ਇਨ]] ਕੀਤਾ ਹੋਣਾ ਚਾਹੀਦਾ ਹੈ।',
'newtitle'         => 'ਨਵੇਂ ਟਾਈਟਲ ਲਈ:',
'move-watch'       => 'ਸਰੋਤ ਤੇ ਟਾਰਗੇਟ ਸਫ਼ੇ ਉੱਤੇ ਨਿਗਰਾਨੀ ਰੱਖੋ',
'movepagebtn'      => 'ਸਫ਼ਾ ਭੇਜੋ',
'pagemovedsub'     => 'ਭੇਜਣਾ ਸਫ਼ਲ ਰਿਹਾ',
'movepage-moved'   => '\'\'\'"$1" ਨੂੰ  "$2"\'\'\' ਉੱਤੇ ਭੇਜਿਆ',
'movedto'          => 'ਮੂਵ ਕੀਤਾ',
'movelogpage'      => 'ਮੂਵ ਲਾਗ',
'movereason'       => 'ਕਾਰਨ:',
'revertmove'       => 'ਰੀਵਰਟ',
'delete_and_move'  => 'ਹਟਾਓ ਅਤੇ ਮੂਵ ਕਰੋ',

# Export
'export'          => 'ਸਫ਼ੇ ਐਕਸਪੋਰਟ ਕਰੋ',
'export-submit'   => 'ਐਕਸਪੋਰਟ',
'export-addcat'   => 'ਸ਼ਾਮਲ',
'export-addns'    => 'ਸ਼ਾਮਲ',
'export-download' => 'ਫਾਇਲ ਵਜੋਂ ਸੰਭਾਲੋ',

# Namespace 8 related
'allmessages'               => 'ਸਿਸਟਮ ਸੁਨੇਹੇ',
'allmessagesname'           => 'ਨਾਂ',
'allmessagesdefault'        => 'ਡਿਫਾਲਟ ਟੈਕਸਟ',
'allmessagescurrent'        => 'ਮੌਜੂਦਾ ਟੈਕਸਟ',
'allmessages-language'      => 'ਭਾਸ਼ਾ:',
'allmessages-filter-submit' => 'ਜਾਓ',

# Thumbnails
'thumbnail-more' => 'ਫੈਲਾਓ',
'filemissing'    => 'ਫਾਇਲ ਗੁੰਮ ਹੈ',

# Special:Import
'import'                  => 'ਪੇਜ ਇੰਪੋਰਟ ਕਰੋ',
'import-interwiki-submit' => 'ਇੰਪੋਰਟ',
'import-comment'          => 'ਟਿੱਪਣੀ:',
'importstart'             => 'ਪੇਜ ਇੰਪੋਰਟ ਕੀਤੇ ਜਾ ਰਹੇ ਹਨ...',
'importfailed'            => 'ਇੰਪੋਰਟ ਫੇਲ੍ਹ: $1',
'importnotext'            => 'ਖਾਲੀ ਜਾਂ ਕੋਈ ਟੈਕਸਟ ਨਹੀਂ',
'importsuccess'           => 'ਇੰਪੋਰਟ ਸਫ਼ਲ!',
'importnofile'            => 'ਕੋਈ ਇੰਪੋਰਟ ਫਾਇਲ ਅੱਪਲੋਡ ਨਹੀਂ ਕੀਤੀ।',

# Import log
'importlogpage'                 => 'ਇੰਪੋਰਟ ਲਾਗ',
'import-logentry-upload-detail' => '$1 ਰੀਵਿਜ਼ਨ',

# Tooltip help for the actions
'tooltip-pt-userpage'            => 'ਤੁਹਾਡਾ ਯੂਜ਼ਰ ਸਫ਼ਾ',
'tooltip-pt-mytalk'              => 'ਤੁਹਾਡਾ ਚਰਚਾ ਪੇਜ',
'tooltip-pt-preferences'         => 'ਮੇਰੀ ਪਸੰਦ',
'tooltip-pt-mycontris'           => 'ਮੇਰੇ ਯੋਗਦਾਨ ਦੀ ਲਿਸਟ',
'tooltip-pt-login'               => 'ਤੁਹਾਨੂੰ ਲਾਗਇਨ ਕਰਨ ਲਈ ਉਤਸ਼ਾਹਿਤ ਕੀਤਾ ਜਾਂਦਾ ਹੈ, ਪਰ ਇਹ ਲਾਜ਼ਮੀ ਨਹੀਂ ਹੈ',
'tooltip-pt-logout'              => 'ਲਾਗ ਆਉਟ',
'tooltip-ca-talk'                => 'ਸਮਗੱਰੀ ਸਫ਼ੇ ਬਾਰੇ ਚਰਚਾ',
'tooltip-ca-edit'                => 'ਤੁਸੀਂ ਇਹ ਸਫ਼ਾ ਸੋਧ ਸਕਦੇ ਹੋ। ਸੰਭਾਲਣ ਤੋਂ ਪਹਿਲਾਂ ਝਲਕ ਬਟਨ ਵਰਤ ਕੇ ਵੇਖੋ ਜੀ',
'tooltip-ca-viewsource'          => 'ਇਹ ਪੇਜ਼ ਸੁਰੱਖਿਅਤ ਹੈ।
ਤੁਸੀਂ ਇਸ ਦਾ ਸਰੋਤ ਵੇਖ ਸਕਦੇ ਹੋ।',
'tooltip-ca-history'             => 'ਇਹ ਸਫ਼ੇ ਦਾ ਪਿਛਲਾ ਰੀਵਿਜ਼ਨ',
'tooltip-ca-protect'             => 'ਇਹ ਪੇਜ ਸੁਰੱਖਿਅਤ ਬਣਾਓ',
'tooltip-ca-delete'              => 'ਇਹ ਪੇਜ ਹਟਾਓ',
'tooltip-ca-move'                => 'ਇਹ ਪੇਜ ਭੇਜੋ',
'tooltip-ca-watch'               => "ਇਹ ਸਫ਼ੇ ਆਪਣੀ ਵਾਚ-ਲਿਸਟ 'ਚੋਂ ਹਟਾਓ",
'tooltip-ca-unwatch'             => 'ਇਹ ਸਫ਼ਾ ਆਪਣੀ ਵਾਚ-ਲਿਸਟ ਤੋਂ ਹਟਾਓ',
'tooltip-search'                 => 'ਖੋਜ {{SITENAME}}',
'tooltip-search-go'              => 'ਠੀਕ ਇਹ ਨਾਂ ਵਾਲੇ ਸਫ਼ੇ ਉੱਤੇ ਜਾਉ, ਜੇ ਮੌਜੂਦ ਹੈ',
'tooltip-search-fulltext'        => 'ਇਸ ਟੈਕਸਟ ਲਈ ਸਫ਼ਿਆਂ ਦੀ ਖੋਜ।',
'tooltip-p-logo'                 => 'ਮੁੱਖ ਪੇਜ',
'tooltip-n-mainpage'             => 'ਮੁੱਖ ਪੇਜ ਖੋਲ੍ਹੋ',
'tooltip-n-mainpage-description' => 'ਮੁੱਖ ਪੇਜ਼ ਉੱਤੇ ਜਾਓ',
'tooltip-n-portal'               => 'ਪਰੋਜੈਕਟ ਬਾਰੇ, ਤੁਸੀਂ ਕੀ ਕਰ ਸਕਦੇ ਹੋ, ਕਿੱਥੇ ਕੁਝ ਲੱਭ ਸਕਦੇ ਹੋ',
'tooltip-n-currentevents'        => 'ਮੌਜੂਦਾ ਸਮਾਗਮ ਬਾਰੇ ਪਿਛਲੀ ਜਾਣਕਾਰੀ ਲੱਭੋ',
'tooltip-n-recentchanges'        => 'ਵਿਕਿ ਵਿੱਚ ਤਾਜ਼ਾ ਬਦਲਾਅ ਦੀ ਲਿਸਟ',
'tooltip-n-randompage'           => 'ਇੱਕ ਰਲਵਾਂ ਪੇਜ ਲੋਡ ਕਰੋ',
'tooltip-n-help'                 => 'ਖੋਜਣ ਲਈ ਥਾਂ',
'tooltip-t-whatlinkshere'        => 'ਸਭ ਵਿਕਿ ਸਫ਼ਿਆਂ ਦੀ ਲਿਸਟ, ਜੋ ਇੱਥੇ ਲਿੰਕ ਕੀਤੇ ਹਨ',
'tooltip-t-recentchangeslinked'  => 'ਇਹ ਸਫ਼ੇ ਤੋਂ ਲਿੰਕ ਕੀਤੇ ਸਫ਼ਿਆਂ ਵਿੱਚ ਤਾਜ਼ਾ ਬਦਲਾਅ',
'tooltip-t-emailuser'            => 'ਇਹ ਯੂਜ਼ਰ ਨੂੰ ਮੇਲ ਭੇਜੋ',
'tooltip-t-upload'               => 'ਚਿੱਤਰ ਜਾਂ ਮੀਡਿਆ ਫਾਇਲਾਂ ਅੱਪਲੋਡ ਕਰੋ',
'tooltip-t-specialpages'         => 'ਸਭ ਖਾਸ ਸਫ਼ਿਆਂ ਦੀ ਲਿਸਟ',
'tooltip-t-print'                => 'ਇਹ ਸਫ਼ੇ ਦਾ ਛਪਣਯੋਗ ਵਰਜਨ',
'tooltip-t-permalink'            => 'ਸਫ਼ੇ ਦੇ ਇਹ ਰੀਵਿਜ਼ਨ ਲਈ ਪੱਕੇ ਲਿੰਕ',
'tooltip-ca-nstab-main'          => 'ਸਮਗੱਰੀ ਪੇਜ ਵੇਖੋ',
'tooltip-ca-nstab-user'          => 'ਯੂਜ਼ਰ ਪੇਜ ਵੇਖੋ',
'tooltip-ca-nstab-media'         => 'ਮੀਡਿਆ ਪੇਜ ਵੇਖੋ',
'tooltip-ca-nstab-special'       => 'ਇਹ ਖਾਸ ਸਫ਼ਾ ਹੈ, ਤੁਸੀਂ ਇਸ ਸਫ਼ੇ ਨੂੰ ਸੋਧ ਨਹੀਂ ਸਕਦੇ ਹੋ',
'tooltip-ca-nstab-project'       => 'ਪਰੋਜੈਕਟ ਪੇਜ ਵੇਖੋ',
'tooltip-ca-nstab-image'         => 'ਚਿੱਤਰ ਪੇਜ ਵੇਖੋ',
'tooltip-ca-nstab-mediawiki'     => 'ਸਿਸਟਮ ਸੁਨੇਹੇ ਵੇਖੋ',
'tooltip-ca-nstab-template'      => 'ਟੈਪਲੇਟ ਵੇਖੋ',
'tooltip-ca-nstab-help'          => 'ਮੱਦਦ ਪੇਜ ਵੇਖੋ',
'tooltip-ca-nstab-category'      => 'ਕੈਟਾਗਰੀ ਪੇਜ ਵੇਖੋ',
'tooltip-save'                   => 'ਆਪਣੇ ਬਦਲਾਅ ਸੰਭਾਲੋ',
'tooltip-preview'                => 'ਆਪਣੇ ਬਦਲਾਅ ਦੀ ਝਲਕ ਵੇਖੋ, ਸੰਭਾਲਣ ਤੋਂ ਪਹਿਲਾਂ ਇਹ ਵਰਤੋਂ ਜੀ!',
'tooltip-upload'                 => 'ਅੱਪਲੋਡ ਸਟਾਰਟ ਕਰੋ',

# Attribution
'others'      => 'ਹੋਰ',
'siteusers'   => '{{SITENAME}} ਯੂਜ਼ਰ $1',
'creditspage' => 'ਪੇਜ ਮਾਣ',

# Spam protection
'spamprotectiontitle' => 'Spam ਸੁਰੱਖਿਆ ਫਿਲਟਰ',

# Skin names
'skinname-standard' => 'ਕਲਾਸਿਕ',
'skinname-monobook' => 'ਮੋਨੋਬੁੱਕ',
'skinname-myskin'   => 'ਮੇਰੀਸਕਿਨ',
'skinname-chick'    => 'ਚੀਚਕ',
'skinname-simple'   => 'ਸੈਂਪਲ',

# Browsing diffs
'previousdiff' => '← ਪੁਰਾਣੀ ਸੋਧ',
'nextdiff'     => 'ਅੰਤਰ ਅੱਗੇ →',

# Media information
'thumbsize'       => 'ਥੰਮਨੇਲ ਆਕਾਰ:',
'widthheightpage' => '$1×$2, $3 ਪੇਜ਼',
'file-info'       => 'ਫਾਇਲ ਆਕਾਰ: $1, MIME ਕਿਸਮ: $2',
'file-info-size'  => '$1 × $2 ਪਿਕਸਲ, ਫਾਇਲ ਆਕਾਰ: $3, MIME ਕਿਸਮ: $4',
'svg-long-desc'   => 'SVG ਫਾਇਲ, nominally $1 × $2 pixels, file size: $3',
'show-big-image'  => 'ਪੂਰਾ ਰੈਜ਼ੋਲੇਸ਼ਨ',

# Special:NewFiles
'newimages' => 'ਨਵੀਆਂ ਫਾਇਲਾਂ ਦੀ ਗੈਲਰੀ',
'noimages'  => 'ਵੇਖਣ ਲਈ ਕੁਝ ਨਹੀਂ',
'ilsubmit'  => 'ਖੋਜ',
'bydate'    => 'ਮਿਤੀ ਨਾਲ',

# EXIF tags
'exif-imagewidth'       => 'ਚੌੜਾਈ',
'exif-imagelength'      => 'ਉਚਾਈ',
'exif-samplesperpixel'  => 'ਭਾਗਾਂ ਦੀ ਗਿਣਤੀ',
'exif-imagedescription' => 'ਚਿੱਤਰ ਟਾਇਟਲ',
'exif-make'             => 'ਕੈਮਰਾ ਨਿਰਮਾਤਾ',
'exif-model'            => 'ਕੈਮਰਾ ਮਾਡਲ',
'exif-software'         => 'ਵਰਤਿਆ ਸਾਫਟਵੇਅਰ',
'exif-artist'           => 'ਲੇਖਕ',
'exif-copyright'        => 'ਕਾਪੀਰਾਈਟ ਟਾਇਟਲ',
'exif-subjectarea'      => 'ਵਿਸ਼ਾ ਖੇਤਰ',
'exif-gpsdatestamp'     => 'GPS ਮਿਤੀ',

'exif-unknowndate' => 'ਅਣਜਾਣ ਮਿਤੀ',

'exif-exposureprogram-2' => 'ਸਧਾਰਨ ਪਰੋਗਰਾਮ',

'exif-meteringmode-0'   => 'ਅਣਜਾਣ',
'exif-meteringmode-1'   => 'ਔਸਤ',
'exif-meteringmode-5'   => 'ਪੈਟਰਨ',
'exif-meteringmode-255' => 'ਹੋਰ',

'exif-lightsource-0'  => 'ਅਣਜਾਣ',
'exif-lightsource-9'  => 'ਵਧੀਆ ਮੌਸਮ',
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

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'ਸਭ',
'namespacesall' => 'ਸਭ',
'monthsall'     => 'ਸਭ',

# E-mail address confirmation
'confirmemail'          => 'ਈਮੇਲ ਐਡਰੈੱਸ ਪੁਸ਼ਟੀ',
'confirmemail_send'     => 'ਇੱਕ ਪੁਸ਼ਟੀ ਕੋਡ ਭੇਜੋ',
'confirmemail_sent'     => 'ਪੁਸ਼ਟੀ ਈਮੇਲ ਭੇਜੀ ਗਈ।',
'confirmemail_invalid'  => 'ਗਲਤ ਪੁਸ਼ਟੀ ਕੋਡ ਹੈ। ਕੋਡ ਦੀ ਮਿਆਦ ਪੁੱਗੀ ਹੋ ਸਕਦੀ ਹੈ।',
'confirmemail_loggedin' => 'ਹੁਣ ਤੁਹਾਡਾ ਈਮੇਲ ਐਡਰੈੱਸ ਚੈੱਕ (confirmed) ਹੋ ਗਿਆ ਹੈ।',
'confirmemail_subject'  => '{{SITENAME}} ਈਮੇਲ ਐਡਰੈੱਸ ਪੁਸ਼ਟੀ',

# Scary transclusion
'scarytranscludetoolong' => '[ਅਫਸੋਸ ਹੈ ਕਿ URL ਬਹੁਤ ਲੰਮਾ ਹੈ]',

# Delete conflict
'recreate' => 'ਮੁੜ-ਬਣਾਓ',

# action=purge
'confirm_purge_button' => 'ਠੀਕ ਹੈ',

# Multipage image navigation
'imgmultipageprev' => '← ਪਿਛਲਾ ਪੇਜ',
'imgmultipagenext' => 'ਅਗਲਾ ਪੇਜ →',
'imgmultigo'       => 'ਜਾਓ!',
'imgmultigoto'     => '$1 ਸਫ਼ੇ ਉੱਤੇ ਜਾਓ',

# Table pager
'table_pager_next'         => 'ਅਗਲਾ ਪੇਜ',
'table_pager_prev'         => 'ਪਿਛਲਾ ਪੇਜ',
'table_pager_first'        => 'ਪਹਿਲਾ ਪੇਜ',
'table_pager_last'         => 'ਆਖਰੀ ਪੇਜ',
'table_pager_limit'        => 'ਹਰੇਕ ਪੇਜ ਲਈ $1 ਆਈਟਮਾਂ',
'table_pager_limit_label'  => 'ਪ੍ਰਤੀ ਸਫ਼ਾ ਆਈਟਮਾਂ:',
'table_pager_limit_submit' => 'ਜਾਓ',
'table_pager_empty'        => 'ਕੋਈ ਨਤੀਜਾ ਨਹੀਂ',

# Auto-summaries
'autosumm-blank' => 'ਪੇਜ ਨੂੰ ਖਾਲੀ ਕਰ ਦਿੱਤਾ',
'autosumm-new'   => '$1 ਨਾਲ ਪੇਜ ਬਣਾਇਆ',

# Live preview
'livepreview-loading' => 'ਲੋਡ ਕੀਤਾ ਜਾ ਰਿਹਾ ਹੈ…',
'livepreview-ready'   => 'ਲੋਡ ਕੀਤਾ ਜਾ ਰਿਹਾ ਹੈ...ਤਿਆਰ!',

# Watchlist editor
'watchlistedit-raw-titles'  => 'ਟਾਇਟਲ:',
'watchlistedit-raw-added'   => '{{PLURAL:$1|1 title was|$1 titles were}} ਸ਼ਾਮਲ:',
'watchlistedit-raw-removed' => '{{PLURAL:$1|1 title was|$1 titles were}} ਹਟਾਓ:',

# Watchlist editing tools
'watchlisttools-edit' => 'ਵਾਚਲਿਸਟ ਵੇਖੋ ਤੇ ਸੋਧੋ',

# Special:Version
'version' => 'ਵਰਜਨ',

# Special:SpecialPages
'specialpages'             => 'ਖਾਸ ਪੇਜ',
'specialpages-group-login' => 'ਲਾਗ ਇਨ / ਅਕਾਊਂਟ ਬਣਾਓ',

# Special:BlankPage
'blankpage' => 'ਖ਼ਾਲੀ ਪੇਜ',

# HTML forms
'htmlform-submit'              => 'ਭੇਜੋ',
'htmlform-reset'               => 'ਬਦਲਾਅ ਵਾਪਸ ਲਵੋ',
'htmlform-selectorother-other' => 'ਹੋਰ',

);

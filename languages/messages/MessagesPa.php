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
 * @author Gman124
 * @author Guglani
 * @author Kaganer
 * @author Sukh
 * @author Surinder.wadhawan
 * @author TariButtar
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
'tog-underline' => 'ਅੰਡਰ-ਲਾਈਨ ਲਿੰਕ:',
'tog-justify' => 'ਪੈਰਾ ਸਹੀ ਕਰੇ .',
'tog-hideminor' => 'ਮੌਨਜੁਦਾ ਬਦਲਾਬ ਮੈ ਸੈ ਨੀਕੈ ਬਦਲਾਬ ਕੌ ਛੁਪਾ ਕਰ ਰਖੇ.',
'tog-hidepatrolled' => 'ਮੌਨਜੁਦਾ ਬਦਲਾਬ ਮੈ ਸੈ ਸਹੀਤਕ ਬਦਲਾਬ ਕੌ ਛੁਪਾ ਕਰ ਰਖੇ.',
'tog-newpageshidepatrolled' => 'ਨਵੀ ਸੁਚੀ ਮੈ ਸੈ ਗਸ਼ਤ ਪਰਚੇ ਕੌ ਛੁਪਾਏ.',
'tog-extendwatchlist' => 'ਸਾਰੀ ਨਵੀ ਤਬਦੀਲੀਆ ਹੀ ਨਹੀ ,ਪੂਰਾਣੀ ਤਬਦੀਲੀਆ ਨੂੰ ਵੀ ਨਵੀ ਸੂਚੀ ਵਿਚ ਵਧਾ ਕੈ ਸ਼ਾਮੀਲ ਕਰੌ.',
'tog-usenewrc' => 'ਸੁਦਾਰ ਕੀਤੇ ਹੌਂਂਞੇ ਰੂਚੀ ਦੀ  ਵਰਤੌ ਕਰੌ (ਜਰੂਰਤ ਹੈ ਜਾਵਾ ਸਕ੍ਰਿਪ੍ਟ ਕੀ)',
'tog-numberheadings' => 'ਆਟੋ-ਨੰਬਰ ਹੈਡਿੰਗ',
'tog-showtoolbar' => 'ਐਡਿਟ ਟੂਲਬਾਰ ਵੇਖੋ (JavaScript)',
'tog-showtoc' => 'ਟੇਬਲ ਆਫ਼ ਕੰਨਟੈੱਟ ਵੇਖਾਓ (for pages with more than 3 headings)',
'tog-rememberpassword' => 'ਇਸ ਬਰਾਊਜ਼ਰ ਉੱਤੇ ਮੇਰਾ ਲਾਗਇਨ ਯਾਦ ਰੱਖੋ ($1 {{PLURAL:$1|ਦਿਨ|ਦਿਨਾਂ}} ਲਈ ਵੱਧ ਤੋਂ ਵੱਧ)',
'tog-watchcreations' => 'ਮੇਰੇ ਵਲੋਂ ਬਣਾਏ ਗਏ ਨਵੇਂ ਸਫ਼ੇ ਮੇਰੀ ਵਾਚ-ਲਿਸਟ ਵਿੱਚ ਪਾਓ',
'tog-watchdefault' => 'ਜੋ ਸਫ਼ੇ ਮੈਂ ਸੋਧਦਾ ਹਾਂ, ਓਹ ਪੇਜ ਮੇਰੀ ਵਾਚ-ਲਿਸਟ ਵਿੱਚ ਪਾਓ',
'tog-watchmoves' => 'ਮੇਰੇ ਵਲੋਂ ਭੇਜੇ ਕਿਤੇ ਸਫ਼ੇ ਨੂੰ ਮੇਰੀ ਵਾਚ-ਲਿਸਟ ਵਿੱਚ ਪਾਓ',
'tog-watchdeletion' => 'ਮੇਰੇ ਵਲੋਂ ਹਟਾਏ ਗਏ ਸਫ਼ੇ ਮੇਰੀ ਵਾਚ-ਲਿਸਟ ਵਿੱਚ ਪਾਓ',
'tog-previewontop' => 'ਐਡਿਟ ਬਕਸੇ ਤੋਂ ਪਹਿਲਾਂ ਝਲਕ ਵੇਖਾਓ',
'tog-previewonfirst' => 'ਪਹਿਲੇ ਐਡਿਟ ਉੱਤੇ ਝਲਕ ਵੇਖਾਓ',
'tog-nocache' => 'ਬਰਾਊਜ਼ਰ ਸਫ਼ਾ ਕੈਸ਼ ਕਰਨਾ ਬੰਦ ਕਰੋ',
'tog-enotifwatchlistpages' => 'ਜਦੋਂ ਮੇਰੀ ਵਾਚ-ਲਿਸਟ ਵਿੱਚੋਂ ਸਫ਼ਾ ਬਦਲਿਆ ਜਾਵੇ ਤਾਂ ਮੈਨੂੰ ਈਮੇਲ ਭੇਜੋ',
'tog-oldsig' => 'ਮੌਜੂਦਾ ਦਸਤਖਤ:',
'tog-watchlisthideown' => 'ਮੇਰੀ ਵਾਚ-ਲਿਸਟ ਵਿੱਚੋਂ ਮੇਰੀਆਂ ਸੋਧਾਂ ਹਟਾਓ',
'tog-watchlisthidebots' => 'ਮੇਰੀ ਵਾਚ-ਲਿਸਟ ਵਿੱਚੋਂ ਰੋਬਾਟ ਦਿਆਂ ਸੋਧਾਂ ਹਟਾਓ',
'tog-watchlisthideminor' => 'ਛੋਟੇ ਸੋਧ ਵਾਚ-ਲਿਸਟ ਤੋਂ ਓਹਲੇ ਰੱਖੋ',

'underline-always' => 'ਹਮੇਸ਼ਾਂ',
'underline-never' => 'ਕਦੇ ਨਹੀਂ',
'underline-default' => 'ਬਰਾਊਜ਼ਰ ਡਿਫਾਲਟ',

# Font style option in Special:Preferences
'editfont-default' => 'ਬਰਾਊਜ਼ਰ ਡਿਫਾਲਟ',

# Dates
'sunday' => 'ਐਤਵਾਰ',
'monday' => 'ਸੋਮਵਾਰ',
'tuesday' => 'ਮੰਗਲਵਾਰ',
'wednesday' => 'ਬੁੱਧਵਾਰ',
'thursday' => 'ਵੀਰਵਾਰ',
'friday' => 'ਸ਼ੁੱਕਰਵਾਰ',
'saturday' => 'ਸ਼ਨੀਵਾਰ',
'sun' => 'ਐਤ',
'mon' => 'ਸੋਮ',
'tue' => 'ਮੰਗਲ',
'wed' => 'ਬੁੱਧ',
'thu' => 'ਵੀਰ',
'fri' => 'ਸ਼ੁੱਕਰ',
'sat' => 'ਸ਼ਨੀ',
'january' => 'ਜਨਵਰੀ',
'february' => 'ਫ਼ਰਵਰੀ',
'march' => 'ਮਾਰਚ',
'april' => 'ਅਪਰੈਲ',
'may_long' => 'ਮਈ',
'june' => 'ਜੂਨ',
'july' => 'ਜੁਲਾਈ',
'august' => 'ਅਗਸਤ',
'september' => 'ਸਿਤੰਬਰ',
'october' => 'ਅਕਤੂਬਰ',
'november' => 'ਨਵੰਬਰ',
'december' => 'ਦਿਸੰਬਰ',
'january-gen' => 'ਜਨਵਰੀ',
'february-gen' => 'ਫ਼ਰਵਰੀ',
'march-gen' => 'ਮਾਰਚ',
'april-gen' => 'ਅਪਰੈਲ',
'may-gen' => 'ਮਈ',
'june-gen' => 'ਜੂਨ',
'july-gen' => 'ਜੁਲਾਈ',
'august-gen' => 'ਅਗਸਤ',
'september-gen' => 'ਸਿਤੰਬਰ',
'october-gen' => 'ਅਕਤੂਬਰ',
'november-gen' => 'ਨਵੰਬਰ',
'december-gen' => 'ਦਿਸੰਬਰ',
'jan' => 'ਜਨਵਰੀ',
'feb' => 'ਫ਼ਰਵਰੀ',
'mar' => 'ਮਾਰਚ',
'apr' => 'ਅਪਰੈਲ',
'may' => 'ਮਈ',
'jun' => 'ਜੂਨ',
'jul' => 'ਜੁਲਾਈ',
'aug' => 'ਅਗਸਤ',
'sep' => 'ਸਿਤੰਬਰ',
'oct' => 'ਅਕਤੂਬਰ',
'nov' => 'ਨਵੰਬਰ',
'dec' => 'ਦਿਸੰਬਰ',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|ਕੈਟਾਗਰੀ|ਕੈਟਾਗਰੀਆਂ}}',
'category_header' => 'ਕੈਟਾਗਰੀ "$1" ਵਿੱਚ ਲੇਖ',
'subcategories' => 'ਸਬ-ਕੈਟਾਗਰੀਆਂ',
'category-media-header' => 'ਕੈਟਾਗਰੀ "$1" ਵਿੱਚ ਮੀਡੀਆ',
'category-empty' => "''ਇਸ ਕੈਟਾਗਰੀ ਵਿੱਚ ਇਸ ਵੇਲ਼ੇ ਕੋਈ ਵੀ ਲੇਖ ਜਾਂ ਮੀਡੀਆ ਨਹੀਂ ਹੈ।''",
'hidden-categories' => '{{PLURAL:$1|ਲੁਕੀਵੀਂ ਸ਼੍ਰੇਣੀ|ਲੁਕਵੀਂਆਂ ਸ਼੍ਰੇਣੀਆਂ}}',
'category-subcat-count' => '{{ਕੁੱਲ $2 ਸ਼੍ਰੇਣੀਆਂ ਵਿਚੋਂ, PLURAL:$2|ਇਸ ਸ਼੍ਰੇਣੀ ਵਿਚ ਸਿਰਫ਼ ਹੇਠ ਲਿਖੀ ਸ਼੍ਰੇਣੀ ਹੈ| ਇਸ ਸ਼੍ਰੇਣੀ ਵਿਚ {{PLURAL:$1|ਉਪ ਸ਼੍ਰੇਣੀ ਹੈ|$1 ਉਪ-ਸ਼੍ਰੇਣੀਆਂ ਹਨ}}}}',
'category-article-count' => '{{PLURAL:$2|ਇਸ ਸ਼੍ਰੇਣੀ ਵਿਚ ਸਿਰਫ਼ ਇਹ ਸਫ਼ਾ ਹੈ|ਇਸ ਸ਼੍ਰੇਣੀ ਵਿਚ, ਕੁੱਲ $2 ਵਿਚੋਂ, ਇਹ {{PLURAL:$1|ਸਫ਼ਾ ਹੈ|$1 ਸਫ਼ੇ}} ਹਨ}}',
'category-file-count' => '{{PLURAL:$2|ਇਸ ਸ਼੍ਰੇਣੀ ਵਿਚ ਸਿਰਫ਼ ਇਹ ਫ਼ਾਈਲ ਹੈ।| ਇਸ ਸ਼੍ਰੇਣੀ ਵਿਚ {{PLURAL:$1|ਫ਼ਾਈਲ ਹੈ|$1 ਫ਼ਾਈਲਾਂ ਹਨ।}}}}',
'listingcontinuesabbrev' => 'ਜਾਰੀ',
'noindex-category' => 'ਕ੍ਰਮਸੂਚੀ ਰਹਿਤ ਸਫ਼ੇ',

'about' => 'ਇਸ ਬਾਰੇ',
'article' => 'ਸਮੱਗਰੀ ਪੇਜ',
'newwindow' => '(ਨਵੀਂ ਵਿੰਡੋ ਵਿੱਚ ਖੁੱਲ੍ਹਦੀ ਹੈ)',
'cancel' => 'ਰੱਦ ਕਰੋ',
'moredotdotdot' => 'ਹੋਰ...',
'mypage' => 'ਮੇਰਾ ਪੇਜ',
'mytalk' => 'ਮੇਰੀ ਗੱਲਬਾਤ',
'anontalk' => 'ਇਹ IP ਲਈ ਗੱਲਬਾਤ',
'navigation' => 'ਰਹਿਨੁਮਾਈ',
'and' => '&#32;ਅਤੇ',

# Cologne Blue skin
'qbfind' => 'ਖੋਜ',
'qbbrowse' => 'ਬਰਾਊਜ਼',
'qbedit' => 'ਸੋਧ',
'qbpageoptions' => 'ਇਹ ਪੇਜ',
'qbpageinfo' => 'ਭਾਗ',
'qbmyoptions' => 'ਮੇਰੇ ਪੇਜ',
'qbspecialpages' => 'ਖਾਸ ਪੇਜ',
'faq' => 'ਅਕਸਰ ਪੁੱਛੇ ਜਾਣ ਵਾਲ਼ੇ ਸਵਾਲ',
'faqpage' => 'Project:ਸਵਾਲ-ਜਵਾਬ',

# Vector skin
'vector-action-addsection' => 'ਮਜ਼ਮੂਨ ਜੋੜੋ',
'vector-action-delete' => 'ਮਿਟਾਓ',
'vector-action-move' => 'ਭੇਜੋ',
'vector-action-protect' => 'ਸੁਰੱਖਿਅਤ ਬਣਾਓ',
'vector-action-undelete' => 'ਹਟਾਉਣਾ ਵਾਪਸ',
'vector-action-unprotect' => 'ਸੁਰੱਖਿਆ ਬਦਲੋ',
'vector-view-create' => 'ਬਣਾਓ',
'vector-view-edit' => 'ਸੋਧ',
'vector-view-history' => 'ਅਤੀਤ ਵੇਖੋ',
'vector-view-view' => 'ਪੜ੍ਹੋ',
'vector-view-viewsource' => 'ਸਰੋਤ ਵੇਖੋ',
'actions' => 'ਕਾਰਵਾਈਆਂ',
'namespaces' => 'ਨਾਮ-ਥਾਂਵਾਂ',
'variants' => 'ਬਦਲ',

'errorpagetitle' => 'ਗ਼ਲਤੀ',
'returnto' => '$1 ’ਤੇ ਵਾਪਸ ਜਾਓ।',
'tagline' => '{{SITENAME}} ਤੋਂ',
'help' => 'ਮਦਦ',
'search' => 'ਖੋਜੋ',
'searchbutton' => 'ਖੋਜੋ',
'go' => 'ਜਾਓ',
'searcharticle' => 'ਜਾਓ',
'history' => 'ਸਫ਼ੇ ਦਾ ਅਤੀਤ',
'history_short' => 'ਅਤੀਤ',
'updatedmarker' => 'ਮੇਰੇ ਆਖਰੀ ਖੋਲ੍ਹਣ ਬਾਦ ਅੱਪਡੇਟ',
'printableversion' => 'ਛਪਣਯੋਗ ਵਰਜਨ',
'permalink' => 'ਪੱਕਾ ਲਿੰਕ',
'print' => 'ਪਰਿੰਟ ਕਰੋ',
'view' => 'ਵੇਖੋ',
'edit' => 'ਬਦਲੋ',
'create' => 'ਬਣਾਓ',
'editthispage' => 'ਇਹ ਪੇਜ ਸੋਧੋ',
'create-this-page' => 'ਇਹ ਸਫ਼ਾ ਬਣਾਓ',
'delete' => 'ਮਿਟਾਓ',
'deletethispage' => 'ਇਹ ਪੇਜ ਹਟਾਓ',
'undelete_short' => 'ਅਣ-ਹਟਾਓ {{PLURAL:$1|one edit|$1 edits}}',
'protect' => 'ਸੁਰੱਖਿਆ',
'protect_change' => 'ਤਬਦੀਲੀ',
'protectthispage' => 'ਇਹ ਪੇਜ ਸੁਰੱਖਿਅਤ ਕਰੋ',
'unprotect' => 'ਸੁਰੱਖਿਆ ਬਦਲੋ',
'unprotectthispage' => 'ਇਹ ਪੇਜ਼ ਦੀ ਸੁਰੱਖਿਆ ਬਦਲੋ',
'newpage' => 'ਨਵਾਂ ਸਫ਼ਾ',
'talkpage' => 'ਇਸ ਪੇਜ ਬਾਰੇ ਚਰਚਾ',
'talkpagelinktext' => 'ਗੱਲਬਾਤ',
'specialpage' => 'ਖਾਸ ਪੇਜ',
'personaltools' => 'ਨਿੱਜੀ ਸੰਦ',
'postcomment' => 'ਨਵਾਂ ਭਾਗ',
'articlepage' => 'ਸਮੱਗਰੀ ਪੇਜ ਵੇਖੋ',
'talk' => 'ਚਰਚਾ',
'views' => 'ਵੇਖੋ',
'toolbox' => 'ਸੰਦ ਬਕਸਾ',
'userpage' => 'ਯੂਜ਼ਰ ਪੇਜ ਵੇਖੋ',
'projectpage' => 'ਪਰੋਜੈਕਟ ਪੇਜ ਵੇਖੋ',
'imagepage' => 'ਫਾਇਲ ਪੇਜ ਵੇਖੋ',
'mediawikipage' => 'ਸੁਨੇਹਾ ਪੇਜ ਵੇਖੋ',
'templatepage' => 'ਟੈਪਲੇਟ ਪੇਜ ਵੇਖੋ',
'viewhelppage' => 'ਮੱਦਦ ਪੇਜ ਵੇਖੋ',
'categorypage' => 'ਕੈਟਾਗਰੀ ਪੇਜ ਵੇਖੋ',
'viewtalkpage' => 'ਚਰਚਾ ਵੇਖੋ',
'otherlanguages' => 'ਹੋਰ ਜ਼ਬਾਨਾਂ ਵਿਚ',
'redirectedfrom' => '($1 ਤੋਂ ਰੀ-ਡਿਰੈਕਟ)',
'redirectpagesub' => 'ਰੀ-ਡਿਰੈਕਟ ਪੇਜ',
'lastmodifiedat' => 'ਇਹ ਸਫ਼ਾ ਆਖ਼ਰੀ ਵਾਰ $1 ਨੂੰ $2 ’ਤੇ ਸੋਧਿਆ ਗਿਆ ਸੀ।',
'viewcount' => 'ਇਹ ਪੇਜ ਅਸੈੱਸ ਕੀਤਾ ਗਿਆ {{PLURAL:$1|ਇੱਕਵਾਰ|$1 ਵਾਰ}}.',
'protectedpage' => 'ਸੁਰੱਖਿਅਤ ਪੇਜ',
'jumpto' => 'ਇਸ ’ਤੇ ਜਾਓ:',
'jumptonavigation' => 'ਰਹਿਨੁਮਾਈ',
'jumptosearch' => 'ਖੋਜੋ',
'pool-errorunknown' => 'ਅਣਜਾਣ ਗਲਤੀ',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => '{{SITENAME}} ਬਾਰੇ',
'aboutpage' => 'Project:ਬਾਰੇ',
'copyright' => 'ਸਮੱਗਰੀ $1 ਹੇਠ ਉਪਲੱਬਧ ਹੈ।',
'copyrightpage' => '{{ns:project}}:ਕਾਪੀਰਾਈਟ',
'currentevents' => 'ਮੌਜੂਦਾ ਇਵੈਂਟ',
'currentevents-url' => 'Project:ਮੌਜੂਦਾ ਈਵੈਂਟ',
'disclaimers' => 'ਇਨਕਾਰੀ ਐਲਾਨ',
'disclaimerpage' => 'Project:ਆਮ ਇਨਕਾਰ',
'edithelp' => 'ਮੱਦਦ ਐਡੀਟਿੰਗ',
'edithelppage' => 'Help:ਐਡਟਿੰਗ',
'helppage' => 'Help:ਚੀਜ਼ਾਂ',
'mainpage' => 'ਮੁੱਖ ਸਫ਼ਾ',
'mainpage-description' => 'ਮੁੱਖ ਸਫ਼ਾ',
'policy-url' => 'Project:ਪਾਲਸੀ',
'portal' => 'ਕਮਿਊਨਟੀ ਪੋਰਟਲ',
'portal-url' => 'Project:ਕਮਿਊਨਟੀ ਪੋਰਟਲ',
'privacy' => 'ਪਰਾਈਵੇਸੀ ਪਾਲਸੀ',
'privacypage' => 'Project:ਪਰਾਈਵੇਸੀ ਪਾਲਸੀ',

'badaccess' => 'ਅਧਿਕਾਰ ਗਲਤੀ',
'badaccess-group0' => 'ਤੁਹਾਨੂੰ ਉਹ ਐਕਸ਼ਨ ਕਰਨ ਦੀ ਮਨਜ਼ੂਰੀ ਨਹੀਂ, ਜਿਸ ਦੀ ਤੁਸੀਂ ਮੰਗ ਕੀਤੀ ਹੈ।',

'ok' => 'ਠੀਕ ਹੈ',
'retrievedfrom' => '"$1" ਤੋਂ ਲਿਆ',
'youhavenewmessages' => 'ਤੁਹਾਡੇ ਲਈ $1। ($2)',
'newmessageslink' => 'ਨਵੇਂ ਸੁਨੇਹੇ',
'newmessagesdifflink' => 'ਆਖ਼ਰੀ ਤਬਦੀਲੀ',
'youhavenewmessagesmulti' => 'ਤੁਹਾਨੂੰ ਨਵੇਂ ਸੁਨੇਹੇ $1 ਉੱਤੇ ਹਨ',
'editsection' => 'ਸੋਧ',
'editold' => 'ਸੋਧੋ',
'viewsourceold' => 'ਸਰੋਤ ਵੇਖੋ',
'editlink' => 'ਸੋਧ',
'viewsourcelink' => 'ਸਰੋਤ ਵੇਖੋ',
'editsectionhint' => 'ਸ਼ੈਕਸ਼ਨ ਸੋਧ: $1',
'toc' => 'ਲਿਸਟ',
'showtoc' => 'ਵੇਖੋ',
'hidetoc' => 'ਓਹਲੇ',
'collapsible-collapse' => 'ਸਮੇਟੋ',
'collapsible-expand' => 'ਫੈਲਾਓ',
'thisisdeleted' => 'ਵੇਖੋ ਜਾਂ $1 ਰੀਸਟੋਰ?',
'viewdeleted' => '$1 ਵੇਖਣਾ?',
'feedlinks' => 'ਫੀਡ:',
'site-rss-feed' => '$1 RSS ਫੀਡ',
'site-atom-feed' => '$1 ਐਟਮ ਫੀਡ',
'page-rss-feed' => '"$1" RSS ਫੀਡ',
'page-atom-feed' => '"$1" ਐਟਮ ਫੀਡ',
'red-link-title' => '$1 (ਸਫ਼ਾ ਮੌਜੂਦ ਨਹੀਂ ਹੈ)',
'sort-descending' => 'ਘੱਟਦਾ ਕ੍ਰਮ',
'sort-ascending' => 'ਵੱਧਦਾ ਕ੍ਰਮ',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'ਲੇਖ',
'nstab-user' => 'ਮੈਂਬਰ ਸਫ਼ਾ',
'nstab-media' => 'ਮੀਡਿਆ ਪੇਜ',
'nstab-special' => 'ਖ਼ਾਸ ਸਫ਼ਾ',
'nstab-project' => 'ਪ੍ਰੋਜੈਕਟ ਸਫ਼ਾ',
'nstab-image' => 'ਫ਼ਾਈਲ',
'nstab-mediawiki' => 'ਸੁਨੇਹਾ',
'nstab-template' => 'ਸਾਂਚਾ',
'nstab-help' => 'ਮੱਦਦ ਪੇਜ',
'nstab-category' => 'ਕੈਟਾਗਰੀ',

# Main script and global functions
'nosuchaction' => 'ਕੋਈ ਇੰਝ ਦਾ ਐਕਸ਼ਨ ਨਹੀਂ',
'nosuchspecialpage' => 'ਕੋਈ ਇੰਝ ਦਾ ਖਾਸ ਪੇਜ ਨਹੀਂ',
'nospecialpagetext' => '<strong>ਤੁਸੀਂ ਇੱਕ ਅਵੈਧ ਖਾਸ ਪੇਜ ਦੀ ਮੰਗ ਕੀਤੀ ਹੈ।</strong>

A list of valid special pages can be found at [[Special:SpecialPages]].',

# General errors
'error' => 'ਗਲਤੀ',
'databaseerror' => 'ਡਾਟਾਬੇਸ ਗਲਤੀ',
'readonly' => 'ਡਾਟਾਬੇਸ ਲਾਕ ਹੈ',
'missing-article' => "ਡਾਟਾਬੇਸ ਨੂੰ ''$1'' $2 ਨਾਮ ਦਾ ਕੋਈ ਸਫ਼ਾ ਨਹੀਂ ਮਿਲਿਆ।
ਆਮ ਤੌਰ ਤੇ ਮਿਟਾਏ ਜਾ ਚੁੱਕੇ ਸਫ਼ੇ ਦੀ ਅਤੀਤ ਕੜੀ ਦੀ ਵਰਤੋਂ ਕਰਨ ਨਾਲ਼ ਇੰਝ ਹੁੰਦਾ ਹੈ।
ਜੇ ਇਹ ਗੱਲ ਨਹੀਂ ਤਾਂ ਹੋ ਸਕਦਾ ਹੈ ਤੁਹਾਨੂੰ ਸਾਫ਼ਟਵੇਅਰ ਵਿਚ ਖ਼ਾਮੀ ਮਿਲ ਗਈ ਹੈ। ਮਿਹਰਬਾਨੀ ਕਰਕੇ ਸਫ਼ੇ ਦੇ ਪਤੇ ਸਮੇਤ [[Special:ListUsers/sysop|administrator]] ਨੂੰ ਇਤਲਾਹ ਦਿਓ।",
'internalerror' => 'ਅੰਦਰੂਨੀ ਗਲਤੀ',
'internalerror_info' => 'ਅੰਦਰੂਨੀ ਗਲਤੀ: $1',
'badtitle' => 'ਗ਼ਲਤ ਸਿਰਲੇਖ',
'badtitletext' => 'ਤੁਹਾਡਾ ਦਰਖ਼ਾਸਤਸ਼ੁਦਾ ਸਿਰਲੇਖ ਨਾਕਾਬਿਲ, ਖ਼ਾਲੀ ਜਾਂ ਗ਼ਲਤ ਜੁੜਿਆ ਹੋਇਆ inter-languagd ਜਾਂ inter-wiki ਸਿਰਲੇਖ ਹੈ। ਇਹ ਵੀ ਹੋ ਸਕਦਾ ਹੈ ਕਿ ਇਸ ਵਿਚ ਇਕ-ਦੋ ਅੱਖਰ ਐਸੇ ਹੋਣ ਜੋ ਸਿਰਲੇਖ ਵਿਚ ਵਰਤੇ ਨਹੀਂ ਜਾ ਸਕਦੇ।',
'viewsource' => 'ਸਰੋਤ ਵੇਖੋ',
'protectedpagetext' => 'ਇਸ ਪੰਨੇ ਨੂੰ ਐਡਿਟ ਕਰਨ ਦੀ ਮਨਾਹੀ ਹੈ।',
'viewsourcetext' => 'ਤੁਸੀਂ ਇਸ ਪੰਨੇ ਦਾ ਸੋਮਾ ਦੇਖ ਸਕਦੇ ਹੋ ਤੇ ਉਸ ਦਾ ਉਤਾਰਾ ਵੀ ਲੈ ਸਕਦੇ ਹੋ।',
'viewyourtext' => 'ਤੁਸੀਂ ਇਸ ਪੰਨੇ ਬਾਰੇ " ਆਪਣੇ ਸੰਪਾਦਨਾਂ " ਨੂੰ ਦੇਖ ਸਕਦੇ ਹੋ ਤੇ ਉਨ੍ਹਾਂ ਦਾ ਉਤਾਰਾ ਵਿ ਲੈ ਸਕਦੇ ਹੋ।',
'protectedinterface' => 'ਇਹ ਪੰਨਾ ਸਾਫ਼ਟਵੇਅਰ ਇੰਟਰਫ਼ੇਸ ਦਾ ਮੂਲ ਪਾਠ ਹੈ ,ਅਤੇ ਦੁਰਵਰਤੌਂ ਤੌਂ ਬਚਾਅ ਲਈ ਰਾਖਵਾਂ ਕੀਤਾ ਗਿਆ ਹੈ।',
'editinginterface' => "'''ਚਿਤਾਵਨੀ''' ਤੁਸੀਂ ਐਸੇ ਪੰਨੇ ਨੂੰ ਬਦਲ ਰਹੇ ਹੋ ਜੋ ਸਾਫ਼ਟਵੇਅਰ ਇੰਟਰਫ਼ੇਸ ਦੇ ਮੂਲ ਪਾਠ ਲਈ ਵਰਤਿਆ ਗਿਆ ਹੈ।
ਇਸ ਪੰਨੇ ਦੇ ਬਦਲਾਅ ਦੁਸਰੇ ਵਰਤੋਂ ਕਰਣ ਵਾਲਿਆਂ ਲਈ ਵਰਤੇ ਜਾਣ ਵਾਲੇ ਇੰਟਰਫਲੇਸ ਦੀ ਸ਼ਕਲ ਤੇ ਅਸਰ ਪਾ ਦੇਣਗੇ।ਅਨੁਵਾਦ ਕਰਣ ਲਈ ,ਕਿਰਪਾ ਕਰਕੇ [//translatewiki.net/wiki/Main_Page?setlang=pa ਟ੍ਰਾਂਸਲੇਟਵਿਕੀ.ਨੈਟ] ਦੀ ਵਰਤੌਂ ਕਰੋ,ਇਹ ਮੀਡੀਆਵਿਕੀ ਦੀ ਸਥਾਨਕੀਕਰਣ ਯੋਜਨਾ ਹੈ।",

# Login and logout pages
'logouttext' => "'''ਹੁਣ ਤੁਸੀਂ ਲਾਗਆਉਟ ਹੋ ਗਏ ਹੋ।'''

You can continue to use {{SITENAME}} anonymously, or you can log in again as the same or as a different user.
Note that some pages may continue to be displayed as if you were still logged in, until you clear your browser cache.",
'welcomecreation' => '== ਜੀ ਆਇਆਂ ਨੂੰ! ==

ਤੁਹਾਡਾ ਖ਼ਾਤਾ ਬਣ ਚੁੱਕਾ ਹੈ। ਆਪਣੀ [[Special:ਪਸੰਦ|{{SITENAME}} ਪਸੰਦ]] ਬਦਲਣੀ ਨਾ ਭੁੱਲੋ।',
'yourname' => 'ਮੈਂਬਰ ਨਾਂ:',
'yourpassword' => 'ਪਾਸਵਰਡ:',
'yourpasswordagain' => 'ਪਾਸਵਰਡ ਦੁਬਾਰਾ ਲਿਖੋ:',
'remembermypassword' => 'ਇਸ ਕੰਪਿਊਟਰ ’ਤੇ ਮੇਰਾ ਲਾਗਇਨ ਯਾਦ ਰੱਖੋ (ਵੱਧ ਤੋਂ ਵੱਧ $1 {{PLURAL:$1|ਦਿਨ|ਦਿਨਾਂ}} ਲਈ)',
'yourdomainname' => 'ਤੁਹਾਡੀ ਡੋਮੇਨ:',
'login' => 'ਲਾਗ ਇਨ',
'nav-login-createaccount' => 'ਲਾਗ ਇਨ/ਖਾਤਾ ਬਣਾਓ',
'loginprompt' => 'ਤੁਹਾਨੂੰ {{SITENAME}} ’ਤੇ ਲਾਗਇਨ ਕਰਨ ਲਈ ਕੂਕੀਜ਼ ਯੋਗ ਕਰਨੇ ਜ਼ਰੂਰੀ ਹਨ।',
'userlogin' => 'ਲਾਗ ਇਨ/ਖਾਤਾ ਖੋਲ੍ਹੋ',
'userloginnocreate' => 'ਲਾਗ ਇਨ',
'logout' => 'ਲਾਗ ਆਉਟ',
'userlogout' => 'ਲਾਗ ਆਉਟ',
'notloggedin' => 'ਲਾਗਇਨ ਨਹੀਂ',
'nologin' => 'ਖਾਤਾ ਨਹੀਂ ਹੈ? $1।',
'nologinlink' => 'ਖਾਤਾ ਬਣਾਓ',
'createaccount' => 'ਖਾਤਾ ਬਣਾਓ',
'gotaccount' => "ਖਾਤਾ ਹੈ? '''$1'''।",
'gotaccountlink' => 'ਲਾਗ ਇਨ',
'userlogin-resetlink' => 'ਆਪਣੀ ਲਾਗਇਨ ਜਾਣਕਾਰੀ ਭੁੱਲ ਗਏ ਹੋ?',
'createaccountmail' => 'ਈਮੇਲ ਨਾਲ',
'createaccountreason' => 'ਕਾਰਨ:',
'badretype' => 'ਤੁਹਾਡੇ ਵਲੋਂ ਦਿੱਤੇ ਪਾਸਵਰਡ ਮਿਲਦੇ ਨਹੀਂ ਹਨ।',
'userexists' => 'ਇਹ ਮੈਂਬਰ-ਨਾਮ ਪਹਿਲਾਂ ਹੀ ਵਰਤੋਂ ’ਚ ਹੈ।
ਮਿਹਰਬਾਨੀ ਕਰਕੇ ਵੱਖਰਾ ਮੈਂਬਰ-ਨਾਮ ਵਰਤੋਂ।',
'loginerror' => 'ਲਾਗਇਨ ਗਲਤੀ',
'createaccounterror' => 'ਅਕਾਊਂਟ ਬਣਾਇਆ ਨਹੀਂ ਜਾ ਸਕਿਆ: $1',
'nocookiesnew' => 'ਯੂਜ਼ਰ ਅਕਾਊਂਟ ਬਣਾਇਆ ਗਿਆ ਹੈ, ਪਰ ਤੁਸੀਂ ਲਾਗਇਨ ਨਹੀਂ ਕੀਤਾ ਹੈ।{{SITENAME}} uses cookies to log in users. You have cookies disabled. Please enable them, then log in with your new username and password.',
'nocookieslogin' => '{{SITENAME}} ਯੂਜ਼ਰਾਂ ਨੂੰ ਲਾਗਇਨ ਕਰਨ ਲਈ ਕੂਕੀਜ਼ ਵਰਤਦੀ ਹੈ। ਤੁਹਾਡੇ ਕੂਕੀਜ਼ ਆਯੋਗ ਕੀਤੇ ਹੋਏ ਹਨ। ਉਨ੍ਹਾਂ ਨੂੰ ਯੋਗ ਕਰਕੇ ਮੁੜ ਟਰਾਈ ਕਰੋ।',
'noname' => 'ਤੁਸੀਂ ਇੱਕ ਵੈਧ ਯੂਜ਼ਰ ਨਾਂ ਨਹੀਂ ਦਿੱਤਾ ਹੈ।',
'loginsuccesstitle' => 'ਲਾਗਇਨ ਸਫ਼ਲ ਰਿਹਾ',
'loginsuccess' => "'''ਤੁਸੀਂ {{SITENAME}} ਉੱਤੇ \"\$1\" ਵਾਂਗ ਲਾਗਇਨ ਕਰ ਚੁੱਕੇ ਹੋ।'''",
'nosuchuser' => '"$1" ਨਾਂ ਨਾਲ ਕੋਈ ਯੂਜ਼ਰ ਨਹੀਂ ਹੈ। ਆਪਣੇ ਸ਼ਬਦ ਧਿਆਨ ਨਾਲ ਚੈੱਕ ਕਰੋ ਜਾਂ ਨਵਾਂ ਅਕਾਊਂਟ ਬਣਾਓ।',
'nosuchusershort' => '"$1" ਨਾਂ ਨਾਲ ਕੋਈ ਵੀ ਯੂਜ਼ਰ ਨਹੀਂ ਹੈ। ਆਪਣੇ ਸ਼ਬਦ ਧਿਆਨ ਨਾਲ ਚੈੱਕ ਕਰੋ।',
'nouserspecified' => 'ਤੁਹਾਨੂੰ ਇੱਕ ਯੂਜ਼ਰ-ਨਾਂ ਦੇਣਾ ਪਵੇਗਾ।',
'wrongpassword' => 'ਗਲਤ ਪਾਸਵਰਡ ਦਿੱਤਾ ਹੈ। ਮੁੜ-ਟਰਾਈ ਕਰੋ ਜੀ।',
'wrongpasswordempty' => 'ਖਾਲੀ ਪਾਸਵਰਡ ਦਿੱਤਾ ਹੈ। ਮੁੜ-ਟਰਾਈ ਕਰੋ ਜੀ।',
'passwordtooshort' => 'ਪਾਸਵਰਡ {{PLURAL:$1|1 ਅੱਖਰ|$1 ਅੱਖਰਾਂ}} ਦਾ ਹੋਣਾ ਲਾਜ਼ਮੀ ਹੈ।',
'password-name-match' => 'ਤੁਹਾਡਾ ਪਾਸਵਰਡ ਤੁਹਾਡੇ ਯੂਜ਼ਰ ਨਾਂ ਤੋਂ ਵੱਖਰਾ ਹੋਣਾ ਚਾਹੀਦਾ ਹੈ।',
'mailmypassword' => 'ਨਵਾਂ ਪਾਸਵਰਡ ਈ-ਮੇਲ ਕਰੋ',
'passwordremindertitle' => '{{SITENAME}} ਲਈ ਪਾਸਵਰਡ ਯਾਦ ਰੱਖੋ',
'passwordremindertext' => 'ਕਿਸੇ ਨੇ (ਸ਼ਾਇਦ ਤੁਸੀਂ, IP ਐਡਰੈੱਸ $1 ਤੋਂ)
ਮੰਗ ਕੀਤੀ ਸੀ ਕਿ ਅਸੀਂ ਤੁਹਾਨੂੰ {{SITENAME}} ($4) ਲਈ ਪਾਸਵਰਡ ਭੇਜੀਏ।
ਯੂਜ਼ਰ "$2" ਲਈ ਹੁਣ ਪਾਸਵਰਡ "$3" ਹੈ।
ਤੁਹਾਨੂੰ ਹੁਣ ਲਾਗਇਨ ਕਰਕੇ ਆਪਣਾ ਪਾਸਵਰਡ ਹੁਣੇ ਬਦਲਣਾ ਚਾਹੀਦਾ ਹੈ।

If someone else made this request or if you have remembered your password and
you no longer wish to change it, you may ignore this message and continue using
your old password.',
'noemail' => 'ਯੂਜ਼ਰ "$1" ਲਈ ਰਿਕਾਰਡ ਵਿੱਚ ਕੋਈ ਈਮੇਲ ਐਡਰੈੱਸ ਨਹੀਂ ਹੈ।',
'noemailcreate' => 'ਤੁਹਾਨੂੰ ਠੀਕ ਈਮੇਲ ਐਡਰੈੱਸ ਦੇਣਾ ਪਵੇਗਾ',
'passwordsent' => '"$1" ਨਾਲ ਰਜਿਸਟਰ ਕੀਤੇ ਈਮੇਲ ਐਡਰੈੱਸ ਉੱਤੇ ਈਮੇਲ ਭੇਜੀ ਗਈ ਹੈ।
ਇਹ ਮਿਲ ਦੇ ਬਾਅਦ ਮੁੜ ਲਾਗਇਨ ਕਰੋ ਜੀ।',
'throttled-mailpassword' => 'ਇੱਕ ਪਾਸਵਰਡ ਰੀਮਾਈਡਰ ਪਹਿਲਾਂ ਹੀ ਭੇਜਿਆ ਗਿਆ ਹੈ, ਆਖਰੀ
$1 ਘੰਟੇ ਵਿੱਚ। ਨੁਕਸਾਨ ਤੋਂ ਬਚਣ ਲਈ, $1 ਘੰਟਿਆਂ ਵਿੱਚ ਇੱਕ ਹੀ ਪਾਸਵਰਡ ਰੀਮਾਈਡਰ ਭੇਜਿਆ ਜਾਂਦਾ ਹੈ।',
'mailerror' => 'ਈਮੇਲ ਭੇਜਣ ਦੌਰਾਨ ਗਲਤੀ: $1',
'acct_creation_throttle_hit' => 'ਅਫਸੋਸ ਹੈ, ਪਰ ਤੁਸੀਂ ਪਹਿਲਾਂ ਹੀ $1 ਅਕਾਊਂਟ ਬਣਾ ਚੁੱਕੇ ਹੋ। ਤੁਸੀਂ ਹੋਰ ਨਹੀਂ ਬਣਾ ਸਕਦੇ।',
'emailauthenticated' => 'ਤੁਹਾਡਾ ਈਮੇਲ ਐਡਰੈੱਸ $1 ਉੱਤੇ ਪਰਮਾਣਿਤ ਕੀਤਾ ਗਿਆ ਹੈ।',
'emailnotauthenticated' => 'ਤੁਹਾਡਾ ਈਮੇਲ ਐਡਰੈੱਸ ਹਾਲੇ ਪਰਮਾਣਿਤ ਨਹੀਂ ਹੈ। ਹੇਠ ਦਿੱਤੇ ਫੀਚਰਾਂ ਲਈ ਕੋਈ ਵੀ ਈਮੇਲ ਨਹੀਂ ਭੇਜੀ ਜਾਵੇਗੀ।',
'noemailprefs' => 'ਇਹ ਫੀਚਰ ਵਰਤਣ ਲਈ ਇੱਕ ਈਮੇਲ ਐਡਰੈੱਸ ਦਿਓ।।',
'emailconfirmlink' => 'ਆਪਣਾ ਈ-ਮੇਲ ਐਡਰੈੱਸ ਕਨਫਰਮ ਕਰੋ।',
'invalidemailaddress' => 'ਈਮੇਲ ਐਡਰੈੱਸ ਮਨਜ਼ੂਰ ਨਹੀਂ ਕੀਤਾ ਜਾ ਸਕਦਾ ਹੈ ਕਿਉਂਕਿ ਇਹ ਠੀਕ ਫਾਰਮੈਟ ਨਹੀਂ ਜਾਪਦਾ ਹੈ। ਇੱਕ ਠੀਕ ਫਾਰਮੈਟ ਵਿੱਚ ਦਿਓ ਜਾਂ ਇਹ ਖੇਤਰ ਖਾਲੀ ਛੱਡ ਦਿਓ।',
'accountcreated' => 'ਅਕਾਊਂਟ ਬਣਾਇਆ',
'accountcreatedtext' => '$1 ਲਈ ਯੂਜ਼ਰ ਅਕਾਊਂਟ ਬਣਾਇਆ ਗਿਆ।',
'createaccount-title' => '{{SITENAME}} ਲਈ ਅਕਾਊਂਟ ਬਣਾਉਣਾ',
'loginlanguagelabel' => 'ਬੋਲੀ: $1',

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
'resetpass-submit-loggedin' => 'ਪਾਸਵਰਡ ਬਦਲੋ',
'resetpass-submit-cancel' => 'ਰੱਦ ਕਰੋ',
'resetpass-temp-password' => 'ਆਰਜ਼ੀ ਪਾਸਵਰਡ:',

# Edit page toolbar
'bold_sample' => 'ਗੂੜ੍ਹੀ ਲਿਖਾਈ',
'bold_tip' => 'ਗੁੜ੍ਹੀ ਲਿਖਾਈ',
'italic_sample' => 'ਟੇਢੀ ਲਿਖਤ',
'italic_tip' => 'ਟੇਢੀ ਲਿਖਾਈ',
'link_sample' => 'ਲਿੰਕ ਦਾ ਸਿਰਲੇਖ',
'link_tip' => 'ਅੰਦਰੂਨੀ ਲਿੰਕ',
'extlink_sample' => 'http://www.example.com ਲਿੰਕ ਸਿਰਲੇਖ',
'extlink_tip' => 'ਬਾਹਰੀ ਲਿੰਕ (ਅਗੇਤਰ http:// ਯਾਦ ਰੱਖੋ)',
'headline_sample' => 'ਸੁਰਖ਼ੀ ਦੀ ਲਿਖਤ',
'headline_tip' => 'ਦੂਜੇ ਦਰਜੇ ਦਾ ਸਿਰਲੇਖ',
'nowiki_sample' => 'ਅਸੰਗਠਿਤ ਪਾਠ (NON -FORMATTED) ਇੱਥੇ ਰਖੋ।',
'nowiki_tip' => 'ਵਿਕੀ ਫ਼ੌਰਮੈਟਿੰਗ ਨਜ਼ਰਅੰਦਾਜ਼ ਕਰੋ',
'image_tip' => 'ਇੰਬੈੱਡ ਚਿੱਤਰ',
'media_tip' => 'ਮੀਡਿਆ ਫਾਇਲ ਲਿੰਕ',
'sig_tip' => 'ਤੁਹਾਡੇ ਦਸਤਖ਼ਤ ਵਕਤ ਸਮੇਤ',
'hr_tip' => 'ਲੇਟਵੀਂ ਲਾਈਨ (use sparingly)',

# Edit pages
'summary' => 'ਸਾਰ:',
'subject' => 'ਵਿਸ਼ਾ/ਹੈੱਡਲਾਈਨ:',
'minoredit' => 'ਇਹ ਛੋਟੀ ਸੋਧ ਹੈ',
'watchthis' => 'ਇਸ ਸਫ਼ੇ ’ਤੇ ਨਜ਼ਰ ਰੱਖੋ',
'savearticle' => 'ਸਫ਼ਾ ਸਾਂਭੋ',
'preview' => 'ਝਲਕ',
'showpreview' => 'ਝਲਕ ਵੇਖੋ',
'showlivepreview' => 'ਲਾਈਵ ਝਲਕ',
'showdiff' => 'ਤਬਦੀਲੀ ਵੇਖੋ',
'anoneditwarning' => "'''ਚੇਤਾਵਨੀ:''' ਤੁਸੀਂ ਲਾਗਇਨ ਨਹੀਂ ਕੀਤਾ ਹੈ। ਤੁਹਾਡਾ IP ਐਡਰੈੱਸ ਇਸ ਸਫ਼ੇ ਦੇ ਅਤੀਤ ਵਿੱਚ ਰਿਕਾਰਡ ਕੀਤਾ ਜਾਵੇਗਾ।",
'missingcommenttext' => 'ਹੇਠਾਂ ਇੱਕ ਟਿੱਪਣੀ ਦਿਓ।',
'summary-preview' => 'ਸੰਖੇਪ ਝਲਕ:',
'subject-preview' => 'ਵਿਸ਼ਾ/ਹੈੱਡਲਾਈਨ ਝਲਕ:',
'blockedtitle' => 'ਯੂਜ਼ਰ ਬਲਾਕ ਕੀਤਾ ਗਿਆ',
'whitelistedittext' => 'ਪੇਜ ਸੋਧਣ ਲਈ ਤੁਹਾਨੂੰ $1 ਕਰਨਾ ਪਵੇਗਾ।',
'nosuchsectiontitle' => 'ਇੰਝ ਦਾ ਕੋਈ ਸ਼ੈਕਸ਼ਨ ਨਹੀਂ ਹੈ।',
'loginreqtitle' => 'ਲਾਗਇਨ ਚਾਹੀਦਾ ਹੈ',
'loginreqlink' => 'ਲਾਗਇਨ',
'loginreqpagetext' => 'ਹੋਰ ਪੇਜ ਵੇਖਣ ਲਈ ਤੁਹਾਨੂੰ $1 ਕਰਨਾ ਪਵੇਗਾ।',
'accmailtitle' => 'ਪਾਸਵਰਡ ਭੇਜਿਆ।',
'accmailtext' => '"$1" ਲਈ ਪਾਸਵਰਡ $2 ਨੂੰ ਭੇਜਿਆ ਗਿਆ।',
'newarticle' => '(ਨਵਾਂ)',
'newarticletext' => "ਤੁਸੀਂ ਕਿਸੇ ਐਸੇ ਲਿੰਕ ਰਾਹੀਂ ਇਸ ਸਫ਼ੇ ’ਤੇ ਪੁੱਜੇ ਹੋ ਜੋ ਹਾਲੇ ਬਣਾਇਆ ਨਹੀਂ ਗਿਆ।
ਸਫ਼ਾ ਬਣਾਉਣ ਲਈ ਹੇਠ ਦਿੱਤੇ ਖ਼ਾਨੇ ਵਿਚ ਲਿਖਣਾ ਸ਼ੁਰੂ ਕਰੋ। (ਹੋਰ ਮਦਦ ਲਈ [[{{MediaWiki:Helppage}}|ਮਦਦ ਸਫ਼ਾ]] ਵੇਖੋ)
ਜੇ ਤੁਸੀਂ ਗ਼ਲਤੀ ਨਾਲ਼ ਇੱਥੇ ਆਏ ਹੋ ਤਾਂ ਆਪਣੇ ਬਰਾਊਜ਼ਰ ਦੇ ''ਪਿੱਛੇ'' (back) ਬਟਨ ’ਤੇ ਕਲਿੱਕ ਕਰੋ।",
'noarticletext' => 'ਫ਼ਿਲਹਾਲ ਇਸ ਸਫ਼ੇ ’ਤੇ ਕੋਈ ਲਿਖਤ ਨਹੀਂ ਹੈ। ਤੁਸੀਂ ਦੂਜੇ ਸਫ਼ਿਆਂ ’ਤੇ [[Special:Search/{{PAGENAME}}|ਇਸ ਸਿਰਲੇਖ ਦੀ ਖੋਜ]] ਕਰ ਸਕਦੇ ਹੋ, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} ਸਬੰਧਿਤ ਚਿੱਠੇ ਖੋਜ] ਸਕਦੇ ਹੋ ਜਾਂ ਇਸ [{{fullurl:{{FULLPAGENAME}}|action=edit}} ਸਫ਼ੇ ਵਿਚ ਲਿਖ] ਸਕਦੇ ਹੋ</span>।',
'noarticletext-nopermission' => 'ਫ਼ਿਲਹਾਲ ਇਸ ਸਫ਼ੇ ’ਤੇ ਕੋਈ ਲਿਖਤ ਨਹੀਂ ਹੈ। ਤੁਸੀਂ ਦੂਸਰੇ ਸਫ਼ਿਆਂ ਤੇ [[Special:Search/{{PAGENAME}}|ਇਸ ਪਾਠ ਦੀ ਖੋਜ]] ਕਰ ਸਕਦੇ ਹੋ, ਸਬੰਧਤ <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} ਚਿੱਠੇ] ਖੋਜ ਸਕਦੇ ਹੋ, ਜਾਂ [{{fullurl:{{FULLPAGENAME}}|action=edit}} ਇਸ ਸਫ਼ੇ ਵਿਚ ਲਿਖ] ਸਕਦੇ ਹੋ</span>।',
'updated' => '(ਅੱਪਡੇਟ)',
'note' => "'''ਨੋਟ:'''",
'previewnote' => 'ਯਾਦ ਰੱਖੋ ਇਹ ਸਿਰਫ਼ ਇੱਕ ਝਲਕ ਹੈ; ਤੁਹਾਡੀਆਂ ਤਬਦੀਲੀਆਂ ਹਾਲੇ ਸਾਂਭੀਆਂ ਨਹੀਂ ਗਈਆਂ!',
'editing' => '$1 ਸੋਧਿਆ ਜਾ ਰਿਹਾ ਹੈ',
'editingsection' => '$1 ਜ਼ੇਰੇ ਸੁਧਾਈ ਹੈ (ਸ਼ੈਕਸ਼ਨ)',
'editingcomment' => '$1 (ਟਿੱਪਣੀ) ਸੋਧ',
'editconflict' => 'ਅਪਵਾਦ ਟਿੱਪਣੀ: $1',
'yourtext' => 'ਤੁਹਾਡਾ ਟੈਕਸਟ',
'storedversion' => 'ਸੰਭਾਲਿਆ ਵਰਜਨ',
'yourdiff' => 'ਅੰਤਰ',
'templatesused' => 'ਇਸ ਸਫੇ ’ਤੇ {{PLURAL:$1|ਵਰਤਿਆ ਸਾਂਚਾ|ਵਰਤੇ ਸਾਂਚੇ}}:',
'templatesusedpreview' => "{{PLURAL:$1|ਟੈਪਲੇਟ|ਟੈਪਲੇਟ}} ਇਹ ਝਲਕ 'ਚ ਵਰਤੇ ਜਾਂਦੇ ਹਨ:",
'templatesusedsection' => 'ਇਹ ਸ਼ੈਕਸ਼ਨ ਵਿੱਚ ਟੈਪਲੇਟ ਵਰਤਿਆ ਜਾਂਦਾ ਹੈ:',
'template-protected' => '(ਸੁਰੱਖਿਅਤ)',
'template-semiprotected' => '(ਨੀਮ-ਸੁਰੱਖਿਅਤ)',
'hiddencategories' => 'ਇਹ ਸਫ਼ਾ {{PLURAL:$1|1 ਲੁਕਵੀਂ ਸ਼੍ਰੇਣੀ|
$1 ਲੁਕਵੀਆਂ ਸ਼੍ਰੇਣੀਆਂ}} ਦਾ ਮੈਂਬਰ ਹੈ:',
'permissionserrors' => 'ਅਧਿਕਾਰ ਗਲਤੀਆਂ',
'permissionserrorstext' => 'ਤੁਹਾਨੂੰ ਇੰਝ ਕਰਨ ਦੇ ਅਧਿਕਾਰ ਨਹੀਂ ਹਨ। ਹੇਠ ਦਿੱਤੇ {{PLURAL:$1|ਕਾਰਨ|ਕਾਰਨ}} ਨੇ:',
'permissionserrorstext-withaction' => '{{PLURAL:$1|ਇਸ ਕਾਰਨ|ਇਹਨਾਂ ਕਾਰਨਾਂ}} ਕਰਕੇ ਤੁਹਾਨੂੰ $2 ਦੀ ਇਜਾਜ਼ਤ ਨਹੀਂ ਹੈ:',
'recreate-moveddeleted-warn' => "'''ਖ਼ਬਰਦਾਰ:
ਤੁਸੀਂ ਐਸਾ ਸਫ਼ਾ ਬਣਾ ਰਹੇ ਹੋ ਜੋ ਪਹਿਲਾਂ ਮਿਟਾਇਆ ਜਾ ਚੁੱਕ ਹੈ।'''

ਖ਼ਿਆਲ ਕਰੋ ਕਿ ਕੀ ਇਸ ਸਫ਼ੇ ਦਾ ਕਾਇਮ ਰਹਿਣਾ ਠੀਕ ਹੈ।
ਇਸਨੂੰ ਮਿਟਾਉਣ ਜਾਂ ਸਿਰਲੇਖ ਬਦਲੀ ਦਾ ਚਿੱਠਾ ਹੇਠਾਂ ਦਿੱਤਾ ਗਿਆ ਹੈ।",
'moveddeleted-notice' => 'ਇਹ ਸਫ਼ਾ ਮਿਟਾ ਦਿੱਤਾ ਗਿਆ ਹੈ।
ਇਸਦੇ ਮਿਟਾਉਣ ਜਾਂ ਸਿਰਲੇਖ ਬਦਲੀ ਦਾ ਚਿੱਠਾ ਹਵਾਲੇ ਲਈ ਹੇਠ ਦਿੱਤਾ ਗਿਆ ਹੈ।',

# Parser/template warnings
'post-expand-template-inclusion-warning' => "'''ਖ਼ਬਰਦਾਰ:''' ਟੈਂਪਲੇਟਾਂ ਦਾ ਅਕਾਰ ਬਹੁਤ ਵੱਡਾ ਹੈ। ਕੁਝ ਟੈਂਪਲੇਟ ਸ਼ਾਮਲ ਨਹੀਂ ਹੋਣਗੇ।",
'post-expand-template-inclusion-category' => 'ਓਹ ਸਫ਼ੇ ਜਿੱਥੇ ਟੈਂਪਲੇਟਾਂ ਦੇ ਸ਼ਾਮਲ ਕਰਨ ਦਾ ਅਕਾਰ ਹੱਦੋਂ ਵਧ ਗਿਆ ਹੈ',
'post-expand-template-argument-warning' => "'''ਖ਼ਬਰਦਾਰ:'''
ਇਸ ਸਫ਼ੇ ਤੇ ਘੱਟੋ ਘੱਟ ਇਕ ਐਸੀ ਟੈਂਪਲੇਟ ਬਹਿਸ ਹੈ ਜਿਸ ਦਾ ਅਕਾਰ ਬਹੁਤ ਵੱਡਾ ਹੈ। ਐਸੀਆਂ ਬਹਿਸਾਂ ਨੂੰ ਛੱਡ ਦਿੱਤਾ ਗਿਆ ਹੈ।",
'post-expand-template-argument-category' => 'ਐਸੇ ਸਫ਼ੇ ਜਿਨ੍ਹਾਂ ਵਿਚ ਫ਼ਰਮੇ ਦੇ ਸਁਘਟਕ ਛੁੱਟ ਗਏ ਹਨ ।',

# Account creation failure
'cantcreateaccounttitle' => 'ਅਕਾਊਂਟ ਬਣਾਇਆ ਨਹੀਂ ਜਾ ਸਕਦਾ',

# History pages
'viewpagelogs' => 'ਇਸ ਸਫ਼ੇ ਲਈ ਚਿੱਠੇ ਵੇਖੋ',
'currentrev' => 'ਮੌਜੂਦਾ ਰੀਵਿਜ਼ਨ',
'currentrev-asof' => '$1 ਮੁਤਾਬਕ ਸਭ ਤੋਂ ਨਵਾਂ ਰੀਵਿਜ਼ਨ',
'revisionasof' => '$1 ਦਾ ਰੀਵਿਜ਼ਨ',
'revision-info' => '$2 ਦਾ ਬਣਾਇਆ $1 ਦਾ ਰੀਵਿਜ਼ਨ',
'previousrevision' => '←ਪੁਰਾਣਾ ਰੀਵਿਜ਼ਨ',
'nextrevision' => 'ਨਵਾਂ ਰੀਵਿਜ਼ਨ→',
'currentrevisionlink' => 'ਸਭ ਤੋ ਨਵਾਂ ਰੀਵਿਜ਼ਨ',
'cur' => 'ਮੌਜੂਦਾ',
'next' => 'ਅੱਗੇ',
'last' => 'ਆਖ਼ਰੀ',
'page_first' => 'ਪਹਿਲਾਂ',
'page_last' => 'ਆਖਰੀ',
'histlegend' => "ਫ਼ਰਕ ਵੇਖੋ:
ਮੁਕਾਬਲਾ ਕਰਨ ਲਈ ਰੀਵਿਜ਼ਨਾਂ ਦੇ ਰੇਡੀਓ ਬਟਨਾਂ ਵਿਚ ਨਿਸ਼ਾਨ ਲਾਓ ਅਤੇ ਜਾਓ ਜਾਂ ਸਭ ਤੋਂ ਥੱਲੇ ਵਾਲ਼ੇ ਬਟਨ ਤੇ ਕਲਿੱਕ ਕਰੋ। <br />
ਲੈਜਅੰਡ:
'''({{int:cur}})''' = ਨਵੇਂ ਰੀਵਿਜ਼ਨ ਨਾਲ਼ੋਂ ਫ਼ਰਕ, '''({{int:last}})''' = ਆਖ਼ਰੀ ਰੀਵਿਜ਼ਨ ਨਾਲ਼ੋਂ ਫ਼ਰਕ, '''({{int:minoreditletter}})''' = ਛੋਟੀ ਸੋਧ।",
'history-fieldset-title' => 'ਅਤੀਤ ’ਤੇ ਨਜ਼ਰ ਮਾਰੋ',
'history-show-deleted' => 'ਸਿਰਫ਼ ਮਿਟਾਏ ਗਏ',
'histfirst' => 'ਸਭ ਤੋਂ ਪਹਿਲਾ',
'histlast' => 'ਸਭ ਤੋਂ ਨਵਾਂ',
'historysize' => '($1 ਬਾਈਟ)',
'historyempty' => '(ਖਾਲੀ)',

# Revision feed
'history-feed-title' => 'ਰੀਵਿਜ਼ਨ ਅਤੀਤ',
'history-feed-item-nocomment' => '$1 ਤੋਂ $2 ’ਤੇ',

# Revision deletion
'rev-deleted-comment' => '(ਟਿੱਪਣੀ ਹਟਾਈ)',
'rev-deleted-user' => '(ਯੂਜ਼ਰ ਨਾਂ ਹਟਾਇਆ)',
'rev-deleted-event' => '(ਐਂਟਰੀ ਹਟਾਈ)',
'rev-delundel' => 'ਦਿਖਾਓ/ਲੁਕਾਓ',
'revdelete-nooldid-title' => 'ਕੋਈ ਟਾਰਗੇਟ ਰੀਵਿਜ਼ਨ ਨਹੀਂ',
'revdelete-legend' => 'ਪਾਬੰਦੀਆਂ ਸੈੱਟ ਕਰੋ:',
'revdelete-hide-text' => 'ਰੀਵਿਜ਼ਨ ਟੈਕਸਟ ਓਹਲੇ',
'revdelete-hide-image' => 'ਫਾਇਲ ਸਮੱਗਰੀ ਓਹਲੇ',
'revdelete-hide-name' => 'ਐਕਸ਼ਨ ਅਤੇ ਟਾਰਗੇਟ ਓਹਲੇ',
'revdelete-radio-set' => 'ਹਾਂ',
'revdelete-log' => 'ਕਾਰਨ:',
'revdelete-submit' => 'ਚੁਣੇ ਰੀਵਿਜ਼ਨ ਉੱਤੇ ਲਾਗੂ ਕਰੋ',
'revdel-restore' => 'ਦਿੱਖ ਬਦਲੋ',
'revdel-restore-deleted' => 'ਮਿਟਾਏ ਗਏ ਰੀਵੀਜ਼ਨ',
'revdel-restore-visible' => 'ਦਿੱਸਣਯੋਗ ਰੀਵੀਜ਼ਨ',
'pagehist' => 'ਪੇਜ ਦਾ ਅਤੀਤ',
'deletedhist' => 'ਹਟਾਇਆ ਗਿਆ ਅਤੀਤ',

# Merge log
'revertmerge' => 'ਅਨ-ਮਰਜ',

# Diffs
'history-title' => '"$1" ਦੇ ਰੀਵਿਜ਼ਨ ਦਾ ਅਤੀਤ',
'lineno' => 'ਲਾਈਨ $1:',
'compareselectedversions' => 'ਚੁਣੇ ਵਰਜਨਾਂ ਦੀ ਤੁਲਨਾ',
'editundo' => 'ਨਕਾਰੋ',
'diff-multi' => '({{PLURAL:$2|ਮੈਂਬਰ ਦੀ|$2 ਮੈਂਬਰਾਂ ਦੀਆਂ}} {{PLURAL:$1|ਵਿਚਕਾਰਲੀ ਰੀਵਿਜ਼ਨ ਨਹੀਂ ਦਿਖਾਈ ਜਾ ਰਹੀ|ਵਿਚਕਾਰਲੀਆਂ $1 ਰੀਵਿਜ਼ਨਾਂ ਨਹੀਂ ਦਿਖਾਈਆਂ ਜਾ ਰਹੀਆਂ}})',

# Search results
'searchresults' => 'ਖੋਜ ਨਤੀਜੇ',
'searchresults-title' => '"$1" ਲਈ ਖੋਜ ਨਤੀਜੇ',
'searchresulttext' => '{{SITENAME}} ਖੋਜ ਬਾਰੇ ਹੋਰ ਜਾਣਕਾਰੀ ਲਵੋ, ਵੇਖੋ [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle' => 'ਤੁਸੀਂ \'\'\'[[:$1]]\'\'\' ਲਈ ਖੋਜ ਕੀਤੀ ([[Special:Prefixindex/$1|"$1" ਨਾਲ ਸ਼ੁਰੂ ਹੁੰਦੇ ਸਭ ਸਫ਼ੇ]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|"$1" ਨਾਲ ਲਿੰਕ ਹੋਏ ਸਭ ਸਫ਼ੇ]])',
'searchsubtitleinvalid' => "ਤੁਸੀਂ'''$1''' ਲਈ ਖੋਜ ਕੀਤੀ।",
'titlematches' => 'ਆਰਟੀਕਲ ਟੈਕਸਟ ਮਿਲਦਾ',
'notitlematches' => 'ਕੋਈ ਪੇਜ ਟਾਇਟਲ ਨਹੀਂ ਮਿਲਦਾ',
'textmatches' => 'ਪੇਜ ਟੈਕਸਟ ਮਿਲਦਾ',
'notextmatches' => 'ਕੋਈ ਪੇਜ ਟੈਕਸਟ ਨਹੀਂ ਮਿਲਦਾ',
'prevn' => 'ਪਿਛਲੇ {{PLURAL:$1|$1}}',
'nextn' => 'ਅਗਲੇ {{PLURAL:$1|$1}}',
'prevn-title' => 'ਪਿਛਲੇ $1 {{PLURAL:$1|ਨਤੀਜਾ|ਨਤੀਜੇ}}',
'nextn-title' => 'ਅਗਲੇ $1 {{PLURAL:$1|ਨਤੀਜਾ|ਨਤੀਜੇ}}',
'shown-title' => 'ਪ੍ਰਤੀ ਸਫ਼ਾ $1 {{PLURAL:$1|ਨਤੀਜਾ|ਨਤੀਜੇ}} ਵਖਾਓ',
'viewprevnext' => 'ਵੇਖੋ ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-exists' => "'''ਇਸ ਵਿਕੀ ’ਤੇ \"[[:\$1]]\" ਨਾਮ ਦਾ ਸਫ਼ਾ ਹੈ।'''",
'searchmenu-new' => "'''ਇਸ ਵਿਕੀ ’ਤੇ \"[[:\$1]]\" ਸਫ਼ਾ ਬਣਾਓ!'''",
'searchhelp-url' => 'Help:ਸਮੱਗਰੀ',
'searchprofile-articles' => 'ਸਮੱਗਰੀ ਸਫ਼ੇ',
'searchprofile-project' => 'ਮਦਦ ਅਤੇ ਪ੍ਰੋਜੈਕਟ ਸਫ਼ੇ',
'searchprofile-images' => 'ਮਲਟੀਮੀਡਿਆ',
'searchprofile-everything' => 'ਸਭ ਕੁਝ',
'searchprofile-advanced' => 'ਆਧੁਨਿਕ',
'searchprofile-articles-tooltip' => '$1 ਵਿਚ ਖੋਜੋ',
'searchprofile-project-tooltip' => '$1 ਵਿਚ ਖੋਜੋ',
'searchprofile-images-tooltip' => 'ਫਾਇਲਾਂ ਖੋਜੋ',
'searchprofile-everything-tooltip' => 'ਸਭ ਚੀਜ਼ਾਂ ਖੋਜੋ (ਗੱਲਬਾਤ ਸਫ਼ਿਆਂ ਸਮੇਤ)',
'searchprofile-advanced-tooltip' => 'ਆਪਣੇ ਬਣਾਏ ਨਾਮ-ਥਾਂਵਾਂ ਵਿਚ ਖੋਜੋ',
'search-result-size' => '$1 ({{PLURAL:$2|੧ ਸ਼ਬਦ|$2 ਸ਼ਬਦ}})',
'search-redirect' => '($1 ਰੀ-ਡਿਰੈਕਟ)',
'search-section' => '(ਭਾਗ $1)',
'search-suggest' => 'ਕੀ ਤੁਹਾਡਾ ਮਤਲਬ ਸੀ: $1',
'search-interwiki-default' => '$1 ਨਤੀਜੇ:',
'search-interwiki-more' => '(ਹੋਰ)',
'search-mwsuggest-enabled' => 'ਸੁਝਾਆਵਾਂ ਨਾਲ',
'search-mwsuggest-disabled' => 'ਕੋਈ ਸੁਝਾਅ ਨਹੀਂ',
'searchrelated' => 'ਸਬੰਧਿਤ',
'searchall' => 'ਸਭ',
'showingresultsheader' => "'''$4''' ਵਾਸਤੇ {{PLURAL:$5|'''$3''' ਵਿਚੋਂ '''$1''' ਨਤੀਜੇ|'''$3''' ਵਿਚੋਂ '''$1 - $2''' ਨਤੀਜੇ}}",
'search-nonefound' => 'ਤੁਹਾਡੀ ਖੋਜ ਨਾਲ਼ ਮੇਲ ਖਾਂਦੇ ਕੋਈ ਨਤੀਜੇ ਨਹੀਂ ਮਿਲੇ।',
'powersearch' => 'ਖੋਜ',
'powersearch-legend' => 'ਤਕਨੀਕੀ ਖੋਜ',
'powersearch-ns' => 'ਨੇਮ-ਸਪੇਸ ਵਿੱਚ ਖੋਜ:',
'powersearch-redir' => 'ਰੀ-ਡਿਰੈਕਟ ਲਿਸਟ',
'powersearch-field' => 'ਇਸ ਲਈ ਖੋਜ',

# Quickbar
'qbsettings' => 'ਤੁਰੰਤ ਬਾਰ',
'qbsettings-none' => 'ਕੋਈ ਨਹੀਂ',

# Preferences page
'preferences' => 'ਮੇਰੀ ਪਸੰਦ',
'mypreferences' => 'ਮੇਰੀਆਂ ਪਸੰਦਾਂ',
'prefs-edits' => 'ਸੋਧਾਂ ਦੀ ਗਿਣਤੀ:',
'prefsnologin' => 'ਲਾਗਇਨ ਨਹੀਂ',
'prefsnologintext' => 'ਯੂਜ਼ਰ ਪਸੰਦ ਸੈੱਟ ਕਰਨ ਲਈ ਤੁਹਾਨੂੰ [[Special:UserLogin|logged in]] ਕਰਨਾ ਪਵੇਗਾ।',
'changepassword' => 'ਪਾਸਵਰਡ ਬਦਲੋ',
'prefs-skin' => 'ਸਕਿਨ',
'skin-preview' => 'ਝਲਕ',
'datedefault' => 'ਕੋਈ ਪਸੰਦ ਨਹੀਂ',
'prefs-datetime' => 'ਮਿਤੀ ਅਤੇ ਸਮਾਂ',
'prefs-personal' => 'ਯੂਜ਼ਰ ਪਰੋਫਾਇਲ',
'prefs-rc' => 'ਤਾਜ਼ਾ ਬਦਲਾਅ',
'prefs-watchlist' => 'ਵਾਚ-ਲਿਸਟ',
'prefs-misc' => 'ਫੁਟਕਲ',
'prefs-resetpass' => 'ਪਾਸਵਰਡ ਬਦਲੋ',
'prefs-email' => 'ਈਮੇਲ ਚੋਣਾਂ',
'prefs-rendering' => 'ਦਿੱਖ',
'saveprefs' => 'ਸੰਭਾਲੋ',
'resetprefs' => 'ਰੀ-ਸੈੱਟ',
'prefs-editing' => 'ਸੰਪਾਦਨ',
'rows' => 'ਕਤਾਰਾਂ:',
'columns' => 'ਕਾਲਮ:',
'searchresultshead' => 'ਖੋਜ',
'resultsperpage' => 'ਪ੍ਰਤੀ ਪੇਜ ਹਿੱਟ:',
'savedprefs' => 'ਤੁਹਾਡੀ ਪਸੰਦ ਸੰਭਾਲੀ ਗਈ ਹੈ।',
'timezonelegend' => 'ਸਮਾਂ ਖੇਤਰ:',
'localtime' => 'ਲੋਕਲ ਸਮਾਂ:',
'timezoneuseserverdefault' => 'ਸਰਵਰ ਡਿਫਾਲਟ ਵਰਤੋਂ',
'servertime' => 'ਸਰਵਰ ਟਾਈਮ',
'guesstimezone' => 'ਬਰਾਊਜ਼ਰ ਤੋਂ ਭਰੋ',
'allowemail' => 'ਹੋਰ ਯੂਜ਼ਰਾਂ ਤੋਂ ਈਮੇਲ ਯੋਗ ਕਰੋ',
'default' => 'ਡਿਫਾਲਟ',
'prefs-files' => 'ਫਾਇਲਾਂ',
'youremail' => 'ਈ-ਮੇਲ:',
'username' => 'ਯੂਜ਼ਰ ਨਾਂ:',
'uid' => 'ਯੂਜ਼ਰ ID:',
'yourrealname' => 'ਅਸਲੀ ਨਾਮ:',
'yourlanguage' => 'ਭਾਸ਼ਾ:',
'yournick' => 'ਛੋਟਾ ਨਾਂ:',
'badsiglength' => 'ਛੋਟਾ ਨਾਂ (Nickname) ਬਹੁਤ ਲੰਮਾ ਹੋ ਗਿਆ ਹੈ, ਇਹ $1 ਅੱਖਰਾਂ ਤੋਂ ਘੱਟ ਚਾਹੀਦਾ ਹੈ।',
'email' => 'ਈਮੇਲ',
'prefs-help-realname' => 'ਅਸਲੀ ਨਾਂ ਚੋਣਵਾਂ ਹੈ, ਅਤੇ ਜੇ ਤੁਸੀਂ ਇਹ ਦਿੱਤਾ ਹੈ ਤਾਂ ਤੁਹਾਡੇ ਕੰਮ ਵਾਸਤੇ ਗੁਣ ਦੇ ਤੌਰ ਉੱਤੇ ਵਰਤਿਆ ਜਾਵੇਗਾ।',
'prefs-help-email' => 'ਤੁਹਾਡੀ ਮਰਜ਼ੀ ਹੈ ਈਮੇਲ ਪਤਾ ਦਿਓ ਜਾਂ ਨਾ ਦਿਓ ਪਰ ਪਾਸਵਰਡ ਭੁੱਲ ਜਾਣ ਤੇ ਨਵਾਂ ਪਾਸਵਰਡ ਹਾਸਲ ਕਰਨ ਲਈ ਇਹ ਜ਼ਰੂਰੀ ਹੈ।',
'prefs-help-email-others' => 'ਤੁਸੀਂ ਇਹ ਵੀ ਚੁਣ ਸਕਦੇ ਹੋ ਕਿ ਤੁਹਾਡੇ ਮੈਂਬਰ ਜਾਂ ਗੱਲ-ਬਾਤ ਸਫ਼ੇ ਤੋਂ ਹੋਰ ਮੈਂਬਰ ਤੁਹਾਨੂੰ ਈ-ਮੇਲ ਭੇਜ ਸਕਣ?
ਜਦੋਂ ਹੋਰ ਮੈਂਬਰ ਤੁਹਾਨੂੰ ਈ-ਮੇਲ ਭੇਜਦੇ ਹਨ ਤਾਂ ਤੁਹਾਡਾ ਈ-ਮੇਲ ਪਤਾ ਜ਼ਾਹਰ ਨਹੀਂ ਕੀਤਾ ਜਾਂਦਾ।',
'prefs-advancedediting' => 'ਤਕਨੀਕੀ ਚੋਣਾਂ',
'prefs-advancedrc' => 'ਤਕਨੀਕੀ ਚੋਣਾਂ',
'prefs-advancedrendering' => 'ਤਕਨੀਕੀ ਚੋਣਾਂ',
'prefs-advancedsearchoptions' => 'ਤਕਨੀਕੀ ਚੋਣਾਂ',
'prefs-advancedwatchlist' => 'ਤਕਨੀਕੀ ਚੋਣਾਂ',

# User rights
'userrights-lookup-user' => 'ਯੂਜ਼ਰ ਗਰੁੱਪ ਦੇਖਭਾਲ',
'userrights-user-editname' => 'ਇੱਕ ਯੂਜ਼ਰ ਨਾਂ ਦਿਓ:',
'editusergroup' => 'ਯੂਜ਼ਰ ਗਰੁੱਪ ਸੋਧ',
'editinguser' => '<b>$1</b> ਯੂਜ਼ਰ ਸੋਧਿਆ ਜਾ ਰਿਹਾ ਹੈ ([[User talk:$1|{{int:talkpagelinktext}}]] | [[Special:Contributions/$1|{{int:contribslink}}]])',
'userrights-editusergroup' => 'ਯੂਜ਼ਰ ਗਰੁੱਪ ਸੋਧ',
'saveusergroups' => 'ਯੂਜ਼ਰ ਗਰੁੱਪ ਸੰਭਾਲੋ',
'userrights-groupsmember' => 'ਇਸ ਦਾ ਮੈਂਬਰ:',
'userrights-reason' => 'ਕਾਰਨ:',

# Groups
'group' => 'ਗਰੁੱਪ:',
'group-user' => 'ਮੈਂਬਰ',
'group-all' => '(ਸਭ)',

'group-user-member' => 'ਮੈਂਬਰ',

# Rights
'right-edit' => 'ਸਫ਼ੇ ਸੋਧ',
'right-delete' => 'ਸਫ਼ੇ ਹਟਾਓ',

# User rights log
'rightsnone' => '(ਕੋਈ ਨਹੀਂ)',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'ਇਹ ਸਫ਼ਾ ਸੋਧੋ',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|ਤਬਦੀਲੀ|
ਤਬਦੀਲੀਆਂ}}',
'recentchanges' => 'ਤਾਜ਼ਾ ਤਬਦੀਲੀਆਂ',
'recentchanges-legend' => 'ਤਾਜ਼ਾ ਬਦਲਾਅ ਚੋਣਾਂ',
'recentchanges-summary' => 'ਇਸ ਵਿਕੀ ਪਰ ਹਾਲ ਵਿਚ ਹੋਏ ਬਦਲਾਅਦੇਖੇ ਜਾ ਸਕਦੇ ਹਨ।',
'recentchanges-feed-description' => 'ਇਸ ਵਿਕੀ ’ਤੇ ਹਾਲ ਹੀ ਵਿਚ ਹੋਈਆਂ ਤਬਦੀਲੀਆਂ ਇਸ ਫ਼ੀਡ ’ਚ ਵੇਖੀਆਂ ਜਾ ਸਕਦੀਆਂ ਹਨ।',
'recentchanges-label-newpage' => 'ਇਹ ਸੋਧ ਨੇ ਨਵਾਂ ਸਫ਼ਾ ਬਣਾਇਆ ਹੈ',
'recentchanges-label-minor' => 'ਇਹ ਛੋਟੀ ਸੋਧ ਹੈ',
'recentchanges-label-bot' => 'ਇਹ ਸੋਧ ਬੋਟ ਵਲੋਂ ਕੀਤੀ ਗਈ ਹੈ',
'recentchanges-label-unpatrolled' => 'ਇਸ ਸੰਪਾਦਨ ਦੀ ਅਜੇ ਨਿਗਰਾਨੀ ਨਹੀਂ ਹੋਈ',
'rcnote' => "$4, $5 ਤੱਕ ਆਖ਼ਰੀ {{PLURAL:$2|ਦਿਨ|'''$2''' ਦਿਨਾਂ}} ਵਿਚ {{PLURAL:$1|'''1''' ਤਬਦੀਲੀ ਹੋਈ ਹੈ।|'''$1''' ਤਬਦੀਲੀਆਂ ਹੋਈਆਂ ਹਨ।}}",
'rcnotefrom' => "'''$2''' ਤੱਕ ('''$1''' ਤੱਕ ਦਿੱਸਦੀਆਂ) ਤਬਦੀਲੀਆਂ ਹੇਠ ਦਿੱਤੀਆਂ ਹਨ।",
'rclistfrom' => '$1 ਤੋਂ ਸ਼ੁਰੂ ਕਰਕੇ ਨਵੀਆਂ ਤਬਦੀਲੀਆਂ ਦਿਖਾਓ',
'rcshowhideminor' => '$1 ਛੋਟੀਆਂ ਸੋਧਾਂ',
'rcshowhidebots' => '$1 ਬੋਟ',
'rcshowhideliu' => '$1 ਲਾਗਇਨ ਹੋਏ ਮੈਂਬਰ',
'rcshowhideanons' => '$1 ਗੁਮਨਾਮ ਮੈਂਬਰ',
'rcshowhidepatr' => 'ਵੇਖੀਆਂ ਜਾ ਚੁੱਕੀਆਂ ਸੋਧਾਂ $1',
'rcshowhidemine' => 'ਮੇਰੀਆਂ ਸੋਧਾਂ $1',
'rclinks' => 'ਪਿਛਲੇ $2 ਦਿਨਾਂ ਵਿਚ ਹੋਈਆਂ $1 ਤਬਦੀਲੀਆਂ ਦਿਖਾਓ <br /> $3',
'diff' => 'ਫ਼ਰਕ',
'hist' => 'ਅਤੀਤ',
'hide' => 'ਲੁਕਾਓ',
'show' => 'ਵਖਾਓ',
'minoreditletter' => 'ਛ',
'newpageletter' => 'ਨ',
'boteditletter' => 'ਬ',
'rc_categories_any' => 'ਕੋਈ ਵੀ',
'rc-enhanced-expand' => 'ਵੇਰਵਾ ਵਖਾਓ (ਜਾਵਾਸਕ੍ਰਿਪਟ ਲੋੜੀਂਦੀ ਹੈ)',
'rc-enhanced-hide' => 'ਵੇਰਵਾ ਲੁਕਾਓ',

# Recent changes linked
'recentchangeslinked' => 'ਸਬੰਧਿਤ ਤਬਦੀਲੀਆਂ',
'recentchangeslinked-feed' => 'ਸਬੰਧਿਤ ਬਦਲਾਅ',
'recentchangeslinked-toolbox' => 'ਸਬੰਧਿਤ ਤਬਦੀਲੀਆਂ',
'recentchangeslinked-title' => '"$1" ਨਾਲ਼ ਸਬੰਧਿਤ ਤਬਦੀਲੀਆਂ',
'recentchangeslinked-noresult' => 'ਜੁੜੇ ਸਫਿਆਂ ’ਤੇ, ਦਿੱਤੇ ਸਮੇਂ ’ਚ ਕੋਈ ਤਬਦੀਲੀ ਨਹੀਂ ਹੋਈ।',
'recentchangeslinked-summary' => 'ਇਹ ਲਿਸਟ ਇਕ ਖ਼ਾਸ ਸਫ਼ੇ ਨਾਲ ਸਬੰਧਿਤ ਸਫ਼ਿਆਂ ਜਾਂ ਕਿਸੇ ਖ਼ਾਸ ਸ਼੍ਰੇਣੀ ਦੇ ਮੈਂਬਰਾਂ ਦੇ ਹਾਲ ਵਿਚ ਹੋਏ ਬਦਲਾਵਾਂ ਨੂੰ ਦਰਸਾਂਉਦੀ ਹੈ। [[Special:Watchlist|ਤੁਹਾਡੀ ਨਿਗਰਾਨੀ-ਲਿਸਟ]] ਵਿਚ ਮੌਜੂਦ ਸਫ਼ੇ ਮੋਟੇ ਅੱਖਰਾਂ ਵਿਚ ਦਿਖਾਈ ਦੇਣਗੇ।',
'recentchangeslinked-page' => 'ਸਫ਼ੇ ਦਾ ਨਾਮ:',
'recentchangeslinked-to' => 'ਇਸਦੇ ਬਦਲੇ ਇਸ ਸਫ਼ੇ ਨਾਲ਼ ਜੁੜੇ ਸਫ਼ਿਆਂ ਵਿਚ ਹੋਏ ਬਦਲਾਅ ਦਿਖਾਓ',

# Upload
'upload' => 'ਫਾਇਲ ਅੱਪਲੋਡ ਕਰੋ',
'uploadbtn' => 'ਫਾਇਲ ਅੱਪਲੋਡ ਕਰੋ',
'reuploaddesc' => 'ਅੱਪਲੋਡ ਫਾਰਮ ਉੱਤੇ ਜਾਓ।',
'uploadnologin' => 'ਲਾਗਇਨ ਨਹੀਂ ਹੋ',
'uploadnologintext' => 'ਤੁਹਾਨੂੰ[[Special:UserLogin|logged in] ਕਰਨਾ ਪਵੇਗਾ]
to upload files.',
'uploaderror' => 'ਅੱਪਲੋਡ ਗਲਤੀ',
'uploadlog' => 'ਅੱਪਲੋਡ ਲਾਗ',
'uploadlogpage' => 'ਅੱਪਲੋਡ ਦਾ ਚਿੱਠਾ',
'filename' => 'ਫਾਇਲ ਨਾਂ',
'filedesc' => 'ਸਾਰ',
'fileuploadsummary' => 'ਸੰਖੇਪ:',
'filestatus' => 'ਕਾਪੀਰਾਈਟ ਹਾਲਤ:',
'filesource' => 'ਸੋਰਸ:',
'uploadedfiles' => 'ਅੱਪਲੋਡ ਕੀਤੀਆਂ ਫਾਇਲਾਂ',
'ignorewarning' => 'ਚੇਤਾਵਨੀ ਅਣਡਿੱਠੀ ਕਰਕੇ ਕਿਵੇਂ ਵੀ ਫਾਇਲ ਸੰਭਾਲੋ।',
'minlength1' => 'ਫਾਇਲ ਨਾਂ ਵਿੱਚ ਘੱਟੋ-ਘੱਟ ਇੱਕ ਅੱਖਰ ਹੋਣਾ ਚਾਹੀਦਾ ਹੈ।',
'badfilename' => 'ਫਾਇਲ ਨਾਂ "$1" ਬਦਲਿਆ ਗਿਆ ਹੈ।',
'filetype-missing' => 'ਫਾਇਲ ਦੀ ਕੋਈ ਐਕਸ਼ਟੇਸ਼ਨ ਨਹੀਂ ਹੈ (ਜਿਵੇਂ ".jpg").',
'fileexists' => "ਇਹ ਫਾਇਲ ਨਾਂ ਪਹਿਲਾਂ ਹੀ ਮੌਜੂਦ ਹੈ। ਜੇ ਤੁਸੀਂ ਇਹ ਬਦਲਣ ਬਾਰੇ ਜਾਣਦੇ ਨਹੀਂ ਹੋ ਤਾਂ  '''<tt>[[:$1]]</tt>''' ਵੇਖੋ ਜੀ। [[$1|thumb]]",
'fileexists-extension' => "ਇਸ ਨਾਂ ਨਾਲ ਰਲਦੀ ਫਾਇਲ ਮੌਜੂਦ ਹੈ: [[$2|thumb]]
* ਅੱਪਲੋਡ ਕੀਤੀ ਫਾਇਲ ਦਾ ਨਾਂ: '''<tt>[[:$1]]</tt>'''
* ਮੌਜੂਦ ਫਾਇਲ ਦਾ ਨਾਂ: '''<tt>[[:$2]]</tt>'''
ਇੱਕ ਵੱਖਰਾ ਨਾਂ ਚੁਣੋ ਜੀ",
'uploadwarning' => 'ਅੱਪਲੋਡ ਚੇਤਾਵਨੀ',
'savefile' => 'ਫਾਇਲ ਸੰਭਾਲੋ',
'uploadedimage' => '"[[$1]]" ਅੱਪਲੋਡ ਕੀਤੀ',
'uploaddisabled' => 'ਅੱਪਲੋਡ ਆਯੋਗ ਹੈ',
'uploadvirus' => 'ਇਹ ਫਾਇਲ ਵਿੱਚ ਵਾਇਰਸ ਹੈ! ਵੇਰਵੇ ਲਈ ਵੇਖੋ: $1',
'sourcefilename' => 'ਸੋਰਸ ਫਾਇਲ ਨਾਂ:',
'watchthisupload' => 'ਇਸ ਫਾਇਲ ਨੂੰ ਵਾਚ ਕਰੋ',
'upload-success-subj' => 'ਠੀਕ ਤਰ੍ਹਾਂ ਅੱਪਲੋਡ',
'upload-warning-subj' => 'ਅੱਪਲੋਡ ਚੇਤਾਵਨੀ',

'upload-file-error' => 'ਅੰਦਰੂਨੀ ਗਲਤੀ',
'upload-misc-error' => 'ਅਣਜਾਣ ਅੱਪਲੋਡ ਗਲਤੀ',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error28' => 'ਅੱਪਲੋਡ ਟਾਈਮ-ਆਉਟ',

'license' => 'ਲਾਈਸੈਂਸਿੰਗ:',
'license-header' => 'ਲਾਈਸੰਸ',
'nolicense' => 'ਕੁਝ ਵੀ ਚੁਣਿਆ',
'license-nopreview' => '(ਝਲਕ ਉਪਲੱਬਧ ਨਹੀਂ)',
'upload_source_file' => ' (ਤੁਹਾਡੇ ਕੰਪਿਊਟਰ ਉੱਤੇ ਇੱਕ ਫਾਇਲ)',

# Special:ListFiles
'imgfile' => 'ਫਾਇਲ',
'listfiles' => 'ਫਾਇਲ ਲਿਸਟ',
'listfiles_date' => 'ਮਿਤੀ',
'listfiles_name' => 'ਨਾਂ',
'listfiles_user' => 'ਯੂਜ਼ਰ',
'listfiles_size' => 'ਆਕਾਰ',
'listfiles_description' => 'ਵੇਰਵਾ',
'listfiles_count' => 'ਵਰਜਨ',

# File description page
'file-anchor-link' => 'ਫ਼ਾਈਲ',
'filehist' => 'ਫ਼ਾਈਲ ਦਾ ਅਤੀਤ',
'filehist-help' => 'ਤਾਰੀਖ਼/ਸਮੇਂ ’ਤੇ ਕਲਿੱਕ ਕਰੋ ਤਾਂ ਉਸ ਸਮੇਂ ਦੀ ਫਾਈਲ ਪੇਸ਼ ਹੋ ਜਾਵੇਗੀ।',
'filehist-deleteall' => 'ਸਭ ਹਟਾਓ',
'filehist-deleteone' => 'ਇਹ ਹਟਾਓ',
'filehist-revert' => 'ਉਲਟਾਓ',
'filehist-current' => 'ਮੌਜੂਦਾ',
'filehist-datetime' => 'ਤਾਰੀਖ਼/ਸਮਾਂ',
'filehist-thumb' => 'ਨਮੂਨਾ',
'filehist-thumbtext' => '$1 ਦੇ ਸਮੇਂ ਦੇ ਸੰਸਕਰਨ ਦਾ ਅੰਗੂਠਾਕਾਰ ਪ੍ਰਤੀਰੂਪ',
'filehist-user' => 'ਮੈਂਬਰ',
'filehist-dimensions' => 'ਨਾਪ',
'filehist-filesize' => 'ਫਾਇਲ ਆਕਾਰ',
'filehist-comment' => 'ਟਿੱਪਣੀ',
'imagelinks' => 'ਫ਼ਾਈਲ ਦੀ ਵਰਤੋਂ',
'linkstoimage' => 'ਇਹ {{PLURAL:$1|ਸਫ਼ੇ ਦੇ ਲਿੰਕ|$1 ਸਫ਼ੇ}} ਇਸ ਫ਼ਾਈਲ ਨਾਲ਼ ਜੋੜਦੇ ਹਨੇ:',
'nolinkstoimage' => 'ਕੋਈ ਵੀ ਸਫ਼ਾ ਇਸ ਫ਼ਾਈਲ ਨਾਲ਼ ਨਹੀਂ ਜੋੜਦਾ।',
'sharedupload-desc-here' => 'ਇਹ ਫ਼ਾਈਲ $1 ਦੀ ਹੈ ਅਤੇ ਹੋਰ ਪ੍ਰਾਜੈਕਟਾਂ ਵਿਚ ਵੀ ਵਰਤੀ ਜਾ ਸਕਦੀ ਹੈ । ਇਸ [$2 ਫ਼ਾਈਲ ਦੇ ਵੇਰਵਾ ਸਫ਼ੇ] ਵਿਚ ਮੌਜੂਦ ਵੇਰਵਾ ਹੇਠ ਦਿਸ ਰਿਹਾ ਹੈ।',
'uploadnewversion-linktext' => 'ਇਸ ਫਾਇਲ ਦਾ ਇੱਕ ਨਵਾਂ ਵਰਜਨ ਅੱਪਲੋਡ ਕਰੋ',

# File reversion
'filerevert' => '$1 ਰੀਵਰਟ',
'filerevert-legend' => 'ਫਾਇਲ ਰੀਵਰਟ',
'filerevert-comment' => 'ਟਿੱਪਣੀ:',
'filerevert-submit' => 'ਰੀਵਰਟ',

# File deletion
'filedelete' => '$1 ਹਟਾਓ',
'filedelete-legend' => 'ਫਾਇਲ ਹਟਾਓ',
'filedelete-comment' => 'ਕਾਰਨ:',
'filedelete-submit' => 'ਹਟਾਓ',
'filedelete-success' => "'''$1''' ਨੂੰ ਹਟਾਇਆ ਗਿਆ।",

# MIME search
'mimesearch' => 'MIME ਖੋਜ',
'mimetype' => 'MIME ਕਿਸਮ:',
'download' => 'ਡਾਊਨਲੋਡ',

# Random page
'randompage' => 'ਰਲ਼ਵਾਂ ਸਫ਼ਾ',

# Statistics
'statistics' => 'ਅੰਕੜੇ',
'statistics-header-pages' => 'ਸਫ਼ਾ ਅੰਕੜੇ',
'statistics-header-edits' => 'ਸੋਧ ਅੰਕੜੇ',
'statistics-header-views' => 'ਵੇਖਣ ਅੰਕੜੇ',
'statistics-header-users' => 'ਯੂਜ਼ਰ ਅੰਕੜੇ',
'statistics-mostpopular' => 'ਸਭ ਤੋਂ ਵੱਧ ਵੇਖੇ ਪੇਜ',

'brokenredirects-edit' => 'ਸੋਧ',
'brokenredirects-delete' => 'ਹਟਾਓ',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|ਬਾਈਟ|ਬਾਈਟ}}',
'nmembers' => '$1 {{PLURAL:$1|ਮੈਂਬਰ|ਮੈਂਬਰ}}',
'unusedcategories' => 'ਅਣਵਰਤੀਆਂ ਕੈਟਾਗਰੀਆਂ',
'unusedimages' => 'ਅਣਵਰਤੀਆਂ ਫਾਇਲਾਂ',
'popularpages' => 'ਪਾਪੂਲਰ ਪੇਜ',
'prefixindex' => 'ਇਸ ਅਗੇਤਰ ਵਾਲ਼ੇ ਸਾਰੇ ਸਫ਼ੇ',
'shortpages' => 'ਛੋਟੇ ਪੇਜ',
'listusers' => 'ਯੂਜ਼ਰ ਲਿਸਟ',
'usercreated' => '$1 ਨੂੰ $2 ’ਤੇ {{GENDER:$3|ਰਚਿਆ}}',
'newpages' => 'ਨਵੇਂ ਸਫ਼ੇ',
'newpages-username' => 'ਯੂਜ਼ਰ ਨਾਂ:',
'ancientpages' => 'ਸਭ ਤੋਂ ਪੁਰਾਣੇ ਪੇਜ',
'move' => 'ਭੇਜੋ',
'movethispage' => 'ਇਹ ਪੇਜ ਭੇਜੋ',
'notargettitle' => 'ਟਾਰਗੇਟ ਨਹੀਂ',
'pager-newer-n' => '{{PLURAL:$1|੧ ਨਵਾਂ|$1 ਨਵੇਂ}}',
'pager-older-n' => '{{PLURAL:$1|੧ ਪੁਰਾਣਾ|$1 ਪੁਰਾਣੇ}}',

# Book sources
'booksources' => 'ਕਿਤਾਬ ਸਰੋਤ',
'booksources-search-legend' => 'ਕਿਤਾਬ ਸਰੋਤ ਖੋਜੋ',
'booksources-go' => 'ਜਾਓ',

# Special:Log
'specialloguserlabel' => 'ਯੂਜ਼ਰ:',
'speciallogtitlelabel' => 'ਟਾਇਟਲ:',
'log' => 'ਚਿੱਠੇ',
'all-logs-page' => 'ਸਭ ਲਾਗ',

# Special:AllPages
'allpages' => 'ਸਭ ਸਫ਼ੇ',
'alphaindexline' => '$1 ਤੋਂ $2',
'nextpage' => 'ਅੱਗੇ ਪੇਜ ($1)',
'prevpage' => 'ਪਿੱਛੇ ਪੇਜ ($1)',
'allarticles' => 'ਸਭ ਸਫ਼ੇ',
'allinnamespace' => 'ਸਭ ਪੇਜ ($1 ਨੇਮਸਪੇਸ)',
'allnotinnamespace' => 'ਸਭ ਪੇਜ ($1 ਨੇਮਸਪੇਸ ਵਿੱਚ ਨਹੀਂ)',
'allpagesprev' => 'ਪਿੱਛੇ',
'allpagesnext' => 'ਅੱਗੇ',
'allpagessubmit' => 'ਜਾਓ',

# Special:Categories
'categories' => 'ਕੈਟਾਗਰੀਆਂ',

# Special:LinkSearch
'linksearch' => 'ਬਾਹਰੀ ਲਿੰਕ',
'linksearch-line' => '$2 ਵਿਚ $1 ਬਾਹਰੀ ਸਿਰਨਾਵਾਂ ਹੈ',

# Special:ListUsers
'listusers-submit' => 'ਵੇਖੋ',
'listusers-noresult' => 'ਕੋਈ ਯੂਜ਼ਰ ਨਹੀਂ ਲੱਭਿਆ।',

# Special:Log/newusers
'newuserlogpage' => 'ਬਣਾਏ ਖਾਤਿਆਂ ਦਾ ਚਿੱਠਾ',

# Special:ListGroupRights
'listgrouprights-group' => 'ਗਰੁੱਪ',
'listgrouprights-members' => '(ਮੈਂਬਰਾਂ ਦੀ ਲਿਸਟ)',

# E-mail user
'mailnologin' => 'ਕੋਈ ਭੇਜਣ ਐਡਰੈੱਸ ਨਹੀਂ',
'emailuser' => 'ਇਸ ਮੈਂਬਰ ਨੂੰ ਈ-ਮੇਲ ਭੇਜੋ',
'emailpage' => 'ਯੂਜ਼ਰ ਨੂੰ ਈਮੇਲ ਕਰੋ',
'defemailsubject' => '{{SITENAME}} ਈਮੇਲ',
'noemailtitle' => 'ਕੋਈ ਈਮੇਲ ਐਡਰੈੱਸ ਨਹੀਂ',
'emailfrom' => 'ਵਲੋਂ:',
'emailto' => 'ਵੱਲ:',
'emailsubject' => 'ਵਿਸ਼ਾ:',
'emailmessage' => 'ਸੁਨੇਹਾ:',
'emailsend' => 'ਭੇਜੋ',
'emailccme' => 'ਸੁਨੇਹੇ ਦੀ ਇੱਕ ਕਾਪੀ ਮੈਨੂੰ ਵੀ ਭੇਜੋ।',
'emailsent' => 'ਈਮੇਲ ਭੇਜੀ ਗਈ',
'emailsenttext' => 'ਤੁਹਾਡੀ ਈਮੇਲ ਭੇਜੀ ਗਈ ਹੈ।',

# Watchlist
'watchlist' => 'ਮੇਰੀ ਨਿਗਰਾਨੀ-ਲਿਸਟ',
'mywatchlist' => 'ਮੇਰੀ ਨਿਗਰਾਨੀ-ਲਿਸਟ',
'watchlistfor2' => '$1 $2 ਲਈ',
'watchnologin' => 'ਲਾਗਇਨ ਨਹੀਂ',
'watch' => 'ਨਜ਼ਰ ਰੱਖੋ',
'watchthispage' => 'ਇਹ ਪੇਜ ਵਾਚ ਕਰੋ',
'unwatch' => 'ਨਜ਼ਰ ਹਟਾਓ',
'watchlist-details' => 'ਗੱਲ-ਬਾਤ ਸਫ਼ੇ ਨਾ ਗਿਣਦੇ ਹੋਏ, ਤੁਹਾਡੀ ਨਿਗਰਾਨੀ-ਲਿਸਟ ਵਿਚ {{PLURAL:$1|$1 ਸਫ਼ਾ ਹੈ|$1 ਸਫ਼ੇ ਹਨ}}।',
'wlshowlast' => 'ਆਖ਼ਰੀ $1 ਦਿਨ $2 ਘੰਟੇ $3 ਵਖਾਓ',
'watchlist-options' => 'ਨਿਗਰਾਨੀ-ਲਿਸਟ ਦੇ ਇਖ਼ਤਿਆਰ',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'ਨਿਗ੍ਹਾ (ਵਾਚ) ਰੱਖੀ ਜਾ ਰਹੀ ਹੈ...',
'unwatching' => 'ਨਿਗ੍ਹਾ ਰੱਖਣੀ (ਵਾਚ) ਬੰਦ ਕੀਤੀ ਜਾ ਰਹੀ ਹੈ..',

'enotif_newpagetext' => 'ਇਹ ਨਵਾਂ ਪੇਜ ਹੈ।',
'enotif_impersonal_salutation' => '{{SITENAME}} ਯੂਜ਼ਰ',
'changed' => 'ਬਦਲਿਆ',
'created' => 'ਬਣਾਇਆ',
'enotif_anon_editor' => 'ਅਗਿਆਤ ਯੂਜ਼ਰ $1',

# Delete
'deletepage' => 'ਪੇਜ ਹਟਾਓ',
'confirm' => 'ਪੁਸ਼ਟੀ',
'excontent' => "ਸਮੱਗਰੀ ਸੀ: '$1'",
'exblank' => 'ਪੇਜ ਖਾਲੀ ਹੈ',
'delete-confirm' => '"$1" ਹਟਾਓ',
'delete-legend' => 'ਹਟਾਓ',
'actioncomplete' => 'ਕਾਰਵਾਈ ਪੂਰੀ ਹੋਈ',
'actionfailed' => 'ਕਾਰਵਾਈ ਨਾਕਾਮ',
'dellogpage' => 'ਮਿਟਾਉਣ ਦਾ ਚਿੱਠਾ',
'deletecomment' => 'ਕਾਰਨ:',
'deleteotherreason' => 'ਹੋਰ/ਵਾਧੂ ਕਾਰਨ:',
'deletereasonotherlist' => 'ਹੋਰ ਕਾਰਨ',

# Rollback
'rollback_short' => 'ਰੋਲਬੈਕ',
'rollbacklink' => 'ਵਾਪਸ ਮੋੜੋ',
'rollbackfailed' => 'ਰੋਲਬੈਕ ਫੇਲ੍ਹ',

# Protect
'protectlogpage' => 'ਸੁਰੱਖਿਆ ਚਿੱਠਾ',
'protectedarticle' => '"[[$1]]" ਸੁਰੱਖਿਅਤ ਕੀਤਾ',
'protect-legend' => 'ਸੁਰੱਖਿਆ ਕਨਫਰਮ',
'protectcomment' => 'ਕਾਰਨ:',
'protectexpiry' => 'ਮਿਆਦ:',
'protect-default' => 'ਸਭ ਯੂਜ਼ਰ ਮਨਜ਼ੂਰ',
'protect-fallback' => '"$1" ਅਧਿਕਾਰ ਲੋੜੀਦਾ ਹੈ',
'protect-level-autoconfirmed' => 'ਨਵੇਂ ਤੇ ਗ਼ੈਰ-ਰਜਿਸਟਰ ਯੂਜ਼ਰਾਂ ਉੱਤੇ ਪਾਬੰਦੀ',
'protect-level-sysop' => 'ਕੇਵਲ ਪਰਸ਼ਾਸ਼ਕ',
'restriction-type' => 'ਅਧਿਕਾਰ:',
'minimum-size' => 'ਘੱਟੋ-ਘੱਟ ਆਕਾਰ',
'maximum-size' => 'ਵੱਧੋ-ਵੱਧ ਆਕਾਰ',
'pagesize' => '(ਬਾਈਟ)',

# Restrictions (nouns)
'restriction-edit' => 'ਸੋਧ',
'restriction-move' => 'ਭੇਜੋ',
'restriction-upload' => 'ਅੱਪਲੋਡ',

# Restriction levels
'restriction-level-sysop' => 'ਪੂਰਾ ਸੁਰੱਖਿਅਤ',
'restriction-level-autoconfirmed' => 'ਅਰਧ-ਸੁਰੱਖਿਅਤ',
'restriction-level-all' => 'ਕੋਈ ਲੈਵਲ',

# Undelete
'undeletebtn' => 'ਰੀਸਟੋਰ',
'undeletelink' => 'ਵੇਖੋ/ਮੁੜ ਬਹਾਲ ਕਰੋ',
'undeleteviewlink' => 'ਵੇਖੋ',
'undeletereset' => 'ਰੀ-ਸੈੱਟ',
'undeletecomment' => 'ਟਿੱਪਣੀ:',
'undelete-show-file-submit' => 'ਹਾਂ',

# Namespace form on various pages
'namespace' => 'ਥਾਂ-ਨਾਮ:',
'invert' => 'ਉਲਟ ਚੋਣ',
'blanknamespace' => '(ਮੁੱਖ)',

# Contributions
'contributions' => 'ਮੈਂਬਰ ਯੋਗਦਾਨ',
'contributions-title' => '$1 ਲਈ ਮੈਂਬਰ ਯੋਗਦਾਨ',
'mycontris' => 'ਮੇਰਾ ਯੋਗਦਾਨ',
'contribsub2' => '$1 ($2) ਲਈ',
'uctop' => '(ਟੀਸੀ)',
'month' => 'ਇਸ (ਅਤੇ ਪਿਛਲੇ) ਮਹੀਨੇ ਤੋਂ :',
'year' => 'ਇਸ (ਅਤੇ ਪਿਛਲੇ) ਸਾਲ ਤੋਂ :',

'sp-contributions-newbies' => 'ਸਿਰਫ਼ ਨਵੇਂ ਮੈਂਬਰਾਂ ਦੇ ਯੋਗਦਾਨ ਵਖਾਓ',
'sp-contributions-newbies-sub' => 'ਨਵੇਂ ਅਕਾਊਂਟਾਂ ਲਈ',
'sp-contributions-blocklog' => 'ਪਾਬੰਦੀ ਚਿੱਠਾ',
'sp-contributions-uploads' => 'ਅਪਲੋਡ',
'sp-contributions-logs' => 'ਚਿੱਠੇ',
'sp-contributions-talk' => 'ਗੱਲ-ਬਾਤ',
'sp-contributions-search' => 'ਯੋਗਦਾਨ ਖੋਜੋ',
'sp-contributions-username' => 'IP ਪਤਾ ਜਾਂ ਯੂਜ਼ਰ ਨਾਂ:',
'sp-contributions-toponly' => 'ਸਿਰਫ਼ ਉਹੀ ਸੋਧਾਂ ਵਖਾਓ ਜੋ ਸਭ ਤੋਂ ਨਵੀਂਆਂ ਹਨ',
'sp-contributions-submit' => 'ਖੋਜੋ',

# What links here
'whatlinkshere' => 'ਕਿਹੜੇ (ਸਫ਼ੇ) ਇੱਥੇ ਜੋੜਦੇ ਹਨ',
'whatlinkshere-title' => '$1 ਨਾਲ਼ ਜੋੜਦੇ ਸਫ਼ੇ',
'whatlinkshere-page' => 'ਸਫਾ:',
'linkshere' => "ਇਹ ਸਫ਼ੇ '''[[:$1]]''' ਨਾਲ਼ ਜੋੜਦੇ ਹਨ:",
'nolinkshere' => "ਕੋਈ ਵੀ ਸਫ਼ਾ '''[[:$1]]''' ਨਾਲ਼ ਨਹੀਂ ਜੋੜਦਾ।",
'isredirect' => 'ਰੀ-ਡਿਰੈਕਟ ਸਫ਼ਾ',
'istemplate' => 'ਟਾਕਰਾ ਕਰੋ',
'isimage' => 'ਫ਼ਾਈਲ ਦਾ ਲਿੰਕ',
'whatlinkshere-prev' => '{{PLURAL:$1|ਪਿਛਲਾ|ਪਿਛਲੇ $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|ਅਗਲਾ|ਅਗਲੇ $1}}',
'whatlinkshere-links' => '← ਲਿੰਕ',
'whatlinkshere-hideredirs' => 'ਅਸਿੱਧੇ ਰਾਹ $1',
'whatlinkshere-hidetrans' => '$1 ਇੱਥੇ ਕੀ ਕੀ ਜੁੜਦਾ ਹੈ।',
'whatlinkshere-hidelinks' => '$1 ਲਿੰਕ',
'whatlinkshere-hideimages' => 'ਤਸਵੀਰ ਲਿੰਕ $1',
'whatlinkshere-filters' => 'ਛਾਨਣੀਆਂ',

# Block/unblock
'blockip' => 'ਯੂਜ਼ਰ ਬਲਾਕ ਕਰੋ',
'ipadressorusername' => 'IP ਐਡਰੈਸ ਜਾਂ ਯੂਜ਼ਰ ਨਾਂ:',
'ipbexpiry' => 'ਮਿਆਦ:',
'ipbreason' => 'ਕਾਰਨ:',
'ipbreasonotherlist' => 'ਹੋਰ ਕਾਰਨ',
'ipbsubmit' => 'ਇਹ ਯੂਜ਼ਰ ਲਈ ਪਾਬੰਦੀ',
'ipbother' => 'ਹੋਰ ਟਾਈਮ:',
'ipboptions' => '੨ ਘੰਟੇ:2 hours, ੧ ਦਿਨ:1 day, ੩ ਦਿਨ:3 days, ੧ ਹਫ਼ਤਾ:1 week, ੨ ਹਫ਼ਤੇ:2 weeks, ੧ ਮਹੀਨਾ:1 month, ੩ ਮਹੀਨੇ:3 months, ੬ ਮਹੀਨੇ:6 months, ੧ ਸਾਲ:1 year, ਹਮੇਸ਼ਾ ਲਈ:infinite',
'ipbotheroption' => 'ਹੋਰ',
'ipbotherreason' => 'ਹੋਰ/ਆਮ ਕਾਰਨ:',
'badipaddress' => 'ਗਲਤ IP ਐਡਰੈੱਸ',
'ipb-unblock-addr' => '$1 ਅਣ-ਬਲਾਕ',
'ipb-unblock' => 'ਇੱਕ ਯੂਜ਼ਰ ਨਾਂ ਜਾਂ IP ਐਡਰੈੱਸ ਅਣ-ਬਲਾਕ ਕਰੋ',
'unblockip' => 'ਯੂਜ਼ਰ ਅਣ-ਬਲਾਕ ਕਰੋ',
'ipblocklist' => 'ਪਾਬੰਦੀਸ਼ੁਦਾ ਮੈਂਬਰ',
'ipblocklist-submit' => 'ਖੋਜ',
'infiniteblock' => 'ਬੇਅੰਤ',
'expiringblock' => '$1 $2 ਮਿਆਦ ਖਤਮ',
'anononlyblock' => 'anon. ਹੀ',
'emailblock' => 'ਈਮੇਲ ਬਲਾਕ ਹੈ',
'blocklink' => 'ਪਾਬੰਦੀ ਲਾਓ',
'unblocklink' => 'ਪਾਬੰਦੀ ਰੱਦ ਕਰੋ',
'change-blocklink' => 'ਪਾਬੰਦੀ ਬਦਲੋ',
'contribslink' => 'ਯੋਗਦਾਨ',
'blocklogpage' => 'ਪਾਬੰਦੀ ਚਿੱਠਾ',
'blocklogentry' => '[[$1]] ’ਤੇ $2 ਲਈ ਪਾਬੰਦੀ ਲਾਈ। $3',
'unblocklogentry' => '$1 ਤੋਂ ਪਾਬੰਦੀ ਹਟਾਈ',
'block-log-flags-nocreate' => 'ਖਾਤਾ ਬਣਾਉਣ ’ਤੇ ਪਾਬੰਦੀ ਹੈ',
'proxyblocksuccess' => 'ਪੂਰਾ ਹੋਇਆ',

# Developer tools
'lockdb' => 'ਡਾਟਾਬੇਸ ਲਾਕ',

# Move page
'move-page-legend' => 'ਪੇਜ ਮੂਵ ਕਰੋ',
'movearticle' => 'ਸਫ਼ਾ ਭੇਜੋ:',
'movenologin' => 'ਲਾਗਇਨ ਨਹੀਂ ਹੋ',
'movenologintext' => 'ਇੱਕ ਪੇਜ ਮੂਵ ਕਰਨ ਲਈ ਤੁਸੀਂ ਰਜਿਸਟਰਡ ਮੈਂਬਰ ਹੋਣੇ ਚਾਹੀਦੇ ਹੋ ਅਤੇ [[Special:UserLogin|ਲਾਗਡ ਇਨ]] ਕੀਤਾ ਹੋਣਾ ਚਾਹੀਦਾ ਹੈ।',
'newtitle' => 'ਨਵੇਂ ਟਾਈਟਲ ਲਈ:',
'move-watch' => 'ਸਰੋਤ ਤੇ ਟਾਰਗੇਟ ਸਫ਼ੇ ਉੱਤੇ ਨਿਗਰਾਨੀ ਰੱਖੋ',
'movepagebtn' => 'ਸਫ਼ਾ ਭੇਜੋ',
'pagemovedsub' => 'ਭੇਜਣਾ ਸਫ਼ਲ ਰਿਹਾ',
'movepage-moved' => '\'\'\'"$1" ਨੂੰ  "$2"\'\'\' ਉੱਤੇ ਭੇਜਿਆ',
'movedto' => 'ਮੂਵ ਕੀਤਾ',
'movelogpage' => 'ਭੇਜੇ ਜਾਣ ਦਾ ਚਿੱਠਾ',
'movereason' => 'ਕਾਰਨ:',
'revertmove' => 'ਰੱਦ ਕਰੋ',
'delete_and_move' => 'ਹਟਾਓ ਅਤੇ ਮੂਵ ਕਰੋ',

# Export
'export' => 'ਸਫ਼ੇ ਐਕਸਪੋਰਟ ਕਰੋ',
'export-submit' => 'ਐਕਸਪੋਰਟ',
'export-addcat' => 'ਸ਼ਾਮਲ',
'export-addns' => 'ਸ਼ਾਮਲ',
'export-download' => 'ਫਾਇਲ ਵਜੋਂ ਸੰਭਾਲੋ',

# Namespace 8 related
'allmessages' => 'ਸਿਸਟਮ ਸੁਨੇਹੇ',
'allmessagesname' => 'ਨਾਮ',
'allmessagesdefault' => 'ਡਿਫਾਲਟ ਟੈਕਸਟ',
'allmessagescurrent' => 'ਮੌਜੂਦਾ ਟੈਕਸਟ',
'allmessages-language' => 'ਭਾਸ਼ਾ:',
'allmessages-filter-submit' => 'ਜਾਓ',

# Thumbnails
'thumbnail-more' => 'ਵਧਾਓ',
'filemissing' => 'ਫਾਇਲ ਗੁੰਮ ਹੈ',
'thumbnail_error' => 'ਨਮੂਨਾ ਬਣਾਉਣ ਵਿਚ ਗ਼ਲਤੀ ਹੋਈ ਹੈ: $1',

# Special:Import
'import' => 'ਪੇਜ ਇੰਪੋਰਟ ਕਰੋ',
'import-interwiki-submit' => 'ਇੰਪੋਰਟ',
'import-comment' => 'ਟਿੱਪਣੀ:',
'importstart' => 'ਪੇਜ ਇੰਪੋਰਟ ਕੀਤੇ ਜਾ ਰਹੇ ਹਨ...',
'importfailed' => 'ਇੰਪੋਰਟ ਫੇਲ੍ਹ: $1',
'importnotext' => 'ਖਾਲੀ ਜਾਂ ਕੋਈ ਟੈਕਸਟ ਨਹੀਂ',
'importsuccess' => 'ਇੰਪੋਰਟ ਸਫ਼ਲ!',
'importnofile' => 'ਕੋਈ ਇੰਪੋਰਟ ਫਾਇਲ ਅੱਪਲੋਡ ਨਹੀਂ ਕੀਤੀ।',

# Import log
'importlogpage' => 'ਇੰਪੋਰਟ ਲਾਗ',
'import-logentry-upload-detail' => '$1 ਰੀਵਿਜ਼ਨ',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'ਤੁਹਾਡਾ ਮੈਂਬਰ ਸਫ਼ਾ',
'tooltip-pt-mytalk' => 'ਤੁਹਾਡਾ ਗੱਲਬਾਤ ਸਫ਼ਾ',
'tooltip-pt-preferences' => 'ਤੁਹਾਡੀਆਂ ਪਸੰਦਾਂ',
'tooltip-pt-watchlist' => 'ਓਹਨਾਂ ਸਫ਼ਿਆਂ ਦੀ ਲਿਸਟ ਜੋ ਤੁਸੀਂ ਤਬਦੀਲੀਆਂ ਲਈ ਵੇਖ ਰਹੇ ਹੋ',
'tooltip-pt-mycontris' => 'ਤੁਹਾਡੇ ਯੋਗਦਾਨਾਂ ਦੀ ਲਿਸਟ',
'tooltip-pt-login' => 'ਤੁਹਾਨੂੰ ਲਾਗਇਨ ਕਰਨ ਲਈ ਉਤਸ਼ਾਹਿਤ ਕੀਤਾ ਜਾਂਦਾ ਹੈ; ਪਰ ਇਹ ਕੋਈ ਲਾਜ਼ਮੀ ਨਹੀਂ',
'tooltip-pt-logout' => 'ਲਾਗ ਆਉਟ',
'tooltip-ca-talk' => 'ਸਮਗੱਰੀ ਸਫ਼ੇ ਬਾਰੇ ਚਰਚਾ',
'tooltip-ca-edit' => 'ਤੁਸੀਂ ਇਹ ਸਫ਼ਾ ਸੋਧ ਸਕਦੇ ਹੋ। ਮਿਹਰਬਾਨੀ ਕਰਕੇ ਸੰਭਾਲਣ ਤੋਂ ਪਹਿਲਾਂ ਝਲਕ ਬਟਨ ਵਰਤੋ',
'tooltip-ca-addsection' => 'ਨਵਾਂ ਭਾਗ ਸ਼ੁਰੂ ਕਰੋ',
'tooltip-ca-viewsource' => 'ਇਹ ਸਫ਼ਾ ਸੁਰੱਖਿਅਤ ਹੈ।
ਤੁਸੀਂ ਇਸਦਾ ਸਰੋਤ ਵੇਖ ਸਕਦੇ ਹੋ।',
'tooltip-ca-history' => 'ਇਸ ਸਫ਼ੇ ਦੇ ਪਿਛਲੇ ਰੀਵਿਜ਼ਨ',
'tooltip-ca-protect' => 'ਇਹ ਸਫ਼ਾ ਮਹਿਫ਼ੂਜ਼ ਕਰੋ',
'tooltip-ca-delete' => 'ਇਹ ਸਫ਼ਾ ਮਿਟਾਓ',
'tooltip-ca-move' => 'ਇਹ ਸਫ਼ਾ ਭੇਜੋ',
'tooltip-ca-watch' => 'ਇਹ ਸਫ਼ਾ ਆਪਣੀ ਨਿਗਰਾਨੀ-ਲਿਸਟ ਵਿਚ ਸ਼ਾਮਲ ਕਰੋ',
'tooltip-ca-unwatch' => 'ਇਹ ਸਫ਼ਾ ਆਪਣੀ ਨਿਗਰਾਨੀ-ਲਿਸਟ ’ਚੋਂ ਹਟਾਓ',
'tooltip-search' => '{{SITENAME}} ’ਤੇ ਖੋਜੋ',
'tooltip-search-go' => 'ਠੀਕ ਇਸ ਨਾਮ ਵਾਲ਼ੇ ਸਫ਼ੇ ’ਤੇ ਜਾਉ, ਜੇ ਮੌਜੂਦ ਹੈ ਤਾਂ',
'tooltip-search-fulltext' => 'ਇਸ ਲਿਖਤ ਲਈ ਸਫ਼ੇ ਲੱਭੋ',
'tooltip-p-logo' => 'ਮੁੱਖ ਸਫ਼ੇ ’ਤੇ ਜਾਓ',
'tooltip-n-mainpage' => 'ਮੁੱਖ ਸਫ਼ੇ ’ਤੇ ਜਾਓ',
'tooltip-n-mainpage-description' => 'ਮੁੱਖ ਸਫ਼ੇ ’ਤੇ ਜਾਓ',
'tooltip-n-portal' => 'ਪਰੋਜੈਕਟ ਬਾਰੇ, ਤੁਸੀਂ ਕੀ ਕਰ ਸਕਦੇ ਹੋ, ਕਿੱਥੇ ਕੁਝ ਲੱਭਣਾ ਹੈ',
'tooltip-n-currentevents' => 'ਮੌਜੂਦਾ ਸਮਾਗਮ ਬਾਰੇ ਪਿਛਲੀ ਜਾਣਕਾਰੀ ਲੱਭੋ',
'tooltip-n-recentchanges' => 'ਵਿਕੀ ’ਚ ਤਾਜ਼ਾ ਤਬਦੀਲੀਆਂ ਦੀ ਲਿਸਟ',
'tooltip-n-randompage' => 'ਇਕ ਰਲ਼ਵਾਂ ਸਫ਼ਾ ਲੋਡ ਕਰੋ',
'tooltip-n-help' => 'ਖੋਜਣ ਲਈ ਥਾਂ',
'tooltip-t-whatlinkshere' => 'ਵਿਕੀ ਦੇ ਸਾਰੇ ਸਫ਼ਿਆਂ ਦੀ ਲਿਸਟ, ਜੋ ਇੱਥੇ ਜੋੜਦੇ ਹਨ',
'tooltip-t-recentchangeslinked' => 'ਇਸ ਸਫ਼ੇ ਤੋਂ ਲਿੰਕ ਕੀਤੇ ਸਫ਼ਿਆਂ ਵਿੱਚ ਤਾਜ਼ਾ ਤਬਦੀਲੀਆਂ',
'tooltip-feed-atom' => 'ਇਸ ਸਫ਼ੇ ਦੀ ਐਟਮ ਫ਼ੀਡ',
'tooltip-t-contributions' => 'ਇਸ ਮੈਂਬਰ ਦੇ ਯੋਗਦਾਨ ਦੀ ਲਿਸਟ',
'tooltip-t-emailuser' => 'ਇਸ ਮੈਂਬਰ ਨੂੰ ਈ-ਮੇਲ ਭੇਜੋ',
'tooltip-t-upload' => 'ਚਿੱਤਰ ਜਾਂ ਮੀਡਿਆ ਫਾਇਲਾਂ ਅੱਪਲੋਡ ਕਰੋ',
'tooltip-t-specialpages' => 'ਸਾਰੇ ਖ਼ਾਸ ਸਫ਼ਿਆਂ ਦੀ ਲਿਸਟ',
'tooltip-t-print' => 'ਇਹ ਸਫ਼ੇ ਦਾ ਛਪਣਯੋਗ ਵਰਜਨ',
'tooltip-t-permalink' => 'ਸਫ਼ੇ ਦੇ ਇਸ ਰੀਵਿਜ਼ਨ ਲਈ ਪੱਕਾ ਲਿੰਕ',
'tooltip-ca-nstab-main' => 'ਸਮੱਗਰੀ ਸਫ਼ਾ ਵੇਖੋ',
'tooltip-ca-nstab-user' => 'ਮੈਂਬਰ ਸਫ਼ਾ ਵੇਖੋ',
'tooltip-ca-nstab-media' => 'ਮੀਡਿਆ ਪੇਜ ਵੇਖੋ',
'tooltip-ca-nstab-special' => 'ਇਹ ਖ਼ਾਸ ਸਫ਼ਾ ਹੈ, ਤੁਸੀਂ ਇਸ ਸਫ਼ੇ ਨੂੰ ਸੋਧ ਨਹੀਂ ਸਕਦੇ।',
'tooltip-ca-nstab-project' => 'ਪ੍ਰੋਜੈਕਟ ਸਫ਼ਾ ਵੇਖੋ',
'tooltip-ca-nstab-image' => 'ਫ਼ਾਈਲ ਸਫ਼ਾ ਵੇਖੋ',
'tooltip-ca-nstab-mediawiki' => 'ਸਿਸਟਮ ਸੁਨੇਹੇ ਵੇਖੋ',
'tooltip-ca-nstab-template' => 'ਸਾਂਚਾ ਵੇਖੋ',
'tooltip-ca-nstab-help' => 'ਮੱਦਦ ਪੇਜ ਵੇਖੋ',
'tooltip-ca-nstab-category' => 'ਕੈਟਾਗਰੀ ਸਫ਼ਾ ਵੇਖੋ',
'tooltip-minoredit' => 'ਇਸ ’ਤੇ ਛੋਟੀ ਤਬਦਲੀ ਦੇ ਤੌਰ ’ਤੇ ਨਿਸ਼ਾਨ ਲਾਓ',
'tooltip-save' => 'ਆਪਣੀਆਂ ਤਬਦੀਲੀਆਂ ਸਾਂਭੋ',
'tooltip-preview' => 'ਆਪਣੀ ਤਬਦੀਲੀ ਦੀ ਝਲਕ ਵੇਖੋ, ਸਾਂਭਣ ਤੋਂ ਪਹਿਲਾਂ ਇਹ ਵਰਤੋਂ!',
'tooltip-diff' => 'ਤੁਹਾਡੇ ਦੁਆਰਾ ਲਿਖਤ ਵਿਚ ਕੀਤੀਆਂ ਤਬਦੀਲੀਆਂ ਵਖਾਉਂਦਾ ਹੈ',
'tooltip-compareselectedversions' => 'ਇਸ ਸਫ਼ੇ ਦੇ ਦੋ ਚੁਣੇ ਹੋਏ ਸੋਧਾਂ ਵਿਚ ਫ਼ਰਕ ਵੇਖੋ',
'tooltip-watch' => 'ਇਸ ਸਫ਼ੇ ਨੂੰ ਆਪਣੀ ਨਿਗਰਾਨੀ-ਲਿਸਟ ਵਿਚ ਪਾਓ',
'tooltip-upload' => 'ਅੱਪਲੋਡ ਸਟਾਰਟ ਕਰੋ',
'tooltip-rollback' => "''ਵਾਪਸ ਮੋੜੋ'' ਇਕ ਹੀ ਕਲਿੱਕ ਨਾਲ਼ ਆਖ਼ਰੀ ਯੋਗਦਾਨ ਨੂੰ ਰੱਦ ਕਰ ਦਿੰਦਾ ਹੈ",
'tooltip-undo' => '"ਨਕਾਰੋ" ਇਸ ਤਬਦੀਲੀ ਨੂੰ ਰੱਦ ਕਰਕੇ ਸੋਧ ਫ਼ਾਰਮ ਨੂੰ ਝਲਕ ਦੇ ਅੰਦਾਜ਼ ਵਿਚ ਦਿਖਾਉਂਦਾ ਹੈ।
ਇੰਝ "ਸਾਰ" ਵਿਚ ਤਬਦੀਲੀ ਨਕਾਰਨ ਦਾ ਕਾਰਨ ਲਿਖਿਆ ਜਾ ਸਕਦਾ ਹੈ।',
'tooltip-summary' => 'ਸੰਖੇਪ ਸਾਰ ਦਰਜ ਕਰੋ',

# Attribution
'others' => 'ਹੋਰ',
'siteusers' => '{{SITENAME}} ਯੂਜ਼ਰ $1',
'creditspage' => 'ਪੇਜ ਮਾਣ',

# Spam protection
'spamprotectiontitle' => 'Spam ਸੁਰੱਖਿਆ ਫਿਲਟਰ',

# Skin names
'skinname-standard' => 'ਕਲਾਸਿਕ',
'skinname-monobook' => 'ਮੋਨੋਬੁੱਕ',
'skinname-myskin' => 'ਮੇਰੀਸਕਿਨ',
'skinname-chick' => 'ਚੀਚਕ',
'skinname-simple' => 'ਸੈਂਪਲ',

# Browsing diffs
'previousdiff' => '← ਪੁਰਾਣੀ ਸੋਧ',
'nextdiff' => 'ਨਵੀਂ ਸੋਧ →',

# Media information
'thumbsize' => 'ਥੰਮਨੇਲ ਆਕਾਰ:',
'widthheightpage' => '$1 × $2, $3 ਪੇਜ਼',
'file-info' => 'ਫਾਇਲ ਆਕਾਰ: $1, MIME ਕਿਸਮ: $2',
'file-info-size' => '$1 × $2 ਪਿਕਸਲ, ਫ਼ਾਈਲ ਆਕਾਰ: $3, MIME ਕਿਸਮ: $4',
'file-nohires' => 'ਇਸ ਤੋਂ ਵੱਡੀ ਤਸਵੀਰ ਮੌਜੂਦ ਨਹੀਂ ਹੈ।',
'svg-long-desc' => 'SVG ਫ਼ਾਈਲ, ਆਮ ਤੌਰ ’ਤੇ $1 × $2 ਪਿਕਸਲ, ਫ਼ਾਈਲ ਦਾ ਅਕਾਰ: $3',
'show-big-image' => 'ਪੂਰਾ ਰੈਜ਼ੋਲੇਸ਼ਨ',

# Special:NewFiles
'newimages' => 'ਨਵੀਆਂ ਫਾਇਲਾਂ ਦੀ ਗੈਲਰੀ',
'noimages' => 'ਵੇਖਣ ਲਈ ਕੁਝ ਨਹੀਂ',
'ilsubmit' => 'ਖੋਜ',
'bydate' => 'ਮਿਤੀ ਨਾਲ',

# Bad image list
'bad_image_list' => 'ਤਰਤੀਬ ਇਸ ਤਰਾਂ ਹੈ:
ਸਿਰਫ਼ ਲਿਸਟ ਵਿਚਲੀਆਂ ਚੀਜ਼ਾਂ (* ਨਾਲ ਸ਼ੁਰੂ ਹੋਣ ਵਾਲੀਆਂ ਕਤਾਰਾਂ) ’ਤੇ ਹੀ ਗ਼ੌਰ ਕੀਤਾ ਜਾਵੇਗਾ।
ਲਾਈਨ ਵਿਚ ਪਹਿਲੀ ਕੜੀ ਗ਼ਲਤ ਫ਼ਾਈਲ ਦੀ ਕੜੀ ਹੋਣੀ ਚਾਹੀਦੀ ਹੈ। ਉਸ ਲਾਈਨ ’ਚ ਅੱਗੇ ਦਿਤੀਆਂ ਕੜੀਆਂ ਨੂੰ ਇਤਰਾਜ਼ਯੋਗ ਮੰਨਿਆ ਜਾਵੇਗਾ, ਭਾਵ ਉਹ ਸਫ਼ੇ ਜਿਨ੍ਹਾਂ ਵਿਚ ਫ਼ਾਈਲ ਕਿਸੇ ਲਾਈਨ ਵਿਚ ਸਥਿਤ ਹੋ ਸਕਦੀ ਹੈ।',

# Metadata
'metadata' => 'ਮੇਟਾ ਡੈਟਾ',
'metadata-help' => 'ਇਸ ਫ਼ਾਈਲ ਵਿਚ ਵਾਧੂ ਜਾਣਕਾਰੀਆਂ ਹਨ, ਜੋ ਸ਼ਾਇਦ ਉਸ ਕੈਮਰੇ ਜਾਂ ਸਕੈਨਰ ਦੀ ਦੇਣ ਹਨ ਜੋ ਇਸਨੂੰ ਬਣਾਉਣ ਲਈ ਵਰਤਿਆ ਗਿਆ। ਜੇ ਇਸ ਫ਼ਾਈਲ ਵਿਚ ਕੋਈ ਤਬਦੀਲੀ ਕੀਤੀ ਗਈ ਹੈ ਤਾਂ ਹੋ ਸਕਦਾ ਹੈ ਕੁਝ ਵੇਰਵੇ ਬਦਲੀ ਫ਼ਾਈਲ ਦਾ ਸਹੀ ਰੂਪਮਾਨ ਨਾ ਹੋਣ।',
'metadata-fields' => 'ਇਸ ਸੁਨੇਹੇ ਵਿਚ ਸੂਚੀਬੱਧ ਖੇਤਰ ਤਸਵੀਰ ਸਫ਼ੇ ’ਚ ਸ਼ਾਮਲ ਕੀਤੇ ਜਾਣਗੇ ਜੋ ਉਦੋਂ ਦਿੱਸਦੇ ਹਨ ਜਦੋ ਮੈਟਾਡੈਟਾ ਖ਼ਾਕਾ ਬੰਦ ਹੋਵੇ। ਬਾਕੀ ਉਂਞ ਹੀ ਲੁਕੇ ਹੋਣਗੇ।',

# EXIF tags
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

# External editor support
'edit-externally' => 'ਬਾਹਰੀ ਐਪਲੀਕੇਸ਼ਨ ਵਰਤਦੇ ਹੋਏ ਇਸ ਫ਼ਾਈਲ ਨੂੰ ਸੋਧੋ',
'edit-externally-help' => '(ਜ਼ਿਆਦਾ ਜਾਣਕਾਰੀ ਲਈ [//www.mediawiki.org/wiki/Manual:External_editors setup instructions] ਵੇਖੋ)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'ਸਭ',
'namespacesall' => 'ਸਭ',
'monthsall' => 'ਸਭ',

# E-mail address confirmation
'confirmemail' => 'ਈਮੇਲ ਐਡਰੈੱਸ ਪੁਸ਼ਟੀ',
'confirmemail_send' => 'ਇੱਕ ਪੁਸ਼ਟੀ ਕੋਡ ਭੇਜੋ',
'confirmemail_sent' => 'ਪੁਸ਼ਟੀ ਈਮੇਲ ਭੇਜੀ ਗਈ।',
'confirmemail_invalid' => 'ਗਲਤ ਪੁਸ਼ਟੀ ਕੋਡ ਹੈ। ਕੋਡ ਦੀ ਮਿਆਦ ਪੁੱਗੀ ਹੋ ਸਕਦੀ ਹੈ।',
'confirmemail_loggedin' => 'ਹੁਣ ਤੁਹਾਡਾ ਈਮੇਲ ਐਡਰੈੱਸ ਚੈੱਕ (confirmed) ਹੋ ਗਿਆ ਹੈ।',
'confirmemail_subject' => '{{SITENAME}} ਈਮੇਲ ਐਡਰੈੱਸ ਪੁਸ਼ਟੀ',

# Scary transclusion
'scarytranscludetoolong' => '[ਅਫਸੋਸ ਹੈ ਕਿ URL ਬਹੁਤ ਲੰਮਾ ਹੈ]',

# Delete conflict
'recreate' => 'ਮੁੜ-ਬਣਾਓ',

# action=purge
'confirm_purge_button' => 'ਠੀਕ ਹੈ',

# Multipage image navigation
'imgmultipageprev' => '← ਪਿਛਲਾ ਪੇਜ',
'imgmultipagenext' => 'ਅਗਲਾ ਪੇਜ →',
'imgmultigo' => 'ਜਾਓ!',
'imgmultigoto' => '$1 ਸਫ਼ੇ ਉੱਤੇ ਜਾਓ',

# Table pager
'table_pager_next' => 'ਅਗਲਾ ਪੇਜ',
'table_pager_prev' => 'ਪਿਛਲਾ ਪੇਜ',
'table_pager_first' => 'ਪਹਿਲਾ ਪੇਜ',
'table_pager_last' => 'ਆਖਰੀ ਪੇਜ',
'table_pager_limit' => 'ਹਰੇਕ ਪੇਜ ਲਈ $1 ਆਈਟਮਾਂ',
'table_pager_limit_label' => 'ਪ੍ਰਤੀ ਸਫ਼ਾ ਆਈਟਮਾਂ:',
'table_pager_limit_submit' => 'ਜਾਓ',
'table_pager_empty' => 'ਕੋਈ ਨਤੀਜਾ ਨਹੀਂ',

# Auto-summaries
'autosumm-blank' => 'ਪੇਜ ਨੂੰ ਖਾਲੀ ਕਰ ਦਿੱਤਾ',
'autosumm-new' => '$1 ਨਾਲ ਪੇਜ ਬਣਾਇਆ',

# Live preview
'livepreview-loading' => 'ਲੋਡ ਕੀਤਾ ਜਾ ਰਿਹਾ ਹੈ…',
'livepreview-ready' => 'ਲੋਡ ਕੀਤਾ ਜਾ ਰਿਹਾ ਹੈ...ਤਿਆਰ!',

# Watchlist editor
'watchlistedit-raw-titles' => 'ਟਾਇਟਲ:',
'watchlistedit-raw-added' => '{{PLURAL:$1|1 title was|$1 titles were}} ਸ਼ਾਮਲ:',
'watchlistedit-raw-removed' => '{{PLURAL:$1|1 title was|$1 titles were}} ਹਟਾਓ:',

# Watchlist editing tools
'watchlisttools-view' => 'ਮੌਕੇ ਮੁਤਾਬਕ ਤਬਦੀਲੀਆਂ ਵੇਖੋ',
'watchlisttools-edit' => 'ਨਿਗਰਾਨੀ-ਲਿਸਟ ਵੇਖੋ ’ਤੇ ਸੋਧੋ',
'watchlisttools-raw' => 'ਕੱਚੀ ਨਿਗਰਾਨੀ-ਲਿਸਟ ਸੋਧੋ',

# Special:Version
'version' => 'ਵਰਜਨ',

# Special:SpecialPages
'specialpages' => 'ਖ਼ਾਸ ਸਫ਼ੇ',
'specialpages-group-login' => 'ਲਾਗ ਇਨ / ਅਕਾਊਂਟ ਬਣਾਓ',

# Special:BlankPage
'blankpage' => 'ਖ਼ਾਲੀ ਪੇਜ',

# External image whitelist
'external_image_whitelist' => " #ਇਸ ਲਾਈਨ ਨੂੰ ਇੰਝ ਹੀ ਰਹਿਣ ਦਿਓ <pre>
#ਹੇਠਾਂ ਓਹੀ ਐਕਸਪ੍ਰੈਸ਼ਨ ਪਾਓ (ਜਿਹੜਾ ਹਿੱਸਾ // ਦੇ ਵਿਚਾਲੇ ਹੈ)
#ਇਹ ਬਾਹਰੀ ਤਸਵੀਰਾਂ ਦੇ URLs (ਹੌਟਲਿੰਕਡ) ਨਾਲ਼ ਮਿਲਣਗੀਆਂ
#ਜਿਹੜੀਆਂ ਮਿਲਣਗੀਆਂ ਓਹ ਬਤੌਰ ਤਸਵੀਰਾਂ ਦਿੱਸਣਗੀਆਂ ਨਹੀਂ ਤਾਂ ਤਸਵੀਰ ਦਾ ਸਿਰਫ਼ ਲਿੰਕ ਨਜ਼ਰ ਆਵੇਗਾ
#'#' ਨਾਲ਼ ਸ਼ੁਰੂ ਹੋਣ ਵਾਲ਼ੀਆਂ ਲਾਈਨਾਂ ਟਿੱਪਣੀਆਂ ਵਾਂਗ ਲਈਆਂ ਜਾਂਦੀਆਂ ਹਨ
#ਇਹ ਕੇਸ-ਇਨਸੈਂਸਟਿਵ ਹੈ

#ਸਾਰੇ ਰੈਜੈਕਸ ਫ਼ਰੈਗਮੈਂਟ ਇਸ ਲਾਈਨ ਤੋਂ ਉੱਪਰ ਪਾਓ। ਇਸ ਲਾਈਨ ਨੂੰ ਇੰਝ ਹੀ ਰਹਿਣ ਦਿਓ </pre>",

# Special:Tags
'tag-filter' => '[[Special:Tags|ਟੈਗ]] ਛਾਨਣੀ:',

# HTML forms
'htmlform-submit' => 'ਭੇਜੋ',
'htmlform-reset' => 'ਬਦਲਾਅ ਵਾਪਸ ਲਵੋ',
'htmlform-selectorother-other' => 'ਹੋਰ',

);

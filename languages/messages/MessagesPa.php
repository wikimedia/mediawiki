<?php
/** Punjabi (Gurmukhi)
  * @addtogroup Language
  */
# This file is dual-licensed under GFDL and GPL.
#
# See: http://bugzilla.wikimedia.org/show_bug.cgi?id=1478

$skinNames = array(
	'standard'      => 'ਮਿਆਰੀ',
);

$namespaceNames = array(
	NS_MEDIA          => 'ਮੀਡੀਆ',
	NS_SPECIAL        => 'ਖਾਸ',
	NS_MAIN           => '',
	NS_TALK           => 'ਚਰਚਾ',
	NS_USER           => 'ਮੈਂਬਰ',
	NS_USER_TALK      => 'ਮੈਂਬਰ_ਚਰਚਾ',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => '$1_ਚਰਚਾ',
	NS_IMAGE          => 'ਤਸਵੀਰ',
	NS_IMAGE_TALK     => 'ਤਸਵੀਰ_ਚਰਚਾ',
	NS_MEDIAWIKI      => 'ਮੀਡੀਆਵਿਕਿ',
	NS_MEDIAWIKI_TALK => 'ਮੀਡੀਆਵਿਕਿ_ਚਰਚਾ',
	NS_TEMPLATE       => 'ਨਮੂਨਾ',
	NS_TEMPLATE_TALK  => 'ਨਮੂਨਾ_ਚਰਚਾ',
	NS_HELP           => 'ਮਦਦ',
	NS_HELP_TALK      => 'ਮਦਦ_ਚਰਚਾ',
	NS_CATEGORY       => 'ਸ਼੍ਰੇਣੀ',
	NS_CATEGORY_TALK  => 'ਸ਼੍ਰੇਣੀ_ਚਰਚਾ'
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


$messages = array(
# Dates
'sunday'    => 'ਐਤਵਾਰ',
'monday'    => 'ਸੋਮਵਾਰ',
'tuesday'   => 'ਮੰਗਲਵਾਰ',
'wednesday' => 'ਬੁਧਵਾਰ',
'thursday'  => 'ਵੀਰਵਾਰ',
'friday'    => 'ਸ਼ੁੱਕਰਵਾਰ',
'saturday'  => 'ਸ਼ਨੀਚਰਵਾਰ',
'january'   => 'ਜਨਵਰੀ',
'february'  => 'ਫ਼ਰਵਰੀ',
'march'     => 'ਮਾਰਚ',
'april'     => 'ਅਪ੍ਰੈਲ',
'may_long'  => 'ਮਈ',
'june'      => 'ਜੂਨ',
'july'      => 'ਜੁਲਾਈ',
'august'    => 'ਅਗਸਤ',
'september' => 'ਸਤੰਬਰ',
'october'   => 'ਅਕਤੂਬਰ',
'november'  => 'ਨਵੰਬਰ',
'december'  => 'ਦਸੰਬਰ',
'jan'       => 'ਜਨਵਰੀ',
'feb'       => 'ਫ਼ਰਵਰੀ',
'mar'       => 'ਮਾਰਚ',
'apr'       => 'ਅਪ੍ਰੈਲ',
'may'       => 'ਮਈ',
'jun'       => 'ਜੂਨ',
'jul'       => 'ਜੁਲਾਈ',
'aug'       => 'ਅਗਸਤ',
'sep'       => 'ਸਤੰਬਰ',
'oct'       => 'ਅਕਤੂਬਰ',
'nov'       => 'ਨਵੰਬਰ',
'dec'       => 'ਦਸੰਬਰ',

# Bits of text used by many pages
'categories'      => 'ਸ਼੍ਰੇਣੀਆਂ',
'pagecategories'  => 'ਸ਼੍ਰੇਣੀਆਂ',
'category_header' => "ਸ਼੍ਰੇਣੀ '$1' ਵਾਲੇ ਲੇਖ",
'subcategories'   => 'ਉਪਸ਼੍ਰੇਣੀਆਂ',

'mainpagetext' => 'ਵਿਕਿ ਸਾਫ਼ਟਵੇਅਰ ਚੰਗੀ ਤਰ੍ਹਾਂ ਇੰਸਟਾਲ ਹੋ ਗਿਆ ਹੈ',

'about'          => 'ਜਾਣਕਾਰੀ',
'article'        => 'ਵਿਸ਼ਾ-ਵਸਤੂ ਵਾਲਾ ਪੰਨਾ',
'newwindow'      => '(ਨਵੀਂ window ਵਿੱਚ ਖੁੱਲੇਗਾ)',
'cancel'         => 'ਰੱਦ ਕਰੋ',
'qbfind'         => 'ਲੱਭੋ',
'qbbrowse'       => 'ਵੇਖੋ - Browse',
'qbedit'         => 'ਬਦਲੋ',
'qbpageoptions'  => 'ਪੰਨਾ ਵਿਕਲਪ - Options',
'qbpageinfo'     => 'ਪੰਨਾ ਜਾਣਕਾਰੀ',
'qbmyoptions'    => 'ਮੇਰੇ ਵਿਕਲਪ',
'qbspecialpages' => 'ਖਾਸ ਪੰਨੇ',
'moredotdotdot'  => 'ਹੋਰ...',
'mypage'         => 'ਮੇਰਾ ਪੰਨਾ',
'mytalk'         => 'ਮੇਰੀ ਚਰਚਾ',
'anontalk'       => 'ਇੱਸ ਆਈ-ਪੀ (IP) ਦੀ ਚਰਚਾ',
'navigation'     => 'ਨੈਵੀਗੇਸ਼ੱਨ',

'errorpagetitle'    => 'ਗਲਤੀ',
'returnto'          => 'ਵਾਪਿਸ ਪਰਤੋ: $1.',
'tagline'           => '{{SITENAME}} ਤੋਂ',
'help'              => 'ਮਦਦ',
'search'            => 'ਖੋਜ',
'searchbutton'      => 'ਖੋਜ',
'go'                => 'ਜਾਓ',
'searcharticle'     => 'ਜਾਓ',
'history'           => 'ਪੁਰਾਣੇ ਆਵਰਤਣ',
'history_short'     => 'ਇਤਿਹਾਸ',
'info_short'        => 'ਸੂਚਨਾ',
'printableversion'  => 'ਛਾਪਣ-ਯੋਗ ਆਵਰਤਣ',
'edit'              => 'ਬਦਲੋ',
'editthispage'      => 'ਇਸ ਪੰਨੇ ਨੂੰ ਬਦਲੋ',
'delete'            => 'ਹਟਾਓ',
'deletethispage'    => 'ਇਸ ਪੰਨੇ ਨੂੰ ਹਟਾਓ',
'undelete_short'    => '$1 ਬਦਲਾਵ ਮੁੜ ਵਾਪਿਸ ਲਿਆਓ',
'protect'           => 'ਰੱਖਿਆ ਕਰੋ',
'protectthispage'   => 'ਇਸ ਪੰਨੇ ਦੀ ਰੱਖਿਆ ਕਰੋ',
'unprotect'         => 'ਅਸੁਰੱਖਿਅਤ ਕਰੋ',
'unprotectthispage' => 'ਇਸ ਪੰਨੇ ਨੂੰ ਅਸੁਰੱਖਿਅਤ ਕਰੋ',
'newpage'           => 'ਨਵਾਂ ਪੰਨਾ',
'talkpage'          => 'ਇਸ ਪੰਨੇ ਤੇ ਚਰਚਾ ਕਰੋ',
'specialpage'       => 'ਖ਼ਾਸ ਪੰਨਾ',
'personaltools'     => 'ਨਿਜੀ ਔਜ਼ਾਰ',
'postcomment'       => 'ਆਪਨੇ ਵਿਚਾਰ ਪੇਸ਼ ਕਰੋ',
'articlepage'       => 'ਵਿਸ਼ਾ-ਵਸਤੂ ਵਾਲਾ ਪੰਨਾ ਵੇਖੋ',
'talk'              => 'ਚਰਚਾ',
'toolbox'           => 'ਔਜ਼ਾਰ-ਡੱਬਾ',
'userpage'          => 'ਮੈਂਬਰ ਦਾ ਪੰਨਾ ਵੇਖੋ',
'projectpage'       => 'ਪਰਿਯੋਜਨਾ (project) ਵਾਲਾ ਪੰਨਾ ਵੇਖੋ',
'imagepage'         => 'ਤਸਵੀਰ ਵਾਲਾ ਪੰਨਾ ਵੇਖੋ',
'viewtalkpage'      => 'ਚਰਚਾ ਵਾਲਾ ਪੰਨਾ ਵੇਖੋ',
'otherlanguages'    => 'ਬਾਕੀ ਭਾਸ਼ਾਵਾਂ',
'redirectedfrom'    => '($1 ਤੋਂ ਭੇਜਿਆ ਗਿਆ ਹੈ)',
'lastmodifiedat'    => 'ਅਖੀਰਲਾ ਬਦਲਾਵ $2, $1', # $1 date, $2 time
'viewcount'         => 'ਇਹ ਪੰਨਾ $1 ਵਾਰ ਵੇਖਿਆ ਗਿਆ ਹੈ',
'protectedpage'     => 'ਸੁਰੱਖਿਅਤ ਪੰਨਾ',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'       => '{{SITENAME}} ਬਾਰੇ',
'aboutpage'       => 'Project:ਜਾਣਕਾਰੀ',
'bugreports'      => 'ਖਾਮੀ ਸੂਚਨਾ',
'bugreportspage'  => 'Project:ਖਾਮੀ_ਸੂਚਨਾ',
'copyright'       => 'ਵਿਸ਼ਾ-ਵਸਤੂ $1 ਤਹਿਤ ਉਪਲੱਬਧ ਹੈ',
'disclaimers'     => 'ਡਿਸਕਲੇਮਰ',
'edithelp'        => 'ਬਦਲਾਵ ਮਦਦ',
'edithelppage'    => 'ਮਦਦ:ਬਦਲਾਵ',
'faq'             => 'ਪ੍ਰਸ਼ਨਾਵਲੀ - FAQ',
'faqpage'         => 'Project:ਪ੍ਰਸ਼ਨਾਵਲੀ',
'helppage'        => 'ਮਦਦ:ਵਿਸ਼ਾ-ਵਸਤੂ',
'mainpage'        => 'ਮੁੱਖ ਪੰਨਾ',
'portal'          => 'ਸਮੂਹ ਦ੍ਵਾਰ',
'portal-url'      => 'Project:ਸਮੂਹ ਦ੍ਵਾਰ',
'sitesupport'     => 'ਦਾਨ',
'sitesupport-url' => 'Project:ਦਾਨ',

'ok'              => 'ਠੀਕ ਹੈ',
'pagetitle'       => '$1 - {{SITENAME}}',
'retrievedfrom'   => "'$1' ਤੋਂ ਪ੍ਰਾਪਤ ਕੀਤਾ ਗਿਆ ਹੈ",
'newmessageslink' => 'ਨਵੇਂ ਸੰਦੇਸ਼',
'editsection'     => 'ਬਦਲੋ',
'editold'         => 'ਬਦਲੋ',
'toc'             => 'ਵਿਸ਼ਾ-ਸੂਚੀ',
'showtoc'         => 'ਦਿਖਾਓ',
'hidetoc'         => 'ਛੁਪਾਓ',
'thisisdeleted'   => 'ਵੇਖੋ ਜਾਂ ਮੁੜ ਵਾਪਿਸ ਲਿਆਓ $1?',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'ਲੇਖ',
'nstab-user'      => 'ਮੈਂਬਰ ਦਾ ਪੰਨਾ',
'nstab-media'     => 'ਮੀਡੀਆ',
'nstab-special'   => 'ਖਾਸ',
'nstab-project'   => 'ਜਾਣਕਾਰੀ',
'nstab-image'     => 'ਤਸਵੀਰ',
'nstab-mediawiki' => 'ਸੰਦੇਸ਼',
'nstab-template'  => 'ਨਮੂਨਾ',
'nstab-help'      => 'ਮਦਦ',
'nstab-category'  => 'ਸ਼੍ਰੇਣੀ',

# Main script and global functions
'nosuchaction'      => 'ਅਜੇਹੀ ਕੋਈ ਕਿਰਿਆ ਨਹੀਂ ਹੈ',
'nosuchactiontext'  => 'URL ਦੁਵਾਰਾ ਕੀਤੀ ਗਈ ਕਿਰਿਆ (action) ਤੋਂ ਵਿਕਿ ਸੋਫ਼ਟਵੇਅਰ ਜਾਣੂ ਨਹੀਂ ਹੈ',
'nosuchspecialpage' => 'ਅਜੇਹਾ ਕੋਈ ਖਾਸ ਪੰਨਾ ਨਹੀਂ ਹੈ',
'nospecialpagetext' => 'ਤੁਸੀਂ ਇੱਕ ਖਾਸ ਪੰਨੇ ਦੀ ਮੰਗ ਕੀਤੀ ਹੈ ਜਿਸ ਤੋਂ ਵਿਕਿ ਸੋਫ਼ਟਵੇਅਰ ਜਾਣੂ ਨਹੀਂ ਹੈ',

# General errors
'error'           => 'ਗਲਤੀ',
'databaseerror'   => 'ਡਾਟਾਬੇਸ ਨਾਲ ਸੰਬੰਧਤ ਗਲਤੀ',
'dberrortext'     => "ਡਾਟਾਬੇਸ ਪੁੱਛਗਿੱਛ ਦੀ ਵਾਕ-ਰਚਨਾ ਵਿਚ ਕੋਈ ਗਲਤੀ ਹੋ ਗਈ ਹੈ।
ਇਹ ਕਿਸੇ ਖੋਜ ਬਾਰੇ ਗਲਤ ਪੁੱਛਗਿੱਛ ਦੇ ਕਾਰਨ ਹੋ ਸਕਦਾ ਹੈ($5 ਦੇਖੋ),
ਜਾਂ ਸ਼ਾਇਦ ਸੌਫ਼ਟਵੇਅਰ ਵਿਚ ਕੋਈ ਖ਼ਰਾਬੀ ਹੋ ਸਕਦੀ ਹੈ।
ਪਿਛਲੀ ਵਾਰ ਕੋਸ਼ਿਸ਼ ਕੀਤੀ ਗਈ ਡਾਟਾਬੇਸ ਪੁੱਛਗਿੱਛ ਇਹ ਸੀ:
<blockquote><tt>$1</tt></blockquote>
'<tt>$2</tt>'ਇਸ ਫ਼ੰਕਸ਼ਨ ਦੇ ਵਿਚੋਂ।
MySQL ਨੇ '<tt>$3: $4</tt>'ਗਲਤੀ ਦਿਖਾਈ।",
'dberrortextcl'   => "ਡਾਟਾਬੇਸ ਪੁੱਛਗਿੱਛ ਦੀ ਵਾਕ-ਰਚਨਾ ਵਿਚ ਕੋਈ ਗਲਤੀ ਹੋ ਗਈ ਹੈ।
ਪਿਛਲੀ ਵਾਰ ਕੋਸ਼ਿਸ਼ ਕੀਤੀ ਗਈ ਡਾਟਾਬੇਸ ਪੁੱਛਗਿੱਛ ਇਹ ਸੀ:
<blockquote><tt>$1</tt></blockquote>
'$2'ਇਸ ਫ਼ੰਕਸ਼ਨ ਦੇ ਵਿਚੋਂ।
MySQL ਨੇ '$3: $4'ਗਲਤੀ ਦਿਖਾਈ।",
'noconnect'       => 'ਮਾਫ਼ ਕਰਨਾ! ਵਿਕਿ ਨੂੰ ਕੁਝ ਤਕਨੀਕੀ ਮੁਸ਼ਕਲਾਂ ਦਾ ਸਾਹਮਣਾ ਕਰਨਾ ਪੈ ਰਿਹਾ ਹੈ, ਅਤੇ ਇਹ ਡਾਟਾਬੇਸ ਸਰਵਰ ਨਾਲ ਸੰਪਰਕ ਨਹੀਂ ਬਣਾ ਸਕਦਾ ਹੈ। <br />$1',
'nodb'            => 'ਡਾਟਾਬੇਸ $1 ਨੂੰ ਚੁਣ ਨਹੀਂ ਸਕਿਆ',
'internalerror'   => 'ਅੰਦਰੂਨੀ ਗਲਤੀ',
'filecopyerror'   => "ਫ਼ਾਈਲ '$1' ਨੂੰ ਫ਼ਾਈਲ '$2' ਤੇ ਨਕਲ ਨਹੀਂ ਕੀਤਾ ਜਾ ਸਕਿਆ",
'filerenameerror' => "ਫ਼ਾਈਲ '$1' ਦਾ ਨਾਮ '$2' ਨਹੀਂ ਕੀਤਾ ਜਾ ਸੱਕਿਆ",
'filedeleteerror' => "ਫ਼ਾਈਲ '$1' ਨੂੰ ਨਹੀਂ ਹਟਾਇਆ ਜਾ ਸੱਕਿਆ",
'filenotfound'    => "ਫ਼ਾਈਲ '$1' ਨਹੀਂ ਲੱਭੀ ਜਾ ਸਕੀ",
'badarticleerror' => 'ਇਹ ਕਿਰਿਆ ਇਸ ਪੰਨੇ ਤੇ ਸੰਪੰਨ ਨਹੀਂ ਕੀਤੀ ਜਾ ਸਕਦੀ',
'cannotdelete'    => 'ਪੰਨੇ ਜਾਂ ਤਸਵੀਰ ਨੂੰ ਨਹੀਂ ਹਟਾ ਸੱਕਿਆ ਗਿਆ (ਇਹ ਸ਼ਾਇਦ ਪਿਹਲਾਂ ਹੀ ਕਿਸੇ ਦੁਆਰਾ ਹਟਾ ਦਿੱਤਾ ਗਿਆ ਹੈ)',
'badtitle'        => 'ਗਲਤ ਸਿਰਲੇਖ',
'viewsource'      => 'ਸ੍ਰੋਤ ਦੇਖੋ',

# Login and logout pages
'logouttitle'                => 'ਮੈਂਬਰ ਲਾਗ ਆਊਟ',
'logouttext'                 => 'ਹੁਣ ਤੁਸੀਂ ਲਾਗ ਆਊਟ ਹੋ ਚੁੱਕੇ ਹੋ। ਹੁਣ ਤੁਸੀਂ ਅਗਿਆਤ ਰੂਪ ਵਿੱਚ
 {{SITENAME}} ਦੀ ਵਰਤੋਂ ਕਰ ਸਕਦੇ ਹੋ ਜਾਂ ਓਹੀ ਮੈਂਬਰ
ਜਾਂ ਕਿਸੇ ਹੋਰ ਮੈਂਬਰ ਦੇ ਰੂਪ ਵਿੱਚ ਲਾਗ ਇਨ ਕਰ ਸਕਦੇ ਹੋ।
Note that some pages may continue to be displayed as if you were
still logged in, until you clear your browser cache\n',
'welcomecreation'            => '== ਜੀ ਆਇਆਂ ਨੂੰ, $1! ==

ਤੁਹਾਡਾ ਖਾਤਾ ਬਣਾ ਦਿੱਤਾ ਗਿਆ ਹੈ, ਆਪਣੀਆਂ {{SITENAME}} ਪਸੰਦਾਂ (preferences) ਨੂੰ ਬਦਲਣਾ ਨਾ ਭੁਲਣਾ।',
'loginpagetitle'             => 'ਮੈਂਬਰ ਲਾਗ ਇਨ',
'yourname'                   => 'ਤੁਹਾਡਾ ਨਾਮ',
'yourpassword'               => 'ਤੁਹਾਡਾ ਪਾਸਵਰਡ',
'yourpasswordagain'          => 'ਪਾਸਵਰਡ ਦੌਬਾਰਾ ਲਿੱਖੋ',
'remembermypassword'         => 'ਅੱਗੋਂ ਲਈ ਮੇਰਾ ਪਾਸਵਰਡ ਯਾਦ ਰੱਖੋ',
'loginproblem'               => '<b>ਤੁਹਾਡੇ ਲਾਗ ਇਨ ਵਿੱਚ ਕੁਝ ਸਮੱਸਿਆ ਹੈ,</b><br />ਦੌਬਾਰਾ ਕੋਸ਼ਿਸ਼ ਕਰੋ!',
'login'                      => 'ਲਾਗ ਇਨ',
'loginprompt'                => '{{SITENAME}} ਵਿਚ ਲਾੱਗ-ਇਨ ਕਰਨ ਲਈ ਤੁਹਾਡੀਆਂ cookies enabled ਹੋਣੀਆਂ ਚਾਹੀਦੀਆਂ ਹਨ।',
'userlogin'                  => 'ਨਵਾਂ ਖਾਤਾ ਬਨਾਓ ਜਾਂ ਲਾਗ ਇਨ ਕਰੋ',
'logout'                     => 'ਲਾਗ ਆਊਟ',
'userlogout'                 => 'ਲਾਗ ਆਊਟ',
'notloggedin'                => 'ਲਾਗ ਇਨ ਨਹੀਂ ਹੈ',
'createaccount'              => 'ਨਵਾਂ ਖਾਤਾ ਬਣਾਓ',
'createaccountmail'          => 'ਈ-ਮੇਲ (email) ਰਾਹੀਂ',
'badretype'                  => 'ਪਾਸਵਰਡ ਇਕ ਦੂਜੇ ਨਾਲ ਮੇਲ ਨਹੀਂ ਖਾਂਦੇ',
'userexists'                 => 'ਇਹ ਨਾਮ ਪਿਹਲਾਂ ਹੀ ਵਰਤੋਂ ਵਿੱਚ ਹੈ, ਕਿਰਪਾ ਕਰਕੇ ਕਿਸੇ ਹੋਰ ਨਾਮ ਦੀ ਵਰਤੋਂ ਕਰੋ',
'youremail'                  => '* ਤੁਹਾਡਾ ਈ-ਮੇਲ (email)',
'yourrealname'               => '* ਤੁਹਾਡਾ ਨਾਮ',
'yourlanguage'               => 'Interface language',
'yourvariant'                => 'Language variant',
'yournick'                   => 'ਤੁਹਾਡਾ ਉਪਨਾਮ (ਦਸਤਖ਼ਤ ਲਈ)',
'prefs-help-realname'        => '* <strong>ਅਸਲੀ ਨਾਮ</strong> (ਗੈਰ-ਜ਼ਰੂਰੀ): ਜੇ ਤੁਸੀਂ ਭਰਦੇ ਹੋ, ਤਾਂ ਤੁਹਾਡੇ ਕੰਮ ਨੂੰ attribution ਦੇਣ ਲਈ ਵਰਤਿਆ ਜਾਵੇਗਾ<br />',
'loginerror'                 => 'ਲਾਗ ਇਨ ਵਿੱਚ ਗਲਤੀ',
'prefs-help-email'           => '* <strong>ਈ-ਮੇਲ</strong> (ਗੈਰ-ਜ਼ਰੂਰੀ): ਜੇ ਭਰਦੇ ਹੋ ਤਾਂ ਬਿਨਾਂ ਤੁਹਾਡੇ ਅਸਲੀ ਈ-ਮੇਲ ਨੂੰ ਜਾਣੇ, ਇਸ website ਦੁਆਰਾ ਲੋਗ ਤੁਹਾਨੂੰ ਸੰਪੰਰਕ ਕਰ ਸਕਦੇ ਹਨ
ਅਤੇ ਜੇ ਕਦੀ ਤੁਸੀਂ ਆਪਣਾ ਪਾਸਵਰਡ ਭੁੱਲ ਜਾਓ, ਤਾਂ ਇਸ ਈ-ਮੇਲ ਤੇ ਤੁਹਾਨੂੰ ਨਵਾਂ ਪਾਸਵਰਡ ਭੇਜਿਆ ਜਾ ਸਕਦਾ ਹੈ.',
'noname'                     => 'ਤੁਸੀਂ ਮੈਂਬਰ ਦਾ ਨਾਮ ਸਹੀ ਨਹੀਂ ਦੱਸਿਆ.',
'loginsuccesstitle'          => 'ਲਾਗ ਇਨ ਕਾਮਯਾਬ ਰਿਹਾ',
'loginsuccess'               => "ਹੁਣ ਤੁਸੀਂ {{SITENAME}} ਵਿੱਚ '$1' ਨਾਮ ਨਾਲ ਲਾਗ ਇਨ ਹੋ",
'nosuchuser'                 => "'$1' ਨਾਮ ਦਾ ਕੋਈ ਮੈਂਬਰ ਨਹੀਂ ਹੈ.
ਕਿਰਪਾ ਕਰਕੇ ਨਾਮ ਸਹੀ ਲਿੱਖੋ ਜਾਂ ਨੀਚੇ ਦਿੱਤੇ ਗਏ ਫ਼ਾਰਮ ਦੀ ਵਰਤੋਂ ਕਰਕੇ ਇੱਕ ਨਵਾਂ ਖਾਤਾ ਬਣਾ ਲਓ.",
'wrongpassword'              => 'ਦਿੱਤਾ ਗਿਆ ਪਾਸਵਰਡ ਗਲਤ ਹੈ, ਕਿਰਪਾ ਕਰਕੇ ਦੋਬਾਰਾ ਯਤਨ ਕਰੋ',
'mailmypassword'             => 'ਮੈਨੂੰ ਇੱਕ ਨਵਾਂ ਪਾਸਵਰਡ ਈ-ਮੇਲ ਰਾਹੀਂ ਭੇਜ ਦਿਓ',
'passwordremindertitle'      => '{{SITENAME}} ਵਲੋਂ ਪਾਸਵਰਡ ਯਾਦਦਹਾਣੀ-ਪੱਤ੍ਰ (Password Reminder from {{SITENAME}})',
'passwordremindertext'       => "ਕਿਸੇ ਨੇ (ਸ਼ਾਯਿਦ ਤੁਸੀਂ ਹੀ, $1 IP address ਤੋਂ)
 {{SITENAME}} ਲਾਗ ਇਨ ਦਾ ਨਵਾਂ ਪਾਸਵਰਡ ਭੇਜਣ ਦੀ ਮੰਗ ਕੀਤੀ ਸੀ.
ਮੈਂਬਰ '$1' ਦਾ ਹੁਣ ਨਵਾਂ ਪਾਸਵਰਡ '$3' ਹੈ.
ਕਿਰਪਾ ਕਰਕੇ {{SITENAME}} ਵਿੱਚ ਲਾਗ ਇਨ ਕਰਕੇ ਹੁਣੇ ਆਪਣਾ ਪਾਸਵਰਡ ਬਦਲ ਲਓ.
<br /><br />
Someone (probably you, from IP address $1)
requested that we send you a new {{SITENAME}} login password.
The password for user '$2' is now '$3'.
You should log in and change your password now.",
'noemail'                    => "ਮੈਂਬਰ '$1' ਲਈ ਕੋਈ ਈ-ਮੇਲ ਅਡ੍ਰੈੱਸ ਨਹੀਂ ਹੈ",
'passwordsent'               => "'$1' ਮੈਂਬਰ ਦੇ ਈ-ਮੇਲ ਅਡ੍ਰੈੱਸ ਤੇ ਇੱਕ ਨਵਾਂ ਪਾਸਵਰਡ ਭੇਜ ਦਿੱਤਾ ਗਿਆ ਹੈ.
ਪਾਸਵਰਡ ਮਿਲੱਣ ਤੋਂ ਬਾਅਦ ਕਿਰਪਾ ਲਾਗ ਇਨ ਜ਼ਰੂਰ ਕਰੋ.",
'mailerror'                  => 'ਮੇਲ (mail) $1 ਭੇਜਣ ਵਿੱਚ ਸਮੱਸਿਆ ਆ ਗਈ ਹੈ',
'acct_creation_throttle_hit' => 'ਮਾਫ਼ੀ ਚਾਹੁੰਦੇ ਹਾਂ, ਤੁਸੀਂ ਪਿਹਲਾਂ ਹੀ $1 ਖਾਤੇ ਬਣਾ ਚੁੱਕੇ ਹੋ. ਤੁਸੀਂ ਇਸਤੋਂ ਜ਼ਿਆਦਾ ਨਹੀਂ ਬਣਾ ਸੱਕਦੇ ਹੋ',

# Edit page toolbar
'bold_sample'   => 'ਬੋਲਡ ਅੱਖਰ',
'bold_tip'      => 'ਬੋਲਡ ਅੱਖਰ',
'italic_sample' => 'ਇਟੈਲਿਕ ਅੱਖਰ',
'italic_tip'    => 'ਇਟੈਲਿਕ ਅੱਖਰ',
'link_sample'   => 'Link title',
'link_tip'      => 'ਅੰਦਰੂਨੀ ਕੜੀ',
'extlink_tip'   => 'ਬਾਹਰੀ ਕੜੀ (ਪਹਿਲਾਂ http:// ਲਗਾਉਣਾ ਯਾਦ ਰੱਖੋ)',
'math_tip'      => 'ਗਣਿਤ ਦਾ ਫਾਰਮੂਲਾ (LaTeX)',

# Edit pages
'summary'                => 'ਸਾਰ',
'subject'                => 'ਵਿਸ਼ਾ',
'minoredit'              => 'ਇਹ ਇਕ ਮਾਮੂਲੀ ਬਦਲਾਵ ਹੈ',
'watchthis'              => 'ਇਸ ਪੰਨੇ ਤੇ ਨਜ਼ਰ ਰਖੋ',
'savearticle'            => 'ਪੱਕਾ ਕਰ ਦਿਓ',
'preview'                => 'ਝਲਕ',
'showpreview'            => 'ਝਲਕ ਦਿਖਾਓ',
'blockedtitle'           => 'ਮੈਂਬਰ ਤੇ ਰੋਕ ਲਗਾ ਦਿੱਤੀ ਗਈ ਹੈ',
'blockedtext'            => "ਤੁਹਾਡੇ ਮੈਂਬਰ ਨਾਮ ਜਾਂ IP address ਉੱਤੇ $1 ਦੁਆਰਾ ਰੋਕ ਲਗਾ ਦਿੱਤੀ ਗਈ ਹੈ.
ਕਾਰਣ ਹੈ:<br />''$2''<p>ਇਸ ਰੋਕ ਦੇ ਬਾਰੇ ਵਿੱਚ ਚਰਚਾ ਕਰਨ ਲਈ
$1 ਜਾਂ ਕਿਸੇ ਵੀ ਹੋਰ [[Project:ਪ੍ਰਸ਼ਾਸਕ]]
ਨੂੰ ਸੰਪੰਰਕ ਕਰੋ. ਧਿਆਨ ਦਿਓ ਕਿ ਤੁਸੀਂ ਓਹਨਾਂ ਚਿਰ 'ਇਸ ਮੈਂਬਰ ਨੂੰ ਈ-ਮੇਲ ਕਰੋ' ਸੁਵੀਧਾ ਦੀ ਵਰਤੋਂ
ਨਹੀਂ ਕਰ ਸਕਦੇ, ਜਦੋਂ ਤੱਕ ਕਿ ਤੁਸੀਂ [[Special:Preferences|preferences]]
ਵਿੱਚ ਆਪਣਾ ਈ-ਮੇਲ ਨਹੀਂ ਦਿੰਦੇ. ਤੁਹਾਡਾ IP address ਹੈ $3.
ਕਿਰਪਾ ਕਰਕੇ ਪੁਛ-ਗਿੱਛ ਕਰਦੇ ਵਕਤ ਇਸ IP address ਦੀ ਵਰਤੋਂ ਜ਼ਰੂਰ ਕਰੋ.",
'whitelistedittitle'     => 'ਬਦਲਾਵ ਕਰਨ ਲਈ ਲਾੱਗ-ਇਨ ਹੋਣਾ ਜ਼ਰੂਰੀ ਹੈ',
'whitelistedittext'      => 'ਤੁਹਾਨੂੰ ਲੇਖਾਂ ਵਿੱਚ ਬਦਲਾਵ ਕਰਨ ਲਈ [[Special:Userlogin|login]] ਕਰਨਾ ਜ਼ਰੂਰੀ ਹੈ',
'whitelistreadtitle'     => 'ਪੜ੍ਹਨ ਲਈ ਲਾੱਗ-ਇਨ ਕਰਨਾ ਜ਼ਰੂਰੀ ਹੈ',
'whitelistreadtext'      => 'ਤੁਹਾਨੂੰ ਲੇਖ ਪੜਨ ਲਈ [[Special:Userlogin|login]] ਕਰਨਾ ਜ਼ਰੂਰੀ ਹੈ',
'whitelistacctitle'      => 'ਤੁਹਾਨੂੰ ਖਾਤਾ ਬਨਾਓਣ ਦੀ ਅਨੁਮਤੀ ਨਹੀਂ ਹੈ',
'whitelistacctext'       => 'ਇਸ ਵਿਕਿ ਵਿੱਚ ਖਾਤਾ ਬਨਾਓਣ ਲਈ ਤੁਹਾਨੂੰ [[Special:Userlogin|login]] ਕਰਨਾ ਜ਼ਰੂਰੀ ਹੈ ਅਤੇ ਨਾਲ ਹੀ ਉਪ੍ਯੁਕਤ ਅਨੁਮਤੀ ਵੀ ਹੋਣੀ ਚਾਹੀਦੀ ਹੈ',
'loginreqtitle'          => 'ਲਾਗ ਇਨ ਜ਼ਰੂਰੀ ਹੈ',
'loginreqpagetext'       => 'ਬਾਕੀ ਦੇ ਲੇਖ ਵੇਖਣ ਲਈ $1 ਕਰਨਾ ਜ਼ਰੂਰੀ ਹੈ',
'accmailtitle'           => 'ਪਾਸਵਰਡ ਭੇਜ ਦਿੱਤਾ ਗਿਆ ਹੈ',
'accmailtext'            => "'$1' ਦਾ ਪਾਸਵਰਡ $2 ਨੂੰ ਭੇਜ ਦਿੱਤਾ ਗਿਆ ਹੈ",
'newarticle'             => '(ਨਵਾਂ ਲੇਖ)',
'newarticletext'         => "
ਤੁਸੀਂ ਅਜੇਹੇ ਪੰਨੇ ਤੇ ਪੁੱਜ ਗਏ ਹੋ ਜੋ ਅਜੇ ਲਿੱਖਿਆ ਨਹੀਂ ਗਿਆ ਹੈ.
ਜੇ ਤੁਸੀਂ ਇਸ ਪੰਨੇ ਨੂੰ ਬਨਾਣਾ ਚਾਹੁੰਦੇ ਹੋ, ਤਾਂ ਹੇਠਾਂ ਦਿੱਤੀ
ਥਾਂ ਵਿੱਚ ਲਿੱਖਣਾ ਸ਼ੁਰੂ ਕਰ ਦਿਓ(ਹੋਰ ਜਾਣਕਾਰੀ ਲਈ ਵੇਖੋ [[{{MediaWiki:helppage}}|help page]]).
ਜੇ ਤੁਸੀਂ ਗਲਤੀ ਨਾਲ ਇੱਥੇ ਆ ਗਏ ਹੋ, ਤਾਂ ਆਪਣੇ browser ਦਾ '''back''' button ਦਬਾਓ",
'noarticletext'          => '(ਅਜੇ ਇਹ ਪੰਨਾ ਖਾਲੀ ਹੈ)',
'usercssjsyoucanpreview' => "<strong>ਨਸੀਹਤ:</strong>CSS/JS ਵਿੱਚ ਕੀਤੇ ਗਏ ਬਦਲਾਵ  ਨੂੰ ਪੱਕਾ ਕਰਨ ਤੋਂ ਪਿਹਲਾਂ, 'ਝਲਕ ਦਿਖਾਓ' button ਦਾ ਇਸਤੇਮਾਲ ਕੀਤਾ ਜਾ ਸਕਦਾ ਹੈ",
'usercsspreview'         => "'''ਯਾਦ ਰੱਖੋ ਕਿ ਤੁਸੀਂ ਆਪਣੀ CSS ਦੀ ਸਿਰਫ਼ ਝਲਕ ਵੇਖ ਰਹੇ ਹੋ, ਅਜੇ ਇਸਨੂੰ ਪੱਕਾ ਨਹੀਂ ਕੀਤਾ ਗਿਆ ਹੈ!'''",
'userjspreview'          => "'''ਯਾਦ ਰੱਖੋ ਕਿ ਤੁਸੀਂ ਆਪਣੀ javascript ਦੀ ਸਿਰਫ਼ ਝਲਕ ਵੇਖ ਰਹੇ ਹੋ, ਅਜੇ ਇਸਨੂੰ ਪੱਕਾ ਨਹੀਂ ਕੀਤਾ ਗਿਆ ਹੈ!'''",
'updated'                => '(ਅੱਪਡੇਟ (update) ਹੋ ਗਿਆ ਹੈ)',
'note'                   => '<strong>ਧਿਆਨ ਦਿਓ:</strong>',
'previewnote'            => 'ਯਾਦ ਰੱਖੋ ਕਿ ਇਹ ਸਿਰਫ਼ ਇਕ ਝਲਕ ਹੈ, ਅਜੇ ਇਸਨੂੰ ਪੱਕਾ ਨਹੀਂ ਕੀਤਾ ਗਿਆ ਹੈ!',
'editing'                => 'ਬਦਲ ਰਹੇ ਹਾਂ: $1',
'editinguser'            => 'ਬਦਲ ਰਹੇ ਹਾਂ: $1',
'editconflict'           => 'ਬਦਲਾਵ ਮੱਤਭੇਦ: $1',
'yourdiff'               => 'ਅੰਤਰ (Differences)',

# History pages
'revhistory'  => 'ਸੋਧ ਦਾ ਇਤਿਹਾਸ',
'nohistory'   => 'ਇਸ ਪੰਨੇ ਲਈ ਤਬਦੀਲ਼ੀ ਦਾ ਕੋਈ ੲਤਿਹਾਸ ਨਹੀਂ ਹੈ.',
'revnotfound' => 'ਸੋਧ ਨਹੀਂ ਮਿਲੀ',
'loadhist'    => 'ਪੰਨੇ ਦਾ ਇਤਿਹਾਸ ਲੋਡ ਹੋ ਰਿਹਾ ਹੈ',
'currentrev'  => 'ਮੌਜੂਦਾ ਸੰਸ਼ੋਧਨ',
'cur'         => 'ਮੌਜੂਦਾ',
'next'        => 'ਅਗਲਾ',
'last'        => 'ਪਿਛਲਾ',
'orig'        => 'ਅਸਲ',

# Diffs
'editcurrent'               => 'ਇਸ ਪੰਨੇ ਦੇ ਮੌਜੂਦਾ ਰੁਪਾਂਤਰ ਵਿਚ ਤਬਦੀਲੀ ਕਰੋ',
'selectnewerversionfordiff' => 'ਆਪਸ ਵਿਚ ਮਿਲਾਉਣ ਲਈ ਨਵਾਂ ਰੁਪਾਂਤਰ ਚੁਣੋ',
'selectolderversionfordiff' => 'ਆਪਸ ਵਿਚ ਮਿਲਾਉਣ ਲਈ ਪੁਰਾਣਾ ਰੁਪਾਂਤਰ ਚੁਣੋ',
'compareselectedversions'   => 'ਚੁਣੇ ਹੋਏ ਰਪਾਂਤਰਾਂ ਨੂੰ ਆਪਸ ਵਿਚ ਮਿਲਾਓ',

# Search results
'searchdisabled' => '<p>ਮੁਆਫ਼ੀ ਚਾਹੁੰਦੇ ਹਾਂ! Full text search, performance reasons ਕਰਕੇ ਕੁੱਝ ਦੇਰ ਲਈ ਬੰਦ ਕਰ ਦਿੱਤੀ ਗਈ ਹੈ. ਇਸ ਦਰਮਿਆਨ, ਚਾਹੋ ਤਾਂ ਤੁਸੀਂ Google search ਦੀ ਵਰਤੋਂ ਕਰ ਸਕਦੇ ਹੋ, ਜੋ ਕਿ ਹੋ ਸਕਦਾ ਹੈ ਪੂਰਾਣੀ ਹੋ ਚੁੱਕੀ ਹੋਵੇ</p>',

# Preferences page
'qbsettings-none'         => 'ਕੋਈ ਨਹੀਂ',
'qbsettings-fixedleft'    => 'ਸਥਿਰ ਖੱਬੇ',
'qbsettings-fixedright'   => 'ਸਥਿਰ ਸੱਜਾ',
'qbsettings-floatingleft' => 'ਤੈਰਦਾ ਖੱਬੇ',

# Recent changes
'recentchanges' => 'ਹਾਲ ਵਿੱਚ ਹੋਏ ਬਦਲਾਵ',
'rcnote'        => 'ਪਿੱਛਲੇ <strong>$2</strong> ਦਿਨਾਂ ਵਿੱਚ ਹੋਏ <strong>$1</strong> ਬਦਲਾਵ:',
'rclistfrom'    => '$1 ਤੋਂ ਸ਼ੁਰੂ ਹੋਣ ਵਾਲੇ ਨਵੇਂ ਬਦਲਾਵ ਦਿਖਾਓ',
'rclinks'       => 'ਪਿੱਛਲੇ $2 ਦਿਨਾਂ ਵਿੱਚ ਹੋਏ $1 ਬਦਲਾਵ ਦਿਖਾਓ<br />$3',
'hide'          => 'ਛੁਪਾਓ',
'show'          => 'ਦਿਖਾਓ',

# Miscellaneous special pages
'nbytes' => '$1 ਬਾਈਟ',

# What links here
'whatlinkshere' => 'ਪੰਨੇ ਜੋ ਇੱਥੇ ਜੁੜਦੇ ਹਨ',

# Tooltip help for the actions
'tooltip-search'    => 'ਇਸ ਵਿਕਿ ਵਿੱਚ ਲੱਭੋ',
'tooltip-minoredit' => 'ਮਾਮੂਲੀ ਬਦਲਾਵ ਦੀ ਨਿਸ਼ਾਨੀ ਲਗਾਓ (Mark this as a minor edit)',
'tooltip-save'      => 'ਕੀਤੇ ਗਏ ਬਦਲਾਵ ਪੱਕੇ ਕਰੋ',
'tooltip-preview'   => 'ਕੀਤੇ ਗਏ ਬਦਲਾਵਾਂ ਦੀ ਝਲਕ ਵੇਖੋ, ਕਿਰਪਾ ਕਰਕੇ ਪੱਕਾ ਕਰਨ ਤੋਂ ਪਿਹਲਾਂ ਇਸਦੀ ਵਰਤੋਂ ਜ਼ਰੂਰ ਕਰੋ!',
'tooltip-watch'     => 'ਇਸ ਪੰਨੇ ਨੂੰ ਆਪਣੀ watchlist ਵਿੱਚ ਜਮਾਂ ਕਰੋ',

# Attribution
'lastmodifiedatby' => 'ਇਹ ਪੰਨਾ ਅਖੀਰਲੀ ਵਾਰ $2, $1 ਨੂੰ $3 ਦੁਆਰਾ ਬਦਲਿਆ ਗਿਆ ਸੀ', # $1 date, $2 time, $3 user
'and'              => 'ਅਤੇ',
'othercontribs'    => '$1 ਦੁਆਰਾ ਕੰਮ ਤੇ ਅਧਾਰਤ।',

# Info page
'infosubtitle' => 'ਪੰਨੇ ਸੰਬੰਧੀ ਸੂਚਨਾ',
'numedits'     => 'ਤਬਦੀਲੀਆਂ ਦੀ ਗਿਣਤੀ (ਲੇਖ ਵਿਚਾਲੇ):',
'numtalkedits' => 'ਤਬਦੀਲੀਆਂ ਦੀ ਗਿਣਤੀ (ਚਰਚਾ-ਪੰਨੇ ਵਿਚਾਲੇ):',

);


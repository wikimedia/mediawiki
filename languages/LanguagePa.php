<?php
# Punjabi (Gurmukhi)
# This file is dual-licensed under GFDL and GPL.
#
# See: http://bugzilla.wikimedia.org/show_bug.cgi?id=1478

require_once('LanguageUtf8.php');

/* private */ $wgNamespaceNamesPa = array(
	NS_MEDIA		=> 'ਮੀਡੀਆ',
	NS_SPECIAL		=> 'ਖਾਸ',
	NS_MAIN			=> '',
	NS_TALK			=> 'ਚਰਚਾ',
	NS_USER			=> 'ਮੈਂਬਰ',
	NS_USER_TALK		=> 'ਮੈਂਬਰ_ਚਰਚਾ',
	NS_PROJECT		=> $wgMetaNamespace, /* Wikipedia?: ਵਿਕਿਪੀਡਿਆ */
	NS_PROJECT_TALK		=> $wgMetaNamespace . '_ਚਰਚਾ',
	NS_IMAGE		=> 'ਤਸਵੀਰ',
	NS_IMAGE_TALK		=> 'ਤਸਵੀਰ_ਚਰਚਾ',
	NS_MEDIAWIKI		=> 'ਮੀਡੀਆਵਿਕਿ',
	NS_MEDIAWIKI_TALK	=> 'ਮੀਡੀਆਵਿਕਿ_ਚਰਚਾ',
	NS_TEMPLATE		=> 'ਨਮੂਨਾ',
	NS_TEMPLATE_TALK	=> 'ਨਮੂਨਾ_ਚਰਚਾ',
	NS_HELP			=> 'ਮਦਦ',
	NS_HELP_TALK		=> 'ਮਦਦ_ਚਰਚਾ',
	NS_CATEGORY		=> 'ਸ਼੍ਰੇਣੀ',
	NS_CATEGORY_TALK	=> 'ਸ਼੍ਰੇਣੀ_ਚਰਚਾ'
);

/* private */ $wgQuickbarSettingsPa = array(
	'ਕੋਈ ਨਹੀਂ', 'ਸਥਿਰ ਖੱਬੇ', 'ਸਥਿਰ ਸੱਜਾ', 'ਤੈਰਦਾ ਖੱਬੇ'
);

/* private */ $wgSkinNamesPa = array(
	'standard'      => 'ਮਿਆਰੀ',
) + $wgSkinNamesEn;

#-------------------------------------------------------------------
# Default messages
#-------------------------------------------------------------------
# Allowed characters in keys are: A-Z, a-z, 0-9, underscore (_) and
# hyphen (-). If you need more characters, you may be able to change
# the regex in MagicWord::initRegex

/* private */ $wgAllMessagesPa = array(
# Bits of text used by many pages:
#

# Dates
'sunday'		=> 'ਐਤਵਾਰ',
'monday'		=> 'ਸੋਮਵਾਰ',
'tuesday'		=> 'ਮੰਗਲਵਾਰ',
'wednesday'		=> 'ਬੁਧਵਾਰ',
'thursday'		=> 'ਵੀਰਵਾਰ',
'friday'		=> 'ਸ਼ੁੱਕਰਵਾਰ',
'saturday'		=> 'ਸ਼ਨੀਚਰਵਾਰ',
'january'		=> 'ਜਨਵਰੀ',
'february'		=> 'ਫ਼ਰਵਰੀ',
'march'			=> 'ਮਾਰਚ',
'april'			=> 'ਅਪ੍ਰੈਲ',
'may_long'		=> 'ਮਈ',
'june'			=> 'ਜੂਨ',
'july'			=> 'ਜੁਲਾਈ',
'august'		=> 'ਅਗਸਤ',
'september'		=> 'ਸਤੰਬਰ',
'october'		=> 'ਅਕਤੂਬਰ',
'november'		=> 'ਨਵੰਬਰ',
'december'		=> 'ਦਸੰਬਰ',
'jan'			=> 'ਜਨਵਰੀ',
'feb'			=> 'ਫ਼ਰਵਰੀ',
'mar'			=> 'ਮਾਰਚ',
'apr'			=> 'ਅਪ੍ਰੈਲ',
'may'			=> 'ਮਈ',
'jun'			=> 'ਜੂਨ',
'jul'			=> 'ਜੁਲਾਈ',
'aug'			=> 'ਅਗਸਤ',
'sep'			=> 'ਸਤੰਬਰ',
'oct'			=> 'ਅਕਤੂਬਰ',
'nov'			=> 'ਨਵੰਬਰ',
'dec'			=> 'ਦਸੰਬਰ',

'categories'	=> 'ਸ਼੍ਰੇਣੀਆਂ',
'category'		=> 'ਸ਼੍ਰੇਣੀ',
'category_header'	=> 'ਸ਼੍ਰੇਣੀ \'$1\' ਵਾਲੇ ਲੇਖ',
'subcategories'	=> 'ਉਪਸ਼੍ਰੇਣੀਆਂ',

'linktrail'		=> '/^([ਁਂਃਅਆਇਈਉਊਏਐਓਔਕਖਗਘਙਚਛਜਝਞਟਠਡਢਣਤਥਦਧਨਪਫਬਭਮਯਰਲਲ਼ਵਸ਼ਸਹ਼ਾਿੀੁੂੇੈੋੌ੍ਖ਼ਗ਼ਜ਼ੜਫ਼ੰੱੲੳa-z]+)(.*)$/sDu',
'mainpage'		=> 'ਮੁੱਖ ਪੰਨਾ',
'mainpagetext'	=> 'ਵਿਕਿ ਸਾਫ਼ਟਵੇਅਰ ਚੰਗੀ ਤਰ੍ਹਾਂ ਇੰਸਟਾਲ ਹੋ ਗਿਆ ਹੈ',

# NOTE: To turn off 'Community portal' in the title links,
# set 'portal' => '-'

'portal'		=> 'ਸਮੂਹ ਦ੍ਵਾਰ',
'portal-url'		=> 'Project:ਸਮੂਹ ਦ੍ਵਾਰ',
'about'		=> 'ਜਾਣਕਾਰੀ',
'aboutsite'		=> '{{SITENAME}} ਬਾਰੇ',
'aboutpage'		=> 'Project:ਜਾਣਕਾਰੀ',
'article'		=> 'ਵਿਸ਼ਾ-ਵਸਤੂ ਵਾਲਾ ਪੰਨਾ',
'help'		=> 'ਮਦਦ',
'helppage'		=> 'ਮਦਦ:ਵਿਸ਼ਾ-ਵਸਤੂ',
'wikititlesuffix'	=> '{{SITENAME}}',
'bugreports'		=> 'ਖਾਮੀ ਸੂਚਨਾ',
'bugreportspage'	=> 'Project:ਖਾਮੀ_ਸੂਚਨਾ',
'sitesupport'		=> 'ਦਾਨ',
'sitesupport-url'	=> 'Project:ਦਾਨ',
'faq'			=> 'ਪ੍ਰਸ਼ਨਾਵਲੀ - FAQ',
'faqpage'		=> 'Project:ਪ੍ਰਸ਼ਨਾਵਲੀ',
'edithelp'		=> 'ਬਦਲਾਵ ਮਦਦ',
'newwindow'		=> '(ਨਵੀਂ window ਵਿੱਚ ਖੁੱਲੇਗਾ)',
'edithelppage'	=> 'ਮਦਦ:ਬਦਲਾਵ',
'cancel'		=> 'ਰੱਦ ਕਰੋ',
'qbfind'		=> 'ਲੱਭੋ',
'qbbrowse'		=> 'ਵੇਖੋ - Browse',
'qbedit'		=> 'ਬਦਲੋ',
'qbpageoptions'	=> 'ਪੰਨਾ ਵਿਕਲਪ - Options',
'qbpageinfo'		=> 'ਪੰਨਾ ਜਾਣਕਾਰੀ',
'qbmyoptions'		=> 'ਮੇਰੇ ਵਿਕਲਪ',
'qbspecialpages'	=> 'ਖਾਸ ਪੰਨੇ',
'moredotdotdot'	=> 'ਹੋਰ...',
'mypage'		=> 'ਮੇਰਾ ਪੰਨਾ',
'mytalk'		=> 'ਮੇਰੀ ਚਰਚਾ',
'anontalk'		=> 'ਇੱਸ ਆਈ-ਪੀ (IP) ਦੀ ਚਰਚਾ',
'navigation'		=> 'ਨੈਵੀਗੇਸ਼ੱਨ',

# NOTE: To turn off 'Current Events' in the sidebar,
# set 'currentevents' => '-'
'currentevents'	=> '-',
/*'currentevents-url' => 'Current events',*/

'disclaimers'		=> 'ਡਿਸਕਲੇਮਰ',
'disclaimerpage'	=> 'Project:General_disclaimer',
'errorpagetitle'	=> 'ਗਲਤੀ',
'returnto'		=> 'ਵਾਪਿਸ ਪਰਤੋ: $1.',
'tagline'		=> '{{SITENAME}} ਤੋਂ',
'whatlinkshere'	=> 'ਪੰਨੇ ਜੋ ਇੱਥੇ ਜੁੜਦੇ ਹਨ',
'help'		=> 'ਮਦਦ',
'search'		=> 'ਖੋਜ',
'go'			=> 'ਜਾਓ',
'history'		=> 'ਪੁਰਾਣੇ ਆਵਰਤਣ',
'history_short'	=> 'ਇਤਿਹਾਸ',
'info_short'		=> 'ਸੂਚਨਾ',
'printableversion'	=> 'ਛਾਪਣ-ਯੋਗ ਆਵਰਤਣ',
'edit'		=> 'ਬਦਲੋ',
'editthispage'	=> 'ਇਸ ਪੰਨੇ ਨੂੰ ਬਦਲੋ',
'delete'		=> 'ਹਟਾਓ',
'deletethispage'	=> 'ਇਸ ਪੰਨੇ ਨੂੰ ਹਟਾਓ',
'undelete_short'	=> '$1 ਬਦਲਾਵ ਮੁੜ ਵਾਪਿਸ ਲਿਆਓ',
'undelete_short1'	=> '1 ਬਦਲਾਵ ਮੁੜ ਵਾਪਿਸ ਲਿਆਓ',
'protect'		=> 'ਰੱਖਿਆ ਕਰੋ',
'protectthispage'	=> 'ਇਸ ਪੰਨੇ ਦੀ ਰੱਖਿਆ ਕਰੋ',
'unprotect'		=> 'ਅਸੁਰੱਖਿਅਤ ਕਰੋ',
'unprotectthispage'	=> 'ਇਸ ਪੰਨੇ ਨੂੰ ਅਸੁਰੱਖਿਅਤ ਕਰੋ',
'newpage'		=> 'ਨਵਾਂ ਪੰਨਾ',
'talkpage'		=> 'ਇਸ ਪੰਨੇ ਤੇ ਚਰਚਾ ਕਰੋ',
'specialpage'		=> 'ਖ਼ਾਸ ਪੰਨਾ',
'personaltools'	=> 'ਨਿਜੀ ਔਜ਼ਾਰ',
'postcomment'		=> 'ਆਪਨੇ ਵਿਚਾਰ ਪੇਸ਼ ਕਰੋ',
'articlepage'		=> 'ਵਿਸ਼ਾ-ਵਸਤੂ ਵਾਲਾ ਪੰਨਾ ਵੇਖੋ',
'subjectpage'		=> 'ਵਿਸ਼ਾ ਵੇਖੋ',
'talk'		=> 'ਚਰਚਾ',
'toolbox'		=> 'ਔਜ਼ਾਰ-ਡੱਬਾ',
'userpage'		=> 'ਮੈਂਬਰ ਦਾ ਪੰਨਾ ਵੇਖੋ',
'wikipediapage'	=> 'ਪਰਿਯੋਜਨਾ (project) ਵਾਲਾ ਪੰਨਾ ਵੇਖੋ',
'imagepage'		=> 'ਤਸਵੀਰ ਵਾਲਾ ਪੰਨਾ ਵੇਖੋ',
'viewtalkpage'	=> 'ਚਰਚਾ ਵਾਲਾ ਪੰਨਾ ਵੇਖੋ ',
'otherlanguages'	=> 'ਬਾਕੀ ਭਾਸ਼ਾਵਾਂ',
'redirectedfrom'	=> '($1 ਤੋਂ ਭੇਜਿਆ ਗਿਆ ਹੈ)',
'lastmodified'	=> 'ਅਖੀਰਲਾ ਬਦਲਾਵ $1',
'viewcount'		=> 'ਇਹ ਪੰਨਾ $1 ਵਾਰ ਵੇਖਿਆ ਗਿਆ ਹੈ',
'copyright'		=> 'ਵਿਸ਼ਾ-ਵਸਤੂ $1 ਤਹਿਤ ਉਪਲੱਬਧ ਹੈ',
'poweredby'		=> '{{SITENAME}} is powered by [http://www.mediawiki.org/ MediaWiki], an open source wiki engine.',
'printsubtitle'	=> '({{SERVER}} ਤੋਂ)',
'protectedpage'	=> 'ਸੁਰੱਖਿਅਤ ਪੰਨਾ',
'administrators'	=> 'Project:ਪ੍ਰਸ਼ਾਸਕ',
'sysoptitle'		=> 'Sysop ਦਰਜਾ ਹੋਣਾ ਚਾਹੀਦਾ ਹੈ',
'sysoptext'		=> 'ਜੋ ਤੁਸੀਂ ਕਰਨਾ ਚਾਹ ਰਹੇ ਹੋ, ਓਹ ਸਿਰਫ਼ \'sysop\' ਦਰਜੇ ਵਾਲੇ ਮੈਂਬਰ ਹੀ ਕਰ ਸਕਦੇ ਹਨ. ਹੋਰ ਜਾਣਕਾਰੀ ਲਈ ਵੇਖੋ: $1',
'developertitle'	=> 'Developer ਦਰਜਾ ਹੋਣਾ ਚਾਹੀਦਾ ਹੈ',
'developertext'	=> 'ਜੋ ਤੁਸੀਂ ਕਰਨਾ ਚਾਹ ਰਹੇ ਹੋ, ਓਹ ਸਿਰਫ਼ \'developer\' ਦਰਜੇ ਵਾਲੇ ਮੈਂਬਰ ਹੀ ਕਰ ਸਕਦੇ ਹਨ. ਹੋਰ ਜਾਣਕਾਰੀ ਲਈ ਵੇਖੋ: $1',
'bureaucrattitle'	=> 'Bureaucrat ਦਰਜਾ ਹੋਣਾ ਚਾਹੀਦਾ ਹੈ',
'bureaucrattext'	=> 'ਜੋ ਤੁਸੀਂ ਕਰਨਾ ਚਾਹ ਰਹੇ ਹੋ, ਓਹ ਸਿਰਫ਼ \'bureaucrat\' ਦਰਜੇ ਵਾਲੇ sysop ਮੈਂਬਰ ਹੀ ਕਰ ਸਕਦੇ ਹਨ',
'nbytes'		=> '$1 ਬਾਈਟ',
'go'			=> 'ਜਾਓ',
'ok'			=> 'ਠੀਕ ਹੈ',
'sitetitle'		=> '{{SITENAME}}',
'pagetitle'		=> '$1 - {{SITENAME}}',
'sitesubtitle'	=> 'ਇੱਕ ਆਜ਼ਾਦ ਵਿਸ਼ਵਕੌਸ਼', # FIXME
'retrievedfrom'	=> '\'$1\' ਤੋਂ ਪ੍ਰਾਪਤ ਕੀਤਾ ਗਿਆ ਹੈ',
'newmessages'		=> 'ਤੁਹਾਡੇ ਲਈ $1 ਹਨ',
'newmessageslink'	=> 'ਨਵੇਂ ਸੰਦੇਸ਼',
'editsection'		=> 'ਬਦਲੋ',
'toc'			=> 'ਵਿਸ਼ਾ-ਸੂਚੀ',
'showtoc'		=> 'ਦਿਖਾਓ',
'hidetoc'		=> 'ਛੁਪਾਓ',
'thisisdeleted'	=> 'ਵੇਖੋ ਜਾਂ ਮੁੜ ਵਾਪਿਸ ਲਿਆਓ $1?',
'restorelink'		=> '$1 deleted edits',
'feedlinks'		=> 'Feed:',
'sitenotice'		=> '',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'		=> 'ਲੇਖ',
'nstab-user'		=> 'ਮੈਂਬਰ ਦਾ ਪੰਨਾ',
'nstab-media'		=> 'ਮੀਡੀਆ',
'nstab-special'	=> 'ਖਾਸ',
'nstab-wp'		=> 'ਜਾਣਕਾਰੀ',
'nstab-image'		=> 'ਤਸਵੀਰ',
'nstab-mediawiki'	=> 'ਸੰਦੇਸ਼',
'nstab-template'	=> 'ਨਮੂਨਾ',
'nstab-help'		=> 'ਮਦਦ',
'nstab-category'	=> 'ਸ਼੍ਰੇਣੀ',

# Main script and global functions
#
'nosuchaction'	=> 'ਅਜੇਹੀ ਕੋਈ ਕਿਰਿਆ ਨਹੀਂ ਹੈ',
'nosuchactiontext'	=> 'URL ਦੁਵਾਰਾ ਕੀਤੀ ਗਈ ਕਿਰਿਆ (action) ਤੋਂ ਵਿਕਿ ਸੋਫ਼ਟਵੇਅਰ ਜਾਣੂ ਨਹੀਂ ਹੈ',
'nosuchspecialpage'	=> 'ਅਜੇਹਾ ਕੋਈ ਖਾਸ ਪੰਨਾ ਨਹੀਂ ਹੈ',
'nospecialpagetext'	=> 'ਤੁਸੀਂ ਇੱਕ ਖਾਸ ਪੰਨੇ ਦੀ ਮੰਗ ਕੀਤੀ ਹੈ ਜਿਸ ਤੋਂ ਵਿਕਿ ਸੋਫ਼ਟਵੇਅਰ ਜਾਣੂ ਨਹੀਂ ਹੈ',

# General errors
#
'error'		=> 'ਗਲਤੀ',
'databaseerror'	=> 'ਡਾਟਾਬੇਸ ਨਾਲ ਸੰਬੰਧਤ ਗਲਤੀ',
'dberrortext'		=> 'ਡਾਟਾਬੇਸ ਪੁੱਛਗਿੱਛ ਦੀ ਵਾਕ-ਰਚਨਾ ਵਿਚ ਕੋਈ ਗਲਤੀ ਹੋ ਗਈ ਹੈ।
ਇਹ ਕਿਸੇ ਖੋਜ ਬਾਰੇ ਗਲਤ ਪੁੱਛਗਿੱਛ ਦੇ ਕਾਰਨ ਹੋ ਸਕਦਾ ਹੈ($5 ਦੇਖੋ),
ਜਾਂ ਸ਼ਾਇਦ ਸੌਫ਼ਟਵੇਅਰ ਵਿਚ ਕੋਈ ਖ਼ਰਾਬੀ ਹੋ ਸਕਦੀ ਹੈ।
ਪਿਛਲੀ ਵਾਰ ਕੋਸ਼ਿਸ਼ ਕੀਤੀ ਗਈ ਡਾਟਾਬੇਸ ਪੁੱਛਗਿੱਛ ਇਹ ਸੀ:
<blockquote><tt>$1</tt></blockquote>
\'<tt>$2</tt>\'ਇਸ ਫ਼ੰਕਸ਼ਨ ਦੇ ਵਿਚੋਂ।
MySQL ਨੇ \'<tt>$3: $4</tt>\'ਗਲਤੀ ਦਿਖਾਈ।',
'dberrortextcl' => 'ਡਾਟਾਬੇਸ ਪੁੱਛਗਿੱਛ ਦੀ ਵਾਕ-ਰਚਨਾ ਵਿਚ ਕੋਈ ਗਲਤੀ ਹੋ ਗਈ ਹੈ।
ਪਿਛਲੀ ਵਾਰ ਕੋਸ਼ਿਸ਼ ਕੀਤੀ ਗਈ ਡਾਟਾਬੇਸ ਪੁੱਛਗਿੱਛ ਇਹ ਸੀ:
<blockquote><tt>$1</tt></blockquote>
\'$2\'ਇਸ ਫ਼ੰਕਸ਼ਨ ਦੇ ਵਿਚੋਂ।
MySQL ਨੇ \'$3: $4\'ਗਲਤੀ ਦਿਖਾਈ।\n',
'noconnect'		=> 'ਮਾਫ਼ ਕਰਨਾ! ਵਿਕਿ ਨੂੰ ਕੁਝ ਤਕਨੀਕੀ ਮੁਸ਼ਕਲਾਂ ਦਾ ਸਾਹਮਣਾ ਕਰਨਾ ਪੈ ਰਿਹਾ ਹੈ, ਅਤੇ ਇਹ ਡਾਟਾਬੇਸ ਸਰਵਰ ਨਾਲ ਸੰਪਰਕ ਨਹੀਂ ਬਣਾ ਸਕਦਾ ਹੈ। <br />$1',
'nodb'		=> 'ਡਾਟਾਬੇਸ $1 ਨੂੰ ਚੁਣ ਨਹੀਂ ਸਕਿਆ',
'internalerror'	=> 'ਅੰਦਰੂਨੀ ਗਲਤੀ',
'filecopyerror'	=> 'ਫ਼ਾਈਲ \'$1\' ਨੂੰ ਫ਼ਾਈਲ \'$2\' ਤੇ ਨਕਲ ਨਹੀਂ ਕੀਤਾ ਜਾ ਸਕਿਆ',
'filerenameerror'	=> 'ਫ਼ਾਈਲ \'$1\' ਦਾ ਨਾਮ \'$2\' ਨਹੀਂ ਕੀਤਾ ਜਾ ਸੱਕਿਆ',
'filedeleteerror'	=> 'ਫ਼ਾਈਲ \'$1\' ਨੂੰ ਨਹੀਂ ਹਟਾਇਆ ਜਾ ਸੱਕਿਆ',
'filenotfound'	=> 'ਫ਼ਾਈਲ \'$1\' ਨਹੀਂ ਲੱਭੀ ਜਾ ਸਕੀ',
'unexpected'		=> 'Unexpected value: \'$1\'=\'$2\'.',
'formerror'		=> 'Error: could not submit form',
'badarticleerror'	=> 'ਇਹ ਕਿਰਿਆ ਇਸ ਪੰਨੇ ਤੇ ਸੰਪੰਨ ਨਹੀਂ ਕੀਤੀ ਜਾ ਸਕਦੀ',
'cannotdelete'	=> 'ਪੰਨੇ ਜਾਂ ਤਸਵੀਰ ਨੂੰ ਨਹੀਂ ਹਟਾ ਸੱਕਿਆ ਗਿਆ (ਇਹ ਸ਼ਾਇਦ ਪਿਹਲਾਂ ਹੀ ਕਿਸੇ ਦੁਆਰਾ ਹਟਾ ਦਿੱਤਾ ਗਿਆ ਹੈ)',
'badtitle'		=> 'ਗਲਤ ਸਿਰਲੇਖ',
'viewsource'		=> 'ਸ੍ਰੋਤ ਦੇਖੋ',
'protectedtext'	=> 'This page has been locked to prevent editing; there are
a number of reasons why this may be so, please see
[[{{ns:4}}:Protected page]].

You can view and copy the source of this page:',
'seriousxhtmlerrors'	=> 'Tidy ਦੁਆਰਾ ਕੁਝ ਬਹੁਤ ਹੀ ਅਹਿਮ XHTML markup ਦੀਆਂ ਗਲਤੀਆਂ ਲੱਭੀਆਂ ਗਈਆਂ ਹਨ',

# Login and logout pages
#
'logouttitle'		=> 'ਮੈਂਬਰ ਲਾਗ ਆਊਟ',
'logouttext'		=> 'ਹੁਣ ਤੁਸੀਂ ਲਾਗ ਆਊਟ ਹੋ ਚੁੱਕੇ ਹੋ। ਹੁਣ ਤੁਸੀਂ ਅਗਿਆਤ ਰੂਪ ਵਿੱਚ 
 {{SITENAME}} ਦੀ ਵਰਤੋਂ ਕਰ ਸਕਦੇ ਹੋ ਜਾਂ ਓਹੀ ਮੈਂਬਰ 
ਜਾਂ ਕਿਸੇ ਹੋਰ ਮੈਂਬਰ ਦੇ ਰੂਪ ਵਿੱਚ ਲਾਗ ਇਨ ਕਰ ਸਕਦੇ ਹੋ।  
Note that some pages may continue to be displayed as if you were 
still logged in, until you clear your browser cache\n',

'welcomecreation'	=> '== ਜੀ ਆਇਆਂ ਨੂੰ, $1! ==

ਤੁਹਾਡਾ ਖਾਤਾ ਬਣਾ ਦਿੱਤਾ ਗਿਆ ਹੈ, ਆਪਣੀਆਂ {{SITENAME}} ਪਸੰਦਾਂ (preferences) ਨੂੰ ਬਦਲਣਾ ਨਾ ਭੁਲਣਾ।',

'loginpagetitle'	=> 'ਮੈਂਬਰ ਲਾਗ ਇਨ',
'yourname'		=> 'ਤੁਹਾਡਾ ਨਾਮ',
'yourpassword'	=> 'ਤੁਹਾਡਾ ਪਾਸਵਰਡ',
'yourpasswordagain'	=> 'ਪਾਸਵਰਡ ਦੌਬਾਰਾ ਲਿੱਖੋ',
'newusersonly'	=> '(ਸਿਰਫ਼ ਨਵੇਂ ਮੈਂਬਰਾਂ ਲਈ)',
'remembermypassword'	=> 'ਅੱਗੋਂ ਲਈ ਮੇਰਾ ਪਾਸਵਰਡ ਯਾਦ ਰੱਖੋ',
'loginproblem'	=> '<b>ਤੁਹਾਡੇ ਲਾਗ ਇਨ ਵਿੱਚ ਕੁਝ ਸਮੱਸਿਆ ਹੈ,</b><br />ਦੌਬਾਰਾ ਕੋਸ਼ਿਸ਼ ਕਰੋ!',
'alreadyloggedin'	=> '<font color=red><b>$1, ਤੁਸੀਂ ਪਿਹਲਾਂ ਹੀ ਲਾਗ ਇਨ ਹੋ ਚੁੱਕੇ ਹੋ!</b></font><br />\n',

'login'		=> 'ਲਾਗ ਇਨ',
'loginprompt'		=> '{{SITENAME}} ਵਿਚ ਲਾੱਗ-ਇਨ ਕਰਨ ਲਈ ਤੁਹਾਡੀਆਂ cookies enabled ਹੋਣੀਆਂ ਚਾਹੀਦੀਆਂ ਹਨ।',
'userlogin'		=> 'ਨਵਾਂ ਖਾਤਾ ਬਨਾਓ ਜਾਂ ਲਾਗ ਇਨ ਕਰੋ',
'logout'		=> 'ਲਾਗ ਆਊਟ',
'userlogout'		=> 'ਲਾਗ ਆਊਟ',
'notloggedin'		=> 'ਲਾਗ ਇਨ ਨਹੀਂ ਹੈ',
'createaccount'	=> 'ਨਵਾਂ ਖਾਤਾ ਬਣਾਓ',
'createaccountmail'	=> 'ਈ-ਮੇਲ (email) ਰਾਹੀਂ',
'badretype'		=> 'ਪਾਸਵਰਡ ਇਕ ਦੂਜੇ ਨਾਲ ਮੇਲ ਨਹੀਂ ਖਾਂਦੇ',
'userexists'		=> 'ਇਹ ਨਾਮ ਪਿਹਲਾਂ ਹੀ ਵਰਤੋਂ ਵਿੱਚ ਹੈ, ਕਿਰਪਾ ਕਰਕੇ ਕਿਸੇ ਹੋਰ ਨਾਮ ਦੀ ਵਰਤੋਂ ਕਰੋ',
'youremail'		=> '* ਤੁਹਾਡਾ ਈ-ਮੇਲ (email)',
'yourrealname'	=> '* ਤੁਹਾਡਾ ਨਾਮ',
'yourlanguage'	=> 'Interface language',
'yourvariant'		=> 'Language variant',
'yournick'		=> 'ਤੁਹਾਡਾ ਉਪਨਾਮ (ਦਸਤਖ਼ਤ ਲਈ)',
'emailforlost'	=> 'Star (*) ਨਿਸ਼ਾਨ ਲੱਗੇ ਖਾਨਿਆਂ ਵਿੱਚ ਭਰਨਾ ਲਾਜ਼ਮੀ ਨਹੀਂ ਹੈ. 
ਜੇ ਤੁਸੀਂ ਈ-ਮੇਲ ਦਿੰਦੇ ਹੋ ਤਾਂ ਬਿਨਾਂ ਤੁਹਾਡੇ ਅਸਲੀ ਈ-ਮੇਲ ਨੂੰ ਜਾਣੇ, ਇਸ website ਦੁਆਰਾ ਲੋਕ ਤੁਹਾਨੂੰ ਸੰਪੰਰਕ ਕਰ ਸਕਦੇ ਹਨ 
ਅਤੇ ਜੇ ਕਦੀ ਤੁਸੀਂ ਆਪਣਾ ਪਾਸਵਰਡ ਭੁੱਲ ਜਾਓ, ਤਾਂ ਇਸ ਈ-ਮੇਲ ਤੇ ਤੁਹਾਨੂੰ ਨਵਾਂ ਪਾਸਵਰਡ ਭੇਜਿਆ ਜਾ ਸਕਦਾ ਹੈ. <br /><br />ਤੁਹਾਡਾ ਅਸਲੀ ਨਾਮ, ਜੇ ਤੁਸੀਂ ਇੱਥੇ ਦਿੰਦੇ ਹੋ, ਤੁਹਾਡੇ ਕੰਮ ਨੂੰ attribution ਦੇਣ ਲਈ ਵਰਤਿਆ ਜਾਵੇਗਾ',
'prefs-help-userdata'	=> '* <strong>ਅਸਲੀ ਨਾਮ</strong> (ਗੈਰ-ਜ਼ਰੂਰੀ): ਜੇ ਤੁਸੀਂ ਭਰਦੇ ਹੋ, ਤਾਂ ਤੁਹਾਡੇ ਕੰਮ ਨੂੰ attribution ਦੇਣ ਲਈ ਵਰਤਿਆ ਜਾਵੇਗਾ<br/>

* <strong>ਈ-ਮੇਲ</strong> (ਗੈਰ-ਜ਼ਰੂਰੀ): ਜੇ ਭਰਦੇ ਹੋ ਤਾਂ ਬਿਨਾਂ ਤੁਹਾਡੇ ਅਸਲੀ ਈ-ਮੇਲ ਨੂੰ ਜਾਣੇ, ਇਸ website ਦੁਆਰਾ ਲੋਗ ਤੁਹਾਨੂੰ ਸੰਪੰਰਕ ਕਰ ਸਕਦੇ ਹਨ 
ਅਤੇ ਜੇ ਕਦੀ ਤੁਸੀਂ ਆਪਣਾ ਪਾਸਵਰਡ ਭੁੱਲ ਜਾਓ, ਤਾਂ ਇਸ ਈ-ਮੇਲ ਤੇ ਤੁਹਾਨੂੰ ਨਵਾਂ ਪਾਸਵਰਡ ਭੇਜਿਆ ਜਾ ਸਕਦਾ ਹੈ.',
'loginerror'		=> 'ਲਾਗ ਇਨ ਵਿੱਚ ਗਲਤੀ',
'noname'		=> 'ਤੁਸੀਂ ਮੈਂਬਰ ਦਾ ਨਾਮ ਸਹੀ ਨਹੀਂ ਦੱਸਿਆ.',
'loginsuccesstitle'	=> 'ਲਾਗ ਇਨ ਕਾਮਯਾਬ ਰਿਹਾ',
'loginsuccess'	=> 'ਹੁਣ ਤੁਸੀਂ {{SITENAME}} ਵਿੱਚ \'$1\' ਨਾਮ ਨਾਲ ਲਾਗ ਇਨ ਹੋ',
'nosuchuser'		=> '\'$1\' ਨਾਮ ਦਾ ਕੋਈ ਮੈਂਬਰ ਨਹੀਂ ਹੈ. 
ਕਿਰਪਾ ਕਰਕੇ ਨਾਮ ਸਹੀ ਲਿੱਖੋ ਜਾਂ ਨੀਚੇ ਦਿੱਤੇ ਗਏ ਫ਼ਾਰਮ ਦੀ ਵਰਤੋਂ ਕਰਕੇ ਇੱਕ ਨਵਾਂ ਖਾਤਾ ਬਣਾ ਲਓ.',
'wrongpassword'	=> 'ਦਿੱਤਾ ਗਿਆ ਪਾਸਵਰਡ ਗਲਤ ਹੈ, ਕਿਰਪਾ ਕਰਕੇ ਦੋਬਾਰਾ ਯਤਨ ਕਰੋ',
'mailmypassword'	=> 'ਮੈਨੂੰ ਇੱਕ ਨਵਾਂ ਪਾਸਵਰਡ ਈ-ਮੇਲ ਰਾਹੀਂ ਭੇਜ ਦਿਓ',
'passwordremindertitle'	=> '{{SITENAME}} ਵਲੋਂ ਪਾਸਵਰਡ ਯਾਦਦਹਾਣੀ-ਪੱਤ੍ਰ (Password Reminder from {{SITENAME}})',
'passwordremindertext'	=> 'ਕਿਸੇ ਨੇ (ਸ਼ਾਯਿਦ ਤੁਸੀਂ ਹੀ, $1 IP address ਤੋਂ) 
 {{SITENAME}} ਲਾਗ ਇਨ ਦਾ ਨਵਾਂ ਪਾਸਵਰਡ ਭੇਜਣ ਦੀ ਮੰਗ ਕੀਤੀ ਸੀ. 
ਮੈਂਬਰ \'$1\' ਦਾ ਹੁਣ ਨਵਾਂ ਪਾਸਵਰਡ \'$3\' ਹੈ. 
ਕਿਰਪਾ ਕਰਕੇ {{SITENAME}} ਵਿੱਚ ਲਾਗ ਇਨ ਕਰਕੇ ਹੁਣੇ ਆਪਣਾ ਪਾਸਵਰਡ ਬਦਲ ਲਓ.\n<br /><br />
Someone (probably you, from IP address $1)
requested that we send you a new {{SITENAME}} login password.
The password for user \'$2\' is now \'$3\'.
You should log in and change your password now.',
'noemail'		=> 'ਮੈਂਬਰ \'$1\' ਲਈ ਕੋਈ ਈ-ਮੇਲ ਅਡ੍ਰੈੱਸ ਨਹੀਂ ਹੈ',
'passwordsent'	=> '\'$1\' ਮੈਂਬਰ ਦੇ ਈ-ਮੇਲ ਅਡ੍ਰੈੱਸ ਤੇ ਇੱਕ ਨਵਾਂ ਪਾਸਵਰਡ ਭੇਜ ਦਿੱਤਾ ਗਿਆ ਹੈ. 
ਪਾਸਵਰਡ ਮਿਲੱਣ ਤੋਂ ਬਾਅਦ ਕਿਰਪਾ ਲਾਗ ਇਨ ਜ਼ਰੂਰ ਕਰੋ.',
'loginend'		=> ' ',
'mailerror'		=> 'ਮੇਲ (mail) $1 ਭੇਜਣ ਵਿੱਚ ਸਮੱਸਿਆ ਆ ਗਈ ਹੈ',
'acct_creation_throttle_hit'	=> 'ਮਾਫ਼ੀ ਚਾਹੁੰਦੇ ਹਾਂ, ਤੁਸੀਂ ਪਿਹਲਾਂ ਹੀ $1 ਖਾਤੇ ਬਣਾ ਚੁੱਕੇ ਹੋ. ਤੁਸੀਂ ਇਸਤੋਂ ਜ਼ਿਆਦਾ ਨਹੀਂ ਬਣਾ ਸੱਕਦੇ ਹੋ',

# Edit page toolbar
'bold_sample'		=>'ਬੋਲਡ ਅੱਖਰ',
'bold_tip'		=>'ਬੋਲਡ ਅੱਖਰ',
'italic_sample'	=>'ਇਟੈਲਿਕ ਅੱਖਰ',
'italic_tip'		=>'ਇਟੈਲਿਕ ਅੱਖਰ',
'link_sample'		=>'Link title',
'link_tip'		=>'ਅੰਦਰੂਨੀ ਕੜੀ',
'extlink_tip'		=>'ਬਾਹਰੀ ਕੜੀ (ਪਹਿਲਾਂ http:// ਲਗਾਉਣਾ ਯਾਦ ਰੱਖੋ)',
'math_tip'		=>'ਗਣਿਤ ਦਾ ਫਾਰਮੂਲਾ (LaTeX)',

# Edit pages
#
'summary'		=> 'ਸਾਰ',
'subject'		=> 'ਵਿਸ਼ਾ',
'minoredit'		=> 'ਇਹ ਇਕ ਮਾਮੂਲੀ ਬਦਲਾਵ ਹੈ',
'watchthis'		=> 'ਇਸ ਪੰਨੇ ਤੇ ਨਜ਼ਰ ਰਖੋ',
'savearticle'		=> 'ਪੱਕਾ ਕਰ ਦਿਓ',
'preview'		=> 'ਝਲਕ',
'showpreview'		=> 'ਝਲਕ ਦਿਖਾਓ',
'blockedtitle'	=> 'ਮੈਂਬਰ ਤੇ ਰੋਕ ਲਗਾ ਦਿੱਤੀ ਗਈ ਹੈ',
'blockedtext'		=> 'ਤੁਹਾਡੇ ਮੈਂਬਰ ਨਾਮ ਜਾਂ IP address ਉੱਤੇ $1 ਦੁਆਰਾ ਰੋਕ ਲਗਾ ਦਿੱਤੀ ਗਈ ਹੈ. 
ਕਾਰਣ ਹੈ:<br />\'\'$2\'\'<p>ਇਸ ਰੋਕ ਦੇ ਬਾਰੇ ਵਿੱਚ ਚਰਚਾ ਕਰਨ ਲਈ 
$1 ਜਾਂ ਕਿਸੇ ਵੀ ਹੋਰ [[{{ns:4}}:ਪ੍ਰਸ਼ਾਸਕ]] 
ਨੂੰ ਸੰਪੰਰਕ ਕਰੋ. ਧਿਆਨ ਦਿਓ ਕਿ ਤੁਸੀਂ ਓਹਨਾਂ ਚਿਰ \'ਇਸ ਮੈਂਬਰ ਨੂੰ ਈ-ਮੇਲ ਕਰੋ\' ਸੁਵੀਧਾ ਦੀ ਵਰਤੋਂ 
ਨਹੀਂ ਕਰ ਸਕਦੇ, ਜਦੋਂ ਤੱਕ ਕਿ ਤੁਸੀਂ [[Special:Preferences|preferences]] 
ਵਿੱਚ ਆਪਣਾ ਈ-ਮੇਲ ਨਹੀਂ ਦਿੰਦੇ. ਤੁਹਾਡਾ IP address ਹੈ $3. 
ਕਿਰਪਾ ਕਰਕੇ ਪੁਛ-ਗਿੱਛ ਕਰਦੇ ਵਕਤ ਇਸ IP address ਦੀ ਵਰਤੋਂ ਜ਼ਰੂਰ ਕਰੋ.',
'whitelistedittitle'	=> 'ਬਦਲਾਵ ਕਰਨ ਲਈ ਲਾੱਗ-ਇਨ ਹੋਣਾ ਜ਼ਰੂਰੀ ਹੈ',
'whitelistedittext'	=> 'ਤੁਹਾਨੂੰ ਲੇਖਾਂ ਵਿੱਚ ਬਦਲਾਵ ਕਰਨ ਲਈ [[Special:Userlogin|login]] ਕਰਨਾ ਜ਼ਰੂਰੀ ਹੈ',
'whitelistreadtitle'	=> 'ਪੜ੍ਹਨ ਲਈ ਲਾੱਗ-ਇਨ ਕਰਨਾ ਜ਼ਰੂਰੀ ਹੈ',
'whitelistreadtext'	=> 'ਤੁਹਾਨੂੰ ਲੇਖ ਪੜਨ ਲਈ [[Special:Userlogin|login]] ਕਰਨਾ ਜ਼ਰੂਰੀ ਹੈ',
'whitelistacctitle'	=> 'ਤੁਹਾਨੂੰ ਖਾਤਾ ਬਨਾਓਣ ਦੀ ਅਨੁਮਤੀ ਨਹੀਂ ਹੈ',
'whitelistacctext'	=> 'ਇਸ ਵਿਕਿ ਵਿੱਚ ਖਾਤਾ ਬਨਾਓਣ ਲਈ ਤੁਹਾਨੂੰ [[Special:Userlogin|login]] ਕਰਨਾ ਜ਼ਰੂਰੀ ਹੈ ਅਤੇ ਨਾਲ ਹੀ ਉਪ੍ਯੁਕਤ ਅਨੁਮਤੀ ਵੀ ਹੋਣੀ ਚਾਹੀਦੀ ਹੈ',
'loginreqtitle'	=> 'ਲਾਗ ਇਨ ਜ਼ਰੂਰੀ ਹੈ',
'loginreqtext'	=> 'ਬਾਕੀ ਦੇ ਲੇਖ ਵੇਖਣ ਲਈ [[Special:Userlogin|login]] ਕਰਨਾ ਜ਼ਰੂਰੀ ਹੈ',
'accmailtitle'	=> 'ਪਾਸਵਰਡ ਭੇਜ ਦਿੱਤਾ ਗਿਆ ਹੈ',
'accmailtext'		=> '\'$1\' ਦਾ ਪਾਸਵਰਡ $2 ਨੂੰ ਭੇਜ ਦਿੱਤਾ ਗਿਆ ਹੈ',
'newarticle'		=> '(ਨਵਾਂ ਲੇਖ)',
'newarticletext'	=> '
ਤੁਸੀਂ ਅਜੇਹੇ ਪੰਨੇ ਤੇ ਪੁੱਜ ਗਏ ਹੋ ਜੋ ਅਜੇ ਲਿੱਖਿਆ ਨਹੀਂ ਗਿਆ ਹੈ. 
ਜੇ ਤੁਸੀਂ ਇਸ ਪੰਨੇ ਨੂੰ ਬਨਾਣਾ ਚਾਹੁੰਦੇ ਹੋ, ਤਾਂ ਹੇਠਾਂ ਦਿੱਤੀ 
ਥਾਂ ਵਿੱਚ ਲਿੱਖਣਾ ਸ਼ੁਰੂ ਕਰ ਦਿਓ(ਹੋਰ ਜਾਣਕਾਰੀ ਲਈ ਵੇਖੋ [[Project:Help|help page]]). 
ਜੇ ਤੁਸੀਂ ਗਲਤੀ ਨਾਲ ਇੱਥੇ ਆ ਗਏ ਹੋ, ਤਾਂ ਆਪਣੇ browser ਦਾ \'\'\'back\'\'\' button ਦਬਾਓ',
'noarticletext'	=> '(ਅਜੇ ਇਹ ਪੰਨਾ ਖਾਲੀ ਹੈ)',
'usercssjsyoucanpreview'	=> '<strong>ਨਸੀਹਤ:</strong>CSS/JS ਵਿੱਚ ਕੀਤੇ ਗਏ ਬਦਲਾਵ  ਨੂੰ ਪੱਕਾ ਕਰਨ ਤੋਂ ਪਿਹਲਾਂ, \'ਝਲਕ ਦਿਖਾਓ\' button ਦਾ ਇਸਤੇਮਾਲ ਕੀਤਾ ਜਾ ਸਕਦਾ ਹੈ',
'usercsspreview'	=> '\'\'\'ਯਾਦ ਰੱਖੋ ਕਿ ਤੁਸੀਂ ਆਪਣੀ CSS ਦੀ ਸਿਰਫ਼ ਝਲਕ ਵੇਖ ਰਹੇ ਹੋ, ਅਜੇ ਇਸਨੂੰ ਪੱਕਾ ਨਹੀਂ ਕੀਤਾ ਗਿਆ ਹੈ!\'\'\'',
'userjspreview'	=> '\'\'\'ਯਾਦ ਰੱਖੋ ਕਿ ਤੁਸੀਂ ਆਪਣੀ javascript ਦੀ ਸਿਰਫ਼ ਝਲਕ ਵੇਖ ਰਹੇ ਹੋ, ਅਜੇ ਇਸਨੂੰ ਪੱਕਾ ਨਹੀਂ ਕੀਤਾ ਗਿਆ ਹੈ!\'\'\'',
'updated'		=> '(ਅੱਪਡੇਟ (update) ਹੋ ਗਿਆ ਹੈ)',
'note'		=> '<strong>ਧਿਆਨ ਦਿਓ:</strong> ',
'previewnote'		=> 'ਯਾਦ ਰੱਖੋ ਕਿ ਇਹ ਸਿਰਫ਼ ਇਕ ਝਲਕ ਹੈ, ਅਜੇ ਇਸਨੂੰ ਪੱਕਾ ਨਹੀਂ ਕੀਤਾ ਗਿਆ ਹੈ!',
'editing'		=> 'ਬਦਲ ਰਹੇ ਹਾਂ: $1',
'sectionedit'		=> ' (ਵਿਭਾਗ)',
'commentedit'		=> ' (ਟਿੱਪਣੀ)',
'editconflict'	=> 'ਬਦਲਾਵ ਮੱਤਭੇਦ: $1',
'yourdiff'		=> 'ਅੰਤਰ (Differences)',

# History pages
#
'revhistory'		=> 'ਸੋਧ ਦਾ ਇਤਿਹਾਸ',
'nohistory'		=> 'ਇਸ ਪੰਨੇ ਲਈ ਤਬਦੀਲ਼ੀ ਦਾ ਕੋਈ ੲਤਿਹਾਸ ਨਹੀਂ ਹੈ.',
'revnotfound'		=> 'ਸੋਧ ਨਹੀਂ ਮਿਲੀ',
'loadhist'		=> 'ਪੰਨੇ ਦਾ ਇਤਿਹਾਸ ਲੋਡ ਹੋ ਰਿਹਾ ਹੈ',
'currentrev'		=> 'ਮੌਜੂਦਾ ਸੰਸ਼ੋਧਨ',
'cur'			=> 'ਮੌਜੂਦਾ',
'next'		=> 'ਅਗਲਾ',
'last'		=> 'ਪਿਛਲਾ',
'orig'		=> 'ਅਸਲ',

# Diffs
#
'editcurrent'		=> 'ਇਸ ਪੰਨੇ ਦੇ ਮੌਜੂਦਾ ਰੁਪਾਂਤਰ ਵਿਚ ਤਬਦੀਲੀ ਕਰੋ',
'selectnewerversionfordiff'	=> 'ਆਪਸ ਵਿਚ ਮਿਲਾਉਣ ਲਈ ਨਵਾਂ ਰੁਪਾਂਤਰ ਚੁਣੋ',
'selectolderversionfordiff'	=> 'ਆਪਸ ਵਿਚ ਮਿਲਾਉਣ ਲਈ ਪੁਰਾਣਾ ਰੁਪਾਂਤਰ ਚੁਣੋ',
'compareselectedversions'	=> 'ਚੁਣੇ ਹੋਏ ਰਪਾਂਤਰਾਂ ਨੂੰ ਆਪਸ ਵਿਚ ਮਿਲਾਓ',

# Search results
#
'searchdisabled'	=> '<p>ਮੁਆਫ਼ੀ ਚਾਹੁੰਦੇ ਹਾਂ! Full text search, performance reasons ਕਰਕੇ ਕੁੱਝ ਦੇਰ ਲਈ ਬੰਦ ਕਰ ਦਿੱਤੀ ਗਈ ਹੈ. ਇਸ ਦਰਮਿਆਨ, ਚਾਹੋ ਤਾਂ ਤੁਸੀਂ Google search ਦੀ ਵਰਤੋਂ ਕਰ ਸਕਦੇ ਹੋ, ਜੋ ਕਿ ਹੋ ਸਕਦਾ ਹੈ ਪੂਰਾਣੀ ਹੋ ਚੁੱਕੀ ਹੋਵੇ</p>',

# Recent changes
#
'changes'		=> 'ਬਦਲਾਵ',
'recentchanges'	=> 'ਹਾਲ ਵਿੱਚ ਹੋਏ ਬਦਲਾਵ',
'rcnote'		=> 'ਪਿੱਛਲੇ <strong>$2</strong> ਦਿਨਾਂ ਵਿੱਚ ਹੋਏ <strong>$1</strong> ਬਦਲਾਵ:',
'rclistfrom'		=> '$1 ਤੋਂ ਸ਼ੁਰੂ ਹੋਣ ਵਾਲੇ ਨਵੇਂ ਬਦਲਾਵ ਦਿਖਾਓ',
'rclinks'		=> 'ਪਿੱਛਲੇ $2 ਦਿਨਾਂ ਵਿੱਚ ਹੋਏ $1 ਬਦਲਾਵ ਦਿਖਾਓ<br />$3',
'hide'		=> 'ਛੁਪਾਓ',
'show'		=> 'ਦਿਖਾਓ',
'listform'		=> 'ਸੂਚੀ',
'nchanges'		=> '$1 ਬਦਲਾਵ',

# tooltip help for some actions, most are in Monobook.js
'tooltip-article'	=> 'ਲੇਖ ਦਿਖਾਓ [alt-a]',
'tooltip-talk'	=> 'ਲੇਖ ਬਾਰੇ ਚਰਚਾ [alt-t]',
'tooltip-edit'	=> 'ਤੁਸੀਂ ਇਸ ਪੰਨੇ ਵਿੱਚ ਬਦਲਾਵ ਕਰ ਸੱਕਦੇ ਹੋ. ਕਿਰਪਾ ਕਰਕੇ ਪੱਕਾ ਕਰਨ ਤੋਂ ਪਿਹਲਾਂ, \'ਝਲਕ ਦਿਖਾਓ\' button ਦਾ ਇੱਸਤੇਮਾਲ ਜ਼ਰੂਰ ਕਰੋ [alt-e]',
'tooltip-viewsource'	=> 'ਇਹ ਪੰਨਾ ਸੁਰੱਖਿਅਤ ਹੈ। ਤੁਸੀਂ ਇਸਦਾ ਸ੍ਰੋਤ ਦੇਖ ਸਕਦੇ ਹੋ। [alt-e]',
'tooltip-history'	=> 'ਇਸ ਪਣਨੇ ਦੇ ਪਹਿਲੇ ਰੁਪਾਂਤਰ, [alt-h]',
'tooltip-protect'	=> 'ਇਸ ਪੰਨੇ ਦੀ ਰੱਖਿਆ ਕਰੋ [alt--]',
'tooltip-delete'	=> 'ਇਹ ਪੰਨਾ ਹਟਾ ਦਿਓ [alt-d]',
'tooltip-undelete'	=> 'Restore $1 deleted edits to this page [alt-d]',
'tooltip-move'	=> 'ਇਸ ਪੰਨੇ ਨੂੰ ਕਿਤੇ ਹੋਰ ਲਿਜਾਓ [alt-m]',
'tooltip-nomove'	=> 'ਤੁਹਾਡੇ ਕੋਲ ਇਸ ਪੰਨੇ ਨੂੰ ਕਿਤੇ ਹੋਰ ਲਿਜਾਉਣ ਦੇ ਅਧਿਕਾਰ ਨਹੀਂ ਹਨ',
'tooltip-watch'	=> 'ਇਸ ਪੰਨੇ ਨੂੰ ਆਪਣੀ watchlist ਵਿੱਚ ਜਮਾਂ ਕਰੋ[alt-w]',
'tooltip-unwatch'	=> 'ਇਸ ਪੰਨੇ ਨੂੰ ਆਪਣੀ watchlist ਤੋਂ ਹਟਾ ਦਿਓ[alt-w]',
'tooltip-watchlist'	=> 'ਉਹਨਾਂ ਪੰਨਿਆਂ ਦੀ ਸੂਚੀ, ਜਿਹਨਾਂ ਤੇ ਤੁਸੀਂ ਬਦਲਾਵ ਲਈ ਨਿਗਰਾਨੀ ਰੱਖ ਰਹੇ ਹੋ। [alt-l]',
'tooltip-preferences'	=> 'ਮੇਰੀ ਪਸੰਦ',
'tooltip-mycontris'	=> 'ਮੇਰੇ ਯੋਗਦਾਨ ਦੀ ਸੂਚੀ [alt-y]',
'tooltip-mainpage'	=> 'ਮੁੱਖ ਪੰਨੇ ਤੇ ਜਾਓ [alt-z]',
'tooltip-portal'	=> 'ਪ੍ਰੋਜੈਕਟ ਦੇ ਬਾਰੇ, ਤੁਸੀਂ ਕੀ ਕਰ ਸਕਦੇ ਹੋ, ਚੀਜ਼ਾਂ ਕਿਥੋਂ ਮਿਲਣਗੀਆਂ',
'tooltip-sitesupport'	=> '{{SITENAME}} ਦੀ ਸਹਾਇਤਾ ਕਰੋ',
'tooltip-recentchanges'	=> 'ਵਿਕਿ ਵਿਚ ਹਾਲ ਹੀ ਵਿਚ ਹੋਈਆਂ ਤਬਦੀਲੀਆਂ ਦੀ ਸੂਚੀ। [alt-r]',
'tooltip-specialpages'	=> 'ਸਾਰੇ ਖਾਸ ਪੰਨਿਆਂ ਦੀ ਸੂਚੀ [alt-q]',
'tooltip-specialpage'	=> 'ਇਹ ਇਕ ਖਾਸ ਪੰਨਾ ਹੈ, ਤੁਸੀਂ ਇਸਦੇ ਵਿੱਚ ਕੋਈ ਬਦਲਾਵ ਨਹੀਂ ਕਰ ਸਕਦੇ',
'tooltip-search'	=> 'ਇਸ ਵਿਕਿ ਵਿੱਚ ਲੱਭੋ [alt-f]',
'tooltip-minoredit'	=> 'ਮਾਮੂਲੀ ਬਦਲਾਵ ਦੀ ਨਿਸ਼ਾਨੀ ਲਗਾਓ (Mark this as a minor edit) [alt-i]',
'tooltip-save'	=> 'ਕੀਤੇ ਗਏ ਬਦਲਾਵ ਪੱਕੇ ਕਰੋ [alt-s]',
'tooltip-preview'	=> 'ਕੀਤੇ ਗਏ ਬਦਲਾਵਾਂ ਦੀ ਝਲਕ ਵੇਖੋ, ਕਿਰਪਾ ਕਰਕੇ ਪੱਕਾ ਕਰਨ ਤੋਂ ਪਿਹਲਾਂ ਇਸਦੀ ਵਰਤੋਂ ਜ਼ਰੂਰ ਕਰੋ! [alt-p]',

# Attribution

'lastmodifiedby'	=> 'ਇਹ ਪੰਨਾ ਅਖੀਰਲੀ ਵਾਰ $1 ਨੂੰ $2 ਦੁਆਰਾ ਬਦਲਿਆ ਗਿਆ ਸੀ',
'and'			=> 'ਅਤੇ',
'othercontribs'	=> '$1 ਦੁਆਰਾ ਕੰਮ ਤੇ ਅਧਾਰਤ।',

# Info page
'infosubtitle'	=> 'ਪੰਨੇ ਸੰਬੰਧੀ ਸੂਚਨਾ',
'numedits'		=> 'ਤਬਦੀਲੀਆਂ ਦੀ ਗਿਣਤੀ (ਲੇਖ ਵਿਚਾਲੇ): ',
'numtalkedits'	=> 'ਤਬਦੀਲੀਆਂ ਦੀ ਗਿਣਤੀ (ਚਰਚਾ-ਪੰਨੇ ਵਿਚਾਲੇ): ',
);

#--------------------------------------------------------------------------
# Internationalisation code
#--------------------------------------------------------------------------

class LanguagePa extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesPa;
		return $wgNamespaceNamesPa;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsPa;
		return $wgQuickbarSettingsPa;
	}

	function getSkinNames() {
		global $wgSkinNamesPa;
		return $wgSkinNamesPa;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesPa;
		return $wgNamespaceNamesPa[$index];
	}
	
	function getNsIndex( $text ) {
		global $wgNamespaceNamesPa;

		foreach ( $wgNamespaceNamesPa as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}

	function getMessage( $key )
	{
		global $wgAllMessagesPa;
		if( isset( $wgAllMessagesPa[$key] ) ) {
			return $wgAllMessagesPa[$key];
		} else {
			return parent::getMessage( $key );
		}
	}
	
	var $digitTransTable = array(
		'0' => '੦',
		'1' => '੧',
		'2' => '੨',
		'3' => '੩',
		'4' => '੪',
		'5' => '੫',
		'6' => '੬',
		'7' => '੭',
		'8' => '੮',
		'9' => '੯'
	);
	
	function formatNum( $number ) {
		global $wgTranslateNumerals;
		if( $wgTranslateNumerals ) {
			return strtr( $number, $this->digitTransTable );
		} else {
			return $number;
		}
	}
}
?>

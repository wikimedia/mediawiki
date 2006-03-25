<?php
/**
 * @package MediaWiki
 * @subpackage Language
 */

if( defined( 'MEDIAWIKI' ) ) {

#
# In general you should not make customizations in these language files
# directly, but should use the MediaWiki: special namespace to customize
# user interface messages through the wiki.
# See http://meta.wikipedia.org/wiki/MediaWiki_namespace
#
# NOTE TO TRANSLATORS: Do not copy this whole file when making translations!
# A lot of common constants and a base class with inheritable methods are
# defined here, which should not be redefined. See the other LanguageXx.php
# files for examples.
#

#--------------------------------------------------------------------------
# Language-specific text
#--------------------------------------------------------------------------

if($wgMetaNamespace === FALSE)
	$wgMetaNamespace = str_replace( ' ', '_', $wgSitename );

/* private */ $wgNamespaceNamesEn = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Special',
	NS_MAIN	            => '',
	NS_TALK	            => 'Talk',
	NS_USER             => 'User',
	NS_USER_TALK        => 'User_talk',
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => $wgMetaNamespace . '_talk',
	NS_IMAGE            => 'Image',
	NS_IMAGE_TALK       => 'Image_talk',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_talk',
	NS_TEMPLATE         => 'Template',
	NS_TEMPLATE_TALK    => 'Template_talk',
	NS_HELP             => 'Help',
	NS_HELP_TALK        => 'Help_talk',
	NS_CATEGORY         => 'Category',
	NS_CATEGORY_TALK    => 'Category_talk',
);

if(isset($wgExtraNamespaces)) {
	$wgNamespaceNamesEn=$wgNamespaceNamesEn+$wgExtraNamespaces;
}

/* private */ $wgDefaultUserOptionsEn = array(
	'quickbar' 		=> 1,
	'underline' 		=> 2,
	'cols'			=> 80,
	'rows' 			=> 25,
	'searchlimit' 		=> 20,
	'contextlines' 		=> 5,
	'contextchars' 		=> 50,
	'skin' 			=> $wgDefaultSkin,
	'math' 			=> 1,
	'rcdays' 		=> 7,
	'rclimit' 		=> 50,
	'highlightbroken'	=> 1,
	'stubthreshold' 	=> 0,
	'previewontop' 		=> 1,
	'editsection'		=> 1,
	'editsectiononrightclick'=> 0,
	'showtoc'		=> 1,
 	'showtoolbar' 		=> 1,
	'date' 			=> 0,
	'imagesize' 		=> 2,
	'thumbsize'		=> 2,
	'rememberpassword' 	=> 0,
	'enotifwatchlistpages' 	=> 0,
	'enotifusertalkpages' 	=> 1,
	'enotifminoredits' 	=> 0,
	'enotifrevealaddr' 	=> 0,
	'shownumberswatching' 	=> 1,
	'fancysig' 		=> 0,
	'externaleditor' 	=> 0,
	'externaldiff' 		=> 0,
);

/* private */ $wgQuickbarSettingsEn = array(
	'None', 'Fixed left', 'Fixed right', 'Floating left', 'Floating right'
);

/* private */ $wgSkinNamesEn = array(
	'standard' => 'Classic',
	'nostalgia' => 'Nostalgia',
	'cologneblue' => 'Cologne Blue',
	'davinci' => 'DaVinci',
	'mono' => 'Mono',
	'monobook' => 'MonoBook',
	'myskin' => 'MySkin',
	'chick' => 'Chick'
);

/* private */ $wgMathNamesEn = array(
	MW_MATH_PNG => 'mw_math_png',
	MW_MATH_SIMPLE => 'mw_math_simple',
	MW_MATH_HTML => 'mw_math_html',
	MW_MATH_SOURCE => 'mw_math_source',
	MW_MATH_MODERN => 'mw_math_modern',
	MW_MATH_MATHML => 'mw_math_mathml'
);

# Whether to use user or default setting in Language::date()

/* private */ $wgDateFormatsEn = array(
	MW_DATE_DEFAULT => 'No preference',
	MW_DATE_MDY => '16:12, January 15, 2001',
	MW_DATE_DMY => '16:12, 15 January 2001',
	MW_DATE_YMD => '16:12, 2001 January 15',
	MW_DATE_ISO => '2001-01-15 16:12:34'
);

/* private */ $wgUserTogglesEn = array(
	'highlightbroken',
	'justify',
	'hideminor',
	'usenewrc',
	'numberheadings',
	'showtoolbar',
	'editondblclick',
	'editsection',
	'editsectiononrightclick',
	'showtoc',
	'rememberpassword',
	'editwidth',
	'watchdefault',
	'minordefault',
	'previewontop',
	'previewonfirst',
	'nocache',
	'enotifwatchlistpages',
	'enotifusertalkpages',
	'enotifminoredits',
	'enotifrevealaddr',
	'shownumberswatching',
	'fancysig',
	'externaleditor',
	'externaldiff',
);

/* private */ $wgBookstoreListEn = array(
	'AddALL' => 'http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN',
	'PriceSCAN' => 'http://www.pricescan.com/books/bookDetail.asp?isbn=$1',
	'Barnes & Noble' => 'http://search.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1',
	'Amazon.com' => 'http://www.amazon.com/exec/obidos/ISBN=$1'
);

# Read language names
global $wgLanguageNames;
/** */
require_once( 'Names.php' );

$wgLanguageNamesEn =& $wgLanguageNames;


/* private */ $wgWeekdayNamesEn = array(
	'sunday', 'monday', 'tuesday', 'wednesday', 'thursday',
	'friday', 'saturday'
);


/* private */ $wgMonthNamesEn = array(
	'january', 'february', 'march', 'april', 'may_long', 'june',
	'july', 'august', 'september', 'october', 'november',
	'december'
);
/* private */ $wgMonthNamesGenEn = array(
	'january-gen', 'february-gen', 'march-gen', 'april-gen', 'may-gen', 'june-gen',
	'july-gen', 'august-gen', 'september-gen', 'october-gen', 'november-gen',
	'december-gen'
);

/* private */ $wgMonthAbbreviationsEn = array(
	'jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug',
	'sep', 'oct', 'nov', 'dec'
);

# Note to translators:
#   Please include the English words as synonyms.  This allows people
#   from other wikis to contribute more easily.
#
/* private */ $wgMagicWordsEn = array(
#   ID                                 CASE  SYNONYMS
	MAG_REDIRECT             => array( 0,    '#redirect'              ),
	MAG_NOTOC                => array( 0,    '__NOTOC__'              ),
	MAG_FORCETOC             => array( 0,    '__FORCETOC__'           ),
	MAG_TOC                  => array( 0,    '__TOC__'                ),
	MAG_NOEDITSECTION        => array( 0,    '__NOEDITSECTION__'      ),
	MAG_START                => array( 0,    '__START__'              ),
	MAG_CURRENTMONTH         => array( 1,    'CURRENTMONTH'           ),
	MAG_CURRENTMONTHNAME     => array( 1,    'CURRENTMONTHNAME'       ),
	MAG_CURRENTMONTHNAMEGEN  => array( 1,    'CURRENTMONTHNAMEGEN'    ),
	MAG_CURRENTMONTHABBREV   => array( 1,    'CURRENTMONTHABBREV'     ),
	MAG_CURRENTDAY           => array( 1,    'CURRENTDAY'             ),
	MAG_CURRENTDAYNAME       => array( 1,    'CURRENTDAYNAME'         ),
	MAG_CURRENTYEAR          => array( 1,    'CURRENTYEAR'            ),
	MAG_CURRENTTIME          => array( 1,    'CURRENTTIME'            ),
	MAG_NUMBEROFARTICLES     => array( 1,    'NUMBEROFARTICLES'       ),
	MAG_NUMBEROFFILES        => array( 1,    'NUMBEROFFILES'          ),
	MAG_PAGENAME             => array( 1,    'PAGENAME'               ),
	MAG_PAGENAMEE            => array( 1,    'PAGENAMEE'              ),
	MAG_NAMESPACE            => array( 1,    'NAMESPACE'              ),
	MAG_MSG                  => array( 0,    'MSG:'                   ),
	MAG_SUBST                => array( 0,    'SUBST:'                 ),
	MAG_MSGNW                => array( 0,    'MSGNW:'                 ),
	MAG_END                  => array( 0,    '__END__'                ),
	MAG_IMG_THUMBNAIL        => array( 1,    'thumbnail', 'thumb'     ),
	MAG_IMG_RIGHT            => array( 1,    'right'                  ),
	MAG_IMG_LEFT             => array( 1,    'left'                   ),
	MAG_IMG_NONE             => array( 1,    'none'                   ),
	MAG_IMG_WIDTH            => array( 1,    '$1px'                   ),
	MAG_IMG_CENTER           => array( 1,    'center', 'centre'       ),
	MAG_IMG_FRAMED           => array( 1,    'framed', 'enframed', 'frame' ),
	MAG_INT                  => array( 0,    'INT:'                   ),
	MAG_SITENAME             => array( 1,    'SITENAME'               ),
	MAG_NS                   => array( 0,    'NS:'                    ),
	MAG_LOCALURL             => array( 0,    'LOCALURL:'              ),
	MAG_LOCALURLE            => array( 0,    'LOCALURLE:'             ),
	MAG_SERVER               => array( 0,    'SERVER'                 ),
	MAG_SERVERNAME           => array( 0,    'SERVERNAME'             ),
	MAG_SCRIPTPATH           => array( 0,    'SCRIPTPATH'             ),
	MAG_GRAMMAR              => array( 0,    'GRAMMAR:'               ),
	MAG_NOTITLECONVERT       => array( 0,    '__NOTITLECONVERT__', '__NOTC__'),
	MAG_NOCONTENTCONVERT     => array( 0,    '__NOCONTENTCONVERT__', '__NOCC__'),
	MAG_CURRENTWEEK          => array( 1,    'CURRENTWEEK'            ),
	MAG_CURRENTDOW           => array( 1,    'CURRENTDOW'             ),
	MAG_REVISIONID           => array( 1,    'REVISIONID'             ),
);

#-------------------------------------------------------------------
# Default messages
#-------------------------------------------------------------------
# Allowed characters in keys are: A-Z, a-z, 0-9, underscore (_) and
# hyphen (-). If you need more characters, you may be able to change
# the regex in MagicWord::initRegex

# required for copyrightwarning
global $wgRightsText;

/* private */ $wgAllMessagesEn = array(

# The navigation toolbar, int: is used here to make sure that the appropriate
# messages are automatically pulled from the user-selected language file.

/*
The sidebar for MonoBook is generated from this message, lines that do not
begin with * or ** are discarded, furthermore lines that do begin with ** and
do not contain | are also discarded, but don't depend on this behaviour for
future releases. Also note that since each list value is wrapped in a unique
XHTML id it should only appear once and include characters that are legal
XHTML id names.

Note to translators: Do not include this message in the language files you
submit for inclusion in MediaWiki, it should always be inherited from the
parent class in order maintain consistency across languages.
*/
'sidebar' => '
* navigation
** mainpage|mainpage
** portal-url|portal
** currentevents-url|currentevents
** recentchanges-url|recentchanges
** randompage-url|randompage
** helppage|help
** sitesupport-url|sitesupport
',

# User preference toggles
'tog-underline' => 'Underline links',
'tog-highlightbroken' => 'Format broken links <a href="" class="new">like this</a> (alternative: like this<a href="" class="internal">?</a>).',
'tog-justify'	=> 'Justify paragraphs',
'tog-hideminor' => 'Hide minor edits in recent changes',
'tog-usenewrc' => 'Enhanced recent changes (JavaScript)',
'tog-numberheadings' => 'Auto-number headings',
'tog-showtoolbar'		=> 'Show edit toolbar (JavaScript)',
'tog-editondblclick' => 'Edit pages on double click (JavaScript)',
'tog-editsection'		=> 'Enable section editing via [edit] links',
'tog-editsectiononrightclick'	=> 'Enable section editing by right clicking<br /> on section titles (JavaScript)',
'tog-showtoc'			=> 'Show table of contents (for pages with more than 3 headings)',
'tog-rememberpassword' => 'Remember across sessions',
'tog-editwidth' => 'Edit box has full width',
'tog-watchdefault' => 'Add pages you edit to your watchlist',
'tog-minordefault' => 'Mark all edits minor by default',
'tog-previewontop' => 'Show preview before edit box',
'tog-previewonfirst' => 'Show preview on first edit',
'tog-nocache' => 'Disable page caching',
'tog-enotifwatchlistpages' 	=> 'Send me an email on page changes',
'tog-enotifusertalkpages' 	=> 'Send me an email when my user talk page is changed',
'tog-enotifminoredits' 		=> 'Send me an email also for minor edits of pages',
'tog-enotifrevealaddr' 		=> 'Reveal my email address in notification mails',
'tog-shownumberswatching' 	=> 'Show the number of watching users',
'tog-fancysig' => 'Raw signatures (without automatic link)',
'tog-externaleditor' => 'Use external editor by default',
'tog-externaldiff' => 'Use external diff by default',

'underline-always' => 'Always',
'underline-never' => 'Never',
'underline-default' => 'Browser default',

'skinpreview' => '(Preview)',

# dates
'sunday' => 'Sunday',
'monday' => 'Monday',
'tuesday' => 'Tuesday',
'wednesday' => 'Wednesday',
'thursday' => 'Thursday',
'friday' => 'Friday',
'saturday' => 'Saturday',
'january' => 'January',
'february' => 'February',
'march' => 'March',
'april' => 'April',
'may_long' => 'May',
'june' => 'June',
'july' => 'July',
'august' => 'August',
'september' => 'September',
'october' => 'October',
'november' => 'November',
'december' => 'December',
'jan' => 'Jan',
'feb' => 'Feb',
'mar' => 'Mar',
'apr' => 'Apr',
'may' => 'May',
'jun' => 'Jun',
'jul' => 'Jul',
'aug' => 'Aug',
'sep' => 'Sep',
'oct' => 'Oct',
'nov' => 'Nov',
'dec' => 'Dec',
# Bits of text used by many pages:
#
'categories' => 'Categories',
'category' => 'category',
'category_header' => 'Articles in category "$1"',
'subcategories' => 'Subcategories',


'linktrail'		=> '/^([a-z]+)(.*)$/sD',
'linkprefix'		=> '/^(.*?)([a-zA-Z\x80-\xff]+)$/sD',
'mainpage'		=> 'Main Page',
'mainpagetext'	=> "<big>'''MediaWiki has been successfully installed.'''</big>",
'mainpagedocfooter' => "Consult the [http://meta.wikipedia.org/wiki/MediaWiki_User%27s_Guide User's Guide] for information on using the wiki software.

== Getting started ==

* [http://www.mediawiki.org/wiki/Help:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Help:FAQ MediaWiki FAQ]
* [http://mail.wikipedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]",

'portal'		=> 'Community portal',
'portal-url'	=> 'Project:Community Portal',
'about'			=> 'About',
'aboutsite'		=> 'About {{SITENAME}}',
'aboutpage'		=> 'Project:About',
'article'		=> 'Content page',
'help'			=> 'Help',
'helppage'		=> 'Help:Contents',
'bugreports'	=> 'Bug reports',
'bugreportspage' => 'Project:Bug_reports',
'sitesupport'   => 'Donations',
'sitesupport-url' => 'Project:Site support',
'faq'			=> 'FAQ',
'faqpage'		=> 'Project:FAQ',
'edithelp'		=> 'Editing help',
'newwindow'		=> '(opens in new window)',
'edithelppage'	=> 'Help:Editing',
'cancel'		=> 'Cancel',
'qbfind'		=> 'Find',
'qbbrowse'		=> 'Browse',
'qbedit'		=> 'Edit',
'qbpageoptions' => 'This page',
'qbpageinfo'	=> 'Context',
'qbmyoptions'	=> 'My pages',
'qbspecialpages'	=> 'Special pages',
'moredotdotdot'	=> 'More...',
'mypage'		=> 'My page',
'mytalk'		=> 'My talk',
'anontalk'		=> 'Talk for this IP',
'navigation' => 'Navigation',

# Metadata in edit box
'metadata' => '<b>Metadata</b> (for an explanation see <a href="$1">here</a>)',
'metadata_page' => 'Wikipedia:Metadata',

'currentevents' => 'Current events',
'currentevents-url' => 'Current events',

'disclaimers' => 'Disclaimers',
'disclaimerpage' => "Project:General_disclaimer",
'errorpagetitle' => "Error",
'returnto'		=> "Return to $1.",
'tagline'      	=> "From {{SITENAME}}",
'whatlinkshere'	=> 'Pages that link here',
'help'			=> 'Help',
'search'		=> 'Search',
'go'		=> 'Go',
"history"		=> 'Page history',
'history_short' => 'History',
'info_short'	=> 'Information',
'printableversion' => 'Printable version',
'print' => 'Print',
'edit' => 'Edit',
'editthispage'	=> 'Edit this page',
'delete' => 'Delete',
'deletethispage' => 'Delete this page',
'undelete_short1' => 'Undelete one edit',
'undelete_short' => 'Undelete $1 edits',
'protect' => 'Protect',
'protectthispage' => 'Protect this page',
'unprotect' => 'unprotect',
'unprotectthispage' => 'Unprotect this page',
'newpage' => 'New page',
'talkpage'		=> 'Discuss this page',
'specialpage' => 'Special Page',
'personaltools' => 'Personal tools',
'postcomment'   => 'Post a comment',
'addsection'   => '+',
'articlepage'	=> 'View content page',
'subjectpage'	=> 'View subject', # For compatibility
'talk' => 'Discussion',
'views' => 'Views',
'toolbox' => 'Toolbox',
'userpage' => 'View user page',
'wikipediapage' => 'View project page',
'imagepage' => 	'View image page',
'viewtalkpage' => 'View discussion',
'otherlanguages' => 'In other languages',
'redirectedfrom' => '(Redirected from $1)',
'lastmodified'	=> 'This page was last modified $1.',
'viewcount'		=> 'This page has been accessed $1 times.',
'copyright'	=> 'Content is available under $1.',
'poweredby'	=> "{{SITENAME}} is powered by [http://www.mediawiki.org/ MediaWiki], an open source wiki engine.",
'printsubtitle' => "(From {{SERVER}})",
'protectedpage' => 'Protected page',
'administrators' => "Project:Administrators",

'sysoptitle'	=> 'Sysop access required',
'sysoptext'		=> "The action you have requested can only be
performed by users with \"sysop\" capability.
See $1.",
'developertitle' => 'Developer access required',
'developertext'	=> "The action you have requested can only be
performed by users with \"developer\" capability.
See $1.",

'badaccess'     => 'Permission error',
'badaccesstext' => 'The action you have requested is limited
to users with the "$2" permission assigned.
See $1.',

'versionrequired' => 'Version $1 of MediaWiki required',
'versionrequiredtext' => 'Version $1 of MediaWiki is required to use this page. See [[Special:Version]]',

'nbytes'		=> '$1 bytes',
'ok'			=> 'OK',
'sitetitle'		=> "{{SITENAME}}",
'pagetitle'		=> "$1 - {{SITENAME}}",
'sitesubtitle'	=> 'The Free Encyclopedia', # FIXME
'retrievedfrom' => "Retrieved from \"$1\"",
'newmessages' => "You have $1.",
'newmessageslink' => 'new messages',
'editsection'=>'edit',
'toc' => 'Contents',
'showtoc' => 'show',
'hidetoc' => 'hide',
'thisisdeleted' => "View or restore $1?",
'restorelink1' => 'one deleted edit',
'restorelink' => "$1 deleted edits",
'feedlinks' => 'Feed:',
'sitenotice'	=> '-', # the equivalent to wgSiteNotice

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main' => 'Article',
'nstab-user' => 'User page',
'nstab-media' => 'Media page',
'nstab-special' => 'Special',
'nstab-wp' => 'Project page',
'nstab-image' => 'File',
'nstab-mediawiki' => 'Message',
'nstab-template' => 'Template',
'nstab-help' => 'Help',
'nstab-category' => 'Category',

# Main script and global functions
#
'nosuchaction'	=> 'No such action',
'nosuchactiontext' => 'The action specified by the URL is not
recognized by the wiki',
'nosuchspecialpage' => 'No such special page',
'nospecialpagetext' => 'You have requested an invalid special page, a list of valid special pages may be found at [[{{ns:special}}:Specialpages]].',

# General errors
#
'error'			=> 'Error',
'databaseerror' => 'Database error',
'dberrortext'	=> "A database query syntax error has occurred.
This may indicate a bug in the software.
The last attempted database query was:
<blockquote><tt>$1</tt></blockquote>
from within function \"<tt>$2</tt>\".
MySQL returned error \"<tt>$3: $4</tt>\".",
'dberrortextcl' => "A database query syntax error has occurred.
The last attempted database query was:
\"$1\"
from within function \"$2\".
MySQL returned error \"$3: $4\".\n",
'noconnect'		=> 'Sorry! The wiki is experiencing some technical difficulties, and cannot contact the database server. <br />
$1',
'nodb'			=> "Could not select database $1",
'cachederror'		=> 'The following is a cached copy of the requested page, and may not be up to date.',
'laggedslavemode'   => 'Warning: Page may not contain recent updates.',
'readonly'		=> 'Database locked',
'enterlockreason' => 'Enter a reason for the lock, including an estimate
of when the lock will be released',
'readonlytext'	=> "The database is currently locked to new
entries and other modifications, probably for routine database maintenance,
after which it will be back to normal.
The administrator who locked it offered this explanation:
$1",
'missingarticle' => "The database did not find the text of a page
that it should have found, named \"$1\".

This is usually caused by following an outdated diff or history link to a
page that has been deleted.

If this is not the case, you may have found a bug in the software.
Please report this to an administrator, making note of the URL.",
'readonly_lag' => "The database has been automatically locked while the slave database servers catch up to the master",
'internalerror' => 'Internal error',
'filecopyerror' => "Could not copy file \"$1\" to \"$2\".",
'filerenameerror' => "Could not rename file \"$1\" to \"$2\".",
'filedeleteerror' => "Could not delete file \"$1\".",
'filenotfound'	=> "Could not find file \"$1\".",
'unexpected'	=> "Unexpected value: \"$1\"=\"$2\".",
'formerror'		=> 'Error: could not submit form',
'badarticleerror' => 'This action cannot be performed on this page.',
'cannotdelete'	=> 'Could not delete the page or file specified. (It may have already been deleted by someone else.)',
'badtitle'		=> 'Bad title',
'badtitletext' => "The requested page title was invalid, empty, or
an incorrectly linked inter-language or inter-wiki title.",
'perfdisabled' => 'Sorry! This feature has been temporarily disabled
because it slows the database down to the point that no one can use
the wiki.',
'perfdisabledsub' => "Here's a saved copy from $1:", # obsolete?
'perfcached' => 'The following data is cached and may not be completely up to date:',
'wrong_wfQuery_params' => "Incorrect parameters to wfQuery()<br />
Function: $1<br />
Query: $2
",
'viewsource' => 'View source',
'protectedtext' => "This page has been locked to prevent editing; there are
a number of reasons why this may be so, please see
[[Project:Protected page]].

You can view and copy the source of this page:",
'sqlhidden' => '(SQL query hidden)',

# Login and logout pages
#
'logouttitle'	=> 'User logout',
'logouttext' 		=> "You are now logged out.<br />
You can continue to use {{SITENAME}} anonymously, or you can log in
again as the same or as a different user. Note that some pages may
continue to be displayed as if you were still logged in, until you clear
your browser cache.\n",

'welcomecreation' => "== Welcome, $1! ==

Your account has been created. Don't forget to change your {{SITENAME}} preferences.",

'loginpagetitle' => 'User login',
'yourname'		=> 'Username',
'yourpassword'	=> 'Password',
'yourpasswordagain' => 'Retype password',
'newusersonly'	=> ' (new users only)',
'remembermypassword' => 'Remember me',
'yourdomainname'       => 'Your domain',
'externaldberror'      => 'There was either an external authentication database error or you are not allowed to update your external account.',
'loginproblem'	=> '<b>There has been a problem with your login.</b><br />Try again!',
'alreadyloggedin' => "<strong>User $1, you are already logged in!</strong><br />\n",

'login'			=> 'Log in',
'loginprompt'           => "You must have cookies enabled to log in to {{SITENAME}}.",
'userlogin'		=> 'Create an account or log in',
'logout'		=> 'Log out',
'userlogout'	=> 'Log out',
'notloggedin'	=> 'Not logged in',
'createaccount'	=> 'Create new account',
'createaccountmail'	=> 'by email',
'badretype'		=> 'The passwords you entered do not match.',
'userexists'	=> 'The user name you entered is already in use. Please choose a different name.',
'youremail'		=> 'Email *',
'yourrealname'		=> 'Real name *',
'yourlanguage'	=> 'Language',
'yourvariant'  => 'Variant',
'yournick'		=> 'Nickname',
'email'			=> 'Email',
'emailforlost'		=> "Fields marked with superscripts are optional.  Storing an email address enables people to contact you through the website without you having to reveal your
email address to them, and it can be used to send you a new password if you forget it.<br /><br />Your real name, if you choose to provide it, will be used for giving you attribution for your work.",
'prefs-help-email-enotif' => 'This address is also used to send you email notifications if you enabled the options.',
'prefs-help-realname' 	=> '* Real name (optional): if you choose to provide it this will be used for giving you attribution for your work.',
'loginerror'	=> 'Login error',
'prefs-help-email'      => '* Email (optional): Enables others to contact you through your user or user_talk page without the need of revealing your identity.',
'nocookiesnew'	=> "The user account was created, but you are not logged in. {{SITENAME}} uses cookies to log in users. You have cookies disabled. Please enable them, then log in with your new username and password.",
'nocookieslogin'	=> "{{SITENAME}} uses cookies to log in users. You have cookies disabled. Please enable them and try again.",
'noname'		=> 'You have not specified a valid user name.',
'loginsuccesstitle' => 'Login successful',
'loginsuccess'	=> "You are now logged in to {{SITENAME}} as \"$1\".",
'nosuchuser'	=> "There is no user by the name \"$1\".
Check your spelling, or use the form below to create a new user account.",
'nosuchusershort'	=> "There is no user by the name \"$1\". Check your spelling.",
'wrongpassword'		=> 'The password you entered is incorrect (or missing). Please try again.',
'mailmypassword' 	=> 'Mail me a new password',
'passwordremindertitle' => "Password reminder from {{SITENAME}}",
'passwordremindertext' => "Someone (probably you, from IP address $1)
requested that we send you a new {{SITENAME}} login password.
The password for user \"$2\" is now \"$3\".
You should log in and change your password now.",
'noemail'		            => "There is no e-mail address recorded for user \"$1\".",
'passwordsent'	=> "A new password has been sent to the e-mail address
registered for \"$1\".
Please log in again after you receive it.",
'eauthentsent'             =>  "A confirmation email has been sent to the nominated email address.
Before any other mail is sent to the account, you will have to follow the instructions in the email,
to confirm that the account is actually yours.",
'loginend'		            => '&nbsp;',
'mailerror'                 => "Error sending mail: $1",
'acct_creation_throttle_hit' => 'Sorry, you have already created $1 accounts. You can\'t make any more.',
'emailauthenticated'        => 'Your email address was authenticated on $1.',
'emailnotauthenticated'     => 'Your email address is <strong>not yet authenticated</strong>. No email
will be sent for any of the following features.',
'noemailprefs'              => '<strong>No email address has been specified</strong>, the following
features will not work.',
'emailconfirmlink' => 'Confirm your e-mail address',
'invalidemailaddress'	=> 'The email address cannot be accepted as it appears to have an invalid
format. Please enter a well-formatted address or empty that field.',

# Edit page toolbar
'bold_sample'=>'Bold text',
'bold_tip'=>'Bold text',
'italic_sample'=>'Italic text',
'italic_tip'=>'Italic text',
'link_sample'=>'Link title',
'link_tip'=>'Internal link',
'extlink_sample'=>'http://www.example.com link title',
'extlink_tip'=>'External link (remember http:// prefix)',
'headline_sample'=>'Headline text',
'headline_tip'=>'Level 2 headline',
'math_sample'=>'Insert formula here',
'math_tip'=>'Mathematical formula (LaTeX)',
'nowiki_sample'=>'Insert non-formatted text here',
'nowiki_tip'=>'Ignore wiki formatting',
'image_sample'=>'Example.jpg',
'image_tip'=>'Embedded image',
'media_sample'=>'Example.ogg',
'media_tip'=>'Media file link',
'sig_tip'=>'Your signature with timestamp',
'hr_tip'=>'Horizontal line (use sparingly)',
'infobox'=>'Click a button to get an example text',
# alert box shown in browsers where text selection does not work, test e.g. with mozilla or konqueror
'infobox_alert'=>"Please enter the text you want to be formatted.\\n It will be shown in the infobox for copy and pasting.\\nExample:\\n$1\\nwill become:\\n$2",

# Edit pages
#
'summary'		=> 'Summary',
'subject'		=> 'Subject/headline',
'minoredit'		=> 'This is a minor edit',
'watchthis'		=> 'Watch this page',
'savearticle'	=> 'Save page',
'preview'		=> 'Preview',
'showpreview'	=> 'Show preview',
'showdiff'	=> 'Show changes',
'blockedtitle'	=> 'User is blocked',
'blockedtext'	=> "Your user name or IP address has been blocked by $1.
The reason given is this:<br />''$2''<p>You may contact $1 or one of the other
[[Project:Administrators|administrators]] to discuss the block.

Note that you may not use the \"email this user\" feature unless you have a valid email address registered in your [[Special:Preferences|user preferences]].

Your IP address is $3. Please include this address in any queries you make.
",
'whitelistedittitle' => 'Login required to edit',
'whitelistedittext' => 'You have to [[Special:Userlogin|login]] to edit pages.',
'whitelistreadtitle' => 'Login required to read',
'whitelistreadtext' => 'You have to [[Special:Userlogin|login]] to read pages.',
'whitelistacctitle' => 'You are not allowed to create an account',
'whitelistacctext' => 'To be allowed to create accounts in this Wiki you have to [[Special:Userlogin|log]] in and have the appropriate permissions.',
'loginreqtitle'	=> 'Login Required',
'loginreqtext'	=> 'You must [[special:Userlogin|login]] to view other pages.',
'accmailtitle' => 'Password sent.',
'accmailtext' => "The password for '$1' has been sent to $2.",
'newarticle'	=> '(New)',
'newarticletext' =>
"You've followed a link to a page that doesn't exist yet.
To create the page, start typing in the box below
(see the [[Project:Help|help page]] for more info).
If you are here by mistake, just click your browser's '''back''' button.",
'talkpagetext' => '<!-- MediaWiki:talkpagetext -->',
'anontalkpagetext' => "----''This is the discussion page for an anonymous user who has not created an account yet or who does not use it. We therefore have to use the numerical [[IP address]] to identify him/her. Such an IP address can be shared by several users. If you are an anonymous user and feel that irrelevant comments have been directed at you, please [[Special:Userlogin|create an account or log in]] to avoid future confusion with other anonymous users.'' ",
'noarticletext' => '(There is currently no text in this page)',
'clearyourcache' => "'''Note:''' After saving, you may have to bypass your browser's cache to see the changes. '''Mozilla / Firefox / Safari:''' hold down ''Shift'' while clicking ''Reload'', or press ''Ctrl-Shift-R'' (''Cmd-Shift-R'' on Apple Mac); '''IE:''' hold ''Ctrl'' while clicking ''Refresh'', or press ''Ctrl-F5''; '''Konqueror:''': simply click the ''Reload'' button, or press ''F5''; '''Opera''' users may need to completely clear their cache in ''Tools&rarr;Preferences''.",
'usercssjsyoucanpreview' => "<strong>Tip:</strong> Use the 'Show preview' button to test your new CSS/JS before saving.",
'usercsspreview' => "'''Remember that you are only previewing your user CSS, it has not yet been saved!'''",
'userjspreview' => "'''Remember that you are only testing/previewing your user JavaScript, it has not yet been saved!'''",
'updated'		=> '(Updated)',
'note'			=> '<strong>Note:</strong> ',
'previewnote'	=> 'Remember that this is only a preview, and has not yet been saved!',
'previewconflict' => 'This preview reflects the text in the upper
text editing area as it will appear if you choose to save.',
'editing'		=> "Editing $1",
'editingsection'		=> "Editing $1 (section)",
'editingcomment'		=> "Editing $1 (comment)",
'editconflict'	=> 'Edit conflict: $1',
'explainconflict' => "Someone else has changed this page since you
started editing it.
The upper text area contains the page text as it currently exists.
Your changes are shown in the lower text area.
You will have to merge your changes into the existing text.
<b>Only</b> the text in the upper text area will be saved when you
press \"Save page\".<br />",
'yourtext'		=> 'Your text',
'storedversion' => 'Stored version',
'nonunicodebrowser' => "<strong>WARNING: Your browser is not unicode compliant. A workaround is in place to allow you to safely edit articles: non-ASCII characters will appear in the edit box as hexadecimal codes.</strong>",
'editingold'	=> "<strong>WARNING: You are editing an out-of-date
revision of this page.
If you save it, any changes made since this revision will be lost.</strong>",
'yourdiff'		=> 'Differences',
'copyrightwarning' => "Please note that all contributions to {{SITENAME}} are
considered to be released under the $2 (see $1 for details).
If you don't want your writing to be edited mercilessly and redistributed
at will, then don't submit it here.<br />
You are also promising us that you wrote this yourself, or copied it from a
public domain or similar free resource.
<strong>DO NOT SUBMIT COPYRIGHTED WORK WITHOUT PERMISSION!</strong>",
'copyrightwarning2' => "Please note that all contributions to {{SITENAME}}
may be edited, altered, or removed by other contributors.
If you don't want your writing to be edited mercilessly, then don't submit it here.<br />
You are also promising us that you wrote this yourself, or copied it from a
public domain or similar free resource (see $1 for details).
<strong>DO NOT SUBMIT COPYRIGHTED WORK WITHOUT PERMISSION!</strong>",
'longpagewarning' => "<strong>WARNING: This page is $1 kilobytes long; some
browsers may have problems editing pages approaching or longer than 32kb.
Please consider breaking the page into smaller sections.</strong>",
'readonlywarning' => '<strong>WARNING: The database has been locked for maintenance,
so you will not be able to save your edits right now. You may wish to cut-n-paste
the text into a text file and save it for later.</strong>',
'protectedpagewarning' => "<strong>WARNING:  This page has been locked so that only users with sysop privileges can edit it. Be sure you are following the [[Project:Protected_page_guidelines|protected page guidelines]].</strong>",
'templatesused'	=> 'Templates used on this page:',

# History pages
#
'revhistory'	=> 'Revision history',
'nohistory'		=> 'There is no edit history for this page.',
'revnotfound'	=> 'Revision not found',
'revnotfoundtext' => "The old revision of the page you asked for could not be found.
Please check the URL you used to access this page.\n",
'loadhist'		=> 'Loading page history',
'currentrev'	=> 'Current revision',
'revisionasof'          => 'Revision as of $1',
'revisionasofwithlink'  => 'Revision as of $1; $2<br />$3 | $4',
'previousrevision'	=> '←Older revision',
'nextrevision'		=> 'Newer revision→',
'currentrevisionlink'   => 'view current revision',
'cur'			=> 'cur',
'next'			=> 'next',
'last'			=> 'last',
'orig'			=> 'orig',
'histlegend'	=> 'Diff selection: mark the radio boxes of the versions to compare and hit enter or the button at the bottom.<br />
Legend: (cur) = difference with current version,
(last) = difference with preceding version, M = minor edit.',
'history_copyright'    => '-',
'deletedrev' => '[deleted]',
'histfirst' => 'Earliest',
'histlast' => 'Latest',

# Diffs
#
'difference'	=> '(Difference between revisions)',
'loadingrev'	=> 'loading revision for diff',
'lineno'		=> "Line $1:",
'editcurrent'	=> 'Edit the current version of this page',
'selectnewerversionfordiff' => 'Select a newer version for comparison',
'selectolderversionfordiff' => 'Select an older version for comparison',
'compareselectedversions' => 'Compare selected versions',

# Search results
#
'searchresults' => 'Search results',
'searchresulttext' => "For more information about searching {{SITENAME}}, see [[Project:Searching|Searching {{SITENAME}}]].",
'searchquery'	=> "For query \"$1\"",
'badquery'		=> 'Badly formed search query',
'badquerytext'	=> 'We could not process your query.
This is probably because you have attempted to search for a
word fewer than three letters long, which is not yet supported.
It could also be that you have mistyped the expression, for
example "fish and and scales".
Please try another query.',
'matchtotals'	=> "The query \"$1\" matched $2 page titles
and the text of $3 pages.",
'nogomatch' => "'''There is no page titled \"$1\".''' You can [[$1|create this page]].",
'titlematches'	=> 'Article title matches',
'notitlematches' => 'No page title matches',
'textmatches'	=> 'Page text matches',
'notextmatches'	=> 'No page text matches',
'prevn'			=> "previous $1",
'nextn'			=> "next $1",
'viewprevnext'	=> "View ($1) ($2) ($3).",
'showingresults' => "Showing below up to <b>$1</b> results starting with #<b>$2</b>.",
'showingresultsnum' => "Showing below <b>$3</b> results starting with #<b>$2</b>.",
'nonefound'		=> "'''Note''': unsuccessful searches are
often caused by searching for common words like \"have\" and \"from\",
which are not indexed, or by specifying more than one search term (only pages
containing all of the search terms will appear in the result).",
'powersearch' => 'Search',
'powersearchtext' => "
Search in namespaces :<br />
$1<br />
$2 List redirects &nbsp; Search for $3 $9",
"searchdisabled" => '{{SITENAME}} search is disabled. You can search via Google in the meantime. Note that their indexes of {{SITENAME}} content may be out of date.',

'googlesearch' => '
<form method="get" action="http://www.google.com/search" id="googlesearch">
    <input type="hidden" name="domains" value="{{SERVER}}" />
    <input type="hidden" name="num" value="50" />
    <input type="hidden" name="ie" value="$2" />
    <input type="hidden" name="oe" value="$2" />

    <input type="text" name="q" size="31" maxlength="255" value="$1" />
    <input type="submit" name="btnG" value="$3" />
  <div>
    <input type="radio" name="sitesearch" id="gwiki" value="{{SERVER}}" checked="checked" /><label for="gwiki">{{SITENAME}}</label>
    <input type="radio" name="sitesearch" id="gWWW" value="" /><label for="gWWW">WWW</label>
  </div>
</form>',
'blanknamespace' => '(Main)',

# Preferences page
#
'preferences'	=> 'Preferences',
'prefsnologin' => 'Not logged in',
'prefsnologintext'	=> "You must be [[Special:Userlogin|logged in]] to set user preferences.",
'prefslogintext' => "You are logged in as \"$1\".
Your internal ID number is $2.

See [[Project:User preferences help]] for help deciphering the options.",
'prefsreset'	=> 'Preferences have been reset from storage.',
'qbsettings'	=> 'Quickbar',
'changepassword' => 'Change password',
'skin'			=> 'Skin',
'math'			=> 'Math',
'dateformat'		=> 'Date format',
'math_failure'		=> 'Failed to parse',
'math_unknown_error'	=> 'unknown error',
'math_unknown_function'	=> 'unknown function ',
'math_lexing_error'	=> 'lexing error',
'math_syntax_error'	=> 'syntax error',
'math_image_error'	=> 'PNG conversion failed; check for correct installation of latex, dvips, gs, and convert',
'math_bad_tmpdir'	=> 'Can\'t write to or create math temp directory',
'math_bad_output'	=> 'Can\'t write to or create math output directory',
'math_notexvc'	=> 'Missing texvc executable; please see math/README to configure.',
'prefs-personal' => 'User data',
'prefs-rc' => 'Recent changes & stubs',
'prefs-misc' => 'Misc',
'saveprefs'		=> 'Save',
'resetprefs'	=> 'Reset',
'oldpassword'	=> 'Old password',
'newpassword'	=> 'New password',
'retypenew'		=> 'Retype new password',
'textboxsize'	=> 'Editing',
'rows'			=> 'Rows',
'columns'		=> 'Columns',
'searchresultshead' => 'Search',
'resultsperpage' => 'Hits per page',
'contextlines'	=> 'Lines per hit',
'contextchars'	=> 'Context per line',
'stubthreshold' => 'Threshold for stub display',
'recentchangescount' => 'Titles in recent changes',
'savedprefs'	=> 'Your preferences have been saved.',
'timezonelegend' => 'Time zone',
'timezonetext'	=> 'The number of hours your local time differs from server time (UTC).',
'localtime'	=> 'Local time',
'timezoneoffset' => 'Offset¹',
'servertime'	=> 'Server time',
'guesstimezone' => 'Fill in from browser',
'emailflag'		=> 'Disable e-mail from other users',
'defaultns'		=> 'Search in these namespaces by default:',
'default'		=> 'default',
'files'			=> 'Files',

# User levels special page
#

# switching pan
'groups-lookup-group' => 'Manage group rights',
'groups-group-edit' => 'Existing groups:',
'editgroup' => 'Edit Group',
'addgroup' => 'Add Group',

'userrights-lookup-user' => 'Manage user groups',
'userrights-user-editname' => 'Enter a username: ',
'editusergroup' => 'Edit User Groups',

# group editing
'groups-editgroup'          => 'Edit group',
'groups-addgroup'           => 'Add group',
'groups-editgroup-preamble' => 'If the name or description starts with a colon, the
remainder will be treated as a message name, and hence the text will be localised
using the MediaWiki namespace',
'groups-editgroup-name'     => 'Group name:',
'groups-editgroup-description' => 'Group description (max 255 characters):<br />',
'savegroup'                 => 'Save Group',
'groups-tableheader'        => 'ID || Name || Description || Rights',
'groups-existing'           => 'Existing groups',
'groups-noname'             => 'Please specify a valid group name',
'groups-already-exists'     => 'A group of that name already exists',
'addgrouplogentry'          => 'Added group $2',
'changegrouplogentry'       => 'Changed group $2',
'renamegrouplogentry'       => 'Renamed group $2 to $3',

# user groups editing
#
'userrights-editusergroup' => 'Edit user groups',
'saveusergroups' => 'Save User Groups',
'userrights-groupsmember' => 'Member of:',
'userrights-groupsavailable' => 'Available groups:',
'userrights-groupshelp' => 'Select groups you want the user to be removed from or added to.
Unselected groups will not be changed. You can deselect a group with CTRL + Left Click',
'userrights-logcomment' => 'Changed group membership from $1 to $2',

# Default group names and descriptions
#
'group-anon-name'       => 'Anonymous',
'group-anon-desc'       => 'Anonymous users',
'group-loggedin-name'   => 'User',
'group-loggedin-desc'   => 'General logged in users',
'group-admin-name'      => 'Administrator',
'group-admin-desc'      => 'Trusted users able to block users and delete articles',
'group-bureaucrat-name' => 'Bureaucrat',
'group-bureaucrat-desc' => 'The bureaucrat group is able to make sysops',
'group-steward-name'    => 'Steward',
'group-steward-desc'    => 'Full access',


# Recent changes
#
'changes' => 'changes',
'recentchanges' => 'Recent changes',
'recentchanges-url' => 'Special:Recentchanges',
'recentchangestext' => 'Track the most recent changes to the wiki on this page.',
'rcloaderr'		=> 'Loading recent changes',
'rcnote'		=> "Below are the last <strong>$1</strong> changes in last <strong>$2</strong> days.",
'rcnotefrom'	=> "Below are the changes since <b>$2</b> (up to <b>$1</b> shown).",
'rclistfrom'	=> "Show new changes starting from $1",
'showhideminor' => "$1 minor edits | $2 bots | $3 logged in users | $4 patrolled edits ",
'rclinks'		=> "Show last $1 changes in last $2 days<br />$3",
'rchide'		=> "in $4 form; $1 minor edits; $2 secondary namespaces; $3 multiple edits.",
'rcliu'			=> "; $1 edits from logged in users",
'diff'			=> 'diff',
'hist'			=> 'hist',
'hide'			=> 'Hide',
'show'			=> 'show',
'tableform'		=> 'table',
'listform'		=> 'list',
'nchanges'		=> "$1 changes",
'minoreditletter' => 'm',
'newpageletter' => 'N',
'sectionlink' => '→',
'number_of_watching_users_RCview' 	=> '[$1]',
'number_of_watching_users_pageview' 	=> '[$1 watching user/s]',

# Upload
#
'upload'		=> 'Upload file',
'uploadbtn'		=> 'Upload file',
'uploadlink'	=> 'Upload images',
'reupload'		=> 'Re-upload',
'reuploaddesc'	=> 'Return to the upload form.',
'uploadnologin' => 'Not logged in',
'uploadnologintext'	=> "You must be [[Special:Userlogin|logged in]]
to upload files.",
'upload_directory_read_only' => 'The upload directory ($1) is not writable by the webserver.',
'uploaderror'	=> 'Upload error',
'uploadtext'	=>
"
Use the form below to upload new files,
to view or search previously uploaded images
go to the [[Special:Imagelist|list of uploaded files]],
uploads and deletions are also logged in the [[Special:Log|project log]].

You must also check the box affirming that you are not
violating any copyrights by uploading the file.
Press the \"Upload\" button to finish the upload.

To include the image in a page, use a link in the form
'''<nowiki>[[{{ns:6}}:file.jpg]]</nowiki>''',
'''<nowiki>[[{{ns:6}}:file.png|alt text]]</nowiki>''' or
'''<nowiki>[[{{ns:-2}}:file.ogg]]</nowiki>''' for directly linking to the file.
",

'uploadlog'		=> 'upload log',
'uploadlogpage' => 'Upload_log',
'uploadlogpagetext' => 'Below is a list of the most recent file uploads.',
'filename'		=> 'Filename',
'filedesc'		=> 'Summary',
'filestatus' => 'Copyright status',
'filesource' => 'Source',
'copyrightpage' => "Project:Copyrights",
'copyrightpagename' => "{{SITENAME}} copyright",
'uploadedfiles'	=> 'Uploaded files',
'ignorewarning'	=> 'Ignore warning and save file anyway.',
'minlength'		=> 'File names must be at least three letters.',
'illegalfilename'	=> 'The filename "$1" contains characters that are not allowed in page titles. Please rename the file and try uploading it again.',
'badfilename'	=> 'File name has been changed to "$1".',
'badfiletype'	=> "\".$1\" is not a recommended image file format.",
'largefile'		=> 'It is recommended that images not exceed $1 bytes in size, this file is $2 bytes',
'emptyfile'		=> 'The file you uploaded seems to be empty. This might be due to a typo in the file name. Please check whether you really want to upload this file.',
'fileexists'		=> 'A file with this name exists already, please check $1 if you are not sure if you want to change it.',
'successfulupload' => 'Successful upload',
'fileuploaded'	=> "File $1 uploaded successfully.
Please follow this link: $2 to the description page and fill
in information about the file, such as where it came from, when it was
created and by whom, and anything else you may know about it. If this is an image, you can insert it like this: <tt><nowiki>[[Image:$1|thumb|Description]]</nowiki></tt>",
'uploadwarning' => 'Upload warning',
'savefile'		=> 'Save file',
'uploadedimage' => "uploaded \"[[$1]]\"",
'uploaddisabled' => 'Sorry, uploading is disabled.',
'uploadscripted' => 'This file contains HTML or script code that may be erroneously be interpreted by a web browser.',
'uploadcorrupt' => 'The file is corrupt or has an incorrect extension. Please check the file and upload again.',
'uploadvirus' => 'The file contains a virus! Details: $1',
'sourcefilename' => 'Source filename',
'destfilename' => 'Destination filename',

# Image list
#
'imagelist'		=> 'File list',
'imagelisttext'	=> "Below is a list of $1 files sorted $2.",
'getimagelist'	=> 'fetching file list',
'ilsubmit'		=> 'Search',
'showlast'		=> "Show last $1 files sorted $2.",
'byname'		=> 'by name',
'bydate'		=> 'by date',
'bysize'		=> 'by size',
'imgdelete'		=> 'del',
'imgdesc'		=> 'desc',
'imglegend'		=> 'Legend: (desc) = show/edit file description.',
'imghistory'	=> 'File history',
'revertimg'		=> 'rev',
'deleteimg'		=> 'del',
'deleteimgcompletely'		=> 'Delete all revisions of this file',
'imghistlegend' => 'Legend: (cur) = this is the current file, (del) = delete
this old version, (rev) = revert to this old version.
<br /><i>Click on date to see the file uploaded on that date</i>.',
'imagelinks'	=> 'Links',
'linkstoimage'	=> 'The following pages link to this file:',
'nolinkstoimage' => 'There are no pages that link to this file.',
'sharedupload' => 'This file is a shared upload and may be used by other projects.',
'shareduploadwiki' => 'Please see the [$1 file description page] for further information.',
'shareddescriptionfollows' => '-',
'noimage'       => 'No file by this name exists, you can [$1 upload it]',
'uploadnewversion' => '[$1 Upload a new version of this file]',

# Statistics
#
'statistics'	=> 'Statistics',
'sitestats'		=> '{{SITENAME}} statistics',
'userstats'		=> 'User statistics',
'sitestatstext' => "There are '''$1''' total pages in the database.
This includes \"talk\" pages, pages about {{SITENAME}}, minimal \"stub\"
pages, redirects, and others that probably don't qualify as content pages.
Excluding those, there are '''$2''' pages that are probably legitimate
content pages.

There have been a total of '''$3''' page views, and '''$4''' page edits
since the wiki was setup.
That comes to '''$5''' average edits per page, and '''$6''' views per edit.",
'userstatstext' => "There are '''$1''' registered users, of which
'''$2''' (or '''$4%''') are administrators (see $3).",

# Maintenance Page
#
'maintenance'		=> 'Maintenance page',
'maintnancepagetext'	=> 'This page includes several handy tools for everyday maintenance. Some of these functions tend to stress the database, so please do not hit reload after every item you fixed ;-)',
'maintenancebacklink'	=> 'Back to Maintenance Page',
'disambiguations'	=> 'Disambiguation pages',
'disambiguationspage'	=> 'Template:disambig',
'disambiguationstext'	=> "The following pages link to a <i>disambiguation page</i>. They should link to the appropriate topic instead.<br />A page is treated as disambiguation if it is linked from $1.<br />Links from other namespaces are <i>not</i> listed here.",
'doubleredirects'	=> 'Double redirects',
'doubleredirectstext'	=> "Each row contains links to the first and second redirect, as well as the first line of the second redirect text, usually giving the \"real\" target page, which the first redirect should point to.",
'brokenredirects'	=> 'Broken Redirects',
'brokenredirectstext'	=> 'The following redirects link to a non-existing pages.',
'selflinks'		=> 'Pages with Self Links',
'selflinkstext'		    => 'The following pages contain a link to themselves, which they should not.',
'mispeelings'           => 'Pages with misspellings',
'mispeelingstext'               => "The following pages contain a common misspelling, which are listed on $1. The correct spelling might be given (like this).",
'mispeelingspage'       => 'List of common misspellings',
'missinglanguagelinks'  => 'Missing Language Links',
'missinglanguagelinksbutton'    => 'Find missing language links for',
'missinglanguagelinkstext'      => "These pages do <i>not</i> link to their counterpart in $1. Redirects and subpages are <i>not</i> shown.",


# Miscellaneous special pages
#
'orphans'		=> 'Orphaned pages',
'geo'		=> 'GEO coordinates',
'validate'		=> 'Validate page',
'lonelypages'	=> 'Orphaned pages',
'uncategorizedpages'	=> 'Uncategorized pages',
'uncategorizedcategories'	=> 'Uncategorized categories',
'unusedcategories' => 'Unused categories',
'unusedimages'	=> 'Unused files',
'popularpages'	=> 'Popular pages',
'nviews'		=> '$1 views',
'wantedpages'	=> 'Wanted pages',
'mostlinked'	=> 'Most linked to pages',
'nlinks'		=> '$1 links',
'allpages'		=> 'All pages',
'randompage'	=> 'Random page',
'randompage-url'=> 'Special:Random',
'shortpages'	=> 'Short pages',
'longpages'		=> 'Long pages',
'deadendpages'  => 'Dead-end pages',
'listusers'		=> 'User list',
'specialpages'	=> 'Special pages',
'spheading'		=> 'Special pages for all users',
'restrictedpheading'	=> 'Restricted special pages',
'protectpage'	=> 'Protect page',
'recentchangeslinked' => 'Related changes',
'rclsub'		=> "(to pages linked from \"$1\")",
'debug'			=> 'Debug',
'newpages'		=> 'New pages',
'ancientpages'		=> 'Oldest pages',
'intl'		=> 'Interlanguage links',
'move' => 'Move',
'movethispage'	=> 'Move this page',
'unusedimagestext' => '<p>Please note that other web sites may link to an image with
a direct URL, and so may still be listed here despite being
in active use.</p>',
'unusedcategoriestext' => 'The following category pages exist although no other article or category make use of them.',

'booksources'	=> 'Book sources',
'categoriespagetext' => 'The following categories exist in the wiki.',
'data'	=> 'Data',
'userrights' => 'User rights management',
'groups' => 'User groups',

'booksourcetext' => "Below is a list of links to other sites that
sell new and used books, and may also have further information
about books you are looking for.",
'isbn'	=> 'ISBN',
'rfcurl' =>  'http://www.ietf.org/rfc/rfc$1.txt',
'pubmedurl' =>  'http://www.ncbi.nlm.nih.gov/entrez/query.fcgi?cmd=Retrieve&db=pubmed&dopt=Abstract&list_uids=$1',
'alphaindexline' => "$1 to $2",
'version'		=> 'Version',
'log'		=> 'Logs',
'alllogstext'	=> 'Combined display of upload, deletion, protection, blocking, and sysop logs.
You can narrow down the view by selecting a log type, the user name, or the affected page.',

# Special:Allpages
'nextpage'          => 'Next page ($1)',
'allpagesfrom'		=> 'Display pages starting at:',
'allarticles'		=> 'All articles',
'allnonarticles'	=> 'All non-articles',
'allinnamespace'	=> 'All pages ($1 namespace)',
'allnotinnamespace'	=> 'All pages (not in $1 namespace)',
'allpagesprev'		=> 'Previous',
'allpagesnext'		=> 'Next',
'allpagessubmit'	=> 'Go',

# E this user
#
'mailnologin'	=> 'No send address',
'mailnologintext' => "You must be [[Special:Userlogin|logged in]]
and have a valid e-mail address in your [[Special:Preferences|preferences]]
to send e-mail to other users.",
'emailuser'		=> 'E-mail this user',
'emailpage'		=> 'E-mail user',
'emailpagetext'	=> 'If this user has entered a valid e-mail address in
his or her user preferences, the form below will send a single message.
The e-mail address you entered in your user preferences will appear
as the "From" address of the mail, so the recipient will be able
to reply.',
'usermailererror' => 'Mail object returned error: ',
'defemailsubject'  => "{{SITENAME}} e-mail",
'noemailtitle'	=> 'No e-mail address',
'noemailtext'	=> 'This user has not specified a valid e-mail address,
or has chosen not to receive e-mail from other users.',
'emailfrom'		=> 'From',
'emailto'		=> 'To',
'emailsubject'	=> 'Subject',
'emailmessage'	=> 'Message',
'emailsend'		=> 'Send',
'emailsent'		=> 'E-mail sent',
'emailsenttext' => 'Your e-mail message has been sent.',

# Watchlist
#
'watchlist'			=> 'My watchlist',
'watchlistsub'		=> "(for user \"$1\")",
'nowatchlist'		=> 'You have no items on your watchlist.',
'watchnologin'		=> 'Not logged in',
'watchnologintext'	=> 'You must be [[Special:Userlogin|logged in]] to modify your watchlist.',
'addedwatch'		=> 'Added to watchlist',
'addedwatchtext'	=> "The page \"$1\" has been added to your [[Special:Watchlist|watchlist]].
Future changes to this page and its associated Talk page will be listed there,
and the page will appear '''bolded''' in the [[Special:Recentchanges|list of recent changes]] to
make it easier to pick out.

<p>If you want to remove the page from your watchlist later, click \"Unwatch\" in the sidebar.",
'removedwatch'		=> 'Removed from watchlist',
'removedwatchtext' 	=> "The page \"$1\" has been removed from your watchlist.",
'watch' => 'Watch',
'watchthispage'		=> 'Watch this page',
'unwatch' => 'Unwatch',
'unwatchthispage' 	=> 'Stop watching',
'notanarticle'		=> 'Not a content page',
'watchnochange' 	=> 'None of your watched items was edited in the time period displayed.',
'watchdetails'		=> "* $1 pages watched not counting talk pages
* [[Special:Watchlist/edit|Show and edit complete watchlist]]
",
'wlheader-enotif' 		=> "* Email notification is enabled.",
'wlheader-showupdated'   => "* Pages which have been changed since you last visited them are shown in '''bold'''",
'watchmethod-recent'=> 'checking recent edits for watched pages',
'watchmethod-list'	=> 'checking watched pages for recent edits',
'removechecked' 	=> 'Remove checked items from watchlist',
'watchlistcontains' => "Your watchlist contains $1 pages.",
'watcheditlist'		=> 'Here\'s an alphabetical list of your
watched content pages. Check the boxes of pages you want to remove from your watchlist and click the \'remove checked\' button
at the bottom of the screen (deleting a content page also deletes the accompanying talk page and vice versa).',
'removingchecked' 	=> 'Removing requested items from watchlist...',
'couldntremove' 	=> "Couldn't remove item '$1'...",
'iteminvalidname' 	=> "Problem with item '$1', invalid name...",
'wlnote' 		=> 'Below are the last $1 changes in the last <b>$2</b> hours.',
'wlshowlast' 		=> 'Show last $1 hours $2 days $3',
'wlsaved'		=> 'This is a saved version of your watchlist.',
'wlhideshowown'   	=> '$1 my edits.',
'wlshow'		=> 'Show',
'wlhide'		=> 'Hide',

'enotif_mailer' 		=> '{{SITENAME}} Notification Mailer',
'enotif_reset'			=> 'Mark all pages visited',
'enotif_newpagetext'=> 'This is a new page.',
'changed'			=> 'changed',
'created'			=> 'created',
'enotif_subject' 	=> '{{SITENAME}} page $PAGETITLE has been $CHANGEDORCREATED by $PAGEEDITOR',
'enotif_lastvisited' => 'See $1 for all changes since your last visit.',
'enotif_body' => 'Dear $WATCHINGUSERNAME,

the {{SITENAME}} page $PAGETITLE has been $CHANGEDORCREATED on $PAGEEDITDATE by $PAGEEDITOR, see $PAGETITLE_URL for the current version.

$NEWPAGE

Editor\'s summary: $PAGESUMMARY $PAGEMINOREDIT

Contact the editor:
mail: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

There will be no other notifications in case of further changes unless you visit this page. You could also reset the notification flags for all your watched pages on your watchlist.

             Your friendly {{SITENAME}} notification system

--
To change your watchlist settings, visit
{{SERVER}}{{localurl:Special:Watchlist/edit}}

Feedback and further assistance:
{{SERVER}}{{localurl:Help:Contents}}',

# Delete/protect/revert
#
'deletepage'	=> 'Delete page',
'confirm'		=> 'Confirm',
'excontent' => "content was: '$1'",
'excontentauthor' => "content was: '$1' (and the only contributor was '$2')",
'exbeforeblank' => "content before blanking was: '$1'",
'exblank' => 'page was empty',
'confirmdelete' => 'Confirm delete',
'deletesub'		=> "(Deleting \"$1\")",
'historywarning' => 'Warning: The page you are about to delete has a history: ',
'confirmdeletetext' => "You are about to permanently delete a page
or image along with all of its history from the database.
Please confirm that you intend to do this, that you understand the
consequences, and that you are doing this in accordance with
[[Project:Policy]].",
'actioncomplete' => 'Action complete',
'deletedtext'	=> "\"$1\" has been deleted.
See $2 for a record of recent deletions.",
'deletedarticle' => "deleted \"[[$1]]\"",
'dellogpage'	=> 'Deletion_log',
'dellogpagetext' => 'Below is a list of the most recent deletions.',
'deletionlog'	=> 'deletion log',
'reverted'		=> 'Reverted to earlier revision',
'deletecomment'	=> 'Reason for deletion',
'imagereverted' => 'Revert to earlier version was successful.',
'rollback'		=> 'Roll back edits',
'rollback_short' => 'Rollback',
'rollbacklink'	=> 'rollback',
'rollbackfailed' => 'Rollback failed',
'cantrollback'	=> 'Cannot revert edit; last contributor is only author of this page.',
'alreadyrolled'	=> "Cannot rollback last edit of [[$1]]
by [[User:$2|$2]] ([[User talk:$2|Talk]]); someone else has edited or rolled back the page already.

Last edit was by [[User:$3|$3]] ([[User talk:$3|Talk]]). ",
#   only shown if there is an edit comment
'editcomment' => "The edit comment was: \"<i>$1</i>\".",
'revertpage'	=> "Reverted edit of $2, changed back to last version by $1",
'sessionfailure' => 'There seems to be a problem with your login session;
this action has been canceled as a precaution against session hijacking.
Please hit "back" and reload the page you came from, then try again.',
'protectlogpage' => 'Protection_log',
'protectlogtext' => "Below is a list of page locks/unlocks.
See [[Project:Protected page]] for more information.",
'protectedarticle' => 'protected "[[$1]]"',
'unprotectedarticle' => 'unprotected "[[$1]]"',
'protectsub' => '(Protecting "$1")',
'confirmprotecttext' => 'Do you really want to protect this page?',
'confirmprotect' => 'Confirm protection',
'protectmoveonly' => 'Protect from moves only',
'protectcomment' => 'Reason for protecting',
'unprotectsub' =>"(Unprotecting \"$1\")",
'confirmunprotecttext' => 'Do you really want to unprotect this page?',
'confirmunprotect' => 'Confirm unprotection',
'unprotectcomment' => 'Reason for unprotecting',

# Undelete
'undelete' => 'Restore deleted page',
'undeletepage' => 'View and restore deleted pages',
'undeletepagetext' => 'The following pages have been deleted but are still in the archive and
can be restored. The archive may be periodically cleaned out.',
'undeletearticle' => 'Restore deleted page',
'undeleterevisions' => "$1 revisions archived",
'undeletehistory' => 'If you restore the page, all revisions will be restored to the history.
If a new page with the same name has been created since the deletion, the restored
revisions will appear in the prior history, and the current revision of the live page
will not be automatically replaced.',
'undeleterevision' => "Deleted revision as of $1",
'undeletebtn' => 'Restore!',
'undeletedarticle' => "restored \"[[$1]]\"",
'undeletedrevisions' => "$1 revisions restored",
'undeletedtext'   => "[[$1]] has been successfully restored.
See [[Special:Log/delete]] for a record of recent deletions and restorations.",

# Namespace form on various pages
'namespace' => 'Namespace:',
'invert' => 'Invert selection',

# Contributions
#
'contributions' => 'User contributions',
'mycontris'     => 'My contributions',
'contribsub'    => "For $1",
'nocontribs'    => 'No changes were found matching these criteria.',
'ucnote'        => "Below are this user's last <b>$1</b> changes in the last <b>$2</b> days.",
'uclinks'       => "View the last $1 changes; view the last $2 days.",
'uctop'         => ' (top)' ,
'newbies'       => 'newbies',
'contribs-showhideminor' => '$1 minor edits',

# What links here
#
'whatlinkshere'	=> 'What links here',
'notargettitle' => 'No target',
'notargettext'	=> 'You have not specified a target page or user
to perform this function on.',
'linklistsub'	=> '(List of links)',
'linkshere'		=> 'The following pages link to here:',
'nolinkshere'	=> 'No pages link to here.',
'isredirect'	=> 'redirect page',

# Block/unblock IP
#
'blockip'		=> 'Block user',
'blockiptext'	=> "Use the form below to block write access
from a specific IP address or username.
This should be done only only to prevent vandalism, and in
accordance with [[Project:Policy|policy]].
Fill in a specific reason below (for example, citing particular
pages that were vandalized).",
'ipaddress'		=> 'IP Address',
'ipadressorusername' => 'IP Address or username',
'ipbexpiry'		=> 'Expiry',
'ipbreason'		=> 'Reason',
'ipbsubmit'		=> 'Block this user',
'ipbother'		=> 'Other time',
'ipboptions'		=> '2 hours:2 hours,1 day:1 day,3 days:3 days,1 week:1 week,2 weeks:2 weeks,1 month:1 month,3 months:3 months,6 months:6 months,1 year:1 year,infinite:infinite',
'ipbotheroption'	=> 'other',
'badipaddress'	=> 'Invalid IP address',
'blockipsuccesssub' => 'Block succeeded',
'blockipsuccesstext' => '[[{{ns:Special}}:Contributions/$1|$1]] has been blocked.
<br />See[[{{ns:Special}}:Ipblocklist|IP block list]] to review blocks.',
'unblockip'		=> 'Unblock user',
'unblockiptext'	=> 'Use the form below to restore write access
to a previously blocked IP address or username.',
'ipusubmit'		=> 'Unblock this address',
'ipusuccess'	=> "\"[[$1]]\" unblocked",
'ipblocklist'	=> 'List of blocked IP addresses and usernames',
'blocklistline'	=> "$1, $2 blocked $3 ($4)",
'infiniteblock' => 'infinite',
'expiringblock' => 'expires $1',
'ipblocklistempty'	=> 'The blocklist is empty.',
'blocklink'		=> 'block',
'unblocklink'	=> 'unblock',
'contribslink'	=> 'contribs',
'autoblocker'	=> 'Autoblocked because your IP address has been recently used by "[[User:$1|$1]]". The reason given for $1\'s block is: "\'\'\'$2\'\'\'"',
'blocklogpage'	=> 'Block_log',
'blocklogentry'	=> 'blocked "[[$1]]" with an expiry time of $2',
'blocklogtext'	=> 'This is a log of user blocking and unblocking actions. Automatically
blocked IP addresses are not listed. See the [[Special:Ipblocklist|IP block list]] for
the list of currently operational bans and blocks.',
'unblocklogentry'	=> 'unblocked $1',
'range_block_disabled'	=> 'The sysop ability to create range blocks is disabled.',
'ipb_expiry_invalid'	=> 'Expiry time invalid.',
'ip_range_invalid'	=> "Invalid IP range.\n",
'proxyblocker'	=> 'Proxy blocker',
'proxyblockreason'	=> 'Your IP address has been blocked because it is an open proxy. Please contact your Internet service provider or tech support and inform them of this serious security problem.',
'proxyblocksuccess'	=> "Done.\n",
'sorbs'         => 'SORBS DNSBL',
'sorbsreason'   => 'Your IP address is listed as an open proxy in the [http://www.sorbs.net SORBS] DNSBL.',
'sorbs_create_account_reason' => 'Your IP address is listed as an open proxy in the [http://www.sorbs.net SORBS] DNSBL. You cannot create an account',


# Developer tools
#
'lockdb'		=> 'Lock database',
'unlockdb'		=> 'Unlock database',
'lockdbtext'	=> 'Locking the database will suspend the ability of all
users to edit pages, change their preferences, edit their watchlists, and
other things requiring changes in the database.
Please confirm that this is what you intend to do, and that you will
unlock the database when your maintenance is done.',
'unlockdbtext'	=> 'Unlocking the database will restore the ability of all
users to edit pages, change their preferences, edit their watchlists, and
other things requiring changes in the database.
Please confirm that this is what you intend to do.',
'lockconfirm'	=> 'Yes, I really want to lock the database.',
'unlockconfirm'	=> 'Yes, I really want to unlock the database.',
'lockbtn'		=> 'Lock database',
'unlockbtn'		=> 'Unlock database',
'locknoconfirm' => 'You did not check the confirmation box.',
'lockdbsuccesssub' => 'Database lock succeeded',
'unlockdbsuccesssub' => 'Database lock removed',
'lockdbsuccesstext' => 'The database has been locked.
<br />Remember to remove the lock after your maintenance is complete.',
'unlockdbsuccesstext' => 'The database has been unlocked.',

# Make sysop
'makesysoptitle'	=> 'Make a user into a sysop',
'makesysoptext'		=> 'This form is used by bureaucrats to turn ordinary users into administrators.
Type the name of the user in the box and press the button to make the user an administrator',
'makesysopname'		=> 'Name of the user:',
'makesysopsubmit'	=> 'Make this user into a sysop',
'makesysopok'		=> "<b>User \"$1\" is now a sysop</b>",
'makesysopfail'		=> "<b>User \"$1\" could not be made into a sysop. (Did you enter the name correctly?)</b>",
'setbureaucratflag' => 'Set bureaucrat flag',
'setstewardflag'    => 'Set steward flag',
'bureaucratlog'		=> 'Bureaucrat_log',
'rightslogtext'		=> 'This is a log of changes to user rights.',
'bureaucratlogentry'	=> "Changed group membership for $1 from $2 to $3",
'rights'			=> 'Rights:',
'set_user_rights'	=> 'Set user rights',
'user_rights_set'	=> "<b>User rights for \"$1\" updated</b>",
'set_rights_fail'	=> "<b>User rights for \"$1\" could not be set. (Did you enter the name correctly?)</b>",
'makesysop'         => 'Make a user into a sysop',
'already_sysop'     => 'This user is already an administrator',
'already_bureaucrat' => 'This user is already a bureaucrat',
'already_steward'   => 'This user is already a steward',

# Validation
'val_yes' => 'Yes',
'val_no' => 'No',
'val_of' => '$1 of $2',
'val_revision' => 'Revision',
'val_time' => 'Time',
'val_user_stats_title' => 'Validation overview of user $1',
'val_my_stats_title' => 'My validation overview',
'val_list_header' => '<th>#</th><th>Topic</th><th>Range</th><th>Action</th>',
'val_add' => 'Add',
'val_del' => 'Delete',
'val_show_my_ratings' => 'Show my validations',
'val_revision_number' => 'Revision #$1',
'val_warning' => '<b>Never, <i>ever</i>, change something here without <i>explicit</i> community consensus!</b>',
'val_rev_for' => 'Revisions for $1',
'val_details_th_user' => 'User $1',
'val_validation_of' => 'Validation of "$1"',
'val_revision_of' => 'Revision of $1',
'val_revision_changes_ok' => 'Your ratings have been stored!',
'val_rev_stats' => 'See the validation statistics for "$1" <a href="$2">here</a>',
'val_revision_stats_link' => 'details',
'val_iamsure' => 'Check this box if you really mean it!',
'val_details_th' => '<sub>User</sub> \\ <sup>Topic</sup>',
'val_clear_old' => 'Clear my older validation data',
'val_merge_old' => 'Use my previous assessment where selected \'No opinion\'',
'val_form_note' => "'''Hint:''' Merging your data means that for the article revision you select, all options where you have specified ''no opinion'' will be set to the value and comment of the most recent revision for which you have expressed an opinion. For example, if you want to change a single option for a newer revision, but also keep your other settings for this article in this revision, just select which option you intend to ''change'', and merging will fill in the other options with your previous settings.",
'val_noop' => 'No opinion',
'val_topic_desc_page' => 'Project:Validation topics',
'val_votepage_intro' => 'Change this text <a href="{{SERVER}}{{localurl:MediaWiki:Val_votepage_intro}}">here</a>!',
'val_percent' => '<b>$1%</b><br />($2 of $3 points<br />by $4 users)',
'val_percent_single' => '<b>$1%</b><br />($2 of $3 points<br />by one user)',
'val_total' => 'Total',
'val_version' => 'Version',
'val_tab' => 'Validate',
'val_this_is_current_version' => 'this is the latest version',
'val_version_of' => "Version of $1" ,
'val_table_header' => "<tr><th>Class</th>$1<th colspan=4>Opinion</th>$1<th>Comment</th></tr>\n",
'val_stat_link_text' => 'Validation statistics for this article',
'val_view_version' => 'View this revision',
'val_validate_version' => 'Validate this version',
'val_user_validations' => 'This user has validated $1 pages.',
'val_no_anon_validation' => 'You have to be logged in to validate an article.',
'val_validate_article_namespace_only' => 'Only articles can be validated. This page is <i>not</i> in the article namespace.',
'val_validated' => 'Validation done.',
'val_article_lists' => 'List of validated articles',
'val_page_validation_statistics' => 'Page validation statistics for $1',

# Move page
#
'movepage'		=> 'Move page',
'movepagetext'	=> 'Using the form below will rename a page, moving all
of its history to the new name.
The old title will become a redirect page to the new title.
Links to the old page title will not be changed; be sure to
check for double or broken redirects.
You are responsible for making sure that links continue to
point where they are supposed to go.

Note that the page will \'\'\'not\'\'\' be moved if there is already
a page at the new title, unless it is empty or a redirect and has no
past edit history. This means that you can rename a page back to where
it was just renamed from if you make a mistake, and you cannot overwrite
an existing page.

<b>WARNING!</b>
This can be a drastic and unexpected change for a popular page;
please be sure you understand the consequences of this before
proceeding.',
'movepagetalktext' => 'The associated talk page, if any, will be automatically moved along with it \'\'\'unless:\'\'\'
*You are moving the page across namespaces,
*A non-empty talk page already exists under the new name, or
*You uncheck the box below.

In those cases, you will have to move or merge the page manually if desired.',
'movearticle'	=> 'Move page',
'movenologin'	=> 'Not logged in',
'movenologintext' => "You must be a registered user and [[Special:Userlogin|logged in]]
to move a page.",
'newtitle'		=> 'To new title',
'movepagebtn'	=> 'Move page',
'pagemovedsub'	=> 'Move succeeded',
'pagemovedtext' => "Page \"[[$1]]\" moved to \"[[$2]]\".",
'articleexists' => 'A page of that name already exists, or the
name you have chosen is not valid.
Please choose another name.',
'talkexists'	=> "'''The page itself was moved successfully, but the
talk page could not be moved because one already exists at the new
title. Please merge them manually.'''",
'movedto'		=> 'moved to',
'movetalk'		=> 'Move "talk" page as well, if applicable.',
'talkpagemoved' => 'The corresponding talk page was also moved.',
'talkpagenotmoved' => 'The corresponding talk page was <strong>not</strong> moved.',
'1movedto2'		=> '$1 moved to $2',
'1movedto2_redir' => '$1 moved to $2 over redirect',
'movelogpage' => 'Move log',
'movelogpagetext' => 'Below is a list of page moved.',
'movereason'	=> 'Reason',
'revertmove'	=> 'revert',
'delete_and_move' => 'Delete and move',
'delete_and_move_text'	=>
'==Deletion required==

The destination article "[[$1]]" already exists. Do you want to delete it to make way for the move?',
'delete_and_move_reason' => 'Deleted to make way for move',
'selfmove' => "Source and destination titles are the same; can't move a page over itself.",
'immobile_namespace' => "Destination title is of a special type; cannot move pages into that namespace.",

# Export

'export'		=> 'Export pages',
'exporttext'	=> 'You can export the text and editing history of a particular page or
set of pages wrapped in some XML. This can be imported into another wiki using MediaWiki
via the Special:Import page.

To export article pages, enter the titles in the text box below, one title per line, and
select whether you want the current version as well as all old versions, with the page
history lines, or just the current version with the info about the last edit.

In the latter case you can also use a link, e.g. [[{{ns:Special}}:Export/Train]] for the
article [[Train]].
',
'exportcuronly'	=> 'Include only the current revision, not the full history',

# Namespace 8 related

'allmessages'	=> 'System messages',
'allmessagesname' => 'Name',
'allmessagesdefault' => 'Default text',
'allmessagescurrent' => 'Current text',
'allmessagestext'	=> 'This is a list of system messages available in the MediaWiki: namespace.',
'allmessagesnotsupportedUI' => 'Your current interface language <b>$1</b> is not supported by Special:AllMessages at this site. ',
'allmessagesnotsupportedDB' => 'Special:AllMessages not supported because wgUseDatabaseMessages is off.',

# Thumbnails

'thumbnail-more'	=> 'Enlarge',
'missingimage'		=> "<b>Missing image</b><br /><i>$1</i>\n",
'filemissing'		=> 'File missing',

# Special:Import
'import'	=> 'Import pages',
'importinterwiki' => 'Transwiki import',
'importtext'	=> 'Please export the file from the source wiki using the Special:Export utility, save it to your disk and upload it here.',
'importfailed'	=> "Import failed: $1",
'importnotext'	=> 'Empty or no text',
'importsuccess'	=> 'Import succeeded!',
'importhistoryconflict' => 'Conflicting history revision exists (may have imported this page before)',
'importnosources' => 'No transwiki import sources have been defined and direct history uploads are disabled.',
'importnofile' => 'No import file was uploaded.',
'importuploaderror' => 'Upload of import file failed; perhaps the file is bigger than the allowed upload size.',

# Keyboard access keys for power users
'accesskey-search' => 'f',
'accesskey-minoredit' => 'i',
'accesskey-save' => 's',
'accesskey-preview' => 'p',
'accesskey-diff' => 'v',
'accesskey-compareselectedversions' => 'v',

# tooltip help for some actions, most are in Monobook.js
'tooltip-search' => 'Search {{SITENAME}} [alt-f]',
'tooltip-minoredit' => 'Mark this as a minor edit [alt-i]',
'tooltip-save' => 'Save your changes [alt-s]',
'tooltip-preview' => 'Preview your changes, please use this before saving! [alt-p]',
'tooltip-diff' => 'Show which changes you made to the text. [alt-d]',
'tooltip-compareselectedversions' => 'See the differences between the two selected versions of this page. [alt-v]',
'tooltip-watch' => 'Add this page to your watchlist [alt-w]',

# stylesheets
'Monobook.css' => '/* edit this file to customize the monobook skin for the entire site */',
#'Monobook.js' => '/* edit this file to change js things in the monobook skin */',

# Metadata
'nodublincore' => 'Dublin Core RDF metadata disabled for this server.',
'nocreativecommons' => 'Creative Commons RDF metadata disabled for this server.',
'notacceptable' => 'The wiki server can\'t provide data in a format your client can read.',

# Attribution

'anonymous' => 'Anonymous user(s) of {{SITENAME}}',
'siteuser' => '{{SITENAME}} user $1',
'lastmodifiedby' => 'This page was last modified $1 by $2.',
'and' => 'and',
'othercontribs' => 'Based on work by $1.',
'others' => 'others',
'siteusers' => '{{SITENAME}} user(s) $1',
'creditspage' => 'Page credits',
'nocredits' => 'There is no credits info available for this page.',

# Spam protection

'spamprotectiontitle' => 'Spam protection filter',
'spamprotectiontext' => 'The page you wanted to save was blocked by the spam filter. This is probably caused by a link to an external site.',
'spamprotectionmatch' => 'The following text is what triggered our spam filter: $1',
'subcategorycount' => "There are $1 subcategories to this category.",
'subcategorycount1' => "There is $1 subcategory to this category.",
'categoryarticlecount' => "There are $1 articles in this category.",
'categoryarticlecount1' => "There is $1 article in this category.",
'usenewcategorypage' => "1\n\nSet first character to \"0\" to disable the new category page layout.",
'listingcontinuesabbrev' => " cont.",

# Info page
'infosubtitle' => 'Information for page',
'numedits' => 'Number of edits (article): $1',
'numtalkedits' => 'Number of edits (discussion page): $1',
'numwatchers' => 'Number of watchers: $1',
'numauthors' => 'Number of distinct authors (article): $1',
'numtalkauthors' => 'Number of distinct authors (discussion page): $1',

# Math options
'mw_math_png' => 'Always render PNG',
'mw_math_simple' => 'HTML if very simple or else PNG',
'mw_math_html' => 'HTML if possible or else PNG',
'mw_math_source' => 'Leave it as TeX (for text browsers)',
'mw_math_modern' => 'Recommended for modern browsers',
'mw_math_mathml' => 'MathML if possible (experimental)',

# Patrolling
'markaspatrolleddiff'   => "Mark as patrolled",
'markaspatrolledlink'   => "[$1]",
'markaspatrolledtext'   => "Mark this article as patrolled",
'markedaspatrolled'     => "Marked as patrolled",
'markedaspatrolledtext' => "The selected revision has been marked as patrolled.",
'rcpatroldisabled'      => "Recent Changes Patrol disabled",
'rcpatroldisabledtext'  => "The Recent Changes Patrol feature is currently disabled.",

# Monobook.js: tooltips and access keys for monobook
'Monobook.js' => '/* tooltips and access keys */
ta = new Object();
ta[\'pt-userpage\'] = new Array(\'.\',\'My user page\');
ta[\'pt-anonuserpage\'] = new Array(\'.\',\'The user page for the ip you\\\'re editing as\');
ta[\'pt-mytalk\'] = new Array(\'n\',\'My talk page\');
ta[\'pt-anontalk\'] = new Array(\'n\',\'Discussion about edits from this ip address\');
ta[\'pt-preferences\'] = new Array(\'\',\'My preferences\');
ta[\'pt-watchlist\'] = new Array(\'l\',\'The list of pages you\\\'re monitoring for changes.\');
ta[\'pt-mycontris\'] = new Array(\'y\',\'List of my contributions\');
ta[\'pt-login\'] = new Array(\'o\',\'You are encouraged to log in, it is not mandatory however.\');
ta[\'pt-anonlogin\'] = new Array(\'o\',\'You are encouraged to log in, it is not mandatory however.\');
ta[\'pt-logout\'] = new Array(\'o\',\'Log out\');
ta[\'ca-talk\'] = new Array(\'t\',\'Discussion about the content page\');
ta[\'ca-edit\'] = new Array(\'e\',\'You can edit this page. Please use the preview button before saving.\');
ta[\'ca-addsection\'] = new Array(\'+\',\'Add a comment to this discussion.\');
ta[\'ca-viewsource\'] = new Array(\'e\',\'This page is protected. You can view its source.\');
ta[\'ca-history\'] = new Array(\'h\',\'Past versions of this page.\');
ta[\'ca-protect\'] = new Array(\'=\',\'Protect this page\');
ta[\'ca-delete\'] = new Array(\'d\',\'Delete this page\');
ta[\'ca-undelete\'] = new Array(\'d\',\'Restore the edits done to this page before it was deleted\');
ta[\'ca-move\'] = new Array(\'m\',\'Move this page\');
ta[\'ca-watch\'] = new Array(\'w\',\'Add this page to your watchlist\');
ta[\'ca-unwatch\'] = new Array(\'w\',\'Remove this page from your watchlist\');
ta[\'search\'] = new Array(\'f\',\'Search this wiki\');
ta[\'p-logo\'] = new Array(\'\',\'Main Page\');
ta[\'n-mainpage\'] = new Array(\'z\',\'Visit the Main Page\');
ta[\'n-portal\'] = new Array(\'\',\'About the project, what you can do, where to find things\');
ta[\'n-currentevents\'] = new Array(\'\',\'Find background information on current events\');
ta[\'n-recentchanges\'] = new Array(\'r\',\'The list of recent changes in the wiki.\');
ta[\'n-randompage\'] = new Array(\'x\',\'Load a random page\');
ta[\'n-help\'] = new Array(\'\',\'The place to find out.\');
ta[\'n-sitesupport\'] = new Array(\'\',\'Support us\');
ta[\'t-whatlinkshere\'] = new Array(\'j\',\'List of all wiki pages that link here\');
ta[\'t-recentchangeslinked\'] = new Array(\'k\',\'Recent changes in pages linked from this page\');
ta[\'feed-rss\'] = new Array(\'\',\'RSS feed for this page\');
ta[\'feed-atom\'] = new Array(\'\',\'Atom feed for this page\');
ta[\'t-contributions\'] = new Array(\'\',\'View the list of contributions of this user\');
ta[\'t-emailuser\'] = new Array(\'\',\'Send a mail to this user\');
ta[\'t-upload\'] = new Array(\'u\',\'Upload images or media files\');
ta[\'t-specialpages\'] = new Array(\'q\',\'List of all special pages\');
ta[\'ca-nstab-main\'] = new Array(\'c\',\'View the content page\');
ta[\'ca-nstab-user\'] = new Array(\'c\',\'View the user page\');
ta[\'ca-nstab-media\'] = new Array(\'c\',\'View the media page\');
ta[\'ca-nstab-special\'] = new Array(\'\',\'This is a special page, you can\\\'t edit the page itself.\');
ta[\'ca-nstab-wp\'] = new Array(\'a\',\'View the project page\');
ta[\'ca-nstab-image\'] = new Array(\'c\',\'View the image page\');
ta[\'ca-nstab-mediawiki\'] = new Array(\'c\',\'View the system message\');
ta[\'ca-nstab-template\'] = new Array(\'c\',\'View the template\');
ta[\'ca-nstab-help\'] = new Array(\'c\',\'View the help page\');
ta[\'ca-nstab-category\'] = new Array(\'c\',\'View the category page\');
',

# image deletion
'deletedrevision' => 'Deleted old revision $1.',

# browsing diffs
'previousdiff' => '← Previous diff',
'nextdiff' => 'Next diff →',

'imagemaxsize' => 'Limit images on image description pages to: ',
'thumbsize'	=> 'Thumbnail size: ',
'showbigimage' => 'Download high resolution version ($1x$2, $3 KB)',

'newimages' => 'Gallery of new files',
'noimages'  => 'Nothing to see.',

# short names for language variants used for language conversion links.
# to disable showing a particular link, set it to 'disable', e.g.
# 'variantname-zh-sg' => 'disable',
'variantname-zh-cn' => 'cn',
'variantname-zh-tw' => 'tw',
'variantname-zh-hk' => 'hk',
'variantname-zh-sg' => 'sg',
'variantname-zh' => 'zh',

# labels for User: and Title: on Special:Log pages
'specialloguserlabel' => 'User: ',
'speciallogtitlelabel' => 'Title: ',

'passwordtooshort' => 'Your password is too short. It must have at least $1 characters.',

# Media Warning
'mediawarning' => '\'\'\'Warning\'\'\': This file may contain malicious code, by executing it your system may be compromised.
<hr>',

'fileinfo' => '$1KB, MIME type: <code>$2</code>',

# Metadata
'metadata' => 'Metadata',

# Exif tags
'exif-imagewidth' =>'Width',
'exif-imagelength' =>'Height',
'exif-bitspersample' =>'Bits per component',
'exif-compression' =>'Compression scheme',
'exif-photometricinterpretation' =>'Pixel composition',
'exif-orientation' =>'Orientation',
'exif-samplesperpixel' =>'Number of components',
'exif-planarconfiguration' =>'Data arrangement',
'exif-ycbcrsubsampling' =>'Subsampling ratio of Y to C',
'exif-ycbcrpositioning' =>'Y and C positioning',
'exif-xresolution' =>'Horizontal resolution',
'exif-yresolution' =>'Vertical resolution',
'exif-resolutionunit' =>'Unit of X and Y resolution',
'exif-stripoffsets' =>'Image data location',
'exif-rowsperstrip' =>'Number of rows per strip',
'exif-stripbytecounts' =>'Bytes per compressed strip',
'exif-jpeginterchangeformat' =>'Offset to JPEG SOI',
'exif-jpeginterchangeformatlength' =>'Bytes of JPEG data',
'exif-transferfunction' =>'Transfer function',
'exif-whitepoint' =>'White point chromaticity',
'exif-primarychromaticities' =>'Chromaticities of primarities',
'exif-ycbcrcoefficients' =>'Color space transformation matrix coefficients',
'exif-referenceblackwhite' =>'Pair of black and white reference values',
'exif-datetime' =>'File change date and time',
'exif-imagedescription' =>'Image title',
'exif-make' =>'Camera manufacturer',
'exif-model' =>'Camera model',
'exif-software' =>'Software used',
'exif-artist' =>'Author',
'exif-copyright' =>'Copyright holder',
'exif-exifversion' =>'Exif version',
'exif-flashpixversion' =>'Supported Flashpix version',
'exif-colorspace' =>'Color space',
'exif-componentsconfiguration' =>'Meaning of each component',
'exif-compressedbitsperpixel' =>'Image compression mode',
'exif-pixelydimension' =>'Valid image width',
'exif-pixelxdimension' =>'Valind image height',
'exif-makernote' =>'Manufacturer notes',
'exif-usercomment' =>'User comments',
'exif-relatedsoundfile' =>'Related audio file',
'exif-datetimeoriginal' =>'Date and time of data generation',
'exif-datetimedigitized' =>'Date and time of digitizing',
'exif-subsectime' =>'DateTime subseconds',
'exif-subsectimeoriginal' =>'DateTimeOriginal subseconds',
'exif-subsectimedigitized' =>'DateTimeDigitized subseconds',
'exif-exposuretime' =>'Exposure time',
'exif-fnumber' =>'F Number',
'exif-exposureprogram' =>'Exposure Program',
'exif-spectralsensitivity' =>'Spectral sensitivity',
'exif-isospeedratings' =>'ISO speed rating',
'exif-oecf' =>'Optoelectronic conversion factor',
'exif-shutterspeedvalue' =>'Shutter speed',
'exif-aperturevalue' =>'Aperture',
'exif-brightnessvalue' =>'Brightness',
'exif-exposurebiasvalue' =>'Exposure bias',
'exif-maxaperturevalue' =>'Maximum land aperture',
'exif-subjectdistance' =>'Subject distance',
'exif-meteringmode' =>'Metering mode',
'exif-lightsource' =>'Light source',
'exif-flash' =>'Flash',
'exif-focallength' =>'Lens focal length',
'exif-subjectarea' =>'Subject area',
'exif-flashenergy' =>'Flash energy',
'exif-spatialfrequencyresponse' =>'Spatial frequency response',
'exif-focalplanexresolution' =>'Focal plane X resolution',
'exif-focalplaneyresolution' =>'Focal plane Y resolution',
'exif-focalplaneresolutionunit' =>'Focal plane resolution unit',
'exif-subjectlocation' =>'Subject location',
'exif-exposureindex' =>'Exposure index',
'exif-sensingmethod' =>'Sensing method',
'exif-filesource' =>'File source',
'exif-scenetype' =>'Scene type',
'exif-cfapattern' =>'CFA pattern',
'exif-customrendered' =>'Custom image processing',
'exif-exposuremode' =>'Exposure mode',
'exif-whitebalance' =>'White Balance',
'exif-digitalzoomratio' =>'Digital zoom ratio',
'exif-focallengthin35mmfilm' =>'Focal length in 35 mm film',
'exif-scenecapturetype' =>'Scene capture type',
'exif-gaincontrol' =>'Scene control',
'exif-contrast' =>'Contrast',
'exif-saturation' =>'Saturation',
'exif-sharpness' =>'Sharpness',
'exif-devicesettingdescription' =>'Device settings description',
'exif-subjectdistancerange' =>'Subject distance range',
'exif-imageuniqueid' =>'Unique image ID',
'exif-gpsversionid' =>'GPS tag version',
'exif-gpslatituderef' =>'North or South Latitude',
'exif-gpslatitude' =>'Latitude',
'exif-gpslongituderef' =>'East or West Longitude',
'exif-gpslongitude' =>'Longitude',
'exif-gpsaltituderef' =>'Altitude reference',
'exif-gpsaltitude' =>'Altitude',
'exif-gpstimestamp' =>'GPS time (atomic clock)',
'exif-gpssatellites' =>'Satellites used for measurement',
'exif-gpsstatus' =>'Receiver status',
'exif-gpsmeasuremode' =>'Measurement mode',
'exif-gpsdop' =>'Measurement precision',
'exif-gpsspeedref' =>'Speed unit',
'exif-gpsspeed' =>'Speed of GPS receiver',
'exif-gpstrackref' =>'Reference for direction of movement',
'exif-gpstrack' =>'Direction of movement',
'exif-gpsimgdirectionref' =>'Reference for direction of image',
'exif-gpsimgdirection' =>'Direction of image',
'exif-gpsmapdatum' =>'Geodetic survey data used',
'exif-gpsdestlatituderef' =>'Reference for latitude of destination',
'exif-gpsdestlatitude' =>'Latitude destination',
'exif-gpsdestlongituderef' =>'Reference for longitude of destination',
'exif-gpsdestlongitude' =>'Longitude of destination',
'exif-gpsdestbearingref' =>'Reference for bearing of destination',
'exif-gpsdestbearing' =>'Bearing of destination',
'exif-gpsdestdistanceref' =>'Reference for distance to destination',
'exif-gpsdestdistance' =>'Distance to destination',
'exif-gpsprocessingmethod' =>'Name of GPS processing method',
'exif-gpsareainformation' =>'Name of GPS area',
'exif-gpsdatestamp' =>'GPS date',
'exif-gpsdifferential' =>'GPS differential correction',

# Make & model, can be wikified in order to link to the camera and model name

'exif-make-value' => '$1',
'exif-model-value' =>'$1',
'exif-software-value' => '$1',

# Exif attributes

'exif-compression-1' => 'Uncompressed',
'exif-compression-6' => 'JPEG',

'exif-photometricinterpretation-1' => 'RGB',
'exif-photometricinterpretation-6' => 'YCbCr',

'exif-orientation-1' => 'Normal', // 0th row: top; 0th column: left
'exif-orientation-2' => 'Flipped horizontally', // 0th row: top; 0th column: right
'exif-orientation-3' => 'Rotated 180°', // 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Flipped vertically', // 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Rotated 90° CCW and flipped vertically', // 0th row: left; 0th column: top
'exif-orientation-6' => 'Rotated 90° CW', // 0th row: right; 0th column: top
'exif-orientation-7' => 'Rotated 90° CW and flipped vertically', // 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Rotated 90° CCW', // 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'chunky format',
'exif-planarconfiguration-2' => 'planar format',

'exif-xyresolution-i' => '$1 dpi',
'exif-xyresolution-c' => '$1 dpc',

'exif-colorspace-1' => 'sRGB',
'exif-colorspace-ffff.h' => 'FFFF.H',

'exif-componentsconfiguration-0' => 'does not exist',
'exif-componentsconfiguration-1' => 'Y',
'exif-componentsconfiguration-2' => 'Cb',
'exif-componentsconfiguration-3' => 'Cr',
'exif-componentsconfiguration-4' => 'R',
'exif-componentsconfiguration-5' => 'G',
'exif-componentsconfiguration-6' => 'B',

'exif-exposureprogram-0' => 'Not defined',
'exif-exposureprogram-1' => 'Manual',
'exif-exposureprogram-2' => 'Normal program',
'exif-exposureprogram-3' => 'Aperture priority',
'exif-exposureprogram-4' => 'Shutter priority',
'exif-exposureprogram-5' => 'Creative program (biased toward depth of field)',
'exif-exposureprogram-6' => 'Action program (biased toward fast shutter speed)',
'exif-exposureprogram-7' => 'Portrait mode (for closeup photos with the background out of focus)',
'exif-exposureprogram-8' => 'Landscape mode (for landscape photos with the background in focus)',

'exif-subjectdistance-value' => '$1 metres',

'exif-meteringmode-0' => 'Unknown',
'exif-meteringmode-1' => 'Average',
'exif-meteringmode-2' => 'CenterWeightedAverage',
'exif-meteringmode-3' => 'Spot',
'exif-meteringmode-4' => 'MultiSpot',
'exif-meteringmode-5' => 'Pattern',
'exif-meteringmode-6' => 'Partial',
'exif-meteringmode-255' => 'Other',

'exif-lightsource-0' => 'Unknown',
'exif-lightsource-1' => 'Daylight',
'exif-lightsource-2' => 'Fluorescent',
'exif-lightsource-3' => 'Tungsten (incandescent light)',
'exif-lightsource-4' => 'Flash',
'exif-lightsource-9' => 'Fine weather',
'exif-lightsource-10' => 'Clody weather',
'exif-lightsource-11' => 'Shade',
'exif-lightsource-12' => 'Daylight fluorescent (D 5700 – 7100K)',
'exif-lightsource-13' => 'Day white fluorescent (N 4600 – 5400K)',
'exif-lightsource-14' => 'Cool white fluorescent (W 3900 – 4500K)',
'exif-lightsource-15' => 'White fluorescent (WW 3200 – 3700K)',
'exif-lightsource-17' => 'Standard light A',
'exif-lightsource-18' => 'Standard light B',
'exif-lightsource-19' => 'Standard light C',
'exif-lightsource-20' => 'D55',
'exif-lightsource-21' => 'D65',
'exif-lightsource-22' => 'D75',
'exif-lightsource-23' => 'D50',
'exif-lightsource-24' => 'ISO studio tungsten',
'exif-lightsource-255' => 'Other light source',

'exif-focalplaneresolutionunit-2' => 'inches',

'exif-sensingmethod-1' => 'Undefined',
'exif-sensingmethod-2' => 'One-chip color area sensor',
'exif-sensingmethod-3' => 'Two-chip color area sensor',
'exif-sensingmethod-4' => 'Three-chip color area sensor',
'exif-sensingmethod-5' => 'Color sequential area sensor',
'exif-sensingmethod-7' => 'Trilinear sensor',
'exif-sensingmethod-8' => 'Color sequential linear sensor',

'exif-filesource-3' => 'DSC',

'exif-scenetype-1' => 'A directly photographed image',

'exif-customrendered-0' => 'Normal process',
'exif-customrendered-1' => 'Custom process',

'exif-exposuremode-0' => 'Auto exposure',
'exif-exposuremode-1' => 'Manual exposure',
'exif-exposuremode-2' => 'Auto bracket',

'exif-whitebalance-0' => 'Auto white balance',
'exif-whitebalance-1' => 'Manual white balance',

'exif-scenecapturetype-0' => 'Standard',
'exif-scenecapturetype-1' => 'Landscape',
'exif-scenecapturetype-2' => 'Portrait',
'exif-scenecapturetype-3' => 'Night scene',

'exif-gaincontrol-0' => 'None',
'exif-gaincontrol-1' => 'Low gain up',
'exif-gaincontrol-2' => 'High gain up',
'exif-gaincontrol-3' => 'Low gain down',
'exif-gaincontrol-4' => 'High gain down',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Soft',
'exif-contrast-2' => 'Hard',

'exif-saturation-0' => 'Normal',
'exif-saturation-1' => 'Low saturation',
'exif-saturation-2' => 'High saturation',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Soft',
'exif-sharpness-2' => 'Hard',

'exif-subjectdistancerange-0' => 'Unknown',
'exif-subjectdistancerange-1' => 'Macro',
'exif-subjectdistancerange-2' => 'Close view',
'exif-subjectdistancerange-3' => 'Distant view',

// Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'North latitude',
'exif-gpslatitude-s' => 'South latitude',

// Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'East longitude',
'exif-gpslongitude-w' => 'West longitude',

'exif-gpsstatus-a' => 'Measurement in progress',
'exif-gpsstatus-v' => 'Measurement interoperability',

'exif-gpsmeasuremode-2' => '2-dimensional measurement',
'exif-gpsmeasuremode-3' => '3-dimensional measurement',

// Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Kilometres per hour',
'exif-gpsspeed-m' => 'Miles per hour',
'exif-gpsspeed-n' => 'Knots',

// Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'True direction',
'exif-gpsdirection-m' => 'Magnetic direction',

# external editor support
'edit-externally' => 'Edit this file using an external application',
'edit-externally-help' => 'See the [http://meta.wikimedia.org/wiki/Help:External_editors setup instructions] for more information.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'all',
'imagelistall' => 'all',
'watchlistall1' => 'all',
'watchlistall2' => 'all',
'namespacesall' => 'all',

# E-mail address confirmation
'confirmemail' => 'Confirm E-mail address',
'confirmemail_text' => "This wiki requires that you validate your e-mail address
before using e-mail features. Activate the button below to send a confirmation
mail to your address. The mail will include a link containing a code; load the
link in your browser to confirm that your e-mail address is valid.",
'confirmemail_send' => 'Mail a confirmation code',
'confirmemail_sent' => 'Confirmation e-mail sent.',
'confirmemail_sendfailed' => 'Could not send confirmation mail. Check address for invalid characters.',
'confirmemail_invalid' => 'Invalid confirmation code. The code may have expired.',
'confirmemail_success' => 'Your e-mail address has been confirmed. You may now log in and enjoy the wiki.',
'confirmemail_loggedin' => 'Your e-mail address has now been confirmed.',
'confirmemail_error' => 'Something went wrong saving your confirmation.',

'confirmemail_subject' => '{{SITENAME}} e-mail address confirmation',
'confirmemail_body' => "Someone, probably you from IP address $1, has registered an
account \"$2\" with this e-mail address on {{SITENAME}}.

To confirm that this account really does belong to you and activate
e-mail features on {{SITENAME}}, open this link in your browser:

$3

If this is *not* you, don't follow the link. This confirmation code
will expire at $4.
",

# Inputbox extension, may be useful in other contexts as well
'tryexact' => 'Try exact match',
'searchfulltext' => 'Search full text',
'createarticle' => 'Create article',

# Scary transclusion
'scarytranscludedisabled' => '[Interwiki transcluding is disabled]',
'scarytranscludefailed' => '[Template fetch failed for $1; sorry]',
'scarytranscludetoolong' => '[URL is too long; sorry]',

# Trackbacks
'trackbackbox' => "<div id='mw_trackbacks'>
Trackbacks for this article:<br />
$1
</div>
",
'trackback' => "; $4$5 : [$2 $1]\n",
'trackbackexcerpt' => "; $4$5 : [$2 $1]: <nowiki>$3</nowiki>\n",
'trackbackremove' => ' ([$1 Delete])',
'trackbacklink' => 'Trackback',
'trackbackdeleteok' => 'The trackback was successfully deleted.',

'unit-pixel' => 'px',

'youhavenewmessagesmulti' => "You have new messages on $1",
'newtalkseperator' => ',_',
);

/* a fake language converter */
class fakeConverter {
	var $mLang;
	function fakeConverter($langobj) {$this->mLang = $langobj;}
	function convert($t, $i) {return $t;}
	function getVariants() { return array( $this->mLang->getCode() ); }
	function getPreferredVariant() {return $this->mLang->getCode(); }
	function findVariantLink(&$l, &$n) {}
	function getExtraHashOptions() {return '';}
	function getParsedTitle() {return '';}
	function markNoConversion($text) {return $text;}
	function convertCategoryKey( $key ) {return $key; }

}

#--------------------------------------------------------------------------
# Internationalisation code
#--------------------------------------------------------------------------

class Language {
	var $mConverter;
	function Language() {

		# Copies any missing values in the specified arrays from En to the current language
		$fillin = array( 'wgSysopSpecialPages', 'wgValidSpecialPages', 'wgDeveloperSpecialPages' );
		$name = get_class( $this );

		if( strpos( $name, 'language' ) == 0){
			$lang = ucfirst( substr( $name, 8 ) );
			foreach( $fillin as $arrname ){
				$langver = "{$arrname}{$lang}";
				$enver = "{$arrname}En";
				if( ! isset( $GLOBALS[$langver] ) || ! isset( $GLOBALS[$enver] ))
					continue;
				foreach($GLOBALS[$enver] as $spage => $text){
					if( ! isset( $GLOBALS[$langver][$spage] ) )
						$GLOBALS[$langver][$spage] = $text;
				}
			}
		}
		$this->mConverter = new fakeConverter($this);
	}

	/**
	 * Exports the default user options as defined in
	 * $wgDefaultUserOptionsEn, user preferences can override some of these
	 * depending on what's in (Local|Default)Settings.php and some defines.
	 *
	 * @return array
	 */
	function getDefaultUserOptions() {
		global $wgDefaultUserOptionsEn ;
		return $wgDefaultUserOptionsEn ;
	}

	/**
	 * Exports $wgBookstoreListEn
	 * @return array
	 */
	function getBookstoreList() {
		global $wgBookstoreListEn ;
		return $wgBookstoreListEn ;
	}

	/**
	 * @return array
	 */
	function getNamespaces() {
		global $wgNamespaceNamesEn;
		return $wgNamespaceNamesEn;
	}

	/**
	 * A convenience function that returns the same thing as
	 * getNamespaces() except with the array values changed to ' '
	 * where it found '_', useful for producing output to be displayed
	 * e.g. in <select> forms.
	 *
	 * @return array
	 */
	function getFormattedNamespaces() {
		$ns = $this->getNamespaces();
		foreach($ns as $k => $v) {
			$ns[$k] = strtr($v, '_', ' ');
		}
		return $ns;
	}

	/**
	 * Get a namespace value by key
	 * <code>
	 * $mw_ns = $wgContLang->getNsText( NS_MEDIAWIKI );
	 * echo $mw_ns; // prints 'MediaWiki'
	 * </code>
	 *
	 * @param int $index the array key of the namespace to return
	 * @return mixed, string if the namespace value exists, otherwise false
	 */
	function getNsText( $index ) {
		$ns = $this->getNamespaces();
		return isset( $ns[$index] ) ? $ns[$index] : false;
	}

	/**
	 * A convenience function that returns the same thing as
	 * getNsText() except with '_' changed to ' ', useful for
	 * producing output.
	 *
	 * @return array
	 */
	function getFormattedNsText( $index ) {
		$ns = $this->getNsText( $index );
		return strtr($ns, '_', ' ');
	}

	/**
	 * Get a namespace key by value, case insensetive.
	 *
	 * @param string $text
	 * @return mixed An integer if $text is a valid value otherwise false
	 */
	function getNsIndex( $text ) {
		$ns = $this->getNamespaces();

		foreach ( $ns as $i => $n ) {
			if ( strcasecmp( $n, $text ) == 0)
				return $i;
		}
		return false;
	}

	/**
	 * short names for language variants used for language conversion links.
	 *
	 * @param string $code
	 * @return string
	 */
	function getVariantname( $code ) {
		return wfMsg( "variantname-$code" );
	}

	function specialPage( $name ) {
		return $this->getNsText(NS_SPECIAL) . ':' . $name;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsEn;
		return $wgQuickbarSettingsEn;
	}

	function getSkinNames() {
		global $wgSkinNamesEn;
		return $wgSkinNamesEn;
	}

	function getMathNames() {
		global $wgMathNamesEn;
		return $wgMathNamesEn;
	}

	function getDateFormats() {
		global $wgDateFormatsEn;
		return $wgDateFormatsEn;
	}

	function getValidationTypes() {
		global $wgValidationTypesEn;
		return $wgValidationTypesEn;
	}

	function getUserToggles() {
		global $wgUserTogglesEn;
		return $wgUserTogglesEn;
	}

	function getUserToggle( $tog ) {
		return wfMsg( "tog-$tog" );
	}

	function getLanguageNames() {
		global $wgLanguageNamesEn;
		return $wgLanguageNamesEn;
	}

	function getLanguageName( $code ) {
		global $wgLanguageNamesEn;
		if ( ! array_key_exists( $code, $wgLanguageNamesEn ) ) {
			return '';
		}
		return $wgLanguageNamesEn[$code];
	}

	function getMonthName( $key ) {
		global $wgMonthNamesEn, $wgContLang;
		// see who called us and use the correct message function
		if( get_class( $wgContLang->getLangObj() ) == get_class( $this ) )
			return wfMsgForContent($wgMonthNamesEn[$key-1]);
		else
			return wfMsg($wgMonthNamesEn[$key-1]);
	}

	/* by default we just return base form */
	function getMonthNameGen( $key ) {
		return $this->getMonthName( $key );
	}

	function getMonthAbbreviation( $key ) {
		global $wgMonthAbbreviationsEn, $wgContLang;
		// see who called us and use the correct message function
		if( get_class( $wgContLang->getLangObj() ) == get_class( $this ) )
			return wfMsgForContent(@$wgMonthAbbreviationsEn[$key-1]);
		else
			return wfMsg(@$wgMonthAbbreviationsEn[$key-1]);
	}

	function getWeekdayName( $key ) {
		global $wgWeekdayNamesEn, $wgContLang;
		// see who called us and use the correct message function
		if( get_class( $wgContLang->getLangObj() ) == get_class( $this ) )
			return wfMsgForContent($wgWeekdayNamesEn[$key-1]);
		else
			return wfMsg($wgWeekdayNamesEn[$key-1]);
	}

	/**
	 * Used by date() and time() to adjust the time output.
	 * @access public
	 * @param int   $ts the time in date('YmdHis') format
	 * @param mixed $tz adjust the time by this amount (default false)
	 * @return int
	 */
	function userAdjust( $ts, $tz = false )	{
		global $wgUser, $wgLocalTZoffset;

		if (!$tz) {
			$tz = $wgUser->getOption( 'timecorrection' );
		}

		if ( $tz === '' ) {
			$hrDiff = isset( $wgLocalTZoffset ) ? $wgLocalTZoffset : 0;
			$minDiff = 0;
		} elseif ( strpos( $tz, ':' ) !== false ) {
			$tzArray = explode( ':', $tz );
			$hrDiff = intval($tzArray[0]);
			$minDiff = intval($hrDiff < 0 ? -$tzArray[1] : $tzArray[1]);
		} else {
			$hrDiff = intval( $tz );
		}
		if ( 0 == $hrDiff && 0 == $minDiff ) { return $ts; }

		$t = mktime( (
		  (int)substr( $ts, 8, 2) ) + $hrDiff, # Hours
		  (int)substr( $ts, 10, 2 ) + $minDiff, # Minutes
		  (int)substr( $ts, 12, 2 ), # Seconds
		  (int)substr( $ts, 4, 2 ), # Month
		  (int)substr( $ts, 6, 2 ), # Day
		  (int)substr( $ts, 0, 4 ) ); #Year
		return date( 'YmdHis', $t );
	}

	/**
	 * This is meant to be used by time(), date(), and timeanddate() to get
	 * the date preference they're supposed to use, it should be used in
	 * all children.
	 *
	 *<code>
	 * function timeanddate([...], $format = true) {
	 * 	$datePreference = $this->dateFormat($format);
	 * [...]
	 *</code>
	 *
	 * @param bool $usePrefs: if false, the site/language default is used
	 * @return string
	 */
	function dateFormat( $usePrefs = true ) {
		global $wgUser, $wgAmericanDates;

		if( $usePrefs ) {
			$datePreference = $wgUser->getOption( 'date' );
		} else {
			$options = $this->getDefaultUserOptions();
			$datePreference = (string)$options['date'];
		}

		if( $datePreference == MW_DATE_DEFAULT || $datePreference == '' ) {
			return $wgAmericanDates ? MW_DATE_MDY : MW_DATE_DMY;
		}
		return $datePreference;
	}

	/**
	 * @access public
	 * @param mixed  $ts the time format which needs to be turned into a
	 *               date('YmdHis') format with wfTimestamp(TS_MW,$ts)
	 * @param bool   $adj whether to adjust the time output according to the
	 *               user configured offset ($timecorrection)
	 * @param bool   $format true to use user's date format preference
	 * @param string $timecorrection the time offset as returned by
	 *               validateTimeZone() in Special:Preferences
	 * @return string
	 */
	function date( $ts, $adj = false, $format = true, $timecorrection = false ) {
		global $wgUser;

		if ( $adj ) { $ts = $this->userAdjust( $ts, $timecorrection ); }

		$datePreference = $this->dateFormat( $format );

		$month = $this->getMonthName( substr( $ts, 4, 2 ) );
		$day = $this->formatNum( 0 + substr( $ts, 6, 2 ) );
		$year = $this->formatNum( substr( $ts, 0, 4 ), true );

		switch( $datePreference ) {
			case MW_DATE_DMY: return "$day $month $year";
			case MW_DATE_YMD: return "$year $month $day";
			case MW_DATE_ISO: return substr($ts, 0, 4). '-' . substr($ts, 4, 2). '-' .substr($ts, 6, 2);
			default: return "$month $day, $year";
		}
	}

	/**
	* @access public
	* @param mixed  $ts the time format which needs to be turned into a
	*               date('YmdHis') format with wfTimestamp(TS_MW,$ts)
	* @param bool   $adj whether to adjust the time output according to the
	*               user configured offset ($timecorrection)
	* @param bool   $format true to use user's date format preference
	* @param string $timecorrection the time offset as returned by
	*               validateTimeZone() in Special:Preferences
	* @return string
	*/
	function time( $ts, $adj = false, $format = true, $timecorrection = false ) {
		global $wgUser;

		if ( $adj ) { $ts = $this->userAdjust( $ts, $timecorrection ); }
		$datePreference = $this->dateFormat( $format );

		$t = substr( $ts, 8, 2 ) . ':' . substr( $ts, 10, 2 );

		if ( $datePreference == MW_DATE_ISO ) {
			$t .= ':' . substr( $ts, 12, 2 );
		}
		return $this->formatNum( $t );
	}

	/**
	* @access public
	* @param mixed  $ts the time format which needs to be turned into a
	*               date('YmdHis') format with wfTimestamp(TS_MW,$ts)
	* @param bool   $adj whether to adjust the time output according to the
	*               user configured offset ($timecorrection)
	* @param bool   $format true to use user's date format preference
	* @param string $timecorrection the time offset as returned by
	*               validateTimeZone() in Special:Preferences
	* @return string
	*/
	function timeanddate( $ts, $adj = false, $format = true, $timecorrection = false) {
		global $wgUser;

		$datePreference = $this->dateFormat($format);
		switch ( $datePreference ) {
			case MW_DATE_ISO: return $this->date( $ts, $adj, $format, $timecorrection ) . ' ' .
				$this->time( $ts, $adj, $format, $timecorrection );
			default: return $this->time( $ts, $adj, $format, $timecorrection ) . ', ' .
				$this->date( $ts, $adj, $format, $timecorrection );
		}
	}

	function getMessage( $key ) {
		global $wgAllMessagesEn;
		return @$wgAllMessagesEn[$key];
	}

	function getAllMessages() {
		global $wgAllMessagesEn;
		return $wgAllMessagesEn;
	}

	function iconv( $in, $out, $string ) {
		# For most languages, this is a wrapper for iconv
		return iconv( $in, $out, $string );
	}

	function ucfirst( $string ) {
		# For most languages, this is a wrapper for ucfirst()
		return ucfirst( $string );
	}

	function lcfirst( $s ) {
		return strtolower( $s{0} ). substr( $s, 1 );
	}

	function checkTitleEncoding( $s ) {
		global $wgInputEncoding;

		# Check for UTF-8 URLs; Internet Explorer produces these if you
		# type non-ASCII chars in the URL bar or follow unescaped links.
		$ishigh = preg_match( '/[\x80-\xff]/', $s);
		$isutf = ($ishigh ? preg_match( '/^([\x00-\x7f]|[\xc0-\xdf][\x80-\xbf]|' .
		         '[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xf7][\x80-\xbf]{3})+$/', $s ) : true );

		if( ($wgInputEncoding != 'utf-8') and $ishigh and $isutf )
			return @iconv( 'UTF-8', $wgInputEncoding, $s );

		if( ($wgInputEncoding == 'utf-8') and $ishigh and !$isutf )
			return utf8_encode( $s );

		# Other languages can safely leave this function, or replace
		# it with one to detect and convert another legacy encoding.
		return $s;
	}

	/**
	 * Some languages have special punctuation to strip out
	 * or characters which need to be converted for MySQL's
	 * indexing to grok it correctly. Make such changes here.
	 *
	 * @param string $in
	 * @return string
	 */
	function stripForSearch( $in ) {
		return strtolower( $in );
	}

	function convertForSearchResult( $termsArray ) {
		# some languages, e.g. Chinese, need to do a conversion
		# in order for search results to be displayed correctly
		return $termsArray;
	}

	/**
	 * Get the first character of a string. In ASCII, return
	 * first byte of the string. UTF8 and others have to
	 * overload this.
	 *
	 * @param string $s
	 * @return string
	 */
	function firstChar( $s ) {
		return $s[0];
	}

	function initEncoding() {
		# Some languages may have an alternate char encoding option
		# (Esperanto X-coding, Japanese furigana conversion, etc)
		# If this language is used as the primary content language,
		# an override to the defaults can be set here on startup.
		#global $wgInputEncoding, $wgOutputEncoding, $wgEditEncoding;
	}

	function setAltEncoding() {
		# Some languages may have an alternate char encoding option
		# (Esperanto X-coding, Japanese furigana conversion, etc)
		# If 'altencoding' is checked in user prefs, this gives a
		# chance to swap out the default encoding settings.
		#global $wgInputEncoding, $wgOutputEncoding, $wgEditEncoding;
	}

	function recodeForEdit( $s ) {
		# For some languages we'll want to explicitly specify
		# which characters make it into the edit box raw
		# or are converted in some way or another.
		# Note that if wgOutputEncoding is different from
		# wgInputEncoding, this text will be further converted
		# to wgOutputEncoding.
		global $wgInputEncoding, $wgEditEncoding;
		if( $wgEditEncoding == '' or
		  $wgEditEncoding == $wgInputEncoding ) {
			return $s;
		} else {
			return $this->iconv( $wgInputEncoding, $wgEditEncoding, $s );
		}
	}

	function recodeInput( $s ) {
		# Take the previous into account.
		global $wgInputEncoding, $wgOutputEncoding, $wgEditEncoding;
		if($wgEditEncoding != "") {
			$enc = $wgEditEncoding;
		} else {
			$enc = $wgOutputEncoding;
		}
		if( $enc == $wgInputEncoding ) {
			return $s;
		} else {
			return $this->iconv( $enc, $wgInputEncoding, $s );
		}
	}

	/**
	 * For right-to-left language support
	 *
	 * @return bool
	 */
	function isRTL() { return false; }

	/**
	 * To allow "foo[[bar]]" to extend the link over the whole word "foobar"
	 *
	 * @return bool
	 */
	function linkPrefixExtension() { return false; }


	function &getMagicWords() {
		global $wgMagicWordsEn;
		return $wgMagicWordsEn;
	}

	# Fill a MagicWord object with data from here
	function getMagic( &$mw ) {
		$raw = $this->getMagicWords();
		if( !isset( $raw[$mw->mId] ) ) {
			# Fall back to English if local list is incomplete
			$raw =& Language::getMagicWords();
		}
		$rawEntry = $raw[$mw->mId];
		$mw->mCaseSensitive = $rawEntry[0];
		$mw->mSynonyms = array_slice( $rawEntry, 1 );
	}

	/**
	 * Italic is unsuitable for some languages
	 *
	 * @access public
	 *
	 * @param string $text The text to be emphasized.
	 * @return string
	 */
	function emphasize( $text ) {
		return "<em>$text</em>";
	}

	/**
	 * This function enables formatting of numbers, it should only come
	 * into effect when the $wgTranslateNumerals variable is TRUE.
	 *
	 * Normally we output all numbers in plain en_US style, that is
	 * 293,291.235 for twohundredninetythreethousand-twohundredninetyone
	 * point twohundredthirtyfive. However this is not sutable for all
	 * languages, some such as Pakaran want ੨੯੩,੨੯੫.੨੩੫ and others such as
	 * Icelandic just want to use commas instead of dots, and dots instead
	 * of commas like "293.291,235".
	 *
	 * An example of this function being called:
	 * <code>
	 * wfMsg( 'message', $wgLang->formatNum( $num ) )
	 * </code>
	 *
	 * See LanguageGu.php for the Gujarati implementation and
	 * LanguageIs.php for the , => . and . => , implementation.
	 *
	 * @todo check if it's viable to use localeconv() for the decimal
	 *       seperator thing.
	 * @access public
	 * @param mixed $number the string to be formatted, should be an integer or
	 *        a floating point number.
	 * @param bool $year are we being passed a year? (turns off commafication)
	 * @return mixed whatever we're fed if it's a year, a string otherwise.
	 */
	function formatNum( $number, $year = false ) {
		return $year ? $number : $this->commafy($number);
	}

	/**
	 * Adds commas to a given number
	 *
	 * @param mixed $_
	 * @return string
	 */
	function commafy($_) {
		return strrev((string)preg_replace('/(\d{3})(?=\d)(?!\d*\.)/','$1,',strrev($_)));
	}

	/**
	 * For the credit list in includes/Credits.php (action=credits)
	 *
	 * @param array $l
	 * @return string
	 */
	function listToText( $l ) {
		$s = '';
		$m = count($l) - 1;
		for ($i = $m; $i >= 0; $i--) {
			if ($i == $m) {
				$s = $l[$i];
			} else if ($i == $m - 1) {
				$s = $l[$i] . ' ' . $this->getMessage('and') . ' ' . $s;
			} else {
				$s = $l[$i] . ', ' . $s;
			}
		}
		return $s;
	}

	# Crop a string from the beginning or end to a certain number of bytes.
	# (Bytes are used because our storage has limited byte lengths for some
	# columns in the database.) Multibyte charsets will need to make sure that
	# only whole characters are included!
	#
	# $length does not include the optional ellipsis.
	# If $length is negative, snip from the beginning
	function truncate( $string, $length, $ellipsis = '' ) {
		if( $length == 0 ) {
			return $ellipsis;
		}
		if ( strlen( $string ) <= abs( $length ) ) {
			return $string;
		}
		if( $length > 0 ) {
			$string = substr( $string, 0, $length );
			return $string . $ellipsis;
		} else {
			$string = substr( $string, $length );
			return $ellipsis . $string;
		}
	}

	/**
	 * Grammatical transformations, needed for inflected languages
	 * Invoked by putting {{grammar:case|word}} in a message
	 *
	 * @param string $word
	 * @param string $case
	 * @return string
	 */
	function convertGrammar( $word, $case ) {
		return $word;
	}

	/**
	 * languages like Chinese need to be segmented in order for the diff
	 * to be of any use
	 *
	 * @param string $text
	 * @return string
	 */
	function segmentForDiff( $text ) {
		return $text;
	}

	/**
	 * and unsegment to show the result
	 *
	 * @param string $text
	 * @return string
	 */
	function unsegmentForDiff( $text ) {
		return $text;
	}

	# convert text to different variants of a language.
	function convert( $text, $isTitle = false) {
		return $this->mConverter->convert($text, $isTitle);
	}

	/**
	 * Perform output conversion on a string, and encode for safe HTML output.
	 * @param string $text
	 * @param bool $isTitle -- wtf?
	 * @return string
	 * @todo this should get integrated somewhere sane
	 */
	function convertHtml( $text, $isTitle = false ) {
		return htmlspecialchars( $this->convert( $text, $isTitle ) );
	}

	function convertCategoryKey( $key ) {
		return $this->mConverter->convertCategoryKey( $key );
	}

	/**
	 * get the list of variants supported by this langauge
	 * see sample implementation in LanguageZh.php
	 *
	 * @return array an array of language codes
	 */
	function getVariants() {
		return $this->mConverter->getVariants();
	}


	function getPreferredVariant() {
		return $this->mConverter->getPreferredVariant();
	}

	/**
	 * if a language supports multiple variants, it is
	 * possible that non-existing link in one variant
	 * actually exists in another variant. this function
	 * tries to find it. See e.g. LanguageZh.php
	 *
	 * @param string $link the name of the link
	 * @param mixed $nt the title object of the link
	 * @return null the input parameters may be modified upon return
	 */
	function findVariantLink( &$link, &$nt ) {
		$this->mConverter->findVariantLink($link, $nt);
	}

	/**
	 * returns language specific options used by User::getPageRenderHash()
	 * for example, the preferred language variant
	 *
	 * @return string
	 * @access public
	 */
	function getExtraHashOptions() {
		return $this->mConverter->getExtraHashOptions();
	}

	/**
	 * for languages that support multiple variants, the title of an
	 * article may be displayed differently in different variants. this
	 * function returns the apporiate title defined in the body of the article.
	 *
	 * @return string
	 */
	function getParsedTitle() {
		return $this->mConverter->getParsedTitle();
	}

	/**
	 * Enclose a string with the "no conversion" tag. This is used by
	 * various functions in the Parser
	 *
	 * @param string $text text to be tagged for no conversion
	 * @return string the tagged text
	*/
	function markNoConversion( $text ) {
		return $this->mConverter->markNoConversion( $text );
	}

	/**
	 * A regular expression to match legal word-trailing characters
	 * which should be merged onto a link of the form [[foo]]bar.
	 *
	 * @return string
	 * @access public
	 */
	function linkTrail() {
		return $this->getMessage( 'linktrail' );
	}

	function getLangObj() {
		return $this;
	}

	/**
	 * Get the RFC 3066 code for this language object
	 */
	function getCode() {
		return str_replace( '_', '-', strtolower( substr( get_class( $this ), 8 ) ) );
	}


}

# This should fail gracefully if there's not a localization available
wfSuppressWarnings();
include_once( 'Language' . str_replace( '-', '_', ucfirst( $wgLanguageCode ) ) . '.php' );
wfRestoreWarnings();

}
?>

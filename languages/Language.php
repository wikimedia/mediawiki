<?php
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

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#

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
	NS_CATEGORY_TALK    => 'Category_talk'
);

if(isset($wgExtraNamespaces)) {
	$wgNamespaceNamesEn=$wgNamespaceNamesEn+$wgExtraNamespaces;
}

/* private */ $wgDefaultUserOptionsEn = array(
	'quickbar' => 1, 'underline' => 1, 'hover' => 1,
	'cols' => 80, 'rows' => 25, 'searchlimit' => 20,
	'contextlines' => 5, 'contextchars' => 50,
	'skin' => $wgDefaultSkin, 'math' => 1, 'rcdays' => 7, 'rclimit' => 50,
	'highlightbroken' => 1, 'stubthreshold' => 0,
	'previewontop' => 1, 'editsection'=>1,'editsectiononrightclick'=>0, 'showtoc'=>1,
	'showtoolbar' =>1,
	'date' => 0, 'imagesize' => 2
);

/* private */ $wgQuickbarSettingsEn = array(
	'None', 'Fixed left', 'Fixed right', 'Floating left'
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

define( 'MW_MATH_PNG',    0 );
define( 'MW_MATH_SIMPLE', 1 );
define( 'MW_MATH_HTML',   2 );
define( 'MW_MATH_SOURCE', 3 );
define( 'MW_MATH_MODERN', 4 );
define( 'MW_MATH_MATHML', 5 );

# Validation types
$wgValidationTypesEn = array (
	'0' => "Style|Awful|Awesome|5",
	'1' => "Legal|Illegal|Legal|5",
	'2' => "Completeness|Stub|Extensive|5",
	'3' => "Facts|Wild guesses|Solid as a rock|5",
	'4' => "Suitable for 1.0 (paper)|No|Yes|2",
	'5' => "Suitable for 1.0 (CD)|No|Yes|2"
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
define( 'MW_DATE_DEFAULT', false );
define( 'MW_DATE_USER_FORMAT', true );

/* private */ $wgDateFormatsEn = array(
	'No preference',
	'January 15, 2001',
	'15 January 2001',
	'2001 January 15',
	'2001-01-15'
);

/* private */ $wgUserTogglesEn = array(
	'hover',
	'underline',
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
);

/* private */ $wgBookstoreListEn = array(
	'AddALL' => 'http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN',
	'PriceSCAN' => 'http://www.pricescan.com/books/bookDetail.asp?isbn=$1',
	'Barnes & Noble' => 'http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1',
	'Amazon.com' => 'http://www.amazon.com/exec/obidos/ISBN=$1'
);

# Read language names
global $wgLanguageNames;
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
	MAG_CURRENTDAY           => array( 1,    'CURRENTDAY'             ),
	MAG_CURRENTDAYNAME       => array( 1,    'CURRENTDAYNAME'         ),
	MAG_CURRENTYEAR          => array( 1,    'CURRENTYEAR'            ),
	MAG_CURRENTTIME          => array( 1,    'CURRENTTIME'            ),
	MAG_NUMBEROFARTICLES     => array( 1,    'NUMBEROFARTICLES'       ),
	MAG_CURRENTMONTHNAMEGEN  => array( 1,    'CURRENTMONTHNAMEGEN'    ),
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
	MAG_GRAMMAR              => array( 0,    'GRAMMAR:'               )
);

#-------------------------------------------------------------------
# Default messages
#-------------------------------------------------------------------
# Allowed characters in keys are: A-Z, a-z, 0-9, underscore (_) and
# hyphen (-). If you need more characters, you may be able to change
# the regex in MagicWord::initRegex

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => ""

# NOTE: To turn off "Disclaimers" in the title links,
# set "disclaimers" => ""

# NOTE: To turn off "Community portal" in the title links,
# set "portal" => ""


# required for copyrightwarning
global $wgRightsText;

/* private */ $wgAllMessagesEn = array(
'special_version_prefix' => '&nbsp;',
'special_version_postfix' => '&nbsp;',
# User preference toggles
'tog-hover'		=> 'Show hoverbox over wiki links',
'tog-underline' => 'Underline links',
'tog-highlightbroken' => 'Format broken links <a href="" class="new">like this</a> (alternative: like this<a href="" class="internal">?</a>).',
'tog-justify'	=> 'Justify paragraphs',
'tog-hideminor' => 'Hide minor edits in recent changes',
'tog-usenewrc' => 'Enhanced recent changes (not for all browsers)',
'tog-numberheadings' => 'Auto-number headings',
'tog-showtoolbar'=>'Show edit toolbar',
'tog-editondblclick' => 'Edit pages on double click (JavaScript)',
'tog-editsection'=>'Enable section editing via [edit] links',
'tog-editsectiononrightclick'=>'Enable section editing by right clicking<br /> on section titles (JavaScript)',
'tog-showtoc'=>'Show table of contents<br />(for pages with more than 3 headings)',
'tog-rememberpassword' => 'Remember password across sessions',
'tog-editwidth' => 'Edit box has full width',
'tog-watchdefault' => 'Add pages you edit to your watchlist',
'tog-minordefault' => 'Mark all edits minor by default',
'tog-previewontop' => 'Show preview before edit box and not after it',
'tog-previewonfirst' => 'Show preview on first edit',
'tog-nocache' => 'Disable page caching',

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
'mainpage'		=> 'Main Page',
'mainpagetext'	=> 'Wiki software successfully installed.',
"mainpagedocfooter" => "Please see [http://meta.wikipedia.org/wiki/MediaWiki_i18n documentation on customizing the interface]
and the [http://meta.wikipedia.org/wiki/MediaWiki_User%27s_Guide User's Guide] for usage and configuration help.",
'portal'		=> 'Community portal',
'portal-url'		=> '{{ns:4}}:Community Portal',
'about'			=> 'About',
"aboutsite"      => "About {{SITENAME}}",
"aboutpage"		=> "{{ns:4}}:About",
'article' => 'Content page',
'help'			=> 'Help',
"helppage"		=> "{{ns:12}}:Contents",
"wikititlesuffix" => "{{SITENAME}}",
"bugreports"	=> "Bug reports",
"bugreportspage" => "{{ns:4}}:Bug_reports",
'sitesupport'   => 'Donations', 
'sitesupport-url' => '{{ns:4}}:Site support',
'faq'			=> 'FAQ',
"faqpage"		=> "{{ns:4}}:FAQ",
"edithelp"		=> "Editing help",
"newwindow"		=> "(opens in new window)",
"edithelppage"	=> "{{ns:12}}:Editing",
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
'currentevents' => 'Current events',
'currentevents-url' => 'Current events',
'disclaimers' => 'Disclaimers',
"disclaimerpage"		=> "{{ns:4}}:General_disclaimer",
"errorpagetitle" => "Error",
"returnto"		=> "Return to $1.",
"tagline"      	=> "From {{SITENAME}}",
'whatlinkshere'	=> 'Pages that link here',
'help'			=> 'Help',
'search'		=> 'Search',
'go'		=> 'Go',
"history"		=> 'Page history',
'history_short' => 'History',
'info_short'	=> 'Information',
'printableversion' => 'Printable version',
'edit' => 'Edit',
'editthispage'	=> 'Edit this page',
'delete' => 'Delete',
"deletethispage" => "Delete this page",
"undelete_short" => "Undelete $1 edits",
'protect' => 'Protect',
'protectthispage' => 'Protect this page',
'unprotect' => 'Unprotect',
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
'toolbox' => 'Toolbox',
'userpage' => 'View user page',
'wikipediapage' => 'View project page',
'imagepage' => 	'View image page',
'viewtalkpage' => 'View discussion',
'otherlanguages' => 'Other languages',
'redirectedfrom' => '(Redirected from $1)',
'lastmodified'	=> 'This page was last modified $1.',
'viewcount'		=> 'This page has been accessed $1 times.',
'copyright'	=> 'Content is available under $1.',
'poweredby'	=> "{{SITENAME}} is powered by [http://www.mediawiki.org/ MediaWiki], an open source wiki engine.",
'printsubtitle' => "(From {{SERVER}})",
'protectedpage' => 'Protected page',
'administrators' => "{{ns:4}}:Administrators",
'sysoptitle'	=> 'Sysop access required',
'sysoptext'		=> "The action you have requested can only be
performed by users with \"sysop\" status.
See $1.",
'developertitle' => 'Developer access required',
"developertext"	=> "The action you have requested can only be
performed by users with \"developer\" status.
See $1.",
'bureaucrattitle'	=> 'Bureaucrat access required',
"bureaucrattext"	=> "The action you have requested can only be
performed by sysops with  \"bureaucrat\" status.",
'nbytes'		=> '$1 bytes',
'go'			=> 'Go',
'ok'			=> 'OK',
'sitetitle'		=> "{{SITENAME}}",
'pagetitle'		=> "$1 - {{SITENAME}}",
'sitesubtitle'	=> 'The Free Encyclopedia', # FIXME
'retrievedfrom' => "Retrieved from \"$1\"",
'newmessages' => "You have $1.",
'newmessageslink' => 'new messages',
'editsection'=>'edit',
'toc' => 'Table of contents',
'showtoc' => 'show',
'hidetoc' => 'hide',
'thisisdeleted' => "View or restore $1?",
'restorelink' => "$1 deleted edits",
'feedlinks' => 'Feed:',
'sitenotice'	=> '', # the equivalent to wgSiteNotice

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main' => 'Article',
'nstab-user' => 'User page',
'nstab-media' => 'Media',
'nstab-special' => 'Special',
'nstab-wp' => 'About',
'nstab-image' => 'Image',
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
'nospecialpagetext' => 'You have requested a special page that is not
recognized by the wiki.',

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
'readonly'		=> 'Database locked',
'enterlockreason' => 'Enter a reason for the lock, including an estimate
of when the lock will be released',
'readonlytext'	=> "The database is currently locked to new
entries and other modifications, probably for routine database maintenance,
after which it will be back to normal.
The administrator who locked it offered this explanation:
<p>$1",
'missingarticle' => "The database did not find the text of a page
that it should have found, named \"$1\".

<p>This is usually caused by following an outdated diff or history link to a
page that has been deleted.

<p>If this is not the case, you may have found a bug in the software.
Please report this to an administrator, making note of the URL.",
'internalerror' => 'Internal error',
'filecopyerror' => "Could not copy file \"$1\" to \"$2\".",
'filerenameerror' => "Could not rename file \"$1\" to \"$2\".",
'filedeleteerror' => "Could not delete file \"$1\".",
'filenotfound'	=> "Could not find file \"$1\".",
'unexpected'	=> "Unexpected value: \"$1\"=\"$2\".",
'formerror'		=> 'Error: could not submit form',
'badarticleerror' => 'This action cannot be performed on this page.',
'cannotdelete'	=> 'Could not delete the page or image specified. (It may have already been deleted by someone else.)',
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
[[{{ns:4}}:Protected page]].

You can view and copy the source of this page:",
'seriousxhtmlerrors' => 'There were serious xhtml markup errors detected by tidy.',

# Login and logout pages
#
"logouttitle"	=> 'User logout',
"logouttext" => "You are now logged out.
You can continue to use {{SITENAME}} anonymously, or you can log in
again as the same or as a different user. Note that some pages may
continue to be displayed as if you were still logged in, until you clear
your browser cache\n",

'welcomecreation' => "== Welcome, $1! ==

Your account has been created. Don't forget to change your {{SITENAME}} preferences.",

'loginpagetitle' => 'User login',
'yourname'		=> 'Your user name',
'yourpassword'	=> 'Your password',
'yourpasswordagain' => 'Retype password',
'newusersonly'	=> ' (new users only)',
'remembermypassword' => 'Remember my password across sessions.',
'loginproblem'	=> '<b>There has been a problem with your login.</b><br />Try again!',
'alreadyloggedin' => "<font color=red><b>User $1, you are already logged in!</b></font><br />\n",

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
'youremail'		=> 'Your email*',
'yourrealname'		=> 'Your real name*',
'yourlanguage'	=> 'Interface language',
'yourvariant'  => 'Language variant',
'yournick'		=> 'Your nickname (for signatures)',
'emailforlost'	=> "Fields marked with a star (*) are optional.  Storing an email address enables people to contact you through the website without you having to reveal your
email address to them, and it can be used to send you a new password if you forget it.<br /><br />Your real name, if you choose to provide it, will be used for giving you attribution for your work.",
'prefs-help-userdata' => '* <strong>Real name</strong> (optional): if you choose to provide it this will be used for giving you attribution for your work.<br/>
* <strong>Email</strong> (optional): Enables people to contact you through the website without you having to reveal your
email address to them, and it can be used to send you a new password if you forget it.',
'loginerror'	=> 'Login error',
'nocookiesnew'	=> "The user account was created, but you are not logged in. {{SITENAME}} uses cookies to log in users. You have cookies disabled. Please enable them, then log in with your new username and password.",
"nocookieslogin"	=> "{{SITENAME}} uses cookies to log in users. You have cookies disabled. Please enable them and try again.",
'noname'		=> 'You have not specified a valid user name.',
'loginsuccesstitle' => 'Login successful',
'loginsuccess'	=> "You are now logged in to {{SITENAME}} as \"$1\".",
'nosuchuser'	=> "There is no user by the name \"$1\".
Check your spelling, or use the form below to create a new user account.",
'nosuchusershort'	=> "There is no user by the name \"$1\". Check your spelling.",
'wrongpassword'	=> 'The password you entered is incorrect. Please try again.',
'mailmypassword' => 'Mail me a new password',
'passwordremindertitle' => "Password reminder from {{SITENAME}}",
'passwordremindertext' => "Someone (probably you, from IP address $1)
requested that we send you a new {{SITENAME}} login password.
The password for user \"$2\" is now \"$3\".
You should log in and change your password now.",
'noemail'		=> "There is no e-mail address recorded for user \"$1\".",
'passwordsent'	=> "A new password has been sent to the e-mail address
registered for \"$1\".
Please log in again after you receive it.",
'loginend'		=> '&nbsp;',
'mailerror' => "Error sending mail: $1",
'acct_creation_throttle_hit' => 'Sorry, you have already created $1 accounts. You can\'t make any more.',

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
'media_sample'=>'Example.mp3',
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
'blockedtitle'	=> 'User is blocked',
'blockedtext'	=> "Your user name or IP address has been blocked by $1.
The reason given is this:<br />''$2''<p>You may contact $1 or one of the other
[[{{ns:4}}:Administrators|administrators]] to discuss the block.

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
'accmailtext' => "The Password for '$1' has been sent to $2.",
'newarticle'	=> '(New)',
'newarticletext' =>
"You've followed a link to a page that doesn't exist yet.
To create the page, start typing in the box below
(see the [[{{ns:4}}:Help|help page]] for more info).
If you are here by mistake, just click your browser's '''back''' button.",
'talkpagetext' => '<!-- MediaWiki:talkpagetext -->',
'anontalkpagetext' => "----''This is the discussion page for an anonymous user who has not created an account yet or who does not use it. We therefore have to use the numerical [[IP address]] to identify him/her. Such an IP address can be shared by several users. If you are an anonymous user and feel that irrelevant comments have been directed at you, please [[Special:Userlogin|create an account or log in]] to avoid future confusion with other anonymous users.'' ",
'noarticletext' => '(There is currently no text in this page)',
'clearyourcache' => "'''Note:''' After saving, you have to clear your browser cache to see the changes: '''Mozilla:''' click ''Reload'' (or ''Ctrl-R''), '''IE / Opera:''' ''Ctrl-F5'', '''Safari:''' ''Cmd-R'', '''Konqueror''' ''Ctrl-R''.",
'usercssjsyoucanpreview' => "<strong>Tip:</strong> Use the 'Show preview' button to test your new CSS/JS before saving.",
'usercsspreview' => "'''Remember that you are only previewing your user CSS, it has not yet been saved!'''",
'userjspreview' => "'''Remember that you are only testing/previewing your user JavaScript, it has not yet been saved!'''",
'updated'		=> '(Updated)',
'note'			=> '<strong>Note:</strong> ',
'previewnote'	=> 'Remember that this is only a preview, and has not yet been saved!',
'previewconflict' => 'This preview reflects the text in the upper
text editing area as it will appear if you choose to save.',
'editing'		=> "Editing $1",
"sectionedit"	=> " (section)",
'commentedit'	=> ' (comment)',
'editconflict'	=> 'Edit conflict: $1',
'explainconflict' => "Someone else has changed this page since you
started editing it.
The upper text area contains the page text as it currently exists.
Your changes are shown in the lower text area.
You will have to merge your changes into the existing text.
<b>Only</b> the text in the upper text area will be saved when you
press \"Save page\".\n<p>",
'yourtext'		=> 'Your text',
'storedversion' => 'Stored version',
'editingold'	=> "<strong>WARNING: You are editing an out-of-date
revision of this page.
If you save it, any changes made since this revision will be lost.</strong>\n",
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
'longpagewarning' => "WARNING: This page is $1 kilobytes long; some
browsers may have problems editing pages approaching or longer than 32kb.
Please consider breaking the page into smaller sections.",
'readonlywarning' => 'WARNING: The database has been locked for maintenance,
so you will not be able to save your edits right now. You may wish to cut-n-paste
the text into a text file and save it for later.',
'protectedpagewarning' => "WARNING:  This page has been locked so that only
users with sysop privileges can edit it. Be sure you are following the
<a href='$wgScript/{{ns:4}}:Protected_page_guidelines'>protected page
guidelines</a>.",
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
'revisionasofwithlink'  => '(Revision as of $1; $2)',
'currentrevisionlink'   => 'view current revision',
'cur'			=> 'cur',
'next'			=> 'next',
'last'			=> 'last',
'orig'			=> 'orig',
'histlegend'	=> 'Diff selection: mark the radio boxes of the versions to compare and hit enter or the button at the bottom.<br/>
Legend: (cur) = difference with current version,
(last) = difference with preceding version, M = minor edit.',
'history_copyright'    => '-',

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
'nogomatch' => 'No page with this exact title exists, trying full text search.',
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
"searchdisabled" => "<p>Sorry! Full text search has been disabled temporarily, for performance reasons. In the meantime, you can use the Google search below, which may be out of date.</p>",
'googlesearch' => "
<!-- SiteSearch Google -->
<FORM method=GET action=\"http://www.google.com/search\">
<TABLE bgcolor=\"#FFFFFF\"><tr><td>
<A HREF=\"http://www.google.com/\">
<IMG SRC=\"http://www.google.com/logos/Logo_40wht.gif\"
border=\"0\" ALT=\"Google\"></A>
</td>
<td>
<INPUT TYPE=text name=q size=31 maxlength=255 value=\"$1\">
<INPUT type=submit name=btnG VALUE=\"Google Search\">
<font size=-1>
<input type=hidden name=domains value=\"{{SERVER}}\"><br /><input type=radio name=sitesearch value=\"\"> WWW <input type=radio name=sitesearch value=\"{{SERVER}}\" checked> {{SERVER}} <br />
<input type='hidden' name='ie' value='$2'>
<input type='hidden' name='oe' value='$2'>
</font>
</td></tr></TABLE>
</FORM>
<!-- SiteSearch Google -->",
'blanknamespace' => '(Main)',

# Preferences page
#
'preferences'	=> 'Preferences',
'prefsnologin' => 'Not logged in',
'prefsnologintext'	=> "You must be <a href=\"{{localurl:Special:Userlogin}}\">logged in</a>
to set user preferences.",
'prefslogintext' => "You are logged in as \"$1\".
Your internal ID number is $2.

See [[{{ns:4}}:User preferences help]] for help deciphering the options.",
'prefsreset'	=> 'Preferences have been reset from storage.',
'qbsettings'	=> 'Quickbar settings',
'qbsettingsnote'	=> 'This preference only works in the \'Standard\' and the \'CologneBlue\' skin.',
'changepassword' => 'Change password',
'skin'			=> 'Skin',
'math'			=> 'Rendering math',
'dateformat'	=> 'Date format',
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
'prefs-rc' => 'Recent changes and stub display',
'prefs-misc' => 'Misc settings',
'saveprefs'		=> 'Save preferences',
'resetprefs'	=> 'Reset preferences',
'oldpassword'	=> 'Old password',
'newpassword'	=> 'New password',
'retypenew'		=> 'Retype new password',
'textboxsize'	=> 'Editing',
'rows'			=> 'Rows',
'columns'		=> 'Columns',
'searchresultshead' => 'Search result settings',
'resultsperpage' => 'Hits to show per page',
'contextlines'	=> 'Lines to show per hit',
'contextchars'	=> 'Characters of context per line',
'stubthreshold' => 'Threshold for stub display',
'recentchangescount' => 'Number of titles in recent changes',
'savedprefs'	=> 'Your preferences have been saved.',
'timezonelegend' => 'Time zone',
'timezonetext'	=> 'Enter number of hours your local time differs
from server time (UTC).',
'localtime'	=> 'Local time display',
'timezoneoffset' => 'Offset',
'servertime'	=> 'Server time is now',
'guesstimezone' => 'Fill in from browser',
'emailflag'		=> 'Disable e-mail from other users',
'defaultns'		=> 'Search in these namespaces by default:',

# User levels special page
#

# switching pan
'userlevels-lookup-group' => 'Manage group rights',
'userlevels-group-edit' => 'Existent groups: ',
'editgroup' => 'Edit Group',
'addgroup' => 'Add Group',

'userlevels-lookup-user' => 'Manage user groups',
'userlevels-user-editname' => 'Enter a username: ',
'editusergroup' => 'Edit User Groups',

# group editing
'userlevels-editgroup' => 'Edit group',
'userlevels-addgroup' => 'Add group',
'userlevels-editgroup-name' => 'Group name: ',
'userlevels-editgroup-description' => 'Group description (max 255 characters):<br />',
'savegroup' => 'Save Group',

# user groups editing
'userlevels-editusergroup' => 'Edit user groups',
'saveusergroups' => 'Save User Groups',
'userlevels-groupsmember' => 'Member of:',
'userlevels-groupsavailable' => 'Available groups:',
'userlevels-groupshelp' => 'Select groups you want the user to be removed from or added to.
Unselected groups will not be changed. You can unselect a group by using CTRL + Left Click',
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
'hide'			=> 'hide',
'show'			=> 'show',
'tableform'		=> 'table',
'listform'		=> 'list',
'nchanges'		=> "$1 changes",
'minoreditletter' => 'm',
'newpageletter' => 'N',
'sectionlink' => '&rarr;',

# Upload
#
'upload'		=> 'Upload file',
'uploadbtn'		=> 'Upload file',
'uploadlink'	=> 'Upload images',
'reupload'		=> 'Re-upload',
'reuploaddesc'	=> 'Return to the upload form.',
'uploadnologin' => 'Not logged in',
'uploadnologintext'	=> "You must be <a href=\"{{localurl:Special:Userlogin}}\">logged in</a>
to upload files.",
'uploadfile'	=> 'Upload images, sounds, documents etc.',
'uploaderror'	=> 'Upload error',
'uploadtext'	=>
"'''STOP!''' Before you upload here,
make sure to read and follow the [[Project:Image use policy|image use policy]].

To view or search previously uploaded images,
go to the [[Special:Imagelist|list of uploaded images]].
Uploads and deletions are logged on the
[[Project:Upload log|upload log]].

Use the form below to upload new image files for use in
illustrating your pages.
On most browsers, you will see a \"Browse...\" button, which will
bring up your operating system's standard file open dialog.
Choosing a file will fill the name of that file into the text
field next to the button.
You must also check the box affirming that you are not
violating any copyrights by uploading the file.
Press the \"Upload\" button to finish the upload.
This may take some time if you have a slow internet connection.

The preferred formats are JPEG for photographic images, PNG
for drawings and other iconic images, and OGG for sounds.
Please name your files descriptively to avoid confusion.
To include the image in a page, use a link in the form
'''<nowiki>[[{{ns:6}}:file.jpg]]</nowiki>''' or
'''<nowiki>[[{{ns:6}}:file.png|alt text]]</nowiki>''' or
'''<nowiki>[[{{ns:-2}}:file.ogg]]</nowiki>''' for sounds.

Please note that as with wiki pages, others may edit or
delete your uploads if they think it serves the project, and
you may be blocked from uploading if you abuse the system.",

'uploadlog'		=> 'upload log',
'uploadlogpage' => 'Upload_log',
'uploadlogpagetext' => 'Below is a list of the most recent file uploads.',
'filename'		=> 'Filename',
'filedesc'		=> 'Summary',
'filestatus' => 'Copyright status',
'filesource' => 'Source',
'affirmation'	=> "I affirm that the copyright holder of this file
agrees to license it under the terms of the $1.",
'copyrightpage' => "{{ns:4}}:Copyrights",
'copyrightpagename' => "{{SITENAME}} copyright",
'uploadedfiles'	=> 'Uploaded files',
'noaffirmation' => 'You must affirm that your upload does not violate any copyrights.',
'ignorewarning'	=> 'Ignore warning and save file anyway.',
'minlength'		=> 'Image names must be at least three letters.',
'illegalfilename'	=> 'The filename "$1" contains characters that are not allowed in page titles. Please rename the file and try uploading it again.',
'badfilename'	=> "Image name has been changed to \"$1\".",
'badfiletype'	=> "\".$1\" is not a recommended image file format.",
'largefile'		=> 'It is recommended that images not exceed 100k in size.',
'emptyfile'		=> 'The file you uploaded seems to be empty. This might be due to a typo in the file name. Please check whether you really want to upload this file.',
'fileexists'		=> 'A file with this name exists already, please check $1 if you are not sure if you want to change it.',
'successfulupload' => 'Successful upload',
'fileuploaded'	=> "File uploaded successfully.
Please follow this link: $2 to the description page and fill
in information about the file, such as where it came from, when it was
created and by whom, and anything else you may know about it. If this is an image, you can insert it like this: <tt><nowiki>[[Image:$1|thumb|Description]]</nowiki></tt>",
'uploadwarning' => 'Upload warning',
'savefile'		=> 'Save file',
'uploadedimage' => "uploaded \"$1\"",
'uploaddisabled' => 'Sorry, uploading is disabled.',
'uploadcorrupt' => 'The file is corrupt or has an incorrect extension. Please check the file and upload again.',

# Image list
#
'imagelist'		=> 'Image list',
'imagelisttext'	=> "Below is a list of $1 images sorted $2.",
'getimagelist'	=> 'fetching image list',
'ilshowmatch'	=> 'Show all images with names matching',
'ilsubmit'		=> 'Search',
'showlast'		=> "Show last $1 images sorted $2.",
'all'			=> 'all',
'byname'		=> 'by name',
'bydate'		=> 'by date',
'bysize'		=> 'by size',
'imgdelete'		=> 'del',
'imgdesc'		=> 'desc',
'imglegend'		=> 'Legend: (desc) = show/edit image description.',
'imghistory'	=> 'Image history',
'revertimg'		=> 'rev',
'deleteimg'		=> 'del',
'deleteimgcompletely'		=> 'Delete all revisions',
'imghistlegend' => 'Legend: (cur) = this is the current image, (del) = delete
this old version, (rev) = revert to this old version.
<br /><i>Click on date to see image uploaded on that date</i>.',
'imagelinks'	=> 'Image links',
'linkstoimage'	=> 'The following pages link to this image:',
'nolinkstoimage' => 'There are no pages that link to this image.',

# Statistics
#
'statistics'	=> 'Statistics',
'sitestats'		=> 'Site statistics',
'userstats'		=> 'User statistics',
'sitestatstext' => "There are '''$1''' total pages in the database.
This includes \"talk\" pages, pages about {{SITENAME}}, minimal \"stub\"
pages, redirects, and others that probably don't qualify as content pages.
Excluding those, there are '''$2''' pages that are probably legitimate
content pages.

There have been a total of '''$3''' page views, and '''$4''' page edits
since the wiki was setup.
That comes to '''$5''' average edits per page, and '''$6''' views per edit.",
'userstatstext' => "There are '''$1''' registered users.
'''$2''' of these are administrators (see $3).",

# Maintenance Page
#
'maintenance'		=> 'Maintenance page',
'maintnancepagetext'	=> 'This page includes several handy tools for everyday maintenance. Some of these functions tend to stress the database, so please do not hit reload after every item you fixed ;-)',
'maintenancebacklink'	=> 'Back to Maintenance Page',
'disambiguations'	=> 'Disambiguation pages',
'disambiguationspage'	=> "{{ns:4}}:Links_to_disambiguating_pages",
'disambiguationstext'	=> "The following pages link to a <i>disambiguation page</i>. They should link to the appropriate topic instead.<br />A page is treated as dismbiguation if it is linked from $1.<br />Links from other namespaces are <i>not</i> listed here.",
'doubleredirects'	=> 'Double Redirects',
'doubleredirectstext'	=> "<b>Attention:</b> This list may contain false positives. That usually means there is additional text with links below the first #REDIRECT.<br />\nEach row contains links to the first and second redirect, as well as the first line of the second redirect text, usually giving the \"real\" target page, which the first redirect should point to.",
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
'unusedimages'	=> 'Unused images',
'popularpages'	=> 'Popular pages',
'nviews'		=> '$1 views',
'wantedpages'	=> 'Wanted pages',
'nlinks'		=> '$1 links',
'allpages'		=> 'All pages',
'randompage'	=> 'Random page',
'randompage-url'=> 'Special:Randompage',
'shortpages'	=> 'Short pages',
'longpages'		=> 'Long pages',
'deadendpages'  => 'Dead-end pages',
'listusers'		=> 'User list',
'listadmins'	=> 'Admins list',
'specialpages'	=> 'Special pages',
'spheading'		=> 'Special pages for all users',
'sysopspheading' => 'For sysop use only',
'developerspheading' => 'For developer use only',
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
in active use.',
'booksources'	=> 'Book sources',
'categoriespagetext' => 'The following categories exists in the wiki.',
'data'	=> 'Data',
'userlevels' => 'User levels management',

# FIXME: Other sites, of course, may have affiliate relations with the booksellers list
'booksourcetext' => "Below is a list of links to other sites that
sell new and used books, and may also have further information
about books you are looking for.
{{SITENAME}} is not affiliated with any of these businesses, and
this list should not be construed as an endorsement.",
'isbn'	=> 'ISBN',
'rfcurl' =>  "http://www.faqs.org/rfcs/rfc$1.html",
'alphaindexline' => "$1 to $2",
'version'		=> 'Version',
'log'		=> 'Logs',
'alllogstext'	=> 'Combined display of upload, deletion, protection, blocking, and sysop logs.
You can narrow down the view by selecting a log type, the user name, or the affected page.',

# Special:Allpages
'nextpage'          => 'Next page ($1)',
'articlenamespace'  => '(articles)',
'allpagesformtext1' => 'Display pages starting at: $1',
'allpagesformtext2' => 'Choose namespace: $1 $2',
'allarticles'       => 'All articles',
'allpagesprev'      => 'Previous',
'allpagesnext'      => 'Next',
'allpagesnamespace' => 'All pages ($1 namespace)',
'allpagessubmit'    => 'Go',

# Email this user
#
'mailnologin'	=> 'No send address',
'mailnologintext' => "You must be <a href=\"{{localurl:Special:Userlogin\">logged in</a>
and have a valid e-mail address in your <a href=\"{{localurl:Special:Preferences}}\">preferences</a>
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
'watchnologintext'	=> "You must be <a href=\"{{localurl:Special:Userlogin}}\">logged in</a>
to modify your watchlist.",
'addedwatch'		=> 'Added to watchlist',
'addedwatchtext'	=> "The page \"$1\" has been added to your [[{{ns:-1}}:Watchlist|watchlist]].
Future changes to this page and its associated Talk page will be listed there,
and the page will appear '''bolded''' in the [[Special:Recentchanges|list of recent changes]] to
make it easier to pick out.

<p>If you want to remove the page from your watchlist later, click \"Stop watching\" in the sidebar.",
'removedwatch'		=> 'Removed from watchlist',
'removedwatchtext' 	=> "The page \"$1\" has been removed from your watchlist.",
'watch' => 'Watch',
'watchthispage'		=> 'Watch this page',
'unwatch' => 'Unwatch',
'unwatchthispage' 	=> 'Stop watching',
'notanarticle'		=> 'Not a content page',
'watchnochange' 	=> 'None of your watched items were edited in the time period displayed.',
'watchdetails'		=> "($1 pages watched not counting talk pages;
$2 total pages edited since cutoff;
$3...
<a href='$4'>show and edit complete list</a>.)",
'watchmethod-recent'=> 'checking recent edits for watched pages',
'watchmethod-list'	=> 'checking watched pages for recent edits',
'removechecked' 	=> 'Remove checked items from watchlist',
'watchlistcontains' => "Your watchlist contains $1 pages.",
'watcheditlist'		=> 'Here\'s an alphabetical list of your
watched pages. Check the boxes of pages you want to remove
from your watchlist and click the \'remove checked\' button
at the bottom of the screen.',
'removingchecked' 	=> 'Removing requested items from watchlist...',
'couldntremove' 	=> "Couldn't remove item '$1'...",
'iteminvalidname' 	=> "Problem with item '$1', invalid name...",
'wlnote' 			=> "Below are the last $1 changes in the last <b>$2</b> hours.",
'wlshowlast' 		=> "Show last $1 hours $2 days $3",
'wlsaved'			=> 'This is a saved version of your watchlist.',


# Delete/protect/revert
#
'deletepage'	=> 'Delete page',
'confirm'		=> 'Confirm',
'excontent' => 'content was:',
'exbeforeblank' => 'content before blanking was:',
'exblank' => 'page was empty',
'confirmdelete' => 'Confirm delete',
'deletesub'		=> "(Deleting \"$1\")",
'historywarning' => 'Warning: The page you are about to delete has a history: ',
'confirmdeletetext' => "You are about to permanently delete a page
or image along with all of its history from the database.
Please confirm that you intend to do this, that you understand the
consequences, and that you are doing this in accordance with
[[{{ns:4}}:Policy]].",
'confirmcheck'	=> 'Yes, I really want to delete this.',
'actioncomplete' => 'Action complete',
'deletedtext'	=> "\"$1\" has been deleted.
See $2 for a record of recent deletions.",
'deletedarticle' => "deleted \"$1\"",
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
'protectlogpage' => 'Protection_log',
'protectlogtext' => "Below is a list of page locks/unlocks.
See [[{{ns:4}}:Protected page]] for more information.",
'protectedarticle' => "protected [[$1]]",
'unprotectedarticle' => "unprotected [[$1]]",
'protectsub' =>"(Protecting \"$1\")",
'confirmprotecttext' => 'Do you really want to protect this page?',
'confirmprotect' => 'Confirm protection',
'protectcomment' => 'Reason for protecting',
'unprotectsub' =>"(Unprotecting \"$1\")",
'confirmunprotecttext' => 'Do you really want to unprotect this page?',
'confirmunprotect' => 'Confirm unprotection',
'unprotectcomment' => 'Reason for unprotecting',
'protectreason' => '(give a reason)',

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
'undeletedarticle' => "restored \"$1\"",
'undeletedrevisions' => "$1 revisions restored",
'undeletedtext'   => "[[$1]] has been successfully restored.
See [[{{ns:4}}:Deletion_log]] for a record of recent deletions and restorations.",

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
accordance with [[{{ns:4}}:Policy|policy]].
Fill in a specific reason below (for example, citing particular
pages that were vandalized).",
'ipaddress'		=> 'IP Address/username',
'ipbexpiry'		=> 'Expiry',
'ipbreason'		=> 'Reason',
'ipbsubmit'		=> 'Block this user',
'badipaddress'	=> 'Invalid IP address',
'noblockreason' => 'You must supply a reason for the block.',
'blockipsuccesssub' => 'Block succeeded',
'blockipsuccesstext' => "\"$1\" has been blocked.
<br />See [[Special:Ipblocklist|IP block list]] to review blocks.",
'unblockip'		=> 'Unblock user',
'unblockiptext'	=> 'Use the form below to restore write access
to a previously blocked IP address or username.',
'ipusubmit'		=> 'Unblock this address',
'ipusuccess'	=> "\"$1\" unblocked",
'ipblocklist'	=> 'List of blocked IP addresses and usernames',
'blocklistline'	=> "$1, $2 blocked $3 (expires $4)",
'blocklink'		=> 'block',
'unblocklink'	=> 'unblock',
'contribslink'	=> 'contribs',
'autoblocker'	=> "Autoblocked because you share an IP address with \"$1\". Reason \"$2\".",
'blocklogpage'	=> 'Block_log',
'blocklogentry'	=> 'blocked "$1" with an expiry time of $2',
'blocklogtext'	=> 'This is a log of user blocking and unblocking actions. Automatically
blocked IP addresses are not be listed. See the [[Special:Ipblocklist|IP block list]] for
the list of currently operational bans and blocks.',
'unblocklogentry'	=> 'unblocked "$1"',
'range_block_disabled'	=> 'The sysop ability to create range blocks is disabled.',
'ipb_expiry_invalid'	=> 'Expiry time invalid.',
'ip_range_invalid'	=> "Invalid IP range.\n",
'proxyblocker'	=> 'Proxy blocker',
'proxyblockreason'	=> 'Your IP address has been blocked because it is an open proxy. Please contact your Internet service provider or tech support and inform them of this serious security problem.',
'proxyblocksuccess'	=> "Done.\n",

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

# SQL query
#
'asksql'		=> 'SQL query',
'asksqltext'	=> "Use the form below to make a direct query of the
database.
Use single quotes ('like this') to delimit string literals.
This can often add considerable load to the server, so please use
this function sparingly.",
'sqlislogged'	=> 'Please note that all queries are logged.',
'sqlquery'		=> 'Enter query',
'querybtn'		=> 'Submit query',
'selectonly'	=> 'Only read-only queries are allowed.',
'querysuccessful' => 'Query successful',

# Make sysop
'makesysoptitle'	=> 'Make a user into a sysop',
'makesysoptext'		=> 'This form is used by bureaucrats to turn ordinary users into administrators.
Type the name of the user in the box and press the button to make the user an administrator',
'makesysopname'		=> 'Name of the user:',
'makesysopsubmit'	=> 'Make this user into a sysop',
'makesysopok'		=> "<b>User \"$1\" is now a sysop</b>",
'makesysopfail'		=> "<b>User \"$1\" could not be made into a sysop. (Did you enter the name correctly?)</b>",
'setbureaucratflag' => 'Set bureaucrat flag',
'bureaucratlog'		=> 'Bureaucrat_log',
'bureaucratlogentry'	=> "Rights for user \"$1\" set \"$2\"",
'rights'			=> 'Rights:',
'set_user_rights'	=> 'Set user rights',
'user_rights_set'	=> "<b>User rights for \"$1\" updated</b>",
'set_rights_fail'	=> "<b>User rights for \"$1\" could not be set. (Did you enter the name correctly?)</b>",
'makesysop'         => 'Make a user into a sysop',

# Validation
'val_clear_old' => 'Clear my other validation data for $1',
'val_merge_old' => 'Use my previous assessment where selected \'No opinion\'',
'val_form_note' => '<b>Hint:</b> Merging your data means that for the article
revision you select, all options where you have specified <i>no opinion</i>
will be set to the value and comment of the most recent revision for which you
have expressed an opinion. For example, if you want to change a single option
for a newer revision, but also keep your other settings for this article in
this revision, just select which option you intend to <i>change</i>, and
merging will fill in the other options with your previous settings.',
'val_noop' => 'No opinion',
'val_percent' => '<b>$1%</b><br>($2 of $3 points<br>by $4 users)',
'val_percent_single' => '<b>$1%</b><br>($2 of $3 points<br>by one user)',
'val_total' => 'Total',
'val_version' => 'Version',
'val_tab' => 'Validate',
'val_this_is_current_version' => 'this is the latest version',
'val_version_of' => "Version of $1" ,
'val_table_header' => "<tr><th>Class</th>$1<th colspan=4>Opinion</th>$1<th>Comment</th></tr>\n",
'val_stat_link_text' => 'Validation statistics for this article',
'val_view_version' => 'View this version',
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
[[Special:Maintenance|check]] for double or broken redirects.
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
'movenologintext' => "You must be a registered user and <a href=\"{{localurl:Special:Userlogin}}\">logged in</a>
to move a page.",
'newtitle'		=> 'To new title',
'movepagebtn'	=> 'Move page',
'pagemovedsub'	=> 'Move succeeded',
'pagemovedtext' => "Page \"[[$1]]\" moved to \"[[$2]]\".",
'articleexists' => 'A page of that name already exists, or the
name you have chosen is not valid.
Please choose another name.',
'talkexists'	=> 'The page itself was moved successfully, but the
talk page could not be moved because one already exists at the new
title. Please merge them manually.',
'movedto'		=> 'moved to',
'movetalk'		=> 'Move "talk" page as well, if applicable.',
'talkpagemoved' => 'The corresponding talk page was also moved.',
'talkpagenotmoved' => 'The corresponding talk page was <strong>not</strong> moved.',
'1movedto2'		=> "$1 moved to $2",
'1movedto2_redir' => '$1 moved to $2 over redirect',

# Export

'export'		=> 'Export pages',
'exporttext'	=> 'You can export the text and editing history of a particular page or
set of pages wrapped in some XML. In the future, this may then be imported into another
wiki running MediaWiki software, although there is no support for this feature in the
current version.

To export article pages, enter the titles in the text box below, one title per line, and
select whether you want the current version as well as all old versions, with the page
history lines, or just the current version with the info about the last edit.

In the latter case you can also use a link, e.g. [[{{ns:Special}}:Export/Train]] for the
article [[Train]].
',
'exportcuronly'	=> 'Include only the current revision, not the full history',

# Namespace 8 related

'allmessages'	=> 'All system messages',
'allmessagestext'	=> 'This is a list of all system messages available in the MediaWiki: namespace.',
'allmessagesnotsupported' => 'Your current interface language is not supported by Special:AllMessages at this site',

# Thumbnails

'thumbnail-more'	=> 'Enlarge',
'missingimage'		=> "<b>Missing image</b><br /><i>$1</i>\n",

# Special:Import
'import'	=> 'Import pages',
'importtext'	=> 'Please export the file from the source wiki using the Special:Export utility, save it to your disk and upload it here.',
'importfailed'	=> "Import failed: $1",
'importnotext'	=> 'Empty or no text',
'importsuccess'	=> 'Import succeeded!',
'importhistoryconflict' => 'Conflicting history revision exists (may have imported this page before)',

# Keyboard access keys for power users
'accesskey-search' => 'f',
'accesskey-minoredit' => 'i',
'accesskey-save' => 's',
'accesskey-preview' => 'p',
'accesskey-compareselectedversions' => 'v',

# tooltip help for some actions, most are in Monobook.js
'tooltip-search' => 'Search this wiki [alt-f]',
'tooltip-minoredit' => 'Mark this as a minor edit [alt-i]',
'tooltip-save' => 'Save your changes [alt-s]',
'tooltip-preview' => 'Preview your changes, please use this before saving! [alt-p]',
'tooltip-compareselectedversions' => 'See the differences between the two selected versions of this page. [alt-v]',

# stylesheets

'Monobook.css' => '/* edit this file to customize the monobook skin for the entire site */',
#'Monobook.js' => '/* edit this file to change js things in the monobook skin */',

# Metadata
'nodublincore' => 'Dublin Core RDF metadata disabled for this server.',
'nocreativecommons' => 'Creative Commons RDF metadata disabled for this server.',
'notacceptable' => 'The wiki server can\'t provide data in a format your client can read.',

# Attribution

'anonymous' => "Anonymous user(s) of $wgSitename",
'siteuser' => "$wgSitename user $1",
'lastmodifiedby' => "This page was last modified $1 by $2.",
'and' => 'and',
'othercontribs' => "Based on work by $1.",
'others' => 'others',
'siteusers' => "$wgSitename user(s) $1",
'creditspage' => 'Page credits',
'nocredits' => 'There is no credits info available for this page.',

# Spam protection

'spamprotectiontitle' => 'Spam protection filter',
'spamprotectiontext' => 'The page you wanted to save was blocked by the spam filter. This is probably caused by a link to an external site.',
'spamprotectionmatch' => 'The following text is what triggered our spam filter: $1',
'subcategorycount' => "There are $1 subcategories to this category.",
'subcategorycount1' => "There is $1 subcategorie to this category.",
'categoryarticlecount' => "There are $1 articles in this category.",
'categoryarticlecount1' => "There is $1 article in this category.",
'usenewcategorypage' => "1\n\nSet first character to \"0\" to disable the new category page layout.",

# Info page
"infosubtitle" => "Information for page",
"numedits" => "Number of edits (article): $1",
"numtalkedits" => "Number of edits (discussion page): $1",
"numwatchers" => "Number of watchers: $1",
"numauthors" => "Number of distinct authors (article): $1",
"numtalkauthors" => "Number of distinct authors (discussion page): $1",

# Math options
'mw_math_png' => 'Always render PNG',
'mw_math_simple' => 'HTML if very simple or else PNG',
'mw_math_html' => 'HTML if possible or else PNG',
'mw_math_source' => 'Leave it as TeX (for text browsers)',
'mw_math_modern' => 'Recommended for modern browsers',
'mw_math_mathml' => 'MathML if possible (experimental)',

# Patrolling
'markaspatrolleddiff'   => "Mark as patrolled",
'markaspatrolledlink'   => "<div class='patrollink'>[$1]</div>",
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
ta[\'ca-nomove\'] = new Array(\'\',\'You don\\\'t have the permissions to move this page\');
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
ta[\'t-recentchangeslinked\'] = new Array(\'k\',\'Recent changes in pages linking to this page\');
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
'previousdiff' => '&larr; Go to previous diff',
'nextdiff' => 'Go to next diff &rarr;',

'imagemaxsize' => 'Limit images on image description pages to: ',
'showbigimage' => 'Download high resolution version ($1x$2, $3 KB)',

'newimages' => 'New images gallery',

'sitesettings'                  => 'Site Settings',
'sitesettings-features'         => 'Features',
'sitesettings-permissions'      => 'Permissions',
'sitesettings-memcached'        => 'Memcache Daemon',
'sitesettings-debugging'        => 'Debugging',
'sitesettings-caching'          => 'Page caching',
'sitesettings-wgShowIPinHeader' => 'Show IP in header (for non-logged in users)',
'sitesettings-wgUseDatabaseMessages' => 'Use database messages for user interface labels',
'sitesettings-wgUseCategoryMagic' => 'Enable categories',
'sitesettings-wgUseCategoryBrowser' => 'Enable experimental dmoz-like category browsing. Outputs things like:  Encyclopedia > Music > Style of Music > Jazz',
'sitesettings-wgHitcounterUpdateFreq' => 'Hit counter update frequency',
'sitesettings-wgAllowExternalImages' => 'Allow to include external images into articles',
'sitesettings-permissions-readonly' => 'Maintenance mode: Disable write access',
'sitesettings-permissions-whitelist' => 'Whitelist mode',
'sitesettings-permissions-banning' => 'User banning',
'sitesettings-permissions-miser' => 'Performance settings',
'sitesettings-wgReadOnly' => 'Readonly mode',
'sitesettings-wgReadOnlyFile' => 'Readonly message file',
'sitesettings-wgWhitelistEdit' => 'Users must be logged in to edit',
'sitesettings-wgWhitelistRead' => 'Anonymous users may only read these pages:',
'sitesettings-wgWhitelistAccount-user' => 'Users may create accounts themself',
'sitesettings-wgWhitelistAccount-sysop' => 'Sysops may create accounts for users',
'sitesettings-wgWhitelistAccount-developer' => 'Developers may create accounts for users',
'sitesettings-wgSysopUserBans' => 'Sysops may block logged-in users',
'sitesettings-wgSysopRangeBans' => 'Sysops may block IP-ranges',
'sitesettings-wgDefaultBlockExpiry' => 'By default, blocks expire after:',
'sitesettings-wgMiserMode' => 'Enable miser mode, which disables most "expensive" features',
'sitesettings-wgDisableQueryPages' => 'When in miser mode, disable all query pages, not only "expensive" ones',
'sitesettings-wgUseWatchlistCache' => 'Generate a watchlist once every hour or so',
'sitesettings-wgWLCacheTimeout' => 'The hour or so mentioned above (in seconds):',
'sitesettings-cookies' => 'Cookies',
'sitesettings-performance' => 'Performance',
'sitesettings-images' => 'Images',


);

#--------------------------------------------------------------------------
# Internationalisation code
#--------------------------------------------------------------------------

class Language {
	function Language(){
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
	}

	function getDefaultUserOptions () {
		global $wgDefaultUserOptionsEn ;
		return $wgDefaultUserOptionsEn ;
	}

	function getBookstoreList () {
		global $wgBookstoreListEn ;
		return $wgBookstoreListEn ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesEn;
		return $wgNamespaceNamesEn;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesEn;
		return $wgNamespaceNamesEn[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesEn;

		foreach ( $wgNamespaceNamesEn as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
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
		$togs =& $this->getUserToggles();
		return wfMsg("tog-".$tog);
	}

	function getLanguageNames() {
		global $wgLanguageNamesEn;
		return $wgLanguageNamesEn;
	}

	function getLanguageName( $code ) {
		global $wgLanguageNamesEn;
		if ( ! array_key_exists( $code, $wgLanguageNamesEn ) ) {
			return "";
		}
		return $wgLanguageNamesEn[$code];
	}

	function getMonthName( $key )
	{
		global $wgMonthNamesEn;
		return wfMsg($wgMonthNamesEn[$key-1]);
	}

	/* by default we just return base form */
	function getMonthNameGen( $key )
	{
		return $this->getMonthName( $key );
	}

	function getMonthAbbreviation( $key )
	{
		global $wgMonthAbbreviationsEn;
		return wfMsg(@$wgMonthAbbreviationsEn[$key-1]);
	}

	function getWeekdayName( $key )
	{
		global $wgWeekdayNamesEn;
		return wfMsg($wgWeekdayNamesEn[$key-1]);
	}

	function userAdjust( $ts )
	{
		global $wgUser, $wgLocalTZoffset;

		$tz = $wgUser->getOption( 'timecorrection' );
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

	function date( $ts, $adj = false, $format = MW_DATE_USER_FORMAT )
	{
		global $wgAmericanDates, $wgUser, $wgUseDynamicDates;

		$ts=wfTimestamp(TS_MW,$ts);
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }
		if ( $wgUseDynamicDates ) {
			if ( $format == MW_DATE_USER_FORMAT ) {
				$datePreference = $wgUser->getOption( 'date' );
			} else {
				$options = $this->getDefaultUserOptions();
				$datePreference = $options['date'];
			}
			if ( $datePreference == 0 ) {
				$datePreference = $wgAmericanDates ? 1 : 2;
			}
		} else {
			$datePreference = $wgAmericanDates ? 1 : 2;
		}

		$month = $this->getMonthAbbreviation( substr( $ts, 4, 2 ) );
		$day = $this->formatNum( 0 + substr( $ts, 6, 2 ) );
		$year = $this->formatNum( substr( $ts, 0, 4 ) );

		switch( $datePreference ) {
			case 1: return "$month $day, $year";
			case 2: return "$day $month $year";
			default: return "$year $month $day";
		}
	}

	function time( $ts, $adj = false, $seconds = false )
	{
		$ts=wfTimestamp(TS_MW,$ts);

		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$t = substr( $ts, 8, 2 ) . ':' . substr( $ts, 10, 2 );
		if ( $seconds ) {
			$t .= ':' . substr( $ts, 12, 2 );
		}
		return $this->formatNum( $t );
	}

	function timeanddate( $ts, $adj = false, $format = MW_DATE_USER_FORMAT )
	{
		$ts=wfTimestamp(TS_MW,$ts);

		return $this->time( $ts, $adj ) . ', ' . $this->date( $ts, $adj, $format );
	}

	function rfc1123( $ts )
	{
		return date( 'D, d M Y H:i:s T', $ts );
	}

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesEn;
		return $wgValidSpecialPagesEn;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesEn;
		return $wgSysopSpecialPagesEn;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesEn;
		return $wgDeveloperSpecialPagesEn;
	}

	function getMessage( $key )
	{
		global $wgAllMessagesEn;
		return @$wgAllMessagesEn[$key];
	}

	function getAllMessages()
	{
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
		return strtolower( $s{0}  ). substr( $s, 1 );
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

	function stripForSearch( $in ) {
		# Some languages have special punctuation to strip out
		# or characters which need to be converted for MySQL's
		# indexing to grok it correctly. Make such changes here.
		return strtolower( $in );
	}

	function firstChar( $s ) {
		# Get the first character of a string. In ASCII, return
		# first byte of the string. UTF8 and others have to
		# overload this.
		return $s[0];
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

	# For right-to-left language support
	function isRTL() { return false; }

	# To allow "foo[[bar]]" to extend the link over the whole word "foobar"
	function linkPrefixExtension() { return false; }


	function &getMagicWords()
	{
		global $wgMagicWordsEn;
		return $wgMagicWordsEn;
	}

	# Fill a MagicWord object with data from here
	function getMagic( &$mw )
	{
		$raw =& $this->getMagicWords();
		if( !isset( $raw[$mw->mId] ) ) {
			# Fall back to English if local list is incomplete
			$raw =& Language::getMagicWords();
		}
		$rawEntry = $raw[$mw->mId];
		$mw->mCaseSensitive = $rawEntry[0];
		$mw->mSynonyms = array_slice( $rawEntry, 1 );
	}

	# Italic is unsuitable for some languages
	function emphasize( $text )
	{
		return '<em>'.$text.'</em>';
	}


	# Normally we use the plain ASCII digits. Some languages such as Arabic will
	# want to output numbers using script-appropriate characters: override this
	# function with a translator. See LanguageAr.php for an example.
	function formatNum( $number ) {
		return $number;
	}

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

	# Grammatical transformations, needed for inflected languages
	# Invoked by putting {{grammar:case|word}} in a message
	function convertGrammar( $word, $case ) {
		return $word;
	}

	
	# convert text to different variants of a language. the automatic
	# conversion is done in autoConvert(). here we parse the text 
	# marked with -{}-, which specifies special conversions of the 
	# text that can not be accomplished in autoConvert()
	#
	# syntax of the markup:
	# -{code1:text1;code2:text2;...}-  or
	# -{text}- in which case no conversion should take place for text
	function convert( $text ) {

		if(sizeof($this->getVariants())<2) 
			return $text;

		// no conversion if redirecting
		if(substr($text,0,9) == "#REDIRECT") {
			return $text;
		}


		$plang = $this->getPreferredVariant();

		$tarray = explode("-{", $text);
		$tfirst = array_shift($tarray);
		$text = $this->autoConvert($tfirst);
		
		foreach($tarray as $txt) {
			$marked = explode("}-", $txt);
			
			$choice = explode(";", $marked{0});
			if(!array_key_exists(1, $choice)) {
				/* a single choice */
				$text .= $choice{0};
			}
			else {
				foreach($choice as $c) {
					$v = explode(":", $c);
					if(!array_key_exists(1, $v)) {
						//syntax error in the markup, give up
						$text .= $marked{0};
						break;			
					}
					$code = trim($v{0});
					$content = trim($v{1});
					if($code == $plang) {
						$text .= $content;
						break;
					}
				}
			}
			if(array_key_exists(1, $marked))
				$text .= $this->autoConvert($marked{1});
		}
		
		return $text;
	}

	/* this does the real conversion to the preferred variant.
	   see LanguageZh.php for example
	*/
	function autoConvert($text) {
		return $text;
	}
	
	# returns a list of language variants for conversion.
	# right now mainly used in the Chinese conversion
	function getVariants() {
		$lang = strtolower(substr(get_class($this), 8));
		return array($lang);
	}
	
	
	function getPreferredVariant() {
		global $wgUser;
		
		// if user logged in, get in from user's preference
		if($wgUser->getID()!=0)
			return $wgUser->getOption('variant');
		
		// if we have multiple variants for this langauge, 
		// pick the first one as default
		$v=$this->getVariants() ;
		if(!empty($v))
			return $v{0};
		
		// otherwise there should really be just one variant, 
		// get it from the class name
		$lang = strtolower(substr(get_class($this), 8));
		return $lang;
	}
}

# This should fail gracefully if there's not a localization available
@include_once( 'Language' . str_replace( '-', '_', ucfirst( $wgLanguageCode ) ) . '.php' );

}
?>

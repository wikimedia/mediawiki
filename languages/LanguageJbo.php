<?php

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => "-"

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#
# This translation was made by Pierre Abbat
#

$wgMetaNamespage="uikipedias";

/* private */ $wgNamespaceNamesJbo = array(
	NS_MEDIA            => 'kinjavysnavei',
	NS_SPECIAL          => 'steci',
	NS_MAIN	            => '',
	NS_TALK	            => 'tavla',
	NS_USER             => 'prenu',
	NS_USER_TALK        => 'prenu_tavla',
	NS_WIKIPEDIA        => $wgMetaNamespace,
	NS_WIKIPEDIA_TALK   => $wgMetaNamespace . '_tavla',
	NS_IMAGE            => 'pixra',
	NS_IMAGE_TALK       => 'pixra_tavla',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_tavla',
	NS_TEMPLATE         => 'tarmi',
	NS_TEMPLATE_TALK    => 'tarmi_tavla',
	NS_HELP             => 'sidju',
	NS_HELP_TALK        => 'sidju_tavla',
	NS_CATEGORY	    => 'klesi',
	NS_CATEGORY_TALK    => 'klesi_tavla'
);

/* private */ $wgDefaultUserOptionsJbo = array(
 "quickbar" => 1, "underline" => 1, "hover" => 1,
 "cols" => 80, "rows" => 25, "searchlimit" => 20,
 "contextlines" => 5, "contextchars" => 50,
 "skin" => 0, "math" => 1, "rcdays" => 7, "rclimit" => 50,
 "highlightbroken" => 1, "stubthreshold" => 0,
 "previewontop" => 1, "editsection"=>1,"editsectiononrightclick"=>0, "showtoc"=>1,
 "date" => 0
);

/* private */ $wgQuickbarSettingsJbo = array(
 "claxu", "zunle stodi", "pritu stodi", "zunle fulta"
);

/* private */ $wgSkinNamesJbo = array(
	'standard' => 'manri',
	'nostalgia' => 'cirxrudji',
	'cologneblue' => 'darseltrustu blanu',
	'davinci' => 'davintcis',
	'mono' => 'monos',
	'monobook' => 'monobuk',
	'myskin' => 'mibykap'
);

# Validation types
$wgValidationTypesJbo = array (
		"0" => "suvyci'a|mabla|zabna|5",
		"1" => "nilselfla|tolselfla|selfla|5",
		"2" => "nilmu'o|tsikra|makcu|5",
		"3" => "fatci|cunso selru'a|rokci sligu|5",
		"4" => "xamgu le 1.0moi papri|nago'i|go'i|2",
		"5" => "xamgu le 1.0moi gusyvei|nago'i|go'i|2"
		) ;


/* private */ $wgMathNamesJbo = array(
	MW_MATH_PNG => "roda la me PNG",
	MW_MATH_SIMPLE => "le sampu fa'u zo'e la me HTML fa'u la me PNG",
	MW_MATH_HTML => "le selka'u fa'u zo'e la me HTML fa'u la me PNG",
	MW_MATH_SOURCE => "roda la me TeX fi'o ja'otci lo xracau",
	MW_MATH_MODERN => "cabna ja'otci selstidi",
	MW_MATH_MATHML => "roda la me MathML"
);

/* private */ $wgDateFormatsJbo = array(
 "na zmanei",
 "kanbyma'i 15, 2001",
 "15 kanbyma'i 2001",
 "2001 kanbyma'i 15",
 "2001-01-15"
);

/* private */ $wgUserTogglesJbo = array(
 "hover"  => "lo fulta tanxe cu jibni le .uikis. zei velyla'a",
 "underline" => "lijni'a le velyla'a",
 "highlightbroken" => "le spofu velyla'a cu tamsmi <a href=\"\" class=\"new\">dei</a> peseba'i dei<a href=\"\" class=\"internal\">?</a>",
 "justify" => "jufygri mlasirji",
 "hideminor" => "mipri le tolba'i nunga'i ne'i le puzi nunga'i",
 "usenewrc" => "se xagmaugau nu puzi galfi to me'iro ja'otci toi",
 "numberheadings" => "zmikactcita lei sedytci",
        "showtoolbar" => "jarco tu'a le tcigarna",
 "editondblclick" => "galfi lo papri ta'i lonu remoi catke to DJAvaskript toi",
 "editsection"=>"galfi pa paprypau",
 "editsectiononrightclick"=>"galfi lo paprypau ta'i lonu <br> pritu catke le cmetcita be le paprypau to DJAvaskript toi",
 "showtoc"=>"jarco le selvau cartu<br>be ro pagbu su'ocimei ci'arvelski",
 "rememberpassword" => "morji le sivyvla entre sessões",
 "editwidth" => "Caixa de edição com largura completa",
 "watchdefault" => "Observa artigos novos e modificados",
 "minordefault" => "Marca todas as edições como secundárias, por padrão",
 "previewontop" => "Mostrar Previsão antes da caixa de edição ao invés de ser após",
 "nocache" => "Desabilitar caching de página"
);

/* private */ $wgBookstoreListJbo = array(
 "AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
 "PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
 "Barnes & Noble" => "http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
 "Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1"
);

/* private */ $wgWeekdayNamesJbo = array(
 "soldei", "lurdei", "fagdei", "jaurdei", "mudydei",
 "jimdei", "tedydei"

);

/* private */ $wgMonthNamesJbo = array(
 "kanbyma'i", "jaurbeima'i", "fipma'i", "lanma'i", "bakma'i", "matsi'uma'i",
 "mlajukma'i", "cinfyma'i", "xlima'i", "laxma'i", "rebjukma'i",
 "celma'i"

);

/* private */ $wgMonthAbbreviationsJbo = array(
 "knb", "jac", "fip", "lan", "bak", "mat", "mla", "cnf",
 "xli", "lax", "reb", "cel"
);

/* private */ $wgMagicWordsJbo = array(
#   ID                                 CASE  SYNONYMS
    MAG_REDIRECT             => array( 0,    '#redirect','#refybei',"#ke'urbei"              ),
    MAG_NOTOC                => array( 0,    '__NOTOC__'              ),
    MAG_FORCETOC             => array( 0,    '__FORCETOC__'           ),
    MAG_TOC                  => array( 0,    '__TOC__'                ),
    MAG_NOEDITSECTION        => array( 0,    '__NOEDITSECTION__'      ),
    MAG_START                => array( 0,    '__START__'              ),
    MAG_CURRENTMONTH         => array( 1,    'CURRENTMONTH',"CABMA'I"           ),
    MAG_CURRENTMONTHNAME     => array( 1,    'CURRENTMONTHNAME',"CABMA'ICME"       ),
    MAG_CURRENTDAY           => array( 1,    'CURRENTDAY',"CABDEI"             ),
    MAG_CURRENTDAYNAME       => array( 1,    'CURRENTDAYNAME',"CABDEICME"         ),
    MAG_CURRENTYEAR          => array( 1,    'CURRENTYEAR',"CABNA'A"            ),
    MAG_CURRENTTIME          => array( 1,    'CURRENTTIME',"CABYTCIKA"            ),
    MAG_NUMBEROFARTICLES     => array( 1,    'NUMBEROFARTICLES'       ),
    MAG_CURRENTMONTHNAMEGEN  => array( 1,    'CURRENTMONTHNAMEGEN'    ),
		MAG_PAGENAME             => array( 1,    'PAGENAME'               ),
		MAG_NAMESPACE            => array( 1,    'NAMESPACE'              ),
	MAG_MSG                  => array( 0,    'MSG:'                   ),
	MAG_SUBST                => array( 0,    'SUBST:'                 ),
    MAG_MSGNW                => array( 0,    'MSGNW:'                 ),
	MAG_END                  => array( 0,    '__END__'                ),
    MAG_IMG_THUMBNAIL        => array( 1,    'thumbnail', 'thumb'     ),
    MAG_IMG_RIGHT            => array( 1,    'right',"pritu"                  ),
    MAG_IMG_LEFT             => array( 1,    'left',"zunle"                   ),
    MAG_IMG_NONE             => array( 1,    'none'                   ),
    MAG_IMG_WIDTH            => array( 1,    '$1px'                   ),
    MAG_IMG_CENTER           => array( 1,    'center', 'centre',"midju"       ),
    MAG_IMG_FRAMED	     => array( 1,    'framed', 'enframed', 'frame' ),
    MAG_INT                  => array( 0,    'INT:'                   ),
    MAG_SITENAME             => array( 1,    'SITENAME'               ),
    MAG_NS                   => array( 0,    'NS:'                    ),
	MAG_LOCALURL             => array( 0,    'LOCALURL:'              ),
	MAG_LOCALURLE            => array( 0,    'LOCALURLE:'             ),
	MAG_SERVER               => array( 0,    'SERVER'                 )
);

# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesJbo = array(
 "Userlogin"  => "",
 "Userlogout" => "",
 "Preferences" => "se nelcu'a mi",
 "Watchlist"  => "liste le'i selzga be mi ci'arvelski",
 "Recentchanges" => "pu zi se galfi papri",
 "Upload"  => "nirpu'i lo pixra ja drata datnyvei",
 "Imagelist"  => "liste le'i pixra",
 "Listusers"  => "selvei prenu",
 "Statistics" => "gumdatni tu'a le stuzi",
 "Randompage" => "cunso ci'arvelski",

 "Lonelypages" => "rorcau ci'arvelski",
 "Unusedimages" => "rorcau pixra",
 "Popularpages" => "sorselnei ci'arvelski",
 "Wantedpages" => "seldjirai ci'arvelski",
 "Shortpages" => "tordu ci'arvelski",
 "Longpages"  => "clani ci'arvelski",
 "Newpages"  => "puzi selzba ci'arvelski",
 "Ancientpages" => "tolci'orai ci'arvelski",
 "Intl"  => "datybau velyla'a",
 "Allpages"  => "ro papri sepoi tu'a le cmene",

 "Ipblocklist" => "selzunti ke me IP judri",
 "Maintenance" => "cikrydji papri",
 "Specialpages"  => "steci papri",
 "Contributions" => "pauseldu'a",
 "Emailuser"  => "mrilu fi le prenu",
 "Whatlinkshere" => "lasna makau ti",
 "Recentchangeslinked" => "pu zi se galfi",
 "Movepage"  => "muvgau le papri",
 "Booksources" => "bartu ke cukta krasi",
 #"Categories" => "Categorias de Páginas",
 "Export" => "barfu'i tetai la me XML",
);

/* private */ $wgSysopSpecialPagesJbo = array(
 "Blockip"  => "zunti lo me IP judri",
 "Asksql"  => "preti fo le datnysro",
 "Undelete"  => "catlu gi'e tolvi'u vau lo selvi'u papri"
);

/* private */ $wgDeveloperSpecialPagesJbo = array(
 "Lockdb"  => "ciska ga'orgau fi le datnysro",
 "Unlockdb"  => "ciska kargau fi le datnysro",
 "Debug"   => "facki le cfila lo srenuntarti"
);

/* private */ $wgAllMessagesJbo = array(

# Bits of text used by many pages:
#
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
'tog-nocache' => 'Disable page caching',

"categories" => "papri klesi",

"category" => "klesi",
"category_header" => "ci'arvelski cmima be \"$1\" poi klesi",
"subcategories" => "klepau",

"linktrail"  => "/^([A-Za-z',]+)(.*)\$/sD",
"mainpage"  => "ralju papri",
"mainpagetext" => "le me la .uikis. mutmi'i cu snada se nerpu'i",
"mainpagedocfooter" => "Please see [http://meta.wikipedia.org/wiki/MediaWiki_i18n documentation on customizing the interface]
and the [http://meta.wikipedia.org/wiki/MediaWiki_User%27s_Guide User's Guide] for usage and configuration help.",
'portal'		=> 'Community portal',
'portal-url'		=> '{{ns:4}}:Community Portal',
"about"   => "sera'a",
"aboutwikipedia" => "sera'a la .uikipedias.",
"aboutpage"  => "uikipedias:sera'a",
'article' => 'Content page',
"help"   => "sidju",
"helppage"  => "uikipedias:sidju",
"wikititlesuffix" => "Wikipedia",
'sitesupport'   => 'Donations', # Set a URL in $wgSiteSupportPage in LocalSettings.php
"bugreports" => "skicu lo nunsretarti",
"bugreportspage" => "uikipedias:skicu_lo_nunsretarti",
"faq"   => "cafne preti",
"faqpage"  => "uikipedias:cafne_preti",
"edithelp"  => "galfi djunoi",
"edithelppage" => "uikipedias:galfi_lo_papri_ta'i_makau",
"cancel"  => "todli'a",
"qbfind"  => "sisku",
"qbbrowse"  => "Browse",
"qbedit"  => "galfi",
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
'disclaimers' => 'Disclaimers',
"disclaimerpage"		=> "{{ns:4}}:General_disclaimer",
"errorpagetitle" => "Error",
"returnto"		=> "Return to $1.",
"fromwikipedia"	=> "From {{SITENAME}}",
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
'redirectedfrom' => "(Redirected from $1)",
'lastmodified'	=> "This page was last modified $1.",
'viewcount'		=> "This page has been accessed $1 times.",
'copyright'	=> "Content is available under $1.",
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
'nbytes'		=> "$1 bytes",
'go'			=> 'Go',
'ok'			=> 'OK',
'sitetitle'		=> "{{SITENAME}}",
'pagetitle'		=> "$1 - {{SITENAME}}",
'sitesubtitle'	=> 'The Free Encyclopedia', # FIXME
'retrievedfrom' => "Retrieved from \"$1\"",
"newmessages" => "da $1 fo do",
"newmessageslink" => "cnino notci",
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
This could be because of an illegal search query (see $5),
or it may indicate a bug in the software.
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

'welcomecreation' => "<h2>Welcome, $1!</h2><p>Your account has been created.
Don't forget to change your {{SITENAME}} preferences.",

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
'userlogin'		=> 'Log in',
'logout'		=> 'Log out',
'userlogout'	=> 'Log out',
'notloggedin'	=> 'Not logged in',
'createaccount'	=> 'Create new account',
'createaccountmail'	=> 'by email',
'badretype'		=> 'The passwords you entered do not match.',
'userexists'	=> 'The user name you entered is already in use. Please choose a different name.',
'youremail'		=> 'Your email*',
'yourrealname'		=> 'Your real name*',
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
'whitelistedittext' => 'You have to [[steci:Userlogin|login]] to edit pages.',
'whitelistreadtitle' => 'Login required to read',
'whitelistreadtext' => 'You have to [[steci:Userlogin|login]] to read pages.',
'whitelistacctitle' => 'You are not allowed to create an account',
'whitelistacctext' => 'To be allowed to create accounts in this Wiki you have to [[steci:Userlogin|log]] in and have the appropriate permissions.',
'loginreqtitle'	=> 'Login Required',
'loginreqtext'	=> 'You must [[steci:Userlogin|login]] to view other pages.',
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
'editingold'	=> '<strong>WARNING: You are editing an out-of-date
revision of this page.
If you save it, any changes made since this revision will be lost.</strong>\n',
'yourdiff'		=> 'Differences',
# FIXME: This is inappropriate for third-party use!
'copyrightwarning' => "Please note that all contributions to {{SITENAME}} are
considered to be released under the GNU Free Documentation License
(see $1 for details).
If you don't want your writing to be edited mercilessly and redistributed
at will, then don't submit it here.<br />
You are also promising us that you wrote this yourself, or copied it from a
public domain or similar free resource.
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

# History pages
#
'revhistory'	=> 'Revision history',
'nohistory'		=> 'There is no edit history for this page.',
'revnotfound'	=> 'Revision not found',
'revnotfoundtext' => "The old revision of the page you asked for could not be found.
Please check the URL you used to access this page.\n",
'loadhist'		=> 'Loading page history',
'currentrev'	=> 'Current revision',
'revisionasof'	=> "Revision as of $1",
'cur'			=> 'cab',
'next'			=> 'bav',
'last'			=> 'pur',
'orig'			=> 'kra',
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
'searchhelppage' => "{{ns:4}}:Searching",
'searchingwikipedia' => "Searching {{SITENAME}}",
'searchresulttext' => "For more information about searching {{SITENAME}}, see $1.",
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
'showingresults' => "Showing below <b>$1</b> results starting with #<b>$2</b>.",
'showingresultsnum' => "Showing below <b>$3</b> results starting with #<b>$2</b>.",
'nonefound'		=> "<strong>Note</strong>: unsuccessful searches are
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
'prefsnologintext'	=> "You must be <a href=\"{{localurl:steci:Userlogin}}\">logged in</a>
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

# Recent changes
#
'changes' => 'changes',
'recentchanges' => 'Recent changes',
'recentchangestext' => 'Track the most recent changes to the wiki on this page.',
'rcloaderr'		=> 'Loading recent changes',
'rcnote'		=> "Below are the last <strong>$1</strong> changes in last <strong>$2</strong> days.",
'rcnotefrom'	=> "Below are the changes since <b>$2</b> (up to <b>$1</b> shown).",
'rclistfrom'	=> "Show new changes starting from $1",
# "rclinks"		=> "Show last $1 changes in last $2 hours / last $3 days",
# "rclinks"		=> "Show last $1 changes in last $2 days.",
'showhideminor' => "$1 minor edits | $2 bots | $3 logged in users ",
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
'minoreditletter' => 'M',
'newpageletter' => 'N',

# Upload
#
'upload'		=> 'Upload file',
'uploadbtn'		=> 'Upload file',
'uploadlink'	=> 'Upload images',
'reupload'		=> 'Re-upload',
'reuploaddesc'	=> 'Return to the upload form.',
'uploadnologin' => 'Not logged in',
'uploadnologintext'	=> "You must be <a href=\"{{localurl:steci:Userlogin}}\">logged in</a>
to upload files.",
'uploadfile'	=> 'Upload images, sounds, documents etc.',
'uploaderror'	=> 'Upload error',
'uploadtext'	=> "<strong>STOP!</strong> Before you upload here,
make sure to read and follow the <a href=\"{{localurle:steci:Image_use_policy}}\">image use policy</a>.
<p>If a file with the name you are specifying already
exists on the wiki, it'll be replaced without warning.
So unless you mean to update a file, it's a good idea
to first check if such a file exists.
<p>To view or search previously uploaded images,
go to the <a href=\"{{localurle:steci:Imagelist}}\">list of uploaded images</a>.
Uploads and deletions are logged on the " .
"<a href=\"{{localurle:Project:Upload_log}}\">upload log</a>.
</p><p>Use the form below to upload new image files for use in
illustrating your pages.
On most browsers, you will see a \"Browse...\" button, which will
bring up your operating system's standard file open dialog.
Choosing a file will fill the name of that file into the text
field next to the button.
You must also check the box affirming that you are not
violating any copyrights by uploading the file.
Press the \"Upload\" button to finish the upload.
This may take some time if you have a slow internet connection.
<p>The preferred formats are JPEG for photographic images, PNG
for drawings and other iconic images, and OGG for sounds.
Please name your files descriptively to avoid confusion.
To include the image in a page, use a link in the form
<b>[[{{ns:6}}:file.jpg]]</b> or <b>[[{{ns:6}}:file.png|alt text]]</b>
or <b>[[{{ns:-2}}:file.ogg]]</b> for sounds.
<p>Please note that as with wiki pages, others may edit or
delete your uploads if they think it serves the project, and
you may be blocked from uploading if you abuse the system.",

'uploadlog'		=> 'upload log',
'uploadlogpage' => 'Upload_log',
'uploadlogpagetext' => 'Below is a list of the most recent file uploads.
All times shown are server time (UTC).
<ul>
</ul>
',
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
'badfilename'	=> "Image name has been changed to \"$1\".",
'badfiletype'	=> "\".$1\" is not a recommended image file format.",
'largefile'		=> 'It is recommended that images not exceed 100k in size.',
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
'unusedimages'	=> 'Unused images',
'popularpages'	=> 'Popular pages',
'nviews'		=> '$1 views',
'wantedpages'	=> 'Wanted pages',
'nlinks'		=> '$1 links',
'allpages'		=> 'All pages',
'nextpage'		=> 'Next page ($1)',
'randompage'	=> 'Random page',
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

# Email this user
#
'mailnologin'	=> 'No send address',
'mailnologintext' => "You must be <a href=\"{{localurl:steci:Userlogin\">logged in</a>
and have a valid e-mail address in your <a href=\"{{localurl:steci:Preferences}}\">preferences</a>
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
'watchnologintext'	=> "You must be <a href=\"{{localurl:steci:Userlogin}}\">logged in</a>
to modify your watchlist.",
'addedwatch'		=> 'Added to watchlist',
'addedwatchtext'	=> "The page \"$1\" has been added to your [[{{ns:-1}}:Watchlist|watchlist]].
Future changes to this page and its associated Talk page will be listed there,
and the page will appear '''bolded''' in the [[steci:Recentchanges|list of recent changes]] to
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
'dellogpagetext' => 'Below is a list of the most recent deletions.
All times shown are server time (UTC).
<ul>
</ul>
',
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
by [[prenu:$2|$2]] ([[prenu tavla:$2|Talk]]); someone else has edited or rolled back the page already.

Last edit was by [[prenu:$3|$3]] ([[prenu tavla:$3|Talk]]). ",
#   only shown if there is an edit comment
'editcomment' => "The edit comment was: \"<i>$1</i>\".",
'revertpage'	=> "Reverted edit of $2, changed back to last version by $1",
'protectlogpage' => 'Protection_log',
'protectlogtext' => "Below is a list of page locks/unlocks.
See [[{{ns:4}}:Protected page]] for more information.",
'protectedarticle' => "protected [[:$1]]",
'unprotectedarticle' => "unprotected [[:$1]]",
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
'undeletedtext'   => "[[$1]] has been successfully restored.
See [[{{ns:4}}:Deletion_log]] for a record of recent deletions and restorations.",

# Contributions
#
'contributions'	=> 'User contributions',
'mycontris' => 'My contributions',
'contribsub'	=> "For $1",
'nocontribs'	=> 'No changes were found matching these criteria.',
'ucnote'		=> "Below are this user's last <b>$1</b> changes in the last <b>$2</b> days.",
'uclinks'		=> "View the last $1 changes; view the last $2 days.",
'uctop'		=> ' (top)' ,

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
'val_merge_old' => 'Merge my other validation data into this version',
'val_form_note' => '<b>Hint:</b> Merging your data means that for the article
revision you select, all options where you have specified <i>no opinion</i>
will be set to the value and comment of the most recent revision for which you
have expressed an opinion. For example, if you want to change a single option
for a newer revision, but also keep your other settings for this article in
this revision, just select which option you intend to <i>change</i>, and
merging will fill in the other options with your previous settings.',
'val_noop' => 'No opinion',
'val_percent' => '<b>$1%</b><br>($2 of $3 points by $4 users)',
'val_percent_single' => '<b>$1%</b><br>($2 of $3 points by one user)',
'val_total' => 'Total',
'val_version' => 'Version',
'val_tab' => 'Validate',
'val_this_version' => "<h2>This version</h2>\n",
'val_version_of' => "<h2>Version of $1</h2>\n" ,
'val_table_header' => "<tr><th>Class</th>$1<th colspan=4>Opinion</th>$1<th>Comment</th></tr>\n",
'val_stat_link_text' => 'Validation statistics for this article',

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
"movearticle" => "muvgau le papri",
"movenologin" => "na samyzva",
"movenologintext" => "lo nu do selvei prenu gi'e <a href=\"" .
  wfLocalUrl( "steci:Userlogin" ) . "\">samyzva</a>
cu sarcu lo nu do muvgau lo papri",
"newtitle"  => "cnino paprycme",
"movepagebtn" => "muvgau le papri",
"pagemovedsub" => "le nu muvgau cu snada",
"pagemovedtext" => "la'e lu \"[[$1]]\" li'u cu muvdu lu \"[[$2]]\" li'u",
"articleexists" => "lo papri cu se cmene di'u .ija di'u poi do cuxna na xagyseltai
.i .e'o ko cuxna lo drata cmene",
"talkexists" => "le sevzi papri cu muvdu .iku'i le tavla papri na muvdu .iki'ubo lo papri poi se cmene di'u puzi zasti .i .e'o ko macnu mixygau fi xai",
"movedto"  => "muvdu",
"movetalk"  => "Mover página  \"talk\" também, se aplicável.",
"talkpagemoved" => "le tavla papri cu snada muvdu",
"talkpagenotmoved" => "A página talk correspondente  <strong>não</strong> foi movida.",
'1movedto2'		=> "$1 moved to $2",
'1movedto2_redir' => '$1 moved to $2 over redirect',

# Export

'export'		=> 'Export pages',
'exporttext'	=> 'You can export the text and editing history of a particular
page or set of pages wrapped in some XML; this can then be imported into another
wiki running MediaWiki software, transformed, or just kept for your private
amusement.',
'exportcuronly'	=> 'Include only the current revision, not the full history',

# Namespace 8 related

'allmessages'	=> 'All system messages',
'allmessagestext'	=> 'This is a list of all system messages available in the MediaWiki: namespace.',

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
'spamprotectiontext' => 'The page you wanted to save was blocked by the spam filter. This is probably caused by a link to an external site.

You might want to check the following regular expression for patterns that are currently blocked:',
'subcategorycount' => "There are $1 subcategories to this category.",
'categoryarticlecount' => "There are $1 articles in this category.",
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
'

);

include_once( "LanguageUtf8.php" );

class LanguageJbo extends LanguageUtf8 {

 function getBookstoreList () {
  global $wgBookstoreListJbo;
  return $wgBookstoreListJbo;
 }

 function getNamespaces() {
  global $wgNamespaceNamesJbo;
  return $wgNamespaceNamesJbo;
 }

 function getNsText( $index ) {
  global $wgNamespaceNamesJbo;
  return $wgNamespaceNamesJbo[$index];
 }

 function getNsIndex( $text ) {
  global $wgNamespaceNamesJbo;

  foreach ( $wgNamespaceNamesJbo as $i => $n ) {
   if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
  }
  return false;
 }

 function getQuickbarSettings() {
  global $wgQuickbarSettingsJbo;
  return $wgQuickbarSettingsJbo;
 }

 function getSkinNames() {
  global $wgSkinNamesJbo;
  return $wgSkinNamesJbo;
 }

 function getMathNames() {
  global $wgMathNamesJbo;
  return $wgMathNamesJbo;
 }

 function getDateFormats() {
  global $wgDateFormatsJbo;
  return $wgDateFormatsJbo;
 }

 function getUserToggles() {
  global $wgUserTogglesJbo;
  return $wgUserTogglesJbo;
 }

 function getMonthName( $key )
 {
  global $wgMonthNamesJbo;
  return $wgMonthNamesJbo[$key-1];
 }

 /* by default we just return base form */
 function getMonthNameGen( $key )
 {
  global $wgMonthNamesJbo;
  return $wgMonthNamesJbo[$key-1];
 }

 function getMonthRegex()
 {
  global $wgMonthNamesJbo;
  return implode( "|", $wgMonthNamesEn );
 }

 function getMonthAbbreviation( $key )
 {
  global $wgMonthAbbreviationsJbo;
  return $wgMonthAbbreviationsJbo[$key-1];
 }

 function getWeekdayName( $key )
 {
  global $wgWeekdayNamesJbo;
  return $wgWeekdayNamesJbo[$key-1];
 }

 function timeanddate( $ts, $adj = false )
 {
  return $this->time( $ts, $adj ) . ", " . $this->date( $ts, $adj );
 }

 function getValidSpecialPages()
 {
  global $wgValidSpecialPagesJbo;
  return $wgValidSpecialPagesJbo;
 }

 function getSysopSpecialPages()
 {
  global $wgSysopSpecialPagesJbo;
  return $wgSysopSpecialPagesJbo;
 }

 function getDeveloperSpecialPages()
 {
  global $wgDeveloperSpecialPagesJbo;
  return $wgDeveloperSpecialPagesJbo;
 }

 function getMessage( $key )
 {
  global $wgAllMessagesJbo;
  return $wgAllMessagesJbo[$key];
 }
}

?>

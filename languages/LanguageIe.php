<?php

include_once( "LanguageUtf8.php" );

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => "-"

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#

if($wgMetaNamespace === FALSE)
	$wgMetaNamespace = str_replace( " ", "_", $wgSitename );

/* private */ $wgNamespaceNamesIe = array(
        NS_MEDIA            => "Media",
        NS_SPECIAL          => "Special",
        NS_MAIN             => "",
        NS_TALK             => "Conversation",
        NS_USER             => "Usator",
        NS_USER_TALK        => "Usator_Conversation",
        NS_WIKIPEDIA        => $wgMetaNamespace,
        NS_WIKIPEDIA_TALK   => $wgMetaNamespace . "_conversation",
        NS_IMAGE            => "Image",
        NS_IMAGE_TALK       => "Image_conversation",
        NS_MEDIAWIKI        => "MediaWiki",
        NS_MEDIAWIKI_TALK   => "MediaWiki_conversation",

        ## FIXME: Translations missing
        NS_TEMPLATE         => "Template",
        NS_TEMPLATE_TALK    => "Template_talk",
        NS_HELP             => "Help",
        NS_HELP_TALK        => "Help_talk",
        NS_CATEGORY         => "Category",
        NS_CATEGORY_TALK    => "Category_talk"
);

/* private */ $wgDefaultUserOptionsIe = array(
	"quickbar" => 1, "underline" => 1, "hover" => 1,
	"cols" => 80, "rows" => 25, "searchlimit" => 20,
	"contextlines" => 5, "contextchars" => 50,
	"skin" => 0, "math" => 1, "rcdays" => 7, "rclimit" => 50,
	"highlightbroken" => 1, "stubthreshold" => 0,
	"previewontop" => 1, "editsection"=>1,"editsectiononrightclick"=>0, "showtoc"=>1,
	"showtoolbar" =>1,
	"date" => 0
);

/* private */ $wgQuickbarSettingsIe = array(
	"None", "Fixed left", "Fixed right", "Floating left"
);

/* private */ $wgSkinNamesIe = array(
	'standard' => "Standard",
	'nostalgia' => "Nostalgia",
	'cologneblue' => "Cologne Blue",
	'smarty' => "Paddington",
	'montparnasse' => "Montparnasse",
	'davinci' => "DaVinci",
	'mono' => "Mono",
	'monobook' => "MonoBook",
	"myskin" => "MySkin"
);

/* private */ $wgMathNamesIe = array(
	"Always render PNG",
	"HTML if very simple or else PNG",
	"HTML if possible or else PNG",
	"Leave it as TeX (for text browsers)",
	"Recommended for modern browsers"
);

/* private */ $wgDateFormatsIe = array(
	"No preference",
	"Januar 15, 2001",
	"15 Januar 2001",
	"2001 Januar 15",
	"2001-01-15"
);
/* private */ $wgUserTogglesIe = array(
	"hover"		=> "Show hoverbox over wiki links",
	"underline" => "Underline links",
	"highlightbroken" => "Format broken links <a href=\"\" class=\"new\">like
this</a> (alternative: like this<a href=\"\" class=\"internal\">?</a>).",
	"justify"	=> "Justify paragraphs",
	"hideminor" => "Hide minor edits in recent changes",
	"usenewrc" => "Enhanced recent changes (not for all browsers)",
	"numberheadings" => "Auto-number headings",
	"showtoolbar"=>"Show edit toolbar",
	"editondblclick" => "Edit pages on double click (JavaScript)",
	"editsection"=>"Enable section editing via [edit] links",
	"editsectiononrightclick"=>"Enable section editing by right clicking<br> on section titles (JavaScript)",
	"showtoc"=>"Show table of contents<br>(for articles with more than 3 headings)",
	"rememberpassword" => "Remember password across sessions",
	"editwidth" => "Edit box has full width",
	"watchdefault" => "Add pages you edit to your watchlist",
	"minordefault" => "Mark all edits minor by default",
	"previewontop" => "Show preview before edit box and not after it",
	"nocache" => "Disable page caching"
);


/* Add a $wgBookstoreListIe to override default list of book links for ISBN links */


/* private */ $wgWeekdayNamesIe = array(
	"Soledí", "Lundí", "Mardí", "Mercurdí", "Jovedí",
	"Venerdí", "Saturdí"
);

/* private */ $wgMonthNamesIe = array(
	"Januar", "Februare", "Marte", "April", "Mai", "Junio",
	"Julí", "August", "Septembre", "Octobre", "Novembre",
	"Decembre"
);

/* private */ $wgMonthAbbreviationsIe = array(
	"Jan", "Feb", "Mar", "Apr", "Mai", "Jun", "Jul", "Aug",
	"Sep", "Oct", "Nov", "Dec"
);

# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesIe = array(
	"Userlogin"		=> "",
	"Userlogout"	=> "",
	"Preferences"	=> "Set my user preferences",
	"Watchlist"		=> "My watchlist",
	"Recentchanges" => "Recently updated pages",
	"Upload"		=> "Upload image files",
	"Imagelist"		=> "Image list",
	"Listusers"		=> "Registered users",
	"Statistics"	=> "Site statistics",
	"Randompage"	=> "Random article",

	"Lonelypages"	=> "Orphaned articles",
	"Unusedimages"	=> "Orphaned images",
	"Popularpages"	=> "Popular articles",
	"Wantedpages"	=> "Most wanted articles",
	"Shortpages"	=> "Short articles",
	"Longpages"		=> "Long articles",
	"Newpages"		=> "Newly created articles",
	"Ancientpages"	=> "Oldest articles",
        "Deadendpages"  => "Dead-end pages",
#	"Intl"                => "Interlanguage Links",
	"Allpages"		=> "All pages by title",

	"Ipblocklist"	=> "Blocked users/IP addresses",
	"Maintenance"	=> "Maintenance page",
	"Specialpages"  => "",
	"Contributions" => "",
	"Emailuser"		=> "",
	"Whatlinkshere" => "",
	"Recentchangeslinked" => "",
	"Movepage"		=> "",
        "Blockme"       => "",
	"Booksources"	=> "External book sources",
#	"Categories"	=> "Page categories",
	"Export"		=> "XML page export",
	"Version"		=> "Show MediaWiki version",
);

/* private */ $wgSysopSpecialPagesIe = array(
	"Blockip"		=> "Block a user/IP address",
	"Asksql"		=> "Query the database",
	"Undelete"		=> "Restore deleted pages",
	"Makesysop"		=> "Turn a user into a sysop"
);

/* private */ $wgDeveloperSpecialPagesIe = array(
	"Lockdb"		=> "Make database read-only",
	"Unlockdb"		=> "Restore DB write access"
);

/* private */ $wgAllMessagesIe = array(

# Bits of text used by many pages:
#
"linktrail"		=> "/^([a-z]+)(.*)\$/sD",
"mainpage"		=> "Págine Cardinal",
"mainpagetext"	=> "Wiki software successfully installed.",
"about"			=> "Description",
"aboutwikipedia" => "Description de Wikipedia",
"aboutpage"		=> "$wgMetaNamespace:Description",
"help"			=> "Help",
"helppage"		=> "$wgMetaNamespace:Help",
"wikititlesuffix" => "$wgSitename",
"bugreports"	=> "Raportas de disfunctiones",
"bugreportspage" => "$wgMetaNamespace:Raportas de disfunctiones",
"faq"			=> "FAQ",
"faqpage"		=> "$wgMetaNamespace:FAQ",
"edithelp"		=> "Editing help",
"edithelppage"	=> "$wgMetaNamespace:How_does_one_edit_a_page",
"cancel"		=> "Anullar",
"qbfind"		=> "Trovar",
"qbbrowse"		=> "Browse",
"qbedit"		=> "Modificar",
"qbpageoptions" => "Page options",
"qbpageinfo"	=> "Page info",
"qbmyoptions"	=> "Mi págines",
"qbspecialpages"	=> "Págines special",
"mypage"		=> "Mi págine",
"mytalk"		=> "Mi conversation",
"currentevents" => "-",
"errorpagetitle" => "Erra",
"returnto"		=> "Revenir a $1.",
"fromwikipedia"	=> "De Wikipedia - li Encyclopedia Líbre.",
"whatlinkshere"	=> "Pages that link here",
"help"			=> "Help",
"search"		=> "Trovar",
"go"		=> "Go",
"history"		=> "Old versions",
"printableversion" => "Printable version",
"editthispage"	=> "Modificar ti págine",
"deletethispage" => "Deleter ti págine",
"protectthispage" => "Protecter ti págine",
"unprotectthispage" => "Deprotecter ti págine",
"newpage" => "Nov págine",
"talkpage"		=> "Conversar ti págine",
"postcomment"   => "Impostar un comentarie",
"articlepage"	=> "View article",
"subjectpage"	=> "View subject", # For compatibility
"userpage" => "View user page",
"wikipediapage" => "View meta page",
"imagepage" => 	"View image page",
"viewtalkpage" => "View discussion",
"otherlanguages" => "Altri lingues",
"redirectedfrom" => "(Redirected from $1)",
"lastmodified"	=> "This page was last modified $1.",
"viewcount"		=> "This page has been accessed $1 times.",
"gnunote" => "All text is available under the terms of the <a class=internal href='/wiki/GNU_FDL'>GNU Free Documentation License</a>.",
"printsubtitle" => "(Материал с http://www.wikipedia.org)",
"protectedpage" => "Un págine protectet",
"administrators" => "$wgMetaNamespace:Administratores",
"sysoptitle"	=> "Sysop access required",
"sysoptext"		=> "The action you have requested can only be
performed by users with \"sysop\" status.
See $1.",
"developertitle" => "Developer access required",
"developertext"	=> "The action you have requested can only be
performed by users with \"developer\" status.
See $1.",
"nbytes"		=> "$1 bytes",
"go"			=> "Go",
"ok"			=> "OK",
"sitetitle"		=> $wgSitename,
"sitesubtitle"	=> "Li Encyclopedie Líber",
"retrievedfrom" => "Retrieved from \"$1\"",
"newmessages" => "Vu ha recivet $1.",
"newmessageslink" => "Nov missages",
"editsection"=>"edit",
"toc" => "Table of contents",
"showtoc" => "show",
"hidetoc" => "hide",

# Main script and global functions
#
"nosuchaction"	=> "No such action",
"nosuchactiontext" => "The action specified by the URL is not
recognized by the wiki",
"nosuchspecialpage" => "No such special page",
"nospecialpagetext" => "You have requested a special page that is not
recognized by the wiki.",

# General errors
#
"error"			=> "Error",
"databaseerror" => "Database error",
"dberrortext"	=> "A database query syntax error has occurred.
This could be because of an illegal search query (see $5),
or it may indicate a bug in the software.
The last attempted database query was:
<blockquote><tt>$1</tt></blockquote>
from within function \"<tt>$2</tt>\".
MySQL returned error \"<tt>$3: $4</tt>\".",
"dberrortextcl" => "A database query syntax error has occurred.
The last attempted database query was:
\"$1\"
from within function \"$2\".
MySQL returned error \"$3: $4\".\n",
"noconnect"		=> "Sorry! The wiki is experiencing some technical difficulties, and cannot contact the database server.",
"nodb"			=> "Could not select database $1",
"cachederror"		=> "The following is a cached copy of the requested page, and may not be up to date.",
"readonly"		=> "Database locked",
"enterlockreason" => "Enter a reason for the lock, including an estimate
of when the lock will be released",
"readonlytext"	=> "The database is currently locked to new
entries and other modifications, probably for routine database maintenance,
after which it will be back to normal.
The administrator who locked it offered this explanation:
<p>$1",
"missingarticle" => "The database did not find the text of a page
that it should have found, named \"$1\".

<p>This is usually caused by following an outdated diff or history link to a
page that has been deleted.

<p>If this is not the case, you may have found a bug in the software.
Please report this to an administrator, making note of the URL.",
"internalerror" => "Internal error",
"filecopyerror" => "Could not copy file \"$1\" to \"$2\".",
"filerenameerror" => "Could not rename file \"$1\" to \"$2\".",
"filedeleteerror" => "Could not delete file \"$1\".",
"filenotfound"	=> "Could not find file \"$1\".",
"unexpected"	=> "Unexpected value: \"$1\"=\"$2\".",
"formerror"		=> "Error: could not submit form",
"badarticleerror" => "This action cannot be performed on this page.",
"cannotdelete"	=> "Could not delete the page or image specified. (It may have already been deleted by someone else.)",
"badtitle"		=> "Bad title",
"badtitletext"	=> "The requested page title was invalid, empty, or
an incorrectly linked inter-language or inter-wiki title.",
"perfdisabled" => "Sorry! This feature has been temporarily disabled
because it slows the database down to the point that no one can use
the wiki.",
"perfdisabledsub" => "Here's a saved copy from $1:",

# Login and logout pages
#
"logouttitle"	=> "User logout",
"logouttext" => "You are now logged out.
You can continue to use $wgSitename anonymously, or you can log in
again as the same or as a different user. Note that some pages may
continue to be displayed as if you were still logged in, until you clear
your browser cache\n",

"welcomecreation" => "<h2>Welcome, $1!</h2><p>Your account has been created.
Don't forget to change your $wgSitename preferences.",

"loginpagetitle" => "User login",
"yourname"		=> "Vor nómine",
"yourpassword"	=> "Vor password",
"yourpasswordagain" => "Retype password",
"newusersonly"	=> " (new users only)",
"remembermypassword" => "Remember my password across sessions.",
"loginproblem"	=> "<b>There has been a problem with your login.</b><br>Try again!",
"alreadyloggedin" => "<font color=red><b>User $1, you are already logged in!</b></font><br>\n",

"login"			=> "Intrar",
"userlogin"		=> "Intrar",
"logout"		=> "Exiter",
"userlogout"	=> "Exiter",
"notloggedin"	=> "Not logged in",
"createaccount"	=> "Create new account",
"createaccountmail"	=> "by eMail",
"badretype"		=> "The passwords you entered do not match.",
"userexists"	=> "The user name you entered is already in use. Please choose a different name.",
"youremail"		=> "Your e-mail*",
"yournick"		=> "Your nickname (for signatures)",
"emailforlost"	=> "* Entering an email address is optional.  But it enables people to
contact you through the website without you having to reveal your
email address to them, and it also helps you if you forget your
password.",
"loginerror"	=> "Login error",
"nocookiesnew"	=> "The user account was created, but you are not logged in. $wgSitename uses cookies to log in users. You have

cookies disabled. Please enable them, then log in with your new username and password.",
"nocookieslogin"	=> "$wgSitename uses cookies to log in users. You have cookies disabled. Please enable them and try again.",
"noname"		=> "You have not specified a valid user name.",
"loginsuccesstitle" => "Login successful",
"loginsuccess"	=> "You are now logged in to $wgSitename as \"$1\".",
"nosuchuser"	=> "There is no user by the name \"$1\".
Check your spelling, or use the form below to create a new user account.",
"wrongpassword"	=> "The password you entered is incorrect. Please try again.",
"mailmypassword" => "Mail me a new password",
"passwordremindertitle" => "Password reminder from $wgSitename",
"passwordremindertext" => "Someone (probably you, from IP address $1)
requested that we send you a new $wgSitename login password.
The password for user \"$2\" is now \"$3\".
You should log in and change your password now.",
"noemail"		=> "There is no e-mail address recorded for user \"$1\".",
"passwordsent"	=> "A new password has been sent to the e-mail address
registered for \"$1\".
Please log in again after you receive it.",

# Edit pages
#
"summary"		=> "Summary",
"subject"		=> "Subject/headline",
"minoredit"		=> "This is a minor edit",
"watchthis"		=> "Watch this article",
"savearticle"	=> "Save page",
"preview"		=> "Preview",
"showpreview"	=> "Show preview",
"blockedtitle"	=> "User is blocked",
"blockedtext"	=> "Your user name or IP address has been blocked by $1.
The reason given is this:<br>''$2''<p>You may contact $1 or one of the other
[[$wgMetaNamespace:Administrators|administrators]] to discuss the block.",
"newarticle"	=> "(Nov)",
"newarticletext" =>
"You've followed a link to a page that doesn't exist yet.
To create the page, start typing in the box below
(see the [[$wgMetaNamespace:Help|help page]] for more info).
If you are here by mistake, just click your browser's '''back''' button.",
"anontalkpagetext" => "---- ''This is the discussion page for an anonymous user who has not created an account yet or who does not use it.

We therefore have to use the numerical [[IP address]] to identify him/her. Such an IP address can be shared by several users. If you are an

anonymous user and feel that irrelevant comments have been directed at you, please [[Special:Userlogin|create an account or log in]] to avoid

future confusion with other anonymous users.'' ",
"noarticletext" => "(There is currently no text in this page)",
"updated"		=> "(Updated)",
"note"			=> "<strong>Note:</strong> ",
"previewnote"	=> "Remember that this is only a preview, and has not yet been saved!",
"previewconflict" => "This preview reflects the text in the upper
text editing area as it will appear if you choose to save.",
"editing"		=> "Editing $1",
"sectionedit"	=> " (section)",
"commentedit"	=> " (comment)",
"editconflict"	=> "Edit conflict: $1",
"explainconflict" => "Someone else has changed this page since you
started editing it.
The upper text area contains the page text as it currently exists.
Your changes are shown in the lower text area.
You will have to merge your changes into the existing text.
<b>Only</b> the text in the upper text area will be saved when you
press \"Save page\".\n<p>",
"yourtext"		=> "Vor textu",
"storedversion" => "Version conservat",
"editingold"	=> "<strong>WARNING: You are editing an out-of-date
revision of this page.
If you save it, any changes made since this revision will be lost.</strong>\n",
"yourdiff"		=> "Differences",
# FIXME: This is inappropriate for third-party use!
"copyrightwarning" => "Please note that all contributions to $wgSitename are
considered to be released under the GNU Free Documentation License
(see $1 for details).
If you don't want your writing to be edited mercilessly and redistributed
at will, then don't submit it here.<br>
You are also promising us that you wrote this yourself, or copied it from a
public domain or similar free resource.
<strong>DO NOT SUBMIT COPYRIGHTED WORK WITHOUT PERMISSION!</strong>",
"longpagewarning" => "WARNING: This page is $1 kilobytes long; some
browsers may have problems editing pages approaching or longer than 32kb.
Please consider breaking the page into smaller sections.",
"readonlywarning" => "WARNING: The database has been locked for maintenance,
so you will not be able to save your edits right now. You may wish to cut-n-paste
the text into a text file and save it for later.",
"protectedpagewarning" => "WARNING:  This page has been locked so that only
users with sysop privileges can edit it. Be sure you are following the
<a href='/wiki/$wgMetaNamespace:Protected_page_guidelines'>protected page
guidelines</a>.",

# History pages
#
"revhistory"	=> "Revision history",
"nohistory"		=> "There is no edit history for this page.",
"revnotfound"	=> "Revision not found",
"revnotfoundtext" => "The old revision of the page you asked for could not be found.
Please check the URL you used to access this page.\n",
"loadhist"		=> "Loading page history",
"currentrev"	=> "Current revision",
"revisionasof"	=> "Revision as of $1",
"cur"			=> "cur",
"next"			=> "next",
"last"			=> "last",
"orig"			=> "orig",
"histlegend"	=> "Legend: (cur) = difference with current version,
(last) = difference with preceding version, M = minor edit",

# Diffs
#
"difference"	=> "(Difference between revisions)",
"loadingrev"	=> "loading revision for diff",
"lineno"		=> "Line $1:",
"editcurrent"	=> "Edit the current version of this page",

# Search results
#
"searchresults" => "Search results",
"searchhelppage" => "$wgMetaNamespace:Searching",
"searchingwikipedia" => "Searching $wgSitename",
"searchresulttext" => "For more information about searching $wgSitename, see $1.",
"searchquery"	=> "For query \"$1\"",
"badquery"		=> "Badly formed search query",
"badquerytext"	=> "We could not process your query.
This is probably because you have attempted to search for a
word fewer than three letters long, which is not yet supported.
It could also be that you have mistyped the expression, for
example \"fish and and scales\".
Please try another query.",
"matchtotals"	=> "The query \"$1\" matched $2 article titles
and the text of $3 articles.",
"nogomatch" => "No page with this exact title exists, trying full text search.",
"titlematches"	=> "Article title matches",
"notitlematches" => "No article title matches",
"textmatches"	=> "Article text matches",
"notextmatches"	=> "No article text matches",
"prevn"			=> "previous $1",
"nextn"			=> "next $1",
"viewprevnext"	=> "View ($1) ($2) ($3).",
"showingresults" => "Showing below <b>$1</b> results starting with #<b>$2</b>.",
"showingresultsnum" => "Showing below <b>$3</b> results starting with #<b>$2</b>.",
"nonefound"		=> "<strong>Note</strong>: unsuccessful searches are
often caused by searching for common words like \"have\" and \"from\",
which are not indexed, or by specifying more than one search term (only pages
containing all of the search terms will appear in the result).",
"powersearch" => "Search",
"powersearchtext" => "
Search in namespaces :<br>
$1<br>
$2 List redirects &nbsp; Search for $3 $9",

# Preferences page
#
"preferences"	=> "Preferences",
"prefsnologin" => "Not logged in",
"prefsnologintext"	=> "You must be <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">logged in</a>
to set user preferences.",
"prefslogintext" => "You are logged in as \"$1\".
Your internal ID number is $2.

See [[$wgMetaNamespace:User preferences help]] for help deciphering the options.",
"prefsreset"	=> "Preferences have been reset from storage.",
"qbsettings"	=> "Quickbar settings",
"changepassword" => "Change password",
"skin"			=> "Skin",
"math"			=> "Rendering math",
"dateformat"	=> "Date format",
"math_failure"		=> "Failed to parse",
"math_unknown_error"	=> "unknown error",
"math_unknown_function"	=> "unknown function ",
"math_lexing_error"	=> "lexing error",
"math_syntax_error"	=> "syntax error",
"math_image_error"	=> "PNG conversion failed; check for correct installation of latex, dvips, gs, and convert",
"math_bad_tmpdir"	=> "Can't write to or create math temp directory",
"math_bad_output"	=> "Can't write to or create math output directory",
"math_notexvc"	=> "Missing texvc executable; please see math/README to configure.",
"saveprefs"		=> "Save preferences",
"resetprefs"	=> "Reset preferences",
"oldpassword"	=> "Old password",
"newpassword"	=> "New password",
"retypenew"		=> "Retype new password",
"textboxsize"	=> "Textbox dimensions",
"rows"			=> "Rows",
"columns"		=> "Columns",
"searchresultshead" => "Search result settings",
"resultsperpage" => "Hits to show per page",
"contextlines"	=> "Lines to show per hit",
"contextchars"	=> "Characters of context per line",
"stubthreshold" => "Threshold for stub display",
"recentchangescount" => "Number of titles in recent changes",
"savedprefs"	=> "Your preferences have been saved.",
"timezonetext"	=> "Enter number of hours your local time differs
from server time (UTC).",
"localtime"	=> "Local time display",
"timezoneoffset" => "Offset",
"servertime"	=> "Server time is now",
"guesstimezone" => "Fill in from browser",
"emailflag"		=> "Disable e-mail from other users",
"defaultns"		=> "Search in these namespaces by default:",

# Recent changes
#
"changes" => "changes",
"recentchanges" => "Recent changes",
"recentchangestext" => "Track the most recent changes to the wiki on this page.",
"rcloaderr"		=> "Loading recent changes",
"rcnote"		=> "Below are the last <strong>$1</strong> changes in last <strong>$2</strong> days.",
"rcnotefrom"	=> "Below are the changes since <b>$2</b> (up to <b>$1</b> shown).",
"rclistfrom"	=> "Show new changes starting from $1",
# "rclinks"		=> "Show last $1 changes in last $2 hours / last $3 days",
# "rclinks"		=> "Show last $1 changes in last $2 days.",
"showhideminor"         => "$1 minor edits | $2 bots | $3 logged in users ",
"rclinks"		=> "Show last $1 changes in last $2 days<br>$3",
"rchide"		=> "in $4 form; $1 minor edits; $2 secondary namespaces; $3 multiple edits.",
"rcliu"			=> "; $1 edits from logged in users",
"diff"			=> "diff",
"hist"			=> "hist",
"hide"			=> "hide",
"show"			=> "show",
"tableform"		=> "table",
"listform"		=> "list",
"nchanges"		=> "$1 changes",
"minoreditletter" => "M",
"newpageletter" => "N",

# Upload
#
"upload"		=> "Upload file",
"uploadbtn"		=> "Upload file",
"uploadlink"	=> "Upload images",
"reupload"		=> "Re-upload",
"reuploaddesc"	=> "Return to the upload form.",
"uploadnologin" => "Not logged in",
"uploadnologintext"	=> "You must be <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">logged in</a>
to upload files.",
"uploadfile"	=> "Upload images, sounds, documents etc.",
"uploaderror"	=> "Upload error",
"uploadtext"	=> "<strong>STOP!</strong> Before you upload here,
make sure to read and follow the <a href=\"" .
wfLocalUrlE( "$wgMetaNamespace:Image_use_policy" ) . "\">image use policy</a>.
<p>If a file with the name you are specifying already
exists on the wiki, it'll be replaced without warning.
So unless you mean to update a file, it's a good idea
to first check if such a file exists.
<p>To view or search previously uploaded images,
go to the <a href=\"" . wfLocalUrlE( "Special:Imagelist" ) .
"\">list of uploaded images</a>.
Uploads and deletions are logged on the <a href=\"" .
wfLocalUrlE( "$wgMetaNamespace:Upload_log" ) . "\">upload log</a>.
<p>Use the form below to upload new image files for use in
illustrating your articles.
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
To include the image in an article, use a link in the form
<b>[[image:file.jpg]]</b> or <b>[[image:file.png|alt text]]</b>
or <b>[[media:file.ogg]]</b> for sounds.
<p>Please note that as with wiki pages, others may edit or
delete your uploads if they think it serves the encyclopedia, and
you may be blocked from uploading if you abuse the system.",

"uploadlog"		=> "upload log",
"uploadlogpage" => "Upload_log",
"uploadlogpagetext" => "Below is a list of the most recent file uploads.
All times shown are server time (UTC).
<ul>
</ul>
",
"filename"		=> "Filename",
"filedesc"		=> "Summary",
"filestatus" => "Copyright status",
"filesource" => "Source",
"affirmation"	=> "I affirm that the copyright holder of this file
agrees to license it under the terms of the $1.",
"copyrightpage" => "$wgMetaNamespace:Copyrights",
"copyrightpagename" => "$wgSitename copyright",
"uploadedfiles"	=> "Uploaded files",
"noaffirmation" => "You must affirm that your upload does not violate
any copyrights.",
"ignorewarning"	=> "Ignore warning and save file anyway.",
"minlength"		=> "Image names must be at least three letters.",
"badfilename"	=> "Image name has been changed to \"$1\".",
"badfiletype"	=> "\".$1\" is not a recommended image file format.",
"largefile"		=> "It is recommended that images not exceed 100k in size.",
"successfulupload" => "Successful upload",
"fileuploaded"	=> "File \"$1\" uploaded successfully.
Please follow this link: ($2) to the description page and fill
in information about the file, such as where it came from, when it was
created and by whom, and anything else you may know about it.",
"uploadwarning" => "Upload warning",
"savefile"		=> "Save file",
"uploadedimage" => "uploaded \"$1\"",
"uploaddisabled" => "Sorry, uploading is disabled.",

# Image list
#
"imagelist"		=> "Image list",
"imagelisttext"	=> "Below is a list of $1 images sorted $2.",
"getimagelist"	=> "fetching image list",
"ilshowmatch"	=> "Show all images with names matching",
"ilsubmit"		=> "Search",
"showlast"		=> "Show last $1 images sorted $2.",
"all"			=> "all",
"byname"		=> "by name",
"bydate"		=> "by date",
"bysize"		=> "by size",
"imgdelete"		=> "del",
"imgdesc"		=> "desc",
"imglegend"		=> "Legend: (desc) = show/edit image description.",
"imghistory"	=> "Image history",
"revertimg"		=> "rev",
"deleteimg"		=> "del",
"imghistlegend" => "Legend: (cur) = this is the current image, (del) = delete
this old version, (rev) = revert to this old version.
<br><i>Click on date to see image uploaded on that date</i>.",
"imagelinks"	=> "Image links",
"linkstoimage"	=> "The following pages link to this image:",
"nolinkstoimage" => "There are no pages that link to this image.",

# Statistics
#
"statistics"	=> "Statistics",
"sitestats"		=> "Site statistics",
"userstats"		=> "User statistics",
"sitestatstext" => "There are <b>$1</b> total pages in the database.
This includes \"talk\" pages, pages about $wgSitename, minimal \"stub\"
pages, redirects, and others that probably don't qualify as articles.
Excluding those, there are <b>$2</b> pages that are probably legitimate
articles.<p>
There have been a total of <b>$3</b> page views, and <b>$4</b> page edits
since the software was upgraded (July 20, 2002).
That comes to <b>$5</b> average edits per page, and <b>$6</b> views per edit.",
"userstatstext" => "There are <b>$1</b> registered users.
<b>$2</b> of these are administrators (see $3).",

# Maintenance Page
#
"maintenance"		=> "Maintenance page",
"maintnancepagetext"	=> "This page includes several handy tools for everyday maintenance. Some of these functions tend to stress

the database, so please do not hit reload after every item you fixed ;-)",
"maintenancebacklink"	=> "Back to Maintenance Page",
"disambiguations"	=> "Disambiguation pages",
"disambiguationspage"	=> "$wgMetaNamespace:Links_to_disambiguating_pages",
"disambiguationstext"	=> "The following articles link to a <i>disambiguation page</i>. They should link to the appropriate topic

instead.<br>A page is treated as dismbiguation if it is linked from $1.<br>Links from other namespaces are <i>not</i> listed here.",
"doubleredirects"	=> "Double Redirects",
"doubleredirectstext"	=> "<b>Attention:</b> This list may contain false positives. That usually means there is additional text with links

below the first #REDIRECT.<br>\nEach row contains links to the first and second redirect, as well as the first line of the second redirect text,

usually giving the \"real\" taget article, which the first redirect should point to.",
"brokenredirects"	=> "Broken Redirects",
"brokenredirectstext"	=> "The following redirects link to a non-existing article.",
"selflinks"		=> "Pages with Self Links",
"selflinkstext"		=> "The following pages contain a link to themselves, which they should not.",
"mispeelings"           => "Pages with misspellings",
"mispeelingstext"               => "The following pages contain a common misspelling, which are listed on $1. The correct spelling might be given

(like this).",
"mispeelingspage"       => "List of common misspellings",
"missinglanguagelinks"  => "Missing Language Links",
"missinglanguagelinksbutton"    => "Find missing language links for",
"missinglanguagelinkstext"      => "These articles do <i>not</i> link to their counterpart in $1. Redirects and subpages are <i>not</i> shown.",


# Miscellaneous special pages
#
"orphans"		=> "Orphaned pages",
"lonelypages"	=> "Orphaned pages",
"unusedimages"	=> "Unused images",
"popularpages"	=> "Popular pages",
"nviews"		=> "$1 views",
"wantedpages"	=> "Wanted pages",
"nlinks"		=> "$1 links",
"allpages"		=> "All pages",
"randompage"	=> "Random page",
"shortpages"	=> "Short pages",
"longpages"		=> "Long pages",
"deadendpages"  => "Dead-end pages",
"listusers"		=> "User list",
"specialpages"	=> "Special pages",
"spheading"		=> "Special pages for all users",
"sysopspheading" => "For sysop use only",
"developerspheading" => "For developer use only",
"protectpage"	=> "Protect page",
"recentchangeslinked" => "Related changes",
"rclsub"		=> "(to pages linked from \"$1\")",
"debug"			=> "Debug",
"newpages"		=> "New pages",
"ancientpages"		=> "Oldest articles",
"intl"		=> "Interlanguage links",
"movethispage"	=> "Move this page",
"unusedimagestext" => "<p>Please note that other web sites may link to an image with
a direct URL, and so may still be listed here despite being
in active use.",
"booksources"	=> "Book sources",
# FIXME: Other sites, of course, may have affiliate relations with the booksellers list
"booksourcetext" => "Below is a list of links to other sites that
sell new and used books, and may also have further information
about books you are looking for.
$wgSitename is not affiliated with any of these businesses, and
this list should not be construed as an endorsement.",
"alphaindexline" => "$1 to $2",
"version"		=> "Version",

# Email this user
#
"mailnologin"	=> "No send address",
"mailnologintext" => "You must be <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">logged in</a>
and have a valid e-mail address in your <a href=\"" .
  wfLocalUrl( "Special:Preferences" ) . "\">preferences</a>
to send e-mail to other users.",
"emailuser"		=> "E-mail this user",
"emailpage"		=> "E-mail user",
"emailpagetext"	=> "If this user has entered a valid e-mail address in
his or her user preferences, the form below will send a single message.
The e-mail address you entered in your user preferences will appear
as the \"From\" address of the mail, so the recipient will be able
to reply.",
"defemailsubject"  => "$wgSitename e-mail",
"noemailtitle"	=> "No e-mail address",
"noemailtext"	=> "This user has not specified a valid e-mail address,
or has chosen not to receive e-mail from other users.",
"emailfrom"		=> "From",
"emailto"		=> "To",
"emailsubject"	=> "Subject",
"emailmessage"	=> "Message",
"emailsend"		=> "Send",
"emailsent"		=> "E-mail sent",
"emailsenttext" => "Your e-mail message has been sent.",

# Watchlist
#
"watchlist"			=> "My watchlist",
"watchlistsub"		=> "(for user \"$1\")",
"nowatchlist"		=> "You have no items on your watchlist.",
"watchnologin"		=> "Not logged in",
"watchnologintext"	=> "You must be <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">logged in</a>
to modify your watchlist.",
"addedwatch"		=> "Added to watchlist",
"addedwatchtext"	=> "The page \"$1\" has been added to your <a href=\"" .
  wfLocalUrl( "Special:Watchlist" ) . "\">watchlist</a>.
Future changes to this page and its associated Talk page will be listed there,
and the page will appear <b>bolded</b> in the <a href=\"" .
  wfLocalUrl( "Special:Recentchanges" ) . "\">list of recent changes</a> to
make it easier to pick out.</p>

<p>If you want to remove the page from your watchlist later, click \"Stop watching\" in the sidebar.",
"removedwatch"		=> "Removed from watchlist",
"removedwatchtext" 	=> "The page \"$1\" has been removed from your watchlist.",
"watchthispage"		=> "Watch this page",
"unwatchthispage" 	=> "Stop watching",
"notanarticle"		=> "Not an article",
"watchnochange" 	=> "None of your watched items were edited in the time period displayed.",
"watchdetails"		=> "($1 pages watched not counting talk pages;
$2 total pages edited since cutoff;
$3...
<a href='$4'>show and edit complete list</a>.)",
"watchmethod-recent"=> "checking recent edits for watched pages",
"watchmethod-list"	=> "checking watched pages for recent edits",
"removechecked" 	=> "Remove checked items from watchlist",
"watchlistcontains" => "Your watchlist contains $1 pages.",
"watcheditlist"		=> "Here's an alphabetical list of your
watched pages. Check the boxes of pages you want to remove
from your watchlist and click the 'remove checked' button
at the bottom of the screen.",
"removingchecked" 	=> "Removing requested items from watchlist...",
"couldntremove" 	=> "Couldn't remove item '$1'...",
"iteminvalidname" 	=> "Problem with item '$1', invalid name...",
"wlnote" 			=> "Below are the last $1 changes in the last <b>$2</b> hours.",
"wlshowlast" 		=> "Show last $1 hours $2 days $3",
"wlsaved"			=> "This is a saved version of your watchlist.",


# Delete/protect/revert
#
"deletepage"	=> "Delete page",
"confirm"		=> "Confirm",
"excontent" => "content was:",
"exbeforeblank" => "content before blanking was:",
"exblank" => "page was empty",
"confirmdelete" => "Confirm delete",
"deletesub"		=> "(Deleting \"$1\")",
"historywarning" => "Warning: The page you are about to delete has a history: ",
"confirmdeletetext" => "You are about to permanently delete a page
or image along with all of its history from the database.
Please confirm that you intend to do this, that you understand the
consequences, and that you are doing this in accordance with
[[$wgMetaNamespace:Policy]].",
"confirmcheck"	=> "Yes, I really want to delete this.",
"actioncomplete" => "Action complete",
"deletedtext"	=> "\"$1\" has been deleted.
See $2 for a record of recent deletions.",
"deletedarticle" => "deleted \"$1\"",
"dellogpage"	=> "Deletion_log",
"dellogpagetext" => "Below is a list of the most recent deletions.
All times shown are server time (UTC).
<ul>
</ul>
",
"deletionlog"	=> "deletion log",
"reverted"		=> "Reverted to earlier revision",
"deletecomment"	=> "Reason for deletion",
"imagereverted" => "Revert to earlier version was successful.",
"rollback"		=> "Roll back edits",
"rollbacklink"	=> "rollback",
"rollbackfailed" => "Rollback failed",
"cantrollback"	=> "Cannot revert edit; last contributor is only author of this article.",
"alreadyrolled"	=> "Cannot rollback last edit of [[$1]]
by [[User:$2|$2]] ([[User talk:$2|Talk]]); someone else has edited or rolled back the article already.

Last edit was by [[User:$3|$3]] ([[User talk:$3|Talk]]). ",
#   only shown if there is an edit comment
"editcomment" => "The edit comment was: \"<i>$1</i>\".",
"revertpage"	=> "Reverted edit of $2, changed back to last version by $1",


# Undelete
"undelete" => "Restore deleted page",
"undeletepage" => "View and restore deleted pages",
"undeletepagetext" => "The following pages have been deleted but are still in the archive and
can be restored. The archive may be periodically cleaned out.",
"undeletearticle" => "Restore deleted article",
"undeleterevisions" => "$1 revisions archived",
"undeletehistory" => "If you restore the page, all revisions will be restored to the history.
If a new page with the same name has been created since the deletion, the restored
revisions will appear in the prior history, and the current revision of the live page
will not be automatically replaced.",
"undeleterevision" => "Deleted revision as of $1",
"undeletebtn" => "Restore!",
"undeletedarticle" => "restored \"$1\"",
"undeletedtext"   => "The article [[$1]] has been successfully restored.
See [[$wgMetaNamespace:Deletion_log]] for a record of recent deletions and restorations.",

# Contributions
#
"contributions"	=> "User contributions",
"mycontris" => "My contributions",
"contribsub"	=> "For $1",
"nocontribs"	=> "No changes were found matching these criteria.",
"ucnote"		=> "Below are this user's last <b>$1</b> changes in the last <b>$2</b> days.",
"uclinks"		=> "View the last $1 changes; view the last $2 days.",
"uctop"		=> " (top)" ,


# What links here
#
"whatlinkshere"	=> "What links here",
"notargettitle" => "No target",
"notargettext"	=> "You have not specified a target page or user
to perform this function on.",
"linklistsub"	=> "(List of links)",
"linkshere"		=> "The following pages link to here:",
"nolinkshere"	=> "No pages link to here.",
"isredirect"	=> "redirect page",

# Block/unblock IP
#
"blockip"		=> "Block user",
"blockiptext"	=> "Use the form below to block write access
from a specific IP address or username.
This should be done only only to prevent vandalism, and in
accordance with [[$wgMetaNamespace:Policy|policy]].
Fill in a specific reason below (for example, citing particular
pages that were vandalized).",
"ipaddress"		=> "IP Address/username",
"ipbexpiry"		=> "Expiry",
"ipbreason"		=> "Reason",
"ipbsubmit"		=> "Block this user",
"badipaddress"	=> "Invalid IP address",
"noblockreason" => "You must supply a reason for the block.",
"blockipsuccesssub" => "Block succeeded",
"blockipsuccesstext" => "\"$1\" has been blocked.
<br>See [[Special:Ipblocklist|IP block list]] to review blocks.",
"unblockip"		=> "Unblock user",
"unblockiptext"	=> "Use the form below to restore write access
to a previously blocked IP address or username.",
"ipusubmit"		=> "Unblock this address",
"ipusuccess"	=> "\"$1\" unblocked",
"ipblocklist"	=> "List of blocked IP addresses and usernames",
"blocklistline"	=> "$1, $2 blocked $3 (expires $4)",
"blocklink"		=> "block",
"unblocklink"	=> "unblock",
"contribslink"	=> "contribs",
"autoblocker"	=> "Autoblocked because you share an IP address with \"$1\". Reason \"$2\".",
"blocklogpage"	=> "Block_log",
"blocklogentry"	=> 'blocked "$1" with an expiry time of $2',
"blocklogtext"	=> "This is a log of user blocking and unblocking actions. Automatically
blocked IP addresses are not be listed. See the [[Special:Ipblocklist|IP block list]] for
the list of currently operational bans and blocks.",
"unblocklogentry"	=> 'unblocked "$1"',
"range_block_disabled"	=> "The sysop ability to create range blocks is disabled.",
"ipb_expiry_invalid"	=> "Expiry time invalid.",
"ip_range_invalid"	=> "Invalid IP range.\n",
"proxyblocker"  => "Proxy blocker",
"proxyblockreason"      => "Your IP address has been blocked because it is an open proxy. Please contact your Internet service provider or

tech support and inform them of this serious security problem.",
"proxyblocksuccess"     => "Done.\n",

# Developer tools
#
"lockdb"		=> "Lock database",
"unlockdb"		=> "Unlock database",
"lockdbtext"	=> "Locking the database will suspend the ability of all
users to edit pages, change their preferences, edit their watchlists, and
other things requiring changes in the database.
Please confirm that this is what you intend to do, and that you will
unlock the database when your maintenance is done.",
"unlockdbtext"	=> "Unlocking the database will restore the ability of all
users to edit pages, change their preferences, edit their watchlists, and
other things requiring changes in the database.
Please confirm that this is what you intend to do.",
"lockconfirm"	=> "Yes, I really want to lock the database.",
"unlockconfirm"	=> "Yes, I really want to unlock the database.",
"lockbtn"		=> "Lock database",
"unlockbtn"		=> "Unlock database",
"locknoconfirm" => "You did not check the confirmation box.",
"lockdbsuccesssub" => "Database lock succeeded",
"unlockdbsuccesssub" => "Database lock removed",
"lockdbsuccesstext" => "The database has been locked.
<br>Remember to remove the lock after your maintenance is complete.",
"unlockdbsuccesstext" => "The database has been unlocked.",

# SQL query
#
"asksql"		=> "SQL query",
"asksqltext"	=> "Use the form below to make a direct query of the
database.
Use single quotes ('like this') to delimit string literals.
This can often add considerable load to the server, so please use
this function sparingly.",
"sqlislogged"	=> "Please note that all queries are logged.",
"sqlquery"		=> "Enter query",
"querybtn"		=> "Submit query",
"selectonly"	=> "Only read-only queries are allowed.",
"querysuccessful" => "Query successful",

# Move page
#
"movepage"		=> "Move page",
"movepagetext"	=> "Using the form below will rename a page, moving all
of its history to the new name.
The old title will become a redirect page to the new title.
Links to the old page title will not be changed; be sure to
[[Special:Maintenance|check]] for double or broken redirects.
You are responsible for making sure that links continue to
point where they are supposed to go.

Note that the page will '''not''' be moved if there is already
a page at the new title, unless it is empty or a redirect and has no
past edit history. This means that you can rename a page back to where
it was just renamed from if you make a mistake, and you cannot overwrite
an existing page.

<b>WARNING!</b>
This can be a drastic and unexpected change for a popular page;
please be sure you understand the consequences of this before
proceeding.",
"movepagetalktext" => "The associated talk page, if any, will be automatically moved along with it '''unless:'''
*You are moving the page across namespaces,
*A non-empty talk page already exists under the new name, or
*You uncheck the box below.

In those cases, you will have to move or merge the page manually if desired.",
"movearticle"	=> "Move page",
"movenologin"	=> "Not logged in",
"movenologintext" => "You must be a registered user and <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">logged in</a>
to move a page.",
"newtitle"		=> "To new title",
"movepagebtn"	=> "Move page",
"pagemovedsub"	=> "Move succeeded",
"pagemovedtext" => "Page \"[[$1]]\" moved to \"[[$2]]\".",
"articleexists" => "A page of that name already exists, or the
name you have chosen is not valid.
Please choose another name.",
"talkexists"	=> "The page itself was moved successfully, but the
talk page could not be moved because one already exists at the new
title. Please merge them manually.",
"movedto"		=> "moved to",
"movetalk"		=> "Move \"talk\" page as well, if applicable.",
"talkpagemoved" => "The corresponding talk page was also moved.",
"talkpagenotmoved" => "The corresponding talk page was <strong>not</strong> moved.",
"1movedto2"		=> "$1 moved to $2",
);

class LanguageIe extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesIe;
		return $wgNamespaceNamesIe;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesIe;
		return $wgNamespaceNamesIe[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesIe, $wgNamespaceNamesEn;

		foreach ( $wgNamespaceNamesIe as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		/* Fallback to English names used earlier on the ru.wikipedia */
		foreach ( $wgNamespaceNamesEn as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsIe;
		return $wgQuickbarSettingsIe;
	}

	function getSkinNames() {
		global $wgSkinNamesIe;
		return $wgSkinNamesIe;
	}

	function getMathNames() {
		global $wgMathNamesIe;
		return $wgMathNamesIe;
	}

	function getDateFormats() {
		global $wgDateFormatsIe;
		return $wgDateFormatsIe;
	}

	function getUserToggles() {
		global $wgUserTogglesIe;
		return $wgUserTogglesIe;
	}

	function getMonthName( $key )
	{
		global $wgMonthNamesIe;
		return $wgMonthNamesIe[$key-1];
	}

	/* by default we just return base form */
	function getMonthNameGen( $key )
	{
		global $wgMonthNamesIe;
		return $wgMonthNamesIe[$key-1];
	}

	function getMonthRegex()
	{
		global $wgMonthNamesIe;
		return implode( "|", $wgMonthNamesIe );
	}

	function getMonthAbbreviation( $key )
	{
		global $wgMonthAbbreviationsIe;
		return $wgMonthAbbreviationsIe[$key-1];
	}

	function getWeekdayName( $key )
	{
		global $wgWeekdayNamesIe;
		return $wgWeekdayNamesIe[$key-1];
	}

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesIe;
		return $wgValidSpecialPagesIe;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesIe;
		return $wgSysopSpecialPagesIe;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesIe;
		return $wgDeveloperSpecialPagesIe;
	}

	function getMessage( $key )
	{
		global $wgAllMessagesIe;
		return $wgAllMessagesIe[$key];
	}

	function fallback8bitEncoding() {
		return "windows-1252";
	}
}

?>

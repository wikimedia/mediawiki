<?php

require_once("LanguageUtf8.php");

# Tu môžete meniť názvy "namespaces" (no proste, rôznych častí encyklopédie),
# ale čísla nechajte tak, ako sú! Program to tak vyžaduje...
#
/* private */ $wgNamespaceNamesSk = array(
	-2	=> "Media",
	-1	=> "Špeciálne",
	0	=> "",
	1	=> "Komentár",
	2	=> "Redaktor",
	3	=> "Komentár_k_redaktorovi",
	4	=> "Wikipédia",
	5	=> "Komentár_k_Wikipédii",
	6	=> "Obrázok",
	7	=> "Komentár_k_obrázku",
	8	=> "MediaWiki",
	9	=> "Komentár_k_MediaWiki",
	10  => "Template",
	11  => "Template_talk"

) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsSk = array(
	"None", "Fixed left", "Fixed right", "Floating left"
);

/* private */ $wgSkinNamesSk = array(
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





# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesSk = array(
	"Userlogin"		=> "",
	"Userlogout"	=> "",
	"Preferences"	=> "Užívateľské nastavenia",
	"Watchlist"		=> "Sledované stránky",
	"Recentchanges" => "Ostatné úpravy",
	"Upload"		=> "Ulož obrázky",
	"Imagelist"		=> "Zoznam obrázkov",
	"Listusers"		=> "Registrovaní užívatelia",
	"Statistics"	=> "Štatistika",
	"Randompage"	=> "Náhodný článok",

	"Lonelypages"	=> "Nezaradené články",
	"Unusedimages"	=> "Nezaradené obrázky",
	"Popularpages"	=> "Obľúbené články",
	"Wantedpages"	=> "Najčítanejšie články",
	"Shortpages"	=> "Krátke články",
	"Longpages"		=> "Dlhé články",
	"Newpages"		=> "Nové články",
#	"Intl"                => "Interlanguage Links",
	"Allpages"		=> "Všetky články podľa nadpisu",

	"Ipblocklist"	=> "Zablokované IP adresy",
	"Maintenance" => "Údržba",
	"Specialpages"  => "",
	"Contributions" => "",
	"Emailuser"		=> "",
	"Whatlinkshere" => "",
	"Recentchangeslinked" => "",
	"Movepage"		=> "",
	"Booksources"	=> "Eksterné stránky s knihami",
#	"Categories"	=> "Page categories",
	"Export"		=> "Export XML",
	"Version"		=> "Version",
);

/* private */ $wgSysopSpecialPagesSk = array(
	"Blockip"		=> "Zablokuj IP adresu",
	"Asksql"		=> "Dotaz do databázy",
	"Undelete"		=> "Zobraz a obnov vymazané stránky"
);

/* private */ $wgDeveloperSpecialPagesSk = array(
	"Lockdb"		=> "Zamkni databázu na zápis",
	"Unlockdb"		=> "Odomkni databázu na zápis",
);

/* private */ $wgAllMessagesSk = array(
'special_version_prefix' => '',
'special_version_postfix' => '',

# User Toggles

"tog-hover"		=> "Zobrazuj text nad linkami do wiki",
"tog-underline" => "Podčiarkuj linky",
"tog-highlightbroken" => "Neexistujúce linky zobrazuj červenou.",
"tog-justify"	=> "Zarovnávaj odstavce",
"tog-hideminor" => "V posledných úpravách neukazuj drobné úpravy",
"tog-usenewrc" => "Špeciálne zobrazenie posledných úprav (vyžaduje JavaScript)",
"tog-numberheadings" => "Automaticky čísluj odstavce",
"tog-showtoolbar" => "Show edit toolbar",
"tog-rememberpassword" => "Pamätaj si heslo aj nabudúce",
"tog-editwidth" => "Maximálna šírka editovacieho okna",
"tog-editondblclick" => "Edituj stránky po dvojkliku (JavaScript)",
"tog-watchdefault" => "Upozorňuj na nové a novu upravené stránky",
"tog-minordefault" => "Označ všetky zmeny ako drobné",
"tog-previewontop" => "Zobrazuj ukážku pred editovacím oknom, a nie až za ním",

# Dates
#

'sunday' => 'Nedeľa',
'monday' => 'Pondelok',
'tuesday' => 'Utorok',
'wednesday' => 'Streda',
'thursday' => "Štvrtok",
'friday' => 'Piatok',
'saturday' => 'Sobota',
'january' => 'Január',
'february' => 'Február',
'march' => 'Marec',
'april' => 'Apríl',
'may_long' => 'Máj',
'june' => 'Jún',
'july' => 'Júl',
'august' => 'August',
'september' => 'September',
'october' => 'Október',
'november' => 'November',
'december' => 'December',
'jan' => 'Jan',
'feb' => 'Feb',
'mar' => 'Mar',
'apr' => 'Apr',
'may' => 'Máj',
'jun' => 'Jún',
'jul' => 'Júl',
'aug' => 'Aug',
'sep' => 'Sep',
'oct' => 'Okt',
'nov' => 'Nov',
'dec' => 'Dec',

# Bits of text used by many pages:
#
"linktrail"		=> "/^([a-z]+)(.*)\$/sD",
"mainpage"		=> "Hlavná stránka",
"about"			=> "Úvod",
"aboutwikipedia" => "O Wikipédii",
"aboutpage"		=> "Wikipédia:Úvod",
"help"			=> "Pomoc",
"helppage"		=> "Wikipédia:Pomoc",
"wikititlesuffix" => "Wikipédia",
"bugreports"	=> "Známe_chyby",
"bugreportspage" => "Wikipédia:Známe_chyby",
"faq"			=> "FAQ",
"faqpage"		=> "Wikipédia:FAQ",
"edithelp"		=> "Informácie pre redaktorov",
"edithelppage"	=> "Wikipédia:Ako_editovať_stránku",
"cancel"		=> "Storno",
"qbfind"		=> "Nájdi",
"qbbrowse"		=> "Listuj",
"qbedit"		=> "Edituj",
"qbpageoptions" => "Možnosti stránky",
"qbpageinfo"	=> "Informácie o stránke",
"qbmyoptions"	=> "Moje nastavenia",
"mypage"		=> "Moja stránka",
"mytalk"		=> "Moje komentáre",
"currentevents" => "Aktuality",
"errorpagetitle" => "Chyba",
"returnto"		=> "Späť na $1.",
"fromwikipedia"	=> "Z Wikipédie, slobodnej encyklopédie.",
"whatlinkshere"	=> "Sem ukazujú stránky",
"help"			=> "Pomoc",
"search"		=> "Hľadaj",
"go"		=> "Choď",
"history"		=> "Staršie verzie",
"printableversion" => "Veria na tlač",
"editthispage"	=> "Edituj stránku",
"deletethispage" => "Vymaž stránku",
"protectthispage" => "Zamkni stránku",
"unprotectthispage" => "Odomkni stránku",
"newpage" => "Nová stránka",
"talkpage"		=> "Komentuj stránku",
"articlepage"	=> "Zobraz článok",
"subjectpage"	=> "Zobraz tému", # For compatibility
"userpage" => "Zobraz užívateľovu stránku",
"wikipediapage" => "Zobraz metastránku",
"imagepage" => 	"Zobraz stránku s obrázkom",
"viewtalkpage" => "Zobraz komentáre",
"otherlanguages" => "Iné jazyky",
"redirectedfrom" => "(Presmerované z $1)",
"lastmodified"	=> "Posledné úpravy $1.",
"viewcount"		=> "Táto stránka bola zobrazená $1-krát.",
"gnunote" => "Celý text je dostupný pod podmienkami <a class=internal href='/wiki/GNU_FDL'>GNU Free Documentation License</a>.",
"printsubtitle" => "(Zdroj: http://www.wikipedia.org)",
"protectedpage" => "Zamknutá stránka",
"administrators" => "Wikipédia:Správcovia",
"sysoptitle"	=> "Potrebné oprávnenie: sysop",
"sysoptext"		=> "Požadovanú akciu môžu vykonať iba užívatelia s oprávnením \"sysop\".
Viď $1.",
"developertitle" => "Potrebné oprávnenie: vývojár",
"developertext"	=> "Požadovanú akciu môžu vykonať iba užívatelia s oprávnením \"developer\".
Viď $1.",
"nbytes"		=> "$1 bajtov",
"go"			=> "Choď",
"ok"			=> "OK",
"sitetitle"		=> "Wikipédia",
"sitesubtitle"	=> "The Free Encyclopedia",
"retrievedfrom" => "Zdroj: \"$1\"",
"newmessages" => "Máš $1.",
"newmessageslink" => "nových správ",


# Main script and global functions
#
"nosuchaction"	=> "Neznáma akcia",
"nosuchactiontext" => "Softvér Wikipédie nepozná akciu, ktorú vyžadujete URL",
"nosuchspecialpage" => "Neznáma špeciálna stránka",
"nospecialpagetext" => "Softvér Wikipédie nepozná takúto špeciálnu stránku.",

# General errors
#
"error"			=> "Chyba",
"databaseerror" => "Chyba v databáze",
"dberrortext"	=> "Nastala syntaktická chyba v dotaze do databázy.
Znamená to chybnú dotaz (viď $5),
alebo chybu v softvéri.
Posledný pokus o dotaz bol:
<blockquote><tt>$1</tt></blockquote>
z funkcie \"<tt>$2</tt>\".
MySQL vrátil chybu \"<tt>$3: $4</tt>\".",
"noconnect"		=> "Neviem sa pripojiť k databáze na $1",
"nodb"			=> "Neviem otvoriť databázu $1",
"readonly"		=> "Databáza je zamknutá",
"enterlockreason" => "Zadajte dôvod zamknutia vrátane odhadu, kedy očakávate odomknutie",
"readonlytext"	=> "Databáza Wikipédie je momentálne zamknutá, nové články a úpravy sú zablokované, pravdepodobne kvôli údržbe databázy. Po skončení tejto údržby bude Wikipédia opäť fungovať normálne.
Správca, ktorý nariadil uazmknutie, uvádza tento dôvod:
<p>$1",
"missingarticle" => "Databáza nenašla text stránky, ktorú by mala nájsť, menovite \"$1\".
Toto najskôr nie je chyba v databáze, ale v softvéri.
Prosím ohláste túto chybu správcovi, uveďte aj linku (URL).",
"internalerror" => "Vnútorná chyba",
"filecopyerror" => "Neviem skopírovať súbor \"$1\" na \"$2\".",
"filerenameerror" => "Neviem premenovať súbor \"$1\" na \"$2\".",
"filedeleteerror" => "Neviem vymazať súbor \"$1\".",
"filenotfound"	=> "Neviem nájsť súbor \"$1\".",
"unexpected"	=> "Nečakaná hodnota: \"$1\"=\"$2\".",
"formerror"		=> "Chyba: neviem odoslať formulár",
"badarticleerror" => "Na tejto stránke túto akciu vykonať nemožno.",
"cannotdelete"	=> "Neviem vymazať danú stránku alebo obrázok. (Možno to už vymazal niekto iný.)",
"badtitle"		=> "Zlý nadpis",
"badtitletext"	=> "Požadovaný nadpis bol neplatný, nezadaný, alebo nesprávne linkovaný.",
"perfdisabled" => "Prepáčte! Táto funkcia je počas špičky dočasne vypnutá kvôli veľkej záťaži; prosím vráťte sa medzi 02:00 a 14:00 UTC.",

# Login and logout pages
#
"logouttitle"	=> "Odhlásiť užívateľa",
"logouttext"	=> "Práve ste sa odhlásili.
Môžete naďalej používať Wikipédiu anonymne,
alebo sa môžete opäť prihlásiť pod rovnakým alebo odlišným užívateľským menom.\n",

"welcomecreation" => "<h2>Vitaj, $1!</h2><p>Vaše konto je vytvorené.
Nezabudnite si nastaviť užívateľské nastavenia.",

"loginpagetitle" => "Prihlásiť užívateľa",
"yourname"		=> "Vaše užívateľské meno",
"yourpassword"	=> "Vaše heslo",
"yourpasswordagain" => "Zopakujte heslo",
"newusersonly"	=> " (iba noví užívatelia)",
"remembermypassword" => "Pamätať si heslo aj po vypnutí počítača.",
"loginproblem"	=> "<b>Nastal problém pri prihlasovaní.</b><br>Skúste znova!",
"alreadyloggedin" => "<font color=red><b>Užívateľ $1, vy už ste prihlásený!</b></font><br>\n",

"login"			=> "Log in",
"userlogin"		=> "Log in",
"logout"		=> "Log out",
"userlogout"	=> "Log out",
"createaccount"	=> "Create new account",
"badretype"		=> "The passwords you entered do not match.",
"userexists"	=> "The user name you entered is already in use. Please choosea different name.",
"youremail"		=> "Your e-mail",
"yournick"		=> "Your nickname (for signatures)",
"emailforlost"	=> "If you forget your password, you can have a new one mailed to your e-mail address.",
"loginerror"	=> "Login error",
"noname"		=> "You have not specified a valid user name.",
"loginsuccesstitle" => "Login successful",
"loginsuccess"	=> "You are now logged in to Wikipedia as \"$1\".",
"nosuchuser"	=> "There is no user by the name \"$1\".
Check your spelling, or use the form below to create a new user account.",
"wrongpassword"	=> "The password you entered is incorrect. Please try again.",
"mailmypassword" => "Mail me a new password",
"passwordremindertitle" => "Password reminder from Wikipedia",
"passwordremindertext" => "Someone (probably you, from IP address $1)
requested that we send you a new Wikipedia login password.
The password for user \"$2\" is now \"$3\".
You should log in and change your password now.",
"noemail"		=> "There is no e-mail address recorded for user \"$1\".",
"passwordsent"	=> "A new password has been sent to the e-mail address
registered for \"$1\".
Please log in again after you receive it.",

# Edit pages
#
"summary"		=> "Summary",
"minoredit"		=> "This is a minor edit",
"watchthis"		=> "Watch this article",
"savearticle"	=> "Save page",
"preview"		=> "Preview",
"showpreview"	=> "Show preview",
"blockedtitle"	=> "User is blocked",
"blockedtext"	=> "Your user name or IP address has been blocked by $1.
The reason given is this:<br>''$2''<p>You may contact $1 or one of the other
[[Wikipedia:administrators|administrators]] to discuss the block.",
"newarticle"	=> "(New)",
"newarticletext" =>
"You've followed a link to a page that doesn't exist yet.
To create the page, start typing in the box below
(see the [[Wikipedia:Help|help page]] for more info).
If you are here by mistake, just click your browser's '''back''' button.",
"anontalkpagetext" => "---- ''This is the discussion page for an anonymous user who has not created an account yet or who does not use it. We therefore have to use the numerical [[IP address]] to identify him/her. Such an IP address can be shared by several users. If you are an anonymous user and feel that irrelevant comments have been directed at you, please [[Special:Userlogin|create an account or log in]] to avoid future confusion with other anonymous users.'' ",
"noarticletext" => "(There is currently no text in this page)",
"updated"		=> "(Updated)",
"note"			=> "<strong>Note:</strong> ",
"previewnote"	=> "Remember that this is only a preview, and has not yet been saved!",
"previewconflict" => "This preview reflects the text in the upper
text editing area as it will appear if you choose to save.",
"editing"		=> "Editing $1",
"editconflict"	=> "Edit conflict: $1",
"explainconflict" => "Someone else has changed this page since you
started editing it.
The upper text area contains the page text as it currently exists.
Your changes are shown in the lower text area.
You will have to merge your changes into the existing text.
<b>Only</b> the text in the upper text area will be saved when you
press \"Save page\".\n<p>",
"yourtext"		=> "Your text",
"storedversion" => "Stored version",
"editingold"	=> "<strong>WARNING: You are editing an out-of-date
revision of this page.
If you save it, any changes made since this revision will be lost.</strong>\n",
"yourdiff"		=> "Differences",
"copyrightwarning" => "Please note that all contributions to Wikipedia are
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
"searchhelppage" => "Wikipedia:Searching",
"searchingwikipedia" => "Searching Wikipedia",
"searchresulttext" => "For more information about searching Wikipedia, see $1.",
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
"nogomatch" => "No page with this exact title exists, trying full text search. ",
"titlematches"	=> "Article title matches",
"notitlematches" => "No article title matches",
"textmatches"	=> "Article text matches",
"notextmatches"	=> "No article text matches",
"prevn"			=> "previous $1",
"nextn"			=> "next $1",
"viewprevnext"	=> "View ($1) ($2) ($3).",
"showingresults" => "Showing below <b>$1</b> results starting with #<b>$2</b>.",
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
Your internal ID number is $2.",
"prefsreset"	=> "Preferences have been reset from storage.",
"qbsettings"	=> "Quickbar settings",
"changepassword" => "Change password",
"skin"			=> "Skin",
"math"			=> "Rendering math",
"math_failure"		=> "Failed to parse",
"math_unknown_error"	=> "unknown error",
"math_unknown_function"	=> "unknown function ",
"math_lexing_error"	=> "lexing error",
"math_syntax_error"	=> "syntax error",
"saveprefs"		=> "Save preferences",
"resetprefs"	=> "Reset preferences",
"oldpassword"	=> "Old password",
"newpassword"	=> "New password",
"retypenew"		=> "Retype new password",
"textboxsize"	=> "Editing",
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
"localtime"	=> "Local time",
"timezoneoffset" => "Offset",
"emailflag"		=> "Disable e-mail from other users",

# Recent changes
#
"changes" => "changes",
"recentchanges" => "Recent changes",
"recentchangestext" => "Track the most recent changes to Wikipedia on this page.
[[Wikipedia:Welcome,_newcomers|Welcome, newcomers]]!
Please have a look at these pages: [[wikipedia:FAQ|Wikipedia FAQ]],
[[Wikipedia:Policies and guidelines|Wikipedia policy]]
(especially [[wikipedia:Naming conventions|naming conventions]],
[[wikipedia:Neutral point of view|neutral point of view]]),
and [[wikipedia:Most common Wikipedia faux pas|most common Wikipedia faux pas]].

If you want to see Wikipedia succeed, it's very important that you don't add
material restricted by others' [[wikipedia:Copyrights|copyrights]].
The legal liability could really hurt the project, so please don't do it.
See also the [http://meta.wikipedia.org/wiki/Special:Recentchanges recent meta discussion].",
"rcloaderr"		=> "Loading recent changes",
"rcnote"		=> "Below are the last <strong>$1</strong> changes in last <strong>$2</strong> days.",
"rcnotefrom"	=> "Below are the changes since <b>$2</b> (up to <b>$1</b> shown).",
"rclistfrom"	=> "Show new changes starting from $1",
# "rclinks"		=> "Show last $1 changes in last $2 hours / last $3 days",
"rclinks"		=> "Show last $1 changes in last $2 days.",
"rchide"		=> "in $4 form; $1 minor edits; $2 secondary namespaces; $3 multiple edits.",
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
"uploadfile"	=> "Upload file",
"uploaderror"	=> "Upload error",
"uploadlog"		=> "upload log",
"uploadlogpage" => "Upload_log",
"uploadlogpagetext" => "Below is a list of the most recent file uploads.
All times shown are server time (UTC).
<ul>
</ul>
",
"filename"		=> "Filename",
"filedesc"		=> "Summary",
"affirmation"	=> "I affirm that the copyright holder of this file
agrees to license it under the terms of the $1.",
"copyrightpage" => "Wikipedia:Copyrights",
"copyrightpagename" => "Wikipedia copyright",
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
"deleteimgcompletely"		=> "del",
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
This includes \"talk\" pages, pages about Wikipedia, minimal \"stub\"
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
"maintnancepagetext"	=> "This page includes several handy tools for everyday maintenance. Some of these functions tend to stress the database, so please do not hit reload after every item you fixed ;-)",
"maintenancebacklink"	=> "Back to Maintenance Page",
"disambiguations"	=> "Disambiguation pages",
"disambiguationspage"	=> "Wikipedia:Links_to_disambiguating_pages",
"disambiguationstext"	=> "The following articles link to a <i>disambiguation page</i>. They should link to the appropriate topic instead.<br>A page is treated as dismbiguation if it is linked from $1.<br>Links from other namespaces are <i>not</i> listed here.",
"doubleredirects"	=> "Double Redirects",
"doubleredirectstext"	=> "<b>Attention:</b> This list may contain false positives. That usually means there is additional text with links below the first #REDIRECT.<br>\nEach row contains links to the first and second redirect, as well as the first line of the second redirect text, usually giving the \"real\" taget article, which the first redirect should point to.",
"brokenredirects"	=> "Broken Redirects",
"brokenredirectstext"	=> "The following redirects link to a non-existing article.",
"selflinks"		=> "Pages with Self Links",
"selflinkstext"		=> "The following pages contain a link to themselves, which they should not.",
"mispeelings"           => "Pages with misspellings",
"mispeelingstext"               => "The following pages contain a common misspelling, which are listed on $1. The correct spelling might be given (like this).",
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
"listusers"		=> "User list",
"specialpages"	=> "Special pages",
"spheading"		=> "Special pages",
"sysopspheading" => "Special pages for sysop use",
"developerspheading" => "Special pages for developer use",
"protectpage"	=> "Protect page",
"recentchangeslinked" => "Related changes",
"rclsub"		=> "(to pages linked from \"$1\")",
"debug"			=> "Debug",
"newpages"		=> "New pages",
"movethispage"	=> "Move this page",
"unusedimagestext" => "<p>Please note that other web sites
such as the international Wikipedias may link to an image with
a direct URL, and so may still be listed here despite being
in active use.",
"booksources"	=> "Book sources",
"booksourcetext" => "Below is a list of links to other sites that
sell new and used books, and may also have further information
about books you are looking for.
Wikipedia is not affiliated with any of these businesses, and
this list should not be construed as an endorsement.",

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
"watchlist"		=> "My watchlist",
"watchlistsub"	=> "(for user \"$1\")",
"nowatchlist"	=> "You have no items on your watchlist.",
"watchnologin"	=> "Not logged in",
"watchnologintext"	=> "You must be <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">logged in</a>
to modify your watchlist.",
"addedwatch"	=> "Added to watchlist",
"addedwatchtext" => "The page \"$1\" has been added to your <a href=\"" .
  wfLocalUrl( "Special:Watchlist" ) . "\">watchlist</a>.
Future changes to this page and its associated Talk page will be listed there,
and the page will appear <b>bolded</b> in the <a href=\"" .
  wfLocalUrl( "Special:Recentchanges" ) . "\">list of recent changes</a> to
make it easier to pick out.</p>

<p>If you want to remove the page from your watchlist later, click \"Stop watching\" in the sidebar.",
"removedwatch"	=> "Removed from watchlist",
"removedwatchtext" => "The page \"$1\" has been removed from your watchlist.",
"watchthispage"	=> "Watch this page",
"unwatchthispage" => "Stop watching",
"notanarticle"	=> "Not an article",

# Delete/protect/revert
#
"deletepage"	=> "Delete page",
"confirm"		=> "Confirm",
"confirmdelete" => "Confirm delete",
"deletesub"		=> "(Deleting \"$1\")",
"confirmdeletetext" => "You are about to permanently delete a page
or image along with all of its history from the database.
Please confirm that you intend to do this, that you understand the
consequences, and that you are doing this in accordance with
[[Wikipedia:Policy]].",
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
"cantrollback"	=> "Can't revert edit; last contributor is only author of this article.",
"revertpage"	=> "Reverted to last edit by $1",

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
See [[Wikipedia:Deletion_log]] for a record of recent deletions and restorations.",

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
"blockip"		=> "Block IP address",
"blockiptext"	=> "Use the form below to block write access
from a specific IP address.
This should be done only only to prevent vandalism, and in
accordance with [[Wikipedia:Policy|Wikipedia policy]].
Fill in a specific reason below (for example, citing particular
pages that were vandalized).",
"ipaddress"		=> "IP Address",
"ipbreason"		=> "Reason",
"ipbsubmit"		=> "Block this address",
"badipaddress"	=> "The IP address is badly formed.",
"noblockreason" => "You must supply a reason for the block.",
"blockipsuccesssub" => "Block succeeded",
"blockipsuccesstext" => "The IP address \"$1\" has been blocked.
<br>See [[Special:Ipblocklist|IP block list]] to review blocks.",
"unblockip"		=> "Unblock IP address",
"unblockiptext"	=> "Use the form below to restore write access
to a previously blocked IP address.",
"ipusubmit"		=> "Unblock this address",
"ipusuccess"	=> "IP address \"$1\" unblocked",
"ipblocklist"	=> "List of blocked IP addresses",
"blocklistline"	=> "$1, $2 blocked $3",
"blocklink"		=> "block",
"unblocklink"	=> "unblock",
"contribslink"	=> "contribs",

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
"lockdbsuccesstext" => "The Wikipedia database has been locked.
<br>Remember to remove the lock after your maintenance is complete.",
"unlockdbsuccesstext" => "The Wikipedia database has been unlocked.",

# SQL query
#
"asksql"		=> "SQL query",
"asksqltext"	=> "Use the form below to make a direct query of the
Wikipedia database.
Use single quotes ('like this') to delimit string literals.
This can often add considerable load to the server, so please use
this function sparingly.",
"sqlquery"		=> "Enter query",
"querybtn"		=> "Submit query",
"selectonly"	=> "Queries other than \"SELECT\" are restricted to
Wikipedia developers.",
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
# Math
	'mw_math_png' => "Vždy vytvor PNG",
	'mw_math_simple' => "Na jednoduché použi HTML, inak PNG",
	'mw_math_html' => "Ak sa dá, použi HTML, inak PNG",
	'mw_math_source' => "Ponechaj TeX (pre textové prehliadače)",
	'mw_math_modern' => "Odporúčame pre moderné prehliadače",
	'mw_math_mathml' => 'MathML',

);

class LanguageSk extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesSk;
		return $wgNamespaceNamesSk;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesSk;
		return $wgNamespaceNamesSk[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesSk;

		foreach ( $wgNamespaceNamesSk as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		if( 0 == strcasecmp( "Special", $text ) ) return -1;
		if( 0 == strcasecmp( "Wikipedia", $text ) ) return 4;
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsSk;
		return $wgQuickbarSettingsSk;
	}

	function getSkinNames() {
		global $wgSkinNamesSk;
		return $wgSkinNamesSk;
	}

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesSk;
		return $wgValidSpecialPagesSk;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesSk;
		return $wgSysopSpecialPagesSk;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesSk;
		return $wgDeveloperSpecialPagesSk;
	}

	function getMessage( $key )
	{
		global $wgAllMessagesSk;
		if($wgAllMessagesSk[$key])
			return $wgAllMessagesSk[$key];
		return Language::getMessage( $key );
	}

	function fallback8bitEncoding() {
		return "iso-8859-2"; #?
	}

}
?>

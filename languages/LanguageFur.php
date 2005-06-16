<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

// The names of the namespaces can be set here, but the numbers
// are magical, so don't change or move them!  The Namespace class
// encapsulates some of the magic-ness.
require_once( 'LanguageUtf8.php' );

/* private */ $wgNamespaceNamesFur = array(
	NS_MEDIA			=> 'Media',
	NS_SPECIAL			=> 'Speciâl',
	NS_MAIN				=> '',
	NS_TALK				=> 'Discussion',
	NS_USER				=> 'Utent',
	NS_USER_TALK		=> 'Discussion_utent',        
	NS_PROJECT			=> $wgMetaNamespace,
	NS_PROJECT_TALK		=> 'Discussion_'.$wgMetaNamespace,
	NS_IMAGE			=> 'Figure',
	NS_IMAGE_TALK		=> 'Discussion_figure',
	NS_MEDIAWIKI		=> 'MediaWiki',
	NS_MEDIAWIKI_TALK	=> 'Discussion_MediaWiki',
	NS_TEMPLATE			=> 'Model',
	NS_TEMPLATE_TALK	=> 'Discussion_model',
	NS_HELP				=> 'Jutori',
	NS_HELP_TALK		=> 'Discussion_jutori',
	NS_CATEGORY			=> 'Categorie',
	NS_CATEGORY_TALK	=> 'Discussion_categorie'
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsFur = array(
	'Nissune', 'Fis a Çampe', 'Fis a Drete', 'Flutant a çampe'
);

/* private */ $wgSkinNamesFur = array(
	'standard'		=> 'Standard',
	'nostalgia'		=> 'Nostalgie',
	'cologneblue'	=> 'Cologne Blue',
	'smarty'		=> 'Paddington',
	'montparnasse'	=> 'Montparnasse',
	'davinci'		=> 'DaVinci',
	'mono'			=> 'Mono',
	'monobook'		=> 'MonoBook',
	'myskin'		=> 'MySkin'
);



/* private */ $wgBookstoreListFur = array(
	
);


// All special pages have to be listed here: a description of ""
// will make them not show up on the "Special Pages" page, which
// is the right thing for some of them (such as the "targeted" ones).

/* private */ $wgValidSpecialPagesFur = array(
	'Userlogin'     => '',
	'Userlogout'    => '',
	'Preferences'   => 'Preferencis',
	'Watchlist'     => 'Tignûts di voli',
	'Recentchanges' => 'Ultins cambiaments',
	'Upload'        => 'Cjame sù un file',
	'Imagelist'     => 'Liste des figuris',
	'Listusers'     => 'Liste dai utents',
	'Statistics'    => 'Statistichis',
	'Randompage'    => 'Une pagjine a câs',

	'Lonelypages'   => 'Pagjinis solitaris',
	'Unusedimages'  => 'Figuris no dopradis',
	'Popularpages'  => 'Lis plui popolârs',
	'Wantedpages'   => 'Lis plui desideradis',
	'Shortpages'    => 'Articui curts',
	'Longpages'     => 'Articui luncs',
	'Newpages'      => 'Pagjinis gnovis',
	'Ancientpages'	=> 'Pagjinis vieris',
	'Allpages'      => 'Ducj i articui',

	'Ipblocklist'   => 'Recapits IP blocâts',
	'Maintenance'   => 'Pagjine di manutenzion',
	'Specialpages'  => '', // ces pages doivent rester vides !
	'Contributions' => '',
	'Emailuser'     => '',
	'Whatlinkshere' => '',
	'Recentchangeslinked' => '',
	'Movepage'      => '',
	'Booksources'   => 'Libreriis in linee',
	'Categories'	=> 'Pagjine des categoriis',
	'Export'	=> 'Espuartâ in XML',
	'Version'	=> 'Version',
	'Allmessages'	=> 'Ducj i messaç di sistem'
);

/* private */ $wgSysopSpecialPagesFur = array(
	'Blockip'       => 'Bloche un recapit IP',
	'Asksql'        => 'Acès SQL',
	'Makesysop'		=> 'Dâ i dirits di aministradôr',
                                               
	'Undelete'      => 'Recupere lis pagjinis eliminadis',
	'Import'		=> 'Impuarte une pagjine cul storic'
);

/* private */ $wgDeveloperSpecialPagesFur = array(
	'Lockdb'        => 'Bloche la base di dâts',
	'Unlockdb'      => 'Gjave il bloc ae base di dâts',
);

$wgAllMessagesFur = array(
#'1movedto2' => "$1 moved to $2",
#'1movedto2_redir' => "$1 moved to $2 over redirect",
#'Monobook.css' => "/* edit this file to customize the monobook skin for the entire site */",
/* 'Monobook.js' => "
ta = new Object();
ta['pt-userpage'] = new Array('.','My user page');
ta['pt-anonuserpage'] = new Array('.','The user page for the ip you\'re editing as');
ta['pt-mytalk'] = new Array('n','My talk page');
ta['pt-anontalk'] = new Array('n','Discussion about edits from this ip address');
ta['pt-preferences'] = new Array('','My preferences');
ta['pt-watchlist'] = new Array('l','The list of pages you\'re monitoring for changes.');
ta['pt-mycontris'] = new Array('y','List of my contributions');
ta['pt-login'] = new Array('o','You are encouraged to log in, it is not mandatory however.');
ta['pt-anonlogin'] = new Array('o','You are encouraged to log in, it is not mandatory however.');
ta['pt-logout'] = new Array('o','Log out');
ta['ca-talk'] = new Array('t','Discussion about the content page');
ta['ca-edit'] = new Array('e','You can edit this page. Please use the preview button before saving.');
ta['ca-addsection'] = new Array('+','Add a comment to this discussion.');
ta['ca-viewsource'] = new Array('e','This page is protected. You can view its source.');
ta['ca-history'] = new Array('h','Past versions of this page.');
ta['ca-protect'] = new Array('=','Protect this page');
ta['ca-delete'] = new Array('d','Delete this page');
ta['ca-undelete'] = new Array('d','Restore the edits done to this page before it was deleted');
ta['ca-move'] = new Array('m','Move this page');
ta['ca-nomove'] = new Array('','You don\'t have the permissions to move this page');
ta['ca-watch'] = new Array('w','Add this page to your watchlist');
ta['ca-unwatch'] = new Array('w','Remove this page from your watchlist');
ta['search'] = new Array('f','Search this wiki');
ta['p-logo'] = new Array('','Main Page');
ta['n-mainpage'] = new Array('z','Visit the Main Page');
ta['n-portal'] = new Array('','About the project, what you can do, where to find things');
ta['n-currentevents'] = new Array('','Find background information on current events');
ta['n-recentchanges'] = new Array('r','The list of recent changes in the wiki.');
ta['n-randompage'] = new Array('x','Load a random page');
ta['n-help'] = new Array('','The place to find out.');
ta['n-sitesupport'] = new Array('','Support us');
ta['t-whatlinkshere'] = new Array('j','List of all wiki pages that link here');
ta['t-recentchangeslinked'] = new Array('k','Recent changes in pages linked from this page');
ta['feed-rss'] = new Array('','RSS feed for this page');
ta['feed-atom'] = new Array('','Atom feed for this page');
ta['t-contributions'] = new Array('','View the list of contributions of this user');
ta['t-emailuser'] = new Array('','Send a mail to this user');
ta['t-upload'] = new Array('u','Upload images or media files');
ta['t-specialpages'] = new Array('q','List of all special pages');
ta['ca-nstab-main'] = new Array('c','View the content page');
ta['ca-nstab-user'] = new Array('c','View the user page');
ta['ca-nstab-media'] = new Array('c','View the media page');
ta['ca-nstab-special'] = new Array('','This is a special page, you can\'t edit the page itself.');
ta['ca-nstab-wp'] = new Array('a','View the project page');
ta['ca-nstab-image'] = new Array('c','View the image page');
ta['ca-nstab-mediawiki'] = new Array('c','View the system message');
ta['ca-nstab-template'] = new Array('c','View the template');
ta['ca-nstab-help'] = new Array('c','View the help page');
ta['ca-nstab-category'] = new Array('c','View the category page');
", */
'about' => "Informazions",
#'aboutpage' => "Project:About",
'aboutsite' => "Informazions su la {{SITENAME}}",
#'accesskey-compareselectedversions' => "v",
#'accesskey-minoredit' => "i",
#'accesskey-preview' => "p",
#'accesskey-save' => "s",
#'accesskey-search' => "f",
#'accmailtext' => "The Password for '$1' has been sent to $2.",
#'accmailtitle' => "Password sent.",
#'acct_creation_throttle_hit' => "Sorry, you have already created $1 accounts. You can't make any more.",
#'actioncomplete' => "Action complete",
#'addedwatch' => "Added to watchlist",
/* 'addedwatchtext' => "The page \"$1\" has been added to your [[Special:Watchlist|watchlist]].
Future changes to this page and its associated Talk page will be listed there,
and the page will appear '''bolded''' in the [[Special:Recentchanges|list of recent changes]] to
make it easier to pick out.

<p>If you want to remove the page from your watchlist later, click \"Stop watching\" in the sidebar.", */
#'addgroup' => "Add Group",
#'addsection' => "+",
#'administrators' => "Project:Administrators",
/* 'affirmation' => "I affirm that the copyright holder of this file
agrees to license it under the terms of the $1.", */
#'all' => "all",
'allarticles' => "Ducj i articui",
/* 'alllogstext' => "Combined display of upload, deletion, protection, blocking, and sysop logs.
You can narrow down the view by selecting a log type, the user name, or the affected page.", */
'allmessages' => "Ducj i messaç di sistem",
'allmessagescurrent' => "Test curint",
'allmessagesdefault' => "Test predeterminât",
'allmessagesname' => "Non",
#'allmessagesnotsupportedDB' => "Special:AllMessages not supported because wgUseDatabaseMessages is off.",
#'allmessagesnotsupportedUI' => "Your current interface language <b>$1</b> is not supported by Special:AllMessages at this site. ",
#'allmessagestext' => "This is a list of all system messages available in the MediaWiki: namespace.",
'allpages' => "Dutis lis pagjinis",
#'allpagesformtext1' => "Display pages starting at: $1",
#'allpagesformtext2' => "Choose namespace: $1 $2",
#'allpagesnamespace' => "All pages ($1 namespace)",
#'allpagesnext' => "Next",
#'allpagesprev' => "Previous",
'allpagessubmit' => "Va",
#'alphaindexline' => "$1 to $2",
/* 'alreadyloggedin' => "<font color=red><b>User $1, you are already logged in!</b></font><br />
", */
/* 'alreadyrolled' => "Cannot rollback last edit of [[$1]]
by [[User:$2|$2]] ([[User talk:$2|Talk]]); someone else has edited or rolled back the page already.

Last edit was by [[User:$3|$3]] ([[User talk:$3|Talk]]). ", */
#'ancientpages' => "Oldest pages",
#'and' => "and",
#'anontalk' => "Talk for this IP",
#'anontalkpagetext' => "----''This is the discussion page for an anonymous user who has not created an account yet or who does not use it. We therefore have to use the numerical [[IP address]] to identify him/her. Such an IP address can be shared by several users. If you are an anonymous user and feel that irrelevant comments have been directed at you, please [[Special:Userlogin|create an account or log in]] to avoid future confusion with other anonymous users.'' ",
#'anonymous' => "Anonymous user(s) of Wikipedia",
'apr' => "Avr",
'april' => "Avrîl",
#'article' => "Content page",
/* 'articleexists' => "A page of that name already exists, or the
name you have chosen is not valid.
Please choose another name.", */
'articlenamespace' => "(articui)",
#'articlepage' => "View content page",
#'asksql' => "SQL query",
#'asksqlpheading' => "asksql level",
/* 'asksqltext' => "Use the form below to make a direct query of the
database.
Use single quotes ('like this') to delimit string literals.
This can often add considerable load to the server, so please use
this function sparingly.", */
'aug' => "Avo",
'august' => "Avost",
#'autoblocker' => "Autoblocked because you share an IP address with \"$1\". Reason \"$2\".",
'bad_image_list' => "", #empty
#'badarticleerror' => "This action cannot be performed on this page.",
#'badfilename' => "Image name has been changed to \"$1\".",
#'badfiletype' => "\".$1\" is not a recommended image file format.",
#'badipaddress' => "Invalid IP address",
#'badquery' => "Badly formed search query",
/* 'badquerytext' => "We could not process your query.
This is probably because you have attempted to search for a
word fewer than three letters long, which is not yet supported.
It could also be that you have mistyped the expression, for
example \"fish and and scales\".
Please try another query.", */
#'badretype' => "The passwords you entered do not match.",
#'badtitle' => "Bad title",
/* 'badtitletext' => "The requested page title was invalid, empty, or
an incorrectly linked inter-language or inter-wiki title.", */
#'blanknamespace' => "(Main)",
/* 'block_compress_delete' => "Can't delete this article because it contains block-compressed revisions. 
This is a temporary situation which the developers are well aware of, and should be fixed within a month or two. 
Please mark the article for deletion and wait for a developer to fix our buggy software.", */
/* 'blockedtext' => "Your user name or IP address has been blocked by $1.
The reason given is this:<br />''$2''<p>You may contact $1 or one of the other
[[Project:Administrators|administrators]] to discuss the block.

Note that you may not use the \"email this user\" feature unless you have a valid email address registered in your [[Special:Preferences|user preferences]].

Your IP address is $3. Please include this address in any queries you make.
", */
#'blockedtitle' => "User is blocked",
#'blockip' => "Block user",
#'blockipsuccesssub' => "Block succeeded",
/* 'blockipsuccesstext' => "\"$1\" has been blocked.
<br />See [[Special:Ipblocklist|IP block list]] to review blocks.", */
/* 'blockiptext' => "Use the form below to block write access
from a specific IP address or username.
This should be done only only to prevent vandalism, and in
accordance with [[Project:Policy|policy]].
Fill in a specific reason below (for example, citing particular
pages that were vandalized).", */
'blocklink' => "bloche",
#'blocklistline' => "$1, $2 blocked $3 (expires $4)",
#'blocklogentry' => "blocked \"$1\" with an expiry time of $2",
#'blocklogpage' => "Block_log",
/* 'blocklogtext' => "This is a log of user blocking and unblocking actions. Automatically
blocked IP addresses are not listed. See the [[Special:Ipblocklist|IP block list]] for
the list of currently operational bans and blocks.", */
#'blockpheading' => "block level",
#'bold_sample' => "Bold text",
#'bold_tip' => "Bold text",
#'booksources' => "Book sources",
/* 'booksourcetext' => "Below is a list of links to other sites that
sell new and used books, and may also have further information
about books you are looking for.
{{SITENAME}} is not affiliated with any of these businesses, and
this list should not be construed as an endorsement.", */
#'brokenredirects' => "Broken Redirects",
#'brokenredirectstext' => "The following redirects link to a non-existing pages.",
#'bugreports' => "Bug reports",
#'bugreportspage' => "Project:Bug_reports",
#'bureaucratlog' => "Bureaucrat_log",
#'bureaucratlogentry' => "Rights for user \"$1\" set \"$2\"",
/* 'bureaucrattext' => "The action you have requested can only be
performed by sysops with  \"bureaucrat\" status.", */
#'bureaucrattitle' => "Bureaucrat access required",
#'bydate' => "by date",
#'byname' => "by name",
#'bysize' => "by size",
#'cachederror' => "The following is a cached copy of the requested page, and may not be up to date.",
'cancel' => "Scancele",
#'cannotdelete' => "Could not delete the page or image specified. (It may have already been deleted by someone else.)",
#'cantrollback' => "Cannot revert edit; last contributor is only author of this page.",
'categories' => "Categoriis",
#'categoriespagetext' => "The following categories exist in the wiki.",
'category' => "categorie",
'category_header' => "Articui inte categorie \"$1\"",
'categoryarticlecount' => "In cheste categorie tu puedis cjatâ $1 articui.",
'categoryarticlecount1' => "In cheste categorie tu puedis cjatâ $1 articul.",
#'changepassword' => "Change password",
#'changes' => "changes",
#'clearyourcache' => "'''Note:''' After saving, you have to clear your browser cache to see the changes: '''Mozilla:''' click ''Reload'' (or ''Ctrl-R''), '''IE / Opera:''' ''Ctrl-F5'', '''Safari:''' ''Cmd-R'', '''Konqueror''' ''Ctrl-R''.",
#'columns' => "Columns",
#'compareselectedversions' => "Compare selected versions",
'confirm' => "Conferme",
#'confirmcheck' => "Yes, I really want to delete this.",
'confirmdelete' => "Conferme eliminazion",
/* 'confirmdeletetext' => "You are about to permanently delete a page
or image along with all of its history from the database.
Please confirm that you intend to do this, that you understand the
consequences, and that you are doing this in accordance with
[[Project:Policy]].", */
'confirmprotect' => "Conferme protezion",
'confirmprotecttext' => "Vuelistu pardabon protezi cheste pagjine?",
'confirmunprotect' => "Conferme par gjavâ la protezion",
'confirmunprotecttext' => "Vuelistu pardabon gjavâ la protezion a cheste pagjine?",
#'contextchars' => "Characters of context per line",
#'contextlines' => "Lines to show per hit",
#'contribslink' => "contribs",
#'contribsub' => "For $1",
'contributions' => "Contribûts dal utent",
'copyright' => "Il contignût al è disponibil sot de $1",
#'copyrightpage' => "Project:Copyrights",
#'copyrightpagename' => "{{SITENAME}} copyright",
'copyrightwarning' => "<!-- Perché i link non abbiano l'aspetto di link esterni: -->
<div class=\"plainlinks\">

Frache parsore di un di chescj caratars speciâi par zontâlu tal test:

<charinsert> Â â Ê ê Î î Ô ô Û û </charinsert> &nbsp;
<charinsert> Á á É é Í í Ó ó Ú ú </charinsert> &nbsp;
<charinsert> À à È è Ì ì Ò ò Ù ù </charinsert> &nbsp;
<charinsert> Ä ä Ë ë Ï ï Ö ö Ü ü </charinsert> &nbsp;
<charinsert> ß </charinsert> &nbsp;
<charinsert> Ã ã Ñ ñ Õ õ </charinsert> &nbsp;
<charinsert> Ç ç &#290; &#291; &#310; &#311; &#315; &#316; &#325; &#326; &#342; &#343; &#350; &#351; &#354; &#355; </charinsert> &nbsp;
<charinsert> &#262; &#263; &#313; &#314; &#323; &#324; &#340; &#341; &#346; &#347; Ý ý &#377; &#378; </charinsert> &nbsp;
<charinsert> &#272; &#273; </charinsert> &nbsp;
<charinsert> &#366; &#367; </charinsert> &nbsp;
<charinsert> &#268; &#269; &#270; &#271; &#317; &#318; &#327; &#328; &#344; &#345; &#352; &#353; &#356; &#357; &#381; &#382; </charinsert> &nbsp;
<charinsert> &#461; &#462; &#282; &#283; &#463; &#464; &#465; &#466; &#467; &#468; </charinsert> &nbsp;
<charinsert> &#256; &#257; &#274; &#275; &#298; &#299; &#332; &#333; &#362; &#363; </charinsert> &nbsp;
<charinsert> &#470; &#472; &#474; &#476; </charinsert> &nbsp;
<charinsert> &#264; &#265; &#284; &#285; &#292; &#293; &#308; &#309; &#348; &#349; &#372; &#373; &#374; &#375; </charinsert> &nbsp;
<charinsert> &#258; &#259; &#286; &#287; &#364; &#365; </charinsert> &nbsp;
<charinsert> &#266; &#267; &#278; &#279; &#288; &#289; &#304; &#305; &#379; &#380; </charinsert> &nbsp;
<charinsert> &#260; &#261; &#280; &#281; &#302; &#303; &#370; &#371; </charinsert> &nbsp;
<charinsert> &#321; &#322; </charinsert> &nbsp;
<charinsert> &#336; &#337; &#368; &#369; </charinsert> &nbsp;
<charinsert> &#319; &#320; </charinsert> &nbsp;
<charinsert> &#294; &#295; </charinsert> &nbsp;
<charinsert> Ð ð Þ þ </charinsert> &nbsp;
<charinsert> Œ œ </charinsert> &nbsp;
<charinsert> Æ æ Ø ø Å å </charinsert> &nbsp;
<charinsert> &ndash; &mdash; &hellip; </charinsert> &nbsp;
<charinsert> ~ | ° </charinsert> &nbsp;
<charinsert> ± &minus; × &sup1; ² ³ </charinsert> &nbsp;
<charinsert> &euro; </charinsert> &nbsp;

Selezione il test di meti tal mieç de virgulutis o de parentesis e daspò frache ca sot:
<charinsert> «+» \"+\" '+' [+] [[+]] {{+}}</charinsert>

<div style=\"margin-top:2em\">
<div style=\"font-weight: bold; font-size: 120%;\">I cambiaments che tu âs fat a saran visibii daurman.</div>
* Par plasê, dopre la [[Vichipedie:Sandbox|sandbox]] se tu vuelis fâ cualchi prove.
----
<p style=\"background: red; color: white; font-weight: bold; text-align: center; padding: 2px;\">'''NO STÂ DOPRÂ MATERIÂL CUVIERT DAL DIRIT DI AUTÔR (COPYRIGHT - ©) SE NO TU ÂS UNE AUTORIZAZION ESPLICITE!!!'''</p></div>

* Sta atent, par plasê, che ducj i contribûts ae Vichipedie a son considerâts come dâts fûr sot di une licence GNU Free Documentation License (cjale $1 par altris detais).
* Se no tu vuelis che il to test al puedi jessi gambiât e tornât a jessi distribuît da cualsisei persone cence limits, no stâ mandâlu ae Vichipedie, al è miôr se tu ti fasis un to sît web personâl.
* Inviant chest test, tu stâs garantint che chest al è stât scrit di te in origjin, o che al è stât copiât di une sorzint di public domini, o alc   di simil, opûr che tu âs vût une autorizazion esplicite pe publicazion e  tu puedis dimostrâ chest fat.
</div>

</div>",
/* 'copyrightwarning2' => "Please note that all contributions to {{SITENAME}}
may be edited, altered, or removed by other contributors.
If you don't want your writing to be edited mercilessly, then don't submit it here.<br />
You are also promising us that you wrote this yourself, or copied it from a
public domain or similar free resource (see $1 for details).
<strong>DO NOT SUBMIT COPYRIGHTED WORK WITHOUT PERMISSION!</strong>", */
#'couldntremove' => "Couldn't remove item '$1'...",
'createaccount' => "Cree une gnove identitât",
#'createaccountmail' => "by email",
#'createaccountpheading' => "createaccount level",
#'creditspage' => "Page credits",
#'cur' => "cur",
'currentevents' => "Lis gnovis",
#'currentevents-url' => "Current events",
#'currentrev' => "Current revision",
#'currentrevisionlink' => "view current revision",
#'data' => "Data",
#'databaseerror' => "Database error",
#'dateformat' => "Date format",
/* 'dberrortext' => "A database query syntax error has occurred.
This may indicate a bug in the software.
The last attempted database query was:
<blockquote><tt>$1</tt></blockquote>
from within function \"<tt>$2</tt>\".
MySQL returned error \"<tt>$3: $4</tt>\".", */
/* 'dberrortextcl' => "A database query syntax error has occurred.
The last attempted database query was:
\"$1\"
from within function \"$2\".
MySQL returned error \"$3: $4\".
", */
#'deadendpages' => "Dead-end pages",
#'debug' => "Debug",
'dec' => "Dic",
'december' => "Dicembar",
#'default' => "default",
#'defaultns' => "Search in these namespaces by default:",
#'defemailsubject' => "{{SITENAME}} e-mail",
'delete' => "Elimine",
#'deletecomment' => "Reason for deletion",
#'deletedarticle' => "deleted \"$1\"",
#'deletedrevision' => "Deleted old revision $1.",
/* 'deletedtext' => "\"$1\" has been deleted.
See $2 for a record of recent deletions.", */
#'deleteimg' => "del",
#'deleteimgcompletely' => "Delete all revisions",
#'deletepage' => "Delete page",
#'deletepheading' => "delete level",
#'deletesub' => "(Deleting \"$1\")",
'deletethispage' => "Elimine cheste pagjine",
#'deletionlog' => "deletion log",
#'dellogpage' => "Deletion_log",
#'dellogpagetext' => "Below is a list of the most recent deletions.",
/* 'developertext' => "The action you have requested can only be
performed by users with \"developer\" status.
See $1.", */
#'developertitle' => "Developer access required",
#'diff' => "diff",
#'difference' => "(Difference between revisions)",
#'disambiguations' => "Disambiguation pages",
#'disambiguationspage' => "Project:Links_to_disambiguating_pages",
#'disambiguationstext' => "The following pages link to a <i>disambiguation page</i>. They should link to the appropriate topic instead.<br />A page is treated as disambiguation if it is linked from $1.<br />Links from other namespaces are <i>not</i> listed here.",
#'disclaimerpage' => "Project:General_disclaimer",
#'disclaimers' => "Disclaimers",
#'doubleredirects' => "Double Redirects",
#'doubleredirectstext' => "Each row contains links to the first and second redirect, as well as the first line of the second redirect text, usually giving the \"real\" target page, which the first redirect should point to.",
'edit' => "Modifiche",
#'editcomment' => "The edit comment was: \"<i>$1</i>\".",
#'editconflict' => "Edit conflict: $1",
#'editcurrent' => "Edit the current version of this page",
#'editgroup' => "Edit Group",
#'edithelp' => "Editing help",
#'edithelppage' => "Help:Editing",
'editing' => "Modifiche di $1",
#'editingcomment' => "Editing $1 (comment)",
/* 'editingold' => "<strong>WARNING: You are editing an out-of-date
revision of this page.
If you save it, any changes made since this revision will be lost.</strong>
", */
#'editingsection' => "Editing $1 (section)",
'editsection' => "modifiche",
'editthispage' => "Modifiche cheste pagjine",
#'editusergroup' => "Edit User Groups",
#'emailflag' => "Disable e-mail from other users",
/* 'emailforlost' => "Fields marked with a star (*) are optional.  Storing an email address enables people to contact you through the website without you having to reveal your
email address to them, and it can be used to send you a new password if you forget it.<br /><br />Your real name, if you choose to provide it, will be used for giving you attribution for your work.", */
#'emailfrom' => "From",
#'emailmessage' => "Message",
#'emailpage' => "E-mail user",
/* 'emailpagetext' => "If this user has entered a valid e-mail address in
his or her user preferences, the form below will send a single message.
The e-mail address you entered in your user preferences will appear
as the \"From\" address of the mail, so the recipient will be able
to reply.", */
#'emailsend' => "Send",
#'emailsent' => "E-mail sent",
#'emailsenttext' => "Your e-mail message has been sent.",
#'emailsubject' => "Subject",
#'emailto' => "To",
'emailuser' => "Messaç di pueste a chest utent",
#'emptyfile' => "The file you uploaded seems to be empty. This might be due to a typo in the file name. Please check whether you really want to upload this file.",
/* 'enterlockreason' => "Enter a reason for the lock, including an estimate
of when the lock will be released", */
'error' => "Erôr",
'errorpagetitle' => "Erôr",
#'exbeforeblank' => "content before blanking was:",
#'exblank' => "page was empty",
#'excontent' => "content was:",
/* 'explainconflict' => "Someone else has changed this page since you
started editing it.
The upper text area contains the page text as it currently exists.
Your changes are shown in the lower text area.
You will have to merge your changes into the existing text.
<b>Only</b> the text in the upper text area will be saved when you
press \"Save page\".
<p>", */
#'export' => "Export pages",
#'exportcuronly' => "Include only the current revision, not the full history",
/* 'exporttext' => "You can export the text and editing history of a particular page or
set of pages wrapped in some XML. In the future, this may then be imported into another
wiki running MediaWiki software, although there is no support for this feature in the
current version.

To export article pages, enter the titles in the text box below, one title per line, and
select whether you want the current version as well as all old versions, with the page
history lines, or just the current version with the info about the last edit.

In the latter case you can also use a link, e.g. [[{{ns:Special}}:Export/Train]] for the
article [[Train]].
", */
#'extlink_sample' => "http://www.example.com link title",
#'extlink_tip' => "External link (remember http:// prefix)",
#'faq' => "FAQ",
#'faqpage' => "Project:FAQ",
'feb' => "Fev",
'february' => "Fevrâr",
#'feedlinks' => "Feed:",
#'filecopyerror' => "Could not copy file \"$1\" to \"$2\".",
#'filedeleteerror' => "Could not delete file \"$1\".",
'filedesc' => "Descrizion",
#'fileexists' => "A file with this name exists already, please check $1 if you are not sure if you want to change it.",
#'filemissing' => "File missing",
'filename' => "Non dal file",
#'filenotfound' => "Could not find file \"$1\".",
#'filerenameerror' => "Could not rename file \"$1\" to \"$2\".",
#'filesource' => "Source",
#'filestatus' => "Copyright status",
/* 'fileuploaded' => "File $1 uploaded successfully.
Please follow this link: $2 to the description page and fill
in information about the file, such as where it came from, when it was
created and by whom, and anything else you may know about it. If this is an image, you can insert it like this: <tt><nowiki>[[Image:$1|thumb|Description]]</nowiki></tt>", */
#'formerror' => "Error: could not submit form",
'friday' => "Vinars",
#'geo' => "GEO coordinates",
#'getimagelist' => "fetching image list",
'go' => "Va",
/* 'googlesearch' => "
<div style=\"margin-left: 2em\">

<!-- Google search -->
<div style=\"width:130px;float:left;text-align:center;position:relative;top:-8px\"><a href=\"http://www.google.com/\" style=\"padding:0;background-image:none\"><img src=\"http://www.google.com/logos/Logo_40wht.gif\" alt=\"Google\" style=\"border:none\" /></a></div>

<form method=\"get\" action=\"http://www.google.com/search\" style=\"margin-left:135px\">
  <div>
    <input type=\"hidden\" name=\"domains\" value=\"{{SERVER}}\" />
    <input type=\"hidden\" name=\"num\" value=\"50\" />
    <input type=\"hidden\" name=\"ie\" value=\"$2\" />
    <input type=\"hidden\" name=\"oe\" value=\"$2\" />
    
    <input type=\"text\" name=\"q\" size=\"31\" maxlength=\"255\" value=\"$1\" />
    <input type=\"submit\" name=\"btnG\" value=\"Google Search\" />
  </div>
  <div style=\"font-size:90%\">
    <input type=\"radio\" name=\"sitesearch\" id=\"gwiki\" value=\"{{SERVER}}\" checked=\"checked\" /><label for=\"gwiki\">{{SITENAME}}</label>
    <input type=\"radio\" name=\"sitesearch\" id=\"gWWW\" value=\"\" /><label for=\"gWWW\">WWW</label>
  </div>
</form>

</div>", */
#'guesstimezone' => "Fill in from browser",
#'headline_sample' => "Headline text",
#'headline_tip' => "Level 2 headline",
'help' => "Jutori",
'helppage' => "Jutori:Contignûts",
'hide' => "plate",
'hidetoc' => "plate",
'hist' => "stor",
/* 'histlegend' => "Diff selection: mark the radio boxes of the versions to compare and hit enter or the button at the bottom.<br />
Legend: (cur) = difference with current version,
(last) = difference with preceding version, M = minor edit.", */
'history' => "Storic de pagjine",
#'history_copyright' => "-",
'history_short' => "Storic",
#'historywarning' => "Warning: The page you are about to delete has a history: ",
#'hr_tip' => "Horizontal line (use sparingly)",
#'ignorewarning' => "Ignore warning and save file anyway.",
#'illegalfilename' => "The filename \"$1\" contains characters that are not allowed in page titles. Please rename the file and try uploading it again.",
#'ilshowmatch' => "Show all images with names matching",
'ilsubmit' => "Cîr",
#'image_sample' => "Example.jpg",
#'image_tip' => "Embedded image",
#'imagelinks' => "Image links",
#'imagelist' => "Image list",
#'imagelisttext' => "Below is a list of $1 images sorted $2.",
#'imagemaxsize' => "Limit images on image description pages to: ",
#'imagepage' => "View image page",
#'imagereverted' => "Revert to earlier version was successful.",
#'imgdelete' => "del",
#'imgdesc' => "desc",
/* 'imghistlegend' => "Legend: (cur) = this is the current image, (del) = delete
this old version, (rev) = revert to this old version.
<br /><i>Click on date to see image uploaded on that date</i>.", */
#'imghistory' => "Image history",
#'imglegend' => "Legend: (desc) = show/edit image description.",
#'import' => "Import pages",
#'importfailed' => "Import failed: $1",
#'importhistoryconflict' => "Conflicting history revision exists (may have imported this page before)",
'importnotext' => "Vueit o cence test",
#'importsuccess' => "Import succeeded!",
#'importtext' => "Please export the file from the source wiki using the Special:Export utility, save it to your disk and upload it here.",
#'info_short' => "Information",
#'infobox' => "Click a button to get an example text",
#'infobox_alert' => "Please enter the text you want to be formatted.\n It will be shown in the infobox for copy and pasting.\nExample:\n$1\nwill become:\n$2",
#'infosubtitle' => "Information for page",
#'internalerror' => "Internal error",
#'intl' => "Interlanguage links",
/* 'ip_range_invalid' => "Invalid IP range.
", */
#'ipaddress' => "IP Address/username",
#'ipb_expiry_invalid' => "Expiry time invalid.",
#'ipbexpiry' => "Expiry",
#'ipblocklist' => "List of blocked IP addresses and usernames",
#'ipbreason' => "Reason",
'ipbsubmit' => "Bloche chest utent",
#'ipusubmit' => "Unblock this address",
#'ipusuccess' => "\"$1\" unblocked",
#'isbn' => "ISBN",
#'isredirect' => "redirect page",
#'italic_sample' => "Italic text",
#'italic_tip' => "Italic text",
#'iteminvalidname' => "Problem with item '$1', invalid name...",
'jan' => "Zen",
'january' => "Zenâr",
'jul' => "Lui",
#'july' => "July",
'jun' => "Zug",
'june' => "Zugn",
#'laggedslavemode' => "Warning: Page may not contain recent updates.",
#'largefile' => "It is recommended that images not exceed 100k in size.",
#'last' => "last",
'lastmodified' => "Modificât par l'ultime volte il $1",
'lastmodifiedby' => "Modificât par l'ultime volte il $1 di",
#'lineno' => "Line $1:",
'link_sample' => "Titul dal leam",
#'link_tip' => "Internal link",
#'linklistsub' => "(List of links)",
#'linkshere' => "The following pages link to here:",
#'linkstoimage' => "The following pages link to this image:",
#'linktrail' => "/^([a-z]+)(.*)$/sD",
'listadmins' => "Liste dai aministradôrs",
'listform' => "liste",
#'listingcontinuesabbrev' => " cont.",
'listusers' => "Liste dai utents",
#'loadhist' => "Loading page history",
#'loadingrev' => "loading revision for diff",
#'localtime' => "Local time display",
#'lockbtn' => "Lock database",
#'lockconfirm' => "Yes, I really want to lock the database.",
#'lockdb' => "Lock database",
#'lockdbsuccesssub' => "Database lock succeeded",
/* 'lockdbsuccesstext' => "The database has been locked.
<br />Remember to remove the lock after your maintenance is complete.", */
/* 'lockdbtext' => "Locking the database will suspend the ability of all
users to edit pages, change their preferences, edit their watchlists, and
other things requiring changes in the database.
Please confirm that this is what you intend to do, and that you will
unlock the database when your maintenance is done.", */
#'locknoconfirm' => "You did not check the confirmation box.",
#'log' => "Logs",
'login' => "Jentre",
#'loginend' => "&nbsp;",
#'loginerror' => "Login error",
'loginpagetitle' => "Jentrade dal utent",
#'loginproblem' => "<b>There has been a problem with your login.</b><br />Try again!",
#'loginprompt' => "You must have cookies enabled to log in to {{SITENAME}}.",
#'loginreqtext' => "You must [[special:Userlogin|login]] to view other pages.",
#'loginreqtitle' => "Login Required",
#'loginsuccess' => "You are now logged in to {{SITENAME}} as \"$1\".",
'loginsuccesstitle' => "Jentrât cun sucès",
'logout' => "Jes",
/* 'logouttext' => "You are now logged out.
You can continue to use {{SITENAME}} anonymously, or you can log in
again as the same or as a different user. Note that some pages may
continue to be displayed as if you were still logged in, until you clear
your browser cache
", */
#'logouttitle' => "User logout",
#'lonelypages' => "Orphaned pages",
#'longpages' => "Long pages",
/* 'longpagewarning' => "WARNING: This page is $1 kilobytes long; some
browsers may have problems editing pages approaching or longer than 32kb.
Please consider breaking the page into smaller sections.", */
#'mailerror' => "Error sending mail: $1",
#'mailmypassword' => "Mail me a new password",
#'mailnologin' => "No send address",
/* 'mailnologintext' => "You must be <a href=\"{{localurl:Special:Userlogin\">logged in</a>
and have a valid e-mail address in your <a href=\"{{localurl:Special:Preferences}}\">preferences</a>
to send e-mail to other users.", */
'mainpage' => "Pagjine principâl",
/* 'mainpagedocfooter' => "Please see [http://meta.wikipedia.org/wiki/MediaWiki_i18n documentation on customizing the interface]
and the [http://meta.wikipedia.org/wiki/MediaWiki_User%27s_Guide User's Guide] for usage and configuration help.", */
#'mainpagetext' => "Wiki software successfully installed.",
#'maintenance' => "Maintenance page",
#'maintenancebacklink' => "Back to Maintenance Page",
#'maintnancepagetext' => "This page includes several handy tools for everyday maintenance. Some of these functions tend to stress the database, so please do not hit reload after every item you fixed ;-)",
#'makesysop' => "Make a user into a sysop",
#'makesysopfail' => "<b>User \"$1\" could not be made into a sysop. (Did you enter the name correctly?)</b>",
#'makesysopname' => "Name of the user:",
#'makesysopok' => "<b>User \"$1\" is now a sysop</b>",
#'makesysopsubmit' => "Make this user into a sysop",
/* 'makesysoptext' => "This form is used by bureaucrats to turn ordinary users into administrators.
Type the name of the user in the box and press the button to make the user an administrator", */
#'makesysoptitle' => "Make a user into a sysop",
#'mar' => "Mar",
#'march' => "March",
#'markaspatrolleddiff' => "Mark as patrolled",
#'markaspatrolledlink' => "<div class='patrollink'>[$1]</div>",
#'markaspatrolledtext' => "Mark this article as patrolled",
#'markedaspatrolled' => "Marked as patrolled",
#'markedaspatrolledtext' => "The selected revision has been marked as patrolled.",
/* 'matchtotals' => "The query \"$1\" matched $2 page titles
and the text of $3 pages.", */
#'math' => "Rendering math",
#'math_bad_output' => "Can't write to or create math output directory",
#'math_bad_tmpdir' => "Can't write to or create math temp directory",
#'math_failure' => "Failed to parse",
#'math_image_error' => "PNG conversion failed; check for correct installation of latex, dvips, gs, and convert",
#'math_lexing_error' => "lexing error",
#'math_notexvc' => "Missing texvc executable; please see math/README to configure.",
#'math_sample' => "Insert formula here",
#'math_syntax_error' => "syntax error",
#'math_tip' => "Mathematical formula (LaTeX)",
#'math_unknown_error' => "unknown error",
#'math_unknown_function' => "unknown function ",
'may' => "Mai",
'may_long' => "Mai",
#'media_sample' => "Example.mp3",
#'media_tip' => "Media file link",
#'minlength' => "Image names must be at least three letters.",
'minoredit' => "Cheste e je une piçule modifiche",
'minoreditletter' => "p",
#'mispeelings' => "Pages with misspellings",
#'mispeelingspage' => "List of common misspellings",
#'mispeelingstext' => "The following pages contain a common misspelling, which are listed on $1. The correct spelling might be given (like this).",
/* 'missingarticle' => "The database did not find the text of a page
that it should have found, named \"$1\".

<p>This is usually caused by following an outdated diff or history link to a
page that has been deleted.

<p>If this is not the case, you may have found a bug in the software.
Please report this to an administrator, making note of the URL.", */
/* 'missingimage' => "<b>Missing image</b><br /><i>$1</i>
", */
#'missinglanguagelinks' => "Missing Language Links",
#'missinglanguagelinksbutton' => "Find missing language links for",
#'missinglanguagelinkstext' => "These pages do <i>not</i> link to their counterpart in $1. Redirects and subpages are <i>not</i> shown.",
'monday' => "Lunis",
#'moredotdotdot' => "More...",
'move' => "Môf",
'movearticle' => "Môf l'articul",
'movedto' => "Movude in",
'movenologin' => "No tu sês jentrât",
/* 'movenologintext' => "You must be a registered user and <a href=\"{{localurl:Special:Userlogin}}\">logged in</a>
to move a page.", */
'movepage' => "Môf pagjine",
'movepagebtn' => "Môf pagjine",
/* 'movepagetalktext' => "The associated talk page, if any, will be automatically moved along with it '''unless:'''
*You are moving the page across namespaces,
*A non-empty talk page already exists under the new name, or
*You uncheck the box below.

In those cases, you will have to move or merge the page manually if desired.", */
/* 'movepagetext' => "Using the form below will rename a page, moving all
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
proceeding.", */
#'movetalk' => "Move \"talk\" page as well, if applicable.",
'movethispage' => "Môf cheste pagjine",
#'mw_math_html' => "HTML if possible or else PNG",
#'mw_math_mathml' => "MathML if possible (experimental)",
#'mw_math_modern' => "Recommended for modern browsers",
#'mw_math_png' => "Always render PNG",
#'mw_math_simple' => "HTML if very simple or else PNG",
#'mw_math_source' => "Leave it as TeX (for text browsers)",
'mycontris' => "Gno contribûts",
#'mypage' => "My page",
#'mytalk' => "My talk",
'navigation' => "somari",
#'nbytes' => "$1 bytes",
#'nchanges' => "$1 changes",
'newarticle' => "(Gnûf)",
'newarticletext' => "Tu âs seguît un leam a une pagjine che no esist ancjemò. Par creâ une pagjine, scomence a scrivi tal spazi ca sot (cjale il [[Jutori:Contignûts|jutori]] par altris informazions). Se tu sês ca par erôr, frache semplicementri il boton '''Indaûr''' dal to sgarfadôr.",
#'newbies' => "newbies",
#'newimages' => "New images gallery",
'newmessages' => "Tu âs $1.",
'newmessageslink' => "gnûf(s) messaç",
'newpage' => "Gnove pagjine",
'newpageletter' => "G",
'newpages' => "Gnovis pagjinis",
#'newpassword' => "New password",
#'newtitle' => "To new title",
'newusersonly' => "(dome gnûfs utents)",
'newwindow' => "(al vierç un gnûf barcon)",
#'next' => "next",
#'nextdiff' => "Go to next diff &rarr;",
#'nextn' => "next $1",
#'nextpage' => "Next page ($1)",
#'nextrevision' => "Newer revision&rarr;",
'nlinks' => "$1 leams",
#'noaffirmation' => "You must affirm that your upload does not violate any copyrights.",
'noarticletext' => "(Par cumò nol è nuie in cheste pagjine)",
#'noblockreason' => "You must supply a reason for the block.",
/* 'noconnect' => "Sorry! The wiki is experiencing some technical difficulties, and cannot contact the database server. <br />
$1", */
#'nocontribs' => "No changes were found matching these criteria.",
#'nocookieslogin' => "{{SITENAME}} uses cookies to log in users. You have cookies disabled. Please enable them and try again.",
#'nocookiesnew' => "The user account was created, but you are not logged in. {{SITENAME}} uses cookies to log in users. You have cookies disabled. Please enable them, then log in with your new username and password.",
#'nocreativecommons' => "Creative Commons RDF metadata disabled for this server.",
#'nocredits' => "There is no credits info available for this page.",
#'nodb' => "Could not select database $1",
#'nodublincore' => "Dublin Core RDF metadata disabled for this server.",
#'noemail' => "There is no e-mail address recorded for user \"$1\".",
/* 'noemailtext' => "This user has not specified a valid e-mail address,
or has chosen not to receive e-mail from other users.", */
#'noemailtitle' => "No e-mail address",
#'nogomatch' => "No page with this exact title exists, trying full text search.",
#'nohistory' => "There is no edit history for this page.",
#'noimages' => "Nothing to see.",
'nolinkshere' => "Nissune pagjine e à leams a chest articul",
#'nolinkstoimage' => "There are no pages that link to this image.",
#'noname' => "You have not specified a valid user name.",
/* 'nonefound' => "'''Note''': unsuccessful searches are
often caused by searching for common words like \"have\" and \"from\",
which are not indexed, or by specifying more than one search term (only pages
containing all of the search terms will appear in the result).", */
#'nonunicodebrowser' => "<strong>WARNING: Your browser is not unicode compliant, please change it before editing an article.</strong>",
/* 'nospecialpagetext' => "You have requested a special page that is not
recognized by the wiki.", */
#'nosuchaction' => "No such action",
/* 'nosuchactiontext' => "The action specified by the URL is not
recognized by the wiki", */
#'nosuchspecialpage' => "No such special page",
/* 'nosuchuser' => "There is no user by the name \"$1\".
Check your spelling, or use the form below to create a new user account.", */
#'nosuchusershort' => "There is no user by the name \"$1\". Check your spelling.",
#'notacceptable' => "The wiki server can't provide data in a format your client can read.",
#'notanarticle' => "Not a content page",
/* 'notargettext' => "You have not specified a target page or user
to perform this function on.", */
#'notargettitle' => "No target",
#'note' => "<strong>Note:</strong> ",
#'notextmatches' => "No page text matches",
#'notitlematches' => "No page title matches",
#'notloggedin' => "Not logged in",
'nov' => "Nov",
'november' => "Novembar",
#'nowatchlist' => "You have no items on your watchlist.",
#'nowiki_sample' => "Insert non-formatted text here",
#'nowiki_tip' => "Ignore wiki formatting",
'nstab-category' => "Categorie",
'nstab-help' => "Jutori",
'nstab-image' => "Figure",
'nstab-main' => "Articul",
#'nstab-media' => "Media",
'nstab-mediawiki' => "Messaç",
'nstab-special' => "Speciâl",
'nstab-template' => "Model",
'nstab-user' => "Pagjine dal utent",
'nstab-wp' => "Informazions",
#'numauthors' => "Number of distinct authors (article): $1",
#'numedits' => "Number of edits (article): $1",
#'numtalkauthors' => "Number of distinct authors (discussion page): $1",
#'numtalkedits' => "Number of edits (discussion page): $1",
#'numwatchers' => "Number of watchers: $1",
#'nviews' => "$1 views",
'oct' => "Otu",
'october' => "Otubar",
#'ok' => "OK",
#'oldpassword' => "Old password",
#'orig' => "orig",
#'orphans' => "Orphaned pages",
#'othercontribs' => "Based on work by $1.",
'otherlanguages' => "Altris lenghis",
#'others' => "others",
#'pagemovedsub' => "Move succeeded",
'pagemovedtext' => "Pagjine \"[[$1]]\" movude in \"[[$2]]\".",
#'pagetitle' => "$1 - {{SITENAME}}",
/* 'passwordremindertext' => "Someone (probably you, from IP address $1)
requested that we send you a new {{SITENAME}} login password.
The password for user \"$2\" is now \"$3\".
You should log in and change your password now.", */
#'passwordremindertitle' => "Password reminder from {{SITENAME}}",
/* 'passwordsent' => "A new password has been sent to the e-mail address
registered for \"$1\".
Please log in again after you receive it.", */
#'perfcached' => "The following data is cached and may not be completely up to date:",
/* 'perfdisabled' => "Sorry! This feature has been temporarily disabled
because it slows the database down to the point that no one can use
the wiki.", */
#'perfdisabledsub' => "Here's a saved copy from $1:",
#'personaltools' => "Personal tools",
#'popularpages' => "Popular pages",
'portal' => "Ostarie",
'portal-url' => "Vichipedie:Ostarie",
#'postcomment' => "Post a comment",
#'poweredby' => "{{SITENAME}} is powered by [http://www.mediawiki.org/ MediaWiki], an open source wiki engine.",
'powersearch' => "Cîr",
/* 'powersearchtext' => "
Search in namespaces :<br />
$1<br />
$2 List redirects &nbsp; Search for $3 $9", */
'preferences' => "Preferencis",
/* 'prefs-help-userdata' => "* <strong>Real name</strong> (optional): if you choose to provide it this will be used for giving you attribution for your work.<br />
* <strong>Email</strong> (optional): Enables people to contact you through the website without you having to reveal your
email address to them, and it can be used to send you a new password if you forget it.", */
#'prefs-misc' => "Misc settings",
#'prefs-personal' => "User data",
#'prefs-rc' => "Recent changes and stub display",
/* 'prefslogintext' => "You are logged in as \"$1\".
Your internal ID number is $2.

See [[Project:User preferences help]] for help deciphering the options.", */
#'prefsnologin' => "Not logged in",
/* 'prefsnologintext' => "You must be <a href=\"{{localurl:Special:Userlogin}}\">logged in</a>
to set user preferences.", */
#'prefsreset' => "Preferences have been reset from storage.",
'preview' => "Anteprime",
/* 'previewconflict' => "This preview reflects the text in the upper
text editing area as it will appear if you choose to save.", */
'previewnote' => "Visiti che cheste e je dome une anteprime, e no je stade ancjemò salvade!",
#'previousdiff' => "&larr; Go to previous diff",
#'previousrevision' => "&larr;Older revision",
#'prevn' => "previous $1",
'printableversion' => "Version stampabil",
'printsubtitle' => "(Articul dal sît {{SERVER}})",
'protect' => "Protêç",
'protectcomment' => "Reson pe protezion",
'protectedarticle' => "$1 protezût",
'protectedpage' => "Pagjine protezude",
/* 'protectedpagewarning' => "WARNING:  This page has been locked so that only
users with sysop privileges can edit it. Be sure you are following the
<a href='/w/index.php/Project:Protected_page_guidelines'>protected page
guidelines</a>.", */
/* 'protectedtext' => "This page has been locked to prevent editing; there are
a number of reasons why this may be so, please see
[[Project:Protected page]].

You can view and copy the source of this page:", */
#'protectlogpage' => "Protection_log",
/* 'protectlogtext' => "Below is a list of page locks/unlocks.
See [[Project:Protected page]] for more information.", */
'protectmoveonly' => "Protêç dome dai spostaments",
'protectpage' => "Protêç pagjine",
'protectreason' => "(inseris une reson)",
'protectsub' => "(Protezint \"$1\")",
'protectthispage' => "Protêç cheste pagjine",
#'proxyblocker' => "Proxy blocker",
#'proxyblockreason' => "Your IP address has been blocked because it is an open proxy. Please contact your Internet service provider or tech support and inform them of this serious security problem.",
/* 'proxyblocksuccess' => "Done.
", */
#'pubmedurl' => "http://www.ncbi.nlm.nih.gov/entrez/query.fcgi?cmd=Retrieve&db=pubmed&dopt=Abstract&list_uids=$1",
'qbbrowse' => "Sgarfe",
'qbedit' => "Modifiche",
'qbfind' => "Cjate",
#'qbmyoptions' => "My pages",
#'qbpageinfo' => "Context",
#'qbpageoptions' => "This page",
#'qbsettings' => "Quickbar",
#'qbsettingsnote' => "This preference only works in the 'Standard' and the 'CologneBlue' skin.",
'qbspecialpages' => "Pagjinis speciâls",
#'querybtn' => "Submit query",
#'querysuccessful' => "Query successful",
'randompage' => "Une pagjine a câs",
#'randompage-url' => "Special:Randompage",
#'range_block_disabled' => "The sysop ability to create range blocks is disabled.",
#'rchide' => "in $4 form; $1 minor edits; $2 secondary namespaces; $3 multiple edits.",
'rclinks' => "Mostre i ultins $1 cambiaments tes ultimis $2 zornadis<br />$3",
'rclistfrom' => "Mostre i ultins cambiaments dal $1",
#'rcliu' => "; $1 edits from logged in users",
#'rcloaderr' => "Loading recent changes",
#'rclsub' => "(to pages linked from \"$1\")",
'rcnote' => "Ca sot tu cjatis i ultins <strong>$1</strong> cambiaments te ultimis <strong>$2</strong> zornadis.",
#'rcnotefrom' => "Below are the changes since <b>$2</b> (up to <b>$1</b> shown).",
#'rcpatroldisabled' => "Recent Changes Patrol disabled",
#'rcpatroldisabledtext' => "The Recent Changes Patrol feature is currently disabled.",
#'readonly' => "Database locked",
#'readonly_lag' => "The database has been automatically locked while the slave database servers catch up to the master",
/* 'readonlytext' => "The database is currently locked to new
entries and other modifications, probably for routine database maintenance,
after which it will be back to normal.
The administrator who locked it offered this explanation:
<p>$1", */
/* 'readonlywarning' => "WARNING: The database has been locked for maintenance,
so you will not be able to save your edits right now. You may wish to cut-n-paste
the text into a text file and save it for later.", */
'recentchanges' => "Ultins cambiaments",
#'recentchanges-url' => "Special:Recentchanges",
#'recentchangescount' => "Number of titles in recent changes",
'recentchangeslinked' => "Cambiaments leâts",
'recentchangestext' => "Cheste pagjine e mostre i plui recents cambiaments inte Vichipedie.",
'redirectedfrom' => "(Inviât ca di $1)",
#'remembermypassword' => "Remember my password across sessions.",
#'removechecked' => "Remove checked items from watchlist",
#'removedwatch' => "Removed from watchlist",
#'removedwatchtext' => "The page \"$1\" has been removed from your watchlist.",
#'removingchecked' => "Removing requested items from watchlist...",
#'resetprefs' => "Reset preferences",
#'restorelink' => "$1 deleted edits",
#'resultsperpage' => "Hits to show per page",
'retrievedfrom' => "Cjapât fûr di $1",
'returnto' => "Torne a $1.",
#'retypenew' => "Retype new password",
'reupload' => "Torne a cjamâ sù",
#'reuploaddesc' => "Return to the upload form.",
#'reverted' => "Reverted to earlier revision",
#'revertimg' => "rev",
#'revertpage' => "Reverted edit of $2, changed back to last version by $1",
'revhistory' => "Storic des revisions",
#'revisionasof' => "Revision as of $1",
#'revisionasofwithlink' => "Revision as of $1; $2<br />$3 | $4",
#'revnotfound' => "Revision not found",
/* 'revnotfoundtext' => "The old revision of the page you asked for could not be found.
Please check the URL you used to access this page.
", */
#'rfcurl' => "http://www.faqs.org/rfcs/rfc$1.html",
#'rights' => "Rights:",
#'rightslogtext' => "This is a log of changes to user rights.",
#'rollback' => "Roll back edits",
#'rollback_short' => "Rollback",
#'rollbackfailed' => "Rollback failed",
#'rollbacklink' => "rollback",
#'rows' => "Rows",
'saturday' => "Sabide",
'savearticle' => "Salve la pagjine",
'savedprefs' => "Lis preferencis a son stadis salvadis",
#'savefile' => "Save file",
#'savegroup' => "Save Group",
'saveprefs' => "Salve lis preferencis",
#'saveusergroups' => "Save User Groups",
'search' => "Cîr",
/* 'searchdisabled' => "<p style=\"margin: 1.5em 2em 1em\">{{SITENAME}} search is disabled for performance reasons. You can search via Google in the meantime.
<span style=\"font-size: 89%; display: block; margin-left: .2em\">Note that their indexes of {{SITENAME}} content may be out of date.</span></p>", */
#'searchquery' => "For query \"$1\"",
#'searchresults' => "Search results",
#'searchresultshead' => "Search result settings",
#'searchresulttext' => "For more information about searching {{SITENAME}}, see [[Project:Searching|Searching {{SITENAME}}]].",
#'sectionlink' => "&rarr;",
#'selectnewerversionfordiff' => "Select a newer version for comparison",
#'selectolderversionfordiff' => "Select an older version for comparison",
#'selectonly' => "Only read-only queries are allowed.",
#'selflinks' => "Pages with Self Links",
#'selflinkstext' => "The following pages contain a link to themselves, which they should not.",
#'sep' => "Set",
#'september' => "Setembar",
#'seriousxhtmlerrors' => "There were serious xhtml markup errors detected by tidy.",
#'servertime' => "Server time is now",
/* 'sessionfailure' => "There seems to be a problem with your login session;
this action has been canceled as a precaution against session hijacking.
Please hit \"back\" and reload the page you came from, then try again.", */
#'set_rights_fail' => "<b>User rights for \"$1\" could not be set. (Did you enter the name correctly?)</b>",
#'set_user_rights' => "Set user rights",
#'setbureaucratflag' => "Set bureaucrat flag",
/* 'sharedupload' => "This is a file from the [[Commons:Main Page|Wikimedia Commons]]. Please 
see its '''[[Commons:Image:{{PAGENAME}}|description page]]''' there.", */
#'shortpages' => "Short pages",
'show' => "mostre",
#'showbigimage' => "Download high resolution version ($1x$2, $3 KB)",
'showhideminor' => "$1 piçulis modifichis | $2 bots | $3 utents jentrâts | $4 modifichis verificadis",
#'showingresults' => "Showing below up to <b>$1</b> results starting with #<b>$2</b>.",
#'showingresultsnum' => "Showing below <b>$3</b> results starting with #<b>$2</b>.",
#'showlast' => "Show last $1 images sorted $2.",
'showpreview' => "Mostre anteprime",
'showtoc' => "mostre",
#'sig_tip' => "Your signature with timestamp",
#'siteadminpheading' => "siteadmin level",
#'sitenotice' => "-",
#'sitesettings' => "Site Settings",
#'sitesettings-caching' => "Page caching",
#'sitesettings-cookies' => "Cookies",
#'sitesettings-debugging' => "Debugging",
#'sitesettings-features' => "Features",
#'sitesettings-images' => "Images",
#'sitesettings-memcached' => "Memcache Daemon",
#'sitesettings-performance' => "Performance",
#'sitesettings-permissions' => "Permissions",
#'sitesettings-permissions-banning' => "User banning",
#'sitesettings-permissions-miser' => "Performance settings",
#'sitesettings-permissions-readonly' => "Maintenance mode: Disable write access",
#'sitesettings-permissions-whitelist' => "Whitelist mode",
#'sitesettings-wgAllowExternalImages' => "Allow to include external images into articles",
#'sitesettings-wgDefaultBlockExpiry' => "By default, blocks expire after:",
#'sitesettings-wgDisableQueryPages' => "When in miser mode, disable all query pages, not only \"expensive\" ones",
#'sitesettings-wgHitcounterUpdateFreq' => "Hit counter update frequency",
#'sitesettings-wgMiserMode' => "Enable miser mode, which disables most \"expensive\" features",
#'sitesettings-wgReadOnly' => "Readonly mode",
#'sitesettings-wgReadOnlyFile' => "Readonly message file",
#'sitesettings-wgShowIPinHeader' => "Show IP in header (for non-logged in users)",
#'sitesettings-wgSysopRangeBans' => "Sysops may block IP-ranges",
#'sitesettings-wgSysopUserBans' => "Sysops may block logged-in users",
#'sitesettings-wgUseCategoryBrowser' => "Enable experimental dmoz-like category browsing. Outputs things like:  Encyclopedia > Music > Style of Music > Jazz",
#'sitesettings-wgUseCategoryMagic' => "Enable categories",
#'sitesettings-wgUseDatabaseMessages' => "Use database messages for user interface labels",
#'sitesettings-wgUseWatchlistCache' => "Generate a watchlist once every hour or so",
#'sitesettings-wgWLCacheTimeout' => "The hour or so mentioned above (in seconds):",
#'sitesettings-wgWhitelistAccount-developer' => "Developers may create accounts for users",
#'sitesettings-wgWhitelistAccount-sysop' => "Sysops may create accounts for users",
#'sitesettings-wgWhitelistAccount-user' => "Users may create accounts themself",
#'sitesettings-wgWhitelistEdit' => "Users must be logged in to edit",
#'sitesettings-wgWhitelistRead' => "Anonymous users may only read these pages:",
'sitestats' => "Statistichis dal sît",
/* 'sitestatstext' => "There are '''$1''' total pages in the database.
This includes \"talk\" pages, pages about {{SITENAME}}, minimal \"stub\"
pages, redirects, and others that probably don't qualify as content pages.
Excluding those, there are '''$2''' pages that are probably legitimate
content pages.

There have been a total of '''$3''' page views, and '''$4''' page edits
since the wiki was setup.
That comes to '''$5''' average edits per page, and '''$6''' views per edit.", */
'sitesubtitle' => "L'enciclopedie libare",
#'sitesupport' => "-",
#'sitesupport-url' => "Project:Site support",
#'sitetitle' => "{{SITENAME}}",
#'siteuser' => "Wikipedia user $1",
#'siteusers' => "Wikipedia user(s) $1",
#'skin' => "Skin",
#'sorbs' => "SORBS DNSBL",
#'sorbs_create_account_reason' => "Your IP address is listed as an open proxy in the [http://www.sorbs.net SORBS] DNSBL. You cannot create an account",
#'sorbsreason' => "Your IP address is listed as an open proxy in the [http://www.sorbs.net SORBS] DNSBL.",
#'spamprotectionmatch' => "The following text is what triggered our spam filter: $1",
#'spamprotectiontext' => "The page you wanted to save was blocked by the spam filter. This is probably caused by a link to an external site.",
#'spamprotectiontitle' => "Spam protection filter",
#'special_version_postfix' => "&nbsp;",
#'special_version_prefix' => "&nbsp;",
#'speciallogtitlelabel' => "Title: ",
'specialloguserlabel' => "Utent:",
'specialpage' => "Pagjine speciâl",
'specialpages' => "Pagjinis speciâls",
#'spheading' => "Special pages for all users",
#'sqlhidden' => "(SQL query hidden)",
#'sqlislogged' => "Please note that all queries are logged.",
#'sqlquery' => "Enter query",
'statistics' => "Statistichis",
#'storedversion' => "Stored version",
#'stubthreshold' => "Threshold for stub display",
#'subcategories' => "Subcategories",
#'subcategorycount' => "There are $1 subcategories to this category.",
#'subcategorycount1' => "There is $1 subcategory to this category.",
#'subject' => "Subject/headline",
#'subjectpage' => "View subject",
#'successfulupload' => "Successful upload",
'summary' => "Somari",
'sunday' => "Domenie",
/* 'sysoptext' => "The action you have requested can only be
performed by users with \"sysop\" status.
See $1.", */
#'sysoptitle' => "Sysop access required",
#'tableform' => "table",
'tagline' => "De {{SITENAME}}, l'enciclopedie libare dute in marilenghe.",
#'talk' => "Discussion",
/* 'talkexists' => "The page itself was moved successfully, but the
talk page could not be moved because one already exists at the new
title. Please merge them manually.", */
'talkpage' => "Fevelin di cheste pagjine",
#'talkpagemoved' => "The corresponding talk page was also moved.",
#'talkpagenotmoved' => "The corresponding talk page was <strong>not</strong> moved.",
#'talkpagetext' => "<!-- MediaWiki:talkpagetext -->",
#'templatesused' => "Templates used on this page:",
'textboxsize' => "Modifiche",
#'textmatches' => "Page text matches",
#'thisisdeleted' => "View or restore $1?",
'thumbnail-more' => "Slargje",
'thursday' => "Joibe",
#'timezonelegend' => "Time zone",
#'timezoneoffset' => "Offset",
/* 'timezonetext' => "Enter number of hours your local time differs
from server time (UTC).", */
#'titlematches' => "Article title matches",
'toc' => "Indis",
#'tog-editondblclick' => "Edit pages on double click (JavaScript)",
#'tog-editsection' => "Enable section editing via [edit] links",
#'tog-editsectiononrightclick' => "Enable section editing by right clicking<br /> on section titles (JavaScript)",
#'tog-editwidth' => "Edit box has full width",
#'tog-fancysig' => "Raw signatures (without automatic link)",
#'tog-hideminor' => "Hide minor edits in recent changes",
#'tog-highlightbroken' => "Format broken links <a href=\"\" class=\"new\">like this</a> (alternative: like this<a href=\"\" class=\"internal\">?</a>).",
#'tog-hover' => "Show hoverbox over wiki links",
#'tog-justify' => "Justify paragraphs",
#'tog-minordefault' => "Mark all edits minor by default",
#'tog-nocache' => "Disable page caching",
#'tog-numberheadings' => "Auto-number headings",
#'tog-previewonfirst' => "Show preview on first edit",
#'tog-previewontop' => "Show preview before edit box and not after it",
#'tog-rememberpassword' => "Remember password across sessions",
#'tog-showtoc' => "Show table of contents<br />(for pages with more than 3 headings)",
#'tog-showtoolbar' => "Show edit toolbar",
#'tog-underline' => "Underline links",
#'tog-usenewrc' => "Enhanced recent changes (not for all browsers)",
#'tog-watchdefault' => "Add pages you edit to your watchlist",
'toolbox' => "imprescj",
#'tooltip-compareselectedversions' => "See the differences between the two selected versions of this page. [alt-v]",
#'tooltip-minoredit' => "Mark this as a minor edit [alt-i]",
#'tooltip-preview' => "Preview your changes, please use this before saving! [alt-p]",
#'tooltip-save' => "Save your changes [alt-s]",
#'tooltip-search' => "Search this wiki [alt-f]",
#'tooltip-watch' => "Add this page to your watchlist [alt-w]",
'tuesday' => "Martars",
#'uclinks' => "View the last $1 changes; view the last $2 days.",
#'ucnote' => "Below are this user's last <b>$1</b> changes in the last <b>$2</b> days.",
#'uctop' => " (top)",
#'unblockip' => "Unblock user",
/* 'unblockiptext' => "Use the form below to restore write access
to a previously blocked IP address or username.", */
#'unblocklink' => "unblock",
#'unblocklogentry' => "unblocked \"$1\"",
#'uncategorizedcategories' => "Uncategorized categories",
#'uncategorizedpages' => "Uncategorized pages",
#'undelete' => "Restore deleted page",
#'undelete_short' => "Undelete $1 edits",
#'undeletearticle' => "Restore deleted page",
#'undeletebtn' => "Restore!",
#'undeletedarticle' => "restored \"$1\"",
#'undeletedrevisions' => "$1 revisions restored",
/* 'undeletedtext' => "[[$1]] has been successfully restored.
See [[Special:Log/delete]] for a record of recent deletions and restorations.", */
/* 'undeletehistory' => "If you restore the page, all revisions will be restored to the history.
If a new page with the same name has been created since the deletion, the restored
revisions will appear in the prior history, and the current revision of the live page
will not be automatically replaced.", */
#'undeletepage' => "View and restore deleted pages",
/* 'undeletepagetext' => "The following pages have been deleted but are still in the archive and
can be restored. The archive may be periodically cleaned out.", */
#'undeleterevision' => "Deleted revision as of $1",
#'undeleterevisions' => "$1 revisions archived",
#'undo' => "undo",
#'unexpected' => "Unexpected value: \"$1\"=\"$2\".",
#'unlockbtn' => "Unlock database",
#'unlockconfirm' => "Yes, I really want to unlock the database.",
#'unlockdb' => "Unlock database",
#'unlockdbsuccesssub' => "Database lock removed",
#'unlockdbsuccesstext' => "The database has been unlocked.",
/* 'unlockdbtext' => "Unlocking the database will restore the ability of all
users to edit pages, change their preferences, edit their watchlists, and
other things requiring changes in the database.
Please confirm that this is what you intend to do.", */
#'unprotect' => "Unprotect",
#'unprotectcomment' => "Reason for unprotecting",
#'unprotectedarticle' => "unprotected $1",
#'unprotectsub' => "(Unprotecting \"$1\")",
#'unprotectthispage' => "Unprotect this page",
#'unusedimages' => "Unused images",
/* 'unusedimagestext' => "<p>Please note that other web sites may link to an image with
a direct URL, and so may still be listed here despite being
in active use.</p>", */
#'unwatch' => "Unwatch",
#'unwatchthispage' => "Stop watching",
#'updated' => "(Updated)",
'upload' => "Cjame sù un file",
'uploadbtn' => "Cjame sù un file",
#'uploadcorrupt' => "The file is corrupt or has an incorrect extension. Please check the file and upload again.",
#'uploaddisabled' => "Sorry, uploading is disabled.",
#'uploadedfiles' => "Uploaded files",
#'uploadedimage' => "uploaded \"$1\"",
#'uploaderror' => "Upload error",
#'uploadfile' => "Upload images, sounds, documents etc.",
#'uploadlink' => "Upload images",
#'uploadlog' => "upload log",
#'uploadlogpage' => "Upload_log",
#'uploadlogpagetext' => "Below is a list of the most recent file uploads.",
#'uploadnologin' => "Not logged in",
/* 'uploadnologintext' => "You must be <a href=\"{{localurl:Special:Userlogin}}\">logged in</a>
to upload files.", */
/* 'uploadtext' => "'''STOP!''' Before you upload here,
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
you may be blocked from uploading if you abuse the system.", */
#'uploadwarning' => "Upload warning",
/* 'usenewcategorypage' => "1

Set first character to \"0\" to disable the new category page layout.", */
#'user_rights_set' => "<b>User rights for \"$1\" updated</b>",
#'usercssjsyoucanpreview' => "<strong>Tip:</strong> Use the 'Show preview' button to test your new CSS/JS before saving.",
#'usercsspreview' => "'''Remember that you are only previewing your user CSS, it has not yet been saved!'''",
#'userexists' => "The user name you entered is already in use. Please choose a different name.",
#'userjspreview' => "'''Remember that you are only testing/previewing your user JavaScript, it has not yet been saved!'''",
#'userlevels' => "User levels management",
#'userlevels-addgroup' => "Add group",
#'userlevels-editgroup' => "Edit group",
#'userlevels-editgroup-description' => "Group description (max 255 characters):<br />",
#'userlevels-editgroup-name' => "Group name: ",
#'userlevels-editusergroup' => "Edit user groups",
#'userlevels-group-edit' => "Existent groups: ",
#'userlevels-groupsavailable' => "Available groups:",
/* 'userlevels-groupshelp' => "Select groups you want the user to be removed from or added to.
Unselected groups will not be changed. You can unselect a group by using CTRL + Left Click", */
#'userlevels-groupsmember' => "Member of:",
#'userlevels-lookup-group' => "Manage group rights",
#'userlevels-lookup-user' => "Manage user groups",
#'userlevels-user-editname' => "Enter a username: ",
'userlogin' => "Regjistriti o jentre",
'userlogout' => "Jes",
#'usermailererror' => "Mail object returned error: ",
#'userpage' => "View user page",
#'userrightspheading' => "userrights level",
#'userstats' => "User statistics",
/* 'userstatstext' => "There are '''$1''' registered users.
'''$2''' of these are administrators (see $3).", */
#'val_article_lists' => "List of validated articles",
#'val_clear_old' => "Clear my other validation data for $1",
/* 'val_form_note' => "<b>Hint:</b> Merging your data means that for the article
revision you select, all options where you have specified <i>no opinion</i>
will be set to the value and comment of the most recent revision for which you
have expressed an opinion. For example, if you want to change a single option
for a newer revision, but also keep your other settings for this article in
this revision, just select which option you intend to <i>change</i>, and
merging will fill in the other options with your previous settings.", */
#'val_merge_old' => "Use my previous assessment where selected 'No opinion'",
#'val_no_anon_validation' => "You have to be logged in to validate an article.",
#'val_noop' => "No opinion",
#'val_page_validation_statistics' => "Page validation statistics for $1",
#'val_percent' => "<b>$1%</b><br />($2 of $3 points<br />by $4 users)",
#'val_percent_single' => "<b>$1%</b><br />($2 of $3 points<br />by one user)",
#'val_stat_link_text' => "Validation statistics for this article",
#'val_tab' => "Validate",
/* 'val_table_header' => "<tr><th>Class</th>$1<th colspan=4>Opinion</th>$1<th>Comment</th></tr>
", */
#'val_this_is_current_version' => "this is the latest version",
#'val_total' => "Total",
#'val_user_validations' => "This user has validated $1 pages.",
#'val_validate_article_namespace_only' => "Only articles can be validated. This page is <i>not</i> in the article namespace.",
#'val_validate_version' => "Validate this version",
#'val_validated' => "Validation done.",
#'val_version' => "Version",
#'val_version_of' => "Version of $1",
#'val_view_version' => "View this version",
#'validate' => "Validate page",
#'variantname-zh' => "zh",
#'variantname-zh-cn' => "cn",
#'variantname-zh-hk' => "hk",
#'variantname-zh-sg' => "sg",
#'variantname-zh-tw' => "tw",
#'version' => "Version",
#'viewcount' => "This page has been accessed $1 times.",
#'viewprevnext' => "View ($1) ($2) ($3).",
'viewsource' => "Cjale risultive",
#'viewtalkpage' => "View discussion",
#'wantedpages' => "Wanted pages",
'watch' => "Ten di voli",
/* 'watchdetails' => "($1 pages watched not counting talk pages;
$2 total pages edited since cutoff;
$3...
<a href='$4'>show and edit complete list</a>.)", */
/* 'watcheditlist' => "Here's an alphabetical list of your
watched pages. Check the boxes of pages you want to remove
from your watchlist and click the 'remove checked' button
at the bottom of the screen.", */
'watchlist' => "Tignûts di voli",
'watchlistcontains' => "Tu stâs tignint di voli $1 pagjinis.",
#'watchlistsub' => "(for user \"$1\")",
#'watchmethod-list' => "checking watched pages for recent edits",
#'watchmethod-recent' => "checking recent edits for watched pages",
#'watchnochange' => "None of your watched items were edited in the time period displayed.",
#'watchnologin' => "Not logged in",
/* 'watchnologintext' => "You must be <a href=\"{{localurl:Special:Userlogin}}\">logged in</a>
to modify your watchlist.", */
'watchthis' => "Ten di voli cheste pagjine",
'watchthispage' => "Ten di voli cheste pagjine",
'wednesday' => "Miercus",
/* 'welcomecreation' => "== Welcome, $1! ==

Your account has been created. Don't forget to change your {{SITENAME}} preferences.", */
'whatlinkshere' => "Leams a chest articul",
#'whitelistacctext' => "To be allowed to create accounts in this Wiki you have to [[Special:Userlogin|log]] in and have the appropriate permissions.",
#'whitelistacctitle' => "You are not allowed to create an account",
#'whitelistedittext' => "You have to [[Special:Userlogin|login]] to edit pages.",
#'whitelistedittitle' => "Login required to edit",
#'whitelistreadtext' => "You have to [[Special:Userlogin|login]] to read pages.",
#'whitelistreadtitle' => "Login required to read",
#'wikipediapage' => "View project page",
#'wikititlesuffix' => "{{SITENAME}}",
#'wlnote' => "Below are the last $1 changes in the last <b>$2</b> hours.",
#'wlsaved' => "This is a saved version of your watchlist.",
#'wlshowlast' => "Show last $1 hours $2 days $3",
/* 'wrong_wfQuery_params' => "Incorrect parameters to wfQuery()<br />
Function: $1<br />
Query: $2
", */
#'wrongpassword' => "The password you entered is incorrect. Please try again.",
#'yourdiff' => "Differences",
#'youremail' => "Your email*",
#'yourlanguage' => "Interface language",
#'yourname' => "Your user name",
#'yournick' => "Your nickname (for signatures)",
#'yourpassword' => "Your password",
#'yourpasswordagain' => "Retype password",
#'yourrealname' => "Your real name*",
#'yourtext' => "Your text",
#'yourvariant' => "Language variant",
#'zhconversiontable' => "-{}-",
);

class LanguageFur extends LanguageUtf8 {

	function getBookstoreList () {
		global $wgBookstoreListFur ;
		return $wgBookstoreListFur ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesFur;
		return $wgNamespaceNamesFur;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesFur;
		return $wgNamespaceNamesFur[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesFur, $wgSitename;

		foreach ( $wgNamespaceNamesFur as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsFur;
		return $wgQuickbarSettingsFur;
	}

	function getSkinNames() {
		global $wgSkinNamesFur;
		return $wgSkinNamesFur;
	}


	// Inherit userAdjust()

	function date( $ts, $adj = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = (0 + substr( $ts, 6, 2 )) . " " .
		  $this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) .
		  " " . substr( $ts, 0, 4 );
		return $d;
	}

	function timeanddate( $ts, $adj = false ) {
		return $this->date( $ts, $adj ) . " à " . $this->time( $ts, $adj );
	}

	var $digitTransTable = array(
		',' => '&nbsp;',
		'.' => ','
	);
	
	function formatNum( $number ) {
		global $wgTranslateNumerals;
		return $wgTranslateNumerals ? strtr($number, $this->digitTransTable ) : $number;
	}


	function getValidSpecialPages() {
		global $wgValidSpecialPagesFur;
		return $wgValidSpecialPagesFur;
	}

	function getSysopSpecialPages() {
		global $wgSysopSpecialPagesFur;
		return $wgSysopSpecialPagesFur;
	}

	function getDeveloperSpecialPages() {
		global $wgDeveloperSpecialPagesFur;
		return $wgDeveloperSpecialPagesFur;
	}

	function getMessage( $key ) {
		global $wgAllMessagesFur, $wgAllMessagesEn;
		if( isset( $wgAllMessagesFur[$key] ) ) {
			return $wgAllMessagesFur[$key];
		} else {
			return Language::getMessage( $key );
		}
	}
	
}

?>

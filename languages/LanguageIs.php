<?php
#--------------------------------------------------------------------------
# Language-specific text
#--------------------------------------------------------------------------

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => "-"

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#

require_once( "LanguageUtf8.php" );

if($wgMetaNamespace === FALSE)
	$wgMetaNamespace = str_replace( " ", "_", $wgSitename );

/* private */ $wgNamespaceNamesIs = array(
  NS_MEDIA            => 'Miðlar',
  NS_SPECIAL          => 'Kerfissíða', # Special
  NS_MAIN             => '',
  NS_TALK             => 'Spjall', # Talk
  NS_USER             => 'Notandi',
  NS_USER_TALK        => 'Notandaspjall',
  NS_WIKIPEDIA        => $wgMetaNamespace,
  NS_WIKIPEDIA_TALK   => $wgMetaNamespace . 'spjall',
  NS_IMAGE            => 'Mynd',
  NS_IMAGE_TALK       => 'Myndaspjall',
  NS_MEDIAWIKI        => 'MediaWiki',
  NS_MEDIAWIKI_TALK   => 'MediaWikispjall',
  NS_TEMPLATE         => 'Snið',
  NS_TEMPLATE_TALK    => 'Sniðaspjall',
  NS_HELP             => 'Hjálp',
  NS_HELP_TALK        => 'Hjálparspjall',
  NS_CATEGORY         => 'Flokkur',
  NS_CATEGORY_TALK    => 'Flokkaspjall'
) + $wgNamespaceNamesEn;

/* private */ $wgDefaultUserOptionsIs = array(
	"date" => 2
) + $wgDefaultUserOptionsEn;

# Note to translators:
#   Please include the English words as synonyms.  This allows people
#   from other wikis to contribute more easily.
#
/* private */ $wgMagicWordsIs = array(
#   ID                                 CASE  SYNONYMS
  MAG_REDIRECT             => array( 0,    '#redirect', '#tilvísun' ),
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
  MAG_SERVER               => array( 0,    'SERVER'                 )
);


/* private */ $wgQuickbarSettingsIs = array(
	"Sleppa", "Fast vinstra megin", "Fast hægra megin", "Fljótandi til vinstri"
);

/* private */ $wgSkinNamesIs = array(
	"Venjulegt", "Gamaldags", "Kölnarblátt", "Paddington", "Montparnasse"
);

/* private */ $wgDateFormatsIs = array(
	"Alveg sama",
	"Janúar 15, 2001",
	"15 janúar 2001",
	"2001 janúar 15",
	"2001-01-15"
);

/* private */ $wgBookstoreListIs = array(
	"AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
	"PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
	"Barnes & Noble" => "http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	"Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1"
);



# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesIs = array(
	"Userlogin"		=> "Innskráning",
	"Userlogout"	=> "Útskrá",
	"Preferences"	=> "Stilla notendaviðmót",
	"Watchlist"		=> "Eftirlitslistinn minn",
	"Recentchanges" => "Nýlega uppfærðar síður",
	"Upload"		=> "Hlaða inn myndum",
	"Imagelist"		=> "Myndalisti",
	"Listusers"		=> "Skráðir notendur",
	"Statistics"	=> "Notkunartölur",
	"Randompage"	=> "Velja grein af handahófi",

	"Lonelypages"	=> "Munaðarlausar greinar",
	"Unusedimages"	=> "Munaðarlausar myndir",
	"Popularpages"	=> "Vinsælar greinar",
	"Wantedpages"	=> "Eftirlýstar greinar",
	"Shortpages"	=> "Stuttar greinar",
	"Longpages"		=> "Langar greinar",
	"Newpages"		=> "Nýlegar greinar",
	"Ancientpages"	=> "Elstu greinarnar",
        "Deadendpages"  => "Endastöðvar",
#	"Intl"                => "Tenglar í síður á erlendum málum",
	"Allpages"		=> "Allar síður eftir titli",

	"Ipblocklist"	=> "Bannaðir notendur/IP tölur",
	"Maintenance"	=> "Viðhaldssíða",
	"Specialpages"  => "Sérstakar síður",
	"Contributions" => "Framlög",
	"Emailuser"		=> "Senda notanda tölvupóst",
	"Whatlinkshere" => "Hvað vísar hingað",
	"Recentchangeslinked" => "",
	"Movepage"		=> "Færa síðu",
	"Booksources"	=> "Bókabúðir",
#	"Categories"	=> "Síðuflokkar",
	"Export"		=> "XML útgáfa af síðu",
);

/* private */ $wgSysopSpecialPagesIs = array(
	"Blockip"		=> "Banna notanda/IP tölu",
	"Asksql"		=> "Leita í gagnagrunni",
	"Undelete"		=> "Endurbyggja síður sem hefur verið eytt"
);

/* private */ $wgDeveloperSpecialPagesIs = array(
	"Lockdb"		=> "Gera gagnagrunninn óskrifanlegan (read-only)",
	"Unlockdb"		=> "Gera gagnagrunninn skrifanlegan aftur",
	"Debug"			=> "Aflúsunarupplýsingar"
);

#-------------------------------------------------------------------
# Default messages
#-------------------------------------------------------------------
# Allowed characters in keys are: A-Z, a-z, 0-9, underscore (_) and 
# hyphen (-). If you need more characters, you may be able to change
# the regex in MagicWord::initRegex
$wgAllMessagesIs = array(
'special_version_prefix' => '',
'special_version_postfix' => '',
# User Toggles

"tog-hover"		=> "Sýna hjálpartexta á wiki hlekkjum",
"tog-underline" => "Undirstrika hlekki",
"tog-highlightbroken" => "Sýna brotna hlekki <a href=\"\" class=\"new\">svona</a> (annars: svona<a href=\"\" class=\"internal\">?</a>).",
"tog-justify"	=> "Jafna málsgreinar",
"tog-hideminor" => "Fela minniháttar breytingar",
"tog-usenewrc" => "Endurbætt nýjar tengingar (ekki fyrir alla vafra)",
"tog-numberheadings" => "Númera fyrirsagnir sjálfkrafa",
"tog-editondblclick" => "Breyta síðu ef tvísmellt er á hlekkinn (JavaScript)",
"tog-editsection"=>"Leyfa breytingar á hluta síðna með [edit] hlekkjum",
"tog-editsectiononrightclick"=>"Leyfa breytingar á hluta síðna með því að  hægrismella á titla (JavaScript)",
"tog-showtoc"=>"Sýna efnisyfirlit",
"tog-rememberpassword" => "Muna lykilorð",
"tog-editwidth" => "Innsláttarsvæði hefur fulla breidd",
"tog-watchdefault" => "Bæta síðum sem þú breytir við eftirlitslista",
"tog-minordefault" => "Láta breytingar vera sjálfgefnar sem minniháttar",
"tog-previewontop" => "Setja prufuhnapp fyrir framan breytingahnapp",
"tog-nocache" => "Slökkva á flýtivistun síðna",

# Dates

'sunday' => 'sunnudagur',
'monday' => 'mánudagur',
'tuesday' => 'þriðjudagur',
'wednesday' => 'miðvikudagur',
'thursday' => 'fimmtudagur',
'friday' => 'föstudagur',
'saturday' => 'laugardagur',
'january' => 'janúar',
'february' => 'febrúar',
'march' => 'mars',
'april' => 'apríl',
'may_long' => 'maí',
'june' => 'júní',
'july' => 'júlí',
'august' => 'ágúst',
'september' => 'september',
'october' => 'október',
'november' => 'nóvember',
'december' => 'desember',
'jan' => 'jan',
'feb' => 'feb',
'mar' => 'mar',
'apr' => 'apr',
'may' => 'maí',
'jun' => 'jún',
'jul' => 'júl',
'aug' => 'ágú',
'sep' => 'sep',
'oct' => 'okt',
'nov' => 'nóv',
'dec' => 'des',

#'1movedto2' => "$1 moved to $2",
#'1movedto2_redir' => "$1 moved to $2 over redirect",
#'Monobook.css' => "/* edit this file to customize the monobook skin for the entire site */",
'Monobook.js' => "/* tooltips and access keys */
ta = new Object();
ta['pt-userpage'] = new Array('.','Notendasíðan mín'); 
ta['pt-anonuserpage'] = new Array('.','Notendasíðan fyrir IP töluna þína'); 
ta['pt-mytalk'] = new Array('n','Spallsíðan mín'); 
ta['pt-anontalk'] = new Array('n','Spjallsíðan fyrir þessa IP tölu'); 
ta['pt-preferences'] = new Array('','Almennar stillingar'); 
ta['pt-watchlist'] = new Array('l','Vaktlistinn.'); 
ta['pt-mycontris'] = new Array('y','Listi yfir framlög þín'); 
ta['pt-login'] = new Array('o','Þú ert hvattur/hvött til að innskrá þig, það er hinsvegar ekki nauðsynlegt.'); 
ta['pt-anonlogin'] = new Array('o','Þú ert hvattur/hvött til að innskrá þig, það er hinsvegar ekki nauðsynlegt.'); 
ta['pt-logout'] = new Array('o','Útskráning'); 
ta['ca-talk'] = new Array('t','Spallsíða þessarar síðu'); 
ta['ca-edit'] = new Array('e','Þú getur breytt síðu þessari, vinsamlegast notaðu Forsýn hnappin áður en þú vistar'); 
ta['ca-addsection'] = new Array('+','Viðbótarumræða.'); 
ta['ca-viewsource'] = new Array('e','Síða þessi er vernduð, þú getur skoðað wikikóða hennar.'); 
ta['ca-history'] = new Array('h','Eldri útgáfur af síðunni.'); 
ta['ca-protect'] = new Array('=','Vernda þessa síðu'); 
ta['ca-delete'] = new Array('d','Eyða þessari síðu'); 
ta['ca-undelete'] = new Array('d','Endurvekja breytingar á síðu þessari fyrir en henni var eitt'); 
ta['ca-move'] = new Array('m','Færa þessa síðu'); 
ta['ca-nomove'] = new Array('','Þér er óheimild að færa þessa síðu'); 
ta['ca-watch'] = new Array('w','Bæta þessari síðu við á vaktlistann'); 
ta['ca-unwatch'] = new Array('w','Fjarlægja þessa síðu af vaktlistanum'); 
ta['search'] = new Array('f','Leit'); 
ta['p-logo'] = new Array('','Forsíða'); 
ta['n-mainpage'] = new Array('z','Forsíða Wikipedia'); 
ta['n-portal'] = new Array('','Um verkefnið, hvernig er hægt að hjálpa og hvar á að byrja'); 
ta['n-currentevents'] = new Array('','Líðandi atburðir'); 
ta['n-recentchanges'] = new Array('r','Listi yfir nýlegar breytingar.'); 
ta['n-randompage'] = new Array('x','Handahófsvalin síða'); 
ta['n-help'] = new Array('','Efnisyfirlit yfir hjálparsíður.'); 
ta['n-sitesupport'] = new Array('','Fjárframlagssíða Wikimedia'); 
ta['t-whatlinkshere'] = new Array('j','Ítengilisti'); 
ta['t-recentchangeslinked'] = new Array('k','Nýlegar breitingar á ítengdum síðum'); 
ta['feed-rss'] = new Array('','RSS fyrir þessa síðu'); 
ta['feed-atom'] = new Array('','Atom fyrir þessa síðu'); 
ta['t-contributions'] = new Array('','Sýna framlagslista þessa notanda'); 
ta['t-emailuser'] = new Array('','Senda notanda þessum póst'); 
ta['t-upload'] = new Array('u','Innhlaða myndum eða margmiðlunarskrám'); 
ta['t-specialpages'] = new Array('q','Listi yfir kerfissíður'); 
ta['ca-nstab-main'] = new Array('c','Sýna síðuna'); 
ta['ca-nstab-user'] = new Array('c','Sýna notendasíðuna'); 
ta['ca-nstab-media'] = new Array('c','Sýna margmiðlunarsíðuna'); 
ta['ca-nstab-special'] = new Array('','Þetta er kerfissíða, þér er óhæft að breyta henni.'); 
ta['ca-nstab-wp'] = new Array('a','Sýna verkefnasíðuna'); 
ta['ca-nstab-image'] = new Array('c','Sýna myndasíðuna'); 
ta['ca-nstab-mediawiki'] = new Array('c','Sýna kerfisskilaboðin'); 
ta['ca-nstab-template'] = new Array('c','View the template'); 
ta['ca-nstab-help'] = new Array('c','Sýna hjálparsíðuna'); 
ta['ca-nstab-category'] = new Array('c','Sýna efnisflokkasíðuna');",
'about' => "Um",
'aboutpage' => "Wikipedia:Um",
'aboutwikipedia' => "Um Wikipedia",
#'accesskey-compareselectedversions' => "v",
#'accesskey-minoredit' => "i",
#'accesskey-preview' => "p",
#'accesskey-save' => "s",
#'accesskey-search' => "f",
'accmailtext' => "Lykilorðið fyrir '$1' hefur verið sent á $2.",
'accmailtitle' => "Leyniorð sent.",
#'acct_creation_throttle_hit' => "Sorry, you have already created $1 accounts. You can't make any more.",
#'actioncomplete' => "Action complete",
'addedwatch' => "Bætt á vaktlistann",
'addedwatchtext' => "Síðunni \"$1\" hefur verið bætt á [[Special:Watchlist|Vaktlistann]] þinn. 
Frekari breytingar á henni eða spallsíðu hennar munu verða sýndar þar.
Þar að auki verður síða þessi '''feitletruð''' á [[Special:Recentchanges|Nýlegum breytingum]]
svo auðveldara sé að sjá hana þar meðal fjöldans.

<p>Til að fjarlægja síðu þessa af vaktlistanum þarft þú að ýta á tengilinn er merktur er \"afvakta\".",
#'addsection' => "+",
'administrators' => "Wikipedia:Stjórnendur",
'affirmation' => "Ég staðfesti að rétthafi þessa efnis veitir leyfi
fyrir notkun þess undir $1. Eða að efni þetta er í
almannaeign.",
'all' => "allt",
'allmessages' => "Allar kerfismeldingar",
'allmessagestext' => "Þetta er listi yfir allar meldingar kerfisins í MediaWiki: nafnarýminu.",
'allpages' => "Allar síður",
#'alphaindexline' => "$1 to $2",
'alreadyloggedin' => "<font color=red><b>Notandinn $1 er þegar innskráður!</b></font><br />",
'alreadyrolled' => "Cannot rollback last edit of [[$1]]
by [[User:$2|$2]] ([[User talk:$2|Talk]]); someone else has edited or rolled back the page already. 

Last edit was by [[User:$3|$3]] ([[User talk:$3|Talk]]). ",
'ancientpages' => "Elstu síður",
#'and' => "and",
#'anontalk' => "Talk for this IP",
#'anontalkpagetext' => "----''This is the discussion page for an anonymous user who has not created an account yet or who does not use it. We therefore have to use the numerical [[IP address]] to identify him/her. Such an IP address can be shared by several users. If you are an anonymous user and feel that irrelevant comments have been directed at you, please [[Special:Userlogin|create an account or log in]] to avoid future confusion with other anonymous users.'' ",
#'anonymous' => "Anonymous user(s) of Wikipedia",
#'article' => "Content page",
'articleexists' => "Annaðhvort er þegar til síða undir þessum titli, 
eða sá titill sem þú hefur valið er ekki gildur. 
Vinsamlegast veldu annan titil.",
#'articlepage' => "View content page",
'asksql' => "Gagnagrunnsfyrirspurn",
#'autoblocker' => "Autoblocked because you share an IP address with \"$1\". Reason \"$2\".",
#'badarticleerror' => "This action cannot be performed on this page.",
#'badfilename' => "Image name has been changed to \"$1\".",
#'badfiletype' => "\".$1\" is not a recommended image file format.",
#'badipaddress' => "Invalid IP address",
#'badquery' => "Badly formed search query",
#'blanknamespace' => "(Main)",
'blockedtext' => "Your user name or IP address has been blocked by $1.
The reason given is this:<br />''$2''<p>You may contact $1 or one of the other
[[Wikipedia:Administrators|administrators]] to discuss the block.

Note that you may not use the \"email this user\" feature unless you have a valid email address registered in your [[Special:Preferences|user preferences]].

Your IP address is $3. Please include this address in any queries you make.
",
#'blockedtitle' => "User is blocked",
'blockip' => "Banna notanda",
#'blockipsuccesssub' => "Block succeeded",
#'blocklink' => "block",
#'blocklistline' => "$1, $2 blocked $3 (expires $4)",
#'blocklogentry' => "blocked \"$1\" with an expiry time of $2",
#'blocklogpage' => "Block_log",
'blocklogtext' => "This is a log of user blocking and unblocking actions. Automatically 
blocked IP addresses are not be listed. See the [[Special:Ipblocklist|IP block list]] for
the list of currently operational bans and blocks.",
'bold_sample' => "Feitletraður texti",
'bold_tip' => "Feitletraður texti",
#'booksources' => "Book sources",
'booksourcetext' => "Below is a list of links to other sites that
sell new and used books, and may also have further information
about books you are looking for.Wikipedia is not affiliated with any of these businesses, and
this list should not be construed as an endorsement.",
#'brokenredirects' => "Broken Redirects",
#'brokenredirectstext' => "The following redirects link to a non-existing pages.",
#'bugreports' => "Bug reports",
'bugreportspage' => "Wikipedia:Bug_reports",
#'bureaucratlog' => "Bureaucrat_log",
#'bureaucratlogentry' => "Rights for user \"$1\" set \"$2\"",
#'bureaucrattext' => "The action you have requested can only be performed by sysops with  \"bureaucrat\" status.",
#'bureaucrattitle' => "Bureaucrat access required",
'bydate' => "eftir dagsetningu",
'byname' => "eftir nafni",
'bysize' => "eftir stærð",
#'cachederror' => "The following is a cached copy of the requested page, and may not be up to date.",
'cancel' => "Hætta við",
#'cannotdelete' => "Could not delete the page or image specified. (It may have already been deleted by someone else.)",
#'cantrollback' => "Cannot revert edit; last contributor is only author of this page.",
'categories' => "Efnisflokkar",
'category' => "flokkur",
'category_header' => "Greinar í flokknum \"$1\"",
#'categoryarticlecount' => "There are $1 articles in this category.",
'changepassword' => "Breyta lykilorði",
'changes' => "Breytingar",
#'clearyourcache' => "'''Note:''' After saving, you have to clear your browser cache to see the changes: '''Mozilla:''' click ''Reload'' (or ''Ctrl-R''), '''IE / Opera:''' ''Ctrl-F5'', '''Safari:''' ''Cmd-R'', '''Konqueror''' ''Ctrl-R''.",
#'columns' => "Columns",
#'commentedit' => " (comment)",
#'compareselectedversions' => "Compare selected versions",
'confirm' => "Staðfesta",
'confirmcheck' => "Eyða.",
'confirmdelete' => "Staðfesting á eyðingu",
'confirmdeletetext' => "Þú ert um það bil að eyða síðu eða mynd ásamt
breytingaskrá hennar úr gagnagrunninum.
Vinsamlegast staðfestu hér bæði að þetta sé vilji þinn
og að þú skiljir afleiðingarnar. Þar að auki að þetta
sé í samræmi við [[Wikipedia:Policy|almenna stefnu]].",
#'confirmprotect' => "Confirm protection",
#'confirmprotecttext' => "Do you really want to protect this page?",
#'confirmunprotect' => "Confirm unprotection",
#'confirmunprotecttext' => "Do you really want to unprotect this page?",
#'contextchars' => "Characters of context per line",
#'contextlines' => "Lines to show per hit",
#'contribslink' => "contribs",
#'contribsub' => "For $1",
'contributions' => "Framlög notanda",
#'copyright' => "Content is available under $1.",
'copyrightpage' => "Wikipedia:Höfundarréttur",
'copyrightpagename' => "Höfundarréttarreglum Wikipedia",
'copyrightwarning' => "Please note that all contributions to Wikipedia are
considered to be released under the GNU Free Documentation License
(see $1 for details).
If you don't want your writing to be edited mercilessly and redistributed
at will, then don't submit it here.<br />
You are also promising us that you wrote this yourself, or copied it from a
public domain or similar free resource.
<strong>DO NOT SUBMIT COPYRIGHTED WORK WITHOUT PERMISSION!</strong>",
#'couldntremove' => "Couldn't remove item '$1'...",
#'createaccount' => "Create new account",
#'createaccountmail' => "by email",
#'creditspage' => "Page credits",
'cur' => "nú",
'currentevents' => "Líðandi stund",
#'currentrev' => "Current revision",
'databaseerror' => "Gagnagrunnsvilla",
#'dateformat' => "Date format",
#'deadendpages' => "Dead-end pages",
'debug' => "Aflúsa",
#'defaultns' => "Search in these namespaces by default:",
'defemailsubject' => "Wikipedia e-mail",
'delete' => "Eyða",
'deletecomment' => "Ástæða",
#'deletedarticle' => "deleted \"$1\"",
'deletedtext' => "\"$1\" hefur verið eytt. Sjá lista yfir nýlegar eyðingar á $2.",
'deleteimg' => "eyða",
#'deletepage' => "Delete page",
'deletesub' => "(Eyði: \"$1\")",
'deletethispage' => "Eyða þessari síðu",
#'deletionlog' => "deletion log",
#'dellogpage' => "Deletion_log",
#'developerspheading' => "For developer use only",
#'developertitle' => "Developer access required",
#'diff' => "diff",
#'difference' => "(Difference between revisions)",
#'disambiguations' => "Disambiguation pages",
'disambiguationspage' => "Wikipedia:Links_to_disambiguating_pages",
#'disambiguationstext' => "The following pages link to a <i>disambiguation page</i>. They should link to the appropriate topic instead.<br />A page is treated as dismbiguation if it is linked from $1.<br />Links from other namespaces are <i>not</i> listed here.",
'disclaimerpage' => "Wikipedia:General_disclaimer",
#'disclaimers' => "Disclaimers",
#'doubleredirects' => "Double Redirects",
#'editcomment' => "The edit comment was: \"<i>$1</i>\".",
#'editconflict' => "Edit conflict: $1",
#'editcurrent' => "Edit the current version of this page",
'edithelp' => "Breytingarhjálp",
'edithelppage' => "Help:Editing",
#'editing' => "Editing $1",
#'editsection' => "edit",
'editthispage' => "Breyta þessari síðu",
#'emailflag' => "Disable e-mail from other users",
'emailforlost' => "Fields marked with a star (*) are optional.  Storing an email address enables people to contact you through the website without you having to reveal your 
email address to them, and it can be used to send you a new password if you forget it.<br /><br />Your real name, if you choose to provide it, will be used for giving you attribution for your work.",
#'emailfrom' => "From",
#'emailmessage' => "Message",
#'emailpage' => "E-mail user",
#'emailsend' => "Send",
#'emailsent' => "E-mail sent",
#'emailsenttext' => "Your e-mail message has been sent.",
#'emailsubject' => "Subject",
#'emailto' => "To",
#'emailuser' => "E-mail this user",
'error' => "Villa",
'errorpagetitle' => "Villa",
#'exbeforeblank' => "content before blanking was:",
#'exblank' => "page was empty",
#'excontent' => "content was:",
#'export' => "Export pages",
#'exportcuronly' => "Include only the current revision, not the full history",
'extlink_sample' => "http://www.example.com titill tengils",
'extlink_tip' => "Ytri tengill (muna að setja http:// á undan)",
#'faq' => "FAQ",
'faqpage' => "Wikipedia:FAQ",
'feedlinks' => "Nippan:",
#'filecopyerror' => "Could not copy file \"$1\" to \"$2\".",
#'filedeleteerror' => "Could not delete file \"$1\".",
'filedesc' => "Lýsing",
#'fileexists' => "A file with this name exists already, please check $1 if you are not sure if you want to change it.",
'filename' => "Skráarnafn",
#'filenotfound' => "Could not find file \"$1\".",
#'filerenameerror' => "Could not rename file \"$1\" to \"$2\".",
#'filesource' => "Source",
#'filestatus' => "Copyright status",
'fileuploaded' => "File \"$1\" uploaded successfully.
Please follow this link: $2 to the description page and fill
in information about the file, such as where it came from, when it was
created and by whom, and anything else you may know about it.",
#'formerror' => "Error: could not submit form",
'fromwikipedia' => "Úr Wikipedia, frjálsu alfræðiorðabókinni",
'fundraising_notice' => "Ef þú vilt styðja wikipedia getur þú gert það í verki með að <a href=\"http://wikimediafoundation.org/fundraising\">láta fé af hendi rakna</a>. Verið er að safna fyrir <a href=\"http://meta.wikimedia.org/wiki/What_we_use_the_money_for\">ýmsum hlutum</a>.",
#'getimagelist' => "fetching image list",
'go' => "Áfram",
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
<input type=hidden name=domains value=\"http://is.wikipedia.org\"><br /><input type=radio name=sitesearch value=\"\"> WWW <input type=radio name=sitesearch value=\"http://is.wikipedia.org\" checked> http://is.wikipedia.org <br />
<input type='hidden' name='ie' value='$2'>
<input type='hidden' name='oe' value='$2'>
</font>
</td></tr></TABLE>
</FORM>
<!-- SiteSearch Google -->",
#'guesstimezone' => "Fill in from browser",
#'headline_sample' => "Headline text",
#'headline_tip' => "Level 2 headline",
'help' => "Hjálp",
'helppage' => "Hjálp:Efnisyfirlit",
'hide' => "Fela",
'hidetoc' => "fela",
#'hist' => "hist",
'history' => "Forsaga síðu",
#'history_copyright' => "-",
'history_short' => "Breytingaskrá",
#'historywarning' => "Warning: The page you are about to delete has a history: ",
'hr_tip' => "Lárétt lína (notist sparlega)",
#'ignorewarning' => "Ignore warning and save file anyway.",
'ilshowmatch' => "Sýna allar myndir með nöfn sem passa við",
'ilsubmit' => "Leita",
'image_sample' => "Sýnishorn.jpeg",
'image_tip' => "Setja inn mynd",
'imagelinks' => "Myndatenglar",
'imagelist' => "Skráalisti",
'imagelisttext' => "Hér fyrir neðan er $1 skrám raðað $2.",
#'imagepage' => "View image page",
#'imagereverted' => "Revert to earlier version was successful.",
'imgdelete' => "eyða",
'imgdesc' => "lýsing",
'imghistory' => "Breytingaskrá myndar",
'imglegend' => "Skýringar: (lýsing) = sýna og/eða breyta lýsingu skráar.",
#'import' => "Import pages",
#'importfailed' => "Import failed: $1",
#'importhistoryconflict' => "Conflicting history revision exists (may have imported this page before)",
#'importnotext' => "Empty or no text",
#'importsuccess' => "Import succeeded!",
#'importtext' => "Please export the file from the source wiki using the Special:Export utility, save it to your disk and upload it here.",
#'info_short' => "Information",
#'infobox' => "Click a button to get an example text",
#'infobox_alert' => "Please enter the text you want to be formatted.\n It will be shown in the infobox for copy and pasting.\nExample:\n$1\nwill become:\n$2",
#'infosubtitle' => "Information for page",
#'internalerror' => "Internal error",
#'intl' => "Interlanguage links",
'ipaddress' => "IP Tala/notendanafn",
#'ipb_expiry_invalid' => "Expiry time invalid.",
'ipbexpiry' => "Rennur út eftir",
'ipblocklist' => "Bannaðar notendur og IP tölur",
'ipbreason' => "Ástæða",
'ipbsubmit' => "Banna notanda",
#'ipusubmit' => "Unblock this address",
#'ipusuccess' => "\"$1\" unblocked",
#'isbn' => "ISBN",
#'isredirect' => "redirect page",
'italic_sample' => "Skáletraður texti",
'italic_tip' => "Skáletraður texti",
#'iteminvalidname' => "Problem with item '$1', invalid name...",
#'largefile' => "It is recommended that images not exceed 100k in size.",
#'last' => "last",
'lastmodified' => "Þessari síðu var síðast breytt $1.",
#'lastmodifiedby' => "This page was last modified $1 by $2.",
#'lineno' => "Line $1:",
'link_sample' => "Titill tengils",
'link_tip' => "Innri tengill",
#'linklistsub' => "(List of links)",
#'linkshere' => "The following pages link to here:",
'linkstoimage' => "Eftirfarandi síður tengjast í mynd þessa:",
'linktrail' => "/^([a-z]+)(.*)\$/sD",
'listadmins' => "Stjórnendalisti",
#'listform' => "list",
'listusers' => "Notendalisti",
#'loadhist' => "Loading page history",
#'loadingrev' => "loading revision for diff",
#'localtime' => "Local time display",
#'lockbtn' => "Lock database",
#'lockconfirm' => "Yes, I really want to lock the database.",
#'lockdb' => "Lock database",
#'lockdbsuccesssub' => "Database lock succeeded",
#'lockdbsuccesstext' => "The database has been locked.  <br />Remember to remove the lock after your maintenance is complete.",
#'lockdbtext' => "Locking the database will suspend the ability of all users to edit pages, change their preferences, edit their watchlists, and other things requiring changes in the database.  Please confirm that this is what you intend to do, and that you will unlock the database when your maintenance is done.",
#'locknoconfirm' => "You did not check the confirmation box.",
#'login' => "Log in",
#'loginend' => "&nbsp;",
'loginerror' => "Innskráningarvilla",
#'loginpagetitle' => "User login",
#'loginproblem' => "<b>There has been a problem with your login.</b><br />Try again!",
'loginprompt' => "You must have cookies enabled to log in to Wikipedia.",
#'loginreqtext' => "You must [[special:Userlogin|login]] to view other pages.",
#'loginreqtitle' => "Login Required",
'loginsuccess' => "You are now logged in to Wikipedia as \"$1\".",
#'loginsuccesstitle' => "Login successful",
'logout' => "Útskráning",
'logouttext' => "You are now logged out.
You can continue to use Wikipedia anonymously, or you can log in
again as the same or as a different user. Note that some pages may
continue to be displayed as if you were still logged in, until you clear
your browser cache
",
#'logouttitle' => "User logout",
'lonelypages' => "Munaðarlausar síður",
'longpages' => "Langar síður",
#'longpagewarning' => "WARNING: This page is $1 kilobytes long; some browsers may have problems editing pages approaching or longer than 32kb.  Please consider breaking the page into smaller sections.",
#'mailerror' => "Error sending mail: $1",
#'mailmypassword' => "Mail me a new password",
#'mailnologin' => "No send address",
'mailnologintext' => "You must be <a href=\"{{localurl:Special:Userlogin\">logged in</a>
and have a valid e-mail address in your <a href=\"/wiki/Special:Preferences\">preferences</a>
to send e-mail to other users.",
'mainpage' => "Forsíða",
#'mainpagedocfooter' => "Please see [http://meta.wikipedia.org/wiki/MediaWiki_i18n documentation on customizing the interface] and the [http://meta.wikipedia.org/wiki/MediaWiki_User%27s_Guide User's Guide] for usage and configuration help.",
#'mainpagetext' => "Wiki software successfully installed.",
'maintenance' => "Viðhaldssíða",
#'maintenancebacklink' => "Back to Maintenance Page",
#'maintnancepagetext' => "This page includes several handy tools for everyday maintenance. Some of these functions tend to stress the database, so please do not hit reload after every item you fixed ;-)",
#'makesysop' => "Make a user into a sysop",
#'makesysopfail' => "<b>User \"$1\" could not be made into a sysop. (Did you enter the name correctly?)</b>",
#'makesysopname' => "Name of the user:",
#'makesysopok' => "<b>User \"$1\" is now a sysop</b>",
#'makesysopsubmit' => "Make this user into a sysop",
'makesysoptext' => "This form is used by bureaucrats to turn ordinary users into administrators. 
Type the name of the user in the box and press the button to make the user an administrator",
#'makesysoptitle' => "Make a user into a sysop",
#'matchtotals' => "The query \"$1\" matched $2 page titles and the text of $3 pages.",
#'math' => "Rendering math",
#'math_bad_output' => "Can't write to or create math output directory",
#'math_bad_tmpdir' => "Can't write to or create math temp directory",
#'math_failure' => "Failed to parse",
#'math_image_error' => "PNG conversion failed; check for correct installation of latex, dvips, gs, and convert",
#'math_lexing_error' => "lexing error",
#'math_notexvc' => "Missing texvc executable; please see math/README to configure.",
'math_sample' => "Formúla setjist hér",
#'math_syntax_error' => "syntax error",
'math_tip' => "Stærðfræðiformúla (LaTeX)",
#'math_unknown_error' => "unknown error",
#'math_unknown_function' => "unknown function ",
'media_sample' => "Sýnishorn.ogg",
'media_tip' => "Tengill í margmiðlunarskrá",
#'minlength' => "Image names must be at least three letters.",
'minoredit' => "Minniháttar breyting",
#'minoreditletter' => "M",
#'mispeelings' => "Pages with misspellings",
#'mispeelingspage' => "List of common misspellings",
#'mispeelingstext' => "The following pages contain a common misspelling, which are listed on $1. The correct spelling might be given (like this).",
#'missingarticle' => "The database did not find the text of a page that it should have found, named \"$1\".  <p>This is usually caused by following an outdated diff or history link to a page that has been deleted.  <p>If this is not the case, you may have found a bug in the software.  Please report this to an administrator, making note of the URL.",
#'missingimage' => "<b>Missing image</b><br /><i>$1</i> ",
#'missinglanguagelinks' => "Missing Language Links",
#'missinglanguagelinksbutton' => "Find missing language links for",
#'missinglanguagelinkstext' => "These pages do <i>not</i> link to their counterpart in $1. Redirects and subpages are <i>not</i> shown.",
#'moredotdotdot' => "More...",
'move' => "Færa",
'movearticle' => "Færa",
#'movedto' => "moved to",
#'movenologin' => "Not logged in",
'movenologintext' => "You must be a registered user and <a href=\"/wiki/Special:Userlogin\">logged in</a>
to move a page.",
#'movepage' => "Move page",
#'movepagebtn' => "Move page",
#'movepagetalktext' => "The associated talk page, if any, will be automatically moved along with it '''unless:'''
# *You are moving the page across namespaces,
# *A non-empty talk page already exists under the new name, or
# *You uncheck the box below.
# 
# In those cases, you will have to move or merge the page manually if desired.",
#'movepagetext' => "Using the form below will rename a page, moving all
# of its history to the new name.
# The old title will become a redirect page to the new title.
# Links to the old page title will not be changed; be sure to
# [[Special:Maintenance|check]] for double or broken redirects.
# You are responsible for making sure that links continue to
# point where they are supposed to go.
# 
# Note that the page will '''not''' be moved if there is already
# a page at the new title, unless it is empty or a redirect and has no
# past edit history. This means that you can rename a page back to where
# it was just renamed from if you make a mistake, and you cannot overwrite
# an existing page.
# 
# <b>WARNING!</b>
# This can be a drastic and unexpected change for a popular page;
# please be sure you understand the consequences of this before
# proceeding.",
#'movetalk' => "Move \"talk\" page as well, if applicable.",
'movethispage' => "Færa þessa síðu",
'mycontris' => "Framlög",
'mypage' => "Mín síða",
'mytalk' => "Spjall",
'navigation' => "Flakk",
#'nbytes' => "$1 bytes",
#'nchanges' => "$1 changes",
#'newarticle' => "(New)",
'newarticletext' => "You've followed a link to a page that doesn't exist yet.
To create the page, start typing in the box below 
(see the [[Wikipedia:Help|help page]] for more info).
If you are here by mistake, just click your browser's '''back''' button.",
#'newmessages' => "You have $1.",
#'newmessageslink' => "new messages",
#'newpage' => "New page",
#'newpageletter' => "N",
'newpages' => "Nýjar síður",
'newpassword' => "Nýja lykilorðið",
#'newtitle' => "To new title",
#'newusersonly' => " (new users only)",
'newwindow' => "(í nýjum glugga)",
#'next' => "next",
#'nextn' => "next $1",
#'nextpage' => "Next page ($1)",
#'nlinks' => "$1 links",
#'noaffirmation' => "You must affirm that your upload does not violate any copyrights.",
#'noarticletext' => "(There is currently no text in this page)",
#'noblockreason' => "You must supply a reason for the block.",
#'noconnect' => "Sorry! The wiki is experiencing some technical difficulties, and cannot contact the database server. <br /> $1",
#'nocontribs' => "No changes were found matching these criteria.",
'nocookieslogin' => "Wikipedia uses cookies to log in users. You have cookies disabled. Please enable them and try again.",
'nocookiesnew' => "The user account was created, but you are not logged in. Wikipedia uses cookies to log in users. You have cookies disabled. Please enable them, then log in with your new username and password.",
#'nocreativecommons' => "Creative Commons RDF metadata disabled for this server.",
#'nocredits' => "There is no credits info available for this page.",
#'nodb' => "Could not select database $1",
#'nodublincore' => "Dublin Core RDF metadata disabled for this server.",
#'noemail' => "There is no e-mail address recorded for user \"$1\".",
#'noemailtext' => "This user has not specified a valid e-mail address, or has chosen not to receive e-mail from other users.",
#'noemailtitle' => "No e-mail address",
#'nogomatch' => "No page with this exact title exists, trying full text search.",
#'nohistory' => "There is no edit history for this page.",
#'nolinkshere' => "No pages link to here.",
#'nolinkstoimage' => "There are no pages that link to this image.",
#'noname' => "You have not specified a valid user name.",
#'nonefound' => "<strong>Note</strong>: unsuccessful searches are often caused by searching for common words like \"have\" and \"from\", which are not indexed, or by specifying more than one search term (only pages containing all of the search terms will appear in the result).",
#'nospecialpagetext' => "You have requested a special page that is not recognized by the wiki.",
#'nosuchaction' => "No such action",
#'nosuchactiontext' => "The action specified by the URL is not recognized by the wiki",
#'nosuchspecialpage' => "No such special page",
#'nosuchuser' => "There is no user by the name \"$1\".  Check your spelling, or use the form below to create a new user account.",
#'notacceptable' => "The wiki server can't provide data in a format your client can read.",
#'notanarticle' => "Not a content page",
#'notargettext' => "You have not specified a target page or user to perform this function on.",
#'notargettitle' => "No target",
#'note' => "<strong>Note:</strong> ",
#'notextmatches' => "No page text matches",
#'notitlematches' => "No page title matches",
#'notloggedin' => "Not logged in",
'nowatchlist' => "Vaktlistinn er tómur.",
#'nowiki_sample' => "Insert non-formatted text here",
#'nowiki_tip' => "Ignore wiki formatting",
'nstab-category' => "Efnisflokkur",
'nstab-help' => "Hjálp",
'nstab-image' => "Mynd",
'nstab-main' => "Grein",
#'nstab-media' => "Media",
'nstab-mediawiki' => "Skilaboð",
#'nstab-special' => "Special",
'nstab-template' => "Forsnið",
#'nstab-user' => "User page",
'nstab-wp' => "Um",
#'numauthors' => "Number of distinct authors (article): ",
#'numedits' => "Number of edits (article): ",
#'numtalkauthors' => "Number of distinct authors (discussion page): ",
#'numtalkedits' => "Number of edits (discussion page): ",
#'numwatchers' => "Number of watchers: ",
#'nviews' => "$1 views",
#'ok' => "OK",
'oldpassword' => "Gamla lykilorðið",
#'orig' => "orig",
#'orphans' => "Orphaned pages",
#'othercontribs' => "Based on work by $1.",
#'otherlanguages' => "Other languages",
#'others' => "others",
#'pagemovedsub' => "Move succeeded",
#'pagemovedtext' => "Page \"[[$1]]\" moved to \"[[$2]]\".",
'pagetitle' => "$1 - Wikipedia",
'passwordremindertext' => "Someone (probably you, from IP address $1)
requested that we send you a new Wikipedia login password.
The password for user \"$2\" is now \"$3\".
You should log in and change your password now.",
'passwordremindertitle' => "Password reminder from Wikipedia",
#'passwordsent' => "A new password has been sent to the e-mail address registered for \"$1\".  Please log in again after you receive it.",
#'perfcached' => "The following data is cached and may not be completely up to date:",
#'perfdisabled' => "Sorry! This feature has been temporarily disabled because it slows the database down to the point that no one can use the wiki.",
#'perfdisabledsub' => "Here's a saved copy from $1:",
'personaltools' => "Tenglar",
#'popularpages' => "Popular pages",
'portal' => "Samfélagsgátt",
'portal-url' => "Wikipedia:Samfélagsgátt",
#'postcomment' => "Post a comment",
'poweredby' => "Wikipedia is powered by [http://www.mediawiki.org/ MediaWiki], an open source wiki engine.",
#'powersearch' => "Search",
#'powersearchtext' => "
# Search in namespaces :<br />
# $1<br />
# $2 List redirects &nbsp; Search for $3 $9",
'preferences' => "Stillingar",
'prefs-help-userdata' => "* <strong>Real name</strong> (optional): if you choose to provide it this will be used for giving you attribution for your work.<br/>
* <strong>Email</strong> (optional): Enables people to contact you through the website without you having to reveal your 
email address to them, and it can be used to send you a new password if you forget it.",
#'prefs-misc' => "Misc settings",
'prefs-personal' => "Notendaupplýsingar",
#'prefs-rc' => "Recent changes and stub display",
'prefslogintext' => "You are logged in as \"$1\".
Your internal ID number is $2.

See [[Wikipedia:User preferences help]] for help deciphering the options.",
#'prefsnologin' => "Not logged in",
'prefsnologintext' => "You must be <a href=\"/wiki/Special:Userlogin\">logged in</a>
to set user preferences.",
#'prefsreset' => "Preferences have been reset from storage.",
#'preview' => "Preview",
#'previewconflict' => "This preview reflects the text in the upper text editing area as it will appear if you choose to save.",
#'previewnote' => "Remember that this is only a preview, and has not yet been saved!",
#'prevn' => "previous $1",
'printableversion' => "Prentvæn útgáfa",
'printsubtitle' => "(From http://is.wikipedia.org)",
'protect' => "Vernda",
#'protectcomment' => "Reason for protecting",
#'protectedarticle' => "protected [[$1]]",
#'protectedpage' => "Protected page",
'protectedpagewarning' => "WARNING:  This page has been locked so that only
users with sysop privileges can edit it. Be sure you are following the
<a href='/w/wiki.phtml/Wikipedia:Protected_page_guidelines'>protected page
guidelines</a>.",
'protectedtext' => "This page has been locked to prevent editing; there are
a number of reasons why this may be so, please see
[[Wikipedia:Protected page]].

You can view and copy the source of this page:",
#'protectlogpage' => "Protection_log",
'protectlogtext' => "Below is a list of page locks/unlocks.
See [[Wikipedia:Protected page]] for more information.",
'protectpage' => "Vernda síðu",
#'protectreason' => "(give a reason)",
#'protectsub' => "(Protecting \"$1\")",
#'protectthispage' => "Protect this page",
#'proxyblocker' => "Proxy blocker",
#'proxyblockreason' => "Your IP address has been blocked because it is an open proxy. Please contact your Internet service provider or tech support and inform them of this serious security problem.",
#'proxyblocksuccess' => "Done.  ",
#'qbbrowse' => "Browse",
'qbedit' => "Breyta",
#'qbfind' => "Find",
#'qbmyoptions' => "My pages",
#'qbpageinfo' => "Context",
#'qbpageoptions' => "This page",
'qbsettings' => "Valblaðsstillingar",
#'qbsettingsnote' => "This preference only works in the 'Standard' and the 'CologneBlue' skin.",
#'qbspecialpages' => "Special pages",
#'querybtn' => "Submit query",
#'querysuccessful' => "Query successful",
'randompage' => "Handahófsvalin síða",
#'range_block_disabled' => "The sysop ability to create range blocks is disabled.",
#'rchide' => "in $4 form; $1 minor edits; $2 secondary namespaces; $3 multiple edits.",
'rclinks' => "Sýna síðustu $1 breytingar síðustu $2 daga<br />$3",
'rclistfrom' => "Sýna breytingar frá og með $1",
#'rcliu' => "; $1 edits from logged in users",
#'rcloaderr' => "Loading recent changes",
#'rclsub' => "(to pages linked from \"$1\")",
'rcnote' => "Að neðan eru síðustu <strong>$1</strong> breytingar síðustu <strong>$2</strong> daga.",
#'rcnotefrom' => "Below are the changes since <b>$2</b> (up to <b>$1</b> shown).",
#'readonly' => "Database locked",
#'readonlytext' => "The database is currently locked to new entries and other modifications, probably for routine database maintenance, after which it will be back to normal.  The administrator who locked it offered this explanation: <p>$1",
#'readonlywarning' => "WARNING: The database has been locked for maintenance, so you will not be able to save your edits right now. You may wish to cut-n-paste the text into a text file and save it for later.",
'recentchanges' => "Nýlegar breytingar",
#'recentchangescount' => "Number of titles in recent changes",
'recentchangeslinked' => "Skyldar breytingar",
#'recentchangestext' => "Track the most recent changes to the wiki on this page.",
#'redirectedfrom' => "(Redirected from $1)",
'remembermypassword' => "Muna lykilorðið milli heimsókna.",
'removechecked' => "Fjarlægja merktar síður af vaktlistanum",
'removedwatch' => "Fjarlægt af vaktlistanum",
'removedwatchtext' => "Síðan \"$1\" hefur verið fjarlægð af vaktlistanum.",
'removingchecked' => "Fjarlægi umbeðnar síðu(r) af vaktlistanum...",
#'resetprefs' => "Reset preferences",
#'restorelink' => "$1 deleted edits",
#'resultsperpage' => "Hits to show per page",
#'retrievedfrom' => "Retrieved from \"$1\"",
'returnto' => "Tilbaka: $1.",
'retypenew' => "Endurtaktu nýja lykilorðið",
#'reupload' => "Re-upload",
#'reuploaddesc' => "Return to the upload form.",
#'reverted' => "Reverted to earlier revision",
#'revertimg' => "rev",
#'revertpage' => "Reverted edit of $2, changed back to last version by $1",
#'revhistory' => "Revision history",
#'revisionasof' => "Revision as of $1",
#'revnotfound' => "Revision not found",
#'revnotfoundtext' => "The old revision of the page you asked for could not be found.  Please check the URL you used to access this page.  ",
#'rfcurl' => "http://www.faqs.org/rfcs/rfc$1.html",
#'rights' => "Rights:",
#'rollback' => "Roll back edits",
#'rollback_short' => "Rollback",
#'rollbackfailed' => "Rollback failed",
#'rollbacklink' => "rollback",
#'rows' => "Rows",
'savearticle' => "Vista",
'savedprefs' => "Stillingarnar þínar hafa verið vistaðar.",
#'savefile' => "Save file",
#'saveprefs' => "Save preferences",
'search' => "Leit",
#'searchdisabled' => "<p>Sorry! Full text search has been disabled temporarily, for performance reasons. In the meantime, you can use the Google search below, which may be out of date.</p>",
#'searchquery' => "For query \"$1\"",
#'searchresults' => "Search results",
#'searchresultshead' => "Search result settings",
'searchresulttext' => "For more information about searching {{SITENAME}}, see [[Project:Searching|Searching {{SITENAME}}]].",
#'sectionedit' => " (section)",
#'selectnewerversionfordiff' => "Select a newer version for comparison",
#'selectolderversionfordiff' => "Select an older version for comparison",
#'selectonly' => "Only read-only queries are allowed.",
#'selflinks' => "Pages with Self Links",
#'selflinkstext' => "The following pages contain a link to themselves, which they should not.",
#'seriousxhtmlerrors' => "There were serious xhtml markup errors detected by tidy.",
#'servertime' => "Server time is now",
#'set_rights_fail' => "<b>User rights for \"$1\" could not be set. (Did you enter the name correctly?)</b>",
#'set_user_rights' => "Set user rights",
#'setbureaucratflag' => "Set bureaucrat flag",
'shortpages' => "Stuttar síður",
'show' => "Sýna",
'showhideminor' => "$1 minniháttar breytingar | $2 breytingar eftir vélmenni | $3 breytingar eftir notendur",
#'showingresults' => "Showing below <b>$1</b> results starting with #<b>$2</b>.",
#'showingresultsnum' => "Showing below <b>$3</b> results starting with #<b>$2</b>.",
'showlast' => "Sýna síðustu $1 skrár raðaðar $2.",
'showpreview' => "Forsýn",
#'showtoc' => "show",
#'sig_tip' => "Your signature with timestamp",
#'sitestats' => "Site statistics",
'sitestatstext' => "There are '''$1''' total pages in the database.
This includes \"talk\" pages, pages about Wikipedia, minimal \"stub\"
pages, redirects, and others that probably don't qualify as content pages.
Excluding those, there are '''$2''' pages that are probably legitimate
content pages.

There have been a total of '''$3''' page views, and '''$4''' page edits
since the wiki was setup.
That comes to '''$5''' average edits per page, and '''$6''' views per edit.",
'sitesubtitle' => "Frjálsa alfræðiorðabókin",
'sitesupport' => "Framlög",
'sitetitle' => "Wikipedia",
#'siteuser' => "Wikipedia user $1",
#'siteusers' => "Wikipedia user(s) $1",
'skin' => "Þema",
'spamprotectiontext' => "The page you wanted to save was blocked by the spam filter. This is probably caused by a link to an external site. 

You might want to check the following regular expression for patterns that are currently blocked:",
#'spamprotectiontitle' => "Spam protection filter",
#'specialpage' => "Special Page",
'specialpages' => "Sérstakar síður",
#'spheading' => "Special pages for all users",
#'sqlislogged' => "Please note that all queries are logged.",
#'sqlquery' => "Enter query",
'statistics' => "Tölfræði",
#'storedversion' => "Stored version",
#'stubthreshold' => "Threshold for stub display",
'subcategories' => "Undirflokkar",
#'subcategorycount' => "There are $1 subcategories to this category.",
#'subject' => "Subject/headline",
#'subjectpage' => "View subject",
#'successfulupload' => "Successful upload",
'summary' => "Breytingar",
'sysopspheading' => "Aðeins fyrir stjórnendur",
#'sysoptext' => "The action you have requested can only be performed by users with \"sysop\" status.  See $1.",
#'sysoptitle' => "Sysop access required",
#'tableform' => "table",
'talk' => "Umræða",
#'talkexists' => "The page itself was moved successfully, but the talk page could not be moved because one already exists at the new title. Please merge them manually.",
'talkpage' => "Ræða um þessa síðu",
#'talkpagemoved' => "The corresponding talk page was also moved.",
'talkpagenotmoved' => "Samsvarandi spjallsíða var <strong>ekki</strong> færð.",
#'talkpagetext' => "<!-- MediaWiki:talkpagetext -->",
'textboxsize' => "Textbox dimensions",
#'textmatches' => "Page text matches",
#'thisisdeleted' => "View or restore $1?",
#'thumbnail-more' => "Enlarge",
'timezonelegend' => "Tímabelti",
#'timezoneoffset' => "Offset",
#'timezonetext' => "Enter number of hours your local time differs from server time (UTC).",
#'titlematches' => "Article title matches",
'toc' => "Efnisyfirlit",
'toolbox' => "Verkfæri",
#'tooltip-compareselectedversions' => "See the differences between the two selected versions of this page. [alt-v]",
#'tooltip-minoredit' => "Mark this as a minor edit [alt-i]",
#'tooltip-preview' => "Preview your changes, please use this before saving! [alt-p]",
#'tooltip-save' => "Save your changes [alt-s]",
#'tooltip-search' => "Search this wiki [alt-f]",
#'uclinks' => "View the last $1 changes; view the last $2 days.",
#'ucnote' => "Below are this user's last <b>$1</b> changes in the last <b>$2</b> days.",
#'uctop' => " (top)",
#'unblockip' => "Unblock user",
#'unblockiptext' => "Use the form below to restore write access to a previously blocked IP address or username.",
#'unblocklink' => "unblock",
#'unblocklogentry' => "unblocked \"$1\"",
#'undelete' => "Restore deleted page",
#'undelete_short' => "Undelete $1 edits",
#'undeletearticle' => "Restore deleted page",
#'undeletebtn' => "Restore!",
#'undeletedarticle' => "restored \"$1\"",
'undeletedtext' => "[[$1]] has been successfully restored.
See [[Wikipedia:Deletion_log]] for a record of recent deletions and restorations.",
#'undeletehistory' => "If you restore the page, all revisions will be restored to the history.  If a new page with the same name has been created since the deletion, the restored revisions will appear in the prior history, and the current revision of the live page will not be automatically replaced.",
#'undeletepage' => "View and restore deleted pages",
#'undeletepagetext' => "The following pages have been deleted but are still in the archive and can be restored. The archive may be periodically cleaned out.",
#'undeleterevision' => "Deleted revision as of $1",
#'undeleterevisions' => "$1 revisions archived",
#'unexpected' => "Unexpected value: \"$1\"=\"$2\".",
#'unlockbtn' => "Unlock database",
#'unlockconfirm' => "Yes, I really want to unlock the database.",
#'unlockdb' => "Unlock database",
#'unlockdbsuccesssub' => "Database lock removed",
#'unlockdbsuccesstext' => "The database has been unlocked.",
#'unlockdbtext' => "Unlocking the database will restore the ability of all users to edit pages, change their preferences, edit their watchlists, and other things requiring changes in the database.  Please confirm that this is what you intend to do.",
'unprotect' => "Afvernda",
#'unprotectcomment' => "Reason for unprotecting",
#'unprotectedarticle' => "unprotected [[$1]]",
#'unprotectsub' => "(Unprotecting \"$1\")",
#'unprotectthispage' => "Unprotect this page",
'unusedimages' => "Ónotaðar skrár",
#'unusedimagestext' => "<p>Please note that other web sites may link to an image with a direct URL, and so may still be listed here despite being in active use.",
'unwatch' => "Afvakta",
#'unwatchthispage' => "Stop watching",
#'updated' => "(Updated)",
'upload' => "Hlaða inn skrá",
#'uploadbtn' => "Upload file",
#'uploaddisabled' => "Sorry, uploading is disabled.",
#'uploadedfiles' => "Uploaded files",
#'uploadedimage' => "uploaded \"$1\"",
#'uploaderror' => "Upload error",
'uploadfile' => "Hlaða inn mynd, hljóðskrá, skjali o.s.f.",
#'uploadlink' => "Upload images",
#'uploadlog' => "upload log",
#'uploadlogpage' => "Upload_log",
#'uploadlogpagetext' => "Below is a list of the most recent file uploads.  All times shown are server time (UTC).  <ul> </ul> ",
'uploadnologin' => "Óinnskráð(ur)",
'uploadnologintext' => "You must be <a href=\"/wiki/Special:Userlogin\">logged in</a>
to upload files.",
#'uploadwarning' => "Upload warning",
#'usenewcategorypage' => "1
#
#Set first character to \"0\" to disable the new category page layout.",
#'user_rights_set' => "<b>User rights for \"$1\" updated</b>",
#'usercssjsyoucanpreview' => "<strong>Tip:</strong> Use the 'Show preview' button to test your new css/js before saving.",
#'usercsspreview' => "'''Remember that you are only previewing your user css, it has not yet been saved!'''",
#'userexists' => "The user name you entered is already in use. Please choose a different name.",
#'userjspreview' => "'''Remember that you are only testing/previewing your user javascript, it has not yet been saved!'''",
'userlogin' => "Innskrá",
'userlogout' => "Útskrá",
#'usermailererror' => "Mail object returned error: ",
#'userpage' => "View user page",
#'userstats' => "User statistics",
#'userstatstext' => "There are '''$1''' registered users.  '''$2''' of these are administrators (see $3).",
'version' => "Útgáfa",
#'viewcount' => "This page has been accessed $1 times.",
#'viewprevnext' => "View ($1) ($2) ($3).",
#'viewsource' => "View source",
'viewtalkpage' => "Skoða umræðu",
'wantedpages' => "Eftirsóttar síður",
'watch' => "Vakta",
'watchdetails' => "($1 síður vaktaðar fyrir utan spjallsíður;
$2 samtals breyttar síður frá síðasta cutoff;
$3...
<a href='$4'>sýna og breyta heildarlistanum</a>.)",
'watcheditlist' => "Þetta er listi yfir þínar vöktuðu síður raðað í
stafrófsröð. Merktu við þær síður sem þú vilt fjarlægja
af vaktlistanum og ýttu á 'fjarlægja merktar' takkan
neðst á skjánum.",
'watchlist' => "Vaktlisti",
'watchlistcontains' => "Á vaktlistanum eru $1 síður.",
'watchlistsub' => "(fyrir notandan \"$1\")",
'watchmethod-list' => "leita að breytingum í vöktuðum síðum",
'watchmethod-recent' => "kanna hvort nýlegar breytingar innihalda vaktaðar síður",
'watchnochange' => "Engri síðu á vaktlistanum þínum hefur verið breytt á tilgreindu tímabili.",
'watchnologin' => "Óinnskráð(ur)",
'watchnologintext' => "Þú verður að vera [[Special:Userlogin|skráður inn]] til að geta breytt vaktlistanum.",
'watchthis' => "Vakta",
'watchthispage' => "Vakta þessa síðu",
'welcomecreation' => "<h2>Welcome, $1!</h2><p>Your account has been created.
Don't forget to change your Wikipedia preferences.",
'whatlinkshere' => "Hvað tengist hingað",
#'whitelistacctext' => "To be allowed to create accounts in this Wiki you have to [[Special:Userlogin|log]] in and have the appropriate permissions.",
'whitelistacctitle' => "Þér er óheimilt að skrá þig sem notanda.",
'whitelistedittext' => "Þú verður að [[Special:Userlogin|skrá þig inn]] til að geta breytt síðum.",
'whitelistedittitle' => "Þú verður að skrá þig inn til að geta breytt síðum.",
'whitelistreadtext' => "Þú verður að [[Special:Userlogin|skrá þig inn]] til að lesa síður.",
'whitelistreadtitle' => "Notandi verður að skrá sig inn til að geta lesið.",
'wikipediapage' => "Sýna verkefnissíðu",
'wikititlesuffix' => "Wikipedia, frjálsa alfræðiorðabókin",
'wlnote' => "Að neðan eru síðustu <b>$1</b> breytingar síðustu <b>$2</b> klukkutíma.",
'wlsaved' => "Þetta er vistuð útgáfa af vaktlistanum þínum.",
'wlshowlast' => "Sýna síðustu $1 klukkutíma, $2 daga, $3",
#'wrong_wfQuery_params' => "Incorrect parameters to wfQuery()<br /> Function: $1<br /> Query: $2 ",
'wrongpassword' => "Uppgefið lykilorð er rangt. Vinsamlegast reyndu aftur.",
#'yourdiff' => "Differences",
'youremail' => "Tölvupóstfangið þitt*",
'yourname' => "Notendanafn",
'yournick' => "Gælunafnið þitt (fyrir undirskriftir)",
'yourpassword' => "Lykilorð",
'yourpasswordagain' => "Lykilorð (aftur)",
'yourrealname' => "Fullt nafn þitt*",
#'yourtext' => "Your text",
# Math
	'mw_math_png' => "Alltaf birta PNG mynd",
	'mw_math_simple' => "HTML fyrir einfaldar jöfnur annars PNG",
	'mw_math_html' => "HTML ef hægt er, annars PNG",
	'mw_math_source' => "Sýna TeX jöfnu (fyrir textavafra)",
	'mw_math_modern' => "Mælt með fyrir nýja vafra",
	'mw_math_mathml' => 'MathML',
);


#--------------------------------------------------------------------------
# Internationalisation code
#--------------------------------------------------------------------------

class LanguageIs extends LanguageUtf8 {

	function getDefaultUserOptions () {
		global $wgDefaultUserOptionsIs;
		return $wgDefaultUserOptionsIs;
		}

	function getBookstoreList () {
		global $wgBookstoreListIs;
		return $wgBookstoreListIs;
	}

	function getNamespaces() {
		global $wgNamespaceNamesIs;
		return $wgNamespaceNamesIs;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesIs;
		return $wgNamespaceNamesIs[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesIs;

		foreach ( $wgNamespaceNamesIs as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsIs;
		return $wgQuickbarSettingsIs;
	}

	function getSkinNames() {
		global $wgSkinNamesIs;
		return $wgSkinNamesIs;
	}

	function getDateFormats() {
		global $wgDateFormatsIs;
		return $wgDateFormatsIs;
	}

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesIs;
		return $wgValidSpecialPagesIs;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesIs;
		return $wgSysopSpecialPagesIs;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesIs;
		return $wgDeveloperSpecialPagesIs;
	}

	function getMessage( $key )
	{
		global $wgAllMessagesIs;
		if( isset( $wgAllMessagesIs[$key] ) ) {
			return $wgAllMessagesIs[$key];
		} else {
			return "";
		}
	}
	
	function getAllMessages()
	{
		global $wgAllMessagesIs;
		return $wgAllMessagesIs;
	}

	function getMagicWords() 
	{
		global $wgMagicWordsIs;
		return $wgMagicWordsIs;
	}
}

# @include_once( "Language" . ucfirst( $wgLanguageCode ) . ".php" );

?>

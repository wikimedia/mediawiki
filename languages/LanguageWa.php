<?php

require_once( "LanguageUtf8.php" );

# NOTE: cweri après "NOTE:" po des racsegnes so des ratournaedjes
# k' i gn a.

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => "-"

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#
/* private */ $wgNamespaceNamesWa = array(
	NS_MEDIA          => "Media", /* Media */
	NS_SPECIAL        => "Sipeciås", /* Special */
	NS_MAIN           => "",
	NS_TALK           => "Copene", /* Talk */
	NS_USER	          => "Uzeu", /* User */
	NS_USER_TALK      => "Uzeu_copene", /* User_talk */
	NS_WIKIPEDIA      => "Wikipedia", /* Wikipedia */ 
	NS_WIKIPEDIA_TALK => "Wikipedia_copene", /* Wikipedia_talk */
	NS_IMAGE          => "Imådje", /* Image */
	NS_IMAGE_TALK     => "Imådje_copene", /* Image_talk */
	NS_MEDIAWIKI      => "MediaWiki", /* MediaWiki */
	NS_MEDIAWIKI_TALK => "MediaWiki_copene", /* MediaWiki_talk */
	NS_TEMPLATE       => "Modele",
	NS_TEMPLATE_TALK  => "Modele_copene",
	NS_HELP           => "Aidance",
	NS_HELP_TALK      => "Aidance_copene",
	NS_CATEGORY       => "Categoreye",
	NS_CATEGORY_TALK  => "Categoreye_copene",
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsWa = array(
	"Nole bår", "Aclawêye a hintche", "Aclawêye a droete", "Flotante a hintche"
);

/* private */ $wgSkinNamesWa = array(
	"Sitandård", "Nostaldjike", "Bleu Cologne", "Paddington", "Montparnasse"
);


/* private */ $wgDateFormatsWa = array( /* cwè fé chal ??? */
	"Nole preferince",
	"January 15, 2001",
	"15 January 2001",
	"2001 January 15",
	"2001-01-15"
);

/* private */ $wgBookstoreListWa = array(
	"AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
	"PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
	"Barnes & Noble" => "http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	"Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1"
);


# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
####
#### Li no des pådjes ni s' pout nén (co?) ratourner
#### Name of the pages is not (yet?) translatable
####
/* private */ $wgValidSpecialPagesWa = array(
	"Userlogin"	=> "",
	"Userlogout"	=> "",
	"Preferences"	=> "Candjî mes preferinces",
	"Watchlist"	=> "Mes pådjes shuvowes",
	"Recentchanges" => "Dierins candjmints",
	"Upload"	=> "Eberweter des imådjes",
	"Imagelist"	=> "Djivêye des imådjes",
	"Listusers"	=> "Uzeus eredjîstrés",
	"Statistics"	=> "Sitatistikes del waibe",
	"Randompage"	=> "Årtike a l'&nbsp;astcheyance",

	"Lonelypages"	=> "Årtikes ôrfulins",
	"Unusedimages"	=> "Imådjes ôrfulinnes",
	"Popularpages"	=> "Årtikes les pus léjhous",
	"Wantedpages"	=> "Årtikes les pus rcwerous",
	"Shortpages"	=> "Årtikes les pus courts",
	"Longpages"	=> "Årtikes les pus longous",
	"Newpages"	=> "Årtikes novelmint askepyîs",
	"Ancientpages"	=> "Årtikes les pus vîs",
	"Intl"		=> "Loyéns eterlingaedjes",
	"Allpages"	=> "Totes les påjdes reléjhowes sol tite",

	"Ipblocklist"	=> "Uzeus/adresses IP di blokés",
	"Maintenance"	=> "Pådje di manaedjmint",
	"Specialpages"  => "",
	"Contributions" => "",
	"Emailuser"	=> "",
	"Whatlinkshere" => "",
	"Recentchangeslinked" => "",
	"Movepage"	=> "",
	"Booksources"	=> "External book sources", # co a fé
	"Categories"	=> "Categoreyes des pådjes",
	"Export"	=> "XML page export", # co a fé
	
);

/* private */ $wgSysopSpecialPagesWa = array(
	"Makesysop"		=> "Diner a èn uzeu on livea di manaedjeu",
	"Blockip"		=> "Bloker èn uzeu/ene adresse IP",
	"Asksql"		=> "Query the database", # co a fé
	"Undelete"		=> "Rapexhî des disfacêyès pådjes"
);

/* private */ $wgDeveloperSpecialPagesWa = array(
	"Lockdb"		=> "Mete li båze di dnêyes e môde seulmint-lére",
	"Unlockdb"		=> "Rimete l'&nbsp;accès po scrire al båze di dnêyes",
	"Debug"			=> "Informåcion di disbugaedje"
);

/* private */ $wgAllMessagesWa = array(
'special_version_prefix' => '',
'special_version_postfix' => '',
# User Toggles

"tog-hover"		=> "Racsegnes cwand on passe so les loyéns",
"tog-underline" => "Sorlignî les loyéns",
"tog-highlightbroken" => "Håyner les vudes loyéns <a href=\"\" class=\"new\">come çouchal</a><br> &nbsp;&nbsp;&nbsp; (oudonbén: come çouchal<a href=\"\" class=\"internal\">?</a>).",
"tog-justify"	=> "Djustifyî les hagnons",
"tog-hideminor" => "Èn nén moster les <i>dierins candjmints</i> mineurs", 
"tog-usenewrc" => "Ramidrés <i>dierins candjmints</i> (nén tos les betchteus)",
"tog-numberheadings" => "Limerotaedje otomatike des tites",
"tog-editondblclick" => "Candjî les pådjes avou on dobe-clitch (JavaScript)",
"tog-editsection" => "Eployî les loyéns «[candjî]» po candjî rén k'&nbsp;ene seccion",
"tog-editsectiononrightclick" => "Candjî les seccions avou on dobe-clitch sol tite (JavaScript)",
"tog-showtoc" => "Mostrer l'&nbsp;tåvlea d'&nbsp;ådvins<br>(po ls årtikes avou pus di 3 seccions)",
"tog-rememberpassword" => "Rimimbrer li scret inte les sessions",
"tog-editwidth" => "Li boesse d'&nbsp;aspougnaedje prind tote li lårdjeu",
"tog-watchdefault" => "Shuve les årtikes ki dj'&nbsp;fwai ou ki dj'&nbsp;candje",
"tog-minordefault" => "Prémete mes candjmints come mineurs",
"tog-previewontop" => "Prévey l'&nbsp;årtike å dzeu del boesse d'&nbsp;aspougnaedje",
"tog-nocache" => "Èn nén eployî d'&nbsp;muchete pol håynaedje des pådjes",
# Dates
'sunday' => "dimegne",
'monday' => "londi",
'tuesday' => "mårdi",
'wednesday' => "mierkidi",
'thursday' => "djudi",
'friday' => "vénrdi",
'saturday' => "semdi",
'january' => "djanvî",
'february' => "fevrî",
'march' => "måss",
'april' => "avri",
'may_long' => "may",
'june' => "djun",
'july' => "djulete",
'august' => "awousse",
'september' => "setimbe",
'october' => "octôbe",
'november' => "nôvimbe",
'december' => "decimbe",
'jan' => "dja",
'feb' => "fev",
'mar' => "mås",
'apr' => "avr",
'may' => "may",
'jun' => "djn",
'jul' => "djl",
'aug' => "awo",
'sep' => "set",
'oct' => "oct",
'nov' => "nôv",
'dec' => "dec",


# Bits of text used by many pages:
#
# the [] is only for *bytes*, real chars should be one by one :-(
#"linktrail"     => "/^(?:å|â|ê|î|ô|û|ç|é|è|[a-z]+)(.*)\$/sD",
"linktrail"     => "/^(å|â|ê|î|ô|û|ç|é|è|[a-z]+)(.*)\$/sD",
"categories"	=> "Categoreyes des pådjes",
"category"	=> "categoreye",
"category_header" => "Årtikes el categoreye «%s»",
"subcategories"	=> "Dizo-categoreyes",
"mainpage"		=> "Mwaisse pådje",
"mainpagetext"	=> "Li programe Wiki a stî astalé a l'&nbsp;idêye.",
"about"			=> "Å dfait",
"aboutwikipedia" => "Å dfait di Wikipedia",
"aboutpage"		=> "Wikipedia:Å dfait",
"help"			=> "Aidance",
"helppage"		=> "Wikipedia:Aidance",
"wikititlesuffix" => "Wikipedia",
"bugreports"	=> "Rapoirts di bugs",
"bugreportspage" => "Wikipedia:Rapoirts di bugs",
"sitesupport"	=> "Ecwårlaedje",
"sitesupportpage" => "", # If not set, won't appear. Can be wiki page or URL
"faq"			=> "FAQ", # co a fé
"faqpage"		=> "Wikipedia:FAQ", # co a fé
"edithelp"		=> "Aidance",
"edithelppage"	=> "Wikipedia:Kimint_candjî_ene_pådje",
"cancel"		=> "Rinoncî",
"qbfind"		=> "Trover",
"qbbrowse"		=> "Foyter",
"qbedit"		=> "Candjî",
"qbpageoptions"		=> "Cisse pådje ci",
"qbpageinfo"		=> "Contecse",
"qbmyoptions"		=> "Mes pådjes",
"qbspecialpages"	=> "Pådjes sipeciåles",
"moredotdotdot"		=> "Co dpus...",
"mypage"		=> "Mi pådje",
"mytalk"		=> "Mi copinaedje",
#"currentevents" => "-", /* pol dismete */
"currentevents" => "Actouwålités",
"errorpagetitle" => "Aroke",
"returnto"		=> "Rivni al pådje «$1».",
"fromwikipedia"	=> "Èn årtike di Wikipedia, li libe eciclopedeye.",
"help"			=> "Aidance",
"search"		=> "Cweri",
"history"		=> "Istwere del pådje",
"printableversion" => "Modêye sicrirece-amishtåve",
"editthispage"	=> "Candjî&nbsp;l'&nbsp;pådje",
"deletethispage" => "Disfacer l'&nbsp;pådje",
"protectthispage" => "Protedjî l'&nbsp;pådje",
"unprotectthispage" => "Disprotedjî l'&nbsp;pådje",
"newpage" => "Novele pådje",
"talkpage"	=> "Copene sol pådje",
"postcomment"	=> "Sicrire on comintaire",
"articlepage"	=> "Vey l'&nbsp;årtike",
"subjectpage"	=> "Vey li sudjet", # For compatibility
"userpage" => "Vey li pådje di l'&nbsp;uzeu",
"wikipediapage" => "Vey li meta-pådje",
"imagepage" => 	"Vey li pådje di l'&nbsp;imådje",
"viewtalkpage" => "Vey li pådje di copene",
"otherlanguages" => "Ôtes lingaedjes",
"redirectedfrom" => "(Redjiblé di $1)",
"lastmodified"	=> "Cisse pådje a stî candjeye pol dierin côp li $1.",
"viewcount"		=> "Cisse pådje la a stî léjhowe $1 côps.",
"gnunote" => "Tos les tecses chal sont dizo l'&nbsp;libe licince <a class=internal href='/wiki/GFDL'>GFDL (licince di documintåcion libe di GNU)</a>.",
"printsubtitle" => "(di http://wikipedia.walon.org)",
"protectedpage" => "Pådje protedjeye",
"administrators" => "Wikipedia:Manaedjeus",
"sysoptitle"	=> "I vs fåt esse manaedjeu",
"sysoptext"		=> "The action you have requested can only be
performed by users with \"sysop\" status.
See $1.", # co a fé
"developertitle" => "I vs fåt esse diswalpeu",
"developertext"	=> "The action you have requested can only be
performed by users with \"developer\" status.
See $1.", # co a fé
"nbytes"		=> "$1 octets",
"go"			=> "Potchî",
"ok"			=> "'l est bon",
"sitetitle"		=> "Wikipedia",
"sitesubtitle"	=> "Li libe eciclopedeye",
"retrievedfrom" => "Prin del pådje «$1»",
"newmessages" => "Vos avoz des $1.",
"newmessageslink" => "noveas messaedjes",
"editsection" => "candjî",
"toc" => "Ådvins",
"showtoc" => "mostrer",
"hidetoc" => "catchî",
"thisisdeleted" => "Vey ou rapexhî $1?",
"restorelink" => "$1 candjmints disfacés",

# Main script and global functions
#
"nosuchaction"	=> "Nole sifwaite accion",
"nosuchactiontext" => "L'&nbsp;accion specifieye pal hårdêye n'&nbsp;est
nén ricnoxhowe på wiki.",
"nosuchspecialpage" => "Nole sifwaite pådje",
"nospecialpagetext" => "Vos avoz dmandé ene pådje sipeciåle ki n'&nbsp;est
nén ricnoxhowe på wiki.",

# General errors
#
"error"			=> "Aroke",
"databaseerror" => "Åk n'&nbsp;a nén stî avou l'&nbsp;båze di dnêyes",
"dberrortextcl" => "A database query syntax error has occurred.
The last attempted database query was:
\"$1\"
from within function \"$2\".
MySQL returned error \"$3: $4\".\n", # co a fé
"noconnect"		=> "Mande escuzes! Li wiki a des rujhes tecnikes pol moumint, eyet c'&nbsp;est nén possibe di s'&nbsp;raloyî al båze di dnêyes.",
"nodb"			=> "Dji n'&nbsp;sai tchoezi l'&nbsp;båze di dnêyes $1",
"cachederror"		=> "The following is a cached copy of the requested page, and may not be up to date.", # co a fé
"readonly"		=> "Database locked", # co a fé
"enterlockreason" => "Enter a reason for the lock, including an estimate
of when the lock will be released", # co a fé
"readonlytext"	=> "The database is currently locked to new
entries and other modifications, probably for routine database maintenance,
after which it will be back to normal.
The administrator who locked it offered this explanation:
<p>$1", # co a fé
"missingarticle" => "The database did not find the text of a page
that it should have found, named \"$1\".

<p>This is usually caused by following an outdated diff or history link to a
page that has been deleted.

<p>If this is not the case, you may have found a bug in the software.
Please report this to an administrator, making note of the URL.", # co a fé
"internalerror" => "Divintrinne aroke",
"filecopyerror" => "Dji n'&nbsp;a savou copyî l'&nbsp;fitchî «$1» viè «$2».",
"filerenameerror" => "Dji n'&nbsp;a savou rlomer l'&nbsp;fitchî «$1» e «$2».",
"filedeleteerror" => "Dji n'&nbsp;a savou disfacer l'&nbsp;fitchî «$1».",
"filenotfound"	=> "Dji n'&nbsp;a savou trover l'&nbsp;fitchî «$1».",
"unexpected"	=> "Valixhance nén ratindowe: «$1»=«$2».",
"formerror"		=> "Error: could not submit form", # co a fé
"badarticleerror" => "This action cannot be performed on this page.", # co a fé
"cannotdelete"	=> "Could not delete the page or image specified. (It may have already been deleted by someone else.)", # co a fé
"badtitle"		=> "Måva tite",
"badtitletext"	=> "The requested page title was invalid, empty, or
an incorrectly linked inter-language or inter-wiki title.", # co a fé
"perfdisabled" => "Mande escuzes! mins cisse fonccionålité ci a stî essoctêye
pol moumint pask'&nbsp;ele est trop pezante pol båze di dnêyes, ki dvént
si télmint londjinne k'&nbsp;on s'&nbsp;endè pout pus siervi a môde di djin.",
"perfdisabledsub" => "Vochal ene copeye k'&nbsp;a stî schapêye di $1:",
"wrong_wfQuery_params" => "Parametes incoreks po wfQuery()<br />
Fonccion: $1<br />
Cweraedje: $2
",
"viewsource" => "Vey côde sourdant",
"protectedtext" => "Cisse pådje chal a stî protedjeye siconte des candjmints;
i gn a sacwantès råjhons po çoula, loukîz 
[[{{ns:4}}:Pådje protedjeye]] s'&nbsp;i vs plait.

Mins nerén vos ploz vey eyet copyî li côde sourdant del pådje:",


# Login and logout pages
#
"logouttitle"	=> "Dislodjaedje",
"logouttext"	=> "Vos vs avoz dislodjî.
Vos ploz continouwer a naivyî so {{SITENAME}} anonimmint, oudonbén
vos relodjî dizo l'&nbsp;minme uzeu ou dizo èn uzeu diferin.",

"welcomecreation" => "<h2>Bénvnowe, $1!</h2>
<p>
Vosse conte a stî ahivé.
Èn rovyîz nén di candjî les preferinces di {{SITENAME}} a vosse môde.",

"loginpagetitle" => "Elodjaedje",
"yourname"		=> "Vosse no d'&nbsp;elodjaedje",
"yourpassword"	=> "Vosse sicret",
"yourpasswordagain" => "Ritapez vosse sicret",
"newusersonly"	=> " (seulmint po les noveas uzeus)",
"remembermypassword" => "Rimimbrer m'&nbsp;sicret inte les sessions.",
"loginproblem"	=> "<b>Åk n'&nbsp;a nén stî tot vs elodjant.</b><br />Rissayîz!",
"alreadyloggedin" => "<font color=red><b>Uzeu $1, vos estoz ddja elodjî!</b></font><br />",

"areyounew"		=> "Si vos estoz novea so {{SITENAME}} et ki vos vloz
on conte d'&nbsp;uzeu, tapez on no no d'&nbsp;uzeu ki vos vloz eployî,
et poy deus côps on on scret (li minme, on côp dins tchake boesse).
Come rawete vos ploz (mins c'&nbsp;est nén oblidjî) dner voste adresse
emile; ça vént a pont si vos rovyîz vosse sicret, po dmander k'&nbsp;on
vs evoye èn ôte pa emile.<br>\n",

"login"			=> "S'&nbsp;elodjî",
"loginprompt"		=> "Vos dvoz permete les coûkes po vs elodjî so Wikipedia.",
"userlogin"		=> "S' elodjî", # pont d' "nbsp" chal
"logout"		=> "Si dislodjî",
"userlogout"	=> "Si dislodjî",
"notloggedin"	=> "Nén elodjî",
"createaccount"	=> "Ahiver on novea conte",
"createaccountmail" => "pa emile",
"badretype"		=> "Vos avoz dné deus screts diferins.",
"userexists"	=> "Li no d'&nbsp;uzeu ki vs avoz tchoezi est ddja eployî. Tchoezixhoz è èn ôte s'&nbsp;i vs plait.",
"youremail"		=> "Vost emile*",
"yournick"		=> "Vosse no metou (po les sinateures)",
"emailforlost"	=> "Les tchamps avou ene sitoele (*) 
èn sont nén obligatweres.<br />
L'&nbsp;adresse emile, si vos ndè dnez ene, vos permetrè di rçure et 
d'&nbsp;evoyî des emiles å dtruviè di cisse waibe chal, sins vos dveur 
diner voste adresse emile, çoula vos srè eto foirt ahessåve si vos rovyîz 
måy vosse sicret (on novea scret vos pôrè-st esse evoyî pa emile).<br />
Vosse vraiy no, si vos l'&nbsp;dinez, pôrè esse eployî po mete a costé
di vos ovraedjes.",
"loginerror"	=> "Aroke d'&nbsp;elodjaedje",
"nocookiesnew" => "Li conte a stî ahivé, mins vos n'&nbsp;estoz nén elodjî. {{SITENAME}} eploye des coûkes po l'&nbsp;elodjaedje des uzeus. Vos avoz dismetou l'&nbsp;sopoirt des coûkes dins vosse betchteu waibe; rimetoz l'&nbsp;en alaedje et relodjîz vs avou vosse novea no d'&nbsp;elodjaedje eyet scret, s'&nbsp;i vs plait.",
"nocookieslogin" => "{{SITENAME}} eploye des coûkes po l'&nbsp;elodjaedje des uzeus. Vos avoz dismetou l'&nbsp;sopoirt des coûkes dins vosse betchteu waibe; rimetoz l'&nbsp;en alaedje et relodjîz vs s'&nbsp;i vs plait.",
"noname"	=> "Vos n'&nbsp;avoz nén dné di no d'&nbsp;uzeu valide.",
"loginsuccesstitle" => "Vos estoz elodjî",
"loginsuccess"	=> "L'&nbsp;elodjaedje a stî comifåt, asteure vos estoz elodjî dins {{SITENAME}} dizo l'&nbsp;no d'&nbsp;uzeu «$1».",
"nosuchuser"	=> "I gn a nou uzeu dizo l'&nbsp;no «$1».
Verifyîz çou k'&nbsp;vos avoz tapé, oudonbén rimplixhoz les ôtes tchamps
et clitchîz sol boton po-z ahiver on novea conte.",
"wrongpassword"	=> "Li scret ki vs avoz dné est måva. Rissayîz s'&nbsp;i vs plait.",
"mailmypassword" => "M'&nbsp;emiler on novea scret",
"passwordremindertitle" => "Rimimbraedje do scret po Wikipedia",
"passwordremindertext" => "Ene sakî (probåblumint vos minme, avou l' adresse IP $1)
a dmandé k' on vs emile on novea scret po vs elodjî so Wikipedia.
Li scret po l' uzeu «$2» est asteure «$3».
Po pus di såvrité, vos dvrîz vos elodjî eyet rcandjî vosse sicret å pus abeye.", # pont d' "&nbsp;" chal, ca c' est po-z esse emilé.
"noemail"		=> "I gn a pont d'&nbsp;adresse emile di cnoxhowe po l'&nbsp;uzeu «$1».",
"passwordsent"	=> "On novea scret a stî emilé a l'&nbsp;adresse emile
racsegneye po l'&nbsp;uzeu «$1».
Relodjîz vs avou ç'&nbsp;noû scret on côp ki vos l'&nbsp;åroz rçuvou s'&nbsp;i vs plait.",

# Edit pages
#
"summary"		=> "Rascourti",
"subject"		=> "Sudjet/tiestire",
"minoredit"		=> "C'&nbsp;est on candjmint mineur",
"watchthis"		=> "Shure cist årtike",
"savearticle"	=> "Schaper l'&nbsp;pådje",
"preview"		=> "Vey divant",
"showpreview"	=> "Vey divant",
"blockedtitle"	=> "L'&nbsp;uzeu est bloké",
"blockedtext"	=> "Your user name or IP address has been blocked by $1.
The reason given is this:<br>''$2''<p>You may contact $1 or one of the other
[[Wikipedia:Manaedjeus|administrators]] to discuss the block.

Note that you may not use the \"email this user\" feature unless you have a valid email address registered in your [[Sipeciås:Preferences|user preferences]].

Your IP address is $3. Please include this address in any queries you make.

==Note to AOL users==
Due to continuing acts of vandalism by one particular AOL user, Wikipedia often blocks AOL proxies. Unfortunately, a single proxy server may be used by a large number of AOL users, and hence innocent AOL users are often inadvertently blocked. We apologise for any inconvenience caused.

If this happens to you, please email an administrator, using an AOL email address. Be sure to include the IP address given above.
", # co a fé
"whitelistedittitle" => "S'&nbsp;elodjî po candjî",
"whitelistedittext" => "I vs fåt [[Sipeciås:Userlogin|elodjî]] po pleur candjî les årtikes.",
"whitelistreadtitle" => "S'&nbsp;elodjî po lére",
"whitelistreadtext" => "I vs fåt [[Sipeciås:Userlogin|elodjî]] po pleur lére les årtikes.",
"whitelistacctitle" => "Vos n'&nbsp;avoz nén l'&nbsp;permission d'&nbsp;ahiver on conte chal",
"whitelistacctext" => "Po pleur ahiver on conte so ç'&nbsp;Wiki chal, vos dvoz esse [[Sipeciås:Userlogin|elodjî]] ey aveur les bounès permissions.",
"accmailtitle" => "Li scret a stî evoyî.",
"accmailtext" => "Li scret po «$1» a stî evoyî a $2.",
"newarticle"	=> "(Novea)",
"newarticletext" =>
"Vos avoz clitchî so on loyén viè ene pådje ki n'&nbsp;egzistêye nén co.
Mins '''vos''' l'&nbsp;poloz askepyî! Po çoula, vos n'&nbsp;avoz k'&nbsp;a
cmincî a taper vosse tecse dins l'&nbsp;boesse di tecse chal pa dzo
(alez vey li [[Wikipedia:Aidance|pådje d'&nbsp;aidance]] po pus 
d'&nbsp;informåcion).
Si vos n'&nbsp;voloz nén scrire cisse pådje chal, clitchîz simplumint
sol boton '''En erî''' di vosse betchteu waibe po rivni al pådje di dvant.",
"anontalkpagetext" => "---- ''Çouchal c'&nbsp;est li pådje di copene po
èn uzeu anonime ki n'&nbsp;a nén (co) fwait on conte por lu s'&nbsp;elodjî,
ou ki nel eploye nén. Do côp, on doet eployî si [[adresse IP]] limerike po
l'&nbsp;idintifyî. Come ene sifwaite adresse IP pout esse eployeye pa pus
d'&nbsp;èn uzeu, i s'&nbsp;pout ki vos voeyoz chal des rimåkes et des
messaedjes ki n'&nbsp;sont nén por vos, loukîz s'&nbsp;i vs plait po
[[Sipeciås:Userlogin|fé on novea conte ou s'&nbsp;elodjî]] po n'&nbsp;pus
aveur di confuzion avou des ôtes uzeus anonimes.'' ",
"noarticletext" => "(I gn a pol moumint nou tecse e cisse pådje chal)",
"updated"		=> "(Ramidré)",
"note"			=> "<strong>Note:</strong> ",
"previewnote"	=> "Èn rovyîz nén ki c'&nbsp;est djusse on
prévoeyaedje, li pådje n'&nbsp;est nén co schapêye!",
"previewconflict" => "Ci prévoeyaedje ci mostere kimint kel tecse del
boesse di tecse do dzeu sereut håyné si vos decidez di clitchî so «schaper».",
"editing"		=> "Candjant $1",
"sectionedit"	=> " (seccion)",
"commentedit"	=> " (comintaire)",
"editconflict"	=> "Conflit inte deus candjmints: $1",
"explainconflict" => "Ene sakî a candjî l'&nbsp;pådje do tins ki vos
estîz a scrire.
Li boesse di tecse do dzeur mostere li tecse del pådje come il est
pol moumint sol sierveu.
Li tecse da vosse est sol boesse di tecse do dzo.
Les diferinces sont håynêyes å mitan.
Vos dvoz mete vos candjmints dins l'&nbsp;tecse d'&nbsp;asteure (å dzeur)
si vos lez vloz co evoyî.
<b>Seulmint</b> li tecse do dzeur serè candjî cwand vos clitchroz sol
boton «Schaper l'nbsp;pådje».\n<p>",
"yourtext"		=> "Li tecse da vosse",
"storedversion" => "Modêye sol sierveu",
"editingold"	=> "<strong>ASTEME: Vos estoz ki candje ene viye modêye
del pådje. Si vos l'&nbsp;schapez, tos les candjmints k'&nbsp;ont stî
fwaits dispoy adon si vont piede.</strong>\n",
"yourdiff"		=> "Diferinces",
"copyrightwarning" => "Notez ki totes les contribucions fwaites po {{SITENAME}}
dvèt esse dizo li licince di documintåcion libe di GNU
(GFDL, loukîz $1 po pus di racsegnes).
Si&nbsp;vos n'&nbsp;voloz nén ki vosse tecse poye esse candjî eyet spårdou
pa tot l'&nbsp;minme kî, adon nel evoyîz nén chal.
<br>
Vos nos acertinez eto ki vos avoz scrît l'&nbsp;tecse vos minme, oudonbén
l'&nbsp;avoz copyî d'&nbsp;on sourdant libe (dominne publik ou on sourdant
pareymint libe).
<br>
<strong>N'&nbsp;EVOYÎZ NÉN DES TECSES DIZO ABONDROETS SINS PERMISSION&nbsp;!</strong>",
"longpagewarning" => "ASTEME: Cisse pådje fwait $1 kilo-octets; des
betchteus waibes k'&nbsp;i gn a polèt aveut des rujhes po-z aspougnî
des pådjes k'&nbsp;aprepièt ou di pus di 32&nbsp;Ko.
Vos dvrîz tuzer a pårti l'&nbsp;pådje e pus ptits bokets.",
"readonlywarning" => "ASTEME:  On-z overe sol båze di dnêyes pol moumint, ey elle a stî metowe e mode seulmint-lére.
Do côp, vos n'&nbsp;såroz schaper vos candjmints asteure; motoit vos dvrîz copyî et aclaper l'&nbsp;tecse dins on fitchî da vosse pol poleur rimete sol {{SITENAME}} pus tård.",
"protectedpagewarning" => "ASTEME: Cisse pådje chal a stî protedjeye siconte
des candjmints, seulmint les uzeus avou èn accès di manaedjeu el polèt candjî.
Acertinez vs ki vos shuvoz les 
<a href='/wiki/Wikipedia:Rîles_po_les_pådjes_protedjeyes'>rîles po les pådjes
protedjeyes</a>.",

# History pages
#
"revhistory"	=> "Istwere des modêyes",
"nohistory"	=> "I gn a pont d'&nbsp;istwere des modêyes po cisse pådje chal.",
"revnotfound"	=> "Modêye nén trovêye",
"revnotfoundtext" => "Li viye modêye del pådje ki vos avoz dmandé n'&nbsp;a nén stî trovêye.
Verifyîz l'&nbsp;hårdêye ki vs avoz eployî po-z ariver sol pådje s'&nbsp;i vs plait.\n",
"loadhist"		=> "Tcherdjaedje del pådje di l'&nbsp;istwere",
"currentrev"	=> "Modêye d'&nbsp;asteure",
"revisionasof"	=> "Modêye do $1",
"cur"			=> "ast.",
"next"			=> "shuv.",
"last"			=> "dif.",
"orig"			=> "oridj.",
"histlegend"	=> "Ledjinde: (ast.) = diferince avou l'&nbsp;modêye d'&nbsp;asteure,
(dif.) = diferince avou l'&nbsp;modêye di dvant, M = candjmint mineur",

# Diffs
#
"difference"	=> "(Diferinces inte les modêyes)",
"loadingrev"	=> "tcherdjaedje del modêye po les diferinces",
"lineno"		=> "Roye $1:",
"editcurrent"	=> "Candjî li modêye do moumint di cisse pådje chal",

# Search results
#
"searchresults" => "Rizultats do cweraedje",
"searchresulttext" => "Po pus di racsegnes sol manire di fé des cweraedjes so {{SITENAME}}, loukîz [[Project:Cweraedje|Cweraedje so {{SITENAME}}]].",
"searchquery"	=> "Pol cweraedje «$1»",
"badquery"		=> "Badly formed search query", # co a fé
"badquerytext"	=> "We could not process your query.
This is probably because you have attempted to search for a
word fewer than three letters long, which is not yet supported.
It could also be that you have mistyped the expression, for
example \"fish and and scales\".
Please try another query.", # co a fé
"matchtotals"	=> "Li cweraedje «$1» a trové $2 årtikes avou l'&nbsp;tite
ki corespond eyet $3 årtikes avou l'&nbsp;tecse ki corespond.",
"nogomatch" => "I n'&nbsp;a nole pådje avou ç'&nbsp;tite la, dji saye on cweraedje dins l'&nbsp;tecse des årtikes.",
"titlematches"	=> "Årtikes avou on tites ki corespond",
"notitlematches" => "Nol årtike avou on tite ki corespond",
"textmatches"	=> "Årtikes avou do tecse ki corespond",
"notextmatches"	=> "Nol årtike avou do tecse ki corespond",
"prevn"			=> "$1 di dvant",
"nextn"			=> "$1 shuvants",
"viewprevnext"	=> "Vey ($1) ($2) ($3).",
"showingresults" => "Chal pa dzo <b>$1</b> rizultats a pårti do limero <b>$2</b>.",
"showingresultsnum" => "Chal pa dzo <b>$3</b> rizultats a pårti do limero <b>$2</b>.",
"nonefound"		=> "<strong>Note</strong>: des cweraedjes ki n'&nbsp;dinèt nou rzultat c'&nbsp;est sovint li cweraedje di ptits mots trop corants (come «les», «des») ki n'&nbsp;sont nén indecsés, oudonbén des cweraedjes di pus d'&nbsp;on mot (seulmint les pådjes avou tos les mots dmandés sront håynêyes dins l'&nbsp;rizultat do cweraedje).",
"powersearch" => "Cweri",
"powersearchtext" => "
Cweraedje ezès espåces di nos&nbsp;:<br>
$1<br>
$2 Håyner les redjiblaedjes &nbsp; Cweri après $3 $9",
"searchdisabled" => "<p>Mande escuzes! Li cweraedje å dvins des årtikes a stî dismetou
pol moumint, cåze ki l'&nbsp;sierveu est fortcherdjî.
Tot ratindant, vos ploz eployî Google po fé les rcweraedjes,
mins çoula pout esse ene miete vî.</p>
  
<!-- SiteSearch Google -->
<FORM method=GET action=\"http://www.google.com/search\">
<TABLE bgcolor=\"#FFFFFF\"><tr><td>
<A HREF=\"http://www.google.com/\">
<IMG SRC=\"http://www.google.com/logos/Logo_40wht.gif\"
border=\"0\" ALT=\"Google\"></A>
</td>
<td>
<INPUT TYPE=text name=q size=31 maxlength=255 value=\"$1\">
<INPUT type=submit name=btnG VALUE=\"Cweri avou Google\">
<font size=-1>
<input type=hidden name=domains value=\"{$wgServer}\"><br>
<input type=radio name=sitesearch value=\"\"> sol waibe 
<input type=radio name=sitesearch value=\"{$wgServer}\" checked> so {$wgServer} <br>
<input type='hidden' name='hl' value='wa'>
<input type='hidden' name='ie' value='$2'>
<input type='hidden' name='oe' value='$2'>
</font>
</td></tr></TABLE>
</FORM>
<!-- SiteSearch Google -->",
"blanknamespace" => "(Mwaisse)",

# Preferences page
#
"preferences"	=> "Preferinces",
"prefsnologin" => "Vos n'&nbsp;estoz nén elodjî",
"prefsnologintext"	=> "I vs fåt esse <a href=\"" .
  wfLocalUrl( "Sipeciås:Userlogin" ) . "\">elodjî</a>
po pleur candjî vos preferinces.",
"prefslogintext" => "Vos estoz elodjî dizo l'&nbsp;uzeu «$1».
Vosse divintrin idintifiant est l'&nbsp;limero $2.

Loukîz a [[Wikipedia:Aidance so les preferinces]] po saveur çou k'&nbsp;c'&nbsp;est tchaeke des tchuzes des preferinces des uzeus.",
"prefsreset"	=> "Les preferinces ont stî rmetowes come d'&nbsp;avance a
pårti des wårdêyès valixhances.",
"qbsettings"	=> "Apontiaedjes pol bår di menu", 
"changepassword" => "Candjî l'&nbsp;sicret",
"skin"			=> "Pea",
"math"			=> "Håynaedje des formules matematikes",
"dateformat"		=> "Cogne del date",
"math_failure"		=> "Failed to parse", # co a fé
"math_unknown_error"	=> "aroke nén cnoxhowe",
"math_unknown_function"	=> "fonccion nén cnoxhowe ",
"math_lexing_error"	=> "lexing error", # co a fé
"math_syntax_error"	=> "aroke di sintacse",
"saveprefs"		=> "Schaper les preferinces",
"resetprefs"	=> "Rimete les prémetowès valixhances",
"oldpassword"	=> "Vî scret",
"newpassword"	=> "Noû scret",
"retypenew"		=> "Ritapez l'&nbsp;noû scret",
"textboxsize"	=> "Grandeu del boesse di tecse",
"rows"			=> "Royes",
"columns"		=> "Colones",
"searchresultshead" => "Håynaedje des rzultats di cweraedje",
"resultsperpage" => "Nombe di responses a håyner so en pådje",
"contextlines"	=> "Nombe di royes a håyner po ene response",
"contextchars"	=> "Nombe di caracteres di contecse pa roye",
"stubthreshold" => "Grandeu minimom po håyner les courts årtikes",
"recentchangescount" => "Nombe di tites dins les <i>dierins candjmints</i>",
"savedprefs"	=> "Vos preferinces ont stî schapêyes.",
"timezonetext"	=> "Tapez li nombe d'&nbsp;eures di diferince avou
l'&nbsp;tins univiersel (UTC).",
"localtime"	=> "Håyner l'&nbsp;eure locåle",
"timezoneoffset" => "Diferince d'&nbsp;eures",
"servertime"   => "Server time is now", # co a fé
"guesstimezone" => "Fill in from browser", # co a fé
"emailflag"		=> "Èn nén riçure des emiles des ôtes uzeus",
"defaultns"	=> "Prémetous spåces di nos pol cweraedje:",

# Recent changes
#
"changes" => "candjmints",
"recentchanges" => "Dierins candjmints",
"recentchangestext" => "Shuvoz chal les dierins candjmints k'&nbsp;i gn a yeu
dzo Wikipedia.",
"rcloaderr"		=> "Tcherdjant les dierins candjmints",
"rcnote"		=> "Chal pa dzo les <strong>$1</strong> dierins candjmints des dierins <strong>$2</strong> djoûs.",
"rcnotefrom"	=> "Chal pa dzo les candjmints dispoy li <b>$2</b> (disk'&nbsp;a <b>$1</b> di mostrés).",
"rclistfrom"	=> "Mostrer les candjmints k'&nbsp;i gn a yeu a pårti do $1",
# "rclinks"		=> "Mostrer les $1 dierins candjmints des dierins $2 djoûs.",
"showhideminor"		=> "$1 candmints mineurs",
"rclinks"		=> "Mostrer les $1 dierins candjmints des dierins $2 djoûs; $3",
"rchide"		=> "e $4; $1 candjmints mineurs; $2 nos d'&nbsp;espåces segondaires; $3 candjmints multipes.",
"rcliu"			=> "; $1 candjmints pa des uzeus eredjîstrés",
"diff"			=> "dif.",
"hist"			=> "ist.",
"hide"			=> "cat.",
"show"			=> "håy.",
"tableform"		=> "tåvlea",
"listform"		=> "djivêye",
"nchanges"		=> "$1 candjmints",
"minoreditletter" => "M",
"newpageletter" => "N",

# Upload
#
"upload"		=> "Eberweter on fitchî",
"uploadbtn"		=> "Eberweter",
"uploadlink"	=> "Eberweter des imådjes",
"reupload"		=> "Reberweter",
"reuploaddesc"	=> "Rivni al pådje d'&nbsp;eberwetaedje.",
"uploadnologin" => "Nén elodjî",
"uploadnologintext"	=> "I vs fåt esse <a href=\"" .
  wfLocalUrl( "Sipeciås:Userlogin" ) . "\">elodjî</a> por
vos pleur eberweter des fitchîs.",
"uploadfile"	=> "Eberweter des imådjes, des sons, evnd.",
"uploaderror"	=> "Aroke d'&nbsp;eberwetaedje",
"uploadtext"	=> "'''ASTEME!''' Divant d'&nbsp;eberweter on
fitchî chal, léjhoz et s'&nbsp;vos asseurer di bén shure 
les [[Project:Rîles_po_l'_eployaedje_des_imådjes|rîles po l'&nbsp;eployaedje des imådjes]]
di Wikipedia.

Si on fitchî avou l'&nbsp;minme no egzistêye dedja, i srè replaecî
sins adviertixhmint pa l'&nbsp;ci ki vos årîz eberweté.
Dj'&nbsp;ô bén ki, a moens ki vos vôrîz mete a djoû on fitchî tot
l'&nbsp;replaeçant pa on pus noû, vos dvrîz tofer vos acertiner
k'&nbsp;i n'&nbsp;a nén ddja on fitchî do minme no.

Po håyner ou cweri des imådjes k'&nbsp;ont ddja stî rçuvowes, 
alez sol [[Sipeciås:Imagelist|djivêye des imådjes dedja eberwetêyes]].
Les eberwetaedjes et disfaçaedjes sont metous èn on
[[Project:Djournå_des_eberwetaedjes|djournå des eberwetaedjes]].

Eployîz les boesses d'&nbsp;intrêye chal pa dzo po-z eberweter
des noveas fitchîs d'&nbsp;imådjes po vos årtikes.
Sol plupårt des betchteus, vos voeroz on boton «Foyter...» (ou «Browse...»)
ki vs permetrè di foyter dins les ridants del deure plake da vosse
po tchoezi l'&nbsp;fitchî, çou ki rimplirè otomaticmint li tchamp
do no do fitchî k'&nbsp;est a costé.
Vos dvoz eto clitchî sol boesse por vos acertiner ki vos n'&nbsp;violez
nou abondroet et ki l'&nbsp;fitchî ki vos evoyîz si pout bén eployî
dins l'&nbsp;Wikipedia.
Clitchîz sol boton «Eberweter» po-z evoyî l'&nbsp;fitchî sol sierveu.
çoula pout prinde do tins si l'&nbsp;raloyaedje al daegntoele est
londjin.

Les cognes di fitchîs k'&nbsp;on-z a ptchî c'&nbsp;est li JPEG po
les imådjes foto, eyet li PNG po les dessins, mapes, drapeas, imådjetes...
et li OGG po les sons.
S'&nbsp;i vs plait, dinez a vosse fitchî on no ki dit bén çou 
k'&nbsp;c'&nbsp;est, c'&nbsp;est pus åjhey insi.
Po håyner l'&nbsp;imådje dins èn årtike, eployîz on loyén del
foûme '''<nowiki>[[imådje:fitchî.jpg]]</nowiki>''' ou
'''<nowiki>[[imådje:fitchî.png|tecse po les betchteus nén grafikes]]</nowiki>'''
ou co '''<nowiki>[[media:fitchî.ogg]]</nowiki>''' po les sons.

Notez bén ki, tot come po les pådjes del Wikipedia, les ôtès djins polèt
candjî ou disfacer vos eberwetaedjes s'&nbsp;i pinsèt ki c'&nbsp;est mî
po l'&nbsp;eciclopedeye; eyet vos ploz esse espaitchî d'&nbsp;eberweter
des fitchîs si vos n'&nbsp;rispectez nén l'&nbsp;sistinme.",
"uploadlog"		=> "djournå des eberwetaedjes",
"uploadlogpage" => "Djournå_des_eberwetaedjes",
"uploadlogpagetext" => "Chal pa dzo li djivêye des dierins eberwetaedjes.
Totes les eures sont metowes e tins univiersel (UTC).
<ul>
</ul>
",
"filename"		=> "No do fitchî",
"filedesc"		=> "Discrijhaedje",
"filestatus"		=> "Abondroets ey eployaedje",
"filesource"		=> "Sourdant",
"affirmation"	=> "Dj'&nbsp;acertinêye ki l'&nbsp;djin k'&nbsp;a les
abondroets po ci fitchî chal est d'&nbsp;acoird di l'&nbsp;eplaidî dizo
les condicions del $1.",
"copyrightpage" => "Wikipedia:Abondroets",
"copyrightpagename" => "Abondroets Wikipedia",
"uploadedfiles"	=> "Fitchîs eberwetés",
"noaffirmation" => "Vos dvoz acertiner ki l'&nbsp;fitchî ki vos eberwetez
pout bén esse eployî sins aler siconte des abondroets do fitchî.",
"ignorewarning"	=> "Passer houte des adviertixhmints eyet schaper tot l'&nbsp;minme li fitchî.",
"minlength"		=> "Les imådjes divèt aveur des nos di pol moens troes letes.",
"badfilename"	=> "Li no d'&nbsp;l'&nbsp;imådje a stî candjî a «$1».",
"badfiletype"	=> "«.$1» n'&nbsp;est nén ene sôre d'&nbsp;imådje ricmandêye.",
"largefile"		=> "C'&nbsp;est mî ki les imådjes åyexhe ene grandeu di moens di 100&nbsp;Ko.",
"successfulupload" => "L'&nbsp;eberwetaedje a stî comifåt",
"fileuploaded"	=> "L'&nbsp;eberwetaedje do fitchî «$1» a stî å paire des pôces.
Shuvoz ci loyén chal: ($2) pol pådje di discrijhaedje eyet rimplixhoz les
informåcions sol fitchî, come di wice k'&nbsp;i vént, cwand il a stî
fwait, et pa kî, et tot l'&nbsp;minme kéne informåcion interessante ki vos ploz
saveur å dfait do fitchî.",
"uploadwarning" => "Adviertixhmint so l'&nbsp;eberwetaedje",
"savefile"		=> "Schaper l'&nbsp;fitchî",
"uploadedimage" => "eberwetaedje di «$1»",

# Image list
#
"imagelist"		=> "Djivêye des imådjes",
"imagelisttext"	=> "Chal pa dzo c'&nbsp;est ene djivêye di $1 imådjes relîtes $2.",
"getimagelist"	=> "fetching image list", # co a fé
"ilshowmatch"	=> "Mostrer totes les imådjes avou des nos ki corespondèt a",
"ilsubmit"		=> "Cweri",
"showlast"		=> "Mostrer les $1 dierinnès imådjes relîtes $2.",
"all"			=> "totes",
"byname"		=> "påzès nos",
"bydate"		=> "pazès dates",
"bysize"		=> "pa grandeu",
"imgdelete"		=> "oist.",
"imgdesc"		=> "disc.",
"imglegend"		=> "Ledjinde: (disc.) = håyner/candjî l'&nbsp;discrijhaedje di l'&nbsp;imådje.",
"imghistory"	=> "Istwere di l'&nbsp;imådje",
"revertimg"		=> "mod.",
"deleteimg"		=> "oist.",
"deleteimgcompletely"		=> "oist.",
"imghistlegend" => "Ledjinde: (ast.) = c'&nbsp;est l'&nbsp;imådje k'&nbsp;i gn a asteure, (oist.) = oister (disfacer)
cisse viye modêye la, (mod.) = rivni a cisse viye modêye la.
<br><i>Clitchîz sol date po vey l'&nbsp;imådje evoyeye a cisse date la.</i>",
"imagelinks"	=> "Loyéns viè ciste imådje chal",
"linkstoimage"	=> "Les pådjes shuvantes eployèt ciste imådje chal:",
"nolinkstoimage" => "I n'&nbsp;a nole pådje k'&nbsp;eploye ciste imådje chal.",

# Statistics
#
"statistics"	=> "Sitatistikes",
"sitestats"		=> "Sitatistikes del waibe",
"userstats"		=> "Sitatistikes des uzeus",
"sitestatstext" => "I gn a <b>$1</b> pådjes å totå el båze di dnêyes.
Çoula tot contant les pådjes di «Copenes», les pådjes å dfait di Wikipedia,
les pådjes «stub» (pådjes sins waire di contnou), les redjiblaedjes,
eyet co ds ôtes ki n'&nbsp;sont nén vormint des årtikes.
Si on n'&nbsp;conte nén ces la, i gn a <b>$2</b> pådjes ki sont
probåblumint des vraiys årtikes.<p>
I gn a-st avou å totå <b>$3</b> riwaitaedjes di pådjes, eyet <b>$4</b>
candjmints do contnou des pådjes dispoy li 20 di djulete 2003.
Dj'&nbsp;ô bén k'&nbsp;i gn a ene moyene di <b>$5</b> candjmints par pådje,
eyet <b>$6</b> riwaitaedjes po on candjmint.",
"userstatstext" => "I gn a <b>$1</b> uzeus d'&nbsp;eredjîstrés.
<b>$2</b> di zels sont eto des manaedjeus (riloukîz a $3).
                                                                                
<p><h2>Des ôtes pus spepieusès statistikes</h2><p>
Des pus spepieusès statistikes åd dilong des moes sont
<a href='/stats/WA/Sitemap.htm' class='internal'>chal</a>.
", # NOTE: loyén viè les statistikes

# Maintenance Page
#
"maintenance"		=> "Pådje di manaedjmint",
"maintnancepagetext"	=> "Cisse pådje chal a sacwantès ahessåvès usteyes
po manaedjî les årtikes. Sacwantes di ces fonccions chal polèt esse
sitréndåves pol båze di dnêyes, do côp on vs dimandrè di n'&nbsp;nén
clitchî sol boton «rafrister» di vosse betchteu a tchaeke côp
ki vos fjhoz on ptit candjmint ;-)",
"maintenancebacklink"	=> "Rivni al pådje di manaedjmint",
"disambiguations"	=> "Pådjes d'&nbsp;omonimeye",
"disambiguationspage"	=> "Wikipedia:Loyéns_viè_les_pådjes_d'_omonimeye",
"disambiguationstext"	=> "Les årtikes shuvants ont des loyéns viè en <i>pådje d'&nbsp;omonimeye</i>. I dvrént purade loyî viè l'&nbsp;bon årtike.<br>
Ene pådje est considerêye come pådje d'&nbsp;omonimeye si elle aparexhe e $1.<br>
Les loyéns a pårti d'&nbsp;ôtes espåces di lomaedje èn sont <i>nén</i> håynés chal.",
"doubleredirects"	=> "Dobes redjiblaedjes",
"doubleredirectstext"	=> "<b>Asteme:</b> Cisse djivêye chal pout aveur des fås pôzitifs. Dj'&nbsp;ô bén k'&nbsp;i pout aveur do tecse di pus, avou des loyéns, après li prumî «#REDIRECT».<br>\n
Tchaeke roye a-st on loyén viè l'&nbsp;prumî eyet l'&nbsp;deujhinme redjiblaedje, avou on mostraedje del prumire roye do tecse do deujhinme redjiblaedje, çou ki å pus sovint dene li «vraiy» årtike såme, ki l'&nbsp;prumî redjiblaedje dvreut evoyî viè lu.",
"brokenredirects"	=> "Pierdous redjiblaedjes",
"brokenredirectstext"	=> "Les redjiblaedjes shuvants evoyèt so ene pådje ki n'&nbsp;egzistêye nén.",
"selflinks"		=> "Pådjes avou des loyéns viè zeles minmes",
"selflinkstext"		=> "Les pådjes shuvantes ont on loyén viè zeles minmes, çou ki n'&nbsp;si dvreut nén fé.",
"mispeelings"           => "Pådjes avou des flotches",
"mispeelingstext"               => "Les pådjes shuvantes ont ene ou sacwante
flotche, di cenes metowes so $1. Li scrijhaedje corek pout esse mostré inte
åtchetes a costé do mot må scrît.", 
"mispeelingspage"       => "Djivêye des flotches les pus corantes",
"missinglanguagelinks"  => "Loyéns di lingaedjes ki mankèt",
"missinglanguagelinksbutton"    => "Trover les loyéns di lingaedjes ki mankèt po",
"missinglanguagelinkstext"      => "Ces årtikes chal èn loynut <i>nén</i> viè leu-z omologues so «$1». Les redjiblaedjes eyet les dzo-pådjes èn sont <i>nén</i> mostrêyes.",


# Miscellaneous special pages
#
"orphans"		=> "Pådjes ôrfulinnes",
"lonelypages"	=> "Pådjes ôrfulinnes",
"unusedimages"	=> "Imådjes nén eployeyes",
"popularpages"	=> "Pådjes les pus léjhowes",
"nviews"		=> "léjhowe $1 côps",
"wantedpages"	=> "Pådjes les pus rcwerowes",
"nlinks"		=> "$1 loyéns",
"allpages"		=> "Totes les pådjes",
"randompage"	=> "Årtike a l'&nbsp;astcheyance", # TODO: trop longou?
"shortpages"	=> "Coûtès pådjes",
"longpages"		=> "Longowès pådjes",
"listusers"		=> "Djivêye des uzeus",
"specialpages"	=> "Pådjes sipeciåles",
"spheading"		=> "Pådjes sipeciåles po tos ls uzeus",
"sysopspheading" => "Pådjes sipeciåles po les manaedjeus",
"developerspheading" => "Pådjes sipeciåles po les diswalpeus",
"protectpage"	=> "Protedjî l'&nbsp;pådje",
"recentchangeslinked" => "Candjmints aloyîs",
"rclsub"		=> "(ezès pådjes ki «$1» a-st on loyén dzeu)",
"debug"			=> "Disbugaedje",
"newpages"		=> "Novelès pådjes",
"ancientpages"		=> "Viyådjes",
"intl"		=> "Loyéns eterlingaedjes",
"movethispage"	=> "Displaecî cisse pådje",
"unusedimagestext" => "<p>Notez tot l'&nbsp;minme ki d'&nbsp;ôtès waibes,
come li cenes des ôtes Wikipedias, polèt aveur des loyéns viè ces imådjes la
gråcès a ene direke hårdêye. Do côp, ces imådjes aparexhèt chal, mågré
k'&nbsp;ele soeyexhe eployeyes.",
"booksources"	=> "Sourdants po les lives",
"booksourcetext" => "Chal pa dzo c'&nbsp;est ene djivêye di hårdêyes viè
des waibes ki vindèt des lives, noûs ou di deujhinme mwin, et ki polèt
aveur pus d'&nbsp;informåcions å dfait des lives ki vos cweroz après.
{{SITENAME}} n'&nbsp;a rén a vey avou ces eterprijhes la, eyet cisse djivêye
chal èn doet nén esse veyowe come èn aspoya ou nerén ene reclame.",
"alphaindexline" => "di $1 a $2",

# Email this user
#
"mailnologin"	=> "Nole adresse d'&nbsp;evoyeu",
"mailnologintext" => "Po-z evoyî èn emile a èn ôte uzeu i vs fåt esse <a href=\"" .
  wfLocalUrl( "Sipeciås:Userlogin" ) . "\">elodjî</a>
eyet aveur ene adresse emile d'&nbsp;evoyeu ki soeye valide 
dins vos <a href=\"" .
  wfLocalUrl( "Sipeciås:Preferences" ) . "\">preferinces</a>.",
"emailuser"		=> "Emiler a l'&nbsp;uzeu",
"emailpage"		=> "Emilaedje a èn uzeu",
"emailpagetext"	=> "Si cist uzeu chal a dné ene adresse emile valide dins
ses preferins, vos lyi ploz evoyî èn emile a pårti di cisse pådje chal. 
L'&nbsp;adresse emile k'&nbsp;i gn a dins vos preferinces serè-st eployeye
come adresse di l'&nbsp;evoyeu (adresse «From:» di l'&nbsp;emile),
po ki l'&nbsp;riçuveu poye risponde.",
"noemailtitle"	=> "Pont d'&nbsp;adresse emile",
"noemailtext"	=> "Cist uzeu chal n'&nbsp;a nén dné d'&nbsp;adresse emile
valide, ou n'&nbsp;vout nén rçure des emiles des ôtes uzeus.
Do côp, c'&nbsp;est nén possibe di lyi evoyî èn emile.",
"emailfrom"		=> "Di",
"emailto"		=> "Po",
"emailsubject"	=> "Sudjet",
"emailmessage"	=> "Messaedje",
"emailsend"		=> "Evoyî",
"emailsent"		=> "Emile evoyî",
"emailsenttext" => "Vost emilaedje a stî evoyî comifåt.",

# Watchlist
#
"watchlist"		=> "Mes pådjes shuvowes",
"watchlistsub"	=> "(po l'&nbsp;uzeu «$1»)",
"nowatchlist"	=> "Vosse djivêye des pådjes a shuve est vude.",
"watchnologin"	=> "Vos n'&nbsp;estoz nén elodjî",
"watchnologintext"	=> "I vs fåt esse <a href=\"" .
  wfLocalUrl( "Sipeciås:Userlogin" ) . "\">elodjî</a>
po pleur candjî vosse djivêye des pådjes a shuve.",
"addedwatch"	=> "Radjouté ås shuvous",
"addedwatchtext" => "Li pådje «$1» a stî radjoutêye a vosse <a href=\"" .
  wfLocalUrl( "Sipeciås:Watchlist" ) . "\">djivêye des pådjes a shuve</a>.
Tos les candjmints k'&nbsp;i gn årè di cisse pådje chal, eyet di si pådje
di copene, seront håynés chal, eyet li pådje serè metowe e <b>cråssès letes</b>
el <a href=\"" .
  wfLocalUrl( "Sipeciås:Recentchanges" ) . "\">djivêye des dierins candjmints</a> po k'&nbsp;ça soeye pus åjhey por vos del rimårker.</p>
<p>
Si vos vloz bodjî l'&nbsp;pådje foû di vosse djivêye des shuvous,
clitchîz so «Èn pus shuve li pådje» dins l'&nbsp;bår di menu sol costé.",
"removedwatchtext" => "Li pådje «$1» a stî bodjeye foû di vosse djivêye des pådjes a shuve.",
"watchthispage"	=> "Shuve cisse pådje",
"unwatchthispage" => "Èn pus shuve li pådje",
"notanarticle"	=> "Nén èn årtike",
"watchnochange" => "Nole des pådjes di vosse djivêye di pådjes a shuve n'&nbsp;a stî candjeye dins l'&nbsp;termene di tins dmandêye.",
"watchdetails" => "($1 pådjes shuvowes, sins conter les pådjes di copene;
$2 total pages edited since cutoff;
$3...
<a href='$4'>mostrer eyet candjî l'&bsp;djivêye etire</a>.)", # co a fé
"watchmethod-recent" => "checking recent edits for watched pages", # co a fé
"watchmethod-list" => "checking watched pages for recent edits", # co a fé
"removechecked" => "Bodjî les cayets tchoezis foû del djivêye des pådjes a shuve",
"watchlistcontains" => "I gn a $1 pådjes e vossse djivêye des pådjes a shuve.",
"watcheditlist" => "Here's an alphabetical list of your
watched pages. Check the boxes of pages you want to remove
from your watchlist and click the 'remove checked' button
at the bottom of the screen.", # co a fé
"removingchecked" => "Removing requested items from watchlist...", # co a fé
"couldntremove" => "Couldn't remove item '$1'...", # co a fé
"iteminvalidname" => "Åk n'&nbsp;a nén stî avou «$1», no nén valide...",
"wlnote" => "Chal pa dzo les $1 dierins candjmints des <b>$2</b> dierinnès eures.",
"wlshowlast" => "Mostrer les dierin(nè)s $1 eures, $2 djoûs $3",


# Delete/protect/revert
#
"deletepage"	=> "Disfacer l'&nbsp;pådje",
"confirm"		=> "Acertiner",
"excontent" => "li contnou esteut:",
"exbeforeblank" => "li contnou dvant l'&nbsp;disfaçaedje esteut:",
"exblank" => "li pådje esteut vude",
"confirmdelete" => "Acertinaedje do disfaçaedje",
"deletesub"		=> "(Djisfaçaedje di «$1»)",
"historywarning" => "Adviertixhmint: Li pådje ki vos alez disfacer a-st ene istwere: ",
"confirmdeletetext" => "Vos alez disfacer po tofer del båze di dnêyes ene
pådje ou ene imådje, avou tote si istwere.
Acertinez s'&nbsp;i vs plait ki c'&nbsp;est bén çoula ki vos vloz fé,
ki vos comprindoz les consecwinces, et ki vos fjhoz çoula
tot [[Wikipedia:Rîles a shure|shuvant les rîles]].",
"confirmcheck"	=> "Oyi, dji vou vormint disfacer ci fitchî chal.",
"actioncomplete" => "Fwait",
"deletedtext"	=> "Li pådje «$1» a stî disfacêye. Loukîz li $2 po ene
djivêye des dierins disfaçaedjes.",
"deletedarticle" => "pådje «$1» disfacêye",
"dellogpage"	=> "Djournå_des_disfaçaedjes",
"dellogpagetext" => "Chal pa dzo c'&nbsp;est l'&nbsp;djivêye des dierins
disfaçaedjes. Totes les dates et eures sont-st e tins univiersel (UTC).
<ul>
</ul>
",
"deletionlog"	=> "djournå des disfaçaedjes",
"reverted"		=> "Rimetou ene modêye di dvant",
"deletecomment"	=> "Råjhon do disfaçaedje",
"imagereverted" => "Li rmetaedje del modêye di dvant a stî comifåt.",
"rollback"		=> "Roll back edits", # co a fé
"rollbacklink"	=> "rollback", # co a fé
"rollbackfailed" => "Rollback failed", # co a fé
"cantrollback"	=> "Cannot revert edit; last contributor is only author of this article.", # co a fé
"alreadyrolled"	=> "Cannot rollback last edit of [[$1]]
by [[Uzeu:$2|$2]] ([[Uzeu copene:$2|Copene]]); someone else has edited or rolled back the article already. 

Li dierin candjmint a stî fwait pa [[Uzeu:$3|$3]] ([[Uzeu copene:$3|Copene]]). ",
#   only shown if there is an edit comment
"editcomment" => "The edit comment was: \"<i>$1</i>\".", # co a fé
"revertpage"	=> "Rivnou å dierin candjmint da $1",
"protectlogpage" => "Protection_log", # co a fé
"protectlogtext" => "Chal pa dzo c'&nbsp;est ene djivêye des protedjaedjes
et disprotedjaedjes des pådjes.
Loukîz [[{{ns:4}}:Pådje protedjeye]] po pus di racsegnes.",
"protectedarticle" => "[[$1]] protedjî",
"unprotectedarticle" => "[[$1]] disprotedjî",

# Undelete
"undelete" => "Rapexhî des disfacêyès pådjes",
"undeletepage" => "Vey et rapexhî des disfacêyès pådjes",
"undeletepagetext" => "Les pådjes shuvantes ont stî disfacêyes mins ele sont
co ezès årtchives, do côp ele polèt esse rapexheyes.
Les årtchives sont netieyes di tins en tins.",
"undeletearticle" => "Rapexhî on disfacé årtike",
"undeleterevisions" => "$1 modêyes ezès årtchives",
"undeletehistory" => "Si vos rapexhîz l'&nbsp;pådje, l'&nbsp;istwere del pådje
serè rapexheye eto, avou totes les modêyes co ezès årtchives.
Si ene novele pådje avou l'&nbsp;minme a stî askepieye dispoy li disfaçaedje
di cisse chal, les rapexheyès modêyes seront metowes e l'&nbsp;istwere mins
c'&nbsp;est l'&nbsp;modêye do moumint, et nén l'&nbsp;cisse rapexheye, ki
srè håynêye.",
"undeleterevision" => "Modêye disfacêye li $1",
"undeletebtn" => "Rapexhî!",
"undeletedarticle" => "a rapexhî l'&nbsp;pådje «$1»",
"undeletedtext"   => "L'&nbsp;årtike [[$1]] a stî rapexhî comifåt.
Loukîz [[Wikipedia:Djournå_des_disfaçaedjes]] po ene djivêye des dierins
disfaçaedjes eyet rapexhaedjes.",

# Contributions
#
"contributions"	=> "Ovraedjes di l'&nbsp;uzeu", /* TODO: problinme di longeu */
"mycontris" => "Mes contribucions",
"contribsub"	=> "Po l'&nbsp;uzeu $1",
"nocontribs"	=> "Nou candjmint di trové ki corespondreut a ç'&nbsp;critere la.",
"ucnote"		=> "Chal pa dzo les <b>$1</b> dierins candjmints di l'&nbsp;uzeu so les <b>$2</b> dierins djoûs.",
"uclinks"		=> "Vey les $1 dierins candjmints; vey les $2 dierins djoûs.",
"uctop"		=> " (top)" ,

# What links here
#
"whatlinkshere"	=> "Pådjes ki loynut chal",
"notargettitle" => "No target", # co a fé
"notargettext"	=> "You have not specified a target page or user
to perform this function on.", # co a fé
"linklistsub"	=> "(Djivêye des loyéns)",
"linkshere"		=> "Les pådjes ki shuvèt ont des loyéns viè cisse ci:",
"nolinkshere"	=> "Nole pådje avou des loyéns viè cisse ci.",
"isredirect"	=> "pådje di redjiblaedje",

# Block/unblock IP
#
"blockip"		=> "Bloker èn uzeu",
"blockiptext"	=> "Use the form below to block write access
from a specific IP address or username.
This should be done only only to prevent vandalism, and in
accordance with [[Wikipedia:Rîles a shure|{{SITENAME}} policy]].
Fill in a specific reason below (for example, citing particular
pages that were vandalized).", # co a fé
"ipaddress"		=> "Adresse IP/no d'&nbsp;uzeu",
"ipbreason"		=> "Råjhon",
"ipbsubmit"		=> "Bloker cist uzeu",
"badipaddress"	=> "Nol uzeu avou ç'&nbsp;no la.",
"noblockreason" => "Vos dvoz dner ene råjhon pol blocaedje.",
"blockipsuccesssub" => "Li blocaedje a stî comifåt",
"blockipsuccesstext" => "«$1» a stî bloké.
<br>Loukîz li [[Sipeciås:Ipblocklist|Djivêye des blocaedjes]] po candjî on blocaedje.",
"unblockip"		=> "Disbloker èn uzeu",
"unblockiptext"	=> "Use the form below to restore write access
to a previously blocked IP address.", # co a fé
"ipusubmit"		=> "Unblock this address", # co a fé
"ipusuccess"	=> "«%s» a stî disbloké",
"ipblocklist"	=> "Djivêye d'&nbsp;adresses IP et di nos d'&nbsp;uzeus ki sont blokés",
"blocklistline"	=> "$1, $2 a bloké $3",
"blocklink"		=> "bloker",
"unblocklink"	=> "disbloker",
"contribslink"	=> "contribs", # co a fé
"autoblocker"	=> "Bloké otomaticmint paski vos eployîz li minme adresse IP ki «%s». Råjhon do blocaedje «%s».",
"blocklogpage"	=> "Block_log", # co a fé
"blocklogentry"	=> '«$1» a stî bloké',
"blocklogtext"	=> "This is a log of user blocking and unblocking actions. Automatically 
blocked IP addresses are not be listed. See the [[Special:Ipblocklist|IP block list]] for
the list of currently operational bans and blocks.", # co a fé
"unblocklogentry"	=> '«$1» a stî disbloké',

# Developer tools
#
"lockdb"		=> "Lock database", # co a fé
"unlockdb"		=> "Unlock database", # co a fé
"lockdbtext"	=> "Locking the database will suspend the ability of all
users to edit pages, change their preferences, edit their watchlists, and
other things requiring changes in the database.
Please confirm that this is what you intend to do, and that you will
unlock the database when your maintenance is done.", # co a fé
"unlockdbtext"	=> "Unlocking the database will restore the ability of all
users to edit pages, change their preferences, edit their watchlists, and
other things requiring changes in the database.
Please confirm that this is what you intend to do.", # co a fé
"lockconfirm"	=> "Yes, I really want to lock the database.", # co a fé
"unlockconfirm"	=> "Yes, I really want to unlock the database.", # co a fé
"lockbtn"		=> "Lock database", # co a fé
"unlockbtn"		=> "Unlock database", # co a fé
"locknoconfirm" => "You did not check the confirmation box.", # co a fé
"lockdbsuccesssub" => "Database lock succeeded", # co a fé
"unlockdbsuccesssub" => "Database lock removed", # co a fé
"lockdbsuccesstext" => "The database has been locked.
<br>Remember to remove the lock after your maintenance is complete.", # co a fé
"unlockdbsuccesstext" => "The database has been unlocked.", # co a fé

# SQL query
#
"asksql"		=> "SQL query", # co a fé
"asksqltext"	=> "Use the form below to make a direct query of the
database.
Use single quotes ('like this') to delimit string literals.
This can often add considerable load to the server, so please use
this function sparingly.", # co a fé
"sqlislogged"	=> "Please note that all queries are logged.", # co a fé
"sqlquery"		=> "Enter query", # co a fé
"querybtn"		=> "Submit query", # co a fé
"selectonly"	=> "Only read-only queries are allowed.", # co a fé
"querysuccessful" => "Query successful", # co a fé

# Move page
#
"movepage"		=> "Displaecî l'&nbsp;pådje",
"movepagetext"	=> "Chal vos ploz candjî l'&nbsp;no d'&nbsp;ene pådje,
dj'&nbsp;ô bén displaecî l'&nbsp;pådje, eyet si istwere, viè l'&nbsp;novea no.
Li vî tite divénrè-st ene pådje di redjiblaedje viè l'&nbsp;novele/
Les loyéns viè l'&nbsp;viye pådje èn seront nén candjîs; acertinez vs di
[[Sipeciås:Maintenance|verifyî]] s'&nbsp;i n'&nbsp;a nén des dobes
ou crons redjiblaedjes.
Vos estoz responsåve di fé çou k'&nbsp;i fåt po k'&nbsp;les loyéns
continouwexhe di moenner la k'&nbsp;i fåt.

Notez k'anbsp;el pådje èn serè '''nén''' displaeceye s'anbsp;i gn a ddja ene
pådje avou l'&nbsp;novea tite, a moens k'&nbsp;ele soeye vude, ou ene pådje
di redjiblaedje, et k'&nbsp;ele n'&nbsp;åye nole istwere.
Çoula vout dire ki vos ploz ri-displaecî ene pådje viè l'&nbsp;no k'&nbsp;ele
aveut djusse divant, et insi disfé vosse prumî displaeçaedje, å cas ou vos vs
rindrîz conte ki vos avoz fwait ene flotche; ey eto ki vos n'&nbsp;poloz nén
spotchî par accidint ene pådje k'&nbsp;egzistêye dedja.

<b>ASTEME!</b>
On displaeçaedje pout esse on consecant et nén atindou candjmint po ene
pådje foirt léjhowe; s'&nbsp;i vs plait tuzez bén åzès consecwinces divant
d'&nbsp;continouwer.",
"movepagetalktext" => "Li pådje di copene associeye, s'&nbsp;end a ene, serè
displaeceye otomaticmint avou, '''a moens ki:'''
*Vos displaecîz l'&nbsp;pådje d'&nbsp;èn espåce di lomaedje a èn ôte,
*Ene pådje di copene nén vude egzistêye dedja dizo l'&nbsp;novea no,
*Vos disclitchrîz l'&nbsp;boesse a clitchî chal pa dzo.

Dins ces cas la, vos dvroz displaecî l'&nbsp;pådje di copene al mwin, ou rcopyî
si contnou, si vos l'&nbsp;vloz mete adlé l'&nbsp;novea no
d'&nbsp;l'&nbsp;årtike.",
"movearticle"	=> "Displaecî di",
"movenologin"	=> "Nén elodjî",
"movenologintext" => "I vs fåt esse èn uzeu eredjîstré eyet esse <a href=\"" .
  wfLocalUrl( "Sipeciås:Userlogin" ) . "\">elodjî</a> por vos
pleur displaecî ene pådje.",
"newtitle"		=> "Viè l'&nbsp;novea tite",
"movepagebtn"	=> "Displaecî",
"pagemovedsub"	=> "Li displaeçaedje a stî comifåt",
"pagemovedtext" => "Li pådje «[[$1]]» a stî displaeceye viè «[[$2]]».",
"articleexists" => "Ene pådje egzistêye dedja avou ç'&nbsp;no la, oudonbén
li no k'&nbsp;vos avoz tchoezi n'&nbsp;est nén valide.
Tchoezixhoz è èn ôte s'&nbsp;i vs plait.",
"talkexists"	=> "The page itself was moved successfully, but the
talk page could not be moved because one already exists at the new
title. Please merge them manually.", # co a fé
"movedto"		=> "displaecî viè",
"movetalk"		=> "Displaecî li pådje di copene avou, si ça astchait.",
"talkpagemoved" => "Li pådje di copene corespondante a stî displaeceye avou.",
"talkpagenotmoved" => "Li pådje di copene corespondante n'&nbsp;a <strong>nén</strong> stî displaeceye.",

"export"		=> "Export pages", # co a fé
"exporttext"	=> "You can export the text and editing history of a particular
page or set of pages wrapped in some XML; this can then be imported into another
wiki running MediaWiki software, transformed, or just kept for your private
amusement.", # co a fé
"exportcuronly"	=> "Inclure fok li modêye do moumint, nén tote l'&nbsp;istwere",

# Namespace 8 related

"allmessages"	=> "Tos les messaedjes",
"allmessagestext"	=> "Çouchal est ene djivêye di tos les messaedjes k'&nbsp;i gn a dins l'&nbsp;espåce di lomaedje ''MediaWiki:''",

# Math

'mw_math_png' => "Håyner tofer come ene imådje PNG",
'mw_math_simple' => "Håyner en HTML si c'&nbsp;est foirt simpe, ôtmint e PNG",
'mw_math_html' => "Håyner en HTML si c'&nbsp;est possibe, ôtmint e PNG",
'mw_math_source' => "El leyî e TeX (po les betchteus e môde tecse)",
'mw_math_modern' => "Ricmandé po les betchteus modienes",
);

#--------------------------------------------------------------------------
# Internationalisation code
#--------------------------------------------------------------------------

class LanguageWa extends LanguageUtf8 {

	function getBookstoreList () {
		global $wgBookstoreListWa ;
		return $wgBookstoreListWa ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesWa;
		return $wgNamespaceNamesWa;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesWa;
		return $wgNamespaceNamesWa[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesWa;

		foreach ( $wgNamespaceNamesWa as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsWa;
		return $wgQuickbarSettingsWa;
	}

	function getSkinNames() {
		global $wgSkinNamesWa;
		return $wgSkinNamesWa;
	}

	function getDateFormats() {
		global $wgDateFormatsWa;
		return $wgDateFormatsWa;
	}
	
	# Inherit userAdjust()

	###
	### Dates in Walloon are "1î d' <monthname>" for 1st of the month,
	### "<day> di <monthname>" for months starting by a consoun, and
	### "<day> d' <monthname>" for months starting with a vowel
	###
	function date( $ts, $adj = false )
	{
		global $wgAmericanDates, $wgUser, $wgUseDynamicDates;

		if ( $adj ) { $ts = $this->userAdjust( $ts ); }
		
		$m = substr( $ts, 4, 2 );
		$n = substr( $ts, 6, 2 );

		if ($n == 1) {
		    $d = "1î d'&nbsp;" . $this->getMonthName( $m ) . 
			" " .  substr( $ts, 0, 4 );
		} else if ($n == 2 || $n == 3 || $n == 20 || $n == 22 || $n == 23) {
		    $d = (0 + $n) . " d'&nbsp;" . $this->getMonthName( $m ) . 
			" " .  substr( $ts, 0, 4 );
		} else if ($m == 4 || $m == 8 || $m == 10) {
		    $d = (0 + $n) . " d'&nbsp;" . $this->getMonthName( $m ) . 
			" " .  substr( $ts, 0, 4 );
		} else {
		    $d = (0 + $n) . " di " . $this->getMonthName( $m ) .
			" " .  substr( $ts, 0, 4 );
		}
		  
		return $d;
	}

	function time( $ts, $adj = false )
	{
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$t = substr( $ts, 8, 2 ) . ":" . substr( $ts, 10, 2 );
		return $t;
	}

	function timeanddate( $ts, $adj = false )
	{
		#return $this->time( $ts, $adj ) . " " . $this->date( $ts, $adj );
		return $this->date( $ts, $adj ) . " a " . $this->time( $ts, $adj );
	}

	# Inherit rfc1123()

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesWa;
		return $wgValidSpecialPagesWa;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesWa;
		return $wgSysopSpecialPagesWa;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesWa;
		return $wgDeveloperSpecialPagesWa;
	}

	function getMessage( $key )
	{
		global $wgAllMessagesWa;
		
		if(array_key_exists($key, $wgAllMessagesWa))
			return $wgAllMessagesWa[$key];
		else
			return Language::getMessage($key);
	}

	function getAllMessages()
	{
		global $wgAllMessagesWa;
		return $wgAllMessagesWa;
	}
	
}

?>

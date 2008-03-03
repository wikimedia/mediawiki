<?php
/** Icelandic (Íslenska)
 *
 * @package MediaWiki
 * @subpackage Language
 */

$quickbarSettings = array(
	'Sleppa', 'Fast vinstra megin', 'Fast hægra megin', 'Fljótandi til vinstri'
);

$skinNames = array(
	'standard'	=> 'Klassískt',
	'nostalgia'	=> 'Gamaldags',
	'cologneblue'	=> 'Kölnarblátt',
	'myskin'	=> 'Mitt þema',
);

$datePreferences = array(
	'default',
	'dmyt',
	'short dmyt',
	'tdmy',
	'short tdmy',
	'ISO 8601',
);

$datePreferenceMigrationMap = array(
	'default',
	'dmyt',
	'short dmyt',
	'tdmy',
	'short tdmy',
);	

$dateFormats = array(
	'dmyt time' => 'H:i',
	'dmyt date' => 'j. F Y',
	'dmyt both' => 'j. F Y "kl." H:i',

	'short dmyt time' => 'H:i',
	'short dmyt date' => 'j. M. Y',
	'short dmyt both' => 'j. M. Y "kl." H:i',

	'tdmy time' => 'H:i',
	'tdmy date' => 'j. F Y',
	'tdmy both' => 'H:i, j. F Y',

	'short tdmy time' => 'H:i',
	'short tdmy date' => 'j. M. Y',
	'short tdmy both' => 'H:i, j. M. Y',
);

$magicWords = array(
	'redirect'   => array( 0, '#tilvísun', '#TILVÍSUN', '#redirect' ), // MagicWord::initRegex() sucks
);
$namespaceNames = array(
	NS_MEDIA          => 'Miðill',
	NS_SPECIAL        => 'Kerfissíða',
	NS_MAIN           => '',
	NS_TALK           => 'Spjall',
	NS_USER           => 'Notandi',
	NS_USER_TALK      => 'Notandaspjall',
	NS_PROJECT_TALK   => '$1spjall',
	NS_IMAGE          => 'Mynd',
	NS_IMAGE_TALK     => 'Myndaspjall',
	NS_MEDIAWIKI      => 'Melding',
	NS_MEDIAWIKI_TALK => 'Meldingarspjall',
	NS_TEMPLATE       => 'Snið',
	NS_TEMPLATE_TALK  => 'Sniðaspjall',
	NS_HELP           => 'Hjálp',
	NS_HELP_TALK      => 'Hjálparspjall',
	NS_CATEGORY       => 'Flokkur',
	NS_CATEGORY_TALK  => 'Flokkaspjall'
);

$separatorTransformTable = array(',' => '.', '.' => ',' );
$linkPrefixExtension = true;
$linkTrail = '/^([áðéíóúýþæöa-z-–]+)(.*)$/sDu';

	
#-------------------------------------------------------------------
# Default messages
#-------------------------------------------------------------------

$messages = array(
'linkprefix'=> '/^(.*?)([áÁðÐéÉíÍóÓúÚýÝþÞæÆöÖA-Za-z-–]+)$/sDu',

'1movedto2' => "$1 færð á $2",
'1movedto2_redir' => "$1 færð á $2 yfir tilvísun",
'monobook.css' => "
/* Stórir stafir í ýmsu */
#p-personal ul { text-transform: inherit; } /* notandanfn, spjall, stillingar */
.portlet h5 { text-transform: inherit;}     /* flakk, leit, verkfæri... */
#p-cactions li a {text-transform: inherit;} /* notandasíða, spjall... */",
'monobook.js' => "/* tooltips and access keys */
var ta = new Object();
ta['pt-userpage'] = new Array('.','Notendasíðan mín');
ta['pt-anonuserpage'] = new Array('.','Notendasíðan fyrir IP töluna þína');
ta['pt-mytalk'] = new Array('n','Spallsíðan mín');
ta['pt-anontalk'] = new Array('n','Spjallsíðan fyrir þessa IP tölu');
ta['pt-preferences'] = new Array('','Almennar stillingar');
ta['pt-watchlist'] = new Array('l','Vaktlistinn.');
ta['pt-mycontris'] = new Array('y','Listi yfir framlög þín');
ta['pt-login'] = new Array('o','Þú ert hvattur/hvött til að innskrá þig, það er hinsvegar ekki nauðsynlegt.');
ta['pt-anonlogin'] = new Array('o','Þú ert hvattur/hvött til að innskrá þig, það er hinsvegar ekki nauðsynlegt.');
ta['pt-logout'] = new Array('','Útskráning');
ta['ca-talk'] = new Array('t','Spallsíða þessarar síðu');
ta['ca-edit'] = new Array('e','Þú getur breytt síðu þessari, vinsamlegast notaðu „forskoða“ hnappinn áður en þú vistar');
ta['ca-addsection'] = new Array('+','Viðbótarumræða.');
ta['ca-viewsource'] = new Array('e','Síða þessi er vernduð, þú getur þó skoðað frumkóða hennar.');
ta['ca-history'] = new Array('h','Eldri útgáfur af síðunni.');
ta['ca-protect'] = new Array('=','Vernda þessa síðu');
ta['ca-delete'] = new Array('d','Eyða þessari síðu');
ta['ca-undelete'] = new Array('d','Endurvekja breytingar á síðu þessari fyrir en henni var tortímt');
ta['ca-move'] = new Array('m','Færa þessa síðu');
ta['ca-watch'] = new Array('w','Bæta þessari síðu við á vaktlistann');
ta['ca-unwatch'] = new Array('w','Fjarlægja þessa síðu af vaktlistanum');
ta['search'] = new Array('f','Leit');
ta['p-logo'] = new Array('','Forsíða');
ta['n-mainpage'] = new Array('z','Forsíða {{SITENAME}}');
ta['n-portal'] = new Array('','Um verkefnið, hvernig er hægt að hjálpa og hvar á að byrja');
ta['n-currentevents'] = new Array('','Líðandi stund');
ta['n-recentchanges'] = new Array('r','Listi yfir nýlegar breytingar.');
ta['n-randompage'] = new Array('x','Handahófsvalin síða');
ta['n-help'] = new Array('','Efnisyfirlit yfir hjálparsíður.');
ta['n-sitesupport'] = new Array('','Fjárframlagssíða');
ta['t-whatlinkshere'] = new Array('j','Listi yfir síður sem tengjast í þessa');
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
ta['ca-nstab-project'] = new Array('a','Sýna verkefnasíðuna');
ta['ca-nstab-image'] = new Array('c','Sýna myndasíðuna');
ta['ca-nstab-mediawiki'] = new Array('c','Sýna kerfisskilaboðin');
ta['ca-nstab-template'] = new Array('c','View the template');
ta['ca-nstab-help'] = new Array('c','Sýna hjálparsíðuna');
ta['ca-nstab-category'] = new Array('c','Sýna efnisflokkasíðuna');",
'about' => "Um",
'aboutpage' => "Project:Um",
'aboutsite' => "Um {{SITENAME}}",
'accmailtext' => "Lykilorðið fyrir „$1“ hefur verið sent á $2.",
'accmailtitle' => "Lykilorð sent.",
'acct_creation_throttle_hit' => "Fyrirgefðu, þú hefur nú þegar búið til $1 aðgang(a). Þú getur ekki búið til fleiri.",
'actioncomplete' => "Aðgerð lokið",
'addedwatch' => "Bætt á vaktlistann",
'addedwatchtext' => "Síðunni „$1“ hefur verið bætt á [[Special:Watchlist|Vaktlistann]] þinn.
Frekari breytingar á henni eða spallsíðu hennar munu verða sýndar þar.
Þar að auki verður síða þessi '''feitletruð''' á [[Special:Recentchanges|Nýlegum breytingum]]
svo auðveldara sé að sjá hana þar meðal fjöldans.

<p>Til að fjarlægja síðu þessa af vaktlistanum þarft þú að ýta á tengilinn er merktur er „afvakta“.",
'allmessages' => "Kerfismeldingar",
'allmessagescurrent' => "Núverandi texti",
'allmessagesdefault' => "Sjálfgefinn texti",
'allmessagesname' => "Titill",
'allmessagestext' => "Listi yfir meldingar í „{{ns:8}}“ nafnarýminu.",
'allpages' => "Allar síður",
'alphaindexline' => "$1 til $2",
'alreadyloggedin' => "<strong>Notandinn $1 er þegar innskráður!</strong><br />",
'ancientpages' => "Elstu síður",
'anontalkpagetext' => "----Þetta er spjallsíða fyrir óskráðan notanda sem hefur ekki búið til aðgang enn þá eða notar hann ekki, slíkir notendur þekkjast á [[IP tala|IP tölu]] sinni. Það getur gerst að margir notendur deili sömu IP tölu þannig að athugasemdum sem beint er til eins notanda geta birst á spjallsíðu annars. [[Special:Userlogin|Skráðu þig sem notanda]] til að koma í veg fyrir svona misskilning.''",
'apr' => "apr",
'april' => "apríl",
'articleexists' => "Annaðhvort er þegar til síða undir þessum titli,
eða sá titill sem þú hefur valið er ekki gildur.
Vinsamlegast veldu annan titil.",
'aug' => "ágú",
'august' => "ágúst",
'badfilename' => "Skáarnafninu hefur verið breytt í „$1“.",
'badquery' => "Illa sniðin leitarfyrirspurn",
'badtitle' => "Ógildur titill",
'badtitletext' => "Umbeðin síðutitill er ógildur.",
'blanknamespace' => "(Aðalnafnrýmið)",
'blockip' => "Banna notanda",
'blockipsuccesstext' => "„$1“ hefur verið bannaður.<br />
Sjá [[Special:Ipblocklist|bannaðar notendur og IP tölur]] fyrir yfirlit yfir núverandi bönn.",
'blockiptext' => "Hægt er að hindra einstaka notendur eða IP tölur í að gera breytingar á {{SITENAME}}

Útrennslutímar eru í stöðluðu GNU sniði sem farið er yfir í [http://www.gnu.org/software/tar/manual/html_chapter/tar_7.html tar handbókinni], Til dæmis „1 hour“, „2 days“, „next Wednesday“, „1 January 2017“ eða „indefinite“ og „infinite“ til að banna að eylífu, þetta ætti þó aðeins að vera notað á ódauðlegar verur þar sem um 150 ár ættu að duga jafnvel á þrjóskasta fólk.

Sjá [[meta:Range blocks|Range blocks]] á meta fyrir yfirlit yfir [[CIDR]] tölur, [[{{ns:Special}}:Ipblocklist|bannaða notendur og IP tölur]] fyrir lista yfir þá sem nú eru bannaðir og [[{{ns:4}}:Bönnunarskrá|bönnunarskrá]] fyrir lista sem inniheldur einnig þá sem hafa verið bannaðir í fortíðinni.",
'blocklink' => "banna",
'blocklistline' => "$1, $2 bannaði $3 ($4)",
'infiniteblock' => 'rennur út infinite', //fixme
'expiringblock' => 'rennur út  $1',
'blocklogpage' => "Bönnunarskrá",
'blocklogtext' => "This is a log of user blocking and unblocking actions. Automatically
blocked IP addresses are not be listed. See the [[Special:Ipblocklist|IP block list]] for
the list of currently operational bans and blocks.",
'bold_sample' => "Feitletraður texti",
'bold_tip' => "Feitletraður texti",
'booksources' => "Bókabúðir",
'bydate' => "eftir dagsetningu",
'byname' => "eftir nafni",
'bysize' => "eftir stærð",
'cachederror' => "Eftirfarandi er afrit af umbeðinni síðu og gæti því ekki verið nýjasta útgáfa hennar:",
'cancel' => "Hætta við",
'cantrollback' => "Ekki hægt að taka aftur breytingu, síðasti höfundur er eini höfundur þessarar síðu.",
'categories' => "Flokkar",
'category_header' => "Greinar í flokknum „$1“",
'categoryarticlecount' => "Það eru $1 síður í þessum flokki.",
'changepassword' => "Breyta lykilorði",
'changes' => "Breytingar",
'clearyourcache' => "'''Ath:''' Eftir að þú hefur vistað breytingar þarf að hreynsa flýtiskrár vafrarans til að sjá þær, í '''Mozilla / Firefox''' ''CTRL-Shift-R'', '''IE:''' ''CTRL-F5'', '''Safari:''' ''CMD-Shift-R'', '''Konqueror:''' ''F5''.",
'columns' => "Dálkar",
'compareselectedversions' => "Bera saman valdar útgáfur",
'confirm' => "Staðfesta",
'confirmdelete' => "Staðfesting á eyðingu",
'confirmprotect' => "Verndunarstaðfesting",
'confirmprotecttext' => "Ertu viss um að þú viljir vernda þessa síðu?",
'confirmunprotect' => "Afverndunarstaðfesting",
'confirmunprotecttext' => "Ertu viss um að þú viljir afvernda þessa síðu?",
'contextchars' => "Stafir í samhengi á hverja línu",
'contextlines' => "Línur á hverja niðurstöðu",
'contribslink' => "framlög",
'contribsub' => "Eftir $1",
'contributions' => "Framlög notanda",
'copyright' => "Efni síðunnar má nota undir $1.",
'copyrightpage' => "Project:Höfundarréttur",
'copyrightpagename' => "Höfundarréttarreglum {{SITENAME}}",
'createaccount' => "Nýskrá",
'createaccountmail' => "með netfangi",
'cur' => "nú",
'currentevents' => "Líðandi stund",
'currentevents-url' => "Líðandi stund",
'currentrev' => "Núverandi útgáfa",
'currentrevisionlink' => "núverandi útgáfa",
'databaseerror' => "Gagnagrunnsvilla",
'dateformat' => "Tímasnið",
'datedefault' => 'Sjálfgefið',
'deadendpages' => "Botnlangar",
'dec' => "des",
'december' => "desember",
'defaultns' => "Leita í þessum nafnrýmum:",
'defemailsubject' => "Varðandi {{SITENAME}}",
'delete' => "Eyða",
'deletecomment' => "Ástæða",
'deletedarticle' => "tortímdi „$1“",
'deletedtext' => "„$1“ hefur verið eytt. Sjá lista yfir nýlegar eyðingar í $2.",
'deleteimg' => "eyða",
'deleteimgcompletely' => "Eyða öllum útgáfum",
'deletesub' => "(Eyði: „$1“)",
'deletethispage' => "Eyða þessari síðu",
'deletionlog' => "eyðingaskrá",
'dellogpage' => "Eyðingaskrá",
'diff' => "breyting",
'difference' => "(Munur milli útgáfa)",
'disambiguations' => "Aðgreiningarsíður",
'disambiguationstext' => "The following pages link to a <i>disambiguation page</i>. They should link to the appropriate topic instead.<br />A page is treated as dismbiguation if it is linked from $1.<br />Links from other namespaces are <i>not</i> listed here.",
'disclaimerpage' => "Project:Almennur fyrirvari",
'disclaimers' => "Fyrirvarar",
'edit' => "Breyta",
'edithelp' => "Breytingarhjálp",
'edithelppage' => "Hjálp:Breyta",
'editing' => "Breyti $1",
'editinguser' => "Breyti $1",
'editingcomment' => "Breyti $1 (bæti við athugasemd)",
'editingold' => "<strong>ATH: Þú ert að breyta gamalli útgáfu þessarar síðu og munu allar breytingar sem gerðar hafa verið á henni frá þeirri útgáfu vera fjarlægðar ef þú vistar.</strong>",
'editingsection' => "Breyti $1 (hluta)",
'editsection' => "breyta",
'editold' => "breyta",
'editthispage' => "Breyta þessari síðu",
'emailfrom' => "Frá",
'emailmessage' => "Skilaboð",
'emailpage' => "Senda tölvupóst",
'emailpagetext' => "Hafi notandi þessi fyllt út gild tölvupóstfang í stillingum sínum er hægt að senda skilaboð til hans eða hennar hér. Póstfangið sem þú fylltir út í stillingum þínum mun byrtast í „From:“ hlutanum svo viðtakandinn geti svarað.",
'emailsend' => "Senda",
'emailsent' => "Sending tókst",
'emailsenttext' => "Skilaboðin þín hafa verið send.",
'emailsubject' => "Fyrirsögn",
'emailto' => "Til",
'emailuser' => "Senda þessum notanda tölvupóst",
/* 'enterlockreason' => "Enter a reason for the lock, including an estimate
of when the lock will be released", */
'error' => "Villa",
'errorpagetitle' => "Villa",
'excontent' => "innihaldið var: '$1'",
'explainconflict' => "Síðunni hefur verið breytt síðan þú byrjaðir að gera breytingar á henni, textinn í efri reitnum inniheldur núverandi útgáfu úr gagnagrunni og sá neðri inniheldur þína útgáfu, þú þarft hér að færa breytingar sem þú vilt halda úr neðri reitnum í þann efri og vista síðuna. <strong>Aðeins</strong> texti úr efri reitnum mun vera vistaður þegar þú vistar.",
'export' => "XML útgáfa síðu",
'exportcuronly' => "Aðeins núverandi útgáfu án breytingarskrá",
'extlink_sample' => "http://www.example.com titill tengils",
'extlink_tip' => "Ytri tengill (muna að setja http:// á undan)",
'feb' => "feb",
'february' => "febrúar",
'feedlinks' => "Nippan:",
'filedesc' => "Lýsing",
'fileexists' => "Skrá með þessu nafni er þegar til, skoðaðu $1 ef þú ert óviss um hvort þú viljir breyta henni, ekki verður skrifað yfir gömlu skránna hlaðiru inn nýrri með sama nafni heldur verður núverandi útgáfa geymd í útgáfusögu.",
'filename' => "Skráarnafn",
'fileuploaded' => "Skránni „$1“ hefur verið bætt við á {{SITENAME}}.
Fylgdu þessum tengli: $2 á lýsingarsíðu skráarinnar og fylltu út
upplýsingar um skránna, svosem um uppruna hennar, höfund og aðrar
upplýsingar um hana.",
'friday' => "föstudagur",
'go' => "Áfram",
'searcharticle' => "Áfram",
'guesstimezone' => "Fylla inn",
'headline_sample' => "Fyrirsagnartexti",
'headline_tip' => "Annars stigs fyrirsögn",
'help' => "Hjálp",
'helppage' => "Hjálp:Efnisyfirlit",
'hide' => "Fela",
'hidetoc' => "fela",
'hist' => "breytingaskrá",
'histlegend' => "Skýringar: (nú) = bera saman við núverandi útgáfu,
(breyting) = bera saman við útgáfun á undan, M = minniháttar breyting.",
'history' => "breytingaskrá",
'history_short' => "Breytingaskrá",
'historywarning' => "Athugið: Síðan sem þú ert um það bil að eyða á sér&nbsp;",
'hr_tip' => "Lárétt lína (notist sparlega)",
'ilsubmit' => "Leita",
'image_sample' => "Sýnishorn.jpeg",
'image_tip' => "Setja inn mynd",
'imagelinks' => "Myndatenglar",
'imagelist' => "Skráalisti",
'imagelisttext' => "Hér fyrir neðan er $1 skrám raðað $2.",
'imgdelete' => "eyða",
'imgdesc' => "lýsing",
'imghistlegend' => "Skýringar: (nú) = bera saman við núverandi útgáfu,
(breyting) = bera saman við útgáfun á undan, M = minniháttar breyting.

Legend: (nú) = núverandi útgáfa,
(eyða) = eyða þessari útgáfu, (nota) = nota þessa útgáfu í stað núverandi útgáfu.
<br /><em>Fylgdu dagsetningartenglunum til að sjá mynd sem hlaðið var inn á þeim tíma</em>.",
'imghistory' => "Breytingaskrá myndar",
'imglegend' => "Skýringar: (lýsing) = sýna og/eða breyta lýsingu skráar.",
'ipaddress' => "IP Tala/notendanafn",
'ipbexpiry' => "Rennur út eftir",
'ipblocklist' => "Bannaðar notendur og IP tölur",
'ipbreason' => "Ástæða",
'ipbsubmit' => "Banna notanda",
'ipusubmit' => "Afbanna",
'isredirect' => "tilvísun",
'italic_sample' => "Skáletraður texti",
'italic_tip' => "Skáletraður texti",
'jan' => "jan",
'january' => "janúar",
'jul' => "júl",
'july' => "júlí",
'jun' => "jún",
'june' => "júní",
'last' => "breyting",
'lastmodifiedat' => "Þessari síðu var síðast breytt $2, $1.",
'lineno' => "Lína $1:",
'link_sample' => "Titill tengils",
'link_tip' => "Innri tengill",
'linklistsub' => "(Listi yfir ítengdar síður)",
'linkshere' => "Eftirfarandi síður tengjast hingað:",
'linkstoimage' => "Eftirfarandi síður tengjast í mynd þessa:",
'listingcontinuesabbrev' => " frh.",
'listusers' => "Notendalisti",
'localtime' => "Staðartími",
'lockdb' => "Læsa gagnagrunninum",
'login' => "Innskrá",
'loginerror' => "Innskráningarvilla",
'loginsuccess' => "Þú ert nú innskráð(ur) á {{SITENAME}} sem „$1“.",
'loginsuccesstitle' => "Innskráning tókst",
'logout' => "Útskráning",
'logouttext' => "Þú hefur verið skráð(ur) út.
Þú getur þó haldið áfram að nota {{SITENAME}} nafnlaust og þú getur skráð þig inn sem annar notandi. Athugaðu að sumar síður kunna að birtast líkt og þú sért ennþá innskráður, hægt er að koma í veg fyrir það með því að hreinsa biðminnið í vafranum.",
'lonelypages' => "Munaðarlausar síður",
'longpages' => "Langar síður",
'mailmypassword' => "Senda nýtt lykilorð með tölvupósti",
'mainpage' => "Forsíða",
'makesysop' => "Veita stjórnandaréttindi",
'makesysopname' => "Notandi:",
'makesysopok' => "<strong>Notandanum „$1“ hefur verið veitt stjórnandastaða</strong>",
'makesysopsubmit' => "Gera að stjórnanda",
'makesysoptext' => "Kerfissíða þessi er notuð af möppudýrum til að veita venjulegum notendum stjórnendaréttindi.",
'mar' => "mar",
'march' => "mars",
'math' => "Birting stærðfræðiformúlna",
'math_sample' => "Formúlan setjist hér",
'math_tip' => "LaTeX Stærðfræðiformúla",
'may' => "maí",
'may_long' => "maí",
'media_sample' => "Sýnishorn.ogg",
'media_tip' => "Tengill í margmiðlunarskrá",
'minoredit' => "Minniháttar breyting",
'missingimage' => "<b>Mynd vantar</b><br /><i>$1</i>",
'monday' => "mánudagur",
'move' => "Færa",
'movearticle' => "Færa",
'movenologin' => "Óinnskráð(ur)",
'movenologintext' => "Þú verður að vera [[Kerfissíða:Userlogin|innskráð(ur)]] til  að geta fært síður.",
'movepage' => "Færa síðu",
'movepagebtn' => "Færa síðuna",
'movepagetalktext' => "Spallsíða síðunnar verður sjálfkrafa færð með ef hún er til nema:
* Þú sért að færa síðuna á milli nafnrýma
* Spallsíða sé þegar til undir nýja nafninu
* Þú veljir að færa hana ekki
Í þeim tilfellum verður að færa hana handvirkt.",
'movepagetext' => "Hér er hægt að endurnefna síðu, hún mun ásamt breytingarskrá hennar
verða færð á nýja nafnið og núverandi staðsetning mun
breytast í tilvísun sem vísa mun á nýju staðsetninguna,
tenglar í núverandi nafn munu hinsvegar ekki breytast,
athugaðu að þetta búi ekki til margfaldar
tilvísanir, það er á þína ábyrgð að tryggja það að tenglar haldi áfram
að vísa á rétta síðu.

Athugaðu að síðan mun '''ekki''' verða færð ef það er þegar síða á nafninu
sem þú hyggst færa hana á, nema síða sú sé tóm eða tilvísun sem á sér enga
breytingarsögu. Þú getur þar með fært síðuna aftur til baka án þess að
missa breytingarsöguna, en ekki fært hana venjulega síðu.

'''Varúð:'''
Vertu viss um að skilja afleiðingarnar af þessari aðgerð vel. Þetta gæti þýtt
mjög rótækar breytingar á vinsælum síðum og valdið titringi hjá öðrum notendum.",
'movetalk' => "Færa „Spjall“ síðuna líka ef við á.",
'movethispage' => "Færa þessa síðu",
'mw_math_html' => "HTML ef hægt er, annars PNG",
'mw_math_mathml' => "MathML",
'mw_math_modern' => "Mælt með fyrir nýja vafra",
'mw_math_png' => "Alltaf birta PNG mynd",
'mw_math_simple' => "HTML fyrir einfaldar jöfnur annars PNG",
'mw_math_source' => "Sýna TeX jöfnu (fyrir textavafra)",
'mycontris' => "Framlög",
'mypage' => "Mín síða",
'mytalk' => "Spjall",
'navigation' => "Flakk",
'newarticle' => "(Ný)",
'newimages' => "Gallerí nýlegra skráa",
'newmessageslink' => "ný skilaboð",
'newpages' => "Nýjar síður",
'newpassword' => "Nýja lykilorðið",
'newtitle' => "Yfir á",
'newwindow' => "(í nýjum glugga)",
'nextdiff' => "Næsta breyting →",
'nextn' => "næstu $1",
'nextrevision' => "Næsta útgáfa→",
'noarticletext' => "'''Það er ekki enn grein undir þessu nafni á {{SITENAME}}.'''
* '''[{{fullurl:{{NAMESPACE}}:{{PAGENAME}}|action=edit}} Skrifa grein undir nafninu „{{PAGENAME}}“]'''
* [[{{ns:special}}:Search/{{PAGENAME}}|Leita að „{{PAGENAME}}“]] í öðrum greinum og síðum.",
'noemailtext' => "Notandi þessi hefur kosið að fá ekki tölvupóst frá öðrum notendum eða hefur ekki fyllt út netfang sitt í stillingum.",
'noemailtitle' => "Ekkert póstfang",
'nolinkshere' => "Engar síður tengjast hingað.",
'nolinkstoimage' => "Engar síður tengja í hingað.",
'noname' => "Ógilt notendanafn.",
'nosuchuser' => "Enginn notandi er til undir nafninu „$1“.",
'note' => "<strong>Athugið:</strong>",
'notextmatches' => "Leitarorð fannst/fundust ekki í innihaldi greina",
'notitlematches' => "Engir greinatitlar pössuðu við fyrirspurnina",
'nov' => "nóv",
'november' => "nóvember",
'nowatchlist' => "Vaktlistinn er tómur.",
'nowiki_sample' => "Innsetjið ósniðinn texta hér",
'nowiki_tip' => "Hunsa wikisnið",
'nstab-category' => "Efnisflokkur",
'nstab-help' => "Hjálp",
'nstab-image' => "Mynd",
'nstab-main' => "Grein",
'nstab-mediawiki' => "Skilaboð",
'nstab-template' => "Forsnið",
'nstab-user' => "Notandasíða",
'nstab-project' => "Um",
'oct' => "okt",
'october' => "október",
'oldpassword' => "Gamla lykilorðið",
'otherlanguages' => "Á öðrum tungumálum",
'pagecategories' => "Flokkar",
'pagemovedsub' => "Færsla tókst",
'pagemovedtext' => "Síðan „[[$1]]“ var færð yfir á „[[$2]]“.",
'pagetitle' => "$1 - {{SITENAME}}",
'perfcached' => "Eftirfarandi er afrit af umbeðinni síðu og gæti því ekki verið nýjasta útgáfa hennar:",
'perfdisabled' => "Þessi síða hefur verið gerð óvirk þar sem notkun hennar veldur of miklu álagi á gagnagrunninum.",
'personaltools' => "Tenglar",
'portal' => "Samfélagsgátt",
'portal-url' => "Project:Samfélagsgátt",
'powersearch' => "Leita",
'powersearchtext' => "Leita í eftirfarandi nafnrýmum :<br />
$1<br />
$2 Sýna tilvísarnir &nbsp; Leita að $3 $9",
'preferences' => "Stillingar",
'prefs-misc' => "Aðrar stillingar",
'prefs-personal' => "Notendaupplýsingar",
'prefs-rc' => "Nýlegar breytingar og stubbar",
'prefsnologintext' => "You must be [[Special:Userlogin|logged in]]
to set user preferences.",
'preview' => "Forskoða",
'previewnote' => " Það sem sést hér er aðeins forskoðun og hefur ekki enn verið vistað.",
'previousdiff' => "← Fyrri breyting",
'previousrevision' => "←Fyrri útgáfa",
'prevn' => "síðustu $1",
'printableversion' => "Prentvæn útgáfa",
'protect' => "Vernda",
'protectcomment' => "Ástæða",
'protectedpagewarning' => "<!-- -->",
'protectlogpage' => "Verndunarskrá",
'protectsub' => "(Vernda „$1“)",
'qbedit' => "Breyta",
'qbsettings' => "Valblað",
'randompage' => "Handahófsvalin síða",
'rclinks' => "Sýna síðustu $1 breytingar síðustu $2 daga<br />$3",
'rclistfrom' => "Sýna breytingar frá og með $1",
'rclsub' => "(á síðum sem tengd er í frá „$1“)",
'rcnote' => "Að neðan eru síðustu <strong>$1</strong> breytingar síðustu <strong>$2</strong> daga.",
'recentchanges' => "Nýlegar breytingar",
'recentchangescount' => "Fjöldi síðna á „nýlegum breytingum“",
'recentchangeslinked' => "Skyldar breytingar",
'redirectedfrom' => "(Tilvísun frá $1)",
'remembermypassword' => "Muna.",
'removechecked' => "Fjarlægja merktar síður af vaktlistanum",
'removedwatch' => "Fjarlægt af vaktlistanum",
'removedwatchtext' => "Síðan \"$1\" hefur verið fjarlægð af vaktlistanum.",
'removingchecked' => "Fjarlægi umbeðnar síðu(r) af vaktlistanum...",
'resetprefs' => "Endurstilla valmöguleika",
'restorelink' => "$1 eydda(r) breyting(u/ar)",
'resultsperpage' => "Niðurstöður á síðu",
'retrievedfrom' => "Af „$1“",
'returnto' => "Tilbaka: $1.",
'retypenew' => "Endurtaktu nýja lykilorðið",
'reupload' => "Endurinnhlaða",
'reuploaddesc' => "Aftur á innhlaðningarformið.",
'reverted' => "Breytt aftur til fyrri útgáfu",
'revertimg' => "nota",
'revertpage' => "Tók aftur breytingar $2, breytt til síðustu útgáfu $1",
'revhistory' => "Útgáfusaga",
'revisionasof' => "Útgáfa síðunnar kl. $1",
'rollback' => "Taka aftur breytingar",
'rollback_short' => "Afturtaka",
'rollbackfailed' => "Afturtaka mistókst",
'rollbacklink' => "afturtaka",
'rows' => "Raðir",
'saturday' => "laugardagur",
'savearticle' => "Vista",
'savedprefs' => "Stillingarnar þínar hafa verið vistaðar.",
'savefile' => "Vista",
'saveprefs' => "Vista stillingar",
'search' => "Leit",
'searchbutton' => "Leit",
'searchsubtitle' => "Fyrir fyrirspurnina „[[:$1]]“",
'searchsubtitleinvalid' => "Fyrir fyrirspurnina „$1“",
'searchresults' => "Leitarniðurstöður",
'searchresultshead' => "Leit",
'searchresulttext' => "Fyrir frekari upplýsingar um leit á {{SITENAME}}, sjá $1.",
'sep' => "sep",
'september' => "september",
'servertime' => "Tími netþjóns",
'setbureaucratflag' => "Einnig möppudýr",
'shortpages' => "Stuttar síður",
'show' => "Sýna",
'showingresults' => "Sýni <strong>$1</strong> niðurstöður frá og með #<strong>$2</strong>.",
'showingresultsnum' => "Sýni <strong>$1</strong> niðurstöður frá og með #<strong>$2</strong>.",
'showlast' => "Sýna síðustu $1 skrár raðaðar $2.",
'showpreview' => "Forskoða",
'showtoc' => "sýna",
'sig_tip' => "Undirskrift þín auk tímasetningu",
'sitestats' => "Almenn tölfræði",
'sitestatstext' => "Nú eru alls '''$1''' síður í gagnagrunninum,
þar á meðal „spjall“ síður, síður er snúa að {{SITENAME}} verkefninu,
„stubbar“, tilvísanir og annað efni er ekki telst til greina.
Þar fyrir utan eru '''$2''' síður sem líklega teljast fullgildar greinar.

'''$3''' síður hafa verið skoðaðar og '''$4''' breytingar hafa verið gerðar
síðan vefurinn var settur up. Það reiknast sem '''$5''' breytingar
á hverja síðu að meðaltali, og '''$6''' fléttingar fyrir hverja breytingu.",
'sitesupport' => "Framlög",
'skin' => "Þema",
'specialpage' => "Kerfissíða",
'specialpages' => "Kerfissíður",
'spheading' => "Almennar",
'statistics' => "Tölfræði",
'storedversion' => "Geymd útgáfa",
'stubthreshold' => "Stubbamerkja allt undir",
'subcategories' => "Undirflokkar",
'subcategorycount' => "Það eru $1 undirflokkar í þessum flokki.",
'subject' => "Fyrirsögn",
'successfulupload' => "Innhlaðning tókst",
'summary' => "Breytingar",
'sunday' => "sunnudagur",
'talk' => "Spjall",
'talkpage' => "Ræða um þessa síðu",
'talkpagenotmoved' => "Samsvarandi spjallsíða var <strong>ekki</strong> færð.",
'templatesused' => "Snið notuð á síðunni:",
'textboxsize' => "Breytingarflipinn",
'textmatches' => "Leitarorð fannst/fundust í innihaldi eftirfarandi greina",
'thisisdeleted' => "Endurvekja eða skoða $1?",
'thursday' => "fimmtudagur",
'timezonelegend' => "Tímabelti",
'timezoneoffset' => "Hliðrun",
'timezonetext' => "Hliðrun staðartíma frá UTC+0.",
'titlematches' => "Titlar greina sem pössuðu við fyrirspurnina",
'toc' => "Efnisyfirlit",
'tog-editondblclick' => "Breyta síðu ef tvísmellt er á hlekkinn (JavaScript)",
'tog-editsection' => "Leyfa breytingar á hluta síðna með [edit] hlekkjum",
'tog-editsectiononrightclick' => "Leyfa breytingar á hluta síðna með því að  hægrismella á titla (JavaScript)",
'tog-editwidth' => "Innsláttarsvæði hefur fulla breidd",
'tog-hideminor' => "Fela minniháttar breytingar",
'tog-highlightbroken' => "Sýna brotna hlekki <a href=\"\" class=\"new\">svona</a> (annars: svona<a href=\"\" class=\"internal\">?</a>).",
'tog-justify' => "Jafna málsgreinar",
'tog-minordefault' => "Láta breytingar vera sjálfgefnar sem minniháttar",
'tog-nocache' => "Slökkva á flýtivistun síðna",
'tog-numberheadings' => "Númera fyrirsagnir sjálfkrafa",
'tog-previewontop' => "Setja prufuhnapp fyrir framan breytingahnapp",
'tog-rememberpassword' => "Muna lykilorð",
'tog-showtoc' => "Sýna efnisyfirlit",
'tog-underline' => "Undirstrika hlekki",
'tog-usenewrc' => "Endurbætt nýjar tengingar (ekki fyrir alla vafra)",
'tog-watchdefault' => "Bæta síðum sem þú breytir við eftirlitslista",
'toolbox' => "Verkfæri",
'tooltip-compareselectedversions' => "Sjá breytingarnar á þessari grein á milli útgáfanna sem þú valdir. [alt-v]",
'tooltip-minoredit' => "Merktu þessa breytingu sem minniháttar [alt-i]",
'tooltip-preview' => "Forskoða breytingarnar, vinsamlegast gerðu þetta áður en þú vistar! [alt-p]",
'tooltip-save' => "Vista breytingarnar [alt-s]",
'tooltip-search' => "Leit á þessari Wiki [alt-f]",
'tuesday' => "þriðjudagur",
'unblockip' => "Afbanna notanda",
'unblockiptext' => "Endurvekja skrifréttindi bannaðra notenda eða IP talna.",
'unblocklink' => "afbanna",
'uncategorizedcategories' => "Óflokkaðir flokkar",
'uncategorizedpages' => "Óflokkaðar síður",
'undelete' => "Endurvekja eydda síðu",
'undelete_short' => "Endurvekja $1 breyting(u/ar)",
'undeletearticle' => "Endurvekja eydda síðu",
'undeletebtn' => "Endurvekja!",
'undeletepage' => "Skoða og endurvekja síður",
'undeletepagetext' => "Eftirfarandi síðum hefur verið eitt en eru þó enn í gagnagrunninum og geta verið endurvaknar. Athugið að síður þessar eru reglulega fjarlægðar endanlega úr gagnagrunninum.",
'undeleterevisions' => "$1 breyting(ar)",
'unlockdb' => "Aflæsa gagnagrunninum",
'unprotect' => "Afvernda",
'unprotectcomment' => "Ástæða",
'unprotectsub' => "(Afvernda „$1“)",
'unusedimages' => "Munaðarlausar skrár",
'unusedimagestext' => "<p>Please note that other web sites may link to an image with
a direct URL, and so may still be listed here despite being
in active use.",
'unwatch' => "Afvakta",
'upload' => "Innhlaða",
'uploadbtn' => "Hlaða inn skrá",
'uploadedimage' => "hlóð inn \"$1\"",
'uploaderror' => "Villa í innhlaðningu",
'uploadlog' => "innhlaðningarskrá",
'uploadlogpage' => "Innhlaðningarskrá",
'uploadnologin' => "Óinnskráð(ur)",
'uploadnologintext' => "You must be [[Special:Userlogin|logged in]]
to upload files.",
/*'uploadtext' => "'''Áður en skrá er hlaðið inn''':
* Notaðu [[JPEG]] skráarsniðið fyrir ljósmyndir, [[GIF]] fyrir hreyfimyndir, [[PNG]] fyrir aðrar myndir og [[Ogg Vorbis]] fyrir hljóðskrár.

'''Eftir að skrá er hlaðið inn''':
* Veittu nákvæmar upplýsingar um skránna á skráarsíðunni, t.d. um myndina (hvenær hún er tekin, hvar o.s.f.)
* Gefðu upplýsingar um leyfið sem hún er undir, <code><nowiki>{{</nowiki>GFDL<nowiki>}}</nowiki></code> fyrir [[commons:Commons:Copyright tags#GNU Licenses|GNU FDL]] og <code><nowiki>{{</nowiki>Óhöfundaréttvarið<nowiki>}}</nowiki></code> ef hún er óvernduð af alþjóða höfundarlögum.",*/
'uploadwarning' => "Aðvörun",
'usercssjsyoucanpreview' => "<strong>Ath:</strong> Hægt er að nota „Forskoða“ hnappinn til að prófa CSS og JavaScript kóða áður en hann er vistaður.",
'userlogin' => "Innskrá",
'userlogout' => "Útskrá",
'userstats' => "Notendatölfræði",
'userstatstext' => "Það eru '''$1''' skráðir notendur, þar af eru '''$2''' eða '''$4%''' stjórnendur (sjá $3).",
'version' => "Útgáfa",
'viewprevnext' => "Skoða ($1) ($2) ($3).",
'viewsource' => "Skoða wikikóða",
'viewtalkpage' => "Skoða umræðu",
'wantedpages' => "Eftirsóttar síður",
'watch' => "Vakta",
'watchdetails' => "Fyrir utan spjallsíður eru $1 síða/síður á vaktlistanum þínum. Hægt er að
[$4 sýna heildarlistann og breyta honum].",
'watcheditlist' => "Þetta er listi yfir þínar vöktuðu síður raðað í
stafrófsröð. Merktu við þær síður sem þú vilt fjarlægja
af vaktlistanum og ýttu á 'fjarlægja merktar' takkan
neðst á skjánum.",
'watchlist' => "Vaktlistinn",
'watchlistcontains' => "Á vaktlistanum eru $1 síður.",
'watchmethod-list' => "leita að breytingum í vöktuðum síðum",
'watchmethod-recent' => "kanna hvort nýlegar breytingar innihalda vaktaðar síður",
'watchnochange' => "Engri síðu á vaktlistanum þínum hefur verið breytt á tilgreindu tímabili.",
'watchnologin' => "Óinnskráð(ur)",
'watchnologintext' => "Þú verður að vera [[Special:Userlogin|innskáð(ur)]] til að geta breytt vaktlistanum.",
'watchthis' => "Vakta",
'watchthispage' => "Vakta þessa síðu",
'wednesday' => "miðvikudagur",
'welcomecreation' => "<h2>Velkomin(n), $1!</h2><p>Aðgangurinn þinn hefur verið búinn til.
Ekki gleyma að breyta {{SITENAME}} stillingunum þínum.",
'whatlinkshere' => "Hvað tengist hingað",
'whitelistacctext' => "Til að geta búið til aðganga í þessu Wiki, verður þú að [[Special:Userlogin|innskrá]] og hafa viðkomandi réttindi.",
'whitelistacctitle' => "Þér er óheimilt að skrá þig sem notanda.",
'whitelistedittext' => "Þú verður að [[Special:Userlogin|skrá þig inn]] til að geta breytt síðum.",
'whitelistedittitle' => "Þú verður að skrá þig inn til að geta breytt síðum.",
'whitelistreadtext' => "Þú verður að [[Special:Userlogin|skrá þig inn]] til að lesa síður.",
'whitelistreadtitle' => "Notandi verður að skrá sig inn til að geta lesið.",
'projectpage' => "Sýna verkefnissíðu",
'wlnote' => "Að neðan eru síðustu <b>$1</b> breytingar síðustu <b>$2</b> klukkutíma.",
'wlsaved' => "Þetta er vistuð útgáfa af vaktlistanum þínum.",
'wlshowlast' => "Sýna síðustu $1 klukkutíma, $2 daga, $3",
'wrong_wfQuery_params' => "Incorrect parameters to wfQuery()<br />
Function: $1<br />
Query: $2",
'wrongpassword' => "Uppgefið lykilorð er rangt. Vinsamlegast reyndu aftur.",
'yourdiff' => "Mismunur",
'youremail' => "Tölvupóstfangið þitt*",
'yourlanguage' => "Viðmótstungumál",
'yourname' => "Notendanafn",
'yournick' => "Nafn (fyrir undirskriftir)",
'yourpassword' => "Lykilorð",
'yourpasswordagain' => "Lykilorð (aftur)",
'yourrealname' => "Fullt nafn þitt*",
'yourtext' => "Þinn texti",
);

?>

<?php
/** Icelandic (Íslenska)
 *
 * @addtogroup Language
 */

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
# User preference toggles
'tog-underline'               => 'Undirstrika hlekki',
'tog-highlightbroken'         => 'Sýna brotna hlekki <a href="" class="new">svona</a> (annars: svona<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Jafna málsgreinar',
'tog-hideminor'               => 'Fela minniháttar breytingar',
'tog-usenewrc'                => 'Endurbætt nýjar tengingar (ekki fyrir alla vafra)',
'tog-numberheadings'          => 'Númera fyrirsagnir sjálfkrafa',
'tog-editondblclick'          => 'Breyta síðu ef tvísmellt er á hlekkinn (JavaScript)',
'tog-editsection'             => 'Leyfa breytingar á hluta síðna með [edit] hlekkjum',
'tog-editsectiononrightclick' => 'Leyfa breytingar á hluta síðna með því að  hægrismella á titla (JavaScript)',
'tog-showtoc'                 => 'Sýna efnisyfirlit',
'tog-rememberpassword'        => 'Muna lykilorð',
'tog-editwidth'               => 'Innsláttarsvæði hefur fulla breidd',
'tog-watchdefault'            => 'Bæta síðum sem þú breytir við eftirlitslista',
'tog-minordefault'            => 'Láta breytingar vera sjálfgefnar sem minniháttar',
'tog-previewontop'            => 'Setja prufuhnapp fyrir framan breytingahnapp',
'tog-nocache'                 => 'Slökkva á flýtivistun síðna',

# Dates
'sunday'    => 'sunnudagur',
'monday'    => 'mánudagur',
'tuesday'   => 'þriðjudagur',
'wednesday' => 'miðvikudagur',
'thursday'  => 'fimmtudagur',
'friday'    => 'föstudagur',
'saturday'  => 'laugardagur',
'january'   => 'janúar',
'february'  => 'febrúar',
'march'     => 'mars',
'april'     => 'apríl',
'may_long'  => 'maí',
'june'      => 'júní',
'july'      => 'júlí',
'august'    => 'ágúst',
'september' => 'september',
'october'   => 'október',
'november'  => 'nóvember',
'december'  => 'desember',
'jan'       => 'jan',
'feb'       => 'feb',
'mar'       => 'mar',
'apr'       => 'apr',
'may'       => 'maí',
'jun'       => 'jún',
'jul'       => 'júl',
'aug'       => 'ágú',
'sep'       => 'sep',
'oct'       => 'okt',
'nov'       => 'nóv',
'dec'       => 'des',

# Bits of text used by many pages
'categories'      => 'Flokkar',
'pagecategories'  => 'Flokkar',
'category_header' => 'Greinar í flokknum „$1“',
'subcategories'   => 'Undirflokkar',

'linkprefix' => '/^(.*?)([áÁðÐéÉíÍóÓúÚýÝþÞæÆöÖA-Za-z-–]+)$/sDu',

'about'      => 'Um',
'newwindow'  => '(í nýjum glugga)',
'cancel'     => 'Hætta við',
'qbedit'     => 'Breyta',
'mypage'     => 'Mín síða',
'mytalk'     => 'Spjall',
'navigation' => 'Flakk',

'errorpagetitle'   => 'Villa',
'returnto'         => 'Tilbaka: $1.',
'help'             => 'Hjálp',
'search'           => 'Leit',
'searchbutton'     => 'Leit',
'go'               => 'Áfram',
'searcharticle'    => 'Áfram',
'history'          => 'breytingaskrá',
'history_short'    => 'Breytingaskrá',
'printableversion' => 'Prentvæn útgáfa',
'edit'             => 'Breyta',
'editthispage'     => 'Breyta þessari síðu',
'delete'           => 'Eyða',
'deletethispage'   => 'Eyða þessari síðu',
'undelete_short'   => 'Endurvekja $1 breyting(u/ar)',
'protect'          => 'Vernda',
'unprotect'        => 'Afvernda',
'talkpage'         => 'Ræða um þessa síðu',
'specialpage'      => 'Kerfissíða',
'personaltools'    => 'Tenglar',
'talk'             => 'Spjall',
'toolbox'          => 'Verkfæri',
'projectpage'      => 'Sýna verkefnissíðu',
'viewtalkpage'     => 'Skoða umræðu',
'otherlanguages'   => 'Á öðrum tungumálum',
'redirectedfrom'   => '(Tilvísun frá $1)',
'lastmodifiedat'   => 'Þessari síðu var síðast breytt $2, $1.', # $1 date, $2 time

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Um {{SITENAME}}',
'aboutpage'         => 'Project:Um',
'copyright'         => 'Efni síðunnar má nota undir $1.',
'copyrightpagename' => 'Höfundarréttarreglum {{SITENAME}}',
'copyrightpage'     => 'Project:Höfundarréttur',
'currentevents'     => 'Líðandi stund',
'currentevents-url' => 'Líðandi stund',
'disclaimers'       => 'Fyrirvarar',
'disclaimerpage'    => 'Project:Almennur fyrirvari',
'edithelp'          => 'Breytingarhjálp',
'edithelppage'      => 'Hjálp:Breyta',
'helppage'          => 'Hjálp:Efnisyfirlit',
'mainpage'          => 'Forsíða',
'portal'            => 'Samfélagsgátt',
'portal-url'        => 'Project:Samfélagsgátt',
'sitesupport'       => 'Framlög',

'pagetitle'       => '$1 - {{SITENAME}}',
'retrievedfrom'   => 'Af „$1“',
'newmessageslink' => 'ný skilaboð',
'editsection'     => 'breyta',
'editold'         => 'breyta',
'toc'             => 'Efnisyfirlit',
'showtoc'         => 'sýna',
'hidetoc'         => 'fela',
'thisisdeleted'   => 'Endurvekja eða skoða $1?',
'restorelink'     => '$1 eydda(r) breyting(u/ar)',
'feedlinks'       => 'Nippan:',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Grein',
'nstab-user'      => 'Notandasíða',
'nstab-project'   => 'Um',
'nstab-image'     => 'Mynd',
'nstab-mediawiki' => 'Skilaboð',
'nstab-template'  => 'Forsnið',
'nstab-help'      => 'Hjálp',
'nstab-category'  => 'Efnisflokkur',

# General errors
'error'                => 'Villa',
'databaseerror'        => 'Gagnagrunnsvilla',
'cachederror'          => 'Eftirfarandi er afrit af umbeðinni síðu og gæti því ekki verið nýjasta útgáfa hennar:',
'badtitle'             => 'Ógildur titill',
'badtitletext'         => 'Umbeðin síðutitill er ógildur.',
'perfdisabled'         => 'Þessi síða hefur verið gerð óvirk þar sem notkun hennar veldur of miklu álagi á gagnagrunninum.',
'perfcached'           => 'Eftirfarandi er afrit af umbeðinni síðu og gæti því ekki verið nýjasta útgáfa hennar:',
'wrong_wfQuery_params' => 'Incorrect parameters to wfQuery()<br />
Function: $1<br />
Query: $2',
'viewsource'           => 'Skoða wikikóða',

# Login and logout pages
'logouttext'                 => 'Þú hefur verið skráð(ur) út.
Þú getur þó haldið áfram að nota {{SITENAME}} nafnlaust og þú getur skráð þig inn sem annar notandi. Athugaðu að sumar síður kunna að birtast líkt og þú sért ennþá innskráður, hægt er að koma í veg fyrir það með því að hreinsa biðminnið í vafranum.',
'welcomecreation'            => '<h2>Velkomin(n), $1!</h2><p>Aðgangurinn þinn hefur verið búinn til.
Ekki gleyma að breyta {{SITENAME}} stillingunum þínum.',
'yourname'                   => 'Notendanafn',
'yourpassword'               => 'Lykilorð',
'yourpasswordagain'          => 'Lykilorð (aftur)',
'remembermypassword'         => 'Muna.',
'alreadyloggedin'            => '<strong>Notandinn $1 er þegar innskráður!</strong><br />',
'login'                      => 'Innskrá',
'userlogin'                  => 'Innskrá',
'logout'                     => 'Útskráning',
'userlogout'                 => 'Útskrá',
'createaccount'              => 'Nýskrá',
'createaccountmail'          => 'með netfangi',
'youremail'                  => 'Tölvupóstfangið þitt*',
'yourrealname'               => 'Fullt nafn þitt*',
'yourlanguage'               => 'Viðmótstungumál',
'yournick'                   => 'Nafn (fyrir undirskriftir)',
'loginerror'                 => 'Innskráningarvilla',
'noname'                     => 'Ógilt notendanafn.',
'loginsuccesstitle'          => 'Innskráning tókst',
'loginsuccess'               => 'Þú ert nú innskráð(ur) á {{SITENAME}} sem „$1“.',
'nosuchuser'                 => 'Enginn notandi er til undir nafninu „$1“.',
'wrongpassword'              => 'Uppgefið lykilorð er rangt. Vinsamlegast reyndu aftur.',
'mailmypassword'             => 'Senda nýtt lykilorð með tölvupósti',
'acct_creation_throttle_hit' => 'Fyrirgefðu, þú hefur nú þegar búið til $1 aðgang(a). Þú getur ekki búið til fleiri.',

# Edit page toolbar
'bold_sample'     => 'Feitletraður texti',
'bold_tip'        => 'Feitletraður texti',
'italic_sample'   => 'Skáletraður texti',
'italic_tip'      => 'Skáletraður texti',
'link_sample'     => 'Titill tengils',
'link_tip'        => 'Innri tengill',
'extlink_sample'  => 'http://www.example.com titill tengils',
'extlink_tip'     => 'Ytri tengill (muna að setja http:// á undan)',
'headline_sample' => 'Fyrirsagnartexti',
'headline_tip'    => 'Annars stigs fyrirsögn',
'math_sample'     => 'Formúlan setjist hér',
'math_tip'        => 'LaTeX Stærðfræðiformúla',
'nowiki_sample'   => 'Innsetjið ósniðinn texta hér',
'nowiki_tip'      => 'Hunsa wikisnið',
'image_sample'    => 'Sýnishorn.jpeg',
'image_tip'       => 'Setja inn mynd',
'media_sample'    => 'Sýnishorn.ogg',
'media_tip'       => 'Tengill í margmiðlunarskrá',
'sig_tip'         => 'Undirskrift þín auk tímasetningu',
'hr_tip'          => 'Lárétt lína (notist sparlega)',

# Edit pages
'summary'                => 'Breytingar',
'subject'                => 'Fyrirsögn',
'minoredit'              => 'Minniháttar breyting',
'watchthis'              => 'Vakta',
'savearticle'            => 'Vista',
'preview'                => 'Forskoða',
'showpreview'            => 'Forskoða',
'whitelistedittitle'     => 'Þú verður að skrá þig inn til að geta breytt síðum.',
'whitelistedittext'      => 'Þú verður að [[Special:Userlogin|skrá þig inn]] til að geta breytt síðum.',
'whitelistreadtitle'     => 'Notandi verður að skrá sig inn til að geta lesið.',
'whitelistreadtext'      => 'Þú verður að [[Special:Userlogin|skrá þig inn]] til að lesa síður.',
'whitelistacctitle'      => 'Þér er óheimilt að skrá þig sem notanda.',
'whitelistacctext'       => 'Til að geta búið til aðganga í þessu Wiki, verður þú að [[Special:Userlogin|innskrá]] og hafa viðkomandi réttindi.',
'accmailtitle'           => 'Lykilorð sent.',
'accmailtext'            => 'Lykilorðið fyrir „$1“ hefur verið sent á $2.',
'newarticle'             => '(Ný)',
'anontalkpagetext'       => "----Þetta er spjallsíða fyrir óskráðan notanda sem hefur ekki búið til aðgang enn þá eða notar hann ekki, slíkir notendur þekkjast á [[IP tala|IP tölu]] sinni. Það getur gerst að margir notendur deili sömu IP tölu þannig að athugasemdum sem beint er til eins notanda geta birst á spjallsíðu annars. [[Special:Userlogin|Skráðu þig sem notanda]] til að koma í veg fyrir svona misskilning.''",
'noarticletext'          => "'''Það er ekki enn grein undir þessu nafni á {{SITENAME}}.'''
* '''[{{fullurl:{{NAMESPACE}}:{{PAGENAME}}|action=edit}} Skrifa grein undir nafninu „{{PAGENAME}}“]'''
* [[{{ns:special}}:Search/{{PAGENAME}}|Leita að „{{PAGENAME}}“]] í öðrum greinum og síðum.",
'clearyourcache'         => "'''Ath:''' Eftir að þú hefur vistað breytingar þarf að hreynsa flýtiskrár vafrarans til að sjá þær, í '''Mozilla / Firefox''' ''CTRL-Shift-R'', '''IE:''' ''CTRL-F5'', '''Safari:''' ''CMD-Shift-R'', '''Konqueror:''' ''F5''.",
'usercssjsyoucanpreview' => '<strong>Ath:</strong> Hægt er að nota „Forskoða“ hnappinn til að prófa CSS og JavaScript kóða áður en hann er vistaður.',
'note'                   => '<strong>Athugið:</strong>',
'previewnote'            => ' Það sem sést hér er aðeins forskoðun og hefur ekki enn verið vistað.',
'editing'                => 'Breyti $1',
'editinguser'            => 'Breyti $1',
'editingsection'         => 'Breyti $1 (hluta)',
'editingcomment'         => 'Breyti $1 (bæti við athugasemd)',
'explainconflict'        => 'Síðunni hefur verið breytt síðan þú byrjaðir að gera breytingar á henni, textinn í efri reitnum inniheldur núverandi útgáfu úr gagnagrunni og sá neðri inniheldur þína útgáfu, þú þarft hér að færa breytingar sem þú vilt halda úr neðri reitnum í þann efri og vista síðuna. <strong>Aðeins</strong> texti úr efri reitnum mun vera vistaður þegar þú vistar.',
'yourtext'               => 'Þinn texti',
'storedversion'          => 'Geymd útgáfa',
'editingold'             => '<strong>ATH: Þú ert að breyta gamalli útgáfu þessarar síðu og munu allar breytingar sem gerðar hafa verið á henni frá þeirri útgáfu vera fjarlægðar ef þú vistar.</strong>',
'yourdiff'               => 'Mismunur',
'protectedpagewarning'   => '<!-- -->',
'templatesused'          => 'Snið notuð á síðunni:',

# History pages
'revhistory'          => 'Útgáfusaga',
'currentrev'          => 'Núverandi útgáfa',
'revisionasof'        => 'Útgáfa síðunnar kl. $1',
'previousrevision'    => '←Fyrri útgáfa',
'nextrevision'        => 'Næsta útgáfa→',
'currentrevisionlink' => 'núverandi útgáfa',
'cur'                 => 'nú',
'last'                => 'breyting',
'histlegend'          => 'Skýringar: (nú) = bera saman við núverandi útgáfu,
(breyting) = bera saman við útgáfun á undan, M = minniháttar breyting.',

# Diffs
'difference'              => '(Munur milli útgáfa)',
'lineno'                  => 'Lína $1:',
'compareselectedversions' => 'Bera saman valdar útgáfur',

# Search results
'searchresults'         => 'Leitarniðurstöður',
'searchresulttext'      => 'Fyrir frekari upplýsingar um leit á {{SITENAME}}, sjá $1.',
'searchsubtitle'        => 'Fyrir fyrirspurnina „[[:$1]]“',
'searchsubtitleinvalid' => 'Fyrir fyrirspurnina „$1“',
'badquery'              => 'Illa sniðin leitarfyrirspurn',
'titlematches'          => 'Titlar greina sem pössuðu við fyrirspurnina',
'notitlematches'        => 'Engir greinatitlar pössuðu við fyrirspurnina',
'textmatches'           => 'Leitarorð fannst/fundust í innihaldi eftirfarandi greina',
'notextmatches'         => 'Leitarorð fannst/fundust ekki í innihaldi greina',
'prevn'                 => 'síðustu $1',
'nextn'                 => 'næstu $1',
'viewprevnext'          => 'Skoða ($1) ($2) ($3).',
'showingresults'        => 'Sýni <strong>$1</strong> niðurstöður frá og með #<strong>$2</strong>.',
'showingresultsnum'     => 'Sýni <strong>$1</strong> niðurstöður frá og með #<strong>$2</strong>.',
'powersearch'           => 'Leita',
'powersearchtext'       => 'Leita í eftirfarandi nafnrýmum :<br />
$1<br />
$2 Sýna tilvísarnir &nbsp; Leita að $3 $9',
'blanknamespace'        => '(Aðalnafnrýmið)',

# Preferences page
'preferences'             => 'Stillingar',
'prefsnologintext'        => 'You must be [[Special:Userlogin|logged in]]
to set user preferences.',
'qbsettings'              => 'Valblað',
'qbsettings-none'         => 'Sleppa',
'qbsettings-fixedleft'    => 'Fast vinstra megin',
'qbsettings-fixedright'   => 'Fast hægra megin',
'qbsettings-floatingleft' => 'Fljótandi til vinstri',
'changepassword'          => 'Breyta lykilorði',
'skin'                    => 'Þema',
'math'                    => 'Birting stærðfræðiformúlna',
'dateformat'              => 'Tímasnið',
'datedefault'             => 'Sjálfgefið',
'prefs-personal'          => 'Notendaupplýsingar',
'prefs-rc'                => 'Nýlegar breytingar og stubbar',
'prefs-misc'              => 'Aðrar stillingar',
'saveprefs'               => 'Vista stillingar',
'resetprefs'              => 'Endurstilla valmöguleika',
'oldpassword'             => 'Gamla lykilorðið',
'newpassword'             => 'Nýja lykilorðið',
'retypenew'               => 'Endurtaktu nýja lykilorðið',
'textboxsize'             => 'Breytingarflipinn',
'rows'                    => 'Raðir',
'columns'                 => 'Dálkar',
'searchresultshead'       => 'Leit',
'resultsperpage'          => 'Niðurstöður á síðu',
'contextlines'            => 'Línur á hverja niðurstöðu',
'contextchars'            => 'Stafir í samhengi á hverja línu',
'recentchangescount'      => 'Fjöldi síðna á „nýlegum breytingum“',
'savedprefs'              => 'Stillingarnar þínar hafa verið vistaðar.',
'timezonelegend'          => 'Tímabelti',
'timezonetext'            => 'Hliðrun staðartíma frá UTC+0.',
'localtime'               => 'Staðartími',
'timezoneoffset'          => 'Hliðrun',
'servertime'              => 'Tími netþjóns',
'guesstimezone'           => 'Fylla inn',
'defaultns'               => 'Leita í þessum nafnrýmum:',

# Recent changes
'recentchanges' => 'Nýlegar breytingar',
'rcnote'        => 'Að neðan eru síðustu <strong>$1</strong> breytingar síðustu <strong>$2</strong> daga.',
'rclistfrom'    => 'Sýna breytingar frá og með $1',
'rclinks'       => 'Sýna síðustu $1 breytingar síðustu $2 daga<br />$3',
'diff'          => 'breyting',
'hist'          => 'breytingaskrá',
'hide'          => 'Fela',
'show'          => 'Sýna',

# Recent changes linked
'recentchangeslinked' => 'Skyldar breytingar',

# Upload
'upload'            => 'Innhlaða',
'uploadbtn'         => 'Hlaða inn skrá',
'reupload'          => 'Endurinnhlaða',
'reuploaddesc'      => 'Aftur á innhlaðningarformið.',
'uploadnologin'     => 'Óinnskráð(ur)',
'uploadnologintext' => 'You must be [[Special:Userlogin|logged in]]
to upload files.',
'uploaderror'       => 'Villa í innhlaðningu',
'uploadlog'         => 'innhlaðningarskrá',
'uploadlogpage'     => 'Innhlaðningarskrá',
'filename'          => 'Skráarnafn',
'filedesc'          => 'Lýsing',
'badfilename'       => 'Skáarnafninu hefur verið breytt í „$1“.',
'fileexists'        => 'Skrá með þessu nafni er þegar til, skoðaðu $1 ef þú ert óviss um hvort þú viljir breyta henni, ekki verður skrifað yfir gömlu skránna hlaðiru inn nýrri með sama nafni heldur verður núverandi útgáfa geymd í útgáfusögu.',
'successfulupload'  => 'Innhlaðning tókst',
'fileuploaded'      => 'Skránni „$1“ hefur verið bætt við á {{SITENAME}}.
Fylgdu þessum tengli: $2 á lýsingarsíðu skráarinnar og fylltu út
upplýsingar um skránna, svosem um uppruna hennar, höfund og aðrar
upplýsingar um hana.',
'uploadwarning'     => 'Aðvörun',
'savefile'          => 'Vista',
'uploadedimage'     => 'hlóð inn "$1"',

# Image list
'imagelist'           => 'Skráalisti',
'imagelisttext'       => 'Hér fyrir neðan er $1 skrám raðað $2.',
'ilsubmit'            => 'Leita',
'showlast'            => 'Sýna síðustu $1 skrár raðaðar $2.',
'byname'              => 'eftir nafni',
'bydate'              => 'eftir dagsetningu',
'bysize'              => 'eftir stærð',
'imgdelete'           => 'eyða',
'imgdesc'             => 'lýsing',
'imglegend'           => 'Skýringar: (lýsing) = sýna og/eða breyta lýsingu skráar.',
'imghistory'          => 'Breytingaskrá myndar',
'revertimg'           => 'nota',
'deleteimg'           => 'eyða',
'deleteimgcompletely' => 'Eyða öllum útgáfum',
'imghistlegend'       => 'Skýringar: (nú) = bera saman við núverandi útgáfu,
(breyting) = bera saman við útgáfun á undan, M = minniháttar breyting.

Legend: (nú) = núverandi útgáfa,
(eyða) = eyða þessari útgáfu, (nota) = nota þessa útgáfu í stað núverandi útgáfu.
<br /><em>Fylgdu dagsetningartenglunum til að sjá mynd sem hlaðið var inn á þeim tíma</em>.',
'imagelinks'          => 'Myndatenglar',
'linkstoimage'        => 'Eftirfarandi síður tengjast í mynd þessa:',
'nolinkstoimage'      => 'Engar síður tengja í hingað.',

# Statistics
'statistics'    => 'Tölfræði',
'sitestats'     => 'Almenn tölfræði',
'userstats'     => 'Notendatölfræði',
'sitestatstext' => "Nú eru alls '''$1''' síður í gagnagrunninum,
þar á meðal „spjall“ síður, síður er snúa að {{SITENAME}} verkefninu,
„stubbar“, tilvísanir og annað efni er ekki telst til greina.
Þar fyrir utan eru '''$2''' síður sem líklega teljast fullgildar greinar.

'''$3''' síður hafa verið skoðaðar og '''$4''' breytingar hafa verið gerðar
síðan vefurinn var settur up. Það reiknast sem '''$5''' breytingar
á hverja síðu að meðaltali, og '''$6''' fléttingar fyrir hverja breytingu.",
'userstatstext' => "Það eru '''$1''' skráðir notendur, þar af eru '''$2''' eða '''$4%''' stjórnendur (sjá $3).",

'disambiguations' => 'Aðgreiningarsíður',

# Miscellaneous special pages
'lonelypages'             => 'Munaðarlausar síður',
'uncategorizedpages'      => 'Óflokkaðar síður',
'uncategorizedcategories' => 'Óflokkaðir flokkar',
'unusedimages'            => 'Munaðarlausar skrár',
'wantedpages'             => 'Eftirsóttar síður',
'allpages'                => 'Allar síður',
'randompage'              => 'Handahófsvalin síða',
'shortpages'              => 'Stuttar síður',
'longpages'               => 'Langar síður',
'deadendpages'            => 'Botnlangar',
'listusers'               => 'Notendalisti',
'specialpages'            => 'Kerfissíður',
'spheading'               => 'Almennar',
'rclsub'                  => '(á síðum sem tengd er í frá „$1“)',
'newpages'                => 'Nýjar síður',
'ancientpages'            => 'Elstu síður',
'move'                    => 'Færa',
'movethispage'            => 'Færa þessa síðu',
'unusedimagestext'        => '<p>Please note that other web sites may link to an image with
a direct URL, and so may still be listed here despite being
in active use.',

# Book sources
'booksources' => 'Bókabúðir',

'alphaindexline' => '$1 til $2',
'version'        => 'Útgáfa',

# E-mail user
'emailuser'       => 'Senda þessum notanda tölvupóst',
'emailpage'       => 'Senda tölvupóst',
'emailpagetext'   => 'Hafi notandi þessi fyllt út gild tölvupóstfang í stillingum sínum er hægt að senda skilaboð til hans eða hennar hér. Póstfangið sem þú fylltir út í stillingum þínum mun byrtast í „From:“ hlutanum svo viðtakandinn geti svarað.',
'defemailsubject' => 'Varðandi {{SITENAME}}',
'noemailtitle'    => 'Ekkert póstfang',
'noemailtext'     => 'Notandi þessi hefur kosið að fá ekki tölvupóst frá öðrum notendum eða hefur ekki fyllt út netfang sitt í stillingum.',
'emailfrom'       => 'Frá',
'emailto'         => 'Til',
'emailsubject'    => 'Fyrirsögn',
'emailmessage'    => 'Skilaboð',
'emailsend'       => 'Senda',
'emailsent'       => 'Sending tókst',
'emailsenttext'   => 'Skilaboðin þín hafa verið send.',

# Watchlist
'watchlist'          => 'Vaktlistinn',
'mywatchlist'        => 'Vaktlistinn',
'nowatchlist'        => 'Vaktlistinn er tómur.',
'watchnologin'       => 'Óinnskráð(ur)',
'watchnologintext'   => 'Þú verður að vera [[Special:Userlogin|innskáð(ur)]] til að geta breytt vaktlistanum.',
'addedwatch'         => 'Bætt á vaktlistann',
'addedwatchtext'     => "Síðunni „$1“ hefur verið bætt á [[Special:Watchlist|Vaktlistann]] þinn.
Frekari breytingar á henni eða spallsíðu hennar munu verða sýndar þar.
Þar að auki verður síða þessi '''feitletruð''' á [[Special:Recentchanges|Nýlegum breytingum]]
svo auðveldara sé að sjá hana þar meðal fjöldans.

<p>Til að fjarlægja síðu þessa af vaktlistanum þarft þú að ýta á tengilinn er merktur er „afvakta“.",
'removedwatch'       => 'Fjarlægt af vaktlistanum',
'removedwatchtext'   => 'Síðan "$1" hefur verið fjarlægð af vaktlistanum.',
'watch'              => 'Vakta',
'watchthispage'      => 'Vakta þessa síðu',
'unwatch'            => 'Afvakta',
'watchnochange'      => 'Engri síðu á vaktlistanum þínum hefur verið breytt á tilgreindu tímabili.',
'watchdetails'       => 'Fyrir utan spjallsíður eru $1 síða/síður á vaktlistanum þínum. Hægt er að
[$4 sýna heildarlistann og breyta honum].',
'watchmethod-recent' => 'kanna hvort nýlegar breytingar innihalda vaktaðar síður',
'watchmethod-list'   => 'leita að breytingum í vöktuðum síðum',
'removechecked'      => 'Fjarlægja merktar síður af vaktlistanum',
'watchlistcontains'  => 'Á vaktlistanum eru $1 síður.',
'watcheditlist'      => "Þetta er listi yfir þínar vöktuðu síður raðað í
stafrófsröð. Merktu við þær síður sem þú vilt fjarlægja
af vaktlistanum og ýttu á 'fjarlægja merktar' takkan
neðst á skjánum.",
'removingchecked'    => 'Fjarlægi umbeðnar síðu(r) af vaktlistanum...',
'wlnote'             => 'Að neðan eru síðustu <b>$1</b> breytingar síðustu <b>$2</b> klukkutíma.',
'wlshowlast'         => 'Sýna síðustu $1 klukkutíma, $2 daga, $3',
'wlsaved'            => 'Þetta er vistuð útgáfa af vaktlistanum þínum.',

# Delete/protect/revert
'confirm'            => 'Staðfesta',
'excontent'          => "innihaldið var: '$1'",
'confirmdelete'      => 'Staðfesting á eyðingu',
'deletesub'          => '(Eyði: „$1“)',
'historywarning'     => 'Athugið: Síðan sem þú ert um það bil að eyða á sér&nbsp;',
'actioncomplete'     => 'Aðgerð lokið',
'deletedtext'        => '„$1“ hefur verið eytt. Sjá lista yfir nýlegar eyðingar í $2.',
'deletedarticle'     => 'tortímdi „$1“',
'dellogpage'         => 'Eyðingaskrá',
'deletionlog'        => 'eyðingaskrá',
'reverted'           => 'Breytt aftur til fyrri útgáfu',
'deletecomment'      => 'Ástæða',
'rollback'           => 'Taka aftur breytingar',
'rollback_short'     => 'Afturtaka',
'rollbacklink'       => 'afturtaka',
'rollbackfailed'     => 'Afturtaka mistókst',
'cantrollback'       => 'Ekki hægt að taka aftur breytingu, síðasti höfundur er eini höfundur þessarar síðu.',
'revertpage'         => 'Tók aftur breytingar $2, breytt til síðustu útgáfu $1',
'protectlogpage'     => 'Verndunarskrá',
'protectsub'         => '(Vernda „$1“)',
'confirmprotect'     => 'Verndunarstaðfesting',
'protectcomment'     => 'Ástæða',
'unprotectsub'       => '(Afvernda „$1“)',

# Undelete
'undelete'          => 'Endurvekja eydda síðu',
'undeletepage'      => 'Skoða og endurvekja síður',
'undeletepagetext'  => 'Eftirfarandi síðum hefur verið eitt en eru þó enn í gagnagrunninum og geta verið endurvaknar. Athugið að síður þessar eru reglulega fjarlægðar endanlega úr gagnagrunninum.',
'undeleterevisions' => '$1 breyting(ar)',
'undeletebtn'       => 'Endurvekja!',

# Contributions
'contributions' => 'Framlög notanda',
'mycontris'     => 'Framlög',
'contribsub2'   => 'Eftir $1 ($2)',

# What links here
'whatlinkshere' => 'Hvað tengist hingað',
'linklistsub'   => '(Listi yfir ítengdar síður)',
'linkshere'     => 'Eftirfarandi síður tengjast hingað:',
'nolinkshere'   => 'Engar síður tengjast hingað.',
'isredirect'    => 'tilvísun',

# Block/unblock
'blockip'            => 'Banna notanda',
'blockiptext'        => 'Hægt er að hindra einstaka notendur eða IP tölur í að gera breytingar á {{SITENAME}}

Útrennslutímar eru í stöðluðu GNU sniði sem farið er yfir í [http://www.gnu.org/software/tar/manual/html_chapter/tar_7.html tar handbókinni], Til dæmis „1 hour“, „2 days“, „next Wednesday“, „1 January 2017“ eða „indefinite“ og „infinite“ til að banna að eylífu, þetta ætti þó aðeins að vera notað á ódauðlegar verur þar sem um 150 ár ættu að duga jafnvel á þrjóskasta fólk.

Sjá [[meta:Range blocks|Range blocks]] á meta fyrir yfirlit yfir [[CIDR]] tölur, [[{{ns:Special}}:Ipblocklist|bannaða notendur og IP tölur]] fyrir lista yfir þá sem nú eru bannaðir og [[{{ns:4}}:Bönnunarskrá|bönnunarskrá]] fyrir lista sem inniheldur einnig þá sem hafa verið bannaðir í fortíðinni.',
'ipaddress'          => 'IP Tala/notendanafn',
'ipbexpiry'          => 'Rennur út eftir',
'ipbreason'          => 'Ástæða',
'ipbsubmit'          => 'Banna notanda',
'blockipsuccesstext' => '„$1“ hefur verið bannaður.<br />
Sjá [[Special:Ipblocklist|bannaðar notendur og IP tölur]] fyrir yfirlit yfir núverandi bönn.',
'unblockip'          => 'Afbanna notanda',
'unblockiptext'      => 'Endurvekja skrifréttindi bannaðra notenda eða IP talna.',
'ipusubmit'          => 'Afbanna',
'ipblocklist'        => 'Bannaðar notendur og IP tölur',
'blocklistline'      => '$1, $2 bannaði $3 ($4)',
'infiniteblock'      => 'rennur út infinite',
'expiringblock'      => 'rennur út  $1',
'blocklink'          => 'banna',
'unblocklink'        => 'afbanna',
'contribslink'       => 'framlög',
'blocklogpage'       => 'Bönnunarskrá',
'blocklogtext'       => 'This is a log of user blocking and unblocking actions. Automatically
blocked IP addresses are not be listed. See the [[Special:Ipblocklist|IP block list]] for
the list of currently operational bans and blocks.',

# Developer tools
'lockdb'   => 'Læsa gagnagrunninum',
'unlockdb' => 'Aflæsa gagnagrunninum',

# Move page
'movepage'         => 'Færa síðu',
'movepagetext'     => "Hér er hægt að endurnefna síðu, hún mun ásamt breytingarskrá hennar
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
'movepagetalktext' => 'Spallsíða síðunnar verður sjálfkrafa færð með ef hún er til nema:
* Þú sért að færa síðuna á milli nafnrýma
* Spallsíða sé þegar til undir nýja nafninu
* Þú veljir að færa hana ekki
Í þeim tilfellum verður að færa hana handvirkt.',
'movearticle'      => 'Færa',
'movenologin'      => 'Óinnskráð(ur)',
'movenologintext'  => 'Þú verður að vera [[Kerfissíða:Userlogin|innskráð(ur)]] til  að geta fært síður.',
'newtitle'         => 'Yfir á',
'movepagebtn'      => 'Færa síðuna',
'pagemovedsub'     => 'Færsla tókst',
'pagemovedtext'    => 'Síðan „[[$1]]“ var færð yfir á „[[$2]]“.',
'articleexists'    => 'Annaðhvort er þegar til síða undir þessum titli,
eða sá titill sem þú hefur valið er ekki gildur.
Vinsamlegast veldu annan titil.',
'movetalk'         => 'Færa „Spjall“ síðuna líka ef við á.',
'talkpagenotmoved' => 'Samsvarandi spjallsíða var <strong>ekki</strong> færð.',
'1movedto2'        => '$1 færð á $2',
'1movedto2_redir'  => '$1 færð á $2 yfir tilvísun',

# Export
'export'        => 'XML útgáfa síðu',
'exportcuronly' => 'Aðeins núverandi útgáfu án breytingarskrá',

# Namespace 8 related
'allmessages'        => 'Kerfismeldingar',
'allmessagesname'    => 'Titill',
'allmessagesdefault' => 'Sjálfgefinn texti',
'allmessagescurrent' => 'Núverandi texti',
'allmessagestext'    => 'Listi yfir meldingar í „{{ns:8}}“ nafnarýminu.',

# Thumbnails
'missingimage' => '<b>Mynd vantar</b><br /><i>$1</i>',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Notendasíðan mín',
'tooltip-pt-anonuserpage'         => 'Notendasíðan fyrir IP töluna þína',
'tooltip-pt-mytalk'               => 'Spallsíðan mín',
'tooltip-pt-anontalk'             => 'Spjallsíðan fyrir þessa IP tölu',
'tooltip-pt-preferences'          => 'Almennar stillingar',
'tooltip-pt-watchlist'            => 'Vaktlistinn.',
'tooltip-pt-mycontris'            => 'Listi yfir framlög þín',
'tooltip-pt-login'                => 'Þú ert hvattur/hvött til að innskrá þig, það er hinsvegar ekki nauðsynlegt.',
'tooltip-pt-anonlogin'            => 'Þú ert hvattur/hvött til að innskrá þig, það er hinsvegar ekki nauðsynlegt.',
'tooltip-pt-logout'               => 'Útskráning',
'tooltip-ca-talk'                 => 'Spallsíða þessarar síðu',
'tooltip-ca-edit'                 => 'Þú getur breytt síðu þessari, vinsamlegast notaðu „forskoða“ hnappinn áður en þú vistar',
'tooltip-ca-addsection'           => 'Viðbótarumræða.',
'tooltip-ca-viewsource'           => 'Síða þessi er vernduð, þú getur þó skoðað frumkóða hennar.',
'tooltip-ca-history'              => 'Eldri útgáfur af síðunni.',
'tooltip-ca-protect'              => 'Vernda þessa síðu',
'tooltip-ca-delete'               => 'Eyða þessari síðu',
'tooltip-ca-undelete'             => 'Endurvekja breytingar á síðu þessari fyrir en henni var tortímt',
'tooltip-ca-move'                 => 'Færa þessa síðu',
'tooltip-ca-watch'                => 'Bæta þessari síðu við á vaktlistann',
'tooltip-ca-unwatch'              => 'Fjarlægja þessa síðu af vaktlistanum',
'tooltip-search'                  => 'Leit á þessari Wiki',
'tooltip-p-logo'                  => 'Forsíða',
'tooltip-n-mainpage'              => 'Forsíða {{SITENAME}}',
'tooltip-n-portal'                => 'Um verkefnið, hvernig er hægt að hjálpa og hvar á að byrja',
'tooltip-n-currentevents'         => 'Líðandi stund',
'tooltip-n-recentchanges'         => 'Listi yfir nýlegar breytingar.',
'tooltip-n-randompage'            => 'Handahófsvalin síða',
'tooltip-n-help'                  => 'Efnisyfirlit yfir hjálparsíður.',
'tooltip-n-sitesupport'           => 'Fjárframlagssíða',
'tooltip-t-whatlinkshere'         => 'Listi yfir síður sem tengjast í þessa',
'tooltip-t-recentchangeslinked'   => 'Nýlegar breitingar á ítengdum síðum',
'tooltip-feed-rss'                => 'RSS fyrir þessa síðu',
'tooltip-feed-atom'               => 'Atom fyrir þessa síðu',
'tooltip-t-contributions'         => 'Sýna framlagslista þessa notanda',
'tooltip-t-emailuser'             => 'Senda notanda þessum póst',
'tooltip-t-upload'                => 'Innhlaða myndum eða margmiðlunarskrám',
'tooltip-t-specialpages'          => 'Listi yfir kerfissíður',
'tooltip-ca-nstab-main'           => 'Sýna síðuna',
'tooltip-ca-nstab-user'           => 'Sýna notendasíðuna',
'tooltip-ca-nstab-media'          => 'Sýna margmiðlunarsíðuna',
'tooltip-ca-nstab-special'        => 'Þetta er kerfissíða, þér er óhæft að breyta henni.',
'tooltip-ca-nstab-project'        => 'Sýna verkefnasíðuna',
'tooltip-ca-nstab-image'          => 'Sýna myndasíðuna',
'tooltip-ca-nstab-mediawiki'      => 'Sýna kerfisskilaboðin',
'tooltip-ca-nstab-template'       => 'View the template',
'tooltip-ca-nstab-help'           => 'Sýna hjálparsíðuna',
'tooltip-ca-nstab-category'       => 'Sýna efnisflokkasíðuna',
'tooltip-minoredit'               => 'Merktu þessa breytingu sem minniháttar',
'tooltip-save'                    => 'Vista breytingarnar',
'tooltip-preview'                 => 'Forskoða breytingarnar, vinsamlegast gerðu þetta áður en þú vistar!',
'tooltip-compareselectedversions' => 'Sjá breytingarnar á þessari grein á milli útgáfanna sem þú valdir.',

# Stylesheets
'monobook.css' => '
/* Stórir stafir í ýmsu */
#p-personal ul { text-transform: inherit; } /* notandanfn, spjall, stillingar */
.portlet h5 { text-transform: inherit;}     /* flakk, leit, verkfæri... */
#p-cactions li a {text-transform: inherit;} /* notandasíða, spjall... */',

# Scripts
'monobook.js' => '/* Deprecated; use [[MediaWiki:common.js]] */',

# Spam protection
'subcategorycount'       => 'Það eru $1 undirflokkar í þessum flokki.',
'categoryarticlecount'   => 'Það eru $1 síður í þessum flokki.',
'listingcontinuesabbrev' => ' frh.',

# Math options
'mw_math_png'    => 'Alltaf birta PNG mynd',
'mw_math_simple' => 'HTML fyrir einfaldar jöfnur annars PNG',
'mw_math_html'   => 'HTML ef hægt er, annars PNG',
'mw_math_source' => 'Sýna TeX jöfnu (fyrir textavafra)',
'mw_math_modern' => 'Mælt með fyrir nýja vafra',
'mw_math_mathml' => 'MathML',

# Browsing diffs
'previousdiff' => '← Fyrri breyting',
'nextdiff'     => 'Næsta breyting →',

'newimages' => 'Gallerí nýlegra skráa',

);

?>

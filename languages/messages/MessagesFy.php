<?php
/** Frisian (Frysk)
 *
 * @addtogroup Language
 *
 */

$quickbarSettings = array(
	'Ut', 'Lofts fêst', 'Rjochts fêst', 'Lofts sweevjend'
);

$skinNames = array(
	'standard' => 'Standert',
	'nostalgia' => 'Nostalgy',
);

$datePreferences = array(
	'default',
	'fy normal',
	'ISO 8601',
);

$defaultDateFormat = 'fy normal';

$dateFormats = array(
	'fy normal time' => 'H.i',
	'fy normal date' => 'j M Y',
	'fy normal both' => 'j M Y, H.i',
);

$datePreferenceMigrationMap = array(
	'default',
	'fy normal',
	'fy normal',
	'fy normal',
);

$namespaceNames = array(
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => 'Wiki',
	NS_MAIN           => '',
	NS_TALK           => 'Oerlis',
	NS_USER           => 'Meidogger',
	NS_USER_TALK      => 'Meidogger_oerlis',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => '$1_oerlis',
	NS_IMAGE          => 'Ofbyld',
	NS_IMAGE_TALK     => 'Ofbyld_oerlis',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'MediaWiki_oerlis',
	NS_TEMPLATE       => 'Berjocht',
	NS_TEMPLATE_TALK  => 'Berjocht_oerlis',
	NS_HELP           => 'Hulp',
	NS_HELP_TALK      => 'Hulp_oerlis',
	NS_CATEGORY       => 'Kategory',
	NS_CATEGORY_TALK  => 'Kategory_oerlis'
);

$namespaceAliases = array(
	'Brûker' => NS_USER,
	'Brûker_oerlis' => NS_USER_TALK,
);

$separatorTransformTable = array(',' => '.', '.' => ',' );
$linkTrail = '/^([a-zàáèéìíòóùúâêîôûäëïöü]+)(.*)$/sDu';


$messages = array(
# User Toggles

"tog-underline"		=> "Keppelings ûnderstreekje",
"tog-highlightbroken"	=> "Keppelings nei lege siden ta <a href=\"\" class=\"new\">read</a> (oars mei in fraachteken<a href=\"\" class=\"internal\">?</a>).",
"tog-justify"		=> "Paragrafen útfolje",
"tog-hideminor"		=> "Tekstwizigings wei litte út 'Koarts feroare'",
"tog-usenewrc"		=> "Utwreide ferzje fan 'Koarts feroare' brûke (net mei alle blêdzjers mooglik)",
"tog-numberheadings"	=> "Koppen fansels nûmerje",
"tog-showtoolbar" => "Show edit toolbar",
"tog-editondblclick"	=> "Dûbelklik jout bewurkingsside (freget JavaScript)",
"tog-editsection"	=> "Jou [bewurk]-keppelings foar seksjebewurking",
"tog-editsectiononrightclick" => "Rjochtsklik op sekjsetitels jout seksjebewurking (freget JavaScript)",
"tog-showtoc"		=> "Ynhâldsopjefte, foar siden mei mear as twa koppen",
"tog-rememberpassword" => "Oare kear fansels oanmelde",
"tog-editwidth"		=> "Bewurkingsfjild sa breed as de side",
"tog-watchdefault"	=> "Sides dy't jo feroare hawwe folgje",
"tog-minordefault"	=> "Feroarings yn it earst oanjaan as tekstwizigings.",
"tog-previewontop"	=> "By it neisjen, bewurkingsfjild ûnderoan sette",
"tog-nocache"		=> "Gjin oerslag brûke",
# Dates
'sunday' => 'snein',
'monday' => 'moandei',
'tuesday' => 'tiisdei',
'wednesday' => 'woansdei',
'thursday' => 'tongersdei',
'friday' => 'freed',
'saturday' => 'sneon',
'january' => 'jannewaris',
'february' => 'febrewaris',
'march' => 'maart',
'april' => 'april',
'may_long' => 'maaie',
'june' => 'juny',
'july' => 'july',
'august' => 'augustus',
'september' => 'septimber',
'october' => 'oktober',
'november' => 'novimber',
'december' => 'decimber',
'jan' => 'jan',
'feb' => 'feb',
'mar' => 'mar',
'apr' => 'apr',
'may' => 'mai',
'jun' => 'jun',
'jul' => 'jul',
'aug' => 'aug',
'sep' => 'sep',
'oct' => 'okt',
'nov' => 'nov',
'dec' => 'dec',


# Bits of text used by many pages:
#
"mainpage"		=> "Haadside",
"mainpagetext"	=> "Wiki-programma goed installearre.",
"about"		=> "Ynfo",
"aboutsite"      	=> "Oer de {{SITENAME}}",
"aboutpage"		=> "{{ns:project}}:Ynfo",
"help"		=> "Help",
"helppage"		=> "{{ns:project}}:Help",
"bugreports"	=> "Brekmelding",
"bugreportspage"	=> "{{ns:project}}:Brekmelding",
"faq"			=> "FAQ",
"faqpage"		=> "{{ns:project}}:FAQ",
"edithelp"		=> "Siden bewurkje",
"edithelppage"	=> "{{ns:project}}:Bewurk-rie",
"cancel"		=> "Ferlitte",
"qbfind"		=> "Sykje",
"qbbrowse"		=> "Blêdzje",
"qbedit"		=> "Bewurkje",
"qbpageoptions" 	=> "Side-opsjes",
"qbpageinfo"	=> "Side-ynfo",
"qbmyoptions"	=> "Myn Opsjes",
"mypage"		=> "Myn side",
"mytalk"		=> "Myn oerlis",
"currentevents" 	=> "Hjoeddeis",
"errorpagetitle" 	=> "Fout",
"returnto"		=> "Werom nei \"$1\".",
"whatlinkshere"	=> "Siden mei in keppeling hjirhinne",
"help"		=> "Help",
"search"		=> "Sykje",
"searchbutton"	=> "Sykje",
"go"			=> "Side",
'searcharticle'			=> "Side",
"history"		=> "Sideskiednis",
"printableversion" => "Ofdruk-ferzje",
"editthispage"	=> "Side bewurkje",
"deletethispage" 	=> "Side wiskje",
"protectthispage" => "Side beskermje",
"newpage" 		=> "Nije side",
"talkpage"		=> "Sideoerlis",
"postcomment"   	=> "Skrieuw in opmerking",
"articlepage"	=> "Side lêze",
"userpage" 		=> "Brûkerside",
"projectpage" 	=> "Metaside",
"imagepage" 	=> "Ofbyldside",
"viewtalkpage" 	=> "Oerlisside",
"otherlanguages" 	=> "Oare talen",
"redirectedfrom" 	=> "(Trochwiisd fan \"$1\")",
"lastmodifiedat"	=> "Lêste kear bewurke op $2, $1.",
"viewcount"		=> "Disse side is $1 kear iepenslein.",
"protectedpage" 	=> "Beskerme side",
"nbytes"		=> "$1 byte",
"ok"			=> "Goed",
"retrievedfrom" 	=> "Untfongen fan \"$1\"",
"editsection"	=> "edit",
"editold"	=> "edit",
"toc" 		=> "Ynhâld",
"showtoc" 		=> "sjen litte",
"hidetoc" 		=> "net sjen litte",
"thisisdeleted"	=> "\"$1\" lêze of werombringje?",
"restorelink" 	=> "$1 wiske ferzjes",

# Main script and global functions
#
"nosuchaction"	=> "Unbekende aksje.",
"nosuchactiontext" => "De aksje dy't jo oanjoegen fia de URL is net bekind by it Wiki-programma",
"nosuchspecialpage" => "Unbekende side",
"nospecialpagetext" => "Jo hawwe in Wiki-side opfrege dy't net bekind is by it Wiki-programma.",


# General errors
#
"error"			=> "Fout",
"databaseerror" 		=> "Databankfout",
"dberrortext"		=> "Sinboufout in databankfraach.
De lêst besochte databankfraach wie:
<blockquote><tt>$1</tt></blockquote>
fan funksje \"<tt>$2</tt>\" út.
MySQL joech fout \"<tt>$3: $4</tt>\" werom.",

"dberrortextcl" 		=> "Sinboufout in databankfraach.
De lêst besochte databankfraach wie:
\"$1\"
fan funksje \"$2\" út.
MySQL joech fout \"<tt>$3: $4</tt>\" werom.",

"noconnect"			=> "Sorry! Troch in fout yn de technyk, kin de Wiki gjin ferbining meitsje mei de databanktsjinner.",
"nodb"			=> "Kin databank \"$1\" net berikke.",
"cachederror"		=> "Dit is in ferzje út de oerslag, mar it kin wêze dat dy ferâldere is.",
"readonly"			=> "Databank is Net-skriuwe",
"enterlockreason" 	=> "Skriuw wêrom de databank net-skriuwe makke is,
en sawat hoenear't de men wêr skriuwe kin",
"readonlytext"	=> "De {{SITENAME}} databank is ôfsletten foar nije siden en oare wizigings,
nei alle gedachten is it foar ûnderhâld, en kinne jo der letter gewoan wer brûk fan meitsje.
De behearder hat dizze útlis joen:
<p>$1</p>",

# problem with link: [[{{ns:project}}:Brekmelding|behearder]]
"missingarticle" 		=> "De databank kin in side net fine, nammentlik: \"$1\".
<p>Faak is dit om't in âlde ferskil-, of skiednisside opfreege wurdt fan in side dy't wiske is.
<p>As dat it hjir net is, dan hawwe jo faaks in brek yn it programa fûn.
Jou dat asjebleaft troch oan de [[{{ns:project}}:Brekmelding|behearder]], tegearre mei de URL.",

"internalerror" 		=> "Ynwindige fout",
"filecopyerror" 		=> "Koe bestân \"$1\" net kopiearje as \"$2\".",
"filerenameerror" 	=> "Koe bestân \"$1\" net werneame as \"$2\".",
"filedeleteerror" 	=> "Koe bestân \"$1\" net wiskje.",
"filenotfound"		=> "Koe bestân \"$1\" net fine.",
"unexpected"		=> "Hommelse wearde: \"$1\"=\"$2\".",
"formerror"			=> "Fout: koe formulier net oerlizze",
"badarticleerror" 	=> "Dit kin op dizze side net dien wurden.",
"cannotdelete"		=> "Koe de oantsjutte side of ôfbyld net wiskje. (Faaks hat in oar dat al dien.)",
"badtitle"			=> "Misse titel",
"badtitletext"		=> "De opfreeche side titel wie ûnjildich, leech, of in
miskeppele ynter-taal of ynter-wiki titel.",
"perfdisabled" 		=> "Sorry! Dit ûnderdiel is tydlik út set om't it de databank sa starich makket
dat gjinien de wiki brûke kin.",
"perfdisabledsub" 	=> "Dit is in opsleine ferzje fan \"$1\":",


# Login and logout pages
#
"logouttitle" 	=> "Ofmelde",
"logouttext"	=> "Jo binne no ôfmeld.
Jo kinne de {{SITENAME}} fierders anonym brûke,
of jo op 'e nij [[{{ns:special}}:Userlogin|oanmelde]] ûnder in oare namme.",
"welcomecreation" => "<h2>Wolkom, $1!</h2><p>Jo ynstellings bin oanmakke.
Ferjit net se oan jo foarkar oan te passen.",

"loginpagetitle" 	=> "Oanmelde",
"yourname"  	=> "Jo brûkersnamme",
"yourpassword" => "Jo wachtwurd",
"yourpasswordagain" => "Jo wachtwurd (nochris)",
"remembermypassword" => "Oare kear fansels oanmelde.",
"loginproblem" 	=> "<b>Der wie wat mis mei jo oanmelden.</b><br />Besykje it nochris, a.j.w.",
"alreadyloggedin" => "<strong>Brûker $1, jo binne al oanmeld!</strong><br />",
"login"		=> "Oanmelde",
"userlogin"		=> "Oanmelde",
"logout"		=> "Ofmelde",
"userlogout"	=> "Ofmelde",
"notloggedin"	=> "Net oanmelde",
"createaccount"	=> "Nije ynstelingd oanmeitsje",
"badretype"		=> "De infierde wuchtwurden binne net lyk.",
"userexists"	=> "Dy brûkersname wurdt al brûkt. Besykje in oarenien.",
"youremail"		=> "Jo e-postadres (*).",
"yournick"		=> "Jo alias (foar sinjaturen)",

"loginerror"	=> "Oanmeldflater",
"noname"		=> "Jo moatte in brûkersnamme opjaan.",
"loginsuccesstitle" => "Oanmelden slagge.",
"loginsuccess"	=> "Jo binne no oanmelde op de {{SITENAME}} as: $1.",
"nosuchuser"	=> "Brûkersnamme en wachtwurd hearre net by elkoar.
Besykje op 'e nij, of fier it wachtwurd twa kear yn en meitsje neie brûkersynstellings.",

"wrongpassword"	=> "Brûkersnamme en wachtwurd hearre net by elkoar.
Besykje op 'e nij, of fier it wachtwurd twa kear yn en meitsje neie brûkersynstellings.",

"mailmypassword" 	=> "Stjoer my in nij wachtwurd.",
"passwordremindertitle" => "Nij wachtwurd foar de {{SITENAME}}",
"passwordremindertext" => "Immen (nei alle gedachten jo, fan Ynternet-adres $1)
hat frege en stjoer jo in nij {{SITENAME}} wachtwurd.
I wachtwurd foar brûker \"$2\" is no \"$3\".
Meld jo no oan, en feroarje jo wachtwurd.",
"noemail"		=> "Der is gjin e-postadres foar brûker \"$1\".",
"passwordsent"	=> "In nij wachtwurd is tastjoert oan it e-postadres foar \"$1\".
Please log in again after you receive it.",

# Edit pages
#
"summary"		=> "Gearfetting",
"subject"		=> "Mêd",
"minoredit"		=> "Dit is in tekstwiziging",
"watchthis"		=> "Folgje dizze side",
"savearticle"	=> "Fêstlizze",
"preview"		=> "Oerlêze",
"showpreview"	=> "Oerlêze foar de side fêstlein is",
"blockedtitle"	=> "Brûker is útsletten troch",
"blockedtext"	=> "Jo brûkersname of Ynternet-adres is útsletten.
As reden is opjûn:<br />''$2''<p>As jo wolle, kinne jo hjiroer kontakt op nimme meid de behearder.

(Om't in Ynternet-adressen faak mar foar ien sessie tawiisd wurde, kin it wêze
dat it eins gjit om in oar dy't deselde tagongferskaffer hat as jo hawwe. As it jo
net betreft, besykje dan earst of it noch sa is as jo in skofke gjin
Ynternet-ferbining hân hawwe. As it in probleem bliuwt, skriuw dan de behearder.
Sorry, foar it ûngemak.)

Jo Ynternet-adres is: $3. Nim dat op yn jo berjocht.

Tink derom, dat \"skriuw nei dizze brûker\" allinich wol as jo in
e-postadres opjûn hawwe in jo [[{{ns:special}}:Preferences|ynstellings]].",

"newarticle"	=> "(Nij)",
# problem with link: [[{{ns:project}}:Bewurk-rie|Mear ynformaasje oer bewurkjen]]
"newarticletext" =>
"Jo hawwe in keppeling folge nei in side dêr't noch gjin tekst op stiet.
Om sels tekst te meistjsen kinne jo dy gewoan yntype in dit bewurkingsfjild
([[{{ns:project}}:Bewurk-rie|Mear ynformaasje oer bewurkjen]].)
Oars kinne jo tebek mei de tebek-knop fan jo blêdzjer.",

"anontalkpagetext" => "---- ''Dit is de oerlisside fan in unbekinde brûker; in brûker
dy't sich net oanmeld hat. Om't der gjin namme is wurd it Ynternet-adres brûkt om
oan te jaan wa. Mar faak is it sa dat sa'n adres net altid troch deselde brûkt wurdt.
As jo it idee hawwe dat jo as ûnbekinde brûker opmerkings foar in oar krije, dan kinne
jo jo [[{{ns:special}}:Userlogin|oanmelde]], dat jo allinnich opmerkings foar josels krije.''",
"noarticletext" => "(Der stjit noch gjin tekst op dizze side.)",
"updated"		=> "(Bewurke)",
"note"		=> "<strong>Opmerking:</strong>",
"previewnote"	=> "Tink der om dat dizze side noch net fêstlein is!",
"previewconflict" => "Dizze side belanget allinich it earste bewurkingsfjild oan.",
"editing"		=> "Bewurkje \"$1\"",
'editinguser'		=> "Bewurkje \"$1\"",
//"editing"		=> "Bewurkje \"$1\" (seksje)",
//"editing"		=> "Bewurkje \"$1\" (nije opmerking)",
"editconflict"	=> "Tagelyk bewurke: \"$1\"",
"explainconflict" => "In oar hat de side feroare sûnt jo begûn binne mei it bewurkjen.
It earste bewurkingsfjild is hoe't de tekst wilens wurde is.
Jo feroarings stean yn it twadde fjild.
Dy wurde allinnich tapasse safier as jo se yn it earste fjild ynpasse.
<b>Allinnich</b> de tekst út it earste fjild kin fêstlein wurde.<br />",
"yourtext"		=> "Jo tekst",
"storedversion" => "Fêstleine ferzje",
"editingold"	=> "<strong>Waarskôging: Jo binne dwaande mei in âldere ferzje fan dizze side.
Soenen jo dizze fêstlizze, dan is al wat sûnt dy tiid feroare is kwyt.</strong>",
"yourdiff"		=> "Feroarings",
/*"copyrightwarning" => "Alle bydragen ta de {{SITENAME}} wurde sjoen
as fallend ûnder de GNU Iepen Dokumentaasje Lisinsje
(sjoch fierders: \"$1\").
As jo net wolle dat jo skriuwen ûnferbidlik oanpast en frij ferspraat wurdt,
dan is it baas, en set it net op de {{SITENAME}}.<br />
Jo ferklare ek dat jo dit sels skreaun hawwe, of it oernaam hawwe út in
publyk eigendom of in oare iepen boarne.
<strong><big>Foeg gjin wurk ûnder auteursrjocht ta sûnder tastimming!</big></strong>",*/
"longpagewarning" => "<strong>Waarskôging: Dizze side is $1 kilobyte lang;
der binne blêdzjers dy problemen hawwe mei siden fan tsjin de 32kb. of langer.
Besykje de side yn lytsere stikken te brekken.</strong>",
"readonlywarning" => "<strong>Waarskôging: De databank is ôfsletten foar
ûnderhâld, dus jo kinne jo bewurkings no net fêstlizze.
It wie baas en nim de tekst foar letter oer yn in tekstbestân.</strong>",
# problem with link: [[Project:Beskerm-rie|rie oer beskerme siden]]
"protectedpagewarning" => "<strong>Waarskôging: Dizze side is beskerme, dat
gewoane brûkers dy net bewurkje kinne. Tink om de
[[Project:Beskerm-rie|rie oer beskerme siden]].</strong>",

# History pages
#
"revhistory"	=> "Sideskiednis",
"nohistory"		=> "Dit is de earste ferzje fan de side.",
"revnotfound"	=> "Ferzje net fûn",
"revnotfoundtext" => "De âlde ferzje fan dizze side dêr't jo om frege hawwe, is der net.
Gean nei of de keppeling dy jo brûkt hawwe wol goed is.",
"loadhist"		=> "Sideskiednis ...",
"currentrev"	=> "Dizze ferzje",
"revisionasof"	=> "Ferzje op $1",
"cur"			=> "no",
"next"		=> "dan",
"last"		=> "doe",
"orig"		=> "ea",
"histlegend"	=> "Utlis: (no) = ferskil mei de side sa't dy no is,
(doe) = ferskill mei de side sa't er doe wie, foar de feroaring, T = Tekstwiziging",


# Diffs
#
"difference"	=> "(Ferskil tusken ferzjes)",
"loadingrev"	=> "Ferskil tusken ferzjes ...",
"lineno"		=> "Rigel $1:",
"editcurrent"	=> "Bewurk de hjoeddeistiche ferzje fan dizze side",

# Search results
#
"searchresults" => "Sykresultaat",
# problem with link: [[Project:Syk-rie|Ynformaasje oer it sykjen|Sykje troch de {{SITENAME}}]]
"searchresulttext" => "\"[[Project:Syk-rie|Ynformaasje oer it sykjen|Sykje troch de {{SITENAME}}]]\" troch de {{SITENAME}}.",
"searchsubtitle"	=> "Foar fraach \"[[:$1]]\"",
"searchsubtitleinvalid"	=> "Foar fraach \"$1\"",
"badquery"		=> "Misfoarme sykfraach",
"badquerytext"	=> "Jo fraach koe net ferwurke wurde.
Dit is faaks om't jo besyke hawwe en sykje in word fan ien of twa letters, wat it programma noch net kin.
Of it soe kinne dat jo de fraach misskreaun hawwe, lykas \"frysk en en frei\". Besykje it nochris.",
"matchtotals"	=> "Foar \"$1\" binne $2 titles fûn en $3 siden.",
"noexactmatch" => "Der is gjin side mei krekt dizze titel. Faaks is it better en Sykje nei dizze tekst.",
"titlematches"	=> "Titels",
"notitlematches" => "Gjin titels",
"textmatches"	=> "Siden",
"notextmatches"	=> "Gjin siden",
"prevn"		=> "foarige $1",
"nextn"		=> "folgende $1",
"viewprevnext"	=> "($1) ($2) ($3) besjen.",
"showingresults"	=> "<b>$1</b> resultaten fan <b>$2</b> ôf.",
"showingresultsnum" => "<b>$3</b> resultaten fan <b>$2</b> ôf.",
"nonefound"		=> "As der gjin resultaten binne, tink der dan om dat der <b>net</b> socht
wurde kin om wurden as \"it\" en \"in\", om't dy net byhâlden wurde, en dat as der mear
wurden syke wurde, allinnich siden fûn wurde wêr't <b>alle</b> worden op fûn wurde.",

"powersearch" => "Sykje",
"powersearchtext" => "
Sykje in nammeromten :<br />
$1<br />
$2 List trochferwizings &nbsp; Sykje nei \"$3\" \"$9\"",

"searchdisabled" => "<p>Op it stuit stjit it trochsykjen fan tekst net oan, om't de
tsjinner it net oankin. Mei't we nije apparatuer krije wurdt it nei alle gedanken wer
mooglik. Foar now kinne jo sykje fia Google:</p>",



# Preferences page
#
"preferences"		=> "Ynstellings",
"prefsnologin" 		=> "Net oanmeld",
"prefsnologintext"	=> "Jo moatte [[{{ns:special}}:Userlogin|oanmeld]] wêze om jo ynstellings te feroarjen.",

"prefsreset"		=> "De ynstellings binne tebek set sa't se fêstlein wienen.",
"qbsettings"		=> "Menu",
"changepassword" 		=> "Wachtword feroarje",
"skin"			=> "Side-oansjen",
"math"			=> "Formules",
"dateformat"		=> "Datum",
'datedefault' => 'Gjin foarkar',
"math_failure"		=> "Untsjutbere formule",
"math_unknown_error"	=> "Unbekinde fout",
"math_unknown_function"	=> "Unbekinde funksje",
"math_lexing_error"	=> "Unbekind wurd",
"math_syntax_error"	=> "Sinboufout",
"saveprefs"			=> "Ynstellings fêstlizze",
"resetprefs"		=> "Ynstellings tebek sette",
"oldpassword"		=> "Ald wachtwurd",
"newpassword"		=> "Nij wachtwurd",
"retypenew"			=> "Nij wachtwurd (nochris)",
"textboxsize"		=> "Tekstfjid-omjittings",
"rows"			=> "Rigen",
"columns"			=> "Kolommen",
"searchresultshead" 	=> "Sykje",
"resultsperpage" 		=> "Treffers de side",
"contextlines"		=> "Rigels inhâld de treffer",
"contextchars"		=> "Tekens fan de inhâld de rigel",
"stubthreshold" 		=> "Grins foar stobben",
"recentchangescount" 	=> "Nûmer of titels op 'Koarts feroare'",
"savedprefs"		=> "Jo ynstellings binne fêstlein.",
"timezonetext"		=> "Jou it tal fan oeren dat jo tiidsône ferskilt fan UTC (Greenwich).",
"localtime"			=> "Jo tiidsône",
"timezoneoffset" 		=> "Ferskil",
"servertime"		=> "UTC",
"guesstimezone" 		=> "Freegje de blêdzjer",
"defaultns"			=> "Nammeromten dy't normaal trochsykje wurde:",

# Recent changes
#
"changes" 			=> "feroarings",
"recentchanges" 		=> "Koarts feroare",
"recentchangestext" 	=> "De lêste feroarings fan de {{SITENAME}}.",
"rcnote"			=> "Dit binne de lêste <strong>$1</strong> feroarings yn de lêste <strong>$2</strong> dagen.",
"rcnotefrom"		=> "Dit binne de feroarings sûnt <b>$2</b> (maksimaal <b>$1</b>).",
"rclistfrom"		=> "Jou nije feroarings, begjinnende mei $1",
"rclinks"			=> "Jou $1 nije feroarings yn de lêste $2 dagen; $3 tekstwiziging",
"diff"			=> "ferskil",
"hist"			=> "skiednis",
"hide"			=> "gjin",
"show"			=> "al",
"minoreditletter" 	=> "T",
"newpageletter" 		=> "N",

# Upload
#
"upload"		=> "Bied bestân oan",
"uploadbtn"		=> "Bied bestân oan",
"reupload"		=> "Op 'e nij oanbiede",
"reuploaddesc"	=> "Werom nei oanbied-side.",
"uploadnologin" 	=> "Net oanmelde",
"uploadnologintext" => "Jo moatte [[{{ns:special}}:Userlogin|oanmeld]] wêze om in bestân oanbieden te kinnen.",

"uploaderror"	=> "Oanbied-fout",
# problem with link: [[Project:Ofbyld-rie|regels foar ôfbyldbrûk]]
"uploadtext"	=> "'''STOP!''' Lês ear't jo eat oanbiede
de [[Project:Ofbyld-rie|regels foar ôfbyldbrûk]] foar de {{SITENAME}}.

Earder oanbeane ôfbylden, kinne jo fine op de
[[Special:Imagelist|list of oanbeane ôfbylden]].
Wat oanbean en wat wiske wurdt, wurdt delskreaun yn it
[[Special:Log/delete|lochboek]].

Om't nije ôfbylden oan te bieden, kieze jo in bestân út sa't dat
normaal is foar jo blêdzjer en bestjoersysteem.
Dan jouwe jo oan jo gjin auteursrjocht skeine troch it oanbieden.
Mei \"Bied oan\" begjinne jo dan it oanbieden.
Dit kin efkes duorje as jo Ynternet-ferbining net sa flug is.

Foar de bestânsforam wurdt foto's JPEG oanret, foar tekenings ensfh. PNG, en foar
lûden OGG. Brûk in dúdlike bestânsnamme, sa't in oar ek wit wat it is.

Om it ôfbyld yn in side op te nimmen, meitsje jo dêr sa'n keppeling:<br />
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:jo_foto.jpg|omskriuwing]]</nowiki>''' of
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:jo_logo.png|omskriuwing]]</nowiki>''';
en foar lûden '''<nowiki>[[</nowiki>{ns:media}}<nowiki>:jo_lûd.ogg]]</nowiki>'''.

Tink derom dat oaren bewurkje kinne wat jo oanbiede, as dat better is foar de {{SITENAME}},
krekt's sa't dat foar siden jildt, en dat jo útsletten wurde kinne as jo misbrûk
meitsje fan it systeem..",

"uploadlog"		=> "oanbied log",
"uploadlogpage" 	=> "Oanbied_log",
"uploadlogpagetext" => "Liste fan de lêst oanbeane bestannen.
(Tiid oanjûn as UTC).
<ul>
</ul>",

"filename"		=> "Bestânsnamme",
"filedesc"		=> "Omskriuwing",
"copyrightpage" 	=> "{{ns:project}}:Auteursrjocht",
"copyrightpagename" => "{{SITENAME}} auteursrjocht",
"uploadedfiles"	=> "Oanbeane bestannen",
"minlength"		=> "Ofbyldnammen moatte trije letters of mear wêze.",
"badfilename"	=> "De ôfbyldnamme is feroare nei \"$1\".",
"badfiletype"	=> "\".$1\" is net yn in oanrette bestânsfoarm.",
"largefile"		=> "It is baas as ôfbylden net grutter as 100k binne.",
"successfulupload" => "Oanbieden slagge.",
"fileuploaded"	=> "Bestân \"$1\" goed oanbean.
Gean no fierder nei de beskriuwingsside: ($2). Dêr kinne jo oanjaan
wêr't it bestân wei kaam, hoenear it oanmakke is en wa't it makke hat,
en wat jo fierder mar oan ynformaasje hawwe.",

"uploadwarning" 	=> "Oanbied waarskôging",
"savefile"		=> "Lis bestân fêst",
"uploadedimage" 	=> " \"[[$1]]\" oanbean",
"uploaddisabled" => "Sorry, op dizze tsjinner kin net oanbean wurde.",

# Image list
#
"imagelist"		=> "Ofbyld list",
"imagelisttext"	=> "Dit is in list fan $1 ôfbylden, op $2.",
"getimagelist"	=> "Ofbyld list ...",
"ilsubmit"		=> "Sykje",
"showlast"		=> "Jou lêste $1 ôfbylden, op $2.",
"byname"		=> "namme",
"bydate"		=> "datum",
"bysize"		=> "grutte",
"imgdelete"		=> "wisk",
"imgdesc"		=> "tekst",
"imglegend"		=> "Utlis: (tekst) = Jou/bewurk ôfbyld-omskriuwing.",
"imghistory"	=> "Ofbyldskiednis",
"revertimg"		=> "tebek",
"deleteimg"		=> "wisk",
"deleteimgcompletely"		=> "wisk",
"imghistlegend"	=> "Utlis: (no) = dit is it hjoeddeiske ôfbyld,
(wisk) = wiskje dizze âldere ferzje, (tebek) = set ôfbyld tebek nei dizze âldere ferzje.
<br /><i>Fia de datum kinne jo it ôfbyld dat doe oanbean besjen</i>.",

"imagelinks"	=> "Ofbyldkeppelings",
"linkstoimage"	=> "Dizze siden binne keppele oan it ôfbyld:",
"nolinkstoimage" => "Der binne gjin siden oan dit ôfbyld keppelje.",

# Statistics
#
"statistics"	=> "Statistyk",
"sitestats"		=> "Side statistyk",
"userstats"		=> "Brûker statistyk",
"sitestatstext" => "It tal fan siden in de {{SITENAME}} is: <b>$2</b>.<br />
(Oerlissiden, siden oer de {{SITENAME}}, oare bysûndere siden,  stobben en
trochferwizings yn de databank binne dêrby net meiteld.)<br />
It tal fan siden in de databank is: <b>$1</b>.
<p>
Der is <b>$3</b> kear in side opfrege, en <b>$4</b> kear in side bewurke,
sûnt it programma bywurke is (15 oktober 2002).
Dat komt yn trochslach del op <b>$5</b> kear bewurke de side,
en <b>$6</b> kear opfrege de bewurking.",

"userstatstext" => "It tal fan registreare brûkers is <b>$1</b>.
It tal fan behearders dêrfan is: <b>$2</b>.",

# Maintenance Page
#
"disambiguations"		=> "Trochverwizings",
"disambiguationspage"	=> "{{ns:project}}:trochferwizing",
# problem with link: [[{{ns:project}}:trochferwizing]]
"disambiguations-text"	=> "Dizze siden binne keppele fia in
[[{{ns:project}}:trochferwizing]].
Se soenen mei de side sels keppele wurde moatte.<br />
(Allinnich siden út deselde nammeromte binne oanjûn.)",

"doubleredirects"	=> "Dûbele trochverwizings",
"doubleredirectstext"	=> "<b>Let op!</b> Der kinne missen yn dizze list stean!
Dat komt dan ornaris troch oare keppelings ûnder de \"#REDIRECT\".<br />
Eltse rigel jout keppelings nei de earste en twadde trochverwizing, en dan de earste regel fan
de twadde trochferwizing, wat it \"echte\" doel wêze moat.",

"brokenredirects"		=> "Misse trochferwizings",
"brokenredirectstext"	=> "Dizze trochferwizings ferwize nei siden dy't der net binne.",


# Miscellaneous special pages
#
"lonelypages"	=> "Lossteande siden",
"unusedimages"	=> "Lossteande ôbylden",
"popularpages"	=> "Grage siden",
"nviews"		=> "$1 kear sjoen",
"wantedpages"	=> "Nedige siden",
"nlinks"		=> "$1 keer keppele",
"allpages"		=> "Alle titels",
"randompage"	=> "Samar in side",
"shortpages"	=> "Koarte siden",
"longpages"		=> "Lange siden",
"listusers"		=> "Brûkerlist",
"specialpages"	=> "Bysûndere siden",
"spheading"		=> "Bysûndere siden foar all brûkers",
"recentchangeslinked" => "Folgje keppelings",
"rclsub"		=> "(nei siden dêr't \"$1\" keppelings nei hat)",
"newpages"		=> "Nije siden",
"ancientpages"	=> "Alde siden",
"movethispage"	=> "Move this side",
"unusedimagestext" => "<p>Tink derom dat ore web sides lykas fan de oare
parten fan it meartaliche projekt mei in keppeling nei in direkte URL nei
an ôfbyld makke hawwe kinne. Dan wurde se noch brûke, mar stean al in dizze list.",

"alphaindexline" 	=> "$1 oan't $2",


# Email this brûker
#
"mailnologin"	=> "Gjin adres beskikber",
"mailnologintext" => "Jo moatte [[{{ns:special}}:Userlogin|oanmeld]]
wêze, en in jildich e-postadres [[{{ns:special}}:Preferences|ynsteld]]
hawwe, om oan oare brûkers e-post stjoere te kinnen.",

"emailuser"		=> "Skriuw dizze brûker",
"emailpage"		=> "E-post nei brûker",
"emailpagetext"	=> "As dizze brûker in jildich e-postadres in ynsteld hat,
dan kinne jo ien berjocht ferstjoere.
It e-postadres dat jo ynsteld hawwe wurdt brûkt as de ôfstjoerder, sa't de ûntfanger
antwurdzje kin.",
"noemailtitle"	=> "Gjin e-postadres",
"noemailtext"	=> "Dizze brûker had gjin jildich e-postadres ynsteld,
of hat oanjaan gjin post fan oare brûkers krije te wollen.",
"emailfrom"		=> "Fan",
"emailto"		=> "Oan",
"emailsubject"	=> "Oer",
"emailmessage"	=> "Tekst",
"emailsend"		=> "Stjoer",
"emailsent"		=> "Berjocht stjoerd",
"emailsenttext" => "Jo berjocht is stjoerd.",

# Watchlist
#
"watchlist"		=> "Folchlist",
"nowatchlist"	=> "Jo hawwe gjin siden op jo folchlist.",
"watchnologin"	=> "Not oanmeld in",
"watchnologintext"=> "Jo moatte [[{{ns:special}}:Userlogin|oanmeld]] wêze om jo folchlist te feroarjen.",

"addedwatch"	=> "Oan folchlist tafoege",
"addedwatchtext"	=> "De side \"$1\" is tafoege oan jo <a href=\""
. "{{localurle:{{ns:special}}:Watchlist}}\">folchlist</a>.
As dizze side sels, of de oerlisside, feroare wurd, dan komt dat dêr yn,
en de side stiet dan ek <b>fet</b> yn de <a href=\"" .
  "{{localurle:{{ns:special}}:Recentchanges}}\">Koarts feroare</a> list.

<p>As jo letter in side net mear folgje wolle, dan brûke jo \"Ferjit dizze side\".",
"removedwatch"	=> "Net mear folgje",
"removedwatchtext" => "De side \"$1\" stiet net mear op jo folchlist.",
"watchthispage"	=> "Folgje dizze side",
"unwatchthispage" => "Ferjit dizze side",
"notanarticle"	=> "Dit kin net folge wurde.",
"watchnochange" 	=> "Fan de siden dy't jo folgje is der yn dizze perioade net ien feroare.",
"watchdetails"	=> "Jo folchlist hat $1 siden (oerlissiden net meiteld).
In dizze perioade binne der $2 siden feroare.
$3. ([$4 Gâns myn folchlist].)",

"watchmethod-recent" => "Koarts feroare ...",
"watchmethod-list" => "Folge ...",
"removechecked"	=> "Ferjit dizze siden",
"watchlistcontains" => "Jo folgje op it stuit $1 siden.",
"watcheditlist"	=> "Dit binne de siden op jo folchlist, oardere op alfabet.
Jou oan hokfoar siden jo net mear folgje wolle, en befêstigje dat ûnderoan de side.",

"removingchecked" => "Wiskje siden fan jo folchlist ...",
"couldntremove" 	=> "Koe \"$1\" net ferjitte ...",
"iteminvalidname" => "Misse namme: \"$1\" ...",
"wlnote" 		=> "Dit binne de lêste <strong>$1</strong> feroarings yn de lêste <strong>$2</strong> oeren.",


# Delete/protect/revert
#
"deletepage"	=> "Wisk side",
"confirm"		=> "Befêstigje",
"excontent"		=> "inhâld wie: '$1'",
"exbeforeblank" 	=> "foar de tekst wiske wie, wie dat: '$1'",
"exblank"		=> "side wie leech",
"confirmdelete"	=> "Befestigje wiskjen",
"deletesub"		=> "(Wiskje \"$1\")",
"historywarning"	=> "Waarskôging: De side dy't jo wiskje wolle hat skiednis:",
# problem with link: [[{{ns:project}}:wisk-rie]]
"confirmdeletetext" => "Jo binne dwaande mei it foar altyd wiskjen fan in side
of ôfbyld, tegearre mei alle skiednis, út de databank.
Befêstigje dat jo dat wier dwaan wolle. Befêstigje dat dat is wat jo witte wat it gefolch
is en dat jo dit dogge neffens de [[{{ns:project}}:wisk-rie]].",

"actioncomplete"	=> "Dien",
"deletedtext"	=> "\"$1\" is wiske.
Sjoch \"$2\" foar in list fan wat resint wiske is.",
"deletedarticle"	=> "\"$1\" is wiske",
"dellogpage"	=> "Wisk_loch",
"dellogpagetext" => "Dit is wat der resint wiske is.
(Tiden oanjûn as UTC).
<ul>
</ul>",

"deletionlog"	=> "wisk loch",
"reverted"		=> "Tebekset nei eardere ferzje",
"deletecomment"	=> "Reden foar it wiskjen",
"imagereverted"	=> "Tebeksette nei eardere ferzje is slagge.",
"rollback"		=> "Feroarings tebeksette",
"rollbacklink"	=> "feroaring tebeksette",
"rollbackfailed"	=> "Feroaring tebeksette net slagge",
"cantrollback"	=> "Disse feroaringt kin net tebek set, om't der mar ien skriuwer is.",
"alreadyrolled"	=> "Kin de feroaring fan [[:$1]]
troch [[User:$2|$2]] ([[User talk:$2|Oerlis]]) net tebeksette;
inoar hat de feroaring tebekset, of oars wat oan de side feroare.

De lêste feroaring wie fan [[User:$3|$3]] ([[User talk:$3|Oerlis]]).",
#   only shown if there is an edit comment
"editcomment"	=> "De gearfetting wie: \"<i>$1</i>\".",
"revertpage"	=> "Tebek set ta de ferzje fan \"$1\"",

# Undelete
"undelete"		=> "Side werom set",
"undeletepage"	=> "Side besjen en werom sette",
"undeletepagetext" => "Dizze siden binne wiske, mar sitte noch yn it argyf en kinne weromset wurde.
(It argyf kin út en troch leechmeitsje wurde.)",
"undeletearticle" => "Set side werom",
"undeleterevisions" => "$1 ferzjes in it argyf",
"undeletehistory" => "Soenen jo dizze side weromsette, dan wurde alle ferzjes weromset as part
fan de skiednis. As der in nije side is mei dizze namme, dan wurd de hjoeddeise ferzje <b>net</b>
troch de lêste ferzje út dy weromsette skiednis ferfangen.",
"undeleterevision" => "Wiske side, sa't dy $1 wie.",
"undeletebtn" 	=> "Weromset!",
"undeletedarticle" => "\"$1\" weromset",

# Contributions
#
"contributions"	=> "Brûker bydragen",
"mycontris"		=> "Myn bydragen",
"contribsub"	=> "Foar \"$1\"",
"nocontribs"	=> "Der binne gjin feroarings fûn dyt't hjirmei oerienkomme.",
"ucnote"		=> "Dit binne dizze brûker's leste <b>$1</b> feroarings yn de lêste <b>$2</b> dagen.",
"uclinks"		=> "Besjoch de lêste $1 feroarings; besjoch de lêste $2 dagen.",
"uctop"		=> " (boppen)",

# What links here
#
"whatlinkshere"	=> "Wat is hjirmei keppele",
"notargettitle"	=> "Gjin side",
"notargettext"	=> "Jo hawwe net sein oer hokfoar side jo dit witte wolle.",
"linklistsub"	=> "(List fan keppelings)",
"linkshere"		=> "Dizze siden binne hjirmei keppele:",
"nolinkshere"	=> "Gjinien side is hjirmei keppele!",
"isredirect"	=> "trochverwizing",

# Block/unblock IP
#
"blockip"		=> "Slut brûker út",
# problem with link: [[{{ns:project}}:Utslut-rie|útslut-rie]]
"blockiptext"	=> "Brûk dizze fjilden om in brûker fan skriuwtagong út te sluten.
Dit soe allinnich omwillens fan fandalisme dwaan wurde moatte, sa't de
[[{{ns:project}}:Utslut-rie|útslut-rie]] it oanjout.
Meld de krekte reden! Begelyk, neam de siden dy't oantaaste waarden.",
"ipaddress"		=> "Brûkernamme of Ynternet-adres",
"ipbreason"		=> "Reden",
"ipbsubmit"		=> "Slut dizze brûker út",
"badipaddress"	=> "Dy brûker bestiet net",
"blockipsuccesssub" => "Utsluting slagge",
"blockipsuccesstext" => "Brûker \"$1\" is útsletten.<br />
(List fan [[{{ns:special}}:Ipblocklist|útslette brûkers]].)",
"unblockip"		=> "Lit brûker der wer yn",
"unblockiptext"	=> "Brûk dizze fjilden om in brûker wer skriuwtagong te jaan.",
"ipusubmit"		=> "Lit dizze brûker der wer yn",
"ipblocklist"	=> "List fan útsletten Ynternet-adressen en brûkersnammen",
"blocklistline"	=> '"$3", troch "$2" op $1 ($4)',
"blocklink"		=> "slut út",
"unblocklink"	=> "lit yn",
"contribslink"	=> "bydragen",
"autoblocker"	=> "Jo wienen útsletten om't jo Ynternet-adres oerienkomt mei dat fan \"$1\".
Foar it útslute fan dy brûker waard dizze reden joen: \"$2\".",

# Developer tools
#
"lockdb"		=> "Meitsje de database 'Net-skriuwe'",
"unlockdb"  	=> "Meitsje de databank skriuwber",
"lockdbtext"	=> "Salang as de databank 'Net-skriuwe' is,
is foar brûkers it feroarjen fan siden, ynstellings, folchlisten, ensfh. net mooglik.
Befêstigje dat dit is wat jo wolle, en dat jo de databank wer skriuwber meitsje as
jo ûnderhâld ree is.",
"unlockdbtext"	=> "As de databank skriuwber makke wurdt,
is foar brûkers it feroarjen fan siden, ynstelingen, folchlisten, ensfh, wer mooglik.
Befêstigje dat dit is wat jo wolle.",
"lockconfirm"	=> "Ja, ik wol wier de databank 'Net--skriuwe' meitsje.",
"unlockconfirm"	=> "Ja, ik wol wier de databank skriuwber meitsje.",
"lockbtn"		=> "Meitsje de database 'Net-skriuwe'",
"unlockbtn"		=> "Meitsje de databank skriuwber",
"locknoconfirm"	=> "Jo hawwe jo hanneling net befêstige.",
"lockdbsuccesssub" => "Databank is 'Net-skriuwe'",
"unlockdbsuccesssub" => "Database is skriuwber",
"lockdbsuccesstext" => "De {{SITENAME}} databank is 'Net-skriuwe' makke.
<br />Tink derom en meitsje de databank skriuwber as jo ûnderhâld ree is.",
"unlockdbsuccesstext" => "De {{SITENAME}} databank is skriuwber makke.",

# Move page
#
"movepage"		=> "Werneam side",
"movepagetext"	=> "Dit werneamt in side, mei alle sideskiednis.
De âlde titel wurdt in trochferwizing nei de nije.
Keppelings mei de âlde side wurde net feroare;
gean sels nei of't der dûbele of misse ferwizings binne.
It hinget fan jo ôf of't de siden noch keppelen binne sa't it mient wie.

De side wurdt '''net''' werneamt as der al in side mei dy namme is, útsein as it in side
sûnder skiednis is en de side leech is of in trochferwizing is. Sa kinne jo in side
daalks weromneame as jo in flater meitsje, mar jo kinne in oare side net oerskriuwe.",

"movepagetalktext" => "As der in oerlisside by heart, dan bliuwt dy oan de side keppele, '''útsein''':
*De nije sidenamme yn in oare nammeromte is,
*Der keppele oan de nije namme al in net-lege oerlisside is, of
*Jo dêr net foar kieze.

In dizze gefallen is it oan jo hoe't jo de oerlisside werneame of ynfoegje wolle.",

"movearticle"	=> "Werneam side",
"movenologin"	=> "Net oameld",
"movenologintext" => "Jo moatte [[{{ns:special}}:Userlogin|oanmeld]] wêze om in side wer te neamen.",

"newtitle"		=> "As nij titel",
"movepagebtn"	=> "Werneam side",
"pagemovedsub"	=> "Werneamen slagge",
"pagemovedtext"	=> "Side \"[[$1]]\" werneamd as \"[[$2]]\".",
"articleexists"	=> "Der is al in side mei dy namme,
of oars is de namme dy't jo oanjûn hawwe net tastean.
Besykje it op 'e nij.",

"talkexists"	=> "It werneamen op sich is slagge, mar de eardere oerlisside is
net mear keppele om't der foar de nije namme el al in oerlisside wie.
Gearfoegje de oerlissiden hânmjittig.",

"movedto"		=> "werenamd as",
"talkpagemoved"	=> "De oerlisside is al noch keppele.",
"talkpagenotmoved" => "De oerlisside is <strong>net</strong> mear keppele.",
# Math
'mw_math_png' => "Altiten as PNG ôfbyldzje",
'mw_math_simple' => "HTML foar ienfâldiche formules, oars PNG",
'mw_math_html' => "HTML as mooglik, oars PNG",
'mw_math_source' => "Lit de TeX ferzje stean (foar tekstblêdzjers)",
'mw_math_modern' => "Oanbefelle foar resinte blêdzjers",
'mw_math_mathml' => 'MathML',

);


?>

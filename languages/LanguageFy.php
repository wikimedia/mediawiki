<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => "-"

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#

require_once( "LanguageUtf8.php" );

if($wgMetaNamespace === FALSE)
	$wgMetaNamespace = str_replace( " ", "_", $wgSitename );

/* private */ $wgNamespaceNamesFy = array(
	-2	=> "Media",
	-1	=> "Wiki",
	0	=> "",
	1	=> "Oerlis",
	2	=> "Meidogger",
	3	=> "Meidogger_oerlis",
	4	=> $wgMetaNamespace,
	5	=> $wgMetaNamespace . "_oerlis",
	6	=> "Ofbyld",
	7	=> "Ofbyld_oerlis",
	8	=> "MediaWiki",
	9	=> "MediaWiki_oerlis",
	10  => "Berjocht",
	11  => "Berjocht_oerlis",
            12  => "Hulp",
            13  => "Hulp_oerlis",
            14  => "Kategory",
            15  => "Kategory_oerlis"

) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsFy = array(
	"Ut", "Lofts fêst", "Rjochts fêst", "Lofts sweevjend"
);

/* private */ $wgSkinNamesFy = array(
	'standard' => "Standert",
	'nostalgia' => "Nostalgy",
	'cologneblue' => "Keuls blau",
	'smarty' => "Paddington",
	'montparnasse' => "Montparnasse",
	'davinci' => "DaVinci",
	'mono' => "Mono",
	'monobook' => "MonoBook",
 "myskin" => "MySkin" 
);


/* private */ $wgDateFormatsFy = array(
	'Gjin foarkar',
	'16:12, jan 15, 2001',
	'16:12, 15 jan 2001',
	'16:12, 2001 jan 15',
	'ISO 8601' => '2001-01-15 16:12:34'
);

/* private */ $wgBookstoreListFy = array(
);

# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesFy = array(
	"Userlogin"		=> "",
	"Userlogout"	=> "",
	"Preferences"	=> "Ynstellings",
	"Watchlist"		=> "Folchlist",
	"Recentchanges"   => "Koarts feroare",
	"Upload"		=> "Ofbyld oanbiede",
	"Imagelist"		=> "Ofbyld list",
	"Listusers"		=> "Bekinde brûkers",
	"Statistics"	=> "Statistyk",
	"Randompage"	=> "Samar in side",

	"Lonelypages"	=> "Lossteande siden",
	"Unusedimages"	=> "Lossteande ôfbylden",
	"Popularpages"	=> "Grage siden",
	"Wantedpages"	=> "Nedige siden",
	"Shortpages"	=> "Koarte siden",
	"Longpages"		=> "Lange siden",
	"Newpages"		=> "Nije siden",
	"Ancientpages"	=> "Alde siden",
	"Allpages"		=> "Alle titels",

	"Ipblocklist"	=> "Utsletten brûkers/Ynternet-adressen",
	"Maintenance"     => "Underhâldsside",
	"Specialpages"    => "Bysûndere siden",
	"Contributions"   => "",
	"Emailuser"		=> "",
	"Whatlinkshere"   => "",
	"Recentchangeslinked" => "",
	"Movepage"		=> "",
	"Booksources"	=> "",
#	"Categories"      => "Kategoryen",
	"Export"	=> "XML export",
	"Version"	=> "Version",
);

/* private */ $wgSysopSpecialPagesFy = array(
	"Blockip"		=> "Utsletten brûker/Ynternet-adres",
	"Asksql"		=> "Freegje de databank",
	"Undelete"		=> "Set wisse siden wer teplak"
);

/* private */ $wgDeveloperSpecialPagesFy = array(
	"Lockdb"  		=> "Meitsje de databank Net-Skriuwe",
	"Unlockdb"  	=> "Meitsje de databank skriuwber",
);

/* private */ $wgAllMessagesFy = array(
'special_version_prefix' => '',
'special_version_postfix' => '',
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
#"linktrail"		=> "/^([àáèéìíòóùúâêîôûäëïöüa-z]+)(.*)\$/sD",
"linktrail"		=> "/^((?:[a-z]|à|á|è|é|ì|í|ò|ó|ù|ú|â|ê|î|ô|û|ä|ë|ï|ö|ü)+)(.*)\$/sD",
"mainpage"		=> "Haadside",
"mainpagetext"	=> "Wiki-programma goed installearre.",
"about"		=> "Ynfo",
"aboutsite"      	=> "Oer de $wgSitename",
"aboutpage"		=> "$wgMetaNamespace:Ynfo",
"help"		=> "Help",
"helppage"		=> "$wgMetaNamespace:Help",
"wikititlesuffix" => "$wgSitename",
"bugreports"	=> "Brekmelding",
"bugreportspage"	=> "$wgMetaNamespace:Brekmelding",
"faq"			=> "FAQ",
"faqpage"		=> "$wgMetaNamespace:FAQ",
"edithelp"		=> "Siden bewurkje",
"edithelppage"	=> "$wgMetaNamespace:Bewurk-rie",
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
"tagline"      	=> "Fan $wgSitename, de frije ensyklopedy.", # FIXME
"whatlinkshere"	=> "Siden mei in keppeling hjirhinne",
"help"		=> "Help",
"search"		=> "Sykje",
"go"			=> "Side",
"history"		=> "Sideskiednis",
"printableversion" => "Ofdruk-ferzje",
"editthispage"	=> "Side bewurkje",
"deletethispage" 	=> "Side wiskje",
"protectthispage" => "Side beskermje",
"unprotectthisside" => "Side frij jaan",
"newpage" 		=> "Nije side",
"talkpage"		=> "Sideoerlis",
"postcomment"   	=> "Skrieuw in opmerking",
"articlepage"	=> "Side lêze",
"subjectpage"	=> "Side lêze", # For compatibility
"userpage" 		=> "Brûkerside",
"wikipediapage" 	=> "Metaside",
"imagepage" 	=> "Ofbyldside",
"viewtalkpage" 	=> "Oerlisside",
"otherlanguages" 	=> "Oare talen",
"redirectedfrom" 	=> "(Trochwiisd fan \"$1\")",
"lastmodified"	=> "Lêste kear bewurke op $1.",
"viewcount"		=> "Disse side is $1 kear iepenslein.",
"gnunote" 		=> "Alle tekst is beskiber ûnder de betingsten fan de <a class=internal href='$wgScriptPath/GNU_Vrije_Documentatie_Licentie'>GNU Iepen Dokumentaasje Lisinsje</a>.",
"printsubtitle" 	=> "(Fan http://$wgServer)",
"protectedpage" 	=> "Beskerme side",
"administrators" 	=> "$wgMetaNamespace:Behear",
"sysoptitle"	=> "Allinnich foar behearders",
"sysoptext"		=> "Om dit te dwaan moatte jo behearder wêze. Sjoch \"$1\".",
"developertitle"  => "Allinich foar untwiklers",
"developertext"	=> "Om dit te dwaan moatte jo ûntwikler wêze. Sjoch \"$1\".",
"nbytes"		=> "$1 byte",
"go"			=> "Side",
"ok"			=> "Goed",
"sitetitle"		=> $wgSitename,
"sitesubtitle"	=> "De frije ensyklopedy",
"retrievedfrom" 	=> "Untfongen fan \"$1\"",
"newmessages" 	=> "Jo hawwe $1.",
"newmessageslink" => "nije berjochten",
"editsection"	=> "edit",
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
"readonlytext"	=> "De $wgSitename databank is ôfsletten foar nije siden en oare wizigings,
nei alle gedachten is it foar ûnderhâld, en kinne jo der letter gewoan wer brûk fan meitsje.
De behearder hat dizze útlis joen:
<p>$1</p>",

"missingarticle" 		=> "De databank kin in side net fine, nammentlik: \"$1\".
<p>Faak is dit om't in âlde ferskil-, of skiednisside opfreege wurdt fan in side dy't wiske is.
<p>As dat it hjir net is, dan hawwe jo faaks in brek yn it programa fûn.
Jou dat asjebleaft troch oan de [[$wgMetaNamespace:Brekmelding|behearder]], tegearre mei de URL.",

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
Jo kinne de $wgSitename fierders anonym brûke,
of jo op 'e nij [[Wiki:Userlogin|oanmelde]] ûnder in oare namme.\n",
"welcomecreation" => "<h2>Wolkom, $1!</h2><p>Jo ynstellings bin oanmakke.
Ferjit net se oan jo foarkar oan te passen.",

"loginpagetitle" 	=> "Oanmelde",
"yourname"  	=> "Jo brûkersnamme",
"yourpassword" => "Jo wachtwurd",
"yourpasswordagain" => "Jo wachtwurd (nochris)",
"newusersonly" 	=> " (allinnich foar nije brûkers)",
"remembermypassword" => "Oare kear fansels oanmelde.",
"loginproblem" 	=> "<b>Der wie wat mis mei jo oanmelden.</b><br />Besykje it nochris, a.j.w.",
"alreadyloggedin" => "<font color=red><b>Brûker $1, jo binne al oanmeld!</b></font><br />\n",
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
"emailforlost"	=> "* In e-postadres hoecht net.<br />
Mar it helpt, soenen jo jo wachtwurd ferjitte.
En mei in e-postadres kinne oaren fan de web siden contact mei jo krije,
sûnder dat se dat adres witte. (Dat leste kin ek wer útset by de instellings.)",

"loginerror"	=> "Oanmeldflater",
"noname"		=> "Jo moatte in brûkersnamme opjaan.",
"loginsuccesstitle" => "Oanmelden slagge.",
"loginsuccess"	=> "Jo binne no oanmelde op de $wgSitename as: $1.",
"nosuchuser"	=> "Brûkersnamme en wachtwurd hearre net by elkoar.
Besykje op 'e nij, of fier it wachtwurd twa kear yn en meitsje neie brûkersynstellings.",

"wrongpassword"	=> "Brûkersnamme en wachtwurd hearre net by elkoar.
Besykje op 'e nij, of fier it wachtwurd twa kear yn en meitsje neie brûkersynstellings.",

"mailmypassword" 	=> "Stjoer my in nij wachtwurd.",
"passwordremindertitle" => "Nij wachtwurd foar de $wgSitename",
"passwordremindertext" => "Immen (nei alle gedachten jo, fan Ynternet-adres $1)
hat frege en stjoer jo in nij $wgSitename wachtwurd.
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
e-postadres opjûn hawwe in jo [[Wiki:Preferences|ynstellings]].",

"newarticle"	=> "(Nij)",
"newarticletext" =>
"Jo hawwe in keppeling folge nei in side dêr't noch gjin tekst op stiet.
Om sels tekst te meistjsen kinne jo dy gewoan yntype in dit bewurkingsfjild 
([[$wgMetaNamespace:Bewurk-rie|Mear ynformaasje oer bewurkjen]].)
Oars kinne jo tebek mei de tebek-knop fan jo blêdzjer.",

"anontalkpagetext" => "---- ''Dit is de oerlisside fan in unbekinde brûker; in brûker
dy't sich net oanmeld hat. Om't der gjin namme is wurd it Ynternet-adres brûkt om
oan te jaan wa. Mar faak is it sa dat sa'n adres net altid troch deselde brûkt wurdt.
As jo it idee hawwe dat jo as ûnbekinde brûker opmerkings foar in oar krije, dan kinne
jo jo [[Wiki:Userlogin|oanmelde]], dat jo allinnich opmerkings foar josels krije.'' ",
"noarticletext" => "(Der stjit noch gjin tekst op dizze side.)",
"updated"		=> "(Bewurke)",
"note"		=> "<strong>Opmerking:</strong> ",
"previewnote"	=> "Tink der om dat dizze side noch net fêstlein is!",
"previewconflict" => "Dizze side belanget allinich it earste bewurkingsfjild oan.",
"editing"		=> "Bewurkje \"$1\"",
"editing"		=> "Bewurkje \"$1\" (seksje)",
"editing"		=> "Bewurkje \"$1\" (nije opmerking)",
"editconflict"	=> "Tagelyk bewurke: \"$1\"",
"explainconflict" => "In oar hat de side feroare sûnt jo begûn binne mei it bewurkjen.
It earste bewurkingsfjild is hoe't de tekst wilens wurde is. 
Jo feroarings stean yn it twadde fjild.
Dy wurde allinnich tapasse safier as jo se yn it earste fjild ynpasse.
<b>Allinnich</b> de tekst út it earste fjild kin fêstlein wurde.\n<p>",
"yourtext"		=> "Jo tekst",
"storedversion" => "Fêstleine ferzje",
"editingold"	=> "<strong><font color=red>Waarskôging</font>: Jo binne dwaande mei in âldere ferzje fan dizze side.
Soenen jo dizze fêstlizze, dan is al wat sûnt dy tiid feroare is kwyt.</strong>\n",
"yourdiff"		=> "Feroarings",
# REPLACE THE COPYRIGHT WARNING IF YOUR SITE ISN'T GFDL!
"copyrightwarning" => "Alle bydragen ta de $wgSitename wurde sjoen
as fallend ûnder de GNU Iepen Dokumentaasje Lisinsje
(sjoch fierders: \"$1\").
As jo net wolle dat jo skriuwen ûnferbidlik oanpast en frij ferspraat wurdt,
dan is it baas, en set it net op de $wgSitename.<br />
Jo ferklare ek dat jo dit sels skreaun hawwe, of it oernaam hawwe út in
publyk eigendom of in oare iepen boarne.
<strong><big>Foeg gjin wurk ûnder auteursrjocht ta sûnder tastimming!</big></strong>",
"longpagewarning" => "<font color=red>Waarskôging</font>: Dizze side is $1 kilobyte lang; 
der binne blêdzjers dy problemen hawwe mei siden fan tsjin de 32kb. of langer.
Besykje de side yn lytsere stikken te brekken.",
"readonlywarning" => "<font color=red>Waarskôging</font>: De databank is ôfsletten foar
ûnderhâld, dus jo kinne jo bewurkings no net fêstlizze.
It wie baas en nim de tekst foar letter oer yn in tekstbestân.",
"protectedpagewarning" => "<font color=red>Waarskôging</font>: Dizze side is beskerme, dat
gewoane brûkers dy net bewurkje kinne. Tink om de
<a href='$wgScriptPath/$wgMetaNamespace:Beskerm-rie'>rie oer beskerme siden</a>.",

# History pages
#
"revhistory"	=> "Sideskiednis",
"nohistory"		=> "Dit is de earste ferzje fan de side.",
"revnotfound"	=> "Ferzje net fûn",
"revnotfoundtext" => "De âlde ferzje fan dizze side dêr't jo om frege hawwe, is der net.
Gean nei of de keppeling dy jo brûkt hawwe wol goed is.\n",
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
"searchresulttext" => "\"[[Project:Syk-rie|Ynformaasje oer it sykjen|Sykje troch de {{SITENAME}}]]\" troch de {{SITENAME}}.",
"searchquery"	=> "Foar fraach \"$1\"",
"badquery"		=> "Misfoarme sykfraach",
"badquerytext"	=> "Jo fraach koe net ferwurke wurde.
Dit is faaks om't jo besyke hawwe en sykje in word fan ien of twa letters, wat it programma noch net kin.
Of it soe kinne dat jo de fraach misskreaun hawwe, lykas \"frysk en en frei\". Besykje it nochris.",
"matchtotals"	=> "Foar \"$1\" binne $2 titles fûn en $3 siden.",
"nogomatch" => "Der is gjin side mei krekt dizze titel. Faaks is it better en Sykje nei dizze tekst.",
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
"blanknamespace" => "($wgSitename)",


# Preferences page
#
"preferences"		=> "Ynstellings",
"prefsnologin" 		=> "Net oanmeld",
"prefsnologintext"	=> "Jo moatte [[Wiki:Userlogin|oanmeld]] wêze om jo ynstellings te feroarjen.",

"prefslogintext" 		=> "Jo binne oanmeld, $1.
Jo Wiki-nûmer is $2.

([[$wgMetaNamespace:Ynstelling-rie|Help by de ynstellings]].",

"prefsreset"		=> "De ynstellings binne tebek set sa't se fêstlein wienen.",
"qbsettings"		=> "Menu", 
"changepassword" 		=> "Wachtword feroarje",
"skin"			=> "Side-oansjen",
"math"			=> "Formules",
"dateformat"		=> "Datum",
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
"emailflag"			=> "Gjin post fan oare brûkers",
"defaultns"			=> "Nammeromten dy't normaal trochsykje wurde:",

# Recent changes
#
"changes" 			=> "feroarings",
"recentchanges" 		=> "Koarts feroare",
# This is the default text, and can be overriden by editing [[$wgMetaNamespace::Recentchanges]]
"recentchangestext" 	=> "De lêste feroarings fan de $wgSitename.",
"rcloaderr"			=> "Koarts feroare ...",
"rcnote"			=> "Dit binne de lêste <strong>$1</strong> feroarings yn de lêste <strong>$2</strong> dagen.",
"rcnotefrom"		=> "Dit binne de feroarings sûnt <b>$2</b> (maksimaal <b>$1</b>).",
"rclistfrom"		=> "Jou nije feroarings, begjinnende mei $1",
"rclinks"			=> "Jou $1 nije feroarings yn de lêste $2 dagen; $3 tekstwiziging",
"rchide"			=> "in $4 form; $1 tekstwizigings; $2 oare nammeromten; $3 meartallige feroarings.",
"rcliu"			=> "; $1 feroarings troch oanmelde brûkers",
"diff"			=> "ferskil",
"hist"			=> "skiednis",
"hide"			=> "gjin",
"show"			=> "al",
"tableform"			=> "tabel",
"listform"			=> "list",
"nchanges"			=> "$1 feroarings",
"minoreditletter" 	=> "T",
"newpageletter" 		=> "N",

# Upload
#
"upload"		=> "Bied bestân oan",
"uploadbtn"		=> "Bied bestân oan",
"uploadlink"	=> "Bied ôfbylden oan",
"reupload"		=> "Op 'e nij oanbiede",
"reuploaddesc"	=> "Werom nei oanbied-side.",
"uploadnologin" 	=> "Net oanmelde",
"uploadnologintext" => "Jo moatte [[Wiki:Userlogin|oanmeld]] wêze om in bestân oanbieden te kinnen.",

"uploadfile"	=> "Bied ôfbylden, lûden, dokuminten ensfh. oan.",
"uploaderror"	=> "Oanbied-fout",
"uploadtext"	=> "'''STOP!''' Lês ear't jo eat oanbiede
de [[Project:Ofbyld-rie|regels foar ôfbyldbrûk]] foar de {{SITENAME}}.

Earder oanbeane ôfbylden, kinne jo fine op de
[[Project:Imagelist|list of oanbeane ôfbylden]].
Wat oanbean en wat wiske wurdt, wurdt delskreaun yn it
[[Project:Oanbied-loch|lochboek]].

Om't nije ôfbylden oan te bieden, kieze jo in bestân út sa't dat
normaal is foar jo blêdzjer en bestjoersysteem.
Dan jouwe jo oan jo gjin auteursrjocht skeine troch it oanbieden.
Mei \"Bied oan\" begjinne jo dan it oanbieden.
Dit kin efkes duorje as jo Ynternet-ferbining net sa flug is.

Foar de bestânsforam wurdt foto's JPEG oanret, foar tekenings ensfh. PNG, en foar
lûden OGG. Brûk in dúdlike bestânsnamme, sa't in oar ek wit wat it is.

Om it ôfbyld yn in side op te nimmen, meitsje jo dêr sa'n keppeling:<br />
'''<nowiki>[[ôfbyld:jo_foto.jpg|omskriuwing]]</nowiki>''' of
'''<nowiki>[[ôfbyld:jo_logo.png|omskriuwing]]</nowiki>''';
en foar lûden '''<nowiki>[[media:jo_lûd.ogg]]</nowiki>'''.

Tink derom dat oaren bewurkje kinne wat jo oanbiede, as dat better is foar de $wgSitename,
krekt's sa't dat foar siden jildt, en dat jo útsletten wurde kinne as jo misbrûk
meitsje fan it systeem..",

"uploadlog"		=> "oanbied log",
"uploadlogpage" 	=> "Oanbied_log",
"uploadlogpagetext" => "Liste fan de lêst oanbeane bestannen.
(Tiid oanjûn as UTC).
<ul>
</ul>
",

"filename"		=> "Bestânsnamme",
"filedesc"		=> "Omskriuwing",
"affirmation"	=> "Ik befêstigje dat de eigner fan de rjochten op dit bestân 
ynstimt mei fersprieding ûnder de betingsten fan de $1.",

"copyrightpage" 	=> "$wgMetaNamespace:Auteursrjocht",
"copyrightpagename" => "$wgSitename auteursrjocht",
"uploadedfiles"	=> "Oanbeane bestannen",
"noaffirmation" => "Jo moatte befestigje dat wat jo oanbiede gjin rjochten skeint.",
"ignorewarning"	=> "Sjoch oer de warskôging hinne en lis bestân dochs fêst.",
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
"ilshowmatch"	=> "Jou alle ôfbylden mei in name as",
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
"sitestatstext" => "It tal fan siden in de $wgSitename is: <b>$2</b>.<br />
(Oerlissiden, siden oer de $wgSitename, oare bysûndere siden,  stobben en
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
"maintenance"		=> "Underhâld",
"maintnancepagetext"	=> "Op dizze side stiet ark foar it deistich ûnderhâld.
In part fan de funksjes freegje in soad fan de databank, dus freegje net nei
eltse oanpassing daalks in fernijde side op.",

"maintenancebacklink"	=> "Werom nei Underhâldside",
"disambiguations"		=> "Trochverwizings",
"disambiguationspage"	=> "$wgMetaNamespace:trochferwizing",
"disambiguationstext"	=> "Dizze siden binne keppele fia in
[[$wgMetaNamespace:trochferwizing]]. 
Se soenen mei de side sels keppele wurde moatte.<br />
(Allinnich siden út deselde nammeromte binne oanjûn.)",

"doubleredirects"	=> "Dûbele trochverwizings",
"doubleredirectstext"	=> "<b>Let op!</b> Der kinne missen yn dizze list stean!
Dat komt dan ornaris troch oare keppelings ûnder de \"#REDIRECT\".<br />
Eltse rigel jout keppelings nei de earste en twadde trochverwizing, en dan de earste regel fan
de twadde trochferwizing, wat it \"echte\" doel wêze moat.",

"brokenredirects"		=> "Misse trochferwizings",
"brokenredirectstext"	=> "Dizze trochferwizings ferwize nei siden dy't der net binne.",
"selflinks"			=> "Siden mei sels-ferwizings",
"selflinkstext"		=> "Dizze siden hawwe in keppeling nei de side sels, wat net sa wêze moat.",
"mispeelings"           => "Siden mei skriuwflaters",
"mispeelingstext"		=> "Op dizze siden stiet in skriuw- of typ-flater dy't in soad makke wurd, lykas oanjoen op \"$1\".
Dêr soe ek stean moatte hoe't it (goed skreaun) wurdt.",
"mispeelingspage"       => "List fan faak makke flaters",
"missinglanguagelinks"  => "Untbrekkende taalkeppelings",
"missinglanguagelinksbutton"    => "Fyn ûntbrekkende taalkeppelings foar",
"missinglanguagelinkstext"      => "Dizze siden hawwe gjin taalkeppeling nei deselde side yn taal \"$1\".
(Ferwizings en oanheake siden binne net <i>net</i> besocht.",


# Miscellaneous special pages
#
"orphans"		=> "Lossteande siden",
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
"sysopspheading"	=> "Allinich foar behearders",
"developerspheading" => "Allinich foar untwiklers",
"protectpage"	=> "Beskerm side",
"recentchangeslinked" => "Folgje keppelings",
"rclsub"		=> "(nei siden dêr't \"$1\" keppelings nei hat)",
"debug"		=> "Breksykje",
"newpages"		=> "Nije siden",
"ancientpages"	=> "Alde siden",
"movethispage"	=> "Move this side",
"unusedimagestext" => "<p>Tink derom dat ore web sides lykas fan de oare
parten fan it meartaliche projekt mei in keppeling nei in direkte URL nei
an ôfbyld makke hawwe kinne. Dan wurde se noch brûke, mar stean al in dizze list.",

"booksources"	=> "",
"booksourcetext" 	=> "",
"alphaindexline" 	=> "$1 oan't $2",


# Email this brûker
#
"mailnologin"	=> "Gjin adres beskikber",
"mailnologintext" => "Jo moatte [[Wiki:Userlogin|oanmeld]]
wêze, en in jildich e-postadres [[Wiki:Preferences|ynsteld]]
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
"watchlistsub"	=> "(foar brûker \"$1\")",
"nowatchlist"	=> "Jo hawwe gjin siden op jo folchlist.",
"watchnologin"	=> "Not oanmeld in",
"watchnologintext"=> "Jo moatte [[Wiki:Userlogin|oanmeld]] wêze om jo folchlist te feroarjen.",

"addedwatch"	=> "Oan folchlist tafoege",
"addedwatchtext"	=> "De side \"$1\" is tafoege oan jo <a href=\"" 
. "{{localurle:Wiki:Watchlist}}\">folchlist</a>.
As dizze side sels, of de oerlisside, feroare wurd, dan komt dat dêr yn,
en de side stiet dan ek <b>fet</b> yn de <a href=\"" .
  "{{localurle:Wiki:Recentchanges}}\">Koarts feroare</a> list.

<p>As jo letter in side net mear folgje wolle, dan brûke jo \"Ferjit dizze side\".",
"removedwatch"	=> "Net mear folgje",
"removedwatchtext" => "De side \"$1\" stiet net mear op jo folchlist.",
"watchthispage"	=> "Folgje dizze side",
"unwatchthispage" => "Ferjit dizze side",
"notanarticle"	=> "Dit kin net folge wurde.",
"watchnochange" 	=> "Fan de siden dy't jo folgje is der yn dizze perioade net ien feroare.",
"watchdetails"	=> "Jo folchlist hat $1 siden (oerlissiden net meiteld).
In dizze perioade binne der $2 siden feroare.
$3. (<a href='$4'>Gâns myn folchlist</a>.)",

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
"excontent"		=> "inhâld wie:",
"exbeforeblank" 	=> "foar de tekst wiske wie, wie dat:",
"exblank"		=> "side wie leech",
"confirmdelete"	=> "Befestigje wiskjen",
"deletesub"		=> "(Wiskje \"$1\")",
"historywarning"	=> "Waarskôging: De side dy't jo wiskje wolle hat skiednis: ",
"confirmdeletetext" => "Jo binne dwaande mei it foar altyd wiskjen fan in side
of ôfbyld, tegearre mei alle skiednis, út de databank.
Befêstigje dat jo dat wier dwaan wolle. Befêstigje dat dat is wat jo witte wat it gefolch 
is en dat jo dit dogge neffens de [[$wgMetaNamespace:wisk-rie]].",

"actioncomplete"	=> "Dien",
"deletedtext"	=> "\"$1\" is wiske.
Sjoch \"$2\" foar in list fan wat resint wiske is.",
"deletedarticle"	=> "\"$1\" is wiske",
"dellogpage"	=> "Wisk_loch",
"dellogpagetext" => "Dit is wat der resint wiske is.
(Tiden oanjûn as UTC).
<ul>
</ul>
",

"deletionlog"	=> "wisk loch",
"reverted"		=> "Tebekset nei eardere ferzje",
"deletecomment"	=> "Reden foar it wiskjen",
"imagereverted"	=> "Tebeksette nei eardere ferzje is slagge.",
"rollback"		=> "Feroarings tebeksette",
"rollbacklink"	=> "feroaring tebeksette",
"rollbackfailed"	=> "Feroaring tebeksette net slagge",
"cantrollback"	=> "Disse feroaringt kin net tebek set, om't der mar ien skriuwer is.",
"alreadyrolled"	=> "Kin de feroaring fan [[$1]]
troch [[Brûker:$2|$2]] ([[Brûker oerlis:$2|Oerlis]]) net tebeksette;
inoar hat de feroaring tebekset, of oars wat oan de side feroare.

De lêste feroaring wie fan [[Brûker:$3|$3]] ([[Brûker oerlis:$3|Oerlis]]). ",
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
"undeletedtext"   => "It weromsette fan side [[$1]] is slagge.
(List fan resint [[$wgMetaNamespace:wisk-loch|wiske of weromsette siden]].",

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
"blockiptext"	=> "Brûk dizze fjilden om in brûker fan skriuwtagong út te sluten.
Dit soe allinnich omwillens fan fandalisme dwaan wurde moatte, sa't de
[[$wgMetaNamespace:Utslut-rie|útslut-rie]] it oanjout.
Meld de krekte reden! Begelyk, neam de siden dy't oantaaste waarden.",
"ipaddress"		=> "Brûkernamme of Ynternet-adres",
"ipbreason"		=> "Reden",
"ipbsubmit"		=> "Slut dizze brûker út",
"badipaddress"	=> "Dy brûker bestiet net",
"noblockreason"	=> "Jo moatte de krekte reden opjaan.",
"blockipsuccesssub" => "Utsluting slagge",
"blockipsuccesstext" => "Brûker \"$1\" is útsletten.<br />
(List fan [[Wiki:Ipblocklist|útslette brûkers]].)",
"unblockip"		=> "Lit brûker der wer yn",
"unblockiptext"	=> "Brûk dizze fjilden om in brûker wer skriuwtagong te jaan.",
"ipusubmit"		=> "Lit dizze brûker der wer yn",
"ipusuccess"	=> "Brûker \"$1\" ynlitten",
"ipblocklist"	=> "List fan útsletten Ynternet-adressen en brûkersnammen",
"blocklistline"	=> "$\"3\", troch \"$2\" op $1",
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
"lockdbsuccesstext" => "De $wgSitename databank is 'Net-skriuwe' makke.
<br />Tink derom en meitsje de databank skriuwber as jo ûnderhâld ree is.",
"unlockdbsuccesstext" => "De $wgSitename databank is skriuwber makke.",

# SQL query
#
"asksql"		=> "SQL-fraach",
"asksqltext"	=> "Brûk dizze fjilden foar in databank-fraach oan de $wgSitename databank.
Brûk inkele oanheltekens ('likas dit') foar tekst.
Dit kin in foar de tsjinner in soad wurk betsjutte. Brûk dit dus net ûnnedig.",
"sqlislogged"	=> "(Alle fragen komme yn in lochbestân.)",
"sqlquery"		=> "Fraach",
"querybtn"		=> "Bied de fraach oan",
"selectonly"	=> "Oare fragen as \"SELECT\" binne foarbehâlden oan
$wgSitename ûntwiklers.",
"querysuccessful" => "Fraach slagge",


# Move page
#
"movepage"		=> "Werneam side",
"movepagetext"	=> "Dit werneamt in side, mei alle sideskiednis.
De âlde titel wurdt in trochferwizing nei de nije.
Keppelings mei de âlde side wurde net feroare; 
[[Wiki:Maintenance|gean sels nei]] of't der dûbele of misse ferwizings binne.
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
"movenologintext" => "Jo moatte [[Wiki:Userlogin|oanmeld]] wêze om in side wer te neamen.",

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
"moveoerlis"	=> "De oerlisside, as dy der is, moat oan de side keppele bliuwe.",
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


class LanguageFy extends LanguageUtf8 {

	function getBookstoreList () {
		global $wgBookstoreListFy ;
		return $wgBookstoreListFy ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesFy;
		return $wgNamespaceNamesFy;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesFy;
		return $wgNamespaceNamesFy[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesFy;

		foreach ( $wgNamespaceNamesFy as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		if ( 0 == strcasecmp( "Brûker", $text ) ) return 2; 
		if ( 0 == strcasecmp( "Brûker_oerlis", $text ) ) return 3;
		if ( 0 == strcasecmp( "Special", $text ) ) return -1;
		return false;
	}


	function getQuickbarSettings() {
		global $wgQuickbarSettingsFy;
		return $wgQuickbarSettingsFy;
	}

	function getSkinNames() {
		global $wgSkinNamesFy;
		return $wgSkinNamesFy;
	}

	function getDateFormats() {
		global $wgDateFormatsFy;
		return $wgDateFormatsFy;
	}

  
	function date( $ts, $adj = false ) {
		global $wgUser;

		if ( $adj ) { $ts = $this->userAdjust( $ts ); }
		
		switch ( $wgUser->getOption( 'date' ) ) {
			# jan 8, 2001
			case '0': case '1': return $d = $this->getMonthAbbreviation( substr( $ts, 4, 2 ) )
				. ' ' . (0 + substr( $ts, 6, 2 )) . ', ' . substr( $ts, 0, 4 );
			# 8 jannewaris 2001
			case '2': return (0 + substr( $ts, 6, 2 )) . ' ' .
				$this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) . ' ' .
				substr( $ts, 0, 4 );
			case 'ISO 8601': return substr($ts, 0, 4). '-' . substr($ts, 4, 2). '-' .substr($ts, 6, 2);
			# 2001 jannewaris 8
			default: return substr( $ts, 0, 4 ) . ' ' .
				$this->getMonthAbbreviation( substr( $ts, 4, 2 ) )
				. ' ' . (0 + substr( $ts, 6, 2 ));
		}
	}
	function timeanddate( $ts, $adj = false ) {
		global $wgUser;
		
		switch ( $wgUser->getOption( 'date' ) ) {
			case 'ISO 8601': return $this->date( $ts, $adj ) . ' ' . $this->time( $ts, $adj ); 
			default: return $this->time( $ts, $adj ) . ', ' . $this->date( $ts, $adj );
		}
	}

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesFy;
		return $wgValidSpecialPagesFy;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesFy;
		return $wgSysopSpecialPagesFy;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesFy;
		return $wgDeveloperSpecialPagesFy;
	}

	function getMessage( $key )
	{
		global $wgAllMessagesFy;
		if( isset( $wgAllMessagesFy[$key] ) ) {
			return $wgAllMessagesFy[$key];
		} else {
			return Language::getMessage( $key );
		}
	}
}

?>

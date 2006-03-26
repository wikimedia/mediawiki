<?php
/** Limburgish (Limburgs)
  *
  * @package MediaWiki
  * @subpackage Language
  */
/**
 * @access private
 */
$wgNamespaceNamesLi = array(
	NS_MEDIA			=> 'Media',
	NS_SPECIAL			=> 'Speciaal',
	NS_MAIN				=> '',
	NS_TALK				=> 'Euverlik',
	NS_USER				=> 'Gebroeker',
	NS_USER_TALK		=> 'Euverlik_gebroeker',
	NS_PROJECT			=> $wgMetaNamespace,
	NS_PROJECT_TALK		=> 'Euverlik_Wikipedia',
	NS_IMAGE			=> 'Aafbeilding',
	NS_IMAGE_TALK		=> 'Euverlik_afbeelding',
	NS_MEDIAWIKI		=> 'MediaWiki',
	NS_MEDIAWIKI_TALK	=> 'Euverlik_MediaWiki',
	NS_TEMPLATE			=> 'Sjabloon',
	NS_TEMPLATE_TALK	=> 'Euverlik_sjabloon',
	NS_HELP				=> 'Help',
	NS_HELP_TALK		=> 'Euverlik_help',
	NS_CATEGORY			=> 'Kategorie',
	NS_CATEGORY_TALK	=> 'Euverlik_kategorie'

) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsLi = array(
 "Oetgesjakeld", "Links vas", "Rechts vas", "Links zwevend"
);

/* private */ $wgSkinNamesLi = array(
	'standard' => "Standaard",
	'nostalgia' => "Nostalgie",
	'cologneblue' => "Keuls blauw",
) + $wgSkinNamesEn;

/* private */ $wgAllMessagesLi = array(

'sunday' => 'zondig',
'monday' => 'moandig',
'tuesday' => 'dinsdig',
'wednesday' => 'woonsdig',
'thursday' => 'donderdig',
'friday' => 'vriedig',
'saturday' => 'zoaterdig',
'january' => 'januari',
'february' => 'fibberwari',
'march' => 'maart',
'april' => 'april',
'may_long' => 'mei',
'june' => 'juni',
'july' => 'juli',
'august' => 'augustus',
'september' => 'september',
'october' => 'oktober',
'november' => 'november',
'december' => 'december',
'jan' => 'jan',
'feb' => 'feb',
'mar' => 'mrt',
'apr' => 'apr',
'may' => 'mei',
'jun' => 'jun',
'jul' => 'jul',
'aug' => 'aug',
'sep' => 'sep',
'oct' => 'okt',
'nov' => 'nov',
'dec' => 'dec',

# Bits of text used by many pages:
# Diverse stukjes tekst
"linktrail" => "/^([àáèéìíòóùúâêîôûäöüïëça-z]+)(.*)$/sDu",
"mainpage"  => "Hoofdpagina",
"about"   => "Info",
'aboutsite' => 'Euver {{SITENAME}}',
"aboutpage"  => "Wikipedia:info",
"help"   => "Help",
"helppage"  => "Wikipedia:Help",
"bugreports" => "Foutenrapportage",
"bugreportspage" => "Wikipedia:Foutenrapportage",
"faq"   => "FAQ",
"faqpage"  => "Wikipedia:Veulgestjilde vroage",
"edithelp"  => "Hulp bie bewirken",
"edithelppage" => "Wikipedia:Instructies",
"cancel"  => "Annulere",
"qbfind"  => "Zeuke",
"qbbrowse"  => "Bladere",
"qbedit"  => "Bewirke",
"qbpageoptions" => "Pagina-opties",
"qbpageinfo" => "Pagina-informatie",
"qbmyoptions" => "mien opties",
"mypage"  => "mien gebroekerspagina",
"mytalk"  => "mien euverlik",
"currentevents" => "In het nuuis",
"errorpagetitle" => "Fout",
"returnto"  => "Truuk noa $1.",
"whatlinkshere" => "Pagina's die hierheen verwijzen",
"help"   => "Hulp",
"search"  => "Zeuke",
"history"  => "Veurgeschiedenis",
"printableversion" => "Printer-vriendelijke versie",
"editthispage" => "Pagina bewirke",
"deletethispage" => "Verwiedere",
"protectthispage" => "Beveilige",
"unprotectthispage" => "Beveiliging opheffen",
"talkpage"  => "euverlikpagina",
"subjectpage" => "Artikel",
"articlepage"   => "Artikel",
"userpage" => "gebroekerspagina",
"wikipediapage" => "Artikel",
"imagepage" => "Beschrijvingspagina",
"otherlanguages" => "Andere talen",
"redirectedfrom" => "(Doorverwezen vanaf $1)",
"lastmodified" => "Deze pagina werd het laatst gewijzigd op $1.",
"viewcount"  => "Deze pagina werd $1 maal bekeken.",
"printsubtitle" => "(Uit {{SERVER}})",
"protectedpage" => "Beveiligde pagina",
"administrators" => "Wikipedia:Systeembeheerders",
"sysoptitle" => "Toegang alleen voor systeembeheerders",
"sysoptext"  => "De gevraagde handeling kan enkel uitgevoerd worden door systeembeheerders. Zie $1.",
"developertitle" => "Toegang alleen voor systeemontwikkelaars",
"developertext" => "De gevraagde handeling kan enkel uitgevoerd worden door systeemontwikkelaars. Zie $1.",
"nbytes"  => "$1 bytes",
"go"   => "OK",
"ok"   => "OK",
"sitetitle"  => "Wikipedia",
"sitesubtitle" => "De vrije encyclopedie",
"retrievedfrom" => "Afkomstig van Wikipedia, de Vrije Encyclopedie. \"$1\"",

# Main script and global functions
# Algemene functies
"nosuchaction" => "Gevraagde handeling bestjit neet",
"nosuchactiontext" => "De door de URL gespecifieerde handeling wordt neet herkend door de Wikipedia software",
"nosuchspecialpage" => "De gevraagde speciale pagina is onvindbaar",
"nospecialpagetext" => "U heeft een speciale pagina aangevraagd die neet wordt herkend door de Wikipedia software",

# General errors
# Algemene foutmeldingen
"error"   => "Fout",
"databaseerror" => "Database fout",
"dberrortext" => "Bie ut zeuke is un syntaxfout in de database opgetreden.
Dit kan zijn veroorzaakt door een illegale zoekactie (zie $5),
 of het duidt op een fout in de software.
De laatste zoekpoging in de database was:
<blockquote><tt>$1</tt></blockquote>
vanuit de functie \"<tt>$2</tt>\".
MySQL gaf the foutmelding \"<tt>$3: $4</tt>\".",
"noconnect"  => "Verbinden met de database op $1 was neet mogelijk",
"nodb"   => "Selectie van database $1 neet mogelijk",
"readonly"  => "Database geblokeerd",
"enterlockreason" => "Geef un reden veur de blokkiering en wielang ut waarschijnlijk git doere. De ingegeven reden zal aan de gebroekers getoond worden.",
"readonlytext" => "De database van Wikipedia is momenteel gesloten voor nieuwe bewerkingen en wijzigingen, waarschijnlijk voor bestandsonderhoud.
De verantwoordelijke systeembeheerder gaf hiervoor volgende reden op:
<p>$1",
"missingarticle" => "De database heeft een paginatekst (\"$1\") die het zou moeten vinden neet gevonden. Dit is geen fout in de database, maar waarschijnlijk in de software. Meld dit a.u.b. aan een beheerder, met vermelding van de URL.",
"internalerror" => "Interne fout",
"filecopyerror" => "Bestand \"$1\" naar \"$2\" kopiëren neet mogelijk.",
"filerenameerror" => "Wijziging titel bestand \"$1\" in \"$2\" neet mogelijk.",
"filedeleteerror" => "Kos bestjand \"$1\" neet weghoale.",
"filenotfound" => "Kos bestjand \"$1\" neet vinge.",
"unexpected" => "Onverwachte waarde: \"$1\"=\"$2\".",
"formerror"  => "Fout: kos formulier neet verzenden",
"badarticleerror" => "Deze handeling kan op deze pagina neet worden uitgevoerd.",
"cannotdelete" => "Kos de pagina of afbeelding neet verwijderen.",
"badtitle"              => "Ongeldige paginatitel",
"badtitletext"  => "De opgevraagde pagina is neet beschikbaar of leeg.",
"perfdisabled" => "Om te veurkomme dat de database weurt euverbelast is deze pagina allein tusje 03:00 en 15:00 (West-Europese zomertied) beschikbaar.",

# Login and logout pages
# Aanmelden en afmelden
"logouttitle" => "Afmelden gebroeker",
"logouttext" => "U bent nu afgemeld.
U kan Wikipedia anoniem blijven gebruiken, of u opnieuw aanmelden onder dezelfde of onder een andere naam.\n",

"welcomecreation" => "<h2>Welkom, $1!</h2><p>Uw gebroekersprofiel is aangemaakt. U kan nu uw persoonlijke voorkeuren instellen.",

"loginpagetitle" => "gebroekersnaam",
"yourname"  => "Uw gebroekersnaam",
"yourpassword" => "Uw wachtwoord",
"yourpasswordagain" => "Wachtwoord opnieuw ingeven",
"newusersonly" => " (alleen nieuwe gebroekers)",
"remembermypassword" => "mien wachtwoord onthouden voor latere sessies.",
"loginproblem" => "<b>Er is een probleem met het aanmelden.</b><br />Probeer het opnieuw a.u.b.",
"alreadyloggedin" => "<strong>gebroeker $1, u bent al aangemeld.</strong><br />\n",

"login"   => "Aanmelden",
"userlogin"  => "Aanmelden",
"logout"  => "Afmelden",
"userlogout" => "Afmelden",
"createaccount" => "Nieuw gebroekersprofiel aanmaken.",
"badretype"  => "De ingevoerde wachtwoorden verschillen van elkaar.",
"userexists" => "De gebroekersnaam die u heeft ingevoerd is al in gebruik. Gelieve een andere naam te kiezen.",
"youremail"  => "Uw e-mailadres",
"yournick"  => "Uw bijnaam (voor handtekeningen)",
"emailforlost" => "Als u uw wachtwoord bent vergeten kun u een nieuw naar uw e-mailadres laten opsturen.",
"loginerror" => "Inlogfout",
"noname"  => "U dient een gebroekersnaam op te geven.",
"loginsuccesstitle" => "Aanmelden gelukt.",
"loginsuccess" => "U bent nu aangemeld bij Wikipedia als \"$1\".",
"nosuchuser" => "Er bestaat geen gebroeker met de naam \"$1\". Controleer uw spelling, of gebruik onderstaand formulier om een nieuw gebroekersprofiel aan te maken.",
"wrongpassword" => "Het ingegeven wachtwoord is neet juist. Probeer het opnieuw.",
"mailmypassword" => "Stuur mij een nieuw wachtwoord op",
"passwordremindertitle" => "Herinnering wachtwoord Wikipedia Li",
"passwordremindertext" => "Iemand (waarschijnlijk uzelf) vanaf IP-adres $1 heeft verzocht u een nieuw wachtwoord voor Wikipedia toe te zenden. Het nieuwe wachtwoord voor gebroeker \"$2\" is \"$3\". Advies: nu aanmelden en uw wachtwoord wijzigigen.",
"noemail"  => "Er is geen e-mailadres geregistreerd voor \"$1\".",
"passwordsent" => "Er is een nieuw wachtwoord verzonden naar het e-mailadres wat geregistreerd staat voor \"$1\".
Gelieve na ontvangst opnieuw aan te melden.",

# Edit pages
# Pagina bewerken
"summary"  => "Samenvatting",
"minoredit"  => "Dit is un kleine verangering",
"watchthis" => "Volg dees pagina",
"savearticle" => "Pagina opsjloan",
"preview"  => "Noakieke",
"showpreview" => "Toon bewerking ter controle",
"blockedtitle" => "gebroeker is geBlokkierd",
"blockedtext" => "Uw gebroekersnaam of IP-adres is door $1 geBlokkierd. De opgegeven reden:<br />$2<p>. U kunt voor euverlik contact opnemen met de [[Wikipedia:Systeembeheerders|systeembeheerders]].",
"newarticle" => "(Nieuw)",
"newarticletext" => "Verwijder dit en beschrijf hier de nieuwe pagina.",
"noarticletext" => "(Deze pagina bevat momenteel geen tekst)",
"updated"  => "(Bijgewerkt)",
"note"   => "<strong>Opmerking:</strong> ",
"previewnote" => "Let op: dit is een controlepagina; uw tekst is nog neet opgeslagen!",
"previewconflict" => "Deze versie toont hoe de tekst in het bovenste veld eruit gaat zien wanneer u zou opslaan.",
"editing"  => "Bewerkingspagina: $1",
"editconflict" => "Bewerkingsconflict: $1",
"explainconflict" => "Iemand anders heeft deze pagina gewijzigd nadat u aan deze bewerking bent begonnen. Het bovenste tekstveld toont de huidige versie van de pagina. U zal uw eigen wijzigingen moeten integreren in die tekst. Alleen de tekst in het bovenste veld wordt bewaard wanneer u kiest voor \"Pagina opslaan\".<br />",
"yourtext"  => "Uw tekst",
"storedversion" => "Opgesjlage versie",
"editingold" => "<strong>WAARSCHUWING: U bent bezig een oude versie van deze pagina te bewerken. Wanneer u uw bewerking opslaat, gaan alle wijzigingen die na deze versie gedaan zijn verloren.</strong>",
"yourdiff"  => "Wijzigingen",
"copyrightwarning" => "Opgelet: Alle bijdragen aan Wikipedia worden geacht te zijn vrijgegeven onder de GNU Free Documentation License. Als u neet wil dat uw tekst door anderen naar believen bewerkt en verspreid kan worden, kies dan neet voor 'Pagina Opslaan'.<br /> Hierbij belooft u ons tevens dat u deze tekst zelf hebt geschreven, of overgenomen uit een vrije, openbare bron.<br /> <strong>GEBRUIK GEEN MATERIAAL DAT BESCHERMD WORDT DOOR AUTEURSRECHT, TENZIJ JE DAARTOE TOESTEMMING HEBT!</strong>",


# History pages
# Geschiedenis pagina's
"revhistory" => "Bewirkingsgeschiedenis",
"nohistory"  => "Deze pagina hèt nog gein bewirkinge ondergaan.",
"revnotfound" => "Wieziging neet gevonge",
"revnotfoundtext" => "De opgevroagde ouwe versie van deze pagina is onvindbaar. Controleer a.u.b. de URL die u gebruikte om naar deze pagina te gaan.\n",
"loadhist"  => "Bezig met het laden van de paginageschiedenis",
"currentrev" => "Huidige versie",
"revisionasof" => "Versie op $1",
"cur"   => "huidig",
"next"   => "volgende",
"last"   => "vorige",
"orig"   => "orig",
"histlegend" => "Verklaring afkortingen: (wijz) = verschil met huidige versie, (vorige) = verschil met voorgaande versie, K = kleine wijziging",

# Diffs
# Verschil
"difference" => "(Verschil tussen bewerkingen)",
"loadingrev" => "bezig paginaversie te laden",
"lineno"  => "Regel $1:",
"editcurrent" => "De huidige versie van deze pagina bewerken",

# Search results
# Zoek resultaten
"searchresults" => "Zoekresultaten",
"searchresulttext" => "Voor meer informatie over zoeken op Wikipedia: zie $1.",
"searchquery" => "Voor zoekopdracht \"$1\"",
"badquery"  => "Slecht geformuleerde zoekopdracht",
"badquerytext" => "Uw zoekopdracht kon neet worden uitgevoerd. Dit komt wellicht doordat u heeft geprobeerd een woord van minder dan drie letters te zoeken; dat wordt door de software neet ondersteund. Het is ook mogelijk dat u de zoekterm verkeerd hebt ingetypt, zoals bij \"vissen en en schubben\".",
"matchtotals" => "De zoekterm \"$1\" is gevonden in $2 paginatitels en in de tekst van $3 pagina's.",
"titlematches" => "Overeenkomst met volgende titels",
"notitlematches" => "Geen enkele paginatitel gevonden met de opgegeven zoekterm",
"textmatches" => "Overeenkomst met artikel inhoud",
"notextmatches" => "Geen artikel gevonden met opgegeven zoekterm",
"prevn"   => "vorige $1",
"nextn"   => "volgende $1",
"viewprevnext" => "($1) ($2) ($3) bekijken.",
"showingresults" => "Hieronder de resultaten <b>$1</b> beginnend met #<b>$2</b>.",
"nonefound"  => "<strong>Merk op:</strong> wanneer een zoekopdracht mislukt komt dat vaak door gebruik van (in het Engels) veel voorkomende woorden zoals \"of\" en \"be\", die neet geïndexeerd zijn, of door verschillende zoektermen tegelijk op te geven (u krijgt dan alleen in pagina's waaarin alle opgegeven termen voorkomen).",
"powersearch" => "Zoeken",
"powersearchtext" => "
 Zoek in naamruimten :<br />
$1<br />
$2 Toon redirects   Zoek: $3 $9",

# Preferences page
# Voorkeuren
"preferences" => "Voorkeuren",
"prefsnologin" => "Niet aangemeld",
"prefsnologintext" => "U dient [[Special:Userlogin|aangemeld]] te zijn om voorkeuren te kunnen instellen.",
"prefslogintext" => "U bent aangemeld als \"$1\". Uw interne identificatienummer is $2.",
"prefsreset" => "Standaardvoorkeuren hersteld.",
"qbsettings" => "Menubalkinstellingen",
"changepassword" => "Wachtwoord wijzigen",
"skin"   => "Wikipedia-Uiterlijk",
"saveprefs"  => "Voorkeuren opslaan",
"resetprefs" => "Standaardvoorkeuren herstellen",
"oldpassword" => "Huidig wachtwoord",
"newpassword" => "Nieuw wachtwoord",
"retypenew"  => "Voer het nieuwe wachtwoord nogmaals in",
"textboxsize" => "Afmetingen tekstveld",
"rows"   => "Regels",
"columns"  => "Kolommen",
"searchresultshead" => "Instellingen voor zoekresultaten",
"resultsperpage" => "Aantal per bladzijde te tonen zoekresultaten",
"contextlines" => "Aantal regels per gevonden pagina",
"contextchars" => "Aantal tekens van de context per regel",
"stubthreshold" => "Grootte waaronder een pagina als 'stub' wordt aangegeven",
"recentchangescount" => "Aantal titels in liest recente wijzigingen",
"savedprefs" => "Uw voorkeuren zijn opgeslagen.",
"timezonetext" => "De tied van de server is UTC (Coordinated Universal Time) Geef aan hoeveel uur de plaatselijke tied in uw woonplaats verschilt met die van de server. Voor o.a. België en Nederland: +1 (+2 zomertied); voor Suriname en voor de Nederlandse Antillen: -4; voor Zuid-Afrika: +2.",
"localtime" => "Plaatselijke tied",
"timezoneoffset" => "tiedsverschil",
"emailflag"  => "E-mail ontvangen van andere gebruiken uitschakelen",

# Recent changes
# Recente wijzigingen
"recentchanges" => "Recente wijzigingen",
"recentchangestext" => "Deze pagina toont de meest recente wijzigingen aan Wikipedia.
Mocht u hier nieuw zijn, dan welkom bij Wikipedia! Bekijk AUB de volgende pagina's eens: [[Wikipedia:Veel gestelde vragen|Veel gestelde vragen]], [[Wikipedia:Instructies|Instructies]], [[Wikipedia:Objectiviteit|Objectiviteit]] en [[Wikipedia:Wat je neet moet doen|Wat je NIET moet doen]].
Als u pagina's wilt verwijderen, ga naar [[Wikipedia:Te verwijderen pagina's|Te verwijderen pagina's]], als u iets wilt bediscussiëren, ga naar [[Wikipedia:euverlik gewenst|euverlik gewenst]]. Er is ook een email-liest voor WikipediaNL: [http://www.wikipedia.org/mailman/listinfo/wikinl-l WikiNL-l].
<br />Om Wikipedia te laten slagen is het erg belangrijk '''geen''' materiaal toe te voegen waarop iemand anders auteursrechten heeft, tenzij u daartoe toestemming heeft. De wettelijke gevolgen van inbreuk op de rechten van anderen zouden de hele onderneming zwaar kunnen schaden.",
"rcloaderr"  => "Meest recente wijzigingen laden",
"rcnote"  => "Hieronder zijn de <strong>$1</strong> laatste wijzigingen gedaan in de laatste <strong>$2</strong> dagen.",
"rcnotefrom"  => "Veranderingen sinds <b>$2</b> (met een maximum van <b>$1</b> veranderingen).",
"rclistfrom"  => "Toon de veranderingen beginnend vanaf $1",
# "rclinks"  => "Bekijk de $1 laatste wijzigingen in de laatste $2 uren / laatste $3 dagen.",
"rclinks"  => "Bekijk de $1 laatste wijzigingen in de laatste $2 dagen.",
"rchide"  => "in $4 vorm; $1 kleine wijzigingen; $2 wijzigingen op speciale pagina's zoals euverlik- en gebroekerspagina's; $3 meervoudige wijzigingen.",
"diff"   => "wijz",
"hist"   => "hist",
"hide"   => "verberg",
"show"   => "toon",
"tableform"  => "tabel",
"listform"  => "liest",
"nchanges"  => "$1 wijzigingen",
"minoreditletter" => "K",
"newpageletter" => "N",

# Upload
#
"upload"  => "Upload",
"uploadbtn"  => "upload bestand",
"uploadlink" => "upload afbeelding",
"reupload"  => "Opnieuw uploaden",
"reuploaddesc" => "Terug naar het uploadformulier.",
"uploadnologin" => "Niet aangemeld",
"uploadnologintext" => "U dient [[Special:Userlogin|aangemeld te zijn]]
om bestanden te uploaden.",
"uploaderror" => "upload fout",
"uploadtext" => "<strong>STOP!</strong> Voor u iets hier upload,
wees zeker dat het in overeenkomst is met het Wikipedia
[[Wikipedia:Beleid_voor_gebruik_van_afbeeldingen|afbeeldingsbeleid]].

Om de reeds ge-uploade bestanden te bekijken of te zoeken ga naar de . [[Special:Imagelist|liest van ge-uploade bestanden]].
Uploads en verwijderingen worden bijgehouden in het
[[Special:Log/upload|upload logboek]].

Gebroek het onderstaande formulier om bestanden zoals afbeeldingen en geluidsbestanden die relevant zijn voor uw artikel te u-loaden. Bij de meeste browers zoals 'Internet Explorer' en 'Mozilla' zult u een \"Bladeren...\" of \"Browse..\" knop zien die een standaard dialoogscherm van uw bestuuringssysteem oproept. Kiest u een bestand, dan zal het ingevuld worden in het veld naast de \"Bladeren...\" knop. U dient ook het vakje aan te vinken waarmee u bevestigt dat er geen schending van auteursrechten plaatsvindt door het gebruik van dat bestand. Vul het veld \"Omschrijving\" in. Druk op de \"Upload\" knop om het uploaden te voltooien. Dit kan even duren als u een langzame internetverbinding gebruikt.

Gebroek bij voorkeur JPEG voor foto's, PNG voor tekeningen en dergelijke en OGG voor geluid.
Geef uw bestanden een duidelijk omschrijvende naam om verwarring te voorkomen. Om het bestand in een pagina te laten verschijnen, kunt u het volgende doen;  <b><nowiki>[[afbeelding:uw_foto.jpg]]</nowiki></b> of <b><nowiki>[[afbeelding:uw_logo.png|alt text]]</nowiki></b> of <b><nowiki>[[media:uw_geluid.ogg]]</nowiki></b> voor audio.

Vergeet neet dat net als met andere pagina's op Wikipedia anderen de ge-uploade bestanden kunnen verwijderen indien men denkt dat dat in het voordeel van het project is. Bij misbruik van dit systeem kan u de toegang tot Wikipedia ontzegd worden.",
"uploadlog"  => "upload logboek",
"uploadlogpage" => "Upload_logboek",
"uploadlogpagetext" => "Hieronder de liest met de meest recent ge-uploade bestanden. Alle tieden zijn servertied (UTC).
<ul>
</ul>
",
"filename"  => "Bestandsnaam",
"filedesc"  => "Beschrijving",
"copyrightpage" => "Wikipedia:Auteursrechten",
"copyrightpagename" => "Wikipedia auteursrechten",
"uploadedfiles" => "Ge-uploade bestanden",
"ignorewarning" => "Negeer de waarschuwing en sla het bestand op.",
"minlength"  => "De naam van het bestand moet uit ten minste drie tekens bestaan.",
"badfilename" => "De naam van het bestand is gewijzigd in \"$1\".",
"badfiletype" => "\".$1\" is geen aanbevolen afbeeldings bestandsformaat.",
"largefile"  => "Aanbeveling: maak afbeeldingen neet groter dan 100k",
"successfulupload" => "De upload was succesvol",
"fileuploaded" => "<b>Het uploaden van bestand \"$1\" is geslaagd.</b> Gelieve deze link naar de omschrijvingspagina te volgen: ($2). Vul daar informatie in over dit bestand, bijvoorbeeld de oorsprong, wanneer en door wie het gemaakt is en wat u verder er nog over te vertellen heeft.",
"uploadwarning" => "Upload waarschuwing ",
"savefile"  => "Bestand opslaan",
"uploadedimage" => "heeft ge-upload: \"[[$1]]\"",

# Image list
# Afbeeldingsliest
"imagelist"  => "liest van afbeeldingen",
"imagelisttext" => "Hier volgt een liest met $1 afbeeldingen geordend $2.",
"getimagelist" => "liest van afbeeldingen ophalen",
"ilsubmit"  => "Zoek",
"showlast"  => "Toon de laatste $1 afbeeldingen geordend $2.",
"byname"  => "op naam",
"bydate"  => "op datum",
"bysize"  => "op grootte",
"imgdelete"  => "verw",
"imgdesc"  => "besc",
"imglegend"  => "Verklaring: (besc) = toon/verander beschrijving van de afbeelding, (verw) = verwijdering de afbeelding.",
"imghistory" => "Geschiedenis van de afbeelding",
"revertimg"  => "rev",
"deleteimg"  => "verw",
"imghistlegend" => "Verklaring: (cur)= huidige afbeelding, (verw) = verwijder de oude versie, (rev) = breng oude versie terug.<br />
<i>Klik op de datum om de afbeeldingen die ge-upload zijn op die datum te zien</i>.",
"imagelinks" => "Afbeeldingsverwijzingen",
"linkstoimage" => "Deze afbeelding wordt gebruikt op de volgende pagina's:",
"nolinkstoimage" => "Geen enkele pagina gebruikt deze afbeelding.",

# Statistics
# Statistieken
"statistics" => "Statistieken",
"sitestats"  => "Statistieken betreffende Wikipedia LI",
"userstats"  => "Statistieken betreffende gebroekers",
"sitestatstext" => "Er zijn <b>$1</b> pagina's in de database. Hierbij zijn inbegrepen \"euverlik\" pagina's, pagina's over Wikipedia, extreem korte \"stub\" pagina's, redirects, en diverse andere pagina's die waarschijnlijk neet als artikel moeten worden geteld. Na uitsluiting daarvan, is er een geschat aantal van <b>$2</b> artikels.<p>
Er is in totaal $3 maal een pagina bekeken, en $4 maal een pagina bewerkt. Dat geeft een gemiddelde van $5 bewerkingen per pagina, en $6 paginabezoeken per wijziging.",
"userstatstext" => "Er zijn momenteel $1 geregistreerde gebroekers; hiervan zijn er $2 systeembeheerders (zie $3).",

# Maintenance Page
#
"maintenance"           => "Onderhoudspagina",
"maintnancepagetext"    => "Op deze pagina vindt u een aantal handige zoekopdrachten om kleine alledaagse problemen in de Wikipedia te verhelpen. Sommige van deze zoekopdrachten vormen een grote belasting voor de database; ga dus neet na elke paar pagina's die u hersteld heeft, de pagina opnieuw laden.",
"maintenancebacklink"   => "Terug naar de Onderhoudspagina",
"disambiguations"       => "Doorverwijspagina's",
"disambiguationspage"   => "Wikipedia:Doorverwijspagina",
"disambiguationstext"   => "De onderstaande artikelen verwijzen naar een [[Wikipedia:Doorverwijspagina|doorverwijspagina]]. Deze zouden waarschijnlijk direct naar de onderwerpspagina moeten verwijzen. <br />Als doorverwijspagina's worden die pagina's beschouwd waar vanaf $1 naar verwezen wordt.<br />Opmerking: Deze liest toont alleen pagina's vanuit de hoofdnaamruimte, en dus neet euverlikpagina's, Wikipedia:pagina's en dergelijke.",
"doubleredirects"       => "Dubbele redirects",
"doubleredirectstext"   => "<b>Let op:</b> Er kunnen in deze liest redirects staan die er neet in thuishoren. Dat komt over het algemeen doordat er na de #REDIRECT nog andere links op de pagina staan.<br />\nOp elke regel vindt u de eerste redirectpagina, de tweede redirectpagina en de eerste regel van de tweede redirectpagina. Normaal gesproken bevat deze laatste de pagina waar de eerste redirect naartoe zou moeten verwijzen.",
"brokenredirects"       => "Gebroken redirects",
"brokenredirectstext"   => "De onderstaande redirectpagina's bevatten een redirect naar een neet-bestaande pagina.",
"selflinks"             => "Pagina's die naar zichzelf verwijzen",
"selflinkstext"         => "De volgende pagina's verwijzen naar zichzelf, wat neet hoort te gebeuren.",
"mispeelings"           => "Pagina's met spelfouten",
"mispeelingstext"       => "De volgende pagina's bevatten een veel voorkomende spel- of typfout, die staat aangegeven op de liest in $1. Daar staat meestal ook (tussen haakjes) de juiste spelling.",
"mispeelingspage"       => "Veel voorkomende spelfouten",
"missinglanguagelinks"  => "Ontbrekende taallinks",
"missinglanguagelinksbutton"    => "Vind ontbrekende taallinks voor",
"missinglanguagelinkstext"      => "De onderstaande artikelen bevatten geen taallink naar een overeenkomende pagina in de taal \"$1\".",

# Miscellaneous special pages
# Diverse speciale pagina's
"orphans"  => "Weespagina's",
"lonelypages" => "Weespagina's",
"unusedimages" => "Ongebruikte afbeeldingen",
"popularpages" => "Populaire artikels",
"nviews"  => "$1 keer bekeken",
"wantedpages" => "Gewenste pagina's",
"nlinks"  => "$1 verwijzingen",
"allpages"  => "Alle pagina's",
"randompage" => "Willekeurig artikel",
"shortpages" => "Korte artikels",
"longpages"  => "Lange artikels",
"listusers"  => "liest van gebroekers",
"specialpages" => "Speciale pagina's",
"spheading"  => "Speciale pagina's",
"protectpage" => "Beveilig pagina",
"recentchangeslinked" => "Volg links",
"rclsub"  => "(van pagina's waarnaar \"$1\" verwijst)",
"debug"   => "Bugreparatie",
"newpages"  => "Nieuwe pagina's",
"movethispage" => "Verplaats deze pagina",
"unusedimagestext" => "<p>Let op! Het zou kunnen dat er via een directe link verwezen wordt naar een afbeelding, bijvoorbeeld vanuit een anderstalige Wikipedia. Het is daarom mogelijk dat een afbeelding hier vermeld staat terwijl het wel degelijk gebruikt wordt.",
"booksources" => "Boekwinkels",
"booksourcetext" => "Hieronder is een liest van externe websites die boeken verkopen en ook verdere informatie hierover kunnen verstekken. Via een ISBN-nummer in een artikel kan u via deze pagina een werk opzoeken. <p>Deze dienst is enkel ter uwer informatie. Wikipedia heeft <u>geen enkele</u> relatie met deze websites.",

# Email this user
# E-mail deze gebroeker
"mailnologin" => "Geen e-mailadres gekend voor deze gebroeker",
"mailnologintext" => "U dient [[Special:Userlogin|aangemeld te zijn]]
en een geldig e-mailadres in uw [[Special:Preferences|voorkeuren]]
to send e-mail to other users.",
"emailuser"  => "E-mail deze gebroeker",
"emailpage"  => "E-mail gebroeker",
"emailpagetext" => "Indien deze gebroeker een geldig e-mailadres heeft opgegeven dan kan u via dit formulier een bericht verzenden. Het e-mailadres dat u heeft opgegeven bij uw voorkeuren zal als afzender gebruikt worden.",
"noemailtitle" => "Geen e-mailadres gekend voor deze gebroeker",
"noemailtext" => "Deze gebroeker heeft geen geldig e-mailadres opgegeven of heeft deze functie uitgeschakelt.",
"emailfrom"  => "Van",
"emailto"  => "Aan",
"emailsubject" => "Onderwerp",
"emailmessage" => "Bericht",
"emailsend"  => "Verstuur bericht",
"emailsent"  => "E-mail versturen",
"emailsenttext" => "Uw bericht is verzonden.",

# Watchlist
# Volgliest
"watchlist"  => "Volgliest",
"watchlistsub" => "(van gebroeker \"$1\")",
"nowatchlist" => "Er staat neets op uw volgliest.",
"watchnologin" => "U bent neet aangemeld",
"watchnologintext" => "Om uw volgliest te veranderen dient u eerst [[Special:Userlogin|aangemeld]]
te zijn.",
"addedwatch" => "Toegevoegd aan volgliest",
"addedwatchtext" => "De pagina \"$1\" is aan uw [[Special:Watchlist|volgliest]] toegevoegd.
Toekomstige wijzigingen aan deze pagina en euverlikpagina zullen hier vermeld worden.
Ook zullen deze pagina's in het <b>vet</b> verschijnen in de [[Special:Recentchanges|liest van recente wijzigingen]] zodat u ze eenvoudiger kan opmerken.

Indien u een pagina wenst te verwijderen van uw volgliest klik dan op \"Van volgliest verwijderen\" in de menubalk.",
"removedwatch" => "Van volgliest verwijderen",
"removedwatchtext" => "De pagina \"$1\" is van uw volgliest verwijderd.",
"watchthispage" => "Volg deze pagina",
"unwatchthispage" => "Niet meer volgen",
"notanarticle" => "Is geen artikel",


# Delete/protect/revert
# Verwijderen/beschermen/annuleren
"deletepage" => "Pagina verwijderen",
"confirm"  => "Bevestig",
"confirmdelete" => "Bevestig verwijdering",
"deletesub"  => "(Verwijderen \"$1\")",
"confirmdeletetext" => "U bent staat op het punt een pagina of afbeelding voorgoed te verwijderen. Dit verwijdert alle inhoud en geschiedenis van de database. Bevestig hieronder dat dit inderdaad uw bedoeling is, dat u de gevolgen begrijpt, en dat uw verwijdering overeenkomt met de [[Wikipedia:Instructies]].",

"actioncomplete" => "Actie voltooid",
"deletedtext" => "\"$1\" is verwijderd. Zie $2 voor een overzicht van recente verwijderingen.",
"deletedarticle" => "\"$1\" is verwijderd",
"dellogpage" => "Logboek_verwijderde_pagina's", # This one needs the underscores!
"dellogpagetext" => "Hieronder ziet u een liest van de meest recentelijk verwijderde pagina's en afbeeldingen. Alle tieden zijn servertied, UTC-0.",
"deletionlog" => "Logboek verwijderde pagina's",
"reverted"  => "Eerdere versie hersteld",
"deletecomment" => "Reden voor verwijdering",
"imagereverted" => "De omzetting naar de oudere versie is geslaagd.",
"rollback"      => "Wijzigingen ongedaan maken",
"rollbacklink"  => "Terugdraaien",
"cantrollback"  => "Ongedaan maken van wijzigingen onmogelijk: Dit artikel heeft slechts 1 auteur.",
"revertpage"    => "Hersteld tot de versie na de laatste wijziging door $1.",

# Undelete
"undelete" => "Verwijderde pagina terugplaatsen",
"undeletepage" => "Verwijderde pagina's bekijken en terugplaatsen",
"undeletepagetext" => "De onderstaande pagina's zijn verwijderd, maar bevinden zich nog steeds in het archief, en kunnen teruggeplaatst worden.",
"undeletearticle" => "Verwijderde pagina terugplaatsen",
"undeleterevisions" => "$1 versies in het archief",
"undeletehistory" => "Als u een pagina terugplaatst, worden alle versies als oude versies teruggeplaatst. Als er al een nieuwe pagina met dezelfde naam is aangemaakt, zullen deze versies als oude versies worden teruggeplaatst, maar de huidige versie neet gewijzigd worden.",
"undeleterevision" => "Verwijderde versie van $1",
"undeletebtn" => "Terugplaatsen!",
"undeletedarticle" => "\"$1\" is teruggeplaatst.",
"undeletedtext" =>"Het artikel [[$1]] is teruggeplaatst. Zie [[Wikipedia:Logboek verwijderde pagina's]] voor een liest van de meest recente verwijderingen en terugplaatsingen.",

# Contributions
# Bijdragen
"contributions" => "Bijdragen per gebroeker",
"contribsub" => "Veur $1",
"nocontribs" => "Gein wijzigingen gevonden die aan de gestelde criteria voldoen.",
"ucnote"  => "Hieonger zeet ger de litste <b>$1</b> wijzigingen van deze gebroeker in de laatste <b>$2</b> dagen.",
"uclinks"  => "Bekijk de laatste <b>$1</b> veranderingen; bekijk de laatste <b>$2</b> dagen.",
"uctop" => " (litste wijziging)",

# What links here
# Wat linkt hier
"whatlinkshere" => "Links noa deze pagina",
"notargettitle" => "Gein doelpagina",
"notargettext" => "Ger hubt neet gezag veur welleke pagina ger deze functie wilt bekieke.",
"linklistsub" => "(liest van verwiezingen)",
"linkshere"  => "De volgende pagina's verwiezen hienoatoe:",
"nolinkshere" => "Gin enkele pagina verwiest hieheen.",
"isredirect" => "redirect pagina",

# Block/unblock IP
#
"blockip"  => "Blokkier IP-adres",
"blockiptext" => "Gebroek het onderstaande formulier om schrijftoegang van een bepaald IP-adres te verbieden. Dit mag enkel gedaan worden om vandalisme te voorkomen en moet in overeenstemming zijn met de [[Wikipedia:spelregels|spelregels]] van Wikipedia. Vul een specifieke reden in.",
"ipaddress"  => "IP-adres",
"ipbreason"  => "Reden",
"ipbsubmit"  => "Blokkier dit IP-adres",
"badipaddress" => "Het IP-adres heeft een ongeldige opmaak.",
"blockipsuccesssub" => "Blokkering gelukt",
"blockipsuccesstext" => "Het IP-adres \"$1\" is geBlokkierd.<br />
Zie de [[Special:Ipblocklist|liest van geBlokkierde IP-adressen]].",
"unblockip"  => "De-Blokkier IP-adres",
"unblockiptext" => "Gebroek het onderstaande formulier om terug schrijftoegang te geven aan een geBlokkierd IP-adres.",
"ipusubmit"  => "De-Blokkier dit IP-adres.",
"ipusuccess" => "Het IP-adres \"$1\" is gedeBlokkierd.",
"ipblocklist" => "Liest van geblokkierde IP-adressen.",
"blocklistline" => "Op $1 blokkierde $2 ut adres $3 ($4)",
"blocklink"  => "Blokkier",
"unblocklink" => "de-Blokkier",
"contribslink" => "bijdragen",

# Developer tools
# Ontwikkelingsgereedsschap
"lockdb"  => "Blokkier de database",
"unlockdb"  => "De-Blokkier de database",
"lockdbtext" => "Waarschuwing: De database blokkeren heeft tot gevolg dat geen enkele gebroeker meer in staat is de pagina's te bewerken, hun voorkeuren te wijzigen of iets anders te doen waarvoor er wijzigingen in de database nodig zijn.",
"unlockdbtext" => "Het de-blokkeren van de database zal de gebroekers de mogelijkheid geven om wijzigingen aan pagina's op te slaan, hun voorkeuren te wijzigen en alle andere bewerkingen waarvoor er wijzigingen in de database nodig zijn. Is dit inderdaad wat u wilt doen?.",
"lockconfirm" => "Ja, ik wil de database blokkeren.",
"unlockconfirm" => "Ja, ik wil de database de-blokkeren.",
"lockbtn"  => "Blokkier de database",
"unlockbtn"  => "De-Blokkier de database",
"locknoconfirm" => "U heeft neet het vakje aangevinkt om uw keuze te bevestigen.",
"lockdbsuccesssub" => "Blokkering database succesvol",
"unlockdbsuccesssub" => "Blokkering van de database opgeheven",
"lockdbsuccesstext" => "De database van Wikipedia LI is geblokkierd.
Vergeet neet de database opnieuw te de-blokkeren zodra u klaar bent met uw onderhoud.",
"unlockdbsuccesstext" => "Blokkering van de database van Wikipedia LI is opgeheven.",

# Move page
# Verplaats pagina
"movepage"  => "Verplaats pagina",
"movepagetext" => "Door middel van het onderstaande formulier kan u de titel van een pagina hernoemen. De voorgeschiedenis van de oude pagina zal deze van de nieuwe worden. De oude titel zal automatisch een doorverwijzing worden naar de nieuwe. U kunt een dergelijke hernoeming alleen doen plaatsvinden, als er geen pagina bestaat met de nieuwe naam, of als er slechts een redirect zonder verdere geschiedenis is.",
"movepagetalktext" => "De biebeheurende euverlikpagina weurt auch verplaatsjt, mer '''neet''' in de volgende gevallen:
* Als de pagina naar een andere naamruimte wordt verplaatst
* Als er al een neet-lege euverlikpagina bestaat onder de andere naam
* Als u de onderstaande radiobox neet aangevinkt laat",
"movearticle" => "Verplaatsj pagina",
"movenologin" => "Neet aangemeld",
"movenologintext" => "U dient [[Special:Userlogin|aangemeld]]
te zijn om een pagina te verplaatsen.",
"newtitle"  => "Noa de nuuje titel",
"movepagebtn" => "Verplaatsj pagina",
"pagemovedsub" => "De verplaatsjing is gelukt",
"pagemovedtext" => "Pagina \"[[$1]]\" verplaatst naar \"[[$2]]\".",
"articleexists" => "D'r is al un pagina mit deze titel of de titel is ongeldig. <br />Gelieve een andere titel te kiezen.",
"talkexists" => "De pagina zelf is verplaatst, maar de euverlikpagina kon neet worden verplaatst, omdat de doeltitel al een neet-lege euverlikpagina had. Combineer de euverlikpagina's a.u.b. handmatig.",
"movedto"  => "verplaatsjt noa",
"movetalk"  => "Verplaatsj \"euverlik\" pagina auch indien aanwezig.",
"talkpagemoved" => "De bieheurende euverlikpagina is auch verplaatsjt.",
"talkpagenotmoved" => "De bijheurende euverlikpagina is <strong>neet</strong> verplaatsjt.",


# Update from wikipedia
'accmailtext' => "'t Wachwaord veur '$1' is nao $2 versjtuurd.",
'accmailtitle' => "Wachwaord versjtuurd.",
'acct_creation_throttle_hit' => "Sorry, de höbs al $1 accounts aangemak. De kins d'r gein mië aanmake.",
'addgroup' => "Groep toevoge",
'allarticles' => "Alle artikele",
'alllogstext' => "Gecombinierde wiergaaf van uploads, verwiederinge, besjerminge, blokkieringe, en sysop logs.
De kins ouch 'n log type, de gebroekersname of de betriffende pazjena selectiere.",
'allmessages' => "Alle systeemberichte",
'allmessagescurrent' => "Huidige tèks",
'allmessagesdefault' => "Default tèks",
'allmessagesname' => "Naom",
'allmessagesnotsupportedDB' => "Special:AllMessages neet ongersjteund omdat wgUseDatabaseMessages oet (off) sjtèt.",
'allmessagesnotsupportedUI' => "Dien huidige interface taol <b>$1</b> weurt bie dees site neet ongerstjeund doer Special:AllMessages.",
'allmessagestext' => "Dit is 'n liest van alle systeemberichte besjikbaar in de MediaWiki: naomroemde.",
'allpagesnext' => "Volgende",
'allpagesprev' => "Vurrige",
'alphaindexline' => "$1 nao $2",
'ancientpages' => "Awdste pazjena's",
'and' => "en",
'anontalk' => "Euverlik veur dit IP adres",
'anontalkpagetext' => "----''Dit is de euverlikpazjena veur 'ne anonieme gebroeker dae nog gein account haet aangemak of dae 't neet gebroek. Daorom gebroeke v'r 't [[IP adres]] om de gebroeker te identificere. Dat adres kint weure gedeild doer miedere gebroekers. As e 'ne anonieme gebroeker bis en de höbs 't geveul dat 'r onrillevante commentare aan dich gericht zint, kins e 't biste [[Special:Userlogin|'n account crëere of inlogge]] om toekomstige verwarring mit angere anonieme gebroekers te veurkomme.''",
'anonymous' => "Anonieme gebroeker(s) van Wikipedia",
'article' => "Contentpazjena",
'autoblocker' => "Omdas e 'n IP addres deils mit \"$1\" (rede \"$2\") bis e automatisch geblokkierd.",
'blocklogentry' => "\"$1\" geblokkierd veur de tied van $2",
'blocklogpage' => "Blokkier log",
'blocklogtext' => "Dit is 'n log van 't blokkieringe van gebroekers. Automatisch geblokkierde IP adresse sjtón hie neet bie. Zuug de [[Special:Ipblocklist|IP blokkier liest]] vuur de liest van op dit mement akteve blokkieringe.",
'bold_sample' => "Vètte tèks",
'bold_tip' => "Vètte tèks",
'bureaucratlogentry' => "Rechte vuur gebroeker \"$1\" op \"$2\" gesjtèld",
'cachederror' => "Dit is 'n gecachete kopie van de gevraogde pazjena, en is mesjien neet gans up to date.",
'categories' => "Kattegorië",
'category' => "Kattegorie",
'category_header' => "Artikele in kategorie \"$1\"",
'categoryarticlecount' => "D'r zunt $1 artikele in dees kategorie.",
'categoryarticlecount1' => "D'r is $1 artikel in dees kategorie.",
'changes' => "verangeringe",
'clearyourcache' => "'''Mèrk op:''' Nao 't opsjlaon, kins e diene browsercache wisse om de verangeringe te zeen: '''Mozilla / Firefox:''' ''Ctrl-Shift-R'', '''Internet Explorer:''' ''Ctrl-F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror''' ''F5''.",
'compareselectedversions' => "Vergeliek geselecteerde versies",
'confirmemail_body' => "Someone, probably you from IP address $1, has registered an
account \"$2\" with this e-mail address on {{SITENAME}}.

To confirm that this account really does belong to you and activate
e-mail features on {{SITENAME}}, open this link in your browser:

$3

If this is *not* you, don't follow the link. This confirmation code
will expire at $4.",
'confirmprotect' => "Bevèstig besjerme",
'confirmprotecttext' => "Wits e zeker das e dees pazjena wils besjerme?",
'confirmunprotect' => "Bevèstig vriegaeve van besjèrming",
'confirmunprotecttext' => "Wits e zeker das de besjèrming van dees pazjena wils vriegaeve?",
'copyright' => "De inhawd is besjikbaar onger de $1.",
'copyrightwarning2' => "Mèrk op dat alle biedraag aan {{SITENAME}} kinne weure verangerd, aangepas of weggehaold doer anger luui. As e neet wils dat dien tèks zoemer kint weure aangepas mos e 't hie neet plaatsje.<br />
De beluifs os auch das e dees tèks zelf höbs gesjreve, of gekopieerd van 'n [[public domain]] of vergeliekbare vrieë bron (zuug $1 vuur details).
<strong>HIE GEIN AUTEURSRECHTELIK BESJERMD WERK ZONGER TOESJTUMMING!</strong>",
'couldntremove' => "Kos item '$1' neet wisse...",
'createaccountmail' => "per e-mail",
'creditspage' => "Sjrievers van dees pazjena",
'currentevents-url' => "In 't nuuis",
'currentrevisionlink' => "zuug huidige versie",
'dateformat' => "Datumformaat",
'deadendpages' => "Doedlaupende pazjena's",
'default' => "sjtandaard",
'defaultns' => "Zeuk sjtandaard in dees naomroemdes:",
'delete' => "Wisse",
'deletedrevision' => "Aw versie $1 gewist.",
'deleteimgcompletely' => "Wis al versies",
'edit' => "Bewirk",
'editcomment' => "'t Edit commentaor waor: \"<i>$1</i>\".",
'editingcomment' => "Bewirk $1 (commentaar)",
'editingsection' => "Bewirke van sectie van $1",
'editsection' => "bewirk",
'emptyfile' => "'t Besjtand das e höbs geupload is laeg. Dit kump waorsjienliek doer 'n tiepfout in de besjtandsnaom. Kiek estebleef of se dit besjtand wirkelik wils uploade.",
'exbeforeblank' => "inhawd veur 't wisse waor:",
'exblank' => "pazjena waor laeg",
'excontent' => "inhawd waor:",
'export' => "Exportier pazjena's",
'extlink_sample' => "http://www.example.com link titel",
'extlink_tip' => "Externe link (mit de http:// prefix)",
'fileexists' => "D'r is al e besjtand mit dees naam, bekiek $1 of se dat besjtand mesjien wils vervange.",
'filemissing' => "Besjtand ontbrik",
'filesource' => "Bron",
'geo' => "GEO coördinate",
'groups-editgroup-name' => "Group name:",
'groups-group-edit' => "Existing groups:",
'guesstimezone' => "Invulle van browser",
'hidetoc' => "verberg",
'history_short' => "Historie",
'imagemaxsize' => "Bepèrk plaetsjes op de besjrievingspazjena's van aafbeildinge tot:",
'ipboptions' => "2 hours,1 day,3 days,1 week,2 weeks,1 month,3 months,6 months,1 year,infinite",
'italic_sample' => "Italic tèks",
'italic_tip' => "Italic tèks",
'lastmodifiedby' => "Dees pazjena is 't litst verangert op $1 doer $2.",
'link_sample' => "Link titel",
'link_tip' => "Interne link",
'loginreqtext' => "De mos [[special:Userlogin|inglogge]] om anger pazjenas te bekieke.",
'longpagewarning' => "WAARSJUWING: Dees pazjena is $1 kilobytes lang; 'n aantal browsers kint probleme höbbe mit 't verangere van pazjena's in de buurt van of grotter as 32 kB. Kiek of se sjtukke van de pazjena mesjiens kins verplaatsje nao 'n nuui pazjena.",
'mailerror' => "Fout bie 't versjture van mail: $1",
'mainpagetext' => "Wiki software succesvol geïnsjtalleerd.",
'math' => "Mattemetik rendere",
'math_bad_output' => "Kin neet sjrieve nao de output directory veur mattematik",
'math_tip' => "Wiskundige formule (LaTeX)",
'math_unknown_error' => "onbekènde fout",
'math_unknown_function' => "onbekènde functie",
'missingimage' => "<b>Plaetsje neet besjikbaar</b><br /><i>$1</i>",
'moredotdotdot' => "Miè...",
'move' => "Verplaatsj",
'mw_math_html' => "HTML woe meugelik en angesj PNG",
'mw_math_mathml' => "MathML woe meugelik (experimenteil)",
'mw_math_modern' => "Aangeroaje vuur nuui browsers",
'mw_math_png' => "Ummer PNG rendere",
'mw_math_simple' => "HTML in erg simpele gevalle en angesj PNG",
'mw_math_source' => "Laot de TeX code sjtaon (vuur tèksbrowsers)",
'mycontris' => "Mien biedraag",
'namespace' => "Naamruumde:",
'navigation' => "Navegatie",
'newimages' => "Nuui plaetjes",
'newmessages' => "De höbs $1.",
'newmessageslink' => "nuui berichte",
'newpage' => "Nuui pazjena",
'newwindow' => "(in nuui venster)",
'nextdiff' => "Gank nao de volgende diff →",
'nextpage' => "Volgende pazjena ($1)",
'nogomatch' => "Inne pazjena mit exak dees titel besjtit neet, probeer noe alle teks doer te zeuke.",
'noimages' => "Niks te zeen.",
'nonunicodebrowser' => "<strong>WAARSJUWING: Diene browser is voldit neet aan de unicode sjtandaarde, gebroek estebleef inne angere browser veurdas e artikele gis bewirke.</strong>",
'nstab-category' => "Kategorie",
'nstab-image' => "Aafbeilding",
'nstab-main' => "Artikel",
'nstab-mediawiki' => "Berich",
'nstab-template' => "Sjabloon",
'nstab-user' => "Gebroeker",
'nstab-wp' => "Euver",
'perfcached' => "De volgende data is gecachet en is mesjien neet gans up to date:",
'perfdisabledsub' => "Hie is 'n opgesjlage kopie van $1:",
'personaltools' => "Persoenlike hulpmiddele",
'portal' => "Gebroekersportaal",
'portal-url' => "Wikipedia:Gebroekersportaal",
'poweredby' => "{{SITENAME}} dreit op [http://www.mediawiki.org/ MediaWiki], 'n vriej software wiki.",
'prefs-misc' => "Anger insjtèllinge",
'prefs-personal' => "Gebroekersinfo",
'prefs-rc' => "Recènte verangeringe en weergaaf van sjtumpkes",
'previousdiff' => "← Gank nao de vurrige diff",
'previousrevision' => "←Awwer versie",
'protect' => "Besjerm",
'protectcomment' => "Rede veur besjerming",
'protectedarticle' => "$1 besjermd",
'protectedpagewarning' => "WAARSJUWING:  Dees pazjena is besjermd zoedat ze allein doer gebroekers mit administratorrechte kint weure verangerd.",
'protectedtext' => "Dizze pazjena is besjermd om bewirkinge te veurkomme; d'r zint 'n aantal meugelike redene hieveur.
<!-- Zuug ouch [[Project:Protected page]]. -->

De kins de brontèks van dees pazjena bekieke en kopiëre:",
'protectmoveonly' => "Besjerm allein taege verplaatsje",
'protectsub' => "(Besjerme van \"$1\")",
'proxyblockreason' => "Dien IP adres is geblokkierd omdat 't 'n ôpe proxy is. Contactier estebleef diene internet service provider of technische ongersjteuning en informeer ze van dit sirrieuze veiligheidsprebleim.",
'proxyblocksuccess' => "Klaor.",
'qbspecialpages' => "Speciaal pazjena's",
'readonlywarning' => "WAARSJUWING: De database is vasgezèt veur ongerhoud, dus op 't mement kins e dien verangeringe neet opsjlaon. De kins dien tèks 't biste opsjlaon in 'n tèksbesjtand om 't later hie nog es te prebere.",
'removechecked' => "Verwieder aangevinkde pazjena's van dien volgliest",
'removingchecked' => "Pazjena's van volgliest aafgehaold...",
'restorelink' => "$1 verwiederde versies",
'scarytranscludefailed' => "[Template fetch failed; sorry]",
'showbigimage' => "Download versie met hoege resolutie ($1x$2, $3 kB)",
'showhideminor' => "$1 klein verangeringe | $2 bots | $3 ingelogde gebroekers | $4 patrolled edits",
'showingresultsnum' => "Hieonger de <b>$3</b> resultate vanaaf #<b>$2</b>.",
'siteuser' => "Wikipedia gebroeker $1",
'siteusers' => "Wikipedia gebroekers(s) $1",
'specialpage' => "Speciaal Pazjena",
'subcategories' => "Subkattegorië",
'subcategorycount' => "Dees kategorie haet $1 subkategorieë.",
'subcategorycount1' => "Dees kategorie haet $1 subkategorie.",
'tagline' => "Van {{SITENAME}}",
'talk' => "Euverlik",
'templatesused' => "Sjablone gebroek in dees pazjena:",
'thisisdeleted' => "$1 bekieke of trökzètte?",
'thumbnail-more' => "Vergroete",
'thumbsize' => "Thumbnail size :",
'timezonelegend' => "Tiedzone",
'toc' => "Inhawd",
'tog-editondblclick' => "Bewirk pazjena's bie 'ne dubbelklik (JavaScript)",
'tog-editsection' => "Bewirke van secties via [bewirke] links",
'tog-editsectiononrightclick' => "Sècties bewirke mit inne rechtermoesklik op sèctietitels (JavaScript)",
'tog-editwidth' => "Edit boks haet de vol breidte",
'tog-fancysig' => "Rauwe signature (zonger automatische link)",
'tog-hideminor' => "Verbèrg klein bewirking bie recènte verangeringe",
'tog-highlightbroken' => 'Formatteer gebraoke links <a href="" class="new">op dees meneer</a> (angesj: zoe<a href="" class="internal">?</a>).',
'tog-justify' => "Paragrafe oetvulle",
'tog-minordefault' => "Merkeer sjtandaard alle bewirke as klein",
'tog-nocache' => "Pazjena cache oetzitte",
'tog-numberheadings' => "Köpkes automatisch nummere",
'tog-previewonfirst' => "Preview laote zien bie de iesjte bewirking",
'tog-rememberpassword' => "Paswoerd onthawwe bie 't aafmèlde",
'tog-showtoc' => "Inhawdsopgaaf vuur pazjena's mit mië as 3 köpkes",
'tog-showtoolbar' => "Laot edit toolbar zeen",
'tog-underline' => "Links ongersjtreipe",
'tog-usenewrc' => "Oetgebreide recènte vervangeringe (neet vuur alle browsers)",
'tog-watchdefault' => "Voog pazjena's die se bewirks toe aan dien volgliest",
'toolbox' => "Gereidsjapskis",
'tooltip-compareselectedversions' => "Bekiek de versjille tusje de twie geselectierde versies van dees pazjena. [alt-v]",
'tooltip-minoredit' => "Markeer dit as 'n kleine verangering [alt-i]",
'tooltip-preview' => "Bekiek dien verangeringe verdas e ze definnetief opsjleus! [alt-p]",
'tooltip-save' => "Bewaar dien verangeringe [alt-s]",
'tooltip-search' => "Doerzeuk dizze wiki [alt-f]",
'tooltip-watch' => "Voog dees pazjena toe aan dien volgliest [alt-w]",
'uncategorizedcategories' => "Ongekattegoriseerde kattegorië",
'uncategorizedpages' => "Ongekattegoriseerde pazjena's",
'unprotect' => "Besjerming opheffe",
'unprotectcomment' => "Rede veur opheffe van besjerming",
'unprotectedarticle' => "besjerming van $1 opgeheve",
'unprotectsub' => "(Besjerming van \"$1\" opheve)",
'unwatch' => "Sjtop volge",
'usenewcategorypage' => '1

Set first character to "0" to disable the nuui layout veur kategorieë.',
'userrights-user-editname' => 'Enter a username:',

'val_tab' => "Valideer",
'val_this_is_current_version' => "dit is de litste versie",
'val_total' => "Totaal",
'val_user_validations' => "Deze gebroeker haet $1 pazjena's gevalideerd.",
'val_validate_article_namespace_only' => "Allein artikele kinne weure gevalideerd. Dees pazjena bevink zich <i>neet</i> in de artikel naomroemde.",
'val_validate_version' => "Valideer dees versie",
'val_validated' => "Gevalideerd.",
'val_version' => "Versie",
'val_version_of' => "Versie van $1",
'val_view_version' => "Bekiek dees versie",
'validate' => "Valideer pazjena",
'version' => "Versie",
'viewsource' => "Bekiek brontèks",
'viewtalkpage' => "Bekiek euverlik",
'watch' => "Volg",
'watchlistcontains' => "Diene volgliest bevat $1 pazjena's.",
'watchmethod-list' => "controlere van gevolgde pazjena's veur recènte verangeringe",
'watchmethod-recent' => "Controleer recènte verangere veur gevolgde pazjena's",
'watchnochange' => "Gein van dien gevolgde items is aangepas in dees periode.",
'wlnote' => "Hieonger de litste $1 verangeringe van de litste <b>$2</b> oer.",
'wlsaved' => "Dit is 'n opgesjlage versie van dien volgliest.",
'wlshowlast' => "Toen litste $1 oere $2 daag $3",
'yourlanguage' => "Taol van de gebroekersinterface",
'yourrealname' => "Dien echte naam*",
'yourvariant' => "Taalvariant",
);

require_once( "LanguageUtf8.php" );

class LanguageLi extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesLi;
		return $wgNamespaceNamesLi;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsLi;
		return $wgQuickbarSettingsLi;
	}

	function getSkinNames() {
		global $wgSkinNamesLi;
		return $wgSkinNamesLi;
	}

	function date( $ts, $adj = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = (0 + substr( $ts, 6, 2 )) . " " .
		$this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) . " " .
		substr( $ts, 0, 4 );
		return $d;
	}

	function timeanddate( $ts, $adj = false ) {
		return $this->date( $ts, $adj ) . " " . $this->time( $ts, $adj );
	}

	function getMessage( $key ) {
		global $wgAllMessagesLi;
		if( isset( $wgAllMessagesLi[$key] ) ) {
			return $wgAllMessagesLi[$key];
		} else {
			return parent::getMessage( $key );
		}
	}
}
?>

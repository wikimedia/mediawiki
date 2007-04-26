<?php
/** Danish (Dansk)
  *
  * @addtogroup Language
 */

$namespaceNames = array(
	NS_MEDIA			=> 'Media',
	NS_SPECIAL			=> 'Speciel',
	NS_MAIN				=> '',
	NS_TALK				=> 'Diskussion',
	NS_USER				=> 'Bruger',
	NS_USER_TALK		=> 'Brugerdiskussion',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK		=> '$1-diskussion',
	NS_IMAGE			=> 'Billede',
	NS_IMAGE_TALK		=> 'Billeddiskussion',
	NS_MEDIAWIKI		=> 'MediaWiki',
	NS_MEDIAWIKI_TALK	=> 'MediaWiki-diskussion',
	NS_TEMPLATE  		=> 'Skabelon',
	NS_TEMPLATE_TALK	=> 'Skabelondiskussion',
	NS_HELP				=> 'Hjælp',
	NS_HELP_TALK		=> 'Hjælp-diskussion',
	NS_CATEGORY			=> 'Kategori',
	NS_CATEGORY_TALK	=> 'Kategoridiskussion'

);

$skinNames = array(
	'standard' => 'Klassisk',
	'nostalgia' => 'Nostalgi',
	'cologneblue' => 'Cologne-blå',
);

$datePreferences = false;
$defaultDateFormat = 'dmy';
$dateFormats = array(
	'dmy time' => 'H:i',
	'dmy date' => 'j. M Y',
	'dmy both' => 'j. M Y "kl." H:i',
);

$bookstoreList = array(
	"Bibliotek.dk" => "http://bibliotek.dk/vis.php?base=dfa&origin=kommando&field1=ccl&term1=is=$1&element=L&start=1&step=10",
	"Bogguide.dk" => "http://www.bogguide.dk/find_boeger_bog.asp?ISBN=$1",
	'inherit' => true,
);

$separatorTransformTable = array(',' => '.', '.' => ',' );
$linkTrail = '/^([a-zæøå]+)(.*)$/sDu';

#-------------------------------------------------------------------
# Default messages
#-------------------------------------------------------------------

$messages = array(
# User preference toggles
"tog-underline" => "Understreg henvisninger",
"tog-highlightbroken" => "Brug røde henvisninger til tomme sider",
"tog-justify"	=> "Justér afsnit",
"tog-hideminor" => "Skjul mindre ændringer i seneste ændringer listen",
"tog-extendwatchlist" => "Udvidet liste med seneste ændringer",
"tog-usenewrc" => "Udvidet seneste ændringer liste<br />(ikke for alle browsere)",
"tog-numberheadings" => "Automatisk nummerering af overskrifter",
"tog-showtoolbar" => "Vis værktøjslinje til redigering",
"tog-editondblclick" => "Redigér sider med dobbeltklik (JavaScript)",
"tog-editsection"=>"Redigér afsnit ved hjælp af [redigér]-henvisning",
"tog-editsectiononrightclick"=>"Redigér afsnit ved at højreklikke<br /> på afsnittets titel (JavaScript)",
"tog-showtoc"=>"Vis indholdsfortegnelse<br />(for artikler med mere end tre afsnit)",
"tog-rememberpassword" => "Husk adgangskode til næste besøg",
"tog-editwidth" => "Redigeringsboksen har fuld bredde",
"tog-watchdefault" => "Overvåg nye og ændrede artikler",
"tog-minordefault" => "Markér som standard alle ændringer som mindre",
"tog-previewontop" => "Vis forhåndsvisning før redigeringsboksen",
'tog-previewonfirst' => 'Vis forhåndsvisning når du starter med at redigere',
"tog-nocache" => "Husk ikke siderne til næste besøg",
'tog-enotifwatchlistpages' 	=> 'Send mig en e-mail med sideændringer (bemærk: eksisterende beskedmarkeringer skal fjernes manuelt i overvågningslisten)',
'tog-enotifusertalkpages' 	=> 'Send mig en e-mail når min brugerdiskussionsside ændres (bemærk: eksisterende beskedmarkeringer skal fjernes manuelt i overvågningslisten)',
'tog-enotifminoredits' 		=> 'Send mig også en e-mail for mindre ændringer af sider (der normalt ikke udløser mails med besked om ændringer)',
'tog-enotifrevealaddr' 		=> 'Offentliggør min e-mail-adresse i mails med besked om ændringer (når jeg ændrer en side kan brugere, der overvåger siden, hurtigt komme i kontakt med mig)',
'tog-shownumberswatching' 	=> 'Vis antal brugere, der overvåger (i seneste ændringer-visning, overvågningslisten og i bunden af artikelsider)',
'tog-fancysig' => 'Signaturer uden automatisk link',
'tog-externaleditor' => 'Brug ekstern editor automatisk',
'tog-externaldiff' => 'Brug ekstern forskelsvisning automatisk',

# dates
'sunday' => 'søndag',
'monday' => 'mandag',
'tuesday' => 'tirsdag',
'wednesday' => 'onsdag',
'thursday' => 'torsdag',
'friday' => 'fredag',
'saturday' => 'lørdag',
'january' => 'januar',
'february' => 'februar',
'march' => 'marts',
'april' => 'april',
'may_long' => 'maj',
'june' => 'juni',
'july' => 'juli',
'august' => 'august',
'september' => 'september',
'october' => 'oktober',
'november' => 'november',
'december' => 'december',
'jan' => 'jan',
'feb' => 'feb',
'mar' => 'mar',
'apr' => 'apr',
'may' => 'maj',
'jun' => 'jun',
'jul' => 'jul',
'aug' => 'aug',
'sep' => 'sep',
'oct' => 'okt',
'nov' => 'nov',
'dec' => 'dec',

# Bits of text used by many pages:
#
"categories" => "Kategorier",
"pagecategories" => "Kategorier",
"category_header" => 'Artikler i kategorien "$1"',
"subcategories" => "Underkategorier",

"mainpage"		=> "Forside",
"mainpagetext"	=> "MediaWiki er nu installeret.",
"mainpagedocfooter" => "Se vores engelsksprogede [http://meta.wikimedia.org/wiki/MediaWiki_i18n dokumentation om tilpasning af brugergrænsefladen]
og [http://meta.wikimedia.org/wiki/MediaWiki_User%27s_Guide brugervejledningen] for oplysninger om opsætning og anvendelse.",

'portal'		=> 'Forside for skribenter',
'portal-url'		=> '{{ns:4}}:Forside',
"about"			=> "Om",
"aboutsite"      => "Om {{SITENAME}}",
"aboutpage"		=> "{{ns:4}}:Om",
'article' => 'Artikel',
"help"			=> "Hjælp",
"helppage"		=> "{{ns:4}}:Hjælp",
"bugreports"	=> "Fejlrapporter",
"bugreportspage" => "{{ns:4}}:Fejlrapporter",
"sitesupport"   => "Donation",
'sitesupport-url' => '{{ns:4}}:Donation',
"faq"			=> "OSS",
"faqpage"		=> "{{ns:4}}:OSS",
"edithelp"		=> "Hjælp til redigering",
"newwindow"		=> "(åbner i et nyt vindue)",
"edithelppage"	=> "{{ns:4}}:Hvordan redigerer jeg en side",
"cancel"		=> "Afbryd",
"qbfind"		=> "Find",
"qbbrowse"		=> "Gennemse",
"qbedit"		=> "Redigér",
"qbpageoptions" => "Indstillinger for side",
"qbpageinfo"	=> "Information om side",
"qbmyoptions"	=> "Mine indstillinger",
'qbspecialpages'	=> 'Specielle sider',
'moredotdotdot'	=> 'Mere...',
"mypage"		=> "Min side",
"mytalk"		=> "Min diskussion",
'anontalk'		=> 'Diskussionsside for denne IP-adresse',
'navigation' => 'Navigation',

# Metadata in edit box
'metadata' => '<b>Metadata</b> (for en foklaring se <a href="$1">her</a>)',

"currentevents" => "Aktuelle begivenheder",
'currentevents-url' => 'Aktuelle begivenheder',

'disclaimers' => 'Forbehold',
"disclaimerpage" => "{{ns:4}}:Generelle forbehold",
"errorpagetitle" => "Fejl",
"returnto"		=> "Tilbage til $1.",
"whatlinkshere"	=> "Hvad henviser hertil",
"help"			=> "Hjælp",
"search"		=> "Søg",
"searchbutton"	=> "Søg",
"go"		=> "Gå til",
'searcharticle'		=> "Gå til",
"history"		=> "Historik",
'history_short' => 'Historik',
'info_short'	=> 'Information',
"printableversion" => "Udskriftsvenlig udgave",
'edit' => 'Redigér',
"editthispage"	=> "Redigér side",
'delete' => 'Slet',
"deletethispage" => "Slet side",
"undelete_short" => "Fortryd sletning af $1 versioner",
'protect' => 'Beskyt',
"protectthispage" => "Beskyt side",
'unprotect' => 'Fjern beskyttelse',
"unprotectthispage" => "Fjern beskyttelse af side",
"newpage" => "Ny side",
"talkpage"		=> "Diskussionssiden",
'specialpage' => 'Speciel side',
'personaltools' => 'Personlige værktøjer',
"postcomment"   => "Tilføj en kommentar",
"articlepage"	=> "Se artiklen",
'talk' => 'Diskussion',
'toolbox' => 'Værktøjer',
"userpage" => "Se brugersiden",
"projectpage" => "Se metasiden",
"imagepage" => 	"Se billedsiden",
"viewtalkpage" => "Se diskussion",
"otherlanguages" => "Andre sprog",
"redirectedfrom" => "(Omdirigeret fra $1)",
"lastmodifiedat"	=> "Denne side blev senest ændret den $2, $1.",
"viewcount"		=> "Siden er vist i alt $1 gange.",
'copyright'	=> 'Indholdet&nbsp;er&nbsp;udgivet&nbsp;under&nbsp;$1.',
"protectedpage" => "Beskyttet side",
'badaccess'     => 'Permission error',

'nbytes'		=> '$1 bytes',
"go"			=> "Gå til",
'searcharticle'			=> "Gå til",
"ok"			=> "OK",
"retrievedfrom" => "Hentet fra \"$1\"",
"newmessageslink" => "nye beskeder",
"editsection"=>"redigér",
"editold"=>"redigér",
"toc" => "Indholdsfortegnelse",
"showtoc" => "vis",
"hidetoc" => "skjul",
"thisisdeleted" => "Se eller gendan $1?",
"restorelink" => "$1 slettede ændringer",
'feedlinks' => 'Feed:',
'tagline' => 'Fra {{SITENAME}}',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main' => 'Artikel',
'nstab-user' => 'Brugerside',
'nstab-media' => 'Medie',
'nstab-special' => 'Speciel',
'nstab-project' => 'Om',
'nstab-image' => 'Billede',
'nstab-mediawiki' => 'Besked',
'nstab-template' => 'Skabelon',
'nstab-help' => 'Hjælp',
'nstab-category' => 'Kategori',

# Main script and global functions
#
"nosuchaction"	=> "Funktionen findes ikke",
"nosuchactiontext" => "Funktion angivet i URL'en kan ikke
genkendes af MediaWiki-softwaren",
"nosuchspecialpage" => "En sådan specialside findes ikke",
"nospecialpagetext" => "Du har bedt om en specialside, der ikke kan
genkendes af MediaWiki-softwaren.",

# General errors
#
"error"			=> "Fejl",
"databaseerror" => "Databasefejl",
"dberrortext"	=> "Der er opstået en syntaksfejl i en databaseforespørgsel.
Dette kan være på grund af en ugyldig forespørgsel (se $5),
eller det kan betyde en fejl i softwaren.
Den seneste forsøgte databaseforespørgsel var:
<blockquote><tt>$1</tt></blockquote>
fra funktionen \"<tt>$2</tt>\".
MySQL returnerede fejlen \"<tt>$3: $4</tt>\".",
"dberrortextcl" => "Der er opstået en syntaksfejl i en databaseforespørgsel.
Den seneste forsøgte databaseforespørgsel var:
\"$1\"
fra funktionen \"$2\".
MySQL returnerede fejlen \"$3: $4\".",
'noconnect'		=> 'Der er problemer med {{SITENAME}}s database, vi kan desværre ikke komme i kontakt med den for øjeblikket. Prøv igen senere. <br />
$1',
"nodb"			=> "Kunne ikke vælge databasen $1",
"cachederror"	=> "Det følgende er en gemt kopi af den ønskede side, og er måske ikke helt opdateret.",
"readonly"		=> "Databasen er skrivebeskyttet",
"enterlockreason" => "Skriv en begrundelse for skrivebeskyttelsen, også indeholdende et estimat
på hvornår skrivebeskyttelsen vil blive ophævet igen",
"readonlytext"	=> "{{SITENAME}}databasen er for øjeblikket skrivebeskyttet,
hvilket forhindrer oprettelse af nye sider og andre ændringer,
sandsynligvis på grund af rutinemæssig databasevedligeholdelse,
hvorefter den vil returnere til normaldrift. Administratoren der
skrivebeskyttede databasen har denne forklaring:
<p>$1",
"missingarticle" => "Databasen fandt ikke teksten på en side,
som den skulle have fundet, med navnet \"$1\".

<p>Dette er ikke en databasefejl, men sandsynligvis en fejl i softwaren.

<p>Send venligst en rapport om dette til en administrator,
hvor du også nævner URL'en.",
'readonly_lag' => "Databasen er automatisk blevet låst mens slave database serverne synkronisere med master databasen",
"internalerror" => "Intern fejl",
"filecopyerror" => "Kunne ikke kopiere filen \"$1\" til \"$2\".",
"filerenameerror" => "Kunne ikke omdøbe filen \"$1\" til \"$2\".",
"filedeleteerror" => "Kunne ikke slette filen \"$1\".",
"filenotfound"	=> "Kunne ikke finde filen \"$1\".",
"unexpected"	=> "Uventet værdi: \"$1\"=\"$2\".",
"formerror"		=> "Fejl: Kunne ikke afsende formular",
"badarticleerror" => "Denne funktion kan ikke udføres på denne side.",
"cannotdelete"	=> "Kunne ikke slette siden eller filen der blev angivet.",
"badtitle"		=> "Forkert titel",
"badtitletext"	=> "Den ønskede sides titel var ikke tilladt, tom eller siden
er forkert henvist fra en {{SITENAME}} på et andet sprog.",
"perfdisabled" => "Denne funktion er desværre midlertidigt afbrudt,
fordi den belaster databasen meget hårdt og i en sådan grad,
at siden bliver meget langsom. Funktionen bliver forhåbentlig
omskrevet i den nærmeste fremtid (måske af dig, det er jo open source!).",
"perfdisabledsub" => "Her er en gemt kopi fra $1:",
'perfcached' => 'Følgende data er gemt i cachen, det er muligvis ikke helt opdateret:',
'wrong_wfQuery_params' => "Ugyldig parameter til wfQuery()<br />
Funktion: $1<br />
Forespørgsel: $2",
'viewsource' => 'Vis kilden',
'protectedtext' => "Denne side er skrivebeskyttet for at forhindre ændringer;
der kan være flere årsager til at det er sket,
se [[Special:Log/protect|listen over beskyttede sider]].

Du kan sé og kopiere sidens indhold:",
'sqlhidden' => '(SQL forespørgsel gemt)',

# Login and logout pages
#
"logouttitle"	=> "Bruger-log-af",
"logouttext"	=> "Du er nu logget af.
Du kan fortsætte med at bruge {{SITENAME}} anonymt, eller du kan logge på
igen som den samme eller en anden bruger.",

"welcomecreation" => "== Velkommen, $1! ==

Din konto er blevet oprettet. Glem ikke at personliggøre dine {{SITENAME}}-indstillinger.",

"loginpagetitle" => "Bruger log på",
"yourname"		=> "Dit brugernavn",
"yourpassword"	=> "Din adgangskode",
"yourpasswordagain" => "Gentag adgangskode",
"remembermypassword" => "Husk min adgangskode til næste gang.",
'yourdomainname'       => 'Your domain',
'externaldberror'      => 'There was either an external authentication database error or you are not allowed to update your external account.',
"loginproblem"	=> "<b>Der har været et problem med at få dig logget på.</b><br />Prøv igen!",
"alreadyloggedin" => "<strong>Bruger $1, du er allerede logget på!</strong><br />",

"login"			=> "Log på",
'loginprompt'   => "Du skal have cookies slået til for at kunne logge på {{SITENAME}}.",
"userlogin"		=> "Opret en konto eller log på",
"logout"		=> "Log af",
"userlogout"	=> "Log af",
"notloggedin"	=> "Ikke logget på",
"createaccount"	=> "Opret en ny konto",
'createaccountmail'	=> 'via e-mail',
"badretype"		=> "De indtastede adgangskoder er ikke ens.",
"userexists"	=> "Det brugernavn du har valgt er allerede i brug. Vælg
venligst et andet brugernavn.",
"youremail"		=> "Din e-mail-adresse *",
'yourrealname'		=> 'Dit rigtige navn*',
'yourlanguage'	=> 'Grænsefladesprog',
'yourvariant'  => 'Sprogvariant',
"yournick"		=> "Dit kaldenavn (til signaturer)",
'prefs-help-email' 	=> '** <strong>E-mail-adresse</strong> (valgfrit): Giver andre mulighed for at kontakte dig, uden du behøver at afsløre din e-mail-adresse. Det kan også bruges til at fremsende en ny adgangskode til dig, hvis du glemmer den du har.',
'prefs-help-email-enotif' => 'Denne e-mail-adresse bruges også til at sende beskeder til dig via e-mail, hvis du har aktiveret funktionerne.',
'prefs-help-realname' 	=> '* <strong>Dit rigtige navn</strong> (valgfrit): Hvis du vælger at oplyse dit navn vil dette blive brugt til at tilskrive dig dit arbejde.',
"loginerror"	=> "Fejl med at logge på",
'nocookiesnew'	=> "Din brugerkonto er nu oprettet, men du er ikke logget på. {{SITENAME}} bruger cookies til at logge brugere på. Du har slået cookies fra. Vær venlig at slå cookies til, og derefter kan du logge på med dit nye brugernavn og kodeord.",
"nocookieslogin"	=> "{{SITENAME}} bruger cookies til at logge brugere på. Du har slået cookies fra. Slå dem venligst til og prøv igen.",
"noname"		=> "Du har ikke angivet et gyldigt brugernavn.",
"loginsuccesstitle" => "Logget på med succes",
"loginsuccess"	=> "Du er nu logget på {{SITENAME}} som \"$1\".",
"nosuchuser"	=> "Der er ingen bruger med navnet \"$1\".
Kontrollér stavemåden igen, eller brug formularen herunder til at oprette en ny brugerkonto.",
'nosuchusershort'	=> "Der er ingen bruger ved navn \"$1\". Tjek din stavning.",
"wrongpassword"	=> "Den indtastede adgangskode var forkert. Prøv igen.",
"mailmypassword" => "Send en ny adgangskode til min e-mail-adresse",
"passwordremindertitle" => "Ny adgangskode fra {{SITENAME}}",
"passwordremindertext" => "Nogen (sandsynligvis dig, fra IP-adressen $1)
har bedt om at vi sender dig en ny adgangskode til at logge på {{SITENAME}}.
Den nye adgangskode for bruger \"$2\" er nu \"$3\".
Du bør logge på nu og ændre din adgangskode.",
"noemail"		=> "Der er ikke oplyst en e-mail-adresse for bruger \"$1\".",
"passwordsent"	=> "En ny adgangskode er sendt til e-mail-adressen,
som er registreret for \"$1\".
Du bør logge på og ændre din adgangskode straks efter du har modtaget e-mail'en.",
'mailerror' => "Fejl ved afsendelse af e-mail: $1",
'acct_creation_throttle_hit' => 'Du har allerede oprettet $1 kontoer. Du kan ikke oprette flere.',
'emailauthenticated' 	=> 'Din e-mail-adresse blev bekræftet på $1.',
'emailnotauthenticated'	=> 'Din e-mail-adresse er endnu ikke bekræftet og de avancerede e-mail-funktioner er slået fra indtil bekræftelse har fundet sted (d.u.a.).
Log ind med den midlertidige adgangskode, der er blevet sendt til dig, for at bekræfte, eller bestil et nyt på loginsiden.',
'invalidemailaddress'	=> 'E-mail-adressen kan ikke accepteres da den tilsyneladende har et ugyldigt format. Skriv venligst en e-mail-adresse med et korrekt format eller tøm feltet.',

# Edit page toolbar
'bold_sample'=>'Fed tekst',
'bold_tip'=>'Fed tekst',
'italic_sample'=>'Kursiv tekst',
'italic_tip'=>'Kursiv tekst',
'link_sample'=>'Henvisning',
'link_tip'=>'Intern henvisning',
'extlink_sample'=>'http://www.eksempel.dk Titel på henvisning',
'extlink_tip'=>'Ekstern henvisning (husk http:// præfiks)',
'headline_sample'=>'Tekst til overskrift',
'headline_tip'=>'Type 2 overskrift',
'math_sample'=>'Indsæt formel her (LaTeX)',
'math_tip'=>'Matematisk formel (LaTeX)',
'nowiki_sample'=>'Indsæt tekst her som ikke skal wikiformateres',
'nowiki_tip'=>'Ignorer wikiformatering',
'image_sample'=>'Eksempel.jpg',
'image_tip'=>'Indlejret billede',
'media_sample'=>'Eksempel.mp3',
'media_tip'=>'Henvisning til multimediefil',
'sig_tip'=>'Din signatur med tidsstempel',
'hr_tip'=>'Horisontal linje (brug den sparsomt)',

# Edit pages
#
# problem with link: {{fullurl:{{ns:4}}}}:Beskrivelse
'summary'		=> '<a href="{{fullurl:{{ns:4}}}}:Beskrivelse" title="Giv venligst en kort beskrivelse af din ændring">Beskrivelse</a>',
"subject"		=> "Emne/overskrift",
"minoredit"		=> "Dette er en mindre ændring.",
"watchthis"		=> "Overvåg denne artikel",
"savearticle"	=> "Gem side",
"preview"		=> "Forhåndsvisning",
"showpreview"	=> "Forhåndsvisning",
'showdiff'	=> 'Vis ændringer',
"blockedtitle"	=> "Brugeren er blokeret",
"blockedtext"	=> "Dit brugernavn eller din IP-adresse er blevet blokeret af
$1. Begrundelsen er denne:<br />$2<p>Du kan kontakte $1
eller en af de andre [[{{MediaWiki:grouppage-sysop}}|administratorer]] for at diskutere blokeringen.

Din IP-adresse er $3.
Sørg venligst for at medtage denne IP-adresse i alle henvendelser til en administrator.",
'whitelistedittitle' => 'Log på for at redigere',
'whitelistedittext' => 'Du skal [[Special:Userlogin|logge på]] for at kunne rette artikler.',
'whitelistreadtitle' => 'Log på for at læse',
'whitelistreadtext' => 'Du skal [[Special:Userlogin|logge på]] for at læse artikler.',
'whitelistacctitle' => 'Du har ikke lov til at oprette en konto',
'whitelistacctext' => 'For at få lov til at lave en konto på denne wiki skal du [[Special:Userlogin|logge på]] og have passende rettigheder.',
'loginreqtitle'	=> 'Log på nødvendigt',
'loginreqlink' => 'logge på',
'loginreqpagetext'	=> 'Du skal $1 for at se andre sider.',
'accmailtitle' => 'Adgangskode sendt.',
'accmailtext' => "Adgangskoden for '$1' er sendt til $2.",
"newarticle"	=> "(Ny)",
# problem with link: [[{{ns:4}}:Sandkassen|sandkassen]]
"newarticletext" => "<div style=\"font-size:small;color:\#003333;border-width:1px;border-style:solid;border-color:\#aaaaaa;padding:3px\">'''{{SITENAME}} har endnu ikke nogen {{NAMESPACE}}-side ved navn {{PAGENAME}}.'''<br /> Du kan begynde en side ved at skrive i boksen herunder. (se [[{{MediaWiki:helppage}}|hjælpen]] for yderligere oplysninger).<br /> Eller du kan [[{{ns:-1}}:Search/{{PAGENAME}}|søge efter {{PAGENAME}} i {{SITENAME}}]].<br /> Hvis det ikke var din mening, så tryk på '''Tilbage'''- eller '''Back'''-knappen. '''Dit bidrag til {{SITENAME}} vil fremkomme omgående''', så hvis du bare vil teste tingene, så brug venligst [[{{ns:4}}:Sandkassen|sandkassen]]!</div>",
"anontalkpagetext" => "---- ''Dette er en diskussionsside for en anonym bruger, der ikke har oprettet en konto endnu eller ikke bruger den. Vi er derfor nødt til at bruge den nummeriske IP-adresse til at identificere ham eller hende. En IP-adresse kan være delt mellem flere brugere. Hvis du er en anonym bruger og synes, at du har fået irrelevante kommentarer på sådan en side, så vær venlig at oprette en brugerkonto og [[Special:Userlogin|logge på]], så vi undgår fremtidige forvekslinger med andre anonyme brugere.''",
# problem with link: [[Wiktionary:{{NAMESPACE}}:{{PAGENAME}}|wikiordbogen]]
# problem with link: [[:no:{{PAGENAME}}|norsk]]
# problem with link: [[:nn:{{PAGENAME}}|nynorsk]]
# problem with link: [[:sv:{{PAGENAME}}|svensk]]
"noarticletext" => "<div style=\"border: 1px solid \#ccc; padding: 7px; background-color: \#fff; color: \#000\">'''{{SITENAME}} har ikke nogen side med præcis dette navn.''' * Du kan se om {{PAGENAME}} findes i [[Wiktionary:{{NAMESPACE}}:{{PAGENAME}}|wikiordbogen]] eller på '''[[:no:{{PAGENAME}}|norsk]]''', '''[[:nn:{{PAGENAME}}|nynorsk]]''', eller '''[[:sv:{{PAGENAME}}|svensk]]'''. * Du kan '''[{{fullurl:{{NAMESPACE}}:{{PAGENAME}}|action=edit}} starte siden {{PAGENAME}}]''' * Eller [[{{ns:special}}:Search/{{PAGENAME}}|søge efter {{PAGENAME}}]] i andre artikler ---- * Hvis du har oprettet denne artikel indenfor de sidste få minutter, så kan de skyldes at der er lidt forsinkelse i opdateringen af {{SITENAME}}s cache. Vent venligst og tjek igen senere om artiklen dukker op, inden du forsøger at oprette artiklen igen. </div>",
'clearyourcache' => "'''Bemærk''', efter at have gemt, er du nødt til at tømme din browsers cache for at kunne se ændringerne. '''Mozilla / Firefox / Safari''': hold ''shifttasten'' nede og klik på ''reload'' eller tryk på ''control-shift-r'' (Mac: ''cmd-shift-r''); '''Internet Explorer''': hold ''controltasten'' nede og klik på ''refresh'' eller tryk på ''control-F5''; '''Konqueror''': klik på ''reload'' eller tryk på ''F5''",
'usercssjsyoucanpreview' => "<strong>Tip:</strong> Brug knappen 'forhåndsvisning' til at teste dit nye css/js før du gemmer.",
'usercsspreview' => "'''Husk at du kun tester/forhåndsviser dit eget css, den er ikke gemt endnu!'''",
'userjspreview' => "'''Husk at du kun tester/forhåndsviser dit eget javascript, det er ikke gemt endnu!'''",
"updated"		=> "(Opdateret)",
'note'			=> '<strong>Bemærk:</strong>',
"previewnote"	=> "Husk at dette er kun en forhåndsvisning, siden er ikke
gemt endnu!",
"previewconflict" => "Denne forhåndsvisning er resultatet af den
redigérbare tekst ovenfor,
sådan vil det komme til at se ud hvis du vælger at gemme teksten.",
"editing"		=> "Redigerer $1",
'editinguser'		=> "Redigerer $1",
"editingsection"	=> "Redigerer $1 (afsnit)",
"editingcomment"	=> "Redigerer $1 (kommentar)",
"editconflict"	=> "Redigeringskonflikt: $1",
"explainconflict" => "Nogen har ændret denne side, efter du
startede på at redigere den.
Den øverste tekstboks indeholder den nuværende tekst.
Dine ændringer er vist i den nederste tekstboks.
Du er nødt til at sammenflette dine ændringer med den eksisterende tekst.
<b>Kun</b> teksten i den øverste tekstboks vil blive gemt når du
trykker \"Gem side\".<br />",
"yourtext"		=> "Din tekst",
"storedversion" => "Den gemte version",
'nonunicodebrowser' => "<strong>Advarsel: Din browser er ikke unicode-kompatibel, skift eller opdater din browser før du redigerer en artikel.</strong>",
"editingold"	=> "<strong>ADVARSEL: Du redigerer en gammel version
af denne side.
Hvis du gemmer den, vil alle ændringer foretaget siden denne revision blive
overskrevet.</strong>",
"yourdiff"		=> "Forskelle",
/*"copyrightwarning" => "*Bemærk at alle bidrag til {{SITENAME}} er at betragte som udgivet under GNU Free Documentation License (se $1 for flere oplysninger). *Hvis du ikke ønsker at din tekst skal udsættes for nådesløse redigeringer og at den kan blive kopieret efter forgodtbefindende, så skal du ikke placere den her. *Du lover os også, at du selv har forfattet teksten, kopieret den fra en public domain-kilde eller tilsvarende fri kilde. <strong><big>LÆG ALDRIG MATERIALE HER SOM ER BESKYTTET AF ANDRES OPHAVSRET UDEN DERES TILLADELSE!</big></strong>",*/
"longpagewarning" => "<strong>ADVARSEL: Denne side er $1 kilobyte stor; nogle browsere kan have problemer med at redigere sider der nærmer sig eller er større end 32 Kb. Overvej om siden kan opdeles i mindre dele.</strong>",
"readonlywarning" => "<strong>ADVARSEL: Databasen er låst på grund af vedligeholdelse,
så du kan ikke gemme dine ændringer lige nu. Det kan godt være en god ide at
kopiere din tekst til en tekstfil, så du kan gemme den til senere.</strong>",
# problem with link: [[Project:Politik_for_beskyttede_sider|politiken for beskyttede sider]]
"protectedpagewarning" => "<strong>ADVARSEL: Denne side er låst, så kun administratorer
kan redigere den. Sørg for at du følger
[[Project:Politik_for_beskyttede_sider|politiken for beskyttede sider]].</strong>",
'templatesused'	=> 'Skabeloner der er brugt på denne side:',

# History pages
#
"revhistory"	=> "Versionshistorik",
"nohistory"		=> "Der er ingen versionshistorik for denne side.",
"revnotfound"	=> "Versionen er ikke fundet",
"revnotfoundtext" => "Den gamle version af den side du spurgte efter kan
ikke findes. Kontrollér den URL du brugte til at få adgang til denne side.",
"loadhist"		=> "Indlæser sidens historik",
"currentrev"	=> "Nuværende version",
"revisionasof"	=> "Versionen fra $1",
'previousrevision'	=> '←Ældre version',
'nextrevision'		=> 'Nyere version→',
'currentrevisionlink'   => 'se nuværende version',
"cur"			=> "nuværende",
"next"			=> "næste",
"last"			=> "forrige",
"orig"			=> "originale",
"histlegend"	=> "Forklaring: (nuværende) = forskel til den nuværende
version, (forrige) = forskel til den forrige version, M = mindre ændring",
'deletedrev' => '[slettet]',

# Diffs
#
"difference"	=> "(Forskelle mellem versioner)",
"loadingrev"	=> "indlæser version for at se forskelle",
"lineno"		=> "Linje $1:",
"editcurrent"	=> "Redigér den nuværende version af denne side",
'selectnewerversionfordiff' => 'Vælg en nyere version til sammenligning',
'selectolderversionfordiff' => 'Vælg en ældre version til sammenligning',
'compareselectedversions' => 'Sammenlign valgte versioner',

# Search results
#
"searchresults" => "Søgeresultater",
"searchresulttext" => "For mere information om søgning på {{SITENAME}}, se [[{{MediaWiki:helppage}}|{{int:help}}]].",
"badquery"		=> "Forkert udformet forespørgsel",
"badquerytext"	=> "Vi kunne ikke udføre din forespørgsel.
Det er sandsynligvis fordi du har forsøgt at søge efter et ord med
færre end tre bogstaver, hvilket ikke understøttes endnu.
Det kan også være du har skrevet forkert, for
eksempel \"fisk og og skaldyr\".
Prøv en anden forespørgsel.",
"matchtotals"	=> "Forespørgslen \"$1\" opfyldte $2 artikeltitler
og teksten i $3 artikler.",
# problem with link: [[{{ns:4}}:Efterspurgte_artikler|efterspørge den]]
"noexactmatch" => "{{SITENAME}} har ingen artikel med dette navn. Du kan [[:$1|oprette en artikel med dette navn]] eller [[{{ns:4}}:Efterspurgte_artikler|efterspørge den]]. For at undgå flere artikler om samme emne, bedes du inden oprettelsen søge efter artiklen under alternative navne og stavemåder.",
"titlematches"	=> "Artikeltitler der opfyldte forespørgslen",
"notitlematches" => "Ingen artikeltitler opfyldte forespørgslen",
"textmatches"	=> "Artikeltekster der opfyldte forespørgslen",
"notextmatches"	=> "Ingen artikeltekster opfyldte forespørgslen",
"prevn"			=> "forrige $1",
"nextn"			=> "næste $1",
"viewprevnext"	=> "Vis ($1) ($2) ($3).",
"showingresults" => "Nedenfor vises <b>$1</b> resultater startende med
nummer <b>$2</b>.",
"showingresultsnum" => "Herunder vises <b>$3</b> resultater startende med nummer <b>$2</b>.",
"nonefound"		=> "<strong>Bemærk</strong>: Søgning uden resultat skyldes at man søger efter almindelige ord som \"har\" og \"fra\", der ikke er indekseret, eller at man har angivet mere end ét søgeord (da kun sider indeholdende alle søgeordene vil blive fundet).",
"powersearch" => "Søg",
"powersearchtext" => "
Søg i navnerum:<br />
$1<br />
$2 List omdirigeringer &nbsp; Søg efter $3 $9",
"searchdisabled" => "<p>Beklager! Fuldtekstsøgningen er midlertidigt afbrudt på grund af for stor belastning på serverne. I mellemtidem kan du anvende Google- eller Yahoo!-søgefelterne herunder. Bemærk at deres kopier af {{SITENAME}}s indhold kan være forældet.</p>",
"blanknamespace" => "(Artikler)",

# Preferences page
#
"preferences"	=> "Indstillinger",
"prefsnologin" => "Ikke logget på",
"prefsnologintext"	=> "Du skal være [[Special:Userlogin|logget på]]
for at ændre brugerindstillinger.",
"prefsreset"	=> "Indstillingerne er blevet gendannet fra lageret.",
"qbsettings"	=> "Hurtigmenu",
'qbsettings-none'	=> 'Ingen',
'qbsettings-fixedleft'	=> 'Fast venstre',
'qbsettings-fixedright'	=> 'Fast højre',
'qbsettings-floatingleft'	=> 'Flydende venstre',
'qbsettings-floatingright'	=> 'Flydende højre',
"changepassword" => "Skift adgangskode",
"skin"			=> "Udseende",
"math"			=> "Matematiske formler",
"dateformat"	=> "Datoformat",
"math_failure"		=> "Fejl i matematikken",
"math_unknown_error"	=> "ukendt fejl",
"math_unknown_function"	=> "ukendt funktion",
"math_lexing_error"	=> "lexerfejl",
"math_syntax_error"	=> "syntaksfejl",
'math_image_error'	=> 'PNG-konvertering mislykkedes; undersøg om latex, dvips, gs og convert er installeret korrekt',
'math_bad_tmpdir'	=> 'Kan ikke skrive til eller oprette temp-mappe til math',
'math_bad_output'	=> 'Kan ikke skrive til eller oprette uddata-mappe til math',
'math_notexvc'	=> 'Manglende eksekvérbar texvc; se math/README for opsætningsoplysninger.',
'prefs-personal' => 'Brugerdata',
'prefs-rc' => 'Seneste ændringer og artikelstumper',
'prefs-misc' => 'Forskelligt',
'prefs-watchlist-edits' => 'Antal redigeringer der vises i udvidet overvågningsliste:',
"saveprefs"		=> "Gem indstillinger",
"resetprefs"	=> "Gendan indstillinger",
"oldpassword"	=> "Gammel adgangskode",
"newpassword"	=> "Ny adgangskode",
"retypenew"		=> "Gentag ny adgangskode",
"textboxsize"	=> "Redigering",
"rows"			=> "Rækker",
"columns"		=> "Kolonner",
"searchresultshead" => "Søgeresultater",
"resultsperpage" => "Resultater pr. side",
"contextlines"	=> "Linjer pr. resultat",
"contextchars"	=> "Tegn pr. linje i resultatet",
"stubthreshold" => "Grænse for visning af artikelstumper",
"recentchangescount" => "Antallet af titler på siden \"seneste ændringer\"",
"savedprefs"	=> "Dine indstillinger er blevet gemt.",
'timezonelegend' => 'Tidszone',
"timezonetext"	=> "Indtast antal timer din lokale tid er forskellig
fra serverens tid (UTC). Der bliver automatisk tilpasset til dansk tid,
ellers skulle man for eksempel for dansk vintertid, indtaste \"1\"
(og \"2\" når vi er på sommertid).",
"localtime"	=> "Lokaltid",
"timezoneoffset" => "Forskel",
"servertime"	=> "Serverens tid er nu",
"guesstimezone" => "Hent tidszone fra browseren",
"defaultns"		=> "Søg som standard i disse navnerum:",
'default'		=> 'standard',
'files'			=> 'Filer',

# User levels special page
#

# switching pan

'userrights-lookup-user' => 'Administrér brugergrupper',
'userrights-user-editname' => 'Skriv et brugernavn:',
'editusergroup' => 'Redigér brugergrupper',

# user groups editing
#
'userrights-editusergroup' => 'Redigér brugergrupper',
'saveusergroups' => 'Gem brugergrupper',
'userrights-groupsmember' => 'Medlem af:',
'userrights-groupsavailable' => 'Tilgængelige grupper:',
'userrights-groupshelp' => 'Vælg grupper som du ønsker brugeren skal fjernes fra eller føjes til.
Grupper som ikke er valgt, vil ikke blive ændret. Du kan ophæve valget af en gruppe ved hjælp af CTRL-tasten og et venstreklik.',

# Groups
#
'grouppage-sysop' => 'Project:Administratorer',

# Recent changes
#
"changes" => "ændringer",
"recentchanges" => "Seneste ændringer",
"rcnote"		=> "Nedenfor er de seneste <strong>$1</strong> ændringer i de
sidste <strong>$2</strong> dage.",
"rcnotefrom"	=> "Nedenfor er ændringerne fra <b>$2</b> indtil <b>$1</b> vist.",
"rclistfrom"	=> "Vis nye ændringer startende fra $1",
"rclinks"		=> "Vis seneste $1 ændringer i de sidste $2 dage<br />$3",
"diff"			=> "forskel",
"hist"			=> "historik",
"hide"			=> "skjul",
"show"			=> "vis",
"minoreditletter" => "m",
"newpageletter" => "N",
'sectionlink' => '→',
'number_of_watching_users_pageview' 	=> '[$1 overvågende bruger/e]',

# Upload
#
"upload"		=> "Læg en fil op",
"uploadbtn"		=> "Læg en fil op",
"reupload"		=> "Læg en fil op igen",
"reuploaddesc"	=> "Tilbage til formularen til at lægge filer op.",
"uploadnologin" => "Ikke logget på",
"uploadnologintext"	=> "Du skal være [[Special:Userlogin|logget på]] for at kunne lægge filer op.",
"uploaderror"	=> "Fejl under oplægning af fil",
# problem with link: [[{{NS:4}}:Politik om brug af billeder|politik om brug af billeder]]
# problem with link: [[{{NS:4}}:Skabeloner#Ophavsret|skabelonsiden]]
"uploadtext"	=> "<div style=\"border: 1px solid grey; background: \#ddf; padding: 7px; margin: 0 auto;\">
<strong>STOP!</strong> Før du lægger filer op her,
så vær sikker på du har læst og følger {{SITENAME}}s
[[{{NS:4}}:Politik om brug af billeder|politik om brug af billeder]].

Følg venligst disse retningslinjer:
<ul>
<li>Angiv tydeligt hvor filen stammer fra</li>
<li>Angiv tydeligt hvilken licens filen er omfattet af, ved at tilføje en af skabelonerne <tt>&#123;{GFDL}}</tt> eller <tt>&#123;{PD}}</tt> eller en af de andre du kan finde på [[{{NS:4}}:Skabeloner#Ophavsret|skabelonsiden]].</li>
<li>Brug et beskrivende filnavn, så det er til at se hvad filen indeholder</li>
<li>Tjek i [[Special:Imagelist|listen over filer]] om filen allerede er lagt op</li>
</ul>
</div>
<p>Brug formularen herunder til at lægge nye filer op, som kan bruges i dine artikler.
På de fleste browsere vil du se en \"Browse...\" knap eller en
\"Gennemse...\" knap, som vil
bringe dig til dit styresystems standard-dialog til åbning af filer.
Når du vælger en fil, vil navnet på filen dukke op i tekstfeltet
ved siden af knappen.
Du skal også bekræfte, at du ikke er ved at bryde nogens ophavsret.
Det gør du ved at sætte et mærke i tjekboksen.
Vælg \"Læg en fil op\"-knappen for at lægge filen op.
Dette kan godt tage lidt tid hvis du har en langsom internetforbindelse.

<p>De foretrukne formater er JPEG til fotografiske billeder, PNG
til tegninger og andre små billeder, og OGG til lyd.
For at bruge et billede i en artikel, så brug et link af denne type
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:fil.jpg]]</nowiki>''' eller
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:fil.png|alternativ tekst]]</nowiki>''' eller
'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:fil.ogg]]</nowiki>''' for lyd.

<p>Læg mærke til at præcis som med alle andre sider, så kan og må andre gerne
redigere eller
slette de filer, du har lagt op, hvis de mener det hjælper {{SITENAME}}, og
du kan blive blokeret fra at lægge op hvis du misbruger systemet.",
"uploadlog"		=> "oplægningslog",
"uploadlogpage" => "Oplægningslog",
"uploadlogpagetext" => "Herunder en liste over de senest oplagte filer. Alle de viste tider er serverens tid (UTC).",
"filename"		=> "Filnavn",
"filedesc"		=> "Beskrivelse",
'filestatus' => 'Status på ophavsret',
'filesource' => 'Kilde',
"copyrightpage" => "{{ns:project}}:Ophavsret",
"copyrightpagename" => "{{SITENAME}} ophavsret",
"uploadedfiles"	=> "Filer som er lagt op",
"minlength"		=> "Navnet på filen skal være på mindst tre bogstaver.",
'illegalfilename'	=> 'Filnavnet "$1" indeholder tegn, der ikke er tilladte i sidetitler. Omdøb filen og prøv at lægge den op igen.',
"badfilename"	=> "Navnet på filen er blevet ændret til \"$1\".",
"badfiletype"	=> "\".$1\" er ikke et af de anbefalede filformater.",
"largefile"		=> "Det anbefales, at filer ikke fylder mere end $1kb ($2).",
'emptyfile'		=> 'Filen du lagde op lader til at være tom. Det kan skyldes en slåfejl i filnavnet. Kontroller om du virkelig ønsker at lægge denne fil op.',
'fileexists'		=> 'En fil med det navn findes allerede, tjek venligst $1 om du er sikker på du vil ændre den.',
"successfulupload" => "Oplægning er gennemført med succes",
"fileuploaded"	=> "Filen \"$1\" er lagt op med succes.
Følg dette link: ($2) til siden med beskrivelse, og udfyld
information omkring filen, såsom hvor den kom fra, hvornår den er lavet
og af hvem, og andre ting du ved om filen.",
"uploadwarning" => "Oplægningsadvarsel",
"savefile"		=> "Gem fil",
"uploadedimage" => "Lagde \"[[$1]]\" op",
"uploaddisabled" => "Desværre er funktionen til at lægge billeder op afbrudt på denne server.",
'uploadscripted' => 'Denne fil indeholder HTML eller script-kode, der i visse tilfælde can fejlfortolkes af en browser.',
'uploadcorrupt' => 'Denne fil er beskadiget eller forsynet med en forkert endelse. Kontroller venligst filen og prøv at lægge den op igen.',
'uploadvirus' => 'Denne fil indeholder en virus! Virusnavn: $1',

# Image list
#
"imagelist"		=> "Liste over billeder",
"imagelisttext"	=> "Herunder er en liste med $1 billeder sorteret $2.",
"getimagelist"	=> "henter billedliste",
"ilsubmit"		=> "Søg",
"showlast"		=> "Vis de sidste $1 billeder sorteret $2.",
"byname"		=> "efter navn",
"bydate"		=> "efter dato",
"bysize"		=> "efter størrelse",
"imgdelete"		=> "slet",
"imgdesc"		=> "beskrivelse",
"imglegend"		=> "Forklaring: (beskrivelse) = vis/redigér billedebeskrivelse.",
"imghistory"	=> "Billedhistorik",
"revertimg"		=> "gendan",
"deleteimg"		=> "slet",
"deleteimgcompletely"		=> "Slet alle revisioner af dette billede",
"imghistlegend" => "Forklaring: (nuværende) = dette er det nuværende billede,
(slet) = slet denne gamle version, (gendan) = gendan en gammel version.
<br /><i>Klik på en dato for at se billedet, som er lagt op den dag</i>.",
"imagelinks"	=> "Billedehenvisninger",
"linkstoimage"	=> "De følgende sider henviser til dette billede:",
"nolinkstoimage" => "Der er ingen sider der henviser til dette billede.",
'shareduploadwiki' => 'Se venligst $1 for yderligere information.',
'shareduploadwiki-linktext' => 'siden med billedbeskrivelsen',
'noimage'       => 'Der eksisterer ingen fil med dette navn, du kan $1',
'noimage-linktext' => 'lægge den op',
'uploadnewversion-linktext' => 'Læg en ny version af denne fil op',

# Statistics
#
"statistics"	=> "Statistik",
"sitestats"		=> "Statistiske oplysninger om {{SITENAME}}",
"userstats"		=> "Statistik om brugere på {{SITENAME}}",
"sitestatstext" => "Der er i alt '''$1''' sider i databasen.
Dette tal indeholder \"diskussion\"-sider, sider om {{SITENAME}}, omdirigeringssider og andre sider der sikkert ikke kan kaldes artikler.
Hvis man udelader disse, så er der '''$2''' sider som sandsynligvis er rigtige artikler.
Der har i alt været '''$4''' sideredigeringer siden programmellet blev opdateret den 25. september 2002.
Det vil sige, at der har været '''$5''' gennemsnitlige redigeringer pr. side.",

# Maintenance Page
#
"disambiguations"	=> "Artikler med flertydige titler",
"disambiguationspage"	=> "Project:Henvisninger til artikler med flertydige titler",
"disambiguationstext"	=> "De følgende artikler henviser til
<i>artikler med flertydige titler</i>. De skulle henvise til en ikke-flertydig
titel i stedet for.<br />En artikel bliver behandlet som flertydig, hvis den er
henvist fra $1.<br />Henvisninger fra andre navnerum er <i>ikke</i> listet her.",
"doubleredirects"	=> "Dobbelte omdirigeringer",
"doubleredirectstext"	=> "<b>Bemærk:</b> Denne liste kan indeholde forkerte
resultater. Det er som regel, fordi siden indeholder ekstra tekst under den
første #REDIRECT.<br />\nHver linje indeholder henvisninger til den første og den
anden omdirigering, og den første linje fra den anden omdirigeringstekst,
det giver som regel den \"rigtige\" målartikel, som den første omdirigering
skulle have peget på.",
"brokenredirects"	=> "Dårlige omdirigeringer",
"brokenredirectstext"	=> "De følgende omdirigeringer peger på en side der
ikke eksisterer.",


# Miscellaneous special pages
#
"lonelypages"	=> "Forældreløse artikler",
'uncategorizedpages'	=> 'Ukategoriserede sider',
'uncategorizedcategories'	=> 'Ukategoriserede kategorier',
"unusedimages"	=> "Ubrugte billeder",
"popularpages"	=> "Populære artikler",
"nviews"		=> "$1 visninger",
"wantedpages"	=> "Ønskede artikler",
"nlinks"		=> "$1 henvisninger",
"allpages"		=> "Alle artikler",
"randompage"	=> "Tilfældig artikel",
"shortpages"	=> "Korte artikler",
"longpages"		=> "Lange artikler",
'deadendpages'  => 'Blindgydesider',
"listusers"		=> "Liste over brugere",
"specialpages"	=> "Specielle sider",
"spheading"		=> "Specielle sider for alle brugere",
'restrictedpheading'	=> 'Specielle sider med begrænset adgang',
"recentchangeslinked" => "Relaterede ændringer",
"rclsub"		=> "(til sider henvist fra \"$1\")",
"newpages"		=> "Nyeste artikler",
"ancientpages"		=> "Ældste artikler",
"intl"		=> "Sproghenvisninger",
"movethispage"	=> "Flyt side",
"unusedimagestext" => "<p>Læg mærke til, at andre websider
såsom de andre internationale {{SITENAME}}er måske henviser til et billede med
en direkte URL, så det kan stadig være listet her, selvom det er
i aktivt brug.",
"booksources"	=> "Bogkilder",
'categoriespagetext' => 'De følgende kategorier eksisterer på {{SITENAME}}.',
'data'	=> 'Data',

# FIXME: Other sites, of course, may have affiliate relations with the booksellers list
"booksourcetext" => "Herunder er en liste af henvisninger til steder der
udlåner og/eller sælger nye og brugte bøger, og som måske også har
yderligere oplysninger om bøger du leder efter.
{{SITENAME}} er ikke associeret med nogen af disse steder,
og denne liste skal ikke ses som en anbefaling af disse.",
'isbn'	=> 'ISBN',
"alphaindexline" => "$1 til $2",
'version'		=> 'Information om MediaWiki',
'log'		=> 'Logs',
'alllogstext'	=> 'Samlet visning af oplægningslog, sletningslog, blokeringslog, bureaukratlog og listen over beskyttede sider.
Du kan sortere i visningen ved at vælge type, brugernavn og/eller en udvalgt side.',

# Special:Allpages
'nextpage'          => 'Næste side ($1)',
'allpagesfrom'		=> 'Vis sider startende fra: $1',
'allarticles'       => 'Alle artikler',
'allinnamespace'	=> 'Alle sider (i $1 navnerummet)',
'allnotinnamespace'	=> 'Alle sider (ikke i $1 navnerummet)',
'allpagesprev'      => 'Forrige',
'allpagesnext'      => 'Næste',
'allpagessubmit'    => 'Vis',

# Email this user
#
"mailnologin"	=> "Ingen afsenderadresse",
"mailnologintext" => "Du skal være [[Special:Userlogin|logget på]] og have en gyldig e-mailadresse sat i dine [[Special:Preferences|indstillinger]] for at sende e-mail til andre brugere.",
"emailuser"		=> "E-mail til denne bruger",
"emailpage"		=> "E-mail bruger",
"emailpagetext"	=> "Hvis denne bruger har sat en gyldig e-mail-adresse i
sine brugerindstillinger, så vil formularen herunder sende en enkelt
besked.
Den e-mailadresse, du har sat i dine brugerindstillinger, vil dukke op
i \"Fra\" feltet på denne mail, så modtageren er i stand til at svare.",
'usermailererror' => 'E-mail-modulet returnerede en fejl:',
'defemailsubject'  => "{{SITENAME}} e-mail",
"noemailtitle"	=> "Ingen e-mail-adresse",
"noemailtext"	=> "Denne bruger har ikke angivet en gyldig e-mail-adresse,
eller har valgt ikke at modtage e-mail fra andre brugere.",
"emailfrom"		=> "Fra",
"emailto"		=> "Til",
"emailsubject"	=> "Emne",
"emailmessage"	=> "Besked",
"emailsend"		=> "Send",
"emailsent"		=> "E-mail sendt",
"emailsenttext" => "Din e-mail er blevet sendt.",

# Watchlist
#
"watchlist"		=> "Overvågningsliste",
"mywatchlist"		=> "Overvågningsliste",
"nowatchlist"	=> "Du har ingenting i din overvågningsliste.",
"watchnologin"	=> "Ikke logget på",
"watchnologintext"	=> "Du skal være [[Special:Userlogin|logget på]] for at kunne ændre din overvågningsliste.",
"addedwatch"	=> "Tilføjet til din overvågningsliste",
"addedwatchtext" => "Siden \"$1\" er blevet tilføjet til din [[Special:Watchlist|overvågningsliste]]. Fremtidige ændringer til denne side og den tilhørende diskussionsside vil blive listet der, og siden vil fremstå '''fremhævet''' i [[Special:Recentchanges|listen med de seneste ændringer]] for at gøre det lettere at finde den. Hvis du senere vil fjerne siden fra din overvågningsliste, så klik \"Fjern overvågning\".",
"removedwatch"	=> "Fjernet fra overvågningsliste",
"removedwatchtext" => "Siden \"$1\" er blevet fjernet fra din
overvågningsliste.",
'watch' => 'Overvåg',
"watchthispage"	=> "Overvåg side",
'unwatch' => 'Fjern overvågning',
"unwatchthispage" => "Fjern overvågning",
"notanarticle"	=> "Ikke en artikel",
"watchnochange" => "Ingen af siderne i din overvågningsliste er ændret i den valgte periode.",
"watchdetails" => "* Du har $1 sider på din overvågningsliste (fratrukket alle diskussionssider).
* I tidsintervallet valgt herunder, har brugerne foretaget $2 redigeringer i {{SITENAME}}.
* $3
* Du kan [[Special:Watchlist/edit|vise og redigere den komplette liste]].",
'wlheader-enotif' 		=> "* E-mail underretning er slået til.",
'wlheader-showupdated'   => "* Sider der er ændret siden dit sidste besøg er '''fremhævet'''",
"watchmethod-recent" => "Tjekker seneste ændringer for sider i din overvågningsliste",
"watchmethod-list" => "Tjekker seneste ændringer for sider i din overvågningsliste",
"removechecked" => "Fjern valgte sider fra min overvågningsliste",
"watchlistcontains" => "Din overvågningsliste indeholder $1 sider.",
"watcheditlist" => "Her er en alfabetisk liste over siderne i din overvågningsliste.
Vælg de sider du vil fjerne fra din overvågningsliste
og klik på 'fjern valgte sider fra min overvågningsliste' knappen
i bunden af skærmen.",
"removingchecked" => "Fjerner de valgte sider fra din overvågningsliste...",
"couldntremove" => "Kunne ikke fjerne '$1'...",
"iteminvalidname" => "Problem med '$1', ugyldigt navn...",
"wlnote" => "Nedenfor er de seneste $1 ændringer i de sidste <b>$2</b> timer.",
'wlshowlast' 		=> "Vis de seneste $1 timer $2 dage $3",
'wlsaved'			=> 'Dette er en gemt version af din overvågningsliste.',
'wlhideshowown'		=> '$1 mine redigeringer.',
'wlhideshowbots'		=> '$1 robotredigeringer.',
'wldone'			=> 'Gennemført.',

'enotif_mailer' 		=> '{{SITENAME}} informationsmail',
'enotif_reset'			=> 'Marker alle sider som besøgt',
'enotif_newpagetext'=> 'Dette er en ny side.',
'changed'			=> 'ændret',
'created'			=> 'oprettet',
'enotif_subject' 	=> '{{SITENAME}}-siden $PAGETITLE_QP er blevet ændret af $PAGEEDITOR_QP',
'enotif_lastvisited' => 'Se $1 for alle ændringer siden dit sidste besøg.',
# problem with link: {{fullurl:Landsbybrønden}}
'enotif_body' => 'Kære $WATCHINGUSERNAME,

{{SITENAME}}-siden $PAGETITLE er blevet ændret den $PAGEEDITDATE af $PAGEEDITOR, se $PAGETITLE_URL for den nyeste version.

$NEWPAGE

Bidragyderens beskrivelse: $PAGESUMMARY $PAGEMINOREDIT
Kontakt bidragyderen:
mail $PAGEEDITOR_EMAIL
wiki $PAGEEDITOR_WIKI

Du vil ikke modtage flere beskeder om yderligere ændringer af denne side med mindre du besøger den. På din overvågningsliste kan du også nulstille alle markeringer på de sider, du overvåger.

             Med venlig hilsen {{SITENAME}}s informationssystem

--
Besøg {{fullurl:Special:Watchlist/edit}} for at ændre indstillingerne for din overvågningsliste

Tilbagemelding og yderligere hjælp:
{{fullurl:Landsbybrønden}}',

# Delete/protect/revert
#
"deletepage"	=> "Slet side",
"confirm"		=> "Bekræft",
"excontent" => "indholdet var: '$1'",
"excontentauthor" => "indholdet var: '$1' (og den eneste forfatter var '$2')",
"exbeforeblank" => "indholdet før siden blev tømt var: '$1'",
"exblank" => "siden var tom",
"confirmdelete" => "Bekræft sletning",
"deletesub"		=> "(Sletter \"$1\")",
"historywarning" => "Advarsel: Siden du er ved at slette har en historie:",
"confirmdeletetext" => "Du er ved permanent at slette en side
eller et billede sammen med hele den tilhørende historie fra databasen.
Bekræft venligst at du virkelig vil gøre dette, at du forstår
konsekvenserne, og at du gør dette i overensstemmelse med
[[{{MediaWiki:policy-url}}]].",
"policy-url" => "Project:Politik",
"actioncomplete" => "Gennemført",
"deletedtext"	=> "\"$1\" er slettet.
Se $2 for en fortegnelse over de nyeste sletninger.",
"deletedarticle" => "slettede \"$1\"",
"dellogpage"	=> "Sletningslog",
"dellogpagetext" => "Herunder er en liste over de nyeste sletninger.
Alle tider er serverens tid (UTC).",
"deletionlog"	=> "sletningslog",
"reverted"		=> "Gendannet en tidligere version",
"deletecomment"	=> "Begrundelse for sletning",
"imagereverted" => "Gendannelse af en tidligere version gennemført med
succes.",
"rollback"		=> "Fjern redigeringer",
'rollback_short' => 'Fjern redigering',
"rollbacklink"	=> "fjern redigering",
"rollbackfailed" => "Kunne ikke fjerne redigeringen",
"cantrollback"	=> "Kan ikke fjerne redigering;
den sidste bruger er den eneste forfatter.",
"alreadyrolled"	=> "Kan ikke fjerne den seneste redigering af [[:$1]] foretaget af [[User:$2|$2]] ([[User talk:$2|diskussion]]); en anden har allerede redigeret siden eller fjernet redigeringen. Den seneste redigering er foretaget af [[User:$3|$3]] ([[User talk:$3|diskussion]]).",
#   only shown if there is an edit comment
"editcomment" => "Kommentaren til redigeringen var: \"<i>$1</i>\".",
"revertpage"	=> "Gendannelse til seneste version ved $1, fjerner ændringer fra $2",
'sessionfailure' => 'There seems to be a problem with your login session;
this action has been canceled as a precaution against session hijacking.
Please hit "back" and reload the page you came from, then try again.',
"protectlogpage" => "Liste_over_beskyttede_sider",
# problem with link: [[Project:Beskyttet side]]
"protectlogtext" => "Herunder er en liste over sider der er blevet beskyttet/har fået fjernet beskyttelsen.
Se [[Project:Beskyttet side]] for mere information.",
"protectedarticle" => "[[$1]] beskyttet",
"unprotectedarticle" => "fjernet beskyttelse af [[$1]]",
'protectsub' =>"(Beskytter \"$1\")",
'confirmprotecttext' => 'Vil du virkelig beskytte denne side?',
'confirmprotect' => 'Bekræft beskyttelse',
'protectmoveonly' => 'Beskyt kun fra at blive flyttet',
'protectcomment' => 'Begrundelse for beskyttelse',
'unprotectsub' =>"(Fjern beskyttelse af \"$1\")",
'confirmunprotecttext' => 'Vil du virkelig fjerne beskyttelsen fra denne side?',
'confirmunprotect' => 'Bekræft fjernelse af beskyttelse',
'unprotectcomment' => 'Begrundelse for fjernet beskyttelse',

# Undelete
"undelete" => "Gendan en slettet side",
"undeletepage" => "Se og gendan slettede sider",
"undeletepagetext" => "De følgende sider er slettede, men de findes
stadig i arkivet og kan gendannes. Arkivet blivet periodevis slettet.",
"undeletearticle" => "Gendan slettet artikel",
"undeleterevisions" => "$1 revisioner arkiveret",
"undeletehistory" => "Hvis du gendanner siden, vil alle de historiske
revisioner også blive gendannet. Hvis en ny side med det samme navn
er oprettet siden denne blev slettet, så vil de gendannede revisioner
dukke op i den tidligere historie, og den nyeste revision vil forblive
på siden.",
"undeleterevision" => "Slettet version fra $1",
"undeletebtn" => "Gendan!",
"undeletedarticle" => "gendannede \"$1\"",
'undeletedrevisions' => "$1 versioner gendannet",

# Namespace form on various pages
'namespace' => 'Namvnerum:',
'invert' => 'Invert selection',

# Contributions
#
"contributions"	=> "Brugerbidrag",
"mycontris" => "Mine bidrag",
"contribsub2"	=> "For $1 ($2)",
"nocontribs"	=> "Ingen ændringer er fundet som opfylder disse kriterier.",
"ucnote"	=> "Herunder er denne brugers sidste <b>$1</b> ændringer i de
sidste <b>$2</b> dage.",
"uclinks"	=> "Vis de sidste $1 ændringer; vis de sidste $2 dage.",
"uctop"		=> " (top)" ,
'newbies'       => 'nybegyndere',

# What links here
#
"whatlinkshere"	=> "Hvad henviser hertil",
"notargettitle" => "Intet mål",
"notargettext"	=> "Du har ikke angivet en målside eller bruger at udføre denne funktion på.",
"linklistsub"	=> "(Liste over henvisninger)",
"linkshere"	=> "De følgende sider henviser her til:",
"nolinkshere"	=> "Ingen sider henviser her til.",
"isredirect"	=> "omdirigeringsside",

# Block/unblock IP
#
"blockip"		=> "Bloker bruger",
# problem with link [[meta:Range blocks|IP-adresseblokke]]
"blockiptext"	=> "Brug formularen herunder til at blokere for skriveadgangen fra en specifik IP-adresse eller et brugernavn. Dette må kun gøres for at forhindre vandalisme og skal være i overensstemmelse med [[{{MediaWiki:policy-url}}|{{SITENAME}}s politik]]. Angiv en specifik begrundelse herunder (for eksempel med angivelse af sider der har været udsat for vandalisme). Udløbet (expiry) angives i GNUs standardformat, som er beskrevet i [http://www.gnu.org/software/tar/manual/html_chapter/tar_7.html vejledningen til tar] (på engelsk), fx \"1 hour\", \"2 days\", \"next Wednesday\", \"1 January 2017\". Alternativt kan en blokering gøres uendelig (skriv \"indefinite\" eller \"infinite\"). For oplysninger om blokering af IP-adresseblokke, se [[meta:Range blocks|IP-adresseblokke]] (på engelsk). For at ophæve en blokering, se [[Special:Ipblocklist|listen over blokerede IP-adresser og brugernavne]].",
"ipaddress"		=> "IP-adresse/brugernavn",
'ipbexpiry'		=> 'Udløb',
"ipbreason"		=> "Begrundelse",
"ipbsubmit"		=> "Bloker denne bruger",
"badipaddress"	=> "IP-adressen/brugernavnet er udformet forkert eller eksistere ikke.",
"blockipsuccesssub" => "Blokering udført med succes",
"blockipsuccesstext" => "\"$1\" er blevet blokeret.
<br />Se [[Special:Ipblocklist|IP blokeringslisten]] for alle blokeringer.",
"unblockip"		=> "Ophæv blokering af bruger",
"unblockiptext"	=> "Brug formularen herunder for at gendanne skriveadgangen
for en tidligere blokeret IP-adresse eller bruger.",
"ipusubmit"		=> "Ophæv blokeringen af denne adresse",
"ipblocklist"	=> "Liste over blokerede IP-adresser og brugernavne",
'blocklistline'	=> '$1, $2 blokerede $3 ($4)',
'infiniteblock' => 'udløber infinite', //fixme
'expiringblock' => 'udløber $1',
"blocklink"		=> "bloker",
"unblocklink"	=> "ophæv blokering",
"contribslink"	=> "bidrag",
"autoblocker"	=> "Automatisk blokeret fordi du deler IP-adresse med \"$1\". Begrundelse \"$2\".",
'blocklogpage'	=> 'Blokeringslog',
'blocklogentry'	=> 'blokerede "$1" med $2 som udløbstid',
'blocklogtext'	=> 'Dette er en liste over blokerede brugere og ophævede blokeringer af brugere. Automatisk blokerede IP-adresser er ikke anført her. Se [[Special:Ipblocklist|blokeringslisten]] for den nuværende liste over blokerede brugere.',
'unblocklogentry'	=> 'ophævede blokering af "$1"',
'range_block_disabled'	=> 'Sysop-muligheden for at oprette blokeringsklasser er slået fra.',
'ipb_expiry_invalid'	=> 'Udløbstiden er ugyldig.',
'ip_range_invalid'	=> "Ugyldigt IP-interval.",
'proxyblocker'	=> 'Proxy-blokering',
'proxyblockreason'	=> 'Din IP-adresse er blevet blokeret fordi den er en såkaldt \'\'åben proxy\'\'. Kontakt din Internet-udbyder eller tekniske hotline og oplyse dem om dette alvorlige sikkerhedsproblem.',
'proxyblocksuccess'	=> "Færdig.",

# Developer tools
#
"lockdb"		=> "Lås database",
"unlockdb"		=> "Lås database op",
"lockdbtext"	=> "At låse databasen vil forhindre alle brugere i at kunne redigere sider, ændre indstillinger, redigere overvågningslister og andre ting der kræver ændringer i databasen. Bekræft venligst at du har til hensigt at gøre dette, og at du vil låse databasen op, når din vedligeholdelse er overstået.",
"unlockdbtext"	=> "At låse databasen op vil gøre, at alle brugere igen
kan redigere sider, ændre deres indstillinger, redigere deres
overvågningsliste, og andre ting der kræver ændringer i databasen.
Bekræft venligst at du har til hensigt at gøre dette.",
"lockconfirm"	=> "Ja, jeg vil virkelig låse databasen.",
"unlockconfirm"	=> "Ja, jeg vil virkelig låse databasen op.",
"lockbtn"		=> "Lås databasen",
"unlockbtn"		=> "Lås databasen op",
"locknoconfirm" => "Du har ikke bekræftet handlingen.",
"lockdbsuccesssub" => "Databasen er nu låst",
"unlockdbsuccesssub" => "Databasen er nu låst op",
"lockdbsuccesstext" => "Mediawikidatabasen er låst. <br />Husk at fjerne låsen når du er færdig med din vedligeholdelse.",
"unlockdbsuccesstext" => "Mediawikidatabasen er låst op.",

# Make sysop
'rightslogtext'		=> 'Dette er en log over ændringer i brugeres rettigheder.',

# Move page
#
"movepage"		=> "Flyt side",
"movepagetext"	=> "Når du bruger formularen herunder vil du få omdøbt en
side og flyttet hele sidens historie til det nye navn.
Den gamle titel vil blive en omdirigeringsside til den nye titel.
Henvisninger til den gamle titel vil ikke blive ændret. Sørg for at
tjekke for dobbelte eller dårlige omdirigeringer.
Du er ansvarlig for, at alle henvisninger stadig peger derhen, hvor det er
meningen de skal pege.

Bemærk at siden '''ikke''' kan flyttes hvis der allerede er en side
med den nye titel, medmindre den side er tom eller er en omdirigering
uden nogen historie. Det betyder at du kan flytte en side tilbage hvor
den kom fra, hvis du kommer til at lave en fejl.

<b>ADVARSEL!</b>
Dette kan være en drastisk og uventet ændring for en populær side;
vær sikker på, at du forstår konsekvenserne af dette før du
fortsætter.",
"movepagetalktext" => "Den tilhørende diskussionsside, hvis der er en,
vil automatisk blive flyttet med siden '''medmindre:'''
*Du flytter siden til et andet navnerum,
*En ikke-tom diskussionsside allerede eksisterer under det nye navn, eller
*Du fjerner markeringen i boksen nedenunder.

I disse tilfælde er du nødt til at flytte eller sammenflette siden manuelt.",
"movearticle"	=> "Flyt side",
"movenologin"	=> "Ikke logget på",
"movenologintext" => "Du skal være registreret bruger og være [[Special:Userlogin|logget på]]
for at flytte en side.",
"newtitle"		=> "Til ny titel",
"movepagebtn"	=> "Flyt side",
"pagemovedsub"	=> "Flytning gennemført",
"pagemovedtext" => "Siden \"[[$1]]\" er flyttet til \"[[$2]]\".",
"articleexists" => "En side med det navn eksisterer allerede, eller det
navn du har valgt er ikke gyldigt. Vælg et andet navn.",
"talkexists"	=> "Siden blev flyttet korrekt, men den tilhørende
diskussionsside kunne ikke flyttes, fordi der allerede eksisterer en
med den nye titel. Du er nødt til at flette dem sammen manuelt.",
"movedto"		=> "flyttet til",
"movetalk"		=> "Flyt også \"diskussionssiden\", hvis den eksisterer.",
"talkpagemoved" => "Den tilhørende diskussionsside blev også flyttet.",
"talkpagenotmoved" => "Den tilhørende diskussionsside blev
<strong>ikke</strong> flyttet.",
'1movedto2'		=> "$1 flyttet til $2",
'1movedto2_redir' => '$1 flyttet til $2 over en omdirigering',
'movelogpage' => 'Flyttelog',
'movelogpagetext' => 'Nedenfor er en liste over flyttede sider.',
'movereason'	=> 'Begrundelse',
'revertmove'	=> 'gendan',
'delete_and_move' => 'Slet og flyt',
'delete_and_move_text'	=>
'==Sletning nødvendig==

Målartiklen "[[$1]]" eksisterer allerede. Vil du slette den for at lave plads til flytningen?',
'delete_and_move_reason' => 'Slet for at lave plads til flyningen',
'selfmove' => "Begge sider har samme navn. Man kan ikke flytte en side oven i sig selv.",
'immobile_namespace' => "Måltitlen er en speciel type; man kan ikke flytte sider ind i det navnerum.",

# Export

'export'		=> 'Eksportér sider',
'exporttext'	=> 'Du kan eksportere teksten og historikken fra en eller flere sider i et simpelt XML format. Dette kan bruges til at indsætte siderne i en anden wiki der bruger MediaWiki softwaren, eller du kan beholde den for din egen fornøjelses skyld',
'exportcuronly'	=> 'Eksportér kun den nuværende version, ikke hele historikken',

# Namespace 8 related

'allmessages'	=> 'Alle beskeder',
'allmessagesname' => 'Navn',
'allmessagesdefault' => 'Standard tekst',
'allmessagescurrent' => 'Nuværende tekst',
'allmessagestext'	=> 'Dette er en liste over alle beskeder i MediaWiki: navnerummet.',
'allmessagesnotsupportedUI' => 'Dit aktuelle grænsefladesprog <b>$1</b> er ikke understøttet af Special:AllMessages på dette websted.',
'allmessagesnotsupportedDB' => 'Special:AllMessages ikke understøttet fordi wgUseDatabaseMessages er slået fra.',

# Thumbnails

'thumbnail-more'	=> 'Forstør',
'missingimage'		=> "<b>Mangler billede</b><br /><i>$1</i>",
'filemissing'		=> 'Filen mangler',

# Special:Import
'import'	=> 'Importere sider',
'importinterwiki' => 'Transwiki import',
'importtext'	=> 'Eksportér filen fra kilde-wiki\'en ved hjælp af værktøjet Special:Export, gem den på din harddisk og læg den op her.',
'importfailed'	=> "Importering fejlede: $1",
'importnotext'	=> 'Tom eller ingen tekst',
'importsuccess'	=> 'Importen lykkedes!',
'importhistoryconflict' => 'Der er en konflikt i versionhistorikken (siden kan have været importeret før)',
'importnosources' => 'No transwiki import sources have been defined and direct history uploads are disabled.',

# Keyboard access keys for power users
'accesskey-search' => 'f',
'accesskey-minoredit' => 'i',
'accesskey-save' => 's',
'accesskey-preview' => 'p',
'accesskey-diff' => 'v',
'accesskey-compareselectedversions' => 'v',

# tooltip help for some actions, most are in Monobook.js
'tooltip-search' => 'Søg i {{SITENAME}}',
'tooltip-minoredit' => 'Marker dette som en mindre ændring',
'tooltip-save' => 'Gem dine ændringer',
'tooltip-preview' => 'Forhåndsvis dine ændringer, brug venligst denne funktion inden du gemmer!',
'tooltip-diff' => 'Vis hvilke ændringer du har lavet i teksten.',
'tooltip-compareselectedversions' => 'Se forskellene imellem de to valgte versioner af denne side.',
'tooltip-watch' => 'Tilføj denne side til din overvågningsliste',

# stylesheets
#'monobook.css' => '/* edit this file to customize the monobook skin for the entire site */',
#'monobook.js' => '/* Deprecated; use [[MediaWiki:common.js]] */',

# Metadata
'nodublincore' => 'Dublin Core RDF-metadata er slået fra på denne server.',
'nocreativecommons' => 'Creative Commons RDF-metadata er slået fra på denne server.',
'notacceptable' => 'Wiki-serveren kan ikke levere data i et format, som din klient understøtter.',

# Attribution

'anonymous' => "Anonym(e) bruger(e) af {{SITENAME}}",
'siteuser' => "{{SITENAME}} bruger $1",
'lastmodifiedatby' => "Denne side blev senest ændret $2, $1 af $3.",
'and' => 'og',
'othercontribs' => "Baseret på arbejde af $1.",
'others' => 'andre',
'siteusers' => "{{SITENAME}} bruger(e) $1",
'creditspage' => 'Sidens forfattere',
'nocredits' => 'Der er ingen forfatteroplysninger om denne side.',

# Spam protection

'spamprotectiontitle' => 'Spambeskyttelsesfilter',
# problem with link: [[m:spam blacklist]]
# problem with link: [[m:Special:Listadmins|m:administrator]]
'spamprotectiontext' => 'Siden du prøver at få adgang til er blokeret af spamfilteret. Dette skyldes sandsynligvis et link til et eksternt websted. Se [[m:spam blacklist]] for en komplet liste af blokerede websteder. Hvis du mener at spamfilteret blokerede redigeringen ved en fejl, så kontakt en [[m:Special:Listadmins|m:administrator]]. Det følgende er et udtræk af siden der bevirkede blokeringen:',
'spamprotectionmatch' => 'Følgende tekst udløste vores spamfilter: $1',
'subcategorycount' => "Der er $1 underkategorier i denne kategori.",
'categoryarticlecount' => "Der er $1 artikler i denne kategori.",
'listingcontinuesabbrev' => " forts.",

# Info page
"infosubtitle" => "Information om siden",
"numedits" => "Antal redigeringer (artikel): $1",
"numtalkedits" => "Antal redigeringer (diskussionsside): $1",
"numwatchers" => "Antal overvågere: $1",
"numauthors" => "Antal forskellige forfattere (artikel): $1",
"numtalkauthors" => "Antal forskellige forfattere (diskussionsside): $1",

# Math options
'mw_math_png' => "Vis altid som PNG",
'mw_math_simple' => "HTML hvis meget simpel ellers PNG",
'mw_math_html' => "HTML hvis muligt ellers PNG",
'mw_math_source' => "Lad være som TeX (for tekstbrowsere)",
'mw_math_modern' => "Anbefalet til moderne browsere",
'mw_math_mathml' => "MathML hvis muligt",

# Patrolling
'markaspatrolleddiff'   => "Markér som patruljeret",
'markaspatrolledtext'   => "Markér denne artikel som patruljeret",
'markedaspatrolled'     => "Markeret som patruljeret",
'markedaspatrolledtext' => "Den valgte revision er nu markeret som patruljeret.",
'rcpatroldisabled'      => "Seneste ændringer-patruljeringen er slået fra",
'rcpatroldisabledtext'  => "Funktionen til seneste ændringer-patruljeringen er pt. slået fra.",

# Monobook.js: tooltips and access keys for monobook
'monobook.js' => '/* Deprecated; use [[MediaWiki:common.js]] */',

'accesskey-pt-userpage' => '.',
'tooltip-pt-userpage' => 'Min brugerside',
'accesskey-pt-anonuserpage' => '.',
'tooltip-pt-anonuserpage' => 'Brugersiden for den ip-adresse du redigerer som',
'accesskey-pt-mytalk' => 'n',
'tooltip-pt-mytalk' => 'Min diskussionsside',
'accesskey-pt-anontalk' => 'n',
'tooltip-pt-anontalk' => 'Diskussion om redigeringer fra denne ip-adresse',
'accesskey-pt-preferences' => '',
'tooltip-pt-preferences' => 'Mine indstillinger',
'accesskey-pt-watchlist' => 'l',
'tooltip-pt-watchlist' => 'Listen over sider du overvåger for ændringer.',
'accesskey-pt-mycontris' => 'y',
'tooltip-pt-mycontris' => 'Listen over dine bidrag',
'accesskey-pt-login' => 'o',
'tooltip-pt-login' => 'Du opfordres til at logge på, men det er ikke obligatorisk.',
'accesskey-pt-anonlogin' => 'o',
'tooltip-pt-anonlogin' => 'Du opfordres til at logge på, men det er ikke obligatorisk',
'accesskey-pt-logout' => '',
'tooltip-pt-logout' => 'Log af',
'accesskey-ca-talk' => 't',
'tooltip-ca-talk' => 'Diskussion om indholdet på siden',
'accesskey-ca-edit' => 'e',
'tooltip-ca-edit' => 'Du kan redigere denne side. Brug venligst forhåndsvisning før du gemmer.',
'accesskey-ca-addsection' => '+',
'tooltip-ca-addsection' => 'Tilføj en kommentar til denne diskussion.',
'accesskey-ca-viewsource' => 'e',
'tooltip-ca-viewsource' => 'Denne side er beskyttet. Du kan kigge på kildekoden.',
'accesskey-ca-history' => 'h',
'tooltip-ca-history' => 'Tidligere versioner af denne side.',
'accesskey-ca-protect' => '=',
'tooltip-ca-protect' => 'Beskyt denne side',
'accesskey-ca-delete' => 'd',
'tooltip-ca-delete' => 'Slet denne side',
'accesskey-ca-undelete' => 'd',
'tooltip-ca-undelete' => 'Gendan de redigeringer der blev lavet på denne side før den blev slettet',
'accesskey-ca-move' => 'm',
'tooltip-ca-move' => 'Flyt denne side',
'accesskey-ca-watch' => 'w',
'tooltip-ca-watch' => 'Sæt denne side på din overvågningsliste',
'accesskey-ca-unwatch' => 'w',
'tooltip-ca-unwatch' => 'Fjern denne side fra din overvågningsliste',
'accesskey-search' => 'f',
'tooltip-search' => 'Søg på denne wiki',
'accesskey-p-logo' => '',
'tooltip-p-logo' => 'Forsiden',
'accesskey-n-mainpage' => 'z',
'tooltip-n-mainpage' => 'Besøg forsiden',
'accesskey-n-portal' => '',
'tooltip-n-portal' => 'Om projektet, hvad du kan gøre, hvor tingene findes',
'accesskey-n-currentevents' => '',
'tooltip-n-currentevents' => 'Find baggrundsinformation om aktuelle begivenheder',
'accesskey-n-recentchanges' => 'r',
'tooltip-n-recentchanges' => 'Listen over de seneste ændringer i wikien.',
'accesskey-n-randompage' => 'x',
'tooltip-n-randompage' => 'Gå til en tilfældig artikel',
'accesskey-n-help' => '',
'tooltip-n-help' => 'Hvordan gør jeg ...',
'accesskey-n-sitesupport' => '',
'tooltip-n-sitesupport' => 'Støt os',
'accesskey-t-whatlinkshere' => 'j',
'tooltip-t-whatlinkshere' => 'Liste med alle sider som henviser hertil',
'accesskey-t-recentchangeslinked' => 'k',
'tooltip-t-recentchangeslinked' => 'Seneste ændringer i sider som denne side henviser til',
'accesskey-feed-rss' => '',
'tooltip-feed-rss' => 'RSS-feed for denne side',
'accesskey-feed-atom' => '',
'tooltip-feed-atom' => 'Atom-feed for denne side',
'accesskey-t-contributions' => '',
'tooltip-t-contributions' => 'Se denne brugers bidrag',
'accesskey-t-emailuser' => '',
'tooltip-t-emailuser' => 'Send en e-mail til denne bruger',
'accesskey-t-upload' => 'u',
'tooltip-t-upload' => 'Upload et billede eller anden mediafil',
'accesskey-t-specialpages' => 'q',
'tooltip-t-specialpages' => 'Liste med alle specielle sider',
'accesskey-ca-nstab-main' => 'c',
'tooltip-ca-nstab-main' => 'Se indholdet',
'accesskey-ca-nstab-user' => 'c',
'tooltip-ca-nstab-user' => 'Se brugersiden',
'accesskey-ca-nstab-media' => 'c',
'tooltip-ca-nstab-media' => 'Se mediasiden',
'accesskey-ca-nstab-special' => '',
'tooltip-ca-nstab-special' => 'Dette er en speciel side; man kan ikke redigere sådanne sider.',
//'accesskey-ca-nstab-project' => 'a',
//'tooltip-ca-nstab-project' => 'Se Wikipediasiden',
'accesskey-ca-nstab-image' => 'c',
'tooltip-ca-nstab-image' => 'Se billedsiden',
'accesskey-ca-nstab-mediawiki' => 'c',
'tooltip-ca-nstab-mediawiki' => 'Se systembeskeden',
'accesskey-ca-nstab-template' => 'c',
'tooltip-ca-nstab-template' => 'Se skabelonen',
'accesskey-ca-nstab-help' => 'c',
'tooltip-ca-nstab-help' => 'Se hjælpesiden',
'accesskey-ca-nstab-category' => 'c',
'tooltip-ca-nstab-category' => 'Se kategorisiden',

# image deletion
'deletedrevision' => 'Slettede gammel version $1.',

# browsing diffs
'previousdiff' => '← Gå til forrige forskel',
'nextdiff' => 'Gå til næste forskel →',

'imagemaxsize' => 'Begræns størrelsen af billeder på billedsiderne til:',
'thumbsize'	=> 'Thumbnail størrelse :',
'showbigimage' => 'Download en version i høj opløsning ($1x$2, $3 KB)',

'newimages' => 'Galleri med de nyeste billeder',
'noimages'  => 'Ingenting at se.',


# labels for User: and Title: on Special:Log pages
'specialloguserlabel' => 'Bruger:',
'speciallogtitlelabel' => 'Titel:',

'passwordtooshort' => 'Dit kodeord er for kort. Det skal være mindst $1 tegn langt.',

# Media Warning
'mediawarning' => "'''Advarsel''', denne filtype kan muligvis indeholde skadelig kode, du kan beskadige dit system hvis du udfører den.
<hr />",
# external editor support
'edit-externally' => 'Rediger denne fil med en ekstern editor',
'edit-externally-help' => 'Se [http://meta.wikimedia.org/wiki/Help:External_editors setup instruktionerne] for mere information.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'alle',
'imagelistall' => 'alle',
'watchlistall1' => 'alle',
'watchlistall2' => 'alle',
'namespacesall' => 'alle',

);


?>

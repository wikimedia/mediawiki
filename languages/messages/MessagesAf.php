<?php 

$skinNames = array(
	'standard' => 'Standaard',
	'nostalgia' => 'Nostalgie',
	'cologneblue' => 'Keulen blou',
);

$namespaceNames = array(
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => 'Spesiaal',
	NS_MAIN           => '',
	NS_TALK           => 'Bespreking',
	NS_USER           => 'Gebruiker',
	NS_USER_TALK      => 'Gebruikerbespreking',
	# NS_PROJECT set by $wgMetaNamespace,
	NS_PROJECT_TALK   => '$1bespreking',
	NS_IMAGE          => 'Beeld',
	NS_IMAGE_TALK     => 'Beeldbespreking',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'MediaWikibespreking',
	NS_TEMPLATE       => 'Sjabloon',
	NS_TEMPLATE_TALK  => 'Sjabloonbespreking',
	NS_HELP           => 'Hulp',
	NS_HELP_TALK      => 'Hulpbespreking',
	NS_CATEGORY       => 'Kategorie',
	NS_CATEGORY_TALK  => 'Kategoriebespreking'
);

# South Africa uses space for thousands and comma for decimal
# Reference: AWS Reël 7.4 p. 52, 2002 edition
# glibc is wrong in this respect in some versions
$separatorTransformTable = array( ',' => "\xc2\xa0", '.' => ',' );
$linkTrail = "/^([a-z]+)(.*)\$/sD";

$messages = array(
# User preference toggles
'tog-underline'        => 'Onderstreep skakels.',
'tog-highlightbroken'  => 'Wys gebroke skakels <a href="" class="new">so</a> of so<a href="" class="internal">?</a>).',
'tog-justify'          => 'Justeer paragrawe.',
'tog-hideminor'        => 'Moenie klein wysigings in die nuwe wysigingslys wys nie.',
'tog-usenewrc'         => 'Verbeterde nuwe wysigingslys (vir moderne blaaiers).',
'tog-numberheadings'   => 'Automatiese nommer opskrifte.',
'tog-showtoolbar'      => 'Show edit toolbar',
'tog-editondblclick'   => 'Wysig blaaie met dubbelkliek (JavaScript).',
'tog-rememberpassword' => 'Onthou wagwoord oor sessies.',
'tog-editwidth'        => 'Wysigingsboks met volle wydte.',
'tog-watchdefault'     => 'Lys nuwe en gewysigde bladsye.',
'tog-minordefault'     => 'Merk alle wysigings automaties as klein by verstek.',
'tog-previewontop'     => 'Wys voorskou bo wysigingsboks.',

# Dates
'sunday'    => 'Sondag',
'monday'    => 'Maandag',
'tuesday'   => 'Dinsdag',
'wednesday' => 'Woensdag',
'thursday'  => 'Donderdag',
'friday'    => 'Vrydag',
'saturday'  => 'Saterdag',
'january'   => 'Januarie',
'february'  => 'Februarie',
'march'     => 'Maart',
'april'     => 'April',
'may_long'  => 'Mei',
'june'      => 'Junie',
'july'      => 'Julie',
'august'    => 'Augustus',
'september' => 'September',
'october'   => 'Oktober',
'november'  => 'November',
'december'  => 'Desember',
'jan'       => '01',
'feb'       => '02',
'mar'       => '03',
'apr'       => '04',
'may'       => '05',
'jun'       => '06',
'jul'       => '07',
'aug'       => '08',
'sep'       => '09',
'oct'       => '10',
'nov'       => '11',
'dec'       => '12',

'about'         => 'Omtrent',
'cancel'        => 'Kanselleer',
'qbfind'        => 'Vind',
'qbbrowse'      => 'Snuffel',
'qbedit'        => 'Wysig',
'qbpageoptions' => 'Bladsy opsies',
'qbpageinfo'    => 'Bladsy inligting',
'qbmyoptions'   => 'My opsies',
'mypage'        => 'My bladsy',
'mytalk'        => 'My besprekings',

'errorpagetitle'    => 'Fout',
'returnto'          => 'Keer terug na $1.',
'help'              => 'Hulp',
'search'            => 'Soek',
'searchbutton'      => 'Soek',
'go'                => 'Wys',
'searcharticle'     => 'Wys',
'history'           => 'Ouer weergawes',
'printableversion'  => 'Drukbare weergawe',
'editthispage'      => 'Wysig hierdie bladsy',
'deletethispage'    => 'Skrap bladsy',
'protectthispage'   => 'Beskerm hierdie bladsy',
'unprotectthispage' => 'Laat toe dat bladsy gewysig word',
'newpage'           => 'Nuwe bladsy',
'talkpage'          => 'Bespreek hierdie bladsy',
'articlepage'       => 'Lees artikel',
'userpage'          => 'Lees gebruikersbladsy',
'projectpage'       => 'Lees metabladsy',
'imagepage'         => 'Lees bladsy oor prent',
'viewtalkpage'      => 'Lees bespreking',
'otherlanguages'    => 'Ander tale',
'redirectedfrom'    => '(Van $1 aangestuur.)',
'lastmodifiedat'    => 'Laaste wysiging op $2, $1.', # $1 date, $2 time
'viewcount'         => 'Hierdie bladsy is al $1 keer aangevra.',
'protectedpage'     => 'Beskermde bladsy',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Inligting oor {{SITENAME}}',
'aboutpage'         => '{{ns:4}}:Omtrent',
'bugreports'        => 'Foutrapporte',
'bugreportspage'    => '{{ns:4}}:FoutRapporte',
'copyrightpagename' => '{{SITENAME}} kopiereg',
'copyrightpage'     => '{{ns:4}}:kopiereg',
'currentevents'     => 'Huidige gebeure',
'edithelp'          => 'Wysighulp',
'edithelppage'      => "{{ns:4}}:Hoe_word_'n_bladsy_gewysig",
'faq'               => 'Gewilde vrae',
'faqpage'           => '{{ns:4}}:GewildeVrae',
'helppage'          => '{{ns:4}}:Hulp',
'mainpage'          => 'Tuisblad',
'policy-url'        => 'Project:Policy',

'ok'              => 'Aanvaar',
'retrievedfrom'   => 'Ontsluit van "$1"',
'newmessageslink' => 'nuwe boodskappe',

# Main script and global functions
'nosuchaction'      => 'Ongeldige aksie',
'nosuchactiontext'  => 'Onbekende aksie deur die adres gespesifeer',
'nosuchspecialpage' => 'Ongeldige spesiale bladsy',
'nospecialpagetext' => 'Ongeldige spesiale bladsy gespesifeer.',

# General errors
'error'           => 'Fout',
'databaseerror'   => 'Databasisfout',
'dberrortext'     => 'Sintaksisfout in databasisnavraag.
Die laaste navraag was:
<blockquote><tt>$1</tt></blockquote>
van funksie "<tt>$2</tt>".
MySQL foutboodskap "<tt>$3: $4</tt>".',
'noconnect'       => 'Kon nie met databasis op $1 konnekteer nie',
'nodb'            => 'Kon nie databasis $1 selekteer nie',
'readonly'        => 'Databasis gesluit',
'enterlockreason' => 'Rede vir die sluiting,
en beraming van wanneer ontsluiting sal plaas vind',
'readonlytext'    => 'Die {{SITENAME}} databasis is tans gesluit vir nuwe
artikelwysigings, waarskynlik vir roetine databasisonderhoud,
waarna dit terug sal wees na normaal.
Die administreerder wat dit gesluit het se verduideliking:
<p>$1',
'missingarticle'  => 'Die databasis het nie die teks van die veronderstelde bladsy "$1" gekry nie.
Nie databasisfout nie, moontlik sagtewarefout.
Raporteer die adres asseblief aan enige administrateur.',
'internalerror'   => 'Interne fout',
'filecopyerror'   => 'Kon nie lêer van "$1" na "$2" kopieer nie.',
'filerenameerror' => 'Kon nie lêernaam van "$1" na "$2" wysig nie.',
'filedeleteerror' => 'Kon nie lêer "$1" skrap nie.',
'filenotfound'    => 'Kon nie lêer "$1" vind nie.',
'unexpected'      => 'Onverwagte waarde: "$1"="$2".',
'formerror'       => 'Fout: kon vorm nie stuur nie',
'badarticleerror' => 'Die aksie kon nie op hierdie bladsy uitgevoer word nie.',
'cannotdelete'    => 'Kon nie die bladsy of prent skrap nie, iemand anders het dit miskien reeds geskrap.',
'badtitle'        => 'Ongeldige titel',
'badtitletext'    => "Die bladsytitel waarvoor gevra is, is ongeldig, leeg, of
'n verkeerd geskakelde tussen-taal of tussen-wiki titel.",
'perfdisabled'    => 'Hierdie funksie is afgeskakel tydens spitstoegangsure vir verrigtingsredes, probeer weer tussen 02:00z en 14:00z (Universeel Gekoördineerde Tyd - UGT).',

# Login and logout pages
'logouttitle'           => 'Teken uit',
'logouttext'            => "Jy is nou uitgeteken, en kan aanhou om
{{SITENAME}} anoniem te gebruik; of jy kan inteken as dieselfde of 'n ander gebruiker.",
'welcomecreation'       => '<h2>Welkom, $1.</h2><p>Jou rekening is geskep;
moenie vergeet om jou persoonlike voorkeure te stel nie.',
'loginpagetitle'        => 'Teken in',
'yourname'              => 'Jou gebruikersnaam',
'yourpassword'          => 'Jou wagwoord',
'yourpasswordagain'     => 'Tik weer jou wagwoord in',
'remembermypassword'    => 'Onthou my wagwoord oor sessies.',
'loginproblem'          => '<b>Daar was probleme met jou intekening.</b><br />Probeer weer.',
'alreadyloggedin'       => '<strong>Gebruiker $1, jy is reeds ingeteken.</strong><br />',
'login'                 => 'Teken in',
'userlogin'             => 'Teken in',
'logout'                => 'Teken uit',
'userlogout'            => 'Teken uit',
'createaccount'         => 'Kies nuwe wagwoord',
'badretype'             => 'Die wagwoorde wat jy ingetik het, is nie dieselfde nie.',
'userexists'            => "Die gebruikersnaam wat jy gebruik het, is alreeds gebruik. Kies asseblief 'n ander gebruikersnaam.",
'youremail'             => 'Jou e-pos',
'yournick'              => 'Jou bynaam (vir stempel)',
'loginerror'            => 'Intekenfout',
'noname'                => 'Ongeldige gebruikersnaam.',
'loginsuccesstitle'     => 'Suksesvolle intekening',
'loginsuccess'          => 'Jy is ingeteken by {{SITENAME}} as "$1".',
'nosuchuser'            => 'Daar is geen "$1" gebruikersnaam nie.
Maak seker dit is reg gespel, of gebruik die vorm hier onder om \'n nuwe rekening te skep.',
'wrongpassword'         => 'Ongeldige wagwoord, probeer weer.',
'mailmypassword'        => 'Stuur my wagwword na my e-pos adres.',
'passwordremindertitle' => 'Wagwoordwenk van {{SITENAME}}',
'passwordremindertext'  => 'Iemand (waarskynlik jy, van IP-adres $1)
het gevra dat ons vir jou \'n nuwe {{SITENAME}} wagwoord stuur.
Die wagwoord vir gebruiker "$2" is nou "$3".
Teken asseblief in en verander jou wagwoord.',
'noemail'               => 'Daar is geen e-pos adres vir gebruiker "$1" nie.',
'passwordsent'          => 'Nuwe wagwoord gestuur na e-posadres vir "$1".
Teken asseblief in na jy dit ontvang het.',

# Edit pages
'summary'          => 'Opsomming',
'minoredit'        => 'Klein wysiging',
'watchthis'        => 'Hou bladsy dop',
'savearticle'      => 'Stoor bladsy',
'preview'          => 'Voorskou',
'showpreview'      => 'Wys voorskou',
'blockedtitle'     => 'Gebruiker is geblokkeer',
'blockedtext'      => "Jou gebruikersnaam of IP-adres is deur $1 geblokkeer:
<br />''$2''<p>Jy mag $1 of een van die ander [[{{MediaWiki:grouppage-sysop}}|administreerders]] kontak
om dit te bespreek.",
'newarticle'       => '(Nuut)',
'newarticletext'   => "Die bladsy waarna geskakel is, bestaan nie.
Om 'n nuwe bladsy te skep, tik in die invoerboks hier onder. Lees die [[{{MediaWiki:helppage}}|hulp bladsy]]
vir meer inligting.
Indien jy per ongeluk hier is, gebruik jou blaaier se '''terug''' knop.",
'anontalkpagetext' => "---- ''Dit is die besprekingsbladsy vir 'n anonieme gebruiker wat nie 'n rekening geskep het nie. Ons moet dus hul IP-adres gebruik om hulle te identifiseer. So 'n IP-adres kan deur verskeie gebruikers gedeel word. Indien jy 'n anonieme gebruiker is wat voel dat oneerbiedige komentaar aan jou gerig is, [[Special:Userlogin|skep 'n rekening of teken in]] om verwarring te voorkom met ander anonieme gebruikers.''",
'noarticletext'    => '(Daar is tans geen inligting vir hierdie artikel nie.)',
'updated'          => '(Gewysig)',
'note'             => '<strong>Nota:</strong>',
'previewnote'      => "Onthou dat dit slegs 'n voorskou is en nog nie gestoor is nie!",
'previewconflict'  => 'Hierdie voorskou reflekteer die teks in die boonste invoerboks soos dit sal lyk as jy dit stoor.',
'editing'          => 'Besig om $1 te wysig',
'editinguser'      => 'Besig om $1 te wysig',
'editconflict'     => 'Wysigingskonflik: $1',
'explainconflict'  => 'Iemand anders het hierdie bladsy gewysig sedert jy dit begin verander het.
Die boonste invoerboks het die teks wat tans bestaan.
Jou wysigings word in die onderste invoerboks gewys.
Jy sal jou wysigings moet saamsmelt met die huidige teks.
<strong>Slegs</strong> die teks in die boonste invoerboks sal gestoor word wanneer jy "Stoor bladsy" druk.<br />',
'yourtext'         => 'Jou teks',
'storedversion'    => 'Gestoorde weergawe',
'editingold'       => "<strong>Waarskuwing: jy is besig om 'n ou weergawe van hierdie bladsy te wysig.
As jy dit stoor, sal enige wysigings sedert hierdie wysiging verloor word.</strong>",
'yourdiff'         => 'Wysigings',
'longpagewarning'  => 'Waarskuwing: hierdie bladsy is $1 kilogrepe lank; sekere blaaiers
kan probleme hê met die wysiging va blaaie langer as 32 kilogrepe. Breek asseblief die bladsy op in kleiner dele.',

# History pages
'revhistory'      => 'Wysigingsgeskiedenis',
'nohistory'       => 'Daar is geen wysigingsgeskiedenis vir hierdie bladsy nie.',
'revnotfound'     => 'Wysiging nie gevind nie.',
'revnotfoundtext' => 'Die ou wysiging waarvoor jy gevra het, kon nie gevind word nie. Maak asseblief seker dat die adres wat jy gebruik
het om toegang te kry tot hierdie bladsy, reg is.',
'loadhist'        => 'Besig om bladsy wysigingsgeskiedenis te laai.',
'currentrev'      => 'Huidige wysiging',
'revisionasof'    => 'Wysiging soos op $1',
'cur'             => 'huidige',
'next'            => 'volgende',
'last'            => 'vorige',
'orig'            => 'oorspronklike',
'histlegend'      => 'Byskrif: (huidige) = verskil van huidige weergawe,
(vorige) = verskil van vorige weergawe, M = klein wysiging',

# Diffs
'difference'  => '(Verksil tussen weergawes)',
'loadingrev'  => 'Besig om weergawe van verskil te laai.',
'lineno'      => 'Lyn $1:',
'editcurrent' => 'Wysig die huidige weergawe van hierdie bladsy.',

# Search results
'searchresults'         => 'soekresultate',
'searchresulttext'      => 'Vir meer inligting oor {{SITENAME}} soekresultate, lees [[{{MediaWiki:helppage}}|{{int:help}}]].',
'searchsubtitle'        => 'Vir navraag "[[:$1]]"',
'searchsubtitleinvalid' => 'Vir navraag "$1"',
'badquery'              => 'Verkeerd gestelde navraag',
'badquerytext'          => 'Ons kon nie jou naavraag prosesseer nie.
Dit is miskien omdat jy gesoek het vir iets wat minder as drie letters bevat. Jy het miskien die navraag verkeerd ingetik.',
'matchtotals'           => 'Die navraag "$1" pas $2 artikeltitels
en teks in $3 artikels.',
'noexactmatch'          => "Geen bladsy met hierdie presiese titel bestaan nie, probeer 'n volteksnavraag.",
'titlematches'          => 'Artikeltitel resultate',
'notitlematches'        => 'Geen artikeltitel resultate nie',
'textmatches'           => 'Artikelteks resultate',
'notextmatches'         => 'Geen artikelteks resultate nie',
'prevn'                 => 'vorige $1',
'nextn'                 => 'volgende $1',
'viewprevnext'          => 'Kyk na ($1) ($2) ($3).',
'showingresults'        => 'Onder <b>$1</b> resultate, beginende met #<b>$2</b>.',
'nonefound'             => "<strong>Nota</strong>: onsuksesvolle navrae word gewoonlik veroorsaak deur 'n soektog met algemene
woorde wat nie geindekseer word nie, of spesifisering van meer as een woord (slegs blaaie wat alle navraagwoorde
bevat, word gewys).",
'powersearch'           => 'Soek',
'powersearchtext'       => '
Search in namespaces :<br />
$1<br />
$2 List redirects   Search for $3 $9',

# Preferences page
'preferences'              => 'Voorkeure',
'prefsnologin'             => 'Nie ingeteken nie',
'prefsnologintext'         => 'Jy moet [[Special:Userlogin|ingeteken wees]]
om voorkeure te spesifiseer.',
'prefsreset'               => 'Voorkeure is herstel.',
'qbsettings'               => 'Snelbalkvoorkeure',
'qbsettings-none'          => 'Geen.',
'qbsettings-fixedleft'     => 'Links vas.',
'qbsettings-fixedright'    => 'Regs vas.',
'qbsettings-floatingleft'  => 'Dryf links.',
'qbsettings-floatingright' => 'Dryf regs.',
'changepassword'           => 'Verander wagwoord',
'skin'                     => 'Omslag',
'math'                     => 'Verbeeld wiskunde',
'math_failure'             => 'Kon nie verbeeld nie',
'math_unknown_error'       => 'onbekende fout',
'math_unknown_function'    => 'onbekende funksie',
'math_lexing_error'        => 'leksikale fout',
'math_syntax_error'        => 'sintaksfout',
'saveprefs'                => 'Stoor voorkeure',
'resetprefs'               => 'Herstel voorkeure',
'oldpassword'              => 'Ou wagwoord',
'newpassword'              => 'Nuwe wagwoord',
'retypenew'                => 'Tik nuwe wagwoord weer in',
'textboxsize'              => 'Grootte van invoerboks',
'rows'                     => 'Rye',
'columns'                  => 'Kolomme',
'searchresultshead'        => 'Soekresultaat voorkeure',
'resultsperpage'           => 'Aantal resultate om te wys',
'contextlines'             => 'Aantal lyne per resultaat',
'contextchars'             => 'Karakters konteks per lyn',
'recentchangescount'       => 'Aantal titels in onlangse wysigings',
'savedprefs'               => 'Jou voorkeure is gestoor.',
'timezonetext'             => 'Aantal ure wat plaaslike tyd verskil van UGT.',
'localtime'                => 'Plaaslike tyd',
'timezoneoffset'           => 'Teenrekening',

# Recent changes
'recentchanges'   => 'Onlangse wysigings',
'rcnote'          => 'Hier onder is die laaste <strong>$1</strong> wysigings gedurende die laaste <strong>$2</strong> dae.',
'rcnotefrom'      => 'Hier onder is die wysigings sedert <b>$2</b> (tot by <b>$1</b> word gewys).',
'rclistfrom'      => 'Wys nuwe wysigings en begin by $1',
'rclinks'         => 'Wys die laaste $1 wysigings in die laaste $2 dae.',
'diff'            => 'verskil',
'hist'            => 'geskiedenis',
'hide'            => 'vat weg',
'show'            => 'wys',
'minoreditletter' => 'K',
'newpageletter'   => 'N',

# Recent changes linked
'recentchangeslinked' => 'Verwante veranderings',

# Upload
'upload'            => 'Laai lêer',
'uploadbtn'         => 'Laai lêer',
'reupload'          => 'Herlaai',
'reuploaddesc'      => 'Keer terug na die laaivorm.',
'uploadnologin'     => 'Nie ingeteken nie',
'uploadnologintext' => 'Teken eers in [[Special:Userlogin|logged in]]
om lêers te laai.',
'uploaderror'       => 'Laaifout',
'uploadtext'        => "'''STOP!''' Voor jy hier laai, lees en volg {{SITENAME}} se
[[{{MediaWiki:copyrightpage}}|beleid oor prentgebruik]].

Om prente wat voorheen gelaai is te sien of te soek, gaan na die
[[Special:Imagelist|lys van gelaaide prente]].
Laai van lêers en skrappings word aangeteken in die
[[{{ns:special}}:Log/upload|laailog]].

Gebruik die vorm hier onder om nuwe prente te laai wat jy ter illustrasie in jou artikels wil gebruik.
In die meeste webblaaiers sal jy 'n \"Browse...\" knop sien, wat jou bedryfstelsel se standaard lêeroopmaak dialoogblokkie sal oopmaak.
Deur 'n lêer in hierdie dialoogkassie te kies, vul jy die teksboks naas die knop met die naam van die lêer.
Jy moet ook die blokkie merk om te bevestig dat jy geen kopieregte skend deur die lêer op te laai nie.
Kliek die \"Laai\" knop om die laai af te handel.
Dit mag dalk 'n rukkie neem as jy 'n stadige internetverbinding het.

Die voorkeurformate is JPEG vir fotografiese prente, PNG vir tekeninge en ander ikoniese prente, en OGG vir klanklêers.
Gebruik asseblief beskrywende lêername om verwarring te voorkom.
Om die prent in 'n artikel te gebruik, gebruik 'n skakel met die formaat '''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:file.jpg]]</nowiki>''' of
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:file.png|alt text]]</nowiki>''' of
'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:file.ogg]]</nowiki>''' vir klanklêers.

Let asseblief op dat, soos met {{SITENAME}} bladsye, mag ander jou gelaaide lêers redigeer as hulle dink dit dien die ensiklopedie, en jy kan verhoed word om lêers te laai as jy die stelsel misbruik.",
'uploadlog'         => 'laailog',
'uploadlogpage'     => 'laai_log',
'uploadlogpagetext' => "Hier onder is 'n lys van die mees onlangse lêers wat gelaai is.
Alle tye is bedienertyd (UGT).",
'filename'          => 'Lêernaam',
'filedesc'          => 'Opsomming',
'uploadedfiles'     => 'Gelaaide lêers',
'minlength'         => 'Prentname moet ten minste drie letters lank wees.',
'badfilename'       => 'Prentnaam is verander na "$1".',
'successfulupload'  => 'Laai suksesvol',
'fileuploaded'      => 'Lêer "$1" suksesvol gelaai.
Volg asseblief hierdie skakel: ($2) na die beskrywingsbladsy en vul inligting in oor die die lêer, soos waar dit vandaan kom, wie het dit geskep en wanneer, en enige iets anders wat jy daarvan af weet.',
'uploadwarning'     => 'Laaiwaarskuwing',
'savefile'          => 'Stoor lêer',
'uploadedimage'     => 'Het "[[$1]]" gelaai',

# Image list
'imagelist'           => 'Prentelys',
'imagelisttext'       => 'Hier onder is a lys van $1 prente gesorteer $2.',
'getimagelist'        => 'Besig om prentelys te haal',
'ilsubmit'            => 'Soek',
'showlast'            => 'Wys laaste $1 prente gesorteer $2.',
'byname'              => 'volgens naam',
'bydate'              => 'volgens datum',
'bysize'              => 'volgens grootte',
'imgdelete'           => 'skrap',
'imgdesc'             => 'beskrywing',
'imglegend'           => 'Legende: (beskrywing) = wys/verander prent se beskrywing.',
'imghistory'          => 'Prentgeskiedenis',
'revertimg'           => 'gaan terug',
'deleteimg'           => 'skrap',
'deleteimgcompletely' => 'skrap',
'imghistlegend'       => 'Legende: (huidig) = dit is die huidige prent, (skrap) = skrap hierdie ou weergawe, (gaan terug) = gaan terug na hierdie ou weergawe.
<br /><i>Kliek die datum om die prent te sien wat op daardie datum gelaai is</i>.',
'imagelinks'          => 'Prentskakels',
'linkstoimage'        => 'Die volgende bladsye gebruik hierdie prent:',
'nolinkstoimage'      => 'Daar is geen bladsye wat hierdie prent gebruik nie.',

# Statistics
'statistics'    => 'Statistiek',
'sitestats'     => 'Werfstatistiek',
'userstats'     => 'Gebruikerstatistiek',
'sitestatstext' => 'Daar is \'n totaal van <b>$1</b> bladsye in die databasis.
Dit sluit "bespreek" bladsye in, bladsye oor {{SITENAME}}, minimale "verkorte"
bladsye, wegwysbladsye, en ander wat waarskynlik nie as artikels kwalifiseer nie.
Uitsluitend bogenoemde, is daar <b>$2</b> bladsye wat waarskynlik ware artikels is.<p>
Bladsye is al <b>$3</b> kere aangevra, en <b>$4</b> keer verander sedert die sagteware opgegradeer is (July 20, 2002).
Dit werk uit op gemiddeld <b>$5</b> veranderings per bladsy, en bladsye word <b>$6</b> keer per verandering aangevra.',
'userstatstext' => 'Daar is <b>$1</b> geregistreerde gebruikers.
<b>$2</b> van hulle is administrateurs (sien $3).',

'disambiguations'     => 'Bladsye wat onduidelikhede opklaar',
'disambiguationspage' => '{{ns:4}}:Links_to_disambiguating_pages',

'doubleredirects'     => 'Dubbele aansture',
'doubleredirectstext' => '<b>Let op:</b> Hierdie lys bevat moontlik false positiewe. Dit beteken gewoonlik dat daar nog teks met skakels onder die eerste #REDIRECT is.<br />
Elke ry bevat skakels na die eerste en die tweede aanstuur, asook die eerste reël van van die tweede aanstuurteks, wat gewoonlik die "regte" teikenbladsy gee waarna die eerste aanstuur behoort te wys.',

'brokenredirects'     => 'Stukkende aansture',
'brokenredirectstext' => "Die volgende aansture skakel na 'n bladsy wat nie bestaan nie.",

# Miscellaneous special pages
'nbytes'           => '$1 grepe',
'nlinks'           => '$1 skakels',
'nviews'           => '$1 keer aangevra',
'lonelypages'      => 'Weesbladsye',
'unusedimages'     => 'Ongebruikte prente',
'popularpages'     => 'Populêre bladsye',
'wantedpages'      => 'Gesogte bladsye',
'allpages'         => 'Alle bladsye',
'randompage'       => 'Lukrake bladsy',
'shortpages'       => 'Kort bladsye',
'longpages'        => 'Lang bladsye',
'listusers'        => 'Gebruikerslys',
'specialpages'     => 'Spesiale bladsye',
'spheading'        => 'Spesiale bladsye',
'rclsub'           => '(na bladsye waarna "$1" skakel)',
'newpages'         => 'Nuwe bladsye',
'movethispage'     => 'Skuif hierdie bladsy',
'unusedimagestext' => "<p>Let asseblief op dat ander webwerwe, soos die internasionale {{SITENAME}}s, dalk met 'n direkte URL na 'n prent skakel, so die prent sal dus hier verskyn al word dit aktief gebruik.",

# Book sources
'booksources' => 'Boekbronne',

# E-mail user
'mailnologin'     => 'Geen verstuuradres',
'mailnologintext' => "Jy moet [[Special:Userlogin|ingeteken]]
wees en 'n geldige e-posadres in jou [[Special:Preferences|voorkeure]]
hê om e-pos aan ander gebruikers te stuur.",
'emailuser'       => 'Stuur e-pos na hierdie gebruiker',
'emailpage'       => 'Stuur e-pos na gebruiker',
'emailpagetext'   => 'As die gerbuiker \'n geldoge e-posadres in haar of sy gebruikersvoorkeure het, sal die vorm hier onder \'n enkele boodskap stuur.
Die e-posadres wat jy in jou gebruikersvoorkeure het sal verkyn as die "Van" adres van die pos, so die ontvanger sal kan terug antwoord.',
'noemailtitle'    => 'Geen e-posadres',
'noemailtext'     => "Hierdie gebruiker het nie 'n geldige e-posadres gespesifiseer nie of het gekies om nie e-pos van ander gebruikers te ontvang nie.",
'emailfrom'       => 'Van',
'emailto'         => 'Aan',
'emailsubject'    => 'Onderwerp',
'emailmessage'    => 'Boodskap',
'emailsend'       => 'Stuur',
'emailsent'       => 'E-pos gestuur',
'emailsenttext'   => 'Jou e-pos is gestuur.',

# Watchlist
'watchlist'        => 'My dophoulys',
'mywatchlist'      => 'My dophoulys',
'nowatchlist'      => 'Jy het geen items in jou dophoulys nie.',
'watchnologin'     => 'Nie ingeteken nie',
'watchnologintext' => 'Jy moet [[Special:Userlogin|ingeteken]]
wees om jou dophoulys te verander.',
'addedwatch'       => 'Bygevoeg tot dophoulys',
'addedwatchtext'   => 'Die bladsy "$1" is by jou <a href="{{localurle:Special:Watchlist}}">dophoulys</a> gevoeg.
Toekomstige veranderinge aan hierdie bladsye en sy geassosieerde Bespreekbladsy sal hier verskyn en die bladsy sal in <b>vetdruk</b> verskyn in die <a href="{{localurle:Special:Recentchanges}}">lys van onlangse wysigings</a> om dit makliker te maak om dit raak te sien.

As jy die bladsy later van jou dophoulys wil verwyder, kliek "Moenie meer dophou" in die kantbalk.',
'removedwatch'     => 'Afgehaal van dophoulys',
'removedwatchtext' => 'Die bladsy "$1" is van jou dophoulys afgehaal.',
'watchthispage'    => 'Hou hierdie bladsy dop',
'unwatchthispage'  => 'Moenie meer dophou',
'notanarticle'     => "Nie 'n artikel",

# Delete/protect/revert
'deletepage'        => 'Skrap bladsy',
'confirm'           => 'Bevestig',
'confirmdelete'     => 'Bevestig skrapping',
'deletesub'         => '(Besig om "$1" te skrap)',
'confirmdeletetext' => "Jy staan op die punt om 'n bladsy of prent asook al hulle geskiedenis uit die databasis te skrap.
Bevestig asseblief dat jy dit wil doen, dat jy die gevolge verstaan en dat jy dit doen in ooreenstemming met die [[{{MediaWiki:policy-url}}]].",
'actioncomplete'    => 'Aksie uitgevoer',
'deletedtext'       => '"$1" is geskrap.
Kyk na $2 vir \'n rekord van onlangse skrappings.',
'deletedarticle'    => '"$1" geskrap',
'dellogpage'        => 'Skrap_log',
'dellogpagetext'    => "Hier onder is 'n lys van die mees onlangse skrappings. Alle tye is bedienertyd (UGT).",
'deletionlog'       => 'skrappingslog',
'reverted'          => 'Het terug gegaan na vroeëre weergawe',
'deletecomment'     => 'Rede vir skrapping',
'imagereverted'     => 'Terugkeer na vorige weergawe was suksesvol.',
'rollback'          => 'Rol veranderinge terug',
'rollbacklink'      => 'Rol terug',
'cantrollback'      => 'Kan nie na verandering terug keer nie; die laaste bydraer is die enigste outer van hierdie bladsy.',
'revertpage'        => 'Het teruggegaan na laaste verandering wat $1 gemaak het',

# Undelete
'undelete'          => 'Herstel geskrapte bladsy',
'undeletepage'      => 'Bekyk en herstel geskrapte bladsye',
'undeletepagetext'  => 'Die volgende bladsye is geskrap, maar hulle is nog in die argief en kan herstel word. Die argief kan periodiek skoongemaak word.',
'undeleterevisions' => '$1 weergawes in argief',
'undeletehistory'   => "As jy die bladsy herstel, sal alle weergawes herstel word.
As 'n nuwe bladsy met dieselfde naam sedert die skrapping geskep is, sal die herstelde weergawes in die nuwe bladsy se voorgeskiedenis verskyn en die huidige weergawe van die lewendige bladsy sal nie outomaties vervang word nie.",
'undeletebtn'       => 'Herstel!',
'undeletedarticle'  => 'het "$1" herstel',

# Contributions
'contributions' => 'Gebruikersbydraes',
'mycontris'     => 'My bydraes',
'contribsub2'   => 'Vir $1 ($2)',
'nocontribs'    => 'Geen veranderinge wat by hierdie kriteria pas, is gevind nie.',
'ucnote'        => 'Hier onder is die gebruiker se laaste <b>$1</b> veranderings in die laaste <b>$2</b> dae.',
'uclinks'       => 'Bekyk die laaste $1 veranderings; bekyk die laaste $2 dae.',
'uctop'         => ' (boontoe)',

# What links here
'whatlinkshere' => 'Wat skakel hierheen',
'notargettitle' => 'Geen teiken',
'notargettext'  => "Jy het nie 'n teikenbladsy of gebruiker waarmee hierdie funksie moet werk, gespesifiseer nie.",
'linklistsub'   => '(Lys van skakels)',
'linkshere'     => 'Die volgende bladsye skakel hierheen:',
'nolinkshere'   => 'Geen bladsye skakel hierheen nie.',
'isredirect'    => 'Stuur bladsy aan',

# Block/unblock
'blockip'            => 'Blok IP-adres',
'blockiptext'        => "Gebruik die vorm hier onder om skryftoegang van 'n sekere IP-adres te blok.
Dit moet net gedoen word om vandalisme te voorkom en in ooreenstemming met [[{{MediaWiki:policy-url}}|{{SITENAME}} policy]].
Vul 'n spesifieke rede hier onder in (haal byvoorbeeld spesifieke bladsye wat gevandaliseer is, aan).",
'ipaddress'          => 'IP-Adres',
'ipbreason'          => 'Rede',
'ipbsubmit'          => 'Blok hierdie adres',
'badipaddress'       => 'Die IP-adres is nie in die regte formaat nie.',
'blockipsuccesssub'  => 'Blokkering het geslaag',
'blockipsuccesstext' => 'Die IP-adres "$1" is geblok.
<br />Kyk na [[Special:Ipblocklist|IP block list]] vir \'n oorsig van blokkerings.',
'unblockip'          => 'Maak IP-adres oop',
'unblockiptext'      => "Gebruik die vorm hier onder om skryftoegang te herstel vir 'n voorheen geblokkeerde IP-adres.",
'ipusubmit'          => 'Maak hierdie adres oop',
'ipblocklist'        => 'Lys van geblokkeerde IP-adresse',
'blocklistline'      => '$1, $2 het $3 geblok ($4)',
'blocklink'          => 'blok',
'unblocklink'        => 'maak oop',
'contribslink'       => 'bydraes',

# Developer tools
'lockdb'              => 'Sluit databasis',
'unlockdb'            => 'Ontsluit databasis',
'lockdbtext'          => 'As jy die databasis sluit, kan geen gebruiker meer bladsye redigeer nie, voorkeure verander nie, dophoulyste verander nie, of ander aksies uitvoer wat veranderinge in die databasis verg nie.
Bevestig asseblief dat dit is wat jy wil doen en dat jy die databasis sal ontsluit sodra jy jou instandhouding afgehandel het.',
'unlockdbtext'        => 'As jy die databasis ontsluit, kan gebruikers weer bladsye redigeer, voorkeure verander, dophoulyste verander, of ander aksies uitvoer wat veranderinge in die databasis verg.
Bevestig asseblief dat dit is wat jy wil doen.',
'lockconfirm'         => 'Ja, ek wil regtig die databasis sluit.',
'unlockconfirm'       => 'Ja, ek wil regtig die databasis ontsluit.',
'lockbtn'             => 'Sluit die databasis',
'unlockbtn'           => 'Ontsluit die databasis',
'locknoconfirm'       => 'Jy het nie die bevestigblokkie gemerk nie.',
'lockdbsuccesssub'    => 'Databasissluit het geslaag',
'unlockdbsuccesssub'  => 'Databasisslot is verwyder',
'lockdbsuccesstext'   => 'Die {{SITENAME}} databasis is gesluit.
<br />Onthou om dit te ontsluit wanneer jou onderhoud afgehandel is.',
'unlockdbsuccesstext' => 'Die {{SITENAME}} databasis is ontsluit.',

# Move page
'movepage'         => 'Skuif bladsy',
'movepagetext'     => "Met die vorm hier onder kan jy 'n bladsy hernoem en so al sy geskiedenis na die nuwe naam skuif.
Die ou titel sal 'n aanstuurbladsy na die nuwe titel word.
Skakels na die ou bladsytitel sal nie verander nie; maak seker dat jy
check vir dubbele of gebrekte aansture.
Dis jou verantwoordelikheid om seker te maak dat skakels steeds wys waarheen hulle moet.

Let op dat 'n bladsy '''nie''' geskuif sal word as daar reeds 'n bladsy met so 'n titel bestaan nie, tensy dit leeg is off 'n aanstuurbladsy is, en dit het geen veranderingsgeskiedenis nie. Dit beteken dat jy 'n bladsy kan hernoem na sy ou titel as jy 'n fout gemaak het, en jy kan nie oor 'n bestaande bladsy skryf nie.

<b>WAARSKUWING!</b>
Hierdie kan 'n drasitiese en onverwagte verandering vir 'n populêre bladsy wees;
maak asseblief seker dat jy die gevolge verstaan voordat jy voortgaan.",
'movepagetalktext' => "Die geassosieerde praatbladsy, indien enige, sal outomaties saam met dit geskuif word, '''behalwe as:'''
*Jy die bladsy oor naamkontekste heen skuif,
*'n Bespreekbladsy wat nie leeg is nie reeds onder die nuwe naam bestaan, of
*Jy die merk uit blokkie hier onder wegneem.

In hierdie gevalle, sal jy die bladsy met die hand moet skuif of saamsmelt as jy wil.",
'movearticle'      => 'Skuif bladsy',
'movenologin'      => 'Nie ingeteken nie',
'movenologintext'  => "Jy moet 'n geregistreerde gebruiker wees en [[Special:Userlogin|ingeteken]]
wees om 'n bladsy te skuif.",
'newtitle'         => 'Na nuwe titel',
'movepagebtn'      => 'Skuif bladsy',
'pagemovedsub'     => 'Verskuiwing het geslaag',
'pagemovedtext'    => 'Bladsy "[[$1]]" geskuif na "[[$2]]".',
'articleexists'    => "'n Bladsy met daardie naam bestaan reeds, of die naam wat jy gekies het, is nie geldig nie.
Kies asseblief 'n ander naam.",
'talkexists'       => 'Die bladsy self is suksesvol verskuif, maar die bespreekbladsy kon nie geskuif word nie omdat een reeds bestaan met die nuwe titel. Smelt hulle asseblief met die hand saam.',
'movedto'          => 'geskuif na',
'movetalk'         => 'Skuif "bespreek"bladsy ook, indien van toepassing.',
'talkpagemoved'    => 'Die ooreenkomstige bespreekbladsy is ook geskuif.',
'talkpagenotmoved' => 'Die ooreenkomstige bespreekbladsy is <strong>nie</strong> geskuif nie.',

# Math options
'mw_math_png'    => 'Gebruik altyd PNG.',
'mw_math_simple' => 'Gebruik HTML indien dit eenvoudig is, andersins PNG.',
'mw_math_html'   => 'Gebruik HTML wanneer moontlik, andersins PNG.',
'mw_math_source' => 'Los as TeX (vir teks blaaiers).',
'mw_math_modern' => 'Moderne blaaiers.',
'mw_math_mathml' => 'MathML',

);



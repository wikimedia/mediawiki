<?php
/** Afrikaans (Afrikaans)
 *
 * @addtogroup Language
 *
 * @author SPQRobin
 * @author Adriaan
 * @author Siebrand
 * @author Spacebirdy
 * @author Manie
 * @author Arnobarnard
 */

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
'tog-underline'               => 'Onderstreep skakels.',
'tog-highlightbroken'         => 'Wys gebroke skakels <a href="" class="new">so</a> (andersins: so<a href="" class="internal">?</a>)',
'tog-justify'                 => 'Justeer paragrawe.',
'tog-hideminor'               => 'Moenie klein wysigings in die onlangse wysigingslys wys nie.',
'tog-extendwatchlist'         => 'Brei dophoulys uit om alle toepaslike wysigings te wys',
'tog-usenewrc'                => 'Verbeterde onlangse wysigingslys (vir moderne blaaiers).',
'tog-numberheadings'          => 'Nommer opskrifte outomaties',
'tog-showtoolbar'             => 'Wys redigeergereedskap',
'tog-editondblclick'          => 'Wysig blaaie met dubbelkliek (JavaScript).',
'tog-editsection'             => 'Wys [wysig]-skakels vir elke afdeling',
'tog-editsectiononrightclick' => 'Wysig afdeling met regskliek op afdeling se titel (JavaScript)',
'tog-showtoc'                 => 'Wys inhoudsopgawe (by bladsye met meer as drie opskrifte)',
'tog-rememberpassword'        => 'Onthou wagwoord oor sessies.',
'tog-editwidth'               => 'Wysigingsboks met volle wydte.',
'tog-watchcreations'          => 'Voeg bladsye wat ek skep by my dophoulys',
'tog-watchdefault'            => 'Lys nuwe en gewysigde bladsye.',
'tog-watchmoves'              => 'Voeg die bladsye wat ek skuif by my dophoulys',
'tog-watchdeletion'           => 'Voeg bladsye wat ek verwyder by my dophoulys',
'tog-minordefault'            => 'Merk alle wysigings automaties as klein by verstek.',
'tog-previewontop'            => 'Wys voorskou bo wysigingsboks.',
'tog-previewonfirst'          => 'Wys voorksou met eerste wysiging',
'tog-nocache'                 => 'Deaktiveer bladsykasstelsel (Engels: caching)',
'tog-enotifwatchlistpages'    => 'Stuur vir my epos met bladsyveranderings',
'tog-enotifusertalkpages'     => 'Stuur vir my epos as my eie besprekingsblad verander word',
'tog-enotifminoredits'        => 'Stuur ook epos vir klein bladsywysigings',
'tog-enotifrevealaddr'        => 'Stel my eposadres bloot in kennisgewingspos',
'tog-fancysig'                => 'Doodgewone handtekening (sonder outomatiese skakel)',
'tog-externaleditor'          => "Gebruik outomaties 'n eksterne redigeringsprogram",
'tog-showjumplinks'           => 'Wys "spring na"-skakels vir toeganklikheid',
'tog-uselivepreview'          => 'Gebruik lewendige voorskou (JavaScript) (eksperimenteel)',
'tog-forceeditsummary'        => "Let my daarop as ek nie 'n opsomming van my wysiging gee nie",
'tog-watchlisthideown'        => 'Versteek my wysigings in dophoulys',
'tog-watchlisthidebots'       => 'Versteek robotwysigings in dophoulys',
'tog-watchlisthideminor'      => 'Versteek klein wysigings van my dophoulys',
'tog-ccmeonemails'            => "Stuur my 'n kopie van die e-pos wat ek aan ander stuur",
'tog-diffonly'                => "Moenie 'n bladsy se inhoud onder die wysigingsverskil wys nie",
'tog-showhiddencats'          => 'Wys versteekte kategorië',

'underline-always'  => 'Altyd',
'underline-never'   => 'Nooit',
'underline-default' => 'Blaaierverstek',

'skinpreview' => '(Voorskou)',

# Dates
'sunday'        => 'Sondag',
'monday'        => 'Maandag',
'tuesday'       => 'Dinsdag',
'wednesday'     => 'Woensdag',
'thursday'      => 'Donderdag',
'friday'        => 'Vrydag',
'saturday'      => 'Saterdag',
'sun'           => 'So',
'mon'           => 'Ma',
'tue'           => 'Di',
'wed'           => 'Wo',
'thu'           => 'Do',
'fri'           => 'Vr',
'sat'           => 'Sa',
'january'       => 'Januarie',
'february'      => 'Februarie',
'march'         => 'Maart',
'april'         => 'April',
'may_long'      => 'Mei',
'june'          => 'Junie',
'july'          => 'Julie',
'august'        => 'Augustus',
'september'     => 'September',
'october'       => 'Oktober',
'november'      => 'November',
'december'      => 'Desember',
'january-gen'   => 'Januarie',
'february-gen'  => 'Februarie',
'march-gen'     => 'Maart',
'april-gen'     => 'April',
'may-gen'       => 'Mei',
'june-gen'      => 'Junie',
'july-gen'      => 'Julie',
'august-gen'    => 'Augustus',
'september-gen' => 'September',
'october-gen'   => 'Oktober',
'november-gen'  => 'November',
'december-gen'  => 'Desember',
'jan'           => 'Jan',
'feb'           => 'Feb',
'mar'           => 'Mrt',
'apr'           => 'Apr',
'may'           => 'Mei',
'jun'           => 'Jun',
'jul'           => 'Jul',
'aug'           => 'Aug',
'sep'           => 'Sep',
'oct'           => 'Okt',
'nov'           => 'Nov',
'dec'           => 'Des',

# Categories related messages
'categories'                     => 'Kategorieë',
'categoriespagetext'             => 'Die volgende kategorieë bestaan op die wiki.',
'special-categories-sort-count'  => 'sorteer volgens getal',
'special-categories-sort-abc'    => 'sorteer alfabeties',
'pagecategories'                 => '{{PLURAL:$1|Kategorie|Kategorieë}}',
'category_header'                => 'Artikels in "$1"-kategorie',
'subcategories'                  => 'Subkategorieë',
'category-media-header'          => 'Media in kategorie "$1"',
'category-empty'                 => "''Hierdie kategorie bevat geen artikels of media nie.''",
'hidden-categories'              => '{{PLURAL:$1|Versteekte kategorie|Versteekte kategorië}}',
'hidden-category-category'       => 'Versteekte kategorië', # Name of the category where hidden categories will be listed
'category-subcat-count'          => "{{PLURAL:$2|Hierdie kategorie het slegs die volgende subkategorie.|Hierdie kategorie het die volgende {{PLURAL:$1|subkategorie|$1 subkategorië}}, uit 'n totaal van $2.}}",
'category-subcat-count-limited'  => 'Hierdie kategorie het die volgende {{PLURAL:$1|subkategorie|$1 subkategorië}}.',
'category-article-count'         => "{{PLURAL:$2|Hierdie kategorie bevat slegs die volgende bladsy.|Die volgende {{PLURAL:$1|bladsy|$1 bladsye}} is in hierdie kategorie, uit 'n totaal van $2.}}",
'category-article-count-limited' => 'Die volgende {{PLURAL:$1|bladsy|$1 bladsye}} is in die huidige kategorie.',
'category-file-count'            => "{{PLURAL:$2|Hierdie kategorie bevat net die volgende lêer.|Die volgende {{PLURAL:$1|lêer|$1 lêers}} is in hierdie kategorie, uit 'n totaal van $2.}}",
'category-file-count-limited'    => 'Die volgende {{PLURAL:$1|lêer|$1 lêers}} is in die huidige kategorie.',
'listingcontinuesabbrev'         => 'vervolg',

'mainpagetext'      => "<big>'''MediaWiki is suksesvol geïnstalleer.'''</big>",
'mainpagedocfooter' => "Konsulteer '''[http://meta.wikimedia.org/wiki/Help:Contents User's Guide]''' vir inligting oor hoe om die wikisagteware te gebruik.

== Hoe om te Begin ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]",

'about'          => 'Omtrent',
'article'        => 'Inhoudbladsy',
'newwindow'      => '(verskyn in nuwe venster)',
'cancel'         => 'Kanselleer',
'qbfind'         => 'Vind',
'qbbrowse'       => 'Snuffel',
'qbedit'         => 'Wysig',
'qbpageoptions'  => 'Bladsyopsies',
'qbpageinfo'     => 'Bladsyinligting',
'qbmyoptions'    => 'My opsies',
'qbspecialpages' => 'Spesiale bladsye',
'moredotdotdot'  => 'Meer...',
'mypage'         => 'My bladsy',
'mytalk'         => 'My besprekings',
'anontalk'       => 'Besprekingsblad vir hierdie IP',
'navigation'     => 'Navigasie',
'and'            => 'en',

# Metadata in edit box
'metadata_help' => 'Metadata:',

'errorpagetitle'    => 'Fout',
'returnto'          => 'Keer terug na $1.',
'tagline'           => 'Vanuit {{SITENAME}}',
'help'              => 'Hulp',
'search'            => 'Soek',
'searchbutton'      => 'Soek',
'go'                => 'Wys',
'searcharticle'     => 'Wys',
'history'           => 'Ouer weergawes',
'history_short'     => 'Geskiedenis',
'updatedmarker'     => 'opgedateer sedert my laaste besoek',
'info_short'        => 'Inligting',
'printableversion'  => 'Drukbare weergawe',
'permalink'         => 'Permanente skakel',
'print'             => 'Druk',
'edit'              => 'Wysig',
'create'            => 'Skep',
'editthispage'      => 'Wysig hierdie bladsy',
'create-this-page'  => 'Skep hierdie bladsy',
'delete'            => 'Skrap',
'deletethispage'    => 'Skrap bladsy',
'undelete_short'    => 'Herstel {{PLURAL:$1|een wysiging|$1 wysigings}}',
'protect'           => 'Beskerm',
'protect_change'    => 'wysig beskerming',
'protectthispage'   => 'Beskerm hierdie bladsy',
'unprotect'         => 'Verwyder beskerming',
'unprotectthispage' => 'Verwyder beskerming',
'newpage'           => 'Nuwe bladsy',
'talkpage'          => 'Bespreek hierdie bladsy',
'talkpagelinktext'  => 'Besprekings',
'specialpage'       => 'Spesiale bladsy',
'personaltools'     => 'Persoonlike gereedskap',
'postcomment'       => 'Lewer kommentaar',
'articlepage'       => 'Lees artikel',
'talk'              => 'Bespreking',
'views'             => 'Aansigte',
'toolbox'           => 'Gereedskap',
'userpage'          => 'Lees gebruikersbladsy',
'projectpage'       => 'Lees metabladsy',
'imagepage'         => 'Lees bladsy oor prent',
'mediawikipage'     => 'Bekyk boodskapsbladsy',
'templatepage'      => 'Bekyk sjabloonsbladsy',
'viewhelppage'      => 'Bekyk hulpbladsy',
'categorypage'      => 'Bekyk kategorieblad',
'viewtalkpage'      => 'Lees bespreking',
'otherlanguages'    => 'Ander tale',
'redirectedfrom'    => '(Aangestuur vanaf $1)',
'redirectpagesub'   => 'Aanstuurblad',
'lastmodifiedat'    => 'Laaste wysiging op $2, $1.', # $1 date, $2 time
'viewcount'         => 'Hierdie bladsy is al {{PLURAL:$1|keer|$1 kere}} aangevra.',
'protectedpage'     => 'Beskermde bladsy',
'jumpto'            => 'Spring na:',
'jumptonavigation'  => 'navigasie',
'jumptosearch'      => 'soek',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Inligting oor {{SITENAME}}',
'aboutpage'            => 'Project:Omtrent',
'bugreports'           => 'Foutverslae',
'bugreportspage'       => 'Project:Foutverslae',
'copyright'            => 'Teks is beskikbaar onderhewig aan $1.',
'copyrightpagename'    => '{{SITENAME}} kopiereg',
'copyrightpage'        => '{{ns:project}}:kopiereg',
'currentevents'        => 'Huidige gebeure',
'currentevents-url'    => 'Project:Huidige gebeure',
'disclaimers'          => 'Voorbehoud',
'disclaimerpage'       => 'Project:Voorwaardes',
'edithelp'             => 'Wysighulp',
'edithelppage'         => 'Help:Wysig',
'faq'                  => 'Gewilde vrae',
'faqpage'              => 'Project:GewildeVrae',
'helppage'             => 'Help:Hulp',
'mainpage'             => 'Tuisblad',
'mainpage-description' => 'Tuisblad',
'policy-url'           => 'Project:Beleid',
'portal'               => 'Gebruikersportaal',
'portal-url'           => 'Project:Gebruikersportaal',
'privacy'              => 'Privaatheidsbeleid',
'privacypage'          => 'Project:Privaatheidsbeleid',
'sitesupport'          => 'Skenkings',
'sitesupport-url'      => 'Project:Skenkings',

'badaccess'        => 'Toestemmingsfout',
'badaccess-group0' => 'U is nie toegelaat om die aksie uit te voer wat U aangevra het nie.',
'badaccess-group1' => 'Die aksie wat U aangevra het is beperk tot gebruikers in groep $1.',
'badaccess-group2' => 'Die aksie wat U aangevra het is beperk tot gebruikers in een van die groepe $1.',
'badaccess-groups' => 'Die aksie wat U aangevra het is beperk tot gebruikers in een van die groepe $1.',

'versionrequired'     => 'Weergawe $1 van MediaWiki benodig',
'versionrequiredtext' => 'Weergawe $1 van MediaWiki word benodig om hierdie bladsy te gebruik. Sien [[Special:Version|version page]].',

'ok'                      => 'Aanvaar',
'retrievedfrom'           => 'Ontsluit van "$1"',
'youhavenewmessages'      => 'U het $1 (sien $2).',
'newmessageslink'         => 'nuwe boodskappe',
'newmessagesdifflink'     => 'die laaste wysiging',
'youhavenewmessagesmulti' => 'U het nuwe boodskappe op $1',
'editsection'             => 'wysig',
'editold'                 => 'wysig',
'editsectionhint'         => 'Wysig afdeling: $1',
'toc'                     => 'Inhoud',
'showtoc'                 => 'wys',
'hidetoc'                 => 'versteek',
'thisisdeleted'           => 'Bekyk of herstel $1?',
'viewdeleted'             => 'Bekyk $1?',
'restorelink'             => '{{PLURAL:$1|die geskrapte wysiging|$1 geskrapte wysigings}}',
'red-link-title'          => '$1 (nog nie geskryf nie)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Artikel',
'nstab-user'      => 'Gebruikerblad',
'nstab-media'     => 'Mediablad',
'nstab-special'   => 'Spesiaal',
'nstab-project'   => 'Projekblad',
'nstab-image'     => 'Beeld',
'nstab-mediawiki' => 'Boodskap',
'nstab-template'  => 'Sjabloon',
'nstab-help'      => 'Hulpblad',
'nstab-category'  => 'Kategorie',

# Main script and global functions
'nosuchaction'      => 'Ongeldige aksie',
'nosuchactiontext'  => 'Onbekende aksie deur die adres gespesifeer',
'nosuchspecialpage' => 'Ongeldige spesiale bladsy',
'nospecialpagetext' => 'Ongeldige spesiale bladsy gespesifeer.',

# General errors
'error'                => 'Fout',
'databaseerror'        => 'Databasisfout',
'dberrortext'          => 'Sintaksisfout in databasisnavraag.
Die laaste navraag was:
<blockquote><tt>$1</tt></blockquote>
van funksie "<tt>$2</tt>".
MySQL foutboodskap "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Sintaksisfout in databasisnavraag.
Die laaste navraag was:
<blockquote><tt>$1</tt></blockquote>
van funksie "<tt>$2</tt>".
MySQL foutboodskap "<tt>$3: $4</tt>".',
'noconnect'            => 'Kon nie met databasis op $1 konnekteer nie',
'nodb'                 => 'Kon nie databasis $1 selekteer nie',
'cachederror'          => "Die volgende is 'n gekaste kopie van die aangevraagde blad, en is dalk nie op datum nie.",
'laggedslavemode'      => 'Waarskuwing: Onlangse wysigings dalk nie in bladsy vervat nie.',
'readonly'             => 'Databasis gesluit',
'enterlockreason'      => 'Rede vir die sluiting,
en beraming van wanneer ontsluiting sal plaas vind',
'readonlytext'         => 'Die {{SITENAME}} databasis is tans gesluit vir nuwe
artikelwysigings, waarskynlik vir roetine databasisonderhoud,
waarna dit terug sal wees na normaal.
Die administreerder wat dit gesluit het se verduideliking:

$1',
'missingarticle'       => 'Die databasis het nie die teks van die veronderstelde bladsy "$1" gekry nie.
Nie databasisfout nie, moontlik sagtewarefout.
Raporteer die adres asseblief aan enige administrateur.',
'readonly_lag'         => 'Die databasis is outomaties gesluit terwyl die slaafdatabasisse sinchroniseer met die meester',
'internalerror'        => 'Interne fout',
'internalerror_info'   => 'Interne fout: $1',
'filecopyerror'        => 'Kon nie lêer van "$1" na "$2" kopieer nie.',
'filerenameerror'      => 'Kon nie lêernaam van "$1" na "$2" wysig nie.',
'filedeleteerror'      => 'Kon nie lêer "$1" skrap nie.',
'directorycreateerror' => 'Kon nie gids "$1" skep nie.',
'filenotfound'         => 'Kon nie lêer "$1" vind nie.',
'fileexistserror'      => 'Nie moontlik om na lêer "$1" te skryf: lêer bestaan reeds',
'unexpected'           => 'Onverwagte waarde: "$1"="$2".',
'formerror'            => 'Fout: kon vorm nie stuur nie',
'badarticleerror'      => 'Die aksie kon nie op hierdie bladsy uitgevoer word nie.',
'cannotdelete'         => 'Kon nie die bladsy of prent skrap nie, iemand anders het dit miskien reeds geskrap.',
'badtitle'             => 'Ongeldige titel',
'badtitletext'         => "Die bladsytitel waarvoor gevra is, is ongeldig, leeg, of
'n verkeerd geskakelde tussen-taal of tussen-wiki titel.",
'perfdisabled'         => 'Jammer, hierdie funksie is tydelik afgeskakel omdat dit die databasis soveel verstadig dat dit onbruikbaar vir andere raak.',
'perfcached'           => "Die volgende inligting is 'n gekaste kopie en mag dalk nie volledig op datum wees nie.",
'perfcachedts'         => 'Die volgende data is gekas. Laaste opdatering: $1',
'querypage-no-updates' => 'Opdatering van hierdie bladsy is huidiglik afgeskakel. Inligting hier sal nie tans verfris word nie.',
'wrong_wfQuery_params' => 'Foutiewe parameters na wfQuery()<br />
Funksie: $1<br />
Navraag: $2',
'viewsource'           => 'Bekyk bronteks',
'viewsourcefor'        => 'vir $1',
'actionthrottled'      => 'Outo-rem op aksie uitgevoer',
'actionthrottledtext'  => "As 'n teen-strooi aksie, word U beperk om hierdie aksie te veel keer in 'n kort tyd uit te voer, en U het hierdie limiet oorskry.
Probeer asseblief weer oor 'n paar minute.",
'protectedpagetext'    => 'Hierdie bladsy is beskerm om redigering te verhoed.',
'viewsourcetext'       => 'U kan die bronteks van hierdie bladsy bekyk en wysig:',
'protectedinterface'   => 'Hierdie bladsy verskaf intervlak teks vir die sagteware, en is beskerm om misbruik te voorkom.',
'editinginterface'     => "'''Waarskuwing:''' U is besig om 'n bladsy te redigeer wat koppelvlakinligting aan die programmatuur voorsien. Wysigings aan hierdie bladsy sal die voorkoms van die gebruikerskoppelvlak vir ander gebruikers beïnvloed. Vir vertalings, overweeg om [http://translatewiki.net/wiki/Main_Page?setlang=af Betawiki] (die vertalingsprojek vir MediaWiki) te gebruik.",
'sqlhidden'            => '(SQL navraag versteek)',
'cascadeprotected'     => 'Hierdie bladsy is beskerm teen redigering omdat dit ingesluit is in die volgende {{PLURAL:$1|bladsy|bladsye}} wat beskerm is met die "kaskade" opsie aangeskakel: $2',
'namespaceprotected'   => "U het nie toestemming om bladsye in die '''$1'''-naamruimte te wysig nie.",
'customcssjsprotected' => "U het nie toestemming om hierdie bladsy te redigeer nie, want dit bevat 'n ander gebruiker se persoonlike verstellings.",
'ns-specialprotected'  => 'Spesiale bladsye kan nie geredigeer word nie.',
'titleprotected'       => "Hierdie titel is beskerm teen skepping deur [[Gebruiker:$1|$1]].
Die rede gegee is ''$2''.",

# Login and logout pages
'logouttitle'                => 'Teken uit',
'logouttext'                 => "U is nou uitgeteken, en kan aanhou om
{{SITENAME}} anoniem te gebruik; of U kan inteken as dieselfde of 'n ander gebruiker.",
'welcomecreation'            => '<h2>Welkom, $1.</h2>
Jou rekening is geskep;
moenie vergeet om jou persoonlike voorkeure te stel nie.',
'loginpagetitle'             => 'Teken in',
'yourname'                   => 'Gebruikersnaam',
'yourpassword'               => 'Wagwoord',
'yourpasswordagain'          => 'Herhaal wagwoord',
'remembermypassword'         => 'Onthou my wagwoord oor sessies.',
'loginproblem'               => '<b>Daar was probleme met jou intekening.</b><br />Probeer weer.',
'login'                      => 'Teken in',
'loginprompt'                => 'U blaaier moet koekies toelaat om by {{SITENAME}} aan te teken.',
'userlogin'                  => 'Teken in',
'logout'                     => 'Teken uit',
'userlogout'                 => 'Teken uit',
'notloggedin'                => 'Nie ingeteken nie',
'nologin'                    => 'Nog nie geregistreer nie? $1.',
'nologinlink'                => "Skep gerus 'n rekening",
'createaccount'              => 'Skep nuwe rekening',
'gotaccount'                 => "Het u reeds 'n rekening? $1.",
'gotaccountlink'             => 'Teken gerus aan',
'createaccountmail'          => 'deur e-pos',
'badretype'                  => 'Die ingetikte wagwoorde is nie dieselfde nie.',
'userexists'                 => "Die gebruikersnaam wat jy gebruik het, is alreeds gebruik. Kies asseblief 'n ander gebruikersnaam.",
'youremail'                  => 'E-pos',
'username'                   => 'Gebruikernaam:',
'uid'                        => 'Gebruiker-ID:',
'yourrealname'               => 'Regte naam:',
'yourlanguage'               => 'Taal:',
'yournick'                   => 'Bynaam (vir handtekening)',
'badsig'                     => 'Ongeldige handtekening; gaan HTML na.',
'email'                      => 'E-pos',
'prefs-help-realname'        => 'Regte naam (opsioneel): as u hierdie verskaf, kan dit gebruik word om erkenning vir u werk te gee.',
'loginerror'                 => 'Intekenfout',
'prefs-help-email'           => 'E-pos (opsioneel): Maak dit vir ander moontlik om u te kontak deur u gebruikerblad sonder dat u identiteit verraai word.',
'prefs-help-email-required'  => 'E-pos adres word benodig.',
'nocookieslogin'             => '{{SITENAME}} gebruik koekies vir die aanteken van gebruikers, maar u blaaier laat dit nie toe nie. Skakel dit asseblief aan en probeer weer.',
'noname'                     => 'Ongeldige gebruikersnaam.',
'loginsuccesstitle'          => 'Suksesvolle intekening',
'loginsuccess'               => 'U is ingeteken by {{SITENAME}} as "$1".',
'nosuchuser'                 => 'Daar is geen gebruikersnaam "$1" nie. Maak seker dit is reg gespel, of gebruik die vorm hier onder om \'n nuwe rekening te skep.',
'nosuchusershort'            => 'Daar is geen gebruikersnaam "<nowiki>$1</nowiki>" nie. Maak seker dit is reg gespel.',
'nouserspecified'            => "U moet 'n gebruikersnaam spesifiseer.",
'wrongpassword'              => 'Ongeldige wagwoord, probeer weer.',
'wrongpasswordempty'         => 'Die wagwoord was leeg. Probeer asseblief weer.',
'passwordtooshort'           => 'U wagwoord is te kort. Dit moet ten minste $1 karakters hê.',
'mailmypassword'             => 'E-pos nuwe wagwoord',
'passwordremindertitle'      => 'Wagwoordwenk van {{SITENAME}}',
'passwordremindertext'       => 'Iemand (waarskynlik U, van IP-adres $1)
het gevra dat ons vir U \'n nuwe {{SITENAME}}-wagwoord ($4) stuur.
Die wagwoord vir gebruiker "$2" is nou "$3".
Teken asseblief in en verander U wagwoord.

Indien iemand anders hierdie navraag gerig het of as U die wagwoord onthou en U nie meer die wagwoord wil wysig nie, kan U hierdie boodskap ignoreer en voortgaan om U ou wagwoord te gebruik.',
'noemail'                    => 'Daar is geen e-posadres vir gebruiker "$1" nie.',
'passwordsent'               => 'Nuwe wagwoord gestuur na e-posadres vir "$1".
Teken asseblief in na jy dit ontvang het.',
'eauthentsent'               => "'n Bevestigingpos is gestuur na die gekose e-posadres.
Voordat ander pos na die adres gestuur word,
moet die instruksies in bogenoemde pos gevolg word om te bevestig dat die adres werklik u adres is.",
'mailerror'                  => 'Fout tydens e-pos versending: $1',
'acct_creation_throttle_hit' => 'Jammer. U het reeds $1 rekeninge geskep. U kan nie nog skep nie.',
'emailauthenticated'         => 'U e-posadres is bevestig op $1.',
'emailnotauthenticated'      => 'U e-poasadres is <strong>nog nie bevestig nie</strong>. Geen e-pos sal gestuur word vir die volgende funksies nie.',
'noemailprefs'               => "Spesifiseer 'n eposadres vir hierdie funksies om te werk.",
'emailconfirmlink'           => 'Bevestig u e-posadres',
'accountcreated'             => 'Rekening geskep',
'accountcreatedtext'         => 'Die rekening vir gebruiker $1 is geskep.',
'loginlanguagelabel'         => 'Taal: $1',

# Password reset dialog
'resetpass'           => 'Herstel rekening wagwoord',
'resetpass_header'    => 'Herstel wagwoord',
'resetpass_submit'    => 'Stel wagwoord en teken in',
'resetpass_success'   => 'U wagwoord is suksesvol gewysig! Besig om U in te teken ...',
'resetpass_forbidden' => 'Wagwoorde kannie op {{SITENAME}} gewysig word nie.',

# Edit page toolbar
'bold_sample'     => 'Vet teks',
'bold_tip'        => 'Vetdruk',
'italic_sample'   => 'Skuins teks',
'italic_tip'      => 'Skuinsdruk',
'link_sample'     => 'Skakelnaam',
'link_tip'        => 'Interne skakel',
'extlink_sample'  => 'http://www.voorbeeld.org skakel se titel',
'extlink_tip'     => 'Eksterne skakel (onthou http:// vooraan)',
'headline_sample' => 'Opskrif',
'headline_tip'    => 'Vlak 2-opskrif',
'math_sample'     => 'Plaas formule hier',
'math_tip'        => 'Wiskundige formule (LaTeX)',
'nowiki_sample'   => 'Plaas ongeformatteerde teks hier',
'nowiki_tip'      => 'Ignoreer wiki-formattering',
'image_sample'    => 'Voorbeeld.jpg',
'image_tip'       => 'Beeld/prentjie/diagram',
'media_sample'    => 'Voorbeeld.ogg',
'media_tip'       => 'Skakel na ander tipe medialêer',
'sig_tip'         => 'Handtekening met datum',
'hr_tip'          => 'Horisontale streep (selde nodig)',

# Edit pages
'summary'                  => 'Opsomming',
'subject'                  => 'Onderwerp/opskrif',
'minoredit'                => 'Klein wysiging',
'watchthis'                => 'Hou bladsy dop',
'savearticle'              => 'Stoor bladsy',
'preview'                  => 'Voorskou',
'showpreview'              => 'Wys voorskou',
'showdiff'                 => 'Wys veranderings',
'anoneditwarning'          => "'''Waarskuwing:''' Aangesien u nie aangeteken is nie, sal u IP-adres in dié blad se wysigingsgeskiedenis gestoor word.",
'missingsummary'           => "'''Onthou:''' Geen opsomming van die wysiging is verskaf nie. As \"Stoor\" weer geklik word, word die wysiging sonder opsomming gestoor.",
'missingcommenttext'       => 'Tik die opsomming onder.',
'summary-preview'          => 'Opsomming nakijken',
'blockedtitle'             => 'Gebruiker is geblokkeer',
'blockedtext'              => "<big>'''U gebruikersnaam of IP-adres is geblokkeer.'''</big>

Die blokkering is deur $1 gedoen. Die rede gegee is ''$2''.

* Begin van blokkering: $8
* Verval van blokkering: $6
* Blokkering gemik op: $7

U mag $1 of een van die ander [[{{MediaWiki:Grouppage-sysop}}|administreerders]] kontak om dit te bespreek.
U kan nie die 'e-pos hierdie gebruiker' opsie gebruik tensy 'n geldige e-pos adres gespesifiseer is in U [[Special:Preferences|rekening voorkeure]] en U is nie geblokkeer om dit te gebruik nie. U huidige IP-adres is $3, en die blokkering ID is #$5. Sluit asseblief een of albei hierdie verwysings in by enige navrae.",
'blockednoreason'          => 'geen rede gegeef nie',
'whitelistedittitle'       => 'Inteken benodig om te redigeer',
'whitelistedittext'        => 'U moet $1 om bladsye te wysig.',
'whitelistreadtitle'       => 'Inteken benodig om te bekyk',
'whitelistacctitle'        => "U kan nie 'n rekening te skep nie",
'confirmedittitle'         => 'E-pos-bevestiging nodig om te redigeer',
'confirmedittext'          => 'U moet u e-posadres bevestig voor u bladsye wysig. Verstel en bevestig asseblief u e-posadres by u [[Special:Preferences|voorkeure]].',
'loginreqtitle'            => 'Inteken Benodig',
'loginreqlink'             => 'teken in',
'accmailtitle'             => 'Wagwoord gestuur.',
'accmailtext'              => "Die wagwoord van '$1' is gestuur aan $2.",
'newarticle'               => '(Nuut)',
'newarticletext'           => "Die bladsy waarna geskakel is, bestaan nie.
Om 'n nuwe bladsy te skep, tik in die invoerboks hier onder. Lees die [[{{MediaWiki:Helppage}}|hulpbladsy]]
vir meer inligting.
Indien jy per ongeluk hier is, gebruik jou blaaier se '''terug''' knop.",
'anontalkpagetext'         => "----''Hierdie is die besprekingsblad vir 'n anonieme gebruiker wat nog nie 'n rekening geskep het nie of wat dit nie gebruik nie. Daarom moet ons sy/haar numeriese IP-adres gebruik vir identifikasie. Só 'n adres kan deur verskeie gebruikers gedeel word. Indien jy 'n anonieme gebruiker is wat voel dat ontoepaslike kommentaar teen jou gerig is, [[Special:Userlogin|skep 'n rekening of teken in]] om verwarring met ander anonieme gebruikers te voorkom.''",
'noarticletext'            => 'Daar is tans geen inligting vir hierdie artikel nie. Jy kan [[Special:Search/{{PAGENAME}}|soek vir hierdie bladsytitel]] in ander bladsye of [{{fullurl:{{FULLPAGENAME}}|action=edit}} wysig hierdie bladsy].',
'clearyourcache'           => "'''Let wel''': Na die voorkeure gestoor is, moet u blaaier se kasgeheue verfris word om die veranderinge te sien: '''Mozilla:''' klik ''Reload'' (of ''Ctrl-R''), '''IE / Opera:''' ''Ctrl-F5'', '''Safari:''' ''Cmd-R'', '''Konqueror''' ''Ctrl-R''.",
'usercssjsyoucanpreview'   => '<strong>Wenk:</strong> Gebruik die "Wys voorskou"-knoppie om u nuwe CSS/JS te toets voor u stoor.',
'usercsspreview'           => "'''Onthou hierdie is slegs 'n voorskou van u gebruiker-CSS, dit is nog nie gestoor nie.'''",
'userjspreview'            => "'''Onthou hierdie is slegs 'n toets/voorskou van u gebruiker-JavaScript, dit is nog nie gestoor nie.'''",
'updated'                  => '(Gewysig)',
'note'                     => '<strong>Nota:</strong>',
'previewnote'              => "<strong>Onthou dat hierdie slegs 'n voorskou is en nog nie gestoor is nie!</strong>",
'previewconflict'          => 'Hierdie voorskou vertoon die teks in die boonste teksarea soos dit sou lyk indien jy die bladsy stoor.',
'session_fail_preview'     => '<strong>Jammer! Weens verlies aan sessie-inligting is die wysiging nie verwerk nie.
Probeer asseblief weer. As dit steeds nie werk nie, probeer om af en weer aan te teken.</strong>',
'editing'                  => 'Besig om $1 te wysig',
'editingsection'           => 'Besig om $1 (onderafdeling) te wysig',
'editingcomment'           => 'Besig om $1 (kommentaar) te wysig',
'editconflict'             => 'Wysigingskonflik: $1',
'explainconflict'          => 'Iemand anders het hierdie bladsy gewysig sedert jy dit begin verander het.
Die boonste invoerboks het die teks wat tans bestaan.
Jou wysigings word in die onderste invoerboks gewys.
Jy sal jou wysigings moet saamsmelt met die huidige teks.
<strong>Slegs</strong> die teks in die boonste invoerboks sal gestoor word wanneer jy "Stoor bladsy" druk.<br />',
'yourtext'                 => 'Jou teks',
'storedversion'            => 'Gestoorde weergawe',
'editingold'               => "<strong>WAARSKUWING: Jy is besig om 'n ouer weergawe van hierdie bladsy te wysig.
As jy dit stoor, sal enige wysigings sedert hierdie een weer uitgewis word.</strong>",
'yourdiff'                 => 'Wysigings',
'longpagewarning'          => 'WAARSKUWING: Hierdie bladsy is $1 kG groot.
Probeer asseblief die bladsy verkort en die detail na subartikels skuif sodat dit nie 32 kG oorskry nie.',
'readonlywarning'          => "<strong>WAARSKUWING: Die databasis is gesluit vir onderhoud. Dus sal u nie nou u wysigings kan stoor nie. Dalk wil u die teks plak in 'n lêer en stoor vir later. </strong>",
'protectedpagewarning'     => '<strong>WAARSKUWING: Hierdie blad is beskerm, en slegs administrateurs kan die inhoud verander.</strong>',
'semiprotectedpagewarning' => "'''Let wel:''' Hierdie artikel is beskerm sodat slegs ingetekende gebruikers dit kan wysig.",
'templatesused'            => 'Sjablone in gebruik op hierdie blad:',
'templatesusedpreview'     => 'Sjablone in hierdie voorskou gebruik:',
'templatesusedsection'     => 'Sjablone gebruik in hierdie afdeling:',
'template-protected'       => '(beskermd)',
'hiddencategories'         => "Hierdie bladsy is 'n lid van {{PLURAL:$1|1 versteekte kategorie|$1 versteekte kategorië}}:",
'nocreatetitle'            => 'Bladsy skepping beperk',
'permissionserrorstext'    => 'U het nie toestemming om hierdie te doen nie, om die volgende {{PLURAL:$1|rede|redes}}:',

# Account creation failure
'cantcreateaccounttitle' => 'Kan nie rekening skep nie',

# History pages
'viewpagelogs'        => 'Bekyk logboeke vir hierdie bladsy',
'nohistory'           => 'Daar is geen wysigingsgeskiedenis vir hierdie bladsy nie.',
'revnotfound'         => 'Weergawe nie gevind nie',
'revnotfoundtext'     => 'Die ou weergawe wat jy aangevra het kon nie gevind word nie. Gaan asseblief die URL na wat jy gebruik het.',
'currentrev'          => 'Huidige wysiging',
'revisionasof'        => 'Wysiging soos op $1',
'previousrevision'    => '← Ouer weergawe',
'nextrevision'        => 'Nuwer weergawe →',
'currentrevisionlink' => 'bekyk huidige weergawe',
'cur'                 => 'huidige',
'next'                => 'volgende',
'last'                => 'vorige',
'page_first'          => 'eerste',
'page_last'           => 'laaste',
'histlegend'          => 'Byskrif: (huidige) = verskil van huidige weergawe,
(vorige) = verskil van vorige weergawe, M = klein wysiging',
'deletedrev'          => '[geskrap]',
'histfirst'           => 'Oudste',
'histlast'            => 'Nuutste',
'historyempty'        => '(leeg)',

# Revision feed
'history-feed-title' => 'Weergawegeskiedenis',

# Revision deletion
'rev-deleted-user'  => '(gebruikersnaam geskrap)',
'rev-delundel'      => 'wys/versteek',
'pagehist'          => 'Bladsy geskiedenis',
'deletedhist'       => 'Verwyderde geskiedenis',
'revdelete-content' => 'inhoud',
'revdelete-summary' => 'redigeringsopsomming',
'revdelete-uname'   => 'gebruikersnaam',

# History merging
'mergehistory-from'                => 'Bronbladsy:',
'mergehistory-into'                => 'Bestemmingsbladsy:',
'mergehistory-no-source'           => 'Bronbladsy $1 bestaan nie.',
'mergehistory-no-destination'      => 'Bestemmingsbladsy $1 bestaan nie.',
'mergehistory-invalid-source'      => "Bronbladsy moet 'n geldige titel wees.",
'mergehistory-invalid-destination' => "Bestemmingsbladsy moet 'n geldige titel wees.",

# Diffs
'history-title'           => 'Weergawegeskiedenis van "$1"',
'difference'              => '(Verskil tussen weergawes)',
'lineno'                  => 'Lyn $1:',
'compareselectedversions' => 'Vergelyk gekose weergawes',
'editundo'                => 'maak ongedaan',

# Search results
'searchresults'         => 'soekresultate',
'searchresulttext'      => 'Vir meer inligting oor {{SITENAME}} soekresultate, lees [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'        => 'Vir navraag "[[:$1]]"',
'searchsubtitleinvalid' => 'Vir navraag "$1"',
'noexactmatch'          => "'''Geen bladsy met die titel \"\$1\" bestaan nie.''' Probeer 'n volteksnavraag of [[:\$1|maak die bladsy]].",
'titlematches'          => 'Artikeltitel resultate',
'notitlematches'        => 'Geen artikeltitel resultate nie',
'textmatches'           => 'Artikelteks resultate',
'notextmatches'         => 'Geen artikelteks resultate nie',
'prevn'                 => 'vorige $1',
'nextn'                 => 'volgende $1',
'viewprevnext'          => 'Kyk na ($1) ($2) ($3).',
'search-result-size'    => '$1 ({{PLURAL:$2|1 woord|$2 woorde}})',
'showingresults'        => "Hier volg {{PLURAL:$1|'''1''' resultaat|'''$1''' resultate}} wat met #'''$2''' begin.",
'nonefound'             => "<strong>Nota</strong>: onsuksesvolle navrae word gewoonlik veroorsaak deur 'n soektog met algemene
woorde wat nie geindekseer word nie, of spesifisering van meer as een woord (slegs blaaie wat alle navraagwoorde
bevat, word gewys).",
'powersearch'           => 'Soek',
'powersearch-legend'    => 'Gevorderde soektog',
'powersearchtext'       => '
Search in namespaces :<br />
$1<br />
$2 List redirects   Search for $3 $9',
'search-external'       => 'Eksterne soektog',
'searchdisabled'        => '{{SITENAME}} se soekfunksie is tans afgeskakel ter wille van werkverrigting. Gebruik gerus intussen Google of Yahoo! Let daarop dat hulle indekse van die {{SITENAME}}-inhoud verouderd mag wees.',

# Preferences page
'preferences'              => 'Voorkeure',
'mypreferences'            => 'My voorkeure',
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
'math'                     => 'Wiskunde',
'dateformat'               => 'Datumformaat',
'datedefault'              => 'Geen voorkeur',
'datetime'                 => 'Datum en tyd',
'math_failure'             => 'Kon nie verbeeld nie',
'math_unknown_error'       => 'onbekende fout',
'math_unknown_function'    => 'onbekende funksie',
'math_lexing_error'        => 'leksikale fout',
'math_syntax_error'        => 'sintaksfout',
'prefs-personal'           => 'Gebruikersdata',
'prefs-rc'                 => 'Onlangse wysigings',
'prefs-watchlist'          => 'Dophoulys',
'prefs-watchlist-days'     => 'Aantal dae om in dophoulys te wys:',
'prefs-watchlist-edits'    => 'Aantal wysigings om in uitgebreide dophoulys te wys:',
'prefs-misc'               => 'Allerlei',
'saveprefs'                => 'Stoor voorkeure',
'resetprefs'               => 'Herstel voorkeure',
'oldpassword'              => 'Ou wagwoord',
'newpassword'              => 'Nuwe wagwoord',
'retypenew'                => 'Tik nuwe wagwoord weer in',
'textboxsize'              => 'Wysiging',
'rows'                     => 'Rye',
'columns'                  => 'Kolomme',
'searchresultshead'        => 'Soekresultate',
'resultsperpage'           => 'Aantal resultate om te wys',
'contextlines'             => 'Aantal lyne per resultaat',
'contextchars'             => 'Karakters konteks per lyn',
'recentchangesdays'        => 'Aantal dae wat in onlangse wysigings vertoon word:',
'recentchangescount'       => 'Aantal titels in onlangse wysigings',
'savedprefs'               => 'Jou voorkeure is gestoor.',
'timezonelegend'           => 'Tydsone',
'timezonetext'             => 'Aantal ure waarmee plaaslike tyd van UTC verskil.',
'localtime'                => 'Plaaslike tyd',
'timezoneoffset'           => 'Verplasing¹',
'servertime'               => 'Tyd op die bediener is nou',
'guesstimezone'            => 'Vul in vanaf webblaaier',
'allowemail'               => 'Laat e-pos van ander toe',
'defaultns'                => 'Verstek naamruimtes vir soektog:',
'default'                  => 'verstek',
'files'                    => 'Lêers',

# User rights
'userrights-lookup-user'      => 'Beheer gebruikersgroepe',
'editusergroup'               => 'Wysig gebruikersgroepe',
'editinguser'                 => "Besig om gebruikersrechte van gebruiker '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]] | [[Special:Contributions/$1|{{int:contribslink}}]]) te wysig",
'userrights-editusergroup'    => 'wysig gebruikersgroepe',
'saveusergroups'              => 'Stoor gebruikersgroepe',
'userrights-groupsmember'     => 'Lid van:',
'userrights-groupsremovable'  => 'Skrapbare groepen:',
'userrights-groupsavailable'  => 'Beskikbare groepen:',
'userrights-reason'           => 'Rede vir wysiging:',
'userrights-changeable-col'   => 'Groepe wat U kan verander',
'userrights-unchangeable-col' => 'Groepe wat U nie kan verander nie',

# Groups
'group'            => 'Groep:',
'group-bot'        => 'Robotte',
'group-sysop'      => 'Administrateurs',
'group-bureaucrat' => 'Burokrate',
'group-all'        => '(alle)',

'group-bot-member'        => 'Robot',
'group-sysop-member'      => 'Administrateur',
'group-bureaucrat-member' => 'Burokraat',

'grouppage-bot'        => '{{ns:project}}:Robotte',
'grouppage-sysop'      => '{{ns:project}}:Administrateurs',
'grouppage-bureaucrat' => '{{ns:project}}:Burokrate',

# User rights log
'rightslog'      => 'Gebruikersrechtenlogboek',
'rightslogentry' => 'groep lidmaatskap verander vir $1 van $2 na $3',
'rightsnone'     => '(geen)',

# Recent changes
'nchanges'          => '$1 {{PLURAL:$1|wysiging|wysigings}}',
'recentchanges'     => 'Onlangse wysigings',
'rcnote'            => "Hier volg die laaste {{PLURAL:$1|'''$1''' wysiging|'''$1''' wysigings}} gedurende die afgelope {{PLURAL:$2|dag|'''$2''' dae}}, op $3.",
'rcnotefrom'        => 'Hier onder is die wysigings sedert <b>$2</b> (tot by <b>$1</b> word gewys).',
'rclistfrom'        => 'Vertoon wysigings vanaf $1',
'rcshowhideminor'   => '$1 klein wysigings',
'rcshowhidebots'    => '$1 robotte',
'rcshowhideliu'     => '$1 aangetekende gebruikers',
'rcshowhideanons'   => '$1 anonieme gebruikers',
'rcshowhidepatr'    => '$1 gepatrolleerde wysigings',
'rcshowhidemine'    => '$1 my wysigings',
'rclinks'           => 'Vertoon die laaste $1 wysigings in die afgelope $2 dae<br />$3',
'diff'              => 'verskil',
'hist'              => 'geskiedenis',
'hide'              => 'versteek',
'show'              => 'wys',
'minoreditletter'   => 'k',
'newpageletter'     => 'N',
'boteditletter'     => 'b',
'rc_categories'     => 'Beperk tot kategorië (skei met "|")',
'rc_categories_any' => 'Enige',
'newsectionsummary' => '/* $1 */ nuwe afdeling',

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
[[{{MediaWiki:Copyrightpage}}|beleid oor prentgebruik]].

Om prente wat voorheen gelaai is te sien of te soek, gaan na die
[[Special:Imagelist|lys van gelaaide prente]].
Laai van lêers en skrappings word aangeteken in die
[[Special:Log/upload|laailog]].

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
'uploadlogpagetext' => "Hier volg 'n lys van die mees onlangse lêers wat gelaai is.",
'filename'          => 'Lêernaam',
'filedesc'          => 'Opsomming',
'fileuploadsummary' => 'Opsomming:',
'filestatus'        => 'Outeursregsituasie:',
'filesource'        => 'Bron:',
'uploadedfiles'     => 'Gelaaide lêers',
'ignorewarnings'    => 'Ignoreer enige waarskuwings',
'minlength1'        => 'Prentname moet ten minste een letter lank wees.',
'illegalfilename'   => 'Die lêernaam "$1" bevat karakters wat nie toegelaat word in bladsytitels nie. Verander asseblief die naam en probeer die lêer weer laai.',
'badfilename'       => 'Prentnaam is verander na "$1".',
'largefileserver'   => 'Hierdie lêer is groter as wat die bediener se opstelling toelaat.',
'emptyfile'         => "Die lêer wat jy probeer oplaai het blyk leeg te wees. Dit mag wees omdat jy 'n tikfout in die lêernaam gemaak het. Gaan asseblief na en probeer weer.",
'successfulupload'  => 'Laai suksesvol',
'uploadwarning'     => 'Laaiwaarskuwing',
'savefile'          => 'Stoor lêer',
'uploadedimage'     => 'het "[[$1]]" gelaai',
'overwroteimage'    => 'het een nuwe weergawe van "[[$1]]" gelaai',
'uploaddisabled'    => 'Laai is uitgeskakel',
'uploadcorrupt'     => "Die lêer is foutief of is van 'n verkeerde tipe. Gaan asseblief die lêer na en laai weer op.",
'sourcefilename'    => 'Bronlêernaam:',
'destfilename'      => 'Teikenlêernaam:',
'watchthisupload'   => 'Hou hierdie bladsy dop',

'license' => 'Lisensiëring:',

# Special:Imagelist
'imagelist_search_for'  => 'Soek vir medianaam:',
'imgdesc'               => 'beskrywing',
'imgfile'               => 'lêer',
'imagelist'             => 'Prentelys',
'imagelist_date'        => 'Datum',
'imagelist_name'        => 'Naam',
'imagelist_user'        => 'Gebruiker',
'imagelist_size'        => 'Grootte',
'imagelist_description' => 'Beskryving',

# Image description page
'filehist'                  => 'Lêergeskiedenis',
'filehist-deleteall'        => 'verwyder alles',
'filehist-deleteone'        => 'verwyder hierdie',
'filehist-current'          => 'huidig',
'filehist-datetime'         => 'Datum/Tyd',
'filehist-user'             => 'Gebruiker',
'filehist-dimensions'       => 'Dimensies',
'filehist-filesize'         => 'Lêergrootte',
'filehist-comment'          => 'Opmerking',
'imagelinks'                => 'Prentskakels',
'linkstoimage'              => 'Die volgende bladsye gebruik hierdie prent:',
'nolinkstoimage'            => 'Daar is geen bladsye wat hierdie prent gebruik nie.',
'noimage'                   => "Geen lêer met so 'n naam bestaan nie; $1 gerus.",
'noimage-linktext'          => 'laai dit',
'uploadnewversion-linktext' => 'Laai een nuwe weergawe van hierdie lêer',

# File deletion
'filedelete'                  => 'Skrap $1',
'filedelete-legend'           => 'Skrap lêer',
'filedelete-comment'          => 'Rede vir skrapping:',
'filedelete-submit'           => 'Skrap',
'filedelete-success'          => "'''$1''' is geskrap.",
'filedelete-success-old'      => '<span class="plainlinks">Die weergawe van \'\'\'[[Media:$1|$1]]\'\'\' op $3, $2 is geskrap.</span>',
'filedelete-reason-otherlist' => 'Andere rede',
'filedelete-reason-dropdown'  => '*Algemene skrappingsredes:
** Kopieregskending
** Duplikaatlêer',

# MIME search
'mimesearch' => 'MIME-soek',
'download'   => 'laai af',

# List redirects
'listredirects' => 'Lys aansture',

# Unused templates
'unusedtemplates'     => 'Ongebruikte sjablone',
'unusedtemplatestext' => "Hierdie blad lys alle bladsye in die sjabloonnaamruimte wat nêrens in 'n ander blad ingesluit word nie. Onthou om ook ander skakels na die sjablone na te gaan voor verwydering.",
'unusedtemplateswlh'  => 'ander skakels',

# Random page
'randompage' => 'Lukrake bladsy',

# Random redirect
'randomredirect' => 'Lukrake aanstuur',

# Statistics
'statistics'    => 'Statistiek',
'sitestats'     => 'Werfstatistiek',
'userstats'     => 'Gebruikerstatistiek',
'sitestatstext' => "Daar is {{PLURAL:\$1|'''1''' bladsy|'n totaal van '''\$1''' bladsye}} in die databasis.
Dit sluit \"bespreek\"-bladsye in, bladsye oor {{SITENAME}}, minimale \"verkorte\"
bladsye, wegwysbladsye, en ander wat waarskynlik nie as artikels kwalifiseer nie.
Uitsluitend bogenoemde, is daar {{PLURAL:\$2|'''1''' bladsy|'''\$2''' bladsye}} wat waarskynlik {{PLURAL:\$2|bladsy|bladsye}} met ware inhoud is.

'''\$8''' {{PLURAL:\$8|lêer|lêers}} is gelaai.

{{PLURAL:\$3|Bladsy is al '''1''' keer aangevra|Bladsye is al '''\$3''' kere aangevra}}, en '''\$4''' keer verander sedert hierdie wiki opgezet is.
Dit werk uit op gemiddeld '''\$5''' veranderings per bladsy, en bladsye word '''\$6''' keer per verandering aangevra.

Die ''[http://meta.wikimedia.org/wiki/Help:Job_queue job queue]''-lengte is '''\$7'''.",
'userstatstext' => "Daar is {{PLURAL:$1|'''1''' geregistreerde [[Special:Listusers|gebruiker]]|'''$1''' geregistreerde [[Special:Listusers|gebruikers]]}}, waarvan '''$2''' (of '''$4%''') $5 regte het.",

'disambiguations'     => 'Bladsye wat onduidelikhede opklaar',
'disambiguationspage' => 'Template:Dubbelsinnig',

'doubleredirects'     => 'Dubbele aansture',
'doubleredirectstext' => '<b>Let op:</b> Hierdie lys bevat moontlik false positiewe. Dit beteken gewoonlik dat daar nog teks met skakels onder die eerste #REDIRECT is.<br />
Elke ry bevat skakels na die eerste en die tweede aanstuur, asook die eerste reël van van die tweede aanstuurteks, wat gewoonlik die "regte" teikenbladsy gee waarna die eerste aanstuur behoort te wys.',

'brokenredirects'        => 'Stukkende aansture',
'brokenredirectstext'    => "Die volgende aansture skakel na 'n bladsy wat nie bestaan nie.",
'brokenredirects-edit'   => '(wysig)',
'brokenredirects-delete' => '(skrap)',

'withoutinterwiki'        => 'Bladsye sonder taalskakels',
'withoutinterwiki-submit' => 'Wys',

'fewestrevisions' => 'Artikels met die minste wysigings',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|greep|grepe}}',
'ncategories'             => '$1 {{PLURAL:$1|kategorie|kategorieë}}',
'nlinks'                  => '$1 {{PLURAL:$1|skakel|skakels}}',
'nmembers'                => '$1 {{PLURAL:$1|lid|lede}}',
'nrevisions'              => '$1 {{PLURAL:$1|weergawe|weergawes}}',
'nviews'                  => '$1 {{PLURAL:$1|keer|kere}} aangevra',
'lonelypages'             => 'Weesbladsye',
'uncategorizedpages'      => 'Ongekategoriseerde bladsye',
'uncategorizedcategories' => 'Ongekategoriseerde kategorieë',
'uncategorizedimages'     => 'Ongekategoriseerde lêers',
'uncategorizedtemplates'  => 'Ongekategoriseerde sjablone',
'unusedcategories'        => 'Ongebruikte kategorieë',
'unusedimages'            => 'Ongebruikte lêers',
'popularpages'            => 'Gewilde bladsye',
'wantedcategories'        => 'Gesoekte kategorieë',
'wantedpages'             => 'Gesogte bladsye',
'mostlinked'              => 'Bladsye met meeste skakels daarheen',
'mostlinkedcategories'    => 'Kategorieë met die meeste skakels daarheen',
'mostlinkedtemplates'     => 'Sjablone met die meeste skakels daarheen',
'mostcategories'          => 'Artikels met die meeste kategorieë',
'mostimages'              => 'Beelde met meeste skakels daarheen',
'mostrevisions'           => 'Artikels met meeste wysigings',
'prefixindex'             => 'Alle bladsye (voorvoegselindeks)',
'shortpages'              => 'Kort bladsye',
'longpages'               => 'Lang bladsye',
'deadendpages'            => 'Doodloopbladsye',
'protectedpages'          => 'Beskermde bladsye',
'listusers'               => 'Gebruikerslys',
'specialpages'            => 'Spesiale bladsye',
'spheading'               => 'Spesiale bladsye',
'newpages'                => 'Nuwe bladsye',
'newpages-username'       => 'Gebruikersnaam:',
'ancientpages'            => 'Oudste bladsye',
'move'                    => 'Skuif',
'movethispage'            => 'Skuif hierdie bladsy',
'unusedimagestext'        => "Let asseblief op dat ander webwerwe, soos die internasionale {{SITENAME}}s, dalk met 'n direkte URL na 'n prent skakel, so die prent sal dus hier verskyn al word dit aktief gebruik.",
'unusedcategoriestext'    => 'Die volgende kategoriebladsye bestaan alhoewel geen artikel of kategorie hulle gebruik nie.',
'notargettitle'           => 'Geen teiken',
'notargettext'            => "Jy het nie 'n teikenbladsy of gebruiker waarmee hierdie funksie moet werk, gespesifiseer nie.",

# Book sources
'booksources'               => 'Boekbronne',
'booksources-search-legend' => 'Soek vir boekbronne',
'booksources-go'            => 'Gaan',

# Special:Log
'specialloguserlabel'  => 'Gebruiker:',
'speciallogtitlelabel' => 'Titel:',
'log'                  => 'Logboeke',
'all-logs-page'        => 'Alle logboeke',
'log-search-legend'    => 'Soek vir logboeke',
'log-search-submit'    => 'Gaan',
'alllogstext'          => "Vertoon 'n samestelling van laai-, skrap-, beskerm-, blok- en administrateurlogs van {{SITENAME}}.
Jy kan die vertoning vernou deur 'n logtipe, gebruikersnaam of spesifieke blad te kies.",

# Special:Allpages
'allpages'          => 'Alle bladsye',
'alphaindexline'    => '$1 tot $2',
'nextpage'          => 'Volgende blad ($1)',
'prevpage'          => 'Vorige bladsye ($1)',
'allpagesfrom'      => 'Wys bladsye vanaf:',
'allarticles'       => 'Alle artikels',
'allinnamespace'    => 'Alle bladsye (naamruimte $1)',
'allnotinnamespace' => 'Alle bladsye (nie in naamruimte $1 nie)',
'allpagesprev'      => 'Vorige',
'allpagesnext'      => 'Volgende',
'allpagessubmit'    => 'Gaan',
'allpagesprefix'    => 'Wys bladsye wat begin met:',

# Special:Listusers
'listusersfrom'      => 'Wys gebruikers, beginnende by:',
'listusers-submit'   => 'Wys',
'listusers-noresult' => 'Geen gebruiker gevind.',

# E-mail user
'mailnologin'     => 'Geen versendadres beskikbaar',
'mailnologintext' => "U moet [[Special:Userlogin|ingeteken]] wees en 'n geldige e-posadres in die [[Special:Preferences|voorkeure]] hê om e-pos aan ander gebruikers te stuur.",
'emailuser'       => 'Stuur e-pos na hierdie gebruiker',
'emailpage'       => 'Stuur e-pos na gebruiker',
'emailpagetext'   => 'As dié gerbuiker \'n geldige e-posadres in sy/haar gebruikersvoorkeure het, sal hierdie vorm \'n enkele boodskap stuur. Die e-posadres in jou gebruikersvoorkeure sal verkyn as die "Van"-adres van die pos. Dus sal die ontvanger kan terug antwoord.',
'defemailsubject' => '{{SITENAME}}-epos',
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
'watchlist'            => 'My dophoulys',
'mywatchlist'          => 'My dophoulys',
'watchlistfor'         => "(vir '''$1''')",
'nowatchlist'          => 'Jy het geen items in jou dophoulys nie.',
'watchnologin'         => 'Nie ingeteken nie',
'watchnologintext'     => 'Jy moet [[Special:Userlogin|ingeteken]]
wees om jou dophoulys te verander.',
'addedwatch'           => 'Bygevoeg tot dophoulys',
'addedwatchtext'       => 'Die bladsy "$1" is by u [[Special:Watchlist|dophoulys]] gevoeg.
Die bladsy "$1" is by u [[Special:Watchlist|dophoulys]] gevoeg. Toekomstige veranderinge aan hierdie bladsy en sy verwante besprekingsblad sal daar verskyn en die bladsy sal in \'\'\'vetdruk\'\'\' verskyn in die [[Special:Recentchanges|lys van onlangse wysigings]], sodat u dit makliker kan raaksien.

As u die bladsy later van u dophoulys wil verwyder, kliek "verwyder van dophoulys" in die kieslys bo-aan die bladsy.',
'removedwatch'         => 'Afgehaal van dophoulys',
'removedwatchtext'     => 'Die bladsy "[[:$1]]" is van u dophoulys afgehaal.',
'watch'                => 'Hou dop',
'watchthispage'        => 'Hou hierdie bladsy dop',
'unwatch'              => 'Verwyder van dophoulys',
'unwatchthispage'      => 'Moenie meer dophou',
'notanarticle'         => "Nie 'n artikel",
'watchnochange'        => 'Geen item op die dophoulys is geredigeer in die gekose periode nie.',
'watchlistcontains'    => 'Jou dophoulys bevat $1 {{PLURAL:$1|bladsy|bladsye}}.',
'wlnote'               => "Hier volg die laaste {{PLURAL:$1|verandering|'''$1''' veranderings}} binne die laaste {{PLURAL:$2|uur|'''$2''' ure}}.",
'wlshowlast'           => 'Wys afgelope $1 ure, $2 dae of $3',
'watchlist-show-bots'  => "Wys 'bot' wysigings",
'watchlist-hide-bots'  => 'Versteek robotte',
'watchlist-show-own'   => 'Wys my wysigings',
'watchlist-hide-own'   => 'Versteek my wysigings',
'watchlist-show-minor' => 'Wys klein wysigings',
'watchlist-hide-minor' => 'Versteek klein wysigings',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Plaas op dophoulys...',
'unwatching' => 'Verwyder van dophoulys...',

'enotif_newpagetext'           => "Dis 'n nuwe bladsy.",
'enotif_impersonal_salutation' => '{{SITENAME}} gebruiker',
'changed'                      => 'verander',

# Delete/protect/revert
'deletepage'                  => 'Skrap bladsy',
'confirm'                     => 'Bevestig',
'excontent'                   => "inhoud was: '$1'",
'excontentauthor'             => "Inhoud was: '$1' (en '[[Special:Contributions/$2|$2]]' was die enigste bydraer)",
'exbeforeblank'               => "Inhoud voor uitwissing was: '$1'",
'exblank'                     => 'bladsy was leeg',
'delete-confirm'              => 'Skrap "$1"',
'delete-legend'               => 'Skrap',
'confirmdeletetext'           => "Jy staan op die punt om 'n bladsy of prent asook al hulle geskiedenis uit die databasis te skrap.
Bevestig asseblief dat jy dit wil doen, dat jy die gevolge verstaan en dat jy dit doen in ooreenstemming met die [[{{MediaWiki:Policy-url}}]].",
'actioncomplete'              => 'Aksie uitgevoer',
'deletedtext'                 => '"<nowiki>$1</nowiki>" is geskrap.
Kyk na $2 vir \'n rekord van onlangse skrappings.',
'deletedarticle'              => '"$1" geskrap',
'dellogpage'                  => 'Skraplogboek',
'dellogpagetext'              => "Hier onder is 'n lys van die mees onlangse skrappings. Alle tye is bedienertyd (UGT).",
'deletionlog'                 => 'skrappingslogboek',
'reverted'                    => 'Het terug gegaan na vroeëre weergawe',
'deletecomment'               => 'Rede vir skrapping',
'deletereasonotherlist'       => 'Andere rede',
'rollback'                    => 'Rol veranderinge terug',
'rollback_short'              => 'Rol terug',
'rollbacklink'                => 'Rol terug',
'rollbackfailed'              => 'Terugrol onsuksesvol',
'cantrollback'                => 'Kan nie na verandering terug keer nie; die laaste bydraer is die enigste outer van hierdie bladsy.',
'editcomment'                 => 'Die wysigopsomming was: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'                  => 'Wysigings deur [[Special:Contributions/$2|$2]] teruggerol na laaste weergawe deur $1', # Additional available: $3: revid of the revision reverted to, $4: timestamp of the revision reverted to, $5: revid of the revision reverted from, $6: timestamp of the revision reverted from
'rollback-success'            => 'Wysigings deur $1 teruggerol; terugverander na laaste weergawe deur $2.',
'protectlogpage'              => 'Beskermlogboek',
'protectedarticle'            => 'het [[$1]] beskerm',
'unprotectedarticle'          => 'het beskerming van [[$1]] verwyder',
'protect-title'               => 'Beskerm "$1"',
'protect-legend'              => 'Bevestig beskerming',
'protectcomment'              => 'Rede vir beskerming:',
'protectexpiry'               => 'Verval:',
'protect-default'             => '(normaal)',
'protect-level-autoconfirmed' => 'Beskerm teen anonieme wysigings',
'protect-level-sysop'         => 'Slegs administrateurs',
'pagesize'                    => '(grepe)',

# Restrictions (nouns)
'restriction-edit' => 'Wysig',
'restriction-move' => 'Skuif',

# Undelete
'undelete'               => 'Herstel geskrapte bladsy',
'undeletepage'           => 'Bekyk en herstel geskrapte bladsye',
'undeletepagetext'       => 'Die volgende bladsye is geskrap, maar hulle is nog in die argief en kan herstel word. Die argief kan periodiek skoongemaak word.',
'undeleterevisions'      => '$1 {{PLURAL:$1|weergawe|weergawes}} in argief',
'undeletehistory'        => "As jy die bladsy herstel, sal alle weergawes herstel word.
As 'n nuwe bladsy met dieselfde naam sedert die skrapping geskep is, sal die herstelde weergawes in die nuwe bladsy se voorgeskiedenis verskyn en die huidige weergawe van die lewendige bladsy sal nie outomaties vervang word nie.",
'undeletebtn'            => 'Herstel',
'undeletelink'           => 'herstel',
'undeletedarticle'       => 'het "$1" herstel',
'cannotundelete'         => 'Skrapping onsuksesvol; miskien het iemand anders dié bladsy al geskrap.',
'undelete-search-submit' => 'Soek',

# Namespace form on various pages
'namespace'      => 'Naamruimte:',
'invert'         => 'Omgekeerde seleksie',
'blanknamespace' => '(Hoof)',

# Contributions
'contributions' => 'Gebruikersbydraes',
'mycontris'     => 'My bydraes',
'contribsub2'   => 'Vir $1 ($2)',
'nocontribs'    => 'Geen veranderinge wat by hierdie kriteria pas, is gevind nie.',
'uctop'         => ' (boontoe)',
'month'         => 'Vanaf maand (en vroeër):',
'year'          => 'Vanaf jaar (en vroeër):',

'sp-contributions-newbies'     => 'Wys slegs bydraes deur nuwe rekenings',
'sp-contributions-newbies-sub' => 'Vir nuwe gebruikers',
'sp-contributions-blocklog'    => 'Blokkeerlogboek',
'sp-contributions-search'      => 'Soek na bydraes',
'sp-contributions-submit'      => 'Vertoon',

# What links here
'whatlinkshere'       => 'Skakels hierheen',
'whatlinkshere-title' => 'Bladsye die skakel na $1',
'whatlinkshere-page'  => 'Bladsy:',
'linklistsub'         => '(Lys van skakels)',
'linkshere'           => "Die volgende bladsye skakel na '''[[:$1]]''':",
'nolinkshere'         => "Geen bladsye skakel na '''[[:$1]]'''.",
'isredirect'          => 'Stuur bladsy aan',
'whatlinkshere-prev'  => '{{PLURAL:$1|vorige|vorige $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|volgende|volgende $1}}',
'whatlinkshere-links' => '← skakels',

# Block/unblock
'blockip'            => 'Blok gebruiker',
'blockip-legend'     => 'Blok gebruiker of IP-adres',
'blockiptext'        => "Gebruik die vorm hier onder om skryftoegang van 'n sekere IP-adres te blok.
Dit moet net gedoen word om vandalisme te voorkom en in ooreenstemming met [[{{MediaWiki:Policy-url}}|{{SITENAME}} policy]].
Vul 'n spesifieke rede hier onder in (haal byvoorbeeld spesifieke bladsye wat gevandaliseer is, aan).",
'ipaddress'          => 'IP-adres',
'ipadressorusername' => 'IP-adres of gebruikernaam:',
'ipbexpiry'          => 'Duur:',
'ipbreason'          => 'Rede',
'ipbreasonotherlist' => 'Ander rede',
'ipbreason-dropdown' => '*Algemene redes vir blokkering
** Invoeg van valse inligting
** Skrap van bladsyinhoud
** "Spam" van skakels na eksterne webwerwe
** Invoeg van gemors op bladsye
** Intimiderende gedrag (teistering)
** Misbruik van veelvuldige rekeninge
** Onaanvaarbare gebruikersnaam',
'ipbsubmit'          => 'Blok hierdie adres',
'ipbother'           => 'Ander tydperk:',
'ipboptions'         => '2 ure:2 hours,1 dag:1 day,3 dae:3 days,1 week:1 week,2 weke:2 weeks,1 maand:1 month,3 maande:3 months,6 maande:6 months,1 jaar:1 year,onbeperk:infinite', # display1:time1,display2:time2,...
'ipbotheroption'     => 'ander',
'badipaddress'       => 'Die IP-adres is nie in die regte formaat nie.',
'blockipsuccesssub'  => 'Blokkering het geslaag',
'blockipsuccesstext' => 'Die IP-adres "$1" is geblok.
<br />Kyk na [[Special:Ipblocklist|IP block list]] vir \'n oorsig van blokkerings.',
'unblockip'          => 'Maak IP-adres oop',
'unblockiptext'      => "Gebruik die vorm hier onder om skryftoegang te herstel vir 'n voorheen geblokkeerde IP-adres.",
'ipusubmit'          => 'Maak hierdie adres oop',
'ipblocklist'        => 'Lys van geblokkeerde IP-adresse',
'ipblocklist-submit' => 'Soek',
'blocklistline'      => '$1, $2 het $3 geblok ($4)',
'infiniteblock'      => 'oneindig',
'blocklink'          => 'blok',
'unblocklink'        => 'maak oop',
'contribslink'       => 'bydraes',
'blocklogpage'       => 'Blokkeerlogboek',
'blocklogentry'      => '"[[$1]]" is geblokkeer vir \'n periode van $2 $3',
'unblocklogentry'    => 'blokkade van $1 is opgehef:',
'proxyblocker'       => 'Proxyblokker',

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
'unlockdbsuccesstext' => 'Die {{SITENAME}}-databasis is ontsluit.',

# Move page
'move-page'               => 'Skuif "$1"',
'move-page-legend'        => 'Skuif bladsy',
'movepagetext'            => "Die vorm hieronder hernoem 'n bladsy en skuif die hele wysigingsgeskiedenis na die nuwe naam.
Die ou bladsy sal vervang word met 'n aanstuurblad na die nuwe titel.
'''Skakels na die ou bladsytitel sal nie outomaties verander word nie; maak seker dat dubbele aanstuurverwysings nie voorkom nie deur die \"wat skakel hierheen\"-funksie na die skuif te gebruik.''' Dit is jou verantwoordelikheid om seker te maak dat skakels steeds wys na waarheen hulle behoort te gaan.

Let daarop dat 'n bladsy '''nie''' geskuif sal word indien daar reeds 'n bladsy met dieselfde titel bestaan nie, tensy dit leeg of 'n aanstuurbladsy is en geen wysigingsgeskiedenis het nie. Dit beteken dat jy 'n bladsy kan terugskuif na sy ou titel indien jy 'n fout gemaak het, maar jy kan nie 'n bestaande bladsy oorskryf nie.

<b>WAARSKUWING!</b>
Hierdie kan 'n drastiese en onverwagte verandering vir 'n populêre bladsy wees;
maak asseblief seker dat jy die gevolge van hierdie aksie verstaan voordat jy voortgaan. Gebruik ook die ooreenstemmende besprekingsbladsy om oorleg te pleeg met ander bydraers.",
'movearticle'             => 'Skuif bladsy',
'movenologin'             => 'Nie ingeteken nie',
'movenologintext'         => "Jy moet 'n geregistreerde gebruiker wees en [[Special:Userlogin|ingeteken]]
wees om 'n bladsy te skuif.",
'newtitle'                => 'Na nuwe titel',
'move-watch'              => 'Hou hierdie bladsy dop',
'movepagebtn'             => 'Skuif bladsy',
'pagemovedsub'            => 'Verskuiwing het geslaag',
'movepage-moved'          => '<big>\'\'\'"$1" geskuif na "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => "'n Bladsy met daardie naam bestaan reeds, of die naam wat jy gekies het, is nie geldig nie.
Kies asseblief 'n ander naam.",
'talkexists'              => "'''Die bladsy self is suksesvol geskuif, maar die besprekingsbladsy is nie geskuif nie omdat een reeds bestaan met die nuwe titel. Smelt hulle asseblief met die hand saam.'''",
'movedto'                 => 'geskuif na',
'movetalk'                => 'Skuif besprekingsblad ook, indien van toepassing.',
'talkpagemoved'           => 'Die ooreenkomstige besprekingsblad is ook geskuif.',
'talkpagenotmoved'        => 'Die ooreenkomstige besprekingsblad is <strong>nie</strong> geskuif nie.',
'1movedto2'               => '[[$1]] geskuif na [[$2]]',
'1movedto2_redir'         => '[[$1]] geskuif na [[$2]] oor bestaande aanstuur',
'movelogpage'             => 'Skuiflogboek',
'movereason'              => 'Rede:',
'delete_and_move'         => 'Skrap en skuif',
'delete_and_move_text'    => '==Skrapping benodig==

Die teikenartikel "[[$1]]" bestaan reeds. Wil u dit skrap om plek te maak vir die skuif?',
'delete_and_move_confirm' => 'Ja, skrap die bladsy',
'delete_and_move_reason'  => 'Geskrap om plek te maak vir skuif',
'selfmove'                => 'Bron- en teikentitels is dieselfde; kan nie bladsy oor homself skuif nie.',

# Export
'export'            => 'Eksporteer bladsye',
'export-addcattext' => 'Voeg bladsye by van kategorie:',
'export-addcat'     => 'Voeg by',
'export-download'   => 'Stoor as lêer',
'export-templates'  => 'Sluit sjablone in',

# Namespace 8 related
'allmessages'               => 'Stelselboodskappe',
'allmessagesname'           => 'Naam',
'allmessagesdefault'        => 'Verstekteks',
'allmessagescurrent'        => 'Huidige teks',
'allmessagestext'           => "Hierdie is 'n lys boodskappe wat beskikbaar is in die ''MediaWiki''-naamspasie.",
'allmessagesnotsupportedDB' => "Daar is geen ondersteuning vir '''{{ns:special}}:Allmessages''' omdat '''\$wgUseDatabaseMessages''' uitgeskakel is.",
'allmessagesfilter'         => 'Boodskapnaamfilter:',
'allmessagesmodified'       => 'Wys slegs gewysigdes',

# Thumbnails
'thumbnail-more' => 'Vergroot',

# Special:Import
'import'       => 'Voer bladsye in',
'importfailed' => 'Intrek onsuksesvol: $1',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'My gebruikerbladsy',
'tooltip-pt-anonuserpage'         => 'Die gebruikerbladsy vir die IP-adres waaronder u redigeer',
'tooltip-pt-mytalk'               => 'My besprekingsbladsy',
'tooltip-pt-anontalk'             => 'Bespreking oor bydraes van hierdie IP-adres',
'tooltip-pt-preferences'          => 'My voorkeure',
'tooltip-pt-watchlist'            => 'Die lys bladsye wat jy vir veranderinge dophou',
'tooltip-pt-mycontris'            => 'Lys van my bydraes',
'tooltip-pt-login'                => 'Jy word aangemoedig om in te teken; dit is egter nie verpligtend nie.',
'tooltip-pt-anonlogin'            => 'Jy word aangemoedig om in te teken; dit is egter nie verpligtend nie.',
'tooltip-pt-logout'               => 'Teken uit',
'tooltip-ca-talk'                 => 'Bespreking oor die inhoudsbladsy',
'tooltip-ca-edit'                 => 'Jy kan hierdie bladsy redigeer. Gebruik asseblief die voorskouknop vóór jy dit stoor.',
'tooltip-ca-addsection'           => 'Lewer kommentaar by hierdie bespreking.',
'tooltip-ca-viewsource'           => 'Hierdie bladsy is beskerm. Jy kan die bronteks besigtig.',
'tooltip-ca-history'              => 'Ouer weergawes van hierdie bladsy.',
'tooltip-ca-protect'              => 'Beskerm hierdie bladsy',
'tooltip-ca-delete'               => 'Skrap hierdie bladsy',
'tooltip-ca-undelete'             => 'Herstel die bydraes aan hierdie bladsy voordat dit geskrap is',
'tooltip-ca-move'                 => 'Skuif hierdie bladsy',
'tooltip-ca-watch'                => 'Voeg hierdie bladsy tot u dophoulys',
'tooltip-ca-unwatch'              => 'Verwyder hierdie bladsy van u dophoulys',
'tooltip-search'                  => 'Deursoek {{SITENAME}}',
'tooltip-p-logo'                  => 'Tuisblad',
'tooltip-n-mainpage'              => 'Besoek die Tuisblad',
'tooltip-n-portal'                => 'Meer oor die projek, wat jy kan doen, nuttige skakels',
'tooltip-n-recentchanges'         => "'n Lys van onlangse wysigings",
'tooltip-n-randompage'            => "Laai 'n lukrake bladsye",
'tooltip-n-help'                  => 'Vind meer uit oor iets.',
'tooltip-n-sitesupport'           => 'Ondersteun ons',
'tooltip-t-whatlinkshere'         => "'n Lys bladsye wat hierheen skakel",
'tooltip-feed-rss'                => 'RSS-voed vir hierdie bladsy',
'tooltip-feed-atom'               => 'Atom-voed vir hierdie bladsy',
'tooltip-t-contributions'         => "Bekyk 'n lys van bydraes deur hierdie gebruiker",
'tooltip-t-emailuser'             => "Stuur 'n e-pos aan hierdie gebruiker",
'tooltip-t-upload'                => 'Laai lêers op',
'tooltip-t-specialpages'          => "'n Lys van al die spesiale bladsye",
'tooltip-t-print'                 => 'Drukbare weergawe van hierdie bladsy',
'tooltip-t-permalink'             => "'n Permanente skakel na hierdie weergawe van die bladsy",
'tooltip-ca-nstab-main'           => 'Bekyk die inhoudbladsy',
'tooltip-ca-nstab-user'           => 'Bekyk die gebruikerbladsy',
'tooltip-ca-nstab-media'          => 'Bekyk die mediabladsy',
'tooltip-ca-nstab-special'        => "Hierdie is 'n spesiale bladsy; u kan dit nie wysig nie",
'tooltip-ca-nstab-project'        => 'Bekyk die projekbladsy',
'tooltip-ca-nstab-image'          => 'Bekyk die leêrbladsy',
'tooltip-ca-nstab-mediawiki'      => 'Bekyk die stelselboodskap',
'tooltip-ca-nstab-template'       => 'Bekyk die sjabloon',
'tooltip-ca-nstab-help'           => 'Bekyk die hulpbladsy',
'tooltip-ca-nstab-category'       => 'Bekyk die kategoriebladsy',
'tooltip-minoredit'               => "Dui aan hierdie is 'n klein wysiging",
'tooltip-save'                    => 'Stoor jou wysigings',
'tooltip-preview'                 => "Sien 'n voorskou van jou wysigings, gebruik voor jy die blad stoor!",
'tooltip-diff'                    => 'Wys watter veranderinge u aan die teks gemaak het.',
'tooltip-compareselectedversions' => 'Vergelyk die twee gekose weergawes van hierdie blad.',
'tooltip-watch'                   => 'Voeg hierdie blad by jou dophoulys',
'tooltip-recreate'                => 'Herskep hierdie bladsy al is dit voorheen geskrap',
'tooltip-upload'                  => 'Begin oplaai',

# Stylesheets
'common.css' => '/** Gemeenskaplike CSS vir alle omslae */',

# Attribution
'anonymous' => 'Anonieme gebruiker(s) van {{SITENAME}}',
'siteuser'  => '{{SITENAME}} gebruiker $1',
'others'    => 'ander',
'siteusers' => '{{SITENAME}} gebruiker(s) $1',

# Math options
'mw_math_png'    => 'Gebruik altyd PNG.',
'mw_math_simple' => 'Gebruik HTML indien dit eenvoudig is, andersins PNG.',
'mw_math_html'   => 'Gebruik HTML wanneer moontlik, andersins PNG.',
'mw_math_source' => 'Los as TeX (vir teksblaaiers).',
'mw_math_modern' => 'Moderne blaaiers.',
'mw_math_mathml' => 'MathML',

# Patrol log
'patrol-log-page' => 'Kontroleringslogboek',

# Image deletion
'deletedrevision' => 'Ou weergawe $1 geskrap',

# Browsing diffs
'previousdiff' => '← Ouer wysiging',
'nextdiff'     => 'Nuwer wysiging →',

# Media information
'imagemaxsize'         => 'Beperk beelde op beeldbeskrywingsbladsye tot:',
'file-nohires'         => '<small>Geen hoëre resolusie beskikbaar nie.</small>',
'show-big-image'       => 'Volle resolusie',
'show-big-image-thumb' => '<small>Grootte van hierdie voorskou: $1 × $2 pixels</small>',

# Special:Newimages
'newimages'     => 'Gallery van nuwe beelde',
'imagelisttext' => "Hieronder is a lys van '''$1''' {{PLURAL:$1|lêer|lêers}}, $2 gesorteer.",
'noimages'      => 'Niks te sien nie.',
'ilsubmit'      => 'Soek',
'bydate'        => 'volgens datum',

# EXIF tags
'exif-imagewidth'          => 'Breedte',
'exif-imagelength'         => 'Hoogte',
'exif-imagedescription'    => 'Beeldtitel',
'exif-artist'              => 'Bewerker',
'exif-colorspace'          => 'Kleurruimte',
'exif-exposuretime'        => 'Beligtingstyd',
'exif-exposuretime-format' => '$1 sek ($2)',
'exif-fnumber'             => 'F-getal',
'exif-filesource'          => 'Lêerbron',
'exif-gpsversionid'        => 'GPS-merkerweergawe',
'exif-gpslatituderef'      => 'Noorder- of suiderbreedte',
'exif-gpslatitude'         => 'Breedtegraad',
'exif-gpslongituderef'     => 'Ooster- of westerlengte',
'exif-gpslongitude'        => 'Lengtegraad',
'exif-gpsaltitude'         => 'Hoogte',
'exif-gpstimestamp'        => 'GPS-tyd (atoomhorlosie)',
'exif-gpsspeed'            => 'Snelheid van GPS-ontvanger',

'exif-componentsconfiguration-0' => 'bestaan nie',

'exif-subjectdistance-value' => '$1 meter',

'exif-meteringmode-0' => 'Onbekend',

'exif-lightsource-0'  => 'Onbekend',
'exif-lightsource-1'  => 'Sonlig',
'exif-lightsource-2'  => 'Fluoresserend',
'exif-lightsource-4'  => 'Flits',
'exif-lightsource-10' => 'Bewolkte weer',
'exif-lightsource-11' => 'Skaduwee',

'exif-focalplaneresolutionunit-2' => 'duim',

'exif-scenecapturetype-1' => 'Landskap',
'exif-scenecapturetype-2' => 'Portret',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Noorderbreedte',
'exif-gpslatitude-s' => 'Suiderbreedte',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Oosterlengte',
'exif-gpslongitude-w' => 'Westerlengte',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Kilometer per huur',
'exif-gpsspeed-m' => 'Myl per huur',
'exif-gpsspeed-n' => 'Knope',

# External editor support
'edit-externally'      => "Wysig hierdie lêer met 'n eksterne program",
'edit-externally-help' => 'Sien die [http://meta.wikimedia.org/wiki/Help:External_editors instruksies] (in Engels) vir meer inligting.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'alle',
'imagelistall'     => 'alle',
'watchlistall2'    => 'alles',
'namespacesall'    => 'alle',
'monthsall'        => 'alle',

# E-mail address confirmation
'confirmemail'          => 'Bevestig e-posadres',
'confirmemail_text'     => "Hierdie wiki vereis dat u e-posadres bevestig word voordat epos-funksies gebruik word. Klik onderstaande knoppie om 'n bevestigingspos na u adres te stuur. Die pos sal 'n skakel met 'n kode insluit; maak hierdie skakel oop in u webblaaier om te bevestig dat die adres geldig is.",
'confirmemail_send'     => "Pos 'n bevestigingkode",
'confirmemail_sent'     => 'Bevestigingpos gestuur.',
'confirmemail_invalid'  => 'Ongeldige bevestigingkode. Die kode het moontlik verval.',
'confirmemail_success'  => 'U e-posadres is bevestig. U kan nou aanteken en die wiki gebruik.',
'confirmemail_loggedin' => 'U e-posadres is nou bevestig.',
'confirmemail_error'    => 'Iets het foutgegaan met die stoor van u bevestiging.',
'confirmemail_subject'  => '{{SITENAME}}: E-posadres-bevestiging',
'confirmemail_body'     => 'Iemand, waarskynlik van u IP-adres ($1), het \'n rekening "$2" geregistreer met hierdie e-posadres by {{SITENAME}}.

Om te bevestig dat hierdie adres werklik aan u behoort, en om die posfasiliteite by {{SITENAME}} te aktiveer, besoek hierdie skakel in u blaaier:

$3

Indien dit nié u was nie, ignoreer bloot die skakel (en hierdie pos). Hierde bevestigingkode verval om $4.',

# Delete conflict
'confirmrecreate' => "Gebruiker [[User:$1|$1]] ([[User talk:$1|bespreek]]) het hierdie blad uitgevee ná u begin redigeer het met rede: : ''$2''
Bevestig asseblief dat u regtig hierdie blad oor wil skep.",

# HTML dump
'redirectingto' => 'Stuur aan na [[$1]]...',

# action=purge
'confirm_purge'        => 'Verwyder die kas van hierdie blad?

$1',
'confirm_purge_button' => 'Regso',

# AJAX search
'articletitles' => "Artikels wat met ''$1'' begin",

# Auto-summaries
'autosumm-blank'   => 'Alle inhoud uit bladsy verwyder',
'autosumm-replace' => "Vervang bladsyinhoud met '$1'",
'autoredircomment' => 'Stuur aan na [[$1]]',
'autosumm-new'     => 'Nuwe blad: $1',

# Size units
'size-bytes'     => '$1 G',
'size-kilobytes' => '$1 KG',
'size-megabytes' => '$1 MG',
'size-gigabytes' => '$1 GG',

# Live preview
'livepreview-loading' => 'Laai tans…',
'livepreview-ready'   => 'Laai tans… Gereed!',

# Watchlist editing tools
'watchlisttools-view' => 'Besigtig ter saaklike veranderinge',
'watchlisttools-edit' => 'Bekyk en wysig dophoulys',
'watchlisttools-raw'  => 'Redigeer brondophoulys',

# Special:Version
'version'                  => 'Weergawe', # Not used as normal message but as header for the special page itself
'version-specialpages'     => 'Spesiale bladsye',
'version-software-version' => 'Weergawe',

# Special:Filepath
'filepath'        => 'Lêerpad',
'filepath-page'   => 'Lêer:',
'filepath-submit' => 'Pad',

);

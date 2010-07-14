<?php
/** Danish (Dansk)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Amjaabc
 * @author Anders Wegge Jakobsen <awegge@gmail.com>
 * @author Boivie
 * @author Byrial
 * @author Christian List
 * @author EPO
 * @author Emilkris33
 * @author Froztbyte
 * @author H92
 * @author Hylle
 * @author Jan Friberg
 * @author Jon Harald Søby
 * @author Kwi
 * @author Lars J. Helbo <lars.helbo@gmail.com>
 * @author Leolaursen
 * @author MGA73
 * @author Masz
 * @author MinuteElectron
 * @author Morten LJ
 * @author Najami
 * @author Nghtwlkr
 * @author Peter Andersen
 * @author Purodha
 * @author Qaqqalik
 * @author Remember the dot
 * @author Sarrus
 * @author Sir48
 * @author Slomox
 * @author Svip
 * @author Søren Løvborg
 * @author Urhixidur
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Speciel',
	NS_TALK             => 'Diskussion',
	NS_USER             => 'Bruger',
	NS_USER_TALK        => 'Brugerdiskussion',
	NS_PROJECT_TALK     => '$1-diskussion',
	NS_FILE             => 'Fil',
	NS_FILE_TALK        => 'Fildiskussion',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki-diskussion',
	NS_TEMPLATE         => 'Skabelon',
	NS_TEMPLATE_TALK    => 'Skabelondiskussion',
	NS_HELP             => 'Hjælp',
	NS_HELP_TALK        => 'Hjælp-diskussion',
	NS_CATEGORY         => 'Kategori',
	NS_CATEGORY_TALK    => 'Kategoridiskussion',
);

$namespaceAliases = array(
	'Billede' => NS_FILE,
	'Billeddiskussion' => NS_FILE_TALK,
);

$bookstoreList = array(
	"Bibliotek.dk" => "http://bibliotek.dk/vis.php?base=dfa&origin=kommando&field1=ccl&term1=is=$1&element=L&start=1&step=10",
	"Bogguide.dk" => "http://www.bogguide.dk/find_boeger_bog.asp?ISBN=$1",
	'inherit' => true,
);

$separatorTransformTable = array(',' => '.', '.' => ',' );
$linkTrail = '/^([a-zæøå]+)(.*)$/sDu';

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Dobbelte omdirigeringer' ),
	'BrokenRedirects'           => array( 'Defekte omdirigeringer' ),
	'Disambiguations'           => array( 'Flertydige sider' ),
	'Userlogin'                 => array( 'Log på', 'Brugerlogind' ),
	'Userlogout'                => array( 'Brugerlogud' ),
	'CreateAccount'             => array( 'Opret konto' ),
	'Preferences'               => array( 'Indstillinger' ),
	'Watchlist'                 => array( 'Overvågningsliste' ),
	'Recentchanges'             => array( 'Seneste ændringer' ),
	'Listfiles'                 => array( 'Filer', 'Filliste' ),
	'Newimages'                 => array( 'Nye filer' ),
	'Listusers'                 => array( 'Brugerliste', 'Bruger' ),
	'Listgrouprights'           => array( 'Grupperettighedsliste' ),
	'Statistics'                => array( 'Statistik' ),
	'Randompage'                => array( 'Tilfældig side' ),
	'Lonelypages'               => array( 'Forældreløse sider' ),
	'Uncategorizedpages'        => array( 'Ukategoriserede sider' ),
	'Uncategorizedcategories'   => array( 'Ukategoriserede kategorier' ),
	'Uncategorizedimages'       => array( 'Ukategoriserede filer' ),
	'Uncategorizedtemplates'    => array( 'Ukategoriserede skabeloner' ),
	'Unusedcategories'          => array( 'Ubrugte kategorier' ),
	'Unusedimages'              => array( 'Ubrugte filer' ),
	'Wantedpages'               => array( 'Ønskede sider' ),
	'Wantedcategories'          => array( 'Ønskede kategorier' ),
	'Mostlinked'                => array( 'Sider med flest henvisninger' ),
	'Mostlinkedcategories'      => array( 'Kategorier med flest sider' ),
	'Mostlinkedtemplates'       => array( 'Hyppigst brugte skabeloner' ),
	'Mostimages'                => array( 'Mest brugte filer' ),
	'Mostcategories'            => array( 'Sider med flest kategorier' ),
	'Mostrevisions'             => array( 'Sider med flest redigeringer' ),
	'Fewestrevisions'           => array( 'Sider med færrest redigeringer' ),
	'Shortpages'                => array( 'Korteste sider' ),
	'Longpages'                 => array( 'Længste sider' ),
	'Newpages'                  => array( 'Nye sider' ),
	'Ancientpages'              => array( 'Ældste sider' ),
	'Deadendpages'              => array( 'Blindgydesider' ),
	'Protectedpages'            => array( 'Beskyttede sider' ),
	'Protectedtitles'           => array( 'Beskyttede titler' ),
	'Allpages'                  => array( 'Alle sider' ),
	'Prefixindex'               => array( 'Præfiksindeks' ),
	'Ipblocklist'               => array( 'Blokerede adresser' ),
	'Specialpages'              => array( 'Specialsider' ),
	'Contributions'             => array( 'Bidrag' ),
	'Emailuser'                 => array( 'E-mail' ),
	'Confirmemail'              => array( 'Bekræft e-mail' ),
	'Whatlinkshere'             => array( 'Hvad linker hertil' ),
	'Recentchangeslinked'       => array( 'Relaterede ændringer' ),
	'Movepage'                  => array( 'Flyt side' ),
	'Blockme'                   => array( 'Proxyspærring' ),
	'Booksources'               => array( 'ISBN-søgning' ),
	'Categories'                => array( 'Kategorier' ),
	'Export'                    => array( 'Eksporter' ),
	'Allmessages'               => array( 'MediaWiki-systemmeddelelser' ),
	'Log'                       => array( 'Loglister' ),
	'Blockip'                   => array( 'Bloker adresse' ),
	'Undelete'                  => array( 'Gendannelse' ),
	'Import'                    => array( 'Importere' ),
	'Lockdb'                    => array( 'Databasespærring' ),
	'Unlockdb'                  => array( 'Databaseåbning' ),
	'Userrights'                => array( 'Brugerrettigheder' ),
	'MIMEsearch'                => array( 'MIME-type-søgning' ),
	'FileDuplicateSearch'       => array( 'Filduplikatsøgning' ),
	'Unwatchedpages'            => array( 'Uovervågede sider' ),
	'Listredirects'             => array( 'Henvisninger' ),
	'Revisiondelete'            => array( 'Versionssletning' ),
	'Unusedtemplates'           => array( 'Ubrugte skabeloner' ),
	'Randomredirect'            => array( 'Tilfældig henvisning' ),
	'Mypage'                    => array( 'Min brugerside' ),
	'Mytalk'                    => array( 'Min diskussionsside' ),
	'Mycontributions'           => array( 'Mine bidrag' ),
	'Listadmins'                => array( 'Administratorer' ),
	'Listbots'                  => array( 'Robotter' ),
	'Popularpages'              => array( 'Populære sider' ),
	'Search'                    => array( 'Søgning' ),
	'Resetpass'                 => array( 'Nulstil kodeord' ),
	'Withoutinterwiki'          => array( 'Manglende interwikilinks' ),
	'MergeHistory'              => array( 'Sammenfletning af historikker' ),
	'Filepath'                  => array( 'Filsti' ),
	'Invalidateemail'           => array( 'Ugyldiggør e-mail' ),
	'DeletedContributions'      => array( 'Slettede bidrag' ),
);

$dateFormats = array(
	'mdy time' => 'H:i',
	'mdy date' => 'M j. Y',
	'mdy both' => 'M j. Y, H:i',

	'dmy time' => 'H:i',
	'dmy date' => 'j. F Y',
	'dmy both' => 'j. M Y, H:i',

	'ymd time' => 'H:i',
	'ymd date' => 'Y M j',
	'ymd both' => 'Y M j, H:i'
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Understreg henvisninger:',
'tog-highlightbroken'         => 'Brug røde henvisninger til tomme sider',
'tog-justify'                 => 'Vis afsnit med lige marginer',
'tog-hideminor'               => 'Skjul mindre ændringer i listen over seneste ændringer',
'tog-hidepatrolled'           => 'Skjul patruljerede redigeringer i seneste ændringer',
'tog-newpageshidepatrolled'   => 'Skjul patruljerede sider på listen over nye sider',
'tog-extendwatchlist'         => 'Udvid overvågningslisten til at vise alle ændringer og ikke kun den nyeste',
'tog-usenewrc'                => 'Brug forbedret liste over seneste ændringer (kræver JavaScript)',
'tog-numberheadings'          => 'Automatisk nummerering af overskrifter',
'tog-showtoolbar'             => 'Vis værktøjslinje til redigering (JavaScript)',
'tog-editondblclick'          => 'Redigér sider med dobbeltklik (JavaScript)',
'tog-editsection'             => 'Redigér afsnit ved hjælp af [redigér]-henvisninger',
'tog-editsectiononrightclick' => 'Redigér afsnit ved at højreklikke på deres titler (JavaScript)',
'tog-showtoc'                 => 'Vis indholdsfortegnelse (på sider med mere end tre afsnit)',
'tog-rememberpassword'        => 'Husk min adgangskode på denne computer (højst $1 {{PLURAL:$1|dag|dage}})',
'tog-watchcreations'          => 'Tilføj sider jeg opretter til min overvågningsliste',
'tog-watchdefault'            => 'Tilføj sider jeg redigerer til min overvågningsliste',
'tog-watchmoves'              => 'Tilføj sider jeg flytter til min overvågningsliste',
'tog-watchdeletion'           => 'Tilføj sider jeg sletter til min overvågningsliste',
'tog-previewontop'            => 'Vis forhåndsvisning over redigeringsboksen',
'tog-previewonfirst'          => 'Vis forhåndsvisning når du starter med at redigere',
'tog-nocache'                 => 'Slå caching af sider fra',
'tog-enotifwatchlistpages'    => 'Send mig en e-mail ved sideændringer',
'tog-enotifusertalkpages'     => 'Send mig en e-mail når min brugerdiskussionsside ændres',
'tog-enotifminoredits'        => 'Send mig også en e-mail ved mindre ændringer af overvågede sider',
'tog-enotifrevealaddr'        => 'Vis min e-mail-adresse i mails med besked om ændringer',
'tog-shownumberswatching'     => 'Vis antal brugere, der overvåger',
'tog-oldsig'                  => 'Forhåndsvisning af nuværende signatur',
'tog-fancysig'                => 'Behandl signatur som wikitekst uden automatisk henvisning',
'tog-externaleditor'          => 'Brug ekstern editor automatisk',
'tog-externaldiff'            => 'Brug ekstern forskelsvisning automatisk',
'tog-showjumplinks'           => 'Vis tilgængeligheds-henvisninger',
'tog-uselivepreview'          => 'Brug automatisk forhåndsvisning (JavaScript) (eksperimentel)',
'tog-forceeditsummary'        => 'Advar mig hvis jeg ikke udfylder beskrivelsesfeltet',
'tog-watchlisthideown'        => 'Skjul egne ændringer i overvågningslisten',
'tog-watchlisthidebots'       => 'Skjul ændringer fra bots i overvågningslisten',
'tog-watchlisthideminor'      => 'Skjul mindre ændringer i overvågningslisten',
'tog-watchlisthideliu'        => 'Skjul indloggede brugeres redigeringer i overvågningslisten',
'tog-watchlisthideanons'      => 'Skjul anonyme brugeres redigeringer i overvågningslisten',
'tog-watchlisthidepatrolled'  => 'Skjul patrujerede ændringer fra overvågningslisten',
'tog-nolangconversion'        => 'Deaktiver konverteringer af sprogvarianter',
'tog-ccmeonemails'            => 'Send mig kopier af e-mails som jeg sender til andre brugere',
'tog-diffonly'                => 'Vis ved versionssammenligninger kun forskelle, ikke hele siden',
'tog-showhiddencats'          => 'Vis skjulte kategorier',
'tog-norollbackdiff'          => 'Vis ikke forskel efter tilbagerulning',

'underline-always'  => 'Altid',
'underline-never'   => 'Aldrig',
'underline-default' => 'Brug browserens indstilling',

# Font style option in Special:Preferences
'editfont-style'     => 'Skriftstil ved redigering:',
'editfont-default'   => 'Brug browserens indstilling',
'editfont-monospace' => 'Fastbreddeskrift',
'editfont-sansserif' => 'Skrift uden fødder',
'editfont-serif'     => 'Skrift med fødder',

# Dates
'sunday'        => 'søndag',
'monday'        => 'mandag',
'tuesday'       => 'tirsdag',
'wednesday'     => 'onsdag',
'thursday'      => 'torsdag',
'friday'        => 'fredag',
'saturday'      => 'lørdag',
'sun'           => 'søn',
'mon'           => 'man',
'tue'           => 'tir',
'wed'           => 'ons',
'thu'           => 'tor',
'fri'           => 'fre',
'sat'           => 'lør',
'january'       => 'januar',
'february'      => 'februar',
'march'         => 'marts',
'april'         => 'april',
'may_long'      => 'maj',
'june'          => 'juni',
'july'          => 'juli',
'august'        => 'august',
'september'     => 'september',
'october'       => 'oktober',
'november'      => 'november',
'december'      => 'december',
'january-gen'   => 'januars',
'february-gen'  => 'februars',
'march-gen'     => "marts'",
'april-gen'     => 'aprils',
'may-gen'       => 'majs',
'june-gen'      => 'junis',
'july-gen'      => 'julis',
'august-gen'    => 'augusts',
'september-gen' => 'septembers',
'october-gen'   => 'oktobers',
'november-gen'  => 'novembers',
'december-gen'  => 'decembers',
'jan'           => 'jan',
'feb'           => 'feb',
'mar'           => 'mar',
'apr'           => 'apr',
'may'           => 'maj',
'jun'           => 'jun',
'jul'           => 'jul',
'aug'           => 'aug',
'sep'           => 'sep',
'oct'           => 'okt',
'nov'           => 'nov',
'dec'           => 'dec',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategori|Kategorier}}',
'category_header'                => 'Sider i kategorien "$1"',
'subcategories'                  => 'Underkategorier',
'category-media-header'          => 'Medier i kategorien „$1“',
'category-empty'                 => "''Denne kategori indeholder for øjeblikket hverken sider eller medie-filer.''",
'hidden-categories'              => '{{PLURAL:$1|Skjult kategori|Skjulte kategorier}}',
'hidden-category-category'       => 'Skjulte kategorier',
'category-subcat-count'          => '{{PLURAL:$2|Denne kategori har en underkategori.|Denne kategori indeholder nedenstående {{PLURAL:$1|underkategori|$1 underkategorier}}, af i alt $2.}}',
'category-subcat-count-limited'  => 'Denne kategori indeholder {{PLURAL:$1|underkategori|$1 underkategorier}}.',
'category-article-count'         => 'Denne kategori indeholder {{PLURAL:$2|kun den nedenstående side|{{PLURAL:$1|den nedenstående side|de nedenstående $1 sider}} af i alt $2.}}',
'category-article-count-limited' => 'Kategorien indeholder {{PLURAL:$1|den nedenstående side|de nedenstående $1 sider}}.',
'category-file-count'            => 'Denne kategori indeholder {{PLURAL:$2|kun den nedenstående fil.|{{PLURAL:$1|den nedenstående fil|de nedenstående $1 filer}} af i alt $2.}}',
'category-file-count-limited'    => 'Kategorien indeholder {{PLURAL:$1|den nedenstående fil|de nedenstående $1 filer}}.',
'listingcontinuesabbrev'         => ' forts.',
'index-category'                 => 'Indekserede sider',
'noindex-category'               => 'Ikke-indekserede sider',

'mainpagetext'      => "'''MediaWiki er nu installeret.'''",
'mainpagedocfooter' => 'Se vores engelsksprogede [http://meta.wikimedia.org/wiki/MediaWiki_localisation dokumentation om tilpasning af brugergrænsefladen] og [http://meta.wikimedia.org/wiki/MediaWiki_User%27s_Guide brugervejledningen] for oplysninger om opsætning og anvendelse.',

'about'         => 'Om',
'article'       => 'Artikel',
'newwindow'     => '(åbner i et nyt vindue)',
'cancel'        => 'Afbryd',
'moredotdotdot' => 'Mere...',
'mypage'        => 'Min side',
'mytalk'        => 'Min diskussion',
'anontalk'      => 'Diskussionsside for denne IP-adresse',
'navigation'    => 'Navigation',
'and'           => '&#32;og',

# Cologne Blue skin
'qbfind'         => 'Søg',
'qbbrowse'       => 'Gennemse',
'qbedit'         => 'Redigér',
'qbpageoptions'  => 'Indstillinger for side',
'qbpageinfo'     => 'Information om side',
'qbmyoptions'    => 'Mine indstillinger',
'qbspecialpages' => 'Specielle sider',
'faq'            => 'OSS',
'faqpage'        => 'Project:OSS',

# Vector skin
'vector-action-addsection'       => 'Nyt emne',
'vector-action-delete'           => 'Slet',
'vector-action-move'             => 'Flyt',
'vector-action-protect'          => 'Beskyt',
'vector-action-undelete'         => 'Gendan',
'vector-action-unprotect'        => 'Fjern beskyttelse',
'vector-namespace-category'      => 'Kategori',
'vector-namespace-help'          => 'Hjælpeside',
'vector-namespace-image'         => 'Fil',
'vector-namespace-main'          => 'Side',
'vector-namespace-media'         => 'Medieside',
'vector-namespace-mediawiki'     => 'Besked',
'vector-namespace-project'       => 'Projektside',
'vector-namespace-special'       => 'Specialside',
'vector-namespace-talk'          => 'Diskussion',
'vector-namespace-template'      => 'Skabelon',
'vector-namespace-user'          => 'Brugerside',
'vector-simplesearch-preference' => 'Aktivér forbedrede søgeforslag (kun Vector-udseendet)',
'vector-view-create'             => 'Opret',
'vector-view-edit'               => 'Redigér',
'vector-view-history'            => 'Se historik',
'vector-view-view'               => 'Vis',
'vector-view-viewsource'         => 'Se kilden',
'actions'                        => 'Handlinger',
'namespaces'                     => 'Navnerum',
'variants'                       => 'Varianter',

'errorpagetitle'    => 'Fejl',
'returnto'          => 'Tilbage til $1.',
'tagline'           => 'Fra {{SITENAME}}',
'help'              => 'Hjælp',
'search'            => 'Søg',
'searchbutton'      => 'Søg',
'go'                => 'Gå til',
'searcharticle'     => 'Gå til',
'history'           => 'Historik',
'history_short'     => 'Historik',
'updatedmarker'     => '(ændret)',
'info_short'        => 'Information',
'printableversion'  => 'Udskriftsvenlig udgave',
'permalink'         => 'Permanent henvisning',
'print'             => 'Udskriv',
'edit'              => 'Redigér',
'create'            => 'opret',
'editthispage'      => 'Redigér side',
'create-this-page'  => 'opret ny side',
'delete'            => 'Slet',
'deletethispage'    => 'Slet side',
'undelete_short'    => 'Fortryd sletning af {{PLURAL:$1|$1 version|$1 versioner}}',
'protect'           => 'Beskyt',
'protect_change'    => 'ændr',
'protectthispage'   => 'Beskyt side',
'unprotect'         => 'Fjern beskyttelse',
'unprotectthispage' => 'Frigiv side',
'newpage'           => 'Ny side',
'talkpage'          => 'Diskussion',
'talkpagelinktext'  => 'diskussion',
'specialpage'       => 'Speciel side',
'personaltools'     => 'Personlige værktøjer',
'postcomment'       => 'Nyt afsnit',
'articlepage'       => 'Se artiklen',
'talk'              => 'Diskussion',
'views'             => 'Visninger',
'toolbox'           => 'Værktøjer',
'userpage'          => 'Se brugersiden',
'projectpage'       => 'Se projektsiden',
'imagepage'         => 'Se filsiden',
'mediawikipage'     => 'Vise indholdsside',
'templatepage'      => 'Vise skabelonside',
'viewhelppage'      => 'Vise hjælpeside',
'categorypage'      => 'Vise kategoriside',
'viewtalkpage'      => 'Se diskussion',
'otherlanguages'    => 'Andre sprog',
'redirectedfrom'    => '(Omdirigeret fra $1)',
'redirectpagesub'   => 'Omdirigering',
'lastmodifiedat'    => 'Denne side blev senest ændret $1 $2.',
'viewcount'         => 'Siden er vist i alt $1 {{PLURAL:$1|gang|gange}}.',
'protectedpage'     => 'Beskyttet side',
'jumpto'            => 'Skift til:',
'jumptonavigation'  => 'Navigation',
'jumptosearch'      => 'Søgning',
'view-pool-error'   => 'Beklager, men serverne er i øjeblikket overbelastede.
For mange brugere prøver at vise denne side.
Vent et øjeblik, før du prøver at vise denne side ige. 

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Om {{SITENAME}}',
'aboutpage'            => 'Project:Om',
'copyright'            => 'Indholdet er udgivet under $1.',
'copyrightpage'        => '{{ns:project}}:Ophavsret',
'currentevents'        => 'Aktuelle begivenheder',
'currentevents-url'    => 'Project:Aktuelle begivenheder',
'disclaimers'          => 'Forbehold',
'disclaimerpage'       => 'Project:Generelle forbehold',
'edithelp'             => 'Hjælp til redigering',
'edithelppage'         => 'Help:Hvordan redigerer jeg en side',
'helppage'             => 'Help:Hjælp',
'mainpage'             => 'Forside',
'mainpage-description' => 'Forside',
'policy-url'           => 'Project:Politik',
'portal'               => 'Forside for skribenter',
'portal-url'           => 'Project:Forside',
'privacy'              => 'Behandling af personlige oplysninger',
'privacypage'          => 'Project:Behandling_af_personlige_oplysninger',

'badaccess'        => 'Manglende rettigheder',
'badaccess-group0' => 'Du har ikke de nødvendige rettigheder til denne handling.',
'badaccess-groups' => 'Denne handling kan kun udføres af brugere som tilhører {{PLURAL:$2|gruppen|en af grupperne:}} $1.',

'versionrequired'     => 'Kræver version $1 af MediaWiki',
'versionrequiredtext' => 'Version $1 af MediaWiki er påkrævet, for at bruge denne side. Se [[Special:Version|Versionssiden]]',

'ok'                      => 'OK',
'retrievedfrom'           => 'Hentet fra "$1"',
'youhavenewmessages'      => 'Du har $1 ($2).',
'newmessageslink'         => 'nye beskeder',
'newmessagesdifflink'     => 'ændringer siden sidste visning',
'youhavenewmessagesmulti' => 'Du har nye beskeder på $1',
'editsection'             => 'redigér',
'editold'                 => 'redigér',
'viewsourceold'           => 'vis kildekode',
'editlink'                => 'redigér',
'viewsourcelink'          => 'vis kildetekst',
'editsectionhint'         => 'Rediger afsnit: $1',
'toc'                     => 'Indholdsfortegnelse',
'showtoc'                 => 'vis',
'hidetoc'                 => 'skjul',
'thisisdeleted'           => 'Se eller gendan $1?',
'viewdeleted'             => 'Vis $1?',
'restorelink'             => '{{PLURAL:$1|en slettet ændring|$1 slettede ændringer}}',
'feedlinks'               => 'Feed:',
'feed-invalid'            => 'Ugyldig abonnementstype.',
'feed-unavailable'        => 'Der er ingen syndikeringsfeeds tilgængelige',
'site-rss-feed'           => '$1 RSS-feed',
'site-atom-feed'          => '$1 Atom-feed',
'page-rss-feed'           => '"$1" RSS-feed',
'page-atom-feed'          => '"$1" Atom-feed',
'red-link-title'          => '$1 (siden er ikke skrevet endnu)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Side',
'nstab-user'      => 'brugerside',
'nstab-media'     => 'medie',
'nstab-special'   => 'speciel',
'nstab-project'   => 'om',
'nstab-image'     => 'Fil',
'nstab-mediawiki' => 'besked',
'nstab-template'  => 'skabelon',
'nstab-help'      => 'hjælp',
'nstab-category'  => 'kategori',

# Main script and global functions
'nosuchaction'      => 'Funktionen findes ikke',
'nosuchactiontext'  => "Handlingen som er angivet i URL'en er ugyldig.
Du kan have skrevet URL'en forkert, eller fulgt en ukorrekt henvisning.
Det kan også skyldes en fejl i programmellet som bruges af {{SITENAME}}.",
'nosuchspecialpage' => 'En sådan specialside findes ikke',
'nospecialpagetext' => '<strong>Du har bedt om en specialside, der ikke kan genkendes af MediaWiki-softwaren.</strong>
En liste over gyldige specialsider findes på [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Fejl',
'databaseerror'        => 'Databasefejl',
'dberrortext'          => 'Der er opstået en syntaksfejl i en databaseforespørgsel.
Det kan tyde på en fejl i softwaren. 
Den sidst forsøgte databaseforespørgsel var:
<blockquote><tt>$1</tt></blockquote>
fra funktionen "<tt>$2</tt>". 
Databasen returnerede fejlen "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Der er opstået en syntaksfejl i en databaseforespørgsel. 
Den seneste forsøgte databaseforespørgsel var:
"$1"
fra funktionen "$2". 
Databasen returnerede fejlen "$3: $4"',
'laggedslavemode'      => 'Bemærk: Den viste side indeholder muligvis ikke de nyeste ændringer.',
'readonly'             => 'Databasen er skrivebeskyttet',
'enterlockreason'      => 'Skriv en begrundelse for skrivebeskyttelsen, med samt en vurdering af, hvornår skrivebeskyttelsen ophæves igen',
'readonlytext'         => 'Databasen er midlertidigt skrivebeskyttet. Forsøg venligst senere.

Årsag til spærringen: $1',
'missing-article'      => 'Databasen burde indeholde siden "$1" $2, men det gør den ikke.

Den sandsynlige årsag er at du har fulgt et forældet link til en forskel eller en gammel version af en side der er blevet slettet.

Hvis det ikke er tilfældet, har du muligvis fundet en software-fejl.
Gør venligst en [[Special:ListUsers/sysop|administrator]] opmærksom på det, og husk at fortælle hvilken URL du har fulgt.',
'missingarticle-rev'   => '(versionsnummer: $1)',
'missingarticle-diff'  => '(Forskel: $1, $2)',
'readonly_lag'         => 'Databasen er automatisk blevet låst mens slave database serverne synkronisere med master databasen',
'internalerror'        => 'Intern fejl',
'internalerror_info'   => 'Internal fejl: $1',
'fileappenderrorread'  => 'Kunne ikke læse "$1" under tilføjelsen.',
'fileappenderror'      => 'Kunne ikke tilføje "$1" til "$2".',
'filecopyerror'        => 'Kunne ikke kopiere filen "$1" til "$2".',
'filerenameerror'      => 'Kunne ikke omdøbe filen "$1" til "$2".',
'filedeleteerror'      => 'Kunne ikke slette filen "$1".',
'directorycreateerror' => 'Kunne ikke oprette kataloget "$1".',
'filenotfound'         => 'Kunne ikke finde filen "$1".',
'fileexistserror'      => 'Kunne ikke oprette "$1": filen findes allerede',
'unexpected'           => 'Uventet værdi: "$1"="$2".',
'formerror'            => 'Fejl: Kunne ikke afsende formular',
'badarticleerror'      => 'Denne funktion kan ikke udføres på denne side.',
'cannotdelete'         => 'Kunne ikke slette siden eller filen "$1".
Den kan være blevet slettet af en anden.',
'badtitle'             => 'Forkert titel',
'badtitletext'         => 'Den ønskede sides titel var ikke tilladt, tom eller siden er forkert henvist fra en {{SITENAME}} på et andet sprog.',
'perfcached'           => 'Følgende data er gemt i cachen, det er muligvis ikke helt opdateret:',
'perfcachedts'         => 'Disse data stammer fra cachen, sidste update: $1',
'querypage-no-updates' => "'''Aktualiseringsfunktionen for denne side er pt. deaktiveret. Dataene bliver indtil videre ikke fornyet.'''",
'wrong_wfQuery_params' => 'Ugyldig parameter til wfQuery()<br />
Funktion: $1<br />
Forespørgsel: $2',
'viewsource'           => 'Vis kildetekst',
'viewsourcefor'        => 'for $1',
'actionthrottled'      => 'Begrænsning af handling',
'actionthrottledtext'  => 'For at modvirke spam, er det ikke muligt at udføre denne handling mange gange på kort tid. Du har overskredet grænsen, hvorfor handlingen er blevet afbrudt. Vær venlig at forsøge igen om et par minutter.',
'protectedpagetext'    => 'Denne side er skrivebeskyttet.',
'viewsourcetext'       => 'Du kan se og kopiere kildekoden til siden:',
'protectedinterface'   => 'Denne side indeholder tekst til softwarens sprog-interface og er skrivebeskyttet for at forhindre misbrug.',
'editinginterface'     => "'''Advarsel:''' Du redigerer en side som bruges i programmets grænseflade. Ændringer på denne side vil påvirke udseendet af grænsefladen for andre brugere.
For oversættelser bedes du venligst overveje at bruge [http://translatewiki.net/wiki/Main_Page?setlang=da translatewiki.net], projektet for oversættelse af MediaWiki.",
'sqlhidden'            => '(SQL forespørgsel gemt)',
'cascadeprotected'     => 'Denne side er skrivebeskyttet, da den er indeholdt i nedenstående {{PLURAL:$1|side|sider}}, som er skrivebeskyttet med tilvalg af "nedarvende sidebeskyttelse":
$2',
'namespaceprotected'   => 'Du har ikke rettigheder til at redigere sider i $1-navnerummet.',
'customcssjsprotected' => 'Du har ikke rettigheder til at redigere denne side, da den indeholder en anden brugers personlige indstillinger.',
'ns-specialprotected'  => 'Sider i navnerummet {{ns:special}} kan ikke redigeres.',
'titleprotected'       => "Dette sidenavn er beskyttet mod oprettelse af [[User:$1|$1]]. Begrundelsen for beskyttelsen er ''$2''.",

# Virus scanner
'virus-badscanner'     => "Konfigurationsfejl: ukendt virus-scanner: ''$1''",
'virus-scanfailed'     => 'virus-scan fejlede med fejlkode $1',
'virus-unknownscanner' => 'ukendt virus-scanner:',

# Login and logout pages
'logouttext'                 => "'''Du er nu logget af.'''

Du kan fortsætte med at bruge {{SITENAME}} anonymt, eller du kan [[Special:UserLogin|logge på igen]] som den samme eller en anden bruger.
Bemærk, at nogle sider stadigvæk kan vises som om du var logget på, indtil du tømmer din browsers cache.",
'welcomecreation'            => '== Velkommen, $1! ==

Din konto er blevet oprettet. Glem ikke at personliggøre dine [[Special:Preferences|{{SITENAME}}-indstillinger]].',
'yourname'                   => 'Dit brugernavn:',
'yourpassword'               => 'Din adgangskode:',
'yourpasswordagain'          => 'Gentag adgangskode',
'remembermypassword'         => 'Husk mit brugernavn på denne computer (højst $1 {{PLURAL:$1|dag|dage}})',
'yourdomainname'             => 'Dit domænenavn',
'externaldberror'            => 'Der er opstået en fejl i en ekstern adgangsdatabase, eller du har ikke rettigheder til at opdatere denne.',
'login'                      => 'Log på',
'nav-login-createaccount'    => 'Opret en konto eller log på',
'loginprompt'                => 'Du skal have cookies slået til for at kunne logge på {{SITENAME}}.',
'userlogin'                  => 'Opret en konto eller log på',
'userloginnocreate'          => 'Log ind',
'logout'                     => 'Log af',
'userlogout'                 => 'Log af',
'notloggedin'                => 'Ikke logget på',
'nologin'                    => "Du har ingen brugerkonto? '''$1'''.",
'nologinlink'                => 'Opret ny brugerkonto',
'createaccount'              => 'Opret en ny konto',
'gotaccount'                 => "Du har allerede en brugerkonto? '''$1'''.",
'gotaccountlink'             => 'Log på',
'createaccountmail'          => 'via e-mail',
'badretype'                  => 'De indtastede adgangskoder er ikke ens.',
'userexists'                 => 'Det brugernavn du har valgt er allerede i brug. Vælg venligst et andet brugernavn.',
'loginerror'                 => 'Logon mislykket',
'createaccounterror'         => 'Kunne ikke oprette konto: $1',
'nocookiesnew'               => 'Din brugerkonto er nu oprettet, men du er ikke logget på. {{SITENAME}} bruger cookies til at logge brugere på. Du har slået cookies fra. Vær venlig at slå cookies til, og derefter kan du logge på med dit nye brugernavn og kodeord.',
'nocookieslogin'             => '{{SITENAME}} bruger cookies til at logge brugere på. Du har slået cookies fra. Slå dem venligst til og prøv igen.',
'noname'                     => 'Du har ikke angivet et gyldigt brugernavn.',
'loginsuccesstitle'          => 'Du er nu logget på',
'loginsuccess'               => 'Du er nu logget på {{SITENAME}} som "$1".',
'nosuchuser'                 => 'Der er ingen bruger med navnet "$1".
Der skelnes mellem store og bogstaver i brugernavne. 
Kontrollér stavemåden, eller [[Special:UserLogin/signup|opret en ny konto]].',
'nosuchusershort'            => 'Der er ingen bruger ved navn "$1". Tjek din stavning.',
'nouserspecified'            => 'Angiv venligst et brugernavn.',
'login-userblocked'          => 'Denne bruger er blokeret. Login er ikke tilladt',
'wrongpassword'              => 'Den indtastede adgangskode var forkert. Prøv igen.',
'wrongpasswordempty'         => 'Du glemte at indtaste password. Prøv igen.',
'passwordtooshort'           => 'Adgangskoden skal mindst være på $1 {{PLURAL:$1|tegn|tegn}}.',
'password-name-match'        => 'Kodeordet må ikke være det samme som brugernavnet.',
'mailmypassword'             => 'Send nyt password',
'passwordremindertitle'      => 'Nyt password til {{SITENAME}}',
'passwordremindertext'       => 'Nogen (sandsynligvis dig, fra IP-adressen $1)
har bedt om at vi sender dig en ny adgangskode til at logge på {{SITENAME}} ($4).
En midlertidig adgangskode for bruger "$2" er blevet lavet, den er "$3". 
Hvis dette var din mening, skal du logge ind og vælge en ny adgangskode nu.
Din midlertidige adgangskode vil udløbe om {{PLURAL:$5|en dag|$5 dage}}.

Hvis en anden har bestilt den nye adgangskode, eller hvis du er kommet i tanke om din gamle adgangskode og ikke længere vil ændre den, 
kan du bare ignorere denne e-mail og fortsætte med at bruge din gamle adgangskode.',
'noemail'                    => 'Der er ikke oplyst en e-mail-adresse for bruger "$1".',
'noemailcreate'              => 'Du skal angive en gyldig e-mailadresse',
'passwordsent'               => 'En ny adgangskode er sendt til e-mail-adressen,
som er registreret for "$1".
Du bør logge på og ændre din adgangskode straks efter du har modtaget e-mailen.',
'blocked-mailpassword'       => 'Din IP-adresse er spærret for ændring af sider. For at forhindre misbrug, er det heller ikke muligt, at bestille et nyt password.',
'eauthentsent'               => 'En bekræftelsesmail er sendt til den angivne e-mail-adresse.

Før en e-mail kan modtages af andre brugere af {{SITENAME}}-mailfunktionen, skal adressen og dens tilhørsforhold til denne bruger bekræftes. Følg venligst anvisningerne i denne mail.',
'throttled-mailpassword'     => 'Indenfor {{PLURAL:$1|den sidste time|de sidste $1 timer}} er der allerede sendt et nyt password. For at forhindre misbrug af funktionen, kan der kun bestilles et nyt password en gang for hver {{PLURAL:$1|time|$1 timer}}.',
'mailerror'                  => 'Fejl ved afsendelse af e-mail: $1',
'acct_creation_throttle_hit' => 'Besøgende med samme IP-adresse som dig har oprettet {{PLURAL:$1|en konto|$1 kontoer}} det sidste døgn, og det er ikke tilladt at oprette flere.
Derfor kan besøgende ikke oprette flere kontoer fra denne IP-adresse i øjeblikket.',
'emailauthenticated'         => 'Din e-mail-adresse blev bekræftet $2 $3.',
'emailnotauthenticated'      => 'Din e-mail-adresse er endnu ikke bekræftet og de avancerede e-mail-funktioner er slået fra indtil bekræftelse har fundet sted (d.u.a.). Log ind med den midlertidige adgangskode, der er blevet sendt til dig, for at bekræfte, eller bestil et nyt på loginsiden.',
'noemailprefs'               => 'Angiv en e-mail-adresse, så følgende funktioner er til rådighed.',
'emailconfirmlink'           => 'Bekræft e-mail-adressen (autentificering).',
'invalidemailaddress'        => 'E-mail-adressen kan ikke accepteres da den tilsyneladende har et ugyldigt format. Skriv venligst en e-mail-adresse med et korrekt format eller tøm feltet.',
'accountcreated'             => 'Brugerkonto oprettet',
'accountcreatedtext'         => 'Brugerkontoen $1 er oprettet.',
'createaccount-title'        => 'Opret brugerkonto på {{SITENAME}}',
'createaccount-text'         => 'Nogen har oprettet en konto for din e-post-adresse på {{SITENAME}} ($4) med navnet "$2". Adgangskoden er "$3".
Du opfordres til at logge ind og ændre adgangskoden med det samme.

Du kan ignorere denne besked hvis kontoen blev oprettet ved en fejl.',
'usernamehasherror'          => 'Brugernavn må ikke indeholde #',
'login-throttled'            => 'Du har forsøgt at logge på for mange gange.
Vent venligst før du prøver igen.',
'loginlanguagelabel'         => 'Sprog: $1',
'suspicious-userlogout'      => 'Din anmodning om at logge ud blev nægtet, fordi det ser ud som den blev sendt af en ødelagt browser eller caching proxy.',

# Password reset dialog
'resetpass'                 => 'Skift adgangskode',
'resetpass_announce'        => 'Log på med den via e-mail tilsendte password. For at afslutte tilmeldingen, skal du nu vælge et nyt password.',
'resetpass_text'            => '<!-- Tilføj tekst her -->',
'resetpass_header'          => 'Skift adgangskode',
'oldpassword'               => 'Gammel adgangskode:',
'newpassword'               => 'Ny adgangskode:',
'retypenew'                 => 'Gentag ny adgangskode:',
'resetpass_submit'          => 'Gem adgangskode og log på',
'resetpass_success'         => 'Din adgangskode er nu ændret. Logger ind...',
'resetpass_forbidden'       => 'Adgangskoder kan ikke ændres',
'resetpass-no-info'         => 'Du skal være logget på for at komme direkte til denne side.',
'resetpass-submit-loggedin' => 'Skift adgangskode',
'resetpass-submit-cancel'   => 'Annuller',
'resetpass-wrong-oldpass'   => 'Ugyldig midlertidig eller gældende adgangskode. 
Du har muligvis allerede skiftet din adgangskode eller anmodet om en ny midlertidig kode.',
'resetpass-temp-password'   => 'Midlertidig adgangskode',

# Edit page toolbar
'bold_sample'     => 'Fed tekst',
'bold_tip'        => 'Fed tekst',
'italic_sample'   => 'Kursiv tekst',
'italic_tip'      => 'Kursiv tekst',
'link_sample'     => 'Henvisning',
'link_tip'        => 'Intern henvisning',
'extlink_sample'  => 'http://www.example.com Titel på henvisning',
'extlink_tip'     => 'Ekstern henvisning (husk http:// præfiks)',
'headline_sample' => 'Tekst til overskrift',
'headline_tip'    => 'Type 2 overskrift',
'math_sample'     => 'Indsæt formel her (LaTeX)',
'math_tip'        => 'Matematisk formel (LaTeX)',
'nowiki_sample'   => 'Indsæt tekst her som ikke skal wikiformateres',
'nowiki_tip'      => 'Ignorer wikiformatering',
'image_sample'    => 'Eksempel.jpg',
'image_tip'       => 'Indlejret billede',
'media_sample'    => 'Eksempel.ogg',
'media_tip'       => 'Henvisning til multimediefil',
'sig_tip'         => 'Din signatur med tidsstempel',
'hr_tip'          => 'Horisontal linje (brug den sparsomt)',

# Edit pages
'summary'                          => 'Beskrivelse:',
'subject'                          => 'Emne/overskrift:',
'minoredit'                        => 'Dette er en mindre ændring.',
'watchthis'                        => 'Overvåg denne artikel',
'savearticle'                      => 'Gem side',
'preview'                          => 'Forhåndsvisning',
'showpreview'                      => 'Forhåndsvisning',
'showlivepreview'                  => 'Live-forhåndsvisning',
'showdiff'                         => 'Vis ændringer',
'anoneditwarning'                  => 'Du arbejder uden at være logget på. Istedet for brugernavn vises din IP-adresse i versionshistorikken.',
'anonpreviewwarning'               => "''Du er ikke logget ind. Hvis du gemmer, registreres din IP-adresse i versionshistorikken.''",
'missingsummary'                   => "'''Påmindelse:''' Du har ikke angivet en redigeringsbeskrivelse. Hvis du igen trykker på \"Gem\", gemmes ændringerne uden en beskrivelse.",
'missingcommenttext'               => 'Indtast venligst et resume.',
'missingcommentheader'             => "'''Bemærk:''' Du har ikke angivet en overskrift i feltet „Emne:“ for denne kommentar. Hvis du trykker „Gem side“ én gang til, gemmes dine ændringer uden overskrift.",
'summary-preview'                  => 'Forhåndsvisning af beskrivelsen:',
'subject-preview'                  => 'Forhåndsvisning af emnet:',
'blockedtitle'                     => 'Brugeren er blokeret',
'blockedtext'                      => "'''Dit brugernavn eller din IP-adresse er blevet blokeret.'''

Blokeringen er foretaget af $1.
Den anførte grund er ''$2''.

Blokeringen starter: $8
Blokeringen udløber: $6
Blokeringen er rettet mod: $7

Du kan kontakte $1 eller en af de andre [[{{MediaWiki:Grouppage-sysop}}|administratorer]] for at diskutere blokeringen.
Du kan ikke bruge funktionen 'e-mail til denne bruger' medmindre der er angivet en gyldig email-adresse i dine
[[Special:Preferences|kontoindstillinger]], og du ikke er blevet blokeret fra at bruge den.

Din nuværende IP-adresse er $3, og blokerings-id er #$5.
Angiv venligst alle ovenstående detaljer ved henvendelser om blokeringen.",
'autoblockedtext'                  => 'Din IP-adresse er blevet blokeret automatisk fordi den blev brugt af en anden bruger som er blevet blokeret af $1.
Begrundelsen for det er:

:\'\'$2\'\'

* Blokeringsperiodens start: $8
* Blokeringen udløber: $6
* Blokeringen er ment for: $7

Du kan kontakte $1 eller en af de andre [[{{MediaWiki:Grouppage-sysop}}|administratorer]] for at diskutere blokeringen.

Bemærk at du ikke kan bruge funktionen "e-mail til denne bruger" medmindre du har en gyldig e-mail-adresse registreret i din [[Special:Preferences|brugerindstilling]], og du ikke er blevet blokeret fra at bruge den.

Din nuværende IP-adresse er $3, og blokerings-id\'et er #$5.
Angiv venligst alle de ovenstående detaljer ved eventuelle henvendelser.',
'blockednoreason'                  => 'ingen begrundelse givet',
'blockedoriginalsource'            => "Kildekoden fra '''$1''' vises her:",
'blockededitsource'                => "Kildekoden fra '''Dine ændringer''' til '''$1''':",
'whitelistedittitle'               => 'Log på for at redigere',
'whitelistedittext'                => 'Du skal $1 for at kunne redigere sider.',
'confirmedittext'                  => 'Du skal først bekræfte e-mail-adressen, før du kan lave ændringer. Udfyld og bekræft din e-mail-adresse i dine [[Special:Preferences|Indstillinger]].',
'nosuchsectiontitle'               => 'Kan ikke finde afsnittet',
'nosuchsectiontext'                => 'Du forsøgte at ændre et ikke-eksisterende afsnit. Det kan være flyttet eller slettet, siden du hentede siden.',
'loginreqtitle'                    => 'Log på nødvendigt',
'loginreqlink'                     => 'logge på',
'loginreqpagetext'                 => 'Du skal $1 for at se andre sider.',
'accmailtitle'                     => 'Adgangskode sendt.',
'accmailtext'                      => "En tilfældigt dannet adgangskode for [[User talk:$1|$1]] er sendt til $2.

Adgangskoden for denne ny brugerkonto kan ændres på ''[[Special:ChangePassword|skift   adgangskode]]'', når du logger ind.",
'newarticle'                       => '(Ny)',
'newarticletext'                   => "Du har fulgt en henvisning til en side som endnu ikke findes.
For at oprette siden skal du begynde at skrive i boksen nedenfor
(se [[{{MediaWiki:Helppage}}|hjælpesiden]] for yderligere information).
Hvis du er her ved en fejl, så tryk på din browsers '''tilbage'''-knap.",
'anontalkpagetext'                 => "---- ''Dette er en diskussionsside for en anonym bruger, der ikke har oprettet en konto endnu eller ikke bruger den. 
Vi er derfor nødt til at bruge den numeriske IP-adresse til at identificere ham eller hende. 
En IP-adresse kan være delt mellem flere brugere. 
Hvis du er en anonym bruger og synes, at du har fået irrelevante kommentarer på sådan en side, så vær venlig at [[Special:UserLogin/signup|oprette en brugerkonto]] og [[Special:UserLogin|logge på]], så vi undgår fremtidige forvekslinger med andre anonyme brugere.''",
'noarticletext'                    => 'Der er i øjeblikket ikke nogen tekst på denne side.
Du kan [[Special:Search/{{PAGENAME}}|søge efter sidenavnet]] på andre sider,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} søge i relaterede logger]
eller [{{fullurl:{{FULLPAGENAME}}|action=edit}} oprette siden]</span>.',
'noarticletext-nopermission'       => 'Der er i øjeblikket ikke noget tekst på denne side.
Du kan [[Special:Search/{{PAGENAME}}|søge efter denne sides titel]] på andre sider,
eller <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} se de relaterede loglister]</span>.',
'userpage-userdoesnotexist'        => 'Brugerkontoen "$1" findes ikke. Overvej om du ønsker at oprette eller redigere denne side.',
'userpage-userdoesnotexist-view'   => 'Brugerkontoen "$1" er ikke oprettet.',
'blocked-notice-logextract'        => 'Denne bruger er i øjeblikket blokeret. 
Loggen over den seneste blokering ses nedenfor:',
'clearyourcache'                   => "'''Bemærk: Efter at have gemt er du nødt til at tømme din browsers cache for at kunne se ændringerne.'''
'''Mozilla / Firefox / Safari''': Hold ''shifttasten'' nede og klik på ''reload'', eller tryk enten ''Ctrl-F5'' eller ''Ctrl-Shift-r'' (Mac: ''cmd-shift-r'');
'''Konqueror''': Klik på ''reload'' eller tryk på ''F5'';
'''Opera''': Tøm cachen i ''Tools → Preferences'';
'''Internet Explorer''': hold ''controltasten'' nede og klik på ''refresh'' eller tryk på ''control-F5''.",
'usercssyoucanpreview'             => "'''Tip:''' Brug \"{{int:showpreview}}\"-knappen for at teste dit nye CSS inden du gemmer.",
'userjsyoucanpreview'              => "'''Tip:''' Brug \"{{int:showpreview}}\"-knappen for at teste dit nye JavaScript inden du gemmer.",
'usercsspreview'                   => "'''Husk at dette kun er en forhåndsvisning af dit eget css.
'''Det er ikke gemt endnu!'''",
'userjspreview'                    => "'''Husk at du kun tester/forhåndsviser dit eget javascript, det er ikke gemt endnu!'''",
'userinvalidcssjstitle'            => "'''Advarsel:''' Der findes intet skin „$1“. Tænk på, at brugerspecifikke .css- og .js-sider begynder med små bogstaver, altså f.eks. ''{{ns:user}}:Hansen/monobook.css'' og ikke ''{{ns:user}}:Hansen/Monobook.css''.",
'updated'                          => '(Opdateret)',
'note'                             => "'''Bemærk:'''",
'previewnote'                      => 'Husk at dette er kun en forhåndsvisning, siden er ikke gemt endnu!',
'previewconflict'                  => 'Denne forhåndsvisning er resultatet af den redigérbare tekst ovenfor, sådan vil det komme til at se ud hvis du vælger at gemme teksten.',
'session_fail_preview'             => "'''Din ændring kunne ikke gemmes, da dine sessionsdata er gået tabt.
Prøv venligst igen. Hvis problemet fortsætter, log af og log på igen.'''",
'session_fail_preview_html'        => "'''Din ændring kunne ikke gemmes, da dine sessionsdata er gået tabt.'''

''Da ren HTM er aktiveret i denne Wiki, er forhåndsvisningen blændet ud for at forebygge JavaScript-angreb.''

'''Forsøg venligst igen. Hvis problemet fortsætter, log af og log på igen.'''",
'token_suffix_mismatch'            => "'''Din redigering er afvist, da din browser har forvansket tegnsætningen i redigeringskontrolfilen. Afvisningen sker for at forhindre utilsigtede ændringer i artiklen. Denne fejl opstår nogle gange, når du redigerer gennem en fejlprogrammeret webbaseret anonymiseringstjeneste.'''",
'editing'                          => 'Redigerer $1',
'editingsection'                   => 'Redigerer $1 (afsnit)',
'editingcomment'                   => 'Redigerer $1 (nyt afsnit)',
'editconflict'                     => 'Redigeringskonflikt: $1',
'explainconflict'                  => 'Nogen har ændret denne side, efter du startede på at redigere den.
Den øverste tekstboks indeholder den nuværende tekst.
Dine ændringer er vist i den nederste tekstboks.
Du er nødt til at sammenflette dine ændringer med den eksisterende tekst.
<b>Kun</b> teksten i den øverste tekstboks vil blive gemt når du trykker "Gem side".<br />',
'yourtext'                         => 'Din tekst',
'storedversion'                    => 'Den gemte version',
'nonunicodebrowser'                => "'''Advarsel: Din browser er ikke unicode-kompatibel, skift eller opdater din browser før du redigerer en artikel.'''",
'editingold'                       => "'''ADVARSEL: Du redigerer en gammel version af denne side.
Hvis du gemmer den, vil alle ændringer foretaget siden denne revision blive overskrevet.'''",
'yourdiff'                         => 'Forskelle',
'copyrightwarning'                 => "Bemærk venligst at alle bidrag til {{SITENAME}} er at betragte som udgivne under $2 (se $1 for detaljer).
Hvis du ikke ønsker at din tekst skal udsættes for nådesløse redigeringer og at den kan blive kopieret efter forgodtbefindende, så skal du ikke placere den her.<br />
Du lover os også, at du selv har forfattet teksten eller har kopieret den fra en public domain-kilde eller en tilsvarende fri kilde. 
'''Læg aldrig materiale her som er beskyttet af andres ophavsret uden deres tilladelse!'''",
'copyrightwarning2'                => "Bemærk venligst at alle bidrag til {{SITENAME}} kan bearbejdes, ændres eller slettes af andre brugere.
Læg ingen tekster ind, hvis du ikke kan acceptere at disse kan ændres.

Du bekræfter hermed også, at du selv har skrevet denne tekst eller kopieret den fra en fri kilde (se $1 for detaljer).
'''OVERFØR IKKE OPHAVSRETSLIGT BESKYTTET INDHOLD UDEN TILLADELSE!'''",
'longpagewarning'                  => "'''ADVARSEL: Denne side er $1 kilobyte stor; nogle browsere kan have problemer med at redigere sider der nærmer sig eller er større end 32 kb. 
Overvej om siden kan opdeles i mindre dele.'''",
'longpageerror'                    => "'''FEJL: Teksten, som du ville gemme, er $1 kB stor. Det er større end det tilladet maksimum på $2 kB. Det er ikke muligt at gemme.'''",
'readonlywarning'                  => "'''ADVARSEL: Databasen er låst på grund af vedligeholdelse, så du kan ikke gemme dine ændringer lige nu. Det kan godt være en god ide at kopiere din tekst til en tekstfil, så du kan gemme den til senere.'''

Systemadministratoren som låste databasen, gav denne forklaring: $1",
'protectedpagewarning'             => "'''ADVARSEL: Denne side er skrivebeskyttet, så kun administratorer kan redigere den.'''<br />
Den seneste logpost vises nedenfor:",
'semiprotectedpagewarning'         => "'''Bemærk: Siden er låst, så kun registrerede brugere kan ændre den.'''
<br />Den seneste logpost vises nedenfor:",
'cascadeprotectedwarning'          => "'''BEMÆRK: Denne side er skrivebeskyttet, så den kun kan ændres af brugere med Administratorrettigheder. Den er indeholdt i nedenstående {{PLURAL:$1|side|sider}}, som er skrivebeskyttet med tilvalg af nedarvende sidebeskyttelse:'''",
'titleprotectedwarning'            => "ADVARSEL:  Den side er låst så kun [[Special:ListGroupRights|visse brugere]] kan oprette den.'''
<br />Den seneste logpost vises nedenfor:",
'templatesused'                    => '{{PLURAL:$1|Skabelon|Skabeloner}} der er brugt på denne side:',
'templatesusedpreview'             => 'Følgende {{PLURAL:$1|Skabelon|Skabeloner}} bruges af denne artikelforhåndsvisning:',
'templatesusedsection'             => 'Følgende {{PLURAL:$1|skabelon|skabeloner}} bruges af dette afsnit:',
'template-protected'               => '(skrivebeskyttet)',
'template-semiprotected'           => '(delvist beskyttet)',
'hiddencategories'                 => 'Denne side er i {{PLURAL:$1|en skjult kategori|$1 skjulte kategorier}}:',
'edittools'                        => '<!-- Denne tekst vises under formularen „Ændre“ samt "Upload". -->',
'nocreatetitle'                    => 'Oprettelse af nye sider er begrænset.',
'nocreatetext'                     => 'Serveren har begrænset oprettelse af nye sider. Bestående sider kan ændres eller [[Special:UserLogin|logge på]].',
'nocreate-loggedin'                => 'Du har ikke rettigheder til at oprette nye sider.',
'sectioneditnotsupported-title'    => 'Afsnitsredigering understøttes ikke',
'sectioneditnotsupported-text'     => 'Afsnitsredigering understøttes ikke på denne side.',
'permissionserrors'                => 'Rettighedskonflikt',
'permissionserrorstext'            => 'Du har ikke rettigheder til at gennemføre denne handling, {{PLURAL:$1|årsagen|årsagerne}} er:',
'permissionserrorstext-withaction' => 'Du har ikke rettigheder til at $2 af følgende {{PLURAL:$1|grund|grunde}}:',
'recreate-moveddeleted-warn'       => "'''Advarsel: Du er ved at genskabe en tidligere slettet side.'''
 
Overvej om det er passende at genoprette siden. Sletnings- og flytteloggerne for siden er vist nedenfor.",
'moveddeleted-notice'              => 'Denne side er blevet slettet.
Sletnings- og flytteloggerne for siden er vist nedenfor.',
'log-fulllog'                      => 'Vis fuldstændig log',
'edit-hook-aborted'                => 'Redigering afbrudt af en programfunktion. Ingen tilknyttet forklaring.',
'edit-gone-missing'                => 'Kunne ikke opdatere siden. 
Den ser du til at være slettet.',
'edit-conflict'                    => 'Redigeringskonflikt.',
'edit-no-change'                   => 'Din ændring ignoreredes, fordi der ikke var ændring af teksten.',
'edit-already-exists'              => 'En ny side kunne ikke oprettes, fordi den allerede findes.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Advarsel: Der er for mange beregningstunge oversætter-funktionskald på denne side.

Der bør være færre end {{PLURAL:$2|$2 kald}}, lige nu er der {{PLURAL:$1|$1 kald}}.',
'expensive-parserfunction-category'       => 'Sider med for mange beregningstunge oversætter-funktioner',
'post-expand-template-inclusion-warning'  => 'Advarsel: Der er tilføjet for mange skabeloner til denne side, så nogle af dem bliver ikke vist..',
'post-expand-template-inclusion-category' => 'Sider der indeholder for mange skabeloner',
'post-expand-template-argument-warning'   => 'Advarsel: Mindst en af skabelonparametrene på denne side fylder mere end det tilladte. Denne parameter er derfor udeladt.',
'post-expand-template-argument-category'  => 'Sider med udeladte skabelonparametre',
'parser-template-loop-warning'            => 'Skabelonløkke fundet: [[$1]]',
'parser-template-recursion-depth-warning' => 'En skabelon er rekursivt inkluderet for mange gange ($1)',
'language-converter-depth-warning'        => 'Dybdegrænse for sprogkonvertering overskredet ($1)',

# "Undo" feature
'undo-success' => 'Redigeringen kan fjernes.
Kontroller venligst sammenligningen herunder for at bekræfte at det er hvad du ønsker at gøre, og gem så ændringerne for at fuldføre fjernelsen.',
'undo-failure' => 'Redigeringen kunne ikke fjernes på grund af konflikter med efterfølgende redigeringer.',
'undo-norev'   => 'Redigeringen kunne ikke fjernes fordi den ikke findes eller er blevet slettet.',
'undo-summary' => 'Fjerner version $1 af [[Special:Contributions/$2|$2]] ([[User talk:$2|diskussion]])',

# Account creation failure
'cantcreateaccounttitle' => 'Brugerkontoen kan ikke oprettes.',
'cantcreateaccount-text' => "Oprettelsen af en brugerkonto fra IP-adressen <b>$1</b> er spærret af [[User:$3|$3]]. Årsagen til blokeringen er angivet som ''$2''",

# History pages
'viewpagelogs'           => 'Vis loglister for denne side',
'nohistory'              => 'Der er ingen versionshistorik for denne side.',
'currentrev'             => 'Nuværende version',
'currentrev-asof'        => 'Nuværende version fra $1',
'revisionasof'           => 'Versionen fra $1',
'revision-info'          => 'Version fra $1 af $2',
'previousrevision'       => '←Ældre version',
'nextrevision'           => 'Nyere version→',
'currentrevisionlink'    => 'se nuværende version',
'cur'                    => 'nuværende',
'next'                   => 'næste',
'last'                   => 'forrige',
'page_first'             => 'Starten',
'page_last'              => 'Enden',
'histlegend'             => 'Forklaring: (nuværende) = forskel til den nuværende
version, (forrige) = forskel til den forrige version, M = mindre ændring',
'history-fieldset-title' => 'Bladr i historikken efter dato',
'history-show-deleted'   => 'Kun slettede',
'histfirst'              => 'Ældste',
'histlast'               => 'Nyeste',
'historysize'            => '($1 {{PLURAL:$1|Byte|Bytes}})',
'historyempty'           => '(tom)',

# Revision feed
'history-feed-title'          => 'Versionshistorie',
'history-feed-description'    => 'Versionshistorie for denne side i {{SITENAME}}',
'history-feed-item-nocomment' => '$1 med $2',
'history-feed-empty'          => 'Den ønskede side findes ikke. Måske er den slettet eller flyttet. [[Special:Search|Gennesøg]] {{SITENAME}} efter passende nye sider.',

# Revision deletion
'rev-deleted-comment'         => '(kommentar fjernet)',
'rev-deleted-user'            => '(brugernavn fjernet)',
'rev-deleted-event'           => '(loghandling fjernet)',
'rev-deleted-user-contribs'   => '[brugernavn eller IP-adresse er fjernet - redigeringer er skjult fra bidragene]',
'rev-deleted-text-permission' => "Denne version af siden er blevet '''slettet'''.
Der er måske flere detaljer i [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} sletteloggen].",
'rev-deleted-text-unhide'     => "Denne version af siden er '''slettet'''.
Der er måske flere detaljer i [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} sletningsloggen].
Som administrator kan du stadig [$1 se versionen] hvis du ønsker at fortsætte.",
'rev-suppressed-text-unhide'  => "Denne version af siden er '''skjult'''.
Der er måske flere detaljer i [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} skjulningsloggen].
Som administrator kan du stadig [$1 se versionen] hvis du ønsker at fortsætte.",
'rev-deleted-text-view'       => "Denne version af siden er '''slettet'''.
Som administrator kan du se den. 
Der er måske flere detaljer i [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} sletningsloggen].",
'rev-suppressed-text-view'    => "Denne version af siden er '''skjult'''.
Som administrator kan du se den.  
Der er måske flere detaljer i [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} skjulningsloggen].",
'rev-deleted-no-diff'         => "Du kan ikke vise denne forskel fordi en af versionerne er '''slettet'''
Der er måske flere detaljer i [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} sletningsloggen].",
'rev-suppressed-no-diff'      => "Du kan ikke se denne forskel, fordi en af revisionerne er blevet '''slettet'''.",
'rev-deleted-unhide-diff'     => "En af versionerne for denne forskel er '''slettet'''.
Der er måske flere detaljer i [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} sletningsloggen].
Som administrator kan du stadig [$1 se forskellen] hvis du ønsker at fortsætte.",
'rev-suppressed-unhide-diff'  => "En af sideversionerne i denne forskel er blevet '''skjult'''.
Der kan være detaljer i [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} skjulningsloggen].
Som en administrator kan du stadig [$1 se denne forskel] hvis du ønsker det.",
'rev-deleted-diff-view'       => "En af revisionerne i denne forskel er blevet '''slettet'''.
Som administrator kan du se denne forskel; detaljer kan findes i [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} sletningsloggen].",
'rev-suppressed-diff-view'    => "En af revisionerne i denne forskel er blevet '''skjult'''.
Som administrator kan du se denne forskel; der kan findes detaljer i [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} skjulningsloggen].",
'rev-delundel'                => 'vise/skjule',
'rev-showdeleted'             => 'vis',
'revisiondelete'              => 'Slette/gendanne versioner',
'revdelete-nooldid-title'     => 'Ingen version angivet',
'revdelete-nooldid-text'      => 'Du har ikke angivet en version, som denne handling kan udføres på.',
'revdelete-nologtype-title'   => 'Ingen logtype er angivet',
'revdelete-nologtype-text'    => 'Du har ikke angivet en logtype at udføre denne handling på.',
'revdelete-nologid-title'     => 'Ugyldig logpost',
'revdelete-nologid-text'      => 'Du har ikke angivet en logpost at udføre denne funktion på eller den angivne post findes ikke.',
'revdelete-no-file'           => 'Den angivne fil findes ikke.',
'revdelete-show-file-confirm' => 'Er du sikker på, at du vil vise en slettet version af filen "<nowiki>$1</nowiki>" fra den $2, klokken $3?',
'revdelete-show-file-submit'  => 'Ja',
'revdelete-selected'          => "'''{{PLURAL:$2|Valgte version|Valgte versioner}} af '''$1:''''''",
'logdelete-selected'          => "'''{{PLURAL:$1|Valgt logindførsel|Valgte logindførsler}}:'''",
'revdelete-text'              => "'''Slettede versioner vil fortsat vises i sidehistorik og på logsider, men dele af deres indhold vil ikke være offentligt tilgængeligt.'''
Andre administratorer på {{SITENAME}} vil fortsat være i stand til at se det skjulte indhold og kan gendanne det igen, medmindre der laves yderligere restriktioner.",
'revdelete-confirm'           => 'Vær venlig at bekræfte at du vil gøre dette, at du forstår konsekvenserne, og at du gør det i overensstemmelse med [[{{MediaWiki:Policy-url}}|retningslinjerne]].',
'revdelete-suppress-text'     => "Skjulning bør '''kun''' bruges i de følgende tilfælde:
* Upassende personlige oplysninger
*: ''hjemadresser og -telefonnumre, CPR-numre og lign.''",
'revdelete-legend'            => 'Fastlægge begrænsninger for versionerne:',
'revdelete-hide-text'         => 'Skjul versionens tekst',
'revdelete-hide-image'        => 'Skjul filindhold',
'revdelete-hide-name'         => 'Skjul handling og mål',
'revdelete-hide-comment'      => 'Skjul ændringskommentar',
'revdelete-hide-user'         => 'Skjul brugerens brugernavn/IP',
'revdelete-hide-restricted'   => 'Skjul også informationen for administratorer',
'revdelete-radio-same'        => '(ændr ikke)',
'revdelete-radio-set'         => 'Ja',
'revdelete-radio-unset'       => 'Nej',
'revdelete-suppress'          => 'Skjul også informationen for administratorer',
'revdelete-unsuppress'        => 'Ophæv begrænsninger for gendannede versioner',
'revdelete-log'               => 'Begrundelse:',
'revdelete-submit'            => 'Udfør på {{PLURAL:$1|den udvalgte version|de udvalgte versioner}}',
'revdelete-logentry'          => 'Versionsvisning ændret for [[$1]]',
'logdelete-logentry'          => "ændrede [[$1]]'s synlighed",
'revdelete-success'           => "'''Versionsvisningen er ændret.'''",
'revdelete-failure'           => "'''Kunne ikke ændre versionssynligheden:'''
$1",
'logdelete-success'           => "'''Synlighed ændret med success.'''",
'logdelete-failure'           => "'''Kunne ikke ændre logsynligheden.''' 
$1",
'revdel-restore'              => 'Ændre synlighed',
'revdel-restore-deleted'      => 'slettede redigeringer',
'revdel-restore-visible'      => 'synlige ændringer',
'pagehist'                    => 'Sidehistorik',
'deletedhist'                 => 'Slettet historik',
'revdelete-content'           => 'indhold',
'revdelete-summary'           => 'redigeringsbeskrivelse',
'revdelete-uname'             => 'bruger',
'revdelete-restricted'        => 'tilføjede begrænsninger for administratorer',
'revdelete-unrestricted'      => 'fjernede begrænsninger for administratorer',
'revdelete-hid'               => 'usynliggjorde $1',
'revdelete-unhid'             => 'synliggjorde $1',
'revdelete-log-message'       => '$1 for $2 {{PLURAL:$2|version|versioner}}',
'logdelete-log-message'       => '$1 for $2 {{PLURAL:$2|hændelse|hændelser}}',
'revdelete-hide-current'      => 'Der er opstået en fejl under skjulningen af objektet dateret $1 klokken $2: Dette er den nyeste revision.
Den kan ikke skjules.',
'revdelete-show-no-access'    => 'Fejl under visning af objekt dateret $1 klokken $2: Dette objekt er blevet markeret som "begrænset".
Du har ikke adgang til det.',
'revdelete-modify-no-access'  => 'Fejl under ændring af objekt dateret $1 klokken $2: Dette objekt er blevet markeret som "begrænset".
Du har ikke adgang til det.',
'revdelete-modify-missing'    => 'Fejl under redigering af objekt ID $1: Det findes ikke i databasen!',
'revdelete-no-change'         => "'''Advarsel:''' objektet dateret $1 klokken $2 havde allerede de efterspurgte synlighedsindstillinger.",
'revdelete-concurrent-change' => 'Fejl under modificering af objekt dateret $1 klokken $2: Dens status ser ud til at være blevet ændret af en, imens du prøvede at modificere den.
Se venligst loglisterne.',
'revdelete-only-restricted'   => 'Fejl under skjulning af objekt dateret $2, $1: Du kan ikke skjule objekter for administratorerne uden at vælge en alternativ indstilling.',
'revdelete-reason-dropdown'   => '* Almindelige sletningsårsager
** Overtrædelse af ophavsret
** Upassende personlige oplysninger',
'revdelete-otherreason'       => 'Anden/yderligere årsag:',
'revdelete-reasonotherlist'   => 'Anden årsag',
'revdelete-edit-reasonlist'   => 'Rediger sletningsårsager',
'revdelete-offender'          => 'Forfatter af denne revision:',

# Suppression log
'suppressionlog'     => 'Skjulningslog',
'suppressionlogtext' => 'Nedenfor listes de sletninger og blokeringer der er skjult for almindelige systemadministratorer.Below is a list of deletions and blocks involving content hidden from sysops.
Se [[Special:IPBlockList|IP blokeringslisten]] for alle blokeringer.',

# Revision move
'moverevlogentry'              => 'flyttede {{PLURAL:$3|en version|$3 versioner}} fra $1 til $2',
'revisionmove'                 => 'Flyt ændringer fra "$1"',
'revmove-explain'              => 'Følgende versioner vil blive flyttet fra $1 til den angivne målside. Hvis målsiden ikke findes, vil den blive oprettet. Ellers vil disse versioner blive flettet ind i sidens historik.',
'revmove-legend'               => 'Sæt målside og opsummering',
'revmove-submit'               => 'Flyt revisioner til den valgte side',
'revisionmoveselectedversions' => 'Flyt valgte revisioner',
'revmove-reasonfield'          => 'Begrundelse:',
'revmove-titlefield'           => 'Målside:',
'revmove-badparam-title'       => 'Forkerte parametre',
'revmove-badparam'             => 'Din forespørgsel indeholder ugyldige eller utilstrækkelige parametre.
Vend tilbage til forrige side og prøv igen.',
'revmove-norevisions-title'    => 'Ugyldig målversion',
'revmove-norevisions'          => 'Du har ikke angivet en eller flere målversioner for at udføre denne funktion eller den angivne version eksisterer ikke.',
'revmove-nullmove-title'       => 'Forkert titel',
'revmove-nullmove'             => 'Slutsiden kan ikke være identisk med startsiden.
Vend tilbage til forrige side og vælg et andet navn fra "$1".',
'revmove-success-existing'     => '{{PLURAL:$1|En version fra [[$2]] er|$1 versioner fra [[$2]] er}} blevet flyttet til den eksisterende side [[$3]].',
'revmove-success-created'      => '{{PLURAL:$1|En version fra [[$2]] er|$1 versioner fra [[$2]] er}} blevet flyttet til den nyligt oprettede side [[$3]].',

# History merging
'mergehistory'                     => 'Sammenflet sidehistorikker',
'mergehistory-header'              => "Denne sider giver mulighed for at flette historikken fra en kildeside ind i en nyere side. 
Vær opmæksom på at bevare kontinuiteten i sidehistorikken.

'''Bevar som minimum den nuværende udgave af kildesiden.'''",
'mergehistory-box'                 => 'Sammenflet versioner af to sider:',
'mergehistory-from'                => 'Kildeside:',
'mergehistory-into'                => 'Destinationsside:',
'mergehistory-list'                => 'Sammenflettelig revisioner',
'mergehistory-merge'               => 'Nedenstående udgave af [[:$1]] kan sammenflettes med [[:$2]]. Vælg én version nedfor for at sammenflette denne og alle tidligere versioner. Bemærk at navigation i historikken nulstiller valget.',
'mergehistory-go'                  => 'Vis sammenflettelige versioner',
'mergehistory-submit'              => 'Sammenflet versioner',
'mergehistory-empty'               => 'Der findes ingen sammenflettelige udgaver',
'mergehistory-success'             => '$3 {{PLURAL:$3|version|versioner}} af [[:$1]] blev flettet sammen med [[:$2]].',
'mergehistory-fail'                => 'Sammenfletningen kunne ikke gennemføres. Vær venlig at kontrollere sidenavne og tidsafgrænsning.',
'mergehistory-no-source'           => 'Kildesiden $1 findes ikke.',
'mergehistory-no-destination'      => 'Destinationssiden $1 findes ikke.',
'mergehistory-invalid-source'      => 'Angiv et gyldigt sidenavn som kildeside.',
'mergehistory-invalid-destination' => 'Angiv et gyldigt sidenavn som destinationsside.',
'mergehistory-autocomment'         => 'Flettede [[:$1]] ind i [[:$2]]',
'mergehistory-comment'             => 'Flettede [[:$1]] ind i [[:$2]]: $3',
'mergehistory-same-destination'    => 'Kilde- og målside kan ikke være den samme',
'mergehistory-reason'              => 'Begrundelse:',

# Merge log
'mergelog'           => 'Sammenfletningslog',
'pagemerge-logentry' => 'flettede [[$1]] ind i [[$2]] (revisioner indtil $3)',
'revertmerge'        => 'Gendan sammenfletning',
'mergelogpagetext'   => 'Nedenfor vises en liste med de nyeste sammenfletninger af en sides historik i en anden.',

# Diffs
'history-title'            => 'Versionshistorik for "$1"',
'difference'               => '(Forskel mellem versioner)',
'lineno'                   => 'Linje $1:',
'compareselectedversions'  => 'Sammenlign valgte versioner',
'showhideselectedversions' => 'Vis/skjul udvalgte versioner',
'editundo'                 => 'fjern redigering',
'diff-multi'               => '(Versionssammenligningen medtager {{plural:$1|en mellemliggende version|$1 mellemliggende versioner}}.)',

# Search results
'searchresults'                    => 'Søgeresultater',
'searchresults-title'              => 'Søgeresultater for "$1"',
'searchresulttext'                 => 'For mere information om søgning på {{SITENAME}}, se [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'Du søgte efter \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|alle sider som begynder med "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|alle sider som henviser til "$1"]])',
'searchsubtitleinvalid'            => 'Til din søgning „$1“.',
'toomanymatches'                   => 'Søgningen fandt for mange sider. Prøv venligst med en anden søgning.',
'titlematches'                     => 'Artikeltitler der opfyldte forespørgslen',
'notitlematches'                   => 'Ingen artikeltitler opfyldte forespørgslen',
'textmatches'                      => 'Artikeltekster der opfyldte forespørgslen',
'notextmatches'                    => 'Ingen artikeltekster opfyldte forespørgslen',
'prevn'                            => 'forrige {{PLURAL:$1|$1}}',
'nextn'                            => 'næste {{PLURAL:$1|$1}}',
'prevn-title'                      => 'Forrige $1 {{PLURAL:$1|resultat|resultater}}',
'nextn-title'                      => 'Næste $1 {{PLURAL:$1|resultat|resultater}}',
'shown-title'                      => 'Vis $1 {{PLURAL:$1|resultat|resultater}} per side',
'viewprevnext'                     => 'Vis ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Søgemuligheder',
'searchmenu-exists'                => "'''Der er en side med navnet \"[[:\$1]]\" på denne wiki'''",
'searchmenu-new'                   => "'''Opret siden \"[[:\$1]]\" i denne wiki'''",
'searchhelp-url'                   => 'Help:Hjælp',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Vis alle sider som begynder med dette præfiks]]',
'searchprofile-articles'           => 'Indholdssider',
'searchprofile-project'            => 'Hjælpe- og projektsider',
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Alt',
'searchprofile-advanced'           => 'Avanceret',
'searchprofile-articles-tooltip'   => 'Søg i $1',
'searchprofile-project-tooltip'    => 'Søg i $1',
'searchprofile-images-tooltip'     => 'Søg efter filer',
'searchprofile-everything-tooltip' => 'Søg i alt indhold (inklusive diskussionssider)',
'searchprofile-advanced-tooltip'   => 'Søg i bestemte navnerum',
'search-result-size'               => '$1 ({{PLURAL:$2|et ord|$2 ord}})',
'search-result-category-size'      => '{{PLURAL:$1|1 medlem|$1 medlemmer}} ({{PLURAL:$2|1 underkategori|$2 underkategorier}}, {{PLURAL:$3|1 fil|$3 filer}})',
'search-result-score'              => 'Relevans: $1%',
'search-redirect'                  => '(omdiriger $1)',
'search-section'                   => '(sektion $1)',
'search-suggest'                   => 'Mente du: $1',
'search-interwiki-caption'         => 'Søsterprojekter',
'search-interwiki-default'         => '{{PLURAL:$1|et resultat|$1 resultater}}:',
'search-interwiki-more'            => '(mere)',
'search-mwsuggest-enabled'         => 'med forslag',
'search-mwsuggest-disabled'        => 'ingen forslag',
'search-relatedarticle'            => 'Relateret',
'mwsuggest-disable'                => 'Slå AJAX-forslag fra',
'searcheverything-enable'          => 'Søg i alle navnerum',
'searchrelated'                    => 'relateret',
'searchall'                        => 'alle',
'showingresults'                   => 'Nedenfor vises <b>$1</b> {{PLURAL:$1|resultat|resultater}} startende med nummer <b>$2</b>.',
'showingresultsnum'                => 'Herunder vises <b>$3</b> {{PLURAL:$3|resultat|resultater}} startende med nummer <b>$2</b>.',
'showingresultsheader'             => "{{PLURAL:$5|Resultat '''$1''' af '''$3'''|Resultat '''$1 - $2''' af '''$3'''}} for '''$4'''",
'nonefound'                        => "'''Bemærk''': Som standard søges der kun i bestemte navnerum.
Du kan prøve at bruge \"all:\" som præfiks for at søge i alt indhold (inkl. diskussionssider, skabeloner osv.), eller brug det ønskede navnerum som præfiks.",
'search-nonefound'                 => 'Søgningen gav ingen resultater.',
'powersearch'                      => 'Søg',
'powersearch-legend'               => 'Avanceret søgning',
'powersearch-ns'                   => 'Søg i navnerummene:',
'powersearch-redir'                => 'Vis omdirigeringer',
'powersearch-field'                => 'Søg efter',
'powersearch-togglelabel'          => 'Marker:',
'powersearch-toggleall'            => 'Alle',
'powersearch-togglenone'           => 'Ingen',
'search-external'                  => 'Brug anden søgemaskine',
'searchdisabled'                   => '<p>Beklager! Fuldtekstsøgningen er midlertidigt afbrudt på grund af for stor belastning på serverne. I mellemtidem kan du anvende Google- eller Yahoo!-søgefelterne herunder. Bemærk at deres kopier af {{SITENAME}}s indhold kan være forældet.</p>',

# Quickbar
'qbsettings'               => 'Hurtigmenu',
'qbsettings-none'          => 'Ingen',
'qbsettings-fixedleft'     => 'Fast venstre',
'qbsettings-fixedright'    => 'Fast højre',
'qbsettings-floatingleft'  => 'Flydende venstre',
'qbsettings-floatingright' => 'Flydende højre',

# Preferences page
'preferences'                   => 'Indstillinger',
'mypreferences'                 => 'Indstillinger',
'prefs-edits'                   => 'Antal redigeringer:',
'prefsnologin'                  => 'Ikke logget på',
'prefsnologintext'              => 'Du skal være <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} logget på]</span> for at ændre brugerindstillinger.',
'changepassword'                => 'Skift adgangskode',
'prefs-skin'                    => 'Udseende',
'skin-preview'                  => 'Forhåndsvisning',
'prefs-math'                    => 'Matematiske formler',
'datedefault'                   => 'Ingen præference',
'prefs-datetime'                => 'Dato og klokkeslæt',
'prefs-personal'                => 'Brugerdata',
'prefs-rc'                      => 'Seneste ændringer',
'prefs-watchlist'               => 'Overvågningsliste',
'prefs-watchlist-days'          => 'Antal dage, som overvågningslisten standardmæssigt skal omfatte:',
'prefs-watchlist-days-max'      => '(maks. 7 dage)',
'prefs-watchlist-edits'         => 'Antal redigeringer der vises i udvidet overvågningsliste:',
'prefs-watchlist-edits-max'     => '(maks. 1000)',
'prefs-watchlist-token'         => 'Overvågningslistenøgle:',
'prefs-misc'                    => 'Forskelligt',
'prefs-resetpass'               => 'Skift adgangskode',
'prefs-email'                   => 'Indstillinger for e-mail',
'prefs-rendering'               => 'Udseende',
'saveprefs'                     => 'Gem indstillinger',
'resetprefs'                    => 'Gendan indstillinger',
'restoreprefs'                  => 'Gendan alle standardindstillinger',
'prefs-editing'                 => 'Redigering',
'prefs-edit-boxsize'            => 'Størrelse på redigeringsvinduet.',
'rows'                          => 'Rækker',
'columns'                       => 'Kolonner',
'searchresultshead'             => 'Søgeresultater',
'resultsperpage'                => 'Resultater pr. side',
'contextlines'                  => 'Linjer pr. resultat',
'contextchars'                  => 'Tegn pr. linje i resultatet',
'stub-threshold'                => 'Grænse for visning af henvisning som <a href="#" class="stub">artikelstump</a>:',
'recentchangesdays'             => 'Antal dage som skal vises i seneste ændringer:',
'recentchangesdays-max'         => '(maks. $1 {{PLURAL:$1|dag|dage}})',
'recentchangescount'            => 'Antal redigeringer som skal vises som standard:',
'prefs-help-recentchangescount' => 'Det gælder for seneste ændringer, historikker og logger.',
'prefs-help-watchlist-token'    => 'Hvis du udfylder dette felt med en hemmelig værdi, vil der laves et RSS-feed for din overvågingsliste.
Alle som kender nøglen i feltet vil være i stand til at læse din overvågingsliste, så vælg en sikker værdi.
Her er en tilfældig genereret værdi som du kan bruge: $1',
'savedprefs'                    => 'Dine indstillinger er blevet gemt.',
'timezonelegend'                => 'Tidszone:',
'localtime'                     => 'Lokaltid:',
'timezoneuseserverdefault'      => 'Brug serverens tid',
'timezoneuseoffset'             => 'Anden (angiv forskel)',
'timezoneoffset'                => 'Forskel¹:',
'servertime'                    => 'Serverens tid:',
'guesstimezone'                 => 'Hent tidszone fra browseren',
'timezoneregion-africa'         => 'Afrika',
'timezoneregion-america'        => 'Amerika',
'timezoneregion-antarctica'     => 'Antarktis',
'timezoneregion-arctic'         => 'Arktis',
'timezoneregion-asia'           => 'Asien',
'timezoneregion-atlantic'       => 'Atlanterhavet',
'timezoneregion-australia'      => 'Australien',
'timezoneregion-europe'         => 'Europa',
'timezoneregion-indian'         => 'Indiske Ocean',
'timezoneregion-pacific'        => 'Stillehavet',
'allowemail'                    => 'Tillad e-mails fra andre brugere',
'prefs-searchoptions'           => 'Søgeindstillinger',
'prefs-namespaces'              => 'Navnerum',
'defaultns'                     => 'Ellers søg i disse navnerum:',
'default'                       => 'standard',
'prefs-files'                   => 'Filer',
'prefs-custom-css'              => 'Personlig CSS',
'prefs-custom-js'               => 'Personlig JavaScript',
'prefs-common-css-js'           => 'Fælles CSS/JS for alle udseender:',
'prefs-reset-intro'             => 'Du kan bruge denne side til at tilbagestille alle dine indstillinger til standardindstillingerne.
Det kan ikke gøres om.',
'prefs-emailconfirm-label'      => 'Bekræftelse af e-mail:',
'prefs-textboxsize'             => 'Størrelse på redigeringsvindue',
'youremail'                     => 'Din e-mail-adresse:',
'username'                      => 'Brugernavn:',
'uid'                           => 'Brugernummer:',
'prefs-memberingroups'          => 'Medlem af {{PLURAL:$1|gruppen|grupperne}}:',
'prefs-registration'            => 'Registreringstidspunkt:',
'yourrealname'                  => 'Dit rigtige navn:',
'yourlanguage'                  => 'Sprog:',
'yourvariant'                   => 'Sprogvariant:',
'yournick'                      => 'Signatur:',
'prefs-help-signature'          => 'Kommentarer på diskussionssider bør signeres med "<nowiki>~~~~</nowiki>" som vil blive konverteret til din signatur og et tidsstempel.',
'badsig'                        => 'Syntaksen i underskriften er ugyldig; kontroller venligst den brugte HTML.',
'badsiglength'                  => 'Din sigantur er for lang. Den må højst indeholde {{PLURAL:$1|}}$1 tegn.',
'yourgender'                    => 'Køn:',
'gender-unknown'                => 'Uspecificeret',
'gender-male'                   => 'Mand',
'gender-female'                 => 'Kvinde',
'prefs-help-gender'             => 'Angivelse af køn er valgfrit. Det bruges af programmet til at lave kønskorrekte tekster på nogle sprog. Informationen vil være offentlig.',
'email'                         => 'E-mail',
'prefs-help-realname'           => 'Angivelse af rigtigt navn er valgfrit.
Hvis du vælger at oplyse dit navn, vil det blive brugt til at tilskrive dig dit arbejde.',
'prefs-help-email'              => 'Angivelse af e-mail-adresse er valgfrit. Det gør det muligt at sende dig en ny adgangskode hvis du glemmer den.
Du kan også vælge at lade andre brugere kontakte dig gennem din bruger- eller diskussionsside uden at du behøver at afsløre din identitet.',
'prefs-help-email-required'     => 'E-mail-adresse er krævet.',
'prefs-info'                    => 'Grundlæggende information',
'prefs-i18n'                    => 'Internationalisering:',
'prefs-signature'               => 'Signatur',
'prefs-dateformat'              => 'Dataformat',
'prefs-timeoffset'              => 'Tidsforskel',
'prefs-advancedediting'         => 'Avancerede indstillinger',
'prefs-advancedrc'              => 'Avancerede indstillinger',
'prefs-advancedrendering'       => 'Avancerede indstillinger',
'prefs-advancedsearchoptions'   => 'Avancerede indstillinger',
'prefs-advancedwatchlist'       => 'Avancerede indstillinger',
'prefs-display'                 => 'Indstillinger for visning',
'prefs-diffs'                   => 'Forskelle',

# User rights
'userrights'                   => 'Håndtering af brugerrettigheder',
'userrights-lookup-user'       => 'Administrér brugergrupper',
'userrights-user-editname'     => 'Skriv et brugernavn:',
'editusergroup'                => 'Redigér brugergrupper',
'editinguser'                  => "Ændrer brugerrettigheder for bruger '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'Redigér brugergrupper',
'saveusergroups'               => 'Gem brugergrupper',
'userrights-groupsmember'      => 'Medlem af:',
'userrights-groupsmember-auto' => 'Implicit medlem af:',
'userrights-groups-help'       => 'Du kan ændre denne brugers gruppermedlemsskaber:
* Et markeret afkrydsningsfelt betyder at brugeren er medlen af den pågældende gruppe..
* Et umarkeret felt betyder at brugeren ikke er medlem af gruppen.
* En * betyder at du ikke kan fravælge gruppen, når den først er tilføjet og omvendt.',
'userrights-reason'            => 'Årsag:',
'userrights-no-interwiki'      => 'Du kan ikke ændre brugerrettigheder på andre wikier.',
'userrights-nodatabase'        => 'Databasen $1 eksisterer ikke lokalt.',
'userrights-nologin'           => 'Du skal [[Special:UserLogin|logge på]] med en administrativ konto, før du kan ændre brugerettigheder.',
'userrights-notallowed'        => 'Din konto har ikke andgang til at ændre brugerrettigheder.',
'userrights-changeable-col'    => 'Redigerbare grupper',
'userrights-unchangeable-col'  => 'Uredigerbare grupper',

# Groups
'group'               => 'Gruppe:',
'group-user'          => 'Brugere',
'group-autoconfirmed' => 'Registrerede brugere',
'group-bot'           => 'Robotter',
'group-sysop'         => 'Administratorer',
'group-bureaucrat'    => 'Bureaukrater',
'group-suppress'      => 'Logskjulere',
'group-all'           => '(alle)',

'group-user-member'          => 'bruger',
'group-autoconfirmed-member' => 'Registreret bruger',
'group-bot-member'           => 'Robot',
'group-sysop-member'         => 'Administrator',
'group-bureaucrat-member'    => 'Bureaukrat',
'group-suppress-member'      => 'logskjuler',

'grouppage-user'          => '{{ns:project}}:Brugere',
'grouppage-autoconfirmed' => '{{ns:project}}:Registrerede brugere',
'grouppage-bot'           => '{{ns:project}}:Robotter',
'grouppage-sysop'         => '{{ns:project}}:Administratorer',
'grouppage-bureaucrat'    => '{{ns:project}}:Bureaukrater',
'grouppage-suppress'      => '{{ns:project}}:Logskjulere',

# Rights
'right-read'                  => 'Se sider',
'right-edit'                  => 'Redigere sider',
'right-createpage'            => 'Oprette andre sider end diskussionssider',
'right-createtalk'            => 'Oprette diskussionssider',
'right-createaccount'         => 'Oprette nye brugere',
'right-minoredit'             => 'Markere redigeringer som mindre',
'right-move'                  => 'Flytte sider',
'right-move-subpages'         => 'Flytte sider og undersider',
'right-move-rootuserpages'    => 'Flytte hovedbrugersider',
'right-movefile'              => 'Flytte filer',
'right-suppressredirect'      => 'Flytte sider uden at oprette en omdirigering fra det gamle navn',
'right-upload'                => 'Lægge filer op',
'right-reupload'              => 'Overskrive en allerede eksisterende fil',
'right-reupload-own'          => 'Overskrive en eksisterende fil, som er lagt op af brugeren selv',
'right-reupload-shared'       => 'Lægge en lokal fil op, selvom den allerede findes centralt',
'right-upload_by_url'         => 'Lægge en fil op fra en URL',
'right-purge'                 => 'Nulstille sidens cache uden bekræftelse',
'right-autoconfirmed'         => 'Redigere semibeskyttede sider',
'right-bot'                   => 'Redigeringer markeres som robot',
'right-nominornewtalk'        => 'Mindre ændringer på diskussionssider markerer ikke disse med nyt indhold',
'right-apihighlimits'         => 'Bruge højere grænser i API',
'right-writeapi'              => 'Bruge redigeringsdelen af API',
'right-delete'                => 'Slette sider',
'right-bigdelete'             => 'Slette sider med mange versioner',
'right-deleterevision'        => 'Slette og gendanne enkelte versioner af sider',
'right-deletedhistory'        => 'Se slettede verioner, uden at vise versionens indhold.',
'right-deletedtext'           => 'Vise slettet tekst og ændringer i slettede revisioner',
'right-browsearchive'         => 'Søge i slettede sider',
'right-undelete'              => 'Gendanne en side',
'right-suppressrevision'      => 'Skjule og synliggøre sletninger for administratorer',
'right-suppressionlog'        => 'Se skjulte loglister',
'right-block'                 => 'Blokere brugere',
'right-blockemail'            => 'Blokere en brugers mulighed for at sende mail',
'right-hideuser'              => 'Blokere et brugernavn og skjule navnet',
'right-ipblock-exempt'        => 'Redigere fra blokerede IP-adresser',
'right-proxyunbannable'       => 'Redigere gennem automatisk blokeret proxy',
'right-unblockself'           => 'Fjerne blokering af dem selv',
'right-protect'               => 'Ændre beskyttelse og redigere beskyttede sider',
'right-editprotected'         => 'Ændre beskyttede sider (uden nedarvet sidebeskyttelse)',
'right-editinterface'         => 'Ændre brugergrænsefladens tekster',
'right-editusercssjs'         => 'Ændre andre brugeres JS og CSS filer',
'right-editusercss'           => 'Ændre andre brugeres CSS filer',
'right-edituserjs'            => 'Ændre andre brugeres JS filer',
'right-rollback'              => 'Hurtig gendannelse af alle redigeringer foretaget af den seneste bruger',
'right-markbotedits'          => 'Markere gendannelser som ændringer foretaget af en robot',
'right-noratelimit'           => 'Upåvirket af hastighedsgrænser',
'right-import'                => 'Importere sider fra andre wikier',
'right-importupload'          => 'Importere sider fra en uploadet fil',
'right-patrol'                => 'Markere andres ændringer som patruljeret',
'right-autopatrol'            => 'Egne redigeringer vises automatisk som patruljerede',
'right-patrolmarks'           => 'Se de seneste patruljeringer',
'right-unwatchedpages'        => 'Se en liste over uovervågede sider',
'right-trackback'             => 'Tilføje trackback',
'right-mergehistory'          => 'Sammenflette sidehistorik',
'right-userrights'            => 'Ændre alle brugerrettigheder',
'right-userrights-interwiki'  => 'Ændre brugerrettigheder på andre wikier',
'right-siteadmin'             => 'Låse og frigive databasen',
'right-reset-passwords'       => 'Ændre andre brugeres adgangskode',
'right-override-export-depth' => 'Eksportere sider inkl. henviste sider op til en dybde på 5',
'right-sendemail'             => 'Sende e-mail til andre brugere',
'right-revisionmove'          => 'Flyt revisioner',
'right-selenium'              => 'Kør Selenium tests',

# User rights log
'rightslog'      => 'Rettighedslog',
'rightslogtext'  => 'Dette er en log over ændringer i brugeres rettigheder.',
'rightslogentry' => 'ændrede grupperettigheder for „$1“ fra „$2“ til „$3“.',
'rightsnone'     => '(-)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'se denne side',
'action-edit'                 => 'redigere denne side',
'action-createpage'           => 'oprette sider',
'action-createtalk'           => 'oprette diskussionssider',
'action-createaccount'        => 'Oprette denne brugerkonto',
'action-minoredit'            => 'markere denne redigering som mindre',
'action-move'                 => 'flytte denne side',
'action-move-subpages'        => 'flytte denne side og dens undersider',
'action-move-rootuserpages'   => 'flytte hovedbrugersider',
'action-movefile'             => 'flytte denne fil',
'action-upload'               => 'lægge denne fil op',
'action-reupload'             => 'overskrive den nuværende fil',
'action-reupload-shared'      => 'tilsidesætte denne fil i et delt arkiv',
'action-upload_by_url'        => 'lægge denne fil op fra en URL-adresse',
'action-writeapi'             => "bruge skrive-API'et",
'action-delete'               => 'slette denne side',
'action-deleterevision'       => 'slette denne version',
'action-deletedhistory'       => 'se denne sides slettede historik',
'action-browsearchive'        => 'søge i slettede sider',
'action-undelete'             => 'gendanne denne side',
'action-suppressrevision'     => 'se og gendanne denne skjulte version',
'action-suppressionlog'       => 'se denne private log',
'action-block'                => 'blokere denne bruger fra at redigere',
'action-protect'              => 'ændre på beskyttelsen af denne side',
'action-import'               => 'importere denne side fra en anden wiki',
'action-importupload'         => 'importere denne side fra en filoplægning',
'action-patrol'               => 'patruljere andres redigeringer',
'action-autopatrol'           => 'patruljere din redigering',
'action-unwatchedpages'       => 'se listen over uovervågede sider',
'action-trackback'            => 'tilføj et trackback',
'action-mergehistory'         => 'flette denne sides historik',
'action-userrights'           => 'ændre alle brugerrettigheder',
'action-userrights-interwiki' => 'ændre brugerrettigheder for brugere på andre wikier',
'action-siteadmin'            => 'låse eller låse databasen op',
'action-revisionmove'         => 'flyt versioner',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|ændring|ændringer}}',
'recentchanges'                     => 'Seneste ændringer',
'recentchanges-legend'              => 'Indstillinger for seneste ændringer',
'recentchangestext'                 => "På denne side kan du følge de seneste ændringer på '''{{SITENAME}}'''.",
'recentchanges-feed-description'    => 'Med dette feed kan du følge de seneste ændringer på {{SITENAME}}.',
'recentchanges-label-legend'        => 'Tegnforklaring: $1.',
'recentchanges-legend-newpage'      => '$1 – ny side',
'recentchanges-label-newpage'       => 'Denne redigering oprettede en ny side',
'recentchanges-legend-minor'        => '$1 – mindre ændring',
'recentchanges-label-minor'         => 'Dette er en mindre ændring',
'recentchanges-legend-bot'          => '$1 – botændring',
'recentchanges-label-bot'           => 'Denne redigering blev udført af en bot',
'recentchanges-legend-unpatrolled'  => '$1 – upatruljeret ændring',
'recentchanges-label-unpatrolled'   => 'Denne redigering er endnu ikke blevet patruljeret',
'rcnote'                            => "Herunder ses {{PLURAL:$1|'''1''' ændring|de sidste '''$1''' ændringer}} fra {{PLURAL:$2|i dag|de sidste '''$2''' dage}} fra den $4, kl. $5.",
'rcnotefrom'                        => "Nedenfor er op til '''$1''' ændringer siden '''$2''' vist.",
'rclistfrom'                        => 'Vis nye ændringer startende fra $1',
'rcshowhideminor'                   => '$1 mindre ændringer',
'rcshowhidebots'                    => '$1 robotter',
'rcshowhideliu'                     => '$1 registrerede brugere',
'rcshowhideanons'                   => '$1 anonyme brugere',
'rcshowhidepatr'                    => '$1 kontrollerede ændringer',
'rcshowhidemine'                    => '$1 egne bidrag',
'rclinks'                           => 'Vis seneste $1 ændringer i de sidste $2 dage<br />$3',
'diff'                              => 'forskel',
'hist'                              => 'historik',
'hide'                              => 'Skjul',
'show'                              => 'Vis',
'minoreditletter'                   => 'm',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|overvågende bruger|overvågende brugere}}]',
'rc_categories'                     => 'Kun sider fra kategorierne (adskilt med „|“):',
'rc_categories_any'                 => 'Alle',
'rc-change-size'                    => '$1 {{PLURAL:$1|Byte|Bytes}}',
'newsectionsummary'                 => '/* $1 */ nyt afsnit',
'rc-enhanced-expand'                => 'Vis detaljer (kræver JavaScript)',
'rc-enhanced-hide'                  => 'Skjul detaljer',

# Recent changes linked
'recentchangeslinked'          => 'Relaterede ændringer',
'recentchangeslinked-feed'     => 'Relaterede ændringer',
'recentchangeslinked-toolbox'  => 'Relaterede ændringer',
'recentchangeslinked-title'    => 'Ændringer der relaterer til $1',
'recentchangeslinked-noresult' => 'I det valgte tidsrum blev der ikke foretaget ændringer på sider der henvises til.',
'recentchangeslinked-summary'  => "Denne specialside viser de seneste ændringer på de sider der henvises til. Sider på din overvågningsliste er vist med '''fed''' skrift.",
'recentchangeslinked-page'     => 'Side:',
'recentchangeslinked-to'       => 'Vis ændringer i sider der henviser til den angivne side i stedet',

# Upload
'upload'                      => 'Læg en fil op',
'uploadbtn'                   => 'Læg en fil op',
'reuploaddesc'                => 'Tilbage til formularen til at lægge filer op.',
'upload-tryagain'             => 'Gem modificeret filbeskrivelse',
'uploadnologin'               => 'Ikke logget på',
'uploadnologintext'           => 'Du skal være [[Special:UserLogin|logget på]] for at kunne lægge filer op.',
'upload_directory_missing'    => 'upload-kataloget ($1) findes ikke. Webserveren har ikke mulighed for at oprette kataloget.',
'upload_directory_read_only'  => 'Webserveren har ingen skriverettigheder for upload-kataloget ($1).',
'uploaderror'                 => 'Fejl under oplægning af fil',
'upload-recreate-warning'     => "'''Advarsel: En fil med det navn, er blevet slettet eller flyttet.'''

Sletnings- og flytnings-loggen for denne side leveres her for nemheds skyld:",
'uploadtext'                  => "Brug formularen herunder til at lægge filer op.
Gå til [[Special:FileList|listen over filer]] for at se eller søge i eksisterende filer. (Gen-)oplægnigner logges i [[Special:Log/upload|oplægningsloggen]], sletninger i [[Special:Log/delete|sletteloggen]].

Brug en henvisning af en de følgende typer for at bruge en fil på en side:
*'''<tt><nowiki>[[</nowiki>{{ns:file}}:Filnavn.jpg<nowiki>]]</nowiki></tt>''' for at bruge den fulde version af et bilede
*'''<tt><nowiki>[[</nowiki>{{ns:file}}:Filnavn.png|200px|thumb|left|Alternativ tekst<nowiki>]]</nowiki></tt>''' for at bruge billedet med en bredde på 200&nbsp;pixel i en ramme til venstre med \"Alternativ tekst\" som beskrivelse
*'''<tt><nowiki>[[</nowiki>{{ns:media}}:Filnavn.ogg<nowiki>]]</nowiki></tt>''' for at henvise direkte til filen uden at vise den",
'upload-permitted'            => 'Tilladte filtyper: $1.',
'upload-preferred'            => 'Foretrukne filtyper: $1.',
'upload-prohibited'           => 'Uønskede filtyper: $1.',
'uploadlog'                   => 'oplægningslog',
'uploadlogpage'               => 'Oplægningslog',
'uploadlogpagetext'           => 'Herunder vises de senest oplagte filer. Alle de viste tider er serverens tid.',
'filename'                    => 'Filnavn',
'filedesc'                    => 'Beskrivelse',
'fileuploadsummary'           => 'Beskrivelse/kilde:',
'filereuploadsummary'         => 'Filændringer:',
'filestatus'                  => 'Status på ophavsret',
'filesource'                  => 'Kilde',
'uploadedfiles'               => 'Filer som er lagt op',
'ignorewarning'               => 'Ignorer advarsler og gem fil alligevel',
'ignorewarnings'              => 'Ignorer advarsler',
'minlength1'                  => 'Navnet på filen skal være på mindst et bogstav.',
'illegalfilename'             => 'Filnavnet "$1" indeholder tegn, der ikke er tilladte i sidetitler. Omdøb filen og prøv at lægge den op igen.',
'badfilename'                 => 'Navnet på filen er blevet ændret til "$1".',
'filetype-mime-mismatch'      => 'Filendelsen svarer ikke til MIME-typen.',
'filetype-badmime'            => 'Filer med MIME-typen „$1“ må ikke uploades.',
'filetype-bad-ie-mime'        => 'Kan ikke lægge denne fil op fordi Internet Explorer ville betragte den som værende af typen "$1", som er en ikke-tilladt og en potentiel farlig filtype.',
'filetype-unwanted-type'      => "'''\".\$1\"''' er ikke en foretrukken filtype. {{PLURAL:\$3|Den foretrukne filtype|De foretrukne filtyper}} er \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' er en uønsket filtype. {{PLURAL:\$3|Den tilladte filtype|De tillatdte filtyper}} er \$2.",
'filetype-missing'            => 'Filen der skal uploades har ingen endelse (f.eks. „.jpg“).',
'empty-file'                  => 'Den fil du indsendte var tom.',
'file-too-large'              => 'Den fil du indsendte var for stor.',
'filename-tooshort'           => 'Filnavnet er for kort.',
'filetype-banned'             => 'Denne type fil er ikke tilladt',
'verification-error'          => 'Denne fil bestod ikke fil verifikationen.',
'hookaborted'                 => '↓ Ændringen du forsøgte at gøre blev afbrudt af en udvidelses hook.',
'illegal-filename'            => 'Filnavnet er ikke tilladt.',
'overwrite'                   => 'Overskrivning af en eksisterende fil er ikke tilladt.',
'unknown-error'               => 'En ukendt fejl opstod.',
'tmp-create-error'            => 'Kunne ikke oprette temporær fil.',
'tmp-write-error'             => 'Fejl ved skrivning af temporær fil.',
'large-file'                  => 'Filstørrelsen skal så vidt muligt ikke overstige $1. Denne fil er $2 stor.',
'largefileserver'             => 'Filen er større end den på serveren indstillede maksimale størrelse.',
'emptyfile'                   => 'Filen du lagde op lader til at være tom. Det kan skyldes en slåfejl i filnavnet. Kontroller om du virkelig ønsker at lægge denne fil op.',
'fileexists'                  => 'En fil med det navn findes allerede, tjek venligst [[:$1]] om du er sikker på du vil ændre den.
[[$1|thumb]]',
'filepageexists'              => "Siden med beskrivelse af denne fil er allerede oprettet på '''<tt>[[:$1]]</tt>''', men der eksisterer ikke en fil med dette navn. 
Den beskrivelse du kan angive nedenfor vil derfor ikke blive brugt. 
For at få din beskrivelse vist, skal du selv redigere beskrivelsessiden.
[[$1|thumb]]",
'fileexists-extension'        => "En fil med lignende navn findes allerede: [[$2|thumb]]
* Navnet på den valgte fil: '''<tt>[[:$1]]</tt>'''
* Navnet på den eksisterende fil: '''<tt>[[:$2]]</tt>'''
Vælg venligst et andet navn.",
'fileexists-thumbnail-yes'    => "Det ser ud som om filen indeholder et billede i reduceret størrelse ''(thumbnail)''. [[$1|thumb]]
Kontroller filen '''<tt>[[:$1]]</tt>'''.
Hvis det er billedet i original størrelse, er det ikke nødvendigt at uploade et separat forhåndsvisningsbillede.",
'file-thumbnail-no'           => "Filnavnet begynder med '''<tt>$1</tt>'''.
Det tyder på et billede i reduceret format ''(thumbnail)''.
Hvis du har billedet i fuld størrelse, så brug det i stedet. Ellers bedes du venligst ændre filnavnet.",
'fileexists-forbidden'        => 'En fil med dette navn findes allerede, og den kan ikke overskrives.
Hvis du fortsat vil lægge filen op, bedes du gå tilbage og bruge et nyt navn.
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Der findes allerede en fil med dette navn i det delte filarkiv.
Hvis du fortsat vil lægge filen op, bedes du gå tilbage og bruge et nyt navn.
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Denne fil er en bublet af {{PLURAL:$1|den nedenstående fil|de nedenstående $1 filer}}:',
'file-deleted-duplicate'      => 'En fil identisk med denne fil ([[$1]]) er tidligere blevet slettet. Du bør tjekke den fils sletningshistorik før du prøver at genoplægge den.',
'successfulupload'            => 'Oplægningen er gennemført',
'uploadwarning'               => 'Advarsel',
'uploadwarning-text'          => 'Vær venlig at ændre filbeskrivelsen nedenfor og prøv igen.',
'savefile'                    => 'Gem fil',
'uploadedimage'               => 'lagde "[[$1]]" op',
'overwroteimage'              => 'lagde en ny version af "[[$1]]" op',
'uploaddisabled'              => 'Desværre er funktionen til at lægge filer op afbrudt på denne server.',
'copyuploaddisabled'          => 'Upload via URL er slået fra.',
'uploadfromurl-queued'        => 'Din upload er blevet sat i kø.',
'uploaddisabledtext'          => 'Oplægning af filer er deaktiveret.',
'php-uploaddisabledtext'      => 'Oplægning af filer er forhindret i PHP. Tjek indstillingen for file_uploads.',
'uploadscripted'              => 'Denne fil indeholder HTML eller script-kode, der i visse tilfælde can fejlfortolkes af en browser.',
'uploadvirus'                 => 'Denne fil indeholder en virus! Virusnavn: $1',
'upload-source'               => 'Kildefil',
'sourcefilename'              => 'Vælg en fil:',
'sourceurl'                   => 'Kildens URL:',
'destfilename'                => 'Nyt filnavn:',
'upload-maxfilesize'          => 'Maksimal filstørrelse: $1',
'upload-description'          => 'Filbeskrivelse',
'upload-options'              => 'Oplægningsindstillinger',
'watchthisupload'             => 'Overvåg filen',
'filewasdeleted'              => 'En fil med dette navn er tidligere uploadet og i mellemtiden slettet igen. Kontroller først indførslen i $1, før du gemmer filen.',
'upload-wasdeleted'           => "'''Advarsel: Du er ved at uploade en fil der tidligere er blevet slettet.'''

Overvej om det er passende at fortsætte med uploadet.
Sletningsloggen for denne fil er gengivet herunder.",
'filename-bad-prefix'         => "Navnet på filen du er ved at lægge op begynder med '''\"\$1\"'''. Dette er et ikkebeskrivende navn, der typisk er skabt automatisk af et digitalkamera. Vær venlig at vælge et mere beskrivende navn på dit billede.",
'upload-successful-msg'       => 'Din upload er tilgængelig her: $1',
'upload-failure-subj'         => 'Upload problem',
'upload-failure-msg'          => 'Der var et problem med din upload:

$1',

'upload-proto-error'        => 'Forkert protokol',
'upload-proto-error-text'   => 'Adressen skal begynde med <code>http://</code> eller <code>ftp://</code>.',
'upload-file-error'         => 'Intern fejl',
'upload-file-error-text'    => 'Ved oprettelse af en midlertidig fil på serveren, er der sket en fejl. 
Informer venligst en [[Special:ListUsers/sysop|systemadministrator]].',
'upload-misc-error'         => 'Ukendt fejl ved upload',
'upload-misc-error-text'    => 'Ved upload er der sket en ukendt fejl. Kontroller adressen for fejl, sidens onlinestatus og forsøg igen. Hvis problemet fortsætter, informeres en system-administrator.',
'upload-too-many-redirects' => "URL'en indeholdt for mange omdirigeringer",
'upload-unknown-size'       => 'Ukendt størrelse',
'upload-http-error'         => 'Der opstod en HTTP-fejl: $1',

# img_auth script messages
'img-auth-accessdenied' => 'Adgang nægtet',
'img-auth-nopathinfo'   => 'PATH_INFO mangler.
Din server er ikke sat op til at give denne information.
Den bruger måske CGI og understøtter ikke img_auth.
Se http://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir'     => 'Den ønskede sti er ikke i det opsatte oplægningskatalog.',
'img-auth-badtitle'     => 'Kan ikke lave en gyldig titel ud fra "$1".',
'img-auth-nologinnWL'   => 'Du er ikke logget ind, og "$1" er ikke på hvidlisten.',
'img-auth-nofile'       => 'Filen "$1" findes ikke.',
'img-auth-isdir'        => 'Du forsøger at få adgang til kataloget "$1".
Kun adgang til filer tillades.',
'img-auth-streaming'    => 'Sender "$1".',
'img-auth-public'       => "img_auth.php's funktion er at sende filer fra en privat wiki.
Denne wiki er konfigureret som en offentlig wiki.
For optimal sikkerhed er img_auth.php deaktiveret.",
'img-auth-noread'       => 'Brugeren har ikke rettigheder til at læse "$1".',

# HTTP errors
'http-invalid-url'      => 'Ugyldig webadresse: $1',
'http-invalid-scheme'   => 'URL\'er af typen "$1" er ikke understøttet.',
'http-request-error'    => 'HTTP-forespørgslen fejlede af ukendte årsager.',
'http-read-error'       => 'HTTP-læsefejl.',
'http-timed-out'        => 'HTTP-forespørgslen tog for lang tid.',
'http-curl-error'       => 'Fejl under hentning af URL: $1',
'http-host-unreachable' => 'Webadresse er ikke tilgængelig.',
'http-bad-status'       => 'Der opstod et problem under HTTP-forespørgslen: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL er utilgængelig',
'upload-curl-error6-text'  => 'Den angivne URL er ikke tilgængelig. Kontroller adressen for fejl samt sidens onlinestatus .',
'upload-curl-error28'      => 'Tidsoverskridelse ved upload',
'upload-curl-error28-text' => 'Siden er for længe om at svare. Kontroller om siden er online, vent et øjeblik og prøv igen. Det kan måske hjælpe at prøve på et senere tidspunkt.',

'license'            => 'Licens',
'license-header'     => 'Licensering',
'nolicense'          => 'intet forvalg',
'license-nopreview'  => '(forhåndsvisning ikke mulig)',
'upload_source_url'  => ' (gyldig, offentligt tillgængelig URL)',
'upload_source_file' => ' (en fil på din computer)',

# Special:ListFiles
'listfiles-summary'     => 'Denne specialside viser alle oplagte filer. Standardmæssigt vises de sidst oplagte filer først. Med et klik på spalteoverskriften kan sorteringen vendes om, eller der kan sorteres efter en anden spalte.',
'listfiles_search_for'  => 'Søge efter fil:',
'imgfile'               => 'Fil',
'listfiles'             => 'Filliste',
'listfiles_date'        => 'Dato',
'listfiles_name'        => 'Navn',
'listfiles_user'        => 'Bruger',
'listfiles_size'        => 'Størrelse (Byte)',
'listfiles_description' => 'Beskrivelse',
'listfiles_count'       => 'Versioner',

# File description page
'file-anchor-link'          => 'Fil',
'filehist'                  => 'Filhistorik',
'filehist-help'             => 'Klik på en dato/tid for at se den version af filen.',
'filehist-deleteall'        => 'slet alle',
'filehist-deleteone'        => 'slet denne',
'filehist-revert'           => 'gendan',
'filehist-current'          => 'nuværende',
'filehist-datetime'         => 'Dato/tid',
'filehist-thumb'            => 'Thumbnail',
'filehist-thumbtext'        => 'Thumbnail af versionen fra $1',
'filehist-nothumb'          => 'Ingen thumbnail',
'filehist-user'             => 'Bruger',
'filehist-dimensions'       => 'Dimensioner',
'filehist-filesize'         => 'Filstørrelse',
'filehist-comment'          => 'Kommentar',
'filehist-missing'          => 'Fil mangler',
'imagelinks'                => 'Filhenvisninger',
'linkstoimage'              => '{{Plural:$1|Den følgende side|De følgende $1 sider}} henviser til denne fil:',
'linkstoimage-more'         => 'Flere end $1 {{PLURAL:$1|side|sider}} henviser til denne fil.
Den følgende liste viser kun {{PLURAL:$1|den første henvisning|de $1 første henvisninger}}.
En [[Special:WhatLinksHere/$2|komplet liste]] er tilgængelig.',
'nolinkstoimage'            => 'Der er ingen sider der henviser til denne fil.',
'morelinkstoimage'          => 'Se [[Special:WhatLinksHere/$1|flere henvisninger]] til denne fil.',
'redirectstofile'           => '{{PLURAL:$1|Nedenstående fil|De nedenstående $1 filer}} er en omdirigering til denne fil:',
'duplicatesoffile'          => 'Følgende {{PLURAL:$1|fil er en dublet|filer er dubletter}} af denne fil ([[Special:FileDuplicateSearch/$2|flere detaljer]]):',
'sharedupload'              => 'Denne fil er fra $1 og kan bruges af andre projekter.',
'sharedupload-desc-there'   => 'Denne fil er fra $1 og kan bruges på andre projekter.
Se [$2 filbeskrivelsessiden] for nærmere information.',
'sharedupload-desc-here'    => 'Denne fil er fra $1 og kan bruges på andre projekter.
Indholdet på dens [$2 filbeskrivelsesside] er vist herunder.',
'filepage-nofile'           => 'Der er ingen fil med det navn.',
'filepage-nofile-link'      => 'Der er ingen fil med det navn, med du kan [$1 lægge den op].',
'uploadnewversion-linktext' => 'Læg en ny version af denne fil op',
'shared-repo-from'          => 'fra $1',
'shared-repo'               => 'et delt filarkiv',

# File reversion
'filerevert'                => 'Gendan $1',
'filerevert-legend'         => 'Gendan fil',
'filerevert-intro'          => '<span class="plainlinks">Du gendanner \'\'\'[[Media:$1|$1]]\'\'\' til [$4 version fra $2, $3].</span>',
'filerevert-comment'        => 'Begrundelse:',
'filerevert-defaultcomment' => 'Gendannet til version fra $1, $2',
'filerevert-submit'         => 'Gendan',
'filerevert-success'        => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\' er gendannet til [$4 version fra $2, $3].</span>',
'filerevert-badversion'     => 'Der findes ingen lokal udgave af denne fil med det opgivne tidsstempel.',

# File deletion
'filedelete'                  => 'Slet $1',
'filedelete-legend'           => 'Slet fil',
'filedelete-intro'            => "Du er ved at slette filen '''[[Media:$1|$1]]''' sammen med hele dens historik.",
'filedelete-intro-old'        => '<span class="plainlinks">Du er ved at slette en tidligere version af \'\'\'[[Media:$1|$1]]\'\'\' fra [$4 $2, $3].</span>',
'filedelete-comment'          => 'Begrundelse:',
'filedelete-submit'           => 'Slet',
'filedelete-success'          => "'''$1''' er blevet slettet.",
'filedelete-success-old'      => "Versionen af '''[[Media:$1|$1]]''' fra $2 $3 er blevet slettet.",
'filedelete-nofile'           => "'''$1''' findes ikke.",
'filedelete-nofile-old'       => "Det er ingen version af '''$1''' med de anførte attributter.",
'filedelete-otherreason'      => 'Anden/uddybende begrundelse:',
'filedelete-reason-otherlist' => 'Anden begrundelse',
'filedelete-reason-dropdown'  => '*Hyppige sletningsbegrundelser
** Ophavsretskrænkelse
** Dubletfil
** Filen er ubrugt',
'filedelete-edit-reasonlist'  => 'Rediger sletningsårsager',
'filedelete-maintenance'      => 'Sletning og gendannelse af filer er midlertidigt forhindret på grund af vedligeholdelse.',

# MIME search
'mimesearch'         => 'Søge efter MIME-type',
'mimesearch-summary' => 'På denne specialside kan filerne filtreres efter MIME-typen. Indtastningen skal altid indeholde medie- og undertypen: <tt>image/jpeg</tt> (se billedbeskrivelsessiden).',
'mimetype'           => 'MIME-type:',
'download'           => 'DownloadHerunterladen',

# Unwatched pages
'unwatchedpages' => 'Ikke overvågede sider',

# List redirects
'listredirects' => 'Henvisningsliste',

# Unused templates
'unusedtemplates'     => 'Ikke brugte skabeloner',
'unusedtemplatestext' => 'Her opremses alle sider i {{ns:template}}-navnerummet, der ikke er inkluderet på andre sider.
Husk at kontrollere for andre henvisninger til skabelonerne før de slettes.',
'unusedtemplateswlh'  => 'andre henvisninger',

# Random page
'randompage'         => 'Tilfældig artikel',
'randompage-nopages' => 'Der er ingen sider i {{PLURAL:$2|navnerummet|disse navnerum:}} $1.',

# Random redirect
'randomredirect'         => 'Tilfældige henvisninger',
'randomredirect-nopages' => 'Der er ingen omdirigeringer i navnerummet $1.',

# Statistics
'statistics'                   => 'Statistik',
'statistics-header-pages'      => 'Sidestatistik',
'statistics-header-edits'      => 'Redigeringsstatistik',
'statistics-header-views'      => 'Visningsstatistik',
'statistics-header-users'      => 'Statistik om brugere på {{SITENAME}}',
'statistics-header-hooks'      => 'Anden statistik',
'statistics-articles'          => 'Indholdssider',
'statistics-pages'             => 'Sider',
'statistics-pages-desc'        => 'Alle sider i wikien inklusive diskussionssider, omdirigeringer og andet',
'statistics-files'             => 'Oplagte filer',
'statistics-edits'             => 'Redigeringer siden {{SITENAME}} blev oprettet',
'statistics-edits-average'     => 'Gennemsnitligt antal redigeringer per side',
'statistics-views-total'       => 'Visninger i alt',
'statistics-views-peredit'     => 'Visninger per redigering',
'statistics-users'             => 'Registrerede [[Special:ListUsers|brugere]]',
'statistics-users-active'      => 'Aktive brugere',
'statistics-users-active-desc' => 'Brugere som har udført handlinger i {{PLURAL:$1|det sidste døgn|de sidste $1 dage}}',
'statistics-mostpopular'       => 'Mest besøgte sider',

'disambiguations'      => 'Sider med flertydige titler',
'disambiguationspage'  => 'Template:Flertydig',
'disambiguations-text' => 'De følgende sider henviser til en flertydig titel. De bør henvise direkte til det passende emne i stedet. En side behandles som en side med en flertydig titel hvis den bruger en skabelon som er henvist til fra [[MediaWiki:Disambiguationspage]].',

'doubleredirects'            => 'Dobbelte omdirigeringer',
'doubleredirectstext'        => 'Dette er en liste over sider som omdirigerer til andre omdirigeringssider.
Hver linje indeholder henvisninger til den første og den anden omdirigering, såvel som til målet for den anden omdirigering som sædvanligvis er den "rigtige" målside som den første omdirigering burde henvise til.
<s>Overstregede</s> poster er rettede.',
'double-redirect-fixed-move' => '[[$1]] blev flyttet og er nu en omdirigering til [[$2]]',
'double-redirect-fixer'      => 'Omdirigerings-retter',

'brokenredirects'        => 'Defekte omdirigeringer',
'brokenredirectstext'    => 'Følgende omdirigeringer peger på en side der ikke eksisterer:',
'brokenredirects-edit'   => 'rediger',
'brokenredirects-delete' => 'slet',

'withoutinterwiki'         => 'Sider uden henvisninger til andre sprog',
'withoutinterwiki-summary' => 'Følgende sider henviser ikke til andre sprog.',
'withoutinterwiki-legend'  => 'Præfiks',
'withoutinterwiki-submit'  => 'Vis',

'fewestrevisions' => 'Sider med de færreste versioner',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories'             => '$1 {{PLURAL:$1|kategori|kategorier}}',
'nlinks'                  => '{{PLURAL:$1|1 henvisning|$1 henvisninger}}',
'nmembers'                => '$1 {{PLURAL:$1|medlem|medlemmer}}',
'nrevisions'              => '{{PLURAL:$1|1 ændring|$1 ændringer}}',
'nviews'                  => '{{PLURAL:$1|1 visning|$1 visninger}}',
'specialpage-empty'       => 'Der er ingen resultater at vise.',
'lonelypages'             => 'Forældreløse sider',
'lonelypagestext'         => 'Følgende sider er ikke henvist til eller inkluderet på andre sider på {{SITENAME}}:',
'uncategorizedpages'      => 'Ukategoriserede sider',
'uncategorizedcategories' => 'Ukategoriserede kategorier',
'uncategorizedimages'     => 'Ikke kategoriserede filer',
'uncategorizedtemplates'  => 'Ikke kategoriserede skabeloner',
'unusedcategories'        => 'Ubrugte kategorier',
'unusedimages'            => 'Ubrugte filer',
'popularpages'            => 'Populære sider',
'wantedcategories'        => 'Ønskede kategorier',
'wantedpages'             => 'Ønskede sider',
'wantedpages-badtitle'    => 'Ugyldig titel i resultaterne: $1',
'wantedfiles'             => 'Ønskede filer',
'wantedtemplates'         => 'Ønskede skabeloner',
'mostlinked'              => 'Sider med flest henvisninger',
'mostlinkedcategories'    => 'Mest brugte kategorier',
'mostlinkedtemplates'     => 'Hyppigst brugte skabeloner',
'mostcategories'          => 'Mest brugte sider',
'mostimages'              => 'Mest brugte filer',
'mostrevisions'           => 'Sider med de fleste ændringer',
'prefixindex'             => 'Alle sider med præfiks',
'shortpages'              => 'Korte sider',
'longpages'               => 'Lange sider',
'deadendpages'            => 'Blindgydesider',
'deadendpagestext'        => 'De følgende sider henviser ikke til andre sider i denne wiki.',
'protectedpages'          => 'Skrivebeskyttede sider',
'protectedpages-indef'    => 'Kun beskyttelser uden udløbadato',
'protectedpages-cascade'  => 'Kun nedarvende beskyttelser',
'protectedpagestext'      => 'De følgende sider er beskyttede mod redigering og flytning.',
'protectedpagesempty'     => 'I øjeblikket er ingen sider beskyttet på denne måde.',
'protectedtitles'         => 'Beskyttede sidenavne',
'protectedtitlestext'     => 'Disse sidenavne er beskyttet mod at blive oprettet',
'protectedtitlesempty'    => 'Der er ingen sidetitler der er beskyttet med disse parametre.',
'listusers'               => 'Brugerliste',
'listusers-editsonly'     => 'Vis kun brugere med redigeringer',
'listusers-creationsort'  => 'Sorter efter oprettelsesdato',
'usereditcount'           => '{{PLURAL:$1|én redigering|$1 redigeringer}}',
'usercreated'             => 'Oprettet den $1 $2',
'newpages'                => 'Nyeste sider',
'newpages-username'       => 'Brugernavn:',
'ancientpages'            => 'Ældste sider',
'move'                    => 'Flyt',
'movethispage'            => 'Flyt side',
'unusedimagestext'        => 'De følgende filer findes, men bruges ikke på nogen sider.
Læg mærke til at andre hjemmesider kan henvise til filen med et direkte URL, og kan stadig være listet her, selvom den faktisk er i brug.',
'unusedcategoriestext'    => 'Denne specialside viser alle kategorier, som ikke selv er henført til en kategori.',
'notargettitle'           => 'Sideangivelse mangler',
'notargettext'            => 'Du har ikke angivet en side eller bruger at udføre denne funktion på.',
'nopagetitle'             => 'Siden findes ikke',
'nopagetext'              => 'Den angivne side findes ikke.',
'pager-newer-n'           => '{{PLURAL:$1|1 nyere|$1 nyere}}',
'pager-older-n'           => '{{PLURAL:$1|1 ældre|$1 ældre}}',
'suppress'                => 'Skjul logs',

# Book sources
'booksources'               => 'Bogkilder',
'booksources-search-legend' => 'Søgning efter bøger',
'booksources-go'            => 'Søg',
'booksources-text'          => 'Dette er en liste med henvisninger til Internetsider, som sælger nye og brugte bøger. Der kan der også findes yderligere informationer om bøgerne. {{SITENAME}} er ikke forbundet med nogen af dem.',
'booksources-invalid-isbn'  => 'Det angivne ISBN-nummer ser forkert ud. Tjek med kilden om det er skrevet korrekt.',

# Special:Log
'specialloguserlabel'  => 'Bruger:',
'speciallogtitlelabel' => 'Titel:',
'log'                  => 'Loglister',
'all-logs-page'        => 'Alle offentlige logger',
'alllogstext'          => 'Samlet visning af alle loggene på {{SITENAME}}.
Du kan afgrænse visningen ved at vælge en logtype, brugernavn eller påvirket side. Der skelnes mellem små og store bogstaver for både bruger- og sidenavne.',
'logempty'             => 'Intet passende fundet.',
'log-title-wildcard'   => 'Søg i titler som begynder med teksten',

# Special:AllPages
'allpages'          => 'Alle sider',
'alphaindexline'    => '$1 til $2',
'nextpage'          => 'Næste side ($1)',
'prevpage'          => 'Forrige side ($1)',
'allpagesfrom'      => 'Vis sider fra og med:',
'allpagesto'        => 'Vis sider til og med:',
'allarticles'       => 'Alle sider',
'allinnamespace'    => 'Alle sider (i $1 navnerummet)',
'allnotinnamespace' => 'Alle sider (ikke i $1 navnerummet)',
'allpagesprev'      => 'Forrige',
'allpagesnext'      => 'Næste',
'allpagessubmit'    => 'Vis',
'allpagesprefix'    => 'Vis sider med præfiks:',
'allpagesbadtitle'  => 'Det indtastede sidenavn er ugyldigt: Det har enten et foranstillet sprog-, en Interwiki-forkortelse eller indeholder et eller flere tegn, som ikke må anvendes i sidenavne.',
'allpages-bad-ns'   => 'Navnerummet $1 findes ikke på {{SITENAME}}.',

# Special:Categories
'categories'                    => 'Kategorier',
'categoriespagetext'            => 'Følgende {{PLURAL:$1|kategori|kategorier}} indeholder sider eller media.
[[Special:UnusedCategories|Ubrugte kategorier]] vises ikke her.
Se også [[Special:WantedCategories|ønskede kategorier]].',
'categoriesfrom'                => 'Vis kategorier startende med:',
'special-categories-sort-count' => 'sorter efter antal',
'special-categories-sort-abc'   => 'sorter alfabetisk',

# Special:DeletedContributions
'deletedcontributions'             => 'slettede brugerbidrag',
'deletedcontributions-title'       => 'Slettede brugerbidrag',
'sp-deletedcontributions-contribs' => 'bidrag',

# Special:LinkSearch
'linksearch'       => 'Eksterne henvisninger',
'linksearch-pat'   => 'Søg efter links til:',
'linksearch-ns'    => 'Navnerum:',
'linksearch-ok'    => 'Søg',
'linksearch-text'  => 'Wildcards som "*.wikipedia.org" kan benyttes.<br />Understøttede protokoller: <tt>$1</tt>',
'linksearch-line'  => '$2 linker til $1',
'linksearch-error' => 'Wildcards må kun benyttes i starten af hostnavnet.',

# Special:ListUsers
'listusersfrom'      => 'Vis brugere fra:',
'listusers-submit'   => 'Vis',
'listusers-noresult' => 'Ingen bruger fundet.',
'listusers-blocked'  => '(blokeret)',

# Special:ActiveUsers
'activeusers'            => 'Liste over aktive brugere',
'activeusers-intro'      => 'Dette er en liste over brugere, som har haft en eller anden form for aktivitet inden for {{PLURAL:$1|den|de}} seneste {{PLURAL:$1|dag|$1 dage}}.',
'activeusers-count'      => '$1 {{PLURAL:$1|redigering|redigeringer}} i {{PLURAL:$3|det seneste døgn|de seneste $3 dage}}',
'activeusers-from'       => 'Vis brugere som starter med:',
'activeusers-hidebots'   => 'Skjul robotter',
'activeusers-hidesysops' => 'Skjul administratorer',
'activeusers-noresult'   => 'Ingen brugere fundet.',

# Special:Log/newusers
'newuserlogpage'              => 'Brugeroprettelseslog',
'newuserlogpagetext'          => 'Dette er en log over de senest oprettede brugere.',
'newuserlog-byemail'          => 'kodeord tilsendt pr. e-mail',
'newuserlog-create-entry'     => 'Ny bruger',
'newuserlog-create2-entry'    => 'oprettede ny brugerkonto $1',
'newuserlog-autocreate-entry' => 'Automatisk oprettet konto',

# Special:ListGroupRights
'listgrouprights'                      => 'Brugergrupperettigheder',
'listgrouprights-summary'              => 'Denne side viser de brugergrupper der er defineret på denne wiki og de enkelte gruppers rettigheder.

Der findes muligvis [[{{MediaWiki:Listgrouprights-helppage}}|yderligere information]] om de enkelte rettigheder.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Given rettighed</span>
* <span class="listgrouprights-revoked">Tilbagetrukken rettighed</span>',
'listgrouprights-group'                => 'Gruppe',
'listgrouprights-rights'               => 'Rettigheder',
'listgrouprights-helppage'             => 'Help:Grupperettigheder',
'listgrouprights-members'              => '(vis medlemmer)',
'listgrouprights-addgroup'             => 'Kan tilføje {{PLURAL:$2|gruppen|grupperne}}: $1',
'listgrouprights-removegroup'          => 'Kan fjerne {{PLURAL:$2|gruppen|grupperne}}: $1',
'listgrouprights-addgroup-all'         => 'Kan tilføje alle grupper',
'listgrouprights-removegroup-all'      => 'Kan fjerne alle grupper',
'listgrouprights-addgroup-self'        => 'Kan tilføje {{PLURAL:$2|gruppe|grupper}} til egen konto: $1',
'listgrouprights-removegroup-self'     => 'Kan fjerne {{PLURAL:$2|gruppe|grupper}} fra egen konto: $1',
'listgrouprights-addgroup-self-all'    => 'Kan tilføje alle grupper til egen konto',
'listgrouprights-removegroup-self-all' => 'Kan fjerne alle grupper fra egen konto',

# E-mail user
'mailnologin'          => 'Du er ikke logget på',
'mailnologintext'      => 'Du skal være [[Special:UserLogin|logget på]] og have en gyldig e-mailadresse sat i dine [[Special:Preferences|indstillinger]] for at sende e-mail til andre brugere.',
'emailuser'            => 'E-mail til denne bruger',
'emailpage'            => 'E-mail bruger',
'emailpagetext'        => 'Du kan bruge formularen nedenfor til at sende en e-mail til denne bruger.
Den e-mail-adresse du har angivet i [[Special:Preferences|dine indstillinger]] vil dukke op i "fra"-feltet på e-mailen, så modtageren kan svare dig.',
'usermailererror'      => 'E-mail-modulet returnerede en fejl:',
'defemailsubject'      => 'E-mail fra {{SITENAME}}',
'usermaildisabled'     => 'Bruger-e-mail deaktiveret',
'usermaildisabledtext' => 'Du kan ikke sende e-mails til andre brugere på denne wiki',
'noemailtitle'         => 'Ingen e-mail-adresse',
'noemailtext'          => 'Denne bruger har ikke angivet en gyldig e-mail-adresse.',
'nowikiemailtitle'     => 'E-mail er ikke tilladt',
'nowikiemailtext'      => 'Denne bruger har valgt ikke at modtage e-mail fra andre brugere.',
'email-legend'         => 'Send e-post til en anden {{SITENAME}}-bruger.',
'emailfrom'            => 'Fra:',
'emailto'              => 'Til:',
'emailsubject'         => 'Emne:',
'emailmessage'         => 'Besked:',
'emailsend'            => 'Send',
'emailccme'            => 'Send en kopi af denne e-mail til mig',
'emailccsubject'       => 'Kopi sendes til $1: $2',
'emailsent'            => 'E-mail sendt',
'emailsenttext'        => 'Din e-mail er blevet sendt.',
'emailuserfooter'      => 'Denne e-mail er sendt af $1 til $2 ved hjælp af funktionen "E-mail til denne bruger" på {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'Efterlader system besked.',
'usermessage-editor'  => 'System messenger',

# Watchlist
'watchlist'            => 'Overvågningsliste',
'mywatchlist'          => 'Min overvågningsliste',
'watchlistfor'         => "(for '''$1''')",
'nowatchlist'          => 'Du har ingenting i din overvågningsliste.',
'watchlistanontext'    => 'Du skal $1, for at se din overvågningsliste eller ændre indholdet af den.',
'watchnologin'         => 'Ikke logget på',
'watchnologintext'     => 'Du skal være [[Special:UserLogin|logget på]] for at kunne ændre din overvågningsliste.',
'addedwatch'           => 'Tilføjet til din overvågningsliste',
'addedwatchtext'       => "Siden \"[[:\$1]]\" er blevet tilføjet til din [[Special:Watchlist|overvågningsliste]]. Fremtidige ændringer til denne side og den tilhørende diskussionsside vil blive listet der, og siden vil fremstå '''fremhævet''' i [[Special:RecentChanges|listen med de seneste ændringer]] for at gøre det lettere at finde den. Hvis du senere vil fjerne siden fra din overvågningsliste, så klik \"Fjern overvågning\".",
'removedwatch'         => 'Fjernet fra overvågningsliste',
'removedwatchtext'     => 'Siden "[[:$1]]" er blevet fjernet fra [[Special:Watchlist||din overvågningsliste]].',
'watch'                => 'Overvåg',
'watchthispage'        => 'Overvåg side',
'unwatch'              => 'Fjern overvågning',
'unwatchthispage'      => 'Fjern overvågning',
'notanarticle'         => 'Ikke en artikel',
'notvisiblerev'        => 'Versionen er blevet slettet',
'watchnochange'        => 'Ingen af siderne i din overvågningsliste er ændret i den valgte periode.',
'watchlist-details'    => 'Du har $1 {{PLURAL:$1|side|sider}} på din overvågningsliste (ekskl. diskussionssider).',
'wlheader-enotif'      => '* E-mail-underretning er slået til.',
'wlheader-showupdated' => "* Sider der er ændret siden dit sidste besøg, er '''fremhævet'''",
'watchmethod-recent'   => 'Tjekker seneste ændringer for sider i din overvågningsliste',
'watchmethod-list'     => 'Tjekker seneste ændringer for sider i din overvågningsliste',
'watchlistcontains'    => 'Din overvågningsliste indeholder $1 {{PLURAL:$1|side|sider}}.',
'iteminvalidname'      => "Problem med '$1', ugyldigt navn...",
'wlnote'               => "Nedenfor ses de seneste $1 {{PLURAL:$1|ændring|ændringer}} i {{PLURAL:$2|den sidste time|'''de sidste $2 timer}}'''.",
'wlshowlast'           => 'Vis de seneste $1 timer $2 dage $3',
'watchlist-options'    => 'Indstillinger for overvågningslisten',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Tilføjer overvågning …',
'unwatching' => 'Fjerner overvågning …',

'enotif_mailer'                => '{{SITENAME}} informationsmail',
'enotif_reset'                 => 'Marker alle sider som besøgt',
'enotif_newpagetext'           => 'Dette er en ny side.',
'enotif_impersonal_salutation' => '{{SITENAME}} bruger',
'changed'                      => 'ændret',
'created'                      => 'oprettet',
'enotif_subject'               => '{{SITENAME}}-siden $PAGETITLE er blevet ændret af $PAGEEDITOR',
'enotif_lastvisited'           => 'Se $1 for alle ændringer siden dit sidste besøg.',
'enotif_lastdiff'              => 'Se $1 for at vise denne ændring.',
'enotif_anon_editor'           => 'anonym bruger $1',
'enotif_body'                  => 'Kære $WATCHINGUSERNAME,

{{SITENAME}}-siden $PAGETITLE er blevet $CHANGEDORCREATED den $PAGEEDITDATE af $PAGEEDITOR, se $PAGETITLE_URL for den aktuelle version.

$NEWPAGE

Bidragyderens opsummering: $PAGESUMMARY $PAGEMINOREDIT

Kontakt bidragyderen:
e-mail: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Du vil ikke modtage flere beskeder om yderligere ændringer medmindre du besøger denne side.
På din overvågningsliste kan du også nulstille alle markeringer på de sider, du overvåger.

             Med venlig hilsen {{SITENAME}}s informationssystem

-- 
For at ændre indstillingerne for din overvågningsliste, besøg
{{fullurl:{{#special:Watchlist}}/edit}}

For at slette siden fra din overvågningsliste, besøg
$UNWATCHURL

Tilbagemelding og yderligere hjælp:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Slet side',
'confirm'                => 'Bekræft',
'excontent'              => "indholdet var: '$1'",
'excontentauthor'        => "indholdet var: '$1' (og den eneste forfatter var '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'          => "indholdet før siden blev tømt var: '$1'",
'exblank'                => 'siden var tom',
'delete-confirm'         => 'Slet "$1"',
'delete-legend'          => 'Slet',
'historywarning'         => 'Advarsel: Siden du er ved at slette har en historie med $1 {{PLURAL:$1|revision|revisioner}}:',
'confirmdeletetext'      => 'Du er ved at slette en side sammen med hele dens tilhørende historik.
Bekræft venligst at du virkelig vil gøre dette, at du forstår konsekvenserne, og at du gør det i overensstemmelse med [[{{MediaWiki:Policy-url}}|retningslinjerne]].',
'actioncomplete'         => 'Gennemført',
'actionfailed'           => 'Handlingen mislykkedes',
'deletedtext'            => '"$1" er slettet. Se $2 for en fortegnelse over de nyeste sletninger.',
'deletedarticle'         => 'slettede "$1"',
'suppressedarticle'      => 'skjulte "[[$1]]"',
'dellogpage'             => 'Sletningslog',
'dellogpagetext'         => 'Herunder vises de nyeste sletninger. Alle tider er serverens tid.',
'deletionlog'            => 'sletningslog',
'reverted'               => 'Gendannet en tidligere version',
'deletecomment'          => 'Begrundelse:',
'deleteotherreason'      => 'Anden/uddybende begrundelse:',
'deletereasonotherlist'  => 'Anden begrundelse',
'deletereason-dropdown'  => '
*Hyppige sletningsårsager
** Efter forfatters ønske
** Overtrædelse af ophavsret
** Hærværk',
'delete-edit-reasonlist' => 'Rediger sletningsårsagerrette',
'delete-toobig'          => 'Denne side har en stor historik, over {{PLURAL:$1|en version|$1 versioner}}. Sletning af sådanne sider er begrænset blevet for at forhindre utilsigtet forstyrrelse af {{SITENAME}}.',
'delete-warning-toobig'  => 'Denne side har en stor historik, over {{PLURAL:$1|en version|$1 versioner}} versioner, slettes den kan det forstyrre driften af {{SITENAME}}, gå forsigtigt frem.',

# Rollback
'rollback'          => 'Fjern redigeringer',
'rollback_short'    => 'Fjern redigering',
'rollbacklink'      => 'rul tilbage',
'rollbackfailed'    => 'Kunne ikke fjerne redigeringen',
'cantrollback'      => 'Kan ikke fjerne redigering; den sidste bruger er den eneste forfatter.',
'alreadyrolled'     => 'Kan ikke fjerne den seneste redigering af [[:$1]] foretaget af [[User:$2|$2]] ([[User talk:$2|diskussion]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]);
en anden har allerede redigeret siden eller fjernet redigeringen.

Den seneste redigering er foretaget af [[User:$3|$3]] ([[User talk:$3|diskussion]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "Redigeringsbeskrivelsen var: \"''\$1''\".",
'revertpage'        => 'Gendannelse til seneste version ved [[User:$1|$1]], fjerner ændringer fra [[Special:Contributions/$2|$2]] ([[User talk:$2|diskussion]])',
'revertpage-nouser' => 'Gendannet til seneste version af [[User:$1|$1]], ændringer fra (brugernavn fjernet) fjernet',
'rollback-success'  => 'Ændringerne fra $1 er fjernet,
og den seneste version af $2 er gendannet.',

# Edit tokens
'sessionfailure-title' => 'Sessionsfejl',
'sessionfailure'       => 'Der lader til at være et problem med din loginsession; denne handling blev annulleret som en sikkerhedsforanstaltning mod kapring af sessionen. Tryk på "tilbage"-knappen og genindlæs den side du kom fra, og prøv dernæst igen.',

# Protect
'protectlogpage'              => 'Skrivebeskyttelseslog',
'protectlogtext'              => 'Herunder er en liste med sider der er blevet beskyttet/har fået fjernet beskyttelsen.',
'protectedarticle'            => '[[$1]] beskyttet',
'modifiedarticleprotection'   => 'ændrede beskyttelsen af "[[$1]]"',
'unprotectedarticle'          => 'fjernet beskyttelse af [[$1]]',
'movedarticleprotection'      => 'flyttede beskyttelsesindstillinger fra "[[$2]]" til "[[$1]]"',
'protect-title'               => 'Ændr beskyttelse af "$1"',
'prot_1movedto2'              => '$1 flyttet til $2',
'protect-legend'              => 'Bekræft beskyttelse',
'protectcomment'              => 'Begrundelse:',
'protectexpiry'               => 'Udløb',
'protect_expiry_invalid'      => 'Udløbstiden er ugyldig.',
'protect_expiry_old'          => 'Udløbstiden ligger i fortiden.',
'protect-unchain-permissions' => 'Lås yderligere beskyttelsesindstillinger op',
'protect-text'                => "Her kan beskyttelsesstatus for siden '''$1''' ses og ændres.",
'protect-locked-blocked'      => "Du kan ikke ændre sidens status, da din brugerkonto er spærret. Her er de aktuelle beskyttelsesindstillinger for siden '''„$1“:'''",
'protect-locked-dblock'       => "Databasen er spærret, sidebeskyttelsen kan derfor ikke ændres. her er de aktuelle beskyttelsesindstillinger for siden '''„$1“:'''",
'protect-locked-access'       => "Den brugerkonto har ikke de nødvendige rettigheder til at ændre sidebeskyttelsen. Her er de aktuelle beskyttelsesindstillinger for siden '''„$1“:'''",
'protect-cascadeon'           => 'Denne side er del af en nedarvet skrivebeskyttelse. Wen er indeholdt i nedenstående {{PLURAL:$1|side|sider}}, som er skrivebeskyttet med tilvalg af "nedarvende sidebeskyttelse" Sidebeskyttelsen kan ændres for denne side, det påvirker dog ikke kaskadespærringen:',
'protect-default'             => 'Tillad alle brugere',
'protect-fallback'            => 'Kræver "$1"-tilladelse',
'protect-level-autoconfirmed' => 'Spær for nye og uregistrerede brugere',
'protect-level-sysop'         => 'Kun administratorer',
'protect-summary-cascade'     => 'nedarvende',
'protect-expiring'            => 'til $1 (UTC)',
'protect-expiry-indefinite'   => 'på ubestemt tid',
'protect-cascade'             => 'Beskyt sider som er inkluderet på denne side (nedarvende beskyttelse)',
'protect-cantedit'            => 'Du kan ikke ændre beskyttelsesniveau for denne side, da du ikke har tilladelse til at redigere den.',
'protect-othertime'           => 'Anden tid:',
'protect-othertime-op'        => 'anden tid',
'protect-existing-expiry'     => 'Nugældende udløbstid: $2 $3',
'protect-otherreason'         => 'Anden/uddybende årsag:',
'protect-otherreason-op'      => 'Anden årsag',
'protect-dropdown'            => '* Almindelge besyttelsesårsager
** Gentagen hærværk
** Gentagen spam
** Redigeringskrig
** Side med mange besøgende',
'protect-edit-reasonlist'     => 'Rediger beskyttelsesårsager',
'protect-expiry-options'      => '1 time:1 hour,1 dag:1 day,1 uge:1 week,2 uger:2 weeks,1 måned:1 month,3 måneder:3 months,6 måneder:6 months,1 år:1 year,ubegrænset:infinite',
'restriction-type'            => 'Beskyttelsesstatus',
'restriction-level'           => 'Beskyttelsesniveau:',
'minimum-size'                => 'Mindste størrelse',
'maximum-size'                => 'Største størrelse',
'pagesize'                    => '(bytes)',

# Restrictions (nouns)
'restriction-edit'   => 'ændre',
'restriction-move'   => 'flytte',
'restriction-create' => 'oprette',
'restriction-upload' => 'oplægge',

# Restriction levels
'restriction-level-sysop'         => 'fuldt beskyttet',
'restriction-level-autoconfirmed' => 'delvist beskyttet',
'restriction-level-all'           => 'alle niveauer',

# Undelete
'undelete'                     => 'Gendan en slettet side',
'undeletepage'                 => 'Se og gendan slettede sider',
'undeletepagetitle'            => "'''Slettede versioner af [[:$1|$1]]'''.",
'viewdeletedpage'              => 'Vis slettede sider',
'undeletepagetext'             => '{{PLURAL:$1|Den følgende side er slettet, men den|De følgende sider er slettede, men de}} findes fortsat i arkivet og kan gendannes. Arkivet bliver periodevis slettet.',
'undelete-fieldset-title'      => 'Gendan versioner',
'undeleteextrahelp'            => "For at gendanne siden komplet med alle versioner skal du lade alle afkrydsningsfelterne forblive tomme og klikke på '''''Gendan'''''.
For at gendanne bestemte versioner, vælges disse enkeltvis i afkrydsningsfelterne før der klikkes '''''Gendan'''''.
Et klik på '''''Afbryd''''' tømmer kommentarfeltet og fjerner alle markeringer ved versionerne.",
'undeleterevisions'            => '$1 {{PLURAL:$1|revision|revisioner}} arkiveret',
'undeletehistory'              => 'Hvis du gendanner siden, vil alle tidligere versioner blive gendannet i historikken.
Hvis en ny side med det samme navn er oprettet efter sletningen, så vil de gendannede versioner dukke op i historikken.',
'undeleterevdel'               => 'Gendannelsen gennemføres ikke, når den mest aktuelle version er skjult eller indeholder skjulte dele.
I dette tilfælde må den nyeste version ikke markeres eller dens status skal ændres til en normal version.
Versioner af filer, som du ikke har adgang til, gendannes ikke.',
'undeletehistorynoadmin'       => 'Denne side er blevet slettet.
Årsagen til sletningen er angivet i resuméet nedenfor
sammen med oplysninger om brugerne der har redigeret denne side før sletningen.
Teksten i de slettede versioner er kun tilgængelig for administratorer.',
'undelete-revision'            => 'Slettet version af $1 (fra $4 $5) af $3:',
'undeleterevision-missing'     => 'Ugyldig eller manglende version. Enten er henvisningen forkert eller versionen blev fjernet eller gendannet fra arkivet.',
'undelete-nodiff'              => 'Der findes ingen tidligere version.',
'undeletebtn'                  => 'Gendan',
'undeletelink'                 => 'vis/gendan',
'undeleteviewlink'             => 'vis',
'undeletereset'                => 'Afbryd',
'undeleteinvert'               => 'Omvend valget af versioner',
'undeletecomment'              => 'Begrundelse:',
'undeletedarticle'             => 'gendannede "$1"',
'undeletedrevisions'           => '$1 {{PLURAL:$1|version|versioner}} gendannet',
'undeletedrevisions-files'     => '$1 {{plural:$1|version|versioner}} og $2 {{plural:$2|fil|filer}} gendannet',
'undeletedfiles'               => '$1 {{plural:$1|fil|filer}} gendannet',
'cannotundelete'               => 'Gendannelse mislykkedes; en anden har allerede gendannet siden.',
'undeletedpage'                => "'''$1''' blev gendannet.

I [[Special:Log/delete|slette-loggen]] findes en oversigt over de nyligt slettede og gendannede sider.",
'undelete-header'              => 'Se [[Special:Log/delete|slette-loggen]] for nyligt slettede og gendannede sider.',
'undelete-search-box'          => 'Søg efter slettede sider',
'undelete-search-prefix'       => 'Vis sider som starter med:',
'undelete-search-submit'       => 'Søg',
'undelete-no-results'          => 'Der blev ikke fundet en passende side i arkivet.',
'undelete-filename-mismatch'   => 'Kan ikke gendanne filen med tidsstempel $1: forkert filnavn',
'undelete-bad-store-key'       => 'Kan ikke gendanne filen med tidsstempel $1: file fandtes ikke da den blev slettet',
'undelete-cleanup-error'       => 'Fejl under sletning af ubrugt arkiveret version "$1".',
'undelete-missing-filearchive' => 'Kunne ikke genskabe arkiveret fil med ID $1 fordi den ikke findes i databasen. Måske er den allerede gendannet.',
'undelete-error-short'         => 'Fejl under gendannelsen af fil: $1',
'undelete-error-long'          => 'Der opstod en fejl under gendannelsen af filen:

$1',
'undelete-show-file-confirm'   => 'Er du sikker på at du vil vise en slettet version af filen "<nowiki>$1</nowiki>" fra $2 $3?',
'undelete-show-file-submit'    => 'Ja',

# Namespace form on various pages
'namespace'      => 'Navnerum:',
'invert'         => 'Udeluk valgt navnerum',
'blanknamespace' => '(Hoved)',

# Contributions
'contributions'       => 'Brugerbidrag',
'contributions-title' => 'Brugerbidrag for $1',
'mycontris'           => 'Mine bidrag',
'contribsub2'         => 'For $1 ($2)',
'nocontribs'          => 'Ingen ændringer er fundet som opfylder disse kriterier.',
'uctop'               => ' (seneste)',
'month'               => 'Måned:',
'year'                => 'År:',

'sp-contributions-newbies'             => 'Vis kun bidrag fra nye brugere',
'sp-contributions-newbies-sub'         => 'Fra nye kontoer',
'sp-contributions-newbies-title'       => 'Brugerbidrag fra nye kontoer',
'sp-contributions-blocklog'            => 'blokeringslog',
'sp-contributions-deleted'             => 'slettede brugerbidrag',
'sp-contributions-logs'                => 'loglister',
'sp-contributions-talk'                => 'diskussion',
'sp-contributions-userrights'          => 'håndtering af brugerrettigheder',
'sp-contributions-blocked-notice'      => 'Denne bruger er i øjeblikket blokeret. Loggen over den seneste blokering kan ses nedenfor:',
'sp-contributions-blocked-notice-anon' => 'Denne IP-adresse er i øjeblikket blokeret.
Den seneste post i blokeringsloggen vises nedenfor:',
'sp-contributions-search'              => 'Søg efter bidrag',
'sp-contributions-username'            => 'IP-adresse eller brugernavn:',
'sp-contributions-toponly'             => 'Vis kun redigeringer, der er aktuelle versioner',
'sp-contributions-submit'              => 'Søg',

# What links here
'whatlinkshere'            => 'Hvad henviser hertil',
'whatlinkshere-title'      => 'Sider der linker til "$1"',
'whatlinkshere-page'       => 'Side:',
'linkshere'                => "De følgende sider henviser til '''„[[:$1]]“''':",
'nolinkshere'              => "Ingen sider henviser til '''„[[:$1]]“'''.",
'nolinkshere-ns'           => "Ingen side henviser til '''„[[:$1]]“''' i det valgte navnerum.",
'isredirect'               => 'omdirigeringsside',
'istemplate'               => 'indlejring',
'isimage'                  => 'filhenvisning',
'whatlinkshere-prev'       => '{{PLURAL:$1|forrige|forrige $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|næste|næste $1}}',
'whatlinkshere-links'      => '← henvisninger',
'whatlinkshere-hideredirs' => '$1 omdirigeringer',
'whatlinkshere-hidetrans'  => '$1 inkluderinger',
'whatlinkshere-hidelinks'  => '$1 henvisninger',
'whatlinkshere-hideimages' => '$1 fillinks',
'whatlinkshere-filters'    => 'Filtre',

# Block/unblock
'blockip'                         => 'Bloker bruger',
'blockip-title'                   => 'Bloker bruger',
'blockip-legend'                  => 'Bloker bruger',
'blockiptext'                     => 'Brug formularen herunder til at blokere for skriveadgang fra en bestemt IP-adresse eller et brugernavn.
Dette bør kun gøres for at forhindre vandalisme og skal være i overensstemmelse med [[{{MediaWiki:Policy-url}}|retningslinjerne]].
Angiv en konkret begrundelse herunder (for eksempel med angivelse af sider der har været udsat for vandalisme).',
'ipaddress'                       => 'IP-adresse/brugernavn',
'ipadressorusername'              => 'IP-adresse eller brugernavn',
'ipbexpiry'                       => 'varighed',
'ipbreason'                       => 'Begrundelse:',
'ipbreasonotherlist'              => 'Anden begrundelse',
'ipbreason-dropdown'              => '*Generelle begrundelser
** Indsættelse af forkerte oplysninger
** Fjernelse af korrekt indhold
** Linkspam
** Indsættelse af vås/nonsens
** Utilstedelig opførsel
** Misbrug af flere brugerkonti
** Uacceptabelt brugernavn',
'ipbanononly'                     => 'Kun anonyme brugere spærres',
'ipbcreateaccount'                => 'Forhindr oprettelse af brugerkontoer',
'ipbemailban'                     => 'Spær brugerens adgang til at sende e-mail',
'ipbenableautoblock'              => 'Spærre den IP-adresse, der bruges af denne bruger samt automatisk alle følgende, hvorfra han foretager ændringer eller forsøger at anlægge brugerkonti',
'ipbsubmit'                       => 'Bloker denne bruger',
'ipbother'                        => 'Anden varighed (engelsk)',
'ipboptions'                      => '2 timer:2 hours,1 dag:1 day,3 dage:3 days,1 uge:1 week,2 uger:2 weeks,1 måned:1 month,3 måneder:3 months,6 måneder:6 months,1 år:1 year,ubegrænset:infinite',
'ipbotheroption'                  => 'Anden varighed',
'ipbotherreason'                  => 'Anden/uddybende begrundelse',
'ipbhidename'                     => 'Skjul brugernavnet i redigeringer og lister',
'ipbwatchuser'                    => 'Overvåg denne brugers brugerside og diskussionsside.',
'ipballowusertalk'                => 'Tillad brugeren at redigere sin egen diskussionsside under blokeringen',
'ipb-change-block'                => 'Forny brugerens blokering med disse indstillinger',
'badipaddress'                    => 'IP-adressen/brugernavnet er udformet forkert eller eksistere ikke.',
'blockipsuccesssub'               => 'Blokeringen er gennemført.',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] er blevet blokeret.<br />
Se [[Special:IPBlockList|IP-blokeringslisten]] for alle blokeringer.',
'ipb-edit-dropdown'               => 'Ændre spærreårsager',
'ipb-unblock-addr'                => 'Ophæv blokeringen af "$1"',
'ipb-unblock'                     => 'Frigive IP-adresse/bruger',
'ipb-blocklist-addr'              => 'Aktuelle blokeringer af "$1"',
'ipb-blocklist'                   => 'Vis alle blokeringer som er i kraft',
'ipb-blocklist-contribs'          => 'Bidrag fra $1',
'unblockip'                       => 'Ophæv blokering af bruger',
'unblockiptext'                   => 'Brug formularen herunder for at gendanne skriveadgangen for en tidligere blokeret IP-adresse eller bruger.',
'ipusubmit'                       => 'Ophæv blokeringen',
'unblocked'                       => '[[User:$1|$1]] blev frigivet',
'unblocked-id'                    => 'Blokering $1 er blevet fjernet',
'ipblocklist'                     => 'Blokerede IP-adresser og brugernavne',
'ipblocklist-legend'              => 'Find en blokeret bruger',
'ipblocklist-username'            => 'Brugernavn eller IP-adresse:',
'ipblocklist-sh-userblocks'       => '$1 blokeringer af kontoer',
'ipblocklist-sh-tempblocks'       => '$1 midlertidige blokeringer',
'ipblocklist-sh-addressblocks'    => '$1 blokeringer af individuelle IP-adresser',
'ipblocklist-submit'              => 'Søg',
'ipblocklist-localblock'          => 'Lokal blokering',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|Øvrig blokering|Øvrige blokeringer}}',
'blocklistline'                   => '$1, $2 blokerede $3 ($4)',
'infiniteblock'                   => 'udløber infinite',
'expiringblock'                   => 'udløber $1 $2',
'anononlyblock'                   => 'kun anonyme',
'noautoblockblock'                => 'Autoblok deaktiveret',
'createaccountblock'              => 'Oprettelse af brugerkonti spærret',
'emailblock'                      => 'e-mail blokeret',
'blocklist-nousertalk'            => 'kan ikke redigere egen diskussionsside',
'ipblocklist-empty'               => 'Blokeringslisten er tom.',
'ipblocklist-no-results'          => 'Den angivene IP-addresse eller brugernavn er ikke blokeret.',
'blocklink'                       => 'bloker',
'unblocklink'                     => 'ophæv blokering',
'change-blocklink'                => 'ændr blokering',
'contribslink'                    => 'bidrag',
'autoblocker'                     => 'Du er automatisk blokeret, fordi du deler IP-adresse med "[[User:$1|$1]]". 
Begrundelse: "$2".',
'blocklogpage'                    => 'Blokeringslog',
'blocklog-showlog'                => 'Denne bruger er tidligere blevet blokeret.
Blokeringsloggen vises nedenfor som reference:',
'blocklog-showsuppresslog'        => 'Denne bruger har tidligere været blokeret og skjult.
Skjulningsloggen vises nedenfor som reference:',
'blocklogentry'                   => 'blokerede [[$1]] med en varighed på $2 $3',
'reblock-logentry'                => 'ændrede blokeringsindstillinger for [[$1]] med en varighed $2 $3',
'blocklogtext'                    => 'Dette er en liste med blokerede brugere og ophævede blokeringer af brugere. Automatisk blokerede IP-adresser er ikke anført her. Se [[Special:IPBlockList|blokeringslisten]] for den nuværende liste med blokerede brugere.',
'unblocklogentry'                 => 'ophævede blokering af "$1"',
'block-log-flags-anononly'        => 'kun anonyme',
'block-log-flags-nocreate'        => 'Oprettelse af brugerkonti blokeret',
'block-log-flags-noautoblock'     => 'Autoblok deaktiveret',
'block-log-flags-noemail'         => 'e-mail blokeret',
'block-log-flags-nousertalk'      => 'kan ikke redigere egen diskussionsside',
'block-log-flags-angry-autoblock' => 'udvidet automatisk blokering slået tilenhanced autoblock enabled',
'block-log-flags-hiddenname'      => 'brugernavnet er skjult',
'range_block_disabled'            => 'Sysop-muligheden for at oprette blokeringsklasser er slået fra.',
'ipb_expiry_invalid'              => 'Udløbstiden er ugyldig.',
'ipb_expiry_temp'                 => 'Brugernavnet kan kun skjules ved permanente blokeringer.',
'ipb_hide_invalid'                => 'Kan ikke skjule denne konto; den kan have for mange redigeringer.',
'ipb_already_blocked'             => '„$1“ er allerede blokeret',
'ipb-needreblock'                 => '== Allerede blokkeret ==
$1 er allerede blokkeret. Vil du ændre indstillingene?',
'ipb-otherblocks-header'          => 'Yderligere {{PLURAL:$1|blokering|blokeringer}}',
'ipb_cant_unblock'                => 'Fejl: Spærre-ID $1 ikke fundet. Spærringen er allerede ophævet.',
'ipb_blocked_as_range'            => 'Fejl: IP-adressen $1 er ikke dirkete blokeret. Derfor kan en blokering ikke ophæves. Adressen er blokeret som en del af intervallet $2. Denne blokering kan ophæves.',
'ip_range_invalid'                => 'Ugyldigt IP-interval.',
'ip_range_toolarge'               => 'Blokeringer af IP-serier større end /$1 er ikke tilladte.',
'blockme'                         => 'Bloker mig',
'proxyblocker'                    => 'Proxy-blokering',
'proxyblocker-disabled'           => 'Denne funktion er ikke i brug.',
'proxyblockreason'                => "Din IP-adresse er blevet blokeret fordi den er en såkaldt ''åben proxy''. Kontakt din Internet-udbyder eller tekniske hotline og oplyse dem om dette alvorlige sikkerhedsproblem.",
'proxyblocksuccess'               => 'Færdig.',
'sorbsreason'                     => 'IP-adressen er opført i DNSBL på {{SITENAME}} som åben PROXY.',
'sorbs_create_account_reason'     => 'IP-adressen er opført i DNSBL på {{SITENAME}} som åben PROXY. Oprettelse af nye brugere er ikke mulig.',
'cant-block-while-blocked'        => 'Du kan ikke blokkere andre brugere mens du selv er blokkeret.',
'cant-see-hidden-user'            => 'Brugeren du prøver at blokerer er allerede blokeret og skjult. Siden du ikke har rettigheden til at skjule brugere, kan du ikke se eller ændre brugerens blokering.',
'ipbblocked'                      => 'Du kan ikke ændre blokeringer, da du selv er blokeret',
'ipbnounblockself'                => 'Du kan ikke ophæve blokeringen af dig selv',

# Developer tools
'lockdb'              => 'Lås database',
'unlockdb'            => 'Lås database op',
'lockdbtext'          => 'At låse databasen vil forhindre alle brugere i at kunne redigere sider, ændre indstillinger, redigere overvågningslister og andre ting der kræver ændringer i databasen. Bekræft venligst at du har til hensigt at gøre dette, og at du vil låse databasen op, når din vedligeholdelse er overstået.',
'unlockdbtext'        => 'At låse databasen op vil gøre, at alle brugere igen kan redigere sider, ændre deres indstillinger, redigere deres overvågningsliste, og andre ting der kræver ændringer i databasen. Bekræft venligst at du har til hensigt at gøre dette.',
'lockconfirm'         => 'Ja, jeg vil virkelig låse databasen.',
'unlockconfirm'       => 'Ja, jeg vil virkelig låse databasen op.',
'lockbtn'             => 'Lås databasen',
'unlockbtn'           => 'Lås databasen op',
'locknoconfirm'       => 'Du har ikke bekræftet handlingen.',
'lockdbsuccesssub'    => 'Databasen er nu låst',
'unlockdbsuccesssub'  => 'Databasen er nu låst op',
'lockdbsuccesstext'   => 'Mediawikidatabasen er låst. <br />Husk at fjerne låsen når du er færdig med din vedligeholdelse.',
'unlockdbsuccesstext' => 'Mediawikidatabasen er låst op.',
'lockfilenotwritable' => 'Database-spærrefilen kan ikke ændres. Hvis databasen skal spærres eller frigives, skal webserveren kunne skrive i denne fil.',
'databasenotlocked'   => 'Databasen er ikke spærret.',

# Move page
'move-page'                    => 'Flyt $1',
'move-page-legend'             => 'Flyt side',
'movepagetext'                 => "Når du bruger formularen herunder vil du få omdøbt en side og flyttet hele sidens historie til det nye navn. Den gamle titel vil blive en omdirigeringsside til den nye titel. Henvisninger til den gamle titel vil ikke blive ændret. Sørg for at tjekke for dobbelte eller dårlige omdirigeringer. Du er ansvarlig for, at alle henvisninger stadig peger derhen, hvor det er meningen de skal pege. Bemærk at siden '''ikke''' kan flyttes hvis der allerede er en side med den nye titel, medmindre den side er tom eller er en omdirigering uden nogen historie. Det betyder at du kan flytte en side tilbage hvor den kom fra, hvis du kommer til at lave en fejl. <b>ADVARSEL!</b> Dette kan være en drastisk og uventet ændring for en populær side; vær sikker på, at du forstår konsekvenserne af dette før du fortsætter.",
'movepagetalktext'             => "Den tilhørende diskussionsside, hvis der er en, vil automatisk blive flyttet med siden '''medmindre:''' *Du flytter siden til et andet navnerum,
*En ikke-tom diskussionsside allerede eksisterer under det nye navn, eller
*Du fjerner markeringen i boksen nedenunder.

I disse tilfælde er du nødt til at flytte eller sammenflette siden manuelt.",
'movearticle'                  => 'Flyt side',
'moveuserpage-warning'         => "'''Advarsel:''' Du er ved at flytte en brugerside. Bemærk at det kun er siden, der vil blive flyttet – brugeren bliver ''ikke'' omdøbt.",
'movenologin'                  => 'Ikke logget på',
'movenologintext'              => 'Du skal være registreret bruger og [[Special:UserLogin|logget på]] for at flytte en side.',
'movenotallowed'               => 'Du har ikke rettigheder til at flytte sider.',
'movenotallowedfile'           => 'Du har ikke tilladelse til at flytte filer.',
'cant-move-user-page'          => 'Du har ikke tilladelse til at flytte brugersider (bortset fra undersider).',
'cant-move-to-user-page'       => 'Du har ikke tilladelse til at flytte brugersider (bortset fra til brugerundersider).',
'newtitle'                     => 'Til ny titel',
'move-watch'                   => 'Overvåg siden',
'movepagebtn'                  => 'Flyt side',
'pagemovedsub'                 => 'Flytning gennemført',
'movepage-moved'               => 'Siden \'\'\'"$1" er flyttet til "$2"\'\'\'',
'movepage-moved-redirect'      => 'En omdirigering er oprettet.',
'movepage-moved-noredirect'    => 'Der oprettedes ikke en omdirigering.',
'articleexists'                => 'En side med det navn eksisterer allerede, eller det
navn du har valgt er ikke gyldigt. Vælg et andet navn.',
'cantmove-titleprotected'      => 'Du kan ikke omdøbe en side til dette navn. Det nye navn er beskyttet mod oprettelse.',
'talkexists'                   => 'Siden blev flyttet korrekt, men den tilhørende diskussionsside kunne ikke flyttes, fordi der allerede eksisterer en med den nye titel. Du er nødt til at flette dem sammen manuelt.',
'movedto'                      => 'flyttet til',
'movetalk'                     => 'Flyt også "diskussionssiden", hvis den eksisterer.',
'move-subpages'                => 'Flyt undersider (op til $1)',
'move-talk-subpages'           => 'Flyt diskussionssidens undersider (op til $1)',
'movepage-page-exists'         => 'Siden $1 findes allerede og kan ikke overskrives automatisk.',
'movepage-page-moved'          => 'Siden $1 er flyttet til $2.',
'movepage-page-unmoved'        => 'Siden $1 kan ikke flyttes til $2.',
'movepage-max-pages'           => 'Grænsen på $1 {{PLURAL:$1|sideflytning|sideflytninger}} er nået. De resterende sider vil ikke blive flyttet automatisk.',
'1movedto2'                    => '[[$1]] flyttet til [[$2]]',
'1movedto2_redir'              => '[[$1]] flyttet til [[$2]] over en omdirigering',
'move-redirect-suppressed'     => 'en omdirigering oprettes ikke',
'movelogpage'                  => 'Flyttelog',
'movelogpagetext'              => 'Nedenfor vises flyttede sider.',
'movesubpage'                  => '{{PLURAL:$1|Underside|Undersider}}',
'movesubpagetext'              => 'Denne side har $1 {{PLURAL:$1|underside|undersider}} som vises nedenfor.',
'movenosubpage'                => 'Denne side har ingen undersider.',
'movereason'                   => 'Begrundelse:',
'revertmove'                   => 'gendan',
'delete_and_move'              => 'Slet og flyt',
'delete_and_move_text'         => '==Sletning nødvendig==

Artiklen "[[:$1]]" eksisterer allerede. Vil du slette den for at gøre plads til flytningen?',
'delete_and_move_confirm'      => 'Ja, slet siden',
'delete_and_move_reason'       => 'Slettet på grund af flytning',
'selfmove'                     => 'Begge sider har samme navn. Man kan ikke flytte en side oven i sig selv.',
'immobile-source-namespace'    => 'Kan ikke flytte sider i navnerummet "$1"',
'immobile-target-namespace'    => 'Kan ikke flytte sider til navnerummet "$1"',
'immobile-target-namespace-iw' => 'En side kan ikke flyttes til en interwiki-henvisning.',
'immobile-source-page'         => 'Denne side kan ikke flyttes.',
'immobile-target-page'         => 'Kan ikke flytte til det navn.',
'imagenocrossnamespace'        => 'Filer kan ikke flyttes til et navnerum der ikke indeholder filer',
'imagetypemismatch'            => 'Den nye filendelse passer ikke til filtypen',
'imageinvalidfilename'         => 'Destinationsnavnet er ugyldigt',
'fix-double-redirects'         => 'Opdater henvisninger til det oprindelige navn',
'move-leave-redirect'          => 'Efterlad en omdirigering',
'protectedpagemovewarning'     => "'''Bemærk:''' Denne side er låst så kun administratorer kan flytte den.<br />
Den seneste logpost vises nedenfor:",
'semiprotectedpagemovewarning' => "'''Bemærk:''' Denne side er låst så kun registrerede brugere kan flytte den.<br />
Den seneste logpost vises nedenfor:",
'move-over-sharedrepo'         => '== Fil findes ==
[[:$1]] findes på en delt kilde. Ved at flytte en fil til denne titel vil overskrive den eksisterende delte fil.',
'file-exists-sharedrepo'       => 'Det valgte filnavn er allerede i brug på en delt kilde.
Vælg venligst et andet navn.',

# Export
'export'            => 'Eksportér sider',
'exporttext'        => 'Du kan eksportere teksten og historikken fra en eller flere sider i et simpelt XML-format.
Dette kan så importeres i en anden wiki der bruger MediaWiki-softwaren ved hjælp af [[Special:Import|importsiden]].

For at eksportere sider skal du skrive sidetitlerne i tekstboksen herunder med én titel per linje, og vælge om du vil have alle versioner i historikken eller kun den seneste version.

Hvis du kun vil have den seneste version, kan du også bruge en henvisning, for eksempel [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] for siden "[[{{MediaWiki:Mainpage}}]]".',
'exportcuronly'     => 'Eksportér kun den nuværende version, ikke hele historikken',
'exportnohistory'   => "---- '''Bemærk:''' Eksporten af en komplet versionshistorik er pga. ydelsesårsager pt. ikke mulig.",
'export-submit'     => 'Eksporter',
'export-addcattext' => 'Tilføj sider fra kategori:',
'export-addcat'     => 'Tilføj',
'export-addnstext'  => 'Tilføj sider fra navnerum:',
'export-addns'      => 'Tilføj',
'export-download'   => 'Tilbyd at gemme som en fil',
'export-templates'  => 'Medtag skabeloner',
'export-pagelinks'  => 'Inkluder henviste sider til en dybde på:',

# Namespace 8 related
'allmessages'                   => 'Alle beskeder',
'allmessagesname'               => 'Navn',
'allmessagesdefault'            => 'Standardtekst',
'allmessagescurrent'            => 'Nuværende tekst',
'allmessagestext'               => 'Dette er en liste med alle systembeskeder i MediaWiki-navnerummet.
Besøg venligst [http://www.mediawiki.org/wiki/Localisation MediaWiki-lokalisering] hvis du ønsker at bidrage til den generelle lokalisering (oversættelse og andre lokale tilpasninger) af MediaWiki.',
'allmessagesnotsupportedDB'     => '{{ns:special}}:AllMessages ikke understøttet fordi wgUseDatabaseMessages er slået fra.',
'allmessages-filter-legend'     => 'Filtrér',
'allmessages-filter'            => 'Filtrér efter tilpasningsstatus:',
'allmessages-filter-unmodified' => 'Uændret',
'allmessages-filter-all'        => 'Alt',
'allmessages-filter-modified'   => 'Ændret',
'allmessages-prefix'            => 'Filtrér efter forstavelse:',
'allmessages-language'          => 'Sprog:',
'allmessages-filter-submit'     => 'Udfør',

# Thumbnails
'thumbnail-more'           => 'Forstør',
'filemissing'              => 'Filen mangler',
'thumbnail_error'          => 'Fejl ved oprettelse af thumbnail: $1',
'djvu_page_error'          => 'DjVu-side udenfor sideområdet',
'djvu_no_xml'              => 'XML-data kan ikke hentes til DjVu-filen',
'thumbnail_invalid_params' => 'Ugyldige thumbnail-parametre',
'thumbnail_dest_directory' => 'Kataloget kan ikke oprettes.',
'thumbnail_image-type'     => 'Billedtypen understøttes ikke',
'thumbnail_gd-library'     => 'Ufuldstændig konfiguration af GD-biblioteket: funktionen $1 mangler',
'thumbnail_image-missing'  => 'Filen $1 ser til at mangle',

# Special:Import
'import'                     => 'Importer sider',
'importinterwiki'            => 'Importer sider fra en anden wiki',
'import-interwiki-text'      => 'Vælg en Wiki og en side til importen.
Datoen i den pågældende version og forfatterne ændres ikke.
Alle Transwiki import-aktioner protokolleres i [[Special:Log/import|import-loggen]].',
'import-interwiki-source'    => 'Kildewiki/-side:',
'import-interwiki-history'   => 'Importer alle versioner af denne side',
'import-interwiki-templates' => 'Inkluder alle skabeloner',
'import-interwiki-submit'    => 'Importer',
'import-interwiki-namespace' => 'Importer til navnerum:',
'import-upload-filename'     => 'Filnavn:',
'import-comment'             => 'Kommentar:',
'importtext'                 => "Eksportér filen fra kilde-wiki'en ved hjælp af værktøjet Special:Export, gem den på din harddisk og læg den op her.",
'importstart'                => 'Importerer sider …',
'import-revision-count'      => '– {{PLURAL:$1|1 version|$1 versioner}}',
'importnopages'              => 'Ingen sider fundet til import.',
'imported-log-entries'       => 'Importere $1 {{PLURAL:$1|log post|log poster}}.',
'importfailed'               => 'Importering fejlede: $1',
'importunknownsource'        => 'Ukendt fejlkilde',
'importcantopen'             => 'Importfil kunne ikke åbnes',
'importbadinterwiki'         => 'Forkert Interwiki-henvisning',
'importnotext'               => 'Tom eller ingen tekst',
'importsuccess'              => 'Importen lykkedes!',
'importhistoryconflict'      => 'Der er en konflikt i versionhistorikken (siden kan have været importeret før)',
'importnosources'            => 'Ingen transwiki importkilde defineret og direkte historikuploads er deaktiveret.',
'importnofile'               => 'Ingen importfil valgt!',
'importuploaderrorsize'      => 'Upload af importfil mislykkedes da filen er større en den tilladte maksimale uploadstørrelse.',
'importuploaderrorpartial'   => 'Upload af importfil mislykkedes da filen kun blev delvist uploadet.',
'importuploaderrortemp'      => 'Upload af importfil mislykkedes da en midlertidig mappe mangler.',
'import-parse-failure'       => 'XML fortolkningsfejl under importering',
'import-noarticle'           => 'Der er ingen sider at importere!',
'import-nonewrevisions'      => 'Alle versioner er allerede importeret.',
'xml-error-string'           => '$1 på linje $2, kolonne $3 (byte $4): $5',
'import-upload'              => 'Upload XML-data',
'import-token-mismatch'      => 'Sessionsdata er mistet. Prøv venligst igen.',
'import-invalid-interwiki'   => 'Kan ikke importere fra den angivne wiki.',

# Import log
'importlogpage'                    => 'Importlog',
'importlogpagetext'                => 'Administrativ import af sider med versionshistorik fra andre Wikis.',
'import-logentry-upload'           => '[[$1]] blev importeret',
'import-logentry-upload-detail'    => '{{PLURAL:$1|1 version|$1 versioner}}',
'import-logentry-interwiki'        => '$1 blev importeret (Transwiki)',
'import-logentry-interwiki-detail' => '{{PLURAL:$1|1 version|$1 versioner}} af $2 importeret',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Din brugerside',
'tooltip-pt-anonuserpage'         => 'Brugersiden for den ip-adresse du redigerer som',
'tooltip-pt-mytalk'               => 'Din diskussionsside',
'tooltip-pt-anontalk'             => 'Diskussion om redigeringer fra denne ip-adresse',
'tooltip-pt-preferences'          => 'Mine indstillinger',
'tooltip-pt-watchlist'            => 'Listen over sider du overvåger for ændringer.',
'tooltip-pt-mycontris'            => 'Liste over dine bidrag',
'tooltip-pt-login'                => 'Du opfordres til at logge på, men det er ikke obligatorisk.',
'tooltip-pt-anonlogin'            => 'Du opfordres til at logge på, men det er ikke obligatorisk',
'tooltip-pt-logout'               => 'Log af',
'tooltip-ca-talk'                 => 'Diskussion om indholdet på siden',
'tooltip-ca-edit'                 => 'Du kan redigere denne side. Brug venligst forhåndsvisning før du gemmer.',
'tooltip-ca-addsection'           => 'Start et nyt afsnit',
'tooltip-ca-viewsource'           => 'Denne side er beskyttet.
Du kan se på kildeteksten.',
'tooltip-ca-history'              => 'Tidligere versioner af denne side',
'tooltip-ca-protect'              => 'Beskyt denne side',
'tooltip-ca-unprotect'            => 'Fjern beskyttelsen af siden',
'tooltip-ca-delete'               => 'Slet denne side',
'tooltip-ca-undelete'             => 'Gendan de redigeringer der blev lavet på denne side før den blev slettet',
'tooltip-ca-move'                 => 'Flyt denne side',
'tooltip-ca-watch'                => 'Sæt denne side på din overvågningsliste',
'tooltip-ca-unwatch'              => 'Fjern denne side fra din overvågningsliste',
'tooltip-search'                  => 'Søg på denne wiki',
'tooltip-search-go'               => 'Vis en side med præcis dette navn, hvis den findes',
'tooltip-search-fulltext'         => 'Søg efter sider der indeholder denne tekst',
'tooltip-p-logo'                  => 'Forsiden',
'tooltip-n-mainpage'              => 'Besøg forsiden',
'tooltip-n-mainpage-description'  => 'Besøg forsiden',
'tooltip-n-portal'                => 'Om projektet, hvad du kan gøre, hvor tingene findes',
'tooltip-n-currentevents'         => 'Find baggrundsinformation om aktuelle begivenheder',
'tooltip-n-recentchanges'         => 'Listen over de seneste ændringer i wikien.',
'tooltip-n-randompage'            => 'Gå til en tilfældig artikel',
'tooltip-n-help'                  => 'Hvordan gør jeg ...',
'tooltip-t-whatlinkshere'         => 'Liste med alle sider som henviser hertil',
'tooltip-t-recentchangeslinked'   => 'Seneste ændringer i sider som denne side henviser til',
'tooltip-feed-rss'                => 'RSS-feed for denne side',
'tooltip-feed-atom'               => 'Atom-feed for denne side',
'tooltip-t-contributions'         => 'Se denne brugers bidrag',
'tooltip-t-emailuser'             => 'Send en e-mail til denne bruger',
'tooltip-t-upload'                => 'Upload et billede eller anden mediafil',
'tooltip-t-specialpages'          => 'Liste med alle specielle sider',
'tooltip-t-print'                 => 'Printervenlig udgave af denne side',
'tooltip-t-permalink'             => 'Permanent henvisning til denne version af denne side',
'tooltip-ca-nstab-main'           => 'Se indholdet',
'tooltip-ca-nstab-user'           => 'Se brugersiden',
'tooltip-ca-nstab-media'          => 'Se mediasiden',
'tooltip-ca-nstab-special'        => 'Dette er en speciel side; man kan ikke redigere sådanne sider',
'tooltip-ca-nstab-project'        => 'Vis projektsiden',
'tooltip-ca-nstab-image'          => 'Se filsiden',
'tooltip-ca-nstab-mediawiki'      => 'Se systembeskeden',
'tooltip-ca-nstab-template'       => 'Se skabelonen',
'tooltip-ca-nstab-help'           => 'Se hjælpesiden',
'tooltip-ca-nstab-category'       => 'Se kategorisiden',
'tooltip-minoredit'               => 'Marker dette som en mindre ændring',
'tooltip-save'                    => 'Gem dine ændringer',
'tooltip-preview'                 => 'Forhåndsvis dine ændringer, brug venligst denne funktion inden du gemmer!',
'tooltip-diff'                    => 'Vis hvilke ændringer du har lavet i teksten.',
'tooltip-compareselectedversions' => 'Se forskellene imellem de to valgte versioner af denne side.',
'tooltip-watch'                   => 'Tilføj denne side til din overvågningsliste',
'tooltip-recreate'                => 'Opret side, selv om den blev slettet.',
'tooltip-upload'                  => 'Upload fil',
'tooltip-rollback'                => '"Rul tilbage" fjerner den sidste bidragsyders redigering(er) af denne side med et klik',
'tooltip-undo'                    => '"Fjern redigering" fjerner denne redigering og åbner redigeringssiden med forhåndsvisning.
Det giver mulighed for at tilføje en begrundelse i beskrivelsen.',
'tooltip-preferences-save'        => 'Gem indstillinger',
'tooltip-summary'                 => 'Indtast en kort opsummering',

# Stylesheets
'common.css'   => '/** CSS inkluderet her vil være aktivt for alle brugere. */',
'monobook.css' => '/** CSS inkluderet her vil være aktivt for brugere af Monobook-temaet . */',

# Scripts
'common.js'   => '/* Javascript inkluderet her vil være aktivt for alle brugere. */',
'monobook.js' => '/* Udgået; brug [[MediaWiki:common.js]] */',

# Metadata
'nodublincore'      => 'Dublin Core RDF-metadata er slået fra på denne server.',
'nocreativecommons' => 'Creative Commons RDF-metadata er slået fra på denne server.',
'notacceptable'     => 'Wiki-serveren kan ikke levere data i et format, som din klient understøtter.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Anonym bruger|Anonyme brugere}} på {{SITENAME}}',
'siteuser'         => '{{SITENAME}} bruger $1',
'anonuser'         => '{{SITENAME}} anonym bruger $1',
'lastmodifiedatby' => 'Denne side blev senest ændret $2, $1 af $3.',
'othercontribs'    => 'Baseret på arbejde af $1.',
'others'           => 'andre',
'siteusers'        => '{{SITENAME}} {{PLURAL:$2|bruger|brugere}} $1',
'anonusers'        => '{{SITENAME}} {{PLURAL:$2|anonym bruger|anonyme brugere}} $1',
'creditspage'      => 'Sidens forfattere',
'nocredits'        => 'Der er ingen forfatteroplysninger om denne side.',

# Spam protection
'spamprotectiontitle' => 'Spambeskyttelsesfilter',
'spamprotectiontext'  => 'Siden du ønskede at gemme, blev blokeret af spamfilteret.
Dette skyldes sandsynligvis en henvisning til et sortlistet eksternt websted.',
'spamprotectionmatch' => 'Følgende tekst udløste vores spamfilter: $1',
'spambot_username'    => 'MediaWiki spam-rensning',
'spam_reverting'      => 'Sidste version uden henvisning til $1 gendannet.',
'spam_blanking'       => 'Alle versioner, som indeholdt henvisninger til $1, er renset.',

# Info page
'infosubtitle'   => 'Information om siden',
'numedits'       => 'Antal redigeringer (artikel): $1',
'numtalkedits'   => 'Antal redigeringer (diskussionsside): $1',
'numwatchers'    => 'Antal overvågere: $1',
'numauthors'     => 'Antal forskellige forfattere (artikel): $1',
'numtalkauthors' => 'Antal forskellige forfattere (diskussionsside): $1',

# Skin names
'skinname-standard'    => 'Klassik',
'skinname-nostalgia'   => 'Nostalgi',
'skinname-cologneblue' => 'Kølnerblå',
'skinname-monobook'    => 'MonoBook',
'skinname-myskin'      => 'Eget udseende',
'skinname-chick'       => 'Chick',
'skinname-simple'      => 'Enkelt',
'skinname-modern'      => 'Moderne',

# Math options
'mw_math_png'    => 'Vis altid som PNG',
'mw_math_simple' => 'HTML hvis meget simpel ellers PNG',
'mw_math_html'   => 'HTML hvis muligt ellers PNG',
'mw_math_source' => 'Lad være som TeX (for tekstbrowsere)',
'mw_math_modern' => 'Anbefalet til moderne browsere',
'mw_math_mathml' => 'MathML hvis muligt',

# Math errors
'math_failure'          => 'Fejl i matematikken',
'math_unknown_error'    => 'ukendt fejl',
'math_unknown_function' => 'ukendt funktion',
'math_lexing_error'     => 'lexerfejl',
'math_syntax_error'     => 'syntaksfejl',
'math_image_error'      => 'PNG-konvertering mislykkedes; undersøg om latex, dvips, gs og convert er installeret korrekt',
'math_bad_tmpdir'       => 'Kan ikke skrive til eller oprette temp-mappe til math',
'math_bad_output'       => 'Kan ikke skrive til eller oprette uddata-mappe til math',
'math_notexvc'          => 'Manglende eksekvérbar texvc; se math/README for opsætningsoplysninger.',

# Patrolling
'markaspatrolleddiff'                 => 'Markér som patruljeret',
'markaspatrolledtext'                 => 'Markér denne artikel som patruljeret',
'markedaspatrolled'                   => 'Markeret som patruljeret',
'markedaspatrolledtext'               => 'Den valgte redigering af [[:$1]] er nu markeret som patruljeret.',
'rcpatroldisabled'                    => 'Seneste ændringer-patruljeringen er slået fra',
'rcpatroldisabledtext'                => 'Funktionen til seneste ændringer-patruljeringen er pt. slået fra.',
'markedaspatrollederror'              => 'Markering som „kontrolleret“ ikke mulig.',
'markedaspatrollederrortext'          => 'Du skal vælge en sideændring.',
'markedaspatrollederror-noautopatrol' => 'Du må ikke markere dine egne ændringer som kontrolleret.',

# Patrol log
'patrol-log-page'      => 'Kontrollog',
'patrol-log-header'    => 'Patruljerede versioner.',
'patrol-log-line'      => 'har markeret $1 af $2 som kontrolleret $3.',
'patrol-log-auto'      => '(automatisk)',
'patrol-log-diff'      => 'Version $1',
'log-show-hide-patrol' => '$1 patruljeringslog',

# Image deletion
'deletedrevision'                 => 'Slettede gammel version $1',
'filedeleteerror-short'           => 'Fejl under sletning af fil: $1',
'filedeleteerror-long'            => 'Der opstod en fejl under sletningen af filen:

$1',
'filedelete-missing'              => 'Filen "$1" kan ikke slettes fordi den ikke findes.',
'filedelete-old-unregistered'     => 'Den angivne version "$1" findes ikke i databasen.',
'filedelete-current-unregistered' => 'Den angiovne fil "$1" findes ikke i databasen.',
'filedelete-archive-read-only'    => 'Webserveren har ikke skriveadgang til arkiv-kataloget "$1".',

# Browsing diffs
'previousdiff' => '← Gå til forrige ændring',
'nextdiff'     => 'Gå til næste ændring →',

# Media information
'mediawarning'         => "'''Advarsel''': Denne filtype kan muligvis indeholde skadelig kode. 
Du kan beskadige dit system hvis du udfører den.<hr />",
'imagemaxsize'         => "Maksimal billedstørrelse<br />''(på filbeskrivelsessider)''",
'thumbsize'            => 'Thumbnailstørrelse :',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|side|sider}}',
'file-info'            => '(Filstørrelse: $1, MIME-Type: $2)',
'file-info-size'       => '($1 × $2 punkter, filstørrelse: $3, MIME-Type: $4)',
'file-nohires'         => '<small>Ingen højere opløsning fundet.</small>',
'svg-long-desc'        => '(SVG fil, basisstørrelse $1 × $2 punkters, størrelse: $3)',
'show-big-image'       => 'Version i større opløsning',
'show-big-image-thumb' => '<small>Størrelse af forhåndsvisning: $1 × $2 pixel</small>',
'file-info-gif-looped' => 'gentaget',
'file-info-gif-frames' => '$1 {{PLURAL:$1|ramme|rammer}}',
'file-info-png-looped' => 'gentaget',
'file-info-png-repeat' => 'afspillede $1 {{PLURAL:$1|gang|gange}}',
'file-info-png-frames' => '$1 {{PLURAL:$1|billede|billeder}}',

# Special:NewFiles
'newimages'             => 'Galleri med de nyeste billeder',
'imagelisttext'         => 'Herunder er en liste med $1 {{PLURAL:$1|billede|billeder}} sorteret $2.',
'newimages-summary'     => 'Denne specialside viser de senest oplagte filer.',
'newimages-legend'      => 'Filter',
'newimages-label'       => 'Filnavn (eller en del af det):',
'showhidebots'          => '($1 botter)',
'noimages'              => 'Ingen filer fundet.',
'ilsubmit'              => 'Søg',
'bydate'                => 'efter dato',
'sp-newimages-showfrom' => 'Vis nye filer startende fra $1 $2',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'hours-abbrev' => 't',

# Bad image list
'bad_image_list' => 'Formatet er:

Kun indholdet af lister (linjer startende med *) bliver brugt. Den første henvisning på en linje er til det uønskede billede. Efterfølgende links på samme linjer er undtagelser, dvs. sider hvor billedet må optræde.',

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => 'Denne fil indeholder yderligere informationer, der som regel stammer fra digitalkameraet eller den brugte scanner. Ved en efterfølgende bearbejdning kan nogle data være ændret.',
'metadata-expand'   => 'Vis udvidede data',
'metadata-collapse' => 'Skjul udvidede data',
'metadata-fields'   => 'Følgenden felter fra EXIF-metadata i denne MediaWiki-systemtekst vises på billedbeskrivelsessider; yderligere detaljer kan vises.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Bredde',
'exif-imagelength'                 => 'Længde',
'exif-bitspersample'               => 'Bits pr. farvekomponent',
'exif-compression'                 => 'Kompressionstype',
'exif-photometricinterpretation'   => 'Pixelsammensætning',
'exif-orientation'                 => 'Kameraretning',
'exif-samplesperpixel'             => 'Antal komponenter',
'exif-planarconfiguration'         => 'Dataretning',
'exif-ycbcrsubsampling'            => 'Subsamplingrate fra Y til C',
'exif-ycbcrpositioning'            => 'Y og C positionering',
'exif-xresolution'                 => 'Horisontal opløsning',
'exif-yresolution'                 => 'Vertikal opløsning',
'exif-resolutionunit'              => 'Måleenhed for opløsning',
'exif-stripoffsets'                => 'Bileddata-forskydning',
'exif-rowsperstrip'                => 'Antal linjer pr. stribe',
'exif-stripbytecounts'             => 'Bytes pr. komprimeret stribe',
'exif-jpeginterchangeformat'       => 'Offset til JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Størrelse af JPEG-fil i bytes',
'exif-transferfunction'            => 'Overførselsfunktion',
'exif-whitepoint'                  => 'Manuel med måling',
'exif-primarychromaticities'       => 'Kromaticitet af primærfarver',
'exif-ycbcrcoefficients'           => 'YCbCr-koefficienter',
'exif-referenceblackwhite'         => 'Sort/hvide-referencepunkter',
'exif-datetime'                    => 'Lagringstidspunkt',
'exif-imagedescription'            => 'Billedtitel',
'exif-make'                        => 'Producent',
'exif-model'                       => 'Model',
'exif-software'                    => 'Software',
'exif-artist'                      => 'Fotograf',
'exif-copyright'                   => 'Ophavsret',
'exif-exifversion'                 => 'Exif-version',
'exif-flashpixversion'             => 'Understøttet Flashpix-version',
'exif-colorspace'                  => 'Farverum',
'exif-componentsconfiguration'     => 'Betydning af enkelte komponenter',
'exif-compressedbitsperpixel'      => 'Komprimerede bits pr. pixel',
'exif-pixelydimension'             => 'Gyldig billedbredde',
'exif-pixelxdimension'             => 'Gyldig billedhøjde',
'exif-makernote'                   => 'Producentnotits',
'exif-usercomment'                 => 'Brugerkommentarer',
'exif-relatedsoundfile'            => 'Tilhørende lydfil',
'exif-datetimeoriginal'            => 'Optagelsestidspunkt',
'exif-datetimedigitized'           => 'Digitaliseringstidspunkt',
'exif-subsectime'                  => 'Lagringstidspunkt (1/100 s)',
'exif-subsectimeoriginal'          => 'Optagelsestidspunkt (1/100 s)',
'exif-subsectimedigitized'         => 'Digitaliseringstidspunkt (1/100 s)',
'exif-exposuretime'                => 'Belysningsvarighed',
'exif-exposuretime-format'         => '$1 sekunder ($2)',
'exif-fnumber'                     => 'Blænde',
'exif-exposureprogram'             => 'Belysningsprogram',
'exif-spectralsensitivity'         => 'Spectral sensitivitet',
'exif-isospeedratings'             => 'Film- eller sensorfølsomhed (ISO)',
'exif-oecf'                        => 'Optoelektronisk omregningsfaktor',
'exif-shutterspeedvalue'           => 'Belysningstidsværdi',
'exif-aperturevalue'               => 'Blændeværdi',
'exif-brightnessvalue'             => 'Lyshedsværdi',
'exif-exposurebiasvalue'           => 'Belysningsindstilling',
'exif-maxaperturevalue'            => 'Største blænde',
'exif-subjectdistance'             => 'Afstand',
'exif-meteringmode'                => 'Målemetode',
'exif-lightsource'                 => 'Lyskilde',
'exif-flash'                       => 'Blitz',
'exif-focallength'                 => 'Brændvidde',
'exif-subjectarea'                 => 'Område',
'exif-flashenergy'                 => 'Blitzstyrke',
'exif-spatialfrequencyresponse'    => 'Rumligt frekvenssvar',
'exif-focalplanexresolution'       => 'Fokuseringspunkt X-opløsning',
'exif-focalplaneyresolution'       => 'Fokuseringspunkt Y-opløsning',
'exif-focalplaneresolutionunit'    => 'Enhed for fokuseringsopløsning',
'exif-subjectlocation'             => 'Motivsted',
'exif-exposureindex'               => 'Belysningsindeks',
'exif-sensingmethod'               => 'Målemetode',
'exif-filesource'                  => 'Filens kilde',
'exif-scenetype'                   => 'Scenetype',
'exif-cfapattern'                  => 'CFA-mønster',
'exif-customrendered'              => 'Brugerdefineret billedbehandling',
'exif-exposuremode'                => 'Belysningsmodus',
'exif-whitebalance'                => 'Hvidafstemning',
'exif-digitalzoomratio'            => 'Digitalzoom',
'exif-focallengthin35mmfilm'       => 'Brændvidde (småbilledækvivalent)',
'exif-scenecapturetype'            => 'Optagelsestype',
'exif-gaincontrol'                 => 'Forstærkning',
'exif-contrast'                    => 'Kontrast',
'exif-saturation'                  => 'Mætning',
'exif-sharpness'                   => 'Skarphed',
'exif-devicesettingdescription'    => 'Apparatindstilling',
'exif-subjectdistancerange'        => 'Motivafstand',
'exif-imageuniqueid'               => 'Billed-ID',
'exif-gpsversionid'                => 'GPS-dag-version',
'exif-gpslatituderef'              => 'Nordlig eller sydlig bredde',
'exif-gpslatitude'                 => 'Geografisk bredde',
'exif-gpslongituderef'             => 'Østlig eller vestlig længde',
'exif-gpslongitude'                => 'Geografisk længde',
'exif-gpsaltituderef'              => 'Udgangshøjde',
'exif-gpsaltitude'                 => 'Højde',
'exif-gpstimestamp'                => 'GPS-tid',
'exif-gpssatellites'               => 'Til målingen brugte satelitter',
'exif-gpsstatus'                   => 'Modtagerstatus',
'exif-gpsmeasuremode'              => 'Målemetode',
'exif-gpsdop'                      => 'Målepræcision',
'exif-gpsspeedref'                 => 'Hastighedsenhed',
'exif-gpsspeed'                    => 'GPS-modtagerens hastighed',
'exif-gpstrackref'                 => 'Reference for bevægelsesretningen',
'exif-gpstrack'                    => 'Bevægelsesretningen',
'exif-gpsimgdirectionref'          => 'Reference for retningen af billedet',
'exif-gpsimgdirection'             => 'Billedretning',
'exif-gpsmapdatum'                 => 'Geodætisk dato benyttet',
'exif-gpsdestlatituderef'          => 'Reference for bredden',
'exif-gpsdestlatitude'             => 'Bredde',
'exif-gpsdestlongituderef'         => 'Reference for længden',
'exif-gpsdestlongitude'            => 'Længde',
'exif-gpsdestbearingref'           => 'Reference for motivretningen',
'exif-gpsdestbearing'              => 'Motivretning',
'exif-gpsdestdistanceref'          => 'Reference for motivafstanden',
'exif-gpsdestdistance'             => 'Motivafstand',
'exif-gpsprocessingmethod'         => 'GPS-metodens navn',
'exif-gpsareainformation'          => 'GPS-områdets navn',
'exif-gpsdatestamp'                => 'GPS-dato',
'exif-gpsdifferential'             => 'GPS-differentialkorrektur',

# EXIF attributes
'exif-compression-1' => 'Ukomprimeret',

'exif-unknowndate' => 'Ukendt dato',

'exif-orientation-1' => 'Normal',
'exif-orientation-2' => 'Horisontalt spejlet',
'exif-orientation-3' => 'Drejet 180°',
'exif-orientation-4' => 'Vertikalt spejlet',
'exif-orientation-5' => 'Drejet 90° mod uret og spejlet vertikalt',
'exif-orientation-6' => 'Drejet 90° med uret',
'exif-orientation-7' => 'Drejet 90° med uret og spejlet vertikalt',
'exif-orientation-8' => 'Drejet 90° mod uret',

'exif-planarconfiguration-1' => 'Grovformat',
'exif-planarconfiguration-2' => 'Planformat',

'exif-componentsconfiguration-0' => 'Findes ikke',

'exif-exposureprogram-0' => 'Ukendt',
'exif-exposureprogram-1' => 'Manuel',
'exif-exposureprogram-2' => 'Standardprogram',
'exif-exposureprogram-3' => 'Tidsautomatik',
'exif-exposureprogram-4' => 'Blændeautomatik',
'exif-exposureprogram-5' => 'Kreativprogram med tendens til stor skarphedsdybde',
'exif-exposureprogram-6' => 'Aktionprogram med tendens til kort belysningstid',
'exif-exposureprogram-7' => 'Portrætprogram',
'exif-exposureprogram-8' => 'Landskabsoptagelse',

'exif-subjectdistance-value' => '$1 meter',

'exif-meteringmode-0'   => 'Ukendt',
'exif-meteringmode-1'   => 'Gennemsnitlig',
'exif-meteringmode-2'   => 'Midtcentreret',
'exif-meteringmode-3'   => 'Spotmåling',
'exif-meteringmode-4'   => 'Flerspotmåling',
'exif-meteringmode-5'   => 'Mønster',
'exif-meteringmode-6'   => 'Billeddel',
'exif-meteringmode-255' => 'Ukendt',

'exif-lightsource-0'   => 'Ukendt',
'exif-lightsource-1'   => 'Dagslys',
'exif-lightsource-2'   => 'Fluorescerende',
'exif-lightsource-3'   => 'Glødelampe',
'exif-lightsource-4'   => 'Blitz',
'exif-lightsource-9'   => 'Godt vejr',
'exif-lightsource-10'  => 'Overskyet',
'exif-lightsource-11'  => 'Skyggefuldt',
'exif-lightsource-12'  => 'Dagslys fluorescerende (D 5700–7100 K)',
'exif-lightsource-13'  => 'Dagshvidt fluorescerende (N 4600–5400 K)',
'exif-lightsource-14'  => 'Koldthvidt fluorescerende (W 3900–4500 K)',
'exif-lightsource-15'  => 'Hvist fluorescerende (WW 3200–3700 K)',
'exif-lightsource-17'  => 'Standardlys A',
'exif-lightsource-18'  => 'Standardlys B',
'exif-lightsource-19'  => 'Standardlys C',
'exif-lightsource-24'  => 'ISO studio kunstlys',
'exif-lightsource-255' => 'Andre lyskilder',

# Flash modes
'exif-flash-fired-0'    => 'Blitz ikke udløst',
'exif-flash-fired-1'    => 'Blitz udløst',
'exif-flash-return-0'   => 'ingen funktion til at opdage pulserende lys',
'exif-flash-return-2'   => 'pulserende lys ikke opdaget',
'exif-flash-return-3'   => 'pulserende lys opdaget',
'exif-flash-mode-1'     => 'tvunget blitzudløsning',
'exif-flash-mode-2'     => 'tvunget blitzløs',
'exif-flash-mode-3'     => 'automatisk modus',
'exif-flash-function-1' => 'Ingen blitzfunktion',
'exif-flash-redeye-1'   => 'reducering af røde øjne',

'exif-focalplaneresolutionunit-2' => 'Tomme',

'exif-sensingmethod-1' => 'Udefineret',
'exif-sensingmethod-2' => 'En-chip-farvesensor',
'exif-sensingmethod-3' => 'To-chip-farvesensor',
'exif-sensingmethod-4' => 'Tre-chip-farvesensor',
'exif-sensingmethod-5' => 'Farvesekventiel områdesensor',
'exif-sensingmethod-7' => 'Triliniær sensor',
'exif-sensingmethod-8' => 'Farvesekventiel liniarsensor',

'exif-scenetype-1' => 'Normal',

'exif-customrendered-0' => 'Standard',
'exif-customrendered-1' => 'Brugerdefineret',

'exif-exposuremode-0' => 'Automatisk belysning',
'exif-exposuremode-1' => 'Manuel belysning',
'exif-exposuremode-2' => 'Belysningsrække',

'exif-whitebalance-0' => 'Automatisk',
'exif-whitebalance-1' => 'Manuel',

'exif-scenecapturetype-0' => 'Normal',
'exif-scenecapturetype-1' => 'Landskab',
'exif-scenecapturetype-2' => 'Portræt',
'exif-scenecapturetype-3' => 'Natscene',

'exif-gaincontrol-0' => 'Ingen',
'exif-gaincontrol-1' => 'Low gain up',
'exif-gaincontrol-2' => 'High gain up',
'exif-gaincontrol-3' => 'Low gain down',
'exif-gaincontrol-4' => 'High gain down',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Svag',
'exif-contrast-2' => 'Stærk',

'exif-saturation-0' => 'Normal',
'exif-saturation-1' => 'Ringe',
'exif-saturation-2' => 'Høj',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Ringe',
'exif-sharpness-2' => 'Stærk',

'exif-subjectdistancerange-0' => 'Ukendt',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Nær',
'exif-subjectdistancerange-3' => 'Fjern',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'nordl. bredde',
'exif-gpslatitude-s' => 'sydl. bredde',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'østl. længde',
'exif-gpslongitude-w' => 'vestl. længde',

'exif-gpsstatus-a' => 'Måling kører',
'exif-gpsstatus-v' => 'Målingens interoperabilitet',

'exif-gpsmeasuremode-2' => '2-dimensional måling',
'exif-gpsmeasuremode-3' => '3-dimensional måling',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'km/h',
'exif-gpsspeed-m' => 'mph',
'exif-gpsspeed-n' => 'Knob',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Faktisk retning',
'exif-gpsdirection-m' => 'Magnetisk retning',

# External editor support
'edit-externally'      => 'Rediger denne fil med en ekstern editor',
'edit-externally-help' => '(Se [http://www.mediawiki.org/wiki/Manual:External_editors setup instruktionerne] for mere information)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'alle',
'imagelistall'     => 'alle',
'watchlistall2'    => 'alle',
'namespacesall'    => 'alle',
'monthsall'        => 'alle',
'limitall'         => 'alle',

# E-mail address confirmation
'confirmemail'              => 'Bekræft e-mail-adressen',
'confirmemail_noemail'      => 'Du har ikke angivet en gyldig e-mail-adresse i din [[Special:Preferences|brugerprofil]].',
'confirmemail_text'         => '{{SITENAME}} kræver, at du bekræfter en e-mail-adresse (autentificering), før du kan bruge de udvidede e-mail-funktioner. Med et klik på kontrolfeltet forneden sendes en e-mail til dig. Denne e-mail indeholder et link med en bekræftelseskode. Med et klik på dette link bekræftes, at e-mail-adressen er gyldig.',
'confirmemail_pending'      => 'En bekræftelsesmail er allerede sendt til dig. Hvis du først for nylig har oprettet brugerkontoen, vent da et par minutter på denne e-mail, før du bestiller en ny kode.',
'confirmemail_send'         => 'Send bekræftelseskode',
'confirmemail_sent'         => 'Bekræftelses-e-mail afsendt.',
'confirmemail_oncreate'     => 'En bekræftelseskode er sendt til din e-mail-adresse. Denne kode skal ikke bruges til anmeldelsen, den kræves dog til aktiveringen af e-mail-funktionerne indenfor Wikien.',
'confirmemail_sendfailed'   => 'Bekræftelsesmailen kunne ikke afsendes. Kontroller at e-mail-adressen er korrekt.

Rückmeldung des Mailservers: $1',
'confirmemail_invalid'      => 'Ugyldig bekræftelseskode. Kodens gyldighed er muligvis udløbet.',
'confirmemail_needlogin'    => 'Du skal $1 for at bekræfte e-mail-adressen.',
'confirmemail_success'      => 'E-mail-adressen er nu bekræftet. Du kan nu logge på.',
'confirmemail_loggedin'     => 'E-mail-adressen er nu bekræftet.',
'confirmemail_error'        => 'Der skete en fejl ved bekræftelsen af e-mail-adressen.',
'confirmemail_subject'      => '[{{SITENAME}}] - bekræftelse af e-mail-adressen',
'confirmemail_body'         => 'Hej,

Nogen med IP-adresse $1, sandsynligvis dig, har bestilt en bekræftelse af denne e-mail-adresse til brugerkontoen "$2" på {{SITENAME}}.

For at aktivere e-mail-funktionen for {{SITENAME}} (igen) og for at bekræfte, at denne brugerkonto virkelig hører til din e-mail-adresse og dermed til dig, bedes du åbne det følgende link i din browser: $3

Bekræftelseskoden er gyldig indtil følgende tidspunkt: $4

Hvis denne e-mail-adresse *ikke* hører til den anførte brugerkonto, skal du i stedet åbne dette link i din browser: $5

-- 
{{SITENAME}}: {{fullurl:{{Mediawiki:mainpage}}}}',
'confirmemail_body_changed' => 'Der er nogen, sandsynligvis dig, fra ip-adressen $1, der har ændret emailadressen for kontoen "$2" til denne adresse på {{SITENAME}}.

For at bekræfte, at denne konto virkeligt tilhører dig og for at genaktivere emailfunktionerne på {{SITENAME}}, bedes du åbne følgende link i en browser:

$3

Hvis denne konto *ikke* tilhører dig, bedes du åbne følgende link, for at afbryde ændringen

$5

Denne bekræftelseskode udløber $4',
'confirmemail_invalidated'  => 'E-mail-bekræftelse afvist',
'invalidateemail'           => 'Cancel e-mail confirmation',

# Scary transclusion
'scarytranscludedisabled' => '[Interwiki-tilkobling er deaktiveret]',
'scarytranscludefailed'   => '[Hentning af skabelon for $1 mislykkedes]',
'scarytranscludetoolong'  => "[URL'en er for lang]",

# Trackbacks
'trackbackbox'      => 'Trackbacks for denne side:<br />
$1',
'trackbackremove'   => '([$1 slet])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => 'Trackback blev slettet.',

# Delete conflict
'deletedwhileediting' => 'Bemærk: Det blev forsøgt at slette denne side, efter at du var begyndt, at ændre den! 
Kig i [{{fullurl:Special:Log|type=delete&page=}}{{FULLPAGENAMEE}} slette-loggen], 
hvorfor siden blev slettet. Hvis du gemmer siden bliver den oprettet igen.',
'confirmrecreate'     => "Bruger [[User:$1|$1]] ([[User talk:$1|Diskussion]]) har slettet denne side, efter at du begyndte at ændre den. Begrundelsen lyder:
: ''$2''
Bekræft venligst, at du virkelig vil oprette denne side igen.",
'recreate'            => 'Opret igen',

# action=purge
'confirm_purge_button' => 'O.k.',
'confirm-purge-top'    => "Slet denne side fra serverens mellemlager (''cache'')?",
'confirm-purge-bottom' => 'Rensning af en side sletter mellemlageret og fremtvinger den nyeste version.',

# Multipage image navigation
'imgmultipageprev' => '← forrige side',
'imgmultipagenext' => 'næste side →',
'imgmultigo'       => 'OK',
'imgmultigoto'     => 'Gå til side $1',

# Table pager
'ascending_abbrev'         => 'op',
'descending_abbrev'        => 'ned',
'table_pager_next'         => 'Næste side',
'table_pager_prev'         => 'Forrige side',
'table_pager_first'        => 'Første side',
'table_pager_last'         => 'Sidste side',
'table_pager_limit'        => 'Vis $1 indførsler pr. side',
'table_pager_limit_label'  => 'Elementer pr. side:',
'table_pager_limit_submit' => 'Start',
'table_pager_empty'        => 'Ingen resultater',

# Auto-summaries
'autosumm-blank'   => 'Tømmer siden',
'autosumm-replace' => "Sidens indhold blev erstattet med: '$1'",
'autoredircomment' => 'Omdirigering til [[$1]] oprettet',
'autosumm-new'     => "Oprettede siden med '$1'",

# Live preview
'livepreview-loading' => 'Indlæser …',
'livepreview-ready'   => 'Indlæser … færdig!',
'livepreview-failed'  => 'Live-forhåndsvisning ikke mulig! Brug venligst den almindelige forhåndsvisning.',
'livepreview-error'   => 'Forbindelse ikke mulig: $1 "$2". Brug venligst den almindelige forhåndsvisning.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Ændringer som er nyere end {{PLURAL:$1|et sekund|$1 sekunder}}, vises muligvis ikke i denne liste.',
'lag-warn-high'   => 'Grundet stor belastning af databaseserveren, vil ændringer der er nyere end {{PLURAL:$1|et sekund|$1 sekunder}} måske ikke blive vist i denne liste.',

# Watchlist editor
'watchlistedit-numitems'       => 'Din overvågningsliste indeholder {{PLURAL:$1|1 side|$1 sider}}, diskussionssider fraregnet.',
'watchlistedit-noitems'        => 'Din overvågningsliste er tom.',
'watchlistedit-normal-title'   => 'Rediger overvågningsliste',
'watchlistedit-normal-legend'  => 'Slet sider fra overvågningslisten',
'watchlistedit-normal-explain' => 'Siderne på din overvågningsliste er vist nedenfor.
For at fjerne en side, skal boksen ud for den markeres, og klik på "{{int:Watchlistedit-normal-submit}}".
Du kan også [[Special:Watchlist/raw|redigere siden direkte]].',
'watchlistedit-normal-submit'  => 'Fjern valgte',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 side|$1 sider}} er fjernet fra din overvågningsliste:',
'watchlistedit-raw-title'      => 'Direkte redigering af overvågningsliste',
'watchlistedit-raw-legend'     => 'Direkte redigering af overvågningsliste',
'watchlistedit-raw-explain'    => 'Siderne i din overvågningsliste er vist nedenfor, og kan ændres ved at tilføje og fjerne fra listen;
en side per linie
Når du er færdig, klik "{{int:Watchlistedit-raw-submit}}".
Du kan også [[Special:Watchlist/edit|bruge standard editoren]].',
'watchlistedit-raw-titles'     => 'Sider:',
'watchlistedit-raw-submit'     => 'Opdater overvågningsliste',
'watchlistedit-raw-done'       => 'Din overvågningsliste blev opdateret.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 side|$1 sider}} er tilføjet:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 side|$1 sider}} er fjernet:',

# Watchlist editing tools
'watchlisttools-view' => 'Se ændrede sider i overvågningslisten',
'watchlisttools-edit' => 'Rediger overvågningsliste',
'watchlisttools-raw'  => 'Rediger rå overvågningsliste',

# Core parser functions
'unknown_extension_tag' => 'Unknown extension tag "$1"',
'duplicate-defaultsort' => 'Advarsel: Standardsorteringsnøglen "$2" tilsidesætter den tidligere sorteringsnøgle "$1".',

# Special:Version
'version'                          => 'Version',
'version-extensions'               => 'Installerede udvidelser',
'version-specialpages'             => 'Specialsider',
'version-parserhooks'              => 'Oversætter-funktioner',
'version-variables'                => 'Variabler',
'version-other'                    => 'Andet',
'version-mediahandlers'            => 'Specialhåndtering af mediefiler',
'version-hooks'                    => 'Funktionstilføjelser',
'version-extension-functions'      => 'Udvidelsesfunktioner',
'version-parser-extensiontags'     => 'Tilføjede tags',
'version-parser-function-hooks'    => 'Oversætter-funktioner',
'version-skin-extension-functions' => 'Ekstra funktioner til udseende',
'version-hook-name'                => 'Navn',
'version-hook-subscribedby'        => 'Brugt af',
'version-version'                  => '(Version $1)',
'version-license'                  => 'Licens',
'version-software'                 => 'Installeret software',
'version-software-product'         => 'Produkt',
'version-software-version'         => 'Version',

# Special:FilePath
'filepath'         => 'Filsti',
'filepath-page'    => 'Fil:',
'filepath-submit'  => 'Vis sti',
'filepath-summary' => 'Denne specialside giver et direkte link til en fil.
Billeder vises i fuld opløsning, og andre mediatyper vil blive aktiveret med deres tilhørende program.

Angiv filnavnet uden "{{ns:file}}:"-præfiks.',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Find dubletfiler',
'fileduplicatesearch-summary'  => 'Find dublerede filer baseret på deres hash-værdi.

Angiv filnavnet uden "{{ns:file}}:"-præfikset.',
'fileduplicatesearch-legend'   => 'Find dubletfiler.',
'fileduplicatesearch-filename' => 'Filnavn:',
'fileduplicatesearch-submit'   => 'Find',
'fileduplicatesearch-info'     => '$1 × $2 punkter<br />Filstørrelse: $3<br />MIME-type: $4',
'fileduplicatesearch-result-1' => 'Filen "$1" har ingen identiske dubletter.',
'fileduplicatesearch-result-n' => 'Filen "$1" har {{PLURAL:$2|en dublet|$2 dubletter}}.',

# Special:SpecialPages
'specialpages'                   => 'Specialsider',
'specialpages-note'              => '----
* Normale specialsider.
* <strong class="mw-specialpagerestricted">Specialsider med begrænset adgang.</strong>',
'specialpages-group-maintenance' => 'Vedligeholdelsesside',
'specialpages-group-other'       => 'Andre specialsider',
'specialpages-group-login'       => 'Opret bruger / logon',
'specialpages-group-changes'     => 'Seneste ændringer og loglister',
'specialpages-group-media'       => 'Mediafiler og oplægning',
'specialpages-group-users'       => 'Brugere og rettigheder',
'specialpages-group-highuse'     => 'Højt profilerede sider',
'specialpages-group-pages'       => 'Sidelister',
'specialpages-group-pagetools'   => 'Sideværktøjer',
'specialpages-group-wiki'        => 'Wikidata og værktøjer',
'specialpages-group-redirects'   => 'Specialsider der viderestiller',
'specialpages-group-spam'        => 'Spamværktøjer',

# Special:BlankPage
'blankpage'              => 'Blank side',
'intentionallyblankpage' => 'Denne side er bevidst uden indhold.',

# External image whitelist
'external_image_whitelist' => " #Lad denne linje være som den er<pre>
#Skriv dele af regulære uttryk (delen som er mellem //) nedenfor
#Disse vil tjekkes mod URL'er for eksterne billeder
#De som matcher, vil blive vist som billeder. Øvrige vil kun blive vist som henvisninger til billedet
#Linjer som begynder med # behandles som kommentarer
#Der skelnes ikke mellem store og små bogstaver

#Skriv alle delene af regulære udtryk over denne linje. Lad denne linje være som den er</pre>",

# Special:Tags
'tags'                    => 'Tags til sideversioner',
'tag-filter'              => '[[Special:Tags|tag]]filter:',
'tag-filter-submit'       => 'Filtrér',
'tags-title'              => 'Tags',
'tags-intro'              => 'This page lists the tags that the software may mark an edit with, and their meaning.
Denne side oplister de tags som programmet kan mærke en redigering med, og deres betydning.',
'tags-tag'                => 'Tagnavn',
'tags-display-header'     => 'Udseende på ændringslister',
'tags-description-header' => 'Beskrivelse af betydning',
'tags-hitcount-header'    => 'Taggede ændringer',
'tags-edit'               => 'Redigér',
'tags-hitcount'           => '{{PLURAL:$1|en ændring|$1 ændringer}}',

# Special:ComparePages
'comparepages'     => 'Sammenlign sider',
'compare-selector' => 'Sammenlign sideversioner',
'compare-page1'    => 'Side 1',
'compare-page2'    => 'Side 2',
'compare-rev1'     => 'Version 1',
'compare-rev2'     => 'Version 2',
'compare-submit'   => 'Sammenlign',

# Database error messages
'dberr-header'      => 'Wikien har et problem',
'dberr-problems'    => 'Undskyld! Siden har tekniske problemer.',
'dberr-again'       => 'Prøv at vente et par minutter og opdater Nyså siden igen.',
'dberr-info'        => '(Kan ikke komme i kontakt med databaseserveren: $1)',
'dberr-usegoogle'   => 'Du kan prøve at søge med Google imens.',
'dberr-outofdate'   => 'Bemærk at deres indeks over vores sider kan være forældet.',
'dberr-cachederror' => 'Det følgende er en mellemlagret kopi af den forespurgte side. Den kan være forældet.',

# HTML forms
'htmlform-invalid-input'       => 'Det er problemer med dine angivelser',
'htmlform-select-badoption'    => 'Den angivne værdi er ikke gyldigt valg.',
'htmlform-int-invalid'         => 'Den angivne værdi er ikke et heltal.',
'htmlform-float-invalid'       => 'Den angivne værdi er ikke et tal.',
'htmlform-int-toolow'          => 'Den angivne værdi er under minimumsværdien på $1',
'htmlform-int-toohigh'         => 'Den angivne værdi er over maksimumsværdien på $1',
'htmlform-required'            => 'Denne værdi skal angives',
'htmlform-submit'              => 'Gem',
'htmlform-reset'               => 'Annuller ændringer',
'htmlform-selectorother-other' => 'Anden',

);

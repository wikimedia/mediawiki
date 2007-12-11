<?php
/** Norwegian (‪Norsk (bokmål)‬)
 *
 * @addtogroup Language
 *
 * @author Jon Harald Søby
 * @author Nike
 * @author Teak
 * @author לערי ריינהארט
 * @author Siebrand
 */

$skinNames = array(
	'standard'    => 'Standard',
	'nostalgia'   => 'Nostalgi',
	'cologneblue' => 'Kölnerblå'
);

$bookstoreList = array(
	'Antikvariat.net' => 'http://www.antikvariat.net/',
	'Frida' => 'http://wo.uio.no/as/WebObjects/frida.woa/wa/fres?action=sok&isbn=$1&visParametre=1&sort=alfabetisk&bs=50',
	'Bibsys' => 'http://ask.bibsys.no/ask/action/result?cmd=&kilde=biblio&fid=isbn&term=$1&op=and&fid=bd&term=&arstall=&sortering=sortdate-&treffPrSide=50',
	'Akademika' => 'http://www.akademika.no/sok.php?ts=4&sok=$1',
	'Haugenbok' => 'http://www.haugenbok.no/resultat.cfm?st=extended&isbn=$1',
	'Amazon.com' => 'http://www.amazon.com/exec/obidos/ISBN=$1'
);

$namespaceNames = array(
	NS_MEDIA          => 'Medium',
	NS_SPECIAL        => 'Spesial',
	NS_MAIN           => '',
	NS_TALK           => 'Diskusjon',
	NS_USER           => 'Bruker',
	NS_USER_TALK      => 'Brukerdiskusjon',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => '$1-diskusjon',
	NS_IMAGE          => 'Bilde',
	NS_IMAGE_TALK     => 'Bildediskusjon',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'MediaWiki-diskusjon',
	NS_TEMPLATE       => 'Mal',
	NS_TEMPLATE_TALK  => 'Maldiskusjon',
	NS_HELP           => 'Hjelp',
	NS_HELP_TALK      => 'Hjelpdiskusjon',
	NS_CATEGORY       => 'Kategori',
	NS_CATEGORY_TALK  => 'Kategoridiskusjon',
);

$separatorTransformTable = array(',' => "\xc2\xa0", '.' => ',' );
$linkTrail = '/^([æøåa-z]+)(.*)$/sDu';

$dateFormats = array(
	'mdy time' => 'H:i',
	'mdy date' => 'M j., Y',
	'mdy both' => 'M j., Y "kl." H:i',

	'dmy time' => 'H:i',
	'dmy date' => 'j. M Y',
	'dmy both' => 'j. M Y "kl." H:i',

	'ymd time' => 'H:i',
	'ymd date' => 'Y M j.',
	'ymd both' => 'Y M j. "kl." H:i',
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( "Doble_omdirigeringer" ),
	'BrokenRedirects'           => array( "Ødelagte_omdirigeringer" ),
	'Disambiguations'           => array( "Pekere" ),
	'Userlogin'                 => array( "Logg_inn" ),
	'Userlogout'                => array( "Logg_ut" ),
	'Preferences'               => array( "Innstillinger" ),
	'Watchlist'                 => array( "Overvåkningsliste", "Overvåkingsliste" ),
	'Recentchanges'             => array( "Siste_endringer" ),
	'Upload'                    => array( "Last_opp" ),
	'Imagelist'                 => array( "Filliste", "Bildeliste", "Billedliste" ),
	'Newimages'                 => array( "Nye_bilder" ),
	'Listusers'                 => array( "Brukerliste" ),
	'Statistics'                => array( "Statistikk" ),
	'Randompage'                => array( "Tilfeldig_side", "Tilfeldig" ),
	'Lonelypages'               => array( "Foreldreløse_sider" ),
	'Uncategorizedpages'        => array( "Ukategoriserte_sider" ),
	'Uncategorizedcategories'   => array( "Ukategoriserte_kategorier" ),
	'Uncategorizedimages'       => array( "Ukategoriserte_filer", "Ukategoriserte_bilder" ),
	'Unusedcategories'          => array( "Ubrukte_kategorier" ),
	'Unusedimages'              => array( "Ubrukte_filer", "Ubrukte_bilder" ),
	'Wantedpages'               => array( "Ønskede_sider" ),
	'Wantedcategories'          => array( "Ønskede_kategorier" ),
	'Mostlinked'                => array( "Mest_lenkede_sider", "Mest_lenka_sider" ),
	'Mostlinkedcategories'      => array( "Største_kategorier" ),
	'Mostlinkedtemplates'       => array( "Mest_brukte_maler" ),
	'Mostcategories'            => array( "Flest_kategorier" ),
	'Mostimages'                => array( "Mest_brukte_bilder", "Mest_brukte_filer" ),
	'Mostrevisions'             => array( "Flest_revisjoner" ),
	'Fewestrevisions'           => array( "Færrest_revisjoner" ),
	'Shortpages'                => array( "Korte_sider" ),
	'Longpages'                 => array( "Lange_sider" ),
	'Newpages'                  => array( "Nye_sider" ),
	'Ancientpages'              => array( "Gamle_sider" ),
	'Deadendpages'              => array( "Blindveisider" ),
	'Protectedpages'            => array( "Beskyttede_sider" ),
	'Allpages'                  => array( "Alle_sider" ),
	'Prefixindex'               => array( "Prefiksindeks" ),
	'Ipblocklist'               => array( "Blokkeringsliste" ),
	'Specialpages'              => array( "Spesialsider" ),
	'Contributions'             => array( "Bidrag" ),
	'Emailuser'                 => array( "E-post" ),
	'Whatlinkshere'             => array( "Lenker_hit" ),
	'Recentchangeslinked'       => array( "Relaterte_endringer" ),
	'Movepage'                  => array( "Flytt_side" ),
	'Blockme'                   => array( "Blokker_meg" ),
	'Booksources'               => array( "Bokkilder" ),
	'Categories'                => array( "Kategorier" ),
	'Export'                    => array( "Eksporter" ),
	'Version'                   => array( "Versjon" ),
	'Allmessages'               => array( "Alle_systembeskjeder" ),
	'Log'                       => array( "Logg", "Logger" ),
	'Blockip'                   => array( "Blokker" ),
	'Undelete'                  => array( "Gjenopprett" ),
	'Import'                    => array( "Importer" ),
	'Lockdb'                    => array( "Lås_database" ),
	'Unlockdb'                  => array( "Åpne_database" ),
	'Userrights'                => array( "Brukerrettigheter" ),
	'MIMEsearch'                => array( "MIME-søk" ),
	'Unwatchedpages'            => array( "Uovervåkede_sider" ),
	'Listredirects'             => array( "Omdirigeringsliste" ),
	'Revisiondelete'            => array( "Revisjonssletting" ),
	'Unusedtemplates'           => array( "Ubrukte_maler" ),
	'Randomredirect'            => array( "Tilfeldig_omdirigering" ),
	'Mypage'                    => array( "Min_side" ),
	'Mytalk'                    => array( "Min_diskusjon" ),
	'Mycontributions'           => array( "Mine_bidrag" ),
	'Listadmins'                => array( "Administratorliste", "Administratorer" ),
	'Popularpages'              => array( "Populære_sider" ),
	'Search'                    => array( "Søk" ),
	'Resetpass'                 => array( "Resett_passord" ),
	'Withoutinterwiki'          => array( "Uten_interwiki" ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Strek under lenker:',
'tog-highlightbroken'         => 'Formater lenker til ikke-eksisterende sider <a href="" class="new">slik</a> (alternativt: slik<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Blokkjusterte avsnitt',
'tog-hideminor'               => 'Skjul mindre endringer i siste endringer',
'tog-extendwatchlist'         => 'Utvid overvåkningslista til å vise alle endringer i valgt tidsrom',
'tog-usenewrc'                => 'Forbedret siste endringer (ikke for alle nettlesere)',
'tog-numberheadings'          => 'Nummerer overskrifter',
'tog-showtoolbar'             => 'Vis verktøylinje (JavaScript)',
'tog-editondblclick'          => 'Rediger sider ved å dobbeltklikke (JavaScript)',
'tog-editsection'             => 'Rediger avsnitt ved hjelp av [rediger]-lenke',
'tog-editsectiononrightclick' => 'Rediger avsnitt ved å høyreklikke på avsnittsoverskrift (JavaScript)',
'tog-showtoc'                 => 'Vis innholdsfortegnelse (for sider med mer enn tre seksjoner)',
'tog-rememberpassword'        => 'Husk passordet',
'tog-editwidth'               => 'Full bredde på redigeringsboksen',
'tog-watchcreations'          => 'Overvåk sider du oppretter',
'tog-watchdefault'            => 'Overvåk alle redigerte sider',
'tog-watchmoves'              => 'Legg til sider jeg flytter i overvåkningslista mi',
'tog-watchdeletion'           => 'Legg til sider jeg sletter i overvåkningslista mi',
'tog-minordefault'            => 'Merk i utgangspunktet alle redigeringer som mindre',
'tog-previewontop'            => 'Flytt forhåndsvisningen foran redigeringsboksen',
'tog-previewonfirst'          => 'Vis forhåndsvisning ved første redigering av en side',
'tog-nocache'                 => 'Skru av mellomlagring av sider («caching»)',
'tog-enotifwatchlistpages'    => 'Send meg en e-post ved sideendringer',
'tog-enotifusertalkpages'     => 'Send meg en e-post ved endringer av brukerdiskusjonssiden min',
'tog-enotifminoredits'        => 'Send meg en e-post også ved mindre sideendringer',
'tog-enotifrevealaddr'        => 'Vis min e-postadresse i utgående meldinger',
'tog-shownumberswatching'     => 'Vis antall overvåkende brukere',
'tog-fancysig'                => 'Råsignatur (uten automatisk lenke)',
'tog-externaleditor'          => 'Bruk ekstern behandler som standard',
'tog-externaldiff'            => 'Bruk ekstern differanse som standard',
'tog-showjumplinks'           => 'Slå på «gå til»-lenker',
'tog-uselivepreview'          => 'Bruk levende forhåndsvisning (eksperimentell JavaScript)',
'tog-forceeditsummary'        => 'Advar meg når jeg ikke har noen redigeringsforklaring',
'tog-watchlisthideown'        => 'Skjul mine endringer fra overvåkningslista',
'tog-watchlisthidebots'       => 'Skjul robotendringer fra overvåkningslista',
'tog-watchlisthideminor'      => 'Skjul mindre endringer fra overvåkningslista',
'tog-nolangconversion'        => 'Slå av variantkonvertering',
'tog-ccmeonemails'            => 'Send meg kopier av e-poster jeg sender til andre brukere',
'tog-diffonly'                => 'Ikke vis sideinnhold under differ',

'underline-always'  => 'Alltid',
'underline-never'   => 'Aldri',
'underline-default' => 'Bruk nettleserstandard',

'skinpreview' => '(forhåndsvisning)',

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
'march'         => 'mars',
'april'         => 'april',
'may_long'      => 'mai',
'june'          => 'juni',
'july'          => 'juli',
'august'        => 'august',
'september'     => 'september',
'october'       => 'oktober',
'november'      => 'november',
'december'      => 'desember',
'january-gen'   => 'januar',
'february-gen'  => 'februar',
'march-gen'     => 'mars',
'april-gen'     => 'april',
'may-gen'       => 'mai',
'june-gen'      => 'juni',
'july-gen'      => 'juli',
'august-gen'    => 'august',
'september-gen' => 'september',
'october-gen'   => 'oktober',
'november-gen'  => 'november',
'december-gen'  => 'desember',
'jan'           => 'jan',
'feb'           => 'feb',
'mar'           => 'mar',
'apr'           => 'apr',
'may'           => 'mai',
'jun'           => 'jun',
'jul'           => 'jul',
'aug'           => 'aug',
'sep'           => 'sep',
'oct'           => 'okt',
'nov'           => 'nov',
'dec'           => 'des',

# Bits of text used by many pages
'categories'            => 'Kategorier',
'pagecategories'        => '{{PLURAL:$1|Kategori|Kategorier}}',
'category_header'       => 'Artikler i kategorien «$1»',
'subcategories'         => 'Underkategorier',
'category-media-header' => 'Filer i kategorien «$1»',
'category-empty'        => "''Denne kategorien ineholder for tiden ingen artikler eller filer.''",

'mainpagetext'      => "<big>'''MediaWiki-programvaren er nå installert.'''</big>",
'mainpagedocfooter' => 'Se [http://meta.wikimedia.org/wiki/Help:Contents brukerveiledningen] for informasjon om hvordan du bruker wiki-programvaren.

==Å starte==
*[http://www.mediawiki.org/wiki/Manual:Configuration_settings Konfigurasjonsliste]
*[http://www.mediawiki.org/wiki/Manual:FAQ Ofte stilte spørsmål]
*[http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki e-postliste]',

'about'          => 'Om',
'article'        => 'Artikkel',
'newwindow'      => '(åpner i nytt vindu)',
'cancel'         => 'Avbryt',
'qbfind'         => 'Finn',
'qbbrowse'       => 'Søk',
'qbedit'         => 'Rediger',
'qbpageoptions'  => 'Sideinnstillinger',
'qbpageinfo'     => 'Sideinformasjon',
'qbmyoptions'    => 'Egne innstillinger',
'qbspecialpages' => 'Spesialsider',
'moredotdotdot'  => 'Mer…',
'mypage'         => 'Min side',
'mytalk'         => 'Min diskusjonsside',
'anontalk'       => 'Brukerdiskusjon for denne IP-adressa',
'navigation'     => 'Navigasjon',

# Metadata in edit box
'metadata_help' => 'Metadata:',

'errorpagetitle'    => 'Feil',
'returnto'          => 'Tilbake til $1.',
'tagline'           => 'Fra {{SITENAME}}',
'help'              => 'Hjelp',
'search'            => 'Søk',
'searchbutton'      => 'Søk',
'go'                => 'Gå',
'searcharticle'     => 'Gå',
'history'           => 'Historikk',
'history_short'     => 'Historikk',
'updatedmarker'     => 'oppdatert siden mitt forrige besøk',
'info_short'        => 'Informasjon',
'printableversion'  => 'Utskriftsvennlig versjon',
'permalink'         => 'Permanent lenke',
'print'             => 'Skriv ut',
'edit'              => 'Rediger',
'editthispage'      => 'Rediger siden',
'delete'            => 'Slett',
'deletethispage'    => 'Slett side',
'undelete_short'    => 'Gjenopprett {{PLURAL:én revisjon|$1 revisjoner}}',
'protect'           => 'Lås',
'protect_change'    => 'endre beskyttelse',
'protectthispage'   => 'Lås siden',
'unprotect'         => 'Åpne',
'unprotectthispage' => 'Åpne siden',
'newpage'           => 'Ny side',
'talkpage'          => 'Diskuter siden',
'talkpagelinktext'  => 'Diskusjon',
'specialpage'       => 'Spesialside',
'personaltools'     => 'Personlige verktøy',
'postcomment'       => 'Legg til en kommentar',
'articlepage'       => 'Vis artikkel',
'talk'              => 'Diskusjon',
'views'             => 'Visninger',
'toolbox'           => 'Verktøy',
'userpage'          => 'Vis brukerside',
'projectpage'       => 'Vis prosjektside',
'imagepage'         => 'Bildeside',
'mediawikipage'     => 'Vis beskjedside',
'templatepage'      => 'Vis mal',
'viewhelppage'      => 'Vis hjelpeside',
'categorypage'      => 'Vis kategori',
'viewtalkpage'      => 'Vis diskusjon',
'otherlanguages'    => 'Andre språk',
'redirectedfrom'    => '(Omdirigert fra $1)',
'redirectpagesub'   => 'Omdirigeringsside',
'lastmodifiedat'    => 'Denne siden ble sist endret $2, $1.', # $1 date, $2 time
'viewcount'         => 'Denne siden er vist $1 {{plural:$1|gang|ganger}}.',
'protectedpage'     => 'Låst side',
'jumpto'            => 'Gå til:',
'jumptonavigation'  => 'navigasjon',
'jumptosearch'      => 'søk',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Om {{SITENAME}}',
'aboutpage'         => 'Project:Om',
'bugreports'        => 'Feilmeldinger',
'bugreportspage'    => 'Project:Feilmeldinger',
'copyright'         => 'Innholdet er tilgjengelig under $1.',
'copyrightpagename' => 'Opphavsrett',
'copyrightpage'     => '{{ns:project}}:Opphavsrett',
'currentevents'     => 'Aktuelt',
'currentevents-url' => 'Project:Aktuelt',
'disclaimers'       => 'Opphavsrett',
'disclaimerpage'    => 'Project:Opphavsrett',
'edithelp'          => 'Redigeringshjelp',
'edithelppage'      => 'Help:Hvordan redigere',
'faq'               => 'Ofte stilte spørsmål',
'faqpage'           => 'Project:Ofte stilte spørsmål',
'helppage'          => 'Help:Hjelp',
'mainpage'          => 'Hovedside',
'policy-url'        => 'Project:Retningslinjer',
'portal'            => 'Prosjektportal',
'portal-url'        => 'Project:Prosjektportal',
'privacy'           => 'Personvern',
'privacypage'       => 'Project:Personvern',
'sitesupport'       => 'Donasjoner',
'sitesupport-url'   => 'Project:Donasjoner',

'badaccess'        => 'Rettighetsfeil',
'badaccess-group0' => 'Du har ikke tilgang til å utføre handlingen du prøvde på.',
'badaccess-group1' => 'Handlingen du prøvde å utføre er begrenset til medlemmer i gruppa $1.',
'badaccess-group2' => 'Handlingen du prøvde å utføre kan kun utføres av medlemmer i en av gruppene $1.',
'badaccess-groups' => 'Handlingen du prøvde å utføre kan kun utføres av brukere i en av gruppene $1.',

'versionrequired'     => 'Versjon $1 av MediaWiki påtrengt',
'versionrequiredtext' => 'Versjon $1 av MediaWiki er nødvendig for å bruke denne siden. Se [[Special:Version]]',

'ok'                      => 'OK',
'retrievedfrom'           => 'Hentet fra «$1»',
'youhavenewmessages'      => 'Du har $1 ($2).',
'newmessageslink'         => 'nye meldinger',
'newmessagesdifflink'     => 'forskjell fra forrige beskjed',
'youhavenewmessagesmulti' => 'Du har nye beskjeder på $1',
'editsection'             => 'rediger',
'editold'                 => 'rediger',
'editsectionhint'         => 'Rediger seksjon: $1',
'toc'                     => 'Innhold',
'showtoc'                 => 'vis',
'hidetoc'                 => 'skjul',
'thisisdeleted'           => 'Se eller gjenopprett $1?',
'viewdeleted'             => 'Vis $1?',
'restorelink'             => '{{plural:$1|én slettet revisjon|$1 slettede revisjoner}}',
'feedlinks'               => 'Mating:',
'feed-invalid'            => 'Ugyldig matingstype.',
'site-rss-feed'           => '$1 RSS-føde',
'site-atom-feed'          => '$1 Atom-føde',
'page-rss-feed'           => '«$1» RSS-føde',
'page-atom-feed'          => '«$1» Atom-føde',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Artikkel',
'nstab-user'      => 'Brukerside',
'nstab-media'     => 'Mediaside',
'nstab-special'   => 'Spesial',
'nstab-project'   => 'Prosjektside',
'nstab-image'     => 'Fil',
'nstab-mediawiki' => 'Melding',
'nstab-template'  => 'Mal',
'nstab-help'      => 'Hjelp',
'nstab-category'  => 'Kategori',

# Main script and global functions
'nosuchaction'      => 'Funksjonen finnes ikke',
'nosuchactiontext'  => 'MediaWiki-programvaren kjenner ikke igjen funksjonen som er spesifisert i URL-en.',
'nosuchspecialpage' => 'En slik spesialside finnes ikke',
'nospecialpagetext' => 'Du har bedt om en ugyldig spesialside; en liste over gyldige spesialsider finnes på [[Special:Specialpages]].',

# General errors
'error'                => 'Feil',
'databaseerror'        => 'databasefeil',
'dberrortext'          => 'Det har skjedd en syntaksfeil i databasen. Den sist forsøkte forespørsel var: <blockquote><tt>$1</tt></blockquote> fra funksjonen «<tt>$2</tt>». MySQL returnerte feilen «<tt>$3: $4</tt>».',
'dberrortextcl'        => 'Det har skjedd en syntaksfeil i databasen. Den sist forsøkte forespørselen var: «$1» fra funksjonen «$2». MySQL returnerte feilen «$3: $4».',
'noconnect'            => 'Beklager! Wikien har tekniske problemer, og kan ikke kontakte databasetjeneren.<br />$1',
'nodb'                 => 'Kunne ikke velge databasen $1',
'cachederror'          => 'Det følgende er en lagret kopi av den ønskede siden, og er ikke nødvendigvis oppdatert.',
'laggedslavemode'      => 'Advarsel: Dette kan være en eldre versjon av siden.',
'readonly'             => 'Databasen er skrivebeskyttet',
'enterlockreason'      => 'Skriv en begrunnelse for skrivebeskyttelsen, inkludert et estimat angående når den vil bli opphevet',
'readonlytext'         => 'Databasen er for øyeblikket skrivebeskyttet, sannsynligvis på grunn av rutinemessig vedlikehold.

Administratoren som låste databasen ga forklaringen: $1',
'missingarticle'       => 'Databasen fant ikke teksten til en side den skulle ha funnet, «$1».

Hvis dette er en nylig endret side vil det vanligvis hjelpe å prøve igjen
om et minutt eller to. Ellers er det mulig du har fulgt en lenke til en side
som er blitt slettet.

Hvis dette ikke er tilfelle er det mulig du har støtt på en programfeil.
Send en rapport om dette til en administrator, og inkluder adressen (URL-en)
til siden.',
'readonly_lag'         => 'Databasen er automatisk skrivebeskyttet så slavetjenerne kan ta igjen mestertjeneren',
'internalerror'        => 'Intern feil',
'internalerror_info'   => 'Intern feil: $1',
'filecopyerror'        => 'Kunne ikke kopiere fila «$1» til «$2».',
'filerenameerror'      => 'Kunne ikke omdøpe filen «$1» til «$2».',
'filedeleteerror'      => 'Kunne ikke slette filen «$1».',
'directorycreateerror' => 'Kunne ikke opprette mappe «$1».',
'filenotfound'         => 'Kunne ikke finne filen «$1».',
'fileexistserror'      => 'Kunne ikke skrive til filen «$1»: filen eksisterer',
'unexpected'           => 'Uventet verdi: «$1»=«$2».',
'formerror'            => 'Feil: kunne ikke sende skjema',
'badarticleerror'      => 'Handlingen kan ikke utføres på denne siden.',
'cannotdelete'         => 'Kunne ikke slette fila (den kan allerede være slettet av noen andre).',
'badtitle'             => 'Ugyldig tittel',
'badtitletext'         => 'Den ønskede tittelen var ugyldig, tom eller galt lenket fra et annet språk.',
'perfdisabled'         => 'Denne funksjonen er midlertidig utilgjengelig av vedlikeholdsgrunner.',
'perfcached'           => 'Følgende data er en lagret kopi, og ikke nødvendigvis den siste versjonen i databasen.',
'perfcachedts'         => 'Følgende data er en lagret kopi, og ble sist oppdatert $1.',
'querypage-no-updates' => 'Oppdateringer for denne siden er slått av. Data her vil ikke bli gjenoppfrisket.',
'wrong_wfQuery_params' => 'Gale paramtere til wfQuery()<br />
Funksjon: $1<br />
Spørring: $2',
'viewsource'           => 'Vis kildekode',
'viewsourcefor'        => 'for $1',
'actionthrottled'      => 'Handlingsgrense overskredet',
'actionthrottledtext'  => 'For å beskytte mot spam, kan du ikke utføre denne handlingen for mange ganger i løpet av et kort tidssrom, og du har overskredet denne grensen. Prøv igjen om noen minutter.',
'protectedpagetext'    => 'Denne siden har blitt låst for redigeringer.',
'viewsourcetext'       => 'Du kan se og kopiere kilden til denne siden:',
'protectedinterface'   => 'Denne siden viser brukergrensesnittet for programvaren, og er låst for å hindre misbruk.',
'editinginterface'     => "'''Advarsel:''' Du redigerer en side som brukes i grensesnittet for programvaren. Endringer på denne siden vil påvirke hvordan grensesnittet vil se ut.",
'sqlhidden'            => '(SQL-spørring skjult)',
'cascadeprotected'     => 'Denne siden har blitt låst for redigering, fordi den inkluderes i følgende sider, som er låst med «dypbeskyttelse» slått på:<!--{{PLURAL:$1}}-->
$2',
'namespaceprotected'   => "Du har ikke tillatelse til å redigere sider i navnerommet '''$1'''.",
'customcssjsprotected' => 'Du har ikke tillatelse til å redigere denne siden, fordi den inneholder en annen brukers personlige innstillinger.',
'ns-specialprotected'  => 'Sier i navnerommet {{ns:special}} kan ikke redigeres.',

# Login and logout pages
'logouttitle'                => 'Logg ut',
'logouttext'                 => '<strong>Du er nå logget ut.</strong><br />
Du kan fortsette å bruke {{SITENAME}} anonymt, eller logge inn igjen som samme eller annen bruker.',
'welcomecreation'            => '==Velkommen, $1!==

Brukerkontoen din har blitt opprettet. Ikke glem å endre [[Special:Preferences|innstillingene dine]].',
'loginpagetitle'             => 'Logg inn',
'yourname'                   => 'Brukernavn',
'yourpassword'               => 'Passord',
'yourpasswordagain'          => 'Gjenta passord',
'remembermypassword'         => 'Husk passordet',
'yourdomainname'             => 'Ditt domene',
'externaldberror'            => 'Det var en ekstern autentifiseringsfeil, eller du kan ikke oppdatere din eksterne konto.',
'loginproblem'               => '<strong>Du ble ikke logget inn.</strong><br />Prøv igjen!',
'login'                      => 'Logg inn',
'loginprompt'                => 'Du må ha slått på cookies for å logge in på {{SITENAME}}.',
'userlogin'                  => 'Logg inn eller registrer deg',
'logout'                     => 'Logg ut',
'userlogout'                 => 'Logg ut',
'notloggedin'                => 'Ikke innlogget',
'nologin'                    => 'Er du ikke registrert? $1.',
'nologinlink'                => 'Registrer deg',
'createaccount'              => 'Opprett ny konto',
'gotaccount'                 => 'Har du allerede et brukernavn? $1.',
'gotaccountlink'             => 'Logg inn',
'createaccountmail'          => 'per e-post',
'badretype'                  => 'Passordene samsvarte ikke.',
'userexists'                 => 'Brukernavnet er allerede i bruk. Velg et nytt.',
'youremail'                  => 'E-postadresse',
'username'                   => 'Brukernavn:',
'uid'                        => 'Bruker-ID:',
'yourrealname'               => 'Virkelig navn *',
'yourlanguage'               => 'Språk:',
'yourvariant'                => 'Variant',
'yournick'                   => 'Signatur',
'badsig'                     => 'Ugyldig råsignatur; sjekk HTML-tagger.',
'badsiglength'               => 'Brukernavn for langt; må være kortere enn $1 tegn.',
'email'                      => 'E-post',
'prefs-help-realname'        => '* Virkelig navn (valgfritt): dersom du velger å oppgi navnet, vil det bli brukt til å kreditere deg for ditt arbeid.',
'loginerror'                 => 'Innloggingsfeil',
'prefs-help-email'           => '* E-post (valgfritt): Muliggjør at andre kan kontakte deg uten at identiteten din blir avslørt.',
'prefs-help-email-required'  => 'E-postadresse er påkrevd.',
'nocookiesnew'               => 'Din brukerkonto er nå opprettet, men du har ikke logget på. {{SITENAME}} bruker informasjonskapsler («cookies») for å logge brukere på. Du har slått dem av. Slå dem p åfor å kunne logge på med ditt nye brukernavn og passord.',
'nocookieslogin'             => '{{SITENAME}} bruker informasjonskapsler («cookies») for å logge brukere på. Du har slått dem av. Slå dem på og prøv igjen.',
'noname'                     => 'Du har ikke oppgitt et gyldig brukernavn.',
'loginsuccesstitle'          => 'Du er nå logget inn',
'loginsuccess'               => 'Du er nå logget inn på {{SITENAME}} som «$1».',
'nosuchuser'                 => 'Det eksisterer ingen bruker ved navn «$1». Sjekk stavemåten eller opprett en ny konto.',
'nosuchusershort'            => 'Det finnes ingen bruker ved navn «$1». Kontroller stavemåten.',
'nouserspecified'            => 'Du må oppgi et brukernavn.',
'wrongpassword'              => 'Du har oppgitt et ugyldig passord. Prøv igjen.',
'wrongpasswordempty'         => 'Du oppga ikke noe passord. Prøv igjen.',
'passwordtooshort'           => 'Passordet ditt er for kort. Det må ha minst $1 tegn.',
'mailmypassword'             => 'Send nytt passord.',
'passwordremindertitle'      => 'Nytt passord fra {{SITENAME}}',
'passwordremindertext'       => 'Noen (antagelig deg, fra IP-adressa $1) ba oss sende deg et nytt passord til {{SITENAME}} ($4). Passordet for kontoen «$2» er nå «$3». Du burde logge inn og endre pasordet nå.

Dersom noen andre gjorde denne forespørselen eller om du kom på passordet og ikke lenger ønsker å endre det, kan du ignorere denne beskjeden og fortsette å bruke det gamle passordet.',
'noemail'                    => 'Det er ikke registrert noen e-postadresse for brukeren «$1».',
'passwordsent'               => 'Et nytt passord har blitt send til e-postadressa registrert på bruker «$1». Logg inn når du har mottatt det nye passordet.',
'blocked-mailpassword'       => 'IP-adressa di er blokkert fra å redigere, og kan følgelig ikke bruke denne funksjonen, for å forhindre misbruk.',
'eauthentsent'               => 'En bekreftelsesmelding har blitt sendt til gitte e-postadresse. Før andre e-poster kan sendes til kontoen, må du følge instruksjonene i e-posten for å bekrefte at kontoen faktisk er din.',
'throttled-mailpassword'     => 'En passordpåminnelse har blitt sendt for mindre enn $1 timer siden. For å forhindre misbruk kan kun én passordpåminnelse sendes per $1 timer.',
'mailerror'                  => 'Feil under sending av e-post: $1',
'acct_creation_throttle_hit' => 'Beklager, du har allerede opprettet $1 kontoer. Du kan ikke opprette flere.',
'emailauthenticated'         => 'E-postadressa di ble bekreftet $1.',
'emailnotauthenticated'      => "E-postadressa di er '''ikke bekreftet'''. Ingen e-poster vil bli sendt for følgende tjenester.",
'noemailprefs'               => '<strong>Ingen e-postadresse er oppgitt</strong>, så følgende funksjoner vil ikke fungere.',
'emailconfirmlink'           => 'Bekreft e-postadressa di.',
'invalidemailaddress'        => 'E-postadressa kan ikke aksepteres, fordi den er ugyldig formatert. Skriv inn en fungerende e-postadresse eller tøm feltet.',
'accountcreated'             => 'Brukerkonto opprettet',
'accountcreatedtext'         => 'Brukerkonto for $1 har blitt opprettet.',
'createaccount-title'        => 'Kontoopretting på {{SITENAME}}',
'createaccount-text'         => 'Noen ($1) opprettet en konto for $2 på {{SITENAME}} ($4). Passordet for «$2» er «$3». Du burde logge inn og endre passordet nå.

Du kan ignorere denne beskjeden dersom kontoen feilaktig ble opprettet.',
'loginlanguagelabel'         => 'Språk: $1',

# Password reset dialog
'resetpass'               => 'Resett kontopassord',
'resetpass_announce'      => 'Du logget inn med en midlertidig e-postkode. For å fullføre innloggingen, må du oppgi et nytt passord her:',
'resetpass_text'          => '<!-- Legg til tekst her -->',
'resetpass_header'        => 'Resett passord',
'resetpass_submit'        => 'Angi passord og logg inn',
'resetpass_success'       => 'Passordet ditt har blitt endret! Logger inn…',
'resetpass_bad_temporary' => 'Ugyldig midlertidig passord. Du kan allerede ha endret passordet, eller bedt om et nytt midlertidig passord.',
'resetpass_forbidden'     => 'Passord kan ikke endres på denne wikien',
'resetpass_missing'       => 'Ingen skjemadata.',

# Edit page toolbar
'bold_sample'     => 'Fet tekst',
'bold_tip'        => 'Fet tekst',
'italic_sample'   => 'Kursiv tekst',
'italic_tip'      => 'Kursiv tekst',
'link_sample'     => 'Lenketittel',
'link_tip'        => 'Intern lenke',
'extlink_sample'  => '{{SERVER}} lenketittel',
'extlink_tip'     => 'Ekstern lenke (husk prefikset http://)',
'headline_sample' => 'Overskrift',
'headline_tip'    => 'Overskrift',
'math_sample'     => 'Sett inn formel her',
'math_tip'        => 'Matematisk formel (LaTeX)',
'nowiki_sample'   => 'Sett inn uformatert tekst her',
'nowiki_tip'      => 'Ignorer wikiformatering',
'image_sample'    => 'Eksempel.jpg',
'image_tip'       => 'Bilde',
'media_sample'    => 'Eksempel.ogg',
'media_tip'       => 'Mediafillenke',
'sig_tip'         => 'Din signatur med dato',
'hr_tip'          => 'Horisontal linje',

# Edit pages
'summary'                   => 'Redigeringsforklaring',
'subject'                   => 'Overskrift',
'minoredit'                 => 'Mindre endring',
'watchthis'                 => 'Overvåk side',
'savearticle'               => 'Lagre siden',
'preview'                   => 'Forhåndsvisning',
'showpreview'               => 'Forhåndsvisning',
'showlivepreview'           => 'Levende forhåndsvisning',
'showdiff'                  => 'Vis endringer',
'anoneditwarning'           => "'''Advarsel:''' Du er ikke logget inn. IP-adressa di vil bli bevart i sidens redigeringshistorikk.",
'missingsummary'            => "'''Påminnelse:''' Du har ikke lagt inn en [[Help:Redigeringsforklaring|redigeringsforklaring]]. velger du ''Lagre siden'' en gang til blir endringene lagret uten forklaring.",
'missingcommenttext'        => 'Vennligst legg inn en kommentar under.',
'missingcommentheader'      => "'''Merk:''' Du har ikke angitt et emne/overskrift for denne kommentaren. Om du trykker Lagre igjen, vil redigeringen din bli lagret uten en.",
'summary-preview'           => 'Forhåndsvisning av sammendrag',
'subject-preview'           => 'Forhåndsvisning av emne/overskrift',
'blockedtitle'              => 'Brukeren er blokkert',
'blockedtext'               => "<big>'''Ditt brukernavn eller din IP-adresse har blitt blokkert.'''</big>

Blokkeringen ble utført av $1. Grunnen som ble oppgitt var ''$2''.

Blokkeringen utgår: $6

Du kan kontakte $1 eller en annen [[{{MediaWiki:Grouppage-sysop}}|administrator]] for å diskutere blokkeringen. Du kan ikke bruke «E-post til denne brukeren-funksjonen med mindre du har oppgitt en gyldig e-postadresse i [[Special:Preferences|innstillingene dine]]. Din nåværende IP-adresse er $3, og blokkerings-ID-en er #$5. Vennligst inkluder begge disse ved henvendelser.",
'autoblockedtext'           => "Din IP-adresse har blitt automatisk blokkert fordi den ble brukt av en annen bruker som ble blokkert av $1.
Den oppgitte grunnen var:

:'''$2'''

Blokken utgår: $6

Du kan kontakte $1 eller en av de andre [[{{MediaWiki:Grouppage-sysop}}|administratorene]] for å diskutere blokkeringen.

Merk at du ikke kan bruke «E-post til denne brukeren»-funksjonen med mindre du har registrert en gyldig e-postadresse i [[Special:Preferences|innstillingene dine]].

Din blokkerings-ID er $5. Vennligst inkluder denne ID-en i din forespørsel.",
'blockednoreason'           => 'ingen grunn gitt',
'blockedoriginalsource'     => "Kilden til '''$1''' vises nedenfor:",
'blockededitsource'         => "Teksten i '''dine endringer''' på '''$1''' vises nedenfor:",
'whitelistedittitle'        => 'Innlogging kreves for å redigere',
'whitelistedittext'         => 'Du må $1 for å redigere artikler.',
'whitelistreadtitle'        => 'Innlogging kreves for å lese',
'whitelistreadtext'         => 'Du må [[Special:Userlogin|logge inn]] for å lese artikler.',
'whitelistacctitle'         => 'Du har ikke adgang til å opprette en konto',
'whitelistacctext'          => 'For å få adgang til å opprette kontoer må du [[Special:Userlogin|logge inn]] og ha riktige rettigheter.',
'confirmedittitle'          => 'E-postbekreftelse nødvendig før du kan redigere',
'confirmedittext'           => 'Du må bekrefte e-postadressa di før du kan redigere sider. Vennligst oppgi og valider e-postadressa di via [[Special:Preferences|innstillingene dine]].',
'nosuchsectiontitle'        => 'Ingen slik seksjon',
'nosuchsectiontext'         => 'Du prøvde å redigere en seksjon som ikke eksisterer. Siden det ikke finnes noen seksjon «$1», er det ikke mulig å lagre endringen din.',
'loginreqtitle'             => 'Innlogging kreves',
'loginreqlink'              => 'innlogging',
'loginreqpagetext'          => 'Du må $1 for å se andre sider.',
'accmailtitle'              => 'Passord sendt',
'accmailtext'               => 'Passordet for «$1» har blitt sendt til $2.',
'newarticle'                => '(ny)',
'newarticletext'            => "Du har fulgt en lenke til en side som ikke finnes ennå. For å opprette siden, start å skrive i boksen under (se [[{{MediaWiki:Helppage}}|hjelpesiden]] for mer informasjon). Om du kom hit ved en feil, bare trykk på nettleserens '''tilbake'''-knapp.",
'anontalkpagetext'          => "----
''Dette er en diskusjonsside for en anonym bruker som ikke har opprettet konto eller ikke er logget inn. Vi er derfor nødt til å bruke den numeriske IP-adressa til å identifisere ham eller henne. En IP-adresse kan være delt mellom flere brukere. Hvis du er en anonym bruker og synes at du har fått irrelevante kommentarer på en slik side, [[Special:Userlogin|logg på]] så vi unngår framtidige forvekslinger med andre anonyme brukere.''",
'noarticletext'             => 'Det er ikke noe tekst på denne siden. Du kan [[Special:Search/{{PAGENAME}}|søke etter siden]] i andre sider, eller [{{fullurl:{{FULLPAGENAME}}|action=edit}} redigere siden].',
'userpage-userdoesnotexist' => 'Brukerkontoen «$1» er ikke registrert. Sjekk om du ønsker å opprette/redigere denne siden.',
'clearyourcache'            => "'''NB:''' Etter å ha lagret må du tømme nettleserens mellomlager («cache») for å kunne se endringene: '''Mozilla/Safari/Konqueror:''' hold nede ''Shift'' mens du klikker på ''Reload'' (eller trykk ''Ctrl-Shift-R''), '''IE:''' trykk ''Ctrl-F5'', '''Opera:''' trykk ''F5''.",
'usercssjsyoucanpreview'    => '<strong>Tips:</strong> Bruk «Forhåndsvisning»-knappen for å teste din nye CSS/JS før du lagrer.',
'usercsspreview'            => "'''Husk at dette bare er en forhåndsvisning av din bruker-CSS og at den ikke er lagret!'''",
'userjspreview'             => "'''Husk at dette bare er en test eller forhåndsvisning av ditt bruker-JavaScript, og det ikke er lagret!'''",
'userinvalidcssjstitle'     => "'''Advarsel:''' Det finnes ikke noe utseende ved navn «$1». Husk at .css- og .js-sider bruker titler i små bokstaver, for eksempel {{ns:user}}:Eksempel/monobook.css, ikke {{ns:user}}:Eksempel/Monobook.css",
'updated'                   => '(Oppdatert)',
'note'                      => '<strong>Merk:</strong>',
'previewnote'               => '<strong>Dette er bare en forhåndsvisning; endringer har ikke blitt lagret!</strong>',
'previewconflict'           => 'Slik vil teksten i redigeringsvinduet se ut dersom du lagrer den.',
'session_fail_preview'      => '<strong>Beklager! Redigeringen din kunne ikke lagres. Vennligst prøv igjen. Om det fortsetter å gå galt, prøv å logge ut og så inn igjen.</strong>',
'session_fail_preview_html' => "<strong>Beklager! Redigeringen din kunne ikke lagres på grunn av tap av sesjonsdata.</strong>

''Fordi denne wikien har rå HTML slått på, er forhåndsvisningen skjult for å forhindre JavaScript-angrep.''",
'token_suffix_mismatch'     => '<strong>Redigeringen din har blitt avvist fordi klienten din ikke hadde punktasjonstegn i redigeringsteksten. Redigeringen har blitt avvist for å hindre ødeleggelse av artikkelteksten. Dette forekommer av og til når man bruker vevbaserte aonynyme proxytjenester.</strong>',
'editing'                   => 'Redigerer $1',
'editinguser'               => 'Redigerer brukeren <b>$1</b>',
'editingsection'            => 'Redigerer $1 (seksjon)',
'editingcomment'            => 'Redigerer $1 (kommentar)',
'editconflict'              => 'Redigeringskonflikt: $1',
'explainconflict'           => 'Noen andre har endret teksten siden du begynte å redigere.
Den øverste boksen inneholder den nåværende tekst.
Dine endringer vises i den nederste boksen.
Du er nødt til å flette dine endringer sammen med den nåværende teksten.
<b>Kun</b> teksten i den øverste tekstboksen vil bli lagret når du
trykker «Lagre siden».<br />',
'yourtext'                  => 'Din tekst',
'storedversion'             => 'Den lagrede versjonen',
'nonunicodebrowser'         => '<strong>ADVARSEL: Nettleseren din har ikke støtte for Unicode. Skru det på før du begynner å redigere artikler.</strong>',
'editingold'                => "'''ADVARSEL: Du redigerer en gammel versjon av denne siden. Hvis du lagrer den, vil alle endringer foretatt siden denne versjonen bli overskrevet.'''",
'yourdiff'                  => 'Forskjeller',
'copyrightwarning'          => 'Vennligst merk at alle bidrag til {{SITENAME}} anses som utgitt under $2 (se $1 for detaljer). Om du ikke vil at dine bidrag skal kunne redigeres og distribuert fritt, ikke legg det til her.<br />
Du lover også at du har skrevet dette selv, eller kopiert det fra en ressurs som er i public domain eller lignende. <strong>IKKE LEGG TIL OPPHAVSBESKYTTET MATERIALE UTEN TILLATELSE!</strong>',
'copyrightwarning2'         => 'Vennligst merk at alle bidrag til {{SITENAME}} kan bli redigert, endret eller fjernet av andre bidragsytere. Om du ikke vil at dine bidrag skal kunne redigeres fritt, ikke legg det til her.<br />
Du lover også at du har skrevet dette selv, eller kopiert det fra en ressurs som er i public domain eller lignende (se $1 for detaljer). <strong>IKKE LEGG TIL OPPHAVSBESKYTTET MATERIALE UTEN TILLATELSE!</strong>',
'longpagewarning'           => '<strong>ADVARSEL: Denne siden er $1&nbsp;kB lang; noen eldre nettlesere kan ha problemer med å redigere sider som nærmer seg eller er lengre enn 32&nbsp;kB. Overvei om ikke siden kan deles opp i mindre deler.</strong>',
'longpageerror'             => '<strong>FEIL: Teksten du har forsøkt å lagre er $1&nbsp;kB lang, dvs. lenger enn det maksimale $2&nbsp;kB. Den kan ikke lagres.</strong>',
'readonlywarning'           => '<strong>ADVARSEL: Databasen er låst på grunn av vedlikehold,
så du kan ikke lagre dine endringer akkurat nå. Det kan være en god idé å
kopiere teksten din til en tekstfil, så du kan lagre den til senere.</strong>',
'protectedpagewarning'      => '<strong>ADVARSEL: Denne siden er låst, slik at kun brukere med administratorrettigheter kan redigere den.</strong>',
'semiprotectedpagewarning'  => "'''Merk:''' Denne siden har blitt låst slik at kun registrerte brukere kan endre den. Nyopprettede og anonyme brukere kan ikke redigere.",
'cascadeprotectedwarning'   => "'''Advarsel:''' Denne siden har blitt låst slik at kun brukere med administratorrettigheter kan redigere den, fordi den inkluderes i følgende dypbeskyttede sider:<!--{{PLURAL:$1}}-->",
'templatesused'             => 'Maler i bruk på denne siden:',
'templatesusedpreview'      => 'Maler som brukes i denne forhåndsvisningen:',
'templatesusedsection'      => 'Maler brukt i denne seksjonen:',
'template-protected'        => '(beskyttet)',
'template-semiprotected'    => '(halvbeskyttet)',
'edittools'                 => '<!-- Teksten her vil vises under redigerings- og opplastingsboksene. -->',
'nocreatetitle'             => 'Sideoppretting er begrenset',
'nocreatetext'              => 'Denne siden har begrensede muligheter for oppretting av nye sider. Du kan gå tilbake og redigere en eksisterende side, eller [[Special:Userlogin|logge inn eller opprette en ny konto]].',
'nocreate-loggedin'         => 'Du har ikke tillatelse til å opprette sider på denne wikien.',
'permissionserrors'         => 'Tilgangsfeil',
'permissionserrorstext'     => 'Du har ikke tillatelse til å utføre dette, av følgende {{PLURAL:$1|grunn|grunner}}:',
'recreate-deleted-warn'     => "'''Advarsel: Du gjenskaper en side som tidligere har blitt slettet.'''

Du burde vurdere hvorvidt det er passende å fortsette å redigere denne siden. Slettingsloggen for denne siden gjengis her:",

# "Undo" feature
'undo-success' => 'Redigeringen kan omgjøres. Sjekk sammenligningen under for å bekrefte at du vil gjøre dette, og lagre endringene for å fullføre omgjøringen.',
'undo-failure' => 'Redigeringen kunne ikke omgjøres på grunn av konflikterende etterfølgende redigeringer.',
'undo-summary' => 'Fjerner revisjon $1 av [[Special:Contributions/$2]] ([[User talk:$2|Brukerdiskusjon:$2]])',

# Account creation failure
'cantcreateaccounttitle' => 'Kan ikke opprette konto',
'cantcreateaccount-text' => "Kontoopretting fra denne IP-adressen (<b>$1</b>) har blitt blokkert av [[User:$3|$3]].

Grunnen som ble oppgitt av $3 er ''$2''",

# History pages
'viewpagelogs'        => 'Vis logger for denne siden',
'nohistory'           => 'Denne siden har ingen historikk.',
'revnotfound'         => 'Versjonen er ikke funnet',
'revnotfoundtext'     => 'Den gamle versjon av siden du etterspurte finnes ikke. Kontroller adressa du brukte for å få adgang til denne siden.',
'loadhist'            => 'Laster historikk',
'currentrev'          => 'Nåværende versjon',
'revisionasof'        => 'Versjonen fra $1',
'revision-info'       => 'Revisjon per $1 av $2',
'previousrevision'    => '← Eldre versjon',
'nextrevision'        => 'Nyere versjon →',
'currentrevisionlink' => 'vis nåværende versjon',
'cur'                 => 'nå',
'next'                => 'neste',
'last'                => 'forrige',
'orig'                => 'original',
'page_first'          => 'først',
'page_last'           => 'sist',
'histlegend'          => 'Forklaring: (nå) = forskjell fra nåværende versjon, (forrige) = forskjell fra forrige versjon, M = mindre endring.',
'deletedrev'          => '[slettet]',
'histfirst'           => 'Første',
'histlast'            => 'Siste',
'historysize'         => '($1 byte)',
'historyempty'        => '(tom)',

# Revision feed
'history-feed-title'          => 'Revisjonshistorikk',
'history-feed-description'    => 'Revisjonshistorikk for denne siden',
'history-feed-item-nocomment' => '$1 på $2', # user at time
'history-feed-empty'          => 'Den etterspurte siden finnes ikke. Den kan ha blitt slettet fra wikien, eller fått et nytt navn. Prøv å [[Special:Search|søke]] etter beslektede sider.',

# Revision deletion
'rev-deleted-comment'         => '(kommentar fjernet)',
'rev-deleted-user'            => '(brukernavn fjernet)',
'rev-deleted-event'           => '(fjernet)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
Denne sidehistorikken har blitt fjernet fra de offentlige arkivene. Det kan være detaljer i [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} slettingsloggen].
</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">
Denne revisjonen har blitt fjernet fra det offentlige arkivet. Som administrator har du mulighet til å se den; det kan være detaljer i [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} slettingsloggen].
</div>',
'rev-delundel'                => 'vis/skjul',
'revisiondelete'              => 'Slett/gjenopprett revisjoner',
'revdelete-nooldid-title'     => 'Ingen målrevisjon',
'revdelete-nooldid-text'      => 'Du har ikke angitt en målrevisjon eller målrevisjoner for denne funksjonen.',
'revdelete-selected'          => "{{PLURAL:$2|Valgt revisjon|Valgte revisjoner}} av '''$1:'''",
'logdelete-selected'          => "{{PLURAL:$2|Valgt loggbegivenhet|Valgte loggelementer}} for '''$1:'''",
'revdelete-text'              => 'Slettede revisjoner vil fortsatt vises i sidehistorikken, men innholdet vil ikke være tilgjengelig for offentligheten.

Andre administratorer på denne wikien vil fortsatt kunne se det skjulte innholdet, og kan gjenopprette det, med mindre videre begrensninger blir gitt av sideoperatørene.',
'revdelete-legend'            => 'Sett revisjonsbegrensninger:',
'revdelete-hide-text'         => 'Skjul revisjonstekst',
'revdelete-hide-name'         => 'Skjul handling og mål',
'revdelete-hide-comment'      => 'Skjul redigeringsforklaring',
'revdelete-hide-user'         => 'Skjul bidragsyters brukernavn eller IP',
'revdelete-hide-restricted'   => 'Disse restriksjonene gjelder også for administratorer',
'revdelete-suppress'          => 'Fjern informasjon også fra administratorer',
'revdelete-hide-image'        => 'Skjul filinnhold',
'revdelete-unsuppress'        => 'Fjern betingelser på gjenopprettede revisjoner',
'revdelete-log'               => 'Loggkommentar:',
'revdelete-submit'            => 'Utfør for valgte revisjoner',
'revdelete-logentry'          => 'endre revisjonssynlighet for [[$1]]',
'logdelete-logentry'          => 'endre hendelsessynlighet for [[$1]]',
'revdelete-logaction'         => '$1 {{PLURAL:$1|revisjon|revisjoner}} satt til modus $2',
'logdelete-logaction'         => '$1 {{plural:$1|hendelse|hendelser}} angående [[$3]] satt til modus $2',
'revdelete-success'           => 'Revisjonssynlighet satt.',
'logdelete-success'           => 'Hendelsessynlighet satt.',

# Oversight log
'oversightlog'    => 'Overoppsynslogg',
'overlogpagetext' => 'Under er en liste over de nyligste slettinger og blokkeringer som involverer innhold skjult for administratorer. Se [[Special:Ipblocklist|IP-blokkeringslista]] for nåværende bannlysninger og blokkeringer.',

# History merging
'mergehistory'                     => 'Flett sidehistorikker',
'mergehistory-header'              => "Denne siden lar deg flette historikken til to sider. Forsikre deg om at denne endringen vil opprettholde historisk sidekontinuitet.

'''I hvert fall den siste revisjonen til kildesiden må forbli.'''",
'mergehistory-box'                 => 'Flett historikken til to sider:',
'mergehistory-from'                => 'Kildeside:',
'mergehistory-into'                => 'Målside:',
'mergehistory-list'                => 'Flettbar redigeringshistorikk',
'mergehistory-merge'               => 'Følgende revisjoner av [[:$1]] kan flettes til [[:$2]]. Du kan velge å flette kun de revisjonene som kom før tidspunktet gitt i tabellen. Merk at bruk av navigasjonslenkene vil resette denne kolonnen.',
'mergehistory-go'                  => 'Vis flettbare redigeringer',
'mergehistory-submit'              => 'Flett revisjoner',
'mergehistory-empty'               => 'Ingen revisjoner kan flettes',
'mergehistory-success'             => '$3 revisjoner av [[:$1]] ble flettet til [[:$2]].',
'mergehistory-fail'                => 'Kunne ikke utføre historikkfletting; vennligst sjekk siden og tidsparameterne igjen.',
'mergehistory-no-source'           => 'Kildesiden $1 finnes ikke.',
'mergehistory-no-destination'      => 'Målsiden $1 finnes ikke.',
'mergehistory-invalid-source'      => 'Kildesiden må ha en gyldig tittel.',
'mergehistory-invalid-destination' => 'Målsiden må ha en gyldig tittel.',

# Merge log
'mergelog'           => 'Flettingslogg',
'pagemerge-logentry' => 'flettet [[$1]] til [[$2]] (revisjoner fram til $3)',
'revertmerge'        => 'Omgjør fletting',
'mergelogpagetext'   => 'Nedenfor er en liste over de nyligste flettingene av sidehistorikker.',

# Diffs
'history-title'           => 'Revisjonshistorikk for «$1»',
'difference'              => '(Forskjeller mellom versjoner)',
'lineno'                  => 'Linje $1:',
'compareselectedversions' => 'Sammenlign valgte versjoner',
'editundo'                => 'omgjør',
'diff-multi'              => '({{PLURAL:$1|Én mellomrevisjon|$1 mellomrevisjoner}} ikke vist.)',

# Search results
'searchresults'         => 'Søkeresultater',
'searchresulttext'      => 'For mer informasjon om søking i {{SITENAME}}, se [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'        => 'Du søkte på «[[$1]]».',
'searchsubtitleinvalid' => 'For forespørsel "$1"',
'noexactmatch'          => "'''Det er ingen side med tittelen «$1».''' Du kan [[:$1|opprette siden]].",
'titlematches'          => 'Artikkeltitler med treff på forespørselen',
'notitlematches'        => 'Ingen artikkeltitler hadde treff på forespørselen',
'textmatches'           => 'Artikkeltekster med treff på forespørselen',
'notextmatches'         => 'Ingen artikkeltekster hadde treff på forespørselen',
'prevn'                 => 'forrige $1',
'nextn'                 => 'neste $1',
'viewprevnext'          => 'Vis ($1) ($2) ($3).',
'showingresults'        => 'Nedenfor vises opptil <b>$1</b> resultater som starter med nummer <b>$2</b>.',
'showingresultsnum'     => "Nedenfor vises '''$3''' resultater som starter med nummer '''$2'''.",
'nonefound'             => "'''Merk:''' Søk uten resultat skyldes ofte at man søker etter alminnelige ord som «i» eller «på», som ikke er indeksert, eller ved å spesifisere mer enn et søkeord (da kun sider som inneholder alle søkeordene blir funnet).",
'powersearch'           => 'Søk',
'powersearchtext'       => 'Søk i navnerom:<br />$1<br />$2 List opp omdirigeringer<br />Søk etter $3 $9',
'searchdisabled'        => 'Søkefunksjonen er slått av. Du kan søke via Google i mellomtiden. Merk at Googles indeksering av {{SITENAME}} muligens er utdatert.',

# Preferences page
'preferences'              => 'Innstillinger',
'mypreferences'            => 'Mine innstillinger',
'prefs-edits'              => 'Antall redigeringer:',
'prefsnologin'             => 'Ikke logget inn',
'prefsnologintext'         => 'Du må være [[Special:Userlogin|logget inn]] for å endre brukerinnstillingene.',
'prefsreset'               => 'Brukerinnstillingene er tilbakestilt.',
'qbsettings'               => 'Brukerinnstillinger for hurtigmeny.',
'qbsettings-none'          => 'Ingen',
'qbsettings-fixedleft'     => 'Fast venstre',
'qbsettings-fixedright'    => 'Fast høyre',
'qbsettings-floatingleft'  => 'Flytende venstre',
'qbsettings-floatingright' => 'Flytende til høyre',
'changepassword'           => 'Endre passord',
'skin'                     => 'Utseende',
'math'                     => 'Matteformler',
'dateformat'               => 'Datoformat',
'datedefault'              => 'Ingen foretrukket',
'datetime'                 => 'Dato og tid',
'math_failure'             => 'Feil i matematikken',
'math_unknown_error'       => 'ukjent feil',
'math_unknown_function'    => 'ukjent funksjon',
'math_lexing_error'        => 'lexerfeil',
'math_syntax_error'        => 'syntaksfeil',
'math_image_error'         => 'PNG-konversjon mislyktes',
'math_bad_tmpdir'          => 'Kan ikke skrive til eller opprette midlertidig mappe',
'math_bad_output'          => 'Kan ikke skrive til eller opprette resultatmappe',
'math_notexvc'             => 'Missing texvc executable; please see math/README to configure.

Mangler kjørbar texvc; vennligst se math/README for å konfigurerer.',
'prefs-personal'           => 'Brukerdata',
'prefs-rc'                 => 'Siste endringer',
'prefs-watchlist'          => 'Overvåkningsliste',
'prefs-watchlist-days'     => 'Antall dager vist i overvåkningslista:',
'prefs-watchlist-edits'    => 'Antall redigeringer som skal vises i utvidet overvåkningsliste:',
'prefs-misc'               => 'Diverse',
'saveprefs'                => 'Lagre innstillinger',
'resetprefs'               => 'Tilbakestill instillinger',
'oldpassword'              => 'Gammelt passord:',
'newpassword'              => 'Nytt passord:',
'retypenew'                => 'Gjenta nytt passord:',
'textboxsize'              => 'Redigering',
'rows'                     => 'Rader:',
'columns'                  => 'Kolonner',
'searchresultshead'        => 'Søking',
'resultsperpage'           => 'Resultater per side:',
'contextlines'             => 'Linjer per resultat',
'contextchars'             => 'Tegn per linje i resultatet',
'stub-threshold'           => 'Grense for <span class="mw-stub-example">stubblenkeformatering</span>:',
'recentchangesdays'        => 'Antall dager som skal vises i siste endringer:',
'recentchangescount'       => 'Antall titler i «siste endringer»:',
'savedprefs'               => 'Innstillingene har blitt lagret.',
'timezonelegend'           => 'Tidssone',
'timezonetext'             => 'Tast inn antall timer lokaltid differerer fra tjenertiden (UTC).',
'localtime'                => 'Lokaltid',
'timezoneoffset'           => 'Forskjell',
'servertime'               => 'Tjenerens tid er nå',
'guesstimezone'            => 'Hent tidssone fra nettleseren',
'allowemail'               => 'Tillat andre å sende meg e-post',
'defaultns'                => 'Søk i disse navnerommene som standard:',
'default'                  => 'standard',
'files'                    => 'Filer',

# User rights
'userrights-lookup-user'      => 'Ordne brukergrupper',
'userrights-user-editname'    => 'Skriv inn et brukernavn:',
'editusergroup'               => 'Endre brukergrupper',
'userrights-editusergroup'    => 'Rediger brukergrupper',
'saveusergroups'              => 'Lagre brukergrupper',
'userrights-groupsmember'     => 'Medlem av:',
'userrights-groupsavailable'  => 'Tilgjengelige grupper:',
'userrights-groupshelp'       => 'Velg grupper du vil at brukeren skal fjernes fra eller lagt til. Ikke valgte grupper vil ikke bli forandret. Du kan fjerne merkingen av en gruppe med Ctrl + Venstreklikk.',
'userrights-reason'           => 'Endringsgrunn:',
'userrights-available-none'   => 'Du kan ikke endre gruppemedlemsskaper.',
'userrights-available-add'    => 'Du kan legge til brukere i $1.',
'userrights-available-remove' => 'Du kan fjerne brukere fra $1.',

# Groups
'group'               => 'Gruppe:',
'group-autoconfirmed' => 'Autobekreftede brukere',
'group-bot'           => 'Roboter',
'group-sysop'         => 'Administratorer',
'group-bureaucrat'    => 'Byråkrater',
'group-all'           => '(alle)',

'group-autoconfirmed-member' => 'Autobekreftet bruker',
'group-bot-member'           => 'robot',
'group-sysop-member'         => 'administrator',
'group-bureaucrat-member'    => 'byråkrat',

'grouppage-autoconfirmed' => '{{ns:project}}:Autobekreftede brukere',
'grouppage-bot'           => '{{ns:project}}:Roboter',
'grouppage-sysop'         => '{{ns:project}}:Administratorer',
'grouppage-bureaucrat'    => '{{ns:project}}:Byråkrater',

# User rights log
'rightslog'      => 'Rettighetslogg',
'rightslogtext'  => 'Dette er en logg over forandringer i brukerrettigheter.',
'rightslogentry' => 'endret gruppe for $1 fra $2 til $3',
'rightsnone'     => '(ingen)',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|endring|endringer}}',
'recentchanges'                     => 'Siste endringer',
'recentchangestext'                 => 'Vis de siste endringene til denne siden',
'recentchanges-feed-description'    => 'Følg med på siste endringer i denne wikien med denne feed-en.',
'rcnote'                            => 'Nedenfor vises de siste <strong>$1</strong> endringene de siste <strong>$2</strong> dagene, fra $3.',
'rcnotefrom'                        => 'Nedenfor er endringene fra <strong>$2</strong> til <strong>$1</strong> vist.',
'rclistfrom'                        => 'Vis nye endringer med start fra $1',
'rcshowhideminor'                   => '$1 mindre endringer',
'rcshowhidebots'                    => '$1 roboter',
'rcshowhideliu'                     => '$1 innloggede brukere',
'rcshowhideanons'                   => '$1 anonyme brukere',
'rcshowhidepatr'                    => '$1 godkjente endringer',
'rcshowhidemine'                    => '$1 mine endringer',
'rclinks'                           => 'Vis siste $1 endringer i de siste $2 dagene<br />$3',
'diff'                              => 'diff',
'hist'                              => 'hist',
'hide'                              => 'skjul',
'show'                              => 'vis',
'minoreditletter'                   => 'm',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 overvåkende {{plural:$1|bruker|brukere}}]',
'rc_categories'                     => 'Begrens til kategorier (skilletegn: «|»)',
'rc_categories_any'                 => 'Alle',
'newsectionsummary'                 => '/* $1 */ ny seksjon',

# Recent changes linked
'recentchangeslinked'          => 'Relaterte endringer',
'recentchangeslinked-title'    => 'Endringer relatert til $1',
'recentchangeslinked-noresult' => 'Ingen endringer på lenkede sider i den gitte perioden.',
'recentchangeslinked-summary'  => "Denne spesialsiden lister opp alle de siste endringene som har skjedd på sider som lenkes til fra denne. Sider som også er på din overvåkningsliste vises i '''fet skrift'''.",

# Upload
'upload'                      => 'Last opp fil',
'uploadbtn'                   => 'Last opp fil',
'reupload'                    => 'Last opp fil igjen',
'reuploaddesc'                => 'Tilbake til skjemaet for å laste opp filer.',
'uploadnologin'               => 'Ikke logget inn',
'uploadnologintext'           => 'Du må være [[Special:Userlogin|loggett inn]] for å kunne laste opp filer.',
'upload_directory_read_only'  => 'Opplastingsmappa ($1) er ikke skrivbar for tjeneren.',
'uploaderror'                 => 'Feil under opplasting av fil',
'uploadtext'                  => "Bruk skjemaet under for å laste opp filer. For å se eller søke i tidligere opplastede filer, gå til [[Special:Imagelist|fillista]]. Opplastinger lagres også i [[Special:Log/upload|opplastingsloggen]].

For å inkludere et bilde på en side, bruk ei slik lenke:
*'''<nowiki>[[</nowiki>{{ns:image}}:Filnavn.jpg<nowiki>]]</nowiki>'''
*'''<nowiki>[[</nowiki>{{ns:image}}:Filnavn.png|Alternativ tekst<nowiki>]]</nowiki>'''
For å lenke direkte til bildet, skriv:
*'''<nowiki>[[</nowiki>{{ns:media}}:Filnavn.ogg<nowiki>]]</nowiki>'''",
'uploadlog'                   => 'opplastingslogg',
'uploadlogpage'               => 'Opplastingslogg',
'uploadlogpagetext'           => 'Her er en liste over de siste opplastede filene.',
'filename'                    => 'Filnavn',
'filedesc'                    => 'Beskrivelse',
'fileuploadsummary'           => 'Filbeskrivelse:',
'filestatus'                  => 'Opphavsrettsstatus',
'filesource'                  => 'Kilde',
'uploadedfiles'               => 'Filer som er lastet opp',
'ignorewarning'               => 'Lagre fila likevel.',
'ignorewarnings'              => 'Ignorer eventuelle advarsler',
'minlength1'                  => 'Filnavn må være på minst én bokstav.',
'illegalfilename'             => 'Filnavnet «$1» inneholder ugyldige tegn; gi fila et nytt navn og prøv igjen.',
'badfilename'                 => 'Navnet på filen er blitt endret til «$1».',
'filetype-badmime'            => 'Filer av typen «$1» kan ikke lastes opp.',
'filetype-badtype'            => "'''«.$1»''' er en uønsket filtype
: Liste over tillatte filtyper: $2",
'filetype-missing'            => 'Filen har ingen endelsen (som «.jpg»).',
'large-file'                  => 'Det er anbefalt at filen ikke er større enn $1; denne filen er $2.',
'largefileserver'             => 'Denne fila er større enn det tjeneren er konfigurert til å tillate.',
'emptyfile'                   => 'Fila du lastet opp ser ut til å være tom. Dette kan komme av en skrivefeil i filnavnet. Sjekk om du virkelig vil laste opp denne fila.',
'fileexists'                  => 'Ei fil med dette navnet finnes allerede. Sjekk $1 hvis du ikke er sikker på at du vil forandre den.',
'fileexists-extension'        => 'En fil med et lignende navn finnes:<br />
Navnet på din fil: <strong><tt>$1</tt></strong><br />
Navn på eksisterende fil: <strong><tt>$2</tt></strong><br />
Den eneste forskjellen ligger i store/små bokstaver i filendelsen. Vennligst sjekk filene for likheter.',
'fileexists-thumb'            => "<center>'''Eksisterende bilde'''</center>",
'fileexists-thumbnail-yes'    => 'Filen ser ut til å være et bilde av redusert størrelse. Vennligst sjekk filen <strong><tt>$1</tt></strong>.<br />
Om filen du har sjekket er det samme bildet, men i opprinnelig størrelse, er det ikke nødvendig å laste opp en ekstra fil.',
'file-thumbnail-no'           => 'Filnavnet begynner med <strong><tt>$1</tt></strong>. Det virker som om det er et bilde av redusert størrelse. Om du har dette bildet i stor utgave, last opp det, eller endre filnavnet på denne filen.',
'fileexists-forbidden'        => 'En fil med dette navnet finnes fra før; gå tilbake og last opp filen under et nytt navn. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Ei fil med dette navnet finnes fra før i det delte fillageret; gå tilbake og last opp fila under et nytt navn. [[Image:$1|thumb|center|$1]]',
'successfulupload'            => 'Opplastingen er gjennomført',
'uploadwarning'               => 'Opplastingsadvarsel',
'savefile'                    => 'Lagre fil',
'uploadedimage'               => 'Lastet opp «[[$1]]»',
'overwroteimage'              => 'last opp en ny versjon av «[[$1]]»',
'uploaddisabled'              => 'Opplastingsfunksjonen er deaktivert',
'uploaddisabledtext'          => 'Opplasting er slått av på denne wikien.',
'uploadscripted'              => 'Denne fila inneholder HTML eller skripting som kan feiltolkes av en nettleser.',
'uploadcorrupt'               => 'Denne fila er ødelagt eller er en ugyldig filtype. Sjekk fila og last den opp på nytt.',
'uploadvirus'                 => 'Denne fila inneholder virus! Detaljer: $1',
'sourcefilename'              => 'Velg ei fil',
'destfilename'                => 'Ønsket filnavn',
'watchthisupload'             => 'Overvåk siden',
'filewasdeleted'              => 'Ei fil ved dette navnet har blitt lastet opp tidligere, og så slettet. Sjekk $1 før du forsøker å laste det opp igjen.',
'upload-wasdeleted'           => "'''Advarsel: Du laster opp en fil som tidligere har blitt slettet.'''

Vurder om det er riktig å fortsette å laste opp denne filen. Slettingsloggen for filen gis nedenunder:",
'filename-bad-prefix'         => 'Navnet på filen du laster opp begynner med <strong>«$1»</strong>, hvilket er et ikke-beksrivende navn som vanligvis brukes automatisk av digitalkameraer. Vennligst bruk et mer beskrivende navn på filen.',
'filename-prefix-blacklist'   => ' #<!-- leave this line exactly as it is --> <pre>
# Syntaksen er som følger:
#   * Alt fra tegnet «#» til slutten av linja er en kommentar
#   * Alle linjer som ikke er blanke er et prefiks som vanligvis brukes automatisk av digitale kameraer
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # noen mobiltelefontyper
IMG # generisk
JD # Jenoptik
MGP # Pentax
PICT # div.
 #</pre> <!-- leave this line exactly as it is -->',

'upload-proto-error'      => 'Gal protokoll',
'upload-proto-error-text' => 'Fjernopplasting behøver adresser som begynner med <code>http://</code> eller <code>ftp://</code>.',
'upload-file-error'       => 'Intern feil',
'upload-file-error-text'  => 'En intern feil oppsto under forsøk på å lage en midlertidig fil på tjeneren. Vennligst kontakt en systemadministrator.',
'upload-misc-error'       => 'Ukjent opplastingsfeil',
'upload-misc-error-text'  => 'En ukjent feil forekom under opplastingen. Vennligst bekreft at adressen er gyldig og tilgjengelig, og prøv igjen. Om problemet fortsetter, kontakt en systemadministrator.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Kunne ikke nå adressen',
'upload-curl-error6-text'  => 'Adressen kunne ikke nås. Vennligst dobbelsjekk at adressen er korrekt og at siden er oppe.',
'upload-curl-error28'      => 'Opplastingstimeout',
'upload-curl-error28-text' => 'Siden brukte for lang tid på å reagere. Vennligst sjekk at siden er oppe, og vent en kort stund for du prøver igjen. Vurder å prøve på en mindre hektisk tid.',

'license'            => 'Lisensiering',
'nolicense'          => 'Ingen spesifisert',
'license-nopreview'  => '(Forhåndsvisning ikke tilgjengelig)',
'upload_source_url'  => ' (en gyldig, offentlig tilgjengelig adresse)',
'upload_source_file' => ' (en fil på din datamaskin)',

# Image list
'imagelist'                 => 'Bildeliste',
'imagelisttext'             => 'Her er ei liste med <strong>$1</strong> filer sortert <strong>$2</strong>.',
'getimagelist'              => 'henter filliste',
'ilsubmit'                  => 'Søk',
'showlast'                  => 'Vis de siste $1 filene sortert $2.',
'byname'                    => 'etter navn',
'bydate'                    => 'etter dato',
'bysize'                    => 'etter størrelse',
'imgdelete'                 => 'slett',
'imgdesc'                   => 'beskrivelse',
'imgfile'                   => 'fil',
'filehist'                  => 'Filhistorikk',
'filehist-help'             => 'Klikk på en dato/klokkeslett for å se filen slik den var på den tiden.',
'filehist-deleteall'        => 'slett alt',
'filehist-deleteone'        => 'slett denne',
'filehist-revert'           => 'tilbakestill',
'filehist-current'          => 'nåværende',
'filehist-datetime'         => 'Dato/tid',
'filehist-user'             => 'Bruker',
'filehist-dimensions'       => 'Dimensjoner',
'filehist-filesize'         => 'Filstørrelse',
'filehist-comment'          => 'Kommenter',
'imagelinks'                => 'Lenker',
'linkstoimage'              => 'Følgende sider har lenker til denne fila:',
'nolinkstoimage'            => 'Det er ingen sider som bruker denne fila.',
'sharedupload'              => 'Denne fila deles av andre prosjekter.',
'shareduploadwiki'          => 'Se $1 for mer informasjon.',
'shareduploadwiki-linktext' => 'filbeskrivelsesside',
'noimage'                   => 'Ingen fil ved dette navnet finnes, du kan $1.',
'noimage-linktext'          => 'laste det opp',
'uploadnewversion-linktext' => 'Last opp en ny versjon av denne fila',
'imagelist_date'            => 'Dato',
'imagelist_name'            => 'Navn',
'imagelist_user'            => 'Bruker',
'imagelist_size'            => 'Størrelse (bytes)',
'imagelist_description'     => 'Beskrivelse',
'imagelist_search_for'      => 'Søk etter bildenavn:',

# File reversion
'filerevert'                => 'Tilbakestill $1',
'filerevert-legend'         => 'Tilbakestill fil',
'filerevert-intro'          => '<span class="plainlinks">Du tilbakestiller \'\'\'[[Media:$1|$1]]\'\'\' til [$4 versjonen à $2, $3].</span>',
'filerevert-comment'        => 'Kommentar:',
'filerevert-defaultcomment' => 'Tilbakestilte til versjonen à $1, $2',
'filerevert-submit'         => 'Tilbakestill',
'filerevert-success'        => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\' har blitt tilbakestilt til [$4 versjonen à $2, $3].</span>',
'filerevert-badversion'     => 'Det er ingen tidligere lokal versjon av denne filen med det gitte tidstrykket.',

# File deletion
'filedelete'             => 'Slett $1',
'filedelete-legend'      => 'Slett fil',
'filedelete-intro'       => "Du sletter '''[[Media:$1|$1]]'''.",
'filedelete-intro-old'   => '<span class="plainlinks">Du sletter versjonen av \'\'\'[[Media:$1|$1]]\'\'\' à [$4 $3, $2].</span>',
'filedelete-comment'     => 'Kommentar:',
'filedelete-submit'      => 'Slett',
'filedelete-success'     => "'''$1''' har blitt slettet.",
'filedelete-success-old' => '<span class="plainlinks">Versjonen av \'\'\'[[Media:$1|$1]]\'\'\' à $3, $2 har blitt slettet.</span>',
'filedelete-nofile'      => "'''$1''' eksisterer ikke på denne siden.",
'filedelete-nofile-old'  => "Det er ingen arkivert versjon av '''$1''' med de gitte attributtene.",
'filedelete-iscurrent'   => 'Du forsøker å slette den nyeste versjonen av denne filen. Vennligst tilbakestill til en eldre versjon først.',

# MIME search
'mimesearch'         => 'MIME-søk',
'mimesearch-summary' => 'Denne siden muliggjør filtrering av filer per MIME-type. Skriv inn: innholdstype/undertype, for eksempel <tt>image/jpeg</tt>.',
'mimetype'           => 'MIME-type:',
'download'           => 'last ned',

# Unwatched pages
'unwatchedpages' => 'Sider som ikke er overvåket',

# List redirects
'listredirects' => 'Liste over omdirigeringer',

# Unused templates
'unusedtemplates'     => 'Ubrukte maler',
'unusedtemplatestext' => 'Denne siden lister opp alle sider i malnavnerommet som ikke er inkludert på en annen side. Husk å sjekke for andre slags lenker til malen før du sletter den.',
'unusedtemplateswlh'  => 'andre lenker',

# Random page
'randompage'         => 'Tilfeldig side',
'randompage-nopages' => 'Det er ingen sider i dette navnerommet.',

# Random redirect
'randomredirect'         => 'Tilfeldig omdirigering',
'randomredirect-nopages' => 'Det er ingen omdirigeringer i dette navnerommet.',

# Statistics
'statistics'             => 'Statistikk',
'sitestats'              => '{{SITENAME}}-statistikk',
'userstats'              => 'Brukerstatistikk',
'sitestatstext'          => "Det er til sammen '''$1''' sider i databasen. Dette inkluderer diskusjonssider, sider om {{SITENAME}}, små stubbsider, omdirigeringer, og annet som antagligvis ikke gjelder som ordentlig innhold. Om man ikke regner med disse, er det '''$2''' sider som sannsynligvis er ordentlige innholdssider.

'''$8''' filer har blitt lastet opp.

Det har vært totalt '''$3''' sidevisninger, og '''$4''' redigeringer siden wikien ble satt opp. Det blir i snitt '''$5''' redigeringer per side, og '''$6''' visninger per redigering.

[http://meta.wikimedia.org/wiki/Help:Job_queue Arbeidskøen] er på '''$7'''.",
'userstatstext'          => "Det er {{PLURAL:$1|'''én''' registrert bruker|'''$1''' registrerte brukere}}, hvorav '''$2''' (eller '''$4&nbsp;%''') har $5rettigheter.",
'statistics-mostpopular' => 'Mest viste sider',

'disambiguations'      => 'Artikler med flertydige titler',
'disambiguationspage'  => 'Template:Peker',
'disambiguations-text' => "Følgende sider lenker til en '''pekerside'''. De burde i stedet lenke til en passende innholdsside.<br />En side anses om en pekerside om den inneholder en mal som det lenkes til fra [[MediaWiki:disambiguationspage]]",

'doubleredirects'     => 'Doble omdirigeringer',
'doubleredirectstext' => "'''NB:''' Denne lista kan inneholde gale resultater. Det er som regel fordi siden inneholder ekstra tekst under den første <tt>#redirect</tt>.<br />Hver linje inneholder lenker til den første og den andre omdirigeringen, og den første linja fra den andre omdirigeringsteksten. Det gir som regel den «riktige» målartikkelen, som den første omdirigeringen skulle ha pekt på.",

'brokenredirects'        => 'Ødelagte omdirigeringer',
'brokenredirectstext'    => 'Følgende omdirigeringer peker til ikkeeksisterende sider.',
'brokenredirects-edit'   => '(rediger)',
'brokenredirects-delete' => '(slett)',

'withoutinterwiki'        => 'Sider uten språklenker.',
'withoutinterwiki-header' => 'Følgende sider lenker ikke til andre språkversjoner:',

'fewestrevisions' => 'Artikler med færrest revisjoner',

# Miscellaneous special pages
'nbytes'                  => '$1 {{plural:$1|byte|bytes}}',
'ncategories'             => '$1 {{plural:$1|kategori|kategorier}}',
'nlinks'                  => '$1 {{plural:$1|lenke|lenker}}',
'nmembers'                => '$1 {{plural:$1|medlem|medlemmer}}',
'nrevisions'              => '$1 {{plural:$1|revisjon|revisjoner}}',
'nviews'                  => '$1 {{plural:$1|visning|visninger}}',
'specialpage-empty'       => 'Denne siden er tom.',
'lonelypages'             => 'Foreldreløse sider',
'lonelypagestext'         => 'Følgende sider blir ikke lenket til fra andre sider på denne wikien.',
'uncategorizedpages'      => 'Ukategoriserte sider',
'uncategorizedcategories' => 'Ukategoriserte kategorier',
'uncategorizedimages'     => 'Ukategoriserte bilder',
'uncategorizedtemplates'  => 'Ukategoriserte maler',
'unusedcategories'        => 'Ubrukte kategorier',
'unusedimages'            => 'Ubrukte filer',
'popularpages'            => 'Populære sider',
'wantedcategories'        => 'Ønskede kategorier',
'wantedpages'             => 'Etterspurte sider',
'mostlinked'              => 'Sider med flest lenker til seg',
'mostlinkedcategories'    => 'Kategorier med flest sider',
'mostlinkedtemplates'     => 'Mest brukte maler',
'mostcategories'          => 'Sider med flest kategorier',
'mostimages'              => 'Mest brukte bilder',
'mostrevisions'           => 'Artikler med flest revisjoner',
'allpages'                => 'Alle sider',
'prefixindex'             => 'Prefiksindeks',
'shortpages'              => 'Korte sider',
'longpages'               => 'Lange sider',
'deadendpages'            => 'Blindveisider',
'deadendpagestext'        => 'Følgende sider lenker ikke til andre sider på denne wikien.',
'protectedpages'          => 'Låste sider',
'protectedpagestext'      => 'Følgende sider er låst for flytting eller redigering',
'protectedpagesempty'     => 'Ingen sider er for øyeblikket låst med disse paramterne.',
'listusers'               => 'Brukerliste',
'specialpages'            => 'Spesialsider',
'spheading'               => 'Spesialsider for alle brukere',
'restrictedpheading'      => 'Spesialsider for administratorer',
'rclsub'                  => '(til sider med lenke fra «$1»)',
'newpages'                => 'Nye sider',
'newpages-username'       => 'Brukernavn:',
'ancientpages'            => 'Eldste sider',
'intl'                    => 'Språklenker',
'move'                    => 'Flytt',
'movethispage'            => 'Flytt siden',
'unusedimagestext'        => '<p>Merk at andre sider kanskje lenker til et bilde med en direkte lenke, så bildet listes her selv om det faktisk er i bruk.</p>',
'unusedcategoriestext'    => 'Følgende kategorier eksisterer, men det er ingen sider i dem.',
'notargettitle'           => 'Intet mål',
'notargettext'            => 'Du har ikke spesifisert en målside eller bruker å utføre denne funksjonen på.',
'pager-newer-n'           => 'nyere $1',
'pager-older-n'           => 'eldre $1',

# Book sources
'booksources'               => 'Bokkilder',
'booksources-search-legend' => 'Søk etter bokkilder',
'booksources-go'            => 'Gå',
'booksources-text'          => 'Under er en liste over lenker til andre sider som selger nye og brukte bøker, og kan også ha videre informasjon om bøker du leter etter:',

'categoriespagetext' => 'Følgende kategorier finnes i wikien.',
'data'               => 'data',
'userrights'         => 'Brukerrettighetskontroll',
'groups'             => 'Brukergrupper',
'alphaindexline'     => '$1 til $2',
'version'            => 'Versjon',

# Special:Log
'specialloguserlabel'  => 'Bruker:',
'speciallogtitlelabel' => 'Tittel:',
'log'                  => 'Logger',
'all-logs-page'        => 'Alle logger',
'log-search-legend'    => 'Søk i loggene.',
'log-search-submit'    => 'Gå',
'alllogstext'          => 'Kombinert visning av alle loggene. Du kan begrense visningen ved å velge loggtype, bruker og/eller påvirket side.',
'logempty'             => 'Ingen elementer i loggen.',
'log-title-wildcard'   => 'Søk i titler som starter med denne teksten',

# Special:Allpages
'nextpage'          => 'Neste side ($1)',
'prevpage'          => 'Forrige side ($1)',
'allpagesfrom'      => 'Vis sider som starter med:',
'allarticles'       => 'Alle artikler',
'allinnamespace'    => 'Alle sider i $1-navnerommet',
'allnotinnamespace' => 'Alle sider (ikke i $1-navnerommet)',
'allpagesprev'      => 'Forrige',
'allpagesnext'      => 'Neste',
'allpagessubmit'    => 'Gå',
'allpagesprefix'    => 'Vis sider med prefikset:',
'allpagesbadtitle'  => 'Den oppgitte sidetittelen var ugyldig eller hadde et interwiki-prefiks. Den kan inneholde ett eller flere tegn som ikke kan bli brukt i titler.',
'allpages-bad-ns'   => '{{SITENAME}} har ikke navnerommet «$1».',

# Special:Listusers
'listusersfrom'      => 'Vis brukere fra og med:',
'listusers-submit'   => 'Vis',
'listusers-noresult' => 'Ingen bruker funnet.',

# E-mail user
'mailnologin'     => 'Ingen avsenderadresse',
'mailnologintext' => 'Du må være [[Special:Userlogin|logget inn]] og ha en gyldig e-postadresse satt i [[Special:Preferences|brukerinnstillingene]] for å sende e-post til andre brukere.',
'emailuser'       => 'E-post til denne brukeren',
'emailpage'       => 'E-post til bruker',
'emailpagetext'   => 'Hvis denne brukeren har oppgitt en gyldig e-postadresse i sine innstillinger, vil dette skjemaet sende en enkelt beskjed. Den e-postadressa du har satt i innstillingene dine vil dukke opp i «Fra»-feltet på denne e-posten, så mottakeren er i stand til å svare.',
'usermailererror' => 'E-postobjekt returnerte feilen:',
'defemailsubject' => 'E-post fra {{SITENAME}}',
'noemailtitle'    => 'Ingen e-postadresse',
'noemailtext'     => 'Dene brukeren har ikke oppgitt en gyldig e-postadresse, eller har valgt å ikke motta e-post fra andre brukere.',
'emailfrom'       => 'Fra',
'emailto'         => 'Til',
'emailsubject'    => 'Emne',
'emailmessage'    => 'Beskjed',
'emailsend'       => 'Send',
'emailccme'       => 'Send meg en kopi av beskjeden min.',
'emailccsubject'  => 'Kopi av din beskjed til $1: $2',
'emailsent'       => 'E-post sendt',
'emailsenttext'   => 'E-postbeskjeden er sendt',

# Watchlist
'watchlist'            => 'Overvåkningsliste',
'mywatchlist'          => 'Overvåkningsliste',
'watchlistfor'         => "(for '''$1''')",
'nowatchlist'          => 'Du har ingenting i overvåkningslista.',
'watchlistanontext'    => 'Vennligst $1 for å vise eller redigere sider på overvåkningslista di.',
'watchnologin'         => 'Ikke logget inn',
'watchnologintext'     => 'Du må være [[Special:Userlogin|logget inn]] for å kunne endre overvåkningslisten.',
'addedwatch'           => 'Lagt til overvåkningslista',
'addedwatchtext'       => "Siden «$1» er føyd til [[Special:Watchlist|overvåkningslistea]]. Fremtidige endringer til denne siden og den tilhørende diskusjonssiden vil bli listet opp her, og siden vil fremstå '''fremhevet''' i [[Special:Recentchanges|lista over de siste endringene]] for å gjøre det lettere å finne den.

Hvis du senere vil fjerne siden fra overvåkningslista, klikk «Avslutt overvåkning» ute i siden.",
'removedwatch'         => 'Fjernet fra overvåkningslista',
'removedwatchtext'     => 'Siden «[[:$1]]» er fjernet fra overvåkningslista di.',
'watch'                => 'Overvåk',
'watchthispage'        => 'Overvåk siden',
'unwatch'              => 'Avslutt overvåkning',
'unwatchthispage'      => 'Fjerner overvåkning',
'notanarticle'         => 'Ikke en artikkel',
'watchnochange'        => 'Ingen av sidene i overvåkningslista er endret i den valgte perioden.',
'watchlist-details'    => '$1 sider overvåket, utenom diskusjonssider.',
'wlheader-enotif'      => '* E-postnotifikasjon er slått på.',
'wlheader-showupdated' => "* Sider som har blitt forandret siden du sist besøkte dem vises i '''fet tekst'''",
'watchmethod-recent'   => 'sjekker siste endringer for sider overvåkningslista',
'watchmethod-list'     => 'sjekker siste endringer for sider i overvåkningstlista',
'watchlistcontains'    => 'Overvåkningslista inneholder $1 {{plural:$1|side|sider}}.',
'iteminvalidname'      => 'Problem med «$1», ugyldig navn…',
'wlnote'               => 'Nedenfor er de siste $1 endringene de siste <b>$2</b> timene.',
'wlshowlast'           => 'Vis siste $1 timer $2 dager $3',
'watchlist-show-bots'  => 'Vis robotredigeringer',
'watchlist-hide-bots'  => 'Skjul robotredigeringer',
'watchlist-show-own'   => 'Vis mine redigeringer',
'watchlist-hide-own'   => 'Skjul mine redigeringer',
'watchlist-show-minor' => 'Vis mindre redigeringer',
'watchlist-hide-minor' => 'Skjul mindre redigeringer',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Overvåker…',
'unwatching' => 'Fjerner fra overvåkningsliste…',

'enotif_mailer'                => '{{SITENAME}} påminnelsessystem',
'enotif_reset'                 => 'Merk alle sider som besøkt',
'enotif_newpagetext'           => 'Dette er en ny side.',
'enotif_impersonal_salutation' => '{{SITENAME}}-bruker',
'changed'                      => 'endret',
'created'                      => 'opprettet',
'enotif_subject'               => '{{SITENAME}}-siden $PAGETITLE har blitt $CHANGEDORCREATED av $PAGEEDITOR',
'enotif_lastvisited'           => 'Se $1 for alle endringer siden ditt forrige besøk.',
'enotif_lastdiff'              => 'Se $1 for å se denne endringen.',
'enotif_anon_editor'           => 'anonym bruker $1',
'enotif_body'                  => '$WATCHINGUSERNAME,

{{SITENAME}}-siden $PAGETITLE har blitt $CHANGEDORCREATED $PAGEEDITDATE av $PAGEEDITOR, se $PAGETITLE_URL for den nåværende versjonen.

$NEWPAGE

Redigeringssammendrag: $PAGESUMMARY $PAGEMINOREDIT

Kontakt brukeren:
e-post: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Det vil ikke komme flere påminnelser om endringer på denne siden med mindre du besøker den. Du kan også fjerne påminnelsesflagg for alle sider i overvåkningslista di.

Med vennlig hilsen,
{{SITENAME}}s påminnelsessystem

--
For å endre innstillingene i overvåkningslista di, besøk {{fullurl:Special:Watchlist/edit}}

Tilbakemeldinger og videre assistanse:
{{fullurl:Project:Hjelp}}',

# Delete/protect/revert
'deletepage'                  => 'Slett side',
'confirm'                     => 'Bekreft',
'excontent'                   => 'Innholdet var: «$1»',
'excontentauthor'             => 'innholdet var «$1» (og eneste bidragsyter var [[{{ns:special}}:Contributions/$2|$2]])',
'exbeforeblank'               => 'innholdet før siden ble tømt var: «$1»',
'exblank'                     => 'siden var tom',
'confirmdelete'               => 'Bekreft sletting',
'deletesub'                   => '(Sletter «[[$1]]»)',
'historywarning'              => 'Advarsel: Siden du er i ferd med å slette har en historikk:',
'confirmdeletetext'           => 'Du holder på å slette en side eller et bilde sammen med historikken. Bilder som slettes kan ikke gjenopprettes, men alle andre sider som slettes på denne måten kan gjenopprettes. Bekreft at du virkelig vil slette denne siden, og at du gjør det i samsvar med [[{{MediaWiki:Policy-url}}|retningslinjene]].',
'actioncomplete'              => 'Gjennomført',
'deletedtext'                 => '«[[$1]]» er slettet. Se $2 for en oversikt over de siste slettingene.',
'deletedarticle'              => 'slettet «[[$1]]»',
'dellogpage'                  => 'Slettingslogg',
'dellogpagetext'              => 'Under er ei liste over nylige slettinger.',
'deletionlog'                 => 'slettingslogg',
'reverted'                    => 'Gjenopprettet en tidligere versjon',
'deletecomment'               => 'Begrunnelse for sletting',
'deleteotherreason'           => 'Annen grunn:',
'deletereasonotherlist'       => 'Annen grunn',
'deletereason-dropdown'       => '* Vanlige grunner for sletting
** På forfatters forespørsel
** Opphavsrettsbrudd
** Hærverk',
'rollback'                    => 'Fjern redigeringer',
'rollback_short'              => 'Tilbakestill',
'rollbacklink'                => 'tilbakestill',
'rollbackfailed'              => 'Kunne ikke tilbakestille',
'cantrollback'                => 'Kan ikke fjerne redigering; den siste brukeren er den eneste forfatteren.',
'alreadyrolled'               => 'Kan ikke fjerne den siste redigeringen på [[$1]] av [[User:$2|$2]] ([[User talk:$2|diskusjon]]); en annen har allerede redigert siden eller fjernet redigeringen. Den siste redigeringen er foretatt av [[User:$3|$3]] ([[User talk:$3|diskusjon]]).',
'editcomment'                 => "Redigeringskommentaren var: «''$1''»", # only shown if there is an edit comment
'revertpage'                  => 'Tilbakestilte endring av [[Special:Contributions/$2|$2]] ([[User talk:$2|diskusjon]] · [[Special:Blockip/$2|blokker]]) til siste versjon av $1',
'rollback-success'            => 'Tilbakestilte endringer av $1; endret til siste versjon av $2.',
'sessionfailure'              => "Det ser ut til å være et problem med innloggingen din, og den har blitt avbrutt av sikkerhetshensyn. Trykk ''Tilbake'' i nettleseren din, oppdater siden og prøv igjen.",
'protectlogpage'              => 'Låsingslogg',
'protectlogtext'              => 'Her er en liste over sider som er blitt beskyttet eller har fått fjernet beskyttelsen. Se [[Special:Protectedpages|listen over låste sider]] for en liste over nåværende låste sider.',
'protectedarticle'            => 'låste [[$1]]',
'modifiedarticleprotection'   => 'endret beskyttelsesnivå for «[[$1]]»',
'unprotectedarticle'          => 'åpnet [[$1]]',
'protectsub'                  => '(Låser «$1»)',
'confirmprotect'              => 'Bekreft låsing',
'protectcomment'              => 'Låsingsbegrunnelse',
'protectexpiry'               => 'Utgår',
'protect_expiry_invalid'      => 'Utgangstiden er ugyldig.',
'protect_expiry_old'          => 'Utgangstiden har allerede vært.',
'unprotectsub'                => '(Åpner «$1»)',
'protect-unchain'             => 'Spesielle flyttingstillatelser',
'protect-text'                => 'Du kan se og forandre beskyttelsesnivået for siden <strong>$1</strong> her.',
'protect-locked-blocked'      => 'Du kan ikke endre beskyttelsesnivåer mens du er blokkert. Dette er de nåværende innstillingene for siden <strong>$1</strong>:',
'protect-locked-dblock'       => 'Beskyttelsesnivåer kan ikke endres under en aktiv databasebeskyttelse. Dette er de nåværende innstillingene for siden <strong>$1</strong>:',
'protect-locked-access'       => 'Kontoen din har ikke tillatelse til å endre beskyttelsesnivåer. Dette er de nåværende innstillingene for siden <strong>$1</strong>:',
'protect-cascadeon'           => 'Denne siden er låst fordi den er inkludert på følgende sider som har dypbeskyttelse slått på. Du kan endre sidens låsingsnivå, men det vil ikke påvirke dypbeskyttelsen.<!--$1-->',
'protect-default'             => '(standard)',
'protect-fallback'            => 'Må ha «$1»-tillatelse',
'protect-level-autoconfirmed' => 'Blokker uregistrerte brukere',
'protect-level-sysop'         => 'Kun administratorer',
'protect-summary-cascade'     => 'dypbeskyttelse',
'protect-expiring'            => 'utgår $1 (UTC)',
'protect-cascade'             => 'Dypbeskyttelse – beskytter alle sider som er inkludert på denne siden.',
'protect-cantedit'            => 'Du kan ikke endre beskyttelsesnivået til denne siden fordi du ikke har tillatelse til å redigere den.',
'restriction-type'            => 'Tillatelse',
'restriction-level'           => 'Restriksjonsnivå',
'minimum-size'                => 'Minimumstørrelse (byte)',
'maximum-size'                => 'Maksimumstørrelse',
'pagesize'                    => '(byte)',

# Restrictions (nouns)
'restriction-edit' => 'Redigering',
'restriction-move' => 'Flytting',

# Restriction levels
'restriction-level-sysop'         => 'fullstendig låst',
'restriction-level-autoconfirmed' => 'halvlåst',
'restriction-level-all'           => 'alle nivåer',

# Undelete
'undelete'                     => 'Vis slettede sider',
'undeletepage'                 => 'Se og gjenopprett slettede sider',
'viewdeletedpage'              => 'Vis slettede sider',
'undeletepagetext'             => 'Følgende sider er slettet, men finnes fortsatt i arkivet og kan gjenopprettes. Arkivet blir periodevis slettet.',
'undeleteextrahelp'            => "For å gjenopprette hele siden, la alle boksene være som de er, og klikk '''Gjenopprett'''. For å gjenopprette kun deler, kryss av revisjonenes bokser, og klikk '''Gjenopprett'''.",
'undeleterevisions'            => '$1 revisjoner arkivert',
'undeletehistory'              => 'Hvis du gjenoppretter siden, vil alle de historiske
revisjoner også bli gjenopprettet. Hvis en ny side med det samme navnet
er opprettet siden denne ble slettet, vil de gjenopprettede revisjonene
dukke opp i den tidligere historikken, og den nyeste revisjonen vil forbli
på siden.',
'undeleterevdel'               => 'Gjenoppretting kan ikke utføres dersom det resulterer i at den øverste revisjonen blir delvis slettet. I slike tilfeller må du fjerne merkingen av den nyeste slettede revisjonen. Revisjoner av filer som du ikke har tilgang til vil ikke gjenopprettes.',
'undeletehistorynoadmin'       => 'Denne artikkelen har blitt slettet. Grunnen for slettingen vises i oppsummeringen nedenfor, sammen med detaljer om brukerne som redigerte siden før den ble slettet. Teksten i disse slettede revisjonene er kun tilgjengelig for administratorer.',
'undelete-revision'            => 'Slettet revisjon av $1 fra $2:',
'undeleterevision-missing'     => 'Ugyldig eller manglende revisjon. Du kan ha en ødelagt lenke, eller revisjonen har blitt fjernet fra arkivet.',
'undelete-nodiff'              => 'Ingen tidligere revisjoner funnet.',
'undeletebtn'                  => 'Gjenopprett',
'undeletereset'                => 'Resett skjema',
'undeletecomment'              => 'Forklaring:',
'undeletedarticle'             => 'gjenopprettet «[[$1]]»',
'undeletedrevisions'           => '$1 revisjoner gjenopprettet',
'undeletedrevisions-files'     => '{{PLURAL:$1|Én revisjon|$1 revisjoner}} og {{PLURAL:$2|én fil|$2 filer}} gjenopprettet',
'undeletedfiles'               => '{{PLURAL:$1|Én fil|$1 filer}} gjenopprettet',
'cannotundelete'               => 'Gjenoppretting feilet; noen andre kan ha gjenopprettet siden først.',
'undeletedpage'                => "<big>'''$1 har blitt gjenopprettet'''</big>

Sjekk [[Special:Log/delete|slettingsloggen]] for en liste over nylige slettinger og gjenopprettelser.",
'undelete-header'              => 'Se [[Special:Log/delete|slettingsloggen]] for nylig slettede sider.',
'undelete-search-box'          => 'Søk i slettede sider',
'undelete-search-prefix'       => 'Vis sider som starter med:',
'undelete-search-submit'       => 'Søk',
'undelete-no-results'          => 'Ingen passende sider funnet i slettingsarkivet.',
'undelete-filename-mismatch'   => 'Kan ikke gjenopprette filrevisjon med tidstrykk $1: ikke samsvarende filnavn',
'undelete-bad-store-key'       => 'Kan ikke gjenopprette filrevisjon med tidstrykk $1: fil manglet før sletting',
'undelete-cleanup-error'       => 'Feil i sletting av ubrukt arkivfil «$1».',
'undelete-missing-filearchive' => 'Kunne ikke gjenopprette filarkivet med ID $1 fordi det ikke er i databasen. Det kan ha blitt gjenopprettet tidligere.',
'undelete-error-short'         => 'Feil under filgjenoppretting: $1',
'undelete-error-long'          => 'Feil oppsto under filgjenoppretting:

$1',

# Namespace form on various pages
'namespace'      => 'Navnerom:',
'invert'         => 'Inverter',
'blanknamespace' => '(Hoved)',

# Contributions
'contributions' => 'Bidrag',
'mycontris'     => 'Mine bidrag',
'contribsub2'   => 'For $1 ($2)',
'nocontribs'    => 'Ingen endringer er funnet som passer disse kriteriene.',
'ucnote'        => 'Her er denne brukerens siste <b>$1</b> endringer i de siste <b>$2</b> dagene.',
'uclinks'       => 'Vis de siste $1 endringene; vis de siste $2 dagene.',
'uctop'         => ' (topp)',
'month'         => 'Måned:',
'year'          => 'År:',

'sp-contributions-newbies'     => 'Vis kun bidrag fra nye kontoer',
'sp-contributions-newbies-sub' => 'For nybegynnere',
'sp-contributions-blocklog'    => 'Blokkeringslogg',
'sp-contributions-search'      => 'Søk etter bidrag',
'sp-contributions-username'    => 'IP-adresse eller brukernavn:',
'sp-contributions-submit'      => 'Søk',

'sp-newimages-showfrom' => 'Vis nye bilder fra og med $1',

# What links here
'whatlinkshere'       => 'Lenker hit',
'whatlinkshere-title' => 'Sider som lenker til $1',
'whatlinkshere-page'  => 'Side:',
'linklistsub'         => '(Liste over lenker)',
'linkshere'           => "Følgende sider lenker til '''[[:$1]]''':",
'nolinkshere'         => "Ingen sider lenker til '''[[:$1]]'''.",
'nolinkshere-ns'      => "Ingen sider lenker til '''[[:$1]]''' i valgte navnerom.",
'isredirect'          => 'omdirigeringsside',
'istemplate'          => 'inklusjon',
'whatlinkshere-prev'  => 'forrige $1',
'whatlinkshere-next'  => 'neste $1',
'whatlinkshere-links' => '← lenker',

# Block/unblock
'blockip'                     => 'Blokker IP-adresse',
'blockiptext'                 => 'Bruk skjemaet under for å blokkere en IP-adresses tilgang til å redigere artikler. Dette må kun gjøres for å forhindre hærverk, og i overensstemmelse med [[{{MediaWiki:Policy-url}}|retningslinjene]]. Fyll ut en spesiell begrunnelse under.',
'ipaddress'                   => 'IP-adresse',
'ipadressorusername'          => 'IP-adresse eller brukernavn',
'ipbexpiry'                   => 'Utløper',
'ipbreason'                   => 'Begrunnelse',
'ipbreasonotherlist'          => 'Annen grunn',
'ipbreason-dropdown'          => '*Vanlige blokkeringsgrunner
** Legger inn feilinformasjon
** Fjerner innhold fra sider
** Lenkespam
** Legger inn vås
** Truende oppførsel
** Misbruk av flere kontoer
** Uakseptabelt brukernavn',
'ipbanononly'                 => 'Blokker kun anonyme brukere',
'ipbcreateaccount'            => 'Hindre kontoopprettelse',
'ipbemailban'                 => 'Forhindre brukeren fra å sende e-post',
'ipbenableautoblock'          => 'Blokker forrige IP-adresse brukt av denne brukeren automatisk, samt alle IP-adresser brukeren forsøker å redigere med i framtiden',
'ipbsubmit'                   => 'Blokker denne adressa',
'ipbother'                    => 'Annen tid',
'ipboptions'                  => '2 timer:2 hours,1 dag:1 day,3 dager:3 days,1 uke:1 week,2 uker:2 weeks,1 måned:1 month,3 måneder:3 months,6 måneder:6 months,1 år:1 year,uendelig:infinite',
'ipbotheroption'              => 'annet',
'ipbotherreason'              => 'Annen grunn:',
'ipbhidename'                 => 'Skjul brukernavn/IP i blokkeringsloggen, blokkeringslista og brukerlista',
'badipaddress'                => 'Ugyldig IP-adresse.',
'blockipsuccesssub'           => 'Blokkering utført',
'blockipsuccesstext'          => 'IP-adressa «$1» er blokkert. Se [[Special:Ipblocklist|blokkeringslista]] for alle blokkeringer.',
'ipb-edit-dropdown'           => 'Rediger blokkeringsgrunner',
'ipb-unblock-addr'            => 'Avblokker $1',
'ipb-unblock'                 => 'Avblokker et brukernavn eller en IP-adresse',
'ipb-blocklist-addr'          => 'Vis gjeldende blokkeringer for $1',
'ipb-blocklist'               => 'Vis gjeldende blokkeringer',
'unblockip'                   => 'Opphev blokkering',
'unblockiptext'               => 'Bruk skjemaet under for å gjenopprette skriveadgangen for en tidligere blokkert adresse eller bruker.',
'ipusubmit'                   => 'Opphev blokkeringen av denne adressa',
'unblocked'                   => '[[User:$1|$1]] har blitt avblokkert',
'unblocked-id'                => 'Blokkering $1 har blitt fjernet',
'ipblocklist'                 => 'Liste over blokkerte IP-adresser og brukere',
'ipblocklist-legend'          => 'Find en blokkert bruker',
'ipblocklist-username'        => 'Brukernavn eller IP-adresse:',
'ipblocklist-submit'          => 'Søk',
'blocklistline'               => '$1, $2 blokkerte $3 ($4)',
'infiniteblock'               => 'uendelig',
'expiringblock'               => 'utgår $1',
'anononlyblock'               => 'kun anonyme',
'noautoblockblock'            => 'autoblokkering slått av',
'createaccountblock'          => 'kontoopretting blokkert',
'emailblock'                  => 'e-posttjenester blokkert',
'ipblocklist-empty'           => 'Blokkeringslista er tom.',
'ipblocklist-no-results'      => 'Den angitte IP-adressa eller brukeren er ikke blokkert.',
'blocklink'                   => 'blokker',
'unblocklink'                 => 'opphev blokkering',
'contribslink'                => 'bidrag',
'autoblocker'                 => 'Du har blitt automatisk blokkert fordi du deler IP-adresse med «[[User:$1|$1]]». Grunnen som ble gitt til at «$1» ble blokkert var: «$2».',
'blocklogpage'                => 'Blokkeringslogg',
'blocklogentry'               => 'Blokkerte «[[$1]]» med en utløpstid på $2 $3',
'blocklogtext'                => 'Dette er en logg som viser hvilke brukere som har blitt blokkert og avblokkert. Automatisk blokkerte IP-adresser vises ikke. Se [[Special:Ipblocklist|blokkeringslista]] for en liste over IP-adresser som er blokkert i nåværende tidspunkt.',
'unblocklogentry'             => 'opphevet blokkeringen av $1',
'block-log-flags-anononly'    => 'kun anonyme brukere',
'block-log-flags-nocreate'    => 'kontoopretting slått av',
'block-log-flags-noautoblock' => 'autoblokkering slått av',
'block-log-flags-noemail'     => 'e-posttjenester blokkert',
'range_block_disabled'        => 'Muligheten til å blokkere flere IP-adresser om gangen er slått av.',
'ipb_expiry_invalid'          => 'Ugyldig utløpstid.',
'ipb_already_blocked'         => '«$1» er allerede blokkert',
'ipb_cant_unblock'            => 'Feil: Blokk-ID $1 ikke funnet. Kan ha blitt avblokkert allerede.',
'ipb_blocked_as_range'        => 'Feil: IP-en $1 er ikke blokkert direkte, og kan ikke avblokkeres. Den er imidlertid blokkert som del av blokkeringa av IP-rangen $2, som kan avblokkeres.',
'ip_range_invalid'            => 'Ugyldig IP-rad.',
'blockme'                     => 'Blokkert meg',
'proxyblocker'                => 'Proxyblokker',
'proxyblocker-disabled'       => 'Denne funksjonen er slått av.',
'proxyblockreason'            => 'IP-adressa di har blitt blokkert fordi den er en åpen proxy. Kontakt internettleverandør eller teknisk støtte og informer dem om dette alvorlige sikkerhetsproblemet.',
'proxyblocksuccess'           => 'Utført.',
'sorbsreason'                 => 'IP-adressa di er oppgitt som åpen proxy i DNSBL.',
'sorbs_create_account_reason' => 'IP-adressa di oppgis som en åpen proxy i DNSBL. Du kan ikke opprette en konto.',

# Developer tools
'lockdb'              => 'Lås database',
'unlockdb'            => 'Åpne database',
'lockdbtext'          => 'Å låse databasen vil avbryte alle brukere fra å kunne
redigere sider, endre deres innstillinger, redigere deres
overvåkningsliste, og andre ting som krever endringer i databasen.
Bekreft at du har til hensikt å gjøre dette, og at du vil
låse opp databasen når vedlikeholdet er utført.',
'unlockdbtext'        => 'Å låse opp databasen vil si at alle brukere igjen
kan redigere sider, endre sine innstillinger, redigere sin
overvåkningsliste, og andre ting som krever endringer i databasen.
Bekreft at du har til hensikt å gjøre dette.',
'lockconfirm'         => 'Ja, jeg vil virkelig låse databasen.',
'unlockconfirm'       => 'Ja, jeg vil virkelig låse opp databasen.',
'lockbtn'             => 'Lås databasen',
'unlockbtn'           => 'Åpne databasen',
'locknoconfirm'       => 'Du har ikke bekreftet handlingen.',
'lockdbsuccesssub'    => 'Databasen er nå låst',
'unlockdbsuccesssub'  => 'Databasen er nå lås opp',
'lockdbsuccesstext'   => 'Databasen er låst.<br />Husk å fjerne låsen når du er ferdig med vedlikeholdet.',
'unlockdbsuccesstext' => 'Databasen er låst opp.',
'lockfilenotwritable' => 'Kan ikke skrive til databasen. For å låse eller åpne databasen, må denne kunne skrives til av tjeneren.',
'databasenotlocked'   => 'Databasen er ikke låst.',

# Move page
'movepage'                => 'Flytt side',
'movepagetext'            => "Når du bruker skjemaet under, vil du få omdøpt en 
side og flyttet hele historikken til det nye navnet.
Den gamle tittelen vil bli en omdirigeringsside til den nye tittelen.
Lenker til den gamle tittelen vil ikke bli endret. Eventuelle omdirigeringer vil bli brutt.

<span style=\"color:#ff0000\"><b>Det er <u>ditt ansvar</u> å rette alle omdirigeringer (bruk «Lenker hit» for å finne dem) hvis du flytter en side!</b></span>

Legg merke til at siden '''ikke''' kan flyttes hvis det allerede finnes en side 
med den nye tittelen, med mindre den siden er tom eller er en omdirigering 
uten noen historikk. Det betyr at du kan flytte en side tilbake dit
den kom fra hvis du gjør en feil.

<b>ADVARSEL!</b>
Dette kan være en drastisk og uventet endring for en populær side;
vær sikker på at du forstår konsekvensene av dette før du
fortsetter.",
'movepagetalktext'        => "Den tilhørende diskusjonssiden, hvis det finnes en,
vil automatisk bli flyttet med siden '''med mindre:'''
*En ikke-tom diskusjonsside allerede eksisterer under det nye navnet, eller
*Du fjerner markeringen i boksen nedenunder.

I disse tilfellene er du nødt til å flytte eller flette sammen siden manuelt.",
'movearticle'             => 'Flytt side',
'movenologin'             => 'Ikke logget inn',
'movenologintext'         => 'Du må være registrert bruker og være [[Special:Userlogin|logget på]] for å flytte en side.',
'movenotallowed'          => 'Du har ikke tillatelse til å flytte sider på denne wikien.',
'newtitle'                => 'Til ny tittel',
'move-watch'              => 'Overvåk denne siden',
'movepagebtn'             => 'Flytt side',
'pagemovedsub'            => 'Flytting gjennomført',
'movepage-moved'          => "<big>'''«$1» har blitt flyttet til «$2»'''</big>", # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'En side med det navnet eksisterer allerede, eller valgte navn er ugyldig. Velg et annet navn.',
'talkexists'              => "'''Siden ble flyttet korrekt, men den tilhørende diskusjonssiden kunne ikke flyttes, fordi det allerede eksisterer en med den nye tittelen. Du er nødt til å flette dem sammen manuelt.'''",
'movedto'                 => 'flyttet til',
'movetalk'                => 'Flytt også diskusjonssiden, hvis den eksisterer.',
'talkpagemoved'           => 'Den tilhørende diskusjonssiden ble også flyttet.',
'talkpagenotmoved'        => 'Den tilhørende diskusjonssiden ble <strong>ikke</strong> flyttet.',
'1movedto2'               => '[[$1]] flyttet til [[$2]]',
'1movedto2_redir'         => '[[$1]] flyttet til [[$2]] over omdirigeringsside',
'movelogpage'             => 'Flyttelogg',
'movelogpagetext'         => 'Her er ei liste over sider som har blitt flyttet.',
'movereason'              => 'Årsak',
'revertmove'              => 'tilbakestill',
'delete_and_move'         => 'Slett og flytt',
'delete_and_move_text'    => '==Sletting nødvendig==
Målsiden «[[$1]]» finnes allerede. Vil du slette den så denne siden kan flyttes dit?',
'delete_and_move_confirm' => 'Ja, slett siden',
'delete_and_move_reason'  => 'Slettet grunnet flytting',
'selfmove'                => 'Kilde- og destinasjonstittel er den samme; kan ikke flytte siden.',
'immobile_namespace'      => 'Sider kan ikke flyttes til dette navnerommet.',

# Export
'export'            => 'Eksportsider',
'exporttext'        => 'Du kan eksportere teksten og redigeringshistorikken for en bestemt side eller en gruppe sider i XML. Dette kan senere importeres til en annen wiki som bruker MediaWiki ved hjelp av [[Special:Import]].

For å eksportere sider, skriv inn titler i tekstboksen under, én tittel per linje, og velg om du vil ha kun nåværende versjon, eller alle versjoner i historikken. Dersom du bare vil ha nåværende versjon, kan du også bruke en lenke, for eksempel [[Special:Export/{{Mediawiki:Mainpage}}]] for siden «{{Mediawiki:Mainpage}}».',
'exportcuronly'     => 'Inkluder kun den nåværende versjonen, ikke hele historikken.',
'exportnohistory'   => "----
'''Merk:''' Eksportering av hele historikken gjennom dette skjemaet har blitt slått av av ytelsesgrunner.",
'export-submit'     => 'Eksporter',
'export-addcattext' => 'Legg til sider fra kategori:',
'export-addcat'     => 'Legg til',
'export-download'   => 'Lagre som fil',

# Namespace 8 related
'allmessages'               => 'Systemmeldinger',
'allmessagesname'           => 'Navn',
'allmessagesdefault'        => 'Standardtekst',
'allmessagescurrent'        => 'Nåværende tekst',
'allmessagestext'           => 'Dette er en liste over tilgjengelige systemmeldinger i MediaWiki-navnerommet.',
'allmessagesnotsupportedDB' => "''{{ns:special}}:Allmessages'' kan ikke brukes fordi '''\$wgUseDatabaseMessages''' er slått av.",
'allmessagesfilter'         => 'Filter:',
'allmessagesmodified'       => 'Vis kun endrede',

# Thumbnails
'thumbnail-more'           => 'Forstørr',
'missingimage'             => '<b>Bilde mangler</b><br /><i>$1</i>',
'filemissing'              => 'Fila mangler',
'thumbnail_error'          => 'Feil under oppretting av miniatyrbilde: $1',
'djvu_page_error'          => 'DjVu-side ute av rekkevidde',
'djvu_no_xml'              => 'Kan ikke hente XML for DjVu-fil',
'thumbnail_invalid_params' => 'Ugyldige miniatyrparametere',
'thumbnail_dest_directory' => 'Kan ikke opprette målmappe',

# Special:Import
'import'                     => 'Importer sider',
'importinterwiki'            => 'Transwiki-importering',
'import-interwiki-text'      => 'Velg en wiki og en side å importere. Revisjonsdatoer og bidragsyteres navn vil bli bevart. Alle transwiki-importeringer spares i [[Special:Log/import|importloggen]].',
'import-interwiki-history'   => 'Kopier all historikk for denne siden',
'import-interwiki-submit'    => 'Importer',
'import-interwiki-namespace' => 'Flytt sidene til navnerommet:',
'importtext'                 => 'Importer fila fra kildewikien med Special:Export-verktøyet, lagre den på den egen datamaskin, og last den opp hit.',
'importstart'                => 'Importerer sider…',
'import-revision-count'      => '({{PLURAL:$1|Én revisjon|$1 revisjoner}})',
'importnopages'              => 'Ingen sider å importere.',
'importfailed'               => 'Importering mislyktes: $1',
'importunknownsource'        => 'Ukjent importkildetype',
'importcantopen'             => 'Kunne ikke åpne importfil',
'importbadinterwiki'         => 'Ugyldig interwikilenke',
'importnotext'               => 'Tom eller ingen tekst',
'importsuccess'              => 'Import lyktes!',
'importhistoryconflict'      => 'Motstridende revisjoner finnes (siden kan ha blitt importert tidligere)',
'importnosources'            => 'Ingen transwikiimportkilder er definert, og direkte historikkimporteringer er slått av.',
'importnofile'               => 'Ingen importfil opplastet.',
'importuploaderror'          => 'Opplasting av importfil feilet; kanskje fila er større en den tillatte opplastingsstørrelsen.',

# Import log
'importlogpage'                    => 'Importlogg',
'importlogpagetext'                => 'Administrativ import av sider med redigeringshistorikk fra andre wikier.',
'import-logentry-upload'           => 'importerte [[$1]] ved opplasting',
'import-logentry-upload-detail'    => 'Importerte {{PLURAL:$1|én revisjon|$1 revisjoner}}',
'import-logentry-interwiki'        => 'transwikiimporterte $1',
'import-logentry-interwiki-detail' => '{{PLURAL:$1|Én revisjon|$1 revisjoner}} fra $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Min brukerside',
'tooltip-pt-anonuserpage'         => 'Brukersiden for IP-adressen du redigerer fra',
'tooltip-pt-mytalk'               => 'Min diskusjonsside',
'tooltip-pt-anontalk'             => 'Diskusjon om redigeringer fra denne IP-adressen',
'tooltip-pt-preferences'          => 'Mine innstillinger',
'tooltip-pt-watchlist'            => 'Liste over sider du overvåker for endringer.',
'tooltip-pt-mycontris'            => 'Liste over mine bidrag',
'tooltip-pt-login'                => 'Du oppfordres til å logge inn, men det er ikke obligatorisk.',
'tooltip-pt-anonlogin'            => 'Du oppfordres til å logge inn, men det er ikke obligatorisk.',
'tooltip-pt-logout'               => 'Logg ut',
'tooltip-ca-talk'                 => 'Diskusjon om innholdssiden',
'tooltip-ca-edit'                 => 'Du kan redigere denne siden. Vennligst bruk Forhåndsvis-knappen før du lagrer.',
'tooltip-ca-addsection'           => 'Legg til et diskusjonsinnlegg.',
'tooltip-ca-viewsource'           => 'Denne siden er beskyttet. Du kan se kildeteksten.',
'tooltip-ca-history'              => 'Tidligere revisjoner av denne siden.',
'tooltip-ca-protect'              => 'Beskytt denne siden',
'tooltip-ca-delete'               => 'Slette denne siden',
'tooltip-ca-undelete'             => 'Gjenopprett redigerenge som ble gjort på denne siden før den ble slettet.',
'tooltip-ca-move'                 => 'Flytt denne siden',
'tooltip-ca-watch'                => 'Legg til denne siden til din overvåkningsliste.',
'tooltip-ca-unwatch'              => 'Fjern denne siden fra din overvåkningsliste.',
'tooltip-search'                  => 'Søk i {{SITENAME}}',
'tooltip-search-go'               => 'Gå til en side med dette navnet dersom det finnes',
'tooltip-search-fulltext'         => 'Søk etter denne teksten',
'tooltip-p-logo'                  => 'Hovedside',
'tooltip-n-mainpage'              => 'Gå til hovedsiden',
'tooltip-n-portal'                => 'Om prosjektet; hva du kan gjøre og hvor du kan finne ting',
'tooltip-n-currentevents'         => 'Finn bakgrunnsinformasjon om aktuelle hendelser',
'tooltip-n-recentchanges'         => 'Liste over siste endringer på wikien.',
'tooltip-n-randompage'            => 'Gå inn på en tilfeldig side',
'tooltip-n-help'                  => 'Stedet for å få hjelp.',
'tooltip-n-sitesupport'           => 'Støtt oss',
'tooltip-t-whatlinkshere'         => 'Liste over alle sider som lenker hit',
'tooltip-t-recentchangeslinked'   => 'Siste endringer i sider som blir lenket fra denne siden',
'tooltip-feed-rss'                => 'RSS-føde for denne siden',
'tooltip-feed-atom'               => 'Atom-føde for denne siden',
'tooltip-t-contributions'         => 'Vis liste over bidrag fra denne brukeren',
'tooltip-t-emailuser'             => 'Send en e-post til denne brukeren',
'tooltip-t-upload'                => 'Last opp bilder eller mediefiler',
'tooltip-t-specialpages'          => 'Liste over alle spesialsider',
'tooltip-t-print'                 => 'Utskriftsvennlig versjon av denne siden',
'tooltip-t-permalink'             => 'Permanent lenke til denne versjonen av siden',
'tooltip-ca-nstab-main'           => 'Vis innholdssiden',
'tooltip-ca-nstab-user'           => 'Vis brukersiden',
'tooltip-ca-nstab-media'          => 'Vis mediasiden',
'tooltip-ca-nstab-special'        => 'Dette er en spesialside, og kan ikke redigeres.',
'tooltip-ca-nstab-project'        => 'Vis prosjektsiden',
'tooltip-ca-nstab-image'          => 'Vis bildesiden',
'tooltip-ca-nstab-mediawiki'      => 'Vis systembeskjeden',
'tooltip-ca-nstab-template'       => 'Vis malen',
'tooltip-ca-nstab-help'           => 'Vis hjelpesiden',
'tooltip-ca-nstab-category'       => 'Vis kategorisiden',
'tooltip-minoredit'               => 'Merk dette som en mindre endring',
'tooltip-save'                    => 'Lagre endringer',
'tooltip-preview'                 => 'Forhåndsvis endringene, vennligst bruk denne funksjonen før du lagrer!',
'tooltip-diff'                    => 'Vis hvilke endringer du har gjort på teksten.',
'tooltip-compareselectedversions' => 'Se forskjellene mellom de to valgte versjonene av denne siden.',
'tooltip-watch'                   => 'Legg denne siden til overvåkningslista di',
'tooltip-recreate'                => 'Gjenopprett siden til tross for at den har blitt slettet',
'tooltip-upload'                  => 'Start opplasting',

# Stylesheets
'common.css'   => '/* CSS plassert i denne fila vil gjelde for alle utseender. */',
'monobook.css' => '/* rediger denne filen for å tilpasse Monobook-skinnet for hele siden */',

# Scripts
'common.js'   => '/* All JavaScript i denne fila vil bli lastet for alle brukere på hver side. */',
'monobook.js' => '/* Foreldet; bruk [[MediaWiki:common.js]] */',

# Metadata
'nodublincore'      => 'Dublin Core RDF-metadata er slått av på denne tjeneren.',
'nocreativecommons' => 'Create Commons RDF-metadata er slått av på denne tjeneren.',
'notacceptable'     => 'Tjeneren har ingen mulige måter å vise data i din nettleser.',

# Attribution
'anonymous'        => 'Anonym(e) bruker(e) av {{SITENAME}}',
'siteuser'         => '{{SITENAME}}-bruker $1',
'lastmodifiedatby' => 'Denne siden ble sist redigert $2, $1 av $3.', # $1 date, $2 time, $3 user
'and'              => 'og',
'othercontribs'    => 'Basert på arbeid av $1.',
'others'           => 'andre',
'siteusers'        => '{{SITENAME}}-bruker(e) $1',
'creditspage'      => 'Sidekreditteringer',
'nocredits'        => 'Ingen krediteringer er tilgjengelig for denne siden.',

# Spam protection
'spamprotectiontitle'    => 'Søppelpostfilter',
'spamprotectiontext'     => 'Siden du ønsket å lagre ble blokkert av spamfilteret. Dette er sannsynligvis forårsaket av en lenke til et eksternt nettsted.',
'spamprotectionmatch'    => 'Følgende tekst er det som aktiverte spamfilteret: $1',
'subcategorycount'       => 'Det er {{PLURAL:$1|én underkategori|$1 underkategorier}} i denne kategorien.',
'categoryarticlecount'   => 'Det er {{PLURAL:$1|en artikkel|$1 artikler}} i denne kategorien.',
'category-media-count'   => 'Det er {{PLURAL:$1|én fil|$1 filer}} i denne kategorien.',
'listingcontinuesabbrev' => ' forts.',
'spambot_username'       => 'MediaWikis spamopprydning',
'spam_reverting'         => 'Tilbakestiller til siste versjon uten lenke til $1',
'spam_blanking'          => 'Alle revisjoner inneholdt lenke til $1, tømmer siden',

# Info page
'infosubtitle'   => 'Sideinformasjon',
'numedits'       => 'Antall redigeringer (artikkel): $1',
'numtalkedits'   => 'Antall redigeringer (diskusjonsside): $1',
'numwatchers'    => 'Antall overvåkere: $1',
'numauthors'     => 'Antall forskjellige bidragsytere (artikkel): $1',
'numtalkauthors' => 'Antall forskjellige bidragsytere (diskusjonsside): $1',

# Math options
'mw_math_png'    => 'Vis alltid som PNG',
'mw_math_simple' => 'HTML hvis veldig enkel, ellers PNG',
'mw_math_html'   => 'HTML vis mulig, ellers PNG',
'mw_math_source' => 'Behold som TeX (for tekst-nettlesere)',
'mw_math_modern' => 'Anbefalt for moderne nettlesere',
'mw_math_mathml' => 'MathML hvis mulig',

# Patrolling
'markaspatrolleddiff'                 => 'Godkjenn endringen',
'markaspatrolledtext'                 => 'Godkjenn denne siden',
'markedaspatrolled'                   => 'Merket som godkjent',
'markedaspatrolledtext'               => 'Endringen er merket som godkjent.',
'rcpatroldisabled'                    => 'Siste endringer-patruljering er slått av',
'rcpatroldisabledtext'                => 'Siste endringer-patruljeringsfunksjonen er slått av.',
'markedaspatrollederror'              => 'Kan ikke merke som godkjent',
'markedaspatrollederrortext'          => 'Du må spesifisere en versjon å merke som godkjent.',
'markedaspatrollederror-noautopatrol' => 'Du kan ikke merke dine egne endringer som godkjente.',

# Patrol log
'patrol-log-page' => 'Godkjenningslogg',
'patrol-log-line' => 'merket $1 av $2 godkjent $3',
'patrol-log-auto' => '(automatisk)',

# Image deletion
'deletedrevision'                 => 'Slettet gammel revisjon $1.',
'filedeleteerror-short'           => 'Feil under filsletting: $1',
'filedeleteerror-long'            => 'Feil oppsto under filsletting:

$1',
'filedelete-missing'              => 'Filen «$1» kan ikke slettes fordi den ikke eksisterer.',
'filedelete-old-unregistered'     => 'Filrevisjonen «$1» finnes ikke i databasen.',
'filedelete-current-unregistered' => 'Filen «$1» finnes ikke i databasen.',
'filedelete-archive-read-only'    => 'Arkivmappa «$1» kan ikke skrives av tjeneren.',

# Browsing diffs
'previousdiff' => '← Gå til forrige revisjon',
'nextdiff'     => 'Gå til neste diff →',

# Media information
'mediawarning'         => "'''Advarsel''': Denne fila kan inneholde farlig kode; ved å åpne den kan systemet ditt kompromitteres.<hr />",
'imagemaxsize'         => 'Begrens bilder på bildebeskrivelsessider til:',
'thumbsize'            => 'Miniatyrbildestørrelse:',
'widthheightpage'      => '$1×$2, $3 sider',
'file-info'            => '(filstørrelse: $1, MIME-type: $2)',
'file-info-size'       => '($1 × $2 piksler, filstørrelse: $3, MIME-type: $4)',
'file-nohires'         => '<small>Ingen høyere oppløsning tilgjengelig.</small>',
'svg-long-desc'        => '(SVG-fil, standardoppløsning $1 × $2 piksler, filstørrelse $3)',
'show-big-image'       => 'Full oppløsning',
'show-big-image-thumb' => '<small>Størrelse på denne forhåndsvisningen: $1 × $2 piksler</small>',

# Special:Newimages
'newimages'    => 'Galleri over nye filer',
'showhidebots' => '($1 roboter)',
'noimages'     => 'Ingenting å se.',

# Bad image list
'bad_image_list' => 'Formatet er slik:

Kun listeelementer (linjer som starter med *) tas med. Den første lenka på en linje må være en lenke til et dårlig bilde. Alle andre linker på samme linje anses å være unntak, altså artikler hvor bildet er tillat brukt.',

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => 'Denne fila inneholder tilleggsinformasjon, antagligvis fra digitalkameraet eller skanneren brukt til å lage eller digitalisere det. Hvis fila har blitt forandret fra utgangspunktet, kan enkelte detaljer kanskje være unøyaktige.',
'metadata-expand'   => 'Vis detaljer',
'metadata-collapse' => 'Skjul detaljer',
'metadata-fields'   => 'EXIF-metadatafelt i denne beskjeden vil bli inkludert på bildesiden mens metadatatabellen er slått sammen. Andre vil skjules som standard.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Bredde',
'exif-imagelength'                 => 'Høyde',
'exif-bitspersample'               => 'Bits per komponent',
'exif-compression'                 => 'Kompresjonsskjema',
'exif-photometricinterpretation'   => 'Pixelsammensetning',
'exif-orientation'                 => 'Retning',
'exif-samplesperpixel'             => 'Antall komponenter',
'exif-planarconfiguration'         => 'Dataarrangement',
'exif-ycbcrpositioning'            => 'Y- og C-posisjonering',
'exif-xresolution'                 => 'Horisontal oppløsning',
'exif-yresolution'                 => 'Vertikal oppløsning',
'exif-resolutionunit'              => 'Enhet for X- og Y-oppløsning',
'exif-rowsperstrip'                => 'Antall rader per stripe',
'exif-jpeginterchangeformatlength' => 'Byte med JPEG-data',
'exif-transferfunction'            => 'Overføringsfunksjon',
'exif-datetime'                    => 'Dato og tid for filendring',
'exif-imagedescription'            => 'Bildetittel',
'exif-make'                        => 'Kameraprodusent',
'exif-model'                       => 'Kameramodell',
'exif-software'                    => 'Programvare brukt',
'exif-artist'                      => 'Opphavsperson',
'exif-copyright'                   => 'Opphavsbeskyttelse tilhører',
'exif-exifversion'                 => 'Exif-versjon',
'exif-flashpixversion'             => 'Støttet Flashpix-versjon',
'exif-colorspace'                  => 'Fargerom',
'exif-componentsconfiguration'     => 'Betydning av hver komponent',
'exif-compressedbitsperpixel'      => 'Bildekompresjonsmodus',
'exif-pixelydimension'             => 'Gyldig bildebredde',
'exif-pixelxdimension'             => 'Gyldig bildehøyde',
'exif-makernote'                   => 'Fabrikkmerknader',
'exif-usercomment'                 => 'Brukerkommentarer',
'exif-relatedsoundfile'            => 'Relatert lydfil',
'exif-datetimeoriginal'            => 'Dato og tid for datagenerering',
'exif-datetimedigitized'           => 'Dato og tid for digitalisering',
'exif-exposuretime'                => 'Eksponeringstid',
'exif-exposuretime-format'         => '$1 sek ($2)',
'exif-fnumber'                     => 'F-nummer',
'exif-exposureprogram'             => 'Eksponeringsprogram',
'exif-spectralsensitivity'         => 'Spektralsensitivitet',
'exif-shutterspeedvalue'           => 'Lukkerhastighet',
'exif-subjectdistance'             => 'Avstand til subjekt',
'exif-lightsource'                 => 'Lyskilde',
'exif-flash'                       => 'Blits',
'exif-flashenergy'                 => 'Blitsenergi',
'exif-exposureindex'               => 'Eksponeringsindeks',
'exif-filesource'                  => 'Filkilde',
'exif-scenetype'                   => 'Scenetype',
'exif-cfapattern'                  => 'CFA-mønster',
'exif-exposuremode'                => 'Eksponeringsmodus',
'exif-whitebalance'                => 'Hvit balanse',
'exif-gaincontrol'                 => 'Scenekontroll',
'exif-contrast'                    => 'Kontrast',
'exif-sharpness'                   => 'Skarphet',
'exif-imageuniqueid'               => 'Unikk bilde-ID',
'exif-gpslatituderef'              => 'nordlig eller sørlig breddegrad',
'exif-gpslatitude'                 => 'Breddegrad',
'exif-gpslongituderef'             => 'Østlig eller vestlig breddegrad',
'exif-gpslongitude'                => 'Lengdegrad',
'exif-gpsaltituderef'              => 'Høydereferanse',
'exif-gpsaltitude'                 => 'Høyde',
'exif-gpstimestamp'                => 'GPS-tid (atomklokke)',
'exif-gpssatellites'               => 'Satelitter brukt i måling',
'exif-gpsstatus'                   => 'Mottakerstatus',
'exif-gpsmeasuremode'              => 'Målingsmodus',
'exif-gpsdop'                      => 'Målingspresisjon',
'exif-gpsspeedref'                 => 'Fartsenhet',
'exif-gpstrackref'                 => 'Referanse for bevegelsesretning',
'exif-gpstrack'                    => 'Bevegelsesretning',
'exif-gpsimgdirectionref'          => 'Referanse for bilderetning',
'exif-gpsimgdirection'             => 'Bilderetning',
'exif-gpsdestlatituderef'          => 'Referanse for målbreddegrad',
'exif-gpsdestlatitude'             => 'Målbreddegrad',
'exif-gpsdestlongituderef'         => 'Referanse for mållengdegrad',
'exif-gpsdestlongitude'            => 'Mållengdegrad',
'exif-gpsdestdistanceref'          => 'Referanse for lengde til mål',
'exif-gpsdestdistance'             => 'Lengde til mål',
'exif-gpsprocessingmethod'         => 'Navn på GPS-prosesseringsmetode',
'exif-gpsareainformation'          => 'Navn på GPS-område',
'exif-gpsdatestamp'                => 'GPS-dato',

# EXIF attributes
'exif-compression-1' => 'Ukomprimert',

'exif-unknowndate' => 'Ukjent dato',

'exif-orientation-1' => 'Normal', # 0th row: top; 0th column: left
'exif-orientation-2' => 'Snudd horisontalt', # 0th row: top; 0th column: right
'exif-orientation-3' => 'Rotert 180°', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Snudd vertikalt', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Rotated 90° CCW and flipped vertically

Rotert 90° mot klokka og vridd vertikalt', # 0th row: left; 0th column: top
'exif-orientation-6' => 'Rotert 90° med klokka', # 0th row: right; 0th column: top
'exif-orientation-7' => 'Rotert 90° med klokka og vridd vertikalt', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Rotert 90° mot klokka', # 0th row: left; 0th column: bottom

'exif-componentsconfiguration-0' => 'finnes ikke',

'exif-exposureprogram-0' => 'Ikke definert',
'exif-exposureprogram-1' => 'Manuell',
'exif-exposureprogram-2' => 'Normalt program',
'exif-exposureprogram-7' => 'Portrettmodus (for nærbilder med ufokusert bakgrunn)',
'exif-exposureprogram-8' => 'Landskapsmodus (for landskapsbilder med fokusert bakgrunn)',

'exif-subjectdistance-value' => '$1 meter',

'exif-meteringmode-0'   => 'Ukjent',
'exif-meteringmode-1'   => 'Gjennomsnitt',
'exif-meteringmode-5'   => 'Mønster',
'exif-meteringmode-6'   => 'Delvis',
'exif-meteringmode-255' => 'Annet',

'exif-lightsource-0'   => 'Ukjent',
'exif-lightsource-1'   => 'Dagslys',
'exif-lightsource-4'   => 'Blits',
'exif-lightsource-9'   => 'Fint vær',
'exif-lightsource-10'  => 'Overskyet',
'exif-lightsource-11'  => 'Skygge',
'exif-lightsource-17'  => 'Standardlys A',
'exif-lightsource-18'  => 'Standardlys B',
'exif-lightsource-19'  => 'Standardlys C',
'exif-lightsource-255' => 'Annen lyskilde',

'exif-focalplaneresolutionunit-2' => 'tommer',

'exif-sensingmethod-1' => 'Udefinert',
'exif-sensingmethod-7' => 'Trilineær sensor',

'exif-customrendered-0' => 'Normal prosess',
'exif-customrendered-1' => 'Tilpasset prosess',

'exif-exposuremode-0' => 'Automatisk eksponering',
'exif-exposuremode-1' => 'Manuell eksponering',

'exif-whitebalance-0' => 'Automatisk hvitbalanse',
'exif-whitebalance-1' => 'Manuell hvitbalanse',

'exif-scenecapturetype-0' => 'Standard',
'exif-scenecapturetype-1' => 'Landskap',
'exif-scenecapturetype-2' => 'Portrett',
'exif-scenecapturetype-3' => 'Nattscene',

'exif-gaincontrol-0' => 'Ingen',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Myk',
'exif-contrast-2' => 'Hard',

'exif-saturation-0' => 'Normal',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Myk',
'exif-sharpness-2' => 'Hard',

'exif-subjectdistancerange-0' => 'Ukjent',
'exif-subjectdistancerange-1' => 'Makro',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Nordlig breddegrad',
'exif-gpslatitude-s' => 'Sørlig breddegrad',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Østlig lengdegrad',
'exif-gpslongitude-w' => 'Vestlig lengdegrad',

'exif-gpsstatus-a' => 'Måling pågår',
'exif-gpsstatus-v' => 'Målingsinteroperabilitet',

'exif-gpsmeasuremode-2' => 'todimensjonell måling',
'exif-gpsmeasuremode-3' => 'tredimensjonell måling',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Kilometer per time',
'exif-gpsspeed-m' => 'Miles per time',
'exif-gpsspeed-n' => 'Knop',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Sann retning',
'exif-gpsdirection-m' => 'Magnetisk retning',

# External editor support
'edit-externally'      => 'Rediger denne fila med en ekstern applikasjon',
'edit-externally-help' => 'Se [http://meta.wikimedia.org/wiki/Help:External_editors oppsettsinstruksjonene] for mer informasjon.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'alle',
'imagelistall'     => 'alle',
'watchlistall2'    => 'alle',
'namespacesall'    => 'alle',
'monthsall'        => 'alle',

# E-mail address confirmation
'confirmemail'            => 'Bekreft e-postadresse',
'confirmemail_noemail'    => 'Du har ikke oppgitt en gydlig e-postadresse i [[Special:Preferences|innstillingene dine]].',
'confirmemail_text'       => 'Denne wikien krever at du bekrefter e-postadressa di før du kan benytte e-posttjenester. Trykk på knappen under for å sende en bekreftelsesmelding til din e-postadresse. E-posten vil inneholde en lenke med en kode; last lenken i nettleseren din for å bekrefte at e-postadressa er gyldig.',
'confirmemail_pending'    => '<div class="error">
En bekreftelseskode har allerede blitt sendt til deg på e-post; om du nylig har opprettet kontoen din, kan du ønske å vente noen minutter før du spør om ny kode.
</div>',
'confirmemail_send'       => 'Send en bekreftelseskode.',
'confirmemail_sent'       => 'Bekreftelsesmelding sendt.',
'confirmemail_oncreate'   => 'En bekreftelseskode ble sendt til din e-postadresse. Denne koden er ikke nødvendig for å logge inn, men er nødvendig for å slå på e-postbaserte tjenester i denne wikien.',
'confirmemail_sendfailed' => 'Kunne ikke sende bekreftelseskode. Sjekk e-postadressa for ugyldige tegn.

E-postsenderen ga følgende melding: $1',
'confirmemail_invalid'    => 'Ugyldig bekreftelseskode. Koden kan ha utløpt.',
'confirmemail_needlogin'  => 'Du må $1 for å bekrefte e-postadressa di.',
'confirmemail_success'    => 'Din e-postadresse har nå blitt bekreftet. Du kan nå logge inn og nyte wikien.',
'confirmemail_loggedin'   => 'E-postadressa di har blitt bekreftet.',
'confirmemail_error'      => 'Noe gitt galt i lagringa av din bekreftelse.',
'confirmemail_subject'    => 'Bekreftelsesmelding fra {{SITENAME}}',
'confirmemail_body'       => 'Noen, antageligvis deg, fra IP-adressa $1, har registrert kontoen «$2» på {{SITENAME}}, og oppgitt denne e-postadressa. For å bekrefte at kontoen virkelig tilhører deg og for å aktivere e-posttjenester på {{SITENAME}}, åpne denne lenken i din nettleser: $3

Om dette ikke er deg, ikke følg lenken. Denne bekreftelseskoden vil løpe ut $4.',

# Scary transclusion
'scarytranscludedisabled' => '[Interwiki-transkludering er slått av]',
'scarytranscludefailed'   => '[Malen kunne ikke hentes for $1; beklager]',
'scarytranscludetoolong'  => '[URL-en er for lang; beklager]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">
Tilbakesporinger for denne artikkelen:<br />
$1
</div>',
'trackbackremove'   => ' ([$1 Slett])',
'trackbacklink'     => 'Tilbakesporing',
'trackbackdeleteok' => 'Tilbakesporingen ble slettet.',

# Delete conflict
'deletedwhileediting' => 'Advarsel: Denne siden har blitt slettet etter at du begynte å redigere den!',
'confirmrecreate'     => '«[[User:$1|$1]]» ([[User talk:$1|diskusjon]]) slettet siden etter at du begynte å redigere den, med begrunnelsen «$2». Vennligst bekreft at du vil gjenopprette siden.',
'recreate'            => 'Gjenopprett',

# HTML dump
'redirectingto' => 'Omdirigerer til [[$1]]…',

# action=purge
'confirm_purge'        => "Vil du slette tjenerens mellomlagrede versjon (''cache'') av denne siden? $1",
'confirm_purge_button' => 'OK',

# AJAX search
'searchcontaining' => "Søk etter artikler som inneholder ''$1''.",
'searchnamed'      => "Søk for artikler ved navn ''$1''.",
'articletitles'    => "Artikler som begynner med ''$1''",
'hideresults'      => 'Skjul resultater',

# Multipage image navigation
'imgmultipageprev'   => '&larr; forrige side',
'imgmultipagenext'   => 'neste side &rarr;',
'imgmultigo'         => 'Gå!',
'imgmultigotopre'    => 'Gå til side',
'imgmultiparseerror' => 'Denne bildefilen ser ut til å være ødelagt eller gal, så {{SITENAME}} kan ikke hente en liste over sider.',

# Table pager
'ascending_abbrev'         => 'stig.',
'descending_abbrev'        => 'synk.',
'table_pager_next'         => 'Neste side',
'table_pager_prev'         => 'Forrige side',
'table_pager_first'        => 'Første side',
'table_pager_last'         => 'Siste side',
'table_pager_limit'        => 'Vis $1 elementer per side',
'table_pager_limit_submit' => 'Gå',
'table_pager_empty'        => 'Ingen resultater',

# Auto-summaries
'autosumm-blank'   => 'Fjerner alt innhold fra siden',
'autosumm-replace' => 'Erstatter siden med «$1»',
'autoredircomment' => 'Omdirigerer til [[$1]]',
'autosumm-new'     => 'Ny side: $1',

# Live preview
'livepreview-loading' => 'Laster…',
'livepreview-ready'   => 'Laster… Klar!',
'livepreview-failed'  => 'Levende forhåndsvisning mislyktes. Prøv vanlig forhåndsvisning.',
'livepreview-error'   => 'Tilkobling mislyktes: $1 «$2»
Prøv vanlig forhåndsvisning.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Endringer nyere enn $1 {{PLURAL:$1|sekund|sekunder}} vises muligens ikke i denne lista.',
'lag-warn-high'   => 'På grunn av stor databaseforsinkelse, vil ikke endringer som er nyere enn $1 {{PLURAL:$1|sekund|sekunder}} vises i denne lista.',

# Watchlist editor
'watchlistedit-numitems'       => 'Overvåkningslista di inneholder {{PLURAL:$1|én tittel|$1 titler}}, ikke inkludert diskusjonssider.',
'watchlistedit-noitems'        => 'Overvåkningslista di inneholder ingen titler.',
'watchlistedit-normal-title'   => 'Rediger overvåkningsliste',
'watchlistedit-normal-legend'  => 'Fjern titler fra overvåkninglista',
'watchlistedit-normal-explain' => 'Titler på overvåkningslista di vises nedenunder. For å fjerne en tittel, merk av boksen ved siden av den og klikk Fjern titler. Du kan også [[Special:Watchlist/raw|redigere den rå overvåkningslista]] eller [[Special:Watchlist/clear|fjerne alle titler]].',
'watchlistedit-normal-submit'  => 'Fjern titler',
'watchlistedit-normal-done'    => '{{PLURAL:$1|Én tittel|$1 titler}} ble fjernet fra overvåkningslisten din:',
'watchlistedit-raw-title'      => 'Rediger rå overvåkningsliste',
'watchlistedit-raw-legend'     => 'Rediger rå overvåkningsliste',
'watchlistedit-raw-explain'    => 'Titler på overvåkningslista di vises nedenunder, og kan redigeres ved å legge til eller fjerne fra lista; én tittel per linje. Når du er ferdig, trykk Oppdater overvåkningsliste. Du kan også [[Special:Watchlist/edit|bruke standardverktøyet]].',
'watchlistedit-raw-titles'     => 'Titler:',
'watchlistedit-raw-submit'     => 'Oppdater overvåkningsliste',
'watchlistedit-raw-done'       => 'Overvåkningslista di har blitt oppdatert.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|Én tittel|$1 titler}} ble lagt til:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|Én tittel|$1 titler}} ble fjernet:',

# Watchlist editing tools
'watchlisttools-view' => 'Vis relevante endringer',
'watchlisttools-edit' => 'Vis og rediger overvåkningsliste',
'watchlisttools-raw'  => 'Rediger rå overvåkningsliste',

);

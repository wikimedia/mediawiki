<?php
/** Norwegian (bokmål)‬ (‪Norsk (bokmål)‬)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Audun
 * @author Boivie
 * @author Byrial
 * @author Eirik
 * @author EivindJ
 * @author Finnrind
 * @author Guaca
 * @author H92
 * @author Harald Khan
 * @author Jon Harald Søby
 * @author Jóna Þórunn
 * @author Kph
 * @author Kph-no
 * @author Laaknor
 * @author Najami
 * @author Nghtwlkr
 * @author Nsaa
 * @author Purodha
 * @author Qaqqalik
 * @author Samuelsen
 * @author Simny
 * @author Stigmj
 * @author Teak
 * @author לערי ריינהארט
 */

$bookstoreList = array(
	'Antikvariat.net' => 'http://www.antikvariat.net/',
	'Frida' => 'http://wo.uio.no/as/WebObjects/frida.woa/wa/fres?action=sok&isbn=$1&visParametre=1&sort=alfabetisk&bs=50',
	'Bibsys' => 'http://ask.bibsys.no/ask/action/result?cmd=&kilde=biblio&fid=isbn&term=$1&op=and&fid=bd&term=&arstall=&sortering=sortdate-&treffPrSide=50',
	'Akademika' => 'http://www.akademika.no/sok.php?ts=4&sok=$1',
	'Haugenbok' => 'http://www.haugenbok.no/resultat.cfm?st=extended&isbn=$1',
	'Amazon.com' => 'http://www.amazon.com/exec/obidos/ISBN=$1'
);

$namespaceNames = array(
	NS_MEDIA            => 'Medium',
	NS_SPECIAL          => 'Spesial',
	NS_TALK             => 'Diskusjon',
	NS_USER             => 'Bruker',
	NS_USER_TALK        => 'Brukerdiskusjon',
	NS_PROJECT_TALK     => '$1-diskusjon',
	NS_FILE             => 'Fil',
	NS_FILE_TALK        => 'Fildiskusjon',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki-diskusjon',
	NS_TEMPLATE         => 'Mal',
	NS_TEMPLATE_TALK    => 'Maldiskusjon',
	NS_HELP             => 'Hjelp',
	NS_HELP_TALK        => 'Hjelpdiskusjon',
	NS_CATEGORY         => 'Kategori',
	NS_CATEGORY_TALK    => 'Kategoridiskusjon',
);

$namespaceAliases = array(
	'Bilde' => NS_FILE,
	'Bildediskusjon' => NS_FILE_TALK,
);

$separatorTransformTable = array( ',' => "\xc2\xa0", '.' => ',' );
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
	'DoubleRedirects'           => array( 'Doble_omdirigeringer' ),
	'BrokenRedirects'           => array( 'Ødelagte_omdirigeringer' ),
	'Disambiguations'           => array( 'Pekere' ),
	'Userlogin'                 => array( 'Logg_inn' ),
	'Userlogout'                => array( 'Logg_ut' ),
	'CreateAccount'             => array( 'Opprett_konto' ),
	'Preferences'               => array( 'Innstillinger' ),
	'Watchlist'                 => array( 'Overvåkningsliste', 'Overvåkingsliste' ),
	'Recentchanges'             => array( 'Siste_endringer' ),
	'Upload'                    => array( 'Last_opp' ),
	'Listfiles'                 => array( 'Filliste', 'Bildeliste', 'Billedliste' ),
	'Newimages'                 => array( 'Nye_filer', 'Nye_bilder' ),
	'Listusers'                 => array( 'Brukerliste' ),
	'Listgrouprights'           => array( 'Grupperettigheter' ),
	'Statistics'                => array( 'Statistikk' ),
	'Randompage'                => array( 'Tilfeldig', 'Tilfeldig_side' ),
	'Lonelypages'               => array( 'Foreldreløse_sider' ),
	'Uncategorizedpages'        => array( 'Ukategoriserte_sider' ),
	'Uncategorizedcategories'   => array( 'Ukategoriserte_kategorier' ),
	'Uncategorizedimages'       => array( 'Ukategoriserte_filer', 'Ukategoriserte_bilder' ),
	'Uncategorizedtemplates'    => array( 'Ukategoriserte_maler' ),
	'Unusedcategories'          => array( 'Ubrukte_kategorier' ),
	'Unusedimages'              => array( 'Ubrukte_filer', 'Ubrukte_bilder' ),
	'Wantedpages'               => array( 'Ønskede_sider', 'Ødelagte_lenker' ),
	'Wantedcategories'          => array( 'Ønskede_kategorier' ),
	'Wantedfiles'               => array( 'Ønskede_filer' ),
	'Wantedtemplates'           => array( 'Ønskede_maler' ),
	'Mostlinked'                => array( 'Mest_lenkede_sider', 'Mest_lenket' ),
	'Mostlinkedcategories'      => array( 'Mest_lenkede_kategorier', 'Mest_brukte_kategorier' ),
	'Mostlinkedtemplates'       => array( 'Mest_lenkede_maler', 'Mest_brukte_maler' ),
	'Mostimages'                => array( 'Mest_lenkede_filer', 'Flest_filer', 'Flest_bilder' ),
	'Mostcategories'            => array( 'Flest_kategorier' ),
	'Mostrevisions'             => array( 'Flest_revisjoner' ),
	'Fewestrevisions'           => array( 'Færrest_revisjoner' ),
	'Shortpages'                => array( 'Korte_sider' ),
	'Longpages'                 => array( 'Lange_sider' ),
	'Newpages'                  => array( 'Nye_sider' ),
	'Ancientpages'              => array( 'Gamle_sider' ),
	'Deadendpages'              => array( 'Blindveisider' ),
	'Protectedpages'            => array( 'Beskyttede_sider' ),
	'Protectedtitles'           => array( 'Beskyttede_titler' ),
	'Allpages'                  => array( 'Alle_sider' ),
	'Prefixindex'               => array( 'Prefiksindeks' ),
	'Ipblocklist'               => array( 'Blokkeringsliste', 'IP-blokkeringsliste' ),
	'Unblock'                   => array( 'Avblokker' ),
	'Specialpages'              => array( 'Spesialsider' ),
	'Contributions'             => array( 'Bidrag' ),
	'Emailuser'                 => array( 'E-post' ),
	'Confirmemail'              => array( 'Bekreft_e-postadresse' ),
	'Whatlinkshere'             => array( 'Lenker_hit' ),
	'Recentchangeslinked'       => array( 'Relaterte_endringer' ),
	'Movepage'                  => array( 'Flytt_side' ),
	'Blockme'                   => array( 'Blokker_meg' ),
	'Booksources'               => array( 'Bokkilder' ),
	'Categories'                => array( 'Kategorier' ),
	'Export'                    => array( 'Eksporter' ),
	'Version'                   => array( 'Versjon' ),
	'Allmessages'               => array( 'Alle_systembeskjeder' ),
	'Log'                       => array( 'Logg', 'Logger' ),
	'Blockip'                   => array( 'Blokker', 'Blokker_IP', 'Blokker_bruker' ),
	'Undelete'                  => array( 'Gjenopprett' ),
	'Import'                    => array( 'Importer' ),
	'Lockdb'                    => array( 'Lås_database' ),
	'Unlockdb'                  => array( 'Åpne_database' ),
	'Userrights'                => array( 'Brukerrettigheter' ),
	'MIMEsearch'                => array( 'MIME-søk' ),
	'FileDuplicateSearch'       => array( 'Filduplikatsøk' ),
	'Unwatchedpages'            => array( 'Uovervåkede_sider' ),
	'Listredirects'             => array( 'Omdirigeringsliste' ),
	'Revisiondelete'            => array( 'Revisjonssletting' ),
	'Unusedtemplates'           => array( 'Ubrukte_maler' ),
	'Randomredirect'            => array( 'Tilfeldig_omdirigering' ),
	'Mypage'                    => array( 'Min_side' ),
	'Mytalk'                    => array( 'Min_diskusjon' ),
	'Mycontributions'           => array( 'Mine_bidrag' ),
	'Listadmins'                => array( 'Administratorliste', 'Administratorer' ),
	'Listbots'                  => array( 'Robotliste', 'Liste_over_roboter' ),
	'Popularpages'              => array( 'Populære_sider' ),
	'Search'                    => array( 'Søk' ),
	'Resetpass'                 => array( 'Endre_passord', 'TIlbakestill_passord' ),
	'Withoutinterwiki'          => array( 'Uten_interwiki' ),
	'MergeHistory'              => array( 'Flett_historikk' ),
	'Filepath'                  => array( 'Filsti' ),
	'Invalidateemail'           => array( 'Ugyldiggjøre_e-post' ),
	'Blankpage'                 => array( 'Blank_side' ),
	'LinkSearch'                => array( 'Lenkesøk' ),
	'DeletedContributions'      => array( 'Slettede_bidrag' ),
	'Tags'                      => array( 'Tagger' ),
	'Activeusers'               => array( 'Aktive_brukere' ),
	'ComparePages'              => array( 'Sammenlign_sider' ),
	'Badtitle'                  => array( 'Ugyldig_tittel' ),
);

$magicWords = array(
	'redirect'              => array( '0', '#OMDIRIGERING', '#REDIRECT' ),
	'numberofpages'         => array( '1', 'ANTALLSIDER', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'ANTALLARTIKLER', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'ANTALLFILER', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'ANTALLBRUKERE', 'NUMBEROFUSERS' ),
	'numberofactiveusers'   => array( '1', 'ANTALLAKTIVEBRUKERE', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'         => array( '1', 'ANTALLREDIGERINGER', 'NUMBEROFEDITS' ),
	'numberofviews'         => array( '1', 'ANTALLVISNINGER', 'NUMBEROFVIEWS' ),
	'pagename'              => array( '1', 'SIDENAVN', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'SIDENAVNE', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'NAVNEROM', 'NAMESPACE' ),
	'namespacee'            => array( '1', 'NAVNEROME', 'NAMESPACEE' ),
	'talkspace'             => array( '1', 'DISKUSJONSROM', 'TALKSPACE' ),
	'talkspacee'            => array( '1', 'DISKUSJONSROME', 'TALKSPACEE' ),
	'subjectspace'          => array( '1', 'SUBJEKTROM', 'ARTIKKELROM', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'         => array( '1', 'SUBJEKTROME', 'ARTIKKELROME', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'          => array( '1', 'FULLTSIDENAVN', 'FULLPAGENAME' ),
	'fullpagenamee'         => array( '1', 'FULLTSIDENAVNE', 'FULLPAGENAMEE' ),
	'subpagename'           => array( '1', 'UNDERSIDENAVN', 'SUBPAGENAME' ),
	'subpagenamee'          => array( '1', 'UNDERSIDENAVNE', 'SUBPAGENAMEE' ),
	'basepagename'          => array( '1', 'GRUNNSIDENAVN', 'BASEPAGENAME' ),
	'basepagenamee'         => array( '1', 'GRUNNSIDENAVNE', 'BASEPAGENAMEE' ),
	'talkpagename'          => array( '1', 'DISKUSJONSSIDENAVN', 'TALKPAGENAME' ),
	'talkpagenamee'         => array( '1', 'DISKUSJONSSIDENAVNE', 'TALKPAGENAMEE' ),
	'subjectpagename'       => array( '1', 'SUBJEKTSIDENAVN', 'ARTIKKELSIDENAVN', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'      => array( '1', 'SUBJEKTSIDENAVNE', 'ARTIKKELSIDENAVNE', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'img_thumbnail'         => array( '1', 'miniatyr', 'mini', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'miniatyr=$1', 'mini=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'høyre', 'right' ),
	'img_left'              => array( '1', 'venstre', 'left' ),
	'img_none'              => array( '1', 'ingen', 'none' ),
	'img_center'            => array( '1', 'sentrer', 'senter', 'center', 'centre' ),
	'img_framed'            => array( '1', 'ramme', 'framed', 'enframed', 'frame' ),
	'img_frameless'         => array( '1', 'ingenramme', 'frameless' ),
	'img_page'              => array( '1', 'side=$1', 'side $1', 'page=$1', 'page $1' ),
	'img_top'               => array( '1', 'topp', 'top' ),
	'img_middle'            => array( '1', 'midt', 'middle' ),
	'img_bottom'            => array( '1', 'bunn', 'bottom' ),
	'img_text_bottom'       => array( '1', 'tekst-bunn', 'text-bottom' ),
	'img_link'              => array( '1', 'lenke=$1', 'link=$1' ),
	'ns'                    => array( '0', 'NR:', 'NS:' ),
	'localurl'              => array( '0', 'LOKALURL:', 'LOCALURL:' ),
	'localurle'             => array( '0', 'LOKALURLE:', 'LOCALURLE:' ),
	'server'                => array( '0', 'TJENER', 'SERVER' ),
	'servername'            => array( '0', 'TJENERNAVN', 'SERVERNAME' ),
	'grammar'               => array( '0', 'GRAMMATIKK:', 'GRAMMAR:' ),
	'gender'                => array( '0', 'KJØNN:', 'GENDER:' ),
	'special'               => array( '0', 'spesial', 'special' ),
	'filepath'              => array( '0', 'FILSTI:', 'FILEPATH:' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Strek under lenker:',
'tog-highlightbroken'         => 'Formater lenker til ikke-eksisterende sider <a href="" class="new">slik</a> (alternativt: slik<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Blokkjusterte avsnitt',
'tog-hideminor'               => 'Skjul mindre endringer i siste endringer',
'tog-hidepatrolled'           => 'Skjul patruljerte endringer i siste endringer',
'tog-newpageshidepatrolled'   => 'Skjul patruljerte sider fra listen over nye sider.',
'tog-extendwatchlist'         => 'Utvid overvåkningslisten til å vise alle endringer, ikke bare de siste',
'tog-usenewrc'                => 'Forbedret siste endringer (krever JavaScript)',
'tog-numberheadings'          => 'Nummerer overskrifter',
'tog-showtoolbar'             => 'Vis verktøylinje (JavaScript)',
'tog-editondblclick'          => 'Rediger sider ved å dobbeltklikke (JavaScript)',
'tog-editsection'             => 'Rediger avsnitt ved hjelp av [rediger]-lenke',
'tog-editsectiononrightclick' => 'Rediger avsnitt ved å høyreklikke på avsnittsoverskrift (JavaScript)',
'tog-showtoc'                 => 'Vis innholdsfortegnelse (for sider med mer enn tre seksjoner)',
'tog-rememberpassword'        => 'Husk meg i denne nettleseren (i høyst $1 {{PLURAL:$1|dag|dager}})',
'tog-watchcreations'          => 'Overvåk sider jeg oppretter',
'tog-watchdefault'            => 'Overvåk alle sider jeg redigerer',
'tog-watchmoves'              => 'Overvåk sider jeg flytter',
'tog-watchdeletion'           => 'Overvåk sider jeg sletter',
'tog-minordefault'            => 'Merk i utgangspunktet alle redigeringer som mindre',
'tog-previewontop'            => 'Flytt forhåndsvisningen foran redigeringsboksen',
'tog-previewonfirst'          => 'Vis forhåndsvisning ved første redigering av en side',
'tog-nocache'                 => 'Deaktiver nettlesermellomlagring av sider («caching»)',
'tog-enotifwatchlistpages'    => 'Send meg en e-post når sider på overvåkningslisten blir endret',
'tog-enotifusertalkpages'     => 'Send meg en e-post ved endringer av brukerdiskusjonssiden min',
'tog-enotifminoredits'        => 'Send meg en e-post også ved mindre sideendringer',
'tog-enotifrevealaddr'        => 'Vis min e-postadresse i utgående meldinger',
'tog-shownumberswatching'     => 'Vis antall overvåkende brukere',
'tog-oldsig'                  => 'Forhåndsvisning av nåværende signatur:',
'tog-fancysig'                => 'Signatur som wikitekst uten automatisk lenke',
'tog-externaleditor'          => 'Bruk ekstern behandler som standard (kun for viderekomne, krever spesielle innstillinger på din datamaskin. [http://www.mediawiki.org/wiki/Manual:External_editors Mer informasjon.])',
'tog-externaldiff'            => 'Bruk ekstern differanse som standard (kun for viderekomne, krever spesielle innstillinger på din datamaskin. [http://www.mediawiki.org/wiki/Manual:External_editors Mer informasjon.])',
'tog-showjumplinks'           => 'Slå på «gå til»-lenker',
'tog-uselivepreview'          => 'Bruk levende forhåndsvisning (eksperimentell JavaScript)',
'tog-forceeditsummary'        => 'Advar meg når jeg ikke gir noen redigeringsforklaring',
'tog-watchlisthideown'        => 'Skjul egne endringer fra overvåkningslisten',
'tog-watchlisthidebots'       => 'Skjul robotendringer fra overvåkningslisten',
'tog-watchlisthideminor'      => 'Skjul mindre endringer fra overvåkningslisten',
'tog-watchlisthideliu'        => 'Skjul endringer av innloggede brukere fra overvåkningslisten',
'tog-watchlisthideanons'      => 'Skjul endringer av anonyme brukere fra overvåkningslisten',
'tog-watchlisthidepatrolled'  => 'Skjul patruljerte endringer fra overvåkningslisten',
'tog-nolangconversion'        => 'Slå av variantkonvertering',
'tog-ccmeonemails'            => 'Send meg kopier av e-poster jeg sender til andre brukere',
'tog-diffonly'                => 'Ikke vis sideinnhold under differ',
'tog-showhiddencats'          => 'Vis skjulte kategorier',
'tog-norollbackdiff'          => 'Ikke vis diff etter tilbakestilling',

'underline-always'  => 'Alltid',
'underline-never'   => 'Aldri',
'underline-default' => 'Bruk nettleserstandard',

# Font style option in Special:Preferences
'editfont-style'     => 'Endre stilen for skrifttypen i området:',
'editfont-default'   => 'Nettleserstandard',
'editfont-monospace' => 'Skrift med fast bredde',
'editfont-sansserif' => 'Sans-serif',
'editfont-serif'     => 'Serif',

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

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategori|Kategorier}}',
'category_header'                => 'Sider i kategorien «$1»',
'subcategories'                  => 'Underkategorier',
'category-media-header'          => 'Filer i kategorien «$1»',
'category-empty'                 => "''Denne kategorien inneholder for tiden ingen artikler eller filer.''",
'hidden-categories'              => '{{PLURAL:$1|Skjult kategori|Skjulte kategorier}}',
'hidden-category-category'       => 'Skjulte kategorier',
'category-subcat-count'          => '{{PLURAL:$2|Denne kategorien har kun den følgende underkategorien.|Denne kategorien har følgende {{PLURAL:$1|underkategori|$1 underkategorier}}, av totalt $2.}}',
'category-subcat-count-limited'  => 'Kategorien har følgende {{PLURAL:$1|underkategori|$1 underkategorier}}.',
'category-article-count'         => '{{PLURAL:$2|Denne kategorien inneholder kun den følgende siden.|Følgende {{PLURAL:$1|side|$1 sider}} er i denne kategorien, av totalt $2.}}',
'category-article-count-limited' => 'Følgende {{PLURAL:$1|side|$1 sider}} er i denne kategorien.',
'category-file-count'            => '{{PLURAL:$2|Denne kategorien inneholder kun den følgende filen.|Følgende {{PLURAL:$1|fil|$1 filer}} er i denne kategorien, av totalt $2.}}',
'category-file-count-limited'    => 'Følgende {{PLURAL:$1|fil|$1 filer}} er i denne kategorien.',
'listingcontinuesabbrev'         => ' forts.',
'index-category'                 => 'Indekserte sider',
'noindex-category'               => 'Ikke-indekserte sider',

'mainpagetext'      => "'''MediaWiki-programvaren er nå installert.'''",
'mainpagedocfooter' => 'Se [http://meta.wikimedia.org/wiki/Help:Contents brukerveiledningen] for informasjon om hvordan du bruker wiki-programvaren.

==Å starte==
*[http://www.mediawiki.org/wiki/Manual:Configuration_settings Oppsettsliste]
*[http://www.mediawiki.org/wiki/Manual:FAQ Ofte stilte spørsmål]
*[https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki e-postliste]',

'about'         => 'Om',
'article'       => 'Innholdsside',
'newwindow'     => '(åpnes i et nytt vindu)',
'cancel'        => 'Avbryt',
'moredotdotdot' => 'Mer …',
'mypage'        => 'Min side',
'mytalk'        => 'Min diskusjonsside',
'anontalk'      => 'Brukerdiskusjon for denne IP-adressen',
'navigation'    => 'Navigasjon',
'and'           => '&#32;og',

# Cologne Blue skin
'qbfind'         => 'Finn',
'qbbrowse'       => 'Bla gjennom',
'qbedit'         => 'Rediger',
'qbpageoptions'  => 'Sideinnstillinger',
'qbpageinfo'     => 'Sideinformasjon',
'qbmyoptions'    => 'Egne innstillinger',
'qbspecialpages' => 'Spesialsider',
'faq'            => 'Ofte stilte spørsmål',
'faqpage'        => 'Project:Ofte stilte spørsmål',

# Vector skin
'vector-action-addsection'       => 'Nytt emne',
'vector-action-delete'           => 'Slett',
'vector-action-move'             => 'Flytt',
'vector-action-protect'          => 'Beskytt',
'vector-action-undelete'         => 'Gjenopprett',
'vector-action-unprotect'        => 'Opphev beskyttelse',
'vector-simplesearch-preference' => 'Aktiver forbedrede søkeforslag (kun for drakten Vector)',
'vector-view-create'             => 'Opprett',
'vector-view-edit'               => 'Rediger',
'vector-view-history'            => 'Vis historikk',
'vector-view-view'               => 'Les',
'vector-view-viewsource'         => 'Vis kilden',
'actions'                        => 'Handlinger',
'namespaces'                     => 'Navnerom',
'variants'                       => 'Varianter',

'errorpagetitle'    => 'Feil',
'returnto'          => 'Tilbake til $1.',
'tagline'           => 'Fra {{SITENAME}}',
'help'              => 'Hjelp',
'search'            => 'Søk',
'searchbutton'      => 'Søk',
'go'                => 'Gå',
'searcharticle'     => 'Gå',
'history'           => 'Sidehistorikk',
'history_short'     => 'Historikk',
'updatedmarker'     => 'oppdatert siden mitt forrige besøk',
'info_short'        => 'Informasjon',
'printableversion'  => 'Utskriftsvennlig versjon',
'permalink'         => 'Permanent lenke',
'print'             => 'Skriv ut',
'edit'              => 'Rediger',
'create'            => 'Opprett',
'editthispage'      => 'Rediger siden',
'create-this-page'  => 'Opprett denne siden',
'delete'            => 'Slett',
'deletethispage'    => 'Slett denne siden',
'undelete_short'    => 'Gjenopprett {{PLURAL:$1|én revisjon|$1 revisjoner}}',
'protect'           => 'Beskytt',
'protect_change'    => 'endre',
'protectthispage'   => 'Lås siden',
'unprotect'         => 'Åpne',
'unprotectthispage' => 'Åpne siden',
'newpage'           => 'Ny side',
'talkpage'          => 'Diskuter denne siden',
'talkpagelinktext'  => 'Diskusjon',
'specialpage'       => 'Spesialside',
'personaltools'     => 'Personlige verktøy',
'postcomment'       => 'Ny seksjon',
'articlepage'       => 'Vis innholdsside',
'talk'              => 'Diskusjon',
'views'             => 'Visninger',
'toolbox'           => 'Verktøy',
'userpage'          => 'Vis brukerside',
'projectpage'       => 'Vis prosjektside',
'imagepage'         => 'Vis filside',
'mediawikipage'     => 'Vis beskjedside',
'templatepage'      => 'Vis mal',
'viewhelppage'      => 'Vis hjelpeside',
'categorypage'      => 'Vis kategoriside',
'viewtalkpage'      => 'Vis diskusjon',
'otherlanguages'    => 'På andre språk',
'redirectedfrom'    => '(Omdirigert fra $1)',
'redirectpagesub'   => 'Omdirigeringsside',
'lastmodifiedat'    => 'Denne siden ble sist endret $1 kl. $2.',
'viewcount'         => 'Denne siden er vist $1 {{PLURAL:$1|gang|ganger}}.',
'protectedpage'     => 'Låst side',
'jumpto'            => 'Gå til:',
'jumptonavigation'  => 'navigasjon',
'jumptosearch'      => 'søk',
'view-pool-error'   => 'Beklager, serverne er overbelastet for øyeblikket.
For mange brukere forsøker å se denne siden.
Vennligst vent en stund før du prøver å besøke denne siden på nytt.

$1',
'pool-timeout'      => 'Tidsavbudd mens man ventet på låsing',
'pool-queuefull'    => 'Køen er full',
'pool-errorunknown' => 'Ukjent feil',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Om {{SITENAME}}',
'aboutpage'            => 'Project:Om',
'copyright'            => 'Innholdet er tilgjengelig under $1.',
'copyrightpage'        => '{{ns:project}}:Opphavsrett',
'currentevents'        => 'Aktuelt',
'currentevents-url'    => 'Project:Aktuelt',
'disclaimers'          => 'Forbehold',
'disclaimerpage'       => 'Project:Generelle forbehold',
'edithelp'             => 'Redigeringshjelp',
'edithelppage'         => 'Help:Redigering',
'helppage'             => 'Help:Innhold',
'mainpage'             => 'Hovedside',
'mainpage-description' => 'Hovedside',
'policy-url'           => 'Project:Retningslinjer',
'portal'               => 'Prosjektportal',
'portal-url'           => 'Project:Prosjektportal',
'privacy'              => 'Personvern',
'privacypage'          => 'Project:Personvern',

'badaccess'        => 'Rettighetsfeil',
'badaccess-group0' => 'Du har ikke tilgang til å utføre handlingen du prøvde på.',
'badaccess-groups' => 'Handlingen du prøvde å utføre kan kun utføres av brukere i {{PLURAL:$2|gruppa|gruppene}} $1.',

'versionrequired'     => 'Versjon $1 av MediaWiki påtrengt',
'versionrequiredtext' => 'Versjon $1 av MediaWiki er nødvendig for å bruke denne siden. Se [[Special:Version|versjonsiden]]',

'ok'                      => 'OK',
'retrievedfrom'           => 'Hentet fra «$1»',
'youhavenewmessages'      => 'Du har $1 ($2).',
'newmessageslink'         => 'nye meldinger',
'newmessagesdifflink'     => 'siste endring',
'youhavenewmessagesmulti' => 'Du har nye beskjeder på $1',
'editsection'             => 'rediger',
'editold'                 => 'rediger',
'viewsourceold'           => 'vis kilde',
'editlink'                => 'rediger',
'viewsourcelink'          => 'vis kilde',
'editsectionhint'         => 'Rediger avsnitt: $1',
'toc'                     => 'Innhold',
'showtoc'                 => 'vis',
'hidetoc'                 => 'skjul',
'thisisdeleted'           => 'Se eller gjenopprett $1?',
'viewdeleted'             => 'Vis $1?',
'restorelink'             => '{{PLURAL:$1|én slettet revisjon|$1 slettede revisjoner}}',
'feedlinks'               => 'Mating:',
'feed-invalid'            => 'Ugyldig matingstype.',
'feed-unavailable'        => 'Abonnementskilder er ikke tilgjengelig',
'site-rss-feed'           => '$1 RSS-mating',
'site-atom-feed'          => '$1 Atom-mating',
'page-rss-feed'           => '«$1» RSS-mating',
'page-atom-feed'          => '«$1» Atom-mating',
'red-link-title'          => '$1 (siden finnes ikke)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Side',
'nstab-user'      => 'Brukerside',
'nstab-media'     => 'Mediaside',
'nstab-special'   => 'Spesialside',
'nstab-project'   => 'Prosjektside',
'nstab-image'     => 'Fil',
'nstab-mediawiki' => 'Melding',
'nstab-template'  => 'Mal',
'nstab-help'      => 'Hjelp',
'nstab-category'  => 'Kategori',

# Main script and global functions
'nosuchaction'      => 'Funksjonen finnes ikke',
'nosuchactiontext'  => 'Handlingen spesifisert i URLen er ugyldig.
Du kan ha skrevet URLen feil, eller fulgt en link som var feil.
Det kan også være en bug i {{SITENAME}}.',
'nosuchspecialpage' => 'En slik spesialside finnes ikke',
'nospecialpagetext' => 'Du ba om en ugyldig spesialside; en liste over gyldige spesialsider finnes på [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Feil',
'databaseerror'        => 'databasefeil',
'dberrortext'          => 'Det har oppstått en syntaksfeil i en databaseforespørsel.
Dette kan tyde på en feil i programvaren.
Forrige databaseforespørsel var:
<blockquote><tt>$1</tt></blockquote>
fra funksjonen «<tt>$2</tt>».
Databasen returnerte feilen «<tt>$3: $4</tt>».',
'dberrortextcl'        => 'Det oppsto en syntaksfeil i en databaseforespørsel.
Forrige databaseforespørsel var:
«$1»
fra funksjonen «$2».
Databasen returnerte feilen «$3: $4».',
'laggedslavemode'      => 'Advarsel: Dette kan være en eldre versjon av siden.',
'readonly'             => 'Databasen er skrivebeskyttet',
'enterlockreason'      => 'Skriv en begrunnelse for skrivebeskyttelsen, inkludert et estimat for når den blir opphevet',
'readonlytext'         => 'Databasen er for øyeblikket skrivebeskyttet, sannsynligvis på grunn av rutinemessig vedlikehold.

Administratoren som låste databasen ga forklaringen: $1',
'missing-article'      => 'Databasen fant ikke teksten på siden som den burde ha funnet, med navnet «$1» $2.

Dette skyldes vanligvis at man følger en utdatert diff- eller historikklenke til en side som har blitt slettet.

Om dette ikke er tilfellet kan du ha oppdaget en feil i programvaren.
Vennligst rapporter dette til en [[Special:ListUsers/sysop|administrator]], oppgi da nettadressen.',
'missingarticle-rev'   => '(revisjon#: $1)',
'missingarticle-diff'  => '(diff: $1, $2)',
'readonly_lag'         => 'Databasen er automatisk skrivebeskyttet så slavetjenerne kan ta igjen mestertjeneren',
'internalerror'        => 'Intern feil',
'internalerror_info'   => 'Intern feil: $1',
'fileappenderrorread'  => 'Klarte ikke å lese «$1» når data skulle tilføyes.',
'fileappenderror'      => 'Kunne ikke legge "$1" til "$2".',
'filecopyerror'        => 'Klarte ikke å kopiere filen «$1» til «$2».',
'filerenameerror'      => 'Klarte ikke å døpe om filen «$1» til «$2».',
'filedeleteerror'      => 'Klarte ikke å slette filen «$1».',
'directorycreateerror' => 'Klarte ikke å opprette mappe «$1».',
'filenotfound'         => 'Klarte ikke å finne filen «$1».',
'fileexistserror'      => 'Klarte ikke å skrive til filen «$1»: filen finnes fra før',
'unexpected'           => 'Uventet verdi: «$1»=«$2».',
'formerror'            => 'Feil: klarte ikke å sende skjema',
'badarticleerror'      => 'Handlingen kan ikke utføres på denne siden.',
'cannotdelete'         => 'Kunne ikke slette fila «$1». Den kan ha blitt slettet av noen andre.',
'badtitle'             => 'Ugyldig tittel',
'badtitletext'         => 'Den ønskede tittelen var ugyldig, tom eller feilaktig lenket fra en annen wiki.
Det kan inneholder en eller flere tegn som ikke kan brukes i titler.',
'perfcached'           => 'Følgende data er en lagret kopi, og ikke nødvendigvis den siste versjonen i databasen.',
'perfcachedts'         => 'Følgende data er en lagret kopi, og ble sist oppdatert $1.',
'querypage-no-updates' => 'Oppdateringer for denne siden er slått av. Data her blir ikke gjenoppfrisket.',
'wrong_wfQuery_params' => 'Gale paramtere til wfQuery()<br />
Funksjon: $1<br />
Spørring: $2',
'viewsource'           => 'Vis kildetekst',
'viewsourcefor'        => 'for $1',
'actionthrottled'      => 'Handlingsgrense overskredet',
'actionthrottledtext'  => 'For å beskytte mot spam, kan du ikke utføre denne handlingen for mange ganger i løpet av et kort tidssrom, og du har overskredet denne grensen. Prøv igjen om noen minutter.',
'protectedpagetext'    => 'Denne siden har blitt låst for redigeringer.',
'viewsourcetext'       => 'Du kan se og kopiere kilden til denne siden:',
'protectedinterface'   => 'Denne siden viser brukergrensesnittet for programvaren, og er låst for å hindre misbruk.',
'editinginterface'     => "'''Advarsel:''' Du redigerer en side som brukes i grensesnittet for programvaren. Endringer på denne siden vil påvirke hvordan grensesnittet vil se ut. For oversettelser er det best om du bruker [http://translatewiki.net/wiki/Main_Page?setlang=no translatewiki.net], prosjektet for oversettelse av MediaWiki.",
'sqlhidden'            => '(SQL-spørring skjult)',
'cascadeprotected'     => 'Denne siden er låst for redigering fordi den inkluderes på følgende sider som har dypbeskyttelse slått på:<!--{{PLURAL:$1}}-->
$2',
'namespaceprotected'   => "Du har ikke tillatelse til å redigere sider i navnerommet '''$1'''.",
'customcssjsprotected' => 'Du har ikke tillatelse til å redigere denne siden, fordi den inneholder en annen brukers personlige innstillinger.',
'ns-specialprotected'  => 'Sier i navnerommet {{ns:special}} kan ikke redigeres.',
'titleprotected'       => "Denne tittelen har blitt låst for oppretting av [[User:$1|$1]].
Den angitte grunnen er ''$2''.",

# Virus scanner
'virus-badscanner'     => "Dårlig konfigurasjon: ukjent virusskanner: ''$1''",
'virus-scanfailed'     => 'skanning mislyktes (kode $1)',
'virus-unknownscanner' => 'ukjent antivirusprogram:',

# Login and logout pages
'logouttext'                 => "'''Du er nå logget ut.'''

Du kan fortsette å bruke {{SITENAME}} anonymt, eller [[Special:UserLogin|logge inn igjen]] som samme eller annen bruker.
Merk at noen sider kan vise at du fortsatt er logget inn fram til du tømmer mellomlageret i nettleseren.",
'welcomecreation'            => '==Velkommen, $1!==
Brukerkontoen din har blitt opprettet.
Ikke glem å endre [[Special:Preferences|innstillingene]] dine.',
'yourname'                   => 'Brukernavn:',
'yourpassword'               => 'Passord:',
'yourpasswordagain'          => 'Gjenta passord',
'remembermypassword'         => 'Husk meg på denne datamaskinen (i maks $1 {{PLURAL:$1|dag|dager}})',
'securelogin-stick-https'    => 'Vær fortsatt tilkoblet til HTTPS etter innlogging',
'yourdomainname'             => 'Ditt domene',
'externaldberror'            => 'Det var en ekstern autentifiseringsfeil, eller du kan ikke oppdatere din eksterne konto.',
'login'                      => 'Logg inn',
'nav-login-createaccount'    => 'Logg inn eller opprett en konto',
'loginprompt'                => 'Du må ha slått på informasjonskapsler for å logge in på {{SITENAME}}.',
'userlogin'                  => 'Logg inn eller opprett en konto',
'userloginnocreate'          => 'Logg inn',
'logout'                     => 'Logg ut',
'userlogout'                 => 'Logg ut',
'notloggedin'                => 'Ikke logget inn',
'nologin'                    => "Er du ikke registrert? '''$1'''.",
'nologinlink'                => 'Opprett en konto',
'createaccount'              => 'Opprett konto',
'gotaccount'                 => "Har du allerede et brukernavn? '''$1'''.",
'gotaccountlink'             => 'Logg inn',
'createaccountmail'          => 'per e-post',
'createaccountreason'        => 'Årsak:',
'badretype'                  => 'Passordene samsvarte ikke.',
'userexists'                 => 'Brukernavnet er allerede i bruk. Velg et nytt.',
'loginerror'                 => 'Innloggingsfeil',
'createaccounterror'         => 'Kunne ikke opprette konto: $1',
'nocookiesnew'               => 'Din brukerkonto er nå opprettet, men du har ikke logget på. {{SITENAME}} bruker informasjonskapsler («cookies») for å logge brukere på. Du har slått dem av. Slå dem p åfor å kunne logge på med ditt nye brukernavn og passord.',
'nocookieslogin'             => '{{SITENAME}} bruker informasjonskapsler («cookies») for å logge brukere på. Du har slått dem av. Slå dem på og prøv igjen.',
'noname'                     => 'Du har ikke oppgitt et gyldig brukernavn.',
'loginsuccesstitle'          => 'Du er nå logget inn',
'loginsuccess'               => 'Du er nå logget inn på {{SITENAME}} som «$1».',
'nosuchuser'                 => 'Det eksisterer ingen bruker ved navn «$1».
Husk at det er forskjell på store og små bokstaver.
Sjekk stavemåten eller [[Special:UserLogin/signup|opprett en ny konto]].',
'nosuchusershort'            => 'Det finnes ingen bruker ved navn «<nowiki>$1</nowiki>». Kontroller stavemåten.',
'nouserspecified'            => 'Du må oppgi et brukernavn.',
'login-userblocked'          => 'Brukeren er blokkert. Innlogging er ikke tillatt.',
'wrongpassword'              => 'Du har oppgitt et ugyldig passord. Prøv igjen.',
'wrongpasswordempty'         => 'Du oppga ikke noe passord. Prøv igjen.',
'passwordtooshort'           => 'Passord må ha minst {{PLURAL:$1|ett tegn|$1 tegn}}.',
'password-name-match'        => 'Passordet ditt må være anderledes enn brukernavnet.',
'password-login-forbidden'   => 'Bruken av disse brukernavn og passord har blitt forbudt.',
'mailmypassword'             => 'Send nytt passord',
'passwordremindertitle'      => 'Nytt midlertidig passord fra {{SITENAME}}',
'passwordremindertext'       => 'Noen (antagelig deg, fra IP-adressen $1) ba oss sende deg et nytt
passord til {{SITENAME}} ($4). Et midlertidig passord for «$2» har
blitt laget og er satt til «$3». Om det var det du ville, må du logge inn
og velge et nytt passord nå. Det midlertidige passordet vil utgå om {{PLURAL:$5|én dag|$5 dager}}.

Dersom denne forespørselen ble utført av noen andre, eller om du kom på passordet
og ikke lenger ønsker å endre det, kan du ignorere denne beskjeden
og fortsette å bruke det gamle passordet.',
'noemail'                    => 'Det er ikke registrert noen e-postadresse for brukeren «$1».',
'noemailcreate'              => 'De må oppgi en gyldig e-postadresse.',
'passwordsent'               => 'Et nytt passord har blitt sendt til e-postadressen registrert på bruker «$1». Logg inn når du har mottatt det nye passordet.',
'blocked-mailpassword'       => 'IP-adressen din er blokkert fra å redigere, og for å forhindre misbruk kan du heller ikke bruke funksjonen som gir deg nytt passord.',
'eauthentsent'               => 'En bekreftelsesmelding ble sendt til gitte e-postadresse. Før andre e-poster kan sendes til kontoen må du følge instruksjonene i e-posten for å bekrefte at kontoen faktisk er din.',
'throttled-mailpassword'     => 'En passordpåminnelse ble sendt for mindre enn {{PLURAL:$1|en time|$1 timer}} siden.
For å forhindre misbruk kan kun én passordpåminnelse sendes per {{PLURAL:$1|time|$1 timer}}.',
'mailerror'                  => 'Feil under sending av e-post: $1',
'acct_creation_throttle_hit' => 'Gjester med samme IP-adresse som deg har opprettet {{PLURAL:$1|én konto|$1 kontoer}} det siste døgnet, og det er ikke tillatt å opprette flere.
Som et resultat kan det ikke opprettes flere kontoer fra denne IP-adressen.',
'emailauthenticated'         => 'Din e-postadresse ble bekreftet $2 $3.',
'emailnotauthenticated'      => 'Din e-postadresse er ikke bekreftet. Du vil ikke kunne motta e-post for noen av følgende egenskaper.',
'noemailprefs'               => 'Oppgi en e-postadresse for at disse funksjonene skal fungere.',
'emailconfirmlink'           => 'Bekreft e-postadressen din.',
'invalidemailaddress'        => 'Din e-postadresse kan ikke aksepteres, fordi den er ugyldig formatert.
Skriv inn en fungerende e-postadresse eller tøm feltet.',
'accountcreated'             => 'Konto opprettet',
'accountcreatedtext'         => 'Brukerkonto for $1 har blitt opprettet.',
'createaccount-title'        => 'Kontooppretting på {{SITENAME}}',
'createaccount-text'         => 'Noen opprettet en konto for din e-postadresse på {{SITENAME}} ($4) med navnet «$2», med «$3» som passord. Du burde logge inn og endre passordet nå.

Du kan ignorere denne beskjeden dersom kontoen ble opprettet ved en feil.',
'usernamehasherror'          => 'Brukernavn kan ikke inneholde nummertegn.',
'login-throttled'            => 'Du har prøvd å logge inn med denne kontoen for mange ganger. Vent før du prøver igjen.',
'loginlanguagelabel'         => 'Språk: $1',
'suspicious-userlogout'      => 'Din forespørsel om å logge ut ble nektet fordi den så ut til å ha bli sendt av en ødelagt nettleser eller en mellomtjener.',

# E-mail sending
'php-mail-error-unknown' => 'Ukjent feil i PHPs mail()-funksjon',

# Password reset dialog
'resetpass'                 => 'Endre passord',
'resetpass_announce'        => 'Du logget inn med en midlertidig e-postkode. For å fullføre innloggingen må du oppgi et nytt passord her:',
'resetpass_text'            => '<!-- Legg til tekst her -->',
'resetpass_header'          => 'Endre passord',
'oldpassword'               => 'Gammelt passord:',
'newpassword'               => 'Nytt passord:',
'retypenew'                 => 'Gjenta nytt passord:',
'resetpass_submit'          => 'Angi passord og logg inn',
'resetpass_success'         => 'Passordet ditt ble endret! Logger inn&nbsp;…',
'resetpass_forbidden'       => 'Passord kan ikke endres',
'resetpass-no-info'         => 'Du må være logget inn for å gå til denne siden direkte',
'resetpass-submit-loggedin' => 'Endre passord',
'resetpass-submit-cancel'   => 'Avbryt',
'resetpass-wrong-oldpass'   => 'Feil midlertidig eller nåværende passord.
Du kan ha allerede byttet passordet, eller bedt om et nytt midlertidig passord.',
'resetpass-temp-password'   => 'Midlertidig passord:',

# Edit page toolbar
'bold_sample'     => 'Fet tekst',
'bold_tip'        => 'Fet tekst',
'italic_sample'   => 'Kursiv tekst',
'italic_tip'      => 'Kursiv tekst',
'link_sample'     => 'Lenketittel',
'link_tip'        => 'Intern lenke',
'extlink_sample'  => 'http://www.example.com lenketittel',
'extlink_tip'     => 'Ekstern lenke (husk prefikset http://)',
'headline_sample' => 'Overskriftstekst',
'headline_tip'    => 'Overskrift, nivå 2',
'math_sample'     => 'Sett inn formel her',
'math_tip'        => 'Matematisk formel (LaTeX)',
'nowiki_sample'   => 'Sett inn uformatert tekst her',
'nowiki_tip'      => 'Ignorer wikiformatering',
'image_sample'    => 'Eksempel.jpg',
'image_tip'       => 'Innebygd fil',
'media_sample'    => 'Eksempel.ogg',
'media_tip'       => 'Fillenke',
'sig_tip'         => 'Din signatur med dato',
'hr_tip'          => 'Horisontal linje (bruk sparsomt)',

# Edit pages
'summary'                          => 'Redigeringsforklaring:',
'subject'                          => 'Emne/overskrift:',
'minoredit'                        => 'Dette er en mindre endring',
'watchthis'                        => 'Overvåk denne siden',
'savearticle'                      => 'Lagre siden',
'preview'                          => 'Forhåndsvisning',
'showpreview'                      => 'Forhåndsvisning',
'showlivepreview'                  => 'Levende forhåndsvisning',
'showdiff'                         => 'Vis endringer',
'anoneditwarning'                  => "'''Advarsel:''' Du er ikke logget inn.
IP-adressen din blir bevart i sidens redigeringshistorikk.",
'anonpreviewwarning'               => "''Du er ikke logget inn. Lagring vil registrere din IP-adresse i sidens redigeringshistorikk.''",
'missingsummary'                   => "'''Påminnelse:''' Du har ikke lagt inn en redigeringsforklaring.
Velger du ''Lagre siden'' en gang til blir endringene lagret uten forklaring.",
'missingcommenttext'               => 'Vennligst legg inn en kommentar under.',
'missingcommentheader'             => "'''Påminnelse:''' Du har ikke angitt et emne/overskrift for denne kommentaren.
Om du trykker «{{int:savearticle}}» igjen vil redigeringen din bli lagret uten forklaring.",
'summary-preview'                  => 'Forhåndsvisning av redigeringsforklaring:',
'subject-preview'                  => 'Forhåndsvisning av emne/overskrift:',
'blockedtitle'                     => 'Brukeren er blokkert',
'blockedtext'                      => "'''Ditt brukernavn eller din IP-adresse har blitt blokkert.'''

Blokkeringen ble utført av $1. Grunnen som ble oppgitt var ''$2''.

* Blokkeringen begynte: $8
* Blokkeringen utgår: $6
* Blokkering ment på: $7

Du kan kontakte $1 eller en annen [[{{MediaWiki:Grouppage-sysop}}|administrator]] for å diskutere blokkeringen.
Du kan ikke bruke «E-post til denne brukeren»-funksjonen med mindre du har oppgitt en gyldig e-postadresse i [[Special:Preferences|innstillingene dine]] og du ikke er blokkert fra å sende e-post.
Din nåværende IP-adresse er $3, og blokkerings-ID-en er #$5.
Vennligst ta all denne informasjonen ved henvendelser.",
'autoblockedtext'                  => "Din IP-adresse har blitt automatisk blokkert fordi den ble brukt av en annen bruker som ble blokkert av $1.
Den oppgitte grunnen var:

:'''$2'''

* Blokkeringen begynte: $8
* Blokkeringen utgår: $6
* Blokkeringen er ment for: $7

Du kan kontakte $1 eller en av de andre [[{{MediaWiki:Grouppage-sysop}}|administratorene]] for å diskutere blokkeringen.

Merk at du ikke kan bruke «E-post til denne brukeren»-funksjonen med mindre du har registrert en gyldig e-postadresse i [[Special:Preferences|innstillingene dine]].

Din IP-adresse er $3, og blokkerings-ID-en er #$5.
Vennligst ta med all denne informasjonen ved henvendelser.",
'blockednoreason'                  => 'ingen grunn gitt',
'blockedoriginalsource'            => "Kildekoden til '''$1''' vises nedenfor:",
'blockededitsource'                => "Kildekoden '''dine endringer''' på '''$1''' vises nedenfor:",
'whitelistedittitle'               => 'Du må logge inn for å redigere',
'whitelistedittext'                => 'Du må $1 for å redigere artikler.',
'confirmedittext'                  => 'Du må bekrefte e-postadressen din før du kan redigere sider. Vennligst oppgi og bekreft e-postadressen din via [[Special:Preferences|innstillingene dine]].',
'nosuchsectiontitle'               => 'Finner ikke avsnittet',
'nosuchsectiontext'                => 'Du prøvde å redigere et avsnitt som ikke eksisterer.
Det kan ha blitt flyttet eller slettet mens du så på siden.',
'loginreqtitle'                    => 'Innlogging kreves',
'loginreqlink'                     => 'logge inn',
'loginreqpagetext'                 => 'Du må $1 for å se andre sider.',
'accmailtitle'                     => 'Passord sendt.',
'accmailtext'                      => 'Et tilfeldig passord for [[User talk:$1|$1]] har blitt sendt til $2.

Passordet for denne nye kontoen [[Special:ChangePassword|kan endres]] når du logger inn.',
'newarticle'                       => '(Ny)',
'newarticletext'                   => "Du har fulgt en lenke til en side som ikke finnes ennå.
For å opprette siden, begynn å skrive i boksen under (se [[{{MediaWiki:Helppage}}|hjelpesiden]] for mer informasjon).
Om du havnet her ved en feil, trykk '''tilbake''' i nettleseren.",
'anontalkpagetext'                 => "----
''Dette er en diskusjonsside for en uregistrert bruker som ikke har opprettet konto eller ikke er logget inn.
Vi er derfor nødt til å bruke den numeriske IP-adressen til å identifisere ham eller henne.
En IP-adresse kan være delt mellom flere brukere.
Hvis du er en uregistrert bruker og synes at du har fått irrelevante kommentarer på en slik side, [[Special:UserLogin/signup|opprett en konto]] eller [[Special:UserLogin|logg inn]] så vi unngår fremtidige forvekslinger med andre uregistrerte brukere.''",
'noarticletext'                    => 'Det er for tiden ingen tekst på denne siden.
Du kan [[Special:Search/{{PAGENAME}}|søke etter denne sidetittelen]] på andre sider,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} søke i relaterte logger],
eller [{{fullurl:{{FULLPAGENAME}}|action=edit}} opprette siden]</span>.',
'noarticletext-nopermission'       => 'Det er ingen tekst på denne siden.
Du kan [[Special:Search/{{PAGENAME}}|søke etter sidens tittel]] i andre sider, eller <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} søke i relevante logger]</span>.',
'userpage-userdoesnotexist'        => 'Brukerkontoen «$1» er ikke registrert. Sjekk om du ønsker å opprette/redigere denne siden.',
'userpage-userdoesnotexist-view'   => 'Kontoen «$1» er ikke registrert.',
'blocked-notice-logextract'        => 'Denne brukeren er for tiden blokkert.
Siste blokkeringsloggelement kan sees nedenfor.',
'clearyourcache'                   => "'''Merk:''' Etter lagring vil det kanskje være nødvendig at nettleseren sletter mellomlageret sitt for at endringene skal tre i kraft. '''Mozilla og Firefox:''' trykk ''Ctrl-Shift-R'', '''Internet Explorer:''' ''Ctrl-F5'', '''Safari:''' ''Cmd-Shift-R'' i engelskspråklig versjon, ''Cmd-Alt-E'' i norskspråklig versjon, '''Konqueror og Opera:''' ''F5''.",
'usercssyoucanpreview'             => "'''Tips:''' Bruk '{{int:showpreview}}'-knappen for å teste din nye CSS før du lagrer.",
'userjsyoucanpreview'              => "'''Tips:''' Bruk '{{int:showpreview}}'-knappen for å teste ditt nye JS før du lagrer.",
'usercsspreview'                   => "'''Husk at dette bare er en forhåndsvisning av din bruker-CSS og at den ikke er lagret!'''",
'userjspreview'                    => "'''Husk at dette bare er en test eller forhåndsvisning av ditt bruker-JavaScript, og det ikke er lagret!'''",
'sitecsspreview'                   => "'''Husk at du bare forhåndsviser denne CSS.'''
'''Den har ikke blitt lagret ennå!'''",
'sitejspreview'                    => "'''Husk at du bare forhåndsviser denne JavaScript-koden.'''
'''Den har ikke blitt lagret ennå!'''",
'userinvalidcssjstitle'            => "'''Advarsel:''' Det finnes ikke noe utseende ved navn «$1». Husk at .css- og .js-sider bruker titler i små bokstaver, for eksempel {{ns:user}}:Eksempel/vector.css, ikke {{ns:user}}:Eksempel/Vector.css",
'updated'                          => '(Oppdatert)',
'note'                             => "'''Merk:'''",
'previewnote'                      => "'''Husk at dette bare er en forhåndsvisning.'''
Endringene dine har ikke blitt lagret ennå!",
'previewconflict'                  => 'Slik vil teksten i redigeringsvinduet se ut dersom du lagrer den.',
'session_fail_preview'             => "'''Beklager! Klarte ikke å lagre redigeringen din. Prøv igjen. Om det fortsetter å gå galt, prøv å [[Special:UserLogout|logge ut]] og så inn igjen.'''",
'session_fail_preview_html'        => "'''Beklager! Klarte ikke å lagre redigeringen din på grunn av tap av øktdata.'''

''Fordi {{SITENAME}} har rå HTML slått på, er forhåndsvisningen skjult for å forhindre JavaScript-angrep.''

'''Om dette er et legitimt redigeringsforsøk, prøv igjen. Om det da ikke fungerer, prøv å [[Special:UserLogout|logge ut]] og logge inn igjen.'''",
'token_suffix_mismatch'            => "'''Redigeringen din har blitt avvist fordi klienten din ikke hadde punktasjonstegn i redigeringsteksten. Redigeringen har blitt avvist for å hindre ødeleggelse av artikkelteksten. Dette forekommer av og til når man bruker vevbaserte anonyme proxytjenester.'''",
'editing'                          => 'Redigerer $1',
'editingsection'                   => 'Redigerer $1 (avsnitt)',
'editingcomment'                   => 'Redigerer $1 (ny seksjon)',
'editconflict'                     => 'Redigeringskonflikt: $1',
'explainconflict'                  => "Noen andre har endret teksten siden du begynte å redigere.
Den øverste boksen inneholder den nåværende tekst.
Dine endringer vises i den nederste boksen.
Du er nødt til å flette dine endringer sammen med den nåværende teksten.
'''Kun''' teksten i den øverste tekstboksen blir lagret når du trykker «{{int:savearticle}}».",
'yourtext'                         => 'Din tekst',
'storedversion'                    => 'Den lagrede versjonen',
'nonunicodebrowser'                => "'''ADVARSEL: Nettleseren din har ikke støtte for Unicode. Skru det på før du begynner å redigere artikler.'''",
'editingold'                       => "'''ADVARSEL:
Du redigerer en gammel versjon av denne siden.
Hvis du lagrer den, vil alle endringer foretatt siden denne versjonen bli overskrevet.'''",
'yourdiff'                         => 'Forskjeller',
'copyrightwarning'                 => "Merk at alle bidrag til {{SITENAME}} anses som utgitt under $2 (se $1 for detaljer).
Om du ikke vil at dine bidrag skal kunne redigeres og distribuert fritt etter andres forgodtbefinnende, ikke legg det til her.<br />
Du lover også at du har skrevet dette selv, eller kopiert det fra en ressurs som samsvarer med vilkårene eller ikke er vernet av opphavsrett.
'''Ikke legg til opphavsbeskyttet materiale uten tillatelse!'''",
'copyrightwarning2'                => "Vennligst merk at alle bidrag til {{SITENAME}} kan bli redigert, endret eller fjernet av andre bidragsytere. Om du ikke vil at dine bidrag skal kunne redigeres fritt, ikke legg det til her.<br />
Du lover også at du har skrevet dette selv, eller kopiert det fra en ressurs som er i public domain eller lignende (se $1 for detaljer). '''IKKE LEGG TIL OPPHAVSBESKYTTET MATERIALE UTEN TILLATELSE!'''",
'longpageerror'                    => "'''FEIL: Teksten du prøvde å lagre er $1&nbsp;kB lang, dvs. lenger enn det maksimale $2&nbsp;kB. Den kan ikke lagres.'''",
'readonlywarning'                  => "'''ADVARSEL: Databasen er låst på grunn av vedlikehold,
så du kan ikke lagre dine endringer akkurat nå. Det kan være en god idé å
kopiere teksten din til en tekstfil, så du kan lagre den til senere.'''

Systemadministratoren som låste databasen oppga følgende årsak: $1",
'protectedpagewarning'             => "'''Advarsel: Denne siden har blitt låst slik at kun brukere med administratorrettigheter kan redigere den.'''
Det siste loggelementet er oppgitt under som referanse:",
'semiprotectedpagewarning'         => "'''Merk:''' Denne siden har blitt låst slik at kun registrerte brukere kan endre den.
Det siste loggelementet er oppgitt under som referanse:",
'cascadeprotectedwarning'          => "'''Advarsel:''' Denne siden har blitt låst slik at kun brukere med administratorrettigheter kan redigere den, fordi den inkluderes på følgende dypbeskyttede sider:<!--{{PLURAL:$1}}-->",
'titleprotectedwarning'            => "'''Advarsel: Denne siden har blitt låst slik at [[Special:ListGroupRights|spesielle rettigheter]] kreves for å opprette den.'''
Det siste loggelementet er oppgitt under som referanse:",
'templatesused'                    => '{{PLURAL:$1|Mal|Maler}} som brukes på denne siden:',
'templatesusedpreview'             => '{{PLURAL:$1|Mal|Maler}} brukt i denne forhåndsvisningen:',
'templatesusedsection'             => '{{PLURAL:$1|Mal|Maler}} brukt i denne seksjonen:',
'template-protected'               => '(beskyttet)',
'template-semiprotected'           => '(halvbeskyttet)',
'hiddencategories'                 => 'Denne siden er medlem av {{PLURAL:$1|1 skjult kategori|$1 skjulte kategorier}}:',
'edittools'                        => '<!-- Teksten her vil vises under redigerings- og opplastingsboksene. -->',
'nocreatetitle'                    => 'Sideoppretting er begrenset',
'nocreatetext'                     => '{{SITENAME}} har begrensede muligheter for oppretting av nye sider. Du kan gå tilbake og redigere en eksisterende side, eller [[Special:UserLogin|logge inn eller opprette en ny konto]].',
'nocreate-loggedin'                => 'Du har ikke tillatelse til å opprette sider.',
'sectioneditnotsupported-title'    => 'Seksjonsredigering støttes ikke',
'sectioneditnotsupported-text'     => 'Seksjonsredigering støttes ikke på denne siden.',
'permissionserrors'                => 'Tilgangsfeil',
'permissionserrorstext'            => 'Du har ikke tillatelse til å utføre dette, av følgende {{PLURAL:$1|grunn|grunner}}:',
'permissionserrorstext-withaction' => 'Du har ikke tillatelse til å $2 {{PLURAL:$1|på grunn av|av følgende grunner}}:',
'recreate-moveddeleted-warn'       => "Advarsel: Du gjenskaper en side som tidligere har blitt slettet.'''

Du burde vurdere hvorvidt det er passende å fortsette å redigere denne siden.
Slette- og flytteloggen for denne siden gjengis her:",
'moveddeleted-notice'              => 'Denne siden har blitt slettet.
Slette- og flytteloggen vises nedenfor.',
'log-fulllog'                      => 'Vis fullstendig logg',
'edit-hook-aborted'                => 'Redigering avbrutt av en funksjon, uten forklaring.',
'edit-gone-missing'                => 'Kunne ikke oppdatere siden fordi den har blitt slettet.',
'edit-conflict'                    => 'Redigeringskonflikt.',
'edit-no-change'                   => 'Redigeringen din ble ignorert fordi det ikke var noen endringer.',
'edit-already-exists'              => 'Kunne ikke opprette ny side fordi den finnes fra før.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Advarsel: Denne siden inneholder for mange prosesskrevende parserfunksjoner.

Det burde være mindre enn {{PLURAL:$2|$2|$2}}, men er nå {{PLURAL:$1|$1|$1}}..',
'expensive-parserfunction-category'       => 'Sider med for mange prosesskrevende parserfunksjoner',
'post-expand-template-inclusion-warning'  => 'Advarsel: Størrelsen på inkluderte maler er for stor.
Noen maler vil ikke bli inkludert.',
'post-expand-template-inclusion-category' => 'Sider som inneholder for store maler',
'post-expand-template-argument-warning'   => 'Advarsel: Siden inneholder ett eller flere malparametere som blir for lange når de utvides.
Disse parameterne har blitt utelatt.',
'post-expand-template-argument-category'  => 'Sider med utelatte malparametere',
'parser-template-loop-warning'            => 'Mal-loop oppdaget: [[$1]]',
'parser-template-recursion-depth-warning' => 'Mal er brukt for mange ganger ($1)',
'language-converter-depth-warning'        => 'Dybdegrense for språkkonvertering overskredet ($1)',

# "Undo" feature
'undo-success' => 'Redigeringen kan omgjøres. Sjekk sammenligningen under for å bekrefte at du vil gjøre dette, og lagre endringene for å fullføre omgjøringen.',
'undo-failure' => 'Redigeringen kunne ikke omgjøres på grunn av konflikterende etterfølgende redigeringer.',
'undo-norev'   => 'Redigeringen kunne ikke fjernes fordi den ikke eksisterer eller ble slettet',
'undo-summary' => 'Fjerner revisjon $1 av [[Special:Contributions/$2]] ([[User talk:$2|diskusjon]] | [[Special:Contributions/$2|{{int:contribslink}}]])',

# Account creation failure
'cantcreateaccounttitle' => 'Kan ikke opprette konto',
'cantcreateaccount-text' => "Kontooppretting fra denne IP-adressen ('''$1''') har blitt blokkert av [[User:$3|$3]].

Grunnen som ble oppgitt av $3 er ''$2''",

# History pages
'viewpagelogs'           => 'Vis logger for denne siden',
'nohistory'              => 'Denne siden har ingen historikk.',
'currentrev'             => 'Nåværende versjon',
'currentrev-asof'        => 'Nåværende revisjon fra $1',
'revisionasof'           => 'Revisjonen fra $1',
'revision-info'          => 'Revisjon per $1 av $2',
'previousrevision'       => '← Eldre revisjon',
'nextrevision'           => 'Nyere revisjon →',
'currentrevisionlink'    => 'Nåværende revisjon',
'cur'                    => 'nå',
'next'                   => 'neste',
'last'                   => 'forrige',
'page_first'             => 'første',
'page_last'              => 'siste',
'histlegend'             => "Valg av diff: merk i radioboksene de revisjonene du ønsker å sammenligne og trykk linjeskift eller knappen nederst på siden.<br />
Forklaring: '''({{int:cur}})''' = forskjell fra nåværende revisjon, '''({{int:last}})''' = forskjell fra foregående revisjon, '''{{int:minoreditletter}}''' = mindre endring.",
'history-fieldset-title' => 'Bla i historikken',
'history-show-deleted'   => 'Kun slettede',
'histfirst'              => 'Første',
'histlast'               => 'Siste',
'historysize'            => '({{PLURAL:$1|1 byte|$1 byte}})',
'historyempty'           => '(tom)',

# Revision feed
'history-feed-title'          => 'Revisjonshistorikk',
'history-feed-description'    => 'Revisjonshistorikk for denne siden',
'history-feed-item-nocomment' => '$1 på $2',
'history-feed-empty'          => 'Den etterspurte siden finnes ikke. Den kan ha blitt slettet fra wikien, eller fått et nytt navn. Prøv å [[Special:Search|søke]] etter beslektede sider.',

# Revision deletion
'rev-deleted-comment'         => '(redigeringskommentar fjernet)',
'rev-deleted-user'            => '(brukernavn fjernet)',
'rev-deleted-event'           => '(fjernet loggoppføring)',
'rev-deleted-user-contribs'   => '[brukernavn eller IP-adresse fjernet – redigeringen vises ikke blant bidragene]',
'rev-deleted-text-permission' => "Denne revisjonen har blitt '''slettet'''.
Det kan være detaljer i [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} slettingsloggen].",
'rev-deleted-text-unhide'     => "Denne sideversjonen har blitt '''slettet'''.
Det kan være detaljer i [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} slettingsloggen].
Som administrator kan du fortsatt [$1 se revisjonen] om du ønsker å fortsette.",
'rev-suppressed-text-unhide'  => "Denne revisjonen har blitt '''unndratt'''.
Det kan være mer informasjon i [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} unndragelseslogg].
Som administrator kan du fortsatt [$1 se revisjonen] om du ønsker å fortsette.",
'rev-deleted-text-view'       => "Denne sideversjonen har blitt '''slettet'''.
Som administrator kan du se den. Det kan finnes flere detaljer i [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} slettingsloggen].",
'rev-suppressed-text-view'    => "Denne revisjonen har blitt '''unndratt'''.
Som administrator kan du se den; det kan være mer informasjon i [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} unndragelsesloggen].",
'rev-deleted-no-diff'         => "Du kan ikke vise forskjellen fordi en av versjonene har blitt '''slettet'''.
Det kan finnes flere detaljer i [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} slettingsloggen].",
'rev-suppressed-no-diff'      => "Du kanne ikke se revisjonsforskjellen fordi en av revisjonene har blitt '''slettet'''.",
'rev-deleted-unhide-diff'     => "Én av versjonene i denne forskjellen har blitt '''slettet'''.
Det kan finnes flere detaljer i [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} slettingsloggen].
Som administrator kan du fortsatt [$1 se forskjellen] om du ønsker å fortsette.",
'rev-suppressed-unhide-diff'  => "En av sideversjonene i denne diffen har blitt '''skjul'''.
Det kan være detaljer i [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} skjulingsloggen].
Som administrator kan du fortsatt [$1 se diffen] om du ønsker det.",
'rev-deleted-diff-view'       => "En av revisjonene i denne diffen har blitt '''slettet'''.
Som administrator kan du se diffen; det kan være detaljer i [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} sletteloggen].",
'rev-suppressed-diff-view'    => "En av revisjonene i denne diffen har blitt '''skjult'''.
Som administrator kan du se denne diffen; det kan være detaljer i [{{fullurl:{{#Special:Log}}/suppcess|page={{FULLPAGENAMEE}}}} skjulingsloggen].",
'rev-delundel'                => 'vis/skjul',
'rev-showdeleted'             => 'vis',
'revisiondelete'              => 'Slett/gjenopprett revisjoner',
'revdelete-nooldid-title'     => 'Ugyldig målversjon',
'revdelete-nooldid-text'      => 'Du har ikke angitt en målversjon for denne funksjonen, den angitte versjonen finnes ikke, eller du forsøker å skjule den nåværende versjonen.',
'revdelete-nologtype-title'   => 'Ingen loggtype spesifisert',
'revdelete-nologtype-text'    => 'Du har ikke spesifisert en loggtype å utføre denne handlingen på.',
'revdelete-nologid-title'     => 'Ugyldig loggelement',
'revdelete-nologid-text'      => 'Du har enten ikke spesifisert ett loggelement å utføre på denne funksjonen, eller spesifisert element finnes ikke.',
'revdelete-no-file'           => 'Den spesifiserte filen finnes ikke.',
'revdelete-show-file-confirm' => 'Er du sikker på at du ønsker å vise en slettet versjon av filen «<nowiki>$1</nowiki>» fra den $2 klokken $3?',
'revdelete-show-file-submit'  => 'Ja',
'revdelete-selected'          => "'''{{PLURAL:$2|Valgt revisjon|Valgte revisjoner}} av [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Valgt loggoppføring|Valgte loggoppføringer}}:'''",
'revdelete-text'              => "'''Slettede versjoner og oppføringer vil fortsatt vises i sidehistorikken og loggene, men deler av innholdet vil ikke lenger bli offentliggjort.'''
Andre administratorer på {{SITENAME}} vil fortsatt kunne se det skjulte innholdet, og kan gjenopprette det, med mindre videre begrensninger blir gitt av sideoperatørene.",
'revdelete-confirm'           => 'Bekreft at du ønsker å gjøre dette, at du forstår konsekvensene, og at du gjør det i samsvar med [[{{MediaWiki:Policy-url}}|retningslinjene]].',
'revdelete-suppress-text'     => "Utelatelse bør '''kun''' brukes i følgende tilfeller:
* Upassende personlige opplysninger
*: ''hjemmeadresser og -telefonnummer, personnummer og lignende''",
'revdelete-legend'            => 'Fastsett synlighetsbegrensninger',
'revdelete-hide-text'         => 'Skjul revisjonstekst',
'revdelete-hide-image'        => 'Skjul filinnhold',
'revdelete-hide-name'         => 'Skjul handling og mål',
'revdelete-hide-comment'      => 'Skjul redigeringsforklaring',
'revdelete-hide-user'         => 'Skjul bidragsyters brukernavn eller IP',
'revdelete-hide-restricted'   => 'La disse begrensningene gjelde for administratorer også, og steng dette grensesnittet',
'revdelete-radio-same'        => '(ikke endre)',
'revdelete-radio-set'         => 'Ja',
'revdelete-radio-unset'       => 'Nei',
'revdelete-suppress'          => 'Fjern informasjon også fra administratorer',
'revdelete-unsuppress'        => 'Fjern betingelser på gjenopprettede revisjoner',
'revdelete-log'               => 'Årsak:',
'revdelete-submit'            => 'Utfør på {{PLURAL:$1|valgt revisjon|valgte revisjoner}}',
'revdelete-logentry'          => 'endre revisjonssynlighet for [[$1]]',
'logdelete-logentry'          => 'endre hendelsessynlighet for [[$1]]',
'revdelete-success'           => "'''Versjonssynlighet vellykket oppdatert.'''",
'revdelete-failure'           => "'''Kunne ikke endre versjonssynligheten:'''
$1",
'logdelete-success'           => "'''Hendelsessynlighet satt.'''",
'logdelete-failure'           => "'''Loggens synlighet kunne ikke bli stilt inn:'''
$1",
'revdel-restore'              => 'endre synlighet',
'revdel-restore-deleted'      => 'slettede revisjoner',
'revdel-restore-visible'      => 'synlige revisjoner',
'pagehist'                    => 'Sidehistorikk',
'deletedhist'                 => 'Slettet historikk',
'revdelete-content'           => 'innhold',
'revdelete-summary'           => 'redigeringssammendrag',
'revdelete-uname'             => 'brukernavn',
'revdelete-restricted'        => 'begrensninger gjelder også administratorer',
'revdelete-unrestricted'      => 'fjernet begrensninger for administratorer',
'revdelete-hid'               => 'skjulte $1',
'revdelete-unhid'             => 'synliggjorde $1',
'revdelete-log-message'       => '$1 for $2 {{PLURAL:$2|revisjon|revisjoner}}',
'logdelete-log-message'       => '$1 for $2 {{PLURAL:$2|element|elementer}}',
'revdelete-hide-current'      => 'Feil under skjuling av objektet datert $2, $1: dette er den gjeldende revisjonen.
Den kan ikke skjules.',
'revdelete-show-no-access'    => 'Feil under visning av objekt datert $2, $1: dette objektet har blitt markert "begrenset".
Du har ikke tilgang til det.',
'revdelete-modify-no-access'  => 'Feil under endring av objekt datert $2, $1: dette objektet har blitt markert "begrenset".
Du har ikke tilgang til det.',
'revdelete-modify-missing'    => 'Feil under endring av objekt ID $1: det mangler i databasen!',
'revdelete-no-change'         => "'''Advarsel:''' objektet datert $2, $1 hadde allerede etterspurt synlighetsinnstillinger.",
'revdelete-concurrent-change' => 'Feil under endring av objektet datert $2, $1: dets status ser ut til å ha blitt endret av noen andre mens du prøvde å endre det.
Vennligst sjekk loggen.',
'revdelete-only-restricted'   => 'Feil ved gjemming av objekt datert $2, $1: du kan ikke skjule objekt fra å vises for administratorer uten også å velge en av de andre visningsalternativene.',
'revdelete-reason-dropdown'   => '*Vanlige slettingsgrunner
** Opphavsrettsbrudd
** Upassende personopplysninger
** Mulig falskt sladder',
'revdelete-otherreason'       => 'Annen/ytterligere årsak:',
'revdelete-reasonotherlist'   => 'Annen årsak',
'revdelete-edit-reasonlist'   => 'Rediger begrunnelser for sletting',
'revdelete-offender'          => 'Forfatter av denne versjonen:',

# Suppression log
'suppressionlog'     => 'Sidefjerningslogg',
'suppressionlogtext' => 'Nedenfor er en liste over sider og blokkeringer med innhold skjult fra administratorer.
Se [[Special:IPBlockList|blokkeringslisten]] for oversikten over nåværende blokkeringer.',

# History merging
'mergehistory'                     => 'Flett sidehistorikker',
'mergehistory-header'              => 'Denne siden lar deg flette historikken til to sider.
Forsikre deg om at denne endringen vil opprettholde historisk sidekontinuitet.',
'mergehistory-box'                 => 'Flett historikken til to sider:',
'mergehistory-from'                => 'Kildeside:',
'mergehistory-into'                => 'Målside:',
'mergehistory-list'                => 'Flettbar redigeringshistorikk',
'mergehistory-merge'               => 'Følgende revisjoner av [[:$1]] kan flettes til [[:$2]]. Du kan velge å flette kun de revisjonene som kom før tidspunktet gitt i tabellen. Merk at bruk av navigasjonslenkene vil resette denne kolonnen.',
'mergehistory-go'                  => 'Vis flettbare redigeringer',
'mergehistory-submit'              => 'Flett revisjoner',
'mergehistory-empty'               => 'Ingen revisjoner kan flettes.',
'mergehistory-success'             => '{{PLURAL:$3|Én revisjon|$3 revisjoner}} av [[:$1]] ble flettet til [[:$2]].',
'mergehistory-fail'                => 'Klarte ikke å utføre historikkfletting; sjekk siden og tidsparameterne igjen.',
'mergehistory-no-source'           => 'Kildesiden $1 finnes ikke.',
'mergehistory-no-destination'      => 'Målsiden $1 finnes ikke.',
'mergehistory-invalid-source'      => 'Kildesiden må ha en gyldig tittel.',
'mergehistory-invalid-destination' => 'Målsiden må ha en gyldig tittel.',
'mergehistory-autocomment'         => 'Flettet [[:$1]] inn i [[:$2]]',
'mergehistory-comment'             => 'Flettet [[:$1]] inn i [[:$2]]: $3',
'mergehistory-same-destination'    => 'Kilde- og målside kan ikke være den samme.',
'mergehistory-reason'              => 'Årsak:',

# Merge log
'mergelog'           => 'Flettingslogg',
'pagemerge-logentry' => 'flettet [[$1]] til [[$2]] (revisjoner fram til $3)',
'revertmerge'        => 'Omgjør fletting',
'mergelogpagetext'   => 'Nedenfor er en liste over de nyligste flettingene av sidehistorikker.',

# Diffs
'history-title'            => 'Revisjonshistorikk for «$1»',
'difference'               => '(Forskjell mellom revisjoner)',
'difference-multipage'     => '(Forskjell mellom sider)',
'lineno'                   => 'Linje $1:',
'compareselectedversions'  => 'Sammenlign valgte revisjoner',
'showhideselectedversions' => 'Vis/skjul valgte versjoner',
'editundo'                 => 'angre',
'diff-multi'               => '({{PLURAL:$1|Én mellomrevisjon|$1 mellomrevisjoner}} av {{PLURAL:$2|én bruker|$2 brukere}} vises ikke)',
'diff-multi-manyusers'     => '({{PLURAL:$1|Én mellomrevisjon|$1 mellomrevisjoner}} av mer enn $2 {{PLURAL:$2|bruker|brukere}} vises ikke)',

# Search results
'searchresults'                    => 'Søkeresultat',
'searchresults-title'              => 'Søkeresultat for «$1»',
'searchresulttext'                 => 'For mer informasjon om søking i {{SITENAME}}, se [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => "Du søkte etter '''[[:$1]]''' ([[Special:Prefixindex/$1|alle sider som begynner med «$1»]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|alle sider som lenker til «$1»]])",
'searchsubtitleinvalid'            => "Du søkte etter '''$1'''",
'toomanymatches'                   => 'For mange mulige svar, prøv med en annen spørring',
'titlematches'                     => 'Artikkeltitler med treff på forespørselen',
'notitlematches'                   => 'Ingen sidetitler samsvarte med søket',
'textmatches'                      => 'Artikkeltekster med treff på forespørselen',
'notextmatches'                    => 'Inden sidetekst samsvarte med søket',
'prevn'                            => 'forrige {{PLURAL:$1|$1}}',
'nextn'                            => 'neste {{PLURAL:$1|$1}}',
'prevn-title'                      => 'Forrige $1 {{PLURAL:$1|resultat|resultater}}',
'nextn-title'                      => 'Neste $1 {{PLURAL:$1|resultat|resultater}}',
'shown-title'                      => 'Vis $1 {{PLURAL:$1|resultat|resultater}} per side',
'viewprevnext'                     => 'Vis ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Søkeinnstillinger',
'searchmenu-exists'                => "* Siden '''[[$1]]'''",
'searchmenu-new'                   => "'''Opprett siden ''[[:$1]]'' på denne wikien.'''",
'searchhelp-url'                   => 'Help:Hjelp',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Vis alle sider som begynner med dette]]',
'searchprofile-articles'           => 'Innholdssider',
'searchprofile-project'            => 'Hjelp- og prosjektsider',
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Alt',
'searchprofile-advanced'           => 'Avansert',
'searchprofile-articles-tooltip'   => 'Søk i $1',
'searchprofile-project-tooltip'    => 'Søk i $1',
'searchprofile-images-tooltip'     => 'Søk etter filer',
'searchprofile-everything-tooltip' => 'Søk i alt innhold (inkldert diskusjonssider)',
'searchprofile-advanced-tooltip'   => 'Søk i visse navnerom',
'search-result-size'               => '$1 ({{PLURAL:$2|ett|$2}} ord)',
'search-result-category-size'      => '{{PLURAL:$1|1 medlem|$1 medlemmer}} ({{PLURAL:$2|1 underkategori|$2 underkategorier}}, {{PLURAL:$3|1 fil|$3 filer}})',
'search-result-score'              => 'Relevans: $1&nbsp;%',
'search-redirect'                  => '(omdirigering $1)',
'search-section'                   => '(avsnitt $1)',
'search-suggest'                   => 'Mente du: $1',
'search-interwiki-caption'         => 'Søsterprosjekt',
'search-interwiki-default'         => '$1-resultat:',
'search-interwiki-more'            => '(mer)',
'search-mwsuggest-enabled'         => 'med forslag',
'search-mwsuggest-disabled'        => 'ingen forslag',
'search-relatedarticle'            => 'Relatert',
'mwsuggest-disable'                => 'Slå av AJAX-forslag',
'searcheverything-enable'          => 'Søk i alle navnerom',
'searchrelated'                    => 'relatert',
'searchall'                        => 'alle',
'showingresults'                   => "Nedenfor vises opptil {{PLURAL:$1|'''ett''' resultat|'''$1''' resultater}} fra og med nummer <b>$2</b>.",
'showingresultsnum'                => "Nedenfor vises {{PLURAL:$3|'''ett''' resultat|'''$3''' resultater}} fra og med nummer '''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Resultat '''$1''' av '''$3'''|Resultat '''$1 - $2''' av '''$3'''}} for '''$4'''",
'nonefound'                        => "'''Merk''': Som standard søkes det kun i enkelte navnerom.
For å søke i alle, bruk prefikset ''all:'' (inkluderer diskusjonssider, maler, osv), eller bruk det ønskede navnerommet som prefiks.",
'search-nonefound'                 => 'Det var ingen resultater som passet til søket.',
'powersearch'                      => 'Avansert søk',
'powersearch-legend'               => 'Avansert søk',
'powersearch-ns'                   => 'Søk i navnerom:',
'powersearch-redir'                => 'Vis omdirigeringer',
'powersearch-field'                => 'Søk etter',
'powersearch-togglelabel'          => 'Merk:',
'powersearch-toggleall'            => 'Alle',
'powersearch-togglenone'           => 'Ingen',
'search-external'                  => 'Eksternt søk',
'searchdisabled'                   => 'Søkefunksjonen er slått av. Du kan søke via Google i mellomtiden. Merk at Googles indeksering av {{SITENAME}} muligens er utdatert.',

# Quickbar
'qbsettings'               => 'Brukerinnstillinger for hurtigmeny.',
'qbsettings-none'          => 'Ingen',
'qbsettings-fixedleft'     => 'Fast venstre',
'qbsettings-fixedright'    => 'Fast høyre',
'qbsettings-floatingleft'  => 'Flytende venstre',
'qbsettings-floatingright' => 'Flytende til høyre',

# Preferences page
'preferences'                   => 'Innstillinger',
'mypreferences'                 => 'Innstillinger',
'prefs-edits'                   => 'Antall redigeringer:',
'prefsnologin'                  => 'Ikke logget inn',
'prefsnologintext'              => 'Du må være <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} logget inn]</span> for å endre brukerinnstillingene.',
'changepassword'                => 'Endre passord',
'prefs-skin'                    => 'Utseende',
'skin-preview'                  => 'Forhåndsvisning',
'prefs-math'                    => 'Matteformler',
'datedefault'                   => 'Ingen foretrukket',
'prefs-datetime'                => 'Dato og tid',
'prefs-personal'                => 'Brukerdata',
'prefs-rc'                      => 'Siste endringer',
'prefs-watchlist'               => 'Overvåkningsliste',
'prefs-watchlist-days'          => 'Dager som skal vises i overvåkningslisten:',
'prefs-watchlist-days-max'      => 'Maksimum 7 dager',
'prefs-watchlist-edits'         => 'Antall redigeringer som skal vises i utvidet overvåkningsliste:',
'prefs-watchlist-edits-max'     => 'Maksimum antall: 1000',
'prefs-watchlist-token'         => 'Nøkkel for overvåkningsliste',
'prefs-misc'                    => 'Diverse',
'prefs-resetpass'               => 'Endre passord',
'prefs-email'                   => 'Alternativer for e-post',
'prefs-rendering'               => 'Utseende',
'saveprefs'                     => 'Lagre',
'resetprefs'                    => 'Tilbakestill ulagrede endringer',
'restoreprefs'                  => 'Tilbakestill til standardinnstillinger overalt',
'prefs-editing'                 => 'Redigering',
'prefs-edit-boxsize'            => 'Størrelse på redigeringsvinduet.',
'rows'                          => 'Rader:',
'columns'                       => 'Kolonner',
'searchresultshead'             => 'Søk',
'resultsperpage'                => 'Resultater per side:',
'contextlines'                  => 'Linjer per resultat',
'contextchars'                  => 'Tegn per linje i resultatet',
'stub-threshold'                => 'Grense for <span class="mw-stub-example">stubblenkeformatering</span>:',
'stub-threshold-disabled'       => 'Deaktivert',
'recentchangesdays'             => 'Antall dager som skal vises i siste endringer:',
'recentchangesdays-max'         => '(maksimum $1 {{PLURAL:$1|dag|dager}})',
'recentchangescount'            => 'Antall redigeringer som skal vises som standard:',
'prefs-help-recentchangescount' => 'Dette inkluderer nylige endringer, sidehistorikk og logger.',
'prefs-help-watchlist-token'    => 'Om du fyller ut dette feltet med et hemmelig tall vil lage en RSS-liste for overvåkingslista di.
Alle som vet det rette tallet vil være i stand til å lese overvåkingslista di, så velg en sikker verdi.
Her er et tilfeldig tall du kan bruke: $1',
'savedprefs'                    => 'Innstillingene ble lagret.',
'timezonelegend'                => 'Tidssone:',
'localtime'                     => 'Lokaltid:',
'timezoneuseserverdefault'      => 'Bruk serverens standardinnstillinger',
'timezoneuseoffset'             => 'Annet (spesifiser forskjell)',
'timezoneoffset'                => 'Forskjell¹:',
'servertime'                    => 'Serverens tid er nå:',
'guesstimezone'                 => 'Hent tidssone fra nettleseren',
'timezoneregion-africa'         => 'Afrika',
'timezoneregion-america'        => 'Amerika',
'timezoneregion-antarctica'     => 'Antarktis',
'timezoneregion-arctic'         => 'Arktis',
'timezoneregion-asia'           => 'Asia',
'timezoneregion-atlantic'       => 'Atlanterhavet',
'timezoneregion-australia'      => 'Australia',
'timezoneregion-europe'         => 'Europe',
'timezoneregion-indian'         => 'Det indiske hav',
'timezoneregion-pacific'        => 'Stillehavet',
'allowemail'                    => 'Tillat andre å sende meg e-post',
'prefs-searchoptions'           => 'Søkealternativ',
'prefs-namespaces'              => 'Navnerom',
'defaultns'                     => 'Søk ellers i disse navnerommene:',
'default'                       => 'standard',
'prefs-files'                   => 'Filer',
'prefs-custom-css'              => 'Personlig CSS',
'prefs-custom-js'               => 'Personlig Javascript',
'prefs-common-css-js'           => 'Delt CSS/JS for alle drakter:',
'prefs-reset-intro'             => 'Du kan bruke denne siden til å tilbakestille innstillingene dine til standardinnstillingene.
Dette kan ikke tilbakestilles.',
'prefs-emailconfirm-label'      => 'E-postbekreftelse:',
'prefs-textboxsize'             => 'Størrelse på redigeringsvindu',
'youremail'                     => 'E-post:',
'username'                      => 'Brukernavn:',
'uid'                           => 'Bruker-ID:',
'prefs-memberingroups'          => 'Medlem i følgende {{PLURAL:$1|gruppe|grupper}}:',
'prefs-registration'            => 'Registreringstid:',
'yourrealname'                  => 'Virkelig navn:',
'yourlanguage'                  => 'Språk:',
'yournick'                      => 'Signatur:',
'prefs-help-signature'          => 'Kommentarer på diskusjonssider bør alltid signeres med «<nowiki>~~~~</nowiki>», som vil bli konvertert til din signatur med tidspunkt.',
'badsig'                        => 'Ugyldig råsignatur; sjekk HTML-elementer.',
'badsiglength'                  => 'Signaturen er for lang.
Den kan maks inneholde $1 {{PLURAL:$1|tegn|tegn}}.',
'yourgender'                    => 'Kjønn:',
'gender-unknown'                => 'Uspesifisert',
'gender-male'                   => 'Mann',
'gender-female'                 => 'Kvinne',
'prefs-help-gender'             => 'Valgfritt: brukes for kjønnskorrekt tekst av programvaren. Denne informasjonen vil være offentlig.',
'email'                         => 'E-post',
'prefs-help-realname'           => '* Virkelig navn (valgfritt): dersom du velger å oppgi navnet, vil det bli brukt til å kreditere deg for ditt arbeid.',
'prefs-help-email'              => 'Å angi e-postadresse er valgfritt, men lar det motta nytt passord om du glemmer det gamle.
Du kan også la velge å la andre brukere kontakte deg via brukersiden din uten å røpe identiteten din.',
'prefs-help-email-required'     => 'E-postadresse er påkrevd.',
'prefs-info'                    => 'Grunnleggende informasjon',
'prefs-i18n'                    => 'Internasjonalisering',
'prefs-signature'               => 'Signatur',
'prefs-dateformat'              => 'Datoformat',
'prefs-timeoffset'              => 'Tidsforskyvning',
'prefs-advancedediting'         => 'Avanserte alternativ',
'prefs-advancedrc'              => 'Avanserte alternativ',
'prefs-advancedrendering'       => 'Avanserte alternativ',
'prefs-advancedsearchoptions'   => 'Avanserte alternativ',
'prefs-advancedwatchlist'       => 'Avanserte alternativ',
'prefs-displayrc'               => 'Visningsalternativ',
'prefs-displaysearchoptions'    => 'Visningsalternativer',
'prefs-displaywatchlist'        => 'Visningsalternativer',
'prefs-diffs'                   => 'Forskjeller',

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => 'E-postadressen ser gyldig ut',
'email-address-validity-invalid' => 'Skriv inn en gyldig e-postadresse',

# User rights
'userrights'                     => 'Brukerrettighetskontroll',
'userrights-lookup-user'         => 'Ordne brukergrupper',
'userrights-user-editname'       => 'Skriv inn et brukernavn:',
'editusergroup'                  => 'Endre brukergrupper',
'editinguser'                    => "Endrer brukerrettighetene til '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'       => 'Rediger brukergrupper',
'saveusergroups'                 => 'Lagre brukergrupper',
'userrights-groupsmember'        => 'Medlem av:',
'userrights-groupsmember-auto'   => 'Implisitt medlem av:',
'userrights-groups-help'         => 'Du kan endre hvilke grupper denne brukeren er medlem av.
* En avkrysset boks betyr at brukeren er medlem av gruppen.
* En uavkrysset boks betyr at brukeren ikke er medlem av gruppen.
* En * betyr at du ikke kan fjerne gruppemedlemskapet når du har lagt det til, eller vice versa.',
'userrights-reason'              => 'Årsak:',
'userrights-no-interwiki'        => 'Du har ikke tillatelse til å endre brukerrettigheter på andre wikier.',
'userrights-nodatabase'          => 'Databasen $1 finnes ikke, eller er ikke lokal.',
'userrights-nologin'             => 'Du må [[Special:UserLogin|logge inn]] med en administratorkonto for å endre brukerrettigheter.',
'userrights-notallowed'          => 'Kontoen din har ikke tillatelse til å endre brukerrettigheter.',
'userrights-changeable-col'      => 'Grupper du kan endre',
'userrights-unchangeable-col'    => 'Grupper du ikke kan endre',
'userrights-irreversible-marker' => '$1 *',

# Groups
'group'               => 'Gruppe:',
'group-user'          => 'Brukere',
'group-autoconfirmed' => 'Autobekreftede brukere',
'group-bot'           => 'Roboter',
'group-sysop'         => 'Administratorer',
'group-bureaucrat'    => 'Byråkrater',
'group-suppress'      => 'Sidefjernere',
'group-all'           => '(alle)',

'group-user-member'          => 'bruker',
'group-autoconfirmed-member' => 'autobekreftet bruker',
'group-bot-member'           => 'robot',
'group-sysop-member'         => 'administrator',
'group-bureaucrat-member'    => 'byråkrat',
'group-suppress-member'      => 'revisjonsfjerner',

'grouppage-user'          => '{{ns:project}}:Brukere',
'grouppage-autoconfirmed' => '{{ns:project}}:Autobekreftede brukere',
'grouppage-bot'           => '{{ns:project}}:Roboter',
'grouppage-sysop'         => '{{ns:project}}:Administratorer',
'grouppage-bureaucrat'    => '{{ns:project}}:Byråkrater',
'grouppage-suppress'      => '{{ns:project}}:Sidefjerning',

# Rights
'right-read'                  => 'Se sider',
'right-edit'                  => 'Redigere sider',
'right-createpage'            => 'Opprette sider (som ikke er diskusjonssider)',
'right-createtalk'            => 'Opprette diskusjonssider',
'right-createaccount'         => 'Opprette nye kontoer',
'right-minoredit'             => 'Marker endringer som mindre',
'right-move'                  => 'Flytte sider',
'right-move-subpages'         => 'Flytte sider med undersider',
'right-move-rootuserpages'    => 'Flytte hovedbrukersider',
'right-movefile'              => 'Flytte filer',
'right-suppressredirect'      => 'Behøver ikke å opprette omdirigeringer ved sideflytting',
'right-upload'                => 'Laste opp filer',
'right-reupload'              => 'Skrive over eksisterende filer',
'right-reupload-own'          => 'Skrive over egne filer',
'right-reupload-shared'       => 'Skrive over delte filer lokalt',
'right-upload_by_url'         => 'Laste opp en fil via URL',
'right-purge'                 => 'Rense mellomlageret for sider',
'right-autoconfirmed'         => 'Redigere halvlåste sider',
'right-bot'                   => 'Bli behandlet som en automatisk prosess',
'right-nominornewtalk'        => 'Får ikke «Du har nye meldinger»-beskjeden ved mindre endringer på diskusjonsside',
'right-apihighlimits'         => 'Bruke API med høyere grenser',
'right-writeapi'              => 'Redigere via API',
'right-delete'                => 'Slette sider',
'right-bigdelete'             => 'Slette sider med stor historikk',
'right-deleterevision'        => 'Slette og gjenopprette enkeltrevisjoner av sider',
'right-deletedhistory'        => 'Se slettet sidehistorikk uten tilhørende sidetekst',
'right-deletedtext'           => 'Vis slettet tekst og endringer mellom slettede versjoner',
'right-browsearchive'         => 'Søke i slettede sider',
'right-undelete'              => 'Gjenopprette sider',
'right-suppressrevision'      => 'Se og gjenopprette skjulte siderevisjoner',
'right-suppressionlog'        => 'Se private logger',
'right-block'                 => 'Blokkere andre brukere fra å redigere',
'right-blockemail'            => 'Blokkere brukere fra å sende e-post',
'right-hideuser'              => 'Blokkere et brukernavn og skjule det fra det offentlige',
'right-ipblock-exempt'        => 'Kan redigere fra blokkerte IP-adresser',
'right-proxyunbannable'       => 'Kan redigere fra blokkerte proxyer',
'right-unblockself'           => 'Fjern blokkering av seg selv',
'right-protect'               => 'Endre beskyttelsesnivåer',
'right-editprotected'         => 'Redigere beskyttede sider',
'right-editinterface'         => 'Redigere brukergrensesnittet',
'right-editusercssjs'         => 'Redigere andre brukeres CSS- og JS-filer',
'right-editusercss'           => 'Redigere andre brukeres CSS-filer',
'right-edituserjs'            => 'Redigere andre brukeres JS-filer',
'right-rollback'              => 'Raskt tilbakestille den siste brukeren som har redigert en gitt side',
'right-markbotedits'          => 'Markere tilbakestillinger som robotredigeringer',
'right-noratelimit'           => 'Påvirkes ikke av hastighetsgrenser',
'right-import'                => 'Importere sider fra andre wikier',
'right-importupload'          => 'Importere sider via opplasting',
'right-patrol'                => 'Markere redigeringer som patruljerte',
'right-autopatrol'            => 'Får sine egne redigeringer merket som patruljerte',
'right-patrolmarks'           => 'Bruke patruljeringsfunksjoner i siste endringer',
'right-unwatchedpages'        => 'Se listen over uovervåkede sider',
'right-trackback'             => 'Gi tilbakemelding',
'right-mergehistory'          => 'Flette sidehistorikker',
'right-userrights'            => 'Redigere alle brukerrettigheter',
'right-userrights-interwiki'  => 'Redigere rettigheter for brukere på andre wikier',
'right-siteadmin'             => 'Låse og låse opp databasen',
'right-reset-passwords'       => 'Nullstille andre brukeres passord',
'right-override-export-depth' => 'Eksporter sider inkludert lenkede sider til en dypde på 5',
'right-sendemail'             => 'Send e-post til andre brukere',

# User rights log
'rightslog'      => 'Brukerrettighetslogg',
'rightslogtext'  => 'Dette er en logg over forandringer i brukerrettigheter.',
'rightslogentry' => 'endret gruppe for $1 fra $2 til $3',
'rightsnone'     => '(ingen)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'se denne siden',
'action-edit'                 => 'redigere denne siden',
'action-createpage'           => 'opprette sider',
'action-createtalk'           => 'opprette diskusjonssider',
'action-createaccount'        => 'opprette denne kontoen',
'action-minoredit'            => 'merke denne redigeringen som mindre',
'action-move'                 => 'flytte denne siden',
'action-move-subpages'        => 'flytte denne siden og dens undersider',
'action-move-rootuserpages'   => 'flytte hovedbrukersider',
'action-movefile'             => 'flytte denne filen',
'action-upload'               => 'laste opp denne filen',
'action-reupload'             => 'overskrive den nåværende filen',
'action-reupload-shared'      => 'overskrive denne filen på fellesdatabasen',
'action-upload_by_url'        => 'laste opp denne filen fra en URL',
'action-writeapi'             => 'bruke skrive-API-en',
'action-delete'               => 'slette denne siden',
'action-deleterevision'       => 'slette denne revisjonen',
'action-deletedhistory'       => 'se denne sidens slettede historikk',
'action-browsearchive'        => 'søke i slettede sider',
'action-undelete'             => 'gjenopprette denne siden',
'action-suppressrevision'     => 'se og gjenopprette denne skjulte revisjonen',
'action-suppressionlog'       => 'se denne private loggen',
'action-block'                => 'blokkere denne brukeren fra å redigere',
'action-protect'              => 'endre denne sidens beskyttelsesnivåer',
'action-import'               => 'importere denne siden fra en annen wiki',
'action-importupload'         => 'importere denne siden fra en opplastet fil',
'action-patrol'               => 'merke andre brukeres redigeringer som patruljert',
'action-autopatrol'           => 'merke redigeringene dine som patruljert',
'action-unwatchedpages'       => 'vise listen over uovervåkede sider',
'action-trackback'            => 'levere tilbakemelding',
'action-mergehistory'         => 'flette sidens historikk',
'action-userrights'           => 'redigere alle brukerrettigheter',
'action-userrights-interwiki' => 'endre brukerrettigheter for brukere på andre wikier',
'action-siteadmin'            => 'låse eller låse opp databasen',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|endring|endringer}}',
'recentchanges'                     => 'Siste endringer',
'recentchanges-legend'              => 'Alternativ for siste endringer',
'recentchangestext'                 => 'Vis de siste endringene til denne siden',
'recentchanges-feed-description'    => 'Følg med på siste endringer i denne wikien med denne matingen.',
'recentchanges-label-newpage'       => 'Denne redigeringen opprettet en ny side',
'recentchanges-label-minor'         => 'Dette er en mindre endring',
'recentchanges-label-bot'           => 'Denne redigeringen ble gjort av en bot',
'recentchanges-label-unpatrolled'   => 'Denne redigeringen har ikke blitt patruljert ennå',
'rcnote'                            => "Nedenfor vises {{PLURAL:$1|'''1''' endring|de siste '''$1''' endringene}} fra {{PLURAL:$2|det siste døgnet|de siste '''$2''' døgnene}}, per $5 $4.",
'rcnotefrom'                        => "Nedenfor er endringene fra '''$2''' (opp til '''$1''' vises).",
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
'hide'                              => 'Skjul',
'show'                              => 'Vis',
'minoreditletter'                   => 'm',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 overvåkende {{PLURAL:$1|bruker|brukere}}]',
'rc_categories'                     => 'Begrens til kategorier (skilletegn: «|»)',
'rc_categories_any'                 => 'Alle',
'newsectionsummary'                 => '/* $1 */ ny seksjon',
'rc-enhanced-expand'                => 'Vis detaljer (krever JavaScript)',
'rc-enhanced-hide'                  => 'Skjul detaljer',

# Recent changes linked
'recentchangeslinked'          => 'Relaterte endringer',
'recentchangeslinked-feed'     => 'Relaterte endringer',
'recentchangeslinked-toolbox'  => 'Relaterte endringer',
'recentchangeslinked-title'    => 'Endringer relatert til «$1»',
'recentchangeslinked-noresult' => 'Ingen endringer på lenkede sider i den gitte perioden.',
'recentchangeslinked-summary'  => "Dette er en liste over de siste endringene på sidene lenket fra en spesifisert side (eller til meldlemmer av en spesifisert kategori).
Sider på [[Special:Watchlist|overvåkningslisten din]] er i '''fet skrift'''.",
'recentchangeslinked-page'     => 'Sidenavn:',
'recentchangeslinked-to'       => 'Vis endringer på sider som lenker til den gitte siden istedet',

# Upload
'upload'                      => 'Last opp fil',
'uploadbtn'                   => 'Last opp fil',
'reuploaddesc'                => 'Avbryt opplasting og gå tilbake til opplastingsskjemaet',
'upload-tryagain'             => 'Send inn endret filbeskrivelse',
'uploadnologin'               => 'Ikke logget inn',
'uploadnologintext'           => 'Du må være [[Special:UserLogin|logget inn]] for å kunne laste opp filer.',
'upload_directory_missing'    => 'Oppplastingsmappen ($1) mangler og kunne ikke opprettes av tjeneren.',
'upload_directory_read_only'  => 'Opplastingsmappa ($1) er ikke skrivbar for tjeneren.',
'uploaderror'                 => 'Feil under opplasting av fil',
'upload-recreate-warning'     => "'''Advarsel: En fil med det navnet har blitt slettet eller flyttet.'''

Slette- og flytteloggen for denne siden gjengis her:",
'uploadtext'                  => "Bruk skjemaet nedenfor for å laste opp filer.
For å se eller søke i eksisterende filer, gå til [[Special:FileList|listen over filer]]. Opplastinger lagres også i [[Special:Log/upload|opplastingsloggen]].

For å inkludere en fil på en side, bruk en slik lenke:
*'''<tt><nowiki>[[</nowiki>{{ns:file}}:Filnavn.jpg<nowiki>]]</nowiki></tt>''' for å bruke bildet i opprinnelig form
*'''<tt><nowiki>[[</nowiki>{{ns:file}}:Filnavn.png|200px|thumb|left|Alternativ tekst<nowiki>]]</nowiki></tt>''' for å bruke bildet med en bredde på 200&nbsp;piksler, venstrestilt og med «Alternativ tekst» som beskrivelse
*'''<tt><nowiki>[[</nowiki>{{ns:media}}:Filnavn.ogg<nowiki>]]</nowiki></tt>''' for å lenke direkte til filen uten å vise den",
'upload-permitted'            => 'Tillatte filtyper: $1.',
'upload-preferred'            => 'Foretrukne filtyper: $1',
'upload-prohibited'           => 'Forbudte filtyper: $1.',
'uploadlog'                   => 'opplastingslogg',
'uploadlogpage'               => 'Opplastingslogg',
'uploadlogpagetext'           => 'Her er en liste over de siste opplastede filene.
Se [[Special:NewFiles|galleriet over nye filer]] for en mer visuell visning',
'filename'                    => 'Filnavn',
'filedesc'                    => 'Beskrivelse',
'fileuploadsummary'           => 'Beskrivelse:',
'filereuploadsummary'         => 'Filendringer:',
'filestatus'                  => 'Opphavsrettsstatus:',
'filesource'                  => 'Kilde:',
'uploadedfiles'               => 'Filer som er lastet opp',
'ignorewarning'               => 'Ignorer advarselen og lagre filen likevel',
'ignorewarnings'              => 'Ignorer eventuelle advarsler',
'minlength1'                  => 'Filnavn må være på minst én bokstav.',
'illegalfilename'             => 'Filnavnet «$1» inneholder ugyldige tegn; gi fila et nytt navn og prøv igjen.',
'badfilename'                 => 'Navnet på filen er blitt endret til «$1».',
'filetype-mime-mismatch'      => 'Filendelsen samsvarer ikke med noen MIME-type.',
'filetype-badmime'            => 'Filer av typen «$1» kan ikke lastes opp.',
'filetype-bad-ie-mime'        => 'Kan ikke laste opp denne filen fordi Internet Explorer ville detektert denne som «$1», noe som er ikke er tillatt, og en potensielt farlig filtype.',
'filetype-unwanted-type'      => "'''«.$1»''' er en uønsket filtype.
{{PLURAL:$3|Foretrukken filtype|Foretrukne filtyper}} er $2.",
'filetype-banned-type'        => "'''«$1»''' er ikke en tillatt filtype.
{{PLURAL:$3|Tillatt filtype|Tillatte filtyper}} er $2.",
'filetype-missing'            => 'Filen har ingen endelse (som «.jpg»).',
'empty-file'                  => 'Filen du sendte inn var tom.',
'file-too-large'              => 'Filen du sendte inn var for stor.',
'filename-tooshort'           => 'Filnavnet er for kort.',
'filetype-banned'             => 'Denne filtypen er forbudt.',
'verification-error'          => 'Denne filen bestod ikke filbekreftelsen.',
'hookaborted'                 => 'Endringene du prøvde å gjøre ble avbrutt av en utvidelseskrok.',
'illegal-filename'            => 'Filnavnet er ikke tillatt.',
'overwrite'                   => 'Overskriving av eksisterende filer er ikke tillatt.',
'unknown-error'               => 'En ukjent feil oppsto.',
'tmp-create-error'            => 'Kunne ikke opprette midlertidig fil.',
'tmp-write-error'             => 'Feil ved skriving av midlertidig fil.',
'large-file'                  => 'Det er anbefalt at filen ikke er større enn $1; denne filen er $2.',
'largefileserver'             => 'Denne fila er større enn det tjeneren er satt opp til å tillate.',
'emptyfile'                   => 'Fila du lastet opp ser ut til å være tom. Dette kan komme av en skrivefeil i filnavnet. Sjekk om du virkelig vil laste opp denne fila.',
'fileexists'                  => "Ei fil med dette navnet finnes allerede.
Sjekk '''<tt>[[:$1]]</tt>''' hvis du ikke er sikker på at du vil forandre den.
[[$1|thumb]]",
'filepageexists'              => "Beskrivelsessiden for denne filen finnes allerede på '''<tt>[[:$1]]</tt>''', men ingen filer med dette navnet finnes. Sammendraget du skruver inn vil ikke vises på beskrivelsessiden. For at det skal dukke opp der må du skrive det inn manuelt etter å da lastet opp filen.
[[$1|thumb]]",
'fileexists-extension'        => "En fil med et lignende navn finnes: [[$2|thumb]]
* Navnet på din fil: '''<tt>[[:$1]]</tt>'''
* Navn på eksisterende fil: '''<tt>[[:$2]]</tt>'''
Velg et annet filnavn.",
'fileexists-thumbnail-yes'    => "Filen ser ut til å være et bilde av redusert størrelse. [[$1|thumb]]
Vennligst sjekk filen '''<tt>[[:$1]]</tt>'''.
Om filen du sjekket er det samme bildet, men i opprinnelig størrelse, er det ikke nødvendig å laste opp en ekstra fil.",
'file-thumbnail-no'           => "Filnavnet begynner med '''<tt>$1</tt>'''.
Det virker som om det er et bilde av redusert størrelse ''(miniatyrbilde)''.
Om du har dette bildet i stor utgave, last opp det, eller endre filnavnet på denne filen.",
'fileexists-forbidden'        => 'En fil med dette navnet finnes fra før, og kan ikke erstattes.
Om du fortsatt ønsker å laste opp fila, gå tilbake og last den opp under et nytt navn. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Ei fil med dette navnet finnes fra før i det delte fillageret.
Om du fortsatt ønsker å laste opp fila, gå tilbake og last den opp under et nytt navn. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Denne filen er en dublett av følgende {{PLURAL:$1|fil|filer}}:',
'file-deleted-duplicate'      => 'En fil identisk med denne filen ([[:$1]]) har tidligere blitt slettet. Du bør sjekke denne filens slettehistorikk før du prøver å laste den opp på nytt.',
'uploadwarning'               => 'Opplastingsadvarsel',
'uploadwarning-text'          => 'Vennligst endre filbeskrivelsen nedenfor og prøv igjen.',
'savefile'                    => 'Lagre fil',
'uploadedimage'               => 'lastet opp «[[$1]]»',
'overwroteimage'              => 'last opp en ny versjon av «[[$1]]»',
'uploaddisabled'              => 'Opplastingsfunksjonen er slått av',
'copyuploaddisabled'          => 'Opplasting via nettadresse deaktivert.',
'uploadfromurl-queued'        => 'Opplastingen din har blitt satt i kø.',
'uploaddisabledtext'          => 'Opplasting er slått av.',
'php-uploaddisabledtext'      => 'PHP-filopplasting er deaktivert. Sjekk innstillingen for file_uploads.',
'uploadscripted'              => 'Denne fila inneholder HTML eller skripting som kan feiltolkes av en nettleser.',
'uploadvirus'                 => 'Denne fila inneholder virus! Detaljer: $1',
'upload-source'               => 'Kildefil',
'sourcefilename'              => 'Velg en fil:',
'sourceurl'                   => 'Kildens URL:',
'destfilename'                => 'Ønsket filnavn:',
'upload-maxfilesize'          => 'Maksimal filstørrelse: $1',
'upload-description'          => 'Filbeskrivelse',
'upload-options'              => 'Opplastingsvalg',
'watchthisupload'             => 'Overvåk denne filen',
'filewasdeleted'              => 'Ei fil ved dette navnet har blitt lastet opp tidligere, og så slettet. Sjekk $1 før du forsøker å laste det opp igjen.',
'upload-wasdeleted'           => "'''Advarsel: Du laster opp en fil som tidligere har blitt slettet.'''

Vurder om det er riktig å fortsette å laste opp denne filen. Slettingsloggen for filen gis nedenunder:",
'filename-bad-prefix'         => "Navnet på filen du laster opp begynner med '''«$1»''', hvilket er et ikke-beksrivende navn som vanligvis brukes automatisk av digitalkameraer. Vennligst bruk et mer beskrivende navn på filen.",
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
'upload-success-subj'         => 'Opplastingen er gjennomført',
'upload-success-msg'          => 'Din opplasting fra [$2] var vellykket. Den er tilgjengelig her: [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'Opplastingsproblem',
'upload-failure-msg'          => 'Det oppsto et problem med opplastingen din fra [$2]:

$1',
'upload-warning-subj'         => 'Opplastingsadvarsel',
'upload-warning-msg'          => 'Det oppsto et problem med opplastingen din fra [$2]. Du kan gå tilbake til [[Special:Upload/stash/$1|opplastingsskjemaet]] for å løse dette problemet.',

'upload-proto-error'        => 'Gal protokoll',
'upload-proto-error-text'   => 'Fjernopplasting behøver adresser som begynner med <code>http://</code> eller <code>ftp://</code>.',
'upload-file-error'         => 'Intern feil',
'upload-file-error-text'    => 'En intern feil oppsto under forsøk på å lage en midlertidig fil på tjeneren. Vennligst kontakt en [[Special:ListUsers/sysop|administrator]].',
'upload-misc-error'         => 'Ukjent opplastingsfeil',
'upload-misc-error-text'    => 'En ukjent feil forekom under opplastingen.
Bekreft at adressen er gyldig og tilgjengelig, og prøv igjen.
Om problemet fortsetter, kontakt en [[Special:ListUsers/sysop|administrator]].',
'upload-too-many-redirects' => 'URLen inneholdt for mange omdirigeringer',
'upload-unknown-size'       => 'Ukjent størrelse',
'upload-http-error'         => 'En HTTP-feil oppstod: $1',

# img_auth script messages
'img-auth-accessdenied'     => 'Ingen tilgang',
'img-auth-nopathinfo'       => 'PATH_INFO mangler.
Tjeneren din er ikke satt opp til å gi denne informasjonen.
Den er kanskje CGI-basert og støtter ikke img_auth.
Se http://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir'         => 'Den ønskede stien finnes ikke i den oppsatte opplastingsmappa.',
'img-auth-badtitle'         => 'Kunne ikke lage en gyldig tittel ut fra «$1».',
'img-auth-nologinnWL'       => 'Du er ikke logget inn, og «$1» er ikke på hvitelista.',
'img-auth-nofile'           => 'Filen «$1» finnes ikke.',
'img-auth-isdir'            => 'Du prøver å få tilgang til mappa «$1».
Kun filtilgang tillates.',
'img-auth-streaming'        => 'Sender «$1».',
'img-auth-public'           => 'Funksjonen til img_auth.php er å lage filer fra en privat wiki.
Denne wikien er satt opp som en offentlig wiki.
For best mulig sikkerhet er img_auth.php slått av.',
'img-auth-noread'           => 'Brukeren har ikke tilgang til å lese «$1».',
'img-auth-bad-query-string' => 'URL-en har en ugyldig spørrestreng.',

# HTTP errors
'http-invalid-url'      => 'Ugyldig internettadresse: $1',
'http-invalid-scheme'   => 'Internettadresser med «$1»-formen støttes ikke',
'http-request-error'    => 'HTTP-forespørselen feilet på grunn av en ukjent feil.',
'http-read-error'       => 'HTTP-lesefeil.',
'http-timed-out'        => 'Tidsavbrudd på HTTP-forespørsel.',
'http-curl-error'       => 'Feil under henting av adresse: $1',
'http-host-unreachable' => 'Kunne ikke nå adressen.',
'http-bad-status'       => 'Det var et problem under HTTP-forespørselen: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Kunne ikke nå adressen',
'upload-curl-error6-text'  => 'Adressen kunne ikke nås. Vennligst dobbelsjekk at adressen er korrekt og at siden er oppe.',
'upload-curl-error28'      => 'Opplastingstimeout',
'upload-curl-error28-text' => 'Siden brukte for lang tid på å reagere. Vennligst sjekk at siden er oppe, og vent en kort stund for du prøver igjen. Vurder å prøve på en mindre hektisk tid.',

'license'            => 'Lisens:',
'license-header'     => 'Lisensiering',
'nolicense'          => 'Ingen spesifisert',
'license-nopreview'  => '(Forhåndsvisning ikke tilgjengelig)',
'upload_source_url'  => ' (en gyldig, offentlig tilgjengelig adresse)',
'upload_source_file' => ' (en fil på din datamaskin)',

# Special:ListFiles
'listfiles-summary'     => 'Denne spesialsiden viser alle opplastede filer. De sist opplastede filene vises på toppen som standard. Klikk på en kolonneoverskrift for å endre sorteringsmetode.',
'listfiles_search_for'  => 'Søk etter filnavn:',
'imgfile'               => 'fil',
'listfiles'             => 'Filliste',
'listfiles_thumb'       => 'Miniatyrbilde',
'listfiles_date'        => 'Dato',
'listfiles_name'        => 'Navn',
'listfiles_user'        => 'Bruker',
'listfiles_size'        => 'Størrelse (bytes)',
'listfiles_description' => 'Beskrivelse',
'listfiles_count'       => 'Versjoner',

# File description page
'file-anchor-link'          => 'Fil',
'filehist'                  => 'Filhistorikk',
'filehist-help'             => 'Klikk på en dato/klokkeslett for å se filen slik den var da.',
'filehist-deleteall'        => 'slett alt',
'filehist-deleteone'        => 'slett',
'filehist-revert'           => 'tilbakestill',
'filehist-current'          => 'nåværende',
'filehist-datetime'         => 'Dato/tid',
'filehist-thumb'            => 'Miniatyrbilde',
'filehist-thumbtext'        => 'Miniatyrbilde av versjonen fra $1',
'filehist-nothumb'          => 'Intet miniatyrbilde',
'filehist-user'             => 'Bruker',
'filehist-dimensions'       => 'Dimensjoner',
'filehist-filesize'         => 'Filstørrelse',
'filehist-comment'          => 'Kommentar',
'filehist-missing'          => 'Fil mangler',
'imagelinks'                => 'Fillenker',
'linkstoimage'              => 'Følgende {{PLURAL:$1|side|$1 sider}} har lenker til denne filen:',
'linkstoimage-more'         => 'Mer enn $1 {{PLURAL:$1|side|sider}} lenker til denne fila.
Følgende liste viser {{PLURAL:$1|den første siden|de $1 første sidene}}.
En [[Special:WhatLinksHere/$2|fullstendig liste]] er tilgjengelig.',
'nolinkstoimage'            => 'Det er ingen sider som bruker denne fila.',
'morelinkstoimage'          => 'Vis [[Special:WhatLinksHere/$1|flere lenker]] til denne filen.',
'redirectstofile'           => 'Følgende {{PLURAL:$1|fil er en omdirigering|filer er omdirigeringer}} til denne filen:',
'duplicatesoffile'          => 'Følgende {{PLURAL:$1|fil er en dublett|filer er dubletter}} av denne filen ([[Special:FileDuplicateSearch/$2|fler detaljer]]):',
'sharedupload'              => 'Denne filen er fra $1 og kan bli brukt av andre prosjekter.',
'sharedupload-desc-there'   => 'Denne fila er fra $1 og kan brukes på andre prosjekter.
Se [$2 filbeskrivelsessida] for mer informasjon.',
'sharedupload-desc-here'    => 'Denne fila er fra $1 og kan brukes av andre prosjekter.
Beskrivelsen fra [$2 filbeskrivelsessida] vises nedenfor.',
'filepage-nofile'           => 'Det finnes ingen fil med dette navnet.',
'filepage-nofile-link'      => 'Ingen fil med dette navnet eksisterer, men du kan [$1 laste den opp].',
'uploadnewversion-linktext' => 'Last opp en ny versjon av denne filen',
'shared-repo-from'          => 'fra $1',
'shared-repo'               => 'et delt fillager',

# File reversion
'filerevert'                => 'Tilbakestill $1',
'filerevert-legend'         => 'Tilbakestill fil',
'filerevert-intro'          => "Du tilbakestiller '''[[Media:$1|$1]]''' til [$4 versjonen à $2, $3].",
'filerevert-comment'        => 'Årsak:',
'filerevert-defaultcomment' => 'Tilbakestilte til versjonen à $1, $2',
'filerevert-submit'         => 'Tilbakestill',
'filerevert-success'        => "'''[[Media:$1|$1]]''' ble tilbakestilt til [$4 versjonen à $2, $3].",
'filerevert-badversion'     => 'Det er ingen tidligere lokal versjon av denne filen med det gitte tidstrykket.',

# File deletion
'filedelete'                  => 'Slett $1',
'filedelete-legend'           => 'Slett fil',
'filedelete-intro'            => "Du er i ferd med å slette fila '''[[Media:$1|$1]]''' sammen med hele dens historikk.",
'filedelete-intro-old'        => "Du sletter versjonen av '''[[Media:$1|$1]]''' à [$4 $3, $2].",
'filedelete-comment'          => 'Årsak:',
'filedelete-submit'           => 'Slett',
'filedelete-success'          => "'''$1''' ble slettet.",
'filedelete-success-old'      => "Versjonen av '''[[Media:$1|$1]]''' à $3, $2 ble slettet.",
'filedelete-nofile'           => "'''$1''' finnes ikke.",
'filedelete-nofile-old'       => "Det er ingen arkivert versjon av '''$1''' med de gitte attributtene.",
'filedelete-otherreason'      => 'Annen/utdypende grunn:',
'filedelete-reason-otherlist' => 'Annen grunn',
'filedelete-reason-dropdown'  => '*Vanlige slettingsgrunner
** Opphavsrettsbrudd
** Duplikatfil',
'filedelete-edit-reasonlist'  => 'Rediger begrunnelser for sletting',
'filedelete-maintenance'      => 'Sletting og gjenoppretting av filer er midlertidig slått av på grunn av vedlikehold.',

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
'unusedtemplatestext' => 'Denne siden lister opp alle sider i malnavnerommet ({{ns:template}}:) som ikke er inkludert på en annen side. Husk å sjekke for andre slags lenker til malen før du sletter den.',
'unusedtemplateswlh'  => 'andre lenker',

# Random page
'randompage'         => 'Tilfeldig side',
'randompage-nopages' => 'Det er ingen sider i {{PLURAL:$2|det følgende navnrommet|de følgende navnrommene}}: $1.',

# Random redirect
'randomredirect'         => 'Tilfeldig omdirigering',
'randomredirect-nopages' => 'Det er ingen omdirigeringer i navnerommet $1.',

# Statistics
'statistics'                   => 'Statistikk',
'statistics-header-pages'      => 'Sidestatistikk',
'statistics-header-edits'      => 'Redigeringsstatistikk',
'statistics-header-views'      => 'Visningsstatistikk',
'statistics-header-users'      => 'Brukerstatistikk',
'statistics-header-hooks'      => 'Øvrig statistikk',
'statistics-articles'          => 'Innholdssider',
'statistics-pages'             => 'Sider',
'statistics-pages-desc'        => 'Alle sider på wikien, inkludert diskusjonssider, omdirigeringer o.l.',
'statistics-files'             => 'Opplastede filer',
'statistics-edits'             => 'Redigeringer siden {{SITENAME}} ble opprettet',
'statistics-edits-average'     => 'Gjennomsnittlig antall redigeringer per side',
'statistics-views-total'       => 'Totalt antall visninger',
'statistics-views-total-desc'  => 'Visninger av ikke-eksisterende sider og spesielsider inkluderes ikke',
'statistics-views-peredit'     => 'Visninger per redigering',
'statistics-users'             => 'Registrerte [[Special:ListUsers|brukere]]',
'statistics-users-active'      => 'Aktive brukere',
'statistics-users-active-desc' => 'Brukere som har utført handlinger {{PLURAL:$1|det siste døgnet|de siste $1 dagene}}',
'statistics-mostpopular'       => 'Mest viste sider',

'disambiguations'      => 'Artikler med flertydige titler',
'disambiguationspage'  => 'Template:Peker',
'disambiguations-text' => "Følgende sider lenker til en '''pekerside'''.
De burde i stedet lenke til en passende innholdsside.<br />
En side anses om en pekerside om den inneholder en mal som det lenkes til fra [[MediaWiki:Disambiguationspage]]",

'doubleredirects'            => 'Doble omdirigeringer',
'doubleredirectstext'        => 'Denne siden lister opp de sidene som er omdirigeringer til andre omdirigeringssider.
Hver rad inneholder lenker til første og andre omdirigering, samt målet for den andre omdirigeringen, som vanligvis er den «virkelige» målsiden som den første omdirigeringen burde peke til.
<del>Gjennomstrøkne</del> sider har blitt fikset.',
'double-redirect-fixed-move' => '[[$1]] har blitt flyttet, og er nå en omdirigering til [[$2]]',
'double-redirect-fixer'      => 'Omdirigeringsfikser',

'brokenredirects'        => 'Brutte omdirigeringer',
'brokenredirectstext'    => 'Følgende omdirigeringer peker til ikkeeksisterende sider:',
'brokenredirects-edit'   => 'rediger',
'brokenredirects-delete' => 'slett',

'withoutinterwiki'         => 'Sider uten språklenker',
'withoutinterwiki-summary' => 'Følgende sider lenker ikke til andre språkversjoner:',
'withoutinterwiki-legend'  => 'Prefiks',
'withoutinterwiki-submit'  => 'Vis',

'fewestrevisions' => 'Artikler med færrest revisjoner',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories'             => '$1 {{PLURAL:$1|kategori|kategorier}}',
'nlinks'                  => '$1 {{PLURAL:$1|lenke|lenker}}',
'nmembers'                => '$1 {{PLURAL:$1|medlem|medlemmer}}',
'nrevisions'              => '$1 {{PLURAL:$1|revisjon|revisjoner}}',
'nviews'                  => '$1 {{PLURAL:$1|visning|visninger}}',
'nimagelinks'             => 'Brukt på $1 {{PLURAL:$1|side|sider}}',
'ntransclusions'          => 'brukt på $1 {{PLURAL:$1|side|sider}}',
'specialpage-empty'       => 'Denne siden er tom.',
'lonelypages'             => 'Foreldreløse sider',
'lonelypagestext'         => 'Følgende sider blir ikke lenket til eller brukt på andre sider på {{SITENAME}}.',
'uncategorizedpages'      => 'Ukategoriserte sider',
'uncategorizedcategories' => 'Ukategoriserte kategorier',
'uncategorizedimages'     => 'Ukategoriserte filer',
'uncategorizedtemplates'  => 'Ukategoriserte maler',
'unusedcategories'        => 'Ubrukte kategorier',
'unusedimages'            => 'Ubrukte filer',
'popularpages'            => 'Populære sider',
'wantedcategories'        => 'Ønskede kategorier',
'wantedpages'             => 'Etterspurte sider',
'wantedpages-badtitle'    => 'Ugyldig tittel i resultatene: $1',
'wantedfiles'             => 'Ønskede filer',
'wantedtemplates'         => 'Etterspurte maler',
'mostlinked'              => 'Sider med flest lenker til seg',
'mostlinkedcategories'    => 'Kategorier med flest sider',
'mostlinkedtemplates'     => 'Mest brukte maler',
'mostcategories'          => 'Sider med flest kategorier',
'mostimages'              => 'Mest brukte filer',
'mostrevisions'           => 'Artikler med flest revisjoner',
'prefixindex'             => 'Alle sider med prefiks',
'shortpages'              => 'Korte sider',
'longpages'               => 'Lange sider',
'deadendpages'            => 'Blindveisider',
'deadendpagestext'        => 'Følgende sider lenker ikke til andre sider på {{SITENAME}}.',
'protectedpages'          => 'Låste sider',
'protectedpages-indef'    => 'Kun beskyttelser på ubestemt tid',
'protectedpages-cascade'  => 'Kun dypbeskyttelse',
'protectedpagestext'      => 'Følgende sider er låst for flytting eller redigering',
'protectedpagesempty'     => 'Ingen sider er for øyeblikket låst med disse paramterne.',
'protectedtitles'         => 'Beskyttede titler',
'protectedtitlestext'     => 'Følgende titler er beskyttet fra opprettelse',
'protectedtitlesempty'    => 'Ingen titler beskyttes med disse parameterne for øyeblikket.',
'listusers'               => 'Brukerliste',
'listusers-editsonly'     => 'Vis bare brukere med redigeringer',
'listusers-creationsort'  => 'Sorter etter opprettelsesdato',
'usereditcount'           => '{{PLURAL:$1|én redigering|$1 redigeringer}}',
'usercreated'             => 'Opprettet den $1 $2',
'newpages'                => 'Nye sider',
'newpages-username'       => 'Brukernavn:',
'ancientpages'            => 'Eldste sider',
'move'                    => 'Flytt',
'movethispage'            => 'Flytt denne siden',
'unusedimagestext'        => 'Følgende filer eksisterer men er ikke innlagt på noen sider.
Merk at andre sider kanskje lenker til en fil med en direkte lenke, så filen listes her selv om den faktisk er i bruk.',
'unusedcategoriestext'    => 'Følgende kategorier finnes, men det er ingen sider i dem.',
'notargettitle'           => 'Intet mål',
'notargettext'            => 'Du oppga ikke en målside eller bruker å utføre denne funksjonen på.',
'nopagetitle'             => 'Målsiden finnes ikke',
'nopagetext'              => 'Siden du ville flytte finnes ikke.',
'pager-newer-n'           => '{{PLURAL:$1|1 nyere|$1 nyere}}',
'pager-older-n'           => '{{PLURAL:$1|1 eldre|$1 eldre}}',
'suppress'                => 'Sidefjerning',

# Book sources
'booksources'               => 'Bokkilder',
'booksources-search-legend' => 'Søk etter bokkilder',
'booksources-go'            => 'Gå',
'booksources-text'          => 'Under er en liste over lenker til andre sider som selger nye og brukte bøker, og kan også ha videre informasjon om bøker du leter etter:',
'booksources-invalid-isbn'  => 'Det gitte ISBN-nummeret er ugyldig; sjekk om du har angitt det riktig.',

# Special:Log
'specialloguserlabel'  => 'Bruker:',
'speciallogtitlelabel' => 'Tittel:',
'log'                  => 'Logger',
'all-logs-page'        => 'Alle offentlige logger',
'alllogstext'          => 'Kombinert visning av alle loggene på {{SITENAME}}.
Du kan minske antallet resultater ved å velge loggtype, brukernavn eller den siden som er påvirket (husk å skille mellom store og små bokstaver).',
'logempty'             => 'Ingen elementer i loggen.',
'log-title-wildcard'   => 'Søk i titler som starter med denne teksten',

# Special:AllPages
'allpages'          => 'Alle sider',
'alphaindexline'    => '$1 til $2',
'nextpage'          => 'Neste side ($1)',
'prevpage'          => 'Forrige side ($1)',
'allpagesfrom'      => 'Vis sider fra og med:',
'allpagesto'        => 'Vis sider som slutter på:',
'allarticles'       => 'Alle sider',
'allinnamespace'    => 'Alle sider i $1-navnerommet',
'allnotinnamespace' => 'Alle sider (ikke i $1-navnerommet)',
'allpagesprev'      => 'Forrige',
'allpagesnext'      => 'Neste',
'allpagessubmit'    => 'Gå',
'allpagesprefix'    => 'Vis sider med prefikset:',
'allpagesbadtitle'  => 'Den angitte sidetittelen var ugyldig eller hadde et interwiki-prefiks. Den kan inneholde ett eller flere tegn som ikke kan brukes i titler.',
'allpages-bad-ns'   => '{{SITENAME}} har ikke navnerommet «$1».',

# Special:Categories
'categories'                    => 'Kategorier',
'categoriespagetext'            => 'Følgende {{PLURAL:$1|kategori|kategorier}} inneholder sider eller media.
[[Special:UnusedCategories|Ubrukte kategorier]] vises ikke her.
Se også [[Special:WantedCategories|ønskede kategorier]].',
'categoriesfrom'                => 'Vis kategorier fra og med:',
'special-categories-sort-count' => 'soter etter antall',
'special-categories-sort-abc'   => 'sorter alfabetisk',

# Special:DeletedContributions
'deletedcontributions'             => 'Slettede brukerbidrag',
'deletedcontributions-title'       => 'Slettede brukerbidrag',
'sp-deletedcontributions-contribs' => 'bidrag',

# Special:LinkSearch
'linksearch'       => 'Eksterne lenker',
'linksearch-pat'   => 'Søkemønster:',
'linksearch-ns'    => 'Navnerom:',
'linksearch-ok'    => 'Søk',
'linksearch-text'  => 'Jokertegn som «*.wikipedia.org» kan brukes.<br />Støttede protokoller: <tt>$1</tt>',
'linksearch-line'  => '$1 lenkes fra $2',
'linksearch-error' => 'Jokertegn kan kun brukes foran tjenernavnet.',

# Special:ListUsers
'listusersfrom'      => 'Vis brukere fra og med:',
'listusers-submit'   => 'Vis',
'listusers-noresult' => 'Ingen bruker funnet.',
'listusers-blocked'  => '(konto blokkert)',

# Special:ActiveUsers
'activeusers'            => 'Liste over aktive brukere',
'activeusers-intro'      => 'Dette er en liste over brukere som har hatt en eller annen form for aktivitet innenfor {{PLURAL:$1|den siste dagen|de siste dagene}}.',
'activeusers-count'      => '$1 {{PLURAL:$1|endring|endringer}} {{PLURAL:$3|det siste døgnet|de siste $3 dagene}}',
'activeusers-from'       => 'Vis brukere fra og med:',
'activeusers-hidebots'   => 'Skjul roboter',
'activeusers-hidesysops' => 'Skjul administratorer',
'activeusers-noresult'   => 'Ingen brukere funnet.',

# Special:Log/newusers
'newuserlogpage'              => 'Brukeropprettelseslogg',
'newuserlogpagetext'          => 'Dette er en logg over brukeropprettelser.',
'newuserlog-byemail'          => 'passord sendt på e-post',
'newuserlog-create-entry'     => 'Ny brukerkonto',
'newuserlog-create2-entry'    => 'opprettet kontoen $1',
'newuserlog-autocreate-entry' => 'Konto opprettet automatisk',

# Special:ListGroupRights
'listgrouprights'                      => 'Rettigheter for brukergrupper',
'listgrouprights-summary'              => 'Følgende er en liste over brukergrupper som er definert på denne wikien, og hvilke rettigheter de har.
Mer informasjon om de enkelte rettighetstypene kan finnes [[{{MediaWiki:Listgrouprights-helppage}}|her]].',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Innvilget rettighet</span>
* <span class="listgrouprights-granted">Tilbaketrukket rettighet</span>',
'listgrouprights-group'                => 'Gruppe',
'listgrouprights-rights'               => 'Rettigheter',
'listgrouprights-helppage'             => 'Help:Grupperettigheter',
'listgrouprights-members'              => '(liste over medlemmer)',
'listgrouprights-addgroup'             => 'Kan legge til {{PLURAL:$2|gruppa|gruppene}}: $1',
'listgrouprights-removegroup'          => 'Kan fjerne {{PLURAL:$2|gruppa|gruppene}}: $1',
'listgrouprights-addgroup-all'         => 'Kan legge til alle grupper',
'listgrouprights-removegroup-all'      => 'Kan fjerne alle grupper',
'listgrouprights-addgroup-self'        => 'Kan legge til {{PLURAL:$2|gruppe|gruppene}} til egen konto: $1',
'listgrouprights-removegroup-self'     => 'Kan ta vekk {{PLURAL:$2|gruppe|grupper}} fra egen konto: $1',
'listgrouprights-addgroup-self-all'    => 'Kan legge til alle grupper til egen konto',
'listgrouprights-removegroup-self-all' => 'Kan ta bort alle grupper fra egen konto',

# E-mail user
'mailnologin'          => 'Ingen avsenderadresse',
'mailnologintext'      => 'Du må være [[Special:UserLogin|logget inn]] og ha en gyldig e-postadresse satt i [[Special:Preferences|brukerinnstillingene]] for å sende e-post til andre brukere.',
'emailuser'            => 'E-post til denne brukeren',
'emailpage'            => 'E-post til bruker',
'emailpagetext'        => 'Du kan bruke skjemaet nedenfor for å sende en e-post til denne brukeren.
Den e-postadressen du har satt i [[Special:Preferences|innstillingene dine]] vil dukke opp i «fra»-feltet på denne e-posten, så mottakeren er i stand til å svare.',
'usermailererror'      => 'E-postobjekt returnerte feilen:',
'defemailsubject'      => 'E-post fra {{SITENAME}}',
'usermaildisabled'     => 'Brukerepost deaktivert',
'usermaildisabledtext' => 'Du kan ikke sende epost til andre brukere på denne wikien',
'noemailtitle'         => 'Ingen e-postadresse',
'noemailtext'          => 'Dene brukeren har ikke oppgitt en gyldig e-postadresse.',
'nowikiemailtitle'     => 'Ingen e-post tillatt',
'nowikiemailtext'      => 'Denne brukeren har valgt å ikke motta e-post fra andre brukere.',
'email-legend'         => 'Send en e-post til en annen {{SITENAME}}-bruker',
'emailfrom'            => 'Fra:',
'emailto'              => 'Til:',
'emailsubject'         => 'Emne:',
'emailmessage'         => 'Beskjed:',
'emailsend'            => 'Send',
'emailccme'            => 'Send meg en kopi av beskjeden min.',
'emailccsubject'       => 'Kopi av din beskjed til $1: $2',
'emailsent'            => 'E-post sendt',
'emailsenttext'        => 'E-postbeskjeden er sendt',
'emailuserfooter'      => 'E-posten ble sendt av $1 til $2 via «Send e-post»-funksjonen på {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'Etterlater en systembeskjed.',
'usermessage-editor'  => 'Systembudbringer',

# Watchlist
'watchlist'            => 'Overvåkningsliste',
'mywatchlist'          => 'Overvåkningsliste',
'watchlistfor2'        => 'For $1 $2',
'nowatchlist'          => 'Du har ingenting i overvåkningslisten.',
'watchlistanontext'    => 'Vennligst $1 for å vise eller redigere sider på overvåkningslisten din.',
'watchnologin'         => 'Ikke logget inn',
'watchnologintext'     => 'Du må være [[Special:UserLogin|logget inn]] for å kunne endre overvåkningslisten.',
'addedwatch'           => 'Lagt til overvåkningsliste',
'addedwatchtext'       => "Siden «[[:$1]]» er lagt til [[Special:Watchlist|overvåkningslisten]].
Fremtidige endringer til denne siden og den tilhørende diskusjonssiden blir listet opp her, og siden vil fremstå '''uthevet''' i [[Special:RecentChanges|listen over siste endringer]] for å gjøre det lettere å finne den.",
'removedwatch'         => 'Fjernet fra overvåkningsliste',
'removedwatchtext'     => 'Siden «[[:$1]]» er fjernet fra [[Special:Watchlist|overvåkningslisten din]].',
'watch'                => 'Overvåk',
'watchthispage'        => 'Overvåk denne siden',
'unwatch'              => 'Avslutt overvåkning',
'unwatchthispage'      => 'Fjerner overvåkning',
'notanarticle'         => 'Ikke en artikkel',
'notvisiblerev'        => 'Revisjonen er slettet',
'watchnochange'        => 'Ingen av sidene i overvåkningslisten er endret i den valgte perioden.',
'watchlist-details'    => '{{PLURAL:$1|Én side|$1 sider}} på din overvåkningsliste, teller ikke diskusjonssider.',
'wlheader-enotif'      => '* E-postnotifikasjon er slått på.',
'wlheader-showupdated' => "* Sider som har blitt forandret siden du sist besøkte dem vises i '''fet tekst'''",
'watchmethod-recent'   => 'sjekker siste endringer for sider i overvåkningslisten',
'watchmethod-list'     => 'sjekker siste endringer for sider i overvåkningslisten',
'watchlistcontains'    => 'Overvåkningslisten inneholder $1 {{PLURAL:$1|side|sider}}.',
'iteminvalidname'      => 'Problem med «$1», ugyldig navn&nbsp;…',
'wlnote'               => "Nedenfor er {{PLURAL:$1|den siste endringen|de siste $1 endringene}} {{PLURAL:$2|den siste timen|de siste '''$2''' timene}}.",
'wlshowlast'           => 'Vis siste $1 timer $2 dager $3',
'watchlist-options'    => 'Alternativ for overvåkningslisten',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Overvåker…',
'unwatching' => 'Fjerner fra overvåkningsliste…',

'enotif_mailer'                => '{{SITENAME}}s påminnelsessystem',
'enotif_reset'                 => 'Merk alle sider som besøkt',
'enotif_newpagetext'           => 'Dette er en ny side.',
'enotif_impersonal_salutation' => '{{SITENAME}}-bruker',
'changed'                      => 'endret',
'created'                      => 'opprettet',
'enotif_subject'               => '{{SITENAME}}-siden $PAGETITLE har blitt $CHANGEDORCREATED av $PAGEEDITOR',
'enotif_lastvisited'           => 'Se $1 for alle endringer siden ditt forrige besøk.',
'enotif_lastdiff'              => 'Se $1 for å se denne endringen.',
'enotif_anon_editor'           => 'anonym bruker $1',
'enotif_body'                  => 'Kjære $WATCHINGUSERNAME,


{{SITENAME}}-siden $PAGETITLE har blitt $CHANGEDORCREATED den $PAGEEDITDATE av $PAGEEDITOR, se $PAGETITLE_URL for den nåværende revisjonen.

$NEWPAGE

Redigeringssammendrag: $PAGESUMMARY $PAGEMINOREDIT

Kontakt brukeren:
e-post: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Det vil ikke komme flere varsler om endringer på denne siden med mindre du besøker den.
Du kan også fjerne varslingsflagg for alle overvåkede sider i overvåkningslisten din.

             Ditt vennlige varslingssystem for {{SITENAME}}

--
For å endre innstillingene for e-postvarsler, se
{{fullurl:{{#special:Preferences}}}}

For å endre innstillingene for overvåkningslisten, se
{{fullurl:{{#special:Watchlist}}/edit}}

For å slette en side fra overvåkningssiden, se
$UNWATCHURL

Tilbakemelding og videre assistanse:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Slett side',
'confirm'                => 'Bekreft',
'excontent'              => 'Innholdet var: «$1»',
'excontentauthor'        => 'innholdet var «$1» (og eneste bidragsyter var [[Special:Contributions/$2|$2]])',
'exbeforeblank'          => 'innholdet før siden ble tømt var: «$1»',
'exblank'                => 'siden var tom',
'delete-confirm'         => 'Slett «$1»',
'delete-legend'          => 'Slett',
'historywarning'         => 'Advarsel: Siden du er i ferd med å slette har en historikk med omtrent {{PLURAL:$1|én revisjon|$1 revisjoner}}:',
'confirmdeletetext'      => 'Du holder på å slette en side sammen med historikken.
Bekreft at du virkelig vil slette denne siden, at du forstår konsekvensene og at du gjør det i samsvar med [[{{MediaWiki:Policy-url}}|retningslinjene]].',
'actioncomplete'         => 'Gjennomført',
'actionfailed'           => 'Handling mislyktes',
'deletedtext'            => '«<nowiki>$1</nowiki>» er slettet.
Se $2 for en oversikt over de siste slettingene.',
'deletedarticle'         => 'slettet «[[$1]]»',
'suppressedarticle'      => 'fjernet «[[$1]]»',
'dellogpage'             => 'Slettingslogg',
'dellogpagetext'         => 'Under er ei liste over nylige slettinger.',
'deletionlog'            => 'slettingslogg',
'reverted'               => 'Gjenopprettet en tidligere versjon',
'deletecomment'          => 'Årsak:',
'deleteotherreason'      => 'Annen/utdypende grunn:',
'deletereasonotherlist'  => 'Annen grunn',
'deletereason-dropdown'  => '* Vanlige grunner for sletting
** På forfatters forespørsel
** Opphavsrettsbrudd
** Vandalisme',
'delete-edit-reasonlist' => 'Rediger begrunnelser for sletting',
'delete-toobig'          => 'Denne siden har en stor redigeringshistorikk, med over {{PLURAL:$1|$1&nbsp;revisjon|$1&nbsp;revisjoner}}. Muligheten til å slette slike sider er begrenset for å unngå utilsiktet forstyrring av {{SITENAME}}.',
'delete-warning-toobig'  => 'Denne siden har en stor redigeringshistorikk, med over {{PLURAL:$1|$1&nbsp;revisjon|$1&nbsp;revisjoner}}. Sletting av denne siden kan forstyrre databasen til {{SITENAME}}; vær varsom.',

# Rollback
'rollback'          => 'Fjern redigeringer',
'rollback_short'    => 'Tilbakestill',
'rollbacklink'      => 'tilbakestill',
'rollbackfailed'    => 'Kunne ikke tilbakestille',
'cantrollback'      => 'Kan ikke fjerne redigering; den siste brukeren er den eneste forfatteren.',
'alreadyrolled'     => 'Kan ikke fjerne den siste redigeringen på [[$1]] av [[User:$2|$2]] ([[User talk:$2|diskusjon]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]); en annen har allerede redigert siden eller fjernet redigeringen.

Den siste redigeringen ble foretatt av [[User:$3|$3]] ([[User talk:$3|diskusjon]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "Redigeringskommentaren var: «''$1''»",
'revertpage'        => 'Tilbakestilte endring av [[Special:Contributions/$2|$2]] ([[User talk:$2|diskusjon]]) til siste versjon av [[User:$1|$1]]',
'revertpage-nouser' => 'Tilbakestilte endringer av (fjernet brukernavn) til siste versjon av [[User:$1|$1]]',
'rollback-success'  => 'Tilbakestilte endringer av $1; endret til siste versjon av $2.',

# Edit tokens
'sessionfailure-title' => 'Sesjonsfeil',
'sessionfailure'       => "Det ser ut til å være et problem med innloggingen din, og den ble avbrutt av sikkerhetshensyn. Trykk ''Tilbake'' i nettleseren din, oppdater siden og prøv igjen.",

# Protect
'protectlogpage'              => 'Beskyttelseslogg',
'protectlogtext'              => 'Her er en liste over sider som er blitt beskyttet eller har fått fjernet beskyttelsen. Se [[Special:ProtectedPages|listen over låste sider]] for en liste over nåværende låste sider.',
'protectedarticle'            => 'beskyttet «[[$1]]»',
'modifiedarticleprotection'   => 'endret beskyttelsesnivå for «[[$1]]»',
'unprotectedarticle'          => 'åpnet [[$1]]',
'movedarticleprotection'      => 'flyttet beskyttelsesinnstillinger fra «[[$2]]» til «[[$1]]»',
'protect-title'               => 'Låser «$1»',
'prot_1movedto2'              => '[[$1]] flyttet til [[$2]]',
'protect-legend'              => 'Bekreft låsing',
'protectcomment'              => 'Årsak:',
'protectexpiry'               => 'Utløper:',
'protect_expiry_invalid'      => 'Utløpstiden er ugyldig.',
'protect_expiry_old'          => 'Utløpstiden har allerede vært.',
'protect-unchain-permissions' => 'Lås opp flere beskyttelsesinnstillinger',
'protect-text'                => "Du kan se og endre beskyttelsesnivået for siden '''<nowiki>$1</nowiki>''' her.",
'protect-locked-blocked'      => "Du kan ikke endre beskyttelsesnivåer mens du er blokkert. Dette er de nåværende innstillingene for siden '''$1''':",
'protect-locked-dblock'       => "Beskyttelsesnivåer kan ikke endres under en aktiv databasebeskyttelse. Dette er de nåværende innstillingene for siden '''$1''':",
'protect-locked-access'       => "Kontoen din har ikke tillatelse til å endre sidebeskyttelsesnivå.
Dette er de nåværende innstillingene for siden '''$1''':",
'protect-cascadeon'           => 'Denne siden er for tiden beskyttet fordi den er inkludert på følgende {{PLURAL:$1|side|sider}} som har dypbeskyttelse slått på.
Du kan endre sidens beskyttelsesnivå, men det vil ikke påvirke dypbeskyttelsen.',
'protect-default'             => 'Tillat alle brukere',
'protect-fallback'            => 'Må ha «$1»-tillatelse',
'protect-level-autoconfirmed' => 'Blokker nye og uregistrerte brukere',
'protect-level-sysop'         => 'Kun administratorer',
'protect-summary-cascade'     => 'dypbeskyttelse',
'protect-expiring'            => 'utløper $1 (UTC)',
'protect-expiry-indefinite'   => 'ubestemt',
'protect-cascade'             => 'Beskytt sider som er inkludert på denne siden (dypbeskyttelse)',
'protect-cantedit'            => 'Du kan ikke endre beskyttelsesnivået til denne siden fordi du ikke har tillatelse til å redigere den.',
'protect-othertime'           => 'Annen tid:',
'protect-othertime-op'        => 'annen tid',
'protect-existing-expiry'     => 'Gjeldende utløpstid: $3 $2',
'protect-otherreason'         => 'Annen/utdypende grunn:',
'protect-otherreason-op'      => 'Annen grunn',
'protect-dropdown'            => '*Vanlige låsingsårsaker
** Gjentatt hærverk
** Gjentatt spam
** Redigeringskrig
** Side med mange besøkende',
'protect-edit-reasonlist'     => 'Rediger låsingsgrunner',
'protect-expiry-options'      => '1 time:1 hour,1 dag:1 day,1 uke:1 week,2 uker:2 weeks,1 måned:1 month,3 måneder:3 months,6 måneder:6 months,1 år:1 year,uendelig:infinite',
'restriction-type'            => 'Tillatelse:',
'restriction-level'           => 'Restriksjonsnivå:',
'minimum-size'                => 'Minimumstørrelse',
'maximum-size'                => 'Maksimumstørrelse:',
'pagesize'                    => '(byte)',

# Restrictions (nouns)
'restriction-edit'   => 'Redigering',
'restriction-move'   => 'Flytting',
'restriction-create' => 'Opprett',
'restriction-upload' => 'Last opp',

# Restriction levels
'restriction-level-sysop'         => 'fullstendig låst',
'restriction-level-autoconfirmed' => 'halvlåst',
'restriction-level-all'           => 'alle nivåer',

# Undelete
'undelete'                     => 'Vis slettede sider',
'undeletepage'                 => 'Se og gjenopprett slettede sider',
'undeletepagetitle'            => "'''Følgende innhold er slettede revisjoner av [[:$1]].'''",
'viewdeletedpage'              => 'Vis slettede sider',
'undeletepagetext'             => 'Følgende {{PLURAL:$1|side|sider}} er slettet, men finnes fortsatt i arkivet og kan gjenopprettes. Arkivet blir periodevis slettet.',
'undelete-fieldset-title'      => 'Gjenopprett revisjoner',
'undeleteextrahelp'            => "For å gjenopprette hele siden, la alle boksene være som de er, og klikk '''''Gjenopprett'''''.
For å gjenopprette kun deler, kryss av revisjonenes bokser, og klikk '''''Gjenopprett'''''.
Å klikke '''''Nullstill''''' vil føre til at alle tekstfelt og bokser gjøres blanke.",
'undeleterevisions'            => '{{PLURAL:$1|Én versjon arkivert|$1 versjoner arkiverte}}',
'undeletehistory'              => 'Om du gjenoppretter siden vil alle revisjoner gjenopprettes i historikken.
Dersom en ny side ved samme navn har blitt oprettet etter slettingen, vil de gjenopprettede revisjonene dukke opp før denne i redigeringshistorikken.',
'undeleterevdel'               => 'Gjenoppretting kan ikke utføres dersom det resulterer i at den øverste revisjonen blir delvis slettet. I slike tilfeller må du fjerne merkingen av den nyeste slettede revisjonen.',
'undeletehistorynoadmin'       => 'Denne artikkelen har blitt slettet. Grunnen for slettingen vises i oppsummeringen nedenfor, sammen med detaljer om brukerne som redigerte siden før den ble slettet. Teksten i disse slettede revisjonene er kun tilgjengelig for administratorer.',
'undelete-revision'            => 'Slettet revisjon av $1 (per $4 $5) av $3:',
'undeleterevision-missing'     => 'Ugyldig eller manglende revisjon. Du kan ha en ødelagt lenke, eller revisjonen har blitt fjernet fra arkivet.',
'undelete-nodiff'              => 'Ingen tidligere revisjoner funnet.',
'undeletebtn'                  => 'Gjenopprett',
'undeletelink'                 => 'vis/gjenopprett',
'undeleteviewlink'             => 'vis',
'undeletereset'                => 'Nullstill',
'undeleteinvert'               => 'Inverter valg',
'undeletecomment'              => 'Årsak:',
'undeletedarticle'             => 'gjenopprettet «[[$1]]»',
'undeletedrevisions'           => '{{PLURAL:$1|Én revisjon|$1 revisjoner}} gjenopprettet',
'undeletedrevisions-files'     => '{{PLURAL:$1|Én revisjon|$1 revisjoner}} og {{PLURAL:$2|én fil|$2 filer}} gjenopprettet',
'undeletedfiles'               => '{{PLURAL:$1|Én fil|$1 filer}} gjenopprettet',
'cannotundelete'               => 'Kunne ikke gjenopprette siden (den kan være gjenopprettet av noen andre).',
'undeletedpage'                => "'''$1 ble gjenopprettet'''

Sjekk [[Special:Log/delete|slettingsloggen]] for en liste over nylige slettinger og gjenopprettelser.",
'undelete-header'              => 'Se [[Special:Log/delete|slettingsloggen]] for nylig slettede sider.',
'undelete-search-box'          => 'Søk i slettede sider',
'undelete-search-prefix'       => 'Vis sider som starter med:',
'undelete-search-submit'       => 'Søk',
'undelete-no-results'          => 'Ingen passende sider funnet i slettingsarkivet.',
'undelete-filename-mismatch'   => 'Kan ikke gjenopprette filrevisjon med tidstrykk $1: ikke samsvarende filnavn',
'undelete-bad-store-key'       => 'Kan ikke gjenopprette filrevisjon med tidstrykk $1: fil manglet før sletting',
'undelete-cleanup-error'       => 'Feil i sletting av ubrukt arkivfil «$1».',
'undelete-missing-filearchive' => 'Klarte ikke å gjenopprette filarkivet med ID $1 fordi det ikke er i databasen. Det kan ha blitt gjenopprettet tidligere.',
'undelete-error-short'         => 'Feil under filgjenoppretting: $1',
'undelete-error-long'          => 'Feil oppsto under filgjenoppretting:

$1',
'undelete-show-file-confirm'   => 'Er du sikker på at du vil vise en slettet versjon av filen «<nowiki>$1</nowiki>» fra den $2 klokken $3?',
'undelete-show-file-submit'    => 'Ja',

# Namespace form on various pages
'namespace'      => 'Navnerom:',
'invert'         => 'Inverter valg',
'blanknamespace' => '(Hoved)',

# Contributions
'contributions'       => 'Brukerbidrag',
'contributions-title' => 'Brukerbidrag av $1',
'mycontris'           => 'Egne bidrag',
'contribsub2'         => 'For $1 ($2)',
'nocontribs'          => 'Ingen endringer er funnet som passer disse kriteriene.',
'uctop'               => '(siste)',
'month'               => 'Fra måned (og tidligere):',
'year'                => 'Fra år (og tidligere):',

'sp-contributions-newbies'             => 'Vis kun bidrag fra nye kontoer',
'sp-contributions-newbies-sub'         => 'For nybegynnere',
'sp-contributions-newbies-title'       => 'Bidrag av nye kontoer',
'sp-contributions-blocklog'            => 'blokkeringslogg',
'sp-contributions-deleted'             => 'slettede brukerbidrag',
'sp-contributions-uploads'             => 'opplastinger',
'sp-contributions-logs'                => 'logger',
'sp-contributions-talk'                => 'diskusjon',
'sp-contributions-userrights'          => 'brukerrettighetskontroll',
'sp-contributions-blocked-notice'      => 'Denne brukeren er for tiden blokkert.
Siste blokkeringsloggelement kan sees nedenfor.',
'sp-contributions-blocked-notice-anon' => 'Denne IP-adressen er for tiden blokkert.
Den siste oppføringen i blokkeringsloggen er vist nedenfor som referanse:',
'sp-contributions-search'              => 'Søk etter bidrag',
'sp-contributions-username'            => 'IP-adresse eller brukernavn:',
'sp-contributions-toponly'             => 'Vis kun endringer som er gjeldende revisjoner',
'sp-contributions-submit'              => 'Søk',

# What links here
'whatlinkshere'            => 'Lenker hit',
'whatlinkshere-title'      => 'Sider som lenker til «$1»',
'whatlinkshere-page'       => 'Side:',
'linkshere'                => "Følgende sider lenker til '''[[:$1]]''':",
'nolinkshere'              => "Ingen sider lenker til '''[[:$1]]'''.",
'nolinkshere-ns'           => "Ingen sider lenker til '''[[:$1]]''' i valgte navnerom.",
'isredirect'               => 'omdirigeringsside',
'istemplate'               => 'transklusjon',
'isimage'                  => 'bildelenke',
'whatlinkshere-prev'       => '{{PLURAL:$1|forrige|forrige $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|neste|neste $1}}',
'whatlinkshere-links'      => '← lenker',
'whatlinkshere-hideredirs' => '$1 omdirigeringer',
'whatlinkshere-hidetrans'  => '$1 transkluderinger',
'whatlinkshere-hidelinks'  => '$1 lenker',
'whatlinkshere-hideimages' => '$1 fillenker',
'whatlinkshere-filters'    => 'Filtre',

# Block/unblock
'blockip'                         => 'Blokker bruker',
'blockip-title'                   => 'Blokker bruker',
'blockip-legend'                  => 'Blokker bruker',
'blockiptext'                     => 'Bruk skjemaet under for å blokkere en IP-adresses tilgang til å redigere artikler. Dette må kun gjøres for å forhindre hærverk, og i overensstemmelse med [[{{MediaWiki:Policy-url}}|retningslinjene]]. Fyll ut en spesiell begrunnelse under.',
'ipaddress'                       => 'IP-adresse',
'ipadressorusername'              => 'IP-adresse eller brukernavn',
'ipbexpiry'                       => 'Varighet:',
'ipbreason'                       => 'Årsak:',
'ipbreasonotherlist'              => 'Annen grunn',
'ipbreason-dropdown'              => '*Vanlige blokkeringsgrunner
** Legger inn feilinformasjon
** Fjerner innhold fra sider
** Lenkespam
** Legger inn vås
** Truende oppførsel
** Misbruk av flere kontoer
** Uakseptabelt brukernavn',
'ipbanononly'                     => 'Blokker kun anonyme brukere',
'ipbcreateaccount'                => 'Hindre kontoopprettelse',
'ipbemailban'                     => 'Forhindre brukeren fra å sende e-post',
'ipbenableautoblock'              => 'Blokker forrige IP-adresse brukt av denne brukeren automatisk, samt alle IP-adresser brukeren forsøker å redigere med i framtiden',
'ipbsubmit'                       => 'Blokker denne brukeren',
'ipbother'                        => 'Annen tid',
'ipboptions'                      => '2 timer:2 hours,1 dag:1 day,3 dager:3 days,1 uke:1 week,2 uker:2 weeks,1 måned:1 month,3 måneder:3 months,6 måneder:6 months,1 år:1 year,uendelig:infinite',
'ipbotheroption'                  => 'annet',
'ipbotherreason'                  => 'Annen/utdypende grunn:',
'ipbhidename'                     => 'Skjul brukernavn fra endringer og lister',
'ipbwatchuser'                    => 'Overvåk brukerens brukerside og diskusjonsside',
'ipballowusertalk'                => 'La brukeren redigere sin egen diskusjonssiden under blokkeringen',
'ipb-change-block'                => 'Blokker brukeren på nytt med disse innstillingene',
'badipaddress'                    => 'Ugyldig IP-adresse.',
'blockipsuccesssub'               => 'Blokkering utført',
'blockipsuccesstext'              => '­«[[Special:Contributions/$1|$1]]» har blitt blokkert.<br />
Se [[Special:IPBlockList|blokkeringslisten]] for alle blokkeringer.',
'ipb-edit-dropdown'               => 'Rediger blokkeringsgrunner',
'ipb-unblock-addr'                => 'Avblokker $1',
'ipb-unblock'                     => 'Avblokker et brukernavn eller en IP-adresse',
'ipb-blocklist'                   => 'Vis gjeldende blokkeringer',
'ipb-blocklist-contribs'          => 'Bidrag fra $1',
'unblockip'                       => 'Opphev blokkering',
'unblockiptext'                   => 'Bruk skjemaet under for å gjenopprette skriveadgangen for en tidligere blokkert adresse eller bruker.',
'ipusubmit'                       => 'Opphev blokkering',
'unblocked'                       => '[[User:$1|$1]] ble avblokkert',
'unblocked-id'                    => 'Blokkering $1 ble fjernet',
'ipblocklist'                     => 'Blokkerte IP-adresser og brukernavn',
'ipblocklist-legend'              => 'Finn en blokkert bruker',
'ipblocklist-username'            => 'Brukernavn eller IP-adresse:',
'ipblocklist-sh-userblocks'       => '$1 blokkeringer av kontoer',
'ipblocklist-sh-tempblocks'       => '$1 midlertidige blokkeringer',
'ipblocklist-sh-addressblocks'    => '$1 blokkeringer av individuelle IP-adresser',
'ipblocklist-submit'              => 'Søk',
'ipblocklist-localblock'          => 'Lokal blokkering',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|Annen blokkering|Andre blokkeringer}}',
'blocklistline'                   => '$1, $2 blokkerte $3 ($4)',
'infiniteblock'                   => 'uendelig',
'expiringblock'                   => 'utgår den $1 kl $2',
'anononlyblock'                   => 'kun uregistrerte',
'noautoblockblock'                => 'autoblokkering slått av',
'createaccountblock'              => 'kontooppretting blokkert',
'emailblock'                      => 'e-post blokkert',
'blocklist-nousertalk'            => 'kan ikke redigere sin egen diskusjonsside',
'ipblocklist-empty'               => 'Blokkeringslisten er tom.',
'ipblocklist-no-results'          => 'Den angitte IP-adressen eller brukeren er ikke blokkert.',
'blocklink'                       => 'blokker',
'unblocklink'                     => 'opphev blokkering',
'change-blocklink'                => 'endre blokkering',
'contribslink'                    => 'bidrag',
'autoblocker'                     => 'Du ble automatisk blokkert fordi du deler IP-adresse med «[[User:$1|$1]]». Grunnen som ble gitt til at «$1» ble blokkert var: «$2».',
'blocklogpage'                    => 'Blokkeringslogg',
'blocklog-showlog'                => 'Denne brukeren har blitt blokkert før.
Blokkeringsloggen vises nedenfor.',
'blocklog-showsuppresslog'        => 'Denne brukeren har tidligere blitt blokkert og skjult.
Skjulingsloggen vises nedenfor.',
'blocklogentry'                   => 'blokkerte [[$1]] med en varighet på $2 $3',
'reblock-logentry'                => 'endret blokkeringsinnstillinger for [[$1]] med en varighet på $2 $3',
'blocklogtext'                    => 'Dette er en logg som viser hvilke brukere som har blitt blokkert og avblokkert. Automatisk blokkerte IP-adresser vises ikke. Se [[Special:IPBlockList|blokkeringslisten]] for en liste over IP-adresser som er blokkert akkurat nå.',
'unblocklogentry'                 => 'opphevet blokkeringen av $1',
'block-log-flags-anononly'        => 'kun uregistrerte brukere',
'block-log-flags-nocreate'        => 'kontooppretting deaktivert',
'block-log-flags-noautoblock'     => 'autoblokkering slått av',
'block-log-flags-noemail'         => 'e-post blokkert',
'block-log-flags-nousertalk'      => 'kan ikke redigere egen diskusjonsside',
'block-log-flags-angry-autoblock' => 'utvidet autoblokkering aktivert',
'block-log-flags-hiddenname'      => 'brukernavn skjult',
'range_block_disabled'            => 'Muligheten til å blokkere flere IP-adresser om gangen er slått av.',
'ipb_expiry_invalid'              => 'Ugyldig utløpstid.',
'ipb_expiry_temp'                 => 'For å skjule brukernavnet må blokkeringen være permanent.',
'ipb_hide_invalid'                => 'Kan ikke skjule denne kontoen. Den kan ha for mange redigeringer.',
'ipb_already_blocked'             => '«$1» er allerede blokkert',
'ipb-needreblock'                 => '== Allerede blokkert ==
$1 er blokkert fra før. Vil du endre innstillingene?',
'ipb-otherblocks-header'          => '{{PLURAL:$1|Annen blokkering|Andre blokkeringer}}',
'ipb_cant_unblock'                => 'Feil: Blokk-ID $1 ikke funnet. Kan ha blitt avblokkert allerede.',
'ipb_blocked_as_range'            => 'Feil: IP-en $1 er ikke blokkert direkte, og kan ikke avblokkeres. Den er imidlertid blokkert som del av blokkeringa av IP-rangen $2, som kan avblokkeres.',
'ip_range_invalid'                => 'Ugyldig IP-rad.',
'ip_range_toolarge'               => 'Blokkering av IP-serier større enn /$1 er ikke tillatt.',
'blockme'                         => 'Blokker meg',
'proxyblocker'                    => 'Proxyblokker',
'proxyblocker-disabled'           => 'Denne funksjonen er slått av.',
'proxyblockreason'                => 'IP-adressen din ble blokkert fordi den er en åpen proxy. Kontakt internettleverandøren din eller teknisk støtte og informer dem om dette alvorlige sikkerhetsproblemet.',
'proxyblocksuccess'               => 'Utført.',
'sorbsreason'                     => 'Din IP-adresse angis som en åpen proxy i DNSBL-en brukt av {{SITENAME}}.',
'sorbs_create_account_reason'     => 'Din IP-adresse angis som en åpen proxy i DNSBL-en brukt av {{SITENAME}}. Du kan ikke opprette en konto',
'cant-block-while-blocked'        => 'Du kan ikke blokkere andre mens du selv er blokkert.',
'cant-see-hidden-user'            => 'Brukeren du prøver å blokkere har allerede blitt blokkert og skjult. Ettersom du ikke har rett til å skjule brukere (hideuser), kan du ikke se eller endre brukerens blokkering.',
'ipbblocked'                      => 'Du kan ikke blokkere eller avblokkere andre brukere, siden du selv er blokkert',
'ipbnounblockself'                => 'Du har ikke tillatelse til å avblokkere deg selv',

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
'lockdbsuccesstext'   => 'Databasen er låst.<br />Husk å [[Special:UnlockDB|låse den opp]] når du er ferdig med vedlikeholdet.',
'unlockdbsuccesstext' => 'Databasen er låst opp.',
'lockfilenotwritable' => 'Kan ikke skrive til databasen. For å låse eller åpne databasen, må denne kunne skrives til av tjeneren.',
'databasenotlocked'   => 'Databasen er ikke låst.',

# Move page
'move-page'                    => 'Flytt $1',
'move-page-legend'             => 'Flytt side',
'movepagetext'                 => "Når du bruker skjemaet nedenfor døper du om en side og flytter hele historikken til det nye navnet.
Den gamle tittelen blir en omdirigeringsside til den nye tittelen.
Du kan oppdatere omdirigeringer som peker til den opprinnelige tittelen automatisk.
Om du velger å ikke gjøre det, sjekk at flyttingen ikke fører til [[Special:DoubleRedirects|doble]] eller [[Special:BrokenRedirects|ødelagte omdirigeringer]].
Du er ansvarlig for at lenker fortsetter å peke til de sidene de er ment å peke til.

Legg merke til at siden '''ikke''' kan flyttes hvis det allerede finnes en side med den nye tittelen, med mindre den er tom eller er en omdirigeringsside uten historikk.
Det betyr at du kan flytte en side tilbake dit den kom fra hvis du gjør en feil, og du kan ikke overskrive eksisterende sider ved et uhell.

'''Advarsel!'''
Dette kan være en drastisk og uventet endring for en populær side;
vær sikker på at du forstår konsekvensene av dette før du fortsetter.",
'movepagetext-noredirectfixer' => "Skjemaet nedenfor vil gi en side ny tittel og flytte historikken dens til det nye navnet.
Den gamle tittelen vil bli en omdirigering til den nye.
Sjekk om det blir [[Special:DoubleRedirects|doble]] eller [[Special:BrokenRedirects|ødelagte omdirigeringer]].
Du er ansvarlig for å sjekke at lenker fortsetter å gå dit de skal.

Merk at sider '''ikke''' blir flyttet om det allerede finnes en side med den tittelen, med mindre siden er tom eller en omdirigering og ikke har noen redigeringshistorikk.
Dette betyr at du kan endre tittelen til en tittel siden hadde tidligere, og at du ikke kan skrive over en eksisterende side.

'''Advarsel!'''
Dette kan være en drastisk og uventen endring for en populær side;
vær sikker på at du forstår konsekvensene av dette før du fortsetter.",
'movepagetalktext'             => "Den tilhørende diskusjonssiden vil automatisk bli flyttet sammen med siden '''med mindre:'''
*Det allerede finnes en diskusjonsside som ikke er tom under det nye navnet, eller
*Du fjerner markeringen i boksen nedenfor.

I disse tilfellene er du nødt til å flytte eller flette siden manuelt, om ønskelig.",
'movearticle'                  => 'Flytt side:',
'moveuserpage-warning'         => "'''Advarsel:''' Du er i ferd med å flytte en brukerside. Merk at kun siden vil bli flyttet; brukernavnet vil ''ikke'' bli endret.",
'movenologin'                  => 'Ikke logget inn',
'movenologintext'              => 'Du må være registrert bruker og være [[Special:UserLogin|logget på]] for å flytte en side.',
'movenotallowed'               => 'Du har ikke tillatelse til å flytte sider.',
'movenotallowedfile'           => 'Du har ikke tillatelse til å flytte filer.',
'cant-move-user-page'          => 'Du har ikke tillatelse til å flytte brukersider (bortsett fra undersider).',
'cant-move-to-user-page'       => 'Du har ikke tillatelse til å flytte brukersider (bortsett fra undersider).',
'newtitle'                     => 'Til ny tittel:',
'move-watch'                   => 'Overvåk kilde- og målsiden',
'movepagebtn'                  => 'Flytt side',
'pagemovedsub'                 => 'Flytting gjennomført',
'movepage-moved'               => "'''«$1» ble flyttet til «$2»'''",
'movepage-moved-redirect'      => 'En omdirigering har blitt opprettet.',
'movepage-moved-noredirect'    => 'Det ble ikke opprettet en omdirigering.',
'articleexists'                => 'En side med det navnet finnes allerede eller det valgte navn er ugyldig.
Velg et annet navn.',
'cantmove-titleprotected'      => 'Du kan ikke flytte en side til dette navnet, fordi den nye tittelen er beskyttet fra opprettelse.',
'talkexists'                   => "'''Siden ble flyttet korrekt, men den tilhørende diskusjonssiden kunne ikke flyttes fordi det allerede finnes en under den nye tittelen.
Du er nødt til å flette dem manuelt.'''",
'movedto'                      => 'flyttet til',
'movetalk'                     => 'Flytt tilhørende diskusjonsside.',
'move-subpages'                => 'Flytt alle undersider (opp til $1)',
'move-talk-subpages'           => 'Flytt alle undersider av diskusjonssiden (opp til $1)',
'movepage-page-exists'         => 'Siden $1 finnes allerede og kan ikke overskrives automatisk.',
'movepage-page-moved'          => 'Siden $1 har blitt flyttet til $2.',
'movepage-page-unmoved'        => 'Siden $1 kunne ikke flyttes til $2.',
'movepage-max-pages'           => 'Grensen på {{PLURAL:$1|én side|$1 sider}} er nådd; ingen flere sider vil bli flyttet automatisk.',
'1movedto2'                    => 'flyttet [[$1]] til [[$2]]',
'1movedto2_redir'              => 'flyttet [[$1]] til [[$2]] over omdirigeringsside',
'move-redirect-suppressed'     => 'ingen omdirigering',
'movelogpage'                  => 'Flyttelogg',
'movelogpagetext'              => 'Her er ei liste over sider som har blitt flyttet.',
'movesubpage'                  => '{{PLURAL:$1|Underside|Undersider}}',
'movesubpagetext'              => 'Denne siden har {{PLURAL:$1|én underside|$1 undersider}} som vises nedenfor.',
'movenosubpage'                => 'Denne siden har ingen undersider.',
'movereason'                   => 'Årsak:',
'revertmove'                   => 'tilbakestill',
'delete_and_move'              => 'Slett og flytt',
'delete_and_move_text'         => '==Sletting nødvendig==
Målsiden «[[:$1]]» finnes allerede. Vil du slette den så denne siden kan flyttes dit?',
'delete_and_move_confirm'      => 'Ja, slett siden',
'delete_and_move_reason'       => 'Slettet grunnet flytting',
'selfmove'                     => 'Kilde- og destinasjonstittel er den samme; kan ikke flytte siden.',
'immobile-source-namespace'    => 'Kan ikke flytte sider i navnerommet «$1»',
'immobile-target-namespace'    => 'Kan ikke flytte sider til navnerommet «$1»',
'immobile-target-namespace-iw' => 'Du kan ikke flytte en side til et navn som er en interwikilenke.',
'immobile-source-page'         => 'Denne siden kan ikke flyttes.',
'immobile-target-page'         => 'Kan ikke flytte til det navnet.',
'imagenocrossnamespace'        => 'Kan ikke flytte filer til andre navnerom enn filnavnerommet',
'nonfile-cannot-move-to-file'  => 'Kan ikke flytte ikke-filer til filnavnerom',
'imagetypemismatch'            => 'Den nye filendelsen tilsvarer ikke filtypen',
'imageinvalidfilename'         => 'Målnavnet er ugyldig',
'fix-double-redirects'         => 'Oppdater omdirigeringer som fører til den gamle tittelen',
'move-leave-redirect'          => 'La det være igjen en omdirigering',
'protectedpagemovewarning'     => "'''Advarsel:''' Denne siden har blitt låst slik at kun brukere med administratorrettigheter kan flytte den.
Det siste loggelementet er oppgitt under som referanse:",
'semiprotectedpagemovewarning' => "'''Merk:''' Denne siden har blitt låst slik at kun registrerte brukere kan flytte den.
Det siste loggelementet er oppgitt under som referanse:",
'move-over-sharedrepo'         => '== Filen finnes ==
[[:$1]] finnes på en delt kilde. Dersom du flytter en fil til dette navnet, vil du overstyre den delte filen.',
'file-exists-sharedrepo'       => 'Det valgte filnavnet er allerede i bruk på en delt kilde.
Vennligst velg et annet navn.',

# Export
'export'            => 'Eksporter sider',
'exporttext'        => 'Du kan eksportere teksten og redigeringshistorikken for en bestemt side eller en gruppe sider i XML.
Dette kan senere importeres til en annen wiki som bruker MediaWiki ved hjelp av [[Special:Import|importsiden]].

For å eksportere sider, skriv inn titler i tekstboksen under, én tittel per linje, og velg om du vil ha kun nåværende versjon, eller alle versjoner i historikken.

Dersom du bare vil ha nåværende versjon, kan du også bruke en lenke, for eksempel [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] for siden «[[{{MediaWiki:Mainpage}}]]».',
'exportcuronly'     => 'Ta bare med den nåværende versjonen, ikke hele historikken.',
'exportnohistory'   => "----
'''Merk:''' Eksportering av hele historikken gjennom dette skjemaet har blitt slått av av ytelsesgrunner.",
'export-submit'     => 'Eksporter',
'export-addcattext' => 'Legg til sider fra kategori:',
'export-addcat'     => 'Legg til',
'export-addnstext'  => 'Legg til sider fra navnerom:',
'export-addns'      => 'Legg til',
'export-download'   => 'Lagre som fil',
'export-templates'  => 'Ta med maler',
'export-pagelinks'  => 'Inkluder lenkede sider med en dybde på:',

# Namespace 8 related
'allmessages'                   => 'Systemmeldinger',
'allmessagesname'               => 'Navn',
'allmessagesdefault'            => 'Standardtekst',
'allmessagescurrent'            => 'Nåværende tekst',
'allmessagestext'               => 'Dette er en liste over tilgjengelige systemmeldinger i MediaWiki-navnerommet.
Besøk [http://translatewiki.net translatewiki.net] om du ønsker å bidra med oversettelse av MediaWiki.',
'allmessagesnotsupportedDB'     => "''{{ns:special}}:Allmessages'' kan ikke brukes fordi '''\$wgUseDatabaseMessages''' er slått av.",
'allmessages-filter-legend'     => 'Filtrér',
'allmessages-filter'            => 'Filtrér etter tilpassningsgrad',
'allmessages-filter-unmodified' => 'Uendret',
'allmessages-filter-all'        => 'Alle',
'allmessages-filter-modified'   => 'Endret',
'allmessages-prefix'            => 'Filtrer etter prefiks:',
'allmessages-language'          => 'Språk:',
'allmessages-filter-submit'     => 'Gå',

# Thumbnails
'thumbnail-more'           => 'Forstørr',
'filemissing'              => 'Fila mangler',
'thumbnail_error'          => 'Feil under oppretting av miniatyrbilde: $1',
'djvu_page_error'          => 'DjVu-side ute av rekkevidde',
'djvu_no_xml'              => 'Klarte ikke å hente XML for DjVu-fil',
'thumbnail_invalid_params' => 'Ugyldige miniatyrparametere, eller PNG-fil med flere piksler enn 12,5 millioner.',
'thumbnail_dest_directory' => 'Klarte ikke å opprette målmappe',
'thumbnail_image-type'     => 'Bildetypen støttes ikke',
'thumbnail_gd-library'     => 'Ufullstendig konfigurering av GD library: mangler funksjonen $1',
'thumbnail_image-missing'  => 'Fila ser ut til å mangle: $1',

# Special:Import
'import'                     => 'Importer sider',
'importinterwiki'            => 'Transwiki-importering',
'import-interwiki-text'      => 'Velg en wiki og en side å importere. Revisjonsdatoer og bidragsyteres navn blir bevart. Alle transwiki-importeringer listes i [[Special:Log/import|importloggen]].',
'import-interwiki-source'    => 'Kildewiki/side:',
'import-interwiki-history'   => 'Kopier all historikk for denne siden',
'import-interwiki-templates' => 'Inkluder alle maler',
'import-interwiki-submit'    => 'Importer',
'import-interwiki-namespace' => 'Målnavnerom:',
'import-upload-filename'     => 'Filnavn:',
'import-comment'             => 'Kommentar:',
'importtext'                 => 'Importer fila fra kildewikien med [[Special:Export|eksporteringsverktøyet]].
Lagre den på din egen datamaskin og last den opp her.',
'importstart'                => 'Importerer sider&nbsp;…',
'import-revision-count'      => '({{PLURAL:$1|Én revisjon|$1 revisjoner}})',
'importnopages'              => 'Ingen sider å importere.',
'imported-log-entries'       => 'Importerte $1 {{PLURAL:$1|loggoppføring|loggoppføringer}}.',
'importfailed'               => 'Importering mislyktes: $1',
'importunknownsource'        => 'Ukjent importkildetype',
'importcantopen'             => 'Kunne ikke åpne importfil',
'importbadinterwiki'         => 'Ugyldig interwikilenke',
'importnotext'               => 'Tom eller ingen tekst',
'importsuccess'              => 'Importering ferdig.',
'importhistoryconflict'      => 'Motstridende revisjoner finnes (siden kan ha blitt importert tidligere)',
'importnosources'            => 'Ingen transwikiimportkilder er angitt, og direkte historikkimporteringer er slått av.',
'importnofile'               => 'Ingen importfil opplastet.',
'importuploaderrorsize'      => 'Importfilopplasting mislyktes. Filen er større enn tillatt opplastingsstørrelse.',
'importuploaderrorpartial'   => 'Importfilopplasting mislyktes. Filen ble kun delvis opplastet.',
'importuploaderrortemp'      => 'Importfilopplasting mislyktes. En midlertidig mappe mangler.',
'import-parse-failure'       => 'Tolkningsfeil ved XML-import',
'import-noarticle'           => 'Ingen side å importere!',
'import-nonewrevisions'      => 'Alle revisjoner var importert fra før.',
'xml-error-string'           => '$1 på linje $2, kolonne $3 (byte: $4): $5',
'import-upload'              => 'Last opp XML-data',
'import-token-mismatch'      => 'Sesjonsdata mistet. Venligst prøv igjen.',
'import-invalid-interwiki'   => 'Kan ikke importere fra angitt wiki.',

# Import log
'importlogpage'                    => 'Importlogg',
'importlogpagetext'                => 'Administrativ import av sider med redigeringshistorikk fra andre wikier.',
'import-logentry-upload'           => 'importerte [[$1]] ved opplasting',
'import-logentry-upload-detail'    => 'Importerte {{PLURAL:$1|én revisjon|$1 revisjoner}}',
'import-logentry-interwiki'        => 'transwikiimporterte $1',
'import-logentry-interwiki-detail' => '{{PLURAL:$1|Én revisjon|$1 revisjoner}} fra $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Din brukerside',
'tooltip-pt-anonuserpage'         => 'Brukersiden for IP-adressen du redigerer fra',
'tooltip-pt-mytalk'               => 'Din diskusjonsside',
'tooltip-pt-anontalk'             => 'Diskusjon om redigeringer fra denne IP-adressen',
'tooltip-pt-preferences'          => 'Dine innstillinger',
'tooltip-pt-watchlist'            => 'Liste over sider du overvåker for endringer.',
'tooltip-pt-mycontris'            => 'Liste over dine bidrag',
'tooltip-pt-login'                => 'Du oppfordres til å logge inn, men det er ikke obligatorisk',
'tooltip-pt-anonlogin'            => 'Du oppfordres til å logge inn, men det er ikke obligatorisk.',
'tooltip-pt-logout'               => 'Logg ut',
'tooltip-ca-talk'                 => 'Diskusjon om innholdssiden',
'tooltip-ca-edit'                 => 'Du kan redigere denne siden. Vennligst bruk forhåndsvisningsknappen før du lagrer.',
'tooltip-ca-addsection'           => 'Start et nytt avsnitt',
'tooltip-ca-viewsource'           => 'Denne siden er beskyttet.
Du kan se på kildeteksten',
'tooltip-ca-history'              => 'Tidligere revisjoner av denne siden',
'tooltip-ca-protect'              => 'Beskytt denne siden',
'tooltip-ca-unprotect'            => 'Fjern sidebeskyttelsen',
'tooltip-ca-delete'               => 'Slett denne siden',
'tooltip-ca-undelete'             => 'Gjenopprett redigerenge som ble gjort på denne siden før den ble slettet.',
'tooltip-ca-move'                 => 'Flytt denne siden',
'tooltip-ca-watch'                => 'Legg denne siden til overvåkningslisten din',
'tooltip-ca-unwatch'              => 'Fjern denne siden fra din overvåkningsliste',
'tooltip-search'                  => 'Søk i {{SITENAME}}',
'tooltip-search-go'               => 'Gå til en side med dette navnet om den finnes',
'tooltip-search-fulltext'         => 'Søk etter sider som innholder denne teksten',
'tooltip-p-logo'                  => 'Hovedside',
'tooltip-n-mainpage'              => 'Gå til hovedsiden',
'tooltip-n-mainpage-description'  => 'Gå til hovedsiden',
'tooltip-n-portal'                => 'Om prosjektet, hva du kan gjøre, hvor du kan finne ting',
'tooltip-n-currentevents'         => 'Finn bakgrunnsinformasjon om aktuelle hendelser',
'tooltip-n-recentchanges'         => 'Liste over siste endringer på wikien.',
'tooltip-n-randompage'            => 'Vis en tilfeldig side',
'tooltip-n-help'                  => 'Stedet for å få hjelp',
'tooltip-t-whatlinkshere'         => 'Liste over alle wikisider som lenker hit',
'tooltip-t-recentchangeslinked'   => 'Siste endringer i sider som blir lenket fra denne siden',
'tooltip-feed-rss'                => 'RSS-mating for denne siden',
'tooltip-feed-atom'               => 'Atom-mating for denne siden',
'tooltip-t-contributions'         => 'Vis liste over bidrag fra denne brukeren',
'tooltip-t-emailuser'             => 'Send en e-post til denne brukeren',
'tooltip-t-upload'                => 'Last opp filer',
'tooltip-t-specialpages'          => 'Liste over alle spesialsider',
'tooltip-t-print'                 => 'Utskriftsvennlig versjon av denne siden',
'tooltip-t-permalink'             => 'Permanent lenke til denne revisjonen av siden',
'tooltip-ca-nstab-main'           => 'Vis innholdssiden',
'tooltip-ca-nstab-user'           => 'Vis brukersiden',
'tooltip-ca-nstab-media'          => 'Vis mediasiden',
'tooltip-ca-nstab-special'        => 'Dette er en spesialside og kan ikke redigeres',
'tooltip-ca-nstab-project'        => 'Vis prosjektsiden',
'tooltip-ca-nstab-image'          => 'Vis filsiden',
'tooltip-ca-nstab-mediawiki'      => 'Vis systembeskjeden',
'tooltip-ca-nstab-template'       => 'Vis malen',
'tooltip-ca-nstab-help'           => 'Vis hjelpesiden',
'tooltip-ca-nstab-category'       => 'Vis kategorisiden',
'tooltip-minoredit'               => 'Merk dette som en mindre endring',
'tooltip-save'                    => 'Lagre endringene dine',
'tooltip-preview'                 => 'Forhåndsvis endringene dine, vennligst gjør dette før du lagrer!',
'tooltip-diff'                    => 'Vis hvilke endringer du har gjort på teksten',
'tooltip-compareselectedversions' => 'Se forskjellen mellom de to valgte revisjonene av denne siden',
'tooltip-watch'                   => 'Legg denne siden til overvåkningslisten din',
'tooltip-recreate'                => 'Gjenopprett siden til tross for at den har blitt slettet',
'tooltip-upload'                  => 'Start opplasting',
'tooltip-rollback'                => '«Tilbakestill»-knappen tilbakestiller den siste bidragsyterens endring(er) på denne siden med ett klikk',
'tooltip-undo'                    => '«Angre» tilbakestiller denne endringen og åpner redigeringsskjemaet i forhåndsvisningsmodus. Det tillater en å legge til en begrunnelse i redigeringsforklaringen.',
'tooltip-preferences-save'        => 'Lagre innstillinger',
'tooltip-summary'                 => 'Skriv et kort sammendrag',

# Stylesheets
'common.css'      => '/* CSS plassert i denne fila vil gjelde for alle utseender. */',
'standard.css'    => '/* CSS i denne fila vil gjelde alle som bruker drakta Standard */',
'nostalgia.css'   => '/* CSS i denne fila vil gjelde alle som bruker drakta Nostalgia */',
'cologneblue.css' => '/* CSS i denne fila vil gjelde alle som bruker drakta Kølnerblå */',
'monobook.css'    => '/* CSS i denne fila vil gjelde alle som bruker drakta Monobook */',
'myskin.css'      => '/* CSS i denne fila vil gjelde alle som bruker drakta MySkin */',
'chick.css'       => '/* CSS i denne fila vil gjelde alle som bruker drakta Chick */',
'simple.css'      => '/* CSS i denne fila vil gjelde alle som bruker drakta Simple */',
'modern.css'      => '/* CSS i denne fila vil gjelde alle som bruker drakta Modern */',

# Scripts
'common.js'      => '/* Javascript i denne fila vil gjelde for alle drakter. */',
'standard.js'    => '/* Javascript i denne fila vil gjelde for brukere av drakta Standard */',
'nostalgia.js'   => '/* Javascript i denne fila vil gjelde for brukere av drakta Nostalgia */',
'cologneblue.js' => '/* Javascript i denne fila vil gjelde for brukere av drakta Kølnerblå */',
'monobook.js'    => '/* Javascript i denne fila vil gjelde for brukere av drakta Monobook */',
'myskin.js'      => '/* Javascript i denne fila vil gjelde for brukere av drakta MySkin */',
'chick.js'       => '/* Javascript i denne fila vil gjelde for brukere av drakta Chick */',
'simple.js'      => '/* Javascript i denne fila vil gjelde for brukere av drakta Simple */',
'modern.js'      => '/* Javascript i denne fila vil gjelde for brukere av drakta Modern */',

# Metadata
'nodublincore'      => 'Dublin Core RDF-metadata er slått av på denne tjeneren.',
'nocreativecommons' => 'Create Commons RDF-metadata er slått av på denne tjeneren.',
'notacceptable'     => 'Tjeneren har ingen mulige måter å vise data i din nettleser.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Anonym bruker|Anonyme brukere}} av {{SITENAME}}',
'siteuser'         => '{{SITENAME}}-bruker $1',
'anonuser'         => '{{SITENAME}}s anonyme bruker $1',
'lastmodifiedatby' => 'Denne siden ble sist redigert $1 kl. $2 av $3.',
'othercontribs'    => 'Basert på arbeid av $1.',
'others'           => 'andre',
'siteusers'        => '{{SITENAME}}-{{PLURAL:$2|bruker|brukere}} $1',
'anonusers'        => '{{SITENAME}}s {{PLURAL:$2|anonyme bruker|anonyme brukere}} $1',
'creditspage'      => 'Sidekrediteringer',
'nocredits'        => 'Ingen krediteringer er tilgjengelig for denne siden.',

# Spam protection
'spamprotectiontitle' => 'Søppelpostfilter',
'spamprotectiontext'  => 'Siden du ønsket å lagre ble blokkert av spamfilteret.
Dette er sannsynligvis forårsaket av en lenke til et svartelistet eksternt nettsted.',
'spamprotectionmatch' => 'Følgende tekst er det som aktiverte spamfilteret: $1',
'spambot_username'    => 'MediaWikis spamopprydning',
'spam_reverting'      => 'Tilbakestiller til siste versjon uten lenke til $1',
'spam_blanking'       => 'Alle revisjoner inneholdt lenke til $1, tømmer siden',

# Info page
'infosubtitle'   => 'Sideinformasjon',
'numedits'       => 'Antall redigeringer (artikkel): $1',
'numtalkedits'   => 'Antall redigeringer (diskusjonsside): $1',
'numwatchers'    => 'Antall overvåkere: $1',
'numauthors'     => 'Antall forskjellige bidragsytere (artikkel): $1',
'numtalkauthors' => 'Antall forskjellige bidragsytere (diskusjonsside): $1',

# Skin names
'skinname-standard'    => 'Standard',
'skinname-nostalgia'   => 'Nostalgi',
'skinname-cologneblue' => 'Kølnerblå',
'skinname-monobook'    => 'Monobook',
'skinname-myskin'      => 'Eget utseende',
'skinname-simple'      => 'Enkel',
'skinname-modern'      => 'Moderne',

# Math options
'mw_math_png'    => 'Vis alltid som PNG',
'mw_math_simple' => 'HTML hvis veldig enkel, ellers PNG',
'mw_math_html'   => 'HTML hvis mulig, ellers PNG',
'mw_math_source' => 'Behold som TeX (for tekst-nettlesere)',
'mw_math_modern' => 'Anbefalt for moderne nettlesere',
'mw_math_mathml' => 'MathML hvis mulig',

# Math errors
'math_failure'          => 'Feil i matematikken',
'math_unknown_error'    => 'ukjent feil',
'math_unknown_function' => 'ukjent funksjon',
'math_lexing_error'     => 'lexerfeil',
'math_syntax_error'     => 'syntaksfeil',
'math_image_error'      => 'PNG-konversjon mislyktes; sjekk at latex og dvipng (eller dvips + gs + convert) er korrekt installert',
'math_bad_tmpdir'       => 'Kan ikke skrive til eller opprette midlertidig mappe',
'math_bad_output'       => 'Kan ikke skrive til eller opprette resultatmappe',
'math_notexvc'          => 'Mangler kjørbar texvc;
se math/README for oppsett.',

# Patrolling
'markaspatrolleddiff'                 => 'Godkjenn endringen',
'markaspatrolledtext'                 => 'Godkjenn denne siden',
'markedaspatrolled'                   => 'Merket som godkjent',
'markedaspatrolledtext'               => 'Den valgte revisjonen av [[:$1]] har blitt markert som patruljert.',
'rcpatroldisabled'                    => 'Siste endringer-patruljering er slått av',
'rcpatroldisabledtext'                => 'Siste endringer-patruljeringsfunksjonen er slått av.',
'markedaspatrollederror'              => 'Kan ikke merke som godkjent',
'markedaspatrollederrortext'          => 'Du må spesifisere en versjon å merke som godkjent.',
'markedaspatrollederror-noautopatrol' => 'Du kan ikke merke dine egne endringer som godkjente.',

# Patrol log
'patrol-log-page'      => 'Godkjenningslogg',
'patrol-log-header'    => 'Dette er en logg over patruljerte sideversjoner.',
'patrol-log-line'      => 'merket $1 av $2 godkjent $3',
'patrol-log-auto'      => '(automatisk)',
'patrol-log-diff'      => 'versjon $1',
'log-show-hide-patrol' => '$1 patruljeringslogg',

# Image deletion
'deletedrevision'                 => 'Slettet gammel revisjon $1.',
'filedeleteerror-short'           => 'Feil under filsletting: $1',
'filedeleteerror-long'            => 'Feil oppsto under filsletting:

$1',
'filedelete-missing'              => 'Filen «$1» kan ikke slettes fordi den ikke finnes.',
'filedelete-old-unregistered'     => 'Filrevisjonen «$1» finnes ikke i databasen.',
'filedelete-current-unregistered' => 'Filen «$1» finnes ikke i databasen.',
'filedelete-archive-read-only'    => 'Arkivmappa «$1» kan ikke skrives av tjeneren.',

# Browsing diffs
'previousdiff' => '← Eldre endring',
'nextdiff'     => 'Nyere endring →',

# Media information
'mediawarning'         => "'''Advarsel''': Denne fila kan inneholde farlig kode.
Ved å åpne den kan systemet ditt kompromitteres.",
'imagemaxsize'         => "Bildestørrelsesgrense:<br />''(for filbeskrivelsessider)''",
'thumbsize'            => 'Miniatyrbildestørrelse:',
'widthheightpage'      => '$1×$2, {{PLURAL:$3|én side|$3 sider}}',
'file-info'            => 'filstørrelse: $1, MIME-type: $2',
'file-info-size'       => '$1 × $2 piksler, filstørrelse: $3, MIME-type: $4',
'file-nohires'         => '<small>Ingen høyere oppløsning tilgjengelig.</small>',
'svg-long-desc'        => 'SVG-fil, standardoppløsning $1 × $2 piksler, filstørrelse: $3',
'show-big-image'       => 'Full oppløsning',
'show-big-image-thumb' => '<small>Størrelse på denne forhåndsvisningen: $1 × $2 piksler</small>',
'file-info-gif-looped' => 'gjentas',
'file-info-gif-frames' => '$1 {{PLURAL:$1|ramme|rammer}}',
'file-info-png-looped' => 'loopet',
'file-info-png-repeat' => 'avspilt $1 {{PLURAL:$1|gang|ganger}}',
'file-info-png-frames' => '$1 {{PLURAL:$1|bilde|bilder}}',

# Special:NewFiles
'newimages'             => 'Galleri over nye filer',
'imagelisttext'         => "Dete er en liste med '''$1''' {{PLURAL:$1|fil|filer}} sortert $2.",
'newimages-summary'     => 'Denne spesialsiden viser de sist opplastede filene.',
'newimages-legend'      => 'Filnavn',
'newimages-label'       => 'Filnavn (helt eller delvis):',
'showhidebots'          => '($1 roboter)',
'noimages'              => 'Ingenting å se.',
'ilsubmit'              => 'Søk',
'bydate'                => 'etter dato',
'sp-newimages-showfrom' => 'Vis nye filer fra og med $2 $1',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'hours-abbrev' => 't',

# Bad image list
'bad_image_list' => 'Formatet er som følger:

Kun listeelementer (linjer som starter med *) tas med.
Den første lenken på en linje må være en lenke til en dårlig fil.
Alle andre lenker på samme linje anses for å være unntak, altså sider der filen kan opptre.',

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => 'Denne filen inneholder tilleggsinformasjon, antagligvis lagt til av digitalkameraet eller skanneren brukt til å lage eller digitalisere det.
Hvis filen har blitt forandret fra utgangspunktet, kan enkelte detaljer være unøyaktige.',
'metadata-expand'   => 'Vis utvidede detaljer',
'metadata-collapse' => 'Skjul utvidede detaljer',
'metadata-fields'   => 'EXIF-metadatafelt listet i denne meldingen inkluderes på bildesiden mens metadatatabellen er slått sammen.
Andre vil skjules som standard.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
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
'exif-ycbcrsubsampling'            => 'Subsamplingsforhold mellom Y og C',
'exif-ycbcrpositioning'            => 'Y- og C-posisjonering',
'exif-xresolution'                 => 'Horisontal oppløsning',
'exif-yresolution'                 => 'Vertikal oppløsning',
'exif-resolutionunit'              => 'Enhet for X- og Y-oppløsning',
'exif-stripoffsets'                => 'Plassering for bildedata',
'exif-rowsperstrip'                => 'Antall rader per stripe',
'exif-stripbytecounts'             => 'Antall byte per kompresserte stripe',
'exif-jpeginterchangeformat'       => 'Offset til JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Byte med JPEG-data',
'exif-transferfunction'            => 'Overføringsfunksjon',
'exif-whitepoint'                  => 'Hvitpunktkromatisitet',
'exif-primarychromaticities'       => 'Primærfargenes renhet',
'exif-ycbcrcoefficients'           => 'Koeffisienter fr fargeromstransformasjonsmatrise',
'exif-referenceblackwhite'         => 'Par av svarte og hvite referanseverdier',
'exif-datetime'                    => 'Dato og tid for filendring',
'exif-imagedescription'            => 'Bildetittel',
'exif-make'                        => 'Kameraprodusent',
'exif-model'                       => 'Kameramodell',
'exif-software'                    => 'Programvare brukt',
'exif-artist'                      => 'Skaper',
'exif-copyright'                   => 'Opphavsbeskyttelse tilhører',
'exif-exifversion'                 => 'Exif-versjon',
'exif-flashpixversion'             => 'Støttet Flashpix-versjon',
'exif-colorspace'                  => 'Fargerom',
'exif-componentsconfiguration'     => 'Betydning av hver komponent',
'exif-compressedbitsperpixel'      => 'Bildekompresjonsmodus',
'exif-pixelydimension'             => 'Bildebredde',
'exif-pixelxdimension'             => 'Bildehøyde',
'exif-makernote'                   => 'Fabrikkmerknader',
'exif-usercomment'                 => 'Brukerkommentarer',
'exif-relatedsoundfile'            => 'Relatert lydfil',
'exif-datetimeoriginal'            => 'Dato og tid for datagenerering',
'exif-datetimedigitized'           => 'Dato og tid for digitalisering',
'exif-subsectime'                  => 'Endringstidspunkt, sekunddeler',
'exif-subsectimeoriginal'          => 'Eksponeringstidspunkt, sekunddeler',
'exif-subsectimedigitized'         => 'Digitaliseringstidspunkt, sekunddeler',
'exif-exposuretime'                => 'Eksponeringstid',
'exif-exposuretime-format'         => '$1 sek ($2)',
'exif-fnumber'                     => 'F-nummer',
'exif-exposureprogram'             => 'Eksponeringsprogram',
'exif-spectralsensitivity'         => 'Spektralsensitivitet',
'exif-isospeedratings'             => 'Filmhastighet (ISO)',
'exif-oecf'                        => 'Optoelektronisk konversjonsfaktor',
'exif-shutterspeedvalue'           => 'APEX lukkerhastighet',
'exif-aperturevalue'               => 'APEX blenderåpning',
'exif-brightnessvalue'             => 'APEX lysstyrke',
'exif-exposurebiasvalue'           => 'Eksponeringsbias',
'exif-maxaperturevalue'            => 'Maksimal blender',
'exif-subjectdistance'             => 'Avstand til subjekt',
'exif-meteringmode'                => 'Målingsmodus',
'exif-lightsource'                 => 'Lyskilde',
'exif-flash'                       => 'Blits',
'exif-focallength'                 => 'Linsens brennvidde',
'exif-subjectarea'                 => 'Motivområde',
'exif-flashenergy'                 => 'Blitsenergi',
'exif-spatialfrequencyresponse'    => 'Romslig frekvensrespons',
'exif-focalplanexresolution'       => 'Oppløsning i fokalplan X',
'exif-focalplaneyresolution'       => 'Oppløsning i fokalplan Y',
'exif-focalplaneresolutionunit'    => 'Enhet for oppløsning i fokalplan',
'exif-subjectlocation'             => 'Motivets beliggenhet',
'exif-exposureindex'               => 'Eksponeringsindeks',
'exif-sensingmethod'               => 'Avkjenningsmetode',
'exif-filesource'                  => 'Filkilde',
'exif-scenetype'                   => 'Scenetype',
'exif-cfapattern'                  => 'CFA-mønster',
'exif-customrendered'              => 'Tilpasset bildebehandling',
'exif-exposuremode'                => 'Eksponeringsmodus',
'exif-whitebalance'                => 'Hvit balanse',
'exif-digitalzoomratio'            => 'Digitalt zoomomfang',
'exif-focallengthin35mmfilm'       => 'Brennvidde på 35 mm-film',
'exif-scenecapturetype'            => 'Motivprogram',
'exif-gaincontrol'                 => 'Scenekontroll',
'exif-contrast'                    => 'Kontrast',
'exif-saturation'                  => 'Metning',
'exif-sharpness'                   => 'Skarphet',
'exif-devicesettingdescription'    => 'Beskrivelse av apparatets innstilling',
'exif-subjectdistancerange'        => 'Avstandsintervall til motiv',
'exif-imageuniqueid'               => 'Unik bilde-ID',
'exif-gpsversionid'                => 'Versjon for GPS-tagger',
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
'exif-gpsspeed'                    => 'GPS-mottakerens hastighet',
'exif-gpstrackref'                 => 'Referanse for bevegelsesretning',
'exif-gpstrack'                    => 'Bevegelsesretning',
'exif-gpsimgdirectionref'          => 'Referanse for bilderetning',
'exif-gpsimgdirection'             => 'Bilderetning',
'exif-gpsmapdatum'                 => 'Brukt geodetisk data',
'exif-gpsdestlatituderef'          => 'Referanse for målbreddegrad',
'exif-gpsdestlatitude'             => 'Målbreddegrad',
'exif-gpsdestlongituderef'         => 'Referanse for mållengdegrad',
'exif-gpsdestlongitude'            => 'Mållengdegrad',
'exif-gpsdestbearingref'           => 'Referanse for retning mot målet',
'exif-gpsdestbearing'              => 'Retning mot målet',
'exif-gpsdestdistanceref'          => 'Referanse for lengde til mål',
'exif-gpsdestdistance'             => 'Lengde til mål',
'exif-gpsprocessingmethod'         => 'Navn på GPS-prosesseringsmetode',
'exif-gpsareainformation'          => 'Navn på GPS-område',
'exif-gpsdatestamp'                => 'GPS-dato',
'exif-gpsdifferential'             => 'Differentiell GPS-korreksjon',
'exif-objectname'                  => 'Kort tittel',

# EXIF attributes
'exif-compression-1' => 'Ukomprimert',

'exif-unknowndate' => 'Ukjent dato',

'exif-orientation-1' => 'Normal',
'exif-orientation-2' => 'Snudd horisontalt',
'exif-orientation-3' => 'Rotert 180°',
'exif-orientation-4' => 'Snudd vertikalt',
'exif-orientation-5' => 'Rotated 90° CCW and flipped vertically

Rotert 90° mot klokka og vridd vertikalt',
'exif-orientation-6' => 'Rotert 90° med klokka',
'exif-orientation-7' => 'Rotert 90° med klokka og vridd vertikalt',
'exif-orientation-8' => 'Rotert 90° mot klokka',

'exif-planarconfiguration-1' => 'chunkformat',
'exif-planarconfiguration-2' => 'planærformat',

'exif-componentsconfiguration-0' => 'finnes ikke',

'exif-exposureprogram-0' => 'Ikke angitt',
'exif-exposureprogram-1' => 'Manuell',
'exif-exposureprogram-2' => 'Normalt program',
'exif-exposureprogram-3' => 'Blenderprioritet',
'exif-exposureprogram-4' => 'Slutterprioritet',
'exif-exposureprogram-5' => 'Kunstnerlig program (prioriterer skarphetsdyp)',
'exif-exposureprogram-6' => 'Bevegelsesprogram (prioriterer kortere sluttertid)',
'exif-exposureprogram-7' => 'Portrettmodus (for nærbilder med ufokusert bakgrunn)',
'exif-exposureprogram-8' => 'Landskapsmodus (for landskapsbilder med fokusert bakgrunn)',

'exif-subjectdistance-value' => '$1 meter',

'exif-meteringmode-0'   => 'Ukjent',
'exif-meteringmode-1'   => 'Gjennomsnitt',
'exif-meteringmode-2'   => 'Sentrumsveid gjennomsnitt',
'exif-meteringmode-3'   => 'Spot',
'exif-meteringmode-4'   => 'Multispot',
'exif-meteringmode-5'   => 'Mønster',
'exif-meteringmode-6'   => 'Delvis',
'exif-meteringmode-255' => 'Annet',

'exif-lightsource-0'   => 'Ukjent',
'exif-lightsource-1'   => 'Dagslys',
'exif-lightsource-2'   => 'Lysrør',
'exif-lightsource-3'   => 'Glødelampe',
'exif-lightsource-4'   => 'Blits',
'exif-lightsource-9'   => 'Fint vær',
'exif-lightsource-10'  => 'Overskyet',
'exif-lightsource-11'  => 'Skygge',
'exif-lightsource-12'  => 'Dagslyslysrør (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Dagshvitt lysrør (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Kaldhvitt lysrør (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Hvitt lysrør (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Standardlys A',
'exif-lightsource-18'  => 'Standardlys B',
'exif-lightsource-19'  => 'Standardlys C',
'exif-lightsource-24'  => 'ISO studiobelysning',
'exif-lightsource-255' => 'Annen lyskilde',

# Flash modes
'exif-flash-fired-0'    => 'Blitz ikke utløst',
'exif-flash-fired-1'    => 'Blitz utløst',
'exif-flash-return-0'   => 'ingen funksjon for oppdaing av strobelys',
'exif-flash-return-2'   => 'strobelys ikke oppdaget',
'exif-flash-return-3'   => 'strobelys oppdaget',
'exif-flash-mode-1'     => 'tvunget blitzutløsning',
'exif-flash-mode-2'     => 'tvunget blitzløs',
'exif-flash-mode-3'     => 'automatisk modus',
'exif-flash-function-1' => 'Ingen blitzfunksjon',
'exif-flash-redeye-1'   => 'røde-øyne-redusering',

'exif-focalplaneresolutionunit-2' => 'tommer',

'exif-sensingmethod-1' => 'Ikke angitt',
'exif-sensingmethod-2' => 'Énchipsfargesensor',
'exif-sensingmethod-3' => 'Tochipsfargesensor',
'exif-sensingmethod-4' => 'Trechipsfargesensor',
'exif-sensingmethod-5' => 'Fargesekvensiell områdesensor',
'exif-sensingmethod-7' => 'Trilineær sensor',
'exif-sensingmethod-8' => 'Fargesekvensiell linær sensor',

'exif-filesource-3' => 'Digitalt stillbildekamera',

'exif-scenetype-1' => 'Direktefotografert bilde',

'exif-customrendered-0' => 'Normal prosess',
'exif-customrendered-1' => 'Tilpasset prosess',

'exif-exposuremode-0' => 'Automatisk eksponering',
'exif-exposuremode-1' => 'Manuell eksponering',
'exif-exposuremode-2' => 'Automatisk alternativeksponering',

'exif-whitebalance-0' => 'Automatisk hvitbalanse',
'exif-whitebalance-1' => 'Manuell hvitbalanse',

'exif-scenecapturetype-0' => 'Standard',
'exif-scenecapturetype-1' => 'Landskap',
'exif-scenecapturetype-2' => 'Portrett',
'exif-scenecapturetype-3' => 'Nattscene',

'exif-gaincontrol-0' => 'Ingen',
'exif-gaincontrol-1' => 'Økning av lavnivåforsterkning',
'exif-gaincontrol-2' => 'Økning av høynivåforsterkning',
'exif-gaincontrol-3' => 'Senkning av lavnivåforsterkning',
'exif-gaincontrol-4' => 'Senkning av høynivåforsterkning',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Myk',
'exif-contrast-2' => 'Hard',

'exif-saturation-0' => 'Normal',
'exif-saturation-1' => 'Lav metningsgrad',
'exif-saturation-2' => 'Høy metningsgrad',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Myk',
'exif-sharpness-2' => 'Hard',

'exif-subjectdistancerange-0' => 'Ukjent',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Nærbilde',
'exif-subjectdistancerange-3' => 'Fjernbilde',

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

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilometer per time',
'exif-gpsspeed-m' => 'Miles per time',
'exif-gpsspeed-n' => 'Knop',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Sann retning',
'exif-gpsdirection-m' => 'Magnetisk retning',

# External editor support
'edit-externally'      => 'Rediger denne filen med et eksternt program',
'edit-externally-help' => '(Se [http://www.mediawiki.org/wiki/Manual:External_editors oppsettsinstruksjonene] for mer informasjon)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'alle',
'imagelistall'     => 'alle',
'watchlistall2'    => 'alle',
'namespacesall'    => 'alle',
'monthsall'        => 'alle',
'limitall'         => 'alle',

# E-mail address confirmation
'confirmemail'              => 'Bekreft e-postadresse',
'confirmemail_noemail'      => 'Du har ikke oppgitt en gyldig e-postadresse i [[Special:Preferences|innstillingene dine]].',
'confirmemail_text'         => 'Du må bekrefte e-postadressen din før du kan benytte deg av e-posttjenester på {{SITENAME}}. Trykk på knappen under for å sende en bekreftelsesmelding til e-postadressen din. Meldingen vil inneholde en lenke med en kode; følg lenken for å bekrefte at e-postadressen er gyldig.',
'confirmemail_pending'      => 'En bekreftelseskode har allerede blitt sendt til deg på e-post; om du nylig opprettet kontoen din, kan du ønske å vente noen minutter før du spør om ny kode.',
'confirmemail_send'         => 'Send en bekreftelseskode.',
'confirmemail_sent'         => 'Bekreftelsesmelding sendt.',
'confirmemail_oncreate'     => 'En bekreftelseskode ble sendt til din e-postadresse. Denne koden er ikke nødvendig for å logge inn, men er nødvendig for å slå på e-postbaserte tjenester i denne wikien.',
'confirmemail_sendfailed'   => '{{SITENAME}} klarte ikke å sende bekreftelseskode.
Sjekk e-postadressen for ugyldige tegn.

E-postsenderen ga følgende melding: $1',
'confirmemail_invalid'      => 'Ugyldig bekreftelseskode. Koden kan ha utløpt.',
'confirmemail_needlogin'    => 'Du må $1 for å bekrefte e-postadressen din.',
'confirmemail_success'      => 'Din e-postadresse er nå bekreftet. Du kan nå logge inn og nyte wikien.',
'confirmemail_loggedin'     => 'E-postadressen din er bekreftet.',
'confirmemail_error'        => 'Noe gikk galt under lagringen av din bekreftelse.',
'confirmemail_subject'      => 'Bekreftelsesmelding fra {{SITENAME}}',
'confirmemail_body'         => 'Noen, antageligvis deg, har registrert kontoen «$2» på {{SITENAME}}, fra IP-adressen $1.

For å bekrefte at denne kontoen tilhører deg og for å aktivere e-posttjenester på {{SITENAME}}, åpne følgende lenke i nettleseren din:

$3

Om du *ikke* registrerte kontoen, følg denne lenken for å avbryte bekreftelse av e-postadresse:

$5

Denne bekreftelseskoden utgår $4.',
'confirmemail_body_changed' => 'Noen, antageligvis deg, fra IP-adresse $1,
har endret e-postadressen til kontoen «$2» til denne adressen på {{SITENAME}}.

For å bekrefte at denne kontoen virkelig er din og for å reaktivere e-postegenskaper på {{SITENAME}}, åpne denne lenken i nettleseren din:

$3

Dersom denne kontoen *ikke* tilhører deg, følg denne lenken
for å avbryte e-postbekreftelsen:

$5

Denne bekreftelseskoden vil løpe ut $4.',
'confirmemail_body_set'     => 'Noen, trolig deg, har satt e-postadressen for kontoen «$2» til denne adressen på {{SITENAME}}, fra IP-adressen $1.

For å bekrefte at denne kontoen tilhører deg og for å slå på e-posttjenestene på {{SITENAME}}, åpne denne lenka i nettleseren din:

$3

Om kontoen *ikke* tilhører deg, følg denne lenka for å avbryte bekreftelsen:

$5

Denne bekreftelseskoden går ut på dato $4.',
'confirmemail_invalidated'  => 'Bekreftelse av e-postadresse avbrutt',
'invalidateemail'           => 'Avbryt bekreftelse av e-postadresse',

# Scary transclusion
'scarytranscludedisabled' => '[Interwiki-transkludering er slått av]',
'scarytranscludefailed'   => '[Malen kunne ikke hentes for $1]',
'scarytranscludetoolong'  => '[URL-en er for lang]',

# Trackbacks
'trackbackbox'      => 'Tilbakesporinger for denne siden:<br />
$1',
'trackbackremove'   => '([$1 Slett])',
'trackbacklink'     => 'Tilbakesporing',
'trackbackdeleteok' => 'Tilbakesporingen ble slettet.',

# Delete conflict
'deletedwhileediting' => "'''Advarsel:''' Denne siden har blitt slettet etter at du begynte å redigere den!",
'confirmrecreate'     => '«[[User:$1|$1]]» ([[User talk:$1|diskusjon]]) slettet siden etter at du begynte å redigere den, med begrunnelsen «$2». Vennligst bekreft at du vil gjenopprette siden.',
'recreate'            => 'Gjenopprett',

# action=purge
'confirm_purge_button' => 'OK',
'confirm-purge-top'    => "Vil du slette tjenerens mellomlagrede versjon (''cache'') av denne siden?",
'confirm-purge-bottom' => 'Rensing av en side sletter mellomlageret og tvinger frem den nyeste versjonen.',

# Multipage image navigation
'imgmultipageprev' => '← forrige side',
'imgmultipagenext' => 'neste side &rarr;',
'imgmultigo'       => 'Gå!',
'imgmultigoto'     => 'Gå til siden $1',

# Table pager
'ascending_abbrev'         => 'stig.',
'descending_abbrev'        => 'synk.',
'table_pager_next'         => 'Neste side',
'table_pager_prev'         => 'Forrige side',
'table_pager_first'        => 'Første side',
'table_pager_last'         => 'Siste side',
'table_pager_limit'        => 'Vis $1 elementer per side',
'table_pager_limit_label'  => 'Element per side:',
'table_pager_limit_submit' => 'Gå',
'table_pager_empty'        => 'Ingen resultater',

# Auto-summaries
'autosumm-blank'   => 'Tømmer siden',
'autosumm-replace' => 'Erstatter siden med «$1»',
'autoredircomment' => 'Omdirigerer til [[$1]]',
'autosumm-new'     => 'Ny side: $1',

# Live preview
'livepreview-loading' => 'Laster…',
'livepreview-ready'   => 'Laster&nbsp;… Klar!',
'livepreview-failed'  => 'Levende forhåndsvisning mislyktes. Prøv vanlig forhåndsvisning.',
'livepreview-error'   => 'Tilkobling mislyktes: $1 «$2»
Prøv vanlig forhåndsvisning.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Endringer nyere enn $1 {{PLURAL:$1|sekund|sekunder}} vises muligens ikke i denne listen.',
'lag-warn-high'   => 'På grunn av stor databaseforsinkelse, vil ikke endringer som er nyere enn $1 {{PLURAL:$1|sekund|sekunder}} vises i denne listen.',

# Watchlist editor
'watchlistedit-numitems'       => 'Overvåkningslisten din inneholder {{PLURAL:$1|én tittel|$1 titler}}, ikke inkludert diskusjonssider.',
'watchlistedit-noitems'        => 'Overvåkningslisten din inneholder ingen titler.',
'watchlistedit-normal-title'   => 'Rediger overvåkningsliste',
'watchlistedit-normal-legend'  => 'Fjern titler fra overvåkninglisten',
'watchlistedit-normal-explain' => 'Titler på overvåkningslisten din vises nedenfor.
For å fjerne en tittel, merk av boksen ved siden av den og klikk på «{{int:Watchlistedit-normal-submit}}».
Du kan også [[Special:Watchlist/raw|redigere den rå overvåkningslisten]].',
'watchlistedit-normal-submit'  => 'Fjern titler',
'watchlistedit-normal-done'    => '{{PLURAL:$1|Én tittel|$1 titler}} ble fjernet fra overvåkningslisten din:',
'watchlistedit-raw-title'      => 'Rediger rå overvåkningsliste',
'watchlistedit-raw-legend'     => 'Rediger rå overvåkningsliste',
'watchlistedit-raw-explain'    => 'Titler på overvåkningslisten din vises nedenunder, og kan redigeres ved å legge til eller fjerne fra listen;
én tittel per linje.
Når du er ferdig, trykk «{{int:Watchlistedit-raw-submit}}».
Du kan også [[Special:Watchlist/edit|bruke standardverktøyet]].',
'watchlistedit-raw-titles'     => 'Titler:',
'watchlistedit-raw-submit'     => 'Oppdater overvåkningsliste',
'watchlistedit-raw-done'       => 'Overvåkningslisten din er oppdatert.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|Én tittel|$1 titler}} ble lagt til:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|Én tittel|$1 titler}} ble fjernet:',

# Watchlist editing tools
'watchlisttools-view' => 'Vis relevante endringer',
'watchlisttools-edit' => 'Vis og rediger overvåkningsliste',
'watchlisttools-raw'  => 'Rediger rå overvåkningsliste',

# Hebrew month names
'hebrew-calendar-m1'      => 'Tisjri',
'hebrew-calendar-m2'      => 'Hesjván',
'hebrew-calendar-m3'      => 'Kislév',
'hebrew-calendar-m4'      => 'Tebét',
'hebrew-calendar-m5'      => 'Sjebát',
'hebrew-calendar-m6'      => 'Adár',
'hebrew-calendar-m6a'     => 'Adár I',
'hebrew-calendar-m6b'     => 'Adár II',
'hebrew-calendar-m7'      => 'Nisán',
'hebrew-calendar-m8'      => 'Ijár',
'hebrew-calendar-m9'      => 'Siván',
'hebrew-calendar-m10'     => 'Tammúz',
'hebrew-calendar-m11'     => 'Ab',
'hebrew-calendar-m12'     => 'Elúl',
'hebrew-calendar-m1-gen'  => 'Tisjri',
'hebrew-calendar-m2-gen'  => 'Hesjván',
'hebrew-calendar-m3-gen'  => 'Kislév',
'hebrew-calendar-m4-gen'  => 'Tebét',
'hebrew-calendar-m5-gen'  => 'Sjebát',
'hebrew-calendar-m6-gen'  => 'Adár',
'hebrew-calendar-m6a-gen' => 'Adár I',
'hebrew-calendar-m6b-gen' => 'Adár II',
'hebrew-calendar-m7-gen'  => 'Nisán',
'hebrew-calendar-m8-gen'  => 'Ijár',
'hebrew-calendar-m9-gen'  => 'Siván',
'hebrew-calendar-m10-gen' => 'Tammúz',
'hebrew-calendar-m11-gen' => 'Ab',
'hebrew-calendar-m12-gen' => 'Elúl',

# Core parser functions
'unknown_extension_tag' => 'Ukjent tilleggsmerking «$1»',
'duplicate-defaultsort' => 'Advarsel: Standardsorteringen «$2» tar over for den tidligere sorteringen «$1».',

# Special:Version
'version'                          => 'Versjon',
'version-extensions'               => 'Installerte utvidelser',
'version-specialpages'             => 'Spesialsider',
'version-parserhooks'              => 'Parsertillegg',
'version-variables'                => 'Variabler',
'version-skins'                    => 'Drakter',
'version-other'                    => 'Annet',
'version-mediahandlers'            => 'Mediahåndterere',
'version-hooks'                    => 'Haker',
'version-extension-functions'      => 'Tilleggsfunksjoner',
'version-parser-extensiontags'     => 'Tilleggstagger',
'version-parser-function-hooks'    => 'Parserfunksjoner',
'version-skin-extension-functions' => 'Skalltilleggsfunksjoner',
'version-hook-name'                => 'Navn',
'version-hook-subscribedby'        => 'Brukes av',
'version-version'                  => '(versjon $1)',
'version-license'                  => 'Lisens',
'version-poweredby-credits'        => "Denne wikien er drevet av '''[http://www.mediawiki.org/ MediaWiki]''', copyright © 2001-$1 $2.",
'version-poweredby-others'         => 'andre',
'version-license-info'             => 'MediaWiki er fri programvare; du kan redistribuere det og/eller modifisere det under betingelsene i GNU General Public License som publisert av Free Software Foundation; enten versjon 2 av lisensen, eller (etter eget valg) enhver senere versjon.

MediaWiki er distribuert i håp om at det vil være nyttig, men UTEN NOEN GARANTI; ikke engang implisitt garanti av SALGBARHET eller EGNETHET FOR ET BESTEMT FORMÅL. Se GNU General Public License for flere detaljer.

Du skal ha mottatt [{{SERVER}}{{SCRIPTPATH}}/COPYING en kopi av GNU General Public License] sammen med dette programmet; hvis ikke, skriv til Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA eller [http://www.gnu.org/licenses/old-licenses/gpl-2.0.html les det på nettet].',
'version-software'                 => 'Installert programvare',
'version-software-product'         => 'Produkt',
'version-software-version'         => 'Versjon',

# Special:FilePath
'filepath'         => 'Filsti',
'filepath-page'    => 'Fil:',
'filepath-submit'  => 'Gå',
'filepath-summary' => 'Denne spesialsiden gir den fullstendige stien for en fil. Bilder vises i full oppløsning; andre filtyper startes direkte i sine assosierte programmer.

	Skriv inn filnavnet uten «{{ns:file}}:»-prefikset.',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Søk etter duplikatfiler',
'fileduplicatesearch-summary'  => 'Søk etter duplikatfiler basert på dets hash-verdi.

Skriv inn filnavn uten «{{ns:file}}:»-prefikset.',
'fileduplicatesearch-legend'   => 'Søk etter en duplikatfil',
'fileduplicatesearch-filename' => 'Filnavn:',
'fileduplicatesearch-submit'   => 'Søk',
'fileduplicatesearch-info'     => '$1 × $2 piksler<br />Filstørrelse: $3<br />MIME-type: $4',
'fileduplicatesearch-result-1' => 'Det er ingen duplikater av «$1».',
'fileduplicatesearch-result-n' => 'Det er {{PLURAL:$2|ett duplikat|$2 duplikater}} av «$1».',

# Special:SpecialPages
'specialpages'                   => 'Spesialsider',
'specialpages-note'              => '----
* <strong class="mw-specialpagerestricted">Markerte spesialsider har begrenset tilgang.</strong>',
'specialpages-group-maintenance' => 'Vedlikeholdsrapporter',
'specialpages-group-other'       => 'Andre spesialsider',
'specialpages-group-login'       => 'Innlogging / registrering',
'specialpages-group-changes'     => 'Siste endringer og logger',
'specialpages-group-media'       => 'Mediarapporter og opplastinger',
'specialpages-group-users'       => 'Brukere og rettigheter',
'specialpages-group-highuse'     => 'Ofte brukte sider',
'specialpages-group-pages'       => 'Sidelister',
'specialpages-group-pagetools'   => 'Sideverktøy',
'specialpages-group-wiki'        => 'Informasjon og verktøy for wikien',
'specialpages-group-redirects'   => 'Omdirigerende spesialsider',
'specialpages-group-spam'        => 'Spamverktøy',

# Special:BlankPage
'blankpage'              => 'Tom side',
'intentionallyblankpage' => 'Denne siden er tom med vilje',

# External image whitelist
'external_image_whitelist' => '#La denne linja være som den er<pre>
#Skriv fragmenter av regulære uttrykk (delen som går mellom //) nedenfor
#Disse vil sjekkes mot adresser til bilder fra eksterne sider
#De som blir godkjent vil vises, ellers vil det gis en lenke til bildet
#Linjer som begynner med # anses som kommentarer
#Det skilles ikke mellom store og små bokstaver

#Skriv alle fragmenter av regulære uttrykk over denne lina. La denne linja være som den er</pre>',

# Special:Tags
'tags'                    => 'Gyldige endringstagger',
'tag-filter'              => 'Filter for [[Special:Tags|tagger]]:',
'tag-filter-submit'       => 'Filtrer',
'tags-title'              => 'Tagger',
'tags-intro'              => 'Denne siden lister opp taggene programvaren kan merke en endring med, og hva de betyr.',
'tags-tag'                => 'Taggnavn',
'tags-display-header'     => 'Utseende på endringslister',
'tags-description-header' => 'Fullstendig betydning',
'tags-hitcount-header'    => 'Taggede endringer',
'tags-edit'               => 'rediger',
'tags-hitcount'           => '{{PLURAL:$1|én endring|$1 endringer}}',

# Special:ComparePages
'comparepages'     => 'Sammenlign sider',
'compare-selector' => 'Sammenlign siderevisjoner',
'compare-page1'    => 'Side 1',
'compare-page2'    => 'Side 2',
'compare-rev1'     => 'Revisjon 1',
'compare-rev2'     => 'Revisjon 2',
'compare-submit'   => 'Sammenlign',

# Database error messages
'dberr-header'      => 'Wikien har et problem',
'dberr-problems'    => 'Siden har tekniske problemer.',
'dberr-again'       => 'Prøv å oppdatere siden om noen minutter.',
'dberr-info'        => '(Kan ikke kontakte databasetjeneren: $1)',
'dberr-usegoogle'   => 'Du kan prøve å søke via Google imens.',
'dberr-outofdate'   => 'Merk at deres indeks over våre sider kan være utdatert.',
'dberr-cachederror' => 'Følgende er en mellomlagret kopi av den etterspurte siden, og kan være foreldet.',

# HTML forms
'htmlform-invalid-input'       => 'Det er problemer med noen av inndatene dine',
'htmlform-select-badoption'    => 'Verdien du valgte er ikke et gyldig alternativ.',
'htmlform-int-invalid'         => 'Verdien du valgte er ikke et heltall.',
'htmlform-float-invalid'       => 'Verdien du valgte er ikke et tall.',
'htmlform-int-toolow'          => 'Verdien du valgte er mindre enn minimum på $1',
'htmlform-int-toohigh'         => 'Verdien du valgte er over det mulige $1',
'htmlform-required'            => 'Denne verdien er påkrevd',
'htmlform-submit'              => 'Lagre',
'htmlform-reset'               => 'Omgjør endringer',
'htmlform-selectorother-other' => 'Andre',

# SQLite database support
'sqlite-has-fts' => '$1 med støtte for fulltekstsøk',
'sqlite-no-fts'  => '$1 uten støtte for fulltekstsøk',

);

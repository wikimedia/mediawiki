<?php
/** Upper Sorbian (Hornjoserbsce)
 *
 * @addtogroup Language
 *
 * @author Dundak
 * @author Michawiki
 * @author G - ג
 * @author Nike
 */

$fallback = 'de';

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Specialnje',
	NS_MAIN             => '',
	NS_TALK             => 'Diskusija',
	NS_USER             => 'Wužiwar',
	NS_USER_TALK        => 'Diskusija_z_wužiwarjom',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_diskusija',
	NS_IMAGE            => 'Wobraz',
	NS_IMAGE_TALK       => 'Diskusija_k_wobrazej',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_diskusija',
	NS_TEMPLATE         => 'Předłoha',
	NS_TEMPLATE_TALK    => 'Diskusija_k_předłoze',
	NS_HELP             => 'Pomoc',
	NS_HELP_TALK        => 'Pomoc_diskusija',
	NS_CATEGORY         => 'Kategorija',
	NS_CATEGORY_TALK    => 'Diskusija_ke_kategoriji'
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( "Dwójne_daleposrědkowanja" ),
	'BrokenRedirects'           => array( "Skóncowane_daleposrědkowanja" ),
	'Disambiguations'           => array( "Rozjasnjenja_wjazmyslnosće" ),
	'Userlogin'                 => array( "Přizwjewić" ),
	'Userlogout'                => array( "Wotzjewić" ),
	'Preferences'               => array( "Nastajenja" ),
	'Watchlist'                 => array( "Wobkedźbowanki" ),
	'Recentchanges'             => array( "Aktualne_změny" ),
	'Upload'                    => array( "Nahraće" ),
	'Imagelist'                 => array( "Dataje" ),
	'Newimages'                 => array( "Nowe_dataje" ),
	'Listusers'                 => array( "Wužiwarjo" ),
	'Statistics'                => array( "Statistika" ),
	'Randompage'                => array( "Připadna_strona" ),
	'Lonelypages'               => array( "Wosyroćene_strony" ),
	'Uncategorizedpages'        => array( "Njekategorizowane_strony" ),
	'Uncategorizedcategories'   => array( "Njekategorizowane_kategorije" ),
	'Uncategorizedimages'       => array( "Njekategorizowane_dataje" ),
	'Uncategorizedtemplates'    => array( "Njekategorizowane_předłohi" ),
	'Unusedcategories'          => array( "Njewužiwane_kategorije" ),
	'Unusedimages'              => array( "Njewužiwane_dataje" ),
	'Wantedpages'               => array( "Požadane_strony" ),
	'Wantedcategories'          => array( "Požadane_kategorije" ),
	'Mostlinked'                => array( "Z_najwjace_stronami_zwjazane_strony" ),
	'Mostlinkedcategories'      => array( "Najhusćišo_wužiwane_kategorije" ),
	'Mostlinkedtemplates'       => array( "Najhusćišo_wužiwane_předłohi" ),
	'Mostcategories'            => array( "Strony_z_najwjace_kategorijemi" ),
	'Mostimages'                => array( "Z_najwjace_stronami_zwjazane_dataje" ),
	'Mostrevisions'             => array( "Strony_z_najwjace_wersijemi" ),
	'Fewestrevisions'           => array( "Strony_z_najmjenje_wersijemi" ),
	'Shortpages'                => array( "Najkrótše_strony" ),
	'Longpages'                 => array( "Najdlěše_strony" ),
	'Newpages'                  => array( "Nowe_strony" ),
	'Ancientpages'              => array( "Najstarše_strony" ),
	'Deadendpages'              => array( "Strony_bjez_wotkazow" ),
	'Protectedpages'            => array( "Škitane_strony" ),
	'Allpages'                  => array( "Wšě_strony" ),
	'Prefixindex'               => array( "Prefiksindeks" ),
	'Ipblocklist'               => array( "Blokowane_IP-adresy" ),
	'Specialpages'              => array( "Specialne_strony" ),
	'Contributions'             => array( "Přinoški" ),
	'Emailuser'                 => array( "E-Mejl" ),
	'Whatlinkshere'             => array( "Lisćina_wotkazow" ),
	'Recentchangeslinked'       => array( "Změny_zwjazanych_stronow" ),
	'Movepage'                  => array( "Přesunyć" ),
	'Blockme'                   => array( "Blokowanje_proksijow" ),
	'Booksources'               => array( "Pytanje_po_ISBN" ),
	'Categories'                => array( "Kategorije" ),
	'Export'                    => array( "Eksport" ),
	'Version'                   => array( "Wersija" ),
	'Allmessages'               => array( "MediaWiki-zdźělenki" ),
	'Log'                       => array( "Protokol" ),
	'Blockip'                   => array( "Blokować" ),
	'Undelete'                  => array( "Wobnowić" ),
	'Import'                    => array( "Import" ),
	'Lockdb'                    => array( "Datowu_banku_zamknyć" ),
	'Unlockdb'                  => array( "Datowu_banku_wotamknyć" ),
	'Userrights'                => array( "Prawa" ),
	'MIMEsearch'                => array( "Pytanje_po_MIME" ),
	'Unwatchedpages'            => array( "Njewobkedźbowane_strony" ),
	'Listredirects'             => array( "Daleposrědkowanja" ),
	'Revisiondelete'            => array( "Wušmórnjenje_wersijow" ),
	'Unusedtemplates'           => array( "Njewužiwane_předłohi" ),
	'Randomredirect'            => array( "Připadne_daleposrědkowanje" ),
	'Mypage'                    => array( "Moja_wužiwarska_strona" ),
	'Mytalk'                    => array( "Moja_diskusijna_strona" ),
	'Mycontributions'           => array( "Moje_přinoški" ),
	'Listadmins'                => array( "Administratorojo" ),
	'Search'                    => array( "Pytać" ),
	'Withoutinterwiki'          => array( "Falowace_mjezyrěčne_wotkazy" ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Wotkazy podšmórnić:',
'tog-highlightbroken'         => 'Wotkazy na prózdne strony wuzběhnyć',
'tog-justify'                 => 'Tekst w blokowej sadźbje',
'tog-hideminor'               => 'Snadne změny w aktualnych změnach schować',
'tog-extendwatchlist'         => 'Rozšěrjena lisćina wobkedźbowankow',
'tog-usenewrc'                => 'Rozšěrjena lisćina aktualnych změnow (trjeba JavaScript)',
'tog-numberheadings'          => 'Nadpisma awtomatisce čisłować',
'tog-showtoolbar'             => 'Gratowu lajstu pokazać (JavaScript)',
'tog-editondblclick'          => 'Strony z dwójnym kliknjenjom wobdźěłować (JavaScript)',
'tog-editsection'             => 'Wobdźěłowanje jednotliwych wotrězkow přez wotkazy [wobdźěłać] zmóžnić',
'tog-editsectiononrightclick' => 'Wobdźěłowanje jednotliwych wotrězkow přez kliknjenje z prawej tastu<br />na nadpisma wotrězkow zmóžnić (JavaScript)',
'tog-showtoc'                 => 'Zapis wobsaha pokazać (za strony z wjace hač 3 nadpismami)',
'tog-rememberpassword'        => 'Hesło na tutym ličaku składować',
'tog-editwidth'               => 'Wobdźěłanske polo ma połnu šěrokosć',
'tog-watchcreations'          => 'Strony, kotrež wutworjam, swojim wobkedźbowankam přidać',
'tog-watchdefault'            => 'Strony, kotrež wobdźěłuju, swojim wobkedźbowankam přidać',
'tog-watchmoves'              => 'Sam přesunjene strony wobkedźbowankam přidać',
'tog-watchdeletion'           => 'Sam wušmórnjene strony wobkedźbowankam přidać',
'tog-minordefault'            => 'Wšě změny zwoprědka jako snadne woznamjenić',
'tog-previewontop'            => 'Přehlad nad wobdźěłanskim polom pokazać',
'tog-previewonfirst'          => 'Do składowanja přeco přehlad pokazać',
'tog-nocache'                 => 'Pufrowanje stronow znjemóžnić',
'tog-enotifwatchlistpages'    => 'Mejlku pósłać, hdyž so strona, kotruž wobkedźbuju, změni',
'tog-enotifusertalkpages'     => 'Mejlku pósłać, hdyž so moja wužiwarska diskusijna strona změni',
'tog-enotifminoredits'        => 'Tež dla snadnych změnow mejlki pósłać',
'tog-enotifrevealaddr'        => 'Moju e-mejlowu adresu w e-mejlowych zdźělenkach wotkryć',
'tog-shownumberswatching'     => 'Ličbu wobkedźbowacych wužiwarjow pokazać',
'tog-fancysig'                => 'Hrube signatury (bjez awtomatiskeho wotkaza)',
'tog-externaleditor'          => 'Eksterny editor jako standard wužiwać',
'tog-externaldiff'            => 'Eksterny diff-program jako standard wužiwać',
'tog-showjumplinks'           => 'Wotkazy typa „dźi do” zmóžnić',
'tog-uselivepreview'          => 'Live-přehlad wužiwać (JavaScript) (eksperimentalnje)',
'tog-forceeditsummary'        => 'Mje skedźbnić, jeli zabudu zjeće',
'tog-watchlisthideown'        => 'Moje změny we wobkedźbowankach schować',
'tog-watchlisthidebots'       => 'Změny awtomatiskich programow (botow) we wobkedźbowankach schować',
'tog-watchlisthideminor'      => 'Snadne změny we wobkedźbowankach schować',
'tog-nolangconversion'        => 'Konwertowanje rěčnych wariantow znjemóžnić',
'tog-ccmeonemails'            => 'Kopije mejlkow dóstać, kiž druhim wužiwarjam pósćelu',
'tog-diffonly'                => 'Jenož rozdźěle pokazać (nic pak zbytny wobsah)',

'underline-always'  => 'přeco',
'underline-never'   => 'ženje',
'underline-default' => 'po standardźe wobhladowaka',

'skinpreview' => '(Přehlad)',

# Dates
'sunday'        => 'Njedźela',
'monday'        => 'Póndźela',
'tuesday'       => 'Wutora',
'wednesday'     => 'Srjeda',
'thursday'      => 'Štwórtk',
'friday'        => 'Pjatk',
'saturday'      => 'Sobota',
'sun'           => 'Nje',
'mon'           => 'Pón',
'tue'           => 'Wut',
'wed'           => 'Srj',
'thu'           => 'Štw',
'fri'           => 'Pja',
'sat'           => 'Sob',
'january'       => 'januar',
'february'      => 'februar',
'march'         => 'měrc',
'april'         => 'apryla',
'may_long'      => 'meja',
'june'          => 'junij',
'july'          => 'julij',
'august'        => 'awgust',
'september'     => 'september',
'october'       => 'oktober',
'november'      => 'nowember',
'december'      => 'december',
'january-gen'   => 'januara',
'february-gen'  => 'februara',
'march-gen'     => 'měrca',
'april-gen'     => 'apryla',
'may-gen'       => 'meje',
'june-gen'      => 'junija',
'july-gen'      => 'julija',
'august-gen'    => 'awgusta',
'september-gen' => 'septembra',
'october-gen'   => 'oktobra',
'november-gen'  => 'nowembra',
'december-gen'  => 'decembra',
'jan'           => 'jan',
'feb'           => 'feb',
'mar'           => 'měr',
'apr'           => 'apr',
'may'           => 'meje',
'jun'           => 'jun',
'jul'           => 'jul',
'aug'           => 'awg',
'sep'           => 'sep',
'oct'           => 'okt',
'nov'           => 'now',
'dec'           => 'dec',

# Bits of text used by many pages
'categories'            => '{{PLURAL:$1|Kategorija|Kategoriji|Kategorije|Kategorije}}',
'pagecategories'        => '{{PLURAL:$1|Kategorija|Kategoriji|Kategorije|Kategorije}}',
'category_header'       => 'Nastawki w kategoriji „$1”',
'subcategories'         => 'Podkategorije',
'category-media-header' => 'Dataje w kategoriji „$1”',
'category-empty'        => "''Tuta kategorija tuchwilu žane nastawki abo medije njewobsahuje.''",

'mainpagetext'      => '<big><b>MediaWiki bu wuspěšnje instalowany.</b></big>',
'mainpagedocfooter' => 'Prošu hlej [http://meta.wikimedia.org/wiki/Help:Contents dokumentaciju] za informacije wo wužiwanju softwary.

== Za nowačkow ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Wo nastajenjach]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]',

'about'          => 'Wo',
'article'        => 'Nastawk',
'newwindow'      => '(wočinja so w nowym woknje)',
'cancel'         => 'Přetorhnyć',
'qbfind'         => 'Namakać',
'qbbrowse'       => 'Přepytować',
'qbedit'         => 'wobdźěłać',
'qbpageoptions'  => 'stronu',
'qbpageinfo'     => 'Kontekst',
'qbmyoptions'    => 'Moje strony',
'qbspecialpages' => 'Specialne strony',
'moredotdotdot'  => 'Wjace…',
'mypage'         => 'Moja strona',
'mytalk'         => 'Moja diskusija',
'anontalk'       => 'Z tutej IP diskutować',
'navigation'     => 'Nawigacija',

# Metadata in edit box
'metadata_help' => 'Metadaty:',

'errorpagetitle'    => 'Zmylk',
'returnto'          => 'Wróćo k stronje $1.',
'tagline'           => 'z {{GRAMMAR:genitiw|{{SITENAME}}}}',
'help'              => 'Pomoc',
'search'            => 'Pytać',
'searchbutton'      => 'Pytać',
'go'                => 'Nastawk',
'searcharticle'     => 'Nastawk',
'history'           => 'stawizny',
'history_short'     => 'stawizny',
'updatedmarker'     => 'Změny z mojeho poslednjeho wopyta',
'info_short'        => 'Informacija',
'printableversion'  => 'Ćišćomna wersija',
'permalink'         => 'Trajny wotkaz',
'print'             => 'Ćišćeć',
'edit'              => 'wobdźěłać',
'editthispage'      => 'Stronu wobdźěłać',
'delete'            => 'Wušmórnyć',
'deletethispage'    => 'Stronu wušmórnyć',
'undelete_short'    => '{{PLURAL:$1|jednu wersiju|$1 wersiji|$1 wersije|$1 wersijow}} wobnowić',
'protect'           => 'Škitać',
'protect_change'    => 'Škit změnić',
'protectthispage'   => 'Stronu škitać',
'unprotect'         => 'Škit zběhnyć',
'unprotectthispage' => 'Škit strony zběhnyć',
'newpage'           => 'Nowa strona',
'talkpage'          => 'diskusija',
'talkpagelinktext'  => 'diskusija',
'specialpage'       => 'Specialna strona',
'personaltools'     => 'Wosobinske nastroje',
'postcomment'       => 'Komentar dodać',
'articlepage'       => 'Nastawk',
'talk'              => 'diskusija',
'views'             => 'Zwobraznjenja',
'toolbox'           => 'Nastroje',
'userpage'          => 'Wužiwarsku stronu pokazać',
'projectpage'       => 'Projektowu stronu pokazać',
'imagepage'         => 'Wobrazowu stronu pokazać',
'mediawikipage'     => 'Zdźělenku pokazać',
'templatepage'      => 'Předłohu pokazać',
'viewhelppage'      => 'Pomocnu stronu pokazać',
'categorypage'      => 'Kategoriju pokazać',
'viewtalkpage'      => 'Diskusiju pokazać',
'otherlanguages'    => 'W druhich rěčach',
'redirectedfrom'    => '(ze strony „$1” sposrědkowane)',
'redirectpagesub'   => 'Daleposrědkowanje',
'lastmodifiedat'    => 'Strona bu posledni raz dnja $1 w $2 hodź. změnjena.', # $1 date, $2 time
'viewcount'         => 'Strona bu {{PLURAL:$1|jónu|dwójce|$1 razy|$1 razow}} wopytana.',
'protectedpage'     => 'Škitana strona',
'jumpto'            => 'Dźi do:',
'jumptonavigation'  => 'Nawigacija',
'jumptosearch'      => 'Pytać',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Wo {{GRAMMAR:lokatiw|{{SITENAME}}}}',
'aboutpage'         => '{{ns:project}}:Wo {{GRAMMAR:lokatiw|{{SITENAME}}}}',
'bugreports'        => 'Zmylkowe wopisanja',
'bugreportspage'    => '{{ns:project}}:Zmylkowe wopisanja',
'copyright'         => 'Wobsah steji pod $1.',
'copyrightpagename' => '{{SITENAME}} awtorske prawa',
'copyrightpage'     => '{{ns:project}}:Awtorske prawa',
'currentevents'     => 'Aktualne podawki',
'currentevents-url' => 'Aktualne podawki',
'disclaimers'       => 'Licencne postajenja',
'disclaimerpage'    => '{{ns:project}}:Licencne postajenja',
'edithelp'          => 'Pomoc za wobdźěłowanje',
'edithelppage'      => '{{ns:help}}:Wobdźěłanje',
'faq'               => 'Husto stajene prašenja (FAQ)',
'faqpage'           => '{{ns:project}}:Husto stajene prašenja (FAQ)',
'helppage'          => '{{ns:project}}:Pomoc',
'mainpage'          => 'Hłowna strona',
'policy-url'        => '{{ns:project}}:Směrnicy',
'portal'            => 'Portal {{GRAMMAR:genitiw|{{SITENAME}}}}',
'privacy'           => 'Škit datow',
'privacypage'       => '{{ns:project}}:Škit datow',
'sitesupport'       => 'Dary',
'sitesupport-url'   => '{{ns:project}}:Darić',

'badaccess'        => 'Nimaš wotpowědne dowolnosće',
'badaccess-group0' => 'Nimaš wotpowědne dowolnosće za tutu akciju.',
'badaccess-group1' => 'Tuta akcija da so jenož wot wužiwarjow skupiny $1 wuwjesć.',
'badaccess-group2' => 'Tuta akcija da so jenož wot wužiwarjow skupin $1 wuwjesć.',
'badaccess-groups' => 'Tuta akcija da so jenož wot wužiwarjow skupin $1 wuwjesć.',

'versionrequired'     => 'Wersija $1 softwary MediaWiki trěbna',
'versionrequiredtext' => 'Wersija $1 softwary MediaWiki je trěbna, zo by so tuta strona wužiwać móhła. Hlej [[{{ns:special}}:Version]]',

'ok'                      => 'W porjadku',
'retrievedfrom'           => 'Z {{GRAMMAR:genitiw|$1}}',
'youhavenewmessages'      => 'Maš $1 ($2).',
'newmessageslink'         => 'nowe powěsće',
'newmessagesdifflink'     => 'poslednja změna',
'youhavenewmessagesmulti' => 'Maš nowe powěsće: $1',
'editsection'             => 'wobdźěłać',
'editold'                 => 'wobdźěłać',
'editsectionhint'         => 'Wotrězk wobdźěłać: $1',
'toc'                     => 'Wobsah',
'showtoc'                 => 'pokazać',
'hidetoc'                 => 'schować',
'thisisdeleted'           => '$1 pokazać abo wobnowić?',
'viewdeleted'             => '$1 pokazać?',
'restorelink'             => '{{PLURAL:$1|1 wušmórnjenu wersiju|$1 wušmórnjenej wersiji|$1 wušmórnjene wersije|$1 wušmórnjenych wersijow}}',
'feedlinks'               => 'Kanal:',
'feed-invalid'            => 'Njepłaćiwy typ abonementa.',
'site-rss-feed'           => '$1 RSS kanal',
'site-atom-feed'          => 'Atom-kanal za $1',
'page-rss-feed'           => 'RSS-kanal za „$1“',
'page-atom-feed'          => 'Atom-Kanal za „$1“',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Nastawk',
'nstab-user'      => 'Wužiwarska strona',
'nstab-media'     => 'Medije',
'nstab-special'   => 'Specialna strona',
'nstab-project'   => 'Projektowa strona',
'nstab-image'     => 'Dataja',
'nstab-mediawiki' => 'Zdźělenka',
'nstab-template'  => 'Předłoha',
'nstab-help'      => 'Pomoc',
'nstab-category'  => 'Kategorija',

# Main script and global functions
'nosuchaction'      => 'Žana tajka akcija',
'nosuchactiontext'  => 'Akcija podata z URL njebu wot wikija připóznata.',
'nosuchspecialpage' => 'Tuta specialna strona njeeksistuje.',
'nospecialpagetext' => 'Tuta specialna strona w tutym wikiju njeeksistuje; lisćina płaćiwych specialnych stronow hodźi so pod [[{{ns:special}}:Specialpages]] namakać.',

# General errors
'error'                => 'Zmylk',
'databaseerror'        => 'Zmylk w datowej bance',
'dberrortext'          => 'Syntaktiski zmylk při wotprašowanju datoweje banki.
To móhło bug w programje być. Poslednje spytane wotprašenje w datowej bance běše:
<blockquote><tt>$1</tt></blockquote>
z funkcije „<tt>$2</tt>”.
MySQL wróći zmylk „<tt>$3: $4</tt>”.',
'dberrortextcl'        => 'Syntaktiski zmylk je we wotprašowanju datoweje banki wustupił.
Poslednje wotprašenje w datowej bance běše:
„$1”
z funkcije „$2”.
MySQL wróći zmylk „$3: $4”.',
'noconnect'            => 'Wodaj! Wiki ma techniske problemy a njemóže ze serwerom datoweje banki zwjazać.
<br />
$1',
'nodb'                 => 'Datowa banka $1 njeda so wubrać',
'cachederror'          => 'Naslědne je kopija z pufrowaka a njemóhło hižo aktualne być.',
'laggedslavemode'      => 'Kedźbu: Je móžno, zo strona žane zaktualizowanja njewobsahuje.',
'readonly'             => 'Datowa banka je zawrjena',
'enterlockreason'      => 'Zapodaj přičinu za zawrjenje a přibližny čas, hdy budźe zawrjenje zběhnjene',
'readonlytext'         => 'Datowa banka je tuchwilu za nowe zapiski a druhe změny zawrjena, najskerje wothladowanskich dźěłow dla; po jich zakónčenju budźe wšitko zaso normalne.

Administrator, kiž je datowu banku zawrěł, je jako přičinu podał: $1',
'missingarticle'       => 'Datowa banka njeje tekst strony, kotraž ma mjeno „$1”, namakała, byrnjež jón poprawom namakać dyrbjała.

To so zwjetša přez zestarjeny wotkaz na wušmórnjenu stronu zawinuje.

Jeli tomu tak njeje, sy snano zmylk w softwarje namakał. Prošu informuj administratora wo tym a zdźěl jemu wužity URL.',
'readonly_lag'         => 'Datowa banka bu awtomatisce zawrjena, mjeztym zo pospytuja wotwisne serwery datowych bankow  hłowny serwer docpěć',
'internalerror'        => 'Znutřkowny zmylk',
'internalerror_info'   => 'Znutřkowny zmylk: $1',
'filecopyerror'        => 'Njebě móžno dataju „$1” k „$2” kopěrować.',
'filerenameerror'      => 'Njebě móžno dataju „$1” na „$2” přemjenować.',
'filedeleteerror'      => 'Njebě móžno dataju „$1” wušmórnyć.',
'directorycreateerror' => 'Zapis „$1“ njeda so wutworić.',
'filenotfound'         => 'Njebě móžno dataju „$1” namakać.',
'fileexistserror'      => 'Njebě móžno do dataje „$1“ pisać, dokelž tuta dataja hižo eksistuje.',
'unexpected'           => 'Njewočakowana hódnota: "$1"="$2".',
'formerror'            => 'Zmylk: njeje móžno formular wotesłać',
'badarticleerror'      => 'Tuta akcija njeda so na tutej stronje wuwjesć.',
'cannotdelete'         => 'Njeje móžno podatu stronu abo dataju wušmórnyć. (Po zdaću je to hižo něchtó druhi činił.)',
'badtitle'             => 'Wopačny titul',
'badtitletext'         => 'Požadane mjeno strony běše njepłaćiwy, prózdny abo njekorektny titul z mjezyrěcneho abo interwikijoweho wotkaza. Snano wobsahuje jedne znamješko abo wjacore znamješka, kotrež w titulach dowolene njejsu.',
'perfdisabled'         => 'Wodaj! Tuta funkcija bu nachwilnje znjemóžnjena, dokelž datowu banku tak spomaluje, zo hižo nichtó wiki wužiwać njemóže.',
'perfcached'           => 'Sćěhowace daty z pufrowaka pochadźeja a snano cyle aktualne njejsu.',
'perfcachedts'         => 'Sćěhowace daty su z pufrowaka a buchu $1 posledni raz zaktualizowane.',
'querypage-no-updates' => "'''Aktualizacija za tutu stronu je tuchwilu znjemóžnjena. Daty so hač na dalše njewobnowjeja.'''",
'wrong_wfQuery_params' => 'Njeprawe parametry za wfQuery()

Funkcija: $1

Wotprašenje: $2',
'viewsource'           => 'Žórło wobhladać',
'viewsourcefor'        => 'za $1',
'actionthrottled'      => 'Akcije wobmjezowane',
'actionthrottledtext'  => 'Jako připrawa přećiwo spamej, je častosć wuwjedźenja tuteje akcije w krótkej dobje wobmjezowana a ty sy tutón limit překročił. Prošu spytaj za něšto mjeńšiny hišće raz.',
'protectedpagetext'    => 'Strona je přećiwo wobdźěłowanju škitana.',
'viewsourcetext'       => 'Móžeš pak jeje žórło wobhladać a jo kopěrować:',
'protectedinterface'   => 'Tuta strona skići tekst za rěčny zwjerch a je škitana zo by so znjewužiwanju zadźěwało.',
'editinginterface'     => '<b>Kedźbu:</b> Wobdźěłuješ stronu, kotraž wobsahuje tekst za rěčny zwjerch. Změny wuskutkuja so bjezposrědnje za wšěch druhich wužiwarjow tutoho rěčneho zwjercha.',
'sqlhidden'            => '(SQL wotprašenje schowane)',
'cascadeprotected'     => 'Strona je za wobdźěłowanje zawrjena, dokelž je w {{PLURAL:$1|slědowacej stronje|slědowacymaj stronomaj|slědowacych stronach}} zapřijata, {{PLURAL:$1|kotraž je|kotrejž stej|kotrež su}} přez kaskadowu opciju {{PLURAL:$1|škitana|škitanej|škitane}}:
$2',
'namespaceprotected'   => "Nimaš dowolnosć, zo by stronu w mjenowym rumje '''$1''' wobdźěłał.",
'customcssjsprotected' => 'Nimaš prawo, zo by tutu stronu wobdźěłał, dokelž wosobinske nastajenja druheho wužiwarja wobsahuje.',
'ns-specialprotected'  => 'Strony w mjenowym rumje {{ns:special}} njedadźa so wobdźěłać.',

# Login and logout pages
'logouttitle'                => 'Wotzjewjenje',
'logouttext'                 => '<strong>Sy nětko wotzjewjeny.</strong><br />
Móžeš {{GRAMMAR:akuzatiw|{{SITENAME}}}} nětko anonymnje dale wužiwać abo so ze samsnym abo druhim wužiwarskim mjenom zaso přizjewić. Wobkedźbuj zo so někotre strony dale jewja kaž by hišće přizjewjeny był doniž pufrowak swojeho wobhladowaka njewuprózdnješ.',
'welcomecreation'            => '== Witaj, $1! ==

Twoje konto bu wutworjene. Njezabudź swoje nastajenja za {{GRAMMAR:akuzatiw|{{SITENAME}}}} změnić.',
'loginpagetitle'             => 'Přizjewjenje',
'yourname'                   => 'Wužiwarske mjeno',
'yourpassword'               => 'Hesło',
'yourpasswordagain'          => 'Hesło znowa zapodać',
'remembermypassword'         => 'Hesło na tutym ličaku sej spomjatkować',
'yourdomainname'             => 'Twoja domejna',
'externaldberror'            => 'Běše pak eksterny zmylk awtentifikacije datoweje banki, pak njesměš swoje eksterne konto aktualizować.',
'loginproblem'               => '<b>Běše problem z přizjewjenjom.</b><br />

Prošu spytaj hišće raz!',
'login'                      => 'Přizjewić',
'loginprompt'                => 'Zo by so pola {{GRAMMAR:genitiw|{{SITENAME}}}} přizjewić móhł, dyrbja so placki (cookies) zmóžnić.',
'userlogin'                  => 'Konto wutworić abo so přizjewić',
'logout'                     => 'Wotzjewić',
'userlogout'                 => 'Wotzjewić',
'notloggedin'                => 'Njepřizjewjeny',
'nologin'                    => 'Nimaš žane konto? $1.',
'nologinlink'                => 'Tu móžeš wužiwarske konto wutworić',
'createaccount'              => 'Wužiwarske konto wutworić',
'gotaccount'                 => 'Maš hižo wužiwarske konto? $1.',
'gotaccountlink'             => 'Přizjewić',
'createaccountmail'          => 'z mejlku',
'badretype'                  => 'Hesle, kotrejž sy zapodał, so njekryjetej.',
'userexists'                 => 'Wužiwarske mjeno, kotrež sy wubrał, so hižo wužiwa. Prošu wubjer druhe mjeno.',
'youremail'                  => 'E-mejl *:',
'username'                   => 'Wužiwarske mjeno:',
'uid'                        => 'ID wužiwarja:',
'yourrealname'               => 'Woprawdźite mjeno *',
'yourlanguage'               => 'Rěč:',
'yourvariant'                => 'Warianta',
'yournick'                   => 'Přimjeno:',
'badsig'                     => 'Njepłaćiwa signatura, prošu HTML přepruwować.',
'badsiglength'               => 'Podpis smě maksimalnje $1 znamješkow dołhi być.',
'email'                      => 'E-mejl',
'prefs-help-realname'        => '* Woprawdźite mjeno (opcionalne): jeli so rozsudźiš to zapodać, budźe to so wužiwać, zo by tebi woprawnjenje za twoje dźěło dało.',
'loginerror'                 => 'Zmylk při přizjewjenju',
'prefs-help-email'           => '* E-mejl (opcionalny): Zmóžnja druhim će přez twoju wužiwarsku abo diskusijnu stronu kontaktować, bjeztoho zo by swoju identitu wotkryć dyrbjał. Jeli sy swoje hesło zabył, budźe móžno, ći nowe hesło připósłać.',
'prefs-help-email-required'  => 'Je płaćiwa emejlowa adresa trjeba.',
'nocookiesnew'               => 'Wužiwarske konto bu wutworjene, njejsy pak přizjewjeny. {{SITENAME}} wužiwa placki (cookies), zo bychu so wužiwarjo přizjewili. Sy placki znjemóžnił. Prošu zmóžń je a přizjew so potom ze swojim nowym wužiwarskim mjenom a hesłom.',
'nocookieslogin'             => '{{SITENAME}} wužiwa placki (cookies) za přizjewjenje wužiwarjow wužiwa. Sy placki znjemóžnił. Prošu zmóžń je a spytaj hišće raz.',
'noname'                     => 'Njejsy płaćiwe wužiwarske mjeno podał.',
'loginsuccesstitle'          => 'Přizjewjenje wuspěšne',
'loginsuccess'               => '<b>Sy nětko jako „$1” w {{GRAMMAR:lokatiw|{{SITENAME}}}} přizjewjeny.</b>',
'nosuchuser'                 => 'Njeje wužiwar z mjenom „$1”. Přepruwuj prawopis abo wutwor nowe konto.',
'nosuchusershort'            => 'Wužiwarske mjeno „$1” njeeksistuje. Prošu přepruwuj prawopis.',
'nouserspecified'            => 'Dyrbiš wužiwarske mjeno podać',
'wrongpassword'              => 'Hesło, kotrež sy zapodał, je wopačne. Prošu spytaj hišće raz.',
'wrongpasswordempty'         => 'Hesło, kotrež sy zapodał, běše prózdne. Prošu spytaj hišće raz.',
'passwordtooshort'           => 'Hesło je překrótke. Dyrbi znajmjeńša $1 {{PLURAL:$1|znamješko|znamješce|znamješka|znamješkow}} měć.',
'mailmypassword'             => 'Pósćelće mi nowe hesło',
'passwordremindertitle'      => 'Skedźbnjenje na hesło z {{GRAMMAR:genitiw|{{SITENAME}}}}',
'passwordremindertext'       => 'Něchtó (najskerje ty, z IP-adresu $1) je wo nowe hesło za přizjewjenje pola {{GRAMMAR:genitiw|{{SITENAME}}}} ($4) prosył. Hesło za wužiwarja „$2” je nětko $3.
Ty měł so nětko přizjewić a swoje hesło změnić.

Jeli něchto druhi hač ty wo nowe hesło prosył, abo sy so zaso na njo dopomnił a hižo nochceš je změnić, móžeš tutu powěsć ignorować a swoje stare hesło dale wužiwać.',
'noemail'                    => 'Za wužiwarja $1 žana e-mejlowa adresa podata njeje.',
'passwordsent'               => 'Nowe hesło bu na e-mejlowu adresu zregistrowanu za wužiwarja „$1” pósłane.
Prošu přizjew so znowa, po tym zo sy je přijał.',
'blocked-mailpassword'       => 'Twoja IP-adresa bu blokowana; tohodla njeje dowolene, nowe hesło požadać, zo by so znjewužiwanju zadźěwało.',
'eauthentsent'               => 'Wobkrućenska mejlka bu na naspomnjenu e-mejlowu adresu pósłana.
Prjedy hač so druha mejlka ke kontu pósćele, dyrbiš so po instrukcijach w mejlce měć, zo by wobkrućił, zo konto je woprawdźe twoje.',
'throttled-mailpassword'     => 'Bu hižo nowe hesło znutřka {{Plural:$1|poslednjeje hodźiny|poslednjeju hodźinow|poslednich hodźin|poslednich hodźin}} pósłane. Zo by znjewužiwanju zadźěwało, so jenož jedne hesło na {{Plural:$1|hodźinu|hodźinje|hodźiny|hodźinow}} pósćele.',
'mailerror'                  => 'Zmylk při słanju mejlki: $1',
'acct_creation_throttle_hit' => 'Wodaj, sy hižo $1 {{PLURAL:$1|konto|kontaj|konty|kontow}} wutworił. Njemóžeš dalše wutworić.',
'emailauthenticated'         => 'Twoja e-mejlowa adresa bu $1 wobkrućena.',
'emailnotauthenticated'      => 'Twoja e-mejlowa adresa hišće wobkrućena <strong>njeje</strong>. Žane mejlki za jednu z sćěhowacych funkcijow pósłane njebudu.',
'noemailprefs'               => 'Podaj e-mejlowu adresu za tute funkcije, zo bychu fungowali.',
'emailconfirmlink'           => 'Wobkruć swoju e-mejlowu adresu',
'invalidemailaddress'        => 'E-mejlowa adresa njeda so akceptować, dokelž ma po zdaću njepłaćiwy
format. Prošu zapodaj płaćiwu adresu abo wuprózdń polo.',
'accountcreated'             => 'Wužiwarske konto wutworjene',
'accountcreatedtext'         => 'Wužiwarske konto za $1 bu wutworjene.',
'createaccount-title'        => 'Wutworjenje wužiwarskeho konta za {{SITENAME}}',
'createaccount-text'         => 'Něchtó ($1) je wužiwarske konto za "$2" na {{SITENAME}} ($4) wutworił. Hesło za "$2" je "$3". Ty měł so nětko přizjewić a swoje hesło změnić.

Móžeš tutu zdźělenku ignorować, jeli so wužiwarske konto ze zmylkom wutworiło.',
'loginlanguagelabel'         => 'Rěč: $1',

# Password reset dialog
'resetpass'               => 'Hesło za wužiwarske konto wróćo stajić',
'resetpass_announce'      => 'Sy so z nachwilnym e-mejlowanym hesłom přizjewił. Zo by přizjewjenje zakónčił, dyrbiš nětko nowe hesło postajić.',
'resetpass_text'          => '<!-- Tu tekst zasunyć -->',
'resetpass_header'        => 'Hesło wróćo stajić',
'resetpass_submit'        => 'Hesło posrědkować a so přizjewić',
'resetpass_success'       => 'Twoje hesło bu wuspěšnje změnjene! Nětko přizjewjenje běži...',
'resetpass_bad_temporary' => 'Njepłaćiwe nachwilne hesło. Snano sy swoje hesło hižo wuspěšnje změnił abo nowe nachwilne hesło požadał.',
'resetpass_forbidden'     => 'Hesła njehodźa so w tutym wikiju změnić.',
'resetpass_missing'       => 'Prózdny formular.',

# Edit page toolbar
'bold_sample'     => 'Tučny tekst',
'bold_tip'        => 'Tučny tekst',
'italic_sample'   => 'Kursiwny tekst',
'italic_tip'      => 'Kursiwny tekst',
'link_sample'     => 'Mjeno wotkaza',
'link_tip'        => 'Znutřkowny wotkaz',
'extlink_sample'  => 'http://www.přikład.de Mjeno wotkaza',
'extlink_tip'     => 'Zwonkowny wotkaz (pomysli sej na prefiks http://)',
'headline_sample' => 'Nadpismo',
'headline_tip'    => 'Nadpismo runiny 2',
'math_sample'     => 'Zasuń tu formulu',
'math_tip'        => 'Matematiska formula (LaTeX)',
'nowiki_sample'   => 'Zasuń tu njeformatowany tekst',
'nowiki_tip'      => 'Wiki-syntaksu ignorować',
'image_sample'    => 'Přikład.jpg',
'image_tip'       => 'Zasadźeny wobraz',
'media_sample'    => 'Přikład.ogg',
'media_tip'       => 'Wotkaz k medijowej dataji',
'sig_tip'         => 'Twoja signatura z časowym kołkom',
'hr_tip'          => 'Wodoruna linija (zrědka wužiwać!)',

# Edit pages
'summary'                   => 'Zjeće',
'subject'                   => 'Tema/Nadpismo',
'minoredit'                 => 'Snadna změna',
'watchthis'                 => 'Stronu wobkedźbować',
'savearticle'               => 'Składować',
'preview'                   => 'Přehlad',
'showpreview'               => 'Přehlad pokazać',
'showlivepreview'           => 'Hnydomny přehlad',
'showdiff'                  => 'Změny pokazać',
'anoneditwarning'           => '<b>Kedźbu:</b> Njejsy přizjewjeny. Změny so z twojej IP-adresu składuja.',
'missingsummary'            => '<b>Kedźbu:</b> Njejsy žane zjeće zapodał. Jeli hišće raz na „Składować” kliknješ so twoje změny bjez komentara składuja.',
'missingcommenttext'        => 'Prošu zapodaj zjeće.',
'missingcommentheader'      => '<b>Kedźbu:</b> Njejsy nadpis za tutón komentar podał. Jeli na „Składować” kliknješ, składuje so twoja změna bjez nadpisa.',
'summary-preview'           => 'Přehlad zjeća',
'subject-preview'           => 'Přehlad temy',
'blockedtitle'              => 'Wužiwar je zablokowany',
'blockedtext'               => '<big>\'\'\'Twoje wužiwarske mjeno abo twoja IP-adresa bu přez administratora $1 blokowane(-a).\'\'\'</big>

Podata přičina je: $2.

Ty móhł wužiwarja $1 abo jednoho z druhich [[{{MediaWiki:Grouppage-sysop}}|administratorow]] kontaktować, zo by wo blokowanju diskutował.

Njemóžeš \'e-mejlowe funkcije\' wužiwać, chibazo sy płaćiwu e-mejlowu adresu w swojich [[{{ns:special}}:Preferences|kontowych nastajenjach]] zapodał. Twoja tuchwilna IP-adresa je $3 a blokowa ID je #$5. Prošu podaj jedyn z njeju abo wobaj we swojich naprašowanjach.

<div style="border-style: solid; border-color: red; border-width: 1px; padding:5px;">
\'\'\'Prošu podaj slědowace daty w kóždym naprašowanju:\'\'\'
*Blokowacy administrator: $1
*Přičina blokowanja: $2
*Započatk blokowanja: $8
*Kónc blokowanja: $6
*IP-adresa: $3
*Bolokowanje nastupa: $7
*ID blokowanja: #$5
</div>',
'autoblockedtext'           => 'Twoja IP-adresa bu awtomatisce blokowana, dokelž ju druhi wužiwar wužiwaše, kiž bu wot administratora $1 zablokowany.
Přičina zablokowanja bě:

:\'\'$2\'\' (<span class="plainlinks">[{{fullurl:Special:Ipblocklist|&action=search&limit=&ip=%23}}$5 zapisk w protokolu]</span>)

<p style="border-style: solid; border-color: red; border-width: 1px; padding:5px;"><b>Móžeš strony dale čitać,</b> 
wobdźěłowanje a wutworjenje stronow w {{SITENAME}} pak je znjemóžnjene.
Jeli widźiš tut zdźělenku, hačrunjež sy jenož čitać chcył, sy (čerwjeny) wotkaz na hišće njeeksistowacy nastawk slědował.</p>


Móžeš administratora $1 abo jednoho z druhich [[{{MediaWiki:Grouppage-sysop}}|administratorow]] kontaktować, zo by z nim wo blokowanju diskutował.

<div style="border-style: solid; border-color: red; border-width: 1px; padding:5px;">
\'\'\'Prošu podaj slědowace daty w kóždym naprašowanju:\'\'\'
*Blokowacy administrator: $1
*Přičina zablokowanja: $2
*Započatk blokowanja: $8
*Kónc blokowanja: $6
*IP-adresa: $3
*ID blokowanja: #$5
</div>',
'blockednoreason'           => 'žana přičina podata',
'blockedoriginalsource'     => 'To je žórłowy tekst strony <b>$1</b>:',
'blockededitsource'         => 'Tekst <b>twojich změnow</b> strony <b>$1</b> so tu pokazuje:',
'whitelistedittitle'        => 'Za wobdźěłowanje je přizjewjenje trěbne.',
'whitelistedittext'         => 'Dyrbiš so $1, zo by strony wobdźěłować móhł.',
'whitelistreadtitle'        => 'Za čitanje je přizjewjenje trěbne.',
'whitelistreadtext'         => 'Dyrbiš so [[{{ns:special}}:Userlogin|přizjewić]], zo by strony čitać móhł.',
'whitelistacctitle'         => 'Njesměš konto wutworić',
'whitelistacctext'          => 'Zo by konta w tutym wikiju wutworjeć směł, dyrbiš so [[{{ns:special}}:Userlogin|přizjewić]] a trěbne dowolnosće měć.',
'confirmedittitle'          => 'Twoja e-mejlowa adresa dyrbi so wobkrućić, prjedy hač móžeš strony wobdźěłować.',
'confirmedittext'           => 'Twoja e-mejlowu adresa dyrbi so wobkrućić, prjedy hač móžeš strony wobdźěłować. Prošu zapodaj a wobkruć swoju e-mejlowu adresu z pomocu [[{{ns:special}}:Preferences|wužiwarskich nastajenjow]].',
'nosuchsectiontitle'        => 'Wotrězk njeeksistuje',
'nosuchsectiontext'         => 'Sy spytał, njewobstejacy wotrězk $1 wobdźěłać. Móžeš pak jenož wobstejace wotrězki wobdźěłać.',
'loginreqtitle'             => 'Přizjewjenje trěbne',
'loginreqlink'              => 'přizjewić',
'loginreqpagetext'          => 'Dyrbiš so $1, zo by strony čitać móhł.',
'accmailtitle'              => 'Hesło bu pósłane.',
'accmailtext'               => 'Hesło za wužiwarja [[{{ns:user}}:$1]] bu na adresu $2 pósłane.',
'newarticle'                => '(Nowy nastawk)',
'newarticletext'            => 'Sy wotkaz k stronje slědował, kotraž hišće njeeksistuje. Zo by stronu wutworił, wupjelń tekstowe polo deleka (hlej [[{{MediaWiki:Helppage}}|stronu pomocy]] za wjace informacijow). Jeli sy zmylnje tu, klikń na tłóčku <b>Wróćo</b> swojeho wobhladowaka.',
'anontalkpagetext'          => '----
<i>To je diskusijna strona za anonymneho wužiwarja, kiž hišće konto wutworił njeje abo je njewužiwa. Dyrbimy tohodla numerisku IP-adresu wužiwać, zo bychmy jeho/ju identifikowali. Tajka adresa hodźi so wot wjacorych wužiwarjow zhromadnje wužiwać. Jeli sy anonymny wužiwar a měniš, zo buchu irelewantne komentary k tebi pósłane, [[{{ns:special}}:Userlogin|wutwor konto abo přizjew so]], zo by přichodnu šmjatańcu wobešoł.</i>',
'noarticletext'             => 'Tuchwilu tuta strona žadyn tekst njewobsahuje, móžeš jeje titul w druhich stronach [[{{ns:special}}:Search/{{PAGENAME}}|pytać]] abo [{{fullurl:{{FULLPAGENAME}}|action=edit}} stronu wutworić].',
'clearyourcache'            => '<b>Kedźbu:</b> Po składowanju dyrbiš snano pufrowak swojeho wobhladowaka wuprózdnić, <b>Mozilla/Firefox/Safari:</b> tłóč na <i>Umsch</i> kliknjo na <i>Znowa</i> abo tłóč <i>Strg-Umsch-R</i> (<i>Cmd-Shift-R</i> na Apple Mac); <b>IE:</b> tłóč <i>Strg</i> kliknjo na symbol <i>Aktualisieren</i> abo tłóč <i>Strg-F5</i>; <b>Konqueror:</b>: Klikń jenož na tłóčatko <i>Erneut laden</i> abo tłoč  <i>F5</i>; Wužiwarjo <b>Opery</b> móža swój pufrowak dospołnje  w <i>Tools→Preferences</i> wuprózdnić.',
'usercssjsyoucanpreview'    => '<strong>Pokiw:</strong> Wužij tłóčku „Přehlad”, zo by swój nowy css/js do składowanja testował.',
'usercsspreview'            => "== Přehlad twojeho wosobinskeho CSS ==

'''Kedźbu:''' Po składowanju dyrbiš pufrowak swojeho wobhladowaka wuprózdnić '''Mozilla/Firefox:''' ''Strg-Shift-R'', '''Internet Explorer:''' ''Strg-F5'', '''Opera:''' ''F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror:''' ''F5''.",
'userjspreview'             => "== Přehlad twojeho wosobinskeho JavaScript ==

'''Kedźbu:''' Po składowanju dyrbiš pufrowak swojeho wobhladowaka wuprózdnić '''Mozilla/Firefox:''' ''Strg-Shift-R'', '''Internet Explorer:''' ''Strg-F5'', '''Opera:''' ''F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror:''' ''F5''.",
'userinvalidcssjstitle'     => '<b>Kedźbu:</b> Skin z mjenom „$1” njeeksistuje. Prošu mysli na to, zo dyrbja wosobinske .css a .js strony z małym pismikom započeć, na př. User:Foo/monobook.css město User:Foo/Monobook.css.',
'updated'                   => '(Zaktualizowany)',
'note'                      => '<strong>Kedźbu:</strong>',
'previewnote'               => '<strong>Kedźbu, to je jenož přehlad, změny hišće składowane njejsu!</strong>',
'previewconflict'           => 'Tutón přehlad tekst w hornim tekstowym polu zwobrazni kaž so zjewi, jeli jón składuješ.',
'session_fail_preview'      => '<strong>Zda so, zo je problem z twojim přizjewjenjom; tuta akcija bu wěstosće dla přećiwo zadobywanju do posedźenja znjemóžnjena. Prošu spytaj hišće raz. Wostanje-li tutón problem, spytaj so wotzjewić a so zaso přizjewić.</strong>',
'session_fail_preview_html' => "<strong>Wodaj! Straty posedźenskich datow dla njemóžachmy twoju změnu předźěłać.</strong>

''Dokelž tutón wiki je luty HTML zmóžnił, je přehlad jako wěstotna naprawa přećiwo atakam přez JavaScript schowany.''

<strong>Jeli to je legitimny wobdźěłowanski pospyt, spytaj prošu hišće raz. Jeli to hišće njefunguje, wotzjew so a přizjew so znowa.</strong>",
'token_suffix_mismatch'     => '<strong>Twoja změna je so wotpokazała, dokelž twój wobhladowak je znamješka skepsał.
Składowanje móže wobsah strony zničić. Móže so to na přikład přez wopačnje dźěłowacy proksy stać.</strong>',
'editing'                   => 'Wobdźěłanje strony $1',
'editinguser'               => 'Wužiwar <b>$1</b> so wobdźěłuje',
'editingsection'            => 'Wobdźěłanje strony $1 (wotrězk)',
'editingcomment'            => 'Wobdźěłanje strony $1 (komentar)',
'editconflict'              => 'Wobdźěłowanski konflikt: $1',
'explainconflict'           => 'Něchtó druhi je stronu změnił w samsnym času, hdyž sy spytał ju wobdźěłować. Hornje tekstowe polo wobsahuje tekst strony kaž tuchwilu eksistuje. Twoje změny so w delnim tekstowym polu pokazuja. Dyrbiš swoje změny do eksistowaceho teksta zadźěłać. <b>Jenož</b> tekst w hornim tekstowym polu so składuje hdyž znowa na „Składować” kliknješ.<br />',
'yourtext'                  => 'Twój tekst',
'storedversion'             => 'Składowana wersija',
'nonunicodebrowser'         => '<strong>KEDŹBU: Twój wobhladowak z Unikodu kompatibelny njeje. Prošu wužiwaj hinaši wobhladowak.</strong>',
'editingold'                => '<strong>KEDŹBU: Wobdźěłuješ staršu wersiju strony. Jeli ju składuješ, zjewi so jako najnowša wersija!</strong>',
'yourdiff'                  => 'Rozdźěle',
'copyrightwarning'          => 'Prošu wobkedźbuj, zo wšě přinoški k {{GRAMMAR:datiw|{{SITENAME}}}} $2 podleže (hlej $1 za podrobnosće). Jeli nochceš, zo so twój přinošk po dobrozdaću wobdźěłuje a znowa rozšěrja, njeskładuj jón.<br />
Lubiš tež, zo sy to sam napisał abo ze zjawneje domejny abo z podobneho žórła kopěrował.
Kopěrowanje tekstow, kiž su přez awtorske prawa škitane, je zakazane! <strong>NJESKŁADUJ PŘINOŠKI Z COPYRIGHTOM BJEZ DOWOLNOSĆE!</strong>',
'copyrightwarning2'         => 'Prošu wobkedźbuj, zo wšě přinoški k {{GRAMMAR:datiw|{{SITENAME}}}} hodźa so wot druhich wužiwarjow wobdźěłować, změnić abo wotstronić. Jeli nochceš, zo so twój přinošk po dobrozdaću wobdźěłuje, njeskładuj jón.<br />

Lubiš nam tež, zo sy jón sam napisał abo ze zjawneje domejny abo z podobneho swobodneho žórła kopěrował (hlej $1 za podrobnosće).

<strong>NJESKŁADUJ PŘINOŠKI Z COPYRIGHTOM BJEZ DOWOLNOSĆE!</strong>',
'longpagewarning'           => '<strong>KEDŹBU: Strona wobsahuje $1 kB; někotre wobhladowaki maja problemy, strony wobdźěłać, kotrež wobsahuja 32 kB abo wjace. Prošu přemysli sej stronu do mjeńšich wotrězkow rozrjadować.</strong>',
'longpageerror'             => '<strong>ZMYLK: Tekst, kotryž sy spytał składować wobsahuje $1 kB, maksimalna wulkosć pak je $2 kB. Njehodźi so składować.</strong>',
'readonlywarning'           => '<strong>KEDŹBU: Datowa banka bu wothladanja dla zawrjena, tohodla njemóžeš swoje wobdźěłowanja nětko składować. Móžeš tekst do tekstoweje dataje přesunyć a jón za pozdźišo składować.</strong>',
'protectedpagewarning'      => '<strong>KEDŹBU: Strona bu škitana, tak zo jenož wužiwarjo z prawami administratora móža ju wobdźěłać.</strong>',
'semiprotectedpagewarning'  => '<b>Kedźbu:</b> Strona bu škitana, tak zo jenož přizjewjeni wužiwarjo móža ju wobdźěłać.',
'cascadeprotectedwarning'   => "'''KEDŹBU: Tuta strona je škitana, tak zo móža ju jenož wužiwarjo z prawami administratora wobdźělać, dokelž je w {{PLURAL:$1|slědowacej stronje|slědowacych stronach}} zapřijata, {{PLURAL:$1|kotraž je|kotrež su}} přez kaskadowu opciju {{PLURAL:$1|škitana|škitane}}:'''",
'templatesused'             => 'Na tutej stronje wužiwane předłohi:',
'templatesusedpreview'      => 'W tutym přehledźe wužiwane předłohi:',
'templatesusedsection'      => 'W tutym wotrězku wužiwane předłohi:',
'template-protected'        => '(škitana)',
'template-semiprotected'    => '(škitana za njepřizjewjenych wužiwarjow a nowačkow)',
'edittools'                 => '<!-- Tutón tekst so spody wobdźěłowanskich a nahrawanskich formularow pokazuje. -->',
'nocreatetitle'             => 'Wutworjenje stron je wobmjezowane.',
'nocreatetext'              => 'Móžnosć wutworjenja nowych stron je w tutym wikiju wobmjezowana. Móžeš wobstejace strony wobdźěłać abo [[{{ns:special}}:Userlogin|so přizjewić abo wužiwarske konto wutworić]].',
'nocreate-loggedin'         => 'Nimaš prawo, zo by nowe strony w tutym wikiju wutworił.',
'permissionserrors'         => 'Woprawnjenske zmylki',
'permissionserrorstext'     => 'Nimaš prawo, zo by tutu akciju wuwjedł. {{PLURAL:$1|Přičina|Přičiny}}:',
'recreate-deleted-warn'     => "'''Kedźbu: Wutworiš stronu, kiž bu prjedy wušmórnjena.'''
 
Prošu přepruwuj, hač je znowawutworjenje woprawnjena a wotpowěduje prawidłam projekta.
Tu slěduje wujimk z protokola wušmórnjenjow z přičinu za předawše wušmórnjenje:",

# "Undo" feature
'undo-success' => 'Wersija je so wuspěšnje wotstroniła. Prošu přepruwuj deleka w přirunanskim napohledźe, hač twoja změna bu přewzata a klikń potom na „Składować”, zo by změnu składował.',
'undo-failure' => '<span class="error">Wobdźěłanje njehodźeše so wotstronić, dokelž wotpowědny wotrězk bu mjeztym změnjeny.</span>',
'undo-summary' => 'Wersija $1 wužiwarja [[{{ns:special}}:Contributions/$2|$2]] ([[{{ns:user_talk}}:$2|diskusija]]) bu wotstronjena.',

# Account creation failure
'cantcreateaccounttitle' => 'Wužiwarske konto njeda so wutworić.',
'cantcreateaccount-text' => "Wutworjenje wužiwarskeho konta z IP-adresy <b>$1</b> bu wot [[User:$3|$3]] zablokowane.

Přičina za blokowanje, podata wot $3: ''$2''",

# History pages
'revhistory'          => 'stawizny',
'viewpagelogs'        => 'protokole tuteje strony pokazać',
'nohistory'           => 'Njeje žanych staršich wersijow strony.',
'revnotfound'         => 'Njebě móžno, požadanu wersiju namakać',
'revnotfoundtext'     => 'Stara wersija strony, kotruž sy žadał, njeda so namakać. Prošu pruwuj URL, kiž sy wužiwał.',
'loadhist'            => 'Stawizny strony so začita',
'currentrev'          => 'Aktualna wersija',
'revisionasof'        => 'Wersija z $1',
'revision-info'       => 'Wersija z $1 wužiwarja $2',
'previousrevision'    => '←starša wersija',
'nextrevision'        => 'nowša wersija→',
'currentrevisionlink' => 'Aktualnu wersiju pokazać',
'cur'                 => 'akt',
'next'                => 'přich',
'last'                => 'posl',
'orig'                => 'prěnja',
'page_first'          => 'spočatk',
'page_last'           => 'kónc',
'histlegend'          => 'Diff wubrać: Wubjer opciske pola za přirunanje a tłóč na enter abo tłóčku deleka.

Legenda: (akt) = rozdźěl k tuchwilnej wersiji, (posl) = rozdźěl k předchadnej wersiji, S = snadna změna.',
'deletedrev'          => '[wušmórnjena]',
'histfirst'           => 'tuchwilnu',
'histlast'            => 'najstaršu',
'historysize'         => '({{PLURAL:$1|1 bajt|$1 bajtaj|$1 bajty|$1 bajtow}})',
'historyempty'        => '(prózdna)',

# Revision feed
'history-feed-title'          => 'Stawizny wersijow',
'history-feed-description'    => 'Stawizny wersijow za tutu stronu w {{GRAMMAR:lokatiw|{{SITENAME}}}}',
'history-feed-item-nocomment' => '$1 w $2 hodź.', # user at time
'history-feed-empty'          => 'Strona, kotruž sy požadał, njeeksistuje. Bu snano z wikija wotstronjena abo přemjenowana. Móžeš [[{{ns:special}}:Search|tu]] za stronami z podobnym titulom pytać.',

# Revision deletion
'rev-deleted-comment'         => '(komentar wotstronjeny)',
'rev-deleted-user'            => '(wužiwarske mjeno wotstronjene)',
'rev-deleted-event'           => '(akcija bu wotstronjena)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">Tuta wersija bu wušmórnjena a njeda so wjace čitać. Přićinu móžeš w [{{fullurl:{{ns:special}}:Log/delete|page={{PAGENAMEE}}}} protokolu wušmórnjenjow] zhonić.</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">Tuta wersija bu wušmórnjena a njeda so wjace čitać. Jako administrator móžeš ju pak dale čitać. Přićinu móžeš w [{{fullurl:{{ns:special}}:Log/delete|page={{PAGENAMEE}}}} protokolu wušmórnjenjow] zhonić.</div>',
'rev-delundel'                => 'pokazać/schować',
'revisiondelete'              => 'Wersije wušmórnyć/wobnowić',
'revdelete-nooldid-title'     => 'Žana wersija podata',
'revdelete-nooldid-text'      => 'Njejsy cilowu wersiju abo cilowe wersije podał, zo by tutu funkciju wužił.',
'revdelete-selected'          => '{{PLURAL:$2|Wubrana wersija|Wubrane wersije}} wot [[:$1]]:',
'logdelete-selected'          => "{{PLURAL:$2|Wubrany zapisk z protokola|Wubranej zapiskaj z protokola|Wubrane zapiski z protokola|Wubrane zapiski z protokola}} za '''$1:'''",
'revdelete-text'              => 'Wušmórnjene wersije so w stawiznach dale jewja, jich wobsah pak za wužiwarjow čitajomne njeje.

Druzy administratorojo w tutym wikiju móža schowany tekst dale čitać a jón wobnowić, chibazo su tež jich prawa wobmjezowane.',
'revdelete-legend'            => 'Wobmjezowanja za wersije zrjadować:',
'revdelete-hide-text'         => 'Tekst tuteje wersije schować',
'revdelete-hide-name'         => 'Akciju w protokolach schować',
'revdelete-hide-comment'      => 'Zjeće schować',
'revdelete-hide-user'         => 'Wužiwarske mjeno/IP-adresu schować',
'revdelete-hide-restricted'   => 'Tute wobmjezowanja na administratorow kaž tež na druhich wužiwarjow nałožować',
'revdelete-suppress'          => 'Přičinu wušmórnjenja tež za administratorow schować',
'revdelete-hide-image'        => 'Wobsah wobraza schować',
'revdelete-unsuppress'        => 'Wobmjezowanja za wobnowjene wersije zběhnyć',
'revdelete-log'               => 'Komentar w protokolu:',
'revdelete-submit'            => 'Na wubranu wersiju nałožować',
'revdelete-logentry'          => 'Widźomnosć wersije změnjena za [[$1]]',
'logdelete-logentry'          => 'je widźomnosć za [[$1]] změnił',
'revdelete-logaction'         => '$1 {{PLURAL:$1|wersiju|wersiji|wersije|wersijow}} do modusa $2 {{PLURAL:$1|stajena|stajenej|stajene|stajenych}}',
'logdelete-logaction'         => '$1 {{PLURAL:$1|zapisk|zapiskaj|zapiski|zapiskow}} za [[$3]] su nětko w modusu $2',
'revdelete-success'           => 'Widźomnosć wersije bu wuspěšnje změnjena.',
'logdelete-success'           => 'Widźomnosć zapiska bu wuspěšnje změnjena.',

# Oversight log
'oversightlog'    => 'Protokol funkcije Oversight',
'overlogpagetext' => 'To je protokol wušmórnjenjow a zablokowanjow, kiž su před administratorami schowane.',

# History merging
'mergehistory'         => 'Stawizny stronow zjednoćić',
'mergehistory-header'  => "Tuta strona ći dowola wersije stawiznow žórłoweje strony na nowej stronje zjednoćić.
Zawěsć, zo tuta změna stawiznisku kontinuitu strony wobchowuje.

'''Znajmjeńša aktualna wersija žórłoweje strony dyrbi so zachować.'''",
'mergehistory-box'     => 'Wersije dweju stronow zjednoćić:',
'mergehistory-from'    => 'Žórłowa strona:',
'mergehistory-into'    => 'Cilowa strona:',
'mergehistory-list'    => 'Zjednoćujomne wersijowe stawizny',
'mergehistory-merge'   => 'Slědowace wersije wot [[:$1|$1]] hodźa so z [[:$2|$2]] zjednoćić. Wužij špaltu z opciskimi tłóčatkami, zo by jenož te wersije zjednoćił, kotrež su so w podatym času a bo před nim wutworili. Wobkedźbuj, zo wužiwanje nawigaciskich wotkazow budźe tutu špaltu wróćo stajeć.',
'mergehistory-go'      => 'Zjednoćujomne změny pokazać',
'mergehistory-submit'  => 'Wersije zjednoćić',
'mergehistory-empty'   => 'Njehodźa so žane wersije zjednoćeć',
'mergehistory-success' => '$3 wersije wot [[:$1|$1]] wuspěšnje z [[:$2|$2]] zjednoćene.',
'mergehistory-fail'    => 'Njeje móžno zjednócenje stawiznow přewjesć, prošu přepruwuj stronu a časowe parametry.',

# Merge log
'mergelog'           => 'Protokol zjednoćenja',
'pagemerge-logentry' => '[[$1]] z [[$2]] zjednoćeny (do $3 {{PLURAL:$3|wersije|wersijow|wersijow|wersijow}})',
'revertmerge'        => 'Zjednoćenje cofnyć',
'mergelogpagetext'   => 'Deleka je lisćina najaktualnišich zjednoćenjow stawiznow dweju stronow.',

# Diffs
'history-title'             => 'Stawizny wersijow strony „$1“',
'difference'                => '(rozdźěl mjez wersijomaj)',
'loadingrev'                => 'začitanje wersijow za diff',
'lineno'                    => 'Rjadka $1:',
'editcurrent'               => 'Tuchwilnu wersiju strony wobdźěłać',
'selectnewerversionfordiff' => 'Nowšu wersiju za přirunanje wubrać',
'selectolderversionfordiff' => 'Staršu wersiju za přirunanje wubrać',
'compareselectedversions'   => 'Wubranej wersiji přirunać',
'editundo'                  => 'cofnyć',
'diff-multi'                => '<small>(Přirunanje wersijow zapřija {{PLURAL:$1|jednu mjez nimaj ležacu wersiju|dwě mjez nimaj ležacej wersiji|$1 mjez nimaj ležace wersije|$1 mjez nimaj ležacych wersijow}}.)</small>',

# Search results
'searchresults'         => 'Pytanske wuslědki',
'searchresulttext'      => 'Za wjace informacijow wo přepytowanju {{GRAMMAR:genitiw|{{SITENAME}}}}, hlej [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'        => 'Sy naprašowanje za „[[:$1|$1]]“ stajił.',
'searchsubtitleinvalid' => 'Sy naprašowanje za „$1“ stajił.',
'noexactmatch'          => '<b>Strona „$1” njeeksistuje.</b>

Móžeš [[{{ns:special}}:Allpages|alfabetiski indeks přepytować]] abo [[:$1|stronu wutworić]].',
'titlematches'          => 'Strony z wotpowědowacym titulom',
'notitlematches'        => 'Žane strony z wotpowědowacym titulom',
'textmatches'           => 'Strony z wotpowědowacym tekstom',
'notextmatches'         => 'Žane strony z wotpowědowacym tekstom',
'prevn'                 => 'předchadne $1',
'nextn'                 => 'přichodne $1',
'viewprevnext'          => '($1) ($2) ($3) pokazać',
'showingresults'        => "Deleka so hač {{PLURAL:$1|'''1''' wuslědk pokazuje|'''$1''' wuslědkaj pokazujetej|'''$1''' wuslědki pokazuja|'''$1''' wuslědkow pokazuje}}, započinajo z #'''$2'''.",
'showingresultsnum'     => "Deleka so {{PLURAL:$3|'''1''' wuslědk pokazuje|'''$3''' wuslědkaj pokazujetej|'''$3''' wuslědki pokazuja|'''$3''' wuslědkow pokazuje}}, započinajo z #'''$2'''.",
'nonefound'             => '<b>Kedźbu:</b> Pytanja bjez wuspěcha so často z pytanjom za powšitkownymi słowami zawinuja, kotrež so njeindicěruja abo přez podaće wjace hač jednoho pytanskeho wuraza. Jenož strony, kotrež wšě pytanske wurazy wobsahuja, so w lisćinje wuslědkow zjewja. W tym padźe spytaj ličbu pytanskich wurazow pomjeńšić.',
'powersearch'           => 'Pytać',
'powersearchtext'       => 'W mjenowych rumach pytać:<br />$1<br />$2 Daleposrědkowanja naličeć<br />Za wurazom pytać: $3 $9',
'searchdisabled'        => 'Pytanje w {{GRAMMAR:lokatiw|{{SITENAME}}}} tuchwilu móžne njeje. Móžeš mjeztym z Google pytać. Wobkedźbuj, zo móža wuslědki z wobsaha {{GRAMMAR:genitiw|{{SITENAME}}}} zestarjene być.',

# Preferences page
'preferences'              => 'Nastajenja',
'preferences-summary'      => 'Na tutej specialnej stronje móžeš wosobinske daty změnić a powjerch swojim potrěbnosćam přiměrjeć.',
'mypreferences'            => 'moje nastajenja',
'prefs-edits'              => 'Ličba změnow:',
'prefsnologin'             => 'Njepřizjewjeny',
'prefsnologintext'         => 'Dyrbiš [[{{ns:special}}:Userlogin|přizjewjeny]] być, zo by nastajenja postajić móhł.',
'prefsreset'               => 'Nastajenja su so ze składa wróćo stajili. Twoje změnjenja njejsu so składowali.',
'qbsettings'               => 'Pobóčna lajsta',
'qbsettings-none'          => 'Žane',
'qbsettings-fixedleft'     => 'Leži nalěwo',
'qbsettings-fixedright'    => 'Leži naprawo',
'qbsettings-floatingleft'  => 'Wisa nalěwo',
'qbsettings-floatingright' => 'Wisa naprawo',
'changepassword'           => 'Hesło změnić',
'skin'                     => 'Šat',
'dateformat'               => 'Format datuma',
'datedefault'              => 'Žane nastajenje',
'datetime'                 => 'Datum a čas',
'math_failure'             => 'Analyza njeje so poradźiła',
'math_unknown_error'       => 'njeznaty zmylk',
'math_unknown_function'    => 'njeznata funkcija',
'math_lexing_error'        => 'leksikalny zmylk',
'math_syntax_error'        => 'syntaktiski zmylk',
'math_image_error'         => 'Konwertowanje do PNG zwrěšćiło; kontroluj prawu instalaciju latex, dvips, gs a konwertuj',
'math_bad_tmpdir'          => 'Njemóžno do nachwilneho matematiskeho zapisa pisać abo jón wutworić',
'math_bad_output'          => 'Njemóžno do matematiskeho zapisa za wudaće pisać abo jón wutworić',
'math_notexvc'             => 'Wuwjedźomny texvc pobrachuje; prošu hlej math/README za konfiguraciju.',
'prefs-personal'           => 'Wužiwarske daty',
'prefs-rc'                 => 'Aktualne změny',
'prefs-watchlist'          => 'Wobkedźbowanki',
'prefs-watchlist-days'     => 'Ličba dnjow, kotrež maja so we wobkedźbowankach pokazać:',
'prefs-watchlist-edits'    => 'Ličba změnow, kotrež maja so we wobkedźbowankach pokazać:',
'prefs-misc'               => 'Wšelake nastajenja',
'saveprefs'                => 'Składować',
'resetprefs'               => 'Wróćo stajić',
'oldpassword'              => 'Stare hesło:',
'newpassword'              => 'Nowe hesło:',
'retypenew'                => 'Nowe hesło wospjetować:',
'textboxsize'              => 'Wobdźěłowanje',
'rows'                     => 'Rjadki:',
'columns'                  => 'Stołpiki:',
'searchresultshead'        => 'Pytać',
'resultsperpage'           => 'Wuslědki za stronu:',
'contextlines'             => 'Rjadki na wuslědk:',
'contextchars'             => 'Kontekst na rjadku:',
'stub-threshold'           => 'Wotkazowe formatowanje <a href="#" class="stub">małych stronow</a> (w bajtach):',
'recentchangesdays'        => 'Ličba dnjow w lisćinje aktualnych změnow:',
'recentchangescount'       => 'Ličba stron w lisćinje aktualnych změnow:',
'savedprefs'               => 'Nastajenja buchu składowane.',
'timezonelegend'           => 'Časowe pasmo',
'timezonetext'             => 'Zapisaj ličbu hodźin, wo kotrež so twój lokalny čas wot časa serwera (UTC) wotchila.',
'localtime'                => 'Lokalny čas',
'timezoneoffset'           => 'Rozdźěl¹',
'servertime'               => 'Čas serwera',
'guesstimezone'            => 'Z wobhladowaka přewzać',
'allowemail'               => 'Mejlki wot druhich wužiwarjow přijimować',
'defaultns'                => 'W tutych mjenowych rumach awtomatisce pytać:',
'default'                  => 'standard',
'files'                    => 'Dataje',

# User rights
'userrights-lookup-user'      => 'Wužiwarske skupiny zrjadować',
'userrights-user-editname'    => 'Wužiwarske mjeno:',
'editusergroup'               => 'Wužiwarske skupiny wobdźěłać',
'userrights-editusergroup'    => 'Wužiwarske skupiny wobdźěłać',
'saveusergroups'              => 'Wužiwarske skupiny składować',
'userrights-groupsmember'     => 'Čłon skupiny:',
'userrights-groupsavailable'  => 'K dispoziciji stejace skupiny:',
'userrights-groupshelp'       => 'Wubjer skupiny, z kotrychž chceš wužiwarja wotstronić abo kotrymž chceš wužiwarja přidać. Njewubrane skupiny so njezměnja. Móžeš skupinu z STRG + lěwe kliknjenje wotwolić',
'userrights-reason'           => 'Přičina:',
'userrights-available-none'   => 'Njesměš wužiwarske prawa změnić.',
'userrights-available-add'    => 'Směš wužiwarjow k skupinam $1 přidać.',
'userrights-available-remove' => 'Směš wužiwarjow ze skupinow $1 wotstronić.',

# Groups
'group'               => 'Skupina:',
'group-autoconfirmed' => 'awtomatisce potwjerdźeny',
'group-bot'           => 'Boty',
'group-sysop'         => 'administratorojo',
'group-bureaucrat'    => 'Běrokraća',
'group-all'           => '(wšě)',

'group-autoconfirmed-member' => 'Potwjerdźeny wužiwar',
'group-bot-member'           => 'bot',
'group-sysop-member'         => 'administrator',
'group-bureaucrat-member'    => 'běrokrat',

'grouppage-autoconfirmed' => '{{ns:project}}:Potwjerdźeni wužiwarjo',
'grouppage-bot'           => '{{ns:project}}:Boty',
'grouppage-sysop'         => '{{ns:project}}:Administratorojo',
'grouppage-bureaucrat'    => '{{ns:project}}:Běrokraća',

# User rights log
'rightslog'      => 'Protokol zrjadowanja wužiwarskich prawow',
'rightslogtext'  => 'To je protokol změnow wužiwarskich prawow.',
'rightslogentry' => 'skupinowe čłonstwo za $1 z $2 na $3 změnjene',
'rightsnone'     => '(ničo)',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|změna|změnje|změny|změnow}}',
'recentchanges'                     => 'Aktualne změny',
'recentchangestext'                 => 'Na tutej stronje móžeš najaktualniše změny w {{GRAMMAR:lokatiw|{{SITENAME}}}} wobkedźbować.',
'recentchanges-feed-description'    => 'Slěduj najaktualniše změny {{GRAMMAR:genitiw|{{SITENAME}}}} w tutym kanalu.',
'rcnote'                            => "Deleka {{PLURAL:$1|je '''1'''je změna|stej poslednjej '''$1''' změnje|su poslednje '''$1''' změny|je poslednich '''$1''' změnow}} w {{PLURAL:$2|poslednim dnju|poslednimaj '''$2''' dnjomaj|poslednich '''$2''' dnjach|poslednich '''$2''' dnjach}}, staw wot $3.",
'rcnotefrom'                        => 'Deleka so změny wot <b>$2</b> pokazuja (hač k <b>$1</b>).',
'rclistfrom'                        => 'Nowe změny pokazać, započinajo z $1',
'rcshowhideminor'                   => 'snadne změny $1',
'rcshowhidebots'                    => 'změny awtomatiskich programow (bots) $1',
'rcshowhideliu'                     => 'změny přizjewjenych wužiwarjow $1',
'rcshowhideanons'                   => 'změny anonymnych wužiwarjow $1',
'rcshowhidepatr'                    => 'dohladowane změny $1',
'rcshowhidemine'                    => 'moje změny $1',
'rclinks'                           => 'Poslednje $1 změnow poslednich $2 dnjow pokazać<br />$3',
'diff'                              => 'rozdźěl',
'hist'                              => 'wersije',
'hide'                              => 'schować',
'show'                              => 'pokazać',
'minoreditletter'                   => 'S',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|wobkedźbowacy wužiwar|wobkedźbowacaj wužiwarjej|wobkedźbowacy wužiwarjo|wobkedźbowacych wužiwarjow}}]',
'rc_categories'                     => 'Jenož kategorije (dźělene z "|")',
'rc_categories_any'                 => 'wšě',
'rc-change-size'                    => '$1 {{PLURAL:$1|bajt|bajtaj|bajty|bajtow}}',
'newsectionsummary'                 => 'nowy wotrězk: /* $1 */',

# Recent changes linked
'recentchangeslinked'          => 'Změny zwjazanych stron',
'recentchangeslinked-title'    => 'Změny na stronach, kotrež su z „$1“ wotkazane',
'recentchangeslinked-noresult' => 'Njejsu změny zwajzanych stron we wubranej dobje.',
'recentchangeslinked-summary'  => "Tuta specialna strona naliči poslednje změny zwjazanych stronow. Strony z twojich wobkedźbowankow so '''tučnje''' jewja.",

# Upload
'upload'                      => 'Dataju nahrać',
'uploadbtn'                   => 'Dataju nahrać',
'reupload'                    => 'Znowa nahrać',
'reuploaddesc'                => 'Wróćo k nahrawanskemu formularej.',
'uploadnologin'               => 'Njepřizjewjeny',
'uploadnologintext'           => 'Dyrbiš [[{{ns:special}}:Userlogin|přizjewjeny]] być, zo by dataje nahrawać móhł.',
'upload_directory_read_only'  => 'Nahrawanski zapis ($1) njehodźi so přez webserwer popisować.',
'uploaderror'                 => 'Zmylk při nahrawanju',
'uploadtext'                  => 'Wužij formular deleka, zo by nowe dataje nahrał; zo by prjedy nahrate wobrazy wobhladał abo pytał dźi k [[{{ns:special}}:Imagelist|lisćinje nahratych datajow]]; podrobnosće k nahrawanjam a wušmórnjenjam so tež w [[{{ns:special}}:Log/upload|protokolu nahrawanjow]] protokoluja.

Zo by so wobraz do strony zapřijał, wužij wotkaz we formje
* <b><nowiki>[[{{ns:image}}:Dataja.jpg]]</nowiki></b>
*<b><nowiki>[[{{ns:image}}:Dataja.png|alternatiwny tekst]]</nowiki></b>

abo zo by so direktnje z dataju zwjazało
* <b><nowiki>[[{{ns:media}}:Dataja.ogg]]</nowiki></b>',
'uploadlog'                   => 'Protokol nahraćow',
'uploadlogpage'               => 'Protokol nahraćow',
'uploadlogpagetext'           => 'Deleka je lisćina naposledk nahratych datajow.',
'filename'                    => 'Mjeno dataje',
'filedesc'                    => 'Zjeće',
'fileuploadsummary'           => 'Zjeće:',
'filestatus'                  => 'Licenca',
'filesource'                  => 'Žórło',
'uploadedfiles'               => 'Nahrate dataje',
'ignorewarning'               => 'Warnowanje ignorować a dataju najebać toho składować.',
'ignorewarnings'              => 'Wšě warnowanja ignorować',
'minlength1'                  => 'Datajowe mjena dyrbja znajmjeńša jedyn pismik dołhe być.',
'illegalfilename'             => 'Mjeno dataje „$1” wobsahuje znamješka, kotrež w titlach stronow dowolene njejsu. Prošu přemjenuj dataju a spytaj ju znowa nahrać.',
'badfilename'                 => 'Mjeno dataje bu do „$1” změnjene.',
'filetype-badmime'            => 'Dataje družiny MIME „$1” njesmědźa so składować.',
'filetype-badtype'            => "'''„.$1“''' njeje dowoleny datajowy format.
: Dowolene su: $2",
'filetype-missing'            => 'Dataja nima kóncowku (na přikład „.jpg“).',
'large-file'                  => 'Doporuča so, zo dataje wjetše hač $1 njejsu; tuta dataja ma $2.',
'largefileserver'             => 'Dataja je wjetša hač serwer dowoluje.',
'emptyfile'                   => 'Dataja, kotruž sy nahrał, zda so prózdna być. Z přičinu móhł pisanski zmylk w mjenje dataje być. Prošu pruwuj hač chceš ju woprawdźe nahrać.',
'fileexists'                  => 'Dataja z tutym mjenom hižo eksistuje. Jeli kliknješ na „Składować”, so wona přepisuje. Prošu pruwuj $1 jeli njejsy wěsty hač chceš ju změnić.',
'fileexists-extension'        => 'Dataja z podobnym mjenom hižo eksistuje:<br />
Mjeno dataje, kotruž chceš nahrać: <strong><tt>$1</tt></strong><br />
Mjeno eksistowaceje dataje: <strong><tt>$2</tt></strong><br />
Jenož kóncowce rozeznawatej so we wulko- a małopisanju. Prošu wuzwol hinaše mjeno.',
'fileexists-thumb'            => "<center>'''Eksistowacy wobraz'''</center>",
'fileexists-thumbnail-yes'    => 'Dataja zda so minaturka <i>(thumbnail)</i> być. Prošu přepruwuj dataju <strong><tt>$1</tt></strong>.<br />
Jeli je to wobraz w originalnej wulkosći, njetrjebaš minaturku nahrać.',
'file-thumbnail-no'           => 'Mjeno dataje započa so z <strong><tt>$1</tt></strong>. Móže to na wobraz pomjeńšeneje wulkosće <i>(thumbnail, miniaturka)</i> pokazać.
Prošu přepruwuj, hač maš dataju w połnym rozeznaću a nahraj ju, hewak wuzwol druhe mjeno.',
'fileexists-forbidden'        => 'Dataja z tutym mjenom hižo eksistuje, prošu dźi wróćo a nahraj ju z druhim mjenom. [[{{ns:image}}:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Dataja z tutym mjenom hižo w zhromadnym chowanišću datajow eksistuje. Prošu dźi wróćo a nahraj ju z druhim mjenom. [[{{ns:image}}:$1|thumb|center|$1]]',
'successfulupload'            => 'Dataja bu wuspěšnje nahrata',
'uploadwarning'               => 'Warnowanje',
'savefile'                    => 'Dataju składować',
'uploadedimage'               => 'je dataju „[[$1]]” nahrał',
'overwroteimage'              => 'je nowu wersiju dataje „[[$1]]“ nahrał',
'uploaddisabled'              => 'Wodaj, nahraće je znjemóžnjene.',
'uploaddisabledtext'          => 'Nahraće datajow je w tutym wikiju znjemóžnjene.',
'uploadscripted'              => 'Dataja wobsahuje HTML- abo skriptowy kod, kotryž móhł so mylnje přez wobhladowak wuwjesć.',
'uploadcorrupt'               => 'Dataja je wobškodźena abo ma wopačnu kóncowku. Prošu přepruwuj dataju a nahraj ju hišće raz.',
'uploadvirus'                 => 'Dataja wirus wobsahuje! Podrobnosće: $1',
'sourcefilename'              => 'Mjeno žórłoweje dataje',
'destfilename'                => 'Mjeno ciloweje dataje',
'watchthisupload'             => 'Stronu wobkedźbować',
'filewasdeleted'              => 'Dataja z tutym mjenom bu prjedy nahrata a pozdźišo wušmórnjena. Prošu přepruwuj $1 prjedy hač ju znowa składuješ.',
'upload-wasdeleted'           => "'''Kedźbu: Nahrawaš dataju, kotraž bu prjedy wušmórnjena.'''
 
Prošu přepruwuj dokładnje, hač wospjetowane nahraće směrnicam wotpowěduje.
Za twoju informaciju slěduje protokol wušmórnjenjow z wopodstatnjenjom za předchadne wušmórnjenje:",
'filename-bad-prefix'         => 'Datajowe mjeno započina so z <strong>„$1“</strong>. To je powšitkownje datajowe mjeno, kotrež digitalna kamera zwjetša dawa a kotrež tohodla jara wuprajiwe njeje. Prošu wubjer bóle wuprajiwe mjeno za twoju dataju.',
'filename-prefix-blacklist'   => ' #<!-- Njezměń tutu linku! --> <pre>
# Syntaksa: 
#   * Wšo wot znamješka "#" hač ke kóncej linki je komentar
#   * Kóžda njeprózdna linka je prefiks za typiske datajowe mjena, 
# kotrež so awtomatisce přez digitalne kamery připokazuja
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # někptre mobilne telefony
IMG # generic
JD # Jenoptik
MGP # Pentax
PICT # misc.
 #</pre> <!-- Njezměń tutu linku! -->',

'upload-proto-error'      => 'Wopačny protokol',
'upload-proto-error-text' => 'URL dyrbi so z <code>http://</code> abo <code>ftp://</code> započeć.',
'upload-file-error'       => 'Nutřkowny zmylk',
'upload-file-error-text'  => 'Nutřkowny zmylk wustupi při pospytu, nachwilnu dataju na serwerje wutworić. Prošu skontaktuj systemoweho administratora.',
'upload-misc-error'       => 'Njeznaty zmylk při nahraću',
'upload-misc-error-text'  => 'Njeznaty zmylk wustupi za čas nahrawanja. Prošu přepruwuj, hač URL je płaćiwy a přistupny a spytaj hišće raz. Jeli problem dale eksistuje, skontaktuj systemoweho administratora.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL docpějomny njeje.',
'upload-curl-error6-text'  => 'Podaty URL njehodźeše so docpěć. Prošu přepruwuj, hač URL je korektny a sydło docpějomne.',
'upload-curl-error28'      => 'Překročenje časa při nahrawanju',
'upload-curl-error28-text' => 'Sydło za wotmołwu předołho trjebaše. Prošu pruwuj, hač sydło je docpějomne, čakaj wokomik a spytaj hišće raz. Spytaj hewak w druhim času hišće raz.',

'license'            => 'Licenca',
'nolicense'          => 'žadyn wuběr',
'license-nopreview'  => '(žadyn přehlad k dispoziciji)',
'upload_source_url'  => ' (płaćiwy, zjawnje docpějomny URL)',
'upload_source_file' => ' (dataja na twojim ličaku)',

# Image list
'imagelist'                 => 'Lisćina datajow',
'imagelist-summary'         => 'Tuta specialna strona naliči wšě nahrate dataje. Standardnje so naposlědk nahrate dateje cyle horjeka pokazuja. Kliknjo na nadpisma stołpikow móžeš sortěrowanje wobroćić abo po druhich kriterijach rjadować.',
'imagelisttext'             => "Deleka je lisćina '''$1''' {{PLURAL:$1|dataje|datajow|datajow|datajow}}, kotraž je po $2 sortěrowana.",
'getimagelist'              => 'Lisćina datajow so čita',
'ilsubmit'                  => 'Pytać',
'showlast'                  => 'Poslednje $1 datajow sortěrowanych po $2 pokazać.',
'byname'                    => 'mjenje',
'bydate'                    => 'datumje',
'bysize'                    => 'wulkosći',
'imgdelete'                 => 'Wušmórnyć',
'imgdesc'                   => 'wopisanje',
'imgfile'                   => 'dataja',
'filehist'                  => 'Wersije dataje',
'filehist-help'             => 'Klikń na wěsty čas, zo by wersiju dataje w tutym času zwobraznił.',
'filehist-deleteall'        => 'wšě wersije wušmórnyć',
'filehist-deleteone'        => 'tutu wersiju wušmórnyć',
'filehist-revert'           => 'cofnyć',
'filehist-current'          => 'aktualnje',
'filehist-datetime'         => 'Čas',
'filehist-user'             => 'Wužiwar',
'filehist-dimensions'       => 'Rozeznaće',
'filehist-filesize'         => 'Wulkosć dataje',
'filehist-comment'          => 'Komentar',
'imagelinks'                => 'Wotkazy',
'linkstoimage'              => 'Dataja so na slědowacych stronach wužiwa:',
'nolinkstoimage'            => 'Njejsu strony, kotrež na tutu dataju wotkazuja.',
'sharedupload'              => 'Tuta dataja je zhromadne nahraće a móže so přez druhe projekty wužiwać.',
'shareduploadwiki'          => 'Za dalše informacije hlej $1.',
'shareduploadwiki-linktext' => 'stronu datajoweho wopisanja',
'noimage'                   => 'Žana dataja z tutym mjenom njeeksistuje. Móžeš $1.',
'noimage-linktext'          => 'ju nahrać',
'uploadnewversion-linktext' => 'nowu wersiju tuteje dataje nahrać',
'imagelist_date'            => 'datum',
'imagelist_name'            => 'mjeno dataje',
'imagelist_user'            => 'wužiwar',
'imagelist_size'            => 'wulkosć (byte)',
'imagelist_description'     => 'wopisanje',
'imagelist_search_for'      => 'Za mjenom wobraza pytać:',

# File reversion
'filerevert'                => 'Wersiju $1 cofnyć',
'filerevert-legend'         => 'Dataju wróćo stajeć',
'filerevert-intro'          => '<span class="plainlinks">Stajiš dataju \'\'\'[[Media:$1|$1]]\'\'\' wróćo na [$4 wersiju wot $2, $3 hodź.]</span>',
'filerevert-comment'        => 'Přičina:',
'filerevert-defaultcomment' => 'wróćo stajene na wersiju wot $1, $2 hodź.',
'filerevert-submit'         => 'Cofnyć',
'filerevert-success'        => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\' bu na [$4 wersiju z $2, $3 hodź.] wróćo stajene.</span>',
'filerevert-badversion'     => 'W zapodatym času žana wersija dataje njeje.',

# File deletion
'filedelete'             => '„$1“ wušmórnyć',
'filedelete-legend'      => 'Wušmórnju dataju',
'filedelete-intro'       => "Wušmórnješ '''„[[Media:$1|$1]]“'''.",
'filedelete-intro-old'   => '<span class="plainlinks">Wušmórnješ \'\'\'„[[Media:$1|$1]]“\'\'\' [wersiju $4 wot $2, $3 hodź].</span>',
'filedelete-comment'     => 'Přičina:',
'filedelete-submit'      => 'Wušmórnyć',
'filedelete-success'     => "Strona '''„$1“''' bu wušmórnjena.",
'filedelete-success-old' => '<span class="plainlinks">Wot dataje \'\'\'„[[Media:$1|$1]]“\'\'\' bu wersija $2, $3 hodźin zničena.</span>',
'filedelete-nofile'      => "'''„$1“''' njeeksistuje na tutym webowym sydle.",
'filedelete-nofile-old'  => "Njeje žana wersija '''„$1“''' wot $2, $3 hodźin.",
'filedelete-iscurrent'   => 'Spytaš najnowšu wersiju dataje wušmórnyć. Prošu cofń do toho na staršu wersiju.',

# MIME search
'mimesearch'         => 'Pytanje po družinje MIME',
'mimesearch-summary' => 'Na tutej specialnej stronje hodźa so dataje po družinje MIME filtrować. Dyrbiš přeco družinu MIME a podrjadowanu družinu zapodać: <tt>image/jpeg</tt> (hlej stronu wopisanja dataje).',
'mimetype'           => 'Družina MIME:',
'download'           => 'Sćahnyć',

# Unwatched pages
'unwatchedpages'         => 'Njewobkedźbowane strony',
'unwatchedpages-summary' => 'Tuta specialna strona naliči wšě strony, kiž njejsu we wobkedźbowankach někotrehožkuli wužiwarja.',

# List redirects
'listredirects'         => 'Lisćina daleposrědkowanjow',
'listredirects-summary' => 'Tuta specialna strona naliči daleposrědkowanja.',

# Unused templates
'unusedtemplates'         => 'Njewužiwane předłohi',
'unusedtemplates-summary' => 'Tuta specialna strona naliči wšě předłohi, kiž so w druhich stronach njewužiwaja. Přepruwuj druhe wotkazy na předłohi, prjedy hač je wušmórnješ.',
'unusedtemplatestext'     => 'Tuta specialna strona naliči wšě předłohi, kiž so w druhich stronach njewužiwaja. Prošu přepruwuj tež druhe móžne wotkazy na předłohi, prjedy hač je wušmórnješ.',
'unusedtemplateswlh'      => 'Druhe wotkazy',

# Random page
'randompage'         => 'Připadny nastawk',
'randompage-nopages' => 'W tutym mjenowym rumje strony njejsu.',

# Random redirect
'randomredirect'         => 'Připadne daleposrědkowanje',
'randomredirect-nopages' => 'Žane daleposrědkowanja w tutym mjenowym rumje.',

# Statistics
'statistics'             => 'Statistika',
'sitestats'              => 'Statistika {{GRAMMAR:genitiw|{{SITENAME}}}}',
'userstats'              => 'Statistika wužiwarjow',
'sitestatstext'          => "{{PLURAL:$1|Je|Stej|Su|Je}} dohromady {{PLURAL:$1|'''1''' strona|'''$1''' stronje|'''$1''' strony|'''$1''' stronow}} w datowej bance. To zapřija tež diskusijne strony, strony wo {{GRAMMAR:lokatiw|{{SITENAME}}}}, krótke nastawki, daleposrědkowanja a druhe, kotrež najskerje nastawki njejsu.

{{PLURAL:$2|Zwostanje|Zwostanjetej|Zwostanu|Zwostanje}} {{PLURAL:$2|'''1''' strona|'''$2''' stronje|'''$2''' strony|'''$2''' stronow}}, {{PLURAL:$2|kotraž najskerje je|kotrejž najskerje stej|kotrež najskerje su|kotrež najskerje je}} {{PLURAL:$2|woprawdźity nastawk|woprawdźitej nastawkaj|woprawdźite nastawki|woprawdźitych nastawkow}}.

{{PLURAL:$8|Je so 1 dataja nahrała|Stej so '''$8''' dataji nahrałoj|Su so '''$8''' dataje nahrali|Je so '''$8''' datajow nahrało}}.

Běše dohromady '''$3''' {{PLURAL:$3|wobhladanje|wobhladani|wobhladanja|wobhladanjow}} stronow a '''$4''' {{PLURAL:$4|změna|změnje|změny|změnow}} stronow, wot toho zo bu {{SITENAME}} připrawjeny. Bě to přerěznje '''$5''' {{PLURAL:$5|změna|změnje|změny|změnow}} na stronu a '''$6''' {{PLURAL:$6|wobhladanje|wobhladani|wobhladanja|wobhladanjow}} na změnu.

Dołhosć [http://meta.wikimedia.org/wiki/Help:Job_queue rynka nadawkow] je '''$7'''.",
'userstatstext'          => "{{PLURAL:$1|Je '''1''' [[{{ns:special}}:Listusers|wužiwar]] zregistrowany|Staj '''$1''' [[{{ns:special}}:Listusers|wužiwarjej]] zregistrowanej|Su '''$1''' [[{{ns:special}}:Listusers|wužiwarjo]] zregistrowani|Je '''$1''' [[{{ns:special}}:Listusers|wužiwarjow]] zregistrowanych}}, '''$2''' (abo '''$4%''') z nich {{PLURAL:$2|je|staj|su|je}} $5.",
'statistics-mostpopular' => 'Najhusćišo wopytowane strony',

'disambiguations'         => 'Rozjasnjenja wjacezmyslnosće',
'disambiguations-summary' => 'Tuta specialna strona naliči nastawki z wotkazami na daleposrědkowanja. Měli město toho na poprawne hesło wotkazać.<br />Strona so jako rozjasnjenje wjacezmyslnosće zarjaduje, jeli [[MediaWiki:disambiguationspage]] na nju wotkazuje. Wotkazy z druhich mjenowych rumow hač hłowneho (nastawkoweho) so tu njenaspomnjeja.',
'disambiguationspage'     => '{{ns:project}}:Rozjasnjenje wjacezmyslnosće',
'disambiguations-text'    => "Slědowace strony na '''rozjasnjenje wjacezmyslnosće''' wotkazuja. Měli město toho na poprawne hesło wotkazać.<br />Strona so jako rozjasnjenje wjacezmyslnosće zarjaduje, jeli [[MediaWiki:disambiguationspage]] na nju wotkazuje.",

'doubleredirects'         => 'Dwójne daleposrědkowanja',
'doubleredirects-summary' => '<b>Kedźbu:</b> Tuta lisćina móže „wopačne pozitiwy” wobsahować. To je potom z wašnjom, jeli su w daleposrědkowanju nimo přispomnjenja, zo so wo tajku stronu jedna, hišće druhe wotkazy zapisane. Tute měli so wotstronjeć.',
'doubleredirectstext'     => 'Kóžda rjadka wobsahuje wotkazy k prěnjemu a druhemu daleposrědkowanju kaž tež k prěnjej lince druheho daleposrědkowanja, kotraž zwjetša woprawdźity cil strony podawa, na kotryž prěnje daleposrědkowanje měło pokazać.',

'brokenredirects'         => 'Skóncowane daleposrědkowanja',
'brokenredirects-summary' => 'Tuta specialna strona naliči daleposrědkowanja na njewobstejace nastawki.',
'brokenredirectstext'     => 'Slědowace daleposrědkowanja wotkazuja na njeeksistowace strony:',
'brokenredirects-edit'    => '(wobdźěłać)',
'brokenredirects-delete'  => '(wušmórnyć)',

'withoutinterwiki'        => 'Strony bjez mjezyrěčnych wotkazow',
'withoutinterwiki-header' => 'Sćěhowace strony njewotkazuja na druhe rěčne wersije:',

'fewestrevisions'         => 'Strony z najmjenje wersijemi',
'fewestrevisions-summary' => 'Tuta specialna strona naliči strony z najmjenje wobdźěłanjemi.',

# Miscellaneous special pages
'nbytes'                          => '$1 {{PLURAL:$1|bajt|bajtaj|bajty|bajtow}}',
'ncategories'                     => '$1 {{PLURAL:$1|jedna kategorija|kategoriji|kategorije|kategorijow}}',
'nlinks'                          => '$1 {{PLURAL:$1|wotkaz|wotkazaj|wotkazy|wotkazow}}',
'nmembers'                        => '{{PLURAL:$1|$1 čłon|$1 čłonaj|$1 čłony|$1 čłonow}}',
'nrevisions'                      => '$1 {{PLURAL:$1|wobdźěłanje|wobdźěłani|wobdźěłanja|wobdźěłanjow}}',
'nviews'                          => '$1 {{PLURAL:$1|jedyn wopyt|wopytaj|wopyty|wopytow}}',
'specialpage-empty'               => 'Tuchwilu žane zapiski.',
'lonelypages'                     => 'Wosyroćene strony',
'lonelypages-summary'             => 'Tuta specialna strona naliči strony, na kotrež so ze žaneje druheje strony njewotkazuje. Tute wosyroćene strony njejsu wupřate, dokelž njehodźa so přez normalnu nawigaciju {{GRAMMAR:genitiw|{{SITENAME}}}} namakać.',
'lonelypagestext'                 => 'Na slědowace strony druhe strony w tutym wikiju njewotkazuja:',
'uncategorizedpages'              => 'Njekategorizowane strony',
'uncategorizedpages-summary'      => 'Tuta specialna strona naliči wšě strony, kotrež dotal njejsu někajkej kategoriji přirjadowane.',
'uncategorizedcategories'         => 'Njekategorizowane kategorije',
'uncategorizedcategories-summary' => 'Tuta specialna strona naliči wšě kategorije, kotrež dotal njejsu někajkej druhej kategoriji přirjadowane.',
'uncategorizedimages'             => 'Njekategorizowane dataje',
'uncategorizedimages-summary'     => 'Tuta specialna strona naliči wšě wobrazy, kotrež dotal njejsu někajkej kategoriji přirjadowane.',
'uncategorizedtemplates'          => 'Njekategorizowane předłohi',
'uncategorizedtemplates-summary'  => 'Tuta specialna strona naliči wšě předłohi, kotrež dotal njejsu někajkej kategoriji přirjadowane.',
'unusedcategories'                => 'Njewužiwane kategorije',
'unusedimages'                    => 'Njewužiwane dataje',
'popularpages'                    => 'Často wopytowane strony',
'popularpages-summary'            => 'Tuta specialna strona naliči najhusćišo wopytowane strony {{GRAMMAR:genitiw|{{SITENAME}}}}.',
'wantedcategories'                => 'Požadane kategorije',
'wantedcategories-summary'        => 'Tuta specialna strona naliči kategorije, kotrež so hižo w nastawkach nałožuja, njejsu pak hišće jako kategorije wutworjene.',
'wantedpages'                     => 'Požadane strony',
'wantedpages-summary'             => 'Tuta specialna strona naliči wšě hišće njeeksistowace strony, na kotrež eksistowace strony hižo wotkazuja.',
'mostlinked'                      => 'Z najwjace stronami zwjazane strony',
'mostlinked-summary'              => 'Tuta specialna strona naliči, njewotwisnje wot mjenoweho ruma, wšě strony z najwjace wotkazami.',
'mostlinkedcategories'            => 'Z najwjace stronami zwjazane kategorije',
'mostlinkedcategories-summary'    => 'Tuta specialna strona naliči najhusćišo wužiwane kategorije.',
'mostlinkedtemplates'             => 'Najhusćišo wužiwane předłohi',
'mostlinkedtemplates-summary'     => 'Tuta specialna strona pokaza lisćinu najhusćišo wužiwanych předłohow.',
'mostcategories'                  => 'Strony z najwjace kategorijemi',
'mostcategories-summary'          => 'Tuta specialna strona naliči najhusćišo kategorizowane strony.',
'mostimages'                      => 'Z najwjace stronami zwjazane dataje',
'mostimages-summary'              => 'Tuta specialna strona naliči najwjace wužiwane dataje.',
'mostrevisions'                   => 'Nastawki z najwjace wersijemi',
'mostrevisions-summary'           => 'Tuta specialna strona naliči strony, kiž buchu najhusćišo wobdźěłane.',
'allpages'                        => 'Wšě nastawki',
'allpages-summary'                => 'Tuta specialna strona naliči wšě strony {{GRAMMAR:genitiw|{{SITENAME}}}} wot A do Ž.',
'prefixindex'                     => 'Wšě nastawki (z prefiksom)',
'prefixindex-summary'             => 'Tuta specialna strona naliči wšě strony, kotrež započinaja z podatym rjadom znamješkow (prefiks). Pohlad móže so na wěsty mjenowy rum wobmjezować.',
'shortpages'                      => 'Krótke nastawki',
'shortpages-summary'              => 'Tuta specialna strona naliči najkrótše nastawki w hłownym mjenowym rumje. Liča so znamješka teksta kajkež so we wobdźěłanskim woknom jewja, potajkim we wiki-syntaksu a bjez wobsaha zapřijatych předłohow. Zakład ličenja je z UTF-8 koděrowany tekst.',
'longpages'                       => 'Dołhe nastawki',
'longpages-summary'               => 'Tuta specialna strona naliči najdlěše nastawki we hłownym mjenowym rumje. Liča so znamješka teksta kaž so we wobdźěłanskim woknom jewja, potajkim we wiki-syntaksu a bjez wobsaha zapřijatych předłohow. Zakład ličenja je z UTF-8 koděrowany tekst.',
'deadendpages'                    => 'Nastawki bjez wotkazow',
'deadendpages-summary'            => 'Tuta specialna strona naliči strony, kiž nimaja wotkazy na druhe nastawki abo jenož wotkazy na njewobstejace strony.',
'deadendpagestext'                => 'Slědowace strony njejsu z druhimi stronami w tutym wikiju zwjazane.',
'protectedpages'                  => 'Škitane strony',
'protectedpages-summary'          => 'Tuta specialna strona naliči wšě strony, kotrež su přećiwo přesunjenju abo wobdźěłowanju škitane.',
'protectedpagestext'              => 'Tuta specialna strona naliči wšě strony, kotrež su přećiwo přesunjenju abo wobdźěłowanju škitane.',
'protectedpagesempty'             => 'Tuchwilu žane.',
'listusers'                       => 'Lisćina wužiwarjow',
'listusers-summary'               => "Tuta specialna strona naliči wšěch zregistrowanych wužiwarjow. Jich dospołnu ličbu móžeš [[{{ns:special}}:Statistics|tu]] zhonić. Přez wuběrowanske polo ''Skupina'' hodźi so wuběr na jednotliwe skupiny wužiwarjow wobmjezować.",
'specialpages'                    => 'Specialne strony',
'specialpages-summary'            => 'Tuta strona naliči wšě specialne strony. Specialne strony so awtomatisce wutworjeja a njehodźa so wobdźěłać.',
'spheading'                       => 'Specialne strony za wšěch wužiwarjow',
'restrictedpheading'              => 'Specialne strony za administratorow',
'rclsub'                          => '(k stronam, na kotrež strona „$1” pokazuje)',
'newpages'                        => 'Nowe strony',
'newpages-summary'                => 'Tuta specialna strona naliči wšě nowe strony poslednich 30 dnjow. Wuslědki móža so na mjenowe rumy, wužiwarske mjena abo woboje wobmjezować.',
'newpages-username'               => 'Wužiwarske mjeno:',
'ancientpages'                    => 'Najstarše nastawki',
'ancientpages-summary'            => 'Tuta specialna strona naliči strony, kiž najdlěši čas změnjene njebuchu.',
'intl'                            => 'Mjezyrěčne wotkazy',
'move'                            => 'Přesunyć',
'movethispage'                    => 'Stronu přesunyć',
'unusedimagestext'                => '<p>Prošu wobkedźbuj, zo je móžno zo so někotre z tutych datajow w druhich wikijach wužiwaja.</p>',
'unusedcategoriestext'            => 'Slědowace kategorije eksistuja, hačrunjež žana druha strona abo kategorija je njewužiwa.',
'notargettitle'                   => 'Žadyn cil',
'notargettext'                    => 'Njejsy cilowu stronu abo wužiwarja podał, zo by funkciju wuwjesć móhł.',

# Book sources
'booksources'               => 'Pytanje po ISBN',
'booksources-summary'       => 'Na tutej specialnej stronje móžeš ISBN zapodać zo by lisćinu z informacijemi k pytanej knize dóstał. Wjazace smužki abo prózdne znamješka so na naprašowanje njewuskutkuja.',
'booksources-search-legend' => 'Žórła za knihi pytać',
'booksources-go'            => 'Pytać',
'booksources-text'          => 'To je lisćina wotkazow k druhim sydłam, kotrež nowe a trjebane knihi předawaja. Tam móžeš tež dalše informacije wo knihach dóstać, kotrež pytaš:',

'categoriespagetext' => 'Slědowace kategorije w tutym wikiju eksistuja:',
'data'               => 'Daty',
'userrights'         => 'Zrjadowanje wužiwarskich prawow',
'groups'             => 'Skupiny wužiwarjow',
'alphaindexline'     => '$1 do $2',
'version'            => 'Wersija',

# Special:Log
'specialloguserlabel'  => 'Wužiwar:',
'speciallogtitlelabel' => 'Strona:',
'log'                  => 'Protokole',
'all-logs-page'        => 'Wšě protokole',
'log-search-legend'    => 'Protokole přepytować',
'log-search-submit'    => 'OK',
'alllogstext'          => 'To je kombinowany pohlad wšěch w {{GRAMMAR:lokatiw|{{SITENAME}}}} wjedźenych protokolow. Móžeš pohlad wobmjezować, wuběrajo typ protokola, wužiwarske mjeno abo potrjechenu stronu.',
'logempty'             => 'Žane wotpowědowace zapiski w protokolu.',
'log-title-wildcard'   => 'Titul započina so z …',

# Special:Allpages
'nextpage'          => 'přichodna strona ($1)',
'prevpage'          => 'předchadna strona ($1)',
'allpagesfrom'      => 'Strony pokazać, započinajo z:',
'allarticles'       => 'Wšě nastawki',
'allinnamespace'    => 'Wšě strony (mjenowy rum $1)',
'allnotinnamespace' => 'Wšě strony (nic w mjenowym rumje $1)',
'allpagesprev'      => 'Předchadne',
'allpagesnext'      => 'Přichodne',
'allpagessubmit'    => 'Pokazać',
'allpagesprefix'    => 'Strony pokazać z prefiksom:',
'allpagesbadtitle'  => 'Mjeno strony, kotrež sy zapodał, njebě płaćiwe. Měješe pak mjezyrěčny, pak mjezywikijowy prefiks abo wobsahowaše jedne abo wjace znamješkow, kotrež w titlach dowolene njejsu.',
'allpages-bad-ns'   => 'Mjenowy rum „$1" w {{grammar:lokatiw|{{SITENAME}}}} njeeksistuje.',

# Special:Listusers
'listusersfrom'      => 'Započinajo z:',
'listusers-submit'   => 'Pokazać',
'listusers-noresult' => 'Njemóžno wužiwarjow namakać. Prošu wobkedźbuj, zo so mało- abo wulkopisanje na wotprašowanje wuskutkuje.',

# E-mail user
'mailnologin'     => 'Njejsy přizjewjeny.',
'mailnologintext' => 'Dyrbiš [[{{ns:special}}:Userlogin|přizjewjeny]] być a płaćiwu e-mejlowu adresu w swojich [[{{ns:special}}:Preferences|nastajenjach]] měć, zo by druhim wužiwarjam mejlki pósłać móhł.',
'emailuser'       => 'Wužiwarjej mejlku pósłać',
'emailpage'       => 'Wužiwarjej mejlku pósłać',
'emailpagetext'   => 'Jeli tutón wužiwar je płaćiwu e-mejlowu adresu w swojich nastajenjach zapodał, budźe formular deleka mejlku słać.
E-mejlowa adresa, kotruž sy w swojich nastajenjach zapodał, jewi so jako adresa w polu „Wot” mejlki, zo by přijimowar móhł wotmołwić.',
'usermailererror' => 'E-mejlowy objekt je zmylk wróćił:',
'defemailsubject' => 'Powěsć z {{grammar:genitiw|{{SITENAME}}}}',
'noemailtitle'    => 'Žana e-mejlowa adresa podata',
'noemailtext'     => 'Tutón wužiwar njeje płaćiwu e-mejlowu adresu podał abo je so rozsudźił, zo nochce mejlki druhich wužiwarjow dóstać.',
'emailfrom'       => 'Wot',
'emailto'         => 'Komu',
'emailsubject'    => 'Předmjet',
'emailmessage'    => 'Powěsć',
'emailsend'       => 'Wotesłać',
'emailccme'       => 'E-mejluj mi kopiju mojeje powěsće.',
'emailccsubject'  => 'Kopija twojeje powěsće wužiwarjej $1: $2',
'emailsent'       => 'Mejlka wotesłana',
'emailsenttext'   => 'Twoja mejlka bu wotesłana.',

# Watchlist
'watchlist'            => 'Wobkedźbowanki',
'mywatchlist'          => 'Wobkedźbowanki',
'watchlistfor'         => '(za wužiwarja <b>$1</b>)',
'nowatchlist'          => 'Nimaš žane strony w swojich wobkedźbowankach.',
'watchlistanontext'    => 'Dyrbiš so $1, zo by swoje wobkedźbowanki wobhladać abo wobdźěłać móhł.',
'watchnologin'         => 'Njejsy přizjewjeny.',
'watchnologintext'     => 'Dyrbiš [[{{ns:special}}:Userlogin|přizjewjeny]] być, zo by swoje wobkedźbowanki změnić móhł.',
'addedwatch'           => 'Strona bu wobkedźbowankam přidata.',
'addedwatchtext'       => "Strona [[:$1]] bu [[{{ns:special}}:Watchlist|wobkedźbowankam]] přidata.
Přichodne změny tuteje strony a přisłušneje diskusijneje strony budu so tam naličeć a strona so '''w tučnym pismje''' w [[{{ns:special}}:Recentchanges|aktualnych změnach]] zjewi.

Jeli chceš stronu pozdźišo ze swojich wobkedźbowankow wotstronić, klikń na rajtark „njewobkedźbować” tuteje strony.",
'removedwatch'         => 'Strona bu z wobkedźbowankow wotstronjena',
'removedwatchtext'     => 'Strona [[:$1]] bu z wobkedźbowankow wotstronjena.',
'watch'                => 'wobkedźbować',
'watchthispage'        => 'stronu wobkedźbować',
'unwatch'              => 'njewobkedźbować',
'unwatchthispage'      => 'wobkedźbowanje skónčić',
'notanarticle'         => 'njeje nastawk',
'watchnochange'        => 'Žana z twojich wobkedźbowanych stron njebu w podatej dobje wobdźěłana.',
'watchlist-details'    => '{{PLURAL:$1|$1 wobkedźbowana strona|$1 wobkedźbowanej stronje|$1 wobkedźbowane strony|$1 wobkedźbowanych stronow}}, diskusijne strony wuwzate.',
'wlheader-enotif'      => '* E-mejlowe zdźělenje je zmóžnjene.',
'wlheader-showupdated' => '* Strony, kotrež buchu po twojim poslednim wopyće změnjene so <b>tučne</b> pokazuja.',
'watchmethod-recent'   => 'Aktualne změny za wobkedźbowane strony přepruwować',
'watchmethod-list'     => 'Wobkedźbowanki za aktualnymi změnami přepruwować',
'watchlistcontains'    => 'Maš $1 {{PLURAL:$1|stronu|stronje|strony|stronow}} w swojich wobkedźbowankach.',
'iteminvalidname'      => 'Problem ze zapiskom „$1“, njepłaćiwe mjeno.',
'wlnote'               => 'Deleka {{PLURAL:$1|je poslednja|stej poslednjej|su poslednje|su poslednje}} $1 {{PLURAL:$1|změna|změnje|změny|změnow}} za poslednje <b>$2</b> hodź.',
'wlshowlast'           => 'Poslednje $1 hodź. - $2 dnjow - $3 pokazać',
'watchlist-show-bots'  => 'změny botow pokazać',
'watchlist-hide-bots'  => 'změny botow schować',
'watchlist-show-own'   => 'moje změny pokazać',
'watchlist-hide-own'   => 'moje změny schować',
'watchlist-show-minor' => 'snadne změny pokazać',
'watchlist-hide-minor' => 'snadne změny schować',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Wobkedźbuju…',
'unwatching' => 'Njewobkedźbuju…',

'enotif_mailer'                => '{{SITENAME}} E-mejlowe zdźělenje',
'enotif_reset'                 => 'Wšě strony jako wopytane woznamjenić',
'enotif_newpagetext'           => 'To je nowa strona.',
'enotif_impersonal_salutation' => 'wužiwar {{GRAMMAR:genitiw|{{SITENAME}}}}',
'changed'                      => 'změnjena',
'created'                      => 'wutworjena',
'enotif_subject'               => '[{{SITENAME}}] Strona „$PAGETITLE” bu přez wužiwarja $PAGEEDITOR $CHANGEDORCREATED.',
'enotif_lastvisited'           => 'Hlej $1 za wšě změny po twojim poslednim wopyće.',
'enotif_lastdiff'              => 'Hlej $1 za tutu změnu.',
'enotif_anon_editor'           => 'anonymny wužiwar $1',
'enotif_body'                  => 'Luby(a) $WATCHINGUSERNAME,<br />

Strona we {{GRAMMAR:lokatiw|{{SITENAME}}}} z mjenom „$PAGETITLE” bu dnja $PAGEEDITDATE wot $PAGEEDITOR $CHANGEDORCREATED,
hlej $PAGETITLE_URL za aktualnu wersiju.

$NEWPAGE

Zjeće wobdźěłaćerja běše: $PAGESUMMARY $PAGEMINOREDIT

Skontaktuj wobdźěłarja:
e-mejl: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Njebudu žane druhe zdźělenki w padźe dalšich změnow, chibazo wopytaš tutu stronu.
Ty móhł tež zdźělenske opcije za wšě ze swojich wobkedźbowanych stronow wróćo stajić.

Přećelny zdźělenski system {{GRAMMAR:genitiw|{{SITENAME}}}}

--
Zo by nastajenja twojich wobkedźbowankow změnił, wopytaj
{{fullurl:{{ns:special}}:Watchlist/edit}}',

# Delete/protect/revert
'deletepage'                  => 'Stronu wušmórnyć',
'confirm'                     => 'Wobkrućić',
'excontent'                   => "wobsah běše: '$1'",
'excontentauthor'             => "wobsah bě: '$1' (jenički wobdźěłowar bě [[{{ns:user}}:$2|$2]] - [[{{ns:user_talk}}:$2|diskusija]])",
'exbeforeblank'               => "wobsah do wuprózdnjenja běše: '$1'",
'exblank'                     => 'strona běše prózdna',
'confirmdelete'               => 'Wušmórnjenje wobkrućić',
'deletesub'                   => '(strona „$1” so wušmórnje)',
'historywarning'              => 'KEDŹBU: Strona, kotruž chceš wušmórnyć, ma stawizny:',
'confirmdeletetext'           => 'Sy so rozsudźił stronu abo dataju hromadźe ze jeje stawiznami z datoweje banki wotstronić. Prošu wobkruć, zo to maš wotpohlad to činić, zo rozumiš sćěwki a zo to wotpowědujo 
[[{{MediaWiki:Policy-url}}|prawidłam tutoho wikija]] činiš.',
'actioncomplete'              => 'Dokónčene',
'deletedtext'                 => 'Strona „$1” bu wušmórnjena. Hlej $2 za lisćinu aktualnych wušmórnjenjow.',
'deletedarticle'              => 'je stronu [[$1]] wušmórnył.',
'dellogpage'                  => 'Protokol wušmórnjenjow',
'dellogpagetext'              => 'Deleka je lisćina najaktualnišich wušmórnjenjow.',
'deletionlog'                 => 'Protokol wušmórnjenjow',
'reverted'                    => 'Na staršu wersiju cofnjene',
'deletecomment'               => 'Přičina wušmórnjenja',
'deleteotherreason'           => 'Druha/přidatna přičina:',
'deletereasonotherlist'       => 'Druha přičina',
'deletereason-dropdown'       => '*Zwučene přičiny za wušmórnjenje
** Požadanje awtora
** Zranjenje copyrighta
** Wandalizm',
'rollback'                    => 'Změny cofnyć',
'rollback_short'              => 'Cofnyć',
'rollbacklink'                => 'Cofnyć',
'rollbackfailed'              => 'Cofnjenje njeporadźiło',
'cantrollback'                => 'Njemóžno změnu cofnyć; strona nima druhich awtorow.',
'alreadyrolled'               => 'Njemóžno poslednu změnu wot [[:$1]] wužiwarja [[{{ns:user}}:$2|$2]] ([[{{ns:user_talk}}:$2|diskusija]]) cofnyć; něchtó druhi je stronu hižo wobdźěłał abo změnu cofnył.

Poslednja změna běše wot wužiwarja [[{{ns:user}}:$3|$3]] ([[{{ns:user_talk}}:$3|diskusija]]).',
'editcomment'                 => 'Komentar wobdźěłanja běše: „<i>$1</i>”.', # only shown if there is an edit comment
'revertpage'                  => 'Změny wužiwarja [[{{ns:user}}:$2|$2]] ([[{{ns:special}}:Contributions/$2|přinoški]]) cofnjene, nawróćene k poslednjej wersiji wužiwarja [[{{ns:user}}:$1|$1]]',
'rollback-success'            => 'Změny wužiwarja $1 cofnjene; wróćo na wersiju wužiwarja $2.',
'sessionfailure'              => 'Zda so, zo je problem z twojim přizjewjenjom; tuta akcija bu wěstosće dla přećiwo zadobywanju do posedźenja znjemóžniła. Prošu klikń na "Wróćo" a začitaj stronu, z kotrejež přińdźeš, znowa; potom spytaj hišće raz.',
'protectlogpage'              => 'Protokol škita',
'protectlogtext'              => 'To je protokol škitanych stronow a zběhnjenja škita. Hlej [[{{ns:special}}:Protectedpages|tutu specialnu stronu]] za lisćinu škitanych stron.',
'protectedarticle'            => 'je stronu [[$1]] škitał',
'modifiedarticleprotection'   => 'je škit strony [[$1]] změnił',
'unprotectedarticle'          => 'je škit strony [[$1]] zběhnył',
'protectsub'                  => '(Stronu „$1” škitać)',
'confirmprotect'              => 'Škit wobkrućić',
'protectcomment'              => 'Přičina za škitanje:',
'protectexpiry'               => 'Čas škita:',
'protect_expiry_invalid'      => 'Njepłaćiwy čas spadnjenja.',
'protect_expiry_old'          => 'Čas škita leži w zańdźenosći.',
'unprotectsub'                => '(Škit za stronu „$1” so zběhnje)',
'protect-unchain'             => 'Škit přećiwo přesunjenju změnić',
'protect-text'                => 'Tu móžeš status škita strony <b>$1</b> wobhladać a změnić.',
'protect-locked-blocked'      => 'Njemóžeš škit strony změnič, dokelž twoje konto je zablokowane. Tu widźiš aktualne škitne nastajenja za stronu<strong>„$1“:</strong>',
'protect-locked-dblock'       => 'Datowa banka je zawrjena, tohodla njemóžeš škit strony změnić. Tu widźiš aktualne škitne nastajenja za stronu<strong>„$1“:</strong>',
'protect-locked-access'       => 'Nimaš trěbne prawa, zo by škit strony změnił. Tu widźiš aktualne škitne nastajenja za stronu<strong>„$1“:</strong>',
'protect-cascadeon'           => 'Tuta strona je tuchwilu škitana, dokelž je w {{PLURAL:$1|slědowacej stronje|slědowacych stronach}} zapřijata, {{PLURAL:$1|kotraž je|kotrež su}} přez kaskadowu opciju {{PLURAL:$1|škitana|škitane}}. Móžeš škitowy status strony změnić, to wšak wliw na kaskadowy škit nima.',
'protect-default'             => '(standard)',
'protect-fallback'            => 'Prawo "$1" trěbne.',
'protect-level-autoconfirmed' => 'jenož přizjewjeni wužiwarjo',
'protect-level-sysop'         => 'jenož administratorojo',
'protect-summary-cascade'     => 'kaskadowacy',
'protect-expiring'            => 'spadnje $1 (UTC)',
'protect-cascade'             => 'Kaskadowacy škit – wšě w tutej stronje zapřijate strony so škituja.',
'restriction-type'            => 'Škitowy status',
'restriction-level'           => 'Runina škita:',
'minimum-size'                => 'Minimalna wulkosć:',
'maximum-size'                => 'Maksimalna wulkosć:',
'pagesize'                    => '(bajtow)',

# Restrictions (nouns)
'restriction-edit' => 'wobdźěłać',
'restriction-move' => 'přesunyć',

# Restriction levels
'restriction-level-sysop'         => 'dospołnje škitany',
'restriction-level-autoconfirmed' => 'połškitany (móže so jenož přez přizjewjenych wužiwarjow wobdźěłać, kiž nowačcy njejsu)',
'restriction-level-all'           => 'wšě',

# Undelete
'undelete'                     => 'Wušmórnjenu stronu wobnowić',
'undeletepage'                 => 'Wušmórnjene strony wobnowić',
'viewdeletedpage'              => 'Wušmórnjene strony wobhladać',
'undeletepagetext'             => 'Tute strony buchu wušmórnjene, su pak hišće w datowej bance składowane a móža so wobnowić.',
'undeleteextrahelp'            => 'Zo by stronu z wšěmi wersijemi wobnowił zapodaj prošu přičinu a klikń na „Wobnowić”. Chceš-li jenož jednotliwe wersije wobnowić, wuběr prošu jich markěrowanske kašćiki, zapodaj přičinu a klikń na „Wobnowić”. Kliknjenje na „Cofnyć” wuprózdni komentarowe polo a wšě kašćiki.',
'undeleterevisions'            => '$1 {{PLURAL:$1|wersija|wersiji|wersije|wersijow}} {{PLURAL:$1|archiwowana|archiwowanej|archiwowane|archiwowane}}',
'undeletehistory'              => 'Jeli stronu wobnowiš, zapřijmnu so wšě (tež prjedy wušmórnjene) wersije zaso do stawiznow. Jeli bu po wušmórnjenju nowa strona ze samsnym mjenom wutworjena, budu so wobnowjene wersije w prjedawšich stawiznach jewić.',
'undeleterevdel'               => 'Wobnowjenje so přewjedźe, hdyž je najaktualniša wersija schowana abo wobsahuje schowane dźěle. W tutym padźe njesměš najaktualnišu wersiju wubrać abo dyrbiš jeje status na normalnu widźomnosć změnić. Wersije, kotrychž widźomnosć su za tebje schowane, so njewobnowjeja.',
'undeletehistorynoadmin'       => 'Strona bu wušmórnjena. Přičina za wušmórnjenje so deleka w zjeću pokazuje, zhromadnje z podrobnosćemi wužiwarjow, kotřiž běchu tutu stronu do zničenja wobdźěłali. Tuchwilny wobsah strony je jenož administratoram přistupny.',
'undelete-revision'            => 'Wušmórnjena wersija strony $1 - $2, $3:',
'undeleterevision-missing'     => 'Njepłaćiwa abo pobrachowaca wersija. Pak je wotkaz wopačny, pak bu wotpowědna wersija z archiwa wobnowjena abo wotstronjena.',
'undelete-nodiff'              => 'Předchadna wersija njeeksistuje.',
'undeletebtn'                  => 'Wobnowić',
'undeletereset'                => 'Cofnyć',
'undeletecomment'              => 'Přičina:',
'undeletedarticle'             => 'Strona „$1” bu wuspěšnje wobnowjena.',
'undeletedrevisions'           => '$1 {{Plural:$1|wersija|wersiji|wersije|wersijow}} {{Plural:$1|wobnowjena|wobnowjenej|wobnowjene|wobnowjene}}',
'undeletedrevisions-files'     => '$1 {{PLURAL:$1|wersija|wersiji|wersije|wersijow}} a $2 {{Plural:$2|dataja|dataji|dataje|datajow}} {{PLURAL:$2|wobnowjena|wobnowjenej|wobnowjene|wobnowjene}}',
'undeletedfiles'               => '$1 {{PLURAL:$1|dataja|dataji|dataje|datajow}} {{PLURAL:$1|wobnowjena|wobnowjenej|wobnowjene|wobnowjene}}.',
'cannotundelete'               => 'Wobnowjenje zwrěšćiło; něchtó druhi je stronu prjedy wobnowił.',
'undeletedpage'                => '<big><b>Strona $1 bu z wuspěchom wobnowjena.</b></big>

Hlej [[{{ns:special}}:Log/delete|protokol]] za lisćinu aktualnych wušmórnjenjow a wobnowjenjow.',
'undelete-header'              => 'Hlej [[{{ns:special}}:Log/delete|protokol wušmórnjenjow]] za njedawno wušmórnjene strony.',
'undelete-search-box'          => 'Wušmórnjene strony pytać',
'undelete-search-prefix'       => 'Strony pokazać, kotrež započinaja so z:',
'undelete-search-submit'       => 'Pytać',
'undelete-no-results'          => 'Žane přihódne strony w archiwje namakane.',
'undelete-filename-mismatch'   => 'Datajowa wersija z časowym kołkom $1 njeda so wobnowić: Datajowej mjenje njehodźitej so jedne k druhemu.',
'undelete-bad-store-key'       => 'Datajowa wersija z časowym kołkom $1 njeda so wobnowić: dataja před zničenjom hižo njeeksistowaše.',
'undelete-cleanup-error'       => 'Zmylk při wušmórnjenju njewužita wersija $1 z archiwa.',
'undelete-missing-filearchive' => 'Dataja z archiwowym ID $1 njeda so wobnowić, dokelž w datowej bance njeje. Snano bu wona hižo wobnowjena.',
'undelete-error-short'         => 'Zmylk při wobnowjenju dataje $1',
'undelete-error-long'          => 'Buchu zmylki při wobnowjenju dataje zwěsćene:

$1',

# Namespace form on various pages
'namespace'      => 'Mjenowy rum:',
'invert'         => 'Wuběr wobroćić',
'blanknamespace' => '(Nastawki)',

# Contributions
'contributions' => 'Přinoški wužiwarja',
'mycontris'     => 'Moje přinoški',
'contribsub2'   => 'za wužiwarja $1 ($2)',
'nocontribs'    => 'Žane změny, kotrež podatym kriterijam wotpowěduja.',
'ucnote'        => 'Deleka su poslednje <b>$1</b> změnow wužiwarja z poslednich <b>$2</b> dnjow.',
'uclinks'       => 'Poslednje $1 přinoškow pokazać; poslednje $2 dnjow pokazać.',
'uctop'         => '(aktualnje)',
'month'         => 'wot měsaca (a do toho):',
'year'          => 'wot lěta (a do toho):',

'sp-contributions-newest'      => 'najnowše',
'sp-contributions-oldest'      => 'najstarše',
'sp-contributions-newer'       => 'nowše $1',
'sp-contributions-older'       => 'starše $1',
'sp-contributions-newbies'     => 'jenož přinoški nowačkow pokazać',
'sp-contributions-newbies-sub' => 'Za nowačkow',
'sp-contributions-blocklog'    => 'protokol zablokowanjow',
'sp-contributions-search'      => 'Přinoški pytać',
'sp-contributions-username'    => 'IP-adresa abo wužiwarske mjeno:',
'sp-contributions-submit'      => 'OK',

'sp-newimages-showfrom' => 'nowe dataje započinajo z $1',

# What links here
'whatlinkshere'         => 'Što wotkazuje sem',
'whatlinkshere-title'   => 'Strony, kotrež na „$1“ wotkazuja',
'whatlinkshere-summary' => 'Tuta specialna strona naliči wšě nutřkowne wotkazy na jednotliwu stronu. Móžne přidawki „zaprijeće předłohi” abo „daleposrědkowanje” skedźbnja na to, zo njeje strona z normalnym wotkazom zawjazana.',
'whatlinkshere-page'    => 'Strona:',
'linklistsub'           => '(Lisćina wotkazow)',
'linkshere'             => "Sćěhowace strony na stronu '''[[:$1]]''' wotkazuja:",
'nolinkshere'           => "Žane strony na '''[[:$1]]''' njewotkazuja.",
'nolinkshere-ns'        => "Žane strony njewotkazuja na '''[[:$1]]''' we wubranym mjenowym rumje.",
'isredirect'            => 'daleposrědkowanje',
'istemplate'            => 'zapřijeće předłohi',
'whatlinkshere-prev'    => '{{PLURAL:$1|předchadny|předchadnej|předchadne|předchadne $1}}',
'whatlinkshere-next'    => '{{PLURAL:$1|přichodny|přichodnej|přichodne|přichodne $1}}',
'whatlinkshere-links'   => '← wotkazy',

# Block/unblock
'blockip'                     => 'Wužiwarja zablokować',
'blockiptext'                 => 'Wužij formular deleka, zo by pisanski přistup za podatu IP-adresu abo wužiwarske mjeno blokował. To měło so jenož stać, zo by wandalizmej zadźěwało a woptpowědujo [[{{MediaWiki:Policy-url}}|prawidłam]]. Zapodaj deleka přičinu (na př. citujo wosebite strony, kotrež běchu z woporom wandalizma).',
'ipaddress'                   => 'IP-adresa',
'ipadressorusername'          => 'IP-adresa abo wužiwarske mjeno',
'ipbexpiry'                   => 'Spadnjenje',
'ipbreason'                   => 'Přičina',
'ipbreasonotherlist'          => 'Druha přičina',
'ipbreason-dropdown'          => '*powšitkowne přičiny
** wandalizm
** wutworjenje njezmyslnych stronow
** linkspam
** wobobinske nadběhi
*specifiske přičiny
** njepřihódne wužiwarske mjeno
** znowapřizjewjenje na přeco zablokowaneho wužiwarja
** proksy, wandalizma jednotliwych wužiwarjow dla dołhodobnje zablokowany',
'ipbanononly'                 => 'Jenož anonymnych wužiwarjow zablokować',
'ipbcreateaccount'            => 'Wutworjenju nowych kontow zadźěwać',
'ipbemailban'                 => 'Wotpósłanje mejlkow znjemóžnić',
'ipbenableautoblock'          => 'IP-adresy blokować kiž buchu přez tutoho wužiwarja hižo wužiwane kaž tež naslědne adresy, z kotrychž so wobdźěłanje pospytuje',
'ipbsubmit'                   => 'Wužiwarja zablokować',
'ipbother'                    => 'Druha doba',
'ipboptions'                  => '1 hodźinu:1 hour,2 hodźinje:2 hours, 6 hodźiny:6 hours,1 dźeń:1 day,3 dny:3 days,1 tydźeń:1 week,2 njedźeli:2 weeks,1 měsać:1 month,3 měsacy:3 months,6 měsacow:6 months,1 lěto:1 year,na přeco:indefinite',
'ipbotheroption'              => 'druha doba (jendźelsce)',
'ipbotherreason'              => 'Druha/přidatna přičina:',
'ipbhidename'                 => 'Wužiwarske mjeno/IP-adresu w protokolu zablokowanjow, w lisćinje aktiwnych zablokowanjow a w zapisu wužiwarjow schować.',
'badipaddress'                => 'Njepłaćiwa IP-adresa',
'blockipsuccesssub'           => 'Zablokowanje wuspěšne',
'blockipsuccesstext'          => 'Wužiwar [[{{ns:special}}:Contributions/$1|$1]] bu zablokowany a akcija bu w [[{{ns:special}}:Log/block|protokolu zablokowanjow]] protokolowana.
<br />Hlej [[{{ns:special}}:Ipblocklist|lisćinu tuchwilnje płaćiwych zablokowanjow]], zo by zablokowanja přehladał.',
'ipb-edit-dropdown'           => 'přičiny zablokowanjow wobdźěłać',
'ipb-unblock-addr'            => 'zablokowanje wužiwarja „$1“ zběhnyć',
'ipb-unblock'                 => 'zablokowanje wužiwarja abo IP-adresy zběhnyć',
'ipb-blocklist-addr'          => 'aktualne zablokowanja za wužiwarja „$1“ zwobraznić',
'ipb-blocklist'               => 'tuchwilne blokowanja zwobraznić',
'unblockip'                   => 'Zablokowanje zběhnyć',
'unblockiptext'               => 'Wužij formular deleka, zo by blokowanje IP-adresy abo wužiwarskeho mjena zběhnył.',
'ipusubmit'                   => 'Zablokowanje zběhnyć',
'unblocked'                   => 'Blokowanje wužiwarja [[{{ns:user}}:$1|$1]] zběhnjene',
'unblocked-id'                => 'Blokowanje ID $1 bu zběhnjene.',
'ipblocklist'                 => 'Lisćina zablokowanych IP-adresow a wužiwarskich mjenow',
'ipblocklist-legend'          => 'Pytanje za zablokowanym wužiwarjom',
'ipblocklist-username'        => 'Wužiwarske mjeno abo IP-adresa:',
'ipblocklist-summary'         => "Tuta specialna strona naliči přidatnje k [[{{ns:special}}:Log/block|protokolej zablokowanjow]] wšěch '''tuchwilu''' zablokowanych wužiwarjow a wše zablokowane IP-adresy hromadźe z awtomatisce zablokowanymi IP-adresami w anonymizowanej formje.",
'ipblocklist-submit'          => 'Pytać',
'blocklistline'               => '$1, $2 je wužiwarja $3 zablokował ($4)',
'infiniteblock'               => 'na přeco',
'expiringblock'               => 'hač do $1',
'anononlyblock'               => 'jenož anonymnych blokować',
'noautoblockblock'            => 'awtoblokowanje znjemóžnjene',
'createaccountblock'          => 'wutworjenje wužiwarskich kontow znjemóžnjene',
'emailblock'                  => 'Wotpósłanje mejlkow bu znjemóžnjene',
'ipblocklist-empty'           => 'Liścina blokowanjow je prózdna.',
'ipblocklist-no-results'      => 'Požadana IP-adresa/požadane wužiwarske mjeno njeje zablokowane.',
'blocklink'                   => 'zablokować',
'unblocklink'                 => 'blokowanje zběhnyć',
'contribslink'                => 'přinoški',
'autoblocker'                 => 'Awtomatiske blokowanje, dokelž twoja IP-adresa bu njedawno wot wužiwarja „[[{{ns:user}}:$1|$1]]” wužita. Přičina, podata přez blokowaceho administratora $1 je: „<b>$2</b>”.',
'blocklogpage'                => 'Protokol zablokowanjow',
'blocklogentry'               => 'je wužiwarja [[$1]] zablokował z časom spadnjenja $2 $3',
'blocklogtext'                => 'To je protokol blokowanja a wotblokowanja wužiwarjow. Awtomatisce blokowane IP-adresy so njenaličuja. Hlej [[{{ns:special}}:Ipblocklist|lisćinu zablokowanych IP-adresow]] za přehlad tuchwilnych blokowanjow.',
'unblocklogentry'             => 'zablokowanje wužiwarja $1 bu zběhnjene',
'block-log-flags-anononly'    => 'jenož anonymnych',
'block-log-flags-nocreate'    => 'wutworjenje wužiwarskich kontow znjemóžnjene',
'block-log-flags-noautoblock' => 'awtomatiske zablokowanje znjemóžnjene',
'block-log-flags-noemail'     => 'wotpósłanje mejlkow bu znjemóžnjene',
'range_block_disabled'        => 'Kmanosć administratorow, cyłe wobłuki IP-adresow blokować, je znjemóžnjena.',
'ipb_expiry_invalid'          => 'Čas spadnjenja je njepłaćiwy.',
'ipb_already_blocked'         => 'Wužiwar „$1” je hižo zablokowany.',
'ipb_cant_unblock'            => 'Zmylk: Njemóžno ID zablokowanja $1 namakać. Zablokowanje je so najskerje mjeztym zběhnyło.',
'ipb_blocked_as_range'        => 'Zmylk: IP $1 njeje direktnje zablokowana a njeda so wublokować. Blokuje so wšak jako dźěl wobwoda $2, kotryž da so wublokować.',
'ip_range_invalid'            => 'Njepłaciwy wobłuk IP-adresow.',
'blockme'                     => 'Blokować',
'proxyblocker'                => 'Awtomatiske blokowanje wotewrjenych proksy-serwerow',
'proxyblocker-disabled'       => 'Tuta funkcija je deaktiwizowana.',
'proxyblockreason'            => 'Twoja IP-adresa bu zablokowana, dokelž je wotewrjeny proksy. Prošu skontaktuj swojeho prowidera abo syćoweho administratora a informuj jeho wo tutym chutnym wěstotnym problemje.',
'proxyblocksuccess'           => 'Dokónčene.',
'sorbs'                       => 'SORBS DNSbl',
'sorbsreason'                 => 'Twoja IP-adresa je zapisana jako wotewrjeny proksy na DNSBL {{GRAMMAR:genitiw|{{SITENAME}}}}.',
'sorbs_create_account_reason' => 'Twoja IP-adresa je zapisana jako wotewrjeny proksy na DNSBL {{GRAMMAR:genitiw|{{SITENAME}}}}. Njemóžeš konto wutworić.',

# Developer tools
'lockdb'              => 'Datowu banku zamknyć',
'unlockdb'            => 'Datowu banku wotamknyć',
'lockdbtext'          => 'Zamknjenje datoweje banki znjemóžni wšěm wužiwarjam strony wobdźěłać, jich nastajenja změnić, jich wobkedźbowanki wobdźěłać a wšě druhe dźěła činić, kotrež sej změny w datowej bance žadaja. Prošu wobkruć, zo chceš datowu banku woprawdźe zamknyć a zo chceš ju zaso wotamknyć, hdyž wothladowanje je sčinjene.',
'unlockdbtext'        => 'Wotamknjenje datoweje banki zaso wšěm wužiwarjam zmóžni strony wobdźěłać, jich nastajenja změnić, jich wobkedźbowanki wobdźěłać a wšě druhe dźěła činić, kotrež sej změny w datowej bance žadaja. Prošu wobkruć, zo chceš datowu banku woprawdźe wotamknyć.',
'lockconfirm'         => 'Haj, chcu datowu banku woprawdźe zamknyć.',
'unlockconfirm'       => 'Haj, chcu datowu banku woprawdźe wotamknyć.',
'lockbtn'             => 'Datowu banku zamknyć',
'unlockbtn'           => 'Datowu banku wotamknyć',
'locknoconfirm'       => 'Njejsy kontrolowy kašćik nakřižował.',
'lockdbsuccesssub'    => 'Datowa banka bu wuspěšnje zamknjena.',
'unlockdbsuccesssub'  => 'Datowa banka bu wuspěšnje wotamknjena.',
'lockdbsuccesstext'   => 'Datowa banka bu zamknjena.
<br />Njezabudź [[{{ns:special}}:Unlockdb|zamknjenje zaso zběhnyć]], po tym zo wothladowanje je sčinjene.',
'unlockdbsuccesstext' => 'Datowa banka bu wotamknjena.',
'lockfilenotwritable' => 'Do dataje zamknjenja datoweje banki njeda so zapisować. Za zamknjenje abo wotamknjenje datoweje banki dyrbi webowy serwer pisanske prawo měć.',
'databasenotlocked'   => 'Datajowa banka zamknjena njeje.',

# Move page
'movepage'                => 'Stronu přesunyć',
'movepagetext'            => 'Wužiwanje formulara deleka budźe stronu přemjenować, suwajo jeje cyłe stawizny pod nowe mjeno. Stary titl budźe daleposrědkowanje na nowy titl. Wotkazy na stary titl so njezměnja. Pruwuj za dwójnymi abo skóncowanymi daleposrědkowanjemi. Dyrbiš zaručić, zo wotkazy na stronu pokazuja, na kotruž dyrbja dowjesć.

Wobkedźbuj, zo strona so <b>nje</b> přesunje, jeli strona z nowym titlom hizo eksistuje, chibazo wona je prózdna abo dalesposrědkowanje a nima zašłe stawizny. To woznamjenja, zo móžeš stronu tam wróćo přemjenować, hdźež bu runje přemjenowana, jeli zmylk činiš a njemóžeš wobstejacu stronu přepisować.

<b>KEDŹBU!</b> Móže to drastiska a njewočakowana změna za woblubowanu stronu być; prošu budź sej wěsty, zo sćěwki rozumiš, prjedy hač pokročuješ.',
'movepagetalktext'        => 'Přisłušna diskusijna strona přesunje so awtomatisce hromadźe z njej, <b>chibazo:</b>
*Njeprózdna diskusijna strona pod nowym mjenom hižo eksistuje abo
*wotstronješ hóčku z kašćika deleka.

W tutych padach dyrbiš stronu manuelnje přesunyć abo zaměšeć, jeli sej to přeješ.',
'movearticle'             => 'Stronu přesunyć',
'movenologin'             => 'Njejsy přizjewjeny.',
'movenologintext'         => 'Dyrbiš zregistrowany wužiwar a [[{{ns:special}}:Userlogin|přizjewjeny]] być, zo by stronu přesunyć móhł.',
'movenotallowed'          => 'Nimaš w tutym wikiju prawo, zo by strony přesunył.',
'newtitle'                => 'pod nowe hesło',
'move-watch'              => 'Stronu wobkedźbować',
'movepagebtn'             => 'Stronu přesunyć',
'pagemovedsub'            => 'Přesunjenje wuspěšne',
'movepage-moved'          => "<big>'''Strona „$1“ bu k nowemu titlej „$2“ přesunjena.'''</big>", # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'Strona z tutym mjenom hižo eksistuje abo mjeno, kotrež sy wuzwolił, płaćiwe njeje. Prošu wuzwol druhe mjeno.',
'talkexists'              => 'Strona sama bu z wuspěchom přesunjena, diskusijna strona pak njeda so přesunyć, dokelž pod nowym titulom hižo eksistuje. Prošu změš jeju manuelnje.',
'movedto'                 => 'přesunjena do hesła',
'movetalk'                => 'Přisłušnu diskusijnu stronu tohorunja přesunyć',
'talkpagemoved'           => 'Přisłušna diskusijna strona bu tohorunja přesunjena.',
'talkpagenotmoved'        => 'Přisłušna diskusijna strona <strong>njebu</strong> přesunjena.',
'1movedto2'               => 'je [[$1]] pod hesło [[$2]] přesunył',
'1movedto2_redir'         => 'je [[$1]] pod hesło [[$2]] přesunył a při tym daleposrědkowanje přepisał.',
'movelogpage'             => 'Protokol přesunjenjow',
'movelogpagetext'         => 'Deleka je lisćina wšěch přesunjenych stronow.',
'movereason'              => 'Přičina',
'revertmove'              => 'wróćo přesunyć',
'delete_and_move'         => 'wušmórnyć a přesunyć',
'delete_and_move_text'    => '== Wušmórnjenje trěbne ==

Cilowa strona „[[$1]]” hižo eksistuje. Chceš ju wušmórnyć, zo by so přesunjenje zmóžniło?',
'delete_and_move_confirm' => 'Haj, stronu wušmórnyć.',
'delete_and_move_reason'  => 'Strona bu wušmórnjena, zo by so přesunjenje zmóžniło.',
'selfmove'                => 'Žórłowy a cilowy titl stej samsnej; strona njehodźi so na sebje samu přesunyć.',
'immobile_namespace'      => 'Cilowy titl je wosebity typ; strony njehodźa so do tutoho mjenoweho ruma abo z njeho přesunyć.',

# Export
'export'            => 'Strony eksportować',
'exporttext'        => 'Móžeš tekst a stawizny wěsteje strony abo skupiny stronow, kotrež su w XML zawite, eksportować. To da so potom do druheho wikija, kotryž ze software MediaWiki dźěła, z pomocu strony {{ns:special}}:Import importować.

Zo by strony eksportował, zapodaj title deleka do tekstoweho pola, jedyn titul na linku, a wubjer hač chceš aktualnu wersiju kaž tež stare wersije z linkami stawiznow strony abo jenož aktualnu wersiju z informacijemi wo poslednjej změnje eksportować.

W poslednim padźe móžeš tež wotkaz wužiwać, na př. „[[{{ns:special}}:Export/{{int:Mainpage}}]]” za stronu „{{int:Mainpage}}”.',
'exportcuronly'     => 'Jenož aktualnu wersiju zapřijeć, nic dospołne stawizny',
'exportnohistory'   => '----
<b>Kedźbu:</b> Eksport cyłych stawiznow přez tutón formular bu z přičin wukonitosće serwera znjemóžnjeny.',
'export-submit'     => 'Eksportować',
'export-addcattext' => 'Strony z kategorije dodawać:',
'export-addcat'     => 'Dodawać',
'export-download'   => 'Jako XML-dataju składować',

# Namespace 8 related
'allmessages'               => 'Systemowe zdźělenki',
'allmessagesname'           => 'Mjeno',
'allmessagesdefault'        => 'Standardny tekst',
'allmessagescurrent'        => 'Aktualny tekst',
'allmessagestext'           => 'To je lisćina wšěch systemowych zdźělenkow, kotrež w mjenowym rumje MediaWiki k dispoziciji steja.',
'allmessagesnotsupportedDB' => 'Strona <b>{{ns:special}}:Allmessages</b> njemóže so wužiwać, dokelž je datowa banka wotpinata.',
'allmessagesfilter'         => 'Filter za jednotliwe zdźělenki:',
'allmessagesmodified'       => 'Jenož změnjene pokazać',

# Thumbnails
'thumbnail-more'           => 'powjetšić',
'missingimage'             => '<b>Pobrachowacy wobraz</b>

<i>$1</i>',
'filemissing'              => 'Dataja pobrachuje',
'thumbnail_error'          => 'Zmylk při wutworjenju miniaturki: $1',
'djvu_page_error'          => 'Strona DjVU zwonka wobłuka strony',
'djvu_no_xml'              => 'Daty XML njemóža so za dataju DjVU wotwołać',
'thumbnail_invalid_params' => 'Njepłaćiwe parametry miniaturki',
'thumbnail_dest_directory' => 'Njemóžno cilowy zapis wutworić.',

# Special:Import
'import'                     => 'Strony importować',
'importinterwiki'            => 'Import z druheho wikija',
'import-interwiki-text'      => 'Wuběr wiki a stronu k importowanju. Daty wersijow a mjena awtorow so zachowaja. Wšě transwiki-importy so w [[{{ns:special}}:Log/import|protokolu importow]] protokoluja.',
'import-interwiki-history'   => 'Wšě wersije ze stawiznow tuteje strony kopěrować',
'import-interwiki-submit'    => 'Importować',
'import-interwiki-namespace' => 'Strony importować do mjenoweho ruma:',
'importtext'                 => 'Prošu eksportuj dataju ze žórłoweho wikija wužiwajo stronu [[{{ns:special}}:Export]], składuj ju na swoju tačel a nahraj ju sem.',
'importstart'                => 'Importuju…',
'import-revision-count'      => '$1 {{PLURAL:$1|wersija|wersiji|wersije|wersijow}}',
'importnopages'              => 'Žane strony za importowanje.',
'importfailed'               => 'Import zwrěšćił: $1',
'importunknownsource'        => 'Njeznate importowe žórło',
'importcantopen'             => 'Importowa dataja njeda so wočinjeć.',
'importbadinterwiki'         => 'Wopačny interwiki-wotkaz',
'importnotext'               => 'Prózdny abo žadyn tekst',
'importsuccess'              => 'Import wuspěšny!',
'importhistoryconflict'      => 'Je konflikt ze stawiznami strony wustupił. Snano bu strona hižo prjedy importowana.',
'importnosources'            => 'Žane importowanske žórła za transwiki wubrane. Direktne nahraće stawiznow je znjemóžnjene.',
'importnofile'               => 'Žana importowanska dataja wubrana.',
'importuploaderror'          => 'Nahraće importoweje dataje zwrěšćiło. Snano je dataja wjetša hač dowolena wulkosć za nahraće.',

# Import log
'importlogpage'                    => 'Protokol importow',
'importlogpagetext'                => 'To je lisćina importowanych stronow ze stawiznami z druhich wikijow.',
'import-logentry-upload'           => 'strona [[$1]] bu přez nahraće importowana',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|wersija|wersiji|wersije|wersijow}}',
'import-logentry-interwiki'        => 'je stronu [[$1]] z druheho wikija přenjesł',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|wersija|wersiji|wersije|wersijow}} z $2 {{PLURAL:$1|importowana|importowanej|importowane|importowane}}',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'moja wužiwarska strona',
'tooltip-pt-anonuserpage'         => 'Wužiwarska strona IP-adresy, z kotrejž tuchwilu dźěłaš',
'tooltip-pt-mytalk'               => 'moja diskusijna strona',
'tooltip-pt-anontalk'             => 'Diskusija wo změnach z tuteje IP-adresy',
'tooltip-pt-preferences'          => 'moje nastajenja',
'tooltip-pt-watchlist'            => 'lisćina stronow, kotrež wobkedźbuješ',
'tooltip-pt-mycontris'            => 'lisćina mojich přinoškow',
'tooltip-pt-login'                => 'Móžeš so woměrje přizjewić, to pak zawjazowace njeje.',
'tooltip-pt-anonlogin'            => 'Móžeš so woměrje přizjewić, to pak zawjazowace njeje.',
'tooltip-pt-logout'               => 'so wotzjewić',
'tooltip-ca-talk'                 => 'diskusija wo stronje',
'tooltip-ca-edit'                 => 'Móžeš stronu wobdźěłać. Prošu wužij tłóčku „Přehlad” do składowanja.',
'tooltip-ca-addsection'           => 'nowy wotrězk k diskusiji dodać',
'tooltip-ca-viewsource'           => 'Strona je škitana. Móžeš pak jeje žórło wobhladać.',
'tooltip-ca-history'              => 'stawizny tuteje strony',
'tooltip-ca-protect'              => 'stronu škitać',
'tooltip-ca-delete'               => 'stronu wušmórnyć',
'tooltip-ca-undelete'             => 'změny wobnowić, kotrež buchu do wušmórnjenja sčinjene',
'tooltip-ca-move'                 => 'stronu přesunyć',
'tooltip-ca-watch'                => 'stronu  wobkedźbowankam přidać',
'tooltip-ca-unwatch'              => 'stronu z wobkedźbowankow wotstronić',
'tooltip-search'                  => '{{GRAMMAR:akuzatiw|{{SITENAME}}}} přepytać',
'tooltip-search-go'               => 'Dźi k stronje z runje tutym mjenom, jeli eksistuje',
'tooltip-search-fulltext'         => 'Strony za tutym tekstom přepytać',
'tooltip-p-logo'                  => 'hłowna strona',
'tooltip-n-mainpage'              => 'hłownu stronu pokazać',
'tooltip-n-portal'                => 'wo projekće, što móžeš činić, hdźe móžeš informacije namakać',
'tooltip-n-currentevents'         => 'pozadkowe informacije wo aktualnych podawkach pytać',
'tooltip-n-recentchanges'         => 'lisćina aktualnych změnow w tutym wikiju',
'tooltip-n-randompage'            => 'připadny nastawk wopytać',
'tooltip-n-help'                  => 'pomocna strona',
'tooltip-n-sitesupport'           => 'Podpěrajće nas',
'tooltip-t-whatlinkshere'         => 'lisćina wšěch stronow, kotrež sem wotkazuja',
'tooltip-t-recentchangeslinked'   => 'aktualne změny w stronach, na kotrež tuta strona wotkazuje',
'tooltip-feed-rss'                => 'RSS-feed za tutu stronu',
'tooltip-feed-atom'               => 'Atom-feed za tutu stronu',
'tooltip-t-contributions'         => 'přinoški tutoho wužiwarja wobhladać',
'tooltip-t-emailuser'             => 'wužiwarjej mejlku pósłać',
'tooltip-t-upload'                => 'dataje nahrać',
'tooltip-t-specialpages'          => 'lisćina wšěch specialnych stronow',
'tooltip-t-print'                 => 'ćišćowy napohlad tuteje strony',
'tooltip-t-permalink'             => 'trajny wotkaz k tutej wersiji strony',
'tooltip-ca-nstab-main'           => 'stronu wobhladać',
'tooltip-ca-nstab-user'           => 'wužiwarsku stronu wobhladać',
'tooltip-ca-nstab-media'          => 'datajowu stronu wobhladać',
'tooltip-ca-nstab-special'        => 'To je specialna strona. Njemóžeš ju wobdźěłać.',
'tooltip-ca-nstab-project'        => 'projektowu stronu wobhladać',
'tooltip-ca-nstab-image'          => 'wobrazowu stronu wobhladać',
'tooltip-ca-nstab-mediawiki'      => 'systemowu zdźělenku wobhladać',
'tooltip-ca-nstab-template'       => 'předłohu wobhladać',
'tooltip-ca-nstab-help'           => 'pomocnu stronu wobhladać',
'tooltip-ca-nstab-category'       => 'kategorijnu stronu wobhladać',
'tooltip-minoredit'               => 'jako snadnu změnu woznamjenić',
'tooltip-save'                    => 'změny składować',
'tooltip-preview'                 => 'twoje změny přehladnyć, prošu čiń to do składowanja!',
'tooltip-diff'                    => 'změny pokazać, kotrež sy w teksće činił',
'tooltip-compareselectedversions' => 'rozdźěle mjez wubranymaj wersijomaj tuteje strony pokazać',
'tooltip-watch'                   => 'tutu stronu wobkedźbowankam přidać',
'tooltip-recreate'                => 'stronu znowa wutworić, hačrunjež bu wumšmórnjena',
'tooltip-upload'                  => 'nahraće startować',

# Stylesheets
'common.css'   => '/* CSS w tutej dataji budźe so na wšěch stronow wuskutkować. */',
'monobook.css' => '/* CSS wobdźěłać, zo by so skin „monobook” za wšěčh wužiwarjow tutoho skina priměrił */',

# Scripts
'common.js'   => '/* Kóždy JavaScript tu so za wšěch wužiwarjow při kóždym zwobraznjenju někajkeje strony začita. */',
'monobook.js' => '/* Zestarjene; prošu [[MediaWiki:common.js]] wužiwać */',

# Metadata
'nodublincore'      => 'Dublin Core RDF metadaty su za tutón serwer znjemóžnjene.',
'nocreativecommons' => 'Creative Commons RDF metadaty su za tutón serwer znjemóžnjene.',
'notacceptable'     => 'Serwer wikija njemóže daty we formaće poskićić, kotryž twój wudawanski nastroj móže čitać.',

# Attribution
'anonymous'        => 'Anonymny wužiwar/anonymni wužiwarjo {{GRAMMAR:genitiw|{{SITENAME}}}}',
'siteuser'         => 'wužiwar {{GRAMMAR:genitiw|{{SITENAME}}}} $1',
'lastmodifiedatby' => 'Strona bu dnja $1 w $2 hodź. wot wužiwarja $3 změnjena.', # $1 date, $2 time, $3 user
'and'              => 'a',
'othercontribs'    => 'Na zakładźe dźěła wužiwarja $1.',
'others'           => 'druhich',
'siteusers'        => 'wužiwarjow {{GRAMMAR:genitiw|{{SITENAME}}}} $1',
'creditspage'      => 'Dźak awtoram',
'nocredits'        => 'Za tutu stronu žane informacije wo zasłužbach njejsu.',

# Spam protection
'spamprotectiontitle'    => 'Spamowy filter',
'spamprotectiontext'     => 'Strona, kotruž sy spytał składować, bu přez spamowy filter zablokowana. Přičina je najskerje wotkaz na eksterne sydło.',
'spamprotectionmatch'    => 'Sćěhowacy tekst je naš spamowy filter wotpokazał: $1',
'subcategorycount'       => 'Tuta kategorija wobsahuje $1 {{PLURAL:$1|podkategoriju|podkategoriji|podkategorije|podkategorijow}}.',
'categoryarticlecount'   => 'Tuta kategorija wobsahuje $1 {{PLURAL:$1|nastawk|nastawkaj|nastawki|nastawkow}}.',
'category-media-count'   => 'Tuta kategorija wobsahuje $1 {{PLURAL:$1|dataju|dataji|dataje|datajow}}.',
'listingcontinuesabbrev' => ' (pokročowane)',
'spambot_username'       => 'MediaWiki čisćenje wot spama',
'spam_reverting'         => 'wróćo na poslednju wersiju, kotraž wotkazy na $1 njewobsahuje',
'spam_blanking'          => 'Wšě wersije wobsahowachu wotkazy na $1, wučisćene.',

# Info page
'infosubtitle'   => 'Informacije za stronu',
'numedits'       => 'Ličba změnow (nastawk): $1',
'numtalkedits'   => 'Ličba změnow (diskusijna strona): $1',
'numwatchers'    => 'Ličba wobkedźbowarjow: $1',
'numauthors'     => 'Ličba rozdźělnych awtorow (nastawk): $1',
'numtalkauthors' => 'Ličba rozdźělnych awtorow (diskusijna strona): $1',

# Math options
'mw_math_png'    => 'Přeco jako PNG zwobraznić',
'mw_math_simple' => 'HTML jeli jara jednory, hewak PNG',
'mw_math_html'   => 'HTML jeli móžno, hewak PNG',
'mw_math_source' => 'Jako TeX wostajić (za tekstowe wobhladowaki)',
'mw_math_modern' => 'Za moderne wobhladowaki doporučene',
'mw_math_mathml' => 'MathML jeli móžno (eksperimentalnje)',

# Patrolling
'markaspatrolleddiff'                 => 'Změnu jako přepruwowanu woznamjenić',
'markaspatrolledtext'                 => 'Tutu změnu nastawka jako přepruwowanu woznamjenić',
'markedaspatrolled'                   => 'Změna bu jako přepruwowana woznamjenjena.',
'markedaspatrolledtext'               => 'Wubrana wersija bu jako přepruwowana woznamjenjena.',
'rcpatroldisabled'                    => 'Přepruwowanje aktualnych změnow je znjemóžnjene.',
'rcpatroldisabledtext'                => 'Funkcija přepruwowanja aktualnych změnow je tuchwilu znjemóžnjena.',
'markedaspatrollederror'              => 'Njemóžno jako přepruwowanu woznamjenić.',
'markedaspatrollederrortext'          => 'Dyrbiš wersiju podać, kotraž so ma jako přepruwowana woznamjenić.',
'markedaspatrollederror-noautopatrol' => 'Njesměš swoje změny jako přepruwowane woznamjenjeć.',

# Patrol log
'patrol-log-page' => 'Protokol přepruwowanjow',
'patrol-log-line' => 'je $1 strony $2 jako přepruwowanu markěrował $3.',
'patrol-log-auto' => '(awtomatisce)',
'patrol-log-diff' => 'wersiju $1',

# Image deletion
'deletedrevision'                 => 'Stara wersija $1 wušmórnjena',
'filedeleteerror-short'           => 'Zmylk při zničenju dataje: $1',
'filedeleteerror-long'            => 'Buchu zmylki při zničenju dataje zwěsćene:

$1',
'filedelete-missing'              => 'Dataja "$1" njeda so zničić, dokelž njeeksistuje.',
'filedelete-old-unregistered'     => 'Podata datajowa wersija "$1" w datowej bance njeje.',
'filedelete-current-unregistered' => 'Podata dataja "$1" w datowej bance njeje.',
'filedelete-archive-read-only'    => 'Do archiwoweho zapisa "$1" njeda so z webowym serwerom pisać.',

# Browsing diffs
'previousdiff' => '← předchadna wersija',
'nextdiff'     => 'přichodna wersija →',

# Media information
'mediawarning'         => '<b>KEDŹBU:</b> Dataja móhła złowólny kod wobsahować, kotrehož wuwjedźenje móhło twój system wobškodźić.<hr />',
'imagemaxsize'         => 'Wobrazy na stronach wobrazoweho wopisanja wobmjezować na:',
'thumbsize'            => 'Wulkosć miniaturkow (thumbnails):',
'widthheight'          => '$1x$2',
'widthheightpage'      => '$1×$2, $3 stronow',
'file-info'            => 'Wulkosć dataje: $1, družina MIME: $2',
'file-info-size'       => '($1 × $2 pikselow, wulkosć dataje: $3, družina MIME: $4)',
'file-nohires'         => '<small>Za tutu dataju žane wyše rozeznaće njeje.</small>',
'svg-long-desc'        => '(SVG-dataja, zakładna wulkosć: $1 × $2 pikselow, datajowa wulkosć: $3)',
'show-big-image'       => 'Wersija z wyšim rozeznaćom',
'show-big-image-thumb' => '<small>Wulkosć miniaturki: $1 × $2 pikselow</small>',

# Special:Newimages
'newimages'         => 'Nowe dataje',
'newimages-summary' => 'Tuta specialna strona naliči aktualnje nahrate wobrazy a druhe dataje.',
'showhidebots'      => '(bots $1)',
'noimages'          => 'Žane dataje.',

# Bad image list
'bad_image_list' => 'Format:

Jenož zapiski lisćiny (linki, kotrež so z * započinaja), so wobkedźbuja. Prěni wotkaz na lince dyrbi wotkaz k njewitanemu wobrazej być.
Nasledne wotkazy na samsnej lince definuja wuwzaća, hdźež so wobraz smě najebać toho jewić.',

# Metadata
'metadata'          => 'Metadaty',
'metadata-help'     => 'Dataja wobsahuje přidatne informacije, kotrež pochadźa z digitalneje kamery abo skenera. Jeli dataja bu wot toho změnjena je móžno, zo někotre podrobnosće z nětčišeho stawa wotchila.',
'metadata-expand'   => 'Podrobnosće pokazać',
'metadata-collapse' => 'Podrobnosće schować',
'metadata-fields'   => 'Sćěhowace EXIF-metadaty so standardnje pokazuja. Druhe so po standardźe schowaja a móža so z tabele rozfałdować.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Šěrokosć',
'exif-imagelength'                 => 'Wysokosć',
'exif-bitspersample'               => 'Bitow na barbowu komponentu',
'exif-compression'                 => 'Metoda kompresije',
'exif-photometricinterpretation'   => 'Zestajenje pikselow',
'exif-orientation'                 => 'Wusměrjenje kamery',
'exif-samplesperpixel'             => 'Ličba komponentow',
'exif-planarconfiguration'         => 'Porjad datow',
'exif-ycbcrsubsampling'            => 'Poměr podwotmasanja (Subsampling) wot Y do C',
'exif-ycbcrpositioning'            => 'Zaměstnjenje Y a C',
'exif-xresolution'                 => 'Wodorune rozeznaće',
'exif-yresolution'                 => 'Padorune rozeznaće',
'exif-resolutionunit'              => 'Jednotka rozeznaća X a Y',
'exif-stripoffsets'                => 'Městno wobrazowych datow',
'exif-rowsperstrip'                => 'Ličba rjadkow na pas',
'exif-stripbytecounts'             => 'Bajty na komprimowany pas',
'exif-jpeginterchangeformat'       => 'Offset k JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Bajty JPEG datow',
'exif-transferfunction'            => 'Přenošowanska funkcija',
'exif-whitepoint'                  => 'Barbowa kwalita běłeho dypka',
'exif-primarychromaticities'       => 'Barbowa kwalita primarnych barbow',
'exif-ycbcrcoefficients'           => 'Koeficienty matriksy za transformaciju barbneho ruma',
'exif-referenceblackwhite'         => 'Por čorneje a běłeje referencneje hódnoty',
'exif-datetime'                    => 'Datum a čas datajoweje změny',
'exif-imagedescription'            => 'Titl wobraza',
'exif-make'                        => 'Zhotowjer kamery',
'exif-model'                       => 'Model kamery',
'exif-software'                    => 'Wužiwana softwara',
'exif-artist'                      => 'Awtor',
'exif-copyright'                   => 'Mějićel awtorskich prawow',
'exif-exifversion'                 => 'Wersija EXIF',
'exif-flashpixversion'             => 'Podpěrowana wersija Flashpix',
'exif-colorspace'                  => 'Barbny rum',
'exif-componentsconfiguration'     => 'Woznam kóždeje komponenty',
'exif-compressedbitsperpixel'      => 'Modus wobrazoweje kompresije',
'exif-pixelydimension'             => 'Płaćiwa šěrokosć wobraza',
'exif-pixelxdimension'             => 'Płaćiwa wysokosć wobraza',
'exif-makernote'                   => 'Přispomnjenki zhotowjerja',
'exif-usercomment'                 => 'Přispomjenja wužiwarja',
'exif-relatedsoundfile'            => 'Zwjazana zynkowa dataja',
'exif-datetimeoriginal'            => 'Datum a čas wutworjenja datow',
'exif-datetimedigitized'           => 'Datum a čas digitalizowanja',
'exif-subsectime'                  => 'Dźěle sekundy za DateTime',
'exif-subsectimeoriginal'          => 'Dźěle sekundy za DateTimeOriginal',
'exif-subsectimedigitized'         => 'Dźěle sekundy za DateTimeDigitized',
'exif-exposuretime'                => 'Naswětlenski čas',
'exif-exposuretime-format'         => '$1 sek. ($2)',
'exif-fnumber'                     => 'Zasłona',
'exif-exposureprogram'             => 'Naswětlenski program',
'exif-spectralsensitivity'         => 'Spektralna cutliwosć',
'exif-isospeedratings'             => 'Cutliwosć filma abo sensora (ISO)',
'exif-oecf'                        => 'Optoelektroniski přeličenski faktor (OECF)',
'exif-shutterspeedvalue'           => 'Naswětlenski čas',
'exif-aperturevalue'               => 'Zasłona',
'exif-brightnessvalue'             => 'Swětłosć',
'exif-exposurebiasvalue'           => 'Naswětlenska korektura',
'exif-maxaperturevalue'            => 'Najwjetša zasłona',
'exif-subjectdistance'             => 'Zdalenje k předmjetej',
'exif-meteringmode'                => 'Měrjenska metoda',
'exif-lightsource'                 => 'Žórło swěcy',
'exif-flash'                       => 'Błysk',
'exif-focallength'                 => 'Palnišćowa zdalenosć',
'exif-subjectarea'                 => 'Wobwod předmjeta',
'exif-flashenergy'                 => 'Sylnosć błyska',
'exif-spatialfrequencyresponse'    => 'Cutliwosć rumoweje frekwency',
'exif-focalplanexresolution'       => 'Wodorune rozeznaće sensora',
'exif-focalplaneyresolution'       => 'Padorune rozeznaće sensora',
'exif-focalplaneresolutionunit'    => 'Jednotka rozeznaća sensora',
'exif-subjectlocation'             => 'Městno předmjeta',
'exif-exposureindex'               => 'Naswětlenski indeks',
'exif-sensingmethod'               => 'Měrjenska metoda',
'exif-filesource'                  => 'Žórło dataje',
'exif-scenetype'                   => 'Typ sceny',
'exif-cfapattern'                  => 'Muster CFA',
'exif-customrendered'              => 'Wot wužiwarja definowane předźěłanje wobrazow',
'exif-exposuremode'                => 'Naswětlenski modus',
'exif-whitebalance'                => 'Balansa běłeho dypka',
'exif-digitalzoomratio'            => 'Digitalny zoom',
'exif-focallengthin35mmfilm'       => 'Palnišćowa zdalenosć za film 35 mm přeličena',
'exif-scenecapturetype'            => 'Družina sceny',
'exif-gaincontrol'                 => 'Regulowanje sceny',
'exif-contrast'                    => 'Kontrast',
'exif-saturation'                  => 'Nasyćenosć',
'exif-sharpness'                   => 'Wótrosć',
'exif-devicesettingdescription'    => 'Nastajenja nastroja',
'exif-subjectdistancerange'        => 'Zdalenosć k motiwej',
'exif-imageuniqueid'               => 'ID wobraza',
'exif-gpsversionid'                => 'Wersija ID GPS',
'exif-gpslatituderef'              => 'Sewjerna abo južna šěrina',
'exif-gpslatitude'                 => 'Geografiska šěrina',
'exif-gpslongituderef'             => 'Wuchodna abo zapadna dołhosć',
'exif-gpslongitude'                => 'Geografiska dołhosć',
'exif-gpsaltituderef'              => 'Referencna wyšina',
'exif-gpsaltitude'                 => 'Wyšina',
'exif-gpstimestamp'                => 'Čas GPS (atomowy časnik)',
'exif-gpssatellites'               => 'Satelity wužiwane za měrjenje',
'exif-gpsstatus'                   => 'Status přijimaka',
'exif-gpsmeasuremode'              => 'Měrjenska metoda',
'exif-gpsdop'                      => 'Měrjenska dokładnosć',
'exif-gpsspeedref'                 => 'Jednotka spěšnosće',
'exif-gpsspeed'                    => 'Spěšnosć přijimaka GPS',
'exif-gpstrackref'                 => 'Referenca za směr pohiba',
'exif-gpstrack'                    => 'Směr pohiba',
'exif-gpsimgdirectionref'          => 'Referenca za wusměrjenje wobraza',
'exif-gpsimgdirection'             => 'Wobrazowy směr',
'exif-gpsmapdatum'                 => 'Wužiwane geodetiske daty',
'exif-gpsdestlatituderef'          => 'Referenca za šěrinu',
'exif-gpsdestlatitude'             => 'Šěrina',
'exif-gpsdestlongituderef'         => 'Referenca dołhosće',
'exif-gpsdestlongitude'            => 'Dołhosć',
'exif-gpsdestbearingref'           => 'Referenca za wusměrjenje',
'exif-gpsdestbearing'              => 'Wusměrjenje',
'exif-gpsdestdistanceref'          => 'Referenca za zdalenosć k cilej',
'exif-gpsdestdistance'             => 'Zdalenosć k cilej',
'exif-gpsprocessingmethod'         => 'Metoda předźěłanja GPS',
'exif-gpsareainformation'          => 'Mjeno wobwoda GPS',
'exif-gpsdatestamp'                => 'Datum GPS',
'exif-gpsdifferential'             => 'Diferencialna korektura GPS',

# EXIF attributes
'exif-compression-1' => 'Njekomprimowany',

'exif-unknowndate' => 'Njeznaty datum',

'exif-orientation-1' => 'Normalnje', # 0th row: top; 0th column: left
'exif-orientation-2' => 'Wodorunje wobroćeny', # 0th row: top; 0th column: right
'exif-orientation-3' => '180° zwjertnjeny', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Padorunje wobroćeny', # 0th row: bottom; 0th column: left
'exif-orientation-5' => '90° přećiwo směrej časnika zwjertneny a padorunje wobroćeny', # 0th row: left; 0th column: top
'exif-orientation-6' => '90° w směrje časnika zwjertnjeny', # 0th row: right; 0th column: top
'exif-orientation-7' => '90° w směrje časnika zwjertnjeny a padorunje wobroćeny', # 0th row: right; 0th column: bottom
'exif-orientation-8' => '90° přećiwo směrej časnika zwjertnjeny', # 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'Škropawy format',
'exif-planarconfiguration-2' => 'Płony format',

'exif-componentsconfiguration-0' => 'Njeeksistuje',

'exif-exposureprogram-0' => 'Njeznaty',
'exif-exposureprogram-1' => 'Manuelny',
'exif-exposureprogram-2' => 'Normalny program',
'exif-exposureprogram-3' => 'Priorita zasłony',
'exif-exposureprogram-4' => 'Priorita zawěrki',
'exif-exposureprogram-5' => 'Kreatiwny program (za hłubokosć wótrosće)',
'exif-exposureprogram-6' => 'Akciski program (za wyšu spěšnosć zawěrki)',
'exif-exposureprogram-7' => 'Portretowy modus (za fota z blikosće z pozadkom zwonka fokusa)',
'exif-exposureprogram-8' => 'Krajinowy modus (za fota krajinow z pozadkom we fokusu)',

'exif-subjectdistance-value' => '$1 m',

'exif-meteringmode-0'   => 'Njeznata',
'exif-meteringmode-1'   => 'Přerězk',
'exif-meteringmode-2'   => 'Srjedźa wusměrjeny',
'exif-meteringmode-3'   => 'Spotowe měrjenje',
'exif-meteringmode-4'   => 'Multispot',
'exif-meteringmode-6'   => 'Dźělna',
'exif-meteringmode-255' => 'Druha',

'exif-lightsource-0'   => 'Njeznata',
'exif-lightsource-1'   => 'Dnjowe swětło',
'exif-lightsource-2'   => 'Fluorescentne',
'exif-lightsource-3'   => 'Žehlawka',
'exif-lightsource-4'   => 'Błysk',
'exif-lightsource-9'   => 'Rjane wjedro',
'exif-lightsource-10'  => 'Pomróčene',
'exif-lightsource-11'  => 'Sćin',
'exif-lightsource-12'  => 'Dnjowe swětło fluoreskowace (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Dnjowoběły fluoreskowacy (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Zymnoběły fluoreskowacy (W 3900 – 4500K)',
'exif-lightsource-15'  => 'běły fluoroskowacy (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Standardne swětło A',
'exif-lightsource-18'  => 'Standardne swětło B',
'exif-lightsource-19'  => 'Standardne swětło C',
'exif-lightsource-24'  => 'ISO studijowa wolframowa žehlawka',
'exif-lightsource-255' => 'Druhe žórło swětła',

'exif-focalplaneresolutionunit-2' => 'cól',

'exif-sensingmethod-1' => 'Njedefinowany',
'exif-sensingmethod-2' => 'Jednočipowy barbowy přestrjenjowy sensor',
'exif-sensingmethod-3' => 'Dwučipowy barbowy přestrjenjowy sensor',
'exif-sensingmethod-4' => 'Třičipowy barbowy přestrjenjowy sensor',
'exif-sensingmethod-5' => 'Sekwencielny barbowy přestrjenjowy sensor',
'exif-sensingmethod-7' => 'Třilinearny sensor',
'exif-sensingmethod-8' => 'Barbowy sekwencielny linearny sensor',

'exif-scenetype-1' => 'Direktnje fotografowany wobraz',

'exif-customrendered-0' => 'Normalne wobdźěłanje',
'exif-customrendered-1' => 'Wužiwarske wobdźěłanje',

'exif-exposuremode-0' => 'Awtomatiske naswětlenje',
'exif-exposuremode-1' => 'Manuelne naswětlenje',
'exif-exposuremode-2' => 'Rjad naswětlenjow (Bracketing)',

'exif-whitebalance-0' => 'Automatiske wurunanje běłeho',
'exif-whitebalance-1' => 'Manuelne wurunanje běłeho',

'exif-scenecapturetype-1' => 'Krajina',
'exif-scenecapturetype-2' => 'Portret',
'exif-scenecapturetype-3' => 'Nócna scena',

'exif-gaincontrol-0' => 'Žane',
'exif-gaincontrol-1' => 'Snadne',
'exif-gaincontrol-2' => 'Wysoke zesylnjenje',
'exif-gaincontrol-3' => 'Niske wosłabjenje',
'exif-gaincontrol-4' => 'Wysoke wosłabjenje',

'exif-contrast-0' => 'Normalny',
'exif-contrast-1' => 'Mjechki',
'exif-contrast-2' => 'Sylny',

'exif-saturation-0' => 'Normalna nasyćenosć',
'exif-saturation-1' => 'Niska nasyćenosć',
'exif-saturation-2' => 'Wysoka nasyćenosć',

'exif-sharpness-0' => 'Normalna',
'exif-sharpness-1' => 'Mjechka',
'exif-sharpness-2' => 'Sylna',

'exif-subjectdistancerange-0' => 'Njeznata',
'exif-subjectdistancerange-2' => 'Bliski pohlad',
'exif-subjectdistancerange-3' => 'Zdaleny pohlad',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Sewjerna šěrina',
'exif-gpslatitude-s' => 'Južna šěrina',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Wuchodna dołhosć',
'exif-gpslongitude-w' => 'Zapadna dołhosć',

'exif-gpsstatus-a' => 'Měrjenje běži',
'exif-gpsstatus-v' => 'Interoperabilita měrjenja',

'exif-gpsmeasuremode-2' => 'dwudimensionalne měrjenje',
'exif-gpsmeasuremode-3' => 'třidimensionalne měrjenje',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-m' => 'mila/h',
'exif-gpsspeed-n' => 'Suki',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Woprawdźity směr',
'exif-gpsdirection-m' => 'Magnetiski směr',

# External editor support
'edit-externally'      => 'Dataju z eksternym programom wobdźěłać',
'edit-externally-help' => 'Hlej [http://meta.wikimedia.org/wiki/Help:External_editors pokiwy za instalaciju] za dalše informacije.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'wšě',
'imagelistall'     => 'wšě',
'watchlistall2'    => 'wšě',
'namespacesall'    => 'wšě',
'monthsall'        => 'wšě',

# E-mail address confirmation
'confirmemail'            => 'Emailowu adresu wobkrućić',
'confirmemail_noemail'    => 'Njejsy płaćiwu e-mejlowu adresu w swojich [[{{ns:special}}:Preferences|nastajenjach]] zapodał.',
'confirmemail_text'       => 'Tutón wiki žada, zo swoju e-mejlowu adresu wobkrućiš, prjedy hač e-mejlowe funkcije wužiješ. Zaktiwuzij tłóčatko deleka, zo by swojej adresy wobkrućensku mejlku pósłał. Mejlka zapřijmje wotkaz, kotryž kod wobsahuje; wočiń wotkaz we swojim wobhladowaku, zo by wobkrućił, zo twoja e-mejlowa adresa je płaćiwa.',
'confirmemail_pending'    => '<div class="error"> Potwjerdźenski kod bu hižo z e-mejlu připósłany. Jeli sy runje swoje konto wutworił, wočakaj prošu někotre mjeńšiny, prjedy hač sej nowy kod žadaš.</div>',
'confirmemail_send'       => 'Wobkrućenski kod pósłać',
'confirmemail_sent'       => 'Wobkrućenska mejlka bu wotesłana.',
'confirmemail_oncreate'   => 'Wobkrućenski kod bu na twoju e-mejlowu adresu pósłany. Tutón kod za přizjewjenje trěbne njeje, trjebaš jón pak, zo by e-mejlowe funkcije we wikiju aktiwizował.',
'confirmemail_sendfailed' => 'Wobkrućenska mejlka njeda so wotesłać. Přepruwuj adresu za njepłaćiwymi znamješkami. E-mejlowy program wotmołwi: $1',
'confirmemail_invalid'    => 'Njepłaćiwy wobkrućacy kod. Kod je snano spadnył.',
'confirmemail_needlogin'  => 'Dyrbiš so $1, zo by e-mejlowu adresu wobkrućić móhł.',
'confirmemail_success'    => 'Twoja e-mejlowa adresa bu wobkrućena. Móžeš so nětko přizjewić.',
'confirmemail_loggedin'   => 'Twoja e-mejlowa adresu bu nětko wobkrućena.',
'confirmemail_error'      => 'Zmylk při wobkrućenju twojeje e-mailoweje adresy.',
'confirmemail_subject'    => '{{SITENAME}} – wobkrućenje e-mejloweje adresy',
'confirmemail_body'       => 'Něchtó, najskerje ty z IP-adresu $1, je wužiwarske konto „$2” z tutej e-mejlowej adresu we {{GRAMMAR:lokatiw|{{SITENAME}}}} wutworił.

Zo by so wobkrućiło, zo tute konto woprawdźe tebi słuša a zo bychu so e-mejlowe funkcije we {{GRAMMAR:lokatiw|{{SITENAME}}}} zaktiwizowali, wočiń tutón wotkaz w swojim wobhladowaku: $3.

Jeli to *njejsy*, njeslěduj wotkaz. Tutón wobkrućenski kod spadnje dnja $4.

-- 
{{SITENAME}}: {{fullurl:{{Mediawiki:mainpage}}}}',

# Scary transclusion
'scarytranscludedisabled' => '[Zapřijeće mjezyrěčnych wotkazow je znjemóžnjene]',
'scarytranscludefailed'   => '[Zapřijeće předłohi za stronu $1 njebě mózno]',
'scarytranscludetoolong'  => '[Bohužel běše URL předołhi]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">Trackbacks za tutón nastawk:<br />
$1</div>',
'trackbackremove'   => '([$1 wušmórnyć])',
'trackbackdeleteok' => 'Trackback bu wuspěšnje wušmórnjeny.',

# Delete conflict
'deletedwhileediting' => '<b>Kedźbu:</b> Strona bu wušmórnjena po tym zo sy započał ju wobdźěłać!',
'confirmrecreate'     => 'Wužiwar [[{{ns:user}}:$1|$1]] ([[{{ns:user_talk}}:$1|diskusija]]) je stronu wušmórnył po tym zo sy započał ju wobdźěłać z přičinu:
: <i>$2</i>
Prošu wobkruć, zo chceš ju woprawdźe znowa wutworić.',
'recreate'            => 'Znowa wutworić',

# HTML dump
'redirectingto' => 'Posrědkuju k stronje [[$1]]',

# action=purge
'confirm_purge'        => 'Pufrowak strony wuprózdnić? $1',
'confirm_purge_button' => 'W porjadku',

# AJAX search
'searchcontaining' => 'Strony pytać, kotrež <i>$1</i> wobsahuja.',
'searchnamed'      => 'Strony pytać, w kotrychž titlach so <i>$1</i> jewi.',
'articletitles'    => 'Strony pytać, kotrež so z <i>$1</i> započinaja',
'hideresults'      => 'Wuslědki schować',

# Multipage image navigation
'imgmultipageprev'   => '← předchadna strona',
'imgmultipagenext'   => 'přichodna strona →',
'imgmultigo'         => 'Dźi!',
'imgmultigotopre'    => 'dźi k stronje',
'imgmultiparseerror' => 'Dataja so zda wobškodźena być, tak zo {{SITENAME}} njemóže lisćinu stronow wutworić.',

# Table pager
'ascending_abbrev'         => 'postupowacy',
'descending_abbrev'        => 'zestupowacy',
'table_pager_next'         => 'přichodna strona',
'table_pager_prev'         => 'předchadna strona',
'table_pager_first'        => 'prěnja strona',
'table_pager_last'         => 'poslednja strona',
'table_pager_limit'        => '$1 {{PLURAL:$1|wuslědk|wuslědkaj|wuslědki|wuslědkow}} na stronu pokazać',
'table_pager_limit_submit' => 'OK',
'table_pager_empty'        => 'Žane wuslědki',

# Auto-summaries
'autosumm-blank'   => 'Strona bu wuprózdnjena',
'autosumm-replace' => "Strona bu z hinašim tekstom přepisana: '$1'",
'autoredircomment' => 'posrědkuju k stronje „[[$1]]”',
'autosumm-new'     => 'nowa strona: $1',

# Size units
'size-kilobytes' => '$1 kB',

# Live preview
'livepreview-loading' => 'Čita so…',
'livepreview-ready'   => 'Začitanje… Hotowe!',
'livepreview-failed'  => 'Dynamiski přehlad njemóžno!
Spytaj normalny přehlad.',
'livepreview-error'   => 'Zwisk njemóžno: $1 "$2"
Spytaj normalny přehlad.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Wobdźěłanja zašłych $1 sekundow so w tutej lisćinje hišće njezwobraznjeja.',
'lag-warn-high'   => 'Wućeženja datoweje banki dla so wobdźěłanja zašłych $1 sekundow w tutej lisćinje hišće njezwobraznjeja.',

# Watchlist editor
'watchlistedit-numitems'       => 'Twoje wobkedźbowanki wobsahuja {{PLURAL:$1|1 zapisk|$1 zapiskaj|$1 zapiski|$1 zapiskow}}, diskusijne strony njejsu ličene.',
'watchlistedit-noitems'        => 'Twoje wobkedźbowanki su prózdne.',
'watchlistedit-normal-title'   => 'Wobkedźbowanki wobdźěłać',
'watchlistedit-normal-legend'  => 'Zapiski z wobkedźbowankow wotstronić',
'watchlistedit-normal-explain' => 'Tu su zapiski z twojich wobkedźbowankow. Zo by zapiski wušmórnył, markěruj kašćiki pódla zapiskow a klikń na „Zapiski wušmórnyć“. Móžeš tež swoje wobkedźbowanki [[{{ns:special}}:Watchlist/raw|w lisćinowym formaće wobdźěłać]]
	abo je [[{{ns:special}}:Watchlist/clear|dospołnje wušmórnyć]].',
'watchlistedit-normal-submit'  => 'Zapiski wotstronić',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 zapisk bu|$1 zapiskaj buštej|$1 zapiski buchu|$1 zapiskow  buchu}} z twojich wobkedźbowankow {{PLURAL:$1|wotstronjeny|wotstronjenej|wotstronjene|wotstronjene}}:',
'watchlistedit-raw-title'      => 'Wobkedźbowanki w lisćinowym formaće wobdźěłać',
'watchlistedit-raw-legend'     => 'Wobkedźbowanki w lisćinowym formaće wobdźěłać',
'watchlistedit-raw-explain'    => 'To su twoje wobkedźbowanki w lisćinowym formaće. Zapiski hodźa so po linkach wušmórnyć abo přidać.
	Na linku je jedyn zapisk dowoleny. Hdyž sy hotowy, klikń na „wobkedźbowanki składować“.
	Móžeš tež [[{{ns:special}}:Watchlist/edit|standardnu wobdźěłowansku stronu]] wužiwać.',
'watchlistedit-raw-titles'     => 'Zapiski:',
'watchlistedit-raw-submit'     => 'Wobkedźbowanki składować',
'watchlistedit-raw-done'       => 'Twoje wobkedźbowanki buchu składowane.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 zapisk bu dodaty|$1 zapiskaj buštej dodatej|$1 zapiski buchu dodate|$1 zapiskow buchu dodate}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 zapisk bu wotstronjeny|$1 zapiskaj buštej wotstronjenej|$1 zapiski buchu wotstronjene|$1 zapiskow buchu wotstronjene}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Wobkedźbowanki: Změny',
'watchlisttools-edit' => 'normalnje wobdźěłać',
'watchlisttools-raw'  => 'Lisćinowy format wobdźěłać (import/eksport)',

# Iranian month names
'iranian-calendar-m1'  => '1. měsac Jalāli',
'iranian-calendar-m2'  => '2. měsac Jalāli',
'iranian-calendar-m3'  => '3. měsac Jalāli',
'iranian-calendar-m4'  => '4. měsac Jalāli',
'iranian-calendar-m5'  => '5. měsac Jalāli',
'iranian-calendar-m6'  => '6. měsac Jalāli',
'iranian-calendar-m7'  => '7. měsac Jalāli',
'iranian-calendar-m8'  => '8. měsac Jalāli',
'iranian-calendar-m9'  => '9. měsac Jalāli',
'iranian-calendar-m10' => '10. měsac Jalāli',
'iranian-calendar-m11' => '11. měsac Jalāli',
'iranian-calendar-m12' => '12. měsac Jalāli',

);

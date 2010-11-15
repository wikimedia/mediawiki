<?php
/** Upper Sorbian (Hornjoserbsce)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Dundak
 * @author J budissin
 * @author Michawiki
 * @author Tchoř
 * @author Tlustulimu
 * @author לערי ריינהארט
 */

$fallback = 'de';

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Specialnje',
	NS_TALK             => 'Diskusija',
	NS_USER             => 'Wužiwar',
	NS_USER_TALK        => 'Diskusija_z_wužiwarjom',
	NS_PROJECT_TALK     => '$1_diskusija',
	NS_FILE             => 'Dataja',
	NS_FILE_TALK        => 'Diskusija_k_dataji',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_diskusija',
	NS_TEMPLATE         => 'Předłoha',
	NS_TEMPLATE_TALK    => 'Diskusija_k_předłoze',
	NS_HELP             => 'Pomoc',
	NS_HELP_TALK        => 'Pomoc_diskusija',
	NS_CATEGORY         => 'Kategorija',
	NS_CATEGORY_TALK    => 'Diskusija_ke_kategoriji',
);

$namespaceAliases = array(
	'Wobraz' => NS_FILE,
	'Diskusija_k_wobrazej' => NS_FILE_TALK,
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Dwójne_daleposrědkowanja' ),
	'BrokenRedirects'           => array( 'Skóncowane_daleposrědkowanja' ),
	'Disambiguations'           => array( 'Rozjasnjenja_wjazmyslnosće' ),
	'Userlogin'                 => array( 'Přizwjewić' ),
	'Userlogout'                => array( 'Wotzjewić' ),
	'CreateAccount'             => array( 'Konto_wutworić' ),
	'Preferences'               => array( 'Nastajenja' ),
	'Watchlist'                 => array( 'Wobkedźbowanki' ),
	'Recentchanges'             => array( 'Aktualne_změny' ),
	'Upload'                    => array( 'Nahraće' ),
	'Listfiles'                 => array( 'Dataje' ),
	'Newimages'                 => array( 'Nowe_dataje' ),
	'Listusers'                 => array( 'Wužiwarjo' ),
	'Listgrouprights'           => array( 'Prawa_skupinow' ),
	'Statistics'                => array( 'Statistika' ),
	'Randompage'                => array( 'Připadna_strona' ),
	'Lonelypages'               => array( 'Wosyroćene_strony' ),
	'Uncategorizedpages'        => array( 'Njekategorizowane_strony' ),
	'Uncategorizedcategories'   => array( 'Njekategorizowane_kategorije' ),
	'Uncategorizedimages'       => array( 'Njekategorizowane_dataje' ),
	'Uncategorizedtemplates'    => array( 'Njekategorizowane_předłohi' ),
	'Unusedcategories'          => array( 'Njewužiwane_kategorije' ),
	'Unusedimages'              => array( 'Njewužiwane_dataje' ),
	'Wantedpages'               => array( 'Požadane_strony' ),
	'Wantedcategories'          => array( 'Požadane_kategorije' ),
	'Wantedfiles'               => array( 'Falowace_dataje' ),
	'Wantedtemplates'           => array( 'Falowace_předłohi' ),
	'Mostlinked'                => array( 'Z_najwjace_stronami_zwjazane_strony' ),
	'Mostlinkedcategories'      => array( 'Najhusćišo_wužiwane_kategorije' ),
	'Mostlinkedtemplates'       => array( 'Najhusćišo_wužiwane_předłohi' ),
	'Mostimages'                => array( 'Z_najwjace_stronami_zwjazane_dataje' ),
	'Mostcategories'            => array( 'Strony_z_najwjace_kategorijemi' ),
	'Mostrevisions'             => array( 'Strony_z_najwjace_wersijemi' ),
	'Fewestrevisions'           => array( 'Strony_z_najmjenje_wersijemi' ),
	'Shortpages'                => array( 'Najkrótše_strony' ),
	'Longpages'                 => array( 'Najdlěše_strony' ),
	'Newpages'                  => array( 'Nowe_strony' ),
	'Ancientpages'              => array( 'Najstarše_strony' ),
	'Deadendpages'              => array( 'Strony_bjez_wotkazow' ),
	'Protectedpages'            => array( 'Škitane_strony' ),
	'Protectedtitles'           => array( 'Škitane_titule' ),
	'Allpages'                  => array( 'Wšě_strony' ),
	'Prefixindex'               => array( 'Prefiksindeks' ),
	'Ipblocklist'               => array( 'Blokowane_IP-adresy' ),
	'Specialpages'              => array( 'Specialne_strony' ),
	'Contributions'             => array( 'Přinoški' ),
	'Emailuser'                 => array( 'E-Mejl' ),
	'Confirmemail'              => array( 'E-Mejl_potwjerdźić' ),
	'Whatlinkshere'             => array( 'Lisćina_wotkazow' ),
	'Recentchangeslinked'       => array( 'Změny_zwjazanych_stronow' ),
	'Movepage'                  => array( 'Přesunyć' ),
	'Blockme'                   => array( 'Blokowanje_proksijow' ),
	'Booksources'               => array( 'Pytanje_po_ISBN' ),
	'Categories'                => array( 'Kategorije' ),
	'Export'                    => array( 'Eksport' ),
	'Version'                   => array( 'Wersija' ),
	'Allmessages'               => array( 'MediaWiki-zdźělenki' ),
	'Log'                       => array( 'Protokol' ),
	'Blockip'                   => array( 'Blokować' ),
	'Undelete'                  => array( 'Wobnowić' ),
	'Import'                    => array( 'Importować' ),
	'Lockdb'                    => array( 'Datowu_banku_zamknyć' ),
	'Unlockdb'                  => array( 'Datowu_banku_wotamknyć' ),
	'Userrights'                => array( 'Prawa' ),
	'MIMEsearch'                => array( 'Pytanje_po_MIME' ),
	'FileDuplicateSearch'       => array( 'Duplikatowe_pytanje' ),
	'Unwatchedpages'            => array( 'Njewobkedźbowane_strony' ),
	'Listredirects'             => array( 'Daleposrědkowanja' ),
	'Revisiondelete'            => array( 'Wušmórnjenje_wersijow' ),
	'Unusedtemplates'           => array( 'Njewužiwane_předłohi' ),
	'Randomredirect'            => array( 'Připadne_daleposrědkowanje' ),
	'Mypage'                    => array( 'Moja_wužiwarska_strona' ),
	'Mytalk'                    => array( 'Moja_diskusijna_strona' ),
	'Mycontributions'           => array( 'Moje_přinoški' ),
	'Listadmins'                => array( 'Administratorojo' ),
	'Listbots'                  => array( 'Boćiki' ),
	'Popularpages'              => array( 'Najwoblubowaniše_strony' ),
	'Search'                    => array( 'Pytać' ),
	'Resetpass'                 => array( 'Hesło_wróćo_stajić' ),
	'Withoutinterwiki'          => array( 'Falowace_mjezyrěčne_wotkazy' ),
	'MergeHistory'              => array( 'Stawizny_zjednoćić' ),
	'Filepath'                  => array( 'Datajowy_puć' ),
	'Invalidateemail'           => array( 'Njepłaćiwa_e-mejl' ),
	'Blankpage'                 => array( 'Prózdna_strona' ),
	'LinkSearch'                => array( 'Wotkazowe_pytanje' ),
	'DeletedContributions'      => array( 'Zničene_přinoški' ),
	'Tags'                      => array( 'Taflički' ),
	'Activeusers'               => array( 'Aktiwni_wužiwarjo' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Wotkazy podšmórnić:',
'tog-highlightbroken'         => 'Wotkazy na njeeksistowace strony formatować <a href="" class="new">tak</a> (alternatiwa: tak<a href="" class="internal">?</a>)',
'tog-justify'                 => 'Tekst w blokowej sadźbje',
'tog-hideminor'               => 'Snadne změny w aktualnych změnach schować',
'tog-hidepatrolled'           => 'Dohladawane změny w aktualnych změnach schować',
'tog-newpageshidepatrolled'   => 'Dohladowane strony z lisćiny nowych stronow schować',
'tog-extendwatchlist'         => 'Lisćinu wobkedźbowankow wočinić, zo by wšě změny widźał, nic jenož najnowše',
'tog-usenewrc'                => 'Rozšěrjenu lisćinu aktualnych změnow (trjeba JavaScript) wužiwać',
'tog-numberheadings'          => 'Nadpisma awtomatisce čisłować',
'tog-showtoolbar'             => 'Gratowu lajstu pokazać (JavaScript)',
'tog-editondblclick'          => 'Strony z dwójnym kliknjenjom wobdźěłować (JavaScript)',
'tog-editsection'             => 'Wobdźěłowanje jednotliwych wotrězkow přez wotkazy [wobdźěłać] zmóžnić',
'tog-editsectiononrightclick' => 'Wobdźěłowanje jednotliwych wotrězkow přez kliknjenje z prawej tastu<br />na nadpisma wotrězkow zmóžnić (JavaScript)',
'tog-showtoc'                 => 'Zapis wobsaha pokazać (za strony z wjace hač 3 nadpismami)',
'tog-rememberpassword'        => 'Přizjewjenje na tutym wobhladowaku sej spomjatkować (za maksimalnje $1 {{PLURAL:$1|dźeń|dnjej|dny|dnjow}})',
'tog-watchcreations'          => 'Strony, kotrež wutworjam, swojim wobkedźbowankam přidać',
'tog-watchdefault'            => 'Strony, kotrež wobdźěłuju, swojim wobkedźbowankam přidać',
'tog-watchmoves'              => 'Sam přesunjene strony wobkedźbowankam přidać',
'tog-watchdeletion'           => 'Sam wušmórnjene strony wobkedźbowankam přidać',
'tog-minordefault'            => 'Wšě změny zwoprědka jako snadne woznamjenić',
'tog-previewontop'            => 'Přehlad nad wobdźěłanskim polom pokazać',
'tog-previewonfirst'          => 'Do składowanja přeco přehlad pokazać',
'tog-nocache'                 => 'Pufrowanje stronow wobhladowaka znjemóžnić',
'tog-enotifwatchlistpages'    => 'E-mejlku pósłać, hdyž so strona z wobkedźbowankow změni',
'tog-enotifusertalkpages'     => 'Mejlku pósłać, hdyž so moja wužiwarska diskusijna strona změni',
'tog-enotifminoredits'        => 'Tež dla snadnych změnow mejlki pósłać',
'tog-enotifrevealaddr'        => 'Moju e-mejlowu adresu w e-mejlowych zdźělenkach wotkryć',
'tog-shownumberswatching'     => 'Ličbu wobkedźbowacych wužiwarjow pokazać',
'tog-oldsig'                  => 'Přehlad eksistowaceje signatury:',
'tog-fancysig'                => 'Ze signaturu kaž z wikitekstom wobchadźeć  (bjez awtomatiskeho wotkaza)',
'tog-externaleditor'          => 'Eksterny editor jako standard wužiwać (jenož za ekspertow, žada sej specialne nastajenja na wašim ličaku)',
'tog-externaldiff'            => 'Eksterny diff-program jako standard wužiwać (jenož za ekspertow, žada sej specialne nastajenja na wašim ličaku)',
'tog-showjumplinks'           => 'Wotkazy typa „dźi do” zmóžnić',
'tog-uselivepreview'          => 'Live-přehlad wužiwać (JavaScript) (eksperimentalnje)',
'tog-forceeditsummary'        => 'Mje skedźbnić, jeli zabudu zjeće',
'tog-watchlisthideown'        => 'Moje změny we wobkedźbowankach schować',
'tog-watchlisthidebots'       => 'Změny awtomatiskich programow (botow) we wobkedźbowankach schować',
'tog-watchlisthideminor'      => 'Snadne změny we wobkedźbowankach schować',
'tog-watchlisthideliu'        => 'Změny přizjewjenych wužiwarjow z wobkedźbowankow schować',
'tog-watchlisthideanons'      => 'Změny anonymnych wužiwarjow z wobkedźbowankow schować',
'tog-watchlisthidepatrolled'  => 'Dohladowane změny we wobkedźbowankach schować',
'tog-nolangconversion'        => 'Konwertowanje rěčnych wariantow znjemóžnić',
'tog-ccmeonemails'            => 'Kopije mejlkow dóstać, kiž druhim wužiwarjam pósćelu',
'tog-diffonly'                => 'Jenož rozdźěle pokazać (nic pak zbytny wobsah)',
'tog-showhiddencats'          => 'Schowane kategorije pokazać',
'tog-norollbackdiff'          => 'Rozdźěl po wróćostajenju zanjechać',

'underline-always'  => 'přeco',
'underline-never'   => 'ženje',
'underline-default' => 'po standardźe wobhladowaka',

# Font style option in Special:Preferences
'editfont-style'     => 'Pismowy stil wobdźěłowanskeho pola:',
'editfont-default'   => 'Standard wobhladowaka',
'editfont-monospace' => 'Pismo z krutej šěrokosću',
'editfont-sansserif' => 'Bjezserifowe pismo',
'editfont-serif'     => 'Serifowe pismo',

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
'april'         => 'apryl',
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

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategorija|Kategoriji|Kategorije|Kategorije}}',
'category_header'                => 'Nastawki w kategoriji „$1”',
'subcategories'                  => 'Podkategorije',
'category-media-header'          => 'Dataje w kategoriji „$1”',
'category-empty'                 => "''Tuta kategorija tuchwilu žane nastawki abo medije njewobsahuje.''",
'hidden-categories'              => '{{PLURAL:$1|Schowana kategorija|Schowanej kategoriji|Schowane kategorije|Schowanych kategorijow}}',
'hidden-category-category'       => 'Schowane kategorije',
'category-subcat-count'          => '{{PLURAL:$2|Tuta kategorija ma jenož slědowacu podkategoriju.|Tuta kategorija ma {{PLURAL:$1|slědowacu podkategoriju|$1 slědowacej podkategoriji|$1 slědowace podkategorije|$1 slědowacych podkategorijow}} z dohromady $2.}}',
'category-subcat-count-limited'  => 'Tuta kategorija ma {{PLURAL:$1|slědowacu podkategoriju|slědowacej $1 podkategoriji|slědowace $1 podkategorije|slědowacych $1 podkategorijow}}:',
'category-article-count'         => '{{PLURAL:$2|Tuta kategorija wobsahuje jenož slědowacu stronu.|{{PLURAL:$1|Slědowaca strona je|Slědowacej $1 stronje stej|Slědowace $1 strony su|Slědowacych $1 stronow je}} w tutej kategoriji z dohromady $2.}}',
'category-article-count-limited' => '{{PLURAL:$1|Slědowaca strona je|Slědowacej $1 stronje stej|Slědowace $1 strony su|Slědowacych $1 stronow je}} w tutej kategoriji:',
'category-file-count'            => '{{PLURAL:$2|Tuta kategorija wobsahuje jenož slědowacu stronu.|{{PLURAL:$1|Slědowaca dataja je|Slědowacej $1 dataji stej|Slědowace $1 dataje|Slědowacych $1 datajow je}} w tutej kategoriji z dohromady $2.}}',
'category-file-count-limited'    => '{{PLURAL:$1|Slědowaca dataj je|Slědowacej $1 dataji stej|Slědowace $1 dataje su|Slědowacych $1 je}} w tutej kategoriji:',
'listingcontinuesabbrev'         => ' (pokročowane)',
'index-category'                 => 'Indicěrowane strony',
'noindex-category'               => 'Njeindicěrowane strony',

'mainpagetext'      => "'''MediaWiki bu wuspěšnje instalowany.'''",
'mainpagedocfooter' => 'Prošu hlej [http://meta.wikimedia.org/wiki/Help:Contents dokumentaciju] za informacije wo wužiwanju softwary.

== Za nowačkow ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Wo nastajenjach]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]',

'about'         => 'Wo',
'article'       => 'Nastawk',
'newwindow'     => '(wočinja so w nowym woknje)',
'cancel'        => 'Přetorhnyć',
'moredotdotdot' => 'Wjace…',
'mypage'        => 'Moja strona',
'mytalk'        => 'moja diskusija',
'anontalk'      => 'Z tutej IP diskutować',
'navigation'    => 'Nawigacija',
'and'           => '&#32;a',

# Cologne Blue skin
'qbfind'         => 'Namakać',
'qbbrowse'       => 'Přepytować',
'qbedit'         => 'wobdźěłać',
'qbpageoptions'  => 'stronu',
'qbpageinfo'     => 'Kontekst',
'qbmyoptions'    => 'Moje strony',
'qbspecialpages' => 'Specialne strony',
'faq'            => 'Husto stajene prašenja (FAQ)',
'faqpage'        => 'Project:Husto stajene prašenja (FAQ)',

# Vector skin
'vector-action-addsection'       => 'Temu přidać',
'vector-action-delete'           => 'wušmórnyć',
'vector-action-move'             => 'přesunyć',
'vector-action-protect'          => 'škitać',
'vector-action-undelete'         => 'Wobnowić',
'vector-action-unprotect'        => 'Škit wotstronić',
'vector-simplesearch-preference' => 'Polěpšene pytanske namjety zmóžnić (jenož šat Vector)',
'vector-view-create'             => 'Wutworić',
'vector-view-edit'               => 'Wobdźěłać',
'vector-view-history'            => 'Stawizny',
'vector-view-view'               => 'Čitać',
'vector-view-viewsource'         => 'Žórło sej wobhladać',
'actions'                        => 'Akcije',
'namespaces'                     => 'Mjenowe rumy',
'variants'                       => 'Warianty',

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
'create'            => 'Wutworić',
'editthispage'      => 'Stronu wobdźěłać',
'create-this-page'  => 'Stronu wudźěłać',
'delete'            => 'wušmórnyć',
'deletethispage'    => 'Stronu wušmórnyć',
'undelete_short'    => '{{PLURAL:$1|jednu wersiju|$1 wersiji|$1 wersije|$1 wersijow}} wobnowić',
'protect'           => 'škitać',
'protect_change'    => 'změnić',
'protectthispage'   => 'Stronu škitać',
'unprotect'         => 'škit zběhnyć',
'unprotectthispage' => 'Škit strony zběhnyć',
'newpage'           => 'Nowa strona',
'talkpage'          => 'diskusija',
'talkpagelinktext'  => 'diskusija',
'specialpage'       => 'Specialna strona',
'personaltools'     => 'Wosobinske nastroje',
'postcomment'       => 'Nowy wotrězk',
'articlepage'       => 'Nastawk',
'talk'              => 'diskusija',
'views'             => 'Zwobraznjenja',
'toolbox'           => 'Nastroje',
'userpage'          => 'Wužiwarsku stronu pokazać',
'projectpage'       => 'Projektowu stronu pokazać',
'imagepage'         => 'Datajowu stronu sej wobhladać',
'mediawikipage'     => 'Zdźělenku pokazać',
'templatepage'      => 'Předłohu pokazać',
'viewhelppage'      => 'Pomocnu stronu pokazać',
'categorypage'      => 'Kategoriju pokazać',
'viewtalkpage'      => 'Diskusiju pokazać',
'otherlanguages'    => 'W druhich rěčach',
'redirectedfrom'    => '(ze strony „$1” sposrědkowane)',
'redirectpagesub'   => 'Daleposrědkowanje',
'lastmodifiedat'    => 'Strona bu posledni raz dnja $1 w $2 hodź. změnjena.',
'viewcount'         => 'Strona bu {{PLURAL:$1|jónu|dwójce|$1 razy|$1 razow}} wopytana.',
'protectedpage'     => 'Škitana strona',
'jumpto'            => 'Dźi do:',
'jumptonavigation'  => 'Nawigacija',
'jumptosearch'      => 'Pytać',
'view-pool-error'   => 'Wodaj, serwery su we wokomiku přećežene.
Přewjele wužiwarjow pospytuje sej tutu stronu wobhladać.
Prošu wočakń chwilku, prjedy hač pospytuješ sej tutu stronu hišće raz wobhladać.

$1',
'pool-timeout'      => 'Timeout, doniž zawrjenje skónčene njeje.',
'pool-queuefull'    => 'Poolowa čakanska rynka je połna',
'pool-errorunknown' => 'Njeznaty zmylk:',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Wo {{GRAMMAR:lokatiw|{{SITENAME}}}}',
'aboutpage'            => 'Project:Wo',
'copyright'            => 'Wobsah steji pod $1.',
'copyrightpage'        => '{{ns:project}}:Awtorske prawa',
'currentevents'        => 'Aktualne podawki',
'currentevents-url'    => 'Project:Aktualne podawki',
'disclaimers'          => 'Licencne postajenja',
'disclaimerpage'       => 'Project:Impresum',
'edithelp'             => 'Pomoc za wobdźěłowanje',
'edithelppage'         => 'Help:Wobdźěłowanje',
'helppage'             => 'Help:Wobsah',
'mainpage'             => 'Hłowna strona',
'mainpage-description' => 'Hłowna strona',
'policy-url'           => 'Project:Směrnicy',
'portal'               => 'Portal {{GRAMMAR:genitiw|{{SITENAME}}}}',
'portal-url'           => 'Project:Portal',
'privacy'              => 'Škit datow',
'privacypage'          => 'Project:Škit datow',

'badaccess'        => 'Nimaš wotpowědne dowolnosće',
'badaccess-group0' => 'Nimaš wotpowědne dowolnosće za tutu akciju.',
'badaccess-groups' => 'Akcija, kotruž sy požadał, wobmjezuje so na wužiwarjow w {{PLURAL:$2|skupinje|jednej ze skupinow}}: $1.',

'versionrequired'     => 'Wersija $1 softwary MediaWiki trěbna',
'versionrequiredtext' => 'Wersija $1 MediaWiki je trěbna, zo by so tuta strona wužiwać móhła. Hlej [[Special:Version|wersijowu stronu]]',

'ok'                      => 'W porjadku',
'retrievedfrom'           => 'Z {{GRAMMAR:genitiw|$1}}',
'youhavenewmessages'      => 'Maš $1 ($2).',
'newmessageslink'         => 'nowe powěsće',
'newmessagesdifflink'     => 'poslednja změna',
'youhavenewmessagesmulti' => 'Maš nowe powěsće: $1',
'editsection'             => 'wobdźěłać',
'editold'                 => 'wobdźěłać',
'viewsourceold'           => 'žórło wobhladać',
'editlink'                => 'wobdźěłać',
'viewsourcelink'          => 'žórło wobhladać',
'editsectionhint'         => 'Wotrězk wobdźěłać: $1',
'toc'                     => 'Wobsah',
'showtoc'                 => 'pokazać',
'hidetoc'                 => 'schować',
'thisisdeleted'           => '$1 pokazać abo wobnowić?',
'viewdeleted'             => '$1 pokazać?',
'restorelink'             => '{{PLURAL:$1|1 wušmórnjenu wersiju|$1 wušmórnjenej wersiji|$1 wušmórnjene wersije|$1 wušmórnjenych wersijow}}',
'feedlinks'               => 'Kanal:',
'feed-invalid'            => 'Njepłaćiwy typ abonementa.',
'feed-unavailable'        => 'Syndikaciske kanale k dispoziciji njesteja',
'site-rss-feed'           => '$1 RSS kanal',
'site-atom-feed'          => 'Atom-kanal za $1',
'page-rss-feed'           => 'RSS-kanal za „$1“',
'page-atom-feed'          => 'Atom-Kanal za „$1“',
'red-link-title'          => '$1 (strona njeeksistuje)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'nastawk',
'nstab-user'      => 'Wužiwarska strona',
'nstab-media'     => 'Medije',
'nstab-special'   => 'Specialna strona',
'nstab-project'   => 'Projektowa strona',
'nstab-image'     => 'Dataja',
'nstab-mediawiki' => 'zdźělenka',
'nstab-template'  => 'Předłoha',
'nstab-help'      => 'Pomoc',
'nstab-category'  => 'Kategorija',

# Main script and global functions
'nosuchaction'      => 'Žana tajka akcija',
'nosuchactiontext'  => 'Akcija, kotruž URL podawa, je njepłaćiwa.
Sy so snano při zapodaću URL zapisał abo sy wopačnemu wotkazej slědował.
To móhło tež programowanski zmylk w {{GRAMMAR:lokatiw|{{SITENAME}}}} być.',
'nosuchspecialpage' => 'Tuta specialna strona njeeksistuje.',
'nospecialpagetext' => '<strong>Tuta specialna strona njeeksistuje.</strong>

Płaćiwe specialne strony hodźa so pod [[Special:SpecialPages|lisćinu specialnych stronow]] namakać.',

# General errors
'error'                => 'Zmylk',
'databaseerror'        => 'Zmylk w datowej bance',
'dberrortext'          => 'Syntaktiski zmylk při wotprašowanju datoweje banki.
To móhło zmylk w programje być. Poslednje spytane wotprašenje w datowej bance běše:
<blockquote><tt>$1</tt></blockquote>
z funkcije "<tt>$2</tt>".
Datowa banka wróći zmylk "tt>$3: $4</tt>".',
'dberrortextcl'        => 'Syntaktiski zmylk je we wotprašowanju datoweje banki wustupił.
Poslednje wotprašenje w datowej bance běše:
"$1"
z funkcije "$2".
Datowa banka wróći zmylk "$3: $4"',
'laggedslavemode'      => 'Kedźbu: Je móžno, zo strona žane zaktualizowanja njewobsahuje.',
'readonly'             => 'Datowa banka je zawrjena',
'enterlockreason'      => 'Zapodaj přičinu za zawrjenje a přibližny čas, hdy budźe zawrjenje zběhnjene',
'readonlytext'         => 'Datowa banka je tuchwilu za nowe zapiski a druhe změny zawrjena, najskerje wothladowanskich dźěłow dla; po jich zakónčenju budźe wšitko zaso normalne.

Administrator, kiž je datowu banku zawrěł, je jako přičinu podał: $1',
'missing-article'      => 'Datowa banka njenamaka tekst strony z mjenom "$1" $2, kotryž dyrbjał so namakać.

To so zwjetša zawinuje, hdyž so njepłaćiwa změna abo zapisk stawiznow na stronu wotkazuje, kotraž bu wušmórnjena.

Jeli to njetrjechi, sy najskerje programowu zmólku w softwarje namakał.
Zdźěl to prošu [[Special:ListUsers/sysop|admininistratorej]] podawajo wotpowědny URL.',
'missingarticle-rev'   => '(Wersijowe čisło: $1)',
'missingarticle-diff'  => '(Rozdźěl: $1, $2)',
'readonly_lag'         => 'Datowa banka bu awtomatisce zawrjena, mjeztym zo pospytuja wotwisne serwery datowych bankow  hłowny serwer docpěć',
'internalerror'        => 'Znutřkowny zmylk',
'internalerror_info'   => 'Znutřkowny zmylk: $1',
'fileappenderrorread'  => '"$1" njeda so čitać při připowěšenju.',
'fileappenderror'      => 'Njeje móžno było "$1" k "$2" připowěsnyć.',
'filecopyerror'        => 'Njebě móžno dataju „$1” k „$2” kopěrować.',
'filerenameerror'      => 'Njebě móžno dataju „$1” na „$2” přemjenować.',
'filedeleteerror'      => 'Njebě móžno dataju „$1” wušmórnyć.',
'directorycreateerror' => 'Zapis „$1“ njeda so wutworić.',
'filenotfound'         => 'Njebě móžno dataju „$1” namakać.',
'fileexistserror'      => 'Njebě móžno do dataje „$1“ pisać, dokelž tuta dataja hižo eksistuje.',
'unexpected'           => 'Njewočakowana hódnota: "$1"="$2".',
'formerror'            => 'Zmylk: njeje móžno formular wotesłać',
'badarticleerror'      => 'Tuta akcija njeda so na tutej stronje wuwjesć.',
'cannotdelete'         => 'Strona abo dataja "$1" njeje so dała wušmórnyć.
Móže być, zo je hižo wot někoho druheho wušmórnjena.',
'badtitle'             => 'Wopačny titul',
'badtitletext'         => 'Požadane mjeno strony běše njepłaćiwy, prózdny abo njekorektny titul z mjezyrěcneho abo interwikijoweho wotkaza. Snano wobsahuje jedne znamješko abo wjacore znamješka, kotrež w titulach dowolene njejsu.',
'perfcached'           => 'Sćěhowace daty z pufrowaka pochadźeja a snano cyle aktualne njejsu.',
'perfcachedts'         => 'Sćěhowace daty su z pufrowaka a buchu $1 posledni raz zaktualizowane.',
'querypage-no-updates' => "'''Aktualizacija za tutu stronu je tuchwilu znjemóžnjena. Daty so hač na dalše njewobnowjeja.'''",
'wrong_wfQuery_params' => 'Njeprawe parametry za wfQuery()

Funkcija: $1

Wotprašenje: $2',
'viewsource'           => 'žórło wobhladać',
'viewsourcefor'        => 'za $1',
'actionthrottled'      => 'Akcije wobmjezowane',
'actionthrottledtext'  => 'Jako připrawa přećiwo spamej, je častosć wuwjedźenja tuteje akcije w krótkej dobje wobmjezowana a ty sy tutón limit překročił. Prošu spytaj za něšto mjeńšiny hišće raz.',
'protectedpagetext'    => 'Strona je přećiwo wobdźěłowanju škitana.',
'viewsourcetext'       => 'Móžeš pak jeje žórło wobhladać a jo kopěrować:',
'protectedinterface'   => 'Tuta strona skići tekst za rěčny zwjerch a je škitana zo by so znjewužiwanju zadźěwało.',
'editinginterface'     => "'''Warnowanje:''' Wobdźěłuješ stronu, kotraž so wužiwa, zo by tekst za softwaru k dispoziciji stajiła. Změny wuskutkuja so na napohlad wužiwarskeho powjercha za druhich wužiwarjow. Hlej za přełožki [http://translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net], projekt lokalizacije MediaWiki.",
'sqlhidden'            => '(SQL wotprašenje schowane)',
'cascadeprotected'     => 'Strona je za wobdźěłowanje zawrjena, dokelž je w {{PLURAL:$1|slědowacej stronje|slědowacymaj stronomaj|slědowacych stronach}} zapřijata, {{PLURAL:$1|kotraž je|kotrejž stej|kotrež su}} přez kaskadowu opciju {{PLURAL:$1|škitana|škitanej|škitane}}:
$2',
'namespaceprotected'   => "Nimaš dowolnosć, zo by stronu w mjenowym rumje '''$1''' wobdźěłał.",
'customcssjsprotected' => 'Nimaš prawo, zo by tutu stronu wobdźěłał, dokelž wosobinske nastajenja druheho wužiwarja wobsahuje.',
'ns-specialprotected'  => 'Specialne strony njedadźa so wobdźěłać.',
'titleprotected'       => "Tutón titul bu přećiwo wutworjenju přez [[User:$1|$1]] škitany.
Podata přičina je ''$2''.",

# Virus scanner
'virus-badscanner'     => "Špatna konfiguracija: njeznaty wirusowy skener: ''$1''",
'virus-scanfailed'     => 'Skenowanje njeporadźiło (kode $1)',
'virus-unknownscanner' => 'njeznaty antiwirus:',

# Login and logout pages
'logouttext'                 => "'''{{GENDER:|Sy|Sy}} nětko {{GENDER:|wotzjewjeny|wotzjewjena}}.'''

Móžeš {{GRAMMAR:akuzatiw|{{SITENAME}}}} nětko anonymnje dale wužiwać abo so ze samsnym abo druhim wužiwarskim mjenom [[Special:UserLogin|zaso přizjewić]].
Wobkedźbuj, zo so někotre strony dale jewja, kaž by hišće přizjewjeny był, doniž pufrowak swojeho wobhladowaka njewuprózdnješ.",
'welcomecreation'            => '== Witaj, $1! ==

Twoje konto bu wutworjene. Njezabudź swoje nastajenja za [[Special:Preferences|{{GRAMMAR:akuzatiw|{{SITENAME}}}}]] změnić.',
'yourname'                   => 'Wužiwarske mjeno',
'yourpassword'               => 'Hesło:',
'yourpasswordagain'          => 'Hesło znowa zapodać',
'remembermypassword'         => 'Na tutym ličaku přizjewjeny wostać (za maksimalnje $1 {{PLURAL:$1|dźeń|dnjej|dny|dnjow}})',
'securelogin-stick-https'    => 'Po přizjewjenju z HTTPS zwjazany wostać',
'yourdomainname'             => 'Twoja domejna',
'externaldberror'            => 'Běše pak eksterny zmylk awtentifikacije datoweje banki, pak njesměš swoje eksterne konto aktualizować.',
'login'                      => 'Přizjewić',
'nav-login-createaccount'    => 'Konto wutworić abo so přizjewić',
'loginprompt'                => 'Za přizjewjenje do {{GRAMMAR:genitiw|{{SITENAME}}}} dyrbja placki zmóžnjene być.',
'userlogin'                  => 'Załožće konto abo přizjewće so',
'userloginnocreate'          => 'Přizjewić',
'logout'                     => 'wotzjewić',
'userlogout'                 => 'wotzjewić',
'notloggedin'                => 'Njepřizjewjeny',
'nologin'                    => "Nimaš žane konto? '''$1'''.",
'nologinlink'                => 'Tu móžeš wužiwarske konto wutworić',
'createaccount'              => 'Wužiwarske konto wutworić',
'gotaccount'                 => "Maš hižo wužiwarske konto? '''$1'''.",
'gotaccountlink'             => 'Přizjewić',
'createaccountmail'          => 'z mejlku',
'createaccountreason'        => 'Přičina:',
'badretype'                  => 'Hesle, kotrejž sy zapodał, so njekryjetej.',
'userexists'                 => 'Wužiwarske mjeno, kotrež sy zapodał, so hižo wužiwa. Wubjer druhe mjeno.',
'loginerror'                 => 'Zmylk při přizjewjenju',
'createaccounterror'         => 'Wužiwarske konto njeda so załožić: $1',
'nocookiesnew'               => 'Wužiwarske konto bu wutworjene, njejsy pak přizjewjeny. {{SITENAME}} wužiwa placki (cookies), zo bychu so wužiwarjo přizjewili. Sy placki znjemóžnił. Prošu zmóžń je a přizjew so potom ze swojim nowym wužiwarskim mjenom a hesłom.',
'nocookieslogin'             => '{{SITENAME}} wužiwa placki (cookies) za přizjewjenje wužiwarjow wužiwa. Sy placki znjemóžnił. Prošu zmóžń je a spytaj hišće raz.',
'noname'                     => 'Njejsy płaćiwe wužiwarske mjeno podał.',
'loginsuccesstitle'          => 'Přizjewjenje wuspěšne',
'loginsuccess'               => "'''Sy nětko jako \"\$1\" w {{GRAMMAR:lokatiw|{{SITENAME}}}} {{GENDER:|přizjewjeny|přizjewjena|přizjewjene}}.'''",
'nosuchuser'                 => 'Njeje wužiwar z mjenom "$1".
Wužiwarske mjena wobkedźbuja wulkopisanje.
Přepruwuj swój prawopis abo [[Special:UserLogin/signup|wutwor nowe konto]].',
'nosuchusershort'            => 'Wužiwarske mjeno „<nowiki>$1</nowiki>” njeeksistuje. Prošu přepruwuj prawopis.',
'nouserspecified'            => 'Dyrbiš wužiwarske mjeno podać',
'login-userblocked'          => 'Tutón wužiwar je zablokowany. Přizjewjenje njedowolene.',
'wrongpassword'              => 'Hesło, kotrež sy zapodał, je wopačne. Prošu spytaj hišće raz.',
'wrongpasswordempty'         => 'Hesło, kotrež sy zapodał, běše prózdne. Prošu spytaj hišće raz.',
'passwordtooshort'           => 'Hesła dyrbja znajmjeńša {{PLURAL:$1|1 znamješko|$1 znamješce|$1 znamješka|$1 znamješkow}} měć.',
'password-name-match'        => 'Twoje hesło dyrbi so wot twojeho wužiwarskeho mjena rozeznać.',
'password-too-weak'          => 'Podate hesło je přesłabe a njehodźi so wužiwać.',
'mailmypassword'             => 'Nowe hesło e-mejlować',
'passwordremindertitle'      => 'Skedźbnjenje na hesło z {{GRAMMAR:genitiw|{{SITENAME}}}}',
'passwordremindertext'       => 'Něchtó z IP-adresu $1 (najskerje ty) je wo nowe hesło za přizjewjenje za {{GRAMMAR:akuzatiw|{{SITENAME}}}} ($4) prosył. Nachwilne hesło za wužiwarja "$2" je so wutworiło a je nětko "$3". Jeli je to twój wotpohlad było dyrbiš so nětko přizjewić a nowe hesło wubrać. Twoje nachwilne hesło spadnje za {{PLURAL:$5|jeden dźeń|$5 dnjej|$5 dny|$5 dnjow}}.

Jeli něchtó druhi wo nowe hesło prosył abo ty sy so zaso na swoje hesło dopomnił a hižo nochceš je změnić, móžeš tutu powěsć ignorować a swoje stare hesło dale wužiwać.',
'noemail'                    => 'Za wužiwarja $1 žana e-mejlowa adresa podata njeje.',
'noemailcreate'              => 'Dyrbiš płaćiwu e-mejlowa adresu podać',
'passwordsent'               => 'Nowe hesło bu na e-mejlowu adresu zregistrowanu za wužiwarja „$1” pósłane.
Prošu přizjew so znowa, po tym zo sy je přijał.',
'blocked-mailpassword'       => 'Twoja IP-adresa bu blokowana; tohodla njeje dowolene, nowe hesło požadać, zo by so znjewužiwanju zadźěwało.',
'eauthentsent'               => 'Wobkrućenska mejlka bu na naspomnjenu e-mejlowu adresu pósłana.
Prjedy hač so druha mejlka ke kontu pósćele, dyrbiš so po instrukcijach w mejlce měć, zo by wobkrućił, zo konto je woprawdźe twoje.',
'throttled-mailpassword'     => 'Bu hižo nowe hesło za {{PLURAL:$1|poslednju hodźinu|poslednjej $1 hodźinje|poslednje $1 hodźiny|poslednich $1 hodźin}} pósłane. Zo by znjewužiwanju zadźěwało, so jenož jedne hesło na {{PLURAL:$1|hodźinu|$1 hodźinje|$1 hodźiny|$1 hodźinow}} pósćele.',
'mailerror'                  => 'Zmylk při słanju mejlki: $1',
'acct_creation_throttle_hit' => 'Wopytowarjo tutoho wikija, kotřiž twoju IP-adresu wužiwaja, su {{PLURAL:$1|1 konto|$1 kontaj|$1 konty|$1 kontow}} posledni dźeń wutworił, štož je maksimalna ličba za tutu periodu. Wopytowarjo, kotřiž tutu IP-adresu wužiwaja, njemóža tuchwilu dalše konta wutworić.',
'emailauthenticated'         => 'Twoja e-mejlowa adresa bu $2 $3 hodź. wobkrućena.',
'emailnotauthenticated'      => 'Twoja e-mejlowa adresa hišće wobkrućena <strong>njeje</strong>. Žane mejlki za jednu z sćěhowacych funkcijow pósłane njebudu.',
'noemailprefs'               => 'Podaj e-mejlowu adresu w swojich nastajenjach, zo bychu tute funkcije k dispoziciji stali.',
'emailconfirmlink'           => 'Wobkruć swoju e-mejlowu adresu',
'invalidemailaddress'        => 'E-mejlowa adresa so njeakceptuje, dokelž ma po zdaću njepłaćiwy format. Prošu zapodaj płaćiwu adresu abo wuprózdń te polo.',
'accountcreated'             => 'Wužiwarske konto wutworjene',
'accountcreatedtext'         => 'Wužiwarske konto za $1 bu wutworjene.',
'createaccount-title'        => 'Wutworjenje wužiwarskeho konta za {{SITENAME}}',
'createaccount-text'         => 'Něchtó je wužiwarske konto za twoju e-mejlowu adresu na {{SITENAME}} ($4) z mjenom "$2" z hesłom "$3" wutworił. Ty měł so nětko přizjewić a swoje hesło změnić.

Móžeš tutu zdźělenku ignorować, jeli so wužiwarske konto zmylnje wutworiło.',
'usernamehasherror'          => 'Wužiwarske mjeno njesmě hašowe znamješka wpbsahować',
'login-throttled'            => 'Sy přehusto spytał so přizjewić. Počakaj prošu, prjedy hač znowa spytaš.',
'loginlanguagelabel'         => 'Rěč: $1',
'suspicious-userlogout'      => 'Twoje naprašowanje za wotzjewjenje bu wotpokazane, dokelž zda so, jako by so přez wobškodźeny wobhladowak abo pufrowacy proksy pósłało',

# JavaScript password checks
'password-strength'            => 'Trochowana hesłowa sylnosć: $1',
'password-strength-bad'        => 'ŠPATNA',
'password-strength-mediocre'   => 'SRĚNJA',
'password-strength-acceptable' => 'akceptabelna',
'password-strength-good'       => 'dobra',
'password-retype'              => 'Hesło wospjetować',
'password-retype-mismatch'     => 'Hesle so njekryjetej',

# Password reset dialog
'resetpass'                 => 'Hesło změnić',
'resetpass_announce'        => 'Sy so z nachwilnym e-mejlowanym hesłom přizjewił. Zo by přizjewjenje zakónčił, dyrbiš nětko nowe hesło postajić.',
'resetpass_text'            => '<!-- Tu tekst zasunyć -->',
'resetpass_header'          => 'Kontowe hesło změniś',
'oldpassword'               => 'Stare hesło:',
'newpassword'               => 'Nowe hesło:',
'retypenew'                 => 'Nowe hesło wospjetować:',
'resetpass_submit'          => 'Hesło posrědkować a so přizjewić',
'resetpass_success'         => 'Twoje hesło bu wuspěšnje změnjene! Nětko přizjewjenje běži...',
'resetpass_forbidden'       => 'Hesła njedadźa so změnić.',
'resetpass-no-info'         => 'Dyrbiš so přizjewić, zo by direktny přistup na tutu stronu měł.',
'resetpass-submit-loggedin' => 'Hesło změnić',
'resetpass-submit-cancel'   => 'Přetorhnyć',
'resetpass-wrong-oldpass'   => 'Njepłaćiwe nachwilne abo aktualne hesło.
Snano sy swoje hesło hižo wuspěšnje změnił abo nowe nachwilne hesło požadał.',
'resetpass-temp-password'   => 'Nachwilne hesło:',

# Edit page toolbar
'bold_sample'     => 'Tučny tekst',
'bold_tip'        => 'Tučny tekst',
'italic_sample'   => 'Kursiwny tekst',
'italic_tip'      => 'Kursiwny tekst',
'link_sample'     => 'Mjeno wotkaza',
'link_tip'        => 'Znutřkowny wotkaz',
'extlink_sample'  => 'http://www.example.com Mjeno wotkaza',
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
'summary'                          => 'Zjeće:',
'subject'                          => 'Tema/Nadpismo:',
'minoredit'                        => 'Snadna změna',
'watchthis'                        => 'Stronu wobkedźbować',
'savearticle'                      => 'Składować',
'preview'                          => 'Přehlad',
'showpreview'                      => 'Přehlad pokazać',
'showlivepreview'                  => 'Hnydomny přehlad',
'showdiff'                         => 'Změny pokazać',
'anoneditwarning'                  => '<b>Kedźbu:</b> Njejsy přizjewjeny. Změny so z twojej IP-adresu składuja.',
'anonpreviewwarning'               => "''Njejsy přizjewjeny. Składowanje přenošuje twoju IP-adresu do wobdźěłowanskeje historije tuteje strony.''",
'missingsummary'                   => '<b>Kedźbu:</b> Njejsy žane zjeće zapodał. Jeli hišće raz na „Składować” kliknješ so twoje změny bjez komentara składuja.',
'missingcommenttext'               => 'Prošu zapodaj zjeće.',
'missingcommentheader'             => "'''Kedźbu:''' Njejsy temu/nadpis za tutón komentar podał. Jeli na „{{int:savearticle}}” kliknješ, składuje so twoja změna bjez temy/nadpisa.",
'summary-preview'                  => 'Přehlad zjeća:',
'subject-preview'                  => 'Přehlad temy:',
'blockedtitle'                     => 'Wužiwar je zablokowany',
'blockedtext'                      => "'''Twoje wužiwarske mjeno abo twoja IP-adresa bu zablokowane.'''

Blokowar je $1.
Podata přičina je ''$2''.

* Spočatk blokowanja: $8
* Kónc blokowanja: $6
* Zablokowany wužiwar: $7

Móžeš $1 abo druheho [[{{MediaWiki:Grouppage-sysop}}|administratora]] kontaktować, zo by wo blokowanju diskutował.
Njemóžeš 'e-mejlowu funkciju' wužiwać, chibazo sy płaćiwu e-mejlowu adresu w swojich [[Special:Preferences|kontowych nastajenjach]] podał a njebu přećiwo jeje wužiwanju zablokowany.
Twoja tuchwilna IP-adresa je $3 a blokowanski ID je #$5. Prošu podaj wšě horjeka naspomnjene podrobnosće w swojich naprašowanjach.",
'autoblockedtext'                  => 'Twoja IP-adresa bu awtomatisce blokowana, dokelž ju druhi wužiwar wužiwaše, kiž bu wot $1 zablokowany.
Přičina blokowanja bě:

:\'\'$2\'\'

* Započatk blokowanja: $8
* Kónc blokowanja: $6
* Zablokowany wužiwar: $7

Móžeš $1 abo jednoho z druhich [[{{MediaWiki:Grouppage-sysop}}|administratorow]] kontaktować, zo by blokowanje diskutował.

Wobkedźbuj, zo njemóžeš funkciju "Wužiwarjej mejlku pósłać" wužiwać, jeli nimaš płaćiwu e-mejlowu adresu, kotraž je w twojich [[Special:Preferences|wužiwarskich nastajenjach]] zregistrowana a njebi blokowany ju wužiwać.

Twój aktualna adresa IP je $3 a ID blokowanja je #$5.
Prošu podaj wšě horjeka naspomnjene podrobnosće w naprašowanjach, kotrež činiš.',
'blockednoreason'                  => 'žana přičina podata',
'blockedoriginalsource'            => 'To je žórłowy tekst strony <b>$1</b>:',
'blockededitsource'                => 'Tekst <b>twojich změnow</b> strony <b>$1</b> so tu pokazuje:',
'whitelistedittitle'               => 'Za wobdźěłowanje je přizjewjenje trěbne.',
'whitelistedittext'                => 'Dyrbiš so $1, zo by strony wobdźěłować móhł.',
'confirmedittext'                  => 'Dyrbiš swoju e-mejlowu adresa wobkrućić, prjedy hač móžeš strony wobdźěłować. Prošu zapodaj a wobkruć swoju e-mejlowu adresu we [[Special:Preferences|wužiwarskich nastajenjach]].',
'nosuchsectiontitle'               => 'Wotrězk njeda so namakać',
'nosuchsectiontext'                => 'Sy spytał, wotrězk wobdźěłać, kotryž njeeksistuje.
Móžno, zo je so přesunył abo zničił, mjeztym zo sej wobhladuješ stronu.',
'loginreqtitle'                    => 'Přizjewjenje trěbne',
'loginreqlink'                     => 'přizjewić',
'loginreqpagetext'                 => 'Dyrbiš so $1, zo by strony čitać móhł.',
'accmailtitle'                     => 'Hesło bu pósłane.',
'accmailtext'                      => "Připadnje spłodźene hesło za [[User talk:$1|$1]] bu do $2 pósłane.

Hesło za tute nowe konto da so na stronje ''[[Special:ChangePassword|hesło změnić]]'' při přizjewjenju změnić.",
'newarticle'                       => '(Nowy nastawk)',
'newarticletext'                   => 'Sy wotkaz k stronje slědował, kotraž hišće njeeksistuje. Zo by stronu wutworił, wupjelń slědowace tekstowe polo (hlej [[{{MediaWiki:Helppage}}|stronu pomocy]] za dalše informacije). Jeli sy zmylnje tu, klikń prosće na tłóčatko <b>Wróćo</b> we swojim wobhladowaku.',
'anontalkpagetext'                 => "---- ''To je diskusijna strona za anonymneho wužiwarja, kiž hišće konto wutworił njeje abo je njewužiwa. Dyrbimy tohodla numerisku IP-adresu wužiwać, zo bychmy jeho/ju identifikowali. Tajka IP-adresa hodźi so wot wjacorych wužiwarjow zhromadnje wužiwać. Jeli sy anonymny wužiwar a měniš, zo buchu irelewantne komentary k tebi pósłane, [[Special:UserLogin/signup|wutwor prošu konto]] abo [[Special:UserLogin|přizjew so]], zo by přichodnu šmjatańcu z anonymnymi wužiwarjemi wobešoł.''",
'noarticletext'                    => 'Tuchwilu tuta strona žadyn tekst njewobsahuje, móžeš [[Special:Search/{{PAGENAME}}|tutón titul strony na druhich stronach pytać]], <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} wotpowědne protokole pytać] abo [{{fullurl:{{FULLPAGENAME}}|action=edit}} tutu stronu wobdźěłać]</span>.',
'noarticletext-nopermission'       => 'Tuchwilu žadyn tekst na tutej stronje njeje.
Móžeš [[Special:Search/{{PAGENAME}}|tutón titul strony]] na druhich stronach pytać abo <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} pytaj wotpowědne protokole]</span>.',
'userpage-userdoesnotexist'        => 'Wužiwarske konto „$1“ njeje zregistrowane. Prošu pruwuj, hač chceš tutu stronu woprawdźe wutworić/wobdźěłać.',
'userpage-userdoesnotexist-view'   => 'Wužiwarske konto "$1" njeje zregistrowane.',
'blocked-notice-logextract'        => 'Tutón wužiwar je tuchwilu zablokowany. Najnowši protokolowy zapisk so deleka jako referenca podawa:',
'clearyourcache'                   => '<b>Kedźbu:</b> Po składowanju dyrbiš snano pufrowak swojeho wobhladowaka wuprózdnić, <b>Mozilla/Firefox/Safari:</b> tłóč na <i>Umsch</i> kliknjo na <i>Znowa</i> abo tłóč <i>Strg-Umsch-R</i> (<i>Cmd-Shift-R</i> na Apple Mac); <b>IE:</b> tłóč <i>Strg</i> kliknjo na symbol <i>Aktualisieren</i> abo tłóč <i>Strg-F5</i>; <b>Konqueror:</b>: Klikń jenož na tłóčatko <i>Erneut laden</i> abo tłoč  <i>F5</i>; Wužiwarjo <b>Opery</b> móža swój pufrowak dospołnje  w <i>Tools→Preferences</i> wuprózdnić.',
'usercssyoucanpreview'             => "'''Pokiw:''' Wužij tłóčku '{{int:showpreview}}', zo by swój nowy css do składowanja testował.",
'userjsyoucanpreview'              => "'''Pokiw:''' Wužij tłóčatko \"{{int:showpreview}}\", zo by swój nowy JavaScript do składowanja testował.",
'usercsspreview'                   => "'''Wobkedźbujće, zo sej jenož přehlad swojeho wužiwarskeho CSS wobhladuješ. Hišće njeje składowany!'''",
'userjspreview'                    => "== Přehlad twojeho wosobinskeho JavaScript ==

'''Kedźbu:''' Po składowanju dyrbiš pufrowak swojeho wobhladowaka wuprózdnić '''Mozilla/Firefox:''' ''Strg-Shift-R'', '''Internet Explorer:''' ''Strg-F5'', '''Opera:''' ''F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror:''' ''F5''.",
'userinvalidcssjstitle'            => "'''Warnowanje:''' Skin z mjenom „$1” njeeksistuje. Prošu mysli na to, zo wosobinske strony .css a .js titul z małym pismikom wuwziwaja, na př. {{ns:user}}:Foo/monobook.css město {{ns:user}}:Foo/Monobook.css.",
'updated'                          => '(Zaktualizowany)',
'note'                             => "'''Kedźbu:'''",
'previewnote'                      => "'''Kedźbu, to je jenož přehlad, změny hišće składowane njejsu!'''",
'previewconflict'                  => 'Tutón přehlad tekst w hornim tekstowym polu zwobrazni kaž so zjewi, jeli jón składuješ.',
'session_fail_preview'             => "'''Njemóžachmy twoju změnu předźěłać, dokelž su so posedźenske daty zhubili. Spytaj prošu hišće raz.
Jeli to hišće njefunguje, [[Special:UserLogout|wotzjew so]] a přizjew so zaso.'''",
'session_fail_preview_html'        => "'''Njemóžachmy twoje změnu předźěłać, dokelž su so posedźenske daty zhubili.'''

''Dokelž we {{GRAMMAR:lokatiw|{{SITENAME}}}} je luty HTML zmóžnił, je přehlad jako wěstotna naprawa přećiwo atakam přez JavaScript schowany.''

'''Jeli to je legitimny wobdźěłowanski pospyt, spytaj prošu hišće raz. Jeli to hišće njefunguje, [[Special:UserLogout|wotzjew so]] a přizjew so znowa.'''",
'token_suffix_mismatch'            => "'''Twoja změna je so wotpokazała, dokelž twój wobhladowak je znamješka skepsał.
Składowanje móže wobsah strony zničić. Móže so to na přikład přez wopačnje dźěłowacy proksy stać.'''",
'editing'                          => 'Wobdźěłanje strony $1',
'editingsection'                   => 'Wobdźěłanje strony $1 (wotrězk)',
'editingcomment'                   => '$1 so wobdźěłuje (nowy wotrězk)',
'editconflict'                     => 'Wobdźěłowanski konflikt: $1',
'explainconflict'                  => "Něchtó druhi je stronu změnił w samsnym času, hdyž sy spytał ju wobdźěłować.
Hornje tekstowe polo wobsahuje tekst strony kaž tuchwilu eksistuje.
Twoje změny so w delnim tekstowym polu pokazuja.
Dyrbiš swoje změny do eksistowaceho teksta zadźěłać.
'''Jenož''' tekst w hornim tekstowym polu so składuje hdyž znowa na „{{int:savearticle}}” kliknješ.",
'yourtext'                         => 'Twój tekst',
'storedversion'                    => 'Składowana wersija',
'nonunicodebrowser'                => "'''KEDŹBU: Twój wobhladowak z Unikodu kompatibelny njeje. Prošu wužiwaj hinaši wobhladowak.'''",
'editingold'                       => "'''KEDŹBU: Wobdźěłuješ staršu wersiju strony. Jeli ju składuješ, zjewi so jako najnowša wersija!'''",
'yourdiff'                         => 'Rozdźěle',
'copyrightwarning'                 => "Prošu wobkedźbuj, zo wšě přinoški k {{GRAMMAR:datiw|{{SITENAME}}}} $2 podleže (hlej $1 za podrobnosće). Jeli nochceš, zo so twój přinošk po dobrozdaću wobdźěłuje a znowa rozšěrja, njeskładuj jón.<br />
Lubiš tež, zo sy to sam napisał abo ze zjawneje domejny abo z podobneho žórła kopěrował.
Kopěrowanje tekstow, kiž su přez awtorske prawa škitane, je zakazane! '''NJESKŁADUJ PŘINOŠKI Z COPYRIGHTOM BJEZ DOWOLNOSĆE!'''",
'copyrightwarning2'                => "Prošu wobkedźbuj, zo wšě přinoški k {{GRAMMAR:datiw|{{SITENAME}}}} hodźa so wot druhich wužiwarjow wobdźěłować, změnić abo wotstronić. Jeli nochceš, zo so twój přinošk po dobrozdaću wobdźěłuje, njeskładuj jón.<br />

Lubiš nam tež, zo sy jón sam napisał abo ze zjawneje domejny abo z podobneho swobodneho žórła kopěrował (hlej $1 za podrobnosće).

'''NJESKŁADUJ PŘINOŠKI Z COPYRIGHTOM BJEZ DOWOLNOSĆE!'''",
'longpagewarning'                  => "'''KEDŹBU: Strona wobsahuje $1 kB; někotre wobhladowaki maja problemy, strony wobdźěłać, kotrež wobsahuja 32 kB abo wjace. Prošu přemysli sej stronu do mjeńšich wotrězkow rozrjadować.'''",
'longpageerror'                    => "'''ZMYLK: Tekst, kotryž sy spytał składować wobsahuje $1 kB, maksimalna wulkosć pak je $2 kB. Njehodźi so składować.'''",
'readonlywarning'                  => "'''KEDŹBU: Datowa banka bu wothladanja dla zawrjena, tohodla njemóžeš swoje změny nětko składować. Móžeš tekst do tekstoweje dataje přesunyć a jón za pozdźišo składować.'''

Administrator, kiž je ju zawrjena, je tutu přičinu podał: $1",
'protectedpagewarning'             => "'''KEDŹBU: Tuta strona bu zawrjena, tak zo jenož wužiwarjo z prawami administratora móža ju wobdźěłać.'''
Najnowši protokolowy zapisk je deleka jako referenca podaty:",
'semiprotectedpagewarning'         => "'''Kedźbu:''' Tuta strona bu zawrjena, tak zo jenož zregistrowani wužiwarjo móža ju wobdźěłać.
Najnowši protokolowy zapisk je deleka jako referenca podaty:",
'cascadeprotectedwarning'          => "'''KEDŹBU:''' Tuta strona je škitana, tak zo móža ju jenož wužiwarjo z prawami administratora wobdźělać, dokelž je w {{PLURAL:$1|slědowacej stronje|slědowacych stronach}} zapřijata, {{PLURAL:$1|kotraž je|kotrež su}} přez kaskadowu opciju {{PLURAL:$1|škitana|škitane}}:",
'titleprotectedwarning'            => "'''WARNOWANJE: Tuta strona bu zawrjena, tak zo [[Special:ListGroupRights|wosebite prawa]] su trěbne, zo by so wutworił.'''
Najnowši protokolowy zapisk je deleka jako referenca podaty:",
'templatesused'                    => 'Na tutej stronje {{PLURAL:$1|wužiwana předłoha|wužiwanej předłoze|wužiwane předłohi|wužiwane předłohi}}:',
'templatesusedpreview'             => 'W tutym přehledźe {{PLURAL:$1|wužiwana předłoha|wužiwanej předłoze|wužiwane předłohi|wužiwane předłohi}}:',
'templatesusedsection'             => 'W tutym wotrězku {{PLURAL:$1|wužiwana předłoha|wužiwanej předłoze|wužiwane předłohi|wužiwane předłohi}}:',
'template-protected'               => '(škitana)',
'template-semiprotected'           => '(škitana za njepřizjewjenych wužiwarjow a nowačkow)',
'hiddencategories'                 => 'Tuta strona je čłon w {{PLURAL:$1|1 schowanej kategoriji|$1 schowanymaj kategorijomaj|$1 schowanych kategorijach|$1 schowanych kategorijach}}:',
'edittools'                        => '<!-- Tutón tekst so spody wobdźěłowanskich a nahrawanskich formularow pokazuje. -->',
'nocreatetitle'                    => 'Wutworjenje stron je wobmjezowane.',
'nocreatetext'                     => 'Na {{GRAMMAR:lokatiw|{{SITENAME}}}} bu wutworjenje nowych stronow wobmjezowane. Móžeš wobstejace strony wobdźěłać abo [[Special:UserLogin|so přizjewić abo wužiwarske konto wutworić]].',
'nocreate-loggedin'                => 'Nimaš prawo, zo by nowe strony wutworił.',
'sectioneditnotsupported-title'    => 'Wobdźěłowanje wotrězka so njepodpěruje',
'sectioneditnotsupported-text'     => 'Wobdźěłowanje wotrězka so na tutej wobdźěłowanskej stronje njepodpěruje.',
'permissionserrors'                => 'Woprawnjenske zmylki',
'permissionserrorstext'            => 'Nimaš prawo, zo by tutu akciju wuwjedł. {{PLURAL:$1|Přičina|Přičiny}}:',
'permissionserrorstext-withaction' => 'Nimaš dowolnosć za $2 ze {{PLURAL:$1|slědowaceje přičiny|slědowaceju přičinow|slědowacych přičinow|slědowacych přičinow}}:',
'recreate-moveddeleted-warn'       => "'''Kedźbu: Wutworiš stronu, kiž bu prjedy wušmórnjena.'''

Prošu přepruwuj, hač je přihódne z wobdźěłowanjom tuteje strony pokročować.
Protokol wušmórnjenjow a přesunjenjow za tutu stronu su tu za informaciju:",
'moveddeleted-notice'              => 'Tuta strona bu wušmórnjena. Protokol wušmórnjenjow a přesunjenjow za  stronu so deleka jako referenca podawa.',
'log-fulllog'                      => 'Dospołny protokol sej wobhladać',
'edit-hook-aborted'                => 'Wobdźěłanje přez hoku přetorhnjene.
Njeje žane wujasnjenje podała.',
'edit-gone-missing'                => 'Strona njeje so aktualizować dała.
Zda so, zo je hîžo wušmórnjena.',
'edit-conflict'                    => 'Wobdźěłanski konflikt.',
'edit-no-change'                   => 'Waše wobdźěłanje bu ignorowane, dokelž tekst njebu zm,ěnjeny.',
'edit-already-exists'              => 'Njebě móžno nowu stronu wutworić.
Eksistuje hižo.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Warnowanje: Tuta strona wobsahuje přewjele parserowych wołanjow.

Dyrbjała mjenje hač $2 {{PLURAL:$2|wołanje|wołanjej|wołanja|wołanjow}} měć, {{PLURAL:$1|je nětko $1 wołanje|stej nětko $1 wołanjej|su nětko $1 wołanja|je nětko $1 wołanjow}}.',
'expensive-parserfunction-category'       => 'Strony, kotrež tajke parserowe funkcije přehusto wołaja, kotrež serwer poćežuja.',
'post-expand-template-inclusion-warning'  => 'Warnowanje: Wulkosć zapřijatych předłohow je přewulka. Někotre předłohi so njezapřijmu.',
'post-expand-template-inclusion-category' => 'Strony, hdźež maksimalna wulkosć zapřijatych předłohow je překročena',
'post-expand-template-argument-warning'   => 'Warnowanje: Tuta strona wobsahuje znajmjeńša jedyn předłohowy argument, kotryž ma přewulku espansisku wulkosć. Tute argumenty bu wuwostajene.',
'post-expand-template-argument-category'  => 'Strony, kotrež wuwostajene předłohowe argumenty wobsahuja',
'parser-template-loop-warning'            => 'Předłohowa sekla wotkryta: [[$1]]',
'parser-template-recursion-depth-warning' => 'Limit za rekursijnu hłubokosć předłohi překročeny ($1)',
'language-converter-depth-warning'        => 'Limit hłubokosće rěčneho konwertera překročena ($1)',

# "Undo" feature
'undo-success' => 'Wersija je so wuspěšnje wotstroniła. Prošu přepruwuj deleka w přirunanskim napohledźe, hač twoja změna bu přewzata a klikń potom na „Składować”, zo by změnu składował.',
'undo-failure' => 'Wobdźěłanje njehodźeše so wotstronić, dokelž wotpowědny wotrězk bu mjeztym změnjeny.',
'undo-norev'   => 'Změna njeda so cofnyć, dokelž njeeksistuje abo bu wušmórnjena.',
'undo-summary' => 'Změna $1 [[Special:Contributions/$2|$2]] ([[User talk:$2|diskusija]]) bu cofnjena.',

# Account creation failure
'cantcreateaccounttitle' => 'Wužiwarske konto njeda so wutworić.',
'cantcreateaccount-text' => "Wutworjenje wužiwarskeho konta z IP-adresy '''$1''' bu wot [[User:$3|$3]] zablokowane.

Přičina za blokowanje, podata wot $3, je: ''$2''",

# History pages
'viewpagelogs'           => 'protokole tuteje strony pokazać',
'nohistory'              => 'Njeje žanych staršich wersijow strony.',
'currentrev'             => 'Aktualna wersija',
'currentrev-asof'        => 'Aktualna wersija wot $1',
'revisionasof'           => 'Wersija z $1',
'revision-info'          => 'Wersija wot $1 wužiwarja $2',
'previousrevision'       => '←starša wersija',
'nextrevision'           => 'nowša wersija→',
'currentrevisionlink'    => 'Aktualnu wersiju pokazać',
'cur'                    => 'akt',
'next'                   => 'přich',
'last'                   => 'posl',
'page_first'             => 'spočatk',
'page_last'              => 'kónc',
'histlegend'             => 'Diff wubrać: Wubjer opciske pola za přirunanje a tłóč na enter abo tłóčku deleka.

Legenda: (akt) = rozdźěl k tuchwilnej wersiji, (posl) = rozdźěl k předchadnej wersiji, S = snadna změna.',
'history-fieldset-title' => 'Stawizny přepytać',
'history-show-deleted'   => 'Jenož wušmórnjene',
'histfirst'              => 'najstaršu',
'histlast'               => 'tuchwilnu',
'historysize'            => '({{PLURAL:$1|1 bajt|$1 bajtaj|$1 bajty|$1 bajtow}})',
'historyempty'           => '(prózdna)',

# Revision feed
'history-feed-title'          => 'Stawizny wersijow',
'history-feed-description'    => 'Stawizny wersijow za tutu stronu w {{GRAMMAR:lokatiw|{{SITENAME}}}}',
'history-feed-item-nocomment' => '$1 w $2 hodź.',
'history-feed-empty'          => 'Strona, kotruž sy požadał, njeeksistuje. Bu snano z wikija wotstronjena abo přesunjena. Móžeš tu [[Special:Search|w {{SITENAME}}]] za stronami z podobnym titulom pytać.',

# Revision deletion
'rev-deleted-comment'         => '(komentar wotstronjeny)',
'rev-deleted-user'            => '(wužiwarske mjeno wotstronjene)',
'rev-deleted-event'           => '(Protokolowa akcija bu wotstronjena)',
'rev-deleted-user-contribs'   => '[wužiwarske mjeno wotstronjene abo IP-adresa wotstronjena - změna mjez přinoškami schowana]',
'rev-deleted-text-permission' => "Tuta wersija strony bu '''wušmórnjena'''.
Hlej podrobnosće w [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} protokolu wušmórnjenjow].",
'rev-deleted-text-unhide'     => "Tuta wersija strony bu '''wušmórnjena'''.
Hlej podrobnosće w [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} protokolu wušmórnjenjow].
Jako administrator móžeš [$1 sej tutu wersiju wobhladać], jeli chceš pokročować.",
'rev-suppressed-text-unhide'  => "Tuta wersija strony bu '''potłóčena'''.
Snano su podrobnosće w [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} protokolu potłóčenjow].
Jako administrator móžeš [$1 sej tutu wersiju wobhladać], jeli chceš pokročować.",
'rev-deleted-text-view'       => "Tuta wersija strony bu '''wušmórnjena'''.
Jako administrator móžeš sej ju wobhladać; hlej podrobnosće w [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} protokolu wušmórnjenjow].",
'rev-suppressed-text-view'    => "Tuta wersija strony bu '''potłóčena'''.
Jako administrator móžeš sej ju wobhladać; snano su podrobnosće w [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} protokolu potłóčenjow].",
'rev-deleted-no-diff'         => "Njemóžeš sej tutón rozdźěl wobhladać, dokelž jedna z wersijow bu '''wušmórnjena'''.
Hlej podrobnosće w [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} protokolu wušmórnjenjow].",
'rev-suppressed-no-diff'      => "Njemóžeš sej tutón rozdźěl wobhladać, dokelž jedna z wersijow bu '''zničena'''.",
'rev-deleted-unhide-diff'     => "Jedna z wersijow tutoho rozdźěla bu '''wušmórnjena'''.
Podrobnosće hlej w [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} protokol wušmórnjenjow].
Jako administrator móžeš hišće [$1 sej tutón rozdźěl wobhladać], jeli chceš pokročować.",
'rev-suppressed-unhide-diff'  => "Jedna z wersijow tutoho rozdźěla je so '''potłóčiła'''.
Za podrobnosće hlej [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} protokol potłóčenjow].
Jako administrator móžeš hišće [$1 sej tutón rozdźěl wobhladać], jeli chceš pokročować.",
'rev-deleted-diff-view'       => "Jedna z wersijiw tutoho rozdźěla je so '''wušmórnyła'''.
Jako administrator móžeš sej tutón rozdźěl wobhladać; podrobnosće namakaš w [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} protokolu wušmórnjenjow].",
'rev-suppressed-diff-view'    => "Jedna z wersijiw tutoho rozdźěla je so '''potłóčiła'''.
Jako administrator móžeš sej tutón rozdźěl wobhladać; podrobnosće namakaš w [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} protokolu potłóčenjow].",
'rev-delundel'                => 'pokazać/schować',
'rev-showdeleted'             => 'pokazać',
'revisiondelete'              => 'Wersije wušmórnyć/wobnowić',
'revdelete-nooldid-title'     => 'Njepłaćiwa cilowa wersija',
'revdelete-nooldid-text'      => 'Pak njejsy cilowu wersiju podał, zo by tutu funkciju wuwjedł, podata wersija njeeksistuje pak pospytuješ aktualnu wersiju schować.',
'revdelete-nologtype-title'   => 'Žadyn protokolowy typ podaty',
'revdelete-nologtype-text'    => 'Njejsy protokolowy typ podał, zo by tutu akciju wuwjedł.',
'revdelete-nologid-title'     => 'Njepłaćiwy protokolowy zapisk',
'revdelete-nologid-text'      => 'Pak njejsy cilowy protokolowy podawk podał, zo by tutu funkciju wuwjedł pak podaty zapisk njeeksistuje.',
'revdelete-no-file'           => 'Podata dataja njeeksistuje.',
'revdelete-show-file-confirm' => 'Chceš sej woprawdźe wušmórnjenu wersiju dataje "<nowiki>$1</nowiki>" wot $2 $3  wobhladać?',
'revdelete-show-file-submit'  => 'Haj',
'revdelete-selected'          => "'''{{PLURAL:$2|Wubrana wersija|Wubranej wersiji|Wubrane wersije|Wubranych wersijow}} wot [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Wubrany zapisk z protokola|Wubranej zapiskaj z protokola|Wubrane zapiski z protokola|Wubrane zapiski z protokola}} za '''$1:''''''",
'revdelete-text'              => "'''Wušmórnjene wersije a podawki so w stawiznach a protokolach dale jewja, ale dźěle jich wobsaha budu njepřistupne za zjawnosć.'''
Druzy administratorojo na {{GRAMMAR:lokatiw|{{SITENAME}}}} móža hišće na schowany tekst přistup měć a jón z pomocu samsneho interfejsa wobnowić, chibazo tež přidatne prawa su wobmjezowane.",
'revdelete-confirm'           => 'Prošu potwjerdź, zo chceš to činić, zo rozumiš konsekwency a zo činiš to po [[{{MediaWiki:Policy-url}}|prawidłach]].',
'revdelete-suppress-text'     => "Potłóčenje dyrbjało so '''jenož''' za slědowace pady wužiwać:
* Njepřihódne wosobinske informacije
*: ''bydlenske adresy a telefonowe čisła, čisła socialneho zawěsćenja atd.''",
'revdelete-legend'            => 'Wobmjezowanja za widźomnosć nastajić',
'revdelete-hide-text'         => 'Tekst tuteje wersije schować',
'revdelete-hide-image'        => 'Wobsah wobraza schować',
'revdelete-hide-name'         => 'Akciju w protokolach schować',
'revdelete-hide-comment'      => 'Zjeće schować',
'revdelete-hide-user'         => 'Wužiwarske mjeno/IP-adresu schować',
'revdelete-hide-restricted'   => 'Daty wot administratorow kaž tež te druhich wužiwarjow potłóčić',
'revdelete-radio-same'        => '(njezměnić)',
'revdelete-radio-set'         => 'Haj',
'revdelete-radio-unset'       => 'Ně',
'revdelete-suppress'          => 'Přičinu wušmórnjenja tež za administratorow schować',
'revdelete-unsuppress'        => 'Wobmjezowanja za wobnowjene wersije zběhnyć',
'revdelete-log'               => 'Přičina:',
'revdelete-submit'            => 'Na {{PLURAL:$1|wubranu wersiju|wubranej wersiji|wubrane wersije|wubrane wersije}} nałožować',
'revdelete-logentry'          => 'Widźomnosć wersije změnjena za [[$1]]',
'logdelete-logentry'          => 'je widźomnosć za [[$1]] změnił',
'revdelete-success'           => "'''Widźomnosć wersije bu wuspěšnje zaktualizowana.'''",
'revdelete-failure'           => "'''Wersijowa widźomnosć njeda so aktualizować:'''
$1",
'logdelete-success'           => 'Widźomnosć zapiska bu wuspěšnje změnjena.',
'logdelete-failure'           => "'''Protokolowa widźomnosć njeda so nastajić:'''
$1",
'revdel-restore'              => 'Widźomnosć změnić',
'revdel-restore-deleted'      => 'zhašane wersije',
'revdel-restore-visible'      => 'widźomne wersije',
'pagehist'                    => 'Stawizny strony',
'deletedhist'                 => 'Wušmórnjene stawizny',
'revdelete-content'           => 'wobsah',
'revdelete-summary'           => 'zjeće wobdźěłać',
'revdelete-uname'             => 'wužiwarske mjeno',
'revdelete-restricted'        => 'na administratorow nałožene wobmjezowanja',
'revdelete-unrestricted'      => 'Wobmjezowanja za administratorow wotstronjene',
'revdelete-hid'               => '$1 schowany',
'revdelete-unhid'             => '$1 pokazany',
'revdelete-log-message'       => '$1 za $2 {{PLURAL:$2|wersija|wersiji|wersije|wersijow}}',
'logdelete-log-message'       => '$1 za $2 {{PLURAL:$2|podawk|podawkaj|podawki|podawkow}}',
'revdelete-hide-current'      => 'Zmylk při chowanju zapiska wot $2, $1: to je aktualna wersija.
Njeda so schować.',
'revdelete-show-no-access'    => 'Zmylk při pokazowanju zapiska wot $2, $1: tutón zapisk bu jako "wobmjezowany" markěrowany.
Nimaš přistup na njón.',
'revdelete-modify-no-access'  => 'Zmylk při změnjenju zapiska wot $2, $1: tutón zapisk bu jako "wobmjezowany" markěrowany.
Nimaš přistup na njón.',
'revdelete-modify-missing'    => 'Zmylk při změnjenju zapiska ID $1: pobrachuje w datowej bance!',
'revdelete-no-change'         => "'''Warnowanje:''' zapisk wot $2, $1 je hižo požadane nastajenja widźomnosće měł.",
'revdelete-concurrent-change' => 'Zmylk při změnjenju zapiska wot $2, $1: zda so, zo jeho status je so wot někoho druheho změnił, mjeztym zo sy spytał jón změnić.
Prošu přepruwuj protokole.',
'revdelete-only-restricted'   => 'Zmylk při chowanju zapiska wot $2, $1; njemóžeš zapiski před wočemi administratorow potłóčić, bjez toho zo wuběraš tež jednu z druhich widźomnosćowych opcijow.',
'revdelete-reason-dropdown'   => '*Zwučene přičiny za wušmórnjenje
** Přeńdźenje awtorskeho prawa
** Njepřihódne wosobinske informacije',
'revdelete-otherreason'       => 'Druha/přidatna přičina:',
'revdelete-reasonotherlist'   => 'Druha přičina',
'revdelete-edit-reasonlist'   => 'Přičiny za wušmórnjenje wobdźěłać',
'revdelete-offender'          => 'Awtor wersije:',

# Suppression log
'suppressionlog'     => 'Protokol potłóčenjow',
'suppressionlogtext' => 'Deleka je lisćina wušmórnjenjow a zablokowanjow, inkluziwnje wobsaha schowaneho wot administratorow. Hlej [[Special:IPBlockList|Lisćina zablokowanjow IP]] za lisćinu tuchwilnych zablokowanjow.',

# Revision move
'moverevlogentry'              => 'je {{PLURAL:$3|jednu wersiju|$3 wersiji|$3 wersije|$3 wersijow}} wot $1 do $2 přesunył',
'revisionmove'                 => 'Wersije wot "$1" přesunyć',
'revmove-explain'              => 'Slědowace wersije přesunu so wot $1 k podatej cilowej stronje . Jeli cil njeeksistuje, wutwori so. Hewak so tute wersije w stawiznach strony zjednoćeja.',
'revmove-legend'               => 'Cilowu stronu a zjeće zwěsćić',
'revmove-submit'               => 'Wersije k wubranej stronje přesunyć',
'revisionmoveselectedversions' => 'Wubrane wersije přesunyć',
'revmove-reasonfield'          => 'Přičina:',
'revmove-titlefield'           => 'Cilowa strona:',
'revmove-badparam-title'       => 'Wopačne parametry',
'revmove-badparam'             => 'Twoje naprašowanje wobsahuje njedowolene abo njedosahace parametry. Prošu klikń na "wróći" a spytaj hišće raz.',
'revmove-norevisions-title'    => 'Njepłaćiwa cilowa wersija',
'revmove-norevisions'          => 'Njejsy jednu wersiju abo wjace wersijow podał, zo by tutu funkciju wuwjedł, abo podata wersija njeeksistuje.',
'revmove-nullmove-title'       => 'Njepłaćiwy titul',
'revmove-nullmove'             => 'Žórłowa a cilowa strona stej identiskej. Prošu klikń na  "wróćo" a zapodaj druhe mjeno strony hač "[[$1]]".',
'revmove-success-existing'     => '{{PLURAL:$1|Jedna wersija je so wot [[$2]]|$1 wersiji stej so wot [[$2]]|$1 wersije su so wot [[$2]]|$1 wersijow je so wot [[$2]]}} do eksistowaceje strony [[$3]] {{PLURAL:$1|přesunyła|přesunyłoj|přesunyli|přesunyło}}.',
'revmove-success-created'      => '{{PLURAL:$1|Jedna wersija je so wot [[$2]]|$1 wersiji stej so wot [[$2]]|$1 wersije su so wot [[$2]]|$1 wersijow je so wot [[$2]]}} do runje wutworjeneje strony [[$3]] {{PLURAL:$1|přesunyła|přesunyłoj|přesunyli|přesunyło}}.',

# History merging
'mergehistory'                     => 'Stawizny stronow zjednoćić',
'mergehistory-header'              => 'Tuta strona ći dowola wersije stawiznow žórłoweje strony na nowej stronje zjednoćić.
Zawěsć, zo tuta změna stawiznisku kontinuitu strony wobchowuje.',
'mergehistory-box'                 => 'Wersije dweju stronow zjednoćić:',
'mergehistory-from'                => 'Žórłowa strona:',
'mergehistory-into'                => 'Cilowa strona:',
'mergehistory-list'                => 'Zjednoćujomne wersijowe stawizny',
'mergehistory-merge'               => 'Slědowace wersije wot [[:$1|$1]] hodźa so z [[:$2|$2]] zjednoćić. Wužij špaltu z opciskimi tłóčatkami, zo by jenož te wersije zjednoćił, kotrež su so w podatym času a bo před nim wutworili. Wobkedźbuj, zo wužiwanje nawigaciskich wotkazow budźe tutu špaltu wróćo stajeć.',
'mergehistory-go'                  => 'Zjednoćujomne změny pokazać',
'mergehistory-submit'              => 'Wersije zjednoćić',
'mergehistory-empty'               => 'Njehodźa so žane wersije zjednoćeć.',
'mergehistory-success'             => '$3 {{PLURAL:$3|wersija|wersiji|wersije|wersijow}} wot [[:$1]] wuspěšnje z [[:$2]] {{PLURAL:$3|zjednoćena|zjednoćenej|zjednoćene|zjednoćene}}.',
'mergehistory-fail'                => 'Njeje móžno zjednócenje stawiznow přewjesć, prošu přepruwuj stronu a časowe parametry.',
'mergehistory-no-source'           => 'Žórłowa strona $1 njeeksistuje.',
'mergehistory-no-destination'      => 'Cilowa strona $1 njeeksistuje.',
'mergehistory-invalid-source'      => 'Žórłowa strona dyrbi płaćiwy titul być.',
'mergehistory-invalid-destination' => 'Cilowa strona dyrbi płaćiwy titul być.',
'mergehistory-autocomment'         => '[[:$1]] z [[:$2]] zjednoćeny',
'mergehistory-comment'             => '[[:$1]] z [[:$2]] zjednoćeny: $3',
'mergehistory-same-destination'    => 'Žórłowa a cilowa strona njesmějetej identiskej być',
'mergehistory-reason'              => 'Přičina:',

# Merge log
'mergelog'           => 'Protokol zjednoćenja',
'pagemerge-logentry' => '[[$1]] z [[$2]] zjednoćeny (do $3 {{PLURAL:$3|wersije|wersijow|wersijow|wersijow}})',
'revertmerge'        => 'Zjednoćenje cofnyć',
'mergelogpagetext'   => 'Deleka je lisćina najaktualnišich zjednoćenjow stawiznow dweju stronow.',

# Diffs
'history-title'            => 'Stawizny wersijow strony „$1“',
'difference'               => '(rozdźěl mjez wersijomaj)',
'difference-multipage'     => '(Rozdźěl mjez stronami)',
'lineno'                   => 'Rjadka $1:',
'compareselectedversions'  => 'Wubranej wersiji přirunać',
'showhideselectedversions' => 'Wubrane wersije pokazać/schować',
'editundo'                 => 'cofnyć',
'diff-multi'               => '({{PLURAL:$1|Jedna mjezywersija|$1 mjezywersiji|$1 mjezywersije|$1 mjezywersijow}} wot {{PLURAL:$2|jednoho wužiwarja|$2 wužiwarjow|$2 wužiwarjow|$2 wužiwarjow}} {{PLURAL:$1|njepokazana|njepokazanej|njepokazane|njepokazane}})',
'diff-multi-manyusers'     => '({{PLURAL:$1|Jedna mjezywersija|$1 mjezywersiji|$1 mjezywersije|$1 mjezywersijow}} wot wjace hač {{PLURAL:$2|jednoho wužiwarja|$2 wužiwarjow|$2 wužiwarjow|$2 wužiwarjow}} {{PLURAL:$1|njepokazana|njepokazanej|njepokazane|njepokazane}})',

# Search results
'searchresults'                    => 'Pytanske wuslědki',
'searchresults-title'              => 'Pytanske wuslědki za "$1"',
'searchresulttext'                 => 'Za dalše informacije wo pytanju {{GRAMMAR:genitiw|{{SITENAME}}}}, hlej [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'Sy za \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|wšěmi stronami, kotrež započinaja so z "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|wšěmi stronami, kotrež na "$1" wotkazuja]]) pytal.',
'searchsubtitleinvalid'            => 'Sy naprašowanje za „$1“ stajił.',
'toomanymatches'                   => 'Přewjele pytanskich wuslědkow, prošu spytaj druhe wotprašenje.',
'titlematches'                     => 'Strony z wotpowědowacym titulom',
'notitlematches'                   => 'Žane strony z wotpowědowacym titulom',
'textmatches'                      => 'Strony z wotpowědowacym tekstom',
'notextmatches'                    => 'Žane strony z wotpowědowacym tekstom',
'prevn'                            => '{{PLURAL:$1|předchadny $1|předchadnej $1|předchadne $1|předchadnych $1}}',
'nextn'                            => '{{PLURAL:$1|přichodny $1|přichodnej $1|přichodne $1|přichodnych $1}}',
'prevn-title'                      => '{{PLURAL:$1|Předchadny wuslědk|Předchadnej $1 wuslědkaj|Předchadne $1 wuslědki|Předchadnych $1 wuslědkow}}',
'nextn-title'                      => '{{PLURAL:$1|Přichodny wuslědk|Přichodnej $1 wuslědkaj|Přichodne $1 wuslědki|Přichodnych $1 wuslědkow}}',
'shown-title'                      => '$1 {{PLURAL:$1|wuslědk|wuslědkaj|wuslědki|wuslědkow}} na stronu pokazać',
'viewprevnext'                     => '($1 {{int:pipe-separator}} $2) ($3) pokazać',
'searchmenu-legend'                => 'Pytanske opcije',
'searchmenu-exists'                => "'''Je strona z mjenom \"[[\$1]]\" na tutym wikiju'''",
'searchmenu-new'                   => "'''Wutwor stronu \"[[:\$1|\$1]]\" na tutym wikiju!'''",
'searchhelp-url'                   => 'Help:Wobsah',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Strony z tutym prefiksom přepytać]]',
'searchprofile-articles'           => 'Wobsahowe strony',
'searchprofile-project'            => 'Pomoc a projektowe strony',
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Wšitko',
'searchprofile-advanced'           => 'Rozšěrjeny',
'searchprofile-articles-tooltip'   => 'W $1 pytać',
'searchprofile-project-tooltip'    => 'W $1 pytać',
'searchprofile-images-tooltip'     => 'Za datajemi pytać',
'searchprofile-everything-tooltip' => 'Cyły wobsah přepytać (inkluziwnje diskusijne strony)',
'searchprofile-advanced-tooltip'   => 'W swójskich mjenowych rumach pytać',
'search-result-size'               => '$1 ({{PLURAL:$2|1 słowo|$2 słowje|$2 słowa|$2 słowow}})',
'search-result-category-size'      => '{{PLURAL:$1|1 čłon|$1 čłonaj|$1 čłonojo|$1 čłonow}} ({{PLURAL:$2|1 podkategorija|$2 podkategoriji|$2 podkategorije|$2 podkategorijow}}, {{PLURAL:$3|1 dataja|$3 dataji|$3 dataje|$3 datajow}})',
'search-result-score'              => 'Relewanca: $1 %',
'search-redirect'                  => '(Daleposrědkowanje $1)',
'search-section'                   => '(wotrězk $1)',
'search-suggest'                   => 'Měnješe ty $1?',
'search-interwiki-caption'         => 'Sotrowske projekty',
'search-interwiki-default'         => '$1 wuslědki:',
'search-interwiki-more'            => '(dalše)',
'search-mwsuggest-enabled'         => 'z namjetami',
'search-mwsuggest-disabled'        => 'žane namjety',
'search-relatedarticle'            => 'Přiwuzne',
'mwsuggest-disable'                => 'Namjety AJAX znjemóžnić',
'searcheverything-enable'          => 'We wšěch mjenowych rumach pytać',
'searchrelated'                    => 'přiwuzny',
'searchall'                        => 'wšě',
'showingresults'                   => "Deleka so hač {{PLURAL:$1|'''1''' wuslědk pokazuje|'''$1''' wuslědkaj pokazujetej|'''$1''' wuslědki pokazuja|'''$1''' wuslědkow pokazuje}}, započinajo z #'''$2'''.",
'showingresultsnum'                => "Deleka so {{PLURAL:$3|'''1''' wuslědk pokazuje|'''$3''' wuslědkaj pokazujetej|'''$3''' wuslědki pokazuja|'''$3''' wuslědkow pokazuje}}, započinajo z #'''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Wuslědk '''$1''' z '''$3'''|Wuslědki '''$1 - $2''' z '''$3'''}} za '''$4'''",
'nonefound'                        => "'''Kedźbu''': Jenož někotre mjenowe rumy pytaja so po standardźe.
Spytaj swoje naprašowanje z prefiksom ''all:'' wužiwać, zo by wšón wobsah (inkluziwnje diskusijne strony, předłohi atd.) pytał abu wužij požadany mjenowy rum jako prefiks.",
'search-nonefound'                 => 'Njebuchu wuslědki namakane, kotrež naprašowanju wotpowěduja.',
'powersearch'                      => 'Pytać',
'powersearch-legend'               => 'Rozšěrjene pytanje',
'powersearch-ns'                   => 'W mjenowych rumach pytać:',
'powersearch-redir'                => 'Daleposrědkowanja nalistować',
'powersearch-field'                => 'Pytać za:',
'powersearch-togglelabel'          => 'Kontrolować:',
'powersearch-toggleall'            => 'Wšě',
'powersearch-togglenone'           => 'Žadyn',
'search-external'                  => 'Eksterne pytanje',
'searchdisabled'                   => 'Pytanje w {{GRAMMAR:lokatiw|{{SITENAME}}}} tuchwilu móžne njeje. Móžeš mjeztym z Google pytać. Wobkedźbuj, zo móža wuslědki z wobsaha {{GRAMMAR:genitiw|{{SITENAME}}}} zestarjene być.',

# Quickbar
'qbsettings'               => 'Pobóčna lajsta',
'qbsettings-none'          => 'Žane',
'qbsettings-fixedleft'     => 'Leži nalěwo',
'qbsettings-fixedright'    => 'Leži naprawo',
'qbsettings-floatingleft'  => 'Wisa nalěwo',
'qbsettings-floatingright' => 'Wisa naprawo',

# Preferences page
'preferences'                   => 'Nastajenja',
'mypreferences'                 => 'moje nastajenja',
'prefs-edits'                   => 'Ličba změnow:',
'prefsnologin'                  => 'Njepřizjewjeny',
'prefsnologintext'              => 'Dyrbiš <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} přizjewjeny]</span>  być, zo by móhł nastajenja postajić.',
'changepassword'                => 'Hesło změnić',
'prefs-skin'                    => 'Šat',
'skin-preview'                  => 'Přehlad',
'prefs-math'                    => 'Math',
'datedefault'                   => 'Žane nastajenje',
'prefs-datetime'                => 'Datum a čas',
'prefs-personal'                => 'Wužiwarske daty',
'prefs-rc'                      => 'Aktualne změny',
'prefs-watchlist'               => 'Wobkedźbowanki',
'prefs-watchlist-days'          => 'Ličba dnjow, kotrež maja so we wobkedźbowankach pokazać:',
'prefs-watchlist-days-max'      => '(maksimalnje 7 dnjow)',
'prefs-watchlist-edits'         => 'Ličba změnow, kotrež maja so we wobkedźbowankach pokazać:',
'prefs-watchlist-edits-max'     => '(maksimalna ličba: 1000)',
'prefs-watchlist-token'         => 'Marka wobkedźbowankow:',
'prefs-misc'                    => 'Wšelake nastajenja',
'prefs-resetpass'               => 'Hesło změnić',
'prefs-email'                   => 'E-mejlowe opcije',
'prefs-rendering'               => 'Napohlad',
'saveprefs'                     => 'Składować',
'resetprefs'                    => 'Njeskładowane změny zaćisnyć',
'restoreprefs'                  => 'Wšě standardne nastajenja wobnowić',
'prefs-editing'                 => 'Wobdźěłowanje',
'prefs-edit-boxsize'            => 'Wulkosć wobdźěłowanskeho wokna.',
'rows'                          => 'Rjadki:',
'columns'                       => 'Stołpiki:',
'searchresultshead'             => 'Pytać',
'resultsperpage'                => 'Wuslědki za stronu:',
'contextlines'                  => 'Rjadki na wuslědk:',
'contextchars'                  => 'Kontekst na rjadku:',
'stub-threshold'                => 'Wotkazowe formatowanje <a href="#" class="stub">małych stronow</a> (w bajtach):',
'stub-threshold-disabled'       => 'Znjemóžnjeny',
'recentchangesdays'             => 'Ličba dnjow w lisćinje aktualnych změnow:',
'recentchangesdays-max'         => '(Maksimalnje $1 {{PLURAL:$1|dźeń|dnjej|dny|dnjow}})',
'recentchangescount'            => 'Ličba stronow, kotraž ma so po standardźe pokazać:',
'prefs-help-recentchangescount' => 'To zapřijima aktualne změny, stawizny stronow a protokole.',
'prefs-help-watchlist-token'    => 'Wupjelnjenje tutoho pola z tajnym klučom budźe RSS-kanal za twoje wobkedźbowanki wupłodźić.
Něchtó, kiž kluč w tutym polu znaje, móže twoje wobkedźbowanki čitać, wubjer tohodla wěstu hódnotu.
Tu je připadnje wupłodźena hódnota, kotruž móžeš wužiwać: $1',
'savedprefs'                    => 'Nastajenja buchu składowane.',
'timezonelegend'                => 'Časowe pasmo:',
'localtime'                     => 'Lokalny čas:',
'timezoneuseserverdefault'      => 'Standard serwera wužiwać',
'timezoneuseoffset'             => 'Druhe (pódaj wotchilenje)',
'timezoneoffset'                => 'Rozdźěl¹:',
'servertime'                    => 'Čas serwera:',
'guesstimezone'                 => 'Z wobhladowaka přewzać',
'timezoneregion-africa'         => 'Afrika',
'timezoneregion-america'        => 'Amerika',
'timezoneregion-antarctica'     => 'Antarktika',
'timezoneregion-arctic'         => 'Arktis',
'timezoneregion-asia'           => 'Azija',
'timezoneregion-atlantic'       => 'Atlantiski ocean',
'timezoneregion-australia'      => 'Awstralija',
'timezoneregion-europe'         => 'Europa',
'timezoneregion-indian'         => 'Indiski ocean',
'timezoneregion-pacific'        => 'Pacifiski ocean',
'allowemail'                    => 'Mejlki wot druhich wužiwarjow přijimować',
'prefs-searchoptions'           => 'Pytanske opcije',
'prefs-namespaces'              => 'Mjenowe rumy',
'defaultns'                     => 'Hewak w tutych mjenowych rumach pytać:',
'default'                       => 'standard',
'prefs-files'                   => 'Dataje',
'prefs-custom-css'              => 'Swójski CSS',
'prefs-custom-js'               => 'Swójski JS',
'prefs-common-css-js'           => 'Zhromadny CSS/JS za w32 šaty:',
'prefs-reset-intro'             => 'You can use this page to reset your preferences to the site defaults. This cannot be undone.
Móžeš tutu stronu wužiwać, zo by swoje nastajenja na standardne hódnoty sydła wróćo stajić. To njeda so anulować.',
'prefs-emailconfirm-label'      => 'E-mejlowe wobkrućenje:',
'prefs-textboxsize'             => 'Wulkosć wobdźěłowanskeho wokna',
'youremail'                     => 'E-mejl *:',
'username'                      => 'Wužiwarske mjeno:',
'uid'                           => 'ID wužiwarja:',
'prefs-memberingroups'          => 'Čłon {{PLURAL:$1|wužiwarskeje skupiny|wužiwarskeju skupinow|wužiwarskich skupinow|wužiwarskich skupinow}}:',
'prefs-registration'            => 'Čas registracije:',
'yourrealname'                  => 'Woprawdźite mjeno *',
'yourlanguage'                  => 'Rěč:',
'yourvariant'                   => 'Warianta:',
'yournick'                      => 'Podpis:',
'prefs-help-signature'          => 'Komentary na diskusijnch stronach měli so přez "<nowiki>~~~~</nowiki>" podpisać, kotrež so do twojeje signatury a časoweho kołka konwertuje.',
'badsig'                        => 'Njepłaćiwa signatura, prošu HTML přepruwować.',
'badsiglength'                  => 'Twoja signatura je předołha.
Smě mjenje hač $1 {{PLURAL:$1|znamješko|znamješce|znamješka|znamješkow}} dołha być.',
'yourgender'                    => 'Splah:',
'gender-unknown'                => 'Njepodaty',
'gender-male'                   => 'Muski',
'gender-female'                 => 'Žónski',
'prefs-help-gender'             => 'Opcionalny: wužiwa so za po splahu specifiske narěčenje přez softwaru. Tuta informacija budźe zjawna.',
'email'                         => 'E-mejl',
'prefs-help-realname'           => '* Woprawdźite mjeno (opcionalne): jeli so rozsudźiš to zapodać, budźe to so wužiwać, zo by tebi woprawnjenje za twoje dźěło dało.',
'prefs-help-email'              => 'E-mejlowa adresa je opcionalna, ale zmóžnja ći nowe hesło emejlować, jeli sy swoje hesło zabył. Móžeš tež druhim dowolić, će přez swoju wužiwarsku abo diskusijnu stronu skontaktować, bjeztoho zo by dyrbjał swoju identitu wotkrył.',
'prefs-help-email-required'     => 'Je płaćiwa emejlowa adresa trjeba.',
'prefs-info'                    => 'Zakładne informacije',
'prefs-i18n'                    => 'Internacionalizacija',
'prefs-signature'               => 'Podpis',
'prefs-dateformat'              => 'Datumowy format',
'prefs-timeoffset'              => 'Časowe wotchilenje',
'prefs-advancedediting'         => 'Rozšěrjene opcije',
'prefs-advancedrc'              => 'Rozšěrjene opcije',
'prefs-advancedrendering'       => 'Rozšěrjene opcije',
'prefs-advancedsearchoptions'   => 'Rozšěrjene opcije',
'prefs-advancedwatchlist'       => 'Rozšěrjene opcije',
'prefs-displayrc'               => 'Zwobraznjenske opcije',
'prefs-displaysearchoptions'    => 'Zwobraznjenske opcije',
'prefs-displaywatchlist'        => 'Zwobraznjenske opcije',
'prefs-diffs'                   => 'Rozdźěle',

# User rights
'userrights'                   => 'Zrjadowanje wužiwarskich prawow',
'userrights-lookup-user'       => 'Wužiwarske skupiny zrjadować',
'userrights-user-editname'     => 'Wužiwarske mjeno:',
'editusergroup'                => 'Wužiwarske skupiny wobdźěłać',
'editinguser'                  => "Měnja so wužiwarske prawa wot wužiwarja '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'Wužiwarske skupiny wobdźěłać',
'saveusergroups'               => 'Wužiwarske skupiny składować',
'userrights-groupsmember'      => 'Čłon skupiny:',
'userrights-groupsmember-auto' => 'Implicitny čłon wot:',
'userrights-groups-help'       => 'Móžeš skupiny změnić, w kotrychž wužiwar je.
* Markěrowany kašćik woznamjenja, zo wužiwar je w tej skupinje.
* Njemarkěrowany kašćik woznamjenja, zo wužiwar w tej skupinje njeje.
* "*" podawa, zo njemóžeš skupinu wotstronić, tak ruče kaž sy ju přidał abo nawopak.',
'userrights-reason'            => 'Přičina:',
'userrights-no-interwiki'      => 'Nimaš prawo wužiwarske prawa w druhich wikijach změnić.',
'userrights-nodatabase'        => 'Datowa banka $1 njeeksistuje abo lokalna njeje.',
'userrights-nologin'           => 'Dyrbiš so z admininstratorowym kontom [[Special:UserLogin|přizjewić]], zo by wužiwarske prawa změnił.',
'userrights-notallowed'        => 'Twoje konto nima trěbne prawa, zo by wužiwarske prawa přidźělił.',
'userrights-changeable-col'    => 'Skupiny, kotrež móžeš změnić',
'userrights-unchangeable-col'  => 'Skupiny, kotrež njemóžeš změnić',

# Groups
'group'               => 'Skupina:',
'group-user'          => 'wužiwarjo',
'group-autoconfirmed' => 'awtomatisce potwjerdźeny',
'group-bot'           => 'Boty',
'group-sysop'         => 'Administratorojo',
'group-bureaucrat'    => 'Běrokraća',
'group-suppress'      => 'dohladowarjo',
'group-all'           => '(wšě)',

'group-user-member'          => 'Wužiwar',
'group-autoconfirmed-member' => 'Potwjerdźeny wužiwar',
'group-bot-member'           => 'bot',
'group-sysop-member'         => 'administrator',
'group-bureaucrat-member'    => 'běrokrat',
'group-suppress-member'      => 'Dohladowar',

'grouppage-user'          => '{{ns:project}}:Wužiwarjo',
'grouppage-autoconfirmed' => '{{ns:project}}:Awtomatisce potwjerdźeni wužiwarjo',
'grouppage-bot'           => '{{ns:project}}:Boćiki',
'grouppage-sysop'         => '{{ns:project}}:Administratorojo',
'grouppage-bureaucrat'    => '{{ns:project}}:Běrokraća',
'grouppage-suppress'      => '{{ns:project}}:Dohladowanje',

# Rights
'right-read'                  => 'Strony čitać',
'right-edit'                  => 'Strony wobdźěłać',
'right-createpage'            => 'Strony wutworić (kotrež diskusijne strony njejsu)',
'right-createtalk'            => 'Diskusijne strony wutworić',
'right-createaccount'         => 'Nowe wužiwarske konta wutworić',
'right-minoredit'             => 'Změny jako snadne markěrować',
'right-move'                  => 'Strony přesunyć',
'right-move-subpages'         => 'Strony z jich podstronami přesunyć',
'right-move-rootuserpages'    => 'Hłowne wužiwarske strony přesunyć',
'right-movefile'              => 'Dataje přesunyć',
'right-suppressredirect'      => 'Při přesunjenju strony ze stareho mjena žane daleposrědkowanje wutworić',
'right-upload'                => 'Dataje nahrać',
'right-reupload'              => 'Eksistowacu dataju přepisać',
'right-reupload-own'          => 'Eksistowacu dataju, kotraž bu wot samsneho wužiwarja nahrata, přepisać',
'right-reupload-shared'       => 'Dataje w hromadźe wužiwanej repozitoriju lokalnje přepisać',
'right-upload_by_url'         => 'Dataju z URL-adresy nahrać',
'right-purge'                 => 'Pufrowak sydła za stronu bjez wobkrućenskeje strony wuprózdnić',
'right-autoconfirmed'         => 'Połzaškitane strony wobdźěłać',
'right-bot'                   => 'Ma so jako awtomatiski proces wobjednać',
'right-nominornewtalk'        => 'Snadne změny k diskusijnym stronam zwobraznjenje nowych powěsćow wuwołać njedać',
'right-apihighlimits'         => 'Wyše limity wi API-naprašowanjach wužiwać',
'right-writeapi'              => 'writeAPI wužiwać',
'right-delete'                => 'Strony zhašeć',
'right-bigdelete'             => 'Strony z dołhimi stawiznami zničić',
'right-deleterevision'        => 'Jednotliwe wersije wušmórnyć a wobnowić',
'right-deletedhistory'        => 'Wušmórnjene zapiski stawiznow bjez přisłušneho teksta wobhladać',
'right-deletedtext'           => 'Wušmórnjeny tekst a změny mjez wušmórnjenymi wersijemi sej wobhladać',
'right-browsearchive'         => 'Zničene strony pytać',
'right-undelete'              => 'Strony wobnowić',
'right-suppressrevision'      => 'Wersije, kotrež su před administratorami schowane, přepruwować a wobnowić',
'right-suppressionlog'        => 'Priwatne protokole wobhladać',
'right-block'                 => 'Druhich wužiwarjow při wobdźěłowanju haćić',
'right-blockemail'            => 'Wužiwarja při słanju e-mejlow haćić',
'right-hideuser'              => 'Wužiwarske mjeno blokować a schować',
'right-ipblock-exempt'        => 'Blokowanja IP, awtomatiske blokowanje a blokowanja wobwodow wobeńć',
'right-proxyunbannable'       => 'Automatiske blokowanja proksyjow wobeńć',
'right-unblockself'           => 'Swójske blokowanje zběhnyć',
'right-protect'               => 'Škitowe schodźenki změnić a škitanu stronu wobdźěłać',
'right-editprotected'         => 'Škitane strony wobdźěłać (bjez kaskadoweho škita)',
'right-editinterface'         => 'Wužiwarski powjerch wobdźěłać',
'right-editusercssjs'         => 'Dataje CSS a JS druhich wužiwarjow wobdźěłać',
'right-editusercss'           => 'Dataje CSS druhich wužiwarjow wobdźěłać',
'right-edituserjs'            => 'Dataje JS druhich wužiwarjow wobdźěłać',
'right-rollback'              => 'Poslednjeho wužiwarja, kotryž wěstu stronu wobdźěła, spěšnje rewertować',
'right-markbotedits'          => 'Rewertowane změny jako botowe změny markěrować',
'right-noratelimit'           => 'Přez žane limity wobmjezowane',
'right-import'                => 'Strony z druhich wikijow importować',
'right-importupload'          => 'Strony přez nahraće datajow importować',
'right-patrol'                => 'Změny jako dohladowane markěrować',
'right-autopatrol'            => 'Změny awtomatisce jako dohladowane markěrować dać',
'right-patrolmarks'           => 'Kontrolowe marki w najnowšich změnach wobhladać',
'right-unwatchedpages'        => 'Lisćinu njewobkedźbowanych stronow wobhladać',
'right-trackback'             => 'Trackback pósłać',
'right-mergehistory'          => 'Stawizny stronow zjednoćić',
'right-userrights'            => 'Wužiwarske prawa wobdźěłać',
'right-userrights-interwiki'  => 'Wužiwarske prawa wužiwarjow druhich wikijow wobdźěłać',
'right-siteadmin'             => 'Datowu banku zawrěć a wotewrěć',
'right-reset-passwords'       => 'Hesła druhich wužiwarjow wróćo stajić',
'right-override-export-depth' => 'Strony inkluziwnje wotkazanych stronow hač do hłubokosće 5 eksportować',
'right-sendemail'             => 'Druhim wužiwarjam e-mejl pósłać',
'right-revisionmove'          => 'Wersije přesunyć',

# User rights log
'rightslog'      => 'Protokol zrjadowanja wužiwarskich prawow',
'rightslogtext'  => 'To je protokol změnow wužiwarskich prawow.',
'rightslogentry' => 'skupinowe čłonstwo za $1 z $2 na $3 změnjene',
'rightsnone'     => '(ničo)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'tutu stronu čitać',
'action-edit'                 => 'tutu stronu wobdźěłać',
'action-createpage'           => 'strony wutworić',
'action-createtalk'           => 'diskusijne strony wutworić',
'action-createaccount'        => 'wužiwarske konto załožić',
'action-minoredit'            => 'tutu změnu jako snadnu markěrować',
'action-move'                 => 'tutu stronu přesunyć',
'action-move-subpages'        => 'tutu stronu a jeje podstrony přesunyć',
'action-move-rootuserpages'   => 'hłowne wužiwarske strony přesunyć',
'action-movefile'             => 'Tutu dataju přesunyć',
'action-upload'               => 'tutu dataju nahrać',
'action-reupload'             => 'eksistowacu dataju přepisać',
'action-reupload-shared'      => 'tutu dataju na zhromadnym repozitoriju přepisać',
'action-upload_by_url'        => 'Tutu dataju z webadresy (URL) nahrać',
'action-writeapi'             => 'API za napisanje wužiwać',
'action-delete'               => 'tutu stronu zničić',
'action-deleterevision'       => 'tutu wersiju wušmórnyć',
'action-deletedhistory'       => 'Zničene wersije tuteje strony zwobraznić',
'action-browsearchive'        => 'Zničene strony pytać',
'action-undelete'             => 'tutu stronu wobnowić',
'action-suppressrevision'     => 'Tutu schowanu wersiju přepruwować a wobnowić',
'action-suppressionlog'       => 'Tutón priwatny protokol wobhladać',
'action-block'                => 'Wobdźěłanju přez wužiwarja zadźěwać',
'action-protect'              => 'škitowe runiny za tutu stronu změnić',
'action-import'               => 'Tutu stronu z druheho wikija importować',
'action-importupload'         => 'Tutu stronu z datajoweho nahraća importować',
'action-patrol'               => 'Změny druhich wužiwarjiw jako dohladowane markować',
'action-autopatrol'           => 'twoju změnu jako dohladowanu markować dać',
'action-unwatchedpages'       => 'lisćinu njewobkedźbowanych stronow zwobraznić',
'action-trackback'            => 'Trackback pósłać',
'action-mergehistory'         => 'stawizny tuteje strony zjednoćić',
'action-userrights'           => 'wšě wužiwarske prawa wobdźěłać',
'action-userrights-interwiki' => 'Wužiwarske prawa wužiwarjow w druhich wikijach wobdźěłać',
'action-siteadmin'            => 'Datowu banku zawrěć abo wotewrić',
'action-revisionmove'         => 'wersije  přesunyć',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|změna|změnje|změny|změnow}}',
'recentchanges'                     => 'Aktualne změny',
'recentchanges-legend'              => 'Opcije aktualnych změnow',
'recentchangestext'                 => 'Na tutej stronje móžeš najaktualniše změny w {{GRAMMAR:lokatiw|{{SITENAME}}}} wobkedźbować.',
'recentchanges-feed-description'    => 'Slěduj najaktualniše změny {{GRAMMAR:genitiw|{{SITENAME}}}} w tutym kanalu.',
'recentchanges-label-newpage'       => 'Tuta změna je nowu stronu wutworiła',
'recentchanges-label-minor'         => 'To je snadna změna',
'recentchanges-label-bot'           => 'Tuta změna bu přez roboćik přewjedźena',
'recentchanges-label-unpatrolled'   => 'Tuta změnu hišće njebu přepruwowana',
'rcnote'                            => "Deleka {{PLURAL:\$1|je '''1''' změna|stej poslednjej '''\$1''' změnje|su poslednje '''\$1''' změny|je poslednich '''\$1''' změnow}} za {{PLURAL:\$2|posledny dźeń|poslednjej '''\$2''' dnjej|poslednje '''\$2''' dny|poslednich '''\$2''' dnjow}}, staw wot \$4, \$5.
<div id=\"rc-legend\" style=\"float:right;font-size:84%;margin-left:5px;\"> <b>Legenda</b><br />
<b><tt>N</tt></b>&nbsp;– Nowy přinošk<br /> <b><tt>S</tt></b>&nbsp;– Snadna změna<br /> <b><tt>B</tt></b>&nbsp;– Změny awtomatiskich programow (bot)<br />  ''(± ličba)''&nbsp;– Změna wulkosće w bajtach </div>",
'rcnotefrom'                        => "Deleka so změny wot '''$2''' pokazuja (hač k '''$1''').",
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
'newpageletter'                     => 'N',
'boteditletter'                     => 'B',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|wobkedźbowacy wužiwar|wobkedźbowacaj wužiwarjej|wobkedźbowacy wužiwarjo|wobkedźbowacych wužiwarjow}}]',
'rc_categories'                     => 'Jenož kategorije (dźělene z "|")',
'rc_categories_any'                 => 'wšě',
'rc-change-size'                    => '$1 {{PLURAL:$1|bajt|bajtaj|bajty|bajtow}}',
'newsectionsummary'                 => 'Nowy wotrězk: /* $1 */',
'rc-enhanced-expand'                => 'Podrobnosće pokazać (wužaduje JavaScript)',
'rc-enhanced-hide'                  => 'Podrobnosće schować',

# Recent changes linked
'recentchangeslinked'          => 'Změny zwjazanych stron',
'recentchangeslinked-feed'     => 'Změny zwjazanych stron',
'recentchangeslinked-toolbox'  => 'Změny zwjazanych stron',
'recentchangeslinked-title'    => 'Změny na stronach, kotrež su z „$1“ wotkazane',
'recentchangeslinked-noresult' => 'Njejsu změny zwajzanych stron we wubranej dobje.',
'recentchangeslinked-summary'  => "Tuta strona nalistuje poslednje změny na wotkazanych stronach (resp. pola kategorijow na čłonach kategorije).
Strony na [[Special:Watchlist|wobkedźbowankach]] su '''tučne'''.",
'recentchangeslinked-page'     => 'Mjeno strony:',
'recentchangeslinked-to'       => 'Změny na stronach pokazać, kotrež na datu stronu wotkazuja',

# Upload
'upload'                      => 'Dataju nahrać',
'uploadbtn'                   => 'Dataju nahrać',
'reuploaddesc'                => 'Nahraće přetorhnyć a so k nahrawanskemu formularej wróćić.',
'upload-tryagain'             => 'Změnjene datajowe wopisanje wotpósłać',
'uploadnologin'               => 'Njepřizjewjeny',
'uploadnologintext'           => 'Dyrbiš [[Special:UserLogin|přizjewjeny]] być, zo by dataje nahrawać móhł.',
'upload_directory_missing'    => 'Zapis nahraćow ($1) faluje a njeda so přez webserwer wutworić.',
'upload_directory_read_only'  => 'Nahrawanski zapis ($1) njehodźi so přez webserwer popisować.',
'uploaderror'                 => 'Zmylk při nahrawanju',
'upload-recreate-warning'     => "'''Warnowanje: Dataja z tym mjenom je so zhašała abo přesunyła.'''

Protokolej zhašenjow a přesunjenjow za tutu stronu stej tu k dobroćiwemu wužiwanju podatej:",
'uploadtext'                  => "Wužij slědowacy formular, zo by nowe dataje nahrał.
Zo by prjedy nahrate dataje wobhladał abo pytał dźi k [[Special:FileList|lisćinje nahratych datajow]], nahraća so tež w [[Special:Log/upload|protokolu nahraćow]], wušmórnjenja  [[Special:Log/delete|protokolu wušmornjenjow]] protokoluja.

Zo by dataju do strony zapřijał, wužij wotkaz w jednej ze slědowacych formow:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Dataja.jpg]]</nowiki></tt>''', zo by połnu wersiju dataje wužiwał
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Dataja.png|200px|thumb|left|alternatiwny tekst]]</nowiki></tt>''', zo by wobraz ze šěrokosću 200 pikselow do kašćika na lěwej kromje z alternatiwnym tekstom jako wopisanje wužiwał
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Dataja.ogg]]</nowiki></tt>''' zo by direktnje k dataji wotkazał, bjeztoho zo by so dataja zwobrazniła",
'upload-permitted'            => 'Dowolene datajowe typy: $1.',
'upload-preferred'            => 'Preferowane datajowe typy: $1.',
'upload-prohibited'           => 'Zakazane datajowe typy: $1.',
'uploadlog'                   => 'Protokol nahraćow',
'uploadlogpage'               => 'Protokol nahraćow',
'uploadlogpagetext'           => 'Deleka je lisćina najnowšich nahratych datajow.
Hlej [[Special:NewFiles|galeriju nowych datajow]] za wizuelny přehlad.',
'filename'                    => 'Mjeno dataje',
'filedesc'                    => 'Zjeće',
'fileuploadsummary'           => 'Zjeće:',
'filereuploadsummary'         => 'Datajowe změny:',
'filestatus'                  => 'Licenca:',
'filesource'                  => 'Žórło:',
'uploadedfiles'               => 'Nahrate dataje',
'ignorewarning'               => 'Warnowanje ignorować a dataju najebać toho składować.',
'ignorewarnings'              => 'Wšě warnowanja ignorować',
'minlength1'                  => 'Datajowe mjena dyrbja znajmjeńša jedyn pismik dołhe być.',
'illegalfilename'             => 'Mjeno dataje „$1” wobsahuje znamješka, kotrež w titlach stronow dowolene njejsu. Prošu přemjenuj dataju a spytaj ju znowa nahrać.',
'badfilename'                 => 'Mjeno dataje bu do „$1” změnjene.',
'filetype-mime-mismatch'      => 'Datajowy sufiks njewotpowěduje MIME-typej.',
'filetype-badmime'            => 'Dataje typa MIME „$1” njesmědźa so nahrać.',
'filetype-bad-ie-mime'        => 'Tuta dataja njeda so nahrać, dokelž Internet Explorer by ju jako "$1" interpretował, kotryž je njedowoleny a potencielnje strašny datajowy typ.',
'filetype-unwanted-type'      => "'''\".\$1\"''' je njepožadany datajowy typ.
{{PLURAL:\$3|Preferowany datajowy typ je|Preferowanej datajowej typaj stej|Preferowane datajowe typy su|Preferowane datajowe typy su}} \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' njeje dowoleny datajowy typ.
{{PLURAL:\$3|Dowoleny datajowy typ je|Dowolenej datajowej typaj stej|Dowolene datajowe typy su|Dowolene datajowe typy su}} \$2.",
'filetype-missing'            => 'Dataja nima kóncowku (na přikład „.jpg“).',
'empty-file'                  => 'Dataja, kotruž sy wotpósłał, bě prózdna.',
'file-too-large'              => 'Dataja, kotruž sy wotpósłał, bě přewulka.',
'filename-tooshort'           => 'Datajowe mjeno je překrótke.',
'filetype-banned'             => 'Tutón datajowy typ je zatamany.',
'verification-error'          => 'Tuta dataja žane datajowe přepruwowanje njepřeběhny.',
'hookaborted'                 => 'Změna, kotruž pospytowaše přewjesć, bu přez rozšěrjensku hoku přetorhnjena.',
'illegal-filename'            => 'Datajowe mjeno njeje dowolene.',
'overwrite'                   => 'Přepisowanje eksistowaceje dataje njeje dowolene.',
'unknown-error'               => 'Njeznaty zmylk je wustupił.',
'tmp-create-error'            => 'Temporerna dataja njeda so wutworić.',
'tmp-write-error'             => 'Zmylk při pisanju temporerneje dataje.',
'large-file'                  => 'Doporuča so, zo dataje wjetše hač $1 njejsu; tuta dataja ma $2.',
'largefileserver'             => 'Dataja je wjetša hač serwer dowoluje.',
'emptyfile'                   => 'Dataja, kotruž sy nahrał, zda so prózdna być. Z přičinu móhł pisanski zmylk w mjenje dataje być. Prošu pruwuj hač chceš ju woprawdźe nahrać.',
'fileexists'                  => "Dataja z tutym mjenom hižo eksistuje.
Jeli kliknješ na „Składować”, so wona přepisuje.
Prošu pruwuj '''<tt>[[:$1]]</tt>''' jeli njejsy wěsty hač chceš ju změnić.
[[$1|thumb]]",
'filepageexists'              => "Wopisanska strona za tutu dataju bu hižo pola '''<tt>[[:$1]]</tt>''' wutworjena,
ale tuchwilu dataja z tutym mjenom njeeksistuje.
Zjeće, kotrež zapodaš, njebudźe so na wopisanskej stronje jewić.
Zo by so twoje zjeće tam jewiło, dyrbiš ju manuelnje wobdźěłać.
[[$1|thumb]]",
'fileexists-extension'        => "Dataja z podobnym mjenom hižo eksistuje: [[$2|thumb]]
* Mjeno dataje, kotruž chceš nahrać: '''<tt>[[:$1]]</tt>'''
* Mjeno eksistowaceje dataje: '''<tt>[[:$2]]</tt>'''
Prošu wubjer druhe mjeno.",
'fileexists-thumbnail-yes'    => "Dataja zda so minaturka ''(thumbnail)'' być. [[$1|thumb]]
Prošu přepruwuj dataju '''<tt>[[:$1]]</tt>'''.
Jeli je to wobraz w originalnej wulkosći, njetrjebaš minaturku nahrać.",
'file-thumbnail-no'           => "Mjeno dataje započina so z '''<tt>$1</tt>'''. Zda so, zo to je wobraz z redukowanej wulkosću ''(thumbnail)'' pokazać.
Jeli maš tutón wobraz z połnym rozeznaćom, nahraj tutón, hewak změń prošu datajowe mjeno.",
'fileexists-forbidden'        => 'Dataja z tutym mjenom hižo eksistuje a njeda so přepisać. Jeli hišće chceš swoju dataju nahrać, dźi  prošu wróćo a wuž nowe mjeno. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Dataja z tutym mjenom hižo eksistuje w zhromadnej chowarni. Jeli hišće chceš swoju dataju nahrać,  dźi prošu wróćo a wužij nowe mjeno. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Tuta dataja je duplikat {{PLURAL:$1|slědowaceje dataje|slědowaceju datajow|slědowacych datajow|slědowacych datajow}}:',
'file-deleted-duplicate'      => 'Dataja, kotraž je identiska z tutej dataju ([[$1]]), je so prjedy zničiła. Ty měł stawizny zničenja tuteje dataje přepruwować, prjedy pokročuješ z jeje zasonahrawanjom.',
'uploadwarning'               => 'Warnowanje',
'uploadwarning-text'          => 'Prošu změń slědowace datajowe wopisanje a spytaj hišće raz.',
'savefile'                    => 'Dataju składować',
'uploadedimage'               => 'je dataju „[[$1]]” nahrał',
'overwroteimage'              => 'je nowu wersiju dataje „[[$1]]“ nahrał',
'uploaddisabled'              => 'Wodaj, nahraće je znjemóžnjene.',
'copyuploaddisabled'          => 'Nahraće přez URL znjemóžnjene.',
'uploadfromurl-queued'        => 'Twoje nahraće je nětko w čakanskim rynku.',
'uploaddisabledtext'          => 'Nahraće datajow je znjemóžnjene.',
'php-uploaddisabledtext'      => 'Nahraća PHP-datajow su znjemóžnjene. Prošu skontroluj nastajenje file_uploads.',
'uploadscripted'              => 'Dataja wobsahuje HTML- abo skriptowy kod, kotryž móhł so mylnje přez wobhladowak wuwjesć.',
'uploadvirus'                 => 'Dataja wirus wobsahuje! Podrobnosće: $1',
'upload-source'               => 'Žórłowa dataja',
'sourcefilename'              => 'Mjeno žórłoweje dataje:',
'sourceurl'                   => 'URL žórła:',
'destfilename'                => 'Mjeno ciloweje dataje:',
'upload-maxfilesize'          => 'Maksimalna datajowa wulkosć: $1',
'upload-description'          => 'Datajowe wopisanje',
'upload-options'              => 'Nahrawanske opcije',
'watchthisupload'             => 'Tutu dataju wobkedźbować',
'filewasdeleted'              => 'Dataja z tutym mjenom bu prjedy nahrata a pozdźišo wušmórnjena. Prošu přepruwuj $1 prjedy hač ju znowa składuješ.',
'upload-wasdeleted'           => "'''Kedźbu: Nahrawaš dataju, kotraž bu prjedy wušmórnjena.'''

Prošu přepruwuj dokładnje, hač wospjetowane nahraće směrnicam wotpowěduje.
Za twoju informaciju slěduje protokol wušmórnjenjow z wopodstatnjenjom za předchadne wušmórnjenje:",
'filename-bad-prefix'         => "Datajowe mjeno započina so z '''„$1“'''. To je powšitkownje datajowe mjeno, kotrež digitalna kamera zwjetša dawa a kotrež tohodla jara wuprajiwe njeje. Prošu wubjer bóle wuprajiwe mjeno za twoju dataju.",
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
'upload-success-subj'         => 'Dataja bu wuspěšnje nahrata',
'upload-success-msg'          => 'Twoje nahraće z [$2] je wuspěšne było: Steji tu k dispoziciji: [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'Nahrawanski problem',
'upload-failure-msg'          => 'Bě problem z twojim nahraćom [$2]:

$1',
'upload-warning-subj'         => 'Nahraćowe warnowanje',
'upload-warning-msg'          => 'Bě problem z twojim nahraćom [$2]. Wróć so k [[Special:Upload/stash/$1|nahrawanskemu formularej]], zo by tutón problem wotstronił.',

'upload-proto-error'        => 'Wopačny protokol',
'upload-proto-error-text'   => 'URL dyrbi so z <code>http://</code> abo <code>ftp://</code> započeć.',
'upload-file-error'         => 'Nutřkowny zmylk',
'upload-file-error-text'    => 'Nutřkowny zmylk wustupi při pospytu, nachwilnu dataju na serwerje wutworić. Prošu skontaktuj [[Special:ListUsers/sysop|administratora]].',
'upload-misc-error'         => 'Njeznaty zmylk při nahraću',
'upload-misc-error-text'    => 'Njeznaty zmylk wustupi při nahrawanju. Prošu přepruwuj, hač URL je płaćiwy a přistupny a spytaj hišće raz. Jeli problem dale eksistuje, skontaktuj [[Special:ListUsers/sysop|administratora]].',
'upload-too-many-redirects' => 'URL wobsahowaše přewjele daleposrědkowanjow',
'upload-unknown-size'       => 'Njeznata wulkosć',
'upload-http-error'         => 'HTTP-zmylk je wustupił: $1',

# img_auth script messages
'img-auth-accessdenied' => 'Přistup wotpokazany',
'img-auth-nopathinfo'   => 'PATH_INFO faluje.
Twój serwer njeje za to konfigurował, zo by tute informacije dale posrědkował.
By móhł na CGI bazować a ani njemóže img_auth podpěrać.
Hlej http://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir'     => 'Požadana šćežka w konfigurowanym nahraćowym zapisu njeje.',
'img-auth-badtitle'     => 'Njeje móžno z "$1" płaćiwy titul tworić.',
'img-auth-nologinnWL'   => 'Njejsy přizjewjeny a "$1" w běłej lisćinje njeje.',
'img-auth-nofile'       => 'Dataja "$1" njeeksistuje.',
'img-auth-isdir'        => 'Popsytuješ na zapis "$1" přistup měć.
Jenož datajowy přistup je dowoleny.',
'img-auth-streaming'    => '"$1" so prudźi.',
'img-auth-public'       => 'Funkcija img_auth.php je za wudaće datjow z priwatneho wikija.
Tutón wiki je jako zjawny wiki konfigurowany.
Za optimalnu wěstotu je img_auth.php znjemóžnjeny.',
'img-auth-noread'       => 'Wužiwar nima přistup, zo by "$1" čitał.',

# HTTP errors
'http-invalid-url'      => 'Njepłaćiwy URL: $1',
'http-invalid-scheme'   => 'URL ze šemu „$1“ so njepodpěruja.',
'http-request-error'    => 'Naprašowanje HTTP je so njeznateho zmylka dla njeporadźiło.',
'http-read-error'       => 'Čitanski zmylk HTTP.',
'http-timed-out'        => 'Naprašowanje HTTP je čas překročiło.',
'http-curl-error'       => 'Zmylk při wołanju URL: $1',
'http-host-unreachable' => 'URL njeda so docpěć.',
'http-bad-status'       => 'Problem je za HTTP-naprašowanje wustupił: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL docpějomny njeje.',
'upload-curl-error6-text'  => 'Podaty URL njehodźeše so docpěć. Prošu přepruwuj, hač URL je korektny a sydło docpějomne.',
'upload-curl-error28'      => 'Překročenje časa při nahrawanju',
'upload-curl-error28-text' => 'Sydło za wotmołwu předołho trjebaše. Prošu pruwuj, hač sydło je docpějomne, čakaj wokomik a spytaj hišće raz. Spytaj hewak w druhim času hišće raz.',

'license'            => 'Licenca:',
'license-header'     => 'Licencowanje',
'nolicense'          => 'žadyn wuběr',
'license-nopreview'  => '(žadyn přehlad k dispoziciji)',
'upload_source_url'  => ' (płaćiwy, zjawnje docpějomny URL)',
'upload_source_file' => ' (dataja na twojim ličaku)',

# Special:ListFiles
'listfiles-summary'     => 'Tuta specialna strona naliči wšě nahrate dataje. Standardnje so naposlědk nahrate dateje cyle horjeka pokazuja. Kliknjo na nadpisma stołpikow móžeš sortěrowanje wobroćić abo po druhich kriterijach rjadować.',
'listfiles_search_for'  => 'Za mjenom wobraza pytać:',
'imgfile'               => 'dataja',
'listfiles'             => 'Lisćina datajow',
'listfiles_thumb'       => 'Wobrazk',
'listfiles_date'        => 'datum',
'listfiles_name'        => 'mjeno dataje',
'listfiles_user'        => 'wužiwar',
'listfiles_size'        => 'wulkosć (byte)',
'listfiles_description' => 'wopisanje',
'listfiles_count'       => 'Wersije',

# File description page
'file-anchor-link'          => 'Dataja',
'filehist'                  => 'Wersije dataje',
'filehist-help'             => 'Klikń na wěsty čas, zo by wersiju dataje w tutym času zwobraznił.',
'filehist-deleteall'        => 'wšě wersije wušmórnyć',
'filehist-deleteone'        => 'tutu wersiju wušmórnyć',
'filehist-revert'           => 'cofnyć',
'filehist-current'          => 'aktualnje',
'filehist-datetime'         => 'Čas',
'filehist-thumb'            => 'Wobrazowy napohlad',
'filehist-thumbtext'        => 'Wobrazowy napohlad za wersiju wot $1',
'filehist-nothumb'          => 'Žadyn wobrazowy napohlad',
'filehist-user'             => 'Wužiwar',
'filehist-dimensions'       => 'Rozeznaće',
'filehist-filesize'         => 'Wulkosć dataje',
'filehist-comment'          => 'Komentar',
'filehist-missing'          => 'Dataja pobrachuje',
'imagelinks'                => 'Datajowe wotkazy',
'linkstoimage'              => '{{PLURAL:$1|Slědowaca strona wotkazuje|Slědowacej $1 stronje wotkazujetej|Slědowace $1 strony wotkazuja|Slědowacych $1 stronow wotkazuje}} na tutu dataju:',
'linkstoimage-more'         => 'Wjace hač $1 {{PLURAL:$1|strona wotkazuje|stronje wotkazujetej|strony wotkazuja|stronow wotkazuje}} na tutu dataju.
Slědowaca lisćina pokazuje jenož {{PLURAL:$1|prěni wotkaz strony|prěnjej $1 wotkazaj strony|prěnje $1 wotkazy strony|prěnich $1 wotkazow strony}} na tutu dataju.
[[Special:WhatLinksHere/$2|Dospołna lisćina]] steji k dispoziciji.',
'nolinkstoimage'            => 'Njejsu strony, kotrež na tutu dataju wotkazuja.',
'morelinkstoimage'          => '[[Special:WhatLinksHere/$1|Dalše wotkazy]] k tutej dataji wobhladać.',
'redirectstofile'           => '{{PLURAL:$1|Slědowaca dataja pósrednja|Slědowacej $1 pósrědnjatej|Slědowace $1 posrědnjaju|Slěddowacych $1 pósrědnja}} k toś tej dataji dalej:',
'duplicatesoffile'          => '{{PLURAL:$1|Slědowaca dataja je duplikat|Slědowacej $1 dataji stej duplikata|Slědowace $1 dataje su duplikaty|Slědowacych $1 duplikatow je duplikaty}} tuteje dataje ([[Special:FileDuplicateSearch/$2|dalše podrobnosće]])::',
'sharedupload'              => 'Tuta dataja je z $1 a da so za druhe projekty wužiwać.',
'sharedupload-desc-there'   => 'Tuta dataja je z $1 a da so přez druhe projekty wužiwać. Prošu hlej [$2 stronu datajoweho wopisanja] za dalše informacije.',
'sharedupload-desc-here'    => 'Tuta dataja je z $1 a da so přez druhe projekty wužiwać. Wopisanje na jeje [$2 stronje datajoweho wopisanja] so deleka pokazuje.',
'filepage-nofile'           => 'Dataja z tutym mjenom njeeksistuje.',
'filepage-nofile-link'      => 'Dataju z tutym mjenom njeeksistuje, ale móžeš [$1 ju nahrać].',
'uploadnewversion-linktext' => 'nowu wersiju tuteje dataje nahrać',
'shared-repo-from'          => 'z $1',
'shared-repo'               => 'zhromadny repozitorij',

# File reversion
'filerevert'                => 'Wersiju $1 cofnyć',
'filerevert-legend'         => 'Dataju wróćo stajeć',
'filerevert-intro'          => "Stajiš dataju '''[[Media:$1|$1]]''' na [$4 wersiju wot $2, $3 hodź.] wróćo.",
'filerevert-comment'        => 'Přičina:',
'filerevert-defaultcomment' => 'wróćo stajene na wersiju wot $1, $2 hodź.',
'filerevert-submit'         => 'Cofnyć',
'filerevert-success'        => "'''[[Media:$1|$1]]''' bu na [$4 wersiju wot $2, $3 hodź.] wróćo stajeny.",
'filerevert-badversion'     => 'W zapodatym času žana wersija dataje njeje.',

# File deletion
'filedelete'                  => '„$1“ wušmórnyć',
'filedelete-legend'           => 'Wušmórnju dataju',
'filedelete-intro'            => "Šmórnješ dataju '''[[Media:$1|$1]]''' zhromadnje z jeje cyłymi stawiznami.",
'filedelete-intro-old'        => "Wušmórnješ wersiju '''[[Media:$1|$1]]''' wot [$4 wot $2, $3 hodź].",
'filedelete-comment'          => 'Přičina:',
'filedelete-submit'           => 'Wušmórnyć',
'filedelete-success'          => "Strona '''„$1“''' bu wušmórnjena.",
'filedelete-success-old'      => "Wersija '''[[Media:$1|$1]]''' wot $2, $3 hodź. bu zničena.",
'filedelete-nofile'           => "'''„$1“''' njeeksistuje.",
'filedelete-nofile-old'       => "Njeje žana archiwowana wersija '''$1''' z podatymi atributami.",
'filedelete-otherreason'      => 'Druha/přidatna přičina:',
'filedelete-reason-otherlist' => 'Druha přičina',
'filedelete-reason-dropdown'  => '*Powšitkowne přičina za wušmórnjenja
** Zranjenje awtorksich prawow
** Dwójna dataja',
'filedelete-edit-reasonlist'  => 'Přičiny za wušmórnjenje wobdźěłać',
'filedelete-maintenance'      => 'Wušmórnjenje a wobnowjenje datajow stej wothladowanja dla nachilu znjemóžnjenej.',

# MIME search
'mimesearch'         => 'Pytanje za typom MIME',
'mimesearch-summary' => 'Na tutej specialnej stronje hodźa so dataje po typje MIME filtrować. Dyrbiš přeco typ MIME a podtyp zapodać: <tt>image/jpeg</tt> (hlej stronu z wopisanjom wobraza).',
'mimetype'           => 'Typ MIME:',
'download'           => 'Sćahnyć',

# Unwatched pages
'unwatchedpages' => 'Njewobkedźbowane strony',

# List redirects
'listredirects' => 'Lisćina daleposrědkowanjow',

# Unused templates
'unusedtemplates'     => 'Njewužiwane předłohi',
'unusedtemplatestext' => 'Tuta strona nalistuje wšě strony w mjenowym rumje {{ns:template}}, kotrež so w druhich stronach njewužiwaja. Prošu přepruwuj druhe wotkazy k předłoham, prjedy hač je wušmórnješ.',
'unusedtemplateswlh'  => 'Druhe wotkazy',

# Random page
'randompage'         => 'Připadny nastawk',
'randompage-nopages' => 'W {{PLURAL:$2|slědowacym mjenowym rumje|slědowacymaj mjenowymaj rumomaj|slědowacych mjenowych rumach|slědowacych mjenowych rumach}} žane strony njejsu: $1',

# Random redirect
'randomredirect'         => 'Připadne daleposrědkowanje',
'randomredirect-nopages' => 'Žane daleposrědkowanja w mjenowym rumje "$1".',

# Statistics
'statistics'                   => 'Statistika',
'statistics-header-pages'      => 'Statistika stronow',
'statistics-header-edits'      => 'Wobdźěłanska statistika',
'statistics-header-views'      => 'Statistiku wobhladać',
'statistics-header-users'      => 'Statistika wužiwarjow',
'statistics-header-hooks'      => 'Druha statistika',
'statistics-articles'          => 'Wobsahowe strony',
'statistics-pages'             => 'Strony',
'statistics-pages-desc'        => 'Wšě strony we wikiju, inkluziwnje diskusijnych stronow, daleposrědkowanja atd.',
'statistics-files'             => 'Nahrate dataje',
'statistics-edits'             => 'Změny stronow wot załoženja {{SITENAME}}',
'statistics-edits-average'     => 'Změny na stronu w přerězku',
'statistics-views-total'       => 'Zwobraznjenja dohromady',
'statistics-views-total-desc'  => 'Pohlady do njeeksistowacych stronow a specialnych stronow njejsu zapřijate',
'statistics-views-peredit'     => 'Zwobraznjenja na změnu',
'statistics-users'             => 'Zregistrowani [[Special:ListUsers|wužiwarjo]]',
'statistics-users-active'      => 'Aktiwni wužiwarjo',
'statistics-users-active-desc' => 'Wužiwarjo, kotřiž su {{PLURAL:$1|wčera|w zańdźenymaj $1 dnjomaj|w zańdźenych $1 dnjach|w zańdźenych $1 dnjach}} aktiwni byli',
'statistics-mostpopular'       => 'Najhusćišo wopytowane strony',

'disambiguations'      => 'Rozjasnjenja wjacezmyslnosće',
'disambiguationspage'  => 'Template:Wjacezmyslnosć',
'disambiguations-text' => "Slědowace strony na '''rozjasnjenje wjacezmyslnosće''' wotkazuja. Měli město toho na poprawnu stronu wotkazać.<br />Strona so jako rozjasnjenje wjacezmyslnosće zarjaduje, jeli předłohu wužiwa, na kotruž so wot [[MediaWiki:Disambiguationspage]] wotkazuje.",

'doubleredirects'            => 'Dwójne daleposrědkowanja',
'doubleredirectstext'        => 'Tuta strona nalistuje strony, kotrež k druhim daleposrědkowanskim stronam dale posrědkuja.
Kóžda rjadka wobsahuje wotkazy k prěnjemu a druhemu daleposrědkowanju kaž tež cil druheho daleposrědkowanja, kotryž je zwjetša  "woprawdźita" cilowa strona, na kotruž prěnje daleposrědkowanje měło pokazać. <del>Přešmórnjene</del> zapiski su hižo sčinjene.',
'double-redirect-fixed-move' => '[[$1]] bu přesunjeny, je nětko daleposrědkowanje do [[$2]]',
'double-redirect-fixer'      => 'Porjedźer daleposrědkowanjow',

'brokenredirects'        => 'Skóncowane daleposrědkowanja',
'brokenredirectstext'    => 'Slědowace daleposrědkowanja wotkazuja na njeeksistowace strony:',
'brokenredirects-edit'   => 'wobdźěłać',
'brokenredirects-delete' => 'wušmórnyć',

'withoutinterwiki'         => 'Strony bjez mjezyrěčnych wotkazow',
'withoutinterwiki-summary' => 'Sćěhowace strony njewotkazuja na druhe rěčne wersije:',
'withoutinterwiki-legend'  => 'Prefiks',
'withoutinterwiki-submit'  => 'Pokazać',

'fewestrevisions' => 'Strony z najmjenje wersijemi',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|bajt|bajtaj|bajty|bajtow}}',
'ncategories'             => '$1 {{PLURAL:$1|jedna kategorija|kategoriji|kategorije|kategorijow}}',
'nlinks'                  => '$1 {{PLURAL:$1|wotkaz|wotkazaj|wotkazy|wotkazow}}',
'nmembers'                => '{{PLURAL:$1|$1 čłon|$1 čłonaj|$1 čłony|$1 čłonow}}',
'nrevisions'              => '$1 {{PLURAL:$1|wobdźěłanje|wobdźěłani|wobdźěłanja|wobdźěłanjow}}',
'nviews'                  => '$1 {{PLURAL:$1|jedyn wopyt|wopytaj|wopyty|wopytow}}',
'nimagelinks'             => 'Wužiwa so na $1 {{PLURAL:$1|stronje|stronomaj|stronach|stronach}}',
'ntransclusions'          => 'wužiwa so na $1 {{PLURAL:$1|stronje|stronomaj|stronach|stronach}}',
'specialpage-empty'       => 'Tuchwilu žane zapiski.',
'lonelypages'             => 'Wosyroćene strony',
'lonelypagestext'         => 'Slědowace strony njejsu wotkazowe cile druhich stronow abo njezapřijimaja so do druhich stronow w {{SITENAME}}.',
'uncategorizedpages'      => 'Njekategorizowane strony',
'uncategorizedcategories' => 'Njekategorizowane kategorije',
'uncategorizedimages'     => 'Njekategorizowane dataje',
'uncategorizedtemplates'  => 'Njekategorizowane předłohi',
'unusedcategories'        => 'Njewužiwane kategorije',
'unusedimages'            => 'Njewužiwane dataje',
'popularpages'            => 'Často wopytowane strony',
'wantedcategories'        => 'Požadane kategorije',
'wantedpages'             => 'Požadane strony',
'wantedpages-badtitle'    => 'Njepłaćiwy titul we wuslědku: $1',
'wantedfiles'             => 'Požadane dataje',
'wantedtemplates'         => 'Falowace předłohi',
'mostlinked'              => 'Z najwjace stronami zwjazane strony',
'mostlinkedcategories'    => 'Z najwjace stronami zwjazane kategorije',
'mostlinkedtemplates'     => 'Najhusćišo wužiwane předłohi',
'mostcategories'          => 'Strony z najwjace kategorijemi',
'mostimages'              => 'Z najwjace stronami zwjazane dataje',
'mostrevisions'           => 'Nastawki z najwjace wersijemi',
'prefixindex'             => 'Wšě strony z prefiksom',
'shortpages'              => 'Krótke nastawki',
'longpages'               => 'Dołhe nastawki',
'deadendpages'            => 'Nastawki bjez wotkazow',
'deadendpagestext'        => 'Slědowace strony njejsu z druhimi stronami w tutym wikiju zwjazane.',
'protectedpages'          => 'Škitane strony',
'protectedpages-indef'    => 'Jenož strony z njewobmjezowanym škitom',
'protectedpages-cascade'  => 'Jenož strony z kaskadowym škitom',
'protectedpagestext'      => 'Tuta specialna strona naliči wšě strony, kotrež su přećiwo přesunjenju abo wobdźěłowanju škitane.',
'protectedpagesempty'     => 'Tuchwilu žane.',
'protectedtitles'         => 'Škitane titule',
'protectedtitlestext'     => 'Slědowace titule su přećiwo wutworjenju škitane',
'protectedtitlesempty'    => 'Žane titule njejsu tuchwilu z tutymi parametrami škitane.',
'listusers'               => 'Lisćina wužiwarjow',
'listusers-editsonly'     => 'Jenož wužiwarjow ze změnami pokazać',
'listusers-creationsort'  => 'Po datumje wutworjenja sortěrować',
'usereditcount'           => '$1 {{PLURAL:$1|změna|změnje|změny|změnow}}',
'usercreated'             => 'Wutworjeny $1 $2 hodź.',
'newpages'                => 'Nowe strony',
'newpages-username'       => 'Wužiwarske mjeno:',
'ancientpages'            => 'Najstarše nastawki',
'move'                    => 'přesunyć',
'movethispage'            => 'Stronu přesunyć',
'unusedimagestext'        => 'Slědowace dataje eksistuja, njejsu wšak do strony zasadźene.
Prošu wobkedźbuj, zo druhe websydła móža na dataju z direktnym URL wotkazować a móža so tu hišće jewić, byrnjež w altiwnym wužiwanju byli.',
'unusedcategoriestext'    => 'Slědowace kategorije eksistuja, hačrunjež žana druha strona abo kategorija je njewužiwa.',
'notargettitle'           => 'Žadyn cil',
'notargettext'            => 'Njejsy cilowu stronu abo wužiwarja podał, zo by funkciju wuwjesć móhł.',
'nopagetitle'             => 'Žana tajka cilowa strona',
'nopagetext'              => 'Cilowa strona, kotruž sće podał, njeeksistuje.',
'pager-newer-n'           => '{{PLURAL:$1|nowši 1|nowšej $1|nowše $1|nowšich $1}}',
'pager-older-n'           => '{{PLURAL:$1|starši 1|staršej $1|starše $1|staršich $1}}',
'suppress'                => 'Dohladowanje',

# Book sources
'booksources'               => 'Pytanje po ISBN',
'booksources-search-legend' => 'Žórła za knihi pytać',
'booksources-go'            => 'Pytać',
'booksources-text'          => 'To je lisćina wotkazow k druhim sydłam, kotrež nowe a trjebane knihi předawaja. Tam móžeš tež dalše informacije wo knihach dóstać, kotrež pytaš:',
'booksources-invalid-isbn'  => 'Podate ISBN-čisło njezda so płaćiwe być; přepruwuj za zmylkami, z tym zo z orginialneho žórła kopěruješ.',

# Special:Log
'specialloguserlabel'  => 'Wužiwar:',
'speciallogtitlelabel' => 'Strona:',
'log'                  => 'Protokole',
'all-logs-page'        => 'Wšě zjawne protokole',
'alllogstext'          => 'Kombinowane zwobraznjenje wšěch k dispozicij stejacych protokolow w {{GRAMMAR:lokatiw|{{SITENAME}}}}. Móžeš napohlad wobmjezować, wuběrajo typ protokola, wužiwarske mjeno (dźiwajo na wulkopisanje) abo potrjechu stronu (tež dźiwajo na wulkopisanje).',
'logempty'             => 'Žane wotpowědowace zapiski w protokolu.',
'log-title-wildcard'   => 'Titul započina so z …',

# Special:AllPages
'allpages'          => 'Wšě nastawki',
'alphaindexline'    => '$1 do $2',
'nextpage'          => 'přichodna strona ($1)',
'prevpage'          => 'předchadna strona ($1)',
'allpagesfrom'      => 'Strony pokazać, započinajo z:',
'allpagesto'        => 'Strony pokazać, kotrež kónča so na:',
'allarticles'       => 'Wšě nastawki',
'allinnamespace'    => 'Wšě strony (mjenowy rum $1)',
'allnotinnamespace' => 'Wšě strony (nic w mjenowym rumje $1)',
'allpagesprev'      => 'Předchadne',
'allpagesnext'      => 'Přichodne',
'allpagessubmit'    => 'Pokazać',
'allpagesprefix'    => 'Strony pokazać z prefiksom:',
'allpagesbadtitle'  => 'Mjeno strony, kotrež sy zapodał, njebě płaćiwe. Měješe pak mjezyrěčny, pak mjezywikijowy prefiks abo wobsahowaše jedne abo wjace znamješkow, kotrež w titlach dowolene njejsu.',
'allpages-bad-ns'   => 'Mjenowy rum „$1" w {{grammar:lokatiw|{{SITENAME}}}} njeeksistuje.',

# Special:Categories
'categories'                    => 'Kategorije',
'categoriespagetext'            => '{{PLURAL:$1|Slědowaca kategorija wobsahuje|Slědowacej kategoriji wobsahujetej|Slědowace kategorije wobsahuja|Slědowace kategorije wobsahuja}} strony abo medije.
[[Special:UnusedCategories|Njewužiwane kategorije]] so tu njepokazuja.
Hlej tež [[Special:WantedCategories|požadane kategorije]].',
'categoriesfrom'                => 'Kategorije pokazać, započinajo z:',
'special-categories-sort-count' => 'Po ličbje sortěrować',
'special-categories-sort-abc'   => 'Alfabetisce sortěrować',

# Special:DeletedContributions
'deletedcontributions'             => 'wušmórnjene přinoški',
'deletedcontributions-title'       => 'wušmórnjene přinoški',
'sp-deletedcontributions-contribs' => 'přinoški',

# Special:LinkSearch
'linksearch'       => 'Eksterne wotkazy',
'linksearch-pat'   => 'Pytanski muster:',
'linksearch-ns'    => 'Mjenowy rum:',
'linksearch-ok'    => 'Pytać',
'linksearch-text'  => 'Zastupniske znamjenja kaž "*.wikipedia.org" smědźa so wužiwać.<br />Podpěrowane protokole: <tt>$1</tt>',
'linksearch-line'  => '$1 je z $2 wotkazany.',
'linksearch-error' => 'Zastupniske znamjenja dadźa so jenož na spočatku URL wužiwać.',

# Special:ListUsers
'listusersfrom'      => 'Započinajo z:',
'listusers-submit'   => 'Pokazać',
'listusers-noresult' => 'Njemóžno wužiwarjow namakać. Prošu wobkedźbuj, zo so mało- abo wulkopisanje na wotprašowanje wuskutkuje.',
'listusers-blocked'  => '(blokowany)',

# Special:ActiveUsers
'activeusers'            => 'Lisćina aktiwnych wužiwarjow',
'activeusers-intro'      => 'To je lisćina wužiwarjow, kotřiž běchu aktiwni za {{PLURAL:$1|posledni dźeń|poslednjej $1 dnjej|poslednje $1 dny|poslednich $1 dnjow}}:',
'activeusers-count'      => '$1 {{PLURAL:$1|změna|změnje|změny|změnow}} w {{PLURAL:$3|zańdźenej dnju|zańdźenymaj $3 dnjomaj|zańdźenych $3 dnjach|zańdźenych $3 dnjach}}',
'activeusers-from'       => 'Wužiwarjow zwobraznić, započinajo z:',
'activeusers-hidebots'   => 'Boćiki schować',
'activeusers-hidesysops' => 'Administratorow schować',
'activeusers-noresult'   => 'Žani wužiwarjo namakani.',

# Special:Log/newusers
'newuserlogpage'              => 'Protokol nowych wužiwarjow',
'newuserlogpagetext'          => 'To je protokol wutworjenja nowych wužiwarskich kontow.',
'newuserlog-byemail'          => 'Hesło z e-mejlku pósłane',
'newuserlog-create-entry'     => 'Nowy wužiwar',
'newuserlog-create2-entry'    => 'Wutwori nowe konto za wužiwarja $1',
'newuserlog-autocreate-entry' => 'Wužiwarske konto bu awtomatisce wutworjene.',

# Special:ListGroupRights
'listgrouprights'                      => 'Prawa wužiwarskeje skupiny',
'listgrouprights-summary'              => 'Slěduje lisćina wužiwarskich skupinow na tutej wikiju z jich wotpowědnymi přistupnymi prawami. Tu móžeš [[{{MediaWiki:Listgrouprights-helppage}}|dalše informacije]] wo jednotliwych prawach namakać.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Garantowane prawo</span>
* <span class="listgrouprights-revoked">Wotwołane prawo</span>',
'listgrouprights-group'                => 'Skupina',
'listgrouprights-rights'               => 'Prawa',
'listgrouprights-helppage'             => 'Help:Skupinske prawa',
'listgrouprights-members'              => '(lisćina čłonow)',
'listgrouprights-addgroup'             => 'Wužiwar hodźi so {{PLURAL:$2|tutej skupinje|tutymaj skupinomaj|tutym skupinam|tutym skupinam}} přidać: $1',
'listgrouprights-removegroup'          => 'Wužiwar hodźi so z {{PLURAL:$2|tuteje skupiny|tuteju skupinow|tutych skupinow|tutych skupinow}} wotstronić: $1',
'listgrouprights-addgroup-all'         => 'Hodźa so wšě skupiny přidać',
'listgrouprights-removegroup-all'      => 'Hodźa so wše skupiny wotstronić',
'listgrouprights-addgroup-self'        => 'Móže {{PLURAL:$2|skupinu|skupinje|skupiny|skupinow}} swójskemu kontu přidać: $1',
'listgrouprights-removegroup-self'     => 'Móže {{PLURAL:$2|skupinu|skupinje|skupiny|skupinow}} ze swójskeho konta wotstronić: $1',
'listgrouprights-addgroup-self-all'    => 'Móže wšě skupiny swójskemu kontu přidać',
'listgrouprights-removegroup-self-all' => 'Móže wšě skupiny ze swójskeho konta wotstronić',

# E-mail user
'mailnologin'          => 'Njejsy přizjewjeny.',
'mailnologintext'      => 'Dyrbiš [[Special:UserLogin|přizjewjeny]] być a płaćiwu e-mejlowu adresu w swojich [[Special:Preferences|nastajenjach]] měć, zo by druhim wužiwarjam mejlki pósłać móhł.',
'emailuser'            => 'Wužiwarjej mejlku pósłać',
'emailpage'            => 'Wužiwarjej mejlku pósłać',
'emailpagetext'        => 'Móžeš slědowacy formular wužiwać, zo by tutomu wužiwarjej e-mejlku pósłał.
E-mejlowa adresa, kotruž sy w [[Special:Preferences|swojich wužiwarskich nastajenjach]] zapodał, zjewi so jako adresa "Wot" e-mejlki, tak zo přijimowar móže ći direktnje wotmołwić.',
'usermailererror'      => 'E-mejlowy objekt je zmylk wróćił:',
'defemailsubject'      => 'Powěsć z {{grammar:genitiw|{{SITENAME}}}}',
'usermaildisabled'     => 'Wužiwarska e-mejl znjemóžnjena',
'usermaildisabledtext' => 'Njemóžeš na tutym wikiju druhim wužiwarjam e-mejl pósłać',
'noemailtitle'         => 'Žana e-mejlowa adresa podata',
'noemailtext'          => 'Tutón wužiwar njeje płaćiwu e-mejlowu adresu podał.',
'nowikiemailtitle'     => 'Žana e-mejl dowolena',
'nowikiemailtext'      => 'Tutón wužiwar nochce žane e-mejlki wot druhich wužiwarjow dóstać.',
'email-legend'         => 'E-mejlku druhemu wužiwarjej {{GRAMMAR:genitiw|{{SITENAME}}}} pósłać',
'emailfrom'            => 'Wot:',
'emailto'              => 'Komu:',
'emailsubject'         => 'Tema:',
'emailmessage'         => 'Powěsć:',
'emailsend'            => 'Wotesłać',
'emailccme'            => 'E-mejluj mi kopiju mojeje powěsće.',
'emailccsubject'       => 'Kopija twojeje powěsće wužiwarjej $1: $2',
'emailsent'            => 'Mejlka wotesłana',
'emailsenttext'        => 'Twoja mejlka bu wotesłana.',
'emailuserfooter'      => 'Tuta e-mejlka bu z pomocu funkcije "Wužiwarjej mejlku pósłać" na {{SITENAME}} wot $1 do $2 pósłana.',

# User Messenger
'usermessage-summary' => 'Systemowu  zdźělenku zawostajić.',
'usermessage-editor'  => 'Systemowy powěstnik',

# Watchlist
'watchlist'            => 'wobkedźbowanki',
'mywatchlist'          => 'wobkedźbowanki',
'watchlistfor2'        => 'Za wužiwarja $1 $2',
'nowatchlist'          => 'Nimaš žane strony w swojich wobkedźbowankach.',
'watchlistanontext'    => 'Dyrbiš so $1, zo by swoje wobkedźbowanki wobhladać abo wobdźěłać móhł.',
'watchnologin'         => 'Njejsy přizjewjeny.',
'watchnologintext'     => 'Dyrbiš [[Special:UserLogin|přizjewjeny]] być, zo by swoje wobkedźbowanki změnić móhł.',
'addedwatch'           => 'Strona bu wobkedźbowankam přidata.',
'addedwatchtext'       => "Strona [[:$1]] bu k twojim [[Special:Watchlist|wobkedźbowankam]] přidata.
Přichodne změny tuteje strony a přisłušneje diskusijneje strony budu so tam nalistować a strona so '''w tučnym pismje''' w [[Special:RecentChanges|lisćinje aktualnych změnach]] zjewi, zo by so wosnadniło ju wubrać.

Jeli chceš stronu pozdźišo ze swojich wobkedźbowankow wotstronić, klikń na rajtark „njewobkedźbować” horjeka na tutej stronje.",
'removedwatch'         => 'Strona bu z wobkedźbowankow wotstronjena',
'removedwatchtext'     => 'Strona "[[:$1]]" bu z [[Special:Watchlist|twojich wobkedźbowankow]] wotstronjena.',
'watch'                => 'wobkedźbować',
'watchthispage'        => 'stronu wobkedźbować',
'unwatch'              => 'njewobkedźbować',
'unwatchthispage'      => 'wobkedźbowanje skónčić',
'notanarticle'         => 'njeje nastawk',
'notvisiblerev'        => 'Wersija bu wušmórnjena',
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
'watchlist-options'    => 'Opcije wobkedźbowankow',

# Displayed when you click the "watch" button and it is in the process of watching
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
'enotif_body'                  => 'Luby $WATCHINGUSERNAME,


Strona we {{GRAMMAR:lokatiw|{{SITENAME}}}} z mjenom $PAGETITLE bu dnja $PAGEEDITDATE wot $PAGEEDITOR $CHANGEDORCREATED, hlej $PAGETITLE_URL za aktualnu wersiju.

$NEWPAGE

Zjeće wobdźěłaćerja: $PAGESUMMARY $PAGEMINOREDIT

Skontaktuj wobdźěłarja:
e-mejl: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Njebudu žane druhe zdźělenki w padźe dalšich změnow, chibazo wopytaš tutu stronu.
Móžeš tež zdźělenske marki za wšě swoje wobkedźbowane strony we swojich wobkedźbowankach wróćo stajić.

               Twój přećelny zdźělenski system {{GRAMMAR:genitiw|{{SITENAME}}}}

--
Zo by nastajenja twojich wobkedźbowankow změnił, wopytaj
{{fullurl:{{#special:Watchlist}}/edit}}

Zo by stronu ze swojich wobkedźbowankow zhašał, wopytaj
$UNWATCHURL


Wotmołwy a dalša pomoc:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Stronu zhašeć',
'confirm'                => 'Wobkrućić',
'excontent'              => "wobsah běše: '$1'",
'excontentauthor'        => "wobsah bě: '$1' (a jenički wobdźěłowar bě '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'          => "wobsah do wuprózdnjenja běše: '$1'",
'exblank'                => 'strona běše prózdna',
'delete-confirm'         => '„$1“ wušmórnyć',
'delete-legend'          => 'Wušmórnyć',
'historywarning'         => "'''KEDŹBU:''' Strona, kotruž chceš wušmórnyć, ma stawizny z přibližnje $1 {{PLURAL:$1|wersiju|wersijomaj|wersijemi|wersijemi}}:",
'confirmdeletetext'      => 'Sy so rozsudźił stronu ze jeje stawiznami wušmórnić.
Prošu potwjerdź, zo maš wotpohlad to činić, zo rozumiš sćěwki a zo to wotpowědujo [[{{MediaWiki:Policy-url}}|zasadam tutoho wikija]] činiš.',
'actioncomplete'         => 'Dokónčene',
'actionfailed'           => 'Akcija je so njeporadźiła',
'deletedtext'            => 'Strona „<nowiki>$1</nowiki>” bu wušmórnjena. Hlej $2 za lisćinu aktualnych wušmórnjenjow.',
'deletedarticle'         => 'je stronu [[$1]] wušmórnył.',
'suppressedarticle'      => '"[[$1]]" potłóčeny',
'dellogpage'             => 'Protokol wušmórnjenjow',
'dellogpagetext'         => 'Deleka je lisćina najaktualnišich wušmórnjenjow.',
'deletionlog'            => 'Protokol wušmórnjenjow',
'reverted'               => 'Na staršu wersiju cofnjene',
'deletecomment'          => 'Přičina:',
'deleteotherreason'      => 'Druha/přidatna přičina:',
'deletereasonotherlist'  => 'Druha přičina',
'deletereason-dropdown'  => '*Zwučene přičiny za wušmórnjenje
** Požadanje awtora
** Zranjenje copyrighta
** Wandalizm',
'delete-edit-reasonlist' => 'Přičiny za wušmórnjenje wobdźěłać',
'delete-toobig'          => 'Tuta strona ma z wjace hač $1 {{PLURAL:$1|wersiju|wersijomaj|wersijemi|wersijemi}} wulke wobdźěłanske stawizny. Wušmórnjenje tajkich stronow bu wobmjezowane, zo by připadne přetorhnjenje {{SITENAME}} wobešło.',
'delete-warning-toobig'  => 'Tuta strona ma z wjace hač $1 {{PLURAL:$1|wersiju|wersijomaj|wersijemi|wersijemi}} wulke wobdźěłanske stawizny. Wušmórnjenje móže operacije datoweje banki {{SITENAME}} přetorhnyć; pokročuj z kedźbliwosću.',

# Rollback
'rollback'          => 'Změny cofnyć',
'rollback_short'    => 'Cofnyć',
'rollbacklink'      => 'Cofnyć',
'rollbackfailed'    => 'Cofnjenje njeporadźiło',
'cantrollback'      => 'Njemóžno změnu cofnyć; strona nima druhich awtorow.',
'alreadyrolled'     => 'Njemóžno poslednu změnu [[:$1]] přez wužiwarja [[User:$2|$2]] ([[User talk:$2|Diskusija]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]) cofnyć; něchtó druhi je stronu wobdźěłał abo změnu hižo cofnył.

Poslednja změna bě wot wužiwarja [[User:$3|$3]] ([[User talk:$3|Diskusija]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "Zjeće wobdźěłanja bě: \"''\$1''\".",
'revertpage'        => 'Změny [[Special:Contributions/$2|$2]] ([[User talk:$2|Diskusija]]) cofnjene a nawróćene k poslednjej wersiji wužiwarja [[User:$1|$1]]',
'revertpage-nouser' => 'Staji změny wot (wužiwarske mjeno wotstronjene) na předchadnu wersiju wot [[User:$1|$1]] wróćo',
'rollback-success'  => 'Změny wužiwarja $1 cofnjene; wróćo na wersiju wužiwarja $2.',

# Edit tokens
'sessionfailure-title' => 'Posedźenski zmylk',
'sessionfailure'       => 'Zda so, zo je problem z twojim přizjewjenjom; tuta akcija bu wěstosće dla přećiwo zadobywanju do posedźenja znjemóžniła. Prošu klikń na "Wróćo" a začitaj stronu, z kotrejež přińdźeš, znowa; potom spytaj hišće raz.',

# Protect
'protectlogpage'              => 'Protokol škita',
'protectlogtext'              => 'To je protokol škitanych stronow a zběhnjenja škita.
Hlej [[Special:ProtectedPages|tutu specialnu stronu]] za lisćinu škitanych stron.',
'protectedarticle'            => 'je stronu [[$1]] škitał',
'modifiedarticleprotection'   => 'je škit strony [[$1]] změnił',
'unprotectedarticle'          => 'je škit strony [[$1]] zběhnył',
'movedarticleprotection'      => 'škitowe nastajenja z "[[$2]]" do "[[$1]]" přesunjene',
'protect-title'               => 'Stronu „$1” škitać',
'prot_1movedto2'              => 'je [[$1]] pod hesło [[$2]] přesunył',
'protect-legend'              => 'Škit wobkrućić',
'protectcomment'              => 'Přičina:',
'protectexpiry'               => 'Čas škita:',
'protect_expiry_invalid'      => 'Njepłaćiwy čas spadnjenja.',
'protect_expiry_old'          => 'Čas škita leži w zańdźenosći.',
'protect-unchain-permissions' => 'Dalše škitne opcije dopušćić',
'protect-text'                => 'Tu móžeš status škita strony <b><nowiki>$1</nowiki></b> wobhladać a změnić.',
'protect-locked-blocked'      => "Njemóžeš škit strony změnič, dokelž twoje konto je zablokowane. Tu widźiš aktualne škitne nastajenja za stronu'''„$1“:'''",
'protect-locked-dblock'       => "Datowa banka je zawrjena, tohodla njemóžeš škit strony změnić. Tu widźiš aktualne škitne nastajenja za stronu'''„$1“:'''",
'protect-locked-access'       => "Nimaš trěbne prawa, zo by škit strony změnił. Tu widźiš aktualne škitne nastajenja za stronu'''„$1“:'''",
'protect-cascadeon'           => 'Tuta strona je tuchwilu škitana, dokelž je w {{PLURAL:$1|slědowacej stronje|slědowacych stronach}} zapřijata, {{PLURAL:$1|kotraž je|kotrež su}} přez kaskadowu opciju {{PLURAL:$1|škitana|škitane}}. Móžeš škitowy status strony změnić, to wšak wliw na kaskadowy škit nima.',
'protect-default'             => 'Wšěch wužiwarjow dowolić',
'protect-fallback'            => 'Prawo "$1" trěbne.',
'protect-level-autoconfirmed' => 'Nowych a njeregistrowanych wužiwarjow blokować',
'protect-level-sysop'         => 'jenož administratorojo',
'protect-summary-cascade'     => 'kaskadowacy',
'protect-expiring'            => 'spadnje $1 (UTC)',
'protect-expiry-indefinite'   => 'njewobmjezowany',
'protect-cascade'             => 'Kaskadowacy škit – wšě w tutej stronje zapřijate strony so škituja.',
'protect-cantedit'            => 'Njemóžeš škitowe runiny tuteje strony změnić, dokelž nimaš dowolnosć, zo by ju wobdźěłał.',
'protect-othertime'           => 'Druhi čas:',
'protect-othertime-op'        => 'druhi čas',
'protect-existing-expiry'     => 'Eksistowacy čas spadnjenja: $2, $3 hodź.',
'protect-otherreason'         => 'Druha/přidatna přičina:',
'protect-otherreason-op'      => 'Druha přičina',
'protect-dropdown'            => '*Powšitkowne škitowe přičiny
** Ekscesiwny wandalizm
** Ekscesiwne spamowanje
** Wobdźěłanska wójna
** Strona z jara wjele změnami',
'protect-edit-reasonlist'     => 'Škitowe přičiny wobdźěłać',
'protect-expiry-options'      => '1 hodźinu:1 hour,1 dźeń:1 day,1 tydźeń:1 week,2 njedźeli:2 weeks,1 měsać:1 month,3 měsacy:3 months,6 měsacow:6 months,1 lěto:1 year,na přeco:infinite',
'restriction-type'            => 'Škitowy status',
'restriction-level'           => 'Runina škita:',
'minimum-size'                => 'Minimalna wulkosć:',
'maximum-size'                => 'Maksimalna wulkosć:',
'pagesize'                    => '(bajtow)',

# Restrictions (nouns)
'restriction-edit'   => 'wobdźěłać',
'restriction-move'   => 'přesunyć',
'restriction-create' => 'Wutworić',
'restriction-upload' => 'Nahrać',

# Restriction levels
'restriction-level-sysop'         => 'dospołnje škitany',
'restriction-level-autoconfirmed' => 'połškitany (móže so jenož přez přizjewjenych wužiwarjow wobdźěłać, kiž nowačcy njejsu)',
'restriction-level-all'           => 'wšě',

# Undelete
'undelete'                     => 'Wušmórnjenu stronu wobnowić',
'undeletepage'                 => 'Wušmórnjene strony wobnowić',
'undeletepagetitle'            => "'''Slědowace wudaće pokazuje wušmórnjene wersije wot [[:$1]]'''.",
'viewdeletedpage'              => 'Wušmórnjene strony wobhladać',
'undeletepagetext'             => '{{PLURAL:$1|Slědowaca strona bu wušmórnjena, ale je|Slědowacej $1 stronje buštej wušmórnjenej, ale stej|Slědowace $1 strony buchu wušmórnjene, ale su|Slědowacych $1 bu wušmórnjene, ale je}} hišće w archiwje a {{PLURAL:$1|da so|datej so|dadźa so|da so}} wobnowić.
Archiw da so periodisce wuprózdnić.',
'undelete-fieldset-title'      => 'Wersije wobnowić',
'undeleteextrahelp'            => "Zo by wšě stawizny strony wobnowił, wostaj prošu wšě kontrolowe kašćiki njewubrane a klikń na '''''Wobnowić'''''. Zo by selektiwne wobnowjenje přewjedł, wubjer kašćiki, kotrež wersijam wotpowěduja, kotrež maja so wobnowić a klikń na '''''Wobnowić'''''.
Kliknjenje na '''''Wróćo stajić''''' komentarne polo a wšě kontrolowe kašćiki wuprózdni.",
'undeleterevisions'            => '$1 {{PLURAL:$1|wersija|wersiji|wersije|wersijow}} {{PLURAL:$1|archiwowana|archiwowanej|archiwowane|archiwowane}}',
'undeletehistory'              => 'Jeli tutu stronu wobnowiš, so wšě (tež prjedy wušmórnjene) wersije zaso do stawiznow wobnowja. Jeli bu po wušmórnjenju nowa strona ze samsnym mjenom wutworjena, budu so wobnowjene wersije w prjedawšich stawiznach jewić.',
'undeleterevdel'               => 'Wobnowjenje so njepřewjedźe, jeli je najwyša strona docpěta abo datajowa wersija budźe so zdźěla wušmórnje.
W tutym padźe dyrbiš najnowšu wušmórnjenu wersiju znjemóžnić abo pokazać.',
'undeletehistorynoadmin'       => 'Strona bu wušmórnjena. Přičina za wušmórnjenje so deleka w zjeću pokazuje, zhromadnje z podrobnosćemi wužiwarjow, kotřiž běchu tutu stronu do zničenja wobdźěłali. Tuchwilny wobsah strony je jenož administratoram přistupny.',
'undelete-revision'            => 'Wušmórnjena wersija strony $1 (wot $4, $5 hodź.) wot $3:',
'undeleterevision-missing'     => 'Njepłaćiwa abo pobrachowaca wersija. Pak je wotkaz wopačny, pak bu wotpowědna wersija z archiwa wobnowjena abo wotstronjena.',
'undelete-nodiff'              => 'Předchadna wersija njeeksistuje.',
'undeletebtn'                  => 'Wobnowić',
'undeletelink'                 => 'wobhladać sej/wobnowić',
'undeleteviewlink'             => 'wobhladać sej',
'undeletereset'                => 'Cofnyć',
'undeleteinvert'               => 'Wuběr wobroćić',
'undeletecomment'              => 'Přičina:',
'undeletedarticle'             => 'je „[[$1]]” wobnowił.',
'undeletedrevisions'           => '$1 {{PLURAL:$1|wersija|wersiji|wersije|wersijow}} {{PLURAL:$1|wobnowjena|wobnowjenej|wobnowjene|wobnowjene}}',
'undeletedrevisions-files'     => '$1 {{PLURAL:$1|wersija|wersiji|wersije|wersijow}} a $2 {{PLURAL:$2|dataja|dataji|dataje|datajow}} {{PLURAL:$2|wobnowjena|wobnowjenej|wobnowjene|wobnowjene}}',
'undeletedfiles'               => '$1 {{PLURAL:$1|dataja|dataji|dataje|datajow}} {{PLURAL:$1|wobnowjena|wobnowjenej|wobnowjene|wobnowjene}}.',
'cannotundelete'               => 'Wobnowjenje zwrěšćiło; něchtó druhi je stronu prjedy wobnowił.',
'undeletedpage'                => "'''Strona $1 bu z wuspěchom wobnowjena.'''

Hlej [[Special:Log/delete|protokol]] za lisćinu aktualnych wušmórnjenjow a wobnowjenjow.",
'undelete-header'              => 'Hlej [[Special:Log/delete|protokol wušmórnjenjow]] za njedawno wušmórnjene strony.',
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
'undelete-show-file-confirm'   => 'Chceš sej woprawdźe zničenu wersiju dataje "<nowiki>$1</nowiki>" wot $2 $3 wobhladać?',
'undelete-show-file-submit'    => 'Haj',

# Namespace form on various pages
'namespace'      => 'Mjenowy rum:',
'invert'         => 'Wuběr wobroćić',
'blanknamespace' => '(Nastawki)',

# Contributions
'contributions'       => 'Přinoški wužiwarja',
'contributions-title' => 'Wužiwarske přinoški wot „$1“',
'mycontris'           => 'moje přinoški',
'contribsub2'         => 'za wužiwarja $1 ($2)',
'nocontribs'          => 'Žane změny, kotrež podatym kriterijam wotpowěduja.',
'uctop'               => '(aktualnje)',
'month'               => 'wot měsaca (a do toho):',
'year'                => 'wot lěta (a do toho):',

'sp-contributions-newbies'             => 'jenož přinoški nowačkow pokazać',
'sp-contributions-newbies-sub'         => 'Za nowačkow',
'sp-contributions-newbies-title'       => 'Wužiwarske přinoški za nowe konta',
'sp-contributions-blocklog'            => 'protokol zablokowanjow',
'sp-contributions-deleted'             => 'wušmórnjene wužiwarske přinoški',
'sp-contributions-logs'                => 'protokole',
'sp-contributions-talk'                => 'diskusija',
'sp-contributions-userrights'          => 'Zrjadowanje wužiwarskich prawow',
'sp-contributions-blocked-notice'      => 'Tutón wužiwar je tuchwilu zablokowany. Najnowši protokolowy zapisk so deleka jako referenca podawa:',
'sp-contributions-blocked-notice-anon' => 'Tuta IP-adresa je tuchwilu zablokowana.
Najnowši zapisk w protokolu blokowanjow so deleka jako referenca podawa:',
'sp-contributions-search'              => 'Přinoški pytać',
'sp-contributions-username'            => 'IP-adresa abo wužiwarske mjeno:',
'sp-contributions-toponly'             => 'Jenož wyše wersije pokazać',
'sp-contributions-submit'              => 'OK',

# What links here
'whatlinkshere'            => 'Što wotkazuje sem',
'whatlinkshere-title'      => 'Strony, kotrež na „$1“ wotkazuja',
'whatlinkshere-page'       => 'Strona:',
'linkshere'                => "Sćěhowace strony na stronu '''[[:$1]]''' wotkazuja:",
'nolinkshere'              => "Žane strony na '''[[:$1]]''' njewotkazuja.",
'nolinkshere-ns'           => "Žane strony njewotkazuja na '''[[:$1]]''' we wubranym mjenowym rumje.",
'isredirect'               => 'daleposrědkowanje',
'istemplate'               => 'zapřijeće předłohi',
'isimage'                  => 'wobrazowy wotkaz',
'whatlinkshere-prev'       => '{{PLURAL:$1|předchadny|předchadnej|předchadne|předchadne $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|přichodny|přichodnej|přichodne|přichodne $1}}',
'whatlinkshere-links'      => '← wotkazy',
'whatlinkshere-hideredirs' => 'Daleposrědkowanja $1',
'whatlinkshere-hidetrans'  => 'Zapřijeća $1',
'whatlinkshere-hidelinks'  => 'Wotkazy $1',
'whatlinkshere-hideimages' => 'wobrazowe wotkazy $1',
'whatlinkshere-filters'    => 'Filtry',

# Block/unblock
'blockip'                         => 'Wužiwarja zablokować',
'blockip-title'                   => 'Wužiwarja blokować',
'blockip-legend'                  => 'Wužiwarja blokować',
'blockiptext'                     => 'Wužij slědowacy formular deleka, zo by pisanski přistup za podatu IP-adresu abo wužiwarske mjeno blokował. To měło so jenož stać, zo by wandalizmej zadźěwało a woptpowědujo [[{{MediaWiki:Policy-url}}|zasadam]]. Zapodaj deleka přičinu (na př. citujo wosebite strony, kotrež běchu z woporom wandalizma).',
'ipaddress'                       => 'IP-adresa',
'ipadressorusername'              => 'IP-adresa abo wužiwarske mjeno',
'ipbexpiry'                       => 'Spadnjenje',
'ipbreason'                       => 'Přičina:',
'ipbreasonotherlist'              => 'Druha přičina',
'ipbreason-dropdown'              => '*powšitkowne přičiny
** wandalizm
** wutworjenje njezmyslnych stronow
** linkspam
** wobobinske nadběhi
*specifiske přičiny
** njepřihódne wužiwarske mjeno
** znowapřizjewjenje na přeco zablokowaneho wužiwarja
** proksy, wandalizma jednotliwych wužiwarjow dla dołhodobnje zablokowany',
'ipbanononly'                     => 'Jenož anonymnych wužiwarjow zablokować',
'ipbcreateaccount'                => 'Wutworjenju nowych kontow zadźěwać',
'ipbemailban'                     => 'Wotpósłanje mejlkow znjemóžnić',
'ipbenableautoblock'              => 'IP-adresy blokować kiž buchu přez tutoho wužiwarja hižo wužiwane kaž tež naslědne adresy, z kotrychž so wobdźěłanje pospytuje',
'ipbsubmit'                       => 'Wužiwarja zablokować',
'ipbother'                        => 'Druha doba',
'ipboptions'                      => '2 hodźinje:2 hours,1 dźeń:1 day,3 dny:3 days,1 tydźeń:1 week,2 njedźeli:2 weeks,1 měsać:1 month,3 měsacy:3 months,6 měsacow:6 months,1 lěto:1 year,na přeco:infinite',
'ipbotheroption'                  => 'druha doba (jendźelsce)',
'ipbotherreason'                  => 'Druha/přidatna přičina:',
'ipbhidename'                     => 'Wužiwarske mjeno stawiznach a lisćinach schować',
'ipbwatchuser'                    => 'Wužiwarsku a diskusijnu stronu tutoho wužiwarja wobkedźbować',
'ipballowusertalk'                => 'Tutomu wužiwarjej dowolić swójsku diskusijnu stronu wobdźěłać, mjeztym zo je blokowany',
'ipb-change-block'                => 'Wužiwarja z tutymi nastajenjemi znowa blokować',
'badipaddress'                    => 'Njepłaćiwa IP-adresa',
'blockipsuccesssub'               => 'Zablokowanje wuspěšne',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] bu zablokowany.
<br />Hlej [[Special:IPBlockList|lisćinu blokowanjow IP]], zo by zablokowanjow pruwował.',
'ipb-edit-dropdown'               => 'přičiny zablokowanjow wobdźěłać',
'ipb-unblock-addr'                => 'zablokowanje wužiwarja „$1“ zběhnyć',
'ipb-unblock'                     => 'zablokowanje wužiwarja abo IP-adresy zběhnyć',
'ipb-blocklist-addr'              => 'Eksistowace zablokowanja za "$1"',
'ipb-blocklist'                   => 'tuchwilne blokowanja zwobraznić',
'ipb-blocklist-contribs'          => 'Přinoški za $1',
'unblockip'                       => 'Zablokowanje zběhnyć',
'unblockiptext'                   => 'Wužij formular deleka, zo by blokowanje IP-adresy abo wužiwarskeho mjena zběhnył.',
'ipusubmit'                       => 'Tute blokěrowanje skónčić',
'unblocked'                       => 'Blokowanje wužiwarja [[User:$1|$1]] bu zběhnjene',
'unblocked-id'                    => 'Blokowanje ID $1 bu zběhnjene.',
'ipblocklist'                     => 'Zablokowane IP-adresy a wužiwarske mjena',
'ipblocklist-legend'              => 'Pytanje za zablokowanym wužiwarjom',
'ipblocklist-username'            => 'Wužiwarske mjeno abo IP-adresa:',
'ipblocklist-sh-userblocks'       => '$1 kontowe zablokowanja',
'ipblocklist-sh-tempblocks'       => '$1 nachwilne zablokowanja',
'ipblocklist-sh-addressblocks'    => '$1 IP-zablokowanja',
'ipblocklist-submit'              => 'Pytać',
'ipblocklist-localblock'          => 'Lokalne blokowanje',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|Druhe blokowanje|Druhej blokowani|Druhe blokowanja|Druhe blokowanja}}',
'blocklistline'                   => '$1, $2 je wužiwarja $3 zablokował ($4)',
'infiniteblock'                   => 'na přeco',
'expiringblock'                   => 'spadnje $1 $2',
'anononlyblock'                   => 'jenož anonymnych blokować',
'noautoblockblock'                => 'awtoblokowanje znjemóžnjene',
'createaccountblock'              => 'wutworjenje wužiwarskich kontow znjemóžnjene',
'emailblock'                      => 'Wotpósłanje mejlkow bu znjemóžnjene',
'blocklist-nousertalk'            => 'njemóže swójsku diskusijnu stronu wobdźěłać',
'ipblocklist-empty'               => 'Liścina blokowanjow je prózdna.',
'ipblocklist-no-results'          => 'Požadana IP-adresa/požadane wužiwarske mjeno njeje zablokowane.',
'blocklink'                       => 'zablokować',
'unblocklink'                     => 'blokowanje zběhnyć',
'change-blocklink'                => 'Blokowanje změnić',
'contribslink'                    => 'přinoški',
'autoblocker'                     => 'Awtomatiske blokowanje, dokelž twoja IP-adresa bu njedawno wot wužiwarja „[[User:$1|$1]]” wužita. Přičina, podata za blokowanje $1, je: "$2"',
'blocklogpage'                    => 'Protokol zablokowanjow',
'blocklog-showlog'                => 'Tutón wužiwar bu prjedy zablokowany. Protokol blokowanjow so deleka jako referenca podawa:',
'blocklog-showsuppresslog'        => 'Tutón wužiwar bu prjedy zablokowany a schowany. Protokol potłóčenjow  so deleka jako referenca podawa:',
'blocklogentry'                   => 'je wužiwarja [[$1]] zablokował z časom spadnjenja $2 $3',
'reblock-logentry'                => 'změni blokowanske nastajenja za [[$1]] z časom spadnjenja $2 $3',
'blocklogtext'                    => 'To je protokol blokowanja a wotblokowanja wužiwarjow. Awtomatisce blokowane IP-adresy so njenalistuja. Hlej [[Special:IPBlockList|lisćinu zablokowanych IP-adresow]] za lisćinu tuchwilnych wuhnaćow a zablokowanjow.',
'unblocklogentry'                 => 'zablokowanje wužiwarja $1 bu zběhnjene',
'block-log-flags-anononly'        => 'jenož anonymnych',
'block-log-flags-nocreate'        => 'wutworjenje wužiwarskich kontow znjemóžnjene',
'block-log-flags-noautoblock'     => 'awtomatiske zablokowanje znjemóžnjene',
'block-log-flags-noemail'         => 'wotpósłanje mejlkow bu znjemóžnjene',
'block-log-flags-nousertalk'      => 'njeje móžno swójsku diskusijnu stronu wobdźěłać',
'block-log-flags-angry-autoblock' => 'polěpšene awtomatiske blokowanje zmóžnjene',
'block-log-flags-hiddenname'      => 'wužiwarske mjeno schowane',
'range_block_disabled'            => 'Kmanosć administratorow, cyłe wobłuki IP-adresow blokować, je znjemóžnjena.',
'ipb_expiry_invalid'              => 'Čas spadnjenja je njepłaćiwy.',
'ipb_expiry_temp'                 => 'Blokowanja schowanych wužiwarskich mjenow maja permanentne być.',
'ipb_hide_invalid'                => 'Njeje móžno tute konto potłóčić; ma snano přewjele změnow.',
'ipb_already_blocked'             => 'Wužiwar „$1” je hižo zablokowany.',
'ipb-needreblock'                 => '== Hižo zablokowany ==
$1 je hižo zablokowany. Chceš nastajenja změnić?',
'ipb-otherblocks-header'          => '{{PLURAL:$1|Druhe blokowanje|Druhej blokowani|Druhe blokowanja|Druhe blokowanja}}',
'ipb_cant_unblock'                => 'Zmylk: Njemóžno ID zablokowanja $1 namakać. Zablokowanje je so najskerje mjeztym zběhnyło.',
'ipb_blocked_as_range'            => 'Zmylk: IP $1 njeje direktnje zablokowana a njeda so wublokować. Blokuje so wšak jako dźěl wobwoda $2, kotryž da so wublokować.',
'ip_range_invalid'                => 'Njepłaciwy wobłuk IP-adresow.',
'ip_range_toolarge'               => 'Wobłukowe bloki, kotrež su wjetše hač /$1, njejsu dowolene.',
'blockme'                         => 'Blokować',
'proxyblocker'                    => 'Awtomatiske blokowanje wotewrjenych proksy-serwerow',
'proxyblocker-disabled'           => 'Tuta funkcija je deaktiwizowana.',
'proxyblockreason'                => 'Twoja IP-adresa bu zablokowana, dokelž je wotewrjeny proksy. Prošu skontaktuj swojeho prowidera abo syćoweho administratora a informuj jeho wo tutym chutnym wěstotnym problemje.',
'proxyblocksuccess'               => 'Dokónčene.',
'sorbs'                           => 'SORBS DNSbl',
'sorbsreason'                     => 'Twoja IP-adresa je jako wotewrjeny proksy na DNSBL {{GRAMMAR:genitiw|{{SITENAME}}}} zapisana.',
'sorbs_create_account_reason'     => 'Twoja IP-adresa je jako wotewrjeny proksy na DNSBL {{GRAMMAR:genitiw|{{SITENAME}}}} zapisana. Njemóžeš konto wutworić.',
'cant-block-while-blocked'        => 'Njemóžeš druhich wužiwarjow blokować, mjeztym zo ty sy zablokowany.',
'cant-see-hidden-user'            => 'Wužiwar, kotrehož pospytuješ blokować, bu hižo zablokowany a schowany. Dokelž nimaš prawo wužiwarja schować, njemóžeš blokowanje wužiwarja widźeć abo wobdźěłać.',
'ipbblocked'                      => 'Njemóžeš druhich wužiwarjow blokować abo wotblokować, dokelž ty sam sy zablokowany',
'ipbnounblockself'                => 'Njesměš so samoho wotblokować',

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
<br />Njezabudź [[Special:UnlockDB|wotzamknyć]], po tym zo wothladowanje je sčinjene.',
'unlockdbsuccesstext' => 'Datowa banka bu wotamknjena.',
'lockfilenotwritable' => 'Do dataje zamknjenja datoweje banki njeda so zapisować. Za zamknjenje abo wotamknjenje datoweje banki dyrbi webowy serwer pisanske prawo měć.',
'databasenotlocked'   => 'Datajowa banka zamknjena njeje.',

# Move page
'move-page'                    => '$1 přesunyć',
'move-page-legend'             => 'Stronu přesunyć',
'movepagetext'                 => 'Wužiwanje formulara deleka budźe stronu přemjenować, suwajo jeje cyłe stawizny pod nowe mjeno. Stary titl budźe daleposrědkowanje na nowy titl. Wotkazy na stary titl so njezměnja. Pruwuj za dwójnymi abo skóncowanymi daleposrědkowanjemi. Dyrbiš zaručić, zo wotkazy na stronu pokazuja, na kotruž dyrbja dowjesć.

Wobkedźbuj, zo strona so <b>nje</b> přesunje, jeli strona z nowym titlom hizo eksistuje, chibazo wona je prózdna abo dalesposrědkowanje a nima zašłe stawizny. To woznamjenja, zo móžeš stronu tam wróćo přemjenować, hdźež bu runje přemjenowana, jeli zmylk činiš a njemóžeš wobstejacu stronu přepisować.

<b>KEDŹBU!</b> Móže to drastiska a njewočakowana změna za woblubowanu stronu być; prošu budź sej wěsty, zo sćěwki rozumiš, prjedy hač pokročuješ.',
'movepagetext-noredirectfixer' => "Wužiwajo slědowacy formular, móžeš stronu přemjenować a wšě jich daty do stawiznow noweho titula přesunyć.
Stary titul budźe dalesposrědkowanska strona k nowemu titulej.
Skontroluj za [[Special:DoubleRedirects|dwójnymi]] abo [[Special:BrokenRedirects|wobškodźenymi dalesposrědkowanjemi]].
Sy za to zamołwity, zo wotkazy na tón cil pokazuja, na kotryž maja pokazować.

Dźiwaj na to, zo strona so '''nje'''přesunje, jeli je hižo strona z nowym titulom, chibazo wona je prózdna abo dalesposrědkowanje a nima stawizny změnow.
To woznamjenja, zo móžeš stronu do stareho mjena wróćopřemjenować, jeli činiš zmylk a njemóžeš eksistowacu stronu přepisać.

'''Warnowanje!'''
To móže drastiska a njewočakowana změna za woblubowanu stronu być:
prošu wuwědomće sej konsekwency, prjedy hač pokročuješ.",
'movepagetalktext'             => 'Přisłušna diskusijna strona přesunje so awtomatisce hromadźe z njej, <b>chibazo:</b>
*Njeprózdna diskusijna strona pod nowym mjenom hižo eksistuje abo
*wotstronješ hóčku z kašćika deleka.

W tutych padach dyrbiš stronu manuelnje přesunyć abo zaměšeć, jeli sej to přeješ.',
'movearticle'                  => 'Stronu přesunyć',
'moveuserpage-warning'         => "'''Warnowanje:''' Sy při tym wužiwarsku stronu přesunyć. Prošu dźiwaj na to, zo so jenož strona posunje a wužiwar so ''nje''budźe přemjenować.",
'movenologin'                  => 'Njejsy přizjewjeny.',
'movenologintext'              => 'Dyrbiš zregistrowany wužiwar a [[Special:UserLogin|přizjewjeny]] być, zo by stronu přesunył.',
'movenotallowed'               => 'Nimaš prawo, zo by strony přesunył.',
'movenotallowedfile'           => 'Nimaš prawo dataje přesunyć.',
'cant-move-user-page'          => 'Nimaš prawo wužiwarske strony přesunyć (wothladajo wot podstronow)',
'cant-move-to-user-page'       => 'Nimaš prawo stronu do wužiwarskeje strony přesunyć (z wuwzaćom do wužiwarskeje podstrony).',
'newtitle'                     => 'pod nowe hesło',
'move-watch'                   => 'Stronu wobkedźbować',
'movepagebtn'                  => 'Stronu přesunyć',
'pagemovedsub'                 => 'Přesunjenje wuspěšne',
'movepage-moved'               => '\'\'\'Strona "$1" bu do "$2" přesunjena.\'\'\'',
'movepage-moved-redirect'      => 'Daleposrědkowanje je so wutworiło.',
'movepage-moved-noredirect'    => 'Wutworjenje daleposrědkowanja bu potłóčene.',
'articleexists'                => 'Strona z tutym mjenom hižo eksistuje abo mjeno, kotrež sy wuzwolił, płaćiwe njeje. Prošu wuzwol druhe mjeno.',
'cantmove-titleprotected'      => 'Njemóžeš stronu do tutoho městna přesunyć, dokelž nowy titul bu přećiwo wutworjenju škitany',
'talkexists'                   => 'Strona sama bu z wuspěchom přesunjena, diskusijna strona pak njeda so přesunyć, dokelž pod nowym titulom hižo eksistuje. Prošu změš jeju manuelnje.',
'movedto'                      => 'přesunjena do hesła',
'movetalk'                     => 'Přisłušnu diskusijnu stronu tohorunja přesunyć',
'move-subpages'                => 'Wšě podstrony (hač do $1) přesunyć',
'move-talk-subpages'           => 'Wšě podstrony diskusijneje strony (hač do $1) přesunyć',
'movepage-page-exists'         => 'Strona $1 hižo eksistuje a njeda so awtomatisce přepisać.',
'movepage-page-moved'          => 'Strona $1 bu do $2 přesunjena.',
'movepage-page-unmoved'        => 'Strona $1 njeda so do $2 přesunyć.',
'movepage-max-pages'           => 'Maksimalna ličba $1 {{PLURAL:$1|strony|stronow|stronow|stronow}} bu přesunjena, dalše strony so awtomatisce njepřesunu.',
'1movedto2'                    => 'je [[$1]] pod hesło [[$2]] přesunył',
'1movedto2_redir'              => 'je [[$1]] pod hesło [[$2]] přesunył a při tym daleposrědkowanje přepisał.',
'move-redirect-suppressed'     => 'daleposrědkowanje podtłóčene',
'movelogpage'                  => 'Protokol přesunjenjow',
'movelogpagetext'              => 'Deleka je lisćina wšěch přesunjenych stronow.',
'movesubpage'                  => '{{PLURAL:$1|Podstrona|Podstronje|Podstrony|Podstronow}}',
'movesubpagetext'              => 'Strona ma {{PLURAL:$1|slědowacu podstronu|slědowacej $1 podstronje|slědowace $1 podstrony|slědowacych $1 podstronow}}.',
'movenosubpage'                => 'Tuta strona podstrony nima.',
'movereason'                   => 'Přičina:',
'revertmove'                   => 'wróćo přesunyć',
'delete_and_move'              => 'wušmórnyć a přesunyć',
'delete_and_move_text'         => '== Wušmórnjenje trěbne ==

Cilowa strona „[[:$1]]” hižo eksistuje. Chceš ju wušmórnyć, zo by so přesunjenje zmóžniło?',
'delete_and_move_confirm'      => 'Haj, stronu wušmórnyć.',
'delete_and_move_reason'       => 'Strona bu wušmórnjena, zo by so přesunjenje zmóžniło.',
'selfmove'                     => 'Žórłowy a cilowy titl stej samsnej; strona njehodźi so na sebje samu přesunyć.',
'immobile-source-namespace'    => 'njemóže strony w mjenowym rumje "$1" přesunyć',
'immobile-target-namespace'    => 'njemóže strono do mjenoweho ruma "$1" přesunyć',
'immobile-target-namespace-iw' => 'Interwiki-wotkaz njeje płaćiwy cil za přesunjenja stronow.',
'immobile-source-page'         => 'Strona njeda so přesunyć.',
'immobile-target-page'         => 'Njemóžno do teje ciloweje strony přesunyć.',
'imagenocrossnamespace'        => 'Wobraz njeda so do druheho mjenoweho ruma hač wobraz přesunyć',
'nonfile-cannot-move-to-file'  => 'Njedataje njedadźa so do datajoweho mjenoweho ruma přesunyć',
'imagetypemismatch'            => 'Nowa dataja swojemu typej njewotpowěduje',
'imageinvalidfilename'         => 'Mjeno ciloweje dataje je njepłaćiwe',
'fix-double-redirects'         => 'Daleposrědkowanja aktualizować, kotrež na prěnjotny titul pokazuja',
'move-leave-redirect'          => 'Daleposrědkowanje zawostajić',
'protectedpagemovewarning'     => "'''WARNOWANJE:''' Tuta strona bu zawrjena, zo bychu jenož wužiwarjo z prawami administratora móhli ju přesunyć.
Najnowši protokolowy zapisk je deleka jako referenca podaty:",
'semiprotectedpagemovewarning' => "'''Kedźbu:''' Tuta strona bu zawrjena, zo bychu jenož zregistrowani wužiwarjo móhli ju přesunyć.
Najnowši protokolowy zapisk je deleka jako referenca podaty:",
'move-over-sharedrepo'         => '== Dataja eksistuje ==
[[:$1]] eksistuje w hromadźe wužiwanym repozitoriju. Přesunjenje dataje k tutomu titulej budźe hromadźe wužiwanu dataju přepisować.',
'file-exists-sharedrepo'       => 'Wubrane datajowe mjeno so hižo w hromadźe wužiwanym repozitoriju wužiwa.
Prošu wubjer druhe mjeno.',

# Export
'export'            => 'Strony eksportować',
'exporttext'        => 'Móžeš tekst a stawizny wěsteje strony abo skupiny stronow, kotrež su w XML zawite, eksportować. To da so potom do druheho wikija, kotryž ze software MediaWiki dźěła, přez [[Special:Import|importowansku stronu]] importować.

Zo by strony eksportował, zapodaj title deleka do tekstoweho pola, jedyn titul na linku, a wubjer, hač chceš aktualnu wersiju kaž tež stare wersije z linkami stawiznow strony abo jenož aktualnu wersiju z informacijemi wo poslednjej změnje eksportować.

W poslednim padźe móžeš tež wotkaz wužiwać, na př. „[[{{#Special:Export}}/{{MediaWiki:Mainpage}}]]” za stronu „[[{{MediaWiki:Mainpage}}]]”.',
'exportcuronly'     => 'Jenož aktualnu wersiju zapřijeć, nic dospołne stawizny',
'exportnohistory'   => '----
<b>Kedźbu:</b> Eksport cyłych stawiznow přez tutón formular bu z přičin wukonitosće serwera znjemóžnjeny.',
'export-submit'     => 'Eksportować',
'export-addcattext' => 'Strony z kategorije dodawać:',
'export-addcat'     => 'Dodawać',
'export-addnstext'  => 'Strony z mjenoweho ruma přidać:',
'export-addns'      => 'Přidać',
'export-download'   => 'Jako XML-dataju składować',
'export-templates'  => 'Předłohi zapřijeć',
'export-pagelinks'  => 'Wotkazane strony zapřijeć, do hłubokosće wot:',

# Namespace 8 related
'allmessages'                   => 'Systemowe zdźělenki',
'allmessagesname'               => 'Mjeno',
'allmessagesdefault'            => 'Standardny tekst',
'allmessagescurrent'            => 'Aktualny tekst',
'allmessagestext'               => 'To je lisćina systemowych zdźělenkow, kotrež w mjenowym rumje MediaWiki k dispoziciji steja. Prošu wopytaj [http://www.mediawiki.org/wiki/Localisation lokalizaciju MediaWiki] a [http://translatewiki.net translatewiki.net], jeli chceš k powšitkownej lokalizaciji MediaWiki přinošować.',
'allmessagesnotsupportedDB'     => "Tuta strona njeda so wužiwać, dokelž '''\$wgUseDatabaseMessages''' bu znjemóžnjeny.",
'allmessages-filter-legend'     => 'Filter',
'allmessages-filter'            => 'Po přiměrjenskim stawje filtrować:',
'allmessages-filter-unmodified' => 'Njezměnjeny',
'allmessages-filter-all'        => 'Wšě',
'allmessages-filter-modified'   => 'Změnjeny',
'allmessages-prefix'            => 'Po prefiksu filtrować:',
'allmessages-language'          => 'Rěč:',
'allmessages-filter-submit'     => 'Wotesłać',

# Thumbnails
'thumbnail-more'           => 'powjetšić',
'filemissing'              => 'Dataja pobrachuje',
'thumbnail_error'          => 'Zmylk při wutworjenju miniaturki: $1',
'djvu_page_error'          => 'Strona DjVU zwonka wobłuka strony',
'djvu_no_xml'              => 'Daty XML njemóža so za dataju DjVU wotwołać',
'thumbnail_invalid_params' => 'Njepłaćiwe parametry miniaturki',
'thumbnail_dest_directory' => 'Njemóžno cilowy zapis wutworić.',
'thumbnail_image-type'     => 'Wobrazowy typ so njepodpěruje',
'thumbnail_gd-library'     => 'Njedospołna konfiguracija GD-biblioteki: falowaca funkcija $1',
'thumbnail_image-missing'  => 'Zda so, zo dataja faluje: $1',

# Special:Import
'import'                     => 'Strony importować',
'importinterwiki'            => 'Import z druheho wikija',
'import-interwiki-text'      => 'Wuběr wiki a stronu za importowanje. Daty wersijow a mjena awtorow so zachowaja. Wšě akcije za transwiki-importy so w [[Special:Log/import|protokolu importow]] protokoluja.',
'import-interwiki-source'    => 'Žórłowy wiki/Žórłowa strona:',
'import-interwiki-history'   => 'Wšě wersije ze stawiznow tuteje strony kopěrować',
'import-interwiki-templates' => 'Wšě předłohi zapřijeć',
'import-interwiki-submit'    => 'Importować',
'import-interwiki-namespace' => 'Cilowy mjenowy rum:',
'import-upload-filename'     => 'Datajowe mjeno:',
'import-comment'             => 'Přičina:',
'importtext'                 => 'Prošu eksportuj dataju ze žórłoweho wikija z pomocu [[Special:Export|Strony eksportować]]. Składuj ju na swojim ličaku a nahraj ju sem.',
'importstart'                => 'Importuju…',
'import-revision-count'      => '$1 {{PLURAL:$1|wersija|wersiji|wersije|wersijow}}',
'importnopages'              => 'Žane strony za importowanje.',
'imported-log-entries'       => '$1 {{PLURAL:$1|protokolowy zapisk importowany|protokolowej zapiskaj importowanej|protokolowe zapiski importowane|protokolowych zapiskow importowanych}}.',
'importfailed'               => 'Import zwrěšćił: $1',
'importunknownsource'        => 'Njeznate importowe žórło',
'importcantopen'             => 'Importowa dataja njeda so wočinjeć.',
'importbadinterwiki'         => 'Wopačny interwiki-wotkaz',
'importnotext'               => 'Prózdny abo žadyn tekst',
'importsuccess'              => 'Import wuspěšny!',
'importhistoryconflict'      => 'Je konflikt ze stawiznami strony wustupił. Snano bu strona hižo prjedy importowana.',
'importnosources'            => 'Žane importowanske žórła za transwiki wubrane. Direktne nahraće stawiznow je znjemóžnjene.',
'importnofile'               => 'Žana importowanska dataja wubrana.',
'importuploaderrorsize'      => 'Nahraće importoweje dataje je so njeporadźiło. Dataja je wjetša hač dowolena datajowa wulkosć.',
'importuploaderrorpartial'   => 'Nahraće importoweje dataje je so njeporadźiło. Dataja je so jenož zdźěla nahrała.',
'importuploaderrortemp'      => 'Nahraće importoweje dataje je so njeporadźiło. Temporarny zapis faluje.',
'import-parse-failure'       => 'Zmylk za XML-import:',
'import-noarticle'           => 'Žadyn nastawk za import!',
'import-nonewrevisions'      => 'Wšě wersije buchu hižo prjedy importowane.',
'xml-error-string'           => '$1 linka $2, špalta $3, (bajt $4): $5',
'import-upload'              => 'XML-daty nahrać',
'import-token-mismatch'      => 'Strata posedźenskich datow. Prošu spytaj hišće raz.',
'import-invalid-interwiki'   => 'Njeje móžno z podateho wikija importować.',

# Import log
'importlogpage'                    => 'Protokol importow',
'importlogpagetext'                => 'To je lisćina importowanych stronow ze stawiznami z druhich wikijow.',
'import-logentry-upload'           => 'strona [[$1]] bu přez nahraće importowana',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|wersija|wersiji|wersije|wersijow}}',
'import-logentry-interwiki'        => 'je stronu $1 z druheho wikija přenjesł',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|wersija|wersiji|wersije|wersijow}} z $2 {{PLURAL:$1|importowana|importowanej|importowane|importowane}}',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Twoja wužiwarska strona',
'tooltip-pt-anonuserpage'         => 'Wužiwarska strona IP-adresy, z kotrejž tuchwilu dźěłaš',
'tooltip-pt-mytalk'               => 'Twoja diskusijna strona',
'tooltip-pt-anontalk'             => 'Diskusija wo změnach z tuteje IP-adresy',
'tooltip-pt-preferences'          => 'moje nastajenja',
'tooltip-pt-watchlist'            => 'lisćina stronow, kotrež wobkedźbuješ',
'tooltip-pt-mycontris'            => 'Lisćina twojich přinoškow',
'tooltip-pt-login'                => 'Móžeš so woměrje přizjewić, to pak zawjazowace njeje.',
'tooltip-pt-anonlogin'            => 'Móžeš so woměrje přizjewić, to pak zawjazowace njeje.',
'tooltip-pt-logout'               => 'so wotzjewić',
'tooltip-ca-talk'                 => 'diskusija wo stronje',
'tooltip-ca-edit'                 => 'Móžeš stronu wobdźěłać. Prošu wužij tłóčku „Přehlad” do składowanja.',
'tooltip-ca-addsection'           => 'Nowy wotrězk započeć',
'tooltip-ca-viewsource'           => 'Strona je škitana. Móžeš pak jeje žórło wobhladać.',
'tooltip-ca-history'              => 'stawizny tuteje strony',
'tooltip-ca-protect'              => 'stronu škitać',
'tooltip-ca-unprotect'            => 'Tutu stronu hižo nješkitać',
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
'tooltip-n-mainpage-description'  => 'Hłownu stronu wopytać',
'tooltip-n-portal'                => 'wo projekće, što móžeš činić, hdźe móžeš informacije namakać',
'tooltip-n-currentevents'         => 'pozadkowe informacije wo aktualnych podawkach pytać',
'tooltip-n-recentchanges'         => 'lisćina aktualnych změnow w tutym wikiju',
'tooltip-n-randompage'            => 'připadny nastawk wopytać',
'tooltip-n-help'                  => 'pomocna strona',
'tooltip-t-whatlinkshere'         => 'lisćina wšěch stronow, kotrež sem wotkazuja',
'tooltip-t-recentchangeslinked'   => 'aktualne změny w stronach, na kotrež tuta strona wotkazuje',
'tooltip-feed-rss'                => 'RSS-feed za tutu stronu',
'tooltip-feed-atom'               => 'Atom-feed za tutu stronu',
'tooltip-t-contributions'         => 'přinoški tutoho wužiwarja wobhladać',
'tooltip-t-emailuser'             => 'wužiwarjej mejlku pósłać',
'tooltip-t-upload'                => 'Dataje nahrać',
'tooltip-t-specialpages'          => 'lisćina wšěch specialnych stronow',
'tooltip-t-print'                 => 'ćišćowy napohlad tuteje strony',
'tooltip-t-permalink'             => 'trajny wotkaz k tutej wersiji strony',
'tooltip-ca-nstab-main'           => 'stronu wobhladać',
'tooltip-ca-nstab-user'           => 'wužiwarsku stronu wobhladać',
'tooltip-ca-nstab-media'          => 'datajowu stronu wobhladać',
'tooltip-ca-nstab-special'        => 'To je specialna strona. Njemóžeš ju wobdźěłać.',
'tooltip-ca-nstab-project'        => 'projektowu stronu wobhladać',
'tooltip-ca-nstab-image'          => 'Datajowu stronu pokazać',
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
'tooltip-rollback'                => '"Rollback" anuluje změny strony poslednjeho sobudźěłaćerja přez jedne kliknjenje.',
'tooltip-undo'                    => 'anuluje tutu změnu a wočinja wobdźěłowanski formular w přehladowym modusu. Zmóžnja přičinu w zjeću přidać.',
'tooltip-preferences-save'        => 'Nastajenja składować',
'tooltip-summary'                 => 'Zapodaj krótke zjeće',

# Stylesheets
'common.css'   => '/* CSS w tutej dataji budźe so na wšěch stronow wuskutkować. */',
'monobook.css' => '/* CSS wobdźěłać, zo by so skin „monobook” za wšěčh wužiwarjow tutoho skina priměrił */',

# Scripts
'common.js'   => '/* Kóždy JavaScript tu so za wšěch wužiwarjow při kóždym zwobraznjenju někajkeje strony začita. */',
'monobook.js' => '/* Slědowacy JavaScript začita so za wužiwarjow, kotřiž šat MonoBook wužiwaja */',

# Metadata
'nodublincore'      => 'Dublin Core RDF metadaty su za tutón serwer znjemóžnjene.',
'nocreativecommons' => 'Creative Commons RDF metadaty su za tutón serwer znjemóžnjene.',
'notacceptable'     => 'Serwer wikija njemóže daty we formaće poskićić, kotryž twój wudawanski nastroj móže čitać.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Anonymny wužiwar|Anonymnaj wužiwarjej|Anonymni wužiwarjo|Anonymni wužiwarjo}} we {{GRAMMAR:lokatiw|{{SITENAME}}}}',
'siteuser'         => 'wužiwarja $1 na {{GRAMMAR:lokatiw|{{SITENAME}}}}',
'anonuser'         => 'anonymny wužiwar $1 na {{GRAMMAR:lokatiw|{{SITENAME}}}}',
'lastmodifiedatby' => 'Strona bu dnja $1 w $2 hodź. wot $3 změnjena.',
'othercontribs'    => 'Bazěruje na dźěle wužiwarja $1.',
'others'           => 'druhich',
'siteusers'        => ' {{PLURAL:$2|wužiwarja|wužiwarjeju|wužiwarjow|wužiwarjow}} $1 na {{GRAMMAR:lokatiw|{{SITENAME}}}}',
'anonusers'        => ' {{PLURAL:$2|anonymny wužiwar|anonymnaj wužiwarjej|anonymni wužiwarjo|anonymnych wužiwarjow}} $1 na {{GRAMMAR:lokatiw|{{SITENAME}}}}',
'creditspage'      => 'Dźak awtoram',
'nocredits'        => 'Za tutu stronu žane informacije wo zasłužbach njejsu.',

# Spam protection
'spamprotectiontitle' => 'Spamowy filter',
'spamprotectiontext'  => 'Strona, kotruž sy spytał składować, bu přez spamowy filter zablokowana. To so najskerje přez wotkaz na  eksterne sydło w čornej lisćinje zawinuje.',
'spamprotectionmatch' => 'Sćěhowacy tekst je naš spamowy filter wotpokazał: $1',
'spambot_username'    => 'MediaWiki čisćenje wot spama',
'spam_reverting'      => 'wróćo na poslednju wersiju, kotraž wotkazy na $1 njewobsahuje',
'spam_blanking'       => 'Wšě wersije wobsahowachu wotkazy na $1, wučisćene.',

# Info page
'infosubtitle'   => 'Informacije za stronu',
'numedits'       => 'Ličba změnow (nastawk): $1',
'numtalkedits'   => 'Ličba změnow (diskusijna strona): $1',
'numwatchers'    => 'Ličba wobkedźbowarjow: $1',
'numauthors'     => 'Ličba rozdźělnych awtorow (nastawk): $1',
'numtalkauthors' => 'Ličba rozdźělnych awtorow (diskusijna strona): $1',

# Skin names
'skinname-standard'    => 'Klasiski',
'skinname-nostalgia'   => 'Nostalgija',
'skinname-cologneblue' => 'Kölnjanska módrina',
'skinname-monobook'    => 'MonoBook',
'skinname-myskin'      => 'MySkin',
'skinname-chick'       => 'Ćipka',
'skinname-simple'      => 'Jednory',
'skinname-modern'      => 'Moderny',

# Math options
'mw_math_png'    => 'Přeco jako PNG zwobraznić',
'mw_math_simple' => 'HTML jeli jara jednory, hewak PNG',
'mw_math_html'   => 'HTML jeli móžno, hewak PNG',
'mw_math_source' => 'Jako TeX wostajić (za tekstowe wobhladowaki)',
'mw_math_modern' => 'Za moderne wobhladowaki doporučene',
'mw_math_mathml' => 'MathML jeli móžno (eksperimentalnje)',

# Math errors
'math_failure'          => 'Analyza njeje so poradźiła',
'math_unknown_error'    => 'njeznaty zmylk',
'math_unknown_function' => 'njeznata funkcija',
'math_lexing_error'     => 'leksikalny zmylk',
'math_syntax_error'     => 'syntaktiski zmylk',
'math_image_error'      => 'Konwertowanje do PNG zwrěšćiło; kontroluj prawu instalaciju latex, dvips, gs a konwertuj',
'math_bad_tmpdir'       => 'Njemóžno do nachwilneho matematiskeho zapisa pisać abo jón wutworić',
'math_bad_output'       => 'Njemóžno do matematiskeho zapisa za wudaće pisać abo jón wutworić',
'math_notexvc'          => 'Wuwjedźomny texvc pobrachuje; prošu hlej math/README za konfiguraciju.',

# Patrolling
'markaspatrolleddiff'                 => 'Změnu jako přepruwowanu woznamjenić',
'markaspatrolledtext'                 => 'Tutu změnu nastawka jako přepruwowanu woznamjenić',
'markedaspatrolled'                   => 'Změna bu jako přepruwowana woznamjenjena.',
'markedaspatrolledtext'               => 'Wubrana wersija [[:$1]] bu jako dohladowana woznamjenjena.',
'rcpatroldisabled'                    => 'Přepruwowanje aktualnych změnow je znjemóžnjene.',
'rcpatroldisabledtext'                => 'Funkcija přepruwowanja aktualnych změnow je tuchwilu znjemóžnjena.',
'markedaspatrollederror'              => 'Njemóžno jako přepruwowanu woznamjenić.',
'markedaspatrollederrortext'          => 'Dyrbiš wersiju podać, kotraž so ma jako přepruwowana woznamjenić.',
'markedaspatrollederror-noautopatrol' => 'Njesměš swoje změny jako přepruwowane woznamjenjeć.',

# Patrol log
'patrol-log-page'      => 'Protokol přepruwowanjow',
'patrol-log-header'    => 'To je protokol dohladowanych wersijow.',
'patrol-log-line'      => 'je $1 strony $2 jako přepruwowanu markěrował $3.',
'patrol-log-auto'      => '(awtomatisce)',
'patrol-log-diff'      => 'wersiju $1',
'log-show-hide-patrol' => 'Protokol dohladowanja $1',

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
'previousdiff' => '← Předchadna změna',
'nextdiff'     => 'Přichodna změna →',

# Media information
'mediawarning'         => "'''Warnowanje''': Tutón datajowy typ móhł złowólny kod wobsahować. Hdyž so wuwjedźe,  móhł so twój system wobškodźić.",
'imagemaxsize'         => "Maksimalna wobrazowa wulkosć:<br />''(za strony datajoweho wopisanja)''",
'thumbsize'            => 'Wulkosć miniaturkow (thumbnails):',
'widthheight'          => '$1x$2',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|strona|stronje|strony|stronow}}',
'file-info'            => 'Wulkosć dataje: $1, typ MIME: $2',
'file-info-size'       => '($1 × $2 pikselow, wulkosć dataje: $3, typ MIME: $4)',
'file-nohires'         => '<small>Za tutu dataju žane wyše rozeznaće njeje.</small>',
'svg-long-desc'        => '(SVG-dataja, zakładna wulkosć: $1 × $2 pikselow, datajowa wulkosć: $3)',
'show-big-image'       => 'Wersija z wyšim rozeznaćom',
'show-big-image-thumb' => '<small>Wulkosć miniaturki: $1 × $2 pikselow</small>',
'file-info-gif-looped' => 'Bjezkónčna sekla',
'file-info-gif-frames' => '$1 {{PLURAL:$1|wobłuk|wobłukaj|wobłuki|wobłukow}}',
'file-info-png-looped' => 'Sekla',
'file-info-png-repeat' => '{{PLURAL:$1|$1 raz|dwójce|$1 razy|$1 razow}} wotehrata',
'file-info-png-frames' => '$1 {{PLURAL:$1|wobłuk|wobłukaj|wobłuki|wobłukow}}',

# Special:NewFiles
'newimages'             => 'Nowe dataje',
'imagelisttext'         => "Deleka je lisćina '''$1''' {{PLURAL:$1|dataje|datajow|datajow|datajow}}, kotraž je po $2 sortěrowana.",
'newimages-summary'     => 'Tuta specialna strona naliči aktualnje nahrate wobrazy a druhe dataje.',
'newimages-legend'      => 'Filter',
'newimages-label'       => 'Datajowe mjeno (abo dźěl z njeho):',
'showhidebots'          => '(bots $1)',
'noimages'              => 'Žane dataje.',
'ilsubmit'              => 'Pytać',
'bydate'                => 'datumje',
'sp-newimages-showfrom' => 'Nowe dataje pokazać, započinajo wot $1, $2',

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
* isospeedratings
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

'exif-orientation-1' => 'Normalnje',
'exif-orientation-2' => 'Wodorunje wobroćeny',
'exif-orientation-3' => '180° zwjertnjeny',
'exif-orientation-4' => 'Padorunje wobroćeny',
'exif-orientation-5' => '90° přećiwo směrej časnika zwjertneny a padorunje wobroćeny',
'exif-orientation-6' => '90° w směrje časnika zwjertnjeny',
'exif-orientation-7' => '90° w směrje časnika zwjertnjeny a padorunje wobroćeny',
'exif-orientation-8' => '90° přećiwo směrej časnika zwjertnjeny',

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
'exif-meteringmode-5'   => 'Šema',
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

# Flash modes
'exif-flash-fired-0'    => 'Błysk so njepušći',
'exif-flash-fired-1'    => 'Błysk zahibany',
'exif-flash-return-0'   => 'žana funkcija za spóznaće błyskoweje refleksije',
'exif-flash-return-2'   => 'žana refleksija błyska wotkryta',
'exif-flash-return-3'   => 'refleksija błyska wotkryta',
'exif-flash-mode-1'     => 'wunućeny błysk',
'exif-flash-mode-2'     => 'Wunućeny błysk potłóčeny',
'exif-flash-mode-3'     => 'awtomatiski modus',
'exif-flash-function-1' => 'Žana błyskowa funkcija',
'exif-flash-redeye-1'   => 'Redukcija čerwjenych wočow',

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

'exif-scenecapturetype-0' => 'Standard',
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
'exif-subjectdistancerange-1' => 'Makro',
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

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'km/h',
'exif-gpsspeed-m' => 'mila/h',
'exif-gpsspeed-n' => 'Suki',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Woprawdźity směr',
'exif-gpsdirection-m' => 'Magnetiski směr',

# External editor support
'edit-externally'      => 'Dataju z eksternym programom wobdźěłać',
'edit-externally-help' => '(Hlej [http://www.mediawiki.org/wiki/Manual:External_editors pokiwy za instalaciju] za dalše informacije)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'wšě',
'imagelistall'     => 'wšě',
'watchlistall2'    => 'wšě',
'namespacesall'    => 'wšě',
'monthsall'        => 'wšě',
'limitall'         => 'wšě',

# E-mail address confirmation
'confirmemail'              => 'Emailowu adresu wobkrućić',
'confirmemail_noemail'      => 'Njejsy płaćiwu e-mejlowu adresu w swojich [[Special:Preferences|nastajenjach]] podał.',
'confirmemail_text'         => 'Tutón wiki žada, zo swoju e-mejlowu adresu wobkrućiš, prjedy hač e-mejlowe funkcije wužiješ. Zaktiwuzij tłóčatko deleka, zo by swojej adresy wobkrućensku mejlku pósłał. Mejlka zapřijmje wotkaz, kotryž kod wobsahuje; wočiń wotkaz we swojim wobhladowaku, zo by wobkrućił, zo twoja e-mejlowa adresa je płaćiwa.',
'confirmemail_pending'      => ' Potwjerdźenski kod bu hižo z e-mejlu připósłany. Jeli sy runje swoje konto wutworił, wočakaj prošu někotre mjeńšiny, prjedy hač sej nowy kod žadaš.',
'confirmemail_send'         => 'Wobkrućenski kod pósłać',
'confirmemail_sent'         => 'Wobkrućenska mejlka bu wotesłana.',
'confirmemail_oncreate'     => 'Wobkrućenski kod bu na twoju e-mejlowu adresu pósłany. Tutón kod za přizjewjenje trěbne njeje, trjebaš jón pak, zo by e-mejlowe funkcije we wikiju aktiwizował.',
'confirmemail_sendfailed'   => '{{SITENAME}} njemóžeše twoje potwjerdźensku e-mejlku pósłać. Přepytaj prošu swoju e-mejlowu adresu za njepłaćiwymi znamješkami.

E-mejlowy program je wróćił: $1',
'confirmemail_invalid'      => 'Njepłaćiwy wobkrućacy kod. Kod je snano spadnył.',
'confirmemail_needlogin'    => 'Dyrbiš so $1, zo by e-mejlowu adresu wobkrućić móhł.',
'confirmemail_success'      => 'Twoja e-mejlowa adresa bu wobkrućena. Móžeš so nětko přizjewić.',
'confirmemail_loggedin'     => 'Twoja e-mejlowa adresu bu nětko wobkrućena.',
'confirmemail_error'        => 'Zmylk při wobkrućenju twojeje e-mailoweje adresy.',
'confirmemail_subject'      => '{{SITENAME}} – wobkrućenje e-mejloweje adresy',
'confirmemail_body'         => 'Něchtó, najskerje ty z IP-adresu $1, je wužiwarske konto "$2" z tutej e-mejlowej adresu we {{GRAMMAR:lokatiw|{{SITENAME}}}} zregistrował.

Zo by so wobkrućiło, zo tute konto woprawdźe tebi słuša a zo bychu so e-mejlowe funkcije we {{GRAMMAR:lokatiw|{{SITENAME}}}} zaktiwizowali, wočiń tutón wotkaz w swojim wobhladowaku:

$3

Jeli *njej*sy konto zregistrował, slěduj wotkaz, zo by wobkrućenje e-mejloweje adresy přetorhnył:

$5

Tute wobkrućenski kod spadnje $4.',
'confirmemail_body_changed' => 'Něchtó, najskerje ty z IP-adresu $1, je e-mejlowu adresu konta "$2" do tuteje adresy na {{GRAMMAR:lokatiw|{{SITENAME}}}} změnił.

Zo by so wobkrućiło, zo tute konto woprawdźe tebi słuša a zo bychu so e-mejlowe funkcije na {{GRAMMAR:lokatiw|{{SITENAME}}}} znowa zaktiwizowali, wočiń tutón wotkaz w swojim wobhladowaku:

$3

Jeli konto ći *nje*słuša, slěduj wotkaz, zo by wobkrućenje e-mejloweje adresy přetorhnył:

$5

Tute wobkrućenski kod spadnje $4.',
'confirmemail_invalidated'  => 'E-mejlowe potwjerdźenje přetorhnjene',
'invalidateemail'           => 'E-mejlowe potwjerdźenje přetorhnyć',

# Scary transclusion
'scarytranscludedisabled' => '[Zapřijeće mjezyrěčnych wotkazow je znjemóžnjene]',
'scarytranscludefailed'   => '[Zapřijimanje předłohi za $1 je so njeporadźiło]',
'scarytranscludetoolong'  => '[URL je předołhi]',

# Trackbacks
'trackbackbox'      => 'Trackbacks za tutón nastawk:<br />
$1',
'trackbackremove'   => '([$1 wušmórnyć])',
'trackbacklink'     => 'Wróćosćěhowanje',
'trackbackdeleteok' => 'Trackback bu wuspěšnje wušmórnjeny.',

# Delete conflict
'deletedwhileediting' => "'''Kedźbu''': Tuta strona bu wušmórnjena, po tym zo sy započał ju wobdźěłać!",
'confirmrecreate'     => "Wužiwar [[User:$1|$1]] ([[User talk:$1|diskusija]]) je stronu wušmórnył, po tym zo sy započał ju wobdźěłać. Přičina:
: ''$2''
Prošu potwjerdź, zo chceš tutu stronu woprawdźe znowa wutworić.",
'recreate'            => 'Znowa wutworić',

# action=purge
'confirm_purge_button' => 'W porjadku',
'confirm-purge-top'    => 'Pufrowak strony wuprózdnić?',
'confirm-purge-bottom' => 'Wuprózdnja pufrowak a wunuzuje zwobraznjenje aktualneje wersije.',

# Multipage image navigation
'imgmultipageprev' => '← předchadna strona',
'imgmultipagenext' => 'přichodna strona →',
'imgmultigo'       => 'Dźi!',
'imgmultigoto'     => 'Dźi k stronje $1',

# Table pager
'ascending_abbrev'         => 'postupowacy',
'descending_abbrev'        => 'zestupowacy',
'table_pager_next'         => 'přichodna strona',
'table_pager_prev'         => 'předchadna strona',
'table_pager_first'        => 'prěnja strona',
'table_pager_last'         => 'poslednja strona',
'table_pager_limit'        => '$1 {{PLURAL:$1|wuslědk|wuslědkaj|wuslědki|wuslědkow}} na stronu pokazać',
'table_pager_limit_label'  => 'Zapiski na stronu:',
'table_pager_limit_submit' => 'Wotpósłać',
'table_pager_empty'        => 'Žane wuslědki',

# Auto-summaries
'autosumm-blank'   => 'Je stronu wuprózdnił',
'autosumm-replace' => "Strona bu z hinašim tekstom přepisana: '$1'",
'autoredircomment' => 'posrědkuju k stronje „[[$1]]”',
'autosumm-new'     => "Wutwori stronu z '$1'",

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
'lag-warn-normal' => 'Změny {{PLURAL:$1|zašłeje $1 sekundy|zašłeju $1 sekundow|zašłych $1 sekundow|zašłych $1 sekundow}} so w tutej lisćinje hišće njezwobraznjeja.',
'lag-warn-high'   => 'Wućeženja datoweje banki dla so změny {{PLURAL:$1|zašłeje $1 sekundy|zašłeje $1 sekundow|zašłych $1 sekundow|zašłych $1 sekundow}} w tutej lisćinje hišće njepokazuja.',

# Watchlist editor
'watchlistedit-numitems'       => 'Twoje wobkedźbowanki wobsahuja {{PLURAL:$1|1 zapisk|$1 zapiskaj|$1 zapiski|$1 zapiskow}}, diskusijne strony njejsu ličene.',
'watchlistedit-noitems'        => 'Twoje wobkedźbowanki su prózdne.',
'watchlistedit-normal-title'   => 'Wobkedźbowanki wobdźěłać',
'watchlistedit-normal-legend'  => 'Zapiski z wobkedźbowankow wotstronić',
'watchlistedit-normal-explain' => 'Tu su zapiski z twojich wobkedźbowankow. Zo by zapisk wušmórnył, markěruj kašćik pódla njeho a klikń na {{int:Watchlistedit-normal-submit}}". Móžeš tež swoje wobkedźbowanki [[Special:Watchlist/raw|w lisćinowym formaće wobdźěłać]].',
'watchlistedit-normal-submit'  => 'Zapiski wotstronić',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 zapisk bu|$1 zapiskaj buštej|$1 zapiski buchu|$1 zapiskow  buchu}} z twojich wobkedźbowankow {{PLURAL:$1|wotstronjeny|wotstronjenej|wotstronjene|wotstronjene}}:',
'watchlistedit-raw-title'      => 'Wobkedźbowanki w lisćinowym formaće wobdźěłać',
'watchlistedit-raw-legend'     => 'Wobkedźbowanki w lisćinowym formaće wobdźěłać',
'watchlistedit-raw-explain'    => 'Titule mjez twojimi wobkedźbowankach so deleka pokazuja, a dadźa so lisćinje přidać abo z njeje wotstronić;
jedyn titul na linku.
Hdyž sy hotowy, klikń na "{{int:Watchlistedit-raw-submit}}".
Móžeš tež [[Special:Watchlist/edit|standardnu wobdźěłowansku stronu]] wužiwać.',
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
'iranian-calendar-m2' => 'Ordibehešt',

# Core parser functions
'unknown_extension_tag' => 'Njeznata taflička rozšěrjenja "$1"',
'duplicate-defaultsort' => 'Warnowanje: Standardny sortěrowonski kluč (DEFAULTSORTKEY) "$2" přepisa prjedawšu sortěrowanski kluč "$1".',

# Special:Version
'version'                          => 'Wersija',
'version-extensions'               => 'Instalowane rozšěrjenja',
'version-specialpages'             => 'Specialne strony',
'version-parserhooks'              => 'Parserowe hoki',
'version-variables'                => 'Wariable',
'version-other'                    => 'Druhe',
'version-mediahandlers'            => 'Předźěłaki medijow',
'version-hooks'                    => 'Hoki',
'version-extension-functions'      => 'Funkcije rozšěrjenjow',
'version-parser-extensiontags'     => "Parserowe rozšěrjenja ''(taflički)''",
'version-parser-function-hooks'    => 'Parserowe funkcije',
'version-skin-extension-functions' => 'Rozšěrjenske funkcije za šaty',
'version-hook-name'                => 'Mjeno hoki',
'version-hook-subscribedby'        => 'Abonowany wot',
'version-version'                  => '(Wersija $1)',
'version-license'                  => 'Licenca',
'version-poweredby-credits'        => "Tutón wiki so wot  '''[http://www.mediawiki.org/ MediaWiki]''', copyright © 2001-$1 $2 podpěruje.",
'version-poweredby-others'         => 'druzy',
'version-license-info'             => 'MediaWiki je swobodna softwara: móžeš ju pod wuměnjenjemi licency GNU General Public License, wozjewjeneje wot załožby Free Software Foundation, rozdźělić a/abo změnić: pak pod wersiju 2 licency pak pod někajkej pozdźišej wersiju.

MediaWiki so w nadźiji rozdźěla, zo budźe wužitny, ale BJEZ GARANTIJU: samo bjez wobsahowaneje garantije PŘEDAWAJOMNOSĆE abo PŘIHÓDNOSĆE ZA WĚSTY ZAMĚR. Hlej GNU general Public License za dalše podrobnosće.

Ty měł [{{SERVER}}{{SCRIPTPATH}}/COPYING kopiju licency GNU General Public License] hromadźe z tutym programom dóstanu měć: jeli nic, napisaj do załožby Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA abo [http://www.gnu.org/licenses/old-licenses/gpl-2.0.html přečitaj ju online].',
'version-software'                 => 'Instalowana software',
'version-software-product'         => 'Produkt',
'version-software-version'         => 'Wersija',

# Special:FilePath
'filepath'         => 'Datajowy puć',
'filepath-page'    => 'Dataja:',
'filepath-submit'  => 'Pytać',
'filepath-summary' => 'Tuta specialna strona wróća dospołny puć aktualneje datajoweje wersije. Wobrazy so połnym rozeznaću pokazuja, druhe datajowe typy so ze zwjazanym programom startuja.

Zapodaj datajowe mjeno bjez dodawka "{{ns:file}}:".',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Dwójne dataje pytać',
'fileduplicatesearch-summary'  => "Pytanje za duplikatnymi datajemi na zakładźe jich hašoweje hódnoty.

Zapodaj datajowe mjeno '''bjez''' prefiksa \"{{ns:file}}:\".",
'fileduplicatesearch-legend'   => 'Duplikaty pytać',
'fileduplicatesearch-filename' => 'Datajowe mjeno:',
'fileduplicatesearch-submit'   => 'Pytać',
'fileduplicatesearch-info'     => '$1 × $2 pikselow<br />Datajowa wulkosć: $3<br />Typ MIME: $4',
'fileduplicatesearch-result-1' => 'Dataja "$1" identiske duplikaty nima.',
'fileduplicatesearch-result-n' => 'Dataja "$1" ma {{PLURAL:$2|1 identiski duplikat|$2 identiskej duplikataj|$2 identiske duplikaty|$2 identiskich duplikatow}}.',

# Special:SpecialPages
'specialpages'                   => 'Specialne strony',
'specialpages-note'              => '----
* Normalne specialne strony.
* <strong class="mw-specialpagerestricted">Specialne strony z wobmjezowanym přistupom</strong>',
'specialpages-group-maintenance' => 'Hladanske lisćiny',
'specialpages-group-other'       => 'Druhe specialne strony',
'specialpages-group-login'       => 'Přizjewjenje',
'specialpages-group-changes'     => 'Poslednje změny a protokole',
'specialpages-group-media'       => 'Medije',
'specialpages-group-users'       => 'Wužiwarjo a prawa',
'specialpages-group-highuse'     => 'Často wužiwane strony',
'specialpages-group-pages'       => 'Lisćiny stronow',
'specialpages-group-pagetools'   => 'Nastroje stronow',
'specialpages-group-wiki'        => 'Wikijowe daty a nastroje',
'specialpages-group-redirects'   => 'Daleposrědkowace specialne strony',
'specialpages-group-spam'        => 'Spamowe nastroje',

# Special:BlankPage
'blankpage'              => 'Prózdna strona',
'intentionallyblankpage' => 'Tuta strona je z wotpohladom prózdna.',

# External image whitelist
'external_image_whitelist' => ' #Wostaj tutu linku eksaktnje kaž je<pre>
#Zapodaj deleka fragmenty regularnych wurazow (jenož tón dźěl mjez //)
#Tute přirunuja so z URL eksternych wobrazow
#Přihódne zwobraznja so jako wobrazy, hewak so jenož wotkaz k wobrazej pokaza
#Z linkami, kotrež so z # započinaja, wobchadźeja kaž komentary
#To na wulkopisanje njedźiwa

#Zapodaj wšě fragmenty regularnych wurazow nad tutej linku. Wostaj tutu linku eksaktnje kaž je</pre>',

# Special:Tags
'tags'                    => 'Płaćiwe taflički změnow',
'tag-filter'              => 'Filter [[Special:Tags|tafličkow]]:',
'tag-filter-submit'       => 'Filter',
'tags-title'              => 'Taflički',
'tags-intro'              => 'Tuta strona nalistuje taflički, z kotrymiž softwara móže změnu markěrować a jich woznam.',
'tags-tag'                => 'Mjeno taflički',
'tags-display-header'     => 'Napohlad na lisćinach změnow',
'tags-description-header' => 'Dospołne wopisanje woznama',
'tags-hitcount-header'    => 'Změny z tafličkami',
'tags-edit'               => 'změnić',
'tags-hitcount'           => '$1 {{PLURAL:$1|změna|změnje|změny|změnow}}',

# Special:ComparePages
'comparepages'     => 'Strony přirunać',
'compare-selector' => 'Wersije strony přirunać',
'compare-page1'    => 'Strona 1',
'compare-page2'    => 'Strona 2',
'compare-rev1'     => 'Wersija 1',
'compare-rev2'     => 'Wersija 2',
'compare-submit'   => 'Přirunać',

# Database error messages
'dberr-header'      => 'Tutón wiki ma problem',
'dberr-problems'    => 'Wodaj! Tute sydło ma techniske ćežkosće.',
'dberr-again'       => 'Počakń někotre mjeńšiny a zaktualizuj stronu.',
'dberr-info'        => '(Njeje móžno ze serwerom datoweje banki zwjazać: $1)',
'dberr-usegoogle'   => 'Mjeztym móžeš z pomocu Google pytać.',
'dberr-outofdate'   => 'Wobkedźbuj, zo jich indeksy našeho wobsaha móhli zestarjene być.',
'dberr-cachederror' => 'Slědowaca je pufrowana kopija požadaneje strony a móhła zestarjena być.',

# HTML forms
'htmlform-invalid-input'       => 'Su problemy z twojim zapodaćom',
'htmlform-select-badoption'    => 'Hódnota, kotruž sy zapodał, płaćiwa opcija njeje.',
'htmlform-int-invalid'         => 'Hódnota, kotruž sy zapodał, cyła ličba njeje.',
'htmlform-float-invalid'       => 'Hódnota, kotruž sy podał, ličba njeje.',
'htmlform-int-toolow'          => 'Hódnota, kotruž sy zapodał, je mjeńša hač minimum $1.',
'htmlform-int-toohigh'         => 'Hódnota, kotruž sy zapodał, je wjetša hač maksimum $1.',
'htmlform-required'            => 'Tuta hódnota je trěbna',
'htmlform-submit'              => 'Wotpósłać',
'htmlform-reset'               => 'Změny cofnyć',
'htmlform-selectorother-other' => 'Druhe',

# SQLite database support
'sqlite-has-fts' => '$1 połnotekstowe pytanje podpěruje.',
'sqlite-no-fts'  => '$1 połnotekstowe pytanje njepodpěruje',

);

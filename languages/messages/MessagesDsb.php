<?php
/** Lower Sorbian (Dolnoserbski)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Derbeth
 * @author Dunak
 * @author Dundak
 * @author Kaganer
 * @author Michawiki
 * @author Murjarik
 * @author Nepl1
 * @author Pe7er
 * @author Qualia
 * @author Tlustulimu
 * @author Tlustulimu Nepl1
 */

$fallback = 'de';


$namespaceNames = array(
	NS_MEDIA            => 'Medija',
	NS_SPECIAL          => 'Specialne',
	NS_TALK             => 'Diskusija',
	NS_USER             => 'Wužywaŕ',
	NS_USER_TALK        => 'Diskusija_wužywarja',
	NS_PROJECT_TALK     => '$1 diskusija',
	NS_FILE             => 'Dataja',
	NS_FILE_TALK        => 'Diskusija wó dataji',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_diskusija',
	NS_TEMPLATE         => 'Pśedłoga',
	NS_TEMPLATE_TALK    => 'Diskusija_wó_pśedłoze',
	NS_HELP             => 'Pomoc',
	NS_HELP_TALK        => 'Diskusija_wó_pomocy',
	NS_CATEGORY         => 'Kategorija',
	NS_CATEGORY_TALK    => 'Diskusija_wó_kategoriji',
);

$namespaceAliases = array(
	'Wobraz' => NS_FILE,
	'Diskusija_wó_wobrazu' => NS_FILE_TALK,
);

$datePreferences = array(
	'default',
	'dmy',
	'ISO 8601',
);

$defaultDateFormat = 'dmy';

$dateFormats = array(
	'dmy time' => 'H:i',
	'dmy date' => 'j xg Y',
	'dmy both' => 'H:i, j xg Y',
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Dwójne_dalejpósrědnjenja' ),
	'BrokenRedirects'           => array( 'Njefunkcioněrujuce_dalejpósrědnjenja' ),
	'Disambiguations'           => array( 'Wótkaze_ku_rozjasnjenju_wopśimjeśa' ),
	'Userlogin'                 => array( 'Pśizjawiś_se' ),
	'Userlogout'                => array( 'Wótzjawiś_se' ),
	'CreateAccount'             => array( 'Wužywarske_konto_załožyś' ),
	'Preferences'               => array( 'Nastajenja' ),
	'Watchlist'                 => array( 'Wobglědowańka' ),
	'Recentchanges'             => array( 'Aktualne_změny' ),
	'Upload'                    => array( 'Uploadowaś' ),
	'Listfiles'                 => array( 'Lisćina_datajow' ),
	'Newimages'                 => array( 'Nowe_dataje' ),
	'Listusers'                 => array( 'Wužywarje' ),
	'Listgrouprights'           => array( 'Pšawa_wužywarskich_kupkow' ),
	'Statistics'                => array( 'Statistika' ),
	'Randompage'                => array( 'Pśipadny_bok' ),
	'Lonelypages'               => array( 'Wósyrośone_boki' ),
	'Uncategorizedpages'        => array( 'Njekategorizěrowane_boki' ),
	'Uncategorizedcategories'   => array( 'Njekategorizěrowane_kategorije' ),
	'Uncategorizedimages'       => array( 'Njekategorizěrowane_dataje' ),
	'Uncategorizedtemplates'    => array( 'Njekategorizěrowane_pśedłogi' ),
	'Unusedcategories'          => array( 'Njewužywane_kategorije' ),
	'Unusedimages'              => array( 'Njewužywane_dataje' ),
	'Wantedpages'               => array( 'Póžedane_boki' ),
	'Wantedcategories'          => array( 'Póžedane_kategorije' ),
	'Wantedfiles'               => array( 'Felujuce_dataje' ),
	'Wantedtemplates'           => array( 'Felujuce_pśedłogi' ),
	'Mostlinked'                => array( 'Boki_na_kótarež_wjeźo_nejwěcej_wótkazow' ),
	'Mostlinkedcategories'      => array( 'Nejwěcej_wužywane_kategorije' ),
	'Mostlinkedtemplates'       => array( 'Nejwěcej_wužywane_pśedłogi' ),
	'Mostimages'                => array( 'Nejwěcej_wužywane_dataje' ),
	'Mostcategories'            => array( 'Boki_z_nejwěcej_kategorijami' ),
	'Mostrevisions'             => array( 'Nejwěcej_wobźěłane_boki' ),
	'Fewestrevisions'           => array( 'Nejmjenjej_wobźěłane_boki' ),
	'Shortpages'                => array( 'Nejkrotše_boki' ),
	'Longpages'                 => array( 'Nejdlěše_boki' ),
	'Newpages'                  => array( 'Nowe_boki' ),
	'Ancientpages'              => array( 'Nejstarše_boki' ),
	'Deadendpages'              => array( 'Boki_kenž_su_slěpe_gasy' ),
	'Protectedpages'            => array( 'Šćitane_boki' ),
	'Protectedtitles'           => array( 'Šćitane_title' ),
	'Allpages'                  => array( 'Wšykne_boki' ),
	'Prefixindex'               => array( 'Indeks_prefiksow' ),
	'Ipblocklist'               => array( 'Blokěrowane_IPje' ),
	'Specialpages'              => array( 'Specialne_boki' ),
	'Contributions'             => array( 'Pśinoski' ),
	'Emailuser'                 => array( 'E-mail' ),
	'Confirmemail'              => array( 'E-mail_wobkšuśiś' ),
	'Whatlinkshere'             => array( 'Lisćina_wótkazow' ),
	'Recentchangeslinked'       => array( 'Změny_na_zalinkowanych_bokach' ),
	'Movepage'                  => array( 'Pśesunuś' ),
	'Blockme'                   => array( 'Proksy-blokěrowanje' ),
	'Booksources'               => array( 'Pytaś_pó_ISBN' ),
	'Categories'                => array( 'Kategorije' ),
	'Export'                    => array( 'Eksportěrowaś' ),
	'Version'                   => array( 'Wersija' ),
	'Allmessages'               => array( 'Systemowe_powěsći' ),
	'Log'                       => array( 'Protokole' ),
	'Blockip'                   => array( 'Blokěrowaś' ),
	'Undelete'                  => array( 'Nawrośiś' ),
	'Import'                    => array( 'Importěrowaś' ),
	'Lockdb'                    => array( 'Datowu_banku_blokěrowaś' ),
	'Unlockdb'                  => array( 'Datowu_banku_zasej_spśistupniś' ),
	'Userrights'                => array( 'Pšawa_wužywarjow' ),
	'MIMEsearch'                => array( 'Pytaś_pó_MIME-typje' ),
	'FileDuplicateSearch'       => array( 'Pytanje_datajowych_duplikatow' ),
	'Unwatchedpages'            => array( 'Boki_kenž_njejsu_we_wobglědowańkach' ),
	'Listredirects'             => array( 'Pśesměrowanja' ),
	'Revisiondelete'            => array( 'Wulašowanje_wersijow' ),
	'Unusedtemplates'           => array( 'Njewužywane_pśedłogi' ),
	'Randomredirect'            => array( 'Pśipadne_pśesměrowanje' ),
	'Mypage'                    => array( 'Mój_bok' ),
	'Mytalk'                    => array( 'Mója_diskusija' ),
	'Mycontributions'           => array( 'Móje_pśinoski' ),
	'Listadmins'                => array( 'Administratory' ),
	'Listbots'                  => array( 'Boty' ),
	'Popularpages'              => array( 'Woblubowane_boki' ),
	'Search'                    => array( 'Pytaś' ),
	'Resetpass'                 => array( 'Šćitne_gronidło_slědk_stajiś' ),
	'Withoutinterwiki'          => array( 'Interwikije_feluju' ),
	'MergeHistory'              => array( 'Stawizny_wersijow_zjadnośiś' ),
	'Filepath'                  => array( 'Datajowa_sćažka' ),
	'Invalidateemail'           => array( 'E-mail_njewobkšuśis' ),
	'Blankpage'                 => array( 'Prozny_bok' ),
	'LinkSearch'                => array( 'Pytanje_wótkazow' ),
	'DeletedContributions'      => array( 'Wulašowane_pśinoski' ),
	'Tags'                      => array( 'Toflicki' ),
	'Activeusers'               => array( 'Aktiwne_wužywarje' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Wótkaze pódšmarnuś:',
'tog-highlightbroken'         => 'Wótkaze na njeeksistěrujuce boki formatěrowaś',
'tog-justify'                 => 'Tekst do bloka zrownaś',
'tog-hideminor'               => 'Małe změny schowaś',
'tog-hidepatrolled'           => 'Doglědowane změny w aktualnych změnach schowaś',
'tog-newpageshidepatrolled'   => 'Doglědowane boki z lisćiny nowych bokow schowaś',
'tog-extendwatchlist'         => 'Wobglědowańku wócyniś, aby wšě změny pokazał, nic jano nejnowše',
'tog-usenewrc'                => 'Rozšyrjonu lisćinu aktualnych změnow (JavaScript trěbny) wužywaś',
'tog-numberheadings'          => 'Nadpisma awtomatiski numerěrowaś',
'tog-showtoolbar'             => 'Wobźěłańsku lejstwu pokazaś (JavaScript)',
'tog-editondblclick'          => 'Boki z dwójnym kliknjenim wobźěłaś (JavaScript)',
'tog-editsection'             => 'Wobźěłanje wótstawkow pśez wótkaze [wobźěłaś] zmóžniś',
'tog-editsectiononrightclick' => 'Wobźěłanje wótstawkow pśez kliknjenje z pšaweju tastu myški zmóžniś (JavaScript)',
'tog-showtoc'                 => 'Wopśimjeśe pokazaś, jolic ma bok wěcej nježli 3 nadpisma',
'tog-rememberpassword'        => 'Z toś tym wobglědowakom pśizjawjony wóstaś (za maksimalnje $1 {{PLURAL:$1|źeń|dnja|dny|dnjow}})',
'tog-watchcreations'          => 'Boki, kótarež załožyjom, awtomatiski wobglědowaś',
'tog-watchdefault'            => 'Boki, kótarež změnijom, awtomatiski wobglědowaś',
'tog-watchmoves'              => 'Boki, kótarež som pśesunuł, awtomatiski wobglědowaś',
'tog-watchdeletion'           => 'Boki, kótarež som wulašował, awtomatiski wobglědowaś',
'tog-minordefault'            => 'Wšykne móje změny ako małe markěrowaś',
'tog-previewontop'            => 'Zespominanje wušej wobźěłowańskego póla pokazaś',
'tog-previewonfirst'          => 'Pśi prědnem wobźěłanju pśecej zespominanje pokazaś',
'tog-nocache'                 => 'Cache bokow wobglědowaka znjemóžniś',
'tog-enotifwatchlistpages'    => 'E-mail pósłaś, jolic se wobglědowany bok změnja',
'tog-enotifusertalkpages'     => 'E-mail pósłaś, změnijo-lic se mój diskusijny bok',
'tog-enotifminoredits'        => 'E-mail teke małych změnow dla pósłaś',
'tog-enotifrevealaddr'        => 'Móju e-mailowu adresu w e-mailowych pówěźeńkach pokazaś',
'tog-shownumberswatching'     => 'Licbu wobglědujucych wužywarjow pokazaś',
'tog-oldsig'                  => 'Eksistěrujuca signatura:',
'tog-fancysig'                => 'Ze signaturu kaž z wikitekstom wobchadaś (bźez awtomatiskego wótkaza)',
'tog-externaleditor'          => 'Eksterny editor ako standard wužywaś (jano za ekspertow, pomina sebje specialne nastajenja na wašom licadle. [http://www.mediawiki.org/wiki/Manual:External_editors Dalšne informacije.])',
'tog-externaldiff'            => 'Eksterny diff ako standard wužywaś (jano za ekspertow, pomina sebje specialne nastajenja na wašom licadle. [http://www.mediawiki.org/wiki/Manual:External_editors Dalšne informacije.])',
'tog-showjumplinks'           => 'Wótkaze typa „źi do” zmóžniś',
'tog-uselivepreview'          => 'Live-pśeglěd wužywaś (JavaScript) (eksperimentelnje)',
'tog-forceeditsummary'        => 'Warnowaś, gaž pśi składowanju zespominanje felujo',
'tog-watchlisthideown'        => 'Móje změny na wobglědowańskej lisćinje schowaś',
'tog-watchlisthidebots'       => 'Změny awtomatiskich programow (botow) na wobglědowańskej lisćinje schowaś',
'tog-watchlisthideminor'      => 'Małe změny na wobglědowańskej lisćinje schowaś',
'tog-watchlisthideliu'        => 'Změny pśizjawjonych wužywarjow z wobglědowańki schowaś',
'tog-watchlisthideanons'      => 'Změny anonymnych wužywarjow z wobglědowańki schowaś',
'tog-watchlisthidepatrolled'  => 'Doglědowane změny we wobglědowańce schowaś',
'tog-nolangconversion'        => 'Konwertěrowanje rěcnych wariantow znjemóžniś',
'tog-ccmeonemails'            => 'Kopije e-mailow dostaś, kótarež drugim wužywarjam pósćelom',
'tog-diffonly'                => 'Pśi pśirownowanju wersijow jano rozdźěle pokazaś',
'tog-showhiddencats'          => 'Schowane kategorije pokazaś',
'tog-norollbackdiff'          => 'Rozdźěl pó slědkstajenju zanjechaś',

'underline-always'  => 'pśecej',
'underline-never'   => 'žednje',
'underline-default' => 'pó standarźe browsera',

# Font style option in Special:Preferences
'editfont-style'     => 'Pismowy stil wobźěłowańskego póla:',
'editfont-default'   => 'Standard wobglědowaka',
'editfont-monospace' => 'Pismo z kšuteju znamuškoweju šyrokosću',
'editfont-sansserif' => 'Bźezserifowe pismo',
'editfont-serif'     => 'Serifowe pismo',

# Dates
'sunday'        => 'Njeźela',
'monday'        => 'Pónjeźele',
'tuesday'       => 'Wałtora',
'wednesday'     => 'Srjoda',
'thursday'      => 'Stwórtk',
'friday'        => 'Pětk',
'saturday'      => 'Sobota',
'sun'           => 'Nje',
'mon'           => 'Pón',
'tue'           => 'Wał',
'wed'           => 'Srj',
'thu'           => 'Stw',
'fri'           => 'Pět',
'sat'           => 'Sob',
'january'       => 'januar',
'february'      => 'februar',
'march'         => 'měrc',
'april'         => 'apryl',
'may_long'      => 'maj',
'june'          => 'junij',
'july'          => ' julij',
'august'        => 'awgust',
'september'     => 'september',
'october'       => 'oktober',
'november'      => 'nowember',
'december'      => 'december',
'january-gen'   => 'januara',
'february-gen'  => 'februara',
'march-gen'     => 'měrca',
'april-gen'     => 'apryla',
'may-gen'       => 'maja',
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
'may'           => 'maja',
'jun'           => 'jun',
'jul'           => 'jul',
'aug'           => 'awg',
'sep'           => 'sep',
'oct'           => 'okt',
'nov'           => 'now',
'dec'           => 'dec',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategorija|Kategoriji|Kategorije}}',
'category_header'                => 'Nastawki w kategoriji „$1“',
'subcategories'                  => 'Pódkategorije',
'category-media-header'          => 'Dataje w kategoriji „$1“',
'category-empty'                 => "''W toś tej kategoriji njejsu něnto žedne nastawki abo medije.''",
'hidden-categories'              => '{{PLURAL:$1|Schowana kategorija|Schowanej kategoriji|Schowane kategorije|Schowanych kategorijow}}',
'hidden-category-category'       => 'Schowane kategorije',
'category-subcat-count'          => '{{PLURAL:$2|Toś ta kategorija ma jano slědujucu pódkategoriju.|Toś ta kategorija ma {{PLURAL:$1|slědujucu pódkategoriju|slědujucej $1 pódkategoriji|slědujuce $1 kategorije|slědujucych $1 pódkategorijow}} z dogromady $2.}}',
'category-subcat-count-limited'  => 'Toś ta kategorija ma {{PLURAL:$1|slědujucu pódkategoriju|slědujucej $1 pódkategoriji|slědujuce $1 pódkategorije|slědujucych $1 pódkategorijow}}.',
'category-article-count'         => '{{PLURAL:$2|Toś ta kategorija wopśimujo jano slědujucy bok.|{{PLURAL:$1|Slědujucy bok jo|Slědujucej $1 boka stej|Slědujuce $1 boki su|Slědujucych $1 bokow jo}} w toś tej kategoriji, z dogromady $2.}}',
'category-article-count-limited' => '{{PLURAL:$1|Slědujucy bok jo|Slědujucej $1 boka stej|Slědujuce $1 boki su|Slědujucych $1 bokow jo}} w toś tej kategoriji:',
'category-file-count'            => '{{PLURAL:$2|Toś ta kategorija wopśimujo jano slědujucu dataju:|{{PLURAL:$1|Slědujuca dataja jo|Slědujucej $1 dataji stej|Slědujuce $1 dataje su|Slědujucych $1 datajow jo}} w toś tej kategoriji, z dogromady $2.}}',
'category-file-count-limited'    => '{{PLURAL:$1|Slědujuca dataja jo|Slědujucej $1 dataji stej|Slědujuce $1 dataje su|Slědujucych $1 datajow jo}} w toś tej kategoriji {{PLURAL:$1|wopśimjona|wopśimjonej|wopśimjone|wopsímjone}}:',
'listingcontinuesabbrev'         => 'dalej',
'index-category'                 => 'Indicěrowane boki',
'noindex-category'               => 'Njeindicěrowane boki',

'mainpagetext'      => "'''MediaWiki jo se wuspěšnje instalěrowało.'''",
'mainpagedocfooter' => "Pomoc pśi wužywanju softwary wiki namakajoš pód [http://meta.wikimedia.org/wiki/Help:Contents User's Guide].

== Na zachopjenje ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Konfiguracija lisćiny połoženjow]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ (pšašanja a wótegrona)]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Lisćina e-mailowych nakładow MediaWiki]",

'about'         => 'Wó',
'article'       => 'Nastawk',
'newwindow'     => '(se wótcynijo w nowem woknje)',
'cancel'        => 'Pśetergnuś',
'moredotdotdot' => 'Wěcej…',
'mypage'        => 'Mój bok',
'mytalk'        => 'mója diskusija',
'anontalk'      => 'Diskusija z toś teju IP',
'navigation'    => 'Nawigacija',
'and'           => '&#32;a',

# Cologne Blue skin
'qbfind'         => 'Namakaś',
'qbbrowse'       => 'Pśeběraś',
'qbedit'         => 'Pśeměniś',
'qbpageoptions'  => 'Toś ten bok',
'qbpageinfo'     => 'Kontekst',
'qbmyoptions'    => 'Móje boki',
'qbspecialpages' => 'Specialne boki',
'faq'            => 'FAQ (pšašanja a wótegrona)',
'faqpage'        => 'Project:FAQ (pšašanja a wótegrona)',

# Vector skin
'vector-action-addsection'       => 'Temu pśidaś',
'vector-action-delete'           => 'Wulašowaś',
'vector-action-move'             => 'Pśesunuś',
'vector-action-protect'          => 'Šćitaś',
'vector-action-undelete'         => 'Wótnowiś',
'vector-action-unprotect'        => 'Šćit změniś',
'vector-simplesearch-preference' => 'Pólěpšone pytańske naraźenja zmóžniś (jano suknja Vector)',
'vector-view-create'             => 'Napóraś',
'vector-view-edit'               => 'Wobźěłaś',
'vector-view-history'            => 'Wersije a awtory',
'vector-view-view'               => 'Cytaś',
'vector-view-viewsource'         => 'Žrědło se woglědaś',
'actions'                        => 'Akcije',
'namespaces'                     => 'Mjenjowe rumy',
'variants'                       => 'Warianty',

'errorpagetitle'    => 'Zmólka',
'returnto'          => 'Slědk k bokoju $1.',
'tagline'           => 'Z {{GRAMMAR:genitiw|{{SITENAME}}}}',
'help'              => 'Pomoc',
'search'            => 'Pytaś',
'searchbutton'      => 'Pytaś',
'go'                => 'Nastawk',
'searcharticle'     => 'Nastawk',
'history'           => 'wersije',
'history_short'     => 'Wersije a awtory',
'updatedmarker'     => 'Změny wót mójogo slědnego woglěda',
'info_short'        => 'Informacija',
'printableversion'  => 'Wersija za śišć',
'permalink'         => 'Wobstawny wótkaz',
'print'             => 'Śišćaś',
'edit'              => 'wobźěłaś',
'create'            => 'Wuźěłaś',
'editthispage'      => 'Bok wobźěłaś',
'create-this-page'  => 'Bok wuźěłaś',
'delete'            => 'Wulašowaś',
'deletethispage'    => 'Toś ten bok wulašowaś',
'undelete_short'    => '{{PLURAL:$1|1 wersiju|$1 wersiji|$1 wersije}} nawrośiś.',
'protect'           => 'Šćitaś',
'protect_change'    => 'změniś',
'protectthispage'   => 'Bok šćitaś',
'unprotect'         => 'Šćit změniś',
'unprotectthispage' => 'Šćit toś togo boka změniś',
'newpage'           => 'Nowy bok',
'talkpage'          => 'Diskusija',
'talkpagelinktext'  => 'diskusija',
'specialpage'       => 'Specialny bok',
'personaltools'     => 'Wósobinske pomocne srědki',
'postcomment'       => 'Nowy wótrězk',
'articlepage'       => 'Nastawk',
'talk'              => 'Diskusija',
'views'             => 'Naglědy',
'toolbox'           => 'Pomocne srědki',
'userpage'          => 'Wužywarski bok pokazaś',
'projectpage'       => 'Projektowy bok pokazaś',
'imagepage'         => 'Datajowy bok se woglědaś',
'mediawikipage'     => 'Nastawk pokazaś',
'templatepage'      => 'Pśedłogu pokazaś',
'viewhelppage'      => 'Pomocny bok pokazaś',
'categorypage'      => 'Kategoriju pokazaś',
'viewtalkpage'      => 'Diskusija',
'otherlanguages'    => 'W drugich rěcach',
'redirectedfrom'    => '(pósrědnjone z boka „$1”)',
'redirectpagesub'   => 'Dalejpósrědnjenje',
'lastmodifiedat'    => 'Slědna změna boka: $1 w $2 goź.',
'viewcount'         => 'Toś ten bok jo był woglědany {{PLURAL:$1|jaden raz|$1 raza|$1 raze}}.',
'protectedpage'     => 'Śćitany bok',
'jumpto'            => 'Źi na bok:',
'jumptonavigation'  => 'Nawigacija',
'jumptosearch'      => 'Pytaś',
'view-pool-error'   => 'Wódaj, serwery su we wokognuśu pśeśěžone.
Pśewjele wužywarjow wopytujo se toś ten bok woglědaś.
Pšosym pócakaj chylu, nježli až wopytujoš znowego na toś ten bok pśistup měś.

$1',
'pool-timeout'      => 'Pśekšocenje casa wob cakanje na zastajenje',
'pool-queuefull'    => 'Cakajucy rěd jo połny',
'pool-errorunknown' => 'Njeznata zmólka',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Wó {{GRAMMAR:lokatiw|{{SITENAME}}}}',
'aboutpage'            => 'Project:Wó_{{GRAMMAR:lokatiw|{{SITENAME}}}}',
'copyright'            => 'Wopśimjeśe stoj pód $1.',
'copyrightpage'        => '{{ns:project}}:Stwóriśelske pšawo',
'currentevents'        => 'Aktualne tšojenja',
'currentevents-url'    => 'Project:Aktualne tšojenja',
'disclaimers'          => 'Impresum',
'disclaimerpage'       => 'Project:impresum',
'edithelp'             => 'Pomoc pśi wobźěłanju',
'edithelppage'         => 'Help:Pomoc pśi wobźěłanju',
'helppage'             => 'Help:Pomoc',
'mainpage'             => 'Głowny bok',
'mainpage-description' => 'Głowny bok',
'policy-url'           => 'Project:Směrnice',
'portal'               => 'Portal {{GRAMMAR:genitiw|{{SITENAME}}}}',
'portal-url'           => 'Project:portal',
'privacy'              => 'Šćit datow',
'privacypage'          => 'Project:Šćit datow',

'badaccess'        => 'Njamaš trěbnu dowólnosć.',
'badaccess-group0' => 'Njamaš trěbnu dowólnosć za toś tu akciju.',
'badaccess-groups' => 'Akcija, kótaruž sy póžedał, wogranicujo se na wužywarjow w {{PLURAL:$2|kupce|jadnej z kupkow}}: $1.',

'versionrequired'     => 'Wersija $1 softwary MediaWiki trěbna',
'versionrequiredtext' => 'Wersija $1 softwary MediaWiki jo trěbna, aby toś ten bok se mógał wužywaś. Glědaj [[Special:Version|Wersijowy bok]]',

'ok'                      => 'Pytaś',
'retrievedfrom'           => 'Z {{GRAMMAR:genitiw|$1}}',
'youhavenewmessages'      => 'Maš $1 ($2).',
'newmessageslink'         => 'nowe powěsći',
'newmessagesdifflink'     => 'slědna změna',
'youhavenewmessagesmulti' => 'Maš nowe powěsći: $1',
'editsection'             => 'wobźěłaś',
'editold'                 => 'wobźěłaś',
'viewsourceold'           => 'glědaś žrědło',
'editlink'                => 'wobźěłaś',
'viewsourcelink'          => 'Žrědło zwobrazniś',
'editsectionhint'         => 'Wótrězk wobźěłaś: $1',
'toc'                     => 'Wopśimjeśe',
'showtoc'                 => 'pokazaś',
'hidetoc'                 => 'schowaś',
'thisisdeleted'           => '$1 woglědaś abo wobnowiś?',
'viewdeleted'             => '$1 pokazaś?',
'restorelink'             => '{{PLURAL:$1|1 wulašowana wersija|$1 wulašowanej wersiji|$1 wulašowane wersije}}',
'feedlinks'               => 'Nowosći:',
'feed-invalid'            => 'Njepłaśecy typ abonementa.',
'feed-unavailable'        => 'Syndikaciske kanale k dispoziciji njestoje',
'site-rss-feed'           => '$1 RSS Feed',
'site-atom-feed'          => '$1 Atom Feed',
'page-rss-feed'           => '"$1" RSS Feed',
'page-atom-feed'          => '"$1" Atom Feed',
'red-link-title'          => '$1 (bok njeeksistěrujo)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Nastawk',
'nstab-user'      => 'Wužywarski bok',
'nstab-media'     => 'Medije',
'nstab-special'   => 'Specialny bok',
'nstab-project'   => 'Projektowy bok',
'nstab-image'     => 'Dataja',
'nstab-mediawiki' => 'Powěźeńka',
'nstab-template'  => 'Pśedłoga',
'nstab-help'      => 'Pomoc',
'nstab-category'  => 'Kategorija',

# Main script and global functions
'nosuchaction'      => 'Toś tu akciju njedajo',
'nosuchactiontext'  => 'Akcija, kótaruž URL pódawa, jo njepłaśiwa.
Sy se snaź zapisał pśi zapódaśu URL abo sy slědował wopacnemu wótkazoju.
To by mógło teke programěrowańska zmólka w {{GRAMMAR:lokatiw|{{SITENAME}}}} byś.',
'nosuchspecialpage' => 'Toś ten specialny bok njeeksistěrujo',
'nospecialpagetext' => '<strong>Toś ten specialny bok njeeksistěrujo</strong>

Płaśece specialne boki namakaju se pód [[Special:SpecialPages|lisćinu specialnych bokow]].',

# General errors
'error'                => 'Zmólka',
'databaseerror'        => 'Zmólka w datowej bance',
'dberrortext'          => 'Syntaktiska zmólka pśi wótpšašowanju datoweje banki nastata.
To by mógło zmólki w softwarje byś.
Slědne wótpšašowanje jo było:
<blockquote><tt>$1</tt></blockquote>
z funkcije "<tt>$2</tt>".
Datowa banka jo zmólku "<tt>$3: $4</tt>" wrośiła.',
'dberrortextcl'        => 'Syntaktiska zmólka pśi wótpšašowanju datoweje banki nastata.
Slědne wopytane wótpšašowanje jo było:
"$1"
z funkcije "$2".
Datowa banka jo zmólku "$3: $4" wrośiła',
'laggedslavemode'      => 'Glědaj: Jo móžno, až pokazany bok nejaktualnjejše změny njewopśimjejo.',
'readonly'             => 'Datowa banka jo zacynjona',
'enterlockreason'      => 'Pšosym zapódaj pśicynu za zacynjenje datoweje banki a informaciju, ga buźo zasej pśistupna',
'readonlytext'         => 'Datowa banka jo wochylu za nowe zapiski a druge změny zacynjona, nejskerjej dla wótwardowańskich źěłow. Pšosym wopytaj pózdźej hyšći raz.

Administrator, kenž jo ju zacynił, jo pódał toś tu pśicynu: $1',
'missing-article'      => 'Datowa banka njejo namakała tekst boka z mjenim "$1" $2, kótaryž dej se namakaś.

To se zwětšego zawinujo pśez njepłaśiwe wótchylenje abo wótkaz w stawiznach k bokoju, kótaryž jo se južo wulašował.

Jolic to njepśitrjefijo, sy snaź namakał programowu zmólku w softwarje.
Pšosym daj to a pśisłušny URL [[Special:ListUsers/sysop|administratoroju]] k wěsći.',
'missingarticle-rev'   => '(wersija: $1)',
'missingarticle-diff'  => '(rozdźěl: $1, $2)',
'readonly_lag'         => 'Datowa banka jo awtomatiski se zacyniła, aby wótwisne serwery se mógli z głownym serwerom wurownowaś.',
'internalerror'        => 'Interna zmólka',
'internalerror_info'   => 'Interna zmólka: $1',
'fileappenderrorread'  => '"$1" njejo se dał cytaś pśi pśipowjesanju.',
'fileappenderror'      => 'Njejo móžno było "$1" k "$2" pśipowjesyś.',
'filecopyerror'        => 'Njejo było móžno dataju „$1” k „$2” kopěrowaś.',
'filerenameerror'      => 'Njejo było móžno dataju „$1” do „$2” pśemjenjowaś.',
'filedeleteerror'      => 'Njejo było móžno dataju „$1” wulašowaś.',
'directorycreateerror' => 'Njejo było móžno, zapis „$1“ wutwóriś.',
'filenotfound'         => 'Njejo było móžno dataju „$1” namakaś.',
'fileexistserror'      => 'Njejo było móžno do dataje "$1" pisaś: Wóna južo eksistěrujo.',
'unexpected'           => 'Njewócakowana gódnota: „$1“=„$2“.',
'formerror'            => 'Zmólka: Njejo móžno formular wótpósłaś.',
'badarticleerror'      => 'Akcija njedajo se na toś tom boku wuwjasć.',
'cannotdelete'         => 'Njejo móžno było bok abo dataju "$1" wulašowaś. Snaź jo to južo něchten drugi cynił.',
'badtitle'             => 'Njepłaśecy nadpis',
'badtitletext'         => 'Nadpis pominanego boka jo był njepłaśecy, prozny abo njekorektny nadpis, póchadajucy z mjazyrěcnego abo interwikijowego wótkaza. Snaź wopśimjejo jadno abo wěcej znamuškow, kótarež njejsu w nadpisach dowólone.',
'perfcached'           => 'Toś te daty póchadaju z pufrowaka a mógu toś njeaktualne byś.',
'perfcachedts'         => 'Toś te daty póchadaju z pufrowaka, slědna aktualizacija: $1',
'querypage-no-updates' => 'Aktualizěrowanje toś togo boka jo se znjemóžniło. Daty how se nejžpjerwjej raz njeaktualizěruju.',
'wrong_wfQuery_params' => 'Njedobre parametery za wfQuery()<br />
Funkcija: $1<br />
Wótpšašanje: $2',
'viewsource'           => 'Žrědło se wobglědaś',
'viewsourcefor'        => 'za $1',
'actionthrottled'      => 'Akcije limitowane',
'actionthrottledtext'  => 'Ako napšawa pśeśiwo spamoju, móžoš toś tu akciju jano někotare raze we wěstym case wuwjasć. Sy toś ten limit dośěgnuł. Pšosym wopytaj za někotare minuty hyšći raz.',
'protectedpagetext'    => 'Wobźěłanje toś togo boka jo se znjemóžniło.',
'viewsourcetext'       => 'Žrědłowy tekst togo boka móžoš se woglědaś a kopěrowaś:',
'protectedinterface'   => 'Toś ten bok wopśimujo tekst za rěcny zwjerch softwary. Jogo wobźěłowanje jo se znjemóžniło, aby se znjewužywanjeju zadorało.',
'editinginterface'     => "'''Warnowanje:''' Wobźěłujoš bok, kótaryž se wužywa, aby se tekst za pówjerch software MediaWiki k dispoziciji stajił. Změny na toś tom boku buźo wuglědanje wužywarskego pówjercha za drugich wužywarjow wobwliwowaś. Wužywaj pšosym za pśełožki [http://translatewiki.net/wiki/Main_Page?setlang=dsb translatewiki.net], projekt MediaWiki za lokalizacije.",
'sqlhidden'            => '(Wótpšašanje SQL schowane)',
'cascadeprotected'     => 'Za toś ten bok jo se wobźěłowanje znjemóžniło, dokulaž jo zawězany do {{PLURAL:$1|slědujucego boka|slědujuceju bokowu|slědujucych bokow}}, {{PLURAL:$1|kótaryž jo|kótarejž stej|kótarež su}} pśez kaskadowu opciju {{PLURAL:$1|šćitany|šćitanej|šćitane}}: $2',
'namespaceprotected'   => "Njejsy wopšawnjony, boki w rumje: '''$1''' wobźěłaś.",
'customcssjsprotected' => 'Toś te boki njesmějoš wobźěłaś, dokulaž wopśimjeju wósobinske dataje drugego wužywarja.',
'ns-specialprotected'  => 'Njejo móžno, boki w rumje {{ns:special}} wobźěłaś.',
'titleprotected'       => "Bok z toś tym mjenim bu wót [[User:$1|$1]] pśeśiwo napóranjeju šćitany. Pśicyna jo ''$2''.",

# Virus scanner
'virus-badscanner'     => "Špatna konfiguracija: njeznaty wirusowy scanner: ''$1''",
'virus-scanfailed'     => 'Scannowanje jo se njeraźiło (kod $1)',
'virus-unknownscanner' => 'njeznaty antiwirus:',

# Login and logout pages
'logouttext'                 => "'''Sy se něnto wótzjawił.'''

Móžoš {{SITENAME}} anomymnje dalej wužywaś abo móžoš [[Special:UserLogin|se znowego pśizjawiś]] ako samski abo hynakšy wužywaŕ.
Źiwaj na to, až někotare boki se dalej tak zwobraznjuju ako by hyšći pśizjawjeny był, až njewuproznijoš cache swójego wobglědowaka.",
'welcomecreation'            => '== Witaj, $1! ==

Twójo konto jo se załožyło. Njezabydni změniś swóje [[Special:Preferences|nastajenja {{SITENAME}}]].',
'yourname'                   => 'mě wužywarja',
'yourpassword'               => 'šćitne gronidło:',
'yourpasswordagain'          => 'Šćitne gronidło hyšći raz zapódaś:',
'remembermypassword'         => 'Na toś tom licadle pśizjawjony wóstaś (za maksimalnje $1 {{PLURAL:$1|źeń|dnja|dny|dnjow}})',
'securelogin-stick-https'    => 'Pó pśizjawjenju z HTTPS zwězany wóstaś',
'yourdomainname'             => 'Twója domejna',
'externaldberror'            => 'Abo jo wustupiła eksterna zmólka awtentifikacije datoweje banki, abo njesmějoš swójo eksterne wužywarske konto aktualizěrowaś.',
'login'                      => 'Pśizjawiś se',
'nav-login-createaccount'    => 'Pśizjawiś se/Konto załožyś',
'loginprompt'                => 'Za pśizjawjenje do boka {{SITENAME}} muse cookije dowólone byś.',
'userlogin'                  => 'Pśizjawiś se/Konto załožyś',
'userloginnocreate'          => 'Pśizjawiś',
'logout'                     => 'wótzjawiś se',
'userlogout'                 => 'wótzjawiś se',
'notloggedin'                => 'Njepśizjawjony(a)',
'nologin'                    => "Njamaš wužywarske konto? '''$1'''.",
'nologinlink'                => 'Nowe wužywarske konto załožyś',
'createaccount'              => 'Wužywarske konto załožyś',
'gotaccount'                 => "Maš južo wužywarske konto? '''$1'''.",
'gotaccountlink'             => 'Pśizjawiś se',
'createaccountmail'          => 'z e-mailku',
'createaccountreason'        => 'Pśicyna:',
'badretype'                  => 'Šćitnej gronidle, kótarejž sy zapódał, se njemakajotej.',
'userexists'                 => 'Wužywarske mě se južo wužywa.
Pšosym wubjeŕ druge mě.',
'loginerror'                 => 'Zmólka pśi pśizjawjenju',
'createaccounterror'         => 'Wužywarske konto njejo se napóraś dało: $1',
'nocookiesnew'               => 'Wužywarske konto jo se južo wutwóriło, ale wužywaŕ njejo pśizjawjony. {{SITENAME}} wužywa cookije za pśizjawjenja. Jo notne, cookije zmóžniś a se wótnowotki pśizjawiś.',
'nocookieslogin'             => '{{SITENAME}} wužywa cookije za pśizjawjenja. Jo notne, cookije zmóžniś a se wótnowotki pśizjawiś.',
'noname'                     => 'Njejsy žedno płaśece wužywarske mě zapódał.',
'loginsuccesstitle'          => 'Pśizjawjenje wuspěšne',
'loginsuccess'               => "'''Sy něnto ako „$1” w {{GRAMMAR:lokatiw|{{SITENAME}}}} pśizjawjony.'''",
'nosuchuser'                 => 'Wužywaŕ z mjenim „$1“ njeeksistěrujo. Wužywarske mjenja źiwaju na wjelikopisanje.
Pśeglěduj pšawopis abo [[Special:UserLogin/signup|załož nowe konto]].',
'nosuchusershort'            => 'Wužywarske mě „<nowiki>$1</nowiki>“ njeeksistěrujo. Pśeglěduj pšawopis.',
'nouserspecified'            => 'Pšosym pódaj wužywarske mě.',
'login-userblocked'          => 'Toś ten wužywaŕ jo blokěrowany. Pśizjawjenje njejo dowólone.',
'wrongpassword'              => 'Zapódane šćitne gronidło njejo pšawe. Pšosym wopytaj hyšći raz.',
'wrongpasswordempty'         => 'Šćitne gronidło jo było prozne. Pšosym zapódaj jo hyšći raz.',
'passwordtooshort'           => 'Gronidła deje nanejmjenjej {{PLURAL:$|1 znamuško|$1 znamušce|$1 znamuška|$1 znamuškow}} měś.',
'password-name-match'        => 'Twójo gronidło musy se wót swójogo wužywarskego mjenja rozeznaś.',
'password-login-forbidden'   => 'Wužywanje toś togo wužywarskego mjenja a gronidła jo zakazane.',
'mailmypassword'             => 'Nowe gronidło pśipósłaś',
'passwordremindertitle'      => 'Nowe nachylne pótajmne słowo za {{SITENAME}}',
'passwordremindertext'       => 'Něchten z IP-adresu $1 (nejskerjej ty) jo se wupšosył nowe gronidło za {{SITENAME}} ($4).
Nachylne gronidło za wužywarja "$2" jo se napórało a jo něnto "$3". Jolic jo to twój wótglěd było, musyš se něnto pśijawiś a wubraś nowe gronidło. Twójo nachylne gronidło pśepadnjo za {{PLURAL:$5|jadyn źeń|$5 dnja|$5 dny|$5 dnjow}}.

Jolic jo něchten drugi wó nowe šćitne gronidło pšosył abo ty sy se zasej dopomnjeł na swójo gronidło  a njocoš wěcej jo změniś, móžoš toś tu powěsć ignorěrowaś a swójo stare gronidło dalej wužywaś.',
'noemail'                    => 'Wužywaŕ „$1“ njejo e-mailowu adresu zapódał.',
'noemailcreate'              => 'Dejš płaśiwu e-mailowu adresu pódaś',
'passwordsent'               => 'Nowe šćitne gronidło jo se wótpósłało na e-mailowu adresu wužywarja „$1“.
Pšosym pśizjaw se zasej, gaž jo dostanjoš.',
'blocked-mailpassword'       => 'Twója IP-adresa jo se za wobźěłowanje bokow blokěrowała a teke pśipósłanje nowego šćitnego gronidła jo se znjemóžniło, aby se znjewužywanjeju zadorało.',
'eauthentsent'               => 'Wobkšuśenje jo se na e-mailowu adresu wótposłało.

Nježli až wótpósćelo se dalšna e-mail na to wužywarske konto, dejš slědowaś instrukcije w powěsći a tak wobkšuśiś, až konto jo wót wěrnosći twójo.',
'throttled-mailpassword'     => 'W běgu {{PLURAL:$1|slědneje $1 góźiny|slědnjeju $1 góźinowu|slědnych $1 góźinow}} jo se južo raz wó nowe šćitne gronidło pšosyło. Aby se znjewužywanje wobinuło, wótpósćelo se jano jadno šćitne gronidło w běgu {{PLURAL:$1|$1 góźiny|$1 góźinowu|$1 góźinow}}.',
'mailerror'                  => 'Zmólka pśi wótpósłanju e-maila: $1',
'acct_creation_throttle_hit' => 'Woglědowarje toś togo wikija, kótarež wužywaju twóju IP-adresu su napórali {{PLURAL:$1|1 konto|$1 konśe|$1 konta|$1 kontow}} slědny źeń. To jo maksimalna dowólona licba za toś tu periodu.
Woglědowarje, kótarež wužywaju toś tu IP-adresu njamógu tuchylu dalšne konta napóraś.',
'emailauthenticated'         => 'Twója e-mailowa adresa jo se $2 $3 goź. wobkšuśiła.',
'emailnotauthenticated'      => 'Twója e-mailowa adresa njejo hyšći wobkšuśona. E-mailowe funkcije móžoš aklej pó wuspěšnem wobkšuśenju wužywaś.',
'noemailprefs'               => 'Zapódaj e-mailowu adresu w swójich nastajenjach, aby toś te funkcije stali k dispoziciji.',
'emailconfirmlink'           => 'Wobkšuś swóju e-mailowu adresu.',
'invalidemailaddress'        => 'Toś ta e-mailowa adresa njamóžo se akceptěrowaś, dokulaž zda se, až jo njepłaśiwy format. Pšošym zapódaj adresu w korektnem formaśe abo wuprozń to pólo.',
'accountcreated'             => 'Wužywarske konto jo se wutwóriło.',
'accountcreatedtext'         => 'Wužywarske konto $1 jo se wutwóriło.',
'createaccount-title'        => 'Wužywarske konto za {{SITENAME}} nawarjone',
'createaccount-text'         => 'Něchten jo konto za twóje e-mailowu adresu na {{GRAMMAR:lokatiw|{{SITENAME}}}} ($4) z mjenim "$2", z pótajmnym słowom "$3", wutwórił. Dejš se pśizjawiś a swóje pótajmne słowo něnt změniś.

Móžoš toś te zdźělenje ignorowaś, jolic toś te konto jo se jano zamólnje wutwóriło.',
'usernamehasherror'          => 'Wužywarske mě njesmějo hašowe znamuška wopśimjeś',
'login-throttled'            => 'Sy pśecesto wopytał se pśizjawiś. Pócakaj pšosym, nježli až wopytajoš znowego.',
'loginlanguagelabel'         => 'Rěc: $1',
'suspicious-userlogout'      => 'Twójo póžedanje za wótzjawjenim jo se wótpokazało, dokulaž zda se, až jo se pósłało pśez wobškóźony wobglědowak abo pufrowański proksy',

# E-mail sending
'php-mail-error-unknown' => 'Njeznata zmólka w PHP-funkciji mail()',

# Password reset dialog
'resetpass'                 => 'Gronidło změniś',
'resetpass_announce'        => 'Sy z nachylnym e-mailowym šćitnym gronidłom pśizjawjony. Aby pśizjawjenje zakóńcył, zapódaj how nowe šćitne gronidło:',
'resetpass_text'            => '<!-- Dodaj how tekst -->',
'resetpass_header'          => 'Kontowe gronidło změniś',
'oldpassword'               => 'Stare šćitne gronidło:',
'newpassword'               => 'Nowe šćitne gronidło:',
'retypenew'                 => 'Nowe šćitne gronidło (hyšći raz):',
'resetpass_submit'          => 'Šćitne gronidło nastajiś a se pśizjawiś',
'resetpass_success'         => 'Twójo nowe šćitne gronidło jo nastajone. Něnto se pśizjaw …',
'resetpass_forbidden'       => 'Gronidła njedaju se změniś',
'resetpass-no-info'         => 'Dejš pśizjawjony byś, aby direktny pśistup na toś ten bok měł.',
'resetpass-submit-loggedin' => 'Gronidło změniś',
'resetpass-submit-cancel'   => 'Pśetergnuś',
'resetpass-wrong-oldpass'   => 'Njepłaśiwe nachylne abo aktualne gronidło.
Sy snaź swójo gronidło južo wuspěšnje změnił abo nowe nachylne gronidło pominał.',
'resetpass-temp-password'   => 'Nachylne gronidło:',

# Edit page toolbar
'bold_sample'     => 'Tucny tekst',
'bold_tip'        => 'Tucny tekst',
'italic_sample'   => 'Kursiwny tekst',
'italic_tip'      => 'Kursiwny tekst',
'link_sample'     => 'Tekst wótkaza',
'link_tip'        => 'Interny wótkaz',
'extlink_sample'  => 'http://www.example.com nadpismo wótkaza',
'extlink_tip'     => 'Eksterny wótkaz (źiwaś na http://)',
'headline_sample' => 'Nadpismo',
'headline_tip'    => 'Nadpismo rowniny 2',
'math_sample'     => 'Zapódaj how formulu',
'math_tip'        => 'Matematiska formula (LaTeX)',
'nowiki_sample'   => 'Zapódaj how njeformatěrowany tekst',
'nowiki_tip'      => 'Wiki-syntaksu ignorěrowaś',
'image_sample'    => 'Pokazka.jpg',
'image_tip'       => 'Zasajźona dataja',
'media_sample'    => 'pokazka.ogg',
'media_tip'       => 'Datajowy wótkaz',
'sig_tip'         => 'Twója signatura z casowym kołkom',
'hr_tip'          => 'Horicontalna linija (rědko wužywaś)',

# Edit pages
'summary'                          => 'Zespominanje:',
'subject'                          => 'Tema/Nadpismo:',
'minoredit'                        => 'Snadna změna',
'watchthis'                        => 'Toś ten bok wobglědowaś',
'savearticle'                      => 'Bok składowaś',
'preview'                          => 'Pśeglěd',
'showpreview'                      => 'Pśeglěd pokazaś',
'showlivepreview'                  => 'Livepśeglěd',
'showdiff'                         => 'Pśeměnjenja pokazaś',
'anoneditwarning'                  => "'''Warnowanje:''' Njejsy pśizjawjony. Změny w stawiznach togo boka składuju se z twójeju IP-adresu.",
'anonpreviewwarning'               => "''Njejsy pśizjawjony. Składowanje pśenosujo twóju IP-adresu do wobźěłowańskeje historije toś togo boka.''",
'missingsummary'                   => "'''Pokazka:''' Njejsy žedno zespominanje zapódał. Gaž kliknjoš na \"Składowaś\" składujo se bok bźez zespominanja.",
'missingcommenttext'               => 'Pšosym zespominanje zapódaś.',
'missingcommentheader'             => "'''Glědaj:''' Njejsy temu/ nadpismo za toś ten komentar pódał. Gaž kliknjoš na \"{{int:savearticle}}\" znowego, składujo se twójo wobźěłanje mimo temy/nadpisma.",
'summary-preview'                  => 'Pśeglěd zespominanja:',
'subject-preview'                  => 'Pśeglěd nadpisma:',
'blockedtitle'                     => 'Wužywaŕ jo se blokěrował',
'blockedtext'                      => "'''Twójo wužywarske mě abo IP-adresa stej se blokěrowałej.'''

Blokěrowanje pśez $1.
Pódana pśicyna: ''$2''.

* Zachopjeńk blokěrowanja: $8
* Kóńc blokěrowanja: $6
* Blokěrowany wužywaŕ: $7

Móžoš $1 abo drugego [[{{MediaWiki:Grouppage-sysop}}|administratora]] kontaktěrowaś, aby wó blokěrowanju diskutěrował.
Njamóžoš funkciju 'Toś tomu wužywarjeju e-mail pósłaś' wužywaś, snaźkuli płaśiwa e-mailowa adresa jo pódana w swójich kontowych [[Special:Preferences|nastajenjach]] a njeblokěrujos se ju wužywaś.
Twója IP-adresa jo $3, a ID blokěrowanja jo #$5.
Pšosym zapśimjej wše górjejcne drobonosći do napšašowanjo, kótarež cyniš.",
'autoblockedtext'                  => 'Twója IP-adresa jo se awtomatiski blokěrowała, dokulaž jo se wót drugego wužywarja wužywała, kótaryž jo był wót $1 blokěrowany.
Pśicyna:

:\'\'$2\'\'

* Zachopjeńk blokěrowanja: $8
* Kóńc blokěrowanja: $6
* Blokěrowany wužywaŕ: $7

Ty móžoš wužywarja $1 abo jadnogo z drugich [[{{MediaWiki:Grouppage-sysop}}|administratorow]] kontaktěrowaś, aby wó blokaźe diskutěrował.

Wobmysli, až njamóžoš funkciju "Toś tomu wužywarjeju e-mail pósłaś" wužywaś, až njezapódajoš płaśecu adresu na boku wužywarskich [[Special:Preferences|nastajenjow]] a až se njeblokěrujoš ju wužywaś.

Twója aktualna IP-adresa jo $3 a ID blokěrowanja jo #$5.
Zapśimjejśo pšosym wše górjejce pomjenjowane drobnosći do wšych napšašowanjow, kótarež cyniš.',
'blockednoreason'                  => 'Pśicyna njejo dana',
'blockedoriginalsource'            => "Žrědłowy tekst boka '''$1''':",
'blockededitsource'                => "Žrědłowy tekst '''Twójich pśinoskow''' do '''$1''' jo:",
'whitelistedittitle'               => 'Za wobźěłanje dejš se pśizjawiś',
'whitelistedittext'                => 'Musyš se $1, aby mógał boki wobźěłowaś.',
'confirmedittext'                  => 'Nježli až móžoš źěłaš, musyš swóju e-mailowu adresu wobkšuśiś. Pšosym dodaj a wobkšuś swóju e-mailowu adresu w [[Special:Preferences|nastajenjach]].',
'nosuchsectiontitle'               => 'Wótrězk njedajo se namakaś',
'nosuchsectiontext'                => 'Sy wopytał wótrězk wobźěłaś, kótaryž njeeksistěrujo.
Jo se snaź pśesunuł abo wulašował, mjaztym až woglědujoš se bok.',
'loginreqtitle'                    => 'Pśizjawjenje trěbne',
'loginreqlink'                     => 'se pśizjawiś',
'loginreqpagetext'                 => 'Dejš $1, aby mógł boki pšawje cytaś.',
'accmailtitle'                     => 'Šćitne gronidło jo se wótpósłało.',
'accmailtext'                      => "Pśipadnje napórane gronidło za [[User talk:$1|$1]] jo se pósłało k $2.

Gronidło za toś to nowe konto dajo se na boku ''[[Special:ChangePassword|Gronidło změniś]]'' pśi pśizjawjenju změniś.",
'newarticle'                       => '(Nowy nastawk)',
'newarticletext'                   => "Sy slědował wótkaz na bok, kótaryž hyšći njeeksistěrujo.
Aby bok napórał, zapiš do kašćika dołojce (glědaj [[{{MediaWiki:Helppage}}|bok pomocy]] za dalšne informacije). Jolic sy zamólnje how, klikni na tłocašk '''Slědk'' w swójom wobglědowaku.",
'anontalkpagetext'                 => "---- ''Toś jo diskusijny bok za anonymnego wužywarja, kótaryž njejo dotychměst žedno wužywarske konto załožył abo swójo konto njewužywa. Togodla dejmy numerisku IP-adresu wužywaś, aby jogo/ju identificěrowali. Taka IP-adresa dajo se wót wšakich wužywarjow wužywaś. Jolic sy anonymny wužywaŕ a se mysliš, až su se njerelewantne komentary na tebje měrili, [[Special:UserLogin/signup|załož konto]] abo [[Special:UserLogin|pśizjaw se]], aby se w pśichoźe zmuśenje z drugimi anonymnymi wužywarjami wobinuł.''",
'noarticletext'                    => 'Dotychměst toś ten bok hyšći njewopśimujo žeden tekst. Móžoš w drugich bokach [[Special:Search/{{PAGENAME}}|titel togo boka pytaś]], <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} wótpowědne protokole pytaś] abo [{{fullurl:{{FULLPAGENAME}}|action=edit}} toś ten bok wobźěłaś]</span>.',
'noarticletext-nopermission'       => 'Tuchylu njejo žeden tekst na toś tom boku.
Móžoš [[Special:Search/{{PAGENAME}}|toś ten bokowy titel]] na drugich bokach pytaś
abo <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} wótpowědne protokole pytaś]</span>.',
'userpage-userdoesnotexist'        => 'Wužywarske konto "$1" njejo zregistrěrowane. Pšosym pśeglědaj, lěc coš toś ten bok wopšawdu napóraś/wobźěłaś.',
'userpage-userdoesnotexist-view'   => 'Wužywarske konto "$1" njejo zregistrowane.',
'blocked-notice-logextract'        => 'Toś ten wužywaŕ jo tuchylu blokěrowany.
Nejnowšy zapisk blokěrowańskego protokola pódawa se dołojce ako referenca:',
'clearyourcache'                   => "'''Pokazka:''' Pó składowanju dejš snaź wuprozniś cache wobglědowaka, aby změny wiźeł.
* '''Firefox/Safari:''' Źarź ''Umsch'' tłocony, mjaztym až kliknjoš na ''Znowego'' abo tłoc pak ''Strg-F5'' pak ''Strg-R'' (''⌘-R'' na Makintošu)
* '''Google Chrome:''' Tłoc na ''Strg-Umsch-R'' ('⌘-Umsch-R'' na Makintošu)
* '''Internet Explorer:''' Źarź ''Strg'' tłocony, mjaztym až kliknjoš na ''Aktualisieren'' abo tłoc ''Strg-F5''
* '''Konqueror:''' Klikni na ''Aktualisieren'' abo tłoc ''F5''
* '''Opera:''' Wuprozni cache w ''Extras → Einstellungen''",
'usercssyoucanpreview'             => "'''Pokazka:''' Wužywaj tłocašk '{{int:showpreview}}', aby swój nowy css testował, nježli až jen składujoš.",
'userjsyoucanpreview'              => "'''Pokazka:''' Wužywaj tłocašk \"{{int:showpreview}}\", aby swój nowy JavaScript testował, nježli až jen składujoš.",
'usercsspreview'                   => "'''Źiwaj na to, až wobglědujoš se jano pśeglěd swójogo wužywarskego CSS. Njejo se hyšći składował!'''",
'userjspreview'                    => "== Pśeglěd Wašogo wužywarskego JavaScripta ==
'''Glědaj:''' Pó składowanju musyš swójomu browseroju kazaś, aby nowu wersiju pokazał: '''Mozilla/Firefox:''' ''Strg-Shift-R'', '''Internet Explorer:''' ''Strg-F5'', '''Opera:''' ''F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror:''' ''F5''.",
'sitecsspreview'                   => "'''Źiwaj na to, až wobglědujoš se jano pśeglěd toś ten CSS.'''
'''Njejo se hyšći składował!'''",
'sitejspreview'                    => "'''Źiwaj na to, až wobglědujoš se jano pśeglěd toś togo koda JavaScript.'''
'''Njejo se hyšći składował!'''",
'userinvalidcssjstitle'            => "'''Warnowanje:''' Njeeksistěrujo šat „$1“. Pšosym mysli na to, až wužywaju .css- a .js-boki mały pismik, na pś. ''{{ns:user}}:Pśikładowa/vector.css'' město ''{{ns:user}}:Pśikładowa/Vector.css''.",
'updated'                          => '(Zaktualizěrowane)',
'note'                             => "'''Pokazka:'''",
'previewnote'                      => "'''To jo jano pśeglěd, bok njejo hyšći składowany!'''",
'previewconflict'                  => 'Toś ten pśeglěd wótbłyšćujo tekst górjejcnego póla. Bok buźo tak wuglědaś, jolic jen něnto składujoš.',
'session_fail_preview'             => "'''Wódaj! Twójo wobźěłanje njejo se mógało składowaś, dokulaž su daty twójogo pósejźenja se zgubili. Pšosym wopytaj hyšći raz. Jolic až to pón pśecej hyšći njejźo, wopytaj se wótzjawiś a zasej pśizjawiś.'''",
'session_fail_preview_html'        => "'''Wódaj! Twójo wobźěłanje njejo se mógało składowaś, dokulaž su daty twójogo pósejźenja se zgubili.'''

''Dokulaž {{SITENAME}} ma cysty html aktiwizěrowany, jo pśeglěd se zacynił - ako šćit pśeśiwo JavaScriptowym atakam.''

'''Jo-lic to legitiměrowane wobźěłanje, wopytaj hyšći raz. Gaž to zasej njejźo, wopytaj se wót- a zasej pśizjawiś.'''",
'token_suffix_mismatch'            => "'''Twójo wobźěłanje jo se wótpokazało, dokulaž jo twój browser znamuška we wobźěłańskem tokenje rozsekał. Składowanje by mógało wopśimjeśe boka znicyś. Take casy se źejo, gaž wužywaš web-bazěrowanu, zmólkatu, anonymnu proksy-słužbu.'''",
'editing'                          => 'Wobźěłanje boka $1',
'editingsection'                   => 'Wobźěłanje boka $1 (wótrězk)',
'editingcomment'                   => '$1 (nowy wótrězk) se wobźěłujo',
'editconflict'                     => 'Wobźěłański konflikt: $1',
'explainconflict'                  => "Něchten drugi jo bok změnił, pó tym, až sy zachopił jen wobźěłaś.
Górjejcne tekstowe pólo wopśimjejo tekst boka, ako tuchylu eksistěrujo.
Twóje změny pokazuju se w dołojcnem tekstowem pólu.
Pšosym zapódaj twóje změny do górjejcnego tekstowego póla.
'''Jano''' wopśimjeśe górjejcnego tekstowego póla se składujo, gaž tłocyš na \"{{int:savearticle}}\".",
'yourtext'                         => 'Twój tekst',
'storedversion'                    => 'Składowana wersija',
'nonunicodebrowser'                => "'''Glědaj:''' Twój browser njamóžo unicodowe znamuška pšawje pśeźěłaś. Pšosym wužywaj hynakšy browser.",
'editingold'                       => "'''Glědaj: Wobźěłajoš staru wersiju toś togo boka. Gaž składujoš, zgubiju se wšykne nowše wersije.'''",
'yourdiff'                         => 'Rozdźěle',
'copyrightwarning'                 => "Pšosym buź se togo wědobny, až wšykne pśinoski na {{SITENAME}} se wózjawiju pód $2 (za detajle glědaj $1). Jolic až njocoš, až twój tekst se mimo zmilnosći wobźěłujo a za spódobanim drugich redistribuěrujo, pón njeskładuj jen how.<br />
Ty teke wobkšuśijoš, až sy tekst sam napisał abo sy jen wót public domainy resp. wót pódobneje lichotneje resursy kopěrował.

'''NJEWÓZJAW WÓT COPYRIGHTA ŠĆITANE ŹĚŁA MIMO DOWÓLNOSĆI!'''",
'copyrightwarning2'                => "Pšosym buź se togo wědobny, až wšykne pśinoski na {{SITENAME}} mógu wót drugich wužywarjow se wobźěłaś, narownaś abo wulašowaś. Jolic až njocoš, až twój tekst se mimo zmilnosći wobźěłujo, ga pón jen how njeskładuj.<br /> Ty teke wobkšuśijoš, až sy tekst sam napisał abo sy jen wót public domainy resp. wót pódobneje lichotneje resursy kopěrował (glědaj $1 za dalše detaile). '''NJEWÓZJAW WÓT COPYRIGHTA ŠĆITANE ŹĚŁA MIMO DOWÓLNOSĆI!'''",
'longpageerror'                    => "'''Zmólka: Tekst, kótaryž coš składowaś jo $1 KB wjeliki. To jo wěcej, ako dowólony maksimum ($2 KB). Składowanje njejo móžno.'''",
'readonlywarning'                  => "'''WARNOWANJE: Datowa banka jo se za wótwardowanje zacyniła, togodla njebuźo tuchylu móžno, twóje změny składowaś. Jolic až coš, ga móžoš tekst do tekstoweje dataje kopěrowaś a pózdźej składowaś.'''

Administrator, kenž jo ju zastajił, su toś tu pśicynu pódał: $1",
'protectedpagewarning'             => "'''Warnowanje: Toś ten bok jo se zastajił, tak až jano wužywarje z pšawami administratora mógu jen wobźěłaś.'''
Nejnowšy protokolowy zapisk jo dokojce ako referenca pódany:",
'semiprotectedpagewarning'         => "'''Glědaj:''' Toś ten bok jo se zastajił, tak až jano zregistrěrowane wužywarje mógu jen wobźěłaś.
Nejnowšy protokolowy zapisk jo dołojce ako referenca pódany:",
'cascadeprotectedwarning'          => "'''Glědaj: Toś ten bok jo se zakazał, tak až jano wužywarje ze sysopowymi priwiliegijami mógu jen wobźěłaś, dokulaž jo zawězana do {{PLURAL:$1|slědujucego boka|slědujuceju bokowu|slědujucych bokow}}, {{PLURAL:$1|kótaryž jo šćitany|kótarejž stej šćitanej|kótarež su šćitane}} z pomocu kaskadoweje zakazanskeje opcije.'''",
'titleprotectedwarning'            => "'''WARNOWANJE: Toś ten bok jo se zastajił, tak až [[Special:ListGroupRights|wósebne pšawa]] su trěbne, aby se napórał.'''
Nejnowšy protokolowy zapisk jo dołojce ako referenca pódany:",
'templatesused'                    => 'Na toś tom boku {{PLURAL:$1|wužywana pśedłoga|wužywanej pśedłoze|wužyane pśedłogi|wužywane pśedłogi}}:',
'templatesusedpreview'             => 'W toś tom pśeglěźe {{PLURAL:$1|wužywana pśedłoga|wužywanej pśedłoze|wužywane pśedłogi|wužywane pśedłogi}}:',
'templatesusedsection'             => 'W toś tom wótrězku {{PLURAL:$1|wužywana pśedłoga|wužywanej pśedłoze|wužywane pśedłogi|wužywane pśedłogi}}:',
'template-protected'               => '(šćitane)',
'template-semiprotected'           => '(poł šćitane)',
'hiddencategories'                 => 'Toś ten bok jo jadna z {{PLURAL:$1|1 schowaneje kategorije|$1 schowaneju kategorijow|$1 schowanych kategorijow|$1 schowanych kategorijow}}:',
'edittools'                        => '<!-- Tekst how buźo wiźeś pód wobźěłowańskimi a upload-formularami. -->',
'nocreatetitle'                    => 'Załožowanje nowych bokow jo se wobgranicowało.',
'nocreatetext'                     => 'Na {{GRAMMAR:lokatiw|{{SITENAME}}}} jo se załoženje nowych bokow wót serwera wobgranicowało. Móžoš hyś slědk a eksistěrujucy bok wobźěłaś, abo se [[Special:UserLogin|pśizjawiś]].',
'nocreate-loggedin'                => 'Njamaš pšawo nowe boki napóraś.',
'sectioneditnotsupported-title'    => 'Wobźěłowanje wótrězka se njepódpěra',
'sectioneditnotsupported-text'     => 'Wobźěłowanje wótrězka njepódpěra se na toś tom wobźěłowańskem boku.',
'permissionserrors'                => 'Problem z pšawami',
'permissionserrorstext'            => 'Njamaš pšawo to cyniś. {{PLURAL:$1|Pśicyna|Pśicynje|Pśicyny}}:',
'permissionserrorstext-withaction' => 'Njamaš pšawo $2. {{PLURAL:$1|Pśicyna|Pśicynje|Pśicyny|Pśicyny}}:',
'recreate-moveddeleted-warn'       => "'''Glědaj: Ty wótžywijoš bok, kótaryž jo pjerwjej se wulašował.'''

Pšosym pśespytuj kradosćiwje, lěc jo gódnje z wobźěłowanim boka pokšacowaś.
Protokol wulašowanjow a pśesunjenjow za toś ten bok so how za informaciju pódawa:",
'moveddeleted-notice'              => 'Toś ten bok jo se wulašował. Protokol wulašowanjow a pśesunjenjow za toś ten bok pódawa se dołojce ako referenca.',
'log-fulllog'                      => 'Dopołny protokol se woglědaś',
'edit-hook-aborted'                => 'Wobźěłanje pśez kokulu pśetergnjony.
Njejo žedno wujasnjenje.',
'edit-gone-missing'                => 'Njejo móžno było bok aktualizěrowaś.
Zda sem až jo wulašowany.',
'edit-conflict'                    => 'Wobźěłański konflikt.',
'edit-no-change'                   => 'Wašo wobźěłanje jo se ignorěrowało, dokulaž tekst njejo se změnił.',
'edit-already-exists'              => 'Njejo móžno było nowy bok napóraś.
Eksistěrujo južo.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Warnowanje: Toś ten bok wopśimujo pśewjele wołanjow parserowych funkcijow wupominajucych wusoke wugbaśe.

Njesmějo daś wěcej nježli $2 {{PLURAL:$2|wołanja|wołanjowu|wołanjow|wołanjow}}, něnto {{PLURAL:$1|jo $1 wołanje|stej $1 wołani|su $1 wołanja|jo $1 wołanjow}}.',
'expensive-parserfunction-category'       => 'Boki z pśewjele paerserowymi funkcijami, kótarež pominaju sebje wusoke wugbaśe.',
'post-expand-template-inclusion-warning'  => 'Warnowanje: Wjelikosć zapśěgnjonych pśedłogow jo pśewjelika. Někotare pśedłogi se njezapśěgu.',
'post-expand-template-inclusion-category' => 'Boki, w kótarychž maksimalna wjelikosć zapśěgnjonych pśedłogow jo pśekšocona.',
'post-expand-template-argument-warning'   => 'Warnowanje: Toś ten bok wopśimujo nanejmjenjej jaden argument w pśedłoze, kótaryž jo pśwjeliki pó ekspanděrowanju. Toś te argumenty se wuwóstajiju.',
'post-expand-template-argument-category'  => 'Boki, kótarež wuwóstajone pśedłogowe argumenty wopśimuju',
'parser-template-loop-warning'            => 'Pśedłogowa šlejfa namakana: [[$1]]',
'parser-template-recursion-depth-warning' => 'Limit rekursijneje dłymi pśedłogi pśekšocony ($1)',
'language-converter-depth-warning'        => 'Limit dłymokosći rěcnego konwertera pśekšocony ($1)',

# "Undo" feature
'undo-success' => 'Wobźěłanje móžo se wótpóraś. Pšosym pśeglěduj dołojcne pśirownowanje aby se wěsty był, až to wót wěrnosći coš, a pón składuj změny, aby se wobźěłanje doskóńcnje wótpórało.',
'undo-failure' => 'Změna njejo se mógała wótpóraś, dokulaž jo něchten pótrjefjony wótrězk mjaztym změnił.',
'undo-norev'   => 'Změna njeda se wótwrośiś, dokulaž njeeksistěčujo abo jo se wulašowała.',
'undo-summary' => 'Wersija $1 wót [[Special:Contributions/$2|$2]] ([[User talk:$2|Diskusija]]) jo se anulěrowała',

# Account creation failure
'cantcreateaccounttitle' => 'Njejo móžno wužywarske konto wutwóriś',
'cantcreateaccount-text' => "Wutwórjenje wužywarskego konta z toś teje IP adresy ('''$1''') jo blokěrowane pśez [[User:$3|$3]].

Pśicyna, kótaruž $3 jo zapódał, jo ''$2''.",

# History pages
'viewpagelogs'           => 'Protokole boka pokazaś',
'nohistory'              => 'Stawizny wobźěłanja za toś ten bok njeeksistěruju.',
'currentrev'             => 'Aktualna wersija',
'currentrev-asof'        => 'Aktualna wersija wót $1',
'revisionasof'           => 'Wersija z $1',
'revision-info'          => 'Wersija z $1 wót wužywarja $2',
'previousrevision'       => '← Zachadna rewizija',
'nextrevision'           => 'Pśiduca wersija →',
'currentrevisionlink'    => 'Aktualna wersija',
'cur'                    => 'aktualny',
'next'                   => 'pśiduce',
'last'                   => 'zachadne',
'page_first'             => 'zachopjeńk',
'page_last'              => 'kóńc',
'histlegend'             => 'Aby se změny pokazali, dejtej se pśirownanskej wersiji wuzwóliś. Pón dej se "enter" abo dołojcne tłocanko (button) tłocyś.<br />

Legenda:
* (Aktualne) = Rozdźěl k aktualnej wersiji, (pśedchadna) = rozdźěl k pśedchadnej wersiji
* Cas/datum = W toś tom casu aktualna wersija, wužywarske mě/IP-adresa wobźěłarja, D = drobna změna',
'history-fieldset-title' => 'W stawiznach pytaś',
'history-show-deleted'   => 'Jano wulašowane',
'histfirst'              => 'nejstarše',
'histlast'               => 'nejnowše',
'historysize'            => '({{PLURAL:$1|1 byte|$1 byta|$1 byty}})',
'historyempty'           => '(prozne)',

# Revision feed
'history-feed-title'          => 'Stawizny wersijow',
'history-feed-description'    => 'Stawizny wersijow za toś ten bok w {{GRAMMAR:lokatiw|{{SITENAME}}}}',
'history-feed-item-nocomment' => '$1 na $2',
'history-feed-empty'          => 'Pominany bok njeeksistěrujo.
Snaź jo se z wiki wulašował abo hynac pómjenił.
[[Special:Search|Pśepytaj]] {{SITENAME}} za relewantnymi bokami.',

# Revision deletion
'rev-deleted-comment'         => '(Zespominanje wulašowane)',
'rev-deleted-user'            => '(Wužywarske mě wulašowane)',
'rev-deleted-event'           => '(protokolowa akcija wulašowana)',
'rev-deleted-user-contribs'   => '[wužywarske mě wótpórane abo IP-adresa wótpórana - změna mjez pśinoskami schowana]',
'rev-deleted-text-permission' => "Toś ta wersija boka jo se '''wulašowała'''. Ewentuelne su drobnostki w [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} protokolu wulašowanjow].",
'rev-deleted-text-unhide'     => "Toś ta wersija boka jo se '''wulašowała'''.
Glědaj drobnostki w [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} protokolu wulašowanjow].
Móžoš se hyšći [$1 toś tu wersiju woglědaś], jolic coš pókšacowaś.",
'rev-suppressed-text-unhide'  => "Toś ta wersija boka jo se '''pódtłocyła'''.
Glědaj drobnostki w [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} protokolu pódtłocenjow].
Móžoš se hyšći [$1 toś tu wersiju woglědaś], jolic coš pókšacowaś.",
'rev-deleted-text-view'       => "Toś ta wersija boka jo se '''wulašowała'''.
Móžoš se ju woglědaś; glědaj drobnostki w [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} protokolu wulašowanjow].",
'rev-suppressed-text-view'    => "Toś ta wersija boka jo se '''pódtłocyła'''.
Móžoš se ju woglědaś; glědaj drobnostki w [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} protokolu pódtłocenjow].",
'rev-deleted-no-diff'         => "Njamóžoš se toś ten rozdźěl woglědaś, dokulaž jadna z wersijow jo se '''wulašowała'''.
Glědaj drobnostki w [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} protokolu wulašowanjow].",
'rev-suppressed-no-diff'      => "Njamóžoš se toś ten rozdźěl woglědaś, dokulaž jadna z wersijow jo se '''wulašowała'''.",
'rev-deleted-unhide-diff'     => "Jadna z wersijow toś togo rozdźěla jo se '''wulašowała'''.
Glědaj drobnostki w [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} protokolu wulašowanjow].
Móžoš se hyšći [$1 toś ten rozdźěl woglědaś], jolic coš pókšacowaś.",
'rev-suppressed-unhide-diff'  => "Jadna z wersijow twójogo rozdźěla jo se '''pódtłócyła'''.
Za drobnostki glědaj [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} protokol pódtłocenjow].
Móžoš se hyšći [$1 toś ten rozdźěl woglědaś], jolic coš pókšacowaś.",
'rev-deleted-diff-view'       => "Jadna z wersijow toś togo rozdźěla jo se '''wulašowała'''.
Móžoš se toś ten rozdźěl woglědaś; drobnostki glědaj w [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} protokolu wulašowanjow].",
'rev-suppressed-diff-view'    => "Jadna z wersijow toś togo rozdźěla jo se '''pódtłocyła'''.
Móžoš se toś ten rozdźěl woglědaś; drobnostki glědaj w [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} protokolu pódtłocenjow].",
'rev-delundel'                => 'pokazaś/schowaś',
'rev-showdeleted'             => 'pokazaś',
'revisiondelete'              => 'Wersije wulašowaś/wótnowiś',
'revdelete-nooldid-title'     => 'Njepłaśiwa celowa wersija',
'revdelete-nooldid-text'      => 'Njejsy pak žednu celowu wersiju pódał, aby se toś ta funkcija wuwjadła, pódana funkcija njeeksistěrujo pak wopytujoš aktualnu wersiju chowaś.',
'revdelete-nologtype-title'   => 'Žeden protokolowy typ pódany',
'revdelete-nologtype-text'    => 'Njejsy pódał protokolowy typ, aby wuwjadł toś tu akciju.',
'revdelete-nologid-title'     => 'Njepłaśiwy protokolowy zapisk',
'revdelete-nologid-text'      => 'Pak njejsy pódał celowe protokolowe tšojenje, aby wuwjadł toś tu funkciju pak pódany zapisk njeeksistěrujo.',
'revdelete-no-file'           => 'Pódana dataja njeeksistěrujo.',
'revdelete-show-file-confirm' => 'Coš se napšawdu wulašowanu wersiju dataje "<nowiki>$1</nowiki>" wót $2 $3 woglědaś?',
'revdelete-show-file-submit'  => 'Jo',
'revdelete-selected'          => "'''{{PLURAL:$2|Wuzwólona wersija|Wuzwólonej wersiji|Wuzwólone wersije}} wót [[:$1]].'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Wuzwólony protokolowe tšojenje|Wuzwólonej protokolowe tšojeni|wuzwólone protokolowe tšojenja}}:'''",
'revdelete-text'              => "'''Wulašowane wersije a tšojenja budu se dalej w stawiznach boka a w protokolach pokazaś, ale źěle jich wopśimjeśa njebudu pśistupne za zjawnosć.'''
Dalšne administratory na {{GRAMMAR:lokatiw|{{SITENAME}}}} mógu ale pśecej hyšći pśistup na schowane wopśimjeśe měś a mógu jo pśez samki interfejs wótnowiś,  snaźkuli su pśidatne wobgranicowanja.",
'revdelete-confirm'           => 'Pšosym wobkšuś, až coš to cyniś, až rozmějoš konsekwence a až cyniš to pó [[{{MediaWiki:Policy-url}}|pšawidłach]].',
'revdelete-suppress-text'     => "Pódtłocenje by se dejał '''jano''' za slědujuce pady wužywaś:
* Njegóźece se wósobinske informacije
*: ''bydleńske adrese a telefonowe numery, numery socialnego zawěsćenja atd.''",
'revdelete-legend'            => 'wobgranicowanja widobnosći póstajiś',
'revdelete-hide-text'         => 'Tekst wersije schowaś',
'revdelete-hide-image'        => 'Wopśimjeśe dataje schowaś',
'revdelete-hide-name'         => 'Akciju log-lisćiny schowaś',
'revdelete-hide-comment'      => 'Komentar wobźěłanja schowaś',
'revdelete-hide-user'         => 'mě/IP-adresu wobźěłarja schowaś',
'revdelete-hide-restricted'   => 'Daty wót administratorow ako teke te drugich wužywarjow pódtłocyś',
'revdelete-radio-same'        => '(njezměniś)',
'revdelete-radio-set'         => 'Jo',
'revdelete-radio-unset'       => 'Ně',
'revdelete-suppress'          => 'Pśicynu wulašowanja teke za administratorow schowaś',
'revdelete-unsuppress'        => 'Wobgranicowanja za wótnowjone wersije zasej zwignuś.',
'revdelete-log'               => 'Pśicyna:',
'revdelete-submit'            => 'Na {{PLURAL:$1|wubranu wersiju|wubranej wersiji|wubrane wersije|wubrane wersije}} nałožyś',
'revdelete-logentry'          => 'Woglědanje wersije změnjone za [[$1]]',
'logdelete-logentry'          => 'wiźobnosć za [[$1]] změnjona.',
'revdelete-success'           => "'''Widobnosć wersije jo se z wuspěchom zaktualizěrowała.'''",
'revdelete-failure'           => "'''Wersijowa widobnosć njedajo se aktualizěrowaś:'''
$1",
'logdelete-success'           => "'''Wiźobnosć log-lisćiny z wuspěchom změnjona.'''",
'logdelete-failure'           => "'''Protokolowa wiźobnosć njejo se dała nastajiś:'''
$1",
'revdel-restore'              => 'Widobnosć změniś',
'revdel-restore-deleted'      => 'wulašowane wersije',
'revdel-restore-visible'      => 'widobne wersije',
'pagehist'                    => 'stawizny boka',
'deletedhist'                 => 'wulašowane stawizny',
'revdelete-content'           => 'wopśimjeśe',
'revdelete-summary'           => 'Zespominanje wobźěłanja',
'revdelete-uname'             => 'wužywarske mě',
'revdelete-restricted'        => 'Wobgranicowanja se teke na administratorow nałožuju',
'revdelete-unrestricted'      => 'Wobgranicowanja za administratorow wótpórane',
'revdelete-hid'               => 'schowa $1',
'revdelete-unhid'             => 'zasej wótkšy $1',
'revdelete-log-message'       => '$1 za $2 {{PLURAL:$2|wersiju|wersiji|wersije|wersijow}}',
'logdelete-log-message'       => '$1 za $2 {{PLURAL:$2|tšojenje|tšojeni|tšojenja|tšojenjow}}',
'revdelete-hide-current'      => 'Zmólka pśi chowanju zapiska wót $2, $1: to jo aktualna wersija.
Njedajo se schowaś.',
'revdelete-show-no-access'    => 'Zmólka pśi pokazowanju zapiska wót $2, $1: toś ten zapisk jo se ako "wobgranicowany" markěrował.
Njamaš pśistup na njen.',
'revdelete-modify-no-access'  => 'Zmólka pśi změnjanju zapiska wót $2, $1: toś ten zapisk jo se ako "wobgranicowany" markěrował.
Njamaš pśistup na njen.',
'revdelete-modify-missing'    => 'Zmólka pśi změnjanju zapiska ID $1: felujo w datowej bance!',
'revdelete-no-change'         => "'''Warnowanje:''' zapisk wót $2, $1 jo južo měł pominane nastajenja wiźobnosći.",
'revdelete-concurrent-change' => 'Zmólka pśi změnjanju zapiska wót $2, $1: zda se, až jogo status jo se změnił wót někogo drugego, mjaztym až sy wopytał jen změniś.
Pšosym pśeglědaj protokole.',
'revdelete-only-restricted'   => 'Zmólka pśi chowanju zapiska wót $2, $1; njamóžoš zapiski pśed wócami administratorow  pódtłocyś, mimo až teke wuběraš jadnu z drugich wiźobnosćowych opcijow.',
'revdelete-reason-dropdown'   => '*Zwucone pśicyny za wulašowanje
** Pśestupjenje awtorskego pšawa
** Njegóźece se wósobinske informacije',
'revdelete-otherreason'       => 'Druga/pśidatna pśicyna:',
'revdelete-reasonotherlist'   => 'Druga pśicyna',
'revdelete-edit-reasonlist'   => 'Pśicyny za lašowanje wobźěłaś',
'revdelete-offender'          => 'Awtor wersije:',

# Suppression log
'suppressionlog'     => 'Protokol pódłocowanjow',
'suppressionlogtext' => 'To jo lisćina wulašowanjow a blokěrowanjow, kótaraž ma wopśimjeśe, kótarež jo za administratorow schowane. Glědaj  [[Special:IPBlockList|lisćinu blokěrowanjow IP]] za lisćinu aktualnych wugnanjow a blokěrowanjow.',

# History merging
'mergehistory'                     => 'Zwězaś stawizny bokow',
'mergehistory-header'              => 'Z toś tym bokom móžoš historiju wersijow žrědłowego boka z tej celowego boka zjadnośiś.
Zaruc, až historija wersijow nastawka jo njepśetergnjona.',
'mergehistory-box'                 => 'Zwězaś wersjiowu toś teju bokowo:',
'mergehistory-from'                => 'Žrědłowy bok:',
'mergehistory-into'                => 'Celowy bok:',
'mergehistory-list'                => 'Wersije, kótarež móžoš zjadnośiś',
'mergehistory-merge'               => 'Slědujuce wersije wót [[:$1]] móžoš z [[:$2]] zjadnośiś. Markěruj wersiju, až k tej se wersije deje pśenjasć. Glědaj pšosym, až wužywanje nawigaciskich wótkazow wuběrk slědk stajijo.',
'mergehistory-go'                  => 'Wersije, kótarež daju se zjadnośiś, pokazaś',
'mergehistory-submit'              => 'Wersije zjadnośiś',
'mergehistory-empty'               => 'Njadaju se žedne wersije zjadnośiś.',
'mergehistory-success'             => '$3 {{PLURAL:$3|wersija|wersiji|wersije|wersijow}} wót [[:$1]] wuspěšnje do [[:$2]] {{PLURAL:$3|zjadnośona|zjadnośonej|zjadnośone|zjadnośone}}.',
'mergehistory-fail'                => 'Njemóžno stawizny zjadnośiś, pśeglědaj pšosym bok a casowe parametry.',
'mergehistory-no-source'           => 'Žrědłowy bok $1 njeeksistěrujo.',
'mergehistory-no-destination'      => 'Celowy bok $1 njeeksistěruje.',
'mergehistory-invalid-source'      => 'Žrědłowy bok musy měś dobre nadpismo.',
'mergehistory-invalid-destination' => 'Celowy bok musy měś dobre nadpismo.',
'mergehistory-autocomment'         => '„[[:$1]]“ do „[[:$2]]“ zjadnośeny',
'mergehistory-comment'             => '„[[:$1]]“ do „[[:$2]]“ zjadnośeny: $3',
'mergehistory-same-destination'    => 'Žrědłowy bok a celowy bok njesmějotej identiskej byś',
'mergehistory-reason'              => 'Pśicyna:',

# Merge log
'mergelog'           => 'Protokol zjadnośenja',
'pagemerge-logentry' => 'zjadnośi [[$1]] do [[$2]] (Wersije až do $3)',
'revertmerge'        => 'Zjadnośenje anulěrowaś',
'mergelogpagetext'   => 'Dołojce jo lisćina nejnowejšych zjadnośenjow historije boka z drugej.',

# Diffs
'history-title'            => 'Stawizny wersijow boka „$1“',
'difference'               => '(rozdźěle mjazy wersijoma/wersijami)',
'difference-multipage'     => '(Rozdźěl mjazy bokami)',
'lineno'                   => 'Rědka $1:',
'compareselectedversions'  => 'Wuzwólonej wersiji pśirownaś',
'showhideselectedversions' => 'Wubrane wersije pokazaś/schowaś',
'editundo'                 => 'wótwrośiś',
'diff-multi'               => '({{PLURAL:$1|Jadna mjazywersija|$1 mjazywersiji|$1 mjazywersije|$1 mjazywersijow}} wót {{PLURAL:$2|jadnogo wužywarja|$2 wužywarjowu|$2 wužywarjow|$2 wužywarjow}} {{PLURAL:$1|njepokazana|njepokazanej|njepokazane|njepokazane}})',
'diff-multi-manyusers'     => '({{PLURAL:$1|Jadna mjazywersija|$1 mjazywersiji|$1 mjazywersije|$1 mjazywersijow}} wót wěcej ako {{PLURAL:$2|jadnogo wužywarja|$2 wužywarjowu|$2 wužywarjow|$2 wužywarjow}} {{PLURAL:$1|njepokazana|njepokazanej|njepokazane|njepokazane}})',

# Search results
'searchresults'                    => 'Wuslědki pytanja',
'searchresults-title'              => 'Pytańske wuslědki za "$1"',
'searchresulttext'                 => 'Za wěcej informacijow wó pśepytowanju {{GRAMMAR:genitiw|{{SITENAME}}}} glědaj [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'Sy pytał za \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|wše boki, kótarež zachopiju se z "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|wše wótkaze, kótarež wótkazuju do "$1"]])',
'searchsubtitleinvalid'            => 'Ty sy pytał „$1“.',
'toomanymatches'                   => 'Pśewjele pytańskich wuslědkow, pšosym wopytaj druge wótpšašanje.',
'titlematches'                     => 'boki z wótpowědujucym napismom',
'notitlematches'                   => 'Boki z wótpowědujucym napismom njeeksistěruju.',
'textmatches'                      => 'Boki z wótpowědujucym tekstom',
'notextmatches'                    => 'Boki z wótpowědujucym tekstom njeeksistěruju.',
'prevn'                            => '{{PLURAL:$1|zachadny $1|zachadnej $1|zachadne $1|zachadnych $1}}',
'nextn'                            => '{{PLURAL:$1|pśiducy $1|pśiducej $1|pśiduce $1|pśiducych $1}}',
'prevn-title'                      => '{{PLURAL:$1|Pjerwjejšny wuslědk|Pjerwjejšnej $1 wuslědka|Pjerwjejšne $1 wuslědki|Pjerwjejšnych $1 wuslědkow}}',
'nextn-title'                      => '{{PLURAL:$1|Pśiducy wuslědk|Pśiducej $1 wuslědka|Pśiduce $1 wuslědki|Pśiducych $1 wuslědkow}}',
'shown-title'                      => '$1 {{PLURAL:$1|wuslědk|wuslědka|wuslědki|wuslědkow}} na bok pokazaś',
'viewprevnext'                     => '($1 {{int:pipe-separator}} $2) ($3) pokazaś',
'searchmenu-legend'                => 'Pytańske opcije',
'searchmenu-exists'                => "'''Jo bok z mjenim \"[[\$1]]\" na toś tom wikiju'''",
'searchmenu-new'                   => "'''Napóraj bok \"[[:\$1|\$1]]\" na toś tom wikiju!'''",
'searchhelp-url'                   => 'Help:Pomoc',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Boki z toś tym prefiksom pśepytaś]]',
'searchprofile-articles'           => 'Wopśimjeśowe boki',
'searchprofile-project'            => 'Pomoc a projektowe boki',
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Wšykno',
'searchprofile-advanced'           => 'Rozšyrjony',
'searchprofile-articles-tooltip'   => 'W $1 pytaś',
'searchprofile-project-tooltip'    => 'W $1 pytaś',
'searchprofile-images-tooltip'     => 'Za datajami pytaś',
'searchprofile-everything-tooltip' => 'Cełe wopsímjeśe pśepytaś (inkluziwnje diskusijne boki)',
'searchprofile-advanced-tooltip'   => 'W swójskich mjenjowych rumach pytaś',
'search-result-size'               => '$1 ({{PLURAL:$2|1 słowow|$2 słowje|$2 słowa|$2 słowow}})',
'search-result-category-size'      => '{{PLURAL:$1|1 cłonk|$1 cłonka|$1 cłonki|$1 cłonkow}} ({{PLURAL:$2|1 pódkategorija|$2 pódkategoriji|$2 pódkategorije|$2 pódkategorijow}}, {{PLURAL:$3|1 dataja|$3 dataji|$3 dataje|$3 datajow}})',
'search-result-score'              => 'Relewanca: $1 %',
'search-redirect'                  => '(pśesměrowanje $1)',
'search-section'                   => '(sekcija $1)',
'search-suggest'                   => 'Měnjašo $1?',
'search-interwiki-caption'         => 'Sotšine projekty',
'search-interwiki-default'         => '$1 wuslědki:',
'search-interwiki-more'            => '(wěcej)',
'search-mwsuggest-enabled'         => 'z naraźenjami',
'search-mwsuggest-disabled'        => 'žedne naraźenja',
'search-relatedarticle'            => 'swójźbne',
'mwsuggest-disable'                => 'Naraźenja pśez AJAX znjemóžniś',
'searcheverything-enable'          => 'We wšych mjenjowych rumach pytaś',
'searchrelated'                    => 'swójźbne',
'searchall'                        => 'wše',
'showingresults'                   => "How {{PLURAL:|jo '''1''' wuslědk|stej '''$1''' wuslědka|su '''$1''' wuslědki}} wót cysła '''$2'''.",
'showingresultsnum'                => "How {{PLURAL:$3|jo '''1''' wuslědk|stej '''$3''' wuslědka|su '''$3''' wuslědki}} wót cysła '''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Wuslědk '''$1''' z '''$3'''|Wuslědki '''$1 - $2''' z '''$3'''}} za '''$4'''",
'nonefound'                        => "'''Pokazka''': Jano někótare mjenjowe rumy se standarnje pytaju. Wopytaj za swóje wótpšašanje prefiks ''all:'' wužywać, aby cełe wopśimjeśe pytał (inkluziwnje diskusijnych bokow, pśedłogi atd.) abo wužyj póžedany mjenjowy rum ako prefiks.",
'search-nonefound'                 => 'Njejsu se wuslědki namakali, kótarež wótpowěduju napšašowanjeju.',
'powersearch'                      => 'Rozšyrjone pytanje',
'powersearch-legend'               => 'Rozšyrjone pytanje',
'powersearch-ns'                   => 'W mjenjowych rumach pytaś:',
'powersearch-redir'                => 'Dalejpósrědnjenja nalistowaś',
'powersearch-field'                => 'Pytaś za:',
'powersearch-togglelabel'          => 'Kontrolěrowaś:',
'powersearch-toggleall'            => 'Wše',
'powersearch-togglenone'           => 'Žeden',
'search-external'                  => 'Eksterne pytanje',
'searchdisabled'                   => 'Pytanje we {{SITENAME}} jo se deaktiwěrowało. Tak dłujko móžoš w googlu pytaś. Pšosym wobmysli, až móžo pytanski indeks za {{SITENAME}} njeaktualny byś.',

# Quickbar
'qbsettings'               => 'Bocna lejstwa',
'qbsettings-none'          => 'Žedne',
'qbsettings-fixedleft'     => 'nalěwo fiksěrowane',
'qbsettings-fixedright'    => 'napšawo fiksěrowane',
'qbsettings-floatingleft'  => 'nalěwo se znosujuce',
'qbsettings-floatingright' => 'napšawo se znosujuce',

# Preferences page
'preferences'                   => 'Nastajenja',
'mypreferences'                 => 'nastajenja',
'prefs-edits'                   => 'Licba wobźěłanjow:',
'prefsnologin'                  => 'Njejsy pśizjawjony',
'prefsnologintext'              => 'Musyš se <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} pśizjawiś]</span>, aby mógał swóje nastajenja změniś.',
'changepassword'                => 'Šćitne gronidło změniś',
'prefs-skin'                    => 'Šat',
'skin-preview'                  => 'Pśeglěd',
'prefs-math'                    => 'Math',
'datedefault'                   => 'Standard',
'prefs-datetime'                => 'Datum a cas',
'prefs-personal'                => 'Wužywarski profil',
'prefs-rc'                      => 'Aktualne změny',
'prefs-watchlist'               => 'Wobglědowańka',
'prefs-watchlist-days'          => 'Licba dnjow, kenž maju se we wobglědowańce pokazaś:',
'prefs-watchlist-days-max'      => 'Maksimalnje 7 dnjow',
'prefs-watchlist-edits'         => 'Maksimalna licba změnow, kótarež maju se w rozšyrjonej wobglědowańce pokazaś:',
'prefs-watchlist-edits-max'     => 'Maksimalna licba: 1000',
'prefs-watchlist-token'         => 'Marka wobglědowańki:',
'prefs-misc'                    => 'Wšake nastajenja',
'prefs-resetpass'               => 'Gronidło změniś',
'prefs-email'                   => 'E-mailowe opcije',
'prefs-rendering'               => 'Naglěd',
'saveprefs'                     => 'Składowaś',
'resetprefs'                    => 'Njeskłaźone změny zachyśiś',
'restoreprefs'                  => 'Wše standardne nastajenja wobnowiś',
'prefs-editing'                 => 'Wobźěłaś',
'prefs-edit-boxsize'            => 'Wjelikosć wobźěłowańskego wokna',
'rows'                          => 'Rědki:',
'columns'                       => 'Słupy:',
'searchresultshead'             => 'Pytaś',
'resultsperpage'                => 'Wuslědki na bok:',
'contextlines'                  => 'Rědki na wuslědk:',
'contextchars'                  => 'Znamuška na rědku:',
'stub-threshold'                => 'Formatěrowanje  <a href="#" class="stub">wótkaza na zarodk</a> (w bytach):',
'stub-threshold-disabled'       => 'Znjemóžnjony',
'recentchangesdays'             => 'Licba dnjow, kenž se pokazuju w "slědnych změnach":',
'recentchangesdays-max'         => '(maksimalnje $1 {{PLURAL:$1|źeń|dnja|dny|dnjow}})',
'recentchangescount'            => 'Licba změnow, kótaraž ma se pó standarźe pokazaś:',
'prefs-help-recentchangescount' => 'To wopśimujo aktualne změny, stawizny bokow a protokole.',
'prefs-help-watchlist-token'    => 'Wupołnjenje toś togo póla z pótajmnym klucom buźo RSS-kanal za twóju wobglědowańku napóraś.
Něchten, kenž znajo kluc w toś tom pólu, móžo twóju wobglědowańku cytaś, wubjeŕ togodla wěstu gódnotu.
How jo pśipadnje napórana gódnota, kótaruž móžoš wužywaś: $1',
'savedprefs'                    => 'Twóje nastajenja su se składowali.',
'timezonelegend'                => 'Casowa cona:',
'localtime'                     => 'Městny cas:',
'timezoneuseserverdefault'      => 'Standard serwera wužywaś',
'timezoneuseoffset'             => 'Drugi (pódaj wótchylenje)',
'timezoneoffset'                => 'Rozdźěl¹:',
'servertime'                    => 'Cas serwera:',
'guesstimezone'                 => 'Z browsera pśewześ',
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
'allowemail'                    => 'Dostawanje e-mailow drugich wužywarjow zmóžniś.',
'prefs-searchoptions'           => 'Pytańske opcije',
'prefs-namespaces'              => 'Mjenjowe rumy',
'defaultns'                     => 'Howac w toś tych mjenjowych rumach pytaś:',
'default'                       => 'Standard',
'prefs-files'                   => 'Dataje',
'prefs-custom-css'              => 'Swójski CSS',
'prefs-custom-js'               => 'Swójski JS',
'prefs-common-css-js'           => 'Zgromadny CSS/JS za wšykne suknje:',
'prefs-reset-intro'             => 'You can use this page to reset your preferences to the site defaults. This cannot be undone.
Móžoš toś ten bok wužywaś, aby slědk stajił swóje nastajenja na standardne gódnoty sedła. To njedajo se anulěrowaś.',
'prefs-emailconfirm-label'      => 'E-mailowe wobkšuśenje:',
'prefs-textboxsize'             => 'Wjelikosć wobźěłowańskego wokna',
'youremail'                     => 'E-mail:',
'username'                      => 'Wužywarske mě:',
'uid'                           => 'ID wužywarja:',
'prefs-memberingroups'          => 'Cłonk {{PLURAL:$1|wužywarskeje skupiny|wužywarskeju kupkowu|wužywarskich kupkow|wužiwarskich kupkow}}:',
'prefs-registration'            => 'Cas registracije:',
'yourrealname'                  => 'Realne mě *:',
'yourlanguage'                  => 'Rěc:',
'yourvariant'                   => 'Rěcna warianta:',
'yournick'                      => 'Pódpismo:',
'prefs-help-signature'          => 'Komentary na diskusijnych bokach měli se pśez "<nowiki>~~~~</nowiki>" pódpisaś, kótarež konwertěrujo se do twójeje signatury a casowego kołka.',
'badsig'                        => 'Signatura njejo dobra; pšosym HTML pśekontrolěrowaś.',
'badsiglength'                  => 'Twója signatura jo pśedłujka. Musy mjenjej ako $1 {{PLURAL:$1|znamješko|znamješce|znamješka|znamješkow}} měś.',
'yourgender'                    => 'Rod:',
'gender-unknown'                => 'Njepódany',
'gender-male'                   => 'Muskecy',
'gender-female'                 => 'Žeńscyny',
'prefs-help-gender'             => 'Opcionalny: wužywa se za pó roźe specifiske nagronjenje pśez softwaru. Toś ta informacija buźo zjawna.',
'email'                         => 'E-mail',
'prefs-help-realname'           => 'Realne mě jo opcionalne. Jolic až jo zapódajośo wužywa se za pódpisanje wašych pśinoskow.',
'prefs-help-email'              => 'E-mailowa adresa jo opcionalna, ale zmóžnja śi nowe gronidło emailowaś, jolic sy zabył swójo gronidło.  Móžoš teke drugim dowóliś se z tobu stajiś do zwiska pśez waš wužywarski abo diskusijny bok, bźez togo až dejš wótekšyś swóju identitu.',
'prefs-help-email-required'     => 'E-mailowa adresa trjebna.',
'prefs-info'                    => 'Zakładne informacije',
'prefs-i18n'                    => 'Internacionalizacija',
'prefs-signature'               => 'Pódpis',
'prefs-dateformat'              => 'Datumowy format',
'prefs-timeoffset'              => 'Casowy rozdźěl',
'prefs-advancedediting'         => 'Rozšyrjone opcije',
'prefs-advancedrc'              => 'Rozšyrjone opcije',
'prefs-advancedrendering'       => 'Rozšyrjone opcije',
'prefs-advancedsearchoptions'   => 'Rozšyrjone opcije',
'prefs-advancedwatchlist'       => 'Rozšyrjone opcije',
'prefs-displayrc'               => 'Zwobraznjowańske opcije',
'prefs-displaysearchoptions'    => 'Zwobraznjowańske opcije',
'prefs-displaywatchlist'        => 'Zwobraznjowańske opcije',
'prefs-diffs'                   => 'Rozdźěle',

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => 'Zda se, až e-mailowa adresa jo płaśiwa',
'email-address-validity-invalid' => 'Zapódaj płaśiwu e-mailowu adresu',

# User rights
'userrights'                   => 'Zastojanje wužywarskich pšawow',
'userrights-lookup-user'       => 'Wužywarske kupki zastojaś',
'userrights-user-editname'     => 'Wužywarske mě:',
'editusergroup'                => 'Wužywarske kupki wobźěłaś.',
'editinguser'                  => "Změnjaju se wužywarske pšawa wužywarja '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'Pšawa wužywarskich kupkow wobźěłaś',
'saveusergroups'               => 'Wužywarske kupki składowaś',
'userrights-groupsmember'      => 'Cłonk kupki:',
'userrights-groupsmember-auto' => 'Implicitny cłonk wót:',
'userrights-groups-help'       => 'Móžoš kupki, w kótarychž wužywaŕ jo, změniś:
* Markěrowany kašćik wóznamjenijo, až wužywaŕ jo w toś tej kupce.
* Njemarkěrowany kašćik woznamjenijo, až wužywaŕ njejo w toś tej kupce.
* * pódawa, až njamóžoš kupku wótwónoźeś, gaž sy ju pśidał abo nawopak.',
'userrights-reason'            => 'Pśicyna:',
'userrights-no-interwiki'      => 'Njamaš pšawo wužywarske pšawa w drugich wikijach změniś.',
'userrights-nodatabase'        => 'Datowa banka $1 njeeksistěrujo abo njejo lokalna.',
'userrights-nologin'           => 'Musyš se z administratorowym kontom [[Special:UserLogin|pśizjawiś]], aby wužywarske pšawa změnił.',
'userrights-notallowed'        => 'Twóje konto njama pšawa, aby wužywarske pšawa pśidało abo wótpórało.',
'userrights-changeable-col'    => 'Kupki, kótarež móžoš změniś',
'userrights-unchangeable-col'  => 'Kupki, kótarež njamóžoš změniś',

# Groups
'group'               => 'Kupka:',
'group-user'          => 'wužywarje',
'group-autoconfirmed' => 'wobkšuśone wužywarje',
'group-bot'           => 'awtomatiske programy (boty)',
'group-sysop'         => 'administratory',
'group-bureaucrat'    => 'běrokraty',
'group-suppress'      => 'Doglědowanja',
'group-all'           => '(wše)',

'group-user-member'          => 'Wužywaŕ',
'group-autoconfirmed-member' => 'Wobkšuśony wužywaŕ',
'group-bot-member'           => 'awtomatiski program (bot)',
'group-sysop-member'         => 'administrator',
'group-bureaucrat-member'    => 'Běrokrat',
'group-suppress-member'      => 'Doglědowanje',

'grouppage-user'          => '{{ns:project}}:Wužywarje',
'grouppage-autoconfirmed' => '{{ns:project}}:Awtomatiski wobkšuśone wužywarje',
'grouppage-bot'           => '{{ns:project}}:awtomatiske programy (boty)',
'grouppage-sysop'         => '{{ns:project}}:Administratory',
'grouppage-bureaucrat'    => '{{ns:project}}:Běrokraty',
'grouppage-suppress'      => '{{ns:project}}:Doglědowanje',

# Rights
'right-read'                  => 'cytaś boki',
'right-edit'                  => 'wobźěłaś boki',
'right-createpage'            => 'Boki napóraś (mimo diskusijnych bokow)',
'right-createtalk'            => 'natwóriś diskusijny bok',
'right-createaccount'         => 'Nowe wužywarske konta załožyś',
'right-minoredit'             => 'Změny ako snadne markěrowaś',
'right-move'                  => 'pśesunuś boki',
'right-move-subpages'         => 'Boki ze swójimi pódbokami pśesunuś',
'right-move-rootuserpages'    => 'Głowne wužywarske boki pśesunuś',
'right-movefile'              => 'Dataje pśesunuś',
'right-suppressredirect'      => 'Pśi pśesunjenju žedno dalejpósrědnjenje ze starego mjenja napóraś',
'right-upload'                => 'Dataje nagraś',
'right-reupload'              => 'Eksistěrujucu dataju pśepisaś',
'right-reupload-own'          => 'Dataju nagratu wót togo samogo wužywarja pśepisaś',
'right-reupload-shared'       => 'Dataje w zgromadnje wužywanem repozitoriju lokalnje pśepisaś',
'right-upload_by_url'         => 'Dataju z URL-adrese nagraś',
'right-purge'                 => 'Sedłowy cache za bok bźez wobkšuśenja prozniś',
'right-autoconfirmed'         => 'Połšćitane boki wobźěłaś',
'right-bot'                   => 'Wobchadanje ako awtomatiski proces',
'right-nominornewtalk'        => 'Snadne změny na diskusijnych bokach njedowjedu k pokazanjeju "Nowe powěsći"',
'right-apihighlimits'         => 'Wuše limity w API-wótpšašanjach wužywaś',
'right-writeapi'              => 'writeAPI wužywaś',
'right-delete'                => 'Boki wulašowaś',
'right-bigdelete'             => 'lašowaś boki, kótarež maju wjelike stawizny',
'right-deleterevision'        => 'Specifiske boki lašowaś a wótnowiś',
'right-deletedhistory'        => 'Wulašowane wersiji w stawiznach se bśez pśisłušnego teksta wobglědaś',
'right-deletedtext'           => 'Wulašowany tekst a změny mjazy wulašowanymi wersijami se woglědaś',
'right-browsearchive'         => 'Wulašowane boki pytaś',
'right-undelete'              => 'Bok wótnowiś',
'right-suppressrevision'      => 'Wersije, kótarež su pśed admibnistratorami schowane, pśeglědaś a wótnowiś',
'right-suppressionlog'        => 'Priwatne protokole se wobglědowaś',
'right-block'                 => 'Drugim wužywarjam wobźěłowanje zawoboraś',
'right-blockemail'            => 'Wužywarjoju słanje emailow zawoboraś',
'right-hideuser'              => 'Wužywarske mě blokěrowaś a schowaś',
'right-ipblock-exempt'        => 'Blokěrowanja IP, awtomatiske blokěrowanja a blokěrowanja wobcerkow se wobinuś',
'right-proxyunbannable'       => 'Awtomatiske blokěrowanje proksyjow se wobinuś',
'right-unblockself'           => 'Wótblokěrowaś se samogo',
'right-protect'               => 'Šćitowe schójźeńki změniś a šćitane boki wobźěłaś',
'right-editprotected'         => 'Šćitane boki wobźěłaś (bśez kaskadowego šćita)',
'right-editinterface'         => 'Wužywański pówjerch wobźěłaś',
'right-editusercssjs'         => 'Dataje CSS a JS drugich wužywarjow wobźěłaś',
'right-editusercss'           => 'Dataje CSS drugich wužywarjow wobźěłaś',
'right-edituserjs'            => 'Dataje JS drugich wužywarjow wobźěłaś',
'right-rollback'              => 'Spěšne anulěrowanje změnow slědnego wužywarja, kótaryž jo dany bok wobźěłał',
'right-markbotedits'          => 'Spěšnje anulěrowane změny ako botowe změny markěrowaś',
'right-noratelimit'           => 'Pśez žedne limity wobgranicowany',
'right-import'                => 'Boki z drugich wikijow importowaś',
'right-importupload'          => 'Boki pśez nagraśe datajow importowaś',
'right-patrol'                => 'Změny ako doglědowane markěrowaś',
'right-autopatrol'            => 'Swójske změny awtomatiski ako doglědowane markěrowane',
'right-patrolmarks'           => 'Kontrolne wobznamjenja w aktualnych změnach',
'right-unwatchedpages'        => 'Lisćinu njewobglědowanych bokow woglědaś',
'right-trackback'             => 'Trackback wótpósłaś',
'right-mergehistory'          => 'Stawizny wersijow bokow zjadnośiś',
'right-userrights'            => 'Wšykne wužywarske pšawa wobźěłaś',
'right-userrights-interwiki'  => 'Wužywarske pšawa w drugich wikijach wobźěłaś',
'right-siteadmin'             => 'Datowu banku zastajiś a zastajenje wótpóraś',
'right-reset-passwords'       => 'Gronidła drugich wužywarjow slědk stajiś',
'right-override-export-depth' => 'Boki inkluziwnje wótkazanych bokow až do dłyma 5 eksportěrowaś',
'right-sendemail'             => 'Drugim wužywarjam e-mail pósłaś',

# User rights log
'rightslog'      => 'Protokol wužywarskich pšawow',
'rightslogtext'  => 'To jo protokol wužywarskich pšawow.',
'rightslogentry' => 'Pśisłušnosć ku kupce jo se za „$1“ změniła wót „$2“ na „$3“.',
'rightsnone'     => '(nic)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'toś ten bok cytaś',
'action-edit'                 => 'toś ten bok wobźěłaś',
'action-createpage'           => 'Boki napóraś',
'action-createtalk'           => 'Diskusijne boki napóraś',
'action-createaccount'        => 'Toś to wužywarske konto napóraś',
'action-minoredit'            => 'toś tu změnu ako snadnu markěrowaś',
'action-move'                 => 'toś ten bok pśesunuś',
'action-move-subpages'        => 'toś ten bok a jogo pódboki pśesunuś',
'action-move-rootuserpages'   => 'głowne wužywarske boki pśesunuś',
'action-movefile'             => 'Toś tu dataju pśesunuś',
'action-upload'               => 'toś tu dataju nagraś',
'action-reupload'             => 'toś tu eksistěrujucu dataju pśepisaś',
'action-reupload-shared'      => 'toś tu dataju w zgromadnem repozitoriumje pśepisaś',
'action-upload_by_url'        => 'toś tu dataju z webadrese (URL) nagraś',
'action-writeapi'             => 'API za pisanje wužywaś',
'action-delete'               => 'Toś ten bok lasowaś',
'action-deleterevision'       => 'Toś tu wersiju lašowaś',
'action-deletedhistory'       => 'Stawizny wulašowanjow toś togo boka zwobrazniś',
'action-browsearchive'        => 'wulašowane boki pytaś',
'action-undelete'             => 'Toś ten bok wótnowiś',
'action-suppressrevision'     => 'schowanu wersiju pśeglědaś a wótnowiś',
'action-suppressionlog'       => 'toś ten priwatny protokol zwobrazniś',
'action-block'                => 'Toś tomu wužiwarjeju wobźěłowanje zawoboraś',
'action-protect'              => 'Šćitowe stopnje za toś ten bok změniś',
'action-import'               => 'toś ten bok z drugego wikija importěrowaś',
'action-importupload'         => 'toś ten bok z datajowego nagraśa importěrowaś',
'action-patrol'               => 'změny drugich wužywarjow ako doglědowane markěrowaś',
'action-autopatrol'           => 'twóju změnu ako doglědowanu markěrowaś daś',
'action-unwatchedpages'       => 'lisćinu njewobglědowanych bokow zwobrazniś',
'action-trackback'            => 'trackback pósłaś',
'action-mergehistory'         => 'Stawizny toś togo boka zjadnośiś',
'action-userrights'           => 'wše wužywarske pšawa wobźěłaś',
'action-userrights-interwiki' => 'wužywarske pšawa wužywarjow w drugich wikijach wobźěłaś',
'action-siteadmin'            => 'datowu banku zastajiś abo wótworiś',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|změna|změnje|změny}}',
'recentchanges'                     => 'Aktualne změny',
'recentchanges-legend'              => 'Opcije aktualnych změnow',
'recentchangestext'                 => "How móžoš slědne změny we '''{{GRAMMAR:lokatiw|{{SITENAME}}}}''' slědowaś.",
'recentchanges-feed-description'    => 'Slěduj z toś tym zapódaśim nejaktualnjejše změny we {{GRAMMAR:lokatiw|{{SITENAME}}}}.',
'recentchanges-label-newpage'       => 'Toś ta změna jo nowy bok napórała.',
'recentchanges-label-minor'         => 'To jo snadna změna',
'recentchanges-label-bot'           => 'Toś ta změna jo se pśez bośik wuwjadła.',
'recentchanges-label-unpatrolled'   => 'Toś ta změna hyšći njejo se pśekontrolěrowała',
'rcnote'                            => "Dołojce {{PLURAL:$1|jo '''1''' změna|stej slědnej '''$1''' změnje|su slědne '''$1''' změny}} w {{PLURAL:$2|slědnem dnju|slědnyma '''$2''' dnjoma|slědnych '''$2''' dnjach}}, staw wót $4, $5.",
'rcnotefrom'                        => "Dołojce pokazuju se změny wót '''$2''' (maks. '''$1''' zapisow).",
'rclistfrom'                        => 'Nowe změny wót $1 pokazaś.',
'rcshowhideminor'                   => 'Snadne změny $1',
'rcshowhidebots'                    => 'awtomatiske programy (boty) $1',
'rcshowhideliu'                     => 'pśizjawjone wužywarje $1',
'rcshowhideanons'                   => 'anonymne wužywarje $1',
'rcshowhidepatr'                    => 'kontrolěrowane změny $1',
'rcshowhidemine'                    => 'móje pśinoski $1',
'rclinks'                           => 'Slědne $1 změny slědnych $2 dnjow pokazaś<br />$3',
'diff'                              => 'rozdźěl',
'hist'                              => 'wersije',
'hide'                              => 'schowaś',
'show'                              => 'pokazaś',
'minoreditletter'                   => 'S',
'newpageletter'                     => 'N',
'boteditletter'                     => 'B',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|wobglědowaŕ|wobglědowarja|wobglědowarje}}]',
'rc_categories'                     => 'Jano boki z kategorijow (źělone z pomocu „|“):',
'rc_categories_any'                 => 'wše',
'newsectionsummary'                 => 'Nowy wótrězk /* $1 */',
'rc-enhanced-expand'                => 'Drobnosći pokazaś (pomina se JavaScript)',
'rc-enhanced-hide'                  => 'Drobnosći schowaś',

# Recent changes linked
'recentchangeslinked'          => 'Změny w zwězanych bokach',
'recentchangeslinked-feed'     => 'Změny w zwězanych bokach',
'recentchangeslinked-toolbox'  => 'Změny w zwězanych bokach',
'recentchangeslinked-title'    => 'Změny na bokach, kótarež su z „$1“ zalinkowane',
'recentchangeslinked-noresult' => 'Zalinkowane boki njejsu we wuzwólonem casu se změnili.',
'recentchangeslinked-summary'  => "To jo lisćina slědnych změnow, kótarež buchu na wótkazanych bokach cynjone (resp. pśi wěstych kategorijach na cłonkach kategorije).
Boki na [[Special:Watchlist|wobglědowańce]] su '''tucne'''.",
'recentchangeslinked-page'     => 'mě boka:',
'recentchangeslinked-to'       => 'Změny pokazaś, kótarež město togo na dany bok wótkazuju.',

# Upload
'upload'                      => 'Dataju nagraś',
'uploadbtn'                   => 'Dataju nagraś',
'reuploaddesc'                => 'Nagraśe pśetergnuś a slědk k nagrawańskemu formularoju',
'upload-tryagain'             => 'Změnjone datajowe wopisanje wótpósłaś',
'uploadnologin'               => 'Njepśizjawjony',
'uploadnologintext'           => 'Dejš se [[Special:UserLogin|pśizjawiś]], aby mógał dataje nagraś.',
'upload_directory_missing'    => 'Nagrawański zapis ($1) felujo a njejo se pśez webserwer napóraś dał.',
'upload_directory_read_only'  => 'Nagrawański zapisk ($1) njedajo se pśez webserwer pisaś.',
'uploaderror'                 => 'Nagrawańska zmólka',
'upload-recreate-warning'     => "'''Warnowanje: Dataja z tym mjenim jo se wulašowała abo pśeunuła.'''

Protokola wulašowanjow a pśesunjenjow za toś ten bok stej how k twójej dispoziciji pódanej:",
'uploadtext'                  => "Wužyj toś ten formular za nagraśe nowych datajow.

Źi na [[Special:FileList|lisćinu nagratych datajow]], aby mógł južo nagrate dataje se wobglědaś abo pytaś, nagraśa protokolěruju se w [[Special:Log/upload|protokolu nagraśow]], wulašowanja w [[Special:Log/delete|protokolu wulašowanjow]].

Aby dataju do boka zapśimjeł, wužyj wótkaz slědujuceje formy
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Dataja.jpg]]</nowiki></tt>''', aby wužywał połnu wersiju dataje
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Dataja.png|200px|thumb|left|alternatiwny tekst]]</nowiki></tt>''', aby wužywał wobraz we wjelikosću 200 pikselow w kašćiku na lěwej kšomje z alternatiwnym tekstom ako wopisanje
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Dataja.ogg]]</nowiki></tt>''', aby direktnje na dataju wótkazował, bźez togo až dataja se zwobraznijo.",
'upload-permitted'            => 'Dowolone datajowe typy: $1.',
'upload-preferred'            => 'Preferěrowane datajowe typy: $1.',
'upload-prohibited'           => 'Njedowolone datajowe typy: $1.',
'uploadlog'                   => 'datajowy protokol',
'uploadlogpage'               => 'Datajowy protokol',
'uploadlogpagetext'           => 'Dołojce jo lisćina nejnowšych datajowych nagraśow.
Glědaj [[Special:NewFiles|galeriju nowych datajow]] za wizuelny pśeglěd.',
'filename'                    => 'Mě dataje',
'filedesc'                    => 'Zespominanje',
'fileuploadsummary'           => 'Zespominanje:',
'filereuploadsummary'         => 'Datajowe změny:',
'filestatus'                  => 'Status copyrighta:',
'filesource'                  => 'Žrědło:',
'uploadedfiles'               => 'Nagrate dataje',
'ignorewarning'               => 'Warnowanje ignorěrowaś a dataju składowaś',
'ignorewarnings'              => 'Wše warnowanja ignorěrowaś',
'minlength1'                  => 'Mjenja datajow muse wopśimjeś nanejmjenjej jaden pismik.',
'illegalfilename'             => 'Datajowe mě „$1“ wopśimjejo njedowólone znamuška, kótarež njejsu dowólone w titulami bokow. Pšosym pśemjeń dataju a wopytaj ju wótnowotki nagraś.',
'badfilename'                 => 'Mě dataje jo se změniło na „$1“.',
'filetype-mime-mismatch'      => 'Datajowy sufiks njewótpowědujo MIME-typoju.',
'filetype-badmime'            => 'Dataje z MIME-typom „$1“ njesměju se nagraś.',
'filetype-bad-ie-mime'        => 'Toś ta dataja njedajo se nagraś, dokulaž Internet Explorer by ju ako "$1" interpretěrował, kótaryž jo njedowólony a potencielnje tšachotny datajowy typ.',
'filetype-unwanted-type'      => "'''„.$1“''' jo njewitany datajowy typ.
{{PLURAL:$3|Dowólony datajowy typ jo|Dowólonej datajowej typa stej|Dowólene datajowe typy su}}: $2.",
'filetype-banned-type'        => "'''„.$1“''' jo njedowólony datajowy typ.
{{PLURAL:$3|Dowólony datajowy typ jo|Dowólenej datajowej typa stej|Dowólone datajowe typy su}} $2.",
'filetype-missing'            => 'Dataja njama žedno rozšyrjenje (na pś. „.jpg“).',
'empty-file'                  => 'Dataja, kótaruž sy wótpósłał, jo prozna była.',
'file-too-large'              => 'Dataja, kótaruž sy wótpósłał, jo pśewjelika była.',
'filename-tooshort'           => 'Datajowe mě jo pśekrotke.',
'filetype-banned'             => 'Toś ten datajowy typ jo zakazany.',
'verification-error'          => 'Toś ta dataja njejo pśejšeła datajowe pśeglědanje.',
'hookaborted'                 => 'Změna, kótaruž sy wopytał pśewjasć, jo se pśetergnuła pśez rozšyrjeńsku kokulu.',
'illegal-filename'            => 'Datajowe mě njejo dowólone.',
'overwrite'                   => 'Pśepisowanje eksistujuceje dataje njejo dowólone.',
'unknown-error'               => 'Njeznata zmólka jo nastała.',
'tmp-create-error'            => 'Temporerna dataja njejo se dała napóraś.',
'tmp-write-error'             => 'Zmólka pśi pisanju temporerneje dataje.',
'large-file'                  => 'Pó móžnosći njedejała dataja wětša byś ako $1. Toś ta dataja jo $2 wjelika.',
'largefileserver'             => 'Dataja jo wětša ako serwer dopušćijo.',
'emptyfile'                   => 'Dataja, kótaruž sy nagrał, jo prozna. Pśicyna móžo byś zmólka w mjenju dataje. Kontrolěruj pšosym, lěc coš dataju napšawdu nagraś.',
'fileexists'                  => "Dataja z toś tym mjenim južo eksistěrujo.
Tłocyš-lic na \"Dataju składowaś\", ga se dataja pśepišo.
Pšosym kontrolěruj '''<tt>[[:\$1]]</tt>''', gaž njejsy se kradu wěsty.
[[\$1|thumb]]",
'filepageexists'              => "Wopisański bok za toś tu dataju bu južo na '''<tt>[[:$1]]</tt>''' napórany, ale dataja z toś tym mjenim tuchylu njeeksistěrujo. Zespominanje, kótarež zapódawaš, njezjawijo se na wopisańskem boku. Aby se twóje zespominanje tam zjawiło, dejš jen manuelnje wobźěłaś.
[[$1|thumb]]",
'fileexists-extension'        => "Eksistěrujo južo dataja z pódobnym mjenim: [[$2|thumb]]
* Mě dataje, kótaraž dej se nagraś: '''<tt>[[:$1]]</tt>'''
* Mě eksistěrujuceje dataje: '''<tt>[[:$2]]</tt>'''
Pšosym wubjeŕ druge mě.",
'fileexists-thumbnail-yes'    => "Zazdaśim ma dataja reducěrowanu wjelikosć ''(thumbnail)''. [[$1|thumb]]
Kontrolěruj pšosym dataju '''<tt>[[:$1]]</tt>'''.
Jolic skontrolěrowana dataja jo ten samy wobraz w originalnej wjelikosći, pón njejo notne, separatny pśeglědowy wobraz nagraś.",
'file-thumbnail-no'           => "Mě dataje zachopijo z '''<tt>$1</tt>'''. Zda se, až to jo wobraz z reducěrowaneju wjelikosću. ''(thumbnail)''.
Jolic maš toś ten wobraz w połnem rozeznaśu, nagraj jen, howac změń pšosym mě dataje.",
'fileexists-forbidden'        => 'Dataja z toś tym mjenim južo eksistěrujo a njedajo se pśepisaś. Jolic coš hyšći swóju dataju nagraś, źi pšosym slědk a wuž nowe mě. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Dataja z toś tym mjenim južo eksistěrujo w zgromadnej chowarni. Jolic hyšći coš nagraś swóju dataju, źi pšosym slědk a wužyj nowe mě.
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Toś ta dataja jo duplikat {{PLURAL:$1|slědujuceje dataje|slědujuceju datajow|slědujucych datajow|slědujucych datajow}}:',
'file-deleted-duplicate'      => 'Dataja, kótaraž jo identiska z toś teju dataju ([[:$1]]) jo se pjerwjej wulašowała. Ty měł stawizny wulašowanja toś teje dataje pśeglědaś, pjerwjej až pokšacujoš z jeje zasejnagrawanjom.',
'uploadwarning'               => 'Warnowanje',
'uploadwarning-text'          => 'Pšosym změń slědujuce datajowe wopisanje a wopytaj hyšći raz.',
'savefile'                    => 'Dataju składowaś',
'uploadedimage'               => 'jo  "[[$1]]" nagrał.',
'overwroteimage'              => 'Jo nowu wersiju "[[$1]]" nagrał.',
'uploaddisabled'              => 'Nagrawanje jo se znjemóžniło.',
'copyuploaddisabled'          => 'Nagraśe pśez URL znjemóžnjone.',
'uploadfromurl-queued'        => 'Twójo nagraśe jo něnto w cakańskem rěźe.',
'uploaddisabledtext'          => 'Nagraśa datajow su znjemóžnjone.',
'php-uploaddisabledtext'      => 'Nagraśa PHP-datajow su znjemóžnjone. Pšosym pśekontrolěruj nastajenje file_uploads.',
'uploadscripted'              => 'Toś ta dataja wopśimjejo HTML abo script code, kótaryž móžo wót browsera se zamólnje wuwjasć.',
'uploadvirus'                 => 'Toś ta dataja ma wirus! Nadrobnosći: $1',
'upload-source'               => 'Žrědłowa dataja',
'sourcefilename'              => 'Mě žrědłoweje dataje:',
'sourceurl'                   => 'URL žrědła:',
'destfilename'                => 'Celowe mě:',
'upload-maxfilesize'          => 'Maksimalna datajowa wjelikosć: $1',
'upload-description'          => 'Datajowe wopisanje',
'upload-options'              => 'Nagrawańske opcije',
'watchthisupload'             => 'Toś tu dataju wobglědowaś',
'filewasdeleted'              => 'Dataja z toś tym mjenim jo se južo raz nagrała a mjaztym zasej wulašowała. Pšosym kontrolěruj pjerwjej $1, nježli až nagrajoš dataju znowego.',
'upload-wasdeleted'           => "'''Glědaj: Nagrawaš dataju, kótaraž jo južo raz se wulašowała.'''

Pšosym kontrolěruj, lic wótpowědujo nowe nagraśe směrnicam.
Aby se mógał informěrowaś jo how protokol z pśicynu wulašowanja:",
'filename-bad-prefix'         => "Mě dataje, kótaruž nagrawaš, zachopijo z '''„$1“'''. Take mě jo wót digitalneje kamery pśedpódane a toś wjele njewugroni. Pšosym pómjeni dataju tak, aby mě wěcej wó jeje wopśimjeśu wugroniło.",
'filename-prefix-blacklist'   => ' #<!-- Njezměń nic na toś tej rědce! --> <pre>
# Syntaksa jo slědujuca:
#   * Wšykno wót "#" znamuška až ku kóńcoju rědki jo komentar.
#   * Kužda njeprozna smužka jo prefiks za typiske datajowe mjenja, kótarež se awtomatiski wót digitalnych kamerow dodawaju.
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # some mobil phones
IMG # generic
JD # Jenoptik
MGP # Pentax
PICT # misc.
 #</pre> <!-- Njezměń nic na toś tej rědce! -->',
'upload-success-subj'         => 'Nagraśe jo było wuspěšne.',
'upload-success-msg'          => 'Twójo nagraśe z [$2] jo wuspěšne było. Stoj how k dispoziciji: [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'Nagrawański problem',
'upload-failure-msg'          => 'Jo był problem z twójim nagraśim wót [$2]:

$1',
'upload-warning-subj'         => 'Nagrawańske warnowanje',
'upload-warning-msg'          => 'Jo był problem z twójim nagraśim z [$2]. Wroś k [[Special:Upload/stash/$1|nagrawańskemu formularoju]], aby wótpórał toś ten problem.',

'upload-proto-error'        => 'Njekorektny protokol',
'upload-proto-error-text'   => 'URL musy zachopiś z <code>http://</code> abo <code>ftp://</code>.',
'upload-file-error'         => 'Interna zmólka',
'upload-file-error-text'    => 'Pśi napóranju temporarneje dataje na serwerje jo nastała interna zmólka. Pšosym staj se ze [[Special:ListUsers/sysop|systemowym administratorom]] do zwiska.',
'upload-misc-error'         => 'Njeznata zmólka pśi nagrawanju.',
'upload-misc-error-text'    => 'Pśi nagrawanju jo nastała njeznata zmólka. Kontrolěruj pšosym, lěc URL jo płaśiwy a pśistupny a wopytaj hyšći raz. Jolic problem dalej eksistěrujo, staj se z [[Special:ListUsers/sysop|administratorom]] do zwiska.',
'upload-too-many-redirects' => 'URL jo pśewjele dalejpósrědnjenja wopśimjeł',
'upload-unknown-size'       => 'Njeznata wjelikosć',
'upload-http-error'         => 'HTTP-zmólka nastata: $1',

# img_auth script messages
'img-auth-accessdenied'     => 'Pśistup zawobarany',
'img-auth-nopathinfo'       => 'PATH_INFO felujo.
Twój serwer njejo konfigurěrowany, aby toś te informacije dalej pósrědnił.
Móžo na CGI bazěrowaś a njamóžo img_auth pódpěraś.
Glědaj http://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir'         => 'Pominana šćažka njejo w konfigurěrowanem nagraśowem zapisu.',
'img-auth-badtitle'         => 'Njejo móžno z "$1" płaśiwy titel twóriś.',
'img-auth-nologinnWL'       => 'Njejsy pśizjawjony a "$1" njejo w běłej lisćinje.',
'img-auth-nofile'           => 'Dataja "$1" njeeksistěrujo.',
'img-auth-isdir'            => 'Wopytujoš na zapis "$1" pśistup měś.
Jano datajowy pśistup jo dowólony.',
'img-auth-streaming'        => '"$1" se tšuga.',
'img-auth-public'           => 'Funkcija img_auth.php jo za wudaśe datajow z priwatnego wikija.
Toś ten wiki jo ako zjawny wiki konfigurěrowany.
Za optimalnu wěstotu img_auth.php jo znjemóžnjony.',
'img-auth-noread'           => 'Wužywaŕ njama pśistup, aby cytał "$1".',
'img-auth-bad-query-string' => 'URL jo njepłaśiwy napšašowański znamuškowy rjeśazk.',

# HTTP errors
'http-invalid-url'      => 'Njepłaśiwy URL: $1',
'http-invalid-scheme'   => 'URL ze šemu "$1" se njepódpěraju.',
'http-request-error'    => 'HTTP-napšašowanje jo se njeraźiło njeznateje zmólki dla.',
'http-read-error'       => 'Cytańska zmólka HTTP.',
'http-timed-out'        => 'HTTP-napšašowanje jo cas pśekšocyło.',
'http-curl-error'       => 'Zmólka pśi wótwółowanju URL: $1',
'http-host-unreachable' => 'URL njejo był pśistupny.',
'http-bad-status'       => 'Wob cas HTTP-napšašowanje jo problem był: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL njejo pśistupna.',
'upload-curl-error6-text'  => 'Pódana URL njejo pśistupna. Pśeglěduj URL na zmólki a kontrolěruj online-status boka.',
'upload-curl-error28'      => 'Pśi nagrawanju jo se cas pśekšocył.',
'upload-curl-error28-text' => 'Bok pśedłujko njejo wótegronił. Kontrolěruj, lic jo bok online, pócakaj wokognuśe a wopytaj pón hyšći raz. Móžo byś zmysłapołne, w drugem casu hyšći raz proběrowaś.',

'license'            => 'Licenca:',
'license-header'     => 'Licencowanje',
'nolicense'          => 'Nic njejo wuzwólone.',
'license-nopreview'  => '(Pśeglěd njejo móžny.)',
'upload_source_url'  => ' (płaśeca, zjawnje pśistupna URL)',
'upload_source_file' => ' (dataja na twójom kompjuterje)',

# Special:ListFiles
'listfiles-summary'     => 'Toś ten specialny bok pokazujo wšykne nagrate dataje.
Jolic se pó wužywarju filtrujo, budu se jano dataje pokazowaś, pśi kótarychž ten wužywaŕ jo nejnowšu wersiju nagrał.',
'listfiles_search_for'  => 'Za medijowym mjenim pytaś:',
'imgfile'               => 'dataja',
'listfiles'             => 'Lisćina datajow',
'listfiles_thumb'       => 'Pśeglědowy wobraz',
'listfiles_date'        => 'datum',
'listfiles_name'        => 'mě dataje',
'listfiles_user'        => 'wužywaŕ',
'listfiles_size'        => 'Wjelikosć (byte)',
'listfiles_description' => 'Zespominanje',
'listfiles_count'       => 'Wersije',

# File description page
'file-anchor-link'          => 'Dataja',
'filehist'                  => 'Stawizny dataje',
'filehist-help'             => 'Tłoc na datum/cas aby tencasna wersija se lodowała.',
'filehist-deleteall'        => 'Wšykno wulašowaś',
'filehist-deleteone'        => 'Lašowaś',
'filehist-revert'           => 'Slědk wześ',
'filehist-current'          => 'něntejšny',
'filehist-datetime'         => 'datum/cas',
'filehist-thumb'            => 'Pśeglědowy wobraz',
'filehist-thumbtext'        => 'Pśeglědowy wobraz za wersiju wót $1',
'filehist-nothumb'          => 'Žeden pśeglědowy wobraz',
'filehist-user'             => 'Wužywaŕ',
'filehist-dimensions'       => 'rozměry',
'filehist-filesize'         => 'Wjelikosć dataje',
'filehist-comment'          => 'Komentar',
'filehist-missing'          => 'Dataja felujo',
'imagelinks'                => 'Datajowe wužywanje',
'linkstoimage'              => '{{PLURAL:$1|Slědujucy bok wótkazujo|Slědujucej $1 boka wótkazujotej|Slědujuce $1 boki wótkazuju|Slědujucych $1 bokow wótkazujo}} na toś tu dataju:',
'linkstoimage-more'         => 'Wěcej nježli $1 {{PLURAL:$1|bok wótkazujo|boka wótkazujotej|boki wótkazuju|bokow wótkazujo}} na toś tu dataju.
Slědujuca lisćina pokazujo jano {{PLURAL:$1|prědny wótkaz|prědnej $1 wótkaza|prědne $1 wótkaze|prědnych $1 wótkazow}} k toś tej dataji.
[[Special:WhatLinksHere/$2|Dopołna lisćina]] stoj k dispoziciji.',
'nolinkstoimage'            => 'Žedne boki njewótkazuju na toś tu dataju.',
'morelinkstoimage'          => '[[Special:WhatLinksHere/$1|Dalšne wótkazy]] k toś tej dataji wobglědaś.',
'redirectstofile'           => '{{PLURAL:$1|Slědujuca dataja dalej pósrědnja|Slědujucej $1 dataji dalej pósrědnjatej|slědujuce $1 dataje dalej póšrědnjaju|Slědujucych $1 datajow dalej pósrědnja}} k toś tej dataji:',
'duplicatesoffile'          => '{{PLURAL:$1|Slědujuca dataja jo duplikat|Slědujucej $1 dataji stej duplikata|Slědujuce dataje $1 su duplikaty|Slědujucych $1 datajow jo duplikaty}} toś teje dataje ([[Special:FileDuplicateSearch/$2|dalšne drobnostki]])::',
'sharedupload'              => 'Toś ta dataja jo z $1 a dajo se pśez druge projekty wužywaś.',
'sharedupload-desc-there'   => 'Toś ta dataja jo z $1 a dajo se pśez druge projekty wužywaś. Pšosym glědaj [$2 bok datajowego wopisanja] za dalšne informacije.',
'sharedupload-desc-here'    => 'Toś ta dataja jo z $1 a dajo se pśez druge projekty wužywaś. Wopisanje na jeje [$2 boku datajowego wopisanja] pokazujo se dołojce.',
'filepage-nofile'           => 'Dataja z toś tym mjenim njeeksistěrujo.',
'filepage-nofile-link'      => 'Dataj z toś tym mjenim njeeksistěrujo, ale móžoš [$1 ju nagraś].',
'uploadnewversion-linktext' => 'Nowu wersiju toś teje dataje nagraś',
'shared-repo-from'          => 'z $1',
'shared-repo'               => 'zgromadny repozitorium',

# File reversion
'filerevert'                => 'Slědk wześ $1',
'filerevert-legend'         => 'Dataju nawrośiś',
'filerevert-intro'          => "Nawrośijoš dataju '''[[Media:$1|$1]]''' na [$4 wersiju wót $2, $3 góź.].",
'filerevert-comment'        => 'Pśicyna:',
'filerevert-defaultcomment' => 'Nawrośona na wersiju wót $1, $2 góź.',
'filerevert-submit'         => 'Slědk wześ',
'filerevert-success'        => "'''[[Media:$1|$1]]''' jo se nawrośiło na [$4 wersiju wót $2, $3 góź.].",
'filerevert-badversion'     => 'Za pódany cas njeeksistěrujo žedna wersija dataje.',

# File deletion
'filedelete'                  => 'Wulašowaś $1',
'filedelete-legend'           => 'Wulašowaś dataje',
'filedelete-intro'            => "Lašujoš dataju '''[[Media:$1|$1]]''' gromaźe z jeje cełymi stawiznami.",
'filedelete-intro-old'        => "Wulašujoš [$4 wersiju wót $2, $3 góź.] dataje '''„[[Media:$1|$1]]“'''.",
'filedelete-comment'          => 'Pśicyna:',
'filedelete-submit'           => 'Wulašowaś',
'filedelete-success'          => "'''$1''' jo se wulašował.",
'filedelete-success-old'      => "Wersija wót $2, $3 góź. dataje '''[[Media:$1|$1]]''' jo se wulašowała.",
'filedelete-nofile'           => "'''$1''' njeekistěrujo.",
'filedelete-nofile-old'       => "Njejo archiwowana wersija '''$1''' z pódanymi atributami.",
'filedelete-otherreason'      => 'Druga/pśidatna pśicyna:',
'filedelete-reason-otherlist' => 'Druga pśicyna',
'filedelete-reason-dropdown'  => '*Powšykne pśicyny za lašowanja
** Pśekśiwjenje stworiśelskego pšawa
** Dwójna dataja',
'filedelete-edit-reasonlist'  => 'Pśicyny za lašowanje wobźěłaś',
'filedelete-maintenance'      => 'Wulašowanje a wótnowjenje datajow stej wótwardowanja dla nachylu znjemóžnjonej.',

# MIME search
'mimesearch'         => 'MIME-typ pytaś',
'mimesearch-summary' => 'Na toś tom specialnem boku mógu se dataje pó MIME-typu filtrowaś. Zapódaśe dej wopśimjeś stawnje typ medija a subtyp: <tt>image/jpeg</tt>.',
'mimetype'           => 'Typ MIME:',
'download'           => 'Ześěgnuś',

# Unwatched pages
'unwatchedpages' => 'Njewobglědowane boki',

# List redirects
'listredirects' => 'Lisćina dalejpósrědnjenjow',

# Unused templates
'unusedtemplates'     => 'Njewužywane pśedłogi',
'unusedtemplatestext' => 'Toś ten bok nalicujo wšykne boki w mjenjowom rumje {{ns:template}}, kótarež njejsu do žednogo drugego boka zawězane. Pšosym kontrolěruj dalšne wótkaze, nježli až je wulašujoš.',
'unusedtemplateswlh'  => 'Druge wótkaze',

# Random page
'randompage'         => 'Pśipadny nastawk',
'randompage-nopages' => 'W {{PLURAL:$2|slědujucem mjenjowem rumje|slědujucyma mjenjowyma rumoma|slědujucych mjenjowych rumach|slědujucych mjenjowych rumach}} žedne boki njejsu: $1',

# Random redirect
'randomredirect'         => 'Pśipadne dalejpósrědnjenje',
'randomredirect-nopages' => 'W mjenjowem rumje "$1" njejsu dalejpósrědnjenja.',

# Statistics
'statistics'                   => 'Statistika',
'statistics-header-pages'      => 'Statistika bokow',
'statistics-header-edits'      => 'Statistika změnow',
'statistics-header-views'      => 'Statistiku zwobrazniś',
'statistics-header-users'      => 'Statistika wužywarjow',
'statistics-header-hooks'      => 'Druga statistika',
'statistics-articles'          => 'Wopśimjeśowe boki',
'statistics-pages'             => 'Boki',
'statistics-pages-desc'        => 'Wše boki w toś tom wikiju, inkluziwnje diskusijne boki, dalejpósrědnjenja atd.',
'statistics-files'             => 'Nagrate dataje',
'statistics-edits'             => 'Změny bokow wót załoženja {{SITENAME}}',
'statistics-edits-average'     => 'Změny na bok w pśerězku',
'statistics-views-total'       => 'Zwobraznjenja dogromady',
'statistics-views-total-desc'  => 'Woglědanja na njeeksistěrujucych bokach a specialnych bokach njejsu zapśimjone',
'statistics-views-peredit'     => 'Zwobraznjenja na změnu',
'statistics-users'             => 'Zregistrěrowane [[Special:ListUsers|wužywarje]]',
'statistics-users-active'      => 'Aktiwne wužywarje',
'statistics-users-active-desc' => 'Wužywarje, kótarež su {{PLURAL:$1|cora|w slědnyma $1 dnjoma|w slědnych $1 dnjach|w slědnych $1 dnjach}} aktiwne byli',
'statistics-mostpopular'       => 'Nejwěcej woglědane boki',

'disambiguations'      => 'Boki, kótarež wótkazuju na boki wěcejzmysłowosći',
'disambiguationspage'  => 'Template:Rozjasnjenje zapśimjeśow',
'disambiguations-text' => 'Slědujuce boki wótkazuju na bok za rozjasnjenje zapśimjeśow.
Wótkazujśo lubjej na pótrjefjony bok.<br />
Bok wobjadnawa se ako bok wujasnjenja zapśimjeśa, gaž wótkazujo na nju [[MediaWiki:Disambiguationspage]].',

'doubleredirects'            => 'Dwójne dalejpósrědnjenja',
'doubleredirectstext'        => 'Toś ten bok nalicujo boki, kótarež dalej pósrědnjaju na druge dalejpósrědnjenja.
Kužda smužka wopśimjejo wótkaze na prědne a druge dalejpósrědnjenje a teke na cel drugego dalejpósrědnjenja, což jo w normalnem paźe "napšawdny" celowy bok, na kótaryž by mógło prědne dalejpósrědnjenje pokazaś. <del>Pśešmarnjone</del> zapiski su južo wobstarane.',
'double-redirect-fixed-move' => '[[$1]] jo se pśesunuł, jo něnto dalejposrědnjenje do [[$2]]',
'double-redirect-fixer'      => 'Pórěźaŕ dalejpósrědnjenjow',

'brokenredirects'        => 'Skóńcowane dalejpósrědnjenja',
'brokenredirectstext'    => 'Slědujuce dalejpósrědnjenja wótkazuju na njeeksistěrujuce boki:',
'brokenredirects-edit'   => 'wobźěłaś',
'brokenredirects-delete' => 'wulašowaś',

'withoutinterwiki'         => 'Boki na kótarychž njejsu žedne wótkaze na druge rěcy',
'withoutinterwiki-summary' => 'Slědujuce boki njewótkazuju na druge rěcne wersije:',
'withoutinterwiki-legend'  => 'Prefiks',
'withoutinterwiki-submit'  => 'Pokazaś',

'fewestrevisions' => 'Boki z nejmjenjej wersijami',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|byta|byty}}',
'ncategories'             => '$1 {{PLURAL:$1|kategorija|kategoriji|kategorije}}',
'nlinks'                  => '$1 {{PLURAL:$1|wótkaz|wótkaza|wótkaze}}',
'nmembers'                => '$1 {{PLURAL:$1|zapis|zapisa|zapise}}',
'nrevisions'              => '$1 {{PLURAL:$1|wobźěłanje|wobźěłani|wobźěłanja}}',
'nviews'                  => '$1 {{PLURAL:$1|wótpšašanje|wótpšašani|wótpšašanja}}',
'nimagelinks'             => 'Wužywa se na $1 {{PLURAL:$1|boku|bokoma|bokach|bokach}}',
'ntransclusions'          => 'wužywa se na $1 {{PLURAL:$1|boku|bokoma|bokach|bokach}}',
'specialpage-empty'       => 'Toś ten bok njewopśimjejo tuchylu žedne zapise.',
'lonelypages'             => 'Wósyrośone boki',
'lonelypagestext'         => 'Slědujuce boki njejsu wótkazowe cele drugich bokow abo njezapśimuju se do drugich bokow w
{{GRAMMAR:lokatiw|{{SITENAME}}}}.',
'uncategorizedpages'      => 'Boki bźez kategorijow',
'uncategorizedcategories' => 'Njekategorizěrowane kategorije',
'uncategorizedimages'     => 'Njekategorizěrowane dataje.',
'uncategorizedtemplates'  => 'Njekategorizěrowane pśedłogi',
'unusedcategories'        => 'Njewužywane kategorije',
'unusedimages'            => 'Njewužywane dataje',
'popularpages'            => 'Woblubowane boki',
'wantedcategories'        => 'Póžedane kategorije',
'wantedpages'             => 'Póžedane boki',
'wantedpages-badtitle'    => 'Njepłaśiwy titel we wuslědku: $1',
'wantedfiles'             => 'Póžedane dataje',
'wantedtemplates'         => 'Brachujuce pśedłogi',
'mostlinked'              => 'Nejcesćej zalinkowane boki',
'mostlinkedcategories'    => 'Nejcesćej wužywane kategorije',
'mostlinkedtemplates'     => 'Nejcesćej wužywane psedłogi',
'mostcategories'          => 'Boki z nejwěcej kategorijami',
'mostimages'              => 'Nejcesćej wótkazane dataje',
'mostrevisions'           => 'Boki z nejwěcej wersijami',
'prefixindex'             => 'Wšykne boki z prefiksom',
'shortpages'              => 'Krotke nastawki',
'longpages'               => 'Dłujke nastawki',
'deadendpages'            => 'Nastawki bźez wótkazow',
'deadendpagestext'        => 'Slědujuce boki njewótkazuju na druge boki we {{GRAMMAR:lokatiw|{{SITENAME}}}}.',
'protectedpages'          => 'Šćitane boki',
'protectedpages-indef'    => 'Jano boki pokazaś, kótarež su na njewěsty cas šćitane',
'protectedpages-cascade'  => 'Jano boki z kaskadowym šćitom',
'protectedpagestext'      => 'Slědujuce boki njamgu se mimo wósebnych pšawow wobźěłaś resp. pśesuwaś',
'protectedpagesempty'     => 'Z toś tymi parametrami njejsu tuchylu žedne boki šćitane.',
'protectedtitles'         => 'Šćitane titele',
'protectedtitlestext'     => 'Slědujuce titele su pśeśiwo twórjenjoju šćitane.',
'protectedtitlesempty'    => 'Tuchylu njejsu žedne boki z pódanych parametrami šćitane.',
'listusers'               => 'Lisćina wužywarjow',
'listusers-editsonly'     => 'Jano wužywarjow ze změnami pokazaś',
'listusers-creationsort'  => 'Pó datumje napóranja sortěrowaś',
'usereditcount'           => '$1 {{PLURAL:$1|změna|změnje|změny|změnow}}',
'usercreated'             => 'jo se $1 $2 góź. {{GENDER:$3|napórał|napórała}}',
'newpages'                => 'Nowe boki',
'newpages-username'       => 'Wužywarske mě:',
'ancientpages'            => 'Nejstarše boki',
'move'                    => 'Pśesunuś',
'movethispage'            => 'Bok pśesunuś',
'unusedimagestext'        => 'Slědujuce dataje eksistěruju, ale njejsu do boka zasajźone.
Pšosym glědaj na to, až druge websedła móžu k drugej dataji z direktnym URL wótkazowaś a móžo togodla how hyšće nalicona byś, lěcrownož se rowno wužywaju.',
'unusedcategoriestext'    => 'Toś ten specialny bok pokazujo wšykne njekategorizěrowane kategorije.',
'notargettitle'           => 'Žeden celowy bok njejo zapódany.',
'notargettext'            => 'Njejsy zapódał celowy bok, źož dejała funkcija se wugbaś.',
'nopagetitle'             => 'Žeden taki celowy bok',
'nopagetext'              => 'Celowy bok, kótaryž sćo pódał, njeeksistěrujo.',
'pager-newer-n'           => '{{PLURAL:$1|nowšy 1|nowšej $1|nowše $1|nowšych $1}}',
'pager-older-n'           => '{{PLURAL:$1|staršy 1|staršej $1|starše $1|staršych $1}}',
'suppress'                => 'Doglědowanje',

# Book sources
'booksources'               => 'Pytanje pó ISBN',
'booksources-search-legend' => 'Knigłowe žrědła pytaś',
'booksources-go'            => 'Pytaś',
'booksources-text'          => 'To jo lisćina z wótkazami na internetowe boki, kótarež pśedawaju nowe a trjebane knigły. Tam mógu teke dalšne informacije wó knigłach byś. {{SITENAME}} njezwisujo góspodarski z žednym z toś tych póbitowarjow.',
'booksources-invalid-isbn'  => 'Pódane ISBN-cysło njezda se płaśiwe byś; pséglědaj za zmólkami, z tym až kopěrujoš z originalnego žrědła.',

# Special:Log
'specialloguserlabel'  => 'Wuwjeźaŕ:',
'speciallogtitlelabel' => 'Cel (titel abo wužywaŕ):',
'log'                  => 'Protokole',
'all-logs-page'        => 'Wšykne zjawne protokole',
'alllogstext'          => 'To jo kombiněrowane zwobraznjenje wšyknych we {{GRAMMAR:lokatiw|{{SITENAME}}}} k dispoziciji stojecych protokolow. Móžoš naglěd pśez wubraśe protokolowego typa, wužywarskego mjenja (pód źiwanim wjelikopisanja) abo pótrjefjonego boka (teke pód źiwanim wjelikopisanja) wobgranicowaś.',
'logempty'             => 'Žedne se góźece zapise njeeksistěruju.',
'log-title-wildcard'   => 'Pytaś nadpismo, kótarež zachopijo z ...',

# Special:AllPages
'allpages'          => 'Wšykne boki',
'alphaindexline'    => '$1 do $2',
'nextpage'          => 'Slědujucy bok ($1)',
'prevpage'          => 'Pśedchadny bok ($1)',
'allpagesfrom'      => 'Boki pokazaś wót:',
'allpagesto'        => 'Boki zwobrazniś, kótarež kóńce se na:',
'allarticles'       => 'Wšykne nastawki',
'allinnamespace'    => 'Wšykne boki (mjenjowy rum: $1)',
'allnotinnamespace' => 'Wšykne boki (nic w mjenjowem rumje $1)',
'allpagesprev'      => 'Pśedchadne',
'allpagesnext'      => 'Slědujuce',
'allpagessubmit'    => 'Pokazaś',
'allpagesprefix'    => 'Boki pokazaś (z prefiksom):',
'allpagesbadtitle'  => 'Zapódane mě boka njejo płaśece: Jo móžno, až ma pśedstajonu rěcnu resp. interwikijowu krotceńku abo wopśimjejo jadno abo wěcej znamuškow, kótarež njamgu se za mjenja bokow wužywaś.',
'allpages-bad-ns'   => 'Mjenjowy rum „$1“ w {{SITENAME}} njeeksistěrujo.',

# Special:Categories
'categories'                    => 'Kategorije',
'categoriespagetext'            => '{{PLURAL:$1|Slědujuca kategorija wopśimujo|Slědujucej kategoriji wopśimujotej|Slědujuce kategorije wopśimuju|Slědujuce kategorije wopśimuju}} boki abo medije.
[[Special:UnusedCategories|Njewužywane kategorije]] se how njepokazuju.
Glědaj teke [[Special:WantedCategories|póžedane kategorije]].',
'categoriesfrom'                => 'Kategorije pokazaś, zachopinajucy z:',
'special-categories-sort-count' => 'pśewuběrowaś pó licbje',
'special-categories-sort-abc'   => 'pśewuběrowaś pó alfabeśe',

# Special:DeletedContributions
'deletedcontributions'             => 'Wulašowane wužywarske pśinoski',
'deletedcontributions-title'       => 'Wulašowane wužywarske pśinoski',
'sp-deletedcontributions-contribs' => 'pśinoski',

# Special:LinkSearch
'linksearch'       => 'Pytanje eksternych wótkazow',
'linksearch-pat'   => 'Pytański muster:',
'linksearch-ns'    => 'Mjenjowy rum:',
'linksearch-ok'    => 'Pytaś',
'linksearch-text'  => 'Jo móžno zastupne znamuška kaž "*.wikipedia.org" wužywaś. 
Jo nanejmjenjej głowna domena trěbna, na pśikład "*.org"<br />
Pódpěrane protokole: <tt>$1</tt> (pšosym njepódaj je w swójom pytanju).',
'linksearch-line'  => '$1 wótkazany z $2',
'linksearch-error' => 'Zasupne znamješko daju se jano na zachopjeńku URL wužywaś.',

# Special:ListUsers
'listusersfrom'      => 'Pokaž wužywarjow wót:',
'listusers-submit'   => 'Pokazaś',
'listusers-noresult' => 'Žeden wužywaŕ njejo se namakał.',
'listusers-blocked'  => '(blokěrowany)',

# Special:ActiveUsers
'activeusers'            => 'Lisćina aktiwnych wužywarjow',
'activeusers-intro'      => 'To jo lisćina wužywarjow, kotrež su byli aktiwne za {{PLURAL:$1|slědny źeń|slědnej $1 dnja|slědne $1 dny|slědnych $1 dnjow}}.',
'activeusers-count'      => '$1 {{PLURAL:$1|změna|změnje|změny|změnow}} w {{PLURAL:$3|slědnem dnju|slědnyma $3 dnjoma|slědnych $3 dnjach|slědnych $3 dnjach}}',
'activeusers-from'       => 'Wužywarjow zwobrazniś, zachopinajucy z:',
'activeusers-hidebots'   => 'Boty schowaś',
'activeusers-hidesysops' => 'Administratorow schowaś',
'activeusers-noresult'   => 'Žedne wužywarje namakane.',

# Special:Log/newusers
'newuserlogpage'              => 'Protokol nowych wužywarjow',
'newuserlogpagetext'          => 'To jo protokol wó nowych wužywarskich kontow.',
'newuserlog-byemail'          => 'Pótajne słowo bu pśez e-mail pósłane.',
'newuserlog-create-entry'     => 'Nowy wužywaŕ',
'newuserlog-create2-entry'    => 'Nowe konto za $1 napórane.',
'newuserlog-autocreate-entry' => 'Wužywarske konto bu awtomatiski napórane',

# Special:ListGroupRights
'listgrouprights'                      => 'Pšawa wužywarskeje kupki',
'listgrouprights-summary'              => 'To jo lisćina wužywarskich kupkow definěrowanych w toś tom wikiju z jich zwězanymi pśistupnymi pšawami. Móžo [[{{MediaWiki:Listgrouprights-helppage}}|pśidatne informacije]] wó jadnotliwych pšawach daś.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Zagarantěrowane pšawo</span>
* <span class="listgrouprights-revoked">Slědk wzete pšawo</span>',
'listgrouprights-group'                => 'Kupka',
'listgrouprights-rights'               => 'Pšawa',
'listgrouprights-helppage'             => 'Help:Kupkowe pšawa',
'listgrouprights-members'              => '(lisćina cłonkow)',
'listgrouprights-addgroup'             => 'Móžo pśidaś {{PLURAL:$2|kupku|kupce|kupki|kupkow}}: $1',
'listgrouprights-removegroup'          => 'Móžo wótwónoźeś {{PLURAL:$2|kupku|kupce|kupki|kupkow}}: $1',
'listgrouprights-addgroup-all'         => 'Móžo pśidaś wšykne kupki',
'listgrouprights-removegroup-all'      => 'Móžo wótwónoźeś wšykne kupki',
'listgrouprights-addgroup-self'        => 'Móžo {{PLURAL:$2|kupku|kupce|kupki|kupkow}} swójskemu kontoju pśidaś: $1',
'listgrouprights-removegroup-self'     => 'Móžo {{PLURAL:$2|kupku|kupce|kupki|kupkow}} ze swójskego konta wótpóraś: $1',
'listgrouprights-addgroup-self-all'    => 'Móžo wše kupki swójskemu kontoju pśidaś',
'listgrouprights-removegroup-self-all' => 'Móžo wše kupki ze swójskego konta wótpóraś',

# E-mail user
'mailnologin'          => 'Njejo móžno e-mailku pósłaś.',
'mailnologintext'      => 'Dejš [[Special:UserLogin|pśizjawjony]] byś a płaśiwu e-mailowu adresu w swójich [[Special:Preferences|nastajenjach]] měś, aby drugim wužywarjam e-mail pósłał.',
'emailuser'            => 'Toś tomu wužywarjeju e-mail pósłaś',
'emailpage'            => 'E-mail wužywarjeju',
'emailpagetext'        => 'Móžoš slědujucy formular wužywaś, aby toś tomu wužywarjeju e-mail pósłał.
E-mailowa adresa, kótaruž sy zapódał w [[Special:Preferences|swójich wužywarskich nastajenjach]], zjawi se ako adresa w pólu "Wót" e-maile, aby dostawaŕ móžo śi direktnje wótegroniś.',
'usermailererror'      => 'E-mailowy objekt jo zmólku wrośił.',
'defemailsubject'      => '{{SITENAME}} e-mail',
'usermaildisabled'     => 'Wužywarska e-mail znjemóžnjona',
'usermaildisabledtext' => 'Njamóžoš w toś tym wikiju drugim wužywarjam e-mail pósłaś',
'noemailtitle'         => 'E-mailowa adresa felujo.',
'noemailtext'          => 'Toś ten wužywaŕ njejo pódał płaśiwu e-mailowu adresu.',
'nowikiemailtitle'     => 'Žedna e-mail dowólona',
'nowikiemailtext'      => 'Toś ten wužywaŕ njoco žednu e-mail wót drugich wužywarjow dostaś.',
'email-legend'         => 'Drugemu wužywarjeju {{SITENAME}} e-mail pósłaś',
'emailfrom'            => 'Wót:',
'emailto'              => 'Komu:',
'emailsubject'         => 'Tema:',
'emailmessage'         => 'Powěsć:',
'emailsend'            => 'Wótpósłaś',
'emailccme'            => 'Pósćel mě kopiju e-maila.',
'emailccsubject'       => 'Kopija Twójeje powěsći na $1: $2',
'emailsent'            => 'e-mail wótposłany',
'emailsenttext'        => 'Twój e-mail jo se wótpósłał.',
'emailuserfooter'      => 'Toś ta e-mailka jo se z pomocu funkcije "Toś tomu wužywarjeju e-mail pósłaś" na {{SITENAME}} wót $1 do $2 pósłała.',

# User Messenger
'usermessage-summary' => 'Systemowu powěźeńku zawóstajis.',
'usermessage-editor'  => 'Systemowy powěstnik',

# Watchlist
'watchlist'            => 'Wobglědowańka',
'mywatchlist'          => 'wobglědowańka',
'watchlistfor2'        => 'Za wužywarja $1 $2',
'nowatchlist'          => 'Žedne zapise w twójej wobglědowańce.',
'watchlistanontext'    => 'Dejš $1, aby mógał swóju wobglědowańku wiźeś abo zapise w njej wobźěłaś.',
'watchnologin'         => 'Njepśizjawjony(a)',
'watchnologintext'     => 'Musyš [[Special:UserLogin|pśizjawjony]] byś, aby mógał swóju wobglědowańku wobźěłaś.',
'addedwatch'           => 'Jo se k wobglědowańce dodało',
'addedwatchtext'       => "Bok \"[[:\$1]]\" jo se k twójej [[Special:Watchlist|wobglědowańce]] dodał.
Pózdźejšne změny na toś tom boku a w pśisłušecej diskusiji se tam nalicuju, a bok buźo se w [[Special:RecentChanges|lisćinje aktualnych změnow]] '''tucnje''' pokazaś, aby daju se lažčej namakaś.",
'removedwatch'         => 'Jo se z wobglědowańki wulašowało',
'removedwatchtext'     => 'Bok "[[:$1]]" jo se z [[Special:Watchlist|twójeje wobglědowańki]] wulašowany.',
'watch'                => 'Wobglědowaś',
'watchthispage'        => 'Bok wobglědowaś',
'unwatch'              => 'Dalej njewobglědowaś',
'unwatchthispage'      => 'Dalej njewobglědowaś',
'notanarticle'         => 'To njejo žeden nastawk',
'notvisiblerev'        => 'Wersija bu wulašowana',
'watchnochange'        => 'Žeden wót tebje wobglědowany bok njejo se we wótpowědujucem casu wobźěłał.',
'watchlist-details'    => 'Wobglědujoš {{PLURAL:$1|$1 bok|$1 boka|$1 boki|$1 bokow}}, bźez diskusijnych bokow.',
'wlheader-enotif'      => '* E-mailowe powěsće su aktiwizěrowane.',
'wlheader-showupdated' => "* Boki, kótarež su wót twójogo slědnego woglěda se změnili, pokazuju se '''tucnje'''.",
'watchmethod-recent'   => 'Kontrolěrowanje aktualnych změnow we wobglědowańce',
'watchmethod-list'     => 'Pśepytanje wobglědowanych bokow za aktualnymi změnami',
'watchlistcontains'    => 'Twója wobglědowańka wopśimujo $1 {{PLURAL:$1|bok|boka|boki|bokow}}.',
'iteminvalidname'      => 'Problem ze zapisom „$1“, njepłaśece mě.',
'wlnote'               => "{{PLURAL:$1|Slědujo slědna změna|slědujotej '''$1''' slědnej změnje|slěduju slědne '''$1''' změny}} {{PLURAL:$2|slědneje góźiny|slědneju '''$2''' góźinowu|slědnych '''$2''' góźinow}}.",
'wlshowlast'           => 'Pokaž změny slědnych $1 góźinow, $2 dnjow abo $3 (w slědnych 30 dnjach).',
'watchlist-options'    => 'Opcije wobglědowańki',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Wobglědowaś …',
'unwatching' => 'Njewobglědowaś …',

'enotif_mailer'                => '{{SITENAME}} e-mailowe powěsći',
'enotif_reset'                 => 'Wšykne boki ako woglědane markěrowaś',
'enotif_newpagetext'           => 'To jo nowy bok.',
'enotif_impersonal_salutation' => '{{SITENAME}}-wužywaŕ',
'changed'                      => 'změnił',
'created'                      => 'napórał',
'enotif_subject'               => '[{{SITENAME}}] $PAGEEDITOR jo bok "$PAGETITLE" $CHANGEDORCREATED',
'enotif_lastvisited'           => 'Wšykne změny na jadno póglědnjenje: $1',
'enotif_lastdiff'              => 'Za toś tu změnu glědaj w $1.',
'enotif_anon_editor'           => 'anonymny wužywaŕ $1',
'enotif_body'                  => 'Luby $WATCHINGUSERNAME,

PAGEEDITOR jo bok {{SITENAME}} "$PAGETITLE" $PAGEEDITDATE $CHANGEDORCREATED, glědaj $PAGETITLE_URL za aktualnu wersiju.

$NEWPAGE

Zespominanje wobźěłarja: $PAGESUMMARY $PAGEMINOREDIT

Kontakt z wobźěłarjom:
E-mail: $PAGEEDITOR_EMAIL
Wiki: $PAGEEDITOR_WIKI

There will be no other notifications in case of further changes unless you visit this page.
You could also reset the notification flags for all your watched pages on your watchlist.

Njebudu žedne dalšne powěźeńki w paźe dalšnych změnow, snaźkuli woglědujoš se toś ten bok.
Móźoś teke chórgojcki powěźeńkow za wšykne twóje wobglědowane boki.

             Twój pśijaśelny powěsćowy system {{SITENAME}}
--
Aby nastajenja twójeje wobglědowańki změnił, woglědaj:
{{fullurl:Special:Watchlist/edit}}

Aby se bok z twójeje wobglědowańki wulašował, woglědaj se
$UNWATCHURL

Pšašanja a dalšna pomoc:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Bok wulašowaś',
'confirm'                => 'Wobkšuśiś',
'excontent'              => "wopśimjeśe jo było: '$1'",
'excontentauthor'        => "wopśimjeśe jo było: '$1' (a jadnučki wobźěłaŕ jo był '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'          => "Wopśimjeśe do wuprozdnjenja jo było: '$1'",
'exblank'                => 'bok jo był prozny',
'delete-confirm'         => '„$1“ lašowaś',
'delete-legend'          => 'Lašowaś',
'historywarning'         => "'''Glědaj:''' Bok, kótaryž coš wulašowaś, ma historiju z něźi $1 {{PLURAL:$1|wersiju|wersijoma|wersijami|wersijami}}:",
'confirmdeletetext'      => 'Coš bok abo dataju ze wšyknymi pśisłušnymi wersijami na pśecej wulašowaś. Pšosym wobkšuś, až sy se wědobny, kake konsekwency móžo to měś, a až jadnaš pó [[{{MediaWiki:Policy-url}}|směrnicach]].',
'actioncomplete'         => 'Akcija jo se wugbała.',
'actionfailed'           => 'Akcija jo se njeraźiła',
'deletedtext'            => '„<nowiki>$1</nowiki>“ jo se wulašował(a/o). W $2 namakajoš lisćinu slědnych wulašowanjow.',
'deletedarticle'         => 'jo "[[$1]]" wulašował',
'suppressedarticle'      => '"[[$1]]" pódtłocony',
'dellogpage'             => 'Protokol wulašowanjow',
'dellogpagetext'         => 'How jo protokol wulašowanych bokow a datajow.',
'deletionlog'            => 'protokol wulašowanjow',
'reverted'               => 'Nawrośone na staršu wersiju',
'deletecomment'          => 'Pśicyna:',
'deleteotherreason'      => 'Druga/pśidatna pśicyna:',
'deletereasonotherlist'  => 'Druga pśicyna',
'deletereason-dropdown'  => '* Powšykne pśicyny za lašowanja
** Žycenje awtora
** Pśekśiwjenje stworiśelskego pšawa
** Wandalizm',
'delete-edit-reasonlist' => 'Pśicyny za lašowanje wobźěłaś',
'delete-toobig'          => 'Toś ten bok ma z wěcej nježli $1 {{PLURAL:$1|wersiju|wersijomaj|wersijami|wersijami}} dłujku historiju. Lašowanje takich bokow bu wobgranicowane, aby wobškoźenju {{GRAMMAR:genitiw|{{SITENAME}}}} z pśigódy zajźowało.',
'delete-warning-toobig'  => 'Toś ten bok ma z wěcej ako $1 {{PLURAL:$1|wersiju|wersijomaj|wersijami|wersijami}} dłujke stawizny. Jich wulašowanje móžo źěło datoweje banki na {{SITENAME}} kazyś;
póstupujśo z glědanim.',

# Rollback
'rollback'          => 'Wobźěłanja slědk wześ',
'rollback_short'    => 'anulěrowaś',
'rollbacklink'      => 'anulěrowaś',
'rollbackfailed'    => 'Slědkwześe njejo se raźiło.',
'cantrollback'      => 'Njejo móžno změnu slědk wześ, slědny pśinosowaŕ jo jadnučki awtor boka.',
'alreadyrolled'     => 'Njejo móžno slědnu změnu w nastawku [[:$1]] wót [[User:$2|$2]] ([[User talk:$2|diskusija]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]) slědk wześ; drugi wužywaŕ jo mjaztym bok změnił abo južo slědk stajił .

Slědnu změnu k bokoju jo pśewjadł [[User:$3|$3]] ([[User talk:$3|diskusija]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "Zapominanje k slědnej změnje jo było: \"''\$1''\".",
'revertpage'        => 'Změny wužywarja [[Special:Contributions/$2|$2]] ([[User talk:$2|Diskusija]]) su se wótwrośili a slědna wersija wužywarja [[User:$1|$1]] jo se wótnowiła.',
'revertpage-nouser' => 'Jo změny wót (wužywarske mě wótpórane) na slědnu wersiju wót [[User:$1|$1]] slědk stajił',
'rollback-success'  => 'Změny wót $1 su se slědk wzeli a slědna wersija wót $2 jo zasej se nawrośiła.',

# Edit tokens
'sessionfailure-title' => 'Pósejźeńska zmólka',
'sessionfailure'       => 'Problem z twójim wužywarskim pósejźenim jo se wujawił.
Wěstoty dla jo akcija se pśetergnuła, aby se zadorało wopacnemu pśirědowanjoju twójeje změny drugemu wužywarjeju.
Pšosym nawroś se na bok, wót kótaregož sy pśišeł a wopytaj hyšći raz.',

# Protect
'protectlogpage'              => 'Protokol šćitanych bokow.',
'protectlogtext'              => 'To jo protokol šćitanych bokow. Glědaj do [[Special:ProtectedPages|lisćiny šćitanych bokow]], aby wiźeł wšykne aktualnje šćitane boki.',
'protectedarticle'            => 'Bok „[[$1]]“ jo se šćitał.',
'modifiedarticleprotection'   => 'Šćitanska rownina za „[[$1]]“ jo se změniła.',
'unprotectedarticle'          => 'jo šćit za „[[$1]]“ wótpórał',
'movedarticleprotection'      => 'šćitowe nastajenja z "[[$2]]" do "[[$1]]" psésunjone',
'protect-title'               => 'Šćit boka „$1“ změniś',
'prot_1movedto2'              => '„[[$1]]“ pśesunjone na „[[$2]]“',
'protect-legend'              => 'Šćitanje wobkšuśiś',
'protectcomment'              => 'Pśicyna:',
'protectexpiry'               => 'cas wótběžy:',
'protect_expiry_invalid'      => 'Zapódany cas jo njekorektny.',
'protect_expiry_old'          => 'Zapódany cas jo wótběžał.',
'protect-unchain-permissions' => 'Dalšne šćitne opcije pśipušćiś',
'protect-text'                => "How móžoš status šćita boka '''<nowiki>$1</nowiki>''' wobglědowaś a jen změniś.",
'protect-locked-blocked'      => "Njamóžoš status šćita togo boka změniś, dokulaž jo twójo wužywarske konto se blokěrowało. How su aktualne nastajenja šćita za bok '''„$1“:'''.",
'protect-locked-dblock'       => "Datowa banka jo zamknjona a toś njejo móžno šćit boka změniś. How su aktualne nastajenja šćita za bok '''„$1“:'''.",
'protect-locked-access'       => "Wašo wužywarske konto njama notne pšawa za změnu šćita toś togo boka. How su aktualne nastajenja šćita boka '''„$1“:'''.",
'protect-cascadeon'           => 'Toś ten bok jo tuchylu šćitany, dokulaž jo zawězany do {{PLURAL:$1|slědujucego boka|slědujuceju bokowu|slědujucych bokow}}, źož kaskadowy šćit jo aktiwěrowany. Status šćita móžo se za toś ten bok změniś, to ale njewówliwujo kaskadowy šćit:',
'protect-default'             => 'Wšyknym wužywarjam dowóliś',
'protect-fallback'            => 'Slědujuce pšawo jo notne: „$1“.',
'protect-level-autoconfirmed' => 'Nowych a njeregistrěrowanych wužywarjow blokěrowaś',
'protect-level-sysop'         => 'Jano administratory',
'protect-summary-cascade'     => 'kaskaděrujucy',
'protect-expiring'            => 'kóńcy $1 (UTC)',
'protect-expiry-indefinite'   => 'njewobgranicowany',
'protect-cascade'             => 'Kaskaděrujucy šćit – wšykne pśedłogi, kótarež su zawězane do toś togo boka, tejerownosći se zamknu.',
'protect-cantedit'            => 'Njamóžoš šćitne rowniny toś tego boka změniś, dokulaž njamaš dowólnosć toś ten bok wobźěłaś.',
'protect-othertime'           => 'Drugi cas:',
'protect-othertime-op'        => 'drugi cas',
'protect-existing-expiry'     => 'Eksistěrujucy cas pśepadnjenja: $2, $3',
'protect-otherreason'         => 'Druga/pśidatna pśicyna:',
'protect-otherreason-op'      => 'Druga pśicyna',
'protect-dropdown'            => '*Powšykne šćitowe pśicyny
** Ekscesiwny wanadalizm
** Ekscesiwne spamowanje
** Wobźěłańska wójna
** Bok z wusokim datowym wobchadom',
'protect-edit-reasonlist'     => 'Šćitne pśicyny wobźěłaś',
'protect-expiry-options'      => '1 góźina:1 hour,1 źeń:1 day,1 tyźeń:1 week,2 tyźenja:2 weeks,1 mjasec:1 month,3 mjasece:3 months,6 mjasecy:6 months,1 lěto:1 year,na nimjer:infinite',
'restriction-type'            => 'Status šćita',
'restriction-level'           => 'Rownina šćita:',
'minimum-size'                => 'Minimalna wjelikosć',
'maximum-size'                => 'maksimalna wjelikosć:',
'pagesize'                    => '(byty)',

# Restrictions (nouns)
'restriction-edit'   => 'wobźěłaś',
'restriction-move'   => 'pśesunuś',
'restriction-create' => 'Natwóriś',
'restriction-upload' => 'Nagraś',

# Restriction levels
'restriction-level-sysop'         => 'połnje šćitane',
'restriction-level-autoconfirmed' => 'poł šćitane',
'restriction-level-all'           => 'wšykne',

# Undelete
'undelete'                     => 'Wulašowane boki woglědaś',
'undeletepage'                 => 'Wulašowane boki pokazaś a wótnowiś.',
'undeletepagetitle'            => "'''Slědujuce wudaśe wobstoj z wulašowanych wersijow wót [[:$1|$1]]'''.",
'viewdeletedpage'              => 'Wulašowane boki pokazaś',
'undeletepagetext'             => '{{PLURAL:$1|Slědujucy bok jo se wulašował, ale jo|Slědujucej $1 boka stej se wulašowałej, ale stej|Slědujuce $1 boki su se wulašowali, ale su|Slědujucych $1 bokow jo se wulašowało, ale jo}} hyšći w archiwje a {{PLURAL:$1|dajo|dajotej|daju|dajo}} se nawrośiś.
Archiw dajo se periodiski wuprozniś.',
'undelete-fieldset-title'      => 'Wersije wótnowiś',
'undeleteextrahelp'            => "Aby wšyknymi wersijami boka nawrośiś, wóstaj wšykne kontrolowe kašćiki prozne a klikni na zapódaj '''''Nawrośíś'''''.
Aby jano wěste wersije nawrośił, wubjeŕ kašćiki, kótarež wótpowěduju wersijam, kótarež maju se nawrośiś a klikni na '''''Nawrośiś'''''.
Kliknjenje na '''''Pśetergnuś''''' wuprozni komentarne pólo a wšykne kontrolowe kašćiki.",
'undeleterevisions'            => '$1 {{PLURAL:$1|wersija archiwěrowana|wersiji archiwěrowanej|wersije archiwěrowane}}',
'undeletehistory'              => 'Jolic nawrośijoš bok, nawrośiju se wšykne wersije stawiznow.
Joli až jo se wutwórił nowy bok ze samskim mjenim wót casa wulašowanja, nawrośone wersije zjawiju se w  pjerwješnych stawiznach.',
'undeleterevdel'               => 'Nawrośenje njejo móžne, gaž wjeźo k nejwušemu bokoju abo datajowej wersiji, kótaraž se pó źělach lašujo.
W takich padach dejš nejnowše wulašowane wersije markěroanje abo schowanje wótpóraś.',
'undeletehistorynoadmin'       => 'Toś ten bok jo se wulašował. Pśicyna wulašowanja pokazujo se w zespominanju. Tam stoje teke nadrobnosći wó wužywarjach, kótarež su bok pśed wulašowanim wobźěłali. Aktualny tekst toś tych wulašowanych wersijow jo jano administratoram pśistupny.',
'undelete-revision'            => 'Wulašowana wersija boka $1 (wót $4, $5) wót $3:',
'undeleterevision-missing'     => 'Njepłaśeca abo felujuca wersija. Snaź jo link wopacny abo wersija jo z archiwa se nawrośiła resp. wulašowała.',
'undelete-nodiff'              => 'Žedne něgajšne wersije',
'undeletebtn'                  => 'Wótnowiś',
'undeletelink'                 => 'woglědaś se/wótnowiś',
'undeleteviewlink'             => 'woglědaś se',
'undeletereset'                => 'Slědk wześ',
'undeleteinvert'               => 'Wuběrk pśewobrośiś',
'undeletecomment'              => 'Pśicyna:',
'undeletedarticle'             => 'bok „[[$1]]“ nawrośony',
'undeletedrevisions'           => '{{PLURAL:$1|1 wersija jo se nawrośiła|$1 wersiji stej se nawrośiłej|$1 wersije su se nawrośili}}.',
'undeletedrevisions-files'     => '{{PLURAL:$1|1 wersija|$1 wersiji|$1 wersije}} a {{PLURAL:$2|1 dataja|$2 dataji|$2 dataje}} {{PLURAL:$2|jo se nawrośiła|stej se nawrośiłej|su se nawrośili}}.',
'undeletedfiles'               => '{{PLURAL:$1|1 dataja jo se nawrośiła|$1 dataji stej se nawrośiłej|$1 dataje su se nawrośili}}.',
'cannotundelete'               => 'Nawrośenje njejo se zglucyło; něchten drugi jo bok južo nawrośił.',
'undeletedpage'                => "Bok '''$1''' jo se nawrośił.

W [[Special:Log/delete|log-lisćinje wulašowanjow]] namakajoš pśeglěd wulašowanych a nawrośonych bokow.",
'undelete-header'              => 'Gano wulašowane boki wiźiš w [[Special:Log/delete|log-lisćinje wulašowanjow]].',
'undelete-search-box'          => 'Wulašowane boki pytaś',
'undelete-search-prefix'       => 'Pokaž boki, kótarež zachopiju z:',
'undelete-search-submit'       => 'Pytaś',
'undelete-no-results'          => 'W archiwje wulašowanych bokow žeden bok pytanemu słowoju njewótpowědujo.',
'undelete-filename-mismatch'   => 'Njejo móžno było, datajowu wersiju z casowym kołkom $1 nawrośiś: Datajowej mjeni se njemakatej.',
'undelete-bad-store-key'       => 'Njejo móžno było, wersiju z casowym kołkom $1 nawrośiś: Dataja južo pśed wulašowanim njejo eksistěrowała.',
'undelete-cleanup-error'       => 'Zmólka pśi wulašowanju njewužywaneje archiwneje dataje $1.',
'undelete-missing-filearchive' => 'Njejo móžno, archiwnu dataju ID $1 nawrośiś. Wóna južo w datowej bance njejo. Snaź jo južo raz se nawrośiła.',
'undelete-error-short'         => 'Zmólka pśi nawrośenju dataje: $1',
'undelete-error-long'          => 'Zmólki pśi nawrośenju dataje:

$1',
'undelete-show-file-confirm'   => 'Coš se napšawdu wulašowanu wersiju dataje "<nowiki>$1</nowiki>" wót $2 $3 woglědaś?',
'undelete-show-file-submit'    => 'Jo',

# Namespace form on various pages
'namespace'      => 'Mjenjowy rum:',
'invert'         => 'Wuběr wobrośiś',
'blanknamespace' => '(Nastawki)',

# Contributions
'contributions'       => 'Wužywarske pśinoski',
'contributions-title' => 'Wužywarske pśinoski wót $1',
'mycontris'           => 'móje pśinoski',
'contribsub2'         => 'Za $1 ($2)',
'nocontribs'          => 'Za toś te kriterije njejsu žedne změny se namakali.',
'uctop'               => '(aktualny)',
'month'               => 'wót mjaseca (a jěsnjej):',
'year'                => 'wót lěta (a jěsnjej):',

'sp-contributions-newbies'             => 'Pśinoski jano za nowych wužywarjow pokazaś',
'sp-contributions-newbies-sub'         => 'Za nowackow',
'sp-contributions-newbies-title'       => 'Wužywarske pśinoski nowych kontow',
'sp-contributions-blocklog'            => 'Protokol blokěrowanjow',
'sp-contributions-deleted'             => 'Wulašowane wužywarske pśinoski',
'sp-contributions-uploads'             => 'nagraśa',
'sp-contributions-logs'                => 'protokole',
'sp-contributions-talk'                => 'diskusija',
'sp-contributions-userrights'          => 'Zastojanje wužywarskich pšawow',
'sp-contributions-blocked-notice'      => 'Toś ten wužywaŕ jo tuchylu blokěrowany. Nejnowšy zapisk blokěrowańskego protokola pódawa se dołojce ako referenca:',
'sp-contributions-blocked-notice-anon' => 'Toś ta IP-adresa jo tuchylu zablokěrowana.
Nejnowšy zapisk protokola blokěrowanjow pódawa se dołojce ako referenca:',
'sp-contributions-search'              => 'Pśinoski pytaś',
'sp-contributions-username'            => 'IP-adresa abo wužywarske mě:',
'sp-contributions-toponly'             => 'Jano wuše wersije pokazaś',
'sp-contributions-submit'              => 'Pytaś',

# What links here
'whatlinkshere'            => 'Wótkaze na toś ten bok',
'whatlinkshere-title'      => 'Boki, kótarež wótkazuju na "$1"',
'whatlinkshere-page'       => 'bok:',
'linkshere'                => "Toś te boki wótkazuju na '''„[[:$1]]“''':",
'nolinkshere'              => "Žedne boki njewótkazuju na '''[[:$1]]'''.",
'nolinkshere-ns'           => "Žedne boki we wubranem mjenjowem rumje njewótkazuju na '''[[:$1]]'''.",
'isredirect'               => 'dalejpósrědnjujucy bok',
'istemplate'               => 'zawězanje pśedłogi',
'isimage'                  => 'datajowy wótkaz',
'whatlinkshere-prev'       => '{{PLURAL:$1|zachadny|zachadnej|zachadne $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|pśiducy|pśiducej|pśiduce $1}}',
'whatlinkshere-links'      => '← wótkaze',
'whatlinkshere-hideredirs' => '$1 pśesměrowań',
'whatlinkshere-hidetrans'  => 'Zapśěgnjenja $1',
'whatlinkshere-hidelinks'  => '$1 wótkazow',
'whatlinkshere-hideimages' => 'Datajowe wótkaze $1',
'whatlinkshere-filters'    => 'filtry',

# Block/unblock
'blockip'                         => 'Wužywarja blokěrowaś',
'blockip-title'                   => 'Wužywarja blokěrowaś',
'blockip-legend'                  => 'Wužywarja blokěrowaś',
'blockiptext'                     => 'Wužywaj slědujucy formular, jolic až coš wěstej IP-adresy abo konkretnemu wužywarjeju pśistup znjemóžniś. Take dejało se pó [[{{MediaWiki:Policy-url}}|směrnicach]] jano staś, aby se wandalizmoju zadorało. Pšosym zapódaj pśicynu za twójo blokěrowanje (na pś. mógu se citěrowaś konkretne boki, źo jo se wandalěrowało).',
'ipaddress'                       => 'IP-adresa',
'ipadressorusername'              => 'IP-adresa abo wužywarske mě',
'ipbexpiry'                       => 'Cas blokěrowanja:',
'ipbreason'                       => 'Pśicyna:',
'ipbreasonotherlist'              => 'Druga pśicyna',
'ipbreason-dropdown'              => '*powšykne pśicyny blokěrowanja
** pódawanje njepšawych informacijow
** wulašowanje wopśimjeśa bokow
** pódawanje spamowych eksternych wótkazow
** pisanje głuposćow na bokach
** pśestupjenje zasady "žedne wósobinske atakěrowanja"
** złowólne wužywanje wjele wužywarskich kontow
** njekorektne wužywarske mě',
'ipbanononly'                     => 'Jano anonymnych wužywarjow blokěrowaś',
'ipbcreateaccount'                => 'Twórjenje wužywarskich kontow znjemóžniś',
'ipbemailban'                     => 'pósłanje e-mailow znjemóžniś',
'ipbenableautoblock'              => 'Awtomatiske blokěrowanje slědneje wót togo wužywarja wužywaneje IP-adresy a wšyknych slědujucych adresow, wót kótarychž wopytajo boki wobźěłaś.',
'ipbsubmit'                       => 'Togo wužywarja blokěrowaś.',
'ipbother'                        => 'Drugi cas:',
'ipboptions'                      => '2 góźinje:2 hours,1 źeń:1 day,3 dny:3 days,1 tyźeń:1 week,2 tyźenja:2 weeks,1 mjasec:1 month,3 mjasece:3 months,6 mjasecy:6 months,1 lěto:1 year,na nimjer:infinite',
'ipbotheroption'                  => 'drugi',
'ipbotherreason'                  => 'Hynakša/dalšna pśicyna:',
'ipbhidename'                     => 'Wužywarske mě w změnach a lisćinach schowaś',
'ipbwatchuser'                    => 'Wužywarski a diskusijny bok toś togo wužywarja wobglědowaś',
'ipballowusertalk'                => 'Toś tomu wužywarjeju dowóliś swój diskusijny bok wobźěłaś, mjazytm až jo blokěrowany.',
'ipb-change-block'                => 'Wužywarja z toś tymi nastajenjami znowego blokěrowaś',
'badipaddress'                    => 'IP-adresa jo njekorektna',
'blockipsuccesssub'               => 'Wuspěšnje blokěrowane',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] jo se blokěrował.<br />
Glědaj do [[Special:IPBlockList|lisćiny aktiwnych blokěrowanjow]], aby blokěrowanja pśeglědał.',
'ipb-edit-dropdown'               => 'Pśicyny blokěrowanja wobźěłaś',
'ipb-unblock-addr'                => '$1 dopušćiś',
'ipb-unblock'                     => 'Wužywarske mě abo IP-adresu dopušćiś',
'ipb-blocklist'                   => 'Wšykne aktualne blokěrowanja pokazaś',
'ipb-blocklist-contribs'          => 'Pśinoski za $1',
'unblockip'                       => 'Wužywarja dopušćiś',
'unblockiptext'                   => 'Z pomocu dołojcnego formulara móžotej IP-adresa abo wužywaŕ zasej se dopušćiś.',
'ipusubmit'                       => 'Toś to blokěrowanje wótpóraś',
'unblocked'                       => 'Wužywaŕ [[User:$1|$1]] jo zasej se dopušćił.',
'unblocked-id'                    => '$1 jo se dopušćił(a).',
'ipblocklist'                     => 'Blokěrowane wužywarje',
'ipblocklist-legend'              => 'Blokěrowanego wužywarja pytaś',
'ipblocklist-username'            => 'Wužywarske mě abo IP-adresa:',
'ipblocklist-sh-userblocks'       => 'Kontowe blokěrowanja $1',
'ipblocklist-sh-tempblocks'       => 'nachylne blokěrowanja $1',
'ipblocklist-sh-addressblocks'    => 'Blokěrowanja jadnotliwych IP $1',
'ipblocklist-submit'              => 'Pytaś',
'ipblocklist-localblock'          => 'Lokalne blokěrowanje',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|Druge blokěrowanje|Drugej blokěrowani|Druge blokěrowanja|Druge blokěrowanja}}',
'blocklistline'                   => '$1, $2 jo blokěrował $3 (až do $4)',
'infiniteblock'                   => 'njewobgranicowany',
'expiringblock'                   => 'pśepadnjo $1 $2',
'anononlyblock'                   => 'jano anonymne',
'noautoblockblock'                => 'awtomatiske blokěrowanje znjemóžnjone',
'createaccountblock'              => 'wutwórjenje wužywarskich kontow znjemóžnjone',
'emailblock'                      => 'Pósłanje e-mailow jo se blokěrowało.',
'blocklist-nousertalk'            => 'njemóžno swójski diskusijny bok wobźěłaś',
'ipblocklist-empty'               => 'Lisćina jo prozna.',
'ipblocklist-no-results'          => 'Póžedana IP-Adresa abo wužywarske mě njejstej blokěrowanej.',
'blocklink'                       => 'blokěrowaś',
'unblocklink'                     => 'dopušćiś',
'change-blocklink'                => 'Blokěrowanje změniś',
'contribslink'                    => 'pśinoski',
'autoblocker'                     => 'Awtomatiski blokěrowany, dokulaž twója IP-adresa jo se rowno wót "[[User:$1|$1]]". Pśicyna za blokěrowanje wužywarja $1 jo: "$2".',
'blocklogpage'                    => 'Protokol blokěrowanjow',
'blocklog-showlog'                => 'Toś ten wužywaŕ jo se pjerwjej zablokěrował. Protokol blokěrowanjow pódawa se dołojce ako referenca:',
'blocklog-showsuppresslog'        => 'Toś ten wužywaŕ jo se pjerwjej zablokěrował a schował. Protokol pódtłocowanjow pódawa se dołojce ako referenca:',
'blocklogentry'                   => '[[$1]] blokěrujo se na $2 $3',
'reblock-logentry'                => 'jo změnił blokěrowańske nastajenja za [[$1]] z casom spadnjenja $2 $3',
'blocklogtext'                    => 'To jo protokol blokěrowanjow a dopušćenjow.
IP-adresy, ako su awtomatiski se blokěrowali, se njepokažu.
Na boce [[Special:IPBlockList|Lisćina blokěrowanych IP-adresow a wužywarskich mjenjow]] jo móžno, akualne blokěrowanja pśeglědowaś.',
'unblocklogentry'                 => 'jo $1 zasej dopušćił',
'block-log-flags-anononly'        => 'jano anonymne',
'block-log-flags-nocreate'        => 'stwórjenje konta jo se znjemóžniło',
'block-log-flags-noautoblock'     => 'awtomatiske blokěrowanje jo deaktiwěrowane',
'block-log-flags-noemail'         => 'e-mailowanje jo blokěrowane',
'block-log-flags-nousertalk'      => 'njejo móžno swójski diskusijny bok wobźěłaś',
'block-log-flags-angry-autoblock' => 'pólěpšone awtomatsike blokěrowanje zmóžnjone',
'block-log-flags-hiddenname'      => 'wužywarske mě schowane',
'range_block_disabled'            => 'Móžnosć administratora, blokěrowaś cełe adresowe rumy, njejo aktiwěrowana.',
'ipb_expiry_invalid'              => 'Pódany cas jo njepłaśecy.',
'ipb_expiry_temp'                 => 'Blokěrowanja schowanych wužywarskich mjenjow deje permanentne byś.',
'ipb_hide_invalid'                => 'Njejo móžno toś to konto pódtłocyś; jo snaź pśewjele změnow.',
'ipb_already_blocked'             => '"$1" jo južo blokěrowany.',
'ipb-needreblock'                 => '== Južo zablokěrowany ==
$1 jo južo zablokěrowany. Coš nastajenja změniś?',
'ipb-otherblocks-header'          => '{{PLURAL:$1|Druge blokěrowanje|Drugej blokěrowani|Druge blokěrowanja|Druge blokěrowanja}}',
'ipb_cant_unblock'                => 'Zmólka: Blokěrowańska ID $1 njejo se namakała. Blokěrowanje jo było južo wótpórane.',
'ipb_blocked_as_range'            => 'Zmólka: IP-adresa $1 njejo direktnje blokěrowana a njeda se wótblokěrowaś. Jo pak ako źěl wobcerka $2 blokěrowana, kótaryž da se wótblokěrowaś.',
'ip_range_invalid'                => 'Njepłaśecy wobłuk IP-adresow.',
'ip_range_toolarge'               => 'Wobcerkowe bloki, kótarež su wětše ako /$1, njejsu dowólone.',
'blockme'                         => 'blokěruj mě',
'proxyblocker'                    => 'Blokěrowanje proxy',
'proxyblocker-disabled'           => 'Toś ta funkcija jo znjemóžnjona.',
'proxyblockreason'                => 'Twója IP-adresa jo se blokěrowała, dokulaž jo wócynjony proxy. Pšosym kontaktěruj swójogo seśowego providera abo swóje systemowe administratory a informěruj je wó toś tom móžnem wěstotnem problemje.',
'proxyblocksuccess'               => 'Gótowe.',
'sorbsreason'                     => 'Twója IP-adresa jo w DNSBL we {{GRAMMAR:lokatiw|{{SITENAME}}}} zapisana ako wócynjony proxy.',
'sorbs_create_account_reason'     => 'Twója IP-adresa jo w DNSBL {{GRAMMAR:genitiw|{{SITENAME}}}} ako wócynjony proxy zapisana. Njejo móžno, nowe wužywarske konta załožowaś.',
'cant-block-while-blocked'        => 'Njesmějoš žednych drugich wužywarjow blokěrowaś, mjaztym až ty sy blokěrowany.',
'cant-see-hidden-user'            => 'Wužywaŕ, kótaregož wopytujoš blokěrowaś, jo južo zablokěrowany a schowany. Dokulaž njamaš pšawo wužywarja schowaś, njamóžoš blokěrowanje wužywarja wiźeś abo wobźěłaś.',
'ipbblocked'                      => 'Njamóžoš drugich wužywarjow blokěrowaś abo wótblokěrowaś, dokulaž ty sam jo zablokěrowany',
'ipbnounblockself'                => 'Njesmějoš se samogo wótblokěrowaś',

# Developer tools
'lockdb'              => 'Datowu banku zamknuś',
'unlockdb'            => 'Datowu banku zasej spśistupniś',
'lockdbtext'          => 'Zamknjenje datoweje banki znjemóžnijo wšyknym wužywarjam boki wobźěłaś, swóje nastajenja změnjaś, swóju wobglědowańku wobźěłaś a druge źěła wugbaś, kótarež pominaju změnu w datowej bance. Pšosym wobkšuś, až coš něnto datowu banku zamknuś a zasej dopušćiś, gaž sy swóje změny pśewjadł.',
'unlockdbtext'        => 'Spśistupnjenje datoweje banki zmóžnijo wšyknym wužywarjam boki wobźěłaś, swóje nastajenja změnjaś, swóju wobglědowańku wobźěłaś a druge źěła wugbaś, kótarež pominaju změnu w datowej bance. Pšosym wobkšuś, až coš datowu banku zasej spśistupniś.',
'lockconfirm'         => 'Jo, datowu banku com napšawdu zamknuś.',
'unlockconfirm'       => 'Jo, datowu banku com napšawdu zasej spśistupniś.',
'lockbtn'             => 'Datowu banku zamknuś',
'unlockbtn'           => 'Datowu banku zasej spśistupniś',
'locknoconfirm'       => 'Njejsy hyšći wobkšuśił.',
'lockdbsuccesssub'    => 'Datowa banka jo zamknjona.',
'unlockdbsuccesssub'  => 'Datowa banka jo zasej se spśistupniła.',
'lockdbsuccesstext'   => 'Datowa banka jo zamknjona.
<br />Njezabydń ju [[Special:UnlockDB|zasej spśistupniś]], gaž swójo zeźěłajoš.',
'unlockdbsuccesstext' => 'Datowa banka jo zasej pśistupna.',
'lockfilenotwritable' => 'Njejo móžno, blokěrowansku dataju datoweje banki změniś. Coš-lic datowu banku zamknuś abo zasej spśistupniś, dej webowy serwer měś pšawo, do njeje pisaś.',
'databasenotlocked'   => 'Datowa banka njejo zamknjona.',

# Move page
'move-page'                    => '$1 pśesunuś',
'move-page-legend'             => 'Bok pśesunuś',
'movepagetext'                 => "Z pomocu slědujucego formulara móžoš bok pśemjenjowaś, pśi comž se jogo wersije k nowemu mjenjoju pśesuwaju.
Stary titel wordujo dalejpósrědnjeński bok k nowemu titeloju.
Móžoš awtomatiski aktualizěrowaś dalejposrědkowanja, kótarež pokazuju na originalny titel.
Jolic njocoš, pśeglědaj za [[Special:DoubleRedirects|dwójnymi]] abo [[Special:BrokenRedirects|defektnymi daleposrědkowanjami]].
Sy zagronity, až wótkaze wjedu tam, źož maju wjasć.

Źiwaj na to, až se bok '''nje'''pśesuwa, jolic jo južo bok z nowym titelom, snaźkuli jo prozny abo dalejpósrědnjenje a njama stare wobźěłane wersije. To ma groniś, až móžoš bok zasej slědk pśemjenjowaś, jolic cyniš zmólku, a njemóžoš eksistěrujucy bok pśepisaś.

'''WARNOWANJE!'''
To móžo byś drastiska a njewocakowana změna za popularny bok;
pšosym zawěsć, až konsekwency rozmijoš, nježli až pókšacujoš.",
'movepagetext-noredirectfixer' => "Z pomocu slědujucego formulara móžoš bok pśemjenjowaś, pśi comž se jogo wersije k nowemu mjenjoju pśesuwaju.
Stary titel wordujo dalejpósrědnjeński bok k nowemu titeloju.
Móžoš awtomatiski aktualizěrowaś dalejposrědkowanja, kótarež pokazuju na originalny titel.
Jolic njocoš, pśeglědaj za [[Special:DoubleRedirects|dwójnymi]] abo [[Special:BrokenRedirects|defektnymi daleposrědkowanjami]].
Sy zagronity, až wótkaze wjedu tam, źož maju wjasć.

Źiwaj na to, až se bok '''nje'''pśesuwa, jolic jo južo bok z nowym titelom, snaźkuli jo prozny abo dalejpósrědnjenje a njama stare wobźěłane wersije. To ma groniś, až móžoš bok zasej slědk pśemjenjowaś, jolic cyniš zmólku, a njemóžoš eksistěrujucy bok pśepisaś.

'''WARNOWANJE!'''
To móžo byś drastiska a njewocakowana změna za popularny bok;
pšosym źiwaj na to, až rozumijoš konsekwency, nježli až pókšacujoš.",
'movepagetalktext'             => "Pśisłušny diskusijny bok se sobu pśesunjo, '''ale nic gaž:'''
* eksistěrujo južo diskusijny bok z toś tym mjenim, abo gaž
* wótwólijoš toś tu funkciju.

W toś tyma padoma dej wopśimjeśe boka manualnje se pśesunuś resp. gromadu wjasć, jolic až to coš.",
'movearticle'                  => 'Bok pśesunuś',
'moveuserpage-warning'         => "'''Warnowanje:''' Coš rowno wužywarski bok pśesunuś. Pšosym wobmysli, až jano bok se pśesunjo a wužiwaŕ '''nje'''buźo se pśemjenjowaś.",
'movenologin'                  => 'Njepśizjawjony(a)',
'movenologintext'              => 'Musyš zregistrěrowany wužywaŕ a [[Special:UserLogin|pśizjawjony]] byś, aby pśesunuł bok.',
'movenotallowed'               => 'Njamaš pšawo pśesuwaś boki.',
'movenotallowedfile'           => 'Njamaš pšawo dataje pśesunuś.',
'cant-move-user-page'          => 'Njamaš pšawo wužywarske boki pśesunuś (mimo pódbokow).',
'cant-move-to-user-page'       => 'Njamaš pšawo bok k wužywarskemu bokoju pśesunuś (z wuwześim k wužywarskemu pódbokoju).',
'newtitle'                     => 'nowy nadpis:',
'move-watch'                   => 'Toś ten bok wobglědowaś',
'movepagebtn'                  => 'Bok pśesunuś',
'pagemovedsub'                 => 'Bok jo se pśesunuł.',
'movepage-moved'               => '\'\'\'Bok "$1" jo se do "$2" pśesunuł.\'\'\'',
'movepage-moved-redirect'      => 'Dalejpósrědnjenje jo se napórało.',
'movepage-moved-noredirect'    => 'Napóranje dalejpósrědnjenja jo se pódtłocyło.',
'articleexists'                => 'Bok z takim mjenim južo eksistěrujo abo mě, kótarež sćo wuwzólił jo njepłaśece. Pšosym wuzwól nowe mě.',
'cantmove-titleprotected'      => 'Njamóžoš bok k toś tomu městnoju pśesunuś, dokulaž nowy titel jo pśeśiwo napóranjeju šćitany.',
'talkexists'                   => 'Samy bok jo se pśesunuł, ale pśisłušny diskusijny bok nic, dokulaž eksistěrujo južo taki bok z nowym mjenim. Pšosym pśirownaj wopśimjeśi manualnje.',
'movedto'                      => 'pśesunjony do',
'movetalk'                     => 'Diskusijny bok sobu pśesunuś.',
'move-subpages'                => 'Wše pódboki (až do $1) pśesunuś',
'move-talk-subpages'           => 'Wše pódboki diskusijnego boka  (až do $1) pśesunuś',
'movepage-page-exists'         => 'Bok $1 južo eksistěrujo a njedajo se awtomatiski pśepisaś.',
'movepage-page-moved'          => 'Bok $1 jo se do $2 pśesunuł.',
'movepage-page-unmoved'        => 'Bok $1 njejo se do $2 pśesunuś dał.',
'movepage-max-pages'           => 'Maksimalna licba $1 {{PLURAL:$1|boka|bokowu|bokow|bokow}} jo se pśesunuła a žedne dalšne wěcej njedaje se awtomatiski pśesunuś.',
'1movedto2'                    => '„[[$1]]“ pśesunjone na „[[$2]]“',
'1movedto2_redir'              => 'Nastawk „[[$1]]“ jo se pśesunuł na „[[$2]]“. Pśi tom jo jadno dalejpósrědnjenje se pśepisało.',
'move-redirect-suppressed'     => 'dalejpósrědnjenje pódtłocone',
'movelogpage'                  => 'Protokol pśesunjenjow',
'movelogpagetext'              => 'How jo lisćina wšyknych pśesunjonych bokow.',
'movesubpage'                  => '{{PLURAL:$1|Pódbok|Pódboka|Pódboki|Pódbokow}}',
'movesubpagetext'              => 'Bok ma {{PLURAL:$1|slědujucy pódbok|slědujucej $1 pódboka|slědujuce $1 pódboki|slědujucych $1 pódbokow}}.',
'movenosubpage'                => 'Toś ten bok njama pódboki.',
'movereason'                   => 'Pśicyna:',
'revertmove'                   => 'nawrośiś',
'delete_and_move'              => 'Wulašowaś a pśesunuś',
'delete_and_move_text'         => '==Celowy bok eksistěrujo - wulašowaś??==

Bok „[[:$1]]“ južo eksistěrujo. Coš jen wulašowaś, aby mógał toś ten bok pśesunuś?',
'delete_and_move_confirm'      => 'Jo, toś ten bok wulašowaś',
'delete_and_move_reason'       => 'wulašowane, aby było městno za pśesunjenje',
'selfmove'                     => 'Wuchadne a celowe mě stej identiskej; njejo móžno, bok na sam se pśesunuś.',
'immobile-source-namespace'    => 'Boki w mjenjowem rumje "$1" njedaju se pśesunuś',
'immobile-target-namespace'    => 'Boki njedaju se do mjenjowego ruma "$1" pśesunuś',
'immobile-target-namespace-iw' => 'Interwiki-wótkaz njejo płaśiwy cel za pśesunjenja bokow.',
'immobile-source-page'         => 'Toś ten bok njedajo se pśesunuś.',
'immobile-target-page'         => 'Njejo móžno na toś ten celowy bok pśesunuś.',
'imagenocrossnamespace'        => 'Dataja njedajo se pśesunuś do mjenjowego ruma, kótarež njejo za dataje.',
'nonfile-cannot-move-to-file'  => 'Njedataje njedaje se do datajowego mjenjowego ruma pśesunuś',
'imagetypemismatch'            => 'Nowy datajowy sufiks swójomu typoju njewótpowědujo',
'imageinvalidfilename'         => 'Mě celoweje dataje jo njepłaśiwe',
'fix-double-redirects'         => 'Dalejpósrědnjenja, kótarež wótkazuju na originalny titel, aktualizěrowaś',
'move-leave-redirect'          => 'Daleposrědnjenje zawóstajiś',
'protectedpagemovewarning'     => "'''WARNOWANJE:''' Toś ten bok jo se zastajił, aby jano wužiwarje z pšawami administratora mógli jen pśesunuś.
Nejnowšy protokolowy zapisk jo dołojce ako referenca pódany:",
'semiprotectedpagemovewarning' => "'''Glědaj:''' Toś ten bok jo se zastajił, aby jano zregistrěrowane wužywarje mógli jen pśesunuś.
Nejnowšy protokolowy zapisk jo dołojce ako referenca pódany:",
'move-over-sharedrepo'         => '== Dataja eksistěrujo ==
[[:$1]] eksistěrujo w gromaźe wužywanem repozitoriumje. Pśesunjenje dataje k toś tomu titeloju buźo gromaźe wužywanu dataju pśepisowaś.',
'file-exists-sharedrepo'       => 'Wubrane datajowe mě wužywa se južo w gromaźe wužywanem repozitoriumje.
Pšosym wubjeŕ druge mě.',

# Export
'export'            => 'Boki eksportěrowaś',
'exporttext'        => 'Móžoš tekst a stawizny boka abo skupiny bokow, kótarež su w XML zapisane, eksportěrowaś. Jo móžno je do drugeje wiki importěrowaś pśeź MediaWiki [[Special:Import|bok importěrowanja]].

Za eksportěrowanje bokow zapódaj nadpisma do dołojcnego tekstowogo póla, jadno nadpismo na smužku, a wuzwól nowe a stare wersije z wótkazami stawiznow boka abo jano aktualnu wersiju z informacijami wó slědnjej změnje.

W slědnem padźe móžoš teke wótkaz wužywaś, na pś. [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] za bok "[[{{MediaWiki:Mainpage}}]]".',
'exportcuronly'     => 'Jano aktualne wersije, bźez stawiznow',
'exportnohistory'   => "----
'''Pokazka:''' Eksportěrowanje cełych stawiznow bokow pśez toś ten formular njejo dla performancowych pśicyn tuchylu móžne.",
'export-submit'     => 'Eksportěrowaś',
'export-addcattext' => 'Pśidaś boki z kategorije:',
'export-addcat'     => 'Dodaś',
'export-addnstext'  => 'Boki z mjenjowego ruma pśidaś:',
'export-addns'      => 'Pśidaś',
'export-download'   => 'Ako XML-dataju składowaś',
'export-templates'  => 'Pśedłogi zapśimjeś',
'export-pagelinks'  => 'Wótkazane boki zapśěgnuś, až k dłymoju wót:',

# Namespace 8 related
'allmessages'                   => 'Systemowe zdźělenja',
'allmessagesname'               => 'Mě',
'allmessagesdefault'            => 'Standardny tekst',
'allmessagescurrent'            => 'Aktualny tekst',
'allmessagestext'               => 'How jo lisćina systemowych powěsćow w mjenowem rumje MediaWiki.
Pšosym wobglědaj [http://www.mediawiki.org/wiki/Localisation lokalizaciju MediaWiki] a [http://translatewiki.net translatewiki.net], jolic coš k lokalizaciji MediaWiki pśinosowaś.',
'allmessagesnotsupportedDB'     => "'''{{ns:special}}:Allmessages''' njejo tuchylu móžno, dokulaž jo datowa banka offline.",
'allmessages-filter-legend'     => 'Filter',
'allmessages-filter'            => 'Pó pśiměrjeńskem stawje filtrěrowaś:',
'allmessages-filter-unmodified' => 'Njezměnjony',
'allmessages-filter-all'        => 'Wše',
'allmessages-filter-modified'   => 'Změnjony',
'allmessages-prefix'            => 'Pó prefiksu filtrěrowaś:',
'allmessages-language'          => 'Rěc:',
'allmessages-filter-submit'     => 'Wótpósłaś',

# Thumbnails
'thumbnail-more'           => 'Pówětšyś',
'filemissing'              => 'Dataja felujo',
'thumbnail_error'          => 'Zmólka pśi stwórjenju pśeglěda: $1',
'djvu_page_error'          => 'DjVu-bok pśesegujo wobłuk.',
'djvu_no_xml'              => 'Njejo móžno, XML za DjVu-dataju wótwołaś.',
'thumbnail_invalid_params' => 'Njepłaśece parametry pśeglěda',
'thumbnail_dest_directory' => 'Njejo móžno celowy zapis stwóriś.',
'thumbnail_image-type'     => 'Wobrazy typ se njepódpěra',
'thumbnail_gd-library'     => 'Njedopołna konfiguracija GD-biblioteki: felujuca funkcija $1',
'thumbnail_image-missing'  => 'Zda se, až dataja felujo: $1',

# Special:Import
'import'                     => 'Boki importěrowaś',
'importinterwiki'            => 'Transwiki-importěrowanje',
'import-interwiki-text'      => 'Wuzwól wiki a bok za importěrowanje.
Datumy wersijow a wužywarske mjenja pśi tym se njezměniju.
Wšykne transwiki-importowe akcije protokolěruju se w [[Special:Log/import|log-lisćinje importow]].',
'import-interwiki-source'    => 'Žrědłowy wiki/bok:',
'import-interwiki-history'   => 'Importěruj wšykne wersije toś togo boka',
'import-interwiki-templates' => 'Wše pśedłogi zapśěgnuś',
'import-interwiki-submit'    => 'Importěrowaś',
'import-interwiki-namespace' => 'Celowy mjenjowy rum:',
'import-upload-filename'     => 'Datajowe mě:',
'import-comment'             => 'Komentar:',
'importtext'                 => 'Eksportěruj pšosym dataju ze žredlowego wikija z pomocu [[Special:Export|eksporteje funkcije]]. Składuj ju na swójom licadle a nagraj ju sem.',
'importstart'                => 'Importěrowanje bokow...',
'import-revision-count'      => '$1 {{PLURAL:$1|wersija|wersiji|wersije}}',
'importnopages'              => 'Boki za importěrowanje njeeksistěruju.',
'imported-log-entries'       => '$1 {{PLURAL:$1|protokolowy zapisk importěrowany|protokolowej zapiska inmportěrowanej|protokolowe zapiski importěrowane|protokolowych zapiskow importěrowanych}}.',
'importfailed'               => 'Zmólka pśi importěrowanju: $1',
'importunknownsource'        => 'Njeznate źrědło importěrowanja.',
'importcantopen'             => 'Dataja za importěrowanje njejo se dała wócyniś.',
'importbadinterwiki'         => 'Njepłaśecy interwikijowy wótkaz',
'importnotext'               => 'Prozdne abo bźez teksta',
'importsuccess'              => 'Import dokóńcony!',
'importhistoryconflict'      => 'Konflikt wersijow (snaź jo toś ten bok južo raz se importěrował)',
'importnosources'            => 'Za transwikijowe importěrowanje njejsu žrědła definěrowane, direktne stawizny nagraśow su znjemóžnjone.',
'importnofile'               => 'Žedna dataja za importěrowanje njejo se nagrała.',
'importuploaderrorsize'      => 'Nagrawanje importoweje dataje jo se njeraźiło. Dataja jo wětša ako dowólona wjelikosć nagraśow.',
'importuploaderrorpartial'   => 'Nagrawanje importoweje dataje jo se njeraźiło. Dataja jo se jano pó źělach nagrała.',
'importuploaderrortemp'      => 'Nagrawanje importoweje dataje jo se njeraźiło. Temporarny zapis feluje.',
'import-parse-failure'       => 'Zmólka pśi XML-imporśe:',
'import-noarticle'           => 'Žeden bok za import!',
'import-nonewrevisions'      => 'Wšykne wersije buchu južo pjerwjej importowane.',
'xml-error-string'           => '$1 smužka $2, słup $3, (Byte $4): $5',
'import-upload'              => 'XML-daty nagraś',
'import-token-mismatch'      => 'Zgubjenje posejźeńskich datow. Pšosym wopytaj hyšći raz.',
'import-invalid-interwiki'   => 'Njejo móžno importěrowaś z pódanego wikija.',

# Import log
'importlogpage'                    => 'Log-lisćinu importěrowaś',
'importlogpagetext'                => 'Administratiwne importěrowanje bokow ze stawiznami z drugich wikijow.',
'import-logentry-upload'           => 'jo se [[$1]]  pśez nagrawańske nagraśe importěrowała.',
'import-logentry-upload-detail'    => '{{PLURAL:$1|$1 wersija|$1 wersiji|$1 wersije}}',
'import-logentry-interwiki'        => 'Dataja $1 jo se importěrowała (transwiki).',
'import-logentry-interwiki-detail' => '{{PLURAL:$1|$1 wersija|$1 wersiji|$1 wersije}} wót $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Twój wužywarski bok',
'tooltip-pt-anonuserpage'         => 'Wužywarski bok za IP-adresu, z kótarejuž bok wobźěłajoš',
'tooltip-pt-mytalk'               => 'Twój diskusijny bok',
'tooltip-pt-anontalk'             => 'Diskusija wó změnach z tuteje IP-adresy.',
'tooltip-pt-preferences'          => 'Móje pśistajenja',
'tooltip-pt-watchlist'            => 'Lisćina bokow, kótarež se wobglěduju',
'tooltip-pt-mycontris'            => 'Lisćina twójich pśinoskow',
'tooltip-pt-login'                => 'Pśizjawjenje njejo obligatoriske, ale lubje witane.',
'tooltip-pt-anonlogin'            => 'Pśizjawjenje njejo obligatoriske, ale lubje witane.',
'tooltip-pt-logout'               => 'Wótzjawiś',
'tooltip-ca-talk'                 => 'Diskusija wó wopśimjeśu boka',
'tooltip-ca-edit'                 => 'Móžoš bok wobźěłaś. Nježlic składujoš, wužywaj pšosym funkciju "pśeglěd".',
'tooltip-ca-addsection'           => 'Nowy wótrězk zachopiś',
'tooltip-ca-viewsource'           => 'Bok jo šćitany. Jo móžno, žrědłowy tekst woglědaś.',
'tooltip-ca-history'              => 'Něgajšne wersije togo boka.',
'tooltip-ca-protect'              => 'Toś ten bok šćitaś',
'tooltip-ca-unprotect'            => 'Šćit toś togo boka změniś',
'tooltip-ca-delete'               => 'Toś ten bok wulašowaś',
'tooltip-ca-undelete'             => 'Zapise pśed wulašowanim boka nawrośiś.',
'tooltip-ca-move'                 => 'Toś ten bok pśesunuś',
'tooltip-ca-watch'                => 'Dodaj toś ten bok do swójeje wobglědowańskeje lisćiny.',
'tooltip-ca-unwatch'              => 'Bok z wobglědowańskeje lisćiny wulašowaś',
'tooltip-search'                  => 'Pśepytaś {{SITENAME}}',
'tooltip-search-go'               => 'Źi direktnje na bok z toś tym mjenim.',
'tooltip-search-fulltext'         => 'Toś ten tekst w bokach pytaś',
'tooltip-p-logo'                  => 'Głowny bok',
'tooltip-n-mainpage'              => 'Glowny bok pokazaś',
'tooltip-n-mainpage-description'  => 'Hłownu stronu wopytać',
'tooltip-n-portal'                => 'Wó portalu, co móžoš cyniś, źo co namakajoš',
'tooltip-n-currentevents'         => 'Slězynowe informacije k aktualnym tšojenjam',
'tooltip-n-recentchanges'         => 'Lisćina aktualnych změnow w(e) {{SITENAME}}.',
'tooltip-n-randompage'            => 'Pśipadny bok',
'tooltip-n-help'                  => 'Pomocny bok pokazaś',
'tooltip-t-whatlinkshere'         => 'Lisćina wšyknych wiki bokow, kótarež how wótkazuju',
'tooltip-t-recentchangeslinked'   => 'Aktualne změny w bokach, na kótarež toś ten bok wótkazujo',
'tooltip-feed-rss'                => 'RSS-feed za toś ten bok',
'tooltip-feed-atom'               => 'Atom-feed za toś ten bok',
'tooltip-t-contributions'         => 'Pśinoski togo wužywarja wobglědowaś',
'tooltip-t-emailuser'             => 'Wužywarjeju e-mail pósłaś',
'tooltip-t-upload'                => 'Dataje nagraś',
'tooltip-t-specialpages'          => 'Lisćina wšyknych specialnych bokow',
'tooltip-t-print'                 => 'Śišćańska wersija boka',
'tooltip-t-permalink'             => 'Stawny wótkaz na toś tu wersiju boka',
'tooltip-ca-nstab-main'           => 'Wopśimjeśe pokazaś',
'tooltip-ca-nstab-user'           => 'Wužywarski bok pokazaś',
'tooltip-ca-nstab-media'          => 'Pokazaś bok medijow/datajow.',
'tooltip-ca-nstab-special'        => 'To jo specialny bok, kótaryž njedajo se wobźěłaś.',
'tooltip-ca-nstab-project'        => 'Portal pokazaś',
'tooltip-ca-nstab-image'          => 'Bok z datajami pokazaś',
'tooltip-ca-nstab-mediawiki'      => 'Systemowy tekst pokazaś',
'tooltip-ca-nstab-template'       => 'Pśedłogu pokazaś',
'tooltip-ca-nstab-help'           => 'Pomocny bok pokazaś',
'tooltip-ca-nstab-category'       => 'Bok kategorijow pokazaś',
'tooltip-minoredit'               => 'Změnu ako drobnu markěrowaś',
'tooltip-save'                    => 'Změny składowaś',
'tooltip-preview'                 => "Pšosym '''pśeglěd změnow''' wužywaś, nježlic až składujoš!",
'tooltip-diff'                    => 'Pokazujo změny teksta w tabelariskej formje.',
'tooltip-compareselectedversions' => 'Wuzwólonej wersiji boka pśirownowaś',
'tooltip-watch'                   => 'Toś ten bok wobglědowańce dodaś',
'tooltip-recreate'                => 'Bok nawrośiś, lěcrowno jo był wulašowany',
'tooltip-upload'                  => 'Nagraśe zachopiś',
'tooltip-rollback'                => '"Roolback" anulěrujo změny slědnego wužywarja na toś tom boku z jadnym kliknjenim.',
'tooltip-undo'                    => 'Anulěrujo toś tu změnu a wócynijo wobźěłański formular w pśeglědowem modusu.
W zespominanju dajo se pśicyna pódaś.',
'tooltip-preferences-save'        => 'Nastajenja składowaś',
'tooltip-summary'                 => 'Zapódaj krotke zespominanje',

# Stylesheets
'common.css'   => '/** Na toś tom městnje wustatkujo se CSS na wšykne šaty. */',
'monobook.css' => '/* How zaměstnjony CSS wustatkujo se na wužywarje monobook-šata */',

# Scripts
'common.js'   => '/* Kuždy JavaScript how lodujo se za wšykne wužywarje na kuždem boce. */',
'monobook.js' => '/* Slědujucy JavaScript zacytajo se za wužywarjow, kótarež skin MonoBook wužywaju */',

# Metadata
'nodublincore'      => 'Metadaty Dublin Core RDF su za toś ten serwer deaktiwěrowane.',
'nocreativecommons' => 'Metadaty Creative Commons RDF su za toś ten serwer deaktiwěrowane.',
'notacceptable'     => 'Wiki-serwer njamóžo daty za twój klient wobźěłaś.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Anonymny wužywaŕ|Anonymnej wužywarja|Anonymne wužywarje}} na {{SITENAME}}',
'siteuser'         => '{{SITENAME}}-wužywaŕ $1',
'anonuser'         => '{{SITENAME}} anonymny wužywaŕ $1',
'lastmodifiedatby' => 'Toś ten bok jo slědny raz se wobźěłał $2, $1 góź. wót wužywarja $3.',
'othercontribs'    => 'Bazěrujo na źěle $1',
'others'           => 'druge',
'siteusers'        => '{{SITENAME}} {{PLURAL:$2|wužywaŕ|wužiwarja|wužywarje}} $1',
'anonusers'        => '{{SITENAME}} {{PLURAL:$2|anonymny wužywaŕ|anonymnej wužywarja|anonymne wužywarje|anonymnych wužywarjow}} $1',
'creditspage'      => 'Informacija wó boku',
'nocredits'        => 'Njeeksistěruju žedne informacije za toś ten bok.',

# Spam protection
'spamprotectiontitle' => 'Spamowy filter',
'spamprotectiontext'  => 'Bok, kótaryž coš składowaś, jo se wót spamowego filtra blokěrował. To nejskerjej zawinujo wótkaz na eksterne sydło w carnej lisćinje.',
'spamprotectionmatch' => "'''Spamowy filter jo slědujucy tekst namakał: ''$1'''''",
'spambot_username'    => 'MediaWikijowe spamowe rěšenje',
'spam_reverting'      => 'Nawrośijo se slědna wersija, kótaraž njejo wopśimjeła wótkaz na $1.',
'spam_blanking'       => 'Wšykne wersije su wopśimowali wótkaze na $1, do rěcha spórane.',

# Info page
'infosubtitle'   => 'Informacija wó boku',
'numedits'       => 'Licba změnow boka: $1',
'numtalkedits'   => 'Licba změnow diskusijnego boka: $1',
'numwatchers'    => 'Licba  wobglědowarjow: $1',
'numauthors'     => 'Licba awtorow: $1',
'numtalkauthors' => 'Licba diskutěrujucych: $1',

# Skin names
'skinname-standard'    => 'Klasiski',
'skinname-nostalgia'   => 'Nostalgiski',
'skinname-cologneblue' => 'Kölnski Módry',
'skinname-monobook'    => 'MonoBook',
'skinname-myskin'      => 'Mój šat',
'skinname-chick'       => 'Kurjetko',
'skinname-simple'      => 'Jadnorje',
'skinname-modern'      => 'Moderny',

# Math options
'mw_math_png'    => 'Pśecej ako PNG zwobrazniś.',
'mw_math_simple' => 'Jadnory TeX ako HTML, howacej PNG',
'mw_math_html'   => 'Jo-lic móžno ako HTML, howacej PNG',
'mw_math_source' => 'Ako TeX wóstajiś (za tekstowe browsery)',
'mw_math_modern' => 'Pórucyjo se za moderne browsery',
'mw_math_mathml' => 'Jo-lic móžno - MathML (eksperimentelny)',

# Math errors
'math_failure'          => 'Zmólka',
'math_unknown_error'    => 'njeznata zmólka',
'math_unknown_function' => 'njeznata funkcija',
'math_lexing_error'     => 'leksikaliska zmólka',
'math_syntax_error'     => 'syntaktiska zmólka',
'math_image_error'      => 'PNG-konwertěrowanje njejo se raźiło; pśekontrolěruj korektnu instalaciju latex a dvipng (abo dvips + gs + konwertěruj)',
'math_bad_tmpdir'       => 'Njejo móžno temporarny zapisk za matematiske formule załožyś resp. do njogo pisaś.',
'math_bad_output'       => 'Njejo móžno celowy zapisk za matematiske formule załožyś resp. do njogo pisaś.',
'math_notexvc'          => 'Program texvc felujo. Pšosym glědaj do math/README.',

# Patrolling
'markaspatrolleddiff'                 => 'Ako kontrolěrowane markěrowaś',
'markaspatrolledtext'                 => 'Markěruj toś ten bok ako kontrolěrowany',
'markedaspatrolled'                   => 'jo se ako kontrolěrowany markěrował',
'markedaspatrolledtext'               => 'Wubrana wersija [[:$1]] jo se markěrowała ako kontrolěrowana.',
'rcpatroldisabled'                    => 'Kontrolěrowanje slědnych změnow jo se znjemóžniło.',
'rcpatroldisabledtext'                => 'Kontrolěrowanje slědnych změnow jo tuchylu se znjemóžniło.',
'markedaspatrollederror'              => 'Markěrowanje ako "kontrolěrowane" njejo móžne.',
'markedaspatrollederrortext'          => 'Musyš wersiju wuzwóliś.',
'markedaspatrollederror-noautopatrol' => 'Njesmějoš swóje změny ako kontrolěrowane markěrowaś.',

# Patrol log
'patrol-log-page'      => 'Protokol kontrolow',
'patrol-log-header'    => 'To jo protokol pśekontrolowanych wersijow.',
'patrol-log-line'      => 'markěrował $1 wót $2 ako kontrolěrowane $3.',
'patrol-log-auto'      => '(awtomatiski)',
'patrol-log-diff'      => 'Wersija $1',
'log-show-hide-patrol' => 'Protokol doglědowanja $1',

# Image deletion
'deletedrevision'                 => 'wulašowana stara wersija: $1',
'filedeleteerror-short'           => 'Zmólka pśi wulašowanju dataje: $1',
'filedeleteerror-long'            => 'Pśi wulašowanju dataje su se zwěsćili zmólki:

$1',
'filedelete-missing'              => 'Dataja „$1“ njamóžo se wulašowaś, dokulaž njeeksistěrujo.',
'filedelete-old-unregistered'     => 'Pódana wersija „$1“ w datowej bance njeeksistěrujo.',
'filedelete-current-unregistered' => 'Pódana dataja „$1“ w datowej bance njeeksistěrujo.',
'filedelete-archive-read-only'    => 'Webserwer njamóžo do archiwowego zapisa „$1“ pisaś.',

# Browsing diffs
'previousdiff' => '← pśedchadna změna',
'nextdiff'     => 'Pśiduca změna →',

# Media information
'mediawarning'         => "'''Warnowanje''': Toś ten datajowy typ móžo wopśimjeś złosny programowy kod. Gaž toś ten kod se wuwjeźo, twój system móžo se wobškóźeś.",
'imagemaxsize'         => "Maksimalna wobrazowa wjelikosć:<br />'' (za boki datajowego wopisanja)''",
'thumbsize'            => 'Rozměra miniaturow:',
'widthheightpage'      => '$1 × $2, $3 {{PLURAL:$3|bok|boka|boki|bokow}}',
'file-info'            => 'wjelikosć dataje: $1, MIME-Typ: $2',
'file-info-size'       => '$1 × $2 pikselow, wjelikosć dataje: $3, MIME-Typ: $4',
'file-nohires'         => '<small>Wuše wótgranicowanje njeeksistěrujo.</small>',
'svg-long-desc'        => 'dataja SVG, nominalnje: $1 × $2 piksele, wjelikosć dataje: $3',
'show-big-image'       => 'Połne optiske wótgranicowanje.',
'show-big-image-thumb' => '<small>wjelikosć pśeglěda: $1 × $2 pikselow</small>',
'file-info-gif-looped' => 'bźezkóńcna šlejfa',
'file-info-gif-frames' => '$1 {{PLURAL:$1|wobłuk|wobłuka|wobłuki|wobłukow}}',
'file-info-png-looped' => 'šlejfa',
'file-info-png-repeat' => '{{PLURAL:$1|$1 raz|dwójcy|$1 raze|$1 razow}} wótegrata',
'file-info-png-frames' => '$1 {{PLURAL:$1|wobłuk|wobłuka|wobłuki|wobłukow}}',

# Special:NewFiles
'newimages'             => 'Nowe dataje',
'imagelisttext'         => "How jo lisćina '''$1''' {{PLURAL:$1|dataje|datajowu|datajow}}, sortěrowane $2.",
'newimages-summary'     => 'Toś ten specialny bok pokazujo dataje, kótarež su se ako slědne nagrali.',
'newimages-legend'      => 'Filter',
'newimages-label'       => 'Datajowe mě (abo źěl z togo):',
'showhidebots'          => '(awtomatiske programy (boty) $1)',
'noimages'              => 'Žedne dataje njejsu se namakali.',
'ilsubmit'              => 'Pytaś',
'bydate'                => 'pó datumje',
'sp-newimages-showfrom' => 'Pokaž nowe dataje wót $1, $2',

# Bad image list
'bad_image_list' => 'Format jo slědujucy:

Jano smužki, kótarež zachopiju z *, se wugódnośiju. Prědny wótkaz na smužce musy wótkaz na njekśětu dataju byś.
Slědujuce wótkaze w tej samej smužce se za wuwześa naglědaju, w kótarychž móžo dataja weto se pokazaś.',

# Metadata
'metadata'          => 'Metadaty',
'metadata-help'     => 'Toś ta dataja wopśimjejo pśidatne informacije, kótarež nejskerjej póchadaju wót digitalneje kamery abo scannera. Jolic dataja bu pozdźej změnjona, njeby mógli někotare detaile změnjonu dataju wótbłyšćowaś.',
'metadata-expand'   => 'rozšyrjone detaile pokazaś',
'metadata-collapse' => 'rozšyrjone detaile schowaś',
'metadata-fields'   => 'Slědujuce póla EXIF-metadatow se pokazuju na bokach, kótarež wopisuju wobraze; dalšne detaile, kótarež normalnje su schowane, mógu se pśidatnje pokazaś.

* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Šyrokosć',
'exif-imagelength'                 => 'Wusokosć',
'exif-bitspersample'               => 'Bity na komponentu',
'exif-compression'                 => 'Wašnja kompriměrowanja',
'exif-photometricinterpretation'   => 'Zestajenje pikselow',
'exif-orientation'                 => 'Wusměrjenje kamery',
'exif-samplesperpixel'             => 'Licba komponentow',
'exif-planarconfiguration'         => 'Struktura datow',
'exif-ycbcrsubsampling'            => 'Subsamplingowa rata wót Y do C',
'exif-ycbcrpositioning'            => 'Pozicijoněrowanje Y a C',
'exif-xresolution'                 => 'Horicontalne optiske wótgranicowanje',
'exif-yresolution'                 => 'Wertikalne optiske wótgranicowanje',
'exif-resolutionunit'              => 'Měra optiskego wótgranicowanja',
'exif-stripoffsets'                => 'městnosć wobrazowych datow',
'exif-rowsperstrip'                => 'Licba smužkow na rědku',
'exif-stripbytecounts'             => 'Byty na kompriměrowanu rědku',
'exif-jpeginterchangeformat'       => 'Offset k JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Byty JPEG-dataje',
'exif-transferfunction'            => 'Funkcija pśestajenja',
'exif-whitepoint'                  => 'kwalita barwy běłego dypka',
'exif-primarychromaticities'       => 'Kwalita barwy primarnych barwow.',
'exif-ycbcrcoefficients'           => 'YCbCr-koeficienty',
'exif-referenceblackwhite'         => 'Pórik carneje a běłeje referencneje gódnoty',
'exif-datetime'                    => 'Cas składowanja',
'exif-imagedescription'            => 'Mě wobraza',
'exif-make'                        => 'Zgótowaŕ kamery',
'exif-model'                       => 'Model kamery',
'exif-software'                    => 'Softwara',
'exif-artist'                      => 'Awtor',
'exif-copyright'                   => 'Wobsejźaŕ stwóriśelskich pšawow',
'exif-exifversion'                 => 'Wersija Exif',
'exif-flashpixversion'             => 'Pódpěrana wersija Flashpix',
'exif-colorspace'                  => 'Barwowy rum',
'exif-componentsconfiguration'     => 'Wóznam jadnotliwych komponentow',
'exif-compressedbitsperpixel'      => 'Kompriměrowane bity na piksel',
'exif-pixelydimension'             => 'Šyrokosć wobraza',
'exif-pixelxdimension'             => 'Wusokosć wobraza',
'exif-makernote'                   => 'Noticy zgótowarja',
'exif-usercomment'                 => 'Komentary wužywarja',
'exif-relatedsoundfile'            => 'Pśisłušna zukowa dataja',
'exif-datetimeoriginal'            => 'Datum a cas wutwórjenja datow',
'exif-datetimedigitized'           => 'Datum a cas digitalizěrowanja',
'exif-subsectime'                  => 'Źěły sekundow za datum a cas (1/100 s)',
'exif-subsectimeoriginal'          => 'Źěły sekundow za datum a cas wutwórjenja datow (1/100 s)',
'exif-subsectimedigitized'         => 'Źěły sekundow za datum a cas digitalizěrowanja (1/100 s)',
'exif-exposuretime'                => 'Cas wobswětlenja',
'exif-exposuretime-format'         => '$1 sek ($2)',
'exif-fnumber'                     => 'Blenda',
'exif-exposureprogram'             => 'Program wobswětlenja',
'exif-spectralsensitivity'         => 'Spektralna cuśiwosć',
'exif-isospeedratings'             => 'Cuśiwosć filma abo sensora (ISO)',
'exif-oecf'                        => 'Optoelektroniski pśelicowański faktor (OECF)',
'exif-shutterspeedvalue'           => 'Gódnota wobswětleńskego casa APEX',
'exif-aperturevalue'               => 'APEX-blenda',
'exif-brightnessvalue'             => 'APEX-swětłosć',
'exif-exposurebiasvalue'           => 'Směrnica za wobswětlenje',
'exif-maxaperturevalue'            => 'Nejžwětša blenda',
'exif-subjectdistance'             => 'zdalonosć',
'exif-meteringmode'                => 'Wašnja měrjenja',
'exif-lightsource'                 => 'Žrědło swětła',
'exif-flash'                       => 'Błysk',
'exif-focallength'                 => 'Palna dalokosć',
'exif-subjectarea'                 => 'wobłuk',
'exif-flashenergy'                 => 'mócnosć błyska',
'exif-spatialfrequencyresponse'    => 'Cuśiwosć rumoweje frekwence',
'exif-focalplanexresolution'       => 'horicontalne optiske wótgranicowanje sensora',
'exif-focalplaneyresolution'       => 'wertikalne optiske wótgranicowanje sensora',
'exif-focalplaneresolutionunit'    => 'Jadnotka optiskego wótgranicowanja sensora',
'exif-subjectlocation'             => 'Městno motiwa',
'exif-exposureindex'               => 'Indeks wobswětlenja',
'exif-sensingmethod'               => 'wašnja měrjenja',
'exif-filesource'                  => 'Žrědło dataje',
'exif-scenetype'                   => 'Typ sceny',
'exif-cfapattern'                  => 'Muster CFA',
'exif-customrendered'              => 'Wót wužywarja definěrowane wobźěłanje wobraza',
'exif-exposuremode'                => 'Modus wobswětlenja',
'exif-whitebalance'                => 'Balansa běłosći',
'exif-digitalzoomratio'            => 'digitalne zoomowanje',
'exif-focallengthin35mmfilm'       => 'Palna dalokosć (wótpowědnik za małe wobraze)',
'exif-scenecapturetype'            => 'wašnja nagraśa',
'exif-gaincontrol'                 => 'Regulěrowanje sceny',
'exif-contrast'                    => 'kontrast',
'exif-saturation'                  => 'naseśenje',
'exif-sharpness'                   => 'wótšosć',
'exif-devicesettingdescription'    => 'Nastajenja aparata',
'exif-subjectdistancerange'        => 'Zdalonosć motiwa',
'exif-imageuniqueid'               => 'Jadnorazny ID wobraza',
'exif-gpsversionid'                => 'Wersija taga GPS',
'exif-gpslatituderef'              => 'Pódpołnocna abo pódpołdnjowa šyrina',
'exif-gpslatitude'                 => 'Šyrina',
'exif-gpslongituderef'             => 'Pódzajtšna abo pódwjacorna dliń',
'exif-gpslongitude'                => 'Dliń',
'exif-gpsaltituderef'              => 'Referencna wusokosć',
'exif-gpsaltitude'                 => 'Wusokosć',
'exif-gpstimestamp'                => 'GPS-cas',
'exif-gpssatellites'               => 'Za měrjenje wužywane satelity',
'exif-gpsstatus'                   => 'Status pśidostawaka',
'exif-gpsmeasuremode'              => 'wašnja měrjenja',
'exif-gpsdop'                      => 'dokradnosć měry',
'exif-gpsspeedref'                 => 'Jadnotka spěšnosći',
'exif-gpsspeed'                    => 'Spěšnosć GPS-pśidostawaka',
'exif-gpstrackref'                 => 'Referenca za směr pógibowanja',
'exif-gpstrack'                    => 'směr pógibowanja',
'exif-gpsimgdirectionref'          => 'Referenca směra wobraza',
'exif-gpsimgdirection'             => 'Směr wobraza',
'exif-gpsmapdatum'                 => 'Wužyte geodetiske dataje',
'exif-gpsdestlatituderef'          => 'Referenca šyriny celowego městna',
'exif-gpsdestlatitude'             => 'Šyrina celowego městna',
'exif-gpsdestlongituderef'         => 'Referenca dlini celowego městna',
'exif-gpsdestlongitude'            => 'Dliń abo celowe městno',
'exif-gpsdestbearingref'           => 'Referenca za wusměrjenje',
'exif-gpsdestbearing'              => 'Wusměrjenje',
'exif-gpsdestdistanceref'          => 'Referenca za distancu k celowemu městnu',
'exif-gpsdestdistance'             => 'Distanca k celowemu městnu',
'exif-gpsprocessingmethod'         => 'Mě metody pśeźěłanja GPS',
'exif-gpsareainformation'          => 'Mě wobcerka GPS',
'exif-gpsdatestamp'                => 'Datum GPS',
'exif-gpsdifferential'             => 'Diferencialna korektura GPS',
'exif-objectname'                  => 'Krotki titel',

# EXIF attributes
'exif-compression-1' => 'Njekompriměrowany',

'exif-unknowndate' => 'Njeznaty datum',

'exif-orientation-1' => 'Normalny',
'exif-orientation-2' => 'horicontalnje wobrośony',
'exif-orientation-3' => 'Pśewobrośony',
'exif-orientation-4' => 'wertikalnje wobrośony',
'exif-orientation-5' => 'Wobrośony wó 90° nalěwo a wertikalnje',
'exif-orientation-6' => 'Wó 90° pśeśiwo směroju špěry zwjertnjony',
'exif-orientation-7' => 'Wobrośony wó 90° napšawo a wertikalnje',
'exif-orientation-8' => 'Wó 90° do směra špěry zwjertnjony',

'exif-planarconfiguration-1' => 'gropny format',
'exif-planarconfiguration-2' => 'płony format',

'exif-xyresolution-i' => '$1 dpi (dypkow na col)',

'exif-componentsconfiguration-0' => 'njeeksistěrujo',

'exif-exposureprogram-0' => 'Njedefiněrowane',
'exif-exposureprogram-1' => 'manualnje',
'exif-exposureprogram-2' => 'Normalny program',
'exif-exposureprogram-3' => 'Priorita blendy',
'exif-exposureprogram-4' => 'Priorita blendy',
'exif-exposureprogram-5' => 'Kreatiwny program (wjelika dłym wótšosći)',
'exif-exposureprogram-6' => 'Aktiwny program (wjelika malsnosć momentoweje bildki)',
'exif-exposureprogram-7' => 'portretowy modus (za closeup-fotografije z njefokusěrowaneju slězynu)',
'exif-exposureprogram-8' => 'wobraze krajiny',

'exif-subjectdistance-value' => '{{PLURAL:$1|$1 meter|$1 metra|$1 metry}}',

'exif-meteringmode-0'   => 'Njeznaty',
'exif-meteringmode-1'   => 'Pśerězna gódnota',
'exif-meteringmode-2'   => 'srjejźa wusměrjone',
'exif-meteringmode-3'   => 'Spot',
'exif-meteringmode-4'   => 'MultiSpot',
'exif-meteringmode-5'   => 'Muster',
'exif-meteringmode-6'   => 'Źělny',
'exif-meteringmode-255' => 'Drugi',

'exif-lightsource-0'   => 'Njeznaty',
'exif-lightsource-1'   => 'Dnjowne swětło',
'exif-lightsource-2'   => 'Fluorescentny',
'exif-lightsource-3'   => 'Žaglawka',
'exif-lightsource-4'   => 'Błysk',
'exif-lightsource-9'   => 'Rědne wjedro',
'exif-lightsource-10'  => 'Mrokawe wjedro',
'exif-lightsource-11'  => 'Seń',
'exif-lightsource-12'  => 'Dnjowe swětło fluorescentne (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Dnjowoběły fluorescentny (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Zymny běły fluorescentny (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Běły fluorescentny (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Standardne swětło A',
'exif-lightsource-18'  => 'Standardne swětło B',
'exif-lightsource-19'  => 'Standardne swětło C',
'exif-lightsource-24'  => 'ISO studijowe swětło',
'exif-lightsource-255' => 'Druge žrědło swětła',

# Flash modes
'exif-flash-fired-0'    => 'Błysk njejo se zapusćił',
'exif-flash-fired-1'    => 'Błysk jo se zapusćił',
'exif-flash-return-0'   => 'žedna funkcija za nadejźenje refleksije fotobłyska',
'exif-flash-return-2'   => 'Refleksija fotobłyska njejo se nadejšła',
'exif-flash-return-3'   => 'Refleksija fotobłyska jo se nadejšła',
'exif-flash-mode-1'     => 'wunuźone błysknjenje',
'exif-flash-mode-2'     => 'wunuźone błysknjenje pódtłocone',
'exif-flash-mode-3'     => 'awtomatiski modus',
'exif-flash-function-1' => 'Njejo błyskowa funkcija',
'exif-flash-redeye-1'   => 'Modus redukcije cerwjenych wócow',

'exif-focalplaneresolutionunit-2' => 'cole',

'exif-sensingmethod-1' => 'Njedefiněrujobny',
'exif-sensingmethod-2' => 'Jadnochipowy barwowy sensor ruma',
'exif-sensingmethod-3' => 'Dwuchipowy barwowy sensor ruma',
'exif-sensingmethod-4' => 'Tśichipowy barwowy sensor ruma',
'exif-sensingmethod-5' => 'Sekwencielny barwowy sensor ruma',
'exif-sensingmethod-7' => 'Tśilinearny sensor',
'exif-sensingmethod-8' => 'Sekwencielny barwowy linearny sensor',

'exif-filesource-3' => 'Digitalna stojańskowobrazowa kamera',

'exif-scenetype-1' => 'Direktnje fotografěrowany wobraz',

'exif-customrendered-0' => 'Normalne wobźěłanje',
'exif-customrendered-1' => 'Wužywarske wobźěłanje',

'exif-exposuremode-0' => 'Awtomatiske wobswětlenje',
'exif-exposuremode-1' => 'Manuelna blenda',
'exif-exposuremode-2' => 'Awtoblenda',

'exif-whitebalance-0' => 'Awtomatiska rownowaga běłosći',
'exif-whitebalance-1' => 'Manuelna rownowaga běłosći',

'exif-scenecapturetype-0' => 'Standard',
'exif-scenecapturetype-1' => 'Krajina',
'exif-scenecapturetype-2' => 'Portret',
'exif-scenecapturetype-3' => 'Nocna scena',

'exif-gaincontrol-0' => 'Žedne',
'exif-gaincontrol-1' => 'Małe zmócnjenje',
'exif-gaincontrol-2' => 'wjelike zmócnjenje',
'exif-gaincontrol-3' => 'małe wósłabjenje',
'exif-gaincontrol-4' => 'Wjelike wósłabjenje',

'exif-contrast-0' => 'Normalny',
'exif-contrast-1' => 'Słaby',
'exif-contrast-2' => 'Mócny',

'exif-saturation-0' => 'Normalny',
'exif-saturation-1' => 'małe naseśenje',
'exif-saturation-2' => 'wjelike naseśenje',

'exif-sharpness-0' => 'Normalny',
'exif-sharpness-1' => 'Słaby',
'exif-sharpness-2' => 'Mócny',

'exif-subjectdistancerange-0' => 'Njeznaty',
'exif-subjectdistancerange-1' => 'makro',
'exif-subjectdistancerange-2' => 'Bliski rozglěd',
'exif-subjectdistancerange-3' => 'Daloki rozglěd',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Pódpołnocna šyrina',
'exif-gpslatitude-s' => 'Pódpołdnjowa šyrina',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Pódzajtšna dliń',
'exif-gpslongitude-w' => 'Pódwjacorna dliń',

'exif-gpsstatus-a' => 'Měrjenje w běgu',
'exif-gpsstatus-v' => 'kompatibelnosć měry',

'exif-gpsmeasuremode-2' => '2-dimensionalne měrjenje',
'exif-gpsmeasuremode-3' => '3-dimensionalne měrjenje',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilometry na góźinu',
'exif-gpsspeed-m' => 'Mile na góźinu',
'exif-gpsspeed-n' => 'Suki',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Wopšawdny směr',
'exif-gpsdirection-m' => 'Magnetiski směr',

# External editor support
'edit-externally'      => 'Dataje z eksternym programom wobźěłaś',
'edit-externally-help' => '(Za dalšne informacije glědaj [http://www.mediawiki.org/wiki/Manual:External_editors instalaciske instrukcije]).',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'wšykne',
'imagelistall'     => 'wšykne',
'watchlistall2'    => 'wšykne',
'namespacesall'    => 'wšykne',
'monthsall'        => 'wšykne',
'limitall'         => 'wšykne',

# E-mail address confirmation
'confirmemail'              => 'E-mailowu adresu wobkšuśiś.',
'confirmemail_noemail'      => 'W swójich [[Special:Preferences|nastajenjach]] njejsy płaśecu e-mailowu adresu zapódał.',
'confirmemail_text'         => '{{SITENAME}} pomina, až wobkšuśijoš swóju e-mailowu adresu, nježlic až móžoš e-mailowe funkcije wužywaś. Tłocyš-lic na tłocatko, dostanjoš e-mailku, w kótarejž jo wótkaz z wobkšuśenskim gronidłom. Tłocenje na wótkaz wobkšuśijo, až twója e-mailowa adresa jo korektna.',
'confirmemail_pending'      => 'Tebje jo južo jadno wobkšuśeńske gronidło se pśimailowało. Sy-lic swójo wužywarske konto akle gano wutwórił, ga pócakaj hyšći žedne minuty na e-mail, nježlic až pominaš nowe gronidło.',
'confirmemail_send'         => 'Wobkšuśeńske gronidło pósłaś',
'confirmemail_sent'         => 'Wobkšuśeńska e-mailka pósłana.',
'confirmemail_oncreate'     => 'Na Twóju adresu jo se wótpósłało wobkšuśeńske gronidło. Toś ten kod njejo notny za pśizjawjenje, ale za aktiwěrowanje e-mailowych funkcijow we wikiju.',
'confirmemail_sendfailed'   => '{{SITENAME}} njejo se mógła twóju wobkšuśensku e-mail pósłaś. Pšosym pśeglědaj swóju e-mailowu adresu na njepłaśiwe znamuška.

E-mailowy program jo wrośił: $1',
'confirmemail_invalid'      => 'Njepłaśece wobkšuśeńske gronidło. Snaź jo kod mjaztym płaśiwosć zgubił.',
'confirmemail_needlogin'    => 'Dejš $1 aby swóju e-mailowu adresu wobkšuśił.',
'confirmemail_success'      => 'Twója e-mailowa adresa jo wobkšuśona
Móžoš se něnto [[Special:UserLogin|pśizjawiś]] a se wikiju wijaseliś.',
'confirmemail_loggedin'     => 'Twója e-mailowa adresa jo něnto wobkšuśona.',
'confirmemail_error'        => 'Zmólka pśi wobkšuśenju e-mailoweje adresy.',
'confirmemail_subject'      => '{{SITENAME}} - Wobkšuśenje e-mailoweje adrese',
'confirmemail_body'         => 'Něchten, nejskerjej ty z adresy $1, jo na boku {{SITENAME}} wužywarske konto "$2" z toś teju e-mailoweju adresu zregistrěrował.

Aby wobkšuśił, až toś to konto napšawdu śi słuša a aby e-mailowe funkcije na boce {{SITENAME}} aktiwěrował, wócyń toś ten wótkaz w swójim browserje:

$3

Jolic až *njejsy* toś to konto zregistrěrował, slěduj toś tomu wótkazoju, aby wobkśuśenje e-mejloweje adresy anulował:

$5

Toś ten wobkšuśeński kod płaśi do $4.',
'confirmemail_body_changed' => 'Něchten, nejskerjej ty z IP-adresy $1, jo e-mailowu adresu konta "$2" do toś teje adrese na {{GRAMMAR:lokatiw{{SITENAME}}}} změnił.

Aby wobkšuśił, až toś to konto napšawdu śi słuša a aby e-mailowe funkcije na {{GRAMMAR:lokatiw{{SITENAME}}}} aktiwěrował, wócyń toś ten wótkaz w swójom wobglědowaku:

$3

Jolic toś to konto śi *nje*słuša, slěduj toś tomu wótkazoju, aby wobkśuśenje e-mejloweje adrese anulěrował:

$5

Toś ten wobkšuśeński kod płaśi až do $4.',
'confirmemail_body_set'     => 'Něchten, nejskerjej ty z IP-adresy $1, jo e-mailowu adresu konta "$2" do toś teje adrese na {{GRAMMAR:lokatiw{{SITENAME}}}} změnił.

Aby wobkšuśił, až toś to konto napšawdu śi słuša a aby e-mailowe funkcije na {{GRAMMAR:lokatiw{{SITENAME}}}} aktiwěrował, wócyń toś ten wótkaz w swójom wobglědowaku:

$3

Jolic toś to konto śi *nje*słuša, slěduj toś tomu wótkazoju, aby wobkśuśenje e-mejloweje adrese anulěrował:

$5

Toś ten wobkšuśeński kod płaśi až do $4.',
'confirmemail_invalidated'  => 'Emailowe wobkšuśenje pśetergnjone',
'invalidateemail'           => 'Emailowe wobkšuśenje pśetergnuś',

# Scary transclusion
'scarytranscludedisabled' => '[Pśidawanje interwiki jo deaktiwěrowane]',
'scarytranscludefailed'   => '[Zapśěgnjenje pśedłogi za $1 njejo se raźiło]',
'scarytranscludetoolong'  => '[URL jo pśedłujki]',

# Trackbacks
'trackbackbox'      => 'Trackbacki za toś ten bok:<br />
$1',
'trackbackremove'   => '([$1 wulašowaś])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => 'Trackback jo wuspěšnje wulašowany.',

# Delete conflict
'deletedwhileediting' => "'''Warnowanje''': Toś ten bok se wulašujo, gaž zachopijoš jen wobźěłaś!",
'confirmrecreate'     => "Wužywaŕ [[User:$1|$1]] ([[User talk:$1|diskusija]]) jo bok wulašował, nježli až sy zachopił jen wobźěłaś, pśicyna:
: ''$2''
Pšosym wobkšuśiś, až napšawdu coš ten bok zasej wutwóriś.",
'recreate'            => 'Wótnowótki wutwóriś',

# action=purge
'confirm_purge_button' => 'W pórědku.',
'confirm-purge-top'    => 'Wulašowaś cache togo boka?',
'confirm-purge-bottom' => 'Wuproznijo cache a wunuzijo zwobraznjenje aktualneje wersije.',

# Multipage image navigation
'imgmultipageprev' => '← slědny bok',
'imgmultipagenext' => 'pśiducy bok →',
'imgmultigo'       => 'W pórědku',
'imgmultigoto'     => 'Źi k bokoju $1',

# Table pager
'ascending_abbrev'         => 'górjej',
'descending_abbrev'        => 'dołoj',
'table_pager_next'         => 'Pśiducy bok',
'table_pager_prev'         => 'Pjerwjejšny bok',
'table_pager_first'        => 'Prědny bok',
'table_pager_last'         => 'Slědny bok',
'table_pager_limit'        => 'Pokazaś {{PLURAL:$1|$1 objekt|$1 objekta|$1 objekty}} na bok',
'table_pager_limit_label'  => 'Zapiski na bok:',
'table_pager_limit_submit' => 'Start',
'table_pager_empty'        => 'Žedne wuslědki',

# Auto-summaries
'autosumm-blank'   => 'Bok jo se wuproznił',
'autosumm-replace' => "Bok narownajo se z: '$1'",
'autoredircomment' => 'Pśesměrowanje na [[$1]]',
'autosumm-new'     => "Jo napórał bok z '$1'",

# Live preview
'livepreview-loading' => 'Lodowanje …',
'livepreview-ready'   => 'Lodowanje … gótowe!',
'livepreview-failed'  => 'Live-pśeglěd njejo móžny. Pšosym normalny pśeglěd wužywaś.',
'livepreview-error'   => 'Kontaktowanje njejo se zglucyło: $1 "$2". Pšosym normalny pśeglěd wužywaś.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Změny {{PLURAL:$1|slědneje $1 sekundy|slědneju $1 sekundowu|slědnych $1 sekundow|slědnych $1 sekundow}} njepókazuju se w toś tej lisćinje.',
'lag-warn-high'   => 'Dla wusokego wuśěženja serwera datoweje banki jo móžno, až pśinoski, kótarež su nowše ako {{PLURAL:$1|$1 sekunda|sekunźe|sekundy|sekundow}} se snaź na toś tej liśćinje njepokazuju.',

# Watchlist editor
'watchlistedit-numitems'       => 'Twója wobglědowańka wopśimuju {{PLURAL:$1|$1 zapisk|$1 zapiska|$1 zapiski|$1 zapiskow}}, bźez diskusijnych bokow.',
'watchlistedit-noitems'        => 'Twója wobglědowańka jo prozna.',
'watchlistedit-normal-title'   => 'Zapise wobźěłaś',
'watchlistedit-normal-legend'  => 'Zapiski z wobglědowańki wulašowaś',
'watchlistedit-normal-explain' => 'Zapiski w twójej wobglědowańce pokazuju se dołojce. Aby zapisk wulašował, markěruj kašćik pódla zapiska a klikni na "{{int:Watchlistedit-normal-submit}}". Móžoš swóju wobglědowańku teke w [[Special:Watchlist/raw|lisćinowem formaśe]] wobźěłaś.',
'watchlistedit-normal-submit'  => 'Zapise wulašowaś',
'watchlistedit-normal-done'    => '{{PLURAL:$1 zapisk jo|$1 zapiska stej|$1 zapiski su|$1 zapiskow jo}} se z twójeje wobglědowańki {{PLURAL:wulašował|wulašowałej|wulašowali|wulašowało}}.',
'watchlistedit-raw-title'      => 'Samu wobglědowańku wobźěłaś',
'watchlistedit-raw-legend'     => 'Samu wobglědowańku wobźěłaś',
'watchlistedit-raw-explain'    => 'Titele, kótarež namakaju se w twójej wobglědowańce pokazuju se dołojce a daju se lisćinje pśidaś a z njeje wótpóraś; jaden titel na smužku.
Gaž sy gótowy, klikni na "{{int:Watchlistedit-raw-submit}}".
Móžoš teke [[Special:Watchlist/edit|standardny wobźěłowański bok wužywaś]].',
'watchlistedit-raw-titles'     => 'Zapise:',
'watchlistedit-raw-submit'     => 'Lisćinu aktualizěrowaś',
'watchlistedit-raw-done'       => 'Twója wobglědowańka jo se zaktualizěrowała.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 zapis jo se dodał|$1 zapisa stej se dodałej|$1 zapise su se dodali}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 zapis jo se wulašował|$1 zapisa stej se wulašowałej|$1 zapise su se wulašowali}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Změny wobglědaś',
'watchlisttools-edit' => 'Wobglědowańku pokazaś a wobźěłaś',
'watchlisttools-raw'  => 'Wobglědowańku wobźěłaś',

# Iranian month names
'iranian-calendar-m1'  => 'Farwardin',
'iranian-calendar-m2'  => 'Ordibehešt',
'iranian-calendar-m3'  => 'Chordad',
'iranian-calendar-m4'  => 'Tir',
'iranian-calendar-m5'  => 'Mordad',
'iranian-calendar-m6'  => 'Šahriwar',
'iranian-calendar-m7'  => 'Mehr',
'iranian-calendar-m8'  => 'Aban',
'iranian-calendar-m9'  => 'Azar',
'iranian-calendar-m10' => 'Dej',
'iranian-calendar-m11' => 'Bahman',
'iranian-calendar-m12' => 'Esfand',

# Core parser functions
'unknown_extension_tag' => 'Njeznaty tag rozšyrjenja „$1“',
'duplicate-defaultsort' => 'Glědaj: Standardny sortěrowański kluc (DEFAULT SORT KEY) "$2" pśepišo pjerwjej wužyty kluc "$1".',

# Special:Version
'version'                          => 'Wersija',
'version-extensions'               => 'Instalowane rozšyrjenja',
'version-specialpages'             => 'Specialne boki',
'version-parserhooks'              => 'Parserowe kokule',
'version-variables'                => 'Wariable',
'version-skins'                    => 'Suknje',
'version-other'                    => 'Druge',
'version-mediahandlers'            => 'Pśeźěłaki medijow',
'version-hooks'                    => 'Kokule',
'version-extension-functions'      => 'Funkcije rozšyrjenjow',
'version-parser-extensiontags'     => 'Tagi parserowych rozšyrjenjow',
'version-parser-function-hooks'    => 'Parserowe funkcije',
'version-skin-extension-functions' => 'Funkcije za rozšyrjenja šatow',
'version-hook-name'                => 'Mě kokule',
'version-hook-subscribedby'        => 'Aboněrowany wót',
'version-version'                  => '(Wersija $1)',
'version-license'                  => 'Licenca',
'version-poweredby-credits'        => "Toś ten wiki spěchujo se wót '''[http://www.mediawiki.org/ MediaWiki]''', copyright © 2001-$1 $2.",
'version-poweredby-others'         => 'druge',
'version-license-info'             => 'MediaWiki jo licha softwara: móžoš ju pód wuměnjenjami licence GNU General Public License, wózjawjeneje wót załožby Free Software Foundation, rozdźěliś a/abo změniś: pak pód wersiju 2 licence pak pód někakeju pózdźejšeju wersiju.

MediaWiki rozdźěla se w naźeji, až buźo wužitny, ale BŹEZ GARANTIJE: samo bźez wopśimjoneje garantije PŚEDAWAJOBNOSĆI abo PŚIGÓDNOSĆI ZA WĚSTY ZAMĚR. Glědaj GNU general Public License za dalšne drobnostki.

Ty by dejał [{{SERVER}}{{SCRIPTPATH}}/COPYING kopiju licence GNU General Public License] gromaźe z toś tym programom dostanu měś: jolic nic, napiš do załožby Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA abo [http://www.gnu.org/licenses/old-licenses/gpl-2.0.html pśecytaj ju online].',
'version-software'                 => 'Instalěrowana software',
'version-software-product'         => 'Produkt',
'version-software-version'         => 'Wersija',

# Special:FilePath
'filepath'         => 'Datajowa droga',
'filepath-page'    => 'Dataja:',
'filepath-submit'  => 'Pytaś',
'filepath-summary' => 'Toś ten specialny bok wróśa dopołnu drogu za dataju.
Wobraze se w połnym wótgranicowanju pokazuju, druge datajowe typy se ze zwězanym programom direktnje startuju.',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Za duplikatnymi datajami pytaś',
'fileduplicatesearch-summary'  => 'Za datajowymi duplikatami na zakłaźe gótnoty hash pytaś.

Zapódaj datajowe mě bźez prefiksa "{{ns:file}}:".',
'fileduplicatesearch-legend'   => 'pytaś duplikata',
'fileduplicatesearch-filename' => 'Datajowe mě:',
'fileduplicatesearch-submit'   => 'Pytaś',
'fileduplicatesearch-info'     => '$1 × $2 Piksel<br />wjelikosć dataja: $3<br />typ MIME: $4',
'fileduplicatesearch-result-1' => 'Dataja "$1" njama identiske duplikaty.',
'fileduplicatesearch-result-n' => 'Dataja "$1" ma {{PLURAL:$2|1 identiski duplikat|$2 identiskej duplikata|$2 identiske duplikaty|$2 identiskich duplikatow}}.',

# Special:SpecialPages
'specialpages'                   => 'Specialne boki',
'specialpages-note'              => '----
* Normalne specialne boki
* <strong class="mw-specialpagerestricted">Specialne boki z wobgranicowanym pśistupom</strong>',
'specialpages-group-maintenance' => 'Wótwardowańske lisćiny',
'specialpages-group-other'       => 'Druge specialne boki',
'specialpages-group-login'       => 'Pśizjawjenje',
'specialpages-group-changes'     => 'Slědne změny a protokole',
'specialpages-group-media'       => 'Medije',
'specialpages-group-users'       => 'Wužywarje a pšawa',
'specialpages-group-highuse'     => 'Cesto wužywane boki',
'specialpages-group-pages'       => 'Lisćiny bokow',
'specialpages-group-pagetools'   => 'Rědy bokow',
'specialpages-group-wiki'        => 'Wikijowe daty a rědy',
'specialpages-group-redirects'   => 'Dalej pósrědnjajuce boki',
'specialpages-group-spam'        => 'Spamowe rědy',

# Special:BlankPage
'blankpage'              => 'Prozny bok',
'intentionallyblankpage' => 'Toś ten bok jo z wótglědom prozny.',

# External image whitelist
'external_image_whitelist' => ' #Wóstaj toś tu smužku rowno tak jo<pre>
#Zapódaj fragmenty regularnych wurazow (jano źěl mjazy //) dołojce
#Toś te budu se pśirunowaś z URL ekseternych wobrazow
#Te, kótarež makaju se, zwobraznuju se ako wobraze, howac pokažo se jano wótkaz k wobrazoju
#Ze smužkami, kótarež zachopiju se z #, wobchadaju ako z komentarami
#To njeźiwa na wjelikopisanje

#Staj wše fragmenty regularnych wurazow nad smužku. Wóstaj toś tu smužku rowno tak jo</pre>',

# Special:Tags
'tags'                    => 'Płaśiwe toflicki změnow',
'tag-filter'              => 'Filter [[Special:Tags|toflickow]]:',
'tag-filter-submit'       => 'Filter',
'tags-title'              => 'Toflicki',
'tags-intro'              => 'Toś ten bok nalicyjo toflicki, z kótarymiž softwara móžo změnu markěrowaś a jich wóznam.',
'tags-tag'                => 'Mě toflicki',
'tags-display-header'     => 'Naglěd na lisćinach změnow',
'tags-description-header' => 'Dopołne wopisanje wóznama',
'tags-hitcount-header'    => 'Změny z toflickami',
'tags-edit'               => 'wobźěłaś',
'tags-hitcount'           => '$1 {{PLURAL:$1|změna|změnje|změny|změnow}}',

# Special:ComparePages
'comparepages'     => 'Boki pśirownaś',
'compare-selector' => 'Wersije boka pśirownaś',
'compare-page1'    => 'Bok 1',
'compare-page2'    => 'Bok 2',
'compare-rev1'     => 'Wersija 1',
'compare-rev2'     => 'Wersija 2',
'compare-submit'   => 'Pśirownaś',

# Database error messages
'dberr-header'      => 'Toś ten wiki ma problem',
'dberr-problems'    => 'Wódaj! Toś to sedło ma techniske śěžkosći.',
'dberr-again'       => 'Pócakaj někotare minuty a aktualizěruj bok.',
'dberr-info'        => '(Njejo móžno ze serwerom datoweje banki zwězaś: $1)',
'dberr-usegoogle'   => 'Móžoš mjaztym pśez Google pytaś.',
'dberr-outofdate'   => 'Źiwaj na to, až jich indekse našogo wopśimjeśa by mógli zestarjone byś.',
'dberr-cachederror' => 'Slědujuca jo pufrowana kopija pominanego boka a by mógła zestarjona byś.',

# HTML forms
'htmlform-invalid-input'       => 'Su někotare problemy z twójim zapodaśim',
'htmlform-select-badoption'    => 'Gódnota, kótaruž sy pódał, njejo płaśiwa opcija.',
'htmlform-int-invalid'         => 'Gódnota, kótaruž sy pódał, njejo ceła licba.',
'htmlform-float-invalid'       => 'Gódnota, kótaruž sy pódał, njejo licba.',
'htmlform-int-toolow'          => 'Gódnota, kótaruž sy pódał, jo mjeńša ako minimum $1',
'htmlform-int-toohigh'         => 'Gódnota, kótaruž sy pódał, jo wětša ako maksimum $1',
'htmlform-required'            => 'Toś ta gódnota jo trěbna',
'htmlform-submit'              => 'Wótpósłaś',
'htmlform-reset'               => 'Změny anulěrowaś',
'htmlform-selectorother-other' => 'Druge',

# SQLite database support
'sqlite-has-fts' => 'Wersija $1 z pódpěru za połnotekstowe pytanje',
'sqlite-no-fts'  => 'Wersija $1 bźez pódpěry za połnotekstowe pytanje',

);

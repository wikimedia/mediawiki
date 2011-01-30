<?php
/** Luxembourgish (Lëtzebuergesch)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Kaffi
 * @author Les Meloures
 * @author Purodha
 * @author Reedy
 * @author Robby
 * @author Urhixidur
 * @author לערי ריינהארט
 */

$fallback = 'de';

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Spezial',
	NS_TALK             => 'Diskussioun',
	NS_USER             => 'Benotzer',
	NS_USER_TALK        => 'Benotzer_Diskussioun',
	NS_PROJECT_TALK     => '$1 Diskussioun',
	NS_FILE             => 'Fichier',
	NS_FILE_TALK        => 'Fichier_Diskussioun',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_Diskussioun',
	NS_TEMPLATE         => 'Schabloun',
	NS_TEMPLATE_TALK    => 'Schabloun_Diskussioun',
	NS_HELP             => 'Hëllef',
	NS_HELP_TALK        => 'Hëllef_Diskussioun',
	NS_CATEGORY         => 'Kategorie',
	NS_CATEGORY_TALK    => 'Kategorie_Diskussioun',
);

$namespaceAliases = array(
	'Bild' => NS_FILE,
	'Bild_Diskussioun' => NS_FILE_TALK,
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Duebel_Viruleedungen' ),
	'BrokenRedirects'           => array( 'Futtis_Viruleedungen' ),
	'Disambiguations'           => array( 'Homonymie' ),
	'Userlogin'                 => array( 'Umellen' ),
	'Userlogout'                => array( 'Ofmellen' ),
	'CreateAccount'             => array( 'Benotzerkont_opmaachen' ),
	'Preferences'               => array( 'Astellungen' ),
	'Watchlist'                 => array( 'Iwwerwaachungslëscht' ),
	'Recentchanges'             => array( 'Rezent_Ännerungen' ),
	'Upload'                    => array( 'Eroplueden' ),
	'Listfiles'                 => array( 'Billerlëscht' ),
	'Newimages'                 => array( 'Nei_Biller' ),
	'Listusers'                 => array( 'Lëscht_vun_de_Benotzer' ),
	'Listgrouprights'           => array( 'Lëscht_vun_de_Grupperechter' ),
	'Statistics'                => array( 'Statistik' ),
	'Randompage'                => array( 'Zoufälleg_Säit' ),
	'Lonelypages'               => array( 'Weesesäiten' ),
	'Uncategorizedpages'        => array( 'Säiten_ouni_Kategorie' ),
	'Uncategorizedcategories'   => array( 'Kategorien_ouni_Kategorie' ),
	'Uncategorizedimages'       => array( 'Biller_ouni_Kategorie' ),
	'Uncategorizedtemplates'    => array( 'Schablounen_ouni_Kategorie' ),
	'Unusedcategories'          => array( 'Onbenotze_Kategorien' ),
	'Unusedimages'              => array( 'Onbenotzte_Biller' ),
	'Wantedpages'               => array( 'Gewënschte_Säiten' ),
	'Wantedcategories'          => array( 'Gewënschte_Kategorien' ),
	'Wantedfiles'               => array( 'Gewënschte_Fichieren' ),
	'Wantedtemplates'           => array( 'Gewënschte_Schablounen' ),
	'Mostlinked'                => array( 'Dacks_verlinkte_Säiten' ),
	'Mostlinkedcategories'      => array( 'Dacks_benotzte_Kategorien' ),
	'Mostlinkedtemplates'       => array( 'Dacks_benotzte_Schablounen' ),
	'Mostimages'                => array( 'Dacks_benotzte_Biller' ),
	'Mostcategories'            => array( 'Säite_mat_de_meeschte_Kategorien' ),
	'Mostrevisions'             => array( 'Säite_mat_de_meeschten_Ännerungen' ),
	'Fewestrevisions'           => array( 'Säite_mat_de_mannsten_Ännerungen' ),
	'Shortpages'                => array( 'Kuerz_Säiten' ),
	'Longpages'                 => array( 'Laang_Säiten' ),
	'Newpages'                  => array( 'Nei_Säiten' ),
	'Ancientpages'              => array( 'Al_Säiten' ),
	'Deadendpages'              => array( 'Sakgaasse-Säiten' ),
	'Protectedpages'            => array( 'Protegéiert_Säiten' ),
	'Protectedtitles'           => array( 'Gespaarte_Säiten' ),
	'Allpages'                  => array( 'All_Säiten' ),
	'Prefixindex'               => array( 'Indexsich' ),
	'Ipblocklist'               => array( 'Lëscht_vu_gespaarten_IPen_a_Benotzer' ),
	'Unblock'                   => array( 'Spär_ophiewen' ),
	'Specialpages'              => array( 'Spezialsäiten' ),
	'Contributions'             => array( 'Kontributiounen' ),
	'Emailuser'                 => array( 'Dësem_Benotzer_eng_E-Mail_schécken' ),
	'Confirmemail'              => array( 'E-Mail_confirméieren' ),
	'Whatlinkshere'             => array( 'Linken_op_dës_Säit' ),
	'Recentchangeslinked'       => array( 'Ännerungen_op_verlinkte_Säiten' ),
	'Movepage'                  => array( 'Säit_réckelen' ),
	'Blockme'                   => array( 'Mech_spären' ),
	'Booksources'               => array( 'Bicher_mat_hirer_ISBN_sichen' ),
	'Categories'                => array( 'Kategorien' ),
	'Export'                    => array( 'Exportéieren' ),
	'Version'                   => array( 'Versioun' ),
	'Allmessages'               => array( 'All_Systemmessagen' ),
	'Log'                       => array( 'Logbicher' ),
	'Blockip'                   => array( 'Spären' ),
	'Undelete'                  => array( 'Restauréieren' ),
	'Import'                    => array( 'Importéieren' ),
	'Lockdb'                    => array( 'Datebank_spären' ),
	'Unlockdb'                  => array( 'Spär_vun_der_Datebank_ophiewen' ),
	'Userrights'                => array( 'Benotzerrechter' ),
	'MIMEsearch'                => array( 'Sich_no_MIME-Zorten' ),
	'FileDuplicateSearch'       => array( 'Sich_no_duebele_Fichieren' ),
	'Unwatchedpages'            => array( 'Säiten_déi_net_iwwerwaacht_ginn' ),
	'Listredirects'             => array( 'Viruleedungen' ),
	'Revisiondelete'            => array( 'Versioun_läschen' ),
	'Unusedtemplates'           => array( 'Onbenotzte_Schablounen' ),
	'Randomredirect'            => array( 'Zoufälleg_Viruleedung' ),
	'Mypage'                    => array( 'Meng_Benotzersäit' ),
	'Mytalk'                    => array( 'Meng_Diskussiounssäit' ),
	'Mycontributions'           => array( 'Meng_Kontributiounen' ),
	'Listadmins'                => array( 'Lëscht_vun_den_Administrateuren' ),
	'Listbots'                  => array( 'Botten' ),
	'Popularpages'              => array( 'Beléifste_Säiten' ),
	'Search'                    => array( 'Sichen' ),
	'Resetpass'                 => array( 'Passwuert_zrécksetzen' ),
	'Withoutinterwiki'          => array( 'Säiten_ouni_Interwiki-Linken' ),
	'MergeHistory'              => array( 'Versiounen_zesummeleeën' ),
	'Filepath'                  => array( 'Pad_bäi_de_Fichier' ),
	'Invalidateemail'           => array( 'E-Mailadress_net_confirméieren' ),
	'Blankpage'                 => array( 'Eidel_Säit' ),
	'LinkSearch'                => array( 'Weblink-Sich' ),
	'DeletedContributions'      => array( 'Geläschte_Kontributiounen' ),
	'Tags'                      => array( 'Taggen' ),
	'Activeusers'               => array( 'Aktiv_Benotzer' ),
	'ComparePages'              => array( 'Säite_vergkäichen' ),
	'Badtitle'                  => array( 'Net_valabelen_Titel' ),
);

$magicWords = array(
	'numberofarticles'      => array( '1', 'Artikelen', 'ARTIKELANZAHL', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'Fichieren', 'DATEIANZAHL', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'Benotzerzuel', 'BENUTZERANZAHL', 'NUMBEROFUSERS' ),
	'numberofactiveusers'   => array( '1', 'Aktiv_Benotzer', 'AKTIVE_BENUTZER', 'NUMBEROFACTIVEUSERS' ),
	'pagename'              => array( '1', 'Säitennumm', 'SEITENNAME', 'PAGENAME' ),
	'namespace'             => array( '1', 'Nummraum', 'NAMENSRAUM', 'NAMESPACE' ),
	'subjectspace'          => array( '1', 'Haaptnummraum', 'HAUPTNAMENSRAUM', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectpagename'       => array( '1', 'Haaptsäit', 'HAUPTSEITE', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'img_right'             => array( '1', 'riets', 'rechts', 'right' ),
	'img_left'              => array( '1', 'lénks', 'links', 'left' ),
	'img_none'              => array( '1', 'ouni', 'ohne', 'none' ),
	'img_center'            => array( '1', 'zentréiert', 'zentriert', 'center', 'centre' ),
	'img_framed'            => array( '1', 'gerummt', 'gerahmt', 'framed', 'enframed', 'frame' ),
	'img_frameless'         => array( '1', 'net_gerummt', 'rahmenlos', 'frameless' ),
	'img_border'            => array( '1', 'bord', 'rand', 'border' ),
	'grammar'               => array( '0', 'GRAMMAIRE', 'GRAMMATIK:', 'GRAMMAR:' ),
	'plural'                => array( '0', 'PLURAL', 'PLURAL:' ),
	'formatnum'             => array( '0', 'ZUELEFORMAT', 'ZAHLENFORMAT', 'FORMATNUM' ),
	'special'               => array( '0', 'spezial', 'special' ),
	'hiddencat'             => array( '1', '__VERSTOPPTE_KATEGORIE__', '__VERSTECKTE_KATEGORIE__', '__WARTUNGSKATEGORIE__', '__HIDDENCAT__' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Linken ënnersträichen:',
'tog-highlightbroken'         => 'Format vu futtise Linken <a href="" class="new">esou</a> (alternativ: <a href="" class="internal">?</a>).',
'tog-justify'                 => "Ränner vum Text riichten (''justify'')",
'tog-hideminor'               => 'Verstopp kleng Ännerungen an de rezenten Ännerungen',
'tog-hidepatrolled'           => 'Iwwerkuckten Ännerungen an de "Rezenten Ännerungen" verstoppen',
'tog-newpageshidepatrolled'   => 'Iwwerkuckte Säiten op der Lëscht vun den "Neie Säite" verstoppen',
'tog-extendwatchlist'         => 'Iwwerwaachungslëscht op all Ännerungen ausbreeden, net nëmmen op déi rezentst',
'tog-usenewrc'                => 'Erweidert rezent Ännerunge benotzen (verlaangt JavaScript)',
'tog-numberheadings'          => 'Iwwerschrëften automatesch numeréieren',
'tog-showtoolbar'             => 'Ännerungstoolbar weisen (JavaScript)',
'tog-editondblclick'          => 'Säite mat Duebelklick veränneren (JavaScript)',
'tog-editsection'             => "Linke fir d'Ännere vun eenzelnen Abschnitter weisen",
'tog-editsectiononrightclick' => 'Eenzel Abschnitter mat Rietsklick änneren (JavaScript)',
'tog-showtoc'                 => 'Inhaltsverzeechnes weise bei Säite mat méi wéi dräi Iwwerschrëften',
'tog-rememberpassword'        => 'Meng Umeldung mat dësem Browser(fir maximal $1 {{PLURAL:$1|Dag|Deeg}}) verhalen',
'tog-watchcreations'          => 'Säiten déi ech nei uleeën automatesch op meng Iwwerwaachungslëscht setzen',
'tog-watchdefault'            => 'Säiten déi ech änneren op meng Iwwerwaachungslëscht setzen',
'tog-watchmoves'              => 'Säiten déi ech réckelen automatesch op meng Iwwerwaachungslëscht setzen',
'tog-watchdeletion'           => 'Säiten déi ech läschen op meng Iwwerwaachungslëscht setzen',
'tog-minordefault'            => "Alles wat ech änneren automatesch als 'Kleng Ännerungen' weisen",
'tog-previewontop'            => "Déi ''nach-net gespäichert Versioun'' iwwer der Ännerungsfënster weisen",
'tog-previewonfirst'          => "Beim éischten Änneren déi  ''nach net gespäichert Versioun'' weisen.",
'tog-nocache'                 => 'Säitecache vum Browser desaktivéieren',
'tog-enotifwatchlistpages'    => 'Schéckt mir eng E-Mail wann eng vun de Säiten op menger Iwwerwaachungslëscht geännert gëtt',
'tog-enotifusertalkpages'     => 'Schéckt mir E-Maile wa meng Diskussiounssäit geännert gëtt.',
'tog-enotifminoredits'        => 'Schéckt mir och bei klengen Ännerungen op vu mir iwwerwaachte Säiten eng E-Mail.',
'tog-enotifrevealaddr'        => 'Meng E-Mailadress an de Benoriichtigungsmaile weisen.',
'tog-shownumberswatching'     => "D'Zuel vun de Benotzer déi dës Säit iwwerwaache weisen",
'tog-oldsig'                  => 'Ausgesi vun der aktueller Ënnerschrëft:',
'tog-fancysig'                => 'Ënnerschrëft als Wiki-Text behandelen (Ouni automatesche Link)',
'tog-externaleditor'          => 'Externen Editeur als Standard benotzen (Nëmme fir Experten, et musse speziell Astellungen op ärem Computer gemaach ginn. [http://www.mediawiki.org/wiki/Manual:External_editors Méi Informatiounen.])',
'tog-externaldiff'            => 'En Externen Diff-Programm als Standard benotzen (nëmme fir Experten, et musse speziell Astellungen op ärem Computer gemaach ginn. [http://www.mediawiki.org/wiki/Manual:External_editors Méi Informatiounen])',
'tog-showjumplinks'           => 'Aktivéiere vun de "Sprang op"-Linken',
'tog-uselivepreview'          => 'Live-Preview benotzen (JavaScript) (experimentell)',
'tog-forceeditsummary'        => 'Warnen, wa beim Späicheren de Resumé feelt',
'tog-watchlisthideown'        => 'Meng Ännerungen op menger Iwwerwaachungslëscht verstoppen',
'tog-watchlisthidebots'       => 'Ännerunge vu Botten op menger Iwwerwaachungslëscht verstoppen',
'tog-watchlisthideminor'      => 'Kleng Ännerungen op menger Iwwerwaachungslëscht verstoppen',
'tog-watchlisthideliu'        => 'Ännerunge vun ugemellte Benotzer verstoppen',
'tog-watchlisthideanons'      => 'Ännerunge vun anonyme Benotzer (IP-Adressen) verstoppen',
'tog-watchlisthidepatrolled'  => 'Iwwerkuckten Ännerungen op der Iwwerwaachungslëscht verstoppen',
'tog-nolangconversion'        => 'Ëmwandlung vu Sproochvarianten ausschalten',
'tog-ccmeonemails'            => 'Schéck mir eng Kopie vun de Mailen, déi ech anere Benotzer schécken.',
'tog-diffonly'                => "Weis bei Versiounsvergläicher just d'Ënnerscheeder an net déi ganz Säit",
'tog-showhiddencats'          => 'Verstoppte Kategorië weisen',
'tog-noconvertlink'           => 'Ëmwandlung vum Titel desaktivéieren',
'tog-norollbackdiff'          => 'Ënnerscheed nom Zrécksetzen ënnerdrécken',

'underline-always'  => 'Ëmmer',
'underline-never'   => 'Ni',
'underline-default' => 'vun der Browserastellung ofhängeg',

# Font style option in Special:Preferences
'editfont-style'     => "Schrëftfamill fir d'Ännerungsfënster:",
'editfont-default'   => 'Standard vum Browser',
'editfont-monospace' => 'Schrëft mat enger fixer Breet (fir all Zeechen)',
'editfont-sansserif' => 'Schrëft ouni Serifen',
'editfont-serif'     => 'Schrëft mat Serifen',

# Dates
'sunday'        => 'Sonndeg',
'monday'        => 'Méindeg',
'tuesday'       => 'Dënschdeg',
'wednesday'     => 'Mëttwoch',
'thursday'      => 'Donneschdeg',
'friday'        => 'Freideg',
'saturday'      => 'Samsdeg',
'sun'           => 'Son',
'mon'           => 'Méi',
'tue'           => 'Dën',
'wed'           => 'Mët',
'thu'           => 'Don',
'fri'           => 'Fre',
'sat'           => 'Sam',
'january'       => 'Januar',
'february'      => 'Februar',
'march'         => 'Mäerz',
'april'         => 'Abrëll',
'may_long'      => 'Mee',
'june'          => 'Juni',
'july'          => 'Juli',
'august'        => 'August',
'september'     => 'September',
'october'       => 'Oktober',
'november'      => 'November',
'december'      => 'Dezember',
'january-gen'   => 'Januar',
'february-gen'  => 'Februar',
'march-gen'     => 'Mäerz',
'april-gen'     => 'Abrëll',
'may-gen'       => 'Mee',
'june-gen'      => 'Juni',
'july-gen'      => 'Juli',
'august-gen'    => 'August',
'september-gen' => 'September',
'october-gen'   => 'Oktober',
'november-gen'  => 'November',
'december-gen'  => 'Dezember',
'jan'           => 'Jan.',
'feb'           => 'Feb.',
'mar'           => 'Mäe.',
'apr'           => 'Abr.',
'may'           => 'Mee',
'jun'           => 'Jun.',
'jul'           => 'Jul.',
'aug'           => 'Aug.',
'sep'           => 'Sep.',
'oct'           => 'Okt.',
'nov'           => 'Nov.',
'dec'           => 'Dez.',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategorie|Kategorien}}',
'category_header'                => 'Säiten an der Kategorie "$1"',
'subcategories'                  => 'Ënnerkategorien',
'category-media-header'          => 'Medien an der Kategorie "$1"',
'category-empty'                 => "''Dës Kategorie ass fir den Ament eidel''",
'hidden-categories'              => '{{PLURAL:$1|Verstoppt Kategorie|Verstoppt Kategorien}}',
'hidden-category-category'       => 'Verstoppt Kategorien',
'category-subcat-count'          => 'Dës Kategorie huet {{PLURAL:$2|nëmmen dës Ënnerkategorie.|dës {{PLURAL:$1|Ënnerkategorie|$1 Ënnerkategorien}}, vu(n) $2 am Ganzen.}}',
'category-subcat-count-limited'  => 'Dës Kategorie huet dës {{PLURAL:$1|Ënnerkategorie|$1 Ënnerkategorien}}.',
'category-article-count'         => 'An dëser Kategorie {{PLURAL:$2|ass just dës Säit.|{{PLURAL:$1|ass just dës Säit|si(nn) $1 Säiten}}, vu(n) $2 am Ganzen.}}',
'category-article-count-limited' => 'Dës {{PLURAL:$1|Säit ass|$1 Säite sinn}} an dëser Kategorie.',
'category-file-count'            => 'An dëser Kategorie {{PLURAL:$2|ass just dëse Fichier.|{{PLURAL:$1|ass just dëse Fichier|si(nn) $1 Fichieren}}, vu(n) $2 am Ganzen.}}',
'category-file-count-limited'    => '{{PLURAL:$1|Dëse Fichier ass|Dës $1 Fichiere sinn}} an dëser Kategorie.',
'listingcontinuesabbrev'         => '(Fortsetzung)',
'index-category'                 => 'Indexéiert Säiten',
'noindex-category'               => 'Net-indexéiert Säiten',

'mainpagetext'      => "'''MediaWiki gouf installéiert.'''",
'mainpagedocfooter' => "Kuckt w.e.g. [http://meta.wikimedia.org/wiki/Help:Contents d'Benotzerhandbuch] fir den Interface ze personnaliséieren.

== Starthëllefen ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Hëllef bei der Konfiguratioun]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki-FAQ]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Mailinglëscht vun neie MediaWiki-Versiounen]",

'about'         => 'A propos',
'article'       => 'Säit',
'newwindow'     => '(geet an enger neier Fënster op)',
'cancel'        => 'Zréck',
'moredotdotdot' => 'Méi …',
'mypage'        => 'Meng Säit',
'mytalk'        => 'Meng Diskussioun',
'anontalk'      => 'Diskussioun fir dës IP Adress',
'navigation'    => 'Navigatioun',
'and'           => '&#32;an',

# Cologne Blue skin
'qbfind'         => 'Fannen',
'qbbrowse'       => 'Duerchsichen',
'qbedit'         => 'Änneren',
'qbpageoptions'  => 'Säitenoptiounen',
'qbpageinfo'     => 'Kontext',
'qbmyoptions'    => 'Meng Säiten',
'qbspecialpages' => 'Spezialsäiten',
'faq'            => 'FAQ',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection'       => 'Sujet derbäisetzen',
'vector-action-delete'           => 'Läschen',
'vector-action-move'             => 'Réckelen',
'vector-action-protect'          => 'Spären',
'vector-action-undelete'         => 'Restauréieren',
'vector-action-unprotect'        => 'Spär ophiewen',
'vector-simplesearch-preference' => 'Verbessert Sichvirschléi aktiviéieren (nëmme beim Ausgesinn Vector)',
'vector-view-create'             => 'Uleeën',
'vector-view-edit'               => 'Änneren',
'vector-view-history'            => 'Versioune weisen',
'vector-view-view'               => 'Liesen',
'vector-view-viewsource'         => 'Quellcode weisen',
'actions'                        => 'Aktiounen',
'namespaces'                     => 'Nummraim',
'variants'                       => 'Varianten',

'errorpagetitle'    => 'Feeler',
'returnto'          => 'Zréck op $1.',
'tagline'           => 'Vu {{SITENAME}}',
'help'              => 'Hëllef',
'search'            => 'Sichen',
'searchbutton'      => 'Volltext-Sich',
'go'                => 'Lass',
'searcharticle'     => 'Säit',
'history'           => 'Historique vun der Säit',
'history_short'     => 'Versiounen',
'updatedmarker'     => "geännert zënter ech d'Säit fir d'lescht gekuckt hunn",
'info_short'        => 'Informatioun',
'printableversion'  => 'Drockversioun',
'permalink'         => 'Zitéierfäege Link',
'print'             => 'Drécken',
'view'              => 'Weisen',
'edit'              => 'Änneren',
'create'            => 'Uleeën',
'editthispage'      => 'Dës Säit änneren',
'create-this-page'  => 'Dës Säit uleeën',
'delete'            => 'Läschen',
'deletethispage'    => 'Dës Säit läschen',
'undelete_short'    => '$1 {{PLURAL:$1|Versioun|Versioune}} restauréieren',
'viewdeleted_short' => '{{PLURAL:$1|Eng geläschte Versioun|$1 geläschte Versioune}} weisen',
'protect'           => 'Spären',
'protect_change'    => 'änneren',
'protectthispage'   => 'Dës Säit schützen',
'unprotect'         => 'Spär ophiewen',
'unprotectthispage' => "D'Spär vun dëser Säit ophiewen",
'newpage'           => 'Nei Säit',
'talkpage'          => 'Diskussioun',
'talkpagelinktext'  => 'Diskussioun',
'specialpage'       => 'Spezialsäit',
'personaltools'     => 'Perséinlech Tools',
'postcomment'       => 'Neien Abschnitt',
'articlepage'       => 'Säit',
'talk'              => 'Diskussioun',
'views'             => 'Affichagen',
'toolbox'           => 'Geschirkëscht',
'userpage'          => 'Benotzersäit',
'projectpage'       => 'Meta-Text',
'imagepage'         => 'Billersäit kucken',
'mediawikipage'     => 'Säit mat de Message weisen',
'templatepage'      => 'Schabloune(säit) weisen',
'viewhelppage'      => 'Hëllefssäit weisen',
'categorypage'      => 'Kategoriesäit weisen',
'viewtalkpage'      => 'Diskussioun weisen',
'otherlanguages'    => 'Aner Sproochen',
'redirectedfrom'    => '(Virugeleet vu(n) $1)',
'redirectpagesub'   => 'Viruleedungssäit',
'lastmodifiedat'    => "Dës Säit gouf den $1 ëm $2 Auer fir d'lescht geännert.",
'viewcount'         => 'Dës Säit gouf bis elo {{PLURAL:$1|emol|$1-mol}} ofgefrot.',
'protectedpage'     => 'Gespaarte Säit',
'jumpto'            => 'Wiesselen op:',
'jumptonavigation'  => 'Navigatioun',
'jumptosearch'      => 'Sich',
'view-pool-error'   => "Pardon, d'Servere si fir de Moment iwwerlaascht.
Zevill Benotzer versichen dës Säit ze gesinn.
Waart w.e.g. e bëssen ier Dir versicht dës Säit nach emol opzeruffen.

$1",
'pool-timeout'      => "Timeout bis d'Spär opgehuewen ass",
'pool-queuefull'    => 'Pool-Queue ass voll',
'pool-errorunknown' => 'Onbekannte Feeler',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Iwwer {{SITENAME}}',
'aboutpage'            => 'Project:Iwwer',
'copyright'            => 'Inhalt ass zur Verfügung gestallt ënnert der $1.<br />',
'copyrightpage'        => '{{ns:project}}:Copyright',
'currentevents'        => 'Aktualitéit',
'currentevents-url'    => 'Project:Aktualitéit',
'disclaimers'          => 'Impressum',
'disclaimerpage'       => 'Project:Impressum',
'edithelp'             => 'Hëllef beim Änneren',
'edithelppage'         => 'Help:Wéi änneren ech eng Säit',
'helppage'             => 'Help:Hëllef',
'mainpage'             => 'Haaptsäit',
'mainpage-description' => 'Haaptsäit',
'policy-url'           => 'Project:Richtlinnen',
'portal'               => '{{SITENAME}}-Portal',
'portal-url'           => 'Project:Kommunautéit',
'privacy'              => 'Dateschutz',
'privacypage'          => 'Project:Dateschutz',

'badaccess'        => 'Net genuch Rechter',
'badaccess-group0' => 'Dir hutt net déi néideg Rechter fir dës Aktioun duerchzeféieren.',
'badaccess-groups' => "D'Aktioun déi dir gewielt hutt, kann nëmme vu Benotzer aus {{PLURAL:$2|der Grupp|enger vun de Gruppen}} $1 duerchgefouert ginn.",

'versionrequired'     => 'Versioun $1 vu MediaWiki gëtt gebraucht',
'versionrequiredtext' => "D'Versioun $1 vu MediaWiki ass néideg, fir dës Säit ze benotzen. Kuckt d'[[Special:Version|Versiounssäit]]",

'ok'                      => 'OK',
'retrievedfrom'           => 'Vun „$1“',
'youhavenewmessages'      => 'Dir hutt $1 ($2).',
'newmessageslink'         => 'nei Messagen',
'newmessagesdifflink'     => 'Nei Messagen',
'youhavenewmessagesmulti' => 'Dir hutt nei Messagen op $1',
'editsection'             => 'änneren',
'editold'                 => 'änneren',
'viewsourceold'           => 'Quellcode kucken',
'editlink'                => 'änneren',
'viewsourcelink'          => 'Quelltext weisen',
'editsectionhint'         => 'Abschnitt änneren: $1',
'toc'                     => 'Inhaltsverzeechnes',
'showtoc'                 => 'weisen',
'hidetoc'                 => 'verstoppen',
'collapsible-collapse'    => 'Zesummeklappen',
'collapsible-expand'      => 'Opklappen',
'thisisdeleted'           => '$1 kucken oder zrécksetzen?',
'viewdeleted'             => 'Weis $1?',
'restorelink'             => '$1 geläscht {{PLURAL:$1|Versioun|Versiounen}}',
'feedlinks'               => 'Feed:',
'feed-invalid'            => 'Net valabelen Typ vun Abonnements-Feed',
'feed-unavailable'        => "''Syndication Feeds'' stinn net zur Verfügung.",
'site-rss-feed'           => 'RSS-Feed fir $1',
'site-atom-feed'          => 'Atom-Feed fir $1',
'page-rss-feed'           => 'RSS-Feed fir "$1"',
'page-atom-feed'          => 'Atom-Feed fir "$1"',
'red-link-title'          => '$1 (Säit gëtt et net)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Säit',
'nstab-user'      => 'Benotzersäit',
'nstab-media'     => 'Media Säit',
'nstab-special'   => 'Spezialsäit',
'nstab-project'   => 'Projetssäit:',
'nstab-image'     => 'Fichier',
'nstab-mediawiki' => 'Systemmessage',
'nstab-template'  => 'Schabloun',
'nstab-help'      => 'Hëllef-Säit',
'nstab-category'  => 'Kategorie',

# Main script and global functions
'nosuchaction'      => 'Dës Aktioun gëtt et net',
'nosuchactiontext'  => 'Déi Aktioun, déi an der URL ugi war, ass net valabel.
Méiglecherweis hutt dir Iech bei der URL vertippt, oder Dir hutt op en net korrekte Link geklickt.
Et kann awer och sinn datt et e Bug a(n) {{SITENAME}} gëtt.',
'nosuchspecialpage' => 'Spezialsäit gëtt et net',
'nospecialpagetext' => '<strong>Dir hutt eng Spezialsäit ofgefrot déi et net gëtt.</strong>

All Spezialsäiten déi et gëtt, sinn op der [[Special:SpecialPages|Lëscht vun de Spezialsäiten]] ze fannen.',

# General errors
'error'                => 'Feeler',
'databaseerror'        => 'Datebank Feeler',
'dberrortext'          => 'En Datebank Syntax Feeler ass opgetrueden.
Dëst kann op e Feeler an der Software hiweisen.
De leschte versichten Datebank Query war:
<blockquote><tt>$1</tt></blockquote>
vun der Funktioun "<tt>$2</tt>".
D\'Datebank huet de Feeler "<tt>$3: $4</tt>" gemellt.',
'dberrortextcl'        => 'En Datebank Syntax Feeler ass opgetrueden.
De leschten Datebank Query war:
"$1"
vun der Funktioun "$2".
D\'Datebank huet de Feeler "$3: $4" gemellt.',
'laggedslavemode'      => 'Opgepasst: Dës Säit ass net onbedéngt um neiste Stand.',
'readonly'             => "D'Datebank ass gespaart",
'enterlockreason'      => "Gitt w.e.g. e Grond u firwat d'Datebak gespaart ass, a wéi laang dës Spär ongeféier bestoe soll.",
'readonlytext'         => "D'Datebank ass elo fir all Ännerunge gespaart, wahrscheinlech wéinst Maintenance vun der Datebank, duerno ass erëm alles beim alen.

Den Administrateur huet dës Erklärung uginn: $1",
'missing-article'      => "Den Text „$1“ $2 gouf net an der Datebank fonnt.

Dat geschitt normalerweis duerch e Link op eng Säit déi geläscht oder geréckelt gouf.

Wann dat net de Fall ass, hutt Dir eventuell e Feeler an der Software fonnt.
Mellt dëst w.e.g. bei engem [[Special:ListUsers/sysop|Administrateur]] a vergiesst net d'URL unzeginn.",
'missingarticle-rev'   => '(Versiounsnummer: $1)',
'missingarticle-diff'  => '(Ënnerscheed tëscht Versiounen: $1, $2)',
'readonly_lag'         => "D'Datebank gouf automatesch gespaart fir datt d'Zweetserveren (slaves) nees mat dem Haaptserver (master) synchron geschalt kënne ginn.",
'internalerror'        => 'Interne Feeler',
'internalerror_info'   => 'Interne Feeler: $1',
'fileappenderrorread'  => '"$1" konnt während dem Derbäisetze net gelies ginn.',
'fileappenderror'      => '"$1" konnt net bäi "$2" derbäigesat ginn.',
'filecopyerror'        => 'De Fichier "$1" konnt net op "$2" kopéiert ginn.',
'filerenameerror'      => 'De Fichier "$1" konnt net op "$2" ëmbenannt ginn.',
'filedeleteerror'      => 'De Fichier "$1" konnt net geläscht ginn.',
'directorycreateerror' => 'De Repertoire "$1" konnt net geschafe ginn.',
'filenotfound'         => 'De Fichier "$1" gouf net fonnt.',
'fileexistserror'      => 'De Fichier "$1" konnt net geschriwwe ginn, well et dee Fichier scho gëtt.',
'unexpected'           => 'Onerwaarte Wäert: "$1"="$2".',
'formerror'            => 'Feeler: Dat wat Dir aginn hutt konnt net verschafft ginn.',
'badarticleerror'      => 'Dës Aktioun kann net op dëser Säit duerchgefouert ginn.',
'cannotdelete'         => 'D\'Bild oder d\'Säit "$1" konnt net geläscht ginn.
Et ka sinn datt et scho vun engem Anere geläscht gouf.',
'badtitle'             => 'Schlechten Titel',
'badtitletext'         => 'De gewënschten Titel ass net valabel, eidel, oder een net korrekten Interwiki Link.',
'perfcached'           => 'Dës Date kommen aus dem Cache a si méiglecherweis net aktuell:',
'perfcachedts'         => 'Dës Donnéeë kommen aus dem Cache, lescht Aktualisatioun: $1',
'querypage-no-updates' => "D'Aktualiséierung vun dëser Säit ass zur Zäit ausgeschalt. D'Date gi bis op weideres net aktualiséiert.'''",
'wrong_wfQuery_params' => 'Falsche Parameter fir wfQuery()<br />
Funktioun: $1<br />
Ufro: $2',
'viewsource'           => 'Quelltext kucken',
'viewsourcefor'        => 'fir $1',
'actionthrottled'      => 'Dës Aktioun gouf gebremst',
'actionthrottledtext'  => 'Fir géint de Spam virzegoen, ass dës Aktioun esou programméiert datt Dir se an enger kuerzer Zäit nëmme limitéiert dacks maache kënnt. Dir hutt dës Limite iwwerschratt. Versicht et w.e.g. an e puer Minutten nach eng Kéier.',
'protectedpagetext'    => 'Dës Säit ass fir Ännerunge gespaart.',
'viewsourcetext'       => 'Dir kënnt de Quelltext vun dëser Säit kucken a kopéieren:',
'protectedinterface'   => 'Op dëser Säit fannt Dir Text fir de Sprooch-Interface vun der Software an dofir ass si gespaart fir Mëssbrauch ze verhënneren.',
'editinginterface'     => "'''Opgepasst:''' Dir sidd am Gaang, eng Säit z'änneren, déi do ass, fir Interface-Text fir d'Software ze liwweren. Ännerungen op dëser Säit änneren den Interface-Text, jee no Kontext, op allen oder verschiddene Säiten, déi vun alle Benotzer gesi ginn. Fir d'Iwwersetzungen z'änneren invitéiere mir Iech de [http://translatewiki.net/wiki/Main_Page?setlang=lb Projet translatewiki.net] vun den internationale Messagen ze benotzen.",
'sqlhidden'            => '(SQL-Offro verstoppt)',
'cascadeprotected'     => 'Dës Säit gouf fir Ännerunge gespaart, well se duerch Cascadeprotectioun vun {{PLURAL:$1|dëser Säit|dëse Säite}} gespaart ass mat der Cascadenoptioun:
$2',
'namespaceprotected'   => "Dir hutt net déi néideg Rechter fir d'Säiten am Nummraum '''$1''' ze änneren.",
'customcssjsprotected' => "Dir hutt net déi néideg Rechter fir dës Säit z'änneren, well si zu de perséinlechen Astellunge vun engem anere Benotzer gehéiert.",
'ns-specialprotected'  => 'Spezialsäite kënnen net verännert ginn.',
'titleprotected'       => "Eng Säit mat dësem Numm kann net ugeluecht ginn. Dës Spär gouf vum [[User:$1|$1]] gemaach deen als Grond ''$2'' uginn huet.",

# Virus scanner
'virus-badscanner'     => "Schlecht Configuratioun: onbekannte  Virescanner: ''$1''",
'virus-scanfailed'     => 'De Scan huet net fonctionnéiert (Code $1)',
'virus-unknownscanner' => 'onbekannten Antivirus:',

# Login and logout pages
'logouttext'                 => "'''Dir sidd elo ofgemellt.'''

Dir kënnt {{SITENAME}} elo anonym benotzen, oder Iech [[Special:UserLogin|erëm umellen]].

Opgepasst: Op verschiddene Säite gesäit et nach esou aus, wéi wann Dir nach ugemellt wiert, bis Dir Ärem Browser säin Tëschespäicher (cache) eidel maacht.",
'welcomecreation'            => '== Wëllkomm, $1! ==
Äre Kont gouf kreéiert.
Denkt drun, Är [[Special:Preferences|{{SITENAME}}-Astellungen]] unzepassen.',
'yourname'                   => 'Benotzernumm:',
'yourpassword'               => 'Passwuert:',
'yourpasswordagain'          => 'Passwuert nach eemol antippen:',
'remembermypassword'         => 'Meng Umeldung op dësem Computer (fir maximal $1 {{PLURAL:$1|Dag|Deeg}}) verhalen',
'securelogin-stick-https'    => 'Nom Umelle mat HTTPS verbonn bleiwen',
'yourdomainname'             => 'Ären Domain',
'externaldberror'            => 'Entweder ass e Feeler bei der externer Authentifizéierung geschitt, oder Dir däerft Ären externe Benotzerkont net aktualiséieren.',
'login'                      => 'Umellen',
'nav-login-createaccount'    => 'Aloggen',
'loginprompt'                => "Fir sech op {{SITENAME}} umellen ze kënnen, mussen d'Cookien aktivéiert sinn.",
'userlogin'                  => 'Aloggen / Benotzerkont uleeën',
'userloginnocreate'          => 'Umellen',
'logout'                     => 'Ofmellen',
'userlogout'                 => 'Ausloggen',
'notloggedin'                => 'Net ageloggt',
'nologin'                    => "Hutt Dir kee Benotzerkont? '''$1'''.",
'nologinlink'                => 'Neie Benotzerkont maachen',
'createaccount'              => 'Neie Kont opmaachen',
'gotaccount'                 => "Dir hutt schonn e Benotzerkont? '''$1'''.",
'gotaccountlink'             => 'Umellen',
'createaccountmail'          => 'Via E-Mail',
'createaccountreason'        => 'Grond:',
'badretype'                  => 'Är Passwierder stëmmen net iwwerdeneen.',
'userexists'                 => 'Dëse Benotzernumm gëtt scho benotzt.
Sicht Iech een anere Benotzernumm.',
'loginerror'                 => 'Feeler beim Umellen',
'createaccounterror'         => 'Benotzerkont konnt net opgemaach ginn: $1',
'nocookiesnew'               => "De Benotzerkont gouf ugeluecht, awer Dir sidd net ageloggt.
{{SITENAME}} brauch fir dës Funktioun Cookien.
Dir hutt d'Cookien desaktivéiert.
Aktivéiert déi w.e.g. a loggt Iech da mat Ärem neie Benotzernomm a mat dem respektive Passwuert an.",
'nocookieslogin'             => "{{SITENAME}} benotzt Cookië beim Umelle vun de Benotzer. Dir hutt Cookien ausgeschalt, w.e.g aktivéiert d'Cookien a versicht et nach eng Kéier.",
'nocookiesfornew'            => 'De Benotzerkont gouf net ugeluecht, well mir seng Quell net bestëmme konnten.
Vergewëssert Iech datt Dir Cookien zouloosst, luet dës Säit nei a probéiert nach emol.',
'noname'                     => 'Dir hutt kee gëltege Benotzernumm uginn.',
'loginsuccesstitle'          => 'Umeldung huet geklappt',
'loginsuccess'               => "'''Dir sidd elo als \"\$1\" op {{SITENAME}} ugemellt.'''",
'nosuchuser'                 => 'Et gëtt kee Benotzernumm mam Numm "$1".
Beim Benotzernumm gëtt tëschent groussen a klenge Buschtawen ënnerscheet (casesensitive).
Kuckt w.e.g. op d\'Schreifweis richteg ass, oder [[Special:UserLogin/signup|maacht en neie Benotzerkont op]].',
'nosuchusershort'            => 'De Benotzernumm "<nowiki>$1</nowiki>" gëtt et net. Kuckt w.e.g. op d\'Schreifweis richteg ass.',
'nouserspecified'            => 'Gitt w.e.g. e Benotzernumm un.',
'login-userblocked'          => 'Dëse Benotzer ass gespaart. Aloggen ass net erlaabt.',
'wrongpassword'              => 'Dir hutt e falscht (oder kee) Passwuert aginn. Probéiert w.e.g. nach eng Kéier.',
'wrongpasswordempty'         => "D'Passwuert dat Dir aginn hutt war eidel. Probéiert w.e.g. nach eng Kéier.",
'passwordtooshort'           => 'Passwierder musse mindestens {{PLURAL:$1|1 Zeeche|$1 Zeeche}} laang sinn.',
'password-name-match'        => 'Äert Passwuert muss verschidde vun Ärem Benotzernumm sinn.',
'password-login-forbidden'   => "D'Benotze vun dësem Benotzernumm a Passwuert gouf verbueden.",
'mailmypassword'             => 'Neit Passwuert per E-Mail kréien',
'passwordremindertitle'      => 'Neit Passwuert fir ee {{SITENAME}}-Benotzerkont',
'passwordremindertext'       => 'Iergendeen (waarscheinlech Dir, mat der IP-Adress $1) huet en neit Passwuert fir {{SITENAME}} ($4) gefrot. Een temporäert Passwuert fir de Benotzer $2 gouf ugeluecht an et ass: $3. Wann et dat ass, wat Dir wollt, da sollt Dir Iech elo aloggen an en neit Passwuert eraussichen. Äert temporäert Passwuert leeft a(n) {{PLURAL:$5|engem Dag| $5 Deeg}} of.

Wann een aneren dës Ufro sollt gemaach hunn oder wann Dir Iech an der Zwëschenzäit nees un Äert Passwuert erënnere kënnt, an Dir Äert Passwuert net ännere wëllt, da kënnt Dir weider Äert aalt Passwuert benotzen.',
'noemail'                    => 'De Benotzer "$1" huet keng E-Mailadress uginn.',
'noemailcreate'              => 'Dir musst eng valabel E-Mailadress uginn',
'passwordsent'               => 'Een neit Passwuert gouf un déi fir de Benotzer "$1" gespäichert E-Mailadress geschéckt.
Mellt Iech w.e.g. domat un, soubal Dir et kritt hutt.',
'blocked-mailpassword'       => "Déi vun Iech benotzten IP-Adress ass fir d'Ännere vu Säite gespaart. Fir Mëssbrauch ze verhënneren, gouf d'Méiglechkeet fir een neit Passwuert unzefroen och gespaart.",
'eauthentsent'               => "Eng Confirmatiouns-E-Mail gouf un déi Adress geschéckt déi Dir uginn hutt.<br />
Ier iergendeng E-Mail vun anere Benotzer op dee Kont geschéckt ka ginn, musst Dir als éischt d'Instructiounen an der Confirmatiouns-E-Mail befollegen, fir ze bestätegen datt de Kont wierklech Ären eegenen ass.",
'throttled-mailpassword'     => "An {{PLURAL:$1|der leschter Stonn|de leschte(n) $1 Stonnen}} gouf eng Erënenrung un d'Passwuert verschéckt.
Fir de Mëssbrauch vun dëser Funktioun ze verhënneren kann nëmmen all {{PLURAL:$1|Stonn|$1 Stonnen}} esou eng Erënnerung verschéckt ginn.",
'mailerror'                  => 'Feeler beim Schécke vun der E-Mail: $1',
'acct_creation_throttle_hit' => 'Visiteure vun dëser Wiki déi Är IP-Adress hu {{PLURAL:$1|schonn $1 Benotzerkont|scho(nn) $1 Benotzerkonten}} an de leschten Deeg opgemaach, dëst ass déi maximal Zuel déi an dësem Zäitraum erlaabt ass.
Dofir kënne Visiteure déi dës IP-Adress benotzen den Ament keng Benotzerkonten opmaachen.',
'emailauthenticated'         => 'Är E-Mailadress gouf den $2 ëm $3 Auer bestätegt.',
'emailnotauthenticated'      => 'Är E-Mail Adress gouf <strong>nach net confirméiert</strong>.<br />
Dowéinst ass et bis ewell net méiglech, fir déi folgend Funktiounen E-Mailen ze schécken oder ze kréien.',
'noemailprefs'               => 'Gitt eng E-Mailadress bei Ären Astellungen un, fir datt déi Funktioune funktionéieren.',
'emailconfirmlink'           => 'Confirméiert är E-Mailadress w.e.g..',
'invalidemailaddress'        => 'Dës E-Mailadress gëtt net akzeptéiert well se en ongëltegt Format (z.B. ongëlteg Zeechen) ze hu schéngt.
Gitt eng valabel E-Mailadress an oder loosst dëst Feld eidel.',
'accountcreated'             => 'De Kont gouf geschaf',
'accountcreatedtext'         => 'De Benotzerkont fir $1 gouf geschaf.',
'createaccount-title'        => 'Opmaache vun engem Benotzerkont op {{SITENAME}}',
'createaccount-text'         => 'Et gouf e Benotzerkont "$2" fir Iech op {{SITENAME}} ($4) ugeluecht mat dem Passwuert "$3".
Dir sollt Iech aloggen an Äert Passwuert elo änneren.

Wann dëse Benotzerkont ongewollt ugeluecht gouf, kënnt Dir dës Noriicht einfach ignoréieren.',
'usernamehasherror'          => "Am Benotzernumm däerfe keng ''hash'' Zeeche sinn",
'login-throttled'            => "Dir hutt zevill dacks versicht d'Passwuert vun dësem Benotzerkont anzeginn.
Waart w.e.g. ier Dir et nach eng Kéier versicht.",
'loginlanguagelabel'         => 'Sprooch: $1',
'suspicious-userlogout'      => 'Är Ufro fir Iech auszeloggen gouf refuséiert well et esou ausgesäit wéi wann se vun engem Futtise Browser oder Proxy-Tëschespäicher kënnt.',

# E-mail sending
'php-mail-error-unknown' => 'Onbekannte Feeler an der PHP-Mail-Fonctioun',

# JavaScript password checks
'password-strength'            => 'Geschate Stäerkt vum Passwuert: $1',
'password-strength-bad'        => 'SCHLECHT',
'password-strength-mediocre'   => 'mëttelméisseg',
'password-strength-acceptable' => 'akzeptabel',
'password-strength-good'       => 'gutt',
'password-retype'              => 'Passwuert hei nach eemol antippen',
'password-retype-mismatch'     => 'Déi Passwierder déi Dir aginn hutt sinn net identesch',

# Password reset dialog
'resetpass'                 => 'Passwuert änneren',
'resetpass_announce'        => 'Dir sidd mat engem temporären , per E-Mail geschéckte Code ageloggt.
Fir är Umeldung ofzeschléissen, musst Dir elo hei een neit Passwuert uginn:',
'resetpass_text'            => '<!-- Schreiwt ären Text heihin-->',
'resetpass_header'          => 'Passwuert vum Benotzerkont änneren',
'oldpassword'               => 'Aalt Passwuert:',
'newpassword'               => 'Neit Passwuert:',
'retypenew'                 => 'Neit Passwuert nach eemol antippen:',
'resetpass_submit'          => 'Passwuert aginn an umellen',
'resetpass_success'         => 'Äert Passwuert gouf geännert. Loggt Iech elo an ...',
'resetpass_forbidden'       => 'Passwierder kënnen net geännert ginn.',
'resetpass-no-info'         => 'Dir musst ageloggt sinn, fir direkt op dës Säit ze kommen.',
'resetpass-submit-loggedin' => 'Passwuert änneren',
'resetpass-submit-cancel'   => 'Annulléieren',
'resetpass-wrong-oldpass'   => 'Net valabelt temporäert oder aktuellt Passwuert.
Vläicht hutt Dir Äert Passwuert scho geännert oder en neit temporäert Passwuert ugefrot.',
'resetpass-temp-password'   => 'Temporäert Passwuert:',

# Edit page toolbar
'bold_sample'     => 'Fettgedréckten Text',
'bold_tip'        => 'Fettgedréckten Text',
'italic_sample'   => 'Kursiven Text',
'italic_tip'      => 'Kursiven Text',
'link_sample'     => 'Link-Text',
'link_tip'        => 'Interne Link',
'extlink_sample'  => 'http://www.example.com Link Titel',
'extlink_tip'     => 'Externe Link (Vergiesst net den http:// Prefix)',
'headline_sample' => 'Titel Text',
'headline_tip'    => 'Iwwerschrëft vum Niveau 2',
'math_sample'     => 'Formel hei asetzen',
'math_tip'        => 'Mathematesch Formel (LaTeX)',
'nowiki_sample'   => 'Net-formatéierten Text hei androen',
'nowiki_tip'      => 'Wiki-Format ignoréieren',
'image_sample'    => 'Beispill.jpg',
'image_tip'       => 'Bildlink',
'media_sample'    => 'Beispill.ogg',
'media_tip'       => 'Link op e Medieichier',
'sig_tip'         => 'Är Ënnerschrëft mat Zäitstempel',
'hr_tip'          => 'Horizontal Linn (mat Mooss gebrauchen)',

# Edit pages
'summary'                          => 'Resumé:',
'subject'                          => 'Sujet/Iwwerschrëft:',
'minoredit'                        => 'Kleng Ännerung',
'watchthis'                        => 'Dës Säit iwwerwaachen',
'savearticle'                      => 'Säit späicheren',
'preview'                          => 'Kucken ouni ofzespäicheren',
'showpreview'                      => 'Kucken ouni ofzespäicheren',
'showlivepreview'                  => 'Live-Kucken ouni ofzespäicheren',
'showdiff'                         => 'Weis Ännerungen',
'anoneditwarning'                  => 'Dir sidd net ageloggt. Dowéinst gëtt amplaz vun engem Benotzernumm Är IP Adress am Historique vun dëser Säit gespäichert.',
'anonpreviewwarning'               => "''Dir sidd net ageloggt. Wann Dir ofspäichert gëtt Är IP-Adress an der Lëscht vun de Versioune vun dëser Säit enregistréiert.''",
'missingsummary'                   => "'''Erënnerung:''' Dir hutt kee Resumé aginn. Wann Dir nachemol op \"Säit ofspäicheren\" klickt, gëtt är Ännerung ouni Resumé ofgespäichert.",
'missingcommenttext'               => 'Gitt w.e.g. eng Bemierkung an.',
'missingcommentheader'             => "'''Denkt drun:''' Dir hutt keen Titel/Sujet fir dës Bemierkung aginn.
Wann Dir nach en Kéier op \"{{int:savearticle}}\" klickt da gëtt Är Ännerung ouni Titel gespäichert.",
'summary-preview'                  => 'Resumé kucken ouni ofzespäicheren:',
'subject-preview'                  => 'Sujet/Iwwerschrëft kucken:',
'blockedtitle'                     => 'Benotzer ass gespaart',
'blockedtext'                      => "Äre Benotzernumm oder är IP Adress gouf gespaart.

D'Spär gouf vum \$1 gemaach. Als Grond gouf ''\$2'' uginn.

* Ufank vun der Spär: \$8
* Ënn vun der Spär: \$6
* Spär betrëfft: \$7

Dir kënnt den/d' \$1 kontaktéieren oder ee vun den aneren [[{{MediaWiki:Grouppage-sysop}}|Administrateure]] fir iwwer d'Spär ze schwätzen.

Dëst sollt Der besonnesch maachen, wann Der d'Gefill hutt, datt de Grond fir d'Spären net bei Iech läit.
D'Ursaach dofir ass an deem Fall, datt der eng dynamesch IP hutt, iwwert en Access-Provider, iwwer deen och aner Leit fueren.
Aus deem Grond ass et recommandéiert, sech e Benotzernumm zouzeleeën, fir all Mëssverständnes z'evitéieren.

Dir kënnt d'Funktioun \"Dësem Benotzer eng E-Mail schécken\" nëmme benotzen, wann Dir eng gëlteg E-Mail Adress bei Ären [[Special:Preferences|Astellungen]] aginn hutt.
Är aktuell-IP Adress ass \$3 an d'Nummer vun der Spär ass #\$5.
Schreift all dës Informatioune w.e.g. bei all Ufro derbäi.",
'autoblockedtext'                  => 'Är IP-Adress gouf automatesch gespaart, well se vun engem anere Benotzer gebraucht gouf, an dee vum $1 gespaart gouf.
De Grond dofir war:

:\'\'$2\'\'

* Ufank vun der Spär: $8
* Dauer vun der Spär: $6
* D\'Spär leeft of: $7

Dir kënnt de(n) $1 oder soss een [[{{MediaWiki:Grouppage-sysop}}|Administrateur]] kontaktéieren, fir iwwer déi Spär ze diskutéieren.

Bedenkt datt Dir d\'Fonctioun "Dësem Benotzer eng E-Mail schécken" benotze kënnt wann Dir eng gëlteg E-Mailadress an Ären [[Special:Preferences|Astellungen]] uginn hutt a wann dat net fir Iech gespaart gouf.

Är aktuell IP-Adress ass $3 an d\'Nummer vun Ärer Spär ass $5.
Gitt dës Donnéeë w.e.g bei allen Ufroen zu dëser Spär un.',
'blockednoreason'                  => 'Kee Grond uginn',
'blockedoriginalsource'            => "De Quelltext vun '''$1''' steet hei drënner:",
'blockededitsource'                => "Den Text vun '''Ären Ännerungen''' op '''$1''' steet hei drënner:",
'whitelistedittitle'               => "Login noutwännesch fir z'änneren",
'whitelistedittext'                => 'Dir musst Iech $1, fir Säiten änneren ze kënnen.',
'confirmedittext'                  => 'Dir musst är E-Mail-Adress confirméieren, ier Dir Ännerunge maache kënnt.
Gitt w.e.g. eng E-Mailadrss a validéiert se op äre [[Special:Preferences|Benotzerastellungen]].',
'nosuchsectiontitle'               => 'Et gëtt keen esou enn Abschnitt',
'nosuchsectiontext'                => "Dir hutt versicht een Abschnitt z'änneren den et net gëtt.
Et ka sinn datt e geännert oder geläscht gouf iwwerdm wou Dir d'Säit gekuckt hutt.",
'loginreqtitle'                    => 'Umeldung néideg',
'loginreqlink'                     => 'umellen',
'loginreqpagetext'                 => 'Dir musst Iech $1, fir aner Säite liesen zu kënnen.',
'accmailtitle'                     => 'Passwuert gouf geschéckt.',
'accmailtext'                      => "En zoufällegt Passwuert fir [[User talk:$1|$1]] gouf op $2 geschéckt.

D'Passwuert fir dësen neie Benotzerkont kann op der ''[[Special:ChangePassword|Passwuert ännere]]'' Säit beim Alogge geännert ginn.",
'newarticle'                       => '(Nei)',
'newarticletext'                   => "Dir hutt op e Link vun enger Säit geklickt, déi et nach net gëtt. Fir déi Säit unzeleeën, gitt w.e.g. Ären Text an déi Këscht hei drënner an (kuckt d'[[{{MediaWiki:Helppage}}|Hëllef Säit]] fir méi Informatiounen). Wann Dir duerch een Iertum heihi komm sidd, da klickt einfach op de Knäppchen '''Zréck''' vun ärem Browser.",
'anontalkpagetext'                 => "---- ''Dëst ass d'Diskussiounssäit fir en anonyme Benotzer deen nach kee Kont opgemaach huet oder en net benotzt. Dowéinst musse mir d'IP Adress benotzen, fir de Benotzer z'identifizéieren. Sou eng IP Adress ka vun e puer Benotzer gedeelt ginn. Wann Dir en anonyme Benotzer sidd an Dir irrelevant Kommentäre krut, [[Special:UserLogin/signup|maacht w.e.g. e Kont op]] oder [[Special:UserLogin|loggt Iech an]], fir weider Verwiesselunge mat anonyme Benotzer ze verhënneren.''",
'noarticletext'                    => 'Dës Säit huet momentan keen Text.
Dir kënnt op anere Säiten no [[Special:Search/{{PAGENAME}}|dësem Säitentitel sichen]],
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} an den entspriechende Logbicher nokucken] oder [{{fullurl:{{FULLPAGENAME}}|action=edit}} esou eng Säit uleeën]</span>.',
'noarticletext-nopermission'       => 'Elo ass keen Text op dëser Säit.
Dir kënnt op anere Säiten [[Special:Search/{{PAGENAME}}|no dësem Sàitentitel sichen]],
oder <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} an de Logbicher sichen]</span>.',
'userpage-userdoesnotexist'        => 'De Benotzerkont "$1" gëtt et net. Iwwerpréift w.e.g. op Dir dës Säit erschafe/ännere wëllt.',
'userpage-userdoesnotexist-view'   => 'De Benotzerkont "$1" ass net registréiert.',
'blocked-notice-logextract'        => 'Dëse Benotzer ass elo gespaart.
Déi lescht Entrée am Logbuch vun de Späre steet als Referenz hei drënner:',
'clearyourcache'                   => "'''Opgepasst - Nom Späichere muss der Ärem Browser seng Cache eidel maachen, fir d'Ännerungen ze gesinn.''' '''Mozilla / Firefox / Safari: ''' dréckt op ''Shift'' während Dir ''reload'' klickt oder dréckt ''Ctrl-F5'' oder ''Ctrl-R''(''Command-R'' op engem Macintosh);'''Konqueror: ''' klickt  ''Reload'' oder dréckt ''F5'' '''Opera:''' maacht de Cache eidel an ''Tools → Preferences;'' '''Internet Explorer:''' dréckt ''Ctrl'' während Dir op ''Refresh'' klickt oder dréckt ''Ctrl-F5.''",
'usercssyoucanpreview'             => "'''Tipp:''' Benotzt de \"{{int:showpreview}}\"-Knäppchen, fir Ären neien CSS virum Späicheren ze testen.",
'userjsyoucanpreview'              => "'''Tipp:''' Benotzt de ''{{int:showpreview}}''-Knäppchen, fir Ären neie JavaScript virum Späicheren ze testen.",
'usercsspreview'                   => "'''Bedenkt: Dir kuckt just är Benotzer CSS.
Si gouf nach net gespäichert!'''",
'userjspreview'                    => "'''Denkt drun datt Dir äre Javascript nëmmen test, nach ass näischt gespäichert!'''",
'sitecsspreview'                   => "'''Denkt drun datt Dir dësen CSS just kuckt.
E gouf nach net gespäichert!'''",
'sitejspreview'                    => "'''Denkt drun datt Dir dëse JavaScript-Code just kuckt.
E gouf nach net gespäichert!'''",
'userinvalidcssjstitle'            => "'''Opgepasst:''' Et gëtt keen Ausgesinn (skin) \"\$1\".
Denkt drun datt eegen .css an .js Säiten e kleng geschriwwenen Titel benotzen, z. Bsp. {{ns:user}}:Foo/vector.css am Géigesaz zu {{ns:user}}:Foo/Vector.css.",
'updated'                          => '(Geännert)',
'note'                             => "'''Notiz:'''",
'previewnote'                      => "'''Dëst ass nëmmen eng net gespäichert Versioun; d'Ännerunge sinn nach net gespäichert!'''",
'previewconflict'                  => 'Dir gesitt an dem ieweschten Textfeld wéi den Text ausgesi wäert, wann Dir späichert.',
'session_fail_preview'             => "'''Är Ännerung konnt net gespäichert gi well d'Date vun Ärer Sessioun verluergaange sinn.
Versicht et w.e.g. nach eng Kéier.
Wann de Problem dann ëmmer nach bestoe sollt, da versicht Iech [[Special:UserLogout|auszeloggen]] an dann erëm anzeloggen.'''",
'session_fail_preview_html'        => "'''Är Ännerung konnt net gespäichert gi well d'Date vun Ärer Sessioun verluergaange sinn.'''

''Well op {{SITENAME}} ''raw HTML'' aktivéiert ass, gouf d'Uweise vun der nach net gespäicherter Versioun ausgeblennt fir JavaScript-Attacken ze vermeiden.''

'''Wann dir eng berechtegt Ännerung maache wëllt, da versicht et w.e.g. nach eng Kéier.
Wann de Problem dann ëmmer nach bestoe sollt, versicht Iech [[Special:UserLogout|auszeloggen]] an dann erëm anzeloggen.'''",
'token_suffix_mismatch'            => "'''Är Ännerung gouf refuséiert, well Äre Browser Zeechen am Ännerungs-Identifiant verännert huet.'''
D'Ännerung gouf refuséiert, fir ze verhënneren datt den Text op der Säit onliesbar gëtt.
Dëst geschitt heiandsdo wann Dir en anonyme Proxy-Service um Internet benotzt.",
'editing'                          => 'Ännere vu(n) $1',
'editingsection'                   => 'Ännere vu(n) $1 (Abschnitt)',
'editingcomment'                   => 'Ännere vu(n) $1 (neien Abschnitt)',
'editconflict'                     => 'Ännerungskonflikt: $1',
'explainconflict'                  => "En anere Benotzer huet un dëser Säit geschafft, während Dir amgaange waart, se z'änneren.
Dat iewescht Textfeld weist Iech den aktuellen Text.
Är Ännerunge gesitt Dir am ënneschten Textfeld.
Dir musst Är Ännerungen an dat iewescht Textfeld androen.
'''Nëmmen''' den Text aus dem ieweschten Textfeld gëtt gehale wann Dir op \"{{int:savearticle}}\" klickt.",
'yourtext'                         => 'Ären Text',
'storedversion'                    => 'Gespäichert Versioun',
'nonunicodebrowser'                => "'''OPGEPASST:''' Äre Browser ass net Unicode kompatibel. Ännert dat w.e.g. éier Dir eng Säit ännert.",
'editingold'                       => "'''OPGEPASST: Dir ännert eng al Versioun vun dëser Säit. Wann Dir späichert, sinn all rezent Versioune vun dëser Säit verluer.'''",
'yourdiff'                         => 'Ënnerscheeder',
'copyrightwarning'                 => "W.e.g. notéiert datt all Kontributiounen op {{SITENAME}} automatesch ënnert der $2 (kuckt $1 fir méi Informatiounen) verëffentlecht sinn.
Wann Dir net wëllt datt är Texter vun anere Mataarbechter verännert, geläscht a weiderverdeelt kënne ginn, da setzt näischt heihinner.<br />
Dir verspriecht ausserdeem datt Dir dësen Text selwer verfaasst hutt, oder aus dem Domaine public oder ähnleche Ressource kopéiert hutt.
'''DROT KEE COPYRECHTLECH GESCHÜTZTE CONTENU OUNI ERLAABNES AN!'''",
'copyrightwarning2'                => "W.e.g. notéiert datt all Kontributiounen op {{SITENAME}} vun anere Benotzer verännert oder geläscht kënne ginn. Wann dir dat net wëllt, da setzt näischt heihinner.<br />
Dir verspriecht ausserdeem datt dir dësen Text selwer verfaasst hutt, oder aus dem Domaine public oder anere fräie Quelle kopéiert hutt. (cf. $1 fir méi Detailler). '''DROT KEE COPYRECHTLECH GESCHÜTZTE CONTENU AN!'''",
'longpageerror'                    => "'''FEELER: Den Text, den Dir Versicht ze späicheren, huet $1 KB. Dëst ass méi wéi den erlaabte Maximum vun $2 KB – dofir kann den Text net gespäichert ginn.'''",
'readonlywarning'                  => "'''OPGEPASST: D'Datebank gouf wéinst Maintenanceaarbechte fir Säitenännerunge gespaart, dofir kënnt Dir déi Säit den Ament net ofspäicheren. Versuergt den Text a versicht d'Ännerunge méi spéit nach emol ze maachen.'''

Den Administrateur den d'Datebank gespaart huet, huet dës Erklärung ginn: $1",
'protectedpagewarning'             => "'''OPGEPASST: Dës Säit gouf gespaart a kann nëmme vun engem Administrateur geännert ginn.''' Déi lescht Zeil aus de Logbicher fannt Dir zu Ärer Informatioun hei ënnendrënner.",
'semiprotectedpagewarning'         => "'''Bemierkung:''' Dës Säit gouf esou gespaart, datt nëmme ugemellte Benotzer s'ännere kënnen. Déi lescht Zeil aus de Logbicher fannt Dir zu Ärer Informatioun hei ënnendrënner.",
'cascadeprotectedwarning'          => "'''Passt op:''' Dës Säit gouf gespaart a kann nëmme vu Benotzer mat Administreursrechter geännert ginn. Si ass an dës {{PLURAL:$1|Säit|Säiten}} agebonnen, déi duerch Cascadespäroptioun gespaart {{PLURAL:$1|ass|sinn}}:'''",
'titleprotectedwarning'            => "'''OPGEPASST: Dës Säit gouf gespaart sou datt [[Special:ListGroupRights|spezifesch Rechter]] gebraucht gi fir se uleeën ze kënnen.''' Déi lescht Zeil aus de Logbicher fannt Dir zu Ärer Informatioun hei ënnendrënner.",
'templatesused'                    => '{{PLURAL:$1|Schabloun|Schablounen}} déi op dëser Säit am Gebrauch sinn:',
'templatesusedpreview'             => '{{PLURAL:$1|Schabloun|Schablounen}} déi an dëser nach net gespäicherter Versioun benotzt ginn:',
'templatesusedsection'             => '{{PLURAL:$1|Schabloun|Schablounen}} déi an dësem Abschnitt am Gebrauch {{PLURAL:$1|ass|sinn}}:',
'template-protected'               => '(gespaart)',
'template-semiprotected'           => '(gespaart fir net-ugemellten an nei Benotzer)',
'hiddencategories'                 => 'Dës Säit gehéiert zu {{PLURAL:$1|1 verstoppter Kategorie|$1 verstoppte Kategorien}}:',
'edittools'                        => '<!-- Dësen Text gëtt ënnert dem "Ännere"-Formulair esouwéi dem "Eropluede"-Formulair ugewisen. -->',
'nocreatetitle'                    => "D'Uleeë vun neie Säiten ass limitéiert.",
'nocreatetext'                     => "Op {{SITENAME}} gouf d'Schafe vun neie Säite limitéiert. Dir kënnt Säiten déi scho bestinn änneren oder Iech [[Special:UserLogin|umellen]].",
'nocreate-loggedin'                => 'Dir hutt keng Berechtigung fir nei Säiten unzeleeën.',
'sectioneditnotsupported-title'    => 'Ännere vum Abschnitt gëtt net ënnerstëtzt',
'sectioneditnotsupported-text'     => "D'Ännere vun Abschnitten gëtt op dëser Ännerungssäit net ënnertstetzt.",
'permissionserrors'                => 'Berechtigungs-Feeler',
'permissionserrorstext'            => 'Dir hutt net genuch Rechter fir déi Aktioun auszeféieren. {{PLURAL:$1|Grond|Grënn}}:',
'permissionserrorstext-withaction' => 'Dir sidd, aus {{PLURAL:$1|dësem Grond|dëse Grënn}}, net berechtegt $2 :',
'recreate-moveddeleted-warn'       => "'''Opgepasst: Dir sidd amgaang eng Säit unzeleeën déi schonn eng Kéier geläscht gouf.'''

Frot Iech ob et wierklech sënnvoll ass dës Säit nees nei ze schafen.
Fir Iech z'informéieren fannt Dir hei d'Logbuch vum Läsche mam Grond:",
'moveddeleted-notice'              => 'Dës Säit gouf geläscht.
Hei ass den Extrait aus dem Logbuch vum Réckelen a Läsche fir déi Säit.',
'log-fulllog'                      => 'Dat ganzt Logbuch weisen',
'edit-hook-aborted'                => "D'Ännerung gouf ouni Erklärung vun enger Schnëttstell (hook) ofgebrach.",
'edit-gone-missing'                => "D'Säit konnt net aktualiséiert ginn.
Si gouf anscheinend geläscht.",
'edit-conflict'                    => 'Ännerungskonflikt.',
'edit-no-change'                   => 'Är ännerung gouf ignoréiert, well Dir näischt um Text geännert hutt.',
'edit-already-exists'              => 'Déi nei Säit konnt net ugeluecht ginn, well et se scho gëtt.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Opgepasst: Dës Säit huet zevill Ufroe vu komplexe Parserfunktiounen.

Et däerfen net méi wéi $2 {{PLURAL:$2|Ufro|Ufroe}} sinn, aktuell {{PLURAL:$2|ass et $1 Ufro|sinn et $1 Ufroe}}.',
'expensive-parserfunction-category'       => 'Säiten, déi komplex Parserfunktiounen ze dacks opruffen',
'post-expand-template-inclusion-warning'  => "Opgepasst: D'Gréisst vun den agebonnene Schablounen ass ze grouss, e puer Schabloune kënnen net agebonne ginn.",
'post-expand-template-inclusion-category' => "Säiten, op denen d'maximal Gréist vun agebonnene Schablounen iwwerschratt ass",
'post-expand-template-argument-warning'   => "'''Warnung: Op dëser Säit ass mindestens een Argument an enger Schabloun dat eng ze grouss Expansiounsgréisst huet. Dës Argumenter goufen ewechgelooss.",
'post-expand-template-argument-category'  => 'Säiten, op dene mindestens e Parameter vun enger Schabloun vergiess ginn ass',
'parser-template-loop-warning'            => 'Endlos Schleef an der Schabloun: [[$1]] entdeckt',
'parser-template-recursion-depth-warning' => "D'Limit vun der Zuel vun de Verschachtelunge vu Schabloune gouf iwwerschratt ($1)",
'language-converter-depth-warning'        => "D'Limite vun der déift vun der Sproochëmwandlung gouf iwwerschratt ($1)",

# "Undo" feature
'undo-success' => "D'Ännerung gëtt réckgängeg gemaach. Iwwerpréift w.e.g. de Verglach ënnedrënner fir nozekuckeen ob et esou richteg ass, duerno späichert w.e.g d'Ännerungen of fir dës Aktioun ofzeschléissen.",
'undo-failure' => "D'Ännerung konnt net réckgängeg gemaach ginn, wëll de betraffenen Abschnitt an der Tëschenzäit geännert gouf.",
'undo-norev'   => "D'Ännerung kann net zréckgesat ginn, well et se net gëtt oder well se scho geläscht ass.",
'undo-summary' => 'Ännerung $1 vu(n) [[Special:Contributions/$2|$2]] ([[User talk:$2|Diskussioun]] | [[Special:Contributions/$2|{{MediaWiki:Contribslink}}]]) annulléieren.',

# Account creation failure
'cantcreateaccounttitle' => 'Benotzerkont konnt net opgemaach ginn',
'cantcreateaccount-text' => 'D\'Opmaache vu Benotzerkonten vun dëser IP Adress (\'\'\'$1\'\'\') gouf vum [[User:$3|$3]] gespaart.

De Benotzer $3 huet "$2" als Grond uginn.',

# History pages
'viewpagelogs'           => 'Logbicher fir dës Säit weisen',
'nohistory'              => 'Et gëtt keng al Versioune vun dëser Säit.',
'currentrev'             => 'Aktuell Versioun',
'currentrev-asof'        => 'Aktuell Versioun vum $1',
'revisionasof'           => 'Versioun vum $1',
'revision-info'          => 'Versioun vum $1 vum $2.',
'previousrevision'       => '← Méi al Versioun',
'nextrevision'           => 'Méi rezent Ännerung→',
'currentrevisionlink'    => 'Aktuell Versioun',
'cur'                    => 'aktuell',
'next'                   => 'nächst',
'last'                   => 'lescht',
'page_first'             => 'éischt',
'page_last'              => 'lescht',
'histlegend'             => "Fir d'Ännerungen unzeweisen: Klickt déi zwou Versiounen un, déi solle verglach ginn.<br />
*(aktuell) = Ënnerscheed mat der aktueller Versioun,
*(lescht) = Ënnerscheed mat der aler Versioun,
*k = Kleng Ännerung.",
'history-fieldset-title' => 'An de Versioune sichen',
'history-show-deleted'   => 'nëmmen déi geläschten',
'histfirst'              => 'Eelsten',
'histlast'               => 'Neisten',
'historysize'            => '({{PLURAL:$1|1 Byte|$1 Byten}})',
'historyempty'           => '(eidel)',

# Revision feed
'history-feed-title'          => 'Historique vun de Versiounen',
'history-feed-description'    => 'Versiounshistorique fir dës Säit op {{SITENAME}}',
'history-feed-item-nocomment' => '$1 ëm $2',
'history-feed-empty'          => 'Déi ugefrote Säit gëtt et net.
Vläicht gouf se geläscht oder geréckelt.
[[Special:Search|Sich op]] {{SITENAME}} no passenden neie Säiten.',

# Revision deletion
'rev-deleted-comment'         => '(Bemierkung geläscht)',
'rev-deleted-user'            => '(Benotzernumm ewechgeholl)',
'rev-deleted-event'           => '(Aktioun aus dem Logbuch erausgeholl)',
'rev-deleted-user-contribs'   => '[Benotzernumm oder IP-Adress ewechgeholl - Ännerung an der Lescht vun de Kontributioune verstoppt]',
'rev-deleted-text-permission' => "Dës Versioun vun der Säit gouf '''geläscht'''.
Dir fannt eventuell méi Informatiounen an der [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Läsch-Logbuch].",
'rev-deleted-text-unhide'     => "Dës Versioun vun der Säit gouf '''geläscht'''.
Detailer kënnen am [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Lösch-Logbuch] fonnt ginn.
Als Administrateur kënnt Dir nach ëmmer [$1 dës Versioun kucken] wann Dir weiderfuere wëllt.",
'rev-suppressed-text-unhide'  => "Dës Versioun vun der Säit gouf '''geläscht'''.
Detailler kënnen am  [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Läschlogbuch] sinn.
Als Adminstrateur kënnt Dir [$1 dës Versioun gesi] wann Dir weiderfuere wëllt.",
'rev-deleted-text-view'       => "Dës Versioun vun der Säit gouf '''geläscht'''.
Als Administrateur kënnt Dir se gesinn; Dir fannt eventuell méi Detailer am  [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Läsch-Logbuch].",
'rev-suppressed-text-view'    => "Dës Versioun vun der Säit gouf '''geläscht'''.
Als Administrateur kënnt dir se gesinn; méi Detailler fannt Dir eventuell am [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Läsch-Logbuch].",
'rev-deleted-no-diff'         => "Dir kënnt dësen Ënnerscheed net gesinn, well eng vun de Versiounen '''geläscht gouf'''.
Detailer stinn eventuell am [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Läsch-Logbuch].",
'rev-suppressed-no-diff'      => "Dir kënnt dësen Diff (Ënnerscheed) net gesinn well eng vun de Versioune '''geläscht''' gouf.",
'rev-deleted-unhide-diff'     => "Eng vun de Versioune vun dësem Ënnerscheed gouf '''geläscht'''.
Detailer stinn eventuell am [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Läsch-Logbuch].
Als Administrateur kënnt Dir [$1 mat dësem Link de Versiounsënnerscheed kucke] wann Dir weiderfuere wëllt.",
'rev-suppressed-unhide-diff'  => "Eng vun de Versioune vun dësem Ënnerscheed gouf '''geläscht'''.
Detailer stinn eventuell am [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Läsch-Logbuch].
Als Administrateur kënnt Dir [$1 mat dësem Link de Versiounsënnerscheed kucke] wann Dir weiderfuere wëllt.",
'rev-deleted-diff-view'       => "Eng Versioun vun dësem Versiounsënnerscheed gouf '''geläscht'''.
Als Administrateur kënnt Dir dësen Ënnerscheed gesinn; et ka sinn datt Dir méi Detailer am [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} Logbuch vum Läsche] fannt.",
'rev-suppressed-diff-view'    => "Eng vun de Versioune vun dësem Ënnerscheeed gouf '''ënnerdréckt'''.
Als Administrateur kënnt Dir dësen Ënnerscheed gesinn; et ka sinn datt Dir Detailer am [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Logbuch vun den Ënnerdréckunge] fannt.",
'rev-delundel'                => 'weisen/verstoppen',
'rev-showdeleted'             => 'Weisen',
'revisiondelete'              => 'Versioune läschen/restauréieren',
'revdelete-nooldid-title'     => 'Ongülteg Zilversioun',
'revdelete-nooldid-text'      => 'Dir hutt entweder keng Versioun uginn fir dës Funktioun ze benotzen, déi Versioun déi Dir uginn hutt gëtt et net, oder dir versicht déi aktuell Versioun ze verstoppen.',
'revdelete-nologtype-title'   => 'Keen Typ vu Logbuch uginn',
'revdelete-nologtype-text'    => 'Dir hutt keen Typ vu Logbuch ugi fir dës Aktioun ze maachen.',
'revdelete-nologid-title'     => 'Net valabele Wäert am Logbuch',
'revdelete-nologid-text'      => 'Dir hutt kee Logtyp erausgesicht oder de gewielte Logtyp gëtt et net.',
'revdelete-no-file'           => 'De Fichier deen ugi war gëtt et net.',
'revdelete-show-file-confirm' => 'Sidd Dir sécher datt Dir déi geläschte Versioun vum Fichier "<nowiki>$1</nowiki>" vum $2 ëm $3 gesi wëllt?',
'revdelete-show-file-submit'  => 'Jo',
'revdelete-selected'          => "'''{{PLURAL:$2|Gewielte Versioun|Gewielte Versioune}} vu(n) '''$1''' :'''",
'logdelete-selected'          => "'''Ausgewielten {{PLURAL:$1|Evenement|Evenementer}} aus dem Logbuch:'''",
'revdelete-text'              => "'''Geläschte Versiounen oder aner geäschte Bestanddeeler sinn net méi ëffentlech zougänglech, si stinn awer weiderhinn an der Versiounsgeschicht vun der Säit.'''
Aner {{SITENAME}}-Administrateure kënnen de geläschten Inhalt oder aner geläschte Bestanddeeler weiderhi gesinn a restauréieren, et sief, et gouf festgeluecht, datt déi Limitatioune vum Accès och fir Administrateure gëllen.",
'revdelete-confirm'           => "Confirméiert w.e.g. datt Dir dat maache wëllt, datt Dir d'Konsequenze verstitt an datt Dir dëst an Aklang mat de [[{{MediaWiki:Policy-url}}|Richtlinne]] maacht.",
'revdelete-suppress-text'     => "Ënnerdréckung sollt '''nëmmen''' an dëse Fäll benotzt ginn:
* Net ubruechte perséinlechen Informatiounen
*: ''Adressen, Telefonsnummeren, Sozialversécherungsnummeren asw.''",
'revdelete-legend'            => "Limitatioune fir d'Sichtbarkeet festleeën",
'revdelete-hide-text'         => 'Text vun der Versioun verstoppen',
'revdelete-hide-image'        => 'Bildinhalt verstoppen',
'revdelete-hide-name'         => 'Logbuch-Aktioun verstoppen',
'revdelete-hide-comment'      => 'Bemierkung verstoppen',
'revdelete-hide-user'         => 'Dem Auteur säi Benotzernumm/IP verstoppen',
'revdelete-hide-restricted'   => 'Donnéeën och fir Administrateuren suppriméieren geneesou wéi fir déi Aner',
'revdelete-radio-same'        => '(net änneren)',
'revdelete-radio-set'         => 'Jo',
'revdelete-radio-unset'       => 'Neen',
'revdelete-suppress'          => 'Grond vum Läschen och fir Administrateure verstoppt',
'revdelete-unsuppress'        => 'Limitatiounen fir restauréiert Versiounen ophiewen',
'revdelete-log'               => 'Grond:',
'revdelete-submit'            => 'Op déi gewielte {{PLURAL:$1|Versioun|Versiounen}} uwenden',
'revdelete-logentry'          => 'Sichtbarkeet vun der Versioun gouf geännert fir [[$1]]',
'logdelete-logentry'          => "huet d'Sichtbarkeet vun [[$1]] geännert",
'revdelete-success'           => "'''Sichtbarkeet vun de Versioune gouf aktualiséiert.''''",
'revdelete-failure'           => "'''Sichtbarkeet vun der Versioun konnt net aktualiséiert ginn:'''
$1",
'logdelete-success'           => "'''Sichbarkeet vum Logbuch geännert.'''",
'logdelete-failure'           => "'''D'Sichtbarkeet vum Logbuch konnt net agestllt ginn:'''
$1",
'revdel-restore'              => 'Sichtbarkeet änneren',
'revdel-restore-deleted'      => 'geläschte Versiounen',
'revdel-restore-visible'      => 'sichtbar Versiounen',
'pagehist'                    => 'Versioune vun dëser Säit',
'deletedhist'                 => 'Geläschte Versiounen',
'revdelete-content'           => 'Inhalt',
'revdelete-summary'           => 'Resumé änneren',
'revdelete-uname'             => 'Benotzernumm',
'revdelete-restricted'        => 'Limitatioune fir Administrateuren ageschalt',
'revdelete-unrestricted'      => 'Limitatioune fir Administrateuren opgehuewen',
'revdelete-hid'               => '$1 verstoppen',
'revdelete-unhid'             => '$1 weisen',
'revdelete-log-message'       => '$1 fir $2 {{PLURAL:$2|Versioun|Versiounen}}',
'logdelete-log-message'       => '$1 fir $2 {{PLURAL:$2|Evenement|Evenementer}}',
'revdelete-hide-current'      => 'Feeler beim Verstoppe vum Objet vum $2 ëm $1: et ass déi aktuell Versioun.
Si kann net verstoppt ginn.',
'revdelete-show-no-access'    => 'Feeler beim Weise vum Objet vum $1 ëm $2 Auer: dësen Objet gouf als "limitéiert2 markéiert.
Dir hutt keen Zougang zu dësem Objet.',
'revdelete-modify-no-access'  => 'Feeler beim Ännere vum Objet vum $1 ëm $2 Auer: dësen Objet gouf als "limitéiert" markéiert.
Dir hutt keen Zougang dozou.',
'revdelete-modify-missing'    => 'Feeler beim Ännere vun der ID $1: si feelt an der Datebank!',
'revdelete-no-change'         => "'''Opgepasst:''' D'Element vum $2 ëm $1 Auer huet schonn déi ugefrote Sichtbarkeetsastellung.",
'revdelete-concurrent-change' => "Feeler beim Ännere vum Element vum $1 ëm $2 Auer: säit Statut schéngt geännert ginn ze si während Dir vericht hutt et z'änneren.
Kuckt w.e.g. an de Logbicher no.",
'revdelete-only-restricted'   => 'Feler beim verstoppe vum Objet vum $2, $1: Dir kënnt keng Objete virun den Administrateure verstoppen ouni och eng vun den aneren Optiounen vum weisen erauszesichen.',
'revdelete-reason-dropdown'   => "* Generell Läschgrënn
**Verletzung vun den Droits d'Auteur
**Net ubruechte perséinlech Informatioun",
'revdelete-otherreason'       => 'Aneren/zousätzleche Grond:',
'revdelete-reasonotherlist'   => 'Anere Grond:',
'revdelete-edit-reasonlist'   => 'Läschgrënn änneren',
'revdelete-offender'          => 'Auteur vun der Versioun:',

# Suppression log
'suppressionlog'     => 'Lëscht vun de verstoppten a geläschte Säiten',
'suppressionlogtext' => "Ënnendrënner ass eng Lëscht vun de geläschte Säiten a Spären déi fir d'Administrateuren net sichtbar sinn.
Kuckt [[Special:IPBlockList|Lëscht vun de gespaarten IPen]] fir déi aktuell Spären.",

# Revision move
'moverevlogentry'              => '{{PLURAL:$3|eng Versioun|$3 Versioune}} vun $1 op $2 geréckelt',
'revisionmove'                 => 'Versioune vun "$1" réckelen',
'revmove-explain'              => "Dës Versioune gi vu(n) $1 op déi spezifizéiert Zilsäit geréckelt. Wann et d'Zilsäit net gëtt, da gëtt se ugeluecht. Soss ginn d'Versiounen an de Versiounshistorique integréiert.",
'revmove-legend'               => 'Zilsäit a Resumé festleeën',
'revmove-submit'               => 'Versiounen op déi erausgesichte Säit réckelen',
'revisionmoveselectedversions' => 'Erausgesichte Versioune réckelen',
'revmove-reasonfield'          => 'Grond:',
'revmove-titlefield'           => 'Zilsäit:',
'revmove-badparam-title'       => 'Falsch Parameter',
'revmove-badparam'             => 'An Ärer Ufro sinn net erlaabten oder net genuch Parameter dran.
Gitt zréck op déi säit virdrun a probéiert nach emol.',
'revmove-norevisions-title'    => 'Net-valabel Zilversioun',
'revmove-norevisions'          => 'Dir hutt keng Zilversioun uginn, fir dës Fonctionalitéit ze benotzen oder déi Versioun déi Dir uginn hutt gëtt et net.',
'revmove-nullmove-title'       => 'Schlechten Titel',
'revmove-nullmove'             => 'D\'Quell- an d\'Zilsäit sinn déi selwëscht. Klickt w.e.g. op „zréck“ a gitt en anere Säitennumm wéi "$1" un.',
'revmove-success-existing'     => "{{PLURAL:$1|Eng Versioun vu(n) [[$2]] gouf|$1 Versioune vu(n) [[$2]] goufen}} op d'Säit [[$3]] geréckelt.",
'revmove-success-created'      => '{{PLURAL:$1|Eng Versioun vu(n) [[$2]] gouf|$1 Versioune vu(n) [[$2]] goufen}} op déi nei Säit [[$3]] geréckelt.',

# History merging
'mergehistory'                     => 'Historiquë fusionéieren',
'mergehistory-header'              => "Mat dëser Spezialsäit kënnt Dir d'Versiounsgeschicht vun enger Ursprungssäit mat der Versiounsgeschicht vun enger Zilsäit zesummeleeën.
Passt op, datt d'Versiounsgeschicht der Säit historesch korrekt ass.

'''Als Minimum muss déi aktuell Versioun vun der Ursprungssäit bestoe bleiwen.'''",
'mergehistory-box'                 => 'Historiquë vun zwou Säite fusionéieren',
'mergehistory-from'                => 'Originalsäit:',
'mergehistory-into'                => 'Zilsäit:',
'mergehistory-list'                => 'Versiounen, déi zesummegeluecht kënne ginn',
'mergehistory-merge'               => "Dës Versioune vun [[:$1]] kënnen matt [[:$2]] zesummegeluecht ginn.
Benotzt d'Radio-Knäppecher fir nëmmen déi Versiunen ze fusonnéieren déi zu engem spezifischen Zäitpunkt oder virdrun ugeluecht goufen.
Denkt w.e.g drunn datt d'Navigatiounslinken d'Wiel vun de Versiounen nees zrécksetzen.",
'mergehistory-go'                  => 'Weis déi Versiounen, déi zesummegeluecht kënne ginn',
'mergehistory-submit'              => 'Versioune verschmelzen',
'mergehistory-empty'               => 'Et kënne keng Versioune zesummegeluecht ginn.',
'mergehistory-success'             => '{{PLURAL:$3|1 Versioun gouf|$3 Versioune goufe}} vun [[:$1]] op [[:$2]] zesummegeluecht.',
'mergehistory-fail'                => "Versiounszesummeleeung war net méiglech, kuckt w.e.g. d'Säiten an d'Zäit-Parameter no.",
'mergehistory-no-source'           => 'Originalsäit "$1" gëtt et net.',
'mergehistory-no-destination'      => 'Zilsäit "$1" gëtt et net.',
'mergehistory-invalid-source'      => "D'Originalsäit muss ee gültege Säitennumm hunn.",
'mergehistory-invalid-destination' => 'Zilsäit muss e gültege Säitennumm sinn.',
'mergehistory-autocomment'         => '[[:$1]] zesummegeluecht an [[:$2]]',
'mergehistory-comment'             => '[[:$1]] zesummegeluecht an [[:$2]]: $3',
'mergehistory-same-destination'    => 'Ausgangs- an Zilsäit däerfen net déi selwescht sinn',
'mergehistory-reason'              => 'Grond:',

# Merge log
'mergelog'           => 'Fusiouns-Logbuch',
'pagemerge-logentry' => '[[$1]] zesummegeluecht an [[$2]] (Versioune bis $3)',
'revertmerge'        => 'Zesummeféieren ophiewen',
'mergelogpagetext'   => 'Lëscht vun de rezenten Zesummeféierunge vu Versiounsgeschichten.',

# Diffs
'history-title'            => 'Versiounshistorique vun „$1“',
'difference'               => '(Ennerscheed tëscht Versiounen)',
'difference-multipage'     => '(Ënnerscheed tëschent Säiten)',
'lineno'                   => 'Linn $1:',
'compareselectedversions'  => 'Ausgewielte Versioune vergläichen',
'showhideselectedversions' => 'Erausgesichte Versioune weisen/verstoppen',
'editundo'                 => 'zréck',
'diff-multi'               => '({{PLURAL:$1|Eng Tëscheversioun|$1 Tëscheversioune}} vun {{PLURAL:$2|engem|$2}} Benotzer {{PLURAL:$1|¨gëtt|ginn}} net gewisen)',
'diff-multi-manyusers'     => '({{PLURAL:$1|Eng Tëscheversioun|$1 Tëscheversioune}} vu méi wéi $2 {{PLURAL:$2|Benotzer|Benotzer}} ginn net gewisen)',

# Search results
'searchresults'                    => 'Resultat vun der Sich',
'searchresults-title'              => 'Resultater vun der Sich no "$1"',
'searchresulttext'                 => "Fir méi Informatiounen iwwer d'Sichfunktiounen op {{SITENAME}}, kuckt w.e.g op [[{{MediaWiki:Helppage}}|{{int:help}}]].",
'searchsubtitle'                   => 'Dir hutt no "[[:$1]]" gesicht ([[Special:Prefixindex/$1|all Säiten déi mat "$1" ufänken]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|all Säiten déi op "$1" linken]])',
'searchsubtitleinvalid'            => 'Dir hutt no "$1" gesicht.',
'toomanymatches'                   => 'Zevill Resultater goufe fonnt, versicht w.e.g. eng aner Ufro',
'titlematches'                     => 'Iwwereneestëmmungen am Säitentitel',
'notitlematches'                   => 'Keng Iwwereneestëmmunge mat Säitentitelen',
'textmatches'                      => 'Iwwereneestëmmungen am Säitentext',
'notextmatches'                    => 'Keng Iwwereneestëmmungen',
'prevn'                            => 'vireg {{PLURAL:$1|$1}}',
'nextn'                            => 'nächst {{PLURAL:$1|$1}}',
'prevn-title'                      => 'Vireg $1 {{PLURAL:$1|Resultat|Resultater}}',
'nextn-title'                      => 'Nächst $1 {{PLURAL:$1|Resultat|Resultater}}',
'shown-title'                      => '$1 {{PLURAL:$1|Resultat|Resultater}} pro Säit weisen',
'viewprevnext'                     => 'Weis ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Optioune vun der Sich',
'searchmenu-exists'                => "* Säit '''[[$1]]'''",
'searchmenu-new'                   => "'''Opmaache vun der Säit ''[[:$1|$1]]'' op dëser Wiki!'''",
'searchmenu-new-nocreate'          => '"$1" ass en net-valabele Säitennumm oder ka vun Iech net ugeluecht ginn.',
'searchhelp-url'                   => 'Help:Hëllef',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|All Säite weisen, déi mat dem Begrëff ufänken nodeem Dir sicht]]',
'searchprofile-articles'           => 'Säite mat Inhalt',
'searchprofile-project'            => 'Hëllef a Projetssäiten',
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Alles',
'searchprofile-advanced'           => 'Erweidert',
'searchprofile-articles-tooltip'   => 'Sichen a(n) $1',
'searchprofile-project-tooltip'    => 'Sichen a(n) $1',
'searchprofile-images-tooltip'     => 'No Fichiere sichen',
'searchprofile-everything-tooltip' => 'Op alle Säiten nom Inhalt sichen  (inclusiv Diskussiounssäiten)',
'searchprofile-advanced-tooltip'   => 'Sich an den Nummraim déi an de perséinlichen Astellungen festgeluecht sinn',
'search-result-size'               => '$1 ({{PLURAL:$2|1 Wuert|$2 Wierder}})',
'search-result-category-size'      => '{{PLURAL:$1|1 Säit|$1 Säiten}} ({{PLURAL:$2|1 Ënnerkategorie|$2 Ënnerkategorien}}, {{PLURAL:$3|1 Fichier|$3 Fichieren}})',
'search-result-score'              => 'Relevanz: $1 %',
'search-redirect'                  => '(Viruleedung $1)',
'search-section'                   => '(Abschnitt $1)',
'search-suggest'                   => 'Mengt Dir: $1',
'search-interwiki-caption'         => 'Schwësterprojeten',
'search-interwiki-default'         => '$1 Resultater:',
'search-interwiki-more'            => '(méi)',
'search-mwsuggest-enabled'         => 'mat Virschléi',
'search-mwsuggest-disabled'        => 'keng Virschléi',
'search-relatedarticle'            => 'A Verbindung',
'mwsuggest-disable'                => 'Ajax-Virschléi ausschalten',
'searcheverything-enable'          => 'An allen Nummraim sichen',
'searchrelated'                    => 'a Verbindng',
'searchall'                        => 'all',
'showingresults'                   => "Hei gesitt der  {{PLURAL:$1| '''1''' Resultat|'''$1''' Resultater}}, ugefaang mat #'''$2'''.",
'showingresultsnum'                => "Hei gesitt der  {{PLURAL:$3|'''1''' Resultat|'''$3''' Resultater}}, ugefaange mat #'''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Resultat '''$1''' vu(n) '''$3'''|Resultater '''$1 - $2''' vu(n) '''$3'''}} fir '''$4'''",
'nonefound'                        => "'''Opgepasst''': Nëmmen e puer Nummraim gi ''par default'' duerchsicht. Versicht an ärer Ufro ''all:'' anzestellen fir de gesamten Inhalt (inklusiv Diskussiounssäiten, Schablonen, ...), oder benotzt déi gwënschten Nummraim als Virastellung.",
'search-nonefound'                 => 'Fir är Ufro gouf näischt fonnt.',
'powersearch'                      => 'Erweidert Sich',
'powersearch-legend'               => 'Erweidert Sich',
'powersearch-ns'                   => 'Sich an den Nummraim:',
'powersearch-redir'                => 'Viruleedunge weisen',
'powersearch-field'                => 'Sich no:',
'powersearch-togglelabel'          => 'Markéieren:',
'powersearch-toggleall'            => 'All',
'powersearch-togglenone'           => 'Keen',
'search-external'                  => 'Extern Sich',
'searchdisabled'                   => "D'Sichfunktioun op {{SITENAME}} ass ausgeschalt. Dir kënnt iwwerdeems mat Hëllef vu Google sichen. Bedenkt awer, datt deenen hire  Sichindex fir {{SITENAME}} eventuell net dem aktuellste Stand entsprecht.",

# Quickbar
'qbsettings'               => 'Geschirläischt',
'qbsettings-none'          => 'Keen',
'qbsettings-fixedleft'     => 'Lénks, fest',
'qbsettings-fixedright'    => 'Riets, fest',
'qbsettings-floatingleft'  => 'schwiewt lenks',
'qbsettings-floatingright' => 'Schwiewt riets',

# Preferences page
'preferences'                   => 'Astellungen',
'mypreferences'                 => 'Meng Astellungen',
'prefs-edits'                   => 'Zuel vun den Ännerungen:',
'prefsnologin'                  => 'Net ageloggt',
'prefsnologintext'              => 'Dir musst <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}}ageloggt]</span> sinn, fir Är Astellungen änneren ze kënnen.',
'changepassword'                => 'Passwuert änneren',
'prefs-skin'                    => 'Skin',
'skin-preview'                  => 'Kucken',
'prefs-math'                    => 'Math/TeX',
'datedefault'                   => 'Egal (Standard)',
'prefs-datetime'                => 'Datum an Auerzäit',
'prefs-personal'                => 'Benotzerprofil',
'prefs-rc'                      => 'Rezent Ännerungen',
'prefs-watchlist'               => 'Iwwerwaachungslëscht',
'prefs-watchlist-days'          => 'Zuel vun den Deeg, déi an der Iwwerwaachungslëscht ugewise solle ginn:',
'prefs-watchlist-days-max'      => '(Maximal 7 Deeg)',
'prefs-watchlist-edits'         => 'Maximal Zuel vun den Ännerungen déi an der erweiderter Iwwerwaachungslëscht ugewise solle ginn:',
'prefs-watchlist-edits-max'     => '(Maximal Zuel: 1000)',
'prefs-watchlist-token'         => 'Iwwerwaachungslëscht-Token:',
'prefs-misc'                    => 'Verschiddenes',
'prefs-resetpass'               => 'Passwuert änneren',
'prefs-email'                   => 'E-Mail-Optiounen',
'prefs-rendering'               => 'Ausgesinn',
'saveprefs'                     => 'Späicheren',
'resetprefs'                    => 'Net gespäichert Ännerungen zrécksetzen',
'restoreprefs'                  => 'All Standardastellungen zrécksetzen',
'prefs-editing'                 => 'Änneren',
'prefs-edit-boxsize'            => 'Gréisst vun der Ännerungsfënster:',
'rows'                          => 'Zeilen',
'columns'                       => 'Kolonnen',
'searchresultshead'             => 'Sich',
'resultsperpage'                => 'Zuel vun de Resultater pro Säit:',
'contextlines'                  => 'Zuel vun de Linnen:',
'contextchars'                  => 'Kontextcharactère pro Linn:',
'stub-threshold'                => 'Maximum (a Byte) bei deem e Link nach ëmmer am <a href="#" class="stub">Skizze-Format</a> gewise gëtt:',
'stub-threshold-disabled'       => 'Desaktivéiert',
'recentchangesdays'             => 'Deeg déi an de Rezenten Ännerungen ugewise ginn:',
'recentchangesdays-max'         => '(Maximal $1 {{PLURAL:$1|Dag|Deeg}})',
'recentchangescount'            => 'Zuel vun den Ännerungen déi als Standard gewise ginn:',
'prefs-help-recentchangescount' => 'Inklusiv Rezent Ännerungen, Versiounshistoriquen a Logbicher.',
'prefs-help-watchlist-token'    => 'Wann dir dëst Feld mat engem Geheimcode ausfëllt gëtt en RSS-Feed fir Är Iwwerwaachungslëscht generéiert.
Jiddereen deen de Geheimcode aus dësem Feld kennt kann Är Iwwerwaachungslëscht liesen, wielt dofir e séchere Wäert.
Hei ass een zoufälleg generéierte Wäert deen Dir benotze kënnt: $1',
'savedprefs'                    => 'Är Astellunge goufe gespäichert.',
'timezonelegend'                => 'Zäitzon:',
'localtime'                     => 'Lokalzäit:',
'timezoneuseserverdefault'      => 'De Standardwert vum Server benotzen',
'timezoneuseoffset'             => 'Aner (Differenz uginn)',
'timezoneoffset'                => 'Zäit-Differenz¹:',
'servertime'                    => 'Serverzäit:',
'guesstimezone'                 => 'Vum Browser iwwerhuelen',
'timezoneregion-africa'         => 'Afrika',
'timezoneregion-america'        => 'Amerika',
'timezoneregion-antarctica'     => 'Antarktis',
'timezoneregion-arctic'         => 'Arktis',
'timezoneregion-asia'           => 'Asien',
'timezoneregion-atlantic'       => 'Atlanteschen Ozean',
'timezoneregion-australia'      => 'Australien',
'timezoneregion-europe'         => 'Europa',
'timezoneregion-indian'         => 'Indeschen Ozean',
'timezoneregion-pacific'        => 'Pazifeschen Ozean',
'allowemail'                    => 'E-Maile vun anere Benotzer kréien.',
'prefs-searchoptions'           => 'Sichoptiounen',
'prefs-namespaces'              => 'Nummraim',
'defaultns'                     => 'Soss an dësen Nummraim sichen:',
'default'                       => 'Standard',
'prefs-files'                   => 'Fichieren',
'prefs-custom-css'              => 'Benotzerdefinéierten CSS',
'prefs-custom-js'               => 'Benotzerdefinéierte JS',
'prefs-common-css-js'           => 'Gemeinsam CSS/JS fir all Ausgesinn (skins):',
'prefs-reset-intro'             => "Dir kënnt dës Säit benotze fir Är Astellungen zréck op d'Standard-Astllungen ze setzen.
Dëst kann net réckgängeg gemaach ginn.",
'prefs-emailconfirm-label'      => 'E-Mail Confirmatioun:',
'prefs-textboxsize'             => 'Gréisst vun der Ännerungsfënster',
'youremail'                     => 'E-Mailadress:',
'username'                      => 'Benotzernumm:',
'uid'                           => 'Benotzer ID:',
'prefs-memberingroups'          => 'Member vun {{PLURAL:$1|der Benotzergrupp|de Benotzergruppen}}:',
'prefs-registration'            => 'Zäitpunkt vum Opmaache vum Benotzerkont:',
'yourrealname'                  => 'Richtegen Numm:',
'yourlanguage'                  => 'Sprooch vun der Benotzeruewerfläch:',
'yourvariant'                   => 'Sproochvariant:',
'yournick'                      => 'Ënnerschrëft:',
'prefs-help-signature'          => 'Bemierkungen op Diskussiounssäite solle mat "<nowiki>~~~~</nowiki>" ënnerscheiwwe ginn. Dëst gëtt dann an Är Ënnerschrëft an en Zäitstempel ëmgewandelt.',
'badsig'                        => "D'Syntax vun ärer Ënnerschëft ass net korrekt; iwwerpréift w.e.g. ären HTML Code.",
'badsiglength'                  => 'Är Ënnerschrëft ass ze laang.
Si muss manner wéi $1 {{PLURAL:$1|Zeechen|Zeechen}} hunn.',
'yourgender'                    => 'Geschlecht:',
'gender-unknown'                => 'Net uginn',
'gender-male'                   => 'Männlech',
'gender-female'                 => 'Weiblech',
'prefs-help-gender'             => "Fakultativ: gëtt benotzt fir eng ''Gender-korrekt'' Uried duerch d'Software. Dës Informatioun ass ëffentlech.",
'email'                         => 'E-Mail',
'prefs-help-realname'           => 'Äre richtegen Numm ass fakultativ. Wann Dir en ugitt, gëtt e benotzt fir Iech Är Kontributiounen zouzeuerdnen.',
'prefs-help-email'              => "D'E-Mailadress ass fakultativ, awer si gëtt gebraucht fir Iech Äert Passwuert ze mailen, wann Dir et géift vergiessen.",
'prefs-help-email-others'       => 'Dir kënnt Iech och dofir decidéieren datt Anerer Iech iwwer Är Diskussiounssäit kontaktéieren ouni datt Dir dobäi Är Identitéit verrode musst.',
'prefs-help-email-required'     => 'Eng gëlteg E-Mailadress gëtt heifir gebraucht.',
'prefs-info'                    => 'Grondinformatioun',
'prefs-i18n'                    => 'Internationalisatioun',
'prefs-signature'               => 'Ënnerschrëft',
'prefs-dateformat'              => 'Format vum Datum',
'prefs-timeoffset'              => 'Zäitënnerscheed',
'prefs-advancedediting'         => 'Méi Optiounen',
'prefs-advancedrc'              => 'Méi Optiounen',
'prefs-advancedrendering'       => 'Méi Optiounen',
'prefs-advancedsearchoptions'   => 'Méi Optiounen',
'prefs-advancedwatchlist'       => 'Méi Optiounen',
'prefs-displayrc'               => 'Optioune vun deem wat gewise gëtt',
'prefs-displaysearchoptions'    => 'Optioune vum Affichage',
'prefs-displaywatchlist'        => 'Optioune vun deem wat gewise gëtt',
'prefs-diffs'                   => 'Ënnerscheeder',

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => "D'E-Mailadress schéngt valabel ze sinn",
'email-address-validity-invalid' => 'Gitt eng valabel e-Mailadress an',

# User rights
'userrights'                   => 'Benotzerrechterverwaltung',
'userrights-lookup-user'       => 'Benotzergrupp verwalten',
'userrights-user-editname'     => 'Benotzernumm uginn:',
'editusergroup'                => 'Benotzergruppen änneren',
'editinguser'                  => "Ännere vun de Rechter vum Benotzer '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'Benotzergruppen änneren',
'saveusergroups'               => 'Benotzergruppe späicheren',
'userrights-groupsmember'      => 'Member vun:',
'userrights-groupsmember-auto' => 'Implizit Member vun:',
'userrights-groups-help'       => "Dir kënnt d'Gruppen zu deenen dëse Benotzer gehéiert änneren.
* Een ugekräizt Haische bedeit, datt de Benotzer Member vun dëser Grupp ass.
* Een net ugekräizt Haische bedeit, datt de Benotzer net Member vun dëser Grupp ass.
* E Stäerchen (*) bedeit datt Dir d'Grupp net méi ewechhuele kënnt wann e bis eemol derbäigesat ass oder gouf.",
'userrights-reason'            => 'Grond:',
'userrights-no-interwiki'      => "Dir hutt net déi néideg Rechter, fir d'Rechter vu Benoutzer op anere Wikien z'änneren.",
'userrights-nodatabase'        => "D'Datebank $1 gëtt et net oder se ass net lokal.",
'userrights-nologin'           => 'Dir musst mat engem Administrateurs-Benotzerkont [[Special:UserLogin|ageloggt sinn]], fir Benotzerrechter änneren ze kënnen.',
'userrights-notallowed'        => "Dir hutt net déi néideg Rechter fir d'Rechter vun anere Benotzer z'änneren.",
'userrights-changeable-col'    => 'Gruppen déi Dir ännere kënnt',
'userrights-unchangeable-col'  => 'Gruppen déi Dir net ännere kënnt',

# Groups
'group'               => 'Grupp:',
'group-user'          => 'Benotzer',
'group-autoconfirmed' => 'Registréiert Benotzer',
'group-bot'           => 'Botten',
'group-sysop'         => 'Administrateuren',
'group-bureaucrat'    => 'Bürokraten',
'group-suppress'      => 'Iwwersicht',
'group-all'           => '(all)',

'group-user-member'          => 'Benotzer',
'group-autoconfirmed-member' => 'Registréierte Benotzer',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Administrateur',
'group-bureaucrat-member'    => 'Bürokrat',
'group-suppress-member'      => 'Iwwersiicht',

'grouppage-user'          => '{{ns:project}}:Benotzer',
'grouppage-autoconfirmed' => '{{ns:project}}:Registréiert Benotzer',
'grouppage-bot'           => '{{ns:project}}:Botten',
'grouppage-sysop'         => '{{ns:project}}:Administrateuren',
'grouppage-bureaucrat'    => '{{ns:project}}:Bürokraten',
'grouppage-suppress'      => '{{ns:project}}:Iwwersiicht',

# Rights
'right-read'                  => 'Säite liesen',
'right-edit'                  => 'Säiten änneren',
'right-createpage'            => 'Säiten uleeën (déi keng Diskussiounssäite sinn)',
'right-createtalk'            => 'Diskussiounssäiten uleeën',
'right-createaccount'         => 'Nei Benotzerkonten uleeën',
'right-minoredit'             => 'Ännerungen als kleng markéieren',
'right-move'                  => 'Säite réckelen',
'right-move-subpages'         => 'Säiten zesumme mat hiren Ënnersäite réckelen',
'right-move-rootuserpages'    => 'Haapt-Benotzersäite réckelen',
'right-movefile'              => 'Fichiere réckelen',
'right-suppressredirect'      => 'Keng Viruleedung vum alen Numm aus uleeë wann eng Säit geréckelt gëtt',
'right-upload'                => 'Fichieren eroplueden',
'right-reupload'              => 'E Fichier iwwerschreiwen',
'right-reupload-own'          => 'E Fichier iwwerschreiwen deen Dir selwer eropgelueden hutt',
'right-reupload-shared'       => 'Lokalt Iwwerschreiwe vun engem Fichier deen an engem gemeinsam benotzte Repertoire steet',
'right-upload_by_url'         => 'E Fichier vun enger URL-Adress eroplueden',
'right-purge'                 => 'De Säitecache eidel maachen ouni nozefroen',
'right-autoconfirmed'         => 'Hallef-gespaarte Säiten änneren',
'right-bot'                   => 'Als automatesche Prozess behandelen (Bot)',
'right-nominornewtalk'        => 'Kleng Ännerungen op Diskussiounssäite léisen de Banner vun de neie Messagen net aus',
'right-apihighlimits'         => 'Benotzt méi héich Limite bei den API Ufroen',
'right-writeapi'              => "API benotze fir d'Wiki z'änneren",
'right-delete'                => 'Säite läschen',
'right-bigdelete'             => 'Säite mat engem groussen Historique läschen',
'right-deleterevision'        => 'Spezifesch Versioune vu Säite läschen a restauréieren',
'right-deletedhistory'        => 'Weis geläschte Versiounen am Historique, ouni den assoziéierten Text',
'right-deletedtext'           => "Geläschten Text an d'Ännerungen tëschent de geläschte Versioune weisen",
'right-browsearchive'         => 'Geläschte Säite sichen',
'right-undelete'              => 'Eng Säit restauréieren',
'right-suppressrevision'      => 'Virun den Administrateure verstoppte Versiounen nokucken a restauréieren',
'right-suppressionlog'        => 'Privat Lëschte kucken',
'right-block'                 => 'Aner Benotzer fir Ännerunge spären',
'right-blockemail'            => 'E Benotzer spären esou datt hie keng Maile verschécke kann',
'right-hideuser'              => 'E Benotzernumm spären, an deem e virun der Ëffentlechkeet verstoppt gëtt',
'right-ipblock-exempt'        => 'Ausname vun IP-Spären, automatesche Spären a vu Späre vu Plage vun IPen',
'right-proxyunbannable'       => 'Automatesche Proxyspären ëmgoen',
'right-unblockself'           => 'Seng eege Spär ophiewen',
'right-protect'               => 'Protectiounsniveauen änneren a gespaarte Säiten änneren',
'right-editprotected'         => 'Protegéiert Säiten (ouni Kaskadeprotectioun) änneren',
'right-editinterface'         => 'De Benotzerinterface änneren',
'right-editusercssjs'         => 'Anere Benotzer hir CSS a JS Fichieren änneren',
'right-editusercss'           => 'Anere Benotzer hir CSS Fichieren änneren',
'right-edituserjs'            => 'Anere Benotzer hir JS Fichieren änneren',
'right-rollback'              => "Ännerunge vum läschte Benotzer vun enger spezieller Säit séier z'récksetzen ''(rollback)''",
'right-markbotedits'          => 'Annuléiert Ännerungen als Botännerunge weisen',
'right-noratelimit'           => 'Net limitéiert duerch Zäitlimitatiounen um Server',
'right-import'                => 'Säite vun anere Wikien importéieren',
'right-importupload'          => 'Säite vun engem eropgeluedene Fichier importéieren',
'right-patrol'                => 'Aneren hir Ännerungen als kontrolléiert markéieren',
'right-autopatrol'            => 'Déi eegen Ännerungen automatesch als iwwerkuckt markéieren',
'right-patrolmarks'           => 'Markéierung "iwwerkuckt" an de rezenten Ännerunge weisen',
'right-unwatchedpages'        => 'Lëscht vun den net iwwerwaachte Säite weisen',
'right-trackback'             => 'En Trackback matdeelen',
'right-mergehistory'          => 'Zesummeféierung vum Historique vun de Versioune vu Säiten',
'right-userrights'            => 'All Benotzerrechter änneren',
'right-userrights-interwiki'  => 'Benotzerrechter vu Benotzer op anere Wiki-Siten änneren',
'right-siteadmin'             => "Datebank spären an d'Spär ophiewen",
'right-reset-passwords'       => 'Anere Benotzer hir Passwierder zrécksetzen',
'right-override-export-depth' => 'Säiten exportéieren inklusiv de verlinkte Säite bis zu enger Déift vu 5',
'right-sendemail'             => 'Anere Benotzer E-Maile schécken',
'right-revisionmove'          => 'Versioune réckelen',
'right-disableaccount'        => 'Benotzerkonten desaktivéieren',

# User rights log
'rightslog'      => 'Logbuch vun de Benotzerrechter',
'rightslogtext'  => "Dëst ass d'Lëscht vun den Ännerunge vu Benotzerrechter.",
'rightslogentry' => "huet d'Benotzerrechter vum $1 vun $2 op $3 geännert.",
'rightsnone'     => '(keen)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'dës Säit ze liesen',
'action-edit'                 => "dës Säit z'änneren",
'action-createpage'           => 'Säiten unzelleeën',
'action-createtalk'           => 'Diskussiounssäiten unzeleeën',
'action-createaccount'        => 'dëse Benotzerkont unzeleeën',
'action-minoredit'            => 'dës Ännerung als kleng Ännerung ze markéieren',
'action-move'                 => 'dës Säit ze réckelen',
'action-move-subpages'        => 'dës Säit an déi Ënnersäiten déi dozou gehéieren ze réckelen',
'action-move-rootuserpages'   => 'Haapt-Benotzersäite réckelen',
'action-movefile'             => 'Dëse Fichier réckelen',
'action-upload'               => 'dëse Fichier eropzelueden',
'action-reupload'             => "dëse Fichier (deen et scho gëtt) z'iwwerschreiwen",
'action-reupload-shared'      => "dëse Fichier op dem gemeinsam benotzte Repertoire z'iwwerschreiwen",
'action-upload_by_url'        => 'Fichiere vun enger Internetadress (URL) eropzelueden',
'action-writeapi'             => "d'API mat Schreifzougrëff ze benotzen",
'action-delete'               => 'dës Säit ze läschen',
'action-deleterevision'       => 'dës Versioun ze läschen',
'action-deletedhistory'       => "d'Lëscht vun de geläschte Versiounen ze gesinn",
'action-browsearchive'        => 'no geläschte Säiten ze sichen',
'action-undelete'             => 'dës Säit ze restauréieren',
'action-suppressrevision'     => 'déi verstoppte Versioun kucken a restauréieren',
'action-suppressionlog'       => 'dës privat Lëscht ze kucken',
'action-block'                => 'dëse Benotzer fir Ännerungen ze spären',
'action-protect'              => 'de Protectiounsstatus vun dëser Säit änneren',
'action-import'               => "dës Säit aus enger anerer Wiki z'importéieren",
'action-importupload'         => "dës Säit duerch d'Eropluede vun engem Fichier importéieren",
'action-patrol'               => 'd?Ännerunge vun Aneren als iwwerkuckt markéieren',
'action-autopatrol'           => 'eegen Ännerungen als iwwerkuckt ze markéieren',
'action-unwatchedpages'       => "d'Lëscht vun den net iwwerwaachte Säiten ze kucken",
'action-trackback'            => "en ''Trackback'' matzedeelen",
'action-mergehistory'         => "d'Versiounsgeschicht vun dëser Säit zesummenzeféieren",
'action-userrights'           => "all Benotzerrechter z'änneren",
'action-userrights-interwiki' => "d'Rechter vu Benotzer vun anere Wikien z'änneren",
'action-siteadmin'            => "d'Datebank ze spären oder d'Spär opzehiewen",
'action-revisionmove'         => 'Versioune réckelen',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|Ännerung|Ännerungen}}',
'recentchanges'                     => 'Rezent Ännerungen',
'recentchanges-legend'              => 'Optioune vun de rezenten Ännerungen',
'recentchangestext'                 => "Op dëser Säit kënnt Dir déi rezent Ännerungen op '''{{SITENAME}}''' gesinn.",
'recentchanges-feed-description'    => 'Verfollegt mat dësem Feed déi rezent Ännerungen op {{SITENAME}}.',
'recentchanges-label-newpage'       => 'Dës Ännerung huet eng nei Säit ugeluecht',
'recentchanges-label-minor'         => 'Dëst ass eng kleng Ännerung',
'recentchanges-label-bot'           => 'Dës Ännerung gouf vun engem Bot gemaacht',
'recentchanges-label-unpatrolled'   => 'Dës Ännerung gouf nach net nogekuckt',
'rcnote'                            => "Hei {{PLURAL:$1|ass déi lescht Ännerung|sinn déi lescht '''$1''' Ännerungen}} {{PLURAL:$2|vum leschten Dag|vun de leschten '''$2''' Deeg}}, Stand: $4 ëm $5 Auer.",
'rcnotefrom'                        => "Ugewise ginn d'Ännerunge vum '''$2''' un (maximal '''$1''' Ännerunge gi gewisen).",
'rclistfrom'                        => 'Weis Ännerunge vu(n) $1 un',
'rcshowhideminor'                   => 'Kleng Ännerunge $1',
'rcshowhidebots'                    => 'Botte $1',
'rcshowhideliu'                     => 'Ugemellte Benotzer $1',
'rcshowhideanons'                   => 'Anonym Benotzer $1',
'rcshowhidepatr'                    => 'iwwerwaacht Ännerunge $1',
'rcshowhidemine'                    => 'Meng Ännerunge $1',
'rclinks'                           => 'Weis déi lescht $1 Ännerunge vun de leschten $2 Deeg.<br />$3',
'diff'                              => 'Ënnerscheed',
'hist'                              => 'Versiounen',
'hide'                              => 'verstoppen',
'show'                              => 'weisen',
'minoreditletter'                   => 'k',
'newpageletter'                     => 'N',
'boteditletter'                     => 'B',
'number_of_watching_users_pageview' => '[$1 Benotzer {{PLURAL:$1|iwwerwaacht|iwwerwaachen}}]',
'rc_categories'                     => 'Nëmme Säiten aus de Kategorien (getrennt mat "|"):',
'rc_categories_any'                 => 'All',
'rc-change-size'                    => '$1 {{PLURAL:$1|Byte|Bytes}}',
'newsectionsummary'                 => 'Neien Abschnitt /* $1 */',
'rc-enhanced-expand'                => 'Detailer weisen (erfuedert JavaScript)',
'rc-enhanced-hide'                  => 'Detailer verstoppen',

# Recent changes linked
'recentchangeslinked'          => 'Ännerungen op verlinkte Säiten',
'recentchangeslinked-feed'     => 'Ännerungen op verlinkte Säiten',
'recentchangeslinked-toolbox'  => 'Ännerungen op verlinkte Säiten',
'recentchangeslinked-title'    => 'Ännerungen a Verbindung mat "$1"',
'recentchangeslinked-noresult' => 'Am ausgewielten Zäitraum goufen op de verlinkte Säite keng Ännerunge gemaach.',
'recentchangeslinked-summary'  => "Dëst ass eng Lëscht mat Ännerunge vu verlinkte Säiten op eng bestëmmte Säit (oder vu Membersäite vun der spezifizéierter Kategorie).
Säite vun [[Special:Watchlist|Ärer Iwwerwaachungslëscht]] si '''fett''' geschriwwen.",
'recentchangeslinked-page'     => 'Säitennumm:',
'recentchangeslinked-to'       => 'Weis Ännerungen zu de verlinkte Säiten aplaz vun der gefroter Säit',

# Upload
'upload'                      => 'Eroplueden',
'uploadbtn'                   => 'Fichier eroplueden',
'reuploaddesc'                => 'Eroplueden ofbriechen an zréck op de Formulaire fir Eropzelueden',
'upload-tryagain'             => 'Déi geännert Beschreiwung vum Fichier schécken',
'uploadnologin'               => 'Net ageloggt',
'uploadnologintext'           => 'Dir musst [[Special:UserLogin|ageloggt sinn]], fir Fichieren eroplueden zu kënnen.',
'upload_directory_missing'    => 'De Repertoire an deen Dir eropluede wollt ($1) feelt a konnt net vum Webserver ugeluecht ginn.',
'upload_directory_read_only'  => 'De Webserver kann net an den Upload-Repertoire ($1) schreiwen.',
'uploaderror'                 => 'Feeler bäim Eroplueden',
'upload-recreate-warning'     => "'''Opgepasst: E Fichier mat deem Numm gouf scho geläscht oder geréckelt.'''

Hei fannt Dir en Extrait aus dem Läsch- a Réckel-Logbuch fir dëse Fichier.",
'uploadtext'                  => "Benotzt dëse Formulaire, fir nei Fichieren eropzelueden.
Gitt op d'[[Special:FileList|Lëscht vun den eropgeluedene Fichieren]], fir no Fichieren ze sichen déi virdrun eropgeluede goufen, Eropluedunge fannt dir an der [[Special:Log/upload|Lëscht vun den eropgeluedene Fichieren]], geläschte Fichieren am [[Special:Log/delete|Läschlog]].

Fir e '''Bild''' op enger Säit zu benotzen, schreift amplaz vum Bild eng vun dëse Formelen:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Fichier.jpg]]</nowiki></tt>''' fir déi ganz Versioun vum Fichier ze benotzen
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Fichier.png|200px|thumb|left|alt text]]</nowiki></tt>''' fir eng 200 Pixel breet Versioun an enger Këscht am lénkse Rand mat 'alt text' als Beschreiwung
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Fichier.ogg]]</nowiki></tt>''' fir e Fichier direk ze verlinken ouni de Fichier ze weisen",
'upload-permitted'            => 'Erlaabte Formater vun de Fichieren: $1.',
'upload-preferred'            => 'Fichierszorten déi am beschte funktionéieren: $1.',
'upload-prohibited'           => 'Verbuede Fichiers Formater: $1.',
'uploadlog'                   => 'Lëscht vun den eropgeluedene Fichieren',
'uploadlogpage'               => 'Logbuch vum Eroplueden',
'uploadlogpagetext'           => "Dëst ass d'Lëscht vun de rezente Fichieren déi eropgeluede goufen.
Kuckt [[Special:NewFiles|d'Gallerie vun de neie Fichieren]] wann Dir méi e visuellen Iwwerbléck wëllt",
'filename'                    => 'Numm vum Fichier',
'filedesc'                    => 'Resumé',
'fileuploadsummary'           => 'Resumé/Quell:',
'filereuploadsummary'         => 'Ännerunge vum Fichier:',
'filestatus'                  => 'Copyright Status:',
'filesource'                  => 'Quell:',
'uploadedfiles'               => 'Eropgeluede Fichieren',
'ignorewarning'               => 'Warnung ignoréieren an de Fichier nawell späicheren',
'ignorewarnings'              => 'Ignoréier all Iwwerschreiwungswarnungen',
'minlength1'                  => "D'Nimm vu Fichiere musse mindestens e Buschtaf am Numm hunn.",
'illegalfilename'             => 'Am Fichiersnumm "$1" sti Schrëftzeechen, déi net am Numm vun enger Säit erlaabt sinn. W.e.g. nennt de Fichier anescht, a probéiert dann nach eng Kéier.',
'badfilename'                 => 'Den Numm vum Fichier gouf an "$1" ëmgeännert.',
'filetype-mime-mismatch'      => 'Dateierweiderung ".$1" passt net op de MIME-Typ vum Fichier ($2).',
'filetype-badmime'            => 'Fichiere vum MIME-Typ "$1" kënnen net eropgeluede ginn.',
'filetype-bad-ie-mime'        => 'Dëse Fichier kann net eropgeluede ginn, well den Internet Explorer en als „$1“ erkennt, deen net erlaabt ass well et e potentiell geféierleche Fichierstyp ass.',
'filetype-unwanted-type'      => "'''\".\$1\"''' ass een onerwënschte Fichiersformat.
Erwënschte {{PLURAL:\$3|Format ass|Formater sinn}}: \$2.",
'filetype-banned-type'        => '\'\'\'".$1"\'\'\' {{PLURAL:$4|is not a permitted file type|si Fichiersformater déi net erlaabt sinn}}.
Erlaabt {{PLURAL:$3|ass|sinn}}: $2.',
'filetype-missing'            => 'De Fichier huet keng Erweiderung (wéi z. B. ".jpg").',
'empty-file'                  => 'De Fichier deen Dir geschéckt hutt war eidel.',
'file-too-large'              => 'De Fichier deen Dir geschéckt hutt war ze grouss.',
'filename-tooshort'           => 'Den Numm vum Fichier ass ze kuerz.',
'filetype-banned'             => 'Dësen Typ vu Fichier kann net eropgeluede ginn.',
'verification-error'          => "Dëse Fichier huet d'Fichiers-Iwwerpréifung net passéiert.",
'hookaborted'                 => "D'Ännerung déi Dir versicht hutt ze maachen ass duerch en 'extension-hook' ofgebrach ginn.",
'illegal-filename'            => 'Den Numm vum Fichier ass net erlaabt.',
'overwrite'                   => "D'Iwwerschreiwe vun engem Fichier ass net erlaabt.",
'unknown-error'               => 'En onbekannte Feeler ass geschitt.',
'tmp-create-error'            => 'Den temporäre Fichier konnt net ugeluecht ginn.',
'tmp-write-error'             => 'Feeler beim Schreiwe vum temporäre Fichier.',
'large-file'                  => "D'Fichiere sollen no Méiglechkeet net méi grouss wéi $1 sinn. Dëse Fichier huet $2.",
'largefileserver'             => 'Dëse Fichier ass méi grouss wéi déi um Server agestallte Maximalgréisst.',
'emptyfile'                   => 'De Fichier deen Dir eropgelueden hutt, schéngt eidel ze sinn. Dëst kann duerch en Tippfeeler am Numm vum Fichier kommen. Préift w.e.g. no, op Dir dëse Fichier wierklech eropluede wëllt.',
'fileexists'                  => "Et gëtt schonn e Fichier mat dësem Numm, kuckt w.e.g.
'''<tt>[[:$1]]</tt>''' wann Dir net sécher sidd, ob Dir den Numm ännere wëllt.
[[$1|thumb]]",
'filepageexists'              => "D'Beschreiwungssäit fir dëse Fichier gouf schonns als '''<tt>[[:$1]]</tt>''' ugeluecht, et gëtt awer kee Fichier mat deem Numm.

De Resumé deen Dir agitt, gëtt net op d'Beschreiwungssäit iwwerholl.
Fir datt äre Resumé do opdaucht musst Dir e manuell änneren.
[[$1|thumb]]",
'fileexists-extension'        => "E Fichier mat engem ähnlechen Numm gëtt et schonn: [[$2|thumb]]
* Numm vum Fichier deen Dir versicht eropzelueden: '''<tt>[[:$1]]</tt>'''
* Numm vum Fichier deen et scho gëtt: '''<tt>[[:$2]]</tt>'''
Wielt w.e.g. en aneren Numm.",
'fileexists-thumbnail-yes'    => "Beim Fichier schéngt et sech ëm e klengt Bild ''(Miniatur)'' ze handelen. [[$1|thumb]]
Kuckt de Fichier '''<tt>[[:$1]]</tt>''' w.e.g. no.
Wann et sech ëm d'Bild an der Originalgréisst handelt, da brauch kee separat Bild als Minitaur eropgelueden ze ginn.",
'file-thumbnail-no'           => "Den Numm vum Fichier fänkt mat '''<tt>$1</tt>''' un.
Da deit drop hin datt et eng Minitaur ''(thumbnail)'' ass.
Wann Dir dat Bild a méi enger grousser Opléisung hutt, da luet dëst erop, soss ännert den Numm vum Fichier w.e.g.",
'fileexists-forbidden'        => 'Et gëtt schonn e Fichier mat dësem Numm an dee kann net iwwerschriwwe ginn.
Wann Dir de Fichier nach ëmmer eropluede wëllt, da gitt w.e.g. zréck a benotzt en neien Numm. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'E Fichier mat dësem Numm gëtt et schonn an dem gedeelte Repertoire.
Wann Dir dëse Fichier trotzdeem eropluede wëllt da gitt w.e.g. zréck a luet dëse Fichier ënner engem aneren Numm erop. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Dëse Fichier schéngt een Doublon vun {{PLURAL:$1|dësem Fichier|dëse Fichieren}} ze sinn:',
'file-deleted-duplicate'      => 'En identesche Fichier ([[$1]]) gouf virdru geläscht. Kuckt w.e.g. an der Lëscht vum Läschen no, Ier Dir en nach emol eropluet.',
'uploadwarning'               => 'Opgepasst',
'uploadwarning-text'          => "Ännert d'Beschreiwung hei ënnedrënner w.e.g. a versicht et nach eng Kéier.",
'savefile'                    => 'Fichier späicheren',
'uploadedimage'               => 'huet "[[$1]]" eropgelueden',
'overwroteimage'              => 'huet eng nei Versioun vun "[[$1]]" eropgelueden',
'uploaddisabled'              => "Pardon, d'Eropluede vu Fichieren ass ausgeschalt.",
'copyuploaddisabled'          => "D'Eroplueden iwwer URL ass desaktivéiert.",
'uploadfromurl-queued'        => "Dat wat Dir eropgelueden hutt gouf an d'Waardelëscht agedroen.",
'uploaddisabledtext'          => "D'Eropluede vu Fichieren ass ausgeschalt.",
'php-uploaddisabledtext'      => "D'Eropluede vu Fichieren ass am PHP desaktivéiert. Kuckt w.e.g. d'Astellung ''file_uploads'' no.",
'uploadscripted'              => 'An dësem Fichier ass HTML- oder Scriptcode, dee vun engem Webbrowser falsch interpretéiert kéint ginn.',
'uploadvirus'                 => 'An dësem Fichier ass ee Virus! Detailer: $1',
'upload-source'               => 'Quellfichier',
'sourcefilename'              => 'Numm vum Originalfichier:',
'sourceurl'                   => 'Quell-URL:',
'destfilename'                => 'Numm ënner deem de Fichier gespäichert gëtt:',
'upload-maxfilesize'          => 'Maximal Fichiersgréisst: $1',
'upload-description'          => 'Beschreiwung vum Fichier',
'upload-options'              => 'Optioune vum Eroplueden',
'watchthisupload'             => 'Dëse Fichier iwwerwaachen',
'filewasdeleted'              => 'E Fichier mat dësem Numm gouf schonn eemol eropgelueden an duerno nees geläscht. Kuckt w.e.g op $1 no, ier Dir dee Fichier nach eng Kéier eropluet.',
'upload-wasdeleted'           => "'''Opgepasst: Dir luet e Fichier erop, dee schonn eng Kéier geläscht gouf.'''

Kuckt w.e.g. genee no, ob dat neit Eroplueden de Richtlinnen entsprécht.
Zu Ärer Informatioun steet an der Läsch-Lëscht de Grond vum virege Läschen:",
'filename-bad-prefix'         => "Den Numm vum Fichier fänkt mat '''„$1“''' un. Dësen Numm krut en automatesch vun der Kamera a seet näischt iwwer dat aus, wat drop ass. Gitt dem Fichier w.e.gl. en Numm, deen den Inhalt besser beschreift, an deen net verwiesselt ka ginn.",
'upload-success-subj'         => 'Eroplueden erfollegräich',
'upload-success-msg'          => 'Ärt Eropluede vun [$2] huet fonctionnéiert. De Fichier ass hei disponibel: [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'Problem beim Eroplueden',
'upload-failure-msg'          => 'Et gouf e Problem mam Fichier vu(n) [$2] deen Dir eropgelueden hutt:

$1',
'upload-warning-subj'         => 'Warnung beim Eroplueden',
'upload-warning-msg'          => 'Et gouf e Problem beim Eropluede vun [$2]. Dir kënnt op de [[Special:Upload/stash/$1|Formulaire fir eropzelueden]] goe fir de Problem ze léisen.',

'upload-proto-error'        => 'Falsche Protokoll',
'upload-proto-error-text'   => "D'URL muss mat <code>http://</code> oder <code>ftp://</code> ufänken.",
'upload-file-error'         => 'Interne Feeler',
'upload-file-error-text'    => 'Beim Erstelle vun engem temporäre Fichier um Server ass een interne Feeler geschitt.
Informéiert w.e.g. e vun den [[Special:ListUsers/sysop|Administrateuren]].',
'upload-misc-error'         => 'Onbekannte Feeler beim Eroplueden',
'upload-misc-error-text'    => "Beim Eroplueden ass en onbekannte Feeler geschitt.
Kuckt d'URL w.e.g. no, a vergewëssert iech datt d'Säit online ass a probéiert et dann nach eng Kéier.
Wann de Problem weider besteet, dann un de [[Special:ListUsers/sysop|Administrateuren]].",
'upload-too-many-redirects' => "Et waren zevill Viruleedungen fir d'URL do",
'upload-unknown-size'       => 'Onbekannte Gréisst',
'upload-http-error'         => 'Et ass en HTTP-Feeler geschitt: $1',

# Special:UploadStash
'uploadstash'          => 'Um Server späichere virum Eroplueden',
'uploadstash-summary'  => 'Op dëser Säit huet en Zougrëff op Fichieren déi eropgeluede sinn (oder am Gaang sinn eropgelueden ze ginn) déi awer nach net op der Wiki publizéiert sinn. Dës Fichier kënnen eenzeg an eleng vun deem Benotzer deen se eropgelueden huet gesi ginn.',
'uploadstash-clear'    => 'Um Server gespäichert Fichieren déi nach net eropgeluede si läschen',
'uploadstash-nofiles'  => 'Dir hutt keng gespäichert Fichieren déi Dir nach net eropgelueden hutt.',
'uploadstash-badtoken' => "D'Ausféiere vun dëser Aktioun huet net fonctionnéiert, vläicht well d'Informatiounen iwwer Är Rechter ofgelaf sinn. Probéiert et nach emol.",
'uploadstash-errclear' => "D'Läsche vun de Fichieren huet net fonctionnéiert.",
'uploadstash-refresh'  => 'Lëscht vun de Fichieren aktualiséieren',

# img_auth script messages
'img-auth-accessdenied' => 'Zougang refuséiert',
'img-auth-notindir'     => 'De gefrote Pad ass net am Upload-Repertoire agestallt.',
'img-auth-badtitle'     => 'Aus "$1" ka kee valabelen Titel gemaach ginn.',
'img-auth-nologinnWL'   => 'Dir sidd net ageloggt a(n) "$1" ass net op der Wäisser Lëscht.',
'img-auth-nofile'       => 'De Fichier "$1" gëtt et net.',
'img-auth-isdir'        => 'Dir versicht op de Repertoire "$1" zouzegräifen.
Nèemmen Datenofruff ass erlaabt.',
'img-auth-streaming'    => '"$1" lueden.',
'img-auth-public'       => "D'Fonctioun img_auth.php erlaabt et fir Fichieren vun enger privater Wiki erauszeginn.
Dës Wiki ass als ëffentlech Wiki configuréiert.
Fir eng oprimal Sécherheet ass img_auth.php ausgeschalt.",
'img-auth-noread'       => 'De Benotzer hut keen Zougang fir "$1" ze liesen',

# HTTP errors
'http-invalid-url'      => 'Net-valabel URL: $1',
'http-invalid-scheme'   => 'URLe mam Schema "$1" ginn net ënnerstëtzt',
'http-request-error'    => "D'HTTP-Ufro huet wéinst engem onbekannte Feeler net fonctionnéiert.",
'http-read-error'       => 'HTTP-Feeler beim Liesen.',
'http-timed-out'        => 'HTTP-Ufro huet ze laang gebraucht (time out).',
'http-curl-error'       => 'Feeler beim Ofruff vun der URL: $1',
'http-host-unreachable' => "D'URL konnt net erreecht ginn.",
'http-bad-status'       => 'Et gouf e Problem bäi der HTTP-Ufro: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => "URL ass net z'erreechen",
'upload-curl-error6-text'  => 'Déi URL déi Dir uginn hutt kann net erreecht ginn.
Kuckt w.e.g. no op kee Feeler an der URL ass an op de Site och online ass.',
'upload-curl-error28'      => "D'Eroplueden huet ze laang gedauert (timeout)",
'upload-curl-error28-text' => "Dëse Site huet ze laang gebraucht fir z'äntwerten. Kuckt w. e. g. no, ob dëse Site online ass, waart een Ament a probéiert et dann nach eng Kéier. Et ka sënnvoll sinn, et nach eng Kéier méi spéit ze versichen.",

'license'            => 'Lizenzéiert:',
'license-header'     => 'Lizenzéieren',
'nolicense'          => 'Keng Lizenz ausgewielt',
'license-nopreview'  => '(Kucken ouni ofzespäichere geet net)',
'upload_source_url'  => ' (gëlteg, ëffentlech zougänglech URL)',
'upload_source_file' => ' (e Fichier op Ärem Computer)',

# Special:ListFiles
'listfiles-summary'     => "Op dëser Spezialsäit stinn all déi eropgeluede Fichieren. Déi als läscht eropgeluede Fichieren ginn als éischt ugewisen. Duerch e Klick op d'Iwwerschrëfte vun de Kolonnen kënnt Dir d'Sortéierung ëmdréinen an Dir kënnt esou och no enger anerer Kolonn sortéieren.",
'listfiles_search_for'  => 'Sicht nom Fichier:',
'imgfile'               => 'Fichier',
'listfiles'             => 'Lëscht vun de Fichieren',
'listfiles_thumb'       => 'Miniaturbild',
'listfiles_date'        => 'Datum',
'listfiles_name'        => 'Numm',
'listfiles_user'        => 'Benotzer',
'listfiles_size'        => 'Gréisst',
'listfiles_description' => 'Beschreiwung',
'listfiles_count'       => 'Versiounen',

# File description page
'file-anchor-link'                  => 'Fichier',
'filehist'                          => 'Versiounen',
'filehist-help'                     => 'Klickt op e bestëmmten Zäitpunkt fir déi respektiv Versioun vum Fichier ze kucken.',
'filehist-deleteall'                => 'All Versioune läschen',
'filehist-deleteone'                => 'Läschen',
'filehist-revert'                   => 'zrécksetzen',
'filehist-current'                  => 'aktuell',
'filehist-datetime'                 => 'Versioun vum',
'filehist-thumb'                    => 'Miniaturbild',
'filehist-thumbtext'                => "Miniaturbild fir d'Versioun vum $1",
'filehist-nothumb'                  => 'Kee Miniaturbild do',
'filehist-user'                     => 'Benotzer',
'filehist-dimensions'               => 'Dimensiounen',
'filehist-filesize'                 => 'Gréisst vum Fichier',
'filehist-comment'                  => 'Bemierkung',
'filehist-missing'                  => 'Fichier feelt',
'imagelinks'                        => 'Linken op Fichieren',
'linkstoimage'                      => 'Dës {{PLURAL:$1|Säit benotzt|Säite benotzen}} dëse Fichier:',
'linkstoimage-more'                 => 'Méi wéi {{PLURAL:$1|eng Säit ass|$1 Säite si}} mat dësem Fichier verlinkt.
Dës Lëscht weist nëmmen {{PLURAL:$1|den éischte Link|déi éischt $1 Linken}} op dëse Fichier.
Eng [[Special:WhatLinksHere/$2|komplett Lëscht]] ass disponibel.',
'nolinkstoimage'                    => 'Keng Säit benotzt dëse Fichier.',
'morelinkstoimage'                  => 'Weis [[Special:WhatLinksHere/$1|méi Linken]] op dëse Fichier.',
'redirectstofile'                   => '{{PLURAL:$1|De Fichier leet|Dës Fichiere leede}} virun op de Fichier:',
'duplicatesoffile'                  => '{{PLURAL:$1|De Fichier ass een Doublon|Dës Fichiere sinn Doublone}} vum Fichier ([[Special:FileDuplicateSearch/$2|méi Detailer]]):',
'sharedupload'                      => 'Dëse Fichier ass vu(n) $1 a ka vun anere Projete benotzt ginn.',
'sharedupload-desc-there'           => "Dëse Fichier ass vu(n) $1 a kann an anere Projete benotzt ginn.
Kuckt w.e.g. d'[$2 Säit mat der Beschreiwung vum Fichier] fir méi Informatiounen.",
'sharedupload-desc-here'            => "Dëse Fichier ass vu(n) $1 an däerf vun anere Projete benotzt ginn.
D'Beschreiwung op senger [$2 Beschreiwungssäit] steet hei ënnedrënner.",
'filepage-nofile'                   => 'Et gëtt kee Fichier mat deem Numm.',
'filepage-nofile-link'              => 'Et gëtt kee Fichier mat deem Numm, awer Dir kënnt [$1 en eroplueden].',
'uploadnewversion-linktext'         => 'Eng nei Versioun vun dësem Fichier eroplueden',
'shared-repo-from'                  => 'vu(n) $1',
'shared-repo'                       => 'e gemeinsam genotzte Medienarchiv',
'shared-repo-name-wikimediacommons' => 'Wikimedia-Commons',

# File reversion
'filerevert'                => '"$1" zrécksetzen',
'filerevert-legend'         => 'De Fichier zrécksetzen.',
'filerevert-intro'          => "Dir setzt de Fichier '''[[Media:$1|$1]]''' op d'[$4 Versioun vum $2, $3 Auer] zréck.",
'filerevert-comment'        => 'Bemierkung:',
'filerevert-defaultcomment' => "zréckgesat op d'Versioun vum $1, $2 Auer",
'filerevert-submit'         => 'Zrécksetzen',
'filerevert-success'        => "'''[[Media:$1|$1]]''' gouf op d'[$4 Versioun vum $2, $3 Auer] zréckgesat.",
'filerevert-badversion'     => 'Et gëtt keng Versioun vun deem Fichier mat der Zäitinformatioun déi Dir uginn hutt.',

# File deletion
'filedelete'                  => 'Läsch "$1"',
'filedelete-legend'           => 'Fichier läschen',
'filedelete-intro'            => "Dir läscht de Fichier '''[[Media:$1|$1]]''' mat all senge Versiounen (Historique).",
'filedelete-intro-old'        => "Dir läscht  d'Versioun $4  vum $2, $3 Auer vum Fichier '''„[[Media:$1|$1]]“'''.",
'filedelete-comment'          => 'Grond:',
'filedelete-submit'           => 'Läschen',
'filedelete-success'          => "'''$1''' gouf geläscht.",
'filedelete-success-old'      => "D'Versioun vu(n) '''[[Media:$1|$1]]''' vum $2, $3 Auer gouf geläscht.",
'filedelete-nofile'           => "'''$1''' gëtt et net.",
'filedelete-nofile-old'       => "Et gëtt vun '''$1''' keng archivéiert Versioun mat den Attributer déi dir uginn hutt.",
'filedelete-otherreason'      => 'Aneren/zousätzleche Grond:',
'filedelete-reason-otherlist' => 'Anere Grond',
'filedelete-reason-dropdown'  => "* Allgemeng Läschgrënn
** Verletzung vun den Droits d'auteur
** De Fichier gëtt et nach eng Kéier an der Datebank",
'filedelete-edit-reasonlist'  => 'Läschgrënn änneren',
'filedelete-maintenance'      => 'Läschen a Restauréiere vu Fichieren temporär ausgeschalt wéinst Maintenance.',

# MIME search
'mimesearch'         => 'Sich no MIME-Zort',
'mimesearch-summary' => "Op dëser Spezialsäit kënnen d'Fichieren no hirem MIME-Typ gefiltert ginn.
Dir musst ëmmer de Medien- a Subtyp aginn: z. Bsp. <tt>image/jpeg</tt>.",
'mimetype'           => 'MIME-Typ:',
'download'           => 'eroflueden',

# Unwatched pages
'unwatchedpages' => 'Nët iwwerwaachte Säiten',

# List redirects
'listredirects' => 'Lëscht vun de Viruleedungen',

# Unused templates
'unusedtemplates'     => 'Onbenotzte Schablounen',
'unusedtemplatestext' => 'Op dëser Säit stinn all Säiten aus dem {{ns:template}} Nummraum, déi a kenger anerer Säit benotzt ginn. Vergiesst net nozekucken, ob et keng aner Linken op dës Schabloune gëtt, ier Dir eng Schabloun läscht.',
'unusedtemplateswlh'  => 'Aner Linken',

# Random page
'randompage'         => 'Zoufallssäit',
'randompage-nopages' => 'Et gëtt keng Säiten {{PLURAL:$2|am Nummraum|an den Nummraim}}: $1.',

# Random redirect
'randomredirect'         => 'Zoufälleg Viruleedung',
'randomredirect-nopages' => 'Am Nummraum $1 gëtt et keng Viruleedungen.',

# Statistics
'statistics'                   => 'Statistik',
'statistics-header-pages'      => 'Säitestatistiken',
'statistics-header-edits'      => 'Statistik vun den Ännerungen',
'statistics-header-views'      => "Sttistiken iwwert d'Visiten",
'statistics-header-users'      => 'Benotzerstatistik',
'statistics-header-hooks'      => 'Aner Statistiken',
'statistics-articles'          => 'Säite mat Inhalt',
'statistics-pages'             => 'Säiten',
'statistics-pages-desc'        => 'All Säiten an der Wiki, inklusiv Diskussiounssäiten, Viruleedungen, asw.',
'statistics-files'             => 'Eropgeluede Fichieren',
'statistics-edits'             => 'Säitenännerungen zënter datt et {{SITENAME}} gëtt',
'statistics-edits-average'     => 'Duerchschnëttlech Zuel vun Ännerunge pro Säit',
'statistics-views-total'       => 'Total vun den Oprif',
'statistics-views-total-desc'  => 'Kucke vu Säiten déi et net gëtt a vu Spezialsäite sinn net mat dran',
'statistics-views-peredit'     => 'Oprif pro Ännerung',
'statistics-users'             => 'Registréiert [[Special:ListUsers|Benotzer]]',
'statistics-users-active'      => 'Aktiv Benotzer',
'statistics-users-active-desc' => 'Benotzer déi während {{PLURAL:$1|dem leschten Dag|de leschten $1 Deeg}} eppes gemaach hunn',
'statistics-mostpopular'       => 'Am meeschte gekuckte Säiten',

'disambiguations'      => 'Homonymie Säiten',
'disambiguationspage'  => 'Template:Homonymie',
'disambiguations-text' => 'Dës Säite si mat enger Homonymie-Säit verlinkt.
Sie sollten am beschten op déi eigentlech gemengte Säit verlinkt sinn.<br />
Eng Säite gëtt als Homonymiesäit behandelt, wa si eng Schabloun benotzt déi vu [[MediaWiki:Disambiguationspage]] verlinkt ass.',

'doubleredirects'            => 'Duebel Viruleedungen',
'doubleredirectstext'        => 'Op dëser Säit stinn déi Säiten déi op aner Viruleedungssäite viruleeden.
An all Rei sti Linken zur éischter an zweeter Viruleedung, souwéi d\'Zil vun der zweeter Viruleedung, déi normalerweis déi "richteg" Zilsäit ass, op déi déi éischt Viruleedung hilinke soll.
<del>Duerchgestrachen</del> Linke goufe schonn esou verännert datt déi duebel Viruleedung opgeléist ass.',
'double-redirect-fixed-move' => '[[$1]] gouf geréckelt, et ass elo eng Viruleedung op [[$2]]',
'double-redirect-fixer'      => 'Verbesserung vu Viruleedungen',

'brokenredirects'        => 'Futtis Viruleedungen',
'brokenredirectstext'    => 'Dës Viruleedunge linken op Säiten déi et net gëtt.',
'brokenredirects-edit'   => 'änneren',
'brokenredirects-delete' => 'läschen',

'withoutinterwiki'         => 'Säiten ouni Interwiki-Linken',
'withoutinterwiki-summary' => 'Op dëser Spezialsäit stinn all déi Säiten déi keng Interwikilinken hunn.',
'withoutinterwiki-legend'  => 'Prefix',
'withoutinterwiki-submit'  => 'Weisen',

'fewestrevisions' => 'Säite mat de mannsten Ännerungen',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|Byte|Byten}}',
'ncategories'             => '$1 {{PLURAL:$1|Kategorie|Kategorien}}',
'nlinks'                  => '$1 {{PLURAL:$1|Link|Linken}}',
'nmembers'                => '$1 {{PLURAL:$1|Member|Memberen}}',
'nrevisions'              => '$1 {{PLURAL:$1|Versioun|Versiounen}}',
'nviews'                  => '$1 {{PLURAL:$1|Offro|Offroen}}',
'nimagelinks'             => 'Benotzt op {{PLURAL:$1|enger Säit|$1 Säiten}}',
'ntransclusions'          => 'benotzt op {{PLURAL:$1|enger Säit|$1 Säiten}}',
'specialpage-empty'       => 'Dës Säit ass eidel.',
'lonelypages'             => 'Weesesäiten',
'lonelypagestext'         => 'Dës Säite sinn net vun anere Säite vu(n) {{SITENAME}} verlinkt respektiv a kenger Säit vu(n) {{SITENAME}} agebonn.',
'uncategorizedpages'      => 'Säiten ouni Kategorie',
'uncategorizedcategories' => 'Kategorien déi selwer nach keng Kategorie hunn',
'uncategorizedimages'     => 'Biller ouni Kategorie',
'uncategorizedtemplates'  => 'Schablounen ouni Kategorie',
'unusedcategories'        => 'Onbenotzte Kategorien',
'unusedimages'            => 'Onbenotzte Biller',
'popularpages'            => 'Populär Säiten',
'wantedcategories'        => 'Gewënschte Kategorien',
'wantedpages'             => 'Gewënschte Säiten',
'wantedpages-badtitle'    => 'Net valabelen Titel am Resultat: $1',
'wantedfiles'             => 'Gewënschte Fichieren',
'wantedtemplates'         => 'Gewënschte Schablounen',
'mostlinked'              => 'Dacks verlinkte Säiten',
'mostlinkedcategories'    => 'Dacks benotzte Kategorien',
'mostlinkedtemplates'     => 'Dacks benotzte Schablounen',
'mostcategories'          => 'Säite mat de meeschte Kategorien',
'mostimages'              => 'Dacks benotzte Biller',
'mostrevisions'           => 'Säite mat de meeschte Versiounen',
'prefixindex'             => 'All Säite mat Prefix',
'shortpages'              => 'Kuerz Säiten',
'longpages'               => 'Laang Säiten',
'deadendpages'            => 'Sakgaasse-Säiten',
'deadendpagestext'        => 'Dës Säite si mat kenger anerer Säit op {{SITENAME}} verlinkt.',
'protectedpages'          => 'Gespaarte Säiten',
'protectedpages-indef'    => 'Nëmme onbegrenzt-gespaarte Säite weisen',
'protectedpages-cascade'  => 'Nëmme Säiten déi duerch Kaskade gespaart sinn',
'protectedpagestext'      => 'Dës Säite si gespaart esou datt si weder geännert nach geréckelt kënne ginn',
'protectedpagesempty'     => 'Elo si keng Säite mat dëse Parameteren gespaart.',
'protectedtitles'         => 'Gespaarten Titel',
'protectedtitlestext'     => 'Dës Titele si gespaart an et ka keng Säit mat deenen Titelen ugeluecht ginn',
'protectedtitlesempty'    => 'Zur Zäit si mat de Parameteren déi Dir uginn hutt keng Säite fir neit Uleeë gespaart.',
'listusers'               => 'Benotzerlëscht',
'listusers-editsonly'     => 'Nëmme Benotzer mat Ännerunge weisen',
'listusers-creationsort'  => 'No dem Datum vum Uleeë sortéieren',
'usereditcount'           => '$1 {{PLURAL:$1|Ännerung|Ännerungen}}',
'usercreated'             => 'De(n) $1 ëm $2 Auer ugeluecht',
'newpages'                => 'Nei Säiten',
'newpages-username'       => 'Benotzernumm:',
'ancientpages'            => 'Al Säiten',
'move'                    => 'Réckelen',
'movethispage'            => 'Dës Säit réckelen',
'unusedimagestext'        => 'Dës Fichieren gëtt et, si sinn awer a kenger Säit agebonn.
Denkt w.e.g. drunn datt aner Internetsäiten dëse Fichier mat enger direkter URL verlinke kënnen. An dem Fall gëtt de Fichier hei opgelëscht obwuel en aktiv gebraucht gëtt.',
'unusedcategoriestext'    => 'Dës Kategoriesäiten existéieren, mee weder en Artikel nach eng Kategorie maachen dovunner Gebrauch.',
'notargettitle'           => 'Dir hutt keng Säit uginn.',
'notargettext'            => 'Dir hutt keng Zilsäit oder keen Zilbenotzer uginn fir déi dës Funktioun ausgeféiert soll ginn.',
'nopagetitle'             => 'Zilsäit gëtt et net',
'nopagetext'              => 'Déi Zilsäit déi dir uginn hutt gëtt et net.',
'pager-newer-n'           => '{{PLURAL:$1|nächsten|nächst $1}}',
'pager-older-n'           => '{{PLURAL:$1|vireg|vireg $1}}',
'suppress'                => 'Iwwersiicht',
'querypage-disabled'      => 'Dës Spezialsäit ass aus Performance-Grënn ausgeschalt.',

# Book sources
'booksources'               => 'Bicherreferenzen',
'booksources-search-legend' => 'No Bicherreferenze sichen',
'booksources-go'            => 'Sichen',
'booksources-text'          => 'Hei ass eng Lëscht mat Linken op Internetsäiten, déi nei a gebraucht Bicher verkafen. Do kann et sinn datt Dir méi Informatiounen iwwer déi Bicher fannt déi Dir sicht.',
'booksources-invalid-isbn'  => "D'ISBN-Nummer déi Dir uginn hutt schéngt net gëlteg ze sinn. Kuckt w.e.g. no ob beim Kopéiere kee Feeler geschitt ass.",

# Special:Log
'specialloguserlabel'  => 'Benotzer:',
'speciallogtitlelabel' => 'Titel:',
'log'                  => 'Logbicher',
'all-logs-page'        => 'All ëffentlech Logbicher',
'alllogstext'          => "Dëst ass eng kombinéiert Lëscht vu Logbicher op {{SITENAME}}.
Dir kënnt d'Sich limitéieren wann dir e Log-Typ, e Benotzernumm (case-senisitive) oder déi gefrote Säit  (och case-senisitive) agitt.",
'logempty'             => 'Näischt fonnt.',
'log-title-wildcard'   => 'Titel fänkt mat dësem Text un',

# Special:AllPages
'allpages'          => 'All Säiten',
'alphaindexline'    => '$1 bis $2',
'nextpage'          => 'Nächst Säit ($1)',
'prevpage'          => 'Säit viru(n) ($1)',
'allpagesfrom'      => 'Säite weisen, ugefaange mat:',
'allpagesto'        => 'Weis Säite bis:',
'allarticles'       => "All d'Säiten",
'allinnamespace'    => "All d'Säiten ($1 Nummraum)",
'allnotinnamespace' => "All d'Säiten (net am $1 Nummraum)",
'allpagesprev'      => 'Vireg',
'allpagesnext'      => 'Nächst',
'allpagessubmit'    => 'Lass',
'allpagesprefix'    => 'Säite mat Prefix weisen:',
'allpagesbadtitle'  => 'Den Titel vun dëser Säit ass net valabel oder hat en Interwiki-Prefix. Et ka sinn datt een oder méi Zeechen drasinn, déi net an Titele benotzt kënne ginn.',
'allpages-bad-ns'   => 'Den Nummraum „$1“ gëtt et net op {{SITENAME}}.',

# Special:Categories
'categories'                    => 'Kategorien',
'categoriespagetext'            => 'Dës {{PLURAL:$1|Kategorie huet|Kategorien hu}} Säiten oder Medien.
[[Special:UnusedCategories|Netbenotze Kategorien]] ginn hei net gewisen.
Kuckt och [[Special:WantedCategories|Gewënschte Kategorien]].',
'categoriesfrom'                => 'Weis Kategorien ugefaang bei:',
'special-categories-sort-count' => 'No der Zuel sortéieren',
'special-categories-sort-abc'   => 'alphabetesch sortéieren',

# Special:DeletedContributions
'deletedcontributions'             => 'Geläschte Kontributiounen',
'deletedcontributions-title'       => 'Geläschte Kontributiounen',
'sp-deletedcontributions-contribs' => 'Kontributiounen',

# Special:LinkSearch
'linksearch'       => 'Extern Linken',
'linksearch-pat'   => 'Sich-Critère:',
'linksearch-ns'    => 'Nummraum:',
'linksearch-ok'    => 'Sichen',
'linksearch-text'  => 'Sougennante "Wildcards" wéi zum Beispill <tt>*.example.com</tt> kënne benotzt ginn.<br />
Ënnerstëtzte Protekoller: <tt>$1</tt>',
'linksearch-line'  => '$1 verlinkt vun $2',
'linksearch-error' => 'Wildcards (*,?) kënnen nëmmen am Ufank vum Host-Numm benotzt ginn.',

# Special:ListUsers
'listusersfrom'      => "D'Benotzer weisen, ugefaange bei:",
'listusers-submit'   => 'Weis',
'listusers-noresult' => 'Kee Benotzer fonnt.',
'listusers-blocked'  => '(gespaart)',

# Special:ActiveUsers
'activeusers'            => 'Lëscht vun den aktive Benotzer',
'activeusers-intro'      => 'Dëst ass eng Lëscht vun de Benotzer déi op iergend eng Manéier an de leschten $1 {{PLURAL:$1|Dag|Deeg}} aktiv waren.',
'activeusers-count'      => '$1 {{PLURAL:$1|Ännerung|Ännerungen}} {{PLURAL:$3|gëschter|an de leschten $3 Deeg}}',
'activeusers-from'       => 'Benotzer weisen, ugefaang bäi:',
'activeusers-hidebots'   => 'Botte verstoppen',
'activeusers-hidesysops' => 'Administrateure verstoppen',
'activeusers-noresult'   => 'Keng Benotzer fonnt.',

# Special:Log/newusers
'newuserlogpage'              => 'Logbuch vun den neien Umeldungen',
'newuserlogpagetext'          => "Dëst ass d'Lescht vun de Benotzernimm déi ugeluecht goufen.",
'newuserlog-byemail'          => "d'Passwuert gouf per E-Mail geschéckt",
'newuserlog-create-entry'     => 'Neie Benotzer',
'newuserlog-create2-entry'    => 'huet den neie Benotzerkont $1 opgemaach',
'newuserlog-autocreate-entry' => 'Benotzerkont gouf automatesch gemaach',

# Special:ListGroupRights
'listgrouprights'                      => 'Rechter vun de Benotzergruppen',
'listgrouprights-summary'              => 'Dëst ass eng Lëscht vun den op dëser Wiki definéierte Benotzergruppen an den domat verbonnene Rechter.
Et ginn [[{{MediaWiki:Listgrouprights-helppage}}|zousätzlech Informatiounen]] iwwer individuell Benotzerrechter.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Recht dat zouerkannt gouf</span>
* <span class="listgrouprights-revoked">Recht dat ofgeholl gouf</span>',
'listgrouprights-group'                => 'Grupp',
'listgrouprights-rights'               => 'Rechter',
'listgrouprights-helppage'             => 'Help:Grupperechter',
'listgrouprights-members'              => '(Lëscht vun de Memberen)',
'listgrouprights-addgroup'             => 'Kann {{PLURAL:$2|dës Grupp|dës Gruppen}} derbäisetzen: $1',
'listgrouprights-removegroup'          => 'Kann {{PLURAL:$2|dëse Gruppe|dës Gruppen}} ewechhuelen: $1',
'listgrouprights-addgroup-all'         => 'Kann all Gruppen derbäisetzen',
'listgrouprights-removegroup-all'      => 'Ka Benotzer aus alle Gruppen eraushuelen',
'listgrouprights-addgroup-self'        => "Däerf {{PLURAL:$2|de Grupp|d'Gruppe}} bäi säin eegene Benotzerkont derbäisetzen: $1",
'listgrouprights-removegroup-self'     => "Däerf {{PLURAL:$2|de Grupp|d'Gruppe}} vu sengem eegene Benotzerkont ewechhuelen: $1",
'listgrouprights-addgroup-self-all'    => 'däerf all Gruppe bäi säin eegene Benotzerkont derbäisetzen',
'listgrouprights-removegroup-self-all' => 'Däerf all Gruppe vu sengem eegene Benotzerkont ewechhuelen',

# E-mail user
'mailnologin'          => 'Keng E-Mailadress',
'mailnologintext'      => 'Dir musst [[Special:UserLogin|ugemellt]] sinn an eng gëlteg E-Mail Adress an Äre [[Special:Preferences|Astellungen]] aginn hunn, fir engem anere Benotzer eng E-Mail ze schécken.',
'emailuser'            => 'Dësem Benotzer eng E-Mail schécken',
'emailpage'            => 'Dem Benotzer eng E-Mail schécken',
'emailpagetext'        => 'Dir kënnt mat dësem Formulaire dësem Benotzer en E-Mail-Message schécken.
D\'E-Mailadress, déi Dir an [[Special:Preferences|Ären Astellungen]] aginn hutt, steet an der "From" Adress vun der Mail, sou datt den Destinataire Iech direkt äntwerte kann.',
'usermailererror'      => 'E-Mail-Objet mellt deen heite Feeler:',
'defemailsubject'      => 'E-Mail vu(n) {{SITENAME}}',
'usermaildisabled'     => 'Benotzer E-Mail ausgeschalt',
'usermaildisabledtext' => 'Dir kënnt op dëser Wiki anere Benotzer keng E-Mail schécken',
'noemailtitle'         => 'Keng E-Mailadress',
'noemailtext'          => 'Dëse Benotzer huet keng valabel E-Mailadress uginn.',
'nowikiemailtitle'     => 'Keng E-Mail erlaabt',
'nowikiemailtext'      => 'Dëse Benotzer wëllt keng E-Maile vun anere Benotzer kréien.',
'email-legend'         => 'Engem anere(n) {{SITENAME}}-Benotzer eng E-Mail schécken',
'emailfrom'            => 'Vum:',
'emailto'              => 'Fir:',
'emailsubject'         => 'Sujet:',
'emailmessage'         => 'Message:',
'emailsend'            => 'Schécken',
'emailccme'            => 'Eng E-Mailkopie vun der Noriicht fir mech',
'emailccsubject'       => 'Kopie vun denger Noriicht un $1: $2',
'emailsent'            => 'E-Mail geschéckt',
'emailsenttext'        => 'Är E-Mail gouf fortgeschéckt.',
'emailuserfooter'      => 'Dës E-Mail gouf vum $1 dem $2 geschéckt dobäi gouf d\'Funktioun "Benotzer E-Mail" op {{SITENAME}} benotzt.',

# User Messenger
'usermessage-summary' => 'Benoriichtegung hannerloossen.',
'usermessage-editor'  => 'Benoriichtegungs-System',

# Watchlist
'watchlist'            => 'Meng Iwwerwaachungslëscht',
'mywatchlist'          => 'Meng Iwwerwaachungslëscht',
'watchlistfor2'        => 'Vum $1 $2',
'nowatchlist'          => 'Är Iwwerwaachungslëscht ass eidel.',
'watchlistanontext'    => "Dir musst $1 fir Säiten op ärer Iwwerwaachungslëscht ze gesinn oder z'änneren.",
'watchnologin'         => 'Net ageloggt',
'watchnologintext'     => "Dir musst [[Special:UserLogin|ugemellt]] sinn, fir Är Iwwerwaachungslëscht z'änneren.",
'addedwatch'           => "Op d'Iwwerwaachungslëscht gesat",
'addedwatchtext'       => "D'Säit \"[[:\$1]]\" gouf op är [[Special:Watchlist|Iwwerwaachtungslëscht]] gesat. All weider Ännerungen op dëser Säit an/oder der Diskussiounssäit ginn hei opgelëscht, an d'Säit gesäit '''fettgedréckt''' bei de [[Special:RecentChanges|rezenten Ännerungen]] aus, fir se méi séier erëmzefannen.

Wann dir dës Säit net méi iwwerwaache wëllt, klickt op \"Net méi iwwerwaachen\" uewen op der Säit.",
'removedwatch'         => 'Vun der Iwwerwaachungslëscht erofgeholl',
'removedwatchtext'     => 'D\'Säit "[[:$1]]" gouf vun [[Special:Watchlist|ärer Iwwerwaachungslëscht]] erofgeholl.',
'watch'                => 'Iwwerwaachen',
'watchthispage'        => 'Dës Säit iwwerwaachen',
'unwatch'              => 'Net méi iwwerwaachen',
'unwatchthispage'      => 'Net méi iwwerwaachen',
'notanarticle'         => 'Keng Säit',
'notvisiblerev'        => 'Versioun gouf geläscht',
'watchnochange'        => 'Keng vun Ären iwwerwaachte Säite gouf während der ugewisener Zäit verännert.',
'watchlist-details'    => "{{PLURAL:$1|1 Säit|$1 Säiten}} sinn op ärer Iwwerwaachungsklëscht (d'Diskussiounssäite net matgezielt).",
'wlheader-enotif'      => '* E-Mail-Bescheed ass aktivéiert.',
'wlheader-showupdated' => "* Säiten déi zënter ärer leschter Visite geännert goufen, si '''fett''' geschriwwen",
'watchmethod-recent'   => 'Rezent Ännerunge ginn op iwwerwaacht Säiten iwwerpréift',
'watchmethod-list'     => 'Iwwerwaachte Säite ginn op rezent Ännerungen iwwerpréift',
'watchlistcontains'    => 'Op ärer Iwwerwaachungslëscht $1 {{PLURAL:$1|steet $1 Säit|stinn $1 Säiten}}.',
'iteminvalidname'      => "Problem mat dem Objet '$1', ongëltegen Numm ...",
'wlnote'               => "Hei {{PLURAL:$1|ass déi lescht Ännerung|sinn déi lescht '''$1''' Ännerunge}} vun {{PLURAL:$2|der leschter Stonn|de leschte(n) '''$2''' Stonnen}}.",
'wlshowlast'           => "Weis d'Ännerunge vun de leschte(n) $1 Stonnen, $2 Deeg oder $3 (an de leschten 30 Deeg).",
'watchlist-options'    => 'Optioune vun der Iwwerwaachungslëscht',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Iwwerwaachen …',
'unwatching' => 'Net méi iwwerwaachen …',

'enotif_mailer'                => '{{SITENAME}} E-Mail-Informatiounssystem',
'enotif_reset'                 => 'All Säiten als besicht markéieren',
'enotif_newpagetext'           => 'Dëst ass eng nei Säit.',
'enotif_impersonal_salutation' => '{{SITENAME}}-Benotzer',
'changed'                      => 'geännert',
'created'                      => 'gemaach',
'enotif_subject'               => '[{{SITENAME}}] D\'Säit "$PAGETITLE" gouf vum $PAGEEDITOR $CHANGEDORCREATED',
'enotif_lastvisited'           => 'All Ännerungen op ee Bléck: $1',
'enotif_lastdiff'              => 'Kuckt $1 fir dës Ännerung.',
'enotif_anon_editor'           => 'Anonyme Benotzer $1',
'enotif_body'                  => 'Léiwe $WATCHINGUSERNAME,

D\'{{SITENAME}}-Säit "$PAGETITLE" gouf vum $PAGEEDITOR den $PAGEEDITDATE $CHANGEDORCREATED. Aktuell Versioun: $PAGETITLE_URL

$NEWPAGE

Resumé vum Mataarbechter: $PAGESUMMARY $PAGEMINOREDIT

Dëse Mataarbechter kontaktéieren:
E-Mail: $PAGEEDITOR_EMAIL
Wiki: $PAGEEDITOR_WIKI

Et gi soulaang keng weider Maile geschéckt, bis Dir d\'Säit nees emol besicht hutt.
Op Ärer Iwwerwaachungslëscht kënnt Dir all Benoorichtigungsmarkeren zesummen zrécksetzen.


             Äre frëndleche {{SITENAME}} Benoriichtigungssystem

--
Fir d\'Astellungen op ärer Iwwerwaachungslëscht z\'änneren, besicht w.e.g.
{{fullurl:Special:Watchlist/edit}}

Fir d\'Säit vun Ärer Iwwerwaachungslëscht erofzehuelen, gitt w.e.g. op
$UNWATCHURL

Reaktiounen a méi Hëllef:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Säit läschen',
'confirm'                => 'Confirméieren',
'excontent'              => "Inhalt war: '$1'",
'excontentauthor'        => "Op der Säit stoung: '$1' (An als eenzegen dru geschriwwen hat de Benotzer '[[Special:Contributions/$2|$2]]').",
'exbeforeblank'          => "Den Inhalt virum Läsche war: '$1'",
'exblank'                => "D'Säit war eidel",
'delete-confirm'         => 'Läsche vu(n) "$1"',
'delete-legend'          => 'Läschen',
'historywarning'         => "'''Opgepasst:''' Déi Säit déi Dir läsche wëllt huet en Historique mat ongeféier $1 {{PLURAL:$1|Versioun|Versiounen}}:",
'confirmdeletetext'      => "Dir sidd am Gaang, eng Säit mat hirem kompletten Historique vollstänneg aus der Datebank ze läschen.
W.e.g. confirméiert, datt Dir dëst wierklech wëllt, datt Dir d'Konsequenze verstitt, an datt dat Ganzt am Aklang mat de [[{{MediaWiki:Policy-url}}|Richtlinne]] geschitt.",
'actioncomplete'         => 'Aktioun ofgeschloss',
'actionfailed'           => 'Aktioun huet net fonctionnéiert',
'deletedtext'            => '"<nowiki>$1</nowiki>" gouf geläscht. Kuckt $2 fir eng Lëscht vun de Säiten déi viru Kuerzem geläscht goufen.',
'deletedarticle'         => 'huet "[[$1]]" geläscht',
'suppressedarticle'      => 'geläscht "$1"',
'dellogpage'             => 'Läschlëscht',
'dellogpagetext'         => 'Hei fannt dir eng Lëscht mat rezent geläschte Säiten. All Auerzäiten sinn déi vum Server.',
'deletionlog'            => 'Läschlëscht',
'reverted'               => 'Op déi Versioun virdrun zréckgesat',
'deletecomment'          => 'Grond:',
'deleteotherreason'      => 'Aneren/ergänzende Grond:',
'deletereasonotherlist'  => 'Anere Grond',
'deletereason-dropdown'  => '* Heefegst Grënn fir eng Säit ze läschen
** Wonsch vum Auteur
** Verletzung vun engem Copyright
** Vandalismus',
'delete-edit-reasonlist' => 'Läschgrënn änneren',
'delete-toobig'          => "Dës Säit huet e laangen Historique, méi wéi $1 {{PLURAL:$1|Versioun|Versiounen}}.
D'Läsche vun esou Säite gouf limitéiert fir ongewollte Stéierungen op {{SITENAME}} ze verhënneren.",
'delete-warning-toobig'  => "Dës Säit huet eng laang Versiounsgeschicht, méi wéi $1 {{PLURAL:$1|Versioun|Versiounen}}.
D'Läschen dovun kann zu Stéierungen am Funktionnement vun {{SITENAME}} féieren;
dës Aktioun soll mat Vierssiicht gemaach ginn.",

# Rollback
'rollback'          => 'Ännerungen zrécksetzen',
'rollback_short'    => 'Zrécksetzen',
'rollbacklink'      => 'Zrécksetzen',
'rollbackfailed'    => 'Zrécksetzen huet net geklappt',
'cantrollback'      => 'Lescht Ännerung kann net zréckgesat ginn. De leschten Auteur ass deen eenzegen Auteur vun dëser Säit.',
'alreadyrolled'     => 'Déi lescht Ännerung vun der Säit [[:$1]] vum [[User:$2|$2]] ([[User talk:$2|talk]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]);; kann net zeréckgesat ginn;
een Aneren huet dëst entweder scho gemaach oder nei Ännerungen agedroen.

Déi lescht Ännerung vun der Säit ass vum [[User:$3|$3]] ([[User talk:$3|Diskussioun]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "De Resumé vun der Ännerung war: \"''\$1''\".",
'revertpage'        => 'Ännerunge vum [[Special:Contributions/$2|$2]] ([[User talk:$2|Diskussioun]]) zréckgesat op déi lescht Versioun vum [[User:$1|$1]]',
'revertpage-nouser' => 'Zréckgesaten Ännerungen vum (Benotzernummewechgeholl) op déilescht Versioun vum [[User:$1|$1]]',
'rollback-success'  => "D'Ännerunge vum $1 goufen zréckgesat op déi lescht Versioun vum $2.",

# Edit tokens
'sessionfailure-title' => 'Setzungsfeeler',
'sessionfailure'       => 'Et schéngt e Problem mat ärer Loginséance ze ginn;
Dës Aktioun gouf aus Sécherheetsgrënn ofgebrach, fir ze verhënneren datt är Séance piratéiert ka ginn.
Klickt w.e.g. op "Zréck" a lued déi Säit vun där Dir komm sidd nei, a versicht et dann nach eng Kéier.',

# Protect
'protectlogpage'              => 'Protectiouns-Logbuch',
'protectlogtext'              => "Dëst ass d'Lëscht vun de Säitespären.
Kuckt d'[[Special:ProtectedPages|Lëscht vun de gespaarte Säite]] fir eng Lëscht vun den aktuelle Säitespären.",
'protectedarticle'            => 'huet "[[$1]]" gespaart',
'modifiedarticleprotection'   => 'huet d\'Protectioun vun "[[$1]]" geännert',
'unprotectedarticle'          => "huet d'Spär vu(n) [[$1]] opgehuewen",
'movedarticleprotection'      => 'huet de Säiteschutz vun "[[$2]]" op "[[$1]]" geännert',
'protect-title'               => 'Ännerung vun der Protectioun vu(n) „$1“',
'prot_1movedto2'              => '[[$1]] gouf op [[$2]] geréckelt',
'protect-legend'              => "Confirméiert d'Protectioun",
'protectcomment'              => 'Grond:',
'protectexpiry'               => 'Dauer vun der Spär:',
'protect_expiry_invalid'      => "D'Dauer déi Dir uginn hutt ass ongëlteg.",
'protect_expiry_old'          => "D'Spärzäit läit an der Vergaangenheet.",
'protect-unchain-permissions' => "D'Spär vu weidere Spär-Optioune ophiewen",
'protect-text'                => "Hei kënnt Dir de Protectiounsstatus fir d'Säit '''$1''' kucken an änneren.",
'protect-locked-blocked'      => "Dir kënnt den Niveau vun der Säite-Protectioun net änneren, well Äre Benotzerkont gespaart ass.
Hei sinn déi aktuell Säite-Protectiouns-Astellungen fir d'Säit '''$1''':",
'protect-locked-dblock'       => "Den Niveau vun der Proectioun vun der Säit kann net geänenert ginn, well d'Datebank gespaart ass.
Hei sinn déi aktuell Astellungen fir d'Säit '''$1''':",
'protect-locked-access'       => "Dir hutt net déi néideg Rechter fir de Protectiouns-Niveau vun dëser Säit z'änneren.
Hei sinn déi aktuell Astellunge fir d'Säit '''$1''':",
'protect-cascadeon'           => "Dës Säit ass elo gespaart well si an déi folgend {{PLURAL:$1|Säit|Säiten}} agebonn ass déi duerch eng Kaskadespär gespaart {{PLURAL:$1|ass|sinn}}. De Protectiounsniveau ka fir dës Säit geännert ginn, dat huet awer keen Afloss op d'Kaskadespär.",
'protect-default'             => 'All Benotzer erlaben',
'protect-fallback'            => 'Eng "$1"-Autorisatioun gëtt gebraucht.',
'protect-level-autoconfirmed' => 'Spär fir nei an net ugemellte Benotzer',
'protect-level-sysop'         => 'Nëmmen Administrateuren',
'protect-summary-cascade'     => 'Protectioun a Kaskaden',
'protect-expiring'            => 'bis $1 (UTC)',
'protect-expiry-indefinite'   => 'net definéiert',
'protect-cascade'             => "Kaskade-Spär – alleguerten d'Schablounen déi an dës Säit agebonne si ginn och gespaart.",
'protect-cantedit'            => "Dir kënnt d'Spär vun dëser Säit net änneren, well Dir net déi néideg Rechter hutt fir déi Säit z'änneren.",
'protect-othertime'           => 'Aner Zäit:',
'protect-othertime-op'        => 'aner Zäit',
'protect-existing-expiry'     => 'Ënn vun der Säitespär: $2 ëm $3 Auer',
'protect-otherreason'         => 'Aneren/zousätzleche Grond:',
'protect-otherreason-op'      => 'Anere Grond',
'protect-dropdown'            => '*Déi heefegst Grënn fir eng Säit ze spären
** Weblink-Spam
** Permanenten Ännerungskonflikt
** Dacks benotzte Schablounen
** Säit déi dacks besicht gëtt',
'protect-edit-reasonlist'     => 'Grënn vun der Protectioun änneren',
'protect-expiry-options'      => '1 Stonn:1 hour,1 Dag:1 day,1 Woch:1 week,2 Wochen:2 weeks,1 Mount:1 month,3 Méint:3 months,6 Méint:6 months,1 Joer:1 year,onbegrenzt:infinite',
'restriction-type'            => 'Berechtigung:',
'restriction-level'           => 'Niveau vun de Limitatiounen:',
'minimum-size'                => 'Mindestgréisst',
'maximum-size'                => 'Maximalgréisst:',
'pagesize'                    => '(Byten)',

# Restrictions (nouns)
'restriction-edit'   => 'Änneren',
'restriction-move'   => 'réckelen',
'restriction-create' => 'Uleeën',
'restriction-upload' => 'Eroplueden',

# Restriction levels
'restriction-level-sysop'         => 'ganz gespaart',
'restriction-level-autoconfirmed' => 'hallef-gespaart (nëmmen ugemellte Benotzer déi net nei sinn)',
'restriction-level-all'           => 'alleguerten',

# Undelete
'undelete'                     => 'Geläschte Säite restauréieren',
'undeletepage'                 => 'Geläschte Säite kucken a restauréieren',
'undeletepagetitle'            => "'''Op dëser Lëscht sti geläschte Versioune vun [[:$1]]'''.",
'viewdeletedpage'              => 'Geläschte Säite weisen',
'undeletepagetext'             => "Dës {{PLURAL:$1|Säit gouf |Säite goufe}} geläscht mee sinn nach ëmmer am Archiv a kënne vun Administrateure restauréiert ginn. D'Archiv gëtt periodesch eidel gemaach.",
'undelete-fieldset-title'      => 'Versioune restauréieren',
'undeleteextrahelp'            => "Fir d'Säit komplett mat alle Versiounen ze retabléieren, markéiert keng vun den eenzelne Casë mat engem Krop a klickt op '''''Restauréieren'''''.
Fir nëmmen eng bestëmmte Versioun vun der Säit ze restauréieren, markéiert d'Case vun der gewënschter Versioun mat engem Krop, a klickt duerno op '''''Restauréieren'''''.
Klickt op '''''Reset''''' fir d'Kommentarfeld eidel ze maachen an d'Kreep aus all de Casen ewechzehuelen.",
'undeleterevisions'            => '{{PLURAL:$1|1 Versioun|$1 Versiounen}} archivéiert',
'undeletehistory'              => 'Wann Dir dës Säit restauréiert, ginn och all déi al Versioune restauréiert.
Wann zënter dem Läschen eng nei Säit mat dem selwechten Numm ugeluecht gouf, ginn déi restauréiert Versioune chronologesch an den Historique agedroen.',
'undeleterevdel'               => "D'Restauratioun gëtt net gemaach wann dëst dozou féiert datt déi aktuell Versioun vun der Säit oder vum Fichier deelweis geläscht gëtt.
An esou Fäll däerf déi neiste Versioun net markéiert ginn oder déi neiste geläschte Versioun muss nees ugewise ginn.",
'undeletehistorynoadmin'       => "Dës Säit gouf geläscht. De Grond fir d'Läsche gesitt der ënnen, zesumme mat der Iwwersiicht vun den eenzele Versioune vun der Säit an hiren Auteuren. Déi verschidden Textversioune kënnen awer just vun Administrateure gekuckt a restauréiert ginn.",
'undelete-revision'            => 'Geläschte Versioun vu(n) $1 (Versioun vum $4 um $5 Auer) vum $3:',
'undeleterevision-missing'     => "Ongëlteg oder Versioun déi feelt. Entweder ass de Link falsch oder d'Versioun gouf aus dem Archiv restauréiert oder geläscht.",
'undelete-nodiff'              => 'Et si keng méi al Versiounen do.',
'undeletebtn'                  => 'Restauréieren',
'undeletelink'                 => 'weisen/restauréieren',
'undeleteviewlink'             => 'weisen',
'undeletereset'                => 'Ofbriechen',
'undeleteinvert'               => 'Auswiel ëmdréinen',
'undeletecomment'              => 'Grond:',
'undeletedarticle'             => 'huet "[[$1]]" restauréiert',
'undeletedrevisions'           => '$1 {{PLURAL:$1|Versioun gouf|$1 Versioune goufe}} restauréiert',
'undeletedrevisions-files'     => '{{PLURAL:$1|1 Versioun|$1 Versiounen}} a(n) {{PLURAL:$2|1 Fichier|$2 Fichiere}} goufe restauréiert',
'undeletedfiles'               => '$1 {{PLURAL:$1|Fichier gouf|Fichiere goufe}} restauréiert',
'cannotundelete'               => "D'Restauratioun huet net fonktionéiert. Een anere Benotzer huet déi Säit warscheinlech scho virun iech restauréiert.",
'undeletedpage'                => "'''$1''' gouf restauréiert.

Am [[Special:Log/delete|Läsch-Logbuch]] fannt Dir déi geläscht a restauréiert Säiten.",
'undelete-header'              => 'Kuckt [[Special:Log/delete|Läschlescht]] fir rezent geläschte Säiten.',
'undelete-search-box'          => 'Sich no geläschte Säiten',
'undelete-search-prefix'       => 'Weis Säiten déi esou ufänken:',
'undelete-search-submit'       => 'Sichen',
'undelete-no-results'          => 'Et goufen am Archiv keng Säite fonnt déi op är Sich passen.',
'undelete-filename-mismatch'   => "D'Dateiversioun vum $1 konnt net restauréiert ginn: De Fichier gouf net fonnt.",
'undelete-bad-store-key'       => "D'Versioun vum Fichier mat dem Zäitstempel $1 konnt net restauréiert ginn: De Fichier war scho virum Läschen net méi do.",
'undelete-cleanup-error'       => 'Feeler beim Läsche vun der onbenotzter Archiv-Versioun $1.',
'undelete-missing-filearchive' => 'De Fichier mat der Archiv-ID $1 kann net restauréiert ginn, well en net an der Datebank ass. Méiglecherweis gouf e scho restauréiert.',
'undelete-error-short'         => 'Feeler beim Restauréiere vum Fichier: $1',
'undelete-error-long'          => 'Beim Restauréiere vun engem Fichier goufe Feeler fonnt:

$1',
'undelete-show-file-confirm'   => '!Sidd Dir sécher, datt dir eng geläschte Versioun vum Fichier „<nowiki>$1</nowiki>“ vum $2 ëm $3 Auer gesi wëllt?',
'undelete-show-file-submit'    => 'Jo',

# Namespace form on various pages
'namespace'      => 'Nummraum:',
'invert'         => 'Auswiel ëmdréinen',
'blanknamespace' => '(Haapt)',

# Contributions
'contributions'       => 'Kontributioune vum Benotzer',
'contributions-title' => 'Kontributioune vum $1',
'mycontris'           => 'Meng Kontributiounen',
'contribsub2'         => 'Fir $1 ($2)',
'nocontribs'          => 'Et goufe keng Ännerunge fonnt, déi dëse Kritèren entspriechen.',
'uctop'               => '(aktuell)',
'month'               => 'Vum Mount (a virdrun):',
'year'                => 'Vum Joer (a virdrun):',

'sp-contributions-newbies'             => 'Nëmme Kontributioune vun neie Mataarbechter weisen',
'sp-contributions-newbies-sub'         => 'Fir déi Nei',
'sp-contributions-newbies-title'       => 'Kontributioune vun neie Benotzer',
'sp-contributions-blocklog'            => 'Spärlescht',
'sp-contributions-deleted'             => 'geläschte Benotzer-Kontributiounen',
'sp-contributions-uploads'             => 'Eropgeluede Fichieren',
'sp-contributions-logs'                => 'Logbicher',
'sp-contributions-talk'                => 'diskutéieren',
'sp-contributions-userrights'          => 'Verwaltung vun de Benotzerrechter',
'sp-contributions-blocked-notice'      => 'Dëse Benotzer ass elo gespaart. Déi lescht Entrée am Läsch-Logbuch steet als Referenz hei ënnendrënner:',
'sp-contributions-blocked-notice-anon' => "Dës IP-Adress ass elo gespaart.
Ënnendrënner steet déi lescht Androung an d'Spärlëscht:",
'sp-contributions-search'              => 'No Kontributioune sichen',
'sp-contributions-username'            => 'IP-Adress oder Benotzernumm:',
'sp-contributions-toponly'             => 'Nëmmen Ännerunge weisen déi déi lescht Versioun sinn',
'sp-contributions-submit'              => 'Sichen',

# What links here
'whatlinkshere'            => 'Linken op dës Säit',
'whatlinkshere-title'      => 'Säiten, déi mat "$1" verlinkt sinn',
'whatlinkshere-page'       => 'Säit:',
'linkshere'                => "Déi folgend Säite linken op '''[[:$1]]''':",
'nolinkshere'              => "Keng Säit ass mat '''[[:$1]]''' verlinkt.",
'nolinkshere-ns'           => "Keng Säite linken op '''[[:$1]]''' am gewielten Nummraum.",
'isredirect'               => 'Viruleedung',
'istemplate'               => 'an dëser Säit dran',
'isimage'                  => 'Link op de Fichier',
'whatlinkshere-prev'       => '{{PLURAL:$1|vireg|vireg $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|nächsten|nächst $1}}',
'whatlinkshere-links'      => '← Linken',
'whatlinkshere-hideredirs' => 'Viruleedungen $1',
'whatlinkshere-hidetrans'  => 'Agebonne Schabloune $1',
'whatlinkshere-hidelinks'  => 'Linken $1',
'whatlinkshere-hideimages' => '$1 Linken op de Fichier',
'whatlinkshere-filters'    => 'Filteren',

# Block/unblock
'blockip'                         => 'Benotzer spären',
'blockip-title'                   => 'Benotzer spären',
'blockip-legend'                  => 'Benotzer spären',
'blockiptext'                     => 'Benotzt dëse Formulaire fir eng spezifesch IP-Adress oder e Benotzernumm ze spären. Dëst soll nëmmen am Fall vu Vandalismus gemaach ginn, en accordance mat den [[{{MediaWiki:Policy-url}}|interne Richlinen]]. Gitt e spezifesche Grond un (zum Beispill Säite wou Vandalismus virgefall ass).',
'ipaddress'                       => 'IP-Adress oder Benotzernamm:',
'ipadressorusername'              => 'IP-Adress oder Benotzernumm:',
'ipbexpiry'                       => 'Gültegkeet:',
'ipbreason'                       => 'Grond:',
'ipbreasonotherlist'              => 'Anere Grond',
'ipbreason-dropdown'              => "*Heefeg Ursaache fir Benotzer ze spären:
**Bewosst falsch Informatiounen an eng oder méi Säite gesat
**Ouni Grond Inhalt vu Säite geläscht
**Spam-Verknëppunge mat externe Säiten
**Topereien an d'Säite gesat
**Beleidegt oder bedréit aner Mataarbechter
**Mëssbrauch vu verschiddene Benotzernimm
**Net akzeptabele Benotzernumm",
'ipbanononly'                     => 'Nëmmen anonym Benotzer spären',
'ipbcreateaccount'                => 'Opmaache vun engem Benotzerkont verhënneren',
'ipbemailban'                     => 'Verhënneren datt de Benotzer E-Maile verschéckt',
'ipbenableautoblock'              => 'Automatesch déi lescht IP-Adress spären déi vun dësem Benotzer benotzt gouf, an all IP-Adresse vun denen dëse Benotzer versicht Ännerunge virzehuelen',
'ipbsubmit'                       => 'Dës IP-Adress resp dëse Benotzer spären',
'ipbother'                        => 'Aner Dauer:',
'ipboptions'                      => '2 Stonnen:2 hours,1 Dag:1 day,3 Deeg:3 days,1 Woch:1 week,2 Wochen:2 weeks,1 Mount:1 month,3 Méint:3 months,6 Méint:6 months,1 Joer:1 year,onbegrenzt:infinite',
'ipbotheroption'                  => 'Aner Dauer',
'ipbotherreason'                  => 'Aneren oder zousätzleche Grond:',
'ipbhidename'                     => 'Benotzernumm op Lëschten a bei Ännerunge verstoppen',
'ipbwatchuser'                    => 'Dësem Benotzer seng Benotzer- an Diskussiouns-Säit iwwerwaachen',
'ipballowusertalk'                => 'Benotzer däerf seng Diskussiounssäiten änneren esouguer wann e gespaart ass',
'ipb-change-block'                => 'De Benotzer mat dese Parameteren nees spären',
'badipaddress'                    => "D'IP-Adress huet dat falscht Format.",
'blockipsuccesssub'               => 'Gouf gespaart',
'blockipsuccesstext'              => "[[Special:Contributions/$1|$1]] gouf gespaart. <br />

Kuckt d'[[Special:IPBlockList|IP Spär-Lëscht]] fir all Spären ze gesin.",
'ipb-edit-dropdown'               => 'Spärgrënn änneren',
'ipb-unblock-addr'                => 'Spär vum $1 ophiewen',
'ipb-unblock'                     => 'Spär vun enger IP-Adress oder engem Benotzer ophiewen',
'ipb-blocklist'                   => 'Kuckt aktuell Spären',
'ipb-blocklist-contribs'          => 'Kontributioune fir $1',
'unblockip'                       => 'Spär vum Benotzer ophiewen',
'unblockiptext'                   => 'Benotzt dëse Formulaire fir enger IP-Adress oder engem Benotzer seng Spär opzehiewen.',
'ipusubmit'                       => 'Des Spär ophiewen',
'unblocked'                       => "D'Spär fir de [[User:$1|Benotzer $1]] gouf opgehuewen",
'unblocked-id'                    => "D'Spär $1 gouf opgehuewen",
'ipblocklist'                     => 'Lëscht vu gespaarten IP-Adressen a Benotzernimm',
'ipblocklist-legend'              => 'No engem gespaarte Benotzer sichen',
'ipblocklist-username'            => 'Benotzernumm oder IP-Adress:',
'ipblocklist-sh-userblocks'       => 'Benotzerspäre $1',
'ipblocklist-sh-tempblocks'       => 'temporär Späre $1',
'ipblocklist-sh-addressblocks'    => 'eenzel IP-Adressen déi gespaart si $1',
'ipblocklist-submit'              => 'Sichen',
'ipblocklist-localblock'          => 'Lokal Spär',
'ipblocklist-otherblocks'         => 'Aner {{PLURAL:$1|Spär|Spären}}',
'blocklistline'                   => '$1, $2 huet de Benotzer:$3 gespaart (gëllt $4)',
'infiniteblock'                   => 'onbegrenzt',
'expiringblock'                   => 'bis den $1 ëm $2',
'anononlyblock'                   => 'nëmmen anonym Benotzer',
'noautoblockblock'                => 'déi automatesch Spär ass deaktivéiert',
'createaccountblock'              => 'Opmaache vu Benotzerkonte gespaart',
'emailblock'                      => 'E-Maile schécke gespaart',
'blocklist-nousertalk'            => 'däerf seng eegen Diskussiounssäit net ännereen',
'ipblocklist-empty'               => "D'Spärlëscht ass eidel.",
'ipblocklist-no-results'          => 'Déi gesichten IP-Adress respektiv de gesichte Benotzer ass net gespaart.',
'blocklink'                       => 'spären',
'unblocklink'                     => 'Spär ophiewen',
'change-blocklink'                => 'Spär änneren',
'contribslink'                    => 'Kontributiounen',
'autoblocker'                     => 'Dir sidd automatesch gespaart well dir eng IP Adress mam "[[User:$1|$1]]" deelt.
De Grond dee fir d\'Spär vum $1 ugi gouf ass: "$2".',
'blocklogpage'                    => 'Spärlëscht',
'blocklog-showlog'                => "Dëse Benotzer war virdru gespaart. D'Lëscht vun de Späre ass als Referenz hei ënnendrënner:",
'blocklog-showsuppresslog'        => "Dëse Benotzer war virdru gespaart a verstoppt. D'Logbuch vun de Suppressiounen steet als Referenz hei ënnendrënner:",
'blocklogentry'                   => '"[[$1]]" gespaart fir $2 $3',
'reblock-logentry'                => "huet d'Spär vum [[$1]] bis den $2 $3 geännert",
'blocklogtext'                    => "Dëst ass eng Lëscht vu Spären a vu Spären déi opgehuewe goufen.
Automatesch gespaarten IP-Adresse sinn hei net opgelëscht.
Kuckt d'[[Special:IPBlockList|IP Spärlëscht]] fir déi aktuell Spären.",
'unblocklogentry'                 => "huet d'Spär vum $1 opgehuewen",
'block-log-flags-anononly'        => 'Nëmmen anonym Benotzer',
'block-log-flags-nocreate'        => 'Schafe vu Benotzerkonte gespaart',
'block-log-flags-noautoblock'     => 'Autoblock deaktivéiert',
'block-log-flags-noemail'         => 'E-Mail gespaart',
'block-log-flags-nousertalk'      => 'däerf seng Diskussiounssäiten net änneren',
'block-log-flags-angry-autoblock' => 'erweidert automatesch Spär aktivéiert',
'block-log-flags-hiddenname'      => 'Benotzernumm verstoppt',
'range_block_disabled'            => 'Dem Administrateur seng Fähegkeet fir ganz Adressberäicher ze spären ass ausser Kraaft.',
'ipb_expiry_invalid'              => "D'Dauer déi Dir uginn hutt ass ongülteg.",
'ipb_expiry_temp'                 => 'Verstoppte Späre vu Benotzernimm solle permanent sinn.',
'ipb_hide_invalid'                => 'Dëse Benotzerkont kann net geläscht ginn; et ka sinn datt zevill Ännerunge vun deem Benotzer gemaach goufen.',
'ipb_already_blocked'             => '"$1" ass scho gespaart.',
'ipb-needreblock'                 => "== Scho gespaart ==
„$1“ ass scho gespaart. Wëllt dir d'Parametere vun der Spär änneren?",
'ipb-otherblocks-header'          => 'Aner  {{PLURAL:$1|Spär|Spären}}',
'ipb_cant_unblock'                => "Feeler: D'Nummer vun der Spär $1 gouf net fonnt. D'Spär gouf waarscheinlech schonn opgehuewen.",
'ipb_blocked_as_range'            => "Feeler: D'IP-Adress $1 gouf net direkt gespaart an déi Spär kann dofir och net opghuewe ginn.
Si ass awer als Deel vun der Rei $2 gespaart, an dës Spär kann opgehuewe ginn.",
'ip_range_invalid'                => 'Ongëltegen IP Block.',
'ip_range_toolarge'               => 'Späre vu Beräicher déi méi grouss wéi /$1 si sinn net erlaabt.',
'blockme'                         => 'Spär mech',
'proxyblocker'                    => 'Proxy blocker',
'proxyblocker-disabled'           => 'Dës Funktioun ass ausgeschalt.',
'proxyblockreason'                => 'Är IP-Adress gouf gespaart, well si een oppene Proxy ass. Kontaktéiert w.e.g. ären Internet-Provider oder ärs Systemadministrateuren und informéiert si iiwwer dëses méigleche Sécherheetsprobleem.',
'proxyblocksuccess'               => 'Gemaach.',
'sorbsreason'                     => 'Är IP Adress steet als oppene Proxy an der schwaarzer Lëscht (DNSBL) déi vu {{SITENAME}} benotzt gëtt.',
'sorbs_create_account_reason'     => 'Är IP-Adress steet als oppene Proxy an der schwaarzer Lëscht déi op {{SITENAME}} benotzt gëtt. DIr kënnt keen neie Benotzerkont opmaachen.',
'cant-block-while-blocked'        => 'Dir däerft keng aner Benotzer spären, esou lang wéi dir selwer gespaart sidd.',
'cant-see-hidden-user'            => "De Benotzer deen Dir versicht ze spären ass scho gespaart a verstoppt. Well Dir d'Recht ''Hideuser'' net hutt kënnt Dir dëse Benotzer net gesinn an dem Benotzer seng Spär net änneren.",
'ipbblocked'                      => 'Dir kënnt keng aner Benotzer spären oder hir Spär ophiewen well Dir selwer gespaart sidd',
'ipbnounblockself'                => 'Dir kënnt Är Spär net selwer ophiewen',

# Developer tools
'lockdb'              => 'Datebank spären',
'unlockdb'            => 'Spär vun der Datebank ophiewen',
'lockdbtext'          => "Wann d'Datebank gespaart ass, ka kee Benotzer Säiten änneren, seng Astellungen änneren, seng Iwwerwaachungslëscht änneren, an all aner Aarbecht, déi op d'Datebank zréckgräift.
W.e.g. confirméiert, datt dir dëst wierklech maache wëllt, an datt dir d'Spär ewechhuelt soubal d'Maintenance-Aarbechten eriwwer sinn.",
'unlockdbtext'        => "D'Ophiewe vun der Spär vun der Datebank léisst et erëm zou datt all Benotzer Säiten änneren, hir Astellungen an hir Iwwerwaachungslëscht veränneren an all aner Operatiounen déi Ännerungen an der Datebank erfuederen.

Confirméiert w.e.g datt et dat ass wat Dir maache wëllt.",
'lockconfirm'         => "Jo, ech wëll d'Datebank wierklech spären.",
'unlockconfirm'       => "Jo, ech well d'Spär vun der Datebank wirklech ophiewen.",
'lockbtn'             => 'Datebank spären',
'unlockbtn'           => 'Spär vun der Datebank ophiewen',
'locknoconfirm'       => "Dir hutt d'Confirmatiounskëscht net ugeklickt.",
'lockdbsuccesssub'    => "D'Datebank ass elo gespaart",
'unlockdbsuccesssub'  => "D'Spär vun der Datebank gouf opgehuewen",
'lockdbsuccesstext'   => "D'{{SITENAME}}-Datebank gouf gespaart. <br />
Denkt drun [[Special:UnlockDB|d'Spär erëm ewechzehuele]] soubaal d'Maintenance-Aarbechte fäerdeg sinn.",
'unlockdbsuccesstext' => "D'Spär vun der Datebank ass opgehuewen.",
'lockfilenotwritable' => "De Fichier mat de Späre vun der Datebank kann net geännert ginn.
Fir d'Datebank ze spären oder fir d'Spär opzehiewen muss dëse Fichier vum Webserver geännert kënne ginn.",
'databasenotlocked'   => "D'Datebank ass net gespaart.",

# Move page
'move-page'                    => 'Réckel $1',
'move-page-legend'             => 'Säit réckelen',
'movepagetext'                 => "Wann dir dëse Formulaire benotzt, réckelt dir eng komplett Säit mat hirem Historique op en neien Numm.
Den alen Titel déngt als Viruleedung op déi nei Säit.
Dir kënnt Viruleedungen déi op déi al Säit ginn automatesch aktualiséieren.
Wann Dir dat net maacht, da vergewëssert iech datt keng [[Special:DoubleRedirects|duebel]] oder [[Special:BrokenRedirects|futtis Viruleedungen]] am Spill sinn.
Dir sidd responsabel datt d'Linke weiderhinn dohinner pointéieren, wou se hi sollen.

Beuecht w.e.g. datt d'Säit '''net''' geréckelt gëtt, wann et schonns eng Säit mat deem Titel gëtt, ausser déi ass eidel, ass eng Viruleedung oder huet keen Historique.
Dëst bedeit datt dir eng Säit ëmbenenne kënnt an datt dir keng Säit iwwerschreiwe kënnt, déi et schonns gëtt.

'''OPGEPASST!'''
Dëst kann en drastesche Changement fir eng populär Säit bedeiten;
verstitt w.e.g. d'Konsequenze vun ärer Handlung éier Dir d'Säit réckelt.",
'movepagetext-noredirectfixer' => "Wann Dir dëse Formulaire benotzt, réckelt dir eng komplett Säit mat hirem Historique op en neien Numm.
Den alen Titel gëtt eng Viruleedung op den neien Titel.
Dir kënnt Viruleedungen déi op déi al Säit ginn automatesch aktualiséieren.
Vergewëssert Iech datt keng [[Special:DoubleRedirects|duebel]] oder [[Special:BrokenRedirects|futtis Viruleedungen]] am Spill sinn.
Dir sidd responsabel datt d'Linke weider dohinner pointéieren, wou se hi sollen.

Denkt w.e.g. drun datt d'Säit '''net''' geréckelt gëtt, wann et schonns eng Säit mat deem Titel gëtt, ausser déi ass eidel, ass eng Viruleedung oder huet keen Historique.
Dëst bedeit datt dir eng Säit zréck op deen Numm dee se virdrun hat ëmbenenne kënnt wann Dir e Feeler maacht an datt Dir keng Säit iwwerschreiwe kënnt, déi et schonns gëtt.

'''OPGEPASST!'''
Dëst kann en drastesche Changement fir eng populär Säit sinn;
verstitt w.e.g. d'Konsequenze vun ärer Handlung éier Dir dëst maacht.",
'movepagetalktext'             => "D'assoziéiert Diskussiounssäit, am Fall wou  eng do ass, gëtt automatesch matgeréckelt, '''ausser:'''
*D'Säit gëtt an een anere Nummraum geréckelt.
*Et gëtt schonn eng Diskussiounssäit mat dësem Numm, oder
*Dir klickt d'Këschtchen ënnendrënner net un.

An deene Fäll musst Dir d'Diskussiounssäit manuell réckelen oder fusionéieren.",
'movearticle'                  => 'Säit réckelen:',
'moveuserpage-warning'         => "'''Opgepasst:''' Dir sidd am gaang eng Benotzersäit ze réckelen. Denkt w.e.g. dorunn datt just d'Säit geréckelt gëtt an datt de Benotzer ''net'' ëmbenannt gëtt.",
'movenologin'                  => 'Net ageloggt',
'movenologintext'              => 'Dir musst e registréierte Benotzer an [[Special:UserLogin|ageloggt]] sinn, fir eng Säit ze réckelen.',
'movenotallowed'               => 'Dir hutt net déi néideg Rechter fir Säiten ze réckelen.',
'movenotallowedfile'           => "Dir hutt net d'Recht fir Fichieren ze réckelen.",
'cant-move-user-page'          => 'Dir hutt net déi néideg Rechter fir Benotzerhaaptsäiten ze réckelen.',
'cant-move-to-user-page'       => "Dir hutt net d'Recht fir eng Säit op eng Benotzersäit (ausser op eng Ënnersäit vun enger Benotzersäit) ze réckelen.",
'newtitle'                     => 'Op den neien Titel:',
'move-watch'                   => 'Dës Säit iwwerwaachen',
'movepagebtn'                  => 'Säit réckelen',
'pagemovedsub'                 => 'Gouf geréckelt',
'movepage-moved'               => "'''D'Säit \"\$1\" gouf op \"\$2\" geréckelt.'''",
'movepage-moved-redirect'      => 'Et gouf eng Viruleedung ugeluecht.',
'movepage-moved-noredirect'    => "D'Uleeë vun enger Viruleedung gouf ënnerdréckt.",
'articleexists'                => 'Eng Säit mat dësem Numm gëtt et schonns, oder den Numm deen Dir gewielt hutt gëtt net akzeptéiert. Wielt w.e.g. en aneren Numm.',
'cantmove-titleprotected'      => "Dir kënnt keng Säit op dës Plaz réckelen, well deen neien Titel fir d'Uleeë gespaart ass.",
'talkexists'                   => "D'Säit selwer gouf erfollegräich geréckelt, mee d'Diskussiounssäit konnt net mat eriwwergeholl gi well et schonns eng ënnert deem neien Titel gëtt. W.e.g. setzt dës manuell zesummen.",
'movedto'                      => 'geréckelt op',
'movetalk'                     => "D'Diskussiounssäit matréckelen, wa méiglech.",
'move-subpages'                => 'Ënnersäite (bis zu $1) réckelen',
'move-talk-subpages'           => 'Ënnersäite vun der Diskussiounssäit (bis zu $1), matréckelen',
'movepage-page-exists'         => "D'Säit $1 gëtt et schonn a kann net automatesch iwwerschriwwe ginn.",
'movepage-page-moved'          => "D'Säit $1 gouf schonn op $2 geréckelt.",
'movepage-page-unmoved'        => "D'Säit $1 konnt nett op $2 geréckelt ginn.",
'movepage-max-pages'           => 'Déi Maximalzuel vu(n) $1 {{PLURAL:$1|Säit gouf|Säite goufe}} gouf geréckelt. All déi aner Säite kënnen net automatesch geréckelt ginn.',
'1movedto2'                    => '[[$1]] gouf op [[$2]] geréckelt',
'1movedto2_redir'              => '[[$1]] gouf op [[$2]] geréckelt, dobäi gouf eng Viruleedung iwwerschriwwen.',
'move-redirect-suppressed'     => 'Viruleedung ewechgehol',
'movelogpage'                  => 'Réckellëscht',
'movelogpagetext'              => 'Dëst ass eng Lëscht vun alle geréckelte Säiten.',
'movesubpage'                  => '{{PLURAL:$1|Ënnersäit|Ënnersäiten}}',
'movesubpagetext'              => 'Dës Säit huet $1 {{PLURAL:$1|Ënnersäit|Ënnersäiten}} déi hei ënnendrënner stinn.',
'movenosubpage'                => 'Dës Säit huet keng Ënnersäiten.',
'movereason'                   => 'Grond:',
'revertmove'                   => 'zréck réckelen',
'delete_and_move'              => 'Läschen a réckelen',
'delete_and_move_text'         => '== Läsche vun der Destinatiounssäit néideg == D\'Säit "[[:$1]]" existéiert schonn. Wëll der se läsche fir d\'Réckelen ze erméiglechen?',
'delete_and_move_confirm'      => "Jo, läsch d'Destinatiounssäit",
'delete_and_move_reason'       => 'Geläscht fir Plaz ze maache fir eng Säit heihin ze réckelen',
'selfmove'                     => 'Source- an Destinatiounsnumm sinn dselwecht; eng Säit kann net op sech selwer geréckelt ginn.',
'immobile-source-namespace'    => 'Säite am Nummraum: $1 kënnen net geréckelt ginn',
'immobile-target-namespace'    => 'Säite kënnen net an den Nummraum: $1 geréckelt ginn',
'immobile-target-namespace-iw' => 'En Interwiki-Link ass kee gëltegt Zil beim Réckele vun enger Säit.',
'immobile-source-page'         => 'Dës Säit kann net geréckelt ginn.',
'immobile-target-page'         => 'Kann net op de Bestëmmungs-titel geréckelt ginn.',
'imagenocrossnamespace'        => 'Fichiere kënnen net an aner Nummraim geréckelt ginn',
'nonfile-cannot-move-to-file'  => '"Keng Fichiere" kënnen net an den {{ns:file}}-Nummraum geréckelt ginn',
'imagetypemismatch'            => 'Déi nei Dateierweiderung ass net mat dem Fichier kompatibel',
'imageinvalidfilename'         => 'Den Numm vum Zil-Fichier ass ongëlteg',
'fix-double-redirects'         => 'All Viruleedungen déi op den Originaltitel weisen aktualiséieren',
'move-leave-redirect'          => 'Viruleedung uleeën',
'protectedpagemovewarning'     => "'''OPGEPASST:''' Dës Säit gouf gespaart esou datt nëmme Benotzer mat Administreurs-Rechter se réckele kënnen. Déi lescht Zeil aus de Logbicher fannt Dir zu Ärer Informatioun hei ënnendrënner.",
'semiprotectedpagemovewarning' => "'''OPGEPASST:''' Dës Säit gouf gespaart esou datt nëmme konfirméiert Benotzer se réckele kënnen. Déi lescht Zeil aus de Logbicher fannt Dir zu Ärer Informatioun hei ënnendrënner.",
'move-over-sharedrepo'         => '== De Fichier gëtt et ==
[[:$1]] gëtt et op engem gedeelte Repertoire. Wann dir e Fichier op dësen Titel réckelt dann ass dee gedeelte Fichier net méi accessibel.',
'file-exists-sharedrepo'       => 'Den Numm vum Fichier deen dir erausgesicht hutt gëtt schonn op engem gemeinsame Repertoire benotzt.
Sicht Iech w.e.g. en aneren Numm.',

# Export
'export'            => 'Säiten exportéieren',
'exporttext'        => "Dir kënnt den Text an den Historique vun enger bestëmmter Säit, oder engem Set vu Säiten, an XML agepakt, exportéieren, déi dann an eng aner Wiki mat MediaWiki Software importéiert gi mat Hëllef vun der [[Special:Import|Import-Säit]].

Fir eng Säit z'exportéieren, gitt den Titel an d'Textkëscht heidrënner an, een Titel pro Linn, a wielt aus op Dir nëmmen déi aktuell Versioun oder all Versioune mam ganzen Historique exportéiere wëllt.

Wann nëmmen déi aktuell Versioun exportéiert soll ginn, kënnt Dir och e Link benotze wéi z.B [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] fir d'\"[[{{MediaWiki:Mainpage}}]]\".",
'exportcuronly'     => 'Nëmmen déi aktuell Versioun exportéieren an net de ganzen Historique',
'exportnohistory'   => "----
'''Hiwäis:''' Den Export vu komplette Versiounshistoriquen ass aus Performancegrënn bis op weideres net méiglech.",
'export-submit'     => 'Exportéieren',
'export-addcattext' => 'Säiten aus Kategorie derbäisetzen:',
'export-addcat'     => 'Derbäisetzen',
'export-addnstext'  => 'Säiten aus Nummraum derbäisetzen:',
'export-addns'      => 'Derbäisetzen',
'export-download'   => 'Als XML-Datei späicheren',
'export-templates'  => 'Inklusiv Schablounen',
'export-pagelinks'  => 'Verlinkte Säiten mat exportéieren, bis zu enger Déift vun:',

# Namespace 8 related
'allmessages'                   => 'All Systemmessagen',
'allmessagesname'               => 'Numm',
'allmessagesdefault'            => 'Standardtext',
'allmessagescurrent'            => 'Aktuellen Text',
'allmessagestext'               => "Dëst ass eng Lëscht vun alle '''Messagen am MediaWiki:Nummraum, déi vun der MediaWiki-Software benotzt ginn.
Besicht w.e.g. [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisatioun] an [http://translatewiki.net translatewiki.net] wann Dir wëllt bei de MediaWiki Iwwersetzunge matschaffen.",
'allmessagesnotsupportedDB'     => "Dës Säit kann net benotzt gi well '''\$wgUseDatabaseMessages''' ausgeschalt ass.",
'allmessages-filter-legend'     => 'Filter',
'allmessages-filter'            => 'Filter nom ugepassten Zoustand:',
'allmessages-filter-unmodified' => 'Net geännert',
'allmessages-filter-all'        => 'Alleguer',
'allmessages-filter-modified'   => 'Geännert',
'allmessages-prefix'            => 'Nom Prefix filteren:',
'allmessages-language'          => 'Sprooch:',
'allmessages-filter-submit'     => 'Lass',

# Thumbnails
'thumbnail-more'           => 'vergréisseren',
'filemissing'              => 'Fichier feelt',
'thumbnail_error'          => 'Feeler beim Erstelle vun der Miniatur: $1',
'djvu_page_error'          => 'DjVu-Säit baussent dem Säiteberäich',
'djvu_no_xml'              => 'Den XML ka fir den DjVu-Fichier net ofgeruff ginn',
'thumbnail_invalid_params' => 'Ongëlteg Miniatur-Parameter',
'thumbnail_dest_directory' => 'Den Zilepertoire konnt net ugeluecht ginn.',
'thumbnail_image-type'     => 'Bildtyp gëtt net ënnerstëtzt',
'thumbnail_gd-library'     => "D'Konfiguratioun vun der GD-Bibliothéik (GD library) ass net komplett: D'Fonctioun $1 feelt",
'thumbnail_image-missing'  => 'De Fichier schengt ze feelen: $1',

# Special:Import
'import'                     => 'Säiten importéieren',
'importinterwiki'            => 'Transwiki-Import',
'import-interwiki-text'      => "Sicht eng Wiki an e Säitentitel eraus fir z'importéieren.
D'Versiounsdatumen an d'Benotzernimm bleiwen dobäi erhalen.
All Transwiki-Import-Aktioune ginn am [[Special:Log/import|Import-Logbuch]] protokolléiert.",
'import-interwiki-source'    => 'Quelle Wiki/Säit:',
'import-interwiki-history'   => "Importéier all d'Versioune vun dëser Säit",
'import-interwiki-templates' => 'Mat alle Schablounen',
'import-interwiki-submit'    => 'Import',
'import-interwiki-namespace' => 'Zil-Nummraum:',
'import-upload-filename'     => 'Numm vum Fichier:',
'import-comment'             => 'Bemierkung:',
'importtext'                 => 'Exportéiert de Fichier w.e.g vun der Source-Wiki mat der [[Special:Export|Funktioun Export]].
Späichert en op Ärem Computer of a luet en hei nees erop.',
'importstart'                => 'Importéier Säiten …',
'import-revision-count'      => '$1 {{PLURAL:$1|Versioun|Versiounen}}',
'importnopages'              => "Et gëtt keng Säiten fir z'importéieren.",
'imported-log-entries'       => "$1 {{PLURAL:$1|Entrée|Entréeën}} an d'Logbuch importéiert.",
'importfailed'               => 'Importatioun huet net fonctionnéiert: $1',
'importunknownsource'        => 'Onbekannt Importquell',
'importcantopen'             => 'De Fichier dee sollt importéiert gi konnt net opgemaach ginn',
'importbadinterwiki'         => 'Falschen Interwiki-Link',
'importnotext'               => 'Eidel oder keen Text',
'importsuccess'              => 'Den Import ass fäerdeg!',
'importhistoryconflict'      => 'Et gëtt Konflikter am Historique vun de Versiounen, (méiglecherweis gouf dës Säit virdrun importéiert).',
'importnosources'            => 'Fir den Transwiki-Import si keng Quellen definéiert an et ass net méiglech fir Säite mat alle Versiounen aus dem Transwiki-Tëschespäicher eropzelueden.',
'importnofile'               => 'Et gouf keen importéierte Fichier eropgelueden',
'importuploaderrorsize'      => "D'Eropluede vum importéierte Fichier huet net fonctionnéiert. De Fichier ass méi grouss wéi maximal erlaabt.",
'importuploaderrorpartial'   => "D'Eropluede vum Fichier huet net geklappt. De Fichier gouf nëmmen deelweis eropgelueden.",
'importuploaderrortemp'      => "D'Eropluede vum Fichier huet net fonctionnéiert. En temporäre Repertoire feelt.",
'import-parse-failure'       => 'Feeler bei engem XML-Import',
'import-noarticle'           => "Keng Säit fir z'importéieren!",
'import-nonewrevisions'      => "All d'Versioune goufe scho virdrunn importéiert.",
'xml-error-string'           => '$1 an der Zeil $2, Spalt $3, (Byte $4): $5',
'import-upload'              => 'XML-Daten importéieren',
'import-token-mismatch'      => "D'Date vun ärer Sessioun si verluer gaang. Versicht et w.e.g. nach eemol.",
'import-invalid-interwiki'   => 'Aus der Wiki déi Dir uginn hutt kann näischt importéiert ginn.',

# Import log
'importlogpage'                    => 'Lëscht vun den Säitenimporten',
'importlogpagetext'                => 'Administrativen Import vu Säite mam Historique vun den Ännerungen aus anere Wikien.',
'import-logentry-upload'           => 'huet [[$1]] vun engem Fichier duerch Eroplueden importéiert',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|Versioun|Versiounen}}',
'import-logentry-interwiki'        => 'huet $1 importéiert (Transwiki)',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|Versioun|Versioune}} vum $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Är Benotzersäit',
'tooltip-pt-anonuserpage'         => 'Benotzersäit vun der IP-Adress vun där aus Dir den Ament Ännerunge maachtt',
'tooltip-pt-mytalk'               => 'Är Diskussiounssäit',
'tooltip-pt-anontalk'             => "Diskussioun iwwer d'Ännerungen déi vun dëser IP-Adress aus gemaach gi sinn",
'tooltip-pt-preferences'          => 'Meng Astellungen',
'tooltip-pt-watchlist'            => 'Lëscht vu Säiten, bei deenen Der op Ännerungen oppasst',
'tooltip-pt-mycontris'            => 'Lëscht vun äre Kontributiounen',
'tooltip-pt-login'                => 'Sech umelle gëtt gäre gesinn, Dir musst et awer net maachen.',
'tooltip-pt-anonlogin'            => 'Et wier gutt, Dir géift Iech aloggen, och wann et keng Musse-Saach ass.',
'tooltip-pt-logout'               => 'Ofmellen',
'tooltip-ca-talk'                 => 'Diskussioun iwwer de Säiteninhalt',
'tooltip-ca-edit'                 => 'Dës Säit ka geännert ginn. Maacht vun der Méiglechkeet Gebrauch fir ze "kucken ouni ofzespäicheren" a kuckt ob alles an der Rei ass ier der ofspäichert.',
'tooltip-ca-addsection'           => 'En neien Abschnitt ufänken.',
'tooltip-ca-viewsource'           => 'Dës Säit ass gespaart. Nëmmen de Quelltext ka gewise ginn.',
'tooltip-ca-history'              => 'Vireg Versioune vun dëser Säit',
'tooltip-ca-protect'              => 'Dës Säit spären',
'tooltip-ca-unprotect'            => "D'Spär vun dëser Säit ophiewen",
'tooltip-ca-delete'               => 'Dës Säit läschen',
'tooltip-ca-undelete'             => 'Dës Säit restauréieren',
'tooltip-ca-move'                 => 'Dës Säit réckelen',
'tooltip-ca-watch'                => 'Dës Säit op är Iwwerwaachungslëscht bäisetzen',
'tooltip-ca-unwatch'              => 'Dës Säit vun der Iwwerwaachungslëscht erofhuelen',
'tooltip-search'                  => 'Op {{SITENAME}} sichen',
'tooltip-search-go'               => 'Direkt op genee déi Säit goen, wann et se gëtt.',
'tooltip-search-fulltext'         => 'No Säite sichen, an deenen dësen Text dran ass',
'tooltip-p-logo'                  => "Besicht d'Haaptsäit",
'tooltip-n-mainpage'              => "Besicht d'Haaptsäit",
'tooltip-n-mainpage-description'  => "Besicht d'Haaptsäit",
'tooltip-n-portal'                => 'Iwwer de Portal, wat Dir maache kënnt, wou wat ze fannen ass',
'tooltip-n-currentevents'         => "D'Aktualitéit a wat derhannert ass",
'tooltip-n-recentchanges'         => 'Lëscht vun de rezenten Ännerungen op {{SITENAME}}.',
'tooltip-n-randompage'            => 'Zoufälleg Säit',
'tooltip-n-help'                  => 'Hëllefsäiten weisen.',
'tooltip-t-whatlinkshere'         => 'Lëscht vun alle Säiten, déi heihi linken',
'tooltip-t-recentchangeslinked'   => 'Rezent Ännerungen op Säiten, déi von hei verlinkt sinn',
'tooltip-feed-rss'                => 'RSS-Feed fir dës Säit',
'tooltip-feed-atom'               => 'Atom-Feed fir dës Säit',
'tooltip-t-contributions'         => 'Lëscht vun de Kontributioune vun dësem Benotzer',
'tooltip-t-emailuser'             => 'Dësem Benotzer eng E-Mail schécken',
'tooltip-t-upload'                => 'Biller oder Mediefichieren eroplueden',
'tooltip-t-specialpages'          => 'Lëscht vun alle Spezialsäiten',
'tooltip-t-print'                 => 'Versioun vun dëser Säit fir auszedrécken',
'tooltip-t-permalink'             => 'Permanente Link op dës Versioun vun dëser Säit',
'tooltip-ca-nstab-main'           => 'Contenu vun der Säit weisen',
'tooltip-ca-nstab-user'           => 'Benotzersäit weisen',
'tooltip-ca-nstab-media'          => 'Mediesäit weisen',
'tooltip-ca-nstab-special'        => 'Dëst ass eng Spezialsäit. Si kann net geännert ginn.',
'tooltip-ca-nstab-project'        => 'Portalsäit weisen',
'tooltip-ca-nstab-image'          => 'Billersäit weisen',
'tooltip-ca-nstab-mediawiki'      => 'Systemmessage weisen',
'tooltip-ca-nstab-template'       => 'Schabloun weisen',
'tooltip-ca-nstab-help'           => 'Hëllefssäite weisen',
'tooltip-ca-nstab-category'       => 'Kategoriesäit weisen',
'tooltip-minoredit'               => 'Dës Ännerung als kleng markéieren.',
'tooltip-save'                    => 'Ännerunge späicheren',
'tooltip-preview'                 => 'Kuckt är Ännerungen ouni ofzespäicheren, Benotzt dëst w.e.g. virum späicheren!',
'tooltip-diff'                    => 'Weist wéi eng Ännerungen Dir beim Text gemaach hutt.',
'tooltip-compareselectedversions' => "D'Ënnerscheeder op dëser Säit tëscht den zwou gewielte Versioune weisen.",
'tooltip-watch'                   => 'Dës Säit op Är Iwwerwaachungslëscht bäisetzen',
'tooltip-recreate'                => "D'Säit nees uleeën, obwuel se geläscht gi war.",
'tooltip-upload'                  => 'Mam eroplueden ufänken',
'tooltip-rollback'                => '"Zrécksetzen" mécht all Ännerunge vum leschten "Auteur" an engem Klick nees réckgängeg.',
'tooltip-undo'                    => '"Zréck" setzt dës Ännerung zréck a mécht den Ännerungsformulaire am Modus "kucken ouni ofzespäicheren" op.
Erlaabt et e Grond an de Resumé derbäizesetzen.',
'tooltip-preferences-save'        => 'Astellunge späicheren',
'tooltip-summary'                 => 'Gitt e kuerze Resumé an',

# Stylesheets
'common.css'      => "/* Dës CSS huet nëmmen Afloss op de Skin ''Chick''  */",
'standard.css'    => "/* Dës CSS huet nëmmen Afloss op de Skin ''Klassesch''  */",
'nostalgia.css'   => "/* Dës CSS huet nëmmen Afloss op de Skin ''Nostalgie''  */",
'cologneblue.css' => "/* Dës CSS huet nëmmen Afloss op de Skin ''Köln Blo''  */",
'monobook.css'    => "/* Dës CSS huet nëmmen Afloss op de Skin ''Monobook''  */",
'myskin.css'      => "/* Dës CSS huet nëmmen Afloss op de Skin ''MySkin''  */",
'chick.css'       => '/* Dës CSS huet nëmmen Afloss op de Skin "Chick" */',
'simple.css'      => "/* Dës CSS huet nëmmen Afloss op de Skin ''Einfach''  */",
'modern.css'      => "/* Dës CSS huet nëmmen Afloss op de Skin ''Modern''  */",

# Scripts
'common.js' => '/* All JavaScript hei gëtt fir all Benotzer beim Luede vun all Säit gelueden. */',

# Metadata
'nodublincore'      => 'Dublin Core RDF Metadata ass op dësem Server ausgeschalt.',
'nocreativecommons' => 'Creative Commons RDF Metadata ass op dësem Server ausgeschalt.',
'notacceptable'     => "De Wiki-Server kann d'Donnéeë net an engem Format liwweren déi vun ärem Apparat geliest kënne ginn.",

# Attribution
'anonymous'        => '{{PLURAL:$1|Anonyme Benotzer|Anonym Benotzer}} op {{SITENAME}}',
'siteuser'         => '{{SITENAME}}-Benotzer $1',
'anonuser'         => 'Anonyme(n) {{SITENAME}}-Benotzer $1',
'lastmodifiedatby' => "Dës Säit gouf den $1 ëm $2 Auer vum $3 fir d'lescht geännert.",
'othercontribs'    => 'Op der Basis vun der Aarbecht vum $1',
'others'           => 'anerer',
'siteusers'        => '{{SITENAME}} {{PLURAL:$2|Benotzer|Benotzer}} $1',
'anonusers'        => '{{PLURAL:$2|Anonyme(n)|Anonym}} {{SITENAME}}-Benotzer $1',
'creditspage'      => 'Quellen',
'nocredits'        => "Fir dës Säit si keng Informatiounen iwwert d'Mataarbechter vun der Säit disponibel.",

# Spam protection
'spamprotectiontitle' => 'Spamfilter',
'spamprotectiontext'  => "D'Säit déi dir späichere wollt gouf vum Spamfilter gespaart.
Dëst warscheinlech duerch en externe Link den op der schwaarzer Lëscht (blacklist) vun den externe Säite steet.",
'spamprotectionmatch' => "'''Dësen Text gouf vum Spamfilter fonnt: ''$1'''''",
'spambot_username'    => 'Botz vum Spam duerch MediaWiki',
'spam_reverting'      => 'Déi lescht Versioun ouni Linken op $1 restauréieren.',
'spam_blanking'       => 'An alle Versioune ware Linken op $1, et ass elo alles gebotzt.',

# Info page
'infosubtitle'   => 'Informatioun zur Säit',
'numedits'       => 'Zuel vun den Ännerunge vun dëser Säit: $1',
'numtalkedits'   => 'Zuel vun den Ännerungen (Diskussiounssäit): $1',
'numwatchers'    => 'Zuel vun de Benotzer déi dës Säit iwwerwaachen: $1',
'numauthors'     => 'Zuel vu verschiddenen Auteuren: $1',
'numtalkauthors' => 'Zuel vun den Auteuren (Diskussiounssäit): $1',

# Skin names
'skinname-standard'    => 'Klassesch',
'skinname-nostalgia'   => 'Nostalgie',
'skinname-cologneblue' => 'Köln Blo',
'skinname-monobook'    => 'MonoBook',
'skinname-myskin'      => 'MySkin',
'skinname-chick'       => 'Chick',
'skinname-simple'      => 'Einfach',
'skinname-modern'      => 'Modern',

# Math options
'mw_math_png'    => 'Ëmmer als PNG duerstellen',
'mw_math_simple' => 'Einfachen TeX als HTML duerstellen, soss PNG',
'mw_math_html'   => 'Wa méiglech als HTML duerstellen, soss PNG',
'mw_math_source' => 'Als TeX loossen (fir Textbrowser)',
'mw_math_modern' => 'Recommandéiert fir modern Browser',
'mw_math_mathml' => 'MathML wa méiglech (experimentell)',

# Math errors
'math_failure'          => 'Parser-Feeler',
'math_unknown_error'    => 'Onbekannte Feeler',
'math_unknown_function' => 'Onbekannte Funktioun',
'math_lexing_error'     => "'Lexing'-Feeler",
'math_syntax_error'     => 'Syntaxfeeler',
'math_image_error'      => "D'PNG-Konvertéierung huet net fonctionnéiert;
iwwerpréift déi korrekt Installatioun vu LaTeX an dvipng (oder dvips + gs + convert)",
'math_bad_tmpdir'       => 'Den temporäre Repertire fir mathematesch Formele kann net ugeluecht ginn oder et kann näischt do gespäichert ginn.',
'math_bad_output'       => 'Den Zilrepertoire fir mathematesch Formele kann net ugeluecht ginn oder et kann näischt do gespäichert ginn.',

# Patrolling
'markaspatrolleddiff'                 => 'Als kontrolléiert markéieren',
'markaspatrolledtext'                 => 'Dës Säit als kontrolléiert markéieren',
'markedaspatrolled'                   => 'ass als kontrolléiert markéiert',
'markedaspatrolledtext'               => 'Déi gewielte Versioun vu(n) [[:$1]] gouf als kontrolléiert markéiert.',
'rcpatroldisabled'                    => 'Rezent Ännerungskontroll ausgeschalt.',
'rcpatroldisabledtext'                => "D'Kontroll vun de leschten Ännerungen ass elo ausgeschalt.",
'markedaspatrollederror'              => 'Kann net als "kontrolléiert" markéiert ginn.',
'markedaspatrollederrortext'          => 'Dir musst eng Säitenännerung auswielen.',
'markedaspatrollederror-noautopatrol' => 'Dir däerft Är eegen Ännerungen net als iwwerkuckt markéieren.',

# Patrol log
'patrol-log-page'      => 'Logbuch vun den iwwerkuckte Versiounen',
'patrol-log-header'    => "Dëst ass d'Logbuch vun den nogekuckte Versiounen.",
'patrol-log-line'      => 'huet d\'$1 vu(n) "$2" als iwwerkuckt markéiert $3',
'patrol-log-auto'      => '(automatesch)',
'patrol-log-diff'      => 'Versioun $1',
'log-show-hide-patrol' => 'Kontroll-Logbuch $1',

# Image deletion
'deletedrevision'                 => 'Al, geläschte Versioun $1',
'filedeleteerror-short'           => 'Feeler beim Läsche vum Fichier: $1',
'filedeleteerror-long'            => 'Bäim Läsche vum Fichier si Feeler festgestallt ginn:

$1',
'filedelete-missing'              => 'De Fichier "$1" kann net geläscht ginn, well et en net gëtt.',
'filedelete-old-unregistered'     => 'Déi Versioun vum Fichier déi Dir uginn hutt "$1" gëtt et an der Datebank net.',
'filedelete-current-unregistered' => 'De Fichier "$1" ass net an der Datebank.',
'filedelete-archive-read-only'    => 'Op den Archiv-Repertoire „$1“ ka vum Webserver aus näischt geschriwwe ginn.',

# Browsing diffs
'previousdiff' => '← Méi al Ännerung',
'nextdiff'     => 'Méi nei Ännerung →',

# Media information
'mediawarning'         => "'''Warnung:''' Dës Zort vu Fichier kann e béiswëllege Programmcode enthalen.
Duerch d'Opmaache vum Fichier kann Äre System beschiedegt ginn.",
'imagemaxsize'         => "Maximal Gréisst fir Biller:<br />''(fir Billerbeschreiwungssäiten)''",
'thumbsize'            => 'Gréisst vun der Miniatur:',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|Säit|Säiten}}',
'file-info'            => 'Dateigréisst: $1, MIME-Typ: $2',
'file-info-size'       => '$1 × $2 Pixel, Dateigréisst: $3, MIME-Typ: $4',
'file-nohires'         => '<small>Et gëtt keng méi héich Opléisung.</small>',
'svg-long-desc'        => 'SVG-Fichier, Basisgréisst: $1 × $2 Pixel, Gréisst vum Fichier: $3',
'show-big-image'       => 'Versioun an enger méi héijer Opléisung',
'show-big-image-thumb' => '<small>Gréisst vun der Miniatur: $1 × $2 Pixel</small>',
'file-info-gif-looped' => 'Endloosschleef',
'file-info-gif-frames' => '$1 {{PLURAL:$1|Bild|Biller}}',
'file-info-png-looped' => 'Endlossschleef',
'file-info-png-repeat' => 'gouf $1 {{PLURAL:$1|mol|mol}} gespillt',
'file-info-png-frames' => '$1 {{PLURAL:$1|Frame|Framen}}',

# Special:NewFiles
'newimages'             => 'Gallerie vun den neie Biller',
'imagelisttext'         => "Hei ass eng Lëscht vu(n) '''$1''' {{PLURAL:$1|Fichier|Fichieren}}, zortéiert $2.",
'newimages-summary'     => 'Dës Spezialsäit weist eng Lëscht mat de Biller a Fichieren déi als läscht eropgeluede goufen.',
'newimages-legend'      => 'Filter',
'newimages-label'       => 'Numm vum Fichier (oder en Deel dovun):',
'showhidebots'          => '($1 Botten)',
'noimages'              => 'Keng Biller fonnt.',
'ilsubmit'              => 'Sichen',
'bydate'                => 'no Datum',
'sp-newimages-showfrom' => 'Nei Biller weisen, ugefaang den $1 ëm $2',

# Bad image list
'bad_image_list' => 'Format:

Nëmmen Zeilen, déi mat engem * ufänken, ginn ausgewäert. Als éischt no dem * muss ee Link op een net gewënscht Bild stoen.
Duerno sti Linken déi Ausnamen definéieren, an deenen hirem Kontext dat Bild awer opdauchen däerf.',

# Metadata
'metadata'          => 'Metadaten',
'metadata-help'     => 'An dësem Fichier si weider Informatiounen, déi normalerweis vun der Digitalkamera oder dem benotzte Scanner kommen. Wann de Fichier nodréiglech geännert gouf, kann et sinn datt eenzel Detailer net mat dem aktuelle Fichier iwwereneestëmmen.',
'metadata-expand'   => 'Weis detailléiert Informatiounen',
'metadata-collapse' => 'Verstopp detailléiert Informatiounen',
'metadata-fields'   => "Dës Felder vun den EXIF-Metadate ginn op Bildbeschreiwungssäite gewise wann d'Metadatentafel zesummegeklappt ass. Déi aner sinn am Standard verstoppt, kënne awer ugewise ginn.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength",

# EXIF tags
'exif-imagewidth'                  => 'Breet',
'exif-imagelength'                 => 'Längt',
'exif-bitspersample'               => 'Bite pro Faarfkomponent',
'exif-compression'                 => 'Aart vun der Kompressioun',
'exif-photometricinterpretation'   => 'Pixelzesummesetzung',
'exif-orientation'                 => 'Kameraausriichtung',
'exif-samplesperpixel'             => 'Zuel vun de Komponenten',
'exif-planarconfiguration'         => 'Datenausriichtung',
'exif-ycbcrsubsampling'            => 'Subsampling-Taux vun Y bis C',
'exif-ycbcrpositioning'            => 'Y an C Positionéierung',
'exif-xresolution'                 => 'Horizontal Opléisung',
'exif-yresolution'                 => 'Vertikal Opléisung',
'exif-resolutionunit'              => 'Moosseenheet vun der Opléisung',
'exif-stripoffsets'                => 'Plaz wou de Fichier vum Bild gespäichert ass',
'exif-rowsperstrip'                => 'Zuel vun den Zeile pro Strëpp',
'exif-stripbytecounts'             => 'Byte pro kompriméiert Strëpp',
'exif-jpeginterchangeformat'       => 'Offset zou JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Gréisst vun de JPEG-Daten a Byten',
'exif-transferfunction'            => 'Transferfunktioun',
'exif-whitepoint'                  => 'Manuell mat Miessung',
'exif-primarychromaticities'       => 'Faarwe vun de primäre Faarwen',
'exif-ycbcrcoefficients'           => 'YCbCr-Koeffizienten',
'exif-referenceblackwhite'         => 'Schwaarz/Wäiss-Referenzpunkten',
'exif-datetime'                    => 'Späicherzäitpunkt',
'exif-imagedescription'            => 'Numm vum Bild',
'exif-make'                        => 'Fabrikant',
'exif-model'                       => 'Modell',
'exif-software'                    => 'Benotzte Software',
'exif-artist'                      => 'Auteur',
'exif-copyright'                   => "Droits d'auteur",
'exif-exifversion'                 => 'Exif-Versioun',
'exif-flashpixversion'             => 'Ënnerstëtzte Flashpix-Versioun',
'exif-colorspace'                  => 'Faarfraum',
'exif-componentsconfiguration'     => 'Bedeitung vun eenzelne Komponenten',
'exif-compressedbitsperpixel'      => 'Kompriméiert Bite pro Pixel',
'exif-pixelydimension'             => 'Gültëg Bildbreet',
'exif-pixelxdimension'             => 'Gültëg Bildhéicht',
'exif-makernote'                   => 'Notize vum Fabrikant',
'exif-usercomment'                 => 'Bemierkunge vum Benotzer',
'exif-relatedsoundfile'            => 'Tounfichier deen dozou gehéiert',
'exif-datetimeoriginal'            => 'Erfaassungszäitpunkt',
'exif-datetimedigitized'           => 'Digitaliséierungszäitpunkt',
'exif-subsectime'                  => 'Späicherzäitpunkt (1/100 s)',
'exif-subsectimeoriginal'          => 'Erfaassungszäitpunkt (1/100 s)',
'exif-subsectimedigitized'         => 'Digitaliséirungszäitpunkt (1/100 s)',
'exif-exposuretime'                => 'Beliichtungsdauer',
'exif-exposuretime-format'         => '$1 Sekonnen ($2)',
'exif-fnumber'                     => 'Blend',
'exif-exposureprogram'             => 'Beliichtungsprogramm',
'exif-spectralsensitivity'         => 'Spectral Sensitivitéit',
'exif-isospeedratings'             => 'Film- oder Sensorempfindlechkeet (ISO)',
'exif-oecf'                        => 'Optoelektroneschen Ëmrechnungsfakteur',
'exif-shutterspeedvalue'           => 'Beliichtungszäitwäert',
'exif-aperturevalue'               => 'Blendewäert',
'exif-brightnessvalue'             => 'Hellegkeetswäert',
'exif-exposurebiasvalue'           => 'Beliichtungsvirgab',
'exif-maxaperturevalue'            => 'Gréisste Blend',
'exif-subjectdistance'             => 'Distanz zum Sujet',
'exif-meteringmode'                => 'Miessmethod',
'exif-lightsource'                 => 'Liichtquell',
'exif-flash'                       => 'Blëtz',
'exif-focallength'                 => 'Brennwäit',
'exif-subjectarea'                 => 'Beräich',
'exif-flashenergy'                 => 'Blëtzstäerkt',
'exif-spatialfrequencyresponse'    => 'Spatial-Frequenz',
'exif-focalplanexresolution'       => 'Sensoropléisung horizontal',
'exif-focalplaneyresolution'       => 'Sensoropléisung vertikal',
'exif-focalplaneresolutionunit'    => 'Eenheet vun der Sensoropléisung',
'exif-subjectlocation'             => 'Motivstanduert',
'exif-exposureindex'               => 'Beliichtungsindex',
'exif-sensingmethod'               => 'Miessmethod',
'exif-filesource'                  => 'Quell vum Fichier',
'exif-scenetype'                   => 'Typ vun der Szeen',
'exif-cfapattern'                  => 'CFA-Muster',
'exif-customrendered'              => 'Benotzerdefinéiert Bildveraarbechtung',
'exif-exposuremode'                => 'Beliichtungsmodus',
'exif-whitebalance'                => 'Wäissofgläich',
'exif-digitalzoomratio'            => 'Digitalzoom',
'exif-focallengthin35mmfilm'       => 'Brennwäit (Klengbildäquivalent)',
'exif-scenecapturetype'            => 'Aart vun der Opnam',
'exif-gaincontrol'                 => 'Verstäerkung',
'exif-contrast'                    => 'Kontrast',
'exif-saturation'                  => 'Saturatioun',
'exif-sharpness'                   => 'Schäerft',
'exif-devicesettingdescription'    => 'Beschreiwung vun den Astellunge vum Apparat',
'exif-subjectdistancerange'        => 'Motivdistanz',
'exif-imageuniqueid'               => 'Bild-ID',
'exif-gpsversionid'                => 'Versioun vum GPS-Tag',
'exif-gpslatituderef'              => 'nördlech oder südlech Breet',
'exif-gpslatitude'                 => 'Geografesch Breet',
'exif-gpslongituderef'             => 'östlech oder westlech geografesch Längt',
'exif-gpslongitude'                => 'Geografesch Längt',
'exif-gpsaltituderef'              => 'Referenzhéicht',
'exif-gpsaltitude'                 => 'Héicht',
'exif-gpstimestamp'                => 'GPS-Zäit',
'exif-gpssatellites'               => "Satelitten déi fir d'Moosse benotzt goufen",
'exif-gpsstatus'                   => 'Status vum Empfänger',
'exif-gpsmeasuremode'              => 'Moossmethod',
'exif-gpsdop'                      => 'Prezisioun vun der Miessung',
'exif-gpsspeedref'                 => 'Eenheet vun der Vitesse',
'exif-gpsspeed'                    => 'Vitesse vum GPS-Empfänger',
'exif-gpstrackref'                 => "Referenz fir d'Bewegungsrichtung",
'exif-gpstrack'                    => 'Bewegungsrichtung',
'exif-gpsimgdirectionref'          => "Referenz fir d'Ausriichtung vum Bild",
'exif-gpsimgdirection'             => 'Bildrichtung',
'exif-gpsmapdatum'                 => 'Geodäteschen Datum benotzt',
'exif-gpsdestlatituderef'          => "Referenz fir d'Breet",
'exif-gpsdestlatitude'             => 'Breet',
'exif-gpsdestlongituderef'         => "Referenz fir d'Längt",
'exif-gpsdestlongitude'            => 'Längt',
'exif-gpsdestbearingref'           => "Referenz fir d'Motivrichtung",
'exif-gpsdestbearing'              => 'Richtung vum Motiv',
'exif-gpsdestdistanceref'          => "Referenz fir d'Distanz bis bäi den Objet (vun der Foto)",
'exif-gpsdestdistance'             => 'Motivdistanz',
'exif-gpsprocessingmethod'         => 'Numm vun der GPS-Prozedur-Method',
'exif-gpsareainformation'          => 'Numm vun der GPS-Géigend',
'exif-gpsdatestamp'                => 'GPS-Datum',
'exif-gpsdifferential'             => 'GPS-Differentialverbesserung',

# EXIF attributes
'exif-compression-1' => 'Onkompriméiert',

'exif-unknowndate' => 'Onbekannten Datum',

'exif-orientation-1' => 'Normal',
'exif-orientation-2' => 'Horizontal gedréit',
'exif-orientation-3' => 'Ëm 180° gedréit',
'exif-orientation-4' => 'Vertikal gedréit',
'exif-orientation-5' => "90° géint d'Richtung vun den Zäre vun der Auer gedréint a vertikal ëmgedréint",
'exif-orientation-6' => "Ëm 90° an d'Richtung vun den Zäre vun der Auer gedréint",
'exif-orientation-7' => '90° an der Richtung vun den Zäre vun der Auer gedréint a vertikal ëmgedréint',
'exif-orientation-8' => "Ëm 90° géint d'Richtung vun den Zäre vun der Auer gedréint",

'exif-componentsconfiguration-0' => 'Gëtt et net',

'exif-exposureprogram-0' => 'Onbekannt',
'exif-exposureprogram-1' => 'Manuell',
'exif-exposureprogram-2' => 'Standardprogramm',
'exif-exposureprogram-3' => 'Zäitautomatik',
'exif-exposureprogram-4' => 'Blendenautomatik',
'exif-exposureprogram-5' => 'Kreative Programm (optiméiert fir Déifteschärft)',
'exif-exposureprogram-6' => 'Action-Programm (optiméiert fir kuerz Beliichtungszäiten)',
'exif-exposureprogram-7' => 'Portrait-Programm (fir Fotoe vun no mat engem net-scharfen Hannergrond)',
'exif-exposureprogram-8' => 'Landschaftsopnamen',

'exif-subjectdistance-value' => '$1 Meter',

'exif-meteringmode-0'   => 'Onbekannt',
'exif-meteringmode-1'   => 'Duerchschnëttlech',
'exif-meteringmode-2'   => 'An der Mëtt zentréiert',
'exif-meteringmode-3'   => 'Spotmiessung',
'exif-meteringmode-4'   => 'Méifachspotmiessung',
'exif-meteringmode-5'   => 'Modell',
'exif-meteringmode-6'   => 'Bilddeel',
'exif-meteringmode-255' => 'Onbekannt',

'exif-lightsource-0'   => 'Onbekannt',
'exif-lightsource-1'   => 'Dageslut',
'exif-lightsource-2'   => 'Fluoreszéierend',
'exif-lightsource-3'   => 'Tungsten (Liicht wéi vun enger elektrescher Bir)',
'exif-lightsource-4'   => 'Blëtz',
'exif-lightsource-9'   => 'Schéint Wieder',
'exif-lightsource-10'  => 'Wollekeg',
'exif-lightsource-11'  => 'Schiet',
'exif-lightsource-12'  => 'Dagesliicht fluoreszéiert (D 5700 – 7100 K)',
'exif-lightsource-13'  => 'Dageswäiss fluoreszéierend (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Kill wäiss fluoreséierent (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Wäiss fluoreszéierent (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Standardluucht A',
'exif-lightsource-18'  => 'Standardluucht B',
'exif-lightsource-19'  => 'Standardluucht C',
'exif-lightsource-24'  => 'ISO Studio Konschtliicht',
'exif-lightsource-255' => 'Aner Liichtquell',

# Flash modes
'exif-flash-fired-0'    => 'Ouni Blëtz',
'exif-flash-fired-1'    => 'mat Blëtz',
'exif-flash-return-0'   => "keng Fonctioun fir d'Reflexioun vum Blëtz festzestellen",
'exif-flash-return-2'   => 'keng Reflexioun vum Blëtz festgestallt',
'exif-flash-return-3'   => 'Reflexioun vum Blëtz festgestallt',
'exif-flash-mode-1'     => 'erzwongene Blëtz',
'exif-flash-mode-2'     => 'De Blëtz war ausgeschalt',
'exif-flash-mode-3'     => 'Automatik',
'exif-flash-function-1' => 'Ouni Blëtzfonctioun',
'exif-flash-redeye-1'   => 'Reduktioun vun de rouden Aen',

'exif-focalplaneresolutionunit-2' => 'Zoll/Inchen',

'exif-sensingmethod-1' => 'Ondefinéiert',
'exif-sensingmethod-2' => 'Een-Chip-Faarfsensor',
'exif-sensingmethod-3' => 'Zwee-Chip-Faarfsensor',
'exif-sensingmethod-4' => 'Dräi-Chip-Faarfsensor',
'exif-sensingmethod-5' => 'Sequentielle raimleche Farwsensor',
'exif-sensingmethod-7' => 'Dräilineare Sensor',
'exif-sensingmethod-8' => 'Sequentielle lineare Farwsensor',

'exif-scenetype-1' => "D'Bild gouf photograféiert",

'exif-customrendered-0' => 'Standard',
'exif-customrendered-1' => 'Benotzerdefinéiert',

'exif-exposuremode-0' => 'Automatesch Beliichtung',
'exif-exposuremode-1' => 'Manuell Beliichtung',
'exif-exposuremode-2' => 'Beliichtungsserie',

'exif-whitebalance-0' => 'Automatesche Wäissofgläich',
'exif-whitebalance-1' => 'Manuelle Wäissofgläich',

'exif-scenecapturetype-0' => 'Standard',
'exif-scenecapturetype-1' => 'Landschaft',
'exif-scenecapturetype-2' => 'Portrait',
'exif-scenecapturetype-3' => 'Nuetszeen',

'exif-gaincontrol-0' => 'Keng',
'exif-gaincontrol-1' => 'Kleng',
'exif-gaincontrol-2' => 'High Gain up',
'exif-gaincontrol-3' => 'Low gain down',
'exif-gaincontrol-4' => 'High gain down',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Schwaach',
'exif-contrast-2' => 'Staark',

'exif-saturation-0' => 'Normal',
'exif-saturation-1' => 'Niddreg Saturatioun',
'exif-saturation-2' => 'Héich',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Douce',
'exif-sharpness-2' => 'Staark',

'exif-subjectdistancerange-0' => 'Onbekannt',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'No',
'exif-subjectdistancerange-3' => 'wäit ewech',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'nërdlech Breet',
'exif-gpslatitude-s' => 'südlech Breet',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'ëstlech Längt',
'exif-gpslongitude-w' => 'westlech Längt',

'exif-gpsstatus-a' => 'Miessung am gaang',
'exif-gpsstatus-v' => 'Interoperabilitéit vu der Miessung',

'exif-gpsmeasuremode-2' => '2-dimensional Miessung',
'exif-gpsmeasuremode-3' => '3-dimensional Miessung',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilometer pro Stonn',
'exif-gpsspeed-m' => 'Meile pro Stonn',
'exif-gpsspeed-n' => 'Kniet',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Tatsächlech Richtung',
'exif-gpsdirection-m' => 'Magnéitesch Richtung',

# External editor support
'edit-externally'      => 'Dëse Fichier mat engem externe Programm veränneren',
'edit-externally-help' => "(Fir gewuer ze gi wéi dat genee geet liest d'[http://www.mediawiki.org/wiki/Manual:External_editors Installatiounsinstruktiounen].)",

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'all',
'imagelistall'     => 'alleguerten',
'watchlistall2'    => 'all',
'namespacesall'    => 'all',
'monthsall'        => 'all',
'limitall'         => 'all',

# E-mail address confirmation
'confirmemail'              => 'E-Mailadress confirméieren',
'confirmemail_noemail'      => 'Dir hutt keng gëlteg E-Mail-Adress an Äre [[Special:Preferences|Benotzerastellungen]] agedro.',
'confirmemail_text'         => "Ier Dir d'E-Mailfunktioune vun {{SITENAME}} benotze kënnt musst dir als éischt Är E-Mailadress confirméieren. Dréckt w.e.g. de Knäppchen hei ënnendrënner fir eng Confirmatiouns-E-Mail op déi Adress ze schécken déi Dir uginn hutt. An där E-Mail steet e Link mat engem Code, deen dir dann an Ärem Browser opmaache musst fir esou ze bestätegen, datt Är Adress och wierklech existéiert a valabel ass.",
'confirmemail_pending'      => 'Dir krut schonn e Confirmatiouns-Code per E-Mail geschéckt. Wenn Dir Äre Benotzerkont eréischt elo kuerz opgemaach hutt, da gedëllegt Iech nach e puer Minutten bis Är E-Mail ukomm ass, ier Dir een neie Code ufrot.',
'confirmemail_send'         => 'Confirmatiouns-E-Mail schécken',
'confirmemail_sent'         => 'Confirmatiouns-E-Mail gouf geschéckt.',
'confirmemail_oncreate'     => "E Confirmatiouns-Code gouf op Är E-Mail-Adress geschéckt.
Dëse Code gëtt fir d'Umeldung net gebraucht. Dir braucht en awer bei der Aktivéierung vun den E-Mail-Funktiounen bannert der Wiki.",
'confirmemail_sendfailed'   => '{{SITENAME}} konnt är Confirmatiouns-E-Mail net schécken.
Iwwerpréift w.e.g. är E-Mailadress op ongëlteg Zeechen.

Feelermeldung vum Mailserver: $1',
'confirmemail_invalid'      => "Ongëltege Confirmatiounscode. Eventuell ass d'Gëltegkeetsdauer vum Code ofgelaf.",
'confirmemail_needlogin'    => 'Dir musst iech $1, fir är E-Mailadress ze confirméieren.',
'confirmemail_success'      => 'Är E-Mailadress gouf confirméiert. Där kënnt iech elo aloggen an a vollem Ëmfang vun der Wiki profitéieren.',
'confirmemail_loggedin'     => 'Är E-Mailadress gouf elo confirméiert.',
'confirmemail_error'        => 'Et ass eppes falsch gelaf bäim Späichere vun ärer Confirmatioun.',
'confirmemail_subject'      => 'Confirmatioun vun der E-Mailadress fir {{SITENAME}}',
'confirmemail_body'         => 'E Benotzer, waarscheinlech dir selwer, hutt mat der IP Adress $1 de Benotzerkont "$2" um Site {{SITENAME}} opgemaach.

Fir ze bestätegen, datt dee Kont iech wierklech gehéiert a fir d\'E-Mail-Funktiounen um Site {{SITENAME}} z\'aktivéieren, maacht w.e.g. dëse Link an ärem Browser op:
$3

Wann dir dëse Benotzerkont *net* opgemaach hutt, maacht w.e.g. dëse Link an ärem Browser op fir d\'E-Mailconfirmation z\'annulléieren:

$5

Sollt et sech net ëm äre Benotzerkont handelen, da maacht de Link *net* op. De Confirmatiounscode ass gëlteg bis de(n) $4.',
'confirmemail_body_changed' => 'E Benotzer, wahrscheinlech Dir selwer, vun der IP-Adress $1,
huet d\'E-Mailadress vum Benotzerkont "$2" op dës Adress op {{SITENAME}} geännert.

Fir ze confirméieren datt dëse Benotzerkont Iech wierklech gehéiert a fir d\'E-Mailfonctiounen op {{SITENAME}} ze reaktivéieren, maacht dës Link an Ärem Browser op:

$3

Wann de Benotzerkont Iech *net* gehéiert, da klickt op dëse Link fir d\'Confirmatioun vun der E-Mailadress auszeschalten:

$5

Dëse Confirmatiouns-Code leeft den $4 of.',
'confirmemail_body_set'     => 'Iergendeen, wahrscheinlech Dir selwer, vun der IP-Adress $1,
huet d\'E-Mailadress vum Benotzerkont "$2" op dës Adress op {{SITENAME}} geännert.

Fir ze confirméieren datt dëse Benotzerkont Iech wierklech gehéiert a fir d\'E-Mailfonctiounen op {{SITENAME}} ze reaktivéieren, maacht dës Link an Ärem Browser op:

$3

Wann de Benotzerkont Iech *net* gehéiert, da klickt op dëse Link fir d\'Confirmatioun vun der E-Mailadress auszeschalten:

$5

Dëse Confirmatiouns-Code leeft den $4 of.',
'confirmemail_invalidated'  => 'Confirmatioun vun der E-Mailadress annulléiert',
'invalidateemail'           => "Annulléier d'E-Mailconfirmation",

# Scary transclusion
'scarytranscludedisabled' => '[Interwiki-Abannung ass ausgeschalt]',
'scarytranscludefailed'   => "[D'Siche no der Schabloun fir $1 huet net funktionéiert]",
'scarytranscludetoolong'  => "[D'URL ass ze laang]",

# Trackbacks
'trackbackbox'      => "''Trackbacke'' fir dës Säit:<br />
$1",
'trackbackremove'   => '([$1 läschen])',
'trackbacklink'     => 'Zréckverfollegen',
'trackbackdeleteok' => "Den ''Trackback'' gouf geläscht.",

# Delete conflict
'deletedwhileediting' => "'''Opgepasst''': Dës Säit gouf geläscht nodeems datt dir ugefaangen hutt se z'änneren!",
'confirmrecreate'     => "De Benotzer [[User:$1|$1]] ([[User talk:$1|Diskussioun]]) huet dës Säit geläscht, nodeems datt där ugefaangen hutt drun ze schaffen. D'Begrënnung war: ''$2'' Bestätegt w.e.g., datt Dir dës Säit wierklich erëm nei opmaache wëllt.",
'recreate'            => 'Erëm uleeën',

# action=purge
'confirm_purge_button' => 'OK',
'confirm-purge-top'    => 'Dës Säit aus dem Server-Cache läschen?',
'confirm-purge-bottom' => "Mécht de Cache vun enger Säit eidel a forcéiert d'Uweise vun der aktueller Versioun.",

# Multipage image navigation
'imgmultipageprev' => '← Vireg Säit',
'imgmultipagenext' => 'nächst Säit →',
'imgmultigo'       => 'Lass',
'imgmultigoto'     => "Géi op d'Säit $1",

# Table pager
'ascending_abbrev'         => 'erop',
'descending_abbrev'        => 'erof',
'table_pager_next'         => 'Nächst Säit',
'table_pager_prev'         => 'Vireg Säit',
'table_pager_first'        => 'Éischt Säit',
'table_pager_last'         => 'Lescht Säit',
'table_pager_limit'        => '$1 Objete pro Säit weisen',
'table_pager_limit_label'  => 'Objete pro Säit:',
'table_pager_limit_submit' => 'Lass',
'table_pager_empty'        => 'Keng Resultater',

# Auto-summaries
'autosumm-blank'   => "D'Säit gouf eidel gemaach",
'autosumm-replace' => "Säit gëtt ersat duerch '$1'",
'autoredircomment' => 'Virugeleet op [[$1]]',
'autosumm-new'     => "Säit ugeluecht mat: '$1'",

# Live preview
'livepreview-loading' => 'Lueden …',
'livepreview-ready'   => 'Lueden … Fäerdeg!',
'livepreview-failed'  => "Live-Preview huet net fonctionéiert! Benotzt w.e.g. d'Fonctioun ''Kucken ouni ofzespäicheren''.",
'livepreview-error'   => "Verbindung net méiglech: $1 „$2“.
Benotzt w.e.g. d'Funktioun fir déi nach net gespäichert Versioun ze kucken (Kucken ouni ofzespäicheren).",

# Friendlier slave lag warnings
'lag-warn-normal' => 'Ännerunge vun {{PLURAL:$1|der leschter Sekonn|de leschte(n) $1 Sekonnen}} kënnen an dëser Lëscht net gewise ginn.',
'lag-warn-high'   => 'Duerch eng héich Serverbelaaschtung kënne Verännerungen déi viru manner wéi $1 {{PLURAL:$1|Sekonn|Sekonne}} gemaach goufen, net an dëser Lëscht ugewise ginn.',

# Watchlist editor
'watchlistedit-numitems'       => "Op Ärer Iwwerwaachungslëscht {{PLURAL:$1|steet 1 Säit|stinn $1 Säiten}}, ouni d'Diskussiounssäiten.",
'watchlistedit-noitems'        => 'Är Iwwerwaachungslëscht ass eidel.',
'watchlistedit-normal-title'   => 'Iwwerwaachungslëscht änneren',
'watchlistedit-normal-legend'  => 'Säite vun der Iwwerwaachungslëscht erofhuelen',
'watchlistedit-normal-explain' => 'D\'Säite vun ärer Iwwerwaachungslëscht ginn drënner gewisen.
Fir eng Säit erofzehuelen, klickt op d\'Haischen niewendrun a klickt duerno op "{{int:Watchlistedit-normal-submit}}".
Dir kënnt och [[Special:Watchlist/raw|déi net formatéiert Iwwerwaachungslëscht änneren]].',
'watchlistedit-normal-submit'  => 'Säiten erofhuelen',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 Säit gouf|$1 Säite goufe}} vun ärer Iwwerwaachungslëscht erofgeholl:',
'watchlistedit-raw-title'      => 'Iwwerwaachungslëscht onformatéiert änneren',
'watchlistedit-raw-legend'     => 'Iwwerwaachungslëscht onformatéiert änneren',
'watchlistedit-raw-explain'    => "D'Säite vun ärer Iwwerwaachungslëscht ginn hei drënner gewisen a kënne geännert ginn andeems der d'Säiten op d'Lëscht derbäisetze oder erofhuelt; eng Säit pro Linn.
Wann Dir fäerdeg sidd, klickt \"{{int:Watchlistedit-raw-submit}}\".
Dir kënnt och [[Special:Watchlist/edit|de Standard Editeur benotzen]].",
'watchlistedit-raw-titles'     => 'Säiten:',
'watchlistedit-raw-submit'     => 'Iwwerwaachungslëscht aktualiséieren',
'watchlistedit-raw-done'       => 'Är Iwwerwaachungslëscht gouf aktualiséiert.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 Säit gouf|$1 Säite goufen}} derbäigesat:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 Säit gouf|$1 Säite goufen}} erausgeholl:',

# Watchlist editing tools
'watchlisttools-view' => 'Iwwerwaachungslëscht: Ännerungen',
'watchlisttools-edit' => 'Iwwerwaachungslëscht weisen an änneren',
'watchlisttools-raw'  => 'Net-formatéiert Iwwerwaachungslëscht änneren',

# Core parser functions
'unknown_extension_tag' => 'Onbekannten Erweiderungs-Tag "$1"',
'duplicate-defaultsort' => 'Opgepasst: Den Zortéierschlëssel "$2" iwwerschreift de viregen Zortéierschlëssel "$1".',

# Special:Version
'version'                          => 'Versioun',
'version-extensions'               => 'Installéiert Erweiderungen',
'version-specialpages'             => 'Spezialsäiten',
'version-parserhooks'              => 'Parser-Erweiderungen',
'version-variables'                => 'Variabelen',
'version-antispam'                 => 'Spam-Preventioun',
'version-skins'                    => 'Skins/Layout',
'version-other'                    => 'Aner',
'version-mediahandlers'            => 'Medien-Ënnerstëtzung',
'version-hooks'                    => 'Klameren',
'version-extension-functions'      => 'Funktioune vun den Erweiderungen',
'version-parser-extensiontags'     => "Parser-Erweiderungen ''(Taggen)''",
'version-parser-function-hooks'    => 'Parser-Funktiounen',
'version-skin-extension-functions' => 'Skin-Erweiderungs-Funktiounen',
'version-hook-name'                => 'Numm vun der Klamer',
'version-hook-subscribedby'        => 'Opruff vum',
'version-version'                  => '(Versioun $1)',
'version-license'                  => 'Lizenz',
'version-poweredby-credits'        => "Dës Wiki fonctionnéiert mat '''[http://www.mediawiki.org/ MediaWiki]''', Copyright © 2001-$1 $2.",
'version-poweredby-others'         => 'anerer',
'version-license-info'             => "MediaWiki ass fräi Software; Dir kënnt se weiderginn an/oder s'änneren ënnert de Bedingungen vun der GNU-General Public License esou wéi se vun der Free Softare Foundation publizéiert ass; entweder ënner der Versioun 2 vun der Lizenz, oder (no Ärem Choix) enger spéiderer Versioun.

MediaWiki gëtt verdeelt an der Hoffnung datt se nëtzlech ass, awer OUNI IERGENDENG GARANTIE; ouni eng implizit Garantie vu Commercialisatioun oder Eegnung fir e bestëmmte Gebrauch. Kuckt d'GPU Geral Public License fir méi Informatiounen.

Dir misst eng [{{SERVER}}{{SCRIPTPATH}}/COPYING Kopie vun der GNU General Public License] mat dësem Programm kritt hunn; wann net da schreift der Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA oder [http://www.gnu.org/licenses/old-licenses/gpl-2.0.html liest se online].",
'version-software'                 => 'Installéiert Software',
'version-software-product'         => 'Produkt',
'version-software-version'         => 'Versioun',

# Special:FilePath
'filepath'         => 'Pad bei de Fichier',
'filepath-page'    => 'Fichier:',
'filepath-submit'  => 'Lass',
'filepath-summary' => 'Op dëser Spezialsäit kënnt Dir de komplette Pad vun der aktueller Versioun vun engem Fichier direkt offroen. Den ugefrote Fichier gëtt direkt gewise respektiv mat enger verbonner Applikatioun gestart.

D\'Ufro muss ouni den Zousaz "{{ns:file}}": gemaach ginn.',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Sich no duebele Fichieren',
'fileduplicatesearch-summary'  => "Sich no Doublone vu Fichieren op der Basis vun hirem ''Hash-Wäert''.

Gitt den Numm vum Fichier ouni de Prefix \"{{ns:file}}:\" an.",
'fileduplicatesearch-legend'   => 'Sich no engem Doublon',
'fileduplicatesearch-filename' => 'Numm vum Fichier:',
'fileduplicatesearch-submit'   => 'Sichen',
'fileduplicatesearch-info'     => '$1 × $2 Pixel<br />Gréisst vum Fichier: $3<br />MIME Typ: $4',
'fileduplicatesearch-result-1' => 'De Fichier "$1" huet keen identeschen Doublon.',
'fileduplicatesearch-result-n' => 'De Fichier "$1" huet {{PLURAL:$2|1 identeschen Doublon|$2 identesch Doublonen}}.',

# Special:SpecialPages
'specialpages'                   => 'Spezialsäiten',
'specialpages-note'              => '----
* Normal Spezialsäiten
* <strong class="mw-specialpagerestricted">Fettgeschriwwe</strong> Spezialsäite sinn nëmme fir Benotzer mat méi Rechter.',
'specialpages-group-maintenance' => 'Maintenance-Rapporten',
'specialpages-group-other'       => 'Aner Spezialsäiten',
'specialpages-group-login'       => 'Aloggen / Umellen',
'specialpages-group-changes'     => 'Rezent Ännerungen a Lëschten',
'specialpages-group-media'       => 'Medie-Rapporten an eropgeluede Fichieren',
'specialpages-group-users'       => 'Benotzer a Rechter',
'specialpages-group-highuse'     => 'Dacks benotzte Säiten',
'specialpages-group-pages'       => 'Lëschte vu Säiten',
'specialpages-group-pagetools'   => 'Handwierksgeschir fir Säiten',
'specialpages-group-wiki'        => 'Systemdaten an Handwierksgeschir',
'specialpages-group-redirects'   => 'Spezialsäiten déi viruleeden',
'specialpages-group-spam'        => 'Handwierksgeschir géint de Spam',

# Special:BlankPage
'blankpage'              => 'Eidel Säit',
'intentionallyblankpage' => 'Dës Säit ass absichtlech eidel. Si gëtt fir Benchmarking an Ähnleches benotzt.',

# External image whitelist
'external_image_whitelist' => "#Dës Zeil genee esou loosse wéi se ass<pre>
#Schreift hei ënnendrënner Fragmenter vu regulären Ausdréck (just den Deel zwëschen den // aginn)
#Dës gi mat den URLe vu Biller aus externe Quelle verglach
#Wann d'Resultat positiv ass, gëtt d'Bild gewisen, soss gëtt d'Bild just als Link gewisen
#Zeilen, déi mat engem # ufänken, ginn als Bemierkung behandelt
#Et gëtt en Ënnerscheed tëschent groussen a klenge Buschtawe gemaach

#All regulär Ausdréck ënner dëser Zeil androen. Dës Zeil genee esou loosse wéi se ass</pre>",

# Special:Tags
'tags'                    => 'Valabel Ännerungsmarkéierungen',
'tag-filter'              => '[[Special:Tags|Markéierungs]]-Filter:',
'tag-filter-submit'       => 'Filter',
'tags-title'              => 'Markéierungen',
'tags-intro'              => 'Op dëser Säit stinn all déi Taggen, déi vun dëser Software fir Ännerungen unzeweise benotzt ginn, an hir Bedeitung.',
'tags-tag'                => 'Numm vun der Markéierung',
'tags-display-header'     => 'Opzielungen op den Ännerungslëschten',
'tags-description-header' => 'Ganz Beschreiwung vun der Bedeitung',
'tags-hitcount-header'    => 'Markéiert Ännerungen',
'tags-edit'               => 'änneren',
'tags-hitcount'           => '$1 {{PLURAL:$1|Ännerung|Ännerungen}}',

# Special:ComparePages
'comparepages'     => 'Säite vergläichen',
'compare-selector' => 'Versioune vu Säite vergläichen',
'compare-page1'    => 'Säit 1',
'compare-page2'    => 'Säit 2',
'compare-rev1'     => 'Versioun 1',
'compare-rev2'     => 'Versioun 2',
'compare-submit'   => 'Vergläichen',

# Database error messages
'dberr-header'      => 'Dës Wiki huet e Problem',
'dberr-problems'    => 'Pardon! Dëse Site huet technesch Schwieregkeeten.',
'dberr-again'       => 'Versicht e puer Minutten ze waarden an dann nei ze lueden.',
'dberr-info'        => '(Den Datebank-Server kann net erreecht ginn: $1)',
'dberr-usegoogle'   => 'An der Tëschenzäit kënnt Dir probéiere mam Google ze sichen.',
'dberr-outofdate'   => 'Denkt drunn, datt de Sichindex vun eisen Inhalte méiglecherweis net aktuell ass.',
'dberr-cachederror' => 'Dëst ass eng tëschegespäichert Kopie vun der gefroter Säit, a si kann eventuell net aktuell sinn.',

# HTML forms
'htmlform-invalid-input'       => 'Et gëtt Problemer mat de Wäerter déi dir aginn hutt.',
'htmlform-select-badoption'    => 'De Wäert deen Dir aginn hutt ass keng valabel Optioun.',
'htmlform-int-invalid'         => 'De Wäert deen Dir aginn hutt ass keng ganz Zuel.',
'htmlform-float-invalid'       => 'De Wäert deen Dir uginn hutt ass keng Zuel.',
'htmlform-int-toolow'          => 'De Wäert deen Dir uginn hutt ass ënnert dem Minimum vu(n) $1',
'htmlform-int-toohigh'         => 'De Wäert deen Dir uginn hutt ass iwwert dem Maximum vu(n) $1',
'htmlform-required'            => 'Dëse Wäert ass verlaangt',
'htmlform-submit'              => 'Späicheren',
'htmlform-reset'               => 'Ännerungen zrécksetzen',
'htmlform-selectorother-other' => 'Anerer',

# SQLite database support
'sqlite-has-fts' => "$1 ënnerstëtzt d'Volltextsich",
'sqlite-no-fts'  => "$1 ënnerstëtzt d'Volltextsich net",

# Special:DisableAccount
'disableaccount'             => 'E Benotzerkont desaktivéieren',
'disableaccount-user'        => 'Benotzernumm:',
'disableaccount-reason'      => 'Grond:',
'disableaccount-confirm'     => "Dëse Benotzerkont desaktivéieren.
De Benotzer ka sech net méi aloggen, säi Passwuert änneren, a kritt och keng Noriichte méi per Mail.
Wann e Benotzer elo iergendwou ageloggt ass da gëtt hien direkt ausgeloggt.
''Denkt drun datt desaktivéiere vun engem Kont net ka réckgängeg gemaach ginn ouni d'Interventioun vun engem Administrateur vum System.''",
'disableaccount-mustconfirm' => 'Dir musst confirméieren datt Dir dëse Kont desaktivéiere wëllt.',
'disableaccount-nosuchuser'  => 'De Benotzerkont "$1" gëtt et net.',
'disableaccount-success'     => 'De Benotzerkont "$1" gouf definitiv desaktivéiert.',
'disableaccount-logentry'    => 'huet de Benotzer [[$1]] definitiv desaktivéiert',

);

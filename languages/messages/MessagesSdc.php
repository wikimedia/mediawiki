<?php
/** Sassaresu (Sassaresu)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Antofa
 * @author Cornelia
 * @author Felis
 */

$namespaceNames = array(
	NS_SPECIAL          => 'Ippiziari',
	NS_TALK             => 'Dischussioni',
	NS_USER             => 'Utenti',
	NS_USER_TALK        => 'Dischussioni_utenti',
	NS_PROJECT_TALK     => 'Dischussioni_$1',
	NS_FILE             => 'Immagina',
	NS_FILE_TALK        => 'Dischussioni_immagina',
	NS_MEDIAWIKI_TALK   => 'Dischussioni_MediaWiki',
	NS_TEMPLATE         => 'Mudellu',
	NS_TEMPLATE_TALK    => 'Dischussioni_mudellu',
	NS_HELP             => 'Aggiuddu',
	NS_HELP_TALK        => 'Dischussioni_aggiuddu',
	NS_CATEGORY         => 'Categuria',
	NS_CATEGORY_TALK    => 'Dischussioni_categuria',
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'RinviiDoppi' ),
	'BrokenRedirects'           => array( 'RinviiIbbagliaddi' ),
	'Disambiguations'           => array( 'CuLuMatessiInnommu' ),
	'Userlogin'                 => array( 'Intra', 'Login', 'Accesso' ),
	'Userlogout'                => array( 'Isci', 'Logout', 'Uscita' ),
	'Preferences'               => array( 'Prifirènzi' ),
	'Watchlist'                 => array( 'AbbaidaddiIppiziari' ),
	'Recentchanges'             => array( 'UlthimiMudìfigghi' ),
	'Upload'                    => array( 'Carrigga' ),
	'Listfiles'                 => array( 'Immagini' ),
	'Newimages'                 => array( 'ImmaginiRizzenti' ),
	'Listusers'                 => array( 'Utenti', 'ErencuUtenti' ),
	'Statistics'                => array( 'Sthatisthigghi' ),
	'Randompage'                => array( 'PàginaCasuari' ),
	'Lonelypages'               => array( 'PàginaÒiffana' ),
	'Uncategorizedpages'        => array( 'PàginiChenaCateguri' ),
	'Uncategorizedcategories'   => array( 'CateguriNòCategurizzaddi' ),
	'Uncategorizedimages'       => array( 'ImmaginiChenaCateguri' ),
	'Uncategorizedtemplates'    => array( 'MudelliChenaCateguri' ),
	'Unusedcategories'          => array( 'CateguriInutirizaddi' ),
	'Unusedimages'              => array( 'FileInutirizaddi' ),
	'Wantedpages'               => array( 'PàginiPiùDumandaddi' ),
	'Wantedcategories'          => array( 'CateguriDumandaddi' ),
	'Mostlinked'                => array( 'PàginiPiùRiciamaddi' ),
	'Mostlinkedcategories'      => array( 'CateguriPiùRiciamaddi' ),
	'Mostlinkedtemplates'       => array( 'MudelliPiùRiciamaddi' ),
	'Mostimages'                => array( 'ImmaginiPiùRiciamaddi' ),
	'Mostcategories'            => array( 'PàginiCunPiùCateguri' ),
	'Mostrevisions'             => array( 'PàginiCunPiùRibisioni' ),
	'Fewestrevisions'           => array( 'PàginiCunMancuRibisioni' ),
	'Shortpages'                => array( 'PàginiPiùCorthi' ),
	'Longpages'                 => array( 'PàginiPiùLonghi' ),
	'Newpages'                  => array( 'PàginiPiùRizzenti' ),
	'Ancientpages'              => array( 'PàginiMancuRizzenti' ),
	'Deadendpages'              => array( 'PàginiChenaIscidda' ),
	'Protectedpages'            => array( 'PàginiPrutiggiddi' ),
	'Allpages'                  => array( 'TuttiLiPàgini' ),
	'Prefixindex'               => array( 'Prefissi' ),
	'Ipblocklist'               => array( 'IPBroccaddi' ),
	'Specialpages'              => array( 'PàginiIppiziari' ),
	'Contributions'             => array( 'Cuntributi', 'CuntributiUtente' ),
	'Emailuser'                 => array( 'InviaPosthaErettrònica' ),
	'Whatlinkshere'             => array( 'PuntaniInogghi' ),
	'Recentchangeslinked'       => array( 'MudìfigghiLiaddi' ),
	'Movepage'                  => array( 'Ippustha', 'Rinumina' ),
	'Blockme'                   => array( 'BroccaProxy' ),
	'Booksources'               => array( 'ZirchaISBN' ),
	'Categories'                => array( 'Categuri' ),
	'Export'                    => array( 'Ippurtha' ),
	'Version'                   => array( 'Versioni' ),
	'Allmessages'               => array( 'Imbasciaddi' ),
	'Log'                       => array( 'Rigisthru', 'Rigisthri', 'Registro', 'Registri' ),
	'Blockip'                   => array( 'Brocca' ),
	'Undelete'                  => array( 'TurraChePrimma' ),
	'Import'                    => array( 'Impurtha' ),
	'Lockdb'                    => array( 'BroccaDB' ),
	'Unlockdb'                  => array( 'IbbruccaDB' ),
	'Userrights'                => array( 'PrimmissiUtenti' ),
	'MIMEsearch'                => array( 'ZirchaMIME' ),
	'Unwatchedpages'            => array( 'PàginiNòAbbaidaddi' ),
	'Listredirects'             => array( 'Rinvii' ),
	'Revisiondelete'            => array( 'CanzillaRibisioni' ),
	'Unusedtemplates'           => array( 'MudelliInutirizaddi' ),
	'Randomredirect'            => array( 'RinviuCasuari' ),
	'Mypage'                    => array( 'MeaPàginaUtenti' ),
	'Mytalk'                    => array( 'MéDischussioni' ),
	'Mycontributions'           => array( 'MéCuntributi' ),
	'Listadmins'                => array( 'Amministhradori' ),
	'Popularpages'              => array( 'PàginiPiùVisitaddi' ),
	'Search'                    => array( 'Zircha', 'Ricerca' ),
	'Resetpass'                 => array( 'RimpusthàParàuraDÓrdhini' ),
	'Withoutinterwiki'          => array( 'PàginiChenaInterwiki' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Sotturìnia li cullegamenti:',
'tog-highlightbroken'         => 'Fuimmadda <a href="" class="new">cussì</a> li cullegamenti a pàgini inesisthenti (si disattibaddu: cussì<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Alliniamentu di li paràgrafi giusthifiggaddu',
'tog-hideminor'               => "Cua li mudìfigghi minori i' l'ulthimi mudìfigghi",
'tog-extendwatchlist'         => "Musthra tutti li mudìfigghi a li abbaidaddi ippiziari, nò soru l'ulthimi.",
'tog-usenewrc'                => "Utirizza l'ulthimi mudìfigghi abanzaddi (dumanda JavaScript)",
'tog-numberheadings'          => 'Numarazioni otomàtigga di li tìturi di sezzioni',
'tog-showtoolbar'             => "Musthra barra di l'isthrumenti di mudìfigga (dumanda JavaScript)",
'tog-editondblclick'          => 'Mudìfigga di li pàgini attrabessu dóppiu clic (dumanda JavaScript)',
'tog-editsection'             => 'Mudìfigga di li sezzioni attrabessu lu cullegamentu [mudifigga]',
'tog-editsectiononrightclick' => "Mudìfigga di li sezzioni attrabessu lu clic dresthu i' lu tìturu (nezzessàriu JavaScript)",
'tog-showtoc'                 => "Musthra l'indizi pa li pàgini cun più di 3 sezzioni",
'tog-rememberpassword'        => "Ammenta la paràura d'órdhini (nezzessàriu azzittà li cookie)",
'tog-editwidth'               => 'Aumenta a la massima larghèzia la casella di mudìfigga',
'tog-watchcreations'          => "Aggiungi li pàgini criaddi a l'abbaidaddi ippiziari",
'tog-watchdefault'            => "Aggiungi li pàgini mudìfiggaddi a l'abbaidaddi ippiziari",
'tog-watchmoves'              => "Aggiungi li pàgini ippusthaddi a l'abbaidaddi ippiziari",
'tog-watchdeletion'           => "Aggiungi li pàgini canzilladdi a l'abbaidaddi ippiziari",
'tog-minordefault'            => "Indica tutti li mudìfigghi cumenti 'minori' in otomàtiggu",
'tog-previewontop'            => "Musthra l'antiprimma sobra la casella di mudìfigga",
'tog-previewonfirst'          => "Musthra l'antiprimma pa la primma mudìfigga",
'tog-nocache'                 => 'Disattiba la mimória cache pa li pàgini',
'tog-enotifwatchlistpages'    => 'Signàrami pa postha erettrònica li mudìfigghi a li pàgini abbaidaddi',
'tog-enotifusertalkpages'     => "Signàrami pa postha erettrònica li mudìfigghi a la me' pàgina di dischussioni",
'tog-enotifminoredits'        => 'Signàrami pa postha erettrònica puru li mudìfigghi minori',
'tog-enotifrevealaddr'        => "Rivera lu me' indirizzu di postha erettrònica i' l'imbasciaddi d'avvisu",
'tog-shownumberswatching'     => "Musthra lu nùmaru d'utenti ch'àni la pàgina abbaidadda",
'tog-fancysig'                => "Interpreta i cumandi wiki i' la fimma (chena cullegaumentu otomatiggu)",
'tog-externaleditor'          => 'Impustha cumenti pridifiniddu un cumponidori di testhi esthernu',
'tog-externaldiff'            => 'Impustha cumenti pridifiniddu un prugramma di diff esthernu',
'tog-showjumplinks'           => "Attiba li cullegamenti atzessibili 'vai a'",
'tog-uselivepreview'          => "Attiba la funzioni ''Live preview'' (dumanda JavaScript; ippirimintari)",
'tog-forceeditsummary'        => "Dumanda cunfèimma si l'oggettu di la mudìfigga è bioddu",
'tog-watchlisthideown'        => "Cua li me' mudìfigghi i' l'abbaidaddi ippiziari",
'tog-watchlisthidebots'       => "Cua li mudìfigghi di li bot i' l'abbaidaddi ippiziari",
'tog-watchlisthideminor'      => "Cua li mudìfigghi minori i' l'abbaidaddi ippiziari",
'tog-nolangconversion'        => "Disattiba lu cunvirthimentu i' li varianti linghìsthighi",
'tog-ccmeonemails'            => "Inviammi una còpia di l'imbasciaddi ippididdi a l'althri utenti",
'tog-diffonly'                => 'No visuarizzà lu cuntinuddu di la pàgina daboi lu cunfrontu tra versioni',
'tog-showhiddencats'          => 'Musthrà li categuri cuaddi',
'tog-norollbackdiff'          => 'Nò musthrà lu cunfrontu tra versioni daboi abé annulladdu li mudifigghi',

'underline-always'  => 'Sempri',
'underline-never'   => 'Mai',
'underline-default' => "Manteni l'impusthazioni di lu nabiggadori",

# Dates
'sunday'        => 'Dumènigu',
'monday'        => 'Luni',
'tuesday'       => 'Marthi',
'wednesday'     => 'Màrchuri',
'thursday'      => 'Giobi',
'friday'        => 'Vènnari',
'saturday'      => 'Sàbadu',
'sun'           => 'Dum',
'mon'           => 'Lun',
'tue'           => 'Mar',
'wed'           => 'Màr',
'thu'           => 'Gio',
'fri'           => 'Vèn',
'sat'           => 'Sàb',
'january'       => 'Ginnàggiu',
'february'      => 'Fribbàggiu',
'march'         => 'Mazzu',
'april'         => 'Abriri',
'may_long'      => 'Màggiu',
'june'          => 'Làmpadda',
'july'          => 'Trìura',
'august'        => 'Aosthu',
'september'     => 'Cabbidannu',
'october'       => 'Santuaini',
'november'      => 'Sant’Andria',
'december'      => 'Naddari',
'january-gen'   => 'Ginnàggiu',
'february-gen'  => 'Fribbàggiu',
'march-gen'     => 'Mazzu',
'april-gen'     => 'Abriri',
'may-gen'       => 'Màggiu',
'june-gen'      => 'Làmpadda',
'july-gen'      => 'Trìura',
'august-gen'    => 'Aosthu',
'september-gen' => 'Cabbidannu',
'october-gen'   => 'Santuaini',
'november-gen'  => "Sant'Andria",
'december-gen'  => 'Naddari',
'jan'           => 'Gin',
'feb'           => 'Fri',
'mar'           => 'Maz',
'apr'           => 'Abr',
'may'           => 'Màg',
'jun'           => 'Làm',
'jul'           => 'Trì',
'aug'           => 'Aos',
'sep'           => 'Cab',
'oct'           => 'SAini',
'nov'           => 'SAndria',
'dec'           => 'Nad',

# Categories related messages
'pagecategories'           => '{{PLURAL:$1|Categuria|Categuri}}',
'category_header'          => 'Pàgini i\' la categuria "$1"',
'subcategories'            => 'Sottucateguri',
'category-media-header'    => 'File i\' la categuria "$1"',
'category-empty'           => "''Attuarmenti la categuria no cunteni nisciuna pàgina o file.''",
'hidden-categories'        => '{{PLURAL:$1|Categuria cuadda|Categuri cuaddi}}',
'hidden-category-category' => 'Categuri cuaddi',
'category-subcat-count'    => "{{PLURAL:$2|Chistha categuria cunteni un'unica sottocateguria, indicadda inogghi.|Chistha categuria cunteni {{PLURAL:$1|la sottocateguria indicadda|li $1 sottocategurì indicaddi}} inogghi, i' un tutari di $2.}}",
'category-article-count'   => "{{PLURAL:$2|Chistha categuria cunteni un'unica pagina, indicadda inogghi.|Chistha categuria cunteni {{PLURAL:$1|la pagina indicadda|li $1 pagini indicaddi}} inogghi, i' un tutari di $2.}}",
'listingcontinuesabbrev'   => '(séguiddu)',

'mainpagetext'      => "'''Isthallazioni di MediaWiki accabadda currentementi.'''",
'mainpagedocfooter' => "Cunsultha la [http://meta.wikimedia.org/wiki/Aggiuddu:Summàriu Ghia utenti] pa maggiori infuimmazioni i l'usu di chisthu software wiki.

== Pa ischuminzà ==
Li sighenti cullegamenti so in linga ingrese:

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Impusthazioni di cunfigurazioni]
* [http://www.mediawiki.org/wiki/Manual:FAQ Prigonti friquenti i MediaWiki]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Mailing list annùnzii MediaWiki]",

'about'         => 'Infuimmazioni',
'article'       => 'Pagina',
'newwindow'     => "(s'abbri in d'unu nobu balchoni)",
'cancel'        => 'Annulla',
'moredotdotdot' => 'Althru...',
'mypage'        => 'La mea pàgina',
'mytalk'        => "Li me' dischussioni",
'anontalk'      => 'Dischussioni pa chisthu IP',
'navigation'    => 'Nabiggazioni',
'and'           => '&#32;e',

# Cologne Blue skin
'qbfind'         => 'Acciappa',
'qbbrowse'       => 'Iffuglia',
'qbedit'         => 'Mudifigga',
'qbpageoptions'  => 'Prifirenzi pàgina',
'qbpageinfo'     => "Infuimmazioni i' la pàgina",
'qbmyoptions'    => "Li me' pàgini",
'qbspecialpages' => 'Pàgini ippiziari',
'faq'            => 'FAQ (infuimmazioni e aggiuddu)',
'faqpage'        => 'Project:FAQ (infuimmazioni e aggiuddu)',

# Vector skin
'vector-action-delete'      => 'Canzella',
'vector-action-move'        => 'Ippustha',
'vector-namespace-category' => 'Categuria',
'vector-namespace-image'    => 'File',
'vector-namespace-main'     => 'Pàgina',
'vector-namespace-talk'     => 'Dischussioni',
'vector-view-create'        => 'Cria',
'vector-view-edit'          => 'Mudifigga',
'vector-view-view'          => 'Leggi',

'errorpagetitle'    => 'Errori',
'returnto'          => 'Turra a $1.',
'tagline'           => 'Da {{SITENAME}}.',
'help'              => 'Aggiuddu',
'search'            => 'Zercha',
'searchbutton'      => 'Zercha',
'go'                => 'Vai',
'searcharticle'     => 'Vai',
'history'           => 'Versioni prizzidenti',
'history_short'     => 'Cronologia',
'updatedmarker'     => "mudìfiggadda di la me' ulthima vìsita",
'info_short'        => 'Infuimmazioni',
'printableversion'  => 'Versioni sthampabiri',
'permalink'         => 'Cullegamentu peimmanenti',
'print'             => 'Sthampa',
'edit'              => 'Mudifigga',
'create'            => 'Cria',
'editthispage'      => 'Mudìfigga chistha pàgina',
'create-this-page'  => 'Cria chistha pàgina',
'delete'            => 'Canzella',
'deletethispage'    => 'Canzella chistha pàgina',
'undelete_short'    => 'Ricùpara {{PLURAL:$1|una ribisioni|$1 ribisioni}}',
'protect'           => 'Brocca',
'protect_change'    => 'ciamba',
'protectthispage'   => 'Prutiggi chistha pàgina',
'unprotect'         => 'Ibbrucca',
'unprotectthispage' => 'Ibbrucca chistha pàgina',
'newpage'           => 'Noba pàgina',
'talkpage'          => 'Pàgina di dischussioni',
'talkpagelinktext'  => 'Dischussioni',
'specialpage'       => 'Pagina ippiziari',
'personaltools'     => 'Isthrumenti passunari',
'postcomment'       => 'Noba sezzioni',
'articlepage'       => 'Vedi la bozi',
'talk'              => 'dischussioni',
'views'             => 'Vìsiti',
'toolbox'           => 'Isthrumenti',
'userpage'          => 'Visuarizza la pàgina utenti',
'projectpage'       => 'Visuarizza la pàgina di saivvìziu',
'imagepage'         => 'Visuarizza la pagina di lu file',
'mediawikipage'     => 'Visuarizza la imbasciadda',
'templatepage'      => 'Visuarizza lu mudellu',
'viewhelppage'      => 'Visuarizza la pàgina di aggiuddu',
'categorypage'      => 'Visuarizza la categuria',
'viewtalkpage'      => 'Visuarizza la pàgina di dischussioni',
'otherlanguages'    => 'Althri linghi',
'redirectedfrom'    => '(Rinviu da $1)',
'redirectpagesub'   => 'Pàgina di rinviu',
'lastmodifiedat'    => 'Ulthima mudìfigga pa la pàgina: $2, $1.',
'viewcount'         => 'Chistha pàgina è isthadda liggidda {{PLURAL:$1|una voltha|$1 volthi}}.',
'protectedpage'     => 'Pàgina broccadda',
'jumpto'            => 'Vai a:',
'jumptonavigation'  => 'nabiggazioni',
'jumptosearch'      => 'zercha',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Infuimmazioni in {{SITENAME}}',
'aboutpage'            => 'Project:Infuimmazioni',
'copyright'            => "Cuntinuddi suggetti a licèntzia d'usu $1.",
'copyrightpage'        => '{{ns:project}}:Copyright',
'currentevents'        => 'Attuarità',
'currentevents-url'    => 'Project:Attuarità',
'disclaimers'          => 'Avvirthènzi',
'disclaimerpage'       => 'Project:Avvirthènzi ginarari',
'edithelp'             => 'Ghia',
'edithelppage'         => 'Help:Mudìfigga',
'helppage'             => 'Help:Indizi',
'mainpage'             => 'Pàgina prinzipari',
'mainpage-description' => 'Pàgina prinzipari',
'policy-url'           => 'Project:Policy',
'portal'               => 'Purthari comuniddai',
'portal-url'           => 'Project:Purthari comuniddai',
'privacy'              => "Infuimmazioni i' la riseivvaddèzia",
'privacypage'          => 'Project:Infuimmazioni i la riseivvaddèzia',

'badaccess'        => 'Primmissi no suffizenti',
'badaccess-group0' => "Nò si diponi di li primmissi nezzessàri pa eseguì l'azioni dumandadda.",
'badaccess-groups' => 'La funzioni dumandadda è riseivvadda a li utenti chi apparthènini a unu di li sighenti gruppi: $1.',

'versionrequired'     => 'Versioni $1 di MediaWiki dumandadda',
'versionrequiredtext' => "Pa usà chistha pàgina è nezzessàriu dipunì di la versioni $1 di lu software MediaWiki. Vedi [[Special:Version|l'appósidda pàgina]].",

'ok'                      => 'EMMU',
'retrievedfrom'           => 'Buggaddu da "$1"',
'youhavenewmessages'      => 'Ài $1 ($2).',
'newmessageslink'         => 'nobi imbasciaddi',
'newmessagesdifflink'     => 'diffarènzia cu la revisioni prizzidenti',
'youhavenewmessagesmulti' => 'Ài nobi imbasciaddi i $1',
'editsection'             => 'mudifigga',
'editold'                 => 'mudifigga',
'viewsourceold'           => "visuarizza l'orìgini",
'editlink'                => 'mudifigga',
'viewsourcelink'          => "visuarizza l'orìgini",
'editsectionhint'         => 'Mudìfigga la sezzioni $1',
'toc'                     => 'Indizi',
'showtoc'                 => 'musthra',
'hidetoc'                 => 'cua',
'thisisdeleted'           => 'Vedi o turra che primma $1?',
'viewdeleted'             => 'Vedi $1?',
'restorelink'             => '{{PLURAL:$1|una mudìfigga canzilladda|$1 mudìfigghi canzilladdi}}',
'feedlinks'               => 'Feed:',
'feed-invalid'            => 'Manera di suttischrizioni di lu feed no vàridda.',
'site-rss-feed'           => '$1 RSS Feed',
'site-atom-feed'          => '$1 Atom Feed',
'page-rss-feed'           => '"$1" RSS Feed',
'page-atom-feed'          => '"$1" Atom Feed',
'red-link-title'          => '$1 (la pagina nò esisthi)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Pàgina',
'nstab-user'      => 'Utenti',
'nstab-media'     => 'File mùrthimediari',
'nstab-special'   => 'Pàgina ippiziari',
'nstab-project'   => 'Pàgina di saivvìziu',
'nstab-image'     => 'Immàgina',
'nstab-mediawiki' => 'Imbasciadda',
'nstab-template'  => 'Mudellu',
'nstab-help'      => 'Aggiuddu',
'nstab-category'  => 'Categuria',

# Main script and global functions
'nosuchaction'      => 'Operazioni no ricuniscidda',
'nosuchactiontext'  => "L'indirizzu immessu no curripondi a unu cumandu ricunisciddu da lu software MediaWiki",
'nosuchspecialpage' => 'Pàgina ippiziari no dipunìbiri',
'nospecialpagetext' => "La pàgina ippiziari dumandadda no è isthadda ricuniscidda da lu software MediaWiki; l'erencu di li pàgini ippiziari vàriddi s'acciappa i'li [[Special:SpecialPages|{{int:specialpages}}]].",

# General errors
'error'                => 'Errori',
'databaseerror'        => 'Errori di la bancadati',
'dberrortext'          => 'Errori di sintassi i\' la prigonta inviadda a la bancadati.
Lu chi pudaria indicà la prisènzia d\'un bacu i\' lu software.
L\'ulthima interrogazioni inviadda a la bancadati è isthadda:
<blockquote><tt>$1</tt></blockquote>
riciamadda da la funzioni "<tt>$2</tt>".
MySQL à turraddu lu sighenti errori "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Errori di sintassi i\' la prigonta inviadda a la bancadati.
L\'ulthima interrogazioni inviadda a la bancadati è isthadda:
"$1"
riciamadda da la funzioni "$2".
MySQL à turraddu lu sighenti errori "$3: $4".',
'laggedslavemode'      => "Attinzioni: la pàgina pudia no cuntinì l'aggiornamenti più rizzenti.",
'readonly'             => 'Bancadati broccadda',
'enterlockreason'      => 'Indica lu mutibu di lu broccu, ippizzifichendi candu po’ assé chi venghia buggaddu.',
'readonlytext'         => "Attuarmenti la bancadati è broccadda e nò so pussìbiri aggiunti o mudìfigghi a li pàgini. Lu broccu soritamenti è liaddu a operazioni di manutinzioni urdhinària, a lu tèimmini di li quari la bancadati è di nobu mudifiggabiri.

L'amministhradori di sisthema chi l'à broccadda à lassaddu chistha giusthifiggazioni: $1",
'missing-article'      => "La bancadati nò ha acciappaddu lu teshu d'una pagina chi abaria dububbu acciappà cu' l'innommu di \"\$1\" \$2.

Di soritu sussedi sighendi una diffarenzia ischadudda o canzilladda i' la cronologia

Sinnò si pò abé ischuberthu un'errori i' lu software MediaWiki.
Si priga di cuntattà un'[[Special:ListUsers/sysop|amministhradore]], ippizzifichendi chisthu URL.",
'missingarticle-rev'   => '(nùmaru di la ribisioni #: $1)',
'missingarticle-diff'  => '(Diff: $1, $2)',
'readonly_lag'         => 'La bancadati è isthadda broccadda automatiggamenti pà cunsintì a lu server cu’ la bancadati slave di fà i’ lu matessi mamentu cu’ lu master',
'internalerror'        => 'Errori internu',
'internalerror_info'   => 'Errori internu: $1',
'filecopyerror'        => 'Impussìbiri cupià lu "$1" in "$2".',
'filerenameerror'      => 'Impussìbiri rinuminà lu file "$1" in "$2".',
'filedeleteerror'      => 'Impussìbiri canzillà lu file "$1".',
'directorycreateerror' => 'Impussìbiri crià la directory "$1".',
'filenotfound'         => 'File "$1" no acciappaddu.',
'fileexistserror'      => 'Impussìbiri ischribì lu file "$1": lu file isisthì già',
'unexpected'           => 'Varori impribisthu: "$1"="$2".',
'formerror'            => 'Errori: impussìbiri invià lu mòdulu',
'badarticleerror'      => 'Operazioni no cunsintidda pa chistha pàgina.',
'cannotdelete'         => 'Impussìbiri canzillà la pàgina o lu file dumandaddu. (Pudia assé isthaddu già canzilladdu.)',
'badtitle'             => 'Tìturu no currettu',
'badtitletext'         => "Lu tìturu di la pàgina dumandadda è bioddu, ibbagliaddu o cun caràtteri no ammessi oppuru deriba da un errori i' li cullegamenti tra siti wiki dibessi o versioni in linghi dibessi di lu matessi situ.",
'perfcached'           => "Li dati chi seghini so cabaddi da una còpia i' la mimória cache di la bancadati, no aggiornaddi in tempu riari.",
'perfcachedts'         => "Li dati chi seghini so cabaddi da una còpia i' la mimória cache di la bancadati. Ulthimu aggiornamentu: $1.",
'querypage-no-updates' => "L'aggiornamenti di la pàgina so timpuraniamenti suippesi. Li dati in edda cuntinuddi no sarani aggiornaddi.",
'wrong_wfQuery_params' => "Errori i' li parametri inviaddi a la funzioni wfQuery()<br />
funzioni: $1<br />
Interrogazioni: $2",
'viewsource'           => 'Vèdi còdizi',
'viewsourcefor'        => 'pa $1',
'actionthrottled'      => 'Azioni limitadda',
'actionthrottledtext'  => "Cumenti rimédiu anti-spam, v'è un lìmiti a l'azioni ch'è pussìbiri eseguì i'nu tempu isthabiriddu, e abà suparaddu. Pògu tèmpu e pói riprubà.",
'protectedpagetext'    => 'Chistha pàgina è isthadda prutiggidda pa impidinni la mudìfigga.',
'viewsourcetext'       => 'È pussìbiri visuarizzà e cupià lu còdizi di chistha pàgina:',
'protectedinterface'   => "Chistha pàgina cunteni un'erementu chi fazzi parthi di l'interfàccia utenti di lu software; è dunca prutiggidda pa evità pussìbiri abusi.",
'editinginterface'     => "'''Attinzioni:''' Lu testhu di chistha pàgina fazzi parthi di l'interfàccia utenti di lu situ. Tutti li mudìfigghi arriggaddi a chistha pàgina si rifrèttini i' l'imbasciaddi visuarizzaddi pa tutti l'utenti. Pa li traduzioni, pa piazeri utirizà [http://translatewiki.net/wiki/Main_Page?setlang=sdc translatewiki.net], lu prugettu di lucarizazioni MediaWiki.",
'sqlhidden'            => "(l'interrogazioni SQL è isthadda cuadda)",
'cascadeprotected'     => 'In chistha pàgina nò è pussìbiri effettuà mudìfigghi parchí è isthadda incrusa {{PLURAL:$1|i la sighenti pàgina indicadda, ch\'è isthadda prutiggidda|i li sighenti pàgini indicaddi, chi so isthaddi prutiggiddi}} chirriendi la prutizioni "ricussiba":
$2',
'namespaceprotected'   => "Nò si diponi di li pimmissi nezzessàri pa mudifiggà li pagini di lu tipu di pagina '''$1'''.",
'customcssjsprotected' => "Nò si diponi di li pimmissi nezzessàri pa mudifiggà la pàgina, parchí cunteni l'impusthazioni passunari di un althru utenti.",
'ns-specialprotected'  => 'No è pussìbiri mudifiggà li pàgini ippiziari.',
'titleprotected'       => "Chisthu tìturu è isthaddu prutiggiddu da la criazioni da [[User:$1|$1]].
La rasgioni frunidda è ''$2''.",

# Login and logout pages
'logouttext'                 => "'''Iscidda effettuadda.'''

Si pò sighì a usà {{SITENAME}} cumenti utenti anònimu oppuru eseguì una noba intradda, cu' lu matessi innòmu utenti o un'innòmu dibessu.
Zerthuni pàgini pudìani continuà a apparì cumenti si la iscidda nò fùssia avvinudda finaghì nò vèni puridda la mimória cache di lu propriu nabiggadori.",
'welcomecreation'            => '== Binvinuddu, $1! ==

La registhrazioni è isthadda criadda currettamenti. No dimintiggà di passunarizzà li prifirenzi di {{SITENAME}}.',
'yourname'                   => 'Innòmu utenti',
'yourpassword'               => "Paràura d'órdhini",
'yourpasswordagain'          => "Ripeti la paràura d'órdhini",
'remembermypassword'         => "Ammenta la paràura d'órdhini",
'yourdomainname'             => 'Ippizzificà lu dumìniu',
'externaldberror'            => "S'è verifiggaddu un errori cu lu server di autentificazioni esthernu, oppuru nò si diponi di l'autorizazioni nezzessàri pa aggiornà la propria registhrazioni estherna.",
'login'                      => 'Intra',
'nav-login-createaccount'    => 'Intra / registhrazioni',
'loginprompt'                => 'Pa intrà a {{SITENAME}} è nezzessàriu abirità li cookie.',
'userlogin'                  => 'Intra o cria una noba registhrazioni',
'logout'                     => 'Esci',
'userlogout'                 => 'Esci',
'notloggedin'                => 'Intradda no effettuadda',
'nologin'                    => "No ài una registhrazioni? '''$1'''.",
'nologinlink'                => 'Crèara abà',
'createaccount'              => 'Crea una noba registhrazioni',
'gotaccount'                 => "Ài già'na registhrazioni? '''$1'''.",
'gotaccountlink'             => 'Intra',
'createaccountmail'          => 'via postha erettrònica',
'badretype'                  => "Li paràuri d'órdhini insiriddi nò cuinzidhini tra èddi.",
'userexists'                 => "L'innòmu utenti insiriddu è già utirizaddu. Pa pazieri chirria un'innòmu utenti dibessu.",
'loginerror'                 => "Errori i' l'intradda",
'nocookiesnew'               => "La registhrazioni di l'utenti pa l'intradda è isthadda criadda, ma nò è isthaddu pussìbiri intrà a {{SITENAME}} parchí li cookie so disattibaddi. Riprubà l'intradda cu l'innòmu utenti e la paràura d'órdhini appèna criaddi daboi abé attibaddu li cookie i lu propriu nabiggadori.",
'nocookieslogin'             => "L'intradda a {{SITENAME}} dumanda l'usu di li cookie, chi risulthani disattibaddi. Riprubà l'intradda daboi abé attibaddu li cookie i' lu propriu nabiggadori.",
'noname'                     => "L'innòmu utenti indicaddu nò è vàriddu.",
'loginsuccesstitle'          => 'Intradda effettuadda',
'loginsuccess'               => "'''Lu cullegamentu a lu server di {{SITENAME}} cu l'innòmu utenti \"\$1\" è attibu.'''",
'nosuchuser'                 => 'Nò è registhraddu caschunu utenti d\'innòmu "$1". Verifiggà l\'innòmu insiriddu o crià una noba registhrazioni.',
'nosuchusershort'            => 'Nò è registhraddu caschunu utenti d\'innòmu "<nowiki>$1</nowiki>". Verifiggà l\'innòmu insiriddu.',
'nouserspecified'            => "È nezzessàriu ippizzificà un'innòmu utenti.",
'wrongpassword'              => "La paràura d'órdhini insiridda nò è curretta. Riprubà.",
'wrongpasswordempty'         => "La paràura d'órdhini insiridda è biodda. Riprubà.",
'passwordtooshort'           => "La paràura d'órdhini insiridda è troppu brebi.
Débi cuntinì arumancu {{PLURAL:$1|1 caràtteri|$1 caràtteri}} e assé dibessa da l'innòmmu utenti.",
'mailmypassword'             => "Invia una noba paràura d'órdhini pa postha erettrònica",
'passwordremindertitle'      => "Saivvìziu promimória paràura d'órdhini di {{SITENAME}}",
'passwordremindertext'       => "Calchunu (forsi tu, cu' l'indirizzu IP \$1) à dumandaddu l'inviu d'una noba paràura d'órdhini pa l'intradda a {{SITENAME}} (\$4).
La paràura d'órdhini pa l'utenti \"\$2\" è isthadda impusthadda a \"\$3\".
È opporthunu eseguì un'intradda cantu primma e ciambà la paràura d'órdhini immediatamenti.

Si nò sei isthaddu tu a fà la prigonta, oppuru ài acciapaddu la paràura d'órdhini e nò desideri più la ciambà, pói ignorà chisth'imbasciadda e continuà a usà la paràura d'órdhini véccia.",
'noemail'                    => 'Nisciunu indirizzu di postha erettrònica registhraddu pa l\'utenti "$1".',
'passwordsent'               => 'Una noba paràura d\'órdhini è isthadda inviadda a l\'indirizzu di postha erettrònica registhraddu pa l\'utenti "$1".
Pa piazeri, effettua una intradda nò appèna la rizzibi.',
'blocked-mailpassword'       => 'Pa pribinì abusi, nò è cunsintiddu usà la funzioni "Invia noba paràura d\'órdhini" da un indirizzu IP broccaddu.',
'eauthentsent'               => "Un'imbasciadda di cunfèimma è insthadda inviadda a l'indirizzu fruniddu. Pa abirità l'inviu d'imbasciaddi pa chistha registhrazioni è nezzessàriu sighì l'isthruzioni indicaddi pa cunfèimmà d'assé lu legìttimu titurari di l'indirizzu.",
'throttled-mailpassword'     => 'Una noba paràura d\'órdhini è già isthadda inviadda da mancu di {{PLURAL:$1|ora|$1 ori}}.
Pa pribinì abusi, la funzioni "Invia noba paràura d\'órdhini" pò assé usadda soru\'na voltha dugna {{PLURAL:$1|ora|$1 ori}}.',
'mailerror'                  => "Errori i' l'inviu di l'imbasciadda: $1",
'acct_creation_throttle_hit' => 'Semmu dipiazuddi, ma ài già criaddu $1 registhrazioni. Nò pói crianni althri.',
'emailauthenticated'         => "L'indirizzu di postha erettrònica è isthaddu cunfèimmaddu lu $2 a li $3.",
'emailnotauthenticated'      => "L'indirizzu di postha erettrònica nò è ancora isthaddu autentiggaddu. Nò sarani inviaddi imbasciaddi di postha erettrònica attrabessu li sighenti funzioni.",
'noemailprefs'               => "Indicà un'indirizzu di postha erettrònica pà attibà chisthi funzioni.",
'emailconfirmlink'           => 'Cunfèimmà lu proprio indirizzu di postha erettrònica',
'invalidemailaddress'        => "L'indirizzu di postha erettrònica indicaddu à un fuimmaddu nò vàriddu.
Insirì un'indirizzu vàriddu o ibbiudà la casella.",
'accountcreated'             => 'Registhrazioni criadda',
'accountcreatedtext'         => "È isthadda criadda un'intradda pa l'utenti $1.",
'createaccount-title'        => "Criazioni di un'intradda a {{SITENAME}}",
'createaccount-text'         => 'Calchunu à criaddu una registhrazioni a {{SITENAME}} ($4) pa contu di "$2", cun la paràura d\'órdhini "$3".
È opporthunu eseguì un\'intradda cantu primma e ciamballa immediatamenti.

Si la registhrazioni è isthadda criadda pa un\'errori, pói ignorà chisth\'imbasciadda.',
'loginlanguagelabel'         => 'Linga: $1',

# Password reset dialog
'resetpass'           => "Ciamba paràura d'órdhini",
'resetpass_announce'  => "L'intradda è isthadda effettuadda cun un còdizi timpuràniu, inviaddu via postha erettrònica.

Pa cumprità la registhrazioni è nezzessàriu impusthà una noba paràura d'órdhini inogghi:",
'resetpass_text'      => '<!-- Aggiungi lu testhu inogghi -->',
'resetpass_header'    => "Ciamba paràura d'órdhini di la registhrazioni",
'oldpassword'         => "Véccia paràura d'órdhini:",
'newpassword'         => "Noba paràura d'órdhini:",
'retypenew'           => "Turra a ischribì la noba paràura d'órdhini:",
'resetpass_submit'    => "Impustha la paràura d'órdhini e intra",
'resetpass_success'   => "La paràura d'órdhini tóia è isthadda mudìfiggadda. Abà sei intrendi...",
'resetpass_forbidden' => "No è pussìbiri mudifiggà li paràuri d'órdhini in {{SITENAME}}.",

# Edit page toolbar
'bold_sample'     => 'Grassetu',
'bold_tip'        => 'Grassetu',
'italic_sample'   => 'Cursibu',
'italic_tip'      => 'Cursibu',
'link_sample'     => 'Tìturu di lu cullegamentu',
'link_tip'        => 'Cullegamentu internu',
'extlink_sample'  => 'http://www.example.com tìturu di lu cullegamentu',
'extlink_tip'     => 'Cullegamentu esthernu (nota lu prefissu http:// )',
'headline_sample' => 'Intisthazioni',
'headline_tip'    => 'Sottu-intisthazioni',
'math_sample'     => 'Insirì la fòimmura inogghi',
'math_tip'        => 'Fòimmura matemàtigga (LaTeX)',
'nowiki_sample'   => 'Insirì lu testhu nò fuimmaddaddu inogghi',
'nowiki_tip'      => 'Ignora la fuimmaddazioni wiki',
'image_sample'    => 'Esempiu.jpg',
'image_tip'       => 'File incoipuraddu',
'media_sample'    => 'Esempiu.ogg',
'media_tip'       => 'Cullegamentu a file mùrthimediari',
'sig_tip'         => 'Fimma cun data e ora',
'hr_tip'          => 'Lìnia orizontari (usà cun moderazioni)',

# Edit pages
'summary'                          => 'Oggettu:',
'subject'                          => 'Tìturu:',
'minoredit'                        => 'Chistha è una mudìfigga minori',
'watchthis'                        => "Aggiungi a l'abbaidaddi ippiziari",
'savearticle'                      => 'Saivva la pagina',
'preview'                          => 'Antiprimma',
'showpreview'                      => 'Visuarizza antiprimma',
'showlivepreview'                  => "Funzioni ''Live preview''",
'showdiff'                         => 'Musthra ciambamenti',
'anoneditwarning'                  => "'''Attinzioni:''' Intradda nò effettuadda. I' la cronologia di la pàgina sarà rigisthraddu l'indirizzu IP tóiu.",
'missingsummary'                   => "'''Promimória:''' Nò hai ippizzificaddu l'oggettu di la mudìfigga. Turrendi à incalchà '''Saivva la pàgina''' lu mudìfigga sarà saivvadda cun l'oggettu bioddu.",
'missingcommenttext'               => 'Insirì un cummentu in giossu.',
'missingcommentheader'             => "'''Promimória:''' Nò hai ippizzificaddu l'intisthazioni di chisthu cummentu. Turrendi à incalchà '''Saivva la pagina''' lu mudìfigga sarà saivvadda chena intisthazioni.",
'summary-preview'                  => 'Antiprimma oggettu:',
'subject-preview'                  => 'Antiprimma oggettu/intisthazioni:',
'blockedtitle'                     => 'Utenti broccaddu.',
'blockedtext'                      => "'''Chisth'innòmmu utenti o indirizzu IP so isthaddi broccaddi.'''

Lu broccu è isthaddu dizzisu da $1. La rasgioni frunidda è: ''$2''.

* Prinzìpiu di lu broccu: $8
* Ischadènzia: $6
* Duradda: $7

Pói ciamà $1 o un'althru [[{{MediaWiki:Grouppage-sysop}}|amministhradore]] pa ciaramiddà di lu broccu.

Attinzioni chi la funzioni '''Ischribì a l'utenti''' nò è attiba si nò è isthaddu rigisthraddu un'indirizzu di postha erettrònica variddu i' li [[Special:Preferences|prifirenzi]].

Si vói ciaramiddanne, pa piazeri prizzisa sempri lu nùmaru di lu broccu (ID #$5) e l'indirizzu IP tóiu ($3).",
'autoblockedtext'                  => "Chisthu indirizzu IP è isthaddi broccaddu automatiggamenti parchí cundibisu cu' un'althru utenti, broccaddu da $1.

La rasgioni frunidda pa lu broccu è:

:''$2''

* Prinzìpiu di lu broccu: $8
* Ischadènzia: $6

Pói ciamà $1 o un'althru [[{{MediaWiki:Grouppage-sysop}}|amministhradore]] pa ciaramiddà di lu broccu.

Attinzioni chi la funzioni '''Ischribì a l'utenti''' nò è attiba si nò è isthaddu rigisthraddu un'indirizzu di postha erettrònica variddu i' li [[Special:Preferences|prifirenzi]].

Si vói ciaramiddanne, pa piazeri prizzisa sempri lu nùmaru di lu broccu (ID #$5).",
'blockednoreason'                  => 'nisciuna mutibazioni indicadda',
'blockedoriginalsource'            => "Inogghi è musthraddu lu codizi di la pagina '''$1''':",
'blockededitsource'                => "Inogghi so musthraddi li '''mudìfigghi arriggaddi''' a la pagina '''$1''':",
'whitelistedittitle'               => 'È nezzessariu intrà pa mudifiggà li pagini',
'whitelistedittext'                => 'Pa mudìfiggà li pàgini è nezzessàriu $1.',
'confirmedittext'                  => "Pa assé abiritaddi a la mudìfigga di li pàgini è nezzessàriu cunfèimma lu proprio indirizzu di postha erettrònica. Pa impusthà e cunfèimmà l'indirizzu usà li [[Special:Preferences|prifirenzi]].",
'nosuchsectiontitle'               => 'La sezzioni nò esisthi',
'nosuchsectiontext'                => "S'è prubendi a mudìfiggà una sezzioni inesisthenti.",
'loginreqtitle'                    => 'Pa mudìfiggà chistha pàgina è nezzessàriu intrà',
'loginreqlink'                     => 'intra',
'loginreqpagetext'                 => 'Pa vidé althri pàgini è nezzessàriu $1.',
'accmailtitle'                     => "Paràura d'órdhini inviadda.",
'accmailtext'                      => "Una paràura d'órdhini giniradda casuarmenti pa [[User talk:$1|$1]] è isthadda inviadda a $2.

La paràura d'órdhini pa chistha noba registhrazioni pò assé mudifiggadda a l'intradda i' la pagina pa ''[[Special:ChangePassword|ciambà la paràura d'órdhini]]''.",
'newarticle'                       => '(Nóbu)',
'newarticletext'                   => "Lu cullegamentu sighiddu curripondi a'na pàgina nò ancora esisthenti.

Si vói crià la pàgina abà, pói sùbidu ischribì in giossu (abbaidda li [[{{MediaWiki:Helppage}}|pàgini d'aggiuddu]] pà maggiori infuimmazioni).

S'ài sighiddu lu cullegamentu pa un'errori, è suffizenti incalchà lu buttoni '''Indareddu''' i' lu propriu nabiggadori.",
'anontalkpagetext'                 => "----''Chistha è la pàgina di dischussioni di un'utenti anònimu, chi no ha ancora criaddu una registhrazioni o, in dugna modu, no la usa. Pa identifiggallu è dunca nezzessàriu usà lu sóiu nùmaru di l'indirizzu IP. L'indirizzi IP, parò, poni assé cundibisi da più utenti. Si sei un'utenti anònimu e vói chi li cummenti prisenti in chistha pàgina no si rifèrini a te, [[Special:UserLogin|crea una noba registhrazion o intra]] cu' chidda ch'hai già pa evità d'assé confusu cu' althri utenti anònimi in futuru.''",
'noarticletext'                    => "Abà chistha pàgina è biodda. È pussìbiri [[Special:Search/{{PAGENAME}}|zirchà chistu tituru]] i' l'althri pàgini di lu situ, <span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} zirchà i' li rigisthri curriraddi] oppuru [{{fullurl:{{FULLPAGENAME}}|action=edit}} mudifiggà la pagina abà]</span>.",
'userpage-userdoesnotexist'        => 'La registhrazioni "$1" nò curripundi a un\'utenti rigisthraddu. Verifiggà chi s\'aggia avveru gana di crià o mudìfiggà chistha pàgina.',
'clearyourcache'                   => "'''Nota:''' daboi abé saivaddu è nezzessàriu pulì la mimória cache di lu propriu nabiggadori pà vidé li ciambamenti. Pa '''Mozilla / Firefox / Safari''': fà clic i Ricàrrigga incalchendi lu buttoni di li maiuschuri, oppuru incalchà ''Ctrl-Maiusc-R'' (''Cmd-Maiusc-R'' i Mac); pa '''Internet Explorer:''' mantinì incalchaddu lu tasthu ''Ctrl'' mentri s'incalcha lu buttoni ''Aggiorna'' o incalchà ''Ctrl-F5''; pa '''Konqueror''': incalchà lu buttoni ''Ricarica'' o lu tasthu ''F5''; pa '''Opera''' pò assé nezzessàriu ibbuiddà cumpretamenti la mimória cache da lu menù ''Strumenti → Preferenze''.",
'usercssyoucanpreview'             => "'''Suggerimentu:''' Usa lu buttoni '''Visuarizza antiprimma''' pa prubà li nobi CSS primma di sàivvaddi.",
'userjsyoucanpreview'              => "'''Suggerimentu:''' Usa lu buttoni '''Visuarizza antiprimma''' pa prubà li nobi JS primma di sàivvaddi.",
'usercsspreview'                   => "'''Ammitanti ch'è soru un'antiprimma di lu propriu CSS passunari; li mudìfigghi nò so ancora isthaddi sàivvaddi!'''",
'userjspreview'                    => "'''Ammitanti ch'è soru un'antiprimma pa prubà lu propriu JavaScript passunari; li mudìfigghi nò so ancora isthaddi sàivvaddi!'''",
'userinvalidcssjstitle'            => "'''Attinzioni:''' Nò isisthi nisciun aipettu gràficu \"\$1\". Amminta chi li pàgini pa li .css e .js passunari àni lu primu caràtteri di lu tìturu minori, cumenti {{ns:user}}:Foo/monobook.css e nò {{ns:user}}:Foo/Monobook.css.",
'updated'                          => '(Aggiornaddu)',
'note'                             => "'''NOTA:'''",
'previewnote'                      => "'''Attinzioni: chistha è soru un'antiprimma. Li mudifigghi a la pagina NÒ so ancora isthaddi saivvaddi!'''",
'previewconflict'                  => "L'antiprimma curripundi a lu testhu prisenti i' la casella di mudìfigga superiori e musthra la pàgina cumenti apparirà si s'à gana di incalchà abà '''Sàivva la pàgina'''.",
'session_fail_preview'             => "'''Semmu dipiazuddi, nò è isthaddu pussìbiri sàivva la mudìfiggà parchí sò andaddi pessi i dati reratibi a la sissioni. Si lu probrema continua, prubà a iscì e effettuà una noba intradda.'''",
'session_fail_preview_html'        => "'''Semmu dipiazuddi, no è isthaddu pussìbiri elaburà la mudìfigga parchì sò andaddi pessi li dati reratibi a la sissioni.'''

''Parchì in {{SITENAME}} è cunsintiddu l'usu di l'HTML chena limitazioni, l'antiprimma no è visuarizzadda, pa sigguriddai contru l'attacchi JavaScript.''

'''Si lu probrema prisisthi, pói prubà à iscì e turrà a intrà.'''",
'token_suffix_mismatch'            => "'''La mudìfigga nò è isthadda sàivvadda parchí lu nabiggadori à musthraddu di gesthì in modu erraddu i caràtteri di punteggiaddura i' lu identifigganti di la mudìfigga. Pa evità una pussìbiri corruzioni di lu testhu di la pàgina, è isthadda rifiutadda l'intrea mudìfigga. Chistha situazioni pó verifiggassi, calch’e voltha, candu so usaddi zerthuni sivvìzi di proxy anònimi via reti chi àni di l'errori.'''",
'editing'                          => 'Mudifigga di $1',
'editingsection'                   => 'Mudifigga di $1 (sezzioni)',
'editingcomment'                   => 'Mudifigga di $1 (cummentu)',
'editconflict'                     => "Cuntrasthu d'edizioni i $1",
'explainconflict'                  => "Un'althru utenti à sàivvaddu una noba versioni di la pàgina primma di lu saivvatàggiu tóiu.
La casella di mudìfigga superiori cunteni lu testhu di la pàgina attuarmenti in lìnia, cumenti è isthadda mudìfiggadda da l'althru utenti.
La versioni cu' li mudìfigghi tói è i' la casella di mudìfigga in giossu.
Si vói cunfèimmalli, dévi arriggà li mudìfigghi tói i' lu testhu esisthenti (casella superiori).
'''Soru''' lu testhu i' la casella superiori sarà sàivvaddu candu tu incalcharé \"Sàivva la pàgina\".",
'yourtext'                         => 'Lu testhu tóiu',
'storedversion'                    => 'La versioni mimurizadda',
'nonunicodebrowser'                => "'''ATTINZIONI: Lu nabiggadori tóiu nò è cumpatìbiri cu' li caràtteri Unicode. Pa cunsintì la mudìfigga di li pàgini chena crià incunvinienti, i caràtteri nò ASCII so visuarizzaddi i' la casella di mudìfigga cumenti còdizi esadezimari.'''",
'editingold'                       => "'''ATTINZIONI: Sei mudìfigghendi una versioni di la pàgina nò aggiornadda. Si vói saivvàlla tutti i ciambamenti arriggaddi daboi chistha ribisioni sarani pessi!'''",
'yourdiff'                         => 'Diffarènzi',
'copyrightwarning'                 => "Nota: tutti li cuntributi a {{SITENAME}} so rilassaddi i la licènzia d'usu $2 (vedi $1 pa maggiori dettàgli). Si nò vói chi li testhi tói siani mudìfiggaddi e disthribuiddi a cassisia chena l'autorizzazioni tóia, nò l'invia à {{SITENAME}}. <br />
Inviendi lu testhu ài la ripunsabiriddai chi lu testhu sia toiu oppuru sia i lu pùbbriggu dumìniu.

'''NÒ INVIÀ MATERIARI CUBERTHU DA DIRITTU D'AUTORI CHENA AUTORIZZAZIONI!'''",
'copyrightwarning2'                => "Nota: tutti li cuntributi inviaddi a {{SITENAME}} pòni assé mudìfiggaddi o canzilladdi da l'althri utenti. Si nò vói chi li testhi tói siani mudìfiggaddi e disthribuiddi a cassisia chena l'autorizzazioni tóia, nò l'invia à chisthu situ.<br />
Inviendi lu testhu ài la ripunsabiriddai chi lu testhu sia toiu oppuru sia i lu pùbbriggu dumìniu (vedi $1 pa maggiori dettàgli).

'''NÒ INVIÀ MATERIARI CUBERTHU DA DIRITTU D'AUTORI CHENA AUTORIZZAZIONI!'''",
'longpagewarning'                  => "'''ATTINZIONI: Chistha pàgina è longa $1 kilobyte; zerthuni nabiggadori pudiani abé dei prubremi. Si pussìbiri suddibidì la pàgina in sezzioni o sottupàgini minori.'''",
'longpageerror'                    => "'''ERRORI: Lu testhu ch'ài inviaddu è longu $1 kB (kilobytes), più di la misura massima cunsintidda di $2 kB. Lu testhu nò pò assé sàivvaddu.'''",
'readonlywarning'                  => "'''ATTINZIONI: La bancadati è isthadda broccadda pa manutinzioni, dunca abà è impussìbiri saivvà li mudìfigghi. Pa no pirdhilli, copia lu testhu mudifiggaddu in un file i' lu to' elaburaddori e torra a saivvallu candu la bancadati sarà ibbruccadda.'''",
'protectedpagewarning'             => "'''ATTINZIONI: Chista pàgina è isthadda broccadda parchì soru l'utenti cun pribiréggi di amministhradori possiano mudìfiggarla.'''",
'semiprotectedpagewarning'         => "'''Nota:''' Chista pàgina è isthadda broccadda parchì soru li utenti registhraddi possiano mudìfiggarla.",
'cascadeprotectedwarning'          => "'''Attinzioni:''' Chistha pàgina è isthadda broccadda in modu chi soru l'utenti cun pribiréggi di amministhradori possiano mudìfiggarla. Lu chi avvini parchí la pàgina è incrusa {{PLURAL:\$1|i la pàgina indicadda ..., ch'è isthadda prutiggidda|i li pàgini indicaddi ..., chi so isthaddi prutiggiddi}} chirriendi la prutizioni \"ricussiba\":",
'titleprotectedwarning'            => "'''ATTINZIONI: Chistha pàgina è isthadda broccadda in modu chi soru zerthuni utenti possiano crialla.'''",
'templatesused'                    => 'Mudelli utirizaddi in chistha pàgina:',
'templatesusedpreview'             => "Mudelli utirizaddi in chisth'antiprimma:",
'templatesusedsection'             => 'Mudelli utirizaddi in chistha sezzioni:',
'template-protected'               => '(prutiggiddu)',
'template-semiprotected'           => '(mezu-prutiggiddu)',
'hiddencategories'                 => 'Chistha pagina appartheni a {{PLURAL:$1|una categuria cuadda|$1 categurì cuaddi}}:',
'edittools'                        => '<!-- Testhu chi appari in giossu lu mòdulu di mudìfiga e di carriggamentu. -->',
'nocreatetitle'                    => 'Criazioni di li pàgini limitadda',
'nocreatetext'                     => '{{SITENAME}} à limitaddu la pussibiliddai di crià nobi pagini a li sori utenti registhraddi. È pussìbiri turrà indareddu e mudìfiggà una pàgina esisthenti, oppuru [[Special:UserLogin|intrà o crià una noba registhrazioni]].',
'nocreate-loggedin'                => 'No si diponi di li pimmissi nezzessàri pa crià nobi pàgini in {{SITENAME}}.',
'permissionserrors'                => 'Errori i li pimmissi',
'permissionserrorstext'            => "Nò si diponi di li pimmissi nezzessàri a eseguì l'azioni dumandadda, pa {{PLURAL:$1|lu sighenti mutibu|li sighenti mutibi}}:",
'permissionserrorstext-withaction' => 'Nò si diponi di li primmissi nezzessàri pa $2, pa {{PLURAL:$1|lu sighenti mutibu|li sighenti mutibi}}:',
'recreate-moveddeleted-warn'       => "'''Attinzioni: s'è pa ricrià una pàgina già canzilladda in passadu.'''

S'azzirthà chi sia avveru opporthunu continuà a mudìfiggà chistha pàgina. L'erencu di li reratibi canzilladduri vèni ripurthaddu inogghi pa cumudiddai:",

# "Undo" feature
'undo-success' => "Chistha mudìfigga pò assé annulladda. Verifiggà lu sighenti cuntrasthu prisintaddu pa s'azzirthà chi lu cuntinuddu curripundi a cantu disizaddu e dunca saivvà li mudìfigghi pa cumprità la procedura di annullamentu.",
'undo-failure' => "Impussìbiri annullà la mudìfigga a càusa d'un cuntrasthu cun mudìfigghi intermédi.",
'undo-summary' => 'Annulladda la mudìfigga $1 di [[Special:Contributions/$2|$2]] ([[User talk:$2|Dischussioni]])',

# Account creation failure
'cantcreateaccounttitle' => 'Impussìbiri registhrà un utenti',
'cantcreateaccount-text' => "Criazioni di registhrazioni da chistu indirizzu IP ('''$1''') è isthadda broccadda da [[User:$3|$3]].

La rasgioni frunidda da $3 è ''$2''",

# History pages
'viewpagelogs'           => 'Visuarizza li rigisthri reratibi a chistha pàgina.',
'nohistory'              => 'Cronologia di li versioni di chistha pàgina nò riperìbiri.',
'currentrev'             => 'Versioni currenti',
'currentrev-asof'        => 'Versioni currenti di li $1',
'revisionasof'           => 'Versioni di lu $1',
'revision-info'          => 'Versioni di lu $1, autori: $2',
'previousrevision'       => '← Versioni mancu rizzenti',
'nextrevision'           => 'Versioni più rizzenti →',
'currentrevisionlink'    => 'Versioni currenti',
'cur'                    => 'curr',
'next'                   => 'sig',
'last'                   => 'priz',
'page_first'             => 'primma',
'page_last'              => 'ulthima',
'histlegend'             => "Cunfrontu i li versioni: isciubarà li caselli curripundenti a li versioni disizaddi e incalchà Inviu o lu buttoni in giossu.

Ippiegazioni: (curr) = diffarènzi cu la versioni currenti, (priz) = diffarènzi cu la versioni prizzidenti, '''m''' = mudìfigga minori, '''b''' = mudìfigga d'un bot, '''N''' = pàgina noba",
'history-fieldset-title' => "Ischurri i' la cronologia",
'histfirst'              => 'Primma',
'histlast'               => 'Ulthima',
'historysize'            => '({{PLURAL:$1|1 byte|$1 bytes}})',
'historyempty'           => '(biodda)',

# Revision feed
'history-feed-title'          => 'Cronologia',
'history-feed-description'    => 'Cronologia di la pàgina i chisthu situ',
'history-feed-item-nocomment' => '$1 lu $2',
'history-feed-empty'          => 'La pàgina dumandadda no isisthi; pudia assé isthadda canzilladda da lu situ o rinuminadda. Verifiggà cu la [[Special:Search|pàgina di zercha]] si vi so pàgini nobi.',

# Revision deletion
'rev-deleted-comment'         => '(cummentu buggaddu)',
'rev-deleted-user'            => '(innòmu utenti buggaddu)',
'rev-deleted-event'           => '(azioni di lu rigisthru buggadda)',
'rev-deleted-text-permission' => "Chistha versioni di la pàgina è isthadda buggadda da l'archìbi visìbiri a lu pùbbriggu.
Cunsulthà lu [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} rigisthru di canzilladdura] pa althri dettàgli.",
'rev-deleted-text-view'       => "Chistha versioni di la pàgina è isthadda buggadda da l'archìbi visìbiri a lu pùbbriggu.
Lu testhu pò assé visuarizzaddu soru da l'amministhradori di lu situ.
Cunsulthà lu [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} rigisthru di canzilladdura] pa althri dettàgli.",
'rev-delundel'                => 'musthra/cua',
'revisiondelete'              => 'Canzella o ricùpara versioni',
'revdelete-nooldid-title'     => 'Versioni nò ippizzificadda',
'revdelete-nooldid-text'      => "No ài ippizzificaddu la o li versioni di la pàgina i' li quari eseguì chistha funzioni; pò assé chi nò esisthini.",
'revdelete-show-file-submit'  => 'Si',
'revdelete-selected'          => "'''{{PLURAL:$2|Versioni sciubaradda|Versioni sciubaraddi}} di [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Eventu di lu rigisthru sciubaraddu|Eventi di lu rigisthru sciubaraddi}}:'''",
'revdelete-legend'            => "Impustha li sighenti limitazioni i'li versioni canzilladdi:",
'revdelete-hide-text'         => 'Cua lu testhu di la versioni',
'revdelete-hide-image'        => 'Cua li cuntinuddi di lu file',
'revdelete-hide-name'         => 'Cua azioni e oggettu di la matessi',
'revdelete-hide-comment'      => "Cua l'oggettu di la mudìfigga",
'revdelete-hide-user'         => "Cua l'innòmmu o l'indirizzu IP di l'autori",
'revdelete-hide-restricted'   => "Apprica chisthi limitazioni puru a l'amministhradori e brocca chistha interfàccia",
'revdelete-suppress'          => "Cua l'infuimmazioni puru a l'amministhradori",
'revdelete-unsuppress'        => "Elimina li limitazioni i' li ribisioni turraddi che primma",
'revdelete-log'               => 'Cummentu pa lu rigisthru:',
'revdelete-submit'            => 'Apprica a la ribisioni isciubaradda',
'revdelete-logentry'          => 'à mudìfiggaddu la visibiriddai pa una ribisioni di [[$1]]',
'logdelete-logentry'          => "à mudìfiggaddu la visibiriddai di l'eventu [[$1]]",
'revdelete-success'           => "'''Visibiriddai di la ribisioni impusthadda.'''",
'logdelete-success'           => "'''Visibiriddai di l'eventu impusthadda.'''",
'revdel-restore'              => 'Ciamba la visibiriddai',
'revdelete-content'           => 'cuntinuddu',
'revdelete-hid'               => 'cua $1',
'revdelete-unhid'             => 'rindi visìbiri $1',
'revdelete-log-message'       => '$1 pa $2 {{PLURAL:$2|ribisioni|ribisioni}}',
'logdelete-log-message'       => '$1 pa $2 {{PLURAL:$2|avvinimentu|avvinimenti}}',

# History merging
'mergehistory'                     => 'Unioni cronologi',
'mergehistory-box'                 => 'Unì la cronologia di dui pàgini:',
'mergehistory-from'                => "Pàgina d'orìgini:",
'mergehistory-into'                => 'Pàgina di disthinazioni:',
'mergehistory-list'                => "Cronologia a la quari è appricabiri l'unioni",
'mergehistory-go'                  => 'Musthra li mudìfigghi chi pòni assé uniddi',
'mergehistory-submit'              => 'Unì li ribisioni',
'mergehistory-empty'               => 'Nisciuna ribisioni da unì.',
'mergehistory-success'             => '$3 {{PLURAL:$3|ribisioni di [[:$1]] è isthadda unidda|ribisioni di [[:$1]] so isthaddi uniddi}} a la cronologia di [[:$2]].',
'mergehistory-fail'                => 'Impussìbiri unì li cronologi. Verifiggà la pàgina e li parametri timpurari.',
'mergehistory-no-source'           => "La pàgina d'orìgini $1 nò isisthi.",
'mergehistory-no-destination'      => 'La pàgina dI disthinazioni $1 nò isisthi.',
'mergehistory-invalid-source'      => "La pàgina d'orìgini débi abé un tìturu currettu.",
'mergehistory-invalid-destination' => 'La pàgina di disthinazioni débi abé un tìturu currettu.',

# Merge log
'mergelog'           => "Rigisthru di l'unioni",
'pagemerge-logentry' => "ha uniddu [[$1]] a [[$2]] (ribisioni finz'a $3)",
'revertmerge'        => 'Anulla unioni',
'mergelogpagetext'   => "Inogghi v'è una listha di l'ulthimi operazioni d'unioni di la cronologia d'una pàgina in un'althra.",

# Diffs
'history-title'           => 'Cronologia di li mudìfigghi di "$1"',
'difference'              => '(Diffarènzia i li ribisioni)',
'lineno'                  => 'Riga $1:',
'compareselectedversions' => 'Cunfronta li versioni sciubaraddi',
'editundo'                => 'annulla',
'diff-multi'              => '({{PLURAL:$1|Una ribisioni di mezu nò musthradda|$1 ribisioni di mezu nò musthraddi}}.)',

# Search results
'searchresults'             => 'Risulthaddi di la zercha',
'searchresults-title'       => 'Risulthaddi di la zercha di "$1"',
'searchresulttext'          => 'Pà maggiori infuimmazioni i cumenti zirchà i {{SITENAME}}, vèdi [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'            => 'Zercha di \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|tutti li pagini ch\'ischumenzani pa "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|tutti li pagini chi puntani a "$1"]])',
'searchsubtitleinvalid'     => "Zirchendi '''$1'''",
'titlematches'              => 'Curripundènzi i lu tìturu di li pagini',
'notitlematches'            => 'Nisciuna curripundènzia i lu tìturi di li pàgini',
'textmatches'               => 'Curripundènzi i lu teshu di li pàgini',
'notextmatches'             => 'Nisciuna curripundènzia i lu teshu di li pàgini',
'prevn'                     => 'prizzidenti {{PLURAL:$1|$1}}',
'nextn'                     => 'sighenti {{PLURAL:$1|$1}}',
'viewprevnext'              => 'Vèdi ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'         => 'Opzioni di zercha',
'searchhelp-url'            => 'Help:Indizi',
'searchprofile-everything'  => 'Tuttu',
'search-result-size'        => '$1 ({{PLURAL:$2|una paraura|$2 parauri}})',
'search-redirect'           => '(rinviu $1)',
'search-section'            => '(sezzioni $1)',
'search-suggest'            => 'Forsi zerchabi: $1',
'search-interwiki-caption'  => 'Prugetti fraddeddi',
'search-interwiki-default'  => 'Risulthaddi da $1:',
'search-interwiki-more'     => '(althru)',
'search-mwsuggest-enabled'  => 'cun suggerimenti',
'search-mwsuggest-disabled' => 'chena suggerimenti',
'search-relatedarticle'     => 'Risulthaddi curriraddi',
'searchrelated'             => 'curriraddi',
'searchall'                 => 'tutti',
'showingresults'            => "Accó {{PLURAL:$1|màssimu '''1''' risulthaddu|màssimu li '''$1''' risulthaddi}} à partì da lu nùmaru #'''$2'''.",
'showingresultsnum'         => "Accó {{PLURAL:$3|'''1''' risulthaddu |li '''$3''' risulthaddi}} à partì da lu nùmaru #'''$2'''.",
'nonefound'                 => "'''Nota''': Soru zerthi tipi di pàgina so zirchaddi i'otomàtiggu. Pruba ischribendi primma di la zercha toia ''all:'' pa zirchà tutti li cuntinuddi (dischussioni, mudelli, etc), o usa lu tipi di pàgina disizaddu cumenti prefissu.",
'powersearch'               => 'Zercha abanzadda',
'powersearch-legend'        => 'Zercha abanzadda',
'powersearch-ns'            => "Zercha i' li tipi di pàgina:",
'powersearch-redir'         => 'Listha rinvii',
'powersearch-field'         => 'Zercha',
'powersearch-toggleall'     => 'Tutti',
'powersearch-togglenone'    => 'Nisciunu',
'searchdisabled'            => 'Abà no pói zirchà in {{SITENAME}}; proba cun Google o sìmiri, ma ammèntaddi chi li cuntinuddi di {{SITENAME}} poni assé no aggiornaddi.',

# Quickbar
'qbsettings'               => 'Prifirenzi di lu menù',
'qbsettings-none'          => 'Nisciunu',
'qbsettings-fixedleft'     => 'Fissu a manca',
'qbsettings-fixedright'    => 'Fissu a drestha',
'qbsettings-floatingleft'  => 'Galliggianti a manca',
'qbsettings-floatingright' => 'Galliggianti a drestha',

# Preferences page
'preferences'               => 'Prifirenzi',
'mypreferences'             => "Li me' prifirenzi",
'prefs-edits'               => 'Mudìfigghi effettuaddi:',
'prefsnologin'              => 'Intradda nò effettuadda',
'prefsnologintext'          => 'Pa mudìfiggà li prifirenzi è nezzessàriu [[Special:UserLogin|intrà]].',
'changepassword'            => "Ciamba paràura d'órdhini",
'prefs-skin'                => 'Aipettu gràficu',
'skin-preview'              => 'antiprimma',
'prefs-math'                => 'Fòimmuri matemàtigghi',
'datedefault'               => 'Nisciuna prifirènzia',
'prefs-datetime'            => 'Data e ora',
'prefs-personal'            => 'Profiru utenti',
'prefs-rc'                  => 'Ulthimi mudìfigghi',
'prefs-watchlist'           => 'Abbaidaddi ippiziari',
'prefs-watchlist-days'      => "Nùmaru di dì da musthrà i'l'abbaidaddi ippiziari:",
'prefs-watchlist-edits'     => 'Nùmaru di mudìfigghi da musthrà cu li funzioni abanzaddi:',
'prefs-misc'                => 'Vari',
'saveprefs'                 => 'Saivva li prifirenzi',
'resetprefs'                => 'Rimpusthà li prifirènzi',
'prefs-editing'             => 'Casella di mudìfigga',
'rows'                      => 'Righi:',
'columns'                   => 'Curonni:',
'searchresultshead'         => 'Zercha',
'resultsperpage'            => 'Nùmaru di risulthaddi pa pàgina:',
'contextlines'              => 'Righi di testhu pa dugnunu risulthaddu:',
'contextchars'              => 'Nùmaru di caràtteri di cuntesthu:',
'stub-threshold'            => 'Varori mìminu pa i <a href="#" class="stub">cullegamenti a li sàgumi</a>:',
'recentchangesdays'         => 'Nùmaru di dì da musthrà i li ulthimi mudìfigghi:',
'recentchangescount'        => 'Numaru pridifiniddu di mudifigghi da musthrà:',
'savedprefs'                => 'Li tó prifirènzi so isthaddi saivvaddi.',
'timezonelegend'            => 'Fusu oràriu:',
'localtime'                 => 'Ora lucari:',
'timezoneoffset'            => 'Diffarènzia¹:',
'servertime'                => 'Ora di lu server:',
'guesstimezone'             => "Usa l'ora di lu tóiu nabiggadori",
'allowemail'                => "Cunsenti lu rizzibimentu di postha erettrònica d'althri utenti (1)",
'prefs-searchoptions'       => 'Opzioni di zercha',
'prefs-namespaces'          => 'Tipu di pagina',
'defaultns'                 => 'Namespace pridifiniddi pa zirchà:',
'default'                   => 'pridifiniddu',
'prefs-files'               => 'File',
'prefs-custom-css'          => 'CSS passunarizzaddu',
'prefs-custom-js'           => 'JS passunarizzaddu',
'youremail'                 => 'Indirizzu di postha erettrònica: *',
'username'                  => 'Innòmu utenti',
'uid'                       => 'ID utenti:',
'prefs-memberingroups'      => 'Membru di {{PLURAL:$1|lu gruppu|li gruppi}}:',
'prefs-registration'        => 'Data di registhrazioni:',
'yourrealname'              => 'Innòmu veru: *',
'yourlanguage'              => 'Linga:',
'yourvariant'               => 'Varianti:',
'yournick'                  => 'Ingiùgliu:',
'badsig'                    => "Errori i' la fimma nò standard, verifiggà i tag HTML.",
'badsiglength'              => "L'ingiugliu sciubaraddu è troppu longu, nò débi prubassà $1 {{PLURAL:$1|caratteri|caratteri}}.",
'yourgender'                => 'Generi:',
'gender-unknown'            => 'Nò ippizzificaddu',
'gender-male'               => 'Maschurinu',
'gender-female'             => 'Femmininu',
'email'                     => 'Indirizzu di postha erettrònica',
'prefs-help-realname'       => "* Innòmu veru (opzionale): si s'isciubara di l'insirì, sarà utirizaddu pa attribuì la paterniddai di li cuntinuddi inviaddi.",
'prefs-help-email'          => "Indirizzu di postha erettrònica (opzionale): cunsenti a l'utenti d'assé cuntattaddi attrabessu la propria pagina utenti o la reratiba pagina di dischussioni, chena dubé riverà la propria identiddai.",
'prefs-help-email-required' => "È nezzessàriu un'indirizzu du postha erettrònica.",

# User rights
'userrights'               => "Gisthioni di li primissi reratibi a l'utenti",
'userrights-lookup-user'   => 'Gisthioni di li gruppi utenti',
'userrights-user-editname' => "Insirì l'innòmu utenti:",
'editusergroup'            => 'Mudìfigga gruppi utenti',
'editinguser'              => "Mudifigga di li diritti di l'utenti '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup' => 'Mudìfigga gruppi utenti',
'saveusergroups'           => 'Sàivva gruppi utenti',
'userrights-groupsmember'  => 'Appartheni a li gruppi:',
'userrights-reason'        => 'Mutibu di la mudìffiga:',
'userrights-no-interwiki'  => "Nò ài lu primmissu pa mudìfiggà li diritti di l'utenti i' althri siti.",
'userrights-nodatabase'    => 'La bancadati $1 nò isisthi o nò è in lucari.',
'userrights-nologin'       => 'Débi [[Special:UserLogin|intrà]] cumenti amministhradori pa assignà li diritti utenti.',
'userrights-notallowed'    => "La registhrazioni tóia nò à lu primissu d'assignà li diritti utenti.",

# Groups
'group'               => 'Gruppu:',
'group-user'          => 'Utenti',
'group-autoconfirmed' => 'Utenti autocunfèimmaddi',
'group-bot'           => 'Bot',
'group-sysop'         => 'Amministhradori',
'group-bureaucrat'    => 'Buròcrati',
'group-suppress'      => 'Ibbàgli',
'group-all'           => '(utenti)',

'group-user-member'          => 'Utenti',
'group-autoconfirmed-member' => 'Utenti autocunfèimmaddu',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Amministhradori',
'group-bureaucrat-member'    => 'Buròcrati',
'group-suppress-member'      => 'Ibbàgliu',

'grouppage-autoconfirmed' => '{{ns:project}}:Utenti autocunfèimmaddi',
'grouppage-bot'           => '{{ns:project}}:Bot',
'grouppage-sysop'         => '{{ns:project}}:Amministhradori',
'grouppage-bureaucrat'    => '{{ns:project}}:Buròcrati',

# User rights log
'rightslog'      => 'Diritti di li utenti',
'rightslogtext'  => "Chisthu è lu rigisthru di lu mudìfigghi a li diritti assignaddi a l'utenti.",
'rightslogentry' => "à mudìfiggaddu l'apparthinènzia di $1 da lu gruppu $2 a lu gruppu $3",
'rightsnone'     => '(nisciunu)',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'mudìfiggà chistha pàgina',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|mudìfigga|mudìfigghi}}',
'recentchanges'                     => 'Ulthimi mudìfigghi',
'recentchanges-legend'              => 'Opzioni ulthimi mudifigghi',
'recentchangestext'                 => 'Chistha pàgina prisinta li mudìfigghi più rizzenti a li cuntinuddi di lu situ.',
'recentchanges-feed-description'    => 'Chisthu feed cunteni li mudìfigghi più rizzenti a li cuntinuddi di lu situ.',
'rcnote'                            => "Inogghi {{PLURAL:$1|è erencadda la mudìfigga più rizzenti arriggadda|so erencaddi li '''$1''' mudìfigghi più rizzenti arriggaddi}} a lu situ {{PLURAL:$2|i' l'ulthimi 24 ori|i' li '''$2''' dì passaddi}}; i dati so aggiornaddi a li $5 di lu $4.",
'rcnotefrom'                        => "Inogghi so erencaddi li mudìfigghi arriggaddi a parthì da '''$2''' (finz'a '''$1''').",
'rclistfrom'                        => 'Musthra li mudìfigghi arriggaddi à partì da $1',
'rcshowhideminor'                   => '$1 li mudìfigghi minori',
'rcshowhidebots'                    => '$1 li bot',
'rcshowhideliu'                     => '$1 li utenti registhraddi',
'rcshowhideanons'                   => '$1 li utenti anònimi',
'rcshowhidepatr'                    => '$1 li mudìfigghi contrulladdi',
'rcshowhidemine'                    => "$1 li me' mudìfigghi",
'rclinks'                           => "Musthra li $1 mudìfigghi più rizzenti arriggaddi i' l'ulthimi $2 dì<br />$3",
'diff'                              => 'diff',
'hist'                              => 'cron',
'hide'                              => 'cua',
'show'                              => 'musthra',
'minoreditletter'                   => 'm',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => "[abbaidadda da {{PLURAL:$1|un'utenti|$1 utenti}}]",
'rc_categories'                     => 'Limita a li categuri (siparaddi da "|")',
'rc_categories_any'                 => 'Cassisia',
'newsectionsummary'                 => '/* $1 */ noba sezzioni',
'rc-enhanced-expand'                => 'Musthra dettagli (dumanda JavaScript)',
'rc-enhanced-hide'                  => 'Cua dettàgli',

# Recent changes linked
'recentchangeslinked'          => 'Mudìfigghi curriraddi',
'recentchangeslinked-feed'     => 'Mudìfigghi curriraddi',
'recentchangeslinked-toolbox'  => 'Mudìfigghi curriraddi',
'recentchangeslinked-title'    => 'Mudìfigghi curriraddi a "$1"',
'recentchangeslinked-noresult' => "Nisciuna mudìfigga a li pàgini curriraddi i' lu perìodu sciubaraddu.",
'recentchangeslinked-summary'  => "Chistha pàgina ippiziari musthra li mudìfigghi più rizzenti a li pàgini curriraddi a chidda sciubaradda (o a li membri di un'ippizzifica categuria). Li [[Special:Watchlist|pàgini abbaidaddi]] so evidenziaddi in '''grasseddu'''.",
'recentchangeslinked-page'     => 'Innòmmu di la pàgina:',
'recentchangeslinked-to'       => 'Musthra soru li mudifigghi a li pagini curriraddi a chidda isciubaradda',

# Upload
'upload'                      => 'Carrigga un file',
'uploadbtn'                   => 'Carrigga',
'reuploaddesc'                => 'Canzella lu carriggamentu e turra a lu mòdulu',
'uploadnologin'               => 'Intradda nò effettuadda',
'uploadnologintext'           => 'È nezzessàriu [[Special:UserLogin|intrà]] pa carriggà file.',
'upload_directory_read_only'  => "Lu server web nò è bonu à ischribì i' la carthella di carriggamentu ($1).",
'uploaderror'                 => "Errori i' lu carriggamentu",
'uploadtext'                  => "Usà lu mòdulu in giossu pà carriggà file.
Pà visuarizzà o zirchà li file già carriggaddi, cunsulthà  lu [[Special:FileList|rigisthru di li file carriggaddi]]. Carriggamenti e ischarriggamenti di file so registhraddi i' lu  [[Special:Log/upload|rigisthru di li carriggamenti]].

Pa insirì un'immàgina i'na pàgina, fà un cullegamentu cussì:
'''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki>''' ,
'''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|alt text]]</nowiki>''' ;
usà inveci
'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki>''' pà culligà direttamenti l'althri tipi di file.",
'upload-permitted'            => 'Fuimmaddi di file autorizaddi: $1.',
'upload-preferred'            => 'Fuimmaddi di file prifiriddi: $1.',
'upload-prohibited'           => 'Fuimmaddi di file pruibbiddi: $1.',
'uploadlog'                   => 'File carriggaddi',
'uploadlogpage'               => 'Rigisthru di li file carriggaddi',
'uploadlogpagetext'           => "Inogghi v'è l'erencu di l'ulthimi file cariggaddi.",
'filename'                    => 'Innòmu di lu file',
'filedesc'                    => 'Dettàgliu',
'fileuploadsummary'           => 'Dettàgli di lu file:',
'filestatus'                  => "Infuimmazioni i' lu copyright:",
'filesource'                  => 'Orìgini:',
'uploadedfiles'               => 'Erencu di li file carriggaddi',
'ignorewarning'               => "Ignora l'avvirthimentu e saivva cumenti si sia lu file",
'ignorewarnings'              => 'Ignora li imbasciaddi di avvirthimentu di lu sisthema',
'minlength1'                  => "Lu nommu di lu file débi assé cumposthu arumandu d'un caràtteri.",
'illegalfilename'             => 'L\'innòmmu "$1" cunteni caràtteri nò primmissi i\' li tìturi di li pàgini. Dà a lu file un\'innòmmu dibessu e prubà a carriggarlu di nobu.',
'badfilename'                 => 'L\'innòmmu di lu file è isthaddu ciambaddu in "$1".',
'filetype-badmime'            => 'Nò è cunsintiddu carriggà file di tipu MIME "$1".',
'filetype-unwanted-type'      => "'''\".\$1\"''' è un tipu di file digradiddu. Li file prifiriddi so di tipu \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' nò è un tipu di file primmissu. Li file primmissi so di tipu \$2.",
'filetype-missing'            => 'Lu file è chena esthensioni (cumenti ".jpg").',
'large-file'                  => 'Pa piazeri nò suparà li misuri di $1 pa ugni file; chisthu file è mannu $2.',
'largefileserver'             => 'Lu file supara li misuri cunsintiddi da la cunfigurazioni di lu server.',
'emptyfile'                   => "Lu file appena cariggaddu pari assé bioddu. Lu chi pudia assé dubuddu a un'errori i' l'innòmmu di lu file. Verifiggà chi s'aggia avveru gana di cariggà chisthu file.",
'fileexists'                  => "Un file cu' chisth'innòmmu isisthi già.
Verifiggà primma '''<tt>[[:$1]]</tt>''' si nò s'è sigguri di vurellu sobbraschribì.
[[$1|thumb]]",
'fileexists-extension'        => "Un file cu'un'innòmmu sìmmiri isisthi già; la sora diffarènzia è l'usu di li maiuschini i' l'esthensioni: [[$2|thumb]]
* Innòmmu di lu file cariggaddu: '''<tt>[[:$1]]</tt>'''
* Innòmmu di lu file esisthenti: '''<tt>[[:$2]]</tt>'''
Verifiggà ch'i dui file nò siani lu matessi o sciubarà un'innòmmu diffarènti pa lu file da carriggà.",
'fileexists-thumbnail-yes'    => "Lu file carriggaddu pari assé lu risulthaddu d'un'antiprimma ''(thumbnail)''. [[$1|thumb]]
Verifiggà, pa cunfrontu, lu file '''<tt>[[:$1]]</tt>'''.
S'è la matessi immàgina, i' li misuri originari, nò è nezzessàriu carriggà althri antiprimmi.",
'file-thumbnail-no'           => "L'innòmmu di lu file ischumenza cun '''<tt>$1</tt>'''. Pari assé lu risulthaddu d'un'antiprimma ''(thumbnail)''.
Si si diponi di l'immàgina i' la risoruzioni originari, pa piazeri carriggalla. A l'invessu, pa piazeri ciambà l'innòmmu di lu file.",
'fileexists-forbidden'        => "Un file cun chisthu innòmmu isisthi già. Turrà indareddu e mudìfiggà l'innòmmu cu' lu quari carriggà lu file. [[File:$1|thumb|center|$1]]",
'fileexists-shared-forbidden' => "Un file cun chisthu innòmmu isisthi già i l'archìbiu di li risuzzi mùrthimediari cundibisi. Turrà indareddu e mudìfiggà l'innòmmu cu' lu quari carriggà lu file. [[File:$1|thumb|center|$1]]",
'successfulupload'            => 'Carriggamentu cumpritaddu',
'uploadwarning'               => 'Avvisu di carriggamentu',
'savefile'                    => 'Saivva file',
'uploadedimage'               => 'ha carriggaddu "[[$1]]"',
'overwroteimage'              => 'ha carriggaddu una nóba versioni di "[[$1]]"',
'uploaddisabled'              => 'Semmu dipiazuddi, ma lu carriggamentu di file è timpuraniamenti suippesu.',
'uploaddisabledtext'          => "Lu carriggamentu di file nò è attibu i' {{SITENAME}}.",
'uploadscripted'              => 'Chistu file cunteni còdizi HTML o script, chi pudia assé interpretaddu mari da lu nabiggadori.',
'uploadvirus'                 => 'Chisthu file cunteni un virus! Dettàgli: $1',
'sourcefilename'              => "Nommu d'orìgini di lu file:",
'destfilename'                => 'Nommu di lu file di disthinazioni:',
'watchthisupload'             => "Aggiungi a l'abbaidaddi ippiziari",
'filewasdeleted'              => "Un file cun chist'innòmmu è già isthaddu cariggaddu e canzilladdu. Verifiggà $1 primma di turrà a cariggallu.",
'upload-wasdeleted'           => "'''Attenzioni: sei carrigghendi un file chi prizzidentementi è già isthaddu canzilladdu.'''

Pa piazeri verifigga si vói carriggà avveru chisthu file; inogghi v'è lu rigisthru cu' la mutibazioni di la canzilladdura:",
'filename-bad-prefix'         => "L'innòmmu di lu file chi sei carrigghendi ischumenza cun '''\"\$1\"''', ch'è un'innòmmu nò dischrittibu assignaddu automatiggamenti da li fotocameri. Pa piazieri sciubarà un'innòmmu più dischrittibu pa lu file tóiu.",

'upload-proto-error'      => 'Protocollu ibbagliaddu',
'upload-proto-error-text' => 'Pa lu cariggamentu "luntanu" è nezzessàriu ippizzificà indirizzi ch\'ischumenzani cun <code>http://</code> oppuru <code>ftp://</code>.',
'upload-file-error'       => 'Errori internu',
'upload-file-error-text'  => "S'è verifiggaddu un errori internu duranti la criazioni di un file timpuràniu i' lu server. Cuntattà un'[[Special:ListUsers/sysop|amministhradore]].",
'upload-misc-error'       => 'Errori ischunisciddu di carriggamentu',
'upload-misc-error-text'  => "S'è verifiggaddu un errori nò identifiggaddu duranti lu carriggamentu di lu file. Verifiggà chi l'URL sia curretta e atzessibili e turrà a prubà. Si lu probrema prisisthi, cuntattà un'amministhradore di sisthema.",

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL nò aggiumpibiri',
'upload-curl-error6-text'  => "Impussìbiri raggiugnì l'URL ippizzificaddu. Verifiggà chi l'URL sia ischrittu currettamenti e chi lu situ sia attibu.",
'upload-curl-error28'      => 'Tempu ischaduddu pa lu carriggamentu',
'upload-curl-error28-text' => "Lu situ à impiegaddu troppu tempu a rippundì. Verifiggà chi lu situ sia attibu, aisittà calchi minuti e turrà a prubà, pussibiimmenti candu v'è mancu genti.",

'license'            => "Licènzia d'usu:",
'nolicense'          => 'Nisciuna licènzia indicadda',
'license-nopreview'  => '(Antiprimma nò dipunìbiri)',
'upload_source_url'  => " (un'indirizzu vàriddu e pùbbriggu)",
'upload_source_file' => " (un file i' lu propriu elaburaddori)",

# Special:ListFiles
'listfiles_search_for'  => 'Zercha immàgini pa innòmu:',
'imgfile'               => 'file',
'listfiles'             => 'Listha di li file',
'listfiles_date'        => 'Data',
'listfiles_name'        => 'Innòmu',
'listfiles_user'        => 'Utenti',
'listfiles_size'        => 'Misuri in byte',
'listfiles_description' => 'Deschrizioni',

# File description page
'file-anchor-link'          => 'Immàgina',
'filehist'                  => 'Cronologia di lu file',
'filehist-help'             => "Fà clic i' un gruppu data/ora pa vidé lu file cumenti era i' lu mamentu indicaddu.",
'filehist-deleteall'        => 'canzella tuttu',
'filehist-deleteone'        => 'canzella',
'filehist-revert'           => 'turra che primma',
'filehist-current'          => 'currenti',
'filehist-datetime'         => 'Data/Ora',
'filehist-thumb'            => 'Miniadduri',
'filehist-thumbtext'        => 'Miniadduri di la virsioni di li $1',
'filehist-user'             => 'Utenti',
'filehist-dimensions'       => 'Misuri',
'filehist-filesize'         => 'Misuri di lu file',
'filehist-comment'          => 'Oggettu',
'imagelinks'                => 'Cullegamenti a lu file',
'linkstoimage'              => "{{PLURAL:$1|La sighenti pàgina pùnta|Li sighenti $1 pàgini pùntani}} a l'immàgina:",
'nolinkstoimage'            => "Nisciuna pàgina cunteni cullegamenti a l'immàgina.",
'sharedupload'              => 'Chisthu file prubeni da $1 e pó assé utirizaddu da althri prugetti.',
'uploadnewversion-linktext' => 'Carrigga una nóba versioni di chistu file',

# File reversion
'filerevert'                => 'Turra che primma $1',
'filerevert-legend'         => 'Ricupara file',
'filerevert-intro'          => "N'isthai pa ricuparà lu file '''[[Media:$1|$1]]''' a la [versioni $4 di lu $2, $3].",
'filerevert-comment'        => 'Oggettu:',
'filerevert-defaultcomment' => 'Turradda che primma la versioni di lu $1, $2',
'filerevert-submit'         => 'Turra che primma',
'filerevert-success'        => "'''Lu file [[Media:$1|$1]]''' è isthaddu ricuparaddu a la [versioni $4 di lu $2, $3].",
'filerevert-badversion'     => "Nò isisthono versioni lucari prizzidenti di lu file cu' la marchaddura oreria disizadda.",

# File deletion
'filedelete'             => 'Canzella $1',
'filedelete-legend'      => 'Canzella lu file',
'filedelete-intro'       => "N'isthai canzellendi '''[[Media:$1|$1]]'''.",
'filedelete-intro-old'   => "N'isthai canzillendi la versioni di '''[[Media:$1|$1]]''' di lu [$4 $2, $3].",
'filedelete-comment'     => 'Mutibazioni:',
'filedelete-submit'      => 'Canzella',
'filedelete-success'     => "Lu file '''$1''' è isthaddu canzilladdu.",
'filedelete-success-old' => '<span class="plainlinks">La versioni di lu $3, $2 di lu file \'\'\'[[Media:$1|$1]]\'\'\' è isthadda canzilladda.</span>',
'filedelete-nofile'      => 'Nò esisthi un file $1.',
'filedelete-nofile-old'  => "In archìbiu nò vi so versioni di '''$1''' cu' li caratterìsthighi indicaddi.",

# MIME search
'mimesearch'         => 'Zercha sigundu a lu tipu MIME',
'mimesearch-summary' => 'Chistha pàgina cunsenti di filthrà li file sigundu lu tipu MIME. Insirì li paràuri zirchaddi cumenti tipu/sottutipu e.g. <tt>image/jpeg</tt>.',
'mimetype'           => 'Tipu MIME:',
'download'           => 'ischarriggamentu',

# Unwatched pages
'unwatchedpages' => 'Pàgini nò abbaidaddi',

# List redirects
'listredirects' => 'Erencu di li rinvii',

# Unused templates
'unusedtemplates'     => 'Mudelli inutirizaddi',
'unusedtemplatestext' => "In chistha pàgina so erencaddi tutti li mudelli (cuntinuddi i' lu tipu di pàgina '''Mudellu''') chi nò so incrusi in nisciuna pàgina. Primma di canzillalli verifiggà chi li template nò àggiani althri cullegamenti ch'entrani.",
'unusedtemplateswlh'  => 'althri cullegamenti',

# Random page
'randompage'         => 'Una pàgina a casu',
'randompage-nopages' => "Nisciuna pàgina i'lu namespace isciubaraddu.",

# Random redirect
'randomredirect'         => 'Un rinviu a casu',
'randomredirect-nopages' => "Nisciun rinviu i' lu tipu di pàgina sciubaraddu.",

# Statistics
'statistics'              => 'Sthatisthigghi',
'statistics-header-users' => "Sthatisthigghi reratibi a l'utenti",
'statistics-pages'        => 'Pagini',
'statistics-mostpopular'  => 'Pàgini più visitaddi',

'disambiguations'      => 'Pàgini cu lu matessi innòmmu',
'disambiguationspage'  => 'Template:Matessi innòmmu',
'disambiguations-text' => "Li pàgini i' la sighenti listha cuntènani cullegamenti a '''pàgini cu' lu matessi innòmmu''' e nò a la rasgiòni a chi dubaristhia fà rifirimentu.<br />So cunsidaraddi pàgini cu lu matessi innòmmu tutti chissi chi cuntènini li mudelli erencaddi in [[MediaWiki:Disambiguationspage]].",

'doubleredirects'     => 'Rinvii doppi',
'doubleredirectstext' => 'Inogghi v\'è una listha di li pàgini chi puntani a pàgini di rinviu. Ogna riga cunteni i cullegamenti a lu primmu e sigundu rinviu, cumenti a la disthinazioni di lu sigundu rinviu, che noimmaimmenti è la pàgina "curretta" a la quari dubia puntà puru lu primmu rinviu.',

'brokenredirects'        => 'Rinvii ibbagliaddi',
'brokenredirectstext'    => 'Li rinvii sighenti puntani a pàgini inesisthenti:',
'brokenredirects-edit'   => 'mudifigga',
'brokenredirects-delete' => 'canzella',

'withoutinterwiki'         => "Pàgini chena cullegamenti p'althri linghi",
'withoutinterwiki-summary' => 'Li sighenti pàgini so chena cullegamenti a versioni in althri linghi (interwiki):',
'withoutinterwiki-submit'  => 'Musthra',

'fewestrevisions' => 'Pàgini cun mancu ribisioni',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|byte}}',
'ncategories'             => '$1 {{PLURAL:$1|categuria|categuri}}',
'nlinks'                  => '$1 {{PLURAL:$1|cullegamentu|cullegamenti}}',
'nmembers'                => '$1 {{PLURAL:$1|erementu|erementi}}',
'nrevisions'              => '$1 {{PLURAL:$1|ribisioni|ribisioni}}',
'nviews'                  => '$1 {{PLURAL:$1|visita|visiti}}',
'specialpage-empty'       => 'Chisthu rapporthu nò cunteni nisciun risulthaddu.',
'lonelypages'             => 'Pàgini òiffani',
'lonelypagestext'         => "Li sighenti pàgini so chena cullegamenti chi prubenani d'althri pàgini di {{SITENAME}}.",
'uncategorizedpages'      => 'Pàgini chena categuri',
'uncategorizedcategories' => 'Categuri nò categurizzaddi',
'uncategorizedimages'     => 'File chena categuri',
'uncategorizedtemplates'  => 'Mudelli chena categuri',
'unusedcategories'        => 'Categuri inutirizaddi',
'unusedimages'            => 'File inutirizaddi',
'popularpages'            => 'Pàgini più visitaddi',
'wantedcategories'        => 'Categuri dumandaddi',
'wantedpages'             => 'Pàgini più dumandaddi',
'mostlinked'              => 'Pàgini più riciamaddi',
'mostlinkedcategories'    => 'Categuri più riciamaddi',
'mostlinkedtemplates'     => 'Mudelli più utirizaddi',
'mostcategories'          => 'Pàgini cun più categuri',
'mostimages'              => 'File più riciamaddi',
'mostrevisions'           => 'Pàgini cun più ribisioni',
'prefixindex'             => 'Indizi di li bozi pa léttari a l’ischumenzu',
'shortpages'              => 'Pàgini più corthi',
'longpages'               => 'Pàgini più longhi',
'deadendpages'            => 'Pàgini chena iscidda',
'deadendpagestext'        => 'Li sighenti pàgini so chena cullegamenti bessu althri pàgini di {{SITENAME}}.',
'protectedpages'          => 'Pàgini prutiggiddi',
'protectedpagestext'      => "Inogghi v'è un'erencu di li pàgini prutiggiddi, di li quari è impididda la mudìfigga o l'ippusthamentu",
'protectedpagesempty'     => 'Abà nò vi so pàgini prutiggiddi.',
'protectedtitles'         => 'Tìturi prutiggiddi',
'protectedtitlestext'     => 'Li sighenti tìturi so prutiggiddi da la criazioni di pàgini nobi',
'protectedtitlesempty'    => 'Abà nò vi so tìturi prutiggiddi.',
'listusers'               => 'Erencu di li utenti',
'newpages'                => 'Pàgini più rizzenti',
'newpages-username'       => 'Nommu utenti:',
'ancientpages'            => 'Pàgini mancu rizzenti',
'move'                    => 'ippustha',
'movethispage'            => 'Ippustha chistha pàgina',
'unusedimagestext'        => "So pussìbiri cullegamenti a file d'althri siti, utirizendi l'indirizzu; chisthi pudiani dunca assé utilizzaddi puru si so i'l'erencu.",
'unusedcategoriestext'    => 'Li pàgini di li categuri indicaddi inogghi sò isthaddi criaddi ma nò cuntenani nisciuna pàgina né sottucateguria.',
'notargettitle'           => 'Dati mancanti',
'notargettext'            => "Nò ài ippizzificaddu una pàgina o un'utenti i' lu quari eseguì chistha funzioni.",
'pager-newer-n'           => '{{PLURAL:$1|1 più rizzenti|$1 più rizzenti}}',
'pager-older-n'           => '{{PLURAL:$1|1 mancu rizzenti|$1 mancu rizzenti}}',

# Book sources
'booksources'               => 'Rifirimenti di libri',
'booksources-search-legend' => 'Zercha rifirimenti di libri',
'booksources-go'            => 'Vai',
'booksources-text'          => "Inogghi v'è una listha di cullegamenti bessu siti estherni chi vindani libri nobi e usaddi, attrabessu li quari è pussìbiri uttinì maggiori infuimmazioni i' lu testhu zirchaddu.",

# Special:Log
'specialloguserlabel'  => 'Utenti:',
'speciallogtitlelabel' => 'Tìturu:',
'log'                  => 'Rigisthri',
'all-logs-page'        => 'Tutti li rigisthri',
'alllogstext'          => "Prisintazioni unifiggadda di li rigisthri reratibi a li operazioni di carriggamentu, canzilladdura, prutizioni, broccu e amministhrazioni di lu situ. Ribbendi li appósiddi campi si pò limità la visuarizzazioni a un'ippizzificu rigisthru, nommu utenti o pàgina.",
'logempty'             => 'Lu rigisthru nò cunteni erementi curripundenti.',
'log-title-wildcard'   => "Zercha li tìturi ch'ischuminzani cun",

# Special:AllPages
'allpages'          => 'Tutti li pàgini',
'alphaindexline'    => 'da $1 a $2',
'nextpage'          => 'Pàgini sighenti ($1)',
'prevpage'          => 'Pàgina prizzidenti ($1)',
'allpagesfrom'      => 'Musthra li pàgini a parthì da:',
'allpagesto'        => "Musthra li pagini finz'a:",
'allarticles'       => 'Tutti li pàgini',
'allinnamespace'    => 'Tutti li pàgini di lu namespace $1',
'allnotinnamespace' => 'Tutti lì pàgini, eschrusu lu namespace $1',
'allpagesprev'      => 'Prizzidenti',
'allpagesnext'      => 'Sighenti',
'allpagessubmit'    => 'Vai',
'allpagesprefix'    => "Musthra li pàgini ch'ischuminzani cun:",
'allpagesbadtitle'  => "Lu tìturu indicaddu pa la pàgina nò è vàriddu o cunteni prefissi interlinga o interwiki. Pudia puru cuntinì unu o più caràtteri chi nò si pudiani usà i' li tìturi.",
'allpages-bad-ns'   => 'Lu namespace "$1" nò isisthi i\' {{SITENAME}}.',

# Special:Categories
'categories'         => 'Categuri',
'categoriespagetext' => "Erencu cumpretu di li categuri prisenti i'lu situ.",

# Special:LinkSearch
'linksearch'    => 'Cullegamenti estherni',
'linksearch-ok' => 'Zercha',

# Special:ListUsers
'listusersfrom'      => "Musthra l'utenti parthendi da:",
'listusers-submit'   => 'Musthra',
'listusers-noresult' => 'Nisciun utenti curripundi a li critéri impusthaddi.',

# Special:Log/newusers
'newuserlogpage'          => 'Nobi utenti',
'newuserlog-create-entry' => "s'è rigisthraddu/a abà",

# Special:ListGroupRights
'listgrouprights-members' => '(erencu di li membri)',

# E-mail user
'mailnologin'     => "Nisciun indirizzu a lu quari invià l'imbasciadda.",
'mailnologintext' => "Pa invià imbasciaddi di postha erettrònica è nezzessàriu [[Special:UserLogin|intrà]] e abé registhraddu un'indirizzu variddu i' li propri [[Special:Preferences|prifirenzi]].",
'emailuser'       => "Ischribì a l'utenti",
'emailpage'       => "Invia un'imbasciadda di postha erettrònica a l'utenti",
'emailpagetext'   => "Si l'utenti à registhraddu un'indirizzu di postha erettrònica vàriddu i' li propri prifirenzi, lu mòdulu in giossu cunsenti d'ischribelli una sora imbasciadda. L'indirizzu indicaddu i' li prifirenzi di lu mandanti apparirà i' lu campu \"Da:\" di l'imbasciadda pa cunsintì a  lu disthinatàriu l'eventuari rippostha.",
'usermailererror' => "L'oggettu di l'imbasciadda à turraddu l'errori:",
'defemailsubject' => 'Imbasciadda da {{SITENAME}}',
'noemailtitle'    => 'Nisciun indirizzu di postha erettrònica',
'noemailtext'     => "Chistu utenti nò à indicaddu un'indirizzu postha erettrònica vàriddu, oppuru à sciubaraddu di nò rizzibì imbasciaddi di postha erettrònica da l'althri utenti.",
'emailfrom'       => 'Da:',
'emailto'         => 'A:',
'emailsubject'    => 'Oggettu:',
'emailmessage'    => 'Imbasciadda:',
'emailsend'       => 'Invia',
'emailccme'       => 'Invia in còpia a lu meu indirizzu.',
'emailccsubject'  => "Còpia di l'imbasciadda inviadda a $1: $2",
'emailsent'       => 'Imbasciadda inviadda',
'emailsenttext'   => "L'imbasciadda di postha erettrònica è isthadda inviadda.",

# Watchlist
'watchlist'            => 'Abbaidaddi ippiziari',
'mywatchlist'          => 'Abbaidaddi ippiziari',
'watchlistfor'         => "(pa '''$1''')",
'nowatchlist'          => "La listha di l'abbaidaddi ippiziari è biodda.",
'watchlistanontext'    => "Pa visuarizzà e mudìfiggà l'erencu di l'abbaidaddi ippiziari è nezzessàriu $1.",
'watchnologin'         => 'Intradda nò effettuadda',
'watchnologintext'     => "Pa mudìfiggà la listha di l'abbaidaddi ippiziari è nezzessàriu primma [[Special:UserLogin|intrà]].",
'addedwatch'           => "Pàgina aggiunta a la listha di l'abbaidaddi ippiziari",
'addedwatchtext'       => "La pàgina  \"[[:\$1]]\" è isthadda aggiunta a la propria [[Special:Watchlist|listha di l'abbaidaddi ippiziari]]. D'abà innanzi, tutti li mudìfigghi arriggaddi a la pàgina e a la sóia dischussioni sarani erencaddi in chidda listha; lu tìturu di la pàgina apparirà in '''grasseddu''' i' la pàgina
di l'[[Special:RecentChanges|ulthimi mudìfigghi]] pa rindiru più visìbiri.

Si daboi s'à gana d'eliminà la pàgina da la listha di l'abbaidaddi ippiziarii, fà clic i' \"nò sighì\" i' la barra in althu.",
'removedwatch'         => "Pàgina eliminadda da la listha di l'abbaidaddi ippiziari",
'removedwatchtext'     => 'La pàgina  "[[:$1]]" è isthadda eliminadda da la listha di l\'abbaidaddi ippiziari.',
'watch'                => 'Sighi',
'watchthispage'        => 'Sighi chistha pàgina',
'unwatch'              => 'Nò sighì',
'unwatchthispage'      => 'Zissa di sighì',
'notanarticle'         => 'Chistha pàgina nò è una bozi',
'notvisiblerev'        => 'La ribisioni è isthadda canzilladda',
'watchnochange'        => "Nisciuna di li pàgini abbaidaddi è isthadda mudìfiggadda i' lu perìodu cunsidaraddu.",
'watchlist-details'    => "La listha di l'abbaidaddi ippiziari cunteni {{PLURAL:$1|una pagina (e la rippettiba pagina di dischussioni)|$1 pagini (e li rippettibi pagini di dischussioni)}}.",
'wlheader-enotif'      => '* La nutìfica via postha erettrònica è attiba.',
'wlheader-showupdated' => "* Li pàgini chi so isthaddi mudìfiggaddi daboi l'ulthima vìsita so evidenziaddi in '''grasseddu'''",
'watchmethod-recent'   => "cuntrollu di li mudìfigghi rizzenti pa l'abbaidaddi ippiziari",
'watchmethod-list'     => "cuntrollu di l'abbaidaddi ippiziari pa mudìfigghi rizzenti",
'watchlistcontains'    => "La listha di l'abbaidaddi ippiziari cunteni {{PLURAL:$1|una pàgina|$1 pàgini}}.",
'iteminvalidname'      => "Probremi cu' la pàgina '$1', innòmmu nò vàriddu...",
'wlnote'               => "Inogghi {{PLURAL:$1|è erencadda la mudìfigga più rizzenti arriggadda|so erencaddi li '''$1''' mudìfigghi più rizzenti arriggaddi}} {{PLURAL:$2|i' la ulthima ora|i' li ulthimi '''$2''' ori}}.",
'wlshowlast'           => 'Musthra li ulthimi $1 ori $2 dì $3',
'watchlist-options'    => 'Opzioni abbaidaddi ippiziari',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => "Aggiunta a l'abbaidaddi ippiziari...",
'unwatching' => "Eliminazioni da l'abbaidaddi ippiziari...",

'enotif_mailer'                => 'Sisthema di nutìfica via postha erettrònica di {{SITENAME}}',
'enotif_reset'                 => 'Signa tutti li pàgini cumenti già visitaddi',
'enotif_newpagetext'           => 'Chistha è una pàgina nóba.',
'enotif_impersonal_salutation' => 'Utenti di {{SITENAME}}',
'changed'                      => 'ciambadda',
'created'                      => 'criadda',
'enotif_subject'               => 'La pàgina $PAGETITLE di {{SITENAME}} è isthadda $CHANGEDORCREATED da $PAGEEDITOR',
'enotif_lastvisited'           => "Cunsultha $1 pa vidé tutti li mudìfigghi da l'ulthima visita tóia.",
'enotif_lastdiff'              => 'Vidé $1 pa visuarizzà la mudìfigga.',
'enotif_anon_editor'           => 'utenti anònimu $1',
'enotif_body'                  => 'Gintiri $WATCHINGUSERNAME,


La pàgina $PAGETITLE di {{SITENAME}} è isthadda $CHANGEDORCREATED in data $PAGEEDITDATE da $PAGEEDITOR; la versioni attuari s\'acciappa a l\'indirizzu $PAGETITLE_URL.

$NEWPAGE

Riassuntu di la mudìfigga, insiriddu da l\'autori: $PAGESUMMARY $PAGEMINOREDIT

Cuntatta l\'autori di la mudìfigga:
via postha erettrònica: $PAGEEDITOR_EMAIL
i\' lu situ: $PAGEEDITOR_WIKI

Nò sarani inviaddi althri nutìfichi in casu d\'althri ciambamenti, arumancu chi tu nò visiti la pàgina. Daboi, è pussìbiri rimpusthà l\'abbisu di nutìfica pa tutti li pàgini i\' la listha di l\'abbaidaddi ippiziari.

             Lu sisthema di nutìfica di {{SITENAME}}

--
Pa mudìfiggà l\'impusthazioni di la listha di l\'abbaidaddi ippiziari, visita
{{fullurl:{{#special:Watchlist}}/edit}}

Pa dì cosa ni pensi e dumandà assisthènzia:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Canzella pàgina',
'confirm'                => 'Cunfèimma',
'excontent'              => "lu cuntinuddu era : '$1'",
'excontentauthor'        => "lu cuntinuddu era: '$1' (e lu soru cuntributori era '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'          => "Lu cuntinuddu primma di l'ibbiuddamentu era: '$1'",
'exblank'                => 'la pàgina era biodda',
'delete-confirm'         => 'Canzella "$1"',
'delete-legend'          => 'Canzella',
'historywarning'         => 'Attinzioni: La pàgina chi sei canzellendi à una cronologia:',
'confirmdeletetext'      => "Sei canzillendi pa sempri da la bancati una pàgina o un'immàgina, umpari a la cronologia d'edda.
Pa piazzeri, cunfèimma chi vói canzillà avveru, ch'ài cumpresu li cunsiguènzi di l'azioni tóia e ch'edda è cunfòimmi a li [[{{MediaWiki:Policy-url}}|lìni ghia]].",
'actioncomplete'         => 'Azioni cumpritadda',
'deletedtext'            => 'La pàgina "<nowiki>$1</nowiki>" è isthadda canzilladda. Cunsultha lu $2 pa un\'erencu di li pàgini canzilladdi da poggu tempu.',
'deletedarticle'         => 'ha canzilladdu "[[$1]]"',
'suppressedarticle'      => 'eliminaddu "[[$1]]"',
'dellogpage'             => 'Canzilladduri',
'dellogpagetext'         => 'Inogghi so erencaddi li pàgini canzilladdi da poggu tempu.',
'deletionlog'            => 'Rigisthru di li canzilladduri',
'reverted'               => 'Turra a la versioni prizzidenti',
'deletecomment'          => 'Mutibu di la canzilladdura:',
'deleteotherreason'      => 'Althra mutibazioni o mutibazioni aggiuntiba:',
'deletereasonotherlist'  => 'Althra mutibazioni',
'deletereason-dropdown'  => "*Mutibazioni più cumuni pa la canzilladdura
** Prigonta de l'autori
** Viorazioni di lu dirittu d'autori
** Vandarismu",
'delete-edit-reasonlist' => 'Mudìfigga li mutibazioni pa la canzilladdura',

# Rollback
'rollback'         => 'Annulla li mudìfigghi',
'rollback_short'   => 'Turra che primma',
'rollbacklink'     => 'turra che primma',
'rollbackfailed'   => 'Nò è ridisciddu a turrà che primma',
'cantrollback'     => "Impussìbiri annullà li mudìfigghi; l'utenti chi n'è l'autori è l'unicu cuntribudori di la pàgina.",
'editcomment'      => "L'oggettu di la mudìfigga era: \"''\$1''\".",
'rollback-success' => 'Annulladdi li mudìfigghi di $1; turradda a la versioni prizzidenti di $2.',
'sessionfailure'   => "S'è verifigaddu un probrema i' la sissioni ch'identifigga l'intradda; lu sistham nò à eseguiddu lu cumandu imparthiddu pa precauzioni. Turrà a la pàgina prizzidenti cu' lu buttoni \"Indareddu\" di lu proprio nabigaddori, turrà a carriggà la pàgina e riprubà.",

# Protect
'protectlogpage'              => 'Prutizioni',
'protectlogtext'              => "Inogghi v'è la listha di li pàgini broccaddi e ibbruccaddi. Védi la [[Special:ProtectedPages|listha di li pàgini prutiggiddi]] pa chiddi chi so attuarmenti prutiggiddi.",
'protectedarticle'            => 'à prutiggiddu "[[$1]]"',
'modifiedarticleprotection'   => 'ha mudìfiggaddu lu libellu di prutizioni di "[[$1]]"',
'unprotectedarticle'          => 'à ibbruccaddu "[[$1]]"',
'protect-title'               => 'Prutizioni di "$1"',
'prot_1movedto2'              => 'ha ippusthaddu [[$1]] a [[$2]]',
'protect-legend'              => 'Cunfèimma la prutizioni',
'protectcomment'              => 'Mutibu di la prutizioni:',
'protectexpiry'               => 'Ischadènzia:',
'protect_expiry_invalid'      => 'Ischadènzia invàridda.',
'protect_expiry_old'          => 'Ischadènzia già passadda.',
'protect-text'                => "Chisthu mòdulu cunsenti di vidé e mudìfiggà lu libellu di prutizioni pa la pàgina '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "Nò è pussìbiri mudìfiggà i libelli di prutizioni cand'è attibu un broccu. L'impusthazioni currenti pa la pàgina so '''$1''':",
'protect-locked-access'       => "Nò si diponi di li primmissi nezzessàri pa mudìfiggà lu libellu di prutizioni di la pàgina.
L'impusthazioni currenti pa la pàgina so '''$1''':",
'protect-cascadeon'           => "Chistha pàgina abà è broccadda parchí è incrusa {{PLURAL:$1|i' la sighenti pàgina, pa la quari|i' li sighenti pàgini, pa li quari}} è attiba la prutizioni ricussiba. È pussìbiri mudìfiggà lu libellu di prutizioni di la pàgina, ma l'impusthazioni reratibi a la prutuzioni ricussina nò sarani mudìfiggaddi.",
'protect-default'             => 'Autorizza tutti li utenti',
'protect-fallback'            => 'È nezzessàriu lu primissu "$1"',
'protect-level-autoconfirmed' => "Brocca l'utenti nobi e anònimi",
'protect-level-sysop'         => 'Soru amministhradori',
'protect-summary-cascade'     => 'ricussiba',
'protect-expiring'            => 'ischadènzia: $1 (UTC)',
'protect-cascade'             => 'Prutizioni ricussiba (isthendi la prutizioni a tutti li pàgini incrusi in chistha)',
'protect-cantedit'            => 'Nò è pussìbiri mudìfiggà li libelli di prutizioni pa la pàgina parchí nò si diponi di pimmissi nezzessàri pa mudìfiggà la pàgina.',
'protect-expiry-options'      => '2 ori:2 hours,1 dì:1 day,3 dì:3 days,1 chedda:1 week,2 cheddi:2 weeks,1 mesi:1 month,3 mesi:3 months,6 mesi:6 months,1 anni:1 year,infiniddu:infinite',
'restriction-type'            => 'Primmissu:',
'restriction-level'           => 'Libellu di isthrigniddura',
'minimum-size'                => 'Misura mìnima',
'maximum-size'                => 'Misura màssima (in byte):',
'pagesize'                    => '(byte)',

# Restrictions (nouns)
'restriction-edit'   => 'Mudifigga',
'restriction-move'   => 'Ippusthamentu',
'restriction-create' => 'Crià',

# Restriction levels
'restriction-level-sysop'         => 'prutiggidda',
'restriction-level-autoconfirmed' => 'semi-prutiggidda',
'restriction-level-all'           => 'tutti li libelli',

# Undelete
'undelete'                     => 'Musthra pàgini canzilladdi',
'undeletepage'                 => 'Musthra e ricupara li pàgini canzilladdi',
'viewdeletedpage'              => 'Musthra li pàgini canzilladdi',
'undeletepagetext'             => "Li pàgini chi so inogghi so isthaddi canzilladdi, ma so ancora i'archìbiu e dunca pudiani assé ricuparaddi. L'archìbiu pó assé ibbiuddaddu di tantu in tantu.",
'undeleterevisions'            => '{{PLURAL:$1|Una ribisioni|$1 ribisioni}} in archìbiu',
'undeletehistorynoadmin'       => "La pàgina è isthadda canzilladda. Lu mutibu di la canzilladdura è indicaddu inogghi, impari ai dati di l'utenti chi abìani mudìfiggaddu la pàgina primma di la canzilladdura. Lu testhu cuntinuddu i' li ribisioni canzilladdi è dipunìbiri soru a li amministhradori.",
'undelete-revision'            => 'Ribisioni canzilladda di la pàgina $1, insiridda lu $2 da $3:',
'undeleterevision-missing'     => "Ribisioni erradda o mancanti. Lu cullegamentu è erraddu oppuru la ribisioni è già isthadda ricuparadda o eliminadda da l'archìbiu.",
'undelete-nodiff'              => 'Nò è isthadda acciappadda nisciuna ribisioni prizzidenti.',
'undeletebtn'                  => 'Turra che primma',
'undeletelink'                 => 'visuarizza/rimpustha',
'undeletereset'                => 'Rimpustha',
'undeletecomment'              => 'Cummentu:',
'undeletedarticle'             => 'à ricuparaddu "[[$1]]"',
'undeletedrevisions'           => '{{PLURAL:$1|Una ribisioni ricuparadda|$1 ribisioni ricuparaddi}}',
'undeletedrevisions-files'     => '{{PLURAL:$1|Una ribisioni|$1 ribisioni}} e {{PLURAL:$2|un file ricuparaddu|$2 file ricuparaddi}}',
'undeletedfiles'               => '{{PLURAL:$1|Un file ricuparaddu|$1 file ricuparaddi}}',
'cannotundelete'               => 'Ricùparu falliddu; è pussìbiri chi la pàgina sia già isthadda ricuparadda da un althru utenti.',
'undelete-header'              => 'Cunsulthà lu [[Special:Log/delete|rigisthru di li canzilladduri]] pa vidé li canzilladduri più rizzenti.',
'undelete-search-box'          => "Zercha i' li pàgini canzilladdi",
'undelete-search-prefix'       => 'Musthra li pàgini lu quari tìturu ischuminza cun:',
'undelete-search-submit'       => 'Zercha',
'undelete-no-results'          => "Nisciuna pàgina acciappadda i' lu rigisthru di li canzilladduri.",
'undelete-filename-mismatch'   => 'Impussìbiri annullà la canzilladdura di la ribisioni di lu file cun marchaddura oreria $1: innòmmu file nò curripundenti.',
'undelete-bad-store-key'       => 'Impussìbiri annullà la canzilladdura di la ribisioni di lu file cun marchaddura oreria $1: file nò dipunìbiri primma di la canzilladdura.',
'undelete-cleanup-error'       => 'Errori i\' la canzilladdura di lu file d\'archìbiu nò utirizaddu "$1".',
'undelete-missing-filearchive' => "Impussìbiri ricuparà l'ID $1 di l'archìbiu file parchí nò è prisenti i' la bancadati. Pudia assé isthaddu già ricuparaddu.",
'undelete-error-short'         => "Errori i' lu ricùparu di lu file: $1",
'undelete-error-long'          => "Si so verifiggaddi di l'errori annullendi la canzilladdura di lu file:

$1",

# Namespace form on various pages
'namespace'      => 'Tipu di pàgina:',
'invert'         => 'invirthi la isciubaradda',
'blanknamespace' => '(Prinzipari)',

# Contributions
'contributions'       => 'Cuntributi utenti',
'contributions-title' => 'Cuntributi di $1',
'mycontris'           => "li me' cuntributi",
'contribsub2'         => 'Pa $1 ($2)',
'nocontribs'          => 'Nò so isthaddi acciappaddi mudifigghi cunfoimmi a li criteri sciubaraddi.',
'uctop'               => '(ulthima pa la pàgina)',
'month'               => 'A parthì da lu mesi (e prizzidenti):',
'year'                => "A parthì da l'anni (e prizzidenti):",

'sp-contributions-newbies'     => 'Musthra soru li cuntributi di li nobi utenti',
'sp-contributions-newbies-sub' => 'Pa li nobi utenti',
'sp-contributions-blocklog'    => 'Brocchi',
'sp-contributions-talk'        => 'dischussioni',
'sp-contributions-search'      => 'Zercha cuntributi',
'sp-contributions-username'    => 'Indirizzu IP o nommu utenti:',
'sp-contributions-submit'      => 'Zercha',

# What links here
'whatlinkshere'            => 'Puntani inogghi',
'whatlinkshere-title'      => 'Pàgini chi pùntani a "$1"',
'whatlinkshere-page'       => 'Pàgina:',
'linkshere'                => "Le sighenti pàgini cuntenani dei cullegamenti a '''[[:$1]]''':",
'nolinkshere'              => "Nisciuna pàgina cunteni dei cullegamenti chi pùntani a '''[[:$1]]'''.",
'nolinkshere-ns'           => "Nò vi so pàgini chi pùntani a '''[[:$1]]''' i' lu namespace sciubaraddu.",
'isredirect'               => 'rinviu',
'istemplate'               => 'incrusioni',
'isimage'                  => "cullegamentu a l'immàgina",
'whatlinkshere-prev'       => '{{PLURAL:$1|prizzidenti|prizzidenti $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|sighenti|sighenti $1}}',
'whatlinkshere-links'      => '← cullegamenti',
'whatlinkshere-hideredirs' => '$1 rinvii',
'whatlinkshere-hidetrans'  => '$1 incrusioni',
'whatlinkshere-hidelinks'  => '$1 cullegamenti',
'whatlinkshere-filters'    => 'Filthri',

# Block/unblock
'blockip'                     => 'Brocca utenti',
'ipaddress'                   => 'Indirizzu IP:',
'ipadressorusername'          => 'Indirizzu IP o innòmu utenti:',
'ipbexpiry'                   => 'Ischadènzia di lu broccu:',
'ipbreason'                   => 'Mutibu di lu broccu:',
'ipbreasonotherlist'          => 'Althra mutibazioni',
'ipbanononly'                 => 'Brocca soru utenti anònimi',
'ipbcreateaccount'            => "Impidisci la criazioni d'althri registhrazioni",
'ipbemailban'                 => "Impidisci a l'utenti l'inviu di postha erettrònica",
'ipbenableautoblock'          => "Brocca automatiggamenti l'ulthimu indirizzu IP usaddu da l'utenti e l'althri chi zerchani di fà mudìfigghi",
'ipbsubmit'                   => "Brocca l'utenti",
'ipbother'                    => 'Duradda nò in erencu:',
'ipboptions'                  => '2 ori:2 hours,1 dì:1 day,3 dì:3 days,1 chedda:1 week,2 cheddi:2 weeks,1 mesi:1 month,3 mesi:3 months,6 mesi:6 months,1 anni:1 year,infiniddu:infinite',
'ipbotheroption'              => 'althru',
'ipbotherreason'              => 'Althri dettàgli/rasgioni aggiuntiba:',
'ipbhidename'                 => "Cua l'innòmu utenti da lu rigisthru di li brocchi, da l'erencu di li brocci attibi e da l'erencu utenti.",
'badipaddress'                => 'Indirizzu IP invàriddu',
'blockipsuccesssub'           => 'Broccu eseguiddu',
'blockipsuccesstext'          => "[[Special:Contributions/$1|$1]] è isthaddu broccaddu.
<br />Cunsulthà la [[Special:IPBlockList|listha di l'IP broccaddi]] pa vidé i brocchi attibi.",
'ipb-edit-dropdown'           => 'Mutibi pa lu broccu',
'ipb-unblock-addr'            => 'Ibbrucca $1',
'ipb-unblock'                 => "Ibbrucca un'utenti o un'indirizzu IP",
'ipb-blocklist-addr'          => 'Erenca i brocchi attibi pa $1',
'ipb-blocklist'               => 'Erenca i brocchi attibi',
'unblockip'                   => "Ibbrucca l'utenti",
'unblockiptext'               => "Usà lu mòdulu in giossu pa turrà l'intradda i'ischrittura a un'utenti o indirizzu IP broccaddu.",
'ipusubmit'                   => "Ibbrucca chisth'indirizzu",
'unblocked'                   => "L'utenti [[User:$1|$1]] è isthaddu ibbruccaddu",
'unblocked-id'                => 'Lu broccu $1 è isthaddu buggaddu',
'ipblocklist'                 => 'Utenti e indirizzi IP broccaddi',
'ipblocklist-legend'          => "Acciappa un'utenti broccaddu",
'ipblocklist-username'        => 'Innòmmu utenti o indirizzu IP:',
'ipblocklist-submit'          => 'Zercha',
'blocklistline'               => '$1, $2 à broccaddu $3 ($4)',
'infiniteblock'               => 'chena ischadènzia',
'expiringblock'               => "finz'a lu $1 a li $2",
'anononlyblock'               => 'soru anònimi',
'noautoblockblock'            => 'chena broccu otomàtiggu',
'createaccountblock'          => 'criazioni registhrazioni broccadda',
'emailblock'                  => 'postha erettrònica broccadda',
'ipblocklist-empty'           => "L'erencu di li brocchi è bioddu.",
'ipblocklist-no-results'      => "L'indirizzu IP o innòmmu utenti ciamaddu nò è broccaddu.",
'blocklink'                   => 'brocca',
'unblocklink'                 => 'ibbrucca',
'change-blocklink'            => 'ciamba broccu',
'contribslink'                => 'cuntributi',
'autoblocker'                 => "Broccaddu automatiggamenti parchí l'indirizzu IP è cundibisu cu' l'utenti \"[[User:\$1|\$1]]\". Lu broccu di l'utenti \$1 è isthaddu impusthaddu pa lu sighenti mutibu: \"'''\$2'''\".",
'blocklogpage'                => 'Brocchi',
'blocklogentry'               => "à broccaddu [[$1]] cu' l'ischadènzia $2 $3",
'blocklogtext'                => "Chisthu è l'erencu di l'azioni di broccu e ibbruccu utenti. Li indirizzi IP broccaddi automatiggamenti nò so erencaddi. Cunsulthà la [[Special:IPBlockList|listha di l'IP broccaddi]] pa l'erencu di l'indirizzi e innòmmi utenti chi so broccaddi abà.",
'unblocklogentry'             => 'à ibbruccaddu $1',
'block-log-flags-anononly'    => 'soru utenti anònimi',
'block-log-flags-nocreate'    => 'criazioni registhrazioni broccadda',
'block-log-flags-noautoblock' => 'broccu otomàtiggu',
'block-log-flags-noemail'     => 'postha erettrònica broccadda',
'range_block_disabled'        => "La pussibiliddai di broccà intervalli d'indirizzi IP nò è attiba abà.",
'ipb_expiry_invalid'          => 'Duradda o ischadènzia di lu broccu nò vàridda.',
'ipb_already_blocked'         => '"$1" è già broccaddu',
'ipb_cant_unblock'            => 'Errori: Impussìbiri acciappà lu broccu cun ID $1. Lu broccu pudia assé già isthaddu buggaddu.',
'ipb_blocked_as_range'        => "Errori: L'indirizzu IP $1 nò è broccaddu individuaimmenti e nò pó assé ibbruccaddu. Lu broccu è inveci attibu a libellu di l'intervallu  $2, chi pó assé ibbruccaddu.",
'ip_range_invalid'            => "Intervallu d'indirizzi ip nò vàriddu.",
'blockme'                     => 'Broccami',
'proxyblocker'                => 'Broccu di li proxy abbérthi',
'proxyblocker-disabled'       => 'Chistha funzioni nò è attiba.',
'proxyblockreason'            => "Chisth'indirizzu IP è isthaddu broccaddu parchí risultha assé un proxy abbérthu. Pa piazeri cuntattà lu propriu frunidori di sivvìzi pa la reti pa infuimmalli di chisthu grabi probrema di sigguriddai.",
'proxyblocksuccess'           => 'Broggu eseguiddu.',
'sorbsreason'                 => "Chisth'indirizzu IP è erencaddu cumenti proxy abbérthu i' la listha-niedda DNSBL utirizadda da {{SITENAME}}.",
'sorbs_create_account_reason' => "Nò è pussìbiri crià nobi registhrazioni da chisthu indirizzu IP parchí è erencaddu cumenti proxy abbérthu i' la listha-niedda DNSBL utirizadda da {{SITENAME}}.",

# Developer tools
'lockdb'              => 'Brocca la bancadati',
'unlockdb'            => 'Ibbrucca la bancadati',
'lockconfirm'         => 'Emmu, vogliu broccà avveru la bancadati.',
'unlockconfirm'       => 'Emmu, vogliu ibbruccà avveru la bancadati.',
'lockbtn'             => 'Brocca la bancadati',
'unlockbtn'           => 'Ibbrucca la bancadati',
'locknoconfirm'       => 'Nò è isthadda sciubaradda la casella di cunfèimma.',
'lockdbsuccesssub'    => 'Broccu di la bancadati eseguiddu',
'unlockdbsuccesssub'  => 'Ibbruccu di la bancadati eseguiddu',
'unlockdbsuccesstext' => 'La bancadati è isthaddu ibbruccadda.',
'databasenotlocked'   => 'La bancadati nò è broccadda.',

# Move page
'move-page-legend'        => 'Ippusthamentu di pàgina',
'movepagetext'            => "Chishu mòdulu di rinominà una pàgina, ippusthendi tutta la cronologia soia a l'innòmmu nobu.
La pàgina attuari sarà automatiggamenti un rinviu a lu nobu tìturu.
I culleggamenti pudiani assé aggiornaddi.
Poi isciubarà di nò lu fà, ma verifigga chi l'ippusthamentu nò aggia criaddu [[Special:DoubleRedirects|doppi rinvi]] o [[Special:BrokenRedirects|rinvi erraddi]]. Ài la ripunsabiriddai chi li cullegamenti a la pàgina risthini curretti.

Nota chi la pàgina '''nò''' sarà ippusthadda si n'isisthi già una cu' lu nobu tìturu, a mancu chi nò sia biodda o un rinviu a lu vécciu tìturu, chena versioni prizzidenti.
In casu d'errori i' l'ippusthamentu pói turrà immediatamenti a lu tìturu vécciu, e nò pussìbiri sobbraischribì pa errori una pàgina già esisthenti.

'''ATTINZIONI!'''
Un ciambamentu cussì forthi pó causà umbè di prubremi a tuttiganti; 
azzirthati d'abé cumpresu li cunsiguènzi di l'ippusthamentu!",
'movepagetalktext'        => "La reratiba pàgina di dischussioni sarà ippusthadda automatiggamenti umpari a la pàgina prinzipari, '''fora chi i' li casi sighenti:'''
* l'ippusthamentu di la pàgina è intra tipi di pàgina dibessi
* isisthi già una pàgina di dischussioni a lu nobu tìturu (nò biodda)
* la casella in giossu nò è isthadda sciubaradda

In chisthi casi, si lu vói avveru, débi ippusthà o aggiugnì a manu le infuimmazioni cuntinuddi i' la pàgina di dischussioni.",
'movearticle'             => 'Ippustha la pàgina',
'movenologin'             => 'Intradda nò effettuadda',
'movenologintext'         => "L'ippusthamentu di li pàgini è cunsintiddu soru a l'utenti registhraddi chi so [[Special:UserLogin|intraddi]] i' lu situ.",
'movenotallowed'          => "Nò si diponi di li primmissi nezzessàri a l'ippusthamentu di pàgini.",
'newtitle'                => 'Nobu tìturu:',
'move-watch'              => "Aggiungi a l'abbaidaddi ippiziari",
'movepagebtn'             => 'Ippustha la pàgina',
'pagemovedsub'            => 'Ippusthamentu effettuaddu',
'movepage-moved'          => '\'\'\'"$1" è isthadda ippusthadda a "$2"\'\'\'',
'articleexists'           => "Una pàgina cun chisth'innòmmu isisthi già, oppuru l'innòmmu sciubaraddu no à vàriddu. Sciubarà un'althru tìturu.",
'cantmove-titleprotected' => "L'ippusthamentu di la pàgina nò è pussìbiri parchí lu nobu tìturu è isthaddu prutiggiddu pa impidinni la criazioni",
'talkexists'              => "'''La pàgina è isthadda ippusthadda currettamenti, ma nò è isthaddu pussìbiri ippusthà la pàgina di dischussioni parchí ni isisthi già un'althra cu' lu nobu tìturu. Aggiugnì a manu li cuntiniddi di li dui pàgini.'''",
'movedto'                 => 'ippusthadda a',
'movetalk'                => 'Ippustha puru la pàgina di dischussioni.',
'1movedto2'               => 'ha ippusthaddu [[$1]] a [[$2]]',
'1movedto2_redir'         => '[[$1]] ippusthadda a [[$2]] attrabessu rinviu',
'movelogpage'             => 'Ippusthamenti',
'movelogpagetext'         => "Chisthu è l'erencu di li pàgini ippusthaddi.",
'movereason'              => 'Mutibu',
'revertmove'              => 'turra che primma',
'delete_and_move'         => 'Canzella e ippustha',
'delete_and_move_text'    => '==Prigonga di canzilladdura==

La pàgina di disthinazioni "[[:$1]]" isisthi già. Vói canzillalla pa rindì pussìbiri l\'ippusthamentu?',
'delete_and_move_confirm' => 'Emmo, sobbraischribì la pàgini',
'delete_and_move_reason'  => "Canzilladda pa rindì pussìbiri l'ippusthamentu",
'selfmove'                => "Lu nobu tìturu è uguari a lu vécciu; impussìbiri ippusthà la pàgina cu' lu matessi innòmmu.",

# Export
'export'            => 'Ippurtha li pàgini',
'exporttext'        => "È pussìbiri ippurthà lu testhu e la cronologia di li mudìfigghi di una pàgina o di un gruppu di pàgini in fuimmaddu XML pa impurthalli i'althri siti ch'utilizzanni lu software MediaWiki, attrabessu [[Special:Import]].

Pa ippurthà li pàgina indicà i tìturi i' la casella di testhu in giossu, unu pa riga, e ippizzificà si s'à gana d'uttinì la versioni currenti e tutti li versioni prizzidenti, cu' li dati di la cronologia di la pàgina, oppure soru l'ulthima versioni e i dati curripundenti a l'ulthima mudìfigga.

In chisth'ulthimu casu si pó utilizzà puru un cullegamentu, pa esempiu [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] pa ippurthà \"[[{{MediaWiki:Mainpage}}]]\".",
'exportcuronly'     => "Incrudi soru la ribisioni attuari, nò l'intrea cronologia.",
'exportnohistory'   => "----
'''Nota:''' l'ippurthazioni di l'intrea cronologia di li pàgini attrabessu chistha interfàccia è isthadda disattibadda pa mutibi liaddi a li presthazioni di lu sisthema.",
'export-submit'     => 'Ippurtha',
'export-addcattext' => 'Aggiungi pàgini da la categuria:',
'export-addcat'     => 'Aggiungi',
'export-download'   => 'Dumanda lu saivvamentu cumenti file',

# Namespace 8 related
'allmessages'                   => 'Imbasciaddi di sisthema',
'allmessagesname'               => 'Nommu',
'allmessagesdefault'            => 'Testhu pridifiniddu',
'allmessagescurrent'            => 'Testhu attuari',
'allmessagestext'               => "Chistha è la listha di tutti l'imbasciaddi di sisthema dipunìbiri i' lu tipu di pàgina MediaWiki.
Pa piazeri utirizà [http://www.mediawiki.org/wiki/Localisation MediaWiki Lucarizazioni] e [http://translatewiki.net translatewiki.net] pa l'althri traduzioni.",
'allmessagesnotsupportedDB'     => "Chistha pàgina nò è supporthadda parchí l'indicadori '''\$wgUseDatabaseMessages''' nò è attibuu.",
'allmessages-filter-unmodified' => 'Nò mudifiggaddi',
'allmessages-filter-all'        => 'Tutti',
'allmessages-filter-modified'   => 'Mudifiggaddi',
'allmessages-language'          => 'Linga:',
'allmessages-filter-submit'     => 'Vai',

# Thumbnails
'thumbnail-more'           => 'Immannitta',
'filemissing'              => 'File mancanti',
'thumbnail_error'          => "Errori i' la criazioni di la miniadduri: $1",
'djvu_page_error'          => 'Nùmaru di pàgina DjVu ibbagliaddu',
'djvu_no_xml'              => "Impussìbiri uttinì l'XML pa lu file DjVu",
'thumbnail_invalid_params' => 'Parametri antiprimma nò curretti',
'thumbnail_dest_directory' => 'Impussìbiri crià la carthella di disthinazioni',

# Special:Import
'import'                     => 'Impurtha pàgini',
'importinterwiki'            => 'Impurthazioni transwiki',
'import-interwiki-text'      => "Sciubarà un prugettu wiki e lu tìturu di la pàgina d'impurthà.
Li dati di prubbiggazioni e l'innòmmi di l'autori di li vàri versioni sarani cunseivaddi.
Tutti l'operazioni d'impurthazioni trans-wiki so rigisthraddi i' lu [[Special:Log/import|rigisthru d'impurthazioni]].",
'import-interwiki-history'   => "Cupia l'intrea cronologia di chistha pàgina",
'import-interwiki-submit'    => 'Impurtha',
'import-interwiki-namespace' => 'Tipu di pagina di disthinazioni:',
'import-comment'             => 'Oggettu:',
'importtext'                 => "Pa piazeri ippurthà lu file da lu situ wiki d'origini cu' la funzioni Special:Export utility, saivvàllu i' lu propriu dischu e daboi carriggallu inogghi.",
'importstart'                => 'Impurthendi li pàgini...',
'import-revision-count'      => '{{PLURAL:$1|una ribisioni impurthadda|$1 ribisioni impurthaddi}}',
'importnopages'              => 'Nisciuna pàgina da impurthà.',
'importfailed'               => 'Impurthazioni nò ridiscidda: $1',
'importunknownsource'        => "Tipu d'orìgini ischunisciddu pa l'impurthazioni",
'importcantopen'             => "Impussìbiri abbrì lu file d'impurthazioni",
'importbadinterwiki'         => 'Cullegamentu interwiki ibbagliaddu',
'importnotext'               => 'Testhu bioddu o mancanti',
'importsuccess'              => 'Impurthazioni finidda!',
'importhistoryconflict'      => 'La cronologia cunteni di li versioni in cuntrasthu (chistha pàgina pudia assé già isthadda impurthadda)',
'importnosources'            => "Nò è isthadda difinidda un'origini pa l'impurthazioni transwiki; l'impurthazioni diretta di la cronologia nò è attiba.",
'importnofile'               => "Nò è isthaddu cariggaddu nisciun file pa l'impurthazioni.",
'importuploaderrorsize'      => "Carriggamentu di file pa l'impurthazioni nò ridisciddu. Lu file supara li misuri massimi cunsintiddi pa lu carriggamentu.",
'importuploaderrorpartial'   => "Carriggamentu di file pa l'impurthazioni nò ridisciddu. Lu file è isthaddu cariggaddu soru in parthi.",
'importuploaderrortemp'      => "Carriggamentu di file pa l'impurthazioni nò ridisciddu. Manca una carhella timpurania.",

# Import log
'importlogpage'                    => 'Impurthazioni',
'importlogpagetext'                => "Rigisthru di l'impurthazioni di pàgini d'althri wiki, cumpreti di cronologia.",
'import-logentry-upload'           => 'à impurthaddu [[$1]] attrabessu lu carriggamentu',
'import-logentry-upload-detail'    => '{{PLURAL:$1|una ribisioni impurthadda|$1 ribisioni impurthaddi}}',
'import-logentry-interwiki'        => "ha traiffiriddu da un'althra wiki la pàgina $1",
'import-logentry-interwiki-detail' => '{{PLURAL:$1|una ribisioni impurthadda|$1 ribisioni impurthaddi}} da $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'La pàgina utenti tóia',
'tooltip-pt-anonuserpage'         => 'La pàgina utenti di chistu indirizzu IP',
'tooltip-pt-mytalk'               => 'Pàgina di li tó dischussioni',
'tooltip-pt-anontalk'             => "Dischussioni i' li mudìfigghi arriggaddi da chisthu indirizzu IP",
'tooltip-pt-preferences'          => 'Li tó prifirènzi',
'tooltip-pt-watchlist'            => "La listha di li pàgini ch'isthai tinendi sottu osseivvazioni",
'tooltip-pt-mycontris'            => 'Listha di li tó cuntributi',
'tooltip-pt-login'                => 'La registhrazioni è cunsigliadda, puru si nò è ubbrigatória',
'tooltip-pt-anonlogin'            => 'La registhrazioni è cunsigliadda, puru si nò è ubbrigatória.',
'tooltip-pt-logout'               => 'Iscidda',
'tooltip-ca-talk'                 => 'Vèdi li dischussioni reratibi a chistha pàgina',
'tooltip-ca-edit'                 => "Pói mudìfiggà chistha pàgina. Pa piazeri usa lu buttoni d'antiprimma primma di saivvà",
'tooltip-ca-addsection'           => 'Ischumenza una sezzioni noba',
'tooltip-ca-viewsource'           => 'Chistha pàgina è prutiggidda, ma pói vidé lu còdizi soiu.',
'tooltip-ca-history'              => 'Versioni prizzidenti di chistha pàgina',
'tooltip-ca-protect'              => 'Prutiggi chistha pàgina',
'tooltip-ca-delete'               => 'Canzella chistha pàgina',
'tooltip-ca-undelete'             => 'Turra la pàgina a primma di la canzilladdura.',
'tooltip-ca-move'                 => 'Ippustha chistha pàgina (ciamba tìturu)',
'tooltip-ca-watch'                => "Aggiungi chistha pàgina a la listha tóia di l'abbaidaddi ippiziari",
'tooltip-ca-unwatch'              => "Elimina chistha pàgina da la tóia listha d'abbaidaddi ippiziari",
'tooltip-search'                  => 'Zercha di dentru a {{SITENAME}}',
'tooltip-search-go'               => "Via a una pàgina cu' lu tìturu indicaddu, si v'è",
'tooltip-search-fulltext'         => "Zercha lu testhu indicaddu i' li pàgini",
'tooltip-p-logo'                  => 'Pagina prinzipari',
'tooltip-n-mainpage'              => 'Visita la pàgina prinzipari',
'tooltip-n-portal'                => "Deschrizioni di lu prugettu, cosa pói fà, und'agattà li cosi",
'tooltip-n-currentevents'         => "Infuimmazioni i' l'eventi d'attuarità",
'tooltip-n-recentchanges'         => "Erencu di l'ulthimi mudìfigghi i' lu situ",
'tooltip-n-randompage'            => 'Musthra una pàgina a casu',
'tooltip-n-help'                  => "Pàgini d'aggiuddu.",
'tooltip-t-whatlinkshere'         => 'Erencu di tutti li pàgini chi pùntani inogghi',
'tooltip-t-recentchangeslinked'   => 'Erencu di li ulthimi mudìfigghi a li pàgini culligaddi a chistha',
'tooltip-feed-rss'                => 'Feed RSS pa chistha pàgina',
'tooltip-feed-atom'               => 'Feed Atom pa chistha pàgina',
'tooltip-t-contributions'         => 'Listha di li cuntributi di chistu utenti',
'tooltip-t-emailuser'             => "Invia un'imbasciadda di postha erettrònica a chisth'utenti",
'tooltip-t-upload'                => 'Carrigga file mùrthimediari',
'tooltip-t-specialpages'          => 'Listha di tutti li pàgini ippiziari',
'tooltip-t-print'                 => 'Versioni sthampabiri di chistha pàgina',
'tooltip-t-permalink'             => 'Cullegamentu peimmanenti a chistha versioni di la pàgina',
'tooltip-ca-nstab-main'           => 'Vèdi la bozi',
'tooltip-ca-nstab-user'           => 'Vèdi la pàgina utenti',
'tooltip-ca-nstab-media'          => 'Vedi la pàgina di lu file mùrthimediari',
'tooltip-ca-nstab-special'        => 'Chistha è una pàgina ippiziari, nò pó assé mudìfiggadda.',
'tooltip-ca-nstab-project'        => 'Vèdi la pàgina di saivvìziu',
'tooltip-ca-nstab-image'          => "Vèdi la pàgina di l'immàgina",
'tooltip-ca-nstab-mediawiki'      => "Vèdi l'imbasciadda di sisthema",
'tooltip-ca-nstab-template'       => 'Vèdi lu mudellu',
'tooltip-ca-nstab-help'           => "Vèdi la pàgina d'aggiuddu",
'tooltip-ca-nstab-category'       => 'Vedì la pàgina di la categuria',
'tooltip-minoredit'               => 'Signàra cumenti mudìfigga minori',
'tooltip-save'                    => 'Sàivva li mudìfigghi',
'tooltip-preview'                 => 'Antiprimma di li mudìfigghi (cunsigliadda, primma di sàivvà!)',
'tooltip-diff'                    => 'Abbaidda li mudìfigghi arriggaddi a lu testhu.',
'tooltip-compareselectedversions' => 'Abbaidda li diffarènzi intra li dui versioni sciubaraddi di chistha pàgina.',
'tooltip-watch'                   => "Aggiungi chistha pàgina a la listha di l'abbaidaddi ippiziari",
'tooltip-recreate'                => "Ricrea la pàgina puru s'è già isthadda canzilladda.",
'tooltip-upload'                  => 'Ischuminza lu carriggamentu',
'tooltip-rollback'                => '"Turra che primma" annulla li mudifigghi a chistha pagina di l\'ulthimu cuntributori cu\' un soru clic.',
'tooltip-undo'                    => "\"Annulla\"  pilmitti d'annullà chistha mudifigga e abbri lu modulu di mudifigga d'antiprimma. Pilmitti d'insirì una mutibazioni i' l'oggettu di la mudifigga.",

# Metadata
'nodublincore'      => "Metadati Dublin Core RDF nò attibi i' chisthu server.",
'nocreativecommons' => "Metadati Commons RDF nò attibi i' chisthu server.",
'notacceptable'     => "Lu server wiki nò è bonu a frunì li dati i' un fuimmaddu liggìbiri da lu nabiggadori utirizaddu.",

# Attribution
'anonymous'        => '{{PLURAL:$1|Utenti anonimu|utenti anonimi}} di {{SITENAME}}',
'siteuser'         => '$1, utenti di {{SITENAME}}',
'lastmodifiedatby' => "Chistha pàgina è isthadda mudìfiggadda pa l'ulthima voltha lu $2, $1 da $3.",
'othercontribs'    => "Lu testhu attuari è basaddu i' li cuntributi di $1.",
'others'           => 'althri',
'siteusers'        => '$1, {{PLURAL:$2|utenti|utenti}} di {{SITENAME}}',
'creditspage'      => 'Autori di la pàgina',
'nocredits'        => "Nisciuna infuimmazioni i' l'autori dipunìbiri pa chistha pàgina.",

# Spam protection
'spamprotectiontitle' => 'Filthru anti-spam',
'spamprotectiontext'  => "La pàgina ch'eri saivvendi è isthadda broccadda da lu filthru anti-spam, pó assé pa la prisènzia di un cullegamentu a un situ esthernu broccaddu.",
'spamprotectionmatch' => 'Lu filthru anti-spam è isthaddu attibaddu da lu testhu sighenti: $1',
'spambot_username'    => 'MediaWiki buggadda spam',
'spam_reverting'      => "Turradda a l'ulthima versioni chena cullegamenti a $1",
'spam_blanking'       => 'Pàgina ibbiuddadda, tutti li ribisioni abìani cullegamenti a $1',

# Info page
'infosubtitle'   => 'Infuimmazioni pa la pàgina',
'numedits'       => 'Nùmaru di mudìfigghi (pàgina): $1',
'numtalkedits'   => 'Nùmaru di mudìfigghi (pàgina di dischussioni): $1',
'numwatchers'    => "Nùmaru d'osseivvadori: $1",
'numauthors'     => "Nùmaru d'autori disthinti (pàgina): $1",
'numtalkauthors' => "Nùmaru d'autori disthinti (pàgina di dischussioni): $1",

# Math options
'mw_math_png'    => 'Musthra sempri in PNG',
'mw_math_simple' => 'HTML si umbè sémprizi, sinnò PNG',
'mw_math_html'   => 'HTML si pussìbiri, sinnò PNG',
'mw_math_source' => 'Lassa in fuimmaddu TeX (pa nabiggadori testhuari)',
'mw_math_modern' => 'Fuimmaddu cunsigliaddu pa li nabiggadori muderni',
'mw_math_mathml' => 'Usa MathML si pussìbiri (ippirimintari)',

# Math errors
'math_failure'          => "Errori i'l'anàrisi sintàttigga",
'math_unknown_error'    => 'errori ischunisciddu',
'math_unknown_function' => 'funzioni ischuniscidda',
'math_lexing_error'     => 'errori di lingàggiu',
'math_syntax_error'     => 'errori di sintassi',
'math_image_error'      => 'Cunvirthimentu in PNG nò ridisciddu; verifiggà chi siani isthalladdi currentementi i sighenti prugrammi: latex, dvips, gs, e convert.',
'math_bad_tmpdir'       => "Impussìbiri ischribì o crià la carthella timpurània pa ''math''",
'math_bad_output'       => "Impussìbiri ischribì o crià la carthella d'iscidda pa ''math''",
'math_notexvc'          => "Fattìbiri ''texvc'' mancanti; pa piazeri cunsulthà ''math/README'' pa la cunfigurazioni.",

# Patrolling
'markaspatrolleddiff'                 => 'Signa la mudìffiga cumenti verifiggadda',
'markaspatrolledtext'                 => 'Signa chistha pàgina cumenti verifiggadda',
'markedaspatrolled'                   => 'Signaddu cumenti verifiggaddu',
'markedaspatrolledtext'               => 'La ribisioni sciubaradda è isthadda signadda cumenti verifiggadda.',
'rcpatroldisabled'                    => "La verìfica di l'ulthimi mudìfigghi è disattibadda.",
'rcpatroldisabledtext'                => "La funzioni di verìfica di l'ulthimi mudìfigghi abà nò è attiba.",
'markedaspatrollederror'              => 'Impussìbiri signàralla cumenti verifiggadda',
'markedaspatrollederrortext'          => 'Débi ippizzificà una ribisizioni da signàrà cumenti verifiggadda.',
'markedaspatrollederror-noautopatrol' => 'Nò si diponi di li primmissi nezzessàri pa signà li propri mudìfigghi cumenti verifiggaddi.',

# Patrol log
'patrol-log-page' => 'Mudìfigghi verifiggaddi',
'patrol-log-line' => 'à signaraddu la $1 a la pàgina $2 cumenti verifiggadda $3',
'patrol-log-auto' => '(verìfica automàtigga)',

# Image deletion
'deletedrevision'                 => 'Prizzidenti ribisioni canzilladda: $1',
'filedeleteerror-short'           => 'Errori canzellendi lu file: $1',
'filedeleteerror-long'            => "Si so verifiggaddi di l'errori prubendi a canzillà lu file:

$1",
'filedelete-missing'              => 'Impussìbiri canzillà lu file "$1" parchí nò isisthi.',
'filedelete-old-unregistered'     => 'La ribisioni di lu file sciubaradda, "$1", no è cuntinudda i\' la bancadati.',
'filedelete-current-unregistered' => 'Lu file ippizzificaddu, "$1", nò è cuntinuddu i\' la bancadati.',
'filedelete-archive-read-only'    => 'L\'elaburaddori sivvidori nò pó ischribì i\' la carthella d\'archìbiu "$1".',

# Browsing diffs
'previousdiff' => '← Diffarènzia prizzidenti',
'nextdiff'     => 'Diffarènzia sighenti →',

# Media information
'mediawarning'         => "'''Attinzioni''': Chisthu file pó cuntinì còdizi marignu, chi si eseguiddu pó danniggià lu propriu sisthema infuimmatigu.",
'imagemaxsize'         => "Misura màssima di l'immàgini i'li reratibi pàgini di dischussioni:",
'thumbsize'            => 'Mannària di li miniadduri:',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|pàgina|pàgini}}',
'file-info'            => 'Misuri: $1, tipu MIME: $2',
'file-info-size'       => '($1 × $2 punti, misuri: $3, tipu MIME: $4)',
'file-nohires'         => '<small>Nò so dipunìbiri versioni a risoruzioni maggiori.</small>',
'svg-long-desc'        => '(file in fuimmaddu SVG, misuri nominari $1 × $2 punti, misuri di lu file: $3)',
'show-big-image'       => 'Versioni a altha risoruzioni',
'show-big-image-thumb' => "<small>Misuri di chisth'antiprimma: $1 × $2 punti</small>",

# Special:NewFiles
'newimages'             => 'Galleria di li file nobi',
'imagelisttext'         => "Inogghi una listha di '''$1''' {{PLURAL:$1|file|file}} ordhinaddi pa $2.",
'showhidebots'          => '($1 li bot)',
'noimages'              => "Nò v'è nudda da vidé.",
'ilsubmit'              => 'Zercha',
'bydate'                => 'data',
'sp-newimages-showfrom' => "Musthra l'immàgini più rizzenti a parthì da l'ori $2 di lu $1",

# Bad image list
'bad_image_list' => "Lu fuimmaddu è lu sighenti:

So cunsidaraddi soru l'erenchi puntaddi (righi ch'ischumenzani cu' lu caràtteri *). Lu primmu cullegamentu i' ugna riga déb'assé un cullegamentu a un file no disizadda. I cullugamenti chi veni appoi, i' la matessi riga, so cunsidaraddi eccezzioni (ossia, pàgini i' li quari l'immàgina pó assé riciamadda noimmaimmenti).",

# Metadata
'metadata'          => 'Metadati',
'metadata-help'     => 'Chisthu file cunteni infuimmazzioni aggiuntibe, pó assé da la fotocamera o da lu scanner. Si lu file é isthaddu mudìfiggaddu, zerthuni dettàgli pudiani nò curripundì a li mudìfigghi arriggaddi.',
'metadata-expand'   => 'Musthra dettàgli',
'metadata-collapse' => 'Cua dettàgli',
'metadata-fields'   => "Li campi reratibi a li metadi EXIF erencaddi in chist'imbasciadda sarani musthraddi i' la pàgina di l'immàgina candu la tabella di li metadati è prisenti i' lu fuimmaddu brebi. Pà impusthazioni pridifinidda, l'althri campi sarani cuaddi.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength",

# EXIF tags
'exif-imagewidth'                => 'Larghèzia',
'exif-imagelength'               => 'Althèzia',
'exif-bitspersample'             => 'Bit pa campioni',
'exif-compression'               => 'Tipu di cumprissioni',
'exif-photometricinterpretation' => 'Sthruttura di li punti',
'exif-orientation'               => 'Orientamentu',
'exif-imagedescription'          => "Deschrizioni di l'immàgina",
'exif-artist'                    => 'Autori',
'exif-copyright'                 => "Infuimmazioni i' lu dirittu d'autori",
'exif-exifversion'               => 'Versioni di lu fuimmaddu Exif',
'exif-usercomment'               => "Noti di l'utenti",
'exif-exposuretime-format'       => '$1 sigundu ($2)',
'exif-flash'                     => 'Caratterìsthiga e cundizioni di lu lampu',
'exif-flashenergy'               => 'Putènzia di lu lampu',
'exif-contrast'                  => 'Cuntrollu cuntrasthu',

'exif-orientation-1' => 'Noimmari',

'exif-componentsconfiguration-0' => 'assenti',

'exif-subjectdistance-value' => '$1 metri',

'exif-meteringmode-0'   => 'Ischunisciddu',
'exif-meteringmode-1'   => 'Mèdia',
'exif-meteringmode-2'   => 'Mèdia pisadda cintradda',
'exif-meteringmode-3'   => 'Luzi puntuari',
'exif-meteringmode-4'   => 'MultiLuzi',
'exif-meteringmode-5'   => 'Taurozza basi',
'exif-meteringmode-255' => 'Althru',

'exif-lightsource-1'  => 'Luzi diurna',
'exif-lightsource-4'  => 'Lampu',
'exif-lightsource-17' => 'Luzi standard A',
'exif-lightsource-18' => 'Luzi standard B',
'exif-lightsource-19' => 'Luzi standard C',
'exif-lightsource-20' => 'Illuminanti D55',
'exif-lightsource-21' => 'Illuminanti D65',
'exif-lightsource-22' => 'Illuminanti D75',
'exif-lightsource-23' => 'Illuminanti D50',

'exif-focalplaneresolutionunit-2' => 'póddighi',

'exif-sensingmethod-1' => 'Nò difiniddu',

'exif-gaincontrol-0' => 'Nisciunu',

'exif-contrast-0' => 'Noimmari',
'exif-contrast-1' => 'Althu cuntrasthu',
'exif-contrast-2' => 'Bassu cuntrasthu',

'exif-saturation-0' => 'Noimmari',

'exif-sharpness-0' => 'Noimmari',
'exif-sharpness-1' => 'Minori nitiddèzia',
'exif-sharpness-2' => 'Maggiori nitiddèzia',

'exif-subjectdistancerange-0' => 'Ischuniscidda',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-n' => 'Nodi',

# External editor support
'edit-externally'      => 'Mudìfigga chistu file usendi un prugramma esthernu',
'edit-externally-help' => "Pa maggiori infuimmazioni cunsulthà l'[http://www.mediawiki.org/wiki/Manual:External_editors isthruzioni] (in ingresu).",

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'tutti',
'imagelistall'     => 'tutti',
'watchlistall2'    => 'tutti',
'namespacesall'    => 'Tutti',
'monthsall'        => 'tutti',

# E-mail address confirmation
'confirmemail'            => 'Cunfèimma indirizzu di postha erettrònica',
'confirmemail_noemail'    => "Nò è isthaddu indicaddu un'indirizzu postha erettrònica vàriddu i' li pròpri [[Special:Preferences|prifirenzi]].",
'confirmemail_text'       => "{{SITENAME}} dumanda la verifigga di l'indirizzu di postha erettrònica primma di pudé l'usà. Incalchà lu buttoni in giossu pa invià una prigonta di cunfèimma a lu propriu indirizzu; i' l'imbasciadda è prisenti un cullegamentu chi cunteni un còdizi. Visità lu cullegamentu cu' lu proprio nabiggadori pa cunfèimmà chi l'indirizzu è vàriddu.",
'confirmemail_pending'    => "Un còdizi di cunfèimma è già isthaddu inviaddu pa postha erettrònica; si la registhrazioni è isthadda cridda da poggu, è nezzessàriu aisittà l'arribu di lu còdizi pa calchi minutu primma di dumandanni unu nobu.",
'confirmemail_send'       => 'Invia un còdizi di cunfèimma via postha erettrònica.',
'confirmemail_sent'       => 'Imbasciadda di postha erettrònica di cunfèimma inviaddu.',
'confirmemail_oncreate'   => "Un còdizi di cunfèimma è isthaddu inviaddu a l'indirizzu di postha erettrònica indicaddu. Lu còdizi nò è nezzessàriu pa intrà i' lu situ, ma pa abirità tutti li funzioni liaddi a la postha erettrònica.",
'confirmemail_sendfailed' => "Impussìbiri invià l'imbasciadda di postha erettrònica di cunfèimma. Verifiggà chi l'indirizzu nò aggia caràtteri nò vàriddi.

Imbasciadda d'errori: $1",
'confirmemail_invalid'    => 'Còdizi di cunfèimma nò vàriddu. Lu còdizi pudaria assé ischaduddu.',
'confirmemail_needlogin'  => 'È nezzessàriu $1 pa cunfèimmà lu propriu indirizzu di postha erettrònica.',
'confirmemail_success'    => "L'indirizzu di postha erettrònica è cunfèimmaddu. Abà è pussìbiri eseguì l'intradda e si godì lu situ.",
'confirmemail_loggedin'   => "L'indirizzu di postha erettrònica è isthaddu cunfèimmaddu.",
'confirmemail_error'      => 'Errori i lu saivvamentu di la cunfèimma.',
'confirmemail_subject'    => "{{SITENAME}}: prigonta di cunfèimma di l'indirizzu di postha erettrònica",
'confirmemail_body'       => "Calchunu, forsi tu cu' l'indirizzu IP \$1,
s'è rigisthraddu cumenti \"\$2\" i' {{SITENAME}} cun chisthu indirizzu di postha erettrònica.

Pa cunfèimmà chi la registhrazioni è tóia e attibà li funzioni reratibi a l'inviu di postha erettrònica i' {{SITENAME}}, abbri lu sighenti cullegamentu i' lu nabiggadori tóiu:

\$3

Si chistha registhrazioni nò t'appartheni, sighì chisthu cullegamentu pa canzillalla.

\$5

Chistu còdizi di cunfèimma ischadrà automatiggamenti a li \$4.",

# Scary transclusion
'scarytranscludedisabled' => "[L'incrusioni di pàgini tra siti wiki nò è attiba]",
'scarytranscludefailed'   => '[Errori: Impussìbiri uttinì lu mudellu $1]',
'scarytranscludetoolong'  => "[L'URL è troppu longu]",

# Trackbacks
'trackbackbox'      => 'Infuimmazioni di tracciamentu pa chistha pàgina:<br />
$1',
'trackbackremove'   => '([$1 Elimina])',
'trackbacklink'     => 'Tracciamentu',
'trackbackdeleteok' => 'Infuimmazioni di tracciamentu eliminaddi.',

# Delete conflict
'deletedwhileediting' => "Attinzioni: Chistha pàgina è isthadda canzilladda daboi ch'ài ischuminzaddu a mudìfiggarla!",
'confirmrecreate'     => "L'utenti [[User:$1|$1]] ([[User talk:$1|dischussioni]]) à canzilladdu chistha pàgina daboi ch'ài ischuminzaddu a mudìfiggarla, pa lu sighenti mutibu: ''$2''

Pa piazeri, cunfèimma chi vòi ricrià avveru chistha pàgina.",
'recreate'            => 'Ricrea',

# action=purge
'confirm_purge_button' => 'Cunfèimma',
'confirm-purge-top'    => 'Vói pulì la mimória cache di chistha pàgina?',

# Multipage image navigation
'imgmultipageprev' => '← pàgina prizzidenti',
'imgmultipagenext' => 'pàgina sighenti →',
'imgmultigo'       => 'Vai',

# Table pager
'ascending_abbrev'         => 'crisc',
'descending_abbrev'        => 'miminan',
'table_pager_next'         => 'Pàgina sighenti',
'table_pager_prev'         => 'Pàgina prizzidenti',
'table_pager_first'        => 'Primma pàgina',
'table_pager_last'         => 'Ulthima pàgina',
'table_pager_limit'        => 'Musthra $1 file pa pàgina',
'table_pager_limit_submit' => 'Vai',
'table_pager_empty'        => 'Nisciun risulthaddu',

# Auto-summaries
'autosumm-blank'   => 'Pàgina cumpretamenti ibbiuddadda',
'autosumm-replace' => "Pàgina susthituidda cun '$1'",
'autoredircomment' => 'Rinviu à la pàgina [[$1]]',
'autosumm-new'     => "Criadda pàgina cun '$1'",

# Live preview
'livepreview-loading' => 'Carrigghendi…',
'livepreview-ready'   => 'Carrigghendi… Prontu!',
'livepreview-failed'  => "Errori i' la funzioni Live preview. Usà l'antiprimma standard.",
'livepreview-error'   => 'Impussìbiri effettuà lu cullegamentu: $1 "$2". Usà l\'antiprimma standard.',

# Friendlier slave lag warnings
'lag-warn-normal' => "Li mudìfigghi arriggaddi {{PLURAL:$1|i' l'ulthimu sigundu|i' l'ulthimi $1 sigundi}} pudiani nò apparì in chistha listha.",
'lag-warn-high'   => "Pa un ritardhu mannu di l'elaburaddori sivvidori di la bancadati, li mudìfigghi arriggaddi i' l'ulthimi $1 sigundi pudiani nò apparì in chistha listha.",

# Watchlist editor
'watchlistedit-numitems'       => "La listha di l'abbaidaddi ippiziari cunteni {{PLURAL:$1|una pàgina (e la rippettiba pàgina di dischussioni)|$1 pàgini (e li rippettibi pàgini di dischussioni)}}.",
'watchlistedit-noitems'        => "La listha di l'abbaidaddi ippiziari è biodda.",
'watchlistedit-normal-title'   => 'Mudìfigga abbaidaddi ippiziari',
'watchlistedit-normal-legend'  => "Eliminiazioni di pàgini da l'abbaidaddi ippiziari",
'watchlistedit-normal-explain' => "Inogghi so erencaddi tutti li pàgini abbaidaddi. Pà canzellà una o più pàgini di la listha, isciubarà li caselli reratibi e fà clic i' lu buttoni '''Elimina pàgini''' in giossu a l'erencu. Pói puru [[Special:Watchlist/raw|mudìfiggalla in fuimmaddu testhu]].",
'watchlistedit-normal-submit'  => 'Elimina pàgini',
'watchlistedit-normal-done'    => "Da la listha di l'abbaidaddi ippiziari {{PLURAL:$1|è isthadda eliminadda una pàgina|so isthaddi eliminaddi $1 pàgini}}:",
'watchlistedit-raw-title'      => "Mudìfigga l'abbaidaddi ippiziari in fuimmaddu testhu",
'watchlistedit-raw-legend'     => 'Mudìfigga testhuari abbaidaddi ippiziari',
'watchlistedit-raw-explain'    => "Inogghi so erencaddi tutti li pàgini abbaidaddi. Pà mudìfiggà la listha aggiugnì o buggà li tìturi, unu pa riga. Cand'ài finiddu, fa clic i' '''Aggiorna la listha''' in giossu a l'erencu. Pói puru [[Special:Watchlist/edit|mudìfiggà la listha cu' l'interfàccia standard]].",
'watchlistedit-raw-titles'     => 'Pàgini:',
'watchlistedit-raw-submit'     => 'Aggiorna la listha',
'watchlistedit-raw-done'       => "La listha di l'abbaidaddi ippiziari è isthadda aggiornadda.",
'watchlistedit-raw-added'      => '{{PLURAL:$1|È isthadda aggiunta una pàgina|So isthaddi aggiunti $1 pàgini}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|È isthadda eliminadda una pàgina|So isthaddi eliminaddi $1 pàgini}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Visuarizza li mudìfigghi attinenti',
'watchlisttools-edit' => 'Visuarizza e mudìfigga la listha',
'watchlisttools-raw'  => 'Mudìfigga la listha in fuimmaddu testhu',

# Special:Version
'version'                  => 'Versioni',
'version-other'            => 'Althru',
'version-software-version' => 'Versioni',

# Special:FilePath
'filepath'        => "Parchossu d'un file",
'filepath-page'   => 'Innommu di lu file:',
'filepath-submit' => 'Parchossu',

# Special:FileDuplicateSearch
'fileduplicatesearch-legend'   => "Zercha d'un dupricaddu",
'fileduplicatesearch-filename' => 'Innòommu di lu file:',
'fileduplicatesearch-submit'   => 'Zercha',

# Special:SpecialPages
'specialpages'             => 'Pagini ippiziari',
'specialpages-group-login' => 'Intra / registhrazioni',

# Special:Tags
'tags-edit' => 'mudifigga',

# HTML forms
'htmlform-submit'              => 'Invia',
'htmlform-reset'               => 'Annulla mudifigghi',
'htmlform-selectorother-other' => 'Althru',

);

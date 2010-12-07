<?php
/** Tarandíne (Tarandíne)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Joetaras
 * @author McDutchie
 */

$specialPageAliases = array(
	'Preferences'               => array( 'Preferenze' ),
	'Watchlist'                 => array( 'PàggeneCondrollete' ),
	'Recentchanges'             => array( 'UrtemeCangiaminde' ),
	'Upload'                    => array( 'Carecaminde' ),
	'Statistics'                => array( 'Statisteche' ),
	'Version'                   => array( 'Versione' ),
	'Allmessages'               => array( 'TutteLeMessagge' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Cullegaminde sottolinèete:',
'tog-highlightbroken'         => 'Formete de collegamende sqausciete <a href="" class="new">cumme quiste</a> (in alternativa: cumme quiste<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Giustifeche le paragrafe',
'tog-hideminor'               => "Scunne le cangiaminde stuédeche jndr'à le cangiaminde recende",
'tog-hidepatrolled'           => "Scunne le cangiaminde condrollete jndr'à le cangiaminde recende",
'tog-newpageshidepatrolled'   => "Scunne le pàggene tenute sotte condrolle da 'a liste de le pàggene nuève",
'tog-extendwatchlist'         => "Spanne 'a liste de le pàggene condrollete pe fa vedè tutte le cangiaminde fatte, none sulamende l'urteme",
'tog-usenewrc'                => "Ause le cangiaminde recende migliorate (richiede 'u JavaScript)",
'tog-numberheadings'          => 'Testete auto-numerete',
'tog-showtoolbar'             => "Fà vedè 'a barra de le cangiaminde (JavaScript)",
'tog-editondblclick'          => "Cange le pàggene cu 'nu doppie clic (JavaScript)",
'tog-editsection'             => 'Abilite le cangiaminde a sezione ausanne [cange]',
'tog-editsectiononrightclick' => "Abilite le cngiaminde d'a sezione ausanne 'u pulsande destre d'u mouse cazzanne sus a 'u titele (Javascript)",
'tog-showtoc'                 => "Fa vedè 'a taggelle de le condenute (pe le pàggene cu cchiù de 3 testete)",
'tog-rememberpassword'        => "Arrencuerdete 'u nome mije sus a stu browser (pe 'nu massime de $1 {{PLURAL:$1|sciurne|sciurne}})",
'tog-watchcreations'          => "Mitte le pàggene ca je agghje ccrejete jndr'à le pàggene condrollete",
'tog-watchdefault'            => "Mitte le pàggene ca je agghje cangete jndr'à le pàggene condrolleteAdd pages I edit to my watchlist",
'tog-watchmoves'              => "Mitte le pàggene ca je agghje spustete jndr'à le pàggene condrollete",
'tog-watchdeletion'           => "Mitte le pàggene ca je agghje scangillete jndr'à le pàggene condrollete",
'tog-minordefault'            => 'Pe convenzione signe tutte le cangiaminde cumme a stuédeche',
'tog-previewontop'            => "Fa vedè l'andeprime apprime de 'a scatole de le cangiaminde",
'tog-previewonfirst'          => "Fà vedè l'andeprime sus a 'u prime cangiaminde",
'tog-nocache'                 => "Disabbilete 'u caching d'a pàgene sfogliate",
'tog-enotifwatchlistpages'    => "Manneme 'na mail quanne 'a pàgene ca stoche a condrolle ha cangete",
'tog-enotifusertalkpages'     => "Manneme 'na mail quanne 'a pàgene de le 'ngazzaminde ha cangete",
'tog-enotifminoredits'        => "Manneme 'na mail quanne onne state fatte cangiaminde stuèdeche sus 'a pàgene",
'tog-enotifrevealaddr'        => "Fa vedè l'indirizze e-mail jndr'à le e-mail de notifiche",
'tog-shownumberswatching'     => "Fa vedè 'u numere de le utinde ca uardene",
'tog-oldsig'                  => "Andeprime d'a firme esistende:",
'tog-fancysig'                => "Firma grezze cumme a 'nu teste de Uicchi (senza collegamende automatiche)",
'tog-externaleditor'          => "Ause n'editore esterne pe default (sulamende pe l'esperte, abbesogne de 'na configurazione speciele sus a 'u combiuter tue)",
'tog-externaldiff'            => "Ause na differenze esterne pe default (sulamende pe l'esperte, abbesogne de 'na configuraziona speciele sus a 'u computer tue)",
'tog-showjumplinks'           => 'Abbilite "zumbe a" pe accedere a le collegaminde',
'tog-uselivepreview'          => "Ause l'andeprime da 'u vive (JavaScript) (Sperimendele)",
'tog-forceeditsummary'        => "Ciercheme conferme quanne stoche a 'nzerische 'nu riepighe vianghe",
'tog-watchlisthideown'        => "Scunne le cangiaminde mie da 'a liste de le pàgene condrollete",
'tog-watchlisthidebots'       => "Scunne le cangiaminde de le not da 'a liste de le pàgene condrollete",
'tog-watchlisthideminor'      => "Scunne le cangiaminde stuèdeche da 'a liste de le pàgene condrollete",
'tog-watchlisthideliu'        => "Scunne le cangiaminde de l'utinde canusciute da 'a liste de le pàgene condrollete",
'tog-watchlisthideanons'      => "Scunne le cangiaminde de l'utinde scanusciute da 'a liste de le pàgene condrollete",
'tog-watchlisthidepatrolled'  => "Scunne le cangiaminde condrollete jndr'à liste de le pàggene condrollete",
'tog-ccmeonemails'            => "Manneme 'na copie de le mail ca je manne a l'ôtre utinde",
'tog-diffonly'                => 'No fà vedè le pàggene cu le condenute sotte a le differenze',
'tog-showhiddencats'          => 'Fa vedè le categorije scunnute',
'tog-norollbackdiff'          => "Non sce penzanne a le differenze apprisse l'esecuzione de 'nu rollback",

'underline-always'  => 'Sembre',
'underline-never'   => 'Maje',
'underline-default' => "Valore de default d'u browser",

# Font style option in Special:Preferences
'editfont-style'     => "Stile d'u carattere jndr'à l'area de le cangiaminde:",
'editfont-default'   => "Valore de default d'u browser",
'editfont-monospace' => 'Carattere Monospaced',
'editfont-sansserif' => 'Carattere Sans-serif',
'editfont-serif'     => 'Carattere Serif',

# Dates
'sunday'        => 'Dumèneche',
'monday'        => 'Lunedìe',
'tuesday'       => 'Martedìe',
'wednesday'     => 'Mercrudìe',
'thursday'      => 'Sciuvedìe',
'friday'        => 'Venerdìe',
'saturday'      => 'Sàbbete',
'sun'           => 'Dum',
'mon'           => 'Lun',
'tue'           => 'Mar',
'wed'           => 'Mer',
'thu'           => 'Giu',
'fri'           => 'Ven',
'sat'           => 'Sab',
'january'       => 'Scennáre',
'february'      => 'Febbráre',
'march'         => 'Màrze',
'april'         => 'Abbríle',
'may_long'      => 'Másce',
'june'          => 'Sciúgne',
'july'          => 'Lùglie',
'august'        => 'Agúste',
'september'     => 'Settèmmre',
'october'       => 'Ottòmmre',
'november'      => 'Novèmbre',
'december'      => 'Decèmmre',
'january-gen'   => 'Scennáre',
'february-gen'  => 'Febbráre',
'march-gen'     => 'Màrze',
'april-gen'     => 'Abbríle',
'may-gen'       => 'Másce',
'june-gen'      => 'Sciúgne',
'july-gen'      => 'Lùglie',
'august-gen'    => 'Agúste',
'september-gen' => 'Settèmmre',
'october-gen'   => 'Ottòmmre',
'november-gen'  => 'Novèmbre',
'december-gen'  => 'Decèmmre',
'jan'           => 'Sce',
'feb'           => 'Feb',
'mar'           => 'Mar',
'apr'           => 'Abb',
'may'           => 'Màs',
'jun'           => 'Sci',
'jul'           => 'Lug',
'aug'           => 'Agú',
'sep'           => 'Set',
'oct'           => 'Ott',
'nov'           => 'Nov',
'dec'           => 'Dec',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Categorije|Categorije}}',
'category_header'                => 'Pàggene jndr\'à categorie "$1"',
'subcategories'                  => 'Sotte Categorije',
'category-media-header'          => 'Media jndr\'à categorie "$1"',
'category-empty'                 => "''Sta categorije pe mò non ge tène manghe 'na pàgene e manghe 'nu media.''",
'hidden-categories'              => '{{PLURAL:$1|categorije scunnute|categorije scunnute}}',
'hidden-category-category'       => 'Categorije scunnute',
'category-subcat-count'          => "{{PLURAL:$2|Sta categorije tène sulamende 'na sottecategorije.|Sta categorije tène {{PLURAL:$1|'na sottecategorije|$1 sottecategorije}}, sus a 'nu totele de $2.}}",
'category-subcat-count-limited'  => 'Sta categorije tène {{PLURAL:$1|sottecategorije|le seguende $1 sottecategorije}}.',
'category-article-count'         => "{{PLURAL:$2|Sta categorije condiene sulamende 'a seguenda pàgene.|{{PLURAL:$1|'A seguende pàgene jè|le seguende $1 pàggene sonde }} condenute jndr'à sta categorije, sus a $2 totele.}}",
'category-article-count-limited' => "{{PLURAL:$1|'A pàgene seguente ste|Le $1 pàggene seguende stonne}} jndr'à categorija corrende",
'category-file-count'            => "{{PLURAL:$2|Sta categorije condene sulamende 'u seguende file.|{{PLURAL:$1|'U seguende file stè |le seguende $1 files stonne}} jndr'à sta categorije, sus a $2 totele.}}",
'category-file-count-limited'    => "{{PLURAL:$1|'U seguende file jè|$1 Le seguende file sonde}} jndr'à categorije corrende.",
'listingcontinuesabbrev'         => 'cond.',
'index-category'                 => 'Pàggene indicizzate',
'noindex-category'               => 'Pàggene none indicizzate',

'mainpagetext'      => "'''MediaUicchi ha state 'nstallete.'''",
'mainpagedocfooter' => "Vè vide [http://meta.wikimedia.org/wiki/Help:Contents User's Guide] pe l'mbormaziune sus a cumme s'ause 'u softuer wiki.

== Pe accumenzà ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Liste pe le configuraziune]
* [http://www.mediawiki.org/wiki/Manual:FAQ FAQ de MediaWiki]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Liste d'a poste de MediaWiki]",

'about'         => 'Sus a',
'article'       => 'Pàgene de le condenute',
'newwindow'     => "(iapre jndr'à 'na fenestra nova)",
'cancel'        => 'Scangìlle',
'moredotdotdot' => 'De cchiù...',
'mypage'        => "'A pàgene meje",
'mytalk'        => 'Ngazzaminde mie',
'anontalk'      => "'Ngazzaminde pe quiste IP",
'navigation'    => 'Naveghesce',
'and'           => '&#32;e',

# Cologne Blue skin
'qbfind'         => 'Cirche',
'qbbrowse'       => 'Sfoglie',
'qbedit'         => 'Cange',
'qbpageoptions'  => 'Pàgene currende',
'qbpageinfo'     => 'Condeste',
'qbmyoptions'    => 'Pàggene mije',
'qbspecialpages' => 'Pàggene speciale',
'faq'            => 'FAQ',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection'       => "Aggiunge 'na discussiona",
'vector-action-delete'           => 'Scangille',
'vector-action-move'             => 'Spuèste',
'vector-action-protect'          => 'Protegge',
'vector-action-undelete'         => 'Repristine',
'vector-action-unprotect'        => 'Sprotegge',
'vector-simplesearch-preference' => "Abbilete le suggereminde d'a ricerche avanzate (Sulamende pe le scheme a vettore)",
'vector-view-create'             => 'Ccreje',
'vector-view-edit'               => 'Cange',
'vector-view-history'            => "Vide 'u cunde",
'vector-view-view'               => 'Ligge',
'vector-view-viewsource'         => "Vide 'u sorgende",
'actions'                        => 'Aziune',
'namespaces'                     => 'Namespace',
'variants'                       => 'Variande',

'errorpagetitle'    => 'Errore',
'returnto'          => 'Tuerne a $1.',
'tagline'           => 'Da {{SITENAME}}',
'help'              => 'Ajute',
'search'            => 'Cirche',
'searchbutton'      => 'Cirche',
'go'                => 'Véje',
'searcharticle'     => 'Véje',
'history'           => "Storie d'a pàgene",
'history_short'     => 'Cunde',
'updatedmarker'     => "aggiornete da l'urtema visite meje",
'info_short'        => "'Mbormazione",
'printableversion'  => 'Versione ca se stambe',
'permalink'         => 'Collegamende ca remane pe sembre',
'print'             => 'Stambe',
'edit'              => 'Cange',
'create'            => 'Ccreje',
'editthispage'      => 'Cange sta pàgene',
'create-this-page'  => "Ccreje 'a pàgene",
'delete'            => 'Scangìlle',
'deletethispage'    => 'Scangille sta pàgene',
'undelete_short'    => "Annulle {{PLURAL:$1|'nu camgiamende|$1 cangiaminde}}",
'protect'           => 'Prutette',
'protect_change'    => 'cange',
'protectthispage'   => 'Prutigge sta pàgene',
'unprotect'         => 'Sprutette',
'unprotectthispage' => 'Sprutigge sta pàgene',
'newpage'           => 'Pàgene nova',
'talkpage'          => "'Ngazzete pe sta pàgene",
'talkpagelinktext'  => 'Parle',
'specialpage'       => 'Pàgene Speciele',
'personaltools'     => 'Struminde personele',
'postcomment'       => 'Seziona nove',
'articlepage'       => "Vide 'a pàgene de le condenute",
'talk'              => "'Ngazzaminde",
'views'             => 'Visite',
'toolbox'           => 'Cascette de le struminde',
'userpage'          => "Vide a pàgene de l'utende",
'projectpage'       => 'Vide a pàgene de le pruggette',
'imagepage'         => 'Vide a pàgene de le file',
'mediawikipage'     => 'Vide a pàgene de le messàgge',
'templatepage'      => 'Vide a pàgene de le template',
'viewhelppage'      => "Vide a pàgene de l'ajute",
'categorypage'      => 'Vide a pàgene de le categorije',
'viewtalkpage'      => "Vide le 'ngazzaminde",
'otherlanguages'    => "Jndr'à l'otre lènghe",
'redirectedfrom'    => '(Riderette da $1)',
'redirectpagesub'   => 'Pàgene de redirezione',
'lastmodifiedat'    => "Sta pàgene a state cangete l'urtema vote da $1, alle $2.",
'viewcount'         => "Sta pàggene ha state viste {{PLURAL:$1|'na vote|$1 vote}}.",
'protectedpage'     => 'Pàgene prutette',
'jumpto'            => 'Zumbe a:',
'jumptonavigation'  => 'navighesce',
'jumptosearch'      => 'cirche',
'view-pool-error'   => "Ne dispiace, le server stonne sovraccarecate jndr'à stu mumende.
Troppe utinde stonne a provene a vedè sta pàgene.
Pe piacere aspitte 'nu picche e pò pruève 'n'otra vote a trasè jndr'à sta pàgene.

$1",
'pool-timeout'      => "Tiembe d'attese scadute pu 'u blocche",
'pool-queuefull'    => "'A code de le sondagge jè chiene",
'pool-errorunknown' => 'Errore scanusciute',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Sus a {{SITENAME}}',
'aboutpage'            => 'Project:Sus a',
'copyright'            => "'U condenute jè disponibile sotte a $1.",
'copyrightpage'        => '{{ns:project}}:Copyrights',
'currentevents'        => 'Fatte de osce a die',
'currentevents-url'    => 'Project:Fatte de osce a die',
'disclaimers'          => 'No ne sacce ninde',
'disclaimerpage'       => 'Project:Scareca uarrile',
'edithelp'             => "Cangianne l'ajute",
'edithelppage'         => 'Help:Cangiaminde',
'helppage'             => 'Help:Condenute',
'mainpage'             => 'Pàgene Prengepàle',
'mainpage-description' => 'Pàgene Prengepàle',
'policy-url'           => 'Project:Reghele',
'portal'               => "Purtale d'a communitate",
'portal-url'           => "Project:Portale d'a Comunitate",
'privacy'              => "Reghele p'a privacy",
'privacypage'          => "Project:Regole p'a privacy",

'badaccess'        => 'Errore de permesse',
'badaccess-group0' => "Tu non ge puè fa l'azione ca è richieste.",
'badaccess-groups' => "L'azione ca tu è richieste è limitete a l'utinde ca stonne jndr'à {{PLURAL:$2|'u gruppe|une de ste gruppe}}: $1.",

'versionrequired'     => "Jè richieste 'a versione $1 de MediaUicchi",
'versionrequiredtext' => "Ha ausà 'a versione $1 de MediaUicchi pe ausà sta pàgene.
Vide [[Special:Version|Versione d'a pàgene]].",

'ok'                      => 'OK',
'retrievedfrom'           => 'Pigghjete da "$1"',
'youhavenewmessages'      => 'Tu tine $1 ($2).',
'newmessageslink'         => 'messàgge nuève',
'newmessagesdifflink'     => 'urteme cangiaminde',
'youhavenewmessagesmulti' => "T'onne arrevete mèssagge nueve sus 'a $1",
'editsection'             => 'cange',
'editsection-brackets'    => '[$1]',
'editold'                 => 'cange',
'viewsourceold'           => 'vide sorgende',
'editlink'                => 'cange',
'viewsourcelink'          => "vide 'u sorgende",
'editsectionhint'         => "Cange 'a sezione: $1",
'toc'                     => 'Condenute',
'showtoc'                 => 'fà vedè',
'hidetoc'                 => 'scunne',
'thisisdeleted'           => 'Vide o ripristine $1?',
'viewdeleted'             => 'Vue ccu vide $1?',
'restorelink'             => "{{PLURAL:$1|'nu cangiamende scangellete|$1 cangiaminde scangellete}}",
'feedlinks'               => 'Feed:',
'feed-invalid'            => "'U tipe d'u feed de sttoscrizione jè invalide.",
'feed-unavailable'        => 'Syndication feeds non ge sonde disponibbele',
'site-rss-feed'           => '$1 RSS Feed',
'site-atom-feed'          => '$1 Atom Feed',
'page-rss-feed'           => '"$1" RSS Feed',
'page-atom-feed'          => '"$1" Atom Feed',
'feed-atom'               => 'Atom',
'feed-rss'                => 'RSS',
'red-link-title'          => "$1 (non g'esiste - addà essere scritte)",

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Pàgene',
'nstab-user'      => "Pàgene de l'utende",
'nstab-media'     => 'Pàgene de le media',
'nstab-special'   => 'Pàgene Speciale',
'nstab-project'   => 'Pàgene de le pruggette',
'nstab-image'     => 'File',
'nstab-mediawiki' => 'Messàgge',
'nstab-template'  => 'Template',
'nstab-help'      => "Pàgene d'ajute",
'nstab-category'  => 'Categorije',

# Main script and global functions
'nosuchaction'      => 'Non ge stonne otre azione',
'nosuchactiontext'  => "L'aziona specifichete da l'URL non g'è canusciute da Uicchi.
Tu puè avè scritte male 'a URL, o quidde ca è scritte jè 'nu collegamende sbagliete.
Pò essere pure ca quiste jè 'nu bochere jndr'à 'u software de {{SITENAME}}.",
'nosuchspecialpage' => 'Non ge stonne pàggene speciele',
'nospecialpagetext' => "<strong>Tu è richieste 'na pàgena speciele ca non g'esiste.</strong>

Pe 'na liste de le pàggene speciele cirche aqquà [[Special:SpecialPages|{{int:specialpages}}]].",

# General errors
'error'                => 'Errore',
'databaseerror'        => "Errore de l'archivije",
'dberrortext'          => "Ha assute n'errore de sindassi de 'na inderrogazione sus a 'u database.
Quiste pò indicà 'nu bochere jndr'à 'u software.
L'urteme tendative de inderrogazione sus a 'u database ha state:
<blockquote><tt>\$1</tt></blockquote>
cu 'a funzione \"<tt>\$2</tt>\".
'U database ha returnate l'errore \"<tt>\$3: \$4</tt>\".",
'dberrortextcl'        => 'A assute \'n\'errore de sindasse sus a \'n\'inderrogazione d\'u database.
L\'urteme tendative de inderrogazione sus a \'u database ha state:
"$1"
ausanne \'a funzione "$2".
\'U database ha returnate l\'errore "$3: $4"',
'laggedslavemode'      => "Attenzione: 'A pàgene no ge tène cangiaminde recente.",
'readonly'             => 'Archivie blocchete',
'enterlockreason'      => "Mitte 'na raggione p'u blocche, 'ncludenne 'na stime de quanne 'u blocche avène luate.",
'readonlytext'         => "'U database jndr'à stu mumende jè blocchete pe nueve 'nzereminde e otre cangiaminde, pò essere 'nu blocche pe 'na manutenziona de ''routine'', apprisse 'a quale torne tutte a poste.

Le amministrature ca onne mise 'u blocche onne date sta motivazione: $1",
'missing-article'      => "'U database non ge iacchije 'u teste de 'na pàgene ca avesse acchià, nnomenete \"\$1\" \$2.

Stu fatte pò succedere quanne le collegaminde 'mbrà le differenze o le cunde non ge sonde aggiornete sus a 'na pàgene ca ha state scangellete.

Ce quiste non g'è 'u case, tu pò essere ca è 'cchiate 'nu bochere jndr'à 'u software.
Pe piacere manne 'na comunicazzione a 'n'[[Special:ListUsers/sysop|amministratore]], mettène jndr'à note pure l'URL.",
'missingarticle-rev'   => '(versione#: $1)',
'missingarticle-diff'  => '(Diff: $1, $2)',
'readonly_lag'         => "'U database ha state automaticamende blocchete purcè le server de le database ca depennene da 'u master onne sciute in eccezzione",
'internalerror'        => 'Errore inderne',
'internalerror_info'   => 'Errore inderne: $1',
'fileappenderrorread'  => 'Non ge se pò leggere "$1" mendre ca appende.',
'fileappenderror'      => 'Non ge se pò \'nzeccà "$1" a "$2".',
'filecopyerror'        => 'Non ge pozze cupià \'u fail "$1" jndr\'à "$2".',
'filerenameerror'      => 'Non ge pozze cangià \'u nome d\'u fail "$1" jndr\'à "$2".',
'filedeleteerror'      => 'Non ge pozze scangillà \'u fail "$1".',
'directorycreateerror' => 'Non ge pozze ccrejà \'a cartelle "$1".',
'filenotfound'         => 'Non ge stoche a iacchje \'u fail "$1".',
'fileexistserror'      => 'Non ge pozze scrivere sus a \'u file "$1": \'u file esiste già',
'unexpected'           => 'Valore inattese: "$1"="$2".',
'formerror'            => "Errore: non ge riesche a reggistrà 'u form",
'badarticleerror'      => "Quest'azione non ge pò essere fatte sus 'a sta pàgene.",
'cannotdelete'         => '\'A pàgene o \'u file "$1" non ge pò essere scangellate.
Pò essere ca ggià ha state scangellete da quacche otre.',
'badtitle'             => 'Titele sbagliete',
'badtitletext'         => "'A pàgene ca è cerchete tène 'nu titele errete, vacande, o jè 'nu collegamende inter-lènghe o inter-uicchi errete.
Pò essere ca tène une o cchiù carattere ca non ge ponne essere ausete jndr'à le titele.",
'perfcached'           => "'U date segeunde stè jndr'à cache e non ge pò essere aggiornete.",
'perfcachedts'         => "'U date segeunde stè jndr'à cache e l'urteme aggiornamente ha state $1.",
'querypage-no-updates' => "L'aggiornaminde pe sta pàgene sonde, pe mò, disabbilitete.
Le date ca stonne aqquà jndre non ge sonde aggiornete.",
'wrong_wfQuery_params' => "Parametre incorrette sus 'a wfQuery()<br />
Function: $1<br />
Query: $2",
'viewsource'           => "Vide 'u sorgende",
'viewsourcefor'        => 'pe $1',
'actionthrottled'      => 'Azione inderrotte',
'actionthrottledtext'  => "Cumme 'na mesure andi-spam, tu è state limitete da fà st'azione troppe vote jndr'à 'nu timbe piccinne e tu è subranete stu limite.
Pe piacere prueve cchiù tarde.",
'protectedpagetext'    => 'Sta pàgene ha state prutette pe no fa fà cangiaminde a uecchje.',
'viewsourcetext'       => "Tu puè vedè e cupià 'a sorgente de sta pàgene:",
'protectedinterface'   => "Sta pàgene te dè l'inderfacce de teste pìu software e jè blocchete pe prevenì l'abbuse.",
'editinginterface'     => "'''Fà attenziò:'''  Tu ste cange 'na pàgene ca jè ausete pe dà 'n'inderfacce de teste p'u software.
Le cangiaminde de sta pàgene vonne a mettene mane sus a l'inderfacce utende pe l'otre utinde.
Pe traduziune, pe piacere vide ce ause [http://translatewiki.net/wiki/Main_Page?setlang=roa-tara translatewiki.net], 'u pruggette de localizzazione de MediaUicchi.",
'sqlhidden'            => '(query SQL ascunnute)',
'cascadeprotected'     => 'Sta pàgene ha state prutette da le cangiaminde, purcè jè ingluse jndr\'à {{PLURAL:$1|seguende pàgene, ca jè|le seguende pàggene, ca sonde}} prutette cu l\'opzione "a caschete":
$2',
'namespaceprotected'   => "Non ge tine 'u permesse pe cangià pàggene d'u neimspeise '''$1'''.",
'customcssjsprotected' => "Non ge tine 'u permesse pe cangià sta pàgene, purcè tène otre configurazione personele.",
'ns-specialprotected'  => 'Le pàgene speciale no ponne essere cangete.',
'titleprotected'       => "Stu titele ha state prutette da 'a ccreazione da [[User:$1|$1]].
'U mutive jè ''$2''.",

# Virus scanner
'virus-badscanner'     => "Configurazione ca fece schife: Virus scanner scanusciute: ''$1''",
'virus-scanfailed'     => 'condrolle fallite (codece $1)',
'virus-unknownscanner' => 'antivirus scanusciute:',

# Login and logout pages
'logouttext'                 => "'''Tu tè scolleghete.'''

Tu puè condinuà a ausà {{SITENAME}} in mode anonime, o tu puè [[Special:UserLogin|collegarte 'n'otra vote]] cumme 'u stesse utende o cumme 'n'otre utende.
Note Bbuene ca certe pàggene ponne condinuà a essere viste cumme ce tu ste angore colleghete, fine a quanne a cache d'u browser no se sdeveche.",
'welcomecreation'            => "== Bovegne, $1! ==
'U cunde tue ha state ccrejete.
No te sce scurdanne de cangià le [[Special:Preferences|{{SITENAME}} preferenze tue]].",
'yourname'                   => 'Nome utende:',
'yourpassword'               => 'Passuord:',
'yourpasswordagain'          => "Scrive 'a passuord notra vote:",
'remembermypassword'         => "Arrencuerdete 'u nome mije sus a stu combiuter (pe 'nu massime de $1 {{PLURAL:$1|sciurne|sciurne}})",
'securelogin-stick-https'    => "Statte collegate ô HTTPS apprisse 'a trasute",
'yourdomainname'             => "'U nome d'u dominie tue:",
'externaldberror'            => "Vide bbuene, o stè 'n'errore de autendicazione a 'u database oppure tu non ge puè aggiorna 'u cunde tue esterne.",
'login'                      => 'Tràse',
'nav-login-createaccount'    => 'Tràse / Reggistrete',
'loginprompt'                => "Tu a tenè le cookies abilitate pe tràse jndr'à {{SITENAME}}.",
'userlogin'                  => 'Tràse / Reggistrete',
'userloginnocreate'          => 'Tràse',
'logout'                     => 'Isse',
'userlogout'                 => 'Isse',
'notloggedin'                => 'Non ge sì colleghete',
'nologin'                    => "Non ge tine n'utenze? '''$1'''.",
'nologinlink'                => "Ccreje 'nu cunde utende",
'createaccount'              => "Ccreje 'nu cunde",
'gotaccount'                 => "Tine già 'nu cunde? '''$1'''.",
'gotaccountlink'             => 'Tràse',
'createaccountmail'          => 'pe e-mail',
'createaccountreason'        => 'Mutive:',
'badretype'                  => 'Le passuord ca è scritte non ge sonde uguale.',
'userexists'                 => "'U nome de l'utende ca è scritte jè già ausete.
Mittene n'otre.",
'loginerror'                 => 'Errore de collegamende',
'createaccounterror'         => "Non ge puè ccrejà 'u cunde utende: $1",
'nocookiesnew'               => "'U cunde utende ha state ccrejete ma angore non g'è trasute jndr'à {{SITENAME}}.

{{SITENAME}} ause le cookie pe fà collegà l'utinde.
Tu tine le cookie disabbilitete.
Pe piacere, ce vuè ccu trase, abilitesce le cookie e pò prueve a mettere 'a ''login'' e 'a password.",
'nocookieslogin'             => "{{SITENAME}} ause le cookie pe fà trasè l'utinde.

Tu tine le cookie disabbilitete.
Pe piacere, vide ce l'abilitesce e pò prueve 'n'otra vote a mettere le date tue.",
'noname'                     => "Non gìè specifichete 'nu nome utende valide.",
'loginsuccesstitle'          => 'Tutte a poste, è trasute!',
'loginsuccess'               => "'''Mò tu si colleghete jndr'à {{SITENAME}} cumme \"\$1\".'''",
'nosuchuser'                 => "Non g'esiste n'utende cu 'u nome \"\$1\".
Fà attenzione ca le nome de l'utinde so senzibbele a le lettere granne e piccenne.
Vide bbuene a cumme l'è scritte, o [[Special:UserLogin/signup|ccreje n'utende nuève]].",
'nosuchusershort'            => 'Non ge ste nisciune utende cu \'u nome "<nowiki>$1</nowiki>".
Condrolle accume l\'è scritte.',
'nouserspecified'            => "A scrivere pe forze 'u nome de l'utende.",
'login-userblocked'          => 'Stu utende jè bloccate. Non ge puè trasè.',
'wrongpassword'              => "E mise 'na passuor sbagliete.
Prueve n'otra vote.",
'wrongpasswordempty'         => "'A passuord 'nzerite jè vianghe.
Mitta n'otra vota.",
'passwordtooshort'           => 'Le password onne a essere almene {{PLURAL:$1|1 carattere|$1 carattere}}.',
'password-name-match'        => "'A password toje adda essere diverse da 'u nome utende tue.",
'password-too-weak'          => "'A passuord mise jè troppe scarse e non ge pò essere ausate.",
'mailmypassword'             => 'Passuord nova pe e-mail',
'passwordremindertitle'      => 'Passuord temboranea nova pe {{SITENAME}}',
'passwordremindertext'       => "Quacchedune (pò essere tu, da quiste indirizze IP \$1) ha cerchete 'na nova password pe {{SITENAME}} (\$4).
'Na password temboranea pe l'utende \"\$2\" ha state ccrejete e ha state 'mbostete a \"\$3\".
Ce quiste ere quidde ca vulive, mo vide ce te colleghe e te scacchie 'na password nova.
'A password temboranea adda murè 'mbrà {{PLURAL:\$5|'nu giurne|\$5 giurne}}.

Ce quacche otre 'nvece ha fatte sta rechieste, o ce tu t'è recurdate 'a password, e non g'à vuè cu cangè cchiù, allore no sce penzanne a 'stu messagge e continue a ausà 'a vecchia password.",
'noemail'                    => 'Non ge stonne email reggistrete pe l\'utende "$1".',
'noemailcreate'              => "Tu ha mèttere 'n'indirizze e-mail valide",
'passwordsent'               => "'Na nova passuord ha state mannete a l'indirizze e-mail reggistrete pe \"\$1\".
Pe piacere, colleghete n'otra vota quanne l'è ricevute.",
'blocked-mailpassword'       => "L'indirizze IP tue jè blocchete pe le cangiaminde e accussì tu non ge puè ausà 'a funzione de recupere d'a password pe prevenìe l'abbuse.",
'eauthentsent'               => "'N'e-mail de conferme ha state mannete a l'indirizze ca tu è ditte.
Apprime ca otre e-mail avènene mannete a 'u cunde tue, tu ha seguì le 'struzione ca stonne jndr'à l'e-mail, pe confermà l'iscrizione.",
'throttled-mailpassword'     => "'Nu arrecordatore de password ha stete già mannete jndr'à {{PLURAL:$1|l'urtema ore|l'urteme $1 ore}}.
Pe prevenì l'abbuse, sulamende 'nu arrecordatore de password avene mannete ogne {{PLURAL:$1|ore|$1 ore}}.",
'mailerror'                  => "Errore mannanne 'a mail: $1",
'acct_creation_throttle_hit' => "Le visitature de sta Uicchi ca stonne ausene stu indirizze IP onne ccrejete {{PLURAL:$1|'nu cunde utende|$1 cunde utinde}} jndr'à l'urteme giurne, e onne raggiunde 'u numere massime ca se pò fà jndr'à stu periode.
'U resultete jè ca le visitature ca stonne ausene stu indirizze IP non ge ponne ccrejà otre cunde utinde nuève jndr'à stu mumende.",
'emailauthenticated'         => "L'indirizze e-mail ca ne date ha state autendichete 'u sciurne $2 a le $3.",
'emailnotauthenticated'      => "L'indirizze e-mail tue non g'a state angore autendichete.
Nisciuna mail t'avène mannete pe tutte le seguende dettaglie.",
'noemailprefs'               => "Specifiche 'n'indirizze e-mail pe ste dettaglie ca onne essere fatiete.",
'emailconfirmlink'           => "Conferme l'indirizze e-mail tue",
'invalidemailaddress'        => "L'indirizze e-mail non ge pò essere accettete cumme l'è scritte purcè tène 'nu formete invalide.
Pe piacere mitte l'indirizze a cumme criste cumanne, ce nò no 'u scè mettènne proprie.",
'accountcreated'             => 'cunde utende ccrejete',
'accountcreatedtext'         => "'U cunde utende pe $1 ha state ccrejete.",
'createaccount-title'        => "Ccreazzione de 'u cunde utende pe {{SITENAME}}",
'createaccount-text'         => 'Quacchedune ha ccrejete \'nu cunde utende cu l\'indirizze e-mail tue sus a {{SITENAME}} ($4) chiamete "$2", cu password "$3".
Tu, mò, t\'avisse a collegà e cangià \'a password toje.

Tu puè pure cacà stu messagge, ce stu cunde utende ha state ccrejete pe errore.',
'usernamehasherror'          => "'U nome utende non ge pò tenè carattere hash",
'login-throttled'            => "Urtemamende tu è pruvate troppe vote a trasè jndr'à Uicchipèdie
Pe piacere vide c'aspitte 'nu picche de timbe apprime de pruvà 'n'otra vote.",
'loginlanguagelabel'         => 'Lénga: $1',
'suspicious-userlogout'      => "'A richiesta toje de assè ha state bloccate purcè pare ca ha state mannate da 'nu browser scuasciate o da 'a cache de 'nu proxy.",

# JavaScript password checks
'password-strength'            => "Lunghezze d'a passuord stimate: $1",
'password-strength-bad'        => 'SCKEFUSE',
'password-strength-mediocre'   => 'megghie de ninde',
'password-strength-acceptable' => 'pò scè',
'password-strength-good'       => 'bbuène',
'password-retype'              => "Rescrive 'a passuord aqquà",
'password-retype-mismatch'     => 'Le passuord no ge sonde uguale',

# Password reset dialog
'resetpass'                 => "Cange 'a password",
'resetpass_announce'        => "Tu tè colleghete cu 'nu codece mannete pe e-mail temboranee.
Pe spiccià 'a procedure de collegamende, tu a 'mbostà 'na password nove aqquà:",
'resetpass_text'            => "<!-- Mitte 'u teste aqquà -->",
'resetpass_header'          => "Cange 'a password d'u cunde utende",
'oldpassword'               => 'Vécchie passuord:',
'newpassword'               => 'Nova passuord:',
'retypenew'                 => "Scrive n'otra vota 'a passuord nova:",
'resetpass_submit'          => "'Mboste 'a passuord e colleghete",
'resetpass_success'         => "'A password toje ha state cangete cu successe! Mò te puè collegà...",
'resetpass_forbidden'       => 'Le Password non ge ponne cangià',
'resetpass-no-info'         => 'Tu a essere colleghete pe accedere a sta pàgene direttamende.',
'resetpass-submit-loggedin' => "Cange 'a password",
'resetpass-submit-cancel'   => 'Annulle',
'resetpass-wrong-oldpass'   => "'A password temboranea o quedda corrende jè invalide.
Pò essere ca tu è già cangete 'a password toje o è richieste una temboranea nove.",
'resetpass-temp-password'   => 'Password temboranea:',

# Edit page toolbar
'bold_sample'     => 'Teste grascette',
'bold_tip'        => 'Teste grascette',
'italic_sample'   => 'Teste corsive',
'italic_tip'      => 'Scritte in corsivo',
'link_sample'     => "Titele d'u collegamende",
'link_tip'        => "Collegamende 'nderne",
'extlink_sample'  => "http://www.example.com Nome d'u collegamende",
'extlink_tip'     => "Collegamende de fore a Uicchipedie (arrecuerdete 'u prefisse http://)",
'headline_sample' => "Teste d'a Testete",
'headline_tip'    => 'Levèlle 2 tèstete',
'math_sample'     => "Mitte 'a formule aqquà",
'math_tip'        => 'Formula matemateche (LaTeX)',
'nowiki_sample'   => "Mitte 'u teste non formattate aqquà",
'nowiki_tip'      => "No scè penzanne 'a formattazione de Uicchi",
'image_sample'    => 'Esembie.jpg',
'image_tip'       => 'File ingapsulete',
'media_sample'    => 'Esembie.ogg',
'media_tip'       => 'File de collegamende',
'sig_tip'         => "'A firma toje cu l'orarie e 'a sciurnete",
'hr_tip'          => 'Linee orizzondele (ausele picche)',

# Edit pages
'summary'                          => 'Riepileghe:',
'subject'                          => 'Oggette/Testete:',
'minoredit'                        => 'Cangiaminde stuèdeche',
'watchthis'                        => 'Condrolle sta pàgene',
'savearticle'                      => "Registre 'a vôsce",
'preview'                          => 'Andeprime',
'showpreview'                      => "Vide l'andeprime",
'showlivepreview'                  => "Andeprime da 'u vive",
'showdiff'                         => 'Fa vedè le cangiaminde',
'anoneditwarning'                  => "'''Attenziò:''' Tu non ge sinde colleghete..
L'indirizze IP tue avène reggistrete jndr'à le cangiaminde de sta pàgene.",
'anonpreviewwarning'               => "''Tu non ge sì collegate. Reggistranne le cangiaminde jndr'à sta pàgene iesse l'indirizze IP tune jndr'à storie.''",
'missingsummary'                   => "'''Arrecuèrdete:''' Tu non g'è provvedute a 'nu riepileghe de le cangiaminde.
Ce tu cazze Reggistre 'n'otra vote, 'u cangiamende tue avène memorizzete senze une.",
'missingcommenttext'               => "Pe piacere mitte 'nu commende aqquà sotte.",
'missingcommentheader'             => "'''Arrecuèrdete:''' Tu non g'è provvedute a 'nu soggette/testate pe stu commende.
Ce tu cazze \"{{int:savearticle}}\" 'n'otra vote, 'u cangiamende tune avène memorizzate senze jidde.",
'summary-preview'                  => "Andeprime d'u riepileghe:",
'subject-preview'                  => "Andeprime de l'Oggette/Testete:",
'blockedtitle'                     => "L'utende è blocchete",
'blockedtext'                      => "''''U nome de l'utende o l'indirizze IP ha state blocchete.'''

'U blocche ha state fatte da $1.
'U mutive date jè ''$2''.

* 'U Blocche accumenze: $8
* 'U Blocche spicce: $6
* Tipe de blocche: $7

Tu puè condatta $1 o n'otre [[{{MediaWiki:Grouppage-sysop}}|amministratore]] pe 'ngazzarte sus a 'u blocche.
Tu non ge puè ausà 'u strumende 'manne 'na mail a stu utende' senza ca mitte n'indirizze e-mail valide jndr'à le
[[Special:Preferences|preferenze tue]] e ce è state blocchete sus 'a l'use sue.
L'IP ca tine mò jè $3 e 'u codece d'u blocche jè #$5.
Pe piacere mitte ste doje 'mbormaziune ce manne 'na richieste de sblocche.",
'autoblockedtext'                  => "L'indirizze IP tue ha state automaticamende blocchete purcè ha state ausete da n'otre utende, ca avère state blocchete da \$1.
'U mutive date jè 'u seguende:

:''\$2''

* Inizie d'u blocche: \$8
* Scadenze d'u blocche: \$6
* Blocche 'ndise: \$7

Tu puè cundattà \$1 o une de l'otre [[{{MediaWiki:Grouppage-sysop}}|amministrature]] pe parà de stu probbleme.

Vide Bbuene ca tu non ge puè ausà 'a funziona \"manne n'e-mail a stu utende\" senze ca tu tìne 'n'indirizze e-mail valide e reggistrete jndr'à seziona [[Special:Preferences|me piace accussì]] e tu non ge sinde blocchete da ausarle.

L'indirizze IP corrende jè \$3, e 'u codece d'u blocche jè #\$5.
Pe piacere mitte tutte le dettaglie ca ponne essere utile pe le richieste tue.",
'blockednoreason'                  => 'nisciune mutive',
'blockedoriginalsource'            => "'A sorgende de '''$1''' jè mostreta aqquà sotte:",
'blockededitsource'                => "'U teste de le '''cangiaminde tue''' sus a '''$1''' ìu stè vide aqquà sotte:",
'whitelistedittitle'               => "Pe fa le cangiaminde t'a collegà",
'whitelistedittext'                => 'Tu ha $1 pàggene da cangià.',
'confirmedittext'                  => "Tu a confermà l'indirizze e-mail tue apprime de cangià le pàggene.
Pe piacere mitte e validesce l'indirizze e-mail tue ausanne le [[Special:Preferences|preferenze de l'utende]].",
'nosuchsectiontitle'               => "Non ge pozze acchià 'a sezione",
'nosuchsectiontext'                => "Tu stè prueve a cangià 'na sezione ca non g'esiste.
Pò essere ca ha state spustate o scangellate quanne tu ste vedive 'a pàgene.",
'loginreqtitle'                    => "T'a collegà pe forze",
'loginreqlink'                     => 'trase',
'loginreqpagetext'                 => 'Tu a $1 pe vedè otre pàggene.',
'accmailtitle'                     => 'Passuord mannete.',
'accmailtext'                      => "'A password ccrejete a uecchije pe [[User talk:$1|$1]] ha state mannete sus 'a $2.

'A password pe stu cunde utende pò essere cangete sus a pàgene ''[[Special:ChangePassword|cange password]]'' 'na vote ca te colleghete.",
'newarticle'                       => '(Nuève)',
'newarticletext'                   => "Tu ste segue 'nu collegamende a pàgene ca angore non g'esiste.
Pe ccrejà 'a pàgene, accuminze a scrivere jndr'à 'u scatole de sotte (vide 'a [[{{MediaWiki:Helppage}}|pàggene d'ajute]] pe avè cchiù 'mbormaziune).
Ce tu te iacche aqquà e manghe tu 'u se purcè, allore cazze 'u buttone '''back''' d'u brauser.",
'anontalkpagetext'                 => "----''Queste jè 'na pàgene de 'ngazzaminde pe 'n'utende anonime, ca non ge vò ccu ccreje angore 'nu cunde utende, o de ce non g'u use.
Nuje auseme 'n'indirizze IP (ca jè numereche) pe identificarle.
E' normale ca essende 'n'indirizze IP pò essere ausete pure da otre utinde ca 'u pigghiene.
Ce tu non ge si 'n'utende anonime e pinze ca le commende ca so revolte a te sonde studecarije, pe piacere [[Special:UserLogin/signup|ccreje 'nu cunde utende]] o [[Special:UserLogin|tràse]] pe no fà confusione jndr'à 'u future cu otre utinde anoneme.''",
'noarticletext'                    => 'Non ge stè scritte ninde jndr\'à sta pàgene.
Tu puè [[Special:Search/{{PAGENAME}}|cercà pe quiste titele]] jndr\'à otre pàggene, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}}] oppure [{{fullurl:{{FULLPAGENAME}}|action=edit}} cange sta pàgene]</span>.',
'noarticletext-nopermission'       => "Pe mò non ge stè teste jndr'à sta pàgene.
Tu puè [[Special:Search/{{PAGENAME}}|cercà pe stu titele]]  jndr'à otre pàggene,
o <span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} cirche jndr'à l'archivije cullegate]</span>.",
'userpage-userdoesnotexist'        => '\'U cunde utende "$1" non g\'è reggistrete.
Pe piacere, condrolle ce tu vuè cu ccreje/cange sta pàgene.',
'userpage-userdoesnotexist-view'   => '\'U cunde utende "$1" non g\'è reggistrate.',
'blocked-notice-logextract'        => "Stu utende jè correndemende bloccate.<br />
L'urteme archivije de le bloccaminde se iacche aqquà sotte pe referimende:",
'clearyourcache'                   => "'''Vide Bbuene - Apprisse 'a reggistrazione, tu puè zumbà 'a cache d'u browser tue pe vedè le cangiaminde.'''
*'''Mozilla / Firefox / Safari:''' cazze 'u ''Shift'' e condemboranemende cazze 'u buttone ''Aggiorna'', o cazze 'nzieme ''Ctrl-F5'' o ''Ctrl-R'' (''Command-R'' sus a 'nu Macintosh);
*'''Konqueror: '''cazze ''Aggiorna'' o cazze ''F5'';
*'''Opera:''' pulizze 'a cache da ''Tools → Preferences'' (in inglese) (Struminde - Preferenze in tagliàne);
*'''Internet Explorer:''' cazze ''Ctrl'' e condemboraneamende cazze ''Aggiorna,'' o cazze ''Ctrl-F5''.",
'usercssyoucanpreview'             => "'''Conziglie:''' Ause 'u buttone \"{{int:showpreview}}\" pe condrollà 'u CSS nuève apprime de reggistrà.",
'userjsyoucanpreview'              => "'''Conziglie:''' Ause 'u buttone \"{{int:showpreview}}\" pe condrollà 'u JavaScript nuève apprime de reggistrà.",
'usercsspreview'                   => "'''Arrecuerdete ca tu ste vide sulamende in andeprime 'u CSS tue.'''
'''Non g'à state angore reggistrete ninde!'''",
'userjspreview'                    => "'''Arrecuerdete ca tu ste vide/teste sulamende in andeprime 'u JavaScript tue.'''
'''Non g'à state angore reggistrete ninde!'''",
'userinvalidcssjstitle'            => "'''Attenziò:''' Non ge stè 'nu skin \"\$1\".
Arrecuerdete ca jndr'à le file personalizzete .css e .js s'ause scrivere le titele cu le lettere piccenne, pe esembie {{ns:user}}:Foo/vector.css è diverse da {{ns:user}}:Foo/Vector.css.",
'updated'                          => '(Cangiete)',
'note'                             => "'''Vide Bbuene:'''",
'previewnote'                      => "'''Queste è sole 'n'andeprime;
le cangiaminde non g'onne state angore reggistrete!'''",
'previewconflict'                  => "Sta andeprime fece vedè 'u teste ca ste jndr'à 'u teste de l'area de sus cumme avène fore ce tu decide cu reggistre.",
'session_fail_preview'             => "'''Ne dispiace! Non ge putime processà 'u cangiamende tue purcè s'a perse 'a sessione de le date.
Pe piacere pruève 'n'otra vote.
Ce angore non ge funzione ninde, [[Special:UserLogout|isse]] e tràse 'n'otre vote.'''",
'session_fail_preview_html'        => "'''Ne dispiace! nuje non ge putime processà 'u cangiamende tue purcè è perse 'a sessione de le date.'''

''Purcè {{SITENAME}} tène abbilitate l'HTML grezze, l'andeprime è scunnute cumme precauzione condre a l'attacche cu 'u JavaScript.''

'''Ce quiste jè 'nu tendative corrette de cangiamende, pe piacere prueve 'n'otra vote.'''
Ce angore tìne probbleme, prueve a [[Special:UserLogout|assè]] e te recolleghe 'n'otra vote.",
'token_suffix_mismatch'            => "''''U cangiamende tue ha state scettate purcè 'u ''client'' tue non ge tène le carattere de le punde jndr'à 'u gettone de cangiamende.'''
'U cangiamende ha state scettate pe prevenì corruzione d'u teste d'a pàgene.
Certe vote, stu fatte succede quanne tu ste ause 'nu servizie proxy cu le bochere e anonime.",
'editing'                          => 'Cangiaminde de $1',
'editingsection'                   => 'Cangiaminde de $1 (sezione)',
'editingcomment'                   => 'Cangiaminde de $1 (seziona nove)',
'editconflict'                     => 'Conflitte de cangiaminde: $1',
'explainconflict'                  => "Quacchedune otre ha cangete 'a pàgene apprime ca tu accumenzasse a fà 'u cangiamende tue.
'U teste ca iacchie sus condene 'u teste d'a pàgene accume se iacchije jndr'à stu mumende.
Le cangiaminde sonde fatte vedè jndr'à vanne de sotte.
Tu puè scuagghià le cangiaminde jndr'à 'u teste de mò.
'''Sulamende''' 'u teste ca stè sus avène reggistrate cazzanne 'u buttone \"{{int:savearticle}}\".",
'yourtext'                         => "'U teste tue",
'storedversion'                    => 'Versione archivijete',
'nonunicodebrowser'                => "'''FA ATTENZIO': 'U browser tue non ge capisce l'unicode.'''
'Na fatije ste jndr'à stu poste ca te conzende de reggistrà senza probbleme 'a pàgene: le carattere ca non ge sonde ASCII le vide cumme a tanda scatele cumme a codece esadecimale.",
'editingold'                       => "'''FA ATTENZIO': Tu ste cange 'na revisione de sta pàgena scadute.'''
Ce tu a reggistre, ogne cangiamende fatte apprisse a sta revisione avène perdute.",
'yourdiff'                         => 'Differenze',
'copyrightwarning'                 => "Pe piacere vide ca tutte le condrebbute de {{SITENAME}} sonde considerete de essere rilasciete sotte 'a $2 (vide $1 pe le dettaglie).
Ce tu non ge vuè ca le condrebbute tue avènene ausete da otre o avènene cangete, non le scè mettènne proprie.<br />
Tu na promettere pure ca le cose ca scrive tu, sonde 'mbormaziune libbere o copiete da 'nu pubbleche dominie.<br />
'''NON METTE' NISCIUNA FATJE CA JE' PROTETTE DA DERITTE SENZA PERMESSE!'''",
'copyrightwarning2'                => "Pe piacere vide ca tutte le condrebbute de {{SITENAME}} ponne essere cangete, alterate o luvete da otre condrebbutore.
Ce tu non ge vuè ca quidde ca scrive avène cangete da tre, allore non scè scrivenne proprie aqquà.<br />
Tu ne stè promitte ca quidde ca scrive tu, o lè scritte cu 'u penziere tue o lè cupiate da risorse de pubbliche dominie o sembre robba libbere (vide $1 pe cchiù dettaglie).
'''NO REGGISTRA' FATIJE CUPERTE DA 'U COPYRIGHT SENZA PERMESSE! NO FA STUDECARIE!'''",
'longpageerror'                    => "'''ERRORE: 'U teste ca tu vuè ccu reggistre è luenghe $1 kilobyte, invece 'u limite massime jè de $2 kilobyte.'''
Non ge puè reggistrà sta pàggene.",
'readonlywarning'                  => "'''FA ATTENZIO': 'U database ha state bloccate pe manutenziona, e allore tu non ge puè reggistrà le cangiaminde ca ste face mò.'''
Tu puè fa 'na bella cose, tagghie e 'nzicche le cangiaminde jndr'à 'nu file de teste sus a 'u combiuter tue e pò cchiù tarde le reggistre sus 'a Uicchi.

L'amministratore ca ha bloccate 'u database ha scritte stu mutive: $1",
'protectedpagewarning'             => "'''ATTENZIO': Sta pàgene ha state bloccate e allore sulamende le utinde cu le privilegge de ''sysop'' ponne cangiarle.'''
L'urteme archivie de le trasute ha state previste aqquà sotte pe referimende:",
'semiprotectedpagewarning'         => "'''Fà attenzione:''' Sta pàgene ha state bloccate accussì sulamende l'utinde reggistrete ponne fà cangiaminde.
L'urteme archivije de le trasute ha state previste aqquà sotte pe referimende:",
'cascadeprotectedwarning'          => "'''FA ATTENZIO':''' Sta pàgene ha state blocchete accussì sulamende l'utinde ca tènene le deritte de amministratore a ponne cangià, purcè inglude {{PLURAL:$1|pàgene|pàggrnr}} prutette a cascate:",
'titleprotectedwarning'            => "'''ATTENZIONE: Sta pàgene ha state bloccate accussì sulamende [[Special:ListGroupRights|specifice diritte]] a ponne ccrejà.'''
L'urteme archivije de le trasute jè provviste sotte pe referimende:",
'templatesused'                    => "{{PLURAL:$1|Template|Template}} ausate sus 'a sta pàgene:",
'templatesusedpreview'             => "{{PLURAL:$1|Template|Template}} ausate jndr'à sta andeprime:",
'templatesusedsection'             => "{{PLURAL:$1|Template|Template}} ausate jndr'à sta sezione:",
'template-protected'               => '(prutette)',
'template-semiprotected'           => '(mmienze protette)',
'hiddencategories'                 => 'Sta pàgene jè membre de {{PLURAL:$1|1 categorja|$1 categorije}} scunnute:',
'nocreatetitle'                    => "Ccreazione d'a pàgene limitete",
'nocreatetext'                     => "{{SITENAME}} ha restritte l'abilità de ccrejà pàggene nuéve.
Tu puè turnà rrete e cangià 'na pàgene ca già esiste, oppure puè [[Special:UserLogin|trasè o ccrejà n'utende nuéve]].",
'nocreate-loggedin'                => 'Non ge tine le permesse pe ccreja pàggene nuève.',
'sectioneditnotsupported-title'    => 'Sezione de le cangiaminde none supportate',
'sectioneditnotsupported-text'     => "Sezione de le cangiaminde non g'è supportate sus a sta pàgene de cangiaminde.",
'permissionserrors'                => 'Errore de permesse',
'permissionserrorstext'            => "Tu non ge tine 'u permesse pe fà ste cose, pe {{PLURAL:$1|stu mutive|ste mutive}}:",
'permissionserrorstext-withaction' => "Tu non ge tine 'u permesse pe $2, pe {{PLURAL:$1|stu mutive|ste mutive}}:",
'recreate-moveddeleted-warn'       => "'''Fa Attenziò: Ste ccreje 'na pàgene ca avère state scangillete apprime.'''

Vide bbuene ce l'ha ccrejà sta pàgene.
Sinde a me, vide l'archivie de le scangellaminde e de le spustaminde accussì sì secure de quidde cà fà:",
'moveddeleted-notice'              => "Sta pàgene ha state scangellate.
L'archivije de le scangellaminde e de le spustaminde pe sta pàgene 'u puè vedè aqquà sotte pe riferimende.",
'log-fulllog'                      => "Vide l'arichivije comblete",
'edit-hook-aborted'                => "Cangiamende annullete da  'nu ''hook''.
Non g'à date nisciune mutive.",
'edit-gone-missing'                => "Non ge puè cangià sta pàgene.
Pare proprie ca l'onne scangellete.",
'edit-conflict'                    => 'conflitte de cangiaminde.',
'edit-no-change'                   => "'U cangiamende ca p fatte, avène scettate purcè 'u teste non g'à cangete manghe de 'na virgola.",
'edit-already-exists'              => "Non ge puè ccrejà 'na pàgene nove purcè esiste già!",

# Parser/template warnings
'expensive-parserfunction-warning'        => "Fà attenziò: Sta vosce tène 'nu sbuenne de funziune de chiamate a l'analizzatore.

Avessere a essere mene de $2 {{PLURAL:$2|chiamate|chiamate}}, 'nvece mò {{PLURAL:$1|ste $1 chiamate|ne stonne $1 chiamate}}.",
'expensive-parserfunction-category'       => 'Pàggene cu troppe chiamate a le funziune de analisi',
'post-expand-template-inclusion-warning'  => "Attenziò: 'a dimenzione d'u template jè troppe granne.
Certe template ponne non essere 'ngluse.",
'post-expand-template-inclusion-category' => "Pàggene addò le dimenziune d'u template sonde assaije proprie",
'post-expand-template-argument-warning'   => "Attenziò: Sta pàgene tène almene 'n'argomende de 'nu template ca jè troppe larie.
Ste argumende onne state luete.",
'post-expand-template-argument-category'  => 'Pàggene ca condenene template cu quacche argomende zumbete',
'parser-template-loop-warning'            => "Amme acchiete 'nu cicle de template: [[$1]]",
'parser-template-recursion-depth-warning' => "Ha state supranete 'u limite di ricorsione de le template ($1)",
'language-converter-depth-warning'        => "'U convertitore de lènghe ha subranate 'u limite de profonnetà ($1)",

# "Undo" feature
'undo-success' => "'U cangiamende pò essere annullate.
Pe piacere verifichesce 'u combronde sotte pe condrollà ca quiste ca tu vuè ccu face e pò reggistrè le cangiaminde aqquà sotte pe spiccià l'annullamende d'u cangiamende.",
'undo-failure' => "'U cangiamende non ge pò essere annullete purcè stè 'nu conflitte de cangiaminde indermedije.",
'undo-norev'   => "'U cangiamende non ge pò essere annullete purcè non g'esiste o a state scangellete.",
'undo-summary' => "Repristine 'a revisione $1 da [[Special:Contributions/$2|$2]] ([[User talk:$2|'Ngazzaminde]])",

# Account creation failure
'cantcreateaccounttitle' => "Non ge puè ccrejà 'nu cunde utende",
'cantcreateaccount-text' => "'A creazione d'u cunde utende da stu 'ndirizze IP ('''$1''') ha state blocchete da [[User:$3|$3]].

'U mutive dete da $3 jè ''$2''",

# History pages
'viewpagelogs'           => "Vide l'archivie pe sta pàgene",
'nohistory'              => "Sta pàgene non ge tène 'a storie de le cangiaminde ca onne state fatte.",
'currentrev'             => 'Versione de osce a die',
'currentrev-asof'        => 'Revisiona corrende de $1',
'revisionasof'           => 'Versione de $1',
'revision-info'          => "Versione scritte jndr'à $1 da $2",
'previousrevision'       => '← Versione Vecchje',
'nextrevision'           => 'Versione cchiù nova →',
'currentrevisionlink'    => 'Versione de mò',
'cur'                    => 'cur',
'next'                   => 'prosseme',
'last'                   => 'urteme',
'page_first'             => 'prime',
'page_last'              => 'urteme',
'histlegend'             => "Differenze de selezione: signe le radio box de le versiune ca vuè cu combronde e cazze ''invio'' o 'u buttone ca ste sotte.<br />
Leggenda: (cur) = differenze cu 'a versiona corrende,
(last) = differenze ca 'a versione precedende, M = cangiaminde stuédeche.",
'history-fieldset-title' => "Sfogghje 'a storie",
'history-show-deleted'   => 'Sule le scangellate',
'histfirst'              => 'Prime',
'histlast'               => 'Urteme',
'historysize'            => '({{PLURAL:$1|1 byte|$1 bytes}})',
'historyempty'           => '(vacande)',

# Revision feed
'history-feed-title'          => 'Storie de le revisiune',
'history-feed-description'    => "Storie de le revisiune oe sta pàgene sus 'a le Uicchipèdie",
'history-feed-item-nocomment' => '$1 a $2',
'history-feed-empty'          => "'A vosce ca è cerchete non g'esiste.
Pò essere ca ha state scangellete da Uicchi o ha state renomenate..
Pruève a [[Special:Search|cercò sus a Uicchi]] 'mbrà le vosce cchiù rilevande.",

# Revision deletion
'rev-deleted-comment'         => '(commende scangillete)',
'rev-deleted-user'            => '(nome utende scangillete)',
'rev-deleted-event'           => "(azione de l'archivie scangillete)",
'rev-deleted-user-contribs'   => '[nome utende o indirizze IP luate - cangiamende scunnute da le condrebbute]',
'rev-deleted-text-permission' => "Sta revisione d'a pàgene ha state '''scangellate'''.
Puè acchijà cchiù 'mbormaziune sus a [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} l'archivije de le scangellaminde].",
'rev-deleted-text-unhide'     => "Sta revisione d'a pàgene ha state '''scangellate'''.
Puè acchijà cchiù 'mbormaziune sus a [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} l'archivije de le scangellaminde].
Cumme a 'n'amministratore tu puè angore [$1 vedè sta revisiona] ce tu avveramende a vuè ccù vide.",
'rev-suppressed-text-unhide'  => "Sta revisione d'a pàgene ha state '''soppresse'''.
Puè acchijà cchiù 'mbormaziune sus a [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} l'archivije de le soppressiune].
Cumme a 'n'amministratore tu puè angore [$1 vedè sta revisiona] ce tu avveramende a vuè ccù vide.",
'rev-deleted-text-view'       => "Sta revisione d'a pàgene ha state '''scangellate'''.
Cumme a 'n'amministratore tu a puè vedè angore; stonne cchiù 'mbormaziune jndr'à l'[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} archiviè de le scangellaminde].",
'rev-suppressed-text-view'    => "Sta revisione d'a pàgene ha state '''soppresse'''.
Cumme a 'n'amministratore tu puè vedè; puè acchijà cchiù 'mbormaziune sus a [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} l'archivije de le soppressiune].",
'rev-deleted-no-diff'         => "Tu non ge piè vedè sta differenze purcè une de le revisiune ha state '''scangellate'''.
Pò essere ca jacchie quacche dettaglie jndr'à l'[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} archiviè de le scangellaminde].",
'rev-suppressed-no-diff'      => "Tu non ge puè vedè sta differenze purcé une de le revisiune ha state '''scangellate'''.",
'rev-deleted-unhide-diff'     => "Une de le revisiune de sta differenza ha state '''scangellate'''.
Pò essere ca iacchije quacche dettaglie jndr'à l'[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} archiviè de le scangellaminde]..
Cumme a 'n'amministratore tu puè angore [$1 vedè sta differenze] ce tu proprie si inderessate.",
'rev-suppressed-unhide-diff'  => "Une de le revisiune de sta differenze ha state '''soppresse'''.
Pò essere ca stonne cchiù 'mbormaziune jndr'à l'[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} archivie de le soppressiune].
Cumme 'n'amministratore tu puè angore [$1 vedè sta differenze] ce te inderesse avveramende.",
'rev-deleted-diff-view'       => "Une de le revisiune de sta differenze ha state '''scangellate'''.
Cumme 'n'amministratore tu puè vedè ste differenze; pò essere ca stonne cchiù 'mbormaziune jndr'à l'[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} archivie de le scangellaminde].",
'rev-suppressed-diff-view'    => "Une de le revisiune de sta differenze ha state '''soppresse'''.
Cumme 'n'amministratore tu puè vedè ste differenze; pò essere ca stonne cchiù 'mbormaziune jndr'à l'[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} archivie de le soppressiune].",
'rev-delundel'                => 'fa vedè/scunne',
'rev-showdeleted'             => 'fà vedè',
'revisiondelete'              => 'Scangille/Repristine revisiune',
'revdelete-nooldid-title'     => 'Revisione de destinazione invalida',
'revdelete-nooldid-text'      => "Tu non g'è specificate 'na revisione da arrivà pe abbilità sta funzione. 'A specifica revisione non g'esiste oppure tu stè pruève a scunnè 'a revisona corrende.",
'revdelete-nologtype-title'   => 'Nisciune tipe de archivije ha state specifichete',
'revdelete-nologtype-text'    => "Tu non g'è specificate 'u tipe de l'archivije pe eseguì st'aziona.",
'revdelete-nologid-title'     => "Richieste jndr'à l'archivije invalide",
'revdelete-nologid-text'      => "Tu non g'è specificate l'archivije de destinazione de l'evende pe eseguì sta funzione o 'a specifiche entrate non g'esiste.",
'revdelete-no-file'           => "'U file specificate non g'esiste.",
'revdelete-show-file-confirm' => 'Si secure ca vuè ccu vide \'a revisione scangellate d\'u file "<nowiki>$1</nowiki>" \'u $2 a le $3?',
'revdelete-show-file-submit'  => 'Sìne',
'revdelete-selected'          => "'''{{PLURAL:$2|Revisiona selezionete|Revisiune selezionete}} de [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Fatte de l'archivije selezionete|Fatte de l'archivije selezionete}}:'''",
'revdelete-text'              => "'''Le revisiune scangellete e le evende iessene angore jndr'à storie d'a pàgene e jndr'à l'archivije, ma stuezze d'u condenute lore pò essere inaccessibbele a 'u pubbleche.'''
Otre amministrature sus a {{SITENAME}}ponne angore trasè jndr'à 'u condenute scunnute e 'u ponne scangellà 'n'otra vote ausanne st'inderfacce, senze 'mbostà otre restriziune.",
'revdelete-confirm'           => 'Pe piacere conferme ca tu vuè ccu face sta cose, ce tu è capite le conseguenze e ce quidde ca ste face jè in accorde cu le [[{{MediaWiki:Policy-url}}|reghele]] de Uicchipèdie.',
'revdelete-suppress-text'     => "'A soppressione adda essere ausate '''sulamende''' jndr'à le case seguende:
* Date personale inopportune
*: ''indirizze, numere de telefono, codice fiscale, ecc.''",
'revdelete-legend'            => "'Mboste le restriziune sus 'a visibbilità",
'revdelete-hide-text'         => "Scunne 'u teste d'a revisione",
'revdelete-hide-image'        => "Scunne 'u codenute d'u fail",
'revdelete-hide-name'         => 'Scunne azione e obbiettive',
'revdelete-hide-comment'      => 'Scunne le commende de le cangiaminde',
'revdelete-hide-user'         => "Scunne 'u nome utende/IP de quidde ca ha fatte 'u cangiamende",
'revdelete-hide-restricted'   => "Live le date da l'amministratore cumme pe l'otre utinde",
'revdelete-radio-same'        => '(non scè cangianne)',
'revdelete-radio-set'         => 'Sine',
'revdelete-radio-unset'       => 'None',
'revdelete-suppress'          => "Live le date da l'amministrature cumme pe l'otre",
'revdelete-unsuppress'        => 'Live le restriziune sus a le revisiune repristinate',
'revdelete-log'               => 'Mutive:',
'revdelete-submit'            => "Applichesce a {{PLURAL:$1|'a revisione|le revisiune}} selezionate",
'revdelete-logentry'          => "ha state cangete 'a visibbilità d'a revisione de [[$1]]",
'logdelete-logentry'          => "ha state cangete 'a visibbilità de l'evende de [[$1]]",
'revdelete-success'           => "'''Visibbilità de le revisiune aggiornate correttamende.'''",
'revdelete-failure'           => "''' 'A visibbilità de le revisiune non ge pò essere aggiornate:'''
$1",
'logdelete-success'           => "'''Log visibility successfully set.'''
'''Visibbilità de l'archivije 'mbostate correttamende.'''",
'logdelete-failure'           => "'''L'archivije d'a visibbilità non ge pò essere 'mbostate:'''
$1",
'revdel-restore'              => "Cange 'a visibilità",
'revdel-restore-deleted'      => 'revisiune scangellate',
'revdel-restore-visible'      => 'Revisiune visibbele',
'pagehist'                    => "Storie d'a vôsce",
'deletedhist'                 => "Storie d'u scangellamende",
'revdelete-content'           => 'condenute',
'revdelete-summary'           => "cange 'u riepileghe",
'revdelete-uname'             => "nome de l'utende",
'revdelete-restricted'        => 'appliche le restriziune a le SysOps',
'revdelete-unrestricted'      => 'live le restriziune a le SysOps',
'revdelete-hid'               => 'scunne $1',
'revdelete-unhid'             => 'fa vedè $1',
'revdelete-log-message'       => '$1 pe $2 {{PLURAL:$2|revisione|revisione}}',
'logdelete-log-message'       => '$1 pe $2 {{PLURAL:$2|fatte|fatte}}',
'revdelete-hide-current'      => "Errore scunnènne le artichele datate $2, $1: queste ète 'a versiona corrende.
Non ge pò essere scunnute.",
'revdelete-show-no-access'    => 'Errore facenne vedè le artichele datate $2, $1: quiste artichele ha state signate "ristrette".
Tu non ge tìne l\'accesse.',
'revdelete-modify-no-access'  => 'Errore cangianne le artichele datate $2, $1: quiste artichele ha state signate "ristrette".
Tu non ge tìne l\'accesse.',
'revdelete-modify-missing'    => "Errore cangianne l'artichele cu l'ID $1: non ge se iacchie jndr'à 'u database!",
'revdelete-no-change'         => "'''Attenziò:''' l'artchele datate $2, $1 già tenève l'imbostaziune de visibbilità richieste.",
'revdelete-concurrent-change' => "Errore cangianne le artichele datate $2, $1: 'u state ca combare ha state cangiate da quacchedune otre mendre ca tu stè pruvave a cangiarle.
Pe piacere condrolle l'archivije.",
'revdelete-only-restricted'   => "Errore scunnènne l'artichele datate $2, $1: tu non ge puè sopprimere l'artichele da 'a viste de le amministrature senze ca scacchie pure une de le otre opziune de soppressione.",
'revdelete-reason-dropdown'   => "*Mutive comune pa scangellazzione
** Violazione d'u copyright
** 'Mbormaziune personale inappropriate",
'revdelete-otherreason'       => 'Otre/addizionale mutive:',
'revdelete-reasonotherlist'   => 'Otre mutive',
'revdelete-edit-reasonlist'   => "Mutive d'a scangellazione d'u cangiamende",
'revdelete-offender'          => "Autore d'a revisione:",

# Suppression log
'suppressionlog'     => 'Archivie de le soppressiune',
'suppressionlogtext' => "Sotte stè 'na liste de scangellaminde e blocche sus a le condenute scunnute da l'amministrature.
Vide 'a [[Special:IPBlockList|liste de le IP bloccate]] pa liste de le operazziune corrende de espulsione e blocche.",

# Revision move
'moverevlogentry'              => "spustate {{PLURAL:$3|'na revisione|$3 revisiune}} da $1 a $2",
'revisionmove'                 => 'Spuèste revisiune da "$1"',
'revmove-explain'              => "Le revisiune successive onna essere spustate da $1 a 'a pàgene de destinazione specificate. Ce 'a destinazione non g'esiste, avène ccrejate. A 'u condrarie, ste revisiune avenene scuagghiate jndr'à 'u cunde d'a pàgene.",
'revmove-legend'               => "'Mboste 'a pàgene de destinazione e 'u riepileghe",
'revmove-submit'               => "Spuèste le revisiune jndr'à pàgene scacchiate",
'revisionmoveselectedversions' => 'Spuèste le revisiune scacchiate',
'revmove-reasonfield'          => 'Mutive:',
'revmove-titlefield'           => 'Pàgene de destinazione:',
'revmove-badparam-title'       => 'Parametre sbagliate',
'revmove-badparam'             => "'A richiesta toje tène parametre non valide o insufficiende.
Tuèrne rrete a 'a pàgene precedende e pruève arrete.",
'revmove-norevisions-title'    => 'Revisione de destinazione invalide',
'revmove-norevisions'          => "Tu non g'è specificate une o cchiù revisiune de destinazione pe fà sta funzione o 'a revisione specificate non g'esiste.",
'revmove-nullmove-title'       => 'Titele sbagliate',
'revmove-nullmove'             => "'A pàgene de destinazione non ge pò essere 'a stesse d'à pàgene de origgene.
Tuèrne rrete a 'a pàgene precedende e scacchie 'nu nome diverse da \"[[\$1]]\".",
'revmove-success-existing'     => "{{PLURAL:$1|'Na revisione da [[$2]] ha|$1 revisiune da [[$2]] onne}} state spustate sus 'a pàgene esistende [[$3]].",
'revmove-success-created'      => "{{PLURAL:$1|'Na revisione da [[$2]] ha|$1 revisiune da [[$2]] onne}} state spustate sus 'a pàgene appene ccrejate [[$3]].",

# History merging
'mergehistory'                     => "Scuagghie 'a storie de le pàggene",
'mergehistory-header'              => "Sta pàgene te face squagghia le revisiune d'a storie de 'na pàgena sorgende jndr'à 'na pàgena nove.
Vide bbuene ce stu cangiamende pò mandenè 'a condinuità storeche d'a pàgene.",
'mergehistory-box'                 => 'Scuagghie le revisiune de doje pàggene:',
'mergehistory-from'                => 'Pàgene sorgende:',
'mergehistory-into'                => 'Pàgene de destinazione:',
'mergehistory-list'                => "cangiamende d'a storie scuagghiabbele",
'mergehistory-merge'               => "Le seguende revisiune de [[:$1]] ponne essere scuagghiate jndr'à [[:$2]].
Ause 'a coulonne cu le radio buttone pe scacchià de scuagghià le revisiune ccrejate apprime de n'nu certe mumende.
Vide Bbuene ca ausanne le collegaminde de navigazzione sta culonne avène azzerate.",
'mergehistory-go'                  => "Fà vedè le cangiaminde ca se ponne squagghià 'nzieme",
'mergehistory-submit'              => "Scuagghije 'nzieme le revisiune",
'mergehistory-empty'               => 'Nisciuna revisione pò essere scuagghiate.',
'mergehistory-success'             => "$3 {{PLURAL:$3|revisione|revisiune}} de [[:$1]] onne state scuagghiate jndr'à [[:$2]] correttamende.",
'mergehistory-fail'                => "Non ge se pò fa vedè 'a storie d'u scuagghiamende, pe piacere verifiche 'n'otra vota a pàgene e le parametre de l'orarie.",
'mergehistory-no-source'           => "'A pàgena sorgende $1 non g'esiste.",
'mergehistory-no-destination'      => "'A pàgene de destinazione $1 non g'esiste.",
'mergehistory-invalid-source'      => "'A pàgena sorgende addà tenè 'nu titele valide.",
'mergehistory-invalid-destination' => "'A pàgene de destinazione addà tenè 'nu titele valide.",
'mergehistory-autocomment'         => "Squagghiete [[:$1]] jndr'à [[:$2]]",
'mergehistory-comment'             => "Squagghiete [[:$1]] jndr'à [[:$2]]: $3",
'mergehistory-same-destination'    => 'Le pàggene sorgende e de destinazione non ge ponne essere le stesse',
'mergehistory-reason'              => 'Mutive:',

# Merge log
'mergelog'           => 'Archivije de le scuagghiaminde',
'pagemerge-logentry' => "scuagghiate [[$1]] jndr'à [[$2]] (revisiune fine a $3)",
'revertmerge'        => 'Squascie',
'mergelogpagetext'   => "Stte stè 'na liste de le cchiù recende scuagghiaminde de le cunde de le vosce jndr'à 'n'otre.",

# Diffs
'history-title'            => 'Liste de le versiune de "$1"',
'difference'               => "(Differenze 'mbrà versiune)",
'difference-multipage'     => "(Differenze 'mbrà le pàggene)",
'lineno'                   => 'Linea $1:',
'compareselectedversions'  => 'Combronde le versiune selezionete',
'showhideselectedversions' => 'Fà vedè/scunne le revisiune selezionate',
'editundo'                 => 'annulle',
'diff-multi'               => "({{PLURAL:$1|'na versione de mmienze|$1 cchiù versiune de mmienze}} de {{PLURAL:$2|'n'utende|$2 utinde}} non ge se vèdene)",
'diff-multi-manyusers'     => "({{PLURAL:$1|'Na revisione de 'mmienze|$1 revisiune de 'mmienze}} non g'è viste da cchiù de $2 {{PLURAL:$2|utende|utinde}})",

# Search results
'searchresults'                    => "Resultete d'a ricerche",
'searchresults-title'              => 'Resultete d\'a ricerche pe "$1"',
'searchresulttext'                 => "Pe cchiù 'mbormaziune sus 'a recerche de {{SITENAME}}, vide [[{{MediaWiki:Helppage}}|{{int:help}}]].",
'searchsubtitle'                   => 'Tu è cerchete pe \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|tutte le pàggene ca accumenzene cu "$1"]]{{int:pipe-separator}} [[Special:WhatLinksHere/$1|tutte le pàggene ca appondene a "$1"]])',
'searchsubtitleinvalid'            => "Tu è cerchete pe '''$1'''",
'toomanymatches'                   => "Troppe combronde sciute bbuene onne state returnete, pe piacere prueve cu 'n'otra inderrogazione",
'titlematches'                     => "'U titele d'a pàgene se iacchje",
'notitlematches'                   => "Nisciune titele de pàgene s'accocchie cu 'a recerche",
'textmatches'                      => "'U teste d'a pàgene combacie",
'notextmatches'                    => "Nisciune teste de pàgene s'accocchie cu 'a recerche",
'prevn'                            => 'rrede {{PLURAL:$1|$1}}',
'nextn'                            => 'nnande {{PLURAL:$1|$1}}',
'prevn-title'                      => 'Precedende $1 {{PLURAL:$1|resultete|resultete}}',
'nextn-title'                      => 'Successive $1 {{PLURAL:$1|resultete|resultete}}',
'shown-title'                      => 'Fà vedè le $1 {{PLURAL:$1|resultete|resultete}} pe pàgene',
'viewprevnext'                     => 'Vide ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Opzione de ricerche',
'searchmenu-exists'                => "'''Stè 'na pàgene nnumenete \"[[\$1]]\" sus 'a sta Uicchipèdie'''",
'searchmenu-new'                   => "'''[[:\$1|Ccreje]] 'a pàgene \"[[:\$1|\$1]]\" sus 'a sta Uicchipèdie!'''",
'searchhelp-url'                   => 'Help:Condenute',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Sfogghije le pàggene cu stu prefisse]]',
'searchprofile-articles'           => 'Vôsce',
'searchprofile-project'            => 'Pàggene de ajiute e de pruggette',
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Tutte',
'searchprofile-advanced'           => 'Avanzete',
'searchprofile-articles-tooltip'   => "Cirche jndr'à $1",
'searchprofile-project-tooltip'    => "Cirche jndr'à $1",
'searchprofile-images-tooltip'     => 'Cirche pe le fail',
'searchprofile-everything-tooltip' => "Cirche jndr'à tutte le vosce (combrese le vosce de le 'ngazzaminde)",
'searchprofile-advanced-tooltip'   => "Cirche jndr'à le namespace personalizzete",
'search-result-size'               => '$1 ({{PLURAL:$2|1 parole|$2 parole}})',
'search-result-category-size'      => '{{PLURAL:$1|1 membre|$1 membre}} ({{PLURAL:$2|1 sottecategorije|$2 sottecategorije}}, {{PLURAL:$3|1 file|$3 file}})',
'search-result-score'              => "'Mbortanze: $1%",
'search-redirect'                  => '(Redirette $1)',
'search-section'                   => '(sezione $1)',
'search-suggest'                   => 'Ce signifeche: $1',
'search-interwiki-caption'         => 'Pruggette sorelle',
'search-interwiki-default'         => '$1 resultete:',
'search-interwiki-more'            => '(de cchiù)',
'search-mwsuggest-enabled'         => 'cu le consiglie',
'search-mwsuggest-disabled'        => 'senza consiglie',
'search-relatedarticle'            => 'Colleghete',
'mwsuggest-disable'                => 'Disabbilete le conziglie in AJAX',
'searcheverything-enable'          => "Cirche jndr'à tutte le namespace",
'searchrelated'                    => 'colleghete',
'searchall'                        => 'tutte',
'showingresults'                   => "Stoche a fazze vedè aqquà sotte {{PLURAL:$1|'''1''' resultete|'''$1''' resultete}} ca accumenzene cu #'''$2'''.",
'showingresultsnum'                => "Stoche a fazze vedè {{PLURAL:$3|'''1''' resultete|'''$3''' resultete}} ca accumenzene cu #'''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Resultate '''$1''' de '''$3'''|Resultate '''$1 - $2''' de '''$3'''}} pe '''$4'''",
'nonefound'                        => "'''Vide bbuene''': Sulamende sus a certe namespace avène fatte 'a ricerche pe default.
Prueve mettènne nnande a l'inderrogaziona toje 'u prefisse '''all:''' pe cercà jndr'à tutte le namespace (ingludenne le pàggene de le 'ngazzaminde, le template, etc), o ause 'u namespace addò vuliva fa 'a ricerche cumme prefisse.",
'search-nonefound'                 => "Non ge stonne resultete ca soddisfecene l'inderrogazione.",
'powersearch'                      => 'Ricerche avanzete',
'powersearch-legend'               => 'Ricerche avanzete',
'powersearch-ns'                   => "Cirche jndr'à le namespace:",
'powersearch-redir'                => 'Liste de le ridirezionaminde',
'powersearch-field'                => 'Cirche pe',
'powersearch-togglelabel'          => 'Verifiche:',
'powersearch-toggleall'            => 'Tutte',
'powersearch-togglenone'           => 'Ninde',
'search-external'                  => 'Ricerche esterne',
'searchdisabled'                   => "'A ricerche sus a {{SITENAME}} ha state disabbilitete.
Tu puè cercà ausanne Google.
Però fa attenzione purcè l'indice lore sus a {{SITENAME}} ponne condenè pàggene ca non ge sonde aggiornate.",

# Quickbar
'qbsettings'               => 'Barra veloce',
'qbsettings-none'          => 'Ninde',
'qbsettings-fixedleft'     => 'Fissete a sinistre (Fixed left)',
'qbsettings-fixedright'    => 'Fissete a destre (Fixed right)',
'qbsettings-floatingleft'  => 'Volanne a sinistre (Floating left)',
'qbsettings-floatingright' => 'Volanne a destre (Floating right)',

# Preferences page
'preferences'                   => 'Me piece accussì',
'mypreferences'                 => 'Me piece accussì',
'prefs-edits'                   => 'Numere de cangiaminde:',
'prefsnologin'                  => 'Non ge sinde colleghete',
'prefsnologintext'              => 'Tu a essere <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} colleghete]</span> pe \'mbostà le preferenze de l\'utinde.',
'changepassword'                => "Cange 'a password",
'prefs-skin'                    => 'Skin',
'skin-preview'                  => 'Andeprime',
'prefs-math'                    => 'Math',
'datedefault'                   => 'Nisciuna preferenze',
'prefs-datetime'                => 'Date e ore',
'prefs-personal'                => "Profile de l'utende",
'prefs-rc'                      => 'Cangiaminde recende',
'prefs-watchlist'               => 'Pàggene condrollete',
'prefs-watchlist-days'          => "Giurne da fà vedè jndr'à liste de le pàggene condrollete:",
'prefs-watchlist-days-max'      => '(massime 7 sciurne)',
'prefs-watchlist-edits'         => "Numere massime de cangiaminde ca se ponne fa vedè jndr'à liste de le pàggene condrollete spannute:",
'prefs-watchlist-edits-max'     => '(numere massime: 1000)',
'prefs-watchlist-token'         => 'Token de le pàggene condrollate:',
'prefs-misc'                    => 'Misc',
'prefs-resetpass'               => "Cange a 'password",
'prefs-email'                   => "Opziune de l'e-mail",
'prefs-rendering'               => 'Aspette',
'saveprefs'                     => 'Reggistre',
'resetprefs'                    => "Pulizze le cangiaminde ca non g'è reggistrete",
'restoreprefs'                  => "Repristene tutte le 'mbostaziune origginale",
'prefs-editing'                 => 'Cangiaminde...',
'prefs-edit-boxsize'            => "Dimenzione d'a pàgene de cangiamende.",
'rows'                          => 'Righe:',
'columns'                       => 'Culonne:',
'searchresultshead'             => 'Cirche',
'resultsperpage'                => 'Trasute pe pàgene:',
'contextlines'                  => 'Linne pe collegamende:',
'contextchars'                  => 'Condeste pe linee:',
'stub-threshold'                => 'Soglie pe <a href="#" class="stub">collegamende stub</a> de formattazione (byte):',
'stub-threshold-disabled'       => 'Disabbilitate',
'recentchangesdays'             => "Sciurne da fà vedè jndr'à le cangiaminde recende:",
'recentchangesdays-max'         => '(massime $1 {{PLURAL:$1|sciurne|sciurne}})',
'recentchangescount'            => 'Numere de cangiaminde da fà vedè pe default:',
'prefs-help-recentchangescount' => "Quiste 'nglude le urteme cangiaminde, le storie de le pàggene e le archivije.",
'prefs-help-watchlist-token'    => "Anghienne stu cambe cu le chiave segrete avène generate 'nu RSS feed pa liste de le pàggene condrollate.<br />
Ogneune ca canosce 'a chiave de stu cambe se pò leggere 'a liste de le pàggene condrollate tue, accussì scacchie 'nu valore secure.<br />
Aqquà ste 'nu valore generate a uecchije ca tu puè ausà: $1",
'savedprefs'                    => 'Le preferenze tue onne state aggiornete.',
'timezonelegend'                => "Orarie d'a zone:",
'localtime'                     => 'Orarie lochele:',
'timezoneuseserverdefault'      => "Ause 'u valore de default d'u server",
'timezoneuseoffset'             => "Otre (specifiche 'a distanze)",
'timezoneoffset'                => 'Distanze¹:',
'servertime'                    => "Orarie d'u server:",
'guesstimezone'                 => "Jnghie da 'u browser",
'timezoneregion-africa'         => 'Africa',
'timezoneregion-america'        => 'America',
'timezoneregion-antarctica'     => 'Antartide',
'timezoneregion-arctic'         => 'Artide',
'timezoneregion-asia'           => 'Asia',
'timezoneregion-atlantic'       => 'Oceane Atlandiche',
'timezoneregion-australia'      => 'Australia',
'timezoneregion-europe'         => 'Europe',
'timezoneregion-indian'         => 'Oceano Indiane',
'timezoneregion-pacific'        => 'Oceano Pacifiche',
'allowemail'                    => "Abbilite l'e-mail da l'otre utinde",
'prefs-searchoptions'           => 'Opzione de ricerche',
'prefs-namespaces'              => 'Namespaces',
'defaultns'                     => "Cirche jndr'à chiste namespace:",
'default'                       => 'defolt',
'prefs-files'                   => 'Fails',
'prefs-custom-css'              => 'CSS Personalizzete',
'prefs-custom-js'               => 'JS Personalizzete',
'prefs-common-css-js'           => 'CSS/JS condivise pe tutte le sfonde:',
'prefs-reset-intro'             => "Tu puè ausà sta pàgene pe azzerà le preferenze tue a quidde de default d'u site.
Quiste non ge pò essere annullate.",
'prefs-emailconfirm-label'      => "Conferme de l'e-mail:",
'prefs-textboxsize'             => "Dimenzione d'a finestre de le cangiaminde",
'youremail'                     => 'Poste:',
'username'                      => "Nome de l'utende:",
'uid'                           => 'ID Utende:',
'prefs-memberingroups'          => "Membre {{PLURAL:$1|d'u gruppe|de le gruppe}}:",
'prefs-registration'            => 'Orarie de reggistrazzione:',
'yourrealname'                  => 'Nome vere:',
'yourlanguage'                  => 'Lènga:',
'yournick'                      => 'Firma toje:',
'prefs-help-signature'          => "Le commende sus a le pàggene de le 'ngazzaminde avessere a essere sgnate cu \"<nowiki>~~~~</nowiki>\" ca pò avène convertite cu 'a firma toje e l'orarie.",
'badsig'                        => "'A firme grezze jè sbagliete.
Condrolle le tag HTML.",
'badsiglength'                  => "'A firme jè troppe longhe.
Addà essere assaje assaje sotte a $1 {{PLURAL:$1|carattere|carattere}}.",
'yourgender'                    => 'Sesso:',
'gender-unknown'                => 'No specificate',
'gender-male'                   => 'Maschele',
'gender-female'                 => 'Femmene',
'prefs-help-gender'             => "Opzionale: ausete pe l'indirizzamende corrette d'u sesse jndr'à 'u software. Sta 'mbormazione jè pubbliche.",
'email'                         => 'Poste',
'prefs-help-realname'           => "'U nome vere (quidde d'u munne reale) jè facoltative.
Ce tu 'u mitte, a fatje ca è fatte t'avène ricanusciute.",
'prefs-help-email'              => "L'indirizze e-mail jè facoltative ma te dè 'a possibbilità de mannarte 'na password nova nove ce tu te scuèrde quedde ca tenive.
Tu puè pure scacchià de lassà otre condatte ausanne l'utende tue o le pàggene de le 'ngazzaminde senza ca abbesogne de fa canoscere l'idendità toje.",
'prefs-help-email-required'     => "L'indirizze e-mail jè obbligatorie.",
'prefs-info'                    => "'Mbormaziune nderra-nderre",
'prefs-i18n'                    => 'Indernazzionalizzazzione',
'prefs-signature'               => 'Firme',
'prefs-dateformat'              => "Formate d'a data",
'prefs-timeoffset'              => "Differenze d'orarie",
'prefs-advancedediting'         => 'Opzione avanzate',
'prefs-advancedrc'              => 'Opzione avanzate',
'prefs-advancedrendering'       => 'Opzione avanzate',
'prefs-advancedsearchoptions'   => 'Opzione avanzate',
'prefs-advancedwatchlist'       => 'Opzione avanzate',
'prefs-displayrc'               => "Fà vedè l'opzione",
'prefs-displaysearchoptions'    => "Fà vedè l'opzione",
'prefs-displaywatchlist'        => "Fà vedè l'opzione",
'prefs-diffs'                   => 'Diff',

# User rights
'userrights'                     => 'Gestione de le deritte utende',
'userrights-lookup-user'         => "Gestisce le gruppe de l'utinde",
'userrights-user-editname'       => "Mitte 'nu nome utende:",
'editusergroup'                  => 'Cange le gruppe utinde',
'editinguser'                    => "Stè cange le deritte de l'utende de l'utende '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}} [[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'       => "Cange le gruppe d'utinde",
'saveusergroups'                 => "Reggistre le gruppe d'utinde",
'userrights-groupsmember'        => 'Membre de:',
'userrights-groupsmember-auto'   => 'Membre imblicite de:',
'userrights-groups-help'         => "Tu puè alterà le gruppe addò de st'utende jè iscritte:
* 'Na spunde de verifiche significhe ca l'utende stè jndr'à stu gruppe.
* 'A spunda de verifica luate significhe ca l'utende non ge stè jndr'à stu gruppe.
* 'Nu * significhe ca tu non ge puè luà 'u gruppe 'na vote ca tu l'è aggiunde, o a smerse.",
'userrights-reason'              => 'Mutive:',
'userrights-no-interwiki'        => "Tu non ge tìne le permesse pe cangià le deritte utende sus a l'otre uicchi.",
'userrights-nodatabase'          => "'U Database $1 non g'esiste o non g'è lochele.",
'userrights-nologin'             => "Tu à essere [[Special:UserLogin|colleghete]] cu 'nu cunde utende d'amministratore pe assignà le deritte utende.",
'userrights-notallowed'          => "'U cunde utende tue non ge tène le permesse pe assignà de deritte a l'utinde.",
'userrights-changeable-col'      => 'Gruppe ca tu puè cangià',
'userrights-unchangeable-col'    => 'Gruppe ca tu non ge puè cangià',
'userrights-irreversible-marker' => '$1*',

# Groups
'group'               => 'Gruppe:',
'group-user'          => 'Utinde',
'group-autoconfirmed' => 'Utinde auto confermatarije',
'group-bot'           => 'Bot',
'group-sysop'         => 'Sysop',
'group-bureaucrat'    => 'Burocrate',
'group-suppress'      => 'Supervisionature',
'group-all'           => '(tutte)',

'group-user-member'          => 'Utende',
'group-autoconfirmed-member' => 'Utende Autoconfermete',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Sysop',
'group-bureaucrat-member'    => 'Burocrate',
'group-suppress-member'      => 'Supervisionatore',

'grouppage-user'          => '{{ns:project}}:Utinde',
'grouppage-autoconfirmed' => '{{ns:project}}:Utinde Autoconfermete',
'grouppage-bot'           => '{{ns:project}}:Bot',
'grouppage-sysop'         => '{{ns:project}}:Amministratore',
'grouppage-bureaucrat'    => '{{ns:project}}:Burocrate',
'grouppage-suppress'      => '{{ns:project}}:Supervisionatore',

# Rights
'right-read'                  => 'Ligge le pàggene',
'right-edit'                  => 'Cange le pàggene',
'right-createpage'            => "Ccreje le pàggene (ca non ge tènene le pàggene de le 'ngazzaminde)",
'right-createtalk'            => "Ccreje le pàggene de le 'ngazzaminde",
'right-createaccount'         => 'Ccreje nueve cunde utinde',
'right-minoredit'             => 'Signe le cangiaminde cumme stuedéche',
'right-move'                  => 'Spuéste le pàggene',
'right-move-subpages'         => 'Spueste le pàggene cu tutte le pàggene ca stonne da sotte',
'right-move-rootuserpages'    => 'Spuèste le pàggene utinde radice',
'right-movefile'              => 'Spueste le file',
'right-suppressredirect'      => "No scè ccrejanne 'nu ridirezionamende da 'u nome vecchije quanne spueste 'na pàgene",
'right-upload'                => 'Careche le fail',
'right-reupload'              => "Sovrascrive 'nu file esistende",
'right-reupload-own'          => "Sovrascrive 'nu file esistende carichete da quacchedune",
'right-reupload-shared'       => "Sovrascrive le file sus a 'u repository de le media condivise locale",
'right-upload_by_url'         => "Careche 'nu file da l'indirizze URL",
'right-purge'                 => "Sdevache 'a cache d'u site pe 'na pàgene senza conferme",
'right-autoconfirmed'         => 'Cange le pàggene prutette a metà',
'right-bot'                   => 'Adda essere trattate cumme a nu processe automateche',
'right-nominornewtalk'        => "No scè ausanne le cangiaminde stuèdeche jndr'à le pàggene de le 'ngazzaminde quanne lasse messagge nuève",
'right-apihighlimits'         => "Ause 'nu limite cchiù ierte jndr'à l'inderrogaziune de le API",
'right-writeapi'              => 'Ause de le API scritte',
'right-delete'                => 'Scangille le pàggene',
'right-bigdelete'             => "Scangille le pàggene cu 'na storia longa longa",
'right-deleterevision'        => 'Scangille o repristine le revisiune specifiche de le pàggene',
'right-deletedhistory'        => "Vide le versiune, d'u cunde, scangellate, senza 'u teste lore associate",
'right-deletedtext'           => "Vide 'u teste scangellate e le cangiaminde 'mbrà le versiune scangellate",
'right-browsearchive'         => 'Cirche le pàggene scangellate',
'right-undelete'              => "Repristine 'na pàgene",
'right-suppressrevision'      => "Revide e repristine le revisiune scunnete da l'amministrature",
'right-suppressionlog'        => "Vide l'archivije privete",
'right-block'                 => 'Blocche otre utinde a fà le cangiaminde',
'right-blockemail'            => "Blocche l'utente a fà mannà le email",
'right-hideuser'              => "Bluecche 'nu cunde utende, scunnènnele da 'u pubbliche",
'right-ipblock-exempt'        => "Zumbe le blocche de l'IP, auto blocche e le blocche a indervalle",
'right-proxyunbannable'       => "Zumbe automaticamende le condrolle d'u proxy",
'right-unblockself'           => 'Sbluecche lore',
'right-protect'               => 'Cange le levèelle de protezione e cange le pàggene prutette',
'right-editprotected'         => 'Cange le pàggene prutette (senza protezzione a cascata)',
'right-editinterface'         => "Cange l'inderfacce utende",
'right-editusercssjs'         => "Cange 'u CSS e 'u JS de l'otre utinde",
'right-editusercss'           => "Cange 'u CSS de l'otre utinde",
'right-edituserjs'            => "Cange 'u JS de l'otre utinde",
'right-rollback'              => "Annulle velocemende le cangiaminde de l'urteme utende ca ha cangiate 'na pàgena particolare",
'right-markbotedits'          => 'Marche le cangiaminde annullate cumme cangiaminde de bot',
'right-noratelimit'           => "Non g'à state tuccate da le limite de le pundegge",
'right-import'                => "Pàggene 'mbortate da otre Uicchipèdie",
'right-importupload'          => "'Mborte le pàggene da 'nu file carechete",
'right-patrol'                => "Signe l'otre cangiaminde cumme condrollate",
'right-autopatrol'            => 'Certe cangiaminde tue onne state marcate automaticamende cumme condrollate',
'right-patrolmarks'           => 'Vide le cangiaminde recende marcate cumme a condrollate',
'right-unwatchedpages'        => "Vide 'na liste de pàggene non condrollete",
'right-trackback'             => "Conferme 'nu trackback",
'right-mergehistory'          => "Scuagghie 'a storie de le pàggene",
'right-userrights'            => 'Cange tutte le deritte utende',
'right-userrights-interwiki'  => "Cange le deritte utende de le utinde sus a l'otre Uicchi",
'right-siteadmin'             => "Blocche e sblocche 'u database",
'right-reset-passwords'       => "Azzere l'otre password de l'utinde",
'right-override-export-depth' => "L'esportazione de pàggene inglude pàggene collegate 'mbonde a 'na profonnetà de 5",
'right-sendemail'             => "Manne 'a mail a otre utinde",
'right-revisionmove'          => 'Spuèste le revisiune',
'right-disableaccount'        => 'Disabbilete le cunde utinde',

# User rights log
'rightslog'      => "Archivie de le diritte de l'utende",
'rightslogtext'  => "Quiste jè 'n'archivije pe le cangiaminde de le deritte de l'utinde.",
'rightslogentry' => "membre d'u gruppe cangete pe $1 da $2 a $3",
'rightsnone'     => '(ninde)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'ligge sta pàgene',
'action-edit'                 => 'cange sta pàgene',
'action-createpage'           => 'ccreje le pàggene',
'action-createtalk'           => "ccreje le pàggene de le 'ngazzaminde",
'action-createaccount'        => 'ccreje stu cunde utende',
'action-minoredit'            => 'signe stu cangiamende cumme stuédeche',
'action-move'                 => 'spuéste sta pàgene',
'action-move-subpages'        => 'spuéste sta pàgene e tutte chidde ca stonne sotte a jedde',
'action-move-rootuserpages'   => 'spuèste le pàggene utinde prengepàle',
'action-movefile'             => 'spueste stu file',
'action-upload'               => 'careche stu fail',
'action-reupload'             => 'sovrascrive stu file esistende',
'action-reupload-shared'      => "sovrascrive stu file jndr'à 'u repository condivise",
'action-upload_by_url'        => "careche stu file da st'indirizze web",
'action-writeapi'             => "ause 'a scritta API",
'action-delete'               => 'scangille sta pàgene',
'action-deleterevision'       => 'scangille sta versione',
'action-deletedhistory'       => "vide 'a storie de sta pàgene scangellete",
'action-browsearchive'        => 'cirche le pàggene scangellete',
'action-undelete'             => 'repristine sta pàgene',
'action-suppressrevision'     => 'revide e ripristine sta revisiona scunnute',
'action-suppressionlog'       => "vide st'archivije privete",
'action-block'                => "blocche st'utende pe le cangiaminde",
'action-protect'              => "cange 'u levèlle de protezzione pe sta pàgene",
'action-import'               => "'mborte sta pàgene da n'otra Uicchi",
'action-importupload'         => "'mborte sta pàgene da 'nu carecamende de 'nu file",
'action-patrol'               => "signe l'otre cangiaminde cumme condrollate",
'action-autopatrol'           => 'signà le cangiaminde tue cumme condrollate',
'action-unwatchedpages'       => "vide 'a liste de le pàggene ca non ge sonde condrollete",
'action-trackback'            => "conferme 'nu trackback",
'action-mergehistory'         => "squagghie 'a sotrie de sta pàgene",
'action-userrights'           => "cange tutte le deritte de l'utende",
'action-userrights-interwiki' => "cange le deritte de l'utende de l'utinde de le otre Uicchi",
'action-siteadmin'            => "blocche o sblocche 'u database",
'action-revisionmove'         => 'spuèste le revisiune',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|cangiaminde|cangiaminde}}',
'recentchanges'                     => 'Cangiaminde recende',
'recentchanges-legend'              => 'Opzione pe le cangiaminde recende',
'recentchangestext'                 => 'Tracce le cchiù recednde cangiaminde de Uicchi sus a sta pàgene.',
'recentchanges-feed-description'    => "Tracce le urteme cangiaminde sus 'a sta Uicchipedie jndr'à quiste feed.",
'recentchanges-label-newpage'       => "Stu cangiamende ha ccrejate 'na pàgena nove",
'recentchanges-label-minor'         => "Quiste ète 'nu cangiamende stuèdeche",
'recentchanges-label-bot'           => "Stu cangiamende ha state fatte da 'nu bot",
'recentchanges-label-unpatrolled'   => "Stu cangiamende non g'à state angore condrollate",
'rcnote'                            => "Sotte {{PLURAL:$1|jè '''1''' cangiamende|sonde le urteme '''$1''' cangiaminde}} jndr'à l'urteme{{PLURAL:$2|sciurne|'''$2''' sciurne}}, jndr'à $5, $4.",
'rcnotefrom'                        => "Sotte stonne le cangiaminde da '''$2''' (fine a '''$1''' mustrete).",
'rclistfrom'                        => 'Fà vedè le urteme cangiaminde partenne da $1',
'rcshowhideminor'                   => '$1 cangiaminde stuèdeche',
'rcshowhidebots'                    => '$1 bot',
'rcshowhideliu'                     => '$1 utende reggistrete',
'rcshowhideanons'                   => '$1 utende scanusciute',
'rcshowhidepatr'                    => '$1 cangiaminde condrollete',
'rcshowhidemine'                    => '$1 cangiaminde mie',
'rclinks'                           => "Vide l'urteme $1 cangiaminde jndr'à l'urteme $2 sciurne<br />$3",
'diff'                              => 'diff',
'hist'                              => 'cunde',
'hide'                              => 'Scunne',
'show'                              => 'Fà vedè',
'minoreditletter'                   => 'm',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|utende|utinde}} ca condrollene]',
'rc_categories'                     => 'Limite de le categorije (separate cu "|")',
'rc_categories_any'                 => 'Tutte',
'rc-change-size'                    => '$1',
'newsectionsummary'                 => '/* $1 */ seziona nove',
'rc-enhanced-expand'                => 'Fà vedè le dettaglie (ha ausà JavaScript)',
'rc-enhanced-hide'                  => 'Scunne le dettaglie',

# Recent changes linked
'recentchangeslinked'          => 'Cangiaminde culleghete',
'recentchangeslinked-feed'     => 'Cangiaminde culleghete',
'recentchangeslinked-toolbox'  => 'Cangiaminde culleghete',
'recentchangeslinked-title'    => 'Cangiaminde culleghete a "$1"',
'recentchangeslinked-backlink' => '← $1',
'recentchangeslinked-noresult' => "Non g'onne state fatte cangiaminde sus 'a sta pàgene jndr'à 'u periede selezionete.",
'recentchangeslinked-summary'  => "Queste jè 'a liste de le cangiaminde fatte de recende a le pàggene culleghete da ' na pàgene specifiche (o a le membre de 'na specifiche categorije).
Pàggene sus 'a [[Special:Watchlist|le Pàggene condrollete]] sonde in '''grascette'''.",
'recentchangeslinked-page'     => "Nome d'a vôsce:",
'recentchangeslinked-to'       => "Fa vedè le cangiaminde de le pàggene colleghete a 'na certa pàgene",

# Upload
'upload'                      => "Careche 'u file",
'uploadbtn'                   => "Careche 'nu fail",
'reuploaddesc'                => "Scangille 'u carecamende e tuerne a 'a schermete de le carecaminde",
'upload-tryagain'             => "Conferme 'u cangiamende d'a descrizione d'u file",
'uploadnologin'               => 'non ge sinde colleghete',
'uploadnologintext'           => 'Tu a essere [[Special:UserLogin|colleghete]] pe carecà le file.',
'upload_directory_missing'    => "'A cartelle de le carecaminde ($1) s'à perdute o pò essere ca non g'à state ccreate da 'u webserver.",
'upload_directory_read_only'  => "'A cartelle d'u carecamende ($1) non ge se pò fà scrivere da 'u webserver.",
'uploaderror'                 => 'Errore de carecamende',
'upload-recreate-warning'     => "'''Attenziò: 'Nu file cu stu nome ha state scangellate o spustate.'''

L'archivije de de scangellaminde e de le spustaminde pe sta pàgene le puè acchià aqquà:",
'uploadtext'                  => "Ause 'u module aqquà sotte pe carecà le file.
Pe vedè o cercà le file carecate precedendemende veje a 'a [[Special:FileList|liste de le file carecate]], le carecaminde sonde pure reggistrate jndr'à l'[[Special:Log/upload|archivije de le carecaminde]], le scangellazziune jndr'à l'[[Special:Log/delete|archivije de le scangellaminde]].

Pe ingludere 'nu file jndr'à 'na pàgene, ause 'u collegamende jndr'à une de le forme seguende:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>''' pe ausà 'a versiona comblete d'u file
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|alt text]]</nowiki></tt>''' pe ausà 'nu file cu 'a dimenziona massime de 200 pixel jndr'à 'na scatele ca se iacchie sus a 'u margine sinistre cu 'u teste 'alt text' cumme didascalìe.
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>''' pe appundà direttamende a 'u file senze ca face vedè 'u file",
'upload-permitted'            => 'Tipe de file permesse: $1.',
'upload-preferred'            => 'Tipe de file preferite: $1.',
'upload-prohibited'           => 'Tipe de file proibbite: $1.',
'uploadlog'                   => 'Archivie de le carecaminde',
'uploadlogpage'               => 'Archivije de le carecaminde',
'uploadlogpagetext'           => "Sotte ste 'na liste de le cchiù recende file carechete.
Vide 'a [[Special:NewFiles|gallerie de le file nuève]] pe vedè l'otre andeprime.",
'filename'                    => "Nome d'u fail",
'filedesc'                    => 'Riepileghe',
'fileuploadsummary'           => 'Riepileghe:',
'filereuploadsummary'         => "Cangiaminde d'u file:",
'filestatus'                  => "State d'u Copyright:",
'filesource'                  => 'Sorgende:',
'uploadedfiles'               => 'File carechete',
'ignorewarning'               => "Futtetene de l'avvertimende e reggistre 'u file",
'ignorewarnings'              => "No scè penzanne a tutte l'avvise",
'minlength1'                  => "'U nome d'u file addà tenè almene 'na lettere.",
'illegalfilename'             => "'U nome d'u file \"\$1\" tène carattere ca non ge sonde conzendite jndr'à le titele de le pàggene.
Pe piacere vide ce renomene 'u file e pruève a carecarle 'n'otra vote.",
'badfilename'                 => '\'U nome d\'u file ha state cangete jndr\'à "$1".',
'filetype-mime-mismatch'      => "L'estenzione d'u file non ge se iacchie cu 'u tipe MIME.",
'filetype-badmime'            => 'Le file d\'u tipe MIME "$1" non ge se ponne carecà.',
'filetype-bad-ie-mime'        => 'Non ge pozze carecò stu file purcè Internet Explorer \'u vole cumme "$1", e allore jidde se penze ca jè \'nu tipe de file potenzialmende pericolose.',
'filetype-unwanted-type'      => "'''\".\$1\"''' ète 'nu tipe de file ca non ge vulime.
{{PLURAL:\$3|'U tipe de file preferite ète|Le tipe de file preferite sonde}} \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' ète 'nu tipe de file ca non g'è permesse.
{{PLURAL:\$3|'U tipe de file permesse ète|Le tipe de file permesse sonde}} \$2.",
'filetype-missing'            => '\'U file non ge tène l\'estenzione (cumme a ".jpg").',
'empty-file'                  => "'U file ca tu è mannate ere vacande.",
'file-too-large'              => "'U file ca tu è mannate ere troppe luènghe.",
'filename-tooshort'           => "'U nome d'u file jè troppe curte.",
'filetype-banned'             => 'Stu tipe de file jè vietate.',
'verification-error'          => "Stu file non g'à passate 'a verifeche de le file.",
'hookaborted'                 => "'U cangiamende ca tu stè pruève a ffà ha state inderrotte da 'nu gange estese.",
'illegal-filename'            => "'U nome d'u file non g'è permesse.",
'overwrite'                   => "'A sovrascritture de 'nu file ca esiste non ge se pò fà.",
'unknown-error'               => "'N'errore scanusciute s'a verificate.",
'tmp-create-error'            => 'Non ge pozze ccrejà file temboranèe.',
'tmp-write-error'             => "Errore scrivenne 'u file temboranèe.",
'large-file'                  => "Normalmende 'u file non g'adda essere cchiù granne de $1;
Stu file jè $2.",
'largefileserver'             => "Stu file jè troppe gruesse pe quidde ca 'a configurazione d'u server permette.",
'emptyfile'                   => "'U file ca tu è carecate pare ca è vacande.
Pò essere ca è scritte male 'u nome d'u file e n'à carecate 'n'otre.
Pe piacere condrolle ce tu avveramende vuè cu careche stu file.",
'fileexists'                  => "'Nu file cu stu nome esiste già, pe piacere verifiche '''<tt>[[:$1]]</tt>''' ce tu non ge sì secure no 'u sce cangianne.
[[$1|thumb]]",
'filepageexists'              => "'A pàgene de descrizione pe stu file ha state già ccreiate 'u '''<tt>[[:$1]]</tt>''', ma nisciune file cu stu nome osce a die esiste.
'U riepileghe ca tu è mise non ge iesse sus 'a pàgene de descrizione.
Pe fà assè 'u riepileghe tu tìne abbesogne de cangiarle a mane.
[[$1|thumb]]",
'fileexists-extension'        => "'Nu file cu 'nu nome simile esiste già: [[$2|thumb]]
* Nome d'u file ca vuè cu careche: '''<tt>[[:$1]]</tt>'''
* Nome d'u file ca già esiste: '''<tt>[[:$2]]</tt>'''
Pe piacere vide ce scacchie 'nu nome differende.",
'fileexists-thumbnail-yes'    => "'U file pare ca jè 'n'immaggine de dimenzione ridotte ''(miniature)''. [[$1|thumb]]
Pe piacere condrolle 'u file '''<tt>[[:$1]]</tt>'''.
Ce 'u file condrollete jè d'a stesse dimenzione de quedda originale allore non ge stè abbesogne de carecà 'na miniatura de cchiù.",
'file-thumbnail-no'           => "'U nome d'u file accumenze pe '''<tt>$1</tt>'''.
Pare ca jè 'n'immaggine piccenne ''(miniature)''.
Ce tu tìne sulamende st'immaggine da carecà apposte, ce nò vide ce cange 'u file.",
'fileexists-forbidden'        => "'U file cu stu nome già esiste e non ge pò essere sovrascritte.<br />
Ce tu vuè angore carecà 'u file tue, pe piacere tuèrne rrete e ause 'nu nome nuève. [[File:$1|thumb|center|$1]]",
'fileexists-shared-forbidden' => "'Nu file cu stu nome già esiste jndr'à 'u repository condivise de le file.<br />
ce tu vuè angore carecà stu file, pe piacere tuèrne rrete e ause 'nu nome nuève. [[File:$1|thumb|center|$1]]",
'file-exists-duplicate'       => "Stu file jè 'na copie {{PLURAL:$1|d'u seguende file|de le seguende file}}:",
'file-deleted-duplicate'      => "'Nu file uguale a stu file ([[$1]]) ha state scangellate precedendemende.<br />
Avissa verificà 'a storie d'a scangellazzione d'u file apprime de condinuà a carecarle.",
'uploadwarning'               => 'Avvise de carecamende',
'uploadwarning-text'          => "Pe piacere cange 'a descrizione d'u file sotte e pruève 'notra vote.",
'savefile'                    => "Reggistre 'u file",
'uploadedimage'               => 'carechete "[[$1]]"',
'overwroteimage'              => 'ha state carechete \'na versiona nove de "[[$1]]"',
'uploaddisabled'              => 'Carecaminde disabbilitete',
'copyuploaddisabled'          => "Carecamende da l'URL disabbilitate.",
'uploadfromurl-queued'        => "'U carecamende tune ha state mise in code.",
'uploaddisabledtext'          => 'Le carecaminde de le file sonde disabbilitete.',
'php-uploaddisabledtext'      => "Le carecaminde de file sonde disabilitate in PHP.<br />
Pe piacere verifiche le 'mbostaziune d'u ''file_uploads''.",
'uploadscripted'              => "Stu file condene HTML o codece de script ca ponne essere inderpretete jndr'à 'nu mode sbagliete da le browser.",
'uploadvirus'                 => "Alanga toje, 'u file condiene 'nu virus! Dettaglie: $1",
'upload-source'               => 'File sorgende',
'sourcefilename'              => "Nome d'u fail d'origgine:",
'sourceurl'                   => 'URL sorgende:',
'destfilename'                => "Nome d'u file de destinazione:",
'upload-maxfilesize'          => "Dimenzione massima d'u file: $1",
'upload-description'          => "Descrizione d'u file",
'upload-options'              => 'Opzione pu carecamende',
'watchthisupload'             => 'Condrolle stu file',
'filewasdeleted'              => "'Nu file ca se chiamave cumme a quidde tue ha state apprime carecate e pò ha state scangellete.
Tu avissa condrollà 'u $1 apprime ca condinue cu 'u carecamende.",
'upload-wasdeleted'           => "'''FA ATTENZIO': Tu ste careche 'nu file ca apprime ha state scangellete.'''

Tu avissa considerà ce è  proprie utile carecà stu file.
L'archivije de le scangellaminde pe stu file 'u iacchije aqquà pe convenienze:",
'filename-bad-prefix'         => "'U nome d'u file ca tu ste careche accumenze pe '''\"\$1\"''', ca normalmende jè 'u nome ca assegne a machena fotografeche e non 'nu nome descrittive d'u file ca vuè ccu careche.
Pe piacere scacchie 'n'otre nome ca jè cchiù descrittive.",
'upload-success-subj'         => 'Carecamende sciute apposte',
'upload-success-msg'          => "'U carecamende tue da [$2] ha riuscite. Mò jè disponibbele aqquà: [[:{{ns:file}}:$1]]",
'upload-failure-subj'         => 'Careche le probbleme',
'upload-failure-msg'          => "Stave 'nu probbleme cu 'u carecamende tune da [$2]:

$1",
'upload-warning-subj'         => 'Avvise de carecamende',
'upload-warning-msg'          => "Stave 'nu probbleme cu 'u carecamende tune da [$2]. Tu puè turnà rrete a 'u [[Special:Upload/stash/$1|module de carecamende]] pe aggiustà stu probbleme.",

'upload-proto-error'        => 'Protocolle scorrette',
'upload-proto-error-text'   => "Le carecaminde remote onne abbesogne de le URL ca accumenzene cu 'a parole <code>http://</code> o <code>ftp://</code>.",
'upload-file-error'         => 'Errore inderne',
'upload-file-error-text'    => "S'à verifichete 'n'errore inderne quanne è pruvete a ccreja 'nu file temboranee sus a 'u server.
Pe piacere condatte 'n'[[Special:ListUsers/sysop|amministratore]].",
'upload-misc-error'         => 'Errore de carecamende scanusciute',
'upload-misc-error-text'    => "'N'errore scanusciute s'a verificate quanne ste facime 'u carecamende.
Pe piacere verifiche ca l'URL jè valide e accessibbele e pruève 'n'otra vote.
Ce 'u probbleme angore jè presende, condatte 'n'[[Special:ListUsers/sysop|amministratore]].",
'upload-too-many-redirects' => "'A URL tène troppe redirezionaminde",
'upload-unknown-size'       => 'Dimenziona scanusciute',
'upload-http-error'         => "S'a verificate 'n'errore HTTP: $1",

# img_auth script messages
'img-auth-accessdenied' => 'Accesse negate',
'img-auth-nopathinfo'   => "No se iacchie PATH_INFO.
'U server tue non g'è 'mbostate o non ge passe st'mbormazione.
Pò essere ca jè basate sus a 'u CGI e non ge pò supportà img_auth.
Vide http://www.mediawiki.org/wiki/Manual:Image_Authorization.",
'img-auth-notindir'     => "'U percorse richieste non ge stè jndr'à cartelle de carecamende configurate",
'img-auth-badtitle'     => 'Non ge se pò costruì \'nu titele valide da "$1".',
'img-auth-nologinnWL'   => 'Tu non ge sì collegate e "$1" non ge stè jndr\'à lista vianghe.',
'img-auth-nofile'       => 'File "$1" non g\'esiste.',
'img-auth-isdir'        => 'Tu ste ppruève a trasè jndr\'à cartelle "$1".
Sulamende le file ponne trasè.',
'img-auth-streaming'    => 'Streaming "$1".',
'img-auth-public'       => "'A funziona de img_auth.php jè 'u resultate de le file da 'na Uicchi private.
Sta Uicchi jè configurate cumme a 'na Uicchi pubbleche.
Pe 'na securezze a uerre proprie, img_auth.php jè disabbilitate.",
'img-auth-noread'       => 'L\'utende non ge tène l\'accesse pe leggere "$1".',

# HTTP errors
'http-invalid-url'      => 'URL invalide: $1',
'http-invalid-scheme'   => 'Le URL cu le "$1" scheme non ge sonde supportate.',
'http-request-error'    => "Richieste HTTP fallite pe 'n'errore scanusciute.",
'http-read-error'       => "Errore jndr'à letture de l' HTTP",
'http-timed-out'        => 'Richieste HTTP fore timbe.',
'http-curl-error'       => "Errore analizzanne l'URL: $1",
'http-host-unreachable' => "Non ge riesche a raggiungere l'URL",
'http-bad-status'       => "Ha state 'nu probbleme duranne 'a richieste HTTP: $1, $2",

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => "Non ge riesche a raggiungere l'URL",
'upload-curl-error6-text'  => "'A URL ca è scritte non ge se pò raggiungere.
Pe piacere, condrolle ca 'a URL jè corrette e ca 'u site funzione.",
'upload-curl-error28'      => 'Carecamende in timeout',
'upload-curl-error28-text' => "U site stè mette troppe timbe pe responnere.
Pe piacere condrolle ca 'u site funzione, aspitte 'nu picche e pruève 'n'otra vote.
Sinde a me, vue ccu pruève quanne ste mene casine?",

'license'            => 'Licenziete da:',
'license-header'     => 'Licenziate da',
'nolicense'          => 'Ninde selezionete',
'license-nopreview'  => "(L'andeprime non g'è disponibbile)",
'upload_source_url'  => " ('na URL, valide e accessibbile pubblicamende)",
'upload_source_file' => " ('nu fail sus a 'u combiuter tue)",

# Special:ListFiles
'listfiles-summary'     => "Sta pàgena speciale face vedè tutte le fiel carecate.<br />
Pe default l'urteme file carecate stè sus a sus a liste.<br />
Ce cazze sus a 'a testate d'a colonne cange l'arrengamende.",
'listfiles_search_for'  => 'Cirche pe nome de le media:',
'imgfile'               => 'file',
'listfiles'             => 'Liste de le fail',
'listfiles_thumb'       => 'Miniature',
'listfiles_date'        => 'Sciurne',
'listfiles_name'        => 'Nome',
'listfiles_user'        => 'Utende',
'listfiles_size'        => 'Dimenzione',
'listfiles_description' => 'Descrizione',
'listfiles_count'       => 'Versiune',

# File description page
'file-anchor-link'          => 'File',
'filehist'                  => 'cunde',
'filehist-help'             => "Cazze sus 'na date/orarie pe vedè 'u fail a cumme asseva jndr'à quidde timbe.",
'filehist-deleteall'        => 'scangille tutte',
'filehist-deleteone'        => 'scangille',
'filehist-revert'           => "'nvirte",
'filehist-current'          => 'corrende',
'filehist-datetime'         => 'Sciurne/Orarie',
'filehist-thumb'            => 'Thumbnail',
'filehist-thumbtext'        => "Thumbnail p'a versione de $1",
'filehist-nothumb'          => 'Nisciuna thumbnail',
'filehist-user'             => 'Utende',
'filehist-dimensions'       => 'Dimenziune',
'filehist-filesize'         => "Dimenzione d'u file",
'filehist-comment'          => 'Commende',
'filehist-missing'          => 'File ca no se iacchje',
'imagelinks'                => 'Collegaminde de file',
'linkstoimage'              => '{{PLURAL:$1|sta pàgene apponde |$1 ste pàggene appondene}} a stu fail:',
'linkstoimage-more'         => "Cchiù de $1 {{PLURAL:$1|pàgene se colleghe|pàggene se collegane}} a stu file.<br />
'A seguende liste face vedè {{PLURAL:$1|'a prima pàgene ca se colleghe|le prime $1 pàggene ca se colleghene}} sulamende a stu file.<br />
'Na [[Special:WhatLinksHere/$2|liste comblete]] è disponibbele.",
'nolinkstoimage'            => 'Non ge stonne pàggene ca appodene a stu fail.',
'morelinkstoimage'          => 'Vide [[Special:WhatLinksHere/$1|cchiù collegaminde]] a stu file.',
'redirectstofile'           => "{{PLURAL:$1|'U seguende file se ridirezione|Le seguende $1 file se ridirezionane}} a stu file:",
'duplicatesoffile'          => "{{PLURAL:$1|'U seguende file ète 'nu|Le seguende $1 file sonde}} duplicate de stu file ([[Special:FileDuplicateSearch/$2|cchiù 'mbormaziune]]):",
'sharedupload'              => 'Stu file avène da $1 e pò essere ausate da otre pruggette.',
'sharedupload-desc-there'   => "Stu file è da $1 e pò essere ausate pe otre pruggette.<br />
Pe piacere vide 'a [$2 pàgene de descrizione d'u file] pe maggiore 'mbormaziune.",
'sharedupload-desc-here'    => "Stu file è da $1 e pò essere ausate pe otre pruggette.<br />
'A descriziona sus a [$2 pàgene de descrizione d'u file] ste aqquà sotte.",
'filepage-nofile'           => 'Nisciune file cu stu nome esiste.',
'filepage-nofile-link'      => "Nisciune file cu stu nome esiste, ma tu 'u puè [$1 carecà].",
'uploadnewversion-linktext' => "Careche 'na versiona nove de stu fail",
'shared-repo-from'          => 'da $1',
'shared-repo'               => "'nu condenitore de cose condivise",

# File reversion
'filerevert'                => "'Nvirte $1",
'filerevert-backlink'       => '← $1',
'filerevert-legend'         => "'Nvirte 'u file",
'filerevert-intro'          => "Tu ste converte 'u file '''[[Media:$1|$1]]''' jndr'à [$4 versione cumme $3, $2].",
'filerevert-comment'        => 'Mutive:',
'filerevert-defaultcomment' => "Convertite a 'a versione a le $2 d'u $1",
'filerevert-submit'         => "'Nvirte",
'filerevert-success'        => "'''[[Media:$1|$1]]''' ha state convertite a 'a versiona [$4 de le $3 d'u $2].",
'filerevert-badversion'     => "Non ge stè 'na versiona locale precedende de stu file cu l'orarie richieste.",

# File deletion
'filedelete'                  => 'Scangille $1',
'filedelete-backlink'         => '← $1',
'filedelete-legend'           => "Scangille 'u file",
'filedelete-intro'            => "Tu ste scangille stu file '''[[Media:$1|$1]]''' cu tutte 'a storia soje apprisse.",
'filedelete-intro-old'        => "Tu stè scangille 'a versiona de '''[[Media:$1|$1]]''' cumme de [$4 $3, $2].",
'filedelete-comment'          => 'Mutive:',
'filedelete-submit'           => 'Scangille',
'filedelete-success'          => "'''$1''' ha state scangellete.",
'filedelete-success-old'      => "'A versione de '''[[Media:$1|$1]]''' de le $3 d'u $2 ha state scangellete.",
'filedelete-nofile'           => "'''$1''' non g'esiste.",
'filedelete-nofile-old'       => "Non ge stè 'na versiona archiviate de '''$1''' cu le specifiche attrebbute.",
'filedelete-otherreason'      => 'Otre/addizionale mutive:',
'filedelete-reason-otherlist' => 'Otre mutive',
'filedelete-reason-dropdown'  => "*Mutive comune pe le scangellaminde
** Violazione d'u Copyright
** File duplichete",
'filedelete-edit-reasonlist'  => "Cange le mutive d'a scangellazione",
'filedelete-maintenance'      => "Scangellaminde e repristinaminde de le file jè temboraneamende disabbilitate duranne 'a manutenzione.",

# MIME search
'mimesearch'         => "Ricerca jndr'à 'u MIME",
'mimesearch-summary' => "Sta pàgene abbilitesce 'u filtre sus a le file pu tipe de MIME.
Input: contenttype/subtype, pe esembie <tt>image/jpeg</tt>.",
'mimetype'           => 'Tipe de MIME:',
'download'           => 'scareche',

# Unwatched pages
'unwatchedpages' => 'Pàggene ca non ge ste condrolle',

# List redirects
'listredirects' => 'Liste de le ridirezionaminde',

# Unused templates
'unusedtemplates'     => 'Template ca non ge sonde ausete',
'unusedtemplatestext' => "Sta pàgene elenghe tutte le pàggene jndr'à 'u namespace {{ns:template}} ca non ge stonne ingluse jndr'à otre pàggene.
Arrecuèrdete de condrollà pe otre collegaminde a le template apprime de scangellarle.",
'unusedtemplateswlh'  => 'Otre collegaminde',

# Random page
'randompage'         => 'Pàgene a uecchie',
'randompage-nopages' => "Non ge stonne pàggene jndr'à {{PLURAL:$2|'u seguende namespace|le seguende namespace}}: $1.",

# Random redirect
'randomredirect'         => 'Redirezionamende a uecchie',
'randomredirect-nopages' => 'Non ge stonne redirezionaminde jndr\'à \'u namespace "$1".',

# Statistics
'statistics'                   => 'Statisteche',
'statistics-header-pages'      => 'Pàgene de le statisteche',
'statistics-header-edits'      => 'Cange le statisteche',
'statistics-header-views'      => 'Vide le statisteche',
'statistics-header-users'      => "Statisteche de l'utende",
'statistics-header-hooks'      => 'Otre statisteche',
'statistics-articles'          => 'Pàggene de le condenute',
'statistics-pages'             => 'Pàggene',
'statistics-pages-desc'        => "Tutte le pàggene jndr'à Uicchi, mettènne le pàggene de le 'ngazzaminde, ridirezionaminde, ecc.",
'statistics-files'             => 'File carechete',
'statistics-edits'             => 'Cangiaminde de le pàggene da quanne sta {{SITENAME}} ha state ccrejete',
'statistics-edits-average'     => 'Cangiaminde medie pe pàgene',
'statistics-views-total'       => 'Visite totele',
'statistics-views-total-desc'  => 'Le viste cu le pàggene inesistende e le pàggene speciale non ge sonde ingluse',
'statistics-views-peredit'     => 'Visite pe cangiaminde',
'statistics-users'             => '[[Special:ListUsers|Utinde reggistrete]]',
'statistics-users-active'      => 'Utinde attive',
'statistics-users-active-desc' => "Sonde l'utinde ca onne fatte quacchecose jndr'à l'urteme {{PLURAL:$1|giurne|$1 giurne}}",
'statistics-mostpopular'       => 'Pàggene cchiù visitete',

'disambiguations'      => 'Pàggene de disambiguazione',
'disambiguationspage'  => 'Template:disambigue',
'disambiguations-text' => "Le pàggene seguende appondene a 'na '''pàgene de disambiguazione'''.
'Nvece avessere appondà a 'a temateca appropriate.<br />
'Na pàgene jè trattate cumme pàgene de disambiguazione ce tu ause 'nu template ca è appundate da [[MediaWiki:Disambiguationspage|Pàggene de disambiguazione]]",

'doubleredirects'            => 'Ridirezionaminde a doppie',
'doubleredirectstext'        => "Sta pàgene elenghe le pàggene ca se ridirezionane sus a otre pàggene de ridirezionaminde.
Ogne righe condene 'nu collegamende a 'u prime e a 'u seconde ridirezionamende pe fà vedè addò arrive 'u seconde ridirezionamende, 'u quale jè normalmende 'a pàgena de destinaziona \"rèale\", addò 'u prime ridirezionamende avesse appondà.
Le situaziune de <del>ingrocie</del> onne state resolte.",
'double-redirect-fixed-move' => "[[$1]] ha state spustate.
Mò s'avène redirette a [[$2]].",
'double-redirect-fixer'      => 'Correttore de redirezionaminde',

'brokenredirects'        => 'Redirezionamninde scuasciete',
'brokenredirectstext'    => "Le ridirezionaminde ca seguene appondene a pàggene ca non g'esistene:",
'brokenredirects-edit'   => 'cange',
'brokenredirects-delete' => 'scangille',

'withoutinterwiki'         => 'Pàggene senza collegaminde a otre Uicchi',
'withoutinterwiki-summary' => 'Le pàggene seguende non ge sonde collegate a nisciuna otra versione de lènghe diverse.',
'withoutinterwiki-legend'  => 'Prefisse',
'withoutinterwiki-submit'  => 'Fà vedè',

'fewestrevisions' => 'Pàggene cu mene cangiaminde',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories'             => '$1 {{PLURAL:$1|categorije|categorije}}',
'nlinks'                  => '$1 {{PLURAL:$1|collegamende|collegaminde}}',
'nmembers'                => '$1 {{PLURAL:$1|membre|membre}}',
'nrevisions'              => '$1 {{PLURAL:$1|revisione|revisiune}}',
'nviews'                  => '$1 {{PLURAL:$1|visite|visite}}',
'nimagelinks'             => 'Ausate sus a $1 {{PLURAL:$1|pàgene|pàggene}}',
'ntransclusions'          => 'ausate sus a $1 {{PLURAL:$1|pàgene|pàggene}}',
'specialpage-empty'       => 'Non ge stonne resultete pe stu report.',
'lonelypages'             => 'Pàggene orfane',
'lonelypagestext'         => "Le pàggene ca seguene non ge sonde appondute da otre pàggene o sonde escluse jndr'à otre pàggene sus a {{SITENAME}}.",
'uncategorizedpages'      => 'Pàggene senza categorije',
'uncategorizedcategories' => 'Categorije senza categorije',
'uncategorizedimages'     => 'fail senza categorije',
'uncategorizedtemplates'  => 'Template senza categorije',
'unusedcategories'        => 'Categorije ca non ge sonde ausete',
'unusedimages'            => "Fail ca non g'avènene ausete",
'popularpages'            => 'Pàggene cchiù canusciute',
'wantedcategories'        => 'Categorije cerchete',
'wantedpages'             => 'Pàggene cchiù cerchete',
'wantedpages-badtitle'    => "Titele invalide in mmienze a l'inzieme de le resultate: $1",
'wantedfiles'             => 'File cchiù cerchete',
'wantedtemplates'         => 'Template cchiù ausete',
'mostlinked'              => 'Pàggene cchiù appundete',
'mostlinkedcategories'    => 'Categorije cchiù appundete',
'mostlinkedtemplates'     => 'Template cchiù appundete',
'mostcategories'          => "Pàggene cu 'nu sacche de categorije",
'mostimages'              => 'Fail cchiù appundete',
'mostrevisions'           => 'Pàggene cchiù cangete',
'prefixindex'             => "Tutte le pàggene cu 'u prefisse",
'shortpages'              => 'Pàggene corte',
'longpages'               => 'Pàggene longhe',
'deadendpages'            => 'Pàggene senza collegamende',
'deadendpagestext'        => "Le pàggene ca seguene non g'appondute a otre pàggene sus a {{SITENAME}}.",
'protectedpages'          => 'Pàggene prutette',
'protectedpages-indef'    => 'Sulamende protezziune indefinite',
'protectedpages-cascade'  => 'Sulamende prutezzione a cascata',
'protectedpagestext'      => 'Le pàggene ca seguene sonde prutette da spustaminde e cangiaminde',
'protectedpagesempty'     => 'Nisciuna pàgene jè prutette pe mò cu ste parametre.',
'protectedtitles'         => 'Titele prutette',
'protectedtitlestext'     => "Le titele ca seguene sonde prutette da 'a ccreazione",
'protectedtitlesempty'    => 'Nisciune titele jè pe mò prutette cu ste parametre.',
'listusers'               => "Liste de l'utende",
'listusers-editsonly'     => "Fà vedè sulamende l'utinde cu cangiaminde fatte",
'listusers-creationsort'  => 'Arrenghete pe date de ccreazione',
'usereditcount'           => '$1 {{PLURAL:$1|cangiamende|cangiaminde}}',
'usercreated'             => "Ccrejete 'u $1 a le ore $2",
'newpages'                => 'Pàggene nuève',
'newpages-username'       => "Nome de l'utende:",
'ancientpages'            => 'Pàggene vìcchje',
'move'                    => 'Spuèste',
'movethispage'            => 'Spueste sta pàgene',
'unusedimagestext'        => "Le file seguende esistene ma non ge se iacchiane jndr'à nisciuna pàggene.
Pe piacere note ca otre site de Indernette ponne appondà a 'nu file cu 'na URL dirette, e accussì ponne essere elengate aqquà fine a ca avènene ausate.",
'unusedcategoriestext'    => 'Le seguende categorije esistene, allore che non ge stonne otre pàggene o categorije ause a lore.',
'notargettitle'           => 'Nisciuna destinazione',
'notargettext'            => "Tu non g'è specificate 'na pàgene o 'n'utende de destinazione sus a 'u quale vuè ccu face l'operazione.",
'nopagetitle'             => 'Nisciuna pàgene de destinazione',
'nopagetext'              => "'A pàgene de destinazione ca tu è specificate non g'esiste.",
'pager-newer-n'           => '{{PLURAL:$1|cchiù nueve 1|cchiù nueve $1}}',
'pager-older-n'           => '{{PLURAL:$1|cchiù vecchie 1|cchiù vicchie $1}}',
'suppress'                => 'Supervisione',

# Book sources
'booksources'               => 'Sorgende de le libbre',
'booksources-search-legend' => 'Cirche pe le fonde de le libbre',
'booksources-isbn'          => 'ISBN:',
'booksources-go'            => 'Veje',
'booksources-text'          => "Sotte stè 'na liste de collegaminde a otre site ca vennene libbre nuève e ausete e puà pure acchià cchiù 'mbormaziune sus a le libbre ca tu ste cirche:",
'booksources-invalid-isbn'  => "L'ISBN ca è mise non ge pare ca ète corrette; verifiche ce è commesse quacche errore quanne ste cupiave quidde origginale.",

# Special:Log
'specialloguserlabel'  => 'Utende:',
'speciallogtitlelabel' => 'Titele:',
'log'                  => 'Archivije',
'all-logs-page'        => "Tutte l'archivije pubbleche",
'alllogstext'          => "Visualizzazione combinate de tutte le archivije disponibbele sus a {{SITENAME}}.
Tu puè restringere 'a viste selezionanne 'u tipe de archivije, 'u nome utende (senzibbile a le maiuscole), o le pàggene coinvolte (pure chiste senzibbile a le maiuscole).",
'logempty'             => "Non ge stè 'n'anema de priatorie jndr'à l'archivije.",
'log-title-wildcard'   => 'Cirche le titele ca accumenzene cu stu teste',

# Special:AllPages
'allpages'          => 'Tutte le pàggene',
'alphaindexline'    => 'da $1 a $2',
'nextpage'          => 'Pàgene apprisse ($1)',
'prevpage'          => 'Pàgene apprime ($1)',
'allpagesfrom'      => 'Fà vedè le pàggene partenne da:',
'allpagesto'        => "Fà vedè pàggene ca spiccene 'u:",
'allarticles'       => 'Tutte le pàggene',
'allinnamespace'    => 'Tutte le pàggene (neimspeise $1)',
'allnotinnamespace' => "Tutte le pàggene (ca non ge stonne jndr'à 'u namespace $1)",
'allpagesprev'      => 'Precedende',
'allpagesnext'      => 'Prossime',
'allpagessubmit'    => 'Veje',
'allpagesprefix'    => "Fa vedè le pàggene cu 'u prefisse:",
'allpagesbadtitle'  => "'U titele d'a vôsce ca è date ere invalide o tenève 'nu prefisse inter-lènghe o inter-uicchi.
Pò condenè une  cchiù carattere ca non ge ponne essere ausate jndr'à le titele.",
'allpages-bad-ns'   => '{{SITENAME}} non ge tène \'u namaspace "$1".',

# Special:Categories
'categories'                    => 'Le Categorije',
'categoriespagetext'            => "{{PLURAL:$1|'A seguende categorije tène|Le seguende categorije tènene}} pàggene o media.
[[Special:UnusedCategories|Categorije non ausate]] non ge se vèdene aqquà.
Vide pure [[Special:WantedCategories|Categorije cercate]].",
'categoriesfrom'                => 'Fà vedè le categorije partenne da:',
'special-categories-sort-count' => 'ordere pe condegge',
'special-categories-sort-abc'   => 'ordere alfabbeticamende',

# Special:DeletedContributions
'deletedcontributions'             => "Condrebbute de l'utende scangellete",
'deletedcontributions-title'       => "Condrebbute de l'utende scangellate",
'sp-deletedcontributions-contribs' => 'condrebbute',

# Special:LinkSearch
'linksearch'       => 'Collegaminde fore a Uicchipèdie',
'linksearch-pat'   => 'Cirche le cambiune:',
'linksearch-ns'    => 'Neimspeise:',
'linksearch-ok'    => 'Cirche',
'linksearch-text'  => 'Le wildcard cumme a "*.wikipedia.org" ponne essere ausate.<br />
Protocolle supportate: <tt>$1</tt>',
'linksearch-line'  => '$1 jè pundete da $2',
'linksearch-error' => "Le wildcard ponne essere ausate sulamende a l'inzie de l'hostname.",

# Special:ListUsers
'listusersfrom'      => "Fà vedè l'utinde partenne da:",
'listusers-submit'   => 'Fa vedè',
'listusers-noresult' => 'Nisciune utende acchiete.',
'listusers-blocked'  => '(bloccate)',

# Special:ActiveUsers
'activeusers'            => "Liste de l'utinde attive",
'activeusers-intro'      => "Queste jè 'n'elenghe de utinde ca avene fatte certe tipe de attività fine a l'urteme $1 {{PLURAL:$1|sciurne|sciurne}}.",
'activeusers-count'      => "$1 {{PLURAL:$1|cangiamende|cangiaminde}} jndr'à l'urteme {{PLURAL:$3|sciurne|$3 sciurne}}",
'activeusers-from'       => "Fà vedè l'utinde partenne da:",
'activeusers-hidebots'   => 'Scunne le bot',
'activeusers-hidesysops' => 'Scunne le amministrature',
'activeusers-noresult'   => 'Nisciune utende acchiate.',

# Special:Log/newusers
'newuserlogpage'              => 'Archivije de ccreazione de le utinde',
'newuserlogpagetext'          => "Quiste ète l'archivije de le creazziune de l'utinde.",
'newuserlog-byemail'          => 'password mannete pe e-mail',
'newuserlog-create-entry'     => 'Utende nuève',
'newuserlog-create2-entry'    => 'cunde utende ccrejete pe $1',
'newuserlog-autocreate-entry' => 'Cunde utende ccrejete automaticamende',

# Special:ListGroupRights
'listgrouprights'                      => 'Deritte de le gruppe utinde',
'listgrouprights-summary'              => "'A liste ca ste vide ète 'na liste de le gruppe utinde ccreiate sus a sta Uicchi, cu le lore deritte d'accesse associate.
Ponne stà [[{{MediaWiki:Listgrouprights-helppage}}|'mbormaziune de cchiù]] sus a le deritte individuale.",
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Dà \'nu deritte</span>
* <span class="listgrouprights-revoked">Live \'nu deritte</span>',
'listgrouprights-group'                => 'Gruppe',
'listgrouprights-rights'               => 'Diritte',
'listgrouprights-helppage'             => 'Help:Deritte de le gruppe',
'listgrouprights-members'              => '(liste de le membre)',
'listgrouprights-addgroup'             => 'Puè aggiungere {{PLURAL:$2|gruppe|gruppe}}: $1',
'listgrouprights-removegroup'          => 'Puè scangellà {{PLURAL:$2|gruppe|gruppe}}: $1',
'listgrouprights-addgroup-all'         => 'Puè aggiungere tutte le gruppe',
'listgrouprights-removegroup-all'      => 'Puè luà tutte le gruppe',
'listgrouprights-addgroup-self'        => "Aggiunge {{PLURAL:$2|'u gruppe|le gruppe}} a 'u cunde utende mije: $1",
'listgrouprights-removegroup-self'     => "Live {{PLURAL:$2|'u gruppe|le gruppe}} da 'u cunde utende mije: $1",
'listgrouprights-addgroup-self-all'    => "Mitte tutte le gruppe sus a 'u cunde utende mije",
'listgrouprights-removegroup-self-all' => "Live tutte le gruppe da 'u cunde utende mije",

# E-mail user
'mailnologin'          => 'Nisciune indirizze de invie',
'mailnologintext'      => "Tu a essere [[Special:UserLogin|collegate]] e a avè 'n'indirizze email valide jndr'à le [[Special:Preferences|preferenze]] tue pe mannà 'na mail a otre utinde.",
'emailuser'            => "Manne n'email a stu utende",
'emailpage'            => "E-mail de l'utende",
'emailpagetext'        => "Tu puè ausà 'a schermate aqquà sotte pe mannà 'n'email a stu utende.
L'indirizze e-mail ca tu è 'nzerite jndr'à le [[Special:Preferences|preferenze tue]] iesse jndr'à 'u cambe \"Da\" de l'e-mail, accussìa ce riceve 'a mail sape a ce addà responnere.",
'usermailererror'      => "L'oggette ''Mail'' ha returnete 'n'errore:",
'defemailsubject'      => 'e-mail de {{SITENAME}}',
'usermaildisabled'     => "L'e-mail de l'utende è disabbilitate",
'usermaildisabledtext' => 'Tu non ge puè mannà e-mail a otre utinde sus a sta uicchi',
'noemailtitle'         => 'Nisciune indirizze e-mail',
'noemailtext'          => "Stu utende non g'à specificate 'n'indirizze e-mail valide.",
'nowikiemailtitle'     => 'Nisciuna e-mail è permesse',
'nowikiemailtext'      => 'Stu utende ha scacchiate de nò ricevere email da otre utinde.',
'email-legend'         => "Manne 'na mail a n'otre utende de {{SITENAME}}",
'emailfrom'            => 'Da:',
'emailto'              => 'A:',
'emailsubject'         => 'Oggette:',
'emailmessage'         => 'Messagge:',
'emailsend'            => 'Manne',
'emailccme'            => "Manneme 'n'email cu 'na copie d'u messàgge.",
'emailccsubject'       => 'Copie de le messàgge tue a $1: $2',
'emailsent'            => 'E-mail mannete',
'emailsenttext'        => "'U messagge email tue ha state mannete.",
'emailuserfooter'      => 'Sta e-mail ha state mannate da $1 a $2 da \'a funziona "E-mail a l\'utende" de {{SITENAME}}.',

# User Messenger
'usermessage-summary' => "Lassanne 'nu messagge de sisteme.",
'usermessage-editor'  => 'Messaggiatore de sisteme',

# Watchlist
'watchlist'            => 'Pàggene condrollete',
'mywatchlist'          => 'Pàggene condrollete',
'watchlistfor2'        => 'Pe $1 $2',
'nowatchlist'          => "Non ge tine pàggene jndr'à liste de le pàggene condrollete.",
'watchlistanontext'    => 'Pe piacere $1 pe vedè o cangià le vosce sus a liste de le pàggene condrollete.',
'watchnologin'         => 'Non ge sinde colleghete',
'watchnologintext'     => 'Tu a essere [[Special:UserLogin|colleghete]] pe cangià le pàggene condrollete tue.',
'addedwatch'           => "Mise jndr'à le pàggene condrollete",
'addedwatchtext'       => "'A pàgene \"[[:\$1]]\" ha state aggiunde jndr'à le [[Special:Watchlist|pàggene condrollete]].
Le cangiaminde future a sta pàgene e 'a pàgene de le 'ngazzaminde associete le puè acchià aqquà, e 'a pàgene avène signete cu 'u '''grascette''' jndr'à [[Special:RecentChanges|liste de le cangiaminde recende]] pe facilità l'identificazione.",
'removedwatch'         => 'Live da le pàggene condrollate',
'removedwatchtext'     => '\'A pàgene "[[:$1]]" ha state scangillete da [[Special:Watchlist|le pàggene condrollete tue]].',
'watch'                => 'Condrolle',
'watchthispage'        => 'Condrolle sta pàgene',
'unwatch'              => 'No condrollà cchiù',
'unwatchthispage'      => "No condrollà cchiù 'a pàgene",
'notanarticle'         => "Non g'è 'na vosce",
'notvisiblerev'        => "'A revisione ha state scangellete",
'watchnochange'        => "Niscune de le vôsce condrollete onne state cangete jndr'à 'u periode visualizzate.",
'watchlist-details'    => "{{PLURAL:$1|$1 pàgene|$1 pàggene}} jndr'à liste de le pàggene condrollete, scartanne le pàggene de le 'ngazzaminde.",
'wlheader-enotif'      => "* 'A notifiche de le e-mail notification jè abbilitete.",
'wlheader-showupdated' => "* Le pàggene ca onne cangete da quanne tu l'è visitate sonde visualizzate in '''grascette'''",
'watchmethod-recent'   => 'verifiche de le cangiaminde recende pe le pàggene condrollete',
'watchmethod-list'     => 'stoche a condrolle le pàggene condrollete pe le urteme cangiaminde',
'watchlistcontains'    => "'A liste de le pàggene condrollete toje condene $1 {{PLURAL:$1|pàgene|pàggene}}.",
'iteminvalidname'      => "Probbleme cu 'a vosce '$1', nome invalide...",
'wlnote'               => "Aqquà sotte {{PLURAL:$1|ste l'urteme cangiamende|stonne l'urteme '''$1''' cangiaminde}} jndr'à {{PLURAL:$2|l'urtema ore|l'urteme '''$2''' ore}}.",
'wlshowlast'           => "Vide l'urteme $1 ore $2 sciurne $3",
'watchlist-options'    => "Opzione d'a liste de le pàggene condrollete",

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Fà vedè...',
'unwatching' => 'No fà vedè...',

'enotif_mailer'                => '{{SITENAME}} Notificatore de email',
'enotif_reset'                 => 'Signe tutte le pàggene cumme visitete',
'enotif_newpagetext'           => "Queste è 'na pàgena nove.",
'enotif_impersonal_salutation' => 'Utende de {{SITENAME}}',
'changed'                      => 'cangete',
'created'                      => 'ccrejete',
'enotif_subject'               => '\'A pàgene de {{SITENAME}} $PAGETITLE ha state $CHANGEDORCREATED da $PAGEEDITOR',
'enotif_lastvisited'           => "Vide $1 pe tutte le cangiaminde da l'urtema visita toje.",
'enotif_lastdiff'              => 'Vide $1 pe vedè stu cangiamende.',
'enotif_anon_editor'           => 'Utende anonime $1',
'enotif_body'                  => 'Care $WATCHINGUSERNAME,


\'A pàgene $PAGETITLE de {{SITENAME}} ha state $CHANGEDORCREATED \'u $PAGEEDITDATE da $PAGEEDITOR, vide $PAGETITLE_URL pa versiona corrende.

$NEWPAGE

Riepileghe de le cangiaminde: $PAGESUMMARY $PAGEMINOREDIT

Condatte l\'editore:
mail: $PAGEEDITOR_EMAIL
uicchi: $PAGEEDITOR_WIKI

Non ge stonne otre notifiche ce tu face otre cangiaminde senza ca tu visite sta pàgene.
Tu puè pure azzerà \'a spunde de le notifiche pe tutte le pàggene condrollete jndr\'à lista toje.

             Statte Bbuene, \'u sisteme de notificaziune de {{SITENAME}}

--
Pe cangià le \'mbostaziune d\'a liste de le pàggene condrollete tue, vè vide
{{fullurl:{{#special:Watchlist}}/edit}}

Pe scangellà \'a pàgene da \'a liste de le pàggene condrollate, vè vide
$UNWATCHURL

Segnalaziune e otre assistenze:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => "Scangille 'a pàgene",
'confirm'                => 'Conferme',
'excontent'              => "'u condenute ere: '$1'",
'excontentauthor'        => "'u condenute ere: '$1' (e l'uneche condrebbutore ere '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'          => "'u condenute apprime d'a pulizie ere: '$1'",
'exblank'                => "'a vosce ere vacande",
'delete-confirm'         => 'Scangille "$1"',
'delete-backlink'        => '← $1',
'delete-legend'          => 'Scangille',
'historywarning'         => "'''Vide Bbuene:''' 'a pàgene ca ste scangille tène 'na storie de cangiaminde cu cchiù o mene $1 {{PLURAL:$1|revisione|revisiune}}:",
'confirmdeletetext'      => "Vide Bbuene, vide ca ste scangille 'na pàgene ca tène pure nu sbuenne de cangiaminde.
Conferme quidde ca ste face, ce si sicure ca è capite quidde ca ste cumbine e ce è corrette rispette a [[{{MediaWiki:Policy-url}}|le regole de scangellazione]], ce no statte quiete.",
'actioncomplete'         => 'Aziona Combletete',
'actionfailed'           => 'Aziona fallite',
'deletedtext'            => '"<nowiki>$1</nowiki>" onne state scangillete.
Vide $2 pe \'na reggistrazione de le scangellaziune recende.',
'deletedarticle'         => 'scangillete "[[$1]]"',
'suppressedarticle'      => 'soppresse "[[$1]]"',
'dellogpage'             => 'Archivie de le scangellaminde',
'dellogpagetext'         => "Sotte ste 'na liste de le cchiù recende scangellaziune.",
'deletionlog'            => 'Archivije de le scangellaminde',
'reverted'               => "Turnà a 'a revisiona cchiù recende",
'deletecomment'          => 'Mutive:',
'deleteotherreason'      => 'Otre mutive de cchiù:',
'deletereasonotherlist'  => 'Otre mutive',
'deletereason-dropdown'  => "*Mutive comune de scangellaminde
** Richieste de l'autore
** Violazione d'u Copyright
** Vandalisme",
'delete-edit-reasonlist' => 'Mutive de scangellazione de le cangiaminde',
'delete-toobig'          => "Sta pàgene tène 'na storie de cangiaminde troppe longhe, sus a $1 {{PLURAL:$1|revisione|revisiune}}.
'U scangellamende de stuèzze de pàgene avène ristrette pe prevenì 'ngasinaminde accidentale de {{SITENAME}}.",
'delete-warning-toobig'  => "Sta pàgene tène 'na storie troppo longhe, sus a $1 {{PLURAL:$1|revisione|revisiune}}.
Scangellanne pò ccreja casine sus a le operazione d'u database de {{SITENAME}};
và cunge cunge!",

# Rollback
'rollback'          => 'Annulle le cangiaminde',
'rollback_short'    => 'Annulle',
'rollbacklink'      => "annulle 'u cangiaminde",
'rollbackfailed'    => 'Annullamende fallite',
'cantrollback'      => "Non ge se pò annullà stu cangiamende;
l'urteme condrebbutore jè sulamende l'autore de sta pàgene.",
'alreadyrolled'     => "Non ge se pò annulla l'urteme cangiamende de [[:$1]] da [[User:$2|$2]] ([[User talk:$2|'Ngazzaminde]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]);
quacche otre ha cangiate o annullate ggià 'a pàgene.

L'urteme cangiamende d'a pàgene ere de [[User:$3|$3]] ([[User talk:$3|'Ngazzaminde]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).",
'editcomment'       => "'U riepileghe d'u cangiamende ere: \"''\$1''\".",
'revertpage'        => "Cangiaminde annullate da [[Special:Contributions/$2|$2]] ([[User talk:$2|Talk]]) a l'urtema versione da [[User:$1|$1]]",
'revertpage-nouser' => "Le cangiaminde annullate ba (nome utende luate) a l'urtema revisione da [[User:$1|$1]]",
'rollback-success'  => "Cangiaminde annullate da $1;
turnate rete a l'urtema versione da $2.",

# Edit tokens
'sessionfailure-title' => 'Sessione fallite',
'sessionfailure'       => "Pare ca stonne probbleme cu 'a sessiona toje de collegamende;
st'azione ha state scangellate pe precauzione condre a le 'ngasinaminde d'a sessione.
Pe piacere cazze \"rete\" e recareche 'a pàgene da addò tu è venute e pruève 'n'otra vote.",

# Protect
'protectlogpage'              => 'Archibie de le prutezziune',
'protectlogtext'              => "Sotte ste 'na liste de pàggene bloccate e sbloccate.
Vide 'a [[Special:ProtectedPages|liste de le pàggene prutette]] pa liste de le operaziune currende sus a le prutezzione de le pàggene.",
'protectedarticle'            => 'prutette "[[$1]]"',
'modifiedarticleprotection'   => '\'u levèlle de protezione ha state cangete pe "[[$1]]"',
'unprotectedarticle'          => 'sprutette "[[$1]]"',
'movedarticleprotection'      => '\'mbostaziune de protezzione spustate da "[[$2]]" a "[[$1]]"',
'protect-title'               => 'Cange levèlle de protezione pe "$1"',
'prot_1movedto2'              => "[[$1]] spustete jndr'à [[$2]]",
'protect-backlink'            => '← $1',
'protect-legend'              => "Conferme 'a protezione",
'protectcomment'              => 'Mutive:',
'protectexpiry'               => 'More:',
'protect_expiry_invalid'      => 'Orarie de scadenze jè invalide.',
'protect_expiry_old'          => "L'ore de scadenza jè jndr'à 'u passate.",
'protect-unchain-permissions' => 'Sbluecche otre opzione de protezzione',
'protect-text'                => "Tu puè vedè e cangià 'u levèlle de protezzione p'a pàgene '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "Tu non ge puè cangià le levèlle de protezzione quanne si bloccate.
Aqquà stonne le 'mbostaziune corrende pa pàgene '''$1''':",
'protect-locked-dblock'       => "Le levèlle de protezzione non ge ponne essere cangete purcè stè 'nu blocche d'u database.
Aqquà stonne le 'mbostaziune corrende pa pàgene '''$1''':",
'protect-locked-access'       => "Tu non ge tine le permesse pe putè cangià le levèlle de protezione de le pàggene.
Chiste sonde le configuraziune corrende p'a pàgene '''$1''':",
'protect-cascadeon'           => "Sta pàgene mò jè  prutette purcè jè ingluse jndr'à {{PLURAL:$1|'a seguende pàgene, ca tène|le seguende pàggene, ca tènene}} a protezione a cascata appizzechete.
Tu puè cangià 'u levèlle de protezione de sta pàgene ma stu cangiamende non ge tène effette a cascata.",
'protect-default'             => "Permitte a tutte l'utinde",
'protect-fallback'            => 'Richieste \'u permesse "$1"',
'protect-level-autoconfirmed' => "Blocche l'utinde nuève e chidde non reggistrete",
'protect-level-sysop'         => 'Sulamende pe le Sysops',
'protect-summary-cascade'     => 'a caschete',
'protect-expiring'            => "more 'u $1 (UTC)",
'protect-expiry-indefinite'   => 'indefinite',
'protect-cascade'             => "Le pàggene prutette 'ngludene sta pàgene (protezione a caschete)",
'protect-cantedit'            => 'Tu non ge puè cangià le levèlle de protezzione de sta pàgene, purcè tu non ge tine le diritte pe cangiarle.',
'protect-othertime'           => 'Otre timbe:',
'protect-othertime-op'        => 'otre orarie',
'protect-existing-expiry'     => "'U timbe de scadenze esistende: $3, $2",
'protect-otherreason'         => 'Otre mutive:',
'protect-otherreason-op'      => 'Otre mutive',
'protect-dropdown'            => '*Mutive de protezzione comune
** Vandalisme eccessive
** Spamming eccessive
** Uerre de cangiaminde condro producende
** Ierte traffiche sus a pàgene',
'protect-edit-reasonlist'     => "Cange le mutive d'a protezione",
'protect-expiry-options'      => '1 ore:1 hour,1 giurne:1 day,1 sumane:1 week,2 sumane:2 weeks,1 mese:1 month,3 mise:3 months,6 mise:6 months,1 anne:1 year,infinite:infinite',
'restriction-type'            => 'Permesse:',
'restriction-level'           => 'Levèlle de restrizione:',
'minimum-size'                => 'Dimenzione minime:',
'maximum-size'                => 'Dimenzione massime:',
'pagesize'                    => '(bytes)',

# Restrictions (nouns)
'restriction-edit'   => 'Cange',
'restriction-move'   => 'Spuèste',
'restriction-create' => 'Ccreje',
'restriction-upload' => 'Careche',

# Restriction levels
'restriction-level-sysop'         => 'tutte prutette',
'restriction-level-autoconfirmed' => "'mmienze prutette",
'restriction-level-all'           => 'ogne levèlle',

# Undelete
'undelete'                     => 'Vide le pàggene scangellete',
'undeletepage'                 => 'Vide e sripristine le pàggene scangellete',
'undeletepagetitle'            => "'''Jndr'à seguende stonne le revisiune scangellate de [[:$1|$1]]'''.",
'viewdeletedpage'              => 'Vide le pàggene scangellete',
'undeletepagetext'             => "{{PLURAL:$1|'A seguende pàgene ha state scangellate ma ète|Le seguende $1 pàggene one state scangellate ma sonde}} angore in archivije e se pò reprocessà.<br />
L'archivije avène periodicamende sdevachete.",
'undelete-fieldset-title'      => 'Repristine le revisiune',
'undeleteextrahelp'            => "Pe repristina totte 'a storie d'a pàgene, lasse tutte le caselle cu le spunde vacande e cazze '''''Repristine'''''.
Pe fà 'nu repristine selettive, mitte 'a spunde jndr'à le caselle corrispondende a le revisiune ca vuè ccu repristine e pò cazze '''''Repristine'''''.
Cazzanne '''''Azzera''''' avène pulezzate 'u cambe d'u commende e tutte le caselle cu 'a spunde.",
'undeleterevisions'            => '$1 {{PLURAL:$1|revisione|revisiune}} archiviete',
'undeletehistory'              => "Ce tu repristine 'a pàgene, tutte le revisiune avènene repristinate jndr'à storie.
Ce 'na pàgena nove cu 'u stesse nome ha state ccrejate da 'a scangellazione, le revisiune repristinate iessène jndr'à storia prengepàle.",
'undeleterevdel'               => "Repristine non ge ponne essere eseguite ce succede ca sus a pàgene prengepàle o sus le revisiune d'u file onne state parzialmende scangellate.
Jndr'à ste case, tu à smarcà o a sconnere 'a revisione scangellate cchiù nove.",
'undeletehistorynoadmin'       => "Sta pàgene ha state scangellate.
'U mutive d'a scangellazione ste scritte jndr'à 'u riepileghe de sotte, cu tutte le dettaglie de l'utinde ca onne mise mane jndr'à sta pàgene apprima d'u scangellamende.
'U teste corrende de ste revisiune scangellate jè sulamende disponibbile pe l'amministrature.",
'undelete-revision'            => 'Revisiona scangellete de $1 (cumme de $4, a $5) da $3:',
'undeleterevision-missing'     => "Revisiona invalide o mangande.
Tu puè avè 'nu collegamende sbagliate o 'a revisione pò essere ca ha state repristinate o luvete da l'archivije.",
'undelete-nodiff'              => 'Nisciuna revisiona precedende ha state acchijate.',
'undeletebtn'                  => 'Repristine',
'undeletelink'                 => 'vide/repristine',
'undeleteviewlink'             => 'vide',
'undeletereset'                => 'Azzere',
'undeleteinvert'               => 'Selezione a smerse',
'undeletecomment'              => 'Mutive:',
'undeletedarticle'             => 'ripristinete "[[$1]]"',
'undeletedrevisions'           => '{{PLURAL:$1|1 revisione|$1 revisiune}} ripristinete',
'undeletedrevisions-files'     => '{{PLURAL:$1|1 revisione|$1 revisiune}} e {{PLURAL:$2|1 file|$2 file}} ripristinete',
'undeletedfiles'               => '{{PLURAL:$1|1 file|$1 file}} ripristinete',
'cannotundelete'               => "Repristine fallite;
quaccheotre pò essere ca ha repristinate 'a pàgene apprime.",
'undeletedpage'                => "'''$1 ha state repristinate'''

Ligge l'[[Special:Log/delete|archivije de le scangellaminde]] pe 'nu report de le urteme scangellaminde e repristinaminde.",
'undelete-header'              => "Vide [[Special:Log/delete|l'archivije de le scangellaminde]] pe l'urteme pàggene scangellete.",
'undelete-search-box'          => 'Cirche le pàggene scangellete',
'undelete-search-prefix'       => 'Fà vedè le pàggene ca accumenzene cu:',
'undelete-search-submit'       => 'Cirche',
'undelete-no-results'          => "Non ge stonne pàggene acchiate jndr'à l'archivije de le scangellaminde.",
'undelete-filename-mismatch'   => "Non ge pozze repristinà 'a revisione d'u file cu orarie $1: nome d'u file errate",
'undelete-bad-store-key'       => "Cannot undelete file revision with timestamp $1: file was missing before deletion.
Non ge pozze repristinà 'a revisione d'u file cu orarie $1: 'u file ha state perdute apprime da scangellazione.",
'undelete-cleanup-error'       => 'Errore scangellanne \'n\'archivije de file non ausate "$1".',
'undelete-missing-filearchive' => "Non ge pozze repristinà 'u file archiviate cu ID $1 purcè non ge stè jndr'à 'u database.
Pò essere ca già ha state scangellate.",
'undelete-error-short'         => 'Errore file non recuperate: $1',
'undelete-error-long'          => "Errore ca s'onne acchiate quanne amme pruvate a reprisitinà 'u file:

$1",
'undelete-show-file-confirm'   => 'Sì secure ca tu vuè ccu vide \'na revisiona scangellate d\'u file "<nowiki>$1</nowiki>" d\'u $2 a le $3?',
'undelete-show-file-submit'    => 'Sine',

# Namespace form on various pages
'namespace'      => 'Namespace:',
'invert'         => "Selezione 'a smerse",
'blanknamespace' => '(Prengepàle)',

# Contributions
'contributions'       => "Condrebbute de l'utende",
'contributions-title' => "Condrebbute de l'utende pe $1",
'mycontris'           => 'Condrebbute mie',
'contribsub2'         => 'Pe $1 ($2)',
'nocontribs'          => 'Nisciune cangiamende ha state acchiate cu ste criterie.',
'uctop'               => '(sus)',
'month'               => "Da 'u mese (e cchiù recende):",
'year'                => "Da l'anne (e cchiù recende):",

'sp-contributions-newbies'             => 'Fà vedè sulamende le condrebbute de le utinde nueve',
'sp-contributions-newbies-sub'         => "Pe l'utinde nuève",
'sp-contributions-newbies-title'       => "Condrebbute de l'utinde pe le cunde utinde nuéve",
'sp-contributions-blocklog'            => 'Archivije de le Bloccaminde',
'sp-contributions-deleted'             => "condrebbute de l'utende scangellate",
'sp-contributions-uploads'             => 'carecaminde',
'sp-contributions-logs'                => 'archivije',
'sp-contributions-talk'                => 'parle',
'sp-contributions-userrights'          => 'Gestione de le deritte utende',
'sp-contributions-blocked-notice'      => "Stu utende jè pe mò bloccate. L'urteme archivije de le bloccaminde se iacchie aqquà sotte pe referimende:",
'sp-contributions-blocked-notice-anon' => "Stu indirizze IP jè pe mò bloccate.<br />
L'urteme archivije de le bloccaminde se iacche aqquà sotte pe referimende:",
'sp-contributions-search'              => 'Ricerche pe condrebbute',
'sp-contributions-username'            => "Indirizze IP o nome de l'utende:",
'sp-contributions-toponly'             => "Sulamende facenne vedè le cangiaminde de l'urteme revisiune",
'sp-contributions-submit'              => 'Cirche',

# What links here
'whatlinkshere'            => 'Appondene aqquà',
'whatlinkshere-title'      => 'Pàggene ca appondene a "$1"',
'whatlinkshere-page'       => 'Pàgene:',
'whatlinkshere-backlink'   => '← $1',
'linkshere'                => "Le pàggene ca avènene appondene a '''[[:$1]]''':",
'nolinkshere'              => "Nisciuna pàgene apponde a '''[[:$1]]'''.",
'nolinkshere-ns'           => "Nisciuna pàgene apponde a '''[[:$1]]''' jndr'à 'u namespace scacchiete.",
'isredirect'               => 'pàgene de ridirezionamende',
'istemplate'               => 'inclusione',
'isimage'                  => "collegamende a l'immaggine",
'whatlinkshere-prev'       => '{{PLURAL:$1|apprime|apprime $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|apprisse|apprisse $1}}',
'whatlinkshere-links'      => '← collegaminde',
'whatlinkshere-hideredirs' => '$1 ridirezionaminde',
'whatlinkshere-hidetrans'  => '$1 transclusiune',
'whatlinkshere-hidelinks'  => '$1 collegaminde',
'whatlinkshere-hideimages' => '$1 collegaminde a immaggine',
'whatlinkshere-filters'    => 'Filtre',

# Block/unblock
'blockip'                         => "Blocche l'utende",
'blockip-title'                   => "Bluecche l'utende",
'blockip-legend'                  => "Bluecche l'utende",
'blockiptext'                     => "Ause 'a schermata de sotte pe bloccà l'accesse in scritture de 'nu specifiche indirizze IP o utende.
Quiste avessa essere fatte sulamende pe prevenìe 'u vandalisme e in accorde cu [[{{MediaWiki:Policy-url}}|le reghele]].
Mitte pure 'nu mutive specifiche aqquà sotte (pe esembije, nnomene 'a pàgene addò è acchiate 'u vandalisme).",
'ipaddress'                       => 'Indirizze IP:',
'ipadressorusername'              => "Indirizze IP o nome de l'utende:",
'ipbexpiry'                       => 'More:',
'ipbreason'                       => 'Mutive:',
'ipbreasonotherlist'              => 'Otre mutive',
'ipbreason-dropdown'              => "*Mutive comune de blocche
** Inzerimende de 'mbormaziune fause
** Scangellamende de condenute da le vôsce
** Collegaminde pubblecetarie a site fore de Uicchipèdie
** Inzerimende de studecarie jndr'à le vôsce
** Menacce e intimidaziune
** Abbuse de cunde utende multiple
** Nome de l'utende inaccettabbele",
'ipbanononly'                     => "Blocche sulamende l'utinde anonime",
'ipbcreateaccount'                => 'No fà ccrejà le cunde utinde',
'ipbemailban'                     => "No fà mannà email a l'utinde",
'ipbenableautoblock'              => "Automaticamende blocche l'urteme indirizze IP ausate da stu utende e tutte le sottosequenze de le IP ca onne state ausate pe cangià",
'ipbsubmit'                       => "Blocche st'utende",
'ipbother'                        => 'Otre orarie:',
'ipboptions'                      => '2 ore:2 hours,1 giurne:1 day,3 giurne:3 days,1 sumane:1 week,2 sumane:2 weeks,1 mese:1 month,3 mise:3 months,6 mise:6 months,1 anne:1 year,infinite:infinite',
'ipbotheroption'                  => 'otre',
'ipbotherreason'                  => 'Otre mutive:',
'ipbhidename'                     => 'Scunne le nome utinde da le cangiaminde e da le liste',
'ipbwatchuser'                    => "Vide a pàgena utende e quedde de le 'ngazzaminde de stu utende",
'ipballowusertalk'                => "Permette a stu utende de cangià 'a propria pàgene de le 'ngazzaminde quanne ète bloccate",
'ipb-change-block'                => "Blocche 'n'otra vote l'utende cu ste 'mbostaziune",
'badipaddress'                    => 'Indirizze IP invalide',
'blockipsuccesssub'               => 'Blocche effettuate',
'blockipsuccesstext'              => "[[Special:Contributions/$1|$1]] ha state bloccate.<br />
Vide [[Special:IPBlockList|'a liste de le IP bloccate]] pe revedè le blocche.",
'ipb-edit-dropdown'               => "Cange le mutive d'u blocche",
'ipb-unblock-addr'                => 'Sblocche $1',
'ipb-unblock'                     => 'Sblocche nome utende o indirizze IP',
'ipb-blocklist'                   => 'Vide le blocche ca esistene',
'ipb-blocklist-contribs'          => 'Condrebbute pe $1',
'unblockip'                       => "Sblocche l'utende",
'unblockiptext'                   => "Ause 'a maschera aqquà sotte pe repristinà l'accesse in scritture a le indirizze IP o a le cunde utinde ca apprime avèrene state bloccate.",
'ipusubmit'                       => 'Live stu blocche',
'unblocked'                       => '[[User:$1|$1]] ha state sblocchete',
'unblocked-id'                    => 'Blocche $1 ha state luvete',
'ipblocklist'                     => "'Ndirizze IP e nome utinde blocchete",
'ipblocklist-legend'              => "Iacchije 'n'utende blocchete",
'ipblocklist-username'            => 'Nome utende o indirizze IP:',
'ipblocklist-sh-userblocks'       => '$1 le cunde utinde blocchete',
'ipblocklist-sh-tempblocks'       => '$1 le blocche temboranee',
'ipblocklist-sh-addressblocks'    => '$1 le blocche de le singhele indirizze IP',
'ipblocklist-submit'              => 'Cirche',
'ipblocklist-localblock'          => 'Blocche locale',
'ipblocklist-otherblocks'         => 'Otre {{PLURAL:$1|blocche|blocche}}',
'blocklistline'                   => '$1, $2 blocchete $3 ($4)',
'infiniteblock'                   => 'quanne pisce a iaddine',
'expiringblock'                   => "more 'u $1 a le $2",
'anononlyblock'                   => "sulamende l'anonime",
'noautoblockblock'                => 'autoblocche disabbilitete',
'createaccountblock'              => "sulamende l'utinde anonime",
'emailblock'                      => 'e-mail blocchete',
'blocklist-nousertalk'            => "Non ge puè cangià 'a pàgene de le 'ngazzaminde toje",
'ipblocklist-empty'               => "'A liste de le blocche jè vacande.",
'ipblocklist-no-results'          => "L'indirizze IP ca è cerchete o 'u nome utende non ge sonde blocchete.",
'blocklink'                       => 'blocche',
'unblocklink'                     => 'sblocche',
'change-blocklink'                => "cange 'u blocche",
'contribslink'                    => 'condrebbute',
'autoblocker'                     => 'Autobloccate purcè l\'indirizze IP tue ha state recendemente ausate da "[[User:$1|$1]]".
\'U mutive date pu blocche de $1 ète: "$2"',
'blocklogpage'                    => 'Archivije de le Bloccaminde',
'blocklog-showlog'                => "Stu utende ha state bloccate precedendemende.
L'archivije de le bloccaminde 'u puè acchià aqquà sotte pe riferimende:",
'blocklog-showsuppresslog'        => "Stu utende ha state bloccate e scunnute precedendemende.
L'archivije de le soppressiune 'u puè acchià aqquà sotte pe riferimende:",
'blocklogentry'                   => "blocchete [[$1]] pe 'nu timbe de $2 $3",
'reblock-logentry'                => "cangiate l'imbostazione de le blocche pe [[$1]] cu 'na data de scadenze de $2 $3",
'blocklogtext'                    => "Quiste è l'archivije de l'aziune de blocche e sblocche pe l'utinde.
L'indirizze IP automaticamende bloccate non ge stonne jndr'à liste.
Vide 'a [[Special:IPBlockList|liste de le IP bloccate]] pa liste de le operaziune de ban e blocche ca stonne attive mò.",
'unblocklogentry'                 => 'sblocchete $1',
'block-log-flags-anononly'        => "sulamende l'utinde anonime",
'block-log-flags-nocreate'        => 'ccreazione de le cunde utinde disabbilitete',
'block-log-flags-noautoblock'     => 'auto blocche disabbilitete',
'block-log-flags-noemail'         => 'e-mail blocchete',
'block-log-flags-nousertalk'      => "non ge puè cangià 'a pàgene de le 'ngazzaminde toje",
'block-log-flags-angry-autoblock' => 'auto blocche avanzate abbilitate',
'block-log-flags-hiddenname'      => "nome de l'utende scunnute",
'range_block_disabled'            => "L'abbilità de le amministrature de ccrejà blocche a indervalle jè disabbilitate.",
'ipb_expiry_invalid'              => "L'orarije de scadenze non g'è valide.",
'ipb_expiry_temp'                 => "Le blocche sus a le nome de l'utinde scunnute onna essere permanende.",
'ipb_hide_invalid'                => 'Non ge se pò scangellà stu cunde utende; tène troppe cangiaminde.',
'ipb_already_blocked'             => '"$1" jè ggià blocchete',
'ipb-needreblock'                 => "== Già blocchete ==
$1 ha state già blocchete. Vuè cu cange le 'mbostaziune?",
'ipb-otherblocks-header'          => 'Otre {{PLURAL:$1|blocche|blocche}}',
'ipb_cant_unblock'                => "Errore: L'ID $1 d'u blocche non ge se iacchie.
Pò essere ca ha state già sbloccate.",
'ipb_blocked_as_range'            => "Errore: l'IP $1 non g'à state bloccate direttamende e non ge pò essere sbloccate.
Jidde ha state bloccate cumme parte de l'indervalle $2, ca pò essere sbloccate.",
'ip_range_invalid'                => "L'indervalle de l'IP non g'è valide.",
'ip_range_toolarge'               => 'Le indervalle de le blocche cchiù larie de /$1 non ge sonde permesse.',
'blockme'                         => 'Bloccheme',
'proxyblocker'                    => 'Bloccaore de proxy',
'proxyblocker-disabled'           => "'A funzione ha state disabbilitete.",
'proxyblockreason'                => "L'indirizze IP tue ha state bloccate purcè jè 'nu proxy apirte.
Pe piacere condatte 'u provider de Indernette tue o 'u supporte tecniche e 'mbormescele de stu serie probbleme de securezze.",
'proxyblocksuccess'               => 'Spicciete.',
'sorbs'                           => 'DNSBL',
'sorbsreason'                     => "L'indirizze IP tue jè elegate cumme a 'nu proxy apirte jndr'à DNSBL ausate da {{SITENAME}}.",
'sorbs_create_account_reason'     => "L'indirizze IP tue jè elegate cumme a 'nu proxy apirte jndr'à DNSBL ausate da {{SITENAME}}.
Tu nonge puè ccrejà 'nu cunde utende",
'cant-block-while-blocked'        => 'Tu non ge puè bloccà otre utinde quanne tu si blocchete.',
'cant-see-hidden-user'            => "L'utende ca tu ste pruève a bloccà ha state già bloccate e scunnute. Ce tu non ge tine le deritte ''hideuser'', tu non ge puè vedè o cangià 'u blocche de l'utende.",
'ipbblocked'                      => 'Tu non ge puè bloccà o sbloccà otre utinde, purcé tu sì ppure bloccate',
'ipbnounblockself'                => "Non ge t'è permesse de sbloccarte da sule",

# Developer tools
'lockdb'              => 'Blocche databeise',
'unlockdb'            => 'Sblocche databeise',
'lockdbtext'          => "Bloccanne 'u database avène sospese l'abbilità de tutte l'utinde de cangià le pàggene, cangià le preferenze lore, cangà 'a liste de le pàggene condrollate e otre cose ca richiedene cangiaminde sus a 'u database.
Pe piacere conferme ca quiste jè quidde ca tu vuè ccù face e arrecuèrdete de sbloccà 'u database quanne è finite 'a manutenziona toje.",
'unlockdbtext'        => "Sbloccanne 'u database repristinesce l'abbilità de tutte l'utinde de cangià le pàggene, cangià le preferenze lore, cangà 'a liste de le pàggene condrollate e otre cose ca richiedene cangiaminde sus a 'u database.
Pe piacere conferme ca quiste jè quidde ca tu vuè ccù face.",
'lockconfirm'         => "Sine, Je avveramende vogghie cu blocche 'u database.",
'unlockconfirm'       => "Sine, Je avveramende vogghie cu sblocche 'u database.",
'lockbtn'             => 'Blocche databeise',
'unlockbtn'           => 'Sblocche databeise',
'locknoconfirm'       => "Tu non g'è selezionate 'a scatele de conferme.",
'lockdbsuccesssub'    => "'U blocche d'u database ha state fatte cu successe",
'unlockdbsuccesssub'  => "'U blocche d'u database ha state luete",
'lockdbsuccesstext'   => "'U database ha state blocchete.<br />
Arrecuerdete de [[Special:UnlockDB|luvà 'u blocche]] apprrisse ca 'a manutenziona ha state combletate.",
'unlockdbsuccesstext' => "'U database ha state sblocchete.",
'lockfilenotwritable' => "'U blocche sus a le file d'u database non g'è scrivibbile.
Pe bloccà o sbloccà 'u database quiste abbesogne de essere scritte da 'nu web server.",
'databasenotlocked'   => "'U database non g'è blocchete.",

# Move page
'move-page'                    => 'Spuèste $1',
'move-page-backlink'           => '← $1',
'move-page-legend'             => "Spuèste 'a pàgene",
'movepagetext'                 => "Ausanne 'u form aqquà sotte ste cange 'u nome d'a pàgene, spustanne tutte a storia soje sus a 'u nome néve.
U' vecchie titele devènde 'nu ridirezionamende sus 'a pàgena nove.
Tu puè aggiornà 'u ridirezionamende ca apponde da 'u titele automaticamende.
Ce tu no fece ninde condrolle ca non ccreje [[Special:DoubleRedirects|doppie ridirezionaminde ]] o [[Special:BrokenRedirects|ridirezionaminde scuasciete]].
Tu si 'u responsabbile de quidde ca cumbine, allore me raccomande fa attenzione.

Vide Bbuene ca 'a pàgene non g'avene spustete ce esiste n'otra pàgene cu 'u titele nuéve, a mene ca jè vacande o jè 'na pàgene de ridirezionamende senza storie.
Quieste significhe ca tu puè fà turnà 'u vecchie nome 'a pàgene ce jedde ha state renomenete e t'è rese conde ca è fatte 'na studecarije sovrascrevènne 'na pàgene esistende.

'''ATTENZIONE!'''
Quiste pò essere 'nu cangiamende drastiche e inaspettete de 'na pàgene famose assaje;
pe piacere a essere secure-secure de le conseguenze prime de procedere.",
'movepagetext-noredirectfixer' => "Ausanne 'u module aqquà sotte puè renomenà 'na pàgene, spustanne tutte 'a storia soje sotte a 'u nome nuève.
'U titele vecchie addevende 'na pàgene de ridirezionamende a 'u titele nuève.
Me raccomande condrolle le redirezionaminde [[Special:DoubleRedirects|a doppie]] o [[Special:BrokenRedirects|scuasciate]].
Tu si responsabbele de assicurarte ca le collagaminde appondene a 'u punde giuste.

Vide ca 'a pàgene '''non''' g'avene spustate ce già stè 'na pàgene cu 'u titele nuève, a meno che non g'è vacande o jè 'nu ridirezionamende e non ge tène 'na storie de cangiaminde.
Quiste signifeche ca jè possibbele renominà 'na pàgene accume se chiamave apprime addò tu è fatte 'n'errore e non g'è possibbele sovrascirevere 'na pàgene esistende.

'''Fà Attenziò!'''
Quiste pò essere 'nu cangiamende inaspettate pe 'na pàgene popolare;
Pe piacere ha essere secure secure de avere capite le conzeguenze apprime de scè nnande.",
'movepagetalktext'             => "'A pagene de le 'ngazzaminde associete avène spustete automaticamende ce però:

* Ste 'na pàgene de 'ngazzaminde chiena sotte 'a vôsce nova, o
* Non ge signe 'u cieck box de sotte.

Jndr'à ste case, 'a pàgene non g'avène spustete e pò t'a cupià a màne 'u codenute sue.",
'movearticle'                  => 'Spuèste:',
'moveuserpage-warning'         => "'''Attenziò:''' Tu stè spuèste 'na pàgene utende. Vide bbuène ca sulamende 'a pàgene avène spustate ma l'utende ''non'' g'avene renomenate.",
'movenologin'                  => 'Non ge sinde colleghete',
'movenologintext'              => "Tu a essere 'n'utende reggistrete e [[Special:UserLogin|colleghete]] pe spustà 'na pàgene.",
'movenotallowed'               => "Tu non ge tìne 'u permesse pe spustà le pàggene.",
'movenotallowedfile'           => "Tu non ge tìne 'u permesse pe spustà le file.",
'cant-move-user-page'          => "Tu non ge tìne 'u permesse pe spustà le pàggene de l'utinde (staccannele cumme sottopàggene).",
'cant-move-to-user-page'       => "Tu non ge tìne 'u permesse pe spustà 'na pàgene a 'na pàgene utende (sulamende pe le sottopàggene de l'utinde).",
'newtitle'                     => "A 'u titele nuève:",
'move-watch'                   => 'condrolle sta pàgene',
'movepagebtn'                  => "Spueste 'a pàgene",
'pagemovedsub'                 => 'Spustaminde eseguite',
'movepage-moved'               => "'''\"\$1\" ha state spustate jndr'à \"\$2\"'''",
'movepage-moved-redirect'      => "'Nu redirezionamende ha state ccrejate.",
'movepage-moved-noredirect'    => "'A ccrejazzione de 'nu redirezionamende ha state soppresse.",
'articleexists'                => "'Na pàgene cu stu nome già esiste, o 'u nome ca tu è scacchiete non g'è valide.
Pe piacere scacchie n'otre nome.",
'cantmove-titleprotected'      => "Tu non ge puè spustà 'na pògene jndr'à sta locazzione purcè 'u titele nuève ha state protette da 'a ccrejazione",
'talkexists'                   => "''''A pàgene ha state spustete, però 'a pàgene de le 'ngazzaminde pò essere ca non g'à state spustete purcè ne ste n'otre già cu 'u titele nuéve.
Videta tu 'a màne a 'ngollà 'a pàgena vecchie cu quedda nova.'''",
'movedto'                      => 'spustete a',
'movetalk'                     => "Spuéste 'a pàgene de le 'ngazzaminde associete",
'move-subpages'                => 'Spuèste le sottopàggene (fine a $1)',
'move-talk-subpages'           => "Spuèste le sotto pàggene de le 'ngazzainde (fine a $1)",
'movepage-page-exists'         => "'A vôsce $1 già esiste e non ge pò essere sovrascritte automaticamende.",
'movepage-page-moved'          => "'A pàgene $1 ha state spustete sus a $2.",
'movepage-page-unmoved'        => "'A pàgene $1 non ge pò essere spustate sus a $2.",
'movepage-max-pages'           => "'U massime de $1 {{PLURAL:$1|pàgene|pàggene}} ha state spustate e non ge se ne ponne spustà cchiù otre in automatiche.",
'1movedto2'                    => "[[$1]] spustete jndr'à [[$2]]",
'1movedto2_redir'              => "spustete [[$1]] jndr'à [[$2]] sus a 'nu ridirezionamende",
'move-redirect-suppressed'     => 'redirezionamende soppresse',
'movelogpage'                  => 'Archivije de le spustaminde',
'movelogpagetext'              => "Sotte stè 'na liste de le pàggene spustete.",
'movesubpage'                  => '{{PLURAL:$1|Sottepàgene|Sottepàggene}}',
'movesubpagetext'              => 'Sta pàgene tène $1 {{PLURAL:$1|sotto pàgene|sotto pàggene}}, vide aqquà sotte.',
'movenosubpage'                => 'Sta pàgene non ge tène sottopàggene.',
'movereason'                   => 'Raggione:',
'revertmove'                   => 'a smerse',
'delete_and_move'              => 'Scangille e spuèste',
'delete_and_move_text'         => '== Scangellazzione richieste ==
\'A pàgene de destinazione "[[:$1]]" esiste già.
Tu à vuè ccu scangille o vuè ccù iacchie \'nu mode pe spustarle?',
'delete_and_move_confirm'      => "Sine, scangille 'a pàggene",
'delete_and_move_reason'       => "'U scangellamende avène fatte pe spustà",
'selfmove'                     => "Le titele sorgende e destinazione sonde le stesse;
non ge se pò movere 'na pàgene sus a sè stesse.",
'immobile-source-namespace'    => 'Non ge pozze spustà le pàggene da \'u namespace "$1"',
'immobile-target-namespace'    => 'Non ge pozze spustà le pàggene jndr\'à \'u namespace "$1"',
'immobile-target-namespace-iw' => "'U collegamende InderUicchi non ge tène 'na destinaziona valide purcè 'a pàgene ha state spustate.",
'immobile-source-page'         => 'Sta pàgene non ge se pò spustà.',
'immobile-target-page'         => 'Non ge puè spustà sus a stu titele de destinazione.',
'imagenocrossnamespace'        => "Non ge pozze spustà 'nu file jndr'à 'nu namespace senza file",
'nonfile-cannot-move-to-file'  => "Non ge pozze spustà 'nu file jndr'à 'nu namespace senza file",
'imagetypemismatch'            => "L'estenziona nove d'u file non ge se accocchie cu 'u tipe sue",
'imageinvalidfilename'         => "'U nome d'u file de destinazzione jè invalide",
'fix-double-redirects'         => "Aggiorne ogne redirezionamende ca apponde a 'u titele origginale",
'move-leave-redirect'          => "Lasse 'nu ridirezionamende rréte",
'protectedpagemovewarning'     => "'''Attenziò:''' Sta pàgene ha state bloccate accussì sulamende l'utinde cu le deritte d'amministratore 'a ponne spustà.
L'urteme archivije de le trasute ha state mise aqquà sotte pe referimende:",
'semiprotectedpagemovewarning' => "'''Vide Bbuène:''' Sta pàgene ha state blocchete accussì sulamende l'utinde reggistrate 'a ponne spustà.
L'urteme archivije de le trasute ha state mise aqquà sotte pe referimende:",
'move-over-sharedrepo'         => "== 'U file esiste ==
[[:$1]] esiste sus a 'n'archivie condivise. Spustanne 'u file sus a stu titele tu vè sovrascrive 'u file condivise.",
'file-exists-sharedrepo'       => "'U nome d'u file ca è scacchiate jè già ausate sus a 'n'archivie condivise.
Pe piacere scacchiene 'notre.",

# Export
'export'            => 'Pàggene esportete',
'exporttext'        => "Tu puè esportà 'u teste e cangià 'a storie de 'na particolare pàgene o 'n'inzieme de pàggene ca stonne jndr'à quacche XML.
Quiste po essere 'mbortate jndr'à 'n'otra uicchi ausanne [[Special:Import|'mborta pàgene]] de MediaUicchi.

Pe esportà pàggene, mitte le titele jndr'à scatele de sotte, 'nu titele pe linèe e scacchie ce tu vuè 'a revisiona corrende o tutte le revisiune, ce le linèe d'a storie d'a pàgene, e 'a revisione corrende cu le 'mbormaziune sus a l'urteme cangiamende.

Cumme urteme case, tu puè pure ausà 'nu collegamende, pe esembie [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] pa pàgene \"[[{{MediaWiki:Mainpage}}]]\".",
'exportcuronly'     => "'Nglude sulamende 'a revisiona corrende, non totte 'a storie",
'exportnohistory'   => "----
'''Vide Bbuène:''' L'esportazione da storia comblete de le pàggene, ausanne sta maschere, ha state disabbilitate pe mutive de prestaziune.",
'export-submit'     => 'Esporte',
'export-addcattext' => "Aggiunge le pàggene da 'a categorije:",
'export-addcat'     => 'Aggiunge',
'export-addnstext'  => "Aggiunge vôsce da 'u namespace:",
'export-addns'      => 'Aggiunge',
'export-download'   => "Reggistre cumme a 'nu file",
'export-templates'  => 'Inglude le template',
'export-pagelinks'  => "Inglude le pàggene collegate 'mbonde a 'na profunnetà de:",

# Namespace 8 related
'allmessages'                   => "Messagge d'u Sisteme",
'allmessagesname'               => 'Nome',
'allmessagesdefault'            => 'Teste de default',
'allmessagescurrent'            => 'Teste corrende',
'allmessagestext'               => "Queste jè 'na liste de tutte le messagge d'u sisteme ca se ponne acchià jndr'à le namespace de MediaUicchi.
Pe piacere vè vide [http://www.mediawiki.org/wiki/Localisation Localizzazione de MediaUicchi] e [http://translatewiki.net translatewiki.net] ce tu vuè ccu condrebbuisce a 'a localizzazione de MediaUicchi.",
'allmessagesnotsupportedDB'     => "Sta pàgene non ge pò essere ausate purcè '''\$wgUseDatabaseMessages''' ha state disabbilitate.",
'allmessages-filter-legend'     => 'Filtre',
'allmessages-filter'            => "Filtre cu 'nu state personalizzate:",
'allmessages-filter-unmodified' => 'Senza cangiamende',
'allmessages-filter-all'        => 'Tutte',
'allmessages-filter-modified'   => 'Cangiate',
'allmessages-prefix'            => 'Filtrate pe prefisse:',
'allmessages-language'          => 'Lènga:',
'allmessages-filter-submit'     => 'Veje',

# Thumbnails
'thumbnail-more'           => 'Allarije',
'filemissing'              => 'File perdute',
'thumbnail_error'          => "Errore ccrejanne l'andeprime picenne: $1",
'djvu_page_error'          => 'Pàgene DjVu fore da le limite',
'djvu_no_xml'              => "Non ge riesche a esaminà l'XML d'u file DjVu",
'thumbnail_invalid_params' => 'Parametre pe le miniature invalide',
'thumbnail_dest_directory' => "Non ge pozze ccrejà 'a cartella de destinazione",
'thumbnail_image-type'     => 'Tipe de immaggine non supportate',
'thumbnail_gd-library'     => "Configurazione d'a libbrerie GD ingomblete: funziona perse $1",
'thumbnail_image-missing'  => "'U file pare ca non ge se iacchie: $1",

# Special:Import
'import'                     => "Pàggene 'mbortete",
'importinterwiki'            => "'Mborte da Transuicchi",
'import-interwiki-text'      => "Schacchie 'na Uicchi e 'nu titele de pàgene da 'mbortà.
Le date d'a revisione e 'u nome de le cangiature avènene preservate.
Tutte le aziune de 'mbortaziune 'mbrà le Uicchi sonde reggistrate jndr'à l'[[Special:Log/import|archivije de le 'mbortaziune]].",
'import-interwiki-source'    => 'Sorgende Uicchi/vosce:',
'import-interwiki-history'   => "Copie tutte 'a sotrie de le versiune de sta pàgene",
'import-interwiki-templates' => 'Inglude tutte le template',
'import-interwiki-submit'    => "'Mborte",
'import-interwiki-namespace' => 'Namespace de destinazione:',
'import-upload-filename'     => "Nome d'u file:",
'import-comment'             => 'Commende:',
'importtext'                 => "Pe piacere esporte 'u file da 'a Uicchi sorgende ausanne l'[[Special:Export|utilità de esportazione]].
Reggistrele sus a 'u combiuter tue e carechele aqquà.",
'importstart'                => "'Mbortazione de le pàggene...",
'import-revision-count'      => '$1 {{PLURAL:$1|revisione|revisiune}}',
'importnopages'              => "Nisciuna pàgene da 'mbortà.",
'imported-log-entries'       => "'Mbortate $1 {{PLURAL:$1|vôsce de l'archivije|vôsce de l'archivije}}.",
'importfailed'               => "'Mbortaziona fallite: <nowiki>$1</nowiki>",
'importunknownsource'        => "Tipe de sorgende de 'mbortaziona scanusciute",
'importcantopen'             => "Non ge puè aprè 'u file 'mbortete",
'importbadinterwiki'         => 'collegamende interuicchi errete',
'importnotext'               => 'Vacande o senza teste',
'importsuccess'              => "'Mbortaziona spicciete!",
'importhistoryconflict'      => "Esiste 'nu conflitte 'brà revisiune d'u cunde d'a pàgene (ponne avè 'mbortate sta pàgene apprime)",
'importnosources'            => "Nisciuna 'mbortazione 'mbrà Uicchi ha state definite e le carecaminde dirette d'a storie onne state disabbilitate.",
'importnofile'               => "Nisciune file de 'mbortazione ha state carecate.",
'importuploaderrorsize'      => "'U carecamende d'u file 'mbortate ha fallite.
'U file ète troppe granne respette a 'a dimenziona massime de carecamende.",
'importuploaderrorpartial'   => "'U carecamende d'u file 'mbortate ha fallite.
'U file ha state carecate sulamende in parte.",
'importuploaderrortemp'      => "'U carecamende d'u file 'mbortate ha fallite.
'A cartelle temboranèe non ge se iacchie.",
'import-parse-failure'       => "Analizzatore de 'mbortaziune de l'XML ha fallite",
'import-noarticle'           => "Nisciuna pàgene 'mbortete!",
'import-nonewrevisions'      => "Tutte le revisiune avèrene già state 'mbortate.",
'xml-error-string'           => "$1 a 'a linea $2, colonne $3 (byte $4): $5",
'import-upload'              => 'Careche le date in XML',
'import-token-mismatch'      => "S'à perse 'a sessione de le date. Pruève 'n'otra vote.",
'import-invalid-interwiki'   => "Non ge pozze 'mbortà da 'a Uicchi specificate.",

# Import log
'importlogpage'                    => "Archivie de le 'mbortaziune",
'importlogpagetext'                => "'Mbortaziune amministrative de pàggene cu 'a storie de le cangiaminde da otre Uicchi.",
'import-logentry-upload'           => "'mbortete [[$1]] da 'u fail carechete",
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|revisione|revisiune}}',
'import-logentry-interwiki'        => 'transuicchied $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|revisione|revisiune}} da $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => "'A pàgene utende meje",
'tooltip-pt-anonuserpage'         => "'A pàgene utende pe l'IP ca tu ste cange cumme",
'tooltip-pt-mytalk'               => "'Ngazzaminde mie",
'tooltip-pt-anontalk'             => "'Ngazzamende sus a le cangiaminde da stu indirizze IP",
'tooltip-pt-preferences'          => 'Me piece accussì',
'tooltip-pt-watchlist'            => "'A liste de le pàggene ca ste condrolle pe le camgiaminde",
'tooltip-pt-mycontris'            => 'Liste de le condrebbute mie',
'tooltip-pt-login'                => "Tu si 'ncoraggiete a cullegarte, jidde non g'è 'n'obblighe.",
'tooltip-pt-anonlogin'            => "Tu si 'ncoraggiete a cullegarte; però non g'è 'n'obblighe.",
'tooltip-pt-logout'               => 'Isse',
'tooltip-ca-talk'                 => "'Ngazzaminde sus 'a pàgene de le condenute",
'tooltip-ca-edit'                 => "Tu puè cangià sta pàgene.
Pe piacere ause 'u buttone de l'andeprime apprime de salvà.",
'tooltip-ca-addsection'           => "Fà accumenzà 'na seziona nove",
'tooltip-ca-viewsource'           => "Sta pàgene jè prutette.
Puè vedè sulamende 'u sorgende.",
'tooltip-ca-history'              => 'Versiune passete de sta pàgene',
'tooltip-ca-protect'              => 'Prutegge sta pàgene',
'tooltip-ca-unprotect'            => 'Sprotegge sta pàgene',
'tooltip-ca-delete'               => 'Scangille sta pàgene',
'tooltip-ca-undelete'             => 'Repristine le cangiaminde fatte a sta pàgene apprime ca evenève scangellate',
'tooltip-ca-move'                 => 'Spuèste sta pàgene',
'tooltip-ca-watch'                => "Aggiunge sta pàgene jndr'à le pàggene condrollete",
'tooltip-ca-unwatch'              => 'No condrollà cchiù sta pàgene',
'tooltip-search'                  => 'Cirche {{SITENAME}}',
'tooltip-search-go'               => "Veje 'a pàgene cu 'u nome esatte ce quiste esiste",
'tooltip-search-fulltext'         => 'Cirche le pàggene cu stu teste',
'tooltip-p-logo'                  => 'Pàgene Prengepàle',
'tooltip-n-mainpage'              => "Vè vide 'a pàgene prengepàle",
'tooltip-n-mainpage-description'  => "Visite 'a pàgena prengepàle",
'tooltip-n-portal'                => "Parkanne d'u proggette, ce puà fà, addò puè acchjà le cose.",
'tooltip-n-currentevents'         => "Iacchje le 'mbormaziune sus a 'u sfonde de 'u fatte corrende",
'tooltip-n-recentchanges'         => "'A liste de le cangiaminde recende jndr'à uicchi.",
'tooltip-n-randompage'            => "Careche 'na pàgene a uecchje",
'tooltip-n-help'                  => "'Nu poste da scuprì",
'tooltip-t-whatlinkshere'         => 'Liste de tutte le pàggene de Uicchi ca appondene aqquà',
'tooltip-t-recentchangeslinked'   => "Cangiaminde recende jndr'à le pàggene appundete da sta pàgene",
'tooltip-feed-rss'                => 'RSS feed pe sta pàgene',
'tooltip-feed-atom'               => 'Atom feed pe sta pàgene',
'tooltip-t-contributions'         => "Vide 'a liste de le condrebbute de quiste utende",
'tooltip-t-emailuser'             => "Manne n'e-mail a stu utende",
'tooltip-t-upload'                => 'Careche le file',
'tooltip-t-specialpages'          => 'Liste de tutte le pàggene speciale',
'tooltip-t-print'                 => 'Versiona stambabele de sta pàgene',
'tooltip-t-permalink'             => "Collegamende permanende a sta versione d'a pàgene",
'tooltip-ca-nstab-main'           => "Vide 'a pàgene cu le condenute",
'tooltip-ca-nstab-user'           => "Vide 'a pàgene de l'utende",
'tooltip-ca-nstab-media'          => "Vide 'a pàgene de le media",
'tooltip-ca-nstab-special'        => "Queste jè 'na pàgena speciale, ca tu non ge puè cangià",
'tooltip-ca-nstab-project'        => "Vide 'a pàgene d'u proggette",
'tooltip-ca-nstab-image'          => "Vide 'a pàgene d'u fail",
'tooltip-ca-nstab-mediawiki'      => "Vide le messàgge d'u sisteme",
'tooltip-ca-nstab-template'       => "Fà vedè 'u template",
'tooltip-ca-nstab-help'           => "Vide 'a pàgene d'ajute",
'tooltip-ca-nstab-category'       => 'Vide a pàgene de le categorije',
'tooltip-minoredit'               => "Signe cumme a 'nu cangiaminde stuèdeche",
'tooltip-save'                    => 'Reggistre le cangiaminde ca è fatte',
'tooltip-preview'                 => "Fà l'andeprime de le cangiaminde ca ste face. Pe piacere falle prima cu reggistre 'a vôsce!",
'tooltip-diff'                    => "Fà vedè ce cangiaminde e fatte a 'u teste.",
'tooltip-compareselectedversions' => "Vide le differenze 'mbrà le doje versiune selezionete de sta pàgene.",
'tooltip-watch'                   => "Mitte sta pàgene jndr'à liste de le pàggene condrollete",
'tooltip-recreate'                => "Reccreje 'a pàgene ca avère state scangellate",
'tooltip-upload'                  => "Accumenze 'u carecamende",
'tooltip-rollback'                => '"Ripristine" annulle le cangiaminde a sta pàgene de l\'urteme condrebbutore cu \'nu cazzamende',
'tooltip-undo'                    => "\"Annulle\" annulle stu cangiamende e iapre 'u form de le cangiaminde facenne vedè l'andeprime.
Permette de aggiungere 'nu mutive jndr'à 'u riepileghe",
'tooltip-preferences-save'        => 'Reggistre le preferenze',
'tooltip-summary'                 => "Mitte 'nu riepileghe piccinne",

# Metadata
'nodublincore'      => "'U metadata ''Dublin Core RDF'' ète disabbilitate pe stu server.",
'nocreativecommons' => "'U metadata ''Creative Commons RDF'' ète disabilitate pe stu server.",
'notacceptable'     => "'U server Uicchi non ge pò vedè le date jndr'à 'u formate ca 'u cliende tue pò leggere.",

# Attribution
'anonymous'        => '{{PLURAL:$1|utende|utinde}} anonime de {{SITENAME}}',
'siteuser'         => 'Utende de {{SITENAME}} $1',
'anonuser'         => 'Utende anonime de {{SITENAME}} $1',
'lastmodifiedatby' => "Sta pàgene ha state cangiate l'urtema vote a le $2, d'u $1 da $3.",
'othercontribs'    => "Basete sus a 'na fatije de $1.",
'others'           => 'otre',
'siteusers'        => '{{PLURAL:$2|utende|utinde}} de {{SITENAME}} $1',
'anonusers'        => '{{PLURAL:$2|utende|utinde}} anonime de {{SITENAME}} $1',
'creditspage'      => 'Pàgene de le crediti',
'nocredits'        => "Non ge stonne 'mbormaziune sus a le credite disponibbele pe sta pàgene.",

# Spam protection
'spamprotectiontitle' => "Filtre de protezione da 'u spam",
'spamprotectiontext'  => "'A pàgene ca tu vuè reggistrà ha state bloccate da 'u filtre anti spam.
Stu fatte ha state causate da 'nu collegamende a 'nu site esterne ca appartene a 'a lista gnore.",
'spamprotectionmatch' => "'U test seguende ha state signalate da 'u nostre filtre anti spam: $1",
'spambot_username'    => "Sdevacatore d'u spam de MediaUicchi",
'spam_reverting'      => "Turnanne a l'urtema revisione no ge condiene collegaminde a $1",
'spam_blanking'       => 'Tutte le revisiune condènene collegaminde a $1, vacande',

# Info page
'infosubtitle'   => "'Mbormazione pe pàgene",
'numedits'       => 'Numere de cangiaminde (pàgene): $1',
'numtalkedits'   => "Numere de cangiaminde (pàgene de le 'ngazzaminde): $1",
'numwatchers'    => 'Numere de visitature: $1',
'numauthors'     => 'Numere de autore distinde (vôsce): $1',
'numtalkauthors' => "Numere de autore distinde (pàgene de le 'ngazzaminde): $1",

# Math options
'mw_math_png'    => "Fà sembre 'u render de le PNG",
'mw_math_simple' => 'HTML ce jè avveramende facile oppure PNG',
'mw_math_html'   => 'HTML ce jè possibbile oppure PNG',
'mw_math_source' => "Lassele cumme a 'nu TeX (pe le browser de teste)",
'mw_math_modern' => 'Raccomandate pe le browser cchiù nuève',
'mw_math_mathml' => 'MathML ce possibbile (sperimendele)',

# Math errors
'math_failure'          => 'Analisi fallite',
'math_unknown_error'    => 'errore scanusciute',
'math_unknown_function' => 'funziona scanusciute',
'math_lexing_error'     => 'errore de lessiche',
'math_syntax_error'     => 'errore de sintassi',
'math_image_error'      => "'A conversione d'u PNG ha fallite;
condrolle ce l'installazione de latex, dvips, gs e convertitore jè corrette",
'math_bad_tmpdir'       => "Non ge puè scrivere o ccrejà 'na cartelle temboranea de math",
'math_bad_output'       => "Non ge puè scrivere o ccrejà 'na cartelle de destinazzione de math",
'math_notexvc'          => 'texvc eseguibbele perdute;
pe piacere vide math/README pe configurà.',

# Patrolling
'markaspatrolleddiff'                 => 'Signe cumme condrollate',
'markaspatrolledtext'                 => 'Signe sta pàgene cumme condrollate',
'markedaspatrolled'                   => 'Signate cumme condrollate',
'markedaspatrolledtext'               => "'A revisiona scacchiate de [[:$1]] ha state signate cumme condrollate.",
'rcpatroldisabled'                    => "Condrolle de l'Urteme Cangiaminde disabbilitate",
'rcpatroldisabledtext'                => "'A funzione Condrolle de l'Urteme Cangiaminde mò ète disabbilitate.",
'markedaspatrollederror'              => 'Non ge se pò signà cumme condrollate',
'markedaspatrollederrortext'          => "Tu è abbesogne de specificà 'na revisione da signà cumme condrollate.",
'markedaspatrollederror-noautopatrol' => 'Tu non ge puè signà le cangiaminde tue cumme condrollate.',

# Patrol log
'patrol-log-page'      => 'Archivije de le condrolle',
'patrol-log-header'    => "Quiste è l'archivije de le revisiune condrollate.",
'patrol-log-line'      => 'signate $1 de $2 condrollate $3',
'patrol-log-auto'      => '(automatiche)',
'patrol-log-diff'      => 'revisione $1',
'log-show-hide-patrol' => '$1 archivije de le condrolle',

# Image deletion
'deletedrevision'                 => 'Vecchia revisione scangellete $1',
'filedeleteerror-short'           => "Errore mentre stè scangelleve 'u file: $1",
'filedeleteerror-long'            => "Quanne ste scangelleve 'u file s'onne presendede ste errore:

$1",
'filedelete-missing'              => '\'U file "$1" non ge pò essere scangellete purcè non g\'esiste!',
'filedelete-old-unregistered'     => "'A revisione d'u file specificate \"\$1\" non ge stè jndr'à 'u database.",
'filedelete-current-unregistered' => '\'U file specificate "$1" non ge stè jndr\'à \'u database.',
'filedelete-archive-read-only'    => '\'A cartelle de archiviazione "$1" non ge pò essere scritte da \'u webserver.',

# Browsing diffs
'previousdiff' => '← Cangiaminde vecchije',
'nextdiff'     => 'Cangiaminde cchiù nuève →',

# Media information
'mediawarning'         => "'''Attenziò''': Stu file pò condenè codece viziuse.
Ce l'esegue sus a 'u sisteme tue pò essere ca se combromette.",
'imagemaxsize'         => "Limite d'a dimenzione e l'immaggine:<br />''(pe le pàggene de descrizione d'u file)''",
'thumbsize'            => "Dimenziona d'a miniature:",
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|pàgene|pàggene}}',
'file-info'            => "(dimenzione d'u fail: $1, tipe de MIME: $2)",
'file-info-size'       => "($1 × $2 pixel, dimenzione d'u fail: $3, tipe de MIME: $4)",
'file-nohires'         => "<small>Manghe 'a risoluzione ierta.</small>",
'svg-long-desc'        => "(Fail SVG, nominalmende sonde $1 × $2 pixel, dimenzione d'u fail: $3)",
'show-big-image'       => 'Risoluzione chiena chiena',
'show-big-image-thumb' => '<small>Dimenziune de sta andeprime: $1 × $2 pixels</small>',
'file-info-gif-looped' => 'infinite',
'file-info-gif-frames' => '$1 {{PLURAL:$1|frame|frame}}',
'file-info-png-looped' => 'infinite',
'file-info-png-repeat' => 'eseguite $1 {{PLURAL:$1|vote|vote}}',
'file-info-png-frames' => '$1 {{PLURAL:$1|frame|frame}}',

# Special:NewFiles
'newimages'             => 'Gallerie de le fail nuève',
'imagelisttext'         => "Sotte stè 'na liste de '''$1''' {{PLURAL:$1|file|file}} arrénghete $2.",
'newimages-summary'     => "Sta pàgena speciale face vedè l'urteme file carecate.",
'newimages-legend'      => 'Filtre',
'newimages-label'       => "Nome d'u fail (o 'nu stuezze de jidde):",
'showhidebots'          => '($1 bot)',
'noimages'              => 'Non ge stè ninde da vedè.',
'ilsubmit'              => 'Cirche',
'bydate'                => 'pe date',
'sp-newimages-showfrom' => 'Fa vedè le file nuève partenne da $2, $1',

# Bad image list
'bad_image_list' => "'U formete jè 'u seguende:

Sulamende le eleminde d'a liste (le linee ca accumènzene cu *) sonde considerete.
'U prime collegamende sus a 'na linea addà essere 'nu collegamende a 'nu fail cattive.
Tutte le sottosequenze ca appondene a stessa linea sonde considerete eccezziune, p.e. le pàggene addò 'u fail pò sce ''inlain''.",

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => "Quiste fail condene 'mbormaziune addizionele, probabilmende aggiunde da 'a machena digitele o 'nu scanner ausete pe ccrejà o digitalizà.
Ce 'u fail ha state cangete da 'u state origginale sue, certe dettaglie pò essere ca no se vèdene jndr'à 'u fail cangete.",
'metadata-expand'   => 'Fa vedè le dettaglie estese',
'metadata-collapse' => 'Scunne le dettaglie estese',
'metadata-fields'   => "EXIF le cambe de metadata elenghete jndr'à quiste messagge verranne mise sus a 'na pàgene de immaggine quanne 'a taggella de metadata jè collassete.
Otre avènene scunnute pe defolt.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength",

# EXIF tags
'exif-imagewidth'                  => 'Larghezze',
'exif-imagelength'                 => 'Altezze',
'exif-bitspersample'               => 'Bit pe combonende',
'exif-compression'                 => 'Scheme de combressione',
'exif-photometricinterpretation'   => 'Combosizione de le pixel',
'exif-orientation'                 => 'Oriendamende',
'exif-samplesperpixel'             => 'Numere de combonende',
'exif-planarconfiguration'         => 'Arrengamende de date',
'exif-ycbcrsubsampling'            => 'Percenduale de sotte cambione de Y a C',
'exif-ycbcrpositioning'            => 'posizzionamende de Y e C',
'exif-xresolution'                 => 'Resoluzione orizzondale',
'exif-yresolution'                 => 'Resoluzione verticale',
'exif-resolutionunit'              => 'Resoluzione de aunità de X e Y',
'exif-stripoffsets'                => "Locazione d'u date immaggine",
'exif-rowsperstrip'                => 'Numere de righe pe strisce',
'exif-stripbytecounts'             => 'Byte pe strisce combresse',
'exif-jpeginterchangeformat'       => 'Distanze da JPEG SOI',
'exif-jpeginterchangeformatlength' => "Byte d'u date d'u JPEG",
'exif-transferfunction'            => 'Funzione de trasferimende',
'exif-whitepoint'                  => "Cromaticetà d'u punde vianghe",
'exif-primarychromaticities'       => 'Cromaticetà de le primarie',
'exif-ycbcrcoefficients'           => "Spazie d'u culore pe le coefficiende d'a matrice de trasformazione",
'exif-referenceblackwhite'         => 'Coppie de vianghe e gnure pe le valore de riferimende',
'exif-datetime'                    => "Cangiamende d'a date e de l'orarie d'u file",
'exif-imagedescription'            => "Titele de l'immaggine",
'exif-make'                        => "Costruttore d'a machene",
'exif-model'                       => "Modelle d'a machene",
'exif-software'                    => 'Softuer ausete',
'exif-artist'                      => 'Autore',
'exif-copyright'                   => "Titolere d'u Copyright",
'exif-exifversion'                 => 'Versione de Exif',
'exif-flashpixversion'             => 'Versione Flashpix supportate',
'exif-colorspace'                  => "Spazie d'u culore",
'exif-componentsconfiguration'     => 'Significate de ogne combonende',
'exif-compressedbitsperpixel'      => "Mode de combressione de l'immaggine",
'exif-pixelydimension'             => "Larghezze de l'immaggine valide",
'exif-pixelxdimension'             => "Altezze de l'immaggine valide",
'exif-makernote'                   => "Note d'u ccreatore",
'exif-usercomment'                 => "Commende de l'utende",
'exif-relatedsoundfile'            => 'File audio collegate',
'exif-datetimeoriginal'            => "Date e ore d'a generazione de le date",
'exif-datetimedigitized'           => "Date e ore d'a digitalizzazzione",
'exif-subsectime'                  => 'DateTime cendesime',
'exif-subsectimeoriginal'          => 'DateTimeOriginal cendesime',
'exif-subsectimedigitized'         => 'DateTimeDigitized cendesime',
'exif-exposuretime'                => "Timbe d'esposizione",
'exif-exposuretime-format'         => '$1 sec ($2)',
'exif-fnumber'                     => 'Numere de F',
'exif-exposureprogram'             => "Programme d'esposizione",
'exif-spectralsensitivity'         => 'Senzitività spettrale',
'exif-isospeedratings'             => 'Senzibbilità ISO',
'exif-oecf'                        => 'Fattore de conversione optoelettroneche',
'exif-shutterspeedvalue'           => 'Velocità de esposizione',
'exif-aperturevalue'               => 'Aperture',
'exif-brightnessvalue'             => 'Lumenosità',
'exif-exposurebiasvalue'           => "Correzzione de l'esposizione",
'exif-maxaperturevalue'            => 'Aperture massime',
'exif-subjectdistance'             => "Distanze d'u soggette",
'exif-meteringmode'                => 'Metode de mesurazione',
'exif-lightsource'                 => 'Sorgende lumenose',
'exif-flash'                       => 'Flash',
'exif-focallength'                 => "Distanze focale de l'obbiettive",
'exif-subjectarea'                 => "Area d'u soggette",
'exif-flashenergy'                 => "Putenze d'u flash",
'exif-spatialfrequencyresponse'    => "Resposte jndr'à frequenze spaziale",
'exif-focalplanexresolution'       => "Resoluzione X sus a 'u piane focale",
'exif-focalplaneyresolution'       => "Resoluzione Y sus a 'u piane focale",
'exif-focalplaneresolutionunit'    => "Aunità de resoluzione d'u piane focale",
'exif-subjectlocation'             => "Posizione d'u soggette",
'exif-exposureindex'               => "Indice d'esposizione",
'exif-sensingmethod'               => 'Metode de rivelazzione',
'exif-filesource'                  => "Sorgende d'u file",
'exif-scenetype'                   => 'Tipe de scene',
'exif-cfapattern'                  => 'Cambione CFA',
'exif-customrendered'              => "Elabborazzione de l'immaggine personalizzate",
'exif-exposuremode'                => "Mode d'esposizione",
'exif-whitebalance'                => "Ualanzamende d'u vianghe",
'exif-digitalzoomratio'            => "Rapporte d'u zoom diggitale",
'exif-focallengthin35mmfilm'       => 'Lunghezze focale uguale a 35 mm',
'exif-scenecapturetype'            => 'Tipe de acquisizzione',
'exif-gaincontrol'                 => "Condrolle d'a scene",
'exif-contrast'                    => 'Condraste',
'exif-saturation'                  => 'Saturazione',
'exif-sharpness'                   => 'Nitidezze',
'exif-devicesettingdescription'    => "Descrizione de le 'mbostaziune d'u dispositive",
'exif-subjectdistancerange'        => "Scale de distanze d'u soggette",
'exif-imageuniqueid'               => "ID de l'immaggine univoche",
'exif-gpsversionid'                => 'Versione de le tag GPS',
'exif-gpslatituderef'              => 'Latitudine nord o sud',
'exif-gpslatitude'                 => 'Latitudene',
'exif-gpslongituderef'             => 'Longitudine est o ovest',
'exif-gpslongitude'                => 'Longitudene',
'exif-gpsaltituderef'              => "Riferimende de l'altitudine",
'exif-gpsaltitude'                 => 'Altitudene',
'exif-gpstimestamp'                => "orarije d'u GPS (relogge atomiche)",
'exif-gpssatellites'               => 'Satellite ausate pe le mesure',
'exif-gpsstatus'                   => "State d'u ricevitore",
'exif-gpsmeasuremode'              => 'Mode de mesurazione',
'exif-gpsdop'                      => 'Precisione de mesurazione',
'exif-gpsspeedref'                 => "Aunità de mesure d'a velocità",
'exif-gpsspeed'                    => "Velocità d'u ricevitore GPS",
'exif-gpstrackref'                 => 'Riferimende pa direzzione de movimende',
'exif-gpstrack'                    => 'Direzzione de movimende',
'exif-gpsimgdirectionref'          => "Riferimende pa direzzione de l'immaggine",
'exif-gpsimgdirection'             => "Direzione de l'immaggine",
'exif-gpsmapdatum'                 => 'Rilevamende geodetiche ausate',
'exif-gpsdestlatituderef'          => "Riferimende pa latitudine d'a destinazione",
'exif-gpsdestlatitude'             => "Latitudine d'a destinazione",
'exif-gpsdestlongituderef'         => "Riferimende pa longitudine d'a destinazione",
'exif-gpsdestlongitude'            => "Longitudine d'a destinazione",
'exif-gpsdestbearingref'           => "Riferimende pa direzzione d'a destinazione",
'exif-gpsdestbearing'              => "Direzzione d'a destinazione",
'exif-gpsdestdistanceref'          => "Riferimende pa distanze d'a destinazione",
'exif-gpsdestdistance'             => "Distanze d'a destinazione",
'exif-gpsprocessingmethod'         => "Nome d'u metode de elabborazzione d'u GPS",
'exif-gpsareainformation'          => "Nome de l'area d'u GPS",
'exif-gpsdatestamp'                => "Date d'u GPS",
'exif-gpsdifferential'             => "Correzzione differenziale d'u GPS",

# EXIF attributes
'exif-compression-1' => 'No combresse',

'exif-unknowndate' => 'Data scanusciute',

'exif-orientation-1' => 'Normale',
'exif-orientation-2' => 'Revultate orizzondalmende',
'exif-orientation-3' => 'Ruete de 180°',
'exif-orientation-4' => 'Revultate verticalmende',
'exif-orientation-5' => 'Ruotate de 90° in senze andiorarie e revultate verticalmende',
'exif-orientation-6' => 'Ruotate de 90° in senze orarie',
'exif-orientation-7' => 'Ruotate de 90° in senze orarie e revultate verticalmende',
'exif-orientation-8' => 'Ruotate de 90° in senze andiorarie',

'exif-planarconfiguration-1' => 'formate a blocche',
'exif-planarconfiguration-2' => 'formate lineare',

'exif-componentsconfiguration-0' => "non g'esiste",

'exif-exposureprogram-0' => 'Non definite',
'exif-exposureprogram-1' => 'Manuale',
'exif-exposureprogram-2' => 'Programma normale',
'exif-exposureprogram-3' => "Priorità d'aperture",
'exif-exposureprogram-4' => "Priorità d'esposizione",
'exif-exposureprogram-5' => "Programme ccreative (basate sus a 'a profonnetà d'u cambe)",
'exif-exposureprogram-6' => "Programme d'azione (basate sus a velocità de riprese)",
'exif-exposureprogram-7' => "Ritratte (soggette vicine cu 'u sfonde fore da 'u fuèche)",
'exif-exposureprogram-8' => "Panorame (soggette lundane cu 'u sfonde jndr'à 'u fuèche)",

'exif-subjectdistance-value' => '$1 metre',

'exif-meteringmode-0'   => 'Scanusciute',
'exif-meteringmode-1'   => 'Medie',
'exif-meteringmode-2'   => 'Medie Pesate Cendrate',
'exif-meteringmode-3'   => 'Spot',
'exif-meteringmode-4'   => 'Multi Spot',
'exif-meteringmode-5'   => 'Pattern',
'exif-meteringmode-6'   => 'Parziele',
'exif-meteringmode-255' => 'Otre',

'exif-lightsource-0'   => 'Scanusciute',
'exif-lightsource-1'   => "Luce d'u giurne",
'exif-lightsource-2'   => 'Florescende',
'exif-lightsource-3'   => 'Tungstene (luce caveda caveda)',
'exif-lightsource-4'   => 'Flash',
'exif-lightsource-9'   => 'Timbe belle',
'exif-lightsource-10'  => 'Timbe nuvolose',
'exif-lightsource-11'  => 'In ombre',
'exif-lightsource-12'  => "Florescenza a 'a luce d'u sciurne (D 5700 - 7100K)",
'exif-lightsource-13'  => "Florescenza a 'u vianghe d'u sciurne (N 4600 - 5400K)",
'exif-lightsource-14'  => "Florescenza a 'u vianghe cavede (W 3900 - 4500K)",
'exif-lightsource-15'  => 'Florescenza vianghe (WW 3200 - 3700K)',
'exif-lightsource-17'  => 'Luce standàrd A',
'exif-lightsource-18'  => 'Luce standàrd B',
'exif-lightsource-19'  => 'Luce standàrd C',
'exif-lightsource-24'  => 'ISO studio tungstene',
'exif-lightsource-255' => 'Otra sogende lumenose',

# Flash modes
'exif-flash-fired-0'    => "'U flash non g'à scattate",
'exif-flash-fired-1'    => 'Flash scattate',
'exif-flash-return-0'   => "nisciuna funzione ca retorne 'a luce stroboscopeca",
'exif-flash-return-2'   => 'luce stroboscopeca de retorne non individuate',
'exif-flash-return-3'   => 'luce stroboscopeca de retorne individuate',
'exif-flash-mode-1'     => 'attenziò flash forzate',
'exif-flash-mode-2'     => "luamende d'u flah forzate",
'exif-flash-mode-3'     => 'mode automateche',
'exif-flash-function-1' => 'Nisciuna funzione pe flash',
'exif-flash-redeye-1'   => "mode de reduzione pe l'uecchie russe",

'exif-focalplaneresolutionunit-2' => 'pollece (inches)',

'exif-sensingmethod-1' => 'Indefinite',
'exif-sensingmethod-2' => "Senzore d'area culore a 1 chip",
'exif-sensingmethod-3' => "Senzore d'area culore a 2 chip",
'exif-sensingmethod-4' => "Senzore d'area culore a 3 chip",
'exif-sensingmethod-5' => "Senzore d'area culore sequenziale",
'exif-sensingmethod-7' => 'Senzore trilinèere',
'exif-sensingmethod-8' => 'Senzore linèere de culore sequenziale',

'exif-scenetype-1' => 'Fotografije dirette',

'exif-customrendered-0' => 'Processe normele',
'exif-customrendered-1' => 'Processe personalizzete',

'exif-exposuremode-0' => 'Auto esposizione',
'exif-exposuremode-1' => 'Esposizione a mane',
'exif-exposuremode-2' => 'Bracketing automateche',

'exif-whitebalance-0' => "Ualanzamende d'u vianghe autometeche",
'exif-whitebalance-1' => "Ualanzamende d'u vianghe a mane",

'exif-scenecapturetype-0' => 'Stàndàrd',
'exif-scenecapturetype-1' => 'Orizzondele',
'exif-scenecapturetype-2' => 'Vertichele',
'exif-scenecapturetype-3' => 'Scene de notte',

'exif-gaincontrol-0' => 'Ninde',
'exif-gaincontrol-1' => "'Mbortanze pu uadagne vasce",
'exif-gaincontrol-2' => "'Mbortanze pu uadagne ierte",
'exif-gaincontrol-3' => "Mene 'mbortanze pu uadagne vasce",
'exif-gaincontrol-4' => "Mene 'mbortanze pu uadagne ierte",

'exif-contrast-0' => 'Normale',
'exif-contrast-1' => 'Muedde',
'exif-contrast-2' => 'Tuéste',

'exif-saturation-0' => 'Normale',
'exif-saturation-1' => 'Saturaziona vasce',
'exif-saturation-2' => 'Saturaziona ierte',

'exif-sharpness-0' => 'Normale',
'exif-sharpness-1' => 'Morbide',
'exif-sharpness-2' => 'Tuèste',

'exif-subjectdistancerange-0' => 'Scanusciute',
'exif-subjectdistancerange-1' => 'Macro',
'exif-subjectdistancerange-2' => "Chiude 'a viste",
'exif-subjectdistancerange-3' => "Distanzie 'a viste",

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Latitudine nord',
'exif-gpslatitude-s' => 'Latitudine sud',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Longitudine est',
'exif-gpslongitude-w' => 'Longitudine ovest',

'exif-gpsstatus-a' => 'Mesurazione in corse',
'exif-gpsstatus-v' => 'Mesurazione inderoperabbele',

'exif-gpsmeasuremode-2' => 'mesurazzione a doje dimenziune',
'exif-gpsmeasuremode-3' => 'mesurazzione a ttre dimenziune',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => "Chilometre a l'ore",
'exif-gpsspeed-m' => 'Miglie pe ore',
'exif-gpsspeed-n' => 'Knots',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Direziona vere',
'exif-gpsdirection-m' => 'Direziona magnetiche',

# External editor support
'edit-externally'      => "Cange stu fail usanne n'applicazione esterne",
'edit-externally-help' => "(Vide le [http://www.mediawiki.org/wiki/Manual:External_editors 'struzione de configurazione] pe avèje cchiù dettaglie)",

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'tutte',
'imagelistall'     => 'tutte',
'watchlistall2'    => 'tutte',
'namespacesall'    => 'tutte',
'monthsall'        => 'tutte',
'limitall'         => 'tutte',

# E-mail address confirmation
'confirmemail'              => "Conferme l'indirizze e-mail",
'confirmemail_noemail'      => "Tu non ge tine 'n'indirizze e-mail valide configurate sus a le [[Special:Preferences|preferenze tue]].",
'confirmemail_text'         => "{{SITENAME}} richiede ca tu ha validà l'indirizze email tue apprime de ausà 'a funzione de l'email.
Cazze 'u buttone de sotte pe mannà 'na email de conferme a l'indirizze tue.
L'email ca t'arrive tène 'u collegamende cu 'u codece;
careche 'u collegamende jndr'à 'u browser tue pe confermà ca l'indirizze email tue è valide.",
'confirmemail_pending'      => "'Nu codece de conferme ha state già mannate a l'email toje;
Ce tu recendemende è ccrejate 'nu cunde utende, tu puè aspettà quacche minute ca jidde arrive e pò puè pruvà a fà 'n'otra richieste pe 'nu codece nuève.",
'confirmemail_send'         => "Manne 'nu codece de conferme",
'confirmemail_sent'         => 'E-mail de conferme mannete.',
'confirmemail_oncreate'     => "'Nu codece de conferme ha state mannate a l'indirizze e-mail tue.
Stu codece non g'è richieste pe collegarte, ma tu n'è abbesogne de averle apprime ca tu ause quacche cose ca se base sus a l'use de le e-mail sus a Uicchi.",
'confirmemail_sendfailed'   => "{{SITENAME}} non ge pò mannà l'email toje de conferme.
Pe piacere condrolle l'indirizze email ce tène carattere invalide.

Destinatarie returnate: $1",
'confirmemail_invalid'      => "Codece de conferme invalide.
Pò essere ca 'u codece ha scadute.",
'confirmemail_needlogin'    => "A confermà $1 l'indirizze email ca è mise.",
'confirmemail_success'      => "L'indirizze e-mail tue ha state confermate.
Tu, mò te puè [[Special:UserLogin|collegà]] e te puè devertì sus 'a Uicchipèdie.",
'confirmemail_loggedin'     => "L'indirizze e-mail tue ha state confermate.",
'confirmemail_error'        => "Quacchedune ha sbagliate reggistranne 'a conferma toje.",
'confirmemail_subject'      => 'Indirizze email de conferme pe {{SITENAME}}',
'confirmemail_body'         => "Quacchedune, pò essere tu, fa l'indirizze IP \$1,
ha reggistrate 'nu cunde utende \"\$2\" cu st'indirizze email sus a {{SITENAME}}.

Pe confermà ca stu cunde ète avveramende 'u tue e pe attivà 'a funzione email de {{SITENAME}}, iapre stu collegamende jndr'à 'u borwser tue:

\$3

Ce tu *NON* g'è reggistrate 'u cunde utende, segue stu collegamende pe scangellà l'indirizze email de conferme:

\$5

Stu codece de conferme more 'u \$4.",
'confirmemail_body_changed' => "Quacchedune, pò essere tu, da l'indirizze IP \$1,
ha cangiate l'indirizze e-mail d'u cunde utende \"\$2\" cu st'indirizze e-mail sus a {{SITENAME}}.

Pe confermà ca stu cunde ète avveramende 'u tune e pe reattivà 'a funzione email de {{SITENAME}}, iapre stu collegamende jndr'à 'u borwser tune:

\$3

Ce tu *NON* g'è reggistrate 'u cunde utende, segue stu collegamende pe scangellà l'indirizze email de conferme:

\$5

Stu codece de conferme scade 'u \$4.",
'confirmemail_invalidated'  => "Conferme de l'indirizze e-mail scangellete",
'invalidateemail'           => "Scangille 'a conferme de l'e-mail",

# Scary transclusion
'scarytranscludedisabled' => "[Collegaminde 'mbrà InterUicchi disabbilitate]",
'scarytranscludefailed'   => "[L'analisi d'u template ha fallite pe $1]",
'scarytranscludetoolong'  => '[URL jè troppe longhe]',

# Trackbacks
'trackbackbox'      => 'Trackback pe sta pàgene:<br />
$1',
'trackbackremove'   => '([$1 Scangille])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => "'U trackback ha state scangellete cu successe.",

# Delete conflict
'deletedwhileediting' => "'''Fà attenziò''': Sta pàgene ha state scangellete apprime ca tu acumenzasse a fà 'u cangiamende!",
'confirmrecreate'     => "L'utende [[User:$1|$1]] ([[User talk:$1|'Ngazzaminde]]) ha scangellate sta pàgene apprisse ca tu è accumenzate a cangiarle, cu stu mutive:
: ''$2''
Pe piacere conferme ca tu vuè avveramende reccrejà sta pàgene.",
'recreate'            => "Ccreje n'otra vote",

# action=purge
'confirm_purge_button' => 'OK',
'confirm-purge-top'    => "Pulizze 'a cache de sta pàgene?",
'confirm-purge-bottom' => "Pulezzanne 'a cache d'a pàgene se pò vedè 'a versiona cchiù aggiornate d'a pàgene.",

# Multipage image navigation
'imgmultipageprev' => '← pàgena precedende',
'imgmultipagenext' => 'pàgena successive →',
'imgmultigo'       => 'Veje!',
'imgmultigoto'     => "Veje 'a pàgene $1",

# Table pager
'ascending_abbrev'         => 'asc',
'descending_abbrev'        => 'desc',
'table_pager_next'         => 'Pàgena successive',
'table_pager_prev'         => 'Pàgena precedende',
'table_pager_first'        => 'Prima pàgene',
'table_pager_last'         => 'Urtema pàgene',
'table_pager_limit'        => 'Fa vedè $1 vosce pe pàgene',
'table_pager_limit_label'  => 'Vôsce pe pàggene:',
'table_pager_limit_submit' => 'Veje',
'table_pager_empty'        => 'Nisciune resultete',

# Auto-summaries
'autosumm-blank'   => "Pulizze 'a pàgene",
'autosumm-replace' => "Condenute sostituite cu '$1'",
'autoredircomment' => 'Stoche a ridirezione sus a [[$1]]',
'autosumm-new'     => "Pàgena ccrejete cu '$1'",

# Live preview
'livepreview-loading' => 'Stoche a careche…',
'livepreview-ready'   => 'Stoche a careche… Agghje fenìte!',
'livepreview-failed'  => "L'andeprima live ha fallite! Pruève cu quedda normale.",
'livepreview-error'   => 'Non ge tè riuscite a connettere: $1 "$2".
Prueve l\'andeprima normele.',

# Friendlier slave lag warnings
'lag-warn-normal' => "Le cangiaminde cchiù nuève de $1 {{PLURAL:$1|seconde|seconde}} pò essere ca non ge se vedene jndr'à sta liste.",
'lag-warn-high'   => "Pe colpe d'u timbe de lag ierte d'u server de database, le cangiaminde cchiù nuève de $1 {{PLURAL:$1|seconde|seconde}} pò essere ca non ge se vedene jndr'à sta liste.",

# Watchlist editor
'watchlistedit-numitems'       => "'A liste de le pàggene condrollate tène {{PLURAL:$1|1 titele|$1 titele}}, 'scudenne le pàggne de le 'ngazzaminde.",
'watchlistedit-noitems'        => "'A lista de le pàggene condrollete toja no tène 'na vosce.",
'watchlistedit-normal-title'   => 'Vide le pàggene condrollete',
'watchlistedit-normal-legend'  => "Live le titele da 'a liste de le pàggene condrollete",
'watchlistedit-normal-explain' => "Le titele sus a 'a liste de le pàggene condrollate avènene fatte vedè aqquà sotte. <br />
Pe luà 'nu titele, smarche 'a sckatele affianghe a jidde e cazze \"{{int:Watchlistedit-normal-submit}}\".<br />
Tu puè pure [[Special:Watchlist/raw|cangià 'a liste a mane]].",
'watchlistedit-normal-submit'  => 'Live le titele',
'watchlistedit-normal-done'    => "{{PLURAL:$1|1 titele ha state|$1 titele onne state}} scangellete da 'a liste de le pàggene condrollete toje:",
'watchlistedit-raw-title'      => "Cange 'a liste de le pàggene condrollete grezze",
'watchlistedit-raw-legend'     => "Cange 'a liste de le pàggene condrollete grezze",
'watchlistedit-raw-explain'    => "Le titele sus a liste de le pàggene condrollate avènene fatte vedè aqquà sotte e ponne essere cangiate aggiunggenne o luannele da 'a liste; <br />
'nu titele pe linèe.<br />
Quanne è spicciate, cazze sus a \"{{int:Watchlistedit-raw-submit}}\".<br />
Tu puè pure [[Special:Watchlist/edit|ausà 'u cangiatore standàrd]].",
'watchlistedit-raw-titles'     => 'Titele:',
'watchlistedit-raw-submit'     => "Aggiorne 'a liste de le pàggene condrollete",
'watchlistedit-raw-done'       => "'A liste de le pàggene condrollete ha state aggiornete.",
'watchlistedit-raw-added'      => "{{PLURAL:$1|'nu titele ha|$1 titele onne}} state aggiunde:",
'watchlistedit-raw-removed'    => "{{PLURAL:$1|'nu titele ha|$1 titele onne}} state scangillete:",

# Watchlist editing tools
'watchlisttools-view' => "Vide le cangiaminde 'mbortande",
'watchlisttools-edit' => 'Vide e cange le pàggene condrollete',
'watchlisttools-raw'  => 'Cange le pàggene condrollete grezze grezze',

# Core parser functions
'unknown_extension_tag' => 'Estenzione d\'u tag scanuscite "$1"',
'duplicate-defaultsort' => "'''Attenziò:''' 'A chiave de arrangamende de default \"\$2\" sovrascrive quedda precedende \"\$1\".",

# Special:Version
'version'                          => 'Versione',
'version-extensions'               => 'Estenziune installete',
'version-specialpages'             => 'Pàggene speciele',
'version-parserhooks'              => 'Hook analizzature',
'version-variables'                => 'Variabbele',
'version-other'                    => 'Otre',
'version-mediahandlers'            => 'Gestore de le Media',
'version-hooks'                    => 'Hook',
'version-extension-functions'      => 'Funziune estese',
'version-parser-extensiontags'     => "Tag pe l'estenziune de l'analizzatore",
'version-parser-function-hooks'    => "Funziune hook de l'analizzatore",
'version-skin-extension-functions' => 'Funziune estese pe le skin',
'version-hook-name'                => "Nome de l'hook",
'version-hook-subscribedby'        => 'Sottoscritte da',
'version-version'                  => '(Versione $1)',
'version-license'                  => 'Licenze',
'version-poweredby-credits'        => "Sta Uicchi jè fatte da '''[http://www.mediawiki.org/ MediaWiki]''', copyright © 2001-$1 $2.",
'version-poweredby-others'         => 'otre',
'version-license-info'             => "MediaUicchi jè 'nu softuare libbere, tu 'u puè redestribbuì  e/o cangiarle sotte le termine d'a GNU (Licenze Pubbleche Generale) cumme pubblecate da 'a Free Software Foundation; endrambe le versiune 2 d'a Licenze, o (a scelta toje) 'le versiune cchiù nnove.

Mediauicchi jè destribbuite cu 'a speranze ca jè utile, ma SENZE NISCIUNA GARANZIE; senze nemmanghe 'a garanzie imblicite de COMMERCIABBELETÀ o IDONIETÀ PE 'NU SCOPE PARTICOLARE. Vatte a vide 'a GNU (Licenze Pubbleche Generale) pe cchiù 'mbormaziune.

Avisse avè ricevute [{{SERVER}}{{SCRIPTPATH}}/COPYING 'na copie d'a GNU (Licenze Pubbleche Generale)] 'nzieme a stu programme, ce none, scrive a 'a Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor , Boston, MA 02110-1301, USA o [http://www.gnu.org/licenses/old-licenses/gpl-2.0.html liggele sus a Indernette].",
'version-software'                 => 'Softuer installete',
'version-software-product'         => 'Prodotte',
'version-software-version'         => 'Versione',

# Special:FilePath
'filepath'         => "Percorse d'u fail",
'filepath-page'    => 'File:',
'filepath-submit'  => 'Véje',
'filepath-summary' => "Sta pàgena speciale retorne 'u percorse comblete pe 'nu file.<br />
Le immaggine sonde mostrate jndr'à resoluziona megghie, otre tipe de file rechiamane 'u lore programme associate direttamende.

Mitte 'u nome d'u file senza 'u prefisse \"{{ns:file}}\".",

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Cirche pe le file duplichete',
'fileduplicatesearch-summary'  => "Cirche pe file duplichete sus a base d'u valore hash.

Mitte 'u nome d'u file senze 'u \"{{ns:file}}:\" prefisse.",
'fileduplicatesearch-legend'   => "Cirche pe 'nu duplichete",
'fileduplicatesearch-filename' => "Nome d'u faile:",
'fileduplicatesearch-submit'   => 'Cirche',
'fileduplicatesearch-info'     => "$1 × $2 pixel<br />Dimenzione d'u file: $3<br />Tipe de MIME: $4",
'fileduplicatesearch-result-1' => '\'U file "$1" non ge tène \'na duplicazione uguale uguale.',
'fileduplicatesearch-result-n' => '\'U file "$1" tène {{PLURAL:$2|1 duplicazione|$2 duplicaziune}} uguale uguale.',

# Special:SpecialPages
'specialpages'                   => 'Pàggene speciele',
'specialpages-note'              => '----
* Pàggene speciale normale.
* <strong class="mw-specialpagerestricted">Pàggene speciale cu le restriziune.</strong>',
'specialpages-group-maintenance' => "Report d'a manutenzione",
'specialpages-group-other'       => 'Otre pàggene speciele',
'specialpages-group-login'       => 'Tràse / Reggistrete',
'specialpages-group-changes'     => 'Cangiaminde recende e archivie',
'specialpages-group-media'       => 'Riepileghe de media e carecaminde',
'specialpages-group-users'       => 'Utinde e deritte',
'specialpages-group-highuse'     => 'Pàggene ausete assaje proprie',
'specialpages-group-pages'       => 'Liste de le pàggene',
'specialpages-group-pagetools'   => 'Pàgene de le struminde',
'specialpages-group-wiki'        => 'Date e struminde de Uicchi',
'specialpages-group-redirects'   => 'Redirezionaminde de le pàggene speciele',
'specialpages-group-spam'        => "Struminde p'u spam",

# Special:BlankPage
'blankpage'              => 'Pàgene vacande',
'intentionallyblankpage' => 'Sta pàgene ha state lassete vianghe apposte',

# External image whitelist
'external_image_whitelist' => "  #Lasse sta linèe satte satte a cumme ste<pre>
#Mitte le frammende de l'espressione regolare (proprie 'a parte ca vè 'mbrà le //) sotte
#Chidde ca se combrondene cu successe cu le immaggine de le URL esterne (collegaminde cavede)
#Chidde ca cu 'u combronde avènene visualizzate cumme immaggine, oppure sulamende 'nu collegamende a l'immaggine avènene visualizzate
#Linèe ca accumenzane pe # sonde trattate cumme commende
#Quiste ète insenzitive pe le maiuscole e le minuscole

#Mitte tutte le frammende regex sus a sta linèe. Lasse sta linèe satte satte a cumme ste</pre>",

# Special:Tags
'tags'                    => 'Cangiaminde de le tag valide',
'tag-filter'              => 'Filtre de le [[Special:Tags|tag]]:',
'tag-filter-submit'       => 'Filtre',
'tags-title'              => 'Tag',
'tags-intro'              => "Sta pàgene elenghe le tag ca 'u software pò marcà cu 'nu cangiamende e 'u lore significate.",
'tags-tag'                => "Nome d'u tag",
'tags-display-header'     => "Accumme parene sus 'a liste de le cangiaminde",
'tags-description-header' => "Descriziona comblete d'u significhete",
'tags-hitcount-header'    => 'Cangiaminde taggate',
'tags-edit'               => 'cange',
'tags-hitcount'           => '$1 {{PLURAL:$1|cangiamende|cangiaminde}}',

# Special:ComparePages
'comparepages'     => 'Combronde le pàggene',
'compare-selector' => "Combronde le revisiune d'à pàgene",
'compare-page1'    => 'Pàgene 1',
'compare-page2'    => 'Pàgene 2',
'compare-rev1'     => 'Revisione 1',
'compare-rev2'     => 'Revisione 2',
'compare-submit'   => 'Combronde',

# Database error messages
'dberr-header'      => "Sta Uicchi tène 'nu probbleme",
'dberr-problems'    => "Simw spiacende! Stu site stè 'ngondre de le diffcoltà tecniche.",
'dberr-again'       => 'Aspitte quacche minute e pò recareche.',
'dberr-info'        => "(Non ge riuscime a condattà 'u server d'u database: $1)",
'dberr-usegoogle'   => 'Pu mumende tu puè pruvà a cercà cu Google.',
'dberr-outofdate'   => 'Vide ca le indice lore de le condenute nuèstre ponne essere non aggiornate.',
'dberr-cachederror' => "Queste jè 'na copie ''cache'' d'a pàgene ca è cercate e allore non g'à puè cangià.",

# HTML forms
'htmlform-invalid-input'       => 'Stonne probbleme cu certe input ca tu è mise',
'htmlform-select-badoption'    => "'U valore ca è specificate non g'è 'n'opziona valide.",
'htmlform-int-invalid'         => "'U valore ca è specificate non g'è 'n'indere.",
'htmlform-float-invalid'       => "'U valore ca è specificate non g'è 'nu numere.",
'htmlform-int-toolow'          => "'U valore ca è specificate jè sotte a 'u minime de $1",
'htmlform-int-toohigh'         => "'U valore ca è specificate jè suverchie a 'u massime de $1",
'htmlform-required'            => 'Stu valore jè richieste',
'htmlform-submit'              => 'Conferme',
'htmlform-reset'               => 'Annulle le cangiaminde',
'htmlform-selectorother-other' => 'Otre',

# SQLite database support
'sqlite-has-fts' => "$1 cu 'u supporte d'a ricerche full-text",
'sqlite-no-fts'  => "$1 senze 'u supporte d'a ricerche full-text",

# Special:DisableAccount
'disableaccount-user'   => "Nome de l'utende:",
'disableaccount-reason' => 'Mutive:',

);

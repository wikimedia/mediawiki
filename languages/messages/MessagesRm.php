<?php
/** Romansh (Rumantsch)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Gion
 * @author Gion-andri
 * @author Kazu89
 * @author Urhixidur
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Multimedia',
	NS_SPECIAL          => 'Spezial',
	NS_TALK             => 'Discussiun',
	NS_USER             => 'Utilisader',
	NS_USER_TALK        => 'Utilisader_discussiun',
	NS_PROJECT_TALK     => '$1_discussiun',
	NS_FILE             => 'Datoteca',
	NS_FILE_TALK        => 'Datoteca_discussiun',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_discussiun',
	NS_TEMPLATE         => 'Model',
	NS_TEMPLATE_TALK    => 'Model_discussiun',
	NS_HELP             => 'Agid',
	NS_HELP_TALK        => 'Agid_discussiun',
	NS_CATEGORY         => 'Categoria',
	NS_CATEGORY_TALK    => 'Categoria_discussiun',
);

$messages = array(
# User preference toggles
'tog-underline'               => 'suttastritgar colliaziuns:',
'tog-highlightbroken'         => 'Formatar colliaziuns betg existentas <a href="" class="new">uschia</a> (alternativa: uschia<a href="" class="internal">?</a>)',
'tog-justify'                 => "Text en furma da 'bloc'",
'tog-hideminor'               => 'Zuppentar pitschnas midadas en las ultimas midadas',
'tog-hidepatrolled'           => 'Zuppentar midadas controlladas en las «ultimas midadas»',
'tog-newpageshidepatrolled'   => 'Zuppentar paginas controlladas en las «paginas novas»',
'tog-extendwatchlist'         => "Extender la glista d'observaziun per mussar tut las midadas e betg be las ultimas",
'tog-usenewrc'                => "Duvrar la versiun extendida da las ''Ultimas midadas'' (basegna JavaScript)",
'tog-numberheadings'          => 'Numerar automaticamain ils titels',
'tog-showtoolbar'             => "Mussa la trav d'utensils (basegna JavaScript)",
'tog-editondblclick'          => 'Modifitgar paginas cun in clic dubel (basegna JavaScript)',
'tog-editsection'             => 'Mussar colliaziuns [modifitgar] per modifitgar singulas secziuns',
'tog-editsectiononrightclick' => 'Activar la pussaivladad da modifitgar secziuns cun in clic dretg (basegna JavaScript)',
'tog-showtoc'                 => 'Mussar ina tabla da cuntegn sin paginas cun dapli che trais tetels',
'tog-rememberpassword'        => "S'annunziar permanantamain cun quest navigatur (per maximalmain $1 {{PLURAL:$1|di|dis}})",
'tog-watchcreations'          => "Observar paginas ch'jau hai creà",
'tog-watchdefault'            => "Observar paginas ch'jau hai edità",
'tog-watchmoves'              => "Observar paginas ch'jau hai spustà",
'tog-watchdeletion'           => "Observar paginas ch'jau hai stizzà",
'tog-minordefault'            => 'Marcar tut las modificaziuns per ordinari sco pitschnas',
'tog-previewontop'            => "Mussar la prevista sur il champ d'endatziun",
'tog-previewonfirst'          => "Adina mussar la prevista suenter l'emprima modificaziun",
'tog-nocache'                 => 'Impedir che tes navigatur memorisescha las paginas en il cache',
'tog-enotifwatchlistpages'    => "Trametta in e-mail sch'ina pagina sin mia glista d'observaziun vegn midada",
'tog-enotifusertalkpages'     => "Trametta in e-mail sch'i ha dà midadas sin mia pagina da discussiun.",
'tog-enotifminoredits'        => 'Trametta era in e-mail tar pitschnas midadas da las paginas',
'tog-enotifrevealaddr'        => "Mussar mia adressa dad e-mail en e-mails d'avis",
'tog-shownumberswatching'     => "Mussar il dumber d'utilisaders che obervan questa pagina",
'tog-oldsig'                  => 'Prevista da la signatura actuala:',
'tog-fancysig'                => "Suttascripziun senza link automatic tar la pagina da l'utilisader.",
'tog-externaleditor'          => 'Utilisar sco standard in editur extern (be per experts, basegna ina configuraziun speziala da des computer. [//www.mediawiki.org/wiki/Manual:External_editors Dapli infurmaziuns.])',
'tog-externaldiff'            => 'Utilisar sco standard in program extern per visualisar differenzas tranter versiuns (be per experts, basegna ina configuraziun speziala da des computer. [//www.mediawiki.org/wiki/Manual:External_editors Dapli infurmaziuns.])',
'tog-showjumplinks'           => 'Activar las colliaziuns "seglir a"',
'tog-uselivepreview'          => 'Utilisar la prevista dinamica (basegna JavaScript) (experiment!)',
'tog-forceeditsummary'        => 'Avertir durant memoriar sche la resumaziun manca',
'tog-watchlisthideown'        => "Zuppentar mias modificaziuns en la glista d'observaziun",
'tog-watchlisthidebots'       => "Zuppentar modificaziuns da bots en la glista d'observaziun",
'tog-watchlisthideminor'      => "Zuppentar pitschnas modificaziuns en la glista d'observaziun",
'tog-watchlisthideliu'        => "Zuppentar modificaziuns d'utilisaders ch'èn s'annunziads en la glista d'observaziun",
'tog-watchlisthideanons'      => "Zuppentar modificaziuns da utilisaders anonims en la glista d'observaziun",
'tog-watchlisthidepatrolled'  => "Zuppentar modificaziuns controlladas en mia glista d'observaziun",
'tog-ccmeonemails'            => "Ma trametter copias dad e-mails ch'jau tramet ad auters utilisaders",
'tog-diffonly'                => 'Betg mussar il cuntegn da la pagina sut differenzas tranter versiuns',
'tog-showhiddencats'          => 'Mussar categorias zuppendatas',
'tog-norollbackdiff'          => 'Betg mussar las differenzas suenter revocar',

'underline-always'  => 'adina suttastritgar',
'underline-never'   => 'mai suttastritgar',
'underline-default' => 'Standard dal navigatur',

# Font style option in Special:Preferences
'editfont-style'     => 'Scrittira per il text en la fanestra da modifitgar:',
'editfont-default'   => 'Standard dal navigatur',
'editfont-monospace' => 'Scrittira cun largezza fixa',
'editfont-sansserif' => 'Scrittira senza serifas',
'editfont-serif'     => 'Scrittira cun serifas',

# Dates
'sunday'        => 'Dumengia',
'monday'        => 'Glindesdi',
'tuesday'       => 'mardi',
'wednesday'     => 'mesemna',
'thursday'      => 'Gievgia',
'friday'        => 'Venderdi',
'saturday'      => 'sonda',
'sun'           => 'du',
'mon'           => 'Gli',
'tue'           => 'ma',
'wed'           => 'mes',
'thu'           => 'gie',
'fri'           => 've',
'sat'           => 'so',
'january'       => 'schaner',
'february'      => 'favrer',
'march'         => 'mars',
'april'         => 'avril',
'may_long'      => 'matg',
'june'          => 'zercladur',
'july'          => 'fanadur',
'august'        => 'avust',
'september'     => 'Settember',
'october'       => 'october',
'november'      => 'november',
'december'      => 'december',
'january-gen'   => 'schaner',
'february-gen'  => 'favrer',
'march-gen'     => 'mars',
'april-gen'     => 'avril',
'may-gen'       => 'matg',
'june-gen'      => 'zercladur',
'july-gen'      => 'fanadur',
'august-gen'    => 'avust',
'september-gen' => 'settember',
'october-gen'   => 'october',
'november-gen'  => 'november',
'december-gen'  => 'december',
'jan'           => 'schan',
'feb'           => 'favr',
'mar'           => 'mars',
'apr'           => 'avr',
'may'           => 'matg',
'jun'           => 'zercl',
'jul'           => 'fan',
'aug'           => 'avu',
'sep'           => 'sett',
'oct'           => 'oct',
'nov'           => 'nov',
'dec'           => 'dec',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Categoria|Categorias}}',
'category_header'                => 'Artitgels en la categoria "$1"',
'subcategories'                  => 'sutcategorias',
'category-media-header'          => 'Datotecas en la categoria "$1"',
'category-empty'                 => "''Questa categoria cuntegna actualmain nagins artitgels e naginas datotecas.''",
'hidden-categories'              => '{{PLURAL:$1|Categoria zuppentada|Categorias zuppentadas}}',
'hidden-category-category'       => 'Categorias zuppentadas',
'category-subcat-count'          => '{{PLURAL:$2|Questa categoria cuntegna be suandanta sutcategoria.|Questa categoria cuntegna {{PLURAL:$1|la suandanta sutcategoria|las $1 suandantas sutcategorias}} da totalmain $2 sutcategoria.}}',
'category-subcat-count-limited'  => 'Questa categoria cuntegna {{PLURAL:$1|suandanta subcategoria|suandantas $1 subcategorias}}:',
'category-article-count'         => '{{PLURAL:$2|Questa categoria cuntegna be la suandanta pagina.|{{PLURAL:$1|La suandanta pagina è|Las $1 suandantas paginas èn}} en questa categoria che cuntegna totalmain $2 paginas.}}',
'category-article-count-limited' => '{{PLURAL:$1|La suandanta pagina è|Las suandantas $1 paginas èn}} actualmain en la categoria.',
'category-file-count'            => '{{PLURAL:$2|Questa categoria cuntegna be la suandanta pagina.|{{PLURAL:$1|La suandanta datoteca è|Las $1 suandantas datotecas èn}} en questa categoria che cuntegna totalmain $2 datotecas.}}',
'category-file-count-limited'    => '{{PLURAL:$1|La suandanta datoteca è|Las suandantas $1 datotecas èn}} actualmain en la categoria.',
'listingcontinuesabbrev'         => 'cuntinuaziun',
'index-category'                 => 'Paginas inditgadas',
'noindex-category'               => 'Paginas betg inditgadas',
'broken-file-category'           => 'Paginas cun colliaziuns da datoteca che na funcziunan betg',

'about'         => 'Surda',
'article'       => 'artitgel',
'newwindow'     => '(avra ina nova fanestra)',
'cancel'        => 'refusar las midadas',
'moredotdotdot' => 'Dapli...',
'mypage'        => 'mia pagina',
'mytalk'        => 'Mia pagina da discussiun',
'anontalk'      => 'Pagina da discussiun da questa IP',
'navigation'    => 'Navigaziun',
'and'           => '&#32;e',

# Cologne Blue skin
'qbfind'         => 'Chattar',
'qbbrowse'       => 'Sfegliar',
'qbedit'         => 'Modifitgar',
'qbpageoptions'  => 'Questa pagina',
'qbpageinfo'     => 'Context',
'qbmyoptions'    => 'Mia pagina',
'qbspecialpages' => 'paginas spezialas',
'faq'            => 'FAQ',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection'       => 'Agiuntar chapitel',
'vector-action-delete'           => 'Stizzar',
'vector-action-move'             => 'Spustar',
'vector-action-protect'          => 'Bloccar',
'vector-action-undelete'         => 'Restituir',
'vector-action-unprotect'        => 'Midar la protecziun',
'vector-simplesearch-preference' => 'Activar las propostas per tschertgar extendidas (be per il skin Vector)',
'vector-view-create'             => 'Crear',
'vector-view-edit'               => 'Modifitgar',
'vector-view-history'            => 'Cronologia',
'vector-view-view'               => 'Leger',
'vector-view-viewsource'         => 'Mussar il code',
'actions'                        => 'Acziuns',
'namespaces'                     => 'Tip da pagina',
'variants'                       => 'Variantas',

'errorpagetitle'    => 'Errur',
'returnto'          => 'Enavos tar $1.',
'tagline'           => 'Ord {{SITENAME}}',
'help'              => 'Agid',
'search'            => 'Tschertgar',
'searchbutton'      => 'Tschertgar',
'go'                => 'Artitgel',
'searcharticle'     => 'dai!',
'history'           => 'versiuns',
'history_short'     => 'versiuns/auturs',
'updatedmarker'     => "actualisà dapi mi'ultima visita",
'printableversion'  => 'Versiun per stampar',
'permalink'         => 'Colliaziun permanenta',
'print'             => 'stampar',
'view'              => 'Leger',
'edit'              => 'Modifitgar',
'create'            => 'Crear',
'editthispage'      => 'Modifitgar questa pagina',
'create-this-page'  => 'Crear questa pagina',
'delete'            => 'Stizzar',
'deletethispage'    => 'Stizzar questa pagina',
'undelete_short'    => 'Revocar {{PLURAL:$1|ina modificaziun|$1 modificaziuns}}',
'viewdeleted_short' => 'Guardar {{PLURAL:$1|ina modificaziun stizzada|$1 modificaziuns stizzadas}}',
'protect'           => 'proteger',
'protect_change'    => 'midar',
'protectthispage'   => 'Proteger questa pagina',
'unprotect'         => 'Midar la protecziun',
'unprotectthispage' => 'Midar la protecziun da questa pagina',
'newpage'           => 'Nova pagina',
'talkpage'          => 'Discutar quest artitgel',
'talkpagelinktext'  => 'Discussiun',
'specialpage'       => 'Pagina speziala',
'personaltools'     => 'Utensils persunals',
'postcomment'       => 'Nova secziun',
'articlepage'       => 'Mussar la pagina da cuntegn',
'talk'              => 'Discussiun',
'views'             => 'Questa pagina',
'toolbox'           => 'Utensils',
'userpage'          => "Mussar la pagina d'utilisader",
'projectpage'       => 'Mussar la pagina da project',
'imagepage'         => 'Mussar la pagina da datotecas',
'mediawikipage'     => 'Mussar la pagina da messadis',
'templatepage'      => 'Mussar la pagina dal model',
'viewhelppage'      => "Mussar pagina d'agid",
'categorypage'      => 'Mussar la pagina da questa categoria',
'viewtalkpage'      => 'Mussar la discussiun',
'otherlanguages'    => 'En autras linguas',
'redirectedfrom'    => '(renvià da $1)',
'redirectpagesub'   => "questa pagina renviescha tar in'auter artitgel",
'lastmodifiedat'    => "Questa pagina è vegnida modifitgada l'ultima giada ils $1 a las $2.",
'viewcount'         => 'Questa pagina è vegnida contemplada {{PLURAL:$1|ina giada|$1 giadas}}.',
'protectedpage'     => 'Pagina protegida',
'jumpto'            => 'Midar tar:',
'jumptonavigation'  => 'navigaziun',
'jumptosearch'      => 'tschertga',
'view-pool-error'   => 'Stgisa, ils servers èn actualmain surchargiads. 
Memia blers utilisaders emprovan da chargiar questa pagina. 
Spetga per plaschair in mument avant che ti eprovas da puspè contemplar questa pagina. 

$1',
'pool-timeout'      => 'Surpassà il temp durant spetgar sin la bloccada',
'pool-queuefull'    => 'La colona da spetga dil pool è plaina',
'pool-errorunknown' => 'Errur nunenconuschenta',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Davart {{SITENAME}}',
'aboutpage'            => 'Project:Davart',
'copyright'            => 'Cuntegn disponibel sut $1.',
'copyrightpage'        => '{{ns:project}}:Resguardar_dretgs_d_autur',
'currentevents'        => 'Events actuals',
'currentevents-url'    => 'Project:Events actuals',
'disclaimers'          => 'Impressum',
'disclaimerpage'       => 'Project:Impressum',
'edithelp'             => 'Agid per modifitgar',
'edithelppage'         => 'Help:Prims pass',
'helppage'             => 'Help:Cuntegn',
'mainpage'             => 'Pagina principala',
'mainpage-description' => 'Pagina principala',
'policy-url'           => 'Project:Directivas',
'portal'               => 'Portal da {{SITENAME}}',
'portal-url'           => 'Project:Portal da {{SITENAME}}',
'privacy'              => 'Protecziun da datas',
'privacypage'          => 'Project:Protecziun_da_datas',

'badaccess'        => "Errur dad access: vus n'avais betg avunda dretgs",
'badaccess-group0' => "Vus na dastgais betg exequir l'acziun giavischada.",
'badaccess-groups' => "L'acziun che vus vulais far dastgan mo utilisaders en {{PLURAL:$2|las gruppas|la gruppa}} $1 exequir.",

'versionrequired'     => 'Versiun $1 da MediaWiki è necessaria',
'versionrequiredtext' => 'Ti dovras versiun $1 da MediaWiki per duvrar questa pagina. Guarda [[Special:Version| qua!]]',

'ok'                      => "D'accord",
'retrievedfrom'           => 'Da "$1"',
'youhavenewmessages'      => 'Ti has $1 ($2).',
'newmessageslink'         => 'novs messadis',
'newmessagesdifflink'     => "l'ultima midada",
'youhavenewmessagesmulti' => 'Ti as novs messadis en $1',
'editsection'             => 'modifitgar',
'editold'                 => 'modifitgar',
'viewsourceold'           => 'mussar il code da funtauna',
'editlink'                => 'modifitgar',
'viewsourcelink'          => 'mussar il code da funtauna',
'editsectionhint'         => 'Modifitgar secziun: $1',
'toc'                     => 'Cuntegn',
'showtoc'                 => 'mussar',
'hidetoc'                 => 'zuppentar',
'collapsible-collapse'    => 'Cumprimer',
'collapsible-expand'      => 'Extender',
'thisisdeleted'           => 'Guardar u restaurar $1?',
'viewdeleted'             => 'Mussa $1?',
'restorelink'             => '{{PLURAL:$1|ina modificaziun stizzada|$1 modificaziuns stizzadas}}',
'feedlinks'               => 'Feed:',
'feed-invalid'            => 'Faus tip da feed per la subscripziun.',
'feed-unavailable'        => "Feed n'è betg disponibel",
'site-rss-feed'           => 'RSS Feed da $1',
'site-atom-feed'          => 'Atom Feed da $1',
'page-rss-feed'           => 'RSS Feed "$1"',
'page-atom-feed'          => 'Atom feed "$1"',
'feed-atom'               => 'Atom',
'feed-rss'                => 'RSS',
'red-link-title'          => "$1 (n'exista betg)",
'sort-descending'         => 'Zavrar descendent',
'sort-ascending'          => 'Zavrar ascendent',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Artitgel',
'nstab-user'      => "Pagina da l'utilisader",
'nstab-media'     => 'Pagina da medias',
'nstab-special'   => 'Pagina speziala',
'nstab-project'   => 'pagina dal project',
'nstab-image'     => 'Datoteca',
'nstab-mediawiki' => 'Messadi',
'nstab-template'  => 'Model',
'nstab-help'      => 'Agid',
'nstab-category'  => 'Categoria',

# Main script and global functions
'nosuchaction'      => "Talas acziuns n'existan betg",
'nosuchactiontext'  => "L'acziun specifitgada per questa URL è faussa.
Ti has endatà fauss la URL, u es suandà in link incorrect.
I po dentant er esser ina errur en la software da {{SITENAME}}.",
'nosuchspecialpage' => "I n'exista betg ina tala pagina speziala",
'nospecialpagetext' => "<strong>Vus avais tschertgà ina pagina speziala che n'exista betg.</strong>

Ina glista da las paginas spezialas existentas chattais vus sut [[Special:SpecialPages|{{int:specialpages}}]].",

# General errors
'error'                => 'Errur',
'databaseerror'        => 'Sbagl da la datoteca',
'dberrortext'          => 'In sbagl da la sintaxa da la dumonda a la banca da datas è capità.
Quai po esser in sbagl en la software.
L\'ultima dumonda per la banca da datas era:
<blockquote><tt>$1</tt></blockquote>
ord la funcziun "<tt>$2</tt>".
La banca da datas ha rapportà l\'errur "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'In sbagl da la sintaxa da la dumonda a la banca da datas è capità.
L\'ultima dumonda per la banca da datas era:
"$1"
ord la funcziun "$2".
La banca da datas ha rapportà l\'errur "$3: $4"',
'laggedslavemode'      => 'Attenziun: La pagina mussada na cuntign eventualmain betg anc las ultimas midadas.',
'readonly'             => 'Banca da datas bloccada',
'enterlockreason'      => 'Inditgescha ina raschun per la bloccada da la banca da datas ed il temp cura che ti quintas che la bloccada po vegnir annullada',
'readonlytext'         => "La banca da datas è actualmain bloccada per novas endataziuns u modificaziuns. Emprova pli tard anc ina giada. 

L'adminstratur che ha bloccà la banca da datas ha inditgà suandant motiv: $1",
'missing-article'      => 'Il text da la pagina cun il num "$1" $2 n\'è betg vegnì chattà en la banca da datas.

Quai capita sch\'ins suonda in link che n\'è betg pli actuals u in link sin ina pagina ch\'è vegnida stizzada.

Sche quai na duess betg esser il cas, lura è quai in sbagl da la software.
Annunzia per plaschair la URL ad in [[Special:ListUsers/sysop|administratur]].',
'missingarticle-rev'   => '(number da la versiun: $1)',
'missingarticle-diff'  => '(Differenza tranter versiuns: $1, $2)',
'readonly_lag'         => "La banca da datas èn vegnidas bloccadas automaticamain per ch'ils servers da bancas da datas derasads (slaves) pon vegnir sincronisads cun il server da bancas da datas principal (master).",
'internalerror'        => 'Errur interna',
'internalerror_info'   => 'Errur interna: $1',
'fileappenderrorread'  => 'Betg pussaivel da leger "$1" durant agiuntar.',
'fileappenderror'      => 'Betg pussaivel dad agiuntar "$1" a "$2".',
'filecopyerror'        => 'Betg pussaivel da copiar la datotca "$1" a "$2".',
'filerenameerror'      => 'Betg pussaivel da renumnar la datotca "$1" a "$2".',
'filedeleteerror'      => 'Betg pussaivel da stizzar la datoteca "$1".',
'directorycreateerror' => 'Betg pussaivel da crear l\'ordinatur "$1".',
'filenotfound'         => 'Betg pussaivel da chattar la datoteca "$1".',
'fileexistserror'      => 'Betg pussaivel da scriver la datoteca "$1": La datoteca exista gia.',
'unexpected'           => 'Valur nunspetgada: "$1"="$2".',
'formerror'            => 'Errur: betg pussaivel da trametter il formular',
'badarticleerror'      => 'Questa acziun na po betg vegnir exequida sin questa pagina.',
'cannotdelete'         => 'Betg pussaivel da stizzar la pagina u datoteca "$1".
Eventualmain ha gia insatgi auter stizza quest element.',
'badtitle'             => "Il num da titel endatà n'è betg valid",
'badtitletext'         => 'Il titel da pagina era betg valid, vids u in titel inter-lingua u inter-wiki betg correct.
El po cuntegnair in u plirs segns che na pon betg vegnir utilisads en titels.',
'perfcached'           => 'Las suandantas datas vegnan ord il cache ed èn eventualmain betg cumplettamain actualas:',
'perfcachedts'         => 'Las suandantas datas derivan dal cache, ultima actualisaziun ils $2 las $3.',
'querypage-no-updates' => 'Las actualisaziuns da questa pagina èn deactivadas. 
Las datas qua vegnan da preschent betg actualisadas.',
'wrong_wfQuery_params' => 'Parameters fauss per wfQuery()<br />
Funcziun: $1<br />
Query: $2',
'viewsource'           => 'Mussar il code da fontauna',
'viewsourcefor'        => 'per $1',
'actionthrottled'      => 'Acziun limitada',
'actionthrottledtext'  => 'Sco mesira cunter spam na pos ti betg exequir questa acziun memia bleras giadas en curt temp. Ti has surpassà questa limita. 
Emprova danovamain en in per minutas.',
'protectedpagetext'    => "Questa pagina è vegnida bloccada per evitar ch'ella vegn modifitgada.",
'viewsourcetext'       => 'Ti pos guardar e copiar il code-fundamental da questa pagina:',
'protectedinterface'   => "Questa pagina cuntegna text per l'interfatscha da la software ed è protegida per evitar abus.",
'editinginterface'     => "'''Attenziun:''' Questa pagina cuntegna text che vegn duvra da la software MediaWiki. Midadas influenzeschan directamain l'interfatscha da l'utilisader. Sche ti vuls far translaziuns u correcturas: Studegia da far quai sin [//translatewiki.net/wiki/Main_Page?setlang=rm translatewiki.net], per che las midadas pon vegnidas surprendidas da tut ils projects.",
'sqlhidden'            => '(Zuppentà la dumonda da SQL)',
'cascadeprotected'     => "Questa pagina è protegida da modificaziuns perquai ch'ella è integrada en suandanta{{PLURAL:$1| pagina, ch'è protegida|s paginas, ch'èn protegidas}} entras l'opziun «protecziun ertada»: 
$2",
'namespaceprotected'   => "Ti n'has betg la lubientscha da modifitgar paginas dal tip da pagina '''$1'''.",
'customcssprotected'   => "Ti n'has betg la permissiun da modifitgar questa pagina da CSS, perquai ch'ella cuntegna configuraziuns persunalas d'in auter utilisader.",
'customjsprotected'    => "Ti n'has betg la permissiun da modifitgar questa pagina da JavaScript, perquai ch'ella cuntegna configuraziuns persunalas d'in auter utilisader.",
'ns-specialprotected'  => 'Paginas spezialas no pon betg vegnir modifitgadas.',
'titleprotected'       => 'Quest titel è vegnì protegì da la creaziun da [[User:$1|$1]].
Il motiv inditgà è "\'\'$2\'\'".',

# Virus scanner
'virus-badscanner'     => "Configuraziun fauss: antivirus nunenconuschent: ''$1''",
'virus-scanfailed'     => 'Scan betg reussì (code $1)',
'virus-unknownscanner' => 'antivirus nunenconuschent:',

# Login and logout pages
'logouttext'                 => "'''Sortì cun success.'''

Ti pos cuntinuar cun utilisar {{SITENAME}} anonimamain, u che ti pos [[Special:UserLogin|t'annunziar]] sco medem u in'auter utilisader. Resguarda che entginas paginas pon anc vesair or tuttina sco sche ti eras annunzià enfin che ti has stizzà il cache da tes navigatur.",
'welcomecreation'            => '==Bainvegni, $1! ==
Tes conto è vegni creà.
Betg emblida da midar tias [[Special:Preferences|preferenzas da {{SITENAME}}]].',
'yourname'                   => "Num d'utilisader",
'yourpassword'               => 'pled-clav',
'yourpasswordagain'          => 'repeter pled-clav',
'remembermypassword'         => "S'annunziar permanantamain sin quest computer (per maximalmain $1 {{PLURAL:$1|di|dis}})",
'securelogin-stick-https'    => "Restar collià entras HTTPS suenter l'annunzia",
'yourdomainname'             => 'Vossa domain',
'externaldberror'            => "U ch'i è capità ina errur cun l'autentificaziun externa u che ti na dastgas betg actualisar tes conto extern.",
'login'                      => "T'annunziar",
'nav-login-createaccount'    => "T'annunziar / registrar",
'loginprompt'                => "Ti stos avair '''activà ils cookies''' per pudair t'annunziar tar {{SITENAME}}.",
'userlogin'                  => "T'annunziar / registrar",
'userloginnocreate'          => "T'annunziar",
'logout'                     => 'Sortir',
'userlogout'                 => 'Sortir',
'notloggedin'                => "Betg s'annunzià",
'nologin'                    => "Anc nagin conto? '''$1'''.",
'nologinlink'                => "Crear in conto d'utilisader",
'createaccount'              => "Crear in conto d'utilisader",
'gotaccount'                 => "Gia in conto d'utilisader? '''$1'''.",
'gotaccountlink'             => "T'annunziar",
'userlogin-resetlink'        => "Emblidà tias datas per s'annunziar?",
'createaccountmail'          => 'per e-mail',
'createaccountreason'        => 'Motiv:',
'badretype'                  => 'Ils dus pleds-clav na corrispundan betg.',
'userexists'                 => "Quest num d'utilisader vegn gia duvrà. 
Tscherna per plaschair in'auter.",
'loginerror'                 => "Sbagl cun t'annunziar",
'createaccounterror'         => 'Betg pussaivel da crear in conto: $1',
'nocookiesnew'               => "Il conto da l'utilisader è vegnì creà, ti es dentant betg t'annunzià. 
{{SITENAME}} utilisescha cookies per che utilisaders pon s'annunziar. 
Ti has deactivà ils cookies. 
Als activescha per plaschair e lura t'annunzia cun tes nov num d'utilisader e pled-clav.",
'nocookieslogin'             => "{{SITENAME}} utilisescha cookies per ch'utilisaders pon s'annunziar.
Ti has deactivà tes cookies.
Activescha per plaschair ils cookis en tes navigatur ed emprova danovamain.",
'nocookiesfornew'            => "Quest conto d'utilisader n'ha betg pudì vegnir creà perquai che nus na pudevan betg confermar sia funtauna. 
Controllescha che ti has activà ils cookies, rechargia la pagina ed emprova danovamain.",
'noname'                     => "Ti n'has betg inditgà in num d'utilisader valid.",
'loginsuccesstitle'          => "T'annunzià cun success",
'loginsuccess'               => "'''Ti es t'annunzia tar {{SITENAME}} sco \"\$1\".'''",
'nosuchuser'                 => 'I exista nagin utilisader cun il num "$1".
Fa stim dad utilisar correctamain maiusclas e minusclas.
Curregia il num u [[Special:UserLogin/signup|creescha in nov conto]].',
'nosuchusershort'            => 'I dat nagin utilisader cun il num "$1".
Curregia ti\'endataziun.',
'nouserspecified'            => "Inditgescha per plaschair in num d'utilisader.",
'login-userblocked'          => "Quest utilisader è bloccà. Betg pussaivel da t'annunziar.",
'wrongpassword'              => "Quai n'era betg il pled-clav correct. Prova anc ina giada.",
'wrongpasswordempty'         => 'Ti as emblidà da scriver tes pled-clav. Prova anc ina giada.',
'passwordtooshort'           => 'Tes pled-clav sto cuntegnair almain {{PLURAL:$1|in bustab|$1 bustabs}}.',
'password-name-match'        => "Il pled-clav na dastga betg esser il medem sco il num d'utilisader.",
'password-login-forbidden'   => "L'utilisaziun da quest num d'utilisader e pled-clav è vegnida scumandada.",
'mailmypassword'             => 'Trametter in nov pled-clav per e-mail',
'passwordremindertitle'      => 'Nov pled-clav temporar per {{SITENAME}}',
'passwordremindertext'       => 'Insatgi (probablamain ti, cun l\'adressa dad IP $1) ha dumandà in nov pled-clav per {{SITENAME}} ($4). Il pled-clav temporar "$3" per l\'utilisader "$2" è vegnì creà. Sche quai era tes intent, ti al dovras per t\'annunziar e tscherner lura in nov pled-clav. Quest pled-clav temporar vegn a scrudar en {{PLURAL:$5|in di|$5 dis}}.

Sch\'insatgi auter ha fatg questa dumonda, ni sch\'il pled-clav è vegnì endament e ti na vuls betg pli midar el, pos ti simplamain ignorar quest messadi e cuntinuar la lavur cun tes pled-clav vegl.',
'noemail'                    => 'L\'utilisader "$1" n\'ha inditgà nagina adressa dad e-mail.',
'noemailcreate'              => "Ti stos endatar in'adressa dad e-mail valaivla",
'passwordsent'               => "In nov pled-clav è vegnì tramess a l'adressa dad e-mail ch'è registrada per l'utilisader \"\$1\".
T'annunzia per plaschair sche ti has retschavì el.",
'blocked-mailpassword'       => "L'adressa dad IP che ti utiliseschas è vegnida bloccada per midar paginas. Plinavant è era la funcziun da generar in nov pled-clav vegnida bloccada per impedir in abus da questa funcziun.",
'eauthentsent'               => "In e-mail da confermaziun è vegnì tramess a l'adressa dad e-mail numnada.
Suonda las infurmaziuns en l'e-mail per confermar ch'il conto d'utilisader è il tes.",
'throttled-mailpassword'     => "Entaifer {{PLURAL:$1|l'ultima ura|las ultimas $1 uras}} è gia vegnì tramess in pled-clav temporar.
Per impedir abus vegn be tramess in pled-clav temporar entaifer {{PLURAL:$1|in ura|$1 uras}}.",
'mailerror'                  => "Errur cun trametter l'e-mail: $1",
'acct_creation_throttle_hit' => "Visitaders da questa wiki cun tia adressa dad IP han gia creà {{PLURAL:$1|1 conto|$1 contos}} l'ultim di. Quai è il maximum lubì en questa perioda.
Perquai pon visitaders cun questa IP betg pli crear dapli contos per il mument.",
'emailauthenticated'         => 'Tia adressa dad e-mail è vegnida verifitgada ils $2 las $3.',
'emailnotauthenticated'      => "Vus n'avais betg anc <strong>confermà vossa adressa dad e-mail</strong>.<br />
Perquei è anc nagin retschaiver e trametter dad e-mails per las suandantas funcziuns pussaivel.",
'noemailprefs'               => 'Inditgescha ina adressa dad e-mail en tias preferenzas, per che suandantas funcziuns ta stattan a disposiziun.',
'emailconfirmlink'           => "Confermar l'adressa dad e-mail",
'invalidemailaddress'        => "L'adressa dad e-mail na po betg vegnir acceptada perquai ch'ella para dad avair in format nunvalid. 
Endatescha per plaschair ina adressa formatada correctamain u svida cumplettamain il champ.",
'accountcreated'             => "Creà il conto d'utilisader",
'accountcreatedtext'         => "Il conto d'utilisader per $1 è vegnì creà.",
'createaccount-title'        => 'Crear in conto per {{SITENAME}}',
'createaccount-text'         => "Insatgi ha creà in conto d'utilisader per tia adressa dad e-mail sin {{SITENAME}} ($4). Il pled-clav generà automaticamain per l'utilisader «$2» è «$3». Ti ta duessas t'annunzar uss e midar tes pled-clav. 

Sche ti na levas betg crear quest conto d'utilisader pos ti ignorar quest e-mail.",
'usernamehasherror'          => "Il num d'utilisader na po betg cuntegnair il segn da rauta (#)",
'login-throttled'            => "Ti has empruvà memia savens da t'annunziar.
Spetga per plaschair avant ch'empruvar anc ina giada.",
'login-abort-generic'        => 'Annunzia senza success - Annullà',
'loginlanguagelabel'         => 'Lingua: $1',
'suspicious-userlogout'      => "Tia dumonda per partir è vegnida refusada perquai ch'i para ch'ella è vegnida tramessa d'in navigatur che funcziuna betg correctamain u d'in proxy da cache.",

# E-mail sending
'php-mail-error-unknown' => 'Errur nunenconuschenta en la funcziun mail() da PHP',

# Change password dialog
'resetpass'                 => 'Midar il pled-clav',
'resetpass_announce'        => "Ti ès t'annunzià cun in pled-clav temporar che ti has retschavì per e-mail. 
Per finir da t'annunziar stos ti definir qua in nov pled-clav:",
'resetpass_header'          => 'Midar il pled-clav dal conto',
'oldpassword'               => 'pled-clav vegl:',
'newpassword'               => 'pled-clav nov:',
'retypenew'                 => 'repeter pled-clav nov:',
'resetpass_submit'          => "Definir il pled clav e m'annunziar",
'resetpass_success'         => 'Tes pled-clav è vegnì midà cun success. 
Ti vegns annunzià…',
'resetpass_forbidden'       => 'Il pled-clav na po betg vegnir midà',
'resetpass-no-info'         => "Ti stos t'annunziar per acceder directamain questa pagina.",
'resetpass-submit-loggedin' => 'Midar il pled-clav',
'resetpass-submit-cancel'   => 'Interrumper',
'resetpass-wrong-oldpass'   => 'Fauss pled-clav temporar u actual.
Eventualmain has ti gia midà cun success tes pled-clav u dumandà per in nov pled-clav temporar.',
'resetpass-temp-password'   => 'Pled-clav temporar:',

# Special:PasswordReset
'passwordreset'                => 'Redefinir il pled-clav',
'passwordreset-text'           => "Emplenescha quest formular per retschaiver in e-mail che cuntegna ils detagls per acceder tes conto d'utilisader.",
'passwordreset-legend'         => 'Redefinir il pled-clav',
'passwordreset-disabled'       => 'La pussaivladad da redefinir il pled-clav è vegnida deactivada sin questa wiki.',
'passwordreset-pretext'        => '{{PLURAL:$1||Endatescha in dals tocs da data sutvart}}',
'passwordreset-username'       => "Num d'utilisader:",
'passwordreset-email'          => 'Adressa dad e-mail:',
'passwordreset-emailtitle'     => 'Detagls davart il conto sn {{SITENAME}}',
'passwordreset-emailtext-ip'   => "Insatgi (probablamain ti da l'adressa dad IP $1) ha dumandà in nov pled-clav per la pagina {{SITENAME}} ($4). 
{{PLURAL:$3|Il suandant conto d'utilisader è collià|Ils suandants contos d'utilisader èn colliads}} cun questa adressa dad e-mail:

$2

{{PLURAL:$3|Quest pled-clav temporar|Quests pleds-clav temporars}} èn valids {{PLURAL:$5|in di|$5 dis}}.
Ti duessas t'annunziar e tscherner ussa in nov pled-clav. Sch'enzatgi auter ha empustà quests novs pleds-clav u sche ti ta regordas puspè da tes pled-clav original e na vuls betg pli midar il pled-clav pos ti ignorar quest messadi e cuntinuar dad utilisar tes pled-clav original.",
'passwordreset-emailtext-user' => "L'utilisader $1 sin {{SITENAME}} ha dumandà in nov pled-clav per tes conto d'utilisader sin la pagina {{SITENAME}} ($4). 
{{PLURAL:$3|Il suandant conto d'utilisader è collià|Ils suandants contos d'utilisader èn colliads}} cun questa adressa dad e-mail:

$2

{{PLURAL:$3|Quest pled-clav temporar|Quests pled-clav temporars}} èn valids {{PLURAL:$5|in di|$5 dis}}.
Ti duessas t'annunziar e tscherner ussa in nov pled-clav. Sche ti na levas betg quests novs pleds-clav u sche ti ta regordas puspè da tes pled-clav original e na vuls betg pli midar il pled-clav pos ti ignorar quest messadi e cuntinuar dad utilisar tes pled-clav original.",
'passwordreset-emailelement'   => "Num d'utilisader: $1
Pled-clav temporar: $2",
'passwordreset-emailsent'      => 'In e-mail cun in nov pled-clav è vegnì tramess.',

# Edit page toolbar
'bold_sample'     => 'Text grass',
'bold_tip'        => 'Text grass',
'italic_sample'   => 'Text cursiv',
'italic_tip'      => 'Text cursiv',
'link_sample'     => 'Titel da la colliaziun',
'link_tip'        => 'Colliaziun interna',
'extlink_sample'  => 'http://www.example.com link title',
'extlink_tip'     => 'Link extern (betg emblidar il prefix http:// )',
'headline_sample' => 'Titel',
'headline_tip'    => 'Titel da segund livel',
'nowiki_sample'   => 'Scriva qua text che na duai betg vegnir formatà',
'nowiki_tip'      => 'Ignorar las formataziuns vichi',
'image_sample'    => 'Exempel.jpg',
'image_tip'       => 'Integrar ina datoteca',
'media_tip'       => 'Colliaziun ad ina datoteca',
'sig_tip'         => 'Tia suttascripziun cun data e temp',
'hr_tip'          => 'Lingia orizontala (betg utilisar savens!)',

# Edit pages
'summary'                          => 'Resumaziun:',
'subject'                          => 'Object:',
'minoredit'                        => 'Midà be bagatellas',
'watchthis'                        => 'Observar quest artitgel',
'savearticle'                      => 'Memorisar la pagina',
'preview'                          => 'Prevista',
'showpreview'                      => 'Mussar prevista',
'showlivepreview'                  => 'Prevista directa',
'showdiff'                         => 'Mussar midadas',
'anoneditwarning'                  => "Vus essas betg annunziads. Empè dal num d'utilisader vign l'adressa dad IP registrada en la historia da las versiuns.",
'anonpreviewwarning'               => "''Ti n'es betg t'annunzià. Cun memorisar vegn perquai tia adressa IP registrada en la cronologia da la pagina.''",
'missingsummary'                   => "'''Avis:''' Ti has betg inditgà ina resumaziun. 
Sche ti cliccas danovamain \"{{int:savearticle}}\" vegn tia midada memorisada senza ina tala.",
'missingcommenttext'               => 'Endatescha per plaschair ina resumaziun.',
'missingcommentheader'             => "'''Avis:''' Ti n'has betg inditgà in subject/titel per quest commentari.
Sche ti cliccas anc ina giada sin \"{{int:savearticle}}\" vegn il commentari memorisà senza in tal.",
'summary-preview'                  => 'prevista da la resumaziun:',
'subject-preview'                  => "Prevista da l'object:",
'blockedtitle'                     => 'Utilisader è bloccà',
'blockedtext'                      => "'''Tes num d'utilisader u tia adressa dad IP è vegnida bloccada.'''

''$1'' ha bloccà tai.
Il motiv inditgà è: ''$2''.

* Bloccà davent da: $8
* Bloccà enfin: $6
* Bloccada pertutga: $7

Ti pos contactar $1 u in auter [[{{MediaWiki:Grouppage-sysop}}|administratur]] per discutar questa bloccada.
Ti na pos betg utilisar la funcziun 'Trametter in e-mail a quest utilisader' senza avair inditgà in'adressa valaivla en tias [[Special:Preferences|preferenzas]] e sche ti n'ès betg vegnì bloccà per utilisar la funcziun.
Ti'adressa dad IP actuala è $3, ed la block ID è #$5.
Integrescha per plaschair tut las indicaziuns survart sche ti contacteschas insatgi.",
'autoblockedtext'                  => 'Tia adressa dad IP è vegnida bloccada automaticamain perquai ch\'ella è vegnida utilisada d\'in auter utilisader ch\'è vegnì bloccà da $1.
Il motiv inditgà è: 

:\'\'$2\'\'

* Bloccà davent da: $8
* Bloccà enfin: $6
* Bloccada pertutga: $7

Ti pos contactar $1 u in auter [[{{MediaWiki:Grouppage-sysop}}|administratur]] per discutar questa bloccada.

Ti na pos betg utilisar la funcziun "Trametter in e-mail a quest utilisader" senza avair inditgà in\'adressa valaivla en tias [[Special:Preferences|preferenzas]] e sche ti n\'ès betg vegnì bloccà per utilisar la funcziun.

Ti\'adressa dad IP actuala è $3, ed la block ID è #$5.
Integrescha per plaschair tut las indicaziuns survart sche ti contacteschas insatgi.',
'blockednoreason'                  => 'inditgà nagina raschun',
'blockedoriginalsource'            => "Il code da funtauna da '''$1''' vegn mussà sutvart:",
'blockededitsource'                => "Il code da funtauna da '''tias modificaziuns''' vid la pagina '''$1''' vegn mussa sutvart:",
'whitelistedittitle'               => "T'annunzia per modifitgar",
'whitelistedittext'                => 'Ti stos $1 per pudair modifitgar paginas.',
'confirmedittext'                  => 'Ti stos confermar tia adressa dad e-mail avant che modifitgar paginas. Inditgescha e conferma per plaschair tia adressa dad e-mail en tias [[Special:Preferences|preferenzas]].',
'nosuchsectiontitle'               => 'Betg pussaivel da chattar la secziun',
'nosuchsectiontext'                => "Ti has empruvà da modifitgar ina secziun che n'exista betg. 
Ella è eventualmain vegnida spustada u stizzada durant che ti has contemplà la pagina.",
'loginreqtitle'                    => 'Annunzia basignaivla',
'loginreqlink'                     => "t'annunziar",
'loginreqpagetext'                 => 'Ti stos $1 per vesair autras paginas.',
'accmailtitle'                     => 'Il pled-clav è vegnì tramess.',
'accmailtext'                      => "In pled-clav casual per l'utilisader [[User talk:$1|$1]] è vegnì tramess a $2.

Il pled-clav per quest nov conto po vegnir midà sin la pagina ''[[Special:ChangePassword|midar pled-clav]]'' suenter che ti t'es annunzià.",
'newarticle'                       => '(Nov)',
'newarticletext'                   => "Ti has cliccà in link ad ina pagina che exista anc betg. Per crear ina pagina, entschaiva a tippar en la stgaffa sutvart (guarda [[{{MediaWiki:Helppage}}|la pagina d'agid]] per t'infurmar).",
'anontalkpagetext'                 => "----''Quai è la pagina da discussiun per in utilisader anomim che n'ha anc betg creà in conto d'utilisader u che n'al utilisescha betg.
Perquai avain nus d'utilisar l'adressa dad IP per l'identifitgar.
Ina tala adressa dad IP po vegnir utilisada da differents utilisaders.
Sche ti es in utilisaders anonim e pensas che commentaris che na pertutgan betg tai vegnan adressads a tai, lura [[Special:UserLogin/signup|creescha in conto]] u [[Special:UserLogin|t'annunzia]] per evitar en futur che ti vegns sbaglià cun auters utilisaders.''",
'noarticletext'                    => 'Quest artitgel cuntegna actualmain nagin text.
Ti pos [[Special:Search/{{PAGENAME}}|tschertgar il term]] sin in\'autra pagina,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} tschertgar en ils logs],
u [{{fullurl:{{FULLPAGENAME}}|action=edit}} crear questa pagina]</span>.',
'noarticletext-nopermission'       => 'Questa pagina cuntegna actualmain nagin text.
Ti pos [[Special:Search/{{PAGENAME}}|tschertgar il term]] sin in\'autra pagina,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} tschertgar en ils logs]</span>.',
'userpage-userdoesnotexist'        => 'Il conto d\'utilisader "<nowiki>$1</nowiki>" n\'èxista betg.
Controllescha sch ti vuls propi crear/modiftgar questa pagina.',
'userpage-userdoesnotexist-view'   => 'Il conto d\'utilisader "$1" n\'exista betg.',
'blocked-notice-logextract'        => "Quai utilisader è bloccà actualmain. 
L'ultima endataziun dal log da bloccar vegn mussà sutvart sco referenza:",
'clearyourcache'                   => "'''Remartga''' Suenter memorisar las midadas stos ti eventualmain svidar il chache da tes navigatur per vesair las midadas.
* '''Firefox / Safari:''' tegnair ''Shift'' durant cliccar ''chargiar danovamain'', u smatgar ''Ctrl-F5'' u ''Ctrl-R'' (''Command-R'' sin in Mac)
* '''Google Chrome:''' smatgar ''Ctrl-Shift-R'' (''Command-Shift-R'' sin in Mac)
* '''Internet Explorer:''' tegnair ''Ctrl'' durant cliccar ''Refresh,'' u smatgar ''Ctrl-F5''
* '''Konqueror:''' cliccar ''Reload'' ni smatgar ''F5''
* '''Opera:''' stizzar il cache sut ''Tools → Preferences''",
'usercssyoucanpreview'             => "'''Tip:''' Utilisescha il buttun \"{{int:showpreview}}\" per testar tes nov CSS avant che memorisar.",
'userjsyoucanpreview'              => "'''Tip:''' Utilisescha il buttun \"{{int:showpreview}}\" per testar tes nov JavaScript avant che memorisar.",
'usercsspreview'                   => "'''Fa stim che quai è be ina prevista da tes CSS d'utilisader.'''
'''El n'è anc betg memorisà.'''",
'userjspreview'                    => "'''Fa stim che quai è be ina prevista da tes JavaScript d'utilisader.'''
'''El n'è anc betg memorisà.'''",
'sitecsspreview'                   => "'''Fa stim che quai è be ina prevista da quest CSS.'''
'''El n'è anc betg memorisà.'''",
'sitejspreview'                    => "'''Fa stim che quai è be ina prevista da quest JavaScript.'''
'''El n'è anc betg memorisà.'''",
'userinvalidcssjstitle'            => "'''Attenziun:''' I n'exista nagin skin \"\$1\".
Fa stim che titels da paginas persunalisadas .css u .js vegnan scrits pitschen, p. ex. {{ns:user}}:Foo/vector.css e betg {{ns:user}}:Foo/Vector.css.",
'updated'                          => '(midà)',
'note'                             => "'''Remartga:'''",
'previewnote'                      => "'''Fa stim che quai è be ina prevista.'''
Tias midadas n'èn anc betg vegnidas memorisadas!",
'previewconflict'                  => "Questa prevista visualisescha il text en il champ d'endataziun sura. Uschia vegn la pagina vesair ora, sche ti la memoriseschas ussa.",
'session_fail_preview'             => "'''Stgisa! Tia modificaziun na pudeva betg vegnir memorisada perquei che las datas da la sesida èn idas a perder.'''
Emprova per plaschair danovamain.
Sche quai na funcziuna anc adina betg, emprova da [[Special:UserLogout|partir]] ed anc ina giada t'annunziar.",
'session_fail_preview_html'        => "''Stgisa! Tia modificaziun na pudeva betg vegnir memorisada perquei che las datas da la sesida èn idas a perder.'''

''Perquai che {{SITENAME}} ha activà la pussaivlada d'utilisar HTML è la prevista betg visibla per impedir attaccas cun JavaScript.''

'''Emprova per plaschair danovamain.'''
Sche quai na funcziuna anc adina betg, emprova da [[Special:UserLogout|partir]] ed anc ina giada t'annunziar.",
'token_suffix_mismatch'            => "'''Tia modificaziun è vegnida refusada perquai che tes navigatur ha manizzà ils segns en il token da modifitgar.'''
La modificaziun è vegnida refusada per evitar ch'il cuntegn da la pagina vegnia destruì. 
Quai po capitar sche ti utiliseschas in survetsch da proxy anonim che na funcziuna betg correctamain.",
'edit_form_incomplete'             => "'''Entginas parts dal formular da modifitgar n'èn betg arrivadas cumplattamain al server; controllescha p.pl. che tias midadas èn intactas ed emprova danovamain.'''",
'editing'                          => 'Modifitgar $1',
'editingsection'                   => 'Modifitgar $1 (secziun)',
'editingcomment'                   => 'Modifitgar $1 (nova secziun)',
'editconflict'                     => 'Conflict da modifitgar: $1',
'explainconflict'                  => "Insatgi auter ha midà questa pagina dapi che ti has cumenzà a la modifitgar. 
Il champ d'endataziun sura cuntegna il text sco che la pagina vesa ora actualmain. 
Tias midadas èn mussadas en il champ d'endataziun sut. 
Ti stos integrar tias midadas en il text existent. 
'''Mo''' il text en il champ d'endataziun sura vegn memorià sche ti cliccas sin \"{{int:savearticle}}\".",
'yourtext'                         => 'Tes text',
'storedversion'                    => 'Versiun memorisada',
'nonunicodebrowser'                => "'''Attenziun: Tes navigatur na sustegna betg unicode.'''
Per che ti pos modifitgar las paginas senza ch'i dettia problems vegnan ils caracters betg ASCII mussads en tes champ d'endataziun sco codes exadecimals.",
'editingold'                       => "'''Attenziun: Ti modifitgeschas ina versiun veglia da questa pagina.'''
Sche ti la memoriseschas van tut las midadas fatgas dapi lura a perder.",
'yourdiff'                         => 'Differenzas',
'copyrightwarning'                 => "Las contribuziuns a {{SITENAME}} vegnan publitgadas confurm a la $2 (contempla $1 per ulteriurs detagls).
Sche ti na vuls betg che tias contribuziuns vegnan modifitgadas e redistribuidas, lura na las trametta betg qua.<br />
Ti garanteschas che ti has scrit tez quai u copià dad ina funtauna ch'è 'public domain' u dad in'autra funtauna libra.
'''Na trametta naginas ovras ch'èn protegidas da dretgs d'autur senza lubientscha explicita!'''",
'copyrightwarning2'                => "Fa stim che tut las contribuziuns a {{SITENAME}} pon vegnir modifitgadas, midadas u stizzadas dad auters contriuents. 
Sche ti na vuls betg che tes texts pon vegnir modifitgads, na als endatescha betg qua.<br />
Ti confermas era che ti has scrit sez quest text u al has copià d'ina funtaunda 'public domain' u semigliant libra (guarda $1 per detagls).
'''Betg trametta ovras protegidas dal dretg d'autur senza lubientscha!'''",
'longpageerror'                    => "'''Errur: Il text che ti has tramess è $1 kilobytes gronds. Quei ei pli grond ch'il maximum da $2 kilobytes. Il text na po betg vegnir memorisà. '''",
'readonlywarning'                  => "'''Attenziun: La banca da datas è vegnida bloccada per motivs da mantegniment, uschia che ti na sas betg memorisar tias modificaziuns gest ussa.'''
Per betg perder las midadas ta recummandain nus da copiar il text en in editur da text sin tes computer ed al memorisar per pli tard. 

Igl adminstratur che ha bloccà la banca da datas ha inditgà suandant motiv: $1",
'protectedpagewarning'             => "'''Attenziun: Questa pagina è vegnida bloccada, uschè che be utilisaders cun dretgs dad administraturs la pon modifitgar. '''
Sco infurmaziun vegn mussada sutvart l'ultima endataziun dal cudesch da log:",
'semiprotectedpagewarning'         => "'''Attenziun: Questa pagina è vegnida bloccada, uschè che be utilisaders registrads la pon modifitgar.'''
Sco infurmaziun vegn mussada sutvart l'ultima endataziun dal cudesch da log:",
'cascadeprotectedwarning'          => "'''Attenziun:''' Questa pagina è vegnida protegiada uschia che be utilisaders cun dretgs d'administratur la pon modifitgar. Quella protecziun è activa perquai che la pagina è integrada en suandanta{{PLURAL:$1| pagina|s paginas}} cun activà l'opziun «protecziun ertada»:",
'titleprotectedwarning'            => "'''Attenziun: Questa pagina è vegnida protegia uschia che [[Special:ListGroupRights|dretgs specifics]] èn necessaris per la crear.'''
L'ultima endataziun dal log vegn mussada sco referenza:",
'templatesused'                    => '{{PLURAL:$1|In model utilisà|Models utilisads}} sin questa pagina:',
'templatesusedpreview'             => '{{PLURAL:$1|In model utilisà|Models utilisads}} per questa prevista:',
'templatesusedsection'             => '{{PLURAL:$1|In model|Models}} utilisads en quest chapitel:',
'template-protected'               => '(bloccà)',
'template-semiprotected'           => '(mez protegidas)',
'hiddencategories'                 => 'Quest artitgel è commember da {{PLURAL:$1|1 categoria zuppentada|$1 categorias zuppentadas}}:',
'nocreatetitle'                    => 'La creaziun da novas paginas è limitada',
'nocreatetext'                     => "{{SITENAME}} ha restrinschì las pussaivladas da crear novas paginas.
Ti pos ir anavos e modifitgar ina pagina existenta, u [[Special:UserLogin|t'annunziar u registrar]].",
'nocreate-loggedin'                => "Ti n'has betg la lubientscha da crear novas paginas.",
'sectioneditnotsupported-title'    => 'La modificaziun da secziuns na vegn betg sustegnida',
'sectioneditnotsupported-text'     => 'La modificaziun da secziuns na vegn betg sustegnì da questa pagina.',
'permissionserrors'                => 'Errur da permissiun',
'permissionserrorstext'            => "Ti n'has betg la permissiun dad exequir questa acziun ord {{PLURAL:$1|il suandant movtiv|ils suandants motivs}}:",
'permissionserrorstext-withaction' => "Ti n'has betg la permissiun da $2. Quai ord {{PLURAL:$1|il suandant motiv|ils suandants motivs}}:",
'recreate-moveddeleted-warn'       => "'''Attenziun: Ti recreeschas in artitgel ch'è vegni stizzà pli baud.'''

Esi propi adattà da puspè crear questa pagina?
Qua pos guardar il log da stizzar e spustar da la pagina:",
'moveddeleted-notice'              => 'Questa pagina è vegnida stizzada. 
Ils logs da stizzar e spustar da la pagina vegnan mussads sutvart sin questa pagina.',
'log-fulllog'                      => 'Mussar il log cumplain',
'edit-hook-aborted'                => 'La modificaziun è vegnida interrutta dal hook.
Nagina decleraziun inditgada.',
'edit-gone-missing'                => "La pagina na pudeva betg vegnir actualisada. 
I para sco sch'ella fiss vegnida stizzada.",
'edit-conflict'                    => 'Conflict da modifitgar.',
'edit-no-change'                   => 'Tia modificaziun è vegnida ignorada perquai che naginas midadas èn vegnidas fatgas en il text.',
'edit-already-exists'              => "Betg pussaivel da crear ina nova pagina perquai ch'ella exista gia.",

# Parser/template warnings
'expensive-parserfunction-warning'        => "'''Attenziun:''' Questa pagina cuntegna memia bleras funcziuns dal parser cumplitgadas. 

Ti stuessas utilisar pli pauc che {{PLURAL:$2|ina tala funcziun|$2 talas funcziuns}}, ussa utiliseschas ti dentant {{PLURAL:$1|ina tala funcziun|$1 talas funcziuns}}.",
'expensive-parserfunction-category'       => 'Paginas cun memia bleras funcziuns da parser cumplitgadas',
'post-expand-template-inclusion-warning'  => "'''Attenziun:''' La grondezza dals models integrads è memia gronda.
Insaquants models vegnan betg integrads.",
'post-expand-template-inclusion-category' => 'Paginas, en las qualas la grondezza maximala da models è surpassada',
'post-expand-template-argument-warning'   => "'''Attenziun:''' Questa pagina cuntegna almain in argument d'in model che ha ina memia gronda grondezza d'expansiun.
Quests arguments vegnan ignorads.",
'post-expand-template-argument-category'  => 'Paginas che cuntegnan arguments ignorads per models',
'parser-template-loop-warning'            => 'Chattà cirquit da models: [[$1]]',
'parser-template-recursion-depth-warning' => 'Surpassa la limita da recursiun da models ($1)',
'language-converter-depth-warning'        => 'Surpassà la limita ($1) dal convertader da linguas',

# "Undo" feature
'undo-success' => 'Questa modificaziun na po betg vegnir revocada.
Controlleschan en la vista per cumparegliar sutvart sche quei è quai che ti vuls far e lura memorisescha las midadas sutvart per terminar la modificaziun.',
'undo-failure' => 'La modificaziun na pudeva betg vegnir revocada causa modificaziuns pli novas che stattan en conflict cun questa acziun.',
'undo-norev'   => "La modificaziun na pudeva betg vegnir revocada perquai ch'ella n'exista betg u è vegnida stizzada.",
'undo-summary' => 'Revocar la versiun $1 da [[Special:Contributions/$2|$2]] ([[User talk:$2|discussiun]])',

# Account creation failure
'cantcreateaccounttitle' => 'Betg pussaivel da crear il conto',
'cantcreateaccount-text' => "La creaziun da contos du'utilisader è vegnida bloccada da l'utilisader [[User:$3|$3]] per questa adressa IP ('''$1''').

Il motiv inditgà da $3 è ''$2''",

# History pages
'viewpagelogs'           => 'Guardar ils logs da questa pagina',
'nohistory'              => "Per questa pagina n'exista nagina cronologia.",
'currentrev'             => 'Versiun actuala',
'currentrev-asof'        => 'Versiun actuala dals $2, las $3 uras',
'revisionasof'           => 'Versiun dals $1',
'revision-info'          => "Quai è ina versiun veglia. Temp da la midada ''$1'' da ''$2''",
'previousrevision'       => '← versiun pli veglia',
'nextrevision'           => 'versiun pli nova →',
'currentrevisionlink'    => 'Mussar la versiun actuala',
'cur'                    => 'act',
'next'                   => 'proxim',
'last'                   => 'davosa',
'page_first'             => 'entschatta',
'page_last'              => 'fin',
'histlegend'             => 'Per vesair las differenzas tranter duas versiuns, marca ils quaderins da la versiuns che ti vul cumparegliar e clicca sin "cumparegliar las versiuns selecziunadas".
* (act) = differenzas cun la versiun actuala
* (davosa) = differenza cun la versiun precedenta
* M = Midà be bagatellas',
'history-fieldset-title' => 'tschertgar en la cronica',
'history-show-deleted'   => 'be versiuns stizzadas',
'histfirst'              => 'pli veglia',
'histlast'               => 'pli nova',
'historysize'            => '({{PLURAL:$1|1 byte|$1 bytes}})',
'historyempty'           => '(vid)',

# Revision feed
'history-feed-title'          => 'Cronologia da las versiuns',
'history-feed-description'    => 'Cronologia da versiuns per questa pagina sin questa vichi',
'history-feed-item-nocomment' => '$1 las $2',
'history-feed-empty'          => "La pagina dumandada n'exista betg. 
Eventualmain è ella vegnida stizzada u stidada da la wiki. 
Emprova da [[Special:Search|tschertgar]] novas paginas sumegliantas en la wiki.",

# Revision deletion
'rev-deleted-comment'         => '(allontanà la resumaziun da las modificaziuns)',
'rev-deleted-user'            => "(stidà num d'utilisader)",
'rev-deleted-event'           => '(stidà acziun dal log)',
'rev-deleted-user-contribs'   => "[Allontanà il num d'utilisader u l'adressa IP - zupentà la modificaziun da las contribuziuns]",
'rev-deleted-text-permission' => "Questa versiun da la pagina è vegnida '''stizzada'''.
Detagls pon vegnir chattads en il [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log da stizzar].",
'rev-deleted-text-unhide'     => "Questa versiun da la pagina è vegnida '''stizzada'''.
Detagls pon vegnir chattads en il [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log da stizzar].
Sco administratur pos ti anc adina [$1 contemplar questa versiun].",
'rev-suppressed-text-unhide'  => "Questa versiun da la pagina è vegnida '''supprimida'''.
Detagls pon vegnir chattads en il [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} log da supprimer].
Sco administratur pos ti anc adina [$1 contemplar questa versiun].",
'rev-deleted-text-view'       => "Questa versiun da la pagina è vegnida '''stizzada'''.
Sco administratur pos ti la vesair; detagls pon vegnir chattads en il [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log da stizzar].",
'rev-suppressed-text-view'    => "Questa versiun da la pagina è vegnida '''supprimida'''.
Sco administratur pos ti la vesair; detagls pon vegnir chattads en il [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}}  log da supprimer].",
'rev-deleted-no-diff'         => "Ti na pos betg guardar quest diff perquai ch'ina da las versiuns è vegnida '''stizzada'''.
Detagls pos ti chattar en [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log da stizzar].",
'rev-suppressed-no-diff'      => "Ti na pos betg guardar quest diff perquai ch'ina da las versiuns è vegnida '''stizzada'''.",
'rev-deleted-unhide-diff'     => "Ina da las versiuns da quest diff è vegnida '''stizzada'''.
Detagls pon vegnir chattads en il [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log da stizzar].
Sco administratur pos ti anc adina [$1 contemplar quest diff].",
'rev-suppressed-unhide-diff'  => "Ina da las versiuns da quest diff è vegnida '''supprimida'''.
Detagls pon vegnir chattads en il [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} log da supprimer].
Sco administratur pos ti anc adina [$1 contemplar quest diff] sche ti vuls cuntinuar.",
'rev-deleted-diff-view'       => "Ina da las versiuns da quest diff è vegnida '''stizzada'''.
Sco administratur pos ti vesair quest diff; detagls pon vegnir chattads en il [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log da stizzar].",
'rev-suppressed-diff-view'    => "Ina da las versiuns da quest diff è vegnida '''supprimida'''.
Sco administratur pos ti vesair quest diff; detagls pon vegnir chattads en il [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}}  log da supprimer].",
'rev-delundel'                => 'mussar/zuppentar',
'rev-showdeleted'             => 'mussar',
'revisiondelete'              => 'Stizzar/restituir versiuns',
'revdelete-nooldid-title'     => 'Inditgà versiun nunvalida',
'revdelete-nooldid-text'      => "Ti n'has u betg inditga ina versiun per exequir questa funcziun, la versiun n'exista betg u che ti emprovas da zuppentar la versiun actuala.",
'revdelete-nologtype-title'   => 'Inditgà nagin tip da log',
'revdelete-nologtype-text'    => "Ti n'has betg inditgà in tip da log per exequir questa acziun.",
'revdelete-nologid-title'     => 'Endataziun nunvalida dal log',
'revdelete-nologid-text'      => "Ti n'has u betg inditga ina versiun per exequir questa funcziun u che la versiun inditgada n'exista betg.",
'revdelete-no-file'           => "La datoteca specifitgada n'exista betg.",
'revdelete-show-file-confirm' => 'Es ti segir che ti vuls vesar ina versiun stizzada da la datoteca "<nowiki>$1</nowiki>" dals $2 las $3?',
'revdelete-show-file-submit'  => 'Gea',
'revdelete-selected'          => "'''{{PLURAL:$2|Versiun tschernida|Versiuns tschernidas}} da [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Elements dal log tschernì|Elements dal log tschernids}}:'''",
'revdelete-radio-set'         => 'Gea',
'revdelete-radio-unset'       => 'Na',
'revdelete-log'               => 'Motiv:',
'revdel-restore'              => 'midar la visibilitad',
'revdel-restore-deleted'      => 'versiuns stizzadas',
'revdel-restore-visible'      => 'versiuns visiblas',
'pagehist'                    => 'Istorgia da las versiuns',
'revdelete-content'           => 'Cuntegn',
'revdelete-summary'           => 'resumaziun da la midada',
'revdelete-uname'             => "num d'utilisader",
'revdelete-log-message'       => '$1 per $2 versiun{{PLURAL:$2||s}}',
'revdelete-otherreason'       => 'Auter/ulteriur motiv:',
'revdelete-reasonotherlist'   => 'Auter motiv',
'revdelete-edit-reasonlist'   => 'Modifitgar ils motivs per stizzar',
'revdelete-offender'          => 'Autur da la versiun:',

# History merging
'mergehistory'                     => 'Unir la cronologia da paginas',
'mergehistory-header'              => "Cun questa pagina speziala pos ti integrar versiuns da la cronologia d'ina pagina originala en ina pagina pli nova. 
Controllescha che questa midada mantegna la cuntinuitad istorica.",
'mergehistory-box'                 => 'Unir la cronologia da duas paginas:',
'mergehistory-from'                => "Pagina d'origin:",
'mergehistory-into'                => 'Pagina da destinaziun:',
'mergehistory-list'                => 'Versiuns che pon vegnir unidas',
'mergehistory-merge'               => 'Las suandantas versiuns da [[:$1]] pon vegnir unidas en [[:$2]].
Marchescha la versiun la quala (e tut questas avant) che duain vegnir surpigliadas.
Fa stim che navigar cun agid da las coalliziuns reinizialisescha tia tscherna.',
'mergehistory-go'                  => 'Mussar versiuns che pon vegnir unidas',
'mergehistory-submit'              => 'Unir las versiuns',
'mergehistory-empty'               => 'Naginas versiuns pon vegnir unidas.',
'mergehistory-success'             => '$3 {{PLURAL:$3|versiun|versiuns}} da [[:$1]] {{PLURAL:$3|è vegnida unida|èn vegnidas unidas}} a [[:$2]].',
'mergehistory-fail'                => 'Betg pussaivel dad unir la cronologia. Controllescha ils parameters da las paginas e datas.',
'mergehistory-no-source'           => "La pagina d'origin $1 n'exista betg.",
'mergehistory-no-destination'      => "La pagina da destinaziun $1 n'exista betg.",
'mergehistory-invalid-source'      => "La pagina d'origin sto esser in titel valaivel.",
'mergehistory-invalid-destination' => 'La pagina da destinaziun sto esser in titel valaivel.',
'mergehistory-autocomment'         => 'Unì [[:$1]] a [[:$2]]',
'mergehistory-comment'             => 'Unì [[:$1]] a [[:$2]]: $3',
'mergehistory-same-destination'    => "Las paginas d'origin e da destinaziun na dastgan betg esser las medemas",
'mergehistory-reason'              => 'Motiv:',

# Merge log
'mergelog'           => "Log d'unir",
'pagemerge-logentry' => 'unì [[$1]] a [[$2]] (versiuns enfin $3)',
'revertmerge'        => 'Revocar la fusiun',
'mergelogpagetext'   => "Sutvart è ina glista da las ultimas cronologias ch'èn vegnidas unidas cun autras.",

# Diffs
'history-title'            => 'Cronica da versiuns da "$1"',
'difference'               => '(differenza tranter versiuns)',
'difference-multipage'     => '(Differenzas tranter las paginas)',
'lineno'                   => 'Lingia $1:',
'compareselectedversions'  => 'cumparegliar las versiuns selecziunadas',
'showhideselectedversions' => 'Mussar/zuppentar las versiuns tschernidas',
'editundo'                 => 'revocar',
'diff-multi'               => '({{PLURAL:$1|Ina versiun|$1 versiuns}} {{PLURAL:$2|dad in utilisader|da $2 utilisaders}} tranter en na {{PLURAL:$1|vegn betg mussada|na vegnan betg mussadas}}.)',

# Search results
'searchresults'                    => 'Resultats da tschertga',
'searchresults-title'              => 'Resultats da tschertga per "$1"',
'searchresulttext'                 => "Per dapli infurmaziuns davart il tschertgar sin {{SITENAME}}, guarda l'[[{{MediaWiki:Helppage}}|{{int:help}}]].",
'searchsubtitle'                   => 'Ti has tschertgà \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|tut las paginas che entschevan cun "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|colliaziuns a "$1"]])',
'searchsubtitleinvalid'            => "Ti has tschertgà '''$1'''",
'toomanymatches'                   => 'Il dumber dals resultats è memia grond. Tschertga per plaschair auters terms.',
'titlematches'                     => 'Titels da paginas che correspundan',
'notitlematches'                   => 'Nagin titel correspunda',
'textmatches'                      => 'Texts da paginas che correspundan',
'notextmatches'                    => 'Nagin text correspunda',
'prevn'                            => 'davos {{PLURAL:$1|$1}}',
'nextn'                            => 'proxims {{PLURAL:$1|$1}}',
'prevn-title'                      => '{{PLURAL:$1|Ultim resultat|Ultims resultats}}',
'nextn-title'                      => '{{PLURAL:$1|Proxim resultat|Proxims resultats}}',
'shown-title'                      => 'Mussar $1 {{PLURAL:$1|resultat|resultats}} per pagina',
'viewprevnext'                     => 'Mussar ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => 'Opziuns da tschertgar',
'searchmenu-exists'                => "'''Igl exista ina pagina cun il num \"[[:\$1]] sin questa vichi\"'''",
'searchmenu-new'                   => "'''Crear la pagina \"[[:\$1]]\" sin questa vichi!'''",
'searchhelp-url'                   => 'Help:Cuntegn',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Mussar tut las paginas cun quest prefix]]',
'searchprofile-articles'           => 'Paginas da cuntegn',
'searchprofile-project'            => 'Agid e paginas dal project',
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Tut',
'searchprofile-advanced'           => 'Avanzà',
'searchprofile-articles-tooltip'   => 'Tschertgar en $1',
'searchprofile-project-tooltip'    => 'Tschertgar en $1',
'searchprofile-images-tooltip'     => 'Tschertgar datotecas',
'searchprofile-everything-tooltip' => 'Tschertgar en tut il cuntegn (inclusivamain paginas da discussiun)',
'searchprofile-advanced-tooltip'   => 'Tschertgar en ulteriurs tips da pagina',
'search-result-size'               => '$1 ({{PLURAL:$2|in pled|$2 pleds}})',
'search-result-category-size'      => '{{PLURAL:$1|1 commember|$1 commembers}} ({{PLURAL:$2|1 sutcategoria|$2 sutcategorias}}, {{PLURAL:$3|1 datoteca|$3 datotecas}})',
'search-result-score'              => 'Relevanza: $1 %',
'search-redirect'                  => '(renvià da $1)',
'search-section'                   => '(chapitel $1)',
'search-suggest'                   => 'Has ti manegià: $1',
'search-interwiki-caption'         => 'Projects sumegliants',
'search-interwiki-default'         => '$1 resultats:',
'search-interwiki-more'            => '(dapli)',
'search-mwsuggest-enabled'         => 'cun propostas',
'search-mwsuggest-disabled'        => 'naginas propostas',
'search-relatedarticle'            => 'Sumegliant',
'mwsuggest-disable'                => 'Deactivar propostas AJAX',
'searcheverything-enable'          => 'Tschertgar en tut ils tips da pagina',
'searchrelated'                    => 'sumegliant',
'searchall'                        => 'tuts',
'showingresults'                   => "Sutvart èn enfin {{PLURAL:$1|'''in''' resultat|'''$1''' resultats}} cumenzond cun il numer '''$2'''.",
'showingresultsnum'                => "Qua èn {{PLURAL:$3|'''1''' resultat|'''$3''' resultats}}, cumenzond cun il number '''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Resultat '''$1''' da '''$3'''|Resultats '''$1-$2''' da '''$3'''}} per '''$4'''",
'nonefound'                        => "'''Remartga''': Sco standard vegn be tschertga en tscherts tips da pagina.
Scriva il prefix ''all:'' avant il term che ti vuls tschertgar, per tschertgar en tut las paginas (incl. discussiuns, models etc.) u scriva directamain il prefix dal spazi da num en il qual ti vuls tschertgar.",
'search-nonefound'                 => 'Per il term tschertgà èn nagins resultats vegnids chattads.',
'powersearch'                      => 'retschertgar',
'powersearch-legend'               => 'Tschertga extendida',
'powersearch-ns'                   => 'Tschertgar en tips da pagina:',
'powersearch-redir'                => 'Mussar sviaments',
'powersearch-field'                => 'Tschertgar',
'powersearch-togglelabel'          => 'Tscherna:',
'powersearch-toggleall'            => 'Tuts',
'powersearch-togglenone'           => 'Nagins',
'search-external'                  => 'Tschertga externa',
'searchdisabled'                   => "La tschertga sin {{SITENAME}} è deactivada. 
Ti pos tschertgar en il fratemp cun Google. 
Considerescha che lur index da {{SITENAME}} po cuntegnair datas ch'èn betg pli actualas.",

# Quickbar
'qbsettings'               => 'Glista laterala',
'qbsettings-none'          => 'Nagins',
'qbsettings-fixedleft'     => 'Sanester, fixà',
'qbsettings-fixedright'    => 'Dretg, fixà',
'qbsettings-floatingleft'  => 'Sanester, flottand',
'qbsettings-floatingright' => 'Dretg, flottand',

# Preferences page
'preferences'                 => 'Preferenzas',
'mypreferences'               => 'Mias preferenzas',
'prefs-edits'                 => 'Dumber da las modificaziuns:',
'prefsnologin'                => "Betg t'annunzià",
'prefsnologintext'            => 'Ti stos esser <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} t\'annunzià]</span> per midar tias preferenzas.',
'changepassword'              => 'Midar pled-clav',
'prefs-skin'                  => 'Skin',
'skin-preview'                => 'Prevista',
'datedefault'                 => 'Nagina preferenza',
'prefs-datetime'              => 'Data e temp',
'prefs-personal'              => "Profil da l'utilisader",
'prefs-rc'                    => 'Ultimas midadas',
'prefs-watchlist'             => "Glista d'observaziun",
'prefs-watchlist-days'        => "Dumber dals dis che vegnan inditgads sin la glista d'observaziun:",
'prefs-watchlist-days-max'    => 'Maximalmain 7 dis',
'prefs-watchlist-edits'       => 'Dumber da las midadas mussadas en la glista dad observaziun extendida:',
'prefs-watchlist-edits-max'   => 'Dumber maximal: 1000',
'prefs-watchlist-token'       => "Token da la glista d'observaziun",
'prefs-misc'                  => 'Different',
'prefs-resetpass'             => 'Midar il pled clav',
'prefs-email'                 => 'Opziuns dad e-mail',
'prefs-rendering'             => 'Cumparsa',
'saveprefs'                   => 'memorisar',
'resetprefs'                  => 'remetter las preferenzas (reset)',
'restoreprefs'                => 'Restituir tut las preferenzas da standard',
'prefs-editing'               => 'Modifitgar',
'prefs-edit-boxsize'          => 'Grondezza da la fanestra da modifitgar',
'rows'                        => 'Lingias:',
'columns'                     => 'Colonnas:',
'searchresultshead'           => 'Tschertga',
'resultsperpage'              => 'resultats per pagina:',
'stub-threshold-disabled'     => 'Deactivà',
'recentchangesdays'           => 'Dumber da dis che duain vegnir mussads en las ultimas midadas:',
'recentchangesdays-max'       => 'Maximalmain $1 {{PLURAL:$1|di|dis}}',
'recentchangescount'          => 'Dumber da modificaziuns che duai vegnir mussà sco standard:',
'savedprefs'                  => 'Tias preferenzas èn vegnidas memorisadas.',
'timezonelegend'              => "Zona d'urari:",
'localtime'                   => 'Temp local:',
'timezoneuseserverdefault'    => 'Utilisar il standard dal wiki ($1)',
'timezoneuseoffset'           => 'Auter (inditgar la differenza)',
'timezoneoffset'              => 'Differenza¹:',
'servertime'                  => 'Temp dal server:',
'guesstimezone'               => 'Surprender dal navigatur',
'timezoneregion-africa'       => 'Africa',
'timezoneregion-america'      => 'America',
'timezoneregion-antarctica'   => 'Antarctica',
'timezoneregion-arctic'       => 'Arctica',
'timezoneregion-asia'         => 'Asia',
'timezoneregion-atlantic'     => 'Ocean atlantic',
'timezoneregion-australia'    => 'Australia',
'timezoneregion-europe'       => 'Europa',
'timezoneregion-indian'       => 'Ocean Indic',
'timezoneregion-pacific'      => 'Ocean pacific',
'allowemail'                  => 'Retschaiver e-mails dad auters utilisaders',
'prefs-searchoptions'         => 'Opziuns da tschertgar',
'prefs-namespaces'            => 'Tips da pagina',
'defaultns'                   => 'Uschiglio tschertgar en quests tips da pagina:',
'default'                     => 'Standard',
'prefs-files'                 => 'Datotecas',
'prefs-custom-css'            => 'CSS persunalisà',
'prefs-custom-js'             => 'JavaScript persunalisà',
'prefs-reset-intro'           => 'Ti pos utilisar questa pagina per restituir las valurs da standard da questa pagina per tias preferenzas. 
Questa operaziun na po betg vegnir revocada.',
'prefs-emailconfirm-label'    => 'Confirmaziun per e-mail:',
'prefs-textboxsize'           => 'Grondezza da la fanestra da modifitgar',
'youremail'                   => 'Adressa dad e-mail:',
'username'                    => "Num d'utilisader:",
'uid'                         => "ID da l'utilisader:",
'prefs-memberingroups'        => 'Commember {{PLURAL:$1|da la gruppa|da las gruppas}}:',
'prefs-registration'          => 'Temp da registraziun:',
'yourrealname'                => 'Num real:',
'yourlanguage'                => 'Lingua:',
'yourvariant'                 => 'varianta',
'yournick'                    => 'Signatura:',
'prefs-help-signature'        => 'Commentaris sin paginas da discussiun duessan vegnir signadas cun "<nowiki>~~~~</nowiki>". Quests segns vegnan lura convertids en tia signatura ed la data.',
'badsig'                      => 'Signatura invalida. 
Controllai ils tags da HTML.',
'badsiglength'                => 'Vossa signatura è memia lunga. 
Ella na dastga betg esser pli lunga che $1 {{PLURAL:$1|caracter|caracters}}.',
'yourgender'                  => 'Schlattaina',
'gender-unknown'              => 'Betg inditgà',
'gender-male'                 => 'Masculin',
'gender-female'               => 'Feminin',
'prefs-help-gender'           => 'Opziunal: vegn utilisà per che la software ta po titular tenor schlattaina.
Questa infurmaziun è publica.',
'email'                       => 'E-mail',
'prefs-help-realname'         => "Opziun: Qua pos inditgar in surnum che vegn inditga empè da tes num d'utilisader en tias suttascripziuns cun '''<nowiki>--~~~~</nowiki>'''.",
'prefs-help-email'            => "L'adressa dad e-mail è opziunala, pussibilitescha dentant da trametter in nov pled-clav en cass da sperdita.",
'prefs-help-email-required'   => "Inditgar in'adressa dad e-mail è obligatoric.",
'prefs-info'                  => 'Infurmaziuns da basa',
'prefs-i18n'                  => 'Internaziunalisaziun',
'prefs-signature'             => 'Signatura',
'prefs-dateformat'            => 'Format da la data',
'prefs-timeoffset'            => 'Differenza da temp',
'prefs-advancedediting'       => 'Opziuns extendidas',
'prefs-advancedrc'            => 'Opziuns extendidas',
'prefs-advancedrendering'     => 'Opziuns extendidas',
'prefs-advancedsearchoptions' => 'Opziuns extendidas',
'prefs-advancedwatchlist'     => 'Opziuns extendidas',
'prefs-displayrc'             => 'Opziuns da visualisar',
'prefs-displaysearchoptions'  => 'Opziuns da visualisar',
'prefs-displaywatchlist'      => 'Opziuns da visualisar',
'prefs-diffs'                 => 'Cumparegliaziun da versiuns',

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => "L'adressa da e-mail para dad esser valida",
'email-address-validity-invalid' => 'Endatescha ina adressa dad e-mail valida',

# User rights
'userrights'                   => "Administraziun da dretgs d'utilisaders",
'userrights-lookup-user'       => "Administrar gruppas d'utilisaders",
'userrights-user-editname'     => "Inditgescha in num d'utilisader:",
'editusergroup'                => "Modifitgar las gruppas d'utilisaders",
'editinguser'                  => "Midar ils dretgs da l'utilisader '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => "Modifitgar las gruppas d'utilisaders",
'saveusergroups'               => "Memorisar questas gruppas d'utilisaders",
'userrights-groupsmember'      => 'Commember da:',
'userrights-groupsmember-auto' => 'Commember implicit da:',
'userrights-groups-help'       => "Ti pos modifitgar las gruppas tar las qualas quest utilisader tutga:
* Ina chaschetta activada vul dir che l'utilisader fa part da questa gruppa.
* Ina chaschetta betg activada vul dir che l'utilisader na fa betg part da questa gruppa. 
* Ina * signifitgescha che ti na pos betg allontanar la gruppa sche ti la has agiuntà (u viceversa).",
'userrights-reason'            => 'Motiv:',
'userrights-no-interwiki'      => "Ti n'has betg la permissiun da midar dretgs d'utilisaders sin autras wikis.",
'userrights-nodatabase'        => "La banca da datas $1 n'exista betg u n'è betg locala.",
'userrights-nologin'           => "Ti stos [[Special:UserLogin|t'annunziar]] cun in conto d'aministratur per modifitgar ils dretgs d'utilisader.",
'userrights-notallowed'        => "Tes conto d'utilisader n'ha betg la permissiun dad agiuntar dretgs d'utilisaders.",
'userrights-changeable-col'    => 'Gruppas che ti pos modifitgar',
'userrights-unchangeable-col'  => 'Gruppas che ti nas pos betg modifitgar',

# Groups
'group'               => 'Gruppa:',
'group-user'          => 'Utilisaders',
'group-autoconfirmed' => 'Utilisaders confermads automaticamain',
'group-bot'           => 'Bots',
'group-sysop'         => 'Administraturs',
'group-bureaucrat'    => 'Birocrat',
'group-suppress'      => 'Oversights',
'group-all'           => '(tuts)',

'group-user-member'          => 'Utilisader',
'group-autoconfirmed-member' => 'Utilisader confermà automaticamain',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Administratur',
'group-bureaucrat-member'    => 'Birocrat',
'group-suppress-member'      => 'Oversight',

'grouppage-user'          => '{{ns:project}}:Utilisaders',
'grouppage-autoconfirmed' => '{{ns:project}}:Utilisaders confermads automaticamain',
'grouppage-bot'           => '{{ns:project}}:Bots',
'grouppage-sysop'         => '{{ns:project}}:Administraturs',
'grouppage-bureaucrat'    => '{{ns:project}}:Birocrats',
'grouppage-suppress'      => '{{ns:project}}:Supervisurs',

# Rights
'right-read'                  => 'Leger paginas',
'right-edit'                  => 'Modifitgar paginas',
'right-createpage'            => 'Crear paginas (danor paginas da discussiun)',
'right-createtalk'            => 'Crear paginas da discussiun',
'right-createaccount'         => "Crear novs contos d'utilisader",
'right-minoredit'             => 'Marcar modificaziuns sco pitschnas',
'right-move'                  => 'Spustar paginas',
'right-move-subpages'         => 'Spustar paginas cun las subpaginas',
'right-move-rootuserpages'    => "Spustar las paginas principalas d'utilisaders",
'right-movefile'              => 'Spustar datotecas',
'right-suppressredirect'      => 'Impedir da crear renviaments cun spustar paginas',
'right-upload'                => 'Chargiar si datotecas',
'right-reupload'              => 'Recuvrir (surscriver) datotecas',
'right-reupload-own'          => "Recuvrir (surscriver) datotecas existentas ch'èn vegnidas chargiadas si da quest utilisader sez",
'right-reupload-shared'       => "Recuvrir (surscriver) localmain ina datoteca ch'è disponibla en in repositori communabel",
'right-upload_by_url'         => "Chargiar si datotecas d'ina URL",
'right-purge'                 => 'Svidar il cache per ina pagina senza confermaziun',
'right-autoconfirmed'         => 'Modifitgar paginas mez protegidas',
'right-bot'                   => 'Vegnir tractà sco process automatic',
'right-nominornewtalk'        => "Midadas pitschnas sin paginas da discussiun na mainan betg ad in avis 'nova novitad' per l'utilisader",
'right-apihighlimits'         => "Utilisar limitas pli autas per dumondas entras l'API",
'right-writeapi'              => "Utilisar l'API per scriver",
'right-delete'                => 'Stizzar paginas',
'right-bigdelete'             => 'Stizzar paginas cun ina gronda cronologia',
'right-deleterevision'        => 'Stizzar e restituir versiuns specificas da paginas',
'right-deletedhistory'        => 'Vesair endataziuns da la cronologia stizzadas, senza il text assozià',
'right-deletedtext'           => 'Vesair text stizzà e midadas tranter versiuns stizzadas',
'right-browsearchive'         => 'Tschertgar paginas stizzadas',
'right-undelete'              => 'Restituir ina pagina',
'right-suppressrevision'      => "Examinar e restituir versiuns ch'èn era betg vesaivlas per ils administraturs",
'right-suppressionlog'        => 'Vesair logs privats',
'right-block'                 => "Impedir utilisaders ch'els pon betg pli modifitgar",
'right-blockemail'            => "Impedir utilisaders ch'els pon trametter e-mails",
'right-hideuser'              => "Bloccar e zuppentar in num d'utilisader",
'right-ipblock-exempt'        => "N'è betg pertutgà da bloccadas dad IP, bloccadas automaticas e bloccadas da blocs d'adressas dad IP",
'right-proxyunbannable'       => "N'è betg pertutgà da bloccadas automaticas da proxys",
'right-unblockself'           => 'Debloccar sasez',
'right-protect'               => 'Midar il livel da protecziun e modifitgar paginas protegidas',
'right-editprotected'         => 'Modifitgar paginas protegidas (senza protecziun ertada)',
'right-editinterface'         => "Modifitgar l'interfascha per l'utilisader",
'right-editusercssjs'         => "Modifitgar datotecas da CSS e JavaScript d'auters utilisaders",
'right-editusercss'           => "Modifitgar datotecas da CSS d'auters utilisaders",
'right-edituserjs'            => "Modifitgar datotecas da JavaScript d'auters utilisaders",
'right-rollback'              => "Revocar svelt las modificaziuns da l'ultim utilisader che ha modifitgà ina pagina particulara.",
'right-noratelimit'           => "N'è betg pertutgà da limitas dad acziuns",
'right-import'                => "Importar paginas d'auters wikis",
'right-importupload'          => "Importar paginas d'ina datoteca chargiada si",
'right-patrol'                => "Marcar modificaziuns d'auters sco controlladas",
'right-autopatrol'            => 'Marcar sias atgnas modificaziuns automaticamain sco controlladas',
'right-unwatchedpages'        => 'Vesair ina glista da las paginas betg contempladas',
'right-trackback'             => 'Trametter in trackback',
'right-mergehistory'          => 'Unir la cronologia da questa pagina',
'right-userrights'            => 'Modifitgar ils dretgs dals utilisaders',
'right-userrights-interwiki'  => "Modifitgar ils dretgs d'utilisaders sin autras wikis",
'right-siteadmin'             => 'Bloccar u debloccar la banca da datas',
'right-override-export-depth' => 'Exportar paginas inclusivamain paginas colliadas fin ad ina profunditad da 5',
'right-sendemail'             => 'Trametter e-mails ad auters utilisaders',

# User rights log
'rightslog'                  => "Log dals dretgs d'utilisader",
'rightslogtext'              => "Quai è il log da las midadas en ils dretgs d'utilisaders.",
'rightslogentry'             => 'midar la commembranza da $1 davent da $2 a $3',
'rightslogentry-autopromote' => 'è vegnì promovì automaticamain da $2 $3',
'rightsnone'                 => '(nagins)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'leger questa pagina',
'action-edit'                 => 'modifitgar questa pagina',
'action-createpage'           => 'crear ina pagina',
'action-createtalk'           => 'crear ina pagina da discussiun',
'action-createaccount'        => "Crear quest conto d'utilisader",
'action-minoredit'            => 'marcar sco pitschna midada',
'action-move'                 => 'spustar questa pagina',
'action-move-subpages'        => 'spustar questa pagina e sias sutpaginas',
'action-move-rootuserpages'   => "spustar la pagina principala d'utilisaders",
'action-movefile'             => 'spustar questa datoteca',
'action-upload'               => 'chargiar si questa datoteca',
'action-reupload'             => 'surscriver questa datoteca existenta',
'action-reupload-shared'      => 'recuvrir (surscriver) questa datoteca en in repositori communabel',
'action-upload_by_url'        => "chargiar si quest datoteca d'ina URL",
'action-writeapi'             => "utilisar l'API per scriver",
'action-delete'               => 'stizzar questa pagina',
'action-deleterevision'       => 'stizzar questa versiun',
'action-deletedhistory'       => 'vesari la glistda da las versiuns stizzadas da questa pagina',
'action-browsearchive'        => 'tschertgar paginas stizzadas',
'action-undelete'             => 'restituir questa pagina',
'action-suppressrevision'     => 'vesair e restituir questa versiun zuppentada',
'action-suppressionlog'       => 'vesair quest log privat',
'action-block'                => "bloccar quest utilisader ch'el na po betg pli modifitgar",
'action-protect'              => 'midar il livel da protecziun da questa pagina',
'action-import'               => "importar questa pagina d'in auter wiki",
'action-importupload'         => "importar questa pagina d'ina datoteca chargiada si",
'action-patrol'               => "marcar modificaziuns d'auters sco controlladas",
'action-autopatrol'           => 'marcar tias modificaziuns sco controlladas',
'action-unwatchedpages'       => 'vesair la glista da las paginas betg contempladas',
'action-trackback'            => 'trametter in trackback',
'action-mergehistory'         => 'unir la cronologia da questa pagina',
'action-userrights'           => 'modifitgar ils dretgs dals utilisaders',
'action-userrights-interwiki' => "modifitgar ils dretgs d'utilisaders sin autras wikis",
'action-siteadmin'            => 'bloccar u debloccar la banca da datas',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|midada|midadas}}',
'recentchanges'                     => 'Ultimas midadas',
'recentchanges-legend'              => 'Opziuns per las ultimas midadas',
'recentchangestext'                 => "Sin questa pagina pos ti suandar las ultimas midadas sin '''{{SITENAME}}'''.",
'recentchanges-feed-description'    => 'Suonda las ultimas midadas en la wiki cun quet feed.',
'recentchanges-label-newpage'       => 'Questa modificaziun ha creà ina nova pagina',
'recentchanges-label-minor'         => 'Quai è ina pitschna modificaziun',
'recentchanges-label-bot'           => 'Questa modificaziun è vegnida exequida dad in bot',
'recentchanges-label-unpatrolled'   => "Questa midada n'è anc betg vegnida controllada",
'rcnote'                            => "Sutvart {{PLURAL:$1|è '''ina''' midada|èn las ultimas '''$1''' midadass}} {{PLURAL:$2|da l'ultim di|dals ultims '''$2''' dis}}, versiun dals  $4 $5.",
'rcnotefrom'                        => "Midadas dapi '''$2''' (maximalmain '''$1''' vegnan mussads).",
'rclistfrom'                        => 'Mussar las novas midadas entschavend cun $1',
'rcshowhideminor'                   => '$1 midadas pitschnas',
'rcshowhidebots'                    => '$1 bots',
'rcshowhideliu'                     => '$1 utilisaders annunziads',
'rcshowhideanons'                   => '$1 utilisaders anonims',
'rcshowhidepatr'                    => '$1 midadas controlladas',
'rcshowhidemine'                    => '$1 mias midadas',
'rclinks'                           => 'Mussar las davosas $1 midadas dals ultims $2 dis<br />$3',
'diff'                              => 'diff',
'hist'                              => 'ist',
'hide'                              => 'Zuppentar',
'show'                              => 'Mussar',
'minoreditletter'                   => 'P',
'newpageletter'                     => 'N',
'boteditletter'                     => 'B',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|utilisader|utilisaders}} observeschan quest artitgel]',
'rc_categories'                     => 'Be paginas ord las categorias (seperar cun "|")',
'rc_categories_any'                 => 'Tuts',
'newsectionsummary'                 => 'Nov chapitel /* $1 */',
'rc-enhanced-expand'                => 'Mussar detagls (JavaScript è necessari)',
'rc-enhanced-hide'                  => 'Zuppentar detagls',

# Recent changes linked
'recentchangeslinked'          => 'Midadas sin paginas cun colliaziuns',
'recentchangeslinked-feed'     => 'midadas sin paginas colliadas',
'recentchangeslinked-toolbox'  => 'midadas sin paginas colliadas',
'recentchangeslinked-title'    => 'Midadas en artitgels ch\'èn colliads cun "$1"',
'recentchangeslinked-noresult' => 'Naginas midadas sin artitgels collads durant la perioda endatada.',
'recentchangeslinked-summary'  => "Quest è ina glista da las midadas ch'èn vegnidas fatgas da curt en artitgels ch'èn colliads cun ina pagina specifica (ni en commembers d'ina categoria specifica).
Paginas sin [[Special:Watchlist|tia glista d'observaziun]] èn '''grassas'''.",
'recentchangeslinked-page'     => 'Num da la pagina:',
'recentchangeslinked-to'       => 'Mussar midadas da paginas che han ina colliaziun a questa pagina',

# Upload
'upload'                      => 'Chargiar si ina datoteca',
'uploadbtn'                   => 'Chargiar si la datoteca',
'reuploaddesc'                => 'Enavos tar la pagina da chargiar si.',
'upload-tryagain'             => 'Trametter la descripziun da la datoteca midada',
'uploadnologin'               => "Betg t'annunzià",
'uploadnologintext'           => "Ti stos [[Special:UserLogin|t'annunziar]] per chargiar si datotecas.",
'upload_directory_missing'    => "L'ordinatur per chargiar viaden ($1) n'exista betg e na pudeva betg vegnir creà dal server.",
'upload_directory_read_only'  => "L'ordinatur per chargiar viaden ($1) na po betg vegnir modifitgà dal server.",
'uploaderror'                 => 'Errur cun chargiar si',
'upload-recreate-warning'     => "'''Attenziun: Ina datoteca cun quest num è vegnida stizzada u spustada.'''

Il log da stizzar e spustar vegn mussà sutvart:",
'uploadtext'                  => "Utilisescha quest formular per chargiar si datotecas.
Per contemplar u tschertgar datotecas gia chargiada si, visita la pagina [[Special:FileList|glista da datotecas chargiadas si]]. Tut las datotecas che vegnan chargiadas si èn era notads en il [[Special:Log/upload|log da chargiar si]], quellas ch'èn vegnidas stizzadas en il [[Special:Log/delete|log dal stizzar]].

Per integrar ina datoteca en in artitgel pos ti per exempel duvrar in dals suandants cumonds:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>''' per utilisar la versiun cumplaina da la datoteca
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|alt text]]</nowiki></tt>''' per utilisar in maletg da la ladezza da 200 pixels en in champ da la vart sanestra cun la descripziun 'alt text'
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>''' per directamain far ina colliaziun a la datoteca senza integrar la datoteca",
'upload-permitted'            => 'Tips da datotecas lubids: $1.',
'upload-preferred'            => 'Tips da datotecas preferids: $1.',
'upload-prohibited'           => 'Tips da datotecas betg lubids: $1.',
'uploadlog'                   => 'Log dal chargiar si',
'uploadlogpage'               => 'Log da chargiar si',
'uploadlogpagetext'           => "Quai è ina glista da las datotecas ch'èn vegnidas chargiadas si sco ultimas.
Guarda era la [[Special:NewFiles|galleria da novas datotecas]] per ina survista pli visuala.",
'filename'                    => 'Num da datoteca',
'filedesc'                    => 'Resumaziun',
'fileuploadsummary'           => 'Resumaziun:',
'filereuploadsummary'         => 'Midadas da la datoteca:',
'filestatus'                  => "Status dals dretgs d'autur:",
'filesource'                  => 'Funtauna:',
'uploadedfiles'               => 'Datotecas chargiadas si',
'ignorewarning'               => "Ignorar l'avertiment e memorisar la datoteca",
'ignorewarnings'              => 'Ignorar tut ils avertiments (Warnung)',
'minlength1'                  => 'Nums da datotecas ston esser almain in bustab lung.',
'illegalfilename'             => 'Il num da datoteca "$1" cuntegna almain in segn betg lubì. Endatescha in\'auter num ed emprova danovamain da chargiar si la datoteca.',
'badfilename'                 => 'Midà num da la datoteca a "$1".',
'filetype-mime-mismatch'      => 'L\'extensiun dal num da datoteca ".$1" na correspunda betg al tip da MIME scuvrì en la datoteca ($2).',
'filetype-badmime'            => 'Datotecas dal tip da MIME "$1" na dastgan betg vegnir chargiads si.',
'filetype-bad-ie-mime'        => "Betg pussaivel da chargiar si questa datoteca perquai che l'Internet Explorar la determinass sco «$1», tge ch'è in tip betg lubì e potenzialmain privlus.",
'filetype-unwanted-type'      => "'''\".\$1\"''' è in tip da datoteca nungiavischà.
Nus preferin datotecas {{PLURAL:\$3|dal tip|dals tips}} \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' {{PLURAL:\$4|n'è betg in tip da datoteca lubì|n'èn betg tips da datoteca lubids}}.
Lubidas èn datotecas {{PLURAL:\$3|dal tip|dals tips}} \$2.",
'filetype-missing'            => 'La datoteca n\'ha nagina extensiun (p.ex. ".jpg").',
'empty-file'                  => 'La datoteca tramessa è vida.',
'file-too-large'              => 'La datoteca tramessa è memia gronda.',
'filename-tooshort'           => 'Il num da datoteca è memia curt.',
'filetype-banned'             => 'Quest tip da datoteca è scumandà.',
'verification-error'          => "Questa datoteca n'è betg passà cun success la verificaziun da datotecas.",
'illegal-filename'            => "Il num da datoteca n'è betg lubì.",
'overwrite'                   => "Recuvrir (surscriver) datotecas existentas n'è betg lubì.",
'unknown-error'               => 'Ina errur nunenconuschenta è capitada.',
'tmp-create-error'            => 'Betg pussaivel da crear la datoteca temporara.',
'tmp-write-error'             => 'Errur cun scriver la datoteca temporara.',
'large-file'                  => "Igl è recumandà che las datotecas n'èn betg pli grondas che $1;
questa datoteca è $2 gronda.",
'largefileserver'             => 'Questa datoteca è pli gronda che la grondezza maximala configurada.',
'emptyfile'                   => "La datoteca che ti has chargià si para dad esser vida.
Quai po esser pervia d'in sbagl da tippar en il num da datoteca.
Controllescha sche ti vuls propi chargiar si questa datoteca.",
'fileexists'                  => "Ina datoteca cun quest num exista gia. Controllescha '''<tt>[[:$1]]</tt>''' sche ti n'es betg segir sche ti vuls midar ella.
[[$1|thumb]]",
'filepageexists'              => "La pagina da descripziun per questa datoteca è vegnida creada ils '''<tt>[[:$1]]</tt>''' ma i n'exista anc nagina datoteca cun quest num. 
La resumaziun che ti endatescha na vegn betg a cumparair sin la pagina da descripziun.
Per che tia resumaziun cumpara là, stos ti modifitar manualmain la pagina da descripziun.
[[$1|thumb]]",
'fileexists-extension'        => "I exista gia ina datoteca cun in num sumegliant: [[$2|thumb]]
* Num da la datoteca che duai vegnir chargiada si: '''<tt>[[:$1]]</tt>'''
* Num da la datoteca ch'exista gia: '''<tt>[[:$2]]</tt>'''
Tscherna per plaschair in auter num.",
'fileexists-thumbnail-yes'    => "Quest maletg para dad esser in maletg da grondezza reducida ''(Maletg da prevista)''.
[[$1|thumb]]
Controllescha per plaschair la datoteca ''<tt>[[:$1]]</tt>'''.
Sche la datoteca menziunada survart è il medem maletg en grondezza originala n'èsi betg necessari da chargiar si in maletg da pervista.",
'file-thumbnail-no'           => "Il num da la datoteca cumenza cun '''<tt>$1</tt>''', perquai para quai dad esser in maletg da grondezza reducida ''(Maletg da prevista)''.
Controllescha sche ti has era il maletg en grondezza originala e chargia si quel sut il num original.",
'fileexists-forbidden'        => 'I exista gia ina datoteca cun quest num che na po betg vegnir recuvrida. 
Sche ti vuls anc adina chargiar si tia datoteca, turna per plaschair anavos e tscherna in auter nu. 
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Ina datoteca cun quest num exista gia en il repositori da datotecas cundividì.
Sche ti vuls anc adina chargiar si tia datoteca, turna anavos e tscherna in auter num.
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Questa datoteca è in duplicat da {{PLURAL:$1|suandanta datoteca|suandantas datotecas}}:',
'file-deleted-duplicate'      => 'Ina daoteca identica a questa ([[:$1]]) è vegnida stizzada pli baud.
Ti duessas controllar la cronologia da stizzar da questa datoteca avant che puspè chargiar si ella.',
'uploadwarning'               => 'Attenziun',
'uploadwarning-text'          => 'Mida per plaschair la descripziun da la datoteca sutvart ed emprova danovamain.',
'savefile'                    => 'Memorisar la datoteca',
'uploadedimage'               => '"[[$1]]" è vengì chargià si',
'overwroteimage'              => 'ha chargià si ina nova versiun da "[[$1]]"',
'uploaddisabled'              => 'Chargiar si è deactivà.',
'uploaddisabledtext'          => 'La funcziun da chargiar si datotecas è deactivada.',
'php-uploaddisabledtext'      => "La funcziun da chargiar si datotecas è deactivada en PHP. 
Controllescha per plaschair l'opziun <code>file_uploads</code>.",
'uploadscripted'              => 'Questa datoteca cuntegna HTML u code da scripts che pudess vegnir exequì per sbagl dal navigatur.',
'uploadvirus'                 => 'La datoteca cuntegna in virus! Detagls: $1',
'upload-source'               => 'Datoteca da funtauna',
'sourcefilename'              => "Num da la datoteca d'origin:",
'sourceurl'                   => 'URL da funtauna:',
'destfilename'                => 'Num da la datoteca da destinaziun:',
'upload-maxfilesize'          => 'Grondezza da datoteca maximala: $1',
'upload-description'          => 'Descripziun da la datoteca',
'upload-options'              => 'Opziuns per chargiar si',
'watchthisupload'             => 'Observar questa datoteca',

'upload-proto-error'        => 'Protocol nuncorrect',
'upload-too-many-redirects' => 'La URL ha cuntegnì memia blers renviaments',
'upload-unknown-size'       => 'Grondezza nunenconuschenta',
'upload-http-error'         => 'In sbagl da HTTP è capità: $1',

# img_auth script messages
'img-auth-accessdenied' => "Refusà l'access",
'img-auth-nopathinfo'   => "PATH_INFO manca.
Tes server n'è betg configurà per surdar questa infurmaziun.
I basa forsa sin CGI è na sustegna betg img_auth.
Consultescha http://www.mediawiki.org/wiki/Manual:Image_Authorization.",
'img-auth-nologinnWL'   => 'Ti n\'ès betg t\'annunzia ed "$1" n\'è betg sin la glista alva.',
'img-auth-nofile'       => 'Datoteca "$1" n\'exista betg.',

# HTTP errors
'http-invalid-url'      => 'URL nunvalida: $1',
'http-invalid-scheme'   => 'URLs dal schema "$1" na vegnan betg sustegnidas.',
'http-request-error'    => "La damonda HTTP n'è betg reussida causa ina errur nunenconuschenta.",
'http-read-error'       => 'Sbagl da leger HTTP.',
'http-timed-out'        => 'Surpassà il temp durant la dumanda HTTP.',
'http-host-unreachable' => "Betg pussaivel da cuntanscher l'URL.",
'http-bad-status'       => 'Durant la dumonda HTTP è ina errur capitada: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => "Betg pussaivel da cuntanscher l'URL",
'upload-curl-error6-text'  => "La URL inditgada na pudeva betg vegnir cuntanschida. 
Controllescha che l'URL è correcta e che la destinaziun è online.",
'upload-curl-error28'      => 'Surpassà il temp cun chargiar si',
'upload-curl-error28-text' => "La pagina dovra memia bler temp per rispunder. 
Controllescha sche la pagina è online, spetga in tempin ed emprova danovamain. 
Eventualmain stos ti empruvar danovamain d'in temp ch'i marscha pli pac.",

'license'            => 'Licenza:',
'license-header'     => 'Licenza',
'nolicense'          => 'Tschernì nagina',
'license-nopreview'  => '(nagina prevista è disponibla)',
'upload_source_url'  => ' (ina URL valida ed accessibla publicamain)',
'upload_source_file' => ' (ina datoteca sin tes computer)',

# Special:ListFiles
'listfiles-summary'     => "Questa pagina speziala mussa tut las datotecas ch'èn vegnidas chargiada si.
Sche la pagina vegn filtrada tenor utilisaders vegnan be datotecas nua che l'utilisader ha chargià si la pli nova versiun da la datotecas mussadas.",
'listfiles_search_for'  => 'Tschtgar tenor il num da dateoca:',
'imgfile'               => 'datoteca',
'listfiles'             => 'Glista dals maletgs',
'listfiles_thumb'       => 'Maletg da prevista',
'listfiles_date'        => 'Data',
'listfiles_name'        => 'Num',
'listfiles_user'        => 'Utilisader',
'listfiles_size'        => 'Grondezza',
'listfiles_description' => 'Descripziun',
'listfiles_count'       => 'Versiuns',

# File description page
'file-anchor-link'          => 'Datoteca',
'filehist'                  => 'Istorgia da las versiuns',
'filehist-help'             => 'Clicca sin ina data/temps per vesair la versiun da lura.',
'filehist-deleteall'        => 'Stizzar tut las versiuns',
'filehist-deleteone'        => 'Stizzar questa versiun',
'filehist-revert'           => 'reinizialisar',
'filehist-current'          => 'actual',
'filehist-datetime'         => 'data/temp',
'filehist-thumb'            => 'Maletg da prevista',
'filehist-thumbtext'        => 'Maletg da prevista per la versiun dals $2 las $3 uras',
'filehist-nothumb'          => 'Nagin maletg da prevista',
'filehist-user'             => 'Utilisader',
'filehist-dimensions'       => 'dimensiuns',
'filehist-filesize'         => 'grondezza da datoteca',
'filehist-comment'          => 'commentari',
'filehist-missing'          => 'Datoteca manca',
'imagelinks'                => 'Paginas che cuntegnan la datoteca',
'linkstoimage'              => '{{PLURAL:$1|La suandanta pagina è colliada|Las suandantas $1 paginas èn colliadas}} cun questa datoteca:',
'nolinkstoimage'            => 'Naginas paginas mussan sin questa datoteca.',
'sharedupload'              => 'Quai è ina datoteca da $1 e vegn eventualmain utilisada dad auters projects.',
'filepage-nofile'           => "I n'exista nagina datoteca cun quest num.",
'filepage-nofile-link'      => "I n'exista nagina datoteca cun quest num, ti la pos dentant [$1 chargiar si].",
'uploadnewversion-linktext' => 'Chargiar si ina nova versiun da questa datoteca',
'shared-repo-from'          => 'da $1',
'shared-repo'               => 'in repositori communabel',

# File reversion
'filerevert'                => 'Reninizialisar $1',
'filerevert-legend'         => 'Reinizialisar la datoteca',
'filerevert-intro'          => "Ti es londervi da reinizialisar la [$4 versiun dals $2 las $3] da la datoteca '''[[Media:$1|$1]]'''.",
'filerevert-comment'        => 'Motiv:',
'filerevert-defaultcomment' => 'Reinizialisà la versiun dals $1 las $2',
'filerevert-submit'         => 'Reinizialisar',
'filerevert-success'        => "'''[[Media:$1|$1]]''' è vegnì reinizialisà a la [$4 versiun dals $2 las $3].",
'filerevert-badversion'     => "I n'exista nagina versiun locala da la datoteca dal temp inditgà.",

# File deletion
'filedelete'                  => 'Stizzar $1',
'filedelete-legend'           => 'Stizzar la datoteca',
'filedelete-intro'            => "Ti es londervi da stizzar la datoteca '''[[Media:$1|$1]]''' inclusivamain l'entira cronologia.",
'filedelete-intro-old'        => "Ti es londervi da stizzar la versiun da '''[[Media:$1|$1]]''' dals [$4 $2 las $3].",
'filedelete-comment'          => 'Motiv:',
'filedelete-submit'           => 'Stizzar',
'filedelete-success'          => "'''$1''' è vegnì stizzà.",
'filedelete-success-old'      => "La versiun da '''[[Media:$1|$1]]''' dals $2 las $3 è vegnida stizzada.",
'filedelete-nofile'           => "'''$1''' n'exista betg.",
'filedelete-nofile-old'       => "I n'exista nagina versiun archivada da '''$1''' cun ils attributs inditgads.",
'filedelete-otherreason'      => 'Auter/ulteriur motiv:',
'filedelete-reason-otherlist' => 'Auter motiv',
'filedelete-reason-dropdown'  => "*Motivs generals per stizzar
** Offensiun da dretgs d'autur
** Duplicat",
'filedelete-edit-reasonlist'  => 'Modifitgar ils motivs per stizzar',
'filedelete-maintenance'      => 'Pervia da lavurs da mantegniment èn las funcziuns per stizzar e reinizialisar datotecas deactivadas temporarmain.',

# MIME search
'mimesearch'         => 'tschertgar tenor tip da MIME',
'mimesearch-summary' => 'Questa pagina permetta da filtrar datotecas tenor lur tip MIME.
Endatar il term da tschertgar en la furma tip/suttip, p.ex. <tt>image/jpeg</tt>.',
'mimetype'           => 'Tip MIME:',
'download'           => 'telechargiar',

# Unwatched pages
'unwatchedpages' => 'Paginas betg contempladas',

# List redirects
'listredirects' => 'Glista cun tut ils renviaments',

# Unused templates
'unusedtemplates'     => 'Models betg utilisads',
'unusedtemplatestext' => "Questa pagina mussa tut las paginas en il tip da pagina {{ns:template}} ch'èn betg integrads en in'autra pagina.
Betg emblida da controllar sche autras colliaziuns mainan als models avant ch'als stizzar.",
'unusedtemplateswlh'  => 'Autras colliaziuns',

# Random page
'randompage'         => 'Artitgel casual',
'randompage-nopages' => 'I na dat naginas paginas da {{PLURAL:$2|quest tip da pagina|quests tips da pagina}}: $1',

# Random redirect
'randomredirect'         => 'Renviament casual',
'randomredirect-nopages' => 'I na vegn betg renvià al tip da pagina "$1".',

# Statistics
'statistics'                   => 'Statisticas',
'statistics-header-pages'      => 'Statistica da paginas',
'statistics-header-edits'      => 'Statistica da modificaziuns',
'statistics-header-views'      => 'Statistica da las visitas',
'statistics-header-users'      => 'Statisticas davart ils utilisaders',
'statistics-header-hooks'      => 'Autras statisticas',
'statistics-articles'          => 'Paginas da cuntegn',
'statistics-pages'             => 'Paginas',
'statistics-pages-desc'        => 'Tut las paginas en la vichi, inclusivamain paginas da discussiun, renviaments, etc.',
'statistics-files'             => 'Datotecas chargiadas si',
'statistics-edits'             => "Modificaziuns da paginas dapi l'installaziun da {{SITENAME}}",
'statistics-edits-average'     => 'Media da modificaziuns per pagina',
'statistics-views-total'       => 'Visitas totalmain',
'statistics-views-total-desc'  => "Visitas da paginas ch'existan betg u da paginas spezialas n'èn betg inclusas",
'statistics-views-peredit'     => 'Visitas per modificaziun',
'statistics-users'             => '[[Special:ListUsers|Utilisaders]] registrads',
'statistics-users-active'      => 'Utilisaders activs',
'statistics-users-active-desc' => "Utilisaders che han fatg almain ina acziun {{PLURAL:$1|l'ultim di|ils ultims $1 dis}}",
'statistics-mostpopular'       => 'Paginas visitadas il pli savens',

'disambiguations'     => 'Paginas per la decleraziun da noziuns',
'disambiguationspage' => 'Template:disambiguiziun',

'doubleredirects'            => 'Renviaments dubels',
'doubleredirectstext'        => "Questa glista mussa renviaments che mainan puspè a renviaments.
Mintga colonna cuntegna colliaziuns a l'emprim ed al segund renviaments, sco era la pagina finala dal segund renviament che è probablamain la pagina a la quala duess vegnir renvià.
Elements <del>stritgads</del> èn gia eliminads.",
'double-redirect-fixed-move' => '[[$1]] è vegnì spustà.
I renviescha uss a [[$2]].',
'double-redirect-fixer'      => 'Bot da renviaments',

'brokenredirects'        => 'Renviaments defects',
'brokenredirectstext'    => 'Ils suandants renviaments mainan a paginas betg existentas:',
'brokenredirects-edit'   => 'modifitgar',
'brokenredirects-delete' => 'stizzar',

'withoutinterwiki'         => 'Artitgels senza colliaziuns ad autras linguas',
'withoutinterwiki-summary' => "Suandantas paginas n'han naginas colliaziuns a versiuns d'autras linguas.",
'withoutinterwiki-legend'  => 'Prefix',
'withoutinterwiki-submit'  => 'Mussar',

'fewestrevisions' => 'Artitgels cun las pli biaras actualisaziuns',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories'             => '$1 {{PLURAL:$1|categoria|categorias}}',
'nlinks'                  => '$1 {{PLURAL:$1|colliaziun|colliaziuns}}',
'nmembers'                => '$1 {{PLURAL:$1|commember|commembers}}',
'nrevisions'              => '{{PLURAL:$1|Ina versiun|$1 versiuns}}',
'nviews'                  => 'Contemplà $1 {{PLURAL:$1|giada|giadas}}',
'nimagelinks'             => 'Utilisà sin $1 {{PLURAL:$1|pagina|paginas}}',
'ntransclusions'          => 'utilisà sin $1 {{PLURAL:$1|pagina|paginas}}',
'specialpage-empty'       => 'Questa pagina cuntegna actualmain naginas endataziuns.',
'lonelypages'             => 'Paginas bandunadas',
'lonelypagestext'         => "Las suandantas paginas n'èn betg integradas u n'èn betg colliadas cun autras paginas sin {{SITENAME}}.",
'uncategorizedpages'      => 'Artitgels betg categorisads',
'uncategorizedcategories' => 'Categorias betg categorisadas',
'uncategorizedimages'     => 'Datotecas betg categorisadas',
'uncategorizedtemplates'  => 'Models betg categorisads',
'unusedcategories'        => 'Categorias betg utilisadas',
'unusedimages'            => 'Maletgs betg utilisads',
'popularpages'            => 'Paginas popularas',
'wantedcategories'        => 'Categorias giavischadas',
'wantedpages'             => 'Artitgels giavischads',
'wantedpages-badtitle'    => 'Titel nunvalid en il resultat: $1',
'wantedfiles'             => 'Datotecas giavischadas',
'wantedtemplates'         => 'Models giavischads',
'mostlinked'              => 'Artitgels sin ils quals las pli biaras colliaziuns mussan',
'mostlinkedcategories'    => 'Categorias utilisadas il pli savens',
'mostlinkedtemplates'     => 'Models integrads il pli savens',
'mostcategories'          => "Artitgels ch'èn en las pli bleras chategorias",
'mostimages'              => 'Datotecas utilisadas il pli savens',
'mostrevisions'           => 'Artitgels cun las pli bleras revisiuns',
'prefixindex'             => 'Tut las paginas cun prefix',
'shortpages'              => 'Paginas curtas',
'longpages'               => 'Artitgels lungs',
'deadendpages'            => 'Artitgels senza colliaziuns internas',
'protectedpages'          => 'Paginas protegidas',
'protectedtitles'         => 'Titels bloccads',
'protectedtitlestext'     => 'Suandants titels èn bloccads per vegnir creads.',
'protectedtitlesempty'    => 'Cun ils parameters inditgads èn naginas titels actualmain bloccads per vegnir creads.',
'listusers'               => 'Glista dals utilisaders',
'usercreated'             => 'Creà ils $1 las $2 uras',
'newpages'                => 'Artitgels novs',
'newpages-username'       => "Num d'utilisader:",
'ancientpages'            => 'Artitgels il pli ditg betg modifitgads',
'move'                    => 'spustar',
'movethispage'            => 'Spustar quest artitgel',
'notargettitle'           => 'Nagina destinaziun',
'notargettext'            => "Ti n'has betg inditgà inaa pagina da destinaziun u in utilisader per exequir questa acziun.",
'nopagetitle'             => "Questa pagina da destinaziun n'exista betg",
'nopagetext'              => "La pagina da destinaziun che ti has inditgà n'exista betg.",
'pager-newer-n'           => '{{PLURAL:$1|pli nov|ils $1 pli novs}}',
'pager-older-n'           => '{{PLURAL:$1|in pli vegl|$1 pli vegls}}',

# Book sources
'booksources'               => 'Tschertga da ISBN',
'booksources-search-legend' => 'Tschertgar pussaivladad da cumpra per cudeschs',
'booksources-go'            => 'Leger',

# Special:Log
'specialloguserlabel'  => 'Utilisader:',
'speciallogtitlelabel' => 'Titel:',
'log'                  => 'logs / cudesch da navigaziun',
'all-logs-page'        => 'Tut ils logs publics',

# Special:AllPages
'allpages'          => 'Tut ils artitgels',
'alphaindexline'    => '$1 enfin $2',
'nextpage'          => 'proxima pagina ($1)',
'prevpage'          => 'ultima pagina ($1)',
'allpagesfrom'      => 'Mussar paginas naven da:',
'allpagesto'        => 'Mussar paginas enfin:',
'allarticles'       => 'Tut ils artitgels',
'allinnamespace'    => 'tut las paginas (tip da pagina $1)',
'allnotinnamespace' => 'Tut ils artitgels (betg dal tip da pagina $1)',
'allpagesprev'      => 'enavos',
'allpagesnext'      => 'enavant',
'allpagessubmit'    => 'Mussar',
'allpagesprefix'    => 'Mussar paginas cun il prefix:',
'allpages-bad-ns'   => 'Il tip da pagina "$1" n\'existà betg sin {{SITENAME}}.',

# Special:Categories
'categories' => 'Categorias',

# Special:DeletedContributions
'deletedcontributions'             => "Contribuziuns d'utilisaders stidadas",
'deletedcontributions-title'       => 'Contribuziuns dad utilisaders stizzadas',
'sp-deletedcontributions-contribs' => 'contribuziuns',

# Special:LinkSearch
'linksearch'       => 'Colliaziuns externas',
'linksearch-pat'   => 'Muster da tschertgar:',
'linksearch-ns'    => 'Tip da pagina:',
'linksearch-ok'    => 'Tschertgar',
'linksearch-text'  => 'Sin questa pagina pos ti tschertgar paginas che cuntegnan colliaziuns a tschertas websites. 
Uschenumnadas wildcards sco p.ex. "*.wikipedia.org" pon vegnir utilisadas.<br />
Protocols sustegnids: <tt>$1</tt>',
'linksearch-line'  => '$1 è collià davent da la pagina $2',
'linksearch-error' => "Wildcards pon esser be a l'entschatta dal num da host.",

# Special:ListUsers
'listusersfrom'      => 'Mussar utilisaders naven da:',
'listusers-submit'   => 'Mussar',
'listusers-noresult' => 'Betg chattà in utilisader.',
'listusers-blocked'  => '(bloccà)',

# Special:ActiveUsers
'activeusers'            => 'Glista dals utilisaders activs',
'activeusers-intro'      => "Quai è ina glista dals utilisaders che han gì activitads {{PLURAL:$1|l'ultim di|en ils ultims $1 dis}}.",
'activeusers-count'      => "$1 {{PLURAL:$1|modificaziun|modificaziuns}} {{PLURAL:$3|l'ultim di|ils ultims $3 dis}}",
'activeusers-from'       => 'Mussar utilisaders naven da:',
'activeusers-hidebots'   => 'Zuppentar bots',
'activeusers-hidesysops' => 'Zuppentar administraturs',
'activeusers-noresult'   => 'Chattà nagins utilisaders.',

# Special:Log/newusers
'newuserlogpage'              => "Log d'utilisaders creads",
'newuserlogpagetext'          => "Quai è il log dals contos d'utilisader ch'èn vegnids creads.",
'newuserlog-byemail'          => 'tramess il pled-clav per e-mail',
'newuserlog-create-entry'     => "Nov conto d'utilisader",
'newuserlog-create2-entry'    => 'Creà in nov conto "$1"',
'newuserlog-autocreate-entry' => 'Quest conto è vegnì creà automaticamain',

# Special:ListGroupRights
'listgrouprights'                      => "Dretgs da las gruppas d'utilisaders",
'listgrouprights-summary'              => "Sutvart vegn mussada ina glista da las gruppas d'utilisaders sin questa wiki cun ils dretgs d'access associads.
Infurmaziuns supplementaras davart ils singuls dretgs chattas [[{{MediaWiki:Listgrouprights-helppage}}|sin questa pagina]].",
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Dretg asssegnà</span>
* <span class="listgrouprights-revoked">Dretg revocà</span>',
'listgrouprights-group'                => 'Gruppa',
'listgrouprights-rights'               => 'Dretgs',
'listgrouprights-helppage'             => 'Help:Dretgs da las gruppas',
'listgrouprights-members'              => '(glista dals commembers)',
'listgrouprights-addgroup'             => 'Agiuntar utilisaders a suandanta{{PLURAL:$2| gruppa|s gruppas}}: $1',
'listgrouprights-removegroup'          => 'Allontanar utilisaders da suandanta{{PLURAL:$2| gruppa|s gruppas}}: $1',
'listgrouprights-addgroup-all'         => 'Agiuntar utilisaders a tut las gruppas',
'listgrouprights-removegroup-all'      => 'Allontanar utilisaders da tut las gruppas',
'listgrouprights-addgroup-self'        => "Agiuntar suandanta{{PLURAL:$2| gruppa|s gruppas}} a l'agen conto: $1",
'listgrouprights-removegroup-self'     => "Allontanar suandanta{{PLURAL:$2| gruppa|s gruppas}} da l'agen conto: $1",
'listgrouprights-addgroup-self-all'    => "Agiuntar tut las gruppas a l'agen conto",
'listgrouprights-removegroup-self-all' => "Allontanar tut las gruppas da l'agen conto",

# E-mail user
'mailnologin'          => 'Nagina adressa per trametter',
'mailnologintext'      => "Ti stos [[Special:UserLogin|t'annunziar]] ed avair ina adressa dad e-mail valida en tias [[Special:Preferences|preferenzas]] per trametter e-mails ad auters utilisaders.",
'emailuser'            => 'Trametter in e-mail a quest utilisader',
'emailpage'            => "Trametter in e-mail a l'utilisader",
'emailpagetext'        => "Ti pos utilisar il formular sutvart per trametter in'e-mail a quest utilisader.
L'adressa dad e-mail che ti has endatà en [[Special:Preferences|tias preferenzas]] vegn inditgada sco speditur da l'e-mail, uschia ch'il retschavider po rispunder directamain a tai.",
'usermailererror'      => 'La funcziun dad e-mail ha returnà ina errur:',
'defemailsubject'      => '{{SITENAME}} e-mail',
'usermaildisabled'     => 'Trametter e-mails è deactivà',
'usermaildisabledtext' => 'Ti na pos betg trametter e-mails ad auters utilisaders sin questa wiki',
'noemailtitle'         => 'Nagina adressa dad e-mail',
'noemailtext'          => "Quest utilisader n'ha betg inditgà ina adressa dad e-mail valida.",
'nowikiemailtitle'     => 'Betg lubì da termetter e-mail',
'nowikiemailtext'      => "Quest utilisader ha tschernì ch'el na vul betg reschaiver e-mails dad auters utilisaders.",
'emailnotarget'        => "Num d'utilisader nunexistent u nunvalid sco desinatur.",
'emailtarget'          => "Endatescha il num d'utilisader dal destinatur",
'emailusername'        => "Num d'utilisader:",
'emailusernamesubmit'  => 'Trametter',
'email-legend'         => 'Trametter in e-mail ad in auter utilisader da {{SITENAME}}',
'emailfrom'            => 'Da:',
'emailto'              => 'A:',
'emailsubject'         => 'Object:',
'emailmessage'         => 'Messadi:',
'emailsend'            => 'Trametter',
'emailccme'            => "Ma trametter ina copia da l'e-mail",
'emailccsubject'       => 'Copia da tes messadi a $1: $2',
'emailsent'            => 'Tramess e-mail.',
'emailsenttext'        => 'Tes e-mail è vegnì tramess.',
'emailuserfooter'      => 'Quest e-mail è vegnì tramess da $1 a $2 cun agid da la funcziun da trametter e-mails ad utilisaders sin {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'Scrit in messadi da sistem.',
'usermessage-editor'  => 'Messenger dal sistem',

# Watchlist
'watchlist'            => "Mia glista d'observaziun",
'mywatchlist'          => "Mia glista d'observaziun",
'watchlistfor2'        => 'Per $1 $2',
'nowatchlist'          => "Ti n'has nagins elements sin tia glista d'observaziun.",
'watchlistanontext'    => "Ti stos $1 per vesair u modifitgar elements sin tia glista d'observaziun",
'watchnologin'         => "Ti n'es betg t'annunzià!",
'watchnologintext'     => "Ti stos [[Special:UserLogin|t'annunziar]] per midar tia glista d'observaziun.",
'addwatch'             => "Agiuntar a la glista d'observaziun",
'addedwatchtext'       => "L'artitgel \"[[:\$1]]\" è vegnì agiuntà a vossa [[Special:Watchlist|glista dad observaziun]].
Midadas futuras vid quai artitgel e la pagina da discussiun appertegnenta vegnan enumeradas là e l'artitgel vegn marcà '''grass''' en la [[Special:RecentChanges|glista da las ultimas midadas]].",
'removewatch'          => "Allontanar da la glista d'observaziun",
'removedwatchtext'     => 'La pagina "[[:$1]]" è vegnida stizzada da [[Special:Watchlist|tia glista d\'observaziun]].',
'watch'                => 'Observar',
'watchthispage'        => 'Observar questa pagina!',
'unwatch'              => 'betg pli observar',
'unwatchthispage'      => 'Betg pli observar',
'notanarticle'         => "Quai n'è betg ina pagina da cuntegn",
'notvisiblerev'        => "L'ultima versiun d'in auter utilisader è vegnida stizzada",
'watchnochange'        => 'Nagin dals artitgels che ti observeschas è vegnì midà durant la perioda da temp inditgada.',
'watchlist-details'    => "{{PLURAL:$1|Ina pagina|$1 paginas}} èn sin tia glista d'observaziun (senza dumbrar las paginas da discussiun).",
'wlheader-enotif'      => "* Il servetsch d'infurmaziun per e-mail è activà.",
'wlheader-showupdated' => "* Artitgels ch'èn vegnids midads suenter che ti has vis els la davosa giada èn mussads '''grass'''",
'watchmethod-recent'   => "intercurir las davosas midadas per la glista d'observaziun",
'watchmethod-list'     => 'intercurir las paginas observadas davart novas midadas',
'watchlistcontains'    => "Tia glista d'observaziun cuntegna $1 {{PLURAL:$1|pagina|paginas}}.",
'iteminvalidname'      => "Problem cun endataziun '$1', num nunvalid...",
'wlnote'               => "Sutvart {{PLURAL:$1|è l'ultima midada|èn las ultimas '''$1''' midadas}} entaifer {{PLURAL:$2|l'ultima ura|las ultimas '''$2''' uras}}.",
'wlshowlast'           => 'Mussar: las ultimas $1 uras, ils ultims $2 dis u $3.',
'watchlist-options'    => "Opziuns per la glista d'observaziun",

# Displayed when you click the "watch" button and it is in the process of watching
'watching'       => 'observ...',
'unwatching'     => 'observ betg pli...',
'watcherrortext' => 'Ina errur è capitada durant midar tia configuraziun da tia glista d\'observaziun per "$1".',

'enotif_mailer'                => "Servetsch d'infurmaziun per e-mail da {{SITENAME}}",
'enotif_reset'                 => 'Marcar tut las paginas sco visitadas.',
'enotif_newpagetext'           => 'Quaii è ina nova pagina.',
'enotif_impersonal_salutation' => 'Utilisader da {{SITENAME}}',
'changed'                      => 'midada',
'created'                      => 'creada',
'enotif_subject'               => 'La pagina $PAGETITLE da {{SITENAME}} è vegnida $CHANGEDORCREATED da $PAGEEDITOR',
'enotif_lastvisited'           => 'Visita $1 per vesair tut las midadas dapi tia ultima visita.',
'enotif_lastdiff'              => 'Guarda $1 per vesair questa midada.',
'enotif_anon_editor'           => 'utilisader anonim $1',
'enotif_body'                  => 'Olla $WATCHINGUSERNAME,

La pagina $PAGETITLE sin {{SITENAME}} è vegnida $CHANGEDORCREATED ils $PAGEEDITDATE da $PAGEEDITOR, guarda $PAGETITLE_URL per la versiun actuala.

$NEWPAGE

Resumaziun dal contribuent: $PAGESUMMARY $PAGEMINOREDIT

Contactar il contribuent:
mail: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Ti na retschaivas betg ulteriurs avis en cass dad ulteriuras midadas sche ti na visitas betg questa pagina. 
Ti has era la pussaivladad da redefinir tut ils flags d\'avis per tut las paginas sin tia glista d\'observaziun.

Cun amiaivels salids,
il sistem d\'avis da {{SITENAME}}

--
Per midar tia configuraziun per avis per e-mails, visita
{{fullurl:{{#special:Preferences}}}}

Per midar tia configuraziun da la glista d\'observaziun, visita
{{fullurl:{{#special:EditWatchlist}}}}

Per stizzar questa pagina da la glista d\'observaziun, visita
$UNWATCHURL

Resuns ed agid chattas qua:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Stizzar la pagina',
'confirm'                => 'Confermar',
'excontent'              => 'il cuntegn era: "$1"',
'excontentauthor'        => 'il cuntegn era: "$1" (ed il sulet contribuent era "[[Special:Contributions/$2|$2]]")',
'exbeforeblank'          => 'il cuntegn avant svidar la pagina era: "$1"',
'exblank'                => 'la pagina era vida',
'delete-confirm'         => 'Stizzar "$1"',
'delete-legend'          => 'Stizzar',
'historywarning'         => "'''Attenziun:''' L'artitgel che ti vuls stizzar ha ina cronologia cun circa {{PLURAL:$1|ina versiun|$1 versiuns}}:",
'confirmdeletetext'      => 'Ti es vidlonder da stizzar permanentamain in artitgel u in maletg e tut las versiuns pli veglias ord la datoteca. <br />
Conferma per plaschair che ti ta es conscient da las consequenzas e che ti ageschas tenor las [[{{MediaWiki:Policy-url}}|directivas da {{SITENAME}}]].',
'actioncomplete'         => "L' acziun è terminada.",
'actionfailed'           => "L'acziun n'è betg reussida",
'deletedtext'            => '"$1" è vegnì stizzà.
Sin $2 chattas ti ina glista dals davos artitgels stizzads.',
'deletedarticle'         => 'ha stizzà "[[$1]]"',
'suppressedarticle'      => 'supprimì "[[$1]]"',
'dellogpage'             => 'log dal stizzar',
'dellogpagetext'         => "Sutvart è ina glista dals elements stizzads l'ultim.",
'deletionlog'            => 'log dal stizzar',
'reverted'               => 'Reinizialisà ina versiun da pli baud',
'deletecomment'          => 'Motiv:',
'deleteotherreason'      => 'Autra / supplementara raschun:',
'deletereasonotherlist'  => 'Autra raschun:',
'deletereason-dropdown'  => "*Motivs frequents per stizzar
** Dumonda da l'autur
** Violaziun dals dretgs d'autur
** Vandalissem",
'delete-edit-reasonlist' => 'Midar ils motivs per il stizzar',

# Rollback
'rollback'       => 'Revocar modificaziuns',
'rollback_short' => 'Revocar',
'rollbacklink'   => 'revocar',
'rollbackfailed' => 'Betg reussì da revocar',
'cantrollback'   => "Betg pussaivel da reinizialisar questa modificaziun; 
l'ulitima contribuziun è dal sulet autur da questa pagina.",
'alreadyrolled'  => "Impussibel da reinizialisar la midada da l'artitgel [[:$1]] da l'utilisader [[User:$2|$2]] ([[User talk:$2|talk]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]);
Enzatgi auter ha gia modifitga u reinizialisà qeusta pagina.

L'ultima modificaziun vid questa pagina è da [[User:$3|$3]] ([[User talk:$3|talk]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).",

# Protect
'protectlogpage'              => 'Log da las protecziuns',
'protectedarticle'            => 'bloccà "[[$1]]"',
'modifiedarticleprotection'   => 'Midà il livel da protecziun per "[[$1]]"',
'protect-title'               => 'Midar il livel da protecziun per "$1"',
'prot_1movedto2'              => 'ha spustà [[$1]] tar [[$2]]',
'protect-legend'              => 'Midar il status da protecziun da la pagina.',
'protectcomment'              => 'Motiv:',
'protectexpiry'               => 'Pretegì enfin:',
'protect_expiry_invalid'      => "Il temp endatà n'è betg valid.",
'protect_expiry_old'          => 'Il temp da proteger giascha en il passà.',
'protect-unchain-permissions' => 'Activar ulteriuras opziuns da protecziun',
'protect-text'                => "Qua pos ti contemplar ed midar il livel da protecziun per l'artitgel '''$1'''.",
'protect-locked-blocked'      => "Ti na pos betg midar il livel da protecziun durant che ti es blocca. 
Qua è la configuraziun actuala per la pagina '''$1''':",
'protect-locked-dblock'       => "Il livel da protecziun na po betg vegnir midà perquai che la banca da datas è bloccada.
Qua è la configuraziun actuala per la pagina '''$1''':",
'protect-locked-access'       => "Tes conto d'utilisader n'ha betg il dretg da midar ils livels da protecziun.
Qua èn las reglas actualas per l'artitgel '''$1''':",
'protect-cascadeon'           => 'Questa pagina è actualmain protegida, perquai ch\'ella è integrada en {{PLURAL:$1|suandant artitgel che ha activà|suandants artitgels che han activà}} la "protecziun ertada".
Ti pos midar il livel da protecziun da quest\'artitgel, quai na vegn dentant betg ad avair in effec sin la "protecziun ertada".',
'protect-default'             => 'Lubir tut ils utilisaders',
'protect-fallback'            => 'Il dretg "$1" è necessari',
'protect-level-autoconfirmed' => 'Bloccar utilisaders novs e na-registrads',
'protect-level-sysop'         => 'be administraturs',
'protect-summary-cascade'     => '"protecziun ertaivla"',
'protect-expiring'            => 'Scroda $1 (UTC)',
'protect-expiry-indefinite'   => 'permanent',
'protect-cascade'             => 'Proteger paginas integradas en questa pagina ("protecziun ertaivla")',
'protect-cantedit'            => "Ti na pos betg midar il livel da protecziun da questa pagina, perquai che ti n'has betg ils dretgs per far quai.",
'protect-othertime'           => 'Auter temp:',
'protect-othertime-op'        => 'auter temp',
'protect-existing-expiry'     => 'Protegì enfin ils $2 las $3',
'protect-otherreason'         => 'Auter/ulteriur motiv:',
'protect-otherreason-op'      => 'Auter motiv',
'protect-dropdown'            => '*Motivs usuals da protecziun
** Vandalissem excessiv
** Spamming intensiv
** Guerra da modificaziuns nunproductiva
** Pagina visitada savens',
'protect-edit-reasonlist'     => 'Midar ils motivs da protecziun',
'protect-expiry-options'      => '1 ura:1 hour,1 di:1 day,1 emna:1 week,2 emnas:2 weeks,1 mais:1 month,3 mais:3 months,6 mais:6 months,1 onn:1 year,permanent:infinite',
'restriction-type'            => 'Status da protecziun:',
'restriction-level'           => 'Livel da protecziun:',
'minimum-size'                => 'Grondezza minimala',
'maximum-size'                => 'Grondezza maximala:',
'pagesize'                    => '(bytes)',

# Restrictions (nouns)
'restriction-edit'   => 'Modifitgar',
'restriction-move'   => 'Spustar',
'restriction-create' => 'Crear',
'restriction-upload' => 'Chargiar si',

# Restriction levels
'restriction-level-sysop'         => 'protecziun cumpletta',
'restriction-level-autoconfirmed' => 'semi-protegì',
'restriction-level-all'           => 'mintga livel',

# Undelete
'undelete'                  => 'Mussar las paginas stizzadas',
'undeletepage'              => 'Guardar u restituir paginas stizzadas',
'undeletepagetitle'         => "'''Il suandant mussa versiuns stizzadas da [[:$1|$1]]'''.",
'viewdeletedpage'           => 'Mussar las paginas stizzadas',
'undeletepagetext'          => "{{PLURAL:$1|La suandanta pagina è stizzada ma è|Las suandantas $1 èn stizzadas ma èn}} anc en l'archiv cun pussaivladad da restituir. 
L'archiv vegn eventualmain svidà periodicamain.",
'undelete-fieldset-title'   => 'Restituir las versiuns',
'undeleteextrahelp'         => "Per restituir l'entira cronologia d'ina pagina, lascha inactiv tut las chaschettas e cllicca sin '''''{{int:undeletebtn}}'''''.
Per restituir be tschertgas part, activescha las chaschettas correspundentas per las versiuns che duain vegnir restituidas e clicca sin '''''{{int:undeletebtn}}'''''.",
'undeleterevisions'         => 'Archivà $1 {{PLURAL:$1|versiun|versiuns}} archived',
'undelete-nodiff'           => 'Chattà naginas versiuns anteriuras.',
'undeletebtn'               => 'restituir',
'undeletelink'              => 'mussar/restituir',
'undeleteviewlink'          => 'contemplar',
'undeletereset'             => 'Redefinir',
'undeleteinvert'            => 'Invertar la selecziun',
'undeletecomment'           => 'Motiv:',
'undeletedarticle'          => 'restituì "[[$1]]"',
'undeletedrevisions'        => 'Restituì {{PLURAL:$1|1 versiun|$1 versiuns}}',
'undeletedrevisions-files'  => 'Restituì {{PLURAL:$1|1 versiun|$1 versiuns}} e {{PLURAL:$2|1 datoteca|$2 datotecas}}',
'undeletedfiles'            => 'Restituì {{PLURAL:$1|1 datoteca|$1 datotecas}}',
'undelete-search-submit'    => 'Tschertga',
'undelete-show-file-submit' => 'Gea',

# Namespace form on various pages
'namespace'      => 'Tip da pagina:',
'invert'         => 'invertar la selecziun',
'blanknamespace' => '(principal)',

# Contributions
'contributions'       => "Contribuziuns da l'utilisader",
'contributions-title' => "Contribuziuns d'utilisader da $1",
'mycontris'           => 'Mias contribuziuns',
'contribsub2'         => 'Per $1 ($2)',
'nocontribs'          => 'Chattà naginas modificaziuns che correspundan a quests criteris.',
'uctop'               => '(actual)',
'month'               => 'dal mais (e pli baud):',
'year'                => "da l'onn (e pli baud):",

'sp-contributions-newbies'             => 'Be mussar contribuziuns da contos novs',
'sp-contributions-newbies-sub'         => "Per novs contos d'utilisader",
'sp-contributions-newbies-title'       => "Contribuziuns da novs contos d'utilisader",
'sp-contributions-blocklog'            => 'log dal bloccar',
'sp-contributions-deleted'             => 'Contribuziuns da commembers stizzadas',
'sp-contributions-uploads'             => 'datotecas chargiadas si',
'sp-contributions-logs'                => 'logs',
'sp-contributions-talk'                => 'discussiun',
'sp-contributions-userrights'          => "administraziun da dretgs d'utilisaders",
'sp-contributions-blocked-notice'      => "Quai utilisader è bloccà actualmain. 
L'ultima endataziun dal log da bloccar vegn mussà sutvart sco referenza:",
'sp-contributions-blocked-notice-anon' => "Questa adressa IP è bloccada actualmain. 
L'ultima endataziun dal log da bloccar vegn mussà sutvart sco referenza:",
'sp-contributions-search'              => "Tschertgar contribuziuns d'utilisaders",
'sp-contributions-username'            => "Adressa IP u num d'utilisader:",
'sp-contributions-toponly'             => "Be mussar modificaziuns ch'èn las ulimas versiuns",
'sp-contributions-submit'              => 'Tschertga',

# What links here
'whatlinkshere'            => 'Colliaziuns sin questa pagina',
'whatlinkshere-title'      => 'Paginas ch\'èn colliadas cun "$1"',
'whatlinkshere-page'       => 'Pagina:',
'linkshere'                => "Suandantas paginas èn colliadas cun '''[[:$1]]''':",
'nolinkshere'              => "Naginas paginas èn colliadas cun '''[[:$1]]'''.",
'nolinkshere-ns'           => "Naginas paginas èn colliadas cun '''[[:$1]]''' en il tip da pagina tschernì.",
'isredirect'               => 'Pagina che renviescha',
'istemplate'               => 'Integraziun da models',
'isimage'                  => 'colliaziun da datoteca',
'whatlinkshere-prev'       => '{{PLURAL:$1|ultim|ultims $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|proxim|proxims $1}}',
'whatlinkshere-links'      => '← colliaziuns',
'whatlinkshere-hideredirs' => '$1 sviaments',
'whatlinkshere-hidetrans'  => '$1 integraziuns da models',
'whatlinkshere-hidelinks'  => '$1 colliaziuns',
'whatlinkshere-hideimages' => '$1 colliaziuns da datotecas',
'whatlinkshere-filters'    => 'Filters',

# Block/unblock
'blockip'                         => "Bloccar l'utilisader",
'blockip-title'                   => "Bloccar l'utilisader",
'blockip-legend'                  => "Bloccar l'utilisader",
'blockiptext'                     => "Utilisescha il formular sutvart per bloccar ina adressa IP u in utilisader uschia ch'el na po betg pli far midadas.
Ti duessas be far quai per impedir vandalissem e en ina moda confurma a las [[{{MediaWiki:Policy-url}}|directivas]].
Inditgescha sutvart in motiv (per exempel pos ti inditgar paginas ch'èn vegnidas destruidas).",
'ipadressorusername'              => "Adressa IP u num d'utilisader:",
'ipbexpiry'                       => 'Bloccà enfin:',
'ipbreason'                       => 'Motiv:',
'ipbreasonotherlist'              => 'Auter motiv',
'ipbreason-dropdown'              => "*Motivs generals per bloccar
** Inserir infurmaziuns faussas
** Stizzar cuntegn da paginas
** Inserir colliaziuns da spam a paginas externas
** Inserir nonsens en paginas
** Sedepurtar nunconvegnent u mulestant
** Abus da plirs contos
** Num d'utilisader nunacceptabel",
'ipbcreateaccount'                => "Impedir la creaziun da contos d'utilisader",
'ipbemailban'                     => 'Bloccar la funcziun da trametter e-mails',
'ipbenableautoblock'              => "Bloccar automaticamain l'ultima adressa IP che quest utilisader ha utilisà e mintga adressa IP che succeda da la quala el emprova da far modificaziuns",
'ipbsubmit'                       => 'Bloccar quest utilisader',
'ipbother'                        => 'Auter temp:',
'ipboptions'                      => '2 uras:2 hours,1 di:1 day,3 dis:3 days,1 emna:1 week,2 emnas:2 weeks,1 mais:1 month,3 mais:3 months,6 mais:6 months,1 onn:1 year,permanent:infinite',
'ipbotheroption'                  => 'auter',
'ipbotherreason'                  => 'Auter/ulteriur motiv:',
'ipbhidename'                     => "Zuppentar il num d'utilisader en las modicaziuns e glistas",
'ipbwatchuser'                    => "Observar las paginas d'utilisader e da discussiun da quest utilisader",
'ipb-change-block'                => "Re-bloccar l'utilisader cun las medemas configuraziuns",
'badipaddress'                    => "L'adressa IP è nunvalida",
'blockipsuccesssub'               => 'Bloccà cun success',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] è vegnì bloccà.<br />
Sin [[Special:IPBlockList|la glista dad IPs bliccadas]] pos ti repassar tut las bloccadas.',
'ipb-edit-dropdown'               => 'Modifitgar ils motivs per bloccar',
'ipb-unblock-addr'                => 'Debloccar $1',
'ipb-unblock'                     => 'Debloccar in utilisader u ina adressa IP',
'ipb-blocklist'                   => 'Mussar tut las bloccadas activas',
'ipb-blocklist-contribs'          => "Contribuziuns d'utilisader da $1",
'unblockip'                       => 'Debloccar in utilisader',
'unblockiptext'                   => "Utilisescha il formular sutvart per restituir l'access da scriver per ina adressa IP u in utilisader ch'è vegnì bloccà pli baud.",
'ipusubmit'                       => 'Allontanar questa bloccada',
'unblocked'                       => '[[User:$1|$1]] è vegnì debloccà',
'unblocked-id'                    => 'La bloccada $1 è vegnida allontanada',
'blocklist'                       => 'Utilisaders bloccads',
'ipblocklist'                     => 'Utilisaders bloccads',
'ipblocklist-legend'              => 'Tschertgar in utilisader bloccà',
'blocklist-userblocks'            => 'zuppentar las bloccadas da contos',
'blocklist-tempblocks'            => 'Zuppentar bloccadas temporaras',
'blocklist-addressblocks'         => 'Zuppentar singuls blocs dad adressas dad IP',
'blocklist-timestamp'             => 'Bul da temp',
'blocklist-target'                => 'Destinaziun',
'blocklist-expiry'                => 'Scroda ils',
'blocklist-by'                    => 'Administratur che ha bloccà',
'blocklist-params'                => 'Parameters da la bloccada',
'blocklist-reason'                => 'Motiv',
'ipblocklist-submit'              => 'Tschertgar',
'ipblocklist-localblock'          => 'Bloccadas localas',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|Autra bloccada|Autras bloccadas}}',
'infiniteblock'                   => 'permanent',
'expiringblock'                   => 'scroda ils $1 las $2 uras',
'anononlyblock'                   => 'be anonims',
'noautoblockblock'                => 'deactivà la bloccada automatica',
'createaccountblock'              => 'bloccà la creaziun da contos',
'emailblock'                      => "bloccà la spediziun d'e-mails",
'blocklist-nousertalk'            => "na po betg modifitgar l'atgna pagina da discussiun",
'ipblocklist-empty'               => 'la glista da bloccadas è vida.',
'ipblocklist-no-results'          => "La adressa IP u l'utilisader dumandà n'è betg bloccà.",
'blocklink'                       => 'bloccar',
'unblocklink'                     => 'debloccar',
'change-blocklink'                => 'Midar opziuns da bloccar',
'contribslink'                    => 'contribuziuns',
'autoblocker'                     => "Vossa adressa dad IP è vegnida bloccada perquai che vus utilisais ina adressa dad IP cun [[User:$1|$1]]. Motiv per bolccar $1: '''$2'''.",
'blocklogpage'                    => 'Log dal bloccar',
'blocklog-showlog'                => 'Quest utilisader è gia vegnì bloccà pli baud.
Qua il log da bloccaziun sco infurmaziun:',
'blocklog-showsuppresslog'        => 'Quest utilisader è pli baud vegnì bloccà e zuppentà.
Quai è il log da supprimer sco infurmaziun:',
'blocklogentry'                   => 'bloccà [[$1]] per $2. Motiv: $3',
'reblock-logentry'                => 'ha midà las opziuns da bloccada per [[$1]] enfin ils: $2 $3',
'unblocklogentry'                 => "debloccà l'utilisader „$1“",
'block-log-flags-anononly'        => 'be utilisaders anonims',
'block-log-flags-nocreate'        => 'deactivà la creaziun da contos',
'block-log-flags-noautoblock'     => 'deactivà la bloccada automatica',
'block-log-flags-noemail'         => "bloccà la spediziun d'e-mails",
'block-log-flags-nousertalk'      => "na po betg modifitgar l'atgna pagina da discussiun",
'block-log-flags-angry-autoblock' => 'activà la bloccada automatica extendida',
'block-log-flags-hiddenname'      => "zuppentà il num d'utilisader",
'range_block_disabled'            => "La pussaivladad che l'administratur po bloccar entiras zonas d'adressas IP è deactivada.",
'ipb_expiry_invalid'              => 'La durada da la bloccada inditgada è nunvalaivla.',
'ipb_expiry_temp'                 => "Bloccadas cun zuppentar il num d'utilisader sont esser permanentas.",
'ipb_already_blocked'             => '"$1" è gia bloccà',
'ipb-needreblock'                 => '$1 è gia bloccà.
Vuls ti midar las opziuns da la bloccada?',
'ipb-otherblocks-header'          => '{{PLURAL:$1|Autra bloccada|Autras bloccadas}}',
'ipb_cant_unblock'                => "Errur: La bloccada cun lÎD $1 n'è betg vegnida chattada.
Eventualmain è ella gia vegnida annulada.",
'ipb_blocked_as_range'            => "Errur: L'adressa IP $1 n'è betg bloccada directamain e na po betg vegnir debloccada.
Ella e bloccada en la zona d'adressas IP $2 che po vegnir debloccà.",
'ip_range_invalid'                => "Zona d'adressas IP nunvalida.",
'ip_range_toolarge'               => "Zonas da bloccadas pli grondas che /$1 n'èn betg lubidas.",
'blockme'                         => 'Bloccar mai',
'proxyblocker'                    => 'Bloccar proxys',
'proxyblocker-disabled'           => 'Questa funcziun è deactivada.',
'proxyblockreason'                => "Tia adressa IP è vegnida bloccada perquai ch'ella è in proxy avert. 
Contactescha tes provider dals survetschs d'internet u ils administraturs dal sistem ed als infurmescha davart quest problem da segirezza pussaivel.",
'proxyblocksuccess'               => 'Terminà.',
'sorbsreason'                     => 'Tia adressa IP fa part da la glista da proxys averts da DNSBL che vegn utilisada da {{SITENAME}}.',
'sorbs_create_account_reason'     => "Tia adressa IP fa part da la glista da proxys averts da DNSBL che vegn utilisada da {{SITENAME}}.
Ti na pos betg crear in conto d'utilisader.",
'cant-block-while-blocked'        => 'Ti na pos betg bloccar auters utilisaders durant che ti es sez bloccà',
'cant-see-hidden-user'            => "L'utilisader che ti emprovas da bloccar è gia bloccà e zuppentà.
Perquai che ti h'has betg il dretg «hideuser» na pos ti betg vesair u modifitgar la bloccada da l'utilisader.",
'ipbblocked'                      => 'Ti na pos betg bloccar u debloccar auters utilisaders perquai che ti es sez bloccà',
'ipbnounblockself'                => 'Ti na bos betg debloccar tai sez',

# Developer tools
'lockdb'              => 'Bloccar la banca da datas',
'unlockdb'            => 'Debloccar la banca da datas',
'lockdbtext'          => "Bloccar la banca da datas impedescha tut ils utilisaders da modifitgar paginas, da midar las preferenzas, dad adattar lur glistas d'observaziuns ed dad utilisar autras funcziuns che basegnan midadas en la banca da datas. 
Conferma che ti vuls propi far quai e che ti debloccas puspè la banca da datas suenter che las lavurs da mantegniment èn terminadas.",
'unlockdbtext'        => "Debloccar la banca da datas lubescha puspè a tut ils utilisaders da modifitgar paginas, da midar las preferenzas, dad adattar lur glistas d'observaziuns ed dad utilisar autras funcziuns che basegnan midadas en la banca da datas. 
Conferma che ti vuls propi far quai.",
'lockconfirm'         => 'Gea, jau vi propi bloccar la banca da datas.',
'unlockconfirm'       => 'Gea, jau vi propi debloccar la banca da datas.',
'lockbtn'             => 'Bloccar la banca da datas',
'unlockbtn'           => 'Debloccar la banca da datas',
'lockdbsuccesssub'    => 'Bloccà cun success la banca da datas',
'unlockdbsuccesssub'  => 'Debloccà cun success la banca da datas',
'lockdbsuccesstext'   => 'La banca da datas è vegnida bloccada.<br />
Betg emblida da [[Special:UnlockDB|debloccar la banca da datas]] suenter che las lavurs da mantegniment èn terminadas.',
'unlockdbsuccesstext' => 'La banca da datas è vegnida debloccada.',
'lockfilenotwritable' => 'La datoteca per bloccar la banca da datas na po betg vegnir scritta.
Per bloccar ni debloccar la banca da datas sto il server avair il dretg da scriver en quella.',
'databasenotlocked'   => 'Questa banca da datas è betg bloccada.',

# Move page
'move-page'                    => 'Spustar "$1"',
'move-page-legend'             => 'Spustar la pagina',
'movepagetext'                 => "Cun il formular sutvart das ti in nov num ad in artitgel e spostas l'entira istorgia da l'artitgel al nov.
L'artitgel vegl renviescha lura al nov.
Ti pos actualisar automaticamain paginas che renvieschan a l'artitgel original.
Sche ti na vuls betg quai, controllescha p. pl las paginas che renvieschan [[Special:DoubleRedirects|dublamain]] u [[Special:BrokenRedirects|incorrect]].
Ti ès responsabels che tut las colliaziuns mainan al lieu ch'els duessan.

Fa stim, che la pagina '''na vegn betg''' spustada sch'i exista gia in artitgel cun il nov titel, auter sche quel è vids u renviescha ad in'autra pagina e n'ha nagina istorgia.

'''ATTENZIUN!'''
Quai po esser ina midada drastica ed nunspetgada per in artitgel popular;
sajas conscient da las consequenzas che quai process po avair.",
'movepagetalktext'             => "La pagina da discussiun che tutga tar l'artitgel vegn spustada automaticamain cun l'artitgel, '''sche betg''':
*Ina pagina da discussiun betg vida exista gia sut il lemma nov
*Ti prendas ora il crutschin dal champ sutvart

En quests cas as ti da spustar u colliar manualmain las paginas, sche giavischà.",
'movearticle'                  => 'Spustar artitgel:',
'moveuserpage-warning'         => "'''Attenziun:''' Ti es londervi da spustar ina pagina d'utilisader. Considerescha che be la pagina vegn spustada ed il num da l'utilisader na vegn ''betg'' midà.",
'movenologin'                  => "Ti n'ès betg t'annunzià",
'movenologintext'              => "Ti stos [[Special:UserLogin|t'annunziar]] per spustar in artitgel.",
'movenotallowed'               => "Ti n'has betg il dretg da spustar paginas.",
'movenotallowedfile'           => "Ti n'has betg il dretg da spustar datotecas.",
'cant-move-user-page'          => "Ti n'has betg il dretg da spustar paginas d'utilisader (auter che sutpaginas).",
'cant-move-to-user-page'       => "Ti n'has betg il dretg da spustar ina pagina ad ina pagina d'utilisader (auter che sutpaginas d'utilisader).",
'newtitle'                     => 'Al titel nov:',
'move-watch'                   => 'Observar questa pagina',
'movepagebtn'                  => 'Spustar la pagina',
'pagemovedsub'                 => 'Spustà cun success',
'movepage-moved'               => '\'\'\'"$1" è vegnì spustà a "$2"\'\'\'',
'movepage-moved-redirect'      => 'In renviament è vegnì creà.',
'movepage-moved-noredirect'    => 'I è vegnì impedì da crear in renviament.',
'articleexists'                => 'I exista gia in artitgel cun quai num. Tscherni per plaschair in auter.',
'cantmove-titleprotected'      => "Ti na pos betg spustar questa pagina en quai lieu, perquai ch'il titel da destinaziun è bloccà e na dastga betg vegnir creà.",
'talkexists'                   => "'''L'artitgel è vegnì spustà cun success. Dentant exista sut il nov num gia ina pagina da discussiun, perquai è la pagina da discussiun betg vegnida spustada. Fa quai p. pl. a maun.'''",
'movedto'                      => 'spustà a',
'movetalk'                     => "Spustar la pagina da discussiun che tutga tar l'artitgel",
'move-subpages'                => 'Spustar sutpaginas (fin a $1)',
'move-talk-subpages'           => 'Spustar sutpaginas da paginas da discussiun (fin a $1)',
'movepage-page-exists'         => 'La pagina $1 exista gia e na po betg vegnir surscritta automaticamain.',
'movepage-page-moved'          => 'La pagina $1 è vegnida spustada a $2.',
'movepage-page-unmoved'        => 'Betg pussaivel da spustar la pagina $1 a $2.',
'movepage-max-pages'           => 'Il dumber maximal da $1 {{PLURAL:$1|pagina|paginas}} è vegnì spustà. Tut las ulteriuras na pon betg vegnir spustadas automaticamain.',
'1movedto2'                    => 'ha spustà [[$1]] a [[$2]]',
'1movedto2_redir'              => 'ha spustà [[$1]] a [[$2]] cun in renviament',
'move-redirect-suppressed'     => 'Impedì renviament',
'movelogpage'                  => 'Log dal spustar',
'movelogpagetext'              => 'Sut è ina glista da tut las paginas spustadas.',
'movesubpage'                  => '{{PLURAL:$1|Sutpagina|Sutpaginas}}',
'movesubpagetext'              => 'Questa pagina ha $1 {{PLURAL:$1|sutpagina mussada|sutpaginasmussadas}} sutvart.',
'movenosubpage'                => "Questa pagina n'ha naginas sutpaginas.",
'movereason'                   => 'Motiv:',
'revertmove'                   => 'spustar anavos',
'delete_and_move'              => 'Stizzar e spustar',
'delete_and_move_text'         => '==Stizzar necessari==

L\'artitgel da destinaziun "[[:$1]]" exista gia. Vul ti stizzar el per far plaz per spustar?',
'delete_and_move_confirm'      => 'Gea, stizzar il artitgel da destinaziun per spustar',
'delete_and_move_reason'       => 'Stizzà per far plaz per spustar',
'selfmove'                     => 'Il num da funtauna e destinaziun èn identics; 
betg pussaivel da spustar ina pagina en il medem lieu.',
'immobile-source-namespace'    => 'Paginas dal tip da pagina "$1" na pon betg vegnir spustadas',
'immobile-target-namespace'    => 'Betg pussaivel da spustar paginas en il tip da pagina "$1"',
'immobile-target-namespace-iw' => "Ina colliaziun dad interwiki n'è betg ina destinaziun valida per spustar.",
'immobile-source-page'         => 'Questa pagina na po betg vegnir spustada.',
'immobile-target-page'         => 'Betg pussaivel da spustar a quest titel da destinaziun.',
'imagenocrossnamespace'        => 'Betg pussaivel da spustar ina datoteca ad in tip da pagina betg da datoteca',
'nonfile-cannot-move-to-file'  => "Betg pussaivel da spustar in element che n'è nagina datoteca al tip da pagina datoteca",
'imagetypemismatch'            => 'La nova extensiun da datoteca na correspunda betg al tip da datoteca',
'imageinvalidfilename'         => 'Il num da datoteca da destinaziun è nunvalid',
'fix-double-redirects'         => 'Schliar renviaments dubels suenter il spustar',
'move-leave-redirect'          => 'Crear renviament',
'protectedpagemovewarning'     => "'''Attenziun:''' Questa pagina è vegnida protegida uschia che be utilisaders cun dretgs d'administratur la pon spustar. 
Sco infurmaziun l'ultima endataziun en il log:",
'semiprotectedpagemovewarning' => "'''Attenziun:''' Questa pagina è vegnida protegida uschia che be utilisaders registrads la pon spustar. 
Sco infurmaziun l'ultima endataziun en il log:",
'move-over-sharedrepo'         => '== Datoteca exista == 
[[:$1]] exista gia en in repositori communabel. Spustar ina datoteca a quest titel vegn a remplazzar la datoteca communabla.',
'file-exists-sharedrepo'       => 'Il num da datoteca tschernì exista gia en in repositori communabel.
Tscherna per plaschair in auter num.',

# Export
'export'            => 'Exportar paginas',
'exporttext'        => "Ti pos exportar il text e la cronologia da modificaziuns d'ina pagina u d'ina gruppa da paginas en in format dad XML.
Quel po vegnir importà en ina autra wiki che sa basa sin MediaWiki cun agid da la [[Special:Import|pagina d'import]].

Endatescha en il champ sutvart tut ils artitgels che ti vuls exportar, per mintga artitgel ina nova lingia, ed inditgescha era sche ti vuls includer l'entira cronologia u be la versiun actuala. 

En cas che ti vul be exportar l'ultima versiun pos ti era utilisar in link, p.ex. [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] per la pagina \"[[{{MediaWiki:Mainpage}}]]\".",
'exportcuronly'     => "Exportar be la versiun actuala, betg l'entira cronologia",
'exportnohistory'   => "----
'''Remartga:''' La funcziun dad exportar l'entira cronologia en questa furma è deactivada ord motivs da prestaziun.",
'export-submit'     => 'Exportar',
'export-addcattext' => 'Agiuntar paginas da la categoria:',
'export-addcat'     => 'Agiuntar',
'export-addnstext'  => 'Agiuntar paginas ord il tip da pagina:',
'export-addns'      => 'Agiuntar',
'export-download'   => 'Memorisar sco datoteca',
'export-templates'  => 'Includer models',
'export-pagelinks'  => 'Includer paginas cun colliaziuns fin ad ina profunditad da:',

# Namespace 8 related
'allmessages'                   => 'Messadis dal sistem',
'allmessagesname'               => 'num',
'allmessagesdefault'            => 'text original',
'allmessagescurrent'            => 'text actual',
'allmessagestext'               => 'Quai è ina glista da tut ils messadis dals differents tips da paginas da MediaWiki che vegnan utilisadas da la software MediaWiki.
Fai ina visita sin [//www.mediawiki.org/wiki/Localisation MediaWiki Localisation] e [//translatewiki.net translatewiki.net] sche ti vuls gidar da translatar la software MediaWiki.',
'allmessagesnotsupportedDB'     => "Questa pagina na po betg vegnir mussada, perquai che '''\$wgUseDatabaseMessages''' è vegnì deactivà.",
'allmessages-filter-legend'     => 'Filtrar',
'allmessages-filter'            => 'Filtrar tenor standi da modificaziun:',
'allmessages-filter-unmodified' => 'Betg modifitgà',
'allmessages-filter-all'        => 'Tut',
'allmessages-filter-modified'   => 'Modifitgà',
'allmessages-prefix'            => 'Filtrar tenor prefix:',
'allmessages-language'          => 'Lingua:',
'allmessages-filter-submit'     => 'Dai',

# Thumbnails
'thumbnail-more'           => 'Mussar pli grond',
'filemissing'              => 'Datoteca manca',
'thumbnail_error'          => 'Sbagl cun crear il maletg da prevista: $1',
'djvu_page_error'          => 'Pagina da DjVu è ordaifer la limita',
'djvu_no_xml'              => "Betg pussaivel da retschaiver l'XML per la datoteca da DjVu",
'thumbnail_invalid_params' => 'Parameters nunvalids dal maletg da prevista',
'thumbnail_dest_directory' => "Betg pussaivel da crear l'ordinatur da destinaziun.",
'thumbnail_image-type'     => 'Quest tip da maletg na vegn betg sustegnì',
'thumbnail_gd-library'     => 'Configuraziun betg cumpletta da la biblioteca da GD: Funcziun mancanta $1',
'thumbnail_image-missing'  => 'Datoteca para da mancar: $1',

# Special:Import
'import'                     => 'Impurtar paginas',
'importinterwiki'            => 'Import transwiki',
'import-interwiki-text'      => "Tscherna ina wiki ed in titel da pagina per importar.
Las datas da las versiuns ed ils nums dals contribuents vegnan mantegnids.
Tut ils imports transwiki vegnan registrads en il [[Special:Log/import|log d'importar]].",
'import-interwiki-source'    => 'Wiki/pagina da funtauna:',
'import-interwiki-history'   => "Copiar l'entira cronologia da questa pagina",
'import-interwiki-templates' => 'Includer tut ils models',
'import-interwiki-submit'    => 'Importar',
'import-interwiki-namespace' => 'Tip da pagina da destinaziun:',
'import-upload-filename'     => 'Num da datoteca:',
'import-comment'             => 'Commentari:',
'importtext'                 => "Exportescha la datoteca da la wiki da funtauna cun agid da la [[Special:Export|funcziun d'export]].
La memorisescha sin tes computer e chargia si ella qua.",
'importstart'                => 'Importar paginas…',
'import-revision-count'      => '{{PLURAL:$1|Ina versiun|$1 versiuns}}',
'importnopages'              => 'Nagina pagina per importar.',
'imported-log-entries'       => 'Importà $1 {{PLURAL:$1|endataziun dal log|endataziuns dal log}}.',
'importfailed'               => "Betg reussì d'importar: <nowiki>$1</nowiki>",
'importunknownsource'        => "Funtauna d'import nunenconuschenta",
'importcantopen'             => "Betg pussaivel dad avrir la datoteca d'import",
'importbadinterwiki'         => 'Colliaziun interwiki nunvalida',
'importnotext'               => 'Vid u nagin text',
'importsuccess'              => "Terminà l'import!",
'importhistoryconflict'      => 'La cronologia cuntegna versiuns che stattan en conflict (eventualmain è questa pagina gia vegnida importada ina giada)',
'importnosources'            => "Naginas funtaunas per l'import transwiki èn vegnidas definidas e la funcziun da chargiar si directamain la coronologia è deactivada.",
'importnofile'               => "Nagina datoteca d'import è vegnida chargiada si.",
'importuploaderrorsize'      => "Betg reussì da chargiar si la datoteca d'import.
La datoteca è pli gronda che la grondezza maximala per chargiar si.",
'importuploaderrorpartial'   => "Betg reussì da chargiar si la datoteca d'import.
La datoteca è be vegnida chargiada si parzialmain.",
'importuploaderrortemp'      => "Betg reussì da chargiar si la datoteca d'import.
In ordinatur temporar manca.",
'import-parse-failure'       => "Errur cun elavurar l'import da XML",
'import-noarticle'           => 'Nagina pagina per importar!',
'import-nonewrevisions'      => 'Tut las versiuns èn gia vegnidas impotadas pli baud.',
'xml-error-string'           => '$1 sin lingia $2, colonna $3 (byte $4): $5',
'import-upload'              => 'Chargiar si datas XML',
'import-token-mismatch'      => 'Perdita da datas da la sesida.
Emprova danovamain.',
'import-invalid-interwiki'   => 'Betg pussaivel dad importar dal wiki inditgà.',

# Import log
'importlogpage'                    => 'Log dals imports',
'importlogpagetext'                => 'Import administrativ da paginas cun cronologia da modificaziuns dad autras wikis.',
'import-logentry-upload'           => 'ha importà [[$1]] cun chargiar si ina datoteca',
'import-logentry-upload-detail'    => '{{PLURAL:$1|Ina versiun|$1 versiuns}}',
'import-logentry-interwiki'        => 'ha importà $1 (transwiki)',
'import-logentry-interwiki-detail' => '{{PLURAL:$1|Ina versiun|$1 versiuns}} da $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => "Mussar tia pagina d'utilisader",
'tooltip-pt-anonuserpage'         => "La pagina d'utilisader per l'adressa IP cun la quala che ti fas modificaziuns",
'tooltip-pt-mytalk'               => 'Mussar tia pagina da discussiun',
'tooltip-pt-anontalk'             => 'Discussiun davart modificaziuns che derivan da questa adressa dad IP',
'tooltip-pt-preferences'          => 'mias preferenzas',
'tooltip-pt-watchlist'            => 'La glista da las paginas da las qualas jau observ las midadas',
'tooltip-pt-mycontris'            => 'Mussar la glista da tut tias contribuziuns',
'tooltip-pt-login'                => "I fiss bun sche ti s'annunziassas, ti na stos dentant betg.",
'tooltip-pt-anonlogin'            => "I fiss bun sche ti t'annunziassas; quai n'è dentant betg obligatoric.",
'tooltip-pt-logout'               => 'Sortir',
'tooltip-ca-talk'                 => "Discussiuns davart il cuntegn da l'artitgel",
'tooltip-ca-edit'                 => "Ti pos modifitgar questa pagina.
Utilisescha per plaschair il buttun 'mussar prevista' avant che memorisar.",
'tooltip-ca-addsection'           => 'Cumenzar nov paragraf',
'tooltip-ca-viewsource'           => 'Questa pagina è protegida.
Ti pos vesair il code-fundamental.',
'tooltip-ca-history'              => 'Versiuns pli veglias da questa pagina',
'tooltip-ca-protect'              => 'Proteger questa pagina',
'tooltip-ca-unprotect'            => 'Midar la protecziun da questa pagina',
'tooltip-ca-delete'               => 'Stizzar quest artitgel',
'tooltip-ca-undelete'             => "Restituir las modificaziuns ch'èn vegnidas fatgas vid questa pagina avant ch'ella è vegnida stizzada",
'tooltip-ca-move'                 => 'Spustar questa pagina',
'tooltip-ca-watch'                => "Agiuntar questa pagina a tia glista d'observaziun",
'tooltip-ca-unwatch'              => "Allontanar questa pagina da tia pagina d'observaziun",
'tooltip-search'                  => 'Intercurir {{SITENAME}}',
'tooltip-search-go'               => "Mussar la pagina cun exact quest num (sch'ella exista)",
'tooltip-search-fulltext'         => 'Tschertgar en tut las paginas quest text',
'tooltip-p-logo'                  => 'Ir a la pagina principala',
'tooltip-n-mainpage'              => 'Ir a la pagina principala',
'tooltip-n-mainpage-description'  => 'Visitar la pagina principala',
'tooltip-n-portal'                => 'Infurmaziuns davart il project, tge che ti pos far, nua che ti chassas infurmaziuns',
'tooltip-n-currentevents'         => 'Chattar infurmaziuns davart occurrenzas actualas',
'tooltip-n-recentchanges'         => 'La glista da las ultimas midadas en la wiki.',
'tooltip-n-randompage'            => 'Chargiar ina pagina casuala.',
'tooltip-n-help'                  => 'Qua chattas agid.',
'tooltip-t-whatlinkshere'         => 'Glista da tut las paginas vichi che mussan sin questa pagina',
'tooltip-t-recentchangeslinked'   => 'Ultimas midadas sin paginas colliadas cun questa pagina',
'tooltip-feed-rss'                => 'RSS feed per questa pagina',
'tooltip-feed-atom'               => 'Atom feed per questa pagina',
'tooltip-t-contributions'         => 'Mussar las contribuziuns da quest utilisader',
'tooltip-t-emailuser'             => 'Trametter in e-mail a quest utilisader',
'tooltip-t-upload'                => 'Chargiar si datotecas',
'tooltip-t-specialpages'          => 'Glista da tut las paginas spezialas',
'tooltip-t-print'                 => 'Versiun per stampar da questa pagina',
'tooltip-t-permalink'             => 'Link permanent tar questa versiun da la pagina',
'tooltip-ca-nstab-main'           => "Mussar l'artitgel",
'tooltip-ca-nstab-user'           => "Mussar la pagina da l'utilisader",
'tooltip-ca-nstab-media'          => 'Mussar la pagina cun medias',
'tooltip-ca-nstab-special'        => 'Quai è ina pagina speziala che ti na pos betg modifitgar',
'tooltip-ca-nstab-project'        => 'Mussar la pagina da project',
'tooltip-ca-nstab-image'          => 'Mussar la pagina da la datoteca',
'tooltip-ca-nstab-mediawiki'      => 'Mussar ils messadis dal sistem',
'tooltip-ca-nstab-template'       => 'Mussar il model',
'tooltip-ca-nstab-help'           => "Mussar la pagina d'agid",
'tooltip-ca-nstab-category'       => 'Mussar la pagina da la categoria',
'tooltip-minoredit'               => 'Marcar questa midada sco midada pitschna',
'tooltip-save'                    => 'Memorisar las midadas',
'tooltip-preview'                 => 'Prevista da las midadas. Utilisescha p. pl. questa funcziun avant che memorisar!',
'tooltip-diff'                    => 'Mussar las midadas che ti has fatg en il text.',
'tooltip-compareselectedversions' => 'Mussar la differenza tranter las duas versiuns selecziunadas da questa pagina.',
'tooltip-watch'                   => "Agiuntar questa pagina a tia pagina d'observaziun",
'tooltip-recreate'                => "Crear danovamain la pagnina, malgrà ch'ella è vegnida stizzada",
'tooltip-upload'                  => 'Cumenzar da chargiar si',
'tooltip-rollback'                => "Revochescha tut las modificaziuns vid questa pagina da l'ultim utilisader cun be in clic.",
'tooltip-undo'                    => 'Revochescha be questa midada e mussa il resultat en la prevista, per che ti pos inditgar en il champ da resumaziun in motiv.',
'tooltip-preferences-save'        => 'Memorisar las preferenzas',
'tooltip-summary'                 => 'Endatescha ina curta resumaziun',

# Stylesheets
'common.css'   => '',
'monobook.css' => "/* editescha quest file per adattar il skin momobook per l'entira pagina */",

# Scripts
'monobook.js' => '',

# Metadata
'notacceptable' => 'll server dal wiki na po betg furnir datas en in format che tes apparat po leger.',

# Attribution
'anonymous'        => '{{PLURAL:$1|In utilisader anonim|Utilisaders anonims}} da {{SITENAME}}',
'siteuser'         => 'utilisader $1 da {{SITENAME}}',
'anonuser'         => 'Utilisader anonim $1 da {{SITENAME}}',
'lastmodifiedatby' => 'Questa pagina è vegnida modifitgada la davosa giada ils $1 las $2 da $3.',
'othercontribs'    => 'Basescha sin la lavur da $1.',
'others'           => 'auters',
'siteusers'        => "{{PLURAL:$2|L'utilisader|Ils utilisaders}} $1 da {{SITENAME}}",
'anonusers'        => "{{PLURAL:$2|L'utilisader anonim|Ils utilisaders anonims}} $1 da {{SITENAME}}",
'creditspage'      => 'Statistica da la pagina',
'nocredits'        => 'Per questa pagina èn naginas infurmaziuns davart ils auturs disponiblas',

# Spam protection
'spamprotectiontitle' => 'Filter da protecziun cunter spam',
'spamprotectiontext'  => "Il text che ti levas memorisar è vegnì bloccà dal filter cunter spam. 
Probablamien è quai capità pervia dad in link ad ina pagina externa ch'è sin ina glista naira.",
'spamprotectionmatch' => 'Suandant text ha activà noss filter da spam: $1',
'spambot_username'    => 'Sistem da MediaWikip per nettegiar da spam',
'spam_reverting'      => "Restituir l'ultima versiun che na cuntegna naginas colliaziuns a $1",
'spam_blanking'       => 'Tut las versiuns cuntegnivan colliaziuns a $1, nettegià',

# Info page
'pageinfo-title'            => 'Infurmaziuns per "$1"',
'pageinfo-header-edits'     => 'Midadas',
'pageinfo-header-watchlist' => "Glista d'observaziun",
'pageinfo-header-views'     => 'Visitas',
'pageinfo-subjectpage'      => 'Pagina',
'pageinfo-talkpage'         => 'Pagina da discussiun',
'pageinfo-watchers'         => 'Dumber dad observaturs',
'pageinfo-edits'            => 'Dumber da midadas',
'pageinfo-authors'          => 'Dumber dad auturs differents',
'pageinfo-views'            => 'Dumber da visitas',
'pageinfo-viewsperedit'     => 'Visitas per midada',

# Patrolling
'markaspatrolleddiff'                 => 'Marcar sco controllà',
'markaspatrolledtext'                 => 'Marcar questa pagina sco controllada',
'markedaspatrolled'                   => 'Marcà sco controllà',
'markedaspatrolledtext'               => 'La versiun tschernida da [[:$1]] è vegnida marcada sco controllada.',
'rcpatroldisabled'                    => 'La controlla da las ultimas midadas è deactivava',
'rcpatroldisabledtext'                => 'La funcziun da controllar las ultimas midadas è actualmain deactivada.',
'markedaspatrollederror'              => 'Betg pussaivel da marcar sco controllà',
'markedaspatrollederrortext'          => 'Ti stos specifitgar ina versiun per marcar sco controllada.',
'markedaspatrollederror-noautopatrol' => 'Ti na dastgas betg marcar tias atgnas midadas sco controlladas.',

# Patrol log
'patrol-log-page'      => 'Log da controlla',
'patrol-log-header'    => 'Quai è il log da las versiuns controlladas.',
'patrol-log-line'      => 'ha marcà $1 da $2 sco controllà $3',
'patrol-log-auto'      => '(automaticamain)',
'patrol-log-diff'      => 'versiun $1',
'log-show-hide-patrol' => '$1 il log da controllas',

# Image deletion
'deletedrevision'                 => 'Stizzà la versiun veglia $1.',
'filedeleteerror-short'           => 'Errur cun stizzar la datoteca: $1',
'filedeleteerror-long'            => 'Cun stizzar la datoteca èn errurs vegnidas constatadas:

$1',
'filedelete-missing'              => 'La datoteca "$1" na po betg vegnir stizzada perquai ch\'ella n\'exista betg.',
'filedelete-old-unregistered'     => 'La versiun inditgada "$1" da la datoteca n\'è betg en la banca da datas.',
'filedelete-current-unregistered' => 'La datoteca "$1" inditgada n\'è betg en la datoteca',
'filedelete-archive-read-only'    => 'L\'ordinatur d\'archiv "$1" na po betg vegnir midà dal server.',

# Browsing diffs
'previousdiff' => '← Versiun pli veglia',
'nextdiff'     => 'versiun pli nova →',

# Media information
'mediawarning'         => "'''Attenziun''': Quest tip da datotecas po cuntegnair code malvulent. 
Cun exequir questa datoteca po tes sistem vegnir donnegià.",
'imagemaxsize'         => "Grondezza maximala da maletgs:<br />''(per paginas da descripziun da datotecas)''",
'thumbsize'            => 'Grondezza dals maletgs da prevista:',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|pagina|paginas}}',
'file-info'            => 'grondezza da datoteca: $1, tip da MIME: $2',
'file-info-size'       => '$1 × $2 pixels, grondezza da datoteca: $3, tip da MIME: $4',
'file-info-size-pages' => '$1 × $2 pixels, grondezza da datoteca: $3, tip da MIME: $4, $5 {{PLURAL:$5|pagina|paginas}}',
'file-nohires'         => '<small>Nagina resuluziun pli auta disponibla.</small>',
'svg-long-desc'        => 'datoteca da SVG, grondezza da basa $1 × $2 pixels, grondezza da datoteca: $3',
'show-big-image'       => 'Resoluziun cumplaina',
'show-big-image-size'  => '$1 × $2 pixels',
'file-info-gif-looped' => 'marscha infinit',
'file-info-gif-frames' => '$1 {{PLURAL:$1|maletg|maletgs}}',
'file-info-png-looped' => 'marscha infinit',
'file-info-png-repeat' => 'Reprodicì $1 {{PLURAL:$1|giada|giadas}}',
'file-info-png-frames' => '$1 {{PLURAL:$1|frame|frames}}',

# Special:NewFiles
'newimages'             => 'Galleria dals novs maletgs',
'imagelisttext'         => "Sutvart è ina glista da '''$1''' {{PLURAL:$1|datoteca|datotecas}} zavrdas $2.",
'newimages-summary'     => "Questa pagina speziala mussa las davosas datotecas ch'èn vegnidas chargiadas si.",
'newimages-legend'      => 'Filter',
'newimages-label'       => 'Num da la datoteca (u ina part da quel):',
'showhidebots'          => '($1 bots)',
'noimages'              => 'Chattà naginas datotecas.',
'ilsubmit'              => 'Tschertgar',
'bydate'                => 'tenor data',
'sp-newimages-showfrom' => 'Las novas datotecas davent dal $1 las $2 vegnan mussadas.',

# Bad image list
'bad_image_list' => "Il format è sco suonda:

Be elements da glistas (lingias che entschaivan cun in *) vegnan risguardads.
L'emprima colliaziun duai esser ina colliaziun ad in maletg betg giavischà.
Tut las colliaziuns che suandan sin la medema lingia vegnan risguardadas sco excepziuns.",

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => 'Questa datoteca cuntegna infurmaziuns supplementaras, probablamain agiuntadas da la camera digitala u dal scanner utilisà per crear digitalisar ella.
Sche la datoteca è vegnida midada dal status original èn tscherts detagls eventualmain betg pli corrects.',
'metadata-expand'   => 'Mussar detagls extendids',
'metadata-collapse' => 'Zuppentar detagls extendids',
'metadata-fields'   => "Las datas da meta da maletgs ch'èn inditgadas sin questa pagina vegnan mussads sin las paginas da descripziun dal maletg; uleriurs detagls zuppentads pon vegnir mussads.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude",

# EXIF tags
'exif-imagewidth'                  => 'Ladezza',
'exif-imagelength'                 => 'Autezza',
'exif-bitspersample'               => 'Bits per cumponenta da colur',
'exif-compression'                 => 'Tip da cumpressiun',
'exif-photometricinterpretation'   => 'Cumposiziun dals pixels',
'exif-orientation'                 => 'Orientaziun',
'exif-samplesperpixel'             => 'Dumber da cumpunentas',
'exif-planarconfiguration'         => 'Arrangement da las datas',
'exif-ycbcrsubsampling'            => 'Rata da subsampling da Y a C',
'exif-ycbcrpositioning'            => 'Posiziunament da Y e C',
'exif-xresolution'                 => 'Resoluziun orizontala',
'exif-yresolution'                 => 'Resoluziun verticala',
'exif-stripoffsets'                => 'Posiziun da las datas da maletg',
'exif-rowsperstrip'                => 'Dumber lingias per strivla',
'exif-stripbytecounts'             => 'Bytes per strivla cumprimida',
'exif-jpeginterchangeformat'       => 'Offset al JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Bytes datas da JPEG',
'exif-whitepoint'                  => 'Cromatissem dil punct alv',
'exif-primarychromaticities'       => 'Cromatissem da las colurs primara�s',
'exif-ycbcrcoefficients'           => 'Coeffizients YCbCr',
'exif-referenceblackwhite'         => 'Pèr da valurs da referenza per nair ed alv',
'exif-datetime'                    => 'Data da modificaziun',
'exif-imagedescription'            => 'Titel dal maletg',
'exif-make'                        => "Fabricant da l'apparat",
'exif-model'                       => "Model da l'apparat",
'exif-software'                    => 'Software utilisada',
'exif-artist'                      => 'Autur',
'exif-copyright'                   => "Dretgs d'autur",
'exif-exifversion'                 => 'Versiun dad Exif',
'exif-flashpixversion'             => 'Versiun da FlashPix sustegnida',
'exif-colorspace'                  => 'Spazi da colurs',
'exif-componentsconfiguration'     => 'Significaziun da las singulas cumponentas',
'exif-compressedbitsperpixel'      => 'Modus da cumprimer',
'exif-pixelydimension'             => 'Ladezza dal maletg',
'exif-pixelxdimension'             => 'Autezza dal maletg',
'exif-usercomment'                 => "Commentari da l'utilisader",
'exif-relatedsoundfile'            => "Datoteca d'audio appartegnenta",
'exif-datetimeoriginal'            => 'Data e temp da la generaziun',
'exif-datetimedigitized'           => 'Data e temp da la digitalisaziun',
'exif-subsectime'                  => 'Data en tschientavels secunda',
'exif-subsectimeoriginal'          => 'Data da generaziun en tschientavels secunda',
'exif-subsectimedigitized'         => 'Data da digitalisaziun en tschientavels secunda',
'exif-exposuretime'                => "Temp d'exposiziun",
'exif-exposuretime-format'         => '$1 secundas ($2)',
'exif-fnumber'                     => 'Cifra dal diafragma (numer F)',
'exif-exposureprogram'             => "Program d'exposiziun",
'exif-spectralsensitivity'         => 'Sensibilitad spectrala',
'exif-isospeedratings'             => 'Sensibilitad ISO',
'exif-shutterspeedvalue'           => 'Sveltezza da la serradira APEX',
'exif-aperturevalue'               => 'Cifra dal diafragma APEX',
'exif-brightnessvalue'             => 'Luminusitad APEX',
'exif-exposurebiasvalue'           => "Correctura d'exposiziun APEX",
'exif-maxaperturevalue'            => 'Pli grond passagi dal diafragma',
'exif-subjectdistance'             => 'Distanza dal subject',
'exif-meteringmode'                => "Metoda da mesiraziun da l'exposiziun (Metering mode)",
'exif-lightsource'                 => 'Funtauna da glisch',
'exif-flash'                       => 'Chametg/straglisch',
'exif-focallength'                 => 'Distanza focala',
'exif-subjectarea'                 => 'Areal dal subject',
'exif-flashenergy'                 => 'Energia dal chametg/straglisch',
'exif-focalplanexresolution'       => 'Resoluziun X dal plaun focal',
'exif-focalplaneyresolution'       => 'Resoluziun Y dal plaun focal',
'exif-focalplaneresolutionunit'    => 'Unitad da mesira per la resoluziun dal plaun focal',
'exif-subjectlocation'             => 'Posiziun dal subject',
'exif-exposureindex'               => "Index d'exposiziun",
'exif-sensingmethod'               => 'Metoda da mesirar',
'exif-filesource'                  => 'Funtauna da la datoteca',
'exif-scenetype'                   => 'Tip da scena',
'exif-customrendered'              => 'Elavuraziun dal maletg persunalisà',
'exif-exposuremode'                => "Modus d'exposiziun",
'exif-whitebalance'                => "Equiliber da l'alv",
'exif-digitalzoomratio'            => 'Quota dal zoom digital',
'exif-focallengthin35mmfilm'       => 'Distanza focala equivalenta en in film da 35 mm',
'exif-scenecapturetype'            => 'Tip da scena registrada',
'exif-contrast'                    => 'Contrast',
'exif-saturation'                  => 'Saturaziun',
'exif-sharpness'                   => 'Netezza (Schärfe)',
'exif-devicesettingdescription'    => "Descripziun da la configuraziun da l'apparat",
'exif-subjectdistancerange'        => 'Distanza dal suject',
'exif-imageuniqueid'               => 'ID unica dal maletg',
'exif-gpsversionid'                => 'Versiun dal tag da GPS',
'exif-gpslatituderef'              => 'Latituda/Grad da ladezza nord u sid',
'exif-gpslatitude'                 => 'Latituda/Ladezza geografica',
'exif-gpslongituderef'             => 'Longhituda/Grad da lunghezza ost u vest',
'exif-gpslongitude'                => 'Longhituda/Lunghezza geografica',
'exif-gpsaltituderef'              => "Referenza per l'autezza",
'exif-gpsaltitude'                 => 'Autezza',
'exif-gpstimestamp'                => 'Temp da GPS (ura atomara)',
'exif-gpssatellites'               => 'Satellits utilisads per mesirar',
'exif-gpsstatus'                   => 'Status dal receptur',
'exif-gpsmeasuremode'              => 'Modus da mesirar',
'exif-gpsdop'                      => 'Prezisiun da mesirar',
'exif-gpsspeedref'                 => 'Unitad da mesira da spertadad',
'exif-gpsspeed'                    => 'Spertadad dal receptur da GPS',
'exif-gpstrackref'                 => 'Referenza per la direcziun dal moviment',
'exif-gpstrack'                    => 'Direcziun dal moviment',
'exif-gpsimgdirectionref'          => 'Referenza per la direcziun dal maletg',
'exif-gpsimgdirection'             => 'Direcziun dal maletg',
'exif-gpsmapdatum'                 => 'Sistem da referenza geodetic',
'exif-gpsdestlatituderef'          => 'Referenza per la latituda (largezza) da la destinaziun',
'exif-gpsdestlatitude'             => 'Latituda/ladezza geografica da la destinaziun',
'exif-gpsdestlongituderef'         => 'Referenza per la longhituda (lunghezza) da la destinaziun',
'exif-gpsdestlongitude'            => 'Longhituda/lunghezza geografica da la destinaziun',
'exif-gpsdestbearingref'           => 'Referenza per la direcziun da la destinaziun',
'exif-gpsdestbearing'              => 'Direcziun da la destinaziun',
'exif-gpsdestdistanceref'          => 'Referenza per la distanza a la destinaziun',
'exif-gpsdestdistance'             => 'Distanza a la destinaziun',
'exif-gpsprocessingmethod'         => "Num da la metoda d'elavuraziun da GPS",
'exif-gpsareainformation'          => 'Num dal territori da GPS',
'exif-gpsdatestamp'                => 'Data da GPS',
'exif-gpsdifferential'             => 'Correctura da differenzial dal GPS',
'exif-jpegfilecomment'             => 'Commentari da la datoteca JPEG',
'exif-keywords'                    => 'Chavazzins',
'exif-worldregioncreated'          => 'Regiun dal mund en la quala la fotografia è vegnida fatga',
'exif-countrycreated'              => 'Pajais en il qual la fotografia è vegnida fatga',
'exif-countrycodecreated'          => 'Code dal pajais en il qual la fotografia è vegnida fatga',
'exif-provinceorstatecreated'      => 'Provinza, stadi u chantun en il qual la fotografia è vegnida fatga',
'exif-citycreated'                 => 'Citad/Lieu en il qual la fotografia è vegnida fatga',
'exif-sublocationcreated'          => 'Part da la citad/dal lieu en il qual la fotografia è vengida fatga',
'exif-worldregiondest'             => 'Regiun dal mund mussada',
'exif-countrydest'                 => 'Pajais mussà',
'exif-countrycodedest'             => 'Code dal pajais mussà',
'exif-provinceorstatedest'         => 'Provinza, chantun u stadi mussà',
'exif-citydest'                    => 'Citad/Lieu mussà',
'exif-sublocationdest'             => 'Part da la citad/dal lieu mussà',
'exif-objectname'                  => 'Titel curt',
'exif-specialinstructions'         => 'Instrucziuns spezialas',
'exif-headline'                    => 'Titel',
'exif-credit'                      => 'Numnar / publicatur',
'exif-source'                      => 'Funtauna',
'exif-editstatus'                  => 'Status redacziunal dal maletg',
'exif-urgency'                     => 'Urgenza',
'exif-fixtureidentifier'           => 'Num da la columna',
'exif-locationdest'                => 'Lieu mussà',
'exif-locationdestcode'            => 'Code dal lieu mussà',
'exif-objectcycle'                 => 'Temp dal di durant il qual quest med duess vegnir emess',
'exif-contact'                     => 'Infurmaziuns da contact',
'exif-writer'                      => 'Autur',
'exif-languagecode'                => 'Lingua',
'exif-iimversion'                  => 'Versiun IIM',
'exif-iimcategory'                 => 'Categoria',
'exif-iimsupplementalcategory'     => 'Categorias supplementaras',
'exif-datetimeexpires'             => 'Betg utilisar suenter',
'exif-datetimereleased'            => 'Public davent da',
'exif-originaltransmissionref'     => 'Code dal lieu danunder la transmissiun originala vegn',
'exif-identifier'                  => 'Identificatur',
'exif-lens'                        => 'Objectiv utilisà',
'exif-serialnumber'                => 'Numer da seria da la camera',
'exif-cameraownername'             => 'Possessur da la camera',
'exif-label'                       => 'Etichetta',
'exif-datetimemetadata'            => 'Data da la davosa midada da las datas da meta',
'exif-nickname'                    => 'Num informal dal maletg',
'exif-rating'                      => 'Valitaziun (ord 5)',
'exif-rightscertificate'           => "Certificat per l'administraziun dals dretgs",
'exif-copyrighted'                 => "Status dals dretgs d'autur",
'exif-copyrightowner'              => "Possessur dals dretgs d'autur",
'exif-usageterms'                  => "Cundizius per l'utilisaziun",
'exif-webstatement'                => "Decleraziun online dals dretgs d'autur",
'exif-originaldocumentid'          => 'ID unica dal document original',
'exif-licenseurl'                  => "URL da la lizenca dal dretg d'autur",
'exif-morepermissionsurl'          => 'Infurmaziuns alternativas davart la lizenza',
'exif-attributionurl'              => 'Sche questa ovra vegn reutilisada, far ina colliaziun a',
'exif-preferredattributionname'    => 'Sche questa ovra vegn reutilisada, p.pl. numnar',
'exif-pngfilecomment'              => 'Commentari da la datoteca PNG',
'exif-disclaimer'                  => 'Exclusiun da responsabladad',
'exif-contentwarning'              => 'Avertiment davart il cuntegn',
'exif-giffilecomment'              => 'Commentari da la datoteca GIF',
'exif-intellectualgenre'           => "Tip da l'element",
'exif-subjectnewscode'             => 'Code dal subject',
'exif-scenecode'                   => 'Code IPTC da la scena',
'exif-event'                       => 'Occurrenza mussada',
'exif-organisationinimage'         => 'Organisaziun mussada',
'exif-personinimage'               => 'Persuna mussada',
'exif-originalimageheight'         => 'Autezza avant tagliar',
'exif-originalimagewidth'          => 'Ladezza avant tagliar',

# EXIF attributes
'exif-compression-1' => 'Betg cumprimà',
'exif-compression-2' => 'GGITT gruppa 3 codaziun da lunghezza currenta Hufman modifitgada en 1 dimensiun',
'exif-compression-3' => 'CCITT gruppa 3 codaziun da fax',
'exif-compression-4' => 'CCITT gruppa 4 codaziun da fax',

'exif-copyrighted-true'  => "Protegì dal dretg d'auturs",
'exif-copyrighted-false' => 'Domena publica',

'exif-unknowndate' => 'Data nunenconuschenta',

'exif-orientation-1' => 'Normal',
'exif-orientation-2' => 'Reflectà orizontalmain',
'exif-orientation-3' => 'Rotà per 180°',
'exif-orientation-4' => 'Reflectà verticalmain',
'exif-orientation-5' => "Rotà 90° en senn cuntrari a l'ura e reflectà verticalmain",
'exif-orientation-6' => "Rotà 90° en senn da l'ura",
'exif-orientation-7' => "Rotà 90° en senn da l'ura e reflectà verticalmain",
'exif-orientation-8' => "Rotà 90° en senn cuntrari a l'ura",

'exif-planarconfiguration-1' => 'Format grop (chunky)',
'exif-planarconfiguration-2' => 'Format planar',

'exif-colorspace-65535' => 'Betg calibrà',

'exif-componentsconfiguration-0' => "n'exista betg",

'exif-exposureprogram-0' => 'Betg definì',
'exif-exposureprogram-1' => 'Manualmain',
'exif-exposureprogram-2' => 'Program da standard',
'exif-exposureprogram-3' => 'Prioritad al diafragma',
'exif-exposureprogram-4' => 'Prioritad a la serradira',
'exif-exposureprogram-5' => 'Program creativ, preferenza ad auta profunditad da la netezza (Schärfentiefe)',
'exif-exposureprogram-6' => 'Program sportiv, preferenza ad ina auta spertezza da la serradira',
'exif-exposureprogram-7' => 'Program da purtrets per fotografias senza focus sin il fund davos',
'exif-exposureprogram-8' => 'Program da cuntrada cun focus sin il fund davos',

'exif-subjectdistance-value' => '$1 meter{{PLURAL:$1||s}}',

'exif-meteringmode-0' => 'Nunenconuschent',
'exif-meteringmode-1' => 'Media',

'exif-lightsource-0'  => 'Nunenconuschent',
'exif-lightsource-1'  => 'Glisch dal di',
'exif-lightsource-2'  => 'Fluorescent',
'exif-lightsource-3'  => 'Pair electric',
'exif-lightsource-4'  => 'Chametg/straglisch',
'exif-lightsource-9'  => "Bel'aura",
'exif-lightsource-10' => 'Nivels',
'exif-lightsource-11' => 'Sumbriva',
'exif-lightsource-17' => 'Glisch da standard A',
'exif-lightsource-18' => 'Glisch da standard B',
'exif-lightsource-19' => 'Glisch da standard C',

# Flash modes
'exif-flash-fired-0'  => 'Senza chametg/straglisch',
'exif-flash-fired-1'  => 'Cun chametg/straglisch',
'exif-flash-redeye-1' => 'Reducziun dad egls cotschens',

'exif-focalplaneresolutionunit-2' => 'Poleschs',

'exif-sensingmethod-1' => 'Betg definì',

'exif-customrendered-0' => 'Process normal',
'exif-customrendered-1' => 'Process persunalisà',

'exif-exposuremode-0' => 'Exposiziun automatica',
'exif-exposuremode-1' => 'Exposiziun manuala',
'exif-exposuremode-2' => "Retscha d'exposiziuns",

'exif-whitebalance-0' => "Equiliber da l'alv automatic",
'exif-whitebalance-1' => "Equiliber da l'alv manual",

'exif-scenecapturetype-0' => 'Standard',
'exif-scenecapturetype-1' => 'Cuntrada',
'exif-scenecapturetype-2' => 'Purtret',
'exif-scenecapturetype-3' => 'Scena da notg',

'exif-gaincontrol-0' => 'Nagina',
'exif-gaincontrol-1' => 'levamain positiv',
'exif-gaincontrol-2' => 'Autamain positiv',
'exif-gaincontrol-3' => 'Levamain negativ',
'exif-gaincontrol-4' => 'Autamain negativ',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Flaivel',
'exif-contrast-2' => 'Ferm',

'exif-saturation-0' => 'Normal',
'exif-saturation-1' => 'Saturaziun basa',
'exif-saturation-2' => 'Saturaziun auta',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Flaivel',
'exif-sharpness-2' => 'Ferm',

'exif-subjectdistancerange-0' => 'Nunenconuschent',
'exif-subjectdistancerange-1' => 'Macro',
'exif-subjectdistancerange-2' => 'Damanaivel',
'exif-subjectdistancerange-3' => 'Lontan',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Latituda nord',
'exif-gpslatitude-s' => 'Latituda sid',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Longhituda ost',
'exif-gpslongitude-w' => 'Longhituda vest',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => '$1 {{PLURAL:$1|meter|meters}} sur il livel da la mar',
'exif-gpsaltitude-below-sealevel' => '$1 {{PLURAL:$1|meter|meters}} sut il livel da la mar',

'exif-gpsstatus-a' => 'Mesiraziun en progress',
'exif-gpsstatus-v' => 'Interoperabilitad da la mesiraziun',

'exif-gpsmeasuremode-2' => 'Mesiraziun en 2 dimensiuns',
'exif-gpsmeasuremode-3' => 'Mesiraziun en 3 dimensiuns',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'km per ura',
'exif-gpsspeed-m' => 'Miglias per ura',
'exif-gpsspeed-n' => 'Nuf',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'Kilometers',
'exif-gpsdestdistance-m' => 'Miglias',
'exif-gpsdestdistance-n' => 'Miglias nauticas',

'exif-gpsdop-excellent' => 'Excellent ($1)',
'exif-gpsdop-good'      => 'Bun ($1)',
'exif-gpsdop-moderate'  => 'Mediocher ($1)',
'exif-gpsdop-fair'      => 'Passabel ($1)',
'exif-gpsdop-poor'      => 'Debel ($1)',

'exif-objectcycle-a' => 'Be la damaun',
'exif-objectcycle-p' => 'Be la saira',
'exif-objectcycle-b' => 'Tant la dumaun sco la saira',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Direcziun reala',
'exif-gpsdirection-m' => 'Direcziun magnetica',

'exif-dc-contributor' => 'Contribuents',
'exif-dc-coverage'    => 'Champ  local u temporal dal med',
'exif-dc-date'        => 'Data(s)',
'exif-dc-publisher'   => 'Editur',
'exif-dc-relation'    => 'Meds sumegliants',
'exif-dc-rights'      => 'Dretgs',
'exif-dc-source'      => 'Funtauna dal med',
'exif-dc-type'        => 'Tip dal med',

'exif-rating-rejected' => 'Refusà',

'exif-isospeedratings-overflow' => 'Pli grond che 65535',

'exif-iimcategory-ace' => 'Art, cultura e divertiment',
'exif-iimcategory-clj' => 'Criminalitad e dretg',
'exif-iimcategory-dis' => 'Catastrofas ed accidents',
'exif-iimcategory-fin' => 'Economia ed affars',
'exif-iimcategory-edu' => 'Furmaziun',
'exif-iimcategory-evn' => 'Ambient',
'exif-iimcategory-hth' => 'Sanadad',
'exif-iimcategory-hum' => 'Interess umans',
'exif-iimcategory-lab' => 'Lavur',
'exif-iimcategory-lif' => 'Lifestyle e temp liber',
'exif-iimcategory-pol' => 'Politica',
'exif-iimcategory-rel' => 'Religiun e cardientscha',
'exif-iimcategory-sci' => 'Scienza e tecnologia',
'exif-iimcategory-soi' => 'Temas socials',
'exif-iimcategory-spo' => 'Sport',
'exif-iimcategory-war' => 'Guerra, conflicts e revoltas',
'exif-iimcategory-wea' => 'Aura',

'exif-urgency-normal' => 'Normal ($1)',
'exif-urgency-low'    => 'Bass ($1)',
'exif-urgency-high'   => 'Aut ($1)',
'exif-urgency-other'  => "Prioritad definida da l'utilisader ($1)",

# External editor support
'edit-externally'      => 'Modifitgar questa datoteca cun in program extern',
'edit-externally-help' => "(Legia [//www.mediawiki.org/wiki/Manual:External_editors instrucziuns d'installaziun] per ulteriuras infurmaziuns)",

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'tut',
'namespacesall' => 'tuts',
'monthsall'     => 'tuts',
'limitall'      => 'tuts',

# E-mail address confirmation
'confirmemail'              => "Confermar l'adressa dad e-mail",
'confirmemail_noemail'      => "Ti n'has betg inditgà ina adressa dad e-mail valida en tias [[Special:Preferences|preferenzas]].",
'confirmemail_text'         => "{{SITENAME}} pretenda che ti confermas tia adressa dad e-mail avant che ti pos utilisar funcziuns dad e-mail. 
Clicca sin il buttun sutvart per ta trametter in e-mail per confermar a tia adressa. 
L'e-mail cuntegna in link cun in code;
Chargia la destinaziun dal link en tes navigatur per confermar che tia adressa dad e-mail è valida.",
'confirmemail_pending'      => "In code per confermar è gia vegnì tramess a tai; 
sche ti has creà avant curt temp tes conto duessas ti anc spitgar in per minutas per che l'e-mail possia arrivar avant che ti genereschas in nov code.",
'confirmemail_send'         => 'Ma trametter in code da confermaziun',
'confirmemail_sent'         => "Tramess l'e-mail da confermaziun.",
'confirmemail_oncreate'     => "In code da confermaziun è vegnì tramess a tia adressa dad e-mail. 
Quest code n'è betg necessari per t'annunziar, ma ti al stos endatar avant che la wiki po activar funcziuns dad e-mail.",
'confirmemail_sendfailed'   => "{{SITENAME}} na pudeva betg trametter l'e-mail da confermaziun.
Controllescha sche ti has endatà caracters nunvalids en tia adressa dad e-mail.

Il server dad e-mail ha returnà: $1",
'confirmemail_invalid'      => 'Il code da confermaziun è nunvalid. Il code è probablamain scrudà.',
'confirmemail_needlogin'    => 'Ti stos $1 per confermar tia adressa dad e-mail.',
'confirmemail_success'      => "Tia adressa dad e-mail è vegnida confermada. 
Ussa pos ti [[Special:UserLogin|t'annunziar]] ed utilisar la wiki.",
'confirmemail_loggedin'     => 'Tia adressa dad e-mail è ussa vegnida confermada.',
'confirmemail_error'        => 'Insatge è crappà cun tes mail da confermaziun. Stgisa foll!',
'confirmemail_subject'      => "{{SITENAME}} - Confermaziun da l'adressa dad e-mail",
'confirmemail_body'         => 'Insatgi cun l\'adressa dad IP $1, probablamain ti, 
ha creà il conto "$2" sin {{SITENAME}} cun questa adressa dad e-mail.

Per confermar ch quest conto tutga propi a tai e per activar las 
funcziuns dad e-mail sin {{SITENAME}} stos ti avrir quest link en tes navigatur:

$3

Sche l\'adressa na tutga *betg* tar il conto numnà, suanda per plaschair a 
questa colliaziun per stizzar la confermaziun da questa adressa dad e-mail:

$5

Quest code da confermaziun scroda ils $4.',
'confirmemail_body_changed' => 'Insatgi, probablamain ti, cun l\'adressa dad IP $1 
ha midà l\'adressa d\'e-mail sin questa adressa dal conto "$2" sin {{SITENAME}}.

Per confermar ch quest conto tutga propi a tai e per reactivar las 
funcziuns dad e-mail sin {{SITENAME}} stos ti avrir quest link en tes navigatur:

$3

Sch\'il conto na tutga *betg* a tai, suanda per plaschair a questa colliaziun 
per stizzar la confermaziun da questa adressa dad e-mail:

$5

Quest code da confermaziun scroda ils $4.',
'confirmemail_body_set'     => 'Insatgi, probablamain ti, cun l\'adressa dad IP $1, 
ha midà l\'adressa dal conto "$2" sin {{SITENAME}} sin questa adressa. 

Per confermar che quest conto tutga propi a tai e per reactivar las
funcziuns dad e-mail sin {{SITENAME}} stos ti avrir quai link en il navigatur:

$3

Sche quai conto na tutga *betg* a tai, suanda il suandant link per 
sbittar la confermaziun da l\'adressa dad e-mail: 

$5

Quai code da confermaziun vegn a scrudar ils $6 las $7.',
'confirmemail_invalidated'  => "La confermaziun da l'adressa dad e-mail è vegnida stizzada",
'invalidateemail'           => "Stizzar la confermaziun da l'adressa dad e-mail",

# Scary transclusion
'scarytranscludedisabled' => "[L'integraziun interwiki è deactivada]",
'scarytranscludefailed'   => "[Betg reussì d'integrar in model per $1]",
'scarytranscludetoolong'  => '[URL è memia lunga]',

# Trackbacks
'trackbackbox'      => 'Trackbacks per questa pagina:<br />
$1',
'trackbackremove'   => '([Stizzar $1])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => 'Il trackback è vegnì stizzà cun success.',

# Delete conflict
'deletedwhileediting'      => "'''Attenziun:''' Questa pagina è vegnida stizzada suenter che ti has cumanzà a la modifitgar.",
'confirmrecreate'          => "L'utilisader [[User:$1|$1]] ([[User talk:$1|talk]]) ha stizzà quest artitgel (motiv: ''$2'') suenter che ti as cumenzà a modifitgar l'artitgel.
Conferma per plaschair che ti vuls propi crear danovamain quest artitgel.",
'confirmrecreate-noreason' => "L'utilisader [[User:$1|$1]] ([[User talk:$1|discussiun]]) ha stizzà questa pagina suenter che ti has cumenzà da la modifitgar. Conferma per plaschair che ti vuls recrear questa pagina.",
'recreate'                 => 'Crear danovamain',

# action=purge
'confirm_purge_button' => 'ok',
'confirm-purge-top'    => 'Stizzar il cache da questa pagina?',
'confirm-purge-bottom' => 'Svida il cache dad ina pagina e sforza da mussar la versiun actuala.',

# action=watch/unwatch
'confirm-watch-button'   => 'OK',
'confirm-watch-top'      => "Vuls ti agiuntar questa pagina a tia glista d'observaziun?",
'confirm-unwatch-button' => 'OK',
'confirm-unwatch-top'    => "Vuls ti allontanar questa pagina da tia glista d'observaziun?",

# Multipage image navigation
'imgmultipageprev' => '← ultima pagina',
'imgmultipagenext' => 'proxima pagina →',
'imgmultigo'       => 'Dai!',
'imgmultigoto'     => 'Ir a la pagina $1',

# Table pager
'ascending_abbrev'         => 'asc',
'descending_abbrev'        => 'desc',
'table_pager_next'         => 'Proxima pagina',
'table_pager_prev'         => 'Ultima pagina',
'table_pager_first'        => 'Emprima pagina',
'table_pager_last'         => 'Ultima pagina',
'table_pager_limit'        => 'Mussar $1 elements per pagina',
'table_pager_limit_label'  => 'Elements per pagina:',
'table_pager_limit_submit' => 'Dai',
'table_pager_empty'        => 'Nagins resultats',

# Auto-summaries
'autosumm-blank'   => 'La pagina è vegnida svidada',
'autosumm-replace' => 'Replazzà il cuntegn cun "$1"',
'autoredircomment' => 'Creà renviament a [[$1]]',
'autosumm-new'     => 'Creà la pagina cun "$1"',

# Live preview
'livepreview-loading' => 'Chargiar…',
'livepreview-ready'   => 'Chargiar… Pront!',
'livepreview-failed'  => 'Errur cun la prevista dinamica!
Emprova cun la prevista normala.',
'livepreview-error'   => 'Betg pussaivel da far la connexiun: $1 "$2".
Emprova cun la prevista normala',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Midadas pli novas che $1 {{PLURAL:$1|secunda|secundas}} vegnan eventualmain betg mussadas.',
'lag-warn-high'   => 'Causa da la gronda chargia da lavur sin il server da bancas da datas vegnan las modificaziuns da las {{PLURAL:$1|ultima secunda|ultimas $1 secundas}} anc betg mussadas en questa glista.',

# Watchlist editor
'watchlistedit-numitems'       => "Tia glista d'observaziun cuntegna {{PLURAL:$1|1 endataziun|$1 endataziuns}}; las paginas da discussiun na vegnan betg dumbradas.",
'watchlistedit-noitems'        => "Tia glista d'observaziun na cuntegna naginas endataziuns.",
'watchlistedit-normal-title'   => "Modifitgar la glista d'observaziun",
'watchlistedit-normal-legend'  => "Allontanar da la glista d'observaziun",
'watchlistedit-normal-explain' => 'Las endataziuns da tia glista d\'observaziun èn mussadas sutvart. 
Per allontanar ina endataziun, tscherna il quadert dasperas e clicca sin "{{int:Watchlistedit-normal-submit}}".
Ti pos era modifitgar tia glista d\'observaziun en il [[Special:EditWatchlist/raw|format da glistas]].',
'watchlistedit-normal-submit'  => 'Allontanar endataziuns',
'watchlistedit-normal-done'    => "{{PLURAL:$1|1 endataziun è vegnida|$1 endataziuns èn vegnidas}} allontanadas da la glista d'observaziun:",
'watchlistedit-raw-title'      => "Modifitgar la glista d'observaziun en il format da la glista",
'watchlistedit-raw-legend'     => "Modifitgar la glista d'observaziun en il format da la glista",
'watchlistedit-raw-explain'    => 'Quai èn las endataziuns da tia glista d\'observaziun en il format da glista. Ti pos agiuntar ed allontanar endataziuns da la glista. Fa dentant attenziun che ti pos be inditgar ina endataziun per lingia. 
Sche ti has terminà tia lavur, clicca sin "{{int:Watchlistedit-raw-submit}}".
Ti pos era utilisar [[Special:EditWatchlist|la pagina da standard]].',
'watchlistedit-raw-titles'     => 'Endataziuns:',
'watchlistedit-raw-submit'     => "Actualisar la glista d'observaziun",
'watchlistedit-raw-done'       => "Tia glista d'observaziun è vegnida actualisada.",
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 endataziun è vegnida agiuntada|$1 endataziuns èn vegnidas agiuntadas}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 endataziun è vegnida allontanada|$1 endataziuns èn vegnidas allontanadas}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Mussar las midadas relevantas',
'watchlisttools-edit' => "Mussar e modifitgar la glista d'observaziun",
'watchlisttools-raw'  => 'Modifitgar il format da la glista (import/export)',

# Core parser functions
'unknown_extension_tag' => 'Tag d\'extensiun nunenconuschent "$1"',
'duplicate-defaultsort' => '\'\'\'Attenziun:\'\'\' La clav da zavrar da standard "$2" remplazza la clav da zavrar da standard veglia "$1".',

# Special:Version
'version'                       => 'Versiun',
'version-extensions'            => 'Extensiuns installadas',
'version-specialpages'          => 'Paginas spezialas',
'version-parserhooks'           => 'Hooks dal parser',
'version-variables'             => 'Variablas',
'version-antispam'              => 'Prevenziun da spam',
'version-skins'                 => 'Skins',
'version-other'                 => 'Auter',
'version-mediahandlers'         => 'Manipulaturs da meds',
'version-hooks'                 => 'Hooks',
'version-extension-functions'   => 'Funcziuns dad extensiuns',
'version-parser-extensiontags'  => 'Tags che extendan il parser',
'version-parser-function-hooks' => 'Hooks per funcziuns dal parser',
'version-hook-name'             => 'Num dal hook',
'version-hook-subscribedby'     => 'Abonnà da',
'version-version'               => '(Versiun $1)',
'version-license'               => 'Licenza',
'version-poweredby-credits'     => "Questa wiki utilisescha '''[//www.mediawiki.org/ MediaWiki]''', copyright © 2001-$1 $2.",
'version-poweredby-others'      => 'auters',
'version-license-info'          => "MediaWiki è software liba; ti la pos redistribuir e/u la modifitgar tenor ils terms da la GNU General Public License sco ch'ella vegn publitgada da la Free Software Foundation; ti pos utilisar la versiun 2 da la licenza u (sche ti vul) mintga versiun che succeda. 

MediaWiki vegn distribuì en la speranza che questa software saja utila, dentant SENZA MINTGA GARANZIA; era senza garanzia implizita da NEGOZIABILITAD u ADDATAZIUN PER IN INTENT SPECIAL. Guarda la GNU General Public License per ulteriurs detagls. 

Ti duessas avair retschavì [{{SERVER}}{{SCRIPTPATH}}/COPYING ina copia da la GNU General Public License] cun quest program; sche na betg, scriva a la Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA u [//www.gnu.org/licenses/old-licenses/gpl-2.0.html la legia online].",
'version-software'              => 'Software installada',
'version-software-product'      => 'Product',
'version-software-version'      => 'Versiun',

# Special:FilePath
'filepath'         => 'Percurs da la datoteca',
'filepath-page'    => 'Datoteca:',
'filepath-submit'  => 'Dai',
'filepath-summary' => 'Questa pagina speciala inditgescha l\'adressa cumpletta per ina datoteca. 
Maletgs vegnan mussads en resoluziun cumpletta, auters tips da datoteca vegnan averts directamain cun lur program assozià. 

Endatescha il num da datoteca senza il prefix "{{ns:file}}:".',

# Special:FileDuplicateSearch
'fileduplicatesearch'           => 'Tschertgar datotecas dublas',
'fileduplicatesearch-summary'   => 'Tschertgar datotecas dublas a basa da valurs da hash.',
'fileduplicatesearch-legend'    => 'Tschertgar duplicats',
'fileduplicatesearch-filename'  => 'Num da datoteca:',
'fileduplicatesearch-submit'    => 'Tschertgar',
'fileduplicatesearch-info'      => '$1 x $2 pixels<br />Grondezza da datoteca: $3<br />Tip da MIME: $4',
'fileduplicatesearch-result-1'  => 'La datoteca "$1" n\'ha nagin dublicat identic.',
'fileduplicatesearch-result-n'  => 'La datoteca "$1" ha {{PLURAL:$2|in dublicat identic|$2 dublicats identics}}.',
'fileduplicatesearch-noresults' => 'Betg chattà ina datoteca cun il num "$1".',

# Special:SpecialPages
'specialpages'                   => 'Paginas spezialas',
'specialpages-note'              => '----
* Paginas spezialas normalas.
* <span class="mw-specialpagerestricted">Paginas spezialas restrenschidas.</span>
* <span class="mw-specialpagecached">Paginas spezialas en il cache (pon esser antiquadas).</span>',
'specialpages-group-maintenance' => 'Rapports da mantegnamant',
'specialpages-group-other'       => 'Autras paginas spezialas',
'specialpages-group-login'       => "T'annunziar / registrar",
'specialpages-group-changes'     => 'Ultimas midadas e logs',
'specialpages-group-media'       => 'Rapports ed elements multimedials',
'specialpages-group-users'       => 'Utilisaders e dretgs',
'specialpages-group-highuse'     => 'Paginas utilisadas savens',
'specialpages-group-pages'       => 'Glistas da paginas',
'specialpages-group-pagetools'   => 'Utensils per paginas',
'specialpages-group-wiki'        => 'Utensils e datas da la wiki',
'specialpages-group-redirects'   => 'Paginas specialas che renvieschan',
'specialpages-group-spam'        => 'Utensils da spam',

# Special:BlankPage
'blankpage'              => 'Pagina vida',
'intentionallyblankpage' => 'Questa pagina è aposta vida.',

# External image whitelist
'external_image_whitelist' => " #Betg midar questa lingia<pre>
#Inserescha sutvart fragments (be la part tranter ils //) dad expressiuns regulars 
#Quels vegnan cumparegliads cun las URLs da maletgs externs 
#Quels che correspundan vegnan mussads sco maletgs, autr'uisa vegn be la colliaziun al maletg mussà
#Lingias che cumenzan cun in # vegnan tractadas sco commentaris
#Scripziun en maiusclas e minusclas na vegn betg resguardada

#Tut ils fragments da regex stos ti inserir sur questa lingia. Lascha questa lingia sco quai ch'ella è</pre>",

# Special:Tags
'tags'                    => 'Signalisaziuns per midadas',
'tag-filter'              => 'Filtrar las [[Special:Tags|Signalisaziuns]]:',
'tag-filter-submit'       => 'Filtrar',
'tags-title'              => 'Signalisaziuns',
'tags-intro'              => "Questa pagina mussa tut las singalisaziuns che la software po duvrar per marcar modificaziuns e declera tge ch'ellas muntan.",
'tags-tag'                => 'Num da la signalisaziun',
'tags-display-header'     => 'Num sin la glista da modificaziuns',
'tags-description-header' => 'Descripziun cumpletta da la muntada',
'tags-hitcount-header'    => 'Modificaziuns signalisadas',
'tags-edit'               => 'modifitgar',
'tags-hitcount'           => '$1 {{PLURAL:$1|midada|midadas}}',

# Special:ComparePages
'comparepages'     => 'Cumparegliar paginas',
'compare-selector' => "Cumparegliar versiuns d'ina pagina",
'compare-page1'    => 'Pagina 1',
'compare-page2'    => 'Pagina 2',
'compare-rev1'     => 'Versiun 1',
'compare-rev2'     => 'Versiun 2',
'compare-submit'   => 'Cumparegliar',

# Database error messages
'dberr-header'      => 'Questa wiki ha in problem',
'dberr-problems'    => 'Stgisa!
Questa pagina ha actualmain difficultads tecnicas.',
'dberr-again'       => 'Spetga in per minutas ed emprova alura da chargiar danovamain.',
'dberr-info'        => '(Betg pussaivel da contactar il server da la banca da datas: $1)',
'dberr-usegoogle'   => 'Ti pos empruvar da tschertgar cun Google en il fratemp.',
'dberr-outofdate'   => 'Resguarda che lur index da noss cuntegn po esser antiquà.',
'dberr-cachederror' => 'Quai è ina copia or dal cache da questa pagina ed è eventualmain betg actuala.',

# HTML forms
'htmlform-invalid-input'       => 'I ha dà problems cun intginas da tias endataziuns',
'htmlform-select-badoption'    => "La valur inditgada n'è betg ina opziun valida.",
'htmlform-int-invalid'         => "La valur inditgada n'è betg ina cifra entira (integer).",
'htmlform-float-invalid'       => "La valur che ti has inditgà n'è betg ina cifra.",
'htmlform-int-toolow'          => 'La valur che ti has inditgà è sut il minimum da $1',
'htmlform-int-toohigh'         => 'La valur che ti has inditgà è sur il maximum da $1',
'htmlform-required'            => 'Questa valur è necessaria',
'htmlform-submit'              => 'Trametter',
'htmlform-reset'               => 'Revocar las midadas',
'htmlform-selectorother-other' => 'Auters',

# SQLite database support
'sqlite-has-fts' => '$1 cun sustegn per la retschertga da text integrala',
'sqlite-no-fts'  => '$1 senza sustegn per la retschertga da text integrala',

);

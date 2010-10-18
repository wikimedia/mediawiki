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
'tog-highlightbroken'         => 'Formatar links betg existents <a href="" class="new">uschia</a> (alternativa: uschia<a href="" class="internal">?</a>)',
'tog-justify'                 => "Text en furma da 'bloc'",
'tog-hideminor'               => 'Zuppentar pitschnas midadas en las ultimas midadas',
'tog-hidepatrolled'           => 'Zuppentar midadas controlladas en las «ultimas midadas»',
'tog-newpageshidepatrolled'   => 'Zuppentar paginas controlladas en las «paginas novas»',
'tog-extendwatchlist'         => "Extender la glista d'observaziun per mussar tut las midadas e betg be las ultimas",
'tog-usenewrc'                => "Duvrar la versiun extendida da las ''Ulimas midadas'' (basegna JavaScript)",
'tog-numberheadings'          => 'Numerar automaticamain ils titels',
'tog-showtoolbar'             => "Mussa la trav d'utensils (basegna JavaScript)",
'tog-editondblclick'          => 'Modifitgar paginas cun in clic dubel (basegna JavaScript)',
'tog-editsection'             => 'Mussar links [modifitgar] per modifitgar singulas secziuns',
'tog-editsectiononrightclick' => 'Activar la pussaivladad da modifitgar secziuns cun in clic dretg (basegna JavaScript)',
'tog-showtoc'                 => 'Mussar ina tabla da cuntegn sin paginas cun dapli che trais tetels',
'tog-rememberpassword'        => "S'annunziar permanantamain cun quest navigatur (per maximalmain $1 {{PLURAL:$1|di|dis}})",
'tog-watchcreations'          => "Observar paginas ch'jau hai creà",
'tog-watchdefault'            => "Observar paginas ch'jau hai edità",
'tog-watchmoves'              => "Observar paginas ch'jau hai spustà",
'tog-watchdeletion'           => "Observar paginas ch'jau hai stizzà",
'tog-previewontop'            => "Mussar la prevista sur il champ d'endatziun",
'tog-previewonfirst'          => "Adina mussar la prevista suenter l'emrima modificaziun",
'tog-nocache'                 => 'Impedir che tes navigatur memorisescha las paginas en il cache',
'tog-enotifwatchlistpages'    => "Trametta in e-mail sch'ina pagina sin mia glista d'observaziun vegn midada",
'tog-enotifusertalkpages'     => "Trametta in e-mail sch'i ha dà midadas sin mia pagina da discussiun.",
'tog-enotifminoredits'        => 'Trametta era in e-mail tar pitschnas midadas da las paginas',
'tog-enotifrevealaddr'        => "Mussar mia adressa dad e-mail en e-mails d'avis",
'tog-shownumberswatching'     => "Mussar il dumber d'utilisaders che obervan questa pagina",
'tog-oldsig'                  => 'Prevista da la signatura actuala:',
'tog-fancysig'                => "Suttascripziun senza link automatic tar la pagina da l'utilisader.",
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
'tog-showhiddencats'          => 'Mussar categorias zuppendatas',
'tog-norollbackdiff'          => 'Betg mussar las differenzas suenter revocar',

'underline-always'  => 'adina suttastritgar',
'underline-never'   => 'mai suttastritgar',
'underline-default' => 'surprender standard dal browser',

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

'mainpagetext'      => "'''MediaWiki è vegnì installà cun success.'''",
'mainpagedocfooter' => "Consultai il [http://meta.wikimedia.org/wiki/Help:Contents manual per utilisaders] per infurmaziuns davart l'utilisaziun da questa software da wiki.

== Cumenzar ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Glista da las opziuns per la configuraziun]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Glista da mail da MediaWiki cun annunzias da novas versiuns]",

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
'vector-action-addsection' => 'Agiuntar chapitel',
'vector-action-delete'     => 'Stizzar',
'vector-action-move'       => 'Spustar',
'vector-action-protect'    => 'Bloccar',
'vector-action-undelete'   => 'Restituir',
'vector-action-unprotect'  => 'Debloccar',
'vector-view-create'       => 'Crear',
'vector-view-edit'         => 'Modifitgar',
'vector-view-history'      => 'Cronologia',
'vector-view-view'         => 'Leger',
'vector-view-viewsource'   => 'Mussar code da funtauna',
'actions'                  => 'Acziuns',
'namespaces'               => 'Tip da pagina',
'variants'                 => 'Variantas',

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
'info_short'        => 'Infurmaziun',
'printableversion'  => 'versiun per stampar',
'permalink'         => 'Link permanent',
'print'             => 'stampar',
'edit'              => 'Modifitgar',
'create'            => 'Crear',
'editthispage'      => 'Modifitgar questa pagina',
'create-this-page'  => 'Crear questa pagina',
'delete'            => 'Stizzar',
'deletethispage'    => 'Stizzar questa pagina',
'undelete_short'    => 'Revocar {{PLURAL:$1|ina modificaziun|$1 modificaziuns}}',
'protect'           => 'proteger',
'protect_change'    => 'midar',
'protectthispage'   => 'Proteger questa pagina',
'unprotect'         => 'Nunprotegì',
'unprotectthispage' => 'Annullar la protecziun da questa pagina',
'newpage'           => 'Nova pagina',
'talkpage'          => 'Discutar quest artitgel',
'talkpagelinktext'  => 'Discussiun',
'specialpage'       => 'Pagina speziala',
'personaltools'     => 'Utensils persunals',
'postcomment'       => 'Nova secziun',
'articlepage'       => 'Mussar la pagina da cuntegn',
'talk'              => 'discussiun',
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
'otherlanguages'    => 'En auteras linguas',
'redirectedfrom'    => '(renvià da $1)',
'redirectpagesub'   => "questa pagina renviescha tar in'auter artitgel",
'lastmodifiedat'    => "L'ultima modificaziun da questa pagina: ils $1 a las $2.",
'viewcount'         => 'Questa pagina è vegnida contemplada {{PLURAL:$1|ina giada|$1 giadas}}.',
'protectedpage'     => 'Pagina protegida',
'jumpto'            => 'Midar tar:',
'jumptonavigation'  => 'navigaziun',
'jumptosearch'      => 'tschertga',
'view-pool-error'   => 'Stgisa, ils servers èn actualmain surchargiads. 
Memia blers utilisaders emprovan da chargiar questa pagina. 
Spetga per plaschair in mument avant che ti eprovas da puspè contemplar questa pagina. 

$1',
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
'versionrequiredtext' => 'Ti dovras versiun $1 da mediawiki per duvrar questa pagina. Guarda [[Special:Version| qua!]]',

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

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Artitgel',
'nstab-user'      => "Pagina da l'utilisader",
'nstab-media'     => 'Pagina da medias',
'nstab-special'   => 'Pagina speziala',
'nstab-project'   => 'pagina dal project',
'nstab-image'     => 'Datoteca',
'nstab-mediawiki' => 'messadi',
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
'editinginterface'     => "'''Attenziun:''' Questa pagina cuntegn text che vegn duvra da software da mediawiki. Midadas influenzeschan directamain l'interface da l'utilisader. Sche ti vuls far translaziuns u correcturas: Studegia da far quai sin [http://translatewiki.net/wiki/Main_Page?setlang=rm translatewiki.net], per che las midadas pon vegnidas surprendidas da tut ils projects.",
'sqlhidden'            => '(Zuppentà la dumonda da SQL)',
'namespaceprotected'   => "Ti n'has betg la lubientscha da modifitgar paginas dal tip da pagina '''$1'''.",
'ns-specialprotected'  => 'Paginas spezialas no pon betg vegnir modifitgadas.',

# Virus scanner
'virus-badscanner'     => "Configuraziun fauss: antivirus nunenconuschent: ''$1''",
'virus-scanfailed'     => 'Scan betg reussì (code $1)',
'virus-unknownscanner' => 'antivirus nunenconuschent:',

# Login and logout pages
'logouttext'                 => "'''Sortì cun success.'''

Ti pos cuntinuar cun utilisar {{SITENAME}} anonimamain, u che ti pos [[Special:UserLogin|t'annunziar]] sco medem u in'auter utilisader. Resguarda che entginas paginas pon anc vesair or tuttina sco sche ti eras annunzià enfin che ti has stizzà il cache da tes browser.",
'welcomecreation'            => '==Bainvegni, $1! ==
Tes conto è vegni creà.
Betg emblida da midar tias [[Special:Preferences|preferenzas da {{SITENAME}}]].',
'yourname'                   => "Num d'utilisader",
'yourpassword'               => 'pled-clav',
'yourpasswordagain'          => 'repeter pled-clav',
'remembermypassword'         => "S'annunziar permanantamain sin quest computer (per maximalmain $1 {{PLURAL:$1|di|dis}})",
'yourdomainname'             => 'Vossa domain',
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
'createaccountmail'          => 'per e-mail',
'createaccountreason'        => 'Motiv:',
'badretype'                  => 'Ils dus pleds-clav na corrispundan betg.',
'userexists'                 => "Quest num d'utilisader vegn gia duvrà. Tscherna per plaschair in'auter.",
'loginerror'                 => "Sbagl cun t'annunziar",
'createaccounterror'         => 'Betg pussaivel da crear in conto: $1',
'nocookiesnew'               => "Il conto da l'utilisader è vegnì creà, ti es dentant betg t'annunzià. 
{{SITENAME}} utilisescha cookies per che utilisaders pon s'annunziar. 
Ti has deactivà ils cookies. 
Als activescha per plaschair e lura t'annunzia cun tes nov num d'utilisader e pled-clav.",
'nocookieslogin'             => "{{SITENAME}} utilisescha cookies per ch'utilisaders pon s'annunziar.
Ti has deactivà tes cookies.
Activescha per plaschair ils cookis en tes navigatur ed emprova danovamain.",
'noname'                     => "Ti n'has betg inditgà in num d'utilisader valid.",
'loginsuccesstitle'          => "T'annunzià cun success",
'loginsuccess'               => "'''Ti es t'annunzia tar {{SITENAME}} sco \"\$1\".'''",
'nosuchuser'                 => 'I exista nagin utilisader cun il num "$1".
Fa stim dad utilisar correctamain maiusclas e minusclas.
Curregia il num u [[Special:UserLogin/signup|creescha in nov conto]].',
'nosuchusershort'            => 'I dat nagin utilisader cun il num "<nowiki>$1</nowiki>".
Curregia ti\'endataziun.',
'nouserspecified'            => "Inditgescha per plaschair in num d'utilisader.",
'login-userblocked'          => "Quest utilisader è bloccà. Betg pussaivel da t'annunziar.",
'wrongpassword'              => "Quai n'era betg il pled-clav correct. Prova anc ina giada.",
'wrongpasswordempty'         => 'Ti as emblidà da scriver tes pled-clav. Prova anc ina giada.',
'passwordtooshort'           => 'Tes pled-clav sto cuntegnair almain {{PLURAL:$1|in bustab|$1 bustabs}}.',
'password-name-match'        => "Il pled-clav na dastga betg esser il medem sco il num d'utilisader.",
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
'loginlanguagelabel'         => 'Lingua: $1',
'suspicious-userlogout'      => "Tia dumonda per partir è vegnida refusada perquai ch'i para ch'ella è vegnida tramessa d'in navigatur che funcziuna betg correctamain u d'in proxy da cache.",

# Password reset dialog
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
'math_sample'     => 'Scriva qua tia furmla',
'math_tip'        => 'Furmla matematica (LaTeX)',
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
'watchthis'                        => 'observar quest artitgel',
'savearticle'                      => "memorisar l'artitgel",
'preview'                          => 'prevista',
'showpreview'                      => 'mussar prevista',
'showlivepreview'                  => 'prevista directa',
'showdiff'                         => 'mussar midadas',
'anoneditwarning'                  => "Vus essas betg annunziads. Empè dal num d'utilisader vign l'adressa dad IP registrada en la historia da las versiuns.",
'missingsummary'                   => "'''Avis:''' Ti has betg inditgà ina resumaziun. 
Sche ti cliccas danovamain \"{{int:savearticle}}\" vegn tia midada memorisada senza ina tala.",
'missingcommenttext'               => 'Endatescha per plaschair ina resumaziun.',
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
'userpage-userdoesnotexist'        => 'Il conto d\'utilisader "$1" n\'èxista betg.
Controllescha sch ti vuls propi crear/modiftgar questa pagina.',
'userpage-userdoesnotexist-view'   => 'Il conto d\'utilisader "$1" n\'exista betg.',
'blocked-notice-logextract'        => "Quai utilisader è bliccà actualmain. 
L'ultima endataziun dal log da bloccar vegn mussà sutvart sco referenza:",
'clearyourcache'                   => "'''Remartga''' Svida il chache da tes browser suenter avair memorisà, per vesair las midadas.
'''Mozilla / Firefox / Safari:''' tegnair ''Shift'' durant cliccar ''chargiar danovamain'', u smatgar ''Ctrl-F5'' u ''Ctrl-R'' (''Command-R'' sin in Macintosh);
'''Konqueror: '''clicca ''Reload'' u smatga ''F5'';
'''Opera:''' stizzar il cache sut ''Tools → Preferences'';
'''Internet Explorer:''' tegna ''Ctrl'' durant cliccar ''Refresh,'' u smatga ''Ctrl-F5''.",
'usercssyoucanpreview'             => "'''Tip:''' Utilisescha il buttun \"{{int:showpreview}}\" per testar tes nov CSS avant che memorisar.",
'userjsyoucanpreview'              => "'''Tip:''' Utilisescha il buttun \"{{int:showpreview}}\" per testar tes nov JavaScript avant che memorisar.",
'usercsspreview'                   => "'''Fa stim che quai è be ina prevista da tes CSS d'utilisader.'''
'''El n'è anc betg memorisà.'''",
'userjspreview'                    => "'''Fa stim che quai è be ina prevista da tes JavaScript d'utilisader.'''
'''El n'è anc betg memorisà.'''",
'userinvalidcssjstitle'            => "'''Attenziun:''' I n'exista nagin skin \"\$1\".
Fa stim che titels da paginas persunalisadas .css u .js vegnan scrits pitschen, p. ex. {{ns:user}}:Foo/monobook.css e betg {{ns:user}}:Foo/Monobook.css.",
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
'editing'                          => 'Modifitgar $1',
'editingsection'                   => 'Modifitgar $1 (secziun)',
'editingcomment'                   => 'Modifitgar $1 (nova secziun)',
'editconflict'                     => 'Conflict da modifitgar: $1',
'explainconflict'                  => "Insatgi auter ha midà questa pagina dapi che ti has cumenzà a la modifitgar. 
Il champ d'endataziun sura cuntegna il text sco che la pagina vesa ora actualmain. 
Tias midadas èn mussadas en il champ d'endataziun sut. 
Ti stos integrar tias midadas en il text existent. 
'''Mo''' il text en il champ d'endataziun sura vegn memorià sche ti cliccas sin \"Memorisar l'artitgel\".",
'yourtext'                         => 'Voss text',
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
'longpagewarning'                  => "'''ADATG: Questa artitgel è $1 kilobytes gronda. Insaquants browsers
han forsa problems cun modifitgar artitgels da la grondezza 32kb u pli grond.
Ponderai per plaschair da divider quest artitgel en pli pitschnas parts. '''",
'longpageerror'                    => "'''Errur: Il text che ti has tramess è $1 kilobytes gronds. Quei ei pli grond ch'il maximum da $2 kilobytes. Il text na po betg vegnir memorisà. '''",
'readonlywarning'                  => "'''Attenziun: La banca da datas è vegnida bloccada per motivs da mantegniment, uschia che ti na sas betg memorisar tias modificaziuns gest ussa.'''
Per betg perder las midadas ta recummandain nus da copiar il text en in editur da text sin tes computer ed al memorisar per pli tard. 

Igl adminstratur che ha bloccà la banca da datas ha inditgà suandant motiv: $1",
'protectedpagewarning'             => "'''Attenziun: Questa pagina è vegnida bloccada, uschè che be utilisaders cun dretgs dad administraturs la pon modifitgar. '''
Sco infurmaziun vegn mussada sutvart l'ultima endataziun dal cudesch da log:",
'semiprotectedpagewarning'         => "'''Attenziun: Questa pagina è vegnida bloccada, uschè che be utilisaders registrads la pon modifitgar.'''
Sco infurmaziun vegn mussada sutvart l'ultima endataziun dal cudesch da log:",
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

# Account creation failure
'cantcreateaccounttitle' => 'Betg pussaivel da crear il conto',

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
'rev-deleted-comment'         => '(eliminà commentari)',
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
'rev-delundel'                => 'mussar/zuppentar',
'rev-showdeleted'             => 'mussar',
'revisiondelete'              => 'Stizzar/restituir versiuns',
'revdelete-nooldid-title'     => 'Inditgà versiun nunvalida',
'revdelete-show-file-submit'  => 'Gea',
'revdelete-selected'          => "'''{{PLURAL:$2|Versiun tschernida|Versiuns tschernidas}} da [[:$1]]:'''",
'revdelete-radio-set'         => 'Gea',
'revdelete-radio-unset'       => 'Na',
'revdelete-log'               => 'Motiv:',
'revdel-restore'              => 'midar la visibilitad',
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
'mergehistory'        => 'Unir la cronologia da paginas',
'mergehistory-header' => "Cun questa pagina speziala pos ti integrar versiuns da la cronologia d'ina pagina originala en ina pagina pli nova. 
Controllescha che questa midada mantegna la cuntinuitad istorica.",
'mergehistory-box'    => 'Unir la cronologia da duas paginas:',
'mergehistory-from'   => "Pagina d'origin:",
'mergehistory-into'   => 'Pagina da destinaziun:',
'mergehistory-list'   => 'Versiuns che pon vegnir unidas',

# Merge log
'revertmerge' => 'Revocar la fusiun',

# Diffs
'history-title'            => 'Cronica da versiuns da "$1"',
'difference'               => '(differenza tranter versiuns)',
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
'search-nonefound'                 => 'Per il term tschertga èn nagins resultats vegnids chattads.',
'powersearch'                      => 'retschertgar',
'powersearch-legend'               => 'Tschertga extendida',
'powersearch-ns'                   => 'Tschertgar en tips da pagina:',
'powersearch-redir'                => 'Mussar sviaments',
'powersearch-field'                => 'Tschertgar',
'powersearch-togglelabel'          => 'Tscherna:',
'powersearch-toggleall'            => 'tuts',
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
'prefs-math'                  => 'TeX',
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
'contextlines'                => 'Lingia per resultat:',
'contextchars'                => 'Segns per lingia:',
'recentchangesdays'           => 'Dumber da dis che duain vegnir mussads en las ultimas midadas:',
'recentchangesdays-max'       => 'Maximalmain $1 {{PLURAL:$1|di|dis}}',
'recentchangescount'          => 'Dumber da modificaziuns che duai vegnir mussà sco standard:',
'savedprefs'                  => 'Tias preferenzas èn vegnidas memorisadas.',
'timezonelegend'              => "Zona d'urari:",
'localtime'                   => 'Temp local:',
'timezoneuseserverdefault'    => 'Utilisar il standard dil server',
'timezoneuseoffset'           => 'Auter (inditgar la differenza)',
'timezoneoffset'              => 'Differenza¹:',
'servertime'                  => 'Temp dal server:',
'guesstimezone'               => 'Emplenescha dal browser',
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
'yourlanguage'                => 'linguatg:',
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
'prefs-help-email'            => "L'adressa dad e-mail è opziunala, pussibilitescha dentant da trametter in nov pled-clav en cass da sperdita. Plinavant pussibilitescha ella ad auters utilisaders da contactar tai per e-mail senza che ti stos publitgar tia identitad.",
'prefs-help-email-required'   => "Inditgar in'adressa dad e-mail è obligatoric.",
'prefs-info'                  => 'Infurmaziuns da basa',
'prefs-i18n'                  => 'Internaziunalisaziun',
'prefs-signature'             => 'Signatura',
'prefs-dateformat'            => 'Format da la data',
'prefs-timeoffset'            => 'Differenza da temp',
'prefs-advancedediting'       => 'Opziuns avanzadas',
'prefs-advancedrc'            => 'Opziuns avanzadas',
'prefs-advancedrendering'     => 'Opziuns avanzadas',
'prefs-advancedsearchoptions' => 'Opziuns avanzadas',
'prefs-advancedwatchlist'     => 'Opziuns avanzadas',
'prefs-displayrc'             => 'Opziuns da visualisar',
'prefs-diffs'                 => 'Cumparegliaziun da versiuns',

# User rights
'userrights'               => "Administraziun da dretgs d'utilisaders",
'userrights-lookup-user'   => "Administrar gruppas d'utilisaders",
'userrights-user-editname' => "Inditgescha in num d'utilisader:",
'editusergroup'            => "Modifitgar las gruppas d'utilisaders",
'editinguser'              => "Midar ils dretgs da l'utilisader '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup' => "Modifitgar las gruppas d'utilisaders",
'saveusergroups'           => "Memorisar questas gruppas d'utilisaders",
'userrights-groupsmember'  => 'Commember da:',
'userrights-nologin'       => "Ti stos [[Special:UserLogin|t'annunziar]] cun in conto d'aministratur per modifitgar ils dretgs d'utilisader.",

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
'right-read'                 => 'Leger paginas',
'right-edit'                 => 'Modifitgar paginas',
'right-createpage'           => 'Crear paginas (danor paginas da discussiun)',
'right-createtalk'           => 'Crear paginas da discussiun',
'right-createaccount'        => "Crear novs contos d'utilisader",
'right-minoredit'            => 'Marcar modificaziuns sco pitschnas',
'right-move'                 => 'Spustar paginas',
'right-move-subpages'        => 'Spustar paginas cun las subpaginas',
'right-movefile'             => 'Spustar datotecas',
'right-suppressredirect'     => 'Impedir da crear renviaments cun spustar paginas',
'right-upload'               => 'Chargiar si datotecas',
'right-reupload'             => 'Recuvrir (surscriver) datotecas',
'right-reupload-own'         => "Recuvrir (surscriver) datotecas existentas ch'èn vegnidas chargiadas si da quest utilisader sez",
'right-reupload-shared'      => "Recuvrir (surscriver) localmain ina datoteca ch'è disponibla en in repositori communabel",
'right-upload_by_url'        => "Chargiar si datotecas d'ina URL",
'right-delete'               => 'Stizzar paginas',
'right-bigdelete'            => 'Stizzar paginas cun ina gronda cronologia',
'right-deleterevision'       => 'Stizzar e restituir versiuns specificas da paginas',
'right-deletedhistory'       => 'Vesair endataziuns da la cronologia stizzadas, senza il text assozià',
'right-deletedtext'          => 'Vesair text stizzà e midadas tranter versiuns stizzadas',
'right-browsearchive'        => 'Tschertgar paginas stizzadas',
'right-undelete'             => 'Restituir ina pagina',
'right-suppressrevision'     => "Examinar e restituir versiuns ch'èn era betg vesaivlas per ils administraturs",
'right-suppressionlog'       => 'Vesair logs privats',
'right-block'                => "Impedir utilisaders ch'els pon betg pli modifitgar",
'right-blockemail'           => "Impedir utilisaders ch'els pon trametter e-mails",
'right-hideuser'             => "Bloccar e zuppentar in num d'utilisader",
'right-editinterface'        => "Modifitgar l'interfascha per l'utilisader",
'right-editusercssjs'        => "Modifitgar datotecas da CSS e JavaScript d'auters utilisaders",
'right-editusercss'          => "Modifitgar datotecas da CSS d'auters utilisaders",
'right-edituserjs'           => "Modifitgar datotecas da JavaScript d'auters utilisaders",
'right-rollback'             => "Revocar svelt las modificaziuns da l'ultim utilisader che ha modifitgà ina pagina particulara.",
'right-import'               => "Importar paginas d'auters wikis",
'right-importupload'         => "Importar paginas d'ina datoteca chargiada si",
'right-patrol'               => "Marcar modificaziuns d'auters sco controlladas",
'right-autopatrol'           => 'Marcar sias atgnas modificaziuns automaticamain sco controlladas',
'right-unwatchedpages'       => 'Vesair ina glista da las paginas betg contempladas',
'right-trackback'            => 'Trametter in trackback',
'right-mergehistory'         => 'Unir la cronologia da questa pagina',
'right-userrights'           => 'Modifitgar ils dretgs dals utilisaders',
'right-userrights-interwiki' => "Modifitgar ils dretgs d'utilisaders sin autras wikis",
'right-siteadmin'            => 'Bloccar u debloccar la banca da datas',
'right-sendemail'            => 'Trametter e-mails ad auters utilisaders',

# User rights log
'rightslog'      => "Log dals dretgs d'utilisader",
'rightslogtext'  => "Quai è il log da las midadas en ils dretgs d'utilisaders.",
'rightslogentry' => 'midar la commembranza da $1 davent da $2 a $3',
'rightsnone'     => '(nagins)',

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
'show'                              => 'mussar',
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
'recentchangeslinked'          => 'midadas sin paginas cun links',
'recentchangeslinked-feed'     => 'midadas sin paginas cun links',
'recentchangeslinked-toolbox'  => 'midadas sin paginas cun links',
'recentchangeslinked-title'    => 'Midadas en artitgels ch\'èn colliads cun "$1"',
'recentchangeslinked-noresult' => 'Naginas midadas sin artitgels collads durant la perioda endatada.',
'recentchangeslinked-summary'  => "Quest è ina glista da las midadas ch'èn vegnidas fatgas da curt en artitgels ch'èn colliads cun ina pagina specifica (ni en commembers d'ina categoria specifica).
Paginas sin [[Special:Watchlist|tia glista d'observaziun]] èn '''grassas'''.",
'recentchangeslinked-page'     => 'Num da la pagina:',
'recentchangeslinked-to'       => 'Mussar midadas da paginas che han ina colliaziun a questa pagina',

# Upload
'upload'                      => 'Chargiar si in file',
'uploadbtn'                   => 'Chargiar si il file',
'reuploaddesc'                => 'Anavos tar la pagina da chargiar si.',
'upload-tryagain'             => 'Trametter la descripziun da la datoteca midada',
'uploadnologin'               => "Betg t'annunzià",
'uploadnologintext'           => "Ti stos [[Special:UserLogin|t'annunziar]] per chargiar si files.",
'upload_directory_missing'    => "L'ordinatur per chargiar viaden ($1) n'exista betg e na pudeva betg vegnir creà dal server.",
'upload_directory_read_only'  => "L'ordinatur per chargiar viaden ($1) na po betg vegnir modifitgà dal server.",
'uploaderror'                 => 'Errur cun chargiar si',
'uploadtext'                  => "Utilisescha quest formular per chargiar si datotecas.
Per contemplar u tschertgar datotecas gia chargiada si, visita la pagina [[Special:FileList|list of uploaded files]]. Tut las datotecas che vegnan chargiadas si èn era notads en il [[Special:Log/upload|log da chargiar si]], quellas ch'èn vegnidas stizzadas en il [[Special:Log/delete|log dal stizzar]].

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
'badfilename'                 => 'Midà num dal file sin "$1".',
'filetype-badmime'            => 'Datotecas dal tip da MIME "$1" na dastgan betg vegnir chargiads si.',
'largefileserver'             => "Quest file è memia gronds. Il server è configurà uschè ch'el accepta be files enfin ina tscherta grondezza.",
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
'file-deleted-duplicate'      => 'Ina daoteca identica a questa ([[$1]]) è vegnida stizzada pli baud.
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
'sourcefilename'              => 'file sin tes computer:',
'sourceurl'                   => 'URL da funtauna:',
'destfilename'                => 'num dal file sin il server:',
'upload-maxfilesize'          => 'Grondezza da datoteca maximala: $1',
'upload-description'          => 'Descripziun da la datoteca',
'upload-options'              => 'Opziuns per chargiar si',
'watchthisupload'             => 'Observar questa datoteca',

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
'imgfile'               => 'datoteca',
'listfiles'             => 'Glista dals maletgs',
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
'imagelinks'                => 'Paginas che cuntegnan la datoteca',
'linkstoimage'              => '{{PLURAL:$1|La suandanta pagina è colliada|Las suandantas $1 paginas èn colliadas}} cun questa datoteca:',
'nolinkstoimage'            => 'Naginas paginas mussan sin questa datoteca.',
'redirectstofile'           => '{{PLURAL:$1|Suandanta datoteca renviescha|Suandantas $1 datotecas renvieschan}} a questa datoteca:',
'sharedupload'              => 'Quai è ina datoteca da $1 e vegn eventualmain utilisada dad auters projects.',
'uploadnewversion-linktext' => 'Chargiar si ina nova versiun da questa datoteca',

# MIME search
'mimesearch' => 'tschertgar tenor tip da MIME',
'download'   => 'telechargiar',

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
'statistics'              => 'Statisticas',
'statistics-header-pages' => 'Statistica da paginas',
'statistics-header-edits' => 'Statistica da modificaziuns',
'statistics-header-users' => 'Statisticas davart ils utilisaders',
'statistics-pages-desc'   => 'Tut las paginas en la vichi, inclusivamain paginas da discussiun, renviaments, etc.',

'disambiguations'     => 'pagina per la decleraziun da noziuns',
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

'withoutinterwiki' => 'Artitgels senza colliaziuns ad autras linguas',

'fewestrevisions' => 'Artitgels cun las pli biaras actualisaziuns',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories'             => '$1 {{PLURAL:$1|categoria|categorias}}',
'nlinks'                  => '$1 {{PLURAL:$1|colliaziun|colliaziuns}}',
'nmembers'                => '$1 {{PLURAL:$1|commember|commembers}}',
'nrevisions'              => '{{PLURAL:$1|Ina versiun|$1 versiuns}}',
'nviews'                  => 'Contemplà $1 {{PLURAL:$1|giada|giadas}}',
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
'mostcategories'          => "Artitgels ch'èn en las pli biaras chategorias",
'mostimages'              => 'Datotecas utilisadas il pli savens',
'mostrevisions'           => 'Artitgels cun las pli biaras revisiuns',
'prefixindex'             => 'Tut las paginas cun prefix',
'shortpages'              => 'Paginas curtas',
'longpages'               => 'Artitgels lungs',
'deadendpages'            => 'artitgels senza links interns che mainan anavant',
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
'allpages'          => 'tut ils *** artitgels',
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
'allpagesprefix'    => 'mussar paginas cun il prefix:',
'allpages-bad-ns'   => 'Il tip da pagina "$1" n\'existà betg sin {{SITENAME}}.',

# Special:Categories
'categories' => 'Categorias',

# Special:LinkSearch
'linksearch'    => 'Links externs',
'linksearch-ns' => 'Tip da pagina:',
'linksearch-ok' => 'Tschertgar',

# Special:Log/newusers
'newuserlogpage'           => "Log d'utilisaders creads",
'newuserlog-create-entry'  => "Nov conto d'utilisader",
'newuserlog-create2-entry' => 'Creà in nov conto "$1"',

# Special:ListGroupRights
'listgrouprights-members' => '(glista dals commembers)',

# E-mail user
'mailnologintext'  => "Ti stos [[Special:UserLogin|t'annunziar]] ed avair ina adressa dad e-mail valida en tias [[Special:Preferences|preferenzas]] per trametter e-mails ad auters utilisaders.",
'emailuser'        => 'Trametter in e-mail a quest utilisader',
'emailpage'        => "Trametter in e-mail a l'utilisader",
'emailpagetext'    => "Ti pos utilisar il formular sutvart per trametter in'e-mail a quest utilisader.
L'adressa dad e-mail che ti has endatà en [[Special:Preferences|tias preferenzas]] vegn inditgada sco speditur da l'e-mail, uschia ch'il retschavider po rispunder directamain a tai.",
'defemailsubject'  => '{{SITENAME}} e-mail',
'noemailtitle'     => 'Nagina adressa dad e-mail',
'noemailtext'      => "Quest utilisader n'ha betg inditgà ina adressa dad e-mail valida.",
'nowikiemailtitle' => 'Betg lubì da termetter e-mail',
'nowikiemailtext'  => "Quest utilisader ha tschernì ch'el na vul betg reschaiver e-mails dad auters utilisaders.",
'email-legend'     => 'Trametter in e-mail ad in auter utilisader da {{SITENAME}}',
'emailfrom'        => 'Da:',
'emailto'          => 'A:',
'emailsubject'     => 'Object:',
'emailmessage'     => 'Messadi:',
'emailsend'        => 'Trametter',
'emailccme'        => "Ma trametter ina copia da l'e-mail",
'emailccsubject'   => 'Copia da tes messadi a $1: $2',
'emailsent'        => 'Tramess e-mail.',
'emailsenttext'    => 'Tes e-mail è vegnì tramess.',
'emailuserfooter'  => 'Quest e-mail è vegnì tramess da $1 a $2 cun agid da la funcziun da trametter e-mails ad utilisaders sin {{SITENAME}}.',

# Watchlist
'watchlist'            => "mia glista d'observaziun",
'mywatchlist'          => "Mia glista d'observaziun",
'watchnologin'         => "Ti n'es betg t'annunzià!",
'watchnologintext'     => "Ti stos [[Special:UserLogin|t'annunziar]] per midar tia glista d'observaziun.",
'addedwatch'           => 'Agiuntà a la glista dad observaziun',
'addedwatchtext'       => "L'artitgel \"[[:\$1]]\" è vegnì agiuntà a vossa [[Special:Watchlist|glista dad observaziun]].
Midadas futuras vid quai artitgel e la pagina da discussiun appertegnenta vegnan enumeradas là e l'artitgel vegn marcà '''grass''' en la [[Special:RecentChanges|glista da las ultimas midadas]].",
'removedwatch'         => "Allontanà da la glista d'observaziun",
'removedwatchtext'     => 'La pagina "[[:$1]]" è vegnida stizzada da [[Special:Watchlist|tia glista d\'observaziun]].',
'watch'                => 'Observar',
'watchthispage'        => 'Observar questa pagina!',
'unwatch'              => 'betg pli observar',
'watchnochange'        => 'Nagin dals artitgels che ti observeschas è vegnì midà durant la perioda da temp inditgada.',
'watchlist-details'    => "{{PLURAL:$1|Ina pagina|$1 paginas}} èn sin tia glista d'observaziun (senza dumbrar las paginas da discussiun).",
'wlheader-showupdated' => "* artitgels che èn vegnids midà suenter che ti as vis els la davosa giada èn mussads '''grass'''",
'watchmethod-recent'   => "intercurir las davosas midadas per la glista d'observaziun",
'watchmethod-list'     => 'intercurir las paginas observadas davart novas midadas',
'watchlistcontains'    => "Tia glista d'observaziun cuntegna $1 {{PLURAL:$1|pagina|paginas}}.",
'iteminvalidname'      => "Problem cun endataziun '$1', num nunvalid...",
'wlnote'               => "Sutvart {{PLURAL:$1|è l'ultima midada|èn las ultimas '''$1''' midadas}} entaifer {{PLURAL:$2|l'ultima ura|las ultimas '''$2''' uras}}.",
'wlshowlast'           => 'Mussar: las ultimas $1 uras, ils ultims $2 dis u $3.',
'watchlist-options'    => "Opziuns per la glista d'observaziun",

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'observ...',
'unwatching' => 'observ betg pli...',

'changed' => 'midà',
'created' => 'creà',

# Delete
'deletepage'            => 'Stizzar la pagina',
'confirm'               => 'Confermar',
'historywarning'        => "'''Attenziun:''' L'artitgel che ti vuls stizzar ha ina cronologia cun circa {{PLURAL:$1|ina versiun|$1 versiuns}}:",
'confirmdeletetext'     => 'Ti es vidlonder da stizzar permanentamain in artitgel u in maletg e tut las versiuns pli veglias ord la datoteca. <br />
Conferma per plaschair che ti ta es conscient da las consequenzas e che ti ageschas tenor las [[{{MediaWiki:Policy-url}}|directivas da {{SITENAME}}]].',
'actioncomplete'        => "L' acziun è terminada.",
'deletedtext'           => '"<nowiki>$1</nowiki>" è vegnì stizzà.
Sin $2 chattas ti ina glista dals davos artitgels stizzads.',
'deletedarticle'        => '"[[$1]]" è stizzà',
'dellogpage'            => 'log dal stizzar',
'deletecomment'         => 'Motiv:',
'deleteotherreason'     => 'Autra / supplementara raschun:',
'deletereasonotherlist' => 'Autra raschun:',

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
'prot_1movedto2'              => 'Spustà [[$1]] tar [[$2]]',
'protect-legend'              => 'Midar il status da protecziun da la pagina.',
'protectcomment'              => 'Motiv:',
'protectexpiry'               => 'Pretegì enfin:',
'protect_expiry_invalid'      => "Il temp endatà n'è betg valid.",
'protect_expiry_old'          => 'Il temp da proteger giascha en il passà.',
'protect-text'                => "Qua pos ti contemplar ed midar il livel da protecziun per l'artitgel '''<nowiki>$1</nowiki>'''.",
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
'protect-cascade'             => 'Proteger paginas integradas en questa pagina ("protecziun ertaivla")',
'protect-cantedit'            => "Ti na pos betg midar il livel da protecziun da questa pagina, perquai che ti n'has betg ils dretgs per far quai.",
'restriction-type'            => 'Status da protecziun:',
'restriction-level'           => 'Livel da protecziun:',

# Restrictions (nouns)
'restriction-edit' => 'Modifitgar',
'restriction-move' => 'spustar',

# Undelete
'viewdeletedpage'           => 'Mussar las paginas stizzadas',
'undeletebtn'               => 'restituir',
'undeletelink'              => 'mussar/restituir',
'undeletedarticle'          => 'restituì "[[$1]]"',
'undelete-search-submit'    => 'Tschertga',
'undelete-show-file-submit' => 'Gea',

# Namespace form on various pages
'namespace'      => 'Tip da pagina:',
'invert'         => 'invertar la selecziun',
'blanknamespace' => '(principal)',

# Contributions
'contributions'       => "contribuziuns da l'utilisader",
'contributions-title' => "Contribuziuns d'utilisader da $1",
'mycontris'           => 'mias contribuziuns',
'contribsub2'         => 'Per $1 ($2)',
'uctop'               => '(actual)',
'month'               => 'dal mais (e pli baud):',
'year'                => "da l'onn (e pli baud):",

'sp-contributions-newbies'     => 'Be mussar contribuziuns da contos novs',
'sp-contributions-newbies-sub' => "Per novs contos d'utilisader",
'sp-contributions-blocklog'    => 'Log dal bloccar',
'sp-contributions-talk'        => 'Discussiun',
'sp-contributions-search'      => "Tschertgar contribuziuns d'utilisaders",
'sp-contributions-username'    => "Adressa IP u num d'utilisader:",
'sp-contributions-submit'      => 'Tschertga',

# What links here
'whatlinkshere'            => 'Links sin questa pagina',
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
'whatlinkshere-filters'    => 'Filters',

# Block/unblock
'blockip'                  => 'Bloccar utilisader',
'ipaddress'                => 'Adressa IP:',
'ipadressorusername'       => "Adressa IP u num d'utilisader:",
'ipbexpiry'                => 'Temp da bloccaziun:',
'ipboptions'               => '2 uras:2 hours,1 di:1 day,3 dis:3 days,1 emna:1 week,2 emnas:2 weeks,1 mais:1 month,3 mais:3 months,6 mais:6 months,1 onn:1 year,infinit:infinite',
'badipaddress'             => "l'adressa-IP è nunvalida",
'ipblocklist'              => "Glista da las adressas da'IP e dals nums d'utilisader bloccads",
'ipblocklist-submit'       => 'Tschertgar',
'blocklink'                => 'bloccar',
'unblocklink'              => 'de-bloccar',
'change-blocklink'         => 'Midar opziuns da bloccar',
'contribslink'             => 'contribuziuns',
'autoblocker'              => "Vossa adressa dad IP è vegnida bloccada perquai che vus utilisais ina adressa dad IP cun [[User:$1|$1]]. Motiv per bolccar $1: '''$2'''.",
'blocklogpage'             => 'Log dal bloccar',
'blocklogentry'            => 'bloccà [[$1]] per $2. Motiv: $3',
'unblocklogentry'          => "debloccà l'utilisader „$1“",
'block-log-flags-nocreate' => 'Deactivà la creaziun da contos',

# Developer tools
'databasenotlocked' => 'Questa banca da datas è betg bloccada.',

# Move page
'move-page'                 => 'Spustar "$1"',
'move-page-legend'          => 'Spustar la pagina',
'movepagetext'              => "Cun il formular sutvart das ti in nov num ad in artitgel e spostas l'entira istorgia da l'artitgel al nov.
L'artitgel vegl renviescha lura al nov.
Ti pos actualisar automaticamain paginas che renvieschan a l'artitgel original.
Sche ti na vuls betg quai, controllescha p. pl las paginas che renvieschan [[Special:DoubleRedirects|dublamain]] u [[Special:BrokenRedirects|incorrect]].
Ti ès responsabels che tut las colliaziuns mainan al lieu ch'els duessan.

Fa stim, che la pagina '''na vegn betg''' spustada sch'i exista gia in artitgel cun il nov titel, auter sche quel è vids u renviescha ad in'autra pagina e n'ha nagina istorgia.

'''ATTENZIUN!'''
Quai po esser ina midada drastica ed nunspetgada per in artitgel popular;
sajas conscient da las consequenzas che quai process po avair.",
'movepagetalktext'          => "La pagina da discussiun che tutga tar l'artitgel vegn spustada automaticamain cun l'artitgel, '''sche betg''':
*Ina pagina da discussiun betg vida exista gia sut il lemma nov
*Ti prendas ora il crutschin dal champ sutvart

En quests cas as ti da spustar u colliar manualmain las paginas, sche giavischà.",
'movearticle'               => 'Spustar artitgel:',
'movenologin'               => "Ti n'ès betg t'annunzià",
'movenologintext'           => "Ti stos [[Special:UserLogin|t'annunziar]] per spustar in artitgel.",
'newtitle'                  => 'Al titel nov:',
'move-watch'                => 'Observar questa pagina',
'movepagebtn'               => 'Spustar la pagina',
'pagemovedsub'              => 'Spustà cun success',
'movepage-moved'            => '\'\'\'"$1" è vegnì spustà a "$2"\'\'\'',
'movepage-moved-redirect'   => 'In renviament è vegnì creà.',
'movepage-moved-noredirect' => 'I è vegnì impedì da crear in renviament.',
'articleexists'             => 'I exista gia in artitgel cun quai num. Tscherni per plaschair in auter.',
'talkexists'                => "'''L'artitgel è vegnì spustà cun success. Dentant exista sut il nov num gia ina pagina da discussiun, perquai è la pagina da discussiun betg vegnida spustada. Fa quai p. pl. a maun.'''",
'movedto'                   => 'spustà a',
'movetalk'                  => "Spustar la pagina da discussiun che tutga tar l'artitgel",
'1movedto2'                 => 'Spustà [[$1]] a [[$2]]',
'1movedto2_redir'           => 'Spustà [[$1]] a [[$2]] cun in renviament',
'move-redirect-suppressed'  => 'Impedì renviament',
'movelogpage'               => 'Log dal spustar',
'movereason'                => 'Motiv:',
'revertmove'                => 'spustar anavos',
'delete_and_move'           => 'Stizzar e spustar',
'delete_and_move_text'      => '==Stizzar necessari==

L\'artitgel da destinaziun "[[:$1]]" exista gia. Vul ti stizzar el per far plaz per spustar?',
'delete_and_move_confirm'   => 'Gea, stizzar il artitgel da destinaziun per spustar',
'delete_and_move_reason'    => 'Stizzà per far plaz per spustar',
'immobile-source-namespace' => 'Paginas dal tip da pagina "$1" na pon betg vegnir spustadas',
'immobile-target-namespace' => 'Betg pussaivel da spustar paginas en il tip da pagina "$1"',
'imagenocrossnamespace'     => 'Betg pussaivel da spustar ina datoteca ad in tip da pagina betg da datoteca',
'fix-double-redirects'      => 'Schliar renviaments dubels suenter il spustar',
'move-leave-redirect'       => 'Crear renviament',

# Export
'export'           => 'Exportar paginas',
'export-addnstext' => 'Agiuntar paginas ord il tip da pagina:',
'export-templates' => 'Includer models',

# Namespace 8 related
'allmessages'               => 'communicaziuns dal sistem',
'allmessagesname'           => 'num',
'allmessagesdefault'        => 'text original',
'allmessagescurrent'        => 'text actual',
'allmessagestext'           => 'Quai è ina glista da tut las communicaziuns dals differents tips da paginas da MediaWiki che vegnan utilisadas da la software da MediaWiki.
Fai ina visita sin [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisation] e [http://translatewiki.net translatewiki.net] sche ti vuls gidar da translatar la software da MediaWiki.',
'allmessagesnotsupportedDB' => "Questa pagina na po betg vegnir mussada, perquai che '''\$wgUseDatabaseMessages''' è vegnì deactivà.",
'allmessages-language'      => 'Lingua:',

# Thumbnails
'thumbnail-more'           => 'Mussar pli grond',
'thumbnail_error'          => 'Sbagl cun crear il maletg da prevista: $1',
'thumbnail_invalid_params' => 'Parameters nunvalids dal maletg da prevista',
'thumbnail_dest_directory' => "Betg pussaivel da crear l'ordinatur da destinaziun.",
'thumbnail_image-type'     => 'Quest tip da maletg na vegn betg sustegnì',
'thumbnail_gd-library'     => 'Configuraziun betg cumpletta da la biblioteca da GD: Funcziun mancanta $1',
'thumbnail_image-missing'  => 'Datoteca para da mancar: $1',

# Special:Import
'import'                     => 'Impurtar paginas',
'import-interwiki-templates' => 'Includer tut ils models',
'import-interwiki-namespace' => 'Tip da pagina da destinaziun:',

# Import log
'importlogpage' => 'Log dals imports',

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
'tooltip-ca-unprotect'            => 'Annullar la protecziun da questa pagina',
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

# Stylesheets
'common.css'   => '/** CSS placed here will be applied to all skins */',
'monobook.css' => "/* editescha quest file per adattar il skin momobook per l'entira pagina */",

# Scripts
'monobook.js' => '/* Deprecated; use [[MediaWiki:common.js]] */',

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
'spam_reverting'      => "Restituir l'ultima versiun che na cuntegna nagins links a $1",
'spam_blanking'       => 'Nettegià tut las versiuns che han cuntegnì links a $1',

# Info page
'infosubtitle'   => 'Infurmaziuns per la pagina',
'numedits'       => 'Dumber da las versiuns da quest artitgel: $1',
'numtalkedits'   => 'Dumber da las versiuns da la pagina da discussiun: $1',
'numwatchers'    => 'dumber dals observaturs: $1',
'numauthors'     => 'Dumber dals auturs da quest artitgel: $1',
'numtalkauthors' => 'dumber dals participants da la discussiun: $1',

# Math options
'mw_math_png'    => 'Adina mussar sco PNG',
'mw_math_simple' => 'HTML sche fitg simpel, uschiglio PNG',
'mw_math_html'   => 'HTML sche pussibel ed uschigio PNG',
'mw_math_source' => 'Schar en furma da TeX (per browsers da text)',
'mw_math_modern' => 'Recumandà per browsers moderns',
'mw_math_mathml' => 'MathML sche pussibel (experimental)',

# Math errors
'math_failure'          => 'Errur dal parser',
'math_unknown_error'    => 'errur nunenconuschenta',
'math_unknown_function' => 'funcziun nunenconuschenta',
'math_lexing_error'     => 'Errur lexicala',
'math_syntax_error'     => 'Sbagl da la sintaxta',
'math_image_error'      => "La conversiun da PNG n'è betg reussida; 
controllescha l'installaziun correcta da latext, dvips, gs e convertescha lura",
'math_bad_tmpdir'       => "Betg pussaivel da scriver u crear l'ordinatur temporar math",
'math_bad_output'       => "Betg pussaivel da scriver u crear l'ordinatur da destinaziun math",
'math_notexvc'          => "Il program texvc n'è betg vegnì chattà. Legia math/README per al configurar.",

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
'file-info'            => '(grondezza da datoteca: $1, tip da MIME: $2)',
'file-info-size'       => '($1 × $2 pixels, grondezza da datoteca: $3, tip da MIME: $4)',
'file-nohires'         => '<small>Nagina resuluziun pli auta disponibla.</small>',
'svg-long-desc'        => '(datoteca da SVG, grondezza da basa $1 × $2 pixels, grondezza da datoteca: $3)',
'show-big-image'       => 'Resoluziun cumplaina',
'show-big-image-thumb' => '<small>Grondezza da quest prevista: $1 × $2 pixels</small>',
'file-info-gif-looped' => 'marscha infinit',
'file-info-gif-frames' => '$1 {{PLURAL:$1|maletg|maletgs}}',

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
'metadata-fields'   => 'Suandants champs da las EXIF-Metadata en quest text da sistem da MediaWiki vegnan mussads sin las paginas da descripziun dal maletg; uleriurs detagls zuppentads normalmain pon vegnir mussads.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

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
'exif-resolutionunit'              => 'Unitad da mesira da la resoluziun',
'exif-stripoffsets'                => 'Posiziun da las datas da maletg',
'exif-rowsperstrip'                => 'Dumber lingias per strivla',
'exif-stripbytecounts'             => 'Bytes per strivla cumprimida',
'exif-jpeginterchangeformat'       => 'Offset al JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Bytes datas da JPEG',
'exif-transferfunction'            => 'Funcziun da transfer',
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
'exif-pixelydimension'             => 'Ladezza dal maletg valida',
'exif-pixelxdimension'             => 'Autezza dal maletg valida',
'exif-makernote'                   => 'Notiza dal fabricant',
'exif-usercomment'                 => "Commentari da l'utilisader",
'exif-relatedsoundfile'            => "Datoteca d'audio appartegnenta",
'exif-datetimeoriginal'            => 'Data e temp da la generaziun',
'exif-datetimedigitized'           => 'Data e temp da la digitalisaziun',
'exif-subsectime'                  => 'Data en tschientavels secunda',
'exif-subsectimeoriginal'          => 'Data da generaziun en tschientavels secunda',
'exif-subsectimedigitized'         => 'Data da digitalisaziun en tschientavels secunda',
'exif-exposuretime'                => "Temp d'exposiziun",
'exif-exposuretime-format'         => '$1 secundas ($2)',
'exif-exposureprogram'             => "Program d'exposiziun",
'exif-oecf'                        => 'Factur da conversiun optoelectronic',
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
'exif-gpsprocessingmethod'         => "Num da la metoda d'elavuraziun da GPS",
'exif-gpsareainformation'          => 'Num dal territori da GPS',
'exif-gpsdatestamp'                => 'Data da GPS',
'exif-gpsdifferential'             => 'Correctura da differenzial dal GPS',

# EXIF attributes
'exif-compression-1' => 'Betg cumprimà',

'exif-unknowndate' => 'Data nunenconuschenta',

'exif-orientation-1' => 'Normal',
'exif-orientation-2' => 'Reflectà orizontalmain',
'exif-orientation-3' => 'Rotà per 180°',
'exif-orientation-4' => 'Reflectà verticalmain',
'exif-orientation-5' => "Rotà 90° en senn cuntrari a l'ura e reflectà verticalmain",
'exif-orientation-6' => "Rotà 90° en senn da l'ura",
'exif-orientation-7' => "Rotà 90° en senn da l'ura e reflectà verticalmain",
'exif-orientation-8' => "Rotà 90° en senn cuntrari a l'ura",

'exif-componentsconfiguration-0' => "n'exista betg",

'exif-exposureprogram-0' => 'Betg definì',
'exif-exposureprogram-1' => 'Manualmain',
'exif-exposureprogram-2' => 'Program da standard',

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
'exif-flash-redeye-1' => 'Reducziun dad egls cotschens',

'exif-focalplaneresolutionunit-2' => 'Poleschs',

'exif-sensingmethod-1' => 'Betg definì',

'exif-scenecapturetype-0' => 'Standard',
'exif-scenecapturetype-1' => 'Cuntrada',
'exif-scenecapturetype-2' => 'Purtret',
'exif-scenecapturetype-3' => 'Scena da notg',

'exif-gaincontrol-0' => 'Nagina',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Flaivel',
'exif-contrast-2' => 'Ferm',

'exif-saturation-0' => 'Normal',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Flaivel',
'exif-sharpness-2' => 'Ferm',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'km per ura',
'exif-gpsspeed-m' => 'Miglias per ura',
'exif-gpsspeed-n' => 'Nuf',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Direcziun reala',
'exif-gpsdirection-m' => 'Direcziun magnetica',

# External editor support
'edit-externally'      => 'Modifitgar questa datoteca cun in program extern',
'edit-externally-help' => "(Legia [http://www.mediawiki.org/wiki/Manual:External_editors instrucziuns d'installaziun] per ulteriuras infurmaziuns)",

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'tuts',
'imagelistall'     => 'tuts',
'watchlistall2'    => 'tut',
'namespacesall'    => 'tuts',
'monthsall'        => 'tuts',
'limitall'         => 'tuts',

# E-mail address confirmation
'confirmemail'             => "Confermar l'adressa dad e-mail",
'confirmemail_noemail'     => "Ti n'has betg inditgà ina adressa dad e-mail valida en tias [[Special:Preferences|preferenzas]].",
'confirmemail_text'        => "{{SITENAME}} pretenda che ti confermas tia adressa dad e-mail avant che ti pos utilisar funcziuns dad e-mail. 
Clicca sin il buttun sutvart per ta trametter in e-mail per confermar a tia adressa. 
L'e-mail cuntegna in link cun in code;
Chargia la destinaziun dal link en tes navigatur per confermar che tia adressa dad e-mail è valida.",
'confirmemail_pending'     => "In code per confermar è gia vegnì tramess a tai; 
sche ti has creà avant curt temp tes conto duessas ti anc spitgar in per minutas per che l'e-mail possia arrivar avant che ti genereschas in nov code.",
'confirmemail_send'        => 'Ma trametter in code da confermaziun',
'confirmemail_sent'        => "Tramess l'e-mail da confermaziun.",
'confirmemail_oncreate'    => "In code da confermaziun è vegnì tramess a tia adressa dad e-mail. 
Quest code n'è betg necessari per t'annunziar, ma ti al stos endatar avant che la wiki po activar funcziuns dad e-mail.",
'confirmemail_sendfailed'  => "{{SITENAME}} na pudeva betg trametter l'e-mail da confermaziun.
Controllescha sche ti has endatà caracters nunvalids en tia adressa dad e-mail.

Il server dad e-mail ha returnà: $1",
'confirmemail_invalid'     => 'Il code da confermaziun è nunvalid. Il code è probablamain scrudà.',
'confirmemail_needlogin'   => 'Ti stos $1 per confermar tia adressa dad e-mail.',
'confirmemail_success'     => "Tia adressa dad e-mail è vegnida confermada. 
Ussa pos ti [[Special:UserLogin|t'annunziar]] ed utilisar la wiki.",
'confirmemail_loggedin'    => 'Tia adressa dad e-mail è ussa vegnida confermada.',
'confirmemail_error'       => 'Insatge è crappà cun tes mail da confermaziun. Stgisa foll!',
'confirmemail_subject'     => "{{SITENAME}} - Confermaziun da l'adressa dad e-mail",
'confirmemail_body'        => 'Insatgi cun l\'adressa dad IP $1, probablamain ti, 
ha creà il conto "$2" sin {{SITENAME}} cun questa adressa dad e-mail.

Per confermar ch quest conta tutga propi a tai e per activar las 
funcziuns dad e-mail sin {{SITENAME}} stos ti avrir quest link en tes navigatur:

$3

Sche l\'adressa na tutga *betg* tar il conto numnà, suanda per plaschair a 
questa colliaziun per stizzar la confermaziun da questa adressa dad e-mail:

$5

Quest code da confermaziun scroda ils $4.',
'confirmemail_invalidated' => "La confermaziun da l'adressa dad e-mail è vegnida stizzada",
'invalidateemail'          => "Stizzar la confermaziun da l'adressa dad e-mail",

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
'deletedwhileediting' => "'''Attenziun:''' Questa pagina è vegnida stizzada suenter che ti has cumanzà a la modifitgar.",
'confirmrecreate'     => "L'utilisader [[User:$1|$1]] ([[User talk:$1|talk]]) ha stizzà quest artitgel (motiv: ''$2'') suenter che ti as cumenzà a modifitgar l'artitgel.
Conferma per plaschair che ti vuls propi crear danovamain quest artitgel.",
'recreate'            => 'Crear danovamain',

# action=purge
'confirm_purge_button' => 'ok',
'confirm-purge-top'    => 'Stizzar il cache da questa pagina?',
'confirm-purge-bottom' => 'Svida il cache dad ina pagina e sforza da mussar la versiun actuala.',

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
Ti pos era modifitgar tia glista d\'observaziun en il [[Special:Watchlist/raw|format da glistas]].',
'watchlistedit-normal-submit'  => 'Allontanar endataziuns',
'watchlistedit-normal-done'    => "{{PLURAL:$1|1 endataziun è vegnida|$1 endataziuns èn vegnidas}} allontanadas da la glista d'observaziun:",
'watchlistedit-raw-title'      => "Modifitgar la glista d'observaziun en il format da la glista",
'watchlistedit-raw-legend'     => "Modifitgar la glista d'observaziun en il format da la glista",
'watchlistedit-raw-explain'    => 'Quai èn las endataziuns da tia glista d\'observaziun en il format da glista. Ti pos agiuntar ed allontanar endataziuns da la glista. Fa dentant attenziun che ti pos be inditgar ina endataziun per lingia. 
Sche ti has terminà tia lavur, clicca sin "{{int:Watchlistedit-raw-submit}}".
Ti pos era utilisar [[Special:Watchlist/edit|la pagina da standard]].',
'watchlistedit-raw-titles'     => 'Endataziuns:',
'watchlistedit-raw-submit'     => "Actualisar la glista d'observaziun",
'watchlistedit-raw-done'       => "Tia glista d'observaziun è vegnida actualisada.",
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 endataziun è vegnida agiuntada|$1 endataziuns èn vegnidas agiuntadas}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 endataziun è vegnida allontanada|$1 endataziuns èn vegnidas allontanadas}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Mussar las midadas relevantas',
'watchlisttools-edit' => "Mussar e modifitgar la glista d'observaziun",
'watchlisttools-raw'  => 'Modifitgar il format da la glista (import/export)',

# Special:Version
'version'                  => 'Versiun',
'version-extensions'       => 'Extensiuns installadas',
'version-specialpages'     => 'Paginas spezialas',
'version-variables'        => 'Variablas',
'version-other'            => 'Auter',
'version-version'          => '(Versiun $1)',
'version-license'          => 'Licenza',
'version-software'         => 'Software installada',
'version-software-product' => 'Product',
'version-software-version' => 'Versiun',

# Special:FilePath
'filepath'        => 'Percurs da la datoteca',
'filepath-page'   => 'Datoteca:',
'filepath-submit' => 'Dai',

# Special:FileDuplicateSearch
'fileduplicatesearch-legend'   => 'Tschertgar duplicats',
'fileduplicatesearch-filename' => 'Num da datoteca:',
'fileduplicatesearch-submit'   => 'Tschertgar',
'fileduplicatesearch-info'     => '$1 x $2 pixels<br />Grondezza da datoteca: $3<br />Tip da MIME: $4',

# Special:SpecialPages
'specialpages'                   => 'Paginas spezialas',
'specialpages-note'              => '----
* Paginas spezialas normalas.
* <strong class="mw-specialpagerestricted">Paginas spezialas restrenschidas.</strong>',
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

# Database error messages
'dberr-problems' => 'Stgisa!
Questa pagina ha actualmain difficultads tecnicas.',

# HTML forms
'htmlform-invalid-input'       => 'I ha dà problems cun intginas da tias endataziuns',
'htmlform-select-badoption'    => "La valur inditgada n'è betg ina opziun valida.",
'htmlform-int-invalid'         => "La valur inditgada n'è betg ina cifra entira (integer).",
'htmlform-float-invalid'       => "La valur che ti has inditgà n'è betg ina cifra.",
'htmlform-int-toolow'          => 'La valur che ti has inditgà è sut il minimum da $1',
'htmlform-int-toohigh'         => 'La valur che ti has inditgà è sur il maximum da $1',
'htmlform-submit'              => 'Trametter',
'htmlform-reset'               => 'Revocar las midadas',
'htmlform-selectorother-other' => 'Auters',

);

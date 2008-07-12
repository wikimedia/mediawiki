<?php
/** Interlingua (Interlingua)
 *
 * @ingroup Language
 * @file
 *
 * @author McDutchie
 * @author Malafaya
 * @author לערי ריינהארט
 * @author Siebrand
 */

$skinNames = array(
	'cologneblue' => 'Blau Colonia',
);

$namespaceNames = array(
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => 'Special',
	NS_MAIN           => '',
	NS_TALK           => 'Discussion',
	NS_USER           => 'Usator',
	NS_USER_TALK      => 'Discussion_Usator',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => 'Discussion_$1',
	NS_IMAGE          => 'Imagine',
	NS_IMAGE_TALK     => 'Discussion_Imagine',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'Discussion_MediaWiki',
	NS_TEMPLATE       => 'Patrono',
	NS_TEMPLATE_TALK  => 'Discussion_Patrono',
	NS_HELP           => 'Adjuta',
	NS_HELP_TALK      => 'Discussion_Adjuta',
	NS_CATEGORY       => 'Categoria',
	NS_CATEGORY_TALK  => 'Discussion_Categoria'
);
$linkTrail = "/^([a-z]+)(.*)\$/sD";

$messages = array(
# User preference toggles
'tog-underline'               => 'Sublinear ligamines',
'tog-highlightbroken'         => 'Formatar ligamines rupte <a href="" class="new">assi</a> (alternativemente: assi<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Justificar paragraphos',
'tog-hideminor'               => 'Occultar modificationes recente minor',
'tog-extendwatchlist'         => 'Expander le observatorio a tote le modificationes applicabile',
'tog-usenewrc'                => 'Modificationes recente meliorate (non functiona in tote le navigatores)',
'tog-numberheadings'          => 'Numerar titulos automaticamente',
'tog-showtoolbar'             => 'Show edit toolbar',
'tog-editondblclick'          => 'Duple clic pro modificar un pagina (usa JavaScript)',
'tog-editsection'             => 'Activar le modification de sectiones con ligamines [modificar]',
'tog-editsectiononrightclick' => 'Activar modification de sectiones con clic dextre super lor titulos (JavaScript)',
'tog-showtoc'                 => 'Monstrar tabula de contento (in paginas con plus de 3 sectiones)',
'tog-rememberpassword'        => 'Recordar contrasigno inter sessiones (usa cookies)',
'tog-editwidth'               => 'Cassa de redaction occupa tote le largor del fenestra',
'tog-watchcreations'          => 'Adder le paginas que io crea a mi observatorio',
'tog-watchdefault'            => 'Poner articulos nove e modificate sub observation',
'tog-watchmoves'              => 'Adder le paginas que io renomina a mi observatorio',
'tog-watchdeletion'           => 'Adder le paginas que io dele a mi observatorio',
'tog-minordefault'            => 'Marcar modificationes initialmente como minor',
'tog-previewontop'            => 'Monstrar previsualisation ante le cassa de edition e non post illo',
'tog-previewonfirst'          => 'Monstrar previsualisation al prime modification',
'tog-nocache'                 => "Disactivar le ''cache'' de paginas",
'tog-enotifwatchlistpages'    => 'Notificar me via e-mail quando se cambia un pagina in mi observatorio',
'tog-enotifusertalkpages'     => 'Notificar me via e-mail quando se cambia mi pagina de discussion',
'tog-enotifminoredits'        => 'Notificar me etiam de modificationes minor',
'tog-enotifrevealaddr'        => 'Revelar mi adresse de e-mail in messages de notification',
'tog-shownumberswatching'     => 'Monstrar le numero de usatores que observa le pagina',
'tog-fancysig'                => 'Signaturas crude (sin ligamine automatic)',
'tog-externaleditor'          => 'Usar editor externe qua standard (pro expertos solmente, necessita configuration special in tu computator)',
'tog-externaldiff'            => "Usar un programma ''diff'' externe qua standard (pro expertos solmente, necessita configuration special in tu computator)",
'tog-showjumplinks'           => 'Activar ligamines de accessibilitate "saltar a"',
'tog-uselivepreview'          => 'Usar previsualisation directe (JavaScript) (Experimental)',
'tog-forceeditsummary'        => 'Prevenir me quando io entra un summario de modification vacue',
'tog-watchlisthideown'        => 'Excluder mi proprie modificationes del observatorio',
'tog-watchlisthidebots'       => 'Excluder le modificationes per bots del observatorio',
'tog-watchlisthideminor'      => 'Excluder le modificationes minor del observatorio',
'tog-ccmeonemails'            => 'Inviar me copias del messages de e-mail que io invia a altere usatores',
'tog-diffonly'                => "Non monstrar le contento del pagina sub le comparationes ''diff''",
'tog-showhiddencats'          => 'Monstrar categorias celate',

'underline-always'  => 'Sempre',
'underline-never'   => 'Nunquam',
'underline-default' => 'Secundo le configuration del navigator',

'skinpreview' => '(Previsualisation)',

# Dates
'sunday'        => 'dominica',
'monday'        => 'lunedi',
'tuesday'       => 'martedi',
'wednesday'     => 'mercuridi',
'thursday'      => 'jovedi',
'friday'        => 'venerdi',
'saturday'      => 'sabbato',
'sun'           => 'dom',
'mon'           => 'lun',
'tue'           => 'mar',
'wed'           => 'mer',
'thu'           => 'jov',
'fri'           => 'ven',
'sat'           => 'sab',
'january'       => 'januario',
'february'      => 'februario',
'march'         => 'martio',
'april'         => 'april',
'may_long'      => 'maio',
'june'          => 'junio',
'july'          => 'julio',
'august'        => 'augusto',
'september'     => 'septembre',
'october'       => 'octobre',
'november'      => 'novembre',
'december'      => 'decembre',
'january-gen'   => 'januario',
'february-gen'  => 'februario',
'march-gen'     => 'martio',
'april-gen'     => 'april',
'may-gen'       => 'maio',
'june-gen'      => 'junio',
'july-gen'      => 'julio',
'august-gen'    => 'augusto',
'september-gen' => 'septembre',
'october-gen'   => 'octobre',
'november-gen'  => 'novembre',
'december-gen'  => 'decembre',
'jan'           => 'jan',
'feb'           => 'feb',
'mar'           => 'mar',
'apr'           => 'apr',
'may'           => 'mai',
'jun'           => 'jun',
'jul'           => 'jul',
'aug'           => 'aug',
'sep'           => 'sep',
'oct'           => 'oct',
'nov'           => 'nov',
'dec'           => 'dec',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Categoria|Categorias}}',
'category_header'                => 'Articulos in le categoria "$1"',
'subcategories'                  => 'Subcategorias',
'category-media-header'          => 'Media in categoria "$1"',
'category-empty'                 => "''Iste categoria non contine alcun paginas o media al momento.''",
'hidden-categories'              => '{{PLURAL:$1|Categoria celate|Categorias celate}}',
'hidden-category-category'       => 'Categorias celate', # Name of the category where hidden categories will be listed
'category-subcat-count'          => '{{PLURAL:$2|Iste categoria ha solmente le sequente subcategoria.|Iste categoria ha le sequente {{PLURAL:$1|subcategoria|$1 subcategorias}}, ex $2 in total.}}',
'category-subcat-count-limited'  => 'Iste categoria ha le sequente {{PLURAL:$1|subcategoria|$1 subcategorias}}.',
'category-article-count'         => '{{PLURAL:$2|Iste categoria contine solmente le sequente pagina.|Le sequente {{PLURAL:$1|pagina es|$1 paginas es}} in iste categora, ex $2 in total.}}',
'category-article-count-limited' => 'Le sequente {{PLURAL:$1|pagina es|$1 paginas es}} in le categoria actual.',
'category-file-count'            => '{{PLURAL:$2|Iste categoria contine solmente le sequente file.|Le sequente {{PLURAL:$1|file es|$1 files es}} in iste categoria, ex $2 in total.}}',
'category-file-count-limited'    => 'Le sequente {{PLURAL:$1|file es|$1 files es}} in le categoria actual.',
'listingcontinuesabbrev'         => 'cont.',

'mainpagetext'      => "<big>'''MediaWiki ha essite installate con successo.'''</big>",
'mainpagedocfooter' => 'Consulta le [http://meta.wikimedia.org/wiki/Help:Contents Guida del usator] pro informationes super le uso del software wiki.

== Pro initiar ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Lista de configurationes]
* [http://www.mediawiki.org/wiki/Manual:FAQ FAQ a proposito de MediaWiki]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Lista de diffusion pro annuncios de nove versiones de MediaWiki]',

'about'          => 'A proposito',
'article'        => 'Pagina de contento',
'newwindow'      => '(se aperi in un nove fenestra)',
'cancel'         => 'Cancellar',
'qbfind'         => 'Trovar',
'qbbrowse'       => 'Foliar',
'qbedit'         => 'Modificar',
'qbpageoptions'  => 'Optiones de pagina',
'qbpageinfo'     => 'Info del pagina',
'qbmyoptions'    => 'Mi optiones',
'qbspecialpages' => 'Paginas special',
'moredotdotdot'  => 'Plus...',
'mypage'         => 'Mi pagina',
'mytalk'         => 'Mi discussion',
'anontalk'       => 'Discussion pro iste adresse IP',
'navigation'     => 'Navigation',
'and'            => 'e',

# Metadata in edit box
'metadata_help' => 'Metadatos:',

'errorpagetitle'    => 'Error',
'returnto'          => 'Retornar a $1.',
'tagline'           => 'De {{SITENAME}}',
'help'              => 'Adjuta',
'search'            => 'Recercar',
'searchbutton'      => 'Recercar',
'go'                => 'Ir',
'searcharticle'     => 'Ir',
'history'           => 'Chronologia',
'history_short'     => 'Historia',
'updatedmarker'     => 'actualisate post mi ultime visita',
'info_short'        => 'Information',
'printableversion'  => 'Version imprimibile',
'permalink'         => 'Ligamine permanente',
'print'             => 'Imprimer',
'edit'              => 'Modificar',
'create'            => 'Crear',
'editthispage'      => 'Modificar iste pagina',
'create-this-page'  => 'Crear iste pagina',
'delete'            => 'Deler',
'deletethispage'    => 'Eliminar iste pagina',
'undelete_short'    => 'Recuperar {{PLURAL:$1|un modification|$1 modificationes}}',
'protect'           => 'Proteger',
'protect_change'    => 'cambiar protection',
'protectthispage'   => 'Proteger iste pagina',
'unprotect'         => 'Disproteger',
'unprotectthispage' => 'Disproteger iste pagina',
'newpage'           => 'Nove pagina',
'talkpage'          => 'Discuter iste pagina',
'talkpagelinktext'  => 'Discussion',
'specialpage'       => 'Pagina Special',
'personaltools'     => 'Instrumentos personal',
'postcomment'       => 'Publicar un commento',
'articlepage'       => 'Vider article',
'talk'              => 'Discussion',
'views'             => 'Visualisationes',
'toolbox'           => 'Instrumentario',
'userpage'          => 'Vider pagina del usator',
'projectpage'       => 'Vider metapagina',
'imagepage'         => 'Vider pagina de imagine',
'mediawikipage'     => 'Vider pagina de message',
'templatepage'      => 'Vider pagina de patrono',
'viewhelppage'      => 'Vider pagina de adjuta',
'categorypage'      => 'Vider pagina de categoria',
'viewtalkpage'      => 'Vider discussion',
'otherlanguages'    => 'Altere linguas',
'redirectedfrom'    => '(Redirigite de $1)',
'redirectpagesub'   => 'Rediriger pagina',
'lastmodifiedat'    => 'Ultime modification: $2, $1.', # $1 date, $2 time
'viewcount'         => 'Iste pagina esseva accessate {{PLURAL:$1|un vice|$1 vices}}.',
'protectedpage'     => 'Pagina protegite',
'jumpto'            => 'Salta a:',
'jumptonavigation'  => 'navigation',
'jumptosearch'      => 'cerca',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'A proposito de {{SITENAME}}',
'aboutpage'            => 'Project:A_proposito',
'bugreports'           => 'Reportos de disfunctiones',
'bugreportspage'       => 'Project:Reportos_de_disfunctiones',
'copyright'            => 'Le contento es disponibile sub $1.',
'copyrightpagename'    => '{{SITENAME}} e derectos de autor (copyright)',
'copyrightpage'        => '{{ns:project}}:Copyright',
'currentevents'        => 'Actualitates',
'currentevents-url'    => 'Project:Actualitates',
'disclaimers'          => 'Declarationes de non-responsabilitate',
'disclaimerpage'       => 'Project:Declaration general de exemption de responsabilitates',
'edithelp'             => 'Adjuta al edition',
'edithelppage'         => 'Help:Como_editar_un_pagina',
'faq'                  => 'Questiones frequente',
'faqpage'              => 'Project:Questiones_frequente',
'helppage'             => 'Help:Adjuta',
'mainpage'             => 'Frontispicio',
'mainpage-description' => 'Frontispicio',
'policy-url'           => 'Project:Politica',
'portal'               => 'Portal del communitate',
'portal-url'           => 'Project:Portal del communitate',
'privacy'              => 'Politica de confidentialitate',
'privacypage'          => 'Project:Politica de confidentialitate',

'badaccess'        => 'Error de permission',
'badaccess-group0' => 'Tu non ha le permission de executar le action que tu ha requestate.',
'badaccess-group1' => 'Le action que tu ha requestate es limitate al usatores in le gruppo $1.',
'badaccess-group2' => 'Le action que tu ha requestate es limitate al usatores in un del gruppos $1.',
'badaccess-groups' => 'Le action que tu ha requestate es limitate al usatores in un del gruppos $1.',

'versionrequired'     => 'Version $1 de MediaWiki requirite',
'versionrequiredtext' => 'Le version $1 de MediaWiki es requirite pro usar iste pagina. Vide [[Special:Version|le pagina de version]].',

'ok'                      => 'OK',
'retrievedfrom'           => 'Recuperate de "$1"',
'youhavenewmessages'      => 'Tu ha $1 ($2).',
'newmessageslink'         => 'messages nove',
'newmessagesdifflink'     => 'ultime modification',
'youhavenewmessagesmulti' => 'Tu ha nove messages super $1',
'editsection'             => 'modificar',
'editold'                 => 'modificar',
'viewsourceold'           => 'vider codice-fonte',
'editsectionhint'         => 'Modifica section: $1',
'toc'                     => 'Contento',
'showtoc'                 => 'revelar',
'hidetoc'                 => 'celar',
'thisisdeleted'           => 'Vider o restaurar $1?',
'viewdeleted'             => 'Vider $1?',
'restorelink'             => '{{PLURAL:$1|un modification|$1 modificationes}} delite',
'feedlinks'               => 'Syndication:',
'feed-invalid'            => 'Typo de syndication invalide.',
'feed-unavailable'        => 'Le syndicationes non es disponibile in {{SITENAME}}',
'site-rss-feed'           => 'Syndication RSS de $1',
'site-atom-feed'          => 'Syndication Atom de $1',
'page-rss-feed'           => 'Syndication RSS de "$1"',
'page-atom-feed'          => 'Syndication Atom de "$1"',
'red-link-title'          => '$1 (non scribite ancora)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Pagina',
'nstab-user'      => 'Pagina de usator',
'nstab-media'     => 'Pagina de multimedia',
'nstab-special'   => 'Special',
'nstab-project'   => 'Pagina de projecto',
'nstab-image'     => 'File',
'nstab-mediawiki' => 'Message',
'nstab-template'  => 'Patrono',
'nstab-help'      => 'Pagina de adjuta',
'nstab-category'  => 'Categoria',

# Main script and global functions
'nosuchaction'      => 'Action inexistente',
'nosuchactiontext'  => 'Le action specificate in le URL non es
recognoscite per le systema de Mediawiki.',
'nosuchspecialpage' => 'Pagina special inexistente',
'nospecialpagetext' => '
Tu demandava un pagina special que non es
recognoscite per le systema de Mediawiki.',

# General errors
'error'                => 'Error',
'databaseerror'        => 'Error de base de datos',
'dberrortext'          => 'Occurreva un error de syntaxe in le consulta al base de datos.
Le ultime demanda inviate al base de datos esseva:
<blockquote><tt>$1</tt></blockquote>
de intra le function "<tt>$2</tt>".
MySQL retornava le error "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Un error de syntaxe occurreva durante un consulta del base de datos.
Le ultime consulta que se tentava es:
"$1"
effectuate per le function "$2".
MySQL retornava le error "$3: $4"',
'noconnect'            => 'Impossibile connecter al base de datos a $1',
'nodb'                 => 'Impossibile selectionar base de datos $1',
'cachederror'          => 'Le sequente copia del pagina se recuperava del cache, e possibilemente non es actual.',
'laggedslavemode'      => 'Attention: Es possibile que le pagina non contine actualisationes recente.',
'readonly'             => 'Base de datos blocate',
'enterlockreason'      => 'Describe le motivo del blocada, includente un estimation
de quando illo essera terminate',
'readonlytext'         => 'Actualmente le base de datos de {{SITENAME}} es blocate pro nove
entratas e altere modificationes, probabilemente pro mantenentia
routinari del base de datos, post le qual illo retornara al normal.
Le administrator responsabile dava iste explication:
<p>$1',
'missing-article'      => 'Le base de datos non ha trovate le texto de un pagina que illo deberea haber trovate, nominate "$1" $2.

Causas normal de iste problema es: tu ha consultate un \'\'diff\'\' obsolete, o tu sequeva un ligamine de historia verso un pagina que ha essite delite.

Si isto non es le caso, es possibile que tu ha trovate un error in le software.
Per favor reporta isto a un administrator, faciente nota del adresse URL.',
'missingarticle-rev'   => '(nº del revision: $1)',
'missingarticle-diff'  => '(Diff: $1, $2)',
'readonly_lag'         => 'Le base de datos ha essite automaticamente blocate durante que le servitores de base de datos secundari se synchronisa con le servitor principal.',
'internalerror'        => 'Error interne',
'internalerror_info'   => 'Error interne: $1',
'filecopyerror'        => 'Impossibile copiar file "$1" a "$2".',
'filerenameerror'      => 'Impossibile renominar file "$1" a "$2".',
'filedeleteerror'      => 'Impossibile eliminar file "$1".',
'directorycreateerror' => 'Impossibile crear le directorio "$1".',
'filenotfound'         => 'Impossibile trovar file "$1".',
'fileexistserror'      => 'Impossibile scriber in le file "$1": le file ja existe',
'unexpected'           => 'Valor impreviste: "$1"="$2".',
'formerror'            => 'Error: impossibile submitter formulario',
'badarticleerror'      => 'Iste action non pote esser effectuate super iste pagina.',
'cannotdelete'         => 'Impossibile eliminar le pagina o imagine specificate. (Illo pote ja haber essite eliminate per un altere persona.)',
'badtitle'             => 'Titulo incorrecte',
'badtitletext'         => 'Le titulo de pagina demandate esseva invalide, vacue, o
un titulo interlinguistic o interwiki incorrectemente ligate.',
'perfdisabled'         => 'Pardono! Iste functionalitate es temporarimente inactivate durante
horas de grande affluentia de accessos pro motivo de performance;
retorna inter 02:00 e 14:00 UTC e tenta de nove.',
'perfcached'           => "Le sequente datos se recuperava del ''cache'' e possibilemente non es actual.",
'perfcachedts'         => 'Le sequente datos se recuperava del cache. Ultime actualisation: le $1.',
'querypage-no-updates' => 'Le actualisationes pro iste pagina es disactivate. Pro le momento, le datos hic non se cambiara.',
'wrong_wfQuery_params' => 'Parametros incorrecte a wfQuery()<br />
Function: $1<br />
Consulta: $2',
'viewsource'           => 'Vider codice fonte',
'viewsourcefor'        => 'de $1',
'actionthrottled'      => 'Action limitate',
'actionthrottledtext'  => 'Como mesura anti-spam, vos es limitate de executar iste action troppo de vices durante un curte periodo de tempore, e vos ha excedite iste limite.
Per favor reprova lo post alcun minutas.',
'protectedpagetext'    => 'Iste pagina ha essite protegite contra modificationes.',
'viewsourcetext'       => 'Tu pote vider e copiar le codice-fonte de iste pagina:',
'protectedinterface'   => 'Iste pagina contine texto pro le interfacie del software, e es protegite pro impedir le abuso.',
'editinginterface'     => "'''Attention:''' Tu va modificar un pagina que se usa pro texto del interfacie pro le software.
Omne modification a iste pagina cambiara le apparentia del interfacie pro altere usatores.
Pro traductiones, per favor considera usar [http://translatewiki.net/wiki/Main_Page?setlang=ia Betawiki], le projecto pro localisar MediaWiki.",
'sqlhidden'            => '(Consulta SQL celate)',
'cascadeprotected'     => 'Iste pagina ha essite protegite contra modificationes, proque illo es includite in le sequente {{PLURAL:$1|pagina, le qual|paginas, le quales}} es protegite usante le option "cascada":
$2',
'namespaceprotected'   => "Tu non ha le permission de modificar paginas in le spatio de nomines '''$1'''.",
'customcssjsprotected' => 'Tu non ha le permission de modificar iste pagina, proque illo contine le configurationes personal de un altere usator.',
'ns-specialprotected'  => 'Le paginas special non es modificabile.',
'titleprotected'       => "Iste titulo ha essite protegite contra creation per [[User:$1|$1]].
Le ration date es ''$2''.",

# Virus scanner
'virus-badscanner'     => 'Configuration incorrecte: programma antivirus non cognoscite: <i>$1</i>',
'virus-scanfailed'     => 'scansion fallite (codice $1)',
'virus-unknownscanner' => 'antivirus non cognoscite:',

# Login and logout pages
'logouttitle'                => 'Fin de session',
'logouttext'                 => 'Tu claudeva tu session.
Tu pote continuar a usar {{SITENAME}} anonymemente, o initiar un
nove session como le mesme o como un altere usator.',
'welcomecreation'            => '<h2>Benvenite, $1!</h2>
<p>Tu conto de usator esseva create.
Non oblida personalisar {{SITENAME}} secundo tu preferentias.',
'loginpagetitle'             => 'Aperir session',
'yourname'                   => 'Nomine de usator:',
'yourpassword'               => 'Contrasigno:',
'yourpasswordagain'          => 'Confirmar contrasigno',
'remembermypassword'         => 'Recordar contrasigno inter sessiones.',
'yourdomainname'             => 'Tu dominio:',
'externaldberror'            => 'O il occureva un error in le base de datos de authentification externe, o tu non ha le autorisation de actualisar tu conto externe.',
'loginproblem'               => '<b>Occurreva problemas pro initiar tu session.</b><br />Tenta de nove!',
'login'                      => 'Aperir un session',
'nav-login-createaccount'    => 'Aperir session',
'loginprompt'                => 'Tu debe haber activate le cookies pro poter identificar te a {{SITENAME}}.',
'userlogin'                  => 'Aperir session',
'logout'                     => 'Claude session',
'userlogout'                 => 'Clauder session',
'notloggedin'                => 'Tu non ha aperite un session',
'nologin'                    => 'Tu non ha un conto? $1.',
'nologinlink'                => 'Crear un conto',
'createaccount'              => 'Crear nove conto',
'gotaccount'                 => 'Tu jam ha un conto? $1.',
'gotaccountlink'             => 'Aperir un session',
'createaccountmail'          => 'per e-mail',
'badretype'                  => 'Le duo contrasignos que tu scribeva non coincide.',
'userexists'                 => 'Le nomine de usator que tu selectionava ja es in uso. Per favor selectiona un nomine differente.',
'youremail'                  => 'E-mail:',
'username'                   => 'Nomine de usator:',
'uid'                        => 'ID del usator:',
'prefs-memberingroups'       => 'Membro de {{PLURAL:$1|gruppo|gruppos}}:',
'yourrealname'               => 'Nomine real:',
'yourlanguage'               => 'Lingua:',
'yournick'                   => 'Tu pseudonymo (pro signaturas)',
'badsig'                     => 'Signatura crude invalide; verificar le etiquettas HTML.',
'badsiglength'               => 'Le signatura es troppo longe.
Illo debe haber minus de $1 {{PLURAL:$1|character|characteres}}.',
'email'                      => 'E-mail',
'prefs-help-realname'        => 'Le nomine real es optional.
Si tu opta pro dar lo, isto essera usate pro dar te attribution pro tu contributiones.',
'loginerror'                 => 'Error in le apertura del session',
'prefs-help-email'           => 'Le adresse de e-mail es optional, sed illo permitte que altere personas te contacta via tu pagina de usator o de discussion, sin necessitate de revelar tu identitate.',
'prefs-help-email-required'  => 'Le adresse de e-mail es requirite.',
'nocookiesnew'               => "Le conto de usator ha essite create, sed tu non ha aperite un session.
{{SITENAME}} usa ''cookies'' pro mantener le sessiones del usatores.
Tu ha disactivate le functionalitate del ''cookies''.
Per favor activa lo, alora aperi un session con tu nove nomine de usator e contrasigno.",
'nocookieslogin'             => "{{SITENAME}} usa ''cookies'' pro mantener le sessiones del usatores.
Tu ha disactivate le functionalitate del ''cookies''.
Per favor activa lo e reprova.",
'noname'                     => 'Tu non specificava un nomine de usator valide.',
'loginsuccesstitle'          => 'Session aperte con successo',
'loginsuccess'               => 'Tu es identificate in {{SITENAME}} como "$1".',
'nosuchuser'                 => 'Non existe usator registrate con le nomine "$1".
Verifica le orthographia, o usa le formulario infra pro crear un nove conto de usator.',
'nosuchusershort'            => 'Non existe un usator con le nomine "<nowiki>$1</nowiki>".
Controla tu orthographia.',
'nouserspecified'            => 'Tu debe specificar un nomine de usator.',
'wrongpassword'              => 'Le contrasigno que tu entrava es incorrecte. Per favor essaya lo de novo.',
'wrongpasswordempty'         => 'Tu non entrava un contrasigno. Per favor essaya lo de novo.',
'passwordtooshort'           => 'Tu contrasigno es invalide o troppo curte.
Illo debe haber al minus {{PLURAL:$1|1 character|$1 characteres}} e debe differer de tu nomine de usator.',
'mailmypassword'             => 'Demandar un nove contrasigno via e-mail',
'passwordremindertitle'      => 'Nove contrasigno in {{SITENAME}}',
'passwordremindertext'       => 'Alcuno (probabilemente tu, con le adresse IP $1)
demandava inviar te un nove contrasigno pro {{SITENAME}} ($4).
Le contrasigno pro le usator "$2" ora es "$3".
Nos consilia que tu initia un session e cambia le contrasigno le plus tosto possibile.

Si un altere persona ha facite iste requesta, o si tu te ha rememorate tu contrasigno e tu non vole cambiar lo plus, tu pote ignorar iste message e continuar a usar tu presente contrasigno.',
'noemail'                    => 'Non existe adresse de e-mail registrate pro le usator "$1".',
'passwordsent'               => 'Un nove contrasigno esseva inviate al adresse de e-mail
registrate pro "$1".
Per favor initia un session post reciper lo.',
'blocked-mailpassword'       => 'Tu adresse IP es blocate de facer modificationes, e pro impedir le abuso, le uso del function pro recuperar contrasignos es equalmente blocate.',
'eauthentsent'               => 'Un e-mail de confirmation ha essite inviate al adresse de e-mail nominate.
Ante que alcun altere e-mail se invia al conto, tu debera sequer le instructiones in le e-mail, pro confirmar que le conto es de facto tue.',
'throttled-mailpassword'     => 'Un memento del contrasigno jam esseva inviate durante le ultime {{PLURAL:$1|hora|$1 horas}}.
Pro impedir le abuso, nos invia solmente un memento de contrasigno per {{PLURAL:$1|hora|$1 horas}}.',
'mailerror'                  => 'Error de inviar e-mail: $1',
'acct_creation_throttle_hit' => 'Excusa, tu jam ha create $1 contos.
Tu non pote facer plus.',
'emailauthenticated'         => 'Tu adresse de e-mail se authentificava le $1.',
'emailnotauthenticated'      => 'Tu adresse de e-mail non ha essite authentificate ancora.
Nos non inviara e-mail pro alcun del sequente functiones.',
'noemailprefs'               => 'Specifica un adresse de e-mail pro poter executar iste functiones.',
'emailconfirmlink'           => 'Confirma tu adresse de e-mail',
'invalidemailaddress'        => 'Le adresse de e-mail ha un formato invalide e non pote esser acceptate.
Per favor entra un adresse ben formatate, o vacua ille campo.',
'accountcreated'             => 'Conto create',
'accountcreatedtext'         => 'Le conto del usator $1 ha essite create.',
'createaccount-title'        => 'Creation de contos pro {{SITENAME}}',
'createaccount-text'         => 'Un persona ha create un conto in tu adresse de e-mail a {{SITENAME}} ($4) denominate "$2", con le contrasigno "$3".
Tu deberea aperir un session e cambiar tu contrasigno ora.

Tu pote ignorar iste message si iste conto se creava in error.',
'loginlanguagelabel'         => 'Lingua: $1',

# Password reset dialog
'resetpass'               => 'Redefinir contrasigno del conto',
'resetpass_announce'      => 'Tu ha aperite un session con un codice temporari que tu recipeva in e-mail.
Pro completar le session, tu debe definir un nove contrasigno hic:',
'resetpass_header'        => 'Redefinir contrasigno',
'resetpass_submit'        => 'Definir contrasigno e aperir un session',
'resetpass_success'       => 'Tu contrasigno ha essite cambiate! Ora se aperi tu session...',
'resetpass_bad_temporary' => 'Contrasigno temporari invalide.
Es possibile que tu jam ha cambiate tu contrasigno o ha requestate un nove contrasigno temporari.',
'resetpass_forbidden'     => 'Le contrasignos in {{SITENAME}} non pote esser cambiate.',
'resetpass_missing'       => 'Le formulario non contineva alcun datos.',

# Edit page toolbar
'bold_sample'     => 'Texto grasse',
'bold_tip'        => 'Texto grasse',
'italic_sample'   => 'Texto italic',
'italic_tip'      => 'Texto italic',
'link_sample'     => 'Titulo del ligamine',
'link_tip'        => 'Ligamine interne',
'extlink_sample'  => 'http://www.example.org titulo del ligamine',
'extlink_tip'     => 'Ligamine externe (non oblida le prefixo http://)',
'headline_sample' => 'Texto del titulo',
'headline_tip'    => 'Titulo de nivello 2',
'math_sample'     => 'Inserer formula hic',
'math_tip'        => 'Formula mathematic (LaTeX)',
'nowiki_sample'   => 'Inserer texto non formatate hic',
'nowiki_tip'      => 'Ignorar formatation wiki',
'image_tip'       => 'File incastrate',
'media_tip'       => 'Ligamine a un file',
'sig_tip'         => 'Vostre signatura con data e hora',
'hr_tip'          => 'Linea horizontal (usa con moderation)',

# Edit pages
'summary'                          => 'Summario',
'subject'                          => 'Subjecto/titulo',
'minoredit'                        => 'Isto es un modification minor',
'watchthis'                        => 'Observar iste pagina',
'savearticle'                      => 'Publicar articulo',
'preview'                          => 'Previsualisar',
'showpreview'                      => 'Monstrar previsualisation',
'showlivepreview'                  => 'Previsualisation directe',
'showdiff'                         => 'Monstrar cambios',
'anoneditwarning'                  => "'''Attention:''' Tu non te ha identificate.
Tu adresse IP essera registrate in le historia de modificationes a iste pagina.",
'missingsummary'                   => "'''Memento:''' Tu non entrava alcun summario del modification.
Si tu clicca super Publicar de novo, le modification essera publicate sin summario.",
'missingcommenttext'               => 'Per favor entra un commento infra.',
'missingcommentheader'             => "'''Memento:''' Tu non entrava un subjecto/titulo pro iste commento.
Si tu clicca super Publicar de novo, tu commento essera publicate sin subjecto/titulo.",
'summary-preview'                  => 'Previsualisation del summario',
'subject-preview'                  => 'Previsualisation del subjecto/titulo',
'blockedtitle'                     => 'Le usator es blocate',
'blockedtext'                      => "<big>'''Tu nomine de usator o adresse IP ha essite blocate.'''</big>

Le blocada esseva facite per $1. Le motivo presentate es ''$2''.

* Initio del blocada: $8
* Expiration del blocada: $6
* Le blocato intendite: $7

Tu pote contactar $1 o un del altere [[{{MediaWiki:Grouppage-sysop}}|administratores]] pro discuter le blocada.
Tu non pote usar le function 'inviar e-mail a iste usator' salvo que un adresse de e-mail valide es specificate in le
[[Special:Preferences|preferentias de tu conto]] e que tu non ha essite blocate de usar lo.
Tu adresse IP actual es $3, e le ID del blocada es #$5. Per favor include un de iste informationes o ambes in omne questiones.",
'autoblockedtext'                  => 'Tu adresse de IP ha essite automaticamente blocate proque un altere usator lo usava qui esseva blocate per $1.
Le ration date es:

:\'\'$2\'\'

* Initio del blocada: $8
* Expiration del blocada: $6

Tu pote contactar $1 o un del altere [[{{MediaWiki:Grouppage-sysop}}|administratores]] pro discuter le blocada.

Nota que tu non pote utilisar le function "inviar e-mail a iste usator" si tu non ha registrate un adresse de e-mail valide in tu [[Special:Preferences|preferentias de usator]] e si tu non ha essite blocate de usar lo.

Le ID de iste blocada es $5.
Per favor include iste ID in omne correspondentia.',
'blockednoreason'                  => 'nulle ration date',
'blockedoriginalsource'            => "Le codice-fonte de '''$1''' se monstra infra:",
'blockededitsource'                => "Le texto de '''tu modificationes''' in '''$1''' se monstra infra:",
'whitelistedittitle'               => 'Session requirite pro modificar',
'whitelistedittext'                => 'Tu debe $1 pro poter modificar paginas.',
'whitelistreadtitle'               => 'Session requirite pro leger',
'whitelistreadtext'                => 'Tu debe [[Special:Userlogin|aperir un session]] pro poter leger paginas.',
'whitelistacctitle'                => 'Tu non ha le permission de crear un conto',
'whitelistacctext'                 => 'Le permission de crear contos in {{SITENAME}} require que tu [[Special:Userlogin|aperi un session]] e que tu ha le autorisation appropriate.',
'confirmedittitle'                 => 'Confirmation del adresse de e-mail es requirite pro poter modificar',
'confirmedittext'                  => 'Tu debe confirmar tu adresse de e-mail pro poter modificar paginas.
Per favor defini e valida tu adresse de e-mail per medio de tu [[Special:Preferences|preferentias de usator]].',
'nosuchsectiontitle'               => 'Tal section non existe',
'nosuchsectiontext'                => 'Tu essayava modificar un section que non existe.
Viste que il non ha alcun section $1, il non ha alcun location pro publicar tu modification.',
'loginreqtitle'                    => 'Session requirite',
'loginreqlink'                     => 'aperir un session',
'loginreqpagetext'                 => 'Tu debe $1 pro poter vider altere paginas.',
'accmailtitle'                     => 'Contrasigno inviate.',
'accmailtext'                      => 'Le contrasigno pro "$1" ha essite inviate a $2.',
'newarticle'                       => '(Nove)',
'newarticletext'                   => "Tu ha sequite un ligamine a un pagina que ancora non existe.
Pro crear un nove pagina, comencia a scriber in le cassa infra.
(Vide le [[{{MediaWiki:Helppage}}|pagina de adjuta]] pro plus information.)
Si tu es hic per error, simplemente clicca le button '''Retornar''' de tu navigator.",
'anontalkpagetext'                 => "---- ''Iste es le pagina de discussion pro un usator anonyme qui ancora non ha create un conto o qui non lo usa. Consequentemente nos debe usar le [[adresse de IP]] numeric pro identificar le/la. Un tal adresse de IP pote esser usate in commun per varie personas. Si tu es un usator anonyme e senti que commentarios irrelevante ha essite dirigite a te, per favor [[Special:Userlogin|crea un conto o aperi un session]] pro evitar futur confusiones con altere usatores anonyme.''",
'noarticletext'                    => 'Actualmente il non ha texto in iste pagina. Tu pote [[Special:Search/{{PAGENAME}}|cercar iste titulo]] in le texto de altere paginas o [{{fullurl:{{FULLPAGENAME}}|action=edit}} modificar iste pagina].',
'userpage-userdoesnotexist'        => 'Le conto de usator "$1" non es registrate. Per favor verifica que tu vole crear/modificar iste pagina.',
'clearyourcache'                   => "'''Nota - Post le publication, es possibile que tu debe temporarimente disactivar le ''cache'' de tu navigator pro vider le cambiamentos.''' '''Mozilla / Firefox / Safari:''' tenente ''Shift'' clicca ''Reload,'' o preme ''Ctrl-F5'' o ''Ctrl-R'' (''Command-R'' in un Macintosh); '''Konqueror: '''clicca ''Reload'' o preme ''F5;'' '''Opera:''' vacua le ''cache'' in ''Tools → Preferences;'' '''Internet Explorer:''' tenente ''Ctrl'' clicca ''Refresh,'' o preme ''Ctrl-F5.''",
'usercssjsyoucanpreview'           => "<strong>Consilio:</strong> Usa le button 'Monstrar previsualisation' pro testar tu nove CSS/JS ante de publicar lo.",
'usercsspreview'                   => "'''Memora que isto es solmente un previsualisation de tu CSS personalisate, illo non ha ancora essite immagazinate!'''",
'userjspreview'                    => "'''Memora que isto es solmente un test/previsualisation de tu JavaScript personalisate, illo non ha ancora essite immagazinate!'''",
'userinvalidcssjstitle'            => "'''Attention:''' Le stilo \"\$1\" non existe.
Memora que le paginas .css and .js personalisate usa un titulo in minusculas, p.ex. {{ns:user}}:Foo/monobook.css e non {{ns:user}}:Foo/Monobook.css.",
'updated'                          => '(Actualisate)',
'note'                             => '<strong>Nota:</strong>',
'previewnote'                      => '<strong>Isto es solmente un previsualisation;
le modificationes non ha ancora essite publicate!</strong>',
'previewconflict'                  => 'Iste previsualisation reflecte le apparentia final del texto in le area de modification superior
si tu opta pro publicar lo.',
'session_fail_preview'             => '<strong>Excusa! Nos non poteva processar tu modification proque nos perdeva le datos del session.
Per favor reprova. Si illo ancora non va, prova clauder e reaperir tu session.</strong>',
'session_fail_preview_html'        => "<strong>Excusa! Nos non poteva processar tu modification proque nos perdeva le datos del session.</strong>

''Viste que HTML crude es active in {{SITENAME}}, le previsualisation es celate como precaution contra attaccos via JavaScript.''

<strong>Si isto es un tentativa de modification legitime, per favor reprova lo. Si illo ancora non va, prova clauder e reaperir tu session.</strong>",
'token_suffix_mismatch'            => "<strong>Tu modification ha essite refusate proque tu cliente corrumpeva le characteres de punctuation in le indicio de modification.
Iste refusa es pro evitar le corruption del texto del pagina.
Isto pote occurrer quando tu usa un servicio problematic de ''proxy'' anonyme a base de web.</strong>",
'editing'                          => 'Modification de $1',
'editingsection'                   => 'Modification de $1 (section)',
'editingcomment'                   => 'Modification de $1 (commento)',
'editconflict'                     => 'Conflicto de edition: $1',
'explainconflict'                  => "Alcuno ha modificate iste pagina post que tu
ha comenciate a modificar lo.
Le area de texto superior contine le texto del pagina como illo existe actualmente.
Tu modificationes se monstra in le area de texto inferior.
Tu debera incorporar tu modificationes in le texto existente.
'''Solmente''' le texto del area superior essera publicate
quando tu cliccara super \"Publicar articulo\".",
'yourtext'                         => 'Tu texto',
'storedversion'                    => 'Version immagazinate',
'nonunicodebrowser'                => '<strong>ATTENTION: Tu utilisa un navigator non compatibile con le characteres Unicode.
Se ha activate un systema de modification alternative que te permittera modificar articulos con securitate: le characteres non-ASCII apparera in le quadro de modification como codices hexadecimal.</strong>',
'editingold'                       => '<strong>ATTENTION: In iste momento tu modifica
un version obsolete de iste pagina.
Si tu lo publica, tote le modificationes facite post iste revision essera perdite.</strong>',
'yourdiff'                         => 'Differentias',
'copyrightwarning'                 => 'Nota ben que tote le contributiones a {{SITENAME}} es considerate public secundo le $2 (vide plus detalios in $1).
Si tu non vole que tu scripto sia modificate impietosemente e redistribuite a voluntate, alora non lo submitte hic.<br />
In addition, tu nos garanti que tu es le autor de tu contributiones, o que tu los ha copiate de un ressource libere de derectos.
<strong>NON USA MATERIAL COPERITE PER DERECTOS DE AUTOR (COPYRIGHT) SIN AUTORISATION EXPRESSE!</strong>',
'copyrightwarning2'                => 'Nota ben que tote le contributiones a {{SITENAME}} pote esser redigite, alterate, o eliminate per altere contributores.
Si tu non vole que tu scripto sia modificate impietosemente, alora non lo submitte hic.<br />
In addition, tu nos garanti que tu es le autor de isto, o que tu lo ha copiate de un ressource a dominio public o alteremente libere de derectos (vide detalios in $1).
<strong>NON SUBMITTE MATERIAL SUBJECTE A COPYRIGHT SIN AUTORISATION EXPRESSE!</strong>',
'longpagewarning'                  => 'ADVERTIMENTO: Iste pagina ha $1 kilobytes de longitude;
alcun navigatores pote presentar problemas in editar
paginas de approximatemente o plus de 32kb.
Considera fragmentar le pagina in sectiones minor.',
'longpageerror'                    => '<strong>ERROR: Le texto que tu submitteva es $1 kilobytes de longor, excedente le maximo de $2 kilobytes.
Illo non pote esser immagazinate.</strong>',
'readonlywarning'                  => '<strong>ATTENTION: Le base de datos ha essite blocate pro mantenentia, ergo tu non pote immagazinar tu modificationes justo nunc.
Nos recommenda copiar-e-collar le texto pro salveguardar lo in un file de texto, assi que tu potera publicar lo plus tarde.</strong>',
'protectedpagewarning'             => '<strong>ATTENTION:  Iste pagina ha essite protegite. Solmente administratores pote modificar lo.</strong>',
'semiprotectedpagewarning'         => "'''Nota:''' Iste pagina ha essite protegite de maniera que solmente usatores registrate pote modificar lo.",
'cascadeprotectedwarning'          => "'''Attention:''' Iste pagina ha essite protegite de maniera que solmente administratores pote modificar lo, proque illo es includite in le protection in cascada del sequente {{PLURAL:$1|pagina|paginas}}:",
'titleprotectedwarning'            => '<strong>ATTENTION:  Iste pagina ha essite protegite de maniera que solmente certe usatores specific pote crear lo.</strong>',
'templatesused'                    => 'Patronos usate in iste pagina:',
'templatesusedpreview'             => 'Patronos usate in iste previsualisation:',
'templatesusedsection'             => 'Patronos usate in iste section:',
'template-protected'               => '(protegite)',
'template-semiprotected'           => '(semi-protegite)',
'hiddencategories'                 => 'Iste pagina es membro de {{PLURAL:$1|1 categoria|$1 categorias}} celate:',
'nocreatetitle'                    => 'Creation de paginas limitate',
'nocreatetext'                     => '{{SITENAME}} ha restringite le permission de crear nove paginas.
Tu pote retornar e modificar un pagina existente, o [[Special:Userlogin|identificar te, o crear un conto]].',
'nocreate-loggedin'                => 'Tu non ha le permission de crear nove paginas in {{SITENAME}}.',
'permissionserrors'                => 'Errores de permissiones',
'permissionserrorstext'            => 'Tu non ha le permission de facer isto, pro le sequente {{PLURAL:$1|motivo|motivos}}:',
'permissionserrorstext-withaction' => 'Tu non ha le permission de $2, pro le sequente {{PLURAL:$1|motivo|motivos}}:',
'recreate-deleted-warn'            => "'''Attention: Tu va recrear un pagina que esseva anteriormente eliminate.'''

Tu deberea considerar si il es appropriate crear iste pagina de novo.
Le registro de eliminationes pro iste pagina se revela hic pro major commoditate:",

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Attention: Iste pagina contine troppo de appellos costose al functiones del analysator syntactic.

Illo debe haber minus de $2, sed al momento ha $1.',
'expensive-parserfunction-category'       => 'Paginas con troppo de appellos costose al functiones del analysator syntactic',
'post-expand-template-inclusion-warning'  => 'Attention: Le grandor del patronos includite ha excedite le maximo.
Alcun patronos non essera includite.',
'post-expand-template-inclusion-category' => 'Paginas excedente le grandor maximal del patronos includite',
'post-expand-template-argument-warning'   => 'Attention: Iste pagina contine al minus un parametro de patrono que ha un grandor de expansion excessive.
Iste parametros ha essite omittite.',
'post-expand-template-argument-category'  => 'Paginas que omitte alcun parametros de patrono',

# "Undo" feature
'undo-success' => 'Le modification es reversibile. Per favor controla le comparation infra pro verificar que tu vole facer isto, e alora immagazina le modificationes infra pro completar le reversion del modification.',
'undo-failure' => 'Le modification non poteva esser revertite a causa de conflicto con modificationes intermedie.',
'undo-norev'   => 'Impossibile reverter le modification proque illo non existe o esseva delite.',
'undo-summary' => 'Reverte le revision $1 per [[Special:Contributions/$2|$2]] ([[User talk:$2|Discussion]])',

# Account creation failure
'cantcreateaccounttitle' => 'Non pote crear conto',
'cantcreateaccount-text' => "Le creation de contos desde iste adresse IP ('''$1''') ha essite blocate per [[User:$3|$3]].

Le motivo que $3 dava es ''$2''",

# History pages
'viewpagelogs'        => 'Vider le registro de iste pagina',
'nohistory'           => 'Iste pagina non ha versiones precedente.',
'revnotfound'         => 'Revision non trovate',
'revnotfoundtext'     => 'Impossibile trovar le version anterior del pagina que tu ha demandate.
Verifica le URL que tu ha usate pro accessar iste pagina.',
'currentrev'          => 'Revision actual',
'revisionasof'        => 'Revision de $1',
'revision-info'       => 'Revision del $1 per $2',
'previousrevision'    => '←Revision plus vetere',
'nextrevision'        => 'Revision plus nove→',
'currentrevisionlink' => 'Revision actual',
'cur'                 => 'actu',
'next'                => 'sequ',
'last'                => 'ultime',
'page_first'          => 'prime',
'page_last'           => 'ultime',
'histlegend'          => 'Legenda: (actu) = differentia del version actual,
(prec) = differentia con le version precedente, M = modification minor',
'deletedrev'          => '[delite]',
'histfirst'           => 'Prime',
'histlast'            => 'Ultime',
'historysize'         => '({{PLURAL:$1|1 byte|$1 bytes}})',
'historyempty'        => '(vacue)',

# Revision feed
'history-feed-title'          => 'Historia de revisiones',
'history-feed-description'    => 'Historia de revisiones pro iste pagina in le wiki',
'history-feed-item-nocomment' => '$1 a $2', # user at time
'history-feed-empty'          => 'Le pagina que tu requestava non existe.
Es possibile que illo esseva delite del wiki, o renominate.
Prova [[Special:Search|cercar nove paginas relevante]] in le wiki.',

# Revision deletion
'rev-deleted-comment'         => '(commento eliminate)',
'rev-deleted-user'            => '(nomine de usator eliminate)',
'rev-deleted-event'           => '(entrata eliminate)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
Iste revision del pagina ha essite eliminate del archivos public.
Es possibile que se trova detalios in le [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} registro de deletiones].</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">
Iste revision del pagina ha essite eliminate del archivos public.
Como administrator in {{SITENAME}} tu pote vider lo;
es possibile que se trova detalios in le [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} registro de deletiones].</div>',
'rev-delundel'                => 'revelar/celar',
'revisiondelete'              => 'Deler/restaurar revisiones',
'revdelete-nooldid-title'     => 'Le revision visate es invalide',
'revdelete-nooldid-text'      => 'O tu non ha specificate alcun revision(es) visate pro executar iste 
function, o le revision specificate non existe, o tu essaya celar le revision actual.',
'revdelete-selected'          => '{{PLURAL:$2|Revision seligite|Revisiones seligite}} de [[:$1]]:',
'logdelete-selected'          => '{{PLURAL:$1|Evento|Eventos}} de registro seligite:',
'revdelete-text'              => 'Le revisiones e eventos delite continuara a apparer in le historia e registro del pagina, sed partes de lor contento essera inaccessibile al publico.

Altere administratores in {{SITENAME}} continuara a poter acceder al contento celate e pote restaurar lo per medio de iste mesme interfacie, si non se ha definite restrictiones additional.',
'revdelete-legend'            => 'Definir restrictiones de visibilitate',
'revdelete-hide-text'         => 'Celar texto del revision',
'revdelete-hide-name'         => 'Celar action e objectivo',
'revdelete-hide-comment'      => 'Celar commento de modification',
'revdelete-hide-user'         => 'Celar nomine de usator o adresse IP del modificator',
'revdelete-hide-restricted'   => 'Applicar iste restrictiones al administratores e blocar iste interfacie',
'revdelete-suppress'          => 'Supprimer datos e de Administratores e de alteres',
'revdelete-hide-image'        => 'Celar contento del file',
'revdelete-unsuppress'        => 'Eliminar restrictiones super revisiones restaurate',
'revdelete-log'               => 'Commento pro registro:',
'revdelete-submit'            => 'Applicar al revision seligite',
'revdelete-logentry'          => 'cambiava le visibilitate de revisiones pro [[$1]]',
'logdelete-logentry'          => 'cambiava le visibilitate de eventos pro [[$1]]',
'revdelete-success'           => "'''Le visibilitate de revisiones ha essite definite con successo.'''",
'logdelete-success'           => "'''Le visibilitate del registro ha essite definite con successo.'''",
'revdel-restore'              => 'Cambiar visibilitate',
'pagehist'                    => 'Historia del pagina',
'deletedhist'                 => 'Historia delite',
'revdelete-content'           => 'contento',
'revdelete-summary'           => 'summario del modification',
'revdelete-uname'             => 'nomine de usator',
'revdelete-restricted'        => 'restrictiones applicate al administratores',
'revdelete-unrestricted'      => 'restrictiones eliminate pro administratores',
'revdelete-hid'               => 'celava $1',
'revdelete-unhid'             => 'revelava $1',
'revdelete-log-message'       => '$1 pro $2 {{PLURAL:$2|revision|revisiones}}',
'logdelete-log-message'       => '$1 pro $2 {{PLURAL:$2|evento|eventos}}',

# Suppression log
'suppressionlog'     => 'Registro de suppressiones',
'suppressionlogtext' => 'Infra es un lista de deletiones e blocadas que involve contento que es celate de administratores.
Vide le [[Special:Ipblocklist|lista de blocadas IP]] pro le lista de bannimentos e blocadas actualmente in operation.',

# History merging
'mergehistory'                     => 'Fusionar historias del paginas',
'mergehistory-header'              => 'Iste pagina te permitte fusionar revisiones del historia de un pagina de origine in un pagina plus nove.
Assecura te que iste cambio mantenera le continuitate historic del pagina.',
'mergehistory-box'                 => 'Fusionar le revisiones de duo paginas:',
'mergehistory-from'                => 'Pagina de origine:',
'mergehistory-into'                => 'Pagina de destination:',
'mergehistory-list'                => 'Historia de modificationes fusionabile',
'mergehistory-merge'               => 'Le sequente revisiones de [[:$1]] pote esser fusionate in [[:$2]].
Usa le columna de buttones radio pro fusionar solmente le revisiones create in e ante le tempore specificate.
Nota que le uso del ligamines de navigation causara le perdita de tote cambios in iste columna.',
'mergehistory-go'                  => 'Revelar modificationes fusionabile',
'mergehistory-submit'              => 'Fusionar revisiones',
'mergehistory-empty'               => 'Non se pote fusionar alcun revisiones.',
'mergehistory-success'             => '$3 {{PLURAL:$3|revision|revisiones}} de [[:$1]] fusionate in [[:$2]] con successo.',
'mergehistory-fail'                => 'Impossibile executar le fusion del historia. Per favor recontrola le parametros del pagina e del tempore.',
'mergehistory-no-source'           => 'Le pagina de origine $1 non existe.',
'mergehistory-no-destination'      => 'Le pagina de destination $1 non existe.',
'mergehistory-invalid-source'      => 'Le pagina de origine debe esser un titulo valide.',
'mergehistory-invalid-destination' => 'Le pagina de destination debe esser un titulo valide.',
'mergehistory-autocomment'         => 'Fusionava [[:$1]] in [[:$2]]',
'mergehistory-comment'             => 'Fusionava [[:$1]] in [[:$2]]: $3',

# Merge log
'mergelog'           => 'Fusionar registro',
'pagemerge-logentry' => 'fusionava [[$1]] in [[$2]] (revisiones usque a $3)',
'revertmerge'        => 'Reverter fusion',
'mergelogpagetext'   => 'Infra es un lista del fusiones le plus recente de un historia de pagina in un altere.',

# Diffs
'history-title'           => 'Historia de revisiones de "$1"',
'difference'              => '(Differentia inter revisiones)',
'lineno'                  => 'Linea $1:',
'compareselectedversions' => 'Comparar versiones seligite',
'editundo'                => 'revocar',
'diff-multi'              => '({{PLURAL:$1|Un revision intermedie|$1 revisiones intermedie}} non se revela.)',

# Search results
'searchresults'             => 'Resultatos del recerca',
'searchresulttext'          => 'Pro plus information super le recerca de {{SITENAME}}, vide [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'            => 'Pro le consulta "[[:$1]]"',
'searchsubtitleinvalid'     => 'Pro le consulta "$1"',
'noexactmatch'              => "'''Non existe un pagina con le titulo \"\$1\".'''
Tu pote [[:\$1|crear iste pagina]].",
'noexactmatch-nocreate'     => "'''Non existe un pagina con titulo \"\$1\".'''",
'toomanymatches'            => 'Se retornava troppo de resultatos. Per favor prova un altere consulta.',
'titlematches'              => 'Coincidentias con titulos de articulos',
'notitlematches'            => 'Necun coincidentia',
'textmatches'               => 'Coincidentias con textos de articulos',
'notextmatches'             => 'Necun coincidentia',
'prevn'                     => '$1 precedentes',
'nextn'                     => '$1 sequentes',
'viewprevnext'              => 'Vider ($1) ($2) ($3).',
'search-result-size'        => '$1 ({{PLURAL:$2|1 parola|$2 parolas}})',
'search-result-score'       => 'Relevantia: $1%',
'search-redirect'           => '(redirection $1)',
'search-section'            => '(section $1)',
'search-suggest'            => 'Esque tu vole dicer: $1',
'search-interwiki-caption'  => 'Projectos-sorores',
'search-interwiki-default'  => 'Resultatos de $1:',
'search-interwiki-more'     => '(plus)',
'search-mwsuggest-enabled'  => 'con suggestiones',
'search-mwsuggest-disabled' => 'sin suggestiones',
'search-relatedarticle'     => 'Connexe',
'mwsuggest-disable'         => 'Disactivar suggestiones via AJAX',
'searchrelated'             => 'connexe',
'searchall'                 => 'totes',
'showingresults'            => "Monstra de {{PLURAL:$1|'''1''' resultato|'''$1''' resultatos}} a partir de nº '''$2'''.",
'showingresultsnum'         => "Se monstra infra {{PLURAL:$3|'''1''' resultato|'''$3''' resultatos}} comenciante a #'''$2'''.",
'showingresultstotal'       => "Infra se monstra le {{PLURAL:$3|resultato '''$1''' de '''$3'''|resultatos '''$1 - $2''' de '''$3'''}}",
'nonefound'                 => '<strong>Nota</strong>: recercas frustrate frequentemente
es causate per le inclusion de vocabulos commun como "que" e "illo",
que non es includite in le indice, o per le specification de plure
terminos de recerca (solmente le paginas que contine tote le terminos
de recerca apparera in le resultato).',
'powersearch'               => 'Recerca avantiate',
'powersearch-legend'        => 'Recerca avantiate',
'powersearch-ns'            => 'Cercar in spatios de nomines:',
'powersearch-redir'         => 'Listar redirectiones',
'powersearch-field'         => 'Cercar',
'search-external'           => 'Recerca externe',
'searchdisabled'            => 'Le recerca in {{SITENAME}} es disactivate.
Tu pote cercar via Google in le interim.
Nota que lor indices del contento de {{SITENAME}} pote esser obsolete.',

# Preferences page
'preferences'              => 'Preferentias',
'mypreferences'            => 'Mi preferentias',
'prefs-edits'              => 'Numero de modificationes:',
'prefsnologin'             => 'Tu non ha aperite un session',
'prefsnologintext'         => 'Tu debe [[Special:Userlogin|aperir un session]]
pro definir tu preferentias.',
'prefsreset'               => 'Tu preferentias anterior ha essite restaurate.',
'qbsettings'               => 'Configuration del barra de utensiles',
'qbsettings-none'          => 'Nulle',
'qbsettings-fixedleft'     => 'Fixe a sinistra',
'qbsettings-fixedright'    => 'Fixe a dextera',
'qbsettings-floatingleft'  => 'Flottante a sinistra',
'qbsettings-floatingright' => 'Flottante a dextera',
'changepassword'           => 'Cambiar contrasigno',
'skin'                     => 'Apparentia',
'math'                     => 'Exhibition de formulas',
'dateformat'               => 'Formato de datas',
'datedefault'              => 'Nulle preferentia',
'datetime'                 => 'Data e hora',
'math_failure'             => 'Impossibile analysar',
'math_unknown_error'       => 'error incognite',
'math_unknown_function'    => 'function incognite',
'math_lexing_error'        => 'error lexic',
'math_syntax_error'        => 'error syntactic',
'math_image_error'         => "Le conversion in PNG ha fallite;
controla que le installation sia correcte del programmas ''latex, dvips, gs,'' e ''convert''.",
'math_bad_tmpdir'          => 'Non pote scriber in o crear le directorio temporari "math".',
'math_bad_output'          => 'Non pote scriber in o crear le directorio de output "math".',
'math_notexvc'             => "Le executabile ''texvc'' manca;
per favor vide math/README pro configurar lo.",
'prefs-personal'           => 'Profilo del usator',
'prefs-rc'                 => 'Modificationes recente',
'prefs-watchlist'          => 'Observatorio',
'prefs-watchlist-days'     => 'Numero de dies a monstrar in le observatorio:',
'prefs-watchlist-edits'    => 'Numero maximal de cambios a monstrar in le observatorio expandite:',
'prefs-misc'               => 'Misc',
'saveprefs'                => 'Confirmar',
'resetprefs'               => 'Reverter cambios',
'oldpassword'              => 'Contrasigno actual:',
'newpassword'              => 'Nove contrasigno:',
'retypenew'                => 'Confirma le nove contrasigno:',
'textboxsize'              => 'Dimensiones del cassa de texto',
'rows'                     => 'Lineas',
'columns'                  => 'Columnas',
'searchresultshead'        => 'Configuration del resultatos de recerca',
'resultsperpage'           => 'Coincidentias per pagina',
'contextlines'             => 'Lineas per coincidentia',
'contextchars'             => 'Characteres de contexto per linea',
'stub-threshold'           => 'Limite pro formatar le ligamines in <a href="#" class="stub">stilo de peciettas</a> (bytes):',
'recentchangesdays'        => 'Numero de dies a monstrar in modificationes recente:',
'recentchangescount'       => 'Quantitate de titulos in modificationes recente',
'savedprefs'               => 'Tu preferentias ha essite confirmate.',
'timezonelegend'           => 'Fuso horari',
'timezonetext'             => 'Scribe le differentia de horas inter tu fuso horari
e illo del servitor (UTC).',
'localtime'                => 'Hora local',
'timezoneoffset'           => 'Differentia de fuso horari',
'servertime'               => 'Hora del servitor',
'guesstimezone'            => 'Obtener del navigator',
'allowemail'               => 'Activar reception de e-mail de altere usatores',
'prefs-searchoptions'      => 'Optiones de recerca',
'prefs-namespaces'         => 'Spatios de nomines',
'defaultns'                => 'Cercar in iste spatios de nomines per predefinition:',
'default'                  => 'predefinition',
'files'                    => 'Files',

# User rights
'userrights'                  => 'Gestion de derectos de usator', # Not used as normal message but as header for the special page itself
'userrights-lookup-user'      => 'Gerer gruppos de usatores',
'userrights-user-editname'    => 'Entra un nomine de usator:',
'editusergroup'               => 'Modificar gruppos de usatores',
'editinguser'                 => "Cambiamento del derectos del usator '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]] | [[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'    => 'Modificar gruppos de usatores',
'saveusergroups'              => 'Immagazinar gruppos de usatores',
'userrights-groupsmember'     => 'Membro de:',
'userrights-groups-help'      => 'Tu pote alterar le gruppos del quales iste usator es membro:
* Un quadrato marcate significa que le usator es membro del gruppo in question.
* Un non marcate significa que ille non es membro de illo.
* Un * indica que tu non potera eliminar le gruppo quando tu lo ha addite, o vice versa.',
'userrights-reason'           => 'Motivo pro le cambio:',
'userrights-no-interwiki'     => 'Tu non ha le permission de modificar le derectos de usatores in altere wikis.',
'userrights-nodatabase'       => 'Le base de datos $1 non existe o non es local.',
'userrights-nologin'          => 'Tu debe [[Special:Userlogin|aperir un session]] con un conto de administrator pro poter assignar derectos de usator.',
'userrights-notallowed'       => 'Tu conto non ha le permission de assignar derectos de usator.',
'userrights-changeable-col'   => 'Gruppos que tu pote modificar',
'userrights-unchangeable-col' => 'Gruppos que tu non pote modificar',

# Groups
'group'               => 'Gruppo:',
'group-user'          => 'Usatores',
'group-autoconfirmed' => 'Usatores autoconfirmate',
'group-bot'           => 'Bots',
'group-sysop'         => 'Administratores',
'group-bureaucrat'    => 'Bureaucrates',
'group-suppress'      => 'Supervisores',
'group-all'           => '(totes)',

'group-user-member'          => 'Usator',
'group-autoconfirmed-member' => 'Usator autoconfirmate',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Administrator',
'group-bureaucrat-member'    => 'Bureaucrate',
'group-suppress-member'      => 'Supervisor',

'grouppage-user'          => '{{ns:project}}:Usatores',
'grouppage-autoconfirmed' => '{{ns:project}}:Usatores autoconfirmate',
'grouppage-bot'           => '{{ns:project}}:Bots',
'grouppage-sysop'         => '{{ns:project}}:Administratores',
'grouppage-bureaucrat'    => '{{ns:project}}:Bureaucrates',
'grouppage-suppress'      => '{{ns:project}}:Supervisores',

# Rights
'right-read'                 => 'Leger paginas',
'right-edit'                 => 'Modificar paginas',
'right-createpage'           => 'Crear paginas (non discussion)',
'right-createtalk'           => 'Crear paginas de discussion',
'right-createaccount'        => 'Crear nove contos de usator',
'right-minoredit'            => 'Marcar modificationes como minor',
'right-move'                 => 'Renominar paginas',
'right-move-subpages'        => 'Renominar paginas con lor subpaginas',
'right-suppressredirect'     => 'Non rediriger le ancian nomine verso le nove quando se renomina un pagina',
'right-upload'               => 'Cargar files',
'right-reupload'             => 'Suberscriber un file existente',
'right-reupload-own'         => 'Superscriber un file anteriormente cargate per uno mesme',
'right-reupload-shared'      => 'Supplantar localmente le files del respositorio commun de media',
'right-upload_by_url'        => 'Cargar un file ab un adresse URL',
'right-purge'                => 'Purgar le cache de un pagina in le sito sin confirmation',
'right-autoconfirmed'        => 'Modificar paginas semiprotegite',
'right-bot'                  => 'Esser tractate como processo automatic',
'right-nominornewtalk'       => 'Non reciper notification de nove messages quando se face modificationes minor in le pagina de discussion',
'right-apihighlimits'        => 'Usar limites plus alte in consultas via API',
'right-writeapi'             => 'Uso del API pro modificar le wiki',
'right-delete'               => 'Deler paginas',
'right-bigdelete'            => 'Deler paginas con historias longe',
'right-deleterevision'       => 'Deler e restaurar revisiones specific de paginas',
'right-deletedhistory'       => 'Vider entratas de historia delite, sin lor texto associate',
'right-browsearchive'        => 'Cercar in paginas delite',
'right-undelete'             => 'Restaurar un pagina',
'right-suppressrevision'     => 'Revider e restaurar revisiones celate ab administratores',
'right-suppressionlog'       => 'Vider registros private',
'right-block'                => 'Blocar altere usatores de facer modificationes',
'right-blockemail'           => 'Blocar un usator de inviar e-mail',
'right-hideuser'             => 'Blocar un nomine de usator, celante lo del publico',
'right-ipblock-exempt'       => 'Facer deviation circa le blocadas de adresses IP, blocadas automatic e blocadas de gammas IP',
'right-proxyunbannable'      => 'Facer deviation circa le blocadas automatic de proxy',
'right-protect'              => 'Cambiar nivellos de protection e modificar paginas protegite',
'right-editprotected'        => 'Modificar paginas protegite (sin cascada)',
'right-editinterface'        => 'Modificar le interfacie de usator',
'right-editusercssjs'        => 'Modificar le files CSS e JS de altere usatores',
'right-rollback'             => 'Rapidemente reverter le modificationes del ultime usator que modificava un pagina particular',
'right-markbotedits'         => 'Marcar modificationes de reversion como facite per un bot',
'right-noratelimit'          => 'Non esser subjecte al limites de frequentia de actiones',
'right-import'               => 'Importar paginas de altere wikis',
'right-importupload'         => 'Importar paginas specificate in un file que tu carga',
'right-patrol'               => 'Marcar le modificationes de alteres como patruliate',
'right-autopatrol'           => 'Marcar automaticamente le proprie modificationes como patruliate',
'right-patrolmarks'          => 'Vider marcas de patrulia in le modificationes recente',
'right-unwatchedpages'       => 'Vider un lista de paginas non observate',
'right-trackback'            => 'Submitter un retroligamine',
'right-mergehistory'         => 'Fusionar le historia de paginas',
'right-userrights'           => 'Modificar tote le derectos de usator',
'right-userrights-interwiki' => 'Modificar le derectos de usatores in altere wikis',
'right-siteadmin'            => 'Blocar e disblocar le base de datos',

# User rights log
'rightslog'      => 'Registro de derectos de usator',
'rightslogtext'  => 'Isto es un registro de cambios in derectos de usator.',
'rightslogentry' => 'cambiava le membrato de gruppos del usator $1 de $2 a $3',
'rightsnone'     => '(nulle)',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|modification|modificationes}}',
'recentchanges'                     => 'Modificationes recente',
'recentchangestext'                 => 'Seque le plus recente modificationes a {{SITENAME}} in iste pagina.',
'recentchanges-feed-description'    => 'Seque le modificationes le plus recente al wiki in iste syndication.',
'rcnote'                            => "Infra es {{PLURAL:$1|'''1''' modification|le ultime '''$1''' modificationes}} in le ultime {{PLURAL:$2|die|'''$2''' dies}}, actualisate le $4 a $5.",
'rcnotefrom'                        => 'infra es le modificationes a partir de <b>$2</b> (usque a <b>$1</b>).',
'rclistfrom'                        => 'Monstrar nove modificationes a partir de $1',
'rcshowhideminor'                   => '$1 modificationes minor',
'rcshowhidebots'                    => '$1 bots',
'rcshowhideliu'                     => '$1 usatores registrate',
'rcshowhideanons'                   => '$1 usatores anonyme',
'rcshowhidepatr'                    => '$1 modificationes patruliate',
'rcshowhidemine'                    => '$1 mi modificationes',
'rclinks'                           => 'Monstrar le $1 ultime modificationes in le $2 ultime dies<br />$3',
'diff'                              => 'diff',
'hist'                              => 'prec',
'hide'                              => 'Celar',
'show'                              => 'Revelar',
'minoreditletter'                   => 'M',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|usator|usatores}} observante]',
'rc_categories'                     => 'Limite a categorias (separar con "|")',
'rc_categories_any'                 => 'Qualcunque',
'newsectionsummary'                 => '/* $1 */ nove section',

# Recent changes linked
'recentchangeslinked'          => 'Modificationes correlate',
'recentchangeslinked-title'    => 'Modificationes associate a "$1"',
'recentchangeslinked-noresult' => 'Nulle modificationes in paginas ligate durante iste periodo.',
'recentchangeslinked-summary'  => "Isto es un lista de modificationes facite recentemente a paginas al quales se refere ligamines in un altere pagina specific (o a membros de un categoria specific).
Le paginas presente in [[Special:Watchlist|tu observatorio]] se revela in litteras '''grasse'''.",
'recentchangeslinked-page'     => 'Nomine del pagina:',
'recentchangeslinked-to'       => 'Monstrar modificationes in le paginas al quales le pagina que tu specificava contine ligamines',

# Upload
'upload'                      => 'Cargar file',
'uploadbtn'                   => 'Cargar file',
'reupload'                    => 'Recargar',
'reuploaddesc'                => 'Retornar al formulario de carga.',
'uploadnologin'               => 'Tu non ha aperite un session',
'uploadnologintext'           => 'Tu debe [[Special:Userlogin|aperir un session]]
pro poter cargar files.',
'upload_directory_missing'    => 'Le directorio de cargamento ($1) manca, e le servitor de web non poteva crear lo.',
'upload_directory_read_only'  => 'Le servitor de web non ha le permission de scriber in le directorio de cargamento ($1).',
'uploaderror'                 => 'Error de carga',
'uploadtext'                  => "'''STOP!''' Ante cargar files al servitor,
prende cognoscentia del politica de {{SITENAME}} super le uso de imagines,
e assecura te de respectar lo.

Pro vider o recercar imagines cargate previemente,
vade al [[Special:Imagelist|lista de imagines cargate]].
Cargas e eliminationes es registrate in le
[[Special:Log/upload|registro de cargas]].

Usa le formulario infra pro cargar nove files de imagine pro
illustrar tu articulos.
In le major parte del navigatores, tu videra un button \"Browse...\",
que facera apparer le cassa de dialogo de apertura de files
standard de tu systema de operation. Selectiona un file pro
inserer su nomine in le campo de texto adjacente al button.
Tu debe additionalmente marcar le quadrato con le qual tu
declara que tu non viola derectos de autor per medio del carga
del file.
Preme le button \"Cargar\" pro initiar le transmission.
Le carga pote prender alcun tempore si tu connexion al Internet
es lente.

Le formatos preferite es JPEG pro imagines photographic,
PNG pro designos e altere imagines iconic, e OGG pro sonos.
Per favor, attribue nomines descriptive a tu files pro evitar
confusion.
Pro includer le imagine in un articulo, usa un ligamine in
le forma '''<nowiki>[[image:file.jpg]]</nowiki>''' o
'''<nowiki>[[image:file.png|texto alternative]]</nowiki>''' o
'''<nowiki>[[media:file.ogg]]</nowiki>''' pro sonos.

Nota que, justo como occurre con le paginas de {{SITENAME}}, alteros
pote modificar o eliminar le files cargate si illes considera que
isto beneficia le encyclopedia, e tu pote haber tu derecto
de carga blocate si tu abusa del systema.",
'upload-permitted'            => 'Typos de file permittite: $1.',
'upload-preferred'            => 'Typos de file preferite: $1.',
'upload-prohibited'           => 'Typos de file prohibite: $1.',
'uploadlog'                   => 'registro de cargas',
'uploadlogpage'               => 'Registro de cargas',
'uploadlogpagetext'           => 'Infra es un lista del plus recente cargas de files.',
'filename'                    => 'Nomine del file',
'filedesc'                    => 'Summario',
'fileuploadsummary'           => 'Summario:',
'filestatus'                  => 'Stato de copyright:',
'filesource'                  => 'Fonte:',
'uploadedfiles'               => 'Files cargate',
'ignorewarning'               => 'Ignorar le advertimento e immagazinar totevia le file',
'ignorewarnings'              => 'Ignorar omne advertimentos',
'minlength1'                  => 'Le nomines de file debe haber al minus un littera.',
'illegalfilename'             => 'Le nomine de file "$1" contine characteres que non es permittite in le titulos de paginas.
Per favor renomina le file e prova recargar lo.',
'badfilename'                 => 'Le nomine del imagine esseva cambiate a "$1".',
'filetype-badmime'            => 'Non es permittite cargar files del typo MIME "$1".',
'filetype-unwanted-type'      => "'''\".\$1\"''' es un typo de file non desirate.
Le {{PLURAL:\$3|typo|typos}} de file preferite es \$2.",
'filetype-banned-type'        => "Le typo de file '''\".\$1\"''' non es permittite.
Le {{PLURAL:\$3|typo|typos}} de file permittite es \$2.",
'filetype-missing'            => 'Le nomine del file non ha un extension (como ".jpg").',
'large-file'                  => 'Es recommendate que le files non sia plus grande de $1;
iste file occupa $2.',
'largefileserver'             => 'Le grandor de iste file excede le limite configurate in le servitor.',
'emptyfile'                   => 'Le file que tu cargava pare esser vacue. Isto pote esser debite a un error in le nomine del file.
Per favor assecura que tu realmente vole cargar un file con precisemente iste nomine.',
'fileexists'                  => 'Un file con iste nomine jam existe. Per favor verifica <strong><tt>$1</tt></strong> si tu non es secur de voler cambiar lo.',
'filepageexists'              => 'Le pagina de description correspondente a iste file jam ha essite create a <strong><tt>$1</tt></strong>, sed un file con iste nomine non existe al momento.
Le summario que tu entra non apparera in le pagina de description.
Si tu vole que illo appare, tu debe inserer lo manualmente.',
'fileexists-extension'        => 'Un file con un nomine similar jam existe:<br />
Nomine del file que tu carga: <strong><tt>$1</tt></strong><br />
Nomine del file existente: <strong><tt>$2</tt></strong><br />
Per favor selige un altere nomine.',
'fileexists-thumb'            => "<center>'''File existente'''</center>",
'fileexists-thumbnail-yes'    => 'Iste file pare esser un imagine a grandor reducite <i>(miniatura)</i>.
Per favor verifica le file <strong><tt>$1</tt></strong>.<br />
Si le file verificate es le mesme imagine a grandor original, non es necessari cargar un miniatura additional.',
'file-thumbnail-no'           => 'Le nomine del file comencia con <strong><tt>$1</tt></strong>.
Illo pare esser un imagine a grandor reducite <i>(miniatura)</i>.
Si tu possede iste imagine in plen resolution, carga lo, alteremente cambia le nomine del file per favor.',
'fileexists-forbidden'        => 'Un file con iste nomine jam existe;
per favor retorna e carga iste file sub un altere nomine. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Un file con iste nomine jam existe in le repositorio de files commun;
per favor retorna e carga iste file sub un altere nomine. [[Image:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Iste file es un duplicato del sequente {{PLURAL:$1|file|files}}:',
'successfulupload'            => 'Carga complete',
'uploadwarning'               => 'Advertimento de carga',
'savefile'                    => 'Immagazinar file',
'uploadedimage'               => '"[[$1]]" cargate',
'overwroteimage'              => 'cargava un nove version de "[[$1]]"',
'uploaddisabled'              => 'Cargamentos disactivate',
'uploaddisabledtext'          => 'Le cargamento de files es disactivate in {{SITENAME}}.',
'uploadscripted'              => 'Iste file contine codice de HTML o de script que pote esser interpretate erroneemente per un navigator del web.',
'uploadcorrupt'               => 'Le file es corrupte o su nomine ha un extension incorrecte.
Per favor verifica le file e recarga lo.',
'uploadvirus'                 => 'Le file contine un virus! Detalios: $1',
'sourcefilename'              => 'Nomine del file de origine:',
'destfilename'                => 'Nomine del file de destination:',
'upload-maxfilesize'          => 'Grandor maximal del files: $1',
'watchthisupload'             => 'Observar iste pagina',
'filewasdeleted'              => 'Un file con iste nomine ha anteriormente essite cargate e postea delite.
Tu debe revider le $1 ante de proceder e recargar lo.',
'upload-wasdeleted'           => "'''Attention: Tu va cargar un file que esseva anteriormente delite.'''

Tu debe considerar si es appropriate continuar a cargar iste file.
Pro major commoditate se trova hic le registro de eliminationes correspondente a iste file:",
'filename-bad-prefix'         => 'Le nomine del file que tu va cargar comencia con <strong>"$1"</strong>, le qual es un nomine non descriptive, typicamente assignate automaticamente per le cameras digital.
Per favor selige un nomine plus descriptive pro tu file.',

'upload-proto-error'      => 'Protocollo incorrecte',
'upload-proto-error-text' => 'Le cargamento remote require que le adresses URL comencia con <code>http://</code> o <code>ftp://</code>.',
'upload-file-error'       => 'Error interne',
'upload-file-error-text'  => 'Un error interne occurreva quando se tentava crear un file temporari in le servitor.
Per favor contacta un administrator de systemas.',
'upload-misc-error'       => 'Error de cargamento non cognoscite',
'upload-misc-error-text'  => 'Un error non cognoscite occurreva durante le cargamento.
Per favor verifica que le adresse URL sia valide e accessible, e reprova.
Si le problema persiste, contacta un administrator de systemas.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Non poteva acceder al URL',
'upload-curl-error6-text'  => 'Le adresse URL fornite es inaccessibile.
Per favor reverifica que le adresse URL sia correcte e que le sito sia in operation.',
'upload-curl-error28'      => 'Le cargamento se prolongava troppo',
'upload-curl-error28-text' => 'Le sito non respondeva intra le limite de tempore.
Per favor controla que le sito sia in operation, attende un poco e reprova.
Pote esser preferite reprovar quando le sito es minus occupate.',

'license'            => 'Licentia:',
'nolicense'          => 'Necun licentia seligite',
'license-nopreview'  => '(Previsualisation non disponibile)',
'upload_source_url'  => ' (un adresse URL valide e publicamente accessibile)',
'upload_source_file' => ' (un file in tu computator)',

# Special:Imagelist
'imagelist-summary'     => 'Iste pagina special monstra tote le files cargate.
Per predefinition le ultime files cargate se monstra al initio del lista.
Tu pote reordinar le lista con un clic super le titulo de un columna.',
'imagelist_search_for'  => 'Cercar un nomine de media:',
'imgfile'               => 'file',
'imagelist'             => 'Lista de imagines',
'imagelist_date'        => 'Data',
'imagelist_name'        => 'Nomine',
'imagelist_user'        => 'Usator',
'imagelist_size'        => 'Grandor',
'imagelist_description' => 'Description',

# Image description page
'filehist'                       => 'Historia del file',
'filehist-help'                  => 'Clicca super un data/hora pro vider le file como appareva a ille tempore.',
'filehist-deleteall'             => 'deler totes',
'filehist-deleteone'             => 'deler',
'filehist-revert'                => 'reverter',
'filehist-current'               => 'actual',
'filehist-datetime'              => 'Data/Hora',
'filehist-user'                  => 'Usator',
'filehist-dimensions'            => 'Dimensiones',
'filehist-filesize'              => 'Grandor del file',
'filehist-comment'               => 'Commento',
'imagelinks'                     => 'Ligamines',
'linkstoimage'                   => 'Le paginas sequente se liga a iste imagine:',
'nolinkstoimage'                 => 'Necun pagina se liga a iste imagine.',
'morelinkstoimage'               => 'Vider [[Special:Whatlinkshere/$1|plus ligamines]] a iste file.',
'redirectstofile'                => 'Le sequente {{PLURAL:$1|file|$1 files}} se redirige a iste file:',
'duplicatesoffile'               => 'Le sequente {{PLURAL:$1|files es un duplicato|$1 files es duplicatos}} de iste file:',
'sharedupload'                   => 'Iste file ha essite cargate pro uso in commun; altere projectos pote usar lo.',
'shareduploadwiki'               => 'Per favor vide le $1 pro ulterior informationes.',
'shareduploadwiki-desc'          => 'Infra se monstra le description in su $1 in le repositorio commun.',
'shareduploadwiki-linktext'      => 'pagina de description de file',
'shareduploadduplicate'          => 'Iste file es un duplicato de $1 del repositorio commun.',
'shareduploadduplicate-linktext' => 'un altere file',
'shareduploadconflict'           => 'Iste file ha le mesme nomine que $1 del repositorio commun.',
'shareduploadconflict-linktext'  => 'un altere file',
'noimage'                        => 'Non existe un file con iste nomine. Vos pote $1.',
'noimage-linktext'               => 'cargar lo',
'uploadnewversion-linktext'      => 'Cargar un nove version de iste file',
'imagepage-searchdupe'           => 'Cercar files duplicate',

# File reversion
'filerevert'                => 'Reverter $1',
'filerevert-legend'         => 'Reverter file',
'filerevert-intro'          => '<span class="plainlinks">Tu reverte \'\'\'[[Media:$1|$1]]\'\'\' al [$4 version del $3 a $2].</span>',
'filerevert-comment'        => 'Commento:',
'filerevert-defaultcomment' => 'Revertite al version del $2 a $1',
'filerevert-submit'         => 'Reverter',
'filerevert-success'        => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\' ha essite revertite al [$4 version del $3 a $2].</span>',
'filerevert-badversion'     => 'Non existe un version local anterior de iste file con le data e hora providite.',

# File deletion
'filedelete'                  => 'Deler $1',
'filedelete-legend'           => 'Deler file',
'filedelete-intro'            => "Tu va deler '''[[Media:$1|$1]]'''.",
'filedelete-intro-old'        => '<span class="plainlinks">Tu va deler le version de \'\'\'[[Media:$1|$1]]\'\'\' del [$4 $3 a $2].</span>',
'filedelete-comment'          => 'Motivo pro deletion:',
'filedelete-submit'           => 'Deler',
'filedelete-success'          => "'''$1''' ha essite delite.",
'filedelete-success-old'      => '<span class="plainlinks">Le version de \'\'\'[[Media:$1|$1]]\'\'\' del $3 a $2 ha essite delite.</span>',
'filedelete-nofile'           => "'''$1''' non existe in {{SITENAME}}.",
'filedelete-nofile-old'       => "Non existe un version archivate de '''$1''' con le attributos specificate.",
'filedelete-iscurrent'        => 'Tu essaya deler le version le plus recente de iste file.
Per favor reverte lo primo a un version anterior.',
'filedelete-otherreason'      => 'Motivo altere/additional:',
'filedelete-reason-otherlist' => 'Altere motivo',
'filedelete-reason-dropdown'  => '*Motivos habitual pro deletion
** Violation de copyright
** File duplicate',
'filedelete-edit-reasonlist'  => 'Modificar motivos pro deletion',

# MIME search
'mimesearch'         => 'Recerca de typo MIME',
'mimesearch-summary' => 'Iste pagina permitte filtrar le files a base de lor typos MIME.
Syntaxe: typo/subtypo, p.ex. <tt>image/jpeg</tt>.',
'mimetype'           => 'Typo MIME:',
'download'           => 'discargar',

# Unwatched pages
'unwatchedpages' => 'Paginas non observate',

# List redirects
'listredirects' => 'Listar redirectiones',

# Unused templates
'unusedtemplates'     => 'Patronos non usate',
'unusedtemplatestext' => 'Iste pagina es un lista de tote le paginas in le spatio de nomines "Patrono" que non es includite in un altere pagina.
Memora verificar que non existe altere ligamines al patronos ante que tu los dele.',
'unusedtemplateswlh'  => 'altere ligamines',

# Random page
'randompage'         => 'Pagina aleatori',
'randompage-nopages' => 'Il non ha paginas in iste spatio de nomines.',

# Random redirect
'randomredirect'         => 'Redirection aleatori',
'randomredirect-nopages' => 'Il non ha redirectiones in iste spatio de nomines.',

# Statistics
'statistics'             => 'Statisticas',
'sitestats'              => 'Statisticas de accesso',
'userstats'              => 'Statisticas de usator',
'sitestatstext'          => "Le base de datos contine un total de {{PLURAL:\$1|'''1''' pagina|'''\$1''' paginas}}.
Iste numero include paginas de \"discussion\", paginas super {{SITENAME}}, \"peciettas\"
minimal, redirectiones, e altere paginas que probabilemente non se qualifica como articulos.
Excludente {{PLURAL:\$1|iste|istes}}, il remane {{PLURAL:\$2|'''1''' pagina|'''\$2''' paginas}} que probabilemente es
{{PLURAL:\$2|un articulo|articulos}} legitime.

'''\$8''' {{PLURAL:\$8|file|files}} ha essite cargate.

Il habeva un total de '''\$3''' {{PLURAL:\$3|visita a un pagina|visitas a paginas}}, e '''\$4''' {{PLURAL:\$4|modification de un pagina|modificationes de paginas}}
desde le establimento de {{SITENAME}}.
Isto representa un media de '''\$5''' modificationes per pagina, e '''\$6''' visitas per modification.

Le longor del [http://www.mediawiki.org/wiki/Manual:Job_queue cauda de actiones] es '''\$7'''.",
'userstatstext'          => "Il ha {{PLURAL:$1|'''1''' [[Special:Listusers|usator]]|'''$1''' [[Special:Listusers|usatores]]}} registrate, del quales '''$2''' (i.e. '''$4%''') ha le derectos de $5.",
'statistics-mostpopular' => 'Le paginas plus visitate',

'disambiguations'      => 'Paginas de disambiguation',
'disambiguationspage'  => '{{ns:project}}:Ligamines_a_paginas_de_disambiguation',
'disambiguations-text' => "Le sequente paginas ha ligamines a un '''pagina de disambiguation'''.
Istes deberea esser reimplaciate con ligamines al topicos appropriate.<br />
Un pagina se tracta como pagina de disambiguation si illo usa un patrono al qual [[MediaWiki:Disambiguationspage]] ha un ligamine.",

'doubleredirects'     => 'Redirectiones duple',
'doubleredirectstext' => '<b>Attention:</b> Iste lista pote continer items false.
Illo generalmente significa que il ha texto additional con ligamines sub le prime #REDIRECT.<br />
Cata linea contine ligamines al prime e secunde redirection, assi como le prime linea del
secunde texto de redirection, generalmente exhibiente le articulo scopo "real",
al qual le prime redirection deberea referer se.',

'brokenredirects'        => 'Redirectiones van',
'brokenredirectstext'    => 'Le redirectiones sequente se liga a articulos inexistente.',
'brokenredirects-edit'   => '(modificar)',
'brokenredirects-delete' => '(deler)',

'withoutinterwiki'         => 'Paginas sin ligamines de lingua',
'withoutinterwiki-summary' => 'Le sequente paginas non ha ligamines a versiones in altere linguas:',
'withoutinterwiki-legend'  => 'Prefixo',
'withoutinterwiki-submit'  => 'Revelar',

'fewestrevisions' => 'Paginas le minus modificate',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories'             => '$1 {{PLURAL:$1|categoria|categorias}}',
'nlinks'                  => '$1 {{PLURAL:$1|ligamine|ligamines}}',
'nmembers'                => '$1 {{PLURAL:$1|membro|membros}}',
'nviews'                  => '$1 {{PLURAL:$1|visita|visitas}}',
'lonelypages'             => 'Paginas orphanas',
'uncategorizedpages'      => 'Paginas non classificate',
'uncategorizedcategories' => 'Categorias non classificate',
'uncategorizedimages'     => 'Files non categorisate',
'uncategorizedtemplates'  => 'Patronos non classificate',
'unusedcategories'        => 'Categorias non usate',
'unusedimages'            => 'Imagines non usate',
'popularpages'            => 'Paginas popular',
'wantedcategories'        => 'Categorias plus demandate',
'wantedpages'             => 'Paginas plus demandate',
'mostlinked'              => 'Paginas le plus ligate',
'mostlinkedcategories'    => 'Categorias le plus ligate',
'mostlinkedtemplates'     => 'Patronos le plus utilisate',
'mostcategories'          => 'Paginas con le plus categorias',
'mostimages'              => 'Files le plus utilisate',
'mostrevisions'           => 'Paginas le plus modificate',
'prefixindex'             => 'Indice de prefixos',
'shortpages'              => 'Paginas curte',
'longpages'               => 'Paginas longe',
'deadendpages'            => 'Paginas sin exito',
'protectedpages'          => 'Paginas protegite',
'listusers'               => 'Lista de usatores',
'newpages'                => 'Nove paginas',
'newpages-username'       => 'Nomine de usator:',
'ancientpages'            => 'Paginas le plus ancian',
'move'                    => 'Renominar',
'movethispage'            => 'Renominar iste pagina',
'unusedimagestext'        => '<p>Nota que altere sitos del web
tal como le {{SITENAME}}s international pote ligar se a un imagine
con un URL directe, e consequentemente illos pote esser listate
hic malgrado esser in uso active.',
'notargettitle'           => 'Sin scopo',
'notargettext'            => 'Tu non ha specificate un pagina o usator super le qual
executar iste function.',

# Book sources
'booksources' => 'Fornitores de libros',

# Special:Log
'specialloguserlabel'  => 'Usator:',
'speciallogtitlelabel' => 'Titulo:',
'log'                  => 'Registros',
'all-logs-page'        => 'Tote le registros',

# Special:Allpages
'allpages'       => 'Tote le paginas',
'alphaindexline' => '$1 a $2',
'nextpage'       => 'Sequente pagina ($1)',
'prevpage'       => 'Precedente pagina ($1)',
'allpagesfrom'   => 'Monstrar le paginas a partir de:',
'allarticles'    => 'Tote le paginas',
'allpagesprev'   => 'Previe',
'allpagesnext'   => 'Sequente',
'allpagessubmit' => 'Ir',
'allpagesprefix' => 'Monstrar le paginas con prefixo:',

# Special:Categories
'categories' => 'Categorias',

# Special:Listusers
'listusers-submit' => 'Revelar',

# Special:Listgrouprights
'listgrouprights-group' => 'Gruppo',

# E-mail user
'mailnologin'     => 'Necun adresse de invio',
'mailnologintext' => 'Tu debe [[Special:Userlogin|aperir un session]]
e haber un adresse de e-mail valide in tu [[Special:Preferences|preferentias]]
pro inviar e-mail a altere usatores.',
'emailuser'       => 'Inviar e-mail a iste usator',
'emailpage'       => 'Inviar e-mail al usator',
'emailpagetext'   => 'Si iste usator forniva un adresse de e-mail valide in
su preferentias de usator, le formulario infra le/la inviara un message.
Le adresse de e-mail que tu forniva in tu preferentias de usator apparera
como le adresse del expeditor del e-mail, a fin que le destinatario
pote responder te.',
'noemailtitle'    => 'Necun adresse de e-mail',
'noemailtext'     => 'Iste usator non ha specificate un adresse de e-mail valide,
o ha optate pro non reciper e-mail de altere usatores.',
'emailfrom'       => 'De',
'emailto'         => 'A',
'emailsubject'    => 'Subjecto',
'emailmessage'    => 'Message',
'emailsend'       => 'Inviar',
'emailsent'       => 'E-mail inviate',
'emailsenttext'   => 'Tu message de e-mail ha essite inviate.',

# Watchlist
'watchlist'            => 'Mi observatorio',
'mywatchlist'          => 'Mi observatorio',
'watchlistfor'         => "(pro '''$1''')",
'nowatchlist'          => 'Tu non ha paginas sub observation.',
'watchnologin'         => 'Tu non ha aperite un session',
'watchnologintext'     => 'Tu debe [[Special:Userlogin|aperir un session]]
pro modificar tu lista de paginas sub observation.',
'addedwatch'           => 'Addite al observatorio',
'addedwatchtext'       => "Le pagina \"<nowiki>\$1</nowiki>\" es ora in tu [[Special:Watchlist|observatorio]].
Omne modificationes futur a iste pagina e su pagina de discussion associate essera listate ibi,
e le pagina apparera '''in litteras grasse''' in le [[Special:Recentchanges|lista de modificationes recente]] pro
facilitar su identification.",
'removedwatch'         => 'Eliminate del observatorio',
'removedwatchtext'     => 'Le pagina "<nowiki>$1</nowiki>" non es plus sub observation.',
'watch'                => 'Observar',
'watchthispage'        => 'Observar iste pagina',
'unwatch'              => 'Disobservar',
'unwatchthispage'      => 'Cancellar observation',
'notanarticle'         => 'Non es un articulo',
'watchlist-details'    => '{{PLURAL:$1|$1 pagina|$1 paginas}} es sub observation, excludente paginas de discussion.',
'wlshowlast'           => 'Revelar ultime $1 horas $2 dies $3',
'watchlist-hide-bots'  => 'Celar modificationes per bots',
'watchlist-hide-own'   => 'Celar mi modificationes',
'watchlist-hide-minor' => 'Celar modificationes minor',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Observation in curso...',
'unwatching' => 'Disobservation in curso...',

# Delete/protect/revert
'deletepage'                  => 'Eliminar pagina',
'confirm'                     => 'Confirmar',
'delete-legend'               => 'Deler',
'historywarning'              => 'Attention: Le pagina que tu va deler ha un historia:',
'confirmdeletetext'           => 'Tu es a puncto de eliminar permanentemente un pagina
o imagine del base de datos, conjunctemente con tote su chronologia de versiones.
Per favor, confirma que, si tu intende facer lo, tu comprende le consequentias,
e tu lo face de accordo con [[{{MediaWiki:Policy-url}}]].',
'actioncomplete'              => 'Action complite',
'deletedtext'                 => '"<nowiki>$1</nowiki>" ha essite eliminate.
Vide $2 pro un registro de eliminationes recente.',
'deletedarticle'              => '"$1" eliminate',
'dellogpage'                  => 'Registro de eliminationes',
'dellogpagetext'              => 'Infra es un lista del plus recente eliminationes.
Tote le horas es in le fuso horari del servitor (UTC).',
'deletionlog'                 => 'registro de eliminationes',
'reverted'                    => 'Revertite a revision anterior',
'deletecomment'               => 'Motivo pro deletion:',
'deleteotherreason'           => 'Motivo altere/additional:',
'deletereasonotherlist'       => 'Altere motivo',
'delete-edit-reasonlist'      => 'Modificar motivos pro deletion',
'rollback'                    => 'Revocar modificationes',
'rollbacklink'                => 'revocar',
'cantrollback'                => 'Impossibile revocar le modification; le ultime contribuente es le unic autor de iste articulo.',
'revertpage'                  => 'Revertite modificationes per [[Special:Contributions/$2|$2]] ([[User talk:$2|Discussion]])
al ultime modification per [[User:$1|$1]]', # Additional available: $3: revid of the revision reverted to, $4: timestamp of the revision reverted to, $5: revid of the revision reverted from, $6: timestamp of the revision reverted from
'protectlogpage'              => 'Registro de protectiones',
'protectcomment'              => 'Commento:',
'protectexpiry'               => 'Expira:',
'protect_expiry_invalid'      => 'Le tempore de expiration es invalide.',
'protect_expiry_old'          => 'Le tempore de expiration es in le passato.',
'protect-unchain'             => 'Disserrar permissiones de renomination',
'protect-text'                => 'Tu pote vider e cambiar hic le nivello de protection del pagina <strong><nowiki>$1</nowiki></strong>.',
'protect-locked-access'       => 'Tu conto non ha permission a cambiar le nivellos de protection de paginas.
Ecce le configurationes actual del pagina <strong>$1</strong>:',
'protect-cascadeon'           => 'Iste pagina es actualmente protegite proque es includite in le sequente {{PLURAL:$1|pagina, le qual|paginas, le quales}} ha activate le protection in cascada.
Tu pote cambiar le nivello de protection de iste pagina, sed isto non cambiara le effecto del protection in cascada.',
'protect-default'             => '(predefinition)',
'protect-fallback'            => 'Requirer permission de "$1"',
'protect-level-autoconfirmed' => 'Blocar usatores non registrate',
'protect-level-sysop'         => 'Administratores solmente',
'protect-summary-cascade'     => 'in cascada',
'protect-expiring'            => 'expira le $1 (UTC)',
'protect-cascade'             => 'Proteger le paginas includite in iste pagina (protection in cascada)',
'protect-cantedit'            => 'Tu non pote cambiar le nivellos de protection de iste pagina, proque tu non ha le autorisation de modificar le pagina.',
'restriction-type'            => 'Permission:',
'restriction-level'           => 'Nivello de restriction:',

# Restrictions (nouns)
'restriction-edit'   => 'Modificar',
'restriction-move'   => 'Renominar',
'restriction-create' => 'Crear',

# Undelete
'undelete'          => 'Restaurar pagina eliminate',
'undeletepage'      => 'Vider e restaurar paginas eliminate',
'undeletepagetext'  => 'Le paginas sequente ha essite eliminate mais ancora es in le archivo e
pote esser restaurate. Le archivo pote esser evacuate periodicamente.',
'undeleterevisions' => '$1 {{PLURAL:$1|revision|revisiones}} archivate',
'undeletehistory'   => 'Si tu restaura un pagina, tote le revisiones essera restaurate al chronologia.
Si un nove pagina con le mesme nomine ha essite create post le elimination, le revisiones
restaurate apparera in le chronologia anterior, e le revision currente del pagina in vigor
non essera automaticamente substituite.',
'undeletebtn'       => 'Restaurar',
'undeletecomment'   => 'Commento:',
'undeletedarticle'  => '"$1" restaurate',
'undeletedfiles'    => '$1 {{PLURAL:$1|archivo|archivos}} restaurate',

# Namespace form on various pages
'namespace'      => 'Spatio de nomine:',
'invert'         => 'Inverter selection',
'blanknamespace' => '(Principal)',

# Contributions
'contributions' => 'Contributiones de usator',
'mycontris'     => 'Mi contributiones',
'contribsub2'   => 'Pro $1 ($2)',
'nocontribs'    => 'Necun modification ha essite trovate secundo iste criterios.',
'uctop'         => ' (alto)',
'month'         => 'A partir del mense (e anterior):',
'year'          => 'A partir del anno (e anterior):',

'sp-contributions-newbies-sub' => 'Pro nove contos',
'sp-contributions-blocklog'    => 'Registro de blocadas',

# What links here
'whatlinkshere'       => 'Referentias a iste pagina',
'whatlinkshere-title' => 'Paginas con ligamines a $1',
'whatlinkshere-page'  => 'Pagina:',
'linklistsub'         => '(Lista de ligamines)',
'linkshere'           => "Le paginas sequente se liga a '''[[:$1]]''':",
'nolinkshere'         => "Necun pagina se liga a '''[[:$1]]'''.",
'isredirect'          => 'pagina de redirection',
'istemplate'          => 'inclusion',
'whatlinkshere-prev'  => '{{PLURAL:$1|precedente|precedente $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|sequente|sequente $1}}',
'whatlinkshere-links' => '← ligamines',

# Block/unblock
'blockip'            => 'Blocar adresse IP',
'blockiptext'        => 'Usa le formulario infra pro blocar le accesso de scriptura
a partir de un adresse IP specific.
Isto debe esser facite solmente pro impedir vandalismo, e de
accordo con le [[{{MediaWiki:Policy-url}}|politica de {{SITENAME}}]].
Scribe un motivo specific infra (per exemplo, citante paginas
specific que ha essite vandalisate).',
'ipaddress'          => 'Adresse IP',
'ipbreason'          => 'Motivo:',
'ipbreasonotherlist' => 'Altere motivo',
'ipbsubmit'          => 'Blocar iste adresse',
'ipboptions'         => '2 horas:2 hours,1 die:1 day,3 dies:3 days,1 septimana:1 week,2 septimanas:2 weeks,1 mense:1 month,3 menses:3 months,6 menses:6 months,1 anno:1 year,infinite:infinite', # display1:time1,display2:time2,...
'ipbotherreason'     => 'Motivo altere/additional:',
'badipaddress'       => 'Adresse IP mal formate.',
'blockipsuccesssub'  => 'Blocada succedite',
'blockipsuccesstext' => 'Le adresse IP "$1" ha essite blocate.
<br />Vide [[Special:Ipblocklist|Lista de IPs blocate]] pro revider le blocadas.',
'unblockip'          => 'Disblocar adresse IP',
'unblockiptext'      => 'Usa le formulario infra pro restaurar le accesso de scriptura
a un adresse IP blocate previemente.',
'ipusubmit'          => 'Disbloca iste adresse',
'ipblocklist'        => 'Lista de adresses IP blocate',
'blocklistline'      => '$1, $2 ha blockate $3 ($4)',
'blocklink'          => 'blocar',
'unblocklink'        => 'disblocar',
'contribslink'       => 'contributiones',
'blocklogpage'       => 'Registro de blocadas',
'blocklogentry'      => 'blocava [[$1]] con un tempore de expiration de $2 $3',

# Developer tools
'lockdb'              => 'Blocar base de datos',
'unlockdb'            => 'Disblocar base de datos',
'lockdbtext'          => 'Le blocada del base de datos suspendera le capacitate de tote
le usatores de modificar paginas, modificar lor preferentias e observatorios,
e altere actiones que require modificationes in le base de datos.
Per favor confirma que isto es tu intention, e que tu disblocara le
base de datos immediatemente post completar tu mantenentia.',
'unlockdbtext'        => 'Le disblocada del base de datos restaurara le capacitate de tote
le usatores de modificar paginas, modificar lor preferentias e observatorios,
e altere actiones que require modificationes in le base de datos.
Per favor confirma que isto es tu intention.',
'lockconfirm'         => 'Si, io realmente vole blocar le base de datos.',
'unlockconfirm'       => 'Si, io realmente vole disblocar le base de datos.',
'lockbtn'             => 'Blocar base de datos',
'unlockbtn'           => 'Disblocar base de datos',
'locknoconfirm'       => 'Tu non ha marcate le quadrato de confirmation.',
'lockdbsuccesssub'    => 'Base de datos blocate con successo',
'unlockdbsuccesssub'  => 'Base de datos disblocate con successo',
'lockdbsuccesstext'   => 'Le base de datos de {{SITENAME}} ha essite blocate.
<br />Rememora te de disblocar lo post completar tu mantenentia.',
'unlockdbsuccesstext' => 'Le base de datos de {{SITENAME}} ha essite disblocate.',

# Move page
'move-page-legend' => 'Renominar pagina',
'movepagetext'     => "Per medio del formulario infra tu pote renominar un pagina,
e transferer tote su chronologia al nove nomine.
Le titulo anterior devenira un pagina de redirection al nove titulo.
Le ligamines al pagina anterior non essera modificate;
assecura te de verificar le apparition de redirectiones duple o defecte.
Tu es responsabile pro assecurar que le ligamines continua a punctar a ubi illos deberea.

Nota que le pagina '''non''' essera renominate si ja existe un pagina
sub le nove titulo, salvo si illo es vacue o un redirection e non
ha un historia de modificationes passate. Isto significa que tu
pote renominar un pagina a su titulo original si tu lo ha renominate
per error, e que tu non pote superscriber un pagina existente.

'''ATTENTION!'''
Isto pote esser un cambio drastic e inexpectate pro un pagina popular;
per favor assecura te que tu comprende le consequentias de isto
ante que tu procede.",
'movepagetalktext' => "Le pagina de discussion associate essera automaticamente renominate conjunctemente con illo '''a minus que''':
*Un pagina de discussion non vacue ja existe sub le nove nomine, o
*Tu dismarca le quadrato infra.

Il tal casos, tu debera renominar o fusionar le pagina manualmente si desirate.",
'movearticle'      => 'Renominar pagina:',
'movenologin'      => 'Tu non ha aperite un session',
'movenologintext'  => 'Tu debe esser un usator registrate e [[Special:Userlogin|aperir un session]]
pro mover un pagina.',
'newtitle'         => 'Al nove titulo',
'move-watch'       => 'Observar iste pagina',
'movepagebtn'      => 'Renominar pagina',
'pagemovedsub'     => 'Renomination succedite',
'movepage-moved'   => '<big>\'\'\'"$1" ha essite renominate a "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'    => 'Un pagina con iste nomine ja existe, o le nomine seligite non es valide.
Per favor selige un altere nomine.',
'talkexists'       => "'''Le pagina mesme ha essite renominate con successo, mais le pagina de discussion associate non ha essite renominate proque ja existe un sub le nove titulo.
Per favor fusiona los manualmente.'''",
'movedto'          => 'renominate a',
'movetalk'         => 'Renominar etiam le pagina de discussion associate',
'1movedto2'        => '[[$1]] renominate a [[$2]]',
'1movedto2_redir'  => '[[$1]] movite a [[$2]] trans redirection',
'movelogpage'      => 'Registro de renominationes',
'movereason'       => 'Motivo:',
'revertmove'       => 'reverter',

# Export
'export' => 'Exportar paginas',

# Namespace 8 related
'allmessages'     => 'Messages del systema',
'allmessagesname' => 'Nomine',

# Thumbnails
'thumbnail-more'  => 'Ampliar',
'thumbnail_error' => 'Error durante le creation del miniatura: $1',

# Special:Import
'import' => 'Importar paginas',

# Import log
'importlogpage' => 'Registro de importationes',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Mi pagina de usator',
'tooltip-pt-mytalk'               => 'Mi pagina de discussion',
'tooltip-pt-preferences'          => 'Mi preferentias',
'tooltip-pt-watchlist'            => 'Le lista de paginas que tu survelia pro modificationes',
'tooltip-pt-mycontris'            => 'Lista de mi contributiones',
'tooltip-pt-login'                => 'Nos recommenda que tu te identifica, ma il non es obligatori.',
'tooltip-pt-logout'               => 'Clauder session',
'tooltip-ca-talk'                 => 'Discussiones a proposito del pagina de contento',
'tooltip-ca-edit'                 => 'Vos pote modificar iste pagina. Per favor usa le button "Monstrar previsualisation" ante que vos publica vostre modificationes.',
'tooltip-ca-addsection'           => 'Adder un commento a iste discussion.',
'tooltip-ca-viewsource'           => 'Iste pagina es protegite. Tu pote vider le codice-fonte de illo.',
'tooltip-ca-protect'              => 'Proteger iste pagina',
'tooltip-ca-delete'               => 'Eliminar iste pagina',
'tooltip-ca-move'                 => 'Renominar iste pagina',
'tooltip-ca-watch'                => 'Adder iste pagina a tu observatorio',
'tooltip-ca-unwatch'              => 'Eliminar iste pagina de tu observatorio',
'tooltip-search'                  => 'Recercar {{SITENAME}}',
'tooltip-p-logo'                  => 'Frontispicio',
'tooltip-n-mainpage'              => 'Visitar le Frontispicio',
'tooltip-n-portal'                => 'A proposito del projecto, que vos pote facer, ubi trovar cosas',
'tooltip-n-currentevents'         => 'Cerca informationes de fundo relative al actualitate',
'tooltip-n-recentchanges'         => 'Le lista de modificationes recente in le wiki.',
'tooltip-n-randompage'            => 'Visita un pagina qualcunque',
'tooltip-n-help'                  => 'Le solutiones de vostre problemas.',
'tooltip-t-whatlinkshere'         => 'Lista de tote le paginas wiki con ligamines a iste pagina',
'tooltip-t-contributions'         => 'Vider le lista de contributiones de iste usator',
'tooltip-t-emailuser'             => 'Inviar un e-mail a iste usator',
'tooltip-t-upload'                => 'Carga files',
'tooltip-t-specialpages'          => 'Lista de tote le paginas special',
'tooltip-ca-nstab-user'           => 'Vider le pagina de usator',
'tooltip-ca-nstab-project'        => 'Vider le pagina de projecto',
'tooltip-ca-nstab-image'          => 'Vide le pagina del file',
'tooltip-ca-nstab-template'       => 'Vider le patrono',
'tooltip-ca-nstab-help'           => 'Vider le pagina de adjuta',
'tooltip-ca-nstab-category'       => 'Vide le pagina del categoria',
'tooltip-minoredit'               => 'Marcar isto como un modification minor',
'tooltip-save'                    => 'Confirmar tu modificationes',
'tooltip-preview'                 => 'Per favor verifica tu modificationes ante que tu los publica!',
'tooltip-diff'                    => 'Detaliar le modificationes que tu ha facite al texto.',
'tooltip-compareselectedversions' => 'Vide le differentias inter le duo versiones selectionate de iste pagina.',
'tooltip-watch'                   => 'Adder iste pagina a tu observatorio',

# Math options
'mw_math_png'    => 'Sempre produce PNG',
'mw_math_simple' => 'HTML si multo simple, alteremente PNG',
'mw_math_html'   => 'HTML si possibile, alteremente PNG',
'mw_math_source' => 'Lassa lo como TeX (pro navigatores in modo texto)',
'mw_math_modern' => 'Recommendate pro navigatores moderne',
'mw_math_mathml' => 'MathML',

# Browsing diffs
'previousdiff' => '← Precedente diff',
'nextdiff'     => 'Sequente diff →',

# Media information
'file-info-size'       => '($1 × $2 pixel, grandor del file: $3, typo MIME: $4)',
'file-nohires'         => '<small>Non es disponibile un resolution plus alte.</small>',
'svg-long-desc'        => '(File SVG, dimensiones nominal: $1 × $2 pixels, grandor del file: $3)',
'show-big-image'       => 'Plen resolution',
'show-big-image-thumb' => '<small>Dimensiones de iste previsualisation: $1 × $2 pixels</small>',

# Special:Newimages
'newimages'     => 'Galleria de nove files',
'imagelisttext' => "Infra es un lista de '''$1''' {{PLURAL:$1|imagine|imagines}} ordinate $2.",
'showhidebots'  => '($1 bots)',
'ilsubmit'      => 'Recercar',
'bydate'        => 'per data',

# Bad image list
'bad_image_list' => 'Le formato es como seque:

Solmente punctos de lista (lineas que comencia con *) es considerate.
Le prime ligamine in un linea debe esser un ligamine a un file invalide.
Omne ligamines posterior in le mesme linea es considerate como exceptiones, i.e. paginas in que le file pote esser directemente incorporate.',

# Metadata
'metadata'          => 'Metadatos',
'metadata-help'     => 'Iste file contine informationes additional, que probabilemente ha venite del camera digital o scanner usate pro crear o digitalisar lo.
Si le file ha essite modificate de su stato original, es possibile que alcun detalios non reflecte completemente le file modificate.',
'metadata-expand'   => 'Revelar detalios extense',
'metadata-collapse' => 'Celar detalios extense',
'metadata-fields'   => "Le campos de metadatos EXIF in iste message essera includite in le visualisation del pagina de imagine quando le tabula de metadatos es collabite.
Alteres essera celate initialmente.
* make ''(marca)''
* model ''(modello)''
* datetimeoriginal ''(data e hora original)''
* exposuretime ''(velocitate de obturation)''
* fnumber ''(relation focal)''
* focallength ''(distantia focal)''", # Do not translate list items

# External editor support
'edit-externally'      => 'Modifica iste file con un programma externe',
'edit-externally-help' => 'Vide le [http://meta.wikimedia.org/wiki/Help:External_editors instructiones de configuration] pro ulterior informationes.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'totes',
'imagelistall'     => 'totes',
'watchlistall2'    => 'totes',
'namespacesall'    => 'totes',
'monthsall'        => 'totes',

# action=purge
'confirm_purge_button' => 'OK',

# Watchlist editor
'watchlistedit-raw-title'  => 'Modification del observatorio in forma crude',
'watchlistedit-raw-legend' => 'Modification del observatorio in forma crude',

# Watchlist editing tools
'watchlisttools-view' => 'Vider cambios relevante',
'watchlisttools-edit' => 'Vider e modificar le observatorio',
'watchlisttools-raw'  => 'Modificar observatorio crude',

# Special:Version
'version'              => 'Version', # Not used as normal message but as header for the special page itself
'version-specialpages' => 'Paginas special',

# Special:Filepath
'filepath-page' => 'File:',

# Special:SpecialPages
'specialpages' => 'Paginas special',

);

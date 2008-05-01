<?php
/** Interlingua (Interlingua)
 *
 * @addtogroup Language
 *
 * @author לערי ריינהארט
 * @author Siebrand
 * @author Malafaya
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
'tog-underline'        => 'Sublinear ligamines',
'tog-highlightbroken'  => 'Formatar ligamines rupte <a href="" class="new">assi</a> (alternativemente: assi<a href="" class="internal">?</a>).',
'tog-justify'          => 'Justificar paragraphos',
'tog-hideminor'        => 'Occultar modificationes recente minor',
'tog-usenewrc'         => 'Modificationes recente meliorate (non functiona in tote le navigatores)',
'tog-numberheadings'   => 'Numerar titulos automaticamente',
'tog-showtoolbar'      => 'Show edit toolbar',
'tog-editondblclick'   => 'Duple clic pro modificar un pagina (usa JavaScript)',
'tog-rememberpassword' => 'Recordar contrasigno inter sessiones (usa cookies)',
'tog-editwidth'        => 'Cassa de redaction occupa tote le largor del fenestra',
'tog-watchdefault'     => 'Poner articulos nove e modificate sub observation',
'tog-minordefault'     => 'Marcar modificationes initialmente como minor',
'tog-previewontop'     => 'Monstrar previsualisation ante le cassa de edition e non post illo',

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
'categories'             => 'Categorias',
'category_header'        => 'Articulos in le categoria "$1"',
'subcategories'          => 'Subcategorias',
'listingcontinuesabbrev' => 'cont.',

'about'         => 'A proposito',
'newwindow'     => '(aperi in un nove fenestra)',
'cancel'        => 'Cancellar',
'qbfind'        => 'Trovar',
'qbbrowse'      => 'Foliar',
'qbedit'        => 'Modificar',
'qbpageoptions' => 'Optiones de pagina',
'qbpageinfo'    => 'Info del pagina',
'qbmyoptions'   => 'Mi optiones',
'mypage'        => 'Mi pagina',
'mytalk'        => 'Mi discussion',
'and'           => 'e',

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
'printableversion'  => 'Version imprimibile',
'permalink'         => 'Ligamine permanente',
'edit'              => 'Modificar',
'editthispage'      => 'Modificar iste pagina',
'delete'            => 'Eliminar',
'deletethispage'    => 'Eliminar iste pagina',
'protectthispage'   => 'Proteger iste pagina',
'unprotectthispage' => 'Disproteger iste pagina',
'newpage'           => 'Nove pagina',
'talkpage'          => 'Discuter iste pagina',
'talkpagelinktext'  => 'Discussion',
'specialpage'       => 'Pagina Special',
'personaltools'     => 'Utensiles personal',
'articlepage'       => 'Vider article',
'talk'              => 'Discussion',
'toolbox'           => 'Cassa de utensiles',
'userpage'          => 'Vider pagina del usator',
'projectpage'       => 'Vider metapagina',
'imagepage'         => 'Vider pagina de imagine',
'viewtalkpage'      => 'Vider discussion',
'otherlanguages'    => 'Altere linguas',
'redirectedfrom'    => '(Redirigite de $1)',
'lastmodifiedat'    => 'Ultime modification: $2, $1.', # $1 date, $2 time
'viewcount'         => 'Iste pagina esseva accessate {{PLURAL:$1|un vice|$1 vices}}.',
'protectedpage'     => 'Pagina protegite',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'A proposito de {{SITENAME}}',
'aboutpage'            => 'Project:A_proposito',
'bugreports'           => 'Reportos de disfunctiones',
'bugreportspage'       => 'Project:Reportos_de_disfunctiones',
'copyrightpagename'    => '{{SITENAME}} e derectos de autor (copyright)',
'copyrightpage'        => '{{ns:project}}:Copyright',
'currentevents'        => 'Actualitates',
'currentevents-url'    => 'Project:Actualitates',
'disclaimers'          => 'Declarationes de exemption de responsabilitates',
'disclaimerpage'       => 'Project:Declaration general de exemption de responsabilitates',
'edithelp'             => 'Adjuta al edition',
'edithelppage'         => 'Help:Como_editar_un_pagina',
'faq'                  => 'Questiones frequente',
'faqpage'              => 'Project:Questiones_frequente',
'helppage'             => 'Help:Adjuta',
'mainpage'             => 'Frontispicio',
'mainpage-description' => 'Frontispicio',
'portal'               => 'Portal del communitate',
'portal-url'           => 'Project:Portal del communitate',
'privacy'              => 'Politica de confidentialitate',
'privacypage'          => 'Project:Politica de confidentialitate',
'sitesupport'          => 'Donationes',

'retrievedfrom'      => 'Recuperate de "$1"',
'youhavenewmessages' => 'Tu ha $1 ($2).',
'newmessageslink'    => 'messages nove',
'editsection'        => 'modificar',
'showtoc'            => 'monstrar',
'hidetoc'            => 'occultar',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'Pagina',
'nstab-user'     => 'Pagina de usator',
'nstab-project'  => 'Pagina de projecto',
'nstab-image'    => 'Archivo',
'nstab-template' => 'Patrono',
'nstab-category' => 'Categoria',

# Main script and global functions
'nosuchaction'      => 'Action inexistente',
'nosuchactiontext'  => 'Le action specificate in le URL non es
recognoscite per le systema de Mediawiki.',
'nosuchspecialpage' => 'Pagina special inexistente',
'nospecialpagetext' => '
Tu demandava un pagina special que non es
recognoscite per le systema de Mediawiki.',

# General errors
'databaseerror'   => 'Error de base de datos',
'dberrortext'     => 'Occurreva un error de syntaxe in le consulta al base de datos.
Le ultime demanda inviate al base de datos esseva:
<blockquote><tt>$1</tt></blockquote>
de intra le function "<tt>$2</tt>".
MySQL retornava le error "<tt>$3: $4</tt>".',
'noconnect'       => 'Impossibile connecter al base de datos a $1',
'nodb'            => 'Impossibile selectionar base de datos $1',
'readonly'        => 'Base de datos blocate',
'enterlockreason' => 'Describe le motivo del blocage, includente un estimation
de quando illo essera terminate',
'readonlytext'    => 'Actualmente le base de datos de {{SITENAME}} es blocate pro nove
entratas e altere modificationes, probabilemente pro mantenentia
routinari del base de datos, post le qual illo retornara al normal.
Le administrator responsabile dava iste explication:
<p>$1',
'missingarticle'  => 'Le base de datos non trovava le texto de un pagina
que illo deberea haber trovate, a saper "$1".
Isto non es un error de base de datos, mais probabilemente
un disfunction in le systema.
Per favor reporta iste occurrentia a un administrator,
indicante le URL.',
'internalerror'   => 'Error interne',
'filecopyerror'   => 'Impossibile copiar file "$1" a "$2".',
'filerenameerror' => 'Impossibile renominar file "$1" a "$2".',
'filedeleteerror' => 'Impossibile eliminar file "$1".',
'filenotfound'    => 'Impossibile trovar file "$1".',
'unexpected'      => 'Valor impreviste: "$1"="$2".',
'formerror'       => 'Error: impossibile submitter formulario',
'badarticleerror' => 'Iste action non pote esser effectuate super iste pagina.',
'cannotdelete'    => 'Impossibile eliminar le pagina o imagine specificate. (Illo pote ja haber essite eliminate per un altere persona.)',
'badtitle'        => 'Titulo incorrecte',
'badtitletext'    => 'Le titulo de pagina demandate esseva invalide, vacue, o
un titulo interlinguistic o interwiki incorrectemente ligate.',
'perfdisabled'    => 'Pardono! Iste functionalitate es temporarimente inactivate durante
horas de grande affluentia de accessos pro motivo de performance;
retorna inter 02:00 e 14:00 UTC e tenta de nove.',
'viewsource'      => 'Vider codice fonte',

# Login and logout pages
'logouttitle'             => 'Fin de session',
'logouttext'              => 'Tu claudeva tu session.
Tu pote continuar a usar {{SITENAME}} anonymemente, o initiar un
nove session como le mesme o como un altere usator.',
'welcomecreation'         => '<h2>Benvenite, $1!</h2>
<p>Tu conto de usator esseva create.
Non oblida personalisar {{SITENAME}} secundo tu preferentias.',
'loginpagetitle'          => 'Aperir session',
'yourname'                => 'Tu nomine de usator',
'yourpassword'            => 'Tu contrasigno',
'yourpasswordagain'       => 'Confirmar contrasigno',
'remembermypassword'      => 'Recordar contrasigno inter sessiones.',
'loginproblem'            => '<b>Occurreva problemas pro initiar tu session.</b><br />Tenta de nove!',
'login'                   => 'Aperir session',
'nav-login-createaccount' => 'Aperir session',
'userlogin'               => 'Aperir session',
'logout'                  => 'Clauder session',
'userlogout'              => 'Clauder session',
'createaccount'           => 'Crear nove conto',
'badretype'               => 'Le duo contrasignos que tu scribeva non coincide.',
'userexists'              => 'Le nomine de usator que tu selectionava ja es in uso. Per favor selectiona un nomine differente.',
'youremail'               => 'Tu e-mail',
'yourrealname'            => 'Nomine real:',
'yournick'                => 'Tu pseudonymo (pro signaturas)',
'loginerror'              => 'Error in le apertura del session',
'noname'                  => 'Tu non specificava un nomine de usator valide.',
'loginsuccesstitle'       => 'Session aperte con successo',
'loginsuccess'            => 'Tu es identificate in {{SITENAME}} como "$1".',
'nosuchuser'              => 'Non existe usator registrate con le nomine "$1".
Verifica le orthographia, o usa le formulario infra pro crear un nove conto de usator.',
'wrongpassword'           => 'Le contrasigno que tu scribeva es incorrecte. Per favor tenta de nove.',
'mailmypassword'          => 'Demandar un nove contrasigno via e-mail',
'passwordremindertitle'   => 'Nove contrasigno in {{SITENAME}}',
'passwordremindertext'    => 'Alcuno (probabilemente tu, con adresse de IP $1)

demandava inviar te un nove contrasigno pro {{SITENAME}}.
Le contrasigno pro le usator "$2" ora es "$3".
Nos consilia que tu initia un session e cambia le contrasigno le plus tosto possibile.',
'noemail'                 => 'Non existe adresse de e-mail registrate pro le usator "$1".',
'passwordsent'            => 'Un nove contrasigno esseva inviate al adresse de e-mail
registrate pro "$1".
Per favor initia un session post reciper lo.',
'accountcreated'          => 'Conto create',
'loginlanguagelabel'      => 'Lingua: $1',

# Edit page toolbar
'bold_sample'     => 'Texto grasse',
'bold_tip'        => 'Texto grasse',
'italic_sample'   => 'Texto italic',
'italic_tip'      => 'Texto italic',
'link_sample'     => 'Titulo del ligamine',
'link_tip'        => 'Ligamine interne',
'extlink_sample'  => 'http://www.exemplo.com titulo del ligamine',
'headline_sample' => 'Texto del titulo',
'headline_tip'    => 'Titulo de nivello 2',
'math_sample'     => 'Inserer formula aqui',

# Edit pages
'summary'          => 'Summario',
'subject'          => 'Subjecto/titulo',
'minoredit'        => 'Iste es un modification minor',
'watchthis'        => 'Poner iste articulo sub observation',
'savearticle'      => 'Salvar articulo',
'preview'          => 'Previsualisar',
'showpreview'      => 'Monstrar previsualisation',
'showdiff'         => 'Monstrar cambios',
'blockedtitle'     => 'Le usator es blocate',
'blockedtext'      => "Tu nomine de usator o adresse de IP ha essite blocate per $1.
Le motivo presentate es iste:<br />''$2''<p>Tu pote contactar $1 o un del altere
[[{{MediaWiki:Grouppage-sysop}}|administratores]] pro discuter le bloco.",
'newarticle'       => '(Nove)',
'newarticletext'   => "Tu ha sequite un ligamine a un pagina que ancora non existe.
Pro crear un nove pagina, comencia a scriber in le cassa infra.
(Vide le [[{{MediaWiki:Helppage}}|pagina de adjuta]] pro plus information.)
Si tu es hic per error, simplemente clicca le button '''Retornar''' de tu navigator.",
'anontalkpagetext' => "---- ''Iste es le pagina de discussion pro un usator anonyme qui ancora non ha create un conto o qui non lo usa. Consequentemente nos debe usar le [[adresse de IP]] numeric pro identificar le/la. Un tal adresse de IP pote esser usate in commun per varie personas. Si tu es un usator anonyme e senti que commentarios irrelevante ha essite dirigite a te, per favor [[Special:Userlogin|crea un conto o aperi un session]] pro evitar futur confusiones con altere usatores anonyme.''",
'noarticletext'    => '(Actualmente il non ha texto in iste pagina)',
'updated'          => '(Actualisate)',
'note'             => '<strong>Nota:</strong>',
'previewnote'      => 'Rememora te que isto es solmente un previsualisation, tu modificationes ancora non ha essite salvate!',
'previewconflict'  => 'Iste previsualisation reflecte le apparentia final del texto in le area de redaction superior
si tu opta pro salvar lo.',
'editing'          => 'Modification de $1',
'editconflict'     => 'Conflicto de edition: $1',
'explainconflict'  => 'Alcuno ha modificate iste pagina post que tu
ha comenciate a modificar lo.
Le area de texto superior contine le texto del pagina tal como illo existe actualmente.
Tu modificationes es monstrate in le area de texto inferior.
Tu debera incorporar tu modificationes al texto existente.
<b>Solmente</b> le texto del area superior essera salvate
quando tu premera "Salvar pagina".<br />',
'yourtext'         => 'Tu texto',
'storedversion'    => 'Version immagazinate',
'editingold'       => '<strong>ADVERTIMENTO: In iste momento tu modifica
un version obsolete de iste pagina.
Si tu lo salvara, tote le modificationes facite post iste revision essera perdite.</strong>',
'yourdiff'         => 'Differentias',
'copyrightwarning' => 'Nota que tote le contributiones a {{SITENAME}} es considerate public secundo le $2 (vide plus detalios in $1).
Si tu non vole que tu scripto sia modificate impietosemente e redistribuite a voluntate, tunc non lo edita aqui.<br />
Additionalmente, tu nos garanti que tu es le autor de tu contributiones, o que tu los ha copiate de un ressource libere de derectos.
<strong>NON USA MATERIAL COPERITE PER DERECTOS DE AUTOR (COPYRIGHT) SIN AUTORISATION EXPRESSE!</strong>',
'longpagewarning'  => 'ADVERTIMENTO: Iste pagina ha $1 kilobytes de longitude;
alcun navigatores pote presentar problemas in editar
paginas de approximatemente o plus de 32kb.
Considera fragmentar le pagina in sectiones minor.',

# History pages
'nohistory'        => 'Iste pagina non ha versiones precedente.',
'revnotfound'      => 'Revision non trovate',
'revnotfoundtext'  => 'Impossibile trovar le version anterior del pagina que tu ha demandate.
Verifica le URL que tu ha usate pro accessar iste pagina.',
'currentrev'       => 'Revision currente',
'revisionasof'     => 'Revision de $1',
'previousrevision' => '←Revision plus vetere',
'nextrevision'     => 'Revision plus nove→',
'cur'              => 'actu',
'next'             => 'sequ',
'last'             => 'prec',
'histlegend'       => 'Legenda: (actu) = differentia del version actual,
(prec) = differentia con le version precedente, M = modification minor',
'histfirst'        => 'Prime',
'histlast'         => 'Ultime',

# Diffs
'difference' => '(Differentia inter revisiones)',
'lineno'     => 'Linea $1:',
'editundo'   => 'revocar',

# Search results
'searchresults'         => 'Resultatos del recerca',
'searchresulttext'      => 'Pro plus information super le recerca de {{SITENAME}}, vide [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'        => 'Pro le consulta "[[:$1]]"',
'searchsubtitleinvalid' => 'Pro le consulta "$1"',
'noexactmatch'          => 'Non existe un pagina con iste titulo exacte, io recurre al recerca de texto integral.',
'titlematches'          => 'Coincidentias con titulos de articulos',
'notitlematches'        => 'Necun coincidentia',
'textmatches'           => 'Coincidentias con textos de articulos',
'notextmatches'         => 'Necun coincidentia',
'prevn'                 => '$1 precedentes',
'nextn'                 => '$1 sequentes',
'viewprevnext'          => 'Vider ($1) ($2) ($3).',
'showingresults'        => "Monstra de {{PLURAL:$1|'''1''' resultato|'''$1''' resultatos}} a partir de nº '''$2'''.",
'nonefound'             => '<strong>Nota</strong>: recercas frustrate frequentemente
es causate per le inclusion de vocabulos commun como "que" e "illo",
que non es includite in le indice, o per le specification de plure
terminos de recerca (solmente le paginas que contine tote le terminos
de recerca apparera in le resultato).',
'powersearch'           => 'Recercar',
'powersearchtext'       => '
Recerca in contextos :<br />
$1<br />
$2 Listar redireciones &nbsp; Recercar pro $3 $9',

# Preferences page
'preferences'              => 'Preferentias',
'mypreferences'            => 'Mi preferentias',
'prefsnologin'             => 'Session non aperte',
'prefsnologintext'         => 'Tu debe [[Special:Userlogin|aperir un session]]
pro definir tu preferentias.',
'prefsreset'               => 'Tu preferentias salvate previemente ha essite restaurate.',
'qbsettings'               => 'Configuration del barra de utensiles',
'qbsettings-none'          => 'Nulle',
'qbsettings-fixedleft'     => 'Fixe a sinistra',
'qbsettings-fixedright'    => 'Fixe a dextera',
'qbsettings-floatingleft'  => 'Flottante a sinistra',
'qbsettings-floatingright' => 'Flottante a dextera',
'changepassword'           => 'Cambiar contrasigno',
'skin'                     => 'Apparentia',
'math'                     => 'Exhibition de formulas',
'math_failure'             => 'Impossibile analysar',
'math_unknown_error'       => 'error incognite',
'math_unknown_function'    => 'function incognite',
'math_lexing_error'        => 'error lexic',
'math_syntax_error'        => 'error syntactic',
'prefs-rc'                 => 'Modificationes recente',
'saveprefs'                => 'Salvar preferentias',
'resetprefs'               => 'Restaurar preferentias',
'oldpassword'              => 'Contrasigno actual',
'newpassword'              => 'Nove contrasigno',
'retypenew'                => 'Confirmar nove contrasigno',
'textboxsize'              => 'Dimensiones del cassa de texto',
'rows'                     => 'Lineas',
'columns'                  => 'Columnas',
'searchresultshead'        => 'Configuration del resultatos de recerca',
'resultsperpage'           => 'Coincidentias per pagina',
'contextlines'             => 'Lineas per coincidentia',
'contextchars'             => 'Characteres de contexto per linea',
'recentchangescount'       => 'Quantitate de titulos in modificationes recente',
'savedprefs'               => 'Tu preferentias ha essite salvate.',
'timezonetext'             => 'Scribe le differentia de horas inter tu fuso horari
e illo del servitor (UTC).',
'localtime'                => 'Hora local',
'timezoneoffset'           => 'Differentia de fuso horari',

# User rights
'editinguser' => 'Modification de $1',

'grouppage-sysop'      => '{{ns:project}}:Administratores',
'grouppage-bureaucrat' => '{{ns:project}}:Bureaucrates',

# Recent changes
'nchanges'          => '$1 {{PLURAL:$1|modification|modificationes}}',
'recentchanges'     => 'Modificationes recente',
'recentchangestext' => 'Seque le plus recente modificationes a {{SITENAME}} in iste pagina.',
'rcnote'            => "Infra es le {{PLURAL:$1|ultime modification|'''$1''' ultime modificationes}} in le {{PLURAL:$2|ultime die|'''$2''' ultime dies}}, in $3.",
'rcnotefrom'        => 'infra es le modificationes a partir de <b>$2</b> (usque a <b>$1</b>).',
'rclistfrom'        => 'Monstrar nove modificationes a partir de $1',
'rcshowhideminor'   => '$1 modificationes minor',
'rcshowhideliu'     => '$1 usatores registrate',
'rcshowhideanons'   => '$1 usatores anonyme',
'rcshowhidemine'    => '$1 mi modificationes',
'rclinks'           => 'Monstrar le $1 ultime modificationes in le $2 ultime dies<br />$3',
'diff'              => 'diff',
'hist'              => 'prec',
'hide'              => 'occultar',
'show'              => 'monstrar',
'minoreditletter'   => 'M',

# Recent changes linked
'recentchangeslinked' => 'Modificationes correlate',

# Upload
'upload'            => 'Cargar file',
'uploadbtn'         => 'Cargar file',
'reupload'          => 'Recargar',
'reuploaddesc'      => 'Retornar al formulario de carga.',
'uploadnologin'     => 'Session non aperte',
'uploadnologintext' => 'Tu debe [[Special:Userlogin|aperir un session]]
pro poter cargar files.',
'uploaderror'       => 'Error de carga',
'uploadtext'        => "'''STOP!''' Ante cargar files al servitor,
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
'uploadlog'         => 'registro de cargas',
'uploadlogpage'     => 'Registro_de_cargas',
'uploadlogpagetext' => 'Infra es un lista del plus recente cargas de files.
Tote le tempores monstrate es in le fuso horari del servitor (UCT).',
'filename'          => 'Nomine del file',
'filedesc'          => 'Description',
'filestatus'        => 'Stato de copyright:',
'filesource'        => 'Fonte:',
'uploadedfiles'     => 'Files cargate',
'badfilename'       => 'Le nomine del imagine esseva cambiate a "$1".',
'successfulupload'  => 'Carga complete',
'uploadwarning'     => 'Advertimento de carga',
'savefile'          => 'Salvar file',
'uploadedimage'     => '"[[$1]]" cargate',

# Special:Imagelist
'imagelist' => 'Lista de imagines',

# Image description page
'filehist-user'  => 'Usator',
'imagelinks'     => 'Ligamines',
'linkstoimage'   => 'Le paginas sequente se liga a iste imagine:',
'nolinkstoimage' => 'Necun pagina se liga a iste imagine.',

# Unused templates
'unusedtemplates' => 'Patronos non usate',

# Random page
'randompage' => 'Pagina aleatori',

# Statistics
'statistics'    => 'Statisticas',
'sitestats'     => 'Statisticas de accesso',
'userstats'     => 'Statisticas de usator',
'sitestatstext' => "Le base de datos contine un total de {{PLURAL:\$1|'''1''' pagina|'''\$1''' paginas}}.
Iste numero include paginas de \"discussion\", paginas super {{SITENAME}}, paginas de \"residuo\"
minime, paginas de redirection, e alteres que probabilemente non se qualifica como articulos.
A parte de istes, il ha {{PLURAL:\$2|'''1''' pagina|'''\$2''' paginas}} que probabilemente es
articulos legitime.

Il habeva un total de '''\$3''' {{PLURAL:\$3|visita a paginas|visitas a paginas}}, e '''\$4''' {{PLURAL:\$4|modification|modificationes}} de paginas
desde le actualisation de {{SITENAME}}.
Isto representa un media de '''\$5''' modificationes per pagina, e '''\$6''' visitas per modification.",
'userstatstext' => "Il ha {{PLURAL:$1|'''1''' [[{{ns:Special}}:Listusers|usator]]|'''$1''' [[{{ns:Special}}:Listusers|usatores]]}} registrate, del quales '''$2''' (o '''$4%''') ha derectos de $5.",

'disambiguations'     => 'Paginas de disambiguation',
'disambiguationspage' => '{{ns:project}}:Ligamines_a_paginas_de_disambiguation',

'doubleredirects'     => 'Redirectiones duple',
'doubleredirectstext' => '<b>Attention:</b> Iste lista pote continer items false.
Illo generalmente significa que il ha texto additional con ligamines sub le prime #REDIRECT.<br />
Cata linea contine ligamines al prime e secunde redirection, assi como le prime linea del
secunde texto de redirection, generalmente exhibiente le articulo scopo "real",
al qual le prime redirection deberea referer se.',

'brokenredirects'     => 'Redirectiones van',
'brokenredirectstext' => 'Le redirectiones sequente se liga a articulos inexistente.',

'withoutinterwiki' => 'Paginas sin ligamines de lingua',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories'             => '$1 {{PLURAL:$1|categoria|categorias}}',
'nlinks'                  => '$1 {{PLURAL:$1|ligamine|ligamines}}',
'nmembers'                => '$1 {{PLURAL:$1|membro|membros}}',
'nviews'                  => '$1 {{PLURAL:$1|visita|visitas}}',
'lonelypages'             => 'Paginas orphanas',
'uncategorizedpages'      => 'Paginas non classificate',
'uncategorizedcategories' => 'Categorias non classificate',
'uncategorizedtemplates'  => 'Patronos non classificate',
'unusedcategories'        => 'Categorias non usate',
'unusedimages'            => 'Imagines non usate',
'popularpages'            => 'Paginas popular',
'wantedcategories'        => 'Categorias plus demandate',
'wantedpages'             => 'Paginas plus demandate',
'shortpages'              => 'Paginas curte',
'longpages'               => 'Paginas longe',
'listusers'               => 'Lista de usatores',
'specialpages'            => 'Paginas special',
'spheading'               => 'Paginas special',
'newpages'                => 'Nove paginas',
'ancientpages'            => 'Paginas le plus ancian',
'move'                    => 'Mover',
'movethispage'            => 'Mover iste pagina',
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

# Special:Allpages
'allpages'       => 'Tote le paginas',
'alphaindexline' => '$1 a $2',
'allpagesfrom'   => 'Monstrar le paginas initiante a:',
'allarticles'    => 'Tote le paginas',
'allpagesprev'   => 'Previe',
'allpagesnext'   => 'Sequente',
'allpagessubmit' => 'Ir',
'allpagesprefix' => 'Monstrar le paginas con prefixo:',

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
'emailsend'       => 'Inviar',
'emailsent'       => 'E-mail inviate',
'emailsenttext'   => 'Tu message de e-mail ha essite inviate.',

# Watchlist
'watchlist'        => 'Paginas sub observation',
'mywatchlist'      => 'Paginas sub observation',
'nowatchlist'      => 'Tu non ha paginas sub observation.',
'watchnologin'     => 'Session non aperte',
'watchnologintext' => 'Tu debe [[Special:Userlogin|aperir un session]]
pro modificar tu lista de paginas sub observation.',
'addedwatch'       => 'Ponite sub observation',
'addedwatchtext'   => "Le pagina \"<nowiki>\$1</nowiki>\" es ora in tu [[Special:Watchlist||lista de paginas sub observation]].
Modificationes futur a iste pagina e su pagina de discussion associate essera listate la,
e le pagina apparera '''in nigretto''' in le [[Special:Recentchanges|lista de modificationes recente]] pro
facilitar su identification.

Si tu vole cessar le obsevation de iste pagina posteriormente, clicca \"Cancellar observation\" in le barra de navigation.",
'removedwatch'     => 'Observation cancellate',
'removedwatchtext' => 'Le pagina "<nowiki>$1</nowiki>" non es plus sub observation.',
'watchthispage'    => 'Poner iste pagina sub observation',
'unwatchthispage'  => 'Cancellar observation',
'notanarticle'     => 'Non es un articulo',

# Delete/protect/revert
'deletepage'        => 'Eliminar pagina',
'confirm'           => 'Confirmar',
'confirmdeletetext' => 'Tu es a puncto de eliminar permanentemente un pagina
o imagine del base de datos, conjunctemente con tote su chronologia de versiones.
Per favor, confirma que, si tu intende facer lo, tu comprende le consequentias,
e tu lo face de accordo con [[{{MediaWiki:Policy-url}}]].',
'actioncomplete'    => 'Action complite',
'deletedtext'       => '"<nowiki>$1</nowiki>" ha essite eliminate.
Vide $2 pro un registro de eliminationes recente.',
'deletedarticle'    => '"$1" eliminate',
'dellogpage'        => 'Registro_de_eliminationes',
'dellogpagetext'    => 'Infra es un lista del plus recente eliminationes.
Tote le horas es in le fuso horari del servitor (UTC).',
'deletionlog'       => 'registro de eliminationes',
'reverted'          => 'Revertite a revision anterior',
'deletecomment'     => 'Motivo del elimination',
'rollback'          => 'Revocar modificationes',
'rollbacklink'      => 'revocar',
'cantrollback'      => 'Impossibile revocar le modification; le ultime contribuente es le unic autor de iste articulo.',
'revertpage'        => 'Revertite al ultime modification per $1', # Additional available: $3: revid of the revision reverted to, $4: timestamp of the revision reverted to, $5: revid of the revision reverted from, $6: timestamp of the revision reverted from

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
'undeletedarticle'  => '"$1" restaurate',
'undeletedfiles'    => '$1 {{PLURAL:$1|archivo|archivos}} restaurate',

# Namespace form on various pages
'namespace'      => 'Spatio de nomine:',
'blanknamespace' => '(Principal)',

# Contributions
'contributions' => 'Contributiones de usator',
'mycontris'     => 'Mi contributiones',
'contribsub2'   => 'Pro $1 ($2)',
'nocontribs'    => 'Necun modification ha essite trovate secundo iste criterios.',
'uctop'         => ' (alto)',

# What links here
'whatlinkshere'       => 'Referentias a iste pagina',
'whatlinkshere-page'  => 'Pagina:',
'linklistsub'         => '(Lista de ligamines)',
'linkshere'           => "Le paginas sequente se liga a '''[[:$1]]''':",
'nolinkshere'         => "Necun pagina se liga a '''[[:$1]]'''.",
'isredirect'          => 'pagina de redirection',
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
'ipbreason'          => 'Motivo',
'ipbsubmit'          => 'Blocar iste adresse',
'badipaddress'       => 'Adresse IP mal formate.',
'blockipsuccesssub'  => 'Blocage con successo',
'blockipsuccesstext' => 'Le adresse IP "$1" ha essite blocate.
<br />Vide [[Special:Ipblocklist|Lista de IPs blocate]] pro revider le blocages.',
'unblockip'          => 'Disblocar adresse IP',
'unblockiptext'      => 'Usa le formulario infra pro restaurar le accesso de scriptura
a un adresse IP blocate previemente.',
'ipusubmit'          => 'Disbloca iste adresse',
'ipblocklist'        => 'Lista de adresses IP blocate',
'blocklistline'      => '$1, $2 ha blockate $3 ($4)',
'blocklink'          => 'blocar',
'unblocklink'        => 'disblocar',
'contribslink'       => 'contributiones',

# Developer tools
'lockdb'              => 'Blocar base de datos',
'unlockdb'            => 'Disblocar base de datos',
'lockdbtext'          => 'Le blocage del base de datos suspendera le capacitate de tote
le usatores de modificar paginas, modificar lor preferentias e listas de paginas sub observation,
e altere actiones que require modificationes in le base de datos.
Per favor confirma que iste es tu intention, e que tu disblocara le
base de datos immediatemente post completar tu mantenentia.',
'unlockdbtext'        => 'Le disblocage del base de datos restaurara le capacitate de tote
le usatores de modificar paginas, modificar lor preferentias e listas de paginas sub observation,
e altere actiones que require modificationes in le base de datos.
Per favor confirma que iste es tu intention.',
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
'move-page-legend' => 'Mover pagina',
'movepagetext'     => "Per medio del formulario infra tu pote renominar un pagina,
movente tote su chronologia al nove nomine.
Le titulo anterior devenira un pagina de redirection al nove titulo.
Le ligamines al pagina anterior non essera modificate;
assecura te de verificar le apparition de redirectiones duple o van.
Tu es responsabile pro assecurar que le ligamines continua a punctar a ubi illos deberea.

Nota que le pagina '''non''' essera movite si ja existe un pagina
sub le nove titulo, salvo si illo es vacue o un redirection e non
ha un chronologia de modificationes passate. Isto significa que tu
pote renominar un pagina a su titulo original si tu lo ha renominate
erroneemente, e que tu non pote superscriber un pagina existente.

<b>ADVERTIMENTO!</b>
Isto pote esser un cambio drastic e inexpectate pro un pagina popular;
per favor assecura te que tu comprende le consequentias de isto
ante proceder.",
'movepagetalktext' => "Le pagina de discussion associate, si existe, essera automaticamente movite conjunctemente con illo '''a minus que''':
*Tu move le pagina trans contextos,
*Un pagina de discussion non vacue ja existe sub le nove nomine, o
*Tu dismarca le quadrato infra.

Il tal casos, tu debera mover o fusionar le pagina manualmente si desirate.",
'movearticle'      => 'Mover pagina',
'movenologin'      => 'Session non aperte',
'movenologintext'  => 'Tu debe esser un usator registrate e [[Special:Userlogin|aperir un session]]
pro mover un pagina.',
'newtitle'         => 'Al nove titulo',
'movepagebtn'      => 'Mover pagina',
'pagemovedsub'     => 'Pagina movite con successo',
'articleexists'    => 'Un pagina con iste nomine ja existe, o le
nomine selectionate non es valide.
Per favor selectiona un altere nomine.',
'talkexists'       => 'Le pagina mesme ha essite movite con successo, mais le
pagina de discussion associate non ha essite movite proque ja existe un sub le
nove titulo. Per favor fusiona los manualmente.',
'movedto'          => 'movite a',
'movetalk'         => 'Mover le pagina de "discussion" tamben, si applicabile.',
'talkpagemoved'    => 'Le pagina de discussion correspondente tamben ha essite movite.',
'talkpagenotmoved' => 'Le pagina de discussion correspondente <strong>non</strong> ha essite movite.',
'1movedto2'        => '[[$1]] movite a [[$2]]',
'1movedto2_redir'  => '[[$1]] movite a [[$2]] trans redirection',
'movelogpage'      => 'Registro de movimentos',

# Export
'export' => 'Exportar paginas',

# Namespace 8 related
'allmessages'     => 'Messages del systema',
'allmessagesname' => 'Nomine',

# Thumbnails
'thumbnail-more' => 'Ampliar',

# Special:Import
'import' => 'Importar paginas',

# Import log
'importlogpage' => 'Registro de importationes',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Mi pagina de usator',
'tooltip-pt-mytalk'               => 'Mi pagina de discussion',
'tooltip-pt-preferences'          => 'Mi preferentias',
'tooltip-pt-mycontris'            => 'Lista de mi contributiones',
'tooltip-search'                  => 'Recercar {{SITENAME}}',
'tooltip-p-logo'                  => 'Frontispicio',
'tooltip-n-mainpage'              => 'Visitar le Frontispicio',
'tooltip-n-portal'                => 'A proposito del projecto, que vos pote facer, ubi trovar cosas',
'tooltip-n-sitesupport'           => 'Sustene nos',
'tooltip-save'                    => 'Salvar tu modificationes',
'tooltip-preview'                 => 'Previsualisar tu cambios, per favor usa isto ante salvar!',
'tooltip-compareselectedversions' => 'Vide le differentias inter le duo versiones selectionate de iste pagina.',
'tooltip-watch'                   => 'Adder iste pagina a tu lista de paginas sub observation',

# Math options
'mw_math_png'    => 'Sempre produce PNG',
'mw_math_simple' => 'HTML si multo simple, alteremente PNG',
'mw_math_html'   => 'HTML si possibile, alteremente PNG',
'mw_math_source' => 'Lassa lo como TeX (pro navigatores in modo texto)',
'mw_math_modern' => 'Recommendate pro navigatores moderne',
'mw_math_mathml' => 'MathML',

# Special:Newimages
'imagelisttext' => "Infra es un lista de '''$1''' {{PLURAL:$1|imagine|imagines}} ordinate $2.",
'ilsubmit'      => 'Recercar',
'bydate'        => 'per data',

# 'all' in various places, this might be different for inflected languages
'namespacesall' => 'tote',
'monthsall'     => 'tote',

);

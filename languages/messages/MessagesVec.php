<?php
/** Vèneto (Vèneto)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author BrokenArrow
 * @author Candalua
 * @author Nick1915
 * @author Omnipaedista
 * @author Urhixidur
 * @author לערי ריינהארט
 */

$fallback = 'it';

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Speciale',
	NS_TALK             => 'Discussion',
	NS_USER             => 'Utente',
	NS_USER_TALK        => 'Discussion_utente',
	NS_PROJECT_TALK     => 'Discussion_$1',
	NS_FILE             => 'File',
	NS_FILE_TALK        => 'Discussion_file',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Discussion_MediaWiki',
	NS_TEMPLATE         => 'Template',
	NS_TEMPLATE_TALK    => 'Discussion_template',
	NS_HELP             => 'Aiuto',
	NS_HELP_TALK        => 'Discussion_aiuto',
	NS_CATEGORY         => 'Categoria',
	NS_CATEGORY_TALK    => 'Discussion_categoria',
);

$namespaceAliases = array(
	'Imagine' => NS_FILE,
	'Discussion_imagine' => NS_FILE_TALK,
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'DópiRimandi' ),
	'BrokenRedirects'           => array( 'RimandiSbaglià' ),
	'Disambiguations'           => array( 'Disanbiguassion' ),
	'Userlogin'                 => array( 'Entra' ),
	'Userlogout'                => array( 'VàFora' ),
	'CreateAccount'             => array( 'CreaAccount' ),
	'Preferences'               => array( 'Preferense' ),
	'Watchlist'                 => array( 'TegnùiDeOcio' ),
	'Recentchanges'             => array( 'ÙltimiCanbiamenti' ),
	'Upload'                    => array( 'Carga' ),
	'Listfiles'                 => array( 'Imagini' ),
	'Newimages'                 => array( 'ImaginiReçenti' ),
	'Listusers'                 => array( 'Utenti' ),
	'Listgrouprights'           => array( 'ListaDiritiDeGrupo' ),
	'Statistics'                => array( 'Statìsteghe' ),
	'Randompage'                => array( 'PàxenaACaso' ),
	'Lonelypages'               => array( 'PàxenaÒrfana' ),
	'Uncategorizedpages'        => array( 'PàxeneSensaCategorie' ),
	'Uncategorizedcategories'   => array( 'CategorieSensaCategorie' ),
	'Uncategorizedimages'       => array( 'ImaginiSensaCategorie' ),
	'Uncategorizedtemplates'    => array( 'TemplateMiaCategorizà' ),
	'Unusedcategories'          => array( 'CategorieMiaDoparà' ),
	'Unusedimages'              => array( 'ImaginiMiaDoparà' ),
	'Wantedpages'               => array( 'PàxeneRichieste' ),
	'Wantedcategories'          => array( 'CategorieRichieste' ),
	'Wantedfiles'               => array( 'FileRichiesti' ),
	'Wantedtemplates'           => array( 'TemplateRichiesti' ),
	'Mostlinked'                => array( 'PàxenePiassèRiciamà' ),
	'Mostlinkedcategories'      => array( 'CategoriePiassèRiciamà' ),
	'Mostlinkedtemplates'       => array( 'TemplatePiassèDoparà' ),
	'Mostimages'                => array( 'ImaginiPiassèRiciamà' ),
	'Mostcategories'            => array( 'PàxeneConPiassèCategorie' ),
	'Mostrevisions'             => array( 'PàxeneConPiassèRevision' ),
	'Fewestrevisions'           => array( 'PàxeneConMancoRevision' ),
	'Shortpages'                => array( 'PàxenePiCurte' ),
	'Longpages'                 => array( 'PàxenePiLonghe' ),
	'Newpages'                  => array( 'PàxenePiNove' ),
	'Ancientpages'              => array( 'PàxeneMancoNove' ),
	'Deadendpages'              => array( 'PàxeneSensaUscita' ),
	'Protectedpages'            => array( 'PàxeneProtete' ),
	'Protectedtitles'           => array( 'TitoliProteti' ),
	'Allpages'                  => array( 'TuteLePàxene' ),
	'Prefixindex'               => array( 'Prefissi' ),
	'Ipblocklist'               => array( 'IPBlocài' ),
	'Specialpages'              => array( 'PàxeneSpeciali' ),
	'Contributions'             => array( 'Contributi' ),
	'Emailuser'                 => array( 'MandaEMail' ),
	'Confirmemail'              => array( 'ConfermaEMail' ),
	'Whatlinkshere'             => array( 'PuntaQua' ),
	'Recentchangeslinked'       => array( 'CanbiamentiLigà' ),
	'Movepage'                  => array( 'Sposta' ),
	'Blockme'                   => array( 'BlocaProxy' ),
	'Booksources'               => array( 'SercaISBN' ),
	'Categories'                => array( 'Categorie' ),
	'Export'                    => array( 'Esporta' ),
	'Allmessages'               => array( 'Messagi' ),
	'Log'                       => array( 'Registri' ),
	'Blockip'                   => array( 'Bloca' ),
	'Undelete'                  => array( 'Ripristina' ),
	'Import'                    => array( 'Inporta' ),
	'Lockdb'                    => array( 'BlocaDB' ),
	'Unlockdb'                  => array( 'DesblocaDB' ),
	'Userrights'                => array( 'ParmessiUtente' ),
	'MIMEsearch'                => array( 'SercaMIME' ),
	'FileDuplicateSearch'       => array( 'SercaDopioniDeiFile' ),
	'Unwatchedpages'            => array( 'PàxeneMiaTegnùDeOcio' ),
	'Listredirects'             => array( 'Rimandi' ),
	'Revisiondelete'            => array( 'ScancelaRevision' ),
	'Unusedtemplates'           => array( 'TemplateMiaDoparà' ),
	'Randomredirect'            => array( 'RImandoCasuale' ),
	'Mypage'                    => array( 'LaMePàxenaUtente' ),
	'Mytalk'                    => array( 'LeMeDiscussion' ),
	'Mycontributions'           => array( 'IMeContributi' ),
	'Listadmins'                => array( 'Aministradori' ),
	'Listbots'                  => array( 'ListaDeiBot' ),
	'Popularpages'              => array( 'PàxenePiassèVisità' ),
	'Search'                    => array( 'Serca' ),
	'Resetpass'                 => array( 'ReinpostaPassword' ),
	'Withoutinterwiki'          => array( 'PàxeneSensaInterwiki' ),
	'MergeHistory'              => array( 'FondiCronologia' ),
	'Filepath'                  => array( 'PercorsoFile' ),
	'Invalidateemail'           => array( 'InvalidaEMail' ),
	'Blankpage'                 => array( 'PàxenaVoda' ),
	'LinkSearch'                => array( 'SercaLigamenti' ),
	'DeletedContributions'      => array( 'ContributiScancelà' ),
	'Tags'                      => array( 'Tag' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Sotołinea i cołegamenti:',
'tog-highlightbroken'         => "Evidensia i cołegamenti che i punta a voxe 'ncora da scrìvar",
'tog-justify'                 => 'Paragrafo: giustificà',
'tog-hideminor'               => 'Scondi łe modifighe picenine n\'te ła paxena "Ultime modifighe"',
'tog-hidepatrolled'           => 'Scondi le modìfeghe verificà tra le ultime modìfeghe',
'tog-newpageshidepatrolled'   => "Scondi le pàxene verificà da l'elenco de le pàxene piassè nove",
'tog-extendwatchlist'         => "Espandi i osservai speciałi mostrando tute łe modifighe, no solo l'ultima",
'tog-usenewrc'                => 'Ultime modifiche avanzade (ghe vol JavaScript)',
'tog-numberheadings'          => 'Auto-numerazion dei titoli de paragrafo',
'tog-showtoolbar'             => 'Mostra la barra dei strumenti de modifica (ghe vol JavaScript)',
'tog-editondblclick'          => 'Dopio clic par modificar ła voxe (ghe vol JavaScript)',
'tog-editsection'             => 'Modifega de łe sezion tramite el cołegamento [modifica]',
'tog-editsectiononrightclick' => 'Modifega de łe sezion tramite clic destro sul titoło (ghe vol JavaScript)',
'tog-showtoc'                 => "Mostra l'indexe par łe pàxene con pì de 3 sezion",
'tog-rememberpassword'        => 'Ricorda la password (no limitar a una session - ghe vol i cookies)',
'tog-editwidth'               => 'Slarga la casèla de modifica fin a inpenir tuto el schermo',
'tog-watchcreations'          => 'Xonta łe pàxene creàe a i osservati speciałi',
'tog-watchdefault'            => 'Xonta łe pàxene modifegàe a i osservati speciałi',
'tog-watchmoves'              => 'Xonta łe pàxene spostà ai oservai speciałi',
'tog-watchdeletion'           => 'Xonta łe pàxene scancełà a i oservai speciałi',
'tog-minordefault'            => 'Indica ogni modifica come picenina (solo come predefinìo)',
'tog-previewontop'            => "Mostra l'anteprima de sora la casèla de modifica",
'tog-previewonfirst'          => "Mostra l'anteprima par la prima modifica",
'tog-nocache'                 => "Disativa ła ''cache'' par łe pàxene",
'tog-enotifwatchlistpages'    => 'Segnàleme via e-mail le modifiche a le pagine osservà',
'tog-enotifusertalkpages'     => 'Segnàleme via e-mail łe modifighe a ła me pàxena de discusion',
'tog-enotifminoredits'        => 'Segnàleme via e-mail anca le modifiche picenine',
'tog-enotifrevealaddr'        => 'Rivela el me indirizo e-mail nei messagi de aviso',
'tog-shownumberswatching'     => "Mostra el nùmaro de utenti che tien d'ocio la pagina",
'tog-fancysig'                => 'Tràta la firma come la fusse un testo wiki (sensa el colegamento automatico)',
'tog-externaleditor'          => 'Dòpara par default un editor de testi esterno (solo par i esperti, ghe vole dele inpostassion speciali sul to computer)',
'tog-externaldiff'            => 'Dòpara par default un programa de diff esterno (solo par i esperti, ghe vole dele inpostassion speciali sul to computer)',
'tog-showjumplinks'           => "Ativa i cołegamenti acesibiłi 'và a'",
'tog-uselivepreview'          => "Ativa la funzion ''Live preview'' (ghe vol JavaScript; sperimental)",
'tog-forceeditsummary'        => "Domanda conferma se l'ogeto de la modifica el xe vodo",
'tog-watchlisthideown'        => 'Scondi łe me modifeghe ne i osservati speciałi',
'tog-watchlisthidebots'       => 'Scondi le modifighe de i bot ne i oservati speciałi',
'tog-watchlisthideminor'      => 'Scondi le modifiche picenine nei osservati speciali',
'tog-watchlisthideliu'        => "Scondi le modifiche dei utenti registrà tra le pàxene tegnùe d'ocio",
'tog-watchlisthideanons'      => "Scondi le modifiche dei utenti anonimi tra le pàxene tegnùe d'ocio",
'tog-watchlisthidepatrolled'  => 'Scondi le modìfeghe verificà ne i osservati speciali',
'tog-ccmeonemails'            => 'Màndeme na copia dei messagi spedìi ai altri utenti',
'tog-diffonly'                => 'No stà mostrar el contenuto de la pagina dopo el confronto tra version',
'tog-showhiddencats'          => 'Mostra łe categorie sconte',
'tog-norollbackdiff'          => 'No stà far védar el riepilogo de le difarense dopo ver fato un ripristino',

'underline-always'  => 'Senpre',
'underline-never'   => 'Mai',
'underline-default' => 'Mantien łe inpostasion de el browser',

# Dates
'sunday'        => 'Domenega',
'monday'        => 'Luni',
'tuesday'       => 'Marti',
'wednesday'     => 'Mèrcoli',
'thursday'      => 'Xòbia',
'friday'        => 'Vènere',
'saturday'      => 'Sabo',
'sun'           => 'dom',
'mon'           => 'lun',
'tue'           => 'mar',
'wed'           => 'mer',
'thu'           => 'gio',
'fri'           => 'ven',
'sat'           => 'sab',
'january'       => 'Zenaro',
'february'      => 'Febraro',
'march'         => 'Marso',
'april'         => 'Avril',
'may_long'      => 'Majo',
'june'          => 'Giugno',
'july'          => 'Lujo',
'august'        => 'Agosto',
'september'     => 'Setenbre',
'october'       => 'Otobre',
'november'      => 'Novenbre',
'december'      => 'Diçenbre',
'january-gen'   => 'zenaro',
'february-gen'  => 'febraro',
'march-gen'     => 'marso',
'april-gen'     => 'avril',
'may-gen'       => 'majo',
'june-gen'      => 'giugno',
'july-gen'      => 'lujo',
'august-gen'    => 'agosto',
'september-gen' => 'setenbre',
'october-gen'   => 'otobre',
'november-gen'  => 'novenbre',
'december-gen'  => 'diçenbre',
'jan'           => 'Zen',
'feb'           => 'Feb',
'mar'           => 'Mar',
'apr'           => 'Avr',
'may'           => 'Mag',
'jun'           => 'Giu',
'jul'           => 'Luj',
'aug'           => 'Ago',
'sep'           => 'Set',
'oct'           => 'Oto',
'nov'           => 'Nov',
'dec'           => 'Diç',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Categoria|Categorie}}',
'category_header'                => 'Pagine in te ła categoria "$1"',
'subcategories'                  => 'Sotocategorie',
'category-media-header'          => 'File ne la categoria "$1"',
'category-empty'                 => "''Al momento la categoria no la contien nissuna pagina né file multimediai.''",
'hidden-categories'              => '{{PLURAL:$1|Categoria sconta|Categorie sconte}}',
'hidden-category-category'       => 'Categorie sconte',
'category-subcat-count'          => "{{PLURAL:$2|Sta categoria la contien un'unica sotocategoria, indicà de seguito.|Sta categoria la contien {{PLURAL:$1|la sotocategoria indicà|le $1 sotocategorie indicà}} de seguito, su un totale de $2.}}",
'category-subcat-count-limited'  => 'Sta categoria la contien {{PLURAL:$1|una sotocategoria, indicà|$1 sotocategorie, indicà}} de seguito.',
'category-article-count'         => "{{PLURAL:$2|Sta categoria la contien un'unica pagina, indicà de seguito.|Sta categoria la contien {{PLURAL:$1|la pagina indicà|le $1 pagine indicà}} de seguito, su un totale de $2.}}",
'category-article-count-limited' => 'Sta categoria la contien {{PLURAL:$1|la pagina indicà|le $1 pagine indicà}} de seguito.',
'category-file-count'            => '{{PLURAL:$2|Sta categoria la contien un solo file, indicà de seguito.|Sta categoria la contien {{PLURAL:$1|un file, indicà|$1 file, indicà}} de seguito, su un total de $2.}}',
'category-file-count-limited'    => 'Sta categoria la contien {{PLURAL:$1|el file indicà|i $1 file indicà}} de seguito.',
'listingcontinuesabbrev'         => 'cont.',

'mainpagetext'      => "<big>'''MediaWiki xè stà instałà con sucesso.'''</big>",
'mainpagedocfooter' => "Varda ła [http://meta.wikimedia.org/wiki/Aiuto:Sommario Guida utente] (in tałian) par verghe piassè informasion su l'uso de sto software wiki.

== Par scuminsiar ==
I seguenti cołegamenti i xe en lengua inglese:

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Inpostasion de configurasion]
* [http://www.mediawiki.org/wiki/Manual:FAQ Domande frequenti su MediaWiki]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Mailing list anunçi MediaWiki]",

'about'         => 'Se parla de',
'article'       => 'Vóxe',
'newwindow'     => '(se verze in te na finestra nova)',
'cancel'        => 'Anuła',
'moredotdotdot' => 'Altro...',
'mypage'        => 'La me pàxena',
'mytalk'        => 'le me discussión',
'anontalk'      => 'Discussion par sto IP',
'navigation'    => 'Navigassión',
'and'           => '&#32;e',

# Cologne Blue skin
'qbfind'         => 'Cata fora',
'qbbrowse'       => 'Sfója',
'qbedit'         => 'Modifega',
'qbpageoptions'  => 'Opzion pàxena',
'qbpageinfo'     => 'Informazion su ła pàxena',
'qbmyoptions'    => 'Le me opzion',
'qbspecialpages' => 'Pàxene speciałi',
'faq'            => 'Domande frequenti',
'faqpage'        => 'Project:Domande frequenti',

# Metadata in edit box
'metadata_help' => 'Metadati:',

'errorpagetitle'    => 'Erór',
'returnto'          => 'Torna a $1.',
'tagline'           => 'Da {{SITENAME}}',
'help'              => 'Ajuto',
'search'            => 'Serca',
'searchbutton'      => 'Serca',
'go'                => 'Và',
'searcharticle'     => 'Và',
'history'           => 'Versión precedenti',
'history_short'     => 'Cronołogia',
'updatedmarker'     => 'modificà da la me ultima visita',
'info_short'        => 'Informassion',
'printableversion'  => 'Version de stanpa',
'permalink'         => 'Colegamento permanente',
'print'             => 'Stanpa',
'edit'              => 'Modìfega',
'create'            => 'Crea',
'editthispage'      => 'Modìfega sta pagina',
'create-this-page'  => 'Crea sta pagina',
'delete'            => 'Scanceła',
'deletethispage'    => 'Scanceła pàxena',
'undelete_short'    => 'Recupera {{PLURAL:$1|una revision|$1 revision}}',
'protect'           => 'Protègi',
'protect_change'    => 'cànbia',
'protectthispage'   => 'Protegi sta pàxena',
'unprotect'         => 'sbloca',
'unprotectthispage' => 'Cava protession',
'newpage'           => 'Pàxena nova',
'talkpage'          => 'Discussion',
'talkpagelinktext'  => 'discussion',
'specialpage'       => 'Pàxena speciałe',
'personaltools'     => 'Strumenti personali',
'postcomment'       => 'Sezion nova',
'articlepage'       => 'Varda la vóxe',
'talk'              => 'Discussion',
'views'             => 'Vìsite',
'toolbox'           => 'Strumenti',
'userpage'          => 'Varda pàxena utente',
'projectpage'       => 'Varda ła pàxena de servizio',
'imagepage'         => 'Varda la pagina del file',
'mediawikipage'     => 'Mostra el messagio',
'templatepage'      => 'Mostra el template',
'viewhelppage'      => 'Mostra la pagina de ajuto',
'categorypage'      => 'Mostra la categoria',
'viewtalkpage'      => 'Varda ła pàxena de discussion',
'otherlanguages'    => 'Altre łengoe',
'redirectedfrom'    => '(Rimando da $1)',
'redirectpagesub'   => 'Pàxena de reindirizamento',
'lastmodifiedat'    => 'Ùltima modìfega $2, $1.',
'viewcount'         => 'Sta pàxena la xè stà leta {{PLURAL:$1|na olta|$1 olte}}.',
'protectedpage'     => 'Pàxena proteta',
'jumpto'            => 'Và a:',
'jumptonavigation'  => 'navigassion',
'jumptosearch'      => 'serca',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Se parla de {{SITENAME}}',
'aboutpage'            => 'Project:Se parla de',
'copyright'            => 'Contenuto disponibile soto $1.',
'copyrightpagename'    => 'El copyright su {{SITENAME}}',
'copyrightpage'        => '{{ns:project}}:Copyright',
'currentevents'        => 'Atuałità',
'currentevents-url'    => 'Project:Atuałità',
'disclaimers'          => 'Avertense',
'disclaimerpage'       => 'Project:Avertense xenerali',
'edithelp'             => 'Guida',
'edithelppage'         => 'Help:Come scrìvar un articolo',
'helppage'             => 'Help:Ajuto',
'mainpage'             => 'Pàxena prinsipałe',
'mainpage-description' => 'Pàxena prinçipałe',
'policy-url'           => 'Project:Policy',
'portal'               => 'Portal comunità',
'portal-url'           => 'Project:Portałe Comunità',
'privacy'              => 'Informassion su la privacy',
'privacypage'          => 'Project:Informassion su la privacy',

'badaccess'        => 'Eròr ne i permessi',
'badaccess-group0' => "No te ghè i permessi necessari par eseguir l'azion richiesta.",
'badaccess-groups' => 'La funzion richiesta la xe riservà ai utenti che fa parte {{PLURAL:$2|del grupo|de uno dei seguenti grupi}}: $1.',

'versionrequired'     => 'Version $1 de MediaWiki richiesta',
'versionrequiredtext' => 'Par doparar sta pagina a ghe vole la version $1 del software MediaWiki. Varda la [[Special:Version|pagina de la version]].',

'ok'                      => 'OK',
'retrievedfrom'           => 'Cavà fora da "$1"',
'youhavenewmessages'      => 'Te ghè $1 ($2).',
'newmessageslink'         => 'messagi novi',
'newmessagesdifflink'     => 'difarensa con la revision precedente',
'youhavenewmessagesmulti' => 'Te ghè novi messagi su $1',
'editsection'             => 'modìfega',
'editold'                 => 'modìfega',
'viewsourceold'           => 'mostra sorgente',
'editlink'                => 'modìfega',
'viewsourcelink'          => 'varda el testo',
'editsectionhint'         => 'Modìfega sezión: $1',
'toc'                     => 'Indice',
'showtoc'                 => 'mostra',
'hidetoc'                 => 'scondi',
'thisisdeleted'           => 'Varda o ripristina $1?',
'viewdeleted'             => 'Vuto védar $1?',
'restorelink'             => '{{PLURAL:$1|una modifica scancelà|$1 modifiche scancelà}}',
'feedlinks'               => 'Feed:',
'feed-invalid'            => 'Modałità de sotoscrission del feed mìa vałida.',
'feed-unavailable'        => 'No ghe xe feed de syndicaton disponibili',
'site-rss-feed'           => 'Feed RSS de $1',
'site-atom-feed'          => 'Feed Atom de $1',
'page-rss-feed'           => 'Feed RSS par "$1"',
'page-atom-feed'          => 'Feed Atom par "$1"',
'red-link-title'          => '$1 (la pàxena no la esiste)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Vóxe',
'nstab-user'      => 'Utente',
'nstab-media'     => 'File multimedial',
'nstab-special'   => 'Pàxena speciale',
'nstab-project'   => 'Pagina de servissio',
'nstab-image'     => 'File',
'nstab-mediawiki' => 'Messagio',
'nstab-template'  => 'Template',
'nstab-help'      => 'Ajuto',
'nstab-category'  => 'Categoria',

# Main script and global functions
'nosuchaction'      => 'Operazion mìa riconossùa',
'nosuchactiontext'  => "L'azion indicà in tel'URL no la xe valida. Te podaressi ver sbalià a digitar l'URL o ver strucà un colegamento mia justo. Opure podarìa anca tratarse de un bug in {{SITENAME}}.",
'nosuchspecialpage' => 'No xè disponibiłe nissuna pàxena speciałe co sto nome',
'nospecialpagetext' => "<big>'''Te ghè richiesto na pagina speciale mìa esistente.'''</big>

Te podi catar na lista de le pagine speciali esistenti in [[Special:SpecialPages|{{int:specialpages}}]].",

# General errors
'error'                => 'Erór',
'databaseerror'        => 'Eror del database',
'dberrortext'          => 'Eror de sintassi ne la richiesta inoltrà al database.
L\'ultima richiesta inoltrà al database l\'è stà:
<blockquote><tt>$1</tt></blockquote>
da la funsion "<tt>$2</tt>".
MySQL gà restituìo un eror "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Se gà verificà un eror de sintassi ne la richiesta al database.
L\'ultima richiesta al database l\'è stà:
"$1"
da la funsion "$2".
MySQL gà restituìo l\'eror "$3: $4".',
'laggedslavemode'      => 'Ocio: la pàxena la podarìa no contegner mìa i ultimi agiornamenti.',
'readonly'             => 'Accesso al database tenporaneamente disabilità',
'enterlockreason'      => 'Fornissi na spiegassion sui motivi del bloco, includendo le probabili data ed ora de riativazion o de rimozion del bloco.',
'readonlytext'         => "El database de {{SITENAME}} el xe al momento blocà, e no'l consente nove imissioni né modifiche, molto probabilmente par operassion de manutension del server, nel qual caso el database el sarà presto de novo completamente acessibile.

L'aministrador de sistema che gà messo el bloco el gà lassà sta spiegassion: $1",
'missing-article'      => 'El database no\'l gà mìa catà el testo de na pagina che la gavarìa dovùo èssarghe soto el nome de "$1" $2.

De solito questo el se verifica quando vien riciamà un confronto tra revision vece de la pagina o un colegamento a na version vecia de na pagina che xe stà scancelà.

In caso contrario, se gà probabilmente catà un eror del software MediaWiki.
Se prega de segnalar l\'acaduto a un [[Special:ListUsers/sysop|aministrador]] specificando la URL in question.',
'missingarticle-rev'   => '(revision n°: $1)',
'missingarticle-diff'  => '(Dif.: $1, $2)',
'readonly_lag'         => 'El database el xe stà blocà automaticamente par consentirghe ai server coi database slave de sincronizarse col master',
'internalerror'        => 'Eròr interno',
'internalerror_info'   => 'Eror interno: $1',
'filecopyerror'        => 'No xè stà possibiłe copiare el file "$1" come "$2".',
'filerenameerror'      => 'No xè stà possibile rinominare el file "$1" in "$2".',
'filedeleteerror'      => 'No xè stà possibiłe scancełare el file "$1".',
'directorycreateerror' => 'Xe inpossibile crear la directory "$1".',
'filenotfound'         => 'No xè stà possibile catar fora el file "$1".',
'fileexistserror'      => 'Xe inpossibile scrìvar el file "$1": sto file l\'esiste de zà',
'unexpected'           => 'Valor inprevisto: "$1"="$2".',
'formerror'            => "Erór: el modulo no'l xè stà invià correttamente",
'badarticleerror'      => 'Sta operazion no la xè consentìa su sta pàxena.',
'cannotdelete'         => "No se pol mìa scancełar la pàxena o l'imagine richiesta.",
'badtitle'             => "El titoło no'l xè mia giusto",
'badtitletext'         => 'El titolo de la pagina richiesta el xe vodo, sbalià o con caràteri mia amessi, opure el deriva da un eròr in tei colegamenti tra siti wiki diversi o version in lengoe diverse del stesso sito.',
'perfcached'           => "Sta qua la xè na copia ''cache'' e quindi la podarìa no èssar conpletamente agiornà.",
'perfcachedts'         => "I dati che segue i xe tirà fora da na copia ''cache'' del database. Ultimo agiornamento: $1.",
'querypage-no-updates' => 'I agiornamenti de la pagina i xe tenporaneamente sospesi. I dati contegnù ne la pagina no i vegnarà mìa agiornà.',
'wrong_wfQuery_params' => 'Parametri sbagliè par wfQuery()<br />
Funsion: $1<br />
Query: $2',
'viewsource'           => 'Varda el testo',
'viewsourcefor'        => 'de $1',
'actionthrottled'      => 'Azion ritardà',
'actionthrottledtext'  => "Come misura de sicureza contro el spam, l'esecuzion de çerte azioni la xe limità a un nùmaro massimo de olte in un determinato periodo de tenpo, limite che in sto caso te ghè superà. Par piaser ripróa tra qualche minuto.",
'protectedpagetext'    => 'Sta pagina la xe stà proteta par inpedìrghene la modifica.',
'viewsourcetext'       => 'Se pol vardar e copiar el codice sorgente de sta pagina:',
'protectedinterface'   => "Sta pàxena la contien un elemento che fa parte de l'interfacia utente del software; e quindi la xè proteta par evitar possibiłi abusi.",
'editinginterface'     => "'''Ocio:''' Te stè modificando na pagina che la fa parte de l'interfacia utente del sito. 
Tute le modifiche che te fè a sta pagina le se riflete su l'aspeto de l'interfacia grafica visualizà da tuti i altri utenti.
Se te ghè bisogno de tradur in veneto un messagio de l'interfacia utente, te pol doparar [http://translatewiki.net/wiki/Main_Page?setlang=vec translatewiki.net], el progeto de localizazion de MediaWiki.",
'sqlhidden'            => '(la query SQL la xe stà sconta)',
'cascadeprotected'     => 'Su sta pàxena no se pol far modifighe parché ła xe stà inclusa {{PLURAL:$1|ne ła pàxena indicà de seguito, che ła xe stà proteta|ne łe pàxene indicae de seguito, che łe xe stà protete}} selezionando ła protesion "ricorsiva":
$2',
'namespaceprotected'   => "No te ghè i permessi necessari par modificar le pagine del namespace '''$1'''.",
'customcssjsprotected' => "No te pol mìa modificar sta pagina, parché la contien le inpostazion personali de n'altro utente.",
'ns-specialprotected'  => 'No se pol modificar le pagine speciali.',
'titleprotected'       => "La creazion de na pagina con sto titolo la xe stà blocà da [[User:$1|$1]].
La motivazion la xe sta qua: ''$2''.",

# Virus scanner
'virus-badscanner'     => "Eror de configurassion: antivirus sconossiùo: ''$1''",
'virus-scanfailed'     => 'scansion mìa riussìa (codice de eror $1)',
'virus-unknownscanner' => 'antivirus sconossiùo:',

# Login and logout pages
'logouttext'                 => "'''Logout efetuà.'''

Ora te podi continuar a doparar {{SITENAME}} come utente anonimo, opure te podi far [[Special:UserLogin|de novo el login]] col nome utente de prima, opure con uno difarente. Nota che çerte pagine le podarìa èssar visualizà come se te fussi ancora loggà, fin che no te neti la cache del to browser.",
'welcomecreation'            => "== Benvegnù, $1! ==
El to account l'è stà creà con sucesso.
No sta desmentegarte de personalixare le to [[Special:Preferences|preferense de {{SITENAME}}]].",
'yourname'                   => 'Nome utente:',
'yourpassword'               => 'Scegli na password',
'yourpasswordagain'          => 'Scrivi la password de novo',
'remembermypassword'         => 'Ricorda la me password par più sessioni (ghe vol i cookies).',
'yourdomainname'             => 'Specifica el dominio:',
'externaldberror'            => 'Se gà verificà un erór con el server de autenticassion esterno, oppure no se dispone de łe autorixassion necessarie par agiornar el proprio açesso esterno.',
'login'                      => 'Entra',
'nav-login-createaccount'    => 'Entra / Regìstrete',
'loginprompt'                => 'Par acédere a {{SITENAME}} ti gà da abiłitare i cookie.',
'userlogin'                  => 'Entra / Regìstrete',
'logout'                     => 'Và fora',
'userlogout'                 => 'và fora',
'notloggedin'                => 'Acesso non efetuà',
'nologin'                    => "No gheto gnancora n'acesso? $1.",
'nologinlink'                => 'Crèalo desso',
'createaccount'              => 'Crea un novo accesso',
'gotaccount'                 => 'Gheto xà un to account? $1.',
'gotaccountlink'             => 'Entra',
'createaccountmail'          => 'par e-mail',
'badretype'                  => 'Le password che te ghè immesso no le coincide, le xè diverse fra de lore.',
'userexists'                 => "El nome utente che ti gà inserìo el xe zà doparà da n'altro utente.
Inserissi un nome difarente.",
'loginerror'                 => "Eror ne l'acesso",
'nocookiesnew'               => "El nome utente par l'acesso el xe stà creà, ma no s'à mìa podesto accédar a {{SITENAME}} parché i cookie i xe disativài. Ripróa a entrar col nome utente e la password 'pena creà dopo ver ativà i cookie sul to browser.",
'nocookieslogin'             => "Par far l'acesso a {{SITENAME}} a ghe vole i cookie, che i risulta èssar disativài. Ripróa a entrar dopo ver ativà i cookie nel to browser.",
'noname'                     => "El nome utente indicà no'l xe mìa valido, no se pol crear un account co sto nome.",
'loginsuccesstitle'          => 'Login efetuà con sucesso!',
'loginsuccess'               => "'''El cołegamento al server de {{SITENAME}} con el nome utente \"\$1\" el xè ativo.'''",
'nosuchuser'                 => 'No esiste nissun utente de nome "$1".
I nomi utenti i distingue tra majuscole e minuscole.
Verifica che el nome inserìo el sia giusto o [[Special:UserLogin/signup|crea na utensa nova]].',
'nosuchusershort'            => 'No xè registrà nissun utente de nome "<nowiki>$1</nowiki>". Verifica el nome inserìo.',
'nouserspecified'            => 'Bisogna specificar un nome utente.',
'wrongpassword'              => "La password che te ghe messo no l'è mia giusta.<br /><br />Riproa, par piaser.",
'wrongpasswordempty'         => 'La password inseria la xè voda. Ripróa da novo.',
'passwordtooshort'           => 'La to password no la xe mìa valida o la xe massa curta.
La gà da contegner almanco {{PLURAL:$1|$1 caràtere|$1 caràteri}} e la gà da essar difarente dal to nome utente.',
'mailmypassword'             => 'Màndeme na password nova par posta eletronica',
'passwordremindertitle'      => 'Servizio Password Reminder de {{SITENAME}}',
'passwordremindertext'       => 'Qualcheduni (probabilmente ti, da l\'indirizo IP $1) el gà domandà che ghe vegna mandà na nova password par {{SITENAME}} ($4).
Na password tenporànea par l\'utente "$2" la xe stà creà e inpostà a "$3".
Se xe questo che te voléi far, desso te podi entrar co\' sta password tenporanea e inpostar na password nova.
La to password tenporànea la scade in {{PLURAL:$5|un zorno|$5 zorni}}.

Se no te sì mìa stà ti a far la domanda, opure t\'è vegnù in mente la password e no te vol più canbiarla, te pol ignorar sto mesagio e continuar a doparar la vecia password.',
'noemail'                    => 'Nissuna casela e-mail la risulta registrà par l\'Utente "$1".',
'passwordsent'               => 'Na password nova la xe stà mandà a la casela e-mail registrà per l\'Utente "$1".
Par piaser, fà subito un login \'pena che la te riva.',
'blocked-mailpassword'       => 'Per prevegner abusi, no se pol mìa doparar la funzion "Invia nova password" da un indirizo IP blocà.',
'eauthentsent'               => "Na email de conferma la xè stà invià a l'indirizzo che te ghè indicà. Prima che qualunque altra mail te vegna invià, te ghè da seguir le istrussioni contegnùe ne la mail ricevuta, par confermar che quel'indirizzo el xè dal bon el tuo.",
'throttled-mailpassword'     => 'Na password nova la xe zà stà mandà da manco de {{PLURAL:$1|$1 ora|$1 ore}}. Par prevegner abusi, la funzion "Invia nova password" la pol èssar doparà solo na volta ogni {{PLURAL:$1|$1 ora|$1 ore}}.',
'mailerror'                  => "Ghe xè stà un eror nel mandare l'email: $1",
'acct_creation_throttle_hit' => "Dei utenti de sta wiki col to stesso indirisso IP i gà creà {{PLURAL:$1|1 utensa|$1 utense}} ne l'ultimo zorno, che xe el massimo consentìo in sto periodo de tenpo. Perciò, i utenti che dòpara sto indirisso IP no i pode crear altre utense par el momento.",
'emailauthenticated'         => "El to indirisso de e-mail l'è stado autenticado su $2 el $3.",
'emailnotauthenticated'      => "El to indirizo email <strong>no'l xè gnancora stà autenticà</strong>. Nissuna email la vegnarà invià tramite le funsioni che segue.",
'noemailprefs'               => 'Indica un indirizo e-mail par ativar ste funzioni.',
'emailconfirmlink'           => 'Conferma el to indirizo de e-mail',
'invalidemailaddress'        => "L'indirisso email no'l pode èssar acetà parché el gà un formato mìa valido.
Inserissi un indirisso valido o svoda la casèła.",
'accountcreated'             => 'Acesso creà',
'accountcreatedtext'         => "Xè stà creà un acesso par l'utente $1.",
'createaccount-title'        => 'Creazion de un acesso a {{SITENAME}}',
'createaccount-text'         => 'Qualcheduni gà creà un acesso a {{SITENAME}} ($4) a nome de $2, associà a sto indirizo de posta eletronica.
La password par l\'utente "$2" la xe inpostà a "$3". Xe oportuno eseguir un acesso quanto prima e canbiar la password subito dopo.

Se l\'acesso el xe stà creà par sbaglio, se pol ignorar sto messagio.',
'login-throttled'            => 'Te ghè fato massa tentativi su la password de sto account. Spèta un tocheto prima de proàr da novo.',
'loginlanguagelabel'         => 'Lengua: $1',

# Password reset dialog
'resetpass'                 => 'Cànbia la password',
'resetpass_announce'        => "L'acesso el xe stà efetuà con un codice tenporaneo, mandà par e-mail. Par conpletar l'acesso bisogna inpostar na password nova:",
'resetpass_text'            => '<!-- Zonta el testo qua -->',
'resetpass_header'          => 'Cànbia la password de la to utensa',
'oldpassword'               => 'Vecia password:',
'newpassword'               => 'Nova password:',
'retypenew'                 => 'Riscrivi la password nova:',
'resetpass_submit'          => 'Inposta la password e acedi al sito',
'resetpass_success'         => 'La password la xe stà modificà. Acesso in corso...',
'resetpass_forbidden'       => 'No se pol modificar le password',
'resetpass-no-info'         => "Te ghè da ver fato l'acesso per poder entrar in sta pàxena.",
'resetpass-submit-loggedin' => 'Cànbia password',
'resetpass-wrong-oldpass'   => 'Password corente o tenporanea mia valida.
Forse te ghè zà canbià la to password o te ghè domandà na password tenporanea nova.',
'resetpass-temp-password'   => 'Password tenporanea:',

# Edit page toolbar
'bold_sample'     => 'Grasseto',
'bold_tip'        => 'Grasseto',
'italic_sample'   => 'Corsivo',
'italic_tip'      => 'Corsivo',
'link_sample'     => 'Nome del colegamento',
'link_tip'        => 'Colegamento interno',
'extlink_sample'  => 'http://www.example.com titolo del colegamento',
'extlink_tip'     => 'Colegamento esterno (ricòrdete el prefisso http:// )',
'headline_sample' => 'Intestazión',
'headline_tip'    => 'Sotointestazion',
'math_sample'     => 'Inserissi qua la formula',
'math_tip'        => 'Formula matemàtega (LaTeX)',
'nowiki_sample'   => 'Inserissi qua el testo non formatà',
'nowiki_tip'      => 'Ignora la formatazion wiki',
'image_sample'    => 'Esenpio.jpg',
'image_tip'       => 'File incorporado',
'media_sample'    => 'Esenpio.ogg',
'media_tip'       => 'Colegamento a file multimedial',
'sig_tip'         => 'La to firma con data e ora',
'hr_tip'          => 'Linea orizontal (dòparela con giudizio)',

# Edit pages
'summary'                          => 'Ogeto:',
'subject'                          => 'Argomento (intestassion):',
'minoredit'                        => 'Sta quà la xe na modìfega picenina',
'watchthis'                        => "Tien d'ocio sta pagina",
'savearticle'                      => 'Salva la pàxena',
'preview'                          => 'Anteprima',
'showpreview'                      => 'Mostra anteprima',
'showlivepreview'                  => "Funzion ''Live preview''",
'showdiff'                         => 'Mostra canbiamenti',
'anoneditwarning'                  => "'''Ocio:''' Acesso mìa efetuà. Ne ła cronołogia de ła pàxena vegnarà registrà el to indirizo IP.",
'missingsummary'                   => "'''Ocio:''' No te ghè indicà l'ogeto de la modifica. Macando de novo 'Salva la pagina' la modifica la vegnerà con l'ogeto vodo.",
'missingcommenttext'               => 'Inserissi un comento qua soto.',
'missingcommentheader'             => "'''Ocio:''' No te ghè specificà l'intestazion de sto commento. Macando de novo '''Salva la pagina''' la modifica la vegnarà salvà senza intestazion.",
'summary-preview'                  => 'Anteprima ogeto:',
'subject-preview'                  => 'Anteprima ogeto/intestazion:',
'blockedtitle'                     => 'Utente blocà',
'blockedtext'                      => "<big>'''Sto nome utente o indirizo IP el xe stà blocà.'''</big>

El bloco el xe stà messo da $1.
La motivazion del bloco la xe sta qua: ''$2''.

* Inizio del bloco: $8
* Scadensa del bloco: $6
* Intervalo del bloco: $7

Se te vol, te pol contatar $1 o n'altro [[{{MediaWiki:Grouppage-sysop}}|aministrador]] par discùtar del bloco.

Nota che la funzion 'Scrivi a l'utente' no la xe mìa ativa se no te ghè registrà un indirizo e-mail valido ne le to [[Special:Preferences|preferenze]] e se sto indirizo no'l xe stà blocà.

Se prega de specificare l'indirizo IP atuale ($3) o el nùmaro del bloco (ID #$5) in qualsiasi richiesta de ciarimenti.",
'autoblockedtext'                  => "Sto indirizo IP el xe stà blocà automaticamente parché condiviso con n'altro utente, a so volta blocà da $1.
La motivazion del blocco la xe sta qua:

:''$2''

* Inizio del bloco: $8
* Scadenza del bloco: $6
* Intervalo del bloco: $7

Se pol contatar $1 o n'altro [[{{MediaWiki:Grouppage-sysop}}|aministrador]] par discùtar del bloco.

Nota che la funzion 'Scrivi a l'utente' no la xe ativa a meno che no te gavi registrà un indirizo e-mail valido ne le to [[Special:Preferences|preferenze]] e che l'indirizo no'l sia stà blocà.

Se prega de specificar el to indirizo IP atuale ($3) e el nùmaro del bloco (ID #$5) in qualsiasi richiesta de ciarimenti.",
'blockednoreason'                  => 'nissuna motivazion indicà',
'blockedoriginalsource'            => "El còdese sorgente de '''$1''' el vien mostrà de seguito:",
'blockededitsource'                => "El testo de le '''to modifiche''' a '''$1''' el xe mostrà qua soto:",
'whitelistedittitle'               => 'Bisogna èssar registrà par poder modificar le pàxene',
'whitelistedittext'                => 'Par modificar łe pàxene ghe xè bisogno de $1.',
'confirmedittext'                  => "Te ghè da confermar l'indirizo e-mail prima de editar le pàxene. Par piaxer inposta e conferma el to indirizo e--mail tramite le to [[Special:Preferences|preferenze]].",
'nosuchsectiontitle'               => 'Sta sezion no la esiste',
'nosuchsectiontext'                => 'Te ghè çercà de modificar na sezion inesistente. No se pol mìa salvar le modifiche in quanto la sezion $1 no la esiste.',
'loginreqtitle'                    => "Par modificar sta pagina bisogna prima eseguir l'acesso al sito.",
'loginreqlink'                     => 'login',
'loginreqpagetext'                 => 'Par védar altre pagine bisogna $1.',
'accmailtitle'                     => 'Password spedia.',
'accmailtext'                      => "Na password xenerà casualmente par [[User talk:$1|$1]] la xe stà mandà a $2.

La password par sta nova utensa la pode vegner canbià, dopo ver fato l'acesso, su la pàxena ''[[Special:ChangePassword|canbiar la password]]''.",
'newarticle'                       => '(Novo)',
'newarticletext'                   => "El cołegamento che te ghè 'pena seguìo el corisponde a na pàxena gnancora esistente.
Se te desideri crear ła pàxena desso, basta che te tachi a scrìvar el testo ne ła caseła qua soto
(fà riferimento a łe [[{{MediaWiki:Helppage}}|pàxene de ajuto]] par majori informassion).
Se te si rivà qua par sbaglio, basta che te machi el boton '''Indrio''' sul to browser.",
'anontalkpagetext'                 => "----''Sta quà l'è la pàxena de discussion de un utente anonimo che no'l se gà gnancora registrà o che no l'efetua el login.
De conseguenza xè necessario identificarlo tramite l'indirizo IP numerico.
Sto indirizo el pode èssar condivixo da diversi utenti.
Se te sì un utente anonimo e te ghè riçevù dei messagi che te secondo ti i xera direti a qualchedun altro, te podi [[Special:UserLogin/signup|registrarte]] o [[Special:UserLogin|efetuar el login]] par evitar confuxion con altri utenti anonimi in futuro.''",
'noarticletext'                    => 'In sto momento ła pàxena richiesta la xè voda.
Se pol [[Special:Search/{{PAGENAME}}|sercar sto titoło]] ne łe altre pàxene,
<span class="plainlinks">[{{fullurl:Special:Log|page={{urlencode:{{FULLPAGENAME}}}}}} sercar i registri relativi],
opure [{{fullurl:{{FULLPAGENAME}}|action=edit}} modificar ła pàxena desso].',
'userpage-userdoesnotexist'        => 'L\'account "$1" no\'l corisponde mìa a un utente registrà. Verifica se te voli dal bon crear o modificar sta pagina.',
'clearyourcache'                   => "'''Ocio: dopo aver salvà, te ghè da netar la cache del to browser par védar i canbiamenti.''' Par '''Mozilla / Firefox / Safari:''' tien macà el boton de le majuscole e schiza \"Ricarica\", o senò maca ''Ctrl-F5'' o ''Ctrl-R'' (''Command-R'' se te ghè el Macintosh); par '''Konqueror:''' schiza \"Ricarica\" o maca ''F5;'' par '''Opera:''' néta la cache in ''Strumenti → Preferenze;'' par '''Internet Explorer:''' tien macà ''Ctrl'' fin che te schizi ''Ricarica'', o maca ''Ctrl-F5.''",
'usercssjsyoucanpreview'           => "'''Sugerimento:''' se consiglia de doparar el boton 'Visualiza anteprima' par proàr i novi CSS o JavaScript prima de salvarli.",
'usercsspreview'                   => "'''Sta qua la xe solo n'anteprima del proprio CSS personal.
Le modifiche no le xe gnancora stà salvà!'''",
'userjspreview'                    => "'''Sta qua la xe solo n'anteprima par proar el proprio JavaScript personal; le modifiche no le xe gnancora stà salvà!'''",
'userinvalidcssjstitle'            => "'''Ocio:'''  No ghe xe nissuna skin con nome \"\$1\". Nota che le pagine par i .css e .js personalizà le gà l'iniziale del titolo minuscola, par esenpio {{ns:user}}:Esenpio/monobook.css e no {{ns:user}}:Esenpio/Monobook.css.",
'updated'                          => '(Agiornà)',
'note'                             => "'''Nota:'''",
'previewnote'                      => "'''Tien presente che sta qua la xe solo n'anteprima, e che la to version NO la xè stà gnancora salvà!'''",
'previewconflict'                  => 'Sta anteprima la corisponde al testo ne la casèla de edizion de sora, e la fa védar come vegnarà fora la pagina se te machi "Salva la pagina" in sto momento.',
'session_fail_preview'             => "No xè stà possibiłe salvar le to modifiche parché i dati de la session i xè andai persi. 
Par piaser, riproa da novo.
Se no funsiona gnancora, proa a [[Special:UserLogout|scołegarte]] e a cołegarte de novo.'''",
'session_fail_preview_html'        => "'''No xe mìa stà possibile elaborar la modifica parché xe 'ndà persi i dati relativi a la session.'''

''Dato che su {{SITENAME}} xe abilità l'uso de HTML senza limitazion, l'anteprima no la vien visualizà; se tratta de na misura de sicureza contro i atachi JavaScript.''

'''Se te stè fasendo na modifica legìtima, par piaser próa de novo.
Se no funsiona gnancora, te pol proár a [[Special:UserLogout|scolegarte]] e efetuar da novo l'acesso.'''",
'token_suffix_mismatch'            => "'''La modifica no la xe mìa stà salvà parché el client el gà mostrà de gestir in maniera sbaglià i caràteri de puntegiatura nel token associà a la stessa. Par evitar na possibile coruzion del testo de la pagina, xe stà rifiutà l'intera modifica. Sta situazion la pode verificarse, a olte, quando vien doparà çerti servizi de proxy anonimi via web che presenta dei bug.'''",
'editing'                          => 'Modifica de $1',
'editingsection'                   => 'Modìfega de $1 (sezion)',
'editingcomment'                   => 'Modifica de $1 (sezion nova)',
'editconflict'                     => 'Conflito de edizion: $1',
'explainconflict'                  => "Qualcun altro el ga salvà na so version de ła voçe nel tempo in cui te stavi preparando ła to version.
La casela de modifica de sora contegne el testo de la voçe ne ła so forma atuałe (el testo atualmente online).
Le to modifiche łe xè inveçe contegnue ne ła caseła de modifica de soto.
Te dovarè inserire, se te vołi, le to modifiche nel testo esistente, e perciò scrivarle ne ła caseła de sora.
'''Soltanto''' el testo ne ła caseła de sora el sarà salvà se te struchi el botón \"Salva\".",
'yourtext'                         => 'El to testo',
'storedversion'                    => 'Version in archivio',
'nonunicodebrowser'                => "'''OCIO: Te stè doparando un browser mìa conpatibile coi caràteri Unicode. Par consentir la modifica de le pagine senza crear inconvenienti, i caràteri non ASCII i vien mostrà ne la casela de modifica soto forma de codici esadecimali.'''",
'editingold'                       => "'''Ocio: Te stè modificando na version de ła voçe non agiornà. Se te la salvi cussì, tuti i canbiamenti apportai dopo sta version i vegnarà persi.'''",
'yourdiff'                         => 'Difarense',
'copyrightwarning'                 => "Nota: tuti i contributi a {{SITENAME}} i se considera rilasià nei termini de la licenza d'uso $2 (varda $1 par savérghene piessè). Se no te voli che i to testi i possa èssar modificà e redistribuìi da chiunque senza nissuna limitazion, no sta inviarli a {{SITENAME}}.<br />
Con l'invio del testo te dichiari inoltre, soto la to responsabilità, che el testo el xe stà scrito da ti personalmente opure che el xe stà copià da na fonte de publico dominio o analogamente lìbara.
'''NO STA INVIAR MATERIALE CUERTO DA DIRITO D'AUTOR SENZA AUTORIZAZION!'''",
'copyrightwarning2'                => "Ocio che tuti i contributi a {{SITENAME}} i pode èssar editai, alterai, o rimossi da altri contributori.
Se no te voli che i to scriti i vegna modificà sensa pietà, alora no sta inserirli qua.<br />
Sapi che te stè prometendo che te stè inserendo un testo scrito de to pugno, o copià da na fonte de publico dominio o similarmente lìbara (varda $1 par i detagli).
'''NO STA INSERIR OPERE PROTETE DA COPYRIGHT SENZA PERMESSO!'''",
'longpagewarning'                  => "'''OCIO: Sta pàxena la xè longa $1 kilobyte; çerti browser i podarìa verghe dei problemi ne ła modifega de pàxene che se aviçina o supera i 32 kB. Valuta l'oportunità de sudivìdar ła pàxena in sezion pìassè picenine.'''",
'longpageerror'                    => "'''ERROR: The text you have submitted is $1 kilobytes 
long, which is longer than the maximum of $2 kilobytes. It cannot be saved.'''",
'readonlywarning'                  => "'''OCIO: El database el xe stà blocà par manutenzion, quindi no se pol salvar le modifiche in sto momento.
Par no pèrdarle, te pol copiar tuto quel che te ghè inserìo fin desso ne la casela de modifica, incolarlo in un programa de elaborazion de testi e salvarlo, intanto che te speti che i sbloca el database.'''

L'aministrador che gà blocà el database el gà dato la seguente spiegassion: $1",
'protectedpagewarning'             => "'''OCIO: Sta pagina la xe sta proteta e solo i aministradori i pode modificarla.'''",
'semiprotectedpagewarning'         => "'''Nota:''' Sta pàxena la xè stà blocà in modo che solo i utenti registrài i poda modefegarla.",
'cascadeprotectedwarning'          => "'''Ocio:''' Sta pagina la xe stà blocà in modo che solo i utenti con privilegi de aministrador i possa modificarla. Questo sucede parché la pagina la xe inclusa {{PLURAL:\$1|ne la pagina indicà de seguito, che la xe stà proteta|ne le pagine indicà de seguito, che le xe stà protete}} selezionando la protezion \"ricorsiva\":",
'titleprotectedwarning'            => "'''OCIO:  Sta pàxena la xe stà blocà in modo che solo i utenti con [[Special:ListGroupRights|çerti privilègi]] i le possa crear.'''",
'templatesused'                    => 'Template doparà in sta pagina:',
'templatesusedpreview'             => 'Template doparà in sta anteprima:',
'templatesusedsection'             => 'Template doparà in sta sezion:',
'template-protected'               => '(proteto)',
'template-semiprotected'           => '(semiproteto)',
'hiddencategories'                 => 'Sta pagina la fa parte de {{PLURAL:$1|una categoria sconta|$1 categorie sconte}}:',
'nocreatetitle'                    => 'Creazion de le pagine limitada',
'nocreatetext'                     => 'La possibilità de crear pagine nóve su {{SITENAME}} la xe stà limità ai soli utenti registrà. Se pol tornar indrìo e modificar na pagina esistente, opure [[Special:UserLogin|entrar o crear un nóvo acesso]].',
'nocreate-loggedin'                => 'No te ghè i permessi necessari a crear pagine nove.',
'permissionserrors'                => 'Eror nei permessi',
'permissionserrorstext'            => "No te ghè i permessi necessari ad eseguir l'azion richiesta, par {{PLURAL:$1|el seguente motivo|i seguenti motivi}}:",
'permissionserrorstext-withaction' => 'No ti gà el parmesso de $2, par {{PLURAL:$1|el seguente motivo|i seguenti motivi}}:',
'recreate-moveddeleted-warn'       => "'''Ocio: te stè par ricrear na pagina zà scancelà precedentemente.'''

Par piaser assicùrete che sia dal bon el caso de 'ndar vanti a modificar sta pagina.
L'elenco de i relativi spostamenti e scancelazion el vien riportà qua de seguito par comodità:",
'moveddeleted-notice'              => "Sta pàxena la xe stà scancelà.
L'elenco dei relativi spostamenti e scancelassion el vien riportà qua soto par informassion.",
'log-fulllog'                      => 'Varda registro conpleto',
'edit-hook-aborted'                => "Modifica abortìa da parte de l'hook.
No xe stà dà nissuna spiegazion in merito.",
'edit-gone-missing'                => 'No se riesse a agiornar la pàxena.
Pararìa che la sìpia stà scancelà.',
'edit-conflict'                    => 'Conflito de modifica.',
'edit-no-change'                   => 'La to modifica la xe stà ignorà, parché no ti gà canbià gnente nel testo.',
'edit-already-exists'              => 'No se pol crear na pàxena nova.
La esiste de zà.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Ocio: Sta pagina la contien dele chiamate de funzion al parser massa onerose.

Dovarìa èssarghe manco de $2 {{PLURAL:$2|chiamata|chiamate}}, {{PLURAL:$1|ghe ne xe|ghe ne xe}} $1.',
'expensive-parserfunction-category'       => 'Pagina con chiamate de funzion al parser massa onerose',
'post-expand-template-inclusion-warning'  => 'Ocio: la dimension de inclusion dei template la xe massa granda.
Alcuni template no i sarà mia inclusi.',
'post-expand-template-inclusion-category' => 'Pagine in do che la dimension de inclusion dei template la xe massa granda',
'post-expand-template-argument-warning'   => 'Ocio: Sta pagina la contien almanco un argomento de template che el gà na dimension de espansion massa granda.
Sti argomenti i xe stà omessi.',
'post-expand-template-argument-category'  => 'Pagine che contien template con argomenti mancanti',
'parser-template-loop-warning'            => 'Xe stà catà un ciclo in tel modèl: [[$1]]',
'parser-template-recursion-depth-warning' => 'Xe stà rajunto el limite màssimo de ricorsion in tel modèl ($1)',

# "Undo" feature
'undo-success' => 'Sta modifica la pode èssar anulà. Verifica el confronto presentà de seguito par èssar sicuro che el contenuto el sia come te lo voli e quindi salva le modifiche par conpletar la procedura de anulamento.',
'undo-failure' => 'No se pol mìa anular la modifica, par via de un conflito con modifiche intermedie.',
'undo-norev'   => 'La modifica no la pode vegner anulà parché no la esiste o la xe stà scancelà.',
'undo-summary' => 'Anulà la modifica $1 de [[Special:Contributions/$2|$2]] ([[User talk:$2|Discussion]] | [[Special:Contributions/$2|{{MediaWiki:Contribslink}}]])',

# Account creation failure
'cantcreateaccounttitle' => "Inpossibile registrar l'utente",
'cantcreateaccount-text' => "La creazion de account novi a partir da sto indirizo IP ('''$1''') la xe stà blocà da [[User:$3|$3]].

La motivazion del bloco fornìa da $3 la xe sta qua: ''$2''",

# History pages
'viewpagelogs'           => 'Varda i registri relativi a sta pagina qua',
'nohistory'              => 'La cronołogia de łe version de sta pàxena no la xè reperibiłe.',
'currentrev'             => 'Version atuałe',
'currentrev-asof'        => 'Version corrente del $1',
'revisionasof'           => 'Revixion $1',
'revision-info'          => 'Version del $1, autor: $2',
'previousrevision'       => '← Version manco reçente',
'nextrevision'           => 'Version pì reçente →',
'currentrevisionlink'    => 'Varda ła version atuałe',
'cur'                    => 'cor',
'next'                   => 'suc',
'last'                   => 'prec',
'page_first'             => 'prima',
'page_last'              => 'ultima',
'histlegend'             => "Confronto tra version: selessiona le casèle corispondenti a le version desiderà e struca Invio o el boton in basso.

Legenda: '''({{int:cur}})''' = difarense con la versión corente,
'''({{int:last}})''' = difarense con la versión precedente, '''{{int:minoreditletter}}''' = modifica picenina",
'history-fieldset-title' => 'Serca in te la cronologia',
'deletedrev'             => '[scancełà]',
'histfirst'              => 'Prima',
'histlast'               => 'Ultima',
'historysize'            => '({{PLURAL:$1|1 byte|$1 byte}})',
'historyempty'           => '(voda)',

# Revision feed
'history-feed-title'          => 'Cronołogia',
'history-feed-description'    => 'Cronołogia de ła pàxena su sto sito',
'history-feed-item-nocomment' => '$1 el $2',
'history-feed-empty'          => 'La pàxena richiesta no la esiste; la podarìa èssar stà scancełà dal sito o rinominà. Verifica con la [[Special:Search|pàxena de riçerca]] se ghe xè nove pàxene.',

# Revision deletion
'rev-deleted-comment'            => '(comento cavà)',
'rev-deleted-user'               => '(nome utente cavà)',
'rev-deleted-event'              => '(elemento cavà)',
'rev-deleted-text-permission'    => "Sta version de la pagina la xe stà '''scancelà'''.
Consulta el [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} registro de scancelazion] par ulteriori detagli.",
'rev-deleted-text-unhide'        => "Sta version de la pàxena la xe sta '''scancelà'''.
Consulta el [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} registro de scancelassion] par ulteriori detagli.
Ai aministradori xe ancora consentìo [$1 vardar sta version] se necessario.",
'rev-deleted-text-view'          => "Sta version de la pagina la xe stà '''scancelà'''.
El testo el pode èssar visualizà soltanto dai aministradori del sito.
Consulta el [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} registro de scancelazion] par ulteriori detagli.",
'rev-deleted-no-diff'            => "No te pode vardar sta difarensa parché una de le revision la xe stà '''scancelà'''.
Consulta el [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} registro de scancelassion] par savérghene piessè.",
'rev-deleted-unhide-diff'        => "Una dele revision de sta difarensa la xe stà '''scancelà'''.
Consulta el [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} registro de scancelassion] par ulteriori detagli.
I aministradori i pode ancora [$1 vardar sta difarensa] se i vole.",
'rev-delundel'                   => 'mostra/scondi',
'revisiondelete'                 => 'Scanceła o ripristina version',
'revdelete-nooldid-title'        => 'Version mìa specificà',
'revdelete-nooldid-text'         => 'No xe stà specificà alcuna version de la pagina su cui eseguir sta funzion.',
'revdelete-nologtype-title'      => 'Nissun tipo de registro specificà',
'revdelete-nologtype-text'       => "No ti gà indicà nissun tipo de registro su cui eseguir l'azion.",
'revdelete-toomanytargets-title' => 'Massa destinassioni',
'revdelete-toomanytargets-text'  => 'Te ghè indicà massa tipi de destinassion su cui eseguir sta operassion.',
'revdelete-nologid-title'        => 'Eròr de indicazion dei registri',
'revdelete-nologid-text'         => "Par eseguir sta funsion no te ghè indicà na destinassion par el registro opure el registro no l'esiste.",
'revdelete-selected'             => "'''{{PLURAL:$2|Version selezionà|Versioni selezionà}} de [[:$1]]:'''",
'logdelete-selected'             => "'''{{PLURAL:$1|Evento del registro selezionà|Eventi del registro selezionè}}:'''",
'revdelete-text'                 => "'''Le revision e le azion scancelàe le restarà visibili ne la cronologia de la pagina, ma parte del testo contegnùo no'l sarà visìbile al publico.'''

I altri aministradori de {{SITENAME}} i podarà vardar istesso i contenuti sconti e ripristinarli atraverso questa stessa interfacia, se no xe stà inpostà altre limitazion.
Par piaser conferma che te voli dal bon far sta scancelassion, che te conossi le conseguense e che te sì drio operar in conformità de le [[{{MediaWiki:Policy-url}}|linee-guida]].",
'revdelete-suppress-text'        => "La sopression la se dovarìa doparar '''solo''' in sti casi qua:

* Informassion personali mia apropriate
*: ''indirissi de casa e nùmari de telefono, nùmari de previdensa sociale, etc.''",
'revdelete-legend'               => 'Inposta le seguenti limitazion su le versioni scancelàe:',
'revdelete-hide-text'            => 'Scondi el testo de ła version',
'revdelete-hide-name'            => 'Scondi azion e ogeto de la stessa',
'revdelete-hide-comment'         => "Scondi l'oggetto de ła modifega",
'revdelete-hide-user'            => "Scondi el nome o l'indirisso IP dell'autore",
'revdelete-hide-restricted'      => 'Scóndighe le informassion indicà anca ai aministradori',
'revdelete-suppress'             => 'Scondi le informazion anca ai aministradori',
'revdelete-hide-image'           => 'Scondi i contenuti del file',
'revdelete-unsuppress'           => 'Elimina le limitazion su le revision ripristinà',
'revdelete-log'                  => 'Comento par el registro:',
'revdelete-submit'               => 'Àplica a ła revixion selezionà',
'revdelete-logentry'             => 'gà modificà la visibilità par una revision de [[$1]]',
'logdelete-logentry'             => "gà modificà la visibilità de l'evento [[$1]]",
'revdelete-success'              => "'''Visibilità de la revision inpostà coretamente.'''",
'revdelete-failure'              => "'''No se riesse a inpostar la visibilità de la version.'''",
'logdelete-success'              => "'''Visibilità de l'evento inpostà coretamente.'''",
'revdel-restore'                 => 'Canbia visibilità',
'pagehist'                       => 'Cronologia de la pagina',
'deletedhist'                    => 'Cronologia scancelà',
'revdelete-content'              => 'contenuto',
'revdelete-summary'              => 'modifica ogeto',
'revdelete-uname'                => 'nome utente',
'revdelete-restricted'           => 'aplicà restrizioni ai aministradori',
'revdelete-unrestricted'         => 'gà cavà le limitazion par i aministradori',
'revdelete-hid'                  => 'scondar $1',
'revdelete-unhid'                => 'mostrar $1',
'revdelete-log-message'          => '$1 par $2 {{PLURAL:$2|revision|revisioni}}',
'logdelete-log-message'          => '$1 par $2 {{PLURAL:$2|evento|eventi}}',

# Suppression log
'suppressionlog'     => 'Registro dei ocultamenti',
'suppressionlogtext' => "Qua soto se cata na lista de le pi reçenti scancelazioni e blochi che riguarda contenuti sconti dai aministradori. Varda la [[Special:IPBlockList|lista dei IP blocà]] par védar l'elenco dei blochi atualmente ativi.",

# History merging
'mergehistory'                     => 'Union cronologie',
'mergehistory-header'              => 'Sta pagina la consente de unir le revision che fa parte de la cronologia d na pagina (ciamà pagina de origine) a la cronologia de na pagina piassè reçente.
Assicùrete che la continuità storica de la pagina no la vegna alterà.',
'mergehistory-box'                 => 'Unissi la cronologia de do pagine:',
'mergehistory-from'                => 'Pagina de origine:',
'mergehistory-into'                => 'Pagina de destinazion:',
'mergehistory-list'                => "Cronologia a cui se pol aplicar l'union",
'mergehistory-merge'               => 'Se pode unir le revision de [[:$1]] indicà de seguito a la cronologia de [[:$2]]. Dòpara la colona coi botoni de opzione par unir tute le revision fin a la data e ora indicàe. Nota che se vien doparà i botoni de navigazion, la colona coi botoni de opzione la vien azerà.',
'mergehistory-go'                  => 'Mostra le modifiche che pol èssar unìe',
'mergehistory-submit'              => 'Unissi le revision',
'mergehistory-empty'               => 'Nissuna revision da unir.',
'mergehistory-success'             => '{{PLURAL:$3|Una revision de [[:$1]] la xe stà unìa|$3 revision de [[:$1]] le xe stà unìe}} a la cronologia de [[:$2]].',
'mergehistory-fail'                => 'Inpossibile unir le cronologie. Verifica la pagina e i parametri tenporali.',
'mergehistory-no-source'           => 'La pagina de origine $1 no la esiste.',
'mergehistory-no-destination'      => 'La pagina de destinazion $1 no la esiste.',
'mergehistory-invalid-source'      => 'La pagina de origine la gà da verghe un titolo coreto.',
'mergehistory-invalid-destination' => 'La pagina de destinazion la gà da verghe un titolo coreto.',
'mergehistory-autocomment'         => 'Union de [[:$1]] in [[:$2]]',
'mergehistory-comment'             => 'Union d [[:$1]] in [[:$2]]: $3',
'mergehistory-same-destination'    => 'Le pàxene de origine e de destinasion no le pode èssar la stessa',
'mergehistory-reason'              => 'Motivassion:',

# Merge log
'mergelog'           => 'Registro de le unioni',
'pagemerge-logentry' => 'gà unìo [[$1]] a [[$2]] (revisioni fin a $3)',
'revertmerge'        => 'Anula unioni',
'mergelogpagetext'   => "Qua de seguito vien presentà na lista de le ultime operazion de unione de la cronologia de na pagina in un'altra.",

# Diffs
'history-title'            => 'Cronologia dei canbiamenti a "$1"',
'difference'               => '(Difarense fra łe version)',
'lineno'                   => 'Riga $1:',
'compareselectedversions'  => 'Confronta łe version selezionà',
'showhideselectedversions' => 'Mostra/scondi version selessionà',
'visualcomparison'         => 'Confronto visuale',
'wikicodecomparison'       => 'Confronto del wikitext',
'editundo'                 => 'anuła',
'diff-multi'               => '({{PLURAL:$1|Una revision intermedia non mostrà|$1 revision intermedie non mostrà}}.)',
'diff-movedto'             => 'spostà a $1',
'diff-styleadded'          => '$1 stile zontà',
'diff-added'               => '$1 zontà',
'diff-changedto'           => 'canbià in $1',
'diff-movedoutof'          => 'spostà fora da $1',
'diff-styleremoved'        => 'stile $1 cavà',
'diff-removed'             => '$1 cavà',
'diff-changedfrom'         => 'canbià da $1',
'diff-src'                 => 'fonte',
'diff-withdestination'     => "co' destinasion $1",
'diff-with'                => "&#32;co' $1 $2",
'diff-with-final'          => '&#32;e $1 $2',
'diff-width'               => 'larghessa',
'diff-height'              => 'altessa',
'diff-p'                   => "un '''paràgrafo'''",
'diff-blockquote'          => "na '''citasion'''",
'diff-h1'                  => "na '''intestasion (livèl 1)'''",
'diff-h2'                  => "na '''intestasion (livèl 2)'''",
'diff-h3'                  => "na '''intestasion (livèl 3)'''",
'diff-h4'                  => "na '''intestasion (livèl 4)'''",
'diff-h5'                  => "na '''intestasion (livèl 5)'''",
'diff-pre'                 => "un '''bloco za formatà'''",
'diff-div'                 => "na '''division'''",
'diff-ul'                  => "na '''lista mia ordenà'''",
'diff-ol'                  => "na '''lista ordenà'''",
'diff-li'                  => "un '''elemento de la lista'''",
'diff-table'               => "na '''tabèla'''",
'diff-tbody'               => "el '''contenuto de na tabèla'''",
'diff-tr'                  => "na '''riga'''",
'diff-td'                  => "na '''casèla'''",
'diff-th'                  => "na '''intestasion'''",
'diff-br'                  => "na '''interusion'''",
'diff-hr'                  => "na '''riga orizontal'''",
'diff-code'                => "un '''toco de còdese da computer'''",
'diff-dl'                  => "na '''lista de definissioni'''",
'diff-dt'                  => "un '''termine de definission'''",
'diff-dd'                  => "na '''definission'''",
'diff-input'               => "un '''input'''",
'diff-form'                => "un '''modulo'''",
'diff-img'                 => "na '''imagine'''",
'diff-span'                => "un '''span'''",
'diff-a'                   => "un '''colegamento'''",
'diff-i'                   => "'''corsivo'''",
'diff-b'                   => "'''grasseto'''",
'diff-strong'              => "'''grasseto'''",
'diff-em'                  => "'''ènfasi'''",
'diff-font'                => "'''caràtere'''",
'diff-big'                 => "'''grando'''",
'diff-del'                 => "'''scancelà'''",
'diff-tt'                  => "'''larghessa fissa'''",
'diff-sub'                 => "'''àpice'''",
'diff-sup'                 => "'''pèdice'''",
'diff-strike'              => "'''sbarà'''",

# Search results
'searchresults'                    => 'Risultato de la riçerca',
'searchresults-title'              => 'Risultati de la riçerca de "$1"',
'searchresulttext'                 => 'Par verghe piassè informassion su la riçerca interna de {{SITENAME}}, varda [[{{MediaWiki:Helppage}}|Riçerca in {{SITENAME}}]].',
'searchsubtitle'                   => 'Te ghè sercà \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|tute le pàxene che taca con "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|tute le pàxene che punta a "$1"]])',
'searchsubtitleinvalid'            => "Te ghè sercà '''$1'''",
'noexactmatch'                     => "'''La pàxena \"\$1\" no ła esiste.''' Te pol [[:\$1|crearla desso]].",
'noexactmatch-nocreate'            => "'''No ghe xe nissuna pagina con titolo \"\$1\".'''",
'toomanymatches'                   => 'Xe stà catà massa corispondense, par piaser próa a modificar la richiesta.',
'titlematches'                     => 'Nei titołi de łe voçi',
'notitlematches'                   => 'Nissuna corispondensa catà nei titoli de le pagine',
'textmatches'                      => 'Corispondense nel testo de le pagine',
'notextmatches'                    => 'Nissuna corispondensa catà nei testi de le pagine',
'prevn'                            => 'precedenti $1',
'nextn'                            => 'sucessivi $1',
'prevn-title'                      => '{{PLURAL:$1|Risultato precedente|$1 risultati precedenti}}',
'nextn-title'                      => '{{PLURAL:$1|Risultato sucessivo|$1 risultati sucessivi}}',
'shown-title'                      => 'Mostra {{PLURAL:$1|un risultato|$1 risultati}} par pàxena',
'viewprevnext'                     => 'Varda ($1) ($2) ($3).',
'searchmenu-legend'                => 'Opzion de riserca',
'searchmenu-exists'                => "*Pàxena '''[[$1]]'''",
'searchmenu-new'                   => "'''Crèa la pàxena \"[[:\$1]]\" su sta wiki!'''",
'searchhelp-url'                   => 'Help:Ajuto',
'searchmenu-prefix'                => "[[Special:PrefixIndex/$1|Varda tute le pàxene co' sto prefisso]]",
'searchprofile-articles'           => 'Pàxene de contenuti',
'searchprofile-project'            => 'Pàxene de projeto e de ajuto',
'searchprofile-images'             => 'File',
'searchprofile-everything'         => 'Tuto quanto',
'searchprofile-advanced'           => 'Avansada',
'searchprofile-articles-tooltip'   => 'Serca in $1',
'searchprofile-project-tooltip'    => 'Serca in $1',
'searchprofile-images-tooltip'     => 'Serca file',
'searchprofile-everything-tooltip' => 'Serca dapartuto (conprese le pàxene de discussion)',
'searchprofile-advanced-tooltip'   => 'Serca nei namespace personalixài',
'search-result-size'               => '$1 ({{PLURAL:$2|una parola|$2 parole}})',
'search-result-score'              => 'Rilevansa: $1%',
'search-redirect'                  => '(rimando $1)',
'search-section'                   => '(sezion $1)',
'search-suggest'                   => 'Fòrsi te sercavi: $1',
'search-interwiki-caption'         => 'Progeti fradèi',
'search-interwiki-default'         => '$1 risultati:',
'search-interwiki-more'            => '(piassè)',
'search-mwsuggest-enabled'         => 'con sugerimenti',
'search-mwsuggest-disabled'        => 'sensa sugerimenti',
'search-relatedarticle'            => 'Ligà',
'mwsuggest-disable'                => 'Disabilita sugerimenti AJAX',
'searchrelated'                    => 'ligà',
'searchall'                        => 'tuti',
'showingresults'                   => "Qua de soto vien mostrà al massimo {{PLURAL:$1|'''1''' risultato|'''$1''' risultati}} a partir dal nùmaro '''$2'''.",
'showingresultsnum'                => "Qua soto ghe xe {{PLURAL:$3|'''1''' risultato|'''$3''' risultati}} a partir da #'''$2'''.",
'showingresultstotal'              => "De seguito vien mostrà {{PLURAL:$4|el risultato '''$1'''|i risultati da '''$1''' a '''$2'''}} su un totale de '''$3'''",
'showingresultsheader'             => "{{PLURAL:$5|Risultato '''$1''' de '''$3'''|Risultati '''$1 - $2''' de '''$3'''}} par '''$4'''",
'nonefound'                        => "'''Ocio''': la riçerca la vien fata in automatico solo in çerti namespace. Se te voli sercar tra tuti i contenuti (conprese pagine de discussion, template, etc.) zónteghe ''all:'' davanti al testo che te serchi, o senò specifica el namespace in cui sercar.",
'search-nonefound'                 => 'La riserca no la gà catà gnente che corisponda ai criteri de riserca.',
'powersearch'                      => 'Riçerca avansada',
'powersearch-legend'               => 'Riçerca avansada',
'powersearch-ns'                   => 'Serca in tei namespace:',
'powersearch-redir'                => 'Elenca i reindirissamenti',
'powersearch-field'                => 'Serca par',
'search-external'                  => 'Riçerca esterna',
'searchdisabled'                   => 'La riçerca interna de {{SITENAME}} no la xe ativa; par intanto te pol proár a doparar un motore de riçerca esterno come Google. (Nota però che i contenuti de {{SITENAME}} presenti in sti motori i podarìa èssar mìà agiornà.)',

# Quickbar
'qbsettings'               => 'Settaggio barra menu',
'qbsettings-none'          => 'Nessun',
'qbsettings-fixedleft'     => 'Fisso a sinistra',
'qbsettings-fixedright'    => 'Fisso a destra',
'qbsettings-floatingleft'  => 'Fluttuante a sinistra',
'qbsettings-floatingright' => 'Fluttuante a destra',

# Preferences page
'preferences'                   => 'Preferense',
'mypreferences'                 => 'le me preferense',
'prefs-edits'                   => 'Nùmaro de modifiche:',
'prefsnologin'                  => 'No te ghè eseguìo el login',
'prefsnologintext'              => 'Te ghè da aver eseguìo el <span class="plainlinks">[{{fullurl:Special:UserLogin|returnto=$1}} login] par poder personalixare le to preferense.',
'changepassword'                => 'Cambia ła password',
'prefs-skin'                    => 'Aspeto grafico',
'skin-preview'                  => 'Anteprima',
'prefs-math'                    => 'Formułe matematiche',
'datedefault'                   => 'Nissuna preferensa',
'prefs-datetime'                => 'Data e ora',
'prefs-personal'                => 'Profiło utente',
'prefs-rc'                      => 'Ultime modifeghe',
'prefs-watchlist'               => 'Tegnùi de òcio',
'prefs-watchlist-days'          => 'Nùmaro de giòrni da far védar nei osservati speciali:',
'prefs-watchlist-days-max'      => '(massimo 7 zorni)',
'prefs-watchlist-edits'         => 'Nùmaro de modifiche da far védar con le funzion avanzade:',
'prefs-watchlist-edits-max'     => '(nùmaro massimo: 1000)',
'prefs-misc'                    => 'Preferense varie',
'prefs-resetpass'               => 'Cànbia password',
'prefs-email'                   => 'Preferense e-mail',
'prefs-rendering'               => 'Aspeto grafico',
'saveprefs'                     => 'Salva le preferense',
'resetprefs'                    => 'Reinposta le preferense',
'restoreprefs'                  => 'Ripristina le inpostassion predefinìe',
'prefs-editing'                 => 'Dimension de la casela de modifica',
'prefs-edit-boxsize'            => 'Dimension de la finestra de modìfega.',
'rows'                          => 'Righe:',
'columns'                       => 'Cołone:',
'searchresultshead'             => 'Riçerca',
'resultsperpage'                => 'Nùmaro de risultati par pàxena:',
'contextlines'                  => 'Righe de testo par ciascun risultato',
'contextchars'                  => 'Caratteri par linea:',
'stub-threshold'                => 'Valor minimo par i <a href="#" class="stub">colegamenti ai stub</a>:',
'recentchangesdays'             => 'Nùmaro de giòrni da mostrar ne le ultime modifiche:',
'recentchangesdays-max'         => '($1 {{PLURAL:$1|zorno|zorni}} massimo)',
'recentchangescount'            => 'Nùmaro de modìfeghe da far védar (valor predefinìo):',
'prefs-help-recentchangescount' => 'Questo include i ùltimi canbiamenti, el stòrico de le pàxene e i registri.',
'savedprefs'                    => 'Le to preferense łe xè stà salvae.',
'timezonelegend'                => 'Fuso orario:',
'localtime'                     => 'Ora locale:',
'timezoneuseserverdefault'      => "Dòpara l'ora del server",
'timezoneuseoffset'             => 'Altro (speçifica difarensa)',
'timezoneoffset'                => 'Difarensa¹:',
'servertime'                    => 'Ora del server:',
'guesstimezone'                 => "Dòpara l'ora del to browser",
'timezoneregion-africa'         => 'Africa',
'timezoneregion-america'        => 'Merica',
'timezoneregion-antarctica'     => 'Antartide',
'timezoneregion-arctic'         => 'Artide',
'timezoneregion-asia'           => 'Asia',
'timezoneregion-atlantic'       => 'Oceano Atlantico',
'timezoneregion-australia'      => 'Australia',
'timezoneregion-europe'         => 'Europa',
'timezoneregion-indian'         => 'Oceano Indian',
'timezoneregion-pacific'        => 'Oceano Pacifico',
'allowemail'                    => 'Consenti la ricezion de e-mail da altri utenti<sup>1</sup>',
'prefs-searchoptions'           => 'Opsioni de riçerca',
'prefs-namespaces'              => 'Namespace',
'defaultns'                     => 'Çerca in sti namespace se non diversamente specificà:',
'default'                       => 'predefinìo',
'prefs-files'                   => 'Imagini',
'prefs-custom-css'              => 'CSS personalixà',
'prefs-custom-js'               => 'JS personalixà',
'prefs-reset-intro'             => 'Te pol doparar sta pagina par riportar le to preferense a quele predefinìe.
Sta operassion no la pol èssar anulà.',
'prefs-emailconfirm-label'      => "Conferma de l'e-mail:",
'prefs-textboxsize'             => 'Dimension de la casèla de modifica',
'youremail'                     => 'La to e-mail',
'username'                      => 'Nome utente',
'uid'                           => 'ID utente:',
'prefs-memberingroups'          => 'Menbro {{PLURAL:$1|del grupo|dei grupi}}:',
'prefs-registration'            => 'Data de registrassion:',
'yourrealname'                  => 'El to vero nome:',
'yourlanguage'                  => 'Lengua:',
'yourvariant'                   => 'Variante de linguaggio:',
'yournick'                      => 'Firma:',
'badsig'                        => 'Erór ne ła firma non standard, verifica i tag HTML.',
'badsiglength'                  => 'La to firma la xe massa longa.
La gà da verghe al massimo $1 {{PLURAL:$1|caràtere|caràteri}}.',
'yourgender'                    => 'Sesso:',
'gender-unknown'                => 'Mia speçificà',
'gender-male'                   => 'Mas-cio',
'gender-female'                 => 'Fémena',
'prefs-help-gender'             => "Opzional: doparà par l'indicassion del gènare dal software. Sta informassion la sarà visìbile da tuti.",
'email'                         => 'Indirizo e-mail',
'prefs-help-realname'           => 'Indicar el proprio nome vero no xe obligatorio; se te siegli de inserirlo, el vegnarà doparà par atribuir la paternità dei contenuti invià.',
'prefs-help-email'              => "L'inserimento del proprio indirizo e-mail no'l xe obligatorio, ma el te consente de farte spedir la password se te te la desménteghi.
In più te pol anca farte contatar da altri tramite la to pagina personale o la pagina de discussion, sensa bisogno de rivelarghe la to identità.",
'prefs-help-email-required'     => "Xe richiesto l'indirizo e-mail.",
'prefs-info'                    => 'Informassion de base',
'prefs-i18n'                    => 'Internassionalisassion',
'prefs-signature'               => 'Firma',
'prefs-dateformat'              => 'Formato de la data',
'prefs-timeoffset'              => 'Ore de difarensa',
'prefs-advancedediting'         => 'Preferense avansade',
'prefs-advancedrc'              => 'Preferense avansade',
'prefs-advancedrendering'       => 'Preferense avansade',
'prefs-advancedsearchoptions'   => 'Preferense avansade',
'prefs-advancedwatchlist'       => 'Preferense avansade',
'prefs-display'                 => 'Preferense de visualixassion',
'prefs-diffs'                   => 'Difarense',

# User rights
'userrights'                  => 'Gestion dei parmessi relativi ai utenti',
'userrights-lookup-user'      => 'Gestion de i gruppi utente',
'userrights-user-editname'    => 'Inserir el nome utente:',
'editusergroup'               => 'Modifica grupi utente',
'editinguser'                 => "Modifica dei diriti assegnà a l'utente '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'    => 'Modifica grupi utente',
'saveusergroups'              => 'Salva grupi utente',
'userrights-groupsmember'     => 'Apartien ai grupi:',
'userrights-groups-help'      => "Se pol modificar i grupi a cui l'utente l'è assegnà.
* Na casela de spunta selezionà la indica l'apartenenza de l'utente al grupo.
* Na casela de spunta deselezionà la indica la so mancata apartenenza al grupo.
* N'asterisco (*) l'indica che no te pol cavar un utente da un grupo na olta che te l'è zontà, o viceversa.",
'userrights-reason'           => 'Motivo de la modifica:',
'userrights-no-interwiki'     => 'No te ghè i parmessi necessari par modificar i diriti dei utenti su altri siti.',
'userrights-nodatabase'       => "El database $1 no l'esiste mìa o no l'è un database local.",
'userrights-nologin'          => "Par assegnarghe diriti ai utenti te ghè da [[Special:UserLogin|efetuar l'acesso]] come aministrador.",
'userrights-notallowed'       => 'No te ghè i parmessi necessari par assegnarghe diriti ai utenti.',
'userrights-changeable-col'   => 'Grupi che te pol canbiar',
'userrights-unchangeable-col' => 'Grupi che no te pol canbiar',

# Groups
'group'               => 'Grupo:',
'group-user'          => 'Utenti',
'group-autoconfirmed' => 'Utenti autoconvalidà',
'group-bot'           => 'Bot',
'group-sysop'         => 'Aministradori',
'group-bureaucrat'    => 'Burocrati',
'group-suppress'      => 'Supervisioni',
'group-all'           => '(utenti)',

'group-user-member'          => 'Utente',
'group-autoconfirmed-member' => 'Utente autoconvalidà',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Aministrador',
'group-bureaucrat-member'    => 'Burocrate',
'group-suppress-member'      => 'Supervision',

'grouppage-user'          => '{{ns:project}}:Utenti',
'grouppage-autoconfirmed' => '{{ns:project}}:Utenti autoconvalidà',
'grouppage-bot'           => '{{ns:project}}:Bot',
'grouppage-sysop'         => '{{ns:project}}:Aministradori',
'grouppage-bureaucrat'    => '{{ns:project}}:Burocrati',
'grouppage-suppress'      => '{{ns:project}}:Supervision',

# Rights
'right-read'                  => 'Lèzi pagine',
'right-edit'                  => 'Modifica pagine',
'right-createpage'            => 'Crea pagine',
'right-createtalk'            => 'Crea pagine de discussion',
'right-createaccount'         => 'Crea account utente novi',
'right-minoredit'             => 'Segna le modifiche come picenine',
'right-move'                  => 'Sposta pagine',
'right-move-subpages'         => 'Sposta le pagine insieme co le so sotopagine',
'right-move-rootuserpages'    => 'Sposta le pàxene prinsipài dei utenti',
'right-movefile'              => 'Sposta file',
'right-suppressredirect'      => 'Scancela un redirect co te sposti na pagina a quel titolo lì',
'right-upload'                => 'Carga file',
'right-reupload'              => 'Sorascrivi un file esistente',
'right-reupload-own'          => 'Sorascrivi un file esistente cargà dal stesso utente',
'right-reupload-shared'       => 'Sorascrivi localmente un file presente in tel deposito multimedial condiviso',
'right-upload_by_url'         => 'Carga un file da un indirisso URL',
'right-purge'                 => 'Neta la cache del sito par na çerta pagina senza bisogno de conferma',
'right-autoconfirmed'         => 'Modifica pagine semi-protete',
'right-bot'                   => 'Da tratar come fusse un processo automatico',
'right-nominornewtalk'        => "Fà in maniera che le modifiche picenine a le pagine de discussion no le faga scatar l'avviso de messaggio novo",
'right-apihighlimits'         => 'Dòpara i limiti superiori ne le query API',
'right-writeapi'              => "Doparar l'API par la modifica de la wiki",
'right-delete'                => 'Scancela pagine',
'right-bigdelete'             => 'Scancela pagine con cronologie longhe',
'right-deleterevision'        => 'Scondi version specifiche de le pagine',
'right-deletedhistory'        => 'Varda i record scancelà de la cronologia, ma senza el testo associà a lori',
'right-browsearchive'         => 'Visualizza pagine scancelae',
'right-undelete'              => 'Recupera na pagina',
'right-suppressrevision'      => 'Rivarda e recupera version sconte',
'right-suppressionlog'        => 'Varda i registri privati',
'right-block'                 => 'Bloca le modifiche da parte de altri utenti',
'right-blockemail'            => 'Inpedìssighe a un utente de mandar e-mail',
'right-hideuser'              => 'Bloca un nome utente, scondéndolo al publico',
'right-ipblock-exempt'        => "Scavalca i blochi de l'IP, i auto-blochi e i blochi de grupi de IP",
'right-proxyunbannable'       => 'Salta via i blochi sui proxy',
'right-protect'               => 'Canbia i livèi de protezion',
'right-editprotected'         => 'Modifica pagine protete',
'right-editinterface'         => "Modifica l'interfacia utente",
'right-editusercssjs'         => 'Modifica i file CSS e JS de altri utenti',
'right-rollback'              => "Anula in prèssia le modifiche fate da l'ultimo utente su na pagina particolar.",
'right-markbotedits'          => 'Segna modifiche specifiche come bot',
'right-noratelimit'           => 'Mìa sogeto al limite de azioni',
'right-import'                => 'Inporta pagine da altre wiki',
'right-importupload'          => 'Inporta pagine da un caricamento de file',
'right-patrol'                => 'Segna le modifiche come verificà',
'right-autopatrol'            => 'Segna automaticamente le modifiche come verificà',
'right-patrolmarks'           => 'Dòpara le funsionalità de patugliamento dei ultimi canbiamenti',
'right-unwatchedpages'        => "Mostra na lista de pagine mìa tegnue d'ocio",
'right-trackback'             => 'Spedissi un trackback',
'right-mergehistory'          => 'Fondi insieme la cronologia de le pagine',
'right-userrights'            => "Modifica tuti quanti i diriti de l'utente",
'right-userrights-interwiki'  => 'Modifica i diriti de utenti de altri siti wiki',
'right-siteadmin'             => 'Bloca e desbloca el database',
'right-reset-passwords'       => 'Reinposta la password de altri utenti',
'right-override-export-depth' => 'Esporta le pàxene, includendo le pàxene ligàe fin a na profondità de 5',

# User rights log
'rightslog'      => 'Diriti dei utenti',
'rightslogtext'  => 'Sto qua el xe el registro de le modifiche ai diriti assegnà ai utenti.',
'rightslogentry' => "gà modificà l'apartenenza de $1 dal grupo $2 al grupo $3",
'rightsnone'     => '(nissun)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'lèxar sta pàxena',
'action-edit'                 => 'modifegar sta pàxena',
'action-createpage'           => 'crear pàxene nove',
'action-createtalk'           => 'crear pàxene de discussion',
'action-createaccount'        => 'crear sta utensa',
'action-minoredit'            => 'segnar sta modìfega come picenina',
'action-move'                 => 'spostar sta pàxena',
'action-move-subpages'        => 'spostar sta pàxena e le so sotopàxene',
'action-move-rootuserpages'   => 'spostar le pàxene prinsipài dei utenti',
'action-movefile'             => 'sposta sto file',
'action-upload'               => 'cargar sto file',
'action-reupload'             => 'sorascrìvar sto file zà esistente',
'action-reupload-shared'      => 'sorascrìvar sto file su un archivio condiviso',
'action-upload_by_url'        => 'cargar sto file da un indirisso URL',
'action-writeapi'             => 'doparar le API in scritura',
'action-delete'               => 'scancelar sta pàxena',
'action-deleterevision'       => 'scancelar sta version',
'action-deletedhistory'       => 'vardar la cronologia scancelà de sta pàxena',
'action-browsearchive'        => 'sercar pàxene scancelè',
'action-undelete'             => 'recuperar sta pàxena',
'action-suppressrevision'     => 'rivardar e ripristinar le modìfeghe sconte',
'action-suppressionlog'       => 'vardar sto registro privato',
'action-block'                => 'blocar sto utente in scritura',
'action-protect'              => 'canbiar i livèi de protession par sta pàxena',
'action-import'               => "inportar sta pàxena da n'antra wiki",
'action-importupload'         => 'inportar sta pàxena tramite caricamento da file',
'action-patrol'               => 'segnar le modìfeghe dei altri utenti come verificàe',
'action-autopatrol'           => 'segnar le to modìfeghe come verificàe',
'action-unwatchedpages'       => "vardar la lista de pàxene mia tegnùe d'ocio",
'action-trackback'            => 'mandar na trackback',
'action-mergehistory'         => 'unir la cronologia de sta pàxena',
'action-userrights'           => 'canbiar tuti i diriti dei utenti',
'action-userrights-interwiki' => 'canbiar i diriti dei utenti su altre wiki',
'action-siteadmin'            => 'blocar e desblocar el database',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|modìfega|modìfeghe}}',
'recentchanges'                     => 'Ùltimi canbiamenti',
'recentchanges-legend'              => 'Opzioni ultime modìfeghe',
'recentchangestext'                 => 'Sta pàxena la presenta łe ultime modifeghe aportàe ai contenuti de el sito.',
'recentchanges-feed-description'    => 'Sto feed qua el riporta le modifiche piassè recenti ai contenuti del sito.',
'rcnote'                            => "Qua soto ghe xe {{PLURAL:$1|l'ultimo cambiamento|i ultimi '''$1''' canbiamenti}} ne {{PLURAL:$2|l'ultimo giòrno|i ultimi '''$2''' giòrni}}; i dati i xe agiornà a le ore $5 del $4.",
'rcnotefrom'                        => " Qui di seguito sono elencate le modifiche da '''$2''' (fino a '''$1''').",
'rclistfrom'                        => 'Mostra łe modìfeghe aportàe a partir da $1',
'rcshowhideminor'                   => '$1 le modìfeghe picenine',
'rcshowhidebots'                    => '$1 i bot',
'rcshowhideliu'                     => '$1 i utenti registrài',
'rcshowhideanons'                   => '$1 i utenti anonimi',
'rcshowhidepatr'                    => '$1 łe modifeghe controłae',
'rcshowhidemine'                    => '$1 łe me modìfeghe',
'rclinks'                           => 'Mostra le $1 modìfeghe pi reçenti fate nei ultimi $2 giorni<br />$3',
'diff'                              => 'dif',
'hist'                              => 'cron',
'hide'                              => 'scondi',
'show'                              => 'mostra',
'minoreditletter'                   => 'm',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[osservà da {{PLURAL:$1|un utente|$1 utenti}}]',
'rc_categories'                     => 'Limita a le categorie (separà da "|")',
'rc_categories_any'                 => 'Qualsiasi',
'newsectionsummary'                 => '/* $1 */ sezion nova',
'rc-enhanced-expand'                => 'Mostra detagli (ghe vole JavaScript)',
'rc-enhanced-hide'                  => 'Scondi detagli',

# Recent changes linked
'recentchangeslinked'          => 'Modìfeghe ligà a sta pàxena',
'recentchangeslinked-title'    => 'Modìfeghe ligà a "$1"',
'recentchangeslinked-noresult' => 'Nel periodo specificà no ghe xe stà nissuna modifica a le pagine colegà.',
'recentchangeslinked-summary'  => "Sta pagina speciale la fa védar le modifiche piassè recenti a le pagine ligà a quela specificà (o a le pagine contegnù ne la categoria specificà).
Le pagine che te stè tegnendo d'ocio sui [[Special:Watchlist|osservati speciali]] le xe in '''grasseto'''.",
'recentchangeslinked-page'     => 'Nome de la pagina:',
'recentchangeslinked-to'       => 'Mostra le modìfeghe su le pagine ligà a la pagina data.',

# Upload
'upload'                      => 'Carga sù un file',
'uploadbtn'                   => 'Carga el file',
'reupload'                    => 'Carga da novo',
'reuploaddesc'                => 'Lassa pèrdar el caricamento e torna al modulo de caricamento',
'upload_directory_missing'    => 'La cartèla de caricamento ($1) no la esiste mìa e no la pode vegner creàda dal browser web.',
'upload_directory_read_only'  => "El server web no l'è bon de scrìvar ne la directory de caricamento ($1).",
'uploaderror'                 => 'Eror nel caricamento',
'uploadtext'                  => "Par cargar novi file, dopara el modulo qua soto. 
Par védar o çercar i file zà caricà, consulta la [[Special:FileList|lista dei file caricà]]. I caricamenti de file te pol védarli nel [[Special:Log/upload|registro dei caricamenti]], le scancelasion nel [[Special:Log/delete|registro de le scancelasion]].

Par métar un file drento de na pagina, te ghè da inserir un colegamento fato come uno de sti qua:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>''' par doparar la version conpleta de sto file
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|testo alternativo]]</nowiki></tt>'''par inserir sto file co na larghessa de 200 pixel in te un riquadro a sinistra con 'testo alternativo' come descrission
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>''' par inserir diretamente un colegamento al file sensa che el se véda in te la pagina",
'upload-permitted'            => 'Tipi de file consentìi: $1.',
'upload-preferred'            => 'Tipi de file consiglià: $1.',
'upload-prohibited'           => 'Tipi de file mìa consentìi: $1.',
'uploadlog'                   => 'File caricai',
'uploadlogpage'               => 'Registro dei file caricai',
'uploadlogpagetext'           => 'Qua se cata la lista dei ultimi files caricà.
Varda la [[Special:NewFiles|galerìa dei file nóvi]] par na vision de insieme.',
'filename'                    => 'Nome del file',
'filedesc'                    => 'Somario',
'fileuploadsummary'           => 'Somario:',
'filereuploadsummary'         => 'Canbiamenti al file:',
'filestatus'                  => 'Informazion sul copyright:',
'filesource'                  => 'Fonte:',
'uploadedfiles'               => 'Files caricà su {{SITENAME}}',
'ignorewarning'               => "Ignora l'avertimento e salva istesso el file.",
'ignorewarnings'              => 'Ignora i messagi de avertimento del sistema',
'minlength1'                  => 'El nome del file el ga da contegner almanco un caràtere.',
'illegalfilename'             => 'El nome file "$1" el contien caràteri che no xè permessi nei titoli de le pagine. Par piaser, rinomina el file e próa a ricaricarlo.',
'badfilename'                 => 'El nome de el file el xè stà convertio in "$1".',
'filetype-badmime'            => 'No xe consentìo de cargar file de tipo MIME "$1".',
'filetype-bad-ie-mime'        => 'No se pode cargar sto file, parché da Internet Explorer el vegnarìa rilevà come "$1", che xe un tipo de file disativà e potensialmente pericoloso.',
'filetype-unwanted-type'      => "Cargar file de tipo '''\".\$1\"''' xe sconsiglià. {{PLURAL:\$3|El tipo de file consiglià el|I tipi de file consiglià i}} xe \$2.",
'filetype-banned-type'        => "Cargar file de tipo '''\".\$1\"''' no xe mìa consentìo. {{PLURAL:\$3|El tipo de file consentìo el|I tipi de file consentìi i}} xe \$2.",
'filetype-missing'            => 'El file no\'l gà nissuna estension (ad es. ".jpg").',
'large-file'                  => 'Se racomanda de no superar mìa le dimension de $1 par ciascun file; sto file el xe grando $2.',
'largefileserver'             => 'El file el supera le dimension consentìe da la configurazion del server.',
'emptyfile'                   => 'El file che te ghè caricà el xè aparentemente vodo. Podarìa èssar par un eror nel nome del file. Par piaser controla se te vol dal bon caricar sto file.',
'fileexists'                  => "Un file con sto nome el esiste de xà, par piaser controła '''<tt>$1</tt>''' se no te sì sicuro de volerlo sovrascrìvar.",
'filepageexists'              => "La pagina de descrizion de sto file la xe zà stà creà a l'indirizo '''<tt>$1</tt>''', anca se no ghe xe gnancora un file co sto nome. La descrizion de l'ogeto inserìa in fase de caricamento no la vegnarà mìa fora su la pagina de discussion. Par far sì che l'ogeto el conpaja su la pagina de discussion, sarà necessario modificarla a man",
'fileexists-extension'        => "Ghe xe zà un file co un nome che ghe someja a sto qua:<br />
Nome del file cargà: '''<tt>$1</tt>'''<br />
Nome del file esistente: '''<tt>$2</tt>'''<br />
Par piaser siegli un nome difarente.",
'fileexists-thumb'            => "<center>'''File zà esistente'''</center>",
'fileexists-thumbnail-yes'    => "El file cargà el pararìa èssar el risultato de n'anteprima ''(thumbnail)''. Verifica, par confronto, el file '''<tt>$1</tt>'''.<br />
Se se trata de la stessa imagine, ne le dimension originali, no xe necessario caricarghene altre anteprime.",
'file-thumbnail-no'           => "El nome del file el scuminsia con '''<tt>$1</tt>'''.
Pararìà quindi che el fusse el risultato de n'anteprima ''(thumbnail)''.
Se se dispone de l'imagine ne la risoluzion originale, se prega di cargarla. In caso contrario, se prega de canbiar el nome del file.",
'fileexists-forbidden'        => 'Un file con sto nome el esiste xà, e no se pode scrìvarghe insima.
Se te vol cargar istesso el to file, par piaser torna indrio e cànbia el nome da darghe al file. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => "Un file con sto nome l'esiste de xà ne l'archivio de risorse multimediałi condivixe.
Se te vol cargar el file istesso, par piaser torna indrio e canbia el nome che te vol darghe al file. [[File:$1|thumb|center|$1]]",
'file-exists-duplicate'       => 'Sto file el xe un duplicato {{PLURAL:$1|del seguente file|dei seguenti file}}:',
'file-deleted-duplicate'      => "Un file preciso identico de sto file ([[$1]]) el xe stà zà scancelà in precedensa. Te dovaressi darghe n'ociada a la cronologia scancelà prima de cargarlo de novo.",
'successfulupload'            => 'Caricamento conpletà',
'uploadwarning'               => 'Avixo de caricamento',
'savefile'                    => 'Salva file',
'uploadedimage'               => 'gà cargà "[[$1]]"',
'overwroteimage'              => 'gà cargà na version nova de "[[$1]]"',
'uploaddisabled'              => 'Semo spiacenti, ma el caricamento de file el xe tenporaneamente sospeso.',
'uploaddisabledtext'          => "El caricamento dei file no'l xe mìa ativo.",
'php-uploaddisabledtext'      => 'El caricamento de file tramite PHP el xe disabilità. Contròla la configurassion de file_uploads.',
'uploadscripted'              => 'Sto file contegne codexe HTML o de script, che podaria essere interpretà eroneamente da un browser web.',
'uploadcorrupt'               => 'El file el xe coróto o el gà na estension mìa giusta. Controla el file e dopo próa de novo a cargarlo.',
'uploadvirus'                 => 'Sto file contegne un virus! Detagli: $1',
'sourcefilename'              => 'Nome del file de origine:',
'destfilename'                => 'Nome del file de destinazion:',
'upload-maxfilesize'          => 'Dimension massima del file: $1',
'watchthisupload'             => "Tien d'ocio sto file",
'filewasdeleted'              => 'Un file con sto nome xè stato xà caricà e scancełà in passato. Verifica $1 prima de caricarlo de novo.',
'upload-wasdeleted'           => "'''Ocio: te stè cargando un file che in precedenza l'era stà scancelà.'''

Verifica par piaser se xe el caso de continuare col caricamento de sto file.
Par to comodità qua ghe xe la registrazion de la scancelazion:",
'filename-bad-prefix'         => "El nome del file che te sì drio cargar el scuminsia con '''\"\$1\"''', che el xe un nome non-descritivo tipicamente assegnà automaticamente da le fotocàmare digitali. Par piaser siegli un nome piassè descritivo par el to file.",

'upload-proto-error'      => 'Protocòl mìa giusto',
'upload-proto-error-text' => 'Par el caricamento remoto bisogna specificar URL che scuminsia con <code>http://</code> opure <code>ftp://</code>.',
'upload-file-error'       => 'Eror interno',
'upload-file-error-text'  => 'Se gà verificà un eror interno durante la creazion de un file tenporaneo sul server.
Par piaser, contatar un [[Special:ListUsers/sysop|aministrador]].',
'upload-misc-error'       => 'Eror non identificà par el caricamento',
'upload-misc-error-text'  => '!Se gà verificà un eror mìa identificà durante el caricamento del file.
Par piaser, verifica che la URL la sia giusta e acessibile e próa da novo.
Se el problema el persiste, contatar un [[Special:ListUsers/sysop|aministrador]].',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL mìa ragiungibile',
'upload-curl-error6-text'  => 'Inpossibile ragiùngiar la URL specificà. Verifica che la URL la sia scrita giusta e che el sito in question el sia ativo.',
'upload-curl-error28'      => 'Tenpo scadùo par el caricamento',
'upload-curl-error28-text' => 'El sito remoto el gà messo massa tenp par rispóndar. Verifica che el sito el sia ativo, speta qualche minuto e próa da novo, eventualmente in un momento de manco tràfico.',

'license'            => "Licenza d'uso:",
'nolicense'          => 'Nissuna liçensa indicà',
'license-nopreview'  => '(Anteprima mìa disponibile)',
'upload_source_url'  => ' (na URL coreta e acessibile)',
'upload_source_file' => ' (un file sul to computer)',

# Special:ListFiles
'listfiles-summary'     => "Sta pagina speciale la fa védar tuti i file caricài.
I file caricài piessè de reçente i vien mostrà a l'inizio de la lista.
Par modificar l'ordinamento, struca su l'intestazion de la colona presièlta.",
'listfiles_search_for'  => 'Çerca imagini par nome:',
'imgfile'               => 'file',
'listfiles'             => 'Imagini',
'listfiles_date'        => 'Data',
'listfiles_name'        => 'Nome',
'listfiles_user'        => 'Utente',
'listfiles_size'        => 'Dimension in byte',
'listfiles_description' => 'Descrizion',
'listfiles_count'       => 'Versioni',

# File description page
'filehist'                  => 'Cronologia del file',
'filehist-help'             => 'Maca su un grupo data/ora par védar el file come el se presentava nel momento indicà.',
'filehist-deleteall'        => 'scancela tuto',
'filehist-deleteone'        => 'scancela',
'filehist-revert'           => 'ripristina',
'filehist-current'          => 'corente',
'filehist-datetime'         => 'Data/Ora',
'filehist-thumb'            => 'Miniatura',
'filehist-thumbtext'        => 'Miniatura par la version del $1',
'filehist-nothumb'          => 'Nissuna miniatura',
'filehist-user'             => 'Utente',
'filehist-dimensions'       => 'Dimensioni',
'filehist-filesize'         => 'Dimension del file',
'filehist-comment'          => 'Ogeto',
'imagelinks'                => 'Colegamenti al file',
'linkstoimage'              => '{{PLURAL:$1|La pagina seguente la|Le $1 pàxene seguenti le}} riciama sto file:',
'linkstoimage-more'         => 'Piassè de $1 {{PLURAL:$1|pagina la ponta|pagine le ponta}} a sto file.
De seguito xe elencà solo {{PLURAL:$1|la prima pagina che ponta|le prime $1 pagine che ponta}} a sto file.
Se pode védar un [[Special:WhatLinksHere/$2|elenco par intiero]].',
'nolinkstoimage'            => 'Nissuna pàxena la punta a sta imagine.',
'morelinkstoimage'          => 'Varda i [[Special:WhatLinksHere/$1|altri colegamenti]] verso sto file.',
'redirectstofile'           => '{{PLURAL:$1|El file seguente el|I $1 file seguenti i}} redirige verso sto file:',
'duplicatesoffile'          => '{{PLURAL:$1|El file seguente el xe un dopion|I $1 file seguenti i xe dei dopioni}} de sto file ([[Special:FileDuplicateSearch/$2|ulteriori detagli]]):',
'sharedupload'              => 'Sto file qua el vien da $1 e se pol dopararlo anca su altri projeti.',
'sharedupload-desc-there'   => 'Sto file el vien da $1 e se pode dopararlo su altri projeti.
Consulta la [$2 pàxena de descrission del file] par ulteriori informassion.',
'sharedupload-desc-here'    => 'Sto file el vien da $1 e se pode dopararlo su altri projeti.
Qua soto vien mostrà la descrission presente in te la [$2 pàxena de descrission del file].',
'filepage-nofile'           => 'No ghe xe nissun file co sto nome.',
'filepage-nofile-link'      => 'Struca qua par cargarlo.',
'uploadnewversion-linktext' => 'Carga na version nova de sto file',
'shared-repo-from'          => 'da $1',
'shared-repo'               => 'un archivio condiviso',

# File reversion
'filerevert'                => 'Ripristina $1',
'filerevert-legend'         => 'Ripristina file',
'filerevert-intro'          => "Te stè par ripristinar el file '''[[Media:$1|$1]]''' a la [versione $4 del $2, $3].",
'filerevert-comment'        => 'Comento:',
'filerevert-defaultcomment' => 'Xe stà ripristinà la version del $1, $2',
'filerevert-submit'         => 'Ripristina',
'filerevert-success'        => "'''El file [[Media:$1|$1]]''' el xe stà ripristinà a la [$4 version del $2, $3].",
'filerevert-badversion'     => 'No esiste mìa version locali precedenti del file col timestamp richiesto.',

# File deletion
'filedelete'                  => 'Scancela $1',
'filedelete-legend'           => 'Scancela el file',
'filedelete-intro'            => "Te stè par scancelar el file '''[[Media:$1|$1]]''' insieme co' tuta la so cronologia.",
'filedelete-intro-old'        => "Te sì drio scancelar la version de '''[[Media:$1|$1]]''' del [$4 $3, $2].",
'filedelete-comment'          => 'Motivo:',
'filedelete-submit'           => 'Scancela',
'filedelete-success'          => "El file '''$1''' el xe stà scancelà.",
'filedelete-success-old'      => "La version del $3, $2 del file '''[[Media:$1|$1]]''' la xe stà scancelà.",
'filedelete-nofile'           => 'No esiste un file $1.',
'filedelete-nofile-old'       => "In archivio no ghe xe version de '''$1''' con le carateristiche indicà",
'filedelete-otherreason'      => 'Altra motivazion o motivazion agiuntiva:',
'filedelete-reason-otherlist' => 'Altra motivazion',
'filedelete-reason-dropdown'  => '*Motivazion piassè comuni par la scancelazion
** Violazion de copyright
** File duplicà',
'filedelete-edit-reasonlist'  => 'Modifica le motivazion par la scancelazion',

# MIME search
'mimesearch'         => 'Çerca in base al tipo MIME',
'mimesearch-summary' => 'Sta pagina la consente de filtrare i file in base al tipo MIME. Inserissi la stringa de riçerca ne la forma tipo/sototipo, ad es. <tt>image/jpeg</tt>.',
'mimetype'           => 'Tipo MIME:',
'download'           => 'descarga',

# Unwatched pages
'unwatchedpages' => 'Pàxene mìa osservàe',

# List redirects
'listredirects' => 'Elenco dei redirect',

# Unused templates
'unusedtemplates'     => 'Template mìa doparà',
'unusedtemplatestext' => 'In sta pagina vien elencà tuti i template (pagine del namespace {{ns:template}}) che no xe inclusi in nissuna pagina. Prima de scancelarli xe mejo verificar che i singoli template no i gabia altri colegamenti entranti.',
'unusedtemplateswlh'  => 'altri cołegamenti',

# Random page
'randompage'         => 'Pàxena a caso',
'randompage-nopages' => 'Nissuna pàxena in tel namespace "$1".',

# Random redirect
'randomredirect'         => 'Un redirect a caso',
'randomredirect-nopages' => 'No ghe xe nissun rimando in tel namespace "$1".',

# Statistics
'statistics'                   => 'Statìsteghe',
'statistics-header-pages'      => 'Statìsteghe relative a le pàxene',
'statistics-header-edits'      => 'Statìsteghe relative a i canbiamenti',
'statistics-header-views'      => 'Statìsteghe relative a le visualizassion',
'statistics-header-users'      => 'Statistiche dei utenti',
'statistics-articles'          => 'Pàxene de contenuti',
'statistics-pages'             => 'Pàxene',
'statistics-pages-desc'        => 'Tute quante le pàxene de la wiki, conprese le pàxene de discussion, i rimandi, ecc.',
'statistics-files'             => 'File cargà',
'statistics-edits'             => "Modifiche a scuminsiar da l'istalassion de {{SITENAME}}",
'statistics-edits-average'     => 'Canbiamenti in media par pàxena',
'statistics-views-total'       => 'Visualizasion totali',
'statistics-views-peredit'     => 'Visualizassion par modifica',
'statistics-jobqueue'          => '[http://www.mediawiki.org/wiki/Manual:Job_queue Cóa dei processi] da far girar in background',
'statistics-users'             => '[[Special:ListUsers|Utenti]] registrà',
'statistics-users-active'      => 'Utenti atìvi',
'statistics-users-active-desc' => "Utenti che gà fato almanco un'azion in {{PLURAL:$1|tel'ultimo zorno|in tei ultimi $1 zorni}}",
'statistics-mostpopular'       => 'Pagine piassè visità',

'disambiguations'      => 'Pàxene de disanbiguazion',
'disambiguationspage'  => 'Template:Disambigua',
'disambiguations-text' => "Le pagine ne la lista che segue le contien dei colegamenti a '''pagine de disanbiguazion''' e no a l'argomento a cui le dovarìà far riferimento.<br />
Vien considerà pagine de disanbiguazion tute quele che contien i template elencà in [[MediaWiki:Disambiguationspage]]",

'doubleredirects'            => 'Redirect dopi',
'doubleredirectstext'        => '<b>Ocio:</b> Stà lista la pode talvolta contegnere dei risultati mìa giusti. Podaria magari sucédar parché ghe fusse del testo agiuntivo o dei colegamenti dopo el tag #REDIRECT.<br /> Ogni riga la contegne i colegamenti al primo ed al secondo redirect, oltre a la prima riga de testo del secondo redirect che de solito contegne el "reale" articolo de destinassion, quelo al quale anca el primo redirect dovaria puntar.',
'double-redirect-fixed-move' => '[[$1]] xe stà spostà, desso el xe solo un rimando a [[$2]]',
'double-redirect-fixer'      => 'Coretòr de redirect',

'brokenredirects'        => 'Redirect mìa giusti',
'brokenredirectstext'    => 'I seguenti rimandi i punta a pàxene che no esiste:',
'brokenredirects-edit'   => '(modifica)',
'brokenredirects-delete' => '(scancela)',

'withoutinterwiki'         => 'Pagine che no gà interwiki',
'withoutinterwiki-summary' => 'Le pagine indicà de seguito no le gà colegamenti a le version in altre lengue:',
'withoutinterwiki-legend'  => 'Prefisso',
'withoutinterwiki-submit'  => 'Mostra',

'fewestrevisions' => 'Voçi con manco revision',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|byte}}',
'ncategories'             => '$1 {{PLURAL:$1|categoria|categorie}}',
'nlinks'                  => '$1 {{PLURAL:$1|colegamento|colegamenti}}',
'nmembers'                => '$1 {{PLURAL:$1|elemento|elementi}}',
'nrevisions'              => '$1 {{PLURAL:$1|revision|revision}}',
'nviews'                  => '$1 {{PLURAL:$1|visita|visite}}',
'specialpage-empty'       => "Sto raporto no'l contien nissun risultato.",
'lonelypages'             => 'Pàxene solitarie',
'lonelypagestext'         => 'Le pagine indicà de seguito no le gà colegamenti o trasclusioni che vegna da altre pagine de {{SITENAME}}.',
'uncategorizedpages'      => 'Pàxene prive de categorie',
'uncategorizedcategories' => 'Categorie prive de categorie',
'uncategorizedimages'     => 'File che no gà na categoria',
'uncategorizedtemplates'  => 'Template che no gà categorie',
'unusedcategories'        => 'Categorie mìa doparàe',
'unusedimages'            => 'Imagini mìa doparàe',
'popularpages'            => 'Pàxene pì viste',
'wantedcategories'        => 'Categorie richieste',
'wantedpages'             => 'Pàxene pì richieste',
'wantedpages-badtitle'    => 'Titolo mia valido nel grupo de risultati: $1',
'wantedfiles'             => 'File domandà',
'wantedtemplates'         => 'Template richiesti',
'mostlinked'              => 'Pàxene piassè puntà',
'mostlinkedcategories'    => 'Categorie piassè riciamae',
'mostlinkedtemplates'     => 'Template piassè doparà',
'mostcategories'          => 'Articołi con piassè categorie',
'mostimages'              => 'File piassè riciamà',
'mostrevisions'           => 'Voçi con piassè revixión',
'prefixindex'             => 'Indice de le vóxe par létere inissiali',
'shortpages'              => 'Pàxene curte',
'longpages'               => 'Pàxene longhe',
'deadendpages'            => 'Pàxene senza uscita',
'deadendpagestext'        => 'Le pagine indicà de seguito no le gà colegamenti verso altre pagine de {{SITENAME}}.',
'protectedpages'          => 'Pagine protete',
'protectedpages-indef'    => 'Solo le protezion infinìe',
'protectedpages-cascade'  => 'Solo protezion ricorsive',
'protectedpagestext'      => 'De seguito vien presentà un elenco de pagine protete, che no se pol modificar o spostar',
'protectedpagesempty'     => 'Al momento no ghe xe pagine protete',
'protectedtitles'         => 'Titoli proteti',
'protectedtitlestext'     => 'No se pol crear pagine coi titoli elencà de seguito',
'protectedtitlesempty'    => 'Al momento no ghe xe titoli proteti coi parametri specificà.',
'listusers'               => 'Elenco dei utenti',
'listusers-editsonly'     => 'Fà védar sol che i utenti che gà fato dei contributi',
'listusers-creationsort'  => 'Òrdena par data de creassion',
'usereditcount'           => '$1 {{PLURAL:$1|contributo|contributi}}',
'usercreated'             => 'Creà el $1 a le $2',
'newpages'                => 'Pàxene nove',
'newpages-username'       => 'Nome utente:',
'ancientpages'            => 'Pàxene pì vece',
'move'                    => 'Sposta',
'movethispage'            => 'Sposta sta pagina',
'unusedimagestext'        => "Par piaser tien conto che altri siti web i podarìa realizar colegamenti ai file doparando diretamente l'URL; quindi sti file i podarìa essar in uso, anca se no i se cata ne l'elenco.",
'unusedcategoriestext'    => 'Le pàxene de łe categorie indicàe de seguito łe xè stà creàe ma no le contien nissuna pàxena né sotocategoria.',
'notargettitle'           => 'Dati mancanti',
'notargettext'            => 'No te ghè indicà na pagina o un utente su cui eseguir sta operazion.',
'nopagetitle'             => 'Pagina de destinassion non esistente',
'nopagetext'              => 'La pagina de destinassion che ti gà indicà no la esiste mìa.',
'pager-newer-n'           => '{{PLURAL:$1|1 piassè reçente|$1 piassè reçenti}}',
'pager-older-n'           => '{{PLURAL:$1|1 manco reçente|$1 manco reçenti}}',
'suppress'                => 'Supervision',

# Book sources
'booksources'               => 'Fonti librarie',
'booksources-search-legend' => 'Riçerca de fonti librarie',
'booksources-go'            => 'Và',
'booksources-text'          => 'De seguito vien presentà un elenco de colegamenti verso siti foresti che vende libri novi e usài, atraverso i quali se pol otegner piassè informazioni sul testo çercà.',
'booksources-invalid-isbn'  => "The given ISBN number does not appear to be valid; check for errors copying from the original source.

El nùmaro ISBN inserìo no'l xe mia valido: controla de novo se te l'è scrito justo.",

# Special:Log
'specialloguserlabel'  => 'Utente:',
'speciallogtitlelabel' => 'Titolo:',
'log'                  => 'Registro',
'all-logs-page'        => 'Tuti i registri',
'alllogstext'          => 'Vixualixazion unificà de tuti i registri disponibili de {{SITENAME}}. 
Te podi restrénzar i criteri de riçerca selezionando el tipo de registro, el nome utente, o la pàxena interessà (ocio che sti ultimi du i distingue tra majuscolo e minuscolo).',
'logempty'             => "El registro no'l contien mìa elementi corispondenti a la riçerca.",
'log-title-wildcard'   => 'Riçerca dei titoli che scuminsia con',

# Special:AllPages
'allpages'          => 'Tute łe pàxene',
'alphaindexline'    => 'da $1 a $2',
'nextpage'          => 'Pàxena dopo ($1)',
'prevpage'          => 'Pagina precedente ($1)',
'allpagesfrom'      => 'Mostra łe pàxene scominsiando da:',
'allpagesto'        => 'Fà védar le pagine fin a:',
'allarticles'       => 'Tute le pàxene',
'allinnamespace'    => 'Tute łe pàxene ($1 namespace)',
'allnotinnamespace' => 'Tute łe pàxene (via de quele nel namespace $1)',
'allpagesprev'      => 'Preçedenti',
'allpagesnext'      => 'Prossime',
'allpagessubmit'    => 'Và',
'allpagesprefix'    => 'Mostra łe pàxene che scuminsia con:',
'allpagesbadtitle'  => "El titolo indicà par la pagina no'l xe mìa valido o el contien prefissi interlengua o interwiki. El podarìa inoltre contegner uno o più caràteri che no se pole doparar nei titoli.",
'allpages-bad-ns'   => 'El namespace "$1" no l\'esiste mìa su {{SITENAME}}.',

# Special:Categories
'categories'                    => 'Categorie',
'categoriespagetext'            => '{{PLURAL:$1|Sta categoria qua soto la|Ste categorie qua soto le}} gà drento pagine o file multimediài.
Le [[Special:UnusedCategories|categorie mìa doparà]] no le vien mìa fate védar.
Varda anca le [[Special:WantedCategories|categorie domandà]].',
'categoriesfrom'                => 'Mostra le categorie tacando da :',
'special-categories-sort-count' => 'ordina par nùmaro',
'special-categories-sort-abc'   => 'ordina alfabeticamente',

# Special:DeletedContributions
'deletedcontributions'       => 'Contributi utente scancelà',
'deletedcontributions-title' => 'Contributi utente scancelà',

# Special:LinkSearch
'linksearch'       => 'Colegamenti foresti',
'linksearch-pat'   => 'Pattern de riçerca:',
'linksearch-ns'    => 'Namespace:',
'linksearch-ok'    => 'Çerca',
'linksearch-text'  => 'Se pol doparar dei metacaràteri, par es. "*.wikipedia.org".<br />
Protocòli suportè: <tt>$1</tt>',
'linksearch-line'  => '$1 presente ne la pagina $2',
'linksearch-error' => "I metacaràteri i pode vegner doparài solo a l'inizio del nome de l'host.",

# Special:ListUsers
'listusersfrom'      => 'Mostra i utenti tacando da:',
'listusers-submit'   => 'Mostra',
'listusers-noresult' => 'Nissun utente el risponde ai criteri inpostà.',

# Special:Log/newusers
'newuserlogpage'              => 'Registro utenti novi',
'newuserlogpagetext'          => 'Sto qua el xè el registro dei novi utenti registrai.',
'newuserlog-byemail'          => 'password spedìa par e-mail',
'newuserlog-create-entry'     => 'se gà pena registrà',
'newuserlog-create2-entry'    => 'ga registrà el nome utente $1',
'newuserlog-autocreate-entry' => 'Account creà automaticamente',

# Special:ListGroupRights
'listgrouprights'                      => 'Diriti dei grupi utenti',
'listgrouprights-summary'              => 'Sta qua la xe na lista dei grupi de utenti definìi su sta wiki, coi diriti asocià a ognuno.
Se pol consultar anca dele altre [[{{MediaWiki:Listgrouprights-helppage}}|informassion in pi]] sui diriti individuali.',
'listgrouprights-group'                => 'Grupo',
'listgrouprights-rights'               => 'Diriti',
'listgrouprights-helppage'             => 'Help:Diriti dei grupi',
'listgrouprights-members'              => '(lista dei menbri)',
'listgrouprights-addgroup'             => 'Pode zontar {{PLURAL:$2|al grupo|ai grupi}}: $1',
'listgrouprights-removegroup'          => 'Pode cavar {{PLURAL:$2|dal grupo|dai grupi}}: $1',
'listgrouprights-addgroup-all'         => 'Pode zontar tuti i grupi',
'listgrouprights-removegroup-all'      => 'Pode cavar tuti i grupi',
'listgrouprights-addgroup-self'        => 'Poder zontar la propria utensa in {{PLURAL:$2|te un grupo|più grupi}}: $1',
'listgrouprights-removegroup-self'     => 'Poder cavar la propria utensa da {{PLURAL:$2|un grupo|dei grupi}}: $1',
'listgrouprights-addgroup-self-all'    => 'Pode zontar la propria utensa in tuti i grupi',
'listgrouprights-removegroup-self-all' => 'Pode cavar la propria utensa da tuti i grupi',

# E-mail user
'mailnologin'      => 'Nissun indirizo a cui mandarghe el messagio',
'mailnologintext'  => 'Par inviare messagi e-mail ad altri utenti xè neçessario [[Special:UserLogin|açedere al sito]] e aver registrà un indirisso vałido ne łe proprie [[Special:Preferences|preferense]].',
'emailuser'        => "Scrivi a l'utente",
'emailpage'        => "Scrivi na e-mail a l'utente",
'emailpagetext'    => 'Te podi usar el modulo chi soto par mandare na e-mail a sto utente.
La e-mail che te ghè indicà ne le [[Special:Preferences|to preferense]] la vegnarà fora nel canpo "Da" de la mail, così che el destinatario el possa rispóndarte a ti diretamente.',
'usermailererror'  => "L'ogeto mail el gà restituìo l'eror:",
'defemailsubject'  => 'Messagio da {{SITENAME}}',
'noemailtitle'     => 'Nissun indirisso e-mail',
'noemailtext'      => "Sto utente no'l gà indicà nissuna casela e-mail valida.",
'nowikiemailtitle' => 'Posta elétronega mia parmessa',
'nowikiemailtext'  => 'Sto utente el ga sielto de no riçévar e-mail da i altri utenti.',
'email-legend'     => "Màndeghe na e-mail a n'altro utente de {{SITENAME}}",
'emailfrom'        => 'Da:',
'emailto'          => 'A:',
'emailsubject'     => 'Ogeto:',
'emailmessage'     => 'Messajo:',
'emailsend'        => 'Invia',
'emailccme'        => 'Màndeme na copia al me indirizo.',
'emailccsubject'   => 'Copia del messagio invià a $1: $2',
'emailsent'        => 'E-mail invià',
'emailsenttext'    => 'La to e-mail la xè stà invià.',
'emailuserfooter'  => 'Sta e-mail la xe stà mandà da $1 a $2 \'traverso la funsion "Manda na e-mail a l\'utente" su {{SITENAME}}.',

# Watchlist
'watchlist'            => "Pagine tegnùe d'ocio",
'mywatchlist'          => 'tegnùi de òcio',
'watchlistfor'         => "(da l'utente '''$1''')",
'nowatchlist'          => "No te ghè indicà pagine da tegner d'ocio.",
'watchlistanontext'    => "Per vardar e modifegar l'ełenco de i osservati speciałi bisogna $1.",
'watchnologin'         => 'Acesso non efetuà',
'watchnologintext'     => 'Te ghè prima da far el [[Special:UserLogin|login]] par modificar la to lista de osservati speciali.',
'addedwatch'           => 'Xontà ai tòi Osservati Speciali',
'addedwatchtext'       => "La pàxena \"[[:\$1]]\" l'è stà xontà a la to [[Special:Watchlist|lista de osservati speciali]].
Le future modìfeghe a sta pagina e a la relativa pagina de discussion le sarà elencàe qui, e la paxena la vegnarà fora in '''grasseto''' ne la pàxena de le [[Special:RecentChanges|ultime modìfeghe]] par èssar pì fàsile da tegner d'ocio.",
'removedwatch'         => 'Cavà da la lista dei Osservati Speciali',
'removedwatchtext'     => 'La pàxena "[[:$1]]" la xè stà cavà da ła to łista de le [[Special:Watchlist|pàxene tegnùe de ocio]].',
'watch'                => "Tien d'ocio",
'watchthispage'        => "Tien d'ocio sta pagina",
'unwatch'              => "No sta tegner d'ocio",
'unwatchthispage'      => "Desmeti de tegner d'ocio",
'notanarticle'         => 'Sta pagina no la xè na voçe',
'notvisiblerev'        => 'La revision la xe stà scancelà',
'watchnochange'        => "Nissun de i to ojeti osservai l'è stà modificà nel periodo mostrà.",
'watchlist-details'    => '{{PLURAL:$1|$1 pagina|$1 pagine}} tegnù de ocio, senza contar le pagine de discussion.',
'wlheader-enotif'      => '* Xe ativà la notifica via e-mail.',
'wlheader-showupdated' => "* Le pàxene che xe stà modificà da la to ultima visita le xe evidensià en '''grasseto'''",
'watchmethod-recent'   => 'controło de łe modifeghe reçenti par i osservati speciałi',
'watchmethod-list'     => 'controło de i osservati speciałi par modifeghe reçenti',
'watchlistcontains'    => 'La lista de i osservati speciałi la contien {{PLURAL:$1|una pagina|$1 pagine}}.',
'iteminvalidname'      => "Problemi con la voçe '$1', nome mìa vałido...",
'wlnote'               => "Qua soto te cati {{PLURAL:$1|l'ultimo canbiamento|i ultimi '''$1''' canbiamenti}} ne {{PLURAL:$2|l'ultima ora|le ultime '''$2''' ore}}.",
'wlshowlast'           => 'Mostra le ultime $1 ore $2 giòrni $3',
'watchlist-options'    => 'Preferense par i osservati speciali',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Zonto ai osservati speciali...',
'unwatching' => 'Cavo dai osservati speciali...',

'enotif_mailer'                => 'Sistema de notifica via e-mail de {{SITENAME}}',
'enotif_reset'                 => 'Segna tute łe pàxene visitae',
'enotif_newpagetext'           => 'Sta qua la xe na nova pàxena.',
'enotif_impersonal_salutation' => 'Utente de {{SITENAME}}',
'changed'                      => 'cambià',
'created'                      => 'Creà',
'enotif_subject'               => 'La pagina $PAGETITLE de {{SITENAME}} la xe stà $CHANGEDORCREATED da $PAGEEDITOR',
'enotif_lastvisited'           => 'Varda $1 par tute le modifiche da la to ultima visita.',
'enotif_lastdiff'              => 'Varda $1 par visualizar la modifica.',
'enotif_anon_editor'           => 'utente anonimo $1',
'enotif_body'                  => 'Caro $WATCHINGUSERNAME,

ła pàxena $PAGETITLE de {{SITENAME}} la xè stà $CHANGEDORCREATED el $PAGEEDITDATE da $PAGEEDITOR, varda $PAGETITLE_URL par ła version atuałe.

$NEWPAGE

Somario del redator: $PAGESUMMARY $PAGEMINOREDIT

Contatta el redator:
mail: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

No ghe sarà altre notifiche in caso de ulteriori canbiamenti, a manco che ti no te visiti sta pàxena. Te podi anca reinpostar l\'avixo de notifica par tuti i osservati speciałi de ła to łista.

             El to amichevole sistema de notifica de {{SITENAME}}

--
Par canbiar łe inpostassion de i to osservati speciałi, visita
{{fullurl:Special:Watchlist/edit}}

Par riscontri e ulteriore assistensa:
{{fullurl:Help:Ciacołe}}',

# Delete
'deletepage'             => 'Scanceła pàxena',
'confirm'                => 'Conferma',
'excontent'              => "el contenuto xera: '$1'",
'excontentauthor'        => "el contenuto l'era: '$1' (e l'unico contribudor l'era '$2')",
'exbeforeblank'          => "El contenuto prima de lo svodamento xera: '$1'",
'exblank'                => "ła pàxena l'era voda",
'delete-confirm'         => 'Scancela "$1"',
'delete-legend'          => 'Scancela',
'historywarning'         => 'Ocio: La pàxena che te stè par scancełar la gà na cronołogia:',
'confirmdeletetext'      => "Te stè par scancełar permanentemente da el database na pàxena o na imagine, insieme a tuta la so cronołogia.
Par piaser, conferma che l'è to intenzion proçedere a tałe scancełazion, conferma che te ghè piena consapevołeza de łe conseguense de la to azion, e conferma che la to azion l'è pienamente otenperante a łe regołe stabilíe in
[[{{MediaWiki:Policy-url}}]].",
'actioncomplete'         => 'Azión conpletà',
'actionfailed'           => 'Azion mia riussìa',
'deletedtext'            => 'La pàxena "<nowiki>$1</nowiki>" l\'è stà scancełà. Varda $2 par un ełenco de łe pàxene scancełae de reçente.',
'deletedarticle'         => 'gà scancełà "[[$1]]"',
'suppressedarticle'      => 'sconto "[[$1]]"',
'dellogpage'             => 'Registro de scancełassión',
'dellogpagetext'         => 'Qui de seguito ghe xe un ełenco de łe pàxene scancełae de reçente.',
'deletionlog'            => 'Registro de scancełasión',
'reverted'               => 'Ripristinà la version preçedente',
'deletecomment'          => 'Motivo de ła scancełazion:',
'deleteotherreason'      => 'Altra motivazion o motivazion agiuntiva:',
'deletereasonotherlist'  => 'Altra motivazion',
'deletereason-dropdown'  => "*Motivazion piassè comuni par la scancelazion
** Richiesta de l'autor
** Violazion de copyright
** Vandalismo",
'delete-edit-reasonlist' => 'Modifica le motivazion par la scancelazion',
'delete-toobig'          => 'La cronologia de sta pagina la xe longa assè (oltre $1 {{PLURAL:$1|revision|revisioni}}). La so scancelazion la xe stà limità par evitar de crear acidentalmente dei problemi de funzionamento al database de {{SITENAME}}.',
'delete-warning-toobig'  => 'La cronologia de sta pagina le xe longa assè (oltre $1 {{PLURAL:$1|revision|revisioni}}). La so scancelazion la pode crear dei problemi de funzionamento al database de {{SITENAME}}; procedi con cautela.',

# Rollback
'rollback'         => 'Anula le modifiche',
'rollback_short'   => 'Tira indrìo',
'rollbacklink'     => 'tira indrìo',
'rollbackfailed'   => 'Ripristino mìa riussìo',
'cantrollback'     => "No xè mia possibiłe tornar a na versión precedente: l'ultima modifica la xè stà aportà da l'unico utente che gà laorà a sto articoło.",
'alreadyrolled'    => "No xè mia possibile efetuar el ripristino de [[:$1]] da [[User:$2|$2]] ([[User talk:$2|discussion]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]); qualcun altro gà xà modificà o efetuà el ripristino de sta voçe.

L'ultima modefega l'è stà fata da [[User:$3|$3]] ([[User talk:$3|discussion]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).",
'editcomment'      => "El comento a la modifica el xera: \"''\$1''\".",
'revertpage'       => 'Anułàe łe modifeghe de [[Special:Contributions/$2|$2]] ([[User talk:$2|discussion]]), riportà a ła version preçedente de [[User:$1|$1]]',
'rollback-success' => 'Anulà le modifiche de $1; riportà a la version precedente de $2.',
'sessionfailure'   => "Se gà verificà un problema ne la session che identifica l'acesso; el sistema, par precauzion, no'l gà mìa eseguìo el comando che te ghè dato. Torna a la pagina precedente col boton 'Indrìo' del to browser, ricarica la pagina e ripróa da novo.",

# Protect
'protectlogpage'              => 'Registro de protezión',
'protectlogtext'              => 'De seguito xe elencàe le azion de protezion e sbloco de le pagine.',
'protectedarticle'            => 'gà proteto "[[$1]]"',
'modifiedarticleprotection'   => 'gà modificà el livel de protezion de "[[$1]]"',
'unprotectedarticle'          => 'gà sblocà "[[$1]]"',
'movedarticleprotection'      => 'gà canbià la protesion da "[[$2]]" a "[[$1]]"',
'protect-title'               => 'Canbia el livèl de protezion par "$1"',
'prot_1movedto2'              => '[[$1]] spostà a [[$2]]',
'protect-legend'              => 'Conferma la protezion',
'protectcomment'              => 'Motivo de ła protezion:',
'protectexpiry'               => 'Scadensa:',
'protect_expiry_invalid'      => 'Scadensa mìa valida.',
'protect_expiry_old'          => 'Scadensa zà passà.',
'protect-unchain'             => 'Sbloca i permessi de spostamento',
'protect-text'                => "Sto modulo qua el consente de védar e modificar el livel de protezion par la pagina '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "No se pol mìa canbiar i livèi de protezion co ghe xe un bloco ativo. Le inpostazion corenti par la pagina le xe '''$1''':",
'protect-locked-dblock'       => "No se pol canbiar i livèi de protezion durante un bloco del database.
Le inpostazion corenti par la pagina le xe '''$1''':",
'protect-locked-access'       => "No te ghè i parmessi necessari par modificar i livèi de protezion de la pagina.
Le impostazion atuali par la pagina le xe '''$1''':",
'protect-cascadeon'           => 'Al momento sta pagina la xe blocà parché la xe inclusa {{PLURAL:$1|ne la pagina indicà de seguito, par la quale|ne le pagine indichè de seguito, par le quali}} xe ativa la protezion ricorsiva. Se pol modificar el livel de protezion individual de la pagina, ma le inpostazion derivanti da la protezion ricorsiva no le sarà mìa modificà.',
'protect-default'             => 'Autoriza tuti i utenti',
'protect-fallback'            => 'Ghe vole el parmesso de "$1"',
'protect-level-autoconfirmed' => 'Bloca i utenti novi o mia registrà',
'protect-level-sysop'         => 'Solo aministradori',
'protect-summary-cascade'     => 'ricorsiva',
'protect-expiring'            => 'scadensa: $1 (UTC)',
'protect-expiry-indefinite'   => 'infinìo',
'protect-cascade'             => "Protezion ricorsiva (l'estende la protezion a tute le pagine incluse in sta qua).",
'protect-cantedit'            => 'No te pol canbiar i livèi de protezion par la pagina, parché no te ghè mìa i parmessi necessari par modifegar la pagina stessa.',
'protect-othertime'           => 'Altra durata:',
'protect-othertime-op'        => 'altra durata',
'protect-existing-expiry'     => 'Scadensa de desso: $2, $3',
'protect-otherreason'         => 'Altri motivi:',
'protect-otherreason-op'      => 'altri motivi',
'protect-dropdown'            => '*Motivi piessè comuni de protession
** Guere de modifica (edit war)
** Inserimenti ripetùi de spam
** Pàxena archivià
** Pàxena doparà assè
** Vandalismi ripetùi',
'protect-edit-reasonlist'     => 'Cànbia i motivi de la protesion',
'protect-expiry-options'      => '1 ora:1 hour,1 zòrno:1 day,1 stimana:1 week,2 stimane:2 weeks,1 mese:1 month,3 mesi:3 months,6 mesi:6 months,1 ano:1 year,infinito:infinite',
'restriction-type'            => 'Parmesso:',
'restriction-level'           => 'Livèl de restrizion:',
'minimum-size'                => 'Dimension minima',
'maximum-size'                => 'Dimension massima:',
'pagesize'                    => '(byte)',

# Restrictions (nouns)
'restriction-edit'   => 'Modifega',
'restriction-move'   => 'Spostamento',
'restriction-create' => 'Creazion',
'restriction-upload' => 'Carga',

# Restriction levels
'restriction-level-sysop'         => 'proteta',
'restriction-level-autoconfirmed' => 'semi-proteta',
'restriction-level-all'           => 'tuti i livèi',

# Undelete
'undelete'                     => 'Recupera na pàxena scancełà',
'undeletepage'                 => 'Varda e recupera pàxene scancełàe',
'undeletepagetitle'            => "'''Quel che segue el xe conposto da revision scancelà de [[:$1]]'''.",
'viewdeletedpage'              => 'Varda łe pàxene scancełàe',
'undeletepagetext'             => "{{PLURAL:$1|La pàxena qua de sèvito la xe stà scancelà, ma la ghe xe 'ncora in archivio e pertanto se pole 'ncora recuperarla|Le $1 pàxene qua de sèvito le xe stà scancelè, ma le ghe xe 'ncora in archivio e pertanto se pole 'ncora recuperarle}}.
L'archivio el vien svodà periodicamente.",
'undelete-fieldset-title'      => 'Recupera version',
'undeleteextrahelp'            => "Par recuperar la storia de la pàxena par intiero, lassa tute łe casełe desełezionàe e struca '''''Ripristina'''''. 
Par efetuar un ripristino sełetivo, seleziona łe casełe corispondenti a łe revixion da ripristinar e struca '''''Ripristina'''''. Strucando '''''Reset''''' vegnarà deselezionàe tute łe casełe e svodà el posto par el comento.",
'undeleterevisions'            => '{{PLURAL:$1|Una revision|$1 revision}} in archivio',
'undeletehistory'              => 'Recuperando sta pàxena, tute łe so revixion le vegnarà inserìe da novo ne ła rełativa cronołogia.
Se dopo ła scancełazion xè stà creà na nova pàxena col stesso titoło, łe revixion recuperà le sarà inserìe ne ła cronołogia preçedente.',
'undeleterevdel'               => "El ripristino no'l vegnarà mìa fato se el determina la scancelazion parziale de la version corente de la pagina o del file interessà. In sto caso, te ghè da cavar el segno de spunta o l'oscuramento da le revisioni scancelà piassè reçenti.",
'undeletehistorynoadmin'       => 'La pàxena la xè stà scancełà. El motivo de ła scancełazion el xè indicà de seguito, insieme ai dati de i utenti che i gavea modifegà ła pàxena prima de ła scancełazion. El testo contegnù ne łe revixion scancełàe i pol védarlo solo i aministradori.',
'undelete-revision'            => 'Revision scancelà de la pagina $1 (inserìa su $4 el $5) de $3:',
'undeleterevision-missing'     => "Revision mìa valida o mancante. O el colegamento no'l xe mìa giusto, opure la revision la xe stà zà ripristinà o eliminà da l'archivio.",
'undelete-nodiff'              => 'No xe stà catà nissuna revision precedente.',
'undeletebtn'                  => 'RIPRISTINA!',
'undeletelink'                 => 'varda/ripristina',
'undeletereset'                => 'Reinposta',
'undeleteinvert'               => 'Inverti selession',
'undeletecomment'              => 'Comento:',
'undeletedarticle'             => 'gà recuperà "[[$1]]"',
'undeletedrevisions'           => '{{PLURAL:$1|Una revision recuperà|$1 revision recuperà}}',
'undeletedrevisions-files'     => '{{PLURAL:$1|Una revision|$1 revision}} e $2 file recuperà',
'undeletedfiles'               => '{{PLURAL:$1|Un file recuperà|$1 file recuperà}}',
'cannotundelete'               => "El recupero no'l xè riussìo: qualchedun altro el podarià aver xà recuperà ła pàxena.",
'undeletedpage'                => "<big>'''$1 la xè stà recuperà'''</big>

Consulta el [[Special:Log/delete|registro de le scancełassion]] par vardare łe scancełassion e i recuperi pì reçenti.",
'undelete-header'              => 'Varda el [[Special:Log/delete|registro de le scancelazion]] par védar le scancelazion piassè reçenti.',
'undelete-search-box'          => 'Çerca ne le pagine scancelà',
'undelete-search-prefix'       => 'Mostra le pagine el cui titolo scuminsia con:',
'undelete-search-submit'       => 'Çerca',
'undelete-no-results'          => "Nissuna pagina corispondente ne l'archivio de le scancelazion.",
'undelete-filename-mismatch'   => "No se pode anular la scancelazion de la revision del file con data/ora $1: el nome del file no'l corisponde.",
'undelete-bad-store-key'       => "No se pol anular la scancelazion de la revision del file con data/ora $1: el file no'l xera disponibile prima de la scancelazion.",
'undelete-cleanup-error'       => 'Eror ne la scancelazion del file de archivio non doparà "$1".',
'undelete-missing-filearchive' => "No se pol ripristinar l'ID $1 de l'archivio file in quanto no'l ghe xe mìà nel database. El podarìa èssar stà zà ripristinà.",
'undelete-error-short'         => 'Eror nel ripristino del file: $1',
'undelete-error-long'          => 'Se gà verificà dei erori nel tentativo de anular la scancelazion del file:

$1',
'undelete-show-file-confirm'   => 'Sito sicuro che te vol vardar na revision del file "<nowiki>$1</nowiki>" scancelà da $2 el $3',
'undelete-show-file-submit'    => 'Sì',

# Namespace form on various pages
'namespace'      => 'Namespace:',
'invert'         => 'inverti ła selessión',
'blanknamespace' => '(Prinsipałe)',

# Contributions
'contributions'       => 'Contributi utente',
'contributions-title' => 'Contributi de $1',
'mycontris'           => 'i me contributi',
'contribsub2'         => 'De $1 ($2)',
'nocontribs'          => 'No xe stà catà nissuna modifica che vaga ben par sti critèri.',
'uctop'               => '(ultima par ła pàxena)',
'month'               => 'A partir dal mese (e precedenti):',
'year'                => "A partir da l'ano (e precedenti):",

'sp-contributions-newbies'       => 'Mostra solo i contributi dei utenti novi',
'sp-contributions-newbies-sub'   => 'Par i novi utenti',
'sp-contributions-newbies-title' => 'Contributi dei utenti novi',
'sp-contributions-blocklog'      => 'registro dei blochi',
'sp-contributions-deleted'       => 'contributi utente scancelà',
'sp-contributions-logs'          => 'registri',
'sp-contributions-talk'          => 'discussion',
'sp-contributions-userrights'    => 'gestion dei parmessi relativi ai utenti',
'sp-contributions-search'        => 'Riçerca contributi',
'sp-contributions-username'      => 'Indirizo IP o nome utente:',
'sp-contributions-submit'        => 'Riçerca',

# What links here
'whatlinkshere'            => 'Pàxene che le punta qua',
'whatlinkshere-title'      => 'Pagine che punta a "$1"',
'whatlinkshere-page'       => 'Pagina:',
'linkshere'                => "Ste pagine qua le contien dei colegamenti a '''[[:$1]]''':",
'nolinkshere'              => "Nissuna pagina la contien colegamenti che punta a '''[[:$1]]'''.",
'nolinkshere-ns'           => "No ghe xe pagine che punta a '''[[:$1]]''' nel namespace selezionà.",
'isredirect'               => 'pagina de reindirizamento',
'istemplate'               => 'inclusion',
'isimage'                  => 'colegamento a imagine',
'whatlinkshere-prev'       => '{{PLURAL:$1|precedente|precedenti $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|sucessivo|sucessivi $1}}',
'whatlinkshere-links'      => '← colegamenti',
'whatlinkshere-hideredirs' => '$1 i rimandi',
'whatlinkshere-hidetrans'  => '$1 le inclusion',
'whatlinkshere-hidelinks'  => '$1 i colegamenti',
'whatlinkshere-hideimages' => '$1 colegamenti a imagini',
'whatlinkshere-filters'    => 'Filtri',

# Block/unblock
'blockip'                         => 'Bloca indirisso IP',
'blockip-legend'                  => "Bloca l'utente",
'blockiptext'                     => "Dòpara el moduło qua soto par blocar l'accesso in scritura a un speçifico utente o indirizo IP. El bloco el gà de èssar operà par prevegner ati de vandalismo e in streta osservansa de ła [[{{MediaWiki:Policy-url}}|policy de {{SITENAME}}]]. Speçifica in detałio el motivo del bloco nel canpo seguente (ad es. indicando i titołi de łe pàxene ogeto de vandalismo).",
'ipaddress'                       => 'Indirisso IP (IP Address)',
'ipadressorusername'              => 'Indirizo IP o nome utente',
'ipbexpiry'                       => 'Scadensa',
'ipbreason'                       => 'Motivazión',
'ipbreasonotherlist'              => 'Altra motivazion',
'ipbreason-dropdown'              => '*Motivazion piassè comuni par i blocchi
** Inserimento de informazion false
** Rimozion de contenuti da le pagine
** Colegamenti promozionali a siti foresti
** Inserimento de contenuti privi de senso
** Conportamenti intimidatori o molestie
** Uso indebito de più account
** Nome utente non cònsono',
'ipbanononly'                     => 'Bloca solo utenti anonimi',
'ipbcreateaccount'                => 'Inpedissi la creazion de altri account',
'ipbemailban'                     => "Inpedìsseghe a l'utente de mandar e-mail",
'ipbenableautoblock'              => "Bloca automaticamente l'ultimo indirizo IP doparà da l'utente e i sucessivi con cui vien tentà modifiche",
'ipbsubmit'                       => 'Bloca sto indirisso IP',
'ipbother'                        => 'Altra durata:',
'ipboptions'                      => '2 ore:2 hours,1 giòrno:1 day,3 giòrni:3 days,1 setimana:1 week,2 setimane:2 weeks,1 mese:1 month,3 mesi:3 months,6 mesi:6 months,1 ano:1 year,infinito:infinite',
'ipbotheroption'                  => 'altro',
'ipbotherreason'                  => 'Altri motivi/detagli:',
'ipbhidename'                     => 'Scondi el nome utente da le modìfeghe e da i elenchi.',
'ipbwatchuser'                    => "Tien d'ocio la pagina utente e la pagina de discussion de sto utente",
'ipballowusertalk'                => 'Parmétighe a sto utente de scrìvar su la so pàxena de discussion finché el xe blocà',
'ipb-change-block'                => "Bloca de novo l'utente co ste inpostassion",
'badipaddress'                    => "L'indirisso IP indicà no'l xè mìa coreto.",
'blockipsuccesssub'               => 'Bloco eseguìo',
'blockipsuccesstext'              => "[[Special:Contributions/$1|$1]] l'è sta bloccà.<br />
Varda [[Special:IPBlockList|lista IP bloccati]] par védar tuti i blochi.",
'ipb-edit-dropdown'               => 'Motivi par el bloco',
'ipb-unblock-addr'                => 'Sbloca $1',
'ipb-unblock'                     => 'Sbloca un utente o un indirizo IP',
'ipb-blocklist-addr'              => 'Blochi esistenti par $1',
'ipb-blocklist'                   => 'Elenca i blochi ativi',
'ipb-blocklist-contribs'          => 'Contributi de $1',
'unblockip'                       => 'Sbloca indirizzo IP',
'unblockiptext'                   => 'Dòpara el modulo qua soto par ridarghe el dirito de scritura a un indirizzo IP precedentemente blocà.',
'ipusubmit'                       => 'Cava sto bloco',
'unblocked'                       => "L'utente [[User:$1|$1]] el xe stà sblocà",
'unblocked-id'                    => 'El bloco $1 el xe stà cavà',
'ipblocklist'                     => 'Nomi utenti e indirizi IP blocài',
'ipblocklist-legend'              => 'Cata fora un utente blocà',
'ipblocklist-username'            => 'Nome utente o indirizo IP:',
'ipblocklist-sh-userblocks'       => '$1 i blochi dei utenti registrài',
'ipblocklist-sh-tempblocks'       => '$1 i blochi tenporanei',
'ipblocklist-sh-addressblocks'    => '$1 i blochi dei singoli IP',
'ipblocklist-submit'              => 'Çerca',
'blocklistline'                   => '$1, $2 gà blocà $3 ($4)',
'infiniteblock'                   => 'infinito',
'expiringblock'                   => 'fin al $1 $2',
'anononlyblock'                   => 'solo anonimi',
'noautoblockblock'                => 'gnente bloco automatico',
'createaccountblock'              => 'creazion account blocà',
'emailblock'                      => 'e-mail blocàe',
'blocklist-nousertalk'            => "no'l pol scrivar su la so pàxena de discussion",
'ipblocklist-empty'               => "L'elenco dei blochi el xe vodo.",
'ipblocklist-no-results'          => "L'indirizo IP o nome utente richiesto no'l xe blocà.",
'blocklink'                       => 'bloca',
'unblocklink'                     => 'sbloca',
'change-blocklink'                => 'cànbia bloco',
'contribslink'                    => 'contributi',
'autoblocker'                     => 'Bloccà automaticamente parché el to indirisso IP el xè stà doparà de recente da "[[User:$1|$1]]". La motivassion del bloco de $1 la xe: "$2"',
'blocklogpage'                    => 'Registro dei blochi',
'blocklog-fulllog'                => 'Registro conpleto dei blochi',
'blocklogentry'                   => 'gà blocà [[$1]] par un periodo de $2 $3',
'reblock-logentry'                => "gà canbià le inpostassion del bloco par [[$1]] co' na scadensa de $2 $3",
'blocklogtext'                    => "Sto qua xè un elenco de azioni de bloco e sbloco dei indirizi IP. I indirizi blocai in automatico no i xè mìa elencai. Varda l'[[Special:IPBlockList|elenco dei IP blocà]] par la lista dei indirizi el cui bloco el xè operativo.",
'unblocklogentry'                 => 'gà sblocà $1',
'block-log-flags-anononly'        => 'solo utenti anonimi',
'block-log-flags-nocreate'        => 'creazion account blocà',
'block-log-flags-noautoblock'     => 'bloco automatico disativà',
'block-log-flags-noemail'         => 'e-mail blocàe',
'block-log-flags-nousertalk'      => "no'l pode scrìvar su la so pàxena de discussion",
'block-log-flags-angry-autoblock' => 'bloco automatico avansado ativo',
'block-log-flags-hiddenname'      => 'nome utente sconto',
'range_block_disabled'            => 'La possibilità de blocar intervali de indirizzi IP no la xe ativa al momento.',
'ipb_expiry_invalid'              => 'Tenpo de scadensa mìa valido. Controla el [http://www.gnu.org/software/tar/manual/html_chapter/tar_7.html manual de tar] par la sintassi esatta.',
'ipb_expiry_temp'                 => 'I blochi dei nomi utente sconti i dovarìa essar infiniti.',
'ipb_hide_invalid'                => "No se riesse a scancelar l'utensa; podarìa èssar che la gà massa contributi.",
'ipb_already_blocked'             => 'L\'utente "$1" el xe zà blocà',
'ipb-needreblock'                 => '== Xà blocà ==
$1 xe xà blocà. Vuto canbiar le inpostassion?',
'ipb_cant_unblock'                => 'Eror: Inpossibile catar el bloco con ID $1. El bloco el podarìa èssar zà stà cavà.',
'ipb_blocked_as_range'            => "Eror: L'indirizo IP $1 no'l xe sogeto a bloco individual e no'l pol èssar sblocà. El bloco el xe invesse ativo a livel de l'intervalo $2, che el pol èssar sblocà.",
'ip_range_invalid'                => 'Intervało de indirissi IP mìa vałido.',
'blockme'                         => 'Blòcheme',
'proxyblocker'                    => 'Bloco dei proxy verti',
'proxyblocker-disabled'           => 'Sta funzion la xe disabilità.',
'proxyblockreason'                => 'Sto indirizo IP el xe stà blocà parché el risulta èssar un proxy verto. Se prega de contatar el proprio fornitor de acesso a Internet o el suporto tènico e dirghe de sto grave problema de sicureza.',
'proxyblocksuccess'               => 'Fatto.',
'sorbsreason'                     => 'Sto indirizo IP el xe elencà come proxy verto ne la lista nera DNSBL doparà da {{SITENAME}}.',
'sorbs_create_account_reason'     => 'No se pol crear acessi novi da sto indirizo IP parché el xe elencà come proxy verto ne la lista nera DNSBL doparà da {{SITENAME}}.',
'cant-block-while-blocked'        => 'No se pode blocar altri utenti finché se xe blocài.',

# Developer tools
'lockdb'              => 'Blocca el database',
'unlockdb'            => 'Sbloca el database',
'lockdbtext'          => 'Bloccare il database sospenderà la possibilità per tutti gli Utenti di modificare le pagine o di crearne di nuove, di cambiare le loro preferenze, di modificare le loro liste di Osservati Speciali, ed in genere non consentirà a nessuno di eseguire operazioni che richiedano modifiche del database.<br /><br />
Per cortesia, conferma che questo è effettivamente quanto tu intendi ora effettuare e, soprattutto, che il prima possibile sbloccherai nuovamente il database, ripristinandone la corretta funzionalità, non appena avrai terminato le tue manutenzioni.',
'unlockdbtext'        => 'Desblocando el database se ripristinarà la possibilità par tuti i utenti de modificar le pagine o de crearghine de nove, de canbiar le so preferense, de modificar le so liste de Osservati Speciali, e in genere de eseguir operassion che richieda modifiche del database.
Per piaser, conferma che questo xe efettivamente quel che te vol far.',
'lockconfirm'         => 'Sì, efetivamente intendo, soto la me responsabilità, blocar el database.',
'unlockconfirm'       => ' Sì, efetivamente intendo, soto la me responsabilità, desblocar el database.',
'lockbtn'             => 'Bloca el database',
'unlockbtn'           => 'Sbloca el database',
'locknoconfirm'       => 'No te ghè spuntà la casela de conferma.',
'lockdbsuccesssub'    => 'Blocco de el database eseguio',
'unlockdbsuccesssub'  => 'Sbloco del database eseguìo',
'lockdbsuccesstext'   => 'El database el xe stà blocà.<br />
Tiente in mente de [[Special:UnlockDB|sblocarlo]] co te ghè finìo de far manutenzion.',
'unlockdbsuccesstext' => 'El database de {{SITENAME}} el xe stà sblocà.',
'lockfilenotwritable' => "Inpossibile scrìvar sul file de ''lock'' del database. Ghe vole acesso in scritura a sto file da parte del server web, par blocar e sblocar el database.",
'databasenotlocked'   => "El database no l'è mìa blocà.",

# Move page
'move-page'                    => 'Spostamento de $1',
'move-page-legend'             => 'Spostamento de pàxena',
'movepagetext'                 => "Col modulo qua soto te podi rinominar na pàxena, spostando anca tuta la so cronołogia al nome novo.
El vecio titoło el deventarà automaticamente un rimando (redirect) che punta al novo titoło.
Te podi agiornar automaticamente i rimandi che punta al vecio titolo.
Se te siegli de no farlo, tiente in mente de controłar con cura che no se crea [[Special:DoubleRedirects|dopi redirect]] o [[Special:BrokenRedirects|redirect interoti]].
Resta ne la to responsabilità de controlar che i colegamenti i continua a puntar verso dove i deve dirìgiarse.

Nota ben: la pàxena '''no''' la sarà spostà se ghe fusse xà na voçe col nome novo, a meno che no la sia na pàxena voda o un rimando, e senpre che no la gabia cronologia.
Questo significa che, se te fè un eror, te podi novamente rinominar na pàxena col vecio titoło, ma no te podi sovrascrìvar na pàxena xà esistente.

'''OCIO!'''
Sto canbiamento drastico el podarìa crear contratenpi che no se se speta, specialmente se se tratta de na pàxena molto visità.
Acèrtete de ver ben valutà le conseguenze del spostamento, prima de procédar.",
'movepagetalktext'             => "La corispondente pàxena de discussion la sarà spostà automaticamente insieme a ła pàxena prinsipałe, '''trane che nei seguenti casi:'''
* El spostamento de ła pàxena el xè tra namespace diversi
* In corispondenza del novo titoło ghe xe xà na pàxena de discussion (mìa voda)
* La caseła chi soto la xè stà desełezionà.",
'movearticle'                  => 'Sposta la pàxena:',
'movenotallowed'               => 'No te ghè i parmessi necessari al spostamento de le pagine.',
'movenotallowedfile'           => 'No ti gà i parmessi necessari par spostar file.',
'cant-move-user-page'          => 'No se dispone dei parmessi necessari par spostar le pàxene utente.',
'cant-move-to-user-page'       => 'No se dispone dei parmessi necessari par spostar la pàxena su na pàxena utente (ad ecezion de na sotopàxena utente).',
'newtitle'                     => 'Al novo titoło de:',
'move-watch'                   => "Tien d'ocio",
'movepagebtn'                  => 'Sposta sta pàxena',
'pagemovedsub'                 => 'Spostamento efetuà con sucesso',
'movepage-moved'               => '<big>\'\'\'"$1" la xe stà spostà a "$2"\'\'\'</big>',
'movepage-moved-redirect'      => 'Un reindirissamento el xe stà creà.',
'movepage-moved-noredirect'    => 'La creassion de un reindirissamento la xe stà sopressa.',
'articleexists'                => "Na pàxena con sto nome la existe xà, opure el nome che te ghè sielto no'l xè vałido.
Par piaser, siegli un titoło difarente.",
'cantmove-titleprotected'      => 'No se pol mìa spostar la pagina, in quanto el novo titolo el xe stà proteto par inpedirghene la creazion',
'talkexists'                   => "'''La pagina la xe stà spostà coretamente, ma no s'à mìa podesto spostar la pagina de discussion parché ghe ne xe xà n'altra col stesso nome. Par piaser meti insieme i contenuti de le do pagine a man.'''",
'movedto'                      => 'spostà a',
'movetalk'                     => 'Sposta anca la corispondente pagina de discussion',
'move-subpages'                => 'Sposta tute le sotopagine (fin a $1)',
'move-talk-subpages'           => 'Sposta tute le sotopagine de la pagina de discussion (fin a $1)',
'movepage-page-exists'         => 'La pagina $1 la esiste de zà e no la pode vegner automaticamente sorascrita.',
'movepage-page-moved'          => 'La pagina $1 la xe stà spostà a $2.',
'movepage-page-unmoved'        => "No s'à mìa podesto spostar $1 a $2.",
'movepage-max-pages'           => "Xe stà ragiunto el nùmaro massimo consentìo de $1 {{PLURAL:$1|pagina|pagine}} spostà e nissun'altra la vegnarà spostà in automatico.",
'1movedto2'                    => 'gà spostà [[$1]] a [[$2]]',
'1movedto2_redir'              => 'gà spostà [[$1]] a [[$2]] con un rimando',
'move-redirect-suppressed'     => 'reindirissamento sopresso',
'movelogpage'                  => 'Registro dei spostamenti',
'movelogpagetext'              => 'Lista de pàxene spostàe.',
'movesubpage'                  => '{{PLURAL:$1|Sotopàxena|Sotopàxene}}',
'movesubpagetext'              => 'Sta pàxena la ga $1 {{PLURAL:$1|sotopàxena|sotopàxene}} mostrà qua soto.',
'movenosubpage'                => 'Sta pàxena no la ga sotopàxene.',
'movereason'                   => 'Motivo:',
'revertmove'                   => 'ripristina',
'delete_and_move'              => 'Scanceła e sposta',
'delete_and_move_text'         => '==Scancełassion richiesta==

La voçe specificà come destinassion "[[:$1]]" l\'esiste xà. Vóto scancełarla par proseguir con ło spostamento?',
'delete_and_move_confirm'      => 'Si! Scancèła ła pàxena',
'delete_and_move_reason'       => 'Scancełà par rendere possibile lo spostamento',
'selfmove'                     => 'El novo titoło el xè conpagno del vecio; no se pol spostar ła pàxena su de ela.',
'immobile-source-namespace'    => 'No te pol spostar pàxene in tel namespace "$1"',
'immobile-target-namespace'    => 'No te pol spostar pàxene \'ntel namespace "$1"',
'immobile-target-namespace-iw' => "El colegamento interwiki no'l xe na valida destinassion in do spostar na pàxena.",
'immobile-source-page'         => 'Sta pàxena no la pol vegner spostà.',
'immobile-target-page'         => 'No te pol spostar a sto titolo.',
'imagenocrossnamespace'        => 'No se pol spostar na imagine verso un namespace diverso da quelo de le imagini',
'imagetypemismatch'            => "L'estension nova del file no la corisponde mìa al tipo de file",
'imageinvalidfilename'         => "El nome file de destinassion no'l xe mia valido",
'fix-double-redirects'         => 'Agiorna tuti quanti i redirect che ponta al titolo originàl',
'move-leave-redirect'          => 'Crea un redirect con lo spostamento',
'protectedpagemovewarning'     => "'''Ocio:''' sta pàxena la xe stà blocà in maniera che solo i aministradori i possa spostarla.",
'semiprotectedpagemovewarning' => "'''Ocio:''' Sta pàxena la xe stà blocà in maniera che solo i utenti registrài i possa spostarla.",

# Export
'export'            => 'Esporta pàxene',
'exporttext'        => 'Te podi esportar el testo e modificar ła cronołogia de na speçifica pàxena o de un gruppo de pàxene ragrupae in XML.
Questo el pode in seguito vegner inportà in te n\'altro wiki che dòpara el software MediaWiki tramite la [[Special:Import|pàxena de inportassion]].

Par esportar le pàxene, scrivi i tìtoli in te la casèla qua soto, un tìtolo par riga, e selessiona se te voli la version ùltima con tute le version preçedenti e el storico de le modìfeghe, opure la version ùltima con solo le informassion de l\'ùltima modìfega.

Nel secondo caso te poli anca doparar un colegamento, par esenpio [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] par la pàxena "[[{{MediaWiki:Mainpage}}]]".',
'exportcuronly'     => "Includi soło ła version attuałe, no l'intera cronołogia",
'exportnohistory'   => "----
'''Ocio!''' Par motivi ligà a le prestazion del sistema xè stà disabiłità l'esportazion de tuta ła storia de łe pàxene fata co sto modulo.",
'export-submit'     => 'Esporta',
'export-addcattext' => 'Zonta pagine da la categoria:',
'export-addcat'     => 'Zonta',
'export-addnstext'  => 'Zonta pàxene dal namespace:',
'export-addns'      => 'Zonta',
'export-download'   => 'Richiedi el salvatagio come file',
'export-templates'  => 'Includi i template',
'export-pagelinks'  => 'Includi le pàxene corelà fin a na profondità de:',

# Namespace 8 related
'allmessages'               => 'Tuti i messagi de sistema',
'allmessagesname'           => 'Nome',
'allmessagesdefault'        => 'Testo predefinìo',
'allmessagescurrent'        => 'Testo come che el xe desso',
'allmessagestext'           => "Sta quà l'è na lista de tuti i messagi disponibili nel namespace MediaWiki.
Par piaser visita [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisation] e [http://translatewiki.net translatewiki.net] se te voli jutarne par la traduzion del software MediaWiki ne le varie lengue.",
'allmessagesnotsupportedDB' => "'''{{ns:special}}:Allmessages''' no'l xè supportà parché '''\$wgUseDatabaseMessages''' no'l xè ativo.",
'allmessagesfilter'         => 'Filtro sui messagi:',
'allmessagesmodified'       => 'Mostra soło quełi modefegà',

# Thumbnails
'thumbnail-more'           => 'Ingrandissi',
'filemissing'              => 'File mancante',
'thumbnail_error'          => 'Eror ne la creazion de la miniatura: $1',
'djvu_page_error'          => 'Nùmaro de pagina DjVu sbaglià',
'djvu_no_xml'              => "Inpossibile otegner l'XML par el file DjVu",
'thumbnail_invalid_params' => 'Parametri anteprima mìa giusti',
'thumbnail_dest_directory' => 'Inpossibile crear la directory de destinazion',
'thumbnail_image-type'     => 'Tipo de imagine mia suportà',
'thumbnail_gd-library'     => 'Configurassion inconpleta de la librarìa GD: manca la funsion $1',
'thumbnail_image-missing'  => 'Pararìa che manchesse el file: $1',

# Special:Import
'import'                     => 'Inporta pàxene',
'importinterwiki'            => 'Inportazion transwiki',
'import-interwiki-text'      => 'Seleziona un projeto wiki e el titoło de ła pàxena da inportar.
Le date de publicazion e i nomi de i autori de łe varie version i sarà conservà.
Tute łe operazion de inportazion trans-wiki łe xè notà nel [[Special:Log/import|registro de inportazion]].',
'import-interwiki-source'    => 'Sorgente wiki/pàxena:',
'import-interwiki-history'   => "Copia l'intiera cronołogia de sta pàxena",
'import-interwiki-templates' => 'Tira dentro tuti i modèi',
'import-interwiki-submit'    => 'Inporta',
'import-interwiki-namespace' => 'Namespace de destinassion:',
'import-upload-filename'     => 'Nome del file:',
'import-comment'             => 'Comento:',
'importtext'                 => 'Par piaser, esporta el file da la wiki de origine doparando la [[Special:Export|funsion de esportassion]].
Salvalo sul to computer e càrghelo qua.',
'importstart'                => 'Inportazion de łe pàxene in corso...',
'import-revision-count'      => '{{PLURAL:$1|una revixion importà|$1 revixion importae}}',
'importnopages'              => 'Nissuna pàxena da inportar.',
'importfailed'               => 'Inportassion falía: $1',
'importunknownsource'        => "Tipo de origine sconossiùo par l'inportassion",
'importcantopen'             => 'Impossibiłe vèrzar el file de inportassion',
'importbadinterwiki'         => 'Cołegamento inter-wiki mìa giusto',
'importnotext'               => 'Testo vodo o mancante',
'importsuccess'              => 'Inportazion avegnù con sucesso!',
'importhistoryconflict'      => 'Esiste na revision de la cronołogia in conflito (sta pàxena la podarìa èssar xà stà inportà)',
'importnosources'            => "No xè stà definia na fonte par l'inportassion transwiki; l'inportassion direta de ła cronołogia no la xè ativa.",
'importnofile'               => "No xè stà caricà nissun file par l'inportassion.",
'importuploaderrorsize'      => "Caricamento del file par l'importazion mìa riussìo. El file el supera le dimension massime consentìe par el caricamento.",
'importuploaderrorpartial'   => "Caricamento del file par l'inportazion mìa riussìo. El file el xe stà cargà solo in parte.",
'importuploaderrortemp'      => "Caricamento del file par l'inportazion mìa riussìo. Manca na cartela tenporanea.",
'import-parse-failure'       => "Eror de analisi ne l'inportazion XML",
'import-noarticle'           => 'Nissuna pagina da inportar.',
'import-nonewrevisions'      => 'Tute le revision le xe zà stà inportà in precedenza.',
'xml-error-string'           => '$1 a riga $2, colona $3 (byte $4): $5',
'import-upload'              => 'Carga dati XML',
'import-token-mismatch'      => "I dati relativi a la session i xe 'ndài persi. Par piaser, próa da novo.",
'import-invalid-interwiki'   => 'No se pode inportar da la wiki indicà.',

# Import log
'importlogpage'                    => 'Inportassion',
'importlogpagetext'                => "Registro de łe inportazion d'ufiçio de pàxene provenienti da altre wiki, conplete de cronołogia.",
'import-logentry-upload'           => 'gà inportà $1 tramite caricamento de file',
'import-logentry-upload-detail'    => '{{PLURAL:$1|una revixion importà|$1 revixion importae}}',
'import-logentry-interwiki'        => 'gà trasferìo da altra wiki ła pàxena $1',
'import-logentry-interwiki-detail' => '{{PLURAL:$1|una revixion importà|$1 revixion importae}} da $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'La to pàxena utente',
'tooltip-pt-anonuserpage'         => 'La pàxena utente de sto indirizo IP',
'tooltip-pt-mytalk'               => 'La to pàxena de discussion',
'tooltip-pt-anontalk'             => 'Discussioni riguardo le modifiche fate da sto ip',
'tooltip-pt-preferences'          => 'Le to preferense',
'tooltip-pt-watchlist'            => 'La lista de le pàxene che te stè tegnendo soto ocio',
'tooltip-pt-mycontris'            => 'La lista de i to contributi',
'tooltip-pt-login'                => "Te consigliemo de registrarte, ma no'l xe obligatorio.",
'tooltip-pt-anonlogin'            => "Te consigliemo de registrarte, ma no'l xe obligatorio.",
'tooltip-pt-logout'               => 'Và fora (logout)',
'tooltip-ca-talk'                 => 'Varda łe discussion rełative a sta pagina',
'tooltip-ca-edit'                 => 'Te podi modìfegar sta pàxena. Par piaser dòpara el boton de anteprima prima de salvar.',
'tooltip-ca-addsection'           => 'Intaca na sezion nova',
'tooltip-ca-viewsource'           => 'Sta pàxena la xè proteta, ma te podi vedar el so còdexe sorgente.',
'tooltip-ca-history'              => 'Version preçedenti de sta pàxena.',
'tooltip-ca-protect'              => 'Protegi sta pàxena',
'tooltip-ca-delete'               => 'Scanceła sta pàxena',
'tooltip-ca-undelete'             => "Ripristina la pàxena come l'era prima de la scancelassion",
'tooltip-ca-move'                 => "Sposta sta pàxena a n'altro titoło",
'tooltip-ca-watch'                => 'Xonta sta pàxena a la to lista de osservati speciali',
'tooltip-ca-unwatch'              => 'Cava sta pàxena da la to lista dei osservati speciali',
'tooltip-search'                  => 'Serca in {{SITENAME}}',
'tooltip-search-go'               => "Và a na pagina col titolo indicà, se l'esiste",
'tooltip-search-fulltext'         => 'Serca in te le pagine sto testo quà',
'tooltip-p-logo'                  => 'Pàxena prinçipałe',
'tooltip-n-mainpage'              => 'Visita la pàxena prinsipałe',
'tooltip-n-portal'                => 'Descrission del projeto, cossa te podi far, e dove catar le robe',
'tooltip-n-currentevents'         => 'Eventi de atuałità',
'tooltip-n-recentchanges'         => 'La lista de le ùltime modìfeghe a sta wiki',
'tooltip-n-randompage'            => 'Mostra na pàxena a caso',
'tooltip-n-help'                  => 'Pagine de ajuto',
'tooltip-t-whatlinkshere'         => 'Lista de tute le pàxene che le porta a sta qua',
'tooltip-t-recentchangeslinked'   => 'Lista de le ùltime modìfeghe a le pàxene puntà da sta qua.',
'tooltip-feed-rss'                => 'Feed RSS par sta pagina',
'tooltip-feed-atom'               => 'Feed Atom par sta pagina qua',
'tooltip-t-contributions'         => 'Lista de i contributi de sto utente',
'tooltip-t-emailuser'             => 'Manda na e-mail a sto utente',
'tooltip-t-upload'                => 'Carga imagini o file multimediałi',
'tooltip-t-specialpages'          => 'Lista de tute łe pàxene speciali',
'tooltip-t-print'                 => 'Version stanpabile de sta pagina',
'tooltip-t-permalink'             => 'Colegamento permanente a sta version de la pagina',
'tooltip-ca-nstab-main'           => 'Varda la vóxe rełativa',
'tooltip-ca-nstab-user'           => 'Varda la pàxena utente',
'tooltip-ca-nstab-media'          => 'Varda la pàxena del file multimedial',
'tooltip-ca-nstab-special'        => 'Sta quà la xe na pàxena speciale, no la pode èssar modifegà.',
'tooltip-ca-nstab-project'        => 'Varda la pàxena del projeto',
'tooltip-ca-nstab-image'          => "Varda la pàxena de l'imagine",
'tooltip-ca-nstab-mediawiki'      => 'Varda el messajo de sistema',
'tooltip-ca-nstab-template'       => 'Varda el template',
'tooltip-ca-nstab-help'           => 'Varda la pàxena de ajuto',
'tooltip-ca-nstab-category'       => 'Varda la pàxena de la categoria',
'tooltip-minoredit'               => 'Segna come modìfega picenina',
'tooltip-save'                    => 'Salva łe modifeghe',
'tooltip-preview'                 => 'Anteprima de łe modìfeghe (consilià, prima de salvar!)',
'tooltip-diff'                    => 'Varda łe modìfeghe aportàe al testo',
'tooltip-compareselectedversions' => 'Varda łe difarenze tra łe do version selezionà de sta pàxena.',
'tooltip-watch'                   => 'Zonta sta pagina a la lista dei osservati speciali',
'tooltip-recreate'                => 'Ricrea ła pàxena anca se la xè stà scancełà',
'tooltip-upload'                  => 'Intaca el caricamento',
'tooltip-rollback'                => "El \"tira indrìo\" co' un clic solo l'anula le modìfeghe a sta pagina fate da l'ultimo contribudor",
'tooltip-undo'                    => 'L\'"Anuła" el permete de anułar sta modìfega e el verze el modulo de modìfega in modalità de anteprima. El permete de inserir na motivassion in te l\'ogeto de la modìfega.',

# Metadata
'nodublincore'      => 'Metadati Dublin Core RDF non ativi su sto server.',
'nocreativecommons' => 'Metadati Commons RDF non ativi su sto server.',
'notacceptable'     => "El server wiki no'l xè in grado di fornire i dati in un formato łeggibiłe dal client utilixà.",

# Attribution
'anonymous'        => '{{PLURAL:$1|Utente anonimo|Utenti anonimi}} de {{SITENAME}}',
'siteuser'         => '$1, utente de {{SITENAME}}',
'lastmodifiedatby' => "Sta pàxena la xè stà modificà l'ultima olta el $2, $1 da $3.",
'othercontribs'    => 'El testo atuale el xe basà su contributi de $1.',
'others'           => 'altri',
'siteusers'        => '$1, {{PLURAL:$2|utente|utenti}} de {{SITENAME}}',
'creditspage'      => 'Autori de ła pàxena',
'nocredits'        => 'Nissuna informazion sui autori disponibile par sta pagina.',

# Spam protection
'spamprotectiontitle' => 'Filtro anti-spam',
'spamprotectiontext'  => 'La pagina che te voléi salvar la xe stà blocà dal filtro anti-spam. 
Questo xe probabilmente dovùo a la presenza de un colegamento a un sito foresto che el se cata su la lista nera.',
'spamprotectionmatch' => 'El filtro anti-spam el xe stà ativà dal seguente testo: $1',
'spambot_username'    => 'MediaWiki - sistema de rimozion del spam',
'spam_reverting'      => "Ripristinà l'ultima version priva de colegamenti a $1",
'spam_blanking'       => 'Pàxena svodà, tute łe version le contegneva cołegamenti a $1',

# Info page
'infosubtitle'   => 'Informazion par la pàxena',
'numedits'       => 'Nùmaro de modifiche (pagina): $1',
'numtalkedits'   => 'Nùmaro de modifeghe (pàxena de discussion): $1',
'numwatchers'    => 'Nùmaro de osservadori: $1',
'numauthors'     => 'Nùmaro de autori distinti (pagina): $1',
'numtalkauthors' => 'Nùmaro de autori distinti (pàxena de discussion): $1',

# Math options
'mw_math_png'    => 'Mostra senpre in PNG',
'mw_math_simple' => "HTML se'l xe senplice assè, altrimenti PNG",
'mw_math_html'   => 'HTML se se pol, altrimenti PNG',
'mw_math_source' => 'Lassa in formato TeX (par browser testuali)',
'mw_math_modern' => 'Racomandà par i browser pì novi',
'mw_math_mathml' => 'Usa MathML se se pol (sperimental)',

# Math errors
'math_failure'          => 'Eror del parser',
'math_unknown_error'    => 'eror sconossiùo',
'math_unknown_function' => 'funzion sconossiùa',
'math_lexing_error'     => 'eror lessicale',
'math_syntax_error'     => 'eror de sintassi',
'math_image_error'      => 'Conversion in PNG fałía',
'math_bad_tmpdir'       => 'Inpossibile scrìvar o crear la directory tenporanea par math',
'math_bad_output'       => 'Inpossibile scrìvar o crear la directory de output par math',
'math_notexvc'          => 'Eseguibile texvc mancante; par piaser consulta math/README par la configurazion.',

# Patrolling
'markaspatrolleddiff'                 => 'Segna la modifica come verificà',
'markaspatrolledtext'                 => 'Segna sta pàxena come verificà',
'markedaspatrolled'                   => 'Segnà come verificà',
'markedaspatrolledtext'               => 'La revixion selessionà la xè stà segnà come verificada.',
'rcpatroldisabled'                    => 'La verifica de le ultime modifiche la xe disativà',
'rcpatroldisabledtext'                => 'La funzion de verifica de le ultime modifiche al momento no la xe ativa.',
'markedaspatrollederror'              => 'No se pol contrassegnar ła voçe come verificà',
'markedaspatrollederrortext'          => 'Bisogna speçificare na revixion da contrassegnar come verificà.',
'markedaspatrollederror-noautopatrol' => 'No te ghè i parmessi necessari par segnar le to stesse modifiche come verificàe.',

# Patrol log
'patrol-log-page'      => 'Modifiche verificàe',
'patrol-log-header'    => 'Qua de sèvito xe elencàe le verifiche de le modifiche.',
'patrol-log-line'      => 'gà segnà la $1 a la pagina $2 come verificà $3',
'patrol-log-auto'      => '(verifica automatica)',
'patrol-log-diff'      => 'revision $1',
'log-show-hide-patrol' => '$1 el registro dei canbiamenti verificài',

# Image deletion
'deletedrevision'                 => 'Vecia version scancełà $1',
'filedeleteerror-short'           => 'Eror ne la scancelazion del file: $1',
'filedeleteerror-long'            => 'Se gà verificà dei eror nel tentativo de scancelar el file:

$1',
'filedelete-missing'              => 'No se pode scancelar el file "$1", parché no l\'esiste.',
'filedelete-old-unregistered'     => 'La revision del file indicà, "$1", no la xe mìa contegnùa nel database.',
'filedelete-current-unregistered' => 'El file specificà, "$1", no\'l xe mìa contegnùo nel database.',
'filedelete-archive-read-only'    => 'El server Web no l\'è bon de scrìvar ne la directory de archivio "$1".',

# Browsing diffs
'previousdiff' => '← Difarensa pi vècia',
'nextdiff'     => 'Difarensa pi nova →',

# Visual comparison
'visual-comparison' => 'Confronto visuale',

# Media information
'mediawarning'         => "'''Ocio''': Sto file qua el podarìa contegner codice maligno; la so esecuzion la podarìa danegiar el proprio sistema informatico.<hr />",
'imagemaxsize'         => "Dimension massima de le imagini:<br />''(su le relative pagine de descrizion)''",
'thumbsize'            => 'Grandeza de le miniature:',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|pagina|pagine}}',
'file-info'            => 'Dimensioni: $1, tipo MIME: $2',
'file-info-size'       => '($1 × $2 pixel, dimensioni: $3, tipo MIME: $4)',
'file-nohires'         => '<small>No xe mìa disponibili versioni a risoluzion piassè granda.</small>',
'svg-long-desc'        => '(file in formato SVG, dimension nominali $1 × $2 pixel, dimension del file: $3)',
'show-big-image'       => 'Version ad alta risoluzion',
'show-big-image-thumb' => '<small>Dimension de sta anteprima: $1 × $2 pixel</small>',

# Special:NewFiles
'newimages'             => 'Imagini nove',
'imagelisttext'         => "Qua ghe xe na lista de '''$1''' {{PLURAL:$1|file|files}} ordinà par $2.",
'newimages-summary'     => 'Sta pagina speciale la mostra i file cargà piassè reçentemente.',
'newimages-legend'      => 'Filtro',
'newimages-label'       => 'Nome del file (o na parte de élo):',
'showhidebots'          => '($1 i bot)',
'noimages'              => 'No ghè gnente da vardare.',
'ilsubmit'              => 'Serca',
'bydate'                => 'data',
'sp-newimages-showfrom' => 'Mostra i file piassè novi a partire da le ore $2 del $1',

# Bad image list
'bad_image_list' => 'El formato el xe el seguente:

Vien considerà solo i elenchi puntati (righe che scuminsia col caratere *).
El primo colegamento su ogni riga el gà da èssar un colegamento a un file indesiderà.
I colegamenti sucessivi, su la stessa riga, i xe considerà come ecezion (cioè pagine in do che el file el pode èssar riciamà normalmente).',

# Metadata
'metadata'          => 'Metadati',
'metadata-help'     => 'Sto file qua el contien informazion agiuntive, probabilmente zontà da la fotocamera o dal scanner doparà par crearlo o digitalizarlo. Se el file el xe stà modificà, certi detagli i podarìa no corispóndar più a le modifiche aportà.',
'metadata-expand'   => 'Mostra detagli',
'metadata-collapse' => 'Scondi detagli',
'metadata-fields'   => "I canpi relativi ai metadati EXIF elencà in sto messagio i vegnarà mostrà su la pagina de l'imagine quando la tabela dei metadati la xe presentà in forma curta. Par inpostazion predefinìa, i altri canpi i vegnarà sconti.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength",

# EXIF tags
'exif-imagewidth'                  => 'Largheza',
'exif-imagelength'                 => 'Alteza',
'exif-bitspersample'               => 'Bit par campione',
'exif-compression'                 => 'Mecanismo de conpression',
'exif-photometricinterpretation'   => 'Strutura de i pixel',
'exif-orientation'                 => 'Orientamento',
'exif-samplesperpixel'             => 'Numero de łe componenti',
'exif-planarconfiguration'         => 'Disposizion de i dati',
'exif-ycbcrsubsampling'            => 'Raporto de campionamento Y / C',
'exif-ycbcrpositioning'            => 'Posizionamento conponenti Y e C',
'exif-xresolution'                 => 'Risoluzion orixontałe',
'exif-yresolution'                 => 'Risoluzion verticałe',
'exif-resolutionunit'              => 'Unità de mixura risoluzion X e Y',
'exif-stripoffsets'                => 'Posizion de i dati imagine',
'exif-rowsperstrip'                => 'Numero righe par striscia',
'exif-stripbytecounts'             => 'Nùmaro de byte par strìssia conpressa',
'exif-jpeginterchangeformat'       => 'Posizion byte SOI JPEG',
'exif-jpeginterchangeformatlength' => 'Numero de byte de dati JPEG',
'exif-transferfunction'            => 'Funsion de trasferimento',
'exif-whitepoint'                  => 'Coordinate cromatiche de el punto de bianco',
'exif-primarychromaticities'       => 'Coordinate cromatiche de i cołori primari',
'exif-ycbcrcoefficients'           => 'Coeficienti matriçe de trasformassion spazi de i cołori',
'exif-referenceblackwhite'         => 'Copia de vałori de riferimento (nero e bianco)',
'exif-datetime'                    => 'Data e ora de modifica de el file',
'exif-imagedescription'            => "Descrizion de l'imagine",
'exif-make'                        => 'Produtore fotocamera',
'exif-model'                       => 'Modeło fotocamera',
'exif-software'                    => 'Software',
'exif-artist'                      => 'Autor',
'exif-copyright'                   => 'Informassion su el copyright',
'exif-exifversion'                 => 'Version de el formato Exif',
'exif-flashpixversion'             => 'Version Flashpix suportà',
'exif-colorspace'                  => 'Spazio de i cołori',
'exif-componentsconfiguration'     => 'Significato de ciascuna componente',
'exif-compressedbitsperpixel'      => 'Modalità de compression imagine',
'exif-pixelydimension'             => 'Largheza efetiva imagine',
'exif-pixelxdimension'             => 'Alteza efetiva imagine',
'exif-makernote'                   => 'Note de el produtore',
'exif-usercomment'                 => "Note de l'utente",
'exif-relatedsoundfile'            => 'File audio cołegà',
'exif-datetimeoriginal'            => 'Data e ora de creassion de i dati',
'exif-datetimedigitized'           => 'Data e ora de digitałixassion',
'exif-subsectime'                  => 'Data e ora, frazion de secondo',
'exif-subsectimeoriginal'          => 'Data e ora de creazion, frazion de secondo',
'exif-subsectimedigitized'         => 'Data e ora de digitałixazion, frazion de secondo',
'exif-exposuretime'                => 'Tenpo de esposission',
'exif-exposuretime-format'         => '$1 s ($2)',
'exif-fnumber'                     => 'Rapporto focałe',
'exif-exposureprogram'             => 'Programa de esposission',
'exif-spectralsensitivity'         => 'Sensibilità spetrałe',
'exif-isospeedratings'             => 'Sensibiłità ISO',
'exif-oecf'                        => 'Fator de conversion optoełetronica',
'exif-shutterspeedvalue'           => 'Tenpo de esposission',
'exif-aperturevalue'               => 'Apertura',
'exif-brightnessvalue'             => 'Luminosità',
'exif-exposurebiasvalue'           => 'Coression esposission',
'exif-maxaperturevalue'            => 'Massima apertura',
'exif-subjectdistance'             => 'Distansa de el sojeto',
'exif-meteringmode'                => 'Metodo de misurassion',
'exif-lightsource'                 => 'Sorgente łuminoxa',
'exif-flash'                       => 'Caratteristiche e stato de el flash',
'exif-focallength'                 => 'Distansa focałe obietivo',
'exif-subjectarea'                 => 'Area inquadrante el sojeto',
'exif-flashenergy'                 => 'Potenza del flash',
'exif-spatialfrequencyresponse'    => 'Risposta in frequensa spaziałe',
'exif-focalplanexresolution'       => 'Risolussion X sul piano focałe',
'exif-focalplaneyresolution'       => 'Risolussion Y sul piano focałe',
'exif-focalplaneresolutionunit'    => 'Unità de misura risolussion sul piano focałe',
'exif-subjectlocation'             => 'Posizion de el sojeto',
'exif-exposureindex'               => 'Sensibilità impostà',
'exif-sensingmethod'               => 'Metodo de riłevassion',
'exif-filesource'                  => 'Origine del file',
'exif-scenetype'                   => 'Tipo de inquadratura',
'exif-cfapattern'                  => 'Disposizion filtro cołor',
'exif-customrendered'              => 'Elaborassion personałixà',
'exif-exposuremode'                => 'Modalità de espoxission',
'exif-whitebalance'                => 'Biłanciamento de el bianco',
'exif-digitalzoomratio'            => 'Rapporto zoom digitałe',
'exif-focallengthin35mmfilm'       => 'Focałe equivalente su 35 mm',
'exif-scenecapturetype'            => 'Tipo de acquixizion',
'exif-gaincontrol'                 => 'Controło inquadratura',
'exif-contrast'                    => 'Controło contrasto',
'exif-saturation'                  => 'Controło saturazion',
'exif-sharpness'                   => 'Controło nitideza',
'exif-devicesettingdescription'    => 'Descrission inpostassioni dispositivo',
'exif-subjectdistancerange'        => 'Scała distansa sojeto',
'exif-imageuniqueid'               => 'ID univoco imagine',
'exif-gpsversionid'                => 'Version de i tag GPS',
'exif-gpslatituderef'              => 'Latitudine Nord/Sud',
'exif-gpslatitude'                 => 'Latitudine',
'exif-gpslongituderef'             => 'Longitudine Est/Ovest',
'exif-gpslongitude'                => 'Longitudine',
'exif-gpsaltituderef'              => "Riferimento par l'altitudine",
'exif-gpsaltitude'                 => 'Altitudine',
'exif-gpstimestamp'                => 'Ora GPS (orołogio atomico)',
'exif-gpssatellites'               => 'Sateliti doparài par ła mixurazion',
'exif-gpsstatus'                   => 'Stato de el riçevitore',
'exif-gpsmeasuremode'              => 'Modalità de misurazion',
'exif-gpsdop'                      => 'Precixion de ła mixurazion',
'exif-gpsspeedref'                 => 'Unità de mixura de ła veloçità',
'exif-gpsspeed'                    => 'Veloçità del riçevitore GPS',
'exif-gpstrackref'                 => 'Riferimento par ła direzion movimento',
'exif-gpstrack'                    => 'Direzion de el movimento',
'exif-gpsimgdirectionref'          => "Riferimento par ła diression de l'imagine",
'exif-gpsimgdirection'             => "Direzion de l'imagine",
'exif-gpsmapdatum'                 => 'Rilevamento geodetico usà',
'exif-gpsdestlatituderef'          => 'Riferimento par ła latitudine de ła destinazion',
'exif-gpsdestlatitude'             => 'Latitudine de ła destinazion',
'exif-gpsdestlongituderef'         => 'Riferimento par ła longitudine de ła destinassion',
'exif-gpsdestlongitude'            => 'Longitudine de ła destinassion',
'exif-gpsdestbearingref'           => 'Riferimento par ła diression de ła destinassion',
'exif-gpsdestbearing'              => 'Diression de ła destinassion',
'exif-gpsdestdistanceref'          => 'Riferimento par ła distansa de ła destinassion',
'exif-gpsdestdistance'             => 'Distansa de ła destinassion',
'exif-gpsprocessingmethod'         => 'Nome de el metodo de elaborassion GPS',
'exif-gpsareainformation'          => 'Nome de ła xòna GPS',
'exif-gpsdatestamp'                => 'Data GPS',
'exif-gpsdifferential'             => 'Corezion diferensiałe GPS',

# EXIF attributes
'exif-compression-1' => 'No conpresso',

'exif-unknowndate' => 'Data sconossiùa',

'exif-orientation-1' => 'Normałe',
'exif-orientation-2' => 'Roersà orixontalmente',
'exif-orientation-3' => 'Ruotà de 180°',
'exif-orientation-4' => 'Roersà verticalmente',
'exif-orientation-5' => 'Ruotà 90° in senso antiorario e roersà verticalmente',
'exif-orientation-6' => 'Ruotà 90° in senso orario',
'exif-orientation-7' => 'Ruotà 90° in senso orario e capovolto verticalmente',
'exif-orientation-8' => 'Ruotà 90° in senso antiorario',

'exif-planarconfiguration-1' => 'a blochi (chunky)',
'exif-planarconfiguration-2' => 'lineare (planar)',

'exif-xyresolution-i' => '$1 punti par połiçe (dpi)',
'exif-xyresolution-c' => '$1 punti par çentimetro (dpc)',

'exif-componentsconfiguration-0' => 'no esiste',

'exif-exposureprogram-0' => 'Non definio',
'exif-exposureprogram-1' => 'Manuałe',
'exif-exposureprogram-2' => 'Standard',
'exif-exposureprogram-3' => 'Priorità al diaframa',
'exif-exposureprogram-4' => "Priorità a l'esposission",
'exif-exposureprogram-5' => 'Artistico (orientà a ła profondità de campo)',
'exif-exposureprogram-6' => 'Sportivo (orientà a ła veloçità de riprexa)',
'exif-exposureprogram-7' => 'Ritrato (sogeti viçini con sfondo fora fuoco)',
'exif-exposureprogram-8' => 'Panorama (sogeti distanti con sfondo a fuoco)',

'exif-subjectdistance-value' => '$1 metri',

'exif-meteringmode-0'   => 'Sconossùo',
'exif-meteringmode-1'   => 'Media',
'exif-meteringmode-2'   => 'Media pesà çentrà',
'exif-meteringmode-3'   => 'Spot',
'exif-meteringmode-4'   => 'MultiSpot',
'exif-meteringmode-5'   => 'Pattern',
'exif-meteringmode-6'   => 'Parsiałe',
'exif-meteringmode-255' => 'Altro',

'exif-lightsource-0'   => 'Sconossùa',
'exif-lightsource-1'   => 'Luçe diurna',
'exif-lightsource-2'   => 'Lanpada a floressiénsa',
'exif-lightsource-3'   => 'Lanpada al tungsteno (a incandessiénsa)',
'exif-lightsource-4'   => 'Flash',
'exif-lightsource-9'   => 'Tenpo beło',
'exif-lightsource-10'  => 'Nùvoło',
'exif-lightsource-11'  => 'In ombrìa',
'exif-lightsource-12'  => 'Daylight fluorescent (D 5700 â€“ 7100K)',
'exif-lightsource-13'  => 'Day white fluorescent (N 4600 â€“ 5400K)',
'exif-lightsource-14'  => 'Cool white fluorescent (W 3900 â€“ 4500K)',
'exif-lightsource-15'  => 'White fluorescent (WW 3200 â€“ 3700K)',
'exif-lightsource-17'  => 'Luçe standard A',
'exif-lightsource-18'  => 'Luçe standard B',
'exif-lightsource-19'  => 'Luçe standard C',
'exif-lightsource-20'  => 'Iłuminante D55',
'exif-lightsource-21'  => 'Iłuminante D65',
'exif-lightsource-22'  => 'Iłuminante D75',
'exif-lightsource-23'  => 'Iłuminante D50',
'exif-lightsource-24'  => 'Lànpada da studio ISO al tungsteno',
'exif-lightsource-255' => 'Altra sorgente łuminoxa',

# Flash modes
'exif-flash-fired-0'    => "El flash no'l xe mia scatà",
'exif-flash-fired-1'    => 'Flash scatà',
'exif-flash-return-0'   => 'nissuna funsione de individuassion del ritorno de la luce stroboscopica',
'exif-flash-return-2'   => 'luce stroboscopica de ritorno mia individuà',
'exif-flash-return-3'   => 'luce stroboscopica de ritorno individuà',
'exif-flash-mode-1'     => 'ativassion flash forzato',
'exif-flash-mode-2'     => 'rimozion flash forzato',
'exif-flash-mode-3'     => 'modalità automatica',
'exif-flash-function-1' => 'Disativa el flash',
'exif-flash-redeye-1'   => 'modalità ridussion òci rossi',

'exif-focalplaneresolutionunit-2' => 'połiçi',

'exif-sensingmethod-1' => 'Non definio',
'exif-sensingmethod-2' => 'Sensore area cołore a 1 chip',
'exif-sensingmethod-3' => 'Sensore area cołore a 2 chip',
'exif-sensingmethod-4' => 'Sensore area cołore a 3 chip',
'exif-sensingmethod-5' => 'Sensor area cołor sequensiałe',
'exif-sensingmethod-7' => 'Sensore triłineare',
'exif-sensingmethod-8' => 'Sensor łinear cołor sequensiałe',

'exif-scenetype-1' => 'Fotografia direta',

'exif-customrendered-0' => 'Processo normałe',
'exif-customrendered-1' => 'Processo personałixà',

'exif-exposuremode-0' => 'Esposission automatega',
'exif-exposuremode-1' => 'Esposission manuałe',
'exif-exposuremode-2' => 'Bracketing automatego',

'exif-whitebalance-0' => 'Biłanciamento de el bianco automatico',
'exif-whitebalance-1' => 'Biłanciamento de el bianco manuałe',

'exif-scenecapturetype-0' => 'Standard',
'exif-scenecapturetype-1' => 'Panorama',
'exif-scenecapturetype-2' => 'Ritrato',
'exif-scenecapturetype-3' => 'Noturna',

'exif-gaincontrol-0' => 'Nissun',
'exif-gaincontrol-1' => 'Enfasi par basso guadagno',
'exif-gaincontrol-2' => 'Enfasi par alto guadagno',
'exif-gaincontrol-3' => 'Deenfasi par basso guadagno',
'exif-gaincontrol-4' => 'Deenfasi par alto guadagno',

'exif-contrast-0' => 'Normałe',
'exif-contrast-1' => 'Alto contrasto',
'exif-contrast-2' => 'Basso contrasto',

'exif-saturation-0' => 'Normałe',
'exif-saturation-1' => 'Bassa saturazion',
'exif-saturation-2' => 'Alta saturazion',

'exif-sharpness-0' => 'Normałe',
'exif-sharpness-1' => 'Manco nitideza',
'exif-sharpness-2' => 'Piassè nitideza',

'exif-subjectdistancerange-0' => 'Sconossùa',
'exif-subjectdistancerange-1' => 'Macro',
'exif-subjectdistancerange-2' => 'Sojeto viçin',
'exif-subjectdistancerange-3' => 'Sojeto łontano',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Latitudine Nord',
'exif-gpslatitude-s' => 'Latitudine Sud',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Longitudine Est',
'exif-gpslongitude-w' => 'Longitudine Ovest',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-0' => 'Metri sul livèl del mar',
'exif-gpsaltitude-1' => 'Metri soto el livèl del mar',

'exif-gpsstatus-a' => 'Mixurassion in corso',
'exif-gpsstatus-v' => 'Mixurassion interoperabiłe',

'exif-gpsmeasuremode-2' => 'Misurassion bidimensionałe',
'exif-gpsmeasuremode-3' => 'Misurassion tridimensionałe',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Chiłometri orari',
'exif-gpsspeed-m' => 'Miglia orarie',
'exif-gpsspeed-n' => 'Nodi',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'Chilometri',
'exif-gpsdestdistance-m' => 'Mija',
'exif-gpsdestdistance-n' => 'Mija nàutiche',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Diression reałe',
'exif-gpsdirection-m' => 'Diression magnetica',

# External editor support
'edit-externally'      => 'Modìfega sto file doparando un programa esterno',
'edit-externally-help' => '(Par savérghene piessè varda le [http://www.mediawiki.org/wiki/Manual:External_editors istruzion])',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'tute',
'imagelistall'     => 'tute',
'watchlistall2'    => 'tute',
'namespacesall'    => 'Tuti',
'monthsall'        => 'tuti',

# E-mail address confirmation
'confirmemail'             => 'Conferma indirisso e-mail',
'confirmemail_noemail'     => 'No te ghè indicà un indirizo e-mail valido ne le to [[Special:Preferences|preferense]].',
'confirmemail_text'        => "{{SITENAME}} el richiede la verifica de l'indirizo e-mail prima che te possi doparar le funzion ligà a l'e-mail.
Struca el boton qua soto par mandar na richiesta de conferma al to indirizo.
Nel messagio che te riva te catarè un colegamento che contien un codice.
Visita el colegamento col to browser par confermar che el to indirizo el xe valido.",
'confirmemail_pending'     => "El codice de conferma el xe zà stà spedìo par posta eletronica; se l'account el xe stà
creà de reçente, par piaser speta par qualche minuto che riva el codice prima de domandàrghene uno novo.",
'confirmemail_send'        => 'Spedissi un codice de conferma par e-mail',
'confirmemail_sent'        => 'Email de conferma invià.',
'confirmemail_oncreate'    => "Un codice de conferma el xe stà spedìo a l'indirizo
de posta eletronica indicà. El codice no'l xe necessario par entrar nel sito,
ma bisogna fornirlo par poder abilitar tute le funzion del sito che dòpara la posta eletronica.",
'confirmemail_sendfailed'  => "{{SITENAME}} no l'è stà bon da inviar el messagio e-mail de conferma. 
Controla che l'indirizo no'l contegna carateri mìa validi.

El messagio de eror el xe: $1",
'confirmemail_invalid'     => 'Codice de conferma mìa valido. El codice el podarìa èssar scadùo.',
'confirmemail_needlogin'   => 'Xè necessario $1 par confermare el proprio indirisso e-mail.',
'confirmemail_success'     => "El to indirisso email l'è stado confermà. Ora te podi loggarte e gòderte la wiki.",
'confirmemail_loggedin'    => 'El to indirisso email el xè stà confermà.',
'confirmemail_error'       => "Qualcossa l'è andà storto nel salvar la to conferma.",
'confirmemail_subject'     => "{{SITENAME}}: e-mail par la conferma de l'indirisso",
'confirmemail_body'        => 'Qualcheduni, probabilmente ti stesso da l\'indirizo IP $1, el ga registrà n\'account "$2" con sto indirizo e-mail su {{SITENAME}}. 

Par confermar che sto account el xe veramente tuo e poder ativar le funzion relative a l\'e-mail su {{SITENAME}}, verzi sto colegamento col to browser: 

$3 

Se l\'account *no* te lo ghè registrà ti, verzi st\'altro colegamento par anular la conferma de l\'indirizo:

$5

El codice de conferma el scadrà in automatico a le $4.',
'confirmemail_invalidated' => 'Richiesta de conferma indirizo e-mail anulà',
'invalidateemail'          => 'Anula richiesta de conferma e-mail',

# Scary transclusion
'scarytranscludedisabled' => "[L'inclusion de pagine tra siti wiki no la xe ativa]",
'scarytranscludefailed'   => '[Inpossibile otegner el template $1]',
'scarytranscludetoolong'  => '[La URL la xe massa longa]',

# Trackbacks
'trackbackbox'      => 'Informazion de trackback par sta voçe:<br />
$1',
'trackbackremove'   => '([$1 Scancela])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => 'Informasion de trackback eliminà coretamente.',

# Delete conflict
'deletedwhileediting' => "'''Ocio''': Sta pàxena la xè stà scancełà dopo che te ghè scominzià a modificarla!",
'confirmrecreate'     => "L'utente [[User:$1|$1]] ([[User talk:$1|discussion]]) el ga scancełà sta voçe dopo che te ghè scuminsià a modificarla, con ła seguente motivazion:
: ''$2''
Par piaser, conferma che te vołi dal bon ricrear sta voçe.",
'recreate'            => 'Ricrea',

# action=purge
'confirm_purge_button' => 'Conferma',
'confirm-purge-top'    => 'Vóto scancełar ła cache in sta pàxena?',
'confirm-purge-bottom' => 'Netar la cache de na pàxena parmete de far védar la so version piassè ajornà.',

# Multipage image navigation
'imgmultipageprev' => '← pagina precedente',
'imgmultipagenext' => 'pagina seguente →',
'imgmultigo'       => 'Và',
'imgmultigoto'     => 'Và a la pagina $1',

# Table pager
'ascending_abbrev'         => 'cresc',
'descending_abbrev'        => 'decresc',
'table_pager_next'         => 'Pagina sucessiva',
'table_pager_prev'         => 'Pagina precedente',
'table_pager_first'        => 'Prima pagina',
'table_pager_last'         => 'Ultima pagina',
'table_pager_limit'        => 'Mostra $1 file par pagina',
'table_pager_limit_submit' => 'Và',
'table_pager_empty'        => 'Nissun risultato',

# Auto-summaries
'autosumm-blank'   => 'Pagina svodà conpletamente',
'autosumm-replace' => "Pagina sostituìa con '$1'",
'autoredircomment' => 'Rimando a ła pàxena [[$1]]',
'autosumm-new'     => "Pàxena creà con '$1'",

# Live preview
'livepreview-loading' => 'Caricamento in corso…',
'livepreview-ready'   => 'Caricamento in corso… Pronto.',
'livepreview-failed'  => "Eror ne la funzion Live preview.
Doparar l'anteprima standard.",
'livepreview-error'   => 'Inpossibile efetuar el colegamento: $1 "$2"
Doparar l\'anteprima standard.',

# Friendlier slave lag warnings
'lag-warn-normal' => "Le modifiche fate {{PLURAL:$1|ne l'ultimo secondo|nei ultimi $1 secondi}} no podarìa no èssarghe gnancora su sta lista.",
'lag-warn-high'   => "Par via de un ecessivo ritardo ne l'agiornamento del server de database, le modifiche fate {{PLURAL:$1|ne l'ultimo secondo|nei ultimi $1 secondi}} le podarìa no èssarghe mìa su sta lista.",

# Watchlist editor
'watchlistedit-numitems'       => 'La lista dei osservati speciali la contien {{PLURAL:$1|una pagina (e la rispetiva pagina de discussion)|$1 pagine (e le rispetive pagine de discussion)}}.',
'watchlistedit-noitems'        => 'La lista dei osservati speciali la xe voda.',
'watchlistedit-normal-title'   => 'Modifica osservati speciali',
'watchlistedit-normal-legend'  => 'Elimina de pagine dai osservati speciali',
'watchlistedit-normal-explain' => "De seguito xe elencà tute le pagine tegnùe d'ocio. Par cavar una o più pagine da la lista, seleziona le casele relative e struca el boton 'Elimina pagine' in fondo a l'elenco. Nota che se pol anca [[Special:Watchlist/raw|modificar la lista in formato testual]].",
'watchlistedit-normal-submit'  => 'Elimina pagine',
'watchlistedit-normal-done'    => 'Da la lista dei osservati speciali xe stà eliminà {{PLURAL:$1|una pagina|$1 pagine}}:',
'watchlistedit-raw-title'      => 'Modifica dei osservati speciali in forma testual',
'watchlistedit-raw-legend'     => 'Modifica testual osservati speciali',
'watchlistedit-raw-explain'    => "De seguito xe elencàe tute le pagine tegnùe d'ocio. Par modificar la lista, zónteghe o càveghe i rispetivi titoli, uno par riga. Na olta che the ghè finìo, struca el boton 'Agiorna la lista' in fondo all'elenco. Nota che te pol anca [[Special:Watchlist/edit|modificar la lista con l'interfacia standard]].",
'watchlistedit-raw-titles'     => 'Pagine:',
'watchlistedit-raw-submit'     => 'Agiorna la lista',
'watchlistedit-raw-done'       => 'La lista dei osservati speciali la xe stà agiornà.',
'watchlistedit-raw-added'      => 'Xe stà zontà {{PLURAL:$1|una pagina|$1 pagine}}:',
'watchlistedit-raw-removed'    => 'Xe stà eliminà {{PLURAL:$1|una pagina|$1 pagine}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Varda le modifiche pertinenti',
'watchlisttools-edit' => 'Varda e modìfega la lista dei osservati speciali',
'watchlisttools-raw'  => 'Modìfega la lista in formato testo',

# Core parser functions
'unknown_extension_tag' => 'Tag estension sconossiùo: "$1"',
'duplicate-defaultsort' => 'Ocio: la ciave de ordinamento predefinìa "$2" la va in conflito co\' quela de prima "$1".',

# Special:Version
'version'                          => 'Version',
'version-extensions'               => 'Estension instalè',
'version-specialpages'             => 'Pagine speciali',
'version-parserhooks'              => 'Hook del parser',
'version-variables'                => 'Variabili',
'version-other'                    => 'Altro',
'version-mediahandlers'            => 'Gestori de contenuti multimediài',
'version-hooks'                    => 'Hook',
'version-extension-functions'      => 'Funzion introdote da estensioni',
'version-parser-extensiontags'     => 'Tag riconossiùi dal parser introdoti da estensioni',
'version-parser-function-hooks'    => 'Hook par funzioni del parser',
'version-skin-extension-functions' => "Funzioni ligà a l'aspeto grafico (skin) introdote da estensioni",
'version-hook-name'                => "Nome de l'hook",
'version-hook-subscribedby'        => 'Sotoscrizioni',
'version-version'                  => '(Version $1)',
'version-license'                  => 'Licensa',
'version-software'                 => 'Software instalà',
'version-software-product'         => 'Prodoto',
'version-software-version'         => 'Version',

# Special:FilePath
'filepath'         => 'Percorso de un file',
'filepath-page'    => 'Nome del file:',
'filepath-submit'  => 'Percorso',
'filepath-summary' => 'Sta pagina speciale la restituìsse el percorso conpleto de un file. Le imagini le vien mostrà a la risoluzion pi granda che se pol, par i altri tipi de file vien avià diretamente el programa associà.

Inserissi el nome del file senza el prefisso "{{ns:file}}:"',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Riçerca dei file duplicà',
'fileduplicatesearch-summary'  => "Riçerca de eventuali duplicati del file in base al valor de ''hash''.

Inserissi el nome del file senza el prefisso \"{{ns:file}}:\"",
'fileduplicatesearch-legend'   => 'Riçerca de un duplicato',
'fileduplicatesearch-filename' => 'Nome del file:',
'fileduplicatesearch-submit'   => 'Riçerca',
'fileduplicatesearch-info'     => '$1 × $2 pixel<br />Dimension: $3<br />Tipo MIME: $4',
'fileduplicatesearch-result-1' => 'No ghe xe duplicati conpagni del file "$1".',
'fileduplicatesearch-result-n' => 'Ghe xe {{PLURAL:$2|un duplicato conpagno|$2 duplicati conpagni}} al file "$1".',

# Special:SpecialPages
'specialpages'                   => 'Pàxene speciałi',
'specialpages-note'              => '----
* Pagine speciali normali.
* <strong class="mw-specialpagerestricted">Pagine speciali ad acesso limità.</strong>',
'specialpages-group-maintenance' => 'Resoconti de manutenzion',
'specialpages-group-other'       => 'Altre pagine speciali',
'specialpages-group-login'       => 'Login / registrazion',
'specialpages-group-changes'     => 'Ultime modifiche e registri',
'specialpages-group-media'       => 'Resoconti e caricamenti dei file multimediài',
'specialpages-group-users'       => 'Utenti e diriti',
'specialpages-group-highuse'     => 'Pagine doparà assè',
'specialpages-group-pages'       => 'Liste de pàxene',
'specialpages-group-pagetools'   => 'Strumenti de pàxena',
'specialpages-group-wiki'        => 'Dati e strumenti wiki',
'specialpages-group-redirects'   => 'Reindirizamenti',
'specialpages-group-spam'        => 'Strumenti anti spam',

# Special:BlankPage
'blankpage'              => 'Pàxena voda',
'intentionallyblankpage' => 'Sta pagina la xe stà lassà voda aposta',

# External image whitelist
'external_image_whitelist' => "  #Lassa sta riga esatamente cussita come la xe<pre>
#Inserissi i framenti de espression regolari (solo el toco che va fra //) de seguito
#Ste qua le corispondarà coi URL de imagini foreste (hotlinked)
#Quele che corispondarà le vegnarà fora come imagini, se no vegnarà mostrà solo un colegamento a l'imagine
#Le linee che taca con # le xe de comento
#No vien tegnù conto del majuscolo/minuscolo

#Inserissi de sora de sta riga tuti i framenti de regex. Lassa sta riga esatamente cussita come la xe</pre>",

# Special:Tags
'tags'                    => 'Tag de le modifiche valide',
'tag-filter'              => '[[Special:Tags|Tag]] filtro:',
'tag-filter-submit'       => 'Filtro',
'tags-title'              => 'Tag',
'tags-intro'              => 'Sta pàxena la elenca i tag che el software el podarìa marcar come na modifica e el so significato.',
'tags-tag'                => 'Nome interno del tag',
'tags-display-header'     => 'Aspetto ne la lista de le modifiche',
'tags-description-header' => 'Descrission conpleta del significado',
'tags-hitcount-header'    => 'Modifiche che gà dei tag',
'tags-edit'               => 'modìfega',
'tags-hitcount'           => '$1 {{PLURAL:$1|modìfega|modìfeghe}}',

# Database error messages
'dberr-header'      => 'Sta wiki la ga un problema',
'dberr-problems'    => 'Sto sito al momento el gà qualche problema tènico.',
'dberr-again'       => 'Próa a spetar un par de minuti e ricargar la pàxena.',
'dberr-info'        => '(No se riesse a métarse in contato col server del database: $1)',
'dberr-usegoogle'   => 'Fin che te speti, te podi proar a sercar su Google.',
'dberr-outofdate'   => 'Tien presente che la so indicixassion dei nostri contenuti la podarìa no èssar ajornà.',
'dberr-cachederror' => 'Quela che segue la xe na copia cache de la pàxena richiesta, e la podarìa no èssar mia ajornà.',

# HTML forms
'htmlform-invalid-input'       => "Ghe xe problemi con l'input che te ghè inserìo",
'htmlform-select-badoption'    => "El valor che te ghè indicà no'l xe mia valido.",
'htmlform-int-invalid'         => "El valor che te ghè indicà no'l xe un nùmaro intero.",
'htmlform-int-toolow'          => 'El valor che te ghè indicà el xe soto al minimo, che xe $1',
'htmlform-int-toohigh'         => 'El valor che te ghè indicà el xe sora al màssimo, che xe $1',
'htmlform-submit'              => 'Manda',
'htmlform-reset'               => 'Scancèla modifiche',
'htmlform-selectorother-other' => 'Altro',

);

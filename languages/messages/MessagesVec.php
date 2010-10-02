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
 * @author Vajotwo
 * @author לערי ריינהארט
 */

$magicWords = array(
	'redirect'              => array( '0', '#VARDA', '#RINVIA', '#RINVIO', '#RIMANDO', '#REDIRECT' ),
);

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
	NS_TEMPLATE         => 'Modèl',
	NS_TEMPLATE_TALK    => 'Discussion_modèl',
	NS_HELP             => 'Ajuto',
	NS_HELP_TALK        => 'Discussion_ajuto',
	NS_CATEGORY         => 'Categoria',
	NS_CATEGORY_TALK    => 'Discussion_categoria',
);

$namespaceAliases = array(
	'Imagine' => NS_FILE,
	'Discussion_imagine' => NS_FILE_TALK,
	'Discussion_template' => NS_TEMPLATE_TALK,
	'Aiuto' => NS_HELP,
	'Discussion_aiuto' => NS_HELP_TALK,
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
	'Listfiles'                 => array( 'ListaFile' ),
	'Newimages'                 => array( 'FileNovi' ),
	'Listusers'                 => array( 'Utenti' ),
	'Listgrouprights'           => array( 'ListaDiritiDeGrupo' ),
	'Statistics'                => array( 'Statìsteghe' ),
	'Randompage'                => array( 'PàxenaACaso' ),
	'Lonelypages'               => array( 'PàxenaÒrfana' ),
	'Uncategorizedpages'        => array( 'PàxeneSensaCategorie' ),
	'Uncategorizedcategories'   => array( 'CategorieSensaCategorie' ),
	'Uncategorizedimages'       => array( 'FileSensaCategorie' ),
	'Uncategorizedtemplates'    => array( 'ModèiMiaCategorizà' ),
	'Unusedcategories'          => array( 'CategorieMiaDoparà' ),
	'Unusedimages'              => array( 'FileMiaDoparà' ),
	'Wantedpages'               => array( 'PàxeneRichieste' ),
	'Wantedcategories'          => array( 'CategorieRichieste' ),
	'Wantedfiles'               => array( 'FileRichiesti' ),
	'Wantedtemplates'           => array( 'ModèiRichiesti' ),
	'Mostlinked'                => array( 'PàxenePiassèRiciamà' ),
	'Mostlinkedcategories'      => array( 'CategoriePiassèRiciamà' ),
	'Mostlinkedtemplates'       => array( 'ModèiPiassèDoparà' ),
	'Mostimages'                => array( 'FilePiassèRiciamà' ),
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
	'Unusedtemplates'           => array( 'ModèiMiaDoparà' ),
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
	'Activeusers'               => array( 'UtentiAtivi' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Sotołinea i cołegamenti:',
'tog-highlightbroken'         => 'Evidensia <a href="" class="new">cussita/a> i cołegamenti che i punta a voxe \'ncora da scrìvar (se disativà vien fora <a href="" class="internal">cussita</a>).',
'tog-justify'                 => 'Paragrafo: giustifegà',
'tog-hideminor'               => 'Scondi łe modifeghe picenine n\'te ła pajina "Ulteme modifeghe"',
'tog-hidepatrolled'           => 'Scondi łe modifeghe verifegae tra łe ulteme modifeghe',
'tog-newpageshidepatrolled'   => "Scondi łe pajine verifegae da l'elenco de łe pajine pì resenti",
'tog-extendwatchlist'         => "Mostra tute łe modifeghe a i oservai spesałi, no soło l'ultima",
'tog-usenewrc'                => 'Utiłisa łe ulteme modifeghe avansae (el richiede JavaScript)',
'tog-numberheadings'          => 'Numerasion automatega de i titołi de sesion',
'tog-showtoolbar'             => 'Mostra ła bara de i strumenti de modifega (el richiede JavaScript)',
'tog-editondblclick'          => 'Modifega de łe pajine tramite dopio clic (el richiede JavaScript)',
'tog-editsection'             => 'Modifega de łe sesion tramite el cołegamento [modifega]',
'tog-editsectiononrightclick' => 'Modifega de łe sesion tramite clic destro sol titoło (el richiede JavaScript)',
'tog-showtoc'                 => "Mostra l'indexe par łe pajine con pì de 3 sesion",
'tog-rememberpassword'        => 'Tiente in mente la me password so sto computer (par un massimo de $1 {{PLURAL:$1|zorno|zorni}})',
'tog-watchcreations'          => 'Zonta łe pajine creae a i oservai spesałi',
'tog-watchdefault'            => 'Zonta łe pajine modifegae a i oservai spesałi',
'tog-watchmoves'              => 'Zonta łe pajine spostae a i oservai spesałi',
'tog-watchdeletion'           => 'Zonta łe pajine scansełae a i oservai spesałi',
'tog-previewontop'            => "Mostra l'anteprima sora ła caseła de modifega e no soto",
'tog-previewonfirst'          => "Mostra l'anteprima par ła prima modifega",
'tog-nocache'                 => 'Disativa ła cache par łe pajine del browser',
'tog-enotifwatchlistpages'    => 'Segnałame via e-mail łe modifeghe a łe pajine oservae',
'tog-enotifusertalkpages'     => 'Segnałame via e-mail łe modifeghe a ła me pajina de discusion',
'tog-enotifminoredits'        => 'Segnałame via e-mail anca łe modifeghe minori',
'tog-enotifrevealaddr'        => 'Riveła el me indiriso e-mail ne i mesaji de avixo',
'tog-shownumberswatching'     => 'Mostra el numaro de utenti che i ga ła pajina en oservasion',
'tog-oldsig'                  => 'Anteprima de ła firma atuałe:',
'tog-fancysig'                => 'Interpreta i comandi wiki ne ła firma (sensa cołegamento automatego)',
'tog-externaleditor'          => "Dopara par default on editor de testo esterno (soło par utenti esperti, el richiede l'uso de impostasion particołari sol proprio computer)",
'tog-externaldiff'            => "Dopara par default on programa de diff esterno (soło par utenti esperti, el richiede l'uso de impostasion particołari sol proprio computer)",
'tog-showjumplinks'           => 'Ativa i cołegamenti acesibiłi "va a"',
'tog-uselivepreview'          => 'Ativa ła funsion "Line preview" (el richiede JavaScript; sperimentałe)',
'tog-forceeditsummary'        => "Chiedi conferma se l'ozeto de ła modifega el xé vodo",
'tog-watchlisthideown'        => 'Scondi łe me modifeghe ne i oservai spesałi',
'tog-watchlisthidebots'       => 'Scondi łe modifeghe de i bot ne i oservai spesałi',
'tog-watchlisthideminor'      => 'Scondi łe modifeghe picenine ne i oservai spesałi',
'tog-watchlisthideliu'        => 'Scondi łe modifeghe de i utenti rejistrà ne i oservai spesałi',
'tog-watchlisthideanons'      => 'Scondi łe modifeghe de i utenti anonimi ne i oservai spesałi',
'tog-watchlisthidepatrolled'  => 'Scondi łe modifeghe verifegae ne i oservai spesałi',
'tog-ccmeonemails'            => 'Inviame na copia de i mesaji spedii a i altri utenti',
'tog-diffonly'                => 'No visuałisar el contenuo de ła pajina dopo el confronto tra version',
'tog-showhiddencats'          => 'Mostra łe categorie sconte',
'tog-norollbackdiff'          => 'No mostrare el confronto tra version dopo aver efetuà on rollback',

'underline-always'  => 'Senpre',
'underline-never'   => 'Mai',
'underline-default' => 'Mantieni łe inpostasion del browser',

# Font style option in Special:Preferences
'editfont-style'     => "Stiłe font de l'area de modifega:",
'editfont-default'   => 'Predefinio del browser',
'editfont-monospace' => 'Font monospasio',
'editfont-sansserif' => 'Font sans-serif',
'editfont-serif'     => 'Font serif',

# Dates
'sunday'        => 'Domenega',
'monday'        => 'Luni',
'tuesday'       => 'Marti',
'wednesday'     => 'Mercore',
'thursday'      => 'Xoba',
'friday'        => 'Venare',
'saturday'      => 'Sabo',
'sun'           => 'dom',
'mon'           => 'lun',
'tue'           => 'mar',
'wed'           => 'mer',
'thu'           => 'xob',
'fri'           => 'ven',
'sat'           => 'sab',
'january'       => 'zenaro',
'february'      => 'febraro',
'march'         => 'marso',
'april'         => 'apriłe',
'may_long'      => 'majo',
'june'          => 'giugno',
'july'          => 'lujo',
'august'        => 'agosto',
'september'     => 'setenbre',
'october'       => 'otobre',
'november'      => 'novenbre',
'december'      => 'disenbre',
'january-gen'   => 'zenaro',
'february-gen'  => 'febraro',
'march-gen'     => 'marso',
'april-gen'     => 'apriłe',
'may-gen'       => 'majo',
'june-gen'      => 'giugno',
'july-gen'      => 'lujo',
'august-gen'    => 'agosto',
'september-gen' => 'setenbre',
'october-gen'   => 'otobre',
'november-gen'  => 'novenbre',
'december-gen'  => 'disenbre',
'jan'           => 'zen',
'feb'           => 'feb',
'mar'           => 'mar',
'apr'           => 'apr',
'may'           => 'maj',
'jun'           => 'giu',
'jul'           => 'luj',
'aug'           => 'ago',
'sep'           => 'set',
'oct'           => 'oto',
'nov'           => 'nov',
'dec'           => 'dis',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Categoria|Categorie}}',
'category_header'                => 'Pajine in te ła categoria "$1"',
'subcategories'                  => 'Sotocategorie',
'category-media-header'          => 'File in te ła categoria "$1"',
'category-empty'                 => "''Al momento ła categoria no ła contien nisuna pajina né file multimediai.''",
'hidden-categories'              => '{{PLURAL:$1|Categoria sconta|Categorie sconte}}',
'hidden-category-category'       => 'Categorie sconte',
'category-subcat-count'          => '{{PLURAL:$2|Sta categoria contien na unica sotocategoria, indicà de seguito.|Sta categoria contien {{PLURAL:$1|ła sotocategoria indicà|łe $1 sotocategorie indicae}} de seguito, so on totałe de $2.}}',
'category-subcat-count-limited'  => 'Sta categoria ła contien {{PLURAL:$1|na sotocategoria, indicà|$1 sotocategorie, indicà}} de seguito.',
'category-article-count'         => '{{PLURAL:$2|Sta categoria contien na unica pajina, indicà de seguito.|Sta categoria contien {{PLURAL:$1|ła pajina indicà|łe $1 pajine indicae}} de seguito, so on totałe de $2.}}',
'category-article-count-limited' => 'Sta categoria ła contien {{PLURAL:$1|ła pajina indicà|łe $1 pajine indicae}} de seguito.',
'category-file-count'            => '{{PLURAL:$2|Sta categoria ła contien on soło file, indicà de seguito.|Sta categoria ła contien {{PLURAL:$1|on file, indicà|$1 file, indicai}} de seguito, so on totałe de $2.}}',
'category-file-count-limited'    => 'Sta categoria ła contien {{PLURAL:$1|el file indicà|i $1 file indicai}} de seguito.',
'listingcontinuesabbrev'         => 'cont.',
'index-category'                 => 'Pajine indicisae',
'noindex-category'               => 'Pajine no indicisae',

'mainpagetext'      => "'''Instałasion de MediaWiki conpletà coretamente.'''",
'mainpagedocfooter' => "Varda ła [http://meta.wikimedia.org/wiki/Aiuto:Sommario Guida utente] par majori informasion so l'uso de sto software wiki.

== Par scumisiar ==

I seguenti cołegamenti i xé en łengua inglese:

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Inpostasion de configurasion]
* [http://www.mediawiki.org/wiki/Manual:FAQ Domande frequenti so MediaWiki]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Mailing list anunsi MediaWiki]",

'about'         => 'Informasion',
'article'       => 'Voxe',
'newwindow'     => '(se verze en na nova finestra)',
'cancel'        => 'Anuła',
'moredotdotdot' => 'Altro...',
'mypage'        => 'Ła me pajina',
'mytalk'        => 'me discusion',
'anontalk'      => 'Discusion par sto IP',
'navigation'    => 'Navigasion',
'and'           => '&#32;e',

# Cologne Blue skin
'qbfind'         => 'Trova',
'qbbrowse'       => 'Sfoja',
'qbedit'         => 'Modifega',
'qbpageoptions'  => 'Opsion pajina',
'qbpageinfo'     => 'Informasion so ła pajina',
'qbmyoptions'    => 'Łe me pajine',
'qbspecialpages' => 'Pajine spesałi',
'faq'            => 'Domande frequenti',
'faqpage'        => 'Project:Domande frequenti',

# Vector skin
'vector-action-addsection' => 'Zonta discusion',
'vector-action-delete'     => 'Scanseła',
'vector-action-move'       => 'Sposta',
'vector-action-protect'    => 'Protezi',
'vector-action-undelete'   => 'Recupera',
'vector-action-unprotect'  => 'Sbloca',
'vector-view-create'       => 'Crea',
'vector-view-edit'         => 'Modifega',
'vector-view-history'      => 'Visuałiza cronołosia',
'vector-view-view'         => 'Lezi',
'vector-view-viewsource'   => 'Visuałiza sorzente',
'actions'                  => 'Asion',
'namespaces'               => 'Namespace',
'variants'                 => 'Varianse',

'errorpagetitle'    => 'Erore',
'returnto'          => 'Torna a $1.',
'tagline'           => 'Da {{SITENAME}}',
'help'              => 'Ajuto',
'search'            => 'Serca',
'searchbutton'      => 'Serca',
'go'                => 'Va',
'searcharticle'     => 'Va',
'history'           => 'Version presedenti',
'history_short'     => 'Cronołosia',
'updatedmarker'     => 'modifegà da ła me ultema visita',
'info_short'        => 'Informasion',
'printableversion'  => 'Version stanpabiłe',
'permalink'         => 'Link parmanente',
'print'             => 'Stanpa',
'edit'              => 'Modifega',
'create'            => 'Crea',
'editthispage'      => 'Modifega sta pajina',
'create-this-page'  => 'Crea sta pajina',
'delete'            => 'Scanseła',
'deletethispage'    => 'Scanseła sta pajina',
'undelete_short'    => 'Recupera {{PLURAL:$1|na revision|$1 revision}}',
'protect'           => 'Protezi',
'protect_change'    => 'canbia',
'protectthispage'   => 'Protezi sta pajina',
'unprotect'         => 'Sbloca',
'unprotectthispage' => 'Cava ła protesion a sta pajina',
'newpage'           => 'Nova pajina',
'talkpage'          => 'Pajina de discusion',
'talkpagelinktext'  => 'Discusion',
'specialpage'       => 'Pajina spesałe',
'personaltools'     => 'Strumenti personałi',
'postcomment'       => 'Nova sesion',
'articlepage'       => 'Varda ła voxe',
'talk'              => 'Discusion',
'views'             => 'Visite',
'toolbox'           => 'Strumenti',
'userpage'          => 'Varda ła pajina utente',
'projectpage'       => 'Varda ła pajina de servisio',
'imagepage'         => 'Varda ła pajina del file',
'mediawikipage'     => 'Varda el mesajo',
'templatepage'      => 'Varda el modeło',
'viewhelppage'      => 'Varda ła pajina de ajuto',
'categorypage'      => 'Varda ła categoria',
'viewtalkpage'      => 'Varda ła pajina de discusion',
'otherlanguages'    => 'Altre łengue',
'redirectedfrom'    => '(Reindirisamento da <b>$1</b>)',
'redirectpagesub'   => 'Pajina de reindirisamento',
'lastmodifiedat'    => 'Ultema modifega par ła pajina: $2, $1.',
'viewcount'         => 'Sta pajina xè sta leta {{PLURAL:$1|na olta|$1 olte}}.',
'protectedpage'     => 'Pajina proteta',
'jumpto'            => 'Va a:',
'jumptonavigation'  => 'navigasion',
'jumptosearch'      => 'serca',
'view-pool-error'   => 'En sto momento i server i xè sovracarichi.
Tropi utenti i sta tentando de visuałisare sta pajina.
Atendare qualche minudo prima de riprovare a cargare ła pajina.

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Se parla de {{SITENAME}}',
'aboutpage'            => 'Project:Se parla de',
'copyright'            => "Contenui sojeti a licensa d'uso $1.",
'copyrightpage'        => '{{ns:project}}:Copyright',
'currentevents'        => 'Atuałità',
'currentevents-url'    => 'Project:Atuałità',
'disclaimers'          => 'Avertense',
'disclaimerpage'       => 'Project:Avertense xenerali',
'edithelp'             => 'Guida',
'edithelppage'         => 'Help:Modifega',
'helppage'             => 'Help:Ajuto',
'mainpage'             => 'Pajina prinsipałe',
'mainpage-description' => 'Pajina prinsipałe',
'policy-url'           => 'Project:Policy',
'portal'               => 'Portałe Comunidà',
'portal-url'           => 'Project:Portałe Comunidà',
'privacy'              => 'Informasion so ła privacy',
'privacypage'          => 'Project:Informassion su la privacy',

'badaccess'        => 'Parmesi no sufisienti',
'badaccess-group0' => "No te disponi de i parmesi nesesari par eseguire l'asion richiesta.",
'badaccess-groups' => 'Ła funsion richiesta ła xè riservà a i utenti che apartien {{PLURAL:$2|al grupo|a uno de i seguenti grupi}}: $1.',

'versionrequired'     => 'Version $1 de MediaWiki richiesta',
'versionrequiredtext' => "Par usare sta pajina xè nesesario dispore de ła version $1 del software MediaWiki. Varda [[Special:Version|l'aposita pajina]].",

'ok'                      => 'OK',
'retrievedfrom'           => 'Estrato da "$1"',
'youhavenewmessages'      => 'Te ghe $1 ($2).',
'newmessageslink'         => 'novi mesaji',
'newmessagesdifflink'     => 'difarensa co ła revision presedente',
'youhavenewmessagesmulti' => 'Te ghe novi mesaji so $1',
'editsection'             => 'modifega',
'editold'                 => 'modifega',
'viewsourceold'           => 'visuałiza sorzente',
'editlink'                => 'modifega',
'viewsourcelink'          => 'visuałiza sorzente',
'editsectionhint'         => 'Modifega ła sesion $1',
'toc'                     => 'Indexe',
'showtoc'                 => 'mostra',
'hidetoc'                 => 'scondi',
'thisisdeleted'           => 'Varda o ripristina $1?',
'viewdeleted'             => 'Varda $1?',
'restorelink'             => '{{PLURAL:$1|na modifega scansełà|$1 modifeghe scansełà}}',
'feedlinks'               => 'Feed:',
'feed-invalid'            => 'Modałità de sotoscrision del feed no vałida.',
'feed-unavailable'        => 'No ghe xè feed disponibiłi',
'site-rss-feed'           => 'Feed RSS de $1',
'site-atom-feed'          => 'Feed Atom de $1',
'page-rss-feed'           => 'Feed RSS par "$1"',
'page-atom-feed'          => 'Feed Atom par "$1"',
'red-link-title'          => '$1 (ła pajina no a esiste)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Voxe',
'nstab-user'      => 'Utente',
'nstab-media'     => 'File multimediałe',
'nstab-special'   => 'Pajina spesałe',
'nstab-project'   => 'Pajina de servisio',
'nstab-image'     => 'File',
'nstab-mediawiki' => 'Mesajo',
'nstab-template'  => 'Modeło',
'nstab-help'      => 'Ajuto',
'nstab-category'  => 'Categoria',

# Main script and global functions
'nosuchaction'      => 'Operasion no riconossua',
'nosuchactiontext'  => "L'asion spesifegà ne l'URL no a xè vałida.
Xè posibiłe che l'URL sia sta dizità en modo erato o che sia sta seguio on cołegamento no vałido.
Ciò podaria anca indicare on bug en {{SITENAME}}.",
'nosuchspecialpage' => 'Pajina prinsipałe no disponibiłe',
'nospecialpagetext' => "<strong>Ła pajina spesałe richiesta no a xè sta riconossua.</strong>

L'elenco de łe pajine spesałi vałide se trova en [[Special:SpecialPages|{{int:specialpages}}]].",

# General errors
'error'                => 'Erore',
'databaseerror'        => 'Erore del database',
'dberrortext'          => 'Erore de sintassi ne ła richiesta inoltrà al database.
Ciò podaria indicare ła presensa de on bug nel software.
L\'ultima query invià al database xè sta:
<blockquote><tt>$1</tt></blockquote>
riciamà da ła funsion "<tt>$2</tt>".
El database el ga restituio el seguente erore "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Erore de sintasi ne ła richiesta inoltrà al database.
L\'ultima query invià al database xè sta:
"$1"
riciamà da ła funsion "$2".
El database ga restituio el seguente erore "$3: $4".',
'laggedslavemode'      => "'''Atension:''' ła pajina podaria no riportare i azornamenti pì resenti.",
'readonly'             => 'Database blocà',
'enterlockreason'      => 'Indicare el motivo del bloco, spesifegando el momento in cui xè presumibiłe che el venga rimoso',
'readonlytext'         => "En sto momento el database xè blocà e no xè disponibiłi azunte o modifeghe a łe pajine. El bloco xè de sołito legà a operasion de manutension ordinaria, al termine de łe quałi el database xè da novo acesibiłe.

L'aministradore de sistema che ga imposto el bloco ga fornio sta spiegasion: $1",
'missing-article'      => 'El database no ga trovà el testo de na pajina che gavaria dovuo trovare soto el nome de "$1" $2.

De sołito ciò se verifica quando el vien riciamà, a partire da ła cronołosia o dal confronto tra revision, on cołegamento a na pajina scansełà, a on confronto tra revision inesistenti o a on confronto tra revision ripułie da ła cronołosia.

En caso contrario, se ga probabilmente scovà on erore del software MediaWiki.
Se prega de segnałare l\'acaduo a on [[Special:ListUsers/sysop|aministradore]] spesifegando l\'URL en question.',
'missingarticle-rev'   => '(numaro de ła revision: $1)',
'missingarticle-diff'  => '(Dif.: $1, $2)',
'readonly_lag'         => 'El database xè sta blocà automaticamente par consentire a i server co i database slave de sincronizarse con el master',
'internalerror'        => 'Erore interno',
'internalerror_info'   => 'Erore interno: $1',
'fileappenderrorread'  => 'No xè sta posibiłe lezare "$1" durante l\'azunta.',
'fileappenderror'      => 'Inposibiłe zontare "$1" a "$2".',
'filecopyerror'        => 'Inposibiłe copiare el file "$1" en "$2".',
'filerenameerror'      => 'Inposibiłe rinominare el file "$1" en "$2".',
'filedeleteerror'      => 'Inposibiłe scansełare el file "$1".',
'directorycreateerror' => 'Inposibiłe creare ła directory "$1".',
'filenotfound'         => 'File "$1" no trovà.',
'fileexistserror'      => 'Inposibiłe scrivare el file "$1": el file esiste zà',
'unexpected'           => 'Vałore inprevisto: "$1"="$2".',
'formerror'            => 'Erore: inposibiłe inviare el moduło',
'badarticleerror'      => 'Operasion no consentia par sta pajina.',
'cannotdelete'         => 'No xè sta posibiłe scansełare el file "$1".
Podaria esare sta zà scansełà da qualcun altro.',
'badtitle'             => 'Titoło no coreto',
'badtitletext'         => "El titoło de ła pajina richiesta xè vodo, erà o con carateri no amesi opure el deriva da n'erore ne i cołegamenti tra siti wiki diversi o version en łengue diverse de ło steso sito.",
'perfcached'           => 'I dati che segue i xè estrati da na copia "cache" del database, no ajornà en tenpo reałe.',
'perfcachedts'         => 'I dati che segue i xè estrati da na copia "cache" del database. Ultemo ajornamento: $1.',
'querypage-no-updates' => 'I azornamenti de ła pajina i xè tenporaneamente sospesi. I dati en esa contegnui no i verà ajornà.',
'wrong_wfQuery_params' => 'Erore ne i parametri invià a ła funsion wfQuery()<br />
Funsion: $1<br />
Query: $2',
'viewsource'           => 'Visuałiza sorzente',
'viewsourcefor'        => 'de $1',
'actionthrottled'      => 'Asion ritardà',
'actionthrottledtext'  => "Come misura de sicuresa contro e o spam, l'esecusion de alcune asion e a xè limità a on numaro masimo de volte en on determinà periodo de tenpo, limite che en questo caso xè sta superà. Se prega de riprovare tra qualche minuto.",
'protectedpagetext'    => 'Sta pajina xè sta proteta par inpedirne ła modifega.',
'viewsourcetext'       => 'Xè posibiłe visuałizare e copiare el codexe sorzente de sta pajina:',
'protectedinterface'   => "Sta pajina contien on elemento che fa parte de l'interfacia utente del software; xè quindi proteta par evitare posibiłi abusi.",
'editinginterface'     => "'''Ocio:''' El testo de sta pajina el fa parte de l'interfacia utente del sito. Tute łe modifeghe aportae a sta pajina se riflete so i mesaji visuałizà par tuti i utenti.
Par łe tradusion, considera ła posibiłità de usare [http://translatewiki.net/wiki/Main_Page?setlang=vec translatewiki.net], el projeto MediaWiki par ła localizasion.",
'sqlhidden'            => '(ła query SQL ła xè sta sconta)',
'cascadeprotected'     => 'So sta pajina no xè posibiłe efetuare modifeghe parché xè sta inclusa {{PLURAL:$1|ne ła pajina indicà de seguito, che xè sta proteta|ne łe pajine indicae de seguito, che e xè sta protete}} sełesionando ła protesion "ricorsiva":',
'namespaceprotected'   => "No se dispone de i parmesi nesesari par modifegare łe pajine del namespace '''$1'''.",
'customcssjsprotected' => "No se dispone de i parmesi nesesari a ła modifega de ła pajina, en quanto contien łe inpostasion parsonałi de n'altro utente.",
'ns-specialprotected'  => 'No xè posibiłe modifegare łe pajine spesałi.',
'titleprotected'       => 'Ła creasion de na pajina con sto titoło xè sta blocà da [[User:$1|$1]].
Ła modivasion xè ła seguente: "$2".',

# Virus scanner
'virus-badscanner'     => 'Erore de configurasion: antivirus sconossuo: "$1"',
'virus-scanfailed'     => 'scansion fałia (codexe $1)',
'virus-unknownscanner' => 'antivirus sconossuo:',

# Login and logout pages
'logouttext'                 => "'''Logout efetuà.'''

Se poe continuare ad usare {{SITENAME}} come utente anonimo opure [[Special:UserLogin|eseguire on novo aceso]], con ło steso nome utente o on nome difarente.
Nota che alcune pajine e potrebe continuare ad aparire come se el logout no el fosse avegnù finché no a vien pułia la cache del proprio browser.",
'welcomecreation'            => "== Benvegnù, $1! ==

L'acount el xè sta creà coretamente. No dismentegare de personałizare łe [[Special:Preferences|prefarense de {{SITENAME}}]].",
'yourname'                   => 'Nome utente:',
'yourpassword'               => 'Password:',
'yourpasswordagain'          => 'Ripeti ła password:',
'remembermypassword'         => 'Ricorda ła password so sto conputer (par un masimo de $1 {{PLURAL:$1|jorno|jorni}})',
'yourdomainname'             => 'Spesifegare el dominio',
'externaldberror'            => "Se xè verifegà n'erore con el server de autenticasion esterno, opure no se dispone de łe autorizasion nesesarie par ajornare el proprio aceso esterno.",
'login'                      => 'Entra',
'nav-login-createaccount'    => 'Entra / Rejistrate',
'loginprompt'                => 'Par asedare a {{SITENAME}} xè nesesario abiłitare i cookie.',
'userlogin'                  => 'Entra / Rejistrate',
'userloginnocreate'          => 'Entra',
'logout'                     => 'Va fora',
'userlogout'                 => 'va fora',
'notloggedin'                => 'Aceso no efetuà',
'nologin'                    => "No te ghe gnancora on aceso? '''$1'''.",
'nologinlink'                => 'Creało desso',
'createaccount'              => 'Crea on novo utente',
'gotaccount'                 => "Te ghe zà n'aceso? '''$1'''.",
'gotaccountlink'             => 'Entra',
'createaccountmail'          => 'par e-mail',
'createaccountreason'        => 'Motivassion:',
'badretype'                  => 'Łe password inserie no e coinside tra loro.',
'userexists'                 => 'El nome utente inserio xè zà utiłizà.
Sceji on nome utente difarente.',
'loginerror'                 => "Erore ne l'aceso",
'createaccounterror'         => "Inposibiłe creare l'acount: $1",
'nocookiesnew'               => "Ła rejistrasion xè sta conpletà, ma no xè sta posibiłe asedare a {{SITENAME}} parché i cookie i xè disativai. Riprovare l'aceso con el nome utente e ła password pena creai dopo aver ativà i cookie nel proprio browser.",
'nocookieslogin'             => "L'aceso a {{SITENAME}} richiede l'uso de i cookie, che i risulta disativai. Riprovare l'aceso dopo aver ativà i cookie nel proprio browser.",
'noname'                     => 'El nome utente indicà no xè vałido.',
'loginsuccesstitle'          => 'Aceso efetuà',
'loginsuccess'               => "'''Te si sta conesso al server de {{SITENAME}} con el nome utente de \"\$1\".'''",
'nosuchuser'                 => 'Nol xè rejistrà alcun utente de nome "$1". I nomi utente i xè sensibiłi a łe majuscołe. Verifegare el nome inserio o [[Special:UserLogin/signup|creare on novo aceso]].',
'nosuchusershort'            => 'Nol xè rejistrà alcun utente de nome "<nowiki>$1</nowiki>". Verifegare el nome inserio.',
'nouserspecified'            => 'Xè nesesario spesifegare on nome utente.',
'login-userblocked'          => 'Sta utensa xè blocà. No xè posibiłe efetuare el login.',
'wrongpassword'              => 'Ła password inseria no a xè coreta. Riprovare.',
'wrongpasswordempty'         => 'No xè sta inseria alcuna password. Riprovare.',
'passwordtooshort'           => 'Łe password e ga da contegnere almanco {{PLURAL:$1|1 caratere|$1 carateri}}.',
'password-name-match'        => 'Ła password a ga da esare difarente dal nome utente.',
'mailmypassword'             => 'Invia na nova password al me indiriso e-mail',
'passwordremindertitle'      => 'Servisio Password Reminder de {{SITENAME}}',
'passwordremindertext'       => 'Qualcheduni (probabilmente ti, da l\'indirizo IP $1) el gà domandà che ghe vegna mandà na nova password par {{SITENAME}} ($4).
Na password tenporànea par l\'utente "$2" la xe stà creà e inpostà a "$3".
Se xe questo che te voléi far, desso te podi entrar co\' sta password tenporanea e inpostar na password nova.
La to password tenporànea la scade in {{PLURAL:$5|un zorno|$5 zorni}}.

Se no te sì mìa stà ti a far la domanda, opure t\'è vegnù in mente la password e no te vol più canbiarla, te pol ignorar sto mesagio e continuar a doparar la vecia password.',
'noemail'                    => 'Nissuna casela e-mail la risulta registrà par l\'Utente "$1".',
'noemailcreate'              => 'Te ghè da fornir un indirisso e-mail vàlido',
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
'usernamehasherror'          => "El nome utente no'l pode contegner caràteri hash",
'login-throttled'            => 'Te ghè fato massa tentativi de autenticarte.
Spèta un tocheto prima de proàr da novo.',
'loginlanguagelabel'         => 'Lengua: $1',
'suspicious-userlogout'      => 'Ła to richiesta de disconesion xè sta negà parché e a senbra invià da on browser non funsionante o on proxy de caching.',

# JavaScript password checks
'password-strength'            => 'Stima de la robusteza de la password: $1',
'password-strength-bad'        => 'TRISTA',
'password-strength-mediocre'   => 'cussì cussì',
'password-strength-acceptable' => "pole 'ndar ben",
'password-strength-good'       => 'bona',
'password-retype'              => 'Scrivi da novo la password',
'password-retype-mismatch'     => 'La password no le corisponde',

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
'resetpass-submit-cancel'   => 'Anùla',
'resetpass-wrong-oldpass'   => 'Password corente o tenporanea mia valida.
Forse te ghè zà canbià la to password o te ghè domandà na password tenporanea nova.',
'resetpass-temp-password'   => 'Password tenporanea:',

# Edit page toolbar
'bold_sample'     => 'Graseto',
'bold_tip'        => 'Graseto',
'italic_sample'   => 'Corsivo',
'italic_tip'      => 'Corsivo',
'link_sample'     => 'Titoło del cołegamento',
'link_tip'        => 'Cołegamento interno',
'extlink_sample'  => 'http://www.example.com titoło del cołegamento',
'extlink_tip'     => 'Cołegamento foresto (ricordate el prefiso http:// )',
'headline_sample' => 'Intestassion',
'headline_tip'    => 'Intestasion de 2° liveło',
'math_sample'     => 'Inserire qua ła formuła',
'math_tip'        => 'Formuła matematega (LaTeX)',
'nowiki_sample'   => 'Inserire qua el testo no formatà',
'nowiki_tip'      => 'Ignora ła formatasion wiki',
'image_sample'    => 'Esenpio.jpg',
'image_tip'       => 'File incorporà',
'media_sample'    => 'Esenpio.ogg',
'media_tip'       => 'Cołegamento al file multimediałe',
'sig_tip'         => 'Firma co data e ora',
'hr_tip'          => 'Łinea orizontałe (usare con giudisio)',

# Edit pages
'summary'                          => 'Ojeto:',
'subject'                          => 'Argomento (intestassion):',
'minoredit'                        => 'Sta qua xè na modifega minore',
'watchthis'                        => 'Zonta a i oservai spesałi',
'savearticle'                      => 'Salva ła pajina',
'preview'                          => 'Anteprima',
'showpreview'                      => 'Visuałiza anteprima',
'showlivepreview'                  => 'Anteprima in tenpo reàl',
'showdiff'                         => 'Mostra canbiamenti',
'anoneditwarning'                  => "'''Atensione:''' Aceso no efetuà. Ne ła cronołosia de ła pajina verà rejistrà l'indiriso IP.",
'anonpreviewwarning'               => '"No te ghe eseguio el login. Salvando el to indiriso IP sarà rejistrà ne ła cronołosia de sta voxe."',
'missingsummary'                   => "'''Ocio:''' No te ghè indicà l'ogeto de la modifica. Macando de novo 'Salva la pagina' la modifica la vegnerà con l'ogeto vodo.",
'missingcommenttext'               => 'Inserissi un comento qua soto.',
'missingcommentheader'             => "'''Ocio:''' No te ghè specificà l'intestazion de sto commento. Macando de novo '''Salva la pagina''' la modifica la vegnarà salvà senza intestazion.",
'summary-preview'                  => "Anteprima de l'ojeto:",
'subject-preview'                  => 'Anteprima ogeto/intestazion:',
'blockedtitle'                     => 'Utente blocà',
'blockedtext'                      => "'''Sto nome utente o indirizo IP el xe stà blocà.'''

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
'nosuchsectiontitle'               => 'Sezion mia catà',
'nosuchsectiontext'                => 'Te ghè sercà de modificar na sezion che no esiste.
Forse la xe stà spostà o scancelà fin che te sèri drio vardar la pagina.',
'loginreqtitle'                    => "Par modificar sta pagina bisogna prima eseguir l'acesso al sito.",
'loginreqlink'                     => 'login',
'loginreqpagetext'                 => 'Par védar altre pagine bisogna $1.',
'accmailtitle'                     => 'Password spedia.',
'accmailtext'                      => "Na password xenerà casualmente par [[User talk:$1|$1]] la xe stà mandà a $2.

La password par sta nova utensa la pode vegner canbià, dopo ver fato l'acesso, su la pàxena ''[[Special:ChangePassword|canbiar la password]]''.",
'newarticle'                       => '(Novo)',
'newarticletext'                   => "El cołegamento pena seguio corisponde a na pajina no ancora esistente.
Se se desidera creare ła pajina ora, basta scumisiare a scrivare el testo ne ła caseła qua soto
(fare riferimento a łe [[{{MediaWiki:Helppage}}|pajine de ajuto]] par saverghene de pì).
Se el cołegamento xè sta seguio par erore, xè sufisiente fare clic sol pulsante '''Indrio''' del proprio browser.",
'anontalkpagetext'                 => "----''Sta quà l'è la pàxena de discussion de un utente anonimo che no'l se gà gnancora registrà o che no l'efetua el login.
De conseguenza xè necessario identificarlo tramite l'indirizo IP numerico.
Sto indirizo el pode èssar condivixo da diversi utenti.
Se te sì un utente anonimo e te ghè ricevù dei messagi che te secondo ti i xera direti a qualchedun altro, te podi [[Special:UserLogin/signup|registrarte]] o [[Special:UserLogin|efetuar el login]] par evitar confuxion con altri utenti anonimi in futuro.''",
'noarticletext'                    => 'En sto momento ła pajina richiesta xè voda. Xè posibiłe [[Special:Search/{{PAGENAME}}|sercare sto titoło]] ne łe altre pajine del sito, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAME}}}} sercare ne i rejistri corełai] opure [{{fullurl:{{FULLPAGENAME}}|action=edit}} modifegare ła pajina ora]</span>.',
'noarticletext-nopermission'       => 'In sto momento no ghe xe nissun testo su sta pagina.
Te pol [[Special:Search/{{PAGENAME}}|sercar el titolo de sta pagina]] in altre pagine,
o <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} sercar in tei registri ligà a sta pagina]</span>.',
'userpage-userdoesnotexist'        => 'L\'account "$1" no\'l corisponde mìa a un utente registrà. Verifica se te voli dal bon crear o modificar sta pagina.',
'userpage-userdoesnotexist-view'   => 'L\'utensa "$1" no la xe gnancora registrà.',
'blocked-notice-logextract'        => "Sto utente xè atualmente blocà.
L'ultimo ełemento del rejistro de i blochi xè riportà de seguito par informasion:",
'clearyourcache'                   => "'''Ocio: dopo aver salvà, te ghè da netar la cache del to browser par védar i canbiamenti.''' Par '''Mozilla / Firefox / Safari:''' tien macà el boton de le majuscole e schiza \"Ricarica\", o senò maca ''Ctrl-F5'' o ''Ctrl-R'' (''Command-R'' se te ghè el Macintosh); par '''Konqueror:''' schiza \"Ricarica\" o maca ''F5;'' par '''Opera:''' néta la cache in ''Strumenti → Preferenze;'' par '''Internet Explorer:''' tien macà ''Ctrl'' fin che te schizi ''Ricarica'', o maca ''Ctrl-F5.''",
'usercssyoucanpreview'             => "'''Sugerimento:''' se consiglia de doparar el boton 'Visualiza anteprima' par proàr i novi CSS o JavaScript prima de salvarli.",
'userjsyoucanpreview'              => "'''Sugerimento:''' se consiglia de doparar el boton 'Visualiza anteprima' par proàr i novi CSS o JavaScript prima de salvarli.",
'usercsspreview'                   => "'''Sta qua la xe solo n'anteprima del proprio CSS personal.
Le modifiche no le xe gnancora stà salvà!'''",
'userjspreview'                    => "'''Sta qua la xe solo n'anteprima par proar el proprio JavaScript personal; le modifiche no le xe gnancora stà salvà!'''",
'userinvalidcssjstitle'            => "'''Ocio:'''  No ghe xe nissuna skin con nome \"\$1\". Nota che le pagine par i .css e .js personalizà le gà l'iniziale del titolo minuscola, par esenpio {{ns:user}}:Esenpio/monobook.css e no {{ns:user}}:Esenpio/Monobook.css.",
'updated'                          => '(Agiornà)',
'note'                             => "'''Nota:'''",
'previewnote'                      => "'''Sta qua xè soło n'anteprima; łe modifeghe a ła pajina NO xè gnancora sta salvae!'''",
'previewconflict'                  => 'Sta anteprima la corisponde al testo ne la casèla de edizion de sora, e la fa védar come vegnarà fora la pagina se te machi "Salva la pagina" in sto momento.',
'session_fail_preview'             => "No xè stà possibiłe salvar le to modifiche parché i dati de la session i xè andai persi.
Par piaser, riproa da novo.
Se no funsiona gnancora, proa a [[Special:UserLogout|scołegarte]] e a cołegarte de novo.'''",
'session_fail_preview_html'        => "'''No xe mìa stà possibile elaborar la modifica parché xe 'ndà persi i dati relativi a la session.'''

''Dato che su {{SITENAME}} xe abilità l'uso de HTML senza limitazion, l'anteprima no la vien visualizà; se tratta de na misura de sicureza contro i atachi JavaScript.''

'''Se te stè fasendo na modifica legìtima, par piaser próa de novo.
Se no funsiona gnancora, te pol proár a [[Special:UserLogout|scolegarte]] e efetuar da novo l'acesso.'''",
'token_suffix_mismatch'            => "'''La modifica no la xe mìa stà salvà parché el client el gà mostrà de gestir in maniera sbaglià i caràteri de puntegiatura nel token associà a la stessa. Par evitar na possibile coruzion del testo de la pagina, xe stà rifiutà l'intera modifica. Sta situazion la pode verificarse, a olte, quando vien doparà serti servizi de proxy anonimi via web che presenta dei bug.'''",
'editing'                          => 'Modifega de $1',
'editingsection'                   => 'Modifega de $1 (sesion)',
'editingcomment'                   => 'Modifica de $1 (sezion nova)',
'editconflict'                     => 'Conflito de edizion: $1',
'explainconflict'                  => "Qualcun altro el ga salvà na so version de ła voxe nel tempo in cui te stavi preparando ła to version.
La casela de modifica de sora contegne el testo de la voxe ne ła so forma atuałe (el testo atualmente online).
Le to modifiche łe xè invese contegnue ne ła caseła de modifica de soto.
Te dovarè inserire, se te vołi, le to modifiche nel testo esistente, e perciò scrivarle ne ła caseła de sora.
'''Soltanto''' el testo ne ła caseła de sora el sarà salvà se te struchi el botón \"Salva\".",
'yourtext'                         => 'El to testo',
'storedversion'                    => 'Version in archivio',
'nonunicodebrowser'                => "'''OCIO: Te stè doparando un browser mìa conpatibile coi caràteri Unicode. Par consentir la modifica de le pagine senza crear inconvenienti, i caràteri non ASCII i vien mostrà ne la casela de modifica soto forma de codici esadecimali.'''",
'editingold'                       => "'''Ocio: Te stè modificando na version de ła voxe non agiornà. Se te la salvi cussì, tuti i canbiamenti apportai dopo sta version i vegnarà persi.'''",
'yourdiff'                         => 'Difarense',
'copyrightwarning'                 => "Nota: tuti i contribui a {{SITENAME}} se considera riłassai ne i termini de ła licensa d'uso $2 (varda $1 par majori detaji). Se no te desidari che i to testi i posa esare modifegai e redistribuii da chiunque sensa alcuna limitasion, no sta inviarli a {{SITENAME}}.<br />
Con l'invio del testo te dichiari inoltre, soto ła to responsabiłità, che el testo xè sta scrito da ti parsonalmente opure che xè sta copià da na fonte de publico dominio o anałogamente libara.
'''NO STA INVIARE MATERIAŁE COPERTO DA DIRITO D'AUTORE SENSA AUTORIZASION!'''",
'copyrightwarning2'                => "Ocio che tuti i contributi a {{SITENAME}} i pode èssar editai, alterai, o rimossi da altri contributori.
Se no te voli che i to scriti i vegna modificà sensa pietà, alora no sta inserirli qua.<br />
Sapi che te stè prometendo che te stè inserendo un testo scrito de to pugno, o copià da na fonte de publico dominio o similarmente lìbara (varda $1 par i detagli).
'''NO STA INSERIR OPERE PROTETE DA COPYRIGHT SENSA PERMESSO!'''",
'longpagewarning'                  => "'''OCIO: Sta pàxena la xè longa $1 kilobyte; serti browser i podarìa verghe dei problemi ne ła modifega de pàxene che se avisina o supera i 32 kB. Valuta l'oportunità de sudivìdar ła pàxena in sezion pìassè picenine.'''",
'longpageerror'                    => "'''ERROR: The text you have submitted is $1 kilobytes
long, which is longer than the maximum of $2 kilobytes. It cannot be saved.'''",
'readonlywarning'                  => "'''OCIO: El database el xe stà blocà par manutenzion, quindi no se pol salvar le modifiche in sto momento.
Par no pèrdarle, te pol copiar tuto quel che te ghè inserìo fin desso ne la casela de modifica, incolarlo in un programa de elaborazion de testi e salvarlo, intanto che te speti che i sbloca el database.'''

L'aministrador che gà blocà el database el gà dato la seguente spiegassion: $1",
'protectedpagewarning'             => "'''Ocio: Sta pagina la xe sta proteta e solo i aministradori i pode modificarla.''' Sta qua la xe l'ultima operassion catà sul registro de la pagina:",
'semiprotectedpagewarning'         => "'''Nota:''' Sta pàxena la xè stà blocà in modo che solo i utenti registrài i poda modefegarla. Sta qua la xe l'ultima operassion catà sul registro de la pagina:",
'cascadeprotectedwarning'          => "'''Ocio:''' Sta pagina la xe stà blocà in modo che solo i utenti con privilegi de aministrador i possa modificarla. Questo sucede parché la pagina la xe inclusa {{PLURAL:\$1|ne la pagina indicà de seguito, che la xe stà proteta|ne le pagine indicà de seguito, che le xe stà protete}} selezionando la protezion \"ricorsiva\":",
'titleprotectedwarning'            => "'''OCIO:  Sta pàxena la xe stà blocà in modo che solo i utenti con [[Special:ListGroupRights|serti privilègi]] i la possa crear.'''  Sta qua la xe l'ultima operassion catà sul registro de la pagina:",
'templatesused'                    => '{{PLURAL:$1|Modeło utiłizà|Modełi utiłizai}} en sta pajina:',
'templatesusedpreview'             => '{{PLURAL:$1|Modeło|Modełi}} doparà en sta anteprima:',
'templatesusedsection'             => '{{PLURAL:$1|Modèl|Modèi}} doparà in sta sezion:',
'template-protected'               => '(proteto)',
'template-semiprotected'           => '(semiproteto)',
'hiddencategories'                 => 'Sta pajina apartien a {{PLURAL:$1|na categoria nascosta|$1 categorie nascoste}}:',
'nocreatetitle'                    => 'Creazion de le pagine limitada',
'nocreatetext'                     => 'La possibilità de crear pagine nóve su {{SITENAME}} la xe stà limità ai soli utenti registrà. Se pol tornar indrìo e modificar na pagina esistente, opure [[Special:UserLogin|entrar o crear un nóvo acesso]].',
'nocreate-loggedin'                => 'No te ghè i permessi necessari a crear pagine nove.',
'sectioneditnotsupported-title'    => 'Modifica de sezion mia suportà',
'sectioneditnotsupported-text'     => 'La modifica de singole sezion no le xe mia suportà su sta pagina.',
'permissionserrors'                => 'Eror nei permessi',
'permissionserrorstext'            => "No te ghè i permessi necessari ad eseguir l'azion richiesta, par {{PLURAL:$1|el seguente motivo|i seguenti motivi}}:",
'permissionserrorstext-withaction' => 'No se dispone de i parmesi nesesari par $2, par {{PLURAL:$1|el seguente modivo|i seguenti modivi}}:',
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
'post-expand-template-inclusion-warning'  => "'''Ocio:''' la dimension de inclusion dei modèi la xe massa granda.
Alcuni modèi no i sarà mia inclusi.",
'post-expand-template-inclusion-category' => 'Pagine in do che la dimension de inclusion dei modèi la xe massa granda',
'post-expand-template-argument-warning'   => "'''Ocio:''' Sta pagina la contien almanco un argomento de modèl che el gà na dimension de espansion massa granda.
Sti argomenti i xe stà omessi.",
'post-expand-template-argument-category'  => 'Pagine che contien dei modèi con argomenti mancanti',
'parser-template-loop-warning'            => 'Xe stà catà un ciclo in tel modèl: [[$1]]',
'parser-template-recursion-depth-warning' => 'Xe stà rajunto el limite màssimo de ricorsion in tel modèl ($1)',
'language-converter-depth-warning'        => 'Limite de profondità del convertidor de lengua superà ($1)',

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
'viewpagelogs'           => 'Visuałiza i rejistri rełativi a sta pajina.',
'nohistory'              => 'La cronołogia de łe version de sta pàxena no la xè reperibiłe.',
'currentrev'             => 'Version atuałe',
'currentrev-asof'        => 'Version atuałe de łe $1',
'revisionasof'           => 'Version de łe $1',
'revision-info'          => 'Version del $1, autor: $2',
'previousrevision'       => '← Version pi vecia',
'nextrevision'           => 'Version pì resente →',
'currentrevisionlink'    => 'Version atuałe',
'cur'                    => 'cor',
'next'                   => 'suc',
'last'                   => 'prec',
'page_first'             => 'prima',
'page_last'              => 'ultima',
'histlegend'             => "Confronto tra version: sełesionare łe casełe corispondenti a łe version desiderae e premare INVIO o el pulsante en baso.

Legenda: '''({{int:cur}})''' = difarense co ła version atuałe, '''({{int:last}})''' = difarense co ła version presedente, '''{{int:minoreditletter}}''' = modifega minore",
'history-fieldset-title' => 'Scori ła cronołosia',
'history-show-deleted'   => 'Solo quei scancelà',
'histfirst'              => 'Prima',
'histlast'               => 'Ultema',
'historysize'            => '({{PLURAL:$1|1 byte|$1 byte}})',
'historyempty'           => '(voda)',

# Revision feed
'history-feed-title'          => 'Cronołogia',
'history-feed-description'    => 'Cronołogia de ła pàxena su sto sito',
'history-feed-item-nocomment' => '$1 el $2',
'history-feed-empty'          => 'La pàxena richiesta no la esiste; la podarìa èssar stà scancełà dal sito o rinominà. Verifica con la [[Special:Search|pàxena de riserca]] se ghe xè nove pàxene.',

# Revision deletion
'rev-deleted-comment'         => '(comento cavà)',
'rev-deleted-user'            => '(nome utente cavà)',
'rev-deleted-event'           => '(elemento cavà)',
'rev-deleted-user-contribs'   => '[nome utente o indirisso IP cavà - modifica sconta dai contributi]',
'rev-deleted-text-permission' => "Sta version de la pagina la xe stà '''scancelà'''.
Varda el [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registro de scancelazion] par ulteriori detagli.",
'rev-deleted-text-unhide'     => "Sta version de la pàxena la xe sta '''scancelà'''.
Varda el [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registro de scancelassion] par ulteriori detagli.
Ai aministradori xe ancora consentìo [$1 vardar sta version] se i vole.",
'rev-suppressed-text-unhide'  => "Sta version de la pagina la xe stà '''cavà'''.
Varda el [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} registro de rimozion] par ulteriori detagli.
I aministratori i pode ancora [$1 vardar sta versione] se i vole.",
'rev-deleted-text-view'       => "Sta version de la pagina la xe stà '''scancelà'''.
El testo el pode èssar visualizà soltanto dai aministradori del sito.
Varda el [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registro de scancelazion] par ulteriori detagli.",
'rev-suppressed-text-view'    => "Sta version de la pagina la xe stà '''cavà'''.
Solo i aministratori i pole ancora védarla.
Varda el [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} registro de rimozion] par ulteriori detagli.",
'rev-deleted-no-diff'         => "No te pode vardar sta difarensa parché una de le revision la xe stà '''scancelà'''.
Varda el [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registro de scancelassion] par savérghene piessè.",
'rev-suppressed-no-diff'      => "No te pol védar sta difarensa, parché una de le revision la xe stà '''scancelà'''.",
'rev-deleted-unhide-diff'     => "Una dele revision de sta difarensa la xe stà '''scancelà'''.
Consulta el [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registro de scancelassion] par ulteriori detagli.
I aministradori i pode ancora [$1 vardar sta difarensa] se i vole.",
'rev-suppressed-unhide-diff'  => "Una dele revision de sta difarensa la xe stà '''sopressa'''.
Consulta el [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} registro de sopression] par ulteriori detagli.
I aministradori i pode ancora [$1 vardar sta difarensa] se i vole.",
'rev-deleted-diff-view'       => "Una dele revision de sta difarensa la xe stà '''scancelà'''.
Come aministrador, te pol vardar el [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registro de scancelassion] par ulteriori detagli.",
'rev-suppressed-diff-view'    => "Una dele revision de sta difarensa la xe stà '''sopressa'''.
Come aminitrador, te pol vardar el [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} registro de sopression] par ulteriori detagli.",
'rev-delundel'                => 'mostra/scondi',
'rev-showdeleted'             => 'mostra',
'revisiondelete'              => 'Scanceła o ripristina version',
'revdelete-nooldid-title'     => 'Version mìa specificà',
'revdelete-nooldid-text'      => 'No xe stà specificà alcuna version de la pagina su cui eseguir sta funzion.',
'revdelete-nologtype-title'   => 'Nissun tipo de registro specificà',
'revdelete-nologtype-text'    => "No ti gà indicà nissun tipo de registro su cui eseguir l'azion.",
'revdelete-nologid-title'     => 'Eròr de indicazion dei registri',
'revdelete-nologid-text'      => "Par eseguir sta funsion no te ghè indicà na destinassion par el registro opure el registro no l'esiste.",
'revdelete-no-file'           => "El file indicà no l'esiste mia.",
'revdelete-show-file-confirm' => 'Vuto dal bon vardar la version scancelà del file "<nowiki>$1</nowiki>" del $2 a le $3?',
'revdelete-show-file-submit'  => 'Sì',
'revdelete-selected'          => "'''{{PLURAL:$2|Version selezionà|Versioni selezionà}} de [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Evento del registro selezionà|Eventi del registro selezionè}}:'''",
'revdelete-text'              => "'''Le revision e le azion scancelàe le restarà visibili ne la cronologia de la pagina, ma parte del testo contegnùo no'l sarà visìbile al publico.'''
I altri aministradori de {{SITENAME}} i podarà vardar istesso i contenuti sconti e ripristinarli atraverso questa stessa interfacia, se no xe stà inpostà altre limitazion.",
'revdelete-confirm'           => 'Par piaser, conferma che vol far sta azion, che te capissi le so conseguense, e che te sì drio operar secondo le [[{{MediaWiki:Policy-url}}|linee guida]].',
'revdelete-suppress-text'     => "La sopression la se dovarìa doparar '''solo''' in sti casi qua:

* Informassion personali mia apropriate
*: ''indirissi de casa e nùmari de telefono, nùmari de previdensa sociale, etc.''",
'revdelete-legend'            => 'Inposta le seguenti limitazion su le versioni scancelàe:',
'revdelete-hide-text'         => 'Scondi el testo de ła version',
'revdelete-hide-image'        => 'Scondi i contenuti del file',
'revdelete-hide-name'         => 'Scondi azion e ogeto de la stessa',
'revdelete-hide-comment'      => "Scondi l'oggetto de ła modifega",
'revdelete-hide-user'         => "Scondi el nome o l'indirisso IP dell'autore",
'revdelete-hide-restricted'   => 'Scóndighe le informassion indicà anca ai aministradori',
'revdelete-radio-same'        => '(no stà canbiar)',
'revdelete-radio-set'         => 'Sì',
'revdelete-radio-unset'       => 'No',
'revdelete-suppress'          => 'Scondi le informazion anca ai aministradori',
'revdelete-unsuppress'        => 'Elimina le limitazion su le revision ripristinà',
'revdelete-log'               => 'Motivassion:',
'revdelete-submit'            => 'Àplica a {{PLURAL:$1|la|le}} revision selezionà',
'revdelete-logentry'          => 'gà modificà la visibilità par una revision de [[$1]]',
'logdelete-logentry'          => "gà modificà la visibilità de l'evento [[$1]]",
'revdelete-success'           => "'''Visibilità de la revision ajornà coretamente.'''",
'revdelete-failure'           => "'''No se riesse a ajornar la visibilità de la version:'''
$1",
'logdelete-success'           => "'''Visibilità de l'evento inpostà coretamente.'''",
'logdelete-failure'           => "'''No se riesse a inpostar la visibilità 'ntel registro:'''
$1",
'revdel-restore'              => 'Canbia ła visibilità',
'revdel-restore-deleted'      => 'revision scancelà',
'revdel-restore-visible'      => 'revision visibili',
'pagehist'                    => 'Cronologia de la pagina',
'deletedhist'                 => 'Cronologia scancelà',
'revdelete-content'           => 'contenuto',
'revdelete-summary'           => 'modifica ogeto',
'revdelete-uname'             => 'nome utente',
'revdelete-restricted'        => 'aplicà restrizioni ai aministradori',
'revdelete-unrestricted'      => 'gà cavà le limitazion par i aministradori',
'revdelete-hid'               => 'scondar $1',
'revdelete-unhid'             => 'mostrar $1',
'revdelete-log-message'       => '$1 par $2 {{PLURAL:$2|revision|revisioni}}',
'logdelete-log-message'       => '$1 par $2 {{PLURAL:$2|evento|eventi}}',
'revdelete-hide-current'      => 'No se pol scondar la version datà $1 a le $2, parché la xe la version corente.',
'revdelete-show-no-access'    => 'No se pol réndar visibile la version datà $1 a le $2: la xe stà marcà come "ad acesso ristreto".
No ti gà acesso su de ela.',
'revdelete-modify-no-access'  => 'No se riesse a modificar la version datà $1 a le $2: la xe stà marcà come "ad acesso ristreto".
No ti gà acesso su de ela.',
'revdelete-modify-missing'    => 'No se riesse a modificar la version con ID $1: no la ghe xe sul database!',
'revdelete-no-change'         => "''Ocio:''' la version datà $1 a le $2 la gà zà le inpostassion de visibilità da ti richieste.",
'revdelete-concurrent-change' => "No se riesse a modificar la version datà $1 a le $2: pararìa che qualchidun altro el gavesse canbià el stato de la version intanto che ti te sercavi de far la stessa roba. Daghe n'ociada sui registri.",
'revdelete-only-restricted'   => "Eròr sercando de scondar l'elemento datà $1 a le $2: no te podi inpedirghe ai aministradori de vardar na revision se no te selessioni al tenpo stesso una de le altre opzioni de restrizion.",
'revdelete-reason-dropdown'   => '*Motivassion pi comuni par la scancelassion
** Violassion de copyright
** Informassion personali inapropriàe',
'revdelete-otherreason'       => 'Altro:',
'revdelete-reasonotherlist'   => 'Altra motivassion',
'revdelete-edit-reasonlist'   => 'Modifica le motivazion par la scancelazion',
'revdelete-offender'          => 'Autor de la revision:',

# Suppression log
'suppressionlog'     => 'Registro dei ocultamenti',
'suppressionlogtext' => "Qua soto se cata na lista de le ultime scancelazioni e blochi che riguarda contenuti sconti dai aministradori. Varda la [[Special:IPBlockList|lista dei IP blocà]] par védar l'elenco dei blochi atualmente ativi.",

# Revision move
'moverevlogentry'        => 'gà spostà {{PLURAL:$3|na revision|$3 revision}} da $1 a $2',
'revisionmove'           => 'Sposta revision da "$1"',
'revmove-reasonfield'    => 'Motivassion:',
'revmove-titlefield'     => 'Pagina de destinassion:',
'revmove-badparam-title' => 'Parametri mia validi',
'revmove-nullmove-title' => 'Titolo mia valido',

# History merging
'mergehistory'                     => 'Union cronologie',
'mergehistory-header'              => 'Sta pagina la consente de unir le revision che fa parte de la cronologia d na pagina (ciamà pagina de origine) a la cronologia de na pagina piassè recente.
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
'revertmerge'        => 'Anuła union',
'mergelogpagetext'   => "Qua de seguito vien presentà na lista de le ultime operazion de unione de la cronologia de na pagina in un'altra.",

# Diffs
'history-title'            => "Cronołosia de łe modifeghe de ''$1''",
'difference'               => '(Difarense fra łe revision)',
'difference-multipage'     => '(Difarensa tra le pagine)',
'lineno'                   => 'Riga $1:',
'compareselectedversions'  => 'Confronta łe version sełesionae',
'showhideselectedversions' => 'Mostra/scondi version selessionà',
'editundo'                 => 'anuła',
'diff-multi'               => '({{PLURAL:$1|Una revision intermedia|$1 revision intermedie}} de {{PLURAL:$2|un utente|$2 utenti}} mia mostrà)',

# Search results
'searchresults'                    => 'Risultati de ła riserca',
'searchresults-title'              => 'Risultati de ła riserca de "$1"',
'searchresulttext'                 => 'Par majori informasion so ła riserca interna de {{SITENAME}}, varda [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'Riserca de \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|tute łe pajine che e inisia par "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|tute łe pajine che e punta a "$1"]])',
'searchsubtitleinvalid'            => "Riserca de '''$1'''",
'toomanymatches'                   => 'Xe stà catà massa corispondense, par piaser próa a modificar la richiesta.',
'titlematches'                     => 'Nei titołi de łe pàxene',
'notitlematches'                   => 'Nisuna corispondensa ne i titołi de łe pajine',
'textmatches'                      => 'Corispondense nel testo de le pagine',
'notextmatches'                    => 'Nisuna corispondensa nel testo de łe pajine',
'prevn'                            => '{{PLURAL:$1|presedente|presedenti $1}}',
'nextn'                            => '{{PLURAL:$1|sucesivo|sucesivi $1}}',
'prevn-title'                      => '{{PLURAL:$1|el risultato prima|i $1 risultati prima}}',
'nextn-title'                      => '{{PLURAL:$1|el risultato dopo|i $1 risultati dopo}}',
'shown-title'                      => 'Mostra {{PLURAL:$1|un risultato|$1 risultati}} par pàxena',
'viewprevnext'                     => 'Varda ($1 {{int:pipe-separator}} $2) ($3).',
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
'search-result-size'               => '$1 ({{PLURAL:$2|na paroła|$2 parołe}})',
'search-result-score'              => 'Rilevansa: $1%',
'search-redirect'                  => '(redirect $1)',
'search-section'                   => '(sesion $1)',
'search-suggest'                   => 'Forse te sercavi: $1',
'search-interwiki-caption'         => 'Projeti fradei',
'search-interwiki-default'         => 'Risultati da $1:',
'search-interwiki-more'            => '(altro)',
'search-mwsuggest-enabled'         => 'con sujerimenti',
'search-mwsuggest-disabled'        => 'sensa sujerimenti',
'search-relatedarticle'            => 'Ligà',
'mwsuggest-disable'                => 'Disabilita sugerimenti AJAX',
'searcheverything-enable'          => 'Serca in tuti quanti i namespace',
'searchrelated'                    => 'ligà',
'searchall'                        => 'tuti',
'showingresults'                   => "Qua de soto vien mostrà al massimo {{PLURAL:$1|'''1''' risultato|'''$1''' risultati}} a partir dal nùmaro '''$2'''.",
'showingresultsnum'                => "Qua soto ghe xe {{PLURAL:$3|'''1''' risultato|'''$3''' risultati}} a partir da #'''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Risultato '''$1''' de '''$3'''|Risultati '''$1 - $2''' de '''$3'''}} par '''$4'''",
'nonefound'                        => "'''Nota''': ła riserca xè efetuà par default soło in alcuni namespace. Prova a premetare \"all:\" al testo de ła riserca par sercare in tuti i namespace (conpresi pajine de discusion, modełi, ecc) opure usa el namespace desiderà come prefiso.",
'search-nonefound'                 => 'La riserca no la gà catà gnente che corisponda ai criteri de riserca.',
'powersearch'                      => 'Riserca',
'powersearch-legend'               => 'Riserca avansà',
'powersearch-ns'                   => 'Serca ne i namespace:',
'powersearch-redir'                => 'Elenca redirect',
'powersearch-field'                => 'Serca',
'powersearch-togglelabel'          => 'Selessiona:',
'powersearch-toggleall'            => 'Tuti quanti',
'powersearch-togglenone'           => 'Nissun',
'search-external'                  => 'Riserca esterna',
'searchdisabled'                   => 'La riserca interna de {{SITENAME}} no la xe ativa; par intanto te pol proár a doparar un motore de riserca esterno come Google. (Nota però che i contenuti de {{SITENAME}} presenti in sti motori i podarìa èssar mìà agiornà.)',

# Quickbar
'qbsettings'               => 'Settaggio barra menu',
'qbsettings-none'          => 'Nessun',
'qbsettings-fixedleft'     => 'Fisso a sinistra',
'qbsettings-fixedright'    => 'Fisso a destra',
'qbsettings-floatingleft'  => 'Fluttuante a sinistra',
'qbsettings-floatingright' => 'Fluttuante a destra',

# Preferences page
'preferences'                   => 'Prefarense',
'mypreferences'                 => 'prefarense',
'prefs-edits'                   => 'Nùmaro de modifiche:',
'prefsnologin'                  => 'No te ghè eseguìo el login',
'prefsnologintext'              => 'Te ghè da aver eseguìo el <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} login] par poder personalixare le to preferense.',
'changepassword'                => 'Cànbia ła password',
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
'prefs-watchlist-token'         => "Segnal par le pagine tegnùe d'ocio:",
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
'searchresultshead'             => 'Riserca',
'resultsperpage'                => 'Nùmaro de risultati par pàxena:',
'contextlines'                  => 'Righe de testo par ciascun risultato',
'contextchars'                  => 'Caratteri par linea:',
'stub-threshold'                => 'Valor minimo par i <a href="#" class="stub">colegamenti ai stub</a>:',
'recentchangesdays'             => 'Nùmaro de giòrni da mostrar ne le ultime modifiche:',
'recentchangesdays-max'         => '($1 {{PLURAL:$1|zorno|zorni}} massimo)',
'recentchangescount'            => 'Nùmaro de modìfeghe da far védar (valor predefinìo):',
'prefs-help-recentchangescount' => 'Questo include i ùltimi canbiamenti, el stòrico de le pàxene e i registri.',
'prefs-help-watchlist-token'    => 'Conpilando sto canpo co na ciave segreta vegnarà generà un feed RSS par i propri osservati speciali. Chiunque conossa la ciave in sto canpo el podarà lèzar i osservati speciali, quindi se racomanda de inserir un valore sicuro. Qua ghe xe un valore generà casualmente che se pol doparar: $1',
'savedprefs'                    => 'Le to preferense łe xè stà salvae.',
'timezonelegend'                => 'Fuso orario:',
'localtime'                     => 'Ora locale:',
'timezoneuseserverdefault'      => "Dòpara l'ora del server",
'timezoneuseoffset'             => 'Altro (spesifica difarensa)',
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
'prefs-searchoptions'           => 'Opsioni de riserca',
'prefs-namespaces'              => 'Namespace',
'defaultns'                     => 'Serca in sti namespace se no diversamente specificà:',
'default'                       => 'predefinìo',
'prefs-files'                   => 'File',
'prefs-custom-css'              => 'CSS personalixà',
'prefs-custom-js'               => 'JS personalixà',
'prefs-common-css-js'           => 'CSS/JS condiviso par tute łe skin:',
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
'prefs-help-signature'          => 'Co se scrive in te le pagine de discussion, a se gà senpre da firmar scrivendo "<nowiki>~~~~</nowiki>", che vegnarà convertìo in te la propria firma seguìa da data e ora.',
'badsig'                        => 'Erór ne ła firma non standard, verifica i tag HTML.',
'badsiglength'                  => 'La to firma la xe massa longa.
La gà da verghe al massimo $1 {{PLURAL:$1|caràtere|caràteri}}.',
'yourgender'                    => 'Sesso:',
'gender-unknown'                => 'Mia spesificà',
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
'prefs-displayrc'               => 'Preferense de visualixassion',
'prefs-diffs'                   => 'Difarense',

# User rights
'userrights'                   => 'Gestion dei parmessi relativi ai utenti',
'userrights-lookup-user'       => 'Gestion de i gruppi utente',
'userrights-user-editname'     => 'Inserir el nome utente:',
'editusergroup'                => 'Modifica grupi utente',
'editinguser'                  => "Modifica dei diriti assegnà a l'utente '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'Modifica grupi utente',
'saveusergroups'               => 'Salva grupi utente',
'userrights-groupsmember'      => 'Apartien ai grupi:',
'userrights-groupsmember-auto' => 'Menbro implìcito de:',
'userrights-groups-help'       => "Se pol modificar i grupi a cui l'utente l'è assegnà.
* Na casela de spunta selezionà la indica l'apartenenza de l'utente al grupo.
* Na casela de spunta deselezionà la indica la so mancata apartenenza al grupo.
* N'asterisco (*) l'indica che no te pol cavar un utente da un grupo na olta che te l'è zontà, o viceversa.",
'userrights-reason'            => 'Motivassion:',
'userrights-no-interwiki'      => 'No te ghè i parmessi necessari par modificar i diriti dei utenti su altri siti.',
'userrights-nodatabase'        => "El database $1 no l'esiste mìa o no l'è un database local.",
'userrights-nologin'           => "Par assegnarghe diriti ai utenti te ghè da [[Special:UserLogin|efetuar l'acesso]] come aministrador.",
'userrights-notallowed'        => 'No te ghè i parmessi necessari par assegnarghe diriti ai utenti.',
'userrights-changeable-col'    => 'Grupi che te pol canbiar',
'userrights-unchangeable-col'  => 'Grupi che no te pol canbiar',

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
'right-purge'                 => 'Neta la cache del sito par na serta pagina senza bisogno de conferma',
'right-autoconfirmed'         => 'Modifica pagine semi-protete',
'right-bot'                   => 'Da tratar come fusse un processo automatico',
'right-nominornewtalk'        => "Fà in maniera che le modifiche picenine a le pagine de discussion no le faga scatar l'avviso de messaggio novo",
'right-apihighlimits'         => 'Dòpara i limiti superiori ne le query API',
'right-writeapi'              => "Doparar l'API par la modifica de la wiki",
'right-delete'                => 'Scancela pagine',
'right-bigdelete'             => 'Scancela pagine con cronologie longhe',
'right-deleterevision'        => 'Scondi version specifiche de le pagine',
'right-deletedhistory'        => 'Varda i record scancelà de la cronologia, ma sensa el testo associà a lori',
'right-deletedtext'           => 'Vardar el testo scancelà e i canbiamenti tra dele revision scancelà',
'right-browsearchive'         => 'Visualizza pagine scancelae',
'right-undelete'              => 'Recupera na pagina',
'right-suppressrevision'      => 'Rivarda e recupera version sconte',
'right-suppressionlog'        => 'Varda i registri privati',
'right-block'                 => 'Bloca le modifiche da parte de altri utenti',
'right-blockemail'            => 'Inpedìssighe a un utente de mandar e-mail',
'right-hideuser'              => 'Bloca un nome utente, scondéndolo al publico',
'right-ipblock-exempt'        => "Scavalca i blochi de l'IP, i auto-blochi e i blochi de grupi de IP",
'right-proxyunbannable'       => 'Salta via i blochi sui proxy',
'right-unblockself'           => 'Sbloca se steso',
'right-protect'               => 'Canbia i livèi de protezion',
'right-editprotected'         => 'Modifica pagine protete',
'right-editinterface'         => "Modifica l'interfacia utente",
'right-editusercssjs'         => 'Modifica i file CSS e JS de altri utenti',
'right-editusercss'           => 'Modifica i file CSS de altri utenti',
'right-edituserjs'            => 'Modifica i file JS de altri utenti',
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
'right-sendemail'             => 'Mandarghe e-mail a cheialtri utenti',

# User rights log
'rightslog'      => 'Diriti de i utenti',
'rightslogtext'  => 'Sto qua el xe el registro de le modifiche ai diriti assegnà ai utenti.',
'rightslogentry' => "gà modificà l'apartenenza de $1 dal grupo $2 al grupo $3",
'rightsnone'     => '(nissun)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'lèxar sta pàxena',
'action-edit'                 => 'modifegare sta pajina',
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
'nchanges'                          => '$1 {{PLURAL:$1|modifega|modifeghe}}',
'recentchanges'                     => 'Ulteme modifeghe',
'recentchanges-legend'              => 'Opsion ulteme modifeghe',
'recentchangestext'                 => 'Sta pàxena la presenta łe ultime modifeghe aportàe ai contenuti de el sito.',
'recentchanges-feed-description'    => 'Sto feed riporta łe modifeghe pì resenti a i contenui del sito.',
'recentchanges-label-newpage'       => 'Sta modifica la ga creà na pagina nova',
'recentchanges-label-minor'         => 'Sta qua la xe na modifica picenina',
'recentchanges-label-bot'           => 'Sta modifica el la ga fata un bot',
'recentchanges-label-unpatrolled'   => 'Sta modifica no la xe stà gnancora verificà',
'rcnote'                            => "De seguito {{PLURAL:$1|xè elencà ła modifega pì resente aportà|xè elencae łe '''$1''' modifeghe pì resenti aportae}} al sito {{PLURAL:$2|ne łe ulteme 24 ore|ne i scorsi '''$2''' jorni}}; i dati xè ajornai a łe $5 del $4.",
'rcnotefrom'                        => " Qui di seguito sono elencate le modifiche da '''$2''' (fino a '''$1''').",
'rclistfrom'                        => 'Mostra łe modifeghe aportae a partire da $1',
'rcshowhideminor'                   => '$1 łe modifeghe minori',
'rcshowhidebots'                    => '$1 i bot',
'rcshowhideliu'                     => '$1 i utenti rejistrai',
'rcshowhideanons'                   => '$1 i utenti anonimi',
'rcshowhidepatr'                    => '$1 łe modifeghe controłae',
'rcshowhidemine'                    => '$1 łe me modifeghe',
'rclinks'                           => 'Mostra łe $1 modifeghe pì resenti aportae ne i ultimi $2 jorni<br />$3',
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
'rc-enhanced-expand'                => 'Mostra detaji (richiede JavaScript)',
'rc-enhanced-hide'                  => 'Scondi detaji',

# Recent changes linked
'recentchangeslinked'          => 'Modifeghe corełae',
'recentchangeslinked-feed'     => 'Modìfeghe ligà a sta pàxena',
'recentchangeslinked-toolbox'  => 'Modìfeghe ligà a sta pàxena',
'recentchangeslinked-title'    => 'Modifeghe corełae a "$1"',
'recentchangeslinked-noresult' => 'Nel periodo specificà no ghe xe stà nissuna modifica a le pagine colegà.',
'recentchangeslinked-summary'  => "Sta pajina spesałe e a mostra łe modifeghe pi resenti a łe pajine cołegà da queła spesifegà (o contenue ne ła categoria spesifegà).
Łe pajine contenue ne ła propria lista de i [[Special:Watchlist|Oservai spesałi]] e xé evidensiae en '''graseto'''.",
'recentchangeslinked-page'     => 'Nome de ła pajina:',
'recentchangeslinked-to'       => 'Mostra soło łe modifeghe a łe pajine cołegae a queła spesifegà',

# Upload
'upload'                      => 'Carga on file',
'uploadbtn'                   => 'Carga file',
'reuploaddesc'                => 'Lassa pèrdar el caricamento e torna al modulo de caricamento',
'upload-tryagain'             => 'Invia la descrission del file modificà',
'uploadnologin'               => 'Te devi far el login par exeguire sta operassion.',
'uploadnologintext'           => 'Te ghè da far [[Special:UserLogin|el login]]
par poder cargar dei file.',
'upload_directory_missing'    => 'La cartèla de caricamento ($1) no la esiste mìa e no la pode vegner creàda dal browser web.',
'upload_directory_read_only'  => "El server web no l'è bon de scrìvar ne la directory de caricamento ($1).",
'uploaderror'                 => 'Eror nel caricamento',
'uploadtext'                  => "Par cargar novi file, dopara el modulo qua soto.
Par védar o sercar i file zà caricà, consulta la [[Special:FileList|lista dei file caricà]]. I caricamenti de file te pol védarli nel [[Special:Log/upload|registro dei caricamenti]], le scancelasion nel [[Special:Log/delete|registro de le scancelasion]].

Par métar un file drento de na pagina, te ghè da inserir un colegamento fato come uno de sti qua:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>''' par doparar la version conpleta de sto file
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|testo alternativo]]</nowiki></tt>'''par inserir sto file co na larghessa de 200 pixel in te un riquadro a sinistra con 'testo alternativo' come descrission
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>''' par inserir diretamente un colegamento al file sensa che el se véda in te la pagina",
'upload-permitted'            => 'Tipi de file consentìi: $1.',
'upload-preferred'            => 'Tipi de file consiglià: $1.',
'upload-prohibited'           => 'Tipi de file mìa consentìi: $1.',
'uploadlog'                   => 'File caricai',
'uploadlogpage'               => 'File cargai',
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
'filetype-mime-mismatch'      => "L'estension del file no a corisponde con el tipo MIME.",
'filetype-badmime'            => 'No xe consentìo de cargar file de tipo MIME "$1".',
'filetype-bad-ie-mime'        => 'No se pode cargar sto file, parché da Internet Explorer el vegnarìa rilevà come "$1", che xe un tipo de file disativà e potensialmente pericoloso.',
'filetype-unwanted-type'      => "Cargar file de tipo '''\".\$1\"''' xe sconsiglià. {{PLURAL:\$3|El tipo de file consiglià el|I tipi de file consiglià i}} xe \$2.",
'filetype-banned-type'        => "Cargar file de tipo '''\".\$1\"''' no xe mìa consentìo. {{PLURAL:\$3|El tipo de file consentìo el|I tipi de file consentìi i}} xe \$2.",
'filetype-missing'            => 'El file no\'l gà nissuna estension (ad es. ".jpg").',
'large-file'                  => 'Se racomanda de no superar mìa le dimension de $1 par ciascun file; sto file el xe grando $2.',
'largefileserver'             => 'El file el supera le dimension consentìe da la configurazion del server.',
'emptyfile'                   => 'El file che te ghè caricà el xè aparentemente vodo. Podarìa èssar par un eror nel nome del file. Par piaser controla se te vol dal bon caricar sto file.',
'fileexists'                  => "Un file co sto nome el esiste de xà, par piaser controła '''<tt>[[:$1]]</tt>''' se no te sì sicuro de volerlo sovrascrìvar.
[[$1|thumb]]",
'filepageexists'              => "La pagina de descrizion de sto file la xe zà stà creà a '''<tt>[[:$1]]</tt>''', anca se no ghe xe gnancora un file co sto nome.
La descrizion de l'ogeto inserìa in fase de caricamento no la vegnarà mìa fora su la pagina de discussion.
Par far sì che l'ogeto el conpaja su la pagina de discussion, sarà necessario modificarla a man. [[$1|thumb]]",
'fileexists-extension'        => "Ghe xe zà un file co un nome che ghe someja a quel lì: [[$2|thumb]]
* Nome del file cargà: '''<tt>[[:$1]]</tt>'''
* Nome del file esistente: '''<tt>[[:$2]]</tt>'''
Par piaser siegli un nome difarente.",
'fileexists-thumbnail-yes'    => "El file el pararìa èssar el risultato de n'anteprima ''(thumbnail)''. [[$1|thumb]]
Verifica, par confronto, el file '''<tt>[[:$1]]</tt>'''.
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
'uploadwarning'               => 'Avixo de caricamento',
'uploadwarning-text'          => 'Par piaser, cànbia la descrission del file qua de soto e próa da novo.',
'savefile'                    => 'Salva file',
'uploadedimage'               => 'ga cargà "[[$1]]"',
'overwroteimage'              => 'gà cargà na version nova de "[[$1]]"',
'uploaddisabled'              => 'Semo spiacenti, ma el caricamento de file el xe tenporaneamente sospeso.',
'uploaddisabledtext'          => "El caricamento dei file no'l xe mìa ativo.",
'php-uploaddisabledtext'      => 'El caricamento de file tramite PHP el xe disabilità. Contròla la configurassion de file_uploads.',
'uploadscripted'              => 'Sto file contegne codexe HTML o de script, che podaria essere interpretà eroneamente da un browser web.',
'uploadvirus'                 => 'Sto file contegne un virus! Detagli: $1',
'upload-source'               => 'File de origine',
'sourcefilename'              => 'Nome del file de origine:',
'sourceurl'                   => 'URL de origine:',
'destfilename'                => 'Nome del file de destinazion:',
'upload-maxfilesize'          => 'Dimension massima del file: $1',
'upload-description'          => 'Descrission del file',
'upload-options'              => 'Opsioni de caricamento',
'watchthisupload'             => "Tien d'ocio sto file",
'filewasdeleted'              => 'Un file con sto nome xè stato xà caricà e scancełà in passato. Verifica $1 prima de caricarlo de novo.',
'upload-wasdeleted'           => "'''Ocio: te stè cargando un file che in precedenza l'era stà scancelà.'''

Verifica par piaser se xe el caso de continuare col caricamento de sto file.
Par to comodità qua ghe xe la registrazion de la scancelazion:",
'filename-bad-prefix'         => "El nome del file che te sì drio cargar el scuminsia con '''\"\$1\"''', che el xe un nome non-descritivo tipicamente assegnà automaticamente da le fotocàmare digitali. Par piaser siegli un nome piassè descritivo par el to file.",
'upload-success-subj'         => 'Caricamento conpletà',

'upload-proto-error'        => 'Protocòl mìa giusto',
'upload-proto-error-text'   => 'Par el caricamento remoto bisogna specificar URL che scuminsia con <code>http://</code> opure <code>ftp://</code>.',
'upload-file-error'         => 'Eror interno',
'upload-file-error-text'    => 'Se gà verificà un eror interno durante la creazion de un file tenporaneo sul server.
Par piaser, contatar un [[Special:ListUsers/sysop|aministrador]].',
'upload-misc-error'         => 'Eror mia identificà par el caricamento',
'upload-misc-error-text'    => '!Se gà verificà un eror mìa identificà durante el caricamento del file.
Par piaser, verifica che la URL la sia giusta e acessibile e próa da novo.
Se el problema el persiste, contatar un [[Special:ListUsers/sysop|aministrador]].',
'upload-too-many-redirects' => "In te l'URL ghe jera massa rimandi",
'upload-unknown-size'       => 'Dimension sconossiùa',
'upload-http-error'         => 'Se gà verificà un eròr HTTP: $1',

# img_auth script messages
'img-auth-accessdenied' => 'Acesso negà',
'img-auth-nopathinfo'   => "Manca el PATH_INFO.
El to server no'l xe mia configurà par passar sta informassion.
Magari el xe basà su CGI e no'l suporta img_auth.
Varda http://www.mediawiki.org/wiki/Manual:Image_Authorization.",
'img-auth-notindir'     => "El percorso richiesto no'l se cata in te la cartèla de caricamento configurà.",
'img-auth-badtitle'     => 'No se riesse a costruir un titolo valido da "$1".',
'img-auth-nologinnWL'   => 'No te sì autenticà e "$1" no\'l xe mia in te la lista bianca.',
'img-auth-nofile'       => 'El file "$1" no l\'esiste mia.',
'img-auth-isdir'        => 'Te sì drio sercar de entrar in te la cartèla "$1".
Xe parmesso entrar solo in tei file, no in te le cartèle.',
'img-auth-streaming'    => 'Streaming de "$1".',
'img-auth-public'       => 'img_auth.php el serve par butar in output dei file da na wiki privata.
Sta wiki la xe configurà come pùblica.
Par na major sicuressa, img_auth.php el xe disabilità.',
'img-auth-noread'       => 'L\'utente no\'l gà mia dirito de lèzar "$1".',

# HTTP errors
'http-invalid-url'      => 'URL mia valido: $1',
'http-invalid-scheme'   => 'Le URL col schema "$1" no le xe suportà',
'http-request-error'    => 'Richiesta HTTP falìa par via de un eror sconossùo.',
'http-read-error'       => 'Eror de letura HTTP.',
'http-timed-out'        => 'Richiesta HTTP scadùa.',
'http-curl-error'       => "Eror nel recupero de l'URL: $1",
'http-host-unreachable' => 'URL mìa ragiungibile',
'http-bad-status'       => 'Ghe xe stà un problema durante la richiesta HTTP: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL mìa ragiungibile',
'upload-curl-error6-text'  => 'Inpossibile ragiùngiar la URL specificà. Verifica che la URL la sia scrita giusta e che el sito in question el sia ativo.',
'upload-curl-error28'      => 'Tenpo scadùo par el caricamento',
'upload-curl-error28-text' => 'El sito remoto el gà messo massa tenp par rispóndar. Verifica che el sito el sia ativo, speta qualche minuto e próa da novo, eventualmente in un momento de manco tràfico.',

'license'            => "Licenza d'uso:",
'license-header'     => "Licensa d'uso",
'nolicense'          => 'Nissuna licensa indicà',
'license-nopreview'  => '(Anteprima mìa disponibile)',
'upload_source_url'  => ' (na URL coreta e acessibile)',
'upload_source_file' => ' (un file sul to computer)',

# Special:ListFiles
'listfiles-summary'     => "Sta pagina speciale la fa védar tuti i file caricài.
I file caricài piessè de recente i vien mostrà a l'inizio de la lista.
Par modificar l'ordinamento, struca su l'intestazion de la colona presièlta.",
'listfiles_search_for'  => 'Serca file par nome:',
'imgfile'               => 'file',
'listfiles'             => 'Lista dei file',
'listfiles_date'        => 'Data',
'listfiles_name'        => 'Nome',
'listfiles_user'        => 'Utente',
'listfiles_size'        => 'Dimension in byte',
'listfiles_description' => 'Descrizion',
'listfiles_count'       => 'Versioni',

# File description page
'file-anchor-link'          => 'File',
'filehist'                  => 'Cronołosia del file',
'filehist-help'             => 'Fare clic so on grupo data/ora par vardare el file come se presenta nel momento indicà.',
'filehist-deleteall'        => 'scancela tuto',
'filehist-deleteone'        => 'scancela',
'filehist-revert'           => 'ripristina',
'filehist-current'          => 'corente',
'filehist-datetime'         => 'Data/Ora',
'filehist-thumb'            => 'Miniadura',
'filehist-thumbtext'        => 'Miniadura de ła version de łe $1',
'filehist-nothumb'          => 'Nissuna miniatura',
'filehist-user'             => 'Utente',
'filehist-dimensions'       => 'Dimension',
'filehist-filesize'         => 'Dimension del file',
'filehist-comment'          => 'Ojeto',
'filehist-missing'          => 'File mancante',
'imagelinks'                => 'Cołegamenti al file',
'linkstoimage'              => '{{PLURAL:$1|Ła seguente pajina contien|Łe seguenti $1 pajine e contien}} cołegamenti al file:',
'linkstoimage-more'         => 'Piassè de $1 {{PLURAL:$1|pagina la ponta|pagine le ponta}} a sto file.
De seguito xe elencà solo {{PLURAL:$1|la prima pagina che ponta|le prime $1 pagine che ponta}} a sto file.
Se pode védar un [[Special:WhatLinksHere/$2|elenco par intiero]].',
'nolinkstoimage'            => 'Nissuna pàxena la punta a sto file.',
'morelinkstoimage'          => 'Varda i [[Special:WhatLinksHere/$1|altri colegamenti]] verso sto file.',
'redirectstofile'           => '{{PLURAL:$1|El file seguente el|I $1 file seguenti i}} redirige verso sto file:',
'duplicatesoffile'          => '{{PLURAL:$1|El file seguente el xe un dopion|I $1 file seguenti i xe dei dopioni}} de sto file ([[Special:FileDuplicateSearch/$2|ulteriori detagli]]):',
'sharedupload'              => 'Sto file el provien da $1 e poe esare utiłizà da altri projeti.',
'sharedupload-desc-there'   => 'Sto file el vien da $1 e se pode dopararlo su altri projeti.
Consulta la [$2 pàxena de descrission del file] par ulteriori informassion.',
'sharedupload-desc-here'    => 'Sto file el vien da $1 e se pode dopararlo su altri projeti.
Qua soto vien mostrà la descrission presente in te la [$2 pàxena de descrission del file].',
'filepage-nofile'           => 'No ghe xe nissun file co sto nome.',
'filepage-nofile-link'      => 'NO ghe xe un file co sto nome, ma te podi [$1 cargarlo su].',
'uploadnewversion-linktext' => 'Carga na nova version de sto file',
'shared-repo-from'          => 'da $1',
'shared-repo'               => 'un archivio condiviso',

# File reversion
'filerevert'                => 'Ripristina $1',
'filerevert-legend'         => 'Ripristina file',
'filerevert-intro'          => "Te stè par ripristinar el file '''[[Media:$1|$1]]''' a la [versione $4 del $2, $3].",
'filerevert-comment'        => 'Motivassion:',
'filerevert-defaultcomment' => 'Xe stà ripristinà la version del $1, $2',
'filerevert-submit'         => 'Ripristina',
'filerevert-success'        => "'''El file [[Media:$1|$1]]''' el xe stà ripristinà a la [$4 version del $2, $3].",
'filerevert-badversion'     => 'No esiste mìa version locali precedenti del file col timestamp richiesto.',

# File deletion
'filedelete'                  => 'Scancela $1',
'filedelete-legend'           => 'Scancela el file',
'filedelete-intro'            => "Te stè par scancelar el file '''[[Media:$1|$1]]''' insieme co' tuta la so cronologia.",
'filedelete-intro-old'        => "Te sì drio scancelar la version de '''[[Media:$1|$1]]''' del [$4 $3, $2].",
'filedelete-comment'          => 'Motivassion:',
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
'filedelete-maintenance'      => 'La scancelassion e el riprìstino dei file i xe disabilità par un tocheto par manutension.',

# MIME search
'mimesearch'         => 'Serca in base al tipo MIME',
'mimesearch-summary' => 'Sta pagina la consente de filtrare i file in base al tipo MIME. Inserissi la stringa de riserca ne la forma tipo/sototipo, ad es. <tt>image/jpeg</tt>.',
'mimetype'           => 'Tipo MIME:',
'download'           => 'descarga',

# Unwatched pages
'unwatchedpages' => 'Pàxene mìa osservàe',

# List redirects
'listredirects' => 'Elenco dei redirect',

# Unused templates
'unusedtemplates'     => 'Modèi mìa doparà',
'unusedtemplatestext' => 'In sta pagina vien elencà tuti i modèi (pagine del namespace {{ns:template}}) che no xe inclusi in nissuna pagina. Prima de scancelarli xe mejo verificar che i singoli modèi no i gabia altri colegamenti entranti.',
'unusedtemplateswlh'  => 'altri cołegamenti',

# Random page
'randompage'         => 'Na pajina a ocio',
'randompage-nopages' => 'No ghe xe nissuna pàxena in {{PLURAL:$2|tel|tei}} namespace "$1".',

# Random redirect
'randomredirect'         => 'Un redirect a caso',
'randomredirect-nopages' => 'No ghe xe nissun rimando in tel namespace "$1".',

# Statistics
'statistics'                   => 'Statisteghe',
'statistics-header-pages'      => 'Statìsteghe relative a le pàxene',
'statistics-header-edits'      => 'Statìsteghe relative a i canbiamenti',
'statistics-header-views'      => 'Statìsteghe relative a le visualizassion',
'statistics-header-users'      => 'Statistiche dei utenti',
'statistics-header-hooks'      => 'Altre statìsteghe',
'statistics-articles'          => 'Pàxene de contenuti',
'statistics-pages'             => 'Pàxene',
'statistics-pages-desc'        => 'Tute quante le pàxene de la wiki, conprese le pàxene de discussion, i rimandi, ecc.',
'statistics-files'             => 'File cargà',
'statistics-edits'             => "Modifiche a scuminsiar da l'istalassion de {{SITENAME}}",
'statistics-edits-average'     => 'Canbiamenti in media par pàxena',
'statistics-views-total'       => 'Visualizasion totali',
'statistics-views-peredit'     => 'Visualizassion par modifica',
'statistics-users'             => '[[Special:ListUsers|Utenti]] registrà',
'statistics-users-active'      => 'Utenti atìvi',
'statistics-users-active-desc' => "Utenti che gà fato almanco un'azion in {{PLURAL:$1|tel'ultimo zorno|in tei ultimi $1 zorni}}",
'statistics-mostpopular'       => 'Pagine piassè visità',

'disambiguations'      => 'Pàxene de disanbiguazion',
'disambiguationspage'  => 'Template:Disambigua',
'disambiguations-text' => "Le pagine ne la lista che segue le contien dei colegamenti a '''pagine de disanbiguazion''' e no a l'argomento a cui le dovarìà far riferimento.<br />
Vien considerà pagine de disanbiguazion tute quele che contien i modèi elencà in [[MediaWiki:Disambiguationspage]]",

'doubleredirects'            => 'Redirect dopi',
'doubleredirectstext'        => 'Sta pagina le elenca pagine che rimanda a altre pagine de rimando.
Ogni riga la contien dei colegamenti al primo e al secondo rimando, oltre a la destinassion del secondo rimando, che de solito la xe la "vera" pagina de destinassion, a cui dovarìa pontar el primo rimando.
Le righe <del>sbarà</del> le xe xà stà sistemà.',
'double-redirect-fixed-move' => '[[$1]] xe stà spostà, desso el xe solo un rimando a [[$2]]',
'double-redirect-fixer'      => 'Coretòr de redirect',

'brokenredirects'        => 'Redirect mìa giusti',
'brokenredirectstext'    => 'I seguenti rimandi i punta a pàxene che no esiste:',
'brokenredirects-edit'   => 'modifica',
'brokenredirects-delete' => 'scancela',

'withoutinterwiki'         => 'Pagine che no gà interwiki',
'withoutinterwiki-summary' => 'Le pagine indicà de seguito no le gà colegamenti a le version in altre lengue:',
'withoutinterwiki-legend'  => 'Prefisso',
'withoutinterwiki-submit'  => 'Mostra',

'fewestrevisions' => 'Pàxene con manco revision',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|byte}}',
'ncategories'             => '$1 {{PLURAL:$1|categoria|categorie}}',
'nlinks'                  => '$1 {{PLURAL:$1|colegamento|colegamenti}}',
'nmembers'                => '$1 {{PLURAL:$1|ełemento|ełementi}}',
'nrevisions'              => '$1 {{PLURAL:$1|revision|revision}}',
'nviews'                  => '$1 {{PLURAL:$1|visita|visite}}',
'specialpage-empty'       => "Sto raporto no'l contien nissun risultato.",
'lonelypages'             => 'Pàxene solitarie',
'lonelypagestext'         => 'Le pagine indicà de seguito no le gà colegamenti o trasclusioni che vegna da altre pagine de {{SITENAME}}.',
'uncategorizedpages'      => 'Pàxene prive de categorie',
'uncategorizedcategories' => 'Categorie prive de categorie',
'uncategorizedimages'     => 'File che no gà na categoria',
'uncategorizedtemplates'  => 'Modèi che no gà categorie',
'unusedcategories'        => 'Categorie mìa doparàe',
'unusedimages'            => 'File mìa doparà',
'popularpages'            => 'Pàxene pì viste',
'wantedcategories'        => 'Categorie richieste',
'wantedpages'             => 'Pàxene pì richieste',
'wantedpages-badtitle'    => 'Titolo mia valido nel grupo de risultati: $1',
'wantedfiles'             => 'File domandà',
'wantedtemplates'         => 'Modèi domandà',
'mostlinked'              => 'Pàxene piassè puntà',
'mostlinkedcategories'    => 'Categorie piassè riciamae',
'mostlinkedtemplates'     => 'Modèi piassè doparà',
'mostcategories'          => 'Articołi con piassè categorie',
'mostimages'              => 'File piassè riciamà',
'mostrevisions'           => 'Pàxene con piassè revixión',
'prefixindex'             => 'Indexe de łe voxi par letere inixiałi',
'shortpages'              => 'Pàxene curte',
'longpages'               => 'Pàxene longhe',
'deadendpages'            => 'Pàxene sensa uscita',
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
'newpages'                => 'Pajine pì resenti',
'newpages-username'       => 'Nome utente:',
'ancientpages'            => 'Pàxene pì vece',
'move'                    => 'Sposta',
'movethispage'            => 'Sposta sta pajina',
'unusedimagestext'        => "Sti file qua i esiste ma no i xe riciamà in nissuna pagina.
Par piaser tien conto che altri siti web i podarìa realizar colegamenti ai file doparando diretamente l'URL; quindi sti file i podarìa essar in uso, anca se no i se cata ne l'elenco.",
'unusedcategoriestext'    => 'Le pàxene de łe categorie indicàe de seguito łe xè stà creàe ma no le contien nissuna pàxena né sotocategoria.',
'notargettitle'           => 'Dati mancanti',
'notargettext'            => 'No te ghè indicà na pagina o un utente su cui eseguir sta operazion.',
'nopagetitle'             => 'Pagina de destinassion mia esistente',
'nopagetext'              => 'La pagina de destinassion che ti gà indicà no la esiste mìa.',
'pager-newer-n'           => '{{PLURAL:$1|1 pì resente|$1 pì resenti}}',
'pager-older-n'           => '{{PLURAL:$1|1 manco resente|$1 manco resenti}}',
'suppress'                => 'Supervision',

# Book sources
'booksources'               => 'Fonti librarie',
'booksources-search-legend' => 'Riserca de fonti librarie',
'booksources-go'            => 'Va',
'booksources-text'          => 'De seguito vien presentà un elenco de colegamenti verso siti foresti che vende libri novi e usài, atraverso i quali se pol otegner piassè informazioni sul testo sercà.',
'booksources-invalid-isbn'  => "El nùmaro ISBN inserìo no'l xe mia valido: controla de novo se te lo ghè copià justo da la fonte originale.",

# Special:Log
'specialloguserlabel'  => 'Utente:',
'speciallogtitlelabel' => 'Titolo:',
'log'                  => 'Rejistro',
'all-logs-page'        => 'Tuti i registri pùblici',
'alllogstext'          => 'Vixualixazion unificà de tuti i registri disponibili de {{SITENAME}}.
Te podi restrénzar i criteri de riçerca selezionando el tipo de registro, el nome utente, o la pàxena interessà (ocio che sti ultimi du i distingue tra majuscolo e minuscolo).',
'logempty'             => "El registro no'l contien mìa elementi corispondenti a la riçerca.",
'log-title-wildcard'   => 'Riçerca dei titoli che scuminsia con',

# Special:AllPages
'allpages'          => 'Tute łe pajine',
'alphaindexline'    => 'da $1 a $2',
'nextpage'          => 'Pàxena dopo ($1)',
'prevpage'          => 'Pajina presedente ($1)',
'allpagesfrom'      => 'Mostra łe pajine scumisiando da:',
'allpagesto'        => 'Mostra łe pajine fino a:',
'allarticles'       => 'Tute łe pajine',
'allinnamespace'    => 'Tute łe pàxene ($1 namespace)',
'allnotinnamespace' => 'Tute łe pàxene (via de quele nel namespace $1)',
'allpagesprev'      => 'Quele prima',
'allpagesnext'      => 'Quele dopo',
'allpagessubmit'    => 'Va',
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
'deletedcontributions'             => 'Contributi utente scancelà',
'deletedcontributions-title'       => 'Contributi utente scancelà',
'sp-deletedcontributions-contribs' => 'contributi',

# Special:LinkSearch
'linksearch'       => 'Cołegamenti foresti',
'linksearch-pat'   => 'Espression de riserca:',
'linksearch-ns'    => 'Namespace:',
'linksearch-ok'    => 'Serca',
'linksearch-text'  => 'Se pol doparar dei metacaràteri, par es. "*.wikipedia.org".<br />
Protocòli suportè: <tt>$1</tt>',
'linksearch-line'  => '$1 presente ne la pagina $2',
'linksearch-error' => "I metacaràteri i pode vegner doparài solo a l'inizio del nome de l'host.",

# Special:ListUsers
'listusersfrom'      => 'Mostra i utenti tacando da:',
'listusers-submit'   => 'Mostra',
'listusers-noresult' => 'Nissun utente el risponde ai criteri inpostà.',
'listusers-blocked'  => '(blocà)',

# Special:ActiveUsers
'activeusers'            => 'Lista dei utenti ativi',
'activeusers-intro'      => 'Sta qua xe la lista dei utenti che ga fato calcossa {{PLURAL:$1|sto ultimo zorno|sti ultimi $1 zorni}}.',
'activeusers-count'      => "$1 {{PLURAL:$1|canbiamento|canbiamenti}} {{PLURAL:$3|ne l'ultimo zorno|in tei ultimi $3 zorni}}",
'activeusers-from'       => 'Fà védar i utenti a partir da:',
'activeusers-hidebots'   => 'Scondi i bot',
'activeusers-hidesysops' => 'Scondi i aministradori',
'activeusers-noresult'   => 'Nissun utente catà.',

# Special:Log/newusers
'newuserlogpage'              => 'Novi utenti',
'newuserlogpagetext'          => 'Sto qua el xè el registro dei novi utenti registrai.',
'newuserlog-byemail'          => 'password spedìa par e-mail',
'newuserlog-create-entry'     => 'se ga pena rejistrà',
'newuserlog-create2-entry'    => 'ga registrà el nome utente $1',
'newuserlog-autocreate-entry' => 'Account creà automaticamente',

# Special:ListGroupRights
'listgrouprights'                      => 'Diriti dei grupi utenti',
'listgrouprights-summary'              => 'Sta qua la xe na lista dei grupi de utenti definìi su sta wiki, coi diriti asocià a ognuno.
Se pol consultar anca dele altre [[{{MediaWiki:Listgrouprights-helppage}}|informassion in pi]] sui diriti individuali.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Dirito acordà</span>
* <span class="listgrouprights-revoked">Dirito revocà</span>',
'listgrouprights-group'                => 'Grupo',
'listgrouprights-rights'               => 'Diriti',
'listgrouprights-helppage'             => 'Help:Diriti dei grupi',
'listgrouprights-members'              => '(Elenco de i menbri)',
'listgrouprights-addgroup'             => 'Pode zontar {{PLURAL:$2|al grupo|ai grupi}}: $1',
'listgrouprights-removegroup'          => 'Pode cavar {{PLURAL:$2|dal grupo|dai grupi}}: $1',
'listgrouprights-addgroup-all'         => 'Pode zontar tuti i grupi',
'listgrouprights-removegroup-all'      => 'Pode cavar tuti i grupi',
'listgrouprights-addgroup-self'        => 'Poder zontar la propria utensa in {{PLURAL:$2|te un grupo|più grupi}}: $1',
'listgrouprights-removegroup-self'     => 'Poder cavar la propria utensa da {{PLURAL:$2|un grupo|dei grupi}}: $1',
'listgrouprights-addgroup-self-all'    => 'Pode zontar la propria utensa in tuti i grupi',
'listgrouprights-removegroup-self-all' => 'Pode cavar la propria utensa da tuti i grupi',

# E-mail user
'mailnologin'          => 'Nissun indirizo a cui mandarghe el messagio',
'mailnologintext'      => 'Par inviare messagi e-mail ad altri utenti bisogna [[Special:UserLogin|acedere al sito]] e aver registrà un indirisso vałido ne łe proprie [[Special:Preferences|preferense]].',
'emailuser'            => "Scrivi a l'utente",
'emailpage'            => "Scrivi na e-mail a l'utente",
'emailpagetext'        => 'Te podi usar el modulo chi soto par mandare na e-mail a sto utente.
La e-mail che te ghè indicà ne le [[Special:Preferences|to preferense]] la vegnarà fora nel canpo "Da" de la mail, così che el destinatario el possa rispóndarte a ti diretamente.',
'usermailererror'      => "L'ogeto mail el gà restituìo l'eror:",
'defemailsubject'      => 'Messagio da {{SITENAME}}',
'usermaildisabled'     => 'e-mail utente disabiłità',
'usermaildisabledtext' => 'No xè posibiłe inviare e-mail ad altri utenti so sto wiki',
'noemailtitle'         => 'Nissun indirisso e-mail',
'noemailtext'          => "Sto utente no'l gà indicà nissuna casela e-mail valida.",
'nowikiemailtitle'     => 'Posta elétronega mia parmessa',
'nowikiemailtext'      => 'Sto utente el ga sielto de no ricévar e-mail da i altri utenti.',
'email-legend'         => "Màndeghe na e-mail a n'altro utente de {{SITENAME}}",
'emailfrom'            => 'Da:',
'emailto'              => 'A:',
'emailsubject'         => 'Ogeto:',
'emailmessage'         => 'Messajo:',
'emailsend'            => 'Invia',
'emailccme'            => 'Màndeme na copia al me indirizo.',
'emailccsubject'       => 'Copia del messagio invià a $1: $2',
'emailsent'            => 'E-mail invià',
'emailsenttext'        => 'La to e-mail la xè stà invià.',
'emailuserfooter'      => 'Sta e-mail la xe stà mandà da $1 a $2 \'traverso la funsion "Manda na e-mail a l\'utente" su {{SITENAME}}.',

# Watchlist
'watchlist'            => 'Oservai spesałi',
'mywatchlist'          => 'oservai spesałi',
'watchlistfor2'        => 'De $1 $2',
'nowatchlist'          => "No te ghè indicà pagine da tegner d'ocio.",
'watchlistanontext'    => "Per vardar e modifegar l'ełenco de i osservati speciałi bisogna $1.",
'watchnologin'         => 'Acesso mia efetuà',
'watchnologintext'     => 'Te ghè prima da far el [[Special:UserLogin|login]] par modificar la to lista de osservati speciali.',
'addedwatch'           => 'Xontà ai tòi Osservati Speciali',
'addedwatchtext'       => "La pàxena \"[[:\$1]]\" l'è stà xontà a la to [[Special:Watchlist|lista de osservati speciali]].
Le future modìfeghe a sta pagina e a la relativa pagina de discussion le sarà elencàe qui, e la paxena la vegnarà fora in '''grasseto''' ne la pàxena de le [[Special:RecentChanges|ultime modìfeghe]] par èssar pì fàsile da tegner d'ocio.",
'removedwatch'         => 'Cavà da la lista dei Osservati Speciali',
'removedwatchtext'     => 'La pàxena "[[:$1]]" la xè stà cavà da ła to łista de le [[Special:Watchlist|pàxene tegnùe de ocio]].',
'watch'                => "Tien d'ocio",
'watchthispage'        => "Tien d'ocio sta pajina",
'unwatch'              => "No sta tegner d'ocio",
'unwatchthispage'      => "Desmeti de tegner d'ocio",
'notanarticle'         => 'Sta pagina no la xè na pagina de contenuto',
'notvisiblerev'        => 'La revision la xe stà scancelà',
'watchnochange'        => "Nissun de i to ojeti osservai l'è stà modificà nel periodo mostrà.",
'watchlist-details'    => 'Ła lista de i oservai spesałi contien {{PLURAL:$1|na pajina (e ła rispetiva pajina de discusion)|$1 pajine (e łe rispetive pajine de discusion)}}.',
'wlheader-enotif'      => '* Xe ativà la notifica via e-mail.',
'wlheader-showupdated' => "* Le pàxene che xe stà modificà da la to ultima visita le xe evidensià en '''grasseto'''",
'watchmethod-recent'   => 'controło de łe ultime modifeghe par i osservati speciałi',
'watchmethod-list'     => 'controło de i osservati speciałi par modifeghe recenti',
'watchlistcontains'    => 'La lista de i osservati speciałi la contien {{PLURAL:$1|una pagina|$1 pagine}}.',
'iteminvalidname'      => "Problemi con la voxe '$1', nome mìa vałido...",
'wlnote'               => "Qua soto te cati {{PLURAL:$1|l'ultimo canbiamento|i ultimi '''$1''' canbiamenti}} ne {{PLURAL:$2|l'ultima ora|le ultime '''$2''' ore}}.",
'wlshowlast'           => 'Mostra łe ulteme $1 ore $2 jorni $3',
'watchlist-options'    => 'Opsion oservai spesałi',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Zonta a i oservai spesałi ...',
'unwatching' => 'Ełiminasion da i oservai spesałi ...',

'enotif_mailer'                => 'Sistema de notifica via e-mail de {{SITENAME}}',
'enotif_reset'                 => 'Segna tute łe pàxene visitae',
'enotif_newpagetext'           => 'Sta qua la xe na nova pàxena.',
'enotif_impersonal_salutation' => 'Utente de {{SITENAME}}',
'changed'                      => 'canbià',
'created'                      => 'creà',
'enotif_subject'               => 'La pagina $PAGETITLE de {{SITENAME}} la xe stà $CHANGEDORCREATED da $PAGEEDITOR',
'enotif_lastvisited'           => 'Varda $1 par tute le modifiche da la to ultima visita.',
'enotif_lastdiff'              => 'Varda $1 par visualizar la modifica.',
'enotif_anon_editor'           => 'utente anonimo $1',
'enotif_body'                  => 'Caro/a $WATCHINGUSERNAME,

ła pàxena $PAGETITLE de {{SITENAME}} la xè stà $CHANGEDORCREATED el $PAGEEDITDATE da $PAGEEDITOR, varda $PAGETITLE_URL par ła version atuałe.

$NEWPAGE

Somario del redator: $PAGESUMMARY $PAGEMINOREDIT

Contatta el redator:
mail: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

No ghe sarà altre notifiche in caso de ulteriori canbiamenti, a manco che ti no te visiti sta pàxena.
Te podi anca reinpostar l\'avixo de notifica par tuti i osservati speciałi de ła to łista.

             El to amichevole sistema de notifica de {{SITENAME}}

--
Par canbiar łe inpostassion de i to osservati speciałi, visita
{{fullurl:Special:Watchlist/edit}}

Par cavar la pagina da i to osservati speciałi, visita
$UNWATCHURL

Par riscontri e ulteriore assistensa:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Scanceła pàxena',
'confirm'                => 'Conferma',
'excontent'              => "el contenuto xera: '$1'",
'excontentauthor'        => "el contenuto l'era: '$1' (e l'unico contribudor l'era '$2')",
'exbeforeblank'          => "El contenuto prima de lo svodamento xera: '$1'",
'exblank'                => "ła pàxena l'era voda",
'delete-confirm'         => 'Scancela "$1"',
'delete-legend'          => 'Scancela',
'historywarning'         => "'''Ocio:''' La pàxena che te sì drio scancełar la gà na cronołogia con circa $1 {{PLURAL:$1|revision|revision}}:",
'confirmdeletetext'      => 'Te ste par scansełare na pajina co tuta ła so cronołosia. Par cortesia, conferma che xè to intension prosedere a tałe scansełasion, che te ghe piena consapevołeza de łe conseguense de ła to axion e che esa xè conforme a łe linee guida stabiłie en [[{{MediaWiki:Policy-url}}]].',
'actioncomplete'         => 'Axion conpletà',
'actionfailed'           => 'Azion mia riussìa',
'deletedtext'            => "Ła pajina ''<nowiki>$1</nowiki>'' xè sta scansełà.
Consultare el rejistro de łe $2 par n'elenco de łe pajine scansełà de rexente.",
'deletedarticle'         => 'ga scansełà "[[$1]]"',
'suppressedarticle'      => 'sconto "[[$1]]"',
'dellogpage'             => 'Scansełasion',
'dellogpagetext'         => 'Qui de seguito ghe xe un ełenco de łe pàxene scancełae de reçente.',
'deletionlog'            => 'Registro de scancełasión',
'reverted'               => 'Riportà a la version de prima',
'deletecomment'          => 'Motivassion:',
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
'rollback'          => 'Anula le modifiche',
'rollback_short'    => 'Tira indrìo',
'rollbacklink'      => 'rollback',
'rollbackfailed'    => 'Ripristino mìa riussìo',
'cantrollback'      => "No xè mia possibiłe tornar a na versión precedente: l'ultima modifica la xè stà aportà da l'unico utente che gà laorà a sto articoło.",
'alreadyrolled'     => "No xè mia possibile efetuar el ripristino de [[:$1]] da [[User:$2|$2]] ([[User talk:$2|discussion]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]); qualcun altro gà xà modificà o efetuà el ripristino de sta voxe.

L'ultima modefega l'è stà fata da [[User:$3|$3]] ([[User talk:$3|discussion]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).",
'editcomment'       => "El comento a la modifica el xera: \"''\$1''\".",
'revertpage'        => 'Anułàe łe modifeghe de [[Special:Contributions/$2|$2]] ([[User talk:$2|discussion]]), riportà a ła version de prima de [[User:$1|$1]]',
'revertpage-nouser' => 'Anulà le modìfeghe de (nome utente cavà), riportà a la version precedente de [[User:$1|$1]]',
'rollback-success'  => 'Anulà le modifiche de $1; riportà a la version precedente de $2.',

# Edit tokens
'sessionfailure' => "Se gà verificà un problema ne la session che identifica l'acesso; el sistema, par precauzion, no'l gà mìa eseguìo el comando che te ghè dato. Torna a la pagina precedente col boton 'Indrìo' del to browser, ricarica la pagina e ripróa da novo.",

# Protect
'protectlogpage'              => 'Protesion',
'protectlogtext'              => 'De seguito xe elencàe le azion de protezion e sbloco de le pagine.',
'protectedarticle'            => 'ga proteto "[[$1]]"',
'modifiedarticleprotection'   => "ga modifegà el liveło de protesion de ''[[$1]]''",
'unprotectedarticle'          => 'gà sblocà "[[$1]]"',
'movedarticleprotection'      => 'gà canbià la protesion da "[[$2]]" a "[[$1]]"',
'protect-title'               => 'Canbia el livèl de protezion par "$1"',
'prot_1movedto2'              => '[[$1]] spostà a [[$2]]',
'protect-legend'              => 'Conferma la protezion',
'protectcomment'              => 'Motivassion:',
'protectexpiry'               => 'Scadensa:',
'protect_expiry_invalid'      => 'Scadensa mìa valida.',
'protect_expiry_old'          => 'Scadensa zà passà.',
'protect-unchain-permissions' => 'Desbloca ulteriori possibilità de protession',
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
'protect-otherreason-op'      => 'Altra modivasion',
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
'undeleteviewlink'             => 'varda',
'undeletereset'                => 'Reinposta',
'undeleteinvert'               => 'Inverti selession',
'undeletecomment'              => 'Motivassion:',
'undeletedarticle'             => 'ga recuperà "[[$1]]"',
'undeletedrevisions'           => '{{PLURAL:$1|Una revision recuperà|$1 revision recuperà}}',
'undeletedrevisions-files'     => '{{PLURAL:$1|Una revision|$1 revision}} e $2 file recuperà',
'undeletedfiles'               => '{{PLURAL:$1|Un file recuperà|$1 file recuperà}}',
'cannotundelete'               => "El recupero no'l xè riussìo: qualchedun altro el podarià aver xà recuperà ła pàxena.",
'undeletedpage'                => "'''$1 la xè stà recuperà'''

Consulta el [[Special:Log/delete|registro de le scancełassion]] par vardare łe scancełassion e i recuperi pì reçenti.",
'undelete-header'              => 'Varda el [[Special:Log/delete|registro de le scancelazion]] par védar le scancelazion piassè reçenti.',
'undelete-search-box'          => 'Serca ne le pagine scancelà',
'undelete-search-prefix'       => 'Mostra le pagine el cui titolo scuminsia con:',
'undelete-search-submit'       => 'Serca',
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
'invert'         => 'inverti ła sełession',
'blanknamespace' => '(Prinsipałe)',

# Contributions
'contributions'       => 'Contribui utente',
'contributions-title' => 'Contribui de $1',
'mycontris'           => 'i me contribui',
'contribsub2'         => 'Par $1 ($2)',
'nocontribs'          => 'No xe stà catà nissuna modifica che vaga ben par sti critèri.',
'uctop'               => '(ultema par ła pajina)',
'month'               => 'Dal mexe (e presedenti):',
'year'                => "Da l'ano (e presedenti):",

'sp-contributions-newbies'             => 'Mostra soło i contribui de i novi utenti',
'sp-contributions-newbies-sub'         => 'Par i novi utenti',
'sp-contributions-newbies-title'       => 'Contributi dei utenti novi',
'sp-contributions-blocklog'            => 'blochi',
'sp-contributions-deleted'             => 'contributi utente scancelà',
'sp-contributions-logs'                => 'registri',
'sp-contributions-talk'                => 'discussion',
'sp-contributions-userrights'          => 'gestion dei parmessi relativi ai utenti',
'sp-contributions-blocked-notice'      => "Sto utente el xe atualmente blocà. L'ultimo elemento del registro dei blochi el xè riportà qua soto par informassion:",
'sp-contributions-blocked-notice-anon' => "Sto indiriso IP xè atualmente blocà. De seguito xè riportà l'ultimo ełemento del rejistro de i blochi:",
'sp-contributions-search'              => 'Riserca contribui',
'sp-contributions-username'            => 'Indirixo IP o nome utente:',
'sp-contributions-submit'              => 'Riserca',

# What links here
'whatlinkshere'            => 'Punta qua',
'whatlinkshere-title'      => "Pajine che punta a ''$1''",
'whatlinkshere-page'       => 'Pajina:',
'linkshere'                => "Łe seguenti pajine contien de i cołegamenti a '''[[:$1]]''':",
'nolinkshere'              => "Nissuna pagina la contien colegamenti che punta a '''[[:$1]]'''.",
'nolinkshere-ns'           => "No ghe xe pagine che punta a '''[[:$1]]''' nel namespace selezionà.",
'isredirect'               => '↓ Pajina de reindirisamento',
'istemplate'               => 'inclusion',
'isimage'                  => 'cołegamento imaxine',
'whatlinkshere-prev'       => '{{PLURAL:$1|presedente|presedenti $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|sucesivo|sucesivi $1}}',
'whatlinkshere-links'      => '← cołegamenti',
'whatlinkshere-hideredirs' => '$1 reindirisamenti',
'whatlinkshere-hidetrans'  => '$1 inclusion',
'whatlinkshere-hidelinks'  => '$1 cołegamenti',
'whatlinkshere-hideimages' => '$1 colegamenti a file',
'whatlinkshere-filters'    => 'Filtri',

# Block/unblock
'blockip'                         => 'Bloco utente',
'blockip-title'                   => "Bloca l'utente",
'blockip-legend'                  => "Bloca l'utente",
'blockiptext'                     => "Dòpara el moduło qua soto par blocar l'accesso in scritura a un speçifico utente o indirizo IP. El bloco el gà de èssar operà par prevegner ati de vandalismo e in streta osservansa de ła [[{{MediaWiki:Policy-url}}|policy de {{SITENAME}}]]. Speçifica in detałio el motivo del bloco nel canpo seguente (ad es. indicando i titołi de łe pàxene ogeto de vandalismo).",
'ipaddress'                       => 'Indirisso IP (IP Address)',
'ipadressorusername'              => 'Indirizo IP o nome utente',
'ipbexpiry'                       => 'Scadensa',
'ipbreason'                       => 'Motivassion:',
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
'ipboptions'                      => '2 ore:2 hours,1 jorno:1 day,3 jorni:3 days,1 setimana:1 week,2 setimane:2 weeks,1 mexe:1 month,3 mexi:3 months,6 mexi:6 months,1 ano:1 year,infinio:infinite',
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
'unblocked'                       => '[[User:$1|$1]] el xe stà sblocà',
'unblocked-id'                    => 'El bloco $1 el xe stà cavà',
'ipblocklist'                     => 'Utenti e indirixi IP blocai',
'ipblocklist-legend'              => 'Cata fora un utente blocà',
'ipblocklist-username'            => 'Nome utente o indirizo IP:',
'ipblocklist-sh-userblocks'       => '$1 i blochi dei utenti registrài',
'ipblocklist-sh-tempblocks'       => '$1 i blochi tenporanei',
'ipblocklist-sh-addressblocks'    => '$1 i blochi dei singoli IP',
'ipblocklist-submit'              => 'Serca',
'ipblocklist-localblock'          => 'Bloco local',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|Altro bloco|Altri blochi}}',
'blocklistline'                   => '$1, $2 gà blocà $3 ($4)',
'infiniteblock'                   => 'infinito',
'expiringblock'                   => 'scade el $1 a le $2',
'anononlyblock'                   => 'solo anonimi',
'noautoblockblock'                => 'gnente bloco automatico',
'createaccountblock'              => 'creazion account blocà',
'emailblock'                      => 'e-mail blocàe',
'blocklist-nousertalk'            => "no'l pol scrivar su la so pàxena de discussion",
'ipblocklist-empty'               => "L'elenco dei blochi el xe vodo.",
'ipblocklist-no-results'          => "L'indirizo IP o nome utente richiesto no'l xe blocà.",
'blocklink'                       => 'bloca',
'unblocklink'                     => 'sbloca',
'change-blocklink'                => 'canbia bloco',
'contribslink'                    => 'contribui',
'autoblocker'                     => 'Bloccà automaticamente parché el to indirisso IP el xè stà doparà de recente da "[[User:$1|$1]]". La motivassion del bloco de $1 la xe: "$2"',
'blocklogpage'                    => 'Blochi',
'blocklog-showlog'                => 'Sto utente el xe stà zà blocà tenpo fa.
Qua soto ghe xe el registro dei blochi:',
'blocklog-showsuppresslog'        => 'Sto utente el xe stà zà blocà e sconto tenpo fa.
Qua soto ghe xe el registro de le sopression:',
'blocklogentry'                   => 'ga blocà [[$1]] par un periodo de $2 $3',
'reblock-logentry'                => "gà canbià le inpostassion del bloco par [[$1]] co' na scadensa de $2 $3",
'blocklogtext'                    => "Sto qua xè un elenco de azioni de bloco e sbloco dei indirizi IP. I indirizi blocai in automatico no i xè mìa elencai. Varda l'[[Special:IPBlockList|elenco dei IP blocà]] par la lista dei indirizi el cui bloco el xè operativo.",
'unblocklogentry'                 => 'ga sblocà $1',
'block-log-flags-anononly'        => 'solo utenti anonimi',
'block-log-flags-nocreate'        => 'creasion acount blocà',
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
'ipb-otherblocks-header'          => '{{PLURAL:$1|Altro bloco|Altri blochi}}',
'ipb_cant_unblock'                => 'Eror: Inpossibile catar el bloco con ID $1. El bloco el podarìa èssar zà stà cavà.',
'ipb_blocked_as_range'            => "Eror: L'indirizo IP $1 no'l xe sogeto a bloco individual e no'l pol èssar sblocà. El bloco el xe invesse ativo a livel de l'intervalo $2, che el pol èssar sblocà.",
'ip_range_invalid'                => 'Intervało de indirissi IP mìa vałido.',
'ip_range_toolarge'               => 'No se pol mia blocar intervali piassè grandi de /$1',
'blockme'                         => 'Blòcheme',
'proxyblocker'                    => 'Bloco dei proxy verti',
'proxyblocker-disabled'           => 'Sta funzion la xe disabilità.',
'proxyblockreason'                => 'Sto indirizo IP el xe stà blocà parché el risulta èssar un proxy verto. Se prega de contatar el proprio fornitor de acesso a Internet o el suporto tènico e dirghe de sto grave problema de sicureza.',
'proxyblocksuccess'               => 'Fatto.',
'sorbsreason'                     => 'Sto indirizo IP el xe elencà come proxy verto ne la lista nera DNSBL doparà da {{SITENAME}}.',
'sorbs_create_account_reason'     => 'No se pol crear acessi novi da sto indirizo IP parché el xe elencà come proxy verto ne la lista nera DNSBL doparà da {{SITENAME}}.',
'cant-block-while-blocked'        => 'No se pode blocar altri utenti finché se xe blocài.',
'cant-see-hidden-user'            => "L'utente che te vol blocar el xe zà stà blocà e sconto. Sicome a no te ghè mia i diriti de hideuser, no te pol mia védar o canbiar el bloco de l'utente.",
'ipbblocked'                      => 'No te pui blocare o sblocare altri utenti, parché ti steso te si blocà',
'ipbnounblockself'                => 'No te pui sblocare ti steso',

# Developer tools
'lockdb'              => 'Blocca el database',
'unlockdb'            => 'Sbloca el database',
'lockdbtext'          => 'Blocar el database a vol dir sospéndar, par tuti i utenti, la possibilità de modificar le pagine o de creàrghene de nove, de canbiar le preferense e canbiar le liste dei osservati speciali, e in general de tute le operassion che richiede modifiche al database. Par piaser, conferma che te sì proprio sicuro de volerlo far e che a la fine de le operassion de manutension de desblocarè el database.',
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

Nota ben: la pàxena '''no''' la sarà spostà se ghe fusse xà na voxe col nome novo, a meno che no la sia na pàxena voda o un rimando, e senpre che no la gabia cronologia.
Questo significa che, se te fè un eror, te podi novamente rinominar na pàxena col vecio titoło, ma no te podi sovrascrìvar na pàxena xà esistente.

'''OCIO!'''
Sto canbiamento drastico el podarìa crear contratenpi che no se se speta, specialmente se se tratta de na pàxena molto visità.
Acèrtete de ver ben valutà le conseguenze del spostamento, prima de procédar.",
'movepagetalktext'             => "La corispondente pàxena de discussion la sarà spostà automaticamente insieme a ła pàxena prinsipałe, '''trane che nei seguenti casi:'''
* El spostamento de ła pàxena el xè tra namespace diversi
* In corispondenza del novo titoło ghe xe xà na pàxena de discussion (mìa voda)
* La caseła chi soto la xè stà desełezionà.",
'movearticle'                  => 'Sposta la pàxena:',
'moveuserpage-warning'         => "'''Ocio:''' Te sì drio spostar na pagina utente. Par piaser tien conto che vegnarà spostà solo la pagina e l'utente no'l vegnarà ''mia'' rinominà.",
'movenologin'                  => 'No te ghè efetuà el login',
'movenologintext'              => 'Te ghè da èssar un utente registrà ed aver efetuà el [[Special:UserLogin|login]] par poder spostar na pàxena.',
'movenotallowed'               => 'No te ghè i parmessi necessari al spostamento de le pagine.',
'movenotallowedfile'           => 'No ti gà i parmessi necessari par spostar file.',
'cant-move-user-page'          => 'No se dispone dei parmessi necessari par spostar le pàxene utente.',
'cant-move-to-user-page'       => 'No se dispone dei parmessi necessari par spostar la pàxena su na pàxena utente (ad ecezion de na sotopàxena utente).',
'newtitle'                     => 'Al novo titoło de:',
'move-watch'                   => "Tien d'ocio",
'movepagebtn'                  => 'Sposta sta pàxena',
'pagemovedsub'                 => 'Spostamento efetuà con sucesso',
'movepage-moved'               => '\'\'\'"$1" la xe stà spostà a "$2"\'\'\'',
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
'1movedto2'                    => 'ga spostà [[$1]] a [[$2]]',
'1movedto2_redir'              => 'ga spostà [[$1]] a [[$2]] tramite on reindirisamento',
'move-redirect-suppressed'     => 'reindirissamento sopresso',
'movelogpage'                  => 'Spostamenti',
'movelogpagetext'              => 'Lista de pàxene spostàe.',
'movesubpage'                  => '{{PLURAL:$1|Sotopàxena|Sotopàxene}}',
'movesubpagetext'              => 'Sta pàxena la ga $1 {{PLURAL:$1|sotopàxena|sotopàxene}} mostrà qua soto.',
'movenosubpage'                => 'Sta pàxena no la ga sotopàxene.',
'movereason'                   => 'Motivo:',
'revertmove'                   => 'ripristina',
'delete_and_move'              => 'Scanceła e sposta',
'delete_and_move_text'         => '==Scancełassion richiesta==

La voxe specificà come destinassion "[[:$1]]" l\'esiste xà. Vóto scancełarla par proseguir con ło spostamento?',
'delete_and_move_confirm'      => 'Si! Scancèła ła pàxena',
'delete_and_move_reason'       => 'Scancełà par rendere possibile lo spostamento',
'selfmove'                     => 'El novo titoło el xè conpagno del vecio; no se pol spostar ła pàxena su de ela.',
'immobile-source-namespace'    => 'No te pol spostar pàxene in tel namespace "$1"',
'immobile-target-namespace'    => 'No te pol spostar pàxene \'ntel namespace "$1"',
'immobile-target-namespace-iw' => "El colegamento interwiki no'l xe na valida destinassion in do spostar na pàxena.",
'immobile-source-page'         => 'Sta pàxena no la pol vegner spostà.',
'immobile-target-page'         => 'No te pol spostar a sto titolo.',
'imagenocrossnamespace'        => 'No se pol spostar un file verso un namespace diverso da quelo dei file.',
'imagetypemismatch'            => "L'estension nova del file no la corisponde mìa al tipo de file",
'imageinvalidfilename'         => "El nome file de destinassion no'l xe mia valido",
'fix-double-redirects'         => 'Agiorna tuti quanti i redirect che ponta al titolo originàl',
'move-leave-redirect'          => 'Crea un redirect con lo spostamento',
'protectedpagemovewarning'     => "'''Ocio:''' sta pàxena la xe stà blocà in maniera che solo i aministradori i possa spostarla. Sta qua la xe l'ultima operassion catà sul registro de la pagina:",
'semiprotectedpagemovewarning' => "'''Ocio:''' Sta pàxena la xe stà blocà in maniera che solo i utenti registrài i possa spostarla. Sta qua la xe l'ultima operassion catà sul registro de la pagina:",
'move-over-sharedrepo'         => "== File xà esistente ==
[[:$1]] l'esiste de xà su de un deposito condiviso. Meténdoghe sto nome a n'altro file, quel vecio el sarà sorascrito.",
'file-exists-sharedrepo'       => "El nome che te ghè sielto el xe xà doparà da n'altro file in te un deposito condiviso.
Par piaser, daghe un nome difarente.",

# Export
'export'            => 'Esporta pajine',
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
'export-templates'  => 'Includi i modèi',
'export-pagelinks'  => 'Includi le pàxene corelà fin a na profondità de:',

# Namespace 8 related
'allmessages'                   => 'Tuti i messagi de sistema',
'allmessagesname'               => 'Nome',
'allmessagesdefault'            => 'Testo predefinìo',
'allmessagescurrent'            => 'Testo come che el xe desso',
'allmessagestext'               => "Sta quà l'è na lista de tuti i messagi disponibili nel namespace MediaWiki.
Par piaser visita [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisation] e [http://translatewiki.net translatewiki.net] se te voli jutarne par la traduzion del software MediaWiki ne le varie lengue.",
'allmessagesnotsupportedDB'     => "'''{{ns:special}}:Allmessages''' no'l xè supportà parché '''\$wgUseDatabaseMessages''' no'l xè ativo.",
'allmessages-filter-legend'     => 'Filtro',
'allmessages-filter'            => 'Filtra par stato de personalixassion:',
'allmessages-filter-unmodified' => 'Mia modificà',
'allmessages-filter-all'        => 'Tuti quanti',
'allmessages-filter-modified'   => 'Modificà',
'allmessages-prefix'            => 'Filtra par prefisso:',
'allmessages-language'          => 'Lengua:',
'allmessages-filter-submit'     => 'Và',

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
'tooltip-pt-userpage'             => 'Ła to pajina utente',
'tooltip-pt-anonuserpage'         => 'La pàxena utente de sto indirizo IP',
'tooltip-pt-mytalk'               => 'Ła to pajina de discusion',
'tooltip-pt-anontalk'             => 'Discussioni riguardo le modifiche fate da sto ip',
'tooltip-pt-preferences'          => 'Łe me prefarense',
'tooltip-pt-watchlist'            => "Ła lista de łe pajine che te ste tegnendo d'ocio",
'tooltip-pt-mycontris'            => 'Elenco de i to contribui',
'tooltip-pt-login'                => 'Ła rejistrasion xé consijà, anca se no a xé obligatoria',
'tooltip-pt-anonlogin'            => "Te consigliemo de registrarte, ma no'l xe obligatorio.",
'tooltip-pt-logout'               => 'Va fora (logout)',
'tooltip-ca-talk'                 => 'Varda łe discusion rełative a sta pajina',
'tooltip-ca-edit'                 => 'Te pui modifegare sta pajina. Par favore usa el pulsante de anteprima prima de salvare',
'tooltip-ca-addsection'           => 'Scumisia na nova sesion',
'tooltip-ca-viewsource'           => 'Sta pajina xè proteta, ma te pui vardare el so codexe sorzente',
'tooltip-ca-history'              => 'Version presedenti de sta pajina',
'tooltip-ca-protect'              => 'Protezi sta pajina',
'tooltip-ca-unprotect'            => 'Cava la protession a sta pagina',
'tooltip-ca-delete'               => 'Scanseła sta pajina',
'tooltip-ca-undelete'             => "Ripristina la pàxena come l'era prima de la scancelassion",
'tooltip-ca-move'                 => 'Sposta sta pajina (canbia titoło)',
'tooltip-ca-watch'                => 'Zonta sta pajina a ła to lista de i oservai spesałi',
'tooltip-ca-unwatch'              => 'Cava sta pajina da ła to lista de oservai spesałi',
'tooltip-search'                  => "Serca a l'interno de {{SITENAME}}",
'tooltip-search-go'               => 'Va a na pajina con el titoło indicà, se esiste',
'tooltip-search-fulltext'         => 'Serca el testo indicà ne łe pajine',
'tooltip-p-logo'                  => 'Visita la pàxena prinsipałe',
'tooltip-n-mainpage'              => 'Visita ła pajina prinsipałe',
'tooltip-n-mainpage-description'  => 'Visita ła pajina prinsipałe',
'tooltip-n-portal'                => 'Descrision del projeto, cossa te pui fare, dove trovare e robe',
'tooltip-n-currentevents'         => 'Informasion so i eventi de atuałità',
'tooltip-n-recentchanges'         => 'Elenco de łe ulteme modifeghe del sito',
'tooltip-n-randompage'            => 'Mostra na pajina a caso',
'tooltip-n-help'                  => 'Pajine de ajuto',
'tooltip-t-whatlinkshere'         => 'Elenco de tute łe pajine che e xé cołegae a sta qua',
'tooltip-t-recentchangeslinked'   => 'Elenco de łe ulteme modifeghe a łe pajine cołegae a sta qua',
'tooltip-feed-rss'                => 'Feed RSS par sta pajina',
'tooltip-feed-atom'               => 'Feed Atom par sta pajina',
'tooltip-t-contributions'         => 'Lista de i contribui de sto utente',
'tooltip-t-emailuser'             => 'Invia on mesajo e-mail a sto utente',
'tooltip-t-upload'                => 'Carga file multimediałi',
'tooltip-t-specialpages'          => 'Lista de tute łe pajine spesałi',
'tooltip-t-print'                 => 'Version stanpabiłe de sta pajina',
'tooltip-t-permalink'             => 'Cołegamento permanente a sta version de ła pajina',
'tooltip-ca-nstab-main'           => 'Varda ea voxe',
'tooltip-ca-nstab-user'           => 'Varda ła pajina utente',
'tooltip-ca-nstab-media'          => 'Varda la pàxena del file multimedial',
'tooltip-ca-nstab-special'        => 'Sta qua e a xé na pajina spesałe, no a poe esare modifegà',
'tooltip-ca-nstab-project'        => 'Varda ła pajina de servisio',
'tooltip-ca-nstab-image'          => 'Varda ła pajina del file',
'tooltip-ca-nstab-mediawiki'      => 'Varda el messajo de sistema',
'tooltip-ca-nstab-template'       => 'Varda el modeło',
'tooltip-ca-nstab-help'           => 'Varda la pàxena de ajuto',
'tooltip-ca-nstab-category'       => 'Varda ła pajina de ła categoria',
'tooltip-minoredit'               => 'Segnała come modifega minore',
'tooltip-save'                    => 'Salva łe modifeghe',
'tooltip-preview'                 => 'Anteprima de łe modifeghe (consijà prima de salvare)',
'tooltip-diff'                    => 'Varda łe modifeghe aportae al testo',
'tooltip-compareselectedversions' => 'Varda łe difarense tra łe do version sełesionae de sta pajina.',
'tooltip-watch'                   => 'Zonta sta pajina a ła lista de i oservai spesałi',
'tooltip-recreate'                => 'Ricrea ła pàxena anca se la xè stà scancełà',
'tooltip-upload'                  => 'Intaca el caricamento',
'tooltip-rollback'                => '"Rollback" anuła łe me modifeghe a sta pajina de l\'ultimo contribudore co on soło clic.',
'tooltip-undo'                    => '"Anuła" parmete de anułare sta modifega e apre el moduło de modifega en modałità de anteprima. Parmete de inserire na modivasion ne l\'ojeto de ła modifega.',
'tooltip-preferences-save'        => 'Salve le preferense',

# Metadata
'nodublincore'      => 'Metadati Dublin Core RDF non ativi su sto server.',
'nocreativecommons' => 'Metadati Commons RDF non ativi su sto server.',
'notacceptable'     => "El server wiki no'l xè in grado di fornire i dati in un formato łeggibiłe dal client utilixà.",

# Attribution
'anonymous'        => '{{PLURAL:$1|Utente anonimo|Utenti anonimi}} de {{SITENAME}}',
'siteuser'         => '$1, utente de {{SITENAME}}',
'anonuser'         => 'Utente anonimo $1 de {{SITENAME}}',
'lastmodifiedatby' => "Sta pàxena la xè stà modificà l'ultima olta el $2, $1 da $3.",
'othercontribs'    => 'El testo atuale el xe basà su contributi de $1.',
'others'           => 'altri',
'siteusers'        => '$1, {{PLURAL:$2|utente|utenti}} de {{SITENAME}}',
'anonusers'        => '{{PLURAL:$2|Utente anonimo|Utenti anonimi}} $1 de {{SITENAME}}',
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
'markedaspatrolledtext'               => 'La revixion de [[:$1]] selessionà la xè stà segnà come verificada.',
'rcpatroldisabled'                    => 'La verifica de le ultime modifiche la xe disativà',
'rcpatroldisabledtext'                => 'La funzion de verifica de le ultime modifiche al momento no la xe ativa.',
'markedaspatrollederror'              => 'No se pol contrassegnar ła voxe come verificà',
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
'previousdiff' => '← Difarensa presedente',
'nextdiff'     => 'Difarensa sucesiva →',

# Media information
'mediawarning'         => "'''Ocio''': Sto file qua el podarìa contegner codice cativo.
La so esecuzion la podarìa danegiar el to computer.",
'imagemaxsize'         => "Dimension massima de le imagini:<br />''(su le relative pagine de descrizion)''",
'thumbsize'            => 'Grandeza de le miniature:',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|pagina|pagine}}',
'file-info'            => 'Dimensioni: $1, tipo MIME: $2',
'file-info-size'       => '($1 × $2 pixel, dimension del file: $3, tipo MIME: $4)',
'file-nohires'         => '<small>No xè disponibiłi version a risołusion pì ełevada.</small>',
'svg-long-desc'        => '(file en formato SVG, dimension nominałi $1 × $2 pixel, dimension del file: $3)',
'show-big-image'       => 'Version ad alta risołusion',
'show-big-image-thumb' => '<small>Dimension de sta anteprima: $1 × $2 pixel</small>',
'file-info-gif-looped' => 'ripetù',
'file-info-gif-frames' => '$1 {{PLURAL:$1|frame|frame}}',

# Special:NewFiles
'newimages'             => 'Galeria dei file novi',
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
'bad_image_list' => "El formato xé el seguente:

I vien considerai soltanto i elenchi puntai (righe che e scumissia con el caratere *). El primo cołegamento so ciascuna riga el dev'esare on cołegamento a on file indesiderà.
I cołegamenti sucesivi, so ła stesa riga, i xé considerai come ecesion (overo, pajine ne łe quali el file el poe esare riciamà normalmente).",

# Metadata
'metadata'          => 'Metadati',
'metadata-help'     => 'Sto file contien informasion zontive, probabilmente zonte da ła fotocamara o da ło scanner usai par crearlo o digitałizarlo. Se el file xè sta modifegà, alcuni detaji i podaria no corispondere a ła realtà.',
'metadata-expand'   => 'Mostra detaji',
'metadata-collapse' => 'Scondi detaji',
'metadata-fields'   => "I canpi rełativi a i metadati EXIF elencai en sto mesajo i verà mostrai so ła pajina de l'imasine quando ła tabeła de i metadati xè presentà ne ła forma breve. Par inpostasion predefinia, i altri canpi i verà sconti.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
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
'exif-lightsource-11'  => 'In onbrìa',
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

'exif-gpsstatus-a' => 'Mixurassion in corso',
'exif-gpsstatus-v' => 'Mixurassion interoperabiłe',

'exif-gpsmeasuremode-2' => 'Misurassion bidimensionałe',
'exif-gpsmeasuremode-3' => 'Misurassion tridimensionałe',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Chiłometri orari',
'exif-gpsspeed-m' => 'Miglia orarie',
'exif-gpsspeed-n' => 'Nodi',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Diression reałe',
'exif-gpsdirection-m' => 'Diression magnetica',

# External editor support
'edit-externally'      => 'Modifega sto file usando on programa foresto',
'edit-externally-help' => '(Par saverghene de pì consultare łe [http://www.mediawiki.org/wiki/Manual:External_editors istrusion])',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'tute',
'imagelistall'     => 'tute',
'watchlistall2'    => 'tute',
'namespacesall'    => 'Tuti',
'monthsall'        => 'tuti',
'limitall'         => 'tuti quanti',

# E-mail address confirmation
'confirmemail'              => 'Conferma indirisso e-mail',
'confirmemail_noemail'      => 'No te ghè indicà un indirizo e-mail valido ne le to [[Special:Preferences|preferense]].',
'confirmemail_text'         => "{{SITENAME}} el richiede la verifica de l'indirizo e-mail prima che te possi doparar le funzion ligà a l'e-mail.
Struca el boton qua soto par mandar na richiesta de conferma al to indirizo.
Nel messagio che te riva te catarè un colegamento che contien un codice.
Visita el colegamento col to browser par confermar che el to indirizo el xe valido.",
'confirmemail_pending'      => "El codice de conferma el xe zà stà spedìo par posta eletronica; se l'account el xe stà
creà de reçente, par piaser speta par qualche minuto che riva el codice prima de domandàrghene uno novo.",
'confirmemail_send'         => 'Spedissi un codice de conferma par e-mail',
'confirmemail_sent'         => 'Email de conferma invià.',
'confirmemail_oncreate'     => "Un codice de conferma el xe stà spedìo a l'indirizo
de posta eletronica indicà. El codice no'l xe necessario par entrar nel sito,
ma bisogna fornirlo par poder abilitar tute le funzion del sito che dòpara la posta eletronica.",
'confirmemail_sendfailed'   => "{{SITENAME}} no l'è stà bon da inviar el messagio e-mail de conferma.
Controla che l'indirizo no'l contegna carateri mìa validi.

El messagio de eror el xe: $1",
'confirmemail_invalid'      => 'Codice de conferma mìa valido. El codice el podarìa èssar scadùo.',
'confirmemail_needlogin'    => 'Xè necessario $1 par confermare el proprio indirisso e-mail.',
'confirmemail_success'      => "El to indirisso email l'è stado confermà. Ora te podi loggarte e gòderte la wiki.",
'confirmemail_loggedin'     => 'El to indirisso email el xè stà confermà.',
'confirmemail_error'        => "Qualcossa l'è andà storto nel salvar la to conferma.",
'confirmemail_subject'      => "{{SITENAME}}: e-mail par la conferma de l'indirisso",
'confirmemail_body'         => 'Qualcheduni, probabilmente ti stesso da l\'indirizo IP $1, el ga registrà n\'account "$2" con sto indirizo e-mail su {{SITENAME}}.

Par confermar che sto account el xe veramente tuo e poder ativar le funzion relative a l\'e-mail su {{SITENAME}}, verzi sto colegamento col to browser:

$3

Se l\'account *no* te lo ghè registrà ti, verzi st\'altro colegamento par anular la conferma de l\'indirizo:

$5

El codice de conferma el scadrà in automatico a le $4.',
'confirmemail_body_changed' => 'Qualcheduni, probabilmente ti steso da l\'indiriso IP $1 el ga rejistrà n\'acount "$2" con sto indiriso e-mail  so {{SITENAME}}.
Par confermare che sto acount el xè veramente tuo e poder ativar łe funsion rełative a l\'e-mail so {{SITENAME}} verzi sto cołegamento nel to browser:
$3
Se l\'acount *no* te o ghe rejistrà ti, verzi st\'altro cołegamento par anułar ła conferma de l\'indiriso e-mail:
$5
El codexe de conferma el scadrà en automatego a łe $4.',
'confirmemail_invalidated'  => 'Richiesta de conferma indirizo e-mail anulà',
'invalidateemail'           => 'Anula richiesta de conferma e-mail',

# Scary transclusion
'scarytranscludedisabled' => "[L'inclusion de pagine tra siti wiki no la xe ativa]",
'scarytranscludefailed'   => '[Inpossibile otegner el modèl $1]',
'scarytranscludetoolong'  => '[La URL la xe massa longa]',

# Trackbacks
'trackbackbox'      => 'Informazion de traciamento par sta voxe:<br />
$1',
'trackbackremove'   => '([$1 Scancela])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => 'Informasion de trackback eliminà coretamente.',

# Delete conflict
'deletedwhileediting' => "'''Ocio''': Sta pàxena la xè stà scancełà dopo che te ghè scominzià a modificarla!",
'confirmrecreate'     => "L'utente [[User:$1|$1]] ([[User talk:$1|discussion]]) el ga scancełà sta voxe dopo che te ghè scuminsià a modificarla, con ła seguente motivazion:
: ''$2''
Par piaser, conferma che te vołi dal bon ricrear sta voxe.",
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
'autosumm-new'     => "Pajina creà co '$1'",

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
'watchlisttools-view' => 'Visuałiza łe modifeghe pertinenti',
'watchlisttools-edit' => 'Visuałiza e modifega ła lista de i oservai spesałi',
'watchlisttools-raw'  => 'Modifega ła lista en formato testo',

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
'version-poweredby-credits'        => "Sta wiki la va con '''[http://www.mediawiki.org/ MediaWiki]''', copyright © 2001-$1 $2.",
'version-poweredby-others'         => 'altri',
'version-license-info'             => "MediaWiki xe un software lìbaro; te pol redistribuirlo e/o modificarlo secondo i termini de la Licensa Publica Zeneral GNU publicà da la Free Software Foundation; secondo la version 2 de la Licensa, o (a scelta tua) una qualunque altra version sucessiva.

MediaWiki el xe distribuìo sperando che el possa vegner utile, ma SENSA NISSUNA GARANSIA; sensa gnanca la garansia inplicita de COMERCIALIZASSION o de ADATAMENTO A UN USO PARTICOLARE. Varda la Licensa Publica Zeneral GNU par ulteriori detagli.

Insieme co sto programa te dovaressi 'ver ricevùo na copia de la Licensa Publica Zeneral GNU; se nò, scrìveghe a la Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA o [http://www.gnu.org/licenses/old-licenses/gpl-2.0.html và a lèzartela online].",
'version-software'                 => 'Software instalà',
'version-software-product'         => 'Prodoto',
'version-software-version'         => 'Version',

# Special:FilePath
'filepath'         => 'Percorso de un file',
'filepath-page'    => 'Nome del file:',
'filepath-submit'  => 'Va',
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
'specialpages'                   => 'Pajine spesałi',
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
'tags-tag'                => 'Nome del tag',
'tags-display-header'     => 'Aspetto ne la lista de le modifiche',
'tags-description-header' => 'Descrission conpleta del significado',
'tags-hitcount-header'    => 'Modifiche che gà dei tag',
'tags-edit'               => 'modìfega',
'tags-hitcount'           => '$1 {{PLURAL:$1|modìfega|modìfeghe}}',

# Special:ComparePages
'comparepages'   => 'Confronta le pagine',
'compare-page1'  => 'Pagina 1',
'compare-page2'  => 'Pagina 2',
'compare-rev1'   => 'Revisión 1',
'compare-rev2'   => 'Revisión 2',
'compare-submit' => 'Confronta',

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
'htmlform-float-invalid'       => "El valor indicà no'l xe mia un nùmaro.",
'htmlform-int-toolow'          => 'El valor che te ghè indicà el xe soto al minimo, che xe $1',
'htmlform-int-toohigh'         => 'El valor che te ghè indicà el xe sora al màssimo, che xe $1',
'htmlform-required'            => 'Sto vałore xè necesario',
'htmlform-submit'              => 'Manda',
'htmlform-reset'               => 'Scancèla modifiche',
'htmlform-selectorother-other' => 'Altro',

);

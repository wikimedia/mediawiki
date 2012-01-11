<?php
/** Interlingue (Interlingue)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Jmb
 * @author Malafaya
 * @author Reedy
 * @author Remember the dot
 * @author Renan
 * @author Valodnieks
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Special',
	NS_TALK             => 'Discussion',
	NS_USER             => 'Usator',
	NS_USER_TALK        => 'Usator_Discussion',
	NS_PROJECT_TALK     => '$1_Discussion',
	NS_FILE             => 'File',
	NS_FILE_TALK        => 'File_Discussion',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_Discussion',
	NS_TEMPLATE         => 'Avise',
	NS_TEMPLATE_TALK    => 'Avise_Discussion',
	NS_HELP             => 'Auxilie',
	NS_HELP_TALK        => 'Auxilie_Discussion',
	NS_CATEGORY         => 'Categorie',
	NS_CATEGORY_TALK    => 'Categorie_Discussion',
);

$specialPageAliases = array(
	'Activeusers'               => array( 'Usatores_activ' ),
	'Allmessages'               => array( 'Omni_li_missages' ),
	'Allpages'                  => array( 'Omni_li_págines' ),
	'Ancientpages'              => array( 'Págines_antiqui' ),
	'Blankpage'                 => array( 'Págine_in_blanc' ),
	'Block'                     => array( 'Blocar', 'Blocar_IP', 'Blocar_usator' ),
	'Blockme'                   => array( 'Blocar_in_mi_self' ),
	'Booksources'               => array( 'Fontes_de_libres' ),
	'BrokenRedirects'           => array( 'Redirectionmentes_ínperfect' ),
	'ChangePassword'            => array( 'Change_parol-clave' ),
	'ComparePages'              => array( 'Comparar_págines' ),
	'Confirmemail'              => array( 'Confirmar_email' ),
	'Contributions'             => array( 'Contributiones' ),
	'CreateAccount'             => array( 'Crear_conto' ),
	'Deadendpages'              => array( 'Págines_moderat' ),
	'DeletedContributions'      => array( 'Contributiones_deletet' ),
	'Disambiguations'           => array( 'Disambiguitones' ),
	'DoubleRedirects'           => array( 'Redirectionmentes_duplic' ),
	'EditWatchlist'             => array( 'Redacter_liste_de_págines_vigilat' ),
	'Emailuser'                 => array( 'Email_de_usator' ),
	'Export'                    => array( 'Exportar' ),
	'Fewestrevisions'           => array( 'Revisiones_max_poc' ),
	'FileDuplicateSearch'       => array( 'Sercha_de_file_duplicat' ),
	'Filepath'                  => array( 'Viette_de_file' ),
	'Import'                    => array( 'Importar' ),
	'Invalidateemail'           => array( 'Email_ínvalid' ),
	'BlockList'                 => array( 'Liste_de_bloc', 'Liste_de_bloces', 'Liste_de_bloc_de_IP' ),
	'LinkSearch'                => array( 'Sercha_de_catenun' ),
	'Listadmins'                => array( 'Liste_de_administratores' ),
	'Listbots'                  => array( 'Liste_de_machines' ),
	'Listfiles'                 => array( 'Liste_de_files', 'Liste_de_file', 'Liste_de_figura' ),
	'Listgrouprights'           => array( 'Jures_de_gruppe_de_liste', 'Jures_de_gruppe_de_usator' ),
	'Listredirects'             => array( 'Liste_de_redirectionmentes' ),
	'Listusers'                 => array( 'Liste_de_usatores', 'Liste_de_usator' ),
	'Lockdb'                    => array( 'Serrar_DB' ),
	'Log'                       => array( 'Diarium', 'Diariumes' ),
	'Lonelypages'               => array( 'Págines_solitari', 'Págines_orfan' ),
	'Longpages'                 => array( 'Págines_long' ),
	'MergeHistory'              => array( 'Historie_de_fusion' ),
	'MIMEsearch'                => array( 'Serchar_MIME' ),
	'Mostcategories'            => array( 'Plu_categories' ),
	'Mostimages'                => array( 'Files_max_ligat', 'Plu_files', 'Plu_figuras' ),
	'Mostlinked'                => array( 'Págines_max_ligat', 'Max_ligat' ),
	'Mostlinkedcategories'      => array( 'Categories_max_ligat', 'Categories_max_usat' ),
	'Mostlinkedtemplates'       => array( 'Avises_max_ligat', 'Avises_max_usat' ),
	'Mostrevisions'             => array( 'Plu_revisiones' ),
	'Movepage'                  => array( 'Mover_págine' ),
	'Mycontributions'           => array( 'Mi_contributiones' ),
	'Mypage'                    => array( 'Mi_págine' ),
	'Mytalk'                    => array( 'Mi_discussion' ),
	'Myuploads'                 => array( 'Mi_cargamentes' ),
	'Newimages'                 => array( 'Nov_files', 'Nov_figuras' ),
	'Newpages'                  => array( 'Nov_págines' ),
	'PasswordReset'             => array( 'Recomensar_parol-clave' ),
	'PermanentLink'             => array( 'Catenun_permanen' ),
	'Popularpages'              => array( 'Págines_populari' ),
	'Preferences'               => array( 'Preferenties' ),
	'Prefixindex'               => array( 'Index_de_prefixe' ),
	'Protectedpages'            => array( 'Págines_gardat' ),
	'Protectedtitles'           => array( 'Titules_gardat' ),
	'Randompage'                => array( 'Sporadic', 'Págine_sporadic' ),
	'Randomredirect'            => array( 'Redirectionmente_sporadic' ),
	'Recentchanges'             => array( 'Nov_changes' ),
	'Recentchangeslinked'       => array( 'Changes_referet', 'Changes_relatet' ),
	'Revisiondelete'            => array( 'Deleter_revision' ),
	'RevisionMove'              => array( 'Mover_revision' ),
	'Search'                    => array( 'Serchar' ),
	'Shortpages'                => array( 'Págines_curt' ),
	'Specialpages'              => array( 'Págines_special' ),
	'Statistics'                => array( 'Statistica' ),
	'Tags'                      => array( 'Puntales' ),
	'Unblock'                   => array( 'Desblocar' ),
	'Uncategorizedcategories'   => array( 'Categories_íncategorizet' ),
	'Uncategorizedimages'       => array( 'Files_íncategorizet', 'Figuras_íncategorizet' ),
	'Uncategorizedpages'        => array( 'Págines_íncategorizet' ),
	'Uncategorizedtemplates'    => array( 'Avises_íncategorizet' ),
	'Undelete'                  => array( 'Restaurar' ),
	'Unlockdb'                  => array( 'Disserrar_DB' ),
	'Unusedcategories'          => array( 'Categories_sin_use' ),
	'Unusedimages'              => array( 'Files_sin_use', 'Figuras_sin_use' ),
	'Unusedtemplates'           => array( 'Avises_sin_use' ),
	'Unwatchedpages'            => array( 'Págines_desvigilat' ),
	'Upload'                    => array( 'Cargar_file' ),
	'UploadStash'               => array( 'Cargamente_stash_de_file' ),
	'Userlogin'                 => array( 'Intrar' ),
	'Userlogout'                => array( 'Surtida' ),
	'Userrights'                => array( 'Jures_de_usator', 'Crear_administrator', 'Crear_machine' ),
	'Wantedcategories'          => array( 'Categories_carit' ),
	'Wantedfiles'               => array( 'Files_carit' ),
	'Wantedpages'               => array( 'Págines_carit', 'Catenunes_ínperfect' ),
	'Wantedtemplates'           => array( 'Avises_carit' ),
	'Watchlist'                 => array( 'Liste_de_págines_vigilat' ),
	'Whatlinkshere'             => array( 'Quo_catenunes_ci' ),
	'Withoutinterwiki'          => array( 'Sin_interwiki' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Ultracatenun:',
'tog-highlightbroken'         => 'Formater catenunes ruptet <a href="" class="new">quam ti</a> (alternative: quam ti<a href="" class="internal">?</a>)',
'tog-justify'                 => 'Justificar paragrafes',
'tog-hideminor'               => 'Ocultar redactiones minori in nov changes',
'tog-hidepatrolled'           => 'Ocultar redactiones vigilat in nov changes',
'tog-newpageshidepatrolled'   => 'Ocultar págines vigilat de liste de nov págines',
'tog-usenewrc'                => 'Usar nov changes augmentat (exige JavaScript)',
'tog-numberheadings'          => 'Auto-numerar rublicas',
'tog-showtoolbar'             => 'Monstrar barre de utensile de redaction (exige JavaScript)',
'tog-editondblclick'          => 'Redacter págines in clacca duplic (exige JavaScript)',
'tog-editsection'             => 'Permisser redaction de division usant catenunes [redacter]',
'tog-editsectiononrightclick' => 'Permisser redaction de division per clacca dextri in titules de division (exige JavaScript)',
'tog-showtoc'                 => 'Monstrar tabelle de contenetes (por págines che plu de 3 divisiones)',
'tog-rememberpassword'        => 'Memorar mi registre in ti computator (por um max de $1 {{PLURAL:$1|die|dies}})',
'tog-watchcreations'          => 'Adjunter págines que yo crear por mi liste de págines vigilat',
'tog-watchdefault'            => 'Adjunter págines que yo redacter por mi liste de págines vigilat',
'tog-watchmoves'              => 'Adjunter págines que yo mover por mi liste de págines vigilat',
'tog-watchdeletion'           => 'Adjunter págines que yo deleter por mi liste de págines vigilat',
'tog-minordefault'            => 'Marcar omni li redactiones minori per contumacie',
'tog-previewontop'            => 'Monstrar prevision ante de buxe de redaction',
'tog-previewonfirst'          => 'Monstrar prevision in prim redaction',
'tog-nocache'                 => 'Desvalidar caching de págine',
'tog-enotifwatchlistpages'    => 'Inviar me e-mail quande un págine de mi liste de págines vigilat es changeat',
'tog-enotifusertalkpages'     => 'Inviar me e-mail quande mi págine de discussion es changeat',
'tog-enotifrevealaddr'        => 'Revelar mi adresse de e-mail in notificationes de e-mail',
'tog-shownumberswatching'     => 'Monstrar li númere de usatores vigilant',
'tog-oldsig'                  => 'Prevision de signature in existentie:',
'tog-fancysig'                => 'Tractar signature quam textu wiki (sin un catenun auto-crate)',
'tog-externaleditor'          => 'Usar redactor extern per contumacie (solmen por usatores expert, besona de colocationes special in tui computator. [//www.mediawiki.org/wiki/Manual:External_editors Plu information.])',
'tog-externaldiff'            => 'Usar diferentie extern per contumacie (solmen por usatores expert, besona de colocationes special in tui computator. [//www.mediawiki.org/wiki/Manual:External_editors Plu information.])',
'tog-showjumplinks'           => 'Permisser catenunes de accessebilitá "saltar a"',
'tog-uselivepreview'          => 'Usar prevision in vivi (exige JavaScript) (experimental)',
'tog-forceeditsummary'        => 'Suggester me quande intrar un redaction che summarium in blanc',
'tog-watchlisthideown'        => 'Ocultar mi redactiones del liste de págines vigilat',
'tog-watchlisthidebots'       => 'Ocultar redactiones de machine del liste de págines vigilat',
'tog-watchlisthideminor'      => 'Ocultar redactiones minori del liste de págines vigilat',
'tog-watchlisthideliu'        => 'Ocultar redactiones de usatores registrat del liste de págines vigilat',
'tog-watchlisthideanons'      => 'Ocultar redactiones de usatores anonim del liste de págines vigilat',
'tog-watchlisthidepatrolled'  => 'Ocultar redactiones vigilat del liste de págines vigilat',
'tog-nolangconversion'        => 'Desvalidar conversion de variantes',
'tog-ccmeonemails'            => 'Inviar me copies de e-mailes que yo invia por altri usatores',
'tog-diffonly'                => 'Ne monstrar li contenete de págine in infra del changes',
'tog-showhiddencats'          => 'Monstrar categories ne visibil',
'tog-noconvertlink'           => 'Desvalidar conversion de titul de catenun',
'tog-norollbackdiff'          => 'Omisser change pos de efectuar un rollback',

'underline-always'  => 'Sempre',
'underline-never'   => 'Nequande',
'underline-default' => 'Criterie de navigator',

# Dates
'sunday'        => 'soledí',
'monday'        => 'lunedí',
'tuesday'       => 'mardí',
'wednesday'     => 'mercurdí',
'thursday'      => 'jovedí',
'friday'        => 'venerdí',
'saturday'      => 'saturdí',
'sun'           => 'sol',
'mon'           => 'lun',
'tue'           => 'mar',
'wed'           => 'mer',
'thu'           => 'jov',
'fri'           => 'ven',
'sat'           => 'sat',
'january'       => 'januar',
'february'      => 'februar',
'march'         => 'marte',
'april'         => 'april',
'may_long'      => 'may',
'june'          => 'junio',
'july'          => 'julí',
'august'        => 'august',
'september'     => 'septembre',
'october'       => 'octobre',
'november'      => 'novembre',
'december'      => 'decembre',
'january-gen'   => 'januar',
'february-gen'  => 'februar',
'march-gen'     => 'marte',
'april-gen'     => 'april',
'may-gen'       => 'may',
'june-gen'      => 'junio',
'july-gen'      => 'juli',
'august-gen'    => 'august',
'september-gen' => 'septembre',
'october-gen'   => 'octobre',
'november-gen'  => 'novembre',
'december-gen'  => 'decembre',
'jan'           => 'jan',
'feb'           => 'feb',
'mar'           => 'mar',
'apr'           => 'apr',
'may'           => 'may',
'jun'           => 'jun',
'jul'           => 'jul',
'aug'           => 'aug',
'sep'           => 'sep',
'oct'           => 'oct',
'nov'           => 'nov',
'dec'           => 'dec',

# Categories related messages
'pagecategories'         => '{{PLURAL:$1|Categorie|Categories}}',
'category_header'        => 'Articules in categorie "$1"',
'subcategories'          => 'Subcategories',
'category-media-header'  => 'Multimedia in categorie "$1"',
'category-empty'         => "''Ti categorie currentmen ne contene págines o media.''",
'hidden-categories'      => '{{PLURAL:$1|Categorie ocultat|Categories ocultat}}',
'category-subcat-count'  => '{{PLURAL:$2|Ti categorie ha solmen li sequent subcategorie.|Ti categorie ha li sequent {{PLURAL:$1|subcategorie|$1 subcategories}}, de un total de $2.}}',
'category-article-count' => '{{PLURAL:$2|Ti categorie contene solmen li sequent págine.|Li sequent {{PLURAL:$1|págine es|$1 págine es}} in ti categorie, de un total de $2.}}',
'category-file-count'    => '{{PLURAL:$2|Ti categorie contene solmen li sequent file.|Li sequent {{PLURAL:$1|file es|$1 files es}} in ti categorie, de un total de $2.}}',
'listingcontinuesabbrev' => 'cont.',
'noindex-category'       => 'Págines ne indexet',

'about'         => 'Concernent',
'article'       => 'Articul',
'newwindow'     => '(inaugurar in nov planca de fenestre)',
'cancel'        => 'Anullar',
'moredotdotdot' => 'Plu...',
'mypage'        => 'Mi págine',
'mytalk'        => 'Mi discussion',
'anontalk'      => 'Discussion por ti ci IP',
'navigation'    => 'Navigation',
'and'           => '&#32;e',

# Cologne Blue skin
'qbfind'         => 'Constatar',
'qbedit'         => 'Redacter',
'qbpageoptions'  => 'Págine de optiones',
'qbpageinfo'     => 'Págine de information',
'qbmyoptions'    => 'Mi optiones',
'qbspecialpages' => 'Págines special',
'faq'            => 'FAQ',

# Vector skin
'vector-action-addsection'       => 'Adjunter tema',
'vector-action-delete'           => 'Deleter',
'vector-action-move'             => 'Mover',
'vector-action-protect'          => 'Gardar',
'vector-action-undelete'         => 'Restituer',
'vector-action-unprotect'        => 'Desgardar',
'vector-simplesearch-preference' => 'Premisser suggestiones de sercha argumentat (pelle Vector solmen)',
'vector-view-create'             => 'Crear',
'vector-view-edit'               => 'Redacter',
'vector-view-history'            => 'Vider historie',
'vector-view-view'               => 'Leer',
'vector-view-viewsource'         => 'Vider fonte',
'actions'                        => 'Actiones',
'namespaces'                     => 'Spacies de nómine',
'variants'                       => 'Variantes',

'errorpagetitle'    => 'Errore',
'returnto'          => 'Retornar a $1.',
'tagline'           => 'De {{SITENAME}}',
'help'              => 'Auxilie',
'search'            => 'Serchar',
'searchbutton'      => 'Serchar',
'go'                => 'Ear',
'searcharticle'     => 'Ear',
'history'           => 'Historie',
'history_short'     => 'Historie',
'updatedmarker'     => 'modernisat desde mi ultim visitation',
'printableversion'  => 'Version por impression',
'permalink'         => 'Catenun permanent',
'edit'              => 'Redacter',
'create'            => 'Crear',
'editthispage'      => 'Redacter',
'delete'            => 'Deleter',
'deletethispage'    => 'Deleter ti págine',
'undelete_short'    => 'Restaurar {{PLURAL:$1|1 modification|$1 modificationes}}',
'protect'           => 'Gardar',
'protect_change'    => 'Change',
'protectthispage'   => 'Gardar ti págine',
'unprotect'         => 'Changear protection',
'unprotectthispage' => 'Changear protection de ti págine',
'newpage'           => 'Nov págine',
'talkpage'          => 'Parlar in ti págine',
'talkpagelinktext'  => 'Discussion',
'specialpage'       => 'Págine special',
'personaltools'     => 'Utensiles personal',
'postcomment'       => 'Nov division',
'articlepage'       => 'Vider li articul',
'talk'              => 'Discussion',
'views'             => 'Vistas',
'toolbox'           => 'Buxe de utensiles',
'userpage'          => 'Vider págine del usator',
'projectpage'       => 'Vider págine de projecte',
'imagepage'         => 'Vider li págine de figura',
'templatepage'      => 'Vider li págine de avise',
'viewhelppage'      => 'Vider págine de auxilie',
'viewtalkpage'      => 'Vider discussion',
'otherlanguages'    => 'Altri lingues',
'redirectedfrom'    => '(Redirectet de $1)',
'redirectpagesub'   => 'Págine de redirecterion',
'lastmodifiedat'    => 'Ti págine esset per ultim témpor redactet in $1, in $2.',
'viewcount'         => 'Ti págine ha esset accesset {{PLURAL:$1|un vez|$1 vezes}}.',
'protectedpage'     => 'Un protectet págine',
'jumpto'            => 'Saltar a:',
'jumptonavigation'  => 'navigation',
'jumptosearch'      => 'serchar',
'view-pool-error'   => 'It me dole que li servitores es totalmen cargat in li moment.
Anc mult usatores es provant vider ti págine.
Pleser atende un témpor quelc ante que vu prova accesser ti págine denov.

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Concernent {{SITENAME}}',
'aboutpage'            => 'Project:Concernent',
'copyright'            => 'Contenete disponibil sub $1.',
'copyrightpage'        => '{{ns:project}}:Jure editorial',
'currentevents'        => 'Eventus current',
'currentevents-url'    => 'Project:Eventus current',
'disclaimers'          => 'Advertimentes',
'disclaimerpage'       => 'Project:Advertimentes comun',
'edithelp'             => 'Redacter auxilie',
'edithelppage'         => 'Help:Redactant',
'helppage'             => 'Help:Contenete',
'mainpage'             => 'Págine principal',
'mainpage-description' => 'Págine principal',
'portal'               => 'Págine de comunité',
'portal-url'           => 'Project:Págine de comunité',
'privacy'              => 'Politica de privatie',
'privacypage'          => 'Project:Politica de privatie',

'versionrequired'     => 'Version $1 de MediaWiki exiget',
'versionrequiredtext' => 'Version $1 de MediaWiki es exiget por usar ti págine.
Vider [[Special:Version|págine de version]].',

'retrievedfrom'           => 'Recuperat de "$1"',
'youhavenewmessages'      => 'Vu have $1 ($2).',
'newmessageslink'         => 'nov missages',
'newmessagesdifflink'     => 'vider missages antiqui',
'youhavenewmessagesmulti' => 'Vu have nov missages in $1',
'editsection'             => 'redacter',
'editold'                 => 'redacter',
'viewsourceold'           => 'vider fonte',
'editlink'                => 'redacter',
'viewsourcelink'          => 'vider fonte',
'editsectionhint'         => 'Redacter division: $1',
'toc'                     => 'Contenetes',
'showtoc'                 => 'monstrar',
'hidetoc'                 => 'ocultar',
'thisisdeleted'           => 'Vider o restaurar $1?',
'viewdeleted'             => 'Vider $1?',
'site-atom-feed'          => '$1 Atom feed',
'page-atom-feed'          => '"$1" Atom feed',
'red-link-title'          => '$1 (págine ne existe)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Articul',
'nstab-user'      => 'Págine de usator',
'nstab-special'   => 'Págine special',
'nstab-project'   => 'Págine de projecte',
'nstab-image'     => 'Figura',
'nstab-mediawiki' => 'Missage',
'nstab-template'  => 'Avise',
'nstab-help'      => 'Auxilie',
'nstab-category'  => 'Categorie',

# General errors
'error'                => 'Erra',
'missing-article'      => 'Li funde de datas ne constatat li textu de un págine que it posse constatar, nominat "$1" $2.

Ti es usualmen causat per sequent un diferentie ne modernisat o catenun de historie por un págine que ha esset deletet.

Si ti ne es li casu, vu posse have constatat un bug in li software.
Pleser raporta it por un [[Special:ListUsers/sysop|administrator]], formant note de li URL.',
'missingarticle-rev'   => '(revision#: $1)',
'unexpected'           => 'Valor ínexpectat: "$1"="$2".',
'badtitle'             => 'Titul mal',
'badtitletext'         => 'Li titul de págine solicitat esset ínvalid, vacui, o íncorectmen ligat inter-lingue o un titul inter-wiki.
It posse contene un o plu carácteres quel ne posse esser usat in titules.',
'wrong_wfQuery_params' => 'Normes íncorect por wfQuery()<br />
Function: $1<br />
Question: $2',
'viewsource'           => 'Vider fonte',
'viewsourcefor'        => 'por $1',
'viewsourcetext'       => 'Vu posse vider e copiar li contenete de ti págine:',
'titleprotected'       => 'Ti titul ha esset gardat de creation per [[User:$1|$1]]. 
Li motive dat es "\'\'$2\'\'".',

# Virus scanner
'virus-badscanner'     => "Configuration maliciosi: virus desconosset examinat: ''$1''",
'virus-scanfailed'     => 'scandesion fallit (code $1)',
'virus-unknownscanner' => 'antivírus desconosset:',

# Login and logout pages
'logouttext'                 => "'''Vu ha terminat vor session.'''
Vu posse continuar usar {{SITENAME}} anonimimen, o vu posse [[Special:UserLogin|aperter un session denov]] quam li sam usator o quam un diferent usator.
Nota que alcun págines posse continuar esser monstrat quam si vu esset registrat, til que vu vacua li cache de tui navigator.",
'welcomecreation'            => '== Benevenit, $1! == 
Tui conto hat esset creat. 
Ne oblivia de mutar tui [[Special:Preferences|preferenties de {{SITENAME}}]].',
'yourname'                   => 'Nómine de usator:',
'yourpassword'               => 'Parol-clave:',
'yourpasswordagain'          => 'Parol-clave denov:',
'remembermypassword'         => 'Memorar mi parol-clave in ti navigator (por un maxim de $1 {{PLURAL:$1|die|dies}})',
'yourdomainname'             => 'Tui dominia:',
'login'                      => 'Aperter session',
'nav-login-createaccount'    => 'Crear un conto o intrar',
'loginprompt'                => 'Cookies deve esser permisset por intrar in {{SITENAME}}.',
'userlogin'                  => 'Crear un conto o intrar',
'userloginnocreate'          => 'Intrar',
'logout'                     => 'Surtida',
'userlogout'                 => 'Surtida',
'notloggedin'                => 'Vu ne ha intrat',
'nologin'                    => 'Ne have un conto? $1.',
'nologinlink'                => 'Crear un conto',
'createaccount'              => 'Crear un conto',
'gotaccount'                 => 'Ja have un conto? $1.',
'gotaccountlink'             => 'Intrar',
'userlogin-resetlink'        => 'Obliviat tui detallies de registre?',
'badretype'                  => 'Li passa-paroles queles vu tippat ne es identic.',
'userexists'                 => 'Nómine de usator ja in usu.
Pleser opta por un nómine diferent.',
'loginerror'                 => 'Erra in initiation del session',
'nocookieslogin'             => '{{SITENAME}} utilisa cookies por far intrar usatores. Vu nu ne permisse cookies. Ples permisser les e provar denov.',
'loginsuccesstitle'          => 'Apertion de session successosi',
'loginsuccess'               => 'Vu ha apertet vor session in {{SITENAME}} quam "$1".',
'wrongpassword'              => 'Parol-clave íncorect inscrit.
Pleser prova denov.',
'wrongpasswordempty'         => 'Parol-clave inscrit esset nud.
Pleser prova denov.',
'mailmypassword'             => 'Nov parol-clave per e-mail',
'throttled-mailpassword'     => 'Un parol-clave amemora ha ja esset inviat, intra li ultim {{PLURAL:$1|hor|$1 hores}}.
Por preventer misusa, solmen un parol-clave amemora va esser inviat per {{PLURAL:$1|hor|$1 hores}}.',
'acct_creation_throttle_hit' => 'Vu ja ha creat $1 contos. Vu ne posse crear pli mult quam to.',
'usernamehasherror'          => 'Nómine de usator ne posse contene mixtura de carácteres',
'loginlanguagelabel'         => 'Lingue: $1',
'suspicious-userlogout'      => 'Tui petition por surtir esset desaprobat pro que probabilmen esset inviat per un navigator ruptet o servitor de autorisation che caching.',

# Change password dialog
'oldpassword' => 'Anteyan passa-parol:',
'newpassword' => 'Nov passa-parol:',
'retypenew'   => 'Confirmar nov passa-parol',

# Special:PasswordReset
'passwordreset-username' => 'Vor nómine usatori',

# Edit page toolbar
'bold_sample'     => 'Textu in nigri',
'bold_tip'        => 'Textu in nigri',
'italic_sample'   => 'Textu cursivmen',
'italic_tip'      => 'Textu cursivmen',
'link_sample'     => 'Titul de catenun',
'link_tip'        => 'Catenun intern',
'extlink_sample'  => 'http://www.example.com titul de catenun',
'extlink_tip'     => 'Catenun extern (memorar prefixe http://)',
'headline_sample' => 'Division de nivelle 2',
'headline_tip'    => 'Division de nivelle 2',
'nowiki_sample'   => 'Ignorar formate wiki',
'nowiki_tip'      => 'Ignorar formate wiki',
'image_tip'       => 'File fixat',
'media_tip'       => 'Catenun in file de multimedia',
'sig_tip'         => 'Tui signature che hor e date',
'hr_tip'          => 'Linea horizontal (use sin excess)',

# Edit pages
'summary'                          => 'Summarium:',
'subject'                          => 'Tema/Division:',
'minoredit'                        => 'Ti es un redaction minori',
'watchthis'                        => 'Vigilar ti págine',
'savearticle'                      => 'Conservar págine',
'preview'                          => 'Prevision',
'showpreview'                      => 'Monstrar prevision',
'showdiff'                         => 'Monstrar changes',
'anoneditwarning'                  => "'''Advertiment:''' Vu ne esser registrat.
Tui adresse de IP va esser registrat in historico de redactiones de ti págine.",
'summary-preview'                  => 'Prevision de summarium:',
'subject-preview'                  => 'Prevision de Tema/Division:',
'whitelistedittitle'               => 'Exige que vu es identificat por redacter',
'whitelistedittext'                => 'Vu have que $1 por redacter págines.',
'loginreqtitle'                    => 'Apertion de session obligatori',
'accmailtitle'                     => 'Li passa-parol es inviat.',
'accmailtext'                      => "Li passa-parol por '$1' ha esset inviat a $2.",
'newarticle'                       => '(Nov)',
'newarticletext'                   => "Vu have sequet un catenun por un págine que ne existe ancor.
Por crear li págine, comensa tippant in li buxe in infra (vider li [[{{MediaWiki:Helppage}}|págine de auxilie]] por plu informationes).
Si vu es ci per errore, clacca in li buton '''retornar''' in tui navigator.",
'noarticletext'                    => 'Vi currentmen ne textu in ti págine.
Vu posse [[Special:Search/{{PAGENAME}}|serchar por ti titul de págine]] in altri págines,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} serchar li diariumes relatet], o [{{fullurl:{{FULLPAGENAME}}|action=edit}} redacter ti págine]</span>.',
'noarticletext-nopermission'       => 'Vi currentmen ne textu in ti págine.
Vu posse [[Special:Search/{{PAGENAME}}|serchar por titul de ti págine]] in altri págines,
o <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} serchar li diariumes relatet]</span>.',
'userpage-userdoesnotexist'        => 'Conto de usator "$1" ne es registrat.
Pleser controla si vu cari crear/redacter ti págine.',
'userpage-userdoesnotexist-view'   => 'Conto de usator "$1" ne es registrat.',
'usercssyoucanpreview'             => "'''Punta:''' Usa li buton \"{{int:showpreview}}\" por provar tui nov CSS ante de conservar.",
'userjsyoucanpreview'              => "'''Punta:''' Usa li buton \"{{int:showpreview}}\" por provar tui nov JavaScript ante de conservar.",
'usercsspreview'                   => "'''Memora que vu es solmen vident un prevision de tui CSS de usator.'''
'''It ne have esset conservat ancor!'''",
'userjspreview'                    => "'''Memora que vu es solmen provant/monstrant tui JavaScript de usator.'''
'''It ne ha esset conservat ancor!'''",
'userinvalidcssjstitle'            => "'''Advertiment:''' Ne vi pelle \"\$1\".
Memora que hábitu .css e págines .js usa un titul plu bass, e.g. {{ns:user}}:Foo/vector.css quam oposit por {{ns:user}}:Foo/Vector.css.",
'updated'                          => '(Modernisat)',
'previewnote'                      => "'''Memora se que ti es solmen un prevision.'''
Tui changes ancor ne ha esset conservat!",
'token_suffix_mismatch'            => "'''Tui redaction ha esset rejectet pro que tui software de navigation multilat li carácteres de punctuation in li simbol de redaction.'''
Li redaction ha esset rejectet por impedir corruption del textu de págine.
Ti quelcvez ocurre quande vu es usant un service de autorisation anonim mal executet in Internet.",
'editing'                          => 'Redactent $1',
'editingsection'                   => 'Redactent $1 (division)',
'editingcomment'                   => 'Redactent $1 (nov division)',
'yourtext'                         => 'Tui textu',
'storedversion'                    => 'Version acumulat',
'yourdiff'                         => 'Diferenties',
'copyrightwarning'                 => "Omni contributiones a {{SITENAME}} es considerat quam publicat sub li termines del $2 (ples vider $1 por plu mult detallies). Si vu ne vole que vor ovres mey esser modificat e distribuet secun arbitrie, ples ne inviar les. Adplu, ples contribuer solmen vor propri ovres o ovres ex un fonte quel es líber de jures. '''NE UTILISA OVRES SUB JURE EDITORIAL SIN DEFINITIV AUTORISATION!'''",
'titleprotectedwarning'            => "'''Advertiment: Ti págine ha esset serrat por que [[Special:ListGroupRights|jures specific]] es necessitat por crear it.''' 
Li ultim intrada in li historico es sub li condition infra por referentie:",
'templatesused'                    => '{{PLURAL:$1|Avise|Avises}} usat in ti págine:',
'templatesusedpreview'             => '{{PLURAL:$1|Avise|Avises}} usat in ti prevision:',
'templatesusedsection'             => '{{PLURAL:$1|Avise|Avises}} usat in ti division:',
'template-protected'               => '(gardat)',
'template-semiprotected'           => '(medie-gardat)',
'hiddencategories'                 => 'Ti págine es un membre de {{PLURAL:$1|1 categorie ocultat|$1 categories ocultat}}:',
'permissionserrorstext-withaction' => 'Vu ne have permission por $2, por li sequent {{PLURAL:$1|motive|motives}}:',
'recreate-moveddeleted-warn'       => "'''Advertiment: Vu es recreant un págine que esset anteriorimen deletet.'''

Vu deve considerar ca it es convenent por continuar redactant ti págine.
Li deletion e diarium de movement por li págine es sub li condition ci por convenience:",
'moveddeleted-notice'              => 'Ti págine ha esset deletet.
Li deletion e diarium de movement por li págine es sub li condition in infra por referentie.',

# Parser/template warnings
'post-expand-template-inclusion-warning'  => "'''Advertiment:''' Avise que include dimension es anc grand.
Alcun avises va ne esser includet.",
'post-expand-template-inclusion-category' => 'Págines u avise que include dimension es excedet',
'post-expand-template-argument-warning'   => "'''Advertiment:''' Ti págine contene in un minim argumente de avise quel ha un anc mensura de expansion grand.
Tis argumentes have esset omisset.",
'post-expand-template-argument-category'  => 'Págines contenent argumentes de avise omisset',

# "Undo" feature
'undo-success' => 'Li redaction posse es desfat. Pleser controla li comparation sub verificar que ti es quo vu cari acter, e tande conservar li changes infra por terminar e desfar li redaction.',
'undo-failure' => 'Li redaction ne posse esser desfat payand conflicte de redactiones.',
'undo-norev'   => 'Li redaction ne posse esser defat pro que it ne existe o esset deletet.',
'undo-summary' => 'Desfat revision $1 per [[Special:Contributions/$2|$2]] ([[User talk:$2|discussion]])',

# History pages
'viewpagelogs'           => 'Vider diariumes por ti págine',
'currentrev'             => 'Ultim revision',
'currentrev-asof'        => 'Ultim revision quam de $1',
'revisionasof'           => 'Revision de $1',
'revision-info'          => 'Revision de $1 e il ha fabricat de $2',
'previousrevision'       => '← Revision antiqui',
'nextrevision'           => 'Revision sequent →',
'currentrevisionlink'    => 'Ultim revision',
'cur'                    => 'hod',
'next'                   => 'prox',
'last'                   => 'ant',
'page_first'             => 'prim',
'page_last'              => 'ultim',
'histlegend'             => "Diferenties de selection: marca li buxes de radio de li revisiones por comparar e batte \"intrar\" o li buton in li funde.<br />
Legende: '''({{int:cur}})''' = diferenties che ultim revision, '''({{int:last}})''' = diferenties que antecede li revision, '''{{int:minoreditletter}}''' = redaction minori.",
'history-fieldset-title' => 'Historie de navigation',
'history-show-deleted'   => 'Deletet solmen',
'histfirst'              => 'Plu antiqui',
'histlast'               => 'Plu recent',

# Revision feed
'history-feed-item-nocomment' => '$1 in $2',

# Revision deletion
'rev-delundel'           => 'monstrar/ocultar',
'revdelete-logentry'     => 'alterat visibilitá de revision de "[[$1]]"',
'revdel-restore'         => 'change visibilitá',
'revdel-restore-deleted' => 'revisiones deletet',
'revdel-restore-visible' => 'revisiones visibil',
'revdelete-content'      => 'contenete',
'revdelete-hid'          => 'ocu $1',
'revdelete-log-message'  => '$1 por $2 {{PLURAL:$2|revision|revisiones}}',

# Suppression log
'suppressionlog'     => 'Diarium de supression',
'suppressionlogtext' => 'Infra es un liste de deletiones e bloces involuent contenete ocultant de administratores. 
Vider li [[Special:IPBlockList|liste de bloc de IP]] por li liste de bloces e bannimentes operational currentmen.',

# Merge log
'revertmerge' => 'Desfar fusion',

# Diffs
'history-title'           => 'Revision del historie de "$1"',
'difference'              => '(Diferentie inter revisiones)',
'lineno'                  => 'Linea $1:',
'compareselectedversions' => 'Comparar revisiones selectet',
'editundo'                => 'anullar',
'diff-multi'              => '({{PLURAL:$1|Un revision intermediari|$1 revisiones intermediari}} per {{PLURAL:$2|un usator|$2 usatores}} ne monstrat)',

# Search results
'searchresults'                    => 'Serchar resultates',
'searchresults-title'              => 'Serchar resultates por "$1"',
'toomanymatches'                   => 'Anc mult concurses esset retornat, pleser prova un question diferent',
'titlematches'                     => 'Resultates in li titules de págines',
'textmatches'                      => 'Resultates in textu de págines',
'prevn'                            => '{{PLURAL:$1|$1}} anteriori',
'nextn'                            => 'proxim {{PLURAL:$1|$1}}',
'prevn-title'                      => 'Anteriori $1 {{PLURAL:$1|resultate|resultates}}',
'nextn-title'                      => 'Proxim $1 {{PLURAL:$1|resultate|resultates}}',
'shown-title'                      => 'Monstrar $1 {{PLURAL:$1|resultate|resultates}} per págine',
'viewprevnext'                     => 'Vider ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-exists'                => "'''Vi un págine nominat \"[[:\$1]]\" in ti wiki.'''",
'searchmenu-new'                   => "'''Crear li págine \"[[:\$1]]\" in ti wiki!'''",
'searchhelp-url'                   => 'Help:Contenete',
'searchprofile-articles'           => 'Págines de contenete',
'searchprofile-project'            => 'Auxilie e Págines de projecte',
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Omnicós',
'searchprofile-advanced'           => 'Avansat',
'searchprofile-articles-tooltip'   => 'Serchar in $1',
'searchprofile-project-tooltip'    => 'Serchar in $1',
'searchprofile-images-tooltip'     => 'Serchar por files',
'searchprofile-everything-tooltip' => 'Serchar omni li contenete (includent págines de discussion)',
'searchprofile-advanced-tooltip'   => 'Serchar in spacies de nómine de hábitu',
'search-result-size'               => '$1 ({{PLURAL:$2|1 parol|$2 paroles}})',
'search-result-category-size'      => '{{PLURAL:$1|1 membre|$1 membres}} ({{PLURAL:$2|1 subcategory|$2 subcategories}}, {{PLURAL:$3|1 file|$3 files}})',
'search-redirect'                  => '(redirectet de $1)',
'search-section'                   => '(division $1)',
'search-suggest'                   => 'Vu intentet: $1',
'searchrelated'                    => 'relatet',
'searchall'                        => 'Omni',
'showingresultsheader'             => "{{PLURAL:$5|Resultate '''$1''' de '''$3'''|Resultates '''$1 - $2''' de '''$3'''}} por '''$4'''",
'search-nonefound'                 => 'Ne esset constatat resultates por li question.',
'powersearch'                      => 'Serchar avansat',

# Quickbar
'qbsettings' => 'Personalisation del barre de utensiles',

# Preferences page
'preferences'               => 'Preferenties',
'mypreferences'             => 'Mi preferenties',
'prefsnologin'              => 'Vu ne ha intrat',
'changepassword'            => 'Modificar passa-parol',
'skin-preview'              => 'Prevision',
'saveprefs'                 => 'Conservar',
'searchresultshead'         => 'Serchar',
'stub-threshold'            => "Catenunes por págines de contenete va aparir <a href=''#'' class=''stub''>de ti forme</a> si ili have minu de (bytes):",
'timezonelegend'            => 'Zone de témpor:',
'timezoneuseserverdefault'  => 'Usar wiki de contumacie ($1)',
'timezoneuseoffset'         => 'Altri (specificar compensation)',
'timezoneoffset'            => 'Compensation¹:',
'timezoneregion-africa'     => 'Africa',
'timezoneregion-america'    => 'America',
'timezoneregion-antarctica' => 'Antarctica',
'timezoneregion-asia'       => 'Asia',
'timezoneregion-atlantic'   => 'Ocean Atlantic',
'timezoneregion-australia'  => 'Australia',
'timezoneregion-europe'     => 'Europe',
'timezoneregion-indian'     => 'Ocean Indian',
'timezoneregion-pacific'    => 'Ocean Pacific',
'youremail'                 => 'E-mail:',
'username'                  => 'Nómine de usator:',
'uid'                       => 'Identification de usator:',
'yourrealname'              => 'Nómine real:',
'yourlanguage'              => 'Lingue:',
'yourvariant'               => 'Variante:',
'yournick'                  => 'Nov signature:',
'yourgender'                => 'Génere:',
'prefs-help-email'          => 'Adresse de e-mail es optional, ma es necessitá por recomensar parol-clave, deve vu obliviar tui parol-clave.',
'prefs-help-email-others'   => 'Vu posse anc optar por permisser altri contacter vu per e-mail complet che un catenun in tui págine de usator o págine de discussion.
Tui adresse de e-mail ne es revelat quande altri usatores contacter vu.',

# User rights
'userrights'                   => 'Gerement de jures de usator',
'userrights-lookup-user'       => 'Gerer gruppes de usator',
'userrights-user-editname'     => 'Intrar un nómine de usator:',
'userrights-editusergroup'     => 'Redacter gruppes de usator',
'userrights-groupsmember'      => 'Membre de:',
'userrights-groupsmember-auto' => 'Membre implicit de:',
'userrights-groups-help'       => 'Vu posse alterar li gruppes de ti usator in:
* Un buxe controlat significa que li usator es in ti gruppe.
* Un buxe descontrolat significa que li usator ne es in ti gruppe.
* Un * indica que vu ne posse remover li gruppe un vez que vu have adjuntet it, o inversi.',
'userrights-reason'            => 'Motive:',
'userrights-no-interwiki'      => 'Vu ne have permission por redacter jures de usator in altri wikis.',
'userrights-nodatabase'        => 'Funde de data $1 ne existe o ne es local.',
'userrights-nologin'           => 'Vu deve [[Special:UserLogin|registrar]] che un conto de administrator por atribuer jures de usator.',
'userrights-notallowed'        => 'Tui conto ne have permission por atribuer jures de usator.',
'userrights-changeable-col'    => 'Gruppes que vu posse changear',
'userrights-unchangeable-col'  => 'Gruppes que vu ne posse changear',

# Groups
'group-user'  => 'Usatores',
'group-sysop' => 'Administratores',

'group-user-member' => 'Usator',

'grouppage-user'       => '{{ns:project}}:Usatores',
'grouppage-sysop'      => '{{ns:project}}:Administratores',
'grouppage-bureaucrat' => '{{ns:project}}:Burócrates',
'grouppage-suppress'   => '{{ns:project}}:Vigilatores',

# User rights log
'rightslog'  => 'Diarium de jures de usator',
'rightsnone' => '(null)',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'redacter ti págine',

# Recent changes
'nchanges'                        => '$1 {{PLURAL:$1|change|changes}}',
'recentchanges'                   => 'Nov changes',
'recentchanges-legend'            => 'Optiones de nov changes',
'recentchangestext'               => 'Seque sur ti-ci págine li ultim modificationes al wiki.',
'recentchanges-feed-description'  => 'Monstra li max nov changes to li wiki in ti feed.',
'recentchanges-label-newpage'     => 'Ti redaction creat un nov págine',
'recentchanges-label-minor'       => 'Ti es un redaction minori',
'recentchanges-label-bot'         => 'Ti redaction esset efectuat per un machine',
'recentchanges-label-unpatrolled' => 'Ti redaction ne have ancor esset protectet',
'rcnote'                          => "In infra {{PLURAL:$1|es '''1''' change|es li ultim '''$1''' changes}} in li ultim {{PLURAL:$2|die|'''$2''' dies}}, quam de $5, $4.",
'rcnotefrom'                      => "In infra es li changes desde '''$2''' (ad-supra por '''$1''' monstrat).",
'rclistfrom'                      => 'Monstrar li nov modificationes desde $1.',
'rcshowhideminor'                 => '$1 redactiones minori',
'rcshowhidebots'                  => '$1 machines',
'rcshowhideliu'                   => '$1 usatores registrat',
'rcshowhideanons'                 => '$1 usatores anonim',
'rcshowhidepatr'                  => '$1 redactiones vigilat',
'rcshowhidemine'                  => '$1 mi redactiones',
'rclinks'                         => 'Monstrar li $1 ultim modificationes fat durante li $2 ultim dies<br />$3.',
'diff'                            => 'dif',
'hist'                            => 'hist',
'hide'                            => 'Ocultar',
'show'                            => 'Monstrar',
'minoreditletter'                 => 'm',
'newpageletter'                   => 'N',
'boteditletter'                   => 'b',
'rc-enhanced-expand'              => 'Monstar detallies (exige JavaScript)',
'rc-enhanced-hide'                => 'Ocultar detallies',

# Recent changes linked
'recentchangeslinked'          => 'Changes referet',
'recentchangeslinked-feed'     => 'Relatet modificationes',
'recentchangeslinked-toolbox'  => 'Changes referet',
'recentchangeslinked-title'    => 'Changes relatet por "$1"',
'recentchangeslinked-noresult' => 'Nequant change in págines ligat durante li periode anunciat.',
'recentchangeslinked-summary'  => "Ti es un liste de changes fat recentmen por págines ligat de un págine specificat (o por membres de un categorie specificat).
Págines in [[Special:Watchlist|tui liste de págines vigilat]] es '''nigri'''.",
'recentchangeslinked-page'     => 'Nómine de págine:',
'recentchangeslinked-to'       => 'Monstrar changes por págines ligat por li págine disposit in vice',

# Upload
'upload'               => 'Cargar file',
'uploadbtn'            => 'Cargar file',
'uploadnologin'        => 'Vu ne ha intrat',
'uploadnologintext'    => 'Vu deve esser [[Special:UserLogin|registrat]] por cargar files.',
'uploaderror'          => 'Errore de cargament',
'uploadtext'           => "Usa li forme infra por cargar files.
Por vider o serchar files cargat anteriorimen ear por li [[Special:FileList|liste de files cargat]], (re)cargamentes es anc registrat in li [[Special:Log/upload|diarium de cargament]], deletiones in li [[Special:Log/delete|diarium de deletion]].

Por includer un file in un págine, usa un catenun in un de sequent formes:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>''' por usar li version complet de li file
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|alt text]]</nowiki></tt>''' por usar un rendition larg de 200 pixel in un buxe in li márgine levul che 'alt text' quam descrition
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>''' por ligar inmediatmen por li file sin monstrar li file",
'upload-permitted'     => 'Tipes de file permisset: $1.',
'upload-preferred'     => 'Tipes de file preferet: $1.',
'upload-prohibited'    => 'Tipes de file prohibit: $1.',
'uploadlog'            => 'Diarium de cargament',
'uploadlogpage'        => 'Diarium de cargament',
'uploadlogpagetext'    => 'Infra es un liste de cargamentes de file max recent.
Vider li [[Special:NewFiles|galerie de nov files]] por posser vider it.',
'filedesc'             => 'Descrition',
'verification-error'   => 'Ti file ne passat per li verification de file.',
'unknown-error'        => 'Un errore desconosset ocurret.',
'tmp-create-error'     => 'Ne posset crear file temporari.',
'tmp-write-error'      => 'Errore por redacter file temporari.',
'uploadwarning'        => 'Advertiment de cargament',
'uploadwarning-text'   => 'Pleser modificar li descrition de file infra e prova denov.',
'savefile'             => 'Conservar file',
'uploadedimage'        => 'cargat "[[$1]]"',
'uploadfromurl-queued' => 'Tui cargament ha esset fat.',
'uploadscripted'       => 'Ti file contene HTML o code scrite que posse esser interpretet erroremen per un navigator web.',
'uploadvirus'          => 'Li file contene un virus!
Detallies: $1',
'upload-maxfilesize'   => 'Mesura maxim de file: $1',
'upload-description'   => 'Descrition de file',
'upload-options'       => 'Optiones de cargament',
'watchthisupload'      => 'Vigilar ti file',
'upload-failure-subj'  => 'Problema de cargament',
'upload-failure-msg'   => 'Ta esset un problema che tui cargament:
$1',

'upload-proto-error'      => 'Protocol incorect',
'upload-proto-error-text' => 'Cargament distant exige comense che <code>http://</code> o <code>ftp://</code>.',
'upload-file-error'       => 'Errore intern',
'upload-file-error-text'  => 'Un errore intern ocurret quande atentant crear un file temporari in li servitor.
Pleser parla che un [[Special:ListUsers/sysop|administrator]].',
'upload-misc-error'       => 'Errore desconosset de cargament',
'upload-misc-error-text'  => 'Un errore desconosset ocurret durante li cargament.
Pleser verificar que li URL es valid e accessibil e prova denov.
Si li problema persister, parla che un [[Special:ListUsers/sysop|administrator]].',
'upload-http-error'       => 'Un errore HTTP ocurret: $1',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Ne esset possibil ear in li URL',
'upload-curl-error6-text'  => 'Li URL preferet ne esset visitat.
Pleser vide denov que li URL es corect e si li loc es disponibil.',
'upload-curl-error28'      => 'For de témpor por cargament de files',
'upload-curl-error28-text' => 'Ti loc demorat mult témpor por responder.
Pleser vider se li loc es disponibil, atende un témpor e prova denov.
Vu posse provar in un témpor minu activ.',

'license'        => 'Autorisant:',
'license-header' => 'Autorisant',

# Special:ListFiles
'imgfile'   => 'archive',
'listfiles' => 'Archives',

# File description page
'file-anchor-link'          => 'Figura',
'filehist'                  => 'Historic de figura',
'filehist-help'             => 'Clacca in un date/témpor por vider li file quam it aparit in ti témpor.',
'filehist-revert'           => 'reverter',
'filehist-current'          => 'current',
'filehist-datetime'         => 'Date/Témpor',
'filehist-thumb'            => 'Miniatura',
'filehist-thumbtext'        => 'Miniatura por version quam de $1',
'filehist-user'             => 'Usator',
'filehist-dimensions'       => 'Dimensiones',
'filehist-filesize'         => 'Mesura de file',
'filehist-comment'          => 'Comentarie',
'imagelinks'                => 'Usu del file',
'linkstoimage'              => 'Li sequent {{PLURAL:$1|catenun de págine|$1 catenunes de págines}} por ti file:',
'nolinkstoimage'            => 'Hay nequant págine que liga por ti file.',
'sharedupload-desc-here'    => 'Ti file es de $1 e posse esser usat per altri projectes.
Li descrition es in li [$2 págine de descrition del file] ta e es monstrat in infra.',
'uploadnewversion-linktext' => 'Cargar un nov version de ti file',

# File deletion
'filedelete-submit' => 'Deleter',

# MIME search
'mimesearch' => 'Serchar MIME',

# Unwatched pages
'unwatchedpages' => 'Págines desvigilat',

# List redirects
'listredirects' => 'Liste de redirecteriones',

# Unused templates
'unusedtemplates'     => 'Avises sin use',
'unusedtemplatestext' => 'Ti págine lista omni li págines in li spacie de nómine {{ns:template}} quel ne es includet in altri págine. Memorar de controlar por altri catenunes por li avises ante de deleter les.',
'unusedtemplateswlh'  => 'altri catenunes',

# Random page
'randompage' => 'Págine sporadic',

# Statistics
'statistics'                   => 'Statistica',
'statistics-header-pages'      => 'Statistica del págine',
'statistics-header-edits'      => 'Redacter statistica',
'statistics-header-views'      => 'Vider statistica',
'statistics-header-users'      => 'Statistica de usator',
'statistics-header-hooks'      => 'Altri statistica',
'statistics-articles'          => 'Págines de contenete',
'statistics-pages'             => 'Págines',
'statistics-pages-desc'        => 'Omni li págines in li wiki, includent págines de discussion, redirectments, etc.',
'statistics-files'             => 'Files cargat',
'statistics-edits'             => 'Redactiones del págine desde que {{SITENAME}} esset etablisset',
'statistics-edits-average'     => 'Valore medial de redactiones per págine',
'statistics-views-total'       => 'Total de vistas',
'statistics-views-peredit'     => 'Vider por redaction',
'statistics-users'             => '[[Special:ListUsers|Usatores]] registrat',
'statistics-users-active'      => 'Usatores activ',
'statistics-users-active-desc' => 'Usatores qui have efectuat un action in li ultim {{PLURAL:$1|die|dies}}',
'statistics-mostpopular'       => 'Págines max visitat',

'disambiguationspage' => 'Template:disambig',

'brokenredirects-edit'   => 'redacter',
'brokenredirects-delete' => 'deleter',

'withoutinterwiki'         => 'Págines sin catenunes de lingue',
'withoutinterwiki-summary' => 'Li proxim págines ne liga por altri versiones de lingue.',
'withoutinterwiki-legend'  => 'Prefixe',
'withoutinterwiki-submit'  => 'Monstrar',

'fewestrevisions' => 'Págines che max poc revisiones',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories'             => '$1 {{PLURAL:$1|categorie|categories}}',
'nlinks'                  => '$1 {{PLURAL:$1|catenun|catenunes}}',
'nmembers'                => '$1 {{PLURAL:$1|usator|usatores}}',
'specialpage-empty'       => 'Hay nequant resultates por ti raporte.',
'lonelypages'             => 'Orfani págines',
'uncategorizedpages'      => 'Págines íncategorizet',
'uncategorizedcategories' => 'Categories íncategorizet',
'uncategorizedimages'     => 'Figuras íncategorizet',
'uncategorizedtemplates'  => 'Avises íncategorizet',
'unusedcategories'        => 'Categories sin use',
'unusedimages'            => 'Figuras sin use',
'wantedcategories'        => 'Categories carit',
'wantedpages'             => 'Págines carit',
'wantedpages-badtitle'    => 'Titul ínvalid in serie de resultate: $1',
'wantedfiles'             => 'Files carit',
'wantedtemplates'         => 'Avises carit',
'prefixindex'             => 'Omni li págines che prefixe',
'shortpages'              => 'Págines curt',
'longpages'               => 'Págines long',
'deadendpages'            => 'Págines sin exeada',
'listusers'               => 'Liste de usatores',
'usereditcount'           => '$1 {{PLURAL:$1|redaction|redactiones}}',
'usercreated'             => 'Creat in $1 in $2',
'newpages'                => 'Nov págines',
'ancientpages'            => 'Li max old págines',
'move'                    => 'Mover',
'movethispage'            => 'Mover ti págine',
'unusedimagestext'        => 'Li proxim files existe ma ne esser fixat in alquel págine. Pleser note que altri págines de Internet posse ligar por un file che un URL direct, e talmen posse silentment esser listat ci malgré essant in usu activ.',
'unusedcategoriestext'    => 'Li proxim págines de categorie existe, benque ne existe altri págine o categorie que far usu de les.',
'pager-newer-n'           => '{{PLURAL:$1|nov 1|nov $1}}',
'pager-older-n'           => '{{PLURAL:$1|antiqui 1|antiqui $1}}',
'suppress'                => 'Perspective comun',

# Book sources
'booksources'               => 'Librari fontes',
'booksources-search-legend' => 'Serchar por fontes de libre',
'booksources-go'            => 'Ear',

# Special:Log
'specialloguserlabel'  => 'Executor:',
'speciallogtitlelabel' => 'Cible (titul o usator):',
'log'                  => 'Diariumes',
'all-logs-page'        => 'Omni li diariumes public',

# Special:AllPages
'allpages'       => 'Omni li págines',
'alphaindexline' => '$1 til $2',
'nextpage'       => 'Proxim págine ($1)',
'prevpage'       => 'Ultim págine ($1)',
'allarticles'    => 'Omni li articules',
'allpagessubmit' => 'Ear',

# Special:Categories
'categories'                    => 'Categories',
'special-categories-sort-count' => 'Sorte per contar',
'special-categories-sort-abc'   => 'Sorte alfabeticmen',

# Special:LinkSearch
'linksearch'      => 'Catenunes extern',
'linksearch-ns'   => 'Spacie de nómine:',
'linksearch-line' => '$1 es ligat de $2',

# Special:Log/newusers
'newuserlogpage'          => 'Diarium de creation de usator',
'newuserlog-create-entry' => 'Nov conto de usator',

# Special:ListGroupRights
'listgrouprights-members' => '(liste de membres)',

# E-mail user
'emailuser'            => 'Parlar che ti usator',
'usermailererror'      => 'Objecte de postage retornat errore:',
'usermaildisabled'     => 'E-mail de usator desvalidat',
'usermaildisabledtext' => 'Vu ne posse inviar e-mail por altri usatores in ti wiki',

# User Messenger
'usermessage-summary'  => 'Abandonant missage del sistema',
'usermessage-editor'   => 'Missagero del sistema',
'usermessage-template' => 'MediaWiki:UserMessage',

# Watchlist
'watchlist'            => 'Liste de págines vigilat',
'mywatchlist'          => 'Mi liste de págines vigilat',
'watchlistfor2'        => 'Por $1 $2',
'watchlistanontext'    => 'Pleser $1 por vider o redacter articules in tui liste de págines vigilat.',
'watchnologin'         => 'Vu ne ha intrat',
'watchnologintext'     => 'Vu deve esser [[Special:UserLogin|registrat]] por redacter tui liste de págines vigilat.',
'addedwatchtext'       => "Li págine ''[[$1]]'' ha esset adjuntet a vor [[Special:Watchlist|liste de sequet págines]]. Li proxim modificationes de ti ci págine e del associat págine de discussion va esser listat ci, e li págine va aperir '''aspessat''' in li [[Special:RecentChanges|liste de recent modificationes]] por esser trovat plu facilmen. Por supresser ti ci págine ex vor liste, ples claccar sur « Ne plu sequer » in li cadre de navigation.",
'watch'                => 'Vigilar',
'watchthispage'        => 'Vigilar ti págine',
'unwatch'              => 'Desvigilar',
'watchnochange'        => 'Nequant de tui tui partes vigilat esset redactet in li periode de témpor monstrat.',
'watchlist-details'    => '{{PLURAL:$1|$1 págine|$1 págines}} in tui liste de págines vigilat, sin págines de discussion.',
'wlheader-enotif'      => '* Li notification de e-mail es permisset.',
'wlheader-showupdated' => "* Págines quel hat esset mutat desde tui ultim visitation es monstrat in '''nigri'''",
'watchmethod-recent'   => 'controlant nov redactiones por págines vigilat',
'watchmethod-list'     => 'controlant págines vigilat por nov redactiones',
'watchlistcontains'    => 'Tui liste de págines vigilat contene $1 {{PLURAL:$1|págine|págines}}.',
'wlnote'               => "Infra {{PLURAL:$1|es li ultim change|es li ultim '''$1''' changes}} in li ultim {{PLURAL:$2|hor|'''$2''' hores}}.",
'wlshowlast'           => 'Monstra ultim $1 hores $2 dies $3',
'watchlist-options'    => 'Optiones de liste de págines vigilat',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Vigilant...',
'unwatching' => 'Desvigilant...',

'created' => 'creat',

# Delete
'deletepage'            => 'Deleter págine',
'delete-legend'         => 'Deleter',
'actioncomplete'        => 'Processu complet',
'actionfailed'          => 'Processu fallit',
'deletedarticle'        => 'deletet "[[$1]]"',
'suppressedarticle'     => "supresset ''[[$1]]''",
'dellogpage'            => 'Diarium de deletion',
'deletecomment'         => 'Motive:',
'deleteotherreason'     => 'Altri motive:',
'deletereasonotherlist' => 'Altri motive',

# Rollback
'rollbacklink' => 'desfar',

# Protect
'protectlogpage'      => 'Diarium de protection',
'protectedarticle'    => 'gardat "[[$1]]"',
'prot_1movedto2'      => '[[$1]] hat movet por [[$2]]',
'protectcomment'      => 'Motive:',
'protect-level-sysop' => 'Administratores solmen',
'protect-expiring'    => 'expira $1 (UTC)',
'restriction-type'    => 'Permission:',

# Restrictions (nouns)
'restriction-edit' => 'Redacter',
'restriction-move' => 'Mover',

# Undelete
'undelete'                     => 'Vider págines deletet',
'undeletepage'                 => 'Vider e restaurar págines deletet',
'undeletepagetitle'            => "'''Li proxim consiste de revisiones deletet de [[:$1|$1]]'''.",
'viewdeletedpage'              => 'Vider págines deletet',
'undeletepagetext'             => 'Li proxim {{PLURAL:$1|págine ha esset deletet ma es|$1 págines have esset esset deletet ma es}} ínmobil in li archive e posse esser restaurat. Li archive posse esser periodicomen demuddat.',
'undelete-fieldset-title'      => 'Restaurar revisiones',
'undeleteextrahelp'            => "Por restaurar li historie complet de págine, abandona omni li buxes de controle deselectet e clacca '''''{{int:undeletebtn}}'''''.
Por efectuar un restauration selectiv, controla li buxes secun por li revisiones por esser restaurat, e clacca '''''{{int:undeletebtn}}'''''.",
'undeleterevisions'            => '$1 {{PLURAL:$1|revision|revisiones}} conservat',
'undeletehistory'              => 'Si vu restaurar li págine, omni li revisiones va esser restaurat por li historie.
Si un nov págine che nómine identic ha esset creat desde li deletion, li revisiones restaurat va aparir in li historie precedent.',
'undeleterevdel'               => 'Restauration ne va esser efectuat si it va resultar in li págine superiori o revision de file essent partialmen deletet.
In tal casus, vu deve descontrolar o desocultar li ultim revision deletet.',
'undeletehistorynoadmin'       => 'Ti págine ha esset deletet.
Li motive por deletion es monstrat in li summarium infra, junt che detallies del usatores que hat redactet ti págine ante de deletion.
Li textu efectiv de tis revisiones deletet es solmen disponibil por administratores.',
'undelete-revision'            => 'Revision deletet de $1 (quam de $4, in $5) per $3:',
'undeleterevision-missing'     => 'Revision ínvalid o mancant.
Vu posse have un catenun mal, o li revision posse have esset restaurat o removet del archive.',
'undelete-nodiff'              => 'Ne esset instituet revision anteriori.',
'undeletebtn'                  => 'Restaurar',
'undeletelink'                 => 'vider/restaurar',
'undeleteviewlink'             => 'vider',
'undeletereset'                => 'Recomensar',
'undeleteinvert'               => 'Inverter selection',
'undeletecomment'              => 'Motive:',
'undeletedarticle'             => 'restaurat "[[$1]]"',
'undeletedrevisions'           => '{{PLURAL:$1|1 revision|$1 revisiones}} restaurat',
'undeletedrevisions-files'     => '{{PLURAL:$1|1 revision|$1 revisiones}} e {{PLURAL:$2|1 file|$2 files}} restaurat',
'undeletedfiles'               => '{{PLURAL:$1|1 file|$1 files}} restaurat',
'undeletedpage'                => "'''$1 ha esset restaurat'''

Consulta li [[Special:Log/delete|diarium de deletion]] por un registre de deletiones nov e restaurationes.",
'undelete-header'              => 'Vider [[Special:Log/delete|li diarium de deletion]] por págines deletet currentmen.',
'undelete-search-box'          => 'Serchar págines deletet',
'undelete-search-prefix'       => 'Monstrar págines comensant che:',
'undelete-search-submit'       => 'Serchar',
'undelete-no-results'          => 'Ne esset instituet págines egale in li archive de deletion.',
'undelete-filename-mismatch'   => 'Ne posse restaurar version de file $1 che hor e date: nómine de file misegala',
'undelete-bad-store-key'       => 'Ne posse restaurar version che hor e date de file $1: file esset existet ante de deletion.',
'undelete-cleanup-error'       => 'Error deletent archive sin usu de file "$1".',
'undelete-missing-filearchive' => 'Ne posse restaurar archive de file ID $1 pro que it ne es in li funde de data.
It posse have ja esset restaurat.',
'undelete-error-short'         => 'Errore in li restauration de file: $1',
'undelete-error-long'          => 'Errores esset incontrat durante li restauration de file:

$1',
'undelete-show-file-confirm'   => 'Vu comprende se que vu cari vider li revision deletet del file "<nowiki>$1</nowiki>" de $2 in $3?',
'undelete-show-file-submit'    => 'Yes',

# Namespace form on various pages
'namespace'      => 'Spacie de nómine:',
'invert'         => 'Inverter selection',
'blanknamespace' => '(Principal)',

# Contributions
'contributions'       => 'Contributiones de usator',
'contributions-title' => 'Contributiones de usator por $1',
'mycontris'           => 'Mi contributiones',
'contribsub2'         => 'Por $1 ($2)',
'uctop'               => '(prim)',
'month'               => 'De mensu (e anterioris):',
'year'                => 'De annu (e anterioris):',

'sp-contributions-newbies'     => 'Monstar contributiones de nov contos solmen',
'sp-contributions-newbies-sub' => 'Por nov contos',
'sp-contributions-blocklog'    => 'diarium de bloc',
'sp-contributions-uploads'     => 'cargamentes de file',
'sp-contributions-logs'        => 'diariumes',
'sp-contributions-talk'        => 'Discussion',
'sp-contributions-search'      => 'Serchar por contributiones',
'sp-contributions-username'    => 'Adresse de IP o nómine de usator:',
'sp-contributions-toponly'     => 'Solmen monstrar redactiones que es in max recent revisiones',
'sp-contributions-submit'      => 'Serchar',

# What links here
'whatlinkshere'            => 'Quo catenunes ci',
'whatlinkshere-title'      => 'Págines quo liga por "$1"',
'whatlinkshere-page'       => 'Págine:',
'linkshere'                => "Li sequent págines liga por '''[[:$1]]''':",
'nolinkshere'              => "Nequant págine liga por '''[[:$1]]'''.",
'isredirect'               => 'págine de redirecterion',
'istemplate'               => 'inclusion',
'isimage'                  => 'catenun de figura',
'whatlinkshere-prev'       => '{{PLURAL:$1|anterior|$1 anterioris}}',
'whatlinkshere-next'       => '{{PLURAL:$1|proxim|proxim $1}}',
'whatlinkshere-links'      => '← catenunes',
'whatlinkshere-hideredirs' => '$1 redirectiones',
'whatlinkshere-hidetrans'  => '$1 transclusiones',
'whatlinkshere-hidelinks'  => '$1 catenunes',
'whatlinkshere-hideimages' => '$1 catenunes de figura',
'whatlinkshere-filters'    => 'Filtres',

# Block/unblock
'blockip'                  => 'Blocar usator',
'ipbreason'                => 'Motive:',
'ipboptions'               => '2 hores:2 hours,1 die:1 day,3 dies:3 days,1 semane:1 week,2 semanes:2 weeks,1 mensu:1 month,3 mensues:3 months,6 mensues:6 months,1 annu:1 year,inprecise:infinite',
'ipbotheroption'           => 'altri',
'ipblocklist'              => 'Usatores blocat',
'ipblocklist-submit'       => 'Serchar',
'infiniteblock'            => 'infinit',
'blocklink'                => 'blocar',
'unblocklink'              => 'desblocar',
'change-blocklink'         => 'change bloc',
'contribslink'             => 'contribs',
'blocklogpage'             => 'Diarium de bloc',
'blocklogentry'            => '"[[$1]]" hat blocat che un témpor de expiration de $2 $3',
'block-log-flags-nocreate' => 'creation de conto debilisat',

# Move page
'movearticle'     => 'Mover págine:',
'movenologin'     => 'Vu ne ha intrat',
'newtitle'        => 'Por nov titul:',
'move-watch'      => 'Vigilar ti págine',
'movepagebtn'     => 'Mover págine',
'talkexists'      => "'''Li págine itself esset movet successosimen, ma li págine de discussion ne posset esser movet pro que un ja existe in li nov titul.
Pleser fuse les manualmen.'''",
'movedto'         => 'movet por',
'1movedto2'       => '[[$1]] hat movet por [[$2]]',
'1movedto2_redir' => 'movet [[$1]] por [[$2]] redirectionment ultra',
'movelogpage'     => 'Diarium de movementiones',
'movereason'      => 'Motive:',
'revertmove'      => 'reverter',

# Export
'export' => 'Exportar págines',

# Namespace 8 related
'allmessages'        => 'Liste del missages del sistema',
'allmessagesname'    => 'Nómine',
'allmessagesdefault' => 'Textu de missage de contumacie',

# Thumbnails
'thumbnail-more'           => 'Expander',
'thumbnail_error'          => 'Errore creant miniatura: $1',
'thumbnail_invalid_params' => 'Parametres de miniatura ínvalid',
'thumbnail_dest_directory' => 'Ne posse crear adressarium de adresse',
'thumbnail_image-type'     => 'Tip de figura ne suportat',
'thumbnail_gd-library'     => 'Configuration de biblioteca GD íncomplet: mancant function $1',
'thumbnail_image-missing'  => 'File sembla por esser mancant: $1',

# Special:Import
'xml-error-string' => '$1 in linea $2, col $3 (byte $4): $5',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Tui págine de usator',
'tooltip-pt-anonuserpage'         => 'Li págine de usator por li adresse de IP vu es redactent quam',
'tooltip-pt-mytalk'               => 'Tui págine de discussion',
'tooltip-pt-anontalk'             => 'Discussion pri redactiones de adresses de IP',
'tooltip-pt-preferences'          => 'Tui preferenties',
'tooltip-pt-watchlist'            => 'Li liste de págines quo vu controla li changes',
'tooltip-pt-mycontris'            => 'Liste de tui contributiones',
'tooltip-pt-login'                => 'Vu es incorageat por crear un conto; támen, it ne esser mandatorio',
'tooltip-pt-anonlogin'            => 'Vu es incorageat por crear un conto; támen, it ne esser mandatorio',
'tooltip-pt-logout'               => 'Surtida',
'tooltip-ca-talk'                 => 'Discussion pri li contenete de págine',
'tooltip-ca-edit'                 => 'Vu posse redacter ti págine. Pleser usar li buton "Monstrar prevision" ante de conservar',
'tooltip-ca-addsection'           => 'Comensar un nov division',
'tooltip-ca-viewsource'           => 'Ti págine es gardat. Vu posse vider li contenete',
'tooltip-ca-history'              => 'Revisiones passat de ti págine',
'tooltip-ca-protect'              => 'Gardar ti págine',
'tooltip-ca-unprotect'            => 'Desgardar ti págine',
'tooltip-ca-delete'               => 'Deleter ti págine',
'tooltip-ca-undelete'             => 'Restaurar li redactiones executet pro ti págine ante de it esser deletet',
'tooltip-ca-move'                 => 'Mover ti págine',
'tooltip-ca-watch'                => 'Adjunter ti págine pro tui liste de págines vigilat',
'tooltip-ca-unwatch'              => 'Desvigilar ti págine de tui liste de págines vigilat',
'tooltip-search'                  => 'Serchar {{SITENAME}}',
'tooltip-search-go'               => 'Ear por un págine che ti nómine exact, si it exister',
'tooltip-search-fulltext'         => 'Serchar págines che ti textu',
'tooltip-p-logo'                  => 'Visita li págine principal',
'tooltip-n-mainpage'              => 'Visita li págine principal',
'tooltip-n-mainpage-description'  => 'Visita li págine principal',
'tooltip-n-portal'                => 'Pri li projecte, quo vu posse executer, u constatar coses',
'tooltip-n-currentevents'         => 'Constata funde de information sur eventus current',
'tooltip-n-recentchanges'         => 'Li liste de nov changes vice wiki',
'tooltip-n-randompage'            => 'Cargar un págine sporadic',
'tooltip-n-help'                  => 'Li loco por constatar auxilie',
'tooltip-t-whatlinkshere'         => 'Liste de omni págines que liga quel por ci',
'tooltip-t-recentchangeslinked'   => 'Nov changes in págines ligat in ti págine',
'tooltip-feed-rss'                => 'Feed RSS por ti págine',
'tooltip-feed-atom'               => 'Feed atom por ti págine',
'tooltip-t-contributions'         => 'Vider li liste de contributiones de ti usator',
'tooltip-t-emailuser'             => 'Inviar un e-mail por ti usator',
'tooltip-t-upload'                => 'Cargar files',
'tooltip-t-specialpages'          => 'Liste de omni págines special',
'tooltip-t-print'                 => 'Version por impression de ti págine',
'tooltip-t-permalink'             => 'Catenun permanent por ti revision de págine',
'tooltip-ca-nstab-main'           => 'Vider li págine de contenete',
'tooltip-ca-nstab-user'           => 'Vider li págine de usator',
'tooltip-ca-nstab-media'          => 'Vider li págine de media',
'tooltip-ca-nstab-special'        => 'Ti es un págine special, vu ne posse redacter it',
'tooltip-ca-nstab-project'        => 'Vider li págine de projecte',
'tooltip-ca-nstab-image'          => 'Vider li págine de figura',
'tooltip-ca-nstab-mediawiki'      => 'Vider li missage de sistema',
'tooltip-ca-nstab-template'       => 'Vider li avise',
'tooltip-ca-nstab-help'           => 'Vider li págine de auxilie',
'tooltip-ca-nstab-category'       => 'Vider li págine de categorie',
'tooltip-minoredit'               => 'Marcar to ci quam un redaction minori',
'tooltip-save'                    => 'Conservar tui changes',
'tooltip-preview'                 => 'Monstrar tui changes, pleser usar ante de conservar!',
'tooltip-diff'                    => 'Monstrar quel changes vu executet in li textu',
'tooltip-compareselectedversions' => 'Vider li differenties inter li du revisiones selectet de ti págine',
'tooltip-watch'                   => 'Adjunter ti págine por tui liste de págines vigilat',
'tooltip-recreate'                => 'Recrear li págine por plan anteriori de delete',
'tooltip-upload'                  => 'Comensar cargament de file',
'tooltip-rollback'                => '"Rollback" reverte redaction(es) de ti págine executet per li ultim contributor in un claccar',
'tooltip-undo'                    => '"Undo" reverte ti redaction e inaugura li forme de redaction in modo de prevision. It concede adjuntent un rason in li summarium.',
'tooltip-preferences-save'        => 'Conservar preferenties',
'tooltip-summary'                 => 'Intrar un summarium curt',

# Stylesheets
'vector.css' => '/* CSS colocat ci va afectar usatores de pelle Vector */',

# Scripts
'vector.js' => '/* Alquel JavaScript ci va esser cargat por usatores que usa li pelle Vector */',

# Patrol log
'patrol-log-line' => 'marcat $1 de $2 protectet $3',
'patrol-log-diff' => 'revision $1',

# Browsing diffs
'previousdiff' => '← Redaction anteriori',
'nextdiff'     => 'Proxim redaction →',

# Media information
'thumbsize'       => 'Mesura de miniatura:',
'widthheightpage' => '$1×$2, $3 {{PLURAL:$3|págine|págines}}',
'file-info-size'  => '$1 × $2 pixeles, dimension de file: $3, tip MIME: $4',
'file-nohires'    => '<small>Nequant resolution max alt disponibil.</small>',
'svg-long-desc'   => '(file SVG, nominalmen $1 × $2 pixeles, mesura de file: $3)',
'show-big-image'  => 'Resolution complet',

# Special:NewFiles
'newimages' => 'Galerie de nov images',
'ilsubmit'  => 'Serchar',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'video-dims' => '$1, $2×$3',

# Bad image list
'bad_image_list' => 'Li formate es quam seque:

Solmen listar detallies (lineas comensant che *) es considerat.
Li prim catenun in un linea deve esser un catenun por un file maliciosi.
Alqual catenunes subsequent in li linea identic es considerat por esser exceptiones, i.e. págines u li file posse ocurrer in linea identic.',

# Metadata
'metadata'        => 'Metadata',
'metadata-help'   => 'Ti file contene information additional, probabilmen adjuntet de li cámera digitale o scandetor usat por crear o digitalizar it. Si li file ha esset redactet de tui statu original, alcun detallies posse ne reflecter completmen li file redactet.',
'metadata-fields' => 'Campes metadata de figura listat in ti missage va esser includet in págine de figura monstra quande li tabelle metadata es crulat.
Altri va esser ocultat per contumacie.
* fabrication
* modelle
* origine de figura
* témpor de exposition
* númere
* percentages de velocitá
* longore focal
* artist
* jure editorial
* descrition de figura
* latitúdine
* longitúdine
* altitudine',

# External editor support
'edit-externally'      => 'Redacter ti file usant un aplication extern',
'edit-externally-help' => '(Vider li [//www.mediawiki.org/wiki/Manual:External_editors instructiones de installation] por plu information)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'omni',
'namespacesall' => 'omni',
'monthsall'     => 'omni',

# Trackbacks
'trackbackbox'      => 'Tracies de dorse por ti págine:<br /> $1',
'trackbackexcerpt'  => '; $4 $5: [$2 $1]: <nowiki>$3</nowiki>',
'trackbackremove'   => '([$1 Deleter])',
'trackbacklink'     => 'Tracie de dorse',
'trackbackdeleteok' => 'Li tracie de dorse esset deletet che successe',

# Multipage image navigation
'imgmultigo' => 'Ear!',

# Table pager
'table_pager_next'         => 'Proxim págine',
'table_pager_prev'         => 'Págine anteriori',
'table_pager_first'        => 'Prim págine',
'table_pager_last'         => 'Ultim págine',
'table_pager_limit'        => 'Monstra $1 detallies por págine',
'table_pager_limit_label'  => 'Detallies por págine',
'table_pager_limit_submit' => 'Ear',
'table_pager_empty'        => 'Nequant resultates',

# Watchlist editor
'watchlistedit-numitems'       => 'Tui liste de págines vigilat contene {{PLURAL:$1|1 titul|$1 titules}}, excludent págines de discussion.',
'watchlistedit-noitems'        => 'Tui liste de págines vigilat ne contene titules.',
'watchlistedit-normal-title'   => 'Redacter liste de págines vigilat',
'watchlistedit-normal-legend'  => 'Remove titules del liste de págines vigilat',
'watchlistedit-normal-explain' => 'Titules in tui liste de págines vigilat es monstrat infra.
Por remover un titul, controla li buxe proxim por it, e clacca "{{int:Watchlistedit-normal-submit}}".
Vu posse anc [[Special:EditWatchlist/raw|redacter li liste vulnerosi]].',
'watchlistedit-normal-submit'  => 'Remover titules',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 titul esset|$1 titules esset}} removet de tui liste de págines vigilat:',
'watchlistedit-raw-title'      => 'Redacter liste de págines vigilat vulnerosi',
'watchlistedit-raw-legend'     => 'Redacter liste de págines vigilat vulnerosi',
'watchlistedit-raw-explain'    => 'Titules in tui liste de págines vigilat es monstrat in infra, e posse esser redactet solmen adjuntent por e removent de li liste; un titul per linea.
Quande terminat, clacca "{{int:Watchlistedit-raw-submit}}".
Vu posse anc [[Special:EditWatchlist|usar li redactor uniform]].',
'watchlistedit-raw-titles'     => 'Titules:',
'watchlistedit-raw-submit'     => 'Modernisar liste de págines vigilat',
'watchlistedit-raw-done'       => 'Tui liste de págines vigilat ha esset modernisat.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 titul esset|$1 titules esset}} adjuntet:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 titul esset|$1 titules esset}} removet:',

# Watchlist editing tools
'watchlisttools-view' => 'Vider changes aplicabil',
'watchlisttools-edit' => 'Vider e redacter liste de págines vigilat',
'watchlisttools-raw'  => 'Redacter liste de págines vigilat vulnerosi',

# Signatures
'timezone-utc' => 'UTC',

# Core parser functions
'duplicate-defaultsort' => '\'\'\'Advertiment:\'\'\' Clave de specie contumacie "$2" substitue temporanmen clave de specie contumacie "$1".',

# Special:Version
'version'                       => 'Version',
'version-extensions'            => 'Extensiones installat',
'version-specialpages'          => 'Págines special',
'version-parserhooks'           => 'Croces analisatores',
'version-variables'             => 'Variabiles',
'version-other'                 => 'Altri',
'version-mediahandlers'         => 'Manuettes de media',
'version-hooks'                 => 'Croces',
'version-extension-functions'   => 'Functiones de extension',
'version-parser-extensiontags'  => 'Puntales de extension analisatores',
'version-parser-function-hooks' => 'Croces de functiones analisatores',
'version-hook-name'             => 'Nómine de croc',
'version-hook-subscribedby'     => 'Subscrit per',
'version-version'               => '(Version $1)',
'version-svn-revision'          => '(r$2)',
'version-license'               => 'Licentie',
'version-software'              => 'Software installat',
'version-software-product'      => 'Producte',
'version-software-version'      => 'Version',

# Special:FilePath
'filepath-page' => 'Figura:',

# Special:SpecialPages
'specialpages'                   => 'Págines special',
'specialpages-group-maintenance' => 'Raportes de conservation',
'specialpages-group-other'       => 'Altri págines special',
'specialpages-group-login'       => 'Intrar / crear conto',
'specialpages-group-changes'     => 'Nov changes e diariumes',
'specialpages-group-media'       => 'Raportes de media e cargamentes de files',
'specialpages-group-users'       => 'Usatores e jures',
'specialpages-group-highuse'     => 'Págines de alt usu',
'specialpages-group-pages'       => 'Listes de págines',
'specialpages-group-pagetools'   => 'Utensiles de págine',
'specialpages-group-wiki'        => 'Data wiki e utensiles',
'specialpages-group-redirects'   => 'Redirectionant págines special',
'specialpages-group-spam'        => 'Utensiles de spam',

# External image whitelist
'external_image_whitelist' => '#Abandonar ti linea exactmen quam it es<pre>
#Colocar fragmentes de expression regulari (precismen li parte que ea inter li //) in infra
#Tis va esser egalat che li URLes de figuras extern (hotlinked)
#Tis que egala va esser monstrat quam figuras, altrimen solmen un catenun por li figura va esser monstrat
#Lineas comensant che # es tractat quam comentaries
#Ti es casu-ínsensitiv

#Colocar omni fragmentes regulari súper ti linea. Abandonar ti linea exactmen quam it es</pre>',

# Special:Tags
'tags'                    => 'Puntales de change valid',
'tag-filter'              => 'Filtre de [[Special:Tags|puntale]]:',
'tag-filter-submit'       => 'Filtre',
'tags-title'              => 'Puntales',
'tags-intro'              => 'Ti págine lista li puntales que li software posse marcar un redaction che, e lor signification.',
'tags-tag'                => 'Nómine de puntale',
'tags-display-header'     => 'Aspecte in listes de change',
'tags-description-header' => 'Descrition complet de signification',
'tags-hitcount-header'    => 'Changes nómiat',
'tags-edit'               => 'redacter',
'tags-hitcount'           => '$1 {{PLURAL:$1|change|changes}}',

);

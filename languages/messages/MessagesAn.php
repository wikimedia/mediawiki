<?php
/** Aragonese (Aragonés)
 *
 * @addtogroup Language
 *
 * @author SPQRobin
 * @author Juanpabl
 * @author G - ג
 */

$fallback = 'es';

$namespaceNames = array(
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => 'Espezial',
	NS_MAIN           => '',
	NS_TALK           => 'Descusión',
	NS_USER           => 'Usuario',
	NS_USER_TALK      => 'Descusión_usuario',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => 'Descusión_$1',
	NS_IMAGE          => 'Imachen',
	NS_IMAGE_TALK     => 'Descusión_imachen',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'Descusión_MediaWiki',
	NS_TEMPLATE       => 'Plantilla',
	NS_TEMPLATE_TALK  => 'Descusión_plantilla',
	NS_HELP           => 'Aduya',
	NS_HELP_TALK      => 'Descusión_aduya',
	NS_CATEGORY       => 'Categoría',
	NS_CATEGORY_TALK  => 'Descusión_categoría',
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Subrayar os binclos:',
'tog-highlightbroken'         => 'Formatiar os binclos trencaus <a href="" class="new"> d\'ista traza </a> (y si no, asinas <a href="" class="internal">?</a>).',
'tog-justify'                 => 'Achustar parrafos',
'tog-hideminor'               => 'Amagar edizions menors en a pachina de "zaguers cambeos"',
'tog-extendwatchlist'         => 'Enamplar a lista de seguimiento ta mostrar toz os cambeos afeutatos.',
'tog-usenewrc'                => 'Presentazión amillorada de "zaguers cambeos" (cal JavaScript)',
'tog-numberheadings'          => 'Numerar automaticament os encabezaus',
'tog-showtoolbar'             => "Mostrar a barra d'ainas d'edizión (cal JavaScript)",
'tog-editondblclick'          => 'Autibar edizión de pachinas fendo-ie doble click (cal JavaScript)',
'tog-editsection'             => 'Autibar a edizión por seczions usando binclos [editar]',
'tog-editsectiononrightclick' => "Autibar a edizión de seczions con o botón dreito d'o ratón <br /> en os titols de seczions (cal JavaScript)",
'tog-showtoc'                 => 'Mostrar o endize de contenius (ta pachinas con más de 3 encabezaus)',
'tog-rememberpassword'        => 'Remerar a parabra de paso entre sesions',
'tog-editwidth'               => "O cuatrón d'edizión tien l'amplaria masima",
'tog-watchcreations'          => 'Bexilar as pachinas que creye',
'tog-watchdefault'            => 'Bexilar as pachinas que edite',
'tog-watchmoves'              => 'Bexilar as pachinas que treslade',
'tog-watchdeletion'           => 'Bexilar as pachinas que borre',
'tog-minordefault'            => 'Marcar por defeuto todas as edizions como menors',
'tog-previewontop'            => "Mostrar l'ambiesta prebia antes d'o cuatrón d'edizión (en cuenta de dimpués)",
'tog-previewonfirst'          => "Mostrar l'ambiesta prebia de l'articlo en a primera edizión",
'tog-nocache'                 => "Desautibar a ''caché'' de pachinas",
'tog-enotifwatchlistpages'    => 'Nimbiar-me un correu cuan bi aiga cambeos en una pachina bexilada por yo',
'tog-enotifusertalkpages'     => 'Nimbiar-me un correu cuan cambee a mía pachina de descusión',
'tog-enotifminoredits'        => 'Nimbiar-me un correu tamién cuan bi aiga edizions menors de pachinas',

# Dates
'monday'    => 'lunes',
'wednesday' => 'miércols',
'mon'       => 'Lun',
'april'     => 'abr',
'august'    => 'agosto',
'october'   => 'otubre',
'november'  => 'nobiembre',
'december'  => 'abiento',
'apr'       => 'abr',
'aug'       => 'ago',
'oct'       => 'otu',
'nov'       => 'nob',
'dec'       => 'abi',

# Bits of text used by many pages
'pagecategories' => '{{PLURAL:$1|Categoría|Categorías}}',
'subcategories'  => 'Subcategorías',

'article'        => 'Articlo',
'newwindow'      => "(s'ubre en una nueba finestra)",
'qbpageoptions'  => 'Ista pachina',
'qbspecialpages' => 'Pachinas espezials',
'mypage'         => 'A mía pachina',
'mytalk'         => 'A mía descusión',
'navigation'     => 'Nabego',

'returnto'          => 'Tornar ta $1.',
'tagline'           => 'De {{SITENAME}}',
'search'            => 'Uscar',
'searchbutton'      => 'Uscar',
'searcharticle'     => 'Ir-ie',
'history_short'     => 'Istorial',
'printableversion'  => 'Bersión ta imprentar',
'permalink'         => 'Enrastre remanén',
'edit'              => 'Editar',
'delete'            => 'Borrar',
'protect'           => 'Protexer',
'protectthispage'   => 'Protexer ista pachina',
'unprotect'         => 'esprotexer',
'unprotectthispage' => 'Esprotexer ista pachina',
'newpage'           => 'Pachina nueba',
'talk'              => 'Descusión',
'toolbox'           => 'Ainas',
'otherlanguages'    => 'Atras luengas',
'redirectedfrom'    => '(Reendrezau dende $1)',
'protectedpage'     => 'Pachina protechida',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'copyright'       => 'O conteniu ye disponible baxo a $1.',
'currentevents'   => 'Autualidat',
'mainpage'        => 'Portalada',
'portal'          => 'A tabierna',
'portal-url'      => '{{ns:project}}:Tabierna',
'privacy'         => 'Politica de pribazidat',
'privacypage'     => '{{ns:project}}:Politica de pribazidat',
'sitesupport'     => 'Donazions',
'sitesupport-url' => '{{ns:project}}:Donazions',

'youhavenewmessages'      => 'Tiens $1 ($2).',
'newmessageslink'         => 'nuebos mensaches',
'youhavenewmessagesmulti' => 'Tiens nuebos mensaches en $1',
'toc'                     => 'Contenitos',
'showtoc'                 => 'mostrar',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Articlo',
'nstab-user'      => "Pachina d'usuario",
'nstab-special'   => 'Espezial',
'nstab-project'   => "Pachina d'o proyeuto",
'nstab-image'     => 'Imachen',
'nstab-mediawiki' => 'Mensache',
'nstab-template'  => 'Plantilla',
'nstab-help'      => 'Aduya',
'nstab-category'  => 'Categoría',

# General errors
'viewsource'    => 'Beyer codigo fuen',
'viewsourcefor' => 'ta $1',

# Login and logout pages
'yourname'          => "Nombre d'usuario:",
'yourpassword'      => 'A tuya parabra de paso:',
'yourpasswordagain' => 'Torna á escribir a tuya parabra de paso:',
'userlogin'         => 'Creyar una cuenta u dentrar-ie',
'userlogout'        => 'Salir',
'nologin'           => 'No tiens una cuenta? $1.',
'nologinlink'       => 'Creyar una nueba cuenta',
'createaccount'     => 'Creyar una nueba cuenta',
'username'          => "Nombre d'usuario:",
'yourrealname'      => 'O tuyo nombre reyal:',
'yourlanguage'      => 'Lenguache:',
'yournick'          => 'A tuya embotada (ta siñar):',
'prefs-help-email'  => "Correu-e (ozional): Premite á atros usuarios contautar con tu por meyo de a tuya pachina d'usuario u a tuya pachina de descusión sin de aber menester de rebelar a tuya identidá.",
'noname'            => "No has introduziu un nombre d'usuario correuto.",

# Edit page toolbar
'nowiki_tip' => 'Innorar o formato wiki',

# Edit pages
'summary'                  => 'Resumen',
'minoredit'                => 'He feito una edizión menor',
'watchthis'                => 'Bexilar ista pachina',
'savearticle'              => 'Alzar pachina',
'preview'                  => 'Bisualizazión prebia',
'showpreview'              => 'Bisualizazión prebia',
'showdiff'                 => 'Mostrar cambeos',
'newarticle'               => '(Nuebo)',
'newarticletext'           => "Has siguito un binclo á una pachina que encara no esiste.
Ta creyar a pachina, prenzipia á escribir en a caxa d'abaxo
(se beiga l'[[{{MediaWiki:helppage}}|aduya]] ta más informazión).
Si bi has plegau por error, puncha o botón d'o tuyo nabegador ta ir entazaga.",
'semiprotectedpagewarning' => "'''Nota:''' Ista página ha estato protexita ta que nomás usuarios rechistratos puedan editar-la.",
'templatesused'            => 'Plantillas emplegatas en ista pachina:',
'template-protected'       => '(protexito)',
'template-semiprotected'   => '(semiprotexito)',

# "Undo" feature
'undo-summary' => 'Esfeita la edizión $1 de [[Special:Contributions/$2|$2]] ([[User talk:$2|desc.]])',

# History pages
'revhistory'   => 'Istorial de rebisions',
'nohistory'    => "Ista pachina no tiene un istorial d'edizions.",
'nextrevision' => 'Rebisión siguién →',
'next'         => 'siguién',

# Search results
'searchresults' => "Resultaus d'a rechira",
'prevn'         => 'anteriors $1',
'nextn'         => 'siguiens $1',
'viewprevnext'  => 'Beyer ($1) ($2) ($3)',
'powersearch'   => 'Uscar',

# Preferences page
'preferences'       => 'Preferenzias',
'mypreferences'     => 'As mías preferenzias',
'prefsnologintext'  => "Has d'estar [[{{ns:special}}:Userlogin|rechistrau]] y aber enzetau una sesión ta cambear as preferenzias d'usuario.",
'prefs-rc'          => 'Zaguers cambeos',
'prefs-watchlist'   => 'Lista de seguimiento',
'saveprefs'         => 'Alzar preferenzias',
'retypenew'         => 'Torna á escribir a tuya nueba parabra de paso:',
'searchresultshead' => "Confegurar resultaus d'a rechira",

# Recent changes
'recentchanges'   => 'Zaguers cambeos',
'rclistfrom'      => 'Mostrar nuebos cambeos dende $1',
'rcshowhideminor' => '$1 edizions menors',
'rcshowhideliu'   => '$1 usuarios rechistraus',
'rcshowhideanons' => '$1 usuarios anonimos',
'rcshowhidemine'  => '$1 as mías edizions',
'show'            => 'Mostrar',

# Recent changes linked
'recentchangeslinked' => 'Cambeos en pachinas relazionadas',

# Upload
'upload'            => 'Cargar archibo',
'uploadnologintext' => "Has d'estar [[{{ns:special}}:Userlogin|rechistrau]] ta cargar archibos.",

# Image list
'ilsubmit'       => 'Uscar',
'nolinkstoimage' => 'Denguna pachina tiene un binclo con ista imachen.',

# Unwatched pages
'unwatchedpages' => 'Pachinas sin bexilar',

# Unused templates
'unusedtemplates' => 'Plantillas sin de uso',

# Random redirect
'randomredirect' => 'Ir-ie á una adreza cualsiquiera',

# Statistics
'statistics' => 'Estadisticas',
'sitestats'  => "Estadisticas d'a {{SITENAME}}",
'userstats'  => "Estadisticas d'usuario",

# Miscellaneous special pages
'uncategorizedpages'      => 'Pachinas sin categorizar',
'uncategorizedcategories' => 'Categorías sin categorizar',
'uncategorizedimages'     => 'Imachens sin categorizar',
'uncategorizedtemplates'  => 'Plantillas sin categorizar',
'unusedcategories'        => 'Categorías sin emplegar',
'unusedimages'            => 'Imachens sin uso',
'wantedcategories'        => 'Categorías requiestas',
'wantedpages'             => 'Pachinas requiestas',
'mostlinked'              => 'Pachinas más enlazadas',
'mostlinkedcategories'    => 'Categorías más enlazadas',
'mostcategories'          => 'Pachinas con más categorías',
'mostimages'              => 'Imachens más emplegatas',
'mostrevisions'           => 'Pachinas con más edizions',
'allpages'                => 'Todas as pachinas',
'prefixindex'             => 'Pachinas por prefixo',
'randompage'              => "Una pachina á l'azar",
'shortpages'              => 'Pachinas más curtas',
'longpages'               => 'Pachinas más largas',
'deadendpages'            => 'Pachinas sin salida',
'specialpages'            => 'Pachinas espezials',
'restrictedpheading'      => 'Pachinas espezials restrinxitas',
'rclsub'                  => '(enta pachinas enlazadas dende "$1")',
'newpages'                => 'Pachinas nuebas',
'ancientpages'            => 'Pachinas más biellas',
'move'                    => 'Tresladar',

'alphaindexline' => '$1 á $2',
'version'        => 'Bersión',

# Special:Log
'specialloguserlabel' => 'Usuario:',
'log'                 => 'Rechistros',
'all-logs-page'       => 'Toz os rechistros',
'log-search-submit'   => 'Ir-ie',

# Special:Allpages
'nextpage'       => 'Siguién pachina ($1)',
'allarticles'    => 'Toz os articlos',
'allinnamespace' => 'Todas as pachinas (espazio $1)',
'allpagessubmit' => 'Amostrar',

# Watchlist
'watchlist'       => 'Lista de seguimiento',
'mywatchlist'     => 'A mía lista de seguimiento',
'nowatchlist'     => 'No tiens denguna pachina en a lista de seguimiento.',
'addedwatch'      => 'Adibiu ta la tuya lista de seguimiento',
'watch'           => 'Bexilar',
'watchthispage'   => 'Bexilar ista pachina',
'unwatch'         => 'Dixar de bexilar',
'unwatchthispage' => 'Dixar de bexilar',

# Delete/protect/revert
'historywarning' => 'Para cuenta: A pachina que bas a borrar tiene un istorial de cambeos:',
'actioncomplete' => 'Aizión rematada',
'protectcomment' => 'Razón ta protexer:',

# Undelete
'undeletepagetext' => "As pachinas siguiens han siu borradas, pero encara son en l'archibo y podría estar restauradas. El archibo se borra periodicamén.",

# Namespace form on various pages
'namespace' => 'Espazio de nombres:',

# Contributions
'contributions' => "Contrebuzions de l'usuario",
'mycontris'     => 'As mías contrebuzions',

'sp-contributions-blocklog' => 'Rechistro de bloqueyos',

# What links here
'whatlinkshere' => 'Pachinas que enlazan con ista',

# Block/unblock
'contribslink' => 'contrebuzions',
'blocklogpage' => 'Rechistro de bloqueyos',

# Move page
'movearticle'     => 'Tresladar pachina:',
'movepagebtn'     => 'Tresladar pachina',
'movepage-moved'  => '<big>\'\'\'"$1" ha estato tresladato á "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'1movedto2'       => '[[$1]] tresladada á [[$2]]',
'1movedto2_redir' => '[[$1]] tresladada á [[$2]] sobre una reendrezera',
'movelogpage'     => 'Rechistro de treslatos',

# Namespace 8 related
'allmessages'        => "Toz os mensaches d'o sistema",
'allmessagesname'    => 'Nombre',
'allmessagesdefault' => 'Testo por defeuto',
'allmessagescurrent' => 'Testo autual',
'allmessagestext'    => 'Ista ye una lista de toz os mensaches disposables en o espazio de nombres MediaWiki.',

# Attribution
'and'    => 'y',
'others' => 'atros',

# Special:Newimages
'newimages' => 'Galería de nuebas imachens',

# 'all' in various places, this might be different for inflected languages
'namespacesall' => 'todo',

);

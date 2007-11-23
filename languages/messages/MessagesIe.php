<?php
/** Interlingue (Interlingue)
 *
 * @addtogroup Language
 *
 * @author Jmb
 * @author G - ג
 * @author SPQRobin
 */

$messages = array(
# Dates
'sunday'    => 'soledí',
'monday'    => 'lunedí',
'tuesday'   => 'mardí',
'wednesday' => 'mercurdí',
'thursday'  => 'jovedí',
'friday'    => 'venerdí',
'saturday'  => 'saturdí',
'january'   => 'januar',
'february'  => 'februar',
'march'     => 'marte',
'april'     => 'april',
'may_long'  => 'may',
'june'      => 'junio',
'july'      => 'julí',
'august'    => 'august',
'september' => 'septembre',
'october'   => 'octobre',
'november'  => 'novembre',
'december'  => 'decembre',
'jan'       => 'jan',
'feb'       => 'feb',
'mar'       => 'mar',
'apr'       => 'apr',
'may'       => 'may',
'jun'       => 'jun',
'jul'       => 'jul',
'aug'       => 'aug',
'sep'       => 'sep',
'oct'       => 'oct',
'nov'       => 'nov',
'dec'       => 'dec',

# Bits of text used by many pages
'pagecategories'  => '{{PLURAL:$1|Categorie|Categories}}',
'category_header' => 'Articules in categorie "$1"',

'mainpagetext' => "<big>'''Software del wiki installat con successe.'''</big>",

'about'          => 'Apropó',
'article'        => 'Articul',
'newwindow'      => '(aperte un nov fenestre)',
'cancel'         => 'Anullar',
'qbfind'         => 'Serchar',
'qbedit'         => 'Modificar',
'qbpageoptions'  => 'Págine de optiones',
'qbpageinfo'     => 'Págine de information',
'qbspecialpages' => 'Special págines',
'moredotdotdot'  => 'Plu mult...',
'mytalk'         => 'Mi discussion',
'anontalk'       => 'Discussion por ti ci IP',

'returnto'         => 'Retornar a $1.',
'help'             => 'Auxilie',
'search'           => 'Serchar',
'go'               => 'Ear',
'history'          => 'Historie',
'history_short'    => 'Historie',
'printableversion' => 'Printabil version',
'edit'             => 'Modificar',
'editthispage'     => 'Modificar ti págine',
'delete'           => 'Deleter',
'deletethispage'   => 'Deleter ti págine',
'undelete_short'   => 'Restaurar {{PLURAL:$1|1 modification|$1 modificationes}}',
'protect'          => 'Protecter',
'unprotect'        => 'Deprotecter',
'talkpage'         => 'Discusser ti págine',
'specialpage'      => 'Special Págine',
'personaltools'    => 'Utensiles personal',
'postcomment'      => 'Impostar un comenta',
'articlepage'      => 'Vider li articul',
'toolbox'          => 'Buxe de utensiles',
'userpage'         => 'Vider págine del usator',
'imagepage'        => 'Vider págine del image',
'viewtalkpage'     => 'Vider li discussion',
'redirectedfrom'   => '(Redirectet de $1)',
'viewcount'        => 'Ti págine ha esset consultat {{PLURAL:$1|$1 vezes|$1 vezes}}.',
'protectedpage'    => 'Un protectet págine',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'      => 'Apropó de {{SITENAME}}',
'aboutpage'      => 'Project:Apropó',
'bugreports'     => 'Raportes de malfunctiones',
'bugreportspage' => 'Project:Raportes de malfunctiones',
'copyright'      => 'Contenete disponibil sub $1.',
'disclaimers'    => 'Advertimentes',
'edithelp'       => 'Auxilie',
'edithelppage'   => 'Help:Qualmen modificar un págine',
'helppage'       => 'Help:Auxilie',
'mainpage'       => 'Principal págine',
'portal'         => 'Págine del comunité',
'portal-url'     => 'Project:Págine del comunité',
'sitesupport'    => 'Donationes',

'editsection' => 'modificar',
'toc'         => 'Tabelle de contenetes',
'hidetoc'     => 'celar',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Articul',
'nstab-user'      => 'Usator',
'nstab-mediawiki' => 'Missage',
'nstab-template'  => 'Modelle',
'nstab-help'      => 'Auxilie',
'nstab-category'  => 'Categorie',

# General errors
'error' => 'Erra',

# Login and logout pages
'logouttitle'                => 'Fine de session',
'logouttext'                 => 'Vu ha terminat vor session.
Vu posse continuar usar {{SITENAME}} anonimimen, o vu posse aperter un session denov quam li sam usator o quam un diferent usator.',
'loginpagetitle'             => 'Registrar se/Intrar',
'yourname'                   => 'Vor nómine usatori:',
'yourpassword'               => 'Vor passa-parol:',
'yourpasswordagain'          => 'Tippa denov vor passa-parol',
'remembermypassword'         => 'Memorar mi passa-parol (per cookie)',
'loginproblem'               => '<b>Hay un problema pri vor intrada.</b><br />Pena far it denov!',
'login'                      => 'Aperter session',
'loginprompt'                => 'Cookies deve esser permisset por intrar in {{SITENAME}}.',
'userlogin'                  => 'Crear un conto o intrar',
'logout'                     => 'Surtir',
'userlogout'                 => 'Surtir',
'notloggedin'                => 'Vu ne ha intrat',
'createaccount'              => 'Crear un nov conto',
'badretype'                  => 'Li passa-paroles queles vu tippat ne es identic.',
'youremail'                  => 'Vor ret-adresse:',
'loginerror'                 => 'Erra in initiation del session',
'nocookieslogin'             => '{{SITENAME}} utilisa cookies por far intrar usatores. Vu nu ne permisse cookies. Ples permisser les e provar denov.',
'loginsuccesstitle'          => 'Apertion de session successosi',
'loginsuccess'               => 'Vu ha apertet vor session in {{SITENAME}} quam "$1".',
'wrongpassword'              => 'Li passa-parol quel vu scrit es íncorect. Prova denov.',
'mailmypassword'             => 'Invia me un nov passa-parol per electronic post',
'acct_creation_throttle_hit' => 'Vu ja ha creat $1 contos. Vu ne posse crear pli mult quam to.',

# Edit pages
'summary'          => 'Resumate',
'minoredit'        => 'Modification minori',
'watchthis'        => 'Sequer ti articul',
'savearticle'      => 'Conservar págine',
'preview'          => 'Previder',
'showpreview'      => 'Previder págine',
'loginreqtitle'    => 'Apertion de session obligatori',
'accmailtitle'     => 'Li passa-parol es inviat.',
'accmailtext'      => "Li passa-parol por '$1' ha esset inviat a $2.",
'editing'          => 'modification de $1',
'editingsection'   => 'modification de $1 (section)',
'editingcomment'   => 'modification de $1 (comenta)',
'copyrightwarning' => 'Omni contributiones a {{SITENAME}} es considerat quam publicat sub li termines del GNU Free Documentation Licence, un licentie de líber documentation (ples vider $1 por plu mult detallies). Si vu ne vole que vor ovres mey esser modificat e distribuet secun arbitrie, ples ne inviar les. Adplu, ples contribuer solmen vor propri ovres o ovres ex un fonte quel es líber de jures. <b>NE UTILISA OVRES SUB JURE EDITORIAL SIN DEFINITIV AUTORISATION!</b>',

# Preferences page
'preferences'    => 'Preferenties',
'prefsnologin'   => 'Vu ne ha intrat',
'qbsettings'     => 'Personalisation del barre de utensiles',
'changepassword' => 'Modificar passa-parol',
'saveprefs'      => 'Conservar preferenties',
'oldpassword'    => 'Anteyan passa-parol:',
'newpassword'    => 'Nov passa-parol:',
'retypenew'      => 'Confirmar nov passa-parol',

# Recent changes
'recentchanges'     => 'Recent modificationes',
'recentchangestext' => 'Seque sur ti-ci págine li ultim modificationes al wiki.',
'rclistfrom'        => 'Monstrar li nov modificationes desde $1.',
'rclinks'           => 'Monstrar li $1 ultim modificationes fat durante li $2 ultim dies<br/ >$3.',
'hide'              => 'Celar',
'show'              => 'Monstrar',

# Recent changes linked
'recentchangeslinked' => 'Relatet modificationes',

# Upload
'upload'    => 'Cargar file',
'uploadbtn' => 'Cargar file',
'filedesc'  => 'Descrition',
'savefile'  => 'Conservar file',

# Image list
'imagelist' => 'Liste de images',

# Random page
'randompage' => 'Págine in hasarde',

# Statistics
'statistics' => 'Statisticas',

# Miscellaneous special pages
'ncategories'             => '$1 {{PLURAL:$1|categorie|categories}}',
'lonelypages'             => 'Orfani págines',
'uncategorizedpages'      => 'Págines sin categories',
'uncategorizedcategories' => 'Categories sin categories',
'unusedimages'            => 'Orfani images',
'wantedpages'             => 'Li max demandat págines',
'allpages'                => 'Omni págines',
'shortpages'              => 'Curt págines',
'longpages'               => 'Long págines',
'deadendpages'            => 'Págines sin exeada',
'listusers'               => 'Liste de usatores',
'specialpages'            => 'Special págines',
'spheading'               => 'Special págines por omni usatores',
'newpages'                => 'Nov págines',
'ancientpages'            => 'Li max old págines',
'move'                    => 'Mover',

# Book sources
'booksources' => 'Librari fontes',

# Watchlist
'watchlist'      => 'Liste de sequet págines',
'addedwatch'     => 'Adjuntet al liste',
'addedwatchtext' => "Li págine ''[[$1]]'' ha esset adjuntet a vor [[Special:Watchlist|liste de sequet págines]]. Li proxim modificationes de ti ci págine e del associat págine de discussion va esser listat ci, e li págine va aperir '''aspessat''' in li [[Special:Recentchanges|liste de recent modificationes]] por esser trovat plu facilmen. Por supresser ti ci págine ex vor liste, ples claccar sur « Ne plu sequer » in li cadre de navigation.",
'watch'          => 'Sequer',
'watchthispage'  => 'Sequer ti págine',

# Delete/protect/revert
'actioncomplete' => 'Supression efectuat',

# Contributions
'mycontris' => 'Mi contributiones',

# What links here
'whatlinkshere' => 'Ligat págines',

# Block/unblock
'ipblocklist' => 'Liste de blocat adresses e usatores',

# Move page
'movenologin' => 'Vu ne ha intrat',

# Export
'export' => 'Exportar págines',

# Namespace 8 related
'allmessages' => 'Liste del missages del sistema',

# Special:Newimages
'newimages' => 'Galerie de nov images',

);

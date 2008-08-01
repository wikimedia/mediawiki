<?php
/** Ladino (Ladino)
 *
 * @ingroup Language
 * @file
 *
 * @author ILVI
 * @author Taichi
 * @author לערי ריינהארט
 * @author SPQRobin
 * @author Runningfridgesrule
 */

$fallback = 'es';

$messages = array(
# User preference toggles
'tog-justify'              => 'Atakanár párrafos',
'tog-hideminor'            => 'Eskonder edisiones minores en «trokos resientes»',
'tog-showtoolbar'          => 'Amostrár la barra de edision',
'tog-rememberpassword'     => 'Akodrár mis informasiones sobre ésta komputadóra',
'tog-watchcreations'       => 'Vijilar las pajinas ke yo kree.',
'tog-watchdefault'         => 'Vijilar las pajinas ke yo modifike',
'tog-watchmoves'           => 'Vijilar las pajinas ke renombre',
'tog-watchdeletion'        => 'Vigilar las pajinas ke efase',
'tog-enotifwatchlistpages' => 'Embiame una pósta kuando aya trokos en una pajina vijilada',
'tog-enotifusertalkpages'  => 'Embiame una pósta kuando troka mi pajina de diskusion de uzuario',
'tog-shownumberswatching'  => 'Amostrár el número de uzuarios ke la vijilan',
'tog-showhiddencats'       => 'Amostrár kategorías eskondidas',

'underline-always' => 'Siempre',
'underline-never'  => 'Nunka',

# Dates
'sunday'        => 'alhád',
'monday'        => 'lúnes',
'tuesday'       => 'mártes',
'wednesday'     => 'miércoles',
'thursday'      => 'djuéves',
'friday'        => 'viérnes',
'saturday'      => 'shabat',
'sun'           => 'alh',
'mon'           => 'lún',
'tue'           => 'már',
'wed'           => 'mie',
'thu'           => 'dju',
'fri'           => 'vié',
'sat'           => 'sha',
'january'       => 'enéro',
'february'      => 'fevrero',
'march'         => 'márso',
'april'         => 'avril',
'may_long'      => 'máyo',
'june'          => 'júnio',
'july'          => 'djulio',
'august'        => 'agosto',
'september'     => 'septiembre',
'october'       => 'oktúbre',
'november'      => 'noviembre',
'december'      => 'disiémbre',
'january-gen'   => 'enéro',
'february-gen'  => 'fevrero',
'march-gen'     => 'márso',
'april-gen'     => 'avril',
'may-gen'       => 'máyo',
'june-gen'      => 'júnio',
'july-gen'      => 'djulio',
'august-gen'    => 'agosto',
'september-gen' => 'septiembre',
'october-gen'   => 'oktúbre',
'november-gen'  => 'noviembre',
'december-gen'  => 'disiémbre',
'jan'           => 'ené',
'feb'           => 'fev',
'mar'           => 'már',
'apr'           => 'avr',
'may'           => 'máy',
'jun'           => 'jún',
'jul'           => 'dju',
'aug'           => 'ago',
'sep'           => 'sep',
'oct'           => 'okt',
'nov'           => 'nov',
'dec'           => 'dis',

# Categories related messages
'category_header'          => 'Artikolos en la kategoría "$1"',
'subcategories'            => 'Subkategorías',
'category-media-header'    => 'Archivos multimedia en la kategoría "$1"',
'category-empty'           => "''La kategoría no kontiene aktualmente ningún artikolo o archivo multimedia''",
'hidden-category-category' => 'Kategorías eskondidas', # Name of the category where hidden categories will be listed
'listingcontinuesabbrev'   => 'kont.',

'about'          => 'Aserka de',
'article'        => 'Artikolo',
'newwindow'      => '(Se avre en una ventana mueva)',
'qbfind'         => 'Bushkar',
'qbedit'         => 'Editar',
'qbpageoptions'  => 'Opsiones de pajina',
'qbmyoptions'    => 'Mis opsiones',
'qbspecialpages' => 'Pajinas espesiales',
'moredotdotdot'  => 'Mas...',
'mypage'         => 'Mi pajina',
'mytalk'         => 'Mi diskusion',
'anontalk'       => 'Diskusion para esta IP',
'and'            => 'e',

'errorpagetitle'   => 'Yerro',
'help'             => 'Ayuda',
'search'           => 'Bushkar',
'searchbutton'     => 'Bushkar',
'go'               => 'Ir',
'searcharticle'    => 'Ir',
'history'          => 'Istorial',
'history_short'    => 'Istorial',
'info_short'       => 'Informasion',
'print'            => 'Imprimír',
'edit'             => 'Editar',
'create'           => 'Krear',
'editthispage'     => 'Editar ésta pajina',
'create-this-page' => 'Krear ésta pajina',
'delete'           => 'Efasar',
'deletethispage'   => 'Efasar ésta pajina',
'undelete_short'   => 'Restorar {{PLURAL:$1|una edision|$1 edisiones}}',
'protect'          => 'Abrigár',
'protectthispage'  => 'Abrigár ésta pajina',
'unprotect'        => 'Desabrigár',
'newpage'          => 'Pajina mueva',
'talkpage'         => 'Diskutir ésta pajina',
'talkpagelinktext' => 'Diskutir',
'postcomment'      => 'Meter un komentário',
'articlepage'      => 'Ver artikolo',
'talk'             => 'Diskusion',
'views'            => 'Vístas',
'userpage'         => 'Ver pajina de uzuario',
'viewhelppage'     => 'Ver pajina de ayúda',
'categorypage'     => 'Ver pajina de kategoría',
'viewtalkpage'     => 'Ver diskusion',
'otherlanguages'   => 'Otras línguas',
'redirectedfrom'   => '(Redirijido desde $1)',
'redirectpagesub'  => 'Pajina redirijida',
'protectedpage'    => 'Pajina abrigida',
'jumptosearch'     => 'bushkeda',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Aserka de {{SITENAME}}',
'aboutpage'            => 'Project:Aserka de',
'currentevents'        => 'Aktualidad',
'currentevents-url'    => 'Project:Aktualidad',
'edithelp'             => 'Ayuda de edision',
'edithelppage'         => 'Help:Komo se edita una pajina',
'faq'                  => 'FAQ',
'faqpage'              => 'Project:FAQ',
'helppage'             => 'Help:Ayuda',
'mainpage'             => 'Kacha',
'mainpage-description' => 'Kacha',
'policy-url'           => 'Project:Politikas',
'portal'               => 'Portal de la komunidád',
'privacy'              => 'Politika de proteksion de informasiones',
'privacypage'          => 'Project:Politika de proteksion de informasiones',

'badaccess' => 'Falta de permesos',

'ok'              => 'OK',
'newmessageslink' => 'mesajes muevos',
'editsection'     => 'editar',
'editold'         => 'editar',
'editsectionhint' => 'Editar seksion: $1',
'showtoc'         => 'amostrár',
'hidetoc'         => 'eskonder',
'thisisdeleted'   => 'Ver o restorar $1?',
'viewdeleted'     => 'Desea ver $1?',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Artikolo',
'nstab-user'      => "Pajina d'uzuario",
'nstab-special'   => 'Espesial',
'nstab-image'     => 'Imagen',
'nstab-mediawiki' => 'Mesaje',
'nstab-help'      => 'Ayuda',
'nstab-category'  => 'Kategoría',

# Main script and global functions
'nosuchspecialpage' => 'No egziste ésta pajina espesial',

# General errors
'error' => 'Yerro',

# Login and logout pages
'accountcreated'     => 'Kuenta kreada',
'accountcreatedtext' => 'La kuenta de uzuario para $1 ha sido kreada.',

# Edit pages
'showdiff' => 'Amostrar trokos',

# Preferences page
'preferences' => 'Preferensias',

# Recent changes
'recentchanges'   => 'Trokos resientes',
'rcshowhideminor' => '$1 edisiones minores',
'rcshowhideliu'   => '$1 usuarios rejistrados',
'rcshowhideanons' => '$1 usuarios anonimos',
'rcshowhidemine'  => '$1 mis edisiones',
'hist'            => 'ist',
'hide'            => 'Eskonder',
'show'            => 'Amostrar',

# Miscellaneous special pages
'ancientpages' => 'Artikolos mas viejos',
'move'         => 'Trasladar',

# Special:Allpages
'allpages'       => 'Todas las pajinas',
'alphaindexline' => '$1 a $2',
'allarticles'    => 'Todos los artikolos',
'allinnamespace' => 'Todas las pajinas (espasio $1)',
'allpagesnext'   => 'Siguiente',
'allpagessubmit' => 'Amostrar la lista',

# Special:Categories
'categories'                    => 'Kategorías',
'special-categories-sort-count' => 'ordenar por número',
'special-categories-sort-abc'   => 'ordenar alefbeticamente',

# Watchlist
'watch' => 'Vijilar',

# Delete/protect/revert
'actioncomplete' => 'Aksion kompleta',

# Move page
'1movedto2'       => '[[$1]] trasladado a [[$2]]',
'1movedto2_redir' => '[[$1]] trasladado a [[$2]] sovre una redireksion',

# Namespace 8 related
'allmessages'        => 'Mesajes del sistema',
'allmessagesname'    => 'Nombre',
'allmessagesdefault' => 'Teksto por defekto',
'allmessagescurrent' => 'Teksto aktual',

# Attribution
'anonymous' => 'Uzuario(s) anonimo(s) de {{SITENAME}}',

# EXIF tags
'exif-filesource'   => 'Manadéro de archivo',
'exif-gpstimestamp' => 'Tiémpo GPS (óra atómica)',
'exif-gpsdatestamp' => 'Dáta GPS',

'exif-meteringmode-255' => 'Otro',

'exif-lightsource-9'  => 'Bueno tiémpo',
'exif-lightsource-10' => 'Tiémpo nuvlozo',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Kilometros por óra',

# External editor support
'edit-externally' => 'Editar ésto archivo uzándo una aplikasion externa',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'todos',
'imagelistall'     => 'todas',
'watchlistall2'    => 'todos',
'namespacesall'    => 'todos',
'monthsall'        => '(todos)',

# E-mail address confirmation
'confirmemail'         => 'Konfirmar direksion e-pósta',
'confirmemail_send'    => 'Embiar el kodigo de konfirmasion.',
'confirmemail_sent'    => 'Konfirmasion de pósta embiada.',
'confirmemail_success' => 'Su direksion de pósta a sido konfirmada. Agóra puedes registrarse e kolaborar en el wiki.',

# Trackbacks
'trackbackremove' => ' ([$1 Efasár])',

# Delete conflict
'recreate' => 'Krear de muevo',

# HTML dump
'redirectingto' => 'Redirijiendo a [[$1]]...',

# action=purge
'confirm_purge_button' => 'Akseptár',

# AJAX search
'hideresults' => 'Eskonder resultados',

# Multipage image navigation
'imgmultipageprev' => '← pajina anterior',
'imgmultipagenext' => 'siguiente pajina →',
'imgmultigo'       => 'Ir!',

# Table pager
'table_pager_next'         => 'Pajina siguiente',
'table_pager_prev'         => 'Pajina anterior',
'table_pager_first'        => 'Primera pajina',
'table_pager_last'         => 'Ultima pajina',
'table_pager_limit_submit' => 'Ir',
'table_pager_empty'        => 'No hay resultados',

# Auto-summaries
'autoredircomment' => 'Redireksionado a [[$1]]',
'autosumm-new'     => 'Pajina mueva: $1',

# Special:Version
'version'                  => 'Versión', # Not used as normal message but as header for the special page itself
'version-specialpages'     => 'Pajinas espesiales',
'version-other'            => 'Otros',
'version-version'          => 'Versión',
'version-software-version' => 'Versión',

# Special:FileDuplicateSearch
'fileduplicatesearch-submit' => 'Bushkar',

);

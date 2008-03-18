<?php
/** Megleno-Romanian (Latin) (Vlăheşte (Latin))
 *
 * @addtogroup Language
 *
 * @author Макѕе
 * @author Кумулај Маркус
 * @author Siebrand
 */

$fallback = 'ro';

$messages = array(
# User preference toggles
'tog-underline' => 'Subliniaere legătuls:',

# Dates
'sun'           => 'Dum',
'mon'           => 'Lun',
'tue'           => 'Mar',
'wed'           => 'Mie',
'thu'           => 'Joi',
'fri'           => 'Vin',
'sat'           => 'Sam',
'january'       => 'januari',
'february'      => 'februari',
'march'         => 'marti',
'april'         => 'aprili',
'may_long'      => 'mă',
'june'          => 'juni',
'july'          => 'juli',
'august'        => 'august',
'september'     => 'septembri',
'october'       => 'oktombri',
'november'      => 'nojembri',
'december'      => 'decembri',
'january-gen'   => 'januari',
'february-gen'  => 'februari',
'march-gen'     => 'marti',
'april-gen'     => 'aprili',
'may-gen'       => 'mai',
'june-gen'      => 'juni',
'july-gen'      => 'juli',
'august-gen'    => 'august',
'september-gen' => 'septembri',
'october-gen'   => 'oktombri',
'november-gen'  => 'nojembri',
'december-gen'  => 'decembri',
'jan'           => 'jan',
'feb'           => 'feb',
'mar'           => 'mar',
'apr'           => 'apr',
'may'           => 'mai',
'jun'           => 'jun',
'jul'           => 'jul',
'aug'           => 'aug',
'sep'           => 'sep',
'oct'           => 'okt',
'nov'           => 'nov',
'dec'           => 'dec',

# Categories related messages
'category_header'        => 'Pažus en kategoria "$1"',
'subcategories'          => 'Subkategorii',
'listingcontinuesabbrev' => 'kontinu',

'about'  => 'Dajpul',
'cancel' => 'renuntǎe',
'mytalk' => 'Maj diskuţu',

'errorpagetitle'   => 'Eru',
'tagline'          => 'De {{SITENAME}}',
'help'             => 'ajutor',
'search'           => 'kaută',
'searchbutton'     => 'kaută',
'searcharticle'    => 'Lie',
'history'          => 'Historia pažus',
'printableversion' => 'vercion printablu',
'permalink'        => 'Legătul permanentul',
'edit'             => 'Edita',
'editthispage'     => 'Edita ce pažu',
'delete'           => 'Delăre',
'protect'          => 'Ažatme',
'newpage'          => 'Paži novi',
'talkpagelinktext' => 'Diskuţu',
'personaltools'    => 'Alatki personalu',
'talk'             => 'Diskuţu',
'views'            => 'Vi',
'toolbox'          => 'alatunikul',
'jumpto'           => 'Lia a:',
'jumptonavigation' => 'navigacion',
'jumptosearch'     => 'kaută',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Dajpul {{SITENAME}}',
'aboutpage'         => 'Project:Dajpul {{SITENAME}}',
'copyrightpage'     => "{{ns:project}}:Prava d'autoru",
'currentevents'     => 'Tebikoru',
'currentevents-url' => 'Project:Tebikoru',
'disclaimers'       => 'tǎmenuls',
'disclaimerpage'    => 'Project:tǎmenul',
'edithelp'          => 'Ajutor pentru editaere',
'edithelppage'      => 'Help:Editaere',
'helppage'          => 'Help:Ajutor',
'mainpage'          => 'Pažu principu',
'privacy'           => 'Politikmus de ližitul',
'privacypage'       => 'Project:Politikmus de ližitul',
'sitesupport'       => 'Donacions',
'sitesupport-url'   => 'Project:Donaţi',

'retrievedfrom'      => 'Aduse de "$1"',
'youhavenewmessages' => 'Veses $1 ($2).',
'newmessageslink'    => 'mesages noves',
'editsection'        => 'Editaere',
'editold'            => 'edita',
'editsectionhint'    => 'Editaere ţisecion: $1',
'toc'                => 'Kuprins',
'showtoc'            => 'arată',
'hidetoc'            => 'askunde',
'site-rss-feed'      => '$1 RSS fitul',
'site-atom-feed'     => '$1 Atom fitul',
'page-rss-feed'      => '"$1" RSS Fitul',
'page-atom-feed'     => '"$1" Atom Fitul',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-user'     => "Pažu d'utilizatoru",
'nstab-project'  => 'Projektu',
'nstab-image'    => 'fişirul',
'nstab-category' => 'kategoria',

# General errors
'viewsource'    => 'Baganaere',
'viewsourcefor' => 'pentru $1',

# Login and logout pages
'yourname'   => "Nom d'utilizatoru:",
'login'      => 'Prilasnaere',
'userlogin'  => 'Prilasnaere / kreare nutilizatoru',
'userlogout' => 'otlastaere',

# Edit page toolbar
'bold_sample'     => 'Eskrire aldin',
'bold_tip'        => 'Eskrire aldin',
'italic_sample'   => 'Eskrire kursive',
'italic_tip'      => 'Eskrire kursive',
'link_sample'     => "Nom s'legătuls",
'link_tip'        => 'Legătul internul',
'extlink_sample'  => "http://www.example.com nom s'legătuls",
'extlink_tip'     => 'Legătul ķsternul (vec prefiks http://)',
'headline_sample' => "Eskrire s'titlus",
'headline_tip'    => 'Titlu de nivel 2',
'math_sample'     => 'Introduca formula isi',
'math_tip'        => "Formula s'matematiks (LaTeX)",
'nowiki_sample'   => 'Intorduca no-Wiki isi',
'nowiki_tip'      => 'No-Wiki klaşu',
'image_tip'       => 'Santigul',
'media_tip'       => 'Legătul fişirul de media',
'sig_tip'         => 'Utilizatorunom et data et temp',
'hr_tip'          => 'Linia orizontala (esnidivale)',

# Edit pages
'summary'                => 'Sumar',
'watchthis'              => 'klăaere ce pažu',
'showdiff'               => 'Arată şumbărae',
'newarticle'             => '(Nova)',
'editing'                => 'o $1 editaere',
'editingsection'         => 'Editaere $1 (sekcion)',
'template-protected'     => '(ažatmat)',
'template-semiprotected' => '(semi-ažatmat)',

# History pages
'currentrev'   => 'Vercion kurentu',
'revisionasof' => 'Vercion de data $1',
'cur'          => 'aktualu',
'last'         => 'precedente',

# Diffs
'history-title'           => 'Editaerehistoria pentru "$1"',
'lineno'                  => 'Linia $1:',
'compareselectedversions' => 'Kompara vercion selektus',
'editundo'                => 'anulizăe',

# Search results
'noexactmatch' => "'''N'pažu vec l'nom \"\$1\" n'ķsistst.''' Pute [[:\$1|kreare ce pažu]].",
'viewprevnext' => 'Vu ($1) ($2) ($3)',
'powersearch'  => 'kaută',

# Preferences page
'mypreferences' => 'Maj prefirenţu',

# Recent changes
'recentchanges'   => 'şumbărae recentae',
'rcshowhidebots'  => '$1 roboti',
'rcshowhideliu'   => '$1 utilizatori prilasnaeri',
'rcshowhideanons' => '$1 utilizatori anonimi',
'rcshowhidemine'  => '$1 mes modifikacions',
'diff'            => 'diferenţu',
'hist'            => 'historia',
'hide'            => 'askunde',
'show'            => 'Arată',
'minoreditletter' => 'm',
'newpageletter'   => 'N',
'boteditletter'   => 'b',

# Upload
'upload' => 'trimiţe fişirul',

# Image description page
'filehist'            => 'Historia fişirulu',
'filehist-current'    => 'kurentu',
'filehist-datetime'   => 'Data/Temp',
'filehist-user'       => 'Utilizatoru',
'filehist-dimensions' => 'Dimencions',
'filehist-comment'    => 'komentarul',
'imagelinks'          => 'legătul',
'linkstoimage'        => 'Ces paži legǎtent a ce fişirul:',

# Random page
'randompage' => 'alaeţu',

# Miscellaneous special pages
'nbytes'       => '$1 {{PLURAL:$1|bătul|bătuls}}',
'nmembers'     => '$1 {{PLURAL:$1|membru|membri}}',
'allpages'     => 'Toats paži',
'specialpages' => 'Paži specalus',
'newpages'     => 'Paži novi',

'alphaindexline' => '$1 vo $2',

# Special:Allpages
'allarticles'    => 'Toats paži',
'allpagessubmit' => 'Treme',

# Watchlist
'watchlist'    => 'Maj klăaeru',
'mywatchlist'  => 'Maj klăaere',
'watchlistfor' => "(pentru '''$1''')",
'watch'        => 'klăaere',
'unwatch'      => 'Deklăaera',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'O klăaere...',
'unwatching' => 'O deklăaere...',

# Delete/protect/revert
'deletedarticle' => 'delǎraj "[[$1]]"',
'rollbacklink'   => 'revenire',

# Namespace form on various pages
'invert'         => 'Ķskluda spaţul',
'blanknamespace' => '(Principu)',

# Contributions
'contributions' => "Kontribuţi d'utilizatori",
'mycontris'     => 'Mes kontribuţi',
'contribsub2'   => 'Pentru $1 ($2)',
'uctop'         => '(susverf)',

# What links here
'whatlinkshere'       => 'Legǎtul a ce pažu',
'whatlinkshere-title' => 'Paži legǎtulent a $1',
'linklistsub'         => '(Lista de legătul)',
'nolinkshere'         => "Paži ne legǎtent a '''[[:$1]]'''.",
'istemplate'          => 'vikulabe',
'whatlinkshere-links' => '← legătuls',

# Block/unblock
'blocklink'    => 'blokuapǎe',
'contribslink' => 'kontribuţi',

# Move page
'revertmove' => 'revenire',

# Export
'export' => 'Ķsporta paži',

# Thumbnails
'thumbnail-more'  => 'ķsinde',
'thumbnail_error' => 'Eru vec kreare de thumbnail: $1',

# Tooltip help for the actions
'tooltip-pt-userpage'       => "Moj pažu d'utilizatoru",
'tooltip-pt-mytalk'         => 'Maj pažu diskuţus',
'tooltip-pt-preferences'    => 'Maj prefirenţu',
'tooltip-pt-mycontris'      => 'Lista de mes kontribucions',
'tooltip-pt-login'          => "Pute prilasnaere, ne l'est doist.",
'tooltip-pt-logout'         => 'otlastaere',
'tooltip-ca-protect'        => 'Ažatme ce pažu',
'tooltip-ca-delete'         => 'Delăre ce pažu',
'tooltip-search'            => 'Kaută en {{SITENAME}}',
'tooltip-n-mainpage'        => "Visita l'pažu principu",
'tooltip-n-portal'          => "Dajpul l'projectu, quelques pote faraere, o truves sabi.",
'tooltip-n-recentchanges'   => "Lista des şumbǎrae recentae en l'wiki.",
'tooltip-n-help'            => 'Ajutor truves isi.',
'tooltip-n-sitesupport'     => 'Supora-nostre',
'tooltip-t-contributions'   => "Vu lista de kontribuţi de c'utilizatoru",
'tooltip-t-specialpages'    => 'Lista de toat paži specialus',
'tooltip-ca-nstab-user'     => "Vu l'pažu d'utilizatoru",
'tooltip-ca-nstab-project'  => "Vu l'pažu de projektu",
'tooltip-ca-nstab-category' => "Vu l'pažu de kategoria",
'tooltip-minoredit'         => "ce-est n'modifikacion minoru",
'tooltip-save'              => 'Salvaere tes modifikacions',

# Media information
'file-nohires'   => "<small>Ce-n-est n'resolucion mai mari.</small>",
'show-big-image' => 'Mareşte resolucion',

# Metadata
'metadata' => 'Metadata',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'toat',
'namespacesall' => 'toat',
'monthsall'     => 'toat',

);

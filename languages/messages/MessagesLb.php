<?php
/** Luxembourgish (Lëtzebuergesch)
 *
 * @addtogroup Language
 *
 * @author SPQRobin
 * @author Siebrand
 * @author לערי ריינהארט
 * @author Kaffi
 */

$fallback = 'de';

$messages = array(
# User preference toggles
'tog-underline'       => 'Linken ënnersträichen:',
'tog-highlightbroken' => 'Format vu futtise Linken <a href="" class="new">esou</a> (alternativ: <a href="" class="internal">?</a>).',
'tog-justify'         => "Ränner vun Text riten (''justify'')",
'tog-hideminor'       => 'Verstopp mineur Ännerungen an de rezenten Ännerungen',
'tog-extendwatchlist' => 'Suivis-Lëscht op all Ännerungen ausbreeden',
'tog-usenewrc'        => 'Mat JavaScript erweidert rezent Ännerungen (klappt net mat all Browser)',
'tog-numberheadings'  => 'Iwwerschrëften automatesch numeréieren',

# Dates
'monday' => 'Méindeg',
'friday' => 'Freideg',

'about'      => 'A propos',
'newwindow'  => '(geet an enger neier Fënster op)',
'mypage'     => 'meng Säit',
'mytalk'     => 'meng Diskussioun',
'navigation' => 'Navigatioun',

'help'             => 'Hëllef',
'search'           => 'Sichen',
'go'               => 'Lass',
'history'          => 'Historique vun der Säit',
'history_short'    => 'Historique',
'printableversion' => 'Printversioun',
'permalink'        => 'Zitéierfäege Link',
'edit'             => 'Änneren',
'delete'           => 'Läschen',
'protect'          => 'Protegéieren',
'unprotect'        => 'Deprotegéieren',
'newpage'          => 'Nei Säit',
'personaltools'    => 'Perséinlech Tools',
'talk'             => 'Diskussioun',
'toolbox'          => 'Geschirkëscht',
'otherlanguages'   => 'Aner Sproochen',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Iwwer {{SITENAME}}',
'aboutpage'         => 'Project:A propos_{{SITENAME}}',
'currentevents'     => 'Aktualitéit',
'currentevents-url' => 'Project:Aktualitéit',
'mainpage'          => 'Haaptsäit',
'portal'            => 'Kommunautéit',
'portal-url'        => 'Project:Kommunautéit',
'sitesupport'       => 'Donatiounen',
'sitesupport-url'   => 'Project:En Don maachen',

'newmessageslink' => 'nei Messagen',
'showtoc'         => 'weis',
'hidetoc'         => 'verstoppen',

# General errors
'badtitle'   => 'Schlechten Titel',
'viewsource' => 'Source kucken',

# Login and logout pages
'yourdomainname'             => 'Ären Domain',
'userlogin'                  => 'Aloggen',
'userlogout'                 => 'Ausloggen',
'acct_creation_throttle_hit' => 'Dir hutt schon $1 Konten. Dir kënnt keng weider kreéieren.',
'accountcreated'             => 'De Kont gouf geschaf',
'accountcreatedtext'         => 'De Benotzerkont fir $1 gouf geschaf.',

# Edit pages
'minoredit'    => 'Mineur Ännerung',
'watchthis'    => 'Dës Säit verfollegen',
'savearticle'  => 'Säit späicheren',
'showdiff'     => 'Weis Ännerungen',
'accmailtitle' => 'Passwuert gouf geschéckt.',
'accmailtext'  => "D'Passwuert fir „$1“ gouf op $2 geschéckt.",

# History pages
'nextrevision' => 'Méi rezent Ännerung→',
'next'         => 'nächst',

# Search results
'nextn' => 'nächst $1',

# Preferences page
'preferences'   => 'Preferenzen',
'mypreferences' => 'Meng Preferenzen',
'saveprefs'     => 'Späicheren',
'newpassword'   => 'Neit Passwuert:',

# Recent changes
'recentchanges' => 'Rezent Ännerungen',
'newpageletter' => 'N',

# Recent changes linked
'recentchangeslinked' => 'Ännerungen op verlinkte Säiten',

# Upload
'upload'          => 'Eroplueden',
'savefile'        => 'Fichier späicheren',
'watchthisupload' => 'Dës Säit verfollegen',

# Random page
'randompage' => 'Zoufallssäit',

# Miscellaneous special pages
'nlinks'            => '$1 {{PLURAL:$1|Link|Linken}}',
'popularpages'      => 'Populär Säiten',
'allpages'          => 'All Säiten',
'specialpages'      => 'Spezialsäiten',
'newpages-username' => 'Benotzernumm:',
'move'              => 'Réckelen',

'alphaindexline' => '$1 bis $2',
'version'        => 'Versioun',

# Special:Log
'all-logs-page' => "All d'Logbicher",

# Special:Allpages
'nextpage'       => 'Nächst Säit ($1)',
'allarticles'    => "All d'Artikelen",
'allinnamespace' => "All d'Säiten ($1 Nummraum)",

# E-mail user
'emailuser' => 'Dësem Benotzer eng Email schécken',

# Watchlist
'watchlist'      => 'Meng Suivi-Lëscht',
'mywatchlist'    => 'Meng Suivi-Lëscht',
'addedwatch'     => "An d'Suivilëscht derbäigesat.",
'addedwatchtext' => "D'Säit \"\$1\" gouf bei är [[Special:Watchlist|Suivi-Lëscht]] bäigefügt. All weider Ännerungen op dëser Säit an/oder der Diskussiounssäit gin hei opgelëscht, an d'Säit gesäit '''fettgedréckt''' bei de [[Special:Recentchanges|rezenten Ännerungen]] aus fir se méi schnell erëmzefannen. <p>Wann dir dës Säit nëmmi verfollege wëllt, klickt op \"Nëmmi verfollegen\" op der Säit.",
'watch'          => 'Verfollegen',
'watchthispage'  => 'Dës Säit verfollegen',
'unwatch'        => 'Net méi verfollegen',

# Delete/protect/revert
'actioncomplete' => 'Aktioun ofgeschloss',

# Undelete
'undelete' => 'Geläschte Säit restauréieren',

# Namespace form on various pages
'namespace' => 'Nummraum:',

# Contributions
'contributions' => 'Kontributiounen',
'mycontris'     => 'Meng Kontributiounen',

'sp-contributions-newbies'  => 'Nëmme Beiträg vun neie Mataarbechter weisen',
'sp-contributions-search'   => 'No Beiträg sichen',
'sp-contributions-username' => 'IP-Adress oder Benotzernumm:',
'sp-contributions-submit'   => 'Sichen',

# What links here
'whatlinkshere' => 'Linken op dës Säit',

# Block/unblock
'blockip' => 'Benotzer blockéieren',

# Move page
'movepage'        => 'Säit réckelen',
'newtitle'        => 'Op neien Titel:',
'move-watch'      => 'Dës Säit verfollegen',
'1movedto2'       => '[[$1]] gouf op [[$2]] geréckelt',
'1movedto2_redir' => '[[$1]] gouf op [[$2]] geréckelt, dobäi gouf eng Weiderleedung iwwerschriwwen.',

# Namespace 8 related
'allmessages' => 'All Systemmessagen',

# Browsing diffs
'nextdiff' => 'Nächsten Ënnerscheed →',

# AJAX search
'searchcontaining' => "No Artikelen siche mat ''$1''.",

# Auto-summaries
'autosumm-replace' => "Säit gëtt ersat duerch '$1'",
'autosumm-new'     => 'Nei Säit: $1',

);

<?php
/** Luxembourgish (Lëtzebuergesch)
 *
 * @addtogroup Language
 *
 * @author SPQRobin
 * @author Siebrand
 * @author לערי ריינהארט
 * @author Kaffi
 * @author Robby
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

'underline-always' => 'ëmmer',

# Dates
'sunday' => 'Sonndeg',
'monday' => 'Méindeg',
'friday' => 'Freideg',

# Bits of text used by many pages
'categories' => 'Kategorien',

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
'minoredit'     => 'Mineur Ännerung',
'watchthis'     => 'Dës Säit verfollegen',
'savearticle'   => 'Säit späicheren',
'showdiff'      => 'Weis Ännerungen',
'accmailtitle'  => 'Passwuert gouf geschéckt.',
'accmailtext'   => "D'Passwuert fir „$1“ gouf op $2 geschéckt.",
'storedversion' => 'Gespäichert Versioun',

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
'allowemail'    => 'Emaile vun anere Benotzer kréien.',

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

# Statistics
'sitestatstext' => "Et sinn am Ganzen '''\$1''' {{PLURAL:\$1|Artikel|Artikelen}} an der Datebank.
Dozou zielen d'\"Diskussiounssäiten\", Säiten iwwert {{SITENAME}}, kuerz Artikelen, Redirecten an anerer déi eventuell net als Artikele gezielt kënne ginn.

Déi ausgeschloss ginn et {{PLURAL:\$2|Artikel den|Artikelen déi}} als Artikel betruecht {{PLURAL:\$2|ka|kënne}} ginn. 

Am ganzen {{PLURAL:\$8|gouf '''1''' Fichier|goufen '''\$8''' Fichieren}} eropgelueden.

Am ganze gouf '''\$3''' {{PLURAL:\$3|Artikeloffro|Artikeloffroen}} ann '''\$4''' {{PLURAL:\$4|Artikelbearbechtung|Artikelbearbechtungen}} zënter datt {{SITENAME}} ageriicht gouf.
Doraus ergi sech '''\$5''' Bearbechtungen pro Artikel an '''\$6''' Artikeloffroen pro Bearbechtung.

Längt vun der [http://meta.wikimedia.org/wiki/Help:Job_queue „Job queue“]: '''\$7'''",

# Miscellaneous special pages
'nlinks'            => '$1 {{PLURAL:$1|Link|Linken}}',
'popularpages'      => 'Populär Säiten',
'allpages'          => 'All Säiten',
'specialpages'      => 'Spezialsäiten',
'newpages-username' => 'Benotzernumm:',
'ancientpages'      => 'Al Säiten',
'move'              => 'Réckelen',

'alphaindexline' => '$1 bis $2',
'version'        => 'Versioun',

# Special:Log
'all-logs-page' => "All d'Logbicher",
'alllogstext'   => 'Dëst ass eng kombinéiert Lëscht vu [[Special:Log/block|Benotzerblockaden-]], [[Special:Log/protect|Säiteschutz-]], [[Special:Log/rights|Bürokraten-]], [[Special:Log/delete|Läsch-]], [[Special:Log/upload|Datei-]], [[Special:Log/move|Réckelen-]], [[Special:Log/newusers|Neiumellungs-]] a [[Special:Log/renameuser|Benotzerännerungs-]]Logbicher.',
'logempty'      => 'Näischt fonnt.',

# Special:Allpages
'nextpage'          => 'Nächst Säit ($1)',
'prevpage'          => 'Virescht Säit ($1)',
'allpagesfrom'      => 'Säite weisen, ugefaange mat:',
'allarticles'       => "All d'Artikelen",
'allinnamespace'    => "All d'Säiten ($1 Nummraum)",
'allnotinnamespace' => "All d'Säiten (net am $1 Nummraum)",
'allpagesprev'      => 'Virescht',
'allpagesnext'      => 'Nächst',
'allpagessubmit'    => 'Lass',
'allpagesprefix'    => 'Säite mat Präfix weisen:',
'allpagesbadtitle'  => 'Den Titel vun dëser Säit ass net valabel oder hat en Interwiki-Prefix. Et ka sinn datt een oder méi Zeechen drasinn, déi net an Titele benotzt kënne ginn.',
'allpages-bad-ns'   => 'De Nummraum „$1“ gëtt et net op {{SITENAME}}.',

# E-mail user
'emailuser'       => 'Dësem Benotzer eng Email schécken',
'defemailsubject' => '{{SITENAME}}-E-Mail',
'noemailtitle'    => 'Keng E-Mail-Adress',

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
'alreadyrolled'  => 'Déi lescht Ännerung vun der Säit [[$1]] vum [[User:$2|$2]] ([[User talk:$2|Diskussioun]]) kann net zeréckgesat ginn; een Aneren huet dëst entweder scho gemaach oder nei Ännerungen agedroen. Lescht Ännerung vum [[User:$3|$3]] ([[User talk:$3|Diskussioun]]).',

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
'allmessages'               => 'All Systemmessagen',
'allmessagesname'           => 'Numm',
'allmessagesdefault'        => 'Standardtext',
'allmessagescurrent'        => 'Aktuellen Text',
'allmessagestext'           => "Dëst ass eng Lëscht vun alle '''Messagen am MediaWiki:namespace''', déi vun der MediaWiki-Software benotzt ginn. Si kënnen nëmme vun [[Wikipedia:Administrators|Administratore]] geännert ginn.",
'allmessagesnotsupportedDB' => "'''Special:AllMessages''' gëtt den Ament net ënnertstëtzt well d'Datebank ''offline'' ass.",
'allmessagesfilter'         => 'Noriichtennummfilter:',
'allmessagesmodified'       => 'Nëmme geännert uweisen',

# Browsing diffs
'nextdiff' => 'Nächsten Ënnerscheed →',

# AJAX search
'searchcontaining' => "No Artikelen siche mat ''$1''.",

# Auto-summaries
'autosumm-replace' => "Säit gëtt ersat duerch '$1'",
'autosumm-new'     => 'Nei Säit: $1',

);

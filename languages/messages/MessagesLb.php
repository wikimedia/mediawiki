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
'tog-fancysig'        => 'Ënnerschrëft ouni automatesche Link op déi eege Benotzersäit',
'tog-ccmeonemails'    => 'Schéck mir eng Kopie vun de Mailen, déi ech anere Benotzer schécken.',

'underline-always' => 'ëmmer',

# Dates
'sunday'    => 'Sonndeg',
'monday'    => 'Méindeg',
'friday'    => 'Freideg',
'january'   => 'Januar',
'february'  => 'Februar',
'march'     => 'Mäerz',
'april'     => 'Abrëll',
'may_long'  => 'Mee',
'june'      => 'Juni',
'july'      => 'Juli',
'august'    => 'August',
'september' => 'September',
'october'   => 'Oktober',
'november'  => 'November',
'jan'       => 'Jan.',
'feb'       => 'Feb.',
'mar'       => 'Mäe.',
'apr'       => 'Abr.',
'may'       => 'Mee',
'jun'       => 'Jun.',
'jul'       => 'Jul.',
'aug'       => 'Aug.',
'sep'       => 'Sep.',
'oct'       => 'Okt.',
'nov'       => 'Nov.',
'dec'       => 'Dez.',

# Bits of text used by many pages
'categories' => 'Kategorien',

'about'      => 'A propos',
'article'    => 'Artikel',
'newwindow'  => '(geet an enger neier Fënster op)',
'mypage'     => 'meng Säit',
'mytalk'     => 'meng Diskussioun',
'anontalk'   => 'Diskussioun fir dës IP Adress',
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
'articlepage'      => 'Artikel',
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

'badaccess'        => 'Net genuch Rechter',
'badaccess-group0' => 'Dir hutt net déi néideg Rechter fir dës Aktioun duerchzeféieren.',

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
'minoredit'        => 'Mineur Ännerung',
'watchthis'        => 'Dës Säit verfollegen',
'savearticle'      => 'Säit späicheren',
'showdiff'         => 'Weis Ännerungen',
'anoneditwarning'  => 'Dir sidd net ageloggt. Dowéinst gëtt amplaz vun engem Benotzernumm är IP Adress am Historique vun dëser Säit gespäichert.',
'autoblockedtext'  => 'Är IP-Adress gouf automatesch gespaart, well se vun engem anere Benotzer gebraucht gouf, an dëse vum $1 gespaart ginn ass. De Grond dofir war: \'\'$2\'\' (<span class="plainlinks">[{{fullurl:Special:Ipblocklist|&action=search&limit=&ip=%23}}$5 Mentioun am Logbuch]</span>) <p style="border-style: solid; border-color: red; border-width: 1px; padding:5px;"><b>Dir kënnt d\'Säit weiderhi liesen,</b> nëmmen d\'Méiglechkeet, se ze änneren oder soss Säiten op der {{SITENAME}} unzeleeën oder ze änneren, gouf gespaart. Wann der dësen Text hei gesitt, obwuel der just liese wollt, hutt der op e roude Link geklickt gehat, deen op eng Säit verknëppt, déi et nach net gëtt.</p> Dir kënnt de(n) $1 oder soss een [[{{MediaWiki:Grouppage-sysop}}|Administrateur]] kontaktéieren, fir iwwer dës Spär ze diskutéieren. <div style="border-style: solid; border-color: red; border-width: 1px; padding:5px;"> \'\'\'Gitt dofir w.e.gl. dës Donnéeën un:\'\'\' *Administrateur dee gespaart huet: $1 *Grond fir d\'Spär: $2 *Ufank vun der Spär: $8 *Enn: $6 *IP-Adress: $3 *Spär-ID: #$5 </div>',
'accmailtitle'     => 'Passwuert gouf geschéckt.',
'accmailtext'      => "D'Passwuert fir „$1“ gouf op $2 geschéckt.",
'anontalkpagetext' => "---- ''Dëst ass d'Diskussiounssäit fir en anonyme Benotzer deen nach kee Kont opgemaach huet oder en net benotzt. Dowéinster musse mer d'[[IP Adress]] benotzen fir hien/hatt z'identifizéieren. Sou eng IP Adress ka vun e puer Benotzer gedeelt gin. Wann Dir en anonyme Benotzer sidd an dir irrelevant Kommentäre krut, [[Special:Userlogin|maacht e Kont op oder loggt Iech an]] fir weider Verwiesselungen mat anonyme Benotzer ze verhënneren.''",
'storedversion'    => 'Gespäichert Versioun',

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
'blockip'       => 'Benotzer blockéieren',
'anononlyblock' => 'nëmmen anonym Benotzer',
'autoblocker'   => 'Dir sidd autoblockéiert well dir eng IP Adress mam "$1" deelt. Grond "$2".',

# Move page
'movepage'        => 'Säit réckelen',
'newtitle'        => 'Op neien Titel:',
'move-watch'      => 'Dës Säit verfollegen',
'articleexists'   => 'Eng Säit mat dësem Numm gëtt et schonns, oder den Numm deen Dir gewielt hutt gëtt net akzeptéiert. Wielt w.e.g. en aneren Numm.',
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

# Attribution
'anonymous' => 'Anonym(e) Benotzer op {{SITENAME}}',

# Browsing diffs
'nextdiff' => 'Nächsten Ënnerscheed →',

# AJAX search
'searchcontaining' => "No Artikelen siche mat ''$1''.",
'articletitles'    => "Artikelen ugefaange mat ''$1''",

# Auto-summaries
'autosumm-blank'   => 'All Inhalt vun der Säit gëtt geläscht',
'autosumm-replace' => "Säit gëtt ersat duerch '$1'",
'autoredircomment' => 'Virugeleet op [[$1]]',
'autosumm-new'     => 'Nei Säit: $1',

);

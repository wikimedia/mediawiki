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
'underline-never'  => 'ni',

# Dates
'sunday'    => 'Sonndeg',
'monday'    => 'Méindeg',
'tuesday'   => 'Dënschdeg',
'wednesday' => 'Mëttwoch',
'thursday'  => 'Donneschdeg',
'friday'    => 'Freideg',
'saturday'  => 'Samsdeg',
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
'december'  => 'Dezember',
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
'categories'      => 'Kategorien',
'pagecategories'  => '{{PLURAL:$1|Kategorie|Kategorien}}',
'category_header' => 'Artikelen an der Kategorie "$1"',
'subcategories'   => 'Ënnerkategorien',
'category-empty'  => "''An dëser Kategorie gëtt et am Ament nach keng Artikelen oder Medien''",

'about'          => 'A propos',
'article'        => 'Artikel',
'newwindow'      => '(geet an enger neier Fënster op)',
'cancel'         => 'Zeréck',
'qbspecialpages' => 'Spezialsäiten',
'moredotdotdot'  => 'Méi …',
'mypage'         => 'meng Säit',
'mytalk'         => 'meng Diskussioun',
'anontalk'       => 'Diskussioun fir dës IP Adress',
'navigation'     => 'Navigatioun',

'errorpagetitle'   => 'Feeler',
'returnto'         => 'Zréck op $1.',
'help'             => 'Hëllef',
'search'           => 'Sichen',
'searchbutton'     => 'Sichen',
'go'               => 'Lass',
'searcharticle'    => 'Lass',
'history'          => 'Historique vun der Säit',
'history_short'    => 'Historique',
'info_short'       => 'Informatioun',
'printableversion' => 'Printversioun',
'permalink'        => 'Zitéierfäege Link',
'print'            => 'Drécken',
'edit'             => 'Änneren',
'delete'           => 'Läschen',
'protect'          => 'Protegéieren',
'unprotect'        => 'Deprotegéieren',
'newpage'          => 'Nei Säit',
'talkpagelinktext' => 'Diskussioun',
'specialpage'      => 'Spezialsäit',
'personaltools'    => 'Perséinlech Tools',
'articlepage'      => 'Artikel',
'talk'             => 'Diskussioun',
'toolbox'          => 'Geschirkëscht',
'otherlanguages'   => 'Aner Sproochen',
'jumptonavigation' => 'Navigatioun',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Iwwer {{SITENAME}}',
'aboutpage'         => 'Project:A propos_{{SITENAME}}',
'bugreports'        => 'Feelermeldungen',
'bugreportspage'    => 'Project:Feelermeldungen',
'copyright'         => 'Inhalt ass zur Verfügung gestallt ënnert der $1.<br />',
'copyrightpagename' => '{{SITENAME}} Copyright',
'copyrightpage'     => '{{ns:project}}:Copyright',
'currentevents'     => 'Aktualitéit',
'currentevents-url' => 'Project:Aktualitéit',
'mainpage'          => 'Haaptsäit',
'portal'            => 'Kommunautéit',
'portal-url'        => 'Project:Kommunautéit',
'sitesupport'       => 'Donatiounen',
'sitesupport-url'   => 'Project:En Don maachen',

'badaccess'        => 'Net genuch Rechter',
'badaccess-group0' => 'Dir hutt net déi néideg Rechter fir dës Aktioun duerchzeféieren.',
'badaccess-group1' => "D'Aktioun déi dir gewielt hutt, kann nëmme vu Benotzer aus de Gruppen $1 duerchgefouert ginn.",
'badaccess-group2' => "D'Aktioun déi dir gewielt hutt, kann nëmme vu Benotzer aus enger vun den $1 Gruppen duerchgefouert ginn.",
'badaccess-groups' => "D'Aktioun déi dir gewielt hutt, kann nëmme vu Benotzer aus de Gruppen $1 duerchgefouert ginn.",

'newmessageslink' => 'nei Messagen',
'showtoc'         => 'weis',
'hidetoc'         => 'verstoppen',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-user'     => 'Benotzersäit',
'nstab-special'  => 'Spezialsäit',
'nstab-template' => 'Schabloun',
'nstab-category' => 'Kategorie',

# Main script and global functions
'nosuchspecialpage' => 'Spezialsäit gëtt et net',
'nospecialpagetext' => "<big>'''Dir hutt eng Spezialsäit ofgefrot déi et net gëtt.'''</big>

All Spezialsäiten déi et gëtt sinn op der [[{{ns:special}}:Specialpages|Lescht vun de Spezialsäiten]] ze fannen.",

# General errors
'error'           => 'Feeler',
'cachederror'     => 'Folgend Säit ass eng Kopie aus dem Cache an net onbedéngt aktuell.',
'badarticleerror' => 'Dës Aktioun kann net op dëser Säit duerchgefouert ginn.',
'cannotdelete'    => "D'Bild oder d'Säit kann net geläscht ginn (ass waarscheinlech schonns vun engem Anere geläscht ginn).",
'badtitle'        => 'Schlechten Titel',
'badtitletext'    => 'De gewënschten Titel ass invalid, eidel, oder een net korrekten Interwiki Link.',
'viewsource'      => 'Source kucken',

# Login and logout pages
'yourname'                   => 'Benotzernumm:',
'yourpassword'               => 'Passwuert:',
'yourdomainname'             => 'Ären Domain',
'userlogin'                  => 'Aloggen',
'userlogout'                 => 'Ausloggen',
'badretype'                  => 'Är Passwierder stëmmen net iwwerdeneen.',
'youremail'                  => 'E-Mail-Adress:',
'username'                   => 'Benotzernumm:',
'uid'                        => 'Benotzer-Nummer:',
'yourrealname'               => 'Richtege Numm:',
'yourlanguage'               => 'Sprooch:',
'badsig'                     => "D'Syntax vun ärer Ënnerschëft ass net korrekt; iwwerpréift w.e.g. ären HTML Code.",
'badsiglength'               => 'De gewielten Numm ass ze laang; e muss manner wéi $1 Zeechen hunn.',
'email'                      => 'E-Mail',
'nouserspecified'            => 'Gitt w.e.g. e Benotzernumm un.',
'passwordremindertitle'      => 'Neit Passwuert fir ee {{SITENAME}}-Benotzerkonto',
'mailerror'                  => 'Feeler beim Schécke vun der E-Mail: $1',
'acct_creation_throttle_hit' => 'Dir hutt schon $1 Konten. Dir kënnt keng weider kreéieren.',
'emailauthenticated'         => 'Äer E-Mail-Adress gouf confirméiert: $1.',
'emailconfirmlink'           => 'Confirméiert äer E-Mail-Adress w.e.g..',
'accountcreated'             => 'De Kont gouf geschaf',
'accountcreatedtext'         => 'De Benotzerkont fir $1 gouf geschaf.',
'loginlanguagelabel'         => 'Sprooch: $1',

# Edit page toolbar
'bold_sample' => 'Fettgedréckten Text',
'bold_tip'    => 'Fettgedréckten Text',

# Edit pages
'summary'          => 'Résumé',
'minoredit'        => 'Mineur Ännerung',
'watchthis'        => 'Dës Säit verfollegen',
'savearticle'      => 'Säit späicheren',
'preview'          => 'Kucken ouni ofzespäicheren',
'showpreview'      => 'Kucken ouni ofzespäicheren',
'showlivepreview'  => 'Live-Kucken ouni ofzespäicheren',
'showdiff'         => 'Weis Ännerungen',
'anoneditwarning'  => 'Dir sidd net ageloggt. Dowéinst gëtt amplaz vun engem Benotzernumm är IP Adress am Historique vun dëser Säit gespäichert.',
'summary-preview'  => 'Résumé kucken ouni ofzespäicheren',
'blockedtitle'     => 'Benotzer ass gespärt',
'blockedtext'      => "Äre Benotzernumm oder är IP Adress gouf vum \$1 blockéiert. De Grond dofir ass deen heiten:<br />''\$2''<p>Dir kënnt den/d' \$1 kontaktéieren oder ee vun deenen aneren [[Wikipedia:Administrators|Administratoren]] fir de Blockage ze beschwätzen. Dëst sollt Der besonnesch maachen, wann der d'Gefill hutt, dass de Grond fir d'Spären net bei Iech läit. D'Ursaach dofir ass an deem Fall, datt der eng dynamesch IP hutt, iwwert en Access-Provider, iwwert deen och aner Leit fueren. Aus deem Grond ass et recommandéiert, sech e Benotzernumm zouzeleeën, fir all Mëssverständnes z'évitéieren. Dir kënnt d'Fonktioun \"Dësem Benotzer eng E-mail schécken\" nëmme benotzen, wann Dir eng valid Email Adress bei äre [[Special:Preferences|Preferenzen]] aginn hutt. Är IP Adress ass \$3. Schreift dës w.e.g. bei all Fro dobäi.",
'autoblockedtext'  => 'Är IP-Adress gouf automatesch gespaart, well se vun engem anere Benotzer gebraucht gouf, an dëse vum $1 gespaart ginn ass. De Grond dofir war: 

\'\'$2\'\' (<span class="plainlinks">[{{fullurl:Special:Ipblocklist|&action=search&limit=&ip=%23}}$5 Mentioun am Logbuch]</span>) 

<p style="border-style: solid; border-color: red; border-width: 1px; padding:5px;"><b>Dir kënnt d\'Säit weiderhi liesen,</b> nëmmen d\'Méiglechkeet, se ze änneren oder soss Säiten op der {{SITENAME}} unzeleeën oder ze änneren, gouf gespaart.
Wann der dësen Text gesitt, obwuel der just liese wollt, hutt der op e roude Link geklickt gehat, deen op eng Säit verknëppt, déi et nach net gëtt.</p> 

Dir kënnt de(n) $1 oder soss een [[{{MediaWiki:Grouppage-sysop}}|Administrateur]] kontaktéieren, fir iwwer dës Spär ze diskutéieren.

<div style="border-style: solid; border-color: red; border-width: 1px; padding:5px;"> \'\'\'Gitt dofir w.e.gl. dës Donnéeën un:\'\'\'
*Administrateur dee gespaart huet: $1 
*Grond fir d\'Spär: $2 
*Ufank vun der Spär: $8 
*Enn: $6 
*IP-Adress: $3 
*Spär-ID: #$5 </div>',
'accmailtitle'     => 'Passwuert gouf geschéckt.',
'accmailtext'      => "D'Passwuert fir „$1“ gouf op $2 geschéckt.",
'anontalkpagetext' => "---- ''Dëst ass d'Diskussiounssäit fir en anonyme Benotzer deen nach kee Kont opgemaach huet oder en net benotzt. Dowéinster musse mer d'[[IP Adress]] benotzen fir hien/hatt z'identifizéieren. Sou eng IP Adress ka vun e puer Benotzer gedeelt gin. Wann Dir en anonyme Benotzer sidd an dir irrelevant Kommentäre krut, [[Special:Userlogin|maacht e Kont op oder loggt Iech an]] fir weider Verwiesselungen mat anonyme Benotzer ze verhënneren.''",
'clearyourcache'   => "'''Opgepasst:''' Nom Späichere muss der Ärem Browser seng Cache eidel maachen, fir d'Ännerungen ze gesinn: '''Mozilla/Firefox:''' klickt ''reload'' (oder ''ctrl-R''), '''IE / Opera:''' ''ctrl-f5'', '''Safari:''' ''cmd-r'', '''Konqueror''' ''ctrl-r''.",
'editing'          => 'Ännere vun $1',
'editinguser'      => 'Ännere vum Benotzer <b>$1</b>',
'editingsection'   => 'Ännere vun $1 (Abschnitt)',
'editingcomment'   => 'Ännere vun $1 (Bemierkung)',
'editconflict'     => 'Ännerungskonflikt: $1',
'explainconflict'  => 'Een anere Benotzer huet un dëser Säit geschafft, während Dir amgaange waart, se ze änneren.

Dat iewegt Textfeld weist Iech den aktuellen Text.

Är Ännerunge gesitt Dir am ënneschten Textfeld.

Dir musst Är Ännerungen an dat iewegt Textfeld androen.

<b>Nëmmen</b> den Text aus dem iewegten Textfeld gëtt gehale wann Dir op "Säit späicheren" klickt. <br />',
'yourtext'         => 'Ären Text',
'storedversion'    => 'Gespäichert Versioun',
'yourdiff'         => 'Ënnerscheeder',
'templatesused'    => 'Schablounen déi an dësem Artikel gebraucht ginn:',

# Account creation failure
'cantcreateaccount-text' => 'Dës IP Adress (<b>$1</b>) gouf vum [[User:$3|$3]] blokéiert fir Benotzer-Konten op der lëtzebuergescher Wikipedia opzemaachen. De Benotzer $3 huet "$2" als Ursaach uginn.',

# History pages
'currentrev'   => 'Aktuell Versioun',
'nextrevision' => 'Méi rezent Ännerung→',
'next'         => 'nächst',
'deletedrev'   => '[geläscht]',
'historyempty' => '(eidel)',

# Diffs
'compareselectedversions' => 'Ausgewielte Versioune vergläichen',

# Search results
'nextn' => 'nächst $1',

# Preferences page
'preferences'     => 'Preferenzen',
'mypreferences'   => 'Meng Preferenzen',
'qbsettings-none' => 'Keen',
'changepassword'  => 'Passwuert änneren',
'saveprefs'       => 'Späicheren',
'newpassword'     => 'Neit Passwuert:',
'columns'         => 'Kolonnen',
'contextlines'    => 'Zuel vun de Linnen:',
'contextchars'    => 'Kontextcharactère pro Linn:',
'allowemail'      => 'Emaile vun anere Benotzer kréien.',

# Recent changes
'recentchanges' => 'Rezent Ännerungen',
'newpageletter' => 'N',

# Recent changes linked
'recentchangeslinked' => 'Ännerungen op verlinkte Säiten',

# Upload
'upload'          => 'Eroplueden',
'badfilename'     => 'Den Numm vum Fichier gouf an "$1" ëmgeännert.',
'savefile'        => 'Fichier späicheren',
'watchthisupload' => 'Dës Säit verfollegen',

# Image list
'byname' => 'no Numm',
'bydate' => 'no Datum',
'bysize' => 'no Gréisst',

# Unused templates
'unusedtemplateswlh' => 'Aner Linken',

# Random page
'randompage' => 'Zoufallssäit',

# Statistics
'statistics'    => 'Statistik',
'userstats'     => 'Benotzerstatistik',
'sitestatstext' => "Et sinn am Ganzen '''\$1''' {{PLURAL:\$1|Artikel|Artikelen}} an der Datebank.
Dozou zielen d'\"Diskussiounssäiten\", Säiten iwwert {{SITENAME}}, kuerz Artikelen, Redirecten an anerer déi eventuell net als Artikele gezielt kënne ginn.

Déi ausgeschloss ginn et {{PLURAL:\$2|Artikel den|Artikelen déi}} als Artikel betruecht {{PLURAL:\$2|ka|kënne}} ginn. 

Am ganzen {{PLURAL:\$8|gouf '''1''' Fichier|goufen '''\$8''' Fichieren}} eropgelueden.

Am ganze gouf '''\$3''' {{PLURAL:\$3|Artikeloffro|Artikeloffroen}} ann '''\$4''' {{PLURAL:\$4|Artikelbearbechtung|Artikelbearbechtungen}} zënter datt {{SITENAME}} ageriicht gouf.
Doraus ergi sech '''\$5''' Bearbechtungen pro Artikel an '''\$6''' Artikeloffroen pro Bearbechtung.

Längt vun der [http://meta.wikimedia.org/wiki/Help:Job_queue „Job queue“]: '''\$7'''",

'brokenredirects'        => 'Futtise Redirect',
'brokenredirectstext'    => 'Folgend Redirects linken op Säiten déi et net gëtt.',
'brokenredirects-delete' => '(läschen)',

# Miscellaneous special pages
'nlinks'            => '$1 {{PLURAL:$1|Link|Linken}}',
'popularpages'      => 'Populär Säiten',
'allpages'          => 'All Säiten',
'specialpages'      => 'Spezialsäiten',
'newpages-username' => 'Benotzernumm:',
'ancientpages'      => 'Al Säiten',
'move'              => 'Réckelen',

# Book sources
'booksources' => 'Bicherressourcen',

'categoriespagetext' => 'Et existéiere folgend Kategorien op {{SITENAME}}:',
'alphaindexline'     => '$1 bis $2',
'version'            => 'Versioun',

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
'emailpage'       => 'E-Mail un de Benotzer',
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

'changed' => 'geännert',

# Delete/protect/revert
'confirm'           => 'Konfirméieren',
'confirmdelete'     => "Konfirméiert d'Läschen",
'confirmdeletetext' => "Dir sidd am Gaang, eng Säit mat hirem kompletten Historique vollstänneg aus der Datebank ze läschen. W.e.g. konfirméiert, datt Dir dëst wierklech wëllt, datt Dir d'Konsequenze verstitt, an datt dat Ganzt en accordance mat de [[Wikipedia:Administrators#Konsequenze vun engem Muechtmëssbrauch|Wikipediaregele]] geschitt.",
'actioncomplete'    => 'Aktioun ofgeschloss',
'cantrollback'      => 'Lescht Ännerung kann net zeréckgesat ginn. De leschten Auteur ass deen eenzegen Auteur vun dëser Säit.',
'alreadyrolled'     => 'Déi lescht Ännerung vun der Säit [[$1]] vum [[User:$2|$2]] ([[User talk:$2|Diskussioun]]) kann net zeréckgesat ginn; een Aneren huet dëst entweder scho gemaach oder nei Ännerungen agedroen. Lescht Ännerung vum [[User:$3|$3]] ([[User talk:$3|Diskussioun]]).',
'confirmprotect'    => "Konfirméiert d'Protectioun",

# Undelete
'undelete' => 'Geläschte Säit restauréieren',

# Namespace form on various pages
'namespace'      => 'Nummraum:',
'blanknamespace' => '(Haapt)',

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
'blockip'                     => 'Benotzer blockéieren',
'blockiptext'                 => 'Benotzt dës Form fir eng spezifesch IP Adress oder e Benotzernumm ze blockéieren. Dëst soll nëmmen am Fall vu Vandalismus gemaach ginn, en accordance mat der [[Wikipedia:Vandalismus|Wikipedia Policy]]. Gitt e spezifesche Grond un (zum Beispill Säite wou Vandalismus virgefall ass).',
'badipaddress'                => "D'IP-Adress huet dat falscht Format.",
'blockipsuccesssub'           => 'Blockage erfollegräich',
'blockipsuccesstext'          => "[[Special:Contributions/$1|$1]] gouf blockéiert. <br />Kuckt d'[[Special:Ipblocklist|IP block list]] fir all Blockage ze gesin.",
'blocklistline'               => '$1, $2 blockéiert $3 (gülteg bis $4)',
'anononlyblock'               => 'nëmmen anonym Benotzer',
'blocklink'                   => 'blockéier',
'contribslink'                => 'Kontributiounen',
'autoblocker'                 => 'Dir sidd autoblockéiert well dir eng IP Adress mam "$1" deelt. Grond "$2".',
'blocklogpage'                => 'Block Log',
'blocklogentry'               => '"[[$1]]" blockéiert, gülteg bis $2',
'blocklogtext'                => "Dëst ass e Log vu Blockéieren an Deblockéieren. Automatesch blockéiert IP Adresse sinn hei net opgelëscht. Kuckt d'[[Special:Ipblocklist|IP block list]] fir déi aktuell Blockagen.",
'block-log-flags-anononly'    => 'Nëmmen anonym Benotzer',
'block-log-flags-noautoblock' => 'Autoblock deaktivéiert',

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

# Spam protection
'categoryarticlecount' => 'An dëser Kategorie {{PLURAL:$1|gëtt et bis ewell 1 Artikel|ginn et bis ewell $1 Artikelen}}.',
'category-media-count' => 'Et {{PLURAL:$1|gëtt eng Datei|ginn $1 Dateien}} an dëser Kategorie',

# Browsing diffs
'nextdiff' => 'Nächsten Ënnerscheed →',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'all',
'imagelistall'     => 'all',
'watchlistall2'    => 'all',

# E-mail address confirmation
'confirmemail'            => 'Email-Adress bestätegen',
'confirmemail_text'       => "Ier der d'Email-Funktioune vun der {{SITENAME}} notze kënnt musst der als éischt är Email-Adress bestätegen. Dréckt w.e.g. de Knäppchen hei ënnendrënner fir eng Confirmatiouns-Email op déi Adress ze schécken déi der uginn hutt. An deer Email steet e Link mat engem Code, deen der dann an ärem Browser opmaache musst fir esou ze bestätegen, datt är Adress och wierklech existéiert a valabel ass.",
'confirmemail_send'       => 'Confirmatiouns-Email schécken',
'confirmemail_sent'       => 'Confirmatiouns-Email gouf geschéckt.',
'confirmemail_sendfailed' => "D'Confirmatiouns-Email konnt net verschéckt ginn. Iwwerpréift w.e.g. är Adress op keng ongëlteg Zeechen dran enthale sinn.",
'confirmemail_invalid'    => "Ongëltege Confirmatiounscode. Eventuell ass d'Gëltegkeetsdauer vum Code ofgelaf.",
'confirmemail_success'    => 'Är Email Address gouf konfirméiert. Där kënnt iech elo aloggen an a vollem Ëmfang vun der Wiki profitéiren.',
'confirmemail_loggedin'   => 'Är Email-Adress gouf elo confirméiert.',
'confirmemail_error'      => 'Et ass eppes falsch gelaf bäim Späichere vun ärer Confirmatioun.',
'confirmemail_subject'    => '{{SITENAME}} Email-Adress-Confirmatioun',
'confirmemail_body'       => 'E User, waarscheinlech där selwer, huet mat der IP Adress $1 de Benotzerkont "$2" um Site {{SITENAME}} opgemaach. Fir ze bestätegen, datt dee Kont iech wierklech gehéiert a fir d\'Email-Funktiounen um Site {{SITENAME}} z\'aktivéieren, maacht w.e.g. de folgende Link an ärem Browser op: $3 Sollt et sech net ëm äre Benotzerkont handelen, da maacht de Link *net* op. De Confirmatiounscode gëtt den $4 ongëlteg.',

# Delete conflict
'confirmrecreate' => "De Benotzer [[User:$1|$1]] ([[User talk:$1|Diskussioun]]) huet dësen Artikel geläscht, nodeems datt där ugefaangen hutt drun ze schaffen. D'Begrënnung war: ''$2'' Bestätegt w.e.g., datt där dësen Artikel wierklich erëm nei opmaache wëllt.",

# AJAX search
'searchcontaining' => "No Artikelen siche mat ''$1''.",
'articletitles'    => "Artikelen ugefaange mat ''$1''",

# Auto-summaries
'autosumm-blank'   => 'All Inhalt vun der Säit gëtt geläscht',
'autosumm-replace' => "Säit gëtt ersat duerch '$1'",
'autoredircomment' => 'Virugeleet op [[$1]]',
'autosumm-new'     => 'Nei Säit: $1',

);

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
 * @author Nike
 */

$fallback = 'de';

$messages = array(
# User preference toggles
'tog-underline'            => 'Linken ënnersträichen:',
'tog-highlightbroken'      => 'Format vu futtise Linken <a href="" class="new">esou</a> (alternativ: <a href="" class="internal">?</a>).',
'tog-justify'              => "Ränner vum Text riten (''justify'')",
'tog-hideminor'            => 'Verstopp kleng Ännerungen an de rezenten Ännerungen',
'tog-extendwatchlist'      => 'Iwwerwaachungs-Lëscht op all Ännerungen ausbreeden',
'tog-usenewrc'             => 'Mat JavaScript erweidert rezent Ännerungen (klappt net mat all Browser)',
'tog-numberheadings'       => 'Iwwerschrëften automatesch numeréieren',
'tog-editondblclick'       => 'Säite mat Duebelklick veränneren (JavaScript)',
'tog-editsection'          => "Linke fir d'Ännere vun eenzelnen Abschnitte weisen",
'tog-showtoc'              => 'Inhaltsverzeechniss weisen bäi Säite mat méi wéi dräi Iwwerschrëften',
'tog-rememberpassword'     => 'Mäi Passwuert op dësem Computer verhalen',
'tog-editwidth'            => 'Verännerungskëscht iwwert déi ganz Breed vum Ecran',
'tog-watchcreations'       => 'Säiten déi ech nei uleeën automatesch op meng Iwwerwaachungs-Lëscht setzen',
'tog-watchdefault'         => 'Säiten déi ech veränneren automatesch op meng Iwwerwaachungs-Lëscht setzen',
'tog-watchmoves'           => 'Säiten déi ech réckelen automatesch op meng Iwwerwaachungs-Lëscht setzen',
'tog-watchdeletion'        => 'Säiten déi ech läschen automatesch op meng Iwwerwaachungs-Lëscht setzen',
'tog-minordefault'         => "Alles wat ech änneren automatesch als 'Kleng Ännerungen' weisen",
'tog-previewonfirst'       => "Beim éischten Änneren de ''Preview'' weisen.",
'tog-nocache'              => 'Säitecache deaktivéieren',
'tog-enotifwatchlistpages' => 'Schéck mir eng E-Mail wann eng vun de Säiten op menger Iwwerwaachungs-Lëscht geännert gëtt.',
'tog-enotifusertalkpages'  => 'Schéckt mir E-Maile wa meng Diskussiounssäit geännert gëtt.',
'tog-enotifminoredits'     => 'Schéckt mir och bäi kléngen Ännerungen op vu mir iwwerwaachte Säiten eng E-Mail.',
'tog-shownumberswatching'  => "D'Zuel vun de Benotzer déi dës Säit iwwerwaache weisen",
'tog-fancysig'             => 'Ënnerschrëft ouni automatesche Link op déi eege Benotzersäit',
'tog-externaleditor'       => 'Externen Editor als Standard benotzen',
'tog-externaldiff'         => 'Externen Diff-Programm als Standard benotzen',
'tog-uselivepreview'       => 'Live-Preview notzen (JavaScript) (experimentell)',
'tog-forceeditsummary'     => 'Warnen, wa beim Späicheren de Résumé feelt',
'tog-watchlisthideown'     => 'Meng Ännerungen op menger Iwwerwaachungs-Lëscht verstoppen',
'tog-watchlisthidebots'    => 'Ännerungen vu Boten op menger Iwwerwaachungs-Lëscht verstoppen',
'tog-watchlisthideminor'   => 'Kleng Ännerungen op menger Iwwerwaachungs-Lëscht verstoppen',
'tog-ccmeonemails'         => 'Schéck mir eng Kopie vun de Mailen, déi ech anere Benotzer schécken.',
'tog-diffonly'             => "Weis bei Versiounevergläicher just d'Ënnerscheeder an net déi ganz Säit",

'underline-always'  => 'ëmmer',
'underline-never'   => 'Ni',
'underline-default' => 'vun der Browserastellung ofhängeg',

'skinpreview' => '(Kucken)',

# Dates
'sunday'        => 'Sonndeg',
'monday'        => 'Méindeg',
'tuesday'       => 'Dënschdeg',
'wednesday'     => 'Mëttwoch',
'thursday'      => 'Donneschdeg',
'friday'        => 'Freideg',
'saturday'      => 'Samsdeg',
'sun'           => 'Son',
'mon'           => 'Méi',
'tue'           => 'Dën',
'wed'           => 'Mët',
'thu'           => 'Don',
'fri'           => 'Fre',
'sat'           => 'Sam',
'january'       => 'Januar',
'february'      => 'Februar',
'march'         => 'Mäerz',
'april'         => 'Abrëll',
'may_long'      => 'Mee',
'june'          => 'Juni',
'july'          => 'Juli',
'august'        => 'August',
'september'     => 'September',
'october'       => 'Oktober',
'november'      => 'November',
'december'      => 'Dezember',
'january-gen'   => 'Januar',
'february-gen'  => 'Februar',
'march-gen'     => 'Mäerz',
'april-gen'     => 'Abrëll',
'may-gen'       => 'Mee',
'june-gen'      => 'Juni',
'july-gen'      => 'Juli',
'august-gen'    => 'August',
'september-gen' => 'September',
'october-gen'   => 'Oktober',
'november-gen'  => 'November',
'december-gen'  => 'Dezember',
'jan'           => 'Jan.',
'feb'           => 'Feb.',
'mar'           => 'Mäe.',
'apr'           => 'Abr.',
'may'           => 'Mee',
'jun'           => 'Jun.',
'jul'           => 'Jul.',
'aug'           => 'Aug.',
'sep'           => 'Sep.',
'oct'           => 'Okt.',
'nov'           => 'Nov.',
'dec'           => 'Dez.',

# Bits of text used by many pages
'categories'            => 'Kategorien',
'pagecategories'        => '{{PLURAL:$1|Kategorie|Kategorien}}',
'category_header'       => 'Säiten an der Kategorie "$1"',
'subcategories'         => 'Souskategorien',
'category-media-header' => 'Medien an der Kategorie "$1"',
'category-empty'        => "''An dëser Kategorie gëtt et am Ament nach keng Säiten oder Medien''",

'mainpagetext'      => "<big>'''MediaWiki gouf mat Succès installéiert.'''</big>",
'mainpagedocfooter' => "Kuckt w.e.g. [http://meta.wikimedia.org/wiki/Help:Contents d'Benotzerhandbuch] fir den Interface ze personnaliséieren. 

== Starthëllefen ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Hëllef bei der Konfiguratioun]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki-FAQ]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Mailinglëscht vun neie MediaWiki-Versiounen]",

'about'          => 'A propos',
'article'        => 'Säit',
'newwindow'      => '(geet an enger neier Fënster op)',
'cancel'         => 'Zeréck',
'qbfind'         => 'Fannen',
'qbbrowse'       => 'Bliederen',
'qbedit'         => 'Änneren',
'qbpageoptions'  => 'Säitenoptiounen',
'qbpageinfo'     => 'Kontext',
'qbmyoptions'    => 'Meng Säiten',
'qbspecialpages' => 'Spezialsäiten',
'moredotdotdot'  => 'Méi …',
'mypage'         => 'meng Säit',
'mytalk'         => 'meng Diskussioun',
'anontalk'       => 'Diskussioun fir dës IP Adress',
'navigation'     => 'Navigatioun',

# Metadata in edit box
'metadata_help' => 'Metadaten:',

'errorpagetitle'   => 'Feeler',
'returnto'         => 'Zréck op $1.',
'tagline'          => 'Vu {{SITENAME}}',
'help'             => 'Hëllef',
'search'           => 'Sichen',
'searchbutton'     => 'Volltext-Sich',
'go'               => 'Lass',
'searcharticle'    => 'Säit',
'history'          => 'Historique vun der Säit',
'history_short'    => 'Historique',
'updatedmarker'    => "geännert zënter dat ech d'Säit fir d'lescht gekuckt hunn",
'info_short'       => 'Informatioun',
'printableversion' => 'Printversioun',
'permalink'        => 'Zitéierfäege Link',
'print'            => 'Drécken',
'edit'             => 'Änneren',
'editthispage'     => 'Dës Säit änneren',
'delete'           => 'Läschen',
'deletethispage'   => 'Dës Säit läschen',
'undelete_short'   => '$1 {{PLURAL:$1|Versioun|Versiounen}} restauréieren',
'protect'          => 'Protegéieren',
'protect_change'   => 'Protectioun änneren',
'protectthispage'  => 'Dës Säit schützen',
'unprotect'        => 'Deprotegéieren',
'newpage'          => 'Nei Säit',
'talkpage'         => 'Diskussioun',
'talkpagelinktext' => 'Diskussioun',
'specialpage'      => 'Spezialsäit',
'personaltools'    => 'Perséinlech Tools',
'postcomment'      => 'Bemierkung derbäisetzen',
'articlepage'      => 'Säit',
'talk'             => 'Diskussioun',
'views'            => 'Offroen',
'toolbox'          => 'Geschirkëscht',
'userpage'         => 'Benotzersäit',
'projectpage'      => 'Meta-Text',
'imagepage'        => 'Bildsäit',
'templatepage'     => 'Schabloune(säit) weisen',
'viewhelppage'     => 'Hëllefssäit weisen',
'categorypage'     => 'Kategoriesäit weisen',
'viewtalkpage'     => 'Diskussioun weisen',
'otherlanguages'   => 'Aner Sproochen',
'redirectedfrom'   => '(Virugeleet vun $1)',
'redirectpagesub'  => 'Redirectsäit',
'lastmodifiedat'   => "Dës Säit gouf den $1 ëm $2 Auer fir d'lescht geännert.", # $1 date, $2 time
'viewcount'        => 'Dës Säit gouf bis elo {{PLURAL:$1|emol|$1-mol}} ofgefrot.',
'protectedpage'    => 'Geschützte Säit',
'jumpto'           => 'Wiesselen op:',
'jumptonavigation' => 'Navigatioun',
'jumptosearch'     => 'Sich',

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
'disclaimers'       => 'Impressum',
'disclaimerpage'    => 'Project:Impressum',
'edithelp'          => 'Hëllef beim Änneren',
'edithelppage'      => 'Hëllef:Wéi änneren ech eng Säit',
'faq'               => 'FAQ',
'faqpage'           => 'Project:FAQ',
'mainpage'          => 'Haaptsäit',
'portal'            => 'Kommunautéit',
'portal-url'        => 'Project:Kommunautéit',
'privacy'           => 'Dateschutz',
'privacypage'       => 'Projet:Dateschutz',
'sitesupport'       => 'Donatiounen',
'sitesupport-url'   => 'Project:En Don maachen',

'badaccess'        => 'Net genuch Rechter',
'badaccess-group0' => 'Dir hutt net déi néideg Rechter fir dës Aktioun duerchzeféieren.',
'badaccess-group1' => "D'Aktioun déi dir gewielt hutt, kann nëmme vu Benotzer aus de Gruppen $1 duerchgefouert ginn.",
'badaccess-group2' => "D'Aktioun déi dir gewielt hutt, kann nëmme vu Benotzer aus enger vun den $1 Gruppen duerchgefouert ginn.",
'badaccess-groups' => "D'Aktioun déi dir gewielt hutt, kann nëmme vu Benotzer aus de Gruppen $1 duerchgefouert ginn.",

'versionrequired'     => 'Versioun $1 vu MediaWiki gëtt gebraucht',
'versionrequiredtext' => "D'Versioun $1 vu MediaWiki ass néideg, fir dës Säit ze notzen. Kuckt d'[[{{ns:special}}:Version|Versiounssäit]]",

'retrievedfrom'           => 'Vun „$1“',
'youhavenewmessages'      => 'Dir hutt $2 op ärer $1.',
'newmessageslink'         => 'nei Messagen',
'newmessagesdifflink'     => 'Nei Messagen',
'youhavenewmessagesmulti' => 'Dir huet nei Messagen op $1',
'editsection'             => 'änneren',
'editold'                 => 'änneren',
'editsectionhint'         => 'Abschnitt veränneren: $1',
'toc'                     => 'Inhaltsverzeechnis',
'showtoc'                 => 'weisen',
'hidetoc'                 => 'verstoppen',
'thisisdeleted'           => '$1 kucken oder zerécksetzen?',
'viewdeleted'             => '$1 weisen?',
'restorelink'             => '$1 geläschte {{PLURAL:$1|Versioun|Versionen}}',
'feedlinks'               => 'Feed:',
'site-rss-feed'           => 'RSS-Feed fir $1',
'site-atom-feed'          => 'Atom-Feed fir $1',
'page-rss-feed'           => 'RSS-Feed fir "$1"',
'page-atom-feed'          => 'Atom-Feed fir "$1"',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Säit',
'nstab-user'      => 'Benotzersäit',
'nstab-media'     => 'Media Säit',
'nstab-special'   => 'Spezialsäit',
'nstab-project'   => 'Portalsäit',
'nstab-image'     => 'Fichier',
'nstab-mediawiki' => 'Systemmessage',
'nstab-template'  => 'Schabloun',
'nstab-help'      => 'Hëllef-Säit',
'nstab-category'  => 'Kategorie',

# Main script and global functions
'nosuchaction'      => 'Dës Aktioun gëtt et net',
'nosuchspecialpage' => 'Spezialsäit gëtt et net',
'nospecialpagetext' => "<big>'''Dir hutt eng Spezialsäit ofgefrot déi et net gëtt.'''</big>

All Spezialsäiten déi et gëtt sinn op der [[{{ns:special}}:Specialpages|Lescht vun de Spezialsäiten]] ze fannen.",

# General errors
'error'                => 'Feeler',
'databaseerror'        => 'Datebank Feeler',
'dberrortext'          => 'En Datebank Syntax Fehler ass opgetrueden. De läschten Datebank Query war: "$1" vun der Funktioun "$2". MySQL Feeler "$3: $4".',
'nodb'                 => "D'Datebank $1 konnt net gewielt ginn",
'cachederror'          => 'Folgend Säit ass eng Kopie aus dem Cache an net onbedéngt aktuell.',
'laggedslavemode'      => 'Opgepasst: Dës Säit ass net onbedingt um neiste Stand.',
'readonly'             => "D'Datebank ass gespart",
'enterlockreason'      => 'Gitt w.e.g. e Grond fir de Lock an, a wéilaang en ongeféier bestoe soll.',
'readonlytext'         => 'Datebank ass am Moment fir all Ännerunge gespaart, wahrscheinlech wéinst Maintenance vun der Datebank, duerno ass erëm alles beim alen. 

Den Administrateur huet folgend Erklärung uginn: $1',
'missingarticle'       => 'D\'Datebank huet den Text vun enger Säit net fonnt deen hätt solle fonnt ginn, mam Numm "$1".

Dëst geschitt meeschtens andeems en alen diff oder Historique Link op eng Säit hiweist déi geläscht ginn ass.

Wann dëst net de Fall ass, hutt dir vläicht e Feeler an der Software fonnt. Sot w.e.g. engem Administrateur Bescheed an deelt him och d\'URL mat.',
'readonly_lag'         => "D'Datebank gouf automatesch gespaart fir datt d'Zweetserveren (slaves) nees mat dem Haaptserver (master) synchron geschalt kënne ginn.",
'internalerror'        => 'Interne Feeler',
'internalerror_info'   => 'Interne Feeler: $1',
'filecopyerror'        => 'De Fichier "$1" konnt net op "$2" kopéiert ginn.',
'filerenameerror'      => 'De Fichier "$1" konnt net op "$2" ëmbenannt ginn.',
'filedeleteerror'      => 'De Fichier "$1" konnt net geläscht ginn.',
'directorycreateerror' => 'De Repertoire "$1" konnt net geschafe ginn.',
'filenotfound'         => 'De Fichier "$1" gouf net fonnt.',
'badarticleerror'      => 'Dës Aktioun kann net op dëser Säit duerchgefouert ginn.',
'cannotdelete'         => "D'Bild oder d'Säit kann net geläscht ginn (ass waarscheinlech schonns vun engem Anere geläscht ginn).",
'badtitle'             => 'Schlechten Titel',
'badtitletext'         => 'De gewënschten Titel ass invalid, eidel, oder een net korrekten Interwiki Link.',
'perfdisabled'         => "'''Pardon!''' Dës Fonktioun gouf wéint Iwwerlaaschtung vum Server temporaire ausgeschalt.",
'perfcachedts'         => 'Dës Donneeë kommen aus dem Cache, leschten Update: $1',
'querypage-no-updates' => "D'Aktualiséierung vun dëser Säit ass zur Zäit ausgeschalt. D'Daten gi bis op weideres net aktualiséiert.'''",
'wrong_wfQuery_params' => 'Falsche Parameter fir wfQuery()<br />
Funktioun: $1<br />
Ufro: $2',
'viewsource'           => 'Source kucken',
'viewsourcefor'        => 'fir $1',
'actionthrottled'      => 'Dës Aktioun gouf gebremst',
'protectedpagetext'    => 'Dës Säit ass fir Ännerunge gespaart.',
'viewsourcetext'       => 'Dir kënnt de Quelltext vun dëser Säit kucken a kopéieren:',
'protectedinterface'   => 'Op dëser Säit fannt Dir Text fir de Sprooch-Interface vun der Software an dofir ass si gespaart fir Mëssbrauch ze verhenneren.',
'editinginterface'     => "'''Opgepasst:''' Dir sidd am Gaang, eng Säit z'änneren, déi do ass, fir Interface-Text fir d'Software ze liwweren. Ännerungen op dëser Säit änneren den Interface-Text, dee jidderee liese kann.",
'namespaceprotected'   => "Dir hutt net déi néideg Rechter fir d'Säiten am Nummraum '''$1''' ze änneren.",
'customcssjsprotected' => 'Dir hutt net déi néideg Rechter fir dës Säit ze änneren, wëll si zu de perséinlechen Astellungen vun engem anere Benotzer gehéiert.',
'ns-specialprotected'  => 'Säiten am {{ns:special}}-Nummraum kënnen net verännert ginn.',

# Login and logout pages
'logouttitle'                => 'Benotzer-Ofmeldung',
'logouttext'                 => '<strong>Dir sidd elo ofgemeld.</strong>

Dir kënnt {{SITENAME}} elo anonym benotzen, oder Iech nacheemol als deeselwechten oder en anere Benotzer umelden. 

Opgepasst: Op verschiddene Säite gesäit et nach esou aus, wéi wann Dir nach ugemeld wiert, bis Dir ärem Browser seng Cache eidelmaacht.',
'welcomecreation'            => '== Wëllkomm, $1! ==

Äre Kont gouf kreéiert. Denkt drun, Är {{SITENAME}}-Preferenzen anzestellen.',
'loginpagetitle'             => 'Benotzer-Umeldung',
'yourname'                   => 'Benotzernumm:',
'yourpassword'               => 'Passwuert:',
'yourpasswordagain'          => 'Passwuert widderhuelen:',
'remembermypassword'         => 'Meng Umeldung op dësem Computer verhalen',
'yourdomainname'             => 'Ären Domain',
'loginproblem'               => "'''Et gouf e Problem bäi ärer Umeldung.'''<br />

Probéiert et w.e.g. nach eng Kéier!",
'login'                      => 'Umellen',
'loginprompt'                => "Fir sech op {{SITENAME}} umellen ze kënnen, mussen d'Cookien aktivéiert sinn.",
'userlogin'                  => 'Aloggen',
'logout'                     => 'Ofmellen',
'userlogout'                 => 'Ausloggen',
'notloggedin'                => 'Net ugemellt',
'nologin'                    => 'Hutt Dir kee Benotzerkont? $1.',
'nologinlink'                => 'Neie Benotzerkonto maachen',
'createaccount'              => 'Neie Kont opmaachen',
'gotaccount'                 => 'Dier hutt schonn e Kont? $1.',
'gotaccountlink'             => 'Umellen',
'createaccountmail'          => 'Via Email',
'badretype'                  => 'Är Passwierder stëmmen net iwwerdeneen.',
'userexists'                 => 'Dëse Benotzernumm gëtt scho benotzt. Sicht iech w.e.g. een anere Benotzernumm.',
'youremail'                  => 'E-Mail-Adress:',
'username'                   => 'Benotzernumm:',
'uid'                        => 'Benotzer-Nummer:',
'yourrealname'               => 'Richtege Numm:',
'yourlanguage'               => 'Sprooch:',
'yournick'                   => 'Äre Spëtznumm (fir Ënnerschrëften)',
'badsig'                     => "D'Syntax vun ärer Ënnerschëft ass net korrekt; iwwerpréift w.e.g. ären HTML Code.",
'badsiglength'               => 'De gewielten Numm ass ze laang; e muss manner wéi $1 Zeechen hunn.',
'email'                      => 'E-Mail',
'loginerror'                 => 'Feeler bäi der Umeldung',
'prefs-help-email'           => 'E-mail adress ass optional, awer si erméiglecht et anere Benotzer, per Mail Kontakt matt iech opzehuelen, ouni datt Dir är Identitéit musst präisginn, an erméiglecht et datt dir e neit Passwuert per Mail zougeschöckt kënnt kréien.',
'nocookieslogin'             => "{{SITENAME}} benotzt Cookiën beim Umelle vun de Benotzer. Dir hutt Cookiën ausgeschalt, w.e.g aktivéiert d'Cookiën a versicht et nach eng Kéier.",
'noname'                     => 'Dir hutt kee gültege Benotzernumm uginn.',
'loginsuccesstitle'          => 'Umeldung huet geklappt',
'loginsuccess'               => "'''Dir sidd elo als \"\$1\" op {{SITENAME}} ugemellt.'''",
'nosuchuser'                 => 'De Benotzernumm "$1" gëtt et net. Kuckt w.e.g. op d\'Schreifweis richteg ass, oder meld iech als neie Benotzer un.',
'nosuchusershort'            => 'De Benotzernumm "$1" gëtt et net. Kuckt w.e.g. op d\'Schreifweis richteg ass.',
'nouserspecified'            => 'Gitt w.e.g. e Benotzernumm un.',
'wrongpassword'              => 'Dir hutt e falscht (oder kee) Passwuert aginn. Probéiert w.e.g. nach eng Kéier.',
'wrongpasswordempty'         => "D'Passwuert dat Dir aginn huet war eidel. Probéiert w.e.g. nach eng Kéier.",
'passwordtooshort'           => 'Ärt Passwuert ass ongülteg oder ze kuerz: Et muss mindestens $1 Zeeche laang sinn an et däerf net matt dem Benotzernumm identesch sinn.',
'mailmypassword'             => 'Neit Passwuert per mail kréien',
'passwordremindertitle'      => 'Neit Passwuert fir ee {{SITENAME}}-Benotzerkonto',
'passwordremindertext'       => "Iergend een matt der IP-Adress $1, waarscheinlech Dir selwer, huet een neit Passwuert fir d'Umeldung op {{SITENAME}} ($4) gefrot.

Dat automatesch generéiert Passwuert fir  de Benotzer $2 ass elo: $3

Dir sollt iech elo umellen an d'Passwuert änneren.

Wann een aneren dës Ufro sollt gemaach hunn oder wann Dir iech an der Zwëschenzäit nees un ärt Passwuert erënnere kënnt an Dir àrt Passwuert net ännere wëllt dann ignoréiert dëse Message a benotzt weider ärt aalt Passwuert.",
'noemail'                    => 'De Benotzer „$1“ huet keng E-Mail-Adress uginn.',
'blocked-mailpassword'       => "Déi vun iech benotzten IP-Adress ass fir d'Ännere vu Säite gespaart. Fir Mëssbrauch ze verhënneren, gouf d'Méiglechkeet fir een neit Passwuert unzefroen och gespaart.",
'eauthentsent'               => "Eng Confirmatiouns-Email gouf un déi uginnen Adress geschéckt.<br>Ier iergend eng Email vun anere Benotzer op dee Kont geschéckt ka ginn, muss der als éischt d'Instructiounen an der Confirmatiouns-Email befollegen, fir ze bestätegen datt de Kont wierklech ären eegenen ass.",
'mailerror'                  => 'Feeler beim Schécke vun der E-Mail: $1',
'acct_creation_throttle_hit' => 'Dir hutt schon $1 Konten. Dir kënnt keng weider kreéieren.',
'emailauthenticated'         => 'Äer E-Mail-Adress gouf confirméiert: $1.',
'emailnotauthenticated'      => 'Är Email Adress gouf <strong>nach net confirméiert</strong>.<br>Dowéinst ass et bis ewell net méiglech, fir déi folgend Funktiounen Emailen ze schécken oder ze kréien.',
'noemailprefs'               => 'Gitt eng E-Mail-Adress un, fir datt déie folgend Funktiounen fonctionéieren.',
'emailconfirmlink'           => 'Confirméiert äer E-Mail-Adress w.e.g..',
'accountcreated'             => 'De Kont gouf geschaf',
'accountcreatedtext'         => 'De Benotzerkont fir $1 gouf geschaf.',
'createaccount-title'        => 'Opmaache vun engem Benotzerkont op {{SITENAME}}',
'loginlanguagelabel'         => 'Sprooch: $1',

# Password reset dialog
'resetpass'           => 'Passwuert fir Benotzerkont zrécksetzen',
'resetpass_header'    => 'Passwuert zrécksetzen',
'resetpass_submit'    => 'Passwuert aginn an umellen',
'resetpass_forbidden' => 'Passwierder kënnen op {{SITENAME}} net geännert ginn.',
'resetpass_missing'   => 'Eidelt Formular',

# Edit page toolbar
'bold_sample'     => 'Fettgedréckten Text',
'bold_tip'        => 'Fettgedréckten Text',
'italic_sample'   => 'Kursiven Text',
'italic_tip'      => 'Kursiven Text',
'link_sample'     => 'Link-Text',
'link_tip'        => 'Interne Link',
'extlink_sample'  => 'http://www.example.com Link Titel',
'extlink_tip'     => 'Externe Link (Vergiesst net den http:// Prefix)',
'headline_sample' => 'Titel Text',
'headline_tip'    => 'Iwwerschrëft vum Niveau 2',
'math_sample'     => 'Formel hei asetzen',
'math_tip'        => 'Mathematesch Formel (LaTeX)',
'nowiki_sample'   => 'Unformatéierten Text hei afügen',
'nowiki_tip'      => 'Unformatéierten Text',
'image_tip'       => 'Bildlink',
'media_tip'       => 'Link op e Medien Fichier',
'sig_tip'         => 'Är Ënnerschrëft matt Zäitstempel',
'hr_tip'          => 'Horizontal Linn (mat Moosse gebrauchen)',

# Edit pages
'summary'                   => 'Résumé',
'subject'                   => 'Sujet/Iwwerschrëft',
'minoredit'                 => 'Kleng Ännerung',
'watchthis'                 => 'Dës Säit iwwerwaachen',
'savearticle'               => 'Säit späicheren',
'preview'                   => 'Kucken ouni ofzespäicheren',
'showpreview'               => 'Kucken ouni ofzespäicheren',
'showlivepreview'           => 'Live-Kucken ouni ofzespäicheren',
'showdiff'                  => 'Weis Ännerungen',
'anoneditwarning'           => 'Dir sidd net ageloggt. Dowéinst gëtt amplaz vun engem Benotzernumm är IP Adress am Historique vun dëser Säit gespäichert.',
'missingsummary'            => "'''Erënnerung:''' Dir hutt kee Résumé aginn. Wann Dir nachemol op \"Säit ofspäicheren\" klickt, gëtt är Ännerung ouni Résumé ofgespäichert.",
'missingcommenttext'        => 'Gitt w.e.g. eng Bemierkung an.',
'summary-preview'           => 'Résumé kucken ouni ofzespäicheren',
'blockedtitle'              => 'Benotzer ass gespärt',
'blockedtext'               => "<big>Äre Benotzernumm oder är IP Adress gouf gespaart.</big>

Dëse Spär gouf vum \$1 gemaach. Als Grond gouf ''\$2'' uginn.

* Ufank vun der Spär: \$8
* Ënn vun der Spär: \$6
* Spär betrefft: \$7
 
Dir kënnt den/d' \$1 kontaktéieren oder ee vun deenen aneren [[{{MediaWiki:Grouppage-sysop}}|Administrateuren]] fir d'Spär ze beschwetzen.

Dëst sollt Der besonnesch maachen, wann der d'Gefill hutt, dass de Grond fir d'Spären net bei Iech läit. D'Ursaach dofir ass an deem Fall, datt der eng dynamesch IP hutt, iwwert en Access-Provider, iwwert deen och aner Leit fueren. Aus deem Grond ass et recommandéiert, sech e Benotzernumm zouzeleeën, fir all Mëssverständnes z'évitéieren. 

Dir kënnt d'Fonktioun \"Dësem Benotzer eng E-mail schécken\" nëmme benotzen, wann Dir eng gülteg Email Adress bei äre [[Special:Preferences|Preferenzen]] aginn hutt. Är aktuell-IP Adress ass \$3 an d'Nummer vun der Spär ass #\$5. Schreift dës w.e.g. bei all Fro dobäi.",
'autoblockedtext'           => 'Är IP-Adress gouf automatesch gespaart, well se vun engem anere Benotzer gebraucht gouf, an dëse vum $1 gespaart ginn ass. De Grond dofir war: 

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
'blockednoreason'           => 'Kee Grond uginn',
'blockededitsource'         => "Den Text vun '''ären Ännerungen''' op '''$1''' steet hei ënnendrënner:",
'whitelistedittitle'        => 'Login noutwännesch fir ze änneren',
'whitelistedittext'         => 'Dir musst iech $1, fir Säiten änneren ze kënnen.',
'whitelistreadtitle'        => 'Fir ze liesen muss Dir ugemeld sinn',
'whitelistreadtext'         => 'Dir musst [[Special:Userlogin|ageloggt]] sinn, fir Säiten ze liesen.',
'whitelistacctitle'         => 'Dir däerft kee Benotzerkont uleeën.',
'whitelistacctext'          => 'Fir Konten op {{SITENAME}} kënnen opzemaachen musst Dir [[Special:Userlogin|ageloggt]] sinn an déi noutwänneg Rechter hunn.',
'loginreqlink'              => 'umellen',
'accmailtitle'              => 'Passwuert gouf geschéckt.',
'accmailtext'               => "D'Passwuert fir „$1“ gouf op $2 geschéckt.",
'newarticle'                => '(Nei)',
'anontalkpagetext'          => "---- ''Dëst ass d'Diskussiounssäit fir en anonyme Benotzer deen nach kee Kont opgemaach huet oder en net benotzt. Dowéinster musse mir d'IP Adress benotzen fir hien/hatt z'identifizéieren. Sou eng IP Adress ka vun e puer Benotzer gedeelt ginn. Wann Dir en anonyme Benotzer sidd an dir irrelevant Kommentäre krut, [[Special:Userlogin|maacht e Kont op oder loggt Iech an]] fir weider Verwiesselungen mat anonyme Benotzer ze verhënneren.''",
'noarticletext'             => '(Dës Säit huet momentan nach keen Text, Dir kënnt op anere Säiten no [[Special:Search/{{PAGENAME}}|dësem Säitentitel sichen]] oder [{{fullurl:{{FULLPAGENAME}}|action=edit}} esou eng Säit uleeën)',
'userpage-userdoesnotexist' => 'De Benotzerkont "$1" gëtt et net. Iwwerpréift w.e.g. op Dir dës Säit erschafe/ännere wëllt.',
'clearyourcache'            => "'''Opgepasst:''' Nom Späichere muss der Ärem Browser seng Cache eidel maachen, fir d'Ännerungen ze gesinn: '''Mozilla/Firefox:''' klickt ''reload'' (oder ''ctrl-R''), '''IE / Opera:''' ''ctrl-f5'', '''Safari:''' ''cmd-r'', '''Konqueror''' ''ctrl-r''.",
'userjspreview'             => "'''Denkt drun datt Dir äre Javascript nëmmen test, nach ass näischt gespäichert!'''",
'updated'                   => '(Geännert)',
'note'                      => '<strong>Notiz:</strong>',
'previewnote'               => "<strong>Dëst ass nëmmen e Preview; D'Ännerunge sinn nach net gespäichert!</strong>",
'editing'                   => 'Ännere vun $1',
'editinguser'               => 'Ännere vum Benotzer <b>$1</b>',
'editingsection'            => 'Ännere vun $1 (Abschnitt)',
'editingcomment'            => 'Ännere vun $1 (Kommentar)',
'editconflict'              => 'Ännerungskonflikt: $1',
'explainconflict'           => 'Een anere Benotzer huet un dëser Säit geschafft, während Dir amgaange waart, se ze änneren.

Dat iewegt Textfeld weist Iech den aktuellen Text.

Är Ännerunge gesitt Dir am ënneschten Textfeld.

Dir musst Är Ännerungen an dat iewegt Textfeld androen.

<b>Nëmmen</b> den Text aus dem iewegten Textfeld gëtt gehale wann Dir op "Säit späicheren" klickt. <br />',
'yourtext'                  => 'Ären Text',
'storedversion'             => 'Gespäichert Versioun',
'editingold'                => '<strong>OPGEPASST: Dir ännert eng al Versioun vun dëser Säit. Wann Dir späichert, sinn all rezent Versioune vun dëser Säit verluer.</strong>',
'yourdiff'                  => 'Ënnerscheeder',
'copyrightwarning'          => 'W.e.g. notéiert datt all Kontributiounen op {{SITENAME}} automatesch ënner der $2 (kuckt $1 fir méi Informatiounen) verëffentlecht sinn. Wann Dir net wëllt datt är Texter vun anere Mataarbechter verännert, geläscht a weiderverdeelt kënne ginn, da setzt näischt heihinner.<br /> 

Dir verspriecht ausserdeem datt dir dësen Text selwer verfaasst hutt, oder aus dem Domaine public oder ähnleche Ressource kopéiert hutt.

<strong>DROT KEE COPYRECHTLECH GESCHÜTZTE CONTENU OUNI ERLAABNISS AN!</strong>',
'copyrightwarning2'         => 'W.e.g. notéiert datt all Kontributiounen op {{SITENAME}} vun anere Matschaffer verännert oder geläscht kënne ginn. Wann dir dat net wëllt, da setzt näischt heihinner.<br /> Dir verspriecht ausserdeem datt dir dësen Text selwer verfaasst hutt, oder aus dem Domaine public oder ähnlecher Ressource kopéiert hutt. (cf. $1 fir méi Detailler). <strong>DROT KEE COPYRECHTLECH GESCHÜTZTE CONTENU AN!</strong>',
'longpagewarning'           => '<strong>WARNUNG: Dës Säit ass $1 kB grouss; verschidde Browser kéinte Problemer hunn, Säiten ze verschaffen, déi méi grouss wéi 32 KB sinn.

Iwwerleet w.e.g., ob eng Opdeelung vun der Säit a méi kleng Abschnitter méiglich ass.</strong>',
'protectedpagewarning'      => '<strong>OPGEPASST: Dës Säit gouf gespaart a kann nëmme vun engem Administrateur geännert ginn.</strong>',
'templatesused'             => 'Schablounen déi op dëser Säit am Gebrauch sinn:',
'templatesusedpreview'      => 'Schablounen déi an dësem Preview am Gebrauch sinn:',
'templatesusedsection'      => 'Schablounen déi an dësem Abschnitt am Gebrauch sinn:',
'template-protected'        => '(protegéiert)',
'nocreate-loggedin'         => 'Dir hutt keng Berechtigung fir nei Säiten op {{SITENAME}} unzeleeën.',
'permissionserrorstext'     => 'Dir hutt net genuch Rechter fir déi Aktioun auszeféieren. {{PLURAL:$1|Grond|Grënn}}:',
'recreate-deleted-warn'     => "'''Opgepasst: Dir sidd am Gaang eng Säit unzeleeën, déi schonn emol geläscht gouf.'''
 
Passt w.e.g. op ob déi nei Säit de Critèren entsprécht.
Zu äre Informatioun fannt Dir d'Läsch-Logbuch mat dem Grond fir dat virescht Läschen:",

# Account creation failure
'cantcreateaccount-text' => 'Dës IP Adress (<b>$1</b>) gouf vum [[User:$3|$3]] blokéiert fir Benotzer-Konten op der lëtzebuergescher Wikipedia opzemaachen. De Benotzer $3 huet "$2" als Ursaach uginn.',

# History pages
'viewpagelogs'        => 'Logbicher fir dës Säit weisen',
'revnotfound'         => 'Dës Versioun gouf net fonnt.',
'currentrev'          => 'Aktuell Versioun',
'revisionasof'        => 'Versioun vum $1',
'revision-info'       => 'Versioun vum $1 vum $2.',
'previousrevision'    => '← Méi al Versioun',
'nextrevision'        => 'Méi rezent Ännerung→',
'currentrevisionlink' => 'aktuell Revisioun kucken',
'cur'                 => 'aktuell',
'next'                => 'nächst',
'last'                => 'lescht',
'orig'                => 'Original',
'page_first'          => 'éischt',
'page_last'           => 'Enn',
'histlegend'          => "Fir d'Ännerungen unzeweisen: Klickt déi zwou Versiounen un déi verglach solle ginn.<br /> 
*(aktuell) = Ënnerscheed mat der aktueller Versioun,
*(lescht) = Ënnerscheed mat der aler Versioun, K = Kleng Ännerung.",
'deletedrev'          => '[geläscht]',
'histfirst'           => 'Eelsten',
'histlast'            => 'Neitsten',
'historysize'         => '({{PLURAL:$1|1 Byte|$1 Byten}})',
'historyempty'        => '(eidel)',

# Revision feed
'history-feed-title'          => 'Historique vun de Versiounen',
'history-feed-description'    => 'Versiounshistorique fir dës Säit op {{SITENAME}}',
'history-feed-item-nocomment' => '$1 ëm $2', # user at time

# Revision deletion
'rev-deleted-comment'    => '(Bemierkung geläscht)',
'rev-deleted-user'       => '(Benotzernumm ewechgeholl)',
'rev-deleted-event'      => '(Aktioun ewechgeholl)',
'rev-delundel'           => 'weisen/verstoppen',
'revisiondelete'         => 'Versioune läschen/restauréieren',
'revdelete-selected'     => "{{PLURAL:$2|Gewielte Versioun|Gewielte Versiounen}} vu(n) '''$1:'''",
'revdelete-hide-text'    => 'Text vun der Versioun verstoppen',
'revdelete-hide-name'    => 'Logbuch-Aktioun verstoppen',
'revdelete-hide-comment' => 'Bemierkung verstoppen',
'revdelete-hide-user'    => 'Dem Auteur säi Benotzernumm/IP verstoppen',
'revdelete-suppress'     => 'Grond vum Läschen och fir Administrateure verstoppt',
'revdelete-hide-image'   => 'Bildinhalt verstoppen',
'revdelete-logaction'    => '$1 {{PLURAL:$1|Versioun|Versiounen}} an de Modus $2 gesat',

# History merging
'mergehistory'        => 'Historiquë fusionéieren',
'mergehistory-box'    => 'Historiquë vun zwou Säite fusionéieren',
'mergehistory-into'   => 'Zilsäit:',
'mergehistory-submit' => 'Versioune verschmelzen',
'mergehistory-empty'  => 'Et kënne keng Versioune fusionéiert ginn',

# Diffs
'history-title'           => 'Versiounshistorique vun „$1“',
'difference'              => '(Ennerscheed tëscht Versiounen)',
'lineno'                  => 'Linn $1:',
'compareselectedversions' => 'Ausgewielte Versioune vergläichen',
'editundo'                => 'zréck',
'diff-multi'              => '({{PLURAL:$1|Eng Tëscheversioun gëtt net|$1 Tëscheversioune ginn net}} gewisen.',

# Search results
'searchresults'         => 'Resultat vun der Sich',
'searchsubtitleinvalid' => 'Dir hutt no "$1" gesicht.',
'noexactmatch'          => "'''Et gëtt keng Säite mam Titel \"\$1\".''' 

Dir kënnt [[:\$1|déi Säit uleeën]].",
'noexactmatch-nocreate' => "'''Et gëtt keng Säit mam Titel \"\$1\".'''",
'titlematches'          => 'Säitentitel Iwwerdeneestëmmungen',
'textmatches'           => 'Säitentext Iwwerdeneestëmmungen',
'prevn'                 => 'virescht $1',
'nextn'                 => 'nächst $1',
'viewprevnext'          => 'Weis ($1) ($2) ($3)',
'showingresults'        => "Hei gesitt der  {{PLURAL:$1| '''1''' Resultat|'''$1''' Resultater}}, ugefaang mat #'''$2'''.",
'showingresultsnum'     => "Hei gesitt der  {{PLURAL:$3|'''1''' Resultat|'''$1''' Resultater}}, ugefaange mat #'''$2'''.",
'nonefound'             => '<strong>Opgepasst</strong>: Net erfollegräich Siche geschéien dacks doduerch, datt zevill allgeméng Wierder benotzt ginn, wéi "an" oder "vun", déi net indexéiert sinn, oder wann dir méi wéi ee Wuert ugitt (dir kritt nëmmen déi Säiten ugewisen, an deenen all d\'Wierder stinn).',
'powersearch'           => 'Sichen',

# Preferences page
'preferences'           => 'Preferenzen',
'mypreferences'         => 'Meng Preferenzen',
'prefs-edits'           => 'Zuel vun den Ännerungen:',
'prefsnologin'          => 'Net ugemeld',
'qbsettings-none'       => 'Keen',
'qbsettings-fixedleft'  => 'Lénks, fest',
'qbsettings-fixedright' => 'Riets, fest',
'changepassword'        => 'Passwuert änneren',
'skin'                  => 'Skin',
'dateformat'            => 'Datumsformat',
'datedefault'           => 'Egal (Standard)',
'datetime'              => 'Datum an Auerzäit',
'math_unknown_error'    => 'Onbekannte Feeler',
'math_lexing_error'     => "'Lexing'-Feeler",
'math_syntax_error'     => 'Syntaxfeeler',
'prefs-personal'        => 'Benotzerprofil',
'prefs-rc'              => 'Rezent Ännerungen',
'prefs-watchlist'       => 'Iwwerwaachungs-Lëscht',
'prefs-watchlist-days'  => 'Maximal Zuel vun den Deeg, déi an der Iwwerwaachungs-Lëscht ugewise solle ginn:',
'prefs-watchlist-edits' => 'Maximal Zuel vun den Ännerungen déi an der erweiderter Iwwerwaachungs-Lëscht ugewise solle ginn:',
'prefs-misc'            => 'Verschiddenes',
'saveprefs'             => 'Späicheren',
'oldpassword'           => 'Aalt Passwuert:',
'newpassword'           => 'Neit Passwuert:',
'retypenew'             => 'Neit Passwuert (nachemol):',
'textboxsize'           => 'Änneren',
'rows'                  => 'Zeilen',
'columns'               => 'Kolonnen',
'searchresultshead'     => 'Sich',
'contextlines'          => 'Zuel vun de Linnen:',
'contextchars'          => 'Kontextcharactère pro Linn:',
'stub-threshold'        => 'Maximum (a Byte) bei deem e Link nach ëmmer am <a href="#" class="stub">Skizze-Format</a> gewise gëtt:',
'recentchangesdays'     => 'Deeg déi an de Rezenten Ännerungen ugewise ginn:',
'recentchangescount'    => 'Zuel vun Titele bei de rezenten Ännerungen an den Neie Säiten:',
'savedprefs'            => 'Är Preferenze goufe gespäichert.',
'timezonelegend'        => 'Zäitzon',
'timezonetext'          => "Gitt d'Zuel vun de Stonnen an, déi tëscht ärer Zäitzon an der Serverzäit (UTC) leien (A West- a Mëtteleuropa ass dat +1 Stonn am Wanter an +2 am Summer).",
'localtime'             => 'Lokalzäit:',
'timezoneoffset'        => 'Ënnerscheed¹:',
'servertime'            => 'Serverzäit:',
'guesstimezone'         => 'Vum Browser iwwerhuelen',
'allowemail'            => 'Emaile vun anere Benotzer kréien.',
'defaultns'             => 'Dës Nimmraim duerchsichen:',
'default'               => 'Standard',
'files'                 => 'Fichieren',

# User rights
'userrights-lookup-user'     => 'Benotzergrupp verwalten',
'userrights-user-editname'   => 'Benotzernumm uginn:',
'editusergroup'              => 'Benotzergruppen änneren',
'userrights-editusergroup'   => 'Benotzergruppen änneren',
'saveusergroups'             => 'Benotzergruppe späicheren',
'userrights-groupsmember'    => 'Member vun:',
'userrights-groupsavailable' => "Et ginn d'Gruppen:",
'userrights-reason'          => 'Grond:',
'userrights-available-none'  => 'Dir däerft keng Benotzerrechter änneren.',
'userrights-available-add'   => "Dir kënnt Benotzer an d'Grupppen $1 derbäisetzen.",
'userrights-nodatabase'      => "D'Datebank $1 gëtt et net oder se ass net lokal.",

# Groups
'group'            => 'Grupp:',
'group-bot'        => 'Boten',
'group-sysop'      => 'Administrateuren',
'group-bureaucrat' => 'Bürokraten',
'group-all'        => '(all)',

'group-bot-member'        => 'Bot',
'group-sysop-member'      => 'Administrateur',
'group-bureaucrat-member' => 'Bürokrat',

'grouppage-bot'        => '{{ns:project}}:Botten',
'grouppage-sysop'      => '{{ns:project}}:Administrateuren',
'grouppage-bureaucrat' => '{{ns:project}}:Bürokraten',

# User rights log
'rightslog'  => 'Logbuch vun de Benotzerrechter',
'rightsnone' => '(keen)',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|Ännerung|Ännerungen}}',
'recentchanges'                     => 'Rezent Ännerungen',
'rcnote'                            => "Ugewise {{PLURAL:$1|gëtt '''1''' Ännerung|ginn déi lescht '''$1''' Ännerungen}} {{PLURAL:$2|vum leschten Dag|vun de leschten '''$2''' Deeg}}. Stand: $3. (<b><tt>N</tt></b>&nbsp;– nei Säiten; <b><tt>k</tt></b>&nbsp;– kleng Ännerung; <b><tt>B</tt></b>&nbsp;– Ännerung durch ee Bot; ''(± Zuel)''&nbsp;– Gréisst vun der Ännerung a Byte)",
'rcnotefrom'                        => "Ugewise ginn d'Ännerunge vum <b>$2</b> un (maximum <b>$1</b> Ännerunge gi gewisen).",
'rclistfrom'                        => 'Nëmmen Ännerungen zënter $1 weisen.',
'rcshowhideminor'                   => 'Kleng Ännerungen $1',
'rcshowhidebots'                    => 'Botten $1',
'rcshowhideliu'                     => 'Ugemellte Benotzer $1',
'rcshowhideanons'                   => 'Anonym Benotzer $1',
'rcshowhidepatr'                    => '$1 iwwerwaacht Ännerungen',
'rcshowhidemine'                    => 'Meng Ännerungen $1',
'rclinks'                           => 'Weis déi lescht $1 Ännerungen vun de leschten $2 Deeg.<br />$3',
'diff'                              => 'Ënnerscheed',
'hist'                              => 'Versiounen',
'hide'                              => 'verstoppen',
'show'                              => 'weisen',
'minoreditletter'                   => 'k',
'newpageletter'                     => 'N',
'boteditletter'                     => 'B',
'number_of_watching_users_pageview' => '[$1 Benotzer {{PLURAL:$1|iwwerwaacht|iwwerwaachen}}]',
'rc_categories'                     => 'Nëmme Säiten aus de Kategorien (getrennt mat "|"):',
'rc_categories_any'                 => 'All',
'newsectionsummary'                 => 'Neien Abschnitt /* $1 */',

# Recent changes linked
'recentchangeslinked'          => 'Ännerungen op verlinkte Säiten',
'recentchangeslinked-title'    => 'Ännerungen op Säiten, déi vun "$1" verlinkt sinn',
'recentchangeslinked-noresult' => 'Am ausgewielten Zäitraum goufen op de verlinkte Säite keng Ännerunge gemaach.',

# Upload
'upload'              => 'Eroplueden',
'uploadbtn'           => 'Fichier eroplueden',
'uploadnologin'       => 'Net ugemellt',
'uploadlogpage'       => 'Logbuch vum eroplueden',
'filename'            => 'Numm vum Fichier',
'filedesc'            => 'Résumé',
'fileuploadsummary'   => 'Résumé/Source:',
'filestatus'          => 'Copyright Status',
'filesource'          => 'Source',
'ignorewarning'       => 'Warnung ignoréieren an de Fichier nawell späicheren.',
'ignorewarnings'      => 'Ignoréier all Iwwerschreiwungswarnungen',
'illegalfilename'     => 'Am Fichiernumm "$1" sti Schrëftzeechen, déi net am Numm vun enger Säit erlaabt sinn. W.e.g. nennt de Fichier anescht, a probéiert dann nach eng Kéier.',
'badfilename'         => 'Den Numm vum Fichier gouf an "$1" ëmgeännert.',
'emptyfile'           => 'De Fichier deen Dir eropgelueden hutt, schéngt eidel ze sinn. Dëst kann duerch en Tippfeeler am Numm vum Fichier kommen. Préift w.e.g. no, op Dir dëse Fichier wierklech eropluede wëllt.',
'fileexists'          => 'Et gëtt schonn e Fichier mat dësem Numm, kuckt w.e.g. $1 wann Dir net sécher sidd, op Dir den Numm ännere wëllt.',
'successfulupload'    => 'Eroplueden erfollegräich',
'savefile'            => 'Fichier späicheren',
'uploadedimage'       => 'huet "[[$1]]" eropgelueden',
'sourcefilename'      => 'Numm vum Sourcefichier',
'destfilename'        => 'Numm op der Wiki',
'watchthisupload'     => 'Dës Säit iwwerwaachen',
'filename-bad-prefix' => 'Den Numm vum Fichier fänkt mat <strong>„$1“</strong> un. Dësen Numm ass automatesch vun der Kamera gi ginn a seet näischt iwwert dat aus, wat drop ass. Gitt dem Fichier w.e.gl. en Numm, deen den Inhalt besser beschreift, an deen net verwiesselt ka ginn.',

'upload-proto-error' => 'Falsche Protokoll',
'upload-file-error'  => 'Interne Feeler',
'upload-misc-error'  => 'Onbekannte Feeler beim Eroplueden',

'upload_source_file' => ' (e Fichier op Ärem Computer)',

# Image list
'imagelist'                 => 'Lëscht vun de Fichieren',
'imagelisttext'             => "Hei ass eng Lëscht vun '''$1''' {{PLURAL:$1|Fichier|Fichieren}}, zortéiert $2.",
'getimagelist'              => 'Billerlëscht gëtt opgestallt',
'ilsubmit'                  => 'Sichen',
'showlast'                  => 'Weis déi lescht $1 Fichieren, no $2 zortéiert.',
'byname'                    => 'no Numm',
'bydate'                    => 'no Datum',
'bysize'                    => 'no Gréisst',
'filehist'                  => 'Versiounen',
'filehist-help'             => 'Klickt op e bestëmmten Zäitpunkt fir déi respektiv Versioun vum Fichier ze kucken.',
'filehist-deleteall'        => 'All Versioune läschen',
'filehist-deleteone'        => 'Dës Versioun läschen',
'filehist-revert'           => 'zrécksetzen',
'filehist-current'          => 'aktuell',
'filehist-datetime'         => 'Versioun vum',
'filehist-user'             => 'Benotzer',
'filehist-dimensions'       => 'Dimesiounen',
'filehist-filesize'         => 'Gréisst vum Fichier',
'filehist-comment'          => 'Bemierkung',
'imagelinks'                => 'Biller Linken',
'linkstoimage'              => 'Déi folgenden Säite benotzen dëse Fichier:',
'nolinkstoimage'            => 'Keng Säit benotzt dëse Fichier.',
'sharedupload'              => 'Dës Fichier ass ee gemeinsam genotzten Upload a ka vun anere Projeten benotzt ginn.',
'shareduploadwiki-linktext' => 'Datei-Beschreiwungssäit',
'noimage'                   => 'Ee Fichier mat dësem Numm gëtt et net, Dir kënnt $1.',
'noimage-linktext'          => 'eroplueden',
'uploadnewversion-linktext' => 'Eng nei Versioun vun dësem Fichier eroplueden',
'imagelist_date'            => 'Datum',
'imagelist_name'            => 'Numm',
'imagelist_user'            => 'Benotzer',
'imagelist_size'            => 'Gréisst',
'imagelist_description'     => 'Beschreiwung',
'imagelist_search_for'      => 'Sicht nom Fichier:',

# File reversion
'filerevert'                => '„$1“ zerécksetzen',
'filerevert-legend'         => 'De Fichier zerécksetzen.',
'filerevert-intro'          => '<span class="plainlinks">Du setz de Fichier \'\'\'[[Media:$1|$1]]\'\'\' op d\'[$4 Versioun vum $2, $3 Auer] zeréck.</span>',
'filerevert-comment'        => 'Grond:',
'filerevert-defaultcomment' => "zeréckgesat op d'Versioun vum $1, $2 Auer",
'filerevert-submit'         => 'Zerécksetzen',
'filerevert-success'        => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\' gouf op d\'[$4 Versioun vum $2, $3 Auer] zeréckgesat.</span>',

# File deletion
'filedelete'         => 'Läsch "$1"',
'filedelete-legend'  => 'Fichier läschen',
'filedelete-intro'   => "Dir läscht de Fichier '''[[Media:$1|$1]]'''.",
'filedelete-comment' => 'Grond:',
'filedelete-submit'  => 'Läschen',
'filedelete-success' => "'''$1''' gouf geläscht.",

# MIME search
'mimesearch' => 'Sich no MIME-Zort',
'download'   => 'eroflueden',

# Unwatched pages
'unwatchedpages' => 'Nët iwwerwaachte Säiten',

# Unused templates
'unusedtemplates'    => 'Onbenotzte Schablounen',
'unusedtemplateswlh' => 'Aner Linken',

# Random page
'randompage'         => 'Zoufallssäit',
'randompage-nopages' => 'Et gëtt keng Säiten an dësem Nummraum.',

# Random redirect
'randomredirect' => 'Zoufälleg Weiderleedung',

# Statistics
'statistics'             => 'Statistik',
'sitestats'              => '{{SITENAME}}-Statistik',
'userstats'              => 'Benotzerstatistik',
'sitestatstext'          => "Et sinn am Ganzen '''\$1''' {{PLURAL:\$1|Säit|Säiten}} an der Datebank.
Dozou zielen d'\"Diskussiounssäiten\", Säiten iwwert {{SITENAME}}, kuerz Säiten, Redirecten an anerer déi eventuell net als Säite gezielt kënne ginn.

Déi ausgeschloss ginn et {{PLURAL:\$2|Säit|Säiten}} déi als Säite betruecht {{PLURAL:\$2|ka|kënne}} ginn. 

Am ganzen {{PLURAL:\$8|gouf '''1''' Fichier|goufen '''\$8''' Fichieren}} eropgelueden.

Am ganze gouf '''\$3''' {{PLURAL:\$3|Säitenoffro|Säitenoffroen}} ann '''\$4''' {{PLURAL:\$4|Säitebearbechtung|Säitebearbechtungen}} zënter datt {{SITENAME}} ageriicht gouf.

Doraus ergi sech '''\$5''' Bearbechtungen pro Säit an '''\$6''' Säitenoffroen pro Bearbechtung.

Längt vun der [http://meta.wikimedia.org/wiki/Help:Job_queue „Job queue“]: '''\$7'''",
'userstatstext'          => "Et gi '''$1''' {{PLURAL:$1|registréierten|registréiert}} [[Special:Listusers|Benotzer]].  '''$2''' (oder '''$4%''') vun dëse {{PLURAL:$2|ass|sinn}} $5.",
'statistics-mostpopular' => 'Am meeschte gekuckte Säiten',

'disambiguations'     => 'Homonymie Säiten',
'disambiguationspage' => 'Template:Homonymie',

'doubleredirects'     => 'Duebel Redirecten',
'doubleredirectstext' => '<b>Opgepasst:</b> An dëser Lëscht kënne falsch Positiver stoen. Dat heescht meeschtens datt et nach Text zu de Linke vum éischte #REDIRECT gëtt.<br /> An all Rei sti Linken zum éischten an zweete Redirect, souwéi déi éischt Zeil vum Text vum zweete Redirect, wou normalerweis déi "richteg" Zilsäit drasteet, op déi den éischte Redirect hilinke soll.',

'brokenredirects'        => 'Futtise Redirect',
'brokenredirectstext'    => 'Folgend Redirects linken op Säiten déi et net gëtt.',
'brokenredirects-edit'   => '(änneren)',
'brokenredirects-delete' => '(läschen)',

'withoutinterwiki'        => 'Säiten ouni Interwiki-Linken',
'withoutinterwiki-header' => 'Dës Säiten hu keng Interwiki-Linken:',

'fewestrevisions' => 'Säite mat de mannsten Ännerungen',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|Byte|Byten}}',
'ncategories'             => '$1 {{PLURAL:$1|Kategorie|Kategorien}}',
'nlinks'                  => '$1 {{PLURAL:$1|Link|Linken}}',
'nmembers'                => '$1 {{PLURAL:$1|Member|Memberen}}',
'nrevisions'              => '$1 {{PLURAL:$1|Revisioun|Revisiounen}}',
'nviews'                  => '$1 {{PLURAL:$1|Offro|Offroen}}',
'specialpage-empty'       => 'Dës Säit ass eidel.',
'lonelypages'             => 'Weesesäiten',
'lonelypagestext'         => 'Dës Säite sinn net vun anere Säite vun {{SITENAME}} verlinkt.',
'uncategorizedpages'      => 'Säiten ouni Kategorie',
'uncategorizedcategories' => 'Kategorien ouni Kategorie',
'uncategorizedimages'     => 'Biller ouni Kategorie',
'uncategorizedtemplates'  => 'Schablounen ouni Kategorie',
'unusedcategories'        => 'Onbenotzt Kategorien',
'unusedimages'            => 'Unbenotzt Biller',
'popularpages'            => 'Populär Säiten',
'wantedcategories'        => 'Gewënschte Kategorien',
'wantedpages'             => 'Gewënschte Säiten',
'mostlinked'              => 'Dack verlinkte Säiten',
'mostlinkedcategories'    => 'Dack benotzte Kategorien',
'mostlinkedtemplates'     => 'Dack benotzte Schablounen',
'mostcategories'          => 'Säite mat de meeschte Kategorien',
'mostimages'              => 'Dack benotzte Biller',
'mostrevisions'           => 'Säite mat de meeschten Versiounen',
'allpages'                => 'All Säiten',
'shortpages'              => 'Kuurz Säiten',
'longpages'               => 'Laang Säiten',
'deadendpages'            => 'Sakgaasse-Säiten',
'deadendpagestext'        => 'Dës Säite si mat kenger anerer Säit op {{SITENAME}} verlinkt.',
'protectedpages'          => 'Protegéiert Säiten',
'protectedtitles'         => 'Gespaarten Titel',
'listusers'               => 'Benotzerlëscht',
'specialpages'            => 'Spezialsäiten',
'spheading'               => 'Spezialsäite fir all Benotzer',
'newpages'                => 'Nei Säiten',
'newpages-username'       => 'Benotzernumm:',
'ancientpages'            => 'Al Säiten',
'intl'                    => 'Interwiki-Linken',
'move'                    => 'Réckelen',
'movethispage'            => 'Dës Säit réckelen',
'pager-newer-n'           => '{{PLURAL:$1|nächsten|nächst $1}}',
'pager-older-n'           => '{{PLURAL:$1|virëschten|virëscht $1}}',

# Book sources
'booksources' => 'Bicherressourcen',

'categoriespagetext' => 'Et existéiere folgend Kategorien op {{SITENAME}}:',
'userrights'         => 'Benotzerrechterverwaltung',
'groups'             => 'Benotzergruppen',
'alphaindexline'     => '$1 bis $2',
'version'            => 'Versioun',

# Special:Log
'specialloguserlabel'  => 'Benotzer:',
'speciallogtitlelabel' => 'Titel:',
'log'                  => 'Logbicher',
'all-logs-page'        => "All d'Logbicher",
'log-search-submit'    => 'Sichen',
'alllogstext'          => 'Dëst ass eng kombinéiert Lëscht vu [[Special:Log/block|Benotzerblockaden-]], [[Special:Log/protect|Säiteschutz-]], [[Special:Log/rights|Bürokraten-]], [[Special:Log/delete|Läsch-]], [[Special:Log/upload|Datei-]], [[Special:Log/move|Réckelen-]], [[Special:Log/newusers|Neiumellungs-]] a [[Special:Log/renameuser|Benotzerännerungs-]]Logbicher.',
'logempty'             => 'Näischt fonnt.',
'log-title-wildcard'   => 'Titel fänkt u matt …',

# Special:Allpages
'nextpage'          => 'Nächst Säit ($1)',
'prevpage'          => 'Virescht Säit ($1)',
'allpagesfrom'      => 'Säite weisen, ugefaange mat:',
'allarticles'       => "All d'Säiten",
'allinnamespace'    => "All d'Säiten ($1 Nummraum)",
'allnotinnamespace' => "All d'Säiten (net am $1 Nummraum)",
'allpagesprev'      => 'Virescht',
'allpagesnext'      => 'Nächst',
'allpagessubmit'    => 'Lass',
'allpagesprefix'    => 'Säite mat Präfix weisen:',
'allpagesbadtitle'  => 'Den Titel vun dëser Säit ass net valabel oder hat en Interwiki-Prefix. Et ka sinn datt een oder méi Zeechen drasinn, déi net an Titele benotzt kënne ginn.',
'allpages-bad-ns'   => 'De Nummraum „$1“ gëtt et net op {{SITENAME}}.',

# Special:Listusers
'listusers-submit'   => 'Weis',
'listusers-noresult' => 'Kee Benotzer fonnt.',

# E-mail user
'mailnologintext' => 'Dir musst [[Special:Userlogin|ugemellt]] sinn an eng gülteg Email Adress an äre [[Special:Preferences|Preferenzen]] agestallt hunn, fir engem anere Benotzer eng E-mail ze schécken.',
'emailuser'       => 'Dësem Benotzer eng Email schécken',
'emailpage'       => 'Dem Benotzer eng Email schécken',
'emailpagetext'   => 'Wann dëse Benotzer eng valid Email Adress a senge Preferenzen agestallt huet, kënnt Dir mat dësem Formulaire e Message schécken. Déi Email Adress déi dir an Äre Preferenzen aginn hutt, steet an der "From" Adress vun der Mail, sou datt den Destinataire Iech och äntwerte kann.',
'usermailererror' => 'E-Mail-Objet mellt deen heite Feeler:',
'defemailsubject' => '{{SITENAME}}-E-Mail',
'noemailtitle'    => 'Keng E-Mail-Adress',
'emailfrom'       => 'Vum',
'emailto'         => 'Fir',
'emailsubject'    => 'Sujet',
'emailmessage'    => 'Message',
'emailsend'       => 'Schécken',
'emailccme'       => 'Eng E-mail-Kopie vum Message fir mech',
'emailccsubject'  => 'Kopie vun denger Noriicht un $1: $2',
'emailsent'       => 'Email geschéckt',
'emailsenttext'   => 'Är Email gouf fortgeschéckt.',

# Watchlist
'watchlist'            => 'Meng Iwwerwaachungs-Lëscht',
'mywatchlist'          => 'Meng Iwwerwaachungs-Lëscht',
'watchlistfor'         => "(fir '''$1''')",
'watchlistanontext'    => "Wannechglift $1 fir Säiten op ärer Suivi-Lëscht ze gesinn oder z'änneren.",
'watchnologin'         => 'Net ageloggt',
'watchnologintext'     => 'Dir musst [[Special:Userlogin|ageloggt]] sinn, fir Är Suivi-Lëscht ze änneren.',
'addedwatch'           => "An d'Iwwerwaachungs-Lëscht derbäigesat.",
'addedwatchtext'       => "D'Säit \"\$1\" gouf bei är [[Special:Watchlist|Lëscht vun den iwwerwaachte Säiten]] bäigefügt. All weider Ännerungen op dëser Säit an/oder der Diskussiounssäit gin hei opgelëscht, an d'Säit gesäit '''fettgedréckt''' bei de [[Special:Recentchanges|rezenten Ännerungen]] aus fir se méi séier erëmzefannen.

Wann dir dës Säit nëmmi iwwerwaache wëllt, klickt op \"Net méi iwwerwaachen\" op der Säit.",
'removedwatch'         => 'Vun der Iwwerwaachungslëscht erofgeholl',
'removedwatchtext'     => 'D\'Säit "$1" gouf vun ärer Iwwerwaachungs-Lësch erofgeholl.',
'watch'                => 'Iwwerwaachen',
'watchthispage'        => 'Dës Säit iwwerwaachen',
'unwatch'              => 'Net méi iwwerwaachen',
'unwatchthispage'      => 'Net méi iwwerwaachen',
'watchnochange'        => 'Keng vun Äre verfollegte Säite gouf während der ugewisener Zäitperiod verännert.',
'watchlist-details'    => "Dir iwwerwaacht {{PLURAL:$1|1 Säit|$1 Säiten}} (d'Diskussiounssäite net matgezielt).",
'wlheader-enotif'      => '* Email-Bescheed ass aktivéiert.',
'wlheader-showupdated' => "* Säiten déi zënter ärer leschter Visite geännert goufen, si '''fett''' geschriwwen",
'watchmethod-recent'   => 'Rezent Ännerungen gin op verfollegt Säiten iwwerpréift',
'watchmethod-list'     => 'Verfollegt Säite ginn op rezent Ännerungen iwwerpréift',
'watchlistcontains'    => 'Op ärer Iwwerwaachungs-Lëscht $1 {{PLURAL:$1|steet $1 Säit|stinn $1 Säiten}}.',
'wlshowlast'           => "Weis d'Ännerunge vun de leschte(n) $1 Stonnen, $2 Deeg oder $3 (an de leschten 30 Deeg).",
'watchlist-show-bots'  => 'Bot-Ännerunge weisen',
'watchlist-hide-bots'  => 'Bot-Ännerunge verstoppen',
'watchlist-show-own'   => 'Meng Ännerunge weisen',
'watchlist-hide-own'   => 'Meng Ännerunge verstoppen',
'watchlist-show-minor' => 'Kleng Ännerunge weisen',
'watchlist-hide-minor' => 'kleng Ännerungen verstoppen',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Iwwerwaachen …',
'unwatching' => 'Net iwwerwaachen …',

'enotif_newpagetext'           => 'Dëst ass eng nei Säit.',
'enotif_impersonal_salutation' => '{{SITENAME}}-Benotzer',
'changed'                      => 'geännert',
'enotif_lastvisited'           => 'All Ännerungen op ee Bléck: $1',
'enotif_lastdiff'              => 'Kuckt $1 fir dës Ännerung.',
'enotif_anon_editor'           => 'Anonyme Benotzer $1',

# Delete/protect/revert
'deletepage'                  => 'Läschungssäit',
'confirm'                     => 'Konfirméieren',
'excontent'                   => "Inhalt war: '$1'",
'excontentauthor'             => "De Contenu war: '$1' (An als eenzegen dru geschriwwen hat den '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'               => "Den Inhalt virum Läsche wor: '$1'",
'exblank'                     => "D'Säit wor eidel",
'confirmdelete'               => "Konfirméiert d'Läschen",
'deletesub'                   => '("$1" gëtt geläscht)',
'historywarning'              => 'Opgepasst: Déi Säit déi dir läsche wëllt huet en Historique.',
'confirmdeletetext'           => "Dir sidd am Gaang, eng Säit mat hirem kompletten Historique vollstänneg aus der Datebank ze läschen. W.e.g. konfirméiert, datt Dir dëst wierklech wëllt, datt Dir d'Konsequenze verstitt, an datt dat Ganzt en accordance mat de [[{{MediaWiki:Policy-url}}|Richtlinien]] geschitt.",
'actioncomplete'              => 'Aktioun ofgeschloss',
'deletedtext'                 => '"$1" gouf geläscht. Kuckt $2 fir eng Lëscht vu rezent geläschte Säiten.',
'deletedarticle'              => '"$1" gouf geläscht',
'dellogpage'                  => 'Läschungslog',
'dellogpagetext'              => 'Hei fannt dir eng Lëscht mat rezent geläschte Säiten. All Auerzäiten sinn déi vum Server (UTC).',
'deletionlog'                 => 'Läschungslog',
'deletecomment'               => "Grond fir d'Läschen",
'deleteotherreason'           => 'Aneren/ergänzende Grond:',
'deletereasonotherlist'       => 'Anere Grond',
'rollbacklink'                => 'Zrécksetzen',
'cantrollback'                => 'Lescht Ännerung kann net zeréckgesat ginn. De leschten Auteur ass deen eenzegen Auteur vun dëser Säit.',
'alreadyrolled'               => 'Déi lescht Ännerung vun der Säit [[$1]] vum [[User:$2|$2]] ([[User talk:$2|Diskussioun]]) kann net zeréckgesat ginn; een Aneren huet dëst entweder scho gemaach oder nei Ännerungen agedroen. Lescht Ännerung vum [[User:$3|$3]] ([[User talk:$3|Diskussioun]]).',
'editcomment'                 => 'Ännerungskommentar: "<i>$1</i>".', # only shown if there is an edit comment
'protectlogpage'              => 'Protectiouns-Logbuch',
'modifiedarticleprotection'   => 'huet d\'Protectioun vun "[[$1]]" geännert',
'confirmprotect'              => "Konfirméiert d'Protectioun",
'protectcomment'              => 'Grond:',
'protectexpiry'               => 'Dauer vun der Spär:',
'protect_expiry_invalid'      => "D'Dauer déi Dir uginn hutt ass ongültig.",
'protect_expiry_old'          => "D'Spärzäit läit an der Vergaangenheet.",
'protect-unchain'             => 'Réckel-Protectioun änneren',
'protect-text'                => "Hei kënnt Dir de Protectiounsstatus fir d'Säit <strong>$1</strong> kucken an änneren.",
'protect-default'             => 'Alleguer (Standard)',
'protect-fallback'            => 'Eng "$1"-Autorisatioun gëtt gebraucht.',
'protect-level-autoconfirmed' => 'Spär fir net ugemellte Benotzer',
'protect-level-sysop'         => 'Nëmmen Administrateuren',
'protect-expiring'            => 'bis $1 (UTC)',
'protect-cascade'             => "Kaskade-Spär – alleguerten d'Schablounen déi an dës Säit agebonne si ginn och gespaart.",
'minimum-size'                => 'Mindestgréisst:',
'maximum-size'                => 'Maximalgréisst:',

# Restrictions (nouns)
'restriction-edit' => 'Änneren',
'restriction-move' => 'réckelen',

# Restriction levels
'restriction-level-all' => 'alleguerten',

# Undelete
'undelete'                 => 'Geläschte Säit restauréieren',
'viewdeletedpage'          => 'Geläschte Säite weisen',
'undeletehistorynoadmin'   => "Dës Säit gouf geläscht. De Grond fir d'Läschen gesitt der ënnen, zesumme mat der Iwwersiicht vun den eenzele Versioune vun der Säit an hiren Auteuren. De Contenu vun dësen Textversioune kann awer just vun Administrateuren gekuckt a restauréiert ginn.",
'undelete-nodiff'          => 'Et si keng méi al Versiounen do.',
'undeletebtn'              => 'Restauréieren',
'undeletecomment'          => 'Grond:',
'undeletedarticle'         => 'huet "[[$1]]" restauréiert',
'undeletedrevisions'       => '$1 {{PLURAL:$1|Versioun gouf|$1 Versioune goufe}} restauréiert',
'undeletedrevisions-files' => '{{PLURAL:$1|1 Versioun|$1 Versiounen}} an {{PLURAL:$2|1 Fichier|$2 Fichieren}} goufe restauréiert',
'undelete-search-box'      => 'Sich no geläschte Säiten',
'undelete-search-submit'   => 'Sichen',

# Namespace form on various pages
'namespace'      => 'Nummraum:',
'invert'         => 'Auswiel ëmdréinen',
'blanknamespace' => '(Haapt)',

# Contributions
'contributions' => 'Kontributiounen',
'mycontris'     => 'Meng Kontributiounen',
'contribsub2'   => 'Fir $1 ($2)',
'ucnote'        => 'Hei stinn dësem Benotzer seng lescht <b>$1</b> Ännerungen vun de leschten <b>$2</b> Deeg.',
'uclinks'       => 'Weis déi läscht $1 Kontributiounen; weis déi läscht $2 Deeg.',
'uctop'         => ' (aktuell)',
'month'         => 'Vum Mount (a virdrun):',
'year'          => 'Vum Joer (a virdrun):',

'sp-contributions-newbies'     => 'Nëmme Kontributioune vun neie Mataarbechter weisen',
'sp-contributions-newbies-sub' => 'Fir déi Nei',
'sp-contributions-blocklog'    => 'Block Log',
'sp-contributions-search'      => 'No Kontributioune sichen',
'sp-contributions-username'    => 'IP-Adress oder Benotzernumm:',
'sp-contributions-submit'      => 'Sichen',

'sp-newimages-showfrom' => 'Nei Biller weisen, ugefaange mat $1',

# What links here
'whatlinkshere'       => 'Linken op dës Säit',
'whatlinkshere-title' => 'Säiten, déi mat „$1“ verlinkt sinn',
'whatlinkshere-page'  => 'Säit:',
'linklistsub'         => '(Lëscht vun de Linken)',
'linkshere'           => "Déi folgend Säite linken op '''[[:$1]]''':",
'nolinkshere'         => "Keng Säit ass mat '''[[:$1]]''' verlinkt.",
'nolinkshere-ns'      => "Keng Säite linken op '''[[:$1]]''' am gewielten Nummraum.",
'isredirect'          => 'Redirectsäit',
'istemplate'          => 'Schablounen déi an dëser Säit integréiert sinn',
'whatlinkshere-prev'  => '{{PLURAL:$1|vireschten|virescht $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|nächsten|nächst $1}}',
'whatlinkshere-links' => '← Linken',

# Block/unblock
'blockip'                     => 'Benotzer blockéieren',
'blockiptext'                 => 'Benotzt dës Form fir eng spezifesch IP Adress oder e Benotzernumm ze blockéieren. Dëst soll nëmmen am Fall vu Vandalismus gemaach ginn, en accordance mat der [[Wikipedia:Vandalismus|Wikipedia Policy]]. Gitt e spezifesche Grond un (zum Beispill Säite wou Vandalismus virgefall ass).',
'ipaddress'                   => 'IP-Adress oder Benotzernamm:',
'ipbreason'                   => 'Grond:',
'ipbreasonotherlist'          => 'Anere Grond',
'ipbreason-dropdown'          => "*Heefeg Ursaache fir Benotzer ze spären:
**Bewosst falsch Informatiounen an een oder méi Säite gesat
**Ouni Grond Inhalt vun Säite geläscht
**Spam-Verknëppunge mat externe Säiten
**Topereien an d'Säite gesat
**Beleidegt oder bedréit aner Mataarbechter
**Mëssbrauch vu verschiddene Benotzernimm
**Net akzeptabele Benotzernumm",
'ipbsubmit'                   => 'Dës IP-Adress resp dëse Benotzer spären',
'ipboptions'                  => '1 Stonn:1 hour,2 Stonen:2 hours,6 Stonnen:6 hours,1 Dag:1 day,3 Deeg:3 days,1 Woch:1 week,2 Wochen:2 weeks,1 Mount:1 month,3 Méint:3 months,1 Joer:1 year,Onbegrenzt:infinite', # display1:time1,display2:time2,...
'badipaddress'                => "D'IP-Adress huet dat falscht Format.",
'blockipsuccesssub'           => 'Mat Succès gespaart',
'blockipsuccesstext'          => "[[Special:Contributions/$1|$1]] gouf gespaart. <br />

Kuckt d'[[Special:Ipblocklist|IP Spär-Lëscht]] fir all Spären ze gesin.",
'unblocked'                   => 'De(n) [[User:$1|$1]] gouf debloquéiert',
'unblocked-id'                => "D'gespäerten ID $1 gouf debloquéiert.",
'ipblocklist'                 => 'Lëscht vu gespaarte Benotzer an IP-Adressen',
'ipblocklist-legend'          => 'No engem gespaarte Benotzer sichen',
'ipblocklist-username'        => 'Benotzernumm oder IP-Adress:',
'ipblocklist-submit'          => 'Sichen',
'blocklistline'               => '$1, $2 blockéiert $3 (gülteg bis $4)',
'infiniteblock'               => 'onbegrenzt',
'expiringblock'               => 'bis $1',
'anononlyblock'               => 'nëmmen anonym Benotzer',
'createaccountblock'          => 'Opmaache vu Benotzerkonte gespaart',
'emailblock'                  => 'E-Maile schécke gespaart',
'blocklink'                   => 'spären',
'unblocklink'                 => 'entspären',
'contribslink'                => 'Kontributiounen',
'autoblocker'                 => 'Dir sidd autoblockéiert well dir eng IP Adress mam "$1" deelt. Grond "$2".',
'blocklogpage'                => 'Block Log',
'blocklogentry'               => '"[[$1]]" gespaart, gülteg bis $2 $3',
'blocklogtext'                => "Dëst ass e Log vu Blockéieren an Deblockéieren. Automatesch blockéiert IP Adresse sinn hei net opgelëscht. Kuckt d'[[Special:Ipblocklist|IP block list]] fir déi aktuell Blockagen.",
'block-log-flags-anononly'    => 'Nëmmen anonym Benotzer',
'block-log-flags-noautoblock' => 'Autoblock deaktivéiert',
'block-log-flags-noemail'     => 'E-Mail gespaart',
'range_block_disabled'        => 'Dem Administrateur seng Fähegkeet fir ganz Adressberäicher ze spären ass ausser Kraaft.',
'ipb_already_blocked'         => '„$1“ ass scho gespaart',
'proxyblocker-disabled'       => 'Dës Funktioun ass ausgeschalt.',
'sorbsreason'                 => 'Är IP Adress steet als oppene Proxy an der schwoarzer Lëscht (DNSBL) vu [http://www.sorbs.net SORBS].',
'sorbs_create_account_reason' => 'Är IP Adress steet als oppene Proxy an der schwoarzer Lëscht (DNSBL) vu [http://www.sorbs.net SORBS]. Där kënnt kee Kont opmaachen',

# Developer tools
'lockdb'            => 'Datebank spären',
'lockdbtext'        => "Wann d'Datebank gespaart ass, ka kee Benotzer méi Säiten änneren, seng Preferenzen änneren, seng Iwwerwaachungs-Lëscht änneren, an all aner Aarbecht, déi op d'Datebank zréckgräift. 

W.e.g. konfirméiert, datt dir dëst wierklech maache wëllt, an datt dir d'Spär ewechhuelt soubal d'Maintenance-Aarbechten eriwwer sinn.",
'lockconfirm'       => "Jo, ech wëll d'Datebank wierklech spären.",
'lockdbsuccesstext' => "D'{{SITENAME}}-Datebank gouf gespaart. <br />
Denkt drun [[Special:Unlockdb|d'Spär erëm ewechzehuele]] soubaal d'Maintenance-Aarbechte fäerdeg sinn.",

# Move page
'movepage'                => 'Säit réckelen',
'movepagetext'            => "Wann der folgende Formulaire benotzt, réckelt dir eng komplett Säit mat hirem Historique op en neien Numm. Den alen Titel déngt als Redirect op déi nei Säit. Linken op déi aal Säit ginn net ëmgeännert;  passt op datt keng duebel oder feelerhaft Redirecten am Spill sinn. Dir sidd responsabel datt d'Linke weiderhinn dohinner pointéieren, wou se hisollen.

Beuecht w.e.g. datt d'Säit '''net''' geréckelt gëtt, wann ët schonn eng Säit mat deem neien Titel gëtt, ausser dës ass eidel, e Redirect oder huet keen Historique. Dëst bedeit datt dir eng Säit kennt ëmbenennen an datt dir keng existent Säit iwwerschreiwe kënnt.

<b>OPGEPASST!</b> 
Dëst kann en drastesche Changement fir eng populär Säit bedeiten; verstitt w.e.g. d'Konsequenze vun ärer Handlung éier Dir d'Säit réckelt.",
'movearticle'             => 'Säit réckelen:',
'newtitle'                => 'Op neien Titel:',
'move-watch'              => 'Dës Säit iwwerwaachen',
'movepagebtn'             => 'Säit réckelen',
'pagemovedsub'            => 'Mat Succès geréckelt',
'movepage-moved'          => "<big>'''D'Säit \"\$1\" gouf op \"\$2\" geréckelt.'''</big>", # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'Eng Säit mat dësem Numm gëtt et schonns, oder den Numm deen Dir gewielt hutt gëtt net akzeptéiert. Wielt w.e.g. en aneren Numm.',
'talkexists'              => "D'Säit selwer gouf erfollegräich geréckelt, mee d'Diskussiounssäit konnt net mat eriwwergeholl gi well et schonns eng ënnert deem neien Titel gëtt. W.e.g. setzt dës manuell zesummen.",
'movedto'                 => 'geréckelt op',
'movetalk'                => "D'Diskussiounssäit matréckelen, wa méiglich.",
'talkpagemoved'           => "D'Diskussiounssäit gouf mat eriwwergeholl.",
'talkpagenotmoved'        => "D'Diskussiounssäit gouf <strong>net</strong> mat eriwwergeholl.",
'1movedto2'               => '[[$1]] gouf op [[$2]] geréckelt',
'1movedto2_redir'         => '[[$1]] gouf op [[$2]] geréckelt, dobäi gouf eng Weiderleedung iwwerschriwwen.',
'movelogpage'             => 'Réckel-Logbuch',
'movereason'              => 'Grond:',
'revertmove'              => 'zréck réckelen',
'delete_and_move'         => 'Läschen a réckelen',
'delete_and_move_text'    => '== Läsche vun der Destinatiounssäit néideg == D\'Säit "[[$1]]" existéiert schonn. Wëll der se läsche fir d\'Réckelen ze erméiglechen?',
'delete_and_move_confirm' => "Jo, läsch d'Destinatiounssäit",
'delete_and_move_reason'  => 'Geläscht fir Plaz ze maache fir eng Säit heihin ze réckelen',

# Export
'export'            => 'Säiten exportéieren',
'exporttext'        => "Dir kënnt den Text an den Historique vun enger bestëmmter Säit, oder engem Set vu Säiten, an XML agepakt, exportéieren. An Zukunft kann dat dann an eng aner Wiki mat MediaWiki Software agedroë ginn, mee dat gëtt mat der aktueller Versioun nach net ënnerstëtzt. Fir eng Säit z'exportéieren, gitt den Titel an d'Textkëscht heidrënner an, een Titel pro Linn, a wielt aus op Dir nëmmen déi aktuell Versioun oder all Versioune mam ganzen Historique exportéiere wëllt. Wann nëmmen déi aktuell Versioun exportéiert soll ginn, kënnt Dir och e Link benotze wéi z.B [[{{ns:special}}:Export/{{Mediawiki:Mainpage}}]] fir d'[[{{Mediawiki:Mainpage}}]].",
'exportcuronly'     => 'Nëmmen déi aktuell Versioun exportéieren an net de ganzen Historique',
'exportnohistory'   => "----
'''Hiwäis:''' Den Export vu komplette Versiounshistoriquen ass aus Performancegrënn bis op weideres net méiglech.",
'export-submit'     => 'Exportéieren',
'export-addcattext' => 'Säiten aus Kategorie derbäisetzen:',
'export-addcat'     => 'Derbäisetzen',
'export-download'   => 'Als XML-Datei späicheren',

# Namespace 8 related
'allmessages'               => 'All Systemmessagen',
'allmessagesname'           => 'Numm',
'allmessagesdefault'        => 'Standardtext',
'allmessagescurrent'        => 'Aktuellen Text',
'allmessagestext'           => "Dëst ass eng Lëscht vun alle '''Messagen am MediaWiki:namespace''', déi vun der MediaWiki-Software benotzt ginn. Si kënnen nëmme vun [[Wikipedia:Administrators|Administrateure]] geännert ginn.",
'allmessagesnotsupportedDB' => "'''Special:AllMessages''' gëtt den Ament net ënnertstëtzt well d'Datebank ''offline'' ass.",
'allmessagesfilter'         => 'Noriichtennummfilter:',
'allmessagesmodified'       => 'Nëmme geännerter weisen',

# Thumbnails
'thumbnail-more'  => 'vergréisseren',
'filemissing'     => 'Fichier feelt',
'djvu_page_error' => 'DjVu-Säit baussent dem Säiteberäich',

# Special:Import
'import-interwiki-history' => "Importéier all d'Versioune vun dëser Säit",
'import-interwiki-submit'  => 'Import',
'importstart'              => 'Importéier Säiten …',
'import-revision-count'    => '$1 {{PLURAL:$1|Versioun|Versiounen}}',
'importunknownsource'      => 'Onbekannt Importquell',
'importbadinterwiki'       => 'Falschen Interwiki-Link',
'importnotext'             => 'Eidel oder keen Text',

# Import log
'importlogpage'                 => 'Import-Logbuch',
'import-logentry-upload-detail' => '$1 {{PLURAL:$1|Versioun|Versiounen}}',
'import-logentry-interwiki'     => 'huet $1 importéiert (Transwiki)',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Meng Benotzersäit',
'tooltip-pt-mytalk'               => 'Meng Diskussioun',
'tooltip-pt-anontalk'             => "Diskussioun iwwer d'Ännerungen déi vun dëser IP-Adress aus gemaach gi sinn",
'tooltip-pt-preferences'          => 'Meng Preferenzen',
'tooltip-pt-watchlist'            => 'Lëscht vu Säiten, bei deenen Der op Ännerungen oppasst',
'tooltip-pt-mycontris'            => 'Lëscht vu menge Kontributiounen',
'tooltip-pt-login'                => 'Sech unzemellen gëtt gäre gesinn, Dir musst et awer net maachen.',
'tooltip-pt-anonlogin'            => 'Et wier gutt, Dir géift Iech aloggen, och wann et keng Musse-Saach ass.',
'tooltip-pt-logout'               => 'Ofmellen',
'tooltip-ca-talk'                 => 'Diskussioun iwwert de Säiteninhalt',
'tooltip-ca-edit'                 => 'Dës Säit ka geännert ginn. Maacht vum Preview Gebrauch a kuckt ob alles an der Rei ass ier der ofspäichert.',
'tooltip-ca-addsection'           => 'Eng Bemierkung bäi dës Diskussioun derbäisetzen.',
'tooltip-ca-viewsource'           => 'Dës Säit ass protegéiert. Nëmmen de Quelltext ka gewise ginn.',
'tooltip-ca-history'              => 'Virescht Versioune vun dëser Säit',
'tooltip-ca-protect'              => 'Dës Säit protegéieren',
'tooltip-ca-delete'               => 'Dës Säit läschen',
'tooltip-ca-move'                 => 'Dës Säit réckelen',
'tooltip-ca-watch'                => 'Dës Säit op är Iwwerwaachungs-Lëscht bäisetzen',
'tooltip-ca-unwatch'              => 'Dës Säit vun der Iwwerwaachungs-Lëscht erofhuelen',
'tooltip-search'                  => 'Op {{SITENAME}} sichen',
'tooltip-n-mainpage'              => 'Eis Entréesdier',
'tooltip-n-portal'                => 'Iwwer de Portal, wat Dir maache kënnt, wou wat ze fannen ass',
'tooltip-n-currentevents'         => "D'Aktualitéit a wat dohannert ass",
'tooltip-n-recentchanges'         => 'Lëscht vun de rezenten Ännerungen op {{SITENAME}}.',
'tooltip-n-randompage'            => 'Zoufälleg Säit',
'tooltip-n-help'                  => 'Hëllefsäiten weisen.',
'tooltip-n-sitesupport'           => 'Ënnerstetzt eis',
'tooltip-t-whatlinkshere'         => 'Lëscht vun alle Säiten, déi heihi linken',
'tooltip-feed-rss'                => 'RSS-Feed fir dës Säit',
'tooltip-feed-atom'               => 'Atom-Feed fir dës Säit',
'tooltip-t-contributions'         => 'Lëscht vun de Kontributiounen vun dësem Benotzer',
'tooltip-t-emailuser'             => 'Dësem Benotzer eng E-Mail schécken',
'tooltip-t-upload'                => 'Biller oder Medie-Fichierën eroplueden',
'tooltip-t-specialpages'          => 'Lëscht vun alle Spezialsäiten',
'tooltip-ca-nstab-main'           => 'Contenu vun der Säit weisen',
'tooltip-ca-nstab-user'           => 'Benotzersäit weisen',
'tooltip-ca-nstab-media'          => 'Mediesäit weisen',
'tooltip-ca-nstab-special'        => 'Dëst ass eng Spezialsäit. Si kann net geännert ginn.',
'tooltip-ca-nstab-project'        => 'Portalsäit weisen',
'tooltip-ca-nstab-image'          => 'Billersäit weisen',
'tooltip-ca-nstab-mediawiki'      => 'Systemmessage weisen',
'tooltip-ca-nstab-template'       => 'Schabloun weisen',
'tooltip-ca-nstab-help'           => 'Hëllefesäite weisen',
'tooltip-ca-nstab-category'       => 'Kategoriesäit weisen',
'tooltip-minoredit'               => 'Dës Ännerung als kleng markéieren.',
'tooltip-save'                    => 'Ännerungen späicheren',
'tooltip-preview'                 => "Klickt op 'Preview' éier Der späichert!",
'tooltip-diff'                    => 'Weis wéi eng Ännerungen der beim Text gemaach hutt.',
'tooltip-compareselectedversions' => "D'Ennerscheeder op dëser Säit tëscht den zwou gewielte Versioune weisen.",
'tooltip-watch'                   => 'Dës Säit op är Iwwerwaachungs-Lëscht bäisetzen',

# Attribution
'anonymous'   => 'Anonym(e) Benotzer op {{SITENAME}}',
'siteuser'    => '{{SITENAME}}-Benotzer $1',
'siteusers'   => '{{SITENAME}}-Benotzer $1',
'creditspage' => 'Quellen',

# Spam protection
'spamprotectiontitle'  => 'Spamfilter',
'spamprotectiontext'   => "D'Säit déi dir späichere wollt gouf vum Spamfilter blockéiert. Dëst wahrscheinlech duerch en externe Link.",
'subcategorycount'     => 'Fir dës Kategorie {{PLURAL:$1|gëtt et $1 Souskategorie| ginn et $1 Souskategorien}}.',
'categoryarticlecount' => 'An dëser Kategorie {{PLURAL:$1|gëtt et bis ewell 1 Säit|ginn et bis ewell $1 Säiten}}.',
'category-media-count' => 'Et {{PLURAL:$1|gëtt eng Datei|ginn $1 Dateien}} an dëser Kategorie',

# Math options
'mw_math_html' => 'Wa méiglech als HTML duerstellen, soss PNG',

# Patrolling
'markedaspatrollederrortext' => 'Dir musst eng Säitenännerung auswielen.',

# Patrol log
'patrol-log-auto' => '(automatesch)',

# Image deletion
'deletedrevision'       => 'Al Revisioun $1 läschen',
'filedeleteerror-short' => 'Feeler beim Läsche vum Fichier: $1',

# Browsing diffs
'previousdiff' => '← virescht Ënnerscheeder',
'nextdiff'     => 'Nächsten Ënnerscheed →',

# Media information
'imagemaxsize'         => 'Biller op de Billerbeschreiwungssäite limitéieren op:',
'thumbsize'            => 'Gréisst vun de Thumbnails:',
'widthheightpage'      => '$1×$2, $3 Säiten',
'file-info'            => '(Dateigréisst: $1, MIME-Typ: $2)',
'file-info-size'       => '($1 × $2 Pixel, Dateigréisst: $3, MIME-Typ: $4)',
'file-nohires'         => '<small>Et gëtt keng méi eng héich Opléisung.</small>',
'svg-long-desc'        => '(SVG-Fichier, Basisgréisst: $1 × $2 Pixel, Gréisst vum Fichier: $3)',
'show-big-image'       => 'Versioun an enger méi héijer Opléisung',
'show-big-image-thumb' => '<small>Gréisst vun dem Thumbnail: $1 × $2 Pixel</small>',

# Metadata
'metadata'          => 'Metadaten',
'metadata-expand'   => 'Weis detailléiert Informatiounen',
'metadata-collapse' => 'Verstopp detailléiert Informatiounen',

# EXIF tags
'exif-imagewidth'                  => 'Breet',
'exif-imagelength'                 => 'Längt',
'exif-bitspersample'               => 'Biten pro Farfkomponent',
'exif-compression'                 => 'Aart vun der Kompressioun',
'exif-photometricinterpretation'   => 'Pixelzesummesetzung',
'exif-orientation'                 => 'Kameraausriichtung',
'exif-samplesperpixel'             => 'Zuel vun de Komponenten',
'exif-planarconfiguration'         => 'Datenausriichtung',
'exif-ycbcrpositioning'            => 'Y an C Positionéirung',
'exif-xresolution'                 => 'Horizontal Opléisung',
'exif-yresolution'                 => 'Vertikal Opléisung',
'exif-resolutionunit'              => 'Moosseenheet vun der Opléisung',
'exif-jpeginterchangeformatlength' => 'Gréisst vun de JPEG-Daten a Byten',
'exif-whitepoint'                  => 'Manuell mat Miessung',
'exif-referenceblackwhite'         => 'Schwoarz/Wäiss-Referenzpunkten',
'exif-datetime'                    => 'Späicherzäitpunkt',
'exif-make'                        => 'Fabrikant',
'exif-copyright'                   => "Droits d'auteur",
'exif-exifversion'                 => 'Exif-Versioun',
'exif-colorspace'                  => 'Faarfraum',
'exif-componentsconfiguration'     => 'Bedeitung vun eenzelne Komponenten',
'exif-compressedbitsperpixel'      => 'Kompriméiert Biten pro Pixel',
'exif-datetimeoriginal'            => 'Erfaassungszäitpunkt',
'exif-datetimedigitized'           => 'Digitaliséierungszäitpunkt',
'exif-subsectime'                  => 'Späicherzäitpunkt (1/100 s)',
'exif-subsectimedigitized'         => 'Digitaliséirungszäitpunkt (1/100 s)',
'exif-exposuretime'                => 'Beliichtungsdauer',
'exif-exposuretime-format'         => '$1 Sekonnen ($2)',
'exif-fnumber'                     => 'Blend',
'exif-exposureprogram'             => 'Beliichtungsprogramm',
'exif-isospeedratings'             => 'Film- oder Sensorempfindlechkeet (ISO)',
'exif-shutterspeedvalue'           => 'Beliichtungszäitwäert',
'exif-aperturevalue'               => 'Blendewäert',
'exif-brightnessvalue'             => 'Hellegkeetswäert',
'exif-lightsource'                 => 'Liichtquell',
'exif-flash'                       => 'Blëtz',
'exif-focallength'                 => 'Brennwäit',
'exif-subjectarea'                 => 'Beräich',
'exif-flashenergy'                 => 'Blëtzstäerkt',
'exif-focalplanexresolution'       => 'Sensoropléisung horizontal',
'exif-focalplaneyresolution'       => 'Sensoropléisung vertikal',
'exif-focalplaneresolutionunit'    => 'Eenheet vun der Sensoropléisung',
'exif-subjectlocation'             => 'Motivstanduert',
'exif-exposureindex'               => 'Beliichtungsindex',
'exif-exposuremode'                => 'Beliichtungsmodus',
'exif-whitebalance'                => 'Wäissofgläich',
'exif-focallengthin35mmfilm'       => 'Brennwäit (Klengbildäquivalent)',
'exif-scenecapturetype'            => 'Opnameaart',
'exif-gaincontrol'                 => 'Verstäerkung',
'exif-sharpness'                   => 'Schäerft',
'exif-subjectdistancerange'        => 'Motivdistanz',
'exif-gpsaltitude'                 => 'Héicht',
'exif-gpstimestamp'                => 'GPS-Zäit',
'exif-gpsdestlatituderef'          => "Referenz fir d'Breet",
'exif-gpsdestlatitude'             => 'Breet',
'exif-gpsdestlongituderef'         => "Referenz fir d'Längt",
'exif-gpsdestlongitude'            => 'Längt',
'exif-gpsdestdistance'             => 'Motivdistanz',
'exif-gpsdatestamp'                => 'GPS-Datum',

# EXIF attributes
'exif-compression-1' => 'Onkompriméiert',

'exif-unknowndate' => 'Onbekannten Datum',

'exif-orientation-2' => 'Horizontal gedréit', # 0th row: top; 0th column: right
'exif-orientation-3' => 'Ëm 180° gedréit', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Vertikal gedréit', # 0th row: bottom; 0th column: left

'exif-componentsconfiguration-0' => 'Gëtt et net',

'exif-exposureprogram-0' => 'Onbekannt',
'exif-exposureprogram-3' => 'Zäitautomatik',
'exif-exposureprogram-8' => 'Landschaftsopnamen',

'exif-meteringmode-0'   => 'Onbekannt',
'exif-meteringmode-1'   => 'Duerchschnëttlech',
'exif-meteringmode-3'   => 'Spotmiessung',
'exif-meteringmode-4'   => 'Méifachspotmiessung',
'exif-meteringmode-6'   => 'Bilddeel',
'exif-meteringmode-255' => 'Onbekannt',

'exif-lightsource-0'  => 'Onbekannt',
'exif-lightsource-1'  => 'Dageslut',
'exif-lightsource-4'  => 'Blëtz',
'exif-lightsource-9'  => 'Schéint Wieder',
'exif-lightsource-10' => 'Wollekeg',
'exif-lightsource-11' => 'Schiet',

'exif-sensingmethod-1' => 'Ondefinéiert',
'exif-sensingmethod-2' => 'Een-Chip-Farfsensor',
'exif-sensingmethod-3' => 'Zwee-Chip-Farfsensor',
'exif-sensingmethod-4' => 'Dräi-Chip-Farfsensor',
'exif-sensingmethod-7' => 'Trilineare Sensor',

'exif-exposuremode-0' => 'Automatesch Beliichtung',
'exif-exposuremode-1' => 'Manuell Beliichtung',
'exif-exposuremode-2' => 'Beliichtungsserie',

'exif-whitebalance-0' => 'Automatesche Wäissofgläich',

'exif-scenecapturetype-3' => 'Nuetszeen',

'exif-gaincontrol-0' => 'Keng',

'exif-contrast-1' => 'Schwaach',
'exif-contrast-2' => 'Stark',

'exif-saturation-2' => 'Héich',

'exif-sharpness-2' => 'Stark',

'exif-subjectdistancerange-0' => 'Onbekannt',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'No',
'exif-subjectdistancerange-3' => 'wäit ewech',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-n' => 'Kniet',

# External editor support
'edit-externally'      => 'Dëse Fichier mat engem externe Programm veränneren',
'edit-externally-help' => "<small>Fir gewuer ze gi wéi dat genee geet liest d'[http://meta.wikimedia.org/wiki/Help:External_editors Installatiounsinstruktiounen].</small>",

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'all',
'imagelistall'     => 'alleguerten',
'watchlistall2'    => 'all',
'namespacesall'    => 'all',
'monthsall'        => 'all',

# E-mail address confirmation
'confirmemail'            => 'Email-Adress bestätegen',
'confirmemail_text'       => "Ier der d'Email-Funktioune vun der {{SITENAME}} notze kënnt musst der als éischt är Email-Adress bestätegen. Dréckt w.e.g. de Knäppchen hei ënnendrënner fir eng Confirmatiouns-Email op déi Adress ze schécken déi der uginn hutt. An däer Email steet e Link mat engem Code, deen der dann an ärem Browser opmaache musst fir esou ze bestätegen, datt är Adress och wierklech existéiert a valabel ass.",
'confirmemail_send'       => 'Confirmatiouns-Email schécken',
'confirmemail_sent'       => 'Confirmatiouns-Email gouf geschéckt.',
'confirmemail_sendfailed' => "D'Confirmatiouns-Email konnt net verschéckt ginn. Iwwerpréift w.e.g. är Adress op keng ongëlteg Zeechen dran enthale sinn.

Feelermeldung vum Mailserver: $1",
'confirmemail_invalid'    => "Ongëltege Confirmatiounscode. Eventuell ass d'Gëltegkeetsdauer vum Code ofgelaf.",
'confirmemail_success'    => 'Är Email Address gouf konfirméiert. Där kënnt iech elo aloggen an a vollem Ëmfang vun der Wiki profitéiren.',
'confirmemail_loggedin'   => 'Är Email-Adress gouf elo confirméiert.',
'confirmemail_error'      => 'Et ass eppes falsch gelaf bäim Späichere vun ärer Confirmatioun.',
'confirmemail_subject'    => '{{SITENAME}} Email-Adress-Confirmatioun',
'confirmemail_body'       => 'E User, waarscheinlech där selwer, huet mat der IP Adress $1 de Benotzerkont "$2" um Site {{SITENAME}} opgemaach. Fir ze bestätegen, datt dee Kont iech wierklech gehéiert a fir d\'Email-Funktiounen um Site {{SITENAME}} z\'aktivéieren, maacht w.e.g. de folgende Link an ärem Browser op: $3 Sollt et sech net ëm äre Benotzerkont handelen, da maacht de Link *net* op. De Confirmatiounscode gëtt den $4 ongëlteg.',

# Delete conflict
'deletedwhileediting' => 'Opgepasst: Dës Säit gouf geläscht nodeems datt der ugefaangen hutt se ze änneren!',
'confirmrecreate'     => "De Benotzer [[User:$1|$1]] ([[User talk:$1|Diskussioun]]) huet dës Säit geläscht, nodeems datt där ugefaangen hutt drun ze schaffen. D'Begrënnung war: ''$2'' Bestätegt w.e.g., datt Dir dës Säit wierklich erëm nei opmaache wëllt.",

# action=purge
'confirm_purge' => 'Dës Säit aus dem Server-Cache läschen? 

$1',

# AJAX search
'searchcontaining' => "No Säite siche mat ''$1''.",
'searchnamed'      => "Sich no Säiten, an deenen hirem Numm ''$1'' virkënnt.",
'articletitles'    => "Säiten déi mat ''$1'' ufänken",
'hideresults'      => 'Verstopp',

# Multipage image navigation
'imgmultipageprev' => '← virëscht Säit',
'imgmultipagenext' => 'nächst Säit →',
'imgmultigotopre'  => "Géi op d'Säit",

# Table pager
'ascending_abbrev'         => 'erop',
'descending_abbrev'        => 'erof',
'table_pager_next'         => 'Nächst Säit',
'table_pager_prev'         => 'Virescht Säit',
'table_pager_first'        => 'Éischt Säit',
'table_pager_last'         => 'Lescht Säit',
'table_pager_limit'        => '$1 Objete pro Säit weisen',
'table_pager_limit_submit' => 'Lass',
'table_pager_empty'        => 'Keng Resultater',

# Auto-summaries
'autosumm-blank'   => 'All Inhalt vun der Säit gëtt geläscht',
'autosumm-replace' => "Säit gëtt ersat duerch '$1'",
'autoredircomment' => 'Virugeleet op [[$1]]',
'autosumm-new'     => 'Nei Säit: $1',

# Live preview
'livepreview-loading' => 'Lueden …',
'livepreview-ready'   => 'Lueden … Fäerdeg!',

# Watchlist editor
'watchlistedit-numitems'       => "Op Ärer Suivi-Lëscht {{PLURAL:$1|steet 1 Säit|stinn $1 Säiten}}, ouni d'Diskussiounssäiten.",
'watchlistedit-noitems'        => 'Är Suivi-Lëscht ass eidel.',
'watchlistedit-normal-title'   => 'Suivi-Lëscht änneren',
'watchlistedit-normal-legend'  => 'Säite vun der Suivi-Lëscht erofhuelen',
'watchlistedit-normal-explain' => 'D\'Säite vun äerer Suivi-Lëscht ginn ënnendrenner gewisen. Fir eng Säit erofzehuelen, klickt op d\'Haischen niewen drunn a klickt dono op "Säiten erofhuelen". Dir kënnt och [[Special:Watchlist/raw|déi net formatéiert Suivi-Lëscht veränneren]], oder [[Special:Watchlist/clear|äer Suivi-Lëscht eidel maachen]].',
'watchlistedit-normal-submit'  => 'Säiten erofhuelen',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 Säit gouf|$1 Säite goufen}} vun ärer Suivi-Lëscht erofgeholl:',
'watchlistedit-raw-title'      => 'Suivi-Lëscht onformatéiert änneren',
'watchlistedit-raw-explain'    => "D'Säite vun äerer Suivi-Lëscht ginn ënnendrenner gewisen a kënne geännert ginn andeems der d'Säiten op d'Lëscht derbäisetzt oder erofhuelt; eng Säit pro Linn. Wann Dir fäerdeg sidd, klickt Suivi-Lëscht à jour setzen. Dir kënnt och [[Special:Watchlist/edit|de Standard Editeur benotzen]].",
'watchlistedit-raw-titles'     => 'Säiten:',
'watchlistedit-raw-submit'     => 'Suivi-Lëscht à jour setzen',
'watchlistedit-raw-done'       => 'Är Iwwerwaachungs-Lëscht gouf gespäichert.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 Säit gouf|$1 Säite goufen}} derbäigesat:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 Säit gouf|$1 Säite goufen}} erausgeholl:',

# Watchlist editing tools
'watchlisttools-view' => 'Iwwerwaachungs-Lëscht: Ännerungen',
'watchlisttools-edit' => 'Iwwerwaachungs-Lëscht weisen an änneren',
'watchlisttools-raw'  => 'Net-formatéiert Iwwerwaachungslëscht änneren',

);

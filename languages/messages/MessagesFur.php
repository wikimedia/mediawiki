<?php
/** Friulian (Furlan)
 *
 * @addtogroup Language
 */
 
$fallback = 'it';

$skinNames = array(
	'nostalgia' => 'Nostalgie',
);
$namespaceNames = array(
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => 'Speciâl',
	NS_MAIN           => '',
	NS_TALK           => 'Discussion',
	NS_USER           => 'Utent',
	NS_USER_TALK      => 'Discussion_utent',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => 'Discussion_$1',
	NS_IMAGE          => 'Figure',
	NS_IMAGE_TALK     => 'Discussion_figure',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'Discussion_MediaWiki',
	NS_TEMPLATE       => 'Model',
	NS_TEMPLATE_TALK  => 'Discussion_model',
	NS_HELP	          => 'Jutori',
	NS_HELP_TALK      => 'Discussion_jutori',
	NS_CATEGORY       => 'Categorie',
	NS_CATEGORY_TALK  => 'Discussion_categorie'
);

$datePreferences = false;
$defaultDateFormat = 'dmy';
$dateFormats = array(
	'dmy time' => 'H:i',
	'dmy date' => 'j "di" M Y',
	'dmy both' => 'j "di" M Y "a lis" H:i',
);

$separatorTransformTable = array(',' => "\xc2\xa0", '.' => ',' );

$messages = array(
# User preference toggles
'tog-underline'               => 'Sotlinee leams',
'tog-highlightbroken'         => 'Mostre leams sbaliâts <a href="" class="new">cussì</a> (invezit di cussì<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Justifiche paragraf',
'tog-hideminor'               => 'Plate lis piçulis modifichis tai ultins cambiaments',
'tog-usenewrc'                => 'Ultins cambiaments avanzâts (JavaScript)',
'tog-numberheadings'          => 'Numerazion automatiche dai titui',
'tog-showtoolbar'             => 'Mostre sbare dai imprescj pe modifiche (JavaScript)',
'tog-editondblclick'          => 'Cambie lis pagjinis fracant dôs voltis (JavaScript)',
'tog-editsection'             => 'Inserìs un leam [cambie] pe editazion veloç di une sezion',
'tog-editsectiononrightclick' => 'Modifiche une sezion fracant cul tast diestri<br /> sui titui des sezions (JavaScript)',
'tog-showtoc'                 => 'Mostre indis (par pagjinis cun plui di 3 sezions)',
'tog-rememberpassword'        => 'Visiti tes prossimis sessions',
'tog-editwidth'               => 'Il spazi pe modifiche al è larc il plui pussibil',
'tog-watchdefault'            => 'Zonte in automatic lis pagjinis che o cambii inte liste di chês tignudis di voli',
'tog-minordefault'            => 'Imposte come opzion predeterminade ducj i cambiaments come piçui',
'tog-previewontop'            => 'Mostre anteprime parsore dal spazi pe modifiche',
'tog-previewonfirst'          => 'Mostre anteprime te prime modifiche',
'tog-nocache'                 => 'No stâ tignî in memorie (caching) lis pagjinis',
'tog-enotifwatchlistpages'    => 'Mandimi une email se la pagjine e gambie',
'tog-enotifusertalkpages'     => 'Mandimi une email cuant che la mê pagjine di discussion e gambie',
'tog-enotifminoredits'        => 'Mandimi une email ancje pai piçui cambiaments ae pagjine',
'tog-enotifrevealaddr'        => 'Distapone fûr il gno recapit email tai messaçs di notifiche',
'tog-shownumberswatching'     => 'Mostre il numar di utents che a stan tignint di voli',
'tog-fancysig'                => 'Firmis crudis (cence leam automatic)',
'tog-externaleditor'          => 'Dopre editôr esterni come opzion predeterminade',
'tog-externaldiff'            => 'Dopre editôr difarencis esterni come opzion predeterminade',

'underline-always'  => 'Simpri',
'underline-never'   => 'Mai',
'underline-default' => 'Predeterminât dal sgarfadôr',

'skinpreview' => '(Anteprime)',

# Dates
'sunday'    => 'Domenie',
'monday'    => 'Lunis',
'tuesday'   => 'Martars',
'wednesday' => 'Miercus',
'thursday'  => 'Joibe',
'friday'    => 'Vinars',
'saturday'  => 'Sabide',
'january'   => 'Zenâr',
'february'  => 'Fevrâr',
'march'     => 'Març',
'april'     => 'Avrîl',
'may_long'  => 'Mai',
'june'      => 'Jugn',
'july'      => 'Lui',
'august'    => 'Avost',
'september' => 'Setembar',
'october'   => 'Otubar',
'november'  => 'Novembar',
'december'  => 'Dicembar',
'jan'       => 'Zen',
'feb'       => 'Fev',
'apr'       => 'Avr',
'may'       => 'Mai',
'jun'       => 'Jug',
'jul'       => 'Lui',
'aug'       => 'Avo',
'sep'       => 'Set',
'oct'       => 'Otu',
'nov'       => 'Nov',
'dec'       => 'Dic',

# Bits of text used by many pages
'categories'      => 'Categoriis',
'pagecategories'  => 'Categoriis',
'category_header' => 'Vôs inte categorie "$1"',
'subcategories'   => 'Sot categoriis',

'mainpagetext' => "'''MediaWiki e je stade instalade cun sucès.'''",

'about'          => 'Informazions',
'newwindow'      => '(al vierç un gnûf barcon)',
'cancel'         => 'Scancele',
'qbfind'         => 'Cjate',
'qbbrowse'       => 'Sgarfe',
'qbedit'         => 'Cambie',
'qbpageoptions'  => 'Cheste pagjine',
'qbpageinfo'     => 'Contest',
'qbmyoptions'    => 'Mês pagjinis',
'qbspecialpages' => 'Pagjinis speciâls',
'moredotdotdot'  => 'Plui...',
'mypage'         => 'Mê pagjine',
'mytalk'         => 'Mês discussions',
'anontalk'       => 'Discussion par chest IP',
'navigation'     => 'somari',

'errorpagetitle'   => 'Erôr',
'returnto'         => 'Torne a $1.',
'tagline'          => 'Di {{SITENAME}}',
'help'             => 'Jutori',
'search'           => 'Cîr',
'searchbutton'     => 'Cîr',
'go'               => 'Va',
'searcharticle'    => 'Va',
'history'          => 'Storic de pagjine',
'history_short'    => 'Storic',
'updatedmarker'    => 'inzornât de mê ultime visite',
'info_short'       => 'Informazions',
'printableversion' => 'Version stampabil',
'permalink'        => 'Leam permanent',
'edit'             => 'Cambie',
'editthispage'     => 'Cambie cheste pagjine',
'delete'           => 'Elimine',
'deletethispage'   => 'Elimine cheste pagjine',
'undelete_short'   => 'Recupere $1 modifichis eliminadis',
'protect'          => 'Protêç',
'protectthispage'  => 'Protêç cheste pagjine',
'newpage'          => 'Gnove pagjine',
'talkpage'         => 'Fevelin di cheste pagjine',
'specialpage'      => 'Pagjine speciâl',
'personaltools'    => 'Imprescj personâi',
'postcomment'      => 'Zonte un coment',
'talk'             => 'Discussion',
'toolbox'          => 'imprescj',
'userpage'         => 'Cjale pagjine dal utent',
'projectpage'      => 'Cjale pagjine dal progjet',
'imagepage'        => 'Cjale pagjine de figure',
'otherlanguages'   => 'Altris lenghis',
'redirectedfrom'   => '(Inviât ca di $1)',
'redirectpagesub'  => 'Pagjine di redirezion',
'lastmodifiedat'   => "Cambiât par l'ultime volte ai $2, $1", # $1 date, $2 time
'viewcount'        => 'Cheste pagjine e je stade viodude $1 voltis.',
'protectedpage'    => 'Pagjine protezude',
'jumpto'           => 'Va a:',
'jumptonavigation' => 'navigazion',
'jumptosearch'     => 'ricercje',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Informazions su {{SITENAME}}',
'copyright'         => 'Il contignût al è disponibil sot de $1',
'currentevents'     => 'Lis gnovis',
'currentevents-url' => 'Lis gnovis',
'disclaimers'       => 'Avîs legâi',
'edithelp'          => 'Jutori pai cambiaments',
'edithelppage'      => 'Help:Cambiaments',
'helppage'          => 'Help:Contignûts',
'mainpage'          => 'Pagjine principâl',
'portal'            => 'Ostarie',
'portal-url'        => '{{ns:project}}:Ostarie',
'privacy'           => 'Politiche pe privacy',
'privacypage'       => 'Project:Politiche_pe_privacy',
'sitesupport'       => 'Doninus',
'sitesupport-url'   => 'Project:Supuarte il sît',

'ok'                  => 'OK',
'retrievedfrom'       => 'Cjapât fûr di $1',
'youhavenewmessages'  => 'Tu âs $1 ($2).',
'newmessageslink'     => 'gnûfs messaçs',
'newmessagesdifflink' => 'difarencis cu la penultime revision',
'editsection'         => 'cambie',
'editold'             => 'cambie',
'toc'                 => 'Indis',
'showtoc'             => 'mostre',
'hidetoc'             => 'plate',
'thisisdeleted'       => 'Vuelistu cjalâ o ripristinâ $1?',
'viewdeleted'         => 'Vuelistu viodi $1?',
'restorelink'         => '$1 modifichis eliminadis',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Vôs',
'nstab-user'      => 'Pagjine dal utent',
'nstab-media'     => 'Media',
'nstab-special'   => 'Speciâl',
'nstab-project'   => 'Informazions',
'nstab-image'     => 'Figure',
'nstab-mediawiki' => 'Messaç',
'nstab-template'  => 'Model',
'nstab-help'      => 'Jutori',
'nstab-category'  => 'Categorie',

# General errors
'error'        => 'Erôr',
'noconnect'    => 'Nus displâs, ma il sît al à al moment cualchi dificoltât tecniche e nol pues conetisi al servidôr de base di dâts. <br />$1',
'nodb'         => 'No si pues selezionâ la base di dâts $1',
'readonlytext' => "La base di dâts pal moment e je blocade e no si puedin zontâ vôs e fâ modifichis, probabilmentri pe normâl manutenzion de base di dâts, daspò de cuâl dut al tornarà normâl.

L'aministradôr ch'al à metût il bloc al à scrit cheste motivazion: $1",
'filenotfound' => 'No si pues cjatâ il file "$1".',
'viewsource'   => 'Cjale risultive',

# Login and logout pages
'logouttitle'           => 'Jessude dal utent',
'logouttext'            => '<strong>Tu sâs cumò lât fûr.</strong><br />Tu puedis continuâ a doprâ {{SITENAME}} come anonim, o tu puedis jentrâ cul stes o cuntun altri non utent. Note che cualchi pagjine e pues mostrâti ancjemò come jentrât tal sît fin cuant che no tu netis la cache dal sgarfadôr.',
'welcomecreation'       => '== Mandi e benvignût $1! ==

La tô identitât e je stade creade. No stâ dismenteâti di gambiâ lis preferencis di {{SITENAME}}.',
'loginpagetitle'        => 'Jentrade dal utent',
'yourname'              => 'Non utent',
'yourpassword'          => 'Peraule clâf',
'yourpasswordagain'     => 'Torne a scrivile',
'remembermypassword'    => 'Visiti di me',
'yourdomainname'        => 'Il to domini',
'alreadyloggedin'       => '<strong>Utent $1, tu sês za jentrât!</strong><br />',
'login'                 => 'Jentre',
'loginprompt'           => 'Tu âs di vê abilitâts i cookies par jentrâ in {{SITENAME}}.',
'userlogin'             => 'Regjistriti o jentre',
'logout'                => 'Jes',
'userlogout'            => 'Jes',
'nologin'               => 'No tu âs ancjemò une identitât par jentrâ? $1.',
'nologinlink'           => 'Creile cumò',
'createaccount'         => 'Cree une gnove identitât',
'gotaccount'            => 'Âstu za une identitât? $1.',
'gotaccountlink'        => 'Jentre',
'createaccountmail'     => 'par pueste eletroniche',
'badretype'             => 'Lis peraulis clâfs inseridis no son compagnis.',
'userexists'            => 'Il non utent inserît al è za doprât. Sielç par plasê un non diferent.',
'youremail'             => 'Email *',
'uid'                   => 'ID utent:',
'yourrealname'          => 'Non vêr *',
'yourlanguage'          => 'Lenghe di mostrâ',
'yourvariant'           => 'Varietât',
'badsig'                => 'Firme crude invalide; controle i tags HTML.',
'email'                 => 'Pueste eletroniche',
'prefs-help-realname'   => '* Non vêr (opzionâl): se tu sielzis di inserîlu al vignarà doprât par dâti un ricognossiment dal tô lavôr.',
'loginerror'            => 'Erôr te jentrade',
'prefs-help-email'      => '* Email (opzionâl): Permet ai altris di contatâti vie la to pagjine utent o di discussion cence scugnî mostrâ a ducj la tô identitât.',
'nocookiesnew'          => "L'identitât utent e je stade creade, ma no tu sês jentrât. {{SITENAME}} al dopre i cookies par visâsi dai utents, e tu tu ju âs disabilitâts. Par plasê abilitiju, dopo jentre cul to gnûf non utent e password.",
'nocookieslogin'        => '{{SITENAME}} e dopre i cookies par visâsi dai utents, e tu tu ju âs disabilitâts. Par plasê abilitiju e torne a provâ.',
'noname'                => 'No tu âs inserît un non utent valit.',
'loginsuccesstitle'     => 'Jentrât cun sucès',
'loginsuccess'          => 'Cumò tu sês jentrât te {{SITENAME}} sicu "$1".',
'wrongpassword'         => 'La peraule clâf zontade no je juste. Torne par plasê a provâ.',
'mailmypassword'        => 'Mandimi une gnove peraule clâf',
'noemail'               => 'Nissune direzion email regjistrade par l\'utent "$1".',
'emailauthenticated'    => 'La tô direzion email e je stade autenticade su $1.',
'emailnotauthenticated' => 'La tô direzion email no je ancjemò autenticade. No vignaran mandâts messaçs pes funzions ca sot.',
'noemailprefs'          => '<strong>Specifiche une direzion email par fâ lâ cheste funzion.</strong>',
'emailconfirmlink'      => 'Conferme la tô direzion email',
'invalidemailaddress'   => 'La direzion email no pues jessi acetade parcè che no samee intun formât valid. Inserìs par plasê une direzion ben formatade o disvuede chel cjamp.',

# Edit page toolbar
'bold_sample'     => 'Test in gruessut',
'bold_tip'        => 'Test in gruessut',
'italic_sample'   => 'Test in corsîf',
'italic_tip'      => 'Test in corsîf',
'link_sample'     => 'Titul dal leam',
'link_tip'        => 'Leams internis',
'extlink_sample'  => 'http://www.example.com titul leam',
'extlink_tip'     => 'Leam esterni (visiti dal prefìs http://)',
'headline_sample' => 'Test dal titul',
'headline_tip'    => 'Titul di nivel 2',
'math_sample'     => 'Inserìs la formule culì',
'math_tip'        => 'Formule matematiche (LaTeX)',
'nowiki_sample'   => 'Inserìs test no formatât culì',
'nowiki_tip'      => 'Ignore la formatazion wiki',
'image_sample'    => 'Esempli.jpg',
'image_tip'       => 'Figure includude',
'media_sample'    => 'Esempli.mp3',
'media_tip'       => 'Leam a un file multimediâl',
'sig_tip'         => 'La tô firme cun ore e date',
'hr_tip'          => 'Rie orizontâl (no stâ doprâle masse spes)',

# Edit pages
'summary'          => 'Somari',
'minoredit'        => 'Cheste al è un piçul cambiament',
'watchthis'        => 'Ten di voli cheste pagjine',
'savearticle'      => 'Salve la pagjine',
'preview'          => 'Anteprime',
'showpreview'      => 'Mostre anteprime',
'showdiff'         => 'Mostre cambiaments',
'anoneditwarning'  => 'No tu sês jentrât cuntun non utent. La to direzion IP e vignarà regjistrade tal storic di cheste pagjine.',
'blockedtitle'     => 'Utent blocât',
'loginreqtitle'    => 'Si scugne jentrâ',
'loginreqlink'     => 'jentrâ',
'loginreqpagetext' => 'Tu scugnis $1 par viodi lis altris pagjinis.',
'accmailtitle'     => 'Password mandade.',
'accmailtext'      => 'La password par "$1" e je stade mandade a $2.',
'newarticle'       => '(Gnûf)',
'newarticletext'   => "Tu âs seguît un leam a une pagjine che no esist ancjemò. Par creâ une pagjine, scomence a scrivi tal spazi ca sot (cjale il [[{{MediaWiki:helppage}}|jutori]] par altris informazions). Se tu sês ca par erôr, frache semplicementri il boton '''Indaûr''' dal to sgarfadôr.",
'noarticletext'    => '(Par cumò nol è nuie in cheste pagjine)',
'updated'          => '(Inzornât)',
'previewnote'      => 'Visiti che cheste e je dome une anteprime, e no je stade ancjemò salvade!',
'editing'          => 'Cambiament di $1',
'editinguser'      => 'Cambiament di $1',
'editingsection'   => 'Cambiament di $1 (sezion)',
'editconflict'     => 'Conflit inte modifiche: $1',
'explainconflict'  => 'Cualchidun altri al à cambiât cheste pagjine di cuant che tu âs començât a modificâle.
La aree di test disore e conten il test de pagjine che esist cumò, i tiei cambiaments a son mostrâts inte aree disot.
Tu varâs di inserî di gnûf i tiei cambiaments tal test esistint.
<b>Dome</b> il test in alt al vignarà salvât cuant che tu frachis su "Salve pagjine".<br />',
'editingold'       => '<strong>ATENZION: tu stâs cambiant une version vecje e no inzornade di cheste pagjine. Se tu la salvis, ducj i cambiaments fats di chê volte in ca a laran pierdûts.</strong>',
'yourdiff'         => 'Difarencis',
'longpagewarning'  => '<strong>ATENZION: cheste pagjine e je grande $1 kilobytes; cualchi sgarfadôr al podarès vê problemis a modificâ pagjinis di 32kb o plui grandis. Considere par plasê la pussibilitât di dividi la pagjine in sezions plui piçulis.</strong>',
'templatesused'    => 'Modei doprâts par cheste pagjine:',

# History pages
'revhistory' => 'Storic des revisions',
'nohistory'  => 'Nol è presint un storic dai cambiaments par cheste pagjine.',
'cur'        => 'cor',
'next'       => 'prossim',
'last'       => 'ultime',
'orig'       => 'orig',

# Diffs
'difference'              => '(Difarence jenfri des revisions)',
'lineno'                  => 'Rie $1:',
'editcurrent'             => 'Cambie la version corinte di cheste pagjine',
'compareselectedversions' => 'Confronte versions selezionadis',

# Search results
'searchresults'         => 'Risultâts de ricercje',
'searchresulttext'      => 'Par plui informazions su lis ricercjis in {{SITENAME}}, cjale [[{{MediaWiki:helppage}}|{{int:help}}]].',
'searchsubtitle'        => 'Pal test "[[:$1]]"',
'searchsubtitleinvalid' => 'Pal test "$1"',
'matchtotals'           => 'La ricercje "$1" e à cjatât $2 titui di pagjinis e il test di $3 pagjinis.',
'noexactmatch'          => "'''No esist une pagjine cul titul \"\$1\".''' Tu podaressis [[:\$1|creâle tu]].",
'titlematches'          => 'Corispondencis tai titui des pagjinis',
'notitlematches'        => 'Nissune corispondence tai titui des pagjinis',
'textmatches'           => 'Corispondencis tal test des pagjinis',
'notextmatches'         => 'Nissune corispondence tal test des pagjinis',
'prevn'                 => 'precedents $1',
'nextn'                 => 'prossims $1',
'viewprevnext'          => 'Cjale ($1) ($2) ($3).',
'showingresults'        => 'Ca sot a son fin a <b>$1</b> risultâts scomençant dal #<b>$2</b>.',
'showingresultsnum'     => 'Ca sot a son <b>$3</b> risultâts scomençant dal #<b>$2</b>.',
'powersearch'           => 'Cîr',
'powersearchtext'       => '
Cîr tai nons dai spazis :<br />
$1<br />
$2 Liste redirezions &nbsp; Cîr $3 $9',
'searchdisabled'        => 'La ricercje in {{SITENAME}} no je ative. Tu puedis doprâ Google intant. Sta atent che i lôr indis sul contignût di {{SITENAME}} a puedin jessi pôc inzornâts.',
'blanknamespace'        => '(Principâl)',

# Preferences page
'preferences'             => 'Preferencis',
'qbsettings'              => 'Sbare svelte',
'qbsettings-none'         => 'Nissune',
'qbsettings-fixedleft'    => 'Fis a Çampe',
'qbsettings-fixedright'   => 'Fis a Drete',
'qbsettings-floatingleft' => 'Flutuant a çampe',
'changepassword'          => 'Gambie peraule clâf',
'skin'                    => 'Mascare',
'math'                    => 'Matematiche',
'dateformat'              => 'Formât de date',
'datedefault'             => 'Nissune preference',
'datetime'                => 'Date e ore',
'prefs-personal'          => 'Dâts utents',
'prefs-rc'                => 'Ultins cambiaments & stubs',
'prefs-watchlist'         => 'Tignudis di voli',
'prefs-watchlist-days'    => 'Numar di zornadis di mostrâ inte liste des pagjinis tignudis di voli:',
'prefs-watchlist-edits'   => 'Numar di modifichis di mostrâ inte liste slargjade:',
'prefs-misc'              => 'Variis',
'saveprefs'               => 'Salve lis preferencis',
'resetprefs'              => 'Predeterminât',
'oldpassword'             => 'Vecje peraule clâf',
'newpassword'             => 'Gnove peraule clâf',
'retypenew'               => 'Torne a scrivi chê gnove',
'textboxsize'             => 'Cambiament',
'rows'                    => 'Riis',
'resultsperpage'          => 'Risultâts par pagjine',
'contextlines'            => 'Riis par risultât',
'recentchangescount'      => 'Numar di titui tai ultins cambiaments',
'savedprefs'              => 'Lis preferencis a son stadis salvadis',
'timezonelegend'          => 'Fûs orari',
'timezonetext'            => 'Il numar di oris di diference rispiet ae ore dal servidôr (UTC).',
'localtime'               => 'Ore locâl',
'servertime'              => 'Ore servidôr',
'guesstimezone'           => 'Cjape impostazions dal sgarfadôr',
'default'                 => 'predeterminât',

# Recent changes
'recentchanges'     => 'Ultins cambiaments',
'recentchangestext' => 'Cheste pagjine e mostre i plui recents cambiaments inte {{SITENAME}}.',
'rcnote'            => 'Ca sot tu cjatis i ultins <strong>$1</strong> cambiaments tes ultimis <strong>$2</strong> zornadis.',
'rclistfrom'        => 'Mostre i ultins cambiaments dal $1',
'rcshowhidebots'    => '$1 bots',
'rcshowhideliu'     => '$1 utents jentrâts',
'rcshowhideanons'   => '$1 utents anonims',
'rcshowhidepatr'    => '$1 cambiaments verificâts',
'rcshowhidemine'    => '$1 miei cambiaments',
'rclinks'           => 'Mostre i ultins $1 cambiaments tes ultimis $2 zornadis<br />$3',
'diff'              => 'difarencis',
'hist'              => 'stor',
'hide'              => 'plate',
'show'              => 'mostre',
'minoreditletter'   => 'p',
'newpageletter'     => 'G',

# Recent changes linked
'recentchangeslinked' => 'Cambiaments leâts',

# Upload
'upload'             => 'Cjame sù un file',
'uploadbtn'          => 'Cjame sù un file',
'reupload'           => 'Torne a cjamâ sù',
'uploadnologin'      => 'No jentrât',
'uploaderror'        => 'Erôr cjamant sù',
'uploadtext'         => "Dopre la form ca sot par cjamâ sù un file, par cjalâ o cirî i files cjamâts sù in precedence va te [[Special:Imagelist|liste dai files cjamâts sù]], lis cjamadis e lis eliminazions a son ancje regjistrâts tal [[Special:Log/upload|regjistri des cjamadis]].

Par includi une figure intune pagjine, dopre un leam inte form
'''<nowiki>[[</nowiki>{{ns:6}}<nowiki>:file.jpg]]</nowiki>''',
'''<nowiki>[[</nowiki>{{ns:6}}<nowiki>:file.png|alt text]]</nowiki>''' or
'''<nowiki>[[</nowiki>{{ns:-2}}<nowiki>:file.ogg]]</nowiki>''' par un leam diret al file.",
'uploadlog'          => 'regjistri cjamâts sù',
'uploadlogpagetext'  => 'Ca sot e je une liste dai file cjamâts su di recent.',
'filename'           => 'Non dal file',
'filedesc'           => 'Descrizion',
'fileuploadsummary'  => 'Somari:',
'filestatus'         => 'Stât dal copyright',
'filesource'         => 'Surzint',
'uploadedfiles'      => 'Files cjamâts sù',
'ignorewarning'      => 'Ignore avîs e salve instès il file.',
'ignorewarnings'     => 'Ignore i avîs',
'badfilename'        => 'File non gambiât in "$1".',
'successfulupload'   => 'Cjamât sù cun sucès',
'savefile'           => 'Salve file',
'uploadedimage'      => 'cjamât sù "$1"',
'uploaddisabled'     => 'Nus displâs, par cumò no si pues cjamâ sù robe.',
'uploaddisabledtext' => 'Lis cjamadis a son disativâts su cheste wiki.',
'sourcefilename'     => 'Non dal file origjinâl',
'destfilename'       => 'Non dal file di destinazion',

# Image list
'imagelist'        => 'Liste des figuris',
'imagelisttext'    => 'Ca sot e je une liste di $1 files ordenâts $2.',
'ilsubmit'         => 'Cîr',
'showlast'         => 'Mostre i ultins $1 files ordenâts $2.',
'byname'           => 'par non',
'bydate'           => 'par date',
'bysize'           => 'par dimension',
'imgdelete'        => 'eli',
'imgdesc'          => 'desc',
'imglegend'        => 'Legenda: (desc) = mostre/cambie descrizion de figure.',
'imghistory'       => 'Storic de figure',
'revertimg'        => 'rip',
'deleteimg'        => 'eli',
'imghistlegend'    => 'Legenda: (cor) = cheste e je la figure corinte, (eli) = elimine
cheste vecje version, (rip) = torne a ripristinâ cheste vecje version.
<br /><i>Frache su une date par viodi la figure cjamade su chê volte</i>.',
'imagelinks'       => 'Leams de figure',
'linkstoimage'     => 'Lis pagjinis ca sot a son leadis a cheste figure:',
'nolinkstoimage'   => 'No son pagjinis leadis a chest file.',
'sharedupload'     => 'Chest file al è condivîs e al pues jessi doprât di altris progjets.',
'shareduploadwiki' => 'Cjale par plasê la [pagjine di descrizion dal file $1] par altris informazions.',

# MIME search
'mimesearch' => 'Ricercje MIME',
'mimetype'   => 'Gjenar MIME:',
'download'   => 'discjame',

# List redirects
'listredirects' => 'Liste des redirezions',

# Unused templates
'unusedtemplates' => 'Modei no doprâts',

# Statistics
'statistics'    => 'Statistichis',
'sitestats'     => 'Statistichis dal sît',
'userstats'     => 'Statistichis dai utents',
'sitestatstext' => "Tu puedis cjatâ in dut '''\$1''' pagjine inte base di dâts.
Chest numar al inclût pagjinis \"discussion\", pagjinis su la {{SITENAME}}, pagjinis cun pocjis peraulis, reindirizaments, e altris che probabilmentri no si puedin considerâ pardabon come pagjinis di contignût.
Gjavant chestis, o vin '''\$2''' pagjinis che a son probabilmentri pagjinis di contignût legjitimis.

'''\$8''' files a son stâts cjamâts sù.

O vin vût in dut '''\$3''' viodudis de pagjinis e '''\$4''' cambiaments aes pagjinis di cuant che la wiki e je stade implantade.
Chest al vûl dî une medie di '''\$5''' cambiaments par pagjine, e '''\$6''' viodudis par ogni cambiament.",
'userstatstext' => "A son '''$1''' utents regjistrâts, di chescj '''$2''' (il '''$4%''') a son aministradôrs (cjale $3).",

'disambiguations'     => 'Pagjinis di disambiguazion',
'disambiguationspage' => 'Template:disambig',

'doubleredirects' => 'Reindirizaments doplis',

'brokenredirects'     => 'Redirezions no funzionantis',
'brokenredirectstext' => 'Lis redirezions ca sot inviin a pagjinis che no esistin:',

# Miscellaneous special pages
'nbytes'                  => '$1 bytes',
'nlinks'                  => '$1 leams',
'lonelypages'             => 'Pagjinis solitaris',
'uncategorizedpages'      => 'Pagjinis cence categorie',
'uncategorizedcategories' => 'Categoriis cence categorie',
'unusedimages'            => 'Files no doprâts',
'popularpages'            => 'Pagjinis popolârs',
'wantedpages'             => 'Pagjinis desideradis',
'mostlinked'              => 'Pagjinis a cui pontin il maiôr numar di leams',
'mostlinkedcategories'    => 'Categoriis a cui pontin il maiôr numar di leams',
'mostcategories'          => 'Vôs cul maiôr numar di categoriis',
'mostimages'              => 'Figuris a cui pontin il maiôr numar di leams',
'mostrevisions'           => 'Vôs cul maiôr numar di revisions',
'allpages'                => 'Dutis lis pagjinis',
'randompage'              => 'Une pagjine a câs',
'shortpages'              => 'Pagjinis curtis',
'longpages'               => 'Pagjinis lungjis',
'deadendpages'            => 'Pagjinis cence usite',
'listusers'               => 'Liste dai utents',
'specialpages'            => 'Pagjinis speciâls',
'spheading'               => 'Pagjinis speciâls par ducj i utents',
'restrictedpheading'      => 'Pagjinis speciâls cun restrizions',
'rclsub'                  => '(aes pagjinis leadis di "$1")',
'newpages'                => 'Gnovis pagjinis',
'ancientpages'            => 'Pagjinis plui vecjis',
'intl'                    => 'Leams interlengâi',
'move'                    => 'Môf',
'movethispage'            => 'Môf cheste pagjine',

'categoriespagetext' => 'Te wiki a esistin lis categoriis ca sot.',
'data'               => 'Dâts',
'version'            => 'Version',

# Special:Log
'specialloguserlabel'  => 'Utent:',
'speciallogtitlelabel' => 'Titul:',
'log'                  => 'Regjistris',
'alllogstext'          => 'Viodude combinade dai regjistris des cjamadis, eliminazions, protezions, blocs e azions day sysop.
Tu puedis strenzi la viodude sielzint un gjenar di regjistri, un non utent o la vôs che ti interesse.',
'logempty'             => 'Nissun element corispondint tal regjistri.',

# Special:Allpages
'nextpage'          => 'Prossime pagjine ($1)',
'allpagesfrom'      => 'Mostre pagjinis scomençant di:',
'allarticles'       => 'Dutis lis vôs',
'allinnamespace'    => 'Dutis lis pagjinis (non dal spazi $1)',
'allnotinnamespace' => 'Dutis lis pagjinis (no tal non dal spazi $1)',
'allpagesprev'      => 'Precedent',
'allpagesnext'      => 'Prossim',
'allpagessubmit'    => 'Va',

# E-mail user
'emailuser'    => 'Messaç di pueste a chest utent',
'noemailtitle' => 'Nissune direzion email',
'noemailtext'  => 'Chest utent nol à specificât une direzion di pueste valide o al à sielzût di no ricevi pueste di altris utents.',
'emailmessage' => 'Messaç',

# Watchlist
'watchlist'            => 'Tignûts di voli',
'mywatchlist'          => 'Tignûts di voli',
'nowatchlist'          => 'Nissun element al è tignût di voli.',
'watchnologin'         => 'No tu sês jentrât',
'watchnologintext'     => "Tu 'nd âs di [[Special:Userlogin|jentrâ]] par modificâ la liste des pagjinis tignudis di voli.",
'addedwatch'           => 'Zontât aes pagjinis tignudis di voli',
'addedwatchtext'       => "La pagjine \"\$1\" e je stade zontade ae [[Special:Watchlist|liste di chês tignudis di voli]].
Tal futûr i cambiaments a cheste pagjine e ae pagjine di discussion relative a saran segnalâts ca,
e la pagjine e sarà '''gruessute''' te [[Special:Recentchanges|liste dai ultins cambiaments]] cussì che tu puedis notâle daurman.

<p>Se tu vuelis gjavâle de liste pi indevant, frache su \"No stâ tignî di voli\" te sbare in alt.",
'removedwatch'         => 'Gjavade de liste',
'removedwatchtext'     => 'La pagjine "$1" e je stade gjavade de liste di chês tignudis di voli.',
'watch'                => 'Ten di voli',
'watchthispage'        => 'Ten di voli cheste pagjine',
'unwatch'              => 'No stâ tignî di voli',
'unwatchthispage'      => 'No stâ tignî di voli plui',
'watchnochange'        => 'Nissun element di chei tignûts di voli al è stât cambiât tal periodi mostrât.',
'watchlist-details'    => '$1 pagjinis tignudis di voli cence contâ lis pagjinis di discussion.',
'wlheader-enotif'      => '* Notifiche par pueste eletroniche ativade.',
'wlheader-showupdated' => "* Lis pagjinis gambiadis de ultime volte che tu lis âs cjaladis a son mostradis in '''gruessut'''",
'watchlistcontains'    => 'Tu stâs tignint di voli $1 pagjinis.',
'wlnote'               => 'Ca sot a son i ultins $1 cambiaments tes ultimis <b>$2</b> oris.',
'wlshowlast'           => 'Mostre ultimis $1 oris $2 zornadis $3',
'wlsaved'              => 'Cheste e je une version salvade de liste da lis pagjinis tignudis di voli.',

'changed' => 'cambiade',
'created' => 'creade',

# Delete/protect/revert
'deletepage'       => 'Elimine pagjine',
'confirm'          => 'Conferme',
'excontent'        => "il contignût al jere: '$1'",
'excontentauthor'  => "il contignût al jere: '$1' (e al veve contribuît dome '$2')",
'exbeforeblank'    => "il contignût prime di disvuedâ al jere: '$1'",
'exblank'          => 'pagjine vueide',
'confirmdelete'    => 'Conferme eliminazion',
'deletesub'        => '(Eliminant "$1")',
'historywarning'   => 'Atenzion: la pagjine che tu stâs eliminant e à un storic.',
'deletedtext'      => '"$1" al è stât eliminât.
Cjale $2 par une liste des ultimis eliminazions.',
'deletedarticle'   => 'eliminât "$1"',
'dellogpage'       => 'Regjistri des eliminazions',
'deletionlog'      => 'regjistri eliminazions',
'deletecomment'    => 'Reson pe eliminazion',
'protectedarticle' => '$1 protezût',
'protectsub'       => '(Protezint "$1")',
'confirmprotect'   => 'Conferme protezion',
'protectcomment'   => 'Reson pe protezion',

# Restrictions (nouns)
'restriction-edit' => 'Cambie',
'restriction-move' => 'Spostament',

# Namespace form on various pages
'namespace' => 'Non dal spazi:',
'invert'    => 'Invertìs selezion',

# Contributions
'contributions' => 'Contribûts dal utent',
'mycontris'     => 'Miei contribûts',
'contribsub2'   => 'Par $1 ($2)',
'nocontribs'    => 'Nissun cambiament che al rispiete chescj criteris cjatât.',
'ucnote'        => 'Ca sot a son i ultins <b>$1</b> cambiaments dal utent tes ultimis <b>$2</b> zornadis.',
'uclinks'       => 'Viôt i ultins $1 cambiaments; viôt lis ultimis $2 zornadis.',
'uctop'         => ' (su)',

# What links here
'whatlinkshere' => 'Leams a cheste vôs',
'linklistsub'   => '(Liste di leams)',
'linkshere'     => 'Lis pagjinis ca sot a son leadis a cheste:',
'nolinkshere'   => 'Nissune pagjine e à leams a cheste vôs',
'isredirect'    => 'pagjine di reindirizament',
'istemplate'    => 'includude',

# Block/unblock
'ipbsubmit'    => 'Bloche chest utent',
'blocklink'    => 'bloche',
'contribslink' => 'contribûts',

# Developer tools
'lockdb'  => 'Bloche base di dâts',
'lockbtn' => 'Bloche base di dâts',

# Move page
'movepage'         => 'Môf pagjine',
'movepagetext'     => "Cun il formulari ca sot tu puedis gambiâ il non a une pagjine, movint dut il sô storic al gnûf non.
Il vieri titul al deventarà une pagjine di reindirizament al gnûf titul. I leams ae vecje pagjine no saran gambiâts; verifiche
par plasê che no sedin reindirizaments doplis o no funzionants.
Tu sês responsabil che i leams a continui a mandâ tal puest just.

Note che la pagjine '''no''' sarà movude se e je za une pagjine cul gnûf titul, a mancul che no sedi vueide o un reindirizament e
cence un storic. Chest al vûl dî che tu puedis tornâ a movi la pagjine tal titul precedent, se
tu 'nd âs sbaliât e che no tu puedis sorescrivi une pagjine esistìnte.

<b>ATENZION!</b>
Chest al pues jessi un cambiament drastic e surprendint par une pagjine popolâr;
tu âs di cognossi lis conseguencis prime di lâ indevant.",
'movearticle'      => 'Môf la vôs',
'movenologin'      => 'No tu sês jentrât',
'movenologintext'  => 'Tu âs di jessi un utent regjistrât e <a href="{{localurl:Special:Userlogin}}">jentrât</a> par movi une pagjine.',
'newtitle'         => 'Al gnûf titul',
'movepagebtn'      => 'Môf pagjine',
'pagemovedsub'     => 'Movude cun sucès',
'articleexists'    => 'Une pagjine cun chest non e esist za, o il non sielt nol è valit.
Sielç par plasê un altri non.',
'talkexists'       => "'''La pagjine e je stade movude cun sucès, ma no si à podût movi la pagjine di discussion parcè che e esist za tal gnûf titul. Trasferìs il contignût a man par plasê.'''",
'movedto'          => 'Movude in',
'movetalk'         => 'Môf ancje la pagjine di discussion, se pussibil.',
'talkpagemoved'    => 'Ancje la pagjine di discussion corispondente e je stade movude.',
'talkpagenotmoved' => 'La pagjine di discussion corispondente <strong>no</strong> je stade movude.',
'1movedto2'        => '$1 movût in $2',
'movelogpage'      => 'Regjistri des pagjinis movudis',
'movelogpagetext'  => 'Ca sot e je une liste des pagjinis movudis.',
'movereason'       => 'Reson',
'revertmove'       => 'ripristine',
'delete_and_move'  => 'Elimine e môf',

# Export
'export'        => 'Espuarte pagjinis',
'exportcuronly' => 'Inclût dome la revision corinte, no dut il storic',

# Namespace 8 related
'allmessages'         => 'Ducj i messaçs di sisteme',
'allmessagesname'     => 'Non',
'allmessagesdefault'  => 'Test predeterminât',
'allmessagescurrent'  => 'Test curint',
'allmessagestext'     => 'Cheste e je une liste dai messaçs di sisteme disponibii tal non dal spazi MediaWiki:',
'allmessagesmodified' => 'Mostre dome modificâts',

# Thumbnails
'thumbnail-more'  => 'Slargje',
'missingimage'    => '<b>Figure mancjante</b><br /><i>$1</i>',
'filemissing'     => 'File mancjant',
'thumbnail_error' => 'Erôr inte creazion de miniature: $1',

# Special:Import
'import'        => 'Impuarte pagjinis',
'importfailed'  => 'Impuartazion falide: $1',
'importnotext'  => 'Vueit o cence test',
'importsuccess' => 'Impuartât cun sucès!',

# Tooltip help for the actions
'tooltip-search'                  => 'Cîr in cheste wiki',
'tooltip-minoredit'               => 'Segne cheste come une piçul cambiament',
'tooltip-save'                    => 'Salve i tiei cambiaments',
'tooltip-preview'                 => 'Anteprime dai tiei cambiaments, doprile par plasê prime di salvâ!',
'tooltip-diff'                    => 'Mostre i cambiaments che tu âs fat al test.',
'tooltip-compareselectedversions' => 'Viôt lis difarencis framieç lis dôs versions di cheste pagjine selezionadis.',
'tooltip-watch'                   => 'Zonte cheste pagjine ae liste di chês tignudis di voli',

# Stylesheets
'monobook.css' => '/* modifiche chest file par personalizâ la skin monobook par dut il sît */',

# Attribution
'anonymous'        => 'Utent(s) anonim(s) di {{SITENAME}}',
'siteuser'         => 'Utent $1 di {{SITENAME}}',
'lastmodifiedatby' => "Cambiât par l'ultime volte ai $2, $1 di", # $1 date, $2 time, $3 user
'and'              => 'e',
'othercontribs'    => 'Basât sul lavôr di $1.',
'others'           => 'altris',
'siteusers'        => 'Utents  $1 di {{SITENAME}}',
'creditspage'      => 'Pagjine dai ricognossiments',
'nocredits'        => 'Nissune informazion sui ricognossiments disponibil par cheste pagjine.',

# Spam protection
'categoryarticlecount'   => 'In cheste categorie tu puedis cjatâ $1 vôs.',
'listingcontinuesabbrev' => 'cont.',

# Info page
'infosubtitle'   => 'Informazions pe pagjine',
'numedits'       => 'Numar di cambiaments (vôs): $1',
'numtalkedits'   => 'Numar di cambiaments (pagjine di discussion): $1',
'numwatchers'    => 'Numar di chei che e àn cjalât: $1',
'numauthors'     => 'Numar di autôrs diviers (vôs): $1',
'numtalkauthors' => 'Numar di autôrs diviers (pagjine di discussion): $1',

# Math options
'mw_math_png'    => 'Torne simpri PNG',
'mw_math_simple' => 'HTML se une vore sempliç, se no PNG',
'mw_math_html'   => 'HTML se pussibil se no PNG',
'mw_math_source' => 'Lassile come TeX (par sgarfadôrs testuâi)',
'mw_math_modern' => 'Racomandât pai sgarfadôrs testuâi',
'mw_math_mathml' => 'MathML se pussibil (sperimentâl)',

# Browsing diffs
'previousdiff' => '&larr; Difarence precedente',
'nextdiff'     => 'Prossime difarence &rarr;',

# Media information
'thumbsize' => 'Dimension miniature:',

'newimages' => 'Galarie dai gnûfs files',
'noimages'  => 'Nuie di viodi.',

'passwordtooshort' => 'La tô peraule clâf e je masse curte, e à di jessi lungje almancul $1 caratars.',

# EXIF tags
'exif-imagewidth'  => 'Largjece',
'exif-imagelength' => 'Altece',
'exif-model'       => 'Model di machine fotografiche',
'exif-software'    => 'Software doprÂt',

# EXIF attributes
'exif-compression-1' => 'Cence compression',

# External editor support
'edit-externally'      => 'Modifiche chest file cuntune aplicazion esterne',
'edit-externally-help' => 'Cjale [http://meta.wikimedia.org/wiki/Help:External_editors setup instructions] par altris informazions.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'ducj',
'imagelistall'     => 'ducj',
'watchlistall2'    => 'dutis',
'namespacesall'    => 'ducj',

# E-mail address confirmation
'confirmemail'          => 'Conferme direzione di pueste',
'confirmemail_text'     => 'Cheste wiki ti domande di validÂ la to direzion di pueste eletroniche prime di doprâ lis funzions di email. Ative il boton ca sot par inviâ un codiç di conferme ae to direzion. Chest messaç al includarà un leam cuntun codiç; cjame il leam tal to sgarfadôr par confermâ la validitât de tô direzion.',
'confirmemail_send'     => 'Mande un codiç di conferme',
'confirmemail_sent'     => 'Messaç di conferme mandât.',
'confirmemail_success'  => 'La tô direzion di pueste e je stade confermade. Tu puedis cumò jentrâ e gjoldi la wiki.',
'confirmemail_loggedin' => 'La tô direzion di pueste e je stade confermade.',

# Scary transclusion
'scarytranscludedisabled' => '[Inclusion dai interwikis no ative]',
'scarytranscludefailed'   => '[Recupar dal model falît par $1; o si scusin]',
'scarytranscludetoolong'  => '[URL masse lungje; o si scusin]',

'youhavenewmessagesmulti' => 'Tu âs gnûfs messaçs su $1',

'hideresults' => 'Plate risultâts',

);



<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageUtf8.php' );

/* private */ $wgNamespaceNamesFur = array(
	NS_MEDIA			=> 'Media',
	NS_SPECIAL			=> 'Speciâl',
	NS_MAIN				=> '',
	NS_TALK				=> 'Discussion',
	NS_USER				=> 'Utent',
	NS_USER_TALK			=> 'Discussion_utent',
	NS_PROJECT			=> $wgMetaNamespace,
	NS_PROJECT_TALK			=> 'Discussion_'.$wgMetaNamespace,
	NS_IMAGE			=> 'Figure',
	NS_IMAGE_TALK			=> 'Discussion_figure',
	NS_MEDIAWIKI			=> 'MediaWiki',
	NS_MEDIAWIKI_TALK		=> 'Discussion_MediaWiki',
	NS_TEMPLATE			=> 'Model',
	NS_TEMPLATE_TALK		=> 'Discussion_model',
	NS_HELP				=> 'Jutori',
	NS_HELP_TALK			=> 'Discussion_jutori',
	NS_CATEGORY			=> 'Categorie',
	NS_CATEGORY_TALK		=> 'Discussion_categorie'
) + $wgNamespaceNamesEn;



/* private */ $wgQuickbarSettingsFur = array(
	'Nissune', 'Fis a Çampe', 'Fis a Drete', 'Flutuant a çampe'
);

/* private */ $wgSkinNamesFur = array(
	'nostalgia'		=> 'Nostalgie',
) + $wgSkinNamesEn;

$wgAllMessagesFur = array(
'1movedto2'		=> "$1 movût in $2",
'about' => "Informazions",
'aboutsite' => "Informazions su {{SITENAME}}",
'addedwatch'		=> "Zontât aes pagjinis tignudis di voli",
'addedwatchtext'	=> "La pagjine \"$1\" e je stade zontade ae [[Special:Watchlist|liste di chês tignudis di voli]].
Tal futûr i cambiaments a cheste pagjine e ae pagjine di discussion relative a saran segnalâts ca,
e la pagjine e sarà '''gruessute''' te [[Special:Recentchanges|liste dai ultins cambiaments]] cussì che tu puedis notâle daurman.

<p>Se tu vuelis gjavâle de liste pi indevant, frache su \"No stâ tignî di voli\" te sbare in alt.",

'administrators' => "Project:Aministradôrs",
'allarticles' => "Ducj i articui",
'allinnamespace'	=> "Dutis lis pagjinis (non dal spazi $1)",
'allmessages' => "Ducj i messaçs di sisteme",
'allmessagescurrent' => "Test curint",
'allmessagesdefault' => "Test predeterminât",
'allmessagesname' => "Non",
'allmessagestext'	=> "Cheste e je une liste dai messaçs di sisteme disponibii tal non dal spazi MediaWiki:",
'allnonarticles'	=> "Ducj i no-articui",
'allnotinnamespace'	=> "Dutis lis pagjinis (no tal non dal spazi $1)",
'allpages' => "Dutis lis pagjinis",
'allpagesfrom'		=> "Mostre pagjinis scomençant di:",
'allpagesnext'		=> "Prossim",
'allpagesprev'		=> "Precedent",
'allpagessubmit' => "Va",
'and' => 'e',
'anontalk'		=> 'Discussion par chest IP',
'anonymous' => 'Utent(s) anonim(s) di {{SITENAME}}',
'apr' => "Avr",
'april' => "Avrîl",
'articleexists' => 'Une pagjine cun chest non e esist za, o il non sielt nol è valit.
Sielç par plasê un altri non.',
'aug' => "Avo",
'august' => "Avost",
'badfilename'	=> 'File non gambiât in "$1".',
'badretype'		=> "Lis peraulis clâfs inseridis no son compagnis.",
'blockedtitle'	=> 'Utent blocât',
'blocklink' => "bloche",
'bold_sample'=>'Test in gruessut',
'bold_tip'=>'Test in gruessut',
'byname'		=> 'par non',
'bydate'		=> 'par date',
'bysize'		=> 'par dimension',
'cancel' => "Scancele",
'categories' => "Categoriis",
'categoriespagetext' => 'Te wiki a esistin lis categoriis ca sot.',
'category' => "categorie",
'category_header' => "Articui inte categorie \"$1\"",
'categoryarticlecount' => "In cheste categorie tu puedis cjatâ $1 articui.",
'categoryarticlecount1' => "In cheste categorie tu puedis cjatâ $1 articul.",
'changepassword' => 'Gambie peraule clâf',
'changes' => 'cambiaments',
'compareselectedversions' => 'Confronte versions selezionadis',
'confirm' => "Conferme",
'confirmdelete' => "Conferme eliminazion",
'confirmprotect' => "Conferme protezion",
'confirmprotecttext' => "Vuelistu pardabon protezi cheste pagjine?",
'confirmunprotect' => "Conferme par gjavâ la protezion",
'confirmunprotecttext' => "Vuelistu pardabon gjavâ la protezion a cheste pagjine?",
'contextlines'	=> 'Riis par risultât',
'contributions' => "Contribûts dal utent",
'contribsub'    => "Par $1",
'contribs-showhideminor' => '$1 piçulis modifichis',
'copyright' => "Il contignût al è disponibil sot de $1",
'copyrightwarning' => "<!-- Perché i link non abbiano l'aspetto di link esterni: -->
<div class=\"plainlinks\">

<div style=\"margin-top:2em\">
<div style=\"font-weight: bold; font-size: 120%;\">I cambiaments che tu âs fat a saran visibii daurman.</div>
* Par plasê, dopre la [[Vichipedie:Sandbox|sandbox]] se tu vuelis fâ cualchi prove.
----
<p style=\"background: red; color: white; font-weight: bold; text-align: center; padding: 2px;\">'''NO STÂ DOPRÂ MATERIÂL CUVIERT DAL DIRIT DI AUTÔR (COPYRIGHT - ©) SE NO TU ÂS UNE AUTORIZAZION ESPLICITE!!!'''</p></div>

* Sta atent, par plasê, che ducj i contribûts ae Vichipedie a son considerâts come dâts fûr sot di une licence GNU Free Documentation License (cjale $1 par altris detais).
* Se no tu vuelis che il to test al puedi jessi gambiât e tornât a jessi distribuît da cualsisei persone cence limits, no stâ mandâlu ae Vichipedie, al è miôr se tu ti fasis un to sît web personâl.
* Inviant chest test, tu stâs garantint che chest al è stât scrit di te in origjin, o che al è stât copiât di une sorzint di public domini, o alc   di simil, opûr che tu âs vût une autorizazion esplicite pe publicazion e  tu puedis dimostrâ chest fat.
</div>

</div>",
'createaccount' => "Cree une gnove identitât",
'createarticle' => "Cree articul",
'creditspage' => 'Pagjine dai ricognossiments',
'cur' => "cor",
'currentevents' => "Lis gnovis",
'data'	=> 'Dâts',
'dateformat'		=> 'Formât de date',
'deadendpages'  => 'Pagjinis cence usite',
'dec' => "Dic",
'december' => "Dicembar",
'default' => "predeterminât",
'delete' => "Elimine",
'delete_and_move' => 'Elimine e môf',
'deletecomment'	=> 'Reson pe eliminazion',
'deletedarticle' => "eliminât \"$1\"",
'deletedtext'	=> "\"$1\" al è stât eliminât.
Cjale $2 par une liste des ultimis eliminazions.",
'deleteimg' => "eli",
'deletepage' => "Elimine pagjine",
'deletesub' => "(Eliminant \"$1\")",
'deletethispage' => "Elimine cheste pagjine",
'deletionlog'	=> 'regjistri eliminazions',
'dellogpage'	=> 'Regjistri des eliminazions',
'destfilename' => 'Non dal file di destinazion',
'diff' => "difarencis",
'difference' => "(Difarence jenfri des revisions)",
'disambiguations'	=> 'Pagjinis di disambiguazion',
'disambiguationspage'	=> 'Template:disambig',
'disclaimers' => "Avîs legâi",
'doubleredirects'	=> 'Reindirizaments doplis',
'edit-externally' => 'Modifiche chest file cuntune aplicazion esterne',
'edit-externally-help' => 'Cjale [http://meta.wikimedia.org/wiki/Help:External_editors setup instructions] par altris informazions.',
'edit' => "Modifiche",
'editcurrent'	=> 'Modifiche la version corinte di cheste pagjine',
'editing' => "Modificant $1",
'edithelp'		=> "Jutori pe modifiche",
'edithelppage'	=> "Jutori:Modifiche",
'editingsection' => "Modificant $1 (sezion)",
'editsection' => "modifiche",
'editthispage' => "Modifiche cheste pagjine",
'emailflag'		=> 'No vueli email da altris utents',
'emailmessage' => "Messaç",
'emailuser' => "Messaç di pueste a chest utent",
'error' => "Erôr",
'errorpagetitle' => "Erôr",
'exbeforeblank' => "il contignût prime di disvuedâ al jere: '$1'",
'exblank' => 'pagjine vueide',
'excontent' => "il contignût al jere: '$1'",
'excontentauthor' => "il contignût al jere: '$1' (e al veve contribuît dome '$2')",
'export' => "Espuarte pagjinis",
'exportcuronly'	=> 'Inclût dome la revision corinte, no dut il storic',
'feb' => "Fev",
'february' => "Fevrâr",
'filedesc' => "Descrizion",
'fileinfo' => '$1KB, gjenar MIME: <code>$2</code>',
'filemissing' => "File mancjant",
'filename' => "Non dal file",
'filenotfound' => "No si pues cjatâ il file \"$1\".",
'filesource' => 'Surzint',
'filestatus' => 'Stât dal copyright',
'friday' => "Vinars",
'go' => "Va",
'googlesearch' => "
<div style=\"margin-left: 2em\">

<!-- Google search -->
<div style=\"width:130px;float:left;text-align:center;position:relative;top:-8px\"><a href=\"http://www.google.com/\" style=\"padding:0;background-image:none\"><img src=\"http://www.google.com/logos/Logo_40wht.gif\" alt=\"Google\" style=\"border:none\" /></a></div>

<form method=\"get\" action=\"http://www.google.com/search\" style=\"margin-left:135px\">
  <div>
    <input type=\"hidden\" name=\"domains\" value=\"{{SERVER}}\" />
    <input type=\"hidden\" name=\"num\" value=\"50\" />
    <input type=\"hidden\" name=\"ie\" value=\"$2\" />
    <input type=\"hidden\" name=\"oe\" value=\"$2\" />

    <input type=\"text\" name=\"q\" size=\"31\" maxlength=\"255\" value=\"$1\" />
    <input type=\"submit\" name=\"btnG\" value=\"Cîr cun Google\" />
  </div>
  <div style=\"font-size:90%\">
    <input type=\"radio\" name=\"sitesearch\" id=\"gwiki\" value=\"{{SERVER}}\" checked=\"checked\" /><label for=\"gwiki\">{{SITENAME}}</label>
    <input type=\"radio\" name=\"sitesearch\" id=\"gWWW\" value=\"\" /><label for=\"gWWW\">WWW</label>
  </div>
</form>

</div>",
'guesstimezone' => "Cjape impostazions dal sgarfadôr",
'help' => "Jutori",
'helppage' => "Jutori:Contignûts",
'hide' => "plate",
'hidetoc' => "plate",
'hist' => "storic",
'history' => "Storic de pagjine",
'history_copyright' => "-",
'history_short' => "Storic",
'historywarning' => 'Atenzion: la pagjine che tu stâs eliminant e à un storic.',
'hr_tip' => "Rie orizontâl (no stâ doprâle masse spes)",
'ilsubmit' => "Cîr",
'image_sample' => "Esempli.jpg",
'imagelinks' => "Leams de figure",
'imagelist' => "Liste des figuris",
'imagelistall' => 'ducj',
'imagelisttext'	=> "Ca sot e je une liste di $1 files ordenâts $2.",
'imagepage' => "Cjale pagjine de figure",
'imgdelete' => "eli",
'imgdesc' => "desc",
'imghistlegend' => "Legenda: (cor) = cheste e je la figure corinte, (eli) = elimine
cheste vecje version, (rip) = torne a ripristinâ cheste vecje version.
<br /><i>Frache su une date par viodi la figure cjamade su chê volte</i>.",
'imghistory' => "Storic de figure",
'imglegend' => "Legenda: (desc) = mostre/modifiche descrizion de figure.",
'import'	=> 'Impuarte pagjinis',
'importfailed'	=> "Impuartazion falide: $1",
'importnotext' => "Vueit o cence test",
'importsuccess'	=> 'Impuartât cun sucès!',
'info_short' => "Informazions",
'infosubtitle' => 'Informazions pe pagjine',
'intl'		=> 'Leams interlengâi',
'invert' => "Invertìs selezion",
'ipbsubmit' => "Bloche chest utent",
'isbn' => "ISBN",
'isredirect' => "pagjine di reindirizament",
'italic_sample'=>'Test in corsîf',
'italic_tip'=>'Test in corsîf',
'jan' => "Zen",
'january' => "Zenâr",
'jul' => "Lui",
'jun' => "Zug",
'june' => "Zugn",
'largefile' => "Si racomande che lis figuris no sedin pi grandis di 100KB.",
'last' => "ultime",
'lastmodified' => "Modificât par l'ultime volte il ai $1",
'lastmodifiedby' => "Modificât par l'ultime volte ai $1 di",
'lineno' => "Rie $1:",
'link_sample' => "Titul dal leam",
'link_tip' => "Leams internis",
'linklistsub' => "(Liste di leams)",
'linkshere' => "Lis pagjinis ca sot a son leadis a cheste:",
'linkstoimage' => "Lis pagjinis ca sot a son leadis a cheste figure:",
'linktrail' => "/^([a-z]+)(.*)$/sD",
'listform' => "liste",
'listingcontinuesabbrev' => " cont.",
'listusers' => "Liste dai utents",
'lockbtn' => "Bloche base di dâts",
'lockdb' => "Bloche base di dâts",
'log'		=> 'Regjistris',
'login' => "Jentre",
'loginend' => "&nbsp;",
'loginerror' => "Erôr te jentrade",
'loginpagetitle' => "Jentrade dal utent",
'loginreqtitle'	=> 'Si scugne jentrâ',
'loginreqpagetext'	=> 'Tu scugnis [[{{ns:special}}:Userlogin|jentrâ\']] par viodi lis altris pagjinis.',
'loginsuccess' => "Cumò tu sês jentrât te {{SITENAME}} sicu \"$1\".",
'loginsuccesstitle' => "Jentrât cun sucès",
'logout' => "Jes",
'lonelypages'	=> 'Pagjinis solitaris',
'longpages'		=> 'Pagjinis lungjis',
'mailmypassword' 	=> "Mandimi une gnove peraule clâf",
'mainpage' => "Pagjine principâl",
'math'			=> 'Matematiche',
'may' => "Mai",
'may_long' => "Mai",
'media_sample' => "Esempli.mp3",
'media_tip' => "Leam a un file multimediâl",
'minlength' => "Il non di une figure al à di jessi lunc al mancul trê letaris.",
'minoredit' => "Cheste e je une piçule modifiche",
'minoreditletter' => "p",
'missingimage'		=> "<b>Figure mancjante</b><br /><i>$1</i>\n",
'monday' => "Lunis",
'Monobook.css' => '/* modifiche chest file par personalizâ la mascare monobook par dut il sît */',
'moredotdotdot'	=> 'Plui...',
'move' => "Môf",
'movearticle' => "Môf l'articul",
'movedto' => "Movude in",
'movelogpage' => 'Regjistri des pagjinis movudis',
'movelogpagetext' => 'Ca sot e je une liste des pagjinis movudis.',
'movenologin' => "No tu sês jentrât",
'movenologintext' => "Tu âs di jessi un utent regjistrât e <a href=\"{{localurl:Special:Userlogin}}\">jentrât</a> par movi une pagjine.",
'movepage' => "Môf pagjine",
'movepagetext'	=> 'Cun il formulari ca sot tu puedis gambiâ il non a une pagjine, movint dut il sô storic al gnûf non.
Il vieri titul al deventarà une pagjine di reindirizament al gnûf titul. I leams ae vecje pagjine no saran gambiâts; verifiche
par plasê che no sedin reindirizaments doplis o no funzionants.
Tu sês responsabil che i leams a continui a mandâ tal puest just.

Note che la pagjine \'\'\'no\'\'\' sarà movude se e je za une pagjine cul gnûf titul, a mancul che no sedi vueide o un reindirizament e
cence un storic. Chest al vûl dî che tu puedis tornâ a movi la pagjine tal titul precedent, se
tu \'nd âs sbaliât e che no tu puedis sorescrivi une pagjine esistìnte.

<b>ATENZION!</b>
Chest al pues jessi un cambiament drastic e surprendint par une pagjine popolâr;
tu âs di cognossi lis conseguencis prime di lâ indevant.',
'movepagebtn' => "Môf pagjine",
'movereason'	=> 'Reson',
'movetalk'		=> 'Môf ancje la pagjine di discussion, se pussibil.',
'movethispage' => "Môf cheste pagjine",
'mw_math_png' => 'Torne simpri PNG',
'mw_math_simple' => 'HTML se une vore sempliç, se no PNG',
'mw_math_html' => 'HTML se pussibil se no PNG',
'mw_math_source' => 'Lassile come TeX (par sgarfadôrs testuâi)',
'mw_math_modern' => 'Racomandât pai sgarfadôrs testuâi',
'mw_math_mathml' => 'MathML se pussibil (sperimentâl)',
'mycontris' => "Miei contribûts",
'mypage' => "Mê pagjine",
'mytalk' => "Mês discussions",
'navigation' => "somari",
'nbytes' => "$1 bytes",
'namespace' => 'Non dal spazi:',
'nchanges' => "$1 cambiaments",
'newarticle' => "(Gnûf)",
'newarticletext' => "Tu âs seguît un leam a une pagjine che no esist ancjemò. Par creâ une pagjine, scomence a scrivi tal spazi ca sot (cjale il [[Jutori:Contignûts|jutori]] par altris informazions). Se tu sês ca par erôr, frache semplicementri il boton '''Indaûr''' dal to sgarfadôr.",
'newimages' => "Galarie dai gnûfs files",
'newmessages' => "Tu âs $1.",
'newmessageslink' => "gnûf(s) messaç(s)",
'newpage' => "Gnove pagjine",
'newpageletter' => "G",
'newpages' => "Gnovis pagjinis",
'newpassword'	=> 'Gnove peraule clâf',
'newtitle' => "Al gnûf titul",
'newusersonly' => "(dome gnûfs utents)",
'newwindow' => "(al vierç un gnûf barcon)",
'nextdiff' => "Prossime difarence &rarr;",
'nlinks' => "$1 leams",
'noarticletext' => "(Par cumò nol è nuie in cheste pagjine)",
'nocontribs'    => 'Nissun cambiament che al rispiete chescj criteris cjatât.',
'nocredits' => 'Nissune informazion sui ricognossiments disponibil par cheste pagjine.',
'nogomatch' => "No esist nissune pagjine cun chest titul esat, provant la ricercje in dut il test.",
'nohistory' => "Nol è presint un storic des modifichis par cheste pagjine.",
'noimages' => "Nuie di viodi.",
'nolinkshere' => "Nissune pagjine e à leams a chest articul",
'nolinkstoimage' => 'No son pagjinis leadis a chest file.',
'nowatchlist'		=> 'No tu stâs tignint di voli nissun element.',
'nov' => "Nov",
'november' => "Novembar",
'nowatchlist' => "Nissun element al è tignût di voli.",
'nstab-category' => "Categorie",
'nstab-help' => "Jutori",
'nstab-image' => "Figure",
'nstab-main' => "Articul",
'nstab-media' => "Media",
'nstab-mediawiki' => "Messaç",
'nstab-special' => "Speciâl",
'nstab-template' => "Model",
'nstab-user' => "Pagjine dal utent",
'nstab-wp' => "Informazions",
'numauthors' => 'Numar di autôrs diviers (articul): $1',
'numedits' => 'Numar di modifichis (articul): $1',
'numtalkauthors' => 'Numar di autôrs diviers (pagjine di discussion): $1',
'numtalkedits' => 'Numar di modifichis (pagjine di discussion): $1',
'numwatchers' => 'Numar di chei che e àn cjalât: $1',
'oct' => "Otu",
'october' => "Otubar",
'ok' => "OK",
'oldpassword'	=> 'Vecje peraule clâf',
'orig' => "orig",
'orphans'		=> 'Pagjinis solitaris',
'othercontribs' => 'Basât sul lavôr di $1.',
'otherlanguages' => "Altris lenghis",
'others' => 'altris',
'pagemovedsub'	=> 'Movude cun sucès',
'pagemovedtext' => "Pagjine \"[[$1]]\" movude in \"[[$2]]\".",
'passwordtooshort' => "La tô peraule clâf e je masse curte, e à di jessi lungje almancul $1 caratars.",
'popularpages'	=> 'Pagjinis popolârs',
'portal' => "Ostarie",
'portal-url' => "Vichipedie:Ostarie",
'poweredby' => "{{SITENAME}} e dopre [http://www.mediawiki.org/ MediaWiki], un motôr wiki a risultive vierte.",
'powersearch' => "Cîr",
'powersearchtext' => "
Cîr tai nons dai spazis :<br />
$1<br />
$2 Liste redirezions &nbsp; Cîr $3 $9",
'preferences' => "Preferencis",
'prefs-personal' => 'Dâts utents',
'prefs-rc' => 'Ultins cambiaments & stubs',
'prefs-misc' => 'Variis',
'prefs-help-realname' 	=> "* Non vêr (opzionâl): se tu sielzis di inserîlu al vignarà doprât par dâti un ricognossiment dal tô lavôr.",
'prefs-help-email'      => "* Email (opzionâl): Permet ai altris di contatâti vie la to pagjine utent o di discussion cence scugnî mostrâ a ducj la tô identitât.",
'prefslogintext' => "Tu sês jentrât come \"$1\".
Il to numar identificatîf interni al è $2.

Cjale [[Project:Preferencis utent]] par un aiût a lis diviersis opzions.",
'preview' => "Anteprime",
'previewnote' => "Visiti che cheste e je dome une anteprime, e no je stade ancjemò salvade!",
'previousdiff' => "&larr; Difarence precedente",
'printableversion' => "Version stampabil",
'printsubtitle' => "(Articul dal sît {{SERVER}})",
'protect' => "Protêç",
'protectcomment' => "Reson pe protezion",
'protectedarticle' => "$1 protezût",
'protectedpage' => "Pagjine protezude",
'protectedtext' => "Cheste pagjine e je stade blocade par prevignî la modifiche; a son
diviersis resons par chest fat, cjale par plasê
[[Project:Pagjinis protezudis]].

Tu puedis instès viodi e copiâ la risultive di cheste pagjine:",
'protectmoveonly' => "Protêç dome dai spostaments",
'protectpage' => "Protêç pagjine",
'protectsub' => "(Protezint \"$1\")",
'protectthispage' => "Protêç cheste pagjine",
'qbbrowse' => "Sgarfe",
'qbedit' => "Modifiche",
'qbfind' => "Cjate",
'qbmyoptions'	=> "Mês pagjinis",
'qbpageinfo'	=> "Contest",
'qbpageoptions' => "Cheste pagjine",
'qbsettings' => "Sbare svelte",
'qbspecialpages' => "Pagjinis speciâls",
'randompage' => "Une pagjine a câs",
'rclinks' => "Mostre i ultins $1 cambiaments tes ultimis $2 zornadis<br />$3",
'rclistfrom' => "Mostre i ultins cambiaments dal $1",
'rcnote' => "Ca sot tu cjatis i ultins <strong>$1</strong> cambiaments tes ultimis <strong>$2</strong> zornadis.",
'recentchanges' => "Ultins cambiaments",
'recentchangesall' => 'ducj',
'recentchangescount' => "Numar di titui tai ultins cambiaments",
'recentchangeslinked' => "Cambiaments leâts",
'recentchangestext' => "Cheste pagjine e mostre i plui recents cambiaments inte Vichipedie.",
'rclsub'		=> "(aes pagjinis leadis di \"$1\")",
'redirectedfrom' => "(Inviât ca di $1)",
'remembermypassword' => "Visiti di me",
'removedwatch'		=> 'Gjavade de liste',
'removedwatchtext' 	=> "La pagjine \"$1\" e je stade gjavade de liste di chês tignudis di voli.",
'resetprefs'	=> 'Predeterminât',
'restorelink' => "$1 modifichis eliminadis",
'restrictedpheading'	=> 'Pagjinis speciâls cun restrizions',
'resultsperpage' => 'Risultâts par pagjine',
'retrievedfrom' => "Cjapât fûr di $1",
'returnto' => "Torne a $1.",
'retypenew'		=> 'Torne a scrivi chê gnove',
'reupload' => "Torne a cjamâ sù",
'revertimg' => "rip",
'revertmove'	=> 'ripristine',
'revhistory' => "Storic des revisions",
'rows' => "Riis",
'saturday' => "Sabide",
'savearticle' => "Salve la pagjine",
'savedprefs' => "Lis preferencis a son stadis salvadis",
'savefile' => "Salve file",
'saveprefs' => "Salve lis preferencis",
'scarytranscludedisabled' => '[Inclusion dai interwikis no ative]',
'scarytranscludefailed' => '[Recupar dal model falît par $1; o si scusin]',
'scarytranscludetoolong' => '[URL masse lungje; o si scusin]',
'search' => "Cîr",
'searchresults' => "Risultâts de ricercje",
'searchresulttext' => "Par plui informazions su lis ricercjis in {{SITENAME}}, cjale [[Project:Ricercje|Ricercje in {{SITENAME}}]].",

'sep' => "Set",
'september' => "Setembar",
'sharedupload' => 'Chest file al è condivîs e al pues jessi doprât di altris progjets.',
'shareduploadwiki' => 'Cjale par plasê la [pagjine di descrizion dal file $1] par altris informazions.',
'shareddescriptionfollows' => '-',
'shortpages'	=> 'Pagjinis curtis',
'show' => "mostre",
'showbigimage' => "Discjame version a alte risoluzion ($1x$2, $3 KB)",
'showdiff'	=> 'Mostre cambiaments',
'showhideminor' => "$1 piçulis modifichis | $2 bots | $3 utents jentrâts | $4 modifichis verificadis",
'showlast'		=> "Mostre i ultins $1 files ordenâts $2.",
'showpreview' => "Mostre anteprime",
'showtoc' => "mostre",
'sig_tip' => "La tô firme cun ore e date",
'sitestats' => "Statistichis dal sît",
'sitestatstext' => "Tu puedis cjatâ in dut '''$1''' pagjine inte base di dâts.
Chest numar al inclût pagjinis \"discussion\", pagjinis su la {{SITENAME}}, pagjinis cun pocjis peraulis, reindirizaments, e altris che probabilmentri no si puedin considerâ pardabon come pagjinis di contignût.
Gjavant chestis, o vin '''$2''' pagjinis che a son probabilmentri pagjinis di contignût legjitimis.

O vin vût in dut '''$3''' viodudis de pagjinis e '''$4''' modifichis aes pagjinis di cuant che la wiki e je stade implantade.
Chest al vûl dî une medie di '''$5''' modifichis par pagjine, e '''$6''' viodudis par modifiche.",
'sitesubtitle' => "L'enciclopedie libare",
'sitesupport' => 'Doninus',
'sitesupport-url' => 'Project:Supuarte il sît',
'siteuser' => 'Utent di {{SITENAME}} $1',
'siteusers' => 'Utents di {{SITENAME}} $1',
'skin'			=> 'Mascare',
'sourcefilename' => 'Non dal file origjinâl',
'specialloguserlabel' => "Utent:",
'speciallogtitlelabel' => "Titul: ",
'specialpage' => "Pagjine speciâl",
'specialpages' => "Pagjinis speciâls",
'spheading'		=> 'Pagjinis speciâls par ducj i utents',
'statistics' => "Statistichis",
'subcategories' => "Sot categoriis",
'successfulupload' => "Cjamât sù cun sucès",
'summary' => "Somari",
'sunday' => "Domenie",
'tableform' => "tabele",
'tagline' => "Di {{SITENAME}}",
'talk' => "Discussion",
'talkexists'	=> "'''La pagjine e je stade movude cun sucès, ma no si à podût movi la pagjine di discussion parcè che e esist za tal gnûf titul. Trasferìs il contignût a man par plasê.'''",
'talkpage' => "Fevelin di cheste pagjine",
'talkpagemoved' => "Ancje la pagjine di discussion corispondente e je stade movude.",
'talkpagenotmoved' => "La pagjine di discussion corispondente <strong>no</strong> je stade movude.",
'templatesused' => "Modei doprâts par cheste pagjine:",
'textboxsize' => "Modifiche",
'thisisdeleted' => "Vuelistu cjalâ o ripristinâ $1?",
'thumbnail-more' => "Slargje",
'thursday' => "Joibe",
'timezonelegend' => "Fûs orari",
'toc' => "Indis",
'tog-highlightbroken' => 'Mostre leams sbaliâts <a href="" class="new">cussì</a> (invezit di cussì<a href="" class="internal">?</a>).',
'tog-justify'	=> 'Justifiche paragraf',
'tog-hideminor' => 'Plate modifichis piçulis tai ultins cambiaments',
'tog-usenewrc' => 'Ultins cambiaments avanzâts (JavaScript)',
'tog-numberheadings' => 'Numerazion automatiche dai titui',
'tog-editondblclick' => 'Modifiche pagjinis fracant dôs voltis (JavaScript)',
'tog-editsection'		=> 'Inserìs un leam [modifiche] pe editazion veloç di une sezion',
'tog-editsectiononrightclick'	=> 'Modifiche une sezion fracant cul tast diestri<br /> sui titui des sezions (JavaScript)',
'tog-showtoc'			=> 'Mostre indis (par pagjinis cun plui di 3 sezions)',
'tog-editwidth' => 'Il spazi pe modifiche al è larc il plui pussibil',
'tog-minordefault' => 'Imposte come opzion predeterminade dutis lis modifichis come piçulis',
'tog-previewontop' => 'Mostre anteprime parsore dal spazi pe modifiche',
'tog-previewonfirst' => 'Mostre anteprime te prime modifiche',
'tog-nocache' => 'No stâ tignî in memorie (caching) lis pagjinis',
'tog-enotifwatchlistpages' 	=> 'Mandimi une email se la pagjine e gambie',
'tog-enotifusertalkpages' 	=> 'Mandimi une email cuant che la mê pagjine di discussion e gambie',
'tog-enotifminoredits' 		=> 'Mandimi une email ancje pes modifichis piçulis ae pagjine',
'tog-enotifrevealaddr' 		=> 'Distapone fûr il gno recapit email tai messaçs di notifiche',
'tog-shownumberswatching' 	=> 'Mostre il numar di utents che a stan tignint di voli',
'tog-fancysig' => 'Firmis crudis (cence leam automatic)',
'tog-externaleditor' => 'Dopre editôr esterni come opzion predeterminade',
'tog-externaldiff' => 'Dopre editôr difarencis esterni come opzion predeterminade',
'tog-rememberpassword' => 'Visiti tes prossimis sessions',
'tog-showtoolbar'		=> 'Mostre sbare dai imprescj di modifiche (JavaScript)',
'tog-underline' => 'Sotlinee leams',
'tog-watchdefault' => 'Zonte pagjinis che o modifichi inte liste di chês tignudis di voli',
'toolbox' => "imprescj",
'tooltip-compareselectedversions' => 'Viôt lis difarencis framieç lis dôs versions di cheste pagjine selezionadis. [alt-v]',
'tooltip-diff' => 'Mostre i cambiaments che tu âs fat al test. [alt-d]',
'tooltip-minoredit' => "Segne cheste come une piçule modifiche [alt-i]",
'tooltip-preview' => "Anteprime dai tiei cambiaments, doprile par plasê prime di salvâ! [alt-p]",
'tooltip-save' => "Salve i tiei cambiaments [alt-s]",
'tooltip-search' => "Cîr in cheste wiki [alt-f]",
'tooltip-watch' => "Zonte cheste pagjine ae liste di chês tignudis di voli [alt-w]",
'tuesday' => "Martars",
'ucnote'        => "Ca sot a son i ultins <b>$1</b> cambiaments dal utent tes ultimis <b>$2</b> zornadis.",
'uclinks'       => "Viôt i ultins $1 cambiaments; viôt lis ultimis $2 zornadis.",
'uctop' => " (su)",
'uncategorizedpages'	=> 'Pagjinis cence categorie',
'uncategorizedcategories'	=> 'Categoriis cence categorie',
'underline-always' => "Simpri",
'underline-never' => "Mai",
'underline-default' => "Predeterminât dal sgarfadôr",
'unusedimages'	=> 'Files no doprâts',
'unwatch' => 'No stâ tignî di voli',
'unwatchthispage' 	=> 'No stâ tignî di voli plui',
'updated' => "(Inzornât)",
'upload' => "Cjame sù un file",
'uploadbtn' => "Cjame sù un file",
'uploaddisabled' => "Nus displâs, par cumò no si pues cjamâ sù robe.",
'uploadedfiles' => "Files cjamâts sù",
'uploadedimage' => "cjamât sù \"$1\"",
'uploaderror' => "Erôr cjamant sù",
'uploadlink' => "Cjame sù figuris",
'uploadlog'		=> 'regjistri cjamâts sù',
'uploadlogpagetext' => 'Ca sot e je une liste dai file cjamâts su di recent.',
'uploadnologin' => 'No jentrât',
'userexists'	=> "Il non utent inserît al è za doprât. Sielç par plasê un non diferent.",
'userlogin' => "Regjistriti o jentre",
'userlogout' => "Jes",
'userpage' => "Cjale pagjine dal utent",
'userstats' => "Statistichis dai utents",
'userstatstext'  => "A son '''$1''' utents regjistrâts, di chescj '''$2''' (il '''$4%''') a son aministradôrs (cjale $3).",
'val_tab' => "Convalide",
'val_version' => "Version",
'val_version_of' => "Version di $1",
'val_view_version' => "Cjale cheste version",
'validate' => "Convalide la pagjine",
'version' => "Version",
'viewcount' => "Cheste pagjine e je stade viodude $1 voltis.",
'viewprevnext' => "Cjale ($1) ($2) ($3).",
'viewsource' => "Cjale risultive",
'wantedpages'	=> 'Pagjinis desideradis',
'watch' => "Ten di voli",
'watchlist' => "Tignûts di voli",
'watchlistall1' => "ducj",
'watchlistall2' => "dutis",
'watchlistcontains' => "Tu stâs tignint di voli $1 pagjinis.",
'watchlistsub'		=> "(par l'utent \"$1\")",
'watchnochange' 	=> 'Nissun element di chei tignûts di voli al è stât modificât tal periodi mostrât.',
'watchdetails'		=> "* $1 pagjinis tignudis di voli cence contâ lis pagjinis di discussion
* [[Special:Watchlist/edit|Mostre e modfiche la liste complete]]",
'watchnologin'		=> 'No tu sês jentrât',
'watchnologintext'	=> "Tu 'nd âs di [[Speciâl:Userlogin|jentrâ]] par modificâ la liste des pagjinis tignudis di voli.",
'watchthis' => "Ten di voli cheste pagjine",
'watchthispage' => "Ten di voli cheste pagjine",
'wednesday' => "Miercus",
'welcomecreation' => "== Mandi e benvignût $1! ==

La tô identitât e je stade creade. No stâ dismenteâti di gambiâ lis preferencis de {{SITENAME}}.",
'whatlinkshere' => "Leams a chest articul",
'wikipediapage' => "Cjale pagjine dal progjet",
'wlheader-enotif' 		=> "* Notifiche par email ativade.",
'wlheader-showupdated'   => "* Lis pagjinis gambiadis de ultime volte che tu lis âs cjaladis a son mostradis in '''gruessut'''",
'wlnote' 		=> 'Ca sot a son i ultins $1 cambiaments tes ultimis <b>$2</b> oris.',
'wlshowlast' 		=> 'Mostre ultimis $1 oris $2 zornadis $3',
'wlsaved'		=> 'This is a saved version of your watchlist.',
'wlhideshowown'   	=> '$1 lis mês modifichis.',
'wlshow'		=> 'Mostre',
'wlhide'		=> 'Plate',
'wrongpassword' => "La peraule clâf zontade no je juste. Torne par plasê a provâ.",
'yourdiff' => "Difarencis",
'youremail'		=> "Email *",
'yourlanguage' => "Lenghe di mostrâ",
'yourpassword'	=> "Peraule clâf",
'yourpasswordagain' => "Torne a scrivile",
'yourrealname'		=> "Non vêr *",
'yourvariant' => 'Varietât',
);

class LanguageFur extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesFur;
		return $wgNamespaceNamesFur;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsFur;
		return $wgQuickbarSettingsFur;
	}

	function getSkinNames() {
		global $wgSkinNamesFur;
		return $wgSkinNamesFur;
	}


	function getDateFormats() {
		return false;
	}


	function date( $ts, $adj = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = (0 + substr( $ts, 6, 2 )) . " di " .
		  $this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) .
		  " " . substr( $ts, 0, 4 );
		return $d;
	}

	function timeanddate( $ts, $adj = false ) {
		return $this->date( $ts, $adj ) . " a lis " . $this->time( $ts, $adj );
	}

	var $digitTransTable = array(
		',' => "\xc2\xa0", // @bug 2749
		'.' => ','
	);

	function formatNum( $number ) {
		return strtr($number, $this->digitTransTable);
	}


	function getMessage( $key ) {
		global $wgAllMessagesFur;
		if( isset( $wgAllMessagesFur[$key] ) ) {
			return $wgAllMessagesFur[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

}

?>

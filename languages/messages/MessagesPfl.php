<?php
/** Pälzisch (Pälzisch)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Als-Holder
 * @author Imbericle
 * @author M-sch
 * @author Manuae
 * @author SPS
 * @author Xqt
 */

$fallback = 'de';

$namespaceNames = array(
	NS_MEDIA            => 'Medium',
	NS_SPECIAL          => 'Schbezial',
	NS_TALK             => 'Babble',
	NS_USER             => 'Middawaida',
	NS_USER_TALK        => 'Middawaida_Dischbediere',
	NS_PROJECT_TALK     => '$1_Dischbediere',
	NS_FILE             => 'Dadai',
	NS_FILE_TALK        => 'Dadai_Dischbediere',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_Dischbediere',
	NS_TEMPLATE         => 'Vorlach',
	NS_TEMPLATE_TALK    => 'Vorlach_Dischbediere',
	NS_HELP             => 'Hilf',
	NS_HELP_TALK        => 'Hilf_Dischbediere',
	NS_CATEGORY         => 'Sachgrubb',
	NS_CATEGORY_TALK    => 'Sachgrubb_Dischbediere',
);

$namespaceAliases = array(
	# German namespaces
	'Medium'                 => NS_MEDIA,
	'Spezial'                => NS_SPECIAL,
	'Diskussion'             => NS_TALK,
	'Benutzer'               => NS_USER,
	'Benutzer_Diskussion'    => NS_USER_TALK,
	'Benudzer'               => NS_USER,
	'Benudzer_Dischbediere'  => NS_USER_TALK,
	'$1_Diskussion'          => NS_PROJECT_TALK,
	'Datei'                  => NS_FILE,
	'Datei_Diskussion'       => NS_FILE_TALK,
	'MediaWiki_Diskussion'   => NS_MEDIAWIKI_TALK,
	'Vorlage'                => NS_TEMPLATE,
	'Vorlage_Diskussion'     => NS_TEMPLATE_TALK,
	'Hilfe'                  => NS_HELP,
	'Hilfe_Diskussion'       => NS_HELP_TALK,
	'Kategorie'              => NS_CATEGORY,
	'Kategorie_Diskussion'   => NS_CATEGORY_TALK,
	'Kadegorie'              => NS_CATEGORY,
	'Kadegorie_Dischbediere' => NS_CATEGORY_TALK,
);

$messages = array(
# User preference toggles
'tog-underline' => 'Lingg unnaschdraische',
'tog-hideminor' => 'Vaschdegg klääne Bearwaidunge',
'tog-hidepatrolled' => 'Vaschdegg gsischdede Ännarunge',
'tog-extendwatchlist' => 'Zaisch alle Ännarunge unn ned nur die ledschde',
'tog-showtoolbar' => "Wergzaisch fas Beawaide zaische (dodezu brauchd's JavaScript)",
'tog-previewontop' => 'Vorbligg owwahalwb vum Beaawaidungsfenschda zaische',
'tog-previewonfirst' => 'Zaischen Vorbligg baim erschdemol Schaffe',
'tog-oldsig' => 'Voahonneni Unnaschfrid',
'tog-uselivepreview' => 'Uuvazeschada Vorbligg (bneedischd JavaScript) (vasugswais)',
'tog-showhiddencats' => 'Zaisch vaschdeglde Grubbe',

'underline-always' => 'Imma',
'underline-never' => 'Gaaned',
'underline-default' => 'Des nemme, wum Browser gsachd hoschd.',

# Font style option in Special:Preferences
'editfont-sansserif' => 'Sans-serif Schrifd',
'editfont-serif' => 'Serif Schrifd',

# Dates
'sunday' => 'Sundaach',
'monday' => 'Mondaach',
'tuesday' => 'Dienschdaach',
'wednesday' => 'Middwoch',
'thursday' => 'Dunnaschdaach',
'friday' => 'Fraidaach',
'saturday' => 'Sõmschdaach',
'sun' => 'Su',
'mon' => 'Mo',
'tue' => 'Di',
'wed' => 'Mi',
'thu' => 'Du',
'fri' => 'Fr',
'sat' => 'So',
'january' => 'Janua',
'february' => 'Februa',
'march' => 'März',
'april' => 'Abril',
'may_long' => 'Mai',
'june' => 'Juni',
'july' => 'Juli',
'august' => 'Auguschd',
'september' => 'Sebdemba',
'october' => 'Ogdowa',
'november' => 'Nowemba',
'december' => 'Dezemba',
'january-gen' => 'Janua',
'february-gen' => 'Februa',
'march-gen' => 'März',
'april-gen' => 'Abril',
'may-gen' => 'Mai',
'june-gen' => 'Juni',
'july-gen' => 'Juli',
'august-gen' => 'Auguschd',
'september-gen' => 'Sebdemba',
'october-gen' => 'Ogdowa',
'november-gen' => 'Nowemba',
'december-gen' => 'Dezemba',
'jan' => 'Jan',
'feb' => 'Feb',
'mar' => 'Mär',
'apr' => 'Abr',
'may' => 'Mai',
'jun' => 'Jun',
'jul' => 'Jul',
'aug' => 'Aug',
'sep' => 'Seb',
'oct' => 'Ogd',
'nov' => 'Nov',
'dec' => 'Dez',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Sachgrubb|Sachgrubbe}}',
'category_header' => 'Saide inde Sachgrubb „$1“',
'subcategories' => 'Unnagrubbe',
'category-media-header' => 'Medje indɐ Sachgrubb „$1“',
'category-empty' => '"Die Sachgrubb hodd kä Said odda Medje."',
'hidden-categories' => '{{PLURAL:$1|Vaschdegldi Sachgrubb|Vaschdeglde Sachgrubbe}}',
'hidden-category-category' => 'Verschdegelde Grubbe',
'category-subcat-count' => '{{PLURAL:$2|Die Sachgrubb hod die Unnagrubb.|Die Sachgrubb hod {{PLURAL:$1|Unnagrubb|$1 Unnagrubbe}}, vun gsomd $2.}}',
'category-subcat-count-limited' => 'Die Sachgrubb hod die {{PLURAL:$1|Unagrubb|$1 Unagrubbe}}.',
'category-article-count' => '{{PLURAL:$2|Indɐ Sachgrubb hodds die Said.|Die {{PLURAL:$1|Said|$1 Saide}} gibbds inde Sachgrubb, vun gsomd $2.}}',
'category-article-count-limited' => 'Die {{PLURAL:$1|Said|$1 Saide}} hodds inde Sachgrubb.',
'category-file-count' => "{{PLURAL:$2|Die Sachgrubb hodd ä Said.|Die {{PLURAL:$1|Said isch änni vun $2 Saide:|S'werren $1 vun gsomd $2 Saide gzaischd:}}}}",
'category-file-count-limited' => 'Die {{PLURAL:$1|Dadai|$1 Dadije}} hodds inde Sachgrubb.',
'listingcontinuesabbrev' => '(Waida)',
'index-category' => 'Eafassdi Saide',
'noindex-category' => 'Saide, wu ned im Vazaischnis sinn',

'about' => 'Iwwa',
'newwindow' => '(werd innem naije Fenschda uffgmachd)',
'cancel' => 'Abbresche',
'moredotdotdot' => 'Mea …',
'mypage' => 'Said',
'mytalk' => 'Dischbediere',
'navigation' => 'Nawigadzion',
'and' => '&#32;unn',

# Cologne Blue skin
'qbfind' => 'Finne',
'qbbrowse' => 'Duaschschdewere',
'qbedit' => 'Beawaide',
'qbpageoptions' => 'Die Said',
'qbmyoptions' => 'Moi Saide',
'qbspecialpages' => 'Schbezialsaide',
'faq' => 'Ofd gschdeldi Froche',

# Vector skin
'vector-action-addsection' => 'Abschnidd dzufiesche',
'vector-action-delete' => 'Lesche',
'vector-action-move' => 'Vaschiewe',
'vector-action-protect' => 'Schidze',
'vector-action-undelete' => 'Zriggbringe',
'vector-view-create' => 'Õleesche',
'vector-view-edit' => 'Beawaide',
'vector-view-history' => 'Dadaigschischd',
'vector-view-view' => 'Lese',
'vector-view-viewsource' => 'Gwelltegschd zaische',
'actions' => 'Maßnõhme',
'namespaces' => 'Nõmensreum',
'variants' => 'Tibbe',

'errorpagetitle' => 'Irrdumm',
'returnto' => 'Zrick zu $1.',
'tagline' => 'Vun {{SITENAME}}',
'help' => 'Unaschdidzung',
'search' => 'Nochgugge',
'searchbutton' => 'Gugg',
'go' => 'Ausfiere',
'searcharticle' => 'Ausfiare',
'history' => 'Gschischd vunde Said',
'history_short' => 'Gschischd',
'printableversion' => 'Drugg-Õsischd',
'permalink' => 'Schdendischa Lingg',
'print' => 'Drugge',
'view' => 'Ogugge',
'edit' => 'Beawaide',
'create' => 'Õleesche',
'editthispage' => 'Die Said beawaide',
'create-this-page' => 'Mach die Said',
'delete' => 'Lesche',
'deletethispage' => 'Lesch die Said',
'undelete_short' => '{{PLURAL:$1|ä Ännarung|$1 Ännarunge}} widdaheaschdelle',
'viewdeleted_short' => 'Zaisch {{PLURAL:$1|ä gleschdi Ännarung|$1 gleschde Ännarunge}}',
'protect' => 'schidze',
'protect_change' => 'ännare',
'protectthispage' => 'Die Said schidze',
'unprotect' => 'Saideschudz ännare',
'newpage' => 'Naiji Said',
'talkpage' => 'Iwwa die Said dischbediere',
'talkpagelinktext' => 'Dischbediere',
'specialpage' => 'Schbezielli Said',
'personaltools' => 'Persenlischs Wergzaisch',
'postcomment' => 'Naije Abschnidd',
'articlepage' => 'Inhald õgugge',
'talk' => 'Dischbediere',
'views' => 'Uffruf',
'toolbox' => 'Wergzaischkischd',
'userpage' => 'Middawaidasaid õgugge',
'projectpage' => 'Brojegdsaid õgugge',
'imagepage' => 'Dadaisaid õgugge',
'mediawikipage' => 'Nochrischd õgugge',
'templatepage' => 'Voalach õgugge',
'viewhelppage' => 'Hilf õgugge',
'categorypage' => 'Zaisch die Kadegorie',
'viewtalkpage' => 'Zaischs Gbabbl',
'otherlanguages' => 'In õnnare Schbroche',
'redirectedfrom' => '(Nochgschiggd worre vun $1)',
'redirectpagesub' => 'Nochschigg-Said',
'lastmodifiedat' => 'Die Said ischs ledschde Mol gännad worre õm $1, õm $2.',
'viewcount' => 'Die Said isch bis jedz {{PLURAL:$1|$1|$1}} mol uffgrufe worre.',
'protectedpage' => 'Said schidze',
'jumpto' => 'Hubs uff:',
'jumptonavigation' => 'Nawigadzion',
'jumptosearch' => 'Nochgugge',
'view-pool-error' => 'Dudma leed, die Maschine isch graad iwwalaschd.
Zu vieli Middawaida guggn grad die Said õ.
Waad ä bissl un brobieas nomol.

$1',
'pool-errorunknown' => 'Ubkonnde Irrdumm',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage).
'aboutsite' => 'Iwwa {{SITENAME}}',
'aboutpage' => 'Project:Iwwa',
'copyright' => 'Was do drin schded isch unna $1 vafieschba.',
'copyrightpage' => '{{ns:project}}:Urhewareschd',
'currentevents' => 'Aggduelli Gscheniss',
'currentevents-url' => 'Project: Leschdi Gschneniss',
'disclaimers' => 'Hafdungsausschluß',
'disclaimerpage' => 'Project:Impressum',
'edithelp' => 'Unaschdizung fas Beawaide',
'helppage' => 'Help:Inhald',
'mainpage' => 'Schdadsaid',
'mainpage-description' => 'Schdadsaid',
'policy-url' => 'Project:Grundsedz',
'portal' => '{{SITENAME}}-Bordal',
'portal-url' => 'Project:Gmoinschafdsbordal',
'privacy' => 'Daadeschuds',
'privacypage' => 'Project:Daadeschuds',

'badaccess' => 'Ned genuch Reschd',

'ok' => 'Alla gud',
'retrievedfrom' => 'Vun "$1"',
'youhavenewmessages' => 'Du hoschd $1 ($2).',
'newmessageslink' => 'naije Nochrischde',
'newmessagesdifflink' => 'ledschdi Ännarung',
'newmessagesdifflinkplural' => 'ledschdi {{PLURAL:$1|Ännarung|Ännarunge}}',
'youhavenewmessagesmulti' => 'Do hoschd ä Nochrischd grischd: $1',
'editsection' => 'beawaide',
'editold' => 'beawaide',
'viewsourceold' => 'Gwelltegschd õgugge',
'editlink' => 'beawaide',
'viewsourcelink' => 'Gwell õgugge',
'editsectionhint' => 'Deel ännare: $1',
'toc' => 'Inhald',
'showtoc' => 'zaische',
'hidetoc' => 'vaschdeggle',
'collapsible-collapse' => 'Oiglabbe',
'collapsible-expand' => 'Uffglabbe',
'thisisdeleted' => '$1 õgugge odda widdaheaschdelle?',
'viewdeleted' => '$1 zaische?',
'restorelink' => '{{PLURAL:$1|ä gleschdi Ännarung|$1 gleschde Ännarunge}}',
'site-rss-feed' => '$1 RSS-Feed',
'site-atom-feed' => '$1 Atom-Feed',
'page-rss-feed' => '"$1" RSS-Feed',
'page-atom-feed' => '"$1" Atom-Feed',
'red-link-title' => '$1 (Said hodds nedd)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Said',
'nstab-user' => 'Middawaidasaid',
'nstab-media' => 'Medije',
'nstab-special' => 'Schbezialsaid',
'nstab-project' => 'Bordal',
'nstab-image' => 'Dadai',
'nstab-mediawiki' => 'Middeelung',
'nstab-template' => 'Vorlach',
'nstab-help' => 'Unaschdidzung',
'nstab-category' => 'Sachgrubb',

# Main script and global functions
'nosuchaction' => 'Des hodds nedd',
'nosuchspecialpage' => 'Schbezialsaid hodds nedd',

# General errors
'error' => 'Irrdumm',
'databaseerror' => 'Daadebongg-Irrdumm',
'readonly' => 'Daadebongg blogiead',
'missing-article' => 'De Tegschd fa „$1“ $2 isch inde Daadebongg nedd gfunne worre.

Noamalawees heeßd des, dass die Said gleschd worre isch.

Wonnse des awwa nedd isch, hoschd villaischdn Irdumm inde Daadebongg gfunne.
Bidde meldsm [[Special:ListUsers/sysop|Adminischdrador]], un gebbde URL dzu õ.',
'missingarticle-rev' => '(Ausgawenumma#: $1)',
'missingarticle-diff' => '(Unnaschied: $1, $2)',
'internalerror' => 'Inderna Irrdumm',
'internalerror_info' => 'Inderna Irrdumm: $1',
'fileappenderrorread' => 'Beim dzugiesche hoddma „$1“ nedd lese kenne.',
'fileappenderror' => '"$1" hoddma nedd zu "$2" dzugiesche kenne.',
'filecopyerror' => '"$1" hoddma nedd zu "$2" kobiere kenne.',
'filerenameerror' => 'Die Said "$1" hoddma nedd uff "$2" umbenenne kenne.',
'filedeleteerror' => '"$1" hoddma nedd lesche kenne.',
'directorycreateerror' => 'S\'Vazaischnis "$1" hoddma nedd mache kenne.',
'filenotfound' => '"$1" hoddma nedd finne kenne.',
'fileexistserror' => '"$1" hodds schun: do hoddma nix schraiwe kenne.',
'unexpected' => 'Uueawadeda Wead: "$1"="$2".',
'formerror' => 'Irrdumm: hoddma nedd mache kenne.',
'badarticleerror' => 'Des geed nedd uffde Said.',
'cannotdelete-title' => '"$1" komma nedd lesche',
'badtitle' => 'Schleschde Didl',
'badtitletext' => 'De Tidl vunde õgfordad Said isch nedd gildisch, lea, oddan nedd gildische Lingg vunem õnnare Wiki.
S konn soi, dasses ä odda mea Zaische drin hodd, wu im Tidl vunde Said nedd gbrauchd werre dirfn.',
'viewsource' => 'Gwelltegschd õgugge',
'viewsource-title' => "D'Tegschd vun $1 õgugge",
'viewsourcetext' => 'Konschdas õgugge un abschraiwe',
'viewyourtext' => 'Konschda doi Eawed uff de Said õgugge un abschraiwe:',
'ns-specialprotected' => 'Do komma nedd drõ schaffe',
'exception-nologin' => 'Bischd nedd õgmeld',

# Virus scanner
'virus-unknownscanner' => 'Uubekonnda Viresucha:',

# Login and logout pages
'welcomeuser' => 'Willkumme, $1!',
'yourname' => 'Middawaidanõme:',
'yourpassword' => 'Kennword:',
'yourpasswordagain' => 'Kennword nomol oigewe:',
'remembermypassword' => 'Moi Kennword uffm Rechna merge (hegschdns fa $1 {{PLURAL:$1|Daach|Daach}})',
'login' => 'Õmelde',
'nav-login-createaccount' => 'Õmelde / Kondo õleesche',
'loginprompt' => 'Cookies mugschd fa {{SITENAME}} schun õhawe.',
'userlogin' => 'Õmelde / Kondo õleesche',
'userloginnocreate' => 'Oilogge',
'logout' => 'Uffhere',
'userlogout' => 'Uffhere',
'nologin' => 'Hoschd noch kä Kondo? $1',
'nologinlink' => 'E Kondo õleesche',
'createaccount' => 'Kondo õleesche',
'gotaccount' => 'Hoschd schun ä Kondo? $1',
'gotaccountlink' => 'Õmelde',
'userlogin-resetlink' => 'Hoschd doi Daade vagesse?',
'createaccountreason' => 'Grund:',
'badretype' => 'Kennword bassd nedd',
'userexists' => 'De Middawaida hodds schun.
Nemmen onnare.',
'loginerror' => 'Irrdumm baim Õmelde',
'createaccounterror' => 'Kondo $1 komma nedd mache',
'loginsuccesstitle' => 'Konschd schaffe',
'login-userblocked' => 'De Middawaida deaf do nemme schaffe.',
'wrongpasswordempty' => 'Hoschds Kennword vagesse. Mags nomol.',
'passwordtooshort' => 'Kennword muss {{PLURAL:$1|1 Zaische|$1 Zaische}} hawe.',
'password-name-match' => 'Doi Kennword deaf nedd so heese wie du.',
'password-login-forbidden' => 'De Nõme uns Kennword sinn fabode.',
'mailmypassword' => 'Naijs Kennword iwwa E-Mail schigge',
'accountcreated' => 'Kondo õgleeschd',
'login-abort-generic' => 'Hodd nedd gklabbd - Abgbroche',
'loginlanguagelabel' => 'Schbrooch: $1',

# Change password dialog
'resetpass' => 'Kennword wegsle',
'oldpassword' => 'Alds Kennword',
'newpassword' => 'Naijes Kennword',
'retypenew' => 'Naijes Kennword nomol oigewe:',
'resetpass_forbidden' => 'Kennwerda komma nedd wegsle',
'resetpass-submit-loggedin' => 'Password wegsle',
'resetpass-submit-cancel' => 'Uffhere',
'resetpass-temp-password' => 'Bschrengds Kennword',

# Special:PasswordReset
'passwordreset' => 'Kennword zriggsedze',
'passwordreset-legend' => 'Kennword zriggsedze',
'passwordreset-username' => 'Middawaida:',
'passwordreset-capture' => 'E-Mail õgugge?',

# Special:ChangeEmail
'changeemail-none' => '(käni)',
'changeemail-cancel' => 'Uffhere',

# Edit page toolbar
'bold_sample' => 'Feddi Schrifd',
'bold_tip' => 'Feddi Schrifd',
'italic_sample' => 'Schebbi Schrifd',
'italic_tip' => 'Schebbi Schrifd',
'link_sample' => 'Schdischwoad',
'link_tip' => 'Inderna Lingg',
'extlink_sample' => 'http://www.example.com Linggtegschd',
'extlink_tip' => 'Exderna Lingg (uff http:// uffbasse)',
'headline_sample' => 'Schlaachzail',
'headline_tip' => 'Iwwaschrifd Ewene 2',
'nowiki_sample' => "Gebb do'n Tegschd oi, wu nedd uffberaid werd",
'nowiki_tip' => 'Wiki-Formatierunge ned beachde',
'image_tip' => 'Bildvawais',
'media_tip' => 'Dadailingg',
'sig_tip' => 'Doi Unnaschrifd midena Zaidõgawb',
'hr_tip' => 'Waagreschdi Linje (schbaasõm vawende)',

# Edit pages
'summary' => 'Iwwabligg:',
'subject' => 'Bedreff:',
'minoredit' => 'Des ische glänni Beawaidung',
'watchthis' => 'Die Said im Aach palde',
'savearticle' => 'Said schbaischere',
'preview' => 'Voaschau',
'showpreview' => 'Voaschau zaische',
'showlivepreview' => 'Live-Voaschau',
'showdiff' => 'Ännarunge zaische',
'anoneditwarning' => "'''Baßma uff:''' Du bischd ned õgemeld. Doi IP-Adress werd inde Gschischd vum Adiggl gschbaischad.",
'summary-preview' => 'Iwwabligg:',
'blockedtitle' => 'Middawaida isch gschbead',
'blockednoreason' => "s'hod kän Grund",
'whitelistedittext' => 'Mugschd disch $1 fas schaffe',
'loginreqtitle' => 'Mugschd disch õmelde',
'loginreqlink' => 'Õmelde',
'loginreqpagetext' => 'Mugschd disch $1 fas õgugge.',
'accmailtitle' => 'Kennword gschiggd',
'newarticle' => '(Naij)',
'newarticletext' => "Du bischdm Lingg nochgõnge zu enna Said, wus ganedd hodd.
Fa die Said õzleesche, konnschd do im Käschdl unne õfonge mid schraiwe (gugg [[{{MediaWiki:Helppage}}|Hilf]] fa mea Auskinfd).
Wonn do nedd hoschd heakumme wolle, drigg uff Browser uff '''Zrigg'''.",
'noarticletext' => 'Uffde Said hods noch kän Tegschd. Du konnschd uff õnnare Saide nochm [[Special:Search/{{PAGENAME}}|Oidrach gugge]], <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} Logbuchaidrach gugge, wu dezu kead],
odda [{{fullurl:{{FULLPAGENAME}}|action=edit}} die Said beawaide]</span>.',
'noarticletext-nopermission' => 'Do hods känn Tegschd.
Du konschd uff onnare Saide [[Special:Search/{{PAGENAME}}|faden Tidl gugge]], odda <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} in alle Logs gugge]</span>, awwa du hoschd kä Ealauwnis die Said zu mache.',
'updated' => '(Gännad)',
'note' => "'''Hiwes:'''",
'previewnote' => "'''Deng'g drõõ, dasses nua e Vorschau isch.'''
Doi Ännarunge sinn noch nedd gschbaischadd worre!",
'editing' => 'Õm $1 beawaide',
'creating' => 'Magsch $1',
'editingsection' => '$1 beawaide (de Deel)',
'editingcomment' => '$1 beawaide (de Deel)',
'editconflict' => 'Schdraid ums Ännare: $1',
'yourtext' => 'Doin Tegschd',
'storedversion' => 'Gschbaischerdi Version',
'yourdiff' => 'Unaschied',
'copyrightwarning' => "Baß uff, dass alli Baidräch fa {{SITENAME}} unna $2 vaeffendlischd werren (gugg $1 fa mea Enzlhaide).
Wonnsda ned basse dud, dass des wu gschriwwe hoschd, gännad un kopiead werre konn, donn duus do ned noischraiwe.<br />
Du gibbschd do a zu, dasses selwaschd gschriwwe hoschd odda vuna effendlischi, fraiji Gwell ('''public domain''') odda vuna ähnlichi fraiji Gwell hawe duschd.
'''Du do nix noi schraiwe, wa unnam Uahewareschd gschizd isch!'''",
'templatesused' => '{{PLURAL:$1|Vorlach wu uffde Said gbrauchd werd|Vorlache wu uffde Saide gbrauchd werren}}:',
'templatesusedpreview' => '{{PLURAL:$1|Vorlach wu inde Vorschau gbrauchd werd|Vorlache wu inde Vorschau gbrauchd werren}}:',
'template-protected' => '(gschizd)',
'template-semiprotected' => '(halwa-gschizd)',
'hiddencategories' => 'Die Said kerd zu vaschdeggelde {{PLURAL:$1|1 Sachgrubb|$1 Sachgrubbe}}:',
'permissionserrorstext-withaction' => 'Du därfschd nedd $2, weesch{{PLURAL:$1|m Grund|ede Grind}}:',
'recreate-moveddeleted-warn' => "'''Baßma uff: Du maggschd do ä Said, wuma frija schumol geleschd kabd hod.'''",
'moveddeleted-notice' => 'Die Said isch gleschd worre.
De Leschoidrach fa die Said isch do unne als Gwell õgewwe.',
'log-fulllog' => 'Alli Oidräsch vunde Logbischa õgugge',
'edit-conflict' => 'Schdraid ums Ännare.',

# Content models
'content-model-text' => 'Glaategschd',
'content-model-css' => 'CSS',

# Parser/template warnings
'post-expand-template-inclusion-warning' => "'''Baßma uff:''' Greeß vunde Vorlach isch iwwaschridde. Oinischi Vorlache werren ned bnuzd.",
'post-expand-template-inclusion-category' => 'Saide mid Vorlache, wu die Greeß iwwaschridde worre isch',
'post-expand-template-argument-warning' => "'''Baßma uff:''' Die Said hodd wenigschdns ä Vorlach midä Kenngreeß, wu groß werre dud. Die Kenngreeß wead do nedd õgeguggd.",
'post-expand-template-argument-category' => 'Saide, wu wegfallene Vorlachewead hawen.',

# History pages
'viewpagelogs' => 'Lochbischa fa die Said õgugge',
'currentrev' => 'Ledschdi Änarung',
'currentrev-asof' => 'Agduell Ausgab vun $1',
'revisionasof' => 'Iwwaawaidung vun $1',
'revision-info' => 'Ännarung vun $1 duasch $2',
'previousrevision' => '← Älderi Beawaidung',
'nextrevision' => 'Naijari Ausgawb →',
'currentrevisionlink' => 'Agduelli Ausgawb',
'cur' => 'jedzischi',
'next' => 'Negschd',
'last' => 'vorischi',
'page_first' => 'Easchd',
'page_last' => 'Ledschd',
'histlegend' => "Du konnschd zwää Ausgawe wehle un vaglaische.<br />
Ealaidarung: '''({{int:cur}})''' = Unnaschied zu jezd,
'''({{int:last}})''' = Unnaschied zude vorischi Ausgab, '''{{int:minoreditletter}}''' = gleni Ännarung.",
'history-fieldset-title' => 'Gugg die Gschischd',
'history-show-deleted' => 'Bloß gleschdi Saide zaische',
'histfirst' => 'Äldschde',
'histlast' => 'Naijschde',
'historyempty' => '(lea)',

# Revision feed
'history-feed-title' => 'Ännarungsgschischd',
'history-feed-item-nocomment' => '$1 õm $2',

# Revision deletion
'rev-delundel' => 'zaisch/vaschdeggl',
'rev-showdeleted' => 'zaische',
'revisiondelete' => 'Lesche/Heaschdelle vun Ännarunge',
'revdelete-show-file-submit' => 'Ja',
'revdelete-hide-image' => 'Vaschdegglde Inhald',
'revdelete-hide-name' => 'Vaschdeggls',
'revdelete-hide-comment' => 'Vaschdeggls Resimee',
'revdelete-hide-user' => 'Vaschdeggl Middawaidanome/IP',
'revdelete-radio-same' => '(dudo nix ännare)',
'revdelete-radio-set' => 'Ja',
'revdelete-radio-unset' => 'Nä',
'revdelete-log' => 'Grund:',
'revdelete-submit' => 'Uff die {{PLURAL:$1|gewehld Asugab|gewehldi Ausgawe}} owende',
'revdel-restore' => 'Sischdbakaid ännare',
'revdel-restore-deleted' => 'gleschdi Ännarunge',
'revdel-restore-visible' => 'sischdbari Ännarunge',
'pagehist' => 'Gschischd vunde Said',
'deletedhist' => 'Gleschde Gschischde',
'revdelete-reasonotherlist' => 'Õnnare Grund',

# History merging
'mergehistory' => 'Gschischde zõmmefiere',
'mergehistory-go' => 'Zaisch, wasma vaoinische konn',
'mergehistory-submit' => 'Gschischde zõmmefiere',
'mergehistory-reason' => 'Grund:',

# Merge log
'revertmerge' => 'Zõmmefiehrung rigggängisch mache',

# Diffs
'history-title' => 'Ännarungsgschischd vun "$1"',
'lineno' => 'Zail $1:',
'compareselectedversions' => 'Ausgawe midnonna vaglaische',
'showhideselectedversions' => 'Zaisch/Vaschdeggl gwehldi Ausgawe',
'editundo' => 'zriggnemme',

# Search results
'searchresults' => 'Eagewnis nochgugge',
'searchresults-title' => 'Eagewnis gugge fa "$1"',
'searchresulttext' => 'Fa mea Ogawe iwwas Nochgugge uff {{SITENAME}}, guggmol uff [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle' => 'Du hoschd noch \'\'\'[[:$1]]\'\'\' geguggd ([[Special:Prefixindex/$1|alle Saide, wu mid "$1" aafange]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|alle Saide, wu uff "$1" valinggd sinn]])',
'searchsubtitleinvalid' => "Du hoschd '''$1''' gsuchd",
'notitlematches' => 'Kän Saidedidl gfunne',
'notextmatches' => 'Kä Iwwaoinschdimmunge midm Tegschd',
'prevn' => 'ledschda {{PLURAL:$1|$1}}',
'nextn' => 'negschd {{PLURAL:$1|$1}}',
'prevn-title' => 'Frijari $1 {{PLURAL:$1|Eagewnis|Eagewnis}}',
'nextn-title' => 'Negschdi $1 {{PLURAL:$1|Eagewnis|Eagewnis}}',
'shown-title' => 'Zaisch $1 {{PLURAL:$1|Eagewnis}} bro Said',
'viewprevnext' => 'Gugg ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-exists' => "'''Dohods ä Said \"[[:\$1]]\".'''",
'searchmenu-new' => "'''Mach die Said „[[:$1]]“ im Wiki.'''",
'searchprofile-articles' => 'Inhald',
'searchprofile-project' => 'Hilf- un Brojegdsaide',
'searchprofile-images' => 'Multimedia',
'searchprofile-everything' => 'Alles',
'searchprofile-advanced' => 'Foadgschridde',
'searchprofile-articles-tooltip' => 'In $1 gugge',
'searchprofile-project-tooltip' => 'In $1 gugge',
'searchprofile-images-tooltip' => 'Gugg noch Bilda',
'searchprofile-everything-tooltip' => 'Such iwwaraal (a wuma dischbedierd)',
'searchprofile-advanced-tooltip' => 'Gugg in õnnare Nõmensreum',
'search-result-size' => '$1 ({{PLURAL:$2|1 Word|$2 Wärda}})',
'search-result-category-size' => '{{PLURAL:$1|1 Said|$1 Saide}} ({{PLURAL:$2|1 Sachgrubb|$2 Sachgrubbe}}, {{PLURAL:$3|1 Dadai|$3 Dadaije}})',
'search-result-score' => 'Bdaidung: $1%',
'search-redirect' => '(Waidalaidung $1)',
'search-section' => '(Abschnidd $1)',
'search-suggest' => 'Hoschd gemäänd: $1',
'search-interwiki-caption' => 'Schweschterprojekt',
'search-interwiki-default' => '$1 Ergebnis:',
'search-interwiki-more' => '(meh)',
'search-relatedarticle' => 'Vawond',
'mwsuggest-disable' => 'Schald Voaschlesch ab',
'searcheverything-enable' => 'Gugg iwwaraal',
'searchrelated' => 'vawond',
'searchall' => 'alle',
'showingresultsheader' => "{{PLURAL:$5|Eagewnis '''$1''' vun '''$3'''|Eagewnis '''$1–$2''' vun '''$3'''}} fa '''$4'''",
'nonefound' => "'''Hiiwais:''' S werre standardmäßich numme e Dail Namensraim durchsucht. Setz ''all:'' vor Dai Suchbegriff zum alle Saide (mit Dischbediersaide, Voalaache usw.) durchsuche odder direkt de Name vum Namensraum, wu durchsucht werre sell.",
'search-nonefound' => 'Kä Eagewnis vunde Õfroch.',
'powersearch' => 'Erwaiterte Such',
'powersearch-legend' => 'Erwaiterte Such',
'powersearch-ns' => 'In de Namensraim suche:',
'powersearch-redir' => 'Waiderlaidunge aazaische',
'powersearch-field' => 'Suche noch',
'powersearch-togglelabel' => 'Wehl:',
'powersearch-toggleall' => 'Alli',
'powersearch-togglenone' => 'Kään',

# Preferences page
'preferences' => 'Obzione',
'mypreferences' => 'Oischdellunge',
'datedefault' => 'Kä Oischdellunge',
'prefs-watchlist' => 'Beowachdungslischd',
'prefs-watchlist-edits-max' => 'Hegschdi Õzahl: 1000',
'prefs-misc' => 'Schunschdisches',
'prefs-rendering' => 'Uffdridd',
'saveprefs' => 'Oischdellunge schbaischere',
'resetprefs' => 'Oischdellunge vawerfe',
'prefs-editing' => 'Schaffe',
'rows' => 'Zaile',
'columns' => 'Schbalde',
'searchresultshead' => 'Nochgugge',
'resultsperpage' => 'Dreffa bro Said',
'stub-threshold-disabled' => 'Abgschdeld',
'guesstimezone' => 'Aus em Browser iwwernemme',
'timezoneregion-africa' => 'Afrika',
'timezoneregion-america' => 'Õmerika',
'timezoneregion-antarctica' => 'Õngdagdika',
'timezoneregion-arctic' => 'Aadigk',
'timezoneregion-asia' => 'Asije',
'timezoneregion-atlantic' => 'Adlõndischa Ozeõn',
'timezoneregion-australia' => 'Auschdralije',
'timezoneregion-europe' => 'Oirobba',
'timezoneregion-indian' => 'Indischa Ozeõn',
'timezoneregion-pacific' => 'Pazifischa Ozeõn',
'prefs-searchoptions' => 'Nochgugge',
'default' => 'Schdondad',
'youremail' => 'E-Mail:',
'username' => '{{GENDER:$1|Middawaida}}:',
'uid' => '{{GENDER:$1|Middawaida}}-Numma:',
'prefs-memberingroups' => '{{GENDER:$2|Middglied}} vun {{PLURAL:$1|Grubb|Grubbe}}:',
'yourrealname' => 'Birschalischa Nõme:',
'yourlanguage' => 'Schbrooch:',
'yournick' => 'Naiji Unnaschfrid',
'yourgender' => 'Gschleschd:',
'gender-unknown' => 'Ghoim gkalde',
'gender-male' => 'Männlisch',
'gender-female' => 'Waiblisch',
'prefs-help-email' => 'E-mail muss ned soi, awwa wead fas naijsedze vum Kennwoad bneedischd, wonns vagesse hoschd.',
'prefs-help-email-others' => 'Konschd a wehle, ob õnnare disch iwwan Lingg uff doina Dischbedier-Said õschbresche kennen.
Doi Address werd ned gzaischd, wõnse midda babbln.',
'prefs-diffs' => 'Unaschied',

# User rights
'userrights-groupsmember' => 'Midglied vun:',
'userrights-reason' => 'Grund:',

# Groups
'group' => 'Grubb:',
'group-user' => 'Middawaida',
'group-bot' => 'Bots',
'group-sysop' => 'Adminischdradore',
'group-bureaucrat' => 'Birograde',
'group-all' => '(alle)',

'group-bot-member' => '{{GENDER:$1|Bot}}',
'group-sysop-member' => '{{GENDER:$1|Adminischdrador}}',
'group-bureaucrat-member' => '{{GENDER:$1|Birokrad}}',

'grouppage-sysop' => '{{ns:project}}:Adminischtratore',

# Rights
'right-read' => 'Saide leese',
'right-edit' => 'Õnde Saide schaffe',
'right-createpage' => 'Saide mache',
'right-createtalk' => 'Dischbediersaide mache',
'right-move' => 'Said bwesche',
'right-move-subpages' => 'Said midde Unasaide bwesche',
'right-movefile' => 'Saide vaschiewe',
'right-upload' => 'Dadaije nufflade',
'right-upload_by_url' => 'Dadaije vunna Address nufflaade',
'right-delete' => 'Saide lesche',
'right-undelete' => 'Said widdaheaschdelle',

# Special:Log/newusers
'newuserlogpage' => 'Naijõmeldungs-Logbuch',

# User rights log
'rightslog' => 'Middawaidareschd-Logbuch',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'die Said beawaide',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|Ännarung|Ännarunge}}',
'recentchanges' => 'Ledschdi Ännarunge',
'recentchanges-legend' => 'Wehl ä Õzaisch fa die ledschde Ännarunge',
'recentchanges-feed-description' => 'Ledschde Ännarunge vun {{SITENAME}} im Feed oigewwe.',
'recentchanges-label-newpage' => 'Domid magschd ä naiji Said',
'recentchanges-label-minor' => "S'ische glenni Beawaidung",
'recentchanges-label-bot' => 'Ännarunge duaschn Bod',
'recentchanges-label-unpatrolled' => 'Die Ännarung isch noch nedd iwwabriefd worre',
'rcnote' => "Õgzaischd {{PLURAL:$1|werd '''1''' Ännarung|werren die ledschde '''$1''' Ännarunge}} {{PLURAL:$2|vum ledschde Daach|inde ledschde '''$2''' Daache}} (Schdond: $4, $5)",
'rcnotefrom' => "Unne sinn Ännarunge said '''$2''' (bis '''$1''').",
'rclistfrom' => 'Zaisch die ledschde Ännarunge ab $1',
'rcshowhideminor' => 'Glenni Ännarunge $1',
'rcshowhidebots' => 'Bots $1',
'rcshowhideliu' => 'Õgmelda Middawaida $1',
'rcshowhideanons' => 'Ned õgmelda Middawaida $1',
'rcshowhidepatr' => '$1 iwabriefde Ännarunge',
'rcshowhidemine' => 'Moi Beawaidunge $1',
'rclinks' => 'Zaisch die ledschde $1 Ännarunge inde ledschde $2 Daach<br />$3',
'diff' => 'Unnaschied',
'hist' => 'Gschischd',
'hide' => 'vaschdeggle',
'show' => 'zaische',
'minoreditletter' => 'k',
'newpageletter' => 'N',
'boteditletter' => 'B',
'rc_categories' => 'Oigschrengd uff Sachgrubbe (abgdeeld middm "|")',
'rc_categories_any' => 'Ebbes',
'rc-enhanced-expand' => 'Änzlhaide zaische (dozu brauchds JavaScript)',
'rc-enhanced-hide' => 'Õgawe vaschdeggle',

# Recent changes linked
'recentchangeslinked' => 'Was õn valinggde Saide gännad worre isch',
'recentchangeslinked-feed' => 'Ännarunge on valinggde Saide',
'recentchangeslinked-toolbox' => 'Ännarunge uff verlingde Saide',
'recentchangeslinked-title' => 'Ännarunge õn Saide, wu „$1“ druff verlinggd',
'recentchangeslinked-summary' => "Die Lischd zaischd ledschdi Ännarunge, vunna bschdimmde Said, wu do valinggd isch (odda ä Midglied vunna bschdimmde Sachgrubb isch).
Saide uff [[Special:Watchlist|Doina Beowachdungslischd]] sinn '''fedd'''.",
'recentchangeslinked-page' => 'Saide:',
'recentchangeslinked-to' => 'Zaisch Ännarunge uff Saide, wu do her valinggd sinn',

# Upload
'upload' => 'Nufflade',
'uploadbtn' => 'Dadai nufflade',
'uploadlogpage' => 'Dadaije-Logbuch',
'filedesc' => 'Iwwabligg',
'fileuploadsummary' => 'Iwwabligg:',
'savefile' => 'Dadai schbaischere',
'uploadedimage' => 'hodd „[[$1]]“ nuffglade',

# Lock manager
'lockmanager-notlocked' => "„$1“ hod ned uffgmachd were kenne, s'isch ganed gschberd gwesd.",
'lockmanager-fail-closelock' => 'Die gbscherd Dadai „$1“ hod ned gschlosse were kenne.',
'lockmanager-fail-deletelock' => 'Die gbscherd Dadai „$1“ hod ned gleschd were kenne.',
'lockmanager-fail-acquirelock' => '„$1“ komma ned schberre.',
'lockmanager-fail-openlock' => 'Die gschberd Dadai „$1“ komma ned uffmache.',
'lockmanager-fail-releaselock' => '„$1“ konn ned fraigewe werre.',

'license' => 'Bwillischung',
'license-header' => 'Bwillischung',

# Special:ListFiles
'listfiles_name' => 'Nome',
'listfiles_size' => 'Greeß',
'listfiles_count' => 'Versione',

# File description page
'file-anchor-link' => 'Dadai',
'filehist' => 'Dadaigschischd',
'filehist-help' => 'Drigg uffn Zaidpunggd zum õzaische, wies dord ausgseje hodd.',
'filehist-deleteall' => 'alles lesche',
'filehist-deleteone' => 'lesche',
'filehist-revert' => 'zriggsedze',
'filehist-current' => 'agduell',
'filehist-datetime' => 'Zaidpungd',
'filehist-thumb' => 'Gleenes Bild',
'filehist-thumbtext' => 'Skizz fa die Ausgab vum $1',
'filehist-user' => 'Middawaida',
'filehist-dimensions' => 'Maß',
'filehist-comment' => 'Oißarung',
'imagelinks' => 'Dadaivawendung',
'linkstoimage' => 'Die {{PLURAL:$1|Said vawaisd|$1 Saide vawaisn}} uff die Dadai:',
'nolinkstoimage' => 'Do hodds kä Said, wu dohea zaischd.',
'sharedupload' => 'Die Datei isch vun $1 un s kann sai, dass se ach vun annere Projekt gebraucht werd.',
'sharedupload-desc-here' => 'Die Dadai isch vun $1 un konn a wuonaschda bnuzd werre.
Ä Bschraiwung finschd [$2 Dadaibschraiwungssaid] unne.',
'uploadnewversion-linktext' => 'E naiere Version vun derre Datei hochlade',

# File deletion
'filedelete' => 'Lesch $1',
'filedelete-legend' => 'Dadai lesche',
'filedelete-submit' => 'Lesche',
'filedelete-success' => "'''$1''' isch gleschd worre.",
'filedelete-maintenance-title' => 'Dadai konnned gleschd werre',

# MIME search
'download' => 'Runalaade',

# Random page
'randompage' => 'Irschndn Adiggl',

# Statistics
'statistics' => 'Schdadischdigge',
'statistics-pages' => 'Saide',

'brokenredirects-edit' => 'beawaide',
'brokenredirects-delete' => 'lesche',

'withoutinterwiki-submit' => 'Zaische',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|Byte|Bytes}}',
'ncategories' => '$1 {{PLURAL:$1|Sachgrubb|Sachgrubbe}}',
'ninterwikis' => '$1 {{PLURAL:$1|interwiki|interwikis}}',
'nlinks' => '$1 {{PLURAL:$1|Lingg|Linggs}}',
'nmembers' => '$1 {{PLURAL:$1|Middawaida|Middawaida}}',
'nrevisions' => '$1 {{PLURAL:$1|Ännarung|Ännarunge}}',
'nimagelinks' => 'Used on $1 {{PLURAL:$1|Said|Saide}}',
'ntransclusions' => 'oigsedzd uff $1 {{PLURAL:$1|Said|Saide}}',
'uncategorizedpages' => 'Said ohne Sachgrubb',
'uncategorizedcategories' => 'Sachgrubb ohne Sachgrubb',
'uncategorizedimages' => 'Dadai ohne Sachgrubb',
'uncategorizedtemplates' => 'Vorlach ohne Sachgrubb',
'unusedcategories' => 'Vawaisdi Sachgrubb',
'unusedimages' => 'Vawaisde Dadaije',
'popularpages' => 'Bliewbde Saide',
'wantedcategories' => 'Gwinschde Sachgrubbe',
'wantedpages' => 'Gwinschde Saide',
'mostlinkedcategories' => 'Nizlischi Sachgrubbe',
'mostlinkedtemplates' => 'Niylischi Vorlache',
'mostcategories' => 'Saide midd õm maigschde Sachgrubbe',
'prefixindex' => 'Alle Saide (midd Voasilw)',
'listusers-editsonly' => 'Zaisch bloß Bnudza mid Baidräsch',
'usercreated' => '{{GENDER:$3|Gmachd}} vun $1 om $2',
'newpages' => 'Naije Saide',
'move' => 'Vaschiewe',
'movethispage' => 'Die Said verschiewe',
'unusedcategoriestext' => 'Die Sachgrubb hodds, a wonnse vun känna onnare Said odda Sachgrubb gnumme werd.',
'pager-newer-n' => '{{PLURAL:$1|negschd 1|negschd $1}}',
'pager-older-n' => '{{PLURAL:$1|vorisch 1|vorische $1}}',

# Book sources
'booksources' => 'Buchgwelle',
'booksources-search-legend' => 'Noch Buchgwelle gugge',
'booksources-go' => 'Geh',

# Special:Log
'log' => 'Logbischa',

# Special:AllPages
'allpages' => 'Alle Saide',
'alphaindexline' => 'vun $1 bis $2',
'prevpage' => 'Voriche Said ($1)',
'allpagesfrom' => 'Saide aazaische wu aafange mid:',
'allpagesto' => 'Saide aazaische wu ufhere mid:',
'allarticles' => 'Alle Saide',
'allpagesprev' => 'Voriche',
'allpagesnext' => 'Negschd',
'allpagessubmit' => 'Zaische',

# Special:Categories
'categories' => 'Sachgrubbe',
'categoriespagetext' => 'Folschndi {{PLURAL:$1|Sachgrubb hodd|Sachgrubbe hawen}} Saide odda Dadaije. [[Special:UnusedCategories|Ubnudze Sachgrubbe]] werren do nedd gzaischd. Gugg a uffde [[Special:WantedCategories|gwinschde Sachgrubbe]].',
'categoriesfrom' => 'Zaisch Sachgrubbe õgfonge middt:',

# Special:DeletedContributions
'sp-deletedcontributions-contribs' => 'Baidräsch',

# Special:LinkSearch
'linksearch' => 'Exderne Lings',
'linksearch-pat' => 'Suchmuschda',
'linksearch-line' => '$1 isch vun $2 valinggd',

# Special:ListUsers
'listusers-submit' => 'Zaische',
'listusers-noresult' => 'Kä Middawaida gfunne',
'listusers-blocked' => '(gschberd)',

# Special:ListGroupRights
'listgrouprights-group' => 'Grubb',
'listgrouprights-members' => '(Midgliedalischd)',

# Email user
'emailuser' => 'E-Mail õnde Middawaida',
'emailusername' => 'Middawaidanõme:',
'emailfrom' => 'Vum:',
'emailto' => 'Fa:',
'emailsubject' => 'Bdreff:',
'emailmessage' => 'Middeelung:',
'emailsend' => 'Abschigge',

# Watchlist
'watchlist' => 'Beowachdungslischd',
'mywatchlist' => 'Beowachdungslischd',
'watchlistfor2' => 'Vun $1 $2',
'addedwatchtext' => "Die Said \"[[:\$1]]\" isch zu doina [[Special:Watchlist|Beowachdungslischd]] zugfieschd worre. Zukinfdischi Ännarunge õnde Said unde Dischbediersaid, wu dzu kead, werren doo õgzaischd, un die Said werd '''fedd''' inde [[Special:RecentChanges|Ledschdi Ännarunge]] õgzaischd domidmas efacha finne konn.",
'removedwatchtext' => 'D Said "[[:$1]]" isch aus [[Special:Watchlist|Dainer Beowachdungslischt]] rausgenumme worre.',
'watch' => 'Beowachde',
'watchthispage' => 'Die Said beowachde',
'unwatch' => 'Nemme beowachde',
'watchlist-details' => 'S hodd {{PLURAL:$1|$1 Said|$1 Saide}} uff doina Beowachdungslischd, Dischbediersaide zeeln nedd.',
'wlshowlast' => 'Die ledschde $1 Schdunnd $2 Daach $3 zaische',
'watchlist-options' => 'Meschlischkaide vunde Beowachdungslischd',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Beowachde ...',
'unwatching' => 'Nimmi beowachde ...',

'enotif_reset' => 'Alle Seide als bsuchd margiere',

# Delete
'deletepage' => 'Said lesche',
'confirm' => 'Beschdedische',
'delete-legend' => 'Lesche',
'confirmdeletetext' => 'Du bisch debai e Said z lesche mid alle Versione.
Bitte du bstätiche, dass Du des wllscht du, dass Du verstehsch, was des hääßt, un dass Du des machscht in Iwweraistimmung mit de [[{{MediaWiki:Policy-url}}|Richtline]].',
'actioncomplete' => 'Maßnohm ferdisch',
'actionfailed' => 'Maßnohm gschaidad',
'deletedtext' => '"$1" isch gelescht worre.
Guck $2 fer e Lischt vun de letschte Leschunge.',
'dellogpage' => 'Leschlogbuch',
'deletecomment' => 'Grund:',
'deleteotherreason' => 'Annere/zusätzliche Grund:',
'deletereasonotherlist' => 'Annere Grund',

# Rollback
'rollbacklink' => 'Zriggsedze',

# Protect
'protectlogpage' => 'Saideschudz-Logbuch',
'protectedarticle' => 'hodd "[[$1]]" gschizd',
'modifiedarticleprotection' => 'hot de Schutzstatus vun "[[$1]]" gännert',
'protectcomment' => 'Grund:',
'protectexpiry' => 'Bis:',
'protect_expiry_invalid' => 'Zaidraum isch nid gildich.',
'protect_expiry_old' => 'Zaidraum licht in de Vergangehääd.',
'protect-text' => "Du kannscht de Schutzstatus vun de Said '''$1''' aagucke un ännere.",
'protect-locked-access' => "Doi Kondo hodd kä Reschd um de Schudzsdadus vunna Said zu ännare.
Do hodds die Oischdellunge vunde Said '''$1''':",
'protect-cascadeon' => 'Die Said isch gschizd, wail se {{PLURAL:$1|zu derre Said ghert|zu denne Saide ghert}}, wu e Kaskadesperrung gelt.
Der Schutzstatus vun derre Said kannscht ännere, awwer des hot kää Aifluss uff d Kaskadesperrung.',
'protect-default' => 'Alle Middawaida erlauwe',
'protect-fallback' => 'Bloß fa Laid mid "$1" Bereschdischung',
'protect-level-autoconfirmed' => 'Naiji un nedd õgmeld Middawaida schberre',
'protect-level-sysop' => 'Bloß fa Adminischdradore',
'protect-summary-cascade' => 'Kaskade',
'protect-expiring' => 'bis $1 (UTC)',
'protect-expiring-local' => 'bis $1',
'protect-cascade' => 'Kaskadesperrung – alle aigebunnene Vorlache sinn midgsperrd.',
'protect-cantedit' => 'Du kannscht de Schutzstatus vun derre Said nit ännere, wail Du nid d Berechdichung dezu hoscht.',
'restriction-type' => 'Berechdichung:',
'restriction-level' => 'Schudsewene:',

# Restrictions (nouns)
'restriction-edit' => 'Beawaide',
'restriction-move' => 'Verschiewe',

# Undelete
'undelete' => 'Widderherschdelle',
'undeletebtn' => 'Widderherschdelle',
'undeletelink' => 'õgugge/widda herschdelle',
'undeleteviewlink' => 'Õgugge',
'undeletereset' => 'Zuriggsedze',
'undelete-show-file-submit' => 'Ja',

# Namespace form on various pages
'namespace' => 'Nõmensraum',
'invert' => 'Wahl dausche',
'blanknamespace' => '(Schdadsaid)',

# Contributions
'contributions' => '{{GENDER:$1|Wassa gemachd hodd}}',
'contributions-title' => 'Middawaidabaidräsch vun $1',
'mycontris' => 'Baidräsch',
'contribsub2' => 'Fa $1 ($2)',
'uctop' => '(geschewedisch)',
'month' => 'än Monad (un frieja):',
'year' => 'Abm Johr (un frieja):',

'sp-contributions-newbies' => 'Zaisch nua Baidräsch vun naije Konde',
'sp-contributions-blocklog' => 'Schberrlogbuch',
'sp-contributions-uploads' => 'Nufflade',
'sp-contributions-logs' => 'Logbischa',
'sp-contributions-talk' => 'Dischbediere',
'sp-contributions-search' => 'Noch Baidräsch gugge',
'sp-contributions-username' => 'IP-Adress odda Middawaidanõme:',
'sp-contributions-toponly' => 'Bloß agduelli Ännarunge zaische',
'sp-contributions-submit' => 'Gugge',

# What links here
'whatlinkshere' => 'Was doher zaische dud',
'whatlinkshere-title' => 'Saide wu uff "$1" valinggn',
'whatlinkshere-page' => 'Said:',
'linkshere' => "Die Saide valinggn uff '''[[:$1]]''':",
'nolinkshere' => "Kä Said zaischd uff '''[[:$1]]'''.",
'isredirect' => 'Waidalaidungsaid',
'istemplate' => 'Vorlacheoibindung',
'isimage' => 'Dadailingg',
'whatlinkshere-prev' => '{{PLURAL:$1|vorisch|vorische $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|negschd|negschde $1}}',
'whatlinkshere-links' => '← Linggs',
'whatlinkshere-hideredirs' => '$1 Waidalaidunge',
'whatlinkshere-hidetrans' => '$1 Vorlacheoibindunge',
'whatlinkshere-hidelinks' => '$1 Linggs',
'whatlinkshere-hideimages' => '$1 Dadailinggs',
'whatlinkshere-filters' => 'Filda',

# Block/unblock
'blockip' => 'Middawaida bloggiere',
'ipbsubmit' => 'Middawaida bloggiere',
'ipboptions' => '2 Schdunne:2 hours,1 Daach:1 day,3 Daach:3 days,1 Woch:1 week,2 Woche:2 weeks,1 Monad:1 month,3 Monad:3 months,6 Monad:6 months,1 Johr:1 year,Fa imma:infinite',
'ipbotheroption' => 'onnari',
'ipusubmit' => 'Die Adreß fraigewwe',
'ipblocklist' => 'Gschberrdi IP-Adress un Middawaidanõme',
'infiniteblock' => 'ubgrensd',
'blocklink' => 'schberre',
'unblocklink' => 'Sperr uffhewe',
'change-blocklink' => 'Schberr ännare',
'contribslink' => 'Baidräsch',
'blocklogpage' => 'Schberrlogbuch',
'blocklogentry' => "hodd [[$1]] gschberrd fa'n Zaidraum vun $2 $3",
'unblocklogentry' => 'hot d Sperr vun $1 uffghowwe',
'block-log-flags-nocreate' => 'Õleesche vun Konde isch gschberrd',

# Developer tools
'lockbtn' => 'Dadebongg schberre',
'unlockbtn' => 'Dadebongg fraigewwe',

# Move page
'move-page-legend' => 'Said vaschiewe',
'movepagetext' => "Midm Formad konnschd ä Said en naije Nome gewwe, debai werrem alli alde Ausgawe uffde nai Nome vaschowe.
Ausm alde Nome werd e Waidalaidungssaid zum naije Nome.
Waidalaidungssaide, wu uffde ald Nome umlaide dun, konnschd vun allä uffde naischde Schdond bringe.
Wonndes ned willschd, guggschd uff [[Special:DoubleRedirects|dobbldi]] odda [[Special:BrokenRedirects|kabuddi Waidalaidunge]].
Soasch dfia, dass Linggs waida uffdie rischdische Saide fiehan.

Gebb Achd, dass die Said '''ned''' vaschowe werd, wonns schunä Said midm naije Nome hod, außa wonnse lea isch odda e Waidalaidung.
Des heeßd, Du konnschd ke Said, wus schun gibbd, iwwaschraiwe.

'''BAßMAUFF!'''
Des isch e wischdischi Ännarung fa e Said un konn zimlisch uuerwaaded soi fa wischdischi Saide;
mach des bloß, wonn die Folsche vunde Maßnohm a abschedze konnschd.",
'movepagetalktext' => "D Dischbediersaid werd ach mid verschowe, '''ausser:'''
* Du verschiebsch die Saide in e annere Namensraum, odder
* s gebbt schun e Dischbediersaid mi dem Name, orrer
* Du wählsch unne d Option, se nid z verschiewe.

In denne Fäll misst mer d Dischbediersaid vun Hand kopiere.",
'movearticle' => 'Said vaschiewe:',
'newtitle' => 'Zum naije Didl:',
'move-watch' => 'Die Said beowachde',
'movepagebtn' => 'Said vaschiewe',
'pagemovedsub' => 'Verschiewung hot geklappt',
'movepage-moved' => '\'\'\'"$1" isch verschowe worre uff "$2"\'\'\'',
'articleexists' => 'E Said mid dem Name gebbt s schun, orrer de Name, wu du gewählt hoscht, isch nid gildich.
Bitte nemm e annere Name.',
'talkexists' => "'''Die Said selwerschd, isch verschowe worre, awwer d Dischbediersaid hot nid kenne verschowe werre, wail s schun enni gebbt mid dem Name.
Bitte duu se vun Hand zammefiehre.'''",
'movedto' => 'vaschowe uff',
'movetalk' => 'Dischbediersaid, wu dezu ghert, verschiewe',
'movelogpage' => 'Vaschiewungs-Logbuch',
'movereason' => 'Grund:',
'revertmove' => 'Zurigg vaschiewe',
'delete_and_move' => 'Lesche un Verschiewe',
'delete_and_move_confirm' => 'Ja, Said lesche',

# Export
'export' => 'Saide rausgewe',
'export-submit' => 'Saide exbordiere',
'export-addcattext' => 'Saide vunde Sachgrubb dzufiesche:',

# Namespace 8 related
'allmessagesname' => 'Nõme',
'allmessagesdefault' => 'Vorgewene Tegschd',
'allmessages-filter-modified' => 'Vaännad',

# Thumbnails
'thumbnail-more' => 'Mags greßa',
'thumbnail_error' => 'Baim Voaschaubild ischwas falsch glaafe: $1',

# Special:Import
'import-interwiki-submit' => 'Impordiere',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Doi Miaddawaidasaid',
'tooltip-pt-mytalk' => 'Doi Said fas Dischbediere',
'tooltip-pt-preferences' => 'Doi Oischdellunge',
'tooltip-pt-watchlist' => 'Lischd vun Saide, wu beowachde duschd',
'tooltip-pt-mycontris' => 'Lischd vun doine Baidräsch',
'tooltip-pt-login' => 'Du konnschd disch õmelde, awwa mugschd ned',
'tooltip-pt-logout' => 'Uffhere',
'tooltip-ca-talk' => 'Iwwa d Inhaldssaid dischbediere',
'tooltip-ca-edit' => 'Du konnschd die Said beawaide.
Bidde nemmde Vorschau-Gnobb vorm Schbaischare',
'tooltip-ca-addsection' => "N'naije Abschnidd õleche",
'tooltip-ca-viewsource' => 'Die Said isch gschizd. Du konnschdda de Gwelltegschd õgugge.',
'tooltip-ca-history' => 'Ledschdi Ausgawe vunde Said',
'tooltip-ca-protect' => 'Die Said schidze',
'tooltip-ca-delete' => 'Die Said lesche',
'tooltip-ca-move' => 'Die Said vaschiewe',
'tooltip-ca-watch' => 'Die Said zu doina Beowachdungslischd dzufiesche',
'tooltip-ca-unwatch' => 'Die Said vun doina Beowachdunschlischd wegnemme',
'tooltip-search' => 'Gugg uff {{SITENAME}} fa',
'tooltip-search-go' => 'Geh zude Said midm Nõme, wonnses hodd',
'tooltip-search-fulltext' => 'Gugg inde Said nochm Tegschd',
'tooltip-p-logo' => 'Schdadsaid',
'tooltip-n-mainpage' => "Uff d'Schdadsaid geje",
'tooltip-n-mainpage-description' => 'Haubdsaid õgugge',
'tooltip-n-portal' => 'Iwwas Brojegd, wu mache konnschd, wu ebbes finne duschd',
'tooltip-n-currentevents' => 'Finn Auskinfd iwwas Naijischde',
'tooltip-n-recentchanges' => 'Lischd vunde ledschde Ännarunge im Wiki',
'tooltip-n-randompage' => 'Laad e zufellischi Said',
'tooltip-n-help' => 'Do konschds rausfinne',
'tooltip-t-whatlinkshere' => 'Lischd vun alle Wikisaide, wu dohie verlingd sinn',
'tooltip-t-recentchangeslinked' => 'Ledschdi Ännarunge in Saide, wu vun do verlinggd sinn',
'tooltip-feed-rss' => 'RSS feed fer die Said',
'tooltip-feed-atom' => 'Atom-Feed fa die Said',
'tooltip-t-contributions' => 'Ledschdi Baidräsch vum Middawaida õgugge',
'tooltip-t-emailuser' => 'Dem Middawaida e E-Mail schigge',
'tooltip-t-upload' => 'Dadaije nufflade',
'tooltip-t-specialpages' => 'Lischd vunde Schbezialsaide',
'tooltip-t-print' => 'Druggausgab vunde Said',
'tooltip-t-permalink' => "N'dauwahafde Lingg uff die Ausgab vunde Said",
'tooltip-ca-nstab-main' => 'Inhald õgugge',
'tooltip-ca-nstab-user' => 'Middawaidasaid õgugge',
'tooltip-ca-nstab-special' => 'Des isch e Spezialsaid, du konnschd d Said selwaschd nedd ännare',
'tooltip-ca-nstab-project' => 'Brojegdsaid õgugge',
'tooltip-ca-nstab-image' => 'Dadaisaid õgugge',
'tooltip-ca-nstab-template' => 'Vorlach õgugge',
'tooltip-ca-nstab-category' => 'Sachgrubbsaid õgugge',
'tooltip-minoredit' => 'Als gleeni Ännarung makiere',
'tooltip-save' => 'Doi Ännarunge schbaischare',
'tooltip-preview' => 'Guggda doi Ännarunge inde Vorschau õ, bvor uff Schbaischare drigschd!',
'tooltip-diff' => 'Gugg, welschi Ännarunge im Tegschd gmachd hoschd',
'tooltip-compareselectedversions' => 'D Unnaschied zwische denne zwee gwehlde Ausgawe õgugge',
'tooltip-watch' => 'Die Said zu doina Beowachdunglischd zufiesche',
'tooltip-rollback' => "„Zriggsedze“ machd alli Beawaidunge vum ledschde Middawaida rigg'gängisch",
'tooltip-undo' => "„Zrigg“ machd nua die Ännarung rigg'gängich un zaischd ä Vorschau õ.
Konnschdn Grund inde Zommefassung õgewwe.",
'tooltip-summary' => 'Gebä koaz Resimee',

# Info page
'pageinfo-hidden-categories' => '{{PLURAL:$1|Vaschdeggldi Sachgrubb|Vaschdegglde Sachgrubbe}} ($1)',
'pageinfo-category-info' => 'Sachgrubb-Õgawe',
'pageinfo-category-subcats' => 'Õzahl vun Unnagrubbe',

# Browsing diffs
'previousdiff' => '← Äldari Beawaidung',
'nextdiff' => 'Naijari Beawaidung →',

# Media information
'file-info-size' => '$1 × $2 Pixels, Dadaigreß: $3, MIME-Type: $4',
'file-nohires' => 'Ke heheri Ufflesung vafieschba.',
'svg-long-desc' => 'SVG-Datei, Grundgreß $1 × $2 Pixels, Dadaigreß: $3',
'show-big-image' => 'Volli Ufflesung',

# Special:NewFiles
'showhidebots' => '(Bots $1)',
'ilsubmit' => 'Such',

# Bad image list
'bad_image_list' => 'Uffbau: bloß Zaile, wu midm * õfonge werren briggsischdischd.
De erschd Lingg mussn Lingg zu änna uuerwinschde Dadai soi.
Õnnare Linggs inde glaische Zail werren als Ausnõhm gnumme, des heesd, Saide, wu inde Dadai vorkumme dirfn.',

# Metadata
'metadata' => 'Medadaade',
'metadata-help' => 'Die Dadai hodd waidari Õgawe, waschoinlisch vunde Digidalkõmara odda vum Skänna, wumase mid gmachd hodd.
Wonn die Dadai vaännad worre isch, donn konns soi, daß zusedzlischi Õgawe fa die vaännad Dadai nemme rischdisch sinn.',
'metadata-expand' => 'Erwaiterte Details aazaiche',
'metadata-collapse' => 'Erwaiterte Details versteckeln',
'metadata-fields' => 'Die EXIF-Medadaade werren inde Bild-Bschraiwung a õgzaischd, wonn die Medadaade-Tabell vaschdegld isch.
Õnnare Medadaade sinn noamalawais vaschdegld.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude',

# Exif tags
'exif-iimcategory' => 'Sachgrubb',
'exif-iimsupplementalcategory' => 'Ergenzndi Sachgrubbe',

'exif-gaincontrol-0' => 'Kään',

# External editor support
'edit-externally' => 'Die Dadai midm õnnare Weagzaisch beawaide',
'edit-externally-help' => '(Gugg uff [//www.mediawiki.org/wiki/Manual:External_editors Inschdallazionsõwaisunge] fa mea Auskinfd)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'alle',
'namespacesall' => 'alle',
'monthsall' => 'alle',

# Watchlist editing tools
'watchlisttools-view' => 'Die wischdische Ännarunge õgugge',
'watchlisttools-edit' => 'Beowachdunglischd õgugge un beawaide',
'watchlisttools-raw' => 'Im große Tegschdfeld beawaide',

# Core parser functions
'duplicate-defaultsort' => '\'\'\'Baßma uff:\'\'\' De Schlissl "$2" dudde frijare Schlissl "$1" iwwaschraiwe.',

# Special:SpecialPages
'specialpages' => 'Schbezialsaide',
'specialpages-group-other' => 'Onare bsundare Saide',
'specialpages-group-pagetools' => 'Wergzaisch fa Saide',
'specialpages-group-wiki' => 'Daade un Wergzaisch',

# Special:Tags
'tag-filter' => '[[Special:Tags|Bschildarungs]]-Filda:',
'tags-edit' => 'bearwaide',

# New logging system
'rightsnone' => '(-)',

# Feedback
'feedback-close' => 'Erledischd',

# Search suggestions
'searchsuggest-search' => 'Suche',

# API errors
'api-error-unknownerror' => 'Uubekonde Irrdumm: "$1".',

# Durations
'duration-seconds' => '$1 {{PLURAL:$1|Sekund|Sekunde}}',
'duration-minutes' => '$1 {{PLURAL:$1|Minud|Minude}}',
'duration-hours' => '$1 {{PLURAL:$1|Schdund|Schdunde}}',
'duration-days' => '$1 {{PLURAL:$1|Daach|Daache}}',
'duration-weeks' => '$1 {{PLURAL:$1|Woch|Woche}}',
'duration-years' => '$1 {{PLURAL:$1|Joa|Joare}}',
'duration-decades' => '$1 {{PLURAL:$1|Jaazehnd|Jaazehnde}}',
'duration-centuries' => '$1 {{PLURAL:$1|Jaahunnad|Jaahunnade}}',
'duration-millennia' => '$1 {{PLURAL:$1|Jaadausnd|Jaadausnde}}',

);

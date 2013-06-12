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
	NS_USER             => 'Benudzer',
	NS_USER_TALK        => 'Benudzer_Dischbediere',
	NS_PROJECT_TALK     => '$1_Dischbediere',
	NS_FILE             => 'Dadai',
	NS_FILE_TALK        => 'Dadai_Dischbediere',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_Dischbediere',
	NS_TEMPLATE         => 'Vorlach',
	NS_TEMPLATE_TALK    => 'Vorlach_Dischbediere',
	NS_HELP             => 'Hilf',
	NS_HELP_TALK        => 'Hilf_Dischbediere',
	NS_CATEGORY         => 'Kadegorie',
	NS_CATEGORY_TALK    => 'Kadegorie_Dischbediere',
);

$namespaceAliases = array(
	# German namespaces
	'Medium'               => NS_MEDIA,
	'Spezial'              => NS_SPECIAL,
	'Diskussion'           => NS_TALK,
	'Benutzer'             => NS_USER,
	'Benutzer_Diskussion'  => NS_USER_TALK,
	'$1_Diskussion'        => NS_PROJECT_TALK,
	'Datei'                => NS_FILE,
	'Datei_Diskussion'     => NS_FILE_TALK,
	'MediaWiki_Diskussion' => NS_MEDIAWIKI_TALK,
	'Vorlage'              => NS_TEMPLATE,
	'Vorlage_Diskussion'   => NS_TEMPLATE_TALK,
	'Hilfe'                => NS_HELP,
	'Hilfe_Diskussion'     => NS_HELP_TALK,
	'Kategorie'            => NS_CATEGORY,
	'Kategorie_Diskussion' => NS_CATEGORY_TALK,
);

$messages = array(
# User preference toggles
'tog-underline' => 'Lingg unnaschdraische',
'tog-hideminor' => 'Vaschdegg klääne Bearwaidunge',
'tog-hidepatrolled' => 'Vaschdegg gsischdede Ännarunge',
'tog-extendwatchlist' => 'Zaisch alle Ännarunge unn ned nur die ledschde',
'tog-showtoolbar' => "Wergzaisch fas Beawaide zaische (dodezu brauchd's JavaScript)",
'tog-showjumplinks' => 'Schdellde "Hubs uff"-Lingg',
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
'wednesday' => 'Midwoch',
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
'category_header' => 'Saide in de Sachgrubb „$1“',
'subcategories' => 'Unnagrubbe',
'category-media-header' => 'Medⁱje indɐ Sachgrubb „$1“',
'category-empty' => '"Die Sachgrubb hod kä Said odda Medije."',
'hidden-categories' => '{{PLURAL:$1|Vaschdegldi Sachgrubb|Vaschdeglde Sachgrubbe}}',
'hidden-category-category' => 'Verschdegelde Grubbe',
'category-subcat-count' => '{{PLURAL:$2|Die Grubb hod bloß die Unnagrubb.|Die Grubb hod {{PLURAL:$1|Unnagrubbe|$1 Unnagrubbe}},vun gsomd $2.}}',
'category-article-count' => '{{PLURAL:$2|In dɐ Grubb hodds nua die Said.|Die {{PLURAL:$1|Said|$1 Saide}} gibbds inde Grubb, vun gsomd $2.}}',
'category-file-count' => "{{PLURAL:$2|Die Grubb hod bloß ä Said.|Die {{PLURAL:$1|Said isch äni vun $2 Saide:|S'werren $1 vun gsomd $2 Saide gzaischd:}}}}",
'listingcontinuesabbrev' => '(Fords.)',
'noindex-category' => 'Saide, wu ned im Vazaischnis sinn',

'about' => 'Iwwa',
'newwindow' => '(werd inem naije Fenschda uffgmachd)',
'cancel' => 'Abbresche',
'moredotdotdot' => 'Mea …',
'mypage' => 'Said',
'mytalk' => 'Dischbediere',
'navigation' => 'Nawigadzion',

# Cologne Blue skin
'qbfind' => 'Finne',
'qbbrowse' => 'Duaschschdewere',
'qbedit' => 'Beawaide',
'qbpageoptions' => 'Die Said',
'qbmyoptions' => 'Moi Saide',
'faq' => 'Ofd gschdeldi Froche',

# Vector skin
'vector-action-addsection' => 'Abschnidd dzufiesche',
'vector-action-delete' => 'Lesche',
'vector-action-move' => 'Vaschiewe',
'vector-action-protect' => 'Schidze',
'vector-action-undelete' => 'Zriggbringe',
'vector-view-create' => 'Oleesche',
'vector-view-edit' => 'Beawaide',
'vector-view-history' => 'Dadaigschischd',
'vector-view-view' => 'Lese',
'vector-view-viewsource' => 'Gwelltegschd zaische',
'actions' => 'Maßnohme',
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
'printableversion' => 'Drugg-Asischd',
'permalink' => 'Schdendischa Lingg',
'print' => 'Drugge',
'view' => 'Ogugge',
'edit' => 'Beawaide',
'create' => 'Aleesche',
'editthispage' => 'Die Said beawaide',
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
'personaltools' => 'Persenlischs Wergzaisch',
'postcomment' => 'Naije Abschnidd',
'talk' => 'Dischbediere',
'views' => 'Uffruf',
'toolbox' => 'Wergzaischkischd',
'categorypage' => 'Zaisch die Kadegorie',
'viewtalkpage' => 'Zaischs Gbabbl',
'otherlanguages' => 'In annare Schbroche',
'redirectedfrom' => '(Nochgschiggd vun $1)',
'redirectpagesub' => 'Nochschigg-Said',
'lastmodifiedat' => 'Die Said ischs ledschde Mol gännad worre õm $1, õm $2.',
'viewcount' => 'Die Said isch bis jedz {{PLURAL:$1|$1|$1}} mol uffgrufe worre.',
'protectedpage' => 'Said schidze',
'jumpto' => 'Hubs uff:',
'jumptonavigation' => 'Nawigadzion',
'jumptosearch' => 'Nochgugge',
'pool-errorunknown' => 'Ubkonnde Irrdumm',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => 'Iwwa {{SITENAME}}',
'aboutpage' => 'Project:Iwwa',
'copyright' => 'Was do drin schded isch unna $1 vafieschba.',
'copyrightpage' => '{{ns:project}}:Urhewareschd',
'currentevents' => 'Aggduelli Gscheniss',
'currentevents-url' => 'Project: Leschdi Gschneniss',
'disclaimers' => 'Hafdungsausschluß',
'disclaimerpage' => 'Project:Impressum',
'edithelp' => 'Unaschdizung fas Beawaide',
'edithelppage' => 'Help:Ännare',
'helppage' => 'Help:Inhald',
'mainpage' => 'Schdadsaid',
'mainpage-description' => 'Schdadsaid',
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
'editsection' => 'beawaide',
'editold' => 'beawaide',
'viewsourceold' => 'Gwelltegschd ogugge',
'editlink' => 'beawaide',
'viewsourcelink' => 'Gwell aagugge',
'editsectionhint' => 'Deel ännare: $1',
'toc' => 'Inhald',
'showtoc' => 'zaische',
'hidetoc' => 'vaschdeggle',
'collapsible-collapse' => 'Oiglabbe',
'viewdeleted' => '$1 zaische?',
'restorelink' => '{{PLURAL:$1|ä gleschdi Ännarung|$1 gleschde Ännarunge}}',
'site-rss-feed' => '$1 RSS-Feed',
'site-atom-feed' => '$1 Atom-Feed',
'page-rss-feed' => '"$1" RSS-Feed',
'page-atom-feed' => '"$1" Atom-Feed',
'red-link-title' => '$1 (Said gibbds nedd)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Said',
'nstab-user' => 'Benudzersaid',
'nstab-media' => 'Medije',
'nstab-special' => 'Schbezialsaid',
'nstab-project' => 'Bordal',
'nstab-image' => 'Dadai',
'nstab-mediawiki' => 'Middeelung',
'nstab-template' => 'Vorlach',
'nstab-help' => 'Unaschdidzung',
'nstab-category' => 'Kadegorie',

# General errors
'error' => 'Irrdumm',
'missing-article' => 'De Tegschd fa „$1“ $2 isch inde Daadebongg ned gfunne worre.

Noamalawees heeßd des, dass die Said gleschd worre isch.

Wonnse des awwa ned isch, hoschd villaischdn Irddumm inde Daadebongg gfunne.
Bidde meldsm [[Special:ListUsers/sysop|Adminischdrador]], un gebbde URL dzu aa.',
'missingarticle-rev' => '(Versionsnummer#: $1)',
'badtitle' => 'Schleschde Didl',
'badtitletext' => 'De Tidl vunde aagefordad Said isch nid gildisch, leer, oddan nid gildische Lingg vunem annare Wiki.
S kann sai, dass es ää odda meh Zaische drin hod, wu im Tidl vunde Said nid gbrauchd werre dirfn.',
'viewsource' => 'Gwelltegschd ogugge',

# Login and logout pages
'welcomeuser' => 'Willkumme, $1!',
'yourname' => 'Benudzernõme:',
'yourpassword' => 'Password:',
'yourpasswordagain' => 'Password nomol oigewe:',
'remembermypassword' => 'Mai Passwort uffm Computer merge (hegschdns fa $1 {{PLURAL:$1|Daach|Daach}})',
'login' => 'Õmelde',
'nav-login-createaccount' => 'Amelde / Benudzerkondo aleesche',
'loginprompt' => 'Cookies mugschd fa {{SITENAME}} schun ohawe.',
'userlogin' => 'Õmelde / Benudzerkondo õleesche',
'userloginnocreate' => 'Oilogge',
'logout' => 'Uffhere',
'userlogout' => 'Uffhere',
'nologin' => 'Hoschd noch kä Kondo? $1',
'nologinlink' => 'E Benudzerkondo aaleesche',
'createaccount' => 'Bnudza oleesche',
'gotaccount' => 'Hoschd schun ä Kondo? $1',
'gotaccountlink' => 'Õmelde',
'userlogin-resetlink' => 'Hoschd doi Daade vagesse?',
'mailmypassword' => 'Nais Passwort per E-Mail schigge',
'loginlanguagelabel' => 'Schbrooch: $1',

# Change password dialog
'resetpass-submit-loggedin' => 'Password wegsle',

# Special:PasswordReset
'passwordreset-username' => 'Benudza:',

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
'nowiki_sample' => 'Gebb do en Tegschd ai, wu nit uffberaid werd',
'nowiki_tip' => 'Wiki-Formatierunge ned beachde',
'image_tip' => 'Bildvawais',
'media_tip' => 'Dadailingg',
'sig_tip' => 'Dai Unnerschrifd mid ener Zaidaagab',
'hr_tip' => 'Horizontale Linie (sparsam verwenne)',

# Edit pages
'summary' => 'Iwwabligg:',
'subject' => 'Bedreff:',
'minoredit' => 'Des isch e klänni Bearwaidung',
'watchthis' => 'Die Said im Aach bhalde',
'savearticle' => 'Said schbeichere',
'preview' => 'Voaschau',
'showpreview' => 'Voaschau zaische',
'showlivepreview' => 'Live-Voaschau',
'showdiff' => 'Ännarunge zaische',
'anoneditwarning' => "'''Baßma uff:''' Du bischd nit aagemeld. Dai IP-Adress werd inde Gschischd vum Adiggl gschbaischad.",
'summary-preview' => 'Iwwabligg:',
'blockednoreason' => "s'hod kän Grund",
'newarticle' => '(Nai)',
'newarticletext' => "Du bisch eme Link nogange zu re Said, wu s no gar nit gebbt.
Zum die Said aaleche, kannscht do in dem Käschtel unne aafange mid schraiwe (guck[[{{MediaWiki:Helppage}}|Hilfe]] fer meh Informatione).
Wenn do nid hin hoscht welle, no druck in Daim Browser uff '''Zrick'''.",
'noarticletext' => 'Uffde Said hods noch kän Tegschd. Du konnschd uff onnare Saide nochm [[Special:Search/{{PAGENAME}}|Oidrach gugge]], <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} Logbuchaidrach gugge, wu dezu kead],
odda [{{fullurl:{{FULLPAGENAME}}|action=edit}} die Said beawaide]</span>.',
'updated' => '(Gännad)',
'note' => "'''Hiwes:'''",
'previewnote' => "'''Deng'g drɔ̃ɔ̃, dass des nua e Vorschau is.'''
Doi Ännerunge sinn no nedd gschbaischadd worre!",
'editing' => 'Õm $1 beawaide',
'editingsection' => '$1 beawaide (de Deel)',
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
'hiddencategories' => 'Die Said ghert zu {{PLURAL:$1|1 versteckelte Kategorie|$1 versteckelte Kategorie}}:',
'permissionserrorstext-withaction' => 'Du därfscht nid $2, wesche{{PLURAL:$1|m Grund|de Grind}}:',
'recreate-moveddeleted-warn' => "'''Baßma uff: Du magschd do ä Said, wuma frija schumol geleschd kabd hod.'''",
'moveddeleted-notice' => 'Die Said isch gleschd worre.
De Leschaidrach fa die Said isch do unne als Kwell aagewwe.',

# Content models
'content-model-css' => 'CSS',

# Parser/template warnings
'post-expand-template-inclusion-warning' => "'''Baßma uff:''' Greeß vunde Vorlach isch iwwaschridde. Oinischi Vorlache werren ned bnuzd.",
'post-expand-template-inclusion-category' => 'Saide mid Vorlache, wu die Greeß iwwaschridde worre isch',
'post-expand-template-argument-warning' => "'''Baßma uff:''' Die Said hod wenigschdns ä Vorlach mida Kenngreeß, wu groß werre dud. Die Kenngreeß wead do ned ogeguggd.",

# History pages
'viewpagelogs' => 'Lochbischer fer die Said aagucke',
'currentrev' => 'Ledschdi Änarung',
'currentrev-asof' => 'Agduell Ausgab vun $1',
'revisionasof' => 'Iwwaawaidung vun $1',
'revision-info' => 'Ännarung vun $1 duasch $2',
'previousrevision' => '← Älderi Beawaidung',
'nextrevision' => 'Naijare Ausgab →',
'currentrevisionlink' => 'Agduell Ausgab',
'cur' => 'jedzischi',
'next' => 'Negschd',
'last' => 'vorischi',
'page_first' => 'Easchd',
'page_last' => 'Ledschd',
'histlegend' => "Du kannscht zwää Versione auswähle un verglaiche.<br />
Erklärung: '''({{int:cur}})''' = Unnerschied zu jetzert,
'''({{int:last}})''' = Unnerschied zu de voriche Version, '''{{int:minoreditletter}}''' = klenni Ännerung.",
'history-fieldset-title' => 'Gugg die Gschischd',
'history-show-deleted' => 'Bloß gleschdi Saide zaische',
'histfirst' => 'Ältschde',
'histlast' => 'Naischde',
'historyempty' => '(lea)',

# Revision feed
'history-feed-item-nocomment' => '$1 õm $2',

# Revision deletion
'rev-delundel' => 'zaisch/verschdeggle',
'rev-showdeleted' => 'zaische',
'revdelete-show-file-submit' => 'Ja',
'revdelete-radio-same' => '(dudo nix ännare)',
'revdelete-radio-set' => 'Ja',
'revdelete-radio-unset' => 'Nä',
'revdelete-submit' => 'Uff die {{PLURAL:$1|gewehld Asugab|gewehldi Ausgawe}} owende',
'revdel-restore' => 'Sischdbakaid ännare',
'revdel-restore-deleted' => 'gleschdi Ännarunge',
'revdel-restore-visible' => 'sischdbari Ännarunge',
'pagehist' => 'Gschischd vunde Said',

# History merging
'mergehistory-go' => 'Zaisch, wasma vaoinische konn',

# Merge log
'revertmerge' => 'Zammefiehrung rigggängisch mache',

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
'prevn' => 'vorisch {{PLURAL:$1|$1}}',
'nextn' => 'negschd {{PLURAL:$1|$1}}',
'prevn-title' => 'Frijari $1 {{PLURAL:$1|Ergewnis|Ergewnis}}',
'nextn-title' => 'Negschdi $1 {{PLURAL:$1|Ergewnis|Ergewnis}}',
'shown-title' => 'Zaisch $1 {{PLURAL:$1|Ergewnis}} bro Said',
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
'searchprofile-advanced-tooltip' => 'Gugg in onare Nõmensreum',
'search-result-size' => '$1 ({{PLURAL:$2|1 Word|$2 Wärda}})',
'search-redirect' => '(Waidalaidung $1)',
'search-section' => '(Abschnidd $1)',
'search-suggest' => 'Hoschd gemäänd: $1',
'search-interwiki-caption' => 'Schweschterprojekt',
'search-interwiki-default' => '$1 Ergebnis:',
'search-interwiki-more' => '(meh)',
'search-relatedarticle' => 'Vawond',
'mwsuggest-disable' => 'Schald Voaschlesch ab',
'searchrelated' => 'vawond',
'searchall' => 'alle',
'showingresultsheader' => "{{PLURAL:$5|Ergewnis '''$1''' vun '''$3'''|Ergewnis '''$1–$2''' vun '''$3'''}} fa '''$4'''",
'nonefound' => "'''Hiiwais:''' S werre standardmäßich numme e Dail Namensraim durchsucht. Setz ''all:'' vor Dai Suchbegriff zum alle Saide (mit Dischbediersaide, Voalaache usw.) durchsuche odder direkt de Name vum Namensraum, wu durchsucht werre sell.",
'search-nonefound' => 'Fa die Such hods kä Ergewnis.',
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
'prefs-watchlist' => 'Beowachdungslischd',
'prefs-misc' => 'Schunschdisches',
'saveprefs' => 'Oischdellunge schbaischere',
'resetprefs' => 'Oischdellunge vawerfe',
'prefs-editing' => 'Schaffe',
'rows' => 'Zaile',
'columns' => 'Schbalde',
'searchresultshead' => 'Nochgugge',
'resultsperpage' => 'Dreffa bro Said',
'stub-threshold-disabled' => 'Abgschdeld',
'guesstimezone' => 'Aus em Browser iwwernemme',
'timezoneregion-europe' => 'Oirobba',
'prefs-searchoptions' => 'Nochgugge',
'youremail' => 'E-Mail:',
'yourrealname' => 'Birschalischa Nõme:',
'yourlanguage' => 'Schbrooch:',
'yournick' => 'Naiji Unnaschfrid',
'yourgender' => 'Gschleschd:',
'gender-unknown' => 'Ghoim gkalde',
'gender-male' => 'Männlisch',
'gender-female' => 'Waiblisch',
'prefs-help-email-others' => 'Konschd a wehle, ob onnare disch iwwan Lingg uff doina Dischbedier-Said oschbresche kennen.
Doi Meil-Address isch ned zaisch nix waida.',
'prefs-diffs' => 'Unaschied',

# User rights
'userrights-groupsmember' => 'Midglied vun:',

# Groups
'group' => 'Grubb:',
'group-user' => 'Benudza',
'group-bot' => 'Bots',
'group-sysop' => 'Adminischdradore',
'group-bureaucrat' => 'Birograde',
'group-all' => '(alle)',

'group-bot-member' => '{{GENDER:$1|Bot}}',
'group-sysop-member' => '{{GENDER:$1|Adminischdrador}}',
'group-bureaucrat-member' => '{{GENDER:$1|Birokrad}}',

'grouppage-sysop' => '{{ns:project}}:Adminischtratore',

# Rights
'right-move' => 'Said bwesche',
'right-move-subpages' => 'Said midde Unasaide bwesche',
'right-movefile' => 'Saide vaschiewe',
'right-upload' => 'Dadaije nufflade',
'right-delete' => 'Saide lesche',

# Special:Log/newusers
'newuserlogpage' => 'Naiaameldungs-Logbuch',

# User rights log
'rightslog' => 'Benutzerrecht-Logbuch',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'die Said beawaide',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|Ännarung|Ännarunge}}',
'recentchanges' => 'Ledschdi Ännarunge',
'recentchanges-legend' => 'Wehl ä Aazaisch fa die ledschdi Ännarunge',
'recentchanges-feed-description' => 'Di letschte Ännerunge vun {{SITENAME}} in des Feed aigewwe.',
'recentchanges-label-newpage' => 'Domid magschd ä naiji Said',
'recentchanges-label-minor' => "S'ische glänni Beawaidung",
'recentchanges-label-bot' => 'Ännarunge duaschn Bod',
'recentchanges-label-unpatrolled' => 'Die Änarung isch noch ned iwwabriefd worre',
'rcnote' => "Aagezaicht {{PLURAL:$1|werd '''1''' Ännerung|werre die letschte '''$1''' Ännerunge}} {{PLURAL:$2|vum letschte Dach|in de letschte '''$2''' Dache}} (Stand: $4, $5)",
'rclistfrom' => 'Zaisch die ledschd Ännarunge ab $1',
'rcshowhideminor' => 'Klenne Ännarunge $1',
'rcshowhidebots' => 'Bots $1',
'rcshowhideliu' => 'Aagemeldte Benutzer $1',
'rcshowhideanons' => 'Nit aagemeldt Benutzer $1',
'rcshowhidepatr' => '$1 iwabriefde Ännarunge',
'rcshowhidemine' => 'Mai Beawaidunge $1',
'rclinks' => 'Zaich die letschde $1 Ännarunge inde ledschde $2 Dach<br />$3',
'diff' => 'Unnaschied',
'hist' => 'Gschischd',
'hide' => 'vaschdeggle',
'show' => 'zaische',
'minoreditletter' => 'k',
'newpageletter' => 'N',
'boteditletter' => 'B',
'rc_categories_any' => 'Ebbes',
'rc-enhanced-expand' => 'Änzlhaide zaische (dozu brauchds JavaScript)',
'rc-enhanced-hide' => 'Ogawe vaschdeggle',

# Recent changes linked
'recentchangeslinked' => 'Was on verlinggde Saide gännad worre isch',
'recentchangeslinked-feed' => 'Ännarunge on valinggde Saide',
'recentchangeslinked-toolbox' => 'Ännarunge on verlingde Saide',
'recentchangeslinked-title' => 'Ännarunge on Saide, wu „$1“ druff verlinggd',
'recentchangeslinked-noresult' => 'Inde Zaid ischdo nix gännad worre.',
'recentchangeslinked-summary' => "Die Lischd zaischd ledschde Ännarunge, vunna bschdimmde Said, wu do valinggd isch (odda zu Midglied vuna bschdimmde Kadegorije isch).
Saide uff [[Special:Watchlist|Dainer Beowachdungslischd]] sinn '''fedd'''.",
'recentchangeslinked-page' => 'Saide:',
'recentchangeslinked-to' => 'Zaisch Ännarunge uff Saide, wu do her valinggd sinn',

# Upload
'upload' => 'Nufflade',
'uploadbtn' => 'Dadai nufflade',
'uploadlogpage' => 'Dadaije-Logbuch',
'filedesc' => 'Iwwabligg',
'fileuploadsummary' => 'Iwwabligg:',
'savefile' => 'Dadai schbaischere',
'uploadedimage' => 'hod „[[$1]]“ nuffglade',

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
'filehist-help' => 'Drigg uff e Zaidpunggd zum aazaische, wie s dord ausgseh hod.',
'filehist-deleteall' => 'alles lesche',
'filehist-deleteone' => 'lesche',
'filehist-revert' => 'zriggsedze',
'filehist-current' => 'agduell',
'filehist-datetime' => 'Zaidpungd',
'filehist-thumb' => 'Gleenes Bild',
'filehist-thumbtext' => 'Skizz fa die Ausgab vum $1',
'filehist-user' => 'Benudzer',
'filehist-dimensions' => 'Maß',
'filehist-comment' => 'Aißarung',
'imagelinks' => 'Dadaivawendung',
'linkstoimage' => 'Die {{PLURAL:$1|Said verwaisd|$1 Saide verwaise}} uff die Datei:',
'nolinkstoimage' => 'Do hods kä Said, wu dohea zaischd.',
'sharedupload' => 'Die Datei isch vun $1 un s kann sai, dass se ach vun annere Projekt gebraucht werd.',
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
'randompage' => 'Irschnd en Adiggl',

# Statistics
'statistics' => 'Schdadischdigge',
'statistics-pages' => 'Saide',

'disambiguationspage' => 'Template:Vadaidlischung',

'brokenredirects-edit' => 'beawaide',
'brokenredirects-delete' => 'lesche',

'withoutinterwiki-submit' => 'Zaische',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|Byte|Bytes}}',
'nmembers' => '$1 {{PLURAL:$1|Dailnemma|Dailnemma}}',
'prefixindex' => 'Alle Saide (mid Präfix)',
'listusers-editsonly' => 'Zaisch bloß Bnudza mid Baidräsch',
'newpages' => 'Naije Saide',
'move' => 'Verschiewe',
'movethispage' => 'Die Said verschiewe',
'pager-newer-n' => '{{PLURAL:$1|negschte 1|negschte $1}}',
'pager-older-n' => '{{PLURAL:$1|vorich 1|voriche $1}}',

# Book sources
'booksources' => 'Buchgwelle',
'booksources-search-legend' => 'No Buchquelle suche',
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
'categories' => 'Kadegorije',

# Special:LinkSearch
'linksearch' => 'Exderne Lings',
'linksearch-line' => '$1 isch vun $2 valinggd',

# Special:ListGroupRights
'listgrouprights-members' => '(Midgliederlischd)',

# Email user
'emailuser' => 'E-Mail on de Benutzer',
'emailfrom' => 'Vum:',
'emailto' => 'Fa:',
'emailsubject' => 'Bdreff:',
'emailmessage' => 'Middeelung:',
'emailsend' => 'Abschigge',

# Watchlist
'watchlist' => 'Beowachdungslischd',
'mywatchlist' => 'Beowachdungslischd',
'watchlistfor2' => 'Vun $1 $2',
'addedwatchtext' => "Die Said \"[[:\$1]]\" isch zu Doina [[Special:Watchlist|Beowachdungslischt]] zugfieschd worre.
Zukinfdischi Ännarunge onde Said unde Dischbediersaid, wu dzu kead, werren doo aagzaischd, un die Said werd '''fedd''' aagzaisch inde [[Special:RecentChanges|Ledschdi Ännarunge]] domidmas efacha finne konn.",
'removedwatchtext' => 'D Said "[[:$1]]" isch aus [[Special:Watchlist|Dainer Beowachdungslischt]] rausgenumme worre.',
'watch' => 'Beowachde',
'watchthispage' => 'Die Said beowachde',
'unwatch' => 'Nimmi beowachde',
'watchlist-details' => 'S hot {{PLURAL:$1|$1 Said|$1 Saide}} uff Dainer Beowachdungslischt, Dischbediersaide zelle nid.',
'wlshowlast' => 'Die letschte $1 Stunne $2 Dache $3 zaiche',
'watchlist-options' => 'Optione vun de Beowachdungslischt',

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
'actioncomplete' => 'Agzion ferdisch',
'actionfailed' => 'Maßnohm gschaidad',
'deletedtext' => '"$1" isch gelescht worre.
Guck $2 fer e Lischt vun de letschte Leschunge.',
'dellogpage' => 'Leschlogbuch',
'deletecomment' => 'Grund:',
'deleteotherreason' => 'Annere/zusätzliche Grund:',
'deletereasonotherlist' => 'Annere Grund',

# Rollback
'rollbacklink' => 'Zeriggsedze',

# Protect
'protectlogpage' => 'Saideschutz-Logbuch',
'protectedarticle' => 'hod "[[$1]]" gschizd',
'modifiedarticleprotection' => 'hot de Schutzstatus vun "[[$1]]" gännert',
'protectcomment' => 'Grund:',
'protectexpiry' => 'Bis:',
'protect_expiry_invalid' => 'Zaidraum isch nid gildich.',
'protect_expiry_old' => 'Zaidraum licht in de Vergangehääd.',
'protect-text' => "Du kannscht de Schutzstatus vun de Said '''$1''' aagucke un ännere.",
'protect-locked-access' => "Dai Benutzerkonto hot ken Recht zum de Schutzstatus vun ener Said ze ännere.
Do hot s di aktuelle Aistellunge vun de Said '''$1''':",
'protect-cascadeon' => 'Die Said isch gschizd, wail se {{PLURAL:$1|zu derre Said ghert|zu denne Saide ghert}}, wu e Kaskadesperrung gelt.
Der Schutzstatus vun derre Said kannscht ännere, awwer des hot kää Aifluss uff d Kaskadesperrung.',
'protect-default' => 'Alle Benudzer erlauwe',
'protect-fallback' => 'Bloß fa Laid mid "$1" Bereschdischung',
'protect-level-autoconfirmed' => 'Naije un nid aagemeldte Benutzer schberre',
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
'undeletelink' => 'aagucke/widda herschdelle',
'undeleteviewlink' => 'Ogugge',
'undeletereset' => 'Zuriggsedze',
'undelete-show-file-submit' => 'Ja',

# Namespace form on various pages
'namespace' => 'Nõmensraum',
'invert' => 'Wahl dausche',
'blanknamespace' => '(Schdadsaid)',

# Contributions
'contributions' => '{{GENDER:$1|Wasa gemachd hod}}',
'contributions-title' => 'Benutzerbaidräch vun $1',
'mycontris' => 'Baidräsch',
'contribsub2' => 'Fer $1 ($2)',
'uctop' => '(akduell)',
'month' => 'än Monad (un frija):',
'year' => 'Abm Johr (un frieja):',

'sp-contributions-newbies' => 'Zaisch nua Baidräsch vun naije Konde',
'sp-contributions-blocklog' => 'Schberrlogbuch',
'sp-contributions-uploads' => 'Nufflade',
'sp-contributions-logs' => 'Logbischa',
'sp-contributions-talk' => 'Dischbediere',
'sp-contributions-search' => 'Noch Baidräsch gugge',
'sp-contributions-username' => 'IP-Adress odda Benudzernõme:',
'sp-contributions-toponly' => 'Bloß agduelli Ännarunge zaische',
'sp-contributions-submit' => 'Suche',

# What links here
'whatlinkshere' => 'Was doher zaische dud',
'whatlinkshere-title' => 'Saide wu uff "$1" verlinke',
'whatlinkshere-page' => 'Said:',
'linkshere' => "Die Saide valingge uff '''[[:$1]]''':",
'nolinkshere' => "Kä Said zaischd uff '''[[:$1]]'''.",
'isredirect' => 'Waidalaidungsaid',
'istemplate' => 'Vorlacheoibindung',
'isimage' => 'Dadailingg',
'whatlinkshere-prev' => '{{PLURAL:$1|vorich|voriche $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|negschd|negschde $1}}',
'whatlinkshere-links' => '← Linggs',
'whatlinkshere-hideredirs' => '$1 Waidalaidunge',
'whatlinkshere-hidetrans' => '$1 Vorlacheaibindunge',
'whatlinkshere-hidelinks' => '$1 Linggs',
'whatlinkshere-filters' => 'Filda',

# Block/unblock
'blockip' => 'Benudzer bloggiere',
'ipbsubmit' => 'Benudzer bloggiere',
'ipboptions' => '2 Stunne:2 hours,1 Dach:1 day,3 Dache:3 days,1 Woch:1 week,2 Woche:2 weeks,1 Monet:1 month,3 Monet:3 months,6 Monet:6 months,1 Johr:1 year,Fer immer:infinite',
'ipbotheroption' => 'onnari',
'ipusubmit' => 'Die Adreß fraigewwe',
'ipblocklist' => 'Gschberrdi IP-Adress un Benudzernõme',
'infiniteblock' => 'ubgrensd',
'blocklink' => 'schberre',
'unblocklink' => 'Sperr uffhewe',
'change-blocklink' => 'Schberr ännare',
'contribslink' => 'Baidräsch',
'blocklogpage' => 'Schberrlogbuch',
'blocklogentry' => 'hot [[$1]] gsperrt fer e Zaidraum vun $2 $3',
'unblocklogentry' => 'hot d Sperr vun $1 uffghowwe',
'block-log-flags-nocreate' => 'Aalesche vun Benudzerkonde isch gschberrd',

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
'movelogpage' => 'Verschiewungs-Logbuch',
'movereason' => 'Grund:',
'revertmove' => 'Zurigg vaschiewe',
'delete_and_move' => 'Lesche un Verschiewe',
'delete_and_move_confirm' => 'Ja, Said lesche',

# Export
'export' => 'Saide expordiere',
'export-submit' => 'Saide exbordiere',

# Namespace 8 related
'allmessagesname' => 'Nõme',
'allmessagesdefault' => 'Vorgewene Tegschd',
'allmessages-filter-modified' => 'Vaännad',

# Thumbnails
'thumbnail-more' => 'Mags greßa',

# Special:Import
'import-interwiki-submit' => 'Impordiere',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Dai Benudzersaid',
'tooltip-pt-mytalk' => 'Dai Said fas Dischbediere',
'tooltip-pt-preferences' => 'Dai Aistellunge',
'tooltip-pt-watchlist' => 'D Lischd vun Saide, wu du beowachde duschd',
'tooltip-pt-mycontris' => 'Lischd vun Daine Baidräsch',
'tooltip-pt-login' => 'Du konnschd disch õmelde, awwa du mugschds ned',
'tooltip-pt-logout' => 'Abmelde',
'tooltip-ca-talk' => 'Iwwa d Inhaldssaid dischbediere',
'tooltip-ca-edit' => 'Du konnschd die Said beawaide.
Bidde nemmde Vorschau-Gnobb vorm Schbaischare',
'tooltip-ca-addsection' => 'E naie Abschnitt aaleche',
'tooltip-ca-viewsource' => 'Die Said isch gschizd. Du konnschd awwa de Gwelltegschd aagugge.',
'tooltip-ca-history' => 'Ledschde Versione vun derre Said',
'tooltip-ca-protect' => 'Die Said schidze',
'tooltip-ca-delete' => 'Die Said lesche',
'tooltip-ca-move' => 'Die Said vaschiewe',
'tooltip-ca-watch' => 'Die Said zu Dainer Beowachdungslischd zufiesche',
'tooltip-ca-unwatch' => 'Die Said aus Dainer Beowachdunschlischd wegnemme',
'tooltip-search' => 'Gugg uff {{SITENAME}} fa',
'tooltip-search-go' => 'Geh zu ere Said mid genää dem Namme, wenn s se gebbt',
'tooltip-search-fulltext' => 'Gugg inde Said nochm Tegschd',
'tooltip-p-logo' => 'Schdadsaid',
'tooltip-n-mainpage' => 'Uff d Schdadsaid geje',
'tooltip-n-mainpage-description' => 'Haubdsaid õgugge',
'tooltip-n-portal' => 'Iwwas Brojegd, wu mache konnschd, wu ebbes finne duschd',
'tooltip-n-currentevents' => 'Finn Auskinfd iwwa naiji Voafell',
'tooltip-n-recentchanges' => 'Lischd vun de ledschde Ännarunge im Wiki',
'tooltip-n-randompage' => 'Lad e zufellischi Said',
'tooltip-n-help' => 'Do konschds rausfinne',
'tooltip-t-whatlinkshere' => 'Lischd vun alle Wikisaide, wu do hie verlingd sinn',
'tooltip-t-recentchangeslinked' => 'Ledschde Ännerunge in Saide, wu vun do verlinggd sin',
'tooltip-feed-rss' => 'RSS feed fer die Said',
'tooltip-feed-atom' => 'Atom-Feed fa die Said',
'tooltip-t-contributions' => 'Die ledschde Baidräsch vum Benudzer aagugge',
'tooltip-t-emailuser' => 'Dem Benutzer e E-Mail schicke',
'tooltip-t-upload' => 'Dadaije nufflade',
'tooltip-t-specialpages' => 'Lischd vunde Schbezialsaide',
'tooltip-t-print' => 'Druggausgab vunde Said',
'tooltip-t-permalink' => 'E dauerhafte Link zu derre Version vun de Said',
'tooltip-ca-nstab-main' => 'Inhald agugge',
'tooltip-ca-nstab-user' => 'D Benudzersaid aagucke',
'tooltip-ca-nstab-special' => 'Des isch e Spezialsaid, du kannscht d Said sälwerscht nit ännere',
'tooltip-ca-nstab-project' => 'Brojegdsaid agugge',
'tooltip-ca-nstab-image' => 'D Dadaisaid aaugugge',
'tooltip-ca-nstab-template' => 'Vorlach aagugge',
'tooltip-ca-nstab-category' => 'D Kadegoriesaid aagucke',
'tooltip-minoredit' => 'Des als klenni Ännarung makiere',
'tooltip-save' => 'Dai Ännerunge schbaischere',
'tooltip-preview' => 'Guck Daine Ännerunge in de Vorschau aa, vor Du uff Spaichere driksch!',
'tooltip-diff' => 'Guck, welle Ännerunge Du im Text gmacht hoscht',
'tooltip-compareselectedversions' => 'D Unnaschied zwische denne zwee gwehlde Versione aagugge',
'tooltip-watch' => 'Die Said zu Dainer Beowachdunglischd zufieche',
'tooltip-rollback' => "„Zriggsedze“ machd alli Beawaidunge vum ledschde Midawaida rigg'gängisch",
'tooltip-undo' => "„Zrigg“ machd nua die Ännarung rigg'gängich un zaischd ä Vorschau aa.
Du kannschd e Grund in de Zommefassung aagewwe.",
'tooltip-summary' => 'Gebä koaz Resimee',

# Browsing diffs
'previousdiff' => '← Ältere Bearwaidung',
'nextdiff' => 'Naijari Beawaidung →',

# Media information
'file-info-size' => '$1 × $2 Pixels, Dateigreß: $3, MIME-Type: $4',
'file-nohires' => 'Ke heheri Ufflesung vafieschba.',
'svg-long-desc' => 'SVG-Datei, Basisgreß $1 × $2 Pixels, Dateigreß: $3',
'show-big-image' => 'Volli Ufflesung',

# Special:NewFiles
'showhidebots' => '(Bots $1)',
'ilsubmit' => 'Such',

# Bad image list
'bad_image_list' => 'Formad:

nur Zaile, wu mid em * õfange werren beriggsischdischd.
De erschd Lingg mussn Lingg zu änna uuerwinschdi Dadai soi.
Annare Linggs inde glaische Zail werren als Ausnõhm behonneld, des heesd, Saide, wu inde Dadai vorkumme dirfn.',

# Metadata
'metadata' => 'Metadata',
'metadata-help' => 'Die Dadai hod waideri Ogawe, waaschoinlisch vunde Digidalkomara odda vum Skänna, wuse mid gmachd worre isch.
Wpnn die Dadai vaännad worre isch, donn konns soi, daß zusedzlischi Ogawe fa die vaännad Dadai nemme rischdisch sin.',
'metadata-expand' => 'Erwaiterte Details aazaiche',
'metadata-collapse' => 'Erwaiterte Details versteckeln',
'metadata-fields' => 'Die EXIF-Medadaade werren inde Bild-Bschraiwung a ogzaischd, wonn die Medadaade-Tabelle verschdegld isch.
Annere Medadaade sinn noamalawais verschdegld.
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

'exif-gaincontrol-0' => 'Kään',

# External editor support
'edit-externally' => 'Die Datei bearwaide mit ener externe Aawendung',
'edit-externally-help' => '(Guck uff [//www.mediawiki.org/wiki/Manual:External_editors Installationsaawisige] fer meh Informatione)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'alle',
'namespacesall' => 'alle',
'monthsall' => 'alle',

# Watchlist editing tools
'watchlisttools-view' => 'Die wichdiche Ännerunge aagucke',
'watchlisttools-edit' => 'D Beowachdunglischt aagucke un bearwaide',
'watchlisttools-raw' => 'ime große Textfeld bearwaide',

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

);

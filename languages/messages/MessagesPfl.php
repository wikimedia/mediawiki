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
'tog-showtoolbar' => "Werkzaisch fas Beawaide zaische (dodezu brauchd's JavaScript)",
'tog-showhiddencats' => 'Zaisch vaschdeglde Kadegorije',

'underline-always' => 'Imma',
'underline-never' => 'Gaaned',
'underline-default' => 'Des nemme, wum Browser gsachd hoschd.',

# Dates
'sunday' => 'Sundaach',
'monday' => 'Mondaach',
'tuesday' => 'Dienschdaach',
'wednesday' => 'Midwoch',
'thursday' => 'Dunnaschdaach',
'friday' => 'Fraidaach',
'saturday' => 'Somschdaach',
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
'september' => 'Sebdember',
'october' => 'Ogdower',
'november' => 'Nowember',
'december' => 'Dezember',
'january-gen' => 'Janua',
'february-gen' => 'Februa',
'march-gen' => 'März',
'april-gen' => 'Abril',
'may-gen' => 'Mai',
'june-gen' => 'Juni',
'july-gen' => 'Juli',
'august-gen' => 'Auguschd',
'september-gen' => 'Sebdember',
'october-gen' => 'Ogdower',
'november-gen' => 'Nowember',
'december-gen' => 'Dezember',
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
'pagecategories' => '{{PLURAL:$1|Kadegorie|Kadegorie}}',
'category_header' => 'Saide in de Kadegorie „$1“',
'subcategories' => 'Unnakadegorie',
'category-media-header' => 'Medie in de Kadegorie „$1“',
'category-empty' => '"Die Kadegorie hod kä Said oda Medije."',
'hidden-categories' => '{{PLURAL:$1|Verschdegelde Kadegorie|Verschdegelde Kadegorije}}',
'hidden-category-category' => 'Verschdegelde Kadegorije',
'category-subcat-count' => '{{PLURAL:$2|Die Kadegorie hod bloß die Unnakadegorie.|Die Kadegorie hod {{PLURAL:$1|Unnakadegorie|$1 Unnakadegorije}},vun gsomd $2.}}',
'category-article-count' => "{{PLURAL:$2|In derre Kadegorie hot's numme die Said.|Die {{PLURAL:$1|Said|$1 Saide}} gebbt's in derre Kadegorie, vun insgsamt $2.}}",
'category-file-count' => "{{PLURAL:$2|Die Kadegorie hod bloß ä Said.|Die {{PLURAL:$1|Said isch äni vun $2 Saide:|S'werren $1 vun gsomd $2 Saide gzaischd:}}}}",
'listingcontinuesabbrev' => '(Forts.)',
'noindex-category' => 'Saide, wu ned im Vazaischnis sin',

'about' => 'Iwwa',
'newwindow' => '(werd im e naie Fenschter uffgmacht)',
'cancel' => 'Abbresche',
'moredotdotdot' => 'Meh …',
'mypage' => 'Said',
'mytalk' => 'Dischbediere',
'navigation' => 'Nawigadzion',

# Cologne Blue skin
'qbfind' => 'Finne',
'qbbrowse' => 'Duaschschdewere',
'qbedit' => 'Beawaide',
'qbpageoptions' => 'Die Said',
'qbmyoptions' => 'Mai Saide',
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
'vector-view-viewsource' => 'Gwelltegschd ozaische',
'actions' => 'Agzione',
'namespaces' => 'Nomensreum',
'variants' => 'Tibbe',

'errorpagetitle' => 'Fehler',
'returnto' => 'Zrick zu $1.',
'tagline' => 'Vun {{SITENAME}}',
'help' => 'Hilf',
'search' => 'Suche',
'searchbutton' => 'Such',
'go' => 'Adiggl',
'searcharticle' => 'Suche',
'history' => 'Gschichd vun de Said',
'history_short' => 'Gschischd',
'printableversion' => 'Drugg-Aasischd',
'permalink' => 'Permanentlink',
'print' => 'Ausdrugge',
'view' => 'Lese',
'edit' => 'Beawaide',
'create' => 'Aleesche',
'editthispage' => 'Die Said beawaide',
'delete' => 'Lesche',
'deletethispage' => 'Lesch die Said',
'undelete_short' => '{{PLURAL:$1|ä Ännerung|$1 Ännerunge}} widderherschdelle',
'viewdeleted_short' => 'Zaisch {{PLURAL:$1|ä gleschdi Ännarung|$1 gleschde Ännarunge}}',
'protect' => 'schidze',
'protect_change' => 'ännare',
'protectthispage' => 'Die Said schidze',
'unprotect' => 'Saideschudz änare',
'newpage' => 'Naiji Said',
'talkpage' => 'Iwwer die Said dischbediere',
'talkpagelinktext' => 'Dischbediere',
'personaltools' => 'Persenlischs Wergzaisch',
'postcomment' => 'Naije Abschnidd',
'talk' => 'Dischbediere',
'views' => 'Uffruf',
'toolbox' => 'Wergzaisch',
'viewtalkpage' => 'Zaischs Gbabbl',
'otherlanguages' => 'In annare Schbroche',
'redirectedfrom' => '(Wairrerglaidet vun $1)',
'redirectpagesub' => 'Wairerlaidungssaid',
'lastmodifiedat' => 'Die Said isch zum ledschde Mol gännad worre om $1, om $2.',
'viewcount' => 'Die Seid isch bis jetzerd {{PLURAL:$1|$1|$1}} mol uffgerufe worre.',
'protectedpage' => 'Said schidze',
'jumpto' => 'Wegsl zu:',
'jumptonavigation' => 'Nawigadzion',
'jumptosearch' => 'Suche',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => 'Iwwa {{SITENAME}}',
'aboutpage' => 'Project:Iwwa',
'copyright' => 'Was do drin schdeht isch unner $1 verfiechbar.',
'copyrightpage' => '{{ns:project}}:Urhewareschd',
'currentevents' => 'Was grad bassierd isch',
'currentevents-url' => 'Project: Leschdi Eraischniss',
'disclaimers' => 'Hafdungsausschluß',
'disclaimerpage' => 'Project:Impressum',
'edithelp' => 'Hilf fas Beawaide',
'edithelppage' => 'Help:Ännare',
'helppage' => 'Help:Inhald',
'mainpage' => 'Schdadsaid',
'mainpage-description' => 'Schdadsaid',
'portal' => '{{SITENAME}}-Bordal',
'portal-url' => 'Project:Gmoinschafdsbordal',
'privacy' => 'Dadeschuds',
'privacypage' => 'Project:Daadeschutz',

'badaccess' => 'Ned genuch Reschd',

'ok' => 'Alla gud',
'retrievedfrom' => 'Vun "$1"',
'youhavenewmessages' => 'Du hoscht $1 ($2).',
'newmessageslink' => 'naie Nochrischde',
'newmessagesdifflink' => 'ledschde Ännerung',
'editsection' => 'beawaide',
'editold' => 'beawaide',
'viewsourceold' => 'Gwuelltegschd ogugge',
'editlink' => 'beawaide',
'viewsourcelink' => 'Gwell aagugge',
'editsectionhint' => 'Abschnidd ännere: $1',
'toc' => 'Inhald',
'showtoc' => 'zaische',
'hidetoc' => 'versteggle',
'collapsible-collapse' => 'Uffglabbe',
'restorelink' => '{{PLURAL:$1|ä gleschdi Ännarung|$1 gleschde Ännarunge}}',
'site-rss-feed' => '$1 RSS Feed',
'site-atom-feed' => '$1 Atom Feed',
'page-rss-feed' => '"$1" RSS Feed',
'page-atom-feed' => '"$1" Atom Feed',
'red-link-title' => '$1 (Said gebbds nid)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Said',
'nstab-user' => 'Benudzersaid',
'nstab-media' => 'Media',
'nstab-special' => 'Schbezialsaid',
'nstab-project' => 'Bordal',
'nstab-image' => 'Dadai',
'nstab-mediawiki' => 'Middeelung',
'nstab-template' => 'Vorlach',
'nstab-help' => 'Hilf',
'nstab-category' => 'Kadegorie',

# General errors
'missing-article' => "De Text fer „$1“ $2 isch inde Daadebong'g nit gfunne worre.

Normalerwais hääßd des, dass die Said gleschd worre isch.

Wenns des nit isch, hoschd villaischd en Fehler in de Daadebong'g gfunne.
Bidde meldsm [[Special:ListUsers/sysop|Adminischdrador]], un gebb d URL dezu aa.",
'missingarticle-rev' => '(Versionsnummer#: $1)',
'badtitle' => 'Schleschde Didl',
'badtitletext' => 'De Titel vun de aageforderte Said isch nid giltich, leer, odder e nid giltiche Link vun eme annere Wiki.
S kann sai, dass es ää odder meh Zaiche drin hot, wu im Titel vun de Said nid gebraucht werre därfe.',
'viewsource' => 'Gwelltegschd ogugge',

# Login and logout pages
'yourname' => 'Benudzername:',
'yourpassword' => 'Password:',
'yourpasswordagain' => 'Password nomol oigewe:',
'remembermypassword' => 'Mai Passwort uff dem Computer merke (hechschtens fer $1 {{PLURAL:$1|Dach|Dach}})',
'login' => 'Omelde',
'nav-login-createaccount' => 'Aamelde / Benudzerkondo aaleeche',
'loginprompt' => 'Cookies mugschd fa {{SITENAME}} schun ohawe.',
'userlogin' => 'Omelde / Benutzerkonto oleesche',
'userloginnocreate' => 'Oilogge',
'logout' => 'Uffhere',
'userlogout' => 'Uffhere',
'nologin' => 'Hoschd noch kä Kondo? $1',
'nologinlink' => 'E Benutzerkondo aaleche',
'createaccount' => 'Bnudza oleesche',
'gotaccount' => 'Hoschd schun ä Kondo? $1',
'gotaccountlink' => 'Omelde',
'userlogin-resetlink' => 'Hoschd doi Dade vagesse?',
'mailmypassword' => 'Nais Passwort per E-Mail schicke',
'loginlanguagelabel' => 'Schbrooch: $1',

# Change password dialog
'resetpass-submit-loggedin' => 'Password wegsle',

# Special:PasswordReset
'passwordreset-username' => 'Benudza:',

# Special:ChangeEmail
'changeemail-cancel' => 'Uffhere',

# Edit page toolbar
'bold_sample' => 'Feddi Schrifd',
'bold_tip' => 'Fedde Schrifd',
'italic_sample' => 'Kursiv Schrifd',
'italic_tip' => 'Kursiv Schrifd',
'link_sample' => 'Schdischword',
'link_tip' => 'Inderna Lingg',
'extlink_sample' => "http://www.example.com Ling'gtegschd",
'extlink_tip' => 'Externer Link (uff http:// Acht gewwe)',
'headline_sample' => 'Schlaachzail Iwwaschrifd',
'headline_tip' => 'Iwwerschrift Ewene 2',
'nowiki_sample' => 'Gebb do en Tegschd ai, wu nit formatiert werd',
'nowiki_tip' => 'Wiki-Formatierunge ignoriere',
'image_tip' => 'Bildverwais',
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
'showpreview' => 'Vorschau zaische',
'showlivepreview' => 'Live-Vorschau',
'showdiff' => 'Ännerunge zaische',
'anoneditwarning' => "'''Baßma uff:''' Du bischd nit aagemeldt. Dai IP-Adress werd in de Gschichd vum Aadiggl gspaischad.",
'summary-preview' => 'Iwwabligg:',
'blockednoreason' => "s'hod kän Grund",
'newarticle' => '(Nai)',
'newarticletext' => "Du bisch eme Link nogange zu re Said, wu s no gar nit gebbt.
Zum die Said aaleche, kannscht do in dem Käschtel unne aafange mid schraiwe (guck[[{{MediaWiki:Helppage}}|Hilfe]] fer meh Informatione).
Wenn do nid hin hoscht welle, no druck in Daim Browser uff '''Zrick'''.",
'noarticletext' => 'Uff de Said hods noch kän Tegschd. Du konnschd uff onnere Saide nochm [[Special:Search/{{PAGENAME}}|Aidrach gugge]], <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} Logbuchaidrach gugge, wu dezu gherd],
odda [{{fullurl:{{FULLPAGENAME}}|action=edit}} die Said bearwaide]</span>.',
'note' => "'''Hiwes:'''",
'previewnote' => "'''Deng'g droa, dass des numme e Vorschau isch.'''
Doi Ännerunge sinn no nid gschbaichert worre!",
'editing' => 'Am $1 bearwaide',
'editingsection' => '$1 bearwaide (Abschnitt)',
'yourtext' => 'Doin Tegschd',
'storedversion' => 'Gschbaischerdi Version',
'yourdiff' => 'Unaschied',
'copyrightwarning' => "Bidde gebb achd, dass alle Baidräch zu {{SITENAME}} unner $2 vereffentlischd werre (guck $1 fer mehr Details).
Wenn du nit willschd, dass deswu du gschriwwe hoschd, gänneret un kopierd werre kann, dann duu s do nit naischraiwe.<br />
du gebbschd do au zu, dass Du des selwerschd gschriwwe hoschd orrer vun ere effendliche, fraie Quell ('''public domain''') orrer vun ere ähnliche fraie Quell her hoschd.
'''SCHRAIB DO NIX NAI, WAS URHEWERRECHDLICH GSCHIZD ISCH!'''",
'templatesused' => '{{PLURAL:$1|Vorlach wu uff derre Said gbrauchd werd|Vorlache wu uff derre Said gbrauchd werre}}:',
'templatesusedpreview' => '{{PLURAL:$1|Vorlach wu in derre Vorschau gbrauchd werd|Vorlache wu in derre Vorschau gbrauchd werre}}:',
'template-protected' => '(gschizd)',
'template-semiprotected' => '(halb-gschizd)',
'hiddencategories' => 'Die Said ghert zu {{PLURAL:$1|1 versteckelte Kategorie|$1 versteckelte Kategorie}}:',
'permissionserrorstext-withaction' => 'Du därfscht nid $2, wesche{{PLURAL:$1|m Grund|de Grind}}:',
'recreate-moveddeleted-warn' => "'''Baßma uff: Du magschd do ä Said, wuma frija schumol geleschd kabd hod.'''",
'moveddeleted-notice' => 'Die Said isch gleschd worre.
De Leschaidrach fa die Said isch do unne als Kwell aagewwe.',

# Parser/template warnings
'post-expand-template-inclusion-warning' => "'''Baßma uff:''' Greeß vunde Vorlach isch iwwaschridde. Oinischi Vorlache werren ned bnuzd.",
'post-expand-template-inclusion-category' => 'Saide mid Vorlache, wu die Greeß iwwaschridde worre isch',
'post-expand-template-argument-warning' => "'''Baßma uff:''' Die Said hod wenigschdns ä Vorlach mida Kenngreeß, wu groß werre dud. Die Kenngreeß wead do ned ogeguggd.",

# History pages
'viewpagelogs' => 'Lochbischer fer die Said aagucke',
'currentrev' => 'Ledschdi Änarung',
'currentrev-asof' => 'Agduell Version vun $1',
'revisionasof' => 'Version vun $1',
'revision-info' => 'Ännarung vun $1 duasch $2',
'previousrevision' => '← Älderi Beawaidung',
'nextrevision' => 'Naijare Versione →',
'currentrevisionlink' => 'Agduell Version',
'cur' => 'jedzischi',
'next' => 'Negschd',
'last' => 'vorischi',
'page_first' => 'Easchd',
'page_last' => 'Ledschd',
'histlegend' => "Du kannscht zwää Versione auswähle un verglaiche.<br />
Erklärung: '''({{int:cur}})''' = Unnerschied zu jetzert,
'''({{int:last}})''' = Unnerschied zu de voriche Version, '''{{int:minoreditletter}}''' = klenni Ännerung.",
'history-fieldset-title' => 'In de Versionsgschichd gugge',
'history-show-deleted' => 'Bloß gleschdi Saide zaische',
'histfirst' => 'Ältschde',
'histlast' => 'Naischde',

# Revision feed
'history-feed-item-nocomment' => '$1 om $2',

# Revision deletion
'rev-delundel' => 'zaisch/verschdeggle',
'rev-showdeleted' => 'zaische',
'revdelete-show-file-submit' => 'Ja',
'revdelete-radio-set' => 'Ja',
'revdelete-radio-unset' => 'Nä',
'revdelete-submit' => 'Uff die gewehld {{PLURAL:$1|Version|Versione}} owende',
'revdel-restore' => 'Sischdbakaid ännere',
'revdel-restore-deleted' => 'gleschdi Änarunge',
'revdel-restore-visible' => 'sischdbari Änarunge',

# Merge log
'revertmerge' => 'Zammefiehrung rickgängich mache',

# Diffs
'history-title' => 'Änarungsgschischd vun "$1"',
'lineno' => 'Zail $1:',
'compareselectedversions' => 'Die Versione midnonna vaglaische',
'editundo' => 'zrigg',

# Search results
'searchresults' => 'Ergewnis suche',
'searchresults-title' => 'Ergewnis suche fer "$1"',
'searchresulttext' => 'Fer mehr Informatione iwwer d Such in {{SITENAME}}, guck emol uff [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle' => 'Du hoscht no \'\'\'[[:$1]]\'\'\' gesucht ([[Special:Prefixindex/$1|alle Saide wo mit "$1" aafange]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|alle Saide wo uf "$1" verlinkt sin]])',
'searchsubtitleinvalid' => "Du hoscht '''$1''' gsucht",
'notitlematches' => 'Kän Saidedidel gfunne',
'notextmatches' => 'Kää Iwwerainstimmunge mit Inhalde',
'prevn' => 'vorisch {{PLURAL:$1|$1}}',
'nextn' => 'negschde {{PLURAL:$1|$1}}',
'prevn-title' => 'Frijari $1 {{PLURAL:$1|result|Ergewnis}}',
'nextn-title' => 'Negschdi $1 {{PLURAL:$1|result|Ergewnis}}',
'shown-title' => 'Zaisch $1 {{PLURAL:$1|Ergewnis}} vunde Said',
'viewprevnext' => 'Gugg ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-new' => "'''Mach die Said „[[:$1]]“ im Wiki.'''",
'searchprofile-articles' => 'Inhald',
'searchprofile-project' => 'Hilf- un Brojegdsaide',
'searchprofile-images' => 'Multimedia',
'searchprofile-everything' => 'Alles',
'searchprofile-advanced' => 'Foadgschridde',
'searchprofile-articles-tooltip' => 'In $1 gugge',
'searchprofile-project-tooltip' => 'In $1 gugge',
'searchprofile-images-tooltip' => 'Gugg noch Bilder',
'searchprofile-everything-tooltip' => 'Such iwwaraal (a wuma dischbedierd)',
'searchprofile-advanced-tooltip' => 'Gugg in onare Nomensraim',
'search-result-size' => '$1 ({{PLURAL:$2|1 Word|$2 Wärder}})',
'search-redirect' => '(Waidalaidung $1)',
'search-section' => '(Abschnidd $1)',
'search-suggest' => 'Hoschd gemäänd: $1',
'search-interwiki-caption' => 'Schweschterprojekt',
'search-interwiki-default' => '$1 Ergebnis:',
'search-interwiki-more' => '(meh)',
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
'powersearch-togglenone' => 'Kään',

# Preferences page
'preferences' => 'Obzione',
'mypreferences' => 'Oischdellunge',
'prefs-misc' => 'Schunschdisches',
'saveprefs' => 'Oischdellunge schbaischere',
'resetprefs' => 'Oischdellunge vawerfe',
'prefs-editing' => 'Schaffe',
'guesstimezone' => 'Aus em Browser iwwernemme',
'youremail' => 'E-Mail:',
'yourrealname' => 'Birschalischa Nome:',
'yourlanguage' => 'Schbrooch:',
'yourgender' => 'Gschleschd:',
'gender-unknown' => 'Ghoim gkalde',
'gender-male' => 'Männlisch',
'gender-female' => 'Waiblisch',
'prefs-diffs' => 'Unaschied',

# Groups
'group' => 'Grubb:',
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
'right-upload' => 'Dadaije nufflade',

# Special:Log/newusers
'newuserlogpage' => 'Naiaameldungs-Logbuch',

# User rights log
'rightslog' => 'Benutzerrecht-Logbuch',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'die Said beawaide',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|Ännerung|Ännerunge}}',
'recentchanges' => 'Ledschdi Ännarunge',
'recentchanges-legend' => 'Optione fa die Aazaisch',
'recentchanges-feed-description' => 'Di letschte Ännerunge vun {{SITENAME}} in des Feed aigewwe.',
'recentchanges-label-newpage' => 'Domid magschd ä naiji Said',
'recentchanges-label-minor' => "S'ische glänni Beawaidung",
'recentchanges-label-bot' => 'Ännarunge duaschn Bod',
'recentchanges-label-unpatrolled' => 'Die Änarung isch noch ned iwwabriefd worre',
'rcnote' => "Aagezaicht {{PLURAL:$1|werd '''1''' Ännerung|werre die letschte '''$1''' Ännerunge}} {{PLURAL:$2|vum letschte Dach|in de letschte '''$2''' Dache}} (Stand: $4, $5)",
'rclistfrom' => 'Zaisch die ledschd Ännerunge ab $1',
'rcshowhideminor' => 'Klenne Ännarunge $1',
'rcshowhidebots' => 'Bots $1',
'rcshowhideliu' => 'Aagemeldte Benutzer $1',
'rcshowhideanons' => 'Nit aagemeldt Benutzer $1',
'rcshowhidepatr' => '$1 iwabriefde Ännarunge',
'rcshowhidemine' => 'Mai Beawaidunge $1',
'rclinks' => 'Zeich die letschte $1 Ännerunge in de letschte $2 Dache<br />$3',
'diff' => 'Unnaschied',
'hist' => 'Gschischd',
'hide' => 'vaschdeggle',
'show' => 'zaische',
'minoreditletter' => 'k',
'newpageletter' => 'N',
'boteditletter' => 'B',
'rc-enhanced-expand' => 'Änzlhaide zaische (dozu brauchds JavaScript)',
'rc-enhanced-hide' => 'Ogawe vaschdeggle',

# Recent changes linked
'recentchangeslinked' => 'Was on verlinggde Saide gännad worre isch',
'recentchangeslinked-feed' => 'Ännarunge on valinggde Saide',
'recentchangeslinked-toolbox' => 'Ännarunge on verlingde Saide',
'recentchangeslinked-title' => 'Ännarunge on Saide, wu „$1“ druff verlinggd',
'recentchangeslinked-summary' => "Die Lischd zaischd ledschde Ännarunge, vunna bschdimmde Said, wu do valinggd isch (odda zu Midglied vuna bschdimmde Kadegorije isch).
Saide uff [[Special:Watchlist|Dainer Beowachdungslischd]] sinn '''fedd'''.",
'recentchangeslinked-page' => 'Saide:',
'recentchangeslinked-to' => 'Zaisch Ännerunge uff Saide, wu do her verlinkt sinn',

# Upload
'upload' => 'Nufflade',
'uploadbtn' => 'Dadai nufflade',
'uploadlogpage' => 'Dateie-Logbuch',
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
'listfiles_size' => 'Greeß',
'listfiles_count' => 'Versione',

# File description page
'file-anchor-link' => 'Dadai',
'filehist' => 'Dadaigschischd',
'filehist-help' => 'Drigg uff e Zaidpunggd zum aazaische, wie s dord ausgseh hod.',
'filehist-deleteall' => 'alles lesche',
'filehist-deleteone' => 'lesche',
'filehist-revert' => 'zuriggsedze',
'filehist-current' => 'agduell',
'filehist-datetime' => 'Zaidpungd',
'filehist-thumb' => 'Gleenes Bild',
'filehist-thumbtext' => 'Vorschaubild fer Version vum $1',
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

# Random page
'randompage' => 'Irschnd en Adiggel',

# Statistics
'statistics' => 'Schdadischdigge',

'disambiguationspage' => 'Template:Vadaidlischung',

'brokenredirects-edit' => 'beawaide',
'brokenredirects-delete' => 'lesche',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|Byte|Bytes}}',
'nmembers' => '$1 {{PLURAL:$1|Mitglied|Mitglieder}}',
'prefixindex' => 'Alle Saide (mid Präfix)',
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
'emailsend' => 'Abschigge',

# Watchlist
'watchlist' => 'Beowachdungslischd',
'mywatchlist' => 'Beowachdungslischd',
'watchlistfor2' => 'Vun $1 $2',
'addedwatchtext' => "Die Said \"[[:\$1]]\" isch zu Dainer [[Special:Watchlist|Beowachdungslischt]] zugefiecht worre.
Zukimftiche Ännerunge an derre Said un de Dischbediersaid, wu dezu ghert, werre doo aagezaicht, un d Said werd '''fett''' aagezaicht in de [[Special:RecentChanges|Letschte Ännerunge]] fer dass es ääfacher isch zum finne.",
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
'protectedarticle' => 'hot "[[$1]]" gschizd',
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
'namespace' => 'Nomensraum',
'invert' => 'Wahl dausche',
'blanknamespace' => '(Haabdsaid)',

# Contributions
'contributions' => '{{GENDER:$1|Wasa gemachd hod}}',
'contributions-title' => 'Benutzerbaidräch vun $1',
'mycontris' => 'Baidräsch',
'contribsub2' => 'Fer $1 ($2)',
'uctop' => '(akduell)',
'month' => 'än Monad (un frija):',
'year' => 'Abm Johr (un frieja):',

'sp-contributions-newbies' => 'Zaich numme Baidräch vun naie Benutzerkonte',
'sp-contributions-blocklog' => 'Schberrlogbuch',
'sp-contributions-uploads' => 'Nufflade',
'sp-contributions-logs' => 'Logbischa',
'sp-contributions-talk' => 'Dischbediere',
'sp-contributions-search' => 'No Baidräch suche',
'sp-contributions-username' => 'IP-Adress orrer Benutzername:',
'sp-contributions-toponly' => 'Bloß agduelli Ännarunge zaische',
'sp-contributions-submit' => 'Suche',

# What links here
'whatlinkshere' => 'Was doher zaische dud',
'whatlinkshere-title' => 'Saide wu uff "$1" verlinke',
'whatlinkshere-page' => 'Said:',
'linkshere' => "Die Saide verlinke zu '''[[:$1]]''':",
'nolinkshere' => "Kä Said zaischd uff '''[[:$1]]'''.",
'isredirect' => 'Wairerlaitungsaid',
'istemplate' => 'Vorlacheoibindung',
'isimage' => 'Dadailingg',
'whatlinkshere-prev' => '{{PLURAL:$1|vorich|voriche $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|negscht|negschte $1}}',
'whatlinkshere-links' => '← Linggs',
'whatlinkshere-hideredirs' => '$1 Waidalaidunge',
'whatlinkshere-hidetrans' => '$1 Vorlacheaibindunge',
'whatlinkshere-hidelinks' => '$1 Linggs',
'whatlinkshere-filters' => 'Filda',

# Block/unblock
'blockip' => 'Benudzer bloggiere',
'ipbsubmit' => 'Benudzer bloggiere',
'ipboptions' => '2 Stunne:2 hours,1 Dach:1 day,3 Dache:3 days,1 Woch:1 week,2 Woche:2 weeks,1 Monet:1 month,3 Monet:3 months,6 Monet:6 months,1 Johr:1 year,Fer immer:infinite',
'ipusubmit' => 'Die Adreß fraigewwe',
'ipblocklist' => 'Gschberrdi IP-Adress un Benudzernome',
'blocklink' => 'schberre',
'unblocklink' => 'Sperr uffhewe',
'change-blocklink' => 'Schberr ännere',
'contribslink' => 'Baidräch',
'blocklogpage' => 'Schberrlogbuch',
'blocklogentry' => 'hot [[$1]] gsperrt fer e Zaidraum vun $2 $3',
'unblocklogentry' => 'hot d Sperr vun $1 uffghowwe',
'block-log-flags-nocreate' => 'Aaleche vun Benutzerkonte isch gsperrt',

# Developer tools
'lockbtn' => 'Dadebongg schberre',
'unlockbtn' => 'Dadebongg fraigewwe',

# Move page
'move-page-legend' => 'Said vaschiewe',
'movepagetext' => "Mid dem Format kannscht ener Said e naie Name gewwe, debai werre alle alde Versione uff de nai Name verschowe.
Aus em Alde Name werd e Wairerlaidungssaid´zum naie Name.
Wairerlaidungssaide, wu uff de ald Name umlaire, kannscht automatisch aktualisiere.
Wenn De des nid willsch, no guck uff [[Special:DoubleRedirects|doppelte]] orrer [[Special:BrokenRedirects|kaputte Wairerlaidunge]].
Du solltescht defer sorche, dass Links wairer zu de richdiche Saide fiehre.

Gebb Acht, dass die Said '''nid''' verschowe werd, wenn s scho e Said mid em naie Name hot, außer wenn se leer isch orrer e Wairerlaidung.
Des hääßt, Du kannscht ke Said, wu s schun gebbt, iwwerschraiwe.

'''WARNUNG!'''
Des isch e wichdiche Ännerung fer e Said un kann ziehmlich unerwartet sai fer wichdiche Saide;
bitte mach des numme, wenn Du die Folche vun derre Aktion kannsch abschätze.",
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
'allmessagesname' => 'Nome',
'allmessagesdefault' => 'Vorgewene Tegschd',

# Thumbnails
'thumbnail-more' => 'Greßer mache',

# Special:Import
'import-interwiki-submit' => 'Impordiere',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Dai Benudzersaid',
'tooltip-pt-mytalk' => 'Dai Said fas Dischbediere',
'tooltip-pt-preferences' => 'Dai Aistellunge',
'tooltip-pt-watchlist' => 'D Lischd vun Saide, wu du beowachde duschd',
'tooltip-pt-mycontris' => 'Lischd vun Daine Baidräsch',
'tooltip-pt-login' => 'Du konnschd disch aamelde, awwer du mugschd s nit',
'tooltip-pt-logout' => 'Abmelde',
'tooltip-ca-talk' => 'Iwwa d Inhaldssaid dischbediere',
'tooltip-ca-edit' => 'Du kannschd die Said bearwaide.
Bidde nemmde Vorschau-Knobb vorm Schbaischere',
'tooltip-ca-addsection' => 'E naie Abschnitt aaleche',
'tooltip-ca-viewsource' => 'Die Said isch gschizd.
Du konnschd awwer de Gwelltegschd aagugge',
'tooltip-ca-history' => 'Ledschde Versione vun derre Said',
'tooltip-ca-protect' => 'Die Said schidze',
'tooltip-ca-delete' => 'Die Said lesche',
'tooltip-ca-move' => 'Die Said vaschiewe',
'tooltip-ca-watch' => 'Die Said zu Dainer Beowachdungslischd zufiesche',
'tooltip-ca-unwatch' => 'Die Said aus Dainer Beowachdunschlischde rausnemme',
'tooltip-search' => 'Durschsuch {{SITENAME}}',
'tooltip-search-go' => 'Geh zu ere Said mid genää dem Namme, wenn s se gebbt',
'tooltip-search-fulltext' => 'Gugg in de Said nochm Tegschd',
'tooltip-p-logo' => 'Haubdsaid',
'tooltip-n-mainpage' => 'Uff d Schdadsaid geje',
'tooltip-n-mainpage-description' => 'Haubdsaid aagucke',
'tooltip-n-portal' => 'Iwwers Brojegd, wude duu kannschd, wu ebbes finne duschd',
'tooltip-n-currentevents' => 'Finn Auskinfd iwwa naiji Voafell',
'tooltip-n-recentchanges' => 'Lischd vun de ledschde Ännarunge in dem Wiki',
'tooltip-n-randompage' => 'E zufällisch Said lade',
'tooltip-n-help' => 'Do konschds rausfinne',
'tooltip-t-whatlinkshere' => 'Lischd vun alle Wikisaide, wu do hie verlingd sinn',
'tooltip-t-recentchangeslinked' => 'Ledschde Ännerunge in Saide, wu vun do verlinggd sin',
'tooltip-feed-rss' => 'RSS feed fer die Said',
'tooltip-feed-atom' => 'Atom feed fer die Said',
'tooltip-t-contributions' => 'Die ledschde Baidräch vun däm Benudzer aagucke',
'tooltip-t-emailuser' => 'Dem Benutzer e E-Mail schicke',
'tooltip-t-upload' => 'Dadaije nufflade',
'tooltip-t-specialpages' => 'Lischd vun alle Schbezialsaide',
'tooltip-t-print' => 'Druggversion vun derre Said',
'tooltip-t-permalink' => 'E dauerhafte Link zu derre Version vun de Said',
'tooltip-ca-nstab-main' => 'D Inhaldssaid aagucke',
'tooltip-ca-nstab-user' => 'D Benudzersaid aagucke',
'tooltip-ca-nstab-special' => 'Des isch e Spezialsaid, du kannscht d Said sälwerscht nit ännere',
'tooltip-ca-nstab-project' => 'D Projektsaid aagucke',
'tooltip-ca-nstab-image' => 'D Dadaisaid aaugugge',
'tooltip-ca-nstab-template' => 'Vorlach aagugge',
'tooltip-ca-nstab-category' => 'D Kadegoriesaid aagucke',
'tooltip-minoredit' => 'Des als klenne Ännerung markiere',
'tooltip-save' => 'Dai Ännerunge schbaischere',
'tooltip-preview' => 'Guck Daine Ännerunge in de Vorschau aa, vor Du uff Spaichere driksch!',
'tooltip-diff' => 'Guck, welle Ännerunge Du im Text gmacht hoscht',
'tooltip-compareselectedversions' => 'D Unnaschied zwische denne zwee gwehlde Versione aagugge',
'tooltip-watch' => 'Die Said zu Dainer Beowachdunglischd zufieche',
'tooltip-rollback' => "„Zeriggsetze“ machd alle Bearwaidunge vum ledschde Bearwaider rigg'gängisch",
'tooltip-undo' => "„Zerigg“ machd numme die Ännerung rigg'gängich un zaichd d Vorschau aa.
Du kannschd e Grund in dr Zammfassung aagewwe",
'tooltip-summary' => 'Gebä koaz Resimee',

# Browsing diffs
'previousdiff' => '← Ältere Bearwaidung',
'nextdiff' => 'Naiere Bearwaidung →',

# Media information
'file-info-size' => '$1 × $2 Pixels, Dateigreß: $3, MIME-Type: $4',
'file-nohires' => 'Ke hechere Ufflesung verfiechbar.',
'svg-long-desc' => 'SVG-Datei, Basisgreß $1 × $2 Pixels, Dateigreß: $3',
'show-big-image' => 'Volli Ufflesung',

# Special:NewFiles
'showhidebots' => '(Bots $1)',
'ilsubmit' => 'Such',

# Bad image list
'bad_image_list' => 'Formad:

nur Zaile, wu mid eme * aafange werre bericksichdischd.
De erschd Link muss e Link zu ere unerwinschd Dadei sai.
Annere Links in der glaiche Zail werre als Ausnahme behanneld, d. h. Saide, wu d Dadei drin vorkumme därfd.',

# Metadata
'metadata' => 'Metadata',
'metadata-help' => 'Die Dadei hot waidere Informatione, wahrschainlich vun de Digidalkamera oder vum Scanner, mid dem wu sie gmacht worre sinn.
Wenn die Dadei verännerd worre isch, dann kann s sai, dass die zusädzlich Information fer die verännert Dadei nimmi richdisch isch.',
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

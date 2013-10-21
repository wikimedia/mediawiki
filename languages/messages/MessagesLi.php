<?php
/** Limburgish (Limburgs)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Aelske
 * @author Benopat
 * @author Cicero
 * @author Geitost
 * @author Kaganer
 * @author Matthias
 * @author Ooswesthoesbes
 * @author Pahles
 * @author Purodha
 * @author Reedy
 * @author Remember the dot
 * @author Tibor
 * @author לערי ריינהארט
 */

$fallback = 'nl';


$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Speciaal',
	NS_TALK             => 'Euverlèk',
	NS_USER             => 'Gebroeker',
	NS_USER_TALK        => 'Euverlèk_gebroeker',
	NS_PROJECT_TALK     => 'Euverlèk_$1',
	NS_FILE             => 'Plaetje',
	NS_FILE_TALK        => 'Euverlèk_plaetje',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Euverlèk_MediaWiki',
	NS_TEMPLATE         => 'Sjabloon',
	NS_TEMPLATE_TALK    => 'Euverlèk_sjabloon',
	NS_HELP             => 'Help',
	NS_HELP_TALK        => 'Euverlèk_help',
	NS_CATEGORY         => 'Categorie',
	NS_CATEGORY_TALK    => 'Euverlèk_categorie',
);

$namespaceAliases = array(
	'Kategorie'           => NS_CATEGORY,
	'Euverlèk_kategorie'  => NS_CATEGORY_TALK,
	'Aafbeilding'         => NS_FILE,
	'Euverlèk_afbeelding' => NS_FILE_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'Aktieve_gebroekers' ),
	'Allmessages'               => array( 'Alle_berichte' ),
	'Allpages'                  => array( 'Alle_pagina\'s' ),
	'Ancientpages'              => array( 'Audste_pagina\'s' ),
	'Blankpage'                 => array( 'Laeg_pagina\'s' ),
	'Block'                     => array( 'Blokkere' ),
	'Blockme'                   => array( 'Blokkeer_mich' ),
	'Booksources'               => array( 'Bookwinkele' ),
	'BrokenRedirects'           => array( 'Gebraoke_doorverwiezinge' ),
	'Categories'                => array( 'Categorieë' ),
	'ChangePassword'            => array( 'Wachwaord_opnuuj_insjtèlle' ),
	'Confirmemail'              => array( 'E-mail_bevestige' ),
	'Contributions'             => array( 'Biedrage' ),
	'CreateAccount'             => array( 'Gebroeker_aonmake' ),
	'Deadendpages'              => array( 'Doedloupende_pagina\'s' ),
	'DeletedContributions'      => array( 'Eweggesjafde_biedrage' ),
	'Disambiguations'           => array( 'Verdudelikingspagina\'s' ),
	'DoubleRedirects'           => array( 'Dobbel_doorverwiezinge' ),
	'Emailuser'                 => array( 'E-maile' ),
	'Export'                    => array( 'Exportere' ),
	'Fewestrevisions'           => array( 'Winnigs_bewirkde_pagina\'s' ),
	'FileDuplicateSearch'       => array( 'Besjtandsduplicate_zeuke' ),
	'Filepath'                  => array( 'Besjtandspaad' ),
	'Import'                    => array( 'Importere' ),
	'Invalidateemail'           => array( 'E-mail_annulere' ),
	'BlockList'                 => array( 'Geblokkeerde_IP\'s' ),
	'LinkSearch'                => array( 'Verwiezinge_zeuke' ),
	'Listadmins'                => array( 'Systeemwèrkers' ),
	'Listbots'                  => array( 'Botlies' ),
	'Listfiles'                 => array( 'Plaetjes' ),
	'Listgrouprights'           => array( 'Groepsrechte_weergaeve' ),
	'Listredirects'             => array( 'Doorverwiezinge' ),
	'Listusers'                 => array( 'Gebroekers' ),
	'Lockdb'                    => array( 'DB_blokkere' ),
	'Log'                       => array( 'Logbeuk', 'Logbook' ),
	'Lonelypages'               => array( 'Weispagina\'s' ),
	'Longpages'                 => array( 'Lang_pagina\'s' ),
	'MergeHistory'              => array( 'Historie_sameveuge' ),
	'MIMEsearch'                => array( 'MIME_zeuke' ),
	'Mostcategories'            => array( 'Meiste_categorieë' ),
	'Mostimages'                => array( 'Meis_gebroekde_plaetjes' ),
	'Mostlinked'                => array( 'Meis_gelinkde_pazjena\'s' ),
	'Mostlinkedcategories'      => array( 'Meis_gelinkde_categorieë' ),
	'Mostlinkedtemplates'       => array( 'Meis_gebroekde_sjablone' ),
	'Mostrevisions'             => array( 'Meis_bewirkde_pagina\'s' ),
	'Movepage'                  => array( 'Verplaatse' ),
	'Mycontributions'           => array( 'Mien_biedrage' ),
	'Mypage'                    => array( 'Mien_pagina\'s' ),
	'Mytalk'                    => array( 'Mien_euverlèk' ),
	'Newimages'                 => array( 'Nuuj_plaetjes' ),
	'Newpages'                  => array( 'Nuuj_pagina\'s' ),
	'Popularpages'              => array( 'Populair_pagina\'s' ),
	'Preferences'               => array( 'Veurkäöre' ),
	'Prefixindex'               => array( 'Alle_artikele' ),
	'Protectedpages'            => array( 'Beveiligde_pagina\'s' ),
	'Protectedtitles'           => array( 'Beveiligde_titels' ),
	'Randompage'                => array( 'Willekäörig_artikel' ),
	'Randomredirect'            => array( 'Willekäörige_doorverwiezing' ),
	'Recentchanges'             => array( 'Lètste_verangeringe' ),
	'Recentchangeslinked'       => array( 'Verwante_verangeringe' ),
	'Revisiondelete'            => array( 'Versie_ewegsjaffe' ),
	'Search'                    => array( 'Zeuke' ),
	'Shortpages'                => array( 'Kórte_pagina\'s' ),
	'Specialpages'              => array( 'Speciaal_pagina\'s' ),
	'Statistics'                => array( 'Sjtattestieke' ),
	'Tags'                      => array( 'Labels' ),
	'Uncategorizedcategories'   => array( 'Óngecategoriseerde_categorieë' ),
	'Uncategorizedimages'       => array( 'Óngecategoriseerde_plaetjes' ),
	'Uncategorizedpages'        => array( 'Óngecategoriseerde_pagina\'s' ),
	'Uncategorizedtemplates'    => array( 'Óngecategorisserde_sjablone' ),
	'Undelete'                  => array( 'Hersjtèlle' ),
	'Unlockdb'                  => array( 'DB_vriegaeve' ),
	'Unusedcategories'          => array( 'Óngebroekde_categorieë' ),
	'Unusedimages'              => array( 'Óngebroekde_plaetjes' ),
	'Unusedtemplates'           => array( 'Óngebroekde_sjablone' ),
	'Unwatchedpages'            => array( 'Neet-gevolgde_pagina\'s' ),
	'Upload'                    => array( 'Uploade' ),
	'Userlogin'                 => array( 'Aanmelje' ),
	'Userlogout'                => array( 'Aafmelje' ),
	'Userrights'                => array( 'Gebroekersrechte' ),
	'Version'                   => array( 'Versie' ),
	'Wantedcategories'          => array( 'Gewunsjde_categorieë' ),
	'Wantedfiles'               => array( 'Gevraogde_besjtande' ),
	'Wantedpages'               => array( 'Gewunsjde_pagina\'s' ),
	'Wantedtemplates'           => array( 'Gevraogde_sjablone' ),
	'Watchlist'                 => array( 'Volglies' ),
	'Whatlinkshere'             => array( 'Verwiezinge_nao_hie' ),
	'Withoutinterwiki'          => array( 'Gein_interwiki' ),
);

$dateFormats = array(
	'mdy time' => 'H:i',
	'mdy date' => 'M j, Y',
	'mdy both' => 'M j, Y H:i',

	'dmy time' => 'H:i',
	'dmy date' => 'j M Y',
	'dmy both' => 'j M Y H:i',

	'ymd time' => 'H:i',
	'ymd date' => 'Y M j',
	'ymd both' => 'Y M j H:i',
);

$messages = array(
# User preference toggles
'tog-underline' => 'Links óngersjtriepe',
'tog-justify' => 'Paragrafe oetvölle',
'tog-hideminor' => 'Versjtaek klein bewirkinge bie recènte verangeringe',
'tog-hidepatrolled' => 'Gemarkeerde wieziginge verberge in recente wieziginge',
'tog-newpageshidepatrolled' => "Gemarkeerde pagina's verberge in de lies mit nuuj pagina's",
'tog-extendwatchlist' => 'Oetgebreide volglies gebroeke óm alle verangeringe te zeen en neet allein de lèste',
'tog-usenewrc' => 'Tuun verangeringe per pagina in recènte verangeringe en volglies (Javascript nudig)',
'tog-numberheadings' => 'Köpkes automatisch nummere',
'tog-showtoolbar' => 'Laot edit toolbar zeen',
'tog-editondblclick' => "Bewirk pagina's bie 'ne dobbelklik (JavaScript)",
'tog-editsection' => 'Bewirke van secties via [bewirke] links',
'tog-editsectiononrightclick' => "Secties bewirke mit 'ne rechtermoesklik op sectietitels (JavaScript nudig)",
'tog-showtoc' => "Inhaudsopgaaf veur pagina's mit mie es 3 köpkes",
'tog-rememberpassword' => 'Mien wachwaord onthouwe veur later sessies (hoegstens $1 {{PLURAL:$1|daag|daag}})',
'tog-watchcreations' => "Volg autematis pagina's die ich aanmaak en bestenj die ich upload",
'tog-watchdefault' => "Voog pagina's em bestenj die se bewirks toe aan dien volglies",
'tog-watchmoves' => "Volg autematis pagina's en bestenj die ich verplaats",
'tog-watchdeletion' => "Volg autematis pagina's en bestenj die ich ewegsjaf",
'tog-minordefault' => 'Markeer sjtanderd alle bewirkinge es klein',
'tog-previewontop' => 'Veurvertuin baove bewèrkingsveld tuine',
'tog-previewonfirst' => 'Preview laote zien bie de ierste bewirking',
'tog-nocache' => 'Zèt de browserpaginacaching oet',
'tog-enotifwatchlistpages' => "Versjik 'ne e-mail nao mich bie bewirkinge van pagina's en bestenj op mien volglies",
'tog-enotifusertalkpages' => "'ne E-mail nao mich versjikke es emes mien euverlèkpagina verangert",
'tog-enotifminoredits' => "Versjik  mich 'ne e-mail bie klein bewirkinge op pagina's en bestenj op mien volglies",
'tog-enotifrevealaddr' => 'Mien e-mailadres tuine in e-mailberichte',
'tog-shownumberswatching' => "'t Aantal gebroekers tuine die dees pagina volg",
'tog-oldsig' => 'Bestaonde ongerteikening:',
'tog-fancysig' => 'Es wikiteks behanjele (zonder autematische verwiezing)',
'tog-uselivepreview' => '"live veurbesjouwing" gebroeke (vereis JavaScript - experimenteel)',
'tog-forceeditsummary' => "'n Melding gaeve bie 'n laeg samevatting",
'tog-watchlisthideown' => 'Eige bewirkinge verberge op mien volglies',
'tog-watchlisthidebots' => 'Botbewirkinge op mien volglies verberge',
'tog-watchlisthideminor' => 'Klein bewirkinge op mien volglies verberge',
'tog-watchlisthideliu' => 'Bewirkinge van aangemelde gebroekers op mien volglies versjtaeke',
'tog-watchlisthideanons' => 'Bewirkinge van anonieme gebroekers op mien volglies versjtaeke',
'tog-watchlisthidepatrolled' => 'Gemarkeerde wieziginge op mien volglies verberge',
'tog-ccmeonemails' => "'n Kopie nao mich versjikke van de e-mail dae ich nao anger gebroekers sjik",
'tog-diffonly' => 'Pagina-inhaud zónger verangeringe neet tuine',
'tog-showhiddencats' => 'Verbórge categorië tuine',
'tog-norollbackdiff' => 'Wieziginge eweglaote nao trökdrieje',
'tog-useeditwarning' => "Waorssjoew mich es ich 'n bewerkdje pagina die nag neet is opgeslage wil verlaote",

'underline-always' => 'Altied',
'underline-never' => 'Noets',
'underline-default' => 'Sjtanderd van de browser',

# Font style option in Special:Preferences
'editfont-style' => 'Lettertypestielbewèrkingsvènster:',
'editfont-default' => 'Standerd browser',
'editfont-monospace' => 'Monospacefont',
'editfont-sansserif' => 'Sans-seriffont',
'editfont-serif' => 'Seriffont',

# Dates
'sunday' => 'zóndig',
'monday' => 'maondig',
'tuesday' => 'dinsdig',
'wednesday' => 'goonsdig',
'thursday' => 'donderdig',
'friday' => 'vriedig',
'saturday' => 'zaoterdig',
'sun' => 'zón',
'mon' => 'mao',
'tue' => 'din',
'wed' => 'woo',
'thu' => 'dón',
'fri' => 'vri',
'sat' => 'zao',
'january' => 'jannewarie',
'february' => 'fibberwarie',
'march' => 'miert',
'april' => 'april',
'may_long' => 'mei',
'june' => 'juni',
'july' => 'juli',
'august' => 'augustus',
'september' => 'september',
'october' => 'oktober',
'november' => 'november',
'december' => 'december',
'january-gen' => 'jannewarie',
'february-gen' => 'fibberwarie',
'march-gen' => 'miert',
'april-gen' => 'april',
'may-gen' => 'mei',
'june-gen' => 'juni',
'july-gen' => 'juli',
'august-gen' => 'augustus',
'september-gen' => 'september',
'october-gen' => 'oktober',
'november-gen' => 'november',
'december-gen' => 'december',
'jan' => 'jan',
'feb' => 'fib',
'mar' => 'mie',
'apr' => 'apr',
'may' => 'mei',
'jun' => 'jun',
'jul' => 'jul',
'aug' => 'aug',
'sep' => 'sep',
'oct' => 'okt',
'nov' => 'nov',
'dec' => 'dec',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Categorie|Categorieë}}',
'category_header' => 'Artikele in categorie "$1"',
'subcategories' => 'Subcategorieë',
'category-media-header' => 'Media in de categorie "$1"',
'category-empty' => "''Dees categorie bevat op 't memènt gein artikele of media.''",
'hidden-categories' => 'Verbórge {{PLURAL:$1|categorie|categorië}}',
'hidden-category-category' => 'Verbórge categorië',
'category-subcat-count' => "{{PLURAL:$2|Dees categorie haet de volgende óngercategorie.|Dees categorie haet de volgende {{PLURAL:$1|óngercategorie|$1 óngercategorië}}, van 'n totaal van $2.}}",
'category-subcat-count-limited' => 'Dees categorie haet de volgende {{PLURAL:$1|óngercategorie|$1 óngercategorië}}.',
'category-article-count' => "{{PLURAL:$2|Dees categorie bevat de volgende pagina.|Dees categorie bevat de volgende {{PLURAL:$1|pagina|$1 pagina's}}, van in totaal $2.}}",
'category-article-count-limited' => "Dees categorie bevat de volgende {{PLURAL:$1|pagina|$1 pagina's}}.",
'category-file-count' => "{{PLURAL:$2|Dees categorie bevat 't volgende bestandj.|Dees categorie bevat {{PLURAL:$1|'t volgende bestandj|de volgende $1 bestenj}}, van in totaal $2.}}",
'category-file-count-limited' => "Dees categorie bevat {{PLURAL:$1|'t volgende bestandj|de volgende $1 bestenj}}.",
'listingcontinuesabbrev' => 'wiejer',
'index-category' => 'Geïndexeerde paazjes',
'noindex-category' => 'Óngeïndexeerde paazjes',
'broken-file-category' => "Pazjena's mit ónjuuste bestandjsverwiezinge",

'about' => 'Informatie',
'article' => 'Pagina',
'newwindow' => '(in nuuj venster)',
'cancel' => 'Aafbraeke',
'moredotdotdot' => 'Miè...',
'mypage' => 'Mien gebroekerspagina',
'mytalk' => 'Mien euverlèkpagina',
'anontalk' => 'Euverlèk veur dit IP adres',
'navigation' => 'Navigatie',
'and' => '&#32;en',

# Cologne Blue skin
'qbfind' => 'Zeuke',
'qbbrowse' => 'Bladere',
'qbedit' => 'Bewirke',
'qbpageoptions' => 'Pagina-opties',
'qbmyoptions' => 'mien opties',
'qbspecialpages' => "Speciaal pagina's",
'faq' => 'FAQ (väölgesjtèlde vraoge)',
'faqpage' => 'Project:Väölgestjèlde vraoge',

# Vector skin
'vector-action-addsection' => 'Voog köpke toe',
'vector-action-delete' => 'Ewegsjaffe',
'vector-action-move' => 'Verplaats',
'vector-action-protect' => 'Besjirm',
'vector-action-undelete' => 'Plaats trök',
'vector-action-unprotect' => 'Anger beveiliging',
'vector-simplesearch-preference' => "Sjakel nuuj zeuksuggesties in (allein veur 't vectoroeterlik)",
'vector-view-create' => 'Maak aan',
'vector-view-edit' => 'Bewirk',
'vector-view-history' => 'Bekiek de gesjiedenis',
'vector-view-view' => 'Laes',
'vector-view-viewsource' => 'Bekiek bróntèks',
'actions' => 'Hanjeling',
'namespaces' => 'Naamruumdes',
'variants' => 'Anger vorme',

'errorpagetitle' => 'Fout',
'returnto' => 'Truuk nao $1.',
'tagline' => 'Van {{SITENAME}}',
'help' => 'Hölp',
'search' => 'Zeuke',
'searchbutton' => 'Zeuk',
'go' => 'OK',
'searcharticle' => 'Gank',
'history' => 'Historie',
'history_short' => 'Historie',
'updatedmarker' => 'bewirk sins mien lètste bezeuk',
'printableversion' => 'Printervruntelike versie',
'permalink' => 'Permanente link',
'print' => 'Aafdrukke',
'view' => 'Bekieke',
'edit' => 'Bewèrk',
'create' => 'Aanmake',
'editthispage' => 'Pagina bewirke',
'create-this-page' => 'Dees pagina aanmake',
'delete' => 'Wisse',
'deletethispage' => 'Wisse',
'undelete_short' => '$1 {{PLURAL:$1|bewirking|bewirkinge}} trökzètte',
'viewdeleted_short' => '{{PLURAL:$1|ein eweggesjafde versie|$1 eweggesjafde versies}} bekieke',
'protect' => 'Besjirm',
'protect_change' => 'beveiligingssjtatus verangere',
'protectthispage' => 'Beveilige',
'unprotect' => 'Gaef anges aan',
'unprotectthispage' => 'Veranger de beveiliging van dees pagina',
'newpage' => 'Nuuj pagina',
'talkpage' => 'euverlèkpagina',
'talkpagelinktext' => 'Euverlègk',
'specialpage' => 'Speciaal pagina',
'personaltools' => 'Persuunlike hulpmiddele',
'postcomment' => 'Nuuj sectie',
'articlepage' => 'Artikel',
'talk' => 'Euverlègk',
'views' => 'Weergave',
'toolbox' => 'Gereidsjapskis',
'userpage' => 'gebroekerspagina',
'projectpage' => 'Projekpagina tuine',
'imagepage' => 'Besjtandjspagina tuine',
'mediawikipage' => 'Berichpagina tuine',
'templatepage' => 'Sjabloonpagina tuine',
'viewhelppage' => 'Hulppagina tuine',
'categorypage' => 'Categoriepagina tuine',
'viewtalkpage' => 'Euverlèk tuine',
'otherlanguages' => 'Anger tale',
'redirectedfrom' => '(Doorverweze van $1)',
'redirectpagesub' => 'Doorverwiespagina',
'lastmodifiedat' => "Dees pagina is 't lèts verangerd op $2, $1.",
'viewcount' => 'Dees pagina is {{PLURAL:$1|1 kier|$1 kier}} bekeke.',
'protectedpage' => 'Beveiligde pagina',
'jumpto' => 'Gank nao:',
'jumptonavigation' => 'navigatie',
'jumptosearch' => 'zeuke',
'view-pool-error' => 'Perdóng, de servers zeen noe euverbelas.
Te väöl gebroekers perberen óm dees pagina te bekieke.
Wach estebleef effe veurdets doe opnuuj toegank perbeers te kriege tót dees pagina.

$1',
'pool-timeout' => "Timeout-wachte veur 't sloete",
'pool-queuefull' => 'De wachrie van de pool is vól',
'pool-errorunknown' => 'Ónbekènde fout',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage).
'aboutsite' => 'Euver {{SITENAME}}',
'aboutpage' => 'Project:Info',
'copyright' => 'De inhawd is besjikbaar ónger de $1.',
'copyrightpage' => '{{ns:project}}:Auteursrechte',
'currentevents' => "In 't nuujs",
'currentevents-url' => "Project:In 't nuujs",
'disclaimers' => 'Aafwiezinge aansjprakelikheid',
'disclaimerpage' => 'Project:Algemein aafwiezing aansjprakelikheid',
'edithelp' => 'Hulp bie bewirke',
'helppage' => 'Help:Help',
'mainpage' => 'Veurblaad',
'mainpage-description' => 'Veurblaad',
'policy-url' => 'Project:Beleid',
'portal' => 'Gebroekersportaol',
'portal-url' => 'Project:Gebroekersportaol',
'privacy' => 'Privacybeleid',
'privacypage' => 'Project:Privacybeleid',

'badaccess' => 'Toeganksfout',
'badaccess-group0' => 'Doe höbs gein rechte óm de gevraogde hanjeling oet te veure.',
'badaccess-groups' => 'De gevraogde hanjeling is veurbehauwe aan gebroekers in {{PLURAL:$2|de gróp|ein van de gróppe}}: $1.',

'versionrequired' => 'Versie $1 van MediaWiki is vereis',
'versionrequiredtext' => 'Versie $1 van MediaWiki is vereis om dees pagina te gebroeke. Bekiek [[Special:Version|Softwareversie]]',

'ok' => 'ok',
'retrievedfrom' => 'Aafkómstig van "$1"',
'youhavenewmessages' => 'Doe höbs $1 ($2).',
'newmessageslink' => 'nuuj berichte',
'newmessagesdifflink' => 'Lèste verangering',
'youhavenewmessagesmulti' => 'Doe höbs nuuj berichte op $1',
'editsection' => 'bewèrk',
'editold' => 'bewirke',
'viewsourceold' => 'brónteks tuine',
'editlink' => 'bewirk',
'viewsourcelink' => 'brónteks tuine',
'editsectionhint' => 'Deilpagina bewirke: $1',
'toc' => 'Inhaud',
'showtoc' => 'tuine',
'hidetoc' => 'versjtaek',
'collapsible-collapse' => 'Inklappe',
'collapsible-expand' => 'Oetklappe',
'thisisdeleted' => '$1 tuine of trökzètte?',
'viewdeleted' => '$1 tuine?',
'restorelink' => '{{PLURAL:$1|ein eweggesjafde versie|$1 eweggesjafde versies}}',
'feedlinks' => 'Feed:',
'feed-invalid' => 'Feedtype waerd neet óngersjteund.',
'feed-unavailable' => 'Syndicatiefeeds zunt neet besjikbaar op {{SITENAME}}',
'site-rss-feed' => '$1 RSS-feed',
'site-atom-feed' => '$1 Atom-feed',
'page-rss-feed' => '“$1” RSS-feed',
'page-atom-feed' => '“$1” Atom-feed',
'red-link-title' => '$1 (pagina besteit neet)',
'sort-descending' => 'Sorteer aafloupendj',
'sort-ascending' => 'Sorteer óploupendj',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Pagina',
'nstab-user' => 'Gebroeker',
'nstab-media' => 'Mediapagina',
'nstab-special' => 'Speciaal pagina',
'nstab-project' => 'Projekpagina',
'nstab-image' => 'Besjtand',
'nstab-mediawiki' => 'Berich',
'nstab-template' => 'Sjabloon',
'nstab-help' => 'Hulppagina',
'nstab-category' => 'Categorie',

# Main script and global functions
'nosuchaction' => 'Gevraogde hanjeling besjteit neet',
'nosuchactiontext' => "De opdrach in de URL is ongeldig.
Mäögelik höbs te 'n typefout gemaak in de URL, of 'n verkierde verwiezing gevolg.
't Kan ouch wieze op 'n fout in de software van {{SITENAME}}.",
'nosuchspecialpage' => "D'r besjteit gein speciaal pagina mit deze naam",
'nospecialpagetext' => "<strong>Doe höbs 'n neet bestaonde speciaal pagina aongevraog.</strong>

'n Lies mit besjtaonde speciaal pagina's sjteit op [[Special:SpecialPages|speciaal pagina’s]].",

# General errors
'error' => 'Fout',
'databaseerror' => 'Databasefout',
'laggedslavemode' => 'Waarsjoewing: de pagina kin verauwerd zien.',
'readonly' => 'Database geblokkeerd',
'enterlockreason' => "Gaef 'n rae veur de blokkering en wie lank 't dinkelik zal dore. De ingegaeve rae zal aan de gebroekers getuind waere.",
'readonlytext' => 'De database van {{SITENAME}} is geblokkeerd veur bewirkinge, waorsjienelik veur besjtandsongerhaud. Nao aafloup waert de functionaliteit weer hersteld.

De verantwoordelike systeembeheerder gaof de volgende rae op:
<p>$1',
'missing-article' => "In de database is gein inhaud aangetróffe veur de pagina \"\$1\" die d'r wel zou mote zien (\$2).

Dit kan veurkómme as doe 'n auwer verwiezing nao 't versjil tösje twee versies van ein pagina volgs of 'n versie opvreugs die is eweggesjaf.

Es dit neet 't geval is, höbs doe mesjins 'n fout in de software gevónje.
Maak hievan melding bie 'ne [[Special:ListUsers/sysop|systeembeheerder]] van {{SITENAME}} en vermeld daobie de URL van dees pagina.",
'missingarticle-rev' => '(versienummer: $1)',
'missingarticle-diff' => '(Wijziging: $1, $2)',
'readonly_lag' => 'De database is autematis vergrendeld terwiel de ongergesjikte databaseservers synchronisere mit de huidserver.',
'internalerror' => 'Interne fout',
'internalerror_info' => 'Interne fout: $1',
'fileappenderrorread' => '"$1" kós neet gelaeze waere tiejes \'t toevoge.',
'fileappenderror' => 'Kós "$1" neet toevogen aan "$2".',
'filecopyerror' => 'Besjtand "$1" kós neet nao "$2" gekopieerd waere.',
'filerenameerror' => 'Verangere van de titel van \'t besjtand "$1" in "$2" neet mäögelik.',
'filedeleteerror' => 'Kós bestjand "$1" neet ewegsjaffe.',
'directorycreateerror' => 'Map "$1" kós neet aangemaak waere.',
'filenotfound' => 'Kós bestjand "$1" neet vènje.',
'fileexistserror' => 'Sjrieve nao bestandj "$1" waor neet mäögelik: \'t bestandj besjteit al',
'unexpected' => 'Ónverwachte waerd: "$1"="$2".',
'formerror' => 'Fout: kós formeleer neet versjikke',
'badarticleerror' => 'Dees hanjeling kan neet waere oetgeveurd op dees pagina.',
'cannotdelete' => 'Kós de pagina of \'t besjtand "$1" neet ewegsjaffe.
Mesjiens haet emes angers det al gedaon.',
'cannotdelete-title' => 'Pagina "$1" kin neet gewösj waere',
'delete-hook-aborted' => "'t Wösje is aafgebroke door 'ne 'hook'.
D'r is gein toelichting besjikbaar.",
'badtitle' => 'Óngeljige paginatitel',
'badtitletext' => 'De opgevraogde pagina is neet besjikbaar of laeg.',
'perfcached' => "De gegaeves koume oet 'n cache en zeen mäögelik neet actueel. 't Geuf {{PLURAL:$1|maximaal ei rizzeltaot|maximaal $1 rizzeltaote}} inne cache.",
'perfcachedts' => "De getuunde gegaeves komme oet 'n cache en zeen veur 't letst biejgewèrk op $1. Maximaal guuef 't {{PLURAL:$4|ei rizzeltaot|$4 rizzeltaote}} inne cache.",
'querypage-no-updates' => "Deze pagina kin op 't memènt neet biegewirk waere. Deze gegaeves waere neet verfrisj.",
'wrong_wfQuery_params' => 'Verkeerde paramaeters veur wfQuery()<br />
Funksie: $1<br />
Query: $2',
'viewsource' => 'Bekiek brónteks',
'viewsource-title' => 'Bekiek brón van $1',
'actionthrottled' => 'Hanjeling taengegehauwe',
'actionthrottledtext' => "Es maotregel taege spam is 't aantal keer per tiedseinheid dets te dees hanjeling kèns verrichte beperk. De höbs de limiet euversjreje. Perbeer 't euver 'n aantal minute obbenuuj.",
'protectedpagetext' => 'Dees pagina is beveilig. Bewirke is neet meugelik.',
'viewsourcetext' => 'De kèns de brónteks van dees pagina bekieke en kopiëre:',
'viewyourtext' => 'Doe kans "dien bewèrkinge" ane brónteks van dees pagina bekieke en euverkopiëre:',
'protectedinterface' => 'Deze pagina bevat teks veur berichte van de software en is beveilig om misbroek te veurkomme.',
'editinginterface' => "'''Waarsjoewing:''' Doe bewirks 'n pagina die gebroek waert door de software. Bewirkinge op dees pagina beïnvlode de gebroekersinterface van jederein. Euverwaeg veur euverzèttinge [//translatewiki.net/wiki/Main_Page?setlang=li translatewiki.net] te gebroeke, 't euverzèttingssprojek veur MediaWiki.",
'cascadeprotected' => "Deze pagina kin neet bewirk waere, omdet zie is opgenome in de volgende {{PLURAL:$1|pagina|pagina's}} die beveilig {{PLURAL:$1|is|zeen}} mèt de kaskaad-optie:
$2",
'namespaceprotected' => "Doe höbs gein rechte om pagina's in de naamruumde '''$1''' te bewirke.",
'customcssprotected' => "De kèns dees CSS-pagina neet bewirke ómdet die persuunlike insjtèllinge van 'ne angere gebroeker bevat.",
'customjsprotected' => "De kèns dees javapagina neet bewirke ómdet die persuunlike insjtèllinge van 'ne angere gebroeker bevat.",
'ns-specialprotected' => 'Pagina\'s in de naamruumde "{{ns:special}}" kinne neet bewirk waere.',
'titleprotected' => "'t aanmake van deze pagina is beveilig door [[User:$1|$1]].
De gegaeve ree is ''$2''.",
'filereadonlyerror' => '\'t Waar neet meugelik óm \'t bestandj "$1" aan te passe went de bestandjsrepositoir "$2" steit noe op allein-laeze.

d\'n Opgegaeve raej vanne sloetendje admin waar "\'\'$3\'\'".',
'invalidtitle-knownnamespace' => 'Óngèljige titel mit naamruumdje "$2" en teks "$3"',
'invalidtitle-unknownnamespace' => 'Óngèljige titel mit ónbekèndj naamruumdenómmer $1 en teks "$2"',
'exception-nologin' => 'Neet aangemèld',
'exception-nologin-text' => 'Óm dees pagina te betrachte of dees hanjeling te kinne doon mós se aangemèldj zeen bie deze wiki.',

# Virus scanner
'virus-badscanner' => "Slechte configuratie: onbekenge virusscanner: ''$1''",
'virus-scanfailed' => 'scanne is mislukt (code $1)',
'virus-unknownscanner' => 'onbekeng antivirus:',

# Login and logout pages
'logouttext' => "'''De bis noe aafgemeld.'''

De kèns {{SITENAME}} noe anoniem (mit vermeljing van IP-adres) gebroeke, of <span class='plainlinks'>[$1 opnuuj aanmelde]</span> ónger dezelfde of 'ne angere naam.
Mäögelik waert nog 'n deil pagina's getuind esofs te nog aangemeld bis pès te de cache van diene browser laeg maaks.",
'yourname' => 'Diene gebroekersnaam',
'yourpassword' => 'Die wachwaord',
'yourpasswordagain' => 'Wachwaord opnuuj intype',
'remembermypassword' => 'Mien wachwaord onthouwe veur later sessies (hoegstens $1 {{PLURAL:$1|daag|daag}})',
'yourdomainname' => 'Die domein',
'externaldberror' => "d'r Is 'n fout opgetraoje biej 't aanmelje biej de database of doe höbs gén toesjtömming diene externe gebroeker biej te wèrke.",
'login' => 'Aanmèlde',
'nav-login-createaccount' => 'Aanmelje / registrere',
'loginprompt' => "Diene browser mót ''cookies'' acceptere óm in te logge op {{SITENAME}}.",
'userlogin' => 'Aanmelde / registrere',
'userloginnocreate' => 'Mèlj aan',
'logout' => 'Aafmelde',
'userlogout' => 'Aafmelde',
'notloggedin' => 'Neet aangemeld',
'nologin' => 'Höbs te nog geine gebroekersnaam? $1.',
'nologinlink' => "Maak 'ne gebroekersnaam aan",
'createaccount' => 'Nuuj gebroekersprofiel aanmake.',
'gotaccount' => "Höbs te al 'ne gebroekersnaam? '''$1'''.",
'gotaccountlink' => 'Inlogge',
'userlogin-resetlink' => 'Bös se dien aanmèljingsgegaeves vergaete?',
'createaccountmail' => 'via de e-mail',
'createaccountreason' => 'Raeje:',
'badretype' => 'De ingeveurde wachwäörd versjille vanein.',
'userexists' => "De gebroekersnaam dae se höbs ingeveurd weurt al gebroek.

Kees estebleef 'ne angere naam.",
'loginerror' => 'Inlogfout',
'createaccounterror' => 'Kós gebroeker neet aanmake: $1',
'nocookiesnew' => "De gebroeker is aangemaak mèr neet aangemeld. {{SITENAME}} gebroek cookies veur 't aanmelje van gebroekers. Sjakel die a.u.b. in en meld dao nao aan mèt diene nuje gebroekersnaam en wachwaord.",
'nocookieslogin' => "{{SITENAME}} gebroek cookies veur 't aanmelje van gebroekers. Doe accepteers gén cookies. Sjakel deze optie a.u.b. in en perbeer 't oppernuuj.",
'nocookiesfornew' => "De gebroeker is neet aangemaak ómdet de bron neet bevestig kos waere.
Zörg deveur dats te cookies höbs ingesjakeld, herlaaj dees pagina en perbeer 't obbenuuts.",
'noname' => "De mos 'n gebroekersnaam opgaeve.",
'loginsuccesstitle' => 'Aanmèlde geluk.',
'loginsuccess' => 'Doe bis noe es "$1" aangemeld bie {{SITENAME}}.',
'nosuchuser' => 'D\'r besjteit geine gebroeker mit de naam "$1".
Die seen huidlettegevullig
Controleer dien spelling, of gebroek ongersjtaond formuleer om \'n [[Special:UserLogin/signup|nuuj]] gebroekersprofiel aan te make.',
'nosuchusershort' => 'De gebroeker "$1" besjteit neet. Konterleer de sjriefwieze.',
'nouserspecified' => "Doe deens 'ne gebroekersnaam op te gaeve.",
'login-userblocked' => 'Deze gebroeker steit geblokkeerd. Aanmèlje is neet toegestange.',
'wrongpassword' => "'t Ingegaeve wachwaord is neet zjus. Perbeer 't obbenuujts.",
'wrongpasswordempty' => "'t Ingegaeve wachwoord waor laeg. Perbeer 't obbenuujts.",
'passwordtooshort' => "Dien wachwaord is te kort. 't Mót minstes oet {{PLURAL:$1|1 teike|$1 teikes}} besjtaon.",
'password-name-match' => 'Die wachwaord mót anges zeen es diene gebroekersnaam.',
'password-login-forbidden' => "'t Gebroek van deze gebroekersnaam mit dit wachwoord is neet toegesjtange.",
'mailmypassword' => "Sjik mich 'n nuuj wachwaord",
'passwordremindertitle' => 'Nuuj tiedelik wachwaord van {{SITENAME}}',
'passwordremindertext' => 'Emes (waorsjienlik dich zelf) haet vanaaf IP-adres $1 \'n nuuj wachwoord veur {{SITENAME}} ($4) verzoch. \'t Nuuj wachwoord veur gebroeker "$2" is "$3". Es dat dien bedoeling waor, mèl diech daan noe aan en kees \'n nuuj wachwoord. \'t Tiedelik wachwoord verluip euver {{PLURAL:$5|$5 daag|$5 daag}}.

Es emes anders dit verzeuk heet gedoon, of wens te diech dien wachwoord weer herinners en \'t neet mie wèls wiezige, negeer dan dit berich en blief dien aud wachwoord gebroeke.',
'noemail' => 'D\'r is gein geregistreerd e-mailadres veur "$1".',
'noemailcreate' => 'Doe mós e geljig e-mailadres ópgaeve.',
'passwordsent' => 'D\'r is \'n nuui wachwaord verzonde nao \'t e-mailadres dat geregistreerd sjtit veur "$1".
Gelieve na ontvangst opnieuw aan te melden.',
'blocked-mailpassword' => "Dien IP-adres is geblokkeerd veur 't make van verangeringe. Om misbroek te veurkomme is 't neet meugelik om 'n nuuj wachwaord aan te vraoge.",
'eauthentsent' => "Dao is 'ne bevèstigingse-mail nao 't genomineerd e-mailadres gesjik.
Iedat anger mail nao dat account versjik kan weure, mós te de insjtructies in daen e-mail volge,
óm te bevèstige dat dit wirkelik dien account is.",
'throttled-mailpassword' => "'n Wachwaordherinnering wörd gedurende de letste {{PLURAL:$1|1 oer|$1 oer}} verzönje. Om misbroek te veurkomme, wörd d'r sjlechs éin herinnering per {{PLURAL:$1|oer|$1 oer}} verzönje.",
'mailerror' => "Fout bie 't versjture van mail: $1",
'acct_creation_throttle_hit' => "Bezeukers van deze wiki mit 'tzelfde IP-adres es doe höbbe de aafgeloupe daag {{PLURAL:$1|al 1 gebroeker|al $1 gebroekers}} geregistreerd, wat 't maximale aantal in deze periode is.
Daorum kens doe vanaaf dit IP-adres op dit moment gein nuje gebroeker registrere.",
'emailauthenticated' => 'Dien e-mailadres is op $2 um $3 bevestig.',
'emailnotauthenticated' => 'Dien e-mailadres is nog neet geauthentiseerd. De zals gein
e-mail óntvange veur alle volgende toepassinge.',
'noemailprefs' => "Gaef 'n e-mailadres op om deze functies te gebroeke.",
'emailconfirmlink' => 'Bevèstig dien e-mailadres',
'invalidemailaddress' => "'t E-mailadres is neet geaccepteerd omdet 't 'n ongeldige opmaak haet. Gaef a.u.b. 'n geldig e-mailadres op of laot 't veld laeg.",
'cannotchangeemail' => 'E-mailadresse kinne neet waere verangerdj óp deze wiki.',
'emaildisabled' => 'Dees site kin gein mails versjikke.',
'accountcreated' => 'Gebroeker aangemaak',
'accountcreatedtext' => 'De gebroeker $1 is aangemaak.',
'createaccount-title' => 'Gebroekers aanmake veur {{SITENAME}}',
'createaccount-text' => 'Emes genaamp "$2" haet \'ne gebroeker veur $2 aangemaak op {{SITENAME}}
($4) mit \'t wachwaord "$3". Meld dich aan en wiezig dien wachwaord.

Negeer dit berich as deze gebroeker zonger dien medewete is aangemaak.',
'usernamehasherror' => '\'ne Gebroekersnaam kèn geint hèkske ("#") bevatte.',
'login-throttled' => "Doe höbs te huifig geperbeerd aan te melje mèt 'n verkierd wachwaord.
Doe mós effe wachte ierdets te 't obbenuuts kens perbere.",
'login-abort-generic' => 'Doe bös neet aangemèldj - Aafgebraoke',
'loginlanguagelabel' => 'Taol: $1',
'suspicious-userlogout' => "Dien verzeuk óm aaf te melde is genegeerd, ómdet 't liek esof 't verzeuk is versjik door 'ne browser of cacheproxy dae kepot is.",

# Email sending
'php-mail-error-unknown' => "Dao haet ziech 'n ónbekénde fout veurgedaon in de mail()-functie van PHP",
'user-mail-no-addy' => "Perbeerdjes 'ne mail te sjikke zónger 'n adres",

# Change password dialog
'resetpass' => 'Wachwaord obbenuuts insjtèlle',
'resetpass_announce' => "Doe bös aangemeld mèt 'ne tiejdelikke code dae per e-mail is toegezönje. Veur 'n nuuj wachwaord in om 't aanmelje te voltooie:",
'resetpass_header' => 'Wachwaord obbenuuts insjtèlle',
'oldpassword' => 'Hujig wachwaord',
'newpassword' => 'Nuuj wachwaord',
'retypenew' => "Veur 't nuuj wachwaord nogins in",
'resetpass_submit' => 'Wachwaord instelle en aanmelje',
'changepassword-success' => 'Dien wachwaord is verangerd. Bezig mèt aanmelje...',
'resetpass_forbidden' => 'Wachwäörd kónne neet verangerd waere',
'resetpass-no-info' => 'Doe moos aangemeld zien ierdets doe dees pagina gebroeke kens.',
'resetpass-submit-loggedin' => 'Wachwaord wiezige',
'resetpass-submit-cancel' => 'Aafbraeke',
'resetpass-wrong-oldpass' => "'t Hujig of tiedelik wachwaord is ongeljig.
Meugelik höbs doe dien wachwaord al gewiezig of 'n nuuj tiedelik wachwaord aangevraog.",
'resetpass-temp-password' => 'Tiedelik wachwaord:',

# Special:PasswordReset
'passwordreset' => 'Wachwaord obbenuuts insjtèlle',
'passwordreset-legend' => 'Wachwaord obbenuuts insjtèlle',
'passwordreset-disabled' => "'t Is hie neet meugelik óm die wachwaord óbbenuits in te sjtelle.",
'passwordreset-username' => 'Gebroekersnaam:',
'passwordreset-domain' => 'Domein:',
'passwordreset-capture' => 'Bekiek de resulterenden e-mail?',
'passwordreset-capture-help' => "Es se dit vekske aanvènks, weurt d'n e-mail (mit e tiedelik wachwaord) nao de gebroek gesjik en ouch aan dich getuind.",
'passwordreset-email' => 'E-mailadres:',
'passwordreset-emailtitle' => 'Gebroekersgegaeves óp {{SITENAME}}',
'passwordreset-emailtext-ip' => "Emes, wersjienlik doe, vanaaf 't IP-adres $1, haet dien gebroekersgegaeves veur {{SITENAME}} ($4) ópgevraog.
De volgende {{PLURAL:$3|gebroeker is|gebroekers zint}} gekoppeld aan dit e-mailadres:

$2

{{PLURAL:$3|Dit tiedelik wachwaord vervilt|Dees tiedelike wachweurd vervallen}} euver {{PLURAL:$5|einen daag|$5 daag}}.
Mel dich aan en veranger 't wachwaord noe. Es se dit verzeuk neet zelf hes gedaon, of es se 't oorspronkelik wachwaord nog kins en 't neet anges wils, laot dit berich den en blief dien aad wachwaord gebroeke.",
'passwordreset-emailtext-user' => "Gebroeker $1 op de site {{SITENAME}} haet dien gebroekersgegaeves veur {{SITENAME}} ($4) ópgevraog.
De volgende {{PLURAL:$3|gebroeker is|gebroekers zint}} gekoppeld aan dit e-mailadres:

$2

{{PLURAL:$3|Dit tiedelik wachwaord vervilt|Dees tiedelike wachweurd vervallen}} euver {{PLURAL:$5|einen daag|$5 daag}}.
Mel dich aan en veranger 't wachwaord noe. Es se dit verzeuk neet zelf hes gedaon, of es se 't oorspronkelik wachwaord nog kins en 't neet anges wils, laot dit berich den en blief dien aad wachwaord gebroeke.",
'passwordreset-emailelement' => 'Gebroekersnaam: $1
Tiedelik wachwaord: $2',
'passwordreset-emailsent' => "d'r Is per mail 'n herinnering versjik.",
'passwordreset-emailsent-capture' => "d'r Is 'ne herinneringse-mail versjik. Deze weurt hieónger getuind.",
'passwordreset-emailerror-capture' => "d'r Is 'ne herinneringse-mail aangemaak. Deze weurt hieónger getuind. 't Verzènje nao de gebroeker is mislök óm de volgende raeje: $1",

# Special:ChangeEmail
'changeemail' => 'Veranger dien e-mailadres',
'changeemail-header' => "Veranger 't e-mailadres van miene gebroekersnaam",
'changeemail-text' => 'Völ dit form in óm dien e-mailadres te verangere. Doe mós dien wachwaord inveuren óm dees veranger te bevestige.',
'changeemail-no-info' => 'Doe moos aangemeld zien ierdets doe dees pagina gebroeke kens.',
'changeemail-oldemail' => 'Hujig mailadres:',
'changeemail-newemail' => 'Nuuj mailadres:',
'changeemail-none' => '(gein)',
'changeemail-submit' => 'Veranger e-mail',
'changeemail-cancel' => 'Braek aaf',

# Edit page toolbar
'bold_sample' => 'Vètten teks',
'bold_tip' => 'Vetten teks',
'italic_sample' => 'Sjuunsen tèks',
'italic_tip' => 'Sjuunsen tèks',
'link_sample' => 'Link titel',
'link_tip' => 'Interne link',
'extlink_sample' => 'http://www.example.com link titel',
'extlink_tip' => 'Extern link (mit de http:// prefix)',
'headline_sample' => 'Deilongerwerp',
'headline_tip' => 'Tusseköpske (hoogste niveau)',
'nowiki_sample' => 'Veur hiej de neet op te make teks in',
'nowiki_tip' => 'Verloup wiki-opmaak',
'image_tip' => 'Mediabesjtandj',
'media_tip' => 'Link nao bestandj',
'sig_tip' => 'Dien handjteikening mit datum en tied',
'hr_tip' => 'Horizontaal lien (gebroek spaarzaam)',

# Edit pages
'summary' => 'Samevatting:',
'subject' => 'Ongerwerp/kop:',
'minoredit' => "Dit is 'n klein verangering",
'watchthis' => 'Volg dees pagina',
'savearticle' => 'Pagina opsjlaon',
'preview' => 'Naokieke',
'showpreview' => 'Betrach dees bewirking',
'showlivepreview' => 'Bewèrking ter controle tuine',
'showdiff' => 'Toen verangeringe',
'anoneditwarning' => 'Doe bis neet aangemeld. Dien IP adres weurt opgesjlage in de historie van dees pagina.',
'anonpreviewwarning' => "''Doe bös neet aangemeldj.''
''Door dien bewèrking op te slaon wört dien IP-adres opgeslagen in de paginagesjiedenis.''",
'missingsummary' => "'''Herinnering:''' doe höbs gein samevatting opgegaeve veur dien bewirking. Es te weer op ''Pagina opslaon'' kliks weurt de bewirking zonger samevatting opgesjlage.",
'missingcommenttext' => 'Plaats dien opmèrking hiej onger, a.u.b.',
'missingcommentheader' => "'''Let op:''' Doe höbs gén ongerwerp/kop veur deze opmèrking opgegaeve. Esse oppernuuj op \"{{int:savearticle}}\" kliks, wörd dien verangering zonger ongerwerp/kop opgeslage.",
'summary-preview' => 'Naokieke samevatting:',
'subject-preview' => 'Naokieke ongerwerp/kop:',
'blockedtitle' => 'Gebroeker is geblokkeerd',
'blockedtext' => "'''Dien gebroekersaccount of IP-adres is geblokkeerd.'''

De blokkade is oetgeveurd door $1. De opgegaeve raej is ''$2''.

* Aanvang blokkade: $8
* Ènj blokkade: $6
* Bedoeld te blokkere: $7

De kèns contak opnumme mit $1 of 'ne angere [[{{MediaWiki:Grouppage-sysop}}|systeemwèrker]] óm de blokkade te besjpraeke.
De kèns gein gebroek make van de functie 'e-mail deze gebroeker', behauve es te 'n geldig e-mailadres höbs opgegaeve in dien [[Special:Preferences|veurkäöre]] en 't gebroek van deze fónksie neet geblokkeerd is.
Dien hujig IP-adres is $3 en 't nómmer van de blokkade is #$5. Vermeld beide gegaeves wens te örges op dees blokkade reageers.",
'autoblockedtext' => "Dien IP-adres is automatisch geblokkeerd omdet 't gebroek is door 'ne gebroeker, dae is geblokkeerd door $1.
De opgegaeve reje is:

:''$2''

* Aanvang blokkade: $8
* Einde blokkade: $6
* Blóksmeining: $7

Doe kins deze blokkaasj bespraeke mèt $1 of 'ne angere [[{{MediaWiki:Grouppage-sysop}}|beheerder]]. Doe kins gén gebroek make van de functie 'e-mail deze gebroeker', tenzijse 'n geldig e-mailadres opgegaeve höbs in dien [[Special:Preferences|veurkeure]] en 't gebroek van deze functie neet is geblokkeerd.

Dien nömmer vanne blokkaasj is #$5 èn dien IP-adres is $3.
Vermeld det esse örges euver deze blokkaasj reageers.",
'blockednoreason' => 'geine ree opgegaeve',
'whitelistedittext' => 'Geer mót uch $1 óm pajzená te bewirke.',
'confirmedittext' => "De mós dien e-mailadres bevestige veurdats te kèns bewirke.
Veur dien e-mailadres in en bevestig 'm bie [[Special:Preferences|dien veurkäöre]].",
'nosuchsectiontitle' => 'Deze subkop bestuit neet',
'nosuchsectiontext' => "Doe probeers 'ne subkop te bewirke dae neet besjtuit.
Meugelikerwies is t'r verplaats of gewösj gewaore wiel se de paasj 'nt bekieke woors.",
'loginreqtitle' => 'Aanmelje verplich',
'loginreqlink' => 'aanmelde',
'loginreqpagetext' => "Doe moos diech $1 om anger pagina's te bekieke.",
'accmailtitle' => 'Wachwaord versjtuurd.',
'accmailtext' => "'n Willekäörig wachwaord veur [[User talk:$1|$1]] is nao $2 gesjtuurd.

't Wachwaord veur deze nuje gebroeker kan gewiezig waere via de pagina ''[[Special:ChangePassword|Wachwaord wiezige]]'' nao 't aanmelje.",
'newarticle' => '(Nuuj)',
'newarticletext' => "De höbs 'ne link gevolg nao 'n pagina die nog neet besjteit.
Type in de box hiejónger óm de pagina te beginne (zuug de [[{{MediaWiki:Helppage}}|helppagina]] veur mie infermasie).
Es te hie per óngelök terech bis gekómme, klik dan op de '''trök'''-knóp van diene browser.",
'anontalkpagetext' => "----''Dit is de euverlèkpagina veur 'ne anonieme gebroeker dae nog gein account haet aangemaak of dae 't neet gebroek.
Daoveur gebroeke v'r 't IP-adres óm de gebroeker te identificere.
Det adres kan waere gedeild door mierdere gebroekers.
Es te 'ne anonieme gebroeker bis en de höbs 't geveul dat 'r ónrelevante commentare aan dich gerich zeen, kèns te 't bèste [[Special:UserLogin/signup|'n account crëere]] of [[Special:UserLogin|inlogge]] óm toekomstige verwarring mit anger anoniem gebroekers te veurkomme.''",
'noarticletext' => 'Dees pagina bevat gein teks.
De kèns [[Special:Search/{{PAGENAME}}|nao deze term zeuke]] in anger pagina\'s, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} de logbeuk doorzeuke] of [{{fullurl:{{FULLPAGENAME}}|action=edit}} dees pagina bewirke]</span>.',
'noarticletext-nopermission' => 'Dees pagina bevat gein teks.
De kans [[Special:Search/{{PAGENAME}}|nao dees term zeuke]] in anger pagina\'s of
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} de logbeuk doorzeuke]</span>.',
'userpage-userdoesnotexist' => 'Doe bewirks \'n gebroekerspagina van \'ne gebroeker dae neet besjteit (gebroeker "<nowiki>$1</nowiki>"). Controlere ofs doe dees pagina waal wils aanmake/bewirke.',
'userpage-userdoesnotexist-view' => 'Gebroeker "$1" is neet geregistreerd.',
'blocked-notice-logextract' => "Deze gebroeker is noe geblok.
De leste bloklogregel wuuertj hiejónger t'r raodpleging gegaeve:",
'clearyourcache' => "Lèt op:''' Nao 't opsjlaon mós te diene browsercache wisse óm de verangeringe te zeen:
* '''Firefox / Safari:''' hauw ''Shift'' ingedrök terwiels te op ''Vernuuj'' kliks of duujs op ''Ctrl-F5'' of ''Ctrl-R'' (''Command-R'' op 'ne Mac)
* '''Google Chrome:''' duuj op ''Ctrl-Shift-R'' (''Command-Shift-R'' op 'ne Mac)
* '''Internet Explorer:''' hauw ''Ctrl'' ingeduujt terwiels te op ''Vernuuj'' kliks of duujs op ''Ctrl-F5''
* '''Konqueror: '''klik op ''Reload'' of duuj op ''F5''
* '''Opera:''' laeg diene cache in ''Extra → Veurkäöre''",
'usercssyoucanpreview' => "'''Tip:''' Gebroek de knóp '{{int:showpreview}}' om dien nuuj CSS te teste veurdets te opsjleis.",
'userjsyoucanpreview' => "'''Tip:''' Gebroek de knóp '{{int:showpreview}}' om dien nuuj JS te teste veurdets te opsjleis.",
'usercsspreview' => "'''Dit is allein 'n veurvertuun van dien perseunlike css, deze is neet opgeslage!'''",
'userjspreview' => "'''Lèt op: doe tes noe dien persuunlik JavaScript.'''
'''De pagina is neet opgesjlage!'''",
'sitecsspreview' => "'''Dit is allein 'n veurvertuin van de CSS.'''
'''Deze is nog neet opgesjlage!'''",
'sitejspreview' => "'''Dit is allein 'n veurvertuin van de JavaScriptcode.'''
'''Deze is nog neet opgesjlage!'''",
'userinvalidcssjstitle' => "'''Waarsjoewing:''' d'r is gein skin \"\$1\". 
Lèt op: dien eige .css- en .js-pagina's beginne mèt  'ne klein lètter, beveurbeeld {{ns:user}}:Naam/vector.css in plaats van {{ns:user}}:Naam/Vector.css.",
'updated' => '(Biegewèrk)',
'note' => "'''Opmirking:'''",
'previewnote' => "'''Lèt op: dit is 'n controlepagina; dien teks is nog neet opgesjlage!'''",
'continue-editing' => 'Gank dórch mit bewirke',
'previewconflict' => "Dees versie toent wie de tèks in 't bôvesjte vèld oet git zeen es e zouws opsjlaon.",
'session_fail_preview' => "'''Sorry! Dien bewerking is neet verwerkt omdat sessiegegevens verlaore zeen gegaon.
Probeer 't opnieuw. Als 't dan nog neet lukt, meldt dich dan aaf en weer aan.'''",
'session_fail_preview_html' => "'''Sorry! Dien bewerking is neet verwerk omdat sessiegegevens verlaore zeen gegaon.'''

''Omdat in deze wiki ruwe HTML is ingesjakeld, is 'n voorvertoning neet meugelik als bescherming taege aanvalle met JavaScript.''

'''Als dit een legitieme bewerking is, probeer 't dan opnieuw. Als 't dan nog neet lukt, meldt dich dan aaf en weer aan.'''",
'token_suffix_mismatch' => "'''Dien bewerking is geweigerd omdat dien client de laesteikes in 't bewerkingstoken onjuist haet behandeld. De bewerking is geweigerd om verminking van de paginateks te veurkomme. Dit gebeurt soms es d'r een webgebaseerde proxydienst wurt gebroek die foute bevat.'''",
'edit_form_incomplete' => "'''Sommige ongerdeile van 't bewerkingsformuleer höbbe de server neet bereik. Controleer of dien bewerkinge intak zien en perbeer 't obbenuits.'''",
'editing' => 'Bewirkingspagina: $1',
'creating' => '$1 aanmakendj',
'editingsection' => 'Bewirke van sectie van $1',
'editingcomment' => 'Bewirke $1 (commentair)',
'editconflict' => 'Bewirkingsconflik: $1',
'explainconflict' => "Emes angers haet dees pagina verangerd naodats doe aan dees bewirking bis begós.
't Ierste teksveld tuint de hujige versie van de pagina.
De mós dien eige verangeringe dao-in inpasse.
'''Allein''' d'n tèks in 't ierste teksveld weurt opgesjlage es te noe op \"{{int:savearticle}}\" duujs.",
'yourtext' => 'Euren teks',
'storedversion' => 'Opgesjlage versie',
'nonunicodebrowser' => "'''WAARSJOEWING: Diene browser kan neet good euverweeg mit unicode.
 Hie waert rekening mit gehauwe om dich zónger probleme pagina's te kinne laote bewirke: neet-ASCII karakters waere in 't bewirkingsveld weergegaeve es hexadecimaal codes.'''",
'editingold' => "'''WAARSJOEWING: De bis 'n auw versie van dees pagina aan 't bewirke.'''
 Es te dees bewirking opjsleis, gaon alle verangeringe die nao dees versie zien aangebrach verlore.",
'yourdiff' => 'Verangeringe',
'copyrightwarning' => "Opgelèt: Alle biedrage aan {{SITENAME}} weure geach te zeen vriegegaeve ónger de $2 (zuug $1 veur details). Wens te neet wils dat dienen teks door angere bewirk en versjpreid weurt, kees dan neet veur 'Pagina opsjlaon'.<br /> Hiebie belaofs te ós ouch dats te dees teks zelf höbs gesjreve, of höbs euvergenómme oet 'n vriej, openbaar brón.<br /> '''GEBROEK GEI MATERIAAL DAT BESJIRMP WEURT DOOR AUTEURSRECH, BEHAUVE WENS TE DAO TOESJTÖMMING VEUR HÖBS!'''",
'copyrightwarning2' => "Mèrk op dat alle biedrages aan {{SITENAME}} kinne weure verangerd, aangepas of weggehaold door anger luuj. As te neet wils dat dienen tèks zoemer kint weure aangepas mós te 't hie neet plaatsje.<br />
De beluifs ós ouch dats te dezen tèks zelf höbs gesjreve, of gekopieerd van 'n brón in 't publiek domein of get vergliekbaars (zuug $1 veur details).
'''HIE GEIN AUTEURSRECHTELIK BESJIRMP WERK ZÓNGER TOESJTUMMING!'''",
'longpageerror' => "Fout: De teks diese höbs toegevoegd haet is {{PLURAL:$1|'ne kilobyte|$1 kilobyte}} groet, wat groeter is es 't maximum van {{PLURAL:$2|'ne kilobyte|$2 kilobyte}}. Opslaon is neet meugelik.'''",
'readonlywarning' => "WAARSJUWING: De database is vasgezèt veur ongerhoud, dus op 't mement kins e dien verangeringe neet opsjlaon. De kins dien tèks 't biste opsjlaon in 'n tèksbesjtand om 't later hie nog es te prebere.

t Is geslaote waenger: $1",
'protectedpagewarning' => "'''WAARSJOEWING: Dees pagina is besjirmp zoedet ze allein door gebroekers mit administratorrechte kint waere verangerd.'''
De lèste logbookregel sjteit hiejónger:",
'semiprotectedpagewarning' => "'''Lèt op:''' Dees pagina is beveilig en kin allein door geregistreerde gebroekers bewirk waere.
De lèste logbookregel steit hiejónger:",
'cascadeprotectedwarning' => "'''Waarschuwing:''' Deze pagina is beveilig en kin allein door beheerders bewerk waere, omdat deze is opgenaome in de volgende {{PLURAL:$1|pagina|pagina's}} {{PLURAL:$1|dae|die}} beveilig {{PLURAL:$1|is|zeen}} met de cascade-optie:",
'titleprotectedwarning' => "'''WAORSJUWING: Deze pagina is beveilig zodet allein inkele gebroekers 'm kinne aanmake. De beneuds [[Special:ListGroupRights|speciale rechte]].'''
De lèste logbookregel vólg hier:",
'templatesused' => 'Op dees pagina {{PLURAL:$1|gebroek sjabloon|gebroekde sjablone}}:',
'templatesusedpreview' => '{{PLURAL:$1|Sjabloon|Sjablone}} gebroek in dees veurvertuining:',
'templatesusedsection' => '{{PLURAL:$1|Sjabloon|Sjablone}} in dees sectie in gebroek:',
'template-protected' => '(besjirmp)',
'template-semiprotected' => '(semi-besjörmp)',
'hiddencategories' => 'Dees pagina vélt in de volgende verborge {{PLURAL:$1|categorie|categorië}}:',
'nocreatetext' => "{{SITENAME}} haet de mäögelikheid óm nuuj pagina's te make beperk.
De kans al besjtaonde pagina's verangere, of de kans [[Special:UserLogin|dich aanmelde of 'n gebroekersaccount aanmake]].",
'nocreate-loggedin' => "De höbs gein rechte óm nuuj pagina's te make.",
'sectioneditnotsupported-title' => 'De kans hie gein köpkes bewirke',
'sectioneditnotsupported-text' => 'Doe kèns hie gein köpkes bewèrke',
'permissionserrors' => 'Foute inne rèchter',
'permissionserrorstext' => 'Doe höbs gein rèchter om det te daon om de volgende {{PLURAL:$1|reje|rejer}}:',
'permissionserrorstext-withaction' => 'Geer höb gein rech óm $2 óm de volgende {{PLURAL:$1|raej|raej}}:',
'recreate-moveddeleted-warn' => "'''Waarsjoewing: de bis bezig mit 't aanmake van 'n pagina die in 't verleje eweggesjaf is.'''

Bedink of 't terech is dets te wiejer wirks aan dees pagina. Veur dien gemaak sjteit hiejónger 't wislogbook en 't logbook verplaatste pagina's veur dees pagina:",
'moveddeleted-notice' => "Dees pagina is eweggesjaf.
Ter infermasie weurt 't wislogbook en 't logbook verplaatsjde pagina's van dees pagina hiejónger weergegaeve.",
'log-fulllog' => "Bekiek 't gans logbook",
'edit-hook-aborted' => "De bewèrking is aafgebroke door 'ne 'hook'.
D'r is gein toelichting besjikbaar.",
'edit-gone-missing' => 'De pagina is neet biegewirk.
Ze lik eweggesjaf te zien.',
'edit-conflict' => 'Bewirkingsconflik.',
'edit-no-change' => "Dien bewirking is genegeerd, ómdet d'r gein verangering in de teks is gemaak.",
'edit-already-exists' => 'De pagina is neet aangemaak.
Zie besjteit al.',
'defaultmessagetext' => 'Obligaten teks',
'editwarning-warning' => "Es se dees pagina verleets verluus se meugelik wieziginge die se haes gemaak.
Es se bös aangemeld, kins se dees waorsjoewing oetzètten in 't bewerkingstabblaad in dien veurkäöre.",

# Parser/template warnings
'expensive-parserfunction-warning' => "'''Waarschuwing:''' dees pagina gebroek te väöl kosbare parserfuncties.

Noe {{PLURAL:$1|is|zeen}} 't d'r $1, terwiel 't d'r minder es $2 {{PLURAL:$2|mótte|mótte}} zeen.",
'expensive-parserfunction-category' => "Pagina's die te väöl kosbare parserfuncties gebroeke",
'post-expand-template-inclusion-warning' => 'Waorsjuwing: de maximaal transclusiegruudje veur sjablone is euversjri-jje.
Sommige sjablone waere neet getranscludeerd.',
'post-expand-template-inclusion-category' => "Pagina's woeveur de maximaal transclusiegruutde is euversjreje",
'post-expand-template-argument-warning' => "Waarsjoewing: dees pagina bevat winnigstes eine sjabloonparameter mit 'n te groete transclusiegruutde.
Dees parameters zeen eweggelaote.",
'post-expand-template-argument-category' => "Pagina's die missende sjabloonillemènte bevatte",
'parser-template-loop-warning' => "D'r is 'ne krinkloup in sjablone geconstateerd: [[$1]]",
'parser-template-recursion-depth-warning' => 'De recursiedeepte veur sjablone is euversjrede ($1)',
'language-converter-depth-warning' => 'De deepdjelimiet veure spraokómzètter is euversjreje ($1)',
'node-count-exceeded-category' => "Pagina's wo 't maximaal aantal nodes te väöl is",
'node-count-exceeded-warning' => "Oppe paasj is 't maximaal aantal nodes te väöl",
'expansion-depth-exceeded-category' => "Pagina's wo de expansiedeepdje te väöl is",
'expansion-depth-exceeded-warning' => 'De paasj haet te väöl sjablone',
'parser-unstrip-loop-warning' => 'Unstriplus gevónje',
'parser-unstrip-recursion-limit' => 'Unstriprecursielimiet te väöl ($1)',

# "Undo" feature
'undo-success' => "Hiej onger stuit de teks wo in de verangering ongedaon gemaak is. Controleer veur 't opslaon of 't resultaot gewins is.",
'undo-failure' => 'De verangering kòs neet ongedaon gemaak waere waeges angere striedige verangeringe.',
'undo-norev' => 'De bewerking kon neet ongedaan gemaak waere, omdat die neet besteet of is verwijderd.',
'undo-summary' => 'Versie $1 van [[Special:Contributions/$2|$2]] ([[User talk:$2|euverlèk]]) óngedaon gemaak.',

# Account creation failure
'cantcreateaccounttitle' => 'Aanmake gebroeker misluk.',
'cantcreateaccount-text' => "'t Aanmake van gebroekers van dit IP-adres ('''$1''') is geblokkeerd door [[User:$3|$3]].

De door $3 opgegaeve reje is ''$2''",

# History pages
'viewpagelogs' => 'Logbeuk veur dees pagina tuine',
'nohistory' => 'Dees pagina is nog neet bewirk.',
'currentrev' => 'Hujige versie',
'currentrev-asof' => 'Hujige versie per $1',
'revisionasof' => 'Versie op $1',
'revision-info' => 'Versie op $1 door $2',
'previousrevision' => '← Awwer versie',
'nextrevision' => 'Nuujere versie→',
'currentrevisionlink' => 'zuug hujige versie',
'cur' => 'hujig',
'next' => 'volgende',
'last' => 'veurige',
'page_first' => 'ierste',
'page_last' => 'lèste',
'histlegend' => 'Verklaoring aafkortinge: (wijz) = versjil mit actueile versie, (vörrige) = versjil mit vörrige versie, K = kleine verangering',
'history-fieldset-title' => 'Door de historie blajere',
'history-show-deleted' => 'Inkel eweggesjaf',
'histfirst' => 'Aajste',
'histlast' => 'Nuujste',
'historysize' => '({{PLURAL:$1|1 byte|$1 bytes}})',
'historyempty' => '(laeg)',

# Revision feed
'history-feed-title' => 'Bewerkingseuverzich',
'history-feed-description' => 'Bewerkingseuverzich veur dees pagina op de wiki',
'history-feed-item-nocomment' => '$1 op $2',
'history-feed-empty' => "De gevraogde pagina besjteit neet.
Wellich is ze gewis of verplaats.
[[Special:Search|Doorzeuk de wiki]] veur relevante pagina's.",

# Revision deletion
'rev-deleted-comment' => '(bewirkingssamevatting eweggesjaf)',
'rev-deleted-user' => '(gebroeker weggehaold)',
'rev-deleted-event' => '(actie weggehaold)',
'rev-deleted-user-contribs' => '[gebroeker of IP gewösj - bewèrking verbórge in biedraag]',
'rev-deleted-text-permission' => "Dees bewerking is '''gewusj'''.
Dao kónne details aanwezig zeen in 't [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} wusjlogbook].",
'rev-deleted-text-unhide' => "Dees versie van de pagina is '''eweggesjaf'''.
Details zien meugelik te vinde in 't [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} wislogbook].
Es beheerder kins se [$1 dees versie bekieke] es se wils.",
'rev-suppressed-text-unhide' => "Dees paginaversie is '''óngerdrök'''.
Achtergrönj zeen meugelik te vinje in 't [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} logbook ven óngerdrökdje versies].
Es behierder kèns toe [$1 de versjille bekieken] es se wils.",
'rev-deleted-text-view' => "Dees bewèrking is '''gewösj'''.
Es beheerder kèns te deze zeen;
dao kónne details aanwezig zeen in 't [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} wusjlogbook].",
'rev-suppressed-text-view' => "Dees paginaversie is '''óngerdrók'''.
Es behieërder kèns se dees bekieke;
achtergrönj zeen meugelik te vinjen in 't [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} logbook ven óngerdrökdje versies].",
'rev-deleted-no-diff' => "De kins de versjille neet bekieke omdet ein van de versies '''is verwiederd'''.
Achtergrönj zeen meugelik te vinje in t [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} verwiederlogbook].",
'rev-suppressed-no-diff' => "Doe kèns de versjèller neet bekieke, wiel ein dèr versies '''gwösj''' steit.",
'rev-deleted-unhide-diff' => "Eine venne angerversjiller is '''gwösj'''.
Details in 't [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} wislog].
Es behieërder kèns se nag [$1 de angering zeen] es se doearch wils gaon.",
'rev-suppressed-unhide-diff' => "Ein vanne versies in dees versjillen is '''óngerdruk'''.
Achtergrönj zeen meugelik te vinjen in 't [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} verbergingslogbook].
Es behierder kèns se [$1 dees versie bekieken] es se wils.",
'rev-deleted-diff-view' => "Ein vanne bewirkinge veure versjille die se höbs ópgevraog, is '''gewösj'''.
As behierder kèns se dees versjille bekieke. Meugelik zeen details zichbaar in 't [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} wösjlogbook].",
'rev-suppressed-diff-view' => "Ein vanne bewirking veure versjille die se höbs ópvraog wuuertj '''óngerdrók'''.
Es behieërder kèns se dees bekieke;
achtergrönj zeen meugelik te vinjen in 't [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} logbook ven óngerdrökdje versies].",
'rev-delundel' => 'Laot zeen/versjtaek',
'rev-showdeleted' => 'toean',
'revisiondelete' => 'Verwijder/herstel bewerkinge',
'revdelete-nooldid-title' => 'Geine doelverzie',
'revdelete-nooldid-text' => "Doe höbs gein(e) doelverzie(s) veur deze hanjeling opgegaeve, d'n aangaeving besteit neet, of doe perbeers de letste versie te verberge.",
'revdelete-nologtype-title' => "d'r Is gein logbooktype opgegaeve",
'revdelete-nologtype-text' => 'De höbs gein logbooktype opgegaeve om deze handeling op oet te voere.',
'revdelete-nologid-title' => 'Ongeldige logbookregel',
'revdelete-nologid-text' => 'De höbs ofwaal gein doellogbookregel opgegaeve of de aangegaeve logbookregel besteit neet.',
'revdelete-no-file' => "'d Aangegaeve bestandj besteit neet.",
'revdelete-show-file-confirm' => 'Wèt se zeker det se de gewösjdje versie ven \'t bestandj "<nowiki>$1</nowiki>" ven $2 óm $3 wils bekieke?',
'revdelete-show-file-submit' => 'Jao',
'revdelete-selected' => "'''Geselecteerde {{PLURAL:$2|bewerking|bewerkinge}} van '''[[:$1]]''':'''",
'logdelete-selected' => "'''{{PLURAL:$1|Geselecteerde log gebeurtenis|Geselecteerde log gebeurtenisse}}:'''",
'revdelete-text' => "'''Gewisjde bewerkinge zeen zichbaar in de gesjiedenis, maar de inhoud is neet langer publiek toegankelik.'''
Anger beheerders van {{SITENAME}} kinne de verborge inhoud benäöjere en de verwiedering ongedaon make mit behölp van dit sjerm, tenzij d'r additionele restricties gelje die zeen ingesteld door de systeembeheerder.",
'revdelete-confirm' => "Bevestig des se dit wils doon, des se de consequenties begrieps en des se dit deis in euvereinstömming mit 't geljendj [[{{MediaWiki:Policy-url}}|beleid]].",
'revdelete-suppress-text' => "Versies verbèrge deentj '''allein''' gebroek te waere in de volgende gevalle:
* Ongepaste perseunlike informatie
*: ''woonadres, telefoonnummers, Burger Service Nummers, enzovoors.''",
'revdelete-legend' => 'Stel zichbaarheidsbeperkinge in',
'revdelete-hide-text' => 'Verberg de bewerkte teks',
'revdelete-hide-image' => 'Verberg bestandjsinhoud',
'revdelete-hide-name' => 'Actie en doel verberge',
'revdelete-hide-comment' => 'De bewerkingssamevatting verberge',
'revdelete-hide-user' => 'Verberg gebroekersnaam/IP van de gebroeker',
'revdelete-hide-restricted' => 'Pas deze beperkinge toe op zowaal beheerders es angere',
'revdelete-radio-same' => '(anger neet)',
'revdelete-radio-set' => 'Jao',
'revdelete-radio-unset' => 'Nein',
'revdelete-suppress' => 'Ongerdruk gegaeves veur zowaal admins es angere',
'revdelete-unsuppress' => 'Verwijder beperkinge op truuk gezatte wieziginge',
'revdelete-log' => 'Reeje:',
'revdelete-submit' => 'Pas toe op de geselecteerde {{PLURAL:$1|bewèrking|bewèrkinger}}',
'revdelete-success' => "'''Wieziging zichbaarheid succesvol ingesteld.'''",
'revdelete-failure' => "'''De zichbaarheid veur de versie kos neet ingesteld waere.'''
$1",
'logdelete-success' => "'''Zichbaarheid van de gebeurtenis succesvol ingesteld.'''",
'logdelete-failure' => "'''De zichbaarheid van de logbookregel kos neet ingesteldj waere:'''
$1",
'revdel-restore' => 'Zichbaarheid verangere',
'revdel-restore-deleted' => 'gwösj versies',
'revdel-restore-visible' => 'zichber versies',
'pagehist' => 'Paginagesjiedenis',
'deletedhist' => 'Verwiederde gesjiedenis',
'revdelete-hide-current' => "dr Is 'n fout opgetraoje bie 't verberge van 't objek van $1 óm $2 oer: dit is de hudige versie.
Dees versie kan neet verbórge waere.",
'revdelete-show-no-access' => 'dr Is \'n fout opgetraoje bie \'t weergaeve van \'t objek van $1 óm $2 oer: dit objek is gemarkeerdj es "besjermp".
Doe höbs t\'r gein toegank toet.',
'revdelete-modify-no-access' => 'dr Is \'n fout opgetraoje bie \'t wiezige van \'t objek van $1 óm $2 oer: dit objek is gemarkeerdj es "besjermp".
Doe höbs gein toegank toet \'m.',
'revdelete-modify-missing' => "dr Is 'n fout opgetraoje bie 't wiezige van versienummer $1: 't kump neet veur in de database!",
'revdelete-no-change' => "'''Waarsjoewing:''' t objek van $1 om $2 oer had al de aangegaeve zichbaarheidsinstellinge.",
'revdelete-concurrent-change' => "dr Is 'n fout opgetraoje bie 't wiezige van t objek van $1 om $2 oer: de status is inmiddels gewiezig door emes anges.
Controleer de logbeuk.",
'revdelete-only-restricted' => "dr Is  n fout opgetraoje bie 't verberge van t item van $1, $2: de kins gein items ongerdrukken oet t zich van beheerders zonger ouch ein van de anger zichbaarheidsopties te selectere.",
'revdelete-reason-dropdown' => '* Väöl veurkómmendje redes veyr verwiedere
** Auteursrechtesjending
** Onbetamelike perseunlike informatie
** Potentieel lesterlike informatie',
'revdelete-otherreason' => 'Anger/biekómstig reeje:',
'revdelete-reasonotherlist' => 'Anger reeje',
'revdelete-edit-reasonlist' => 'Reeje veur verwiedering bewèrke',
'revdelete-offender' => 'Versiesjriever:',

# Suppression log
'suppressionlog' => 'Verbergingslogbook',
'suppressionlogtext' => 'De ongerstaonde lies bevat de verwiederinge en blokkades die veur beheerders verborge zeen.
In de [[Special:BlockList|blokkeerlies]] zeen de hudige blokkades te bekieke.',

# History merging
'mergehistory' => "Gesjiedenis van pagina's samevoege",
'mergehistory-header' => "Deze pagina laot uch toe om versies van de gesjiedenis van 'n brónpagina nao 'n nuujere pagina same te voege.
Wees zeker det deze wieziging de gesjiedenisdoorloupendheid van de pagina zal behaje.",
'mergehistory-box' => "Versies van twee pagina's samevoege:",
'mergehistory-from' => 'Brónpagina:',
'mergehistory-into' => 'Bestömmingspagina:',
'mergehistory-list' => 'Samevoegbare bewerkingsgesjiedenis',
'mergehistory-merge' => "De volgende versies van [[:$1]] kinne samegevoeg waere nao [[:$2]]. Gebroek de kolom mit keuzeröndjes om allein de versies gemaak op en vwur de aangegaeve tied same te voege. Let op det 't gebroeke van de navigatielinks deze kolom zal herinstelle.",
'mergehistory-go' => 'Samevoegbare bewerkinge toeane',
'mergehistory-submit' => 'Versies samevoege',
'mergehistory-empty' => 'Gein inkele versies kinne samegevoeg waere.',
'mergehistory-success' => '$3 {{PLURAL:$3|versie|versies}} van [[:$1]] zeen succesvol samegevoeg nao [[:$2]].',
'mergehistory-fail' => 'Kan gein gesjiedenis samevoege, lèvver opnuuj de pagina- en tiedparamaeters te controlere.',
'mergehistory-no-source' => 'Bronpagina $1 besteit neet.',
'mergehistory-no-destination' => 'Bestömmingspagina $1 besteit neet.',
'mergehistory-invalid-source' => "De bronpagina mot 'ne geldige titel zeen.",
'mergehistory-invalid-destination' => "De bestömmingspagina mot 'ne geldige titel zeen.",
'mergehistory-autocomment' => '[[:$1]] samegevoeg nao [[:$2]]',
'mergehistory-comment' => '[[:$1]] samegevoeg nao [[:$2]]: $3',
'mergehistory-same-destination' => 'De brónpagina en bestömmingspagina kinne neet dezelfde zien',
'mergehistory-reason' => 'Reeje:',

# Merge log
'mergelog' => 'Samevoegingslogbook',
'pagemerge-logentry' => 'voegde [[$1]] nao [[$2]] same (versies tot en met $3)',
'revertmerge' => 'Samevoging ongedaon make',
'mergelogpagetext' => "Hiejonger zuut geer 'ne lies van recente samevoeginge van 'ne paginagesjiedenis nao 'ne angere.",

# Diffs
'history-title' => '$1: bewèrkingseuverzich',
'difference-title' => '$1 versjèl tösje versies',
'difference-title-multipage' => "$1 en $2: versjèl tösje pagina's",
'difference-multipage' => '(Versjil tösje paazjes)',
'lineno' => 'Tekslien $1:',
'compareselectedversions' => 'Vergeliek geselecteerde versies',
'showhideselectedversions' => 'Tuin/versjtaek geselecteerde versies',
'editundo' => 'maak óngedaon',
'diff-multi' => '({{PLURAL:$1|Ein tusseligkende versie|$1 Tusseligkende versies}} dórch {{PLURAL:$2|eine gebroeker|$2 gebroekers}} {{PLURAL:$1|weurt|waere}} neet getuund)',
'diff-multi-manyusers' => '($1 tösseligkende versies door mier es $2 gebroekers waere neet waergaeve)',

# Search results
'searchresults' => 'Zeukresultate',
'searchresults-title' => 'Zeukresultate veur "$1"',
'searchresulttext' => 'Veur mier informatie euver zeuke op {{SITENAME}}, zuug [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle' => 'Doe zeukdes veur \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|pagina\'s die beginne mit "$1"]] {{int:pipe-separator}}[[Special:WhatLinksHere/$1|pagina\'s die verwieze nao "$1"]])',
'searchsubtitleinvalid' => 'Voor zoekopdracht "$1"',
'toomanymatches' => "d'r Wore te väöl resultate. Probeer estebleef  'n anger zeukopdrach.",
'titlematches' => 'Overeinkoms mèt volgende titels',
'notitlematches' => 'Geine inkele paginatitel gevónje mit de opgegaeve zeukterm',
'textmatches' => 'Euvereinkoms mèt artikelinhoud',
'notextmatches' => 'Geen artikel gevonden met opgegeven zoekterm',
'prevn' => 'veurige {{PLURAL:$1|$1}}',
'nextn' => 'volgende {{PLURAL:$1|$1}}',
'prevn-title' => 'Vörge {{PLURAL:$1|resultaat|$1 resultate}}',
'nextn-title' => 'Volgende {{PLURAL:$1|resultaat|$1 resultate}}',
'shown-title' => '$1 {{PLURAL:$1|resultaat|resultate}} per pagina weergaeve',
'viewprevnext' => '($1 {{int:pipe-separator}} $2) ($3) bekieke.',
'searchmenu-legend' => 'Zeukopties',
'searchmenu-exists' => "* Pagina '''[[$1]]'''",
'searchmenu-new' => "'''De pagina \"[[:\$1]]\" aanmake op deze wiki'''",
'searchmenu-prefix' => '[[Special:PrefixIndex/$1|Paginanaome mit dit veurveugsel weergaeve]]',
'searchprofile-articles' => "Inhaudelike pagina's",
'searchprofile-project' => "Help- en projekpagina's",
'searchprofile-images' => 'Bestenj',
'searchprofile-everything' => 'Alles',
'searchprofile-advanced' => 'Oetgebreid',
'searchprofile-articles-tooltip' => 'Zeuke in $1',
'searchprofile-project-tooltip' => 'Zeuke in $1',
'searchprofile-images-tooltip' => 'Zeuke naor besjtandje',
'searchprofile-everything-tooltip' => "Alle inhaud doorzeuke (inklusief euverlèkpagina's)",
'searchprofile-advanced-tooltip' => 'Zeuke in aongegeve naamruumdes',
'search-result-size' => '$1 ({{PLURAL:$2|1 waord|$2 wäörd}})',
'search-result-category-size' => '{{PLURAL:$1|1 categorielid|$1 categorielede}} ({{PLURAL:$2|1 ongercategorie|$2 ongercategorieë}}, {{PLURAL:$3|1 bestandj|$3 bestenj}})',
'search-result-score' => 'Relevantie: $1%',
'search-redirect' => '(doorverwiezing $1)',
'search-section' => '(subkop $1)',
'search-suggest' => 'Meins te sóms: $1',
'search-interwiki-caption' => 'Zösterprojecte',
'search-interwiki-default' => '$1 resultate:',
'search-interwiki-more' => '(meer)',
'search-relatedarticle' => 'Gerelateerd',
'mwsuggest-disable' => 'Suggesties via AJAX oetsjakele',
'searcheverything-enable' => 'Zeuke in alle naamruumdes',
'searchrelated' => 'gerelateerd',
'searchall' => 'alle',
'showingresults' => 'Hieonger staon de <b>$1</b> {{PLURAL:$1|resultaat|resultaat}}, vanaaf #<b>$2</b>.',
'showingresultsnum' => "Hieonger {{PLURAL:$3|steit '''1''' resultaat|staon '''$3''' resultate}} vanaaf #<b>$2</b>.",
'showingresultsheader' => "{{PLURAL:$5|Resultaat '''$1''' van '''$3'''|Resultate '''$1 - $2''' van '''$3'''}} veur '''$4'''",
'nonefound' => "'''Lèt op:''' sjtandaard waere neet alle naamruumdes naogezeuk.
Wens doe in dien zeukopdrach es veurvoegsel \"''all:''\" gebroeks waere alle pagina's naogezeuk (inclusief euverlèkpagina's, sjablone, enzoewiejer).
Doe kans ouch 'n naamruumde es veurvoegsel gebroeke.",
'search-nonefound' => "D'r zien gein resultate veur diene zeukopdrach.",
'powersearch' => 'Zeuke',
'powersearch-legend' => 'Oetgebreid zeuke',
'powersearch-ns' => 'Zeuke in naamruumdes:',
'powersearch-redir' => 'Doorverwiezinge waergaeve',
'powersearch-field' => 'Zeuk nao',
'powersearch-togglelabel' => 'Conterleer:',
'powersearch-toggleall' => 'Alle',
'powersearch-togglenone' => 'Gein',
'search-external' => 'Extern zeuke',
'searchdisabled' => 'Zeuke op {{SITENAME}} is oetgesjakeld vanweige gebrek aan servercapaciteit.
Zoelang as de servers nog neet sjterk genog zunt kins e zeuke bie Google.
Mèrk op dat hun indexe van {{SITENAME}} content e bietje gedatierd kint zien.',

# Preferences page
'preferences' => 'Veurkäöre',
'mypreferences' => 'Mien veurkäöre',
'prefs-edits' => 'Aantal bewèrkinge:',
'prefsnologin' => 'Neet aangemèld',
'prefsnologintext' => 'De mós zeen <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} aagemeld]</span> óm dien veurkäöre te kónne insjtèlle.',
'changepassword' => 'Wachwaord verangere',
'prefs-skin' => '{{SITENAME}}-uterlik',
'skin-preview' => 'Veurbesjouwing',
'datedefault' => 'Gein veurkäör',
'prefs-beta' => 'Bètadeil',
'prefs-datetime' => 'Datum en tied',
'prefs-labs' => 'Alfadeil',
'prefs-user-pages' => "Gebroekerpagina's",
'prefs-personal' => 'Gebroekersinfo',
'prefs-rc' => 'Recènte verangeringe en weergaaf van sjtumpkes',
'prefs-watchlist' => 'Volglies',
'prefs-watchlist-days' => 'Te tuine daag in de volglies:',
'prefs-watchlist-days-max' => 'Maximaal $1 {{PLURAL:$1|daag|daag}}',
'prefs-watchlist-edits' => 'Maximaal aantal bewirkinge in de oetgebreide volglies:',
'prefs-watchlist-edits-max' => 'Maximaal aantal: 1000',
'prefs-watchlist-token' => 'Volgliessläötel:',
'prefs-misc' => 'Anger insjtèllinge',
'prefs-resetpass' => 'Wachwaord wiezige',
'prefs-changeemail' => 'Veranger e-mail',
'prefs-setemail' => "Stel 'n e-mailadres in",
'prefs-email' => 'E-mailopsjes',
'prefs-rendering' => 'Oeterlik',
'saveprefs' => 'Veurkäöre opsjlaon',
'resetprefs' => 'Sjtandaardveurkäöre hersjtèlle',
'restoreprefs' => 'Terug nao standaardinstellinge',
'prefs-editing' => 'Aafmeitinge tèksveld',
'rows' => 'Regels',
'columns' => 'Kolomme',
'searchresultshead' => 'Insjtèllinge veur zeukresultate',
'resultsperpage' => 'Aantal te tuine zeukresultate per pagina',
'stub-threshold' => 'Drempel veur markering <a href="#" class="stub">begske</a>:',
'stub-threshold-disabled' => 'Oetgezatj',
'recentchangesdays' => 'Aantal daag te tuine in de recènte verangeringe:',
'recentchangesdays-max' => '(maximaal $1 {{PLURAL:$1|daag|daag}})',
'recentchangescount' => 'Standerd aantal waer te gaeve bewèrkinge:',
'prefs-help-recentchangescount' => "Dit gelt veur recente wieziginge, paginagesjiedenis en logbookpagina's.",
'savedprefs' => 'Dien veurkäöre zint opgesjlage.',
'timezonelegend' => 'Tiedzone:',
'localtime' => 'Plaatselike tied',
'timezoneuseserverdefault' => 'Gebroek wikistanderd ($1)',
'timezoneuseoffset' => 'Angers (gaef tiedversjil)',
'timezoneoffset' => 'Tiedsversjil¹:',
'servertime' => 'Server tied:',
'guesstimezone' => 'Invulle van browser',
'timezoneregion-africa' => 'Afrika',
'timezoneregion-america' => 'Amerika',
'timezoneregion-antarctica' => 'Antarctica',
'timezoneregion-arctic' => 'Arctis',
'timezoneregion-asia' => 'Azië',
'timezoneregion-atlantic' => 'Atlantische Oceaan',
'timezoneregion-australia' => 'Australië',
'timezoneregion-europe' => 'Europa',
'timezoneregion-indian' => 'Indische Oceaan',
'timezoneregion-pacific' => 'Stille Oceaan',
'allowemail' => 'E-mail van anger gebroekers toesjtaon',
'prefs-searchoptions' => 'Zeukinstellinge',
'prefs-namespaces' => 'Naamruimte',
'defaultns' => 'Zeuk anges in dees naomruumdes:',
'default' => 'sjtandaard',
'prefs-files' => 'Bestenj',
'prefs-custom-css' => 'Persoonlijke CSS',
'prefs-custom-js' => 'Persoonlijke JS',
'prefs-common-css-js' => 'Gedeilde CSS/JS veur eder vormgaeving:',
'prefs-reset-intro' => 'Gebroek dees functie om dien veurkäöre te herstelle nao de standaardinstellinge.
Dees hanjeling kin neet ongedaon gemaak waere.',
'prefs-emailconfirm-label' => 'E-mailbevestiging:',
'youremail' => 'Dien e-mailadres',
'username' => 'Gebroekersnaam:',
'uid' => 'Gebroekersnómmer:',
'prefs-memberingroups' => 'Lid van {{PLURAL:$1|gróp|gróppe}}:',
'prefs-registration' => 'Registratiedatum:',
'yourrealname' => 'Dienen echte naam*',
'yourlanguage' => 'Taal van de gebroekersinterface',
'yourvariant' => 'Sjpraokvariantj veur inhawd:',
'prefs-help-variant' => "Dien veurkäörsvariante of -spelling óm de inhawdspagina's van dizze wiki in te tuine.",
'yournick' => "Diene bienaam (veur ''handjteikeninge'')",
'prefs-help-signature' => 'Reacties op de euverlèkpagina\'s waere meistal ongerteikend mit "<nowiki>~~~~</nowiki>".
De tildes waeren omgezat in dien handjteikening en nen datum en tied van de bewirking.',
'badsig' => 'Óngeljige roew handjteikening; kiek de HTML-tags nao.',
'badsiglength' => 'De handjteikening is te lank.
Zie maag neet mie es $1 {{PLURAL:$1|karakter|karakters}} bevatte.',
'yourgender' => 'Geslach:',
'gender-unknown' => 'Neet aangegaeve',
'gender-male' => 'Miensj',
'gender-female' => 'Vrów',
'prefs-help-gender' => 'Optioneel: dit wört gebroek om gebroekers correk aan te spraeke in de software.
Deze informatie is zichbaar veur angere gebroekers.',
'email' => 'E-mail',
'prefs-help-realname' => '* Echte naam (opsjeneel): esse deze opgufs kin deze naam gebroek waere om dich erkinning te gaeve veur dien wèrk.',
'prefs-help-email' => "E-mailadres is optioneel, mer maak 't muuëgelik óm dich e wachwaord te sjikke es s'n 't vergaete höbs.",
'prefs-help-email-others' => "Doe kans ouch angere in staat stelle per-email kóntak mit uch op te numme via 'n verwiezing op eur gebroekers- en euverlègkpazjena zónger det se diene identiteit luuëts weite.",
'prefs-help-email-required' => "Hiej veur is 'n e-mailadres neudig.",
'prefs-info' => 'Basisinfo',
'prefs-i18n' => 'Spraokinstèllinge',
'prefs-signature' => 'Handjteikening',
'prefs-dateformat' => 'Datumópmaak:',
'prefs-timeoffset' => 'Tiedsversjèl',
'prefs-advancedediting' => 'Wiejer instèllinger',
'prefs-advancedrc' => 'Wiejer instèllinger',
'prefs-advancedrendering' => 'Wiejer instèllinger',
'prefs-advancedsearchoptions' => 'Wiejer instèllinger',
'prefs-advancedwatchlist' => 'Wiejer instèllinger',
'prefs-displayrc' => 'Toeaningsinstèllinger',
'prefs-displaysearchoptions' => 'Toeaningsinstèllinger',
'prefs-displaywatchlist' => 'Toeaningsinstèllinger',
'prefs-diffs' => 'Vers',

# User preference: email validation using jQuery
'email-address-validity-valid' => "'t E-mailadres liek geldig",
'email-address-validity-invalid' => "Gif 'n geldig e-mailadres op",

# User rights
'userrights' => 'Gebroekersrechtebeheer',
'userrights-lookup-user' => 'Beheer gebroekersgróppe',
'userrights-user-editname' => "Veur 'ne gebroekersnaam in:",
'editusergroup' => 'Bewirk gebroekersgróppe',
'editinguser' => "Bezig mit 't bewèrke van de gebroekersrechte van gebroeker '''[[User:$1|$1]]''' $2",
'userrights-editusergroup' => 'Bewirk gebroekersgróppe',
'saveusergroups' => 'Gebroekersgróppe opsjlaon',
'userrights-groupsmember' => 'Leed van:',
'userrights-groupsmember-auto' => 'Impliciet lid van:',
'userrights-groups-help' => "De kèns de gróppe verangere woe deze gebroeker lid van is.
* 'n Aangekruuts vinkvekske beteikent det de gebroeker lid is van de gróp.
* 'n Neet aangekruuts vinkvekske beteikent det de gebroeker neet lid is van de gróp.
* \"*\" Beteikent dets te 'ne gebroeker neet oet 'ne gróp eweg kèns haole naodets te die daobie höbs gedoon, of angersóm.",
'userrights-reason' => 'Reeje:',
'userrights-no-interwiki' => "Doe höbs gein rechte om gebroekersrechte op anger wiki's te wiezige.",
'userrights-nodatabase' => 'Database $1 besteit neet of is gein plaatselike database.',
'userrights-nologin' => "Doe mos dich [[Special:UserLogin|aanmelle]] mit 'ne gebroeker mit de zjuuste rech om gebroekersrech toe te wieze.",
'userrights-notallowed' => 'Doe höbs gein rechte om gebroekersrechte toe te voegen of te wisse.',
'userrights-changeable-col' => 'Gróppe dies te kèns behere',
'userrights-unchangeable-col' => 'Gróppe dies te neet kèns behere',

# Groups
'group' => 'Gróp:',
'group-user' => 'Gebroekers',
'group-autoconfirmed' => 'Geregistreerde gebroekers',
'group-bot' => 'Bots',
'group-sysop' => 'Administrators',
'group-bureaucrat' => 'Bureaucrate',
'group-suppress' => 'Toezichhajers',
'group-all' => '(alle)',

'group-user-member' => '{{GENDER:$1|gebroeker}}',
'group-autoconfirmed-member' => '{{GENDER:$1|geregistreerde gebroeker}}',
'group-bot-member' => '{{GENDER:$1|bot}}',
'group-sysop-member' => '{{GENDER:$1|administrator}}',
'group-bureaucrat-member' => '{{GENDER:$1|bureaucraat}}',
'group-suppress-member' => '{{GENDER:$1|toezichhajer}}',

'grouppage-user' => '{{ns:project}}:Gebroekers',
'grouppage-autoconfirmed' => '{{ns:project}}:Geregistreerde gebroekers',
'grouppage-bot' => '{{ns:project}}:Bots',
'grouppage-sysop' => '{{ns:project}}:Beheerders',
'grouppage-bureaucrat' => '{{ns:project}}:Bureaucrate',
'grouppage-suppress' => '{{ns:project}}:Euverzich',

# Rights
'right-read' => "Pagina's bekieke",
'right-edit' => "Pagina's bewerke",
'right-createpage' => "Pagina's aanmake",
'right-createtalk' => "Euverlegpagina's aanmake",
'right-createaccount' => 'Nuwe gebroekers aanmake',
'right-minoredit' => 'Bewerkinge markere as klein',
'right-move' => "Pagina's hernaome",
'right-move-subpages' => "Pagina's inclusief subpagina's verplaatse",
'right-move-rootuserpages' => "Gebroekerspagina's van 't hoegste niveau verplaatse",
'right-movefile' => 'Bestenje hernoeme',
'right-suppressredirect' => "Een doorverwijzing op de doelpagina verwijdere bie 't hernaome van 'n pagina",
'right-upload' => 'Bestande uploade',
'right-reupload' => "'n bestaond bestand euversjrieve",
'right-reupload-own' => 'Eige bestandsuploads euversjrieve',
'right-reupload-shared' => 'Sjrief bestenj oete gedeildje mediagegaevesbak plaatsjelik euver.',
'right-upload_by_url' => 'Bestande uploade via een URL',
'right-purge' => "De cache veur 'n pagina lege",
'right-autoconfirmed' => "Behandeld waere as 'n geregistreerde gebroeker",
'right-bot' => "Behandeld waere as 'n geautomatiseerd proces",
'right-nominornewtalk' => "Kleine bewerkinge aan 'n euverlegpagina leide neet tot 'n melding 'nuwe berichte'",
'right-apihighlimits' => 'Hoegere API-limiete gebroeke',
'right-writeapi' => 'Bewèrke via de API',
'right-delete' => "Pagina's verwijdere",
'right-bigdelete' => "Pagina's mit 'n grote gesjiedenis verwijdere",
'right-deletelogentry' => 'Wösj of plaats trögk specifieke logbookregels',
'right-deleterevision' => "Versies van pagina's verberge",
'right-deletedhistory' => 'Verwijderde versies bekieke, zonder te kinne zeen wat verwijderd is',
'right-deletedtext' => 'Bekieke gewösjde teks en wieziginge tösse verwiedere versies',
'right-browsearchive' => "Verwijderde pagina's bekieke",
'right-undelete' => "Verwijderde pagina's terugplaatse",
'right-suppressrevision' => 'Verborge versies bekijke en terugplaatse',
'right-suppressionlog' => 'Neet-publieke logboke bekieke',
'right-block' => 'Angere gebroekers de mogelijkheid te bewerke ontnaeme',
'right-blockemail' => "'ne gebroeker 't rech ontnaeme om e-mail te versture",
'right-hideuser' => "'ne gebroeker veur de euverige gebroekers verberge",
'right-ipblock-exempt' => 'IP-blokkades omzeile',
'right-proxyunbannable' => "Blokkades veur proxy's gelde neet",
'right-unblockself' => 'Óntblok eige gebroeker',
'right-protect' => 'Beveiligingsniveaus wijzige',
'right-editprotected' => "Beveiligde pagina's bewerke",
'right-editinterface' => 'De gebroekersinterface bewerke',
'right-editusercssjs' => 'De CSS- en JS-bestande van angere gebroekers bewerke',
'right-editusercss' => 'De CSS-bestande van angere gebroekers bewerke',
'right-edituserjs' => 'De JS-bestande van angere gebroekers bewerke',
'right-rollback' => "Snel de letste bewerking(e) van 'n gebroeker van 'n pagina terugdraaie",
'right-markbotedits' => 'Teruggedraaide bewerkinge markere es botbewerkinge',
'right-noratelimit' => "Heet gein ti'jdsafhankelijke beperkinge",
'right-import' => "Pagina's oet angere wiki's importere",
'right-importupload' => "Pagina's importere oet 'n bestandsupload",
'right-patrol' => 'Bewerkinge es gecontroleerd markere',
'right-autopatrol' => 'Bewerkinge waere automatisch es gecontroleerd gemarkeerd',
'right-patrolmarks' => 'Kontraolteikes in recènte verangeringe bekieke.',
'right-unwatchedpages' => "'n lies mit pagina's die neet op 'n volglies staon bekieke",
'right-mergehistory' => "De gesjiedenis van pagina's samevoege",
'right-userrights' => 'Alle gebroekersrechte bewerke',
'right-userrights-interwiki' => "Gebroekersrechte van gebroekers in angere wiki's wijzige",
'right-siteadmin' => 'De database blokkere en weer vriegaeve',
'right-override-export-depth' => "Export paazjes midin geslinkdje paazjes mit 'n deepdje ven 5",
'right-sendemail' => 'Versjik e-mail aan anger gebroekers',
'right-passwordreset' => 'Bekiek e-mails van ópnuuj ingestèldje wachwäörd',

# Special:Log/newusers
'newuserlogpage' => 'Logbook nuuj gebroekers',
'newuserlogpagetext' => 'Hiej ónger saton de nuuj ingesjreve gebroekers.',

# User rights log
'rightslog' => 'Gebroekersrechtelogbook',
'rightslogtext' => 'Hiej onger staon de wieziginge in gebroekersrechte.',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'dees pagina te bekieke',
'action-edit' => 'dees pagina te bewirke',
'action-createpage' => "pagina's aan te make",
'action-createtalk' => "euverlèkpagina's aan te make",
'action-createaccount' => 'deze gebroeker aan te make',
'action-minoredit' => 'deze bewirking es klein te markere',
'action-move' => 'deze pagina te verplaatse',
'action-move-subpages' => "dees pagina en biebehurende subpagina's te verplaatse",
'action-move-rootuserpages' => "gebroekerspagina's van 't hoegste niveau te verplaatse",
'action-movefile' => 'dit bestandj te hernömme',
'action-upload' => 'dit besjtandj te uploade',
'action-reupload' => 'dit besjtaond besjtandj te euversjrieve',
'action-reupload-shared' => "dit besjtandj te uploade, terwiel d'r al 'n besjtandj mèt dezelfde naam in de gedeilde repository sjteit",
'action-upload_by_url' => "dit besjtandj vanaaf 'ne URL te uploade",
'action-writeapi' => 'via de API te bewirke',
'action-delete' => 'dees pagina eweg te sjaffe',
'action-deleterevision' => 'dees versie eweg te sjaffe',
'action-deletedhistory' => 'de eweggesjafte versies van dees pagina te bekieke',
'action-browsearchive' => "eweggesjafte pagina's te zeuke",
'action-undelete' => 'dees pagina trökzètte',
'action-suppressrevision' => 'dees verborge versie betrachte en trök plaatse',
'action-suppressionlog' => 'dit besjirmp logbook betrachte',
'action-block' => "deze gebroeker 'n bewirkingsblokkaad op lèkge",
'action-protect' => "'t beveiligingsniveau van dees pagina aan passe",
'action-rollback' => 'drej bewèrkinge vanne lèste bewèrkendje gebroeker snel trögk',
'action-import' => "dees pagina van 'n angere wiki importere",
'action-importupload' => "dees pagina van 'n besjtandsupload importere",
'action-patrol' => 'bewerkinge van angere es gecontroleerd te markere',
'action-autopatrol' => 'eige bewerkinge as gecontroleerd te laote markere',
'action-unwatchedpages' => "de lies met pagina's die neet op 'n volglies staon  te bekieke",
'action-mergehistory' => 'de gesjiedenis van deze pagina same te voge',
'action-userrights' => 'alle gebroekersrechte te bewerke',
'action-userrights-interwiki' => "gebroekersrechte van gebroekers van anger wiki's te bewerke",
'action-siteadmin' => 'de database aaf te sloete of aope te stelle',
'action-sendemail' => 'Sjik e-mails',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|bewerking|bewerkinge}}',
'recentchanges' => 'Lètste verangeringe',
'recentchanges-legend' => 'Opties veur recènte verangeringe',
'recentchanges-summary' => 'Volg de recènste bewirkinge op deze wiki op dees pagina.',
'recentchanges-feed-description' => 'Volg de meis recente bewerkinge in deze wiki via deze feed.',
'recentchanges-label-newpage' => "Mit dees verangering is 'n nuuj pagina aangemaak",
'recentchanges-label-minor' => "Dit is 'n klein bewirking",
'recentchanges-label-bot' => "Dees bewirking is oetgeveurd door 'ne bot",
'recentchanges-label-unpatrolled' => 'Dees bewirking is nog neet gekónterleerd',
'rcnote' => "Hiejónger {{PLURAL:$1|steit de lètste bewirking|staon de lètste '''$1''' bewirkinge}} van de aafgeloupe {{PLURAL:$2|daag|'''$2''' daag}}, op $4, um $5.",
'rcnotefrom' => "Verangeringe sins <b>$2</b> (mit 'n maximum van <b>$1</b> verangeringe).",
'rclistfrom' => 'Tuin de verangeringe vanaaf $1',
'rcshowhideminor' => '$1 klein bewèrkinge',
'rcshowhidebots' => '$1 bots',
'rcshowhideliu' => '$1 aangemelde gebroekers',
'rcshowhideanons' => '$1 anonieme gebroekers',
'rcshowhidepatr' => '$1 gecontroleerde bewerkinge',
'rcshowhidemine' => '$1 mien bewirkinge',
'rclinks' => 'Bekiek de $1 lètste verangeringe van de aafgeloupe $2 daag.<br />$3',
'diff' => 'vers',
'hist' => 'hist',
'hide' => 'Versjtaek',
'show' => 'Laot zeen',
'minoreditletter' => 'K',
'newpageletter' => 'N',
'boteditletter' => 'b',
'number_of_watching_users_pageview' => "[$1 {{PLURAL:$1|keer|keer}} op 'ne volglies]",
'rc_categories' => 'Beperk tot allein categorieë (sjeij mit \'n "|")',
'rc_categories_any' => 'Iddere',
'rc-change-size-new' => '$1 {{PLURAL:$1|byte|bytes}} nao verangering',
'newsectionsummary' => '/* $1 */ nuje subkop',
'rc-enhanced-expand' => 'Details weergaeve (JavaScript verplich)',
'rc-enhanced-hide' => 'Details verberge',
'rc-old-title' => 'oearsprónkelik aangemaak es "$1"',

# Recent changes linked
'recentchangeslinked' => 'Volg links',
'recentchangeslinked-feed' => 'Volg links',
'recentchangeslinked-toolbox' => 'Volg links',
'recentchangeslinked-title' => 'Verangeringe verwant mit "$1"',
'recentchangeslinked-summary' => "Dees speciaal pagina tuint de lètste bewirkinge op pagina's die gelink waere vanaaf deze pagina. Pagina's die op [[Special:Watchlist|dien volglies]] staon waere '''vet''' weergegaeve.",
'recentchangeslinked-page' => 'Paginanaam:',
'recentchangeslinked-to' => "Verangeringe weergaeve nao de gelinkde pagina's",

# Upload
'upload' => 'Upload',
'uploadbtn' => 'bestandj uploade',
'reuploaddesc' => "Truuk nao 't uploadformeleer.",
'upload-tryagain' => 'Wèrk bestandjsbesjrieving bie',
'uploadnologin' => 'Neet aangemèld',
'uploadnologintext' => 'De mos [[Special:UserLogin|zien aangemèld]] om besjtande te uploade.',
'upload_directory_missing' => 'De uploadmap ($1) is neet aanwezig en kos neet aangemaak waere door de webserver.',
'upload_directory_read_only' => 'De webserver kin neet sjrieve in de uploadmap ($1).',
'uploaderror' => "fout in 't uploade",
'upload-recreate-warning' => "'''Waorsjoewing: dr is e bestandj mit deze naam verwiederd of hernump.'''

Hiejonger waere t verwiederlogbook en t hernummingslogbook veur dees pagina waergaeve:",
'uploadtext' => "Gebroek 't óngersjtaond formeleer óm besjtande te uploade.
Óm ierder biegedone besjtande te betrachte of te zeuke, gank nao de [[Special:FileList|lies van geüploade besjtande]].
Uploads waere ouch biegehauwte in 't [[Special:Log/upload|uploadlogbook]], ewegsjaffinge in 't [[Special:Log/delete|wislogbook]]

Gebroek óm 'n plaetje of 'n besjtand in 'n pagina op te numme 'ne link in de vörm:
* '''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:Besjtand.jpg]]</nowiki>''' veur vól versies
* '''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:Besjtand.png|200px|thumb|alternatieve teks]]</nowiki>''' veur 'n 200px breid plaetje mit alternatieve teks es besjrieving
of veur mediabesjtande:
* '''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:Besjtand.ogg]]</nowiki>''' óm gewoen nao 't besjtand te verwieze zónger 't waer te gaeve

De lètste link is bedoeld veur mediabesjtande die gein plaetje zeen.",
'upload-permitted' => 'Toegelaote bestandstypes: $1.',
'upload-preferred' => 'Aangeweze bestandstypes: $1.',
'upload-prohibited' => 'Verbaoje bestandstypes: $1.',
'uploadlog' => 'uploadlogbook',
'uploadlogpage' => 'Uploadlogbook',
'uploadlogpagetext' => 'Hieonger de lies mit de meist recent ge-uploade besjtande. Alle tiede zunt servertiede.',
'filename' => 'Besjtandsnaom',
'filedesc' => 'Besjrieving',
'fileuploadsummary' => 'Samevatting:',
'filereuploadsummary' => 'Bestandjswieziginge:',
'filestatus' => 'Auteursrechtesituatie:',
'filesource' => 'Brón:',
'uploadedfiles' => 'Ge-uploade bestanden',
'ignorewarning' => "Negeer deze waarsjuwing en slao 't bestandj toch op",
'ignorewarnings' => 'Negeer alle waarsjuwinge',
'minlength1' => 'Bestandsname mòtte minstes éine letter bevatte.',
'illegalfilename' => 'De bestandjsnaam "$1" bevat ongeldige karakters. Gaef \'t bestandj \'ne angere naam, en probeer \'t dan opnuuj te uploade.',
'filename-toolong' => 'Bestenj moge gein name höbbe die lenger zeen es 240 bytes.',
'badfilename' => 'De naom van \'t besjtand is verangerd in "$1".',
'filetype-mime-mismatch' => 'Extensie ".$1" kömp neet euverein mit \'t MIME-type van \'t bestandj ($2).',
'filetype-badmime' => '\'t Is neet toegestaon om bestenj van MIME type "$1" te uploade.',
'filetype-bad-ie-mime' => 'Dit bestandj kan neet toegevoeg waere omdet Internet Explorer t zów indentificere es "$1", \'n neet toegelaote bestandjstype det potentieel sjadelik is.',
'filetype-unwanted-type' => "'''\".\$1\"''' is 'n ongewunsj bestandstype.
Aangeweze {{PLURAL:\$3|bestandjstype|bestandjstypes}}zeen \$2.",
'filetype-banned-type' => "{{PLURAL:\$4|'t bestandjstype '''\".\$1\"''' weurt|De bestandjstypes '''\".\$1\"''' waere}} neet toegelaote.
{{PLURAL:\$3|'t Toegelaote bestandjstype is|De toegelaote bestandjstypes zeen}} \$2.",
'filetype-missing' => 'Dit bestandj haet gein extensie (wie ".jpg").',
'empty-file' => 't Bestandj det se perbeers te uploade had gein inhald.',
'file-too-large' => 't Bestandj det se perbeers te uploade waas te groet.',
'filename-tooshort' => "t Bestandj det se perbeers te uploade had 'ne te kórte bestandjsnaam.",
'filetype-banned' => 't Bestandj det se perbeers te uploade waas van e neet-toegelaote bestandjstype.',
'verification-error' => 'De verificatie van t bestandj det se probeers te uploade is misluk.',
'hookaborted' => "De wieziging die se perbeers te make is aafgebraoke door 'nen oetbreidingshook.",
'illegal-filename' => 'Deze bestandjsnaam is neet toegelaote.',
'overwrite' => 'E bestandj euversjrieve geit neet.',
'unknown-error' => "dr Is 'n ónbekènde fout opgetraoje.",
'tmp-create-error' => 't Waas neet meugelik e tiedelik bestandj aan te make.',
'tmp-write-error' => 'dr Is n fout opgetraoje bie t sjrieve van t tiedelik bestandj.',
'large-file' => 'Aanbeveling: maak bestenj neet groter dan $1, dit bestand is $2.',
'largefileserver' => "'t Bestandj is groter dan de instelling van de server toestuit.",
'emptyfile' => "'t Besjtand wats re höbs geupload is laeg. Dit kump waorsjienliek door 'n typfout in de besjtandsnaom. Kiek estebleef ofs te dit besjtand wirkelik wils uploade.",
'windows-nonascii-filename' => 'Deze wiki ongersteunt gein bestandjsname mit speciaal teikes.',
'fileexists' => "D'r is al e besjtand mit dees naam, bekiek <strong>[[:$1]]</strong> of se dat besjtand mesjien wils vervange.
[[$1|thumb]]",
'filepageexists' => "De besjrievingspagina veur dit besjtand besjteit al op <strong>[[:$1]]</strong>, meh d'r besjteit gein besjtand mit deze naam. De samevatting dies te höbs opgegaeve zal neet op de besjrievingspagina versjiene. Bewirk de pagina handjmaotig óm dien besjrieving dao te tuine.
[[$1|thumb]]",
'fileexists-extension' => "'n bestand met dezelfde naam bestuit al: [[$2|thumb]]
* Naam van 't geüploade bestand: <strong>[[:$1]]</strong>
* Naam van 't bestaonde bestand: <strong>[[:$2]]</strong>
Lèver 'ne angere naam te keze.",
'fileexists-thumbnail-yes' => "'t Liek 'n afbeilding van 'n verkleinde grootte te zeen ''(thumbnail)''. [[$1|thumb]]
Lèver 't bestand <strong>[[:$1]]</strong> te controlere.
Es 't gecontroleerde bestand dezelfde afbeilding van oorspronkelike grootte is, is 't neet noodzakelik 'ne extra thumbnail te uploade.",
'file-thumbnail-no' => "De bestandsnaam begint met <strong>$1</strong>.
't Liek 'n verkleinde afbeelding te zeen ''(thumbnail)''.
Esse deze afbeelding in volledige resolutie höbs, upload dae afbeelding den. Wiezig anges estebleef de bestandsnaam.",
'fileexists-forbidden' => "d'r Besteit al 'n bestand met deze naam det neet kin waere euevergesjreve. Upload dien bestand onger 'ne angere naam.
[[File:$1|thumb|center|$1]]",
'fileexists-shared-forbidden' => "d'r Besteit al 'n bestand met deze naam bie de gedeilde bestenj. Upload 't bestand onger  'ne angere naam.
[[File:$1|thumb|center|$1]]",
'file-exists-duplicate' => "Dit besjtandj is identiek aon {{PLURAL:$1|'t volgende besjtandj|de volgende besjtande}}:",
'file-deleted-duplicate' => 'n Bestandj det identiek is aan dit bestandj ([[:$1]]) is veurhaer verwiederd.
Raodpleeg t verwiederingslogbook veurdet se wiejer geis.',
'uploadwarning' => 'Upload waarsjuwing',
'uploadwarning-text' => 'Pas de ongerstaonde bestandjsbesjrieving aan en perbeer t daonao opnuuj.',
'savefile' => 'Bestand opsjlaon',
'uploadedimage' => 'haet ge-upload: [[$1]]',
'overwroteimage' => 'haet \'ne nuuje versie van "[[$1]]" toegevoeg',
'uploaddisabled' => 'Uploade is oetgesjakeld',
'copyuploaddisabled' => 't Uploade van bestenj via nen URL is oetgezat.',
'uploadfromurl-queued' => 'Dienen upload is in de wachrie geplaats.',
'uploaddisabledtext' => "'t uploade van bestenj is oetgesjakeld.",
'php-uploaddisabledtext' => 'PHP-bestanduploads zeen oetgesjakeld. Controleer a.u.b. de file_uploads-instelling.',
'uploadscripted' => 'Dit bestandj bevat HTML- of scriptcode die foutief door diene browser weergegaeve kinne waere.',
'uploadvirus' => "'t Bestand bevat 'n virus! Details: $1",
'uploadjava' => "'t Bestandj is e ZIP-bestandj det 'n Java .class-bestandj bevat.
't Uploade van Java-bestenj is neet toegestaon omdet hiemit beveiligingsinstellinge omzeild kinne waere.",
'upload-source' => 'Brónbestandj',
'sourcefilename' => 'Oorspronkelike bestandsnaam:',
'sourceurl' => 'Brón URL:',
'destfilename' => 'Doeltitel:',
'upload-maxfilesize' => 'Maximale bestandjsgrootte: $1',
'upload-description' => 'Bestandjsbesjrieving',
'upload-options' => 'Uploadinstellinge',
'watchthisupload' => 'Volg dit bestandj',
'filewasdeleted' => "d'r Is eerder 'n bestandj mit deze naam verwiederd. Raodpleeg 't $1 veurdetse 't opnuuj toevoegs.",
'filename-bad-prefix' => "De naam van 't bestand detse aan 't uploade bös begint met '''\"\$1\"''', wat 'ne neet-besjrievende naam is dae meestal automatisch door 'ne digitale camera wörd gegaeve. Kees estebleef 'ne dudelike naam veur dien bestand.",
'upload-success-subj' => 'De upload is geluk',
'upload-success-msg' => 'Dienen upload van [$2] is geslaag en is besjikbaar: [[:{{ns:file}}:$1]]',
'upload-failure-subj' => 'Uploadperbleem',
'upload-failure-msg' => 'dr Waas e perbleem mit dienen upload veur [$2]:

$1',
'upload-warning-subj' => 'Uploadwaorsjoewing',
'upload-warning-msg' => 'dr Waas e perbleem mit dienen upload van [$2].
Gank trök nao t [[Special:Upload/stash/$1|uploadformuleer]] om dit perbleem te verhelpe.',

'upload-proto-error' => 'Verkeerd protocol',
'upload-proto-error-text' => "Uploads via deze methode vereise URL's die beginne met <code>http://</code> of <code>ftp://</code>.",
'upload-file-error' => 'Interne fout',
'upload-file-error-text' => "'n Intern fuitje vonj plaats wie 'n tiedelik besjtandj op de server woort aangemaak. Num aub contac op met 'ne [[Special:ListUsers/sysop|systeemwèrker]].",
'upload-misc-error' => 'Onbekinde uploadfout',
'upload-misc-error-text' => "d'r Is tiedes 't uploade 'ne onbekinde fout opgetraeje. Controleer of de URL correc en besjikbaar is en probeer 't opnuuj. Es 't probleem aanhaojt, nöm dan contac op met 'ne [[Special:ListUsers/sysop|systeemwèrker]].",
'upload-too-many-redirects' => 'De URL bevadde te väöl doorverwiezinge',
'upload-unknown-size' => 'Ónbekèndje gruuedje',
'upload-http-error' => 'dr Is n HTTP-fout opgetraoje: $1',
'upload-copy-upload-invalid-domain' => 'Kopië oplajen is neet besjikbaar in dit domein.',

# File backend
'backend-fail-stream' => "'t Waes neet mäögelik  't besjtand $1 te streame.",
'backend-fail-backup' => "'t Waes neet mäögeljk 'n reservekopie van 't besjtand $1 te make.",
'backend-fail-notexists' => "'t Besjtand $1 besjteit neet.",
'backend-fail-hashes' => "'t Waes neet mäögelik de hashes veur 't besjtand op te haole um ze te vergelieke.",
'backend-fail-notsame' => "Dao besjteit al 'n neet-identiek besjtand op de plaats $1.",
'backend-fail-invalidpath' => '$1 is gein geljig opslaagpad.',
'backend-fail-delete' => 'Kós bestjand $1 neet ewegsjaffe.',
'backend-fail-alreadyexists' => "'t Besjtand $1 besjteit al.",
'backend-fail-store' => "'t Waes neet mäögelik 't besjtand $1 op te sloon op lokasie $2.",
'backend-fail-copy' => 'Besjtand $1 kós neet nao $2 gekopieerd waere.',
'backend-fail-move' => 'Besjtand $1 kós neet nao $2 verplaats waere.',
'backend-fail-opentemp' => "'t Waes neet mäögelik 'n tiedelik besjtand te äöpene.",
'backend-fail-writetemp' => "'t Waes neet mäögelik nao 'n tiedelik besjtand te sjrieve.",
'backend-fail-closetemp' => "'t Waes neet mäögelik 'n tiedelik besjtand te sjlete.",
'backend-fail-read' => 'Kós bestjand $1 neet laeze.',
'backend-fail-create' => 'Kós bestandj $1 neet sjrieve.',
'backend-fail-maxsize' => "'t Waar neet meugelik 't bestandj $1 te besjrieve went 't is grótter es {{PLURAL:$2|eine byte|$2 byte}}.",
'backend-fail-readonly' => 'Vannen opslaag "$1" kin op dit memènt allein gelaeze waere. De opgegaeve raeje is: "$2"',
'backend-fail-synced' => '\'t Bestandj "$1" bevindj zich in \'nen ónsamehangendje toestandj inne intern opslaagbackends.',
'backend-fail-connect' => 'Kós de bestandjsbackend neet verbinje mitte opslaagbackend "$1".',
'backend-fail-internal' => '\'n Ónbekèndje fout is ópgetaoje innen opslaagbackend "$1".',
'backend-fail-contenttype' => 'Kós \'t inhawdtype van \'t bestnadj óm es "$1" op te slaon neet bepaole.',
'backend-fail-batchsize' => 'Reiks van $1 {{PLURAL:$1|bestandjsoperatie|bestandjsoperaties}} in de opslaagbackend; de limiet is $2 {{PLURAL:$2|operatie|operaties}}.',
'backend-fail-usable' => "Kós 't bestandj $1 neet besjraeve vanwaenge te mèn rèchte of aafwaezige mappe/kóntaeners.",

# File journal errors
'filejournal-fail-dbconnect' => 'Kós neet verbinje mit de journaaldatabase veur de opslaagbackend "$1".',
'filejournal-fail-dbquery' => 'Kós de journaaldatabase neet biewèrke veur de opslaagbackend "$1".',

# Lock manager
'lockmanager-notlocked' => 'Kós "$1" neet vrijgaeve; \'t waes neet vergrendeld.',
'lockmanager-fail-closelock' => 'Kós \'t vergrendelingsbesjtand veur "$1" neet sjlete.',
'lockmanager-fail-deletelock' => 'Kós \'t vergrendelingsbesjtand veur "$1" neet ewegsjaffe.',
'lockmanager-fail-acquirelock' => 'Kós "$1" neet vergrendele.',
'lockmanager-fail-openlock' => 'Kós \'t vergrendelingsbesjtand veur "$1" neet äöpene.',
'lockmanager-fail-releaselock' => 'Kós de vergrendeling veur "$1" neet opheffe.',
'lockmanager-fail-db-bucket' => 'Kós neet in kontak kómme mit genóg vergrendelingsdatabases in de bucket $1.',
'lockmanager-fail-db-release' => "'t Waar neet meugelik ómme vergrendeling veure database $1 óp tö höffe.",
'lockmanager-fail-svr-acquire' => "'t Waar neet meugelik ómme vergrendeling oppe server $1 tö kriege.",
'lockmanager-fail-svr-release' => "'t Waar neet meugelik ómme vergrendeling veure server $1 óp tö höffe.",

# ZipDirectoryReader
'zip-file-open-error' => "d'r Woor 'n fout bie 't äöpene van 't bestandj veur ZIP-controle.",
'zip-wrong-format' => "'t Opgegaeve bestandj waar gein ZIP-bestandj.",
'zip-bad' => "'t Bestandj is kepót of 'n ónlaesbaar ZIP-bestandj.
De veiligheid kin neet waere gecontroleerd.",
'zip-unsupported' => "'t Bestandj is e ZIP-bestandj det gebroek maak van ZIP-sofwaer dae MediaWiki neet begriep.
De veiligheid kin neet waere gekónterleerdj.",

# Special:UploadStash
'uploadstash' => 'Verbórge uploads',
'uploadstash-summary' => 'Dees pagina beed toegank toet bestenj die geüpload zeen of nag geüpload mótte waere, meh nag neet besjikbaar gemaak zeen óppe wiki. Dees bestenj zeen allein zichbaar veure gebroeker die ze uploadj.',
'uploadstash-clear' => 'Wis verbórge bestenj',
'uploadstash-nofiles' => "d'r Zeen gein verbórge bestenj.",
'uploadstash-badtoken' => "Kin de hanjeling neet oetveure. Dit kump mesjien omdet dien bewèrkingsrefs verloupe zeen. Perbeer 't obbenuits.",
'uploadstash-errclear' => 'Wisse van bestandj mislök.',
'uploadstash-refresh' => 'Wèrk lies van bestenj bie',
'invalid-chunk-offset' => 'Óngèljige chunckaafzatj',

# img_auth script messages
'img-auth-accessdenied' => 'Toegank geweigerd',
'img-auth-nopathinfo' => 'PATH_INFO óntbrèk.
Diene server is neet ingesteld om dees informatie door te gaeve.
Misjien gebroek deze CGI, en dan wört img_auth neet ongersteund.
Zuuch https://www.mediawiki.org/wiki/Manual:Image_Authorization aafbeildingsrechte veur mee informatie.',
'img-auth-notindir' => "'t Ópgevraogdje paad is neet de ingestelde uploadmap.",
'img-auth-badtitle' => 'Kèn geine geldige paginanaam make van "$1".',
'img-auth-nologinnWL' => 'Doe bös neet aangemeld en "$1" steit neet op de witte lies.',
'img-auth-nofile' => 'Bestandj "$1" besteit neet.',
'img-auth-isdir' => 'Doe probeers de map "$1" te benadere.
Allein toegank toet bestenj is toegestange.',
'img-auth-streaming' => '"$1" stroumendj.',
'img-auth-public' => "'t Doel van img_auth.php is de oetvour van bestenj van 'ne beslaote wiki.
Deze wiki is ingesteldj es publieke wiki.
Om beveiligingsrede is img_auth.php oetgesjakeld.",
'img-auth-noread' => 'De gebroeker haet geine laestoegank toet "$1".',
'img-auth-bad-query-string' => "De URL haet 'n óngeljige querystring.",

# HTTP errors
'http-invalid-url' => 'Ongeldige URL: $1',
'http-invalid-scheme' => 'URL\'s mit de ópmaak "$1" waere neet óngersteundj.',
'http-request-error' => "Fout bie 't verzènje van 't verzeuk.",
'http-read-error' => 'HTTP-laezingsfout.',
'http-timed-out' => 'HTTP-verzeuktimeout',
'http-curl-error' => 'Óphaolingsfout URL: $1',
'http-bad-status' => "d'r Is e perbleem ópgetraoje bie 't HTTP-verzeuk: $1 $2",

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'Kòs de URL neet bereike',
'upload-curl-error6-text' => 'De opgegaeve URL is neet bereikbaar. Controleer estebleef of de URL zjus is, en of de website besjikbaar is.',
'upload-curl-error28' => 'time-out dèr upload',
'upload-curl-error28-text' => "'t Doorde te lang veurdet de website antwaord goof. Controleer a.u.b. of de website besjikbaar is, wach effe en probeer 't dan opnuuj. De kèns 't mesjiens probere es 't minder drök is.",

'license' => 'Licentie:',
'license-header' => 'Licentie:',
'nolicense' => "Maak 'ne keuze",
'license-nopreview' => '(Veurvertuun neet besjikbaar)',
'upload_source_url' => " ('ne geldige, publiek toegankelike URL)",
'upload_source_file' => " ('n bestand op diene computer)",

# Special:ListFiles
'listfiles-summary' => 'Óp dees speciaal pagina zeen alle toegeveugde besjtande te bekieke.
Es dees pagina weurt gefilterd op gebroeker, waere allein bestenj wo de gebroeker de lètste versie van haet geüpload waergegaeve.',
'listfiles_search_for' => 'Zeuk nao bestandj:',
'imgfile' => 'bestandj',
'listfiles' => 'Lies van aafbeildinge',
'listfiles_thumb' => 'Miniatuurplaetje',
'listfiles_date' => 'Datum',
'listfiles_name' => 'Naom',
'listfiles_user' => 'Gebroeker',
'listfiles_size' => 'Gruutde (bytes)',
'listfiles_description' => 'Besjrieving',
'listfiles_count' => 'Versies',

# File description page
'file-anchor-link' => 'Besjtandj',
'filehist' => 'Besjtandjshistorie',
'filehist-help' => "Klik op 'ne datum/tied om 't besjtand te zeen wie 't destieds waor.",
'filehist-deleteall' => 'wis alles',
'filehist-deleteone' => 'wis',
'filehist-revert' => 'trökdrèjje',
'filehist-current' => 'hujig',
'filehist-datetime' => 'Datum/tied',
'filehist-thumb' => 'Miniatuurplaetje',
'filehist-thumbtext' => 'Miniatuurplaetje veur versie per $1',
'filehist-nothumb' => 'Geine miniatuuraafbeilding',
'filehist-user' => 'Gebroeker',
'filehist-dimensions' => 'Aafmaetinge',
'filehist-filesize' => 'Besjtandjgruutde',
'filehist-comment' => 'Opmirking',
'filehist-missing' => 'Besjtand ontbrik',
'imagelinks' => 'Bestandjsbroek',
'linkstoimage' => "Dit besjtand weurt op de volgende {{PLURAL:$1|pagina|pagina's}} gebroek:",
'linkstoimage-more' => "Er {{PLURAL:$2|is|zeen}} meer es $1 {{PLURAL:$1|verwiezing|verwiezinge}} nao dit bestandj.
De volgende lies göf allein de eerste {{PLURAL:$1|verwiezing|$1 verwiezinge}} nao dit bestandj waer.
d'r Is ouch ne [[Special:WhatLinksHere/$2|volledige lies]].",
'nolinkstoimage' => 'Gein inkel pagina gebroek dit plaetje.',
'morelinkstoimage' => '[[Special:WhatLinksHere/$1|Mier verwijzinge]] naor dit bestaand bekèèke.',
'linkstoimage-redirect' => '$1 (bestandjsdoorverwiezing) $2',
'duplicatesoffile' => "{{PLURAL:$1|'t Nègsvóggendj bestandj is|De $1 nègsvóggendje bestenj zeen}} identiek aan dit bestandj ([[Special:FileDuplicateSearch/$2|deper]]):",
'sharedupload' => 'Dit besjtandj kump van $1 en kin ouch door anger projekte gebroek waere.',
'sharedupload-desc-there' => 'Dit besjtandj kump van $1 en kin ouch in anger projekte gebroek waere.
Bekiek de [$2 pagina mit de besjtandjsbesjrieving] veur mie infermasie.',
'sharedupload-desc-here' => 'Dit besjtandj kump van $1 en kin ouch in anger projekte gebroek waere.
De [$2 pagina mit de besjtandjsbesjrieving] wurt hiejónger weergegaeve.',
'sharedupload-desc-edit' => 'Dit bestandj kump van $1 en kin ouch in anger projekte gebroek waere. De kins de [$2 pagina mit bestandjsbesjrieving] dao bewirke.',
'sharedupload-desc-create' => 'Dit bestandj kump van $1 en kin ouch in anger projekte gebroek waere. De kins de [$2 pagina mit bestandjsbesjrieving] dao bewirke.',
'filepage-nofile' => 'dr Besteit gei bestandj mit deze naam.',
'filepage-nofile-link' => 'dr Besteit gei bestandj mit deze naam, mer doe kins t [$1 uploade].',
'uploadnewversion-linktext' => "Upload 'n nuuj versie van dit besjtand",
'shared-repo-from' => 'ven $1',
'shared-repo' => 'n gedeilde bestanjebank',

# File reversion
'filerevert' => '$1 trökdrèjje',
'filerevert-legend' => 'Bestandj trökdrejje',
'filerevert-intro' => "Doe bös '''[[Media:$1|$1]]''' aan 't trökdrèjje tot de [$4 versie op $2, $3]",
'filerevert-comment' => 'Raeje:',
'filerevert-defaultcomment' => 'Trökgedrèjt tot de versie op $1, $2',
'filerevert-submit' => 'Trökdrèjje',
'filerevert-success' => "'''[[Media:$1|$1]]''' is trökgedrèjt tot de [$4 versie op $2, $3]",
'filerevert-badversion' => "d'r is geine vörge lokale versie van dit bestand mit 't opgegaeve tiejdstip.",

# File deletion
'filedelete' => 'Wis $1',
'filedelete-legend' => 'Wis bestand',
'filedelete-intro' => "Doe bös '''[[Media:$1|$1]]''' aan 't wisse, mit al ieëder versies.",
'filedelete-intro-old' => "Doe bös de versie van '''[[Media:$1|$1]]''' van [$4 $3, $2] aan 't wisse.",
'filedelete-comment' => 'Reeje:',
'filedelete-submit' => 'Wisse',
'filedelete-success' => "'''$1''' is gewis.",
'filedelete-success-old' => "De versie vae '''[[Media:$1|$1]]''' ven $3, $2 is gewis.</span>",
'filedelete-nofile' => "'''$1''' besteit neet.",
'filedelete-nofile-old' => "d'r is geine versie van '''$1''' in 't archief met de aangegaeve eigensjappe.",
'filedelete-otherreason' => 'Angere/additionele ree:',
'filedelete-reason-otherlist' => 'Angere ree',
'filedelete-reason-dropdown' => '*Väölveurkómmende ree veur wisse
** Auteursrechsjenjing
** Duplicaatbestandj',
'filedelete-edit-reasonlist' => 'Reeje veur verwiedering bewèrke',
'filedelete-maintenance' => 'Verwiedere en trökplaatse is tiedelik neet meugelik waeges ongerhaadswerkzaamhede.',
'filedelete-maintenance-title' => 'Kin bestandj neet wösje',

# MIME search
'mimesearch' => 'Zeuk op MIME-type',
'mimesearch-summary' => "Deze pagina maak het filtere van bestenj veur 't MIME-type meugelik. Inveur: contenttype/subtype, bv <code>image/jpeg</code>.",
'mimetype' => 'MIME-type:',
'download' => 'Downloade',

# Unwatched pages
'unwatchedpages' => "Neet-gevolgde pazjena's",

# List redirects
'listredirects' => 'Lies van redirects',

# Unused templates
'unusedtemplates' => 'Óngerbroekde sjablone',
'unusedtemplatestext' => 'Deze pagina guf alle pagina\'s weer in de {{nas:template}}naamruumde die op gein inkele pagina gebroek waere. Vergaet neet de "Links nao deze pagina" te controlere veures dit sjabloon te wösse.',
'unusedtemplateswlh' => 'anger links',

# Random page
'randompage' => 'Willekäörige pagina',
'randompage-nopages' => 'd\'r zeen gein pagina\'s in dees {{PLURAL:$2|naamruumde|naamruumde}}: "$1".',

# Random redirect
'randomredirect' => 'Willekäörige redirect',
'randomredirect-nopages' => 'd\'r zeen gein redirects in deze naamruumde "$1".',

# Statistics
'statistics' => 'Sjtattestieke',
'statistics-header-pages' => 'Paginastatistieke',
'statistics-header-edits' => 'Bewerkingsstatistieke',
'statistics-header-views' => 'Paginaweergavestatistieke',
'statistics-header-users' => 'Stattestieke euver gebroekers',
'statistics-header-hooks' => 'Anger gegaeves',
'statistics-articles' => "Inhoudelijke pagina's",
'statistics-pages' => "Pagina's",
'statistics-pages-desc' => "Alle pagina's in de wiki, inclusief euverlèkpagina's, doorverwiezinge, enz.",
'statistics-files' => 'Bestenj',
'statistics-edits' => 'Paginabewerkinge sins t begin van {{SITENAME}}',
'statistics-edits-average' => 'Gemiddeld aantal bewerkinge per pagina',
'statistics-views-total' => "Totaal aantal weergegeve pagina's",
'statistics-views-total-desc' => "'t Bekieke van neet-bestaonde pagina's en speciaal pagina's is neet inbegrepe",
'statistics-views-peredit' => "Weergegeve pagina's per bewerking",
'statistics-users' => 'Geregistreerde [[Special:ListUsers|gebroekers]]',
'statistics-users-active' => 'Actieve gebroekers',
'statistics-users-active-desc' => "Gebroekers die in de aafgeloupe {{PLURAL:$1|daag|$1 daag}} 'ne hanjeling höbbe oetgevoerd",
'statistics-mostpopular' => "Meisbekeke pazjena's",

'doubleredirects' => 'Dobbel redirects',
'doubleredirectstext' => "Dees lies haet paazjes mit redireks die nao anger redireks gaon.
Op eder raegel vings te de ierste redirectpazjena, de twiede redirectpazjena en de iesjte raegel van de twiede redirectpazjena. Meistes bevat dees litste de pazjena woe de iesjte redirect naotoe zouw mótte verwieze.
<del>Dórchstreipinge</del> zègke det 't al gedaon is.",
'double-redirect-fixed-move' => "[[$1]] is verplaats en is noe 'n doorverwiezing nao [[$2]]",
'double-redirect-fixed-maintenance' => 'Correctie dóbbel redirek van [[$1]] nao [[$2]].',
'double-redirect-fixer' => 'Doorverwiezinge opsjone',

'brokenredirects' => 'Gebraoke redirects',
'brokenredirectstext' => "De óngersjtaonde redirectpazjena's bevatte 'n redirect nao 'n neet-besjtaonde pazjena:",
'brokenredirects-edit' => 'bewerke',
'brokenredirects-delete' => 'wisse',

'withoutinterwiki' => "Pazjena's zónger interwiki's",
'withoutinterwiki-summary' => "De volgende pagina's linke neet nao versies in 'n anger taal:",
'withoutinterwiki-legend' => 'Veurvoegsel',
'withoutinterwiki-submit' => 'Toean',

'fewestrevisions' => 'Artikele met de minste bewerkinge',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories' => '$1 {{PLURAL:$1|categorie|categorië}}',
'nlinks' => '$1 {{PLURAL:$1|verwiezing|verwiezinge}}',
'nmembers' => '$1 {{PLURAL:$1|lid|lede}}',
'nrevisions' => '$1 {{PLURAL:$1|herzening|herzeninge}}',
'nviews' => '{{PLURAL:$1|eine kieër|$1 kieër}} bekeke',
'nimagelinks' => "Gebroek op $1 {{PLURAL:$1|pagina|pagina's}}",
'ntransclusions' => "Gebroek op $1 {{PLURAL:$1|pagina|pagina's}}",
'specialpage-empty' => 'Deze pagina is laeg.',
'lonelypages' => "Weispazjena's",
'lonelypagestext' => "Nao de ongerstäönde pagina's wörd vanoet {{SITENAME}} neet verweze.
De pafina's zeen ouk neet as sjabloon opgenome.",
'uncategorizedpages' => "Ongekattegoriseerde pazjena's",
'uncategorizedcategories' => 'Ongekattegoriseerde kattegorië',
'uncategorizedimages' => 'Óngecategorizeerde bestenj',
'uncategorizedtemplates' => 'Óngecategorizeerde sjablone',
'unusedcategories' => 'Óngebroekde kategorieë',
'unusedimages' => 'Ongebroekde aafbeildinge',
'popularpages' => 'Populaire artikels',
'wantedcategories' => 'Gewunsjde categorieë',
'wantedpages' => "Gewunsjde pazjena's",
'wantedpages-badtitle' => "Verkeerde titel in 't rizzeltaot gezatj: $1",
'wantedfiles' => 'Neet-bestaonde bestenj mit verwiezinge',
'wantedfiletext-cat' => "De volgendje bestenj waere gebroek meh bestaon neet. Bestenj van extern repository's kinne zeen opgenómmen in de lies, óndanks det ze bestaon. Dergelik valsj positieve waere <del>doorgehaoldj waergaeve</del>. Pagina's die neet-bestaondje bestenj insloete staon op de pagina [[:$1]].",
'wantedfiletext-nocat' => "De volgendje bestenj waere gebroek meh bestaon neet. Bestenj van extern repository's kinne zeen opgenómmen in de lies, óndanks det ze bestaon. Dergelik valsj positieve waere <del>doorgehaoldj waergaeve</del>.",
'wantedtemplates' => 'Neet-bestaonde sjablone mit verwiezinge',
'mostlinked' => "Meis gelinkde pazjena's",
'mostlinkedcategories' => 'Meis-gelinkde categorië',
'mostlinkedtemplates' => 'Meis-gebroekde sjablone',
'mostcategories' => 'Artikele mit de meiste kategorieë',
'mostimages' => 'Meis gelinkde aafbeildinge',
'mostrevisions' => 'Artikele mit de meiste bewirkinge',
'prefixindex' => "Alle pagina's op veurvoegsel",
'prefixindex-namespace' => "Alle pagina's mit 't veurvoogsel (naomruumdje $1)",
'shortpages' => 'Korte artikele',
'longpages' => 'Lang artikele',
'deadendpages' => "Doedloupende pazjena's",
'deadendpagestext' => "De ongerstäönde pagina's verwieze neet nao anger pagina's in {{SITENAME}}.",
'protectedpages' => "Besjörmde pagina's",
'protectedpages-indef' => 'Allein blokkades zonger verloupdatum',
'protectedpages-cascade' => 'Allein beveiliginge mit de cascade-optie',
'protectedpagestext' => "De volgende pagina's zeen beveilig en kinne neet bewerk en/of hernömp waere",
'protectedpagesempty' => "d'r Zeen noe gein pagina's besjörmp die aan deze paramaetere voldaon.",
'protectedtitles' => "Beveiligde pazjena's",
'protectedtitlestext' => 'De volgende titels zeen beveilig en kinne neet aangemaak waere',
'protectedtitlesempty' => "d'r Zeen momenteel gein titels beveilig die aan deze paramaeters voldaon.",
'listusers' => 'Lies van gebroekers',
'listusers-editsonly' => 'Allein gebroekers mit bewèrkinge weergaeve',
'listusers-creationsort' => 'Sortere op registratiedatum',
'usereditcount' => '$1 {{PLURAL:$1|bewèrking|bewèrkinge}}',
'usercreated' => '{{GENDER:$3|aangemaak}} óp $1 óm $2',
'newpages' => "Nuuj pagina's",
'newpages-username' => 'Gebroekersnaam:',
'ancientpages' => 'Artikele die lank neet bewèrk zeen',
'move' => 'Verplaats',
'movethispage' => 'Verplaats dees pagina',
'unusedimagestext' => "De vólgende bestenj zeen aanwezig maar óngebroek.
't Zou kinne det t'r via 'ne directe link verweze weurt.
E bestandj kèn hie dös verkieërdj ópgenómme zeen.",
'unusedcategoriestext' => 'Hiej onger staon categorië die aangemaak zeen, mèr door geine inkele pagina of angere categorie gebroek waere.',
'notargettitle' => 'Gein doelpagina',
'notargettext' => 'Ger hubt neet gezag veur welleke pagina ger deze functie wilt bekieke.',
'nopagetitle' => 'Te hernömme pazjena besteit neet',
'nopagetext' => "De pazjena dae't geer wiltj hernömme besteit neet.",
'pager-newer-n' => '{{PLURAL:$1|nujer 1|nujer $1}}',
'pager-older-n' => '{{PLURAL:$1|auwer 1|auwer $1}}',
'suppress' => 'Toezich',
'querypage-disabled' => 'Dees speciaal pagina steit oet veur performanceredene.',

# Book sources
'booksources' => 'Bookwinkele',
'booksources-search-legend' => "Zeuk informatie euver 'n book",
'booksources-go' => 'Zeuk',
'booksources-text' => "Hiej onger stuit 'n lies met koppelinge nao anger websites die nuuje of gebroekde beuk verkoupe, en die wellich meer informatie euver 't book detse zeuks höbbe:",
'booksources-invalid-isbn' => 't Ingegaeve ISBN liek neet geldig te zeen.
Controleer of se wellich n fout höbs gemaak bie de inveur.',

# Special:Log
'specialloguserlabel' => 'Oetveurder:',
'speciallogtitlelabel' => 'Doel (pagina of gebroeker):',
'log' => 'Logbeuk',
'all-logs-page' => 'Alle aopenbaar logbeuk',
'alllogstext' => "Dit is 't gecombineerd logbook ven {{SITENAME}}. De kins ouch 'n bepaald logbook keze, of filtere op gebroekersnaam of  pazjena, beide huidlettergeveulig.",
'logempty' => "d'r Zeen gein regels in 't logbook die voldaon aan deze criteria.",
'log-title-wildcard' => "Zeuk pagina's die met deze naam beginne",
'showhideselectedlogentries' => 'Tuin of verstaek geselecteerdje logbookregels',

# Special:AllPages
'allpages' => "Alle pagina's",
'alphaindexline' => '$1 nao $2',
'nextpage' => 'Volgende pazjena ($1)',
'prevpage' => 'Veurige pagina ($1)',
'allpagesfrom' => "Tuin pagina's vanaaf:",
'allpagesto' => "Pagina's betrachte tot:",
'allarticles' => 'Alle artikele',
'allinnamespace' => "Alle pazjena's (naamruumde $1)",
'allnotinnamespace' => "Alle pazjena's (neet in naamruumde $1)",
'allpagesprev' => 'Veurige',
'allpagesnext' => 'Irsvolgende',
'allpagessubmit' => 'Gank',
'allpagesprefix' => "Betrach pazjena's mit 't veurvoogsel:",
'allpagesbadtitle' => "De opgegaeve paginanaam is ongeldig of haj 'n intertaal of interwiki veurvoegsel. Meugelik bevatte de naam karakters die neet gebroek moge waere in paginanäöm.",
'allpages-bad-ns' => '{{SITENAME}} haet gein naamruumde mit de naam "$1".',
'allpages-hide-redirects' => 'Verbèrg redireks',

# SpecialCachedPage
'cachedspecial-viewing-cached-ttl' => "De bekieks 'ne paginacache dae maximaal $1 aad is.",
'cachedspecial-viewing-cached-ts' => "De bekieks 'ne paginacache dae muuegelik neet gans biegewirk is.",
'cachedspecial-refresh-now' => 'Tuin lèste.',

# Special:Categories
'categories' => 'Categorieë',
'categoriespagetext' => "De volgende {{PLURAL:$1|categorie bevat|categorieë bevatte}} pazjena's of mediabestenj.
[[Special:UnusedCategories|Óngebroekde categorieë]] waere hie neet weergegaeve.
Zuuch ouch [[Special:WantedCategories|neet-bestaondje categorieë mit verwiezinge]].",
'categoriesfrom' => 'Categorië waergaeve vanaaf:',
'special-categories-sort-count' => 'op aantal sortere',
'special-categories-sort-abc' => 'alfabetisch sortere',

# Special:DeletedContributions
'deletedcontributions' => 'Eweggesjafde gebroekersbiedrages',
'deletedcontributions-title' => 'Eweggesjafde gebroekersbiedrages',
'sp-deletedcontributions-contribs' => 'biedraag',

# Special:LinkSearch
'linksearch' => 'Zeuk extern links',
'linksearch-pat' => 'Zeukpatroon:',
'linksearch-ns' => 'Naamruumde:',
'linksearch-ok' => 'Zeuk',
'linksearch-text' => 'Wildcards wie "*.wikipedia.org" of "*.org" zeen toegestaon.
Haet mèndestes e toepleveldomein, wie beveurbeildj "*.org".<br />
Óngerstäönendje protocolle: <code>$1</code> (veug dees neet tou in dien zeukópdrach).',
'linksearch-line' => '$1 gelink vanaaf $2',
'linksearch-error' => 'Wildcards zijn alleen toegestaan aan het begin van een hostnaam.',

# Special:ListUsers
'listusersfrom' => 'Tuin gebroekers vanaaf:',
'listusers-submit' => 'Tuin',
'listusers-noresult' => 'Gein(e) gebroeker(s) gevonje.',
'listusers-blocked' => '(geblok)',

# Special:ActiveUsers
'activeusers' => 'Aktief gebroekers',
'activeusers-intro' => "Dit is 'n lies mit gebroekers die aktief zeen gewaes in de aafgeloupe {{PLURAL:$1|daag|$1 daag}}.",
'activeusers-count' => '$1 {{PLURAL:$1|bewèrking|bewèrkinger}} inne {{PLURAL:$3|lèsten daag|lès $3 daag}}',
'activeusers-from' => 'Tuin gebroekers vanaaf:',
'activeusers-hidebots' => 'Verberg bots',
'activeusers-hidesysops' => 'Verberg admins',
'activeusers-noresult' => 'Gein gebroekers gevónje.',

# Special:ListGroupRights
'listgrouprights' => 'Rechte van gebroekersgróppe',
'listgrouprights-summary' => 'Op dees pazjena sjtaon de gebroekersgróppe in deze wiki besjreve, mit zien biebehurende rechte.
Infermasie daoreuver èn de individueel rechter vinjs te [[{{MediaWiki:Listgrouprights-helppage}}|hie]].',
'listgrouprights-key' => '* <span class="listgrouprights-granted">Toeweze rech</span>
* <span class="listgrouprights-revoked">Ingetrokke rech</span>',
'listgrouprights-group' => 'Gróp',
'listgrouprights-rights' => 'Rechte',
'listgrouprights-helppage' => 'Help:Gebroekersrechte',
'listgrouprights-members' => '(ledelies)',
'listgrouprights-addgroup' => 'Kan gebroekers aan deze {{PLURAL:$2|groep|groepe}} toevoege: $1',
'listgrouprights-removegroup' => 'Kan gebroekers oet deze {{PLURAL:$2|groep|groepe}} wisse: $1',
'listgrouprights-addgroup-all' => 'Kan gebroekers aan alle groepe toevoege',
'listgrouprights-removegroup-all' => 'Kan gebroekers oet alle groepe wisse',
'listgrouprights-addgroup-self' => 'Voeg de volgende {{PLURAL:$2|groep|gruup}} toe aan eige gebroeker: $1',
'listgrouprights-removegroup-self' => 'Wösj de volgende {{PLURAL:$2|groep|gruup}} van eige gebroeker: $1',
'listgrouprights-addgroup-self-all' => 'Voeg alle gruup toe aan eige gebroeker',
'listgrouprights-removegroup-self-all' => 'Wösj alle gruup van eige gebroeker',

# Email user
'mailnologin' => 'Gein e-mailadres bekènd veur deze gebroeker',
'mailnologintext' => "De mos zien [[Special:UserLogin|aangemèld]] en 'n gèldig e-mailadres in bie dien [[Special:Preferences|veurkäöre]] höbbe ingevuld om mail nao anger gebroekers te sjture.",
'emailuser' => "Sjik deze gebroeker 'nen e-mail",
'emailpage' => "Sjik gebroeker 'nen e-mail",
'emailpagetext' => "Es deze gebroeker e geljig e-mailadres haet opgegaeve den kint g'r via dit formuleer e berich sjikke. 't E-mailadres wat geer heet opgegeve bie eur [[Special:Preferences|veurkäöre]] zal es versjikker aangegaeve waere.
Dae kin dös drek reazjere.",
'usermailererror' => "Foutmeljing biej 't zenje:",
'defemailsubject' => 'E-mail van {{SITENAME}}-gebroeker "$1"',
'usermaildisabled' => 'Gebroeker e-mail oetgezatj.',
'usermaildisabledtext' => 'Doe kèns geinen e-mail sjikke nao anger gebroekers op deze wiki',
'noemailtitle' => 'Gein e-mailadres bekènd veur deze gebroeker',
'noemailtext' => 'Deze gebroeker haet gein gèldig e-mailadres opgegaeve.',
'nowikiemailtitle' => 'E-mail is neet toegestaon',
'nowikiemailtext' => 'Deze gebroeker wil geine e-mail ontvange van anger gebroekers.',
'emailnotarget' => 'Neet-bestäöndje of óngeldige ontvanger.',
'emailtarget' => 'Veur de geadresseerde in',
'emailusername' => 'Gebroekersnaam:',
'emailusernamesubmit' => 'Slaon óp',
'email-legend' => 'ne E-mail versture nao ne angere gebroeker van {{SITENAME}}',
'emailfrom' => 'Ven:',
'emailto' => 'Aan:',
'emailsubject' => 'Óngerwerp:',
'emailmessage' => 'Berich:',
'emailsend' => 'Sjik berich',
'emailccme' => "Stuur 'n kopie van dit berich nao mien e-mailadres.",
'emailccsubject' => 'Kopie van dien berich aan $1: $2',
'emailsent' => 'E-mail sjikke',
'emailsenttext' => 'Die berich is versjik.',
'emailuserfooter' => 'Deze e-mail is verstuurd door $1 aan $2 door de functie "Deze gebroeker e-maile" van {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'Systeembrich naogelaote.',
'usermessage-editor' => 'Sysyeembrich',

# Watchlist
'watchlist' => 'Volglies',
'mywatchlist' => 'Volglies',
'watchlistfor2' => 'Veur $1 $2',
'nowatchlist' => "D'r sjtit niks op dien volglies.",
'watchlistanontext' => '$1 is verplich om dien volglies in te zeen of te wiezige.',
'watchnologin' => 'De bis neet aangemèld',
'watchnologintext' => "De mós [[Special:UserLogin|aangemèld]] zeen veur 't verangere van dien volglies.",
'addwatch' => 'Aan volglies toeveuge',
'addedwatchtext' => "De pagina \"[[:\$1]]\" is aan dien [[Special:Watchlist|volglies]] toegeveug.
Toekomstige verangeringe aan dees pagina en de biebehurende euverlèkpagina weure dao vermeld en de pagina weurt '''vèt''' weergegaeve in de [[Special:RecentChanges|lies van recènte verangeringe]].",
'removewatch' => 'Van volglies aafhoale',
'removedwatchtext' => 'De pagina "[[:$1]]" is van dien [[Special:Watchlist|volglies]] eweggesjaf.',
'watch' => 'Volg',
'watchthispage' => 'Volg dees pagina',
'unwatch' => 'Sjtop volge',
'unwatchthispage' => 'Neet mië volge',
'notanarticle' => 'Is gein artikel',
'notvisiblerev' => 'Bewèrking is verwiederd',
'watchlist-details' => "D'r {{PLURAL:$1|sjteit ein pagina|sjtaon $1 pagina's}} op dien volglies mit oetzunjering van de euverlèkpagina's.",
'wlheader-enotif' => 'Doe wörs per e-mail gewaarsjuwd',
'wlheader-showupdated' => "Pazjena's die verangerd zeen saers doe ze veur 't lètste bekeeks sjtaon '''vet'''",
'watchmethod-recent' => "Controleer recènte verangere veur gevolgde pazjena's",
'watchmethod-list' => "controlere van gevolgde pazjena's veur recènte verangeringe",
'watchlistcontains' => "Dien volglies bevat $1 {{PLURAL:$1|pazjena|pazjena's}}.",
'iteminvalidname' => "Probleem mit object '$1', ongeljige naam...",
'wlnote' => "Hieónger {{PLURAL:$1|steit de lètste verangering|staon de lètste $1 verangeringe}} van {{PLURAL:$2|'t lètste oer|de lètste <b>$2</b> oer}} óp $3 óm $4.",
'wlshowlast' => 'Tuin lètste $1 ore $2 daag $3',
'watchlist-options' => 'Opties veur volglies',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Bezig mit plaatse op de volglies...',
'unwatching' => "Oet de volglies aan 't haole...",
'watcherrortext' => 'Fout tiedens \'t verangere van dien volgliesinstellinge veur "$1".',

'enotif_mailer' => '{{SITENAME}} notificatiemail',
'enotif_reset' => "Mèrk alle bezochde pazjena's aan.",
'enotif_impersonal_salutation' => '{{SITENAME}} gebroeker',
'enotif_lastvisited' => 'Zuug $1 veur al verangeringe saer dien lèste bezeuk.',
'enotif_lastdiff' => 'Zuug $1 om deze wieziging te zeen.',
'enotif_anon_editor' => 'anonieme gebroeker $1',
'enotif_body' => 'Bèste $WATCHINGUSERNAME,

De {{SITENAME}}-pazjena "$PAGETITLE" is $CHANGEDORCREATED op $PAGEEDITDATE door $PAGEEDITOR, zuug $PAGETITLE_URL veur de hujige versie.

$NEWPAGE

Bewirkingssamevatting: $PAGESUMMARY $PAGEMINOREDIT

Contacteer de bewirker:
mail: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Dao zalle bie volgende verangeringe gein nuuj berichte kómme tenzies te dees pazjena obbenuujts bezeuks. De kans ouch de notificatievlegskes op dien volglies verzètte.

             \'t {{SITENAME}}-notificatiesysteem

--
Óm de insjtèllinge van dien volglies te verangere, zuug
{{canonicalurl:{{#special:EditWatchlist}}}}

Óm de paasj van dien wachlies aaf te haole, bezeuk
$UNWATCHURL

Commentaar en wiejer assistentie:
{{canonicalurl:{{MediaWiki:Helppage}}}}',
'created' => 'aangemaak',
'changed' => 'verangerd',

# Delete
'deletepage' => 'Pagina ewegsjaffe',
'confirm' => 'Bevèstig',
'excontent' => "inhawd waor: '$1'",
'excontentauthor' => "inhawd waor: '$1' (aangemaak door [[Special:Contributions/$2|$2]])",
'exbeforeblank' => "inhawd veur 't wisse waor: '$1'",
'exblank' => 'pazjena waor laeg',
'delete-confirm' => '"$1" wisse',
'delete-legend' => 'Wisse',
'historywarning' => 'Waorsjoewing: de pazjena die se wils wisse $1 {{PLURAL:$1|versie|versies}}:',
'confirmdeletetext' => "De sjteis op 't punt 'n pagina veur ummer eweg te sjaffe, inclusief de historie.
Kónfermeer hiejónger dat dit inderdaod dien bedoeling is, dats doe de gevolge begrips en dats doe dit deis in euvereinstömming mit 't [[{{MediaWiki:Policy-url}}|beleid]].",
'actioncomplete' => 'Actie voltoeid',
'actionfailed' => 'Hanjeling mislök',
'deletedtext' => '"$1" is eweggesjaf. Bekiek $2 veur \'n euverzich van recènt eweggesjafde pagina\'s.',
'dellogpage' => 'Wislogbook',
'dellogpagetext' => "Hie volg 'n lies van de meis recènt eweggesjafde pagina's en besjtandje.",
'deletionlog' => 'Wislogbook',
'reverted' => 'Iedere versie hersjtèld',
'deletecomment' => 'Reeje:',
'deleteotherreason' => 'Angere/eventuele ree:',
'deletereasonotherlist' => 'Angere ree',
'deletereason-dropdown' => '*Väölveurkommende wisree
** Op aanvraog van auteur
** Sjending van auteursrech
** Gebroek es zandjbak
** Vandalisme/Sjeljerie',
'delete-edit-reasonlist' => 'Reeje veur verwiedering bewèrke',
'delete-toobig' => "Dees pazjena haet 'ne lange bewerkingsgesjiedenis, mieë es $1 {{PLURAL:$1|versie|versies}}. 't Wisse van dit saort pazjena's is mit rech beperk óm 't próngelök versteure van de werking van {{SITENAME}} te veurkómme.",
'delete-warning-toobig' => "Dees pazjena haet 'ne lange bewerkingsgesjiedenis, mieë es $1 {{PLURAL:$1|versie|versies}}. 't Wisse van dees pazjena kan de werking van de database van {{SITENAME}} versteure. Bön veurzichtig.",

# Rollback
'rollback' => 'Verangering ongedaon gemaak',
'rollback_short' => 'Trökdrèjje',
'rollbacklink' => 'Trökdrieje',
'rollbackfailed' => 'Ongedaon make van wieziginge mislùk.',
'cantrollback' => 'Trökdrejje van verangeringe neet meugelik: Dit artikel haet mer einen auteur.',
'alreadyrolled' => "'t Is neet mäögelik óm de lètste verangering van [[:$1]] door [[User:$2|$2]] ([[User talk:$2|euverlèk]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]) óngedaon te make.
Emes angers haet de pagina al hersjtèld of haet 'n anger bewirking gedaon.

De lètste bewirking is gedaon door [[User:$3|$3]] ([[User talk:$3|euverlik]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).",
'editcomment' => "'t Bewirkingscommentair waor: \"''\$1''\".",
'revertpage' => 'Wieziginge door [[Special:Contributions/$2|$2]] ([[User talk:$2|Euverlik]]) trukgedriejd tot de lètste versie door [[User:$1|$1]]',
'revertpage-nouser' => 'Wieziginge door (gwösdje gebroeker) trögkgezadj nao bie [[User:$1|$1]]',
'rollback-success' => 'Wieziginge door $1 trökgedrèjd; letste versie van $2 hersteld.',

# Edit tokens
'sessionfailure-title' => 'Sessiefout',
'sessionfailure' => "d'r Liek 'n probleem te zeen mit dien aanmelsessie. Diene hanjeling is gestop oet veurzorg taenge 'n beveiligingsrisico (det bestuit oet meugelik \"hijacking\"(euverkape) van deze sessie). Gao 'n pazjena trök, laaj die pazjena opnuuj en probeer 't nog ins.",

# Protect
'protectlogpage' => "Logbook besjermde pagina's",
'protectlogtext' => "Hiej onger staon pazjena's die recèntelik beveilig zeen, of wo van de beveiliging is opgeheve.
Zuug de [[Special:ProtectedPages|lies mit beveiligde pazjena's]] veur alle hujige beveiligde pazjena's.",
'protectedarticle' => '$1 besjermd',
'modifiedarticleprotection' => 'verangerde beveiligingsniveau van "[[$1]]"',
'unprotectedarticle' => 'haet de besjerming van [[$1]] opgeheve',
'movedarticleprotection' => 'haet beveiligingsinstellinge verplaats van "[[$2]]" nao "[[$1]]"',
'protect-title' => 'Besjerme van "$1"',
'protect-title-notallowed' => 'Bekiek \'t beveiligingsniveau veur "$1"',
'prot_1movedto2' => '[[$1]] verplaats nao [[$2]]',
'protect-badnamespace-title' => 'Neet te beveilige naamruumdje',
'protect-badnamespace-text' => "Pagina's in dees naamruumdje kinne neet beveilig waere.",
'protect-legend' => 'Bevèstig besjerme',
'protectcomment' => 'Reeje:',
'protectexpiry' => 'Verlöp:',
'protect_expiry_invalid' => 'De aangegaeve verlouptied is óngeljig.',
'protect_expiry_old' => "De verloupdatum is in 't verleije.",
'protect-unchain-permissions' => 'Maak euverige beveiligingsinstèllinge besjikber',
'protect-text' => "Hie kins te 't beveiligingsniveau veur de pagina '''$1''' bekieke en verangere.",
'protect-locked-blocked' => "De kèns 't beveiligingsniveau neet verangere terwiels te geblokkeerd bis.
Hie zeen de hujige insjtèllinge veur de pazjena '''[[$1]]''':",
'protect-locked-dblock' => "'t Beveiligingsniveau kin neet waere gewiezig ómdet de database geslaote is.
Hiej zeen de hujige instellinge veur de pazjena '''$1''':",
'protect-locked-access' => "'''Dich höbs gein rechte om 't beveiligingsniveau te verangere.'''
Dit zeen de hujige insjtellinge veur de pagina '''$1''':",
'protect-cascadeon' => "Dees pagina is beveilig ómdet ze in de volgende {{PLURAL:$1|pagina|pagina's}} is opgenómme, die beveilig {{PLURAL:$1|is|zeen}} mit de kaskaad-optie. 't Beveiligingsniveau verangere haet gein inkel effek.",
'protect-default' => 'Toesjtoon veur alle gebroekers',
'protect-fallback' => 'Rech "$1" is neudig',
'protect-level-autoconfirmed' => 'Blokkere veur nuuj en anoniem gebroekers',
'protect-level-sysop' => 'Allein systeemwèrkers',
'protect-summary-cascade' => 'kaskaad',
'protect-expiring' => 'verlöp op $1',
'protect-expiring-local' => 'verlöp $1',
'protect-expiry-indefinite' => 'verlöp neet',
'protect-cascade' => "Kaskaadbeveiliging - beveilig alle pagina's en sjablone die in dees pagina opgenómme zeen (let op: dit kin groete gevolge höbbe).",
'protect-cantedit' => "De kèns 't beveiligingsniveau van dees pagina neet verangere, ómdets te gein rech höbs 't te bewirke.",
'protect-othertime' => 'Angere doer:',
'protect-othertime-op' => 'angere doer',
'protect-existing-expiry' => 'Bestaonde verloupdatum: $2 $3',
'protect-otherreason' => 'Euverige/additionele reeje:',
'protect-otherreason-op' => 'anger reeje',
'protect-dropdown' => '*Väölveurkómmende reeje veur beveiliging
** Vandalisme
** Spam
** Bewèrkingskrieg
** Preventieve beveiliging väölbezóchde paasj',
'protect-edit-reasonlist' => 'Reeje veur beveiliging bewèrke',
'protect-expiry-options' => '1 oer:1 hour,1 daag:1 day,1 waek:1 week,2 waek:2 weeks,1 maondj:1 month,3 maondj:3 months,6 maondj:6 months,1 jaor:1 year,veur iwweg:infinite',
'restriction-type' => 'Rech:',
'restriction-level' => 'Bepèrkingsniveau:',
'minimum-size' => 'Min. gruutde',
'maximum-size' => 'Max. gruutde:',
'pagesize' => '(bytes)',

# Restrictions (nouns)
'restriction-edit' => 'Bewèrke',
'restriction-move' => 'Verplaatse',
'restriction-create' => 'Aanmake',
'restriction-upload' => 'Uploade',

# Restriction levels
'restriction-level-sysop' => 'volledig beveilig',
'restriction-level-autoconfirmed' => 'semibeveilig',
'restriction-level-all' => 'eder niveau',

# Undelete
'undelete' => 'Eweggesjafde pagina betrachte',
'undeletepage' => "Verwiederde pazjena's bekieke en trukplaatse",
'undeletepagetitle' => "''''t Volgende besteit oet verwiederde bewèrkinge van [[:$1]]'''.",
'viewdeletedpage' => "Betrach eweggesjafde pagina's",
'undeletepagetext' => "De ongersjtaande {{PLURAL:$1|paasj is|pazjena's zint}} verwiederd, meh {{PLURAL:$1|bevindj|bevinge}} zich nog sjteeds in 't archief, en {{PLURAL:$1|kin|kinne}} weure truukgeplaatsj.",
'undelete-fieldset-title' => 'Versies trukplaatse',
'undeleteextrahelp' => "Om de algehele pagina inclusief alle ierder versies trök te zètte: laot alle hökskes ónaafgevink en klik op '''''{{int:undeletebtn}}'''''.
Om slechs bepaalde versies trök te zètte: vink de trök te plaatse versies aan en klik op '''''{{int:undeletebtn}}'''''.",
'undeleterevisions' => "$1 {{PLURAL:$1|versie|versies}} in 't archief",
'undeletehistory' => "Es te 'n pagina trökzèts, waere alle versies es auw versies trökgezat.
Es d'r 'ne nuuj pagina mit dezelfde naam is aangemaak sins de pagina is eweggesjaf, waere de eweggesjafde versies es auw versies trökgezat en blief de hujige versie intact.",
'undeleterevdel' => 'Hersjtelle is neet meugelik es dao door de meist recènte versie van de pagina gedeiltelik eweggesjaf waert. Sjaf in die gevalle de meist recènt eweggesjafde versies oet de selectie eweg.',
'undeletehistorynoadmin' => 'Deze pazjena is gewis. De reje hiej veur stuit hiej onger, same mit de details van de gebroekers die deze pazjena höbbe bewerk véur de verwiedering. De verwiederde inhoud van de pazjena is allein zichbaar veur beheerders.',
'undelete-revision' => 'Verwiederde versie van $1 (per $4 óm $5) door $3:',
'undeleterevision-missing' => "Ongeldige of missende versie. Meugelik höbse 'n verkeerde verwiezing of is de versie hersteld of verwiederd oet 't archief.",
'undelete-nodiff' => 'Gein eerdere versie gevonje.',
'undeletebtn' => 'Trökzètte',
'undeletelink' => 'bekieke/trökzètte',
'undeleteviewlink' => 'Bekiek',
'undeletereset' => 'Reset',
'undeleteinvert' => 'Ómgedriejde selectie',
'undeletecomment' => 'Reeje:',
'undeletedrevisions' => '$1 {{PLURAL:$1|versie|versies}} truukgeplaatsj',
'undeletedrevisions-files' => '$1 {{PLURAL:$1|versie|versies}} en $2 {{PLURAL:$2|bestandj|bestenj}} trökgeplaats',
'undeletedfiles' => '$1 {{PLURAL:$1|bestandj|bestenj}} trökgeplaats',
'cannotundelete' => "Verwiedere mislùk. Mesjien haet 'ne angere gebroeker de pazjena al verwiederd.",
'undeletedpage' => "'''$1 is trökgeplaats'''

In 't [[Special:Log/delete|logbook verwiederde pazjena's]] staon recènte verwiederinge en herstelhanjelinge.",
'undelete-header' => "Zuug [[Special:Log/delete|'t logbook verwiederde pazjena's]] veur recènt verwiederde pazjena's.",
'undelete-search-title' => "Doorzeuk verwiederde pazjena's",
'undelete-search-box' => "Doorzeuk verwiederde pazjena's",
'undelete-search-prefix' => "Tuin pagina's die beginne mit:",
'undelete-search-submit' => 'Zeuk',
'undelete-no-results' => "Gein pazjena's gevonje in 't archief mit verwiederde pazjena's.",
'undelete-filename-mismatch' => 'Bestandsversie van tiedstip $1 kos neet hersteld waere: bestandsnaam klopte neet',
'undelete-bad-store-key' => "Bestandsversie van tiedstip $1 kos neet hersteld waere: 't bestand miste al veurdet 't waerde verwiederd.",
'undelete-cleanup-error' => 'Fout bie \'t herstelle van ongebroek archiefbestand "$1".',
'undelete-missing-filearchive' => "'t Luk neet om ID $1 trök te plaatse omdet 't neet in de database is. Mesjien is 't al trökgeplaats.",
'undelete-error' => "d'r Is 'n fout bie 't wösje vanne pagina",
'undelete-error-short' => "Fout bie 't herstelle van bestand: $1",
'undelete-error-long' => "d'r Zeen foute opgetraeje bie 't herstelle van 't bestand:

$1",
'undelete-show-file-confirm' => 'Wèt se zeker det se \'n gewösjdje versie ven \'t bestandj "<nowiki>$1</nowiki>" ven $2 óm $3 wils bekieke?',
'undelete-show-file-submit' => 'Jao',

# Namespace form on various pages
'namespace' => 'Naamruumde:',
'invert' => 'Ómgedriejde selectie',
'tooltip-invert' => 'Vink dit aan óm verangeringe te verberge in de geselecteerde naomruumdje (enne gekoppeldje naomruumdje wen aangevink)',
'namespace_association' => 'Gekoppeldje naomruumdje',
'tooltip-namespace_association' => 'Vink dit aan óm ouch verangere te tuine inne euverlègk- of óngerwerpnaomruumdje dae bie de geselecteerde naomruumdje heurt',
'blanknamespace' => '(hoofnaamruumde)',

# Contributions
'contributions' => 'Biedrages per gebroeker',
'contributions-title' => 'Biedrage van $1',
'mycontris' => 'Mien biedrage',
'contribsub2' => 'Veur $1 ($2)',
'nocontribs' => 'Gein wijzigingen gevonden die aan de gestelde criteria voldoen.',
'uctop' => '(lèste verangering)',
'month' => 'Van maond (en ierder):',
'year' => 'Van jaor (en ierder):',

'sp-contributions-newbies' => 'Tuin allein de biedrage van nuuj gebroekers',
'sp-contributions-newbies-sub' => 'Veur nuujelinge',
'sp-contributions-newbies-title' => 'Biedraag ven nuuj gebroekers',
'sp-contributions-blocklog' => 'Blokkeerlogbook',
'sp-contributions-deleted' => 'eweggesjafde gebroekersbiedrages',
'sp-contributions-uploads' => 'uploads',
'sp-contributions-logs' => 'logbeuk',
'sp-contributions-talk' => 'euverlèk',
'sp-contributions-userrights' => 'gebroekersrechtebeheer',
'sp-contributions-blocked-notice' => "Deze gebroeker is noe geblok.
De leste bloklogregel wuuertj hiejónger t'r raodpleging gegaeve:",
'sp-contributions-blocked-notice-anon' => "Dit IP-adres is noe geblok.
De leste bloklogregel wuuertj hiejónger t'r raodpleging gegaeve:",
'sp-contributions-search' => 'Zeuke nao biedrages',
'sp-contributions-username' => 'IP-adres of gebroekersnaam:',
'sp-contributions-toponly' => 'Nör nuujste versies getuundj',
'sp-contributions-submit' => 'Zeuk',

# What links here
'whatlinkshere' => 'Links nao dees pagina',
'whatlinkshere-title' => 'Pagina\'s die verwieze nao "$1"',
'whatlinkshere-page' => 'Pagina:',
'linkshere' => "De volgende pagina's verwieze nao '''[[:$1]]''':",
'nolinkshere' => "D'r zint gein pazjena's mit links nao '''[[:$1]]''' haer.",
'nolinkshere-ns' => "Geine inkele pazjena link nao '''[[:$1]]''' in de gekaoze naamruumde.",
'isredirect' => 'redirect pagina',
'istemplate' => 'ingevoog es sjabloon',
'isimage' => 'bestandjslink',
'whatlinkshere-prev' => '{{PLURAL:$1|veurige|veurige $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|volgende|volgende $1}}',
'whatlinkshere-links' => '← verwiezinge nao dees pagina',
'whatlinkshere-hideredirs' => '$1 redirects',
'whatlinkshere-hidetrans' => '$1 transclusies',
'whatlinkshere-hidelinks' => '$1 links',
'whatlinkshere-hideimages' => '$1 bestandjslinker',
'whatlinkshere-filters' => 'Filters',

# Block/unblock
'autoblockid' => 'Autoblock #$1',
'block' => 'Blok gebroeker',
'unblock' => 'Deblokkeer IP adres',
'blockip' => 'Blokkeer dit IP-adres',
'blockip-title' => 'Blok gebroeker',
'blockip-legend' => "'ne Gebroeker of IP-adres blokkere",
'blockiptext' => "Gebroek 't óngerstjaondj formeleer óm sjrieftoegank van e zeker IP-adres te verbeje. Dit maag allein gedaon weure om vandalisme te veurkómme en in euvereinkóms mitte [[{{MediaWiki:Policy-url}}|beleid]]. Gaef hiejónger de raeje óp (bv. inkel vandaliseerdje paazjes).",
'ipadressorusername' => 'IP-adres of gebroekersnaam',
'ipbexpiry' => "Verlöp (maak 'n keuze)",
'ipbreason' => 'Reeje:',
'ipbreasonotherlist' => 'Angere reje',
'ipbreason-dropdown' => '*Väöl veurkommende rejer veur blokkaazjes
** Foutieve informatie inveure
** Verwiedere van informatie oet artikele
** Spamlinks nao externe websites
** Inveuge van nonsens in artikele
** Intimiderend gedraag
** Misbroek van meerdere gebroekers
** Onacceptabele gebroekersnaam',
'ipb-hardblock' => 'Veurkóm det aangemèldje gebroekers vanaaf dit IP-adres kinne bewèrke',
'ipbcreateaccount' => 'Blokkeer aanmake gebroekers',
'ipbemailban' => "Haoj de gebrorker van 't sture van e-mail",
'ipbenableautoblock' => 'Automatisch de IP-adresse van deze gebroeker blokkere',
'ipbsubmit' => 'Blokkeer dit IP-adres',
'ipbother' => 'Anger verloup',
'ipboptions' => '2 oer:2 hours,1 daag:1 day,3 daag:3 days,1 waek:1 week,2 waek:2 weeks,1 maondj:1 month,3 maondj:3 months,6 maondj:6 months,1 jaor:1 year,veur iwweg:infinite',
'ipbotheroption' => 'anger verloup',
'ipbotherreason' => 'Angere/eventuele rejer:',
'ipbhidename' => 'Verberg gebroekersnaam van liester èn bewèrkinger',
'ipbwatchuser' => 'Gebroekerspazjena en euverlèkpazjena op vólglies plaatse',
'ipb-disableusertalk' => 'Veurkóm det deze gebroeker tiedes de blok de eige euverlègkpagina kin bewirke',
'ipb-change-block' => 'De gebroeker opnuuj blokke met deze instellinge',
'ipb-confirm' => 'Bevestig blok',
'badipaddress' => "'t IP-adres haet 'n ongeldige opmaak.",
'blockipsuccesssub' => 'Blokkaad gelök',
'blockipsuccesstext' => '[[Special:Contributions/$1|$1]] is geblokkeerd.<br />
Zuug de [[Special:BlockList|lies van geblokkeerde IP-adresse]].',
'ipb-blockingself' => "Doe steis óp 't pöntj dichzelf te blokke! Wèts se zeker desse det wils doon?",
'ipb-confirmhideuser' => "Doe steis óp 't pöntj 'ne verbórge gebroeker te blokke. Hieveur waere gebroekersname in alle liesre en logbookregels verbórge. Wèts se zeker desse door wils gaon?",
'ipb-edit-dropdown' => 'Bewerk lies van rejer',
'ipb-unblock-addr' => 'Ónblokkeer $1',
'ipb-unblock' => "Ónblokkeer 'ne gebroeker of IP-adres",
'ipb-blocklist' => 'Bekiek bestaonde blokkades',
'ipb-blocklist-contribs' => 'Biedraag ven $1',
'unblockip' => 'Deblokkeer IP adres',
'unblockiptext' => 'Gebroek het ongersjtaonde formeleer om weer sjrieftoegang te gaeve aan e geblokkierd IP adres.',
'ipusubmit' => 'Deblokkeer dit.',
'unblocked' => 'Blokkade van [[User:$1|$1]] is opgeheve',
'unblocked-range' => '$1 is gedeblokkeerd',
'unblocked-id' => 'Blokkade $1 is opgeheve',
'blocklist' => 'Geblokkeerde gebroekers',
'ipblocklist' => 'Geblokkerde gebroekers',
'ipblocklist-legend' => "'ne Geblokkeerde gebroeker zeuke",
'blocklist-userblocks' => 'Verberg geblokkeerde gebroekers',
'blocklist-tempblocks' => 'Verberg tiedelike bloks',
'blocklist-addressblocks' => 'Verberg bloks van ei IP-adres',
'blocklist-rangeblocks' => 'Verberg IP-adresbloks',
'blocklist-timestamp' => 'Tied',
'blocklist-target' => 'Doel',
'blocklist-expiry' => 'Verlöp',
'blocklist-by' => 'Geblok door',
'blocklist-params' => 'Blokparamaeters',
'blocklist-reason' => 'Reje',
'ipblocklist-submit' => 'Zeuk',
'ipblocklist-localblock' => 'Lokale blok',
'ipblocklist-otherblocks' => 'Anger {{PLURAL:$1|blokkaad|blokkades}}',
'infiniteblock' => 'veur iwweg',
'expiringblock' => 'verlöp op $1 óm $2',
'anononlyblock' => 'allein anoniem',
'noautoblockblock' => 'autoblok neet actief',
'createaccountblock' => 'aanmake gebroekers geblokkeerd',
'emailblock' => 'e-mail geblokkeerd',
'blocklist-nousertalk' => 'kan eige euverlèkpagina neet bewèrke',
'ipblocklist-empty' => 'De blokkeerlies is laeg.',
'ipblocklist-no-results' => 'Dit IP-adres of deze gebroekersnaam is neet geblokkeerd.',
'blocklink' => 'Blokkeer',
'unblocklink' => 'óntblók',
'change-blocklink' => 'blokkaasj verangere',
'contribslink' => 'biedrages',
'emaillink' => 'sjik mail',
'autoblocker' => 'Ómdets te \'n IP-adres deils mit "[[User:$1|$1]]" (geblokkeerd mit raeje "$2") bis te automatisch geblokkeerd.',
'blocklogpage' => 'Blokkeerlogbook',
'blocklog-showlog' => "Dizze gebroeker is eerder geblokkeerd gewaes.
't Blokkeerlogbook wört hiejonger weergaeve:",
'blocklog-showsuppresslog' => "Deze gebroeker is al geblok gewaes en d'r zeen (deil van) bewerkinge van deze gebroeker verbórge. 't Verbèrgingslogbook steit hieónger:",
'blocklogentry' => '"[[$1]]" is geblokkeerd veur d\'n tied van $2 $3',
'reblock-logentry' => 'haet de instellinge veur de blokkaasj veur [[$1]] gewiezig. Deze verlöp noe op $2 om $3',
'blocklogtext' => "Dit is 'n log van blokkades van gebroekers. Automatisch geblokkeerde IP-adresse sjtoon hie neet bie. Zuug de [[Special:BlockList|Lies van geblokkeerde adresse]] veur de lies van op dit mement wèrkende blokkades.",
'unblocklogentry' => 'blokkade van $1 opgeheve',
'block-log-flags-anononly' => 'allein anoniem',
'block-log-flags-nocreate' => 'aanmake gebroekers geblokkeerd',
'block-log-flags-noautoblock' => 'autoblok ongedaon gemaak',
'block-log-flags-noemail' => 'e-mail geblokkeerd',
'block-log-flags-nousertalk' => 'kan eige euverlèkpagina neet bewèrke',
'block-log-flags-angry-autoblock' => 'oetgebreide automatische blokkade ingesjakeld',
'block-log-flags-hiddenname' => 'gebroeker verbórge',
'range_block_disabled' => "De meugelikheid veur beheerders om 'n gróp IP-adresse te blokkere is oetgesjakeld.",
'ipb_expiry_invalid' => 'Ongeldig verloup.',
'ipb_expiry_temp' => 'Blokkaasj veur verbórge gebroekers mótte permanent zeen.',
'ipb_hide_invalid' => "Kinne gebroeker neet verbèrge; d'r haet te väöl angeringe.",
'ipb_already_blocked' => '"$1" is al geblokkeerd',
'ipb-needreblock' => '$1 is al geblokkeerd.
Wils se de instellinge wiezige?',
'ipb-otherblocks-header' => 'Anger {{PLURAL:$1|blokkaad|blokkades}}',
'unblock-hideuser' => 'Doe kins deze gebroeker neet óntblokke, ómdet de gebroekersnaam verbórge is.',
'ipb_cant_unblock' => 'Fout: Blokkadenummer $1 neet gevonje. Mesjiens is de blokkade al opgeheve.',
'ipb_blocked_as_range' => "Fout: 't IP-adres $1 is neet direct geblokkeerd en de blokkade kan neet opgeheve waere. De blokkade is ongerdeil van de reeks $2, wovan de blokkade waal opgeheve kan waere.",
'ip_range_invalid' => 'Ongeldige IP-reeks',
'ip_range_toolarge' => 'Reeksblokkades groeater es /$1 kènne neet.',
'proxyblocker' => 'Proxyblokker',
'proxyblockreason' => "Dien IP-adres is geblokkeerd ómdat 't 'n aope proxy is. Contacteer estebleef diene internet service provider of technische óngersjteuning en informeer ze euver dit serjeus veiligheidsprebleem.",
'sorbsreason' => 'Dien IP-adres is opgenaome in de DNS-blacklist es open proxyserver, dae {{SITENAME}} gebroek.',
'sorbs_create_account_reason' => 'Dien IP-adres is opgenómme in de DNS-blacklist es open proxyserver, dae {{SITENAME}} gebroek. De kèns gein gebroekersaccount aanmake.',
'cant-block-while-blocked' => 'De kins anger gebroekers neet blokkere terwiel se zelf geblokkeerd bös.',
'cant-see-hidden-user' => 'De gebroeker dae se perbeers te blokke is al geblok en verbórge.
Ómdes se \'t rèch "hideuser" neet höbs, kèns se de blok neet bekieke of bewerke.',
'ipbblocked' => 'Doe kèns gein anger gebroekers (ónt)blokke, ómdet se zèlf geblók bös.',
'ipbnounblockself' => 'Doe moogs dichzèlf neet óntblokke.',

# Developer tools
'lockdb' => 'Database blokkere',
'unlockdb' => 'Deblokkeer de database',
'lockdbtext' => "Waarsjoewing: De database blokkere haet 't gevolg dat nemes nog pazjena's kint bewirke, veurkäöre kint verangere of get angers kint doon woeveur d'r verangeringe in de database nudig zint.",
'unlockdbtext' => "Het de-blokkeren van de database zal de gebroekers de mogelijkheid geven om wijzigingen aan pagina's op te slaan, hun voorkeuren te wijzigen en alle andere bewerkingen waarvoor er wijzigingen in de database nodig zijn. Is dit inderdaad wat u wilt doen?.",
'lockconfirm' => 'Jao, ich wil de database blokkere.',
'unlockconfirm' => 'Ja, ik wil de database de-blokkeren.',
'lockbtn' => 'Database blokkere',
'unlockbtn' => 'Deblokkeer de database',
'locknoconfirm' => "De höbs 't vekske neet aangevink om dien keuze te bevèstige.",
'lockdbsuccesssub' => 'Blokkering database succesvol',
'unlockdbsuccesssub' => 'Blokkering van de database opgeheven',
'lockdbsuccesstext' => "De database is geblokkeerd.<br />

Vergaet neet de database opnuuj te [[Special:UnlockDB|deblokkere]] wens te klaor bis mit 't óngerhaud.",
'unlockdbsuccesstext' => 'Blokkering van de database van {{SITENAME}} is opgeheven.',
'lockfilenotwritable' => "Gein sjriefrechte op 't databaselockbestandj. Om de database te kinne blokkere of vrie te gaeve, dient de webserver sjriefrechte op dit bestandj te höbbe.",
'databasenotlocked' => 'De database is neet geblokkeerd.',
'lockedbyandtime' => '(door $1 óm $3 op $2)',

# Move page
'move-page' => '"$1" hernömme',
'move-page-legend' => 'Verplaats pazjena',
'movepagetext' => "Mit 't óngersjtaond formuleer kans te 'n pagina verplaatse. 
De historie van de auw pagina zal nao de nuuj mitgoon. 
De auwe titel zal automatisch 'ne redirect nao de nuuj pagina waere. 
Verwiezinge nao de auw pagina waere neet aangepas.
De pagina's die doorverwieze  nao de oersjprunkelike paginanaom weurt otomatisch biegewirk.
Es dat neet gewunsj is, controleer dan of d'r gein [[Special:DoubleRedirects|dobbel]] of [[Special:BrokenRedirects|gebraoke redirects]] ontsjtange zien.

Doe kans 'n pagina allein verplaatse, es gein pagina besjteit mit de nuje naam, of es op die pagina allein 'ne redirect zónger historie sjteit.

'''Waarsjoewing!'''
Veur väöl bekeke pagina's ken 't verplaatse drastische en onveurzene gevolge höbbe.
Zörg deveur dets te die gevolge euverzuus ierdets te dees hanjeling oetvoers.",
'movepagetext-noredirectfixer' => "Mit 't óngersjtaond formuleer kans te 'n pagina verplaatse. De historie van de auw pagina zal nao de nuuj mitgoon. 
De auwe titel zal automatisch 'ne redirect nao de nuuj pagina waere. 
Controleer den of d'r gein [[Special:DoubleRedirects|dobbel]] of [[Special:BrokenRedirects|gebraoke redirects]] ontsjtange zien.

Doe kans 'n pagina '''allein''' verplaatse, es gein pagina besjteit mit de nuje naam, of es op die pagina allein 'ne redirect zónger historie sjteit. Doe kins dus 's pagina die abusievelik verplaats is, trökverplaatse en 'n bestaondje pagina neet euversjrieve.

'''Waarsjoewing!'''
Veur väöl bekeke pagina's ken 't verplaatse drastische en onveurzene gevolge höbbe.
Zörg deveur dets te die gevolge euverzuus ierdets te dees hanjeling oetvoers.",
'movepagetalktext' => "De biebehurende euverlèkpagina weurt ouch verplaats, mer '''neet''' in de volgende gevalle:
* es al 'n euverlèkpagina besjteit ónger de angere naam
* es doe 't óngersjtaond vekske neet aanvinks",
'movearticle' => 'Verplaats pagina:',
'moveuserpage-warning' => "'''Waorsjoewing:''' doe geis 'ne gebroekerspagina hernömme.
Haaj d'r raekening mit det allein de pagina wuuertj hernömp, ''neet'' de gebroeker.",
'movenologin' => 'Neet aangemèld',
'movenologintext' => "Veur 't verplaatse van 'n pagina mos te zien [[Special:UserLogin|aangemèld]].",
'movenotallowed' => "De kèns gein pazjena's verplaatse.",
'movenotallowedfile' => 'De höbs gein rechte om bestenj te hernömme.',
'cant-move-user-page' => "De höbs gein rechte om gebroekerspagina's te hernömme.",
'cant-move-to-user-page' => "De höbs gein rechte om 'n pagina nao 'n gebroekerspagina te hernömme. Hernömme nao 'n subpagina is waal meugelik.",
'newtitle' => 'Nao de nuje titel',
'move-watch' => 'Volg dees pagina',
'movepagebtn' => 'Verplaats pagina',
'pagemovedsub' => 'De verplaatsing is gelök',
'movepage-moved' => '\'\'\'"$1" is verplaats nao "$2"\'\'\'',
'movepage-moved-redirect' => "d'r Is 'n doorverwiezing aongemaak.",
'movepage-moved-noredirect' => "d'r Is gein doorverwiezing aongemaak.",
'articleexists' => "Dao is al 'n pagina mit deze titel of de titel is óngeljig. <br />Kees estebleef 'ne angere titel.",
'cantmove-titleprotected' => "De kèns gein pazjena nao deze titel herneume, ómdet de nuje titel beveilig is taege 't aanmake d'rvan.",
'talkexists' => "De pagina zelf is verplaats, meh de euverlèkpagina kós neet verplaats waere, ómdet d'r al 'n euverlèkpagina mit de nuje titel besjtóng. Combineer de euverlèkpagina's estebleef mit de hand.",
'movedto' => 'verplaats nao',
'movetalk' => 'Verplaats de euverlèkpagina ouch.',
'move-subpages' => "Herneum subpagina's (maximaal $1)",
'move-talk-subpages' => "Herneum subpagina's van euverlèkpagina's (maximaal $1)",
'movepage-page-exists' => 'De pazjena $1 besteit al en kan neet automatisch gewis waere.',
'movepage-page-moved' => 'De pazjena $1 is hernömp nao $2.',
'movepage-page-unmoved' => 'De pazjena $1 kós neet hernömp waere nao $2.',
'movepage-max-pages' => "'t Maximaal aantal automatisch te hernömme pazjena's is bereik ({{PLURAL:$1|$1|$1}}). De euverige pazjena's waere neet automatisch hernömp.",
'movelogpage' => "Logbook verplaatsde pagina's",
'movelogpagetext' => "Dit is de lies van verplaatsde pazjena's.",
'movesubpage' => "{{PLURAL:$1|Subpaasj|Subpazjena's}}",
'movesubpagetext' => "De {{PLURAL:$1|subpaasj|$1 subpazjena's}} ven deze paasj {{PLURAL:$1|wörd|waere}} hie ónger getuundj.",
'movenosubpage' => "Deze pagina haet gein subpagina's.",
'movereason' => 'Reeje:',
'revertmove' => 'trökdrieje',
'delete_and_move' => 'Wis en verplaats',
'delete_and_move_text' => '==Wisse vereis==

De doeltitel "[[:$1]]" besjteit al. Wils te dit artikel wisse óm ruumde te make veur de verplaatsing?',
'delete_and_move_confirm' => 'Jao, wis de pazjena',
'delete_and_move_reason' => 'Gewis óm artikel [[$1]] te kónne verplaatse',
'selfmove' => "De kèns 'n pazjena neet verplaatse nao dezelfde paginanaam.",
'immobile-source-namespace' => 'Pagina\'s in de naamruumde "$1" kinne nwet hernump waere',
'immobile-target-namespace' => 'Pagina\'s kinne neet hernömp waere nao de naamruumde "$1"',
'immobile-target-namespace-iw' => "'n Interwikiverwiezing is gein geldige bestumming veur 't hernömme van 'n pagina.",
'immobile-source-page' => 'Deze pagina kin neet hernömp waere.',
'immobile-target-page' => "'t Is neet meugelik te hernömmen nao die paginanaam.",
'imagenocrossnamespace' => "'n Mediabestand kin neet nao 'n anger naamruumde verplaats waere",
'nonfile-cannot-move-to-file' => 'Kèn gein neet-bestenj nao bestenj verplaatse.',
'imagetypemismatch' => "De nuje bestandjsextensie is neet gliek aan 't bestandjstype.",
'imageinvalidfilename' => 'De nuje bestandsnaam is ongeldig',
'fix-double-redirects' => 'Alle doorverwiezinge biewerke die verwieze nao de originele paginanaam',
'move-leave-redirect' => "'n Doorverwiezing achterlaote",
'protectedpagemovewarning' => "'''Waorsjoewing: Dees pazjena is besjermp zoedat ze allein doer gebroekers mit administratorrechte kint weure verplaats.'''
De lèste logbookregel steit hierónger:",
'semiprotectedpagemovewarning' => "'''Let op:''' Dees pazjena is beveilig en kin allein door geregistreerde gebroekers verplaats waere.
De lèste logbookregel steit hiejónger:",
'move-over-sharedrepo' => "== 't Bestandj besteit al ==
[[:$1]] besteit al in 'ne gedeildje mediadatabank.
E bestandj hiehaer verplaatse euversjrief 't gedeildj bestandj.",
'file-exists-sharedrepo' => "Deze bestandjsnaam besteit al in 'ne gedeildje mediadatabank.
Kees 'nen angere bestandjsnaam.",

# Export
'export' => "Exporteer pagina's",
'exporttext' => "De kèns de teks en historie van 'n pazjena of van pazjena's exportere (oetveure) nao XML. Dit exportbestandj is daonao te importere (inveure) in 'ne angere MediaWiki mit de [[Special:Import|importpazjena]]. (dèks is hie importrech veur nudig)

Gaef in 't óngersjtaonde veldj de name van de te exportere pazjena's op, ein pazjena per regel, en gaef aan ofs te alle versies mit de bewerkingssamevatting of allein de hujige versies mit de bewirkingssamevatting wils exportere.

In 't letste geval kèns te ouch 'ne link gebroeken, bieveurbild [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] veur de pazjena \"[[{{MediaWiki:Mainpage}}]]\".",
'exportall' => "Exporteer alle pagina's",
'exportcuronly' => 'Allein de letste versie, neet de volledige gesjiedenis',
'exportnohistory' => "----
'''Let op:''' 't exportere van de ganse gesjiedenis is oetgezat waeges prestatieree.",
'exportlistauthors' => "Nöm 'n vól auteurslies op veur eder paasj",
'export-submit' => 'Exportere',
'export-addcattext' => "Voeg pagina's toe van categorie:",
'export-addcat' => 'Toevoege',
'export-addnstext' => 'Pazjes oet de volgende naamruumde toevoge:',
'export-addns' => 'Haeraanvoge',
'export-download' => 'Es bestandj opslaon',
'export-templates' => 'Sjablone toevoge',
'export-pagelinks' => "Pazjena's wonao verweze wuuerdj toevoege tot 'n deepdje ven:",

# Namespace 8 related
'allmessages' => 'Alle systeemberichte',
'allmessagesname' => 'Naam',
'allmessagesdefault' => 'Obligaten teks',
'allmessagescurrent' => 'Hujige teks',
'allmessagestext' => "Dit is 'n lies van alle systeemberichte besjikbaar in de MediaWiki-naamruumde.
Bezeuk [//www.mediawiki.org/wiki/Localisation MediaWiki-lokalisatie] en [//translatewiki.net translatewiki.net] es doe wils biedrage aon lokalisatie.",
'allmessagesnotsupportedDB' => "Deze pagina kan neet gebroek waere omdet '''\$wgUseDatabaseMessages''' oet steit.",
'allmessages-filter-legend' => 'Filter',
'allmessages-filter' => 'Filter óp aangepas:',
'allmessages-filter-unmodified' => 'Ónaangepas',
'allmessages-filter-all' => 'Al',
'allmessages-filter-modified' => 'Aangepas',
'allmessages-prefix' => 'Filter óp veurvoging:',
'allmessages-language' => 'Spraok:',
'allmessages-filter-submit' => 'Gank',

# Thumbnails
'thumbnail-more' => 'Vergroete',
'filemissing' => 'Besjtand ontbrik',
'thumbnail_error' => "Fout bie 't aanmake van thumbnail: $1",
'djvu_page_error' => 'DjVu-pagina boete bereik',
'djvu_no_xml' => "De XML veur 't DjVu-bestandj kos neet opgehaald waere",
'thumbnail-temp-create' => "'t Waar neet meugelik e tiejelik miniatuurbestandj aan te make.",
'thumbnail-dest-create' => "'t Waar neet meugelik 't miniatuurbestandj óp de doellocatie óp te slaon.",
'thumbnail_invalid_params' => 'Onzjuste thumbnailparamaetere',
'thumbnail_dest_directory' => 'Neet in staat doel directory aan te make',
'thumbnail_image-type' => 'Dit besjtandjstype waert neet ongersjteund',
'thumbnail_gd-library' => 'De insjtèllinge veur de GD-bibliotheek zeen incompleet. De functie $1 waert gemis',
'thumbnail_image-missing' => "'t Besjtandj liek neet aanwezig te zeen: $1",

# Special:Import
'import' => "Pazjena's importere",
'importinterwiki' => 'Transwiki-import',
'import-interwiki-text' => "Selecteer 'ne wiki en pazjenanaam om te importere.
Versie- en auteursgegaeves blieve hiej bie bewaard.
Alle transwiki-importhanjelinge waere opgeslage in 't [[Special:Log/import|importlogbook]].",
'import-interwiki-source' => 'Bronwiki/pagina:',
'import-interwiki-history' => 'Volledige gesjiedenis van deze pazjena ouch kopiëre',
'import-interwiki-templates' => 'Alle sjablone opnaeme',
'import-interwiki-submit' => 'Importere',
'import-interwiki-namespace' => 'Doelnaamruumdje:',
'import-upload-filename' => 'Bestandjsnaam:',
'import-comment' => 'Opmèrking:',
'importtext' => 'Gebroek de functie [[Special:Export|export]] in de wiki wo de informatie vanaaf kömp. 
Slaon de oetveur op dien eige systeem op, en voeg dae dao nao hiej toe.',
'importstart' => "Pazjena's aan 't importere ...",
'import-revision-count' => '$1 {{PLURAL:$1|versie|versies}}',
'importnopages' => "Gein pazjena's te importere.",
'imported-log-entries' => '$1 {{PLURAL:$1|logbookregel|logbookregele}} ingeveurdj.',
'importfailed' => 'Import is misluk: $1',
'importunknownsource' => 'Ónbekindj importbróntype',
'importcantopen' => "Kós 't importbestandj neet äöpene",
'importbadinterwiki' => 'Verkeerde interwikilink',
'importnotext' => 'Laeg of geine teks',
'importsuccess' => 'Import geslaag!',
'importhistoryconflict' => "d'r Zeen conflicte in de gesjiedenis van de pazjena (is mesjiens eerder geïmporteerd)",
'importnosources' => "d'r Zeen gein transwiki-importbrónne gedefinieerd en directe gesjiedenis-uploads zeen oetgezat.",
'importnofile' => "d'r Is gein importbestandj geüpload.",
'importuploaderrorsize' => "Upload van 't importbestandj is misluk. 't Bestand is groter es de ingesteldje limiet.",
'importuploaderrorpartial' => "Upload van 't importbestandj is misluk. 't Bestandj is slechs gedeiltelik aangekómme.",
'importuploaderrortemp' => "Upload van 't importbestandj is misluk. De tiedelike map is neet aanwezig.",
'import-parse-failure' => "Fout bie 't verwerke van de XML-import",
'import-noarticle' => "d'r Zeen gein importeerbaar pazjena's!",
'import-nonewrevisions' => 'Alle versies zeen al eerder geïmporteerd.',
'xml-error-string' => '$1 op regel $2, kolom $3 (byte $4): $5',
'import-upload' => 'XML-gegaeves uploade',
'import-token-mismatch' => "De sessiegegaeves zeen verlaore gegange. Perbeer 't opnuuj.",
'import-invalid-interwiki' => "'t Is neet mäögelik van de aangegeve wiki te importere.",
'import-error-edit' => 'De pagina "$1" is neet geïmporteerd omdes se neet de rechte hes óm dees te bewèrke.',
'import-error-create' => 'De pagina "$1" is neet geïmporteerd omdes se neet de rechte hes óm dees aan te make.',
'import-error-interwiki' => 'De pagina "$1" is neet geïmporteerd omdet deze naam is gereserveerd veur extern verwiezinge (interwiki).',
'import-error-special' => 'Pagina "$1" is neet geïmporteerd omdet deze is geplaats in \'n speciaal naamruumdje wo gein pagina\'s in geplaats kinne waere.',
'import-error-invalid' => 'De pagina" "$1" is neet geïmporteerd omdet de naam óngeljig is.',

# Import log
'importlogpage' => 'Importlogbook',
'importlogpagetext' => "Administratieve import van pazjena's mit gesjiedenis van anger wiki's.",
'import-logentry-upload' => "[[$1]] geïmporteerd via 'ne bestandjsupload",
'import-logentry-upload-detail' => '$1 {{PLURAL:$1|versie|versies}}',
'import-logentry-interwiki' => 'transwiki veur $1 geslaag',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|versie|versies}} van $2',

# JavaScriptTest
'javascripttest' => 'Tes JavaScript',
'javascripttest-title' => 'Veur tes oet veur $1',
'javascripttest-pagetext-noframework' => "Dees pagina is gerizzerveerd veur 't oetveure van JavaScriptteste.",
'javascripttest-pagetext-unknownframework' => 'Ónbekèndje testframework "$1".',
'javascripttest-pagetext-frameworks' => 'Kees ein vanne volgende tesframeworks: $1',
'javascripttest-pagetext-skins' => "Kees 'n oeterlik óm de teste op te laote loupe:",
'javascripttest-qunit-intro' => 'Zuuch de [$1 tesdocumentatie] op mediawiki.org.',
'javascripttest-qunit-heading' => 'QUnit tessuite veur MediaWiki JavaScript',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Dien gebroekerspagina',
'tooltip-pt-anonuserpage' => 'De gebroekerspazjena veur dit IP adres',
'tooltip-pt-mytalk' => 'Dien euverlèkpagina',
'tooltip-pt-anontalk' => 'Euverlèk euver verangeringe doer dit IP addres',
'tooltip-pt-preferences' => 'Mien veurkäöre',
'tooltip-pt-watchlist' => "De lies van gevolgde pagina's.",
'tooltip-pt-mycontris' => 'Lies van dien biedrage',
'tooltip-pt-login' => "De weurs aangemeudig om d'ch aan te melje, meh 't is neet verplich.",
'tooltip-pt-anonlogin' => 'De weurs aangemodigd om in te logge, meh t is neet verplich.',
'tooltip-pt-logout' => 'Aafmelde',
'tooltip-ca-talk' => 'Euverlèk euver dit artikel',
'tooltip-ca-edit' => 'De kins dees pagina verangere.',
'tooltip-ca-addsection' => "Begin 'n nuuj sectie",
'tooltip-ca-viewsource' => 'Dees pagina is besjirmp. De kins häör brontèks betrachte.',
'tooltip-ca-history' => 'Auw versies van dees pagina.',
'tooltip-ca-protect' => 'Besjirm dees pagina',
'tooltip-ca-unprotect' => 'Veranger de beveiliging van dees pagina',
'tooltip-ca-delete' => 'Sjaf dees pagina eweg',
'tooltip-ca-undelete' => 'Hersjtèl de verangeringe van dees pazjena van veurdat ze gewist woerd',
'tooltip-ca-move' => 'Verplaats dees pagina',
'tooltip-ca-watch' => 'Dees pagina bie bie dien volglies zètte',
'tooltip-ca-unwatch' => 'Dees pagina van dien volglies ewegsjaffe',
'tooltip-search' => 'Doorzeuk {{SITENAME}}',
'tooltip-search-go' => "Gank nao 'n pagina mit dezelfde naam es die besjteit",
'tooltip-search-fulltext' => "Zeuk de pagina's veur dees teks",
'tooltip-p-logo' => 'Veurblaad',
'tooltip-n-mainpage' => "Bezeuk 't Veurblaad",
'tooltip-n-mainpage-description' => "Bezeuk 't Veurblaad",
'tooltip-n-portal' => "Euver 't projek, wats te kins doon, woes te dinger kins vènje",
'tooltip-n-currentevents' => "Achtergrondinfo van 't nuujs",
'tooltip-n-recentchanges' => 'De lies van recènte verangeringe in de wiki.',
'tooltip-n-randompage' => "Laaj 'n willekäörige pagina",
'tooltip-n-help' => 'De plaats óm informatie euver dit projek te vènje.',
'tooltip-t-whatlinkshere' => "Lies van alle wikipagina's die hiehaer verwieze",
'tooltip-t-recentchangeslinked' => "Recènte verangeringe in pagina's woehaer dees pagina verwis",
'tooltip-feed-rss' => 'RSS feed veur dees pagina',
'tooltip-feed-atom' => 'Atom feed veur dees pagina',
'tooltip-t-contributions' => 'Bekiek de lies van contributies van dizze gebroeker',
'tooltip-t-emailuser' => 'Sjtuur inne mail noa dizze gebroeker',
'tooltip-t-upload' => 'Upload besjtande',
'tooltip-t-specialpages' => "Lies van alle speciaal pagina's",
'tooltip-t-print' => 'Printvruntelike versie van deze pagina',
'tooltip-t-permalink' => 'Permanente link nao dees versie van de pagina',
'tooltip-ca-nstab-main' => 'Betrach de pagina',
'tooltip-ca-nstab-user' => 'Bekiek de gebroekerspagina',
'tooltip-ca-nstab-media' => 'Bekiek de mediapazjena',
'tooltip-ca-nstab-special' => "Dit is 'n speciaal pagina, de kins dees pagina zelf neet bewirke",
'tooltip-ca-nstab-project' => 'Bekiek de projèkpagina',
'tooltip-ca-nstab-image' => 'Bekiek de besjtandspagina',
'tooltip-ca-nstab-mediawiki' => 'Bekiek t systeimberich',
'tooltip-ca-nstab-template' => 'Bekiek t sjabloon',
'tooltip-ca-nstab-help' => 'Bekiek de helppazjena',
'tooltip-ca-nstab-category' => 'Betrach de categoriepagina',
'tooltip-minoredit' => "Markeer dit es 'n klein verangering",
'tooltip-save' => 'Bewaar dien verangeringe',
'tooltip-preview' => 'Betrach dien verangeringe veurdets te ze definitief opsjleis!',
'tooltip-diff' => 'Betrach dien verangeringe in de teks.',
'tooltip-compareselectedversions' => 'Betrach de versjille tösje de twie geselecteerde versies van dees pagina.',
'tooltip-watch' => 'Doog dees pagina bie aan dien volglies',
'tooltip-watchlistedit-normal-submit' => "Pazjena's ewegsjaffe",
'tooltip-watchlistedit-raw-submit' => 'Volglies biewirke',
'tooltip-recreate' => 'Maak deze pagina opnuuj aan ondanks irdere verwiedering',
'tooltip-upload' => 'Uploade',
'tooltip-rollback' => 'Mit "trökdrieje" driejs doe mit eine klik de bewirking(e) trök van de lètste gebroeker dee dees pagina haet bewirk.',
'tooltip-undo' => 'Mit "óngedaon make" driejs te dees bewirking trök en koms te in \'t bewirkingsvinster.
Doe kans in de bewirkingssamevatting \'n reej opgaeve.',
'tooltip-preferences-save' => 'Slaon veurkäöre óp',
'tooltip-summary' => "Veur 'n kórte samevatting in",

# Metadata
'notacceptable' => "De wikiserver kin de gegaeves neet levere in  'ne vorm dae diene client kin laeze.",

# Attribution
'anonymous' => '{{PLURAL:$1|Anonieme gebroeker|Anoniem gebroekers}} ven {{SITENAME}}',
'siteuser' => '{{SITENAME}} gebroeker $1',
'anonuser' => 'anonieme gebroeker óp {{SITENAME}} $1',
'lastmodifiedatby' => "Dees pagina is 't lèts verangerd op $2, $1 door $3.",
'othercontribs' => 'Gebaseerd op wirk van $1.',
'others' => 'angere',
'siteusers' => '{{SITENAME}} {{PLURAL:$2|gebroeker|gebroekers}} $1',
'anonusers' => '{{PLURAL:$2|anonieme gebroeker|anoniem gebroekers}} óp {{SITENAME}} $1',
'creditspage' => 'Sjrievers van dees pazjena',
'nocredits' => "d'r Is gein auteursinformatie besjikbaar veur deze pagina.",

# Spam protection
'spamprotectiontitle' => 'Spamfilter',
'spamprotectiontext' => "De pagina daese wildes opslaon is geblokkeerd door de spamfilter.
Meistal wörd dit door 'ne zwarte externe link veroorzaak.",
'spamprotectionmatch' => "De volgende teks veroorzaakte 'n alarm van de spamfilter: $1",
'spambot_username' => 'MediaWiki spam opruming',
'spam_reverting' => 'Bezig mit trökdrèjje nao de letste versie die gein verwiezing haet nao $1',
'spam_blanking' => "Alle wieziginge mit 'ne link nao $1 waere verwiederd",
'spam_deleting' => 'Alle wieziginge hawwe links nao $1, wuuertj gewösj',

# Info page
'pageinfo-title' => 'Informatie euver "$1"',
'pageinfo-header-edits' => 'Bewirk',
'pageinfo-views' => 'Aantal waergave',
'pageinfo-watchers' => 'Aantal volgers',
'pageinfo-edits' => 'Aantal bewèrkinge',
'pageinfo-authors' => 'Aantal versjillende sjrievers',

# Skin names
'skinname-cologneblue' => 'Keuls blauw',

# Patrolling
'markaspatrolleddiff' => 'Markeer es gecontroleerd',
'markaspatrolledtext' => 'Markeer deze pagina es gecontroleerd',
'markedaspatrolled' => 'Gemarkeerd es gecontroleerd',
'markedaspatrolledtext' => 'De gekaoze versie van [[:$1]] is gemarkeerd es gecontroleerd.',
'rcpatroldisabled' => 'De controlemeugelikheid op recènte wieziginge is oetgesjakeld.',
'rcpatroldisabledtext' => 'De meugelikheid om recènte verangeringe es gecontroleerd aan te mèrke is op dit ougeblik oetgesjakeld.',
'markedaspatrollederror' => 'Kin neet es gecontroleerd waere aangemèrk',
'markedaspatrollederrortext' => "Selecteer 'ne versie om es gecontroleerd aan te mèrke.",
'markedaspatrollederror-noautopatrol' => 'De kèns dien eige verangeringe neet es gecontroleerd markere.',

# Patrol log
'patrol-log-page' => 'Markeerlogbook',
'patrol-log-header' => 'Dit logbook bevat versies die gemarkeerd zeen es gecontroleerd.',
'log-show-hide-patrol' => 'Markeerlogbook $1',

# Image deletion
'deletedrevision' => 'Aw versie $1 gewis',
'filedeleteerror-short' => "Fout biej 't wisse van bestandj: $1",
'filedeleteerror-long' => "d'r Zeen foute opgetraoje bie 't verwiedere van 't bestandj:

$1",
'filedelete-missing' => '\'t Bestandj "$1" kin neet gewis waere, ómdet \'t neet bestuit.',
'filedelete-old-unregistered' => 'De aangegaeve bestandjsversie "$1" stuit neet in de database.',
'filedelete-current-unregistered' => '\'t Aangegaeve bestandj "$1" stuit neet in de database.',
'filedelete-archive-read-only' => 'De webserver kin neet in de archiefmap "$1" sjrieve.',

# Browsing diffs
'previousdiff' => '← Veurige bewirking',
'nextdiff' => 'Volgende bewirking →',

# Media information
'mediawarning' => "'''Waorsjuwing''': Dit bestandj kin 'n anger kood höbbe, door 't te doorveure in dien systeem kin 't gecompromeerde dinger oplevere.",
'imagemaxsize' => "Meximaal aafbeildjingsaafmaeting:<br />''(veur besjrievingspaasj)''",
'thumbsize' => 'Gruutde vanne thumbnail:',
'widthheightpage' => "$1 × $2, $3 {{PLURAL:$3|pazjena|pazjena's}}",
'file-info' => 'bestandsgruutde: $1, MIME-type: $2',
'file-info-size' => '$1 × $2 pixels, besjtandjgruutde: $3, MIME-type: $4',
'file-info-size-pages' => "$1 × $2 pixels, bestandjsgreudje: $3, MIME-type: $4, $5 {{PLURAL:$5|pagina|pagina's}}",
'file-nohires' => 'Gein hogere resolutie besjikbaar.',
'svg-long-desc' => 'SVG-bestandj, nominaal $1 × $2 pixels, bestandsgruutde: $3',
'show-big-image' => 'Vol resolutie',
'show-big-image-preview' => 'Greudje van dees veurvertoening: $1.',
'show-big-image-other' => 'Anger {{PLURAL:$2|resolutie|resoluties}}: $1.',
'show-big-image-size' => '$1 × $2 pixels',
'file-info-gif-looped' => 'herhaolendj',
'file-info-gif-frames' => '$1 {{PLURAL:$1|kader|kadere}}',
'file-info-png-looped' => 'herhaolendj',
'file-info-png-repeat' => '$1 {{PLURAL:$1|kieër|kieër}} aafgespeelj',
'file-info-png-frames' => '$1 {{PLURAL:$1|kader|kadere}}',

# Special:NewFiles
'newimages' => 'Nuuj plaetjes',
'imagelisttext' => "Hie volg 'n lies mit $1 {{PLURAL:$1|aafbeilding|aafbeildinge}} geordend $2.",
'newimages-summary' => 'Op dees speciaal pazjena waere de meis recènt toegevoogde bestenj weergegaeve.',
'newimages-legend' => 'Bestandjsnaam',
'newimages-label' => 'Bestandjsnaam (of deel daarvan):',
'showhidebots' => '($1 Bots)',
'noimages' => 'Niks te zeen.',
'ilsubmit' => 'Zeuk',
'bydate' => 'op datum',
'sp-newimages-showfrom' => 'Tuin nuuj besjtande vanaaf $2, $1',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds' => '{{PLURAL:$1|$1 sekónd|$1 sekónd}}',
'minutes' => '{{PLURAL:$1|$1 menuut|$1 menuut}}',
'hours' => '{{PLURAL:$1|$1 oer|$1 oer}}',
'days' => '{{PLURAL:$1|$1 daag|$1 daag}}',
'ago' => '$1 trögk',

# Bad image list
'bad_image_list' => "De opmaak is es volg:

Allein regele in 'n lies (regele die mit * beginnen) waere verwirk. De ierste link op 'ne regel mót 'ne link zeen nao 'n óngewunsj plaetje.
Alle volgende links die op dezelfde regel sjtaon, waere behanjeld es oetzunjering, zoe wie pagina's woe-op 't plaetje in de teks opgenómme is.",

# Metadata
'metadata' => 'Metadata',
'metadata-help' => "Dit besjtand bevat aanvullende infermasie, dae door 'ne fotocamera, 'ne scanner of 'n fotobewirkingsprogramma toegeveug kin zeen. Es 't besjtand aangepas is, dan kómme details meugelik neet euverein mit 't verangerde besjtand.",
'metadata-expand' => 'Tuin oetgebreide gegaeves',
'metadata-collapse' => 'Versjtaek oetgebreide gegaeves',
'metadata-fields' => "De image-metadatavelde in dit berich waere ouch getuund op 'n afbeildingspagina es de metadatatabel is ingeklap. Anger velde waere verborge.
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
* gpsaltitude",

# Exif tags
'exif-imagewidth' => 'Breidte',
'exif-imagelength' => 'Hoogte',
'exif-bitspersample' => 'Bits per componènt',
'exif-compression' => 'Cómpressiesjema',
'exif-photometricinterpretation' => 'Pixelcompositie',
'exif-orientation' => 'Oriëntatie',
'exif-samplesperpixel' => 'Aantal componente',
'exif-planarconfiguration' => 'Gegaevesstructuur',
'exif-ycbcrsubsampling' => 'Subsampleverhajing van Y toet C',
'exif-ycbcrpositioning' => 'Y- en C-positionering',
'exif-xresolution' => 'Horizontale resolutie',
'exif-yresolution' => 'Verticale resolutie',
'exif-stripoffsets' => 'Locatie aafbeildingsgegaeves',
'exif-rowsperstrip' => 'Rie per strip',
'exif-stripbytecounts' => 'Bytes per gecomprimeerde strip',
'exif-jpeginterchangeformat' => 'Aafstandj towt JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Bytes JPEG-gegaeves',
'exif-whitepoint' => 'Witpuntchromaticiteit',
'exif-primarychromaticities' => 'Chromaticities of primaries',
'exif-ycbcrcoefficients' => 'Transformatiematrixcoëfficiënte veur de kleurruumde',
'exif-referenceblackwhite' => 'Paar zwarte en wite referentiewaarde',
'exif-datetime' => 'Datum en momènt besjtandjsverangering',
'exif-imagedescription' => 'Omsjrieving aafbeilding',
'exif-make' => 'Merk camera',
'exif-model' => 'Cameramodel',
'exif-software' => 'Gebroekdje software',
'exif-artist' => 'Auteur',
'exif-copyright' => 'Copyrighthawter',
'exif-exifversion' => 'Exif-versie',
'exif-flashpixversion' => 'Ongersteundje Flashpix-versie',
'exif-colorspace' => 'Kläörruumde',
'exif-componentsconfiguration' => 'Beteikenis van edere componènt',
'exif-compressedbitsperpixel' => 'Cómpressiemeneer bie dit plaetje',
'exif-pixelydimension' => 'Aafbeildingsbrèdje',
'exif-pixelxdimension' => 'Aafbeildingsheugdje',
'exif-usercomment' => 'Opmerkinge',
'exif-relatedsoundfile' => 'Biebeheurendj audiobestandj',
'exif-datetimeoriginal' => 'Datum en momint van verwèkking',
'exif-datetimedigitized' => 'Datum en momènt van digitizing',
'exif-subsectime' => 'Datum tied subsecond',
'exif-subsectimeoriginal' => 'Subsecond tiedstip datageneratie',
'exif-subsectimedigitized' => 'Subsecond tiedstip digitalisatie',
'exif-exposuretime' => 'Beleechtingstied',
'exif-exposuretime-format' => '$1 sec ($2)',
'exif-fnumber' => 'F-getal',
'exif-exposureprogram' => 'Beleechtingsprogramma',
'exif-spectralsensitivity' => 'Spectrale geveuligheid',
'exif-isospeedratings' => 'ISO/ASA-waarde',
'exif-shutterspeedvalue' => 'Sloetersnelheid in APEX',
'exif-aperturevalue' => 'Äöpening in APEX',
'exif-brightnessvalue' => 'Heljerheid in APEX',
'exif-exposurebiasvalue' => 'Beleechtingscompensatie',
'exif-maxaperturevalue' => 'Maximale diafragma-äöpening',
'exif-subjectdistance' => 'Objekaafstandj',
'exif-meteringmode' => 'Methode leechmaeting',
'exif-lightsource' => 'Leechbron',
'exif-flash' => 'Flitser',
'exif-focallength' => 'Brandjpuntjsaafstandj',
'exif-subjectarea' => 'Objekruumde',
'exif-flashenergy' => 'Flitssterkdje',
'exif-focalplanexresolution' => 'Brandjpuntjsvlak-X-resolutie',
'exif-focalplaneyresolution' => 'Brandjpuntjsvlak-Y-resolutie',
'exif-focalplaneresolutionunit' => 'Einheid CCD-resolutie',
'exif-subjectlocation' => 'Objekslocatie',
'exif-exposureindex' => 'Beleechtingsindex',
'exif-sensingmethod' => 'Opvangmethode',
'exif-filesource' => 'Bestandjsbron',
'exif-scenetype' => 'Saort scene',
'exif-customrendered' => 'Aangepasdje beildverwerking',
'exif-exposuremode' => 'Beleechtingsinstelling',
'exif-whitebalance' => 'Witbalans',
'exif-digitalzoomratio' => 'Digitale zoomfactor',
'exif-focallengthin35mmfilm' => 'Brandjpuntjsaafstandj (35mm-equivalent)',
'exif-scenecapturetype' => 'Saort opname',
'exif-gaincontrol' => 'Piekbeheersing',
'exif-contrast' => 'Contras',
'exif-saturation' => 'Verzaodiging',
'exif-sharpness' => 'Sjerpdje',
'exif-devicesettingdescription' => 'Besjrieving methode-opties',
'exif-subjectdistancerange' => 'Bereik objekaafstandj',
'exif-imageuniqueid' => 'Unieke ID aafbeilding',
'exif-gpsversionid' => 'GPS versienómmer',
'exif-gpslatituderef' => 'Noorder- of zuderbreidte',
'exif-gpslatitude' => 'Breidtegraod',
'exif-gpslongituderef' => 'Ooster- of westerlingdje',
'exif-gpslongitude' => 'Lingdjegraod',
'exif-gpsaltituderef' => 'Hoogdjereferentie',
'exif-gpsaltitude' => 'Hoogdje',
'exif-gpstimestamp' => 'GPS-tied (atoomklok)',
'exif-gpssatellites' => 'Gebroekdje satelliete veur maeting',
'exif-gpsstatus' => 'Ontvangerstatus',
'exif-gpsmeasuremode' => 'Maetmodus',
'exif-gpsdop' => 'Maetprontheid',
'exif-gpsspeedref' => 'Snelheid einheid',
'exif-gpsspeed' => 'Snelheid van GPS-ontvanger',
'exif-gpstrackref' => 'Referentie veur bewaegingsrichting',
'exif-gpstrack' => 'Bewaegingsrichting',
'exif-gpsimgdirectionref' => 'Referentie veur aafbeildingsrichting',
'exif-gpsimgdirection' => 'Aafbeildingsrichting',
'exif-gpsmapdatum' => 'Gebroekdje geodetische ongerzeuksgegaeves',
'exif-gpsdestlatituderef' => 'Referentie veur breidtegraod bestömming',
'exif-gpsdestlatitude' => 'Breidtegraod bestömming',
'exif-gpsdestlongituderef' => 'Referentie veur lingdjegraod bestömming',
'exif-gpsdestlongitude' => 'Lingdjegraod bestömming',
'exif-gpsdestbearingref' => 'Referentie veur richting nao bestömming',
'exif-gpsdestbearing' => 'Richting nao bestömming',
'exif-gpsdestdistanceref' => 'Referentie veur aafstandj toet bestömming',
'exif-gpsdestdistance' => 'Aafstandj toet bestömming',
'exif-gpsprocessingmethod' => 'GPS-verwerkingsmethode',
'exif-gpsareainformation' => 'Naam GPS-gebied',
'exif-gpsdatestamp' => 'GPS-datum',
'exif-gpsdifferential' => 'Differentiële GPS-correctie',
'exif-jpegfilecomment' => 'Opmirking bie JPEG-bestandj',
'exif-keywords' => 'Trefweurd',
'exif-worldregioncreated' => 'Regio vanne welt wo de aafbeilding gemaak is',
'exif-countrycreated' => 'Landj wo de aafbeilding gemaak is',
'exif-countrycodecreated' => 'Landjcode van wo de aafbeilding gemaak is',
'exif-provinceorstatecreated' => 'Provincie of staat wo de aafbeilding gemaak is',
'exif-citycreated' => 'Sjtad wo de aafbeilding gemaak is',
'exif-sublocationcreated' => 'Wiek vanne plaats wo de aafbeilding gemaak is',
'exif-worldregiondest' => 'Getuinde weltregio',
'exif-countrydest' => 'Getuind landj',
'exif-countrycodedest' => "Landjcode van 't getuind",
'exif-provinceorstatedest' => 'Getuinde provincie of staat',
'exif-citydest' => 'Getuinde sjtad',
'exif-sublocationdest' => 'Getuinde wiek vanne sjtad',
'exif-objectname' => 'Kórte naam',
'exif-specialinstructions' => 'Speciaal instructies',
'exif-headline' => 'Kópteks',
'exif-credit' => 'Credit/Leveranceer',
'exif-source' => 'Brón',
'exif-editstatus' => 'Bewirkingsstaat vanne aafbeilding',
'exif-urgency' => 'Urgensie',
'exif-fixtureidentifier' => 'Groepsnaam',
'exif-locationdest' => 'Getuinde locatie',
'exif-locationdestcode' => "Locatiecode van 't getuind",
'exif-objectcycle' => 'Tied vannen daag wo de media veur gemèndj is',
'exif-contact' => 'Kóntakgegaeves',
'exif-writer' => 'Sjriever',
'exif-languagecode' => 'Sjpraok',
'exif-iimversion' => 'IIM-versie',
'exif-iimcategory' => 'Categorie',
'exif-iimsupplementalcategory' => 'Aanvöllendje categorië',
'exif-datetimeexpires' => 'Neet te broeke nao',
'exif-datetimereleased' => 'Gepubliceerd óp',
'exif-originaltransmissionref' => 'Originele taaklocatiecode',
'exif-identifier' => 'Id',
'exif-lens' => 'Gebroekdje laens',
'exif-serialnumber' => 'Serienommer vanne camera',
'exif-cameraownername' => 'Eigeneer vanne camera',
'exif-label' => 'Label',
'exif-datetimemetadata' => "Datum woróp de metadata 't lets is bewirk",
'exif-nickname' => 'Informele naam vanne aafbeilding',
'exif-rating' => 'Werdering (sjaol van 5)',
'exif-rightscertificate' => 'Rechtebehiercertifikaot',
'exif-copyrighted' => 'Auteursrechtestaat',
'exif-copyrightowner' => 'Copyrighthawter',
'exif-usageterms' => 'Gebroekersveurwaerd',
'exif-webstatement' => 'Online copyrightverklaoring',
'exif-originaldocumentid' => "Unieke ID van 't origineel dokement",
'exif-licenseurl' => 'URL veur copyrightlicensie',
'exif-morepermissionsurl' => 'Alternatief licensiegegaeves',
'exif-attributionurl' => 'Gebroek de volgende verwiezing bie hergebroek van dit wirk',
'exif-preferredattributionname' => 'Gebroek de volgende credits bie hergebroek van dit wirk',
'exif-pngfilecomment' => 'Opmirking bie PNG-bestandj',
'exif-disclaimer' => 'Veurbehawd',
'exif-contentwarning' => 'Waorsjoewing euver inhawd',
'exif-giffilecomment' => 'Opmirking bie GIF-bestandj',
'exif-intellectualgenre' => 'Itemtype',
'exif-subjectnewscode' => 'Ongerwerpcode',
'exif-scenecode' => 'IPTC-scènecode',
'exif-event' => 'Aafgebeilde gebäörtenis',
'exif-organisationinimage' => 'Aafgebeilde organisatie',
'exif-personinimage' => 'Aafgebeild persoen',
'exif-originalimageheight' => 'Heugdje vanne aafbeilding veur biesnieje',
'exif-originalimagewidth' => 'Brèdje vanne aafbeilding veur biesnieje',

# Exif attributes
'exif-compression-1' => 'Óngecómprimeerd',
'exif-compression-2' => 'CCITT Groep 3 1-dimensionale aangepasde "Huffman run length"-codering',
'exif-compression-3' => 'CCITT Groep 3 faxcodering',
'exif-compression-4' => 'CCITT Groep 4 faxcodering',

'exif-copyrighted-true' => 'Mit copyright',
'exif-copyrighted-false' => 'Publiek domein',

'exif-unknowndate' => 'Datum ónbekindj',

'exif-orientation-1' => 'Normaal',
'exif-orientation-2' => 'Horizontaal gespegeldj',
'exif-orientation-3' => '180° gedrejd',
'exif-orientation-4' => 'Verticaal gespegeldj',
'exif-orientation-5' => 'Gespegeldj om as linksbaove-rechsonger',
'exif-orientation-6' => '90° linksom gedrejd',
'exif-orientation-7' => 'Gespegeldj om as linksonger-rechsbaove',
'exif-orientation-8' => '90° rechsom gedrejd',

'exif-planarconfiguration-1' => 'chunky gegaevesformaat',
'exif-planarconfiguration-2' => 'planar gegaevesformaat',

'exif-colorspace-65535' => 'Ongekalibreerd',

'exif-componentsconfiguration-0' => 'besjteit neet',

'exif-exposureprogram-0' => 'Neet gedefiniëerd',
'exif-exposureprogram-1' => 'Handjmaotig',
'exif-exposureprogram-2' => 'Normaal programma',
'exif-exposureprogram-3' => 'Diafragmaprioriteit',
'exif-exposureprogram-4' => 'Sloeterprioriteit',
'exif-exposureprogram-5' => 'Creatief (veurkeur veur hoge sjerpte/deepdje)',
'exif-exposureprogram-6' => 'Actie (veurkeur veur hoge sloetersnelheid)',
'exif-exposureprogram-7' => 'Portret (detailopname mit ónsjerpe achtergróndj)',
'exif-exposureprogram-8' => 'Landjsjap (sjerpe achtergróndj)',

'exif-subjectdistance-value' => '$1 maeter',

'exif-meteringmode-0' => 'Ónbekindj',
'exif-meteringmode-1' => 'Gemiddeldj',
'exif-meteringmode-2' => 'Centrumgewaoge',
'exif-meteringmode-3' => 'Spot',
'exif-meteringmode-4' => 'Multi-spot',
'exif-meteringmode-5' => 'Multi-segment (patroon)',
'exif-meteringmode-6' => 'Deilmaeting',
'exif-meteringmode-255' => 'Anges',

'exif-lightsource-0' => 'Ónbekindj',
'exif-lightsource-1' => 'Daagleech',
'exif-lightsource-2' => 'TL-leech',
'exif-lightsource-3' => 'Tungsten (lampeleech)',
'exif-lightsource-4' => 'Flits',
'exif-lightsource-9' => 'Net waer',
'exif-lightsource-10' => 'Bewólk waer',
'exif-lightsource-11' => 'Sjeem',
'exif-lightsource-12' => 'Daagleech fluorescerend (D 5700 – 7100K)',
'exif-lightsource-13' => 'Daagwit fluorescerend (N 4600 - 5400K)',
'exif-lightsource-14' => 'Keul wit fluorescerend (W 3900 - 4500K)',
'exif-lightsource-15' => 'Wit fluorescerend (WW 3200 - 3700K)',
'exif-lightsource-17' => 'Standaard leech A',
'exif-lightsource-18' => 'Standaard leech B',
'exif-lightsource-19' => 'Standaard leech C',
'exif-lightsource-24' => 'ISO-studiotungsten',
'exif-lightsource-255' => 'Angere leechbron',

# Flash modes
'exif-flash-fired-0' => 'Flits is neet aafgegange',
'exif-flash-fired-1' => 'Mit flitser',
'exif-flash-return-0' => 'gein stroboscoopontvangsfunctie',
'exif-flash-return-2' => 'gein stroboscoopontvangs gedetecteerd',
'exif-flash-return-3' => 'stroboscoopontvangs gedetecteerd',
'exif-flash-mode-1' => 'verplich mit flitser',
'exif-flash-mode-2' => 'flitser verplich ongerdruk',
'exif-flash-mode-3' => 'automatische modus',
'exif-flash-function-1' => 'Gein flitserfunctie',
'exif-flash-redeye-1' => 'filter rooj öjg verwiedere',

'exif-focalplaneresolutionunit-2' => 'inch',

'exif-sensingmethod-1' => 'Neet gedefiniëerd',
'exif-sensingmethod-2' => 'Ein-chip-kleursensor',
'exif-sensingmethod-3' => 'Twee-chip-kleursensor',
'exif-sensingmethod-4' => 'Drie-chip-kleursensor',
'exif-sensingmethod-5' => 'Kleurvolgendje gebiedssensor',
'exif-sensingmethod-7' => 'Drielienige sensor',
'exif-sensingmethod-8' => 'Kleurvolgendje liensensor',

'exif-filesource-3' => 'Digitale fotocamera',

'exif-scenetype-1' => "'ne Direk gefotografeerdje aafbeilding",

'exif-customrendered-0' => 'Normaal perces',
'exif-customrendered-1' => 'Aangepasdje verwerking',

'exif-exposuremode-0' => 'Automatische beleechting',
'exif-exposuremode-1' => 'Handjmaotige beleechting',
'exif-exposuremode-2' => 'Auto-Bracket',

'exif-whitebalance-0' => 'Automatische witbalans',
'exif-whitebalance-1' => 'Handjmaotige witbalans',

'exif-scenecapturetype-0' => 'Standaard',
'exif-scenecapturetype-1' => 'Landjsjap',
'exif-scenecapturetype-2' => 'Portret',
'exif-scenecapturetype-3' => 'Nachscène',

'exif-gaincontrol-0' => 'Gein',
'exif-gaincontrol-1' => 'Lege pieke omhoog',
'exif-gaincontrol-2' => 'Hoge pieke omhoog',
'exif-gaincontrol-3' => 'Lege pieke omleeg',
'exif-gaincontrol-4' => 'Hoge pieke omleeg',

'exif-contrast-0' => 'Normaal',
'exif-contrast-1' => 'Weik',
'exif-contrast-2' => 'Hel',

'exif-saturation-0' => 'Normaal',
'exif-saturation-1' => 'Leeg',
'exif-saturation-2' => 'Hoog',

'exif-sharpness-0' => 'Normaal',
'exif-sharpness-1' => 'Zaach',
'exif-sharpness-2' => 'Hel',

'exif-subjectdistancerange-0' => 'Onbekindj',
'exif-subjectdistancerange-1' => 'Macro',
'exif-subjectdistancerange-2' => 'Kortbie',
'exif-subjectdistancerange-3' => 'Wied weg',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Noorderbreidte',
'exif-gpslatitude-s' => 'Zuderbreidte',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Oosterlingdje',
'exif-gpslongitude-w' => 'Westerlingdje',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => "$1 {{PLURAL:$1|maeter|maeter}} baoven 't ziespegel",
'exif-gpsaltitude-below-sealevel' => "$1 {{PLURAL:$1|maeter|maeter}} ónger 't ziespegel",

'exif-gpsstatus-a' => 'Bezig mit maete',
'exif-gpsstatus-v' => 'Maetinteroperabiliteit',

'exif-gpsmeasuremode-2' => '2-dimensionale maeting',
'exif-gpsmeasuremode-3' => '3-dimensionale maeting',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilomaeter per oer',
'exif-gpsspeed-m' => 'Miel per oer',
'exif-gpsspeed-n' => 'Knuip',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'Kilomaeter',
'exif-gpsdestdistance-m' => 'Miel',
'exif-gpsdestdistance-n' => 'Knuip',

'exif-gpsdop-excellent' => 'Oetstaekendj ($1)',
'exif-gpsdop-good' => 'Good ($1)',
'exif-gpsdop-moderate' => 'Gemiddeldj ($1)',
'exif-gpsdop-fair' => 'Redelik ($1)',
'exif-gpsdop-poor' => 'Slech ($1)',

'exif-objectcycle-a' => "Allein 's óchtes",
'exif-objectcycle-p' => "Allein 's aoves",
'exif-objectcycle-b' => "Zwaal 's óchtes es 's aoves",

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Eigelike richting',
'exif-gpsdirection-m' => 'Magnetische richting',

'exif-ycbcrpositioning-1' => 'Gecentreerd',
'exif-ycbcrpositioning-2' => 'Gecositueerd',

'exif-dc-contributor' => 'Mitwirkers',
'exif-dc-coverage' => 'Ruumdjelik of temporeel scoop vanne media',
'exif-dc-date' => 'Datum/data',
'exif-dc-publisher' => 'Oetgaever',
'exif-dc-relation' => 'Gerelateerde media',
'exif-dc-rights' => 'Rechte',
'exif-dc-source' => 'Brónmedia',
'exif-dc-type' => 'Mediatype',

'exif-rating-rejected' => 'Aafgeweze',

'exif-isospeedratings-overflow' => 'Grótter es 65535',

'exif-iimcategory-ace' => 'Kóns, keltuur en vermaak',
'exif-iimcategory-clj' => 'Misdaod en rech',
'exif-iimcategory-dis' => 'Rampe en óngevalle',
'exif-iimcategory-fin' => 'Ikkenomie en bedriefslaeve',
'exif-iimcategory-edu' => 'Óngerwies',
'exif-iimcategory-evn' => 'Miljeu',
'exif-iimcategory-hth' => 'Gezóndjheid',
'exif-iimcategory-hum' => 'Mienselik gerei',
'exif-iimcategory-lab' => 'Wirk',
'exif-iimcategory-lif' => 'Laeve en vrieje tied',
'exif-iimcategory-pol' => 'Politiek',
'exif-iimcategory-rel' => 'Gódsdeens en euvertuging',
'exif-iimcategory-sci' => 'Weitesjap en technologie',
'exif-iimcategory-soi' => 'Sociaal kwesties',
'exif-iimcategory-spo' => 'Spórt',
'exif-iimcategory-war' => 'Krieg, conflik en ónrös',
'exif-iimcategory-wea' => 'Waer',

'exif-urgency-normal' => 'Normaal ($1)',
'exif-urgency-low' => 'Lieg ($1)',
'exif-urgency-high' => 'Hoeg ($1)',
'exif-urgency-other' => 'Door gebroeker gedefinieerde prioriteit ($1)',

# External editor support
'edit-externally' => "Bewirk dit bestand mit 'n extern toepassing",
'edit-externally-help' => '(zuug de [//www.mediawiki.org/wiki/Manual:External_editors setupinsjtructies] veur mie informatie)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'al',
'namespacesall' => 'alle',
'monthsall' => 'al',
'limitall' => 'al',

# Email address confirmation
'confirmemail' => 'Bevèstig e-mailadres',
'confirmemail_noemail' => 'Doe höbs gein geldig e-mailadres ingegaeve in dien [[Special:Preferences|veurkäöre]].',
'confirmemail_text' => "Deze wiki vereis dats te dien e-mailadres instèls iedats te e-mailfuncties
gebroeks. Klik op de knop hieónger óm e bevèstegingsberich nao dien adres te
sjikke. D'n e-mail zal 'ne link mèt 'n code bevatte; eupen de link in diene
browser óm te bevestege dat dien e-mailadres werk.",
'confirmemail_pending' => "Dao is al 'n bevestigingsberich aan dich versjik. Wens te zjus diene gebroeker aangemaak höbs, wach dan e paar minute pès 't aankump veurdets te opnuuj 'n e-mail leuts sjikke.",
'confirmemail_send' => "Sjik 'n bevèstegingcode",
'confirmemail_sent' => 'Bevèstegingsberich versjik.',
'confirmemail_oncreate' => "D'r is 'n bevestigingscode nao dien e-mailadres versjik. Dees code is neet nudig óm aan te melje, meh doe mós dees waal bevestige veurdets te de e-mailmäögelikheite van deze wiki kèns nótse.",
'confirmemail_sendfailed' => "{{SITENAME}} kós 't bevèstegingsberich neet versjikke.
Zuug dien e-mailadres nao op óngeljige karakters.

't E-mailprogramma goof: $1",
'confirmemail_invalid' => 'Óngeljige bevèstigingscode. De code is meugelik verloupe.',
'confirmemail_needlogin' => 'Doe mós $1 óm dien e-mailadres te bevestige.',
'confirmemail_success' => 'Dien e-mailadres is bevesteg. De kins noe inlogke en van de wiki genete.',
'confirmemail_loggedin' => 'Dien e-mailadres is noe vasgelag.',
'confirmemail_error' => "Bie 't opsjlaon van eur bevèstiging is get fout gegange.",
'confirmemail_subject' => 'Bevèstiging e-mailadres veur {{SITENAME}}',
'confirmemail_body' => "Emes, waorsjienlik doe vanaaf 't IP-adres $1, heet 'n account $2
aangemaak mit dit e-mailadres op {{SITENAME}}.

Eupen óm te bevèstige dat dit account wirkelik van dich is en de
e-mailgegaeves op {{SITENAME}} te activere deze link in diene browser:

$3

Es geer dit *neet* zeet, vólg den deze link:

$5

Dees bevèstigingscode blief geljig tot $4",
'confirmemail_body_changed' => "Emes, waersjienlik doe, met 't IP-adres \$1,
haet 't e-mailadres geregistreerd veur gebroeker \"\$2\" op {{SITENAME}} gewiezig nao dit e-mailadres.

Äöpen de volgende verwiezing in diene webbrowser om te bevestige des toe deze gebroeker bis en om de e-mailmeugelikhejen op {{SITENAME}} opnuuj te activere:

\$3

Es se dichzelf '''neet''' hees aangemeld, volg den de volgende verwiezing om de bevestiging van dien e-mailadres te annulere:

\$5

De bevestigingscode vervilt op \$4.",
'confirmemail_body_set' => "Emes, waersjienlik doe, met 't IP-adres \$1,
haet 't e-mailadres geregistreerd veur gebroeker \"\$2\" op {{SITENAME}} ingesteld óp dit e-mailadres.

Äöpen de volgende verwiezing in diene webbrowser om te bevestige des toe deze gebroeker bis en om de e-mailmeugelikhejen op {{SITENAME}} opnuuj te activere:

\$3

Es se dichzelf '''neet''' hees aangemeld, volg den de volgende verwiezing om de bevestiging van dien e-mailadres te annulere:

\$5

De bevestigingscode vervilt op \$4.",
'confirmemail_invalidated' => 'De e-mailbevestiging is geannuleerdj',
'invalidateemail' => 'E-mailbevestiging annulere',

# Scary transclusion
'scarytranscludedisabled' => '[Interwikitransclusie is oetgesjakeld]',
'scarytranscludefailed' => '[Sjabloon $1 kós neet opgehaold waer]',
'scarytranscludetoolong' => '[URL is te lank]',

# Delete conflict
'deletedwhileediting' => "'''Waorsjoewing''': Dees pazjena is gewis naodats doe bis begós mit bewirke!",
'confirmrecreate' => "Gebroeker [[User:$1|$1]] ([[User talk:$1|euverlèk]]) heet dees pagina eweggesjaf naodats doe mèt bewirke begós mèt de rae:
: ''$2''
Bevesteg estebleef dats te dees pazjena ech obbenuujts wils aanmake.",
'confirmrecreate-noreason' => "Naodes se begós bös mit 't verangere haet [[User:$1|$1]] ([[User talk:$1|euverlègk]]) dees pagina gewösj.
Bevestig des se dees pagina óbbenuits wils aanmake.",
'recreate' => 'Pazjena obbenuujts make',

# action=purge
'confirm_purge_button' => 'ok',
'confirm-purge-top' => 'Wils te de buffer vaan dees paas wisse?',
'confirm-purge-bottom' => 't Opsjone van de cache zorg drveur det de lèste versie van n pagina wörd weergegaeve.',

# action=watch/unwatch
'confirm-watch-button' => 'Ok',
'confirm-watch-top' => 'Dees pagina bie dien volglies zètte?',
'confirm-unwatch-button' => 'Ok',
'confirm-unwatch-top' => 'Dees pagina van dien volglies ewegsjaffe?',

# Multipage image navigation
'imgmultipageprev' => '← veurige pazjena',
'imgmultipagenext' => 'volgende pazjena →',
'imgmultigo' => 'Gank!',
'imgmultigoto' => 'Gank naor pazjena $1',

# Table pager
'ascending_abbrev' => 'opl.',
'descending_abbrev' => 'aaf.',
'table_pager_next' => 'Volgende pazjena',
'table_pager_prev' => 'Veurige pazjena',
'table_pager_first' => 'Ierste pazjena',
'table_pager_last' => 'Lètste pazjena',
'table_pager_limit' => 'Tuin $1 resultate per pazjena',
'table_pager_limit_label' => 'Deil per paasj:',
'table_pager_limit_submit' => 'Gank',
'table_pager_empty' => 'Gein resultate',

# Auto-summaries
'autosumm-blank' => 'De pagina is laeggehaold',
'autosumm-replace' => "Teks vervange mit '$1'",
'autoredircomment' => 'Verwies door nao [[$1]]',
'autosumm-new' => 'Nuuj pazjena mit $1',

# Live preview
'livepreview-loading' => 'Laje…',
'livepreview-ready' => 'Laje… Vaerdig!',
'livepreview-failed' => 'Live veurvertuin mislök!
Probeer normaal veurvertuin.',
'livepreview-error' => 'Verbènje mislök: $1 "$2"
Probeer normaal veurvertuin.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Verangeringe die nujer zeen es $1 {{PLURAL:$1|sekónd|sekónd}} waere mesjiens neet getuind in dees lies.',
'lag-warn-high' => "Door 'ne hoege database-servertoeveur zeen verangeringe nujer es $1 {{PLURAL:$1|seconde|seconde}} mäögelik neet besjikbaar in de lies.",

# Watchlist editor
'watchlistedit-numitems' => "Op dien volglies sjtaon {{PLURAL:$1|1 pazjena|$1 pazjena's}}, exclusief euverlèkpazjena's.",
'watchlistedit-noitems' => "Dao sjtaon gein pazjena's op dien volglies.",
'watchlistedit-normal-title' => 'Volglies bewirke',
'watchlistedit-normal-legend' => "Pazjena's ewegsjaffe van dien volglies",
'watchlistedit-normal-explain' => "Pazjena's op dien volglies waere hiejónger getuind.
Klik op 't veerkentje d'rnaeve óm 'n pazjena eweg te sjaffe. Klik daonao op '{{int:Watchlistedit-normal-submit}}'.
De kèns ouch [[Special:EditWatchlist/raw|de roew lies bewirke]].",
'watchlistedit-normal-submit' => "Pazjena's ewegsjaffe",
'watchlistedit-normal-done' => "{{PLURAL:$1|1 pazjena is|$1 pazjena's zeen}} eweggesjaf van dien volglies:",
'watchlistedit-raw-title' => 'Roew volglies bewirke',
'watchlistedit-raw-legend' => 'Roew volglies bewirke',
'watchlistedit-raw-explain' => "Hiejónger sjtaon pazjena's op dien volglies. De kèns de lies bewirke door pazjena's eweg te sjaffe en d'rbie te doon. Ein pazjena per regel.
Wens te vaerdig bis, klik dan op '{{int:Watchlistedit-raw-submit}}'.
De kèns ouch [[Special:EditWatchlist|'t sjtanderd bewirkingssjirm gebroeke]].",
'watchlistedit-raw-titles' => "Pazjena's:",
'watchlistedit-raw-submit' => 'Volglies biewirke',
'watchlistedit-raw-done' => 'Dien volglies is biegewirk.',
'watchlistedit-raw-added' => "{{PLURAL:$1|1 pazjena is|$1 pazjena's zeen}} toegevoog:",
'watchlistedit-raw-removed' => "{{PLURAL:$1|1 pazjena is|$1 pazjena's zeen}} eweggesjaf:",

# Watchlist editing tools
'watchlisttools-view' => 'Volglies bekieke',
'watchlisttools-edit' => 'Volglies bekieke en bewirke',
'watchlisttools-raw' => 'Roew volglies bewirke',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|euverlègk]])',

# Core parser functions
'unknown_extension_tag' => 'Ónbekindje tag "$1"',
'duplicate-defaultsort' => 'Waarsjuwing: De standaardsortering "$2" krieg veurrang veur de sortering "$1".',

# Special:Version
'version' => 'Versie',
'version-extensions' => 'Geïnstalleerde oetbreijinge',
'version-specialpages' => "Speciaal pazjena's",
'version-parserhooks' => 'Parserheuk',
'version-variables' => 'Variabele',
'version-antispam' => 'Spampreventie',
'version-skins' => 'Vörmgevinge',
'version-other' => 'Euverige',
'version-mediahandlers' => 'Mediaverwerkers',
'version-hooks' => 'Heuk',
'version-parser-extensiontags' => 'Parseroetbreijingstags',
'version-parser-function-hooks' => 'Parserfunctieheuk',
'version-hook-name' => 'Hooknaam',
'version-hook-subscribedby' => 'Geabonneerd door',
'version-version' => '(Versie $1)',
'version-license' => 'Licentie',
'version-poweredby-credits' => "Deze wiki weurt aangedreve door '''[//www.mediawiki.org/ MediaWiki]''', copyright © 2001-$1 $2.",
'version-poweredby-others' => 'anger',
'version-license-info' => "MediaWiki is vrieje sofware; de kins MediaWiki verspreien en/of aanpassen onger de veurwaerde van de GNU General Public License wie gepubliceerd door de Free Software Foundation; ofwaal versie 2 van de Licentie, of - nao diene wönsj - innig later versie.

MediaWiki weurd verspreid in de haop det 't nuttig is, mer ZONGER INNIG GARANTIE; zonger zelfs de implicitiete garantie van VERKOUPBAARHEID of GESJIKHEID VEUR INNIG DOEL IN 'T BIEZÖNJER. Zuuch de GNU General Public License veur mier informatie.

Same mit dit programma heurs se 'n [{{SERVER}}{{SCRIPTPATH}}/COPYING kopie van de GNU General Public License] te höbben ontvange; zo neet, sjrief den nao de Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA of [//www.gnu.org/licenses/old-licenses/gpl-2.0.html laes de licentie online].",
'version-software' => 'Geïnstallieërde sofwaer',
'version-software-product' => 'Perduk',
'version-software-version' => 'Versie',
'version-entrypoints' => 'Ingang-URLs',
'version-entrypoints-header-entrypoint' => 'Ingank',
'version-entrypoints-header-url' => 'URL',

# Special:FileDuplicateSearch
'fileduplicatesearch' => 'Zeuk veur döbbelbestaondje bestenj',
'fileduplicatesearch-summary' => 'Zeuk veur döbbel bestaondje bestenj op basis van zien hashwaarde.',
'fileduplicatesearch-legend' => "Zeuk veur 'ne döbbele",
'fileduplicatesearch-filename' => 'Bestandjsnaam:',
'fileduplicatesearch-submit' => 'Zeuk',
'fileduplicatesearch-info' => '$1 × $2 pixel<br />Bestandjsgrootte: $3<br />MIME type: $4',
'fileduplicatesearch-result-1' => '\'t Bestandh "$1" haet gein identieke döbbelversie.',
'fileduplicatesearch-result-n' => '\'t Bestandj "$1" haet {{PLURAL:$2|1 identieke döbbelversie|$2 identiek döbbelversies}}.',
'fileduplicatesearch-noresults' => 'd\'r Is gei bestandj mitte naam "$1" gevónje.',

# Special:SpecialPages
'specialpages' => "Speciaal pagina's",
'specialpages-note' => '----
* Normaal speciaal pagina\'s
* <strong class="mw-specialpagerestricted">Beperk toegankelike speciaal pagina\'s</strong>
* <span class="mw-specialpagecached">Speciaal pagina\'s mit allein gegaeves oete cache (meugelik verajerd)</span>',
'specialpages-group-maintenance' => 'Óngerhajingsrapporter',
'specialpages-group-other' => "Euverige speciaal pazjena's",
'specialpages-group-login' => 'Aanmelje / registrere',
'specialpages-group-changes' => 'Recènte wieziginge en logbeuk',
'specialpages-group-media' => 'Mediaeuverzichte en uploads',
'specialpages-group-users' => 'Gebroekers en rechte',
'specialpages-group-highuse' => "Väölgebroekde pazjena's",
'specialpages-group-pages' => 'Pazjenalieste',
'specialpages-group-pagetools' => 'Pazjenahölpmiddele',
'specialpages-group-wiki' => 'Wikigegaeves en -hölpmiddele',
'specialpages-group-redirects' => "Doorverwiezende speciaal pazjena's",
'specialpages-group-spam' => 'Spamhölpmiddele',

# Special:BlankPage
'blankpage' => 'Laeg pazjena',
'intentionallyblankpage' => 'Deze pagina is bewus laeg gelaote en wurt gebroek veur benchmarks, enzovoort.',

# External image whitelist
'external_image_whitelist' => " #Laot deze regel onveranderd<pre>
#Zèt hierónger reguliere expressiefragmente (allein 't deil det tusse de // steit)
#Deze waere gehaaje taenge de URL's van externe (gehotlinkte) aafbeeldinge
#Es de reguliere expressie van toegang is, wurd 'n aafbeelding weergegaeve, anges wurd allein 'n verwiezing weergegaeve
#Regels die beginne met \"#\" waere es opmerking behanjeld
#Regels in de witte lies zeen neet hooflettergeveulig.

#Zet alle reguliere expressiefragmenten baove deze regel. Laot deze regel onveranderd</pre>",

# Special:Tags
'tags' => 'Geldige wiezigingslabels',
'tag-filter' => '[[Special:Tags|Labelfilter]]:',
'tag-filter-submit' => 'Filtere',
'tags-title' => 'Labels',
'tags-intro' => 'Op deze pagina staon de labels womit de software edere bewerking kan markere, en häör betekenis.',
'tags-tag' => 'Labelnaam',
'tags-display-header' => 'Weergave in wiezigingslieste',
'tags-description-header' => 'Volledige beschrieving van betekenis',
'tags-hitcount-header' => 'Gelabelde bewerkinge',
'tags-edit' => 'bewerking',
'tags-hitcount' => '$1 {{PLURAL:$1|wieziging|wieziginge}}',

# Special:ComparePages
'comparepages' => "Vergeliek pazjena's",
'compare-selector' => 'Vergeliek pazjenaversies',
'compare-page1' => 'Paasj 1',
'compare-page2' => 'Paasj 2',
'compare-rev1' => 'Versie 1',
'compare-rev2' => 'Versie 2',
'compare-submit' => 'Vergeliek',
'compare-invalid-title' => 'De opgegaeve pazjenanaam is óngeljig.',
'compare-title-not-exists' => 'Aangegaeve titel besteit neet.',
'compare-revision-not-exists' => 'Aangegaeve versie besteit neet.',

# Database error messages
'dberr-header' => "Deze wiki haet 'n probleem",
'dberr-problems' => 'Os excuses. Deze site ongervindj op t moment technische probleme.',
'dberr-again' => 'Wach n aantal minute en probeer t daonao opnuuj.',
'dberr-info' => '(Kan gein verbinjing make mit de databaseserver: $1)',
'dberr-usegoogle' => 'Wellich kins se in de tussetied zeuke via Google.',
'dberr-outofdate' => "Let op: häör indices ven os pagina's zeen wellich neet recent.",
'dberr-cachederror' => 'Deze pagina is n kopie oet de cache en is wellich neet de lèste versie.',

# HTML forms
'htmlform-invalid-input' => "d'r Zeen perbleme mit inkel ingegaeve waerd",
'htmlform-select-badoption' => 'De ingegaeve waerd is óngeljig.',
'htmlform-int-invalid' => 'De ingegaeve waerd is gei gans getal.',
'htmlform-float-invalid' => 'De waerd dae se haes ingegaeve is gei getal.',
'htmlform-int-toolow' => 'De ingegaeve waerd lèk ónger de maximumwaerd van $1',
'htmlform-int-toohigh' => 'De ingegaeve waerd lèk baove de maximumwaerd van $1',
'htmlform-required' => 'Dees waerd is verplich',
'htmlform-submit' => 'Slaon óp',
'htmlform-reset' => 'Maak verangeringe óngedaon',
'htmlform-selectorother-other' => 'Anges',

# SQLite database support
'sqlite-has-fts' => 'Zeuk versie $1 mit óngersteuning veur "full-text"',
'sqlite-no-fts' => 'Zeuk versie $1 zónger óngersteuning veur "fulltext"',

# New logging system
'logentry-delete-delete' => '$1 haef de pagina $3 gewösj',
'logentry-delete-restore' => '$1 haet de pagina $3 trögkgezat',
'logentry-delete-event' => "$1 haet de zichbaarheid van {{PLURAL:$5|'ne logbookregel|$5 logbookregels}} van $3 gewiezig: $4",
'logentry-delete-revision' => "$1 haet de zichbaarheid van {{PLURAL:$5|'n versie|$5 versies}} van $3 gewiezig: $4",
'logentry-delete-event-legacy' => '$1 haet de zichbaarheid van logbookregels van $3 gewiezig',
'logentry-delete-revision-legacy' => '$1 haet de zichbaarheid van versies van de pagina $3 gewiezig.',
'logentry-suppress-delete' => '$1 haet de pagina $3 ongerdrök',
'logentry-suppress-event' => "$1 haet heimelik de zichbaarheid van {{PLURAL:$5|'ne logbookregel|$5 logbookregels}} van $3 gewiezig: $4",
'logentry-suppress-revision' => "$1 haet heimelik de zichbaarheid van {{PLURAL:$5|'n versie|$5 versies}} van $3 gewiezig: $4",
'logentry-suppress-event-legacy' => '$1 haet heimelik de zichbaarheid van logbookregels van $3 gewiezig',
'logentry-suppress-revision-legacy' => '$1 haet heimelik de zichbaarheid van versies van de pagina $3 gewiezig.',
'revdelete-content-hid' => 'inhawd verbórge',
'revdelete-summary-hid' => 'bewirkingssamevatting verbórge',
'revdelete-uname-hid' => 'gebroekersnaam verbórge',
'revdelete-content-unhid' => 'inhawd zichbaar gemaak',
'revdelete-summary-unhid' => 'bewirkingssamevatting zichbaar gemaak',
'revdelete-uname-unhid' => 'gebroekersnaam ónthöld',
'revdelete-restricted' => 'haet beperkinge aan beheerders opgelag',
'revdelete-unrestricted' => 'haet beperkinge veur beheerders opgehaeve',
'logentry-move-move' => '$1 verplaatsde pagina $3 nao $4',
'logentry-move-move-noredirect' => "$1 verplaatsde pagina $3 nao $4 zonger 'n doorverwiezing achter te laote",
'logentry-move-move_redir' => "$1 verplaatsde pagina $3 nao $4 euver 'ne redirek",
'logentry-move-move_redir-noredirect' => "$1 verplaatsde pagina $3 nao $4 euver 'ne redirek zonger 'n doorverwiezing achter te laote",
'logentry-patrol-patrol' => '$1 haet versie $4 van pagina $3 es gecontroleerd gemarkeerd',
'logentry-patrol-patrol-auto' => '$1 haet versie $4 van pagina $3 autematis es gecontroleerd gemarkeerd',
'logentry-newusers-newusers' => "$1 haet 'ne gebroeker aangemaak",
'logentry-newusers-create' => "$1 haet 'ne gebroeker aangemaak",
'logentry-newusers-create2' => "$1 haet 'ne gebroeker $3 aangemaak",
'logentry-newusers-autocreate' => 'De gebroeker $1 is autematis aangemaak',
'rightsnone' => '(gein)',

# Feedback
'feedback-bugornote' => 'Es se zewied bös óm e technisch perbleem in détail te besjrieve, [$1 rapperteer \'ne bug]. 
Anges kin se-n ouch \'t einvawdig formeleer hieónger gebroeke. Dien commentaar zal waere toegeveug ane pagina "[$3 $2]", same mit diene gebroekersnaam enne browser dae se gebruuks.',
'feedback-subject' => 'Óngerwerp:',
'feedback-message' => 'Berich:',
'feedback-cancel' => 'Braek aaf',
'feedback-submit' => 'Slaon feedback óp',
'feedback-adding' => 'Feedback weurt aan pagina toegevoeg...',
'feedback-error1' => 'Fout: ónbekind rizzeltaot vanne API',
'feedback-error2' => 'Fout: bewirking mislök',
'feedback-error3' => 'Fout: gein reactie vanne API',
'feedback-thanks' => 'Danke! Diene feedback is oppe pagina "[$2 $1]" geplaats.',
'feedback-close' => 'Gedaon',
'feedback-bugcheck' => "Good! Kónterleer ef of 't neet al ein vanne [$1 bekèndje bugs] is.",
'feedback-bugnew' => "Gekónterleerdj. Mèlj 'ne nuuj bug.",

# Search suggestions
'searchsuggest-search' => 'Zeuke',
'searchsuggest-containing' => 'bevat...',

# API errors
'api-error-badaccess-groups' => 'Doe moogs gein bestenj uploade óp deze wiki.',
'api-error-badtoken' => 'Intern fout: toke is slech.',
'api-error-copyuploaddisabled' => 'Uploade via URL steit óp deze server oet.',
'api-error-duplicate' => "d'r {{PLURAL:$1|steit al [$2 e bestandj]|staon al [$2 bestenj]}} mit dezelfden inhawd oppe wiki.",
'api-error-duplicate-archive' => "d'r {{PLURAL:$1|Waar al [$2 'n anger bestandj]|woren al [$2 $1 anger bestenj]}} óppe site mitte zelfdjen inhawd, meh {{PLURAL:$1|det is|die zeen}} gewösj.",
'api-error-duplicate-archive-popup-title' => '{{PLURAL:$1|Duplicaatbestandj det al gewösj is|Duplicaatbestenj die al gewösj zeen}}',
'api-error-duplicate-popup-title' => 'Zelfde {{PLURAL:$1|bestandj|bestenj}}',
'api-error-empty-file' => 't Bestandj det se perbeers te uploade had gein inhald.',
'api-error-emptypage' => "Doe maags gein nuuj, laeg pagina's aanmake.",
'api-error-fetchfileerror' => "Intern fout: d'r is get fout gegange bie 't óphaole van 't bestandj.",
'api-error-fileexists-forbidden' => 'd\'r Besteit al e bestandj mitte naam "$1" det neet euversjreve kin waere.',
'api-error-fileexists-shared-forbidden' => 'd\'r Besteit al e bestandj mitte naam "$1" inne gedeildje repositoir det neet euversjreve kin waere.',
'api-error-file-too-large' => 't Bestandj det se perbeers te uploade waas te groet.',
'api-error-filename-tooshort' => "t Bestandj det se perbeers te uploade had 'ne te kórte bestandjsnaam.",
'api-error-filetype-banned' => 't Bestandj det se perbeers te uploade waas van e neet-toegelaote bestandjstype.',
'api-error-filetype-banned-type' => "{{PLURAL:$4|'t bestandjstype $1 weurt|De bestandjstypes $1 waere}} neet toegelaote. {{PLURAL:$3|'t Toegelaote bestandjstype is|De toegelaote bestandjstypes zeen}} $2.",
'api-error-filetype-missing' => "'t Bestandj haet gein extensie.",
'api-error-hookaborted' => "De wieziging die se perbeers te make is aafgebraoke door 'nen oetbreidingshook.",
'api-error-http' => "Intern fout: d'r kós gein verbinjing gemaak waere mitte server.",
'api-error-illegal-filename' => 'Deze bestandjsnaam is neet toegelaote.',
'api-error-internal-error' => "Intern fout: d'r is get fout gegange tiedes 't verwirke vanne upload dore wiki.",
'api-error-invalid-file-key' => "Intern fout: 't bestandj is neet aangetróffe inne tiedeliken ópslaag.",
'api-error-missingparam' => "Intern fout: neet alle paramaeters zeen in 't verzeuk mitgeleverdj.",
'api-error-missingresult' => "Intern fout: 't waar neet meugelik vas te stèllen of 't kopiejere is geslaag.",
'api-error-mustbeloggedin' => 'Doe mós aangemèldj zeen óm bestenj te kinnen uploade.',
'api-error-mustbeposted' => 'Inter fout: aanvraog vereis HTTP-POST.',
'api-error-noimageinfo' => "De upload is aafgeróndj, meh de server haet gein gegaeves van 't bestandj vriegegaeve.",
'api-error-nomodule' => "Intern fout: d'r is gein uploadmodule ingesteldj.",
'api-error-ok-but-empty' => 'Intern fout: de server haet gein gegaeves trögkgeleverdj.',
'api-error-overwrite' => 'E bestandj euversjrieve geit neet.',
'api-error-stashfailed' => "Intern fout: de server kós 't tiedelik bestandj neet ópslaon.",
'api-error-timeout' => 'De server haet neet inne verwachde tied geantjweurd.',
'api-error-unclassified' => "dr Is 'n ónbekènde fout opgetraoje.",
'api-error-unknown-code' => 'Intern fout: "$1"',
'api-error-unknown-error' => "Intern fout: d'r is get fout gegange tiedes 't uploade van 't bestandj.",
'api-error-unknown-warning' => 'Onbekinde waorsjuwing: $1',
'api-error-unknownerror' => 'Ónbekèndje fout: "$1"',
'api-error-uploaddisabled' => 'Upload steit oet óp deze wiki.',
'api-error-verification-error' => "Dit bestandj is meugelik besjadig of haet 'n ónjuuste extensie.",

# Durations
'duration-seconds' => '$1 {{PLURAL:$1|sekond|sekond}}',
'duration-minutes' => '$1 {{PLURAL:$1|menuut|menuut}}',
'duration-hours' => '$1 {{PLURAL:$1|oer|oer}}',
'duration-days' => '$1 {{PLURAL:$1|daag|daag}}',
'duration-weeks' => '$1 {{PLURAL:$1|waek|waek}}',
'duration-years' => '$1 {{PLURAL:$1|jaor|jaor}}',
'duration-decades' => '$1 {{PLURAL:$1|decennium|decennia}}',
'duration-centuries' => '$1 {{PLURAL:$1|ieëf|ieëf}}',
'duration-millennia' => '$1 {{PLURAL:$1|millennium|millennia}}',

);

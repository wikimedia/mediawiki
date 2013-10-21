<?php
/** Scottish Gaelic (Gàidhlig)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Akerbeltz
 * @author Alison
 * @author Caoimhin
 * @author Sionnach
 * @author Steafan31
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Meadhan',
	NS_SPECIAL          => 'Sònraichte',
	NS_TALK             => 'Deasbaireachd',
	NS_USER             => 'Cleachdaiche',
	NS_USER_TALK        => 'Deasbaireachd_a\'_chleachdaiche',
	NS_PROJECT_TALK     => 'An_deasbaireachd_aig_$1',
	NS_FILE             => 'Faidhle',
	NS_FILE_TALK        => 'Deasbaireachd_an_fhaidhle',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Deasbaireachd_MediaWiki',
	NS_TEMPLATE         => 'Teamplaid',
	NS_TEMPLATE_TALK    => 'Deasbaireachd_na_teamplaid',
	NS_HELP             => 'Cobhair',
	NS_HELP_TALK        => 'Deasbaireachd_na_cobharach',
	NS_CATEGORY         => 'Roinn-seòrsa',
	NS_CATEGORY_TALK    => 'Deasbaireachd_na_roinn-seòrsa',
);

$messages = array(
# User preference toggles
'tog-underline' => 'Fo-loidhneadh nan ceanglaichean:',
'tog-justify' => 'Taobhaich na h-earrannan',
'tog-hideminor' => 'Falaich mùthaidhean beaga ann an liosta nam mùthaidhean ùra',
'tog-hidepatrolled' => 'Falaich mùthaidhean fo fhaire ann an liosta nam mùthaidhean ùra',
'tog-newpageshidepatrolled' => 'Falaich duilleagan fo fhaire ann an liosta nan duilleagan ùra',
'tog-extendwatchlist' => "Leudaich an clàr-faire gus an seall e gach mùthadh 's chan ann an fheadhainn as ùire a-mhàin",
'tog-usenewrc' => "Buidhnich na h-atharraichean a-rèir duilleige sna mùthaidhean ùra agus air a' chlàr-fhaire (feumaidh seo JavaScript)",
'tog-numberheadings' => 'Cuir àireamhan ri ceann-sgrìobhaidhean leis fhèin',
'tog-showtoolbar' => 'Seall am bàr-inneil deasachaidh (feumaidh seo JavaScript)',
'tog-editondblclick' => 'Tòisich air deasachadh duilleige le briogadh dùbailt (feumaidh seo JavaScript)',
'tog-editsection' => 'Cuir am comas deasachadh earainn le ceanglaichean [deasaich]',
'tog-editsectiononrightclick' => "Cuir an comas deasachadh earainn le briogadh deas air tiotal de dh'earrainn (feumaidh seo JavaScript)",
'tog-showtoc' => 'Seall an clàr-innse (air duilleagan air a bheil barrachd air 3 ceann-sgrìobhaidhean)',
'tog-rememberpassword' => "Cuimhnich gu bheil mi air logadh a-steach air a' choimpiutair seo (suas gu $1 {{PLURAL:$1|latha|latha|làithean|latha}})",
'tog-watchcreations' => "Cuir duilleagan a chruthaicheas mi air a' chlàr-fhaire agam",
'tog-watchdefault' => "Cuir duilleagan a dheasaicheas mi air a' chlàr-fhaire agam",
'tog-watchmoves' => "Cuir duilleagan a ghluaiseas mi air a' chlàr-fhaire agam",
'tog-watchdeletion' => "Cuir duilleagan a sguabas mi às air a' chlàr-fhaire agam",
'tog-minordefault' => 'Comharraich gach mùthadh mar mhùthadh beag a ghnàth',
'tog-previewontop' => "Nochd an ro-shealladh os cionn a' bhogsa deasachaidh",
'tog-previewonfirst' => "Nochd an ro-shealladh nuair a nithear a' chiad deasachadh",
'tog-nocache' => 'Cuir à comas tasgadh nan duilleagan',
'tog-enotifwatchlistpages' => "Cuir post-dealain thugam nuair a mhùthar duilleag a tha air a' chlàr-fhaire agam",
'tog-enotifusertalkpages' => 'Cuir post-dealain thugam nuair a mhùthaichear duilleag mo chonaltraidh',
'tog-enotifminoredits' => 'Cuir post-dealain thugam nuair a nithear mùthadh beag air duilleagan cuideachd',
'tog-enotifrevealaddr' => 'Nochd an seòladh puist-dhealain agam ann am teachdaireachdan fiosrachaidh',
'tog-shownumberswatching' => "Nochd àireamh nan cleachdaichean a tha a' cumail sùil air",
'tog-oldsig' => 'An t-earr-sgrìobhadh làithreach:',
'tog-fancysig' => 'Làimhsich an t-earr-sgrìobhadh mar wikitext (gun cheangal leis fhèin)',
'tog-uselivepreview' => 'Cleachd an ro-shealladh beò (feumaidh seo JavaScript) (deuchainneach)',
'tog-forceeditsummary' => "Cuir ceist nuair a dh'fhàgas mi gearr-chunntas an deasachaidh bàn",
'tog-watchlisthideown' => 'Falaich mo mhùthaidhean fhèin air mo chlàr-faire',
'tog-watchlisthidebots' => 'Falaich mùthaidhean nam bot air mo chlàr-faire',
'tog-watchlisthideminor' => 'Falaich mùthaidhean beaga air mo chlàr-faire',
'tog-watchlisthideliu' => 'Falaich mùthaidhean le cleachdaichean a tha air logadh a-steach air mo chlàr-faire',
'tog-watchlisthideanons' => 'Falaich mùthaidhean le cleachdaichean gun ainm air mo chlàr-faire',
'tog-watchlisthidepatrolled' => 'Falaich mùthaidhean air duilleagan fo fhreiceadan air mo chlàr-faire',
'tog-ccmeonemails' => 'Cuir lethbhric de phuist-dhealain a chuireas mi do chleachdaichean eile thugam',
'tog-diffonly' => 'Na seall susbaint nan duilleagan fo na diofaichean',
'tog-showhiddencats' => 'Seall na roinnean falaichte',
'tog-norollbackdiff' => 'Na dèan diof às dèidh roiligeadh air ais',
'tog-useeditwarning' => 'Thoir rabhadh dhomh ma bhios mi an impis duilleag deasachaidh fhàgail mus do shàbhail mi na mùthaidhean agam',

'underline-always' => 'An-còmhnaidh',
'underline-never' => 'Na dèan seo idir',
'underline-default' => "Bun-roghainn a' bhrabhsair no a' chraicinn",

# Font style option in Special:Preferences
'editfont-style' => 'Stoidhle cruth-clò an raoin dheasachaidh:',
'editfont-default' => "Roghainn bhunaiteach a' bhrabhsair",
'editfont-monospace' => 'Cruth-clò aon-leud',
'editfont-sansserif' => 'Cruth-clò gun serif',
'editfont-serif' => 'Cruth-clò le serif',

# Dates
'sunday' => 'DiDòmhnaich',
'monday' => 'DiLuain',
'tuesday' => 'DiMàirt',
'wednesday' => 'DiCiadain',
'thursday' => 'DiarDaoin',
'friday' => 'DihAoine',
'saturday' => 'DiSathairne',
'sun' => 'DiD',
'mon' => 'DiL',
'tue' => 'DiM',
'wed' => 'DiC',
'thu' => 'Dia',
'fri' => 'Dih',
'sat' => 'DiS',
'january' => 'dhen Fhaoilleach',
'february' => 'dhen Ghearrain',
'march' => 'dhen Mhàrt',
'april' => 'dhen Ghiblean',
'may_long' => 'dhen Chèitean',
'june' => 'dhen Ògmhios',
'july' => 'dhen Iuchar',
'august' => 'dhen Lùnastal',
'september' => 'dhen t-Sultain',
'october' => 'dhen Dàmhair',
'november' => 'dhen t-Samhain',
'december' => 'dhen Dùbhlachd',
'january-gen' => 'dhen Fhaoilleach',
'february-gen' => 'dhen Ghearrain',
'march-gen' => 'dhen Mhàrt',
'april-gen' => 'dhen Ghiblean',
'may-gen' => 'dhen Chèitean',
'june-gen' => 'dhen Ògmhios',
'july-gen' => 'dhen Iuchar',
'august-gen' => 'dhen Lùnastal',
'september-gen' => 'dhen t-Sultain',
'october-gen' => 'dhen Dàmhair',
'november-gen' => 'dhen t-Samhain',
'december-gen' => 'dhen Dùbhlachd',
'jan' => 'Faoi',
'feb' => 'Gearr',
'mar' => 'Màrt',
'apr' => 'Gibl',
'may' => 'Cèit',
'jun' => 'Ògmh',
'jul' => 'Iuch',
'aug' => 'Lùna',
'sep' => 'Sult',
'oct' => 'Dàmh',
'nov' => 'Samh',
'dec' => 'Dùbh',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Roinn-seòrsa|Roinn-seòrsa|Roinnean-seòrsa|Roinn-seòrsa}}',
'category_header' => 'Duilleagan sa roinn "$1"',
'subcategories' => 'Fo-roinnean',
'category-media-header' => 'Meadhanan sa roinn "$1"',
'category-empty' => "''Chan eil duilleagan no meadhanan san roinn seo an-dràsta.''",
'hidden-categories' => '{{PLURAL:$1|Roinn-seòrsa fhalaichte|Roinn-seòrsa fhalaichte|Roinnean-seòrsa falaichte|Roinn-seòrsa fhalaichte}}',
'hidden-category-category' => 'Roinnean falaichte',
'category-subcat-count' => '{{PLURAL:$2|Chan eil san roinn-seòrsa ach an fho-roinn-seòrsa a leanas.|Tha {{PLURAL:$1|an fho-roinn-seòrsa|an $1 fho-roinn-seòrsa|na $1 fo-roinnean-seòrsa|na $1 fo-roinn-seòrsa}}, aig an roinn-seòrsa a leanas, a-mach à $2 uile gu lèir.}}',
'category-subcat-count-limited' => 'Tha {{PLURAL:$1|an fho-roinn-seòrsa|na fo-roinntean-seòrsa}} a leanas sa roinn-seòrsa seo.',
'category-article-count' => '{{PLURAL:$2|Chan eil ach an duilleag a leanas san fho-roinn-seòrsa seo.|Tha {{PLURAL:$1|an duilleag|an $1 dhuilleag|na $1 duilleagan|na $1 duilleag}} a leanas san roinn-seòrsa seo, a-mach à $2 uile gu lèir.}}',
'category-article-count-limited' => 'Tha {{PLURAL:$1|an duilleag|an $1 dhuilleag|na $1 duilleagan|na $1 duilleag}} a leanas san roinn-seòrsa làithreach.',
'category-file-count' => '{{PLURAL:$2|Chan eil ach am faidhle a leanas san fho-roinn-seòrsa seo.|Tha {{PLURAL:$1|am faidhle|an $1 fhaidhle|na $1 faidhlichean|na $1 faidhle}} a leanas san roinn-seòrsa seo, a-mach à $2 uile gu lèir.}}',
'category-file-count-limited' => 'Tha {{PLURAL:$1|am faidhle|an $1 fhaidhle|na $1 faidhlichean|na $1 faidhle}} a leanas san roinn-seòrsa làithreach.',
'listingcontinuesabbrev' => '(an corr)',
'index-category' => "Duilleagan air a' chlàr-innse",
'noindex-category' => "Duilleagan nach eil air a' chlàr-innse",
'broken-file-category' => 'Duilleagan sa bheil ceanglaichean faidhle a tha briste',

'about' => 'Mu',
'article' => 'Duilleag susbainte',
'newwindow' => "(a' fosgladh ann an uinneag ùr)",
'cancel' => 'Sguir dheth',
'moredotdotdot' => 'Barrachd...',
'morenotlisted' => 'Barrachd nach eil air an liosta...',
'mypage' => 'Duilleag',
'mytalk' => 'Deasbaireachd',
'anontalk' => 'Conaltradh airson an IP seo',
'navigation' => 'Seòladh',
'and' => '&#32;agus',

# Cologne Blue skin
'qbfind' => 'Lorg',
'qbbrowse' => 'Brabhsaich',
'qbedit' => 'Deasaich',
'qbpageoptions' => 'An duilleag seo',
'qbmyoptions' => 'Na duilleagan agam',
'qbspecialpages' => 'Duilleagan sònraichte',
'faq' => 'CÀBHA',
'faqpage' => 'Project:CÀBHA',

# Vector skin
'vector-action-addsection' => 'Cuir ris cuspair',
'vector-action-delete' => 'Sguab às',
'vector-action-move' => 'Gluais',
'vector-action-protect' => 'Dìon',
'vector-action-undelete' => 'Neo-dhèan an sguabadh às',
'vector-action-unprotect' => 'Atharraich an dìon',
'vector-simplesearch-preference' => 'Cuir an comas am bàr-luirg simplidh (craiceann vector a-mhàin)',
'vector-view-create' => 'Cruthaich',
'vector-view-edit' => 'Deasaich',
'vector-view-history' => 'Seall an eachdraidh',
'vector-view-view' => 'Leugh',
'vector-view-viewsource' => 'Seall an tùs',
'actions' => 'Gnìomhan',
'namespaces' => 'Namespaces',
'variants' => 'Tionndaidhean',

'navigation-heading' => 'Clàr-taice na seòladaireachd',
'errorpagetitle' => 'Mearachd',
'returnto' => 'Till dhan duilleag a leanas: $1',
'tagline' => 'O {{SITENAME}}',
'help' => 'Cobhair',
'search' => 'Lorg',
'searchbutton' => 'Lorg',
'go' => 'Rach',
'searcharticle' => 'Rach',
'history' => 'Eachdraidh na duilleige',
'history_short' => 'Eachdraidh',
'updatedmarker' => 'air ùrachadh on turas mu dheireadh a thadhail mi air',
'printableversion' => 'Tionndadh a ghabhas a chlò-bhualadh',
'permalink' => 'Ceangal buan',
'print' => 'Clò-bhuail',
'view' => 'Seall',
'edit' => 'Deasaich',
'create' => 'Cruthaich',
'editthispage' => 'Deasaich an duilleag seo',
'create-this-page' => 'Cruthaich an duilleag seo',
'delete' => 'Sguab às',
'deletethispage' => 'Sguab às an duilleag seo',
'undelete_short' => "Neo-dhèan sguabadh às de {{PLURAL:$1|dh'aon deasachadh|$1 dheasachadh|$1 deasachaidhean|$1 deasachadh}}",
'viewdeleted_short' => 'Seall {{PLURAL:$1|aon deasachadh|$1 dheasachadh|$1 deasachaidhean|$1 deasachadh}} a chaidh a sguabadh às',
'protect' => 'Dìon',
'protect_change' => 'mùth',
'protectthispage' => 'Dìon an duilleag seo',
'unprotect' => 'Atharraich an dìon',
'unprotectthispage' => 'Atharraich dìon na duilleige seo',
'newpage' => 'Duilleag ùr',
'talkpage' => 'Dèan deasbad mun duilleag seo',
'talkpagelinktext' => 'Deasbaireachd',
'specialpage' => 'Duilleag shònraichte',
'personaltools' => 'Innealan pearsanta',
'postcomment' => 'Earrann ùr',
'articlepage' => 'Seall duilleag na susbainte',
'talk' => 'Deasbaireachd',
'views' => 'Tadhalan',
'toolbox' => 'Bogsa-innealan',
'userpage' => "Seall duilleag a' chleachdaiche",
'projectpage' => "Seall duilleag a' phròiseict",
'imagepage' => 'Seall duilleag an fhaidhle',
'mediawikipage' => 'Seall duilleag na teachdaireachd',
'templatepage' => 'Seall duilleag na teamplaide',
'viewhelppage' => 'Seall an duilleag cobharach',
'categorypage' => 'Seall duilleag na roinne',
'viewtalkpage' => 'Seall an deasbaireachd',
'otherlanguages' => 'Ann an cànain eile',
'redirectedfrom' => '(Air ath-sheòladh o $1)',
'redirectpagesub' => 'Ath-sheòl an duilleag',
'lastmodifiedat' => 'Chaidh an duilleag seo a mhùthadh $1 aig $2 turas mu dheireadh.',
'viewcount' => 'Chaidh inntrigeadh a dhèanamh dhan duilleag seo {{PLURAL:$1|aon turas|$1 thuras|$1 turais|$1 turas}}.',
'protectedpage' => 'Duilleag fo dhìon',
'jumpto' => 'Gearr leum gu:',
'jumptonavigation' => 'seòladh',
'jumptosearch' => 'lorg',
'view-pool-error' => "Duilich, tha na frithealaichean ro thrang an-dràsta.
Tha cus chleachdaichean a' feuchainn ris an duilleag seo fhaicinn.
Fuirich ort greis mus feuch thu ris an duilleag seo fhaicinn a-rithist.

$1",
'pool-timeout' => "Dh'fhalbh an ùine air 's tu a' feitheamh ris a ghlas",
'pool-queuefull' => 'Tha ciutha nam pròiseasan làn',
'pool-errorunknown' => 'Mearachd neo-aithnichte',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage).
'aboutsite' => 'Mu dhèidhinn {{SITENAME}}',
'aboutpage' => 'Project:Mu dhèidhinn',
'copyright' => 'Tha susbaint ri làimh fo $1.',
'copyrightpage' => '{{ns:project}}:Còraichean lethbhric',
'currentevents' => 'Cùisean an latha',
'currentevents-url' => 'Project:Cùisean an latha',
'disclaimers' => 'Aithrisean-àichidh',
'disclaimerpage' => 'Project:Aithris-àichidh choitcheann',
'edithelp' => 'Cobhair deasachaidh',
'helppage' => 'Help:Susbaint',
'mainpage' => 'Prìomh dhuilleag',
'mainpage-description' => 'Prìomh dhuilleag',
'policy-url' => 'Project:Poileasaidh',
'portal' => 'Doras na coimhearsnachd',
'portal-url' => 'Project:Doras na coimhearsnachd',
'privacy' => 'Am polasaidh prìobhaideachd',
'privacypage' => 'Project:Am polasaidh prìobhaideachd',

'badaccess' => 'Meareachd le cead',
'badaccess-group0' => "Chan eil cead agad an gnìomh a dh'iarr thu a thoirt gu buil.",
'badaccess-groups' => "Tha an gnìomh a dh'iarr thu cuingichte 's cha dèan ach buill {{PLURAL:$2|a' bhuidhinn|nam buidhnean}} a leanas e: $1.",

'versionrequired' => 'Feum air tionndadh $1 de MhediaWiki',
'versionrequiredtext' => 'Tha feum air tionndadh $1 de MhediaWiki mus faicear an duilleag seo.
Seall air [[Special:Version|duilleag an tionndaidh]].',

'ok' => 'Ceart ma-thà',
'retrievedfrom' => 'Air a tharraing à "$1"',
'youhavenewmessages' => 'Tha $1 ($2) agad.',
'newmessageslink' => 'teachdaireachdan ùra',
'newmessagesdifflink' => 'mùthadh mu dheireadh',
'youhavenewmessagesfromusers' => 'Tha $1 o {{PLURAL:$3|aon chleachdaiche|$3 chleachdaiche|$3 cleachdaichean|$3 cleachdaiche}} agad ($2).',
'youhavenewmessagesmanyusers' => 'Tha $1 agad o iomadh cleachdaiche ($2).',
'newmessageslinkplural' => '{{PLURAL:$1|aon teachdaireachd ùr|$1 theachdaireachd ùr|$1 teachdaireachdan ùra|$1 teachdaireachd ùr}}',
'newmessagesdifflinkplural' => '{{PLURAL:$1|am mùthadh|an $1 mhùthadh|na $1 mùthaidhean|na $1 mùthadh}} mu dheireadh',
'youhavenewmessagesmulti' => 'Tha teachdaireachdan ùra agad ann an $1',
'editsection' => 'deasaich',
'editold' => 'deasaich',
'viewsourceold' => 'seall an tùs',
'editlink' => 'deasaich',
'viewsourcelink' => 'seall an tùs',
'editsectionhint' => 'Deasaich earrann: $1',
'toc' => 'Susbaint',
'showtoc' => 'seall',
'hidetoc' => 'falaich',
'collapsible-collapse' => 'Co-theannaich',
'collapsible-expand' => 'Leudaich',
'thisisdeleted' => 'Seall no aisig $1?',
'viewdeleted' => 'Seall $1?',
'restorelink' => '{{PLURAL:$1|aon deasachadh|$1 dheasachadh|$1 deasachaidhean|$1 deasachadh}} a chaidh a sguabadh às',
'feedlinks' => 'Inbhir:',
'feed-invalid' => "Seòrsa mì-dhligheach de dh'fho-sgrìobhadh inbhir.",
'feed-unavailable' => 'Chan eil inbhirean co-bhanntachd ri fhaighinn',
'site-rss-feed' => '$1 Inbhir RSS',
'site-atom-feed' => '$1 Inbhir Atom',
'page-rss-feed' => '"$1" Inbhir RSS',
'page-atom-feed' => '"$1" Inbhir Atom',
'red-link-title' => '$1 (chan eil duilleag ann fhathast)',
'sort-descending' => "Seòrsaich a' tèarnadh",
'sort-ascending' => "Seòrsaich a' dìreadh",

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Duilleag',
'nstab-user' => "Duilleag a' chleachdaiche",
'nstab-media' => 'Meadhanan',
'nstab-special' => 'Duilleag shònraichte',
'nstab-project' => "Duilleag a' phròiseict",
'nstab-image' => 'Faidhle',
'nstab-mediawiki' => 'Teachdaireachd',
'nstab-template' => 'Teamplaid',
'nstab-help' => 'Cuideachadh',
'nstab-category' => 'Roinn',

# Main script and global functions
'nosuchaction' => 'Chan eil a leithid de ghnìomh ann',
'nosuchactiontext' => "Tha an gnìomh a shònraich an t-URL mì-dhligheach.
Faodaidh gun do chuir thu a-steach URL mearachdach no gun do lean thu ri ceangal mearachdach.
Cuideachd, faodaidh gu bheil seo 'na chomharradh air buga sa bhathar-bhog aig {{SITENAME}}",
'nosuchspecialpage' => "Chan eil duilleag shònraichte d' a leithid ann",
'nospecialpagetext' => "<strong>Dh'iarr thu duilleag shònraichte mhì-dhligheach.</strong>

Gheibh thu liosta nan duilleagan sònraichte 's dligheach aig [[Special:SpecialPages|{{int:specialpages}}]].",

# General errors
'error' => 'Mearachd',
'databaseerror' => 'Mearachd an stòir-dhàta',
'laggedslavemode' => "'''Rabhadh:''' Faodaidh nach eil ùrachaidhean a rinneadh o chionn ghoirid a' nochdadh san duilleag.",
'readonly' => 'Stòr-dàta glaiste',
'enterlockreason' => "Cuir a-steach adhbhar a' ghlais, a' gabhail a-steach tuairmeas air fuasgladh a' ghlais.",
'readonlytext' => "Tha an stòr-dàta glaiste do chlàir ùra 's mùthaidhean eile, ma dh'fhaoidte air sgàth obair-chàraidh chunbhalach an stòir-dhàta 's bidh e mar as àbhaist às dèidh sin.

Chuir an rianadair a ghlas e an cèill na leanas: $1",
'missing-article' => 'Cha do lorg an stòr-dàta teacsa de dhuilleag a bu chòir a bhith air a lorg aige \'s air a bheil "$1" $2.

\'S e mùthaidhean no ceangal eachdraidheil ro shean ri duilleag a chaidh a sguabadh às a bhios coireach à seo mar is trice.

Mur eil seo fìor, faodaidh gun do lorg thu buga sa bhathar-bhog.
An dèan thu aithris air seo do [[Special:ListUsers/sysop|rianadair]], ag innse dhaibh dè an t-URL a bha ann.',
'missingarticle-rev' => '(mùthadh#: $1)',
'missingarticle-diff' => '(Diof: $1, $2)',
'readonly_lag' => "Chaidh an stòr-dàta a ghlasadh leis fhèin fhad 's a tha frithealaichean nan stòr-dàta tràilleach air dheireadh a' mhaighstir",
'internalerror' => 'Ion-mhearachd',
'internalerror_info' => 'Ion-mhearachd: $1',
'fileappenderrorread' => 'Cha do ghabh "$1" a leughadh fhad \'s a bhathar \'ga chur ris.',
'fileappenderror' => 'Cha do ghabh "$1" a chur ri "$2".',
'filecopyerror' => 'Cha do ghabh lethbhreac dhen fhaidhle "$1" gu "$2".',
'filerenameerror' => 'Cha do ghabh ainm an fhaidhle "$1" atharrachadh gu "$2".',
'filedeleteerror' => 'Cha do ghabh am faidhle "$1" a sguabadh às.',
'directorycreateerror' => 'Cha do ghabh am pasgan "$1" a chruthachadh.',
'filenotfound' => 'Cha do ghabh am faidhle "$1" a lorg.',
'fileexistserror' => 'Chan urrainnear sgrìobhadh gun fhaidhle "$1": tha am faidhle ann mu thràth',
'unexpected' => 'Luach ris nach robh dùil: "$1"="$2".',
'formerror' => 'Mearachd: cha do ghabh am foirm a chur a-null',
'badarticleerror' => 'Cha ghabh an gnìomh seo a dhèanamh air an duilleag seo.',
'cannotdelete' => 'Cha do ghabh an duilleag no am faidhle "$1" a sguabadh às.
Faodaidh gun deach a sguabadh às le cuideigin eile mu thràth.',
'cannotdelete-title' => 'Cha ghabh an duilleag "$1" a sguabadh às',
'delete-hook-aborted' => 'Sguireadh dhen sguabadh às ri linn dubhain.
Cha deach adhbhar a thoirt seachad.',
'badtitle' => 'Droch thiotal',
'badtitletext' => "Bha an duilleag a dh'iarr thu mì-dhligheach, falamh no le tiotal eadar-chànanach no eadar-uici air a dhroch cheangal.
Faodaidh gu bheil aon no barrachd charactairean ann nach urrainn dhut a chleachdadh ann an tiotalan.",
'perfcached' => "Chaidh an dàta a leanas a thasgadh 's faodaidh gu bheil e air dheireadh. Tha {{PLURAL:$1|$1 toradh|$1 thoradh|$1 toraidhean|$1 toradh}} ri fhaighinn san tasgadan air a' char as motha.",
'perfcachedts' => "Chaidh an dàta a leanas a thasgadh agus chaidh ùrachadh $1 turas mu dheireadh. Tha {{PLURAL:$4|$4 toradh|$4 thoradh|$4 toraidhean|$4 toradh}} ri fhaighinn san tasgadan air a' char as motha.",
'querypage-no-updates' => 'Tha ùrachadh air a chur à comas air an duilleag seo an-dràsta.
Cha dèid an dàta an-seo ùrachadh aig an àm seo.',
'wrong_wfQuery_params' => 'Paramatairean mì-cheart airson wfQuery()<br />
Foincsean: $1<br />
Iarrtas: $2',
'viewsource' => 'Seall an tùs',
'viewsource-title' => 'Seall an tùs aig $1',
'actionthrottled' => 'Gnìomh air a mhùchadh',
'actionthrottledtext' => "Gus casg a chur air spama, chan urrainn dhut an gnìomh seo a dhèanamh ro thric am broinn ùine ghoirid agus chaidh thu thairis air a' chrìoch seo.
Feuch ris a-rithist às a dhèidh seo.",
'protectedpagetext' => 'Chaidh an duilleag seo a dhìon gus casg a chur air deasachadh.',
'viewsourcetext' => "'S urrainn dhut coimhead air tùs na duilleige seo 's lethbhreac a dhèanamh dheth:",
'viewyourtext' => "'S urrainn dhut coimhead air '''na mhùthaich thu''' 's lethbhreac a dhèanamh dheth air an duilleag seo:",
'protectedinterface' => "Bheir an duilleag seo dhut teacsa eadar-aghaidh airson a' bhathar-bhog air an uicipeid seo 's chaidh a ghlasadh gus casg a chur air mì-chleachdadh. Gus eadar-theangachadh atharrachadh no a chur ris airson gach uicipeid, cleachd [//translatewiki.net/ translatewiki.net], pròiseactan eadar-theangachadh MediaWiki.",
'editinginterface' => "'''Rabhadh:''' Tha thu a' deasachadh duilleag a tha 'ga chleachdadh a chum teacsa eadar-aghaidh a sholar airson a' bhathar-bhog.
Ma dh'atharraicheas tu an duilleag seo, bidh buaidh ann air coltas na h-eadar-aghaidh mar a chì càch e air an uicipeid seo.
Gus eadar-theangachadh atharrachadh no a chur ris airson gach uicipeid, cleachd [//translatewiki.net/ translatewiki.net], pròiseactan eadar-theangachadh MediaWiki.",
'cascadeprotected' => 'Chaidh an duilleag seo a dhìon o dheasachadh a chionn \'s gu bheil e am broinn {{PLURAL:$1|na duilleige|nan duilleagan}} a leanas a chaidh an dìon \'s an roghainn "mar eas" air:
$2',
'namespaceprotected' => "Chan eil cead agad duilleagan san namespace '''$1''' a dheasachadh.",
'customcssprotected' => "Chan eil cead agad an duilleag CSS seo a dheasachadh a chionn 's gu bheil na roghainnean pearsanta aig cleachdaiche eile innte.",
'customjsprotected' => "Chan eil cead agad an duilleag JavaScript seo a dheasachadh a chionn 's gu bheil na roghainnean pearsanta aig cleachdaiche eile innte.",
'ns-specialprotected' => 'Chan ghabh duilleagan sònraichte a dheasachadh.',
'titleprotected' => 'Chaidh an duilleag seo a dhìon o chruthachadh le [[User:$1|$1]].
Seo am mìneachadh: "\'\'$2\'\'".',
'filereadonlyerror' => 'Cha ghabh am faidhle "$1" atharrachadh a chionn \'s gu bheil ionad-tasgaidh fhaidhlichean "$2" ri leughadh a-mhàin.
Thug an rianaire a ghlais e seachad an t-adhbhar a leanas: "$3".',
'invalidtitle-knownnamespace' => 'Tiotal mì-dhligheach leis an namespace "$2" agus an teacsa "$3"',
'invalidtitle-unknownnamespace' => 'Tiotal mì-dhligheach leis an àireamh namespace $1 agus an teacsa "$2"',
'exception-nologin' => 'Chan eil thu air clàradh a-steach',
'exception-nologin-text' => 'Feumaidh tu clàradh a-steach air an uicipeid seo mus urrainn dhut seo a dhèanamh.',

# Virus scanner
'virus-badscanner' => "Droch cho-dhealbhachd: sganair bhìorasan neo-aithnichte: ''$1''",
'virus-scanfailed' => "dh'fhàillig an sganadh (còd $1)",
'virus-unknownscanner' => 'sganair bhìorasan neo-aithnichte:',

# Login and logout pages
'logouttext' => "'''Chaidh do logadh a-mach.'''
'S urrainn dhut leantainn air adhart a' cleachdadh {{SITENAME}} a chleachdadh gun urra no 's urrainn dhut <span class='plainlinks'>[$1 logadh a-steach a-rithist]</span> mar an dearbh-chleachdaiche no mar chleachdaiche eile.
Thoir an aire gum bi coltas air cuide dhe na duilleagan mar gum biodh tu air logadh a-steach gus am falamhaich thu tasgadan a' bhrabhsair agad.",
'welcomeuser' => 'Fàilte ort, $1',
'welcomecreation-msg' => 'Chaidh an cunntas agad a chruthachadh.
Na dìochuimhnich na [[Special:Preferences|roghainnean agad air {{SITENAME}}]] a ghleusadh dhut fhèin.',
'yourname' => 'Ainm-cleachdaiche:',
'yourpassword' => 'Am facal-faire agad',
'yourpasswordagain' => 'Ath-sgrìobh facal-faire',
'remembermypassword' => "Cuimhnich gu bheil mi air logadh a-steach air a' choimpiutair seo (suas gu $1 {{PLURAL:$1|latha|latha|làithean|latha}})",
'yourdomainname' => 'An àrainn-lìn agad:',
'password-change-forbidden' => 'Chan urrainn dhut faclan-faire atharrachadh air an uicipeid seo.',
'externaldberror' => 'Thachair mearachd le dearbhadh an stòir-dhàta air neo chan eil cead agad an cunntas agad air an taobh a-muigh ùrachadh.',
'login' => 'Log a-steach',
'nav-login-createaccount' => 'Log a-steach / cruthaich cunntas',
'loginprompt' => 'Feumaidh briosgaidean a bhith ceadaichte mus dèan thu logadh a-steach do {{SITENAME}}.',
'userlogin' => 'Log a-steach / cruthaich cunntas',
'userloginnocreate' => 'Log a-steach',
'logout' => 'Log a-mach',
'userlogout' => 'Log a-mach',
'notloggedin' => 'Chan eil thu air logadh a-steach',
'nologin' => 'Nach eil cunntas agad fhathast? $1.',
'nologinlink' => 'Cruthaich cunntas',
'createaccount' => 'Cruthaich cunntas ùr',
'gotaccount' => 'A bheil cunntas agad mu thràth? $1.',
'gotaccountlink' => 'Log a-steach',
'userlogin-resetlink' => "Na dhìochuimhnich thu d' ainm is facal-faire?",
'createaccountmail' => "Cleachd facal-faire sealach air thuaiream agus cuir e dhan phost-d a tha 'ga shònrachadh gu h-ìosal",
'createaccountreason' => 'Adhbhar:',
'badretype' => "Chan eil an dà fhacal-faire a chuir thu a-steach a' freagairt ri chèile.",
'userexists' => "Tha an t-ainm-cleachdaiche a chuir thu a-steach 'ga chleachdadh mu thràth.
Nach tagh thu ainm eile?",
'loginerror' => 'Mearachd log a-steach',
'createaccounterror' => 'Cha do ghabh an cunntas a leanas a chruthachadh: $1',
'nocookiesnew' => "Chaidh an cunntas a chruthachadh ach cha do rinn thu logadh a-steach.
Tha {{SITENAME}} a' cleachdadh briosgaidean gus daoine a logadh a-steach.
Chuir thu na briosgaidean à comas.
Cuir am comas iad agus log a-steach leis an ainm-chleachdaiche 's am facal-faire agad an uairsin.",
'nocookieslogin' => "Tha {{SITENAME}} a' cleachdadh briosgaidean gus daoine a logadh a-steach.
Chuir thu briosgaidean à comas.
Cuir an comas iad is feuch ris a-rithist.",
'nocookiesfornew' => "Cha deach an cunntas a chruthachadh oir cha b' urrainn dhuinn a thùs a dhearbhadh.
Dèan cinnteach gu bheil briosgaidean an comas, ath-luchdaich an duilleag seo 's feuch ris a-rithist.",
'noname' => 'Cha do thagh thu ainm-cleachdaiche dligheach.',
'loginsuccesstitle' => 'Rinn thu logadh a-steach',
'loginsuccess' => "'''Rinn thu logadh a-steach air {{SITENAME}} mar \"\$1\".'''",
'nosuchuser' => 'Chan eil cleachdaiche ann air a bheil "$1".
Tha ainmean chleachdaichean mothaichail do litrichean mòra \'s beaga.
Thoir sùil air an litreachadh no [[Special:UserLogin/signup|cruthaich cunntas ùr]].',
'nosuchusershort' => 'Chan eil cleachdaiche ann leis an ainm "$1".
Cuir sùil air an litreachadh.',
'nouserspecified' => 'Tha agad ri ainm-cleachdaiche a chur ann.',
'login-userblocked' => 'Chaidh an cleachdaiche seo a chasgadh. Chan eil logadh a-steach ceadaichte dhaibh.',
'wrongpassword' => 'Chuir thu a-steach facal-faire cearr.
Am feuch thu ris a-rithist?',
'wrongpasswordempty' => 'Cha do chuir thu a-steach facal-faire.
Feuch ris a-rithist.',
'passwordtooshort' => "Feumaidh faclan-faire a bhith {{PLURAL:$1|$1 charactar|$1 charactar|$1 caractaran|$1 caractar}} a dh'fhaid air a' char as lugha.",
'password-name-match' => "Chan fhaod am facal-faire 's an t-ainm-cleachdaiche agad a bhith co-ionnann.",
'password-login-forbidden' => "Tha an t-ainm-cleachdaiche 's am facal-faire seo toirmisgte.",
'mailmypassword' => "Cuir facal-faire ùr thugam air a' phost-dealain",
'passwordremindertitle' => 'Facal-faire sealach ùr airson {{SITENAME}}',
'passwordremindertext' => 'Dh\'iarr cuideigin (\'s mathaid gun do dh\'iarr thusa seo on t-seòladh IP $1) facal-faire ùr airson {{SITENAME}} ($4). Chaidh facal-faire sealach a chruthachadh airson "$2" a tha \'na "$3".
Ma bha sin fa-near dhut, bidh agad ri clàradh a-steach agus facal-faire ùr a thaghadh
an-dràsta fhèin. Falbhaidh an ùine air an fhacal-fhaire sealach agad ann an {{PLURAL:$5|$5 latha|$5 latha|$5 làithean|$5 latha}}.

Ma dh\'iarr cuideigin eile seo no ma chuimhnich thu am facal-faire agad \'s mur eil thu
airson atharrachadh tuilleadh, \'s urrainn dhut an teachdaireachd seo a leigeil seachad
agus leantainn ort leis an t-seann fhacal-faire.',
'noemail' => 'Cha deach post-d a chlàradh airson a\' chleachdaiche "$1".',
'noemailcreate' => 'Feumaidh tu post-d dligheach a chur ann',
'passwordsent' => 'Chaidh facal-faire ùr a chur dhan phost-d a chaidh a chlàradh airson "$1".
Clàraich a-steach a-rithist nuair a gheibh thu e.',
'blocked-mailpassword' => "Chaidh bacadh a chur air an t-seòladh IP agad 's chan eil cead deasachaidh agad agus chan urrainn dhut an gleus a chum aiseag an fhacail-fhaire a chleachdadh gus casg a chur air mì-ghnàthachadh.",
'eauthentsent' => 'Chaidh post-d dearbhaidh a chur dhan phost-d a chaidh ainmeachadh.
Mus dèid post-d sam bith eile a chur dhan chunntas, feumaidh tu leantainn ris an treòrachadh sa phost-d mar dhearbhadh gur ann agadsa a tha an cunntas.',
'throttled-mailpassword' => 'Chaidh post-d a chur airson ath-shuidheachadh facail-fhaire mu thràth {{PLURAL:$1|uair|$1 uair|$1 uairean|$1 uair}} a thìde air ais.
Gus casg a chur air mì-ghnàthachadh, cha chuir sinn ach aon chuimhneachan facail-fhaire gach {{PLURAL:$1|uair|$1 uair|$1 uairean|$1 uair}} a thìde.',
'mailerror' => "Mearachd a' cur post: $1",
'acct_creation_throttle_hit' => "Chruthaich na h-aoighean air an Uici seo {{PLURAL:$1|1 chunntas|$1 chunntas|$1 cunntasan|$1 cunntas}} fon IP agad an-dè agus sin an àireamh as motha a tha ceadaichte. Chan urrainn do dh'aoighean eile on IP seo barrachd chunntasan a chruthachadh air sgàth sin.",
'emailauthenticated' => 'Chaidh an seòladh puist-dhealain agad a dhearbhadh $2 aig $3.',
'emailnotauthenticated' => 'Cha deach am post-d agad a dhearbhadh fhathast.
Cha dèid post-d a chur airson gin dhe na feartan a leanas.',
'noemailprefs' => 'Sònraich post-d sna roghainnean agad gus na feartan seo a chur an comas.',
'emailconfirmlink' => 'Dearbh an seòladh puist-dhealain agad',
'invalidemailaddress' => "Chan urrainn dhuinn gabhail ris an t-seòladh seo a chionn 's gu bheil coltas cearr air.
Cuir a-steach seòladh san fhòrmat cheart no falamhaich an raon sin.",
'cannotchangeemail' => 'Cha ghabh na puist-d a tha co-cheangailte ri cunntas atharrachadh air an uicipeid seo.',
'emaildisabled' => 'Chan urrainn dhut puist-d a chur air an làrach seo.',
'accountcreated' => 'Cunntas cruthaichte',
'accountcreatedtext' => 'Chaidh an cunntas cleachdaiche airson $1 a chruthachadh.',
'createaccount-title' => 'Cruthachadh cunntais airson {{SITENAME}}',
'createaccount-text' => 'Chruthaich cuideigin cunntas airson a\' phost-d agad air {{SITENAME}} ($4) air a bheil "$2", leis an fhacal-fhaire "$3".
Bu chòir dhut clàradh a-steach agus am facal-faire agad atharrachadh gu h-ìosal an-dràsta.

\'S urrainn dhut an teachdaireachd seo a leigeil seachad ma chaidh an cunntas a chruthachadh air mhearachd.',
'usernamehasherror' => 'Chan fhaod hais a bhith ann an ainm cleachdaiche',
'login-throttled' => "Dh'fheuch thu ri clàradh a-steach ro thric o chionn ghoirid.
Fuirich ort mus feuch thu ris a-rithist.",
'login-abort-generic' => "Cha do shoirbhich leat leis a' chlàradh a-steach - Chaidh sgur dheth",
'loginlanguagelabel' => 'Cànan: $1',
'suspicious-userlogout' => "Chaidh d' iarrtas airson clàradh a-mach a dhiùltadh a chionn 's gu bheil coltas gun deach a chur le brabhsair briste no le progsaidh tasglannaidh.",

# Email sending
'php-mail-error-unknown' => 'Mearachd neo-aithichte san fheart mail() aig PHP.',
'user-mail-no-addy' => 'Cha do ghabh am post-d a chur leis nach robh seòladh puist-d ann.',
'user-mail-no-body' => 'Bha bodhaig na teachdaireachd bàn no air leth goirid.',

# Change password dialog
'resetpass' => 'Atharraich am facal-faire',
'resetpass_announce' => "Chlàraich thu a-steach le còd sealach a fhuair thu air a' phost-d.
Gus an clàradh a-steach a choileadh, tha agad ri facal-faire ùr a shuidheachadh an-seo:",
'resetpass_header' => "Atharraich facal-faire a' chunntais",
'oldpassword' => 'Seann fhacal-faire',
'newpassword' => 'Facal-faire ùr',
'retypenew' => 'Ath-sgrìobh am facal-faire ùr',
'resetpass_submit' => "Suidhich am facal-faire 's clàraich a-steach",
'changepassword-success' => "Chaidh am facal-faire agad atharrachadh!
'Gad chlàradh a-steach an-dràsta...",
'resetpass_forbidden' => 'Cha ghabh na faclan-faire atharrachadh',
'resetpass-no-info' => 'Feumaidh tu clàradh a-steach mus dèan thu inntrigeadh dìreach dhan duilleag seo.',
'resetpass-submit-loggedin' => 'Atharraich am facal-faire',
'resetpass-submit-cancel' => 'Sguir dheth',
'resetpass-wrong-oldpass' => "Tha am facal-faire sealach no làithreach mì-dhligheach.
Saoil an do dh'atharraich thu am facal-faire agad mu thràth no an do dh'iarr thu facal-faire sealach ùr?",
'resetpass-temp-password' => 'Facal-faire sealach:',

# Special:PasswordReset
'passwordreset' => 'Ath-shuidhich am facal-faire',
'passwordreset-legend' => 'Ath-shuidhich am facal-faire',
'passwordreset-disabled' => 'Chaidh ath-shuidheachadh nam faclan-faire a chur à comas air an uicipeid seo.',
'passwordreset-username' => 'Ainm-cleachdaiche:',
'passwordreset-domain' => 'Àrainn-lìn:',
'passwordreset-capture' => "A bheil thu airson coimhead air a' phost-d?",
'passwordreset-capture-help' => 'Ma chuireas tu cromag sa bhogsa seo, chì thusa am post-d (leis an fhacal-fhaire sealach) agus gheibh an cleachdaiche e cuideachd.',
'passwordreset-email' => 'Seòladh puist-d:',
'passwordreset-emailtitle' => "Dàta a' chunntais air {{SITENAME}}",
'passwordreset-emailtext-ip' => "Dh'iarr cuideigin (thu fhèin, 's mathaid, on t-seòladh IP $1) am facal-faire airson {{SITENAME}} ($4) ath-shuidheachadh. Tha {{PLURAL:$3|an cunntas|an dà chunntas|na $3 cunntasan|na $3 cunntas}} a leanas co-cheangailte ris a' phost-d seo:

$2

Falbhaidh an ùine air {{PLURAL:$3|an fhacal-fhaire|an $3 fhacal-faire|na $3 faclan-faire|na $3 facal-faire}} sealach seo ann an {{PLURAL:$5|latha|$5 latha|$5 làithean|$5 latha}}.
Bu chòir dhut clàradh a-steach agus facal-faire ùr a thaghadh an-dràsta. Ma dh'iarr cuideigin eile seo no ma chuimhnich thu air an fhacal-fhaire agad 's mur eil thu airson atharrachadh tuilleadh, leig seachad an teachdaireachd seo 's lean ort leis an t-seann fhacal-fhaire.",
'passwordreset-emailtext-user' => "Dh'iarr an cleachdaiche $1 air {{SITENAME}} ath-shuidheachadh an fhacail-fhaire air {{SITENAME}} ($4). Tha {{PLURAL:$3|an cunntas-cleachdaiche|an $3 chunntas-cleachdaiche|na $3 cunntasan-cleachdaiche|na $3 cunntas-cleachdaiche}} a leanas co-cheangailte ris a' phost-d seo:

$2

Falbhaidh an ùine air {{PLURAL:$3|an fhacal-fhaire|an $3 fhacal-faire|na $3 faclan-faire|na $3 facal-faire}} sealach seo ann an {{PLURAL:$5|latha|$5 latha|$5 làithean|$5 latha}}.
Bu chòir dhut clàradh a-steach agus facal-faire ùr a thaghadh an-dràsta. Ma dh'iarr cuideigin eile seo no ma chuimhnich thu air an fhacal-fhaire agad 's mur eil thu airson atharrachadh tuilleadh, leig seachad an teachdaireachd seo 's lean ort leis an t-seann fhacal-fhaire.",
'passwordreset-emailelement' => 'Ainm-cleachdaiche: $1
Facal-faire sealach: $2',
'passwordreset-emailsent' => 'Chaidh post-d airson ath-shuidheachadh an fhacail-fhaire a chur.',
'passwordreset-emailsent-capture' => 'Chaidh post-d a chum ath-shuidheachadh an fhacail-fhaire a chur agus chì thu sin gu h-ìosal.',
'passwordreset-emailerror-capture' => "Chaidh post-d a chum ath-shuidheachadh an fhacail-fhaire a ghintinn agus chì thu sin gu h-ìosal ach cha b' urrainn dhuinn a chur dhan chleachdaiche: $1",

# Special:ChangeEmail
'changeemail' => 'Atharraich am post-d',
'changeemail-header' => "Atharraich cunntas a' phuist-d",
'changeemail-text' => 'Lìon am foirm seo gus am post-d agad atharrachadh. Feumaidh tu am facal-faire agad a chur a-steach a-rithist gus a dhearbhadh.',
'changeemail-no-info' => 'Feumaidh tu clàradh a-steach mus dèan thu inntrigeadh dìreach dhan duilleag seo.',
'changeemail-oldemail' => 'An seòladh puist-d làithreach:',
'changeemail-newemail' => 'An seòladh puist-d ùr:',
'changeemail-none' => '(chan eil gin)',
'changeemail-password' => 'Am facal-faire agad air {{SITENAME}}:',
'changeemail-submit' => 'Atharraich am post-d',
'changeemail-cancel' => 'Sguir dheth',

# Edit page toolbar
'bold_sample' => 'Teacs trom',
'bold_tip' => 'Teacs trom',
'italic_sample' => 'Teacsa Eadailteach',
'italic_tip' => 'Teacsa Eadailteach',
'link_sample' => "Tiotal a' cheangail",
'link_tip' => 'Ceangal am broinn na làraich',
'extlink_sample' => "http://www.example.com tiotal a' cheangail",
'extlink_tip' => 'Ceangal dhan taobh a-muigh (cuimhnich an ro-leasachan http://)',
'headline_sample' => 'Teacsa ceann-loidhne',
'headline_tip' => 'Ceann-loidhne ìre 2',
'nowiki_sample' => 'Cuir a-steach teacsa gun fhòrmatadh an-seo',
'nowiki_tip' => 'Leig seachad fòrmatadh uici',
'image_sample' => 'Eisimpleir.jpg',
'image_tip' => 'Faidhle air a leabachadh',
'media_sample' => 'Eisimpleir.ogg',
'media_tip' => 'Ceangal faidhle',
'sig_tip' => "D' ainm sgrìobhte le stampa-ama",
'hr_tip' => 'Loidhne rèidh (na cleachd ro thric e)',

# Edit pages
'summary' => 'Gearr-chunntas:',
'subject' => 'Cuspair/ceann-loidhne:',
'minoredit' => 'Seo mùthadh beag',
'watchthis' => 'Cum sùil air an duilleag seo',
'savearticle' => 'Sàbhail an duilleag',
'preview' => 'Ro-shealladh',
'showpreview' => 'Seall an ro-shealladh',
'showlivepreview' => 'Ro-shealladh beò',
'showdiff' => 'Seall na mùthaidhean',
'anoneditwarning' => "'''Rabhadh:''' Chan eil thu air logadh a-steach.
Thèid an seòladh IP agad a chlàrachadh ann an eachdraidh na duilleige seo.",
'anonpreviewwarning' => "''Chan eil thu air clàradh a-steach. Ma nì thu sàbhaladh, thèid an seòladh IP agad a chlàradh ann an eachdraidh deasachadh na duilleige seo.''",
'missingsummary' => "'''Cuimhnich:''' Cha dug thu seachad gearr-chunntas air na dh'atharraich thu.
Ma bhriogas tu air \"{{int:savearticle}}\" a-rithist, thèid na dheasaich thu a shàbhaladh as aonais gearr-chunntais.",
'missingcommenttext' => 'Cuir a-steach beachd gu h-ìosal.',
'missingcommentheader' => "'''Cuimhnich:''' Cha dug thu seachad cuspair/ceann airson a' bheachd seo.
Ma bhriogas tu air \"{{int:savearticle}}\" a-rithist, thèid na dheasaich thu a shàbhaladh as aonais.",
'summary-preview' => "Ro-shealladh a' ghearr-chunntais:",
'subject-preview' => "Ro-shealladh air a' chuspair/air a' cheann:",
'blockedtitle' => 'Tha an cleachdair air a bhacadh',
'blockedtext' => "''Chaidh an t-ainm-cleachdaiche no an seòladh IP agad a bhacadh.'''

'S e \$1 a chur am bacadh seo ort.
Thug iad an cèill gun do rinn iad sinn air sgàth an adhbhair seo: ''\$2''.

* Toiseach a' bhacaidh: \$8
* Deireadh a' bhacaidh: \$6
* An neach air a bheil am bacadh: \$7

'S urrainn dhut fios a chur gu \$1 no [[{{MediaWiki:Grouppage-sysop}}|rianair]] eile gus am bacadh seo a dheasbad.
Chan urrainn dhut am feart \"Cuir post-d dhan chleachdaiche seo\" a chleachdadh ach ma tha seòladh puist-d dligheach ann an [[Special:Preferences|roghainnean a' chunntais agad]] agus mura deach bacadh a chur air a chleachdadh.
'S e \$3 an seòladh IP làithreach agus agus 's e #\$5 ID a' bhacaidh.
Thoir iomradh air a' mhion-fhiosrachadh gu h-àrd ma chuireas tu ceist sam bith mu dhèidhinn.",
'autoblockedtext' => "''Chaidh an seòladh IP agad a bhacadh gu fèin-obrachail a chionn 's gun deach a chleachdadh le cuideigin eile a chaidh a bhacadh le \$1.'''
Thug iad an cèill gun do rinn iad sinn air sgàth an adhbhair seo: 

:''\$2''.

* Toiseach a' bhacaidh: \$8
* Deireadh a' bhacaidh: \$6
* An neach air a bheil am bacadh: \$7

'S urrainn dhut fios a chur gu \$1 no [[{{MediaWiki:Grouppage-sysop}}|rianair]] eile gus am bacadh seo a dheasbad.

Dh'fhaoidte nach urrainn dhut am feart \"Cuir post-d dhan chleachdaiche seo\" a chleachdadh ach ma tha seòladh puist-d dligheach ann an [[Special:Preferences|roghainnean a' chunntais agad]] agus mura deach bacadh a chur air a chleachdadh.

'S e \$3 an seòladh IP làithreach agus agus 's e #\$5 ID a' bhacaidh.
Thoir iomradh air a' mhion-fhiosrachadh gu h-àrd ma chuireas tu ceist sam bith mu dhèidhinn.",
'blockednoreason' => 'cha deach adhbhar a shònrachadh',
'whitelistedittext' => 'Feumaidh tu $1 mus urrainn dhut duilleagan a dheasachadh.',
'confirmedittext' => "Feumaidh tu am post-d agad a dhearbhadh mus urrainn dhut duilleagan a dheasachadh.
Suidhich is dearbhaich am post-d agad ann an [[Special:Preferences|roghainnean a' chleachdaiche]]",
'nosuchsectiontitle' => 'Cha ghabh an earrann a lorg',
'nosuchsectiontext' => "Dh'fheuch thu ri earrann a dheasachadh nach eil ann.
Dh'fhaoidte gun deach a ghluasad no a sguabadh às fhad 's a bha thu a' coimhead air an duilleag.",
'loginreqtitle' => 'Feumaidh tu clàradh a-steach',
'loginreqlink' => 'log a-steach',
'loginreqpagetext' => 'Feumaidh tu $1 mus urrainn dhut coimhead air duilleagan eile.',
'accmailtitle' => 'Facal-faire air a chur.',
'accmailtext' => "Chaidh facal-faire a chruthachadh air thuaiream airson [[User talk:$1|$1]] 's a chur gu $2.

Gabhaidh am facal-faire airson a' chunntais ùir seo atharrachadh air an fo ''[[Special:ChangePassword|atharraich facal-faire]]'' as dèidh do chleachdaiche logadh a-steach.",
'newarticle' => '(Ùr)',
'newarticletext' => "Lean thu ri ceangal gu duilleag nach eil ann fhathast.
Cuir teacs sa bhogsa gu h-ìosal gus an duilleag seo a chruthachadh (seall air [[{{MediaWiki:Helppage}}|duilleag na cobharach]] airson barrachd fiosrachaidh).
Mura robh dùil agad ris an duilleag seo a ruigsinn, briog air a' phutan '''air ais''' 'nad bhrabhsair.",
'anontalkpagetext' => "----''Seo an duilleag deasbaireachd aig cleachdaiche gun urra nach do chruthaich cunntas fhathast no nach eil 'ga chleachdadh.
Feumaidh sinn an àireamh IP aca a chleachdadh air sgàth sin.
Faodadh grunn chleachdaichean seòladh IP mar a chleachdadh còmhla.
Mas e cleachdaiche gun urra a tha annad 's ma tha thu dhen bheachd nach eil na beachdan seo a' buntainn riut, nach [[Special:UserLogin/signup|clàraich thu]] no [[Special:UserLogin|clàraich a-steach]] gus bùrach mar seo a sheachnadh san àm ri teachd?''",
'noarticletext' => 'Chan eil teacsa sam bith anns an duilleag seo an-dràsta.
\'S urrainn dhut [[Special:Search/{{PAGENAME}}|an tiotal seo a lorg]] ann an duilleagan eile,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} na logaichean co-cheangailte a rannsachadh],
no [{{fullurl:{{FULLPAGENAME}}|action=edit}} an duilleag seo a dheasachadh]</span>.',
'noarticletext-nopermission' => 'Chan eil teacsa sam bith san duilleag seo an-dràsta.
\'S urrainn dhut [[Special:Search/{{PAGENAME}}|tiotal na duilleige seo a lorg]] ann an duilleagan eile, no <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} na logaichean co-cheangailte a rannsachadh]</span> ach chan eil cead agad an duilleag seo a chruthachadh.',
'missing-revision' => 'Chan eil mùthadh #$1 na duilleige "{{PAGENAME}}" ann.

Mar is trice, tachraidh seo ma leanas tu ceangal san eachdraidh a tha fìor aosta \'s a tha a\' dol gu duilleag a chaidh a sguabadh às.
Gheibh thu mion-fhiosrachadh ann an [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} loga nan duilleagan a chaidh a sguabadh às].',
'userpage-userdoesnotexist' => 'Chan e cunntas clàraichte a tha ann an "$1".
Dèan cinnteach gu bheil thu airson an duilleag seo a chruthachadh/dheasachadh.',
'userpage-userdoesnotexist-view' => 'Cha deach an cunntas cleachdaiche "$1" a chlàradh.',
'blocked-notice-logextract' => "Tha an cleachdaiche seo air a bhacadh an-dràsta fhèin.
Chì thu loga a' bhacaidh mu dheireadh gu h-ìosal mar fhiosrachadh dhut:",
'clearyourcache' => "'''An aire:''' As dèidh dhut sàbhaladh, 's mathaid gum bi agad tasgadan a' bhrabhsair agad a chur air gleus mus fhaic thu na dh'atharraich thu.
* '''Firefox / Safari:''' Cum 'shìos 'Shift'' is briog air ''Ath-luchdaich' no brùth ''Ctrl-F5'' no ''Ctrl-R'' (''⌘-R'' air Mac)
* '''Google Chrome:''' Brùth ''Ctrl-Shift-R'' (''⌘-Shift-R'' air Mac)
* '''Internet Explorer:''' Cum shìos ''Ctrl'' is briog air ''Ath-nuadhaich'' no brùth ''Ctrl-F5''
* '''Opera:''' Falamhaich an tasgadan ann an ''Innealan → Roghainnean''",
'usercssyoucanpreview' => "'''Gliocas:''' Cleachd am putan \"{{int:showpreview}}\" airson an CSS agad a chur fo dheuchainn mus sàbhail thu e.",
'userjsyoucanpreview' => "'''Gliocas:''' Cleachd am putan \"{{int:showpreview}}\" gus an JavaScript ùr agad a chur fo dheuchainn mus sàbhail thu e.",
'usercsspreview' => "'''Cuimhnich nach e seo ach ro-shealladh air a' CSS chleachdaiche agad.'''
'''Cha deach a shàbhaladh fhathast!''''",
'userjspreview' => "'''Cuimhnich nach e seo ach ro-shealladh/deuchainn air a' JavaScript agad.'''
'''Cha deach a shàbhaladh fhathast!''''",
'sitecsspreview' => "'''Cuimhnich nach e seo ach ro-shealladh air a' CSS agad.'''
'''Cha deach a shàbhaladh fhathast!''''",
'sitejspreview' => "'''Cuimhnich nach e seo ach ro-shealladh air còd a' JavaScript agad.'''
'''Cha deach a shàbhaladh fhathast!''''",
'userinvalidcssjstitle' => "'''Rabhadh:''' Chan eil an craiceann \"\$1\" ann.
Cleachdaidh duilleagan gnàthaichte .css agus .js tiotal ann an litrichean beaga, m.e. {{ns:user}}:Foo/vector.css seach {{ns:user}}:Foo/Vector.css.",
'updated' => '(Air ùrachadh)',
'note' => "'''An aire:'''",
'previewnote' => "'''Cuimhnich nach eil ann ach ro-shealladh.'''
Cha deach na mùthaidhean agad a shàbhaladh fhathast!",
'continue-editing' => 'Rach gun raon deasachaidh',
'previewconflict' => "Tha an ro-shealladh seo a' sealltainn dhut an teacsa san raon teacsa gu h-àrd mar a nochdas e ma shàbhaileas tu an-dràsta.",
'session_fail_preview' => "'''Duilich! Cha b' urrainn dhuinn na dheasaich thu a làimhseachadh air sgàth call dàta an t-seisein.'''
Nach fheuch thu ris a-rithist?
Mur obraich e fhathast, feuch is [[Special:UserLogout|clàraich a-mach]] is a-steach a-rithist an uairsin.",
'session_fail_preview_html' => "'''Duilich! Cha b' urrainn dhuinn na dheasaich thu a làimhseachadh air sgàth call dàta an t-seisein.'''

''A chionn 's gun do chuir {{SITENAME}} HTML amh an comas, tha an ro-shealladh falaichte mar dhìon an aghaidh ionnsaighean JavaScript.''

'''Mas e deasachadh dligheach a tha seo, feuch ris a-rithist.'''
Mur obraich e fhathast, feuch is [[Special:UserLogout|clàraich a-mach]] is a-steach a-rithist an uairsin.",
'token_suffix_mismatch' => "'''Dhiùlt sinn na dheasaich thu a chionn 's gun do chuir an cliant agad na caractaran puingeachaidh tro chèile san tòcan deasachaidh.'''
Dhiùlt sinn na dheasaich thu air eagal 's gun coirbeadh e teacsa na duilleige.
Tachraidh seo uaireannan ma chleachdar seirbheis-lìn progsaidh gun urra a tha làn de mhearachdan.",
'edit_form_incomplete' => "'''Cha do ràinig cuid dhen fhoirm deasachaidh am frithealaichte; dèan cinnteach gu bheil gach deasachadh agad slàn is feuch ris a-rithist.'''",
'editing' => "A' deasachadh $1",
'creating' => "A' cruthachadh $1",
'editingsection' => "A' deasachadh $1 (earrann)",
'editingcomment' => "A' deasachadh $1 (earrann ùr)",
'editconflict' => 'Còmhstri deasachaidh: $1',
'explainconflict' => "Tha cuideigin eile air an duilleag seo a mhùthadh on a thòisich thu fhèin air a dheasachadh.
Tha am bogsa teacsa gu h-àrd a' nochdadh na duilleige mar a tha i an-dràsta.
Tha na mùthaidhean agadsa sa bhogsa gu h-ìosal.
Bidh agad ris na mùthaidhean agad fhilleadh a-steach san teacsa làithreach.
Cha dèid '''ach an teacsa gu h-àrd''' a shàbhaladh nuair a bhriogas tu air \"{{int:savearticle}}\".",
'yourtext' => 'An teacsa agad',
'storedversion' => 'Lethbhreac taisgte',
'nonunicodebrowser' => "'''Rabhadh: Chan eil am brabhsair agad co-chòrdail le Unicode.'''
Chuir sinn gleus air dòigh dhut a nì cinnteach gun urrainn dhut duilleagan a shàbhaladh gu tèarainte: Nochdaidh caractaran taobh a-muigh ASCII mar chòd sia-dheicheach sa bhogsa deasachaidh.",
'editingold' => "'''RABHADH: Tha thu a' deasachadh lethbhreac seann-aimsireil na duilleige seo.
Ma shàbhalas tu seo, thèid gach mùthadh air chall a rinneadh a-mach on mhùthadh seo.'''",
'yourdiff' => 'Caochlaidhean',
'copyrightwarning' => "Thoir an aire gu bheilear a' tuigsinn gu bheil gach rud a chuireas tu ri {{SITENAME}} air a leigeil mu sgaoil fo $2 (see $1 airson mion-fhiosrachadh).
Mura bi thu toilichte 's daoine eile a' deasachadh gun tròcair na sgrìobh tu 's 'ga sgaoileadh mar a thogras iad, na cuir an-seo e.<br />
Tha thu a' toirt geall cuideachd gun do sgrìobh thu fhèin seo no gun do rinn thu lethbhreac dheth o àrainn phoblach no tùs saor coltach ris.
'''Na cuir ann rudan fo chòir lethbhric gun chead!'''",
'copyrightwarning2' => "Ged a thatar gur moladh {{SITENAME}} a chruthachadh, a mheudachadh, is a leasachadh, thèid droch dheasaicheidhean a chur air imrich gu luath.
Mur eil thu ag iarraidh an sgrìobhaidh agad a dheasaichear is a sgaoilear le càch, na cuir e.<br />
Ma dh'fhoilleachas tu rudeigin an seo, bidh tu a' dearbhadh gun do sgrìobh thu fhèin e, no gur ann às an raon phòballach a thàinig e; thoir aire '''nach eil''' sin a' gabhail a-staigh duilleagan-lìn mar as àbhaist (seall $1 airson barrachd fiosrachaidh). <br />
'''NA CLEACHDAIBH SAOTHAIR FO DHLIGHE-SGRÌOBHAIDH GUN CHEAD!'''",
'longpageerror' => "Mearachd: Tha an teacsa a chur thu thugainn {{PLURAL:$1 kilobyte|$1 kilobytes}} a dh'fhaid is tha sin nas fhaide na tha ceadaichte ({{PLURAL:$2 kilobyte|$2 kilobytes}}).'''
Cha ghabh a shàbhaladh.",
'readonlywarning' => "'''Rabhadh: Chaidh an stòr-dàta a ghlasadh a chum obair-ghlèidhidh agus chan urrainn dhut na dheasaich thu a shàbhaladh an-dràsta fhèin.'''
'S mathaid gum b' fheairrde dhut lethbhreac a dhèanamh dhen teacsa agus a shàbhaladh ann am faidhle ach an urrainn dhut a chleachdadh as a dhèidh seo.

Seo am mìneachadh a thug an rianaire a ghlais e: $1",
'protectedpagewarning' => "'''Rabhadh: Chaidh an duilleag seo a dhìon 's chan urrainn ach dhan fheadhainn aig a bheil ùghdarras rianaire a dheasachadh.'''
Chì thu an clàr mu dheireadh san loga mar fhiosrachadh dhut gu h-ìosal:",
'semiprotectedpagewarning' => "'''An aire:''' Chaidh an duilleag seo a dhìon 's chan fhaod ach cleachdaichean clàraichte a dheasachadh.
Seo an rud mu dheireadh san loga mar fhiosrachadh dhut:",
'cascadeprotectedwarning' => "'''Rabhadh:''' Chaidh an duilleag seo a dhìon 's chan fhaod ach rianairean a dheasachadh a chionn 's gun robh e am broinn {{PLURAL:$1|na duilleige|nan duilleagan}} a leanas a tha cascade-protected.",
'titleprotectedwarning' => "'''Rabhadh: Chaidh an duilleag seo a dhìon 's feumar [[Special:ListGroupRights|còraichean sònraichte]] gus a dheasachadh.'''
Seo an rud mu dheireadh san loga mar fhiosrachadh dhut:",
'templatesused' => "Tha {{PLURAL:$1|teamplaid|theamplaid|teamplaidean|teamplaid}} 'gan cleachdadh air an duilleag seo:",
'templatesusedpreview' => "Tha {{PLURAL:$1|1 teamplaid 'ga cleachdadh|$1 theamplaid 'gan cleachdadh|$1 teamplaidean 'gan cleachdadh|$1 teamplaid 'gan cleachdadh}} san ro-shealladh seo:",
'templatesusedsection' => "Tha {{PLURAL:$1|$1 teamplaid 'ga cleachdadh|$1 theamplaid 'gan cleachdadh|$1 teamplaidean 'gan cleachdadh|$1 teamplaid 'gan cleachdadh}} san earrann seo:",
'template-protected' => '(air a dhìon)',
'template-semiprotected' => '(air a leth-dhìon)',
'hiddencategories' => "Tha an duilleag seo 'na ball de {{PLURAL:$1|1 roinn-seòrsa fhalaichte|$1 roinn-seòrsa fhalaichte|$1 roinnean-seòrsa falaichte|$1 roinn-seòrsa fhalaichte}}:",
'nocreatetext' => "Chuir {{SITENAME}} bacadh air cruthachadh de dhuilleagan ùra.
'S urrainn dhut tilleadh is duilleag a tha ann mu thràth a dheasachadh no [[Special:UserLogin|clàradh a-steach no cunntas a chruthachadh]].",
'nocreate-loggedin' => 'Chan eil cead agad duilleagan ùra a chruthachadh.',
'sectioneditnotsupported-title' => 'Chan eil taic ri deasachadh earrannan',
'sectioneditnotsupported-text' => 'Chan eil taic ri deasachadh earrannan air an duilleag seo.',
'permissionserrors' => "Meareachd leis a' chead",
'permissionserrorstext' => 'Chan eil cead agad sin a dhèanamh air sgàth {{PLURAL:$1|an adhbhair|an $1 adhbhar|nan $1 adhbharan|nan $1 adhbhar}} a leanas:',
'permissionserrorstext-withaction' => 'Chan eil cead agad airson "$2" air sgàth {{PLURAL:$1|an $1 adhbhair|an $1 adhbhar|nan $1 adhbharan|nan $1 adhbhar}} a leanas:',
'recreate-moveddeleted-warn' => "'''Rabhadh: Tha thu gu bhith ath-chruthachadh duilleag a chaidh a sguabadh às roimhe.'''

Saoil am bu chòir dhut leantainn air adhart le deasachadh na duilleige?.
Seo dhut loga an sguabaidh às agus a' ghluasaid mar fhiosrachadh dhut:",
'moveddeleted-notice' => "Chaidh an duilleag seo a sguabadh às.
Chì thu loga an sguabaidh às agus a' ghluasaid gu h-ìosal mar fhiosrachadh dhut.",
'log-fulllog' => 'Seall an loga slàn',
'edit-hook-aborted' => 'Sguireadh dhen deasachadh ri linn dubhan.
Cha deach adhbhar a thoirt seachad.',
'edit-gone-missing' => "Cha b' urrainn dhuinn an duilleag ath-nuadhachadh.
Tha coltas gun deach a sguabadh às.",
'edit-conflict' => 'Còmhstri deasachaidh.',
'edit-no-change' => "Chaidh an obair-dheasachaidh agad a leigeil seachad a chionn 's nach do dh'atharraich thu dad.",
'edit-already-exists' => "Cha b' urrainn dhuinn an duilleag ùr a chruthachadh.
Tha e ann mu thràth.",
'defaultmessagetext' => 'Teacsa bunaiteach na teachdaireachd',
'content-failed-to-parse' => "Dh'fhàillig parsadh susbaint $2 airson modail $1: $3",
'invalid-content-data' => 'Dàta susbaint a tha mì-dhligheach',
'content-not-allowed-here' => 'Chan eil susbaint "$1" ceadaichte air an duilleag [[$2]]',
'editwarning-warning' => 'Ma dh\'fhàgas tu an duilleag seo, faodaidh gun caill thu mùthadh sam bith a rinn thu.
Ma tha thu air logadh a-steach, \'s urrainn dhut an rabhadh seo a chur dheth san roinn "Deasachadh" sna roghainnean agad.',

# Content models
'content-model-wikitext' => 'wikitext',
'content-model-text' => 'teacsa lom',
'content-model-javascript' => 'JavaScript',
'content-model-css' => 'CSS',

# Parser/template warnings
'expensive-parserfunction-warning' => "'''Rabhadh:''' Tha cus expensive parser function calls san duilleag seo.

Bu chòir nas lugha na $2 {{PLURAL:$2|call|calls}} a bhith ann ach tha {{PLURAL:$1|$1 call|$1 calls}} ann.",
'expensive-parserfunction-category' => 'Duilleagan le cus expensive parser function calls',
'post-expand-template-inclusion-warning' => "'''Rabhadh:''' Tha meud na teamplaide ro mhòr.
Cha dèid cuid dhith a ghabhail a-steach.",
'post-expand-template-inclusion-category' => "Duilleagan far a bheil meud nan teamplaidean a' dol thairis air na tha ceadaichte",
'post-expand-template-argument-warning' => "'''Rabhadh:''' Tha aon argamaid teamplaid air a' char as lugha air an duilleag seo aig a bheil meud leudachaidh ro mhòr.
Chaidh na h-argamaidean sinn a leigeil seachad.",
'post-expand-template-argument-category' => 'Duilleagan air an deach argamaidean teamplaidean fhàgail às',
'parser-template-loop-warning' => 'Mhothaicheadh do lùb teamplaid: [[$1]]',
'parser-unstrip-loop-warning' => 'Mhothaich sinn do lùb unstrip',

# Account creation failure
'cantcreateaccounttitle' => 'Cha ghabh an cunntas a chruthachadh',

# History pages
'viewpagelogs' => 'Seall logaichean na duilleige seo',
'nohistory' => 'Chan eil eachdraidh deasachaidh aig an duilleag seo.',
'currentrev' => 'Lethbhreac làithreach',
'currentrev-asof' => 'Am mùthadh mu dheireadh on $1',
'revisionasof' => 'Mùthadh on $1',
'revision-info' => 'Lèirmheas mar a bha e $1 le $2',
'previousrevision' => '← Mùthadh nas sine',
'nextrevision' => 'Mùthadh nas ùire →',
'currentrevisionlink' => 'Am mùthadh mu dheireadh',
'cur' => 'làith',
'next' => 'ath',
'last' => 'roimhe',
'page_first' => 'Toiseach',
'page_last' => 'Deireadh',
'histlegend' => "Taghadh nan diofar: comharraich bogsaichean rèidio nam mùthaidhean gus coimeas a dhèanamh agus put Enter no am putan gu h-ìosal.<br />
Mìneachadh: '''({{int:cur}})''' = an diofar eadar e 's am mùthadh as ùire, '''({{int:last}})''' = an diofar eadar e 's am mùthadh roimhe, '''{{int:minoreditletter}}''' = deasachadh beag.",
'history-fieldset-title' => 'An eachdraidh brabhsaidh',
'history-show-deleted' => 'Na chaidh sguabadh às a-mhàin',
'histfirst' => 'as sine',
'histlast' => 'as ùire',
'historysize' => '({{PLURAL:$1|1 byte|$1 bytes}})',
'historyempty' => '(falamh)',

# Revision feed
'history-feed-title' => 'Eachdraidh nam mùthaidhean',
'history-feed-description' => 'Eachdraidh nam mùthaidhean airson na duilleige seo air an uici',
'history-feed-item-nocomment' => '$1 $2',
'history-feed-empty' => "Chan eil an duilleag a dh'iarr thu ann.
Dh'fhaoidte gun deach a sguabadh às an uici no gun deach ainm ùr a chur air.
Feuch is [[Special:Search|lorg duilleagan ùra iomachaidh air an uici]]",

# Revision deletion
'rev-deleted-comment' => '(chaidh gearr-chunntas an deasachaidh a thoirt air falbh)',
'rev-deleted-user' => '(chaidh an t-ainm-cleachdaiche a thoirt air falbh)',
'rev-deleted-event' => '(chaidh gnìomh an loga a thoirt air falbh)',
'rev-delundel' => 'seall/falaich',
'rev-showdeleted' => 'seall',
'revdelete-selected' => "'''{{PLURAL:$2|Lèirmheas|Lèirmheasan}} de [[:$1]] a thagh thu:'''",
'logdelete-selected' => "'''{{PLURAL:$1|An tachartas loga|Na tachartasan loga}} a thagh thu:'''",
'revdelete-hide-user' => 'Falaich ainm-cleachdaiche/seòladh IP an deasaiche',
'revdelete-radio-same' => '(na atharraich)',
'revdelete-radio-set' => 'Dèan seo',
'revdelete-radio-unset' => 'Na dèan seo',
'revdelete-log' => 'Adhbhar:',
'revdelete-submit' => 'Cuir air {{PLURAL:$1|an lèirmheas|na lèirmheasan}} a thagh thu',
'revdel-restore' => 'mùth follaiseachd',
'revdel-restore-deleted' => 'mùthaidhean a chaidh a sguabadh às',
'revdel-restore-visible' => 'mùthaidhean faicsinneach',
'pagehist' => 'Eachdraidh na duilleige',
'revdelete-otherreason' => 'Adhbhar eile/a bharrachd:',
'revdelete-reasonotherlist' => 'Adhbhar eile',
'revdelete-edit-reasonlist' => 'Deasaich adhbharan an sguabaidh às',
'revdelete-offender' => "Ùghdar a' mhùthaidh:",

# History merging
'mergehistory-from' => 'An duilleag thùsail:',
'mergehistory-reason' => 'Adhbhar:',

# Merge log
'revertmerge' => 'Dì-aontaich',

# Diffs
'history-title' => 'Eachdraidh nam mùthaidhean aig "$1"',
'difference-multipage' => '(An diofar eadar na duilleagan)',
'lineno' => 'Loidhne $1:',
'compareselectedversions' => 'Dèan coimeas eadar na mùthaidhean a thagh thu',
'showhideselectedversions' => 'Seall/Falaich na lèirmheasan a thagh thu',
'editundo' => 'neo-dhèan',
'diff-multi' => '({{PLURAL:$1|Aon lèirmheas eadar-mheadhanach||$1 lèirmheasan eadar-mheadhanach|$1 lèirmheas eadar-mheadhanach}} le {{PLURAL:$2|aon chleachdaiche|$2 chleachdaiche|$2 cleachdaichean|$2 cleachdaiche}} gun sealltainn)',
'diff-multi-manyusers' => '({{PLURAL:$1|Aon lèirmheas eadar-mheadhanach||$1 lèirmheasan eadar-mheadhanach|$1 lèirmheas eadar-mheadhanach}} le {{PLURAL:$2|aon chleachdaiche|$2 chleachdaiche|$2 cleachdaichean|$2 cleachdaiche}} gun sealltainn)',

# Search results
'searchresults' => 'Toraidhean rannsachaidh',
'searchresults-title' => 'Lorg "$1" am broinn nan toraidhean',
'searchresulttext' => 'Airson barrachd fiosrachaidh mu rannsachadh {{SITENAME}}, cuir sùil air [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle' => 'Lorg thu \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|gach duilleag a tha a\' tòiseachadh le "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|gach duilleag a tha a\' ceangal ri "$1"]])',
'searchsubtitleinvalid' => "Lorg thu airson '''$1'''",
'toomanymatches' => 'Fhuaras cus thoraidhean, feuch ceist eile',
'titlematches' => "Tiotalan dhuilleagan a tha a' maidseadh",
'notitlematches' => "Chan eil tiotal de dhuilleag sam bith a' freagairt ris",
'notextmatches' => "Chan eil tiotal de dhuilleag sam bith a' freagairt ris",
'prevn' => 'an {{PLURAL:$1|$1}} roimhe',
'nextn' => 'an ath {{PLURAL:$1|$1}}',
'prevn-title' => '$1 {{PLURAL:$1|toradh|thoradh|toraidhean|toradh}} roimhe',
'nextn-title' => 'An ath $1 {{PLURAL:$1|toradh|thoradh|toraidhean|toradh}}',
'shown-title' => 'Seall $1 {{PLURAL:$1|toradh|thoradh|toraidhean|toradh}} air gach duilleag',
'viewprevnext' => 'Seall ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend' => 'Roghainnean luirg',
'searchmenu-exists' => "'''Tha duilleag air a bheil \"[[:\$1]]\" air an uicipeid seo.'''",
'searchmenu-new' => "'''Cruthaich an duilleag \"[[:\$1]]\" air an uicipeid seo!'''",
'searchmenu-prefix' => '[[Special:PrefixIndex/$1|Rùraich duilleagan aig a bheil an ro-leasachan seo]]',
'searchprofile-articles' => 'Duilleagan susbainte',
'searchprofile-project' => "Duilleagan nan cobharach 's nam pròiseactan",
'searchprofile-images' => 'Ioma-mheadhanan',
'searchprofile-everything' => 'Gach rud',
'searchprofile-advanced' => 'Adhartach',
'searchprofile-articles-tooltip' => 'Lorg ann an $1',
'searchprofile-project-tooltip' => 'Lorg ann an $1',
'searchprofile-images-tooltip' => 'Lorg faidhlichean',
'searchprofile-everything-tooltip' => "Lorg am broinn susbaint sam bith (a' gabhail a-steach nan duilleagan deasbaireachd)",
'searchprofile-advanced-tooltip' => 'Lorg am broinn ainm-spàsan gnàthaichte',
'search-result-size' => '$1 ({{PLURAL:$2 fhacal|$2 fhacal|$2 faclan|$2 facal}})',
'search-result-category-size' => '{{PLURAL:$1|1 bhall|$1 bhall|$1 bhuill|$1 ball}} ({{PLURAL:$2|1 fho-roinn|$2 fho-roinn|$2 fo-roinnean|$2 fo-roinn}}, {{PLURAL:$3|1 fhaidhle|$3 fhaidhle|$3 faidhlichean|$3 faidhle}})',
'search-result-score' => 'Buntainneas: $1%',
'search-redirect' => '(ag ath-sheòladh $1)',
'search-section' => '(earrann $1)',
'search-suggest' => 'An e na leanas a bha fa-near dhut: $1',
'search-interwiki-caption' => 'Pròiseactan co-cheangailte',
'search-interwiki-default' => 'Toraidhean $1:',
'search-interwiki-more' => '(barrachd)',
'search-relatedarticle' => 'Co-cheangailte',
'mwsuggest-disable' => 'Cuir na molaidhean luirg à comas',
'searcheverything-enable' => 'Seall anns na namespaces air fad',
'searchrelated' => 'co-cheangailte',
'searchall' => 'a h-uile',
'showingresults' => "A' nochdadh suas gu $1 {{PLURAL:$1|$1 toradh|$1 thoradh|$1 toraidhean|$1 toradh}} gu h-ìosal a' tòiseachadh le #'''$2'''.",
'showingresultsnum' => "A' nochdadh '''$3''' {{PLURAL:$3|$3 toradh|$3 thoradh|$3 toraidhean|$3 toradh}} gu h-ìosal a' tòiseachadh le #'''$2'''.",
'showingresultsheader' => "{{PLURAL:$5|Toradh '''$1''' à '''$3'''|Toraidhean '''$1 - $2''' of '''$3'''}} airson '''$4'''",
'nonefound' => "'''Aire''': Chan dèid ach cuid dhe na namespaces a lorg a ghnàth.
Feuch ri ''all:'' a chuir air beulaibh an iarrtais agad gus rannsachadh a dhèanamh am broinn na susbainte gu lèir (a' gabhail a-steach nan duilleagan conaltraidh, teamplaidean is msaa), no cleachd an namespace a bha thu ag iarraidh mar ro-leasachan.",
'search-nonefound' => "Cha do fhreagair toradh sam bith ri d' iarrtas.",
'powersearch' => 'Rannsachadh adhartach',
'powersearch-legend' => 'Rannsachadh adhartach',
'powersearch-ns' => 'Lorg ann an namespaces:',
'powersearch-redir' => 'Seall ath-sheòlaidhean',
'powersearch-field' => 'Lorg',
'powersearch-togglelabel' => 'Sgrùd:',
'powersearch-toggleall' => 'Na h-uile',
'powersearch-togglenone' => 'Chan eil gin',
'search-external' => 'Lorg air an taobh a-muigh',
'searchdisabled' => "Tha lorg air {{SITENAME}} à comas.
'S urrainn dhut lorg a dhèanamh air Google san eadar-àm.
Faodaidh gum bi inneacsan susbaint {{SITENAME}} tuilleadh 's sean ge-tà.",

# Preferences page
'preferences' => 'Roghainnean',
'mypreferences' => 'Na roghainnean agam',
'prefs-edits' => 'Co mheud deasachadh:',
'prefsnologin' => 'Chan eil thu air clàradh a-steach',
'prefsnologintext' => 'Feumaidh tu <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} clàradh a-steach]</span> mus urrainn dhut roghainnean cleachdaiche a chur air gleus.',
'changepassword' => 'Atharraich facal-faire',
'prefs-skin' => 'Bian',
'skin-preview' => 'Ro-shealladh',
'datedefault' => 'Gun roghainnean',
'prefs-beta' => 'Feartan Beta',
'prefs-datetime' => 'Ceann-là is àm',
'prefs-labs' => 'Feartan nan deuchainn-lannan',
'prefs-user-pages' => "Duilleagan a' chleachdaiche",
'prefs-personal' => "Pròifil a' chleachdaiche",
'prefs-rc' => 'Mùthaidhean ùra',
'prefs-watchlist' => 'An clàr-faire',
'prefs-watchlist-days' => "Co mheud latha a sheallar air a' chlàr-fhaire:",
'prefs-watchlist-days-max' => "{{PLURAL:$1|latha|latha|làithean|latha}} air a' char as motha",
'prefs-resetpass' => 'Atharraich am facal-faire',
'prefs-changeemail' => 'Atharraich am post-d',
'prefs-setemail' => 'Suidhich seòladh puist-d',
'prefs-email' => "Roghainnean a' phuist-d",
'prefs-rendering' => 'Coltas',
'saveprefs' => 'Sàbhail',
'resetprefs' => 'Falamhaich atharrachaidhean nach deach a shàbhaladh fhathast',
'restoreprefs' => 'Aisig na roghainnean bunaiteach uile',
'prefs-editing' => "A' deasachadh",
'rows' => 'Sreathan',
'columns' => 'Colbhan',
'searchresultshead' => 'Lorg',
'stub-threshold-disabled' => 'À comas',
'savedprefs' => 'Tha na roghainnean agad air an sàbhaladh.',
'timezonelegend' => 'Roinn-tìde:',
'localtime' => 'An t-àm ionadail:',
'servertime' => 'Àm an fhrithealaichte:',
'timezoneregion-africa' => 'Afraga',
'timezoneregion-america' => 'Aimeireaga',
'timezoneregion-antarctica' => 'An Antartaig',
'timezoneregion-arctic' => 'An Arctaig',
'timezoneregion-asia' => 'Àisia',
'timezoneregion-atlantic' => 'An Cuan Siar',
'timezoneregion-australia' => 'Astràilia',
'timezoneregion-europe' => 'An Roinn-Eòrpa',
'timezoneregion-indian' => 'An Cuan Innseanach',
'timezoneregion-pacific' => 'An Cuan Sèimh',
'prefs-namespaces' => 'Namespaces',
'default' => 'an roghainn bhunaiteach',
'prefs-files' => 'Faidhlichean',
'prefs-custom-css' => 'CSS gnàthaichte',
'prefs-custom-js' => 'JavaScript gnàthaichte',
'prefs-common-css-js' => 'CSS/JavaScript ann an coitcheann do gach craiceann:',
'prefs-reset-intro' => "'S urrainn dhut bun-roghainnean na làraich ath-shuidheachadh air an duilleag seo. Cha ghabh seo a neo-dhèanamh.",
'prefs-emailconfirm-label' => 'Dearbhadh puist-d:',
'youremail' => 'Post-dealain:',
'username' => '{{GENDER:$1|Ainm-cleachdaiche}}:',
'uid' => "ID {{GENDER:$1|a' chleachdaiche}}:",
'prefs-memberingroups' => '{{GENDER:$2|Ball}} ann an {{PLURAL:$1|bhuidheann|bhuidheann|buidhnean|buidheann}}:',
'prefs-registration' => 'Àm clàraidh:',
'yourrealname' => "An dearbh ainm a th' ort:",
'yourlanguage' => 'Cànan:',
'yourvariant' => 'Eug-samhail cànan na susbaint:',
'prefs-help-variant' => 'Do roghainn eug-samhail sgrìobhaidh a thèid duilleagan na h-uicipeid seo a shealltainn innte.',
'yournick' => 'Earr-sgrìobhadh ùr:',
'prefs-help-signature' => "Bu chòir dhut d' ainm a chur ri beachdan air duilleagan deasbaireachd le \"<nowiki>~~~~</nowiki>\" agus chithear d' ainm agus stampa ama 'na àite an uairsin.",
'badsig' => 'Tha co-chàradh an t-soidhnidh mì-dhligheach.
Thoir sùil air na tagaichean HTML.',
'badsiglength' => 'Tha an t-earr-sgrìobhadh agad ro fhada.
Chan fhaod e a bhith nas fhaide na $1 {{PLURAL:$1|charactar|charactar|caractaran|caractar}}.',
'yourgender' => 'Gnè:',
'gender-unknown' => 'Gun innse',
'gender-male' => 'Fireann',
'gender-female' => 'Boireann',
'email' => 'Post-d:',
'prefs-help-email' => "Chan leig thu leas post-dealain a chur ann ach bidh feum air ma dhìochuimhnicheas tu am facal-faire agad 's ma dh'iarras tu fear ùr.",
'prefs-help-email-others' => "'S urrainn dhut leigeil le daoine eile post-dealain a chur thugad tro cheangal air an duilleag agad.
Chan fhaicear an seòladh fhèin nuair a chuireas cuideigin post-dealain thugad.",
'prefs-help-email-required' => 'Tha feum air seòladh puist-d.',
'prefs-info' => 'Fiosrachadh bunasach',
'prefs-i18n' => 'Cànan',
'prefs-signature' => 'Earr-sgrìobhadh',
'prefs-dateformat' => "Fòrmat a' chinn-là",
'prefs-timeoffset' => 'Diofar ama',
'prefs-advancedediting' => 'Roghainnean adhartach',
'prefs-advancedrc' => 'Roghainnean adhartach',
'prefs-advancedrendering' => 'Roghainnean adhartach',
'prefs-advancedsearchoptions' => 'Roghainnean adhartach',
'prefs-advancedwatchlist' => 'Roghainnean adhartach',
'prefs-displayrc' => 'Roghainnean taisbeanaidh',
'prefs-displaysearchoptions' => 'Roghainnean taisbeanaidh',
'prefs-displaywatchlist' => 'Roghainnean taisbeanaidh',
'prefs-diffs' => 'Diffs',

# User preference: email validation using jQuery
'email-address-validity-valid' => 'Tha coltas gu bheil am post-d dligheach',
'email-address-validity-invalid' => 'Cuir a-steach post-d dligheach',

# User rights
'userrights' => "Stiùireadh ceadan a' chleachdaiche",
'userrights-lookup-user' => 'Stiùirich na buidhnean chleachdaichean',
'userrights-user-editname' => 'Cuir a-steach ainm-cleachdaiche:',
'editusergroup' => 'Deasaich na buidhnean chleachdaichean',
'editinguser' => "Ag atharrachadh ceadan a' chleachdaiche '''[[User:$1|$1]]''' $2",
'userrights-editusergroup' => 'Deasaich na buidhnean chleachdaichean',
'saveusergroups' => 'Sàbhail na buidhnean chleachdaichean',
'userrights-groupsmember' => 'Ball de:',
'userrights-groupsmember-auto' => 'Ball fèin-obrachail de:',
'userrights-reason' => 'Adhbhar:',
'userrights-no-interwiki' => 'Chan eil cead agad ceadan chleachdaichean a dheasachadh air uicipeidean eile.',
'userrights-nodatabase' => 'Chan eil an stòr-dàta $1 ann no chan e fear ionadail a tha ann.',
'userrights-changeable-col' => 'Buidhnean as urrainn dhut atharrachadh',

# Groups
'group' => 'Buidheann:',
'group-user' => 'Cleachdaichean',
'group-bot' => 'Bots',
'group-sysop' => 'Rianadairean',
'group-bureaucrat' => 'Biurocratan',
'group-all' => '(na h-uile)',

'group-user-member' => '{{GENDER:$1|cleachdaiche}}',
'group-bot-member' => '{{GENDER:$1|bot}}',
'group-sysop-member' => '{{GENDER:$1|rianaire}}',
'group-bureaucrat-member' => '{{GENDER:$1|biùrocrat}}',

'grouppage-user' => '{{ns:project}}:Cleachdaichean',
'grouppage-autoconfirmed' => '{{ns:project}}:Cleachdaichean fèin-dearbhte',
'grouppage-bot' => '{{ns:project}}:Bots',
'grouppage-sysop' => '{{ns:project}}:Rianadairean',
'grouppage-bureaucrat' => '{{ns:project}}:Biurocratan',

# Rights
'right-read' => 'Cead-leughaidh',
'right-edit' => 'Cead-deasachaidh',
'right-createpage' => "Cead-cruthachaidh (de dhuilleagan nach eil 'nan duilleagan deasbaireachd)",
'right-createtalk' => 'Cead duilleagan deasbaireachd a chruthachadh',
'right-createaccount' => 'Cead cunntasan ùra a chruthachadh',
'right-move' => 'Cead duilleagan a ghluasad',
'right-move-subpages' => 'Cead duilleagan a ghluasad leis na fo-dhuilleagan aca',
'right-move-rootuserpages' => 'Cead duilleagan chleachdaichean root a ghluasad',
'right-movefile' => 'Cead faidhlichean a ghluasad',
'right-upload' => 'Cead faidhlichean a luchdadh suas',
'right-reupload' => 'Cead sgrìobhadh thairis air duilleagan a tha ann',
'right-upload_by_url' => 'Faidhlichean a luchdadh suas o URL',

# Special:Log/newusers
'newuserlogpage' => 'Loga cruthachaidh de chleachdaichean',

# User rights log
'rightslog' => "Loga còraichean a' chleachdaiche",

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'deasaich an duilleag seo',
'action-move' => 'gluais an duilleag seo',

# Recent changes
'nchanges' => '{{PLURAL:$1|mhùthadh|mhùthadh|mùthaidhean|mùthadh}}',
'recentchanges' => 'Mùthaidhean ùra',
'recentchanges-legend' => 'Roghainnean nam mùthaidhean ùra',
'recentchanges-summary' => 'Cum sùil air na mùthaidhean as ùire a nithear air an uici air an duilleag seo.',
'recentchanges-feed-description' => 'Cum sùil air na mùthaidhean as ùire a nithear air an uici seo san inbhir seo.',
'recentchanges-label-newpage' => 'Chruthaich thu duilleag ùr leis a sin',
'recentchanges-label-minor' => 'Seo mùthadh beag',
'recentchanges-label-bot' => "'S e bot a rinn an deasachadh seo",
'recentchanges-label-unpatrolled' => 'Cha deach freiceadan tron deasachadh seo fhathast',
'rcnote' => 'Tha {{PLURAL:$1|an $1 mhùthadh|an $1 mhùthadh|na $1 mùthaidhean|na $1 mùthadh}} mu dheireadh anns na $2 {{PLURAL:$2|latha|latha|làithean|latha}} mu dheireadh, mar a bha iad $5, $4.',
'rcnotefrom' => "Gheibhear na mùthaidhean a-mach o '''$2''' (gu ruige '''$1''') gu h-ìosal.",
'rclistfrom' => 'Seall na mùthaidhean ùra a-mach o $1',
'rcshowhideminor' => '$1 mùthaidhean beaga',
'rcshowhidebots' => '$1 botaichean',
'rcshowhideliu' => '$1 neach-cleachdaidh air logadh a-steach',
'rcshowhideanons' => '$1 luchd-cleachdaidh gun ainm',
'rcshowhidepatr' => '$1 na deasachaidhean fo aire freiceadain',
'rcshowhidemine' => '$1 na mùthaidhean agam',
'rclinks' => 'Seall na $1 mùthaidhean mu dheireadh thairis air na $2 làithean mu dheireadh<br />$3',
'diff' => 'diof',
'hist' => 'eachd',
'hide' => 'Falaich',
'show' => 'Seall',
'minoreditletter' => 'b',
'newpageletter' => 'Ù',
'boteditletter' => 'bt',
'rc-enhanced-expand' => 'Seall am mion-fhiosrachadh (feumaidh seo JavaScript)',
'rc-enhanced-hide' => 'Cuir am mion-fhiosrachadh am falach',

# Recent changes linked
'recentchangeslinked' => 'Mùthaidhean co-cheangailte',
'recentchangeslinked-feed' => 'Mùthaidhean buntainneach',
'recentchangeslinked-toolbox' => 'Mùthaidhean buntainneach',
'recentchangeslinked-title' => 'Mùthaidhean co-cheangailte ri "$1"',
'recentchangeslinked-summary' => "Seo liosta nam mùthaidhean a chaidh a chur air duilleagan a tha a' ceangal o dhuilleag shònraichte (no ri buill de roinn shònraichte).
Tha duilleagan air [[Special:Watchlist|do chlàr-faire]] ann an litrichean '''troma'''.",
'recentchangeslinked-page' => 'Ainm na duilleige:',
'recentchangeslinked-to' => "Seall mùthaidhean nan duilleagan a tha a' ceangal ris an duilleag sin 'na àite",

# Upload
'upload' => 'Luchdaich suas faidhle',
'uploadbtn' => 'Luchdaich suas faidhle',
'uploadlogpage' => 'Loga an luchdaidh suas',
'filename' => 'Ainm-faidhle',
'filedesc' => 'Gearr-chunntas',
'fileuploadsummary' => 'Gearr-chunntas:',
'filestatus' => 'Cor dlighe-sgrìobhaidh:',
'ignorewarning' => 'Leig seachad an rabhadh agus sàbhail am faidhle co-dhiù',
'badfilename' => 'Ainm ìomhaigh air atharrachadh ri "$1".',
'fileexists' => 'Tha faidhle ann mu thràth air a bheil an t-ainm seo, cuir sùil air <strong>[[:$1]]</strong> mur eil thu buileach cinntach a bheil thu airson atharrachadh.
[[$1|thumb]]',
'savefile' => 'Sàbhail faidhle',
'uploadedimage' => 'a luchdaich suas "[[$1]]"',

'license' => 'Ceadachadh:',
'license-header' => 'Ceadachadh',
'nolicense' => 'Cha deach gin a thaghadh',

# Special:ListFiles
'listfiles' => 'Liosta nan ìomhaigh',

# File description page
'file-anchor-link' => 'Ìomhaigh',
'filehist' => 'Eachdraidh an fhaidhle',
'filehist-help' => 'Briog air ceann-là/àm gus am faidhle fhaicinn mar a nochd e aig an àm sin.',
'filehist-revert' => 'till',
'filehist-current' => 'làithreach',
'filehist-datetime' => 'Ceann-là/Àm',
'filehist-thumb' => 'Meanbh-dhealbh',
'filehist-thumbtext' => 'Meanbh-dhealbh airson an tionndaidh on $1',
'filehist-user' => 'Neach-cleachdaidh',
'filehist-dimensions' => 'Meud',
'filehist-comment' => 'Beachd',
'imagelinks' => 'Cleachdadh an fhaidhle',
'linkstoimage' => "Tha {{PLURAL:$1|an duilleag|an $1 dhuilleag|na $1 duilleagan|na $1 duilleag}} a leanas a' ceangal ris an fhaidhle seo:",
'nolinkstoimage' => "Chan eil duilleag sam bith a' ceangal an-seo.",
'sharedupload' => 'Tha am faidhle seo o $1 agus faodaidh pròiseactan eile a chleachdadh.',
'sharedupload-desc-here' => "'S ann à $1 a tha am faidhle seo agus faodaidh gu bheil pròiseactan eile 'ga chleachdadh.
Chithear an tuairisgeul a tha aice air [duilleag tuairisgeul an fhaidhle $2] gu h-ìosal.",
'uploadnewversion-linktext' => 'Luchdaich suas tionndadh ùr dhen fhaidhle seo',

# File deletion
'filedelete-reason-dropdown' => "*Adhbharan cumanta airson sguabadh às
** Tha e a' briseadh na còrach-lethbhreac
** Faidhle air a dhùblachadh",

# Random page
'randompage' => 'Duilleag thuairmeach',

# Statistics
'statistics' => 'Staitistearachd',

'doubleredirects' => 'Ath-seòlaidhean dùbailte',

'brokenredirects' => 'Ath-stiùireidhean briste',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|byte|bytes}}',
'nmembers' => '$1 {{PLURAL:$1|bhall|bhall|buill|ball}}',
'nviews' => '$1 {{PLURAL:$1|sealladh|shealladh|seallaidhean|sealladh}}',
'uncategorizedpages' => 'Duilleagan gun roinn-seòrsa',
'uncategorizedcategories' => 'Roinnean-seòrsa gun roinn-seòrsa',
'unusedimages' => 'Faidhlichean gun chleachdadh',
'prefixindex' => 'A h-uile duilleag le ro-leasachan',
'shortpages' => 'Duilleagan goirid',
'longpages' => 'Duilleagan fada',
'listusers' => 'Liosta nan cleachdaichean',
'usercreated' => 'Air a chruthachadh le {{GENDER:$3|}} $1 aig $2',
'newpages' => 'Duilleagan ùra',
'ancientpages' => 'Duilleagan as sìne',
'move' => 'Gluais',
'movethispage' => 'Gluais an duilleag seo',
'pager-newer-n' => '{{PLURAL:$1|1 nas ùire|$1 nas ùire}}',
'pager-older-n' => '{{PLURAL:$1|1 nas sine|$1 nas sine}}',

# Book sources
'booksources' => "Tùsan a tha 'nan leabhraichean",
'booksources-search-legend' => "Lorg tùsan a tha 'nan leabhraichean",
'booksources-go' => 'Siuthad',

# Special:Log
'log' => 'Logaichean',
'all-logs-page' => 'A h-uile loga poblach',
'logempty' => "Chan eil rud sam bith san loga a tha 'ga mhaidseadh.",
'showhideselectedlogentries' => 'Seall/Falaich innteartan an loga a thagh thu',

# Special:AllPages
'allpages' => 'A h-uile duilleag',
'alphaindexline' => '$1 gu $2',
'nextpage' => 'An ath dhuilleag ($1)',
'prevpage' => 'An duilleag roimhe ($1)',
'allpagesfrom' => "Seall duilleagan a tha a' tòiseachadh aig:",
'allpagesto' => "Seall duilleagan a tha a' crìochnachadh aig:",
'allarticles' => 'A h-uile duilleag',
'allpagessubmit' => 'Rach',

# Special:Categories
'categories' => 'Roinnean-seòrsa',
'categoriespagetext' => "Tha duilleagan no meadhan {{PLURAL:$1|san roinn-seòrsa|sna roinntean-seòrsa|}} a leanas.
Chan fhaicear [[Special:UnusedCategories|roinntean-seòrsa gun chleachdadh an-seo]].
Thoir sùil air na [[Special:WantedCategories|roinntean-seòrsa a thathar 'gan iarraidh cuideachd]].",

# Special:DeletedContributions
'deletedcontributions' => "Obair a' chleachdaiche a chaidh a sguabadh às",

# Special:LinkSearch
'linksearch' => 'Lorg sna ceanglaichean dhan taobh a-muigh',
'linksearch-ns' => 'Namespace:',
'linksearch-line' => "Tha $1 a' ceangal an-seo o $2",

# Special:ListGroupRights
'listgrouprights-members' => '(liosta de bhuill)',

# Email user
'emailuser' => 'Cuir post-dealain dhan chleachdaiche seo',
'emailusername' => 'Ainm-cleachdaiche:',
'emailusernamesubmit' => 'Air adhart',
'emailfrom' => 'O:',
'emailto' => 'Gu:',
'emailsubject' => 'Cuspair:',
'emailmessage' => 'Teachdaireachd:',
'emailsend' => 'Cuir',

# Watchlist
'watchlist' => 'An clàr-faire',
'mywatchlist' => 'An clàr-faire',
'watchlistfor2' => 'aig $1 $2',
'nowatchlist' => "Chan eil rud sam bith air a' chlàr-fhaire agad.",
'addwatch' => "Cuir air a' chlàr-fhaire",
'addedwatchtext' => 'Chaidh an duilleag "[[:$1]]" a chur ri [[Special:Watchlist|do chlàr-faire]].
Nochdaidh mùthaidhean a nithear air an duilleag seo \'s air an duilleag deasbaireachd a tha co-cheangailte ris an-seo san àm ri teachd.',
'removewatch' => 'Thoir air falbh on chlàr-fhaire',
'removedwatchtext' => 'Chaidh an duilleag "[[:$1]]" a thoirt air falbh o [[Special:Watchlist|do chlàr-faire]].',
'watch' => 'Cum sùil air',
'watchthispage' => 'Cum sùil air an duilleag seo',
'unwatch' => 'Na cum sùil tuilleadh',
'unwatchthispage' => 'Na cum sùil tuilleadh',
'notanarticle' => 'Chan e duilleag susbaint a tha ann',
'watchlist-details' => 'Tha {{PLURAL:$1|$1 duilleag|$1 dhuilleag|$1 duilleagan|$1 duilleag}} air do chlàr-faire, gun luaidh air na duilleagan deasbaireachd.',
'wlheader-showupdated' => "Tha clò '''trom''' air duilleagan a chaidh atharrachadh on turas mu dheireadh a thadhail thu orra.",
'watchmethod-recent' => "A' sgrùdadh deasachaidhean ùra airson duilleagan air d' fhaire",
'watchmethod-list' => "A' sgrùdadh duilleagan air d' fhaire airson deasachaidhean ùra",
'watchlistcontains' => 'Tha $1 {{PLURAL:$1|duilleag|dhuilleag|duilleagan|duilleag}} air do chlàr-faire.',
'wlnote' => 'Seo {{PLURAL:$1|an $1 mhùthadh|$1 mhùthadh|na $1 mùthaidhean|$1 mùthadh}} mu dheireadh san {{PLURAL:$2|$2 uair a thìde|$2 uair a thìde|$2 uairean a thìde|$2 uair a thìde}} mu dheireadh, mar a bha e $3, $4.',
'wlshowlast' => 'Seall na $1 uairean a thìde mu dheireadh $2 làithean mu dheireadh $3',
'watchlist-options' => 'Roghainnean mo chlàir-faire',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => "'Ga chur air a' chlàr-fhaire...",
'unwatching' => "A' toirt far a' chlàir-fhaire...",

# Delete
'deletepage' => 'Sguab às duilleag',
'confirm' => 'Daingnich',
'excontent' => "stuth a bh' ann: '$1'",
'exblank' => 'bha duilleag falamh',
'delete-confirm' => 'Sguab às "$1"',
'delete-legend' => 'Sguab às',
'confirmdeletetext' => "Tha thu an impis duilleag a sguabadh às agus a h-eachdraidh uile gu lèir.
Dearbhaich gu bheil thu airson seo a dhèanamh 's gun tuig thu a' bhuaidh a bhios ann agus gu bheil thu a' dèanamh seo a-rèir [[{{MediaWiki:Policy-url}}|a' phoileasaidh]].",
'actioncomplete' => 'Gnìomh deiseil',
'actionfailed' => "Dh'fhàillig ort",
'deletedtext' => 'Chaidh "$1" a sguabadh às.
Seall air $2 airson clàr de dhuilleagan a chaidh a sguabadh às o chionn ghoirid.',
'dellogpage' => 'Loga an sguabaidh às',
'reverted' => 'Air aiseag gu tionndadh nas sine',
'deletecomment' => 'Adhbhar:',
'deleteotherreason' => 'Adhbhar eile/a bharrachd:',
'deletereasonotherlist' => 'Adhbhar eile',
'deletereason-dropdown' => "*Adhbharan cumanta airson sguabadh às
** Dh'iarr an t-ùghdar e
** Tha e a' briseadh na còrach-lethbhreac
** Milleadh",
'delete-edit-reasonlist' => 'Deasaich adhbharan sguabadh às',

# Rollback
'rollbacklink' => 'roilig air ais',
'editcomment' => "Seo gearr-chunntas an deasachaidh: \"''\$1''\".",
'revertpage' => 'Deasachaidhean a chaidh a thilleadh le [[Special:Contributions/$2|$2]] ([[User talk:$2|deasbaireachd]]) dhan mhùthadh mu dheireadh le [[User:$1|$1]]',

# Protect
'protectlogpage' => 'Loga an dìon',
'protectlogtext' => 'Tha liosta na chaidh a dhìon gu h-ìosal.
Cuir sùil air [[Special:ProtectedPages|liosta nan duilleagan fo dhìon]] airson liosta na fheadhainn a tha fo dhìon an-dràsta fhèin.',
'protectedarticle' => '"[[$1]]" air a dhìon',
'modifiedarticleprotection' => 'a dh\'atharraich an ìre dìon de "[[$1]]"',
'unprotectedarticle' => 'a neo-dhìon "[[$1]]"',
'protect-title' => 'A\' dìonadh "$1"',
'prot_1movedto2' => '[[$1]] gluaiste ri [[$2]]',
'protect-norestrictiontypes-title' => 'Cha ghabh an duilleag seo a dhìon',
'protect-legend' => 'Daingnich dìonadh',
'protectcomment' => 'Adhbhar:',
'protectexpiry' => 'Falbhaidh an ùine air:',
'protect_expiry_invalid' => 'Tha an t-àm-crìochnachaidh mì-dhligheach.',
'protect_expiry_old' => 'Tha an t-àm crìochnachaidh seachad mu thràth.',
'protect-text' => "Chì thu an ìre dìon dhen duilleag '''$1''' an-seo agus is urrainn dhut atharrachadh an-seo.",
'protect-locked-access' => "Chan eil cead aig a' chunntas agad an ìre dìon de dhuilleag atharrachadh.
Seo roghainnean làithreach na duilleige '''$1''':",
'protect-cascadeon' => "Tha an duilleag seo fo dhìon an-dràsta a chionn 's gu bheil e air a ghabhail a-steach {{PLURAL:$1|$1  duilleag|$1 dhuilleag|$1 duilleagan|$1 duilleag}} a leanas aig a bheil dìon easach air.
'S urrainn dhut ìre dìon na duilleige seo atharrachadh ach cha bhi buaidh air an dìon easach.",
'protect-default' => 'Ceadaich a h-uile cleachdaiche',
'protect-fallback' => 'Na ceadaich ach do chleachdaichean aig a bheil cead "$1"',
'protect-level-autoconfirmed' => 'Na ceadaich ach cleachdaichean a chaidh an dearbhadh gu fèin-obrachail',
'protect-level-sysop' => 'Na ceadaich ach rianadairean',
'protect-summary-cascade' => 'mar eas',
'protect-expiring' => 'falbhaidh an ùine air $1 (UTC)',
'protect-expiring-local' => 'falbhaidh an ùine air $1',
'protect-expiry-indefinite' => 'buan',
'protect-cascade' => "Dìon duilleagan a tha 'gan gabhail a-steach san duilleag seo (dìon mar eas)",
'protect-cantedit' => "Chan urrainn dhut ìre dìon na duilleige seo atharrachadh a chionn 's nach eil cead deasachaidh agad air.",
'protect-othertime' => 'Àm eile:',
'protect-othertime-op' => 'àm eile',
'restriction-type' => 'Cead:',
'restriction-level' => 'Ìre bacaidh:',

# Undelete
'undeleterevisions' => 'Chaidh {{PLURAL:$1|$1 leth-bhreac|$1 leth-bhreac|$1 leth-bhreacan|$1 leth-bhreac}} a chur san tasg-lann',
'undeletelink' => 'seall/aisig',
'undeleteviewlink' => 'seall',

# Namespace form on various pages
'namespace' => 'Namespace:',
'invert' => 'Cuir na thagh mi bun os cionn',
'namespace_association' => 'Namespace co-cheangailte ris',
'blanknamespace' => '(Prìomh)',

# Contributions
'contributions' => "Mùthaidhean a' {{GENDER:$1|chleachdaiche}}",
'contributions-title' => 'Mùthaidhean a rinn $1',
'mycontris' => 'Mùthaidhean',
'contribsub2' => 'Do $1 ($2)',
'uctop' => '(làithreach)',
'month' => 'On mhìos (agus na bu tràithe):',
'year' => 'On bhliadhna (agus na bu tràithe):',

'sp-contributions-newbies' => 'Seall mùthaidhean le cunntasan ùra a-mhàin',
'sp-contributions-blocklog' => 'an loga bacaidh',
'sp-contributions-uploads' => "a' luchdadh suas",
'sp-contributions-logs' => 'logaichean',
'sp-contributions-talk' => 'deasbaireachd',
'sp-contributions-search' => 'Lorg mùthaidhean leis',
'sp-contributions-username' => 'Seòladh IP no ainm-cleachdaiche:',
'sp-contributions-toponly' => 'Na seall deasachaidhean ach na lèirmheasan as ùire',
'sp-contributions-submit' => 'Lorg',

# What links here
'whatlinkshere' => "Na tha a' ceangal a-nall an-seo",
'whatlinkshere-title' => 'Duilleagan a tha a\' ceangal ri "$1"',
'whatlinkshere-page' => 'Duilleag:',
'linkshere' => "Tha na duilleagan a leanas a' ceangal ri '''[[:$1]]''':",
'nolinkshere' => "Chan eil ceangal air duilleag sam bith a tha a' dol gu '''[[:$1]]'''.",
'isredirect' => 'duilleag ath-sheòlaidh',
'istemplate' => 'transclusion',
'isimage' => 'ceangal faidhle',
'whatlinkshere-prev' => '{{PLURAL:$1|roimhe|$1 roimhe}}',
'whatlinkshere-next' => '{{PLURAL:$1|an ath|an ath $1|na ath $1|an ath $1}}',
'whatlinkshere-links' => '← ceanglaichean',
'whatlinkshere-hideredirs' => '$1 ath-sheòlaidhean',
'whatlinkshere-hidetrans' => '$1 transclusions',
'whatlinkshere-hidelinks' => '$1 ceanglaichean',
'whatlinkshere-hideimages' => '$1 ceanglaichean nam faidhlichean',
'whatlinkshere-filters' => 'Criathairean',

# Block/unblock
'blockip' => 'Bac cleachdaiche',
'ipbreason' => 'Adhbhar:',
'ipbsubmit' => 'Bac an cleachdaiche seo',
'ipboptions' => '2 uair a thìde:2 hours, 1 latha:1 day, 3 làithean:3 days, 1 seachdain:1 week, 2 sheachdain:2 weeks, 1 mhìos:1 month, 3 mìosan:3 months, 6 mìosan:6 months, 1 bhliadhna:1 year,neo-chrìochnach:infinite',
'badipaddress' => "Chan eil an seòladh IP aig a' cleachdair seo iomchaidh",
'blockipsuccesssub' => "Shoirbhich leat leis a' bhacadh",
'blockipsuccesstext' => 'Chaidh [[Special:Contributions/$1|$1]] a bhacadh.
<br />Faic [[Special:BlockList|liosta nan IP bacte]] gus sùile a thoirt air na bacaidhean.',
'unblockip' => 'Neo-bhac an cleachdaiche',
'ipusubmit' => 'Thoir air falbh am bacadh seo',
'ipblocklist' => 'Cleachdaichean a chaidh a bhacadh',
'blocklink' => 'bac',
'unblocklink' => 'neo-bhac',
'change-blocklink' => 'mùth bacadh',
'contribslink' => 'mùthaidhean',
'blocklogpage' => 'Loga nam bacadh',
'blocklogentry' => 'Chaidh bacadh a chrìochnaicheas ann an $2 a chur air [[$1]] $3',
'unblocklogentry' => '"$1" air a neo-bhacadh',
'block-log-flags-nocreate' => 'cruthachadh de chunntasan ùra à comas',
'ipb_expiry_invalid' => 'Tha an t-àm-crìochnachaidh mì-dhligheach.',
'ip_range_invalid' => 'Raon IP neo-iomchaidh.',

# Developer tools
'lockdb' => 'Glais an stòr-dàta',
'lockconfirm' => 'Seadh, is ann a tha mi ag iarraidh an stòr-dàta a ghlasadh.',
'lockbtn' => 'Glais an stòr-dàta',
'lockdbsuccesssub' => 'Shoirbhich leat le glasadh an stòir-dhàta',

# Move page
'move-page-legend' => 'Gluais duilleag',
'movepagetext' => "Ma chleachdas tu am foirm gu h-ìosal, cuiridh tu ainm ùr air 's gluaisidh tu a h-eachdraidh gu lèir dhan ainm ùr.
Bidh an seann tiotal 'na ath-sheòladh dhan tiotal ùr an uairsin.
'S urrainn dhut ath-sheòladh sam bith a tha a' dol dhan tiotal tùsail ùrachadh leis fhèin.
Mura dèan thu sin, dèan cinnteach gun cuir thu sùil air eagal 's gum bi [[Special:DoubleRedirects|ath-sheòlaidhean dùbailte]] no [[Special:BrokenRedirects|briste]] ann.
'S ann ort-sa a tha an t-uallach airson dèanamh cinntach gu bheil na ceanglaichean a' dol dha na h-àitichean ceart.

Thoir an aire '''nach dèid''' an duilleag a ghluasad ma tha duilleag air an tiotal ùr mu thràth ach ma bhios e falamh no 'na ath-sheòladh 's mur eil eachdraidh deasachaidh ann.
'S ciall dha seo gun urrainn dhut ainm duilleige a thilleadh dhan ainm a bha air roimhe ma rinn thu mearachd agus nach urrainn dhut sgrìobhadh thairis air duilleag a tha ann.

'''Rabhadh!'''
Faodaidh seo a bhith 'na atharrachadh mòr ris nach bi dùil air duilleag air a bheil fèill mhòr;
dèan cinnteach gu bheil thu a' tuigsinn dè a' bhuaidh a bhios agad mus dèid thu air adhart.",
'movepagetalktext' => "Thèid an duilleag deasbaireachd a tha co-cheangailte ris a ghluasad 'na cois '''ach:'''
*Ma tha duilleag deasbaireachd nach eil falamh aig an ainm ùr mu thràth, no
*Ma bheir thu air falbh a' chromag on bhogsa gu h-ìosal

Ma thachras seo, feumaidh to an duilleag a ghluasad no cho-aontachadh a làimh, ma tha sin fa-near dhut.",
'movearticle' => 'Gluais duilleag:',
'newtitle' => 'Dhan tiotal ùr:',
'move-watch' => 'Cum sùil air an duilleag thùsail agus an duilleag thairgaideach',
'movepagebtn' => 'Gluais duilleag',
'pagemovedsub' => "Shoirbhich leat leis a' ghluasad",
'movepage-moved' => '\'\'\'Chaidh "$1" a ghluasad a "$2"\'\'\'',
'articleexists' => 'Tha duilleag ann mu thràth air a bheil an t-ainm seo no chan eil an t-ainm a thagh thu dligheachd.
Nach tagh thu ainm eile?',
'talkexists' => "'''Chaidh an duilleag fhèin a ghluasad gu soirbheachail ach cha do ghabh an duilleag deasbaireachd a ghluasad a chionn 's gu bheil tè ann aig an tiotal ùr mu thràth.
Bidh agad ris an co-aontachadh a làimh.'''",
'movedto' => 'air a ghluasad a',
'movetalk' => 'Gluais an duilleag deasbaireachd a tha co-cheangailte ris',
'movelogpage' => 'Loga nan gluasadan',
'movereason' => 'Adhbhar:',
'revertmove' => 'till',
'delete_and_move' => 'Sguab às agus gluais',
'delete_and_move_confirm' => 'Siuthad, sguab às an duilleag',

# Export
'export' => 'Às-phortaich duilleagan',

# Namespace 8 related
'allmessages' => 'Teachdaireachdan an t-siostaim',
'allmessagesname' => 'Ainm',
'allmessagesdefault' => 'Teacsa bunaiteach na teachdaireachd',
'allmessagestext' => 'Seo liosta de theachdaireachdan an t-siostaim a tha ri làimh ann an namespace MediaWiki.
Tadhail air [//www.mediawiki.org/wiki/Localisation Ionadaileadh MediaWiki] is [//translatewiki.net translatewiki.net] ma tha thu airson pàirt a ghabhail ann an ionadaileadh MediaWiki.',

# Thumbnails
'thumbnail-more' => 'Meudaich',
'filemissing' => 'Faidhle a dhìth',
'thumbnail_error' => 'Mearachd le cruthachadh na h-ìomhaigheige: $1',

# Special:Import
'importnotext' => 'Falamh no gun teacsa',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'An duilleag phearsanta agad',
'tooltip-pt-mytalk' => 'Duilleag do chonaltraidh',
'tooltip-pt-preferences' => 'Do roghainnean',
'tooltip-pt-watchlist' => "Seo liosta nan duilleagan a tha thu a' cumail sùil orra a thaobh mhùthaidhean a nithear orra",
'tooltip-pt-mycontris' => 'Liosta do mhùthaidhean',
'tooltip-pt-login' => 'Mholamaidh dhut logadh a-steach; ge-tà, cha leig thu leas seo a dhèanamh',
'tooltip-pt-logout' => 'Log a-mach',
'tooltip-ca-talk' => 'Deasbad mu dhuilleag na susbainte',
'tooltip-ca-edit' => "'S urrainn dhut an duilleag seo a dheasachadh. Saoil an cleachd thu an ro-shealladh mus sàbhail thu?",
'tooltip-ca-addsection' => 'Tòisich air earrann ùr',
'tooltip-ca-viewsource' => "Tha an duilleag seo fo dhìon.
'S urrainn dhut a tùs fhaicinn",
'tooltip-ca-history' => 'Seann mhùthaidhean na duilleige seo',
'tooltip-ca-protect' => 'Dìon an duilleag seo',
'tooltip-ca-delete' => 'Sguab às an duilleag seo',
'tooltip-ca-move' => 'Gluais an duilleag seo',
'tooltip-ca-watch' => 'Cuir an duilleag seo air mo chlàr-faire',
'tooltip-ca-unwatch' => 'Thoir an duilleag seo far mo chlàir-fhaire',
'tooltip-search' => 'Rannsaich {{SITENAME}}',
'tooltip-search-go' => 'Rach gu duilleag air a bheil an dearbh ainm seo, ma tha tè ann',
'tooltip-search-fulltext' => 'Lorg an teacs seo sna duilleagan',
'tooltip-p-logo' => 'Tadhail air an duilleag mhòr',
'tooltip-n-mainpage' => "Tadhail air a' phrìomh dhuilleag",
'tooltip-n-mainpage-description' => 'Tadhail air an duilleag mhòr',
'tooltip-n-portal' => 'Mun phròiseact, nas urrainn dhut dèanamh is far an lorg thu nithean',
'tooltip-n-currentevents' => 'Lorg fiosrachadh a bharrachd mu thachartasan an latha',
'tooltip-n-recentchanges' => 'Liosta nam mùthaidhean ùra aig an uici.',
'tooltip-n-randompage' => 'Luchdaich duilleag air thuaiream',
'tooltip-n-help' => 'Far am faigh thu fiosrachadh',
'tooltip-t-whatlinkshere' => "Liosta de gach duilleag uici a tha a' ceangal ris an duilleag seo",
'tooltip-t-recentchangeslinked' => 'Mùthaidhean a rinneadh o chionn ghoirid air duilleagan a tha ceangal ann thuca on duilleag seo',
'tooltip-feed-rss' => 'Inbhir RSS airson na duilleige seo',
'tooltip-feed-atom' => 'Inbhir Atom airson na duilleige seo',
'tooltip-t-contributions' => "Seall liosta nam mùthaidhean a rinn a' chleachdaiche seo",
'tooltip-t-emailuser' => 'Cuir post-dealain dhan chleachdaiche seo',
'tooltip-t-upload' => 'Luchdaich suas faidhle',
'tooltip-t-specialpages' => 'Liosta de gach duilleag shònraichte',
'tooltip-t-print' => 'Tionndadh dhen duilleag a ghabhas a chlò-bhualadh',
'tooltip-t-permalink' => 'Dèan ceangal buan gu mùthadh seo na duilleige',
'tooltip-ca-nstab-main' => 'Seall duilleag na susbainte',
'tooltip-ca-nstab-user' => "Seall duilleag a' chleachdaiche",
'tooltip-ca-nstab-special' => 'Seo duilleag shònraichte, chan urrainn dhut an duilleag fhèin a dheasachadh',
'tooltip-ca-nstab-project' => "Seall duilleag a' phròiseict",
'tooltip-ca-nstab-image' => 'Seall duilleag an fhaidhle',
'tooltip-ca-nstab-template' => 'Seall an teamplaid',
'tooltip-ca-nstab-category' => 'Seall duilleag na roinne',
'tooltip-minoredit' => 'Comharraich seo mar dheasachadh beag',
'tooltip-save' => 'Sàbhail na mùthaidhean agad',
'tooltip-preview' => 'Ro-sheall na mùthaidhean agad; saoil an cleachd thu seo mus sàbhail thu iad?',
'tooltip-diff' => 'Seall na mùthaidhean a chuir mi air an teacs',
'tooltip-compareselectedversions' => 'Seall an diofar eadar an dà mhùthadh dhen duilleag seo a thagh thu',
'tooltip-watch' => 'Cuir an duilleag seo air do chlàr-faire',
'tooltip-rollback' => 'Ma chleachdas tu "Roilig air ais", tillidh thu gach mùthadh a rinn deasaiche àraid le aon bhriogadh',
'tooltip-undo' => 'Tillidh "Neo-dhèan" am mùthadh seo \'s fosglaidh e am foirm mùthaidh ann am modh an ro-sheallaidh. \'S urrainn dhut adhbhar a chur an cèill sa ghearr-chunntas air an dòigh seo.',
'tooltip-summary' => 'Cuir a-steach gearr-chunntas',

# Attribution
'anonymous' => '{{PLURAL:$1|cleachdaiche|cleachdaichean}} gun ainm o {{SITENAME}}',
'siteuser' => 'cleachdaiche {{SITENAME}} $1',
'othercontribs' => 'Stèidhichte air obair le $1.',
'others' => 'eile',
'siteusers' => '{{PLURAL:$2|cleachdaiche|cleachdaichean}} {{SITENAME}} $1',

# Browsing diffs
'previousdiff' => '← Mùthadh nas sine',
'nextdiff' => 'Deasachadh nas ùire →',

# Media information
'file-info-size' => '$1 × $2 pixel, meud an fhaidhle: $3, seòrsa MIME: $4',
'file-nohires' => 'Chan eil dùmhlachd-bhreacaidh nas fhearr ri fhaighinn.',
'svg-long-desc' => 'Faidhle SVG, a-rèir ainm $1 × $2 pixel, meud faidhle: $3',
'show-big-image' => 'Dùmhlachd-bhreacaidh shlàn',

# Special:NewFiles
'ilsubmit' => 'Rannsaich',
'bydate' => 'air ceann-latha',

# Bad image list
'bad_image_list' => "Seo mar a tha am fòrmat:

Cha bheachdaichear ach air buill liosta (loidhniche a tha * air am beulaibh).
Feumaidh a' chiad cheangal air loidhne a bhith 'na cheangal ri droch fhaidhle.
Thathar a' coimhead air ceangal sam bith eile san loidhne sin mar eisgeachdan, 's e sin duilleagan far am faod am faidhle a bhith sa loidhne.",

# Metadata
'metadata' => 'Metadata',
'metadata-help' => "Tha fiosrachadh a bharrachd san fhaidhle seo, 's mathaid o chamara digiteach no sganair a chaidh a chleachdadh gus a chruthachadh no a dhigiteachadh.
Ma chaidh am faidhle tùsail atharrachadh, faodaidh nach eil cuid dhen fhiosrachadh ceart a thaobh an fhaidhle atharraichte tuilleadh.",
'metadata-expand' => 'Seall am fiosrachadh a bharrachd',
'metadata-collapse' => 'Cuir am fiosrachadh a bharrachd am falach',
'metadata-fields' => "Thèid raointean meata-dhàta nan dealbhan a tha ainmichte san teachdaireachd seo a ghabhail a-steach air duilleag an deilbh nuair a bhios clàr a' mheata-dàta air a dhùmhlachadh.
Bidh an fheadhainn eile falaichte a ghnàth.
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

# External editor support
'edit-externally' => 'Deasaich am faidhle le prògram on taobh a-muigh',
'edit-externally-help' => '(Seall air [//www.mediawiki.org/wiki/Manual:External_editors mìneachadh an t-suidheachaidh] airson barrachd fiosrachaidh)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'a h-uile',
'namespacesall' => 'uile',
'monthsall' => 'uile',

# Watchlist editor
'watchlistedit-normal-title' => 'Deasaich an clàr-faire',
'watchlistedit-raw-submit' => 'Ùraich an clàr-faire',

# Watchlist editing tools
'watchlisttools-view' => 'Seall na mùthaidhean iomchaidh',
'watchlisttools-edit' => 'Seall is deasaich mo chlàr-faire',
'watchlisttools-raw' => "Deasaich còd a' chlàir-fhaire",

# Core parser functions
'duplicate-defaultsort' => "'''Rabhadh:''' Tha an iuchair seòrsachaidh bhunaiteach \"\$2\" a' dol thairis air seann iuchair eile, \"\$1\".",

# Special:Version
'version' => 'Tionndadh',

# Special:SpecialPages
'specialpages' => 'Duilleagan sònraichte',

# External image whitelist
'external_image_whitelist' => " #Fàg an loidhne seo dìreach mar a tha e<pre>
#Cuir mìrean nan regular expressions (dìreach a' phàirt eadar //) gu hìosal
#Thèid seisean URL a lorg dhaibh am measg nan dealbhan air an taobh a-muigh (hotlinks)
#Chithear an fheadhainn a tha a' freagairt ri seise a shealltainn air neo chithear ceangal dhan dealbh a-mhàin
#Chan eil ann an loidhnichean a tha a' tòiseachadh le # ach beachdan
#Chan eil aire do litrichean mòra no beaga

#Cuir gach mì regex os cionn na loidhne seo. Fàg an loidhne seo dìreach mar a tha e</pre>",

# Special:Tags
'tag-filter' => 'Criathrag [[Special:Tags|thagaichean]]:',

# New logging system
'rightsnone' => '(chan eil gin)',

# Search suggestions
'searchsuggest-containing' => 'anns a bheil...',

);

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

$messages = array(
# User preference toggles
'tog-underline'               => 'Fo-loidhneadh nan ceanglaichean:',
'tog-highlightbroken'         => 'An cleachdar am fòrmat <a href="" class="new">seo</a> airson ceanglaichean briste (no am fear seo<a href="" class="internal">?</a>)',
'tog-justify'                 => 'Taobhaich na h-earrannan',
'tog-hideminor'               => 'Falaich mùthaidhean beaga ann an liosta nam mùthaidhean ùra',
'tog-hidepatrolled'           => 'Falaich mùthaidhean fo fhaire ann an liosta nam mùthaidhean ùra',
'tog-newpageshidepatrolled'   => 'Falaich duilleagan fo fhaire ann an liosta nan duilleagan ùra',
'tog-extendwatchlist'         => "Leudaich an clàr-faire gus an seall e gach mùthadh 's chan ann an fheadhainn as ùire a-mhàin",
'tog-usenewrc'                => 'Cleachd mùthaidhean ùra leasaichte (feumaidh seo JavaScript)',
'tog-numberheadings'          => 'Cuir àireamhan ri ceann-sgrìobhaidhean leis fhèin',
'tog-showtoolbar'             => 'Seall am bàr-inneil deasachaidh (feumaidh seo JavaScript)',
'tog-editondblclick'          => 'Tòisich air deasachadh duilleige le briogadh dùbailt (feumaidh seo JavaScript)',
'tog-editsection'             => 'Cuir am comas deasachadh earainn le ceanglaichean [deasaich]',
'tog-editsectiononrightclick' => "Cuir an comas deasachadh earainn le briogadh deas air tiotal de dh'earrainn (feumaidh seo JavaScript)",
'tog-showtoc'                 => 'Seall an clàr-innse (air duilleagan air a bheil barrachd air 3 ceann-sgrìobhaidhean)',
'tog-rememberpassword'        => "Cuimhnich gu bheil mi air logadh a-steach air a' choimpiutair seo (suas gu $1 {{PLURAL:$1|latha|latha|latha|latha|làithean|latha}})",
'tog-watchcreations'          => 'Cuir duilleagan a chruthaicheas mi air mo chlàr-faire',
'tog-watchdefault'            => 'Cuir duilleagan a dheasaicheas mi air mo chlàr-faire',
'tog-watchmoves'              => 'Cuir duilleagan a ghluaiseas mi air mo chlàr-faire',
'tog-watchdeletion'           => 'Cuir duilleagan a sguabas mi às air mo chlàr-faire',
'tog-minordefault'            => 'Comharraich gach mùthadh mar mhùthadh beag a ghnàth',
'tog-previewontop'            => "Nochd an ro-shealladh os cionn a' bhogsa deasachaidh",
'tog-previewonfirst'          => "Nochd an ro-shealladh nuair a nithear a' chiad deasachadh",
'tog-nocache'                 => 'Cuir à comas tasgadh nan duilleagan',
'tog-enotifwatchlistpages'    => 'Cuir post-dealain thugam nuair a chuirear mùthadh air duilleag a tha air mo chlàr-faire',
'tog-enotifusertalkpages'     => 'Cuir post-dealain thugam nuair a mhùthaichear duilleag mo chonaltraidh',
'tog-enotifminoredits'        => 'Cuir post-dealain thugam nuair a chuirear mùthadh beag air duilleagan cuideachd',
'tog-enotifrevealaddr'        => 'Nochd an seòladh puist-dhealain agam ann am teachdaireachdan fiosrachaidh',
'tog-shownumberswatching'     => "Nochd àireamh nan cleachdaichean a tha a' cumail sùil air",
'tog-oldsig'                  => 'Ro-shealladh an earr-sgrìobhaidh làithrich:',
'tog-fancysig'                => 'Làimhsich an t-earr-sgrìobhadh mar wikitext (gun cheangal leis fhèin)',
'tog-externaleditor'          => "Cleachd deasaichear on taobh a-muigh a ghnàth (do shàr-eòlaichean a-mhàin, feumaidh seo roghainnean sònraichte air a' choimpiutair agad [http://www.mediawiki.org/wiki/Manual:External_editors Barrachd fiosrachaidh.])",
'tog-externaldiff'            => "Cleachd diff on taobh a-muigh a ghnàth (do shàr-eòlaichean a-mhàin, feumaidh seo roghainnean sònraichte air a' choimpiutair agad. [http://www.mediawiki.org/wiki/Manual:External_editors Barrachd fiosrachaidh.])",
'tog-showjumplinks'           => 'Cuir an comas ceanglaichean so-inntrigeachd "gearr leum gu"',
'tog-uselivepreview'          => 'Cleachd an ro-shealladh beò (feumaidh seo JavaScript) (deuchainneach)',
'tog-forceeditsummary'        => "Cuir ceist nuair a dh'fhàgas mi gearr-chunntas an deasachaidh bàn",
'tog-watchlisthideown'        => 'Falaich mo mhùthaidhean fhèin air mo chlàr-faire',
'tog-watchlisthidebots'       => 'Falaich mùthaidhean nam bot air mo chlàr-faire',
'tog-watchlisthideminor'      => 'Falaich mùthaidhean beaga air mo chlàr-faire',
'tog-watchlisthideliu'        => 'Falaich mùthaidhean le cleachdaichean a tha air logadh a-steach air mo chlàr-faire',
'tog-watchlisthideanons'      => 'Falaich mùthaidhean le cleachdaichean gun ainm air mo chlàr-faire',
'tog-watchlisthidepatrolled'  => 'Falaich mùthaidhean air duilleagan fo fhreiceadan air mo chlàr-faire',
'tog-ccmeonemails'            => 'Cuir lethbhric de phuist-dhealain a chuireas mi do chleachdaichean eile thugam',
'tog-diffonly'                => 'Na seall susbaint nan duilleagan fo na diofaichean',
'tog-showhiddencats'          => 'Seall na roinnean falaichte',
'tog-norollbackdiff'          => 'Na dèan diof às dèidh roiligeadh air ais',

'underline-always'  => 'An-còmhnaidh',
'underline-never'   => 'Na dèan seo idir',
'underline-default' => "Roghainn bhunaiteach a' bhrabhsair",

# Font style option in Special:Preferences
'editfont-style'     => 'Stoidhle cruth-clò an raoin dheasachaidh:',
'editfont-default'   => "Roghainn bhunaiteach a' bhrabhsair",
'editfont-monospace' => 'Cruth-clò aon-leud',
'editfont-sansserif' => 'Cruth-clò gun serif',
'editfont-serif'     => 'Cruth-clò le serif',

# Dates
'sunday'        => 'DiDòmhnaich',
'monday'        => 'DiLuain',
'tuesday'       => 'DiMàirt',
'wednesday'     => 'DiCiadain',
'thursday'      => 'DiarDaoin',
'friday'        => 'DihAoine',
'saturday'      => 'DiSathairne',
'sun'           => 'DiD',
'mon'           => 'DiL',
'tue'           => 'DiM',
'wed'           => 'DiC',
'thu'           => 'Dia',
'fri'           => 'Dih',
'sat'           => 'DiS',
'january'       => 'dhen Fhaoilleach',
'february'      => 'dhen Ghearrain',
'march'         => 'dhen Mhàrt',
'april'         => 'dhen Ghiblean',
'may_long'      => 'dhen Chèitean',
'june'          => 'dhen Ògmhios',
'july'          => 'dhen Iuchar',
'august'        => 'dhen Lùnastal',
'september'     => 'dhen t-Sultain',
'october'       => 'dhen Dàmhair',
'november'      => 'dhen t-Samhain',
'december'      => 'dhen Dùbhlachd',
'january-gen'   => 'dhen Fhaoilleach',
'february-gen'  => 'dhen Ghearrain',
'march-gen'     => 'dhen Mhàrt',
'april-gen'     => 'dhen Ghiblean',
'may-gen'       => 'dhen Chèitean',
'june-gen'      => 'dhen Ògmhios',
'july-gen'      => 'dhen Iuchar',
'august-gen'    => 'dhen Lùnastal',
'september-gen' => 'dhen t-Sultain',
'october-gen'   => 'dhen Dàmhair',
'november-gen'  => 'dhen t-Samhain',
'december-gen'  => 'dhen Dùbhlachd',
'jan'           => 'faoi',
'feb'           => 'gibl',
'mar'           => 'màrt',
'apr'           => 'gibl',
'may'           => 'cèit',
'jun'           => 'ògmh',
'jul'           => 'iuch',
'aug'           => 'lùna',
'sep'           => 'sult',
'oct'           => 'dàmh',
'nov'           => 'samh',
'dec'           => 'dùbh',

# Categories related messages
'pagecategories'                => '{{PLURAL:$1|Roinn-seòrsa|Roinn-seòrsa|Roinn-seòrsa|Roinn-seòrsa|Roinnean-seòrsa|Roinn-seòrsa}}',
'category_header'               => 'Duilleagan sa roinn "$1"',
'subcategories'                 => 'Fo-roinnean',
'category-media-header'         => 'Meadhanan sa roinn "$1"',
'category-empty'                => "''Chan eil duilleagan no meadhanan san roinn seo an-dràsta.''",
'hidden-categories'             => '{{PLURAL:$1|Roinn-seòrsa fhalaichte|Roinn-seòrsa fhalaichte|Roinn-seòrsa fhalaichte|Roinn-seòrsa fhalaichte|Roinnean-seòrsa falaichte|Roinn-seòrsa fhalaichte}}',
'hidden-category-category'      => 'Roinnean falaichte',
'category-subcat-count'         => '{{PLURAL:$2|Chan eil san roinn-seòrsa ach an fho-roinn-seòrsa a leanas.|Tha {{PLURAL:$1|an fho-roinn-seòrsa|an $1 fho-roinn-seòrsa|an fho-roinn-seòrsa|an $1 fho-roinn-seòrsa|na $1 fo-roinnean-seòrsa|na $1 fo-roinn-seòrsa}}, aig an roinn-seòrsa a leanas, a-mach à $2 uile gu lèir.}}',
'category-subcat-count-limited' => 'Tha {{PLURAL:$1|am fo-roinn-seòrsa|$1 na fo-roinntean-seòrsa|$1 na fo-roinntean-seòrsa|$1 na fo-roinntean-seòrsa|$1 na fo-roinntean-seòrsa|$1 na fo-roinntean-seòrsa}} a leanas sa roinn-seòrsa seo.',
'category-article-count'        => '{{PLURAL:$2|Chan eil ach an duilleag a leanas san fho-roinn-seòrsa seo.|Tha {{PLURAL:$1|an duilleag|an $1 dhuilleag|an duilleag|an $1 dhuilleag|na $1 duilleagan|na $1 duilleag}} a leanas san roinn-seòrsa seo, a-mach à $2 uile gu lèir.}}',
'listingcontinuesabbrev'        => 'leant.',
'index-category'                => "Duilleagan air a' chlàr-innse",
'noindex-category'              => "Duilleagan nach eil air a' chlàr-innse",

'mainpagetext'      => "'''Chaidh MediaWiki a stàladh gu soirbheachail.'''",
'mainpagedocfooter' => "Cuir sùil air [http://meta.wikimedia.org/wiki/Help:Contents treòir nan cleachdaichean] airson fiosrachadh mu chleachdadh a' bhathar-bhog wiki.

== Toiseach tòiseachaidh ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Liosta suidheachadh nan roghainnean]
* [http://www.mediawiki.org/wiki/Manual:FAQ CÀBHA MediaWiki]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Liosta puist nan sgaoilidhean MediaWiki]",

'about'         => 'Mu',
'article'       => 'Duilleag susbainte',
'newwindow'     => "(a' fosgladh ann an uinneag ùr)",
'cancel'        => 'Sguir dheth',
'moredotdotdot' => 'Barrachd...',
'mypage'        => 'Mo dhuilleag',
'mytalk'        => 'Mo chonaltradh',
'anontalk'      => 'Conaltradh airson an IP seo',
'navigation'    => 'Seòladh',
'and'           => '&#32;agus',

# Cologne Blue skin
'qbfind'         => 'Lorg',
'qbbrowse'       => 'Brabhsaich',
'qbedit'         => 'Deasaich',
'qbpageoptions'  => 'An duilleag seo',
'qbpageinfo'     => 'Co-theacs',
'qbmyoptions'    => 'Na duilleagan agam',
'qbspecialpages' => 'Duilleagan sònraichte',
'faq'            => 'CÀBHA',
'faqpage'        => 'Project:CÀBHA',

# Vector skin
'vector-action-addsection' => 'Cuir ris cuspair',
'vector-action-delete'     => 'Sguab às',
'vector-action-move'       => 'Gluais',
'vector-action-protect'    => 'Dìon',
'vector-action-undelete'   => 'Neo-dhèan an sguabadh às',
'vector-action-unprotect'  => 'Neo-dhìon',
'vector-view-create'       => 'Cruthaich',
'vector-view-edit'         => 'Deasaich',
'vector-view-history'      => 'Seall an eachdraidh',
'vector-view-view'         => 'Leugh',
'vector-view-viewsource'   => 'Seall an tùs',
'actions'                  => 'Gnìomhan',
'namespaces'               => 'Namespaces',
'variants'                 => 'Tionndaidhean',

'errorpagetitle'    => 'Mearachd',
'returnto'          => 'Till dhan duilleag a leanas: $1',
'tagline'           => 'O {{SITENAME}}',
'help'              => 'Cobhair',
'search'            => 'Lorg',
'searchbutton'      => 'Lorg',
'go'                => 'Rach',
'searcharticle'     => 'Rach',
'history'           => 'Eachdraidh na duilleige',
'history_short'     => 'Eachdraidh',
'updatedmarker'     => 'air ùrachadh on turas mu dheireadh a thadhail mi air',
'info_short'        => 'Fiosrachadh',
'printableversion'  => 'Tionndadh a ghabhas a chlò-bhualadh',
'permalink'         => 'Ceangal buan',
'print'             => 'Clò-bhuail',
'edit'              => 'Deasaich',
'create'            => 'Cruthaich',
'editthispage'      => 'Deasaich an duilleag seo',
'create-this-page'  => 'Cruthaich an duilleag seo',
'delete'            => 'Sguab às',
'deletethispage'    => 'Sguab às an duilleag seo',
'protect'           => 'Dìon',
'protect_change'    => 'mùth',
'protectthispage'   => 'Dìon an duilleag seo',
'unprotect'         => 'Neo-dhìon',
'unprotectthispage' => 'Neo-dhìon an duilleag seo',
'newpage'           => 'Duilleag ùr',
'talkpage'          => 'Dèan deasbad mun duilleag seo',
'talkpagelinktext'  => 'Deasbaireachd',
'specialpage'       => 'Duilleag shònraichte',
'personaltools'     => 'Innealan pearsanta',
'postcomment'       => 'Earrann ùr',
'articlepage'       => 'Seall duilleag na susbainte',
'talk'              => 'Deasbaireachd',
'views'             => 'Tadhalan',
'toolbox'           => 'Bogsa-innealan',
'userpage'          => "Seall duilleag a' chleachdaiche",
'projectpage'       => "Seall duilleag a' phròiseict",
'imagepage'         => 'Seall duilleag an fhaidhle',
'mediawikipage'     => 'Seall duilleag na teachdaireachd',
'templatepage'      => 'Seall duilleag na teamplaide',
'viewhelppage'      => 'Seall an duilleag cobharach',
'categorypage'      => 'Seall duilleag na roinne',
'viewtalkpage'      => 'Seall an deasbaireachd',
'otherlanguages'    => 'Ann an cànain eile',
'redirectedfrom'    => '(Air ath-sheòladh o $1)',
'redirectpagesub'   => 'Ath-sheòl an duilleag',
'lastmodifiedat'    => 'Chaidh an duilleag seo a mhùthadh $1, aig $2 turas mu dheireadh.',
'protectedpage'     => 'Duilleag fo dhìon',
'jumpto'            => 'Gearr leum gu:',
'jumptonavigation'  => 'seòladh',
'jumptosearch'      => 'lorg',
'view-pool-error'   => "Duilich, tha na frithealaichean ro thrang an-dràsta.
Tha cus chleachdaichean a' feuchainn ris an duilleag seo fhaicinn.
Fuirich ort greis mus feuch thu ris an duilleag seo fhaicinn a-rithist.

$1",

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Mu dhèidhinn {{SITENAME}}',
'aboutpage'            => 'Project:Mu dhèidhinn',
'copyright'            => 'Tha susbaint ri làimh fo $1.',
'copyrightpage'        => '{{ns:project}}:Còraichean lethbhric',
'currentevents'        => 'Cùisean an latha',
'currentevents-url'    => 'Project:Cùisean an latha',
'disclaimers'          => 'Aithrisean-àichidh',
'disclaimerpage'       => 'Project:Aithris-àichidh choitcheann',
'edithelp'             => 'Cobhair deasachaidh',
'edithelppage'         => 'Help:Deasachadh',
'helppage'             => 'Help:Susbaint',
'mainpage'             => 'Prìomh dhuilleag',
'mainpage-description' => 'Prìomh dhuilleag',
'policy-url'           => 'Project:Poileasaidh',
'portal'               => 'Doras na Coimhearsnachd',
'portal-url'           => 'Project:Doras na coimhearsnachd',
'privacy'              => 'Am polasaidh prìobhaideachd',
'privacypage'          => 'Project:Am polasaidh prìobhaideachd',

'badaccess'        => 'Meareachd le cead',
'badaccess-group0' => "Chan eil cead agad an gnìomh a dh'iarr thu a thoirt gu buil.",

'versionrequired'     => 'Feum air tionndadh $1 de MhediaWiki',
'versionrequiredtext' => 'Tha feum air tionndadh $1 de MhediaWiki mus faicear an duilleag seo.
Seall air [[Special:Version|duilleag an tionndaidh]].',

'ok'                      => 'Ceart ma-thà',
'retrievedfrom'           => 'Air a tharraing à "$1"',
'youhavenewmessages'      => 'Tha $1 ($2) agad.',
'newmessageslink'         => 'teachdaireachdan ùra',
'newmessagesdifflink'     => 'mùthadh mu dheireadh',
'youhavenewmessagesmulti' => 'Tha teachdaireachdan ùra agad ann an $1',
'editsection'             => 'deasaich',
'editold'                 => 'deasaich',
'viewsourceold'           => 'seall an tùs',
'editlink'                => 'deasaich',
'viewsourcelink'          => 'seall an tùs',
'editsectionhint'         => 'Deasaich earrann: $1',
'toc'                     => 'Susbaint',
'showtoc'                 => 'seall',
'hidetoc'                 => 'falaich',
'thisisdeleted'           => 'Seall no aisig $1?',
'viewdeleted'             => 'Seall $1?',
'feedlinks'               => 'Inbhir:',
'feed-invalid'            => "Seòrsa mì-dhligheach de dh'fho-sgrìobhadh inbhir.",
'feed-unavailable'        => 'Chan eil inbhirean co-bhanntachd ri fhaighinn',
'site-rss-feed'           => '$1 Inbhir RSS',
'site-atom-feed'          => '$1 Inbhir Atom',
'page-rss-feed'           => '"$1" Inbhir RSS',
'page-atom-feed'          => '"$1" Inbhir Atom',
'red-link-title'          => '$1 (chan eil duilleag ann fhathast)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Duilleag',
'nstab-user'      => "Duilleag a' chleachdaiche",
'nstab-media'     => 'Meadhanan',
'nstab-special'   => 'Duilleag shònraichte',
'nstab-project'   => "Duilleag a' phròiseict",
'nstab-image'     => 'Faidhle',
'nstab-mediawiki' => 'Teachdaireachd',
'nstab-template'  => 'Teamplaid',
'nstab-help'      => 'Cuideachadh',
'nstab-category'  => 'Roinn',

# Main script and global functions
'nosuchaction'      => 'Chan eil a leithid de ghnìomh ann',
'nosuchactiontext'  => "Tha an gnìomh a shònraich an t-URL mì-dhligheach.
Faodaidh gun do chuir thu a-steach URL mearachdach no gun do lean thu ri ceangal mearachdach.
Cuideachd, faodaidh gu bheil seo 'na chomharradh air buga sa bhathar-bhog aig {{SITENAME}}",
'nosuchspecialpage' => "Chan eil duilleag shònraichte d' a leithid ann",
'nospecialpagetext' => "<strong>Dh'iarr thu duilleag shònraichte mhì-dhligheach.</strong>

Gheibh thu liosta nan duilleagan sònraichte 's dligheach aig [[Special:SpecialPages|{{int:specialpages}}]].",

# General errors
'error'                => 'Mearachd',
'databaseerror'        => 'Mearachd an stòir-dhàta',
'dberrortext'          => 'Thachair mearachd co-chàraidh rè iarrtas an stòir-dhàta.
Faodaidh gu bheil seo a\' comharrachadh mearachd sa bhathar-bhog.
Seo iarrtas an stòir-dhàta mu dheireadh a chaidh feuchainn ris:
<blockquote><tt>$1</tt></blockquote>
o bhroinn an fhoincsein "<tt>$2</tt>".
Thill an stòr-dàta a\' mhearachd "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Thachair mearachd co-chàraidh rè iarrtas an stòir-dhàta.
Seo iarrtas an stòir-dhàta mu dheireadh a chaidh feuchainn ris:
"$1"
o bhroinn an fhoincsein "$2".
Thill an stòr-dàta a\' mhearachd "$3: $4"',
'laggedslavemode'      => "'''Rabhadh:''' Faodaidh nach eil ùrachaidhean a rinneadh o chionn ghoirid a' nochdadh san duilleag.",
'readonly'             => 'Stòr-dàta glaiste',
'enterlockreason'      => "Cuir a-steach adhbhar a' ghlais, a' gabhail a-steach tuairmeas air fuasgladh a' ghlais.",
'readonlytext'         => "Tha an stòr-dàta glaiste do chlàir ùra 's mùthaidhean eile, ma dh'fhaoidte air sgàth obair-chàraidh chunbhalach an stòir-dhàta 's bidh e mar as àbhaist às dèidh sin.

Chuir an rianadair a ghlas e an cèill na leanas: $1",
'missing-article'      => 'Cha do lorg an stòr-dàta teacsa de dhuilleag a bu chòir a bhith air a lorg aige \'s air a bheil "$1" $2.

\'S e mùthaidhean no ceangal eachdraidheil ro shean ri duilleag a chaidh a sguabadh às a bhios coireach à seo mar is trice.

Mur eil seo fìor, faodaidh gun do lorg thu buga sa bhathar-bhog.
An dèan thu aithris air seo do [[Special:ListUsers/sysop|rianadair]], ag innse dhaibh dè an t-URL a bha ann.',
'missingarticle-rev'   => '(mùthadh#: $1)',
'missingarticle-diff'  => '(Diof: $1, $2)',
'readonly_lag'         => "Chaidh an stòr-dàta a ghlasadh leis fhèin fhad 's a tha frithealaichean nan stòr-dàta tràilleach air dheireadh a' mhaighstir",
'internalerror'        => 'Ion-mhearachd',
'internalerror_info'   => 'Ion-mhearachd: $1',
'fileappenderrorread'  => 'Cha do ghabh "$1" a leughadh fhad \'s a bhathar \'ga chur ris.',
'fileappenderror'      => 'Cha do ghabh "$1" a chur ri "$2".',
'filecopyerror'        => 'Cha do ghabh lethbhreac dhen fhaidhle "$1" gu "$2".',
'filerenameerror'      => 'Cha do ghabh ainm an fhaidhle "$1" atharrachadh gu "$2".',
'filedeleteerror'      => 'Cha do ghabh am faidhle "$1" a sguabadh às.',
'directorycreateerror' => 'Cha do ghabh am pasgan "$1" a chruthachadh.',
'filenotfound'         => 'Cha do ghabh am faidhle "$1" a lorg.',
'fileexistserror'      => 'Chan urrainnear sgrìobhadh gun fhaidhle "$1": tha am faidhle ann mu thràth',
'unexpected'           => 'Luach ris nach robh dùil: "$1"="$2".',
'formerror'            => 'Mearachd: cha do ghabh am foirm a chur a-null',
'badarticleerror'      => 'Cha ghabh an gnìomh seo a dhèanamh air an duilleag seo.',
'cannotdelete'         => 'Cha do ghabh an duilleag no am faidhle "$1" a sguabadh às.
Faodaidh gun deach a sguabadh às le cuideigin eile mu thràth.',
'badtitle'             => 'Droch thiotal',
'badtitletext'         => "Bha an duilleag a dh'iarr thu mì-dhligheach, falamh no le tiotal eadar-chànanach no eadar-uici air a dhroch cheangal.
Faodaidh gu bheil aon no barrachd charactairean ann nach urrainn dhut a chleachdadh ann an tiotalan.",
'perfcached'           => "Chaidh an dàta a leanas a thasgadh 's faodaidh gu bheil e air dheireadh.",
'perfcachedts'         => "Chaidh an dàta a leanas a thasgadh 's chaidh ùrachadh $1 turas mu dheireadh.",
'querypage-no-updates' => 'Tha ùrachadh air a chur à comas air an duilleag seo an-dràsta.
Cha dèid an dàta an-seo ùrachadh aig an àm seo.',
'wrong_wfQuery_params' => 'Paramatairean mì-cheart airson wfQuery()<br />
Foincsean: $1<br />
Iarrtas: $2',
'viewsource'           => 'Seall an tùs',
'viewsourcefor'        => 'airson $1',
'actionthrottled'      => 'Gnìomh air a mhùchadh',
'actionthrottledtext'  => "Gus casg a chur air spama, chan urrainn dhut an gnìomh seo a dhèanamh ro thric am broinn ùine ghoirid agus chaidh thu thairis air a' chrìoch seo.
Feuch ris a-rithist às a dhèidh seo.",
'protectedpagetext'    => 'Chaidh an duilleag seo a ghlasadh gus casg a chur air deasachadh.',
'viewsourcetext'       => "'S urrainn dhut coimhead air tùs na duilleige seo 's lethbhreac a dhèanamh dheth:",
'protectedinterface'   => "Tha an duilleag seo a' solar teacsa eadar-aghaidh airson a' bhathar-bhog is chaidh a ghlaadh gus casg a chur air mì-chleachdadh.",
'editinginterface'     => "'''Rabhadh:''' Tha thu a' deasachadh duilleag a tha 'ga chleachdadh a chum teacsa eadar-aghaidh a sholar airson a' bhathar-bhog.
Ma dh'atharraicheas tu an duilleag seo, bidh buaidh ann air coltas na h-eadar-aghaidh mar a chì càch i.
Ma tha thu airson Gàidhlig a chur air, saoil an cleachd thu [http://translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net], am pròiseact ionadailidh aig MediaWiki?",
'sqlhidden'            => "(Iarrtas SQL 'ga fhalach)",
'namespaceprotected'   => "Chan eil cead agad duilleagan san namespace '''$1''' a dheasachadh.",
'customcssjsprotected' => "Chan eil cead agad an duilleag seo a dheasachadh a chionn 's gu bheil na roghainnean pearsanta aig cleachdaiche eile innte.",
'ns-specialprotected'  => 'Chan ghabh duilleagan sònraichte a dheasachadh.',
'titleprotected'       => 'Chaidh an duilleag seo a dhìon o chruthachadh le [[User:$1|$1]].
Seo am mìneachadh: "\'\'$2\'\'".',

# Virus scanner
'virus-badscanner'     => "Droch cho-dhealbhachd: sganair bhìorasan neo-aithnichte: ''$1''",
'virus-scanfailed'     => "dh'fhàillig an sganadh (còd $1)",
'virus-unknownscanner' => 'sganair bhìorasan neo-aithnichte:',

# Login and logout pages
'welcomecreation'            => '== Fàilte ort, $1! ==
Chaidh an cunntas agad a chruthachadh.
Na dìochuimhnich na [[Special:Preferences|roghainnean agad air {{SITENAME}}]] a ghleusadh dhut fhèin.',
'yourname'                   => 'Ainm-cleachdaiche:',
'yourpassword'               => 'Am facal-faire agad',
'yourpasswordagain'          => 'Ath-sgrìobh facal-faire',
'remembermypassword'         => "Cuimhnich gu bheil mi air logadh a-steach air a' choimpiutair seo (suas gu $1 {{PLURAL:$1|latha|làithean}})",
'login'                      => 'Log a-steach',
'nav-login-createaccount'    => 'Log a-steach / cruthaich cunntas',
'userlogin'                  => 'Log a-steach / cruthaich cunntas',
'logout'                     => 'Log a-mach',
'userlogout'                 => 'Log a-mach',
'nologinlink'                => 'Cruthaich cunntas',
'createaccount'              => 'Cruthaich cunntas ùr',
'loginerror'                 => 'Mearachd log a-steach',
'noname'                     => 'Cha do thagh thu ainm-cleachdaiche dligheach.',
'nosuchusershort'            => 'Chan eil cleachdaiche ann leis an ainm "$1".
Cuir sùil air an litreachadh.',
'wrongpassword'              => 'Chuir thu a-steach facal-faire cearr.
Am feuch thu ris a-rithist?',
'mailmypassword'             => "Cuir facal-faire ùr thugam air a' phost-dealain",
'acct_creation_throttle_hit' => "Chruthaich na h-aoighean air an Uici seo {{PLURAL:$1|chunntas|chunntas|chunntas|chunntas|cunntasan|cunntas}} fon IP agad an-dè agus sin an àireamh as motha a tha ceadaichte. Chan urrainn do dh'aoighean eile on IP seo barrachd chunntasan a chruthachadh air sgàth sin.",
'accountcreated'             => 'Cunntas cruthaichte',

# Password reset dialog
'oldpassword' => 'Seann fhacal-faire',
'newpassword' => 'Facal-faire ùr',
'retypenew'   => 'Ath-sgrìobh am facal-faire ùr',

# Edit page toolbar
'bold_sample'     => 'Teacs trom',
'bold_tip'        => 'Teacs trom',
'italic_sample'   => 'Teacsa Eadailteach',
'italic_tip'      => 'Teacsa Eadailteach',
'link_sample'     => "Tiotal a' cheangail",
'link_tip'        => 'Ceangal am broinn na làraich',
'extlink_sample'  => "http://www.example.com tiotal a' cheangail",
'extlink_tip'     => 'Ceangal dhan taobh a-muigh (cuimhnich an ro-leasachan http://)',
'headline_sample' => 'Teacsa ceann-loidhne',
'headline_tip'    => 'Ceann-loidhne ìre 2',
'math_sample'     => 'Cuir a-steach foirmle an-seo',
'math_tip'        => 'Foirmle matamataig (LaTeX)',
'nowiki_sample'   => 'Cuir a-steach teacsa gun fhòrmatadh an-seo',
'nowiki_tip'      => 'Leig seachad fòrmatadh uici',
'image_sample'    => 'Eisimpleir.jpg',
'image_tip'       => 'Faidhle air a leabachadh',
'media_sample'    => 'Eisimpleir.ogg',
'media_tip'       => 'Ceangal faidhle',
'sig_tip'         => "D' ainm sgrìobhte le stampa-ama",
'hr_tip'          => 'Loidhne rèidh (na cleachd ro thric e)',

# Edit pages
'summary'                          => 'Gearr-chunntas:',
'subject'                          => 'Cuspair/ceann-loidhne:',
'minoredit'                        => 'Seo mùthadh beag',
'watchthis'                        => 'Cum sùil air an duilleag seo',
'savearticle'                      => 'Sàbhail an duilleag',
'preview'                          => 'Ro-shealladh',
'showpreview'                      => 'Seall an ro-shealladh',
'showdiff'                         => 'Seall na mùthaidhean',
'anoneditwarning'                  => "'''Rabhadh:''' Chan eil thu air logadh a-steach.
Thèid an seòladh IP agad a chlàrachadh ann an eachdraidh na duilleige seo.",
'summary-preview'                  => "Ro-shealladh a' ghearr-chunntais:",
'blockedtitle'                     => 'Tha an cleachdair air a bhacadh',
'loginreqlink'                     => 'log a-steach',
'accmailtitle'                     => 'Facal-faire air a chur.',
'accmailtext'                      => "Chaidh facal-faire a chruthachadh air thuaiream airson [[User talk:$1|$1]] 's a chur gu $2.

Gabhaidh am facal-faire airson a' chunntais ùir seo atharrachadh air an fo ''[[Special:ChangePassword|atharraich facal-faire]]'' as dèidh do chleachdaiche logadh a-steach.",
'newarticle'                       => '(Ùr)',
'newarticletext'                   => "Lean thu ri ceangal gu duilleag nach eil ann fhathast.
Cuir teacs sa bhogsa gu h-ìosal gus an duilleag seo a chruthachadh (seall air [[{{MediaWiki:Helppage}}|duilleag na cobharach]] airson barrachd fiosrachaidh).
Mura robh dùil agad ris an duilleag seo a ruigsinn, briog air a' phutan '''air ais''' 'nad bhrabhsair.",
'noarticletext'                    => 'Chan eil teacsa sam bith anns an duilleag seo an-dràsta.
\'S urrainn dhut [[Special:Search/{{PAGENAME}}|an tiotal seo a lorg]] ann an duilleagan eile,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} na logaichean co-cheangailte a rannsachadh],
no [{{fullurl:{{FULLPAGENAME}}|action=edit}} an duilleag seo a dheasachadh]</span>.',
'updated'                          => '(Air ùrachadh)',
'previewnote'                      => "'''Cuimhnich nach eil ann ach ro-shealladh.'''
Cha deach na mùthaidhean agad a shàbhaladh fhathast!",
'editing'                          => "A' deasachadh $1",
'editingsection'                   => "A' deasachadh $1 (earrann)",
'editconflict'                     => 'Còmhstri deasachaidh: $1',
'explainconflict'                  => "Tha cuideigin eile air an duilleag seo a mhùthadh on a thòisich thu fhèin air a dheasachadh.
Tha am bogsa teacsa gu h-àrd a' nochdadh na duilleige mar a tha i an-dràsta.
Tha na mùthaidhean agadsa sa bhogsa gu h-ìosal.
Bidh agad ris na mùthaidhean agad fhilleadh a-steach san teacsa làithreach.
Cha dèid '''ach an teacsa gu h-àrd''' a shàbhaladh nuair a bhriogas tu air \"{{int:savearticle}}\".",
'yourtext'                         => 'An teacsa agad',
'storedversion'                    => 'Lethbhreac taisgte',
'editingold'                       => "'''RABHADH: Tha thu a' deasachadh lethbhreac seann-aimsireil na duilleige seo.
Ma shàbhalas tu seo, thèid gach mùthadh air chall a rinneadh a-mach on mhùthadh seo.'''",
'yourdiff'                         => 'Caochlaidhean',
'copyrightwarning'                 => "Thoir an aire gu bheilear a' tuigsinn gu bheil gach rud a chuireas tu ri {{SITENAME}} air a leigeil mu sgaoil fo $2 (see $1 airson mion-fhiosrachadh).
Mura bi thu toilichte 's daoine eile a' deasachadh gun tròcair na sgrìobh tu 's 'ga sgaoileadh mar a thogras iad, na cuir an-seo e.<br />
Tha thu a' toirt geall cuideachd gun do sgrìobh thu fhèin seo no gun do rinn thu lethbhreac dheth o àrainn phoblach no tùs saor coltach ris.
'''Na cuir ann rudan fo chòir lethbhric gun chead!'''",
'copyrightwarning2'                => "Ged a thatar gur moladh {{SITENAME}} a chruthachadh, a mheudachadh, is a leasachadh, thèid droch dheasaicheidhean a chur air imrich gu luath.
Mur eil thu ag iarraidh an sgrìobhaidh agad a dheasaichear is a sgaoilear le càch, na cuir e.<br />
Ma dh'fhoilleachas tu rudeigin an seo, bidh tu a' dearbhadh gun do sgrìobh thu fhèin e, no gur ann às an raon phòballach a thàinig e; thoir aire '''nach eil''' sin a' gabhail a-staigh duilleagan-lìn mar as àbhaist (seall $1 airson barrachd fiosrachaidh). <br />
'''NA CLEACHDAIBH SAOTHAIR FO DHLIGHE-SGRÌOBHAIDH GUN CHEAD!'''",
'protectedpagewarning'             => "'''Rabhadh: Chaidh an duilleag seo a dhìon 's chan urrainn ach dhan fheadhainn aig a bheil ùghdarras rianaire a dheasachadh.'''
Chì thu an clàr mu dheireadh san loga mar fhiosrachadh dhut gu h-ìosal:",
'templatesused'                    => "Tha {{PLURAL:$1|teamplaid|theamplaid||teamplaid|theamplaid|teamplaidean|teamplaid}} 'gan cleachdadh air an duilleag seo:",
'templatesusedpreview'             => "Tha {{PLURAL:$1|teamplaid 'ga cleachdadh|teamplaidean 'gan cleachdadh|teamplaidean 'gan cleachdadh|teamplaidean 'gan cleachdadh|teamplaidean 'gan cleachdadh|teamplaidean 'gan cleachdadh}} san ro-shealladh seo:",
'template-protected'               => '(air a dhìon)',
'template-semiprotected'           => '(air a leth-dhìon)',
'hiddencategories'                 => "Tha an duilleag seo 'na ball de {{PLURAL:$1|1 roinn-seòrsa fhalaichte|$1 roinn-seòrsa fhalaichte|1 roinn-seòrsa fhalaichte|$1 roinn-seòrsa fhalaichte|$1 roinnean-seòrsa falaichte|$1 roinn-seòrsa fhalaichte}}:",
'permissionserrorstext-withaction' => 'Chan eil cead agad airson "$2" air sgàth {{PLURAL:$1|an adhbhair|nan adhbharan|an adhbhair|nan adhbharan|nan adhbharan}} a leanas:',

# History pages
'viewpagelogs'           => 'Seall logaichean na duilleige seo',
'nohistory'              => 'Chan eil eachdraidh deasachaidh aig an duilleag seo.',
'currentrev'             => 'Lethbhreac làithreach',
'currentrev-asof'        => 'Am mùthadh mu dheireadh on $1',
'revisionasof'           => 'Mùthadh on $1',
'previousrevision'       => '← Mùthadh nas sine',
'nextrevision'           => 'Mùthadh nas ùire →',
'currentrevisionlink'    => 'Am mùthadh mu dheireadh',
'cur'                    => 'làith',
'next'                   => 'ath',
'last'                   => 'roimhe',
'histlegend'             => "Taghadh nan diofar: comharraich bogsaichean rèidio nam mùthaidhean gus coimeas a dhèanamh agus put Enter no am putan gu h-ìosal.<br />
Mìneachadh: '''({{int:cur}})''' = an diofar eadar e 's am mùthadh as ùire, '''({{int:last}})''' = an diofar eadar e 's am mùthadh roimhe, '''{{int:minoreditletter}}''' = deasachadh beag.",
'history-fieldset-title' => 'An eachdraidh brabhsaidh',
'histfirst'              => 'As sine',
'histlast'               => 'As ùire',

# Revision deletion
'rev-delundel'   => 'seall/falaich',
'revdel-restore' => 'mùth follaiseachd',

# Merge log
'revertmerge' => 'Dì-aontaich',

# Diffs
'history-title'           => 'Eachdraidh nam mùthaidhean de "$1"',
'difference'              => '(An diofar eadar na mùthaidhean)',
'lineno'                  => 'Loidhne $1:',
'compareselectedversions' => 'Dèan coimeas eadar na mùthaidhean a thagh thu',
'editundo'                => 'neo-dhèan',

# Search results
'searchresults'             => 'Toraidhean rannsachaidh',
'searchresults-title'       => 'Lorg "$1" am broinn nan toraidhean',
'searchresulttext'          => 'Airson barrachd fiosrachaidh mu rannsachadh {{SITENAME}}, cuir sùil air [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'            => 'Lorg thu \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|gach duilleag a tha a\' tòiseachadh le "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|gach duilleag a tha a\' ceangal ri "$1"]])',
'searchsubtitleinvalid'     => "Lorg thu airson '''$1'''",
'notitlematches'            => "Chan eil tiotal de dhuilleag sam bith a' freagairt ris",
'notextmatches'             => "Chan eil tiotal de dhuilleag sam bith a' freagairt ris",
'prevn'                     => 'an {{PLURAL:$1|$1}} mu dheireadh',
'nextn'                     => 'an ath {{PLURAL:$1|$1}}',
'viewprevnext'              => 'Seall ($1 {{int:pipe-separator}} $2) ($3).',
'searchhelp-url'            => 'Help:Cuideachadh',
'search-result-size'        => '$1 ({{PLURAL:$2|1 fhacal|$2 fhacal|1 fhacal|$2 fhacal|$2 faclan|$2 facal}})',
'search-redirect'           => '(ag ath-sheòladh $1)',
'search-section'            => '(earrann $1)',
'search-suggest'            => 'An e na leanas a bha fa-near dhut: $1',
'search-interwiki-caption'  => 'Pròiseactan co-cheangailte',
'search-interwiki-default'  => 'Toraidhean $1:',
'search-interwiki-more'     => '(barrachd)',
'search-mwsuggest-enabled'  => 'le molaidhean',
'search-mwsuggest-disabled' => 'gun mholaidhean',
'showingresults'            => "A' nochdadh suas gu $1 {{PLURAL:$1|toradh|thoradh|toradh|thoradh|toraidhean|toradh}} gu h-ìosal a' tòiseachadh le #'''$2'''.",
'showingresultsnum'         => "A' nochdadh '''$3''' {{PLURAL:$3|toradh|thoradh|toradh|thoradh|toraidhean|toradh}} gu h-ìosal a' tòiseachadh le #'''$2'''.",
'nonefound'                 => "'''Aire''': Chan dèid ach cuid dhe na namespaces a lorg a ghnàth.
Feuch ri ''all:'' a chuir air beulaibh an iarrtais agad gus rannsachadh a dhèanamh am broinn na susbainte gu lèir (a' gabhail a-steach nan duilleagan conaltraidh, teamplaidean is msaa), no cleachd an namespace a bha thu ag iarraidh mar ro-leasachan.",
'powersearch'               => 'Rannsachadh adhartach',
'powersearch-legend'        => 'Rannsachadh adhartach',
'powersearch-ns'            => 'Lorg ann an namespaces:',
'powersearch-redir'         => 'Seall ath-sheòlaidhean',
'powersearch-field'         => 'Lorg',

# Preferences page
'preferences'    => 'Roghainnean',
'mypreferences'  => 'Mo roghainnean',
'changepassword' => 'Atharraich facal-faire',
'prefs-skin'     => 'Bian',
'skin-preview'   => 'Ro-shealladh',
'prefs-personal' => "Pròifil a' chleachdaiche",
'saveprefs'      => 'Sàbhail',
'resetprefs'     => 'Falamhaich atharrachaidhean nach deach a shàbhaladh fhathast',
'rows'           => 'Sreathan',
'columns'        => 'Colbhan',
'savedprefs'     => 'Tha na roghainnean agad air an sàbhaladh.',
'default'        => 'an roghainn bhunaiteach',
'youremail'      => 'Post-dealain:',
'username'       => 'Ainm-cleachdaiche:',
'yourrealname'   => "An dearbh ainm a th' ort:",
'yourlanguage'   => 'Cànan:',
'yournick'       => 'Earr-sgrìobhadh ùr:',

# User rights
'userrights-changeable-col' => 'Buidhnean as urrainn dhut atharrachadh',

# Groups
'group-sysop' => 'Rianadairean',

'grouppage-sysop' => '{{ns:project}}:Rianadairean',

# User rights log
'rightslog' => "Loga còraichean a' chleachdaiche",

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'deasaich an duilleag seo',
'action-move' => 'gluais an duilleag seo',

# Recent changes
'nchanges'                       => '{{PLURAL:$1|mhùthadh|mhùthadh|mhùthadh|mhùthadh|mùthaidhean|mùthadh}}',
'recentchanges'                  => 'Mùthaidhean ùra',
'recentchanges-legend'           => 'Roghainnean nam mùthaidhean ùra',
'recentchangestext'              => 'Cum sùil air na mùthaidhean as ùire a nithear air an uici air an duilleag seo.',
'recentchanges-feed-description' => 'Cum sùil air na mùthaidhean as ùire a nithear air an uici seo san inbhir seo.',
'rcnote'                         => 'Tha {{PLURAL:$1|an $1 mhùthadh|an $1 mhùthadh|an $1 mhùthadh|an $1  mhùthadh|na $1 mùthaidhean|na $1 mùthadh}} mu dheireadh anns na $2 {{PLURAL:$2|latha|latha|latha|latha|làithean|latha}} mu dheireadh, mar a bha iad $5, $4.',
'rcnotefrom'                     => "Gheibhear na mùthaidhean a-mach o '''$2''' (gu ruige '''$1''') gu h-ìosal.",
'rclistfrom'                     => 'Seall na mùthaidhean ùra a-mach o $1',
'rcshowhideminor'                => '$1 mùthaidhean beaga',
'rcshowhidebots'                 => '$1 botaichean',
'rcshowhideliu'                  => '$1 neach-cleachdaidh air logadh a-steach',
'rcshowhideanons'                => '$1 luchd-cleachdaidh gun ainm',
'rcshowhidemine'                 => '$1 na mùthaidhean agam',
'rclinks'                        => 'Seall na $1 mùthaidhean mu dheireadh thairis air na $2 làithean mu dheireadh<br />$3',
'diff'                           => 'diof',
'hist'                           => 'eachd',
'hide'                           => 'Falaich',
'show'                           => 'Seall',
'minoreditletter'                => 'b',
'newpageletter'                  => 'Ù',
'boteditletter'                  => 'bt',
'rc-enhanced-expand'             => 'Seall am mion-fhiosrachadh (feumaidh seo JavaScript)',
'rc-enhanced-hide'               => 'Cuir am mion-fhiosrachadh am falach',

# Recent changes linked
'recentchangeslinked'         => 'Mùthaidhean co-cheangailte',
'recentchangeslinked-feed'    => 'Mùthaidhean buntainneach',
'recentchangeslinked-toolbox' => 'Mùthaidhean buntainneach',
'recentchangeslinked-title'   => 'Mùthaidhean co-cheangailte ri "$1"',
'recentchangeslinked-summary' => "Seo liosta nam mùthaidhean a chaidh a chur air duilleagan a tha a' ceangal o dhuilleag shònraichte (no ri buill de roinn shònraichte).
Tha duilleagan air [[Special:Watchlist|do chlàr-faire]] ann an litrichean '''troma'''.",
'recentchangeslinked-page'    => 'Ainm na duilleige:',
'recentchangeslinked-to'      => "Seall mùthaidhean nan duilleagan a tha a' ceangal ris an duilleag sin 'na àite",

# Upload
'upload'        => 'Luchdaich a-nuas faidhle',
'uploadlogpage' => 'Loga an luchdaidh suas',
'filename'      => 'Ainm-faidhle',
'filedesc'      => 'Gearr-chunntas',
'filestatus'    => 'Cor dlighe-sgrìobhaidh:',
'ignorewarning' => 'Leig seachad an rabhadh agus sàbhail am faidhle co-dhiù',
'badfilename'   => 'Ainm ìomhaigh air atharrachadh ri "$1".',
'fileexists'    => "Tha faidhle ann mu thràth air a bheil an t-ainm seo, cuir sùil air '''<tt>[[:$1]]</tt>''' mur eil thu buileach cinntach a bheil thu airson atharrachadh.
[[$1|thumb]]",
'savefile'      => 'Sàbhail faidhle',
'uploadedimage' => 'a luchdaich suas "[[$1]]"',

# Special:ListFiles
'listfiles' => 'Liosta nan ìomhaigh',

# File description page
'file-anchor-link'          => 'Ìomhaigh',
'filehist'                  => 'Eachdraidh an fhaidhle',
'filehist-help'             => 'Briog air ceann-là/àm gus am faidhle fhaicinn mar a nochd e aig an àm sin.',
'filehist-current'          => 'làithreach',
'filehist-datetime'         => 'Ceann-là/Àm',
'filehist-thumb'            => 'Meabh-dhealbh',
'filehist-thumbtext'        => 'Meanbh-dhealbh airson an tionndaidh on $1',
'filehist-user'             => 'Neach-cleachdaidh',
'filehist-dimensions'       => 'Meud',
'filehist-comment'          => 'Beachd',
'imagelinks'                => 'Ceanglaichean an fhaidhle',
'linkstoimage'              => "Tha {{PLURAL:$1|an duilleag|an $1 dhuilleag|an duilleag|an $1 dhuilleag|na $1 duilleagan|na $1 duilleag}} a leanas a' ceangal ris an fhaidhle seo:",
'sharedupload'              => 'Tha am faidhle seo o $1 agus faodaidh pròiseactan eile a chleachdadh.',
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
'nbytes'                  => '$1 {{PLURAL:$1|bhaidht|bhaidht|bhaidht|bhaidht|baidht|baidht}}',
'nmembers'                => '$1 {{PLURAL:$1|bhall|bhall|bhall|bhall|buill|ball}}',
'nviews'                  => '$1 {{PLURAL:$1|sealladh|shealladh|sealladh|shealladh|seallaidhean|sealladh}}',
'uncategorizedpages'      => 'Duilleagan gun roinn-seòrsa',
'uncategorizedcategories' => 'Roinnean-seòrsa gun roinn-seòrsa',
'unusedimages'            => 'Faidhlichean gun chleachdadh',
'prefixindex'             => 'A h-uile duilleag le ro-leasachan',
'shortpages'              => 'Duilleagan goirid',
'longpages'               => 'Duilleagan fada',
'listusers'               => 'Liosta nan cleachdaichean',
'newpages'                => 'Duilleagan ùra',
'ancientpages'            => 'Duilleagan as sìne',
'move'                    => 'Gluais',
'movethispage'            => 'Gluais an duilleag seo',
'pager-newer-n'           => '{{PLURAL:$1|1 nas ùire|$1 nas ùire|1 nas ùire|$1 nas ùire|$1 nas ùire|$1 nas ùire}}',
'pager-older-n'           => '{{PLURAL:$1|1 nas sine|$1 nas sine|1 nas sine|$1 nas sine|$1 nas sine|$1 nas sine}}',

# Book sources
'booksources'               => "Tùsan a tha 'nan leabhraichean",
'booksources-search-legend' => "Lorg tùsan a tha 'nan leabhraichean",
'booksources-go'            => 'Rach',

# Special:Log
'log'           => 'Logaichean',
'all-logs-page' => 'A h-uile loga poblach',

# Special:AllPages
'allpages'       => 'A h-uile duilleag',
'alphaindexline' => '$1 gu $2',
'nextpage'       => 'An ath dhuilleag ($1)',
'prevpage'       => 'An duilleag roimhe ($1)',
'allpagesfrom'   => "Seall duilleagan a tha a' tòiseachadh aig:",
'allpagesto'     => "Seall duilleagan a tha a' crìochnachadh aig:",
'allarticles'    => 'A h-uile duilleag',
'allpagessubmit' => 'Rach',

# Special:Categories
'categories'         => 'Roinnean-seòrsa',
'categoriespagetext' => "Tha duilleagan no meadhan {{PLURAL:$1|san roinn-seòrsa|san roinn-seòrsa|san roinn-seòrsa|san roinn-seòrsa|sna roinntean-seòrsa|san roinn-seòrsa}} a leanas.
Chan fhaicear [[Special:UnusedCategories|roinntean-seòrsa gun chleachdadh an-seo]].
Thoir sùil air na [[Special:WantedCategories|roinntean-seòrsa a thathar 'gan iarraidh cuideachd]].",

# Special:LinkSearch
'linksearch' => 'Ceanglaichean dhan taobh a-muigh',

# Special:Log/newusers
'newuserlogpage'          => 'Loga cruthachaidh de chleachdaichean',
'newuserlog-create-entry' => 'Cunntas de chleachdaiche ùr',

# Special:ListGroupRights
'listgrouprights-members' => '(liosta de bhuill)',

# E-mail user
'emailuser'    => 'Cuir post-dealain dhan chleachdaiche seo',
'emailfrom'    => 'O:',
'emailto'      => 'Gu:',
'emailsubject' => 'Cuspair:',
'emailmessage' => 'Teachdaireachd:',
'emailsend'    => 'Cuir',

# Watchlist
'watchlist'          => 'Mo chlàr-faire',
'mywatchlist'        => 'Mo chlàr-faire',
'nowatchlist'        => "Chan eil rud sam bith air a' chlàr-fhaire agad.",
'addedwatch'         => 'Air a chur ri do chlàr-faire',
'addedwatchtext'     => "Chaidh an duilleag \"[[:\$1]]\" a chur ri [[Special:Watchlist|do chlàr-faire]].
Nochdaidh mùthaidhean a nithear air an duilleag seo 's air an duilleag deasbaireachd a tha co-cheangailte ris an-seo san àm ri teachd agus nochdaidh an duilleag ann an litrichean '''troma''' ann an [[Special:RecentChanges|liosta nam mùthaidhean ùra]] gum bi e furasta ri fhaicinn.",
'removedwatch'       => 'Air a thoir air falbh o do chlàr-faire',
'removedwatchtext'   => 'Chaidh an duilleag "[[:$1]]" a thoirt air falbh o [[Special:Watchlist|do chlàr-faire]].',
'watch'              => 'Cum sùil air',
'watchthispage'      => 'Cum sùil air an duilleag seo',
'unwatch'            => 'Na cum sùil tuilleadh',
'watchnochange'      => "Cha deach na duilleagan air d' fhaire a dheasachadh anns a' chuairt ùine taisbeanta.",
'watchlist-details'  => 'Tha {{PLURAL:$1|$1 duilleag|$1 dhuilleag||$1 duilleag|$1 dhuilleag|$1 duilleagan|$1 duilleag}} air do chlàr-faire, gun luaidh air na duilleagan deasbaireachd.',
'watchmethod-recent' => "A' sgrùdadh deasachaidhean ùra airson duilleagan air d' fhaire",
'watchmethod-list'   => "A' sgrùdadh duilleagan air d' fhaire airson deasachaidhean ùra",
'watchlistcontains'  => 'Tha $1 {{PLURAL:$1|duilleag|dhuilleag|duilleag|dhuilleag|duilleagan|duilleag}} air do chlàr-faire.',
'wlnote'             => 'Seo $1 {{PLURAL:$1|mhùthadh mu dheireadh|mhùthadh mu dheireadh|na mùthaidhean mu dheireadh|mùthadh mu dheireadh}} anns na $2 {{PLURAL:$2|uair|uair|uairean|uair}} mu dheireadh.',
'wlshowlast'         => 'Seall na $1 uairean a thìde mu dheireadh $2 làithean mu dheireadh $3',
'watchlist-options'  => 'Roghainnean mo chlàir-faire',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => "'Ga chur air a' chlàr-fhaire...",
'unwatching' => "A' toirt far a' chlàir-fhaire...",

# Delete
'deletepage'             => 'Sguab às duilleag',
'confirm'                => 'Daingnich',
'excontent'              => "stuth a bh' ann: '$1'",
'exblank'                => 'bha duilleag falamh',
'delete-confirm'         => 'Sguab às "$1"',
'delete-legend'          => 'Sguab às',
'confirmdeletetext'      => "Tha thu an impis duilleag a sguabadh às agus a h-eachdraidh uile gu lèir.
Dearbhaich gu bheil thu airson seo a dhèanamh 's gun tuig thu a' bhuaidh a bhios ann agus gu bheil thu a' dèanamh seo a-rèir [[{{MediaWiki:Policy-url}}|a' phoileasaidh]].",
'actioncomplete'         => 'Gnìomh deiseil',
'deletedtext'            => 'Chaidh "<nowiki>$1</nowiki>" a sguabadh às.
Seall air $2 airson clàr de dhuilleagan a chaidh a sguabadh às o chionn ghoirid.',
'deletedarticle'         => '"[[$1]]" air a sguabadh às',
'dellogpage'             => 'Loga an sguabaidh às',
'reverted'               => 'Air aiseag gu tionndadh nas sine',
'deletecomment'          => 'Adhbhar:',
'deleteotherreason'      => 'Adhbhar eile/a bharrachd:',
'deletereasonotherlist'  => 'Adhbhar eile',
'deletereason-dropdown'  => "*Adhbharan cumanta airson sguabadh às
** Dh'iarr an t-ùghdar e
** Tha e a' briseadh na còrach-lethbhreac
** Milleadh",
'delete-edit-reasonlist' => 'Deasaich adhbharan sguabadh às',

# Rollback
'rollbacklink' => 'roilig air ais',
'editcomment'  => "Seo gearr-chunntas an deasachaidh: \"''\$1''\".",
'revertpage'   => 'Deasachaidhean a chaidh a thilleadh le [[Special:Contributions/$2|$2]] ([[User talk:$2|talk]]) dhan mhùthadh mu dheireadh le [[User:$1|$1]]',

# Protect
'protectlogpage'              => 'Loga an dìon',
'protectlogtext'              => "Tha liosta na chaidh a dhìon 's a neo-dhìon gu h-ìosal.
Cuir sùil air [[Special:ProtectedPages|liosta nan duilleagan fo dhìon]] airson liosta na fheadhainn a tha fo dhìon an-dràsta fhèin.",
'protectedarticle'            => '"[[$1]]" air a dhìon',
'modifiedarticleprotection'   => 'a dh\'atharraich an ìre dìon de "[[$1]]"',
'unprotectedarticle'          => 'a neo-dhìon "[[$1]]"',
'protect-title'               => 'A\' dìonadh "$1"',
'prot_1movedto2'              => '[[$1]] gluaiste ri [[$2]]',
'protect-legend'              => 'Daingnich dìonadh',
'protectcomment'              => 'Adhbhar:',
'protectexpiry'               => 'Falbhaidh an ùine air:',
'protect_expiry_invalid'      => 'Tha an t-àm-crìochnachaidh mì-dhligheach.',
'protect_expiry_old'          => 'Tha an t-àm crìochnachaidh seachad mu thràth.',
'protect-text'                => "Chì thu an ìre dìon dhen duilleag '''<nowiki>$1</nowiki>''' an-seo agus is urrainn dhut atharrachadh an-seo.",
'protect-locked-access'       => "Chan eil cead aig a' chunntas agad an ìre dìon de dhuilleag atharrachadh.
Seo roghainnean làithreach na duilleige '''$1''':",
'protect-cascadeon'           => "Tha an duilleag seo fo dhìon an-dràsta a chionn 's gu bheil e air a ghabhail a-steach {{PLURAL:$1|san duilleag|sna duilleagan|san duilleag|sna duilleagan|san duilleag|sna duilleagan}} a leanas aig a bheil dìon easach air.
'S urrainn dhut ìre dìon na duilleige seo atharrachadh ach cha bhi buaidh air an dìon easach.",
'protect-default'             => 'Ceadaich a h-uile cleachdaiche',
'protect-fallback'            => 'Iarr cead "$1"',
'protect-level-autoconfirmed' => 'Cuir bacadh air cleachdaichean ùra is feadhainn gun chlàrachadh',
'protect-level-sysop'         => 'Rianadairean a-mhàin',
'protect-summary-cascade'     => 'mar eas',
'protect-expiring'            => 'falbhaidh an ùine air $1 (UTC)',
'protect-cascade'             => "Dìon duilleagan a tha 'gan gabhail a-steach san duilleag seo (dìon mar eas)",
'protect-cantedit'            => "Chan urrainn dhut ìre dìon na duilleige seo atharrachadh a chionn 's nach eil cead deasachaidh agad air.",
'restriction-type'            => 'Cead:',
'restriction-level'           => 'Ìre bacaidh:',

# Undelete
'undeleterevisions' => 'Chaidh $1 {{PLURAL:$1|leth-bhreac|leth-bhreac|leth-bhreac|leth-bhreac|leth-bhreacan|leth-bhreac}} a chur san tasg-lann',
'undeletelink'      => 'seall/aisig',
'undeletedarticle'  => 'a dh\'aisig "[[$1]]"',

# Namespace form on various pages
'namespace'      => 'Namespace:',
'invert'         => 'Cuir na tagh mi bun os cionn',
'blanknamespace' => '(Prìomh)',

# Contributions
'contributions'       => 'Mùthaidhean an neach-chleachdaidh',
'contributions-title' => 'Mùthaidhean a rinn $1',
'mycontris'           => 'Mo mhùthaidhean',
'contribsub2'         => 'Do $1 ($2)',
'uctop'               => ' (barr)',
'month'               => 'On mhìos (agus na bu tràithe):',
'year'                => 'On bhliadhna (agus na bu tràithe):',

'sp-contributions-newbies'  => 'Seall mùthaidhean le cunntasan ùra a-mhàin',
'sp-contributions-blocklog' => 'an loga bacaidh',
'sp-contributions-talk'     => 'deasbaireachd',
'sp-contributions-search'   => 'Lorg mùthaidhean leis',
'sp-contributions-username' => 'Seòladh IP no ainm-cleachdaiche:',
'sp-contributions-submit'   => 'Lorg',

# What links here
'whatlinkshere'            => "Na tha a' ceangal a-nall an-seo",
'whatlinkshere-title'      => 'Duilleagan a tha a\' ceangal ri "$1"',
'whatlinkshere-page'       => 'Duilleag:',
'linkshere'                => "Tha na duilleagan a leanas a' ceangal ri '''[[:$1]]''':",
'isredirect'               => 'duilleag ath-sheòlaidh',
'istemplate'               => 'transclusion',
'isimage'                  => 'ceangal an deilbh',
'whatlinkshere-prev'       => '{{PLURAL:$1|roimhe|$1 roimhe|roimhe|$1 roimhe|$1 roimhe|$1 roimhe}}',
'whatlinkshere-next'       => '{{PLURAL:$1|an ath|an ath $1|an ath|an ath $1|an ath $1|an ath $1}}',
'whatlinkshere-links'      => '← ceanglaichean',
'whatlinkshere-hideredirs' => '$1 ath-sheòlaidhean',
'whatlinkshere-hidetrans'  => '$1 transclusions',
'whatlinkshere-hidelinks'  => '$1 ceanglaichean',
'whatlinkshere-filters'    => 'Criathairean',

# Block/unblock
'blockip'                  => 'Bac cleachdaiche',
'ipaddress'                => 'IP Seòladh/ainm-cleachdaiche',
'ipbreason'                => 'Adhbhar:',
'ipbsubmit'                => 'Bac an cleachdaiche seo',
'ipboptions'               => '2 uair a thìde:2 hours, 1 latha:1 day, 3 làithean:3 days, 1 seachdain:1 week, 2 sheachdain:2 weeks, 1 mhìos:1 month, 3 mìosan:3 months, 6 mìosan:6 months, 1 bhliadhna:1 year,neo-chrìochnach:infinite',
'badipaddress'             => "Chan eil an seòladh IP aig a' cleachdair seo iomchaidh",
'blockipsuccesssub'        => "Shoirbhich leat leis a' bhacadh",
'blockipsuccesstext'       => "Tha [[Special:Contributions/$1|$1]] air a bhacadh.
<br />Faic [[Special:IPBlockList|Liosta nan IP baicte]] na bacaidhean a dh'ath-sgrùdadh.",
'unblockip'                => 'Neo-bhac an cleachdaiche',
'ipusubmit'                => 'Thoir air falbh am bacadh seo',
'ipblocklist'              => 'Liosta de sheòlaidhean IP is ainmean chleachdaichean a chaidh a bhacadh',
'blocklink'                => 'bac',
'unblocklink'              => 'neo-bhac',
'change-blocklink'         => 'mùth bacadh',
'contribslink'             => 'mùthaidhean',
'blocklogpage'             => 'Loga nam bacadh',
'blocklogentry'            => 'Chaidh bacadh a chrìochnaicheas ann an $2 a chur air [[$1]] $3',
'unblocklogentry'          => '"$1" air a neo-bhacadh',
'block-log-flags-nocreate' => 'cruthachadh de chunntasan ùra à comas',
'ipb_expiry_invalid'       => 'Tha an t-àm-crìochnachaidh mì-dhligheach.',
'ip_range_invalid'         => 'Raon IP neo-iomchaidh.',
'proxyblocksuccess'        => 'Dèanta.',

# Developer tools
'lockdb'           => 'Glais an stòr-dàta',
'lockconfirm'      => 'Seadh, is ann a tha mi ag iarraidh an stòr-dàta a ghlasadh.',
'lockbtn'          => 'Glais an stòr-dàta',
'lockdbsuccesssub' => 'Shoirbhich leat le glasadh an stòir-dhàta',

# Move page
'move-page-legend'        => 'Gluais duilleag',
'movepagetext'            => "Ma chleachdas tu am foirm gu h-ìosal, cuiridh tu ainm ùr air 's gluaisidh tu a h-eachdraidh gu lèir dhan ainm ùr.
Bidh an seann tiotal 'na ath-sheòladh dhan tiotal ùr an uairsin.
'S urrainn dhut ath-sheòladh sam bith a tha a' dol dhan tiotal tùsail ùrachadh leis fhèin.
Mura dèan thu sin, dèan cinntach gun cuir thu sùil air eagal 's gum bi [[Special:DoubleRedirects|ath-sheòlaidhean dùbailte]] no [[Special:BrokenRedirects|briste]] ann.
'S ann ort-sa a tha an t-uallach airson dèanamh cinntach gu bheil na ceanglaichean a' dol dha na h-àitichean ceart.

Thoir an aire '''nach dèid''' an duilleag a ghluasad ma tha duilleag air an tiotal ùr mu thràth ach ma bhios e falamh no 'na ath-sheòladh 's mur eil eachdraidh deasachaidh ann.
'S ciall dha seo gun urrainn dhut ainm duilleige a thilleadh dhan ainm a bha air roimhe ma rinn thu mearachd agus nach urrainn dhut sgrìobhadh thairis air duilleag a tha ann.

'''Rabhadh!'''
Faodaidh seo a bhith 'na atharrachadh mòr ris nach bi dùil air duilleag air a bheil fèill mhòr;
dèan cinntach gu bheil thu a' tuigsinn dè a' bhuaidh a bhios agad mus dèid thu air adhart.",
'movepagetalktext'        => "Thèid an duilleag deasbaireachd a tha co-cheangailte ris a ghluasad 'na cois '''ach:'''
*Ma tha duilleag deasbaireachd nach eil falamh aig an ainm ùr mu thràth, no
*Ma bheir thu air falbh a' chromag on bhogsa gu h-ìosal

Ma thachras seo, feumaidh to an duilleag a ghluasad no cho-aontachadh a làimh, ma tha sin fa-near dhut.",
'movearticle'             => 'Gluais duilleag:',
'newtitle'                => 'Dhan tiotal ùr:',
'move-watch'              => 'Cum sùil air an duilleag thùsail agus an duilleag thairgaideach',
'movepagebtn'             => 'Gluais duilleag',
'pagemovedsub'            => "Shoirbhich leat leis a' ghluasad",
'movepage-moved'          => '\'\'\'Chaidh "$1" a ghluasad a "$2"\'\'\'',
'articleexists'           => 'Tha duilleag ann mu thràth air a bheil an t-ainm seo no chan eil an t-ainm a thagh thu dligheachd.
Nach tagh thu ainm eile?',
'talkexists'              => "'''Chaidh an duilleag fhèin a ghluasad gu soirbheachail ach cha do ghabh an duilleag deasbaireachd a ghluasad a chionn 's gu bheil tè ann aig an tiotal ùr mu thràth.
Bidh agad ris an co-aontachadh a làimh.'''",
'movedto'                 => 'air a ghluasad a',
'movetalk'                => 'Gluais an duilleag deasbaireachd a tha co-cheangailte ris',
'1movedto2'               => '[[$1]] air a ghluasad a [[$2]]',
'1movedto2_redir'         => 'a ghluais [[$1]] a [[$2]] thairis air ath-sheòladh',
'movelogpage'             => 'Loga nan gluasadan',
'movereason'              => 'Adhbhar:',
'revertmove'              => 'till',
'delete_and_move'         => 'Sguab às agus gluais',
'delete_and_move_confirm' => 'Siuthad, sguab às an duilleag',

# Export
'export' => 'Às-phortaich duilleagan',

# Namespace 8 related
'allmessages'     => 'Teachdaireachdan an t-siostaim',
'allmessagesname' => 'Ainm',
'allmessagestext' => 'Seo liosta de theachdaireachdan an t-siostaim a tha ri làimh ann an namespace MediaWiki.
Tadhail air [http://www.mediawiki.org/wiki/Localisation Ionadaileadh MediaWiki] is [http://translatewiki.net translatewiki.net] ma tha thu airson pàirt a ghabhail ann an ionadaileadh MediaWiki.',

# Thumbnails
'thumbnail-more' => 'Meudaich',
'filemissing'    => 'Faidhle a dhìth',

# Special:Import
'importnotext' => 'Falamh no gun teacsa',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'An duilleag phearsanta agad',
'tooltip-pt-mytalk'               => 'Duilleag do chonaltraidh',
'tooltip-pt-preferences'          => 'Do roghainnean',
'tooltip-pt-watchlist'            => "Seo liosta nan duilleagan a tha thu a' cumail sùil orra a thaobh mhùthaidhean a nithear orra",
'tooltip-pt-mycontris'            => 'Liosta do mhùthaidhean',
'tooltip-pt-login'                => 'Mholamaidh dhut logadh a-steach; ge-tà, cha leig thu leas seo a dhèanamh',
'tooltip-pt-logout'               => 'Log a-mach',
'tooltip-ca-talk'                 => 'Deasbad mu dhuilleag na susbainte',
'tooltip-ca-edit'                 => "'S urrainn dhut an duilleag seo a dheasachadh. Saoil an cleachd thu an ro-shealladh mus sàbhail thu?",
'tooltip-ca-addsection'           => 'Tòisich air earrann ùr',
'tooltip-ca-viewsource'           => "Tha an duilleag seo fo dhìon.
'S urrainn dhut a tùs fhaicinn",
'tooltip-ca-history'              => 'Seann mhùthaidhean na duilleige seo',
'tooltip-ca-protect'              => 'Dìon an duilleag seo',
'tooltip-ca-delete'               => 'Sguab às an duilleag seo',
'tooltip-ca-move'                 => 'Gluais an duilleag seo',
'tooltip-ca-watch'                => 'Cuir an duilleag seo air mo chlàr-faire',
'tooltip-ca-unwatch'              => 'Thoir an duilleag seo far mo chlàir-fhaire',
'tooltip-search'                  => 'Rannsaich {{SITENAME}}',
'tooltip-search-go'               => 'Rach gu duilleag air a bheil an dearbh ainm seo, ma tha tè ann',
'tooltip-search-fulltext'         => 'Lorg an teacs seo sna duilleagan',
'tooltip-n-mainpage'              => "Tadhail air a' phrìomh dhuilleag",
'tooltip-n-mainpage-description'  => 'Tadhail air an duilleag mhòr',
'tooltip-n-portal'                => 'Mun phròiseact, nas urrainn dhut dèanamh is far an lorg thu nithean',
'tooltip-n-currentevents'         => 'Lorg fiosrachadh a bharrachd mu thachartasan an latha',
'tooltip-n-recentchanges'         => 'Liosta nam mùthaidhean ùra aig an uici.',
'tooltip-n-randompage'            => 'Luchdaich duilleag air thuaiream',
'tooltip-n-help'                  => 'Far am faigh thu fiosrachadh',
'tooltip-t-whatlinkshere'         => "Liosta de gach duilleag uici a tha a' ceangal ris an duilleag seo",
'tooltip-t-recentchangeslinked'   => 'Mùthaidhean a rinneadh o chionn ghoirid air duilleagan a tha ceangal ann thuca on duilleag seo',
'tooltip-feed-rss'                => 'Inbhir RSS airson na duilleige seo',
'tooltip-feed-atom'               => 'Inbhir Atom airson na duilleige seo',
'tooltip-t-contributions'         => "Seall liosta nam mùthaidhean a rinn a' chleachdaiche seo",
'tooltip-t-emailuser'             => 'Cuir post-dealain dhan chleachdaiche seo',
'tooltip-t-upload'                => 'Luchdaich a-nuas faidhlichean',
'tooltip-t-specialpages'          => 'Liosta de gach duilleag shònraichte',
'tooltip-t-print'                 => 'Tionndadh dhen duilleag a ghabhas a chlò-bhualadh',
'tooltip-t-permalink'             => 'Dèan ceangal buan gu mùthadh seo na duilleige',
'tooltip-ca-nstab-main'           => 'Seall duilleag na susbainte',
'tooltip-ca-nstab-user'           => "Seall duilleag a' chleachdaiche",
'tooltip-ca-nstab-special'        => 'Seo duilleag shònraichte, chan urrainn dhut an duilleag fhèin a dheasachadh',
'tooltip-ca-nstab-project'        => "Seall duilleag a' phròiseict",
'tooltip-ca-nstab-image'          => 'Seall duilleag an fhaidhle',
'tooltip-ca-nstab-template'       => 'Seall an teamplaid',
'tooltip-ca-nstab-category'       => 'Seall duilleag na roinne',
'tooltip-minoredit'               => 'Comharraich seo mar dheasachadh beag',
'tooltip-save'                    => 'Sàbhail na mùthaidhean agad',
'tooltip-preview'                 => 'Ro-sheall na mùthaidhean agad; saoil an cleachd thu seo mus sàbhail thu iad?',
'tooltip-diff'                    => 'Seall na mùthaidhean a chuir mi air an teacs',
'tooltip-compareselectedversions' => 'Seall an diofar eadar an dà mhùthadh dhen duilleag seo a thagh thu',
'tooltip-watch'                   => 'Cuir an duilleag seo air do chlàr-faire',
'tooltip-rollback'                => 'Ma chleachdas tu "Roilig air ais", tillidh thu gach mùthadh a rinn deasaiche àraid le aon bhriogadh',
'tooltip-undo'                    => 'Tillidh "Neo-dhèan" am mùthadh seo \'s fosglaidh e am foirm mùthaidh ann am modh an ro-sheallaidh. \'S urrainn dhut adhbhar a chur an cèill sa ghearr-chunntas air an dòigh seo.',

# Attribution
'anonymous'     => '{{PLURAL:$1|Cleachdaiche|Cleachdaichean|Cleachdaichean|Cleachdaichean|Cleachdaichean|Cleachdaichean}} gun ainm o {{SITENAME}}',
'siteuser'      => 'cleachdaiche {{SITENAME}} $1',
'othercontribs' => 'Stèidhichte air obair le $1.',
'others'        => 'eile',
'siteusers'     => '{{PLURAL:$2|chleachdaiche|chleachdaiche|chleachdaiche|chleachdaiche|cleachdaichean|cleachdaiche}} {{SITENAME}} $1',

# Info page
'infosubtitle' => 'Fiosrachadh mun duilleag',
'numwatchers'  => "Àireamh de dhaoine a tha a' cumail sùil air: $1",

# Math errors
'math_unknown_error' => 'mearachd neo-aithnichte',

# Browsing diffs
'previousdiff' => '← Mùthadh nas sine',
'nextdiff'     => 'Deasachadh nas ùire →',

# Media information
'file-info-size'       => '$1 × $2 pixel, meud an fhaidhle: $3, seòrsa MIME: $4',
'file-nohires'         => '<small>Chan eil dùmhlachd-bhreacaidh nas fhearr ri fhaighinn.</small>',
'svg-long-desc'        => 'Faidhle SVG, a-rèir ainm $1 × $2 pixel, meud faidhle: $3',
'show-big-image'       => 'Dùmhlachd-bhreacaidh shlàn',
'show-big-image-thumb' => '<small>Meud an ro-sheallaidh seo: $1 × $2 pixel</small>',

# Special:NewFiles
'ilsubmit' => 'Rannsaich',
'bydate'   => 'air ceann-latha',

# Bad image list
'bad_image_list' => "Seo mar a tha am fòrmat:

Cha bheachdaichear ach air buill liosta (loidhniche a tha * air am beulaibh).
Feumaidh a' chiad cheangal air loidhne a bhith 'na cheangal ri droch fhaidhle.
Thathar a' coimhead air ceangal sam bith eile san loidhne sin mar eisgeachdan, 's e sin duilleagan far am faod am faidhle a bhith sa loidhne.",

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => "Tha fiosrachadh a bharrachd san fhaidhle seo, 's mathaid o chamara digiteach no sganair a chaidh a chleachdadh gus a chruthachadh no a dhigiteachadh.
Ma chaidh am faidhle tùsail atharrachadh, faodaidh nach eil cuid dhen fhiosrachadh ceart a thaobh an fhaidhle atharraichte tuilleadh.",
'metadata-expand'   => 'Seall am fiosrachadh a bharrachd',
'metadata-collapse' => 'Cuir am fiosrachadh a bharrachd am falach',
'metadata-fields'   => "Thèid raointean EXIF metadata a tha ainmichte san teachdaireachd seo a ghabhail a-steach air duilleag an deilbh nuair a bhios clàr a' mhetadata air a dhùmhlachadh.
Bidh an fheadhainn eile falaichte a ghnàth.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength",

# External editor support
'edit-externally'      => 'Deasaich am faidhle le prògram on taobh a-muigh',
'edit-externally-help' => '(Seall air [http://www.mediawiki.org/wiki/Manual:External_editors mìneachadh an t-suidheachaidh] airson barrachd fiosrachaidh)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'a h-uile',
'namespacesall' => 'uile',
'monthsall'     => 'uile',

# Watchlist editor
'watchlistedit-normal-title' => 'Deasaich an clàr-faire',
'watchlistedit-raw-submit'   => 'Ùraich an clàr-faire',

# Watchlist editing tools
'watchlisttools-view' => 'Seall na mùthaidhean iomchaidh',
'watchlisttools-edit' => 'Seall is deasaich mo chlàr-faire',
'watchlisttools-raw'  => "Deasaich còd a' chlàir-fhaire",

# Special:Version
'version' => 'Tionndadh',

# Special:SpecialPages
'specialpages' => 'Duilleagan sònraichte',

);

<?php
/** Volapük (Volapük)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Malafaya
 * @author Reedy
 * @author Smeira
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Nünamakanäd',
	NS_SPECIAL          => 'Patikos',
	NS_TALK             => 'Bespik',
	NS_USER             => 'Geban',
	NS_USER_TALK        => 'Gebanibespik',
	NS_PROJECT_TALK     => 'Bespik_dö_$1',
	NS_FILE             => 'Ragiv',
	NS_FILE_TALK        => 'Ragivibespik',
	NS_MEDIAWIKI        => 'Sitanuns',
	NS_MEDIAWIKI_TALK   => 'Bespik_dö_sitanuns',
	NS_TEMPLATE         => 'Samafomot',
	NS_TEMPLATE_TALK    => 'Samafomotibespik',
	NS_HELP             => 'Yuf',
	NS_HELP_TALK        => 'Yufibespik',
	NS_CATEGORY         => 'Klad',
	NS_CATEGORY_TALK    => 'Kladibespik',
);

$namespaceAliases = array(
	'Magod' => NS_FILE,
	'Magodibespik' => NS_FILE_TALK,
);

$datePreferences = array(
	'default',
	'vo',
	'vo plain',
	'ISO 8601',
);

$defaultDateFormat = 'vo';

$dateFormats = array(
	'vo time' => 'H:i',
	'vo date' => 'Y F j"id"',
	'vo both' => 'H:i, Y F j"id"',

	'vo plain time' => 'H:i',
	'vo plain date' => 'Y F j',
	'vo plain both' => 'H:i, Y F j',
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Lüodükömstelik', 'Lüodüköms telik' ),
	'BrokenRedirects'           => array( 'Lüodükömsdädik', 'Lüodüköms dädik' ),
	'Disambiguations'           => array( 'Telplänovs', 'Telplänovapads' ),
	'Userlogin'                 => array( 'Gebananunäd' ),
	'Userlogout'                => array( 'Gebanasenunäd' ),
	'Preferences'               => array( 'Buükams' ),
	'Watchlist'                 => array( 'Galädalised' ),
	'Recentchanges'             => array( 'Votükamsnulik' ),
	'Upload'                    => array( 'Löpükön' ),
	'Listfiles'                 => array( 'Ragivalised', 'Magodalised' ),
	'Newimages'                 => array( 'Ragivsnulik', 'Magodsnulik', 'Magods nulik' ),
	'Listusers'                 => array( 'Gebanalised' ),
	'Statistics'                => array( 'Statits' ),
	'Randompage'                => array( 'Padfädik', 'Pad fädik', 'Fädik' ),
	'Lonelypages'               => array( 'Padssoelöl', 'Pads soelöl' ),
	'Uncategorizedpages'        => array( 'Padsnenklads', 'Pads nen klads' ),
	'Uncategorizedcategories'   => array( 'Kladsnenklads', 'Klads nen klads' ),
	'Uncategorizedimages'       => array( 'Ragivsnenklads', 'Magodsnenklads', 'Magods nen klads' ),
	'Uncategorizedtemplates'    => array( 'Samafomotsnenklads', 'Samafomots nen klads' ),
	'Unusedcategories'          => array( 'Kladsnopageböls', 'Klad no pageböls' ),
	'Unusedimages'              => array( 'Ragivsnopageböls', 'Magodsnopageböls', 'Magods no pageböls' ),
	'Wantedpages'               => array( 'Pads mekabik', 'Padsmekabik', 'Padspavilöl', 'Yümsdädik', 'Pads pavilöl', 'Yüms dädik' ),
	'Wantedcategories'          => array( 'Klads mekabik', 'Kladsmekabik', 'Kladspavilöl', 'Klads pavilöl' ),
	'Wantedfiles'               => array( 'Ragivsmekabik' ),
	'Wantedtemplates'           => array( 'Samafomotsmekabik' ),
	'Mostlinked'                => array( 'Suvüno peyümöls' ),
	'Mostlinkedcategories'      => array( 'Klads suvüno peyümöls' ),
	'Mostlinkedtemplates'       => array( 'Samafomots suvüno peyümöls' ),
	'Mostimages'                => array( 'Ragivs suvüno peyümöls' ),
	'Shortpages'                => array( 'Padsbrefik' ),
	'Longpages'                 => array( 'Padslunik' ),
	'Newpages'                  => array( 'Padsnulik' ),
	'Ancientpages'              => array( 'Padsbäldik' ),
	'Protectedpages'            => array( 'Padspejelöl' ),
	'Protectedtitles'           => array( 'Tiädspejelöl' ),
	'Allpages'                  => array( 'Padsvalik' ),
	'Specialpages'              => array( 'Padspatik' ),
	'Contributions'             => array( 'Keblünots' ),
	'Confirmemail'              => array( 'Fümedönladeti' ),
	'Whatlinkshere'             => array( 'Yümsisio', 'Isio' ),
	'Movepage'                  => array( 'Topätükön' ),
	'Categories'                => array( 'Klads' ),
	'Version'                   => array( 'Fomam' ),
	'Allmessages'               => array( 'Nünsvalik' ),
	'Log'                       => array( 'Jenotalised', 'Jenotaliseds' ),
	'Mypage'                    => array( 'Padobik' ),
	'Mytalk'                    => array( 'Bespikobik' ),
	'Mycontributions'           => array( 'Keblünotsobik' ),
	'Search'                    => array( 'Suk' ),
	'Blankpage'                 => array( 'PadVagik' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Dislienükön yümis:',
'tog-highlightbroken'         => 'Jonön yümis dädik <a href="" class="new">ön mod at</a> (voto: ön mod at<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Lonedükön bagafis',
'tog-hideminor'               => 'Klänedön redakamis pülik su lised votükamas nulik',
'tog-hidepatrolled'           => 'Klänedön redakamis pezepöl in lised votükamas nulik.',
'tog-newpageshidepatrolled'   => 'Klänedön padis pezepöl in lised padas nulik',
'tog-extendwatchlist'         => 'Stäänükön galädalisedi ad jonön votükamis tefik valik, e no te nulikünos',
'tog-usenewrc'                => 'Gebön votükamis nulik patik (me JavaScript)',
'tog-numberheadings'          => 'Givön itjäfidiko nümis dilädatiädes',
'tog-showtoolbar'             => 'Jonön redakamastumemi (JavaScript)',
'tog-editondblclick'          => 'Dälön redakön padis pö drän telik mugaknopa (JavaScript)',
'tog-editsection'             => 'Dälön redakami dilädas me yüms: [redakön]',
'tog-editsectiononrightclick' => 'Dälön redakami diläda me klik mugaknopa detik su dilädatiäds (JavaScript)',
'tog-showtoc'                 => 'Jonön ninädalisedi (su pads labü diläds plu 3)',
'tog-rememberpassword'        => 'Dakipolös nunädamanünis obik in bevüresodatävöm at (muiko {{PLURAL:$1|del|dels}} $1)',
'tog-watchcreations'          => 'Läükön padis fa ob pejafölis lä galädalised obik',
'tog-watchdefault'            => 'Läükön padis fa ob peredakölis la galädalised obik',
'tog-watchmoves'              => 'Läükön padis fa ob petopätükölis lä galädalised obik',
'tog-watchdeletion'           => 'Läükön padis fa ob pemoükölis lä galädalised obik',
'tog-minordefault'            => 'Bepenön redakamis no pebepenölis valikis asä pülikis',
'tog-previewontop'            => 'Jonön büologedi bü redakaspad',
'tog-previewonfirst'          => 'Jonön büologedi pö redakam balid',
'tog-nocache'                 => 'Nejäfidükön el "cache" padas in bevüresodatävöm',
'tog-enotifwatchlistpages'    => 'Sedön obe penedi leäktronik ven ek votükon padi se galädalised obik',
'tog-enotifusertalkpages'     => 'Sedön obe penedi leäktronik ven gebanapad obik pavotükon',
'tog-enotifminoredits'        => 'Sedön obe penedi leäktronik igo pö padavotükams pülik',
'tog-enotifrevealaddr'        => 'Jonön ladeti leäktronik oba in nunapeneds.',
'tog-shownumberswatching'     => 'Jonön numi gebanas galädöl',
'tog-oldsig'                  => 'Büologed dispenäda dabinöl:',
'tog-fancysig'                => 'Dispenäd balugik (nen yüms lü gebanapad)',
'tog-externaleditor'          => 'Gebön nomiko redakömi plödik (te pro jäfüdisevans; paramets patik paneodons su nünöm olik)',
'tog-externaldiff'            => 'Gebön nomiko difi plödik (te pro jäfüdisevans; paramets patik paneodons su nünöm olik)',
'tog-showjumplinks'           => 'Dälön lügolovi me yüms „lübunöl“',
'tog-uselivepreview'          => 'Gebön büologedi itjäfidik (JavaScript) (Sperimäntik)',
'tog-forceeditsummary'        => 'Sagön obe, ven redakaplän brefik vagon',
'tog-watchlisthideown'        => 'Klänedön redakamis obik se galädalised',
'tog-watchlisthidebots'       => 'Klänedön redakamis mäikamenas se galädalised',
'tog-watchlisthideminor'      => 'Klänedön redakamis pülik se galädalised',
'tog-watchlisthideliu'        => 'Klänedön redakamis gebanas senunädöl se galädalised',
'tog-watchlisthideanons'      => 'Klänedön redakamis gebanas nennemik se galädalised',
'tog-watchlisthidepatrolled'  => 'Klänedön redakamis pezepöl in galädalised',
'tog-ccmeonemails'            => 'Sedön obe kopiedis penedas, kelis sedob gebanes votik',
'tog-diffonly'                => 'No jonön padaninädi dis difs',
'tog-showhiddencats'          => 'Jonön kladis peklänedöl',
'tog-norollbackdiff'          => 'Moädön difi pos sädunam',

'underline-always'  => 'Pö jenets valik',
'underline-never'   => 'Neföro',
'underline-default' => 'Ma bevüresodatävöm',

# Font style option in Special:Preferences
'editfont-default' => 'Ma bevüresodatävöm',

# Dates
'sunday'        => 'sudel',
'monday'        => 'mudel',
'tuesday'       => 'tudel',
'wednesday'     => 'vedel',
'thursday'      => 'dödel',
'friday'        => 'fridel',
'saturday'      => 'zädel',
'sun'           => 'sud',
'mon'           => 'mud',
'tue'           => 'tud',
'wed'           => 'ved',
'thu'           => 'död',
'fri'           => 'fri',
'sat'           => 'zäd',
'january'       => 'yanul',
'february'      => 'febul',
'march'         => 'mäzul',
'april'         => 'prilul',
'may_long'      => 'mayul',
'june'          => 'yunul',
'july'          => 'yulul',
'august'        => 'gustul',
'september'     => 'setul',
'october'       => 'tobul',
'november'      => 'novul',
'december'      => 'dekul',
'january-gen'   => 'yanul',
'february-gen'  => 'febul',
'march-gen'     => 'mäzul',
'april-gen'     => 'prilul',
'may-gen'       => 'mayul',
'june-gen'      => 'yunul',
'july-gen'      => 'yulul',
'august-gen'    => 'gustul',
'september-gen' => 'setul',
'october-gen'   => 'tobul',
'november-gen'  => 'novul',
'december-gen'  => 'dekul',
'jan'           => 'yan',
'feb'           => 'feb',
'mar'           => 'mäz',
'apr'           => 'pri',
'may'           => 'may',
'jun'           => 'yun',
'jul'           => 'yul',
'aug'           => 'gus',
'sep'           => 'set',
'oct'           => 'tob',
'nov'           => 'nov',
'dec'           => 'dek',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Klad|Klads}}',
'category_header'                => 'Pads in klad: „$1“',
'subcategories'                  => 'Donaklads',
'category-media-header'          => 'Ragivs in klad: „$1“',
'category-empty'                 => "''Klad at anu ninädon padis e ragivis nonikis.''",
'hidden-categories'              => '{{PLURAL:$1|Klad|Klads}} peklänedöl',
'hidden-category-category'       => 'Klads peklänedöl',
'category-subcat-count'          => '{{PLURAL:$2|Klad at labon te donakladi sököl.|Klad at labon {{PLURAL:$1|donakladi sököl|donakladis sököl $1}}, se $2.}}',
'category-subcat-count-limited'  => 'Klad at labon {{PLURAL:$1|donakladi|donakladis}} sököl.',
'category-article-count'         => '{{PLURAL:$2|Klad at labon te padi sököl.|{{PLURAL:$1|Pad sököl binon|Pads sököl $1 binons}} in klad at, se $2.}}',
'category-article-count-limited' => '{{PLURAL:$1|Pad sököl binon|Pads sököl $1 binons}} in klad at.',
'category-file-count'            => '{{PLURAL:$2|Klad at labon te ragivi sököl.|{{PLURAL:$1|Ragiv sököl binon |Ragivs sököl $1 binons}} in klad at, se $2.}}',
'category-file-count-limited'    => '{{PLURAL:$1|Ragiv sököl binon|Ragivs sököl $1 binons}} in klad at.',
'listingcontinuesabbrev'         => '(fov.)',

'mainpagetext'      => "'''El MediaWiki pestiton benosekiko.'''",
'mainpagedocfooter' => 'Konsultolös [http://meta.wikimedia.org/wiki/Help:Contents Gebanageidian] ad tuvön nünis dö geb programema vükik.

== Nüdugot ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Parametalised]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki: SSP]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Potalised tefü fomams nulik ela MediaWiki]',

'about'         => 'Tefü',
'article'       => 'Ninädapad',
'newwindow'     => '(maifikon in fenät nulik)',
'cancel'        => 'Stöpädön',
'moredotdotdot' => 'Plu...',
'mypage'        => 'Pad obik',
'mytalk'        => 'Bespiks obik',
'anontalk'      => 'Bespiks ela IP at',
'navigation'    => 'Nafam',
'and'           => '&#32;e',

# Cologne Blue skin
'qbfind'         => 'Tuvön',
'qbbrowse'       => 'Padön',
'qbedit'         => 'Redakön',
'qbpageoptions'  => 'Pad at',
'qbpageinfo'     => 'Yumed',
'qbmyoptions'    => 'Pads obik',
'qbspecialpages' => 'Pads patik',
'faq'            => 'Säks suvo pasäköls',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection' => 'Lüükön yegädi',
'vector-action-delete'     => 'Moükön',
'vector-action-move'       => 'Topätükön',
'vector-action-protect'    => 'Jelön',
'vector-action-undelete'   => 'Sämoükön',
'vector-action-unprotect'  => 'Säjelön',
'vector-view-create'       => 'Jafön',
'vector-view-edit'         => 'Redakön',
'vector-view-history'      => 'Logön jenotemi',
'vector-view-view'         => 'Reidön',
'vector-view-viewsource'   => 'Logön fonäti',
'actions'                  => 'Duns',
'namespaces'               => 'Nemaspads',

'errorpagetitle'    => 'Pöl',
'returnto'          => 'Geikön lü $1.',
'tagline'           => 'Se {{SITENAME}}',
'help'              => 'Yuf',
'search'            => 'Suk',
'searchbutton'      => 'Sukolöd',
'go'                => 'Gololöd',
'searcharticle'     => 'Maifükön padi',
'history'           => 'Padajenotem',
'history_short'     => 'Jenotem',
'updatedmarker'     => 'pävotükon pos visit lätik oba',
'info_short'        => 'Nün',
'printableversion'  => 'Fom dabükovik',
'permalink'         => 'Yüm laidüpik',
'print'             => 'Bükön',
'view'              => 'Logön',
'edit'              => 'Redakön',
'create'            => 'Jafön',
'editthispage'      => 'Redakolöd padi at',
'create-this-page'  => 'Jafön padi at',
'delete'            => 'Moükön',
'deletethispage'    => 'Moükolös padi at',
'undelete_short'    => 'Sädunön moükami {{PLURAL:$1|redakama bal|redakamas $1}}',
'viewdeleted_short' => 'Logön {{PLURAL:$1|redakami pemoüköl bal|redakamis pemoüköls $1}}',
'protect'           => 'Jelön',
'protect_change'    => 'votükön',
'protectthispage'   => 'Jelön padi at',
'unprotect'         => 'säjelön',
'unprotectthispage' => 'Säjelolöd padi at',
'newpage'           => 'Pad nulik',
'talkpage'          => 'Bespikolöd padi at',
'talkpagelinktext'  => 'Bespik',
'specialpage'       => 'Pad patik',
'personaltools'     => 'Stums pösodik',
'postcomment'       => 'Diläd nulik',
'articlepage'       => 'Jonön ninädapadi',
'talk'              => 'Bespik',
'views'             => 'Logams',
'toolbox'           => 'Stumem',
'userpage'          => 'Logön gebanapadi',
'projectpage'       => 'Logön proyegapadi',
'imagepage'         => 'Jonön ragivapad',
'mediawikipage'     => 'Logön nunapadi',
'templatepage'      => 'Logön samafomotapadi',
'viewhelppage'      => 'Jonön yufapadi',
'categorypage'      => 'Jonolöd kladapadi',
'viewtalkpage'      => 'Logön bespikami',
'otherlanguages'    => 'In püks votik',
'redirectedfrom'    => '(Pelüodükon de pad: $1)',
'redirectpagesub'   => 'Lüodükömapad',
'lastmodifiedat'    => 'Pad at pävotükon lätiküno tü düp $2, ün $1.',
'viewcount'         => 'Pad at pelogon {{PLURAL:$1|balna|$1na}}.',
'protectedpage'     => 'Pad pejelöl',
'jumpto'            => 'Bunön lü:',
'jumptonavigation'  => 'nafam',
'jumptosearch'      => 'suk',
'pool-errorunknown' => 'Pöl nesevädik',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Tefü {{SITENAME}}',
'aboutpage'            => 'Project:Tefü',
'copyright'            => 'Ninäd gebidon ma el $1.',
'copyrightpage'        => '{{ns:project}}:Kopiedagitäts',
'currentevents'        => 'Jenots nuik',
'currentevents-url'    => 'Project:Jenots nuik',
'disclaimers'          => 'Nuneds',
'disclaimerpage'       => 'Project:Gididimiedükam valemik',
'edithelp'             => 'Redakamayuf',
'edithelppage'         => 'Help:Redakam',
'helppage'             => 'Help:Ninäd',
'mainpage'             => 'Cifapad',
'mainpage-description' => 'Cifapad',
'policy-url'           => 'Project:Dunamod',
'portal'               => 'Komotanefaleyan',
'portal-url'           => 'Project:Komotanefaleyan',
'privacy'              => 'Dunamod demü soelöf',
'privacypage'          => 'Project:Dunamod_demü_soelöf',

'badaccess'        => 'Dälapöl',
'badaccess-group0' => 'No pedälol ad ledunön atosi, kelosi ebegol.',
'badaccess-groups' => 'Utos, kelosi vilol dunön, padälon te gebanes dutöl lü {{PLURAL:$2|grup|bal grupas}}: $1.',

'versionrequired'     => 'Fomam: $1 ela MediaWiki paflagon',
'versionrequiredtext' => 'Fomam: $1 ela MediaWiki zesüdon ad gebön padi at. Logolös [[Special:Version|fomamapadi]].',

'ok'                      => 'Si!',
'retrievedfrom'           => 'Pekopiedon se „$1“',
'youhavenewmessages'      => 'Su pad ola binons $1 ($2).',
'newmessageslink'         => 'nuns nulik',
'newmessagesdifflink'     => 'votükam lätik',
'youhavenewmessagesmulti' => 'Labol nunis nulik su $1',
'editsection'             => 'redakön',
'editold'                 => 'redakön',
'viewsourceold'           => 'logön fonätavödemi',
'editlink'                => 'redakön',
'viewsourcelink'          => 'logedön fonäti',
'editsectionhint'         => 'Redakolöd dilädi: $1',
'toc'                     => 'Ninäd',
'showtoc'                 => 'jonön',
'hidetoc'                 => 'klänedön',
'thisisdeleted'           => 'Jonön u sädunön moükami $1?',
'viewdeleted'             => 'Logön eli $1?',
'restorelink'             => '{{PLURAL:$1|redakama bal|redakamas $1}}',
'feedlinks'               => 'Kanad:',
'feed-invalid'            => 'Kanadabonedam no lonöfon.',
'feed-unavailable'        => 'Nünamakanads no gebidons',
'site-rss-feed'           => 'Kanad (RSS): $1',
'site-atom-feed'          => 'Kanad (Atom): $1',
'page-rss-feed'           => 'Kanad (RSS): „$1“',
'page-atom-feed'          => 'Kanad (Atom) „$1“',
'red-link-title'          => '$1 (pad no dabinon)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Yeged',
'nstab-user'      => 'Gebanapad',
'nstab-media'     => 'Nünamakanädapad',
'nstab-special'   => 'Pad patik',
'nstab-project'   => 'Proyegapad',
'nstab-image'     => 'Ragiv',
'nstab-mediawiki' => 'Vödem',
'nstab-template'  => 'Samafomot',
'nstab-help'      => 'Yufapad',
'nstab-category'  => 'Klad',

# Main script and global functions
'nosuchaction'      => 'Atos no mögon',
'nosuchactiontext'  => 'Dun peflagöl fa el URL no sevädon vüke.
Ba epenol eli URL neverätiko, u ba esukol yümi dobik.
Mögos i, das atos sinifon, das dabinon säkädil pö program fa {{SITENAME}} pageböl.',
'nosuchspecialpage' => 'Pad patik at no dabinon',
'nospecialpagetext' => 'Esukol padi patik no dabinöli. Lised padas patik dabinöl binon su pad: [[Special:SpecialPages]].',

# General errors
'error'                => 'Pöl',
'databaseerror'        => 'Pöl in nünodem',
'dberrortext'          => 'Süntagapök pö geb vüka at ejenon.
Atos ba sinifön, das dabinon säkäd pö program.
Steifül lätik ad gebön vüki äbinon:
<blockquote><tt>$1</tt></blockquote>
se dunod: „<tt>$2</tt>“.
Nünodem ägesedon pökanuni: „<tt>$3: $4</tt>“.',
'dberrortextcl'        => 'Süntagapök pö geb vüka at ejenon.
Steifül lätik ad gebön vüki at äbinon:
„$1“
se dunod: „$2“.
Nünodem ägesedon pökanuni: „$3: $4“',
'laggedslavemode'      => 'Nuned: pad ba labon votükamis brefabüik',
'readonly'             => 'Vük pefärmükon',
'enterlockreason'      => 'Penolös kodi löka, keninükamü täxet dula onik e dela, kü pomoükon',
'readonlytext'         => 'Vük pefärmükon: yegeds e votükams nuliks no kanons padakipön. Atos ejenon bo pro kosididaduns, pos kels vük ogeikon ad stad kösömik.

Guvan, kel äfärmükon vüki, äplänon osi ön mod sököl: $1',
'missing-article'      => 'Nünodem no etuvon vödemi pada, keli sötonov dabinön, tiädü „$1“ $2.

Atos kösömiko jenon sekü difa- u jenotemayüm dädik (o.b. lü pad pemoüköl).

Üf yüm no binon dädik, ba etuvol pöli pö program vüka at. Nunodolös oni, begö! [[Special:ListUsers/sysop|guvane]], mäniotölo eli URL.',
'missingarticle-rev'   => '(fomamanüm: $1)',
'missingarticle-diff'  => '(Dif: $1, $2)',
'readonly_lag'         => 'Vük pefärmükon itjäfidiko du dünanünöms slafik kosädons ko mastanünöm.',
'internalerror'        => 'Pöl ninik',
'internalerror_info'   => 'Pöl ninik: $1',
'fileappenderror'      => 'No emögos ad lüükön ragivi "$1" ad "$2".',
'filecopyerror'        => 'No emögos ad kopiedön ragivi "$1" ad "$2".',
'filerenameerror'      => 'No eplöpos ad votanemön ragivi: "$1" ad: "$2".',
'filedeleteerror'      => 'No emögos ad moükön ragivi "$1".',
'directorycreateerror' => 'No eplöpos ad jafön ragiviäri: "$1".',
'filenotfound'         => 'No eplöpos ad tuvön ragivi: "$1".',
'fileexistserror'      => 'No eplöpos ad dakipön ragivi: "$1": ragiv ya dabinon',
'unexpected'           => 'Völad no pespetöl: „$1“=„$2“.',
'formerror'            => 'PÖL: no emögos ad bevobön fometi at.',
'badarticleerror'      => 'Dun at no kanon paledunön su pad at.',
'cannotdelete'         => 'No emögos ad moükön padi u ragivi: "$1".
Ba ya pemoükon fa geban votik.',
'badtitle'             => 'Tiäd badik',
'badtitletext'         => 'Padatiäd peflagöl äbinon nelonöfik, vägik, u ba yüm bevüpükik u bevüvükik dädik. Mögos, das ninädon malati(s), kel(s) no dalon(s) pagebön ad jafön tiädis.',
'perfcached'           => 'Nüns sököl ekömons se el caché e ba no binons anuik.',
'perfcachedts'         => 'Nüns sököl kömons se mem nelaidüpik e päbevobons lätiküno ün: $1.',
'querypage-no-updates' => 'Atimükam pada at penemögükon. Nünods isik no poflifedükons suno.',
'wrong_wfQuery_params' => 'Paramets neverätik lü wfQuery()<br />
Dun: $1<br />
Beg: $2',
'viewsource'           => 'Logön fonäti',
'viewsourcefor'        => 'tefü $1',
'actionthrottled'      => 'Dun pemiedükon',
'actionthrottledtext'  => 'Ad tadunön reklamami itjäfidik (el „spam“), dunot at no padälon tu suvo dü brefüp. Ya erivol miedi gretikün. Steifülolös nogna pos minuts anik.',
'protectedpagetext'    => 'Pad at pejelon ad neletön redakami.',
'viewsourcetext'       => 'Kanol logön e kopiedön fonätakoti pada at:',
'protectedinterface'   => 'Pad at jafon vödemis sitanünas, ed anu pelökofärmükon ad vitön migebis.',
'editinginterface'     => "'''Nuned:''' Anu redakol padi, kel labükon vödemis bevüik pro programem.
Votükams pada at oflunons logoti gebanasita pro gebans votik.
Ad tradutön vödemis, demolös gebi ela [http://translatewiki.net/wiki/Main_Page?setlang=vo translatewiki.net]: topükamaproyeg ela MediaWiki.",
'sqlhidden'            => '(SQL beg peklänedon)',
'cascadeprotected'     => 'Pad at pejelon ta redakam, bi pakeninükon fa {{PLURAL:$1|pad|pads}} sököl, kels pejelons ma „jänajel“: $2',
'namespaceprotected'   => "No dalol redakön padis in nemaspad: '''$1'''.",
'customcssjsprotected' => 'No dalol redakön padi at, bi keninükon parametis pösodik gebana votik.',
'ns-specialprotected'  => 'Pads patik no kanons paredakön.',
'titleprotected'       => "Jaf tiäda at penemögükon fa geban: [[User:$1|$1]].
Kod binon: ''$2''.",

# Virus scanner
'virus-badscanner'     => "Parametem badik: program tavirudik nesevädik: ''$1''",
'virus-scanfailed'     => 'skrutam no eplöpon (kot $1)',
'virus-unknownscanner' => 'program tavirudik nesëvadik:',

# Login and logout pages
'logouttext'                 => "'''Esenunädol oli.'''

Kanol laigebön {{SITENAME}} nennemiko, u kanol [[Special:UserLogin|nunädön oli dönu]] me gebananem ot u gebenanem votik.
Küpälolös, das pads anik ba nog pojenons äsva no esenunädol oli, jüs uklinükol memi no laidüpik bevüresodanaföma olik.",
'welcomecreation'            => '== Benokömö, o $1! ==
Kal olik pejafon.
No glömolöd ad votükön [[Special:Preferences|buükamis olik in {{SITENAME}}]].',
'yourname'                   => 'Gebananem:',
'yourpassword'               => 'Letavöd:',
'yourpasswordagain'          => 'Klavolös dönu letavödi:',
'remembermypassword'         => 'Dakipolöd ninädamanünis obik in nünöm at (muiko {{PLURAL:$1|del|dels}} $1)',
'yourdomainname'             => 'Domen olik:',
'externaldberror'            => 'U ejenon fümükamapöl plödik nünödema, u no dalol atimükön kali plödik ola.',
'login'                      => 'Nunädolös obi',
'nav-login-createaccount'    => 'Nunädön oki / jafön kali',
'loginprompt'                => 'Mutol mögükön „kekilis“ ad kanön nunädön oli in {{SITENAME}}.',
'userlogin'                  => 'Nunädön oki / jafön kali',
'userloginnocreate'          => 'Nunädön oki',
'logout'                     => 'Senunädön oki',
'userlogout'                 => 'Senunädön oki',
'notloggedin'                => 'No enunädol oli',
'nologin'                    => "No labol-li kali? '''$1'''.",
'nologinlink'                => 'Jafolös bali',
'createaccount'              => 'Jafön kali',
'gotaccount'                 => "Ya labol-li kali? '''$1'''.",
'gotaccountlink'             => 'Nunädolös obi',
'createaccountmail'          => 'me pot leäktronik',
'createaccountreason'        => 'Kod:',
'badretype'                  => 'Letavöds fa ol pepenöls no leigons.',
'userexists'                 => 'Gebananem at ya dabinon. Välolös, begö! nemik votik.',
'loginerror'                 => 'Nunädamapöl',
'createaccounterror'         => 'Kal no pejafon: $1',
'nocookiesnew'               => 'Gebanakal pejafon, ab no enunädol oli. {{SITENAME}} gebon „kekilis“ pö nunädam gebanas. Pö bevüresodanaföm olik ye geb kekilas penemogükon. Mogükolös oni e nunädolös oli me gebananem e letavöd nuliks ola.',
'nocookieslogin'             => '{{SITENAME}} gebon „kekilis“ ad nunädön gebanis. Anu geb kekilas nemögon. Mögükolös onis e steifülolös nogna.',
'noname'                     => 'No egivol gebananemi lonöföl.',
'loginsuccesstitle'          => 'Enunädol oli benosekiko',
'loginsuccess'               => "'''Binol anu in {{SITENAME}} as \"\$1\".'''",
'nosuchuser'                 => 'No dabinon geban labü nem: "$1".
Gebananems distidons mayudis i minudis.
Koräkolös tonatami nema at, u [[Special:UserLogin/signup|jafolös kali nulik]].',
'nosuchusershort'            => 'No dabinon geban labü nem: "<nowiki>$1</nowiki>". Koräkolös tonatami nema at.',
'nouserspecified'            => 'Mutol välön gebananemi.',
'wrongpassword'              => 'Letavöd neveräton. Steifülolös dönu.',
'wrongpasswordempty'         => 'Letavöd vagon. Steifülolös dönu.',
'passwordtooshort'           => 'Letavöds mutons binädon me {{PLURAL:$1|malat|malats}} pu $1.',
'mailmypassword'             => 'Sedön letavödi nulik',
'passwordremindertitle'      => 'Letavöd nulik nelaidik in {{SITENAME}}',
'passwordremindertext'       => 'Ek (luveratiko ol, se ladet-IP: $1) ebegon sedi letavöda nulik pro {{SITENAME}} ($4). Letavöd nelaidüpik pejafon pro geban: „$2“ me ninäd: „$3“. If atos ejenon ma vil olik, mutol anu nunädön oli e välön letavödi nulik. Letavöd nelaidüpik ola odulon dü {{PLURAL:$5|del bal|dels $5}}.

If pösod votik edunon begi at, ud if anu memol letavödi olik e no plu vilol votükön oni, dalol nedemön penedi at e laigebön letavödi rigik ola.',
'noemail'                    => 'Ladet leäktronik nonik peregistaron pro geban "$1".',
'passwordsent'               => 'Letavöd nulik pesedon ladete leäktronik fa "$1" peregistaröle.<br />
Nunädolös oli dönu posä ogetol oni.',
'blocked-mailpassword'       => 'Redakam me ladet-IP olik peblokon; sekü atos, ad neletön migebi, no dalol gebön oni ad gegetön letavödi olik.',
'eauthentsent'               => 'Pened leäktronik pesedon ladete pegivöl ad fümükön dabini onik.
Büä pened votik alseimik okanon pasedön kale at, omutol dunön valikosi in pened at peflagöli, ad fümükön, das kal binon jenöfo olik.',
'throttled-mailpassword'     => 'Mebapened tefü letavöd olik ya pesedon, dü {{PLURAL:$1|düp lätik|düps lätik $1}}.
Ad neletön migebi, mebapened te bal a {{PLURAL:$1|düp|düps $1}} dalon pasedön.',
'mailerror'                  => 'Pöl dü sedam pota: $1',
'acct_creation_throttle_hit' => 'Visitans vüka at, gebölo ladeti-IP olik, ejafons {{PLURAL:$1|kali bal|kalis $1}} dü del lätik, kelos binon num gretikün kalas jafovik dü timaperiod at.
Sekü atos, visitans ladeti-IP at geböls no dalons jafön kalis pluik ün atim.',
'emailauthenticated'         => 'Ladet leäktronik olik päfümükon tü düp $2 ün $3.',
'emailnotauthenticated'      => 'Ladet leäktronik ola no nog pefümedon. Pened nonik posedon me pads sököl.',
'noemailprefs'               => 'Givolös ladeti leäktronik, dat pads at okanons pagebön.',
'emailconfirmlink'           => 'Fümedolös ladeti leäktronik ola',
'invalidemailaddress'        => 'Ladet leäktronik no kanon pazepön bi fomät onik jiniko no lonöfon.
Penolös ladeti labü fomät lonöföl, u vagükolös penamaspadi.',
'accountcreated'             => 'Kal pejafon',
'accountcreatedtext'         => 'Gebanakal pro $1 pejafon.',
'createaccount-title'        => 'Kalijafam in {{SITENAME}}',
'createaccount-text'         => 'Ek ejafon kali pro ladet leäktronik ola in {{SITENAME}} ($4) labü nem: „$2“ e letavöd: „$3“. Kanol nunädön oli e votükön letavödi olik anu.

Kanol nedemön penedi at, üf jafam kala at binon pöl.',
'login-throttled'            => 'Esteifülol tumödikna ad nunädön oli änu.
Stebedolös büä osteifülol nogna.',
'loginlanguagelabel'         => 'Pük: $1',

# JavaScript password checks
'password-strength-bad'        => 'BADIK',
'password-strength-mediocre'   => 'zänedöfik',
'password-strength-acceptable' => 'zepabik',
'password-strength-good'       => 'gudik',
'password-retype'              => 'Klavolös dönu letavödi is',

# Password reset dialog
'resetpass'                 => 'Votükön letavödi',
'resetpass_announce'        => 'Enunädol oli me kot nelaidüpik pisedöl ole. Ad finükön nunädami, mutol välön letavödi nulik is:',
'resetpass_header'          => 'Votükön kalaletavödi',
'oldpassword'               => 'Letavöd büik:',
'newpassword'               => 'Letavöd nulik:',
'retypenew'                 => 'Klavolöd dönu letavödi nulik:',
'resetpass_submit'          => 'Välön letavödi e nunädön omi',
'resetpass_success'         => 'Letavöd olik pevotükon benosekiko! Anu sit nunädon oli...',
'resetpass_forbidden'       => 'Letavöds no kanons pavotükön',
'resetpass-no-info'         => 'Mutol nunädön oli ad logön padi at nemediko.',
'resetpass-submit-loggedin' => 'Votükön letavödi',
'resetpass-submit-cancel'   => 'Stöpädön',
'resetpass-wrong-oldpass'   => 'Letavöd (laidüpik u nelaidüpik) no lonöföl.
Ba ya evotükol benosekiko letavödi olik, u ya ebegol benosekiko letavödi nelaidüpik nulik.',
'resetpass-temp-password'   => 'Letavöd nelaidüpik:',

# Edit page toolbar
'bold_sample'     => 'Vödem bigik',
'bold_tip'        => 'Vödem bigik',
'italic_sample'   => 'Korsiv',
'italic_tip'      => 'Korsiv',
'link_sample'     => 'Yümatiäd',
'link_tip'        => 'Yüm ninik',
'extlink_sample'  => 'http://www.example.com yümatiäd',
'extlink_tip'     => 'Yüm plödik (memolös foyümoti: http://)',
'headline_sample' => 'Tiädavödem',
'headline_tip'    => 'Tiäd nivoda 2id',
'math_sample'     => 'Pladolös malatami isio',
'math_tip'        => 'Malatam matematik (LaTeX)',
'nowiki_sample'   => 'Pladolös isio vödemi no pefomätöli',
'nowiki_tip'      => 'Nedemön vükifomätami',
'image_tip'       => 'Magod penüpladöl',
'media_tip'       => 'Yüm lü ragiv mediatik',
'sig_tip'         => 'Dispenäd olik kobü dät e tim',
'hr_tip'          => 'Lien horitätik (no gebolös tu suvo)',

# Edit pages
'summary'                          => 'Plän brefik:',
'subject'                          => 'Subyet/tiäd:',
'minoredit'                        => 'Votükam pülik',
'watchthis'                        => 'Galädolöd padi at',
'savearticle'                      => 'Dakipolöd padi',
'preview'                          => 'Büologed',
'showpreview'                      => 'Jonolöd padalogoti',
'showlivepreview'                  => 'Büologed vifik',
'showdiff'                         => 'Jonolöd votükamis',
'anoneditwarning'                  => "'''Nuned:''' No enunädol oli. Ladet-IP olik poregistaron su redakamajenotem pada at.",
'missingsummary'                   => "'''Noet:''' No epenol redakamipläni. If ovälol dönu knopi: Dakipolöd, redakam olik podakipon nen plän.",
'missingcommenttext'               => 'Penolös, begö! küpeti dono.',
'missingcommentheader'             => "'''Meib:''' No epenol yegädi/tiädi küpete at.
If ovälol dönu knopi: \"{{int:savearticle}}\", redakam olik podakipon nen on.",
'summary-preview'                  => 'Büologed brefik:',
'subject-preview'                  => 'Büologed yegäda/diläda:',
'blockedtitle'                     => 'Geban peblokon',
'blockedtext'                      => "'''Gebananem u ladet-IP olik(s) peblokon(s).'''

Blokam at pejenükon fa geban: $1.
Kod binon: ''$2''.

* Prim blokama: $8
* Fin blokama: $6
* Geban pedesinöl: $7

Kanol penön gebane: $1, u [[{{MediaWiki:Grouppage-sysop}}|guvane]] votik, ad bespikön blokami.
Kanol gebön yümi: 'penön gebane at' bisä ladet leäktronik verätik lonöföl patuvon in [[Special:Preferences|buükams kala]] olik e geb onik no peblokon. Ladet-IP nuik ola binon $3 e nüm blokama binon #$5. Mäniotolös nünis löpik valik in peneds ola.",
'autoblockedtext'                  => "Ladet-IP olik peblokon itjäfidiko bi pägebon fa geban, kel peblokon fa geban: $1.
Kod blokama äbinon:

:''$2''

* Prim bloküpa: $8
* Fin bloküpa: $6
* Geban pedesinöl: $7

Dalol penön gebane: $1 u balane [[{{MediaWiki:Grouppage-sysop}}|guvanas votik]] ad bespikön blokami at.

Küpälolös, das no dalol gebön yümi: „penön gebane at“ if no labol ladeti leäktronik lonöföl in [[Special:Preferences|büukams olik]] ed if geb onik fa ol no peblokon.

Ladet-IP olik binon $3, e nüm blokama at binon #$5. Mäniotolös nünis löpik valik in peneds valik ola.",
'blockednoreason'                  => 'kod nonik pegivon',
'blockedoriginalsource'            => "Fonät pada: '''$1''' pajonon dono:",
'blockededitsource'                => "Vödem '''redakamas olik''' pada: '''$1''' pajonon dono:",
'whitelistedittitle'               => 'Mutol nunädön oli ad redakön',
'whitelistedittext'                => 'Mutol $1 ad redakön padis.',
'confirmedittext'                  => 'Mutol fümedön ladeti leäktronik ola büä okanol redakön padis. Pladölos e lonöfükölos ladeti olik in [[Special:Preferences|buükams olik]].',
'nosuchsectiontitle'               => 'Diläd no petuvöl',
'nosuchsectiontext'                => 'Esteifülol ad redakön dilädi no dabinöli.',
'loginreqtitle'                    => 'Nunädam Paflagon',
'loginreqlink'                     => 'ninädolös obi',
'loginreqpagetext'                 => 'Mutol $1 ad logön padis votik.',
'accmailtitle'                     => 'Letavöd pesedon.',
'accmailtext'                      => "Letavöd fädik pro [[User talk:$1|$1]] pasedon lü $2.

Letavöd kala at kanon pavotükön medü pad: ''[[Special:ChangePassword|votükön letavödi]]'' pö nunädam ini vük.",
'newarticle'                       => '(Nulik)',
'newarticletext'                   => "Esökol yümi lü pad, kel no nog dabinon.
Ad jafön padi at, primolös ad klavön vödemi olik in penaspad dono (logolöd [[{{MediaWiki:Helppage}}|yufapadi]] tefü nüns tefik votik).
If binol is pölo, välolös knopi: '''geikön''' bevüresodatävöma olik.",
'anontalkpagetext'                 => "----''Bespikapad at duton lü geban nennemik, kel no nog ejafon kali, u no vilon labön u gebön oni. Sekü atos pemütobs ad gebön ladeti-IP ad dientifükön gebani at. Ladets-IP kanons pagebön fa gebans difik. If binol geban nennemik e cedol, das küpets netefik pelüodükons ole, [[Special:UserLogin|jafolös, begö! kali]], u [[Special:UserLogin|nunädolös oli]] ad vitön kofudi ko gebans nennemik votik.''",
'noarticletext'                    => 'Atimo no dabinon vödem su pad at.
Kanol [[Special:Search/{{PAGENAME}}|sukön padatiädi at]] su pads votik, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} sukön in jenotaliseds tefik] u [{{fullurl:{{FULLPAGENAME}}|action=edit}} redakön padi at]</span>.',
'userpage-userdoesnotexist'        => 'Gebanakal: "$1" no peregistaron. Fümükolös, va vilol jäfön/redakön padi at.',
'userpage-userdoesnotexist-view'   => 'Gebenakal: "$1" no peregistaron.',
'clearyourcache'                   => "'''Prudö!''' Pos dakip buükamas, mögos, das ozesüdos ad nedemön memi nelaidüpik bevüresodatävöma ad logön votükamis.
'''Mozilla / Firefox / Safari:''' kipolöd klavi: ''Shift'' dono e välolöd eli ''Reload'' (= dönulodön) me mugaparat, u dränolöd klävis: ''Ctrl-F5'' u ''Ctrl-R'' (''Command-R'' if labol eli Macintosh);
'''Konqueror:''' välolöd eli ''Reload'' (= dönulodön) me mugaparat, u dränolöd klavi: ''F5'';
'''Opera:''' vagükolöd lölöfiko memi nelaidüpik me ''Tools → Preferences'' (Stumem → Buükams).
'''Internet Explorer:''' kipolöd klavi: ''Ctrl'' dono e välolöd eli ''Refresh'' (= flifädükön) me mugaparat, u dränolöd klavis: ''Ctrl-F5''.",
'usercssyoucanpreview'             => "'''Mob:''' Välolös eli „{{int:showpreview}}“ ad blufön eli CSS nulik olik bü dakip.",
'userjsyoucanpreview'              => "'''Mob:''' Välolös eli „{{int:showpreview}}“ ad blufön eli JS nulik olik bü dakip.",
'usercsspreview'                   => "'''Memolös, das anu te büologol eli CSS olik.'''
'''No nog pedakipon!'''",
'userjspreview'                    => "'''Memolös, das anu te blufol/büologol eli JavaScript olik, no nog pedakipon!'''",
'userinvalidcssjstitle'            => "'''Nuned:''' No dabinon fomät: \"\$1\".
Memolös, das pads: .css e .js mutons labön tiädi minudik: {{ns:user}}:Foo/vector.css, no {{ns:user}}:Foo/Vector.css.",
'updated'                          => '(peatimükon)',
'note'                             => "'''Penet:'''",
'previewnote'                      => "'''Is pajonon te büologed; votükams no nog pedakipons!'''",
'previewconflict'                  => 'Büologed at jonon vödemi in redakamaspad löpik soäsä opubon if odakipol oni.',
'session_fail_preview'             => "'''Pidö! No emögos ad lasumön votükamis olik kodü per redakamanünodas.<br />Steifülolös dönu. If no oplöpol, tän senunädolös e genunädolös oli, e steifülolös nogna.'''",
'session_fail_preview_html'        => "'''Liedo no eplöpos ad zepön redakami olik kodü per nünodas.'''

''Bi {{SITENAME}} emogükon gebi kota: HTML krüdik, büologed peklänedon as jel ta tataks me el JavaScript.

'''If evilol dunön redakami legik, steifülolös dönu. If no jäfidon, senunädolös oli e nunädolös oli dönu.'''",
'token_suffix_mismatch'            => "'''Redakam olik no peläsumon bi dünanünöm olik ädädükon malülis redakama at.
Redakam perefudon ad vitön dädükami padavödema.
Atos jenon ömna ven geboy düni pladulöma nennemik bevüresodik säkädik.'''",
'editing'                          => 'Redakam pada: $1',
'editingsection'                   => 'Redakam pada: $1 (diläd)',
'editingcomment'                   => 'Redakam pada: $1 (diläd nulik)',
'editconflict'                     => 'Redakamakonflit: $1',
'explainconflict'                  => "Ek evotükon padi at sisä äprimol ad redakön oni.
Vödem balid jonon padi soäsä dabinon anu.
Votükams olik pajonons in vödem telid.
Sludolös, vio fomams tel at mutons pabalön.
Kanol kopiedön se vödem telid ini balid.
'''Te''' vödem balid podakipon ven knopol knopi: \"{{int:savearticle}}\".",
'yourtext'                         => 'Vödem olik',
'storedversion'                    => 'Fomam pedakipöl',
'nonunicodebrowser'                => "'''NÜNED: Bevüresodatävöm olik no kanon gebön eli Unicode.
Ad dälön ole ad redakön padis, malats no-ASCII opubons in redakamabog as kots degmälnumatik.'''",
'editingold'                       => "'''NUNED: Anu redakol fomami büik pada at. If dakipol oni, votükams posik onepubons.'''",
'yourdiff'                         => 'Difs',
'copyrightwarning'                 => "Demolös, das keblünots valik lü Vükiped padasumons ma el $2 (logolöd eli $1 tefü notets). If no vilol, das vödems olik poredakons nenmisero e poseagivons ma vil alana, tän no pladolös oni isio.<br />
Garanol obes, das ol it epenol atosi, u das ekopiedol atosi se räyun notidik u se fon libik sümik.<br />
'''NO PLADOLÖD ISIO NEN DÄL LAUTANA VÖDEMIS LABÜ KOPIEDAGITÄT!'''",
'copyrightwarning2'                => "Demolös, das keblünots valik lü {{SITENAME}} dalons paredakön, pavotükön, u pamoükön fa keblünans votik.
If no vilol, das vödems olik poredakons nenmisero, tän no pladolös onis isio.<br />
Garanol obes, das ol it epenol atosi, u das ekopiedol atosi se räyun notidik u se fon libik sümik (logolös eli $1 pro notets).

'''NO PLADOLÖD ISIO NEN DÄL LAUTANA VÖDEMIS LABÜ KOPIEDAGITÄT!'''",
'longpageerror'                    => "'''PÖL: Vödem fa ol pesedöl labon lunoti miljölätas $1, kelos pluon leigodü völad muik pedälöl miljölätas $2. No kanon padakipön.'''",
'readonlywarning'                  => "'''NUNED: Vük pefärmükon kodü kodididazesüd. No kanol dakipön votükamis olik anu. Kopiedolös vödemi nulik ini program votik e dakipolös oni in nünöm olik. Poso okanol dönu steifülön ad pladön oni isio.'''

Geban, kel efärmükon oni, egevon kodi at: $1",
'protectedpagewarning'             => "'''NUNED: Pad at pejelon, dat te gebans labü guvanagitäts kanons redakön oni.'''",
'semiprotectedpagewarning'         => "'''Noet:''' Pad at pefärmükon. Te gebans peregistaröl kanons redakön oni.",
'cascadeprotectedwarning'          => "'''Nuned:''' Pad at pefärmükon löko (te guvans dalons redakön oni) bi binon dil {{PLURAL:$1|pada|padas}} sököl, me sökodajel {{PLURAL:$1|pejelöla|pejelölas}}:",
'titleprotectedwarning'            => "'''NUNED: Pad at pejelon, dat te gebans labü [[Special:ListGroupRights|gitäts patik]] kanons jafön oni.'''",
'templatesused'                    => '{{PLURAL:$1|Samafomot|Samafomots}} su pad at {{PLURAL:$1|pegeböl|pegeböls}}:',
'templatesusedpreview'             => '{{PLURAL:$1|Samafomot|Samafomots}} in büologed at {{PLURAL:$1|pageböl|pageböls}}:',
'templatesusedsection'             => '{{PLURAL:$1|Samafomot|Samafomots}} in diläd at {{PLURAL:$1|pageböl|pageböls}}:',
'template-protected'               => '(pejelon)',
'template-semiprotected'           => '(dilo pejelon)',
'hiddencategories'                 => 'Pad at duton lü {{PLURAL:$1|klad peklänedöl 1|klads peklänedöl $1}}:',
'nocreatetitle'                    => 'Padijafam pemiedükon',
'nocreatetext'                     => '{{SITENAME}} emiedükon mögi ad jafön padis nulik.
Kanol redakön padi dabinöl, u [[Special:UserLogin|nunädön oli u jafön kali]].',
'nocreate-loggedin'                => 'No dalol jafön padis nulik.',
'permissionserrors'                => 'Dälapöls',
'permissionserrorstext'            => 'No dalol dunön atosi sekü {{PLURAL:$1|kod|kods}} sököl:',
'permissionserrorstext-withaction' => 'No dalol $2, sekü {{PLURAL:$1|kod|kods}} sököl:',
'recreate-moveddeleted-warn'       => "'''NUNED: Dönujafol padi pemoüköl.'''

Vätälolös, va binos pötik ad lairedakön padi at.
Jenotalised moükama pada at pajonon is as yuf.",
'moveddeleted-notice'              => 'Pad at pemoükon.
Jenotems moükamas e topätükamas pada palisedon dono.',
'edit-hook-aborted'                => 'Redakam pestöpädon fa huköm.
No enunon kodi.',
'edit-gone-missing'                => 'No eplöpos ad votükön padi.
Jiniko pemoükon.',
'edit-conflict'                    => 'Redakamakonflit.',
'edit-no-change'                   => 'Redakam olik penedemon, bi vödemivotükams nonik pedunons.',
'edit-already-exists'              => 'No kanoy jafön padi nulik.
On ya dabinon.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Nuned: Pad at vokon „parser“-sekätis tusuvo.

Muton labön {{PLURAL:$2|voki|vokis}} läs $2, ab labon anu {{PLURAL:$1|voki|vokis}} $1.',
'expensive-parserfunction-category'       => 'Pads, kels vokons tusuvo „parser“-sekätis jerik',
'post-expand-template-inclusion-warning'  => 'Nuned: Gretot samafomotas ninükabik binon tuik.
Samafomots anik no poninükons.',
'post-expand-template-inclusion-category' => 'Pads, pö kels gretot samafomotas peninüköl pluon lä maxum.',
'post-expand-template-argument-warning'   => 'Nuned: Pad at ninädon samafomotaparameti pu bali labü stäänükamagretot tuik.
Paramet(s) at pemoädon(s).',
'post-expand-template-argument-category'  => 'Pads labü samafomotaparamets pemoädöl',
'parser-template-loop-warning'            => 'Samafomotasnal petuvon: [[$1]]',
'parser-template-recursion-depth-warning' => 'Okvoknivod maxumik samafomotas ya pereivon ($1)',

# "Undo" feature
'undo-success' => 'Redakam at kanon pasädunön. Reidolös leigodi dono ad fümükön, va vilol vo dunön atosi, e poso dakipolös votükamis ad fisädunön redakami.',
'undo-failure' => 'No eplöpos ad sädunön redakami at sekü konflits vü redakams vüik.',
'undo-norev'   => 'No eplöpos ad sädunön redakami at, bi no dabinon u pämoükon.',
'undo-summary' => 'Äsädunon votükami $1 fa [[Special:Contributions/$2|$2]] ([[User talk:$2|Bespikapad]])',

# Account creation failure
'cantcreateaccounttitle' => 'Kal no kanon pajafön',
'cantcreateaccount-text' => "Kalijaf se ladet-IP at ('''$1''') peblokon fa geban: [[User:$3|$3]].

Kod blokama fa el $3 pegivöl binon ''$2''",

# History pages
'viewpagelogs'           => 'Jonön jenotalisedis pada at',
'nohistory'              => 'Pad at no labon redakamajenotemi.',
'currentrev'             => 'Fomam anuik',
'currentrev-asof'        => 'Fomam nuik tü $1',
'revisionasof'           => 'Fomam dätü $1',
'revision-info'          => 'Fomam timü $1 fa el $2',
'previousrevision'       => '←Fomam vönedikum',
'nextrevision'           => 'Fomam nulikum→',
'currentrevisionlink'    => 'Fomam anuik',
'cur'                    => 'nuik',
'next'                   => 'sököl',
'last'                   => 'lätik',
'page_first'             => 'balid',
'page_last'              => 'lätik',
'histlegend'             => 'Difiväl: välolös fomamis ad paleigodön e gebolös klavi: "Enter" u knopi dono.<br />
Plän: (anuik) = dif tefü fomam anuik,
(lätik) = dif tefü fomam büik, p = redakam pülik.',
'history-fieldset-title' => 'Logamajenotem',
'history-show-deleted'   => 'Te pemoüköls',
'histfirst'              => 'Balid',
'histlast'               => 'Lätik',
'historysize'            => '({{PLURAL:$1|jölät 1|jöläts $1}})',
'historyempty'           => '(vagik)',

# Revision feed
'history-feed-title'          => 'Revidajenotem',
'history-feed-description'    => 'Revidajenotem pada at in vük',
'history-feed-item-nocomment' => '$1 ün $2',
'history-feed-empty'          => 'Pad pevipöl no dabinon.
Ba pemoükon se ragivs, u ba pevotanemon.
Kanol [[Special:Search|sukön]] padis nulik tefik.',

# Revision deletion
'rev-deleted-comment'         => '(küpet pemoükon)',
'rev-deleted-user'            => '(gebananem pemoükon)',
'rev-deleted-event'           => '(lisedadun pemoükon)',
'rev-deleted-text-permission' => "Padafomam at '''pemoükon'''.
Pats tefik ba patuvons in [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} jenotalised moükamas].",
'rev-deleted-text-view'       => "Padafomam at '''pemoükon'''.
As guvan, kanol logön oni; pats tefik ba binons in [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}}  jenotalised moükamas].",
'rev-delundel'                => 'jonön/klänedön',
'rev-showdeleted'             => 'jonön',
'revisiondelete'              => 'Moükön/sädunön moükami fomamas',
'revdelete-nooldid-title'     => 'Zeilafomam no lonöfon',
'revdelete-nooldid-text'      => 'U no elevälol zeilafomami(s) pro dun at, u fomam pelevälöl no dabinon, u steifülol ad klänedön fomami anuik.',
'revdelete-show-file-confirm' => 'Vilol-li fümiko logön revidi pemoüköl ragiva: „<nowiki>$1</nowiki>“ dätü $2 tü $3?',
'revdelete-show-file-submit'  => 'Si',
'revdelete-selected'          => "'''{{PLURAL:$2|Fomam|Fomams}} pevalöl pada: [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Lisedajenot|Lisedajenots}} pevälöl:'''",
'revdelete-text'              => "'''Revids pemoüköl nog opubons in padajenotem, ab ninäd (vödem) onsik no gebidons publüge.'''
Ninäd peklänedöl at binon ye nog lügolovik guvanes votik vüka: {{SITENAME}}, kels kanons nog votükön ninädi peklänedöl u geükön padi medü fometem at (üf miedöfükams u neletians votik no lonöfons).",
'revdelete-legend'            => 'Levälön miedükamis logova:',
'revdelete-hide-text'         => 'Klänedön vödemi revida',
'revdelete-hide-image'        => 'Klänedön ragivaninädi',
'revdelete-hide-name'         => 'Klänedön duni e zeili',
'revdelete-hide-comment'      => 'Klänedön redakamaküpeti',
'revdelete-hide-user'         => 'Klänedön gebananemi u ladeti-IP redakana',
'revdelete-hide-restricted'   => 'Gebön miedükamis at i demü guvans e lökofärmükön fometi at',
'revdelete-radio-same'        => '(no votükolös)',
'revdelete-radio-set'         => 'Si',
'revdelete-radio-unset'       => 'Nö',
'revdelete-suppress'          => 'Klänedön moükamakodis i de guvans (äsi de votikans)',
'revdelete-unsuppress'        => 'Moükön miedükamis fomamas pegegetöl',
'revdelete-log'               => 'Kod:',
'revdelete-submit'            => 'Gebön me {{PLURAL:$1|fomam pevälöl|fomams pevälöls}}',
'revdelete-logentry'          => 'logov fomamas pada: [[$1]] pevotükon',
'logdelete-logentry'          => 'logov jenota: [[$1]] pevotükon',
'revdelete-success'           => "'''Logov padafomama pelonon benosekiko.'''",
'logdelete-success'           => 'Logov jenotaliseda pelonon benosekiko.',
'revdel-restore'              => 'Votükön logovi',
'pagehist'                    => 'Padajenotem',
'deletedhist'                 => 'Jenotem pemoüköl',
'revdelete-content'           => 'ninäd',
'revdelete-summary'           => 'plän redakama',
'revdelete-uname'             => 'gebananem',
'revdelete-restricted'        => 'miedükams pelonöfükons pro guvans',
'revdelete-unrestricted'      => 'miedükams pro guvans pemoükons',
'revdelete-hid'               => '$1 peklänedon',
'revdelete-unhid'             => '$1 pesäklänedon',
'revdelete-log-message'       => '$1 tefü {{PLURAL:$2|fomam|fomams}} $2',
'logdelete-log-message'       => '$1 tefü {{PLURAL:$2|jenot|jenots}} $2',
'revdelete-otherreason'       => 'Kod votik/zuik:',
'revdelete-reasonotherlist'   => 'Kod votik',
'revdelete-edit-reasonlist'   => 'Redakön kodis moükama',
'revdelete-offender'          => 'Lautan fomama:',

# Suppression log
'suppressionlog'     => 'Lovelogam-jenotalised',
'suppressionlogtext' => 'Is palisedons moükams e blokams lätik, kels ätefons ninädi de guvans peklänedöli. Logolös [[Special:IPBlockList|lisedi ladetas-IP pebloköl]], kö pajonons blokams anu lonöföls.',

# Revision move
'moverevlogentry'        => 'petopätükon {{PLURAL:$3|revid bal|revids $3}} de "$1" lü "$2"',
'revmove-reasonfield'    => 'Kod:',
'revmove-nullmove-title' => 'Tiäd badik',

# History merging
'mergehistory'                     => 'Balön padajenotemis',
'mergehistory-header'              => 'Pad at mogükon balami fomamis se jenotem fonätapada ad fomön padi nulik.
Kontrololös, va votükam at okipon fovöfi padajenotema.',
'mergehistory-box'                 => 'Balön fomamis padas tel:',
'mergehistory-from'                => 'Fonätapad:',
'mergehistory-into'                => 'Zeilapad:',
'mergehistory-list'                => 'Redakamajenotem balovik',
'mergehistory-merge'               => 'Fomams sököl pada: [[:$1]] kanons pabalön ini pad: [[:$2]]. Välolös ad balön te fomamis pejaföl ün u bü tim pegivöl. Demolös, das geb nafamayümas osädunon väli olik.',
'mergehistory-go'                  => 'Jonön redakamis balovik',
'mergehistory-submit'              => 'Balön fomamis',
'mergehistory-empty'               => 'Fomams nonik kanons pabalön.',
'mergehistory-success'             => '{{PLURAL:$3|Fomam 1|Fomams $3}} pada: [[:$1]] {{PLURAL:$3|pebalon|pebalons}} benosekiko ini pad: [[:$2]].',
'mergehistory-fail'                => 'No eplöpos ad ledunön balami jenotemas, kontrololös pada- e timaparametis.',
'mergehistory-no-source'           => 'Fonätapad: $1 no dabinon.',
'mergehistory-no-destination'      => 'Zeilapad: $1 no dabinon.',
'mergehistory-invalid-source'      => 'Fonätapad muton labön tiädi lonöföl',
'mergehistory-invalid-destination' => 'Zeilapad muton labön tiädi lonöföl.',
'mergehistory-autocomment'         => 'Pad: [[:$1]] peninükon ini pad: [[:$2]].',
'mergehistory-comment'             => 'Pad: [[:$1]] peninükon ini pad: [[:$2]]: $3',
'mergehistory-same-destination'    => 'Fonäta- e zeilapad no dalons binön pad ot.',
'mergehistory-reason'              => 'Kod:',

# Merge log
'mergelog'           => 'Jenotalised padibalamas',
'pagemerge-logentry' => 'Pad: [[$1]] pebalon ad [[$2]] (fomams jüesa $3)',
'revertmerge'        => 'Säbalön',
'mergelogpagetext'   => 'Is palisedon balamis brefabüikün jenotema pada bal ini votik.',

# Diffs
'history-title'            => 'Revidajenotem pada: "$1"',
'difference'               => '(Dif vü revids)',
'difference-multipage'     => '(Dif vü pads)',
'lineno'                   => 'Lien $1:',
'compareselectedversions'  => 'Leigodolöd fomamis pevälöl',
'showhideselectedversions' => 'Jonön/klänedön fomamis pevälöl',
'editundo'                 => 'sädunön',
'diff-multi'               => '({{PLURAL:$1|Revid vüik bal|Revids vüik $1}} fa {{PLURAL:$2|geban bal|gebans $2}} no {{PLURAL:$1|pejonon|pejonons}})',

# Search results
'searchresults'                    => 'Sukaseks',
'searchresults-title'              => 'Sukaseks pro: "$1"',
'searchresulttext'                 => 'Ad lärnön mödikumosi dö suks in {{SITENAME}}, logolös [[{{MediaWiki:Helppage}}|Suks in {{SITENAME}}]].',
'searchsubtitle'                   => 'Esukol padi: \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|pads me "$1" primöls valiks]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|pads lü "$1" yumöls valiks]])',
'searchsubtitleinvalid'            => "Esukol padi: '''$1'''",
'toomanymatches'                   => 'Pads tu mödiks labü vöd(s) pesuköl petuvons. Sukolös vödi(s) votik.',
'titlematches'                     => 'Leigon ko padatiäd',
'notitlematches'                   => 'Leigon ko padatiäds nonik',
'textmatches'                      => 'Leigon ko dil padavödema',
'notextmatches'                    => 'Leigon ko nos in padavödem',
'prevn'                            => 'büik {{PLURAL:$1|$1}}',
'nextn'                            => 'sököl {{PLURAL:$1|$1}}',
'prevn-title'                      => '{{PLURAL:$1|Sukasek|Sukaseks}} büik $1',
'nextn-title'                      => '{{PLURAL:$1|Sukasek|Sukaseks}} fovik $1',
'shown-title'                      => 'Jonön {{PLURAL:$1|sukaseki|sukasekis}} $1 a pad',
'viewprevnext'                     => 'Logön padis ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => 'Sukaparamets',
'searchmenu-exists'                => "'''Dabinon pad labü nem: \"[[:\$1]]\" su vük at'''",
'searchmenu-new'                   => "'''Jafolös padi: \"[[:\$1]]\" su vük at!'''",
'searchhelp-url'                   => 'Help:Ninäd',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Logön padis labü foyümot at]]',
'searchprofile-articles'           => 'Ninädapads',
'searchprofile-project'            => 'Yufa e Proyegapads',
'searchprofile-images'             => 'Ragivs',
'searchprofile-everything'         => 'Valikos',
'searchprofile-advanced'           => 'Paramets pluik',
'searchprofile-articles-tooltip'   => 'Sukön in $1',
'searchprofile-project-tooltip'    => 'Sukön in $1',
'searchprofile-images-tooltip'     => 'Sukön ragivis',
'searchprofile-everything-tooltip' => 'Sukön in ninäd lölik (keninükamü bespikapads)',
'searchprofile-advanced-tooltip'   => 'Sukön in nemaspads patik',
'search-result-size'               => '$1 ({{PLURAL:$2|vöd 1|vöds $2}})',
'search-result-category-size'      => '{{PLURAL:$1|liman 1|limans $1}} ({{PLURAL:$2|donaklad 1|donaklads $2}}, {{PLURAL:$3|ragiv 1|ragivs $3}})',
'search-result-score'              => 'Demäd: $1%',
'search-redirect'                  => '(lüodüköm: $1)',
'search-section'                   => '(diläd: $1)',
'search-suggest'                   => 'Ediseinol-li: $1 ?',
'search-interwiki-caption'         => 'Svistaproyegs',
'search-interwiki-default'         => 'Seks se $1:',
'search-interwiki-more'            => '(pluikos)',
'search-mwsuggest-enabled'         => 'sa mobs',
'search-mwsuggest-disabled'        => 'nen mobs',
'search-relatedarticle'            => 'Tefik',
'mwsuggest-disable'                => 'Nemögükön mobis ela AJAX',
'searcheverything-enable'          => 'Sukolöd in nemaspads valik',
'searchrelated'                    => 'tefik',
'searchall'                        => 'valik',
'showingresults'                   => "Pajonons dono jü {{PLURAL:$1|sukasek '''1'''|sukaseks '''$1'''}}, primölo me nüm #'''$2'''.",
'showingresultsnum'                => "Dono pajonons {{PLURAL:$3:|sek '''1'''|seks '''$3'''}}, primölo me nüm: '''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Sek: '''$1''' se '''$3'''|Seks: '''$1 - $2''' se '''$3'''}} pro '''$4'''",
'nonefound'                        => "'''Noet''': Suks jenons nomiko in nemaspads te aniks. Ad demön nemaspadis valik (keninükamü bespikapads, samafomots e r.), gebolös foyümoti: ''all:'', u nemaspadi pevilöl as foyümot.",
'search-nonefound'                 => 'Sukaseks nonik dabinons.',
'powersearch'                      => 'Suk',
'powersearch-legend'               => 'Suk komplitikum',
'powersearch-ns'                   => 'Sukön in nemaspads:',
'powersearch-redir'                => 'Lisedön lüodükömis',
'powersearch-field'                => 'Sukön',
'powersearch-togglelabel'          => 'Välön:',
'powersearch-toggleall'            => 'Valik',
'powersearch-togglenone'           => 'Nonik',
'search-external'                  => 'Suk plödik',
'searchdisabled'                   => 'Suk in {{SITENAME}} penemogükon. Vütimo kanol sukön yufü el Google. Demolös, das liseds onik tefü ninäd in {{SITENAME}} ba no binon anuik.',

# Quickbar
'qbsettings'               => 'Stumem',
'qbsettings-none'          => 'Nonik',
'qbsettings-fixedleft'     => 'nedeto (fimiko)',
'qbsettings-fixedright'    => 'Deto (fimiko)',
'qbsettings-floatingleft'  => 'nedeto (vebölo)',
'qbsettings-floatingright' => 'deto (vebölo)',

# Preferences page
'preferences'               => 'Buükams',
'mypreferences'             => 'Buükams obik',
'prefs-edits'               => 'Num redakamas:',
'prefsnologin'              => 'No enunädon oki',
'prefsnologintext'          => 'Nedol <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} nunädön oli]</span> büä kanol votükön gebanabuükamis.',
'changepassword'            => 'Votükön letavödi',
'prefs-skin'                => 'Fomät',
'skin-preview'              => 'Büologed',
'prefs-math'                => 'Logot formülas',
'datedefault'               => 'Buükam nonik',
'prefs-datetime'            => 'Dät e Tim',
'prefs-personal'            => 'Gebananüns',
'prefs-rc'                  => 'Votükams nulik',
'prefs-watchlist'           => 'Galädalised',
'prefs-watchlist-days'      => 'Num delas ad pajonön in galädalised:',
'prefs-watchlist-days-max'  => '(maxum: dels 7)',
'prefs-watchlist-edits'     => 'Num redakamas ad pajonön in galädalised pestäänüköl:',
'prefs-watchlist-edits-max' => '(maxumanum: 1000)',
'prefs-misc'                => 'Votikos',
'prefs-resetpass'           => 'Votükön letavödi',
'prefs-rendering'           => 'Selogam',
'saveprefs'                 => 'Dakipolöd',
'resetprefs'                => 'Buükams rigik',
'restoreprefs'              => 'Geikön lü paramets kösömik valik',
'prefs-editing'             => 'Redakam',
'prefs-edit-boxsize'        => 'Gretot redakamafenäta.',
'rows'                      => 'Kedets:',
'columns'                   => 'Padüls:',
'searchresultshead'         => 'Suk',
'resultsperpage'            => 'Tiäds petuvöl a pad:',
'contextlines'              => 'Kedets a pad petuvöl:',
'contextchars'              => 'Kevödem a kedet:',
'stub-threshold'            => 'Soliad pro fomätam <a href="#" class="stub">sidayümas</a> (jöläts):',
'recentchangesdays'         => 'Dels ad pajonön in votükams nulik:',
'recentchangesdays-max'     => '(maxum: {{PLURAL:$1|del|dels}} $1)',
'recentchangescount'        => 'Num kösömik redakamas ad pajonön:',
'savedprefs'                => 'Buükams olik pedakipons.',
'timezonelegend'            => 'Timatopäd:',
'localtime'                 => 'Tim topik:',
'timezoneuseserverdefault'  => 'Gebön parametemi kösömik dünanünöma',
'timezoneuseoffset'         => 'Votik (nunolös difi)',
'timezoneoffset'            => 'Näedot¹:',
'servertime'                => 'Tim dünanünöma:',
'guesstimezone'             => 'Benüpenolös yufü befüresodatävöm',
'timezoneregion-africa'     => 'Frikop',
'timezoneregion-america'    => 'Merop',
'timezoneregion-asia'       => 'Siyop',
'timezoneregion-atlantic'   => 'Latlantean',
'timezoneregion-australia'  => 'Laustralän',
'timezoneregion-europe'     => 'Yurop',
'timezoneregion-indian'     => 'Lindean',
'timezoneregion-pacific'    => 'Pasifean',
'allowemail'                => 'Fägükolös siti ad getön poti leäktronik de gebans votik',
'prefs-searchoptions'       => 'Sukaparamets',
'prefs-namespaces'          => 'Nemaspads',
'defaultns'                 => 'Votiko sukolös in nemaspads at:',
'default'                   => 'stad kösömik',
'prefs-files'               => 'Ragivs',
'prefs-custom-css'          => 'CSS nekösömik',
'prefs-custom-js'           => 'JavaScript nekösömik',
'youremail'                 => 'Ladet leäktronik *:',
'username'                  => 'Gebananem:',
'uid'                       => 'Gebanadientif:',
'prefs-memberingroups'      => 'Liman {{PLURAL:$1|grupa|grupas}}:',
'yourrealname'              => 'Nem jenöfik *:',
'yourlanguage'              => 'Pük:',
'yournick'                  => 'Dispenäd nulik:',
'badsig'                    => 'Dispenäd no lonöföl: dönulogolös eli HTML.',
'badsiglength'              => 'Dispenäd olik binon tu lunik.
Muton labön {{PLURAL:$1|malati|malatis}} läs $1.',
'yourgender'                => 'Gen:',
'gender-male'               => 'Manik',
'gender-female'             => 'Vomik',
'email'                     => 'Ladet leäktronik',
'prefs-help-realname'       => 'Nem jenöfik no binon zesüdik. If vilol givön oni, pogebon ad dasevön vobi olik.',
'prefs-help-email'          => 'Ladet leäktronik no peflagon, ab dälon sedi letavöda nulik ole üf glömol letavödi olik.
Dalol i dälön votikanes kosikön ko ol yufü gebana- u bespikapad olik nes sävilupol dientifi olik.',
'prefs-help-email-required' => 'Ladet leäktronik paflagon.',
'prefs-info'                => 'Nüns stabik',
'prefs-signature'           => 'Dispenäd',
'prefs-dateformat'          => 'Dätafomam',
'prefs-diffs'               => 'Difs',

# User rights
'userrights'                  => 'Guvam gebanagitätas',
'userrights-lookup-user'      => 'Guvön gebanagrupis',
'userrights-user-editname'    => 'Penolös gebananemi:',
'editusergroup'               => 'Redakön Gebanagrupis',
'editinguser'                 => "Votükam gitätas gebana: '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'    => 'Redakön gebanagrupis',
'saveusergroups'              => 'Dakipolöd gebanagrupis',
'userrights-groupsmember'     => 'Liman grupa(s):',
'userrights-groups-help'      => 'Dalol votükön grupis, lü kels geban at duton.
* Bügil fulik sinifon, das geban duton lü grup tefik.
* Bügil vagik sinifon, das geban no duton lü grup tefik.
* El * sinifon, das no kanol moükön grupi posä iläükol oni, u güo.',
'userrights-reason'           => 'Kod:',
'userrights-no-interwiki'     => 'No labol däli ad votükön gebanagitätis in vüks votik.',
'userrights-nodatabase'       => 'Nünodem: $1 no dabinon, u no binon topik.',
'userrights-nologin'          => 'Mutol [[Special:UserLogin|nunädön oli]] me guvanakal ad dalön gevön gitätis gebanes.',
'userrights-notallowed'       => 'Kal olik no labon däli ad votükön gebanagitätis.',
'userrights-changeable-col'   => 'Grups fa ol votükoviks',
'userrights-unchangeable-col' => 'Grups fa ol nevotükoviks',

# Groups
'group'               => 'Grup:',
'group-user'          => 'Gebans',
'group-autoconfirmed' => 'Gebans itjäfidiko pezepöls',
'group-bot'           => 'Bots',
'group-sysop'         => 'Guvans',
'group-bureaucrat'    => 'Bürans',
'group-suppress'      => 'Lovelogams',
'group-all'           => '(valik)',

'group-user-member'          => 'Geban',
'group-autoconfirmed-member' => 'Geban itjäfidiko pezepöl',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Guvan',
'group-bureaucrat-member'    => 'Büran',
'group-suppress-member'      => 'Lovelogam',

'grouppage-user'          => '{{ns:project}}:Gebans',
'grouppage-autoconfirmed' => '{{ns:project}}:Gebans itjäfidiko pezepöls',
'grouppage-bot'           => '{{ns:project}}:Bots',
'grouppage-sysop'         => '{{ns:project}}:Guvans',
'grouppage-bureaucrat'    => '{{ns:project}}:Bürans',
'grouppage-suppress'      => '{{ns:project}}:Lovelogam',

# Rights
'right-read'                 => 'Reidön padis',
'right-edit'                 => 'Redakön padis',
'right-createpage'           => 'Jafön padis (no bespikapadis)',
'right-createtalk'           => 'Jafön bespikapadis',
'right-createaccount'        => 'Jafön gebanakalis nulik',
'right-minoredit'            => 'Malön redakamis as püliks.',
'right-move'                 => 'Topätükön padis',
'right-move-subpages'        => 'Topätükön padis kobü donapads onsik',
'right-move-rootuserpages'   => 'Topätükön gebanapadis cifik',
'right-movefile'             => 'Topätükön ragivis',
'right-suppressredirect'     => 'No jafön lüodükömi de nem büik posä pad petopätükon',
'right-upload'               => 'Löpükön ragivis',
'right-reupload'             => 'Lovepladön sui ragiv ya dabinöl',
'right-reupload-own'         => 'Lovepladön sui ragiv dabinöl fa ol it pelöpüköl',
'right-reupload-shared'      => 'Nedemön ragivis se ragivakipedöp kobädik',
'right-upload_by_url'        => 'Löpükön ragivi se ladet-URL.',
'right-purge'                => 'Vagükön memi nelaidüpik pada nen fümedam',
'right-autoconfirmed'        => 'Redakön padis dilo pejelölis',
'right-bot'                  => 'Palelogön as dun itjäfidik',
'right-nominornewtalk'       => 'No dälön redakames pülik bespikapadas ad kodön nuni: „nuns nulik“',
'right-apihighlimits'        => 'Gebön miedis löpikum pö seivids-API',
'right-writeapi'             => 'Gebi ela API penamik',
'right-delete'               => 'Moükön padis',
'right-bigdelete'            => 'Moükön padis labü jenotems lunik',
'right-deleterevision'       => 'Moükön u sädunön moükami padafomamas pevälöl',
'right-deletedhistory'       => 'Logön jenotemis pemoüköl nen vödems tefik',
'right-browsearchive'        => 'Sukön padis pemoüköl',
'right-undelete'             => 'Sädunön padimoükami',
'right-suppressrevision'     => 'Logön e nätükön revidis se guvans peklänedölis',
'right-suppressionlog'       => 'Logön jenotalisedis privatik',
'right-block'                => 'Blokön redakamagitäti gebanas votik',
'right-blockemail'           => 'Blokön gitäti gebana ad sedön penedis leäktronik',
'right-hideuser'             => 'Blokön gebananemi, klänedölo oni de votikans',
'right-ipblock-exempt'       => 'Nedemön blokamis-IP, blokamis itjäfidik e grupiblokamis',
'right-proxyunbannable'      => 'Nedemön blokamis itjäfidik pladulömas',
'right-protect'              => 'Votükön jelanivodis e redakön padis pejelöl',
'right-editprotected'        => 'Bevobön padis pejelöl (nen vatafalajel)',
'right-editinterface'        => 'Votükön gebanaloveikömi',
'right-editusercssjs'        => 'Redakön ragivis-CSS e -JS gebanas votik',
'right-editusercss'          => 'Redakön ragivis-CSS gebanas votik',
'right-edituserjs'           => 'Redakön ragivis-JS gebanas votik',
'right-rollback'             => 'Sädunön vifiko redakamis gebana lätik, kel äredakon padi semik.',
'right-markbotedits'         => 'Bepenön redakamis pesädunöl as redakams ela bot',
'right-noratelimit'          => 'No lobedön miedükamis',
'right-import'               => 'Nüveigön padis se vüks votik',
'right-importupload'         => 'Nüveigön padis se ragivilöpükam',
'right-patrol'               => 'Zepön redakamis votikanas',
'right-autopatrol'           => 'Zepön itjäfidiko redakamis okik',
'right-patrolmarks'          => 'Logön zepamals in lised votükamas nulik',
'right-unwatchedpages'       => 'Logön lisedi padas nepagalädöl',
'right-trackback'            => 'Sedön gevegi',
'right-mergehistory'         => 'Kobükön padajenotemis',
'right-userrights'           => 'Redakön gebanagitätis valik',
'right-userrights-interwiki' => 'Redakön gebanagitätis gebanas vükas votik',
'right-siteadmin'            => 'Lökofärmükön e maifükön nünodemi',
'right-sendemail'            => 'Sedön penedis leäktronik lü gebans votik',
'right-revisionmove'         => 'Topätükön fomamis',

# User rights log
'rightslog'      => 'Jenotalised gebanagitätas',
'rightslogtext'  => 'Is palisedons votükams gebanagitätas.',
'rightslogentry' => 'grupalimanam gebana: $1 pevotükon de $2 ad $3',
'rightsnone'     => '(nonik)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'reidön padi at',
'action-edit'                 => 'redakön padi at',
'action-createpage'           => 'jafön padis',
'action-createtalk'           => 'jafön bespikapadis',
'action-createaccount'        => 'jafön gebanakali at',
'action-minoredit'            => 'bepenön redakami at as pülik',
'action-move'                 => 'topätükön padi at',
'action-move-subpages'        => 'topätükön padi at äsi donapadis onik',
'action-move-rootuserpages'   => 'topätükön gebanapadis cifik',
'action-movefile'             => 'topätükön ragivi at',
'action-upload'               => 'löpükön ragivi at',
'action-reupload'             => 'lovepladön sui ragiv dabinöl at',
'action-reupload-shared'      => 'nedemön ragivi at se kipedöp kobädik',
'action-upload_by_url'        => 'löpükön ragivi at se ladet-URL',
'action-writeapi'             => 'gebön eli API penamik',
'action-delete'               => 'moükön padi at',
'action-deleterevision'       => 'moükön fomami at',
'action-deletedhistory'       => 'logön jenotemi pemoüköl pada at',
'action-browsearchive'        => 'sukön vü pads pemoüköl',
'action-undelete'             => 'sämoükön padi at',
'action-suppressrevision'     => 'nülogön e gepladön revidi peklänedöl at',
'action-suppressionlog'       => 'logön jenotalisedi privatik at',
'action-block'                => 'blokön redakami gebana at',
'action-protect'              => 'votükön jelanivodis pada at',
'action-import'               => 'nüveigön padi at se vük votik',
'action-importupload'         => 'nüveigön padi at se ragivilöpükam',
'action-patrol'               => 'Zepön redakami votikanas',
'action-autopatrol'           => 'zepön redakami olik',
'action-unwatchedpages'       => 'Logön lisedi padas no pagalädölas',
'action-trackback'            => 'sedön gevegi',
'action-mergehistory'         => 'balön jenotemi pada at',
'action-userrights'           => 'redakön gebanagitätis valik',
'action-userrights-interwiki' => 'redakön gebanagitätis gebanas vükas votik',
'action-siteadmin'            => 'lökofärmükön u maifükön nünodemi',

# Recent changes
'nchanges'                          => '{{PLURAL:$1|votükam|votükams}} $1',
'recentchanges'                     => 'Votükams nulik',
'recentchanges-legend'              => 'Votükams nulik: paramets',
'recentchangestext'                 => 'Su pad at binons votükams nulikün in vüki at.',
'recentchanges-feed-description'    => 'Getön votükamis nulikün in vük at me nünakanad at.',
'recentchanges-label-newpage'       => 'Redakam at päjafon pad nulik',
'recentchanges-label-minor'         => 'Atos binon redakam pülik',
'recentchanges-label-bot'           => 'Redakam at pädunon fa el bot',
'rcnote'                            => "Dono {{PLURAL:$1|binon votükam '''1'''|binons votükams '''$1'''}} lätikün {{PLURAL:$2|dela|delas '''$2'''}} lätikün, pänumädöls tü $5, $4.",
'rcnotefrom'                        => "Is palisedons votükams sis '''$2''' (jü '''$1''').",
'rclistfrom'                        => 'Jonön votükamis nulik, primölo tü düp $1',
'rcshowhideminor'                   => '$1 votükamis pülik',
'rcshowhidebots'                    => '$1 elis bot',
'rcshowhideliu'                     => '$1 gebanis penunädöl',
'rcshowhideanons'                   => '$1 gebanis nennemik',
'rcshowhidepatr'                    => 'Redakams $1 pekontrolons',
'rcshowhidemine'                    => '$1 redakamis obik',
'rclinks'                           => 'Jonön votükamis lätik $1 ün dels lätik $2<br />$3',
'diff'                              => 'dif',
'hist'                              => 'jen',
'hide'                              => 'Klänedön',
'show'                              => 'Jonolöd',
'minoreditletter'                   => 'p',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[{{PLURAL:$1|geban|gebans}} galädöl $1]',
'rc_categories'                     => 'Te klads fovik (ditolös me el "|")',
'rc_categories_any'                 => 'Alseimik',
'newsectionsummary'                 => '/* $1 */ diläd nulik',
'rc-enhanced-expand'                => 'Jonön patis (el JavaScript zesüdon)',
'rc-enhanced-hide'                  => 'Klänedön patis',

# Recent changes linked
'recentchangeslinked'          => 'Votükams teföl',
'recentchangeslinked-feed'     => 'Votükams teföl',
'recentchangeslinked-toolbox'  => 'Votükams teföl',
'recentchangeslinked-title'    => 'Votükams tefü pad: "$1"',
'recentchangeslinked-noresult' => 'Pads ad pad at peyümöls no pevotükons ün period at.',
'recentchangeslinked-summary'  => "Su pad patik at palisedons votükams padas, lü kels pad pevälöl yumon.
If ye pad pevälöl binon klad, palisedons is votükams nulik padas in klad at.
Pads [[Special:Watchlist|galädaliseda olik]] '''pakazetons'''.",
'recentchangeslinked-page'     => 'Padanem:',
'recentchangeslinked-to'       => 'Jonön güo votükamis padas, kels yumons ad pad pevälöl',

# Upload
'upload'                      => 'Löpükön ragivi',
'uploadbtn'                   => 'Löpükön ragivi',
'reuploaddesc'                => 'Nosükon lopükami e geikön lü löpükamafomet.',
'uploadnologin'               => 'No enunädon oki',
'uploadnologintext'           => 'Mutol [[Special:UserLogin|nunädön oli]] ad löpükön ragivis.',
'upload_directory_missing'    => 'Löpükamaragiviär ($1) no dabinon e no ekanon pajafön fa dünanünöm bevüresodik.',
'upload_directory_read_only'  => 'Ragiviär lopükama ($1) no kanon papenön fa dünanünöm bevüresodik.',
'uploaderror'                 => 'Pök pö löpükam',
'uploadtext'                  => "Gebolös fometi dono ad löpükön ragivis.
Ad logön u sukön ragivis ya pelöpükölis, gebolös [[Special:FileList|lisedi ragivas pelöpüköl]]; (dönu)löpukams palisedons i su [[Special:Log/upload|jenotalised löpükamas]], moükams su [[Special:Log/delete|jenotalised moükamas]].

Ad pladön ragivi ini pad semik, gebolös yümi fomätü:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Ragiv.jpg]]</nowiki></tt>''' ad pladön ragivi in fomät lölöfik;
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Ragiv.png|200px|thumb|left|vödem]]</nowiki></tt>''' ad pladön ragivi in fomät smalik (vidotü pixels 200) in bügil nedeto labü „vödem“ as bepenam;
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Ragiv.ogg]]</nowiki></tt>''' ad yümön nemediko ad ragiv nes jonön oni.",
'upload-permitted'            => 'Ragivasots pedälöl: $1.',
'upload-preferred'            => 'Ragivasots buik: $1.',
'upload-prohibited'           => 'Ragivasots peproiböl: $1.',
'uploadlog'                   => 'jenotalised löpükamas',
'uploadlogpage'               => 'Jenotalised löpükamas',
'uploadlogpagetext'           => 'Dono binon lised ravigalöpükamas nulikün.',
'filename'                    => 'Ragivanem',
'filedesc'                    => 'Plän brefik',
'fileuploadsummary'           => 'Plän brefik:',
'filereuploadsummary'         => 'Votükams ragiva:',
'filestatus'                  => 'Stad tefü kopiedagität:',
'filesource'                  => 'Fon:',
'uploadedfiles'               => 'Ragivs pelöpüköl',
'ignorewarning'               => 'Nedemön nunedi e dakipön ragivi',
'ignorewarnings'              => 'Nedemolöd nunedis alseimik',
'minlength1'                  => 'Ragivanems mutons labön tonati pu bali.',
'illegalfilename'             => 'Ragivanem: „$1“ labon malatis no pedälölis pö padatiäds. Votanemolös ragivi e steifülolös ad löpükön oni dönu.',
'badfilename'                 => 'Ragivanem pevotükon ad "$1".',
'filetype-badmime'            => 'Ragivs MIME-pateda "$1" no dalons palöpükön.',
'filetype-bad-ie-mime'        => 'Löpükam ragiva at no mögon, bi el Internet Explorer lelogonöv oni asä „$1“: ragivasot no pedälöl ä mögiko riskädik.',
'filetype-unwanted-type'      => "'''\".\$1\"''' binon ragivasot no pavipöl.
{{PLURAL:\$3|Ragivasot pabuüköl binon|Ragivasots pabuüköl binons}} \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' binon ragivasot no pedälöl.
{{PLURAL:\$3|Ragivasot pedälöl binon|Ragivasots pedälöl binons}} \$2.",
'filetype-missing'            => 'Ragiv no labon stäänükoti (äs el „.jpg“).',
'large-file'                  => 'Pakomandos, das ragivs no binons gretikums ka mö $1; ragiv at binon mö $2.',
'largefileserver'             => 'Ragiv at binon tu gretik: dünanünöm no kanon dälon oni.',
'emptyfile'                   => 'Ragiv fa ol pelöpüköl binon jiniko vägik. Kod atosa äbinon ba pöl pö ragivanem. Vilol-li jenöfo löpükön ragivi at?',
'fileexists'                  => "Ragiv labü nem at ya dabinon, logolös, begö! '''<tt>[[:$1]]</tt>''' üf no sevol fümiko, va vilol votükön oni.
[[$1|thumb]]",
'filepageexists'              => "Bepenamapad ragiva at ya pejafon ('''<tt>[[:$1]]</tt>'''), ab ragiv nonik labü nem at dabinon anu.
Naböfodönuam olik no opubon su bepenamapad.
Ad pübön oni us, onedol redakön oni ol it.
[[$1|thumb]]",
'fileexists-extension'        => "Ragiv labü nem sümik ya dabinon: [[$2|thumb]]
* Nem ragiva palöpüköl: '''<tt>[[:$1]]</tt>'''
* Nem ragiva dabinöl: '''<tt>[[:$2]]</tt>'''
Välolös, begö! nemi difik.",
'fileexists-thumbnail-yes'    => "Ragiv at binon jiniko magoda gretota smalik ''(magodil)''. [[$1|thumb]]
Logolös, begö! ragivi ya dabinöli: '''<tt>[[:$1]]</tt>'''.
If ragiv ya dabinöli binon magod ot gretota rigik, no zesüdos ad löpükön magodili pluik.",
'file-thumbnail-no'           => "Ragivanem primon me '''<tt>$1</tt>'''. Binon jiniko magod gretota smalik ''(magodil)''.
Üf labol magodi at gretota rigik, löpükölos oni, pläo votükolös ragivanemi.",
'fileexists-forbidden'        => 'Ragiv labü nem at ya dabinon e no dalon paplaädön.
If nog vilol lopükön ragivi olik, geikolös e gebolös nemi votik. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Ragiv labü nem at ya dabinon in ragivastok kobädik. If nog vilol löpükön ragivi olik, geikolös e löpükolös ragivi at me nem votik. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Ragiv at leigon ko {{PLURAL:$1|ragiv|ragivs}} fovik:',
'file-deleted-duplicate'      => 'Ragiv votik, kel leigon ko ragiv at ([[:$1]]), pemoükon büo. Sötol kontrolön moükamajenotemi ragiva et büä odönulöpükol oni.',
'uploadwarning'               => 'Löpükamanuned',
'savefile'                    => 'Dakipolöd ragivi',
'uploadedimage'               => '"[[$1]]" pelöpüköl',
'overwroteimage'              => 'fomami nulik ragiva: „[[$1]]“ pelöpükon',
'uploaddisabled'              => 'Löpükam penemögükon',
'uploaddisabledtext'          => 'Löpükam ragivas penemögükon.',
'uploadscripted'              => 'Ragiv at ninükon eli HTML u vödis programapüka, kelis bevüresodanaföm ba opölanätäpreton',
'uploadvirus'                 => 'Ragiv at labon virudi! Pats: $1',
'upload-source'               => 'Ragiv fonätik',
'sourcefilename'              => 'Ragivanem rigik:',
'destfilename'                => 'Ragivanem nulik:',
'upload-maxfilesize'          => 'Ragivagretot gretikün: $1',
'watchthisupload'             => 'Galädolöd ragivi at',
'filewasdeleted'              => 'Ragiv labü nem at büo pelöpükon e poso pemoükon. Kontrololös eli $1 büä olöpükol oni dönu.',
'upload-wasdeleted'           => "'''Nuned: Löpükol ragivi büo pimoüköl.'''

Vätälolös, va pötos ad löpükön ragivi at. Kodü koveniäl, jenotalised tefü moükam ragiva at pagivon is.",
'filename-bad-prefix'         => "Nem ragiva fa ol palöpüköl primon me '''\"\$1\"''': nem no bepenöl nomiko pagevöl itjäfidiko fa käms nulädik. Välolös, begö! nemi bepenöl pro ragiv olik.",
'upload-success-subj'         => 'Löpükam eplöpon',

'upload-proto-error'      => 'Protok neverätik',
'upload-proto-error-text' => 'Löpükam flagon elis URLs me <code>http://</code> u <code>ftp://</code> primölis.',
'upload-file-error'       => 'Pöl ninik',
'upload-file-error-text'  => 'Pöl ninik äjenon dü steifül ad jafön ragivi nelaidüpik pö dünanünöm.
Begolös yufi [[Special:ListUsers/sysop|guvana]].',
'upload-misc-error'       => 'Pök nesevädik pö löpükam',
'upload-misc-error-text'  => 'Pöl nesevädik äjenon dü löpükam.
Fümedolös, begö! das el URL lonöfon e kanon palogön, e poso steifülolös nogna.
If säkäd at laibinon, kosikolös ko [[Special:ListUsers/sysop|guvan]] tefü on.',
'upload-unknown-size'     => 'Gretot nesevädik',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'No eplöpos ad rivön eli URL',
'upload-curl-error6-text'  => 'No eplöpos ad rivön eli URL at. Kontrololös, va el URL veräton e bevüresodatopäd dabinon.',
'upload-curl-error28'      => 'Löpükamatüp efinikon',
'upload-curl-error28-text' => 'Geükam se bevüresodatopäd at ya pestebedon tu lunüpiko.
Kontrololös, begö! va bevüresodatopäd at jäfidon, stebedolös timüli e steifülolös dönu.
Binosöv gudikum, if steifülolöv dönu ün tim votik läs jäfädik.',

'license'            => 'Dälastad:',
'license-header'     => 'Dälastad',
'nolicense'          => 'Nonik pelevälon',
'license-nopreview'  => '(Büologed no gebidon)',
'upload_source_url'  => ' (el URL lonöföl ä fa valans gebovik)',
'upload_source_file' => ' (ragiv pö nünöm olik)',

# Special:ListFiles
'listfiles-summary'     => 'Su pad patik at ragivs pelöpüköl valik pelisedons.
Nomiko ragivs pelöpüköl lätikün palisedons primü lised.
Klikolös tiädi padüla ad votükön sökaleodi at.',
'listfiles_search_for'  => 'Sukön ragivanemi:',
'imgfile'               => 'ragiv',
'listfiles'             => 'Ragivalised',
'listfiles_date'        => 'Dät',
'listfiles_name'        => 'Nem',
'listfiles_user'        => 'Geban',
'listfiles_size'        => 'Gretot',
'listfiles_description' => 'Bepenam',
'listfiles_count'       => 'Fomams',

# File description page
'file-anchor-link'          => 'Ragiv',
'filehist'                  => 'Jenotem ragiva',
'filehist-help'             => 'Välolös däti/timi ad logön ragivi soäsä äbinon ün tim at.',
'filehist-deleteall'        => 'moükön valikis',
'filehist-deleteone'        => 'moükön atosi',
'filehist-revert'           => 'sädunön valikosi',
'filehist-current'          => 'anuik',
'filehist-datetime'         => 'Dät/Tim',
'filehist-thumb'            => 'Magodil',
'filehist-thumbtext'        => 'Magodil fomama tü $1',
'filehist-nothumb'          => 'Magodil nonik',
'filehist-user'             => 'Geban',
'filehist-dimensions'       => 'Mafots',
'filehist-filesize'         => 'Ragivagret',
'filehist-comment'          => 'Küpet',
'imagelinks'                => 'Ragivayüms',
'linkstoimage'              => '{{PLURAL:$1|Pad sököl payümon|Pads sököl payümons}} ko pad at:',
'linkstoimage-more'         => 'Pads plu {{PLURAL:$1|bals|$1}} labons yümi lü ragiv at.
Lised dono jonon {{PLURAL:$1|padayümi balid|padayümis balid $1}} te lü ragiv at.
[[Special:WhatLinksHere/$2|Lised lölöfik]] gebidon.',
'nolinkstoimage'            => 'Pads nonik peyümons ad ragiv at.',
'morelinkstoimage'          => 'Logolös [[Special:WhatLinksHere/$1|yümis pluik]] ad ragiv at.',
'redirectstofile'           => '{{PLURAL:$1|Ragiv sököl lüodükon|Ragivs sököl $1 lüodükons}} ad ragiv at:',
'duplicatesoffile'          => '{{Plural:$1|Ragiv fovik leigon|Ragivs fovik $1 leigons}} ko ragiv at ([[Special:FileDuplicateSearch/$2|nüns pluik]]):',
'sharedupload'              => 'Ragiv at binon se $1 e kanon pagebön fa proyegs votik.',
'uploadnewversion-linktext' => 'Löpükön fomami nulik ragiva at',
'shared-repo-from'          => 'se $1',

# File reversion
'filerevert'                => 'Geükön padi: $1',
'filerevert-legend'         => 'Geükön ragivi',
'filerevert-intro'          => "Anu geükol padi: '''[[Media:$1|$1]]''' ad [fomam $4: $3, $2].",
'filerevert-comment'        => 'Kod:',
'filerevert-defaultcomment' => 'Pegeükon ad fomam: $2, $1',
'filerevert-submit'         => 'Geükön',
'filerevert-success'        => "Pad: '''[[Media:$1|$1]]''' pegeükon ad [fomam $4: $3, $2].",
'filerevert-badversion'     => 'No dabinon fomam topik büik ragiva at labü timamäk pegevöl',

# File deletion
'filedelete'                  => 'Moükön padi: $1',
'filedelete-legend'           => 'Moükön ragivi',
'filedelete-intro'            => "Moükol padi: '''[[Media:$1|$1]]''', kobü jenotem lölik ona.",
'filedelete-intro-old'        => "Anu moükol fomami pada: '''[[Media:$1|$1]]''' [$4 $3, $2].",
'filedelete-comment'          => 'Kod:',
'filedelete-submit'           => 'Moükön',
'filedelete-success'          => "'''$1''' pemoükon.",
'filedelete-success-old'      => "Fomam ela '''[[Media:$1|$1]]''' timü $3, $2 pemoükon.",
'filedelete-nofile'           => "'''$1''' no dabinon.",
'filedelete-nofile-old'       => "No dabinon fomam peregistaröl pada: '''$1''' labü pats pevipöl.",
'filedelete-otherreason'      => 'Kod votik/zuik:',
'filedelete-reason-otherlist' => 'Kod votik',
'filedelete-reason-dropdown'  => '*Kods kösömik moükama
** Nedem kopiedagitäta
** Ragiv petelöl',
'filedelete-edit-reasonlist'  => 'Redakön kodis moükama',

# MIME search
'mimesearch'         => 'Sukön (MIME)',
'mimesearch-summary' => 'Pad at mögükon ragivisulami ma MIME-sot.
Primanünods: ninädasot/donasot, a.s. <tt>image/jpeg</tt>.',
'mimetype'           => 'Klad ela MIME:',
'download'           => 'donükön',

# Unwatched pages
'unwatchedpages' => 'Pads no pagalädöls',

# List redirects
'listredirects' => 'Lised lüodükömas',

# Unused templates
'unusedtemplates'     => 'Samafomots no pageböls',
'unusedtemplatestext' => 'Pad at jonon padis valik in nemaspad: "{{ns:template}}", kels no paninükons in pad votik. Kontrololös, va dabinons yüms votik lü samafomots at büä omoükol onis.',
'unusedtemplateswlh'  => 'yüms votik',

# Random page
'randompage'         => 'Pad fädik',
'randompage-nopages' => 'Pads nonik dabinons in {{PLURAL:$2|nemaspad sököl|nemaspads sököls}}: $1.',

# Random redirect
'randomredirect'         => 'Lüodüköm fädik',
'randomredirect-nopages' => 'Lüodüköms nonik dabinons in nemaspad: "$1".',

# Statistics
'statistics'                   => 'Statits',
'statistics-header-pages'      => 'Statits pada',
'statistics-header-edits'      => 'Redakamastatits',
'statistics-header-views'      => 'Logamastatits',
'statistics-header-users'      => 'Gebanastatits',
'statistics-header-hooks'      => 'Statits votik',
'statistics-articles'          => 'Pads ninädilabik',
'statistics-pages'             => 'Pads',
'statistics-pages-desc'        => 'Pads valik vüka at, keninükamü bespikapads, lüodüköms e r.',
'statistics-files'             => 'Ragivs pelöpüköl',
'statistics-edits'             => 'Padiredakams sisä {{SITENAME}} päjafon',
'statistics-edits-average'     => 'Num zänedik redakamas a pad',
'statistics-views-total'       => 'Logams (valod)',
'statistics-views-peredit'     => 'Logams a redakam',
'statistics-users'             => '[[Special:ListUsers|Gebans]] peregistaröl',
'statistics-users-active'      => 'Gebans jäfedik',
'statistics-users-active-desc' => 'Gebans, kels edunons bosi ün {{PLURAL:$1|del lätik|dels lätik $1}}',
'statistics-mostpopular'       => 'Pads suvüno palogöls:',

'disambiguations'      => 'Telplänovapads',
'disambiguationspage'  => 'Template:Telplänov',
'disambiguations-text' => "Pads sököl payümons ad '''telplanövapad'''.
Sötons plao payümon lü yeged pötik.<br />
Pad palelogon telplänovapad if gebon samafomoti, lü kel payümon pad [[MediaWiki:Disambiguationspage]].",

'doubleredirects'            => 'Lüodüköms telik',
'doubleredirectstext'        => 'Kedet alik labon yümis lü lüodüköm balid e telid, ed i kedeti balid vödema lüodüköma telid, kel nomiko ninädon padi, ko kel lüodüköm balid söton payümön.',
'double-redirect-fixed-move' => 'Pad: [[$1]] petopätükon, anu binon lüodüköm lü pad: [[$2]]',
'double-redirect-fixer'      => 'Nätüköm lüodükömas',

'brokenredirects'        => 'Lüodüköms dädik',
'brokenredirectstext'    => 'Lüodüköms sököl dugons lü pads no dabinöls:',
'brokenredirects-edit'   => 'redakön',
'brokenredirects-delete' => 'moükön',

'withoutinterwiki'         => 'Pads nen yüms bevüpükik',
'withoutinterwiki-summary' => 'Pads sököl no yumons lü fomams in püks votik.',
'withoutinterwiki-legend'  => 'Foyümot',
'withoutinterwiki-submit'  => 'Jonolöd',

'fewestrevisions' => 'Yegeds labü revids nemödikün',

# Miscellaneous special pages
'nbytes'                  => '{{PLURAL:$1|jölät|jöläts}} $1',
'ncategories'             => '{{PLURAL:$1|klad|klads}} $1',
'nlinks'                  => '{{PLURAL:$1|yüm|yüms}} $1',
'nmembers'                => '{{PLURAL:$1|liman|limans}} $1',
'nrevisions'              => '{{PLURAL:$1|fomam|fomams}} $1',
'nviews'                  => '{{PLURAL:$1|logam|logams}} $1',
'nimagelinks'             => 'Pageböl in {{PLURAL:$1|pad|pads}} $1',
'ntransclusions'          => 'pageböl in {{PLURAL:$1|pad|pads}} $1',
'specialpage-empty'       => 'Pad at vagon.',
'lonelypages'             => 'Pads, lü kels yüms nonik dugons',
'lonelypagestext'         => 'Pads nonik in vük at peyümons ad pads sököl in {{SITENAME}}.',
'uncategorizedpages'      => 'Pads nen klad',
'uncategorizedcategories' => 'Klads nen klad löpikum',
'uncategorizedimages'     => 'Ragivs nen klad',
'uncategorizedtemplates'  => 'Samafomots nen klad',
'unusedcategories'        => 'Klads no pageböls',
'unusedimages'            => 'Ragivs no pageböls',
'popularpages'            => 'Pads suvüno pelogöls',
'wantedcategories'        => 'Klads mekabik',
'wantedpages'             => 'Pads mekabik',
'wantedfiles'             => 'Ragivs mekabik',
'wantedtemplates'         => 'Samafomots mekabik',
'mostlinked'              => 'Pads suvüno peyümöls',
'mostlinkedcategories'    => 'Klads suvüno peyümöls',
'mostlinkedtemplates'     => 'Samafomots suvüno pegeböls',
'mostcategories'          => 'Yegeds labü klads mödikün',
'mostimages'              => 'Magods suvüno peyümöls',
'mostrevisions'           => 'Yegeds suvüno perevidöls',
'prefixindex'             => 'Pads valik kö foyümot',
'shortpages'              => 'Pads brefik',
'longpages'               => 'Pads lunik',
'deadendpages'            => 'Pads nen yüms lü votiks',
'deadendpagestext'        => 'Pads sököl no labons yümis ad pads votik in vüki at.',
'protectedpages'          => 'Pads pejelöl',
'protectedpages-indef'    => 'Te jels nefümik',
'protectedpages-cascade'  => 'Te vatafalajels',
'protectedpagestext'      => 'Pads fovik pejelons e no kanons patöpätükön u paredakön',
'protectedpagesempty'     => 'Pads nonik pejelons',
'protectedtitles'         => 'Tiäds pejelöl',
'protectedtitlestext'     => 'Tiäds sököl no dalons pajafön:',
'protectedtitlesempty'    => 'Tiäds nonik pejelons me paramets at.',
'listusers'               => 'Gebanalised',
'listusers-editsonly'     => 'Jonön te gebanis keblünöl',
'usereditcount'           => '{{PLURAL:$1|redakam|redakams}} $1',
'usercreated'             => 'Pejafon tü $1 tü $2',
'newpages'                => 'Pads nulik',
'newpages-username'       => 'Gebananem:',
'ancientpages'            => 'Pads bäldikün',
'move'                    => 'Topätükön',
'movethispage'            => 'Topätükolöd padi at',
'unusedimagestext'        => 'Demolös, das bevüresodatopäds votik (samo Vükipeds votik) kanons yumön lü ragiv me ladet-URL nemedik. Sekü atos, ragiv at kanon binön su lised isik do nog pagebon.',
'unusedcategoriestext'    => 'Kladapads sököl dabinons do yeged u klad votik nonik gebon oni.',
'notargettitle'           => 'No dabinon zeilapad',
'notargettext'            => 'No evälol fonätapadi u fonätagebani, keli dun at otefon:',
'nopagetitle'             => 'Fonätapad no dabinon',
'nopagetext'              => 'Fonätapad fa ol pevälöl no dabinon.',
'pager-newer-n'           => '{{PLURAL:$1|nulikum 1|nulikum $1}}',
'pager-older-n'           => '{{PLURAL:$1|büikum 1|büikum $1}}',
'suppress'                => 'Lovelogam',

# Book sources
'booksources'               => 'Bukafons',
'booksources-search-legend' => 'Sukön bukafonis:',
'booksources-go'            => 'Getolöd',
'booksources-text'          => 'Is palisedons bevüresodatopäds votik, kels selons bukis nulik e pegebölis, e kels ba labons nünis pluik dö buks fa ol pasuköls:',
'booksources-invalid-isbn'  => 'El ISBN at jiniko no lonöfon; kontrololös pökis po kopiedam se rigafonät.',

# Special:Log
'specialloguserlabel'  => 'Geban:',
'speciallogtitlelabel' => 'Tiäd:',
'log'                  => 'Jenotaliseds',
'all-logs-page'        => 'Jenotaliseds valik',
'alllogstext'          => 'Kobojonam jenotalisedas gebidik valik in {{SITENAME}}.
Ad brefükam lisedi, kanol välön lisedasoti, gebananemi, u padi tefik.',
'logempty'             => 'No dabinons notets in jenotalised at.',
'log-title-wildcard'   => 'Sukön tiäds primöl me:',

# Special:AllPages
'allpages'          => 'Pads valik',
'alphaindexline'    => '$1 jü $2',
'nextpage'          => 'Pad sököl ($1)',
'prevpage'          => 'Pad büik ($1)',
'allpagesfrom'      => 'Jonolöd padis, primöl me:',
'allpagesto'        => 'Jonön padis jü:',
'allarticles'       => 'Yegeds valik',
'allinnamespace'    => 'Pads valik ($1 nemaspad)',
'allnotinnamespace' => 'Pads valik ($1 nemaspad)',
'allpagesprev'      => 'Büik',
'allpagesnext'      => 'Sököl',
'allpagessubmit'    => 'Jonolöd',
'allpagesprefix'    => 'Jonolöd padis labü foyümot:',
'allpagesbadtitle'  => 'Tiäd pegivöl no lonöfon, u ba labon foyümoti vüpükik u vü-vükik. Mögos i, das labon tonatis u malülis no pedälölis ad penön tiädis.',
'allpages-bad-ns'   => '{{SITENAME}} no labon nemaspadi: "$1".',

# Special:Categories
'categories'                    => 'Klads',
'categoriespagetext'            => 'Klads sököl labons padis u ragivis.
[[Special:UnusedCategories|Klads no pageböls]] no pajonons is.
Logolös i [[Special:WantedCategories|klads pevilöl]].',
'categoriesfrom'                => 'Jonön padis primölo de:',
'special-categories-sort-count' => 'leodükön ma num',
'special-categories-sort-abc'   => 'leodükön ma lafab',

# Special:DeletedContributions
'deletedcontributions'             => 'Gebanakeblünots pemoüköl',
'deletedcontributions-title'       => 'Gebanakeblünots pemoüköl',
'sp-deletedcontributions-contribs' => 'keblünots',

# Special:LinkSearch
'linksearch'       => 'Yüms plödik',
'linksearch-pat'   => 'Sukapated:',
'linksearch-ns'    => 'Nemaspad:',
'linksearch-ok'    => 'Suk',
'linksearch-text'  => 'WilStelüls kanons pagebön, a.s. „*.wikipedia.org“.<br />
Protoks pestütöl: <tt>$1</tt>',
'linksearch-line'  => '$1 labon yümi se $2',
'linksearch-error' => 'Stelüls kanons pubön te lä prim lotidiananema.',

# Special:ListUsers
'listusersfrom'      => 'Jonolöd gebanis primölo me:',
'listusers-submit'   => 'Jonolöd',
'listusers-noresult' => 'Geban nonik petuvon.',
'listusers-blocked'  => '(pebloköl)',

# Special:ActiveUsers
'activeusers-count'      => '{{PLURAL:$1|redakam|redakams}} $1 ün {{PLURAL:$3|del lätik|dels lätik $3}}',
'activeusers-hidebots'   => 'Klänedolöd elis bot',
'activeusers-hidesysops' => 'Klänedolöd guvanis',
'activeusers-noresult'   => 'Geban nonik petuvon.',

# Special:Log/newusers
'newuserlogpage'              => 'Lised gebanijafamas',
'newuserlogpagetext'          => 'Is palisedons jafams gebanas nulik.',
'newuserlog-byemail'          => 'letavöd pesedon me pot leäktronik',
'newuserlog-create-entry'     => 'Geban nulik',
'newuserlog-create2-entry'    => 'ejafon kali nulik: $1',
'newuserlog-autocreate-entry' => 'Kal itjäfidiko pejaföl',

# Special:ListGroupRights
'listgrouprights'                 => 'Gitäts gebanagrupa',
'listgrouprights-summary'         => 'Is palisedons gebanagrups in vük at dabinöls, sa gitäts tefik onsik.
Ba dabinons [[{{MediaWiki:Listgrouprights-helppage}}|nüns pluik]] tefü gebanagitäts patik.',
'listgrouprights-group'           => 'Grup',
'listgrouprights-rights'          => 'Gitäts',
'listgrouprights-helppage'        => 'Help:Grupagitäts',
'listgrouprights-members'         => '(lised limanas)',
'listgrouprights-addgroup'        => 'Kanon läükön {{PLURAL:$2|grupi|grupis}}: $1',
'listgrouprights-removegroup'     => 'Kanon moükön {{PLURAL:$2|grupi|grupis}}: $1',
'listgrouprights-addgroup-all'    => 'Kanon läükön grupis valik',
'listgrouprights-removegroup-all' => 'Kanon moükön grupis valik',

# E-mail user
'mailnologin'      => 'Ladet nonik ad sedön',
'mailnologintext'  => 'Mutol [[Special:UserLogin|nunädön oli]] e labön ladeti leäktronik lonöföl pö [[Special:Preferences|buükams olik]] ad dalön sedön poti leäktronik gebanes votik.',
'emailuser'        => 'Penön gebane at',
'emailpage'        => 'Penön gebane',
'emailpagetext'    => 'Kanol gebön fometi dono ad sedön penedi leäktronik gebane at. Ladet leäktronik in [[Special:Preferences|gebanabüukams olik]] opubon as fonät (el "De:") peneda, dat getan okanon gepenön ole.',
'usermailererror'  => 'Potayeg egesedon pöli:',
'defemailsubject'  => 'Ladet leäktronik ela {{SITENAME}}',
'noemailtitle'     => 'Ladet no dabinon',
'noemailtext'      => 'Geban at no egivon ladeti leäktronik lonöföl.',
'nowikiemailtitle' => 'Pot leäktronik no pedälon.',
'nowikiemailtext'  => 'Geban at no vilon getön penedis leäktronik gebanas votik.',
'email-legend'     => 'Sedön penedi gebane votik in {{SITENAME}}',
'emailfrom'        => 'De el:',
'emailto'          => 'Ele:',
'emailsubject'     => 'Yegäd:',
'emailmessage'     => 'Nun:',
'emailsend'        => 'Sedolöd',
'emailccme'        => 'Sedolöd obe kopiedi peneda obik.',
'emailccsubject'   => 'Kopied peneda olik ele $1: $2',
'emailsent'        => 'Pened pesedon',
'emailsenttext'    => 'Pened leäktronik ola pesedon.',
'emailuserfooter'  => 'Pened at pesedon fa geban: $1 gebane: $2 medü program: „sedön gebane penedi“ ela {{SITENAME}}.',

# Watchlist
'watchlist'            => 'Galädalised obik',
'mywatchlist'          => 'Galädalised obik',
'nowatchlist'          => 'Labol nosi in galädalised olik.',
'watchlistanontext'    => '$1 ad logön u redakön lienis galädaliseda olik',
'watchnologin'         => 'No enunädon oki',
'watchnologintext'     => 'Mutol [[Special:UserLogin|nunädön oli]] büä kanol votükön galädalisedi olik.',
'addedwatch'           => 'Peläüköl lä galädalised',
'addedwatchtext'       => "Pad: \"[[:\$1]]\" peläükon lä [[Special:Watchlist|galädalised]] olik.
Votükams fütürik pada at, äsi bespikapada onik, polisedons us, e pad popenon '''me tonats dagik'''  in [[Special:RecentChanges|lised votükamas nulik]] ad fasilükön tuvi ona.

If vilol poso moükön padi de galädalised olik, välolös lä on knopi: „negalädön“.",
'removedwatch'         => 'Pemoükon de galädalised',
'removedwatchtext'     => 'Pad: „[[:$1]]“ pemoükon se [[Special:Watchlist|galädalised olik]].',
'watch'                => 'Galädön',
'watchthispage'        => 'Galädolöd padi at',
'unwatch'              => 'Negalädön',
'unwatchthispage'      => 'No plu galädön',
'notanarticle'         => 'No binon pad ninädilabik',
'notvisiblerev'        => 'Fomam pemoükon',
'watchnochange'        => 'Nonik padas pagalädöl olik peredakon dü period löpo pejonöl.',
'watchlist-details'    => '{{PLURAL:$1|pad $1|pads $1}} su galädalised, plä bespikapads.',
'wlheader-enotif'      => '* Nunam medü pot leäktronik pemögükon.',
'wlheader-showupdated' => "* Pads pos visit lätik ola pevotüköls papenons '''me tonats bigik'''",
'watchmethod-recent'   => 'vestigam redakamas brefabüik padas galädaliseda',
'watchmethod-list'     => 'vestigam votükamas brefabüik padas galädaliseda',
'watchlistcontains'    => 'Galädalised olik labon {{PLURAL:$1|padi|padis}} $1.',
'iteminvalidname'      => "Fikul tefü el '$1': nem no lonöföl...",
'wlnote'               => "Is palisedons votükam{{PLURAL:$1| lätik|s lätik '''$1'''}} dü düp{{PLURAL:$2| lätik|s lätik '''$2'''}}.",
'wlshowlast'           => 'Jonolöd: düpis lätik $1, delis lätik $2, $3',
'watchlist-options'    => 'Paramets galädaliseda',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Papladon ini galädalised...',
'unwatching' => 'Pamoükon se galädalised...',

'enotif_mailer'                => 'Nunamasit ela {{SITENAME}}',
'enotif_reset'                 => 'Malön padis pevisitöl valik',
'enotif_newpagetext'           => 'Atos binon pad nulik.',
'enotif_impersonal_salutation' => 'Geban {{SITENAME}}-a',
'changed'                      => 'pevotüköl',
'created'                      => 'pejafon',
'enotif_subject'               => 'In {{SITENAME}}, pad: $PAGETITLE $CHANGEDORCREATED fa el $PAGEEDITOR',
'enotif_lastvisited'           => 'Logolös eli $1 ad tuvön lisedi votükamas valik pos visit lätik ola.',
'enotif_lastdiff'              => 'Logolös eli $1 ad tuvön votükami at.',
'enotif_anon_editor'           => 'geban nennemik: $1',
'enotif_body'                  => 'O $WATCHINGUSERNAME löfik!


Pad: $PAGETITLE in {{SITENAME}} $CHANGEDORCREATED tü $PAGEEDITDATE fa geban: $PAGEEDITOR; otuvol fomami anuik in $PAGETITLE_URL.

$NEWPAGE

Naböfodönuam redakana: $PAGESUMMARY $PAGEMINOREDIT

Kanol penön gebane:
pot leäktronik: $PAGEEDITOR_EMAIL
pad in vük: $PAGEEDITOR_WIKI

Votükams fütürik no ponunons ole if no ovisitol dönu padi at.
Kanol i geükön nunamastänis padas valik galädaliseda olik.

             Nunamasit flenöfik ela {{SITENAME}} olik

--
Ad votükön parametami galädaliseda olik, loglös
{{fullurl:{{#special:Watchlist}}/edit}}

Küpets e yuf pluik:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Moükolöd padi',
'confirm'                => 'Fümedolös',
'excontent'              => "ninäd äbinon: '$1'",
'excontentauthor'        => "ninäd äbinon: '$1' (e keblünan teik äbinon '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'          => "ninäd bü vagükam äbinon: '$1'",
'exblank'                => 'pad ävagon',
'delete-confirm'         => 'Moükön padi: "$1"',
'delete-legend'          => 'Moükön',
'historywarning'         => 'Nuned: pad, keli vilol moükön, labon jenotemi:',
'confirmdeletetext'      => 'Primikol ad moükön laidüpiko padi u magodi sa jenotem valik ona. Fümedolös, das desinol ad dunön atosi, das suemol sekis, e das dunol atosi bai [[{{MediaWiki:Policy-url}}]].',
'actioncomplete'         => 'Peledunon',
'deletedtext'            => 'Pad: "<nowiki>$1</nowiki>" pemoükon;
$2 jonon moükamis nulik.',
'deletedarticle'         => 'Pad: "[[$1]]" pemoükon',
'suppressedarticle'      => 'logov pada: „[[$1]]“ pevotükon',
'dellogpage'             => 'Jenotalised moükamas',
'dellogpagetext'         => 'Dono binon lised moükamas nulikün.',
'deletionlog'            => 'jenotalised moükamas',
'reverted'               => 'Pegeükon ad revid büik',
'deletecomment'          => 'Kod:',
'deleteotherreason'      => 'Kod votik:',
'deletereasonotherlist'  => 'Kod votik',
'deletereason-dropdown'  => '* Kods kösömik moükama
** Beg lautana
** Kopiedagitäts
** Vandalim',
'delete-edit-reasonlist' => 'Redakön kodis moükama',
'delete-toobig'          => 'Pad at labon redakamajenotemi lunik ({{PLURAL:$1|revid|revids}} plu $1).
Moükam padas somik pemiedükon ad vitön däropami pö {{SITENAME}}.',
'delete-warning-toobig'  => 'Pad at labon jenotemi lunik: {{PLURAL:$1|revid|revids}} plu $1.
Prudö! Moükam onik ba osäkädükon jäfidi nünodema: {{SITENAME}}.',

# Rollback
'rollback'         => 'Sädunön redakamis',
'rollback_short'   => 'Sädunön vali',
'rollbacklink'     => 'sädunön vali',
'rollbackfailed'   => 'Sädunam no eplöpon',
'cantrollback'     => 'Redakam no kanon pasädunön; keblünan lätik binon lautan teik pada at.',
'alreadyrolled'    => 'No eplöpos ad sädunön redakami lätik pada: [[:$1]] fa geban: [[User:$2|$2]] ([[User talk:$2|Bespikapad]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]); ek ya eredakon oni ud esädunon redakami ona.

Redakam lätik päjenükon fa geban: [[User:$3|$3]] ([[User talk:$3|Bespikapad]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'      => "Redakamaplän äbinon: „''$1''“.",
'revertpage'       => 'Redakams ela [[Special:Contributions/$2|$2]] ([[User talk:$2|Bespik]]) pegeükons ad fomam ma redakam lätik gebana: [[User:$1|$1]]',
'rollback-success' => 'Redakams gebana: $1 pesädunons; pad pevotükon ad fomam lätik fa geban: $2.',

# Edit tokens
'sessionfailure' => 'Jiniko ädabinon säkäd seimik pö nunädam olik.
Dun at no pelasumon ad vitön mögi, das votükams olik pogivulons gebane votik.
Välolös knopi: „Geikön“ e dönulodolös padi, de kel ekömol, e tän steifülolös nogna.',

# Protect
'protectlogpage'              => 'Jenotalised jelodamas',
'protectlogtext'              => 'Is palisedons pads pelökofärmüköl e pemaifüköls.
Logolös [[Special:ProtectedPages|lisedi padas pejelöl]], kö pajonons padijelams anu lonöföls.',
'protectedarticle'            => 'ejelon padi: „[[$1]]“',
'modifiedarticleprotection'   => 'evotükon jelanivodi pada: „[[$1]]“',
'unprotectedarticle'          => 'Pad: „[[$1]]“ pesäjelon.',
'movedarticleprotection'      => 'moved protection settings from „[[$2]]“ to „[[$1]]“',
'protect-title'               => 'lonon jelanivodi pada: „$1“',
'prot_1movedto2'              => '[[$1]] petopätükon lü [[$2]]',
'protect-legend'              => 'Fümedolös jeli',
'protectcomment'              => 'Kod:',
'protectexpiry'               => 'Dul:',
'protect_expiry_invalid'      => 'Dul no lonöfon.',
'protect_expiry_old'          => 'Dul ya epasetikon.',
'protect-text'                => "Kanol logön e votükön is jelanivodi pada: '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "No kanol votükön jelanivodi bi peblokol. Ekö! paramets anuik pada: '''$1''':",
'protect-locked-dblock'       => "Jelanivods no kanons pavotükön sekü lökofärmükam vüka at. Ekö! paramets anuik pada: '''$1''':",
'protect-locked-access'       => "Kal olik no labon däli ad votükön jelanivodi padas.
Ekö! parametem anuik pada: '''$1''':",
'protect-cascadeon'           => 'Pad at atimo pajelon bi duton lü {{PLURAL:$1|pad sököl, kel labon|pads sököl, kels labons}} jänajeli jäfidik. Kanol votükön jelanivodi pada at, ab atos no oflunon jänajeli.',
'protect-default'             => 'Dälön gebanes valik',
'protect-fallback'            => 'Däl: "$1" zesüdon',
'protect-level-autoconfirmed' => 'Blokön gebanis nulik e no peregistarölis',
'protect-level-sysop'         => 'Te guvans',
'protect-summary-cascade'     => 'as jän',
'protect-expiring'            => 'dul jü $1 (UTC)',
'protect-expiry-indefinite'   => 'nenfinik',
'protect-cascade'             => 'Jelön padis in pad at pekeninükölis (jänajelam)',
'protect-cantedit'            => 'No kanol votükön jelanivodi pada at bi no labol däli ad redakön oni.',
'protect-othertime'           => 'Tim votik:',
'protect-othertime-op'        => 'tim votik',
'protect-existing-expiry'     => 'Dul dabinöl: jü $3, tü $2',
'protect-otherreason'         => 'Kod votik/pluik:',
'protect-otherreason-op'      => 'Kod votik',
'protect-dropdown'            => '* Jelakods suvik
** Vandalim tuik
** Spam tuik
** Redakamakrigs tupöl
** Pad labü dakosäd tuik',
'protect-edit-reasonlist'     => 'Redakön jelakodis',
'protect-expiry-options'      => 'düp 1:1 hour,del 1:1 day,vig 1:1 week,vigs 2:2 weeks,mul 1:1 month,muls 3:3 months,muls 6:6 months,yel 1:1 year,laidüp:infinite',
'restriction-type'            => 'Däl:',
'restriction-level'           => 'Miedükamanivod:',
'minimum-size'                => 'Gretot smalikün',
'maximum-size'                => 'Gretot gretikün:',
'pagesize'                    => '(jöläts)',

# Restrictions (nouns)
'restriction-edit'   => 'Redakön',
'restriction-move'   => 'Topätükön',
'restriction-create' => 'Jafön',
'restriction-upload' => 'Löpükön',

# Restriction levels
'restriction-level-sysop'         => 'pejelon lölöfiko',
'restriction-level-autoconfirmed' => 'pejelon dilo',
'restriction-level-all'           => 'nivod alseimik',

# Undelete
'undelete'                     => 'Jonön padis pemoüköl',
'undeletepage'                 => 'Jonön e sädunön padimoükamis',
'undeletepagetitle'            => "'''Sökölos binädon me fomams pemoüköl pada: [[:$1]]'''.",
'viewdeletedpage'              => 'Jonön padis pemoüköl',
'undeletepagetext'             => '{{PLURAL:$1|Pad sököl pemoükon ab binon nog in registar: moükam ona|Pads sököl $1 pemoükons ab binons nog in registar: moükam onas}} kanon pasädunön.
Registar pavagükon periodiko.',
'undelete-fieldset-title'      => 'Nätükön revidis',
'undeleteextrahelp'            => "Ad sädunön moükami pada lölik, vagükolös bügilis valik e välolös me mugaparat knopi: '''''Sädunolöd moükami'''''.
Ad sädunön moükami no lölöfik, välolös me mugaparat bügilis revidas pavipöl, täno knopi: '''''Sädunolöd moükami'''''. Knop: '''''Vagükolöd vali''''' vagükön küpeti e bügilis valik.",
'undeleterevisions'            => '{{PLURAL:$1|revid 1 peregistaron|revids $1 peregistarons}}',
'undeletehistory'              => 'If osädunol moükami pada at, revids valik ogepubons in jenotem onik.
If pad nulik labü tiäd ot pejafon pos moükam at, revids pada rigik ogepubons in jenotem ona.',
'undeleterevdel'               => 'Sädunam moükama no poledunon if okodon moükami dila padafomama lätik.
Ön jenets at, nedol sävälön u säklänedön fomamis pemoüköl nulikün.',
'undeletehistorynoadmin'       => 'Yeged at pemoükon. Kod moükama pajonon dono, kobü pats gebanas, kels iredakons padi at büä pämoükon. Vödem redakamas pemoüköl at gebidon te guvanes.',
'undelete-revision'            => 'Fomam pada: $1 (dät: $4, tim: $5), pemoüköl fa geban: $3:',
'undeleterevision-missing'     => 'Fomam no lonöföl u no dabinöl.
Ba labol yümi dädik, u ba fomam pegepübon u pemoükon se registar.',
'undelete-nodiff'              => 'Fomams büik no petuvons.',
'undeletebtn'                  => 'Sädunön moükami',
'undeletelink'                 => 'logön/sädunön',
'undeleteviewlink'             => 'logön',
'undeletereset'                => 'Vagükolöd vali',
'undeleteinvert'               => 'Väli güükön',
'undeletecomment'              => 'Kod:',
'undeletedarticle'             => 'Moükam pada: "[[$1]]" pesädunon',
'undeletedrevisions'           => 'Moükam {{PLURAL:$1|revida 1 pesädunon|revidas $1 pesädunons}}',
'undeletedrevisions-files'     => 'Moükam {{PLURAL:$1|revida 1|revidas $1}} e {{PLURAL:$2|ragiva 1|ragivas $2}} pesädunons',
'undeletedfiles'               => 'Moükam {{PLURAL:$1|ragiva 1|ragivas $1}} pesädunon',
'cannotundelete'               => 'Sädunam moükama no eplöpon. Ba ek ya esädunon moükami at.',
'undeletedpage'                => "'''Moükam pada: $1 pesädunon'''

Logolös [[Special:Log/delete|lisedi moükamas]] if vilol kontrolön moükamis e sädunamis brefabüikis.",
'undelete-header'              => 'Logolös [[Special:Log/delete|jenotalisedi moükamas]] ad tuvön padis brefabüo pemoükölis.',
'undelete-search-box'          => 'Sukön padis pemoüköl',
'undelete-search-prefix'       => 'Jonön padis primölo me:',
'undelete-search-submit'       => 'Sukolöd',
'undelete-no-results'          => 'Pads leigöl nonik petuvons in registar moükamas.',
'undelete-filename-mismatch'   => 'No mögos ad moükön ragivirevidi tü $1: ragivanem no leigon',
'undelete-bad-store-key'       => 'No mögos ad moükön ragivirevidi tü $1: ragiv no ädabinon bü moükam.',
'undelete-cleanup-error'       => 'Pöl dü moükam ragiva no pageböla: "$1".',
'undelete-missing-filearchive' => 'No emögos ad sädunön moükami ragiva: $1 bi no binon in nünodem.
Moükam onik ba ya pesädunon.',
'undelete-error-short'         => 'Pöl dü sädunam moükama ragiva: $1',
'undelete-error-long'          => 'Pöls äjenons dü sädunam moükama ragiva:

$1',
'undelete-show-file-confirm'   => 'Vilol-li fümiko logön revidi pemoüköl ragiva: „<nowiki>$1</nowiki>“ dätü $2 tü $3?',
'undelete-show-file-submit'    => 'Si',

# Namespace form on various pages
'namespace'      => 'Nemaspad:',
'invert'         => 'Güükön väloti',
'blanknamespace' => '(Cifik)',

# Contributions
'contributions'       => 'Gebanakeblünots',
'contributions-title' => 'Gebanakeblünots pro $1',
'mycontris'           => 'Keblünots obik',
'contribsub2'         => 'Tefü $1 ($2)',
'nocontribs'          => 'Votükams nonik petuvons me paramets at.',
'uctop'               => '(lätik)',
'month'               => 'De mul (e büiks):',
'year'                => 'De yel (e büiks):',

'sp-contributions-newbies'       => 'Jonolöd te keblünotis kalas nulik',
'sp-contributions-newbies-sub'   => 'Tefü kals nulik',
'sp-contributions-newbies-title' => 'Gebanakeblünots pro kals nulik',
'sp-contributions-blocklog'      => 'Jenotalised blokamas',
'sp-contributions-deleted'       => 'gebanakeblünots pemoüköl',
'sp-contributions-talk'          => 'bespik',
'sp-contributions-userrights'    => 'guvam gebanagitätas',
'sp-contributions-search'        => 'Sukön keblünotis',
'sp-contributions-username'      => 'Ladet-IP u gebananem:',
'sp-contributions-submit'        => 'Suk',

# What links here
'whatlinkshere'            => 'Yüms isio',
'whatlinkshere-title'      => 'Pads ad "$1" yumöls',
'whatlinkshere-page'       => 'Pad:',
'linkshere'                => "Pads sököl payümons ko '''[[:$1]]''':",
'nolinkshere'              => "Pads nonik peyümons lü '''[[:$1]]'''.",
'nolinkshere-ns'           => "Pads nonik yumons lü pad: '''[[:$1]]''' in nemaspad pevälöl.",
'isredirect'               => 'lüodükömapad',
'istemplate'               => 'ninükam',
'isimage'                  => 'yüm magoda',
'whatlinkshere-prev'       => '{{PLURAL:$1|büik|büik $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|sököl|sököl $1}}',
'whatlinkshere-links'      => '← yüms',
'whatlinkshere-hideredirs' => '$1 lüodükömis',
'whatlinkshere-hidetrans'  => '$1 ninükodis',
'whatlinkshere-hidelinks'  => '$1 yümis',
'whatlinkshere-hideimages' => '$1 yümis magodas',
'whatlinkshere-filters'    => 'Suls',

# Block/unblock
'blockip'                         => 'Blokön gebani',
'blockip-title'                   => 'Blokön gebani',
'blockip-legend'                  => 'Blokön gebani',
'blockiptext'                     => 'Gebolös padi at ad blokön redakamagitäti gebananema u ladeta-IP semikas. Atos söton padunön teiko ad vitön vandalimi, e bai [[{{MediaWiki:Policy-url}}|dunalesets {{SITENAME}}]]. Penolös dono kodi patik pro blokam (a. s., mäniotolös padis pedobüköl).',
'ipadressorusername'              => 'Ladet-IP u gebananem',
'ipbexpiry'                       => 'Dü',
'ipbreason'                       => 'Kod:',
'ipbreasonotherlist'              => 'Kod votik',
'ipbreason-dropdown'              => '*Blokamakods suvik:
** Läükam nünas neverätik
** Moükam ninäda se pads
** Läükam yümas plödik tu mödikis (el „spam“)
** Penam vödas/vödemas nesiämik su pads
** Kondöt tu komipälik u dredüköl
** Geb dobik kalas mödik
** Gebananem no zepabik',
'ipbanononly'                     => 'Blokön te gebanis nen gebananem',
'ipbcreateaccount'                => 'Neletön kalijafi',
'ipbemailban'                     => 'Nemögükön gebane sedi pota leäktronik',
'ipbenableautoblock'              => 'Blokön itjäfidiko ladeti-IP lätik fa geban at pegeböli, äsi ladetis-IP fovik valik, yufü kels osteifülon ad redakön',
'ipbsubmit'                       => 'Blokön gebani at',
'ipbother'                        => 'Dul votik',
'ipboptions'                      => 'düps 2:2 hours,del 1:1 day,dels 3:3 days,vig 1:1 week,vigs 2:2 weeks,mul 1:1 month,muls 3:3 months,muls 6:6 months,yel 1:1 year,laidüp:infinite',
'ipbotheroption'                  => 'dul votik',
'ipbotherreason'                  => 'Kod(s) votik',
'ipbhidename'                     => 'Klänedön gebananemi se redakams e liseds',
'ipbwatchuser'                    => 'Galädon gebana- e bespikapadis gebana at',
'ipballowusertalk'                => 'Dälön gebane pebloköl ad redakön bespikapadi okik',
'ipb-change-block'                => 'Dönublokön gebani me paramets at',
'badipaddress'                    => 'Ladet-IP no lonöfon',
'blockipsuccesssub'               => 'Blokam eplöpon',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] peblokon.
<br />Logolös [[Special:IPBlockList|lisedi ladetas-IP pebloköl]] ad vestigön blokamis.',
'ipb-edit-dropdown'               => 'Redakön kodis blokama',
'ipb-unblock-addr'                => 'Säblokön eli $1',
'ipb-unblock'                     => 'Säblokön gebananemi u ladeti-IP',
'ipb-blocklist'                   => 'Logön blokamis dabinöl',
'ipb-blocklist-contribs'          => 'Keblünots gebana: $1',
'unblockip'                       => 'Säblokön gebani',
'unblockiptext'                   => 'Gebolös padi at ad gegivön redakamafägi gebane (u ladete-IP) büo pibloköle.',
'ipusubmit'                       => 'Säblokön ladeti at',
'unblocked'                       => '[[User:$1|$1]] pesäblokon',
'unblocked-id'                    => 'Blokam: $1 pesädunon',
'ipblocklist'                     => 'Ladets-IP e gebananems pebloköls',
'ipblocklist-legend'              => 'Tuvön gebani pebloköl',
'ipblocklist-username'            => 'Gebananem u ladet IP:',
'ipblocklist-sh-userblocks'       => 'kaliblokams $1',
'ipblocklist-sh-tempblocks'       => 'blokams nelaidüpik $1',
'ipblocklist-sh-addressblocks'    => 'blokams $1 tefü ladets-IP balatik',
'ipblocklist-submit'              => 'Suk',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|Blokam|Blokams}} votik',
'blocklistline'                   => '$1, $2 äblokon $3 ($4)',
'infiniteblock'                   => 'laidüpo',
'expiringblock'                   => 'dulon jü $1 düp $2',
'anononlyblock'                   => 'te nennemans',
'noautoblockblock'                => 'Blokam itjäfidik penemögukon',
'createaccountblock'              => 'kalijaf peblokon',
'emailblock'                      => 'ladet leäktronik peblokon',
'blocklist-nousertalk'            => 'no dalon redakön bespikapadi okik',
'ipblocklist-empty'               => 'Blokamalised vagon.',
'ipblocklist-no-results'          => 'Ladet-IP u gebananem peflagöl no peblokon.',
'blocklink'                       => 'blokön',
'unblocklink'                     => 'säblokön',
'change-blocklink'                => 'votükön blokami',
'contribslink'                    => 'keblünots',
'autoblocker'                     => 'Peblokon bi ladet-IP olik pegebon brefabüo fa geban: „[[User:$1|$1]]“. Kod blokama ela $1 binon: „$2“',
'blocklogpage'                    => 'Jenotalised blokamas',
'blocklogentry'                   => '"[[$1]]" peblokon dü: $2 $3',
'reblock-logentry'                => 'blokamaparamets gebana: [[$1]] pevotükons, pro dul: $2 (kod: $3)',
'blocklogtext'                    => 'Is binon lised gebanablokamas e gebanasäblokamas. Ladets-IP itjäfidiko pebloköls no pajonons. Logolös blokamis e xilis anu lonöfölis in [[Special:IPBlockList|lised IP-blokamas]].',
'unblocklogentry'                 => '$1 pesäblokon',
'block-log-flags-anononly'        => 'te gebans nennemik',
'block-log-flags-nocreate'        => 'kalijaf penemögükon',
'block-log-flags-noautoblock'     => 'blokam itjäfidik penemögükon',
'block-log-flags-noemail'         => 'ladet leäktronik peblokon',
'block-log-flags-nousertalk'      => 'no dalon redakön bespikapadi okik',
'block-log-flags-angry-autoblock' => 'blokam itjäfidik gudikum pemögükon',
'block-log-flags-hiddenname'      => 'gebananem peklänedon',
'range_block_disabled'            => 'Fäg guvana ad jafön ladetemis penemögükon.',
'ipb_expiry_invalid'              => 'Blokamadul no lonöfon.',
'ipb_expiry_temp'                 => 'Gebananemiblokams klänedik mutons binön laidüpiks.',
'ipb_already_blocked'             => '"$1" ya peblokon',
'ipb-needreblock'                 => '== Ya Peblokon ==
Geban: $1 ya peblokon. Vilol-li votükön parametis?',
'ipb-otherblocks-header'          => '{{PLURAL:$1|Blokam|Blokams}} votik',
'ipb_cant_unblock'                => 'Pöl: Bokamadientif: $1 no petuvon. Ba ya pesäblokon.',
'ipb_blocked_as_range'            => 'Pöl: ladet-IP $1 no peblokon stedöfiko e no kanon pasäblokön.
Peblokon ye as dil ladetema: $2, kel kanon pasäblokön.',
'ip_range_invalid'                => 'Ladetem-IP no lonöföl.',
'blockme'                         => 'Blokolöd obi',
'proxyblocker'                    => 'Bloköm pladulömas',
'proxyblocker-disabled'           => 'Dun at penemogükon.',
'proxyblockreason'                => 'Ladet-IP olik peblokon bi binon pladulöm maifik.
Kosikolös ko dünigevan bevüresodik u kaenastütans olik e nunolös ones sefasäkädi fefik at.',
'proxyblocksuccess'               => 'Peledunon.',
'sorbsreason'                     => 'Ladet-IP olik palisedon as pladulöm maifik pö el DNSBL fa {{SITENAME}} pageböl.',
'sorbs_create_account_reason'     => 'Ladet-IP olik palisedon as pladulöm maifik pö el DNSBL fa {{SITENAME}} pageböl.
No dalol jafön kali.',
'cant-block-while-blocked'        => 'No dalol blokön gebanis votik bi peblokol it.',

# Developer tools
'lockdb'              => 'Lökofärmükön nünodemi',
'unlockdb'            => 'Maifükön nünödemi',
'lockdbtext'          => 'Lökofärmükam nünodema onemogükon gebanes valik redakami padas, votükami buükamas, redakami galädalisedas e dinas votükovik votik in nünodem,
ven ufinükol vobi olik.',
'unlockdbtext'        => 'Maifükmam nünodema omögükon gebanes valik redakami padas, votükami buükamas, redakami galädalisedas e dinas votükovik votik in nünodem.
Fümükolös, begö! das vilol vo dunön atosi.',
'lockconfirm'         => 'Si! Vo vilob lökofärmükön nünodemi.',
'unlockconfirm'       => 'Si! Vo vilob maifükön nünodemi.',
'lockbtn'             => 'Lökofärmükön nünodemi',
'unlockbtn'           => 'Maifükön nünodemi',
'locknoconfirm'       => 'No evälol fümedabokili.',
'lockdbsuccesssub'    => 'Lökofärmükam nünodema eplöpon',
'unlockdbsuccesssub'  => 'Maifükam nünodema eplöpon',
'lockdbsuccesstext'   => 'Nünodem pelökofärmükon.<br />
No glömolös ad [[Special:UnlockDB|maifükön oni]] ven ufinükol vobi olik.',
'unlockdbsuccesstext' => 'Nünodem pemaifükon.',
'lockfilenotwritable' => 'Ragiv lökofärmükamas no votükovon. Ad lökofärmükön u maifükön nünodemi, ragiv at muton binön votükovik (dub dünanünöm).',
'databasenotlocked'   => 'Vük at no pefärmükon.',

# Move page
'move-page'                    => 'Topätükön padi: $1',
'move-page-legend'             => 'Topätükolöd padi',
'movepagetext'                 => "Me fomet at kanoy votükön padanemi, ottimo feapladölo jenotemi lölöfik ona disi nem nulik. Tiäd büik ovedon lüodüköm lü tiäd nulik. Yüms lü padatiäd büik no povotükons; kontrolös dabini lüodükömas telik u dädikas. Gididol ad garanön, das yüms blebons lüodükön lü pads, lü kels mutons lüodükön.

Küpälolös, das pad '''no''' potopätükon if ya dabinon pad labü tiäd nulik, bisä vagon u binon lüodüköm e no labon jenotemi. Atos sinifon, das, if pölol, nog kanol gepladön padi usio, kö äbinon büo, e das no kanol pladön padi nulik sui pad ya dabinöl.

<b>NUNED!</b>
Votükam at kanon binön mu staböfik ä no paspetöl pö pad pöpedik. Suemolös, begö! gudiko sekis duna at büä ofövol oni.",
'movepagetalktext'             => "Bespikapad tefik potopätükön itjäfidiko kobü pad at '''pläsif:'''
* bespikapad no vägik labü tiäd nulik ya dabinon, u
* vagükol anu bokili dono.

Ön jenets at, if vilol topätükön bespikapadi u balön oni e padi ya dabinöl, ol it omutol dunön osi.",
'movearticle'                  => 'Topätükolöd padi',
'movenologin'                  => 'No enunädon oki',
'movenologintext'              => 'Mutol binön geban peregistaröl e [[Special:UserLogin|nunädön oli]] ad topätükön padi.',
'movenotallowed'               => 'No dalol topätükön padis.',
'movenotallowedfile'           => 'No dalol topätükön ragivis.',
'cant-move-user-page'          => 'No dalol topäkütön gebanapadis (pläamü donapads).',
'cant-move-to-user-page'       => 'No dalol topätükön padi ad gebanapad (te ad gebanadonapad).',
'newtitle'                     => 'Lü tiäd nulik',
'move-watch'                   => 'Pladolöd padi at ini galädalised',
'movepagebtn'                  => 'Topätükolöd padi',
'pagemovedsub'                 => 'Topätükam eplöpon',
'movepage-moved'               => '\'\'\'"$1" petopätükon lü "$2"\'\'\'',
'movepage-moved-redirect'      => 'Lüodüköm pejafon.',
'movepage-moved-noredirect'    => 'Lüoküköm ye no pejafon.',
'articleexists'                => 'Pad labü nem at ya dabinon, u nem fa ol pevälöl no lonöfon.
Välolös nemi votik.',
'cantmove-titleprotected'      => 'No kanol topätükön padi bi jafam tiäda nulik at penemögükon.',
'talkexists'                   => "'''Pad it petopätükon benosekiko, ab bespikapad onik no petopätükon bi ya dabinon pad labü tiäd ona. Ol it balolös onis.'''",
'movedto'                      => 'petöpätükon lü',
'movetalk'                     => 'Topätükolöd bespikapadi tefik',
'move-subpages'                => 'Topätükön donapadis (jü $1)',
'move-talk-subpages'           => 'Topätükön donapadis (jü $1) bespikapada',
'movepage-page-exists'         => 'Pad: $1 ya dabinon; pad nulik no dalon palovepladön sui on itjäfidiko.',
'movepage-page-moved'          => 'Pad: $1 petopätükon lü $2.',
'movepage-page-unmoved'        => 'No eplöpos ad topätükön padi: $1 ad pad: $2.',
'movepage-max-pages'           => 'Maxumanüm {{PLURAL:$1|pada bal|pads $1}} petopätükon; pads pluik nonik potopätükons itjäfidiko.',
'1movedto2'                    => '[[$1]] petopätükon lü [[$2]]',
'1movedto2_redir'              => '[[$1]] petopätükon lü [[$2]] vegü lüodüköm',
'move-redirect-suppressed'     => 'lüodüköm no pejafon',
'movelogpage'                  => 'Jenotalised topätükamas',
'movelogpagetext'              => 'Is palisedons pads petopätüköl.',
'movesubpage'                  => '{{PLURAL:$1|Donapad|Donapads}}',
'movesubpagetext'              => 'Pad at labon {{PLURAL:$1|donapadi bal, kel pajonon|donapadis $1, kels pajonons}} dono.',
'movenosubpage'                => 'Pad at no labon donapadis.',
'movereason'                   => 'Kod:',
'revertmove'                   => 'sädunön',
'delete_and_move'              => 'Moükolöd e topätükolöd',
'delete_and_move_text'         => '==Moükam peflagon==

Yeged nulik "[[:$1]]" ya dabinon. Vilol-li moükön oni ad jafön spadi pro topätükam?',
'delete_and_move_confirm'      => 'Si! moükolöd padi',
'delete_and_move_reason'       => 'Pemoükon ad jafön spadi pro topätükam',
'selfmove'                     => 'Tiäds nulik e bäldik binons ots; pad no kanon patopätükön sui ok it.',
'immobile-source-namespace'    => 'Paditopätükön ini nemaspad: "$1" nemögon',
'immobile-target-namespace'    => 'Paditopätükam ini nemaspad: "$1" nemögon',
'immobile-target-namespace-iw' => 'Yüms vüvükik no lonöfons as zeil paditopätükama.',
'immobile-source-page'         => 'Pad at no binon topätükovik.',
'immobile-target-page'         => 'Topätükam ad tiäd at nemögon.',
'imagenocrossnamespace'        => 'Ragivs no kanons patopätükön ini nemaspad no pedisinöl pro ragivs',
'imagetypemismatch'            => 'Poyümot ragiva nulik no pöton pö sot onik',
'imageinvalidfilename'         => 'Zeilaragivanem no lonöfon',
'fix-double-redirects'         => 'Verätükön lüodükömis, kels dugons lü tiäd rigik',
'move-leave-redirect'          => 'Posbinükön as lüodüköm',

# Export
'export'            => 'Seveigön padis',
'exporttext'        => 'Kanol seveigön vödemi e redakajenotemi padi u pademi patädik gebölo eli XML. Kanons poso panüveigön ini vük votik medü el MediaWiki me Patikos:Nüveigön padi.

Ad seveigön padis, penolös tiädis in penamaspad dono, tiädi bal a kedet, e välolös, va vilol fomami anuik kobü fomams büik valik, ko kedets padajenotema, u te fomami anuik kobü nüns dö redakam lätikün.

Ön jenet lätik, kanol i gebön yümi, a.s.: [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] pro pad "[[{{MediaWiki:Mainpage}}]]".',
'exportcuronly'     => 'Ninükolöd te revidi anuik, no jenotemi valik',
'exportnohistory'   => "----
'''Noet:''' Seveig padajenotema lölik medü fomet at penemögükon ad gudükumön duinafägi.",
'export-submit'     => 'Seveigolöd',
'export-addcattext' => 'Läükön padis se klad:',
'export-addcat'     => 'Läükön',
'export-addnstext'  => 'Läükön padis se nemaspad:',
'export-addns'      => 'Läükön',
'export-download'   => 'Dakipön as ragiv',
'export-templates'  => 'Keninükön samafomotis',

# Namespace 8 related
'allmessages'               => 'Sitanuns',
'allmessagesname'           => 'Nem',
'allmessagesdefault'        => 'Vödem rigädik',
'allmessagescurrent'        => 'Vödem nuik',
'allmessagestext'           => 'Is binon lised sitanunas valik lonöföl in nemaspad: Sitanuns.
Gebolös [http://www.mediawiki.org/wiki/Localisation Topükami ela MediaWiki] ed el [http://translatewiki.net translatewiki.net] if vilol keblünön topükame valemik ela MediaWiki.',
'allmessagesnotsupportedDB' => "Pad at no kanon pagebön bi el '''\$wgUseDatabaseMessages''' penemögükon.",
'allmessages-filter-legend' => 'Sul',
'allmessages-filter-all'    => 'Valik',
'allmessages-language'      => 'Pük:',
'allmessages-filter-submit' => 'Golön',

# Thumbnails
'thumbnail-more'           => 'Gretükön',
'filemissing'              => 'Ragiv deföl',
'thumbnail_error'          => 'Pöl pö jafam magodila: $1',
'djvu_no_xml'              => 'No eplöpos ad tuvön eli XML pro ragiv fomätü DjVu',
'thumbnail_invalid_params' => 'Paramets magodila no lonöfons',
'thumbnail_dest_directory' => 'No emögos ad jafön zeilaragiviäri',

# Special:Import
'import'                     => 'Nüveigön padis',
'importinterwiki'            => 'Nüveigam vü vüks',
'import-interwiki-text'      => 'Levälolös vüki e padatiädi ad nüveigön.
Däts fomamas e nems redakanas pokipedons.
Nüveigs vüvükik valik pajonons su [[Special:Log/import|nüveigamalised]].',
'import-interwiki-source'    => 'Fonätavük/pad:',
'import-interwiki-history'   => 'Kopiedön fomamis valik jenotema pada at',
'import-interwiki-templates' => 'Keninükön samafomotis valik',
'import-interwiki-submit'    => 'Nüveigön',
'import-interwiki-namespace' => 'Ini nemaspad:',
'import-upload-filename'     => 'Ragivanem:',
'import-comment'             => 'Küpet:',
'importtext'                 => 'Seveigolös ragivi se fonätavük me [[Special:Export|stum seveiga]].
Dakipolös oni su nünöm olik e löpükolös oni isio.',
'importstart'                => 'Nüveigölo padis...',
'import-revision-count'      => '{{PLURAL:$1|fomam|fomams}} $1',
'importnopages'              => 'Pads nonik ad nüveigön.',
'importfailed'               => 'Nüveigam no eplöpon: <nowiki>$1</nowiki>',
'importunknownsource'        => 'Sot nüveigamafonäta nesevädon',
'importcantopen'             => 'No eplöpos ad maifükön ragivi nüveigabik',
'importbadinterwiki'         => 'Yüm vüvükik dädik',
'importnotext'               => 'Vödem vagik',
'importsuccess'              => 'Nüveigam efinikon!',
'importhistoryconflict'      => 'Dabinon konflit jenotemas (pad at ba ya pänüveigon balna ün paset)',
'importnosources'            => 'Nüveigafonäts vüvükik nonik pelevälons e löpükam stedöfik jenotemas penemögükon.',
'importnofile'               => 'Ragiv nüveigabik nonik pelöpükon.',
'importuploaderrorsize'      => 'Löpükam ragiva nüveigabik no eplöpon. Gretot ragiva pluon demü gretot gretikün pedälöl.',
'importuploaderrorpartial'   => 'Löpükam ragiva nüveigabik no eplöpon. Ragiv pelöpükon te dilo.',
'importuploaderrortemp'      => 'Löpükam ragiva nüveigabik no eplöpon. Ragiviär nelaidüpik nekomon.',
'import-parse-failure'       => 'Pöl pö nüveigam ela XML',
'import-noarticle'           => 'Pad nüveigabik nonik!',
'import-nonewrevisions'      => 'Fomams valik ya pinüveigons.',
'xml-error-string'           => '$1 pö lien: $2, kolum: $3 (jölat: $4): $5',
'import-upload'              => 'Löpükön nünodis-XML',
'import-token-mismatch'      => 'Redakamanünods peperons. Steifülolös dönu.',
'import-invalid-interwiki'   => 'Nüveigam se vük pavilöl no mögon.',

# Import log
'importlogpage'                    => 'Jenotalised nüveigamas',
'importlogpagetext'                => 'Nüveigam guverik padas labü redakamajenotem se vüks votik',
'import-logentry-upload'           => 'pad: [[$1]] penüveigon medü ragivilöpükam',
'import-logentry-upload-detail'    => '{{PLURAL:$1|fomam|fomams}} $1',
'import-logentry-interwiki'        => 'pevotavükükon: $1',
'import-logentry-interwiki-detail' => '{{PLURAL:$1|fomam|fomams}} $1 se $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Gebanapad olik',
'tooltip-pt-anonuserpage'         => 'Gebanapad ladeta-IP, me kel redakol',
'tooltip-pt-mytalk'               => 'Bespiks olik',
'tooltip-pt-anontalk'             => 'Bespik votükamas me ladet-IP at pejenükölas',
'tooltip-pt-preferences'          => 'Buükams obik',
'tooltip-pt-watchlist'            => 'Lised padas, kö galädol tefü votükams',
'tooltip-pt-mycontris'            => 'Lised keblünotas olik',
'tooltip-pt-login'                => 'Binos gudik, ab no bligik, ad nunädön oyi.',
'tooltip-pt-anonlogin'            => 'Binos gudik - ab no zesüdik - ad nunädön oli.',
'tooltip-pt-logout'               => 'Senunädön oki',
'tooltip-ca-talk'                 => 'Bespik dö ninädapad',
'tooltip-ca-edit'                 => 'Kanol redakön padi at. Gebolös, begö! büologedi bü dakip.',
'tooltip-ca-addsection'           => 'Primön dilädi nulik',
'tooltip-ca-viewsource'           => 'Pad at pejelon. Kanol logön fonätakoti onik.',
'tooltip-ca-history'              => 'Fomams büik pada at.',
'tooltip-ca-protect'              => 'Jelön padi at',
'tooltip-ca-delete'               => 'Moükön padi at',
'tooltip-ca-undelete'             => 'Gegetön redakamis pada at büä pämoükon',
'tooltip-ca-move'                 => 'Topätükön padi at',
'tooltip-ca-watch'                => 'Lüükolös padi at lü galädalised olik',
'tooltip-ca-unwatch'              => 'Moükön padi at se galädalised olik',
'tooltip-search'                  => 'Sukön in {{SITENAME}}',
'tooltip-search-go'               => 'Tuvön padi labü nem at if dabinon',
'tooltip-search-fulltext'         => 'Sukön vödemi at su pads',
'tooltip-p-logo'                  => 'Cifapad',
'tooltip-n-mainpage'              => 'Visitolös Cifapadi',
'tooltip-n-mainpage-description'  => 'Visitolös cifapadi',
'tooltip-n-portal'                => 'Tefü proyek, kio kanol-li dunön, kiplado tuvön dinis',
'tooltip-n-currentevents'         => 'Tuvön nünis valemik tefü jenots anuik',
'tooltip-n-recentchanges'         => 'Lised votükamas nulik in vüki.',
'tooltip-n-randompage'            => 'Lodön padi fädik',
'tooltip-n-help'                  => 'Is kanoy tuvön yufi e nünis.',
'tooltip-t-whatlinkshere'         => 'Lised padas valik, kels yumons isio',
'tooltip-t-recentchangeslinked'   => 'Votükams nulik padas, lü kels pad at yumon',
'tooltip-feed-rss'                => 'Kanad (RSS) pro pad at',
'tooltip-feed-atom'               => 'Kanad (Toum) pro pad at',
'tooltip-t-contributions'         => 'Logön keblünotalisedi gebana at',
'tooltip-t-emailuser'             => 'Sedolös penedi gebane at',
'tooltip-t-upload'                => 'Löpükön ragivis',
'tooltip-t-specialpages'          => 'Lised padas patik valik',
'tooltip-t-print'                 => 'Fomam dabükovik pada at',
'tooltip-t-permalink'             => 'Yüm laidüpik lü padafomam at',
'tooltip-ca-nstab-main'           => 'Logön ninädapadi',
'tooltip-ca-nstab-user'           => 'Logön gebanapadi',
'tooltip-ca-nstab-media'          => 'Logön ragivapadi',
'tooltip-ca-nstab-special'        => 'Atos binon pad patik, no kanol redakön oni',
'tooltip-ca-nstab-project'        => 'Logön proyegapadi',
'tooltip-ca-nstab-image'          => 'Logön padi ragiva',
'tooltip-ca-nstab-mediawiki'      => 'Logön sitanuni',
'tooltip-ca-nstab-template'       => 'Logön samafomoti',
'tooltip-ca-nstab-help'           => 'Logön yufapadi',
'tooltip-ca-nstab-category'       => 'Logön kladapadi',
'tooltip-minoredit'               => 'Nemön atosi votükami pülik',
'tooltip-save'                    => 'Dakipolös votükamis olik',
'tooltip-preview'                 => 'Büologed votükamas olik. Gebolös bü dakip, begö!',
'tooltip-diff'                    => 'Jonön votükamis olik in vödem at.',
'tooltip-compareselectedversions' => 'Logön difis vü fomams pevälöl tel pada at.',
'tooltip-watch'                   => 'Lüükön padi at galädalisede olik',
'tooltip-recreate'                => 'Dönujafön padi do ya balna emoükon',
'tooltip-upload'                  => 'Primön löpükami.',
'tooltip-rollback'                => '„Sädunön vali“ sädunon redakami(s) pada at fa keblünan lätik me klik bal mugaparata.',
'tooltip-undo'                    => '"Sädunön bali" sädunon redakami at e maifükön redakamafometi as büologed.
Dälon läükami koda.',

# Stylesheets
'common.css'   => '/** El CSS isio peplädöl pogebon pro padafomäts valik */',
'monobook.css' => '/* El CSS isio pepladöl otefon gebanis padafomäta: Monobook */',

# Scripts
'common.js' => '/* El JavaScript isik alseimik pogebon pro gebans valik pö padilogam valik. */',

# Metadata
'notacceptable' => 'Dünanünömi vüka no fägon ad blünön nünodis ma fomät, keli nünöm olik kanon reidön.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Geban|Gebans}} nennemik ela {{SITENAME}}',
'siteuser'         => 'Geban ela {{SITENAME}}: $1',
'lastmodifiedatby' => 'Pad at pävotükon lätiküno tü dÜp $1, ün $2, fa el $3.',
'othercontribs'    => 'Stabü vob gebana: $1.',
'others'           => 'votiks',
'siteusers'        => '{{PLURAL:$2|Geban|Gebans}} ela {{SITENAME}}: $1',
'creditspage'      => 'Padanüns',
'nocredits'        => 'Nüns padi at teföls no gebidons.',

# Spam protection
'spamprotectiontitle' => 'Jelasul ta spam',
'spamprotectiontext'  => 'Pad, keli vilol dakipön, peblokon fa spamisul.
Pad luveratiko ninädon yümi lü bevüresodatopäd plödik in blägalised.',
'spamprotectionmatch' => 'Vödem sököl ekodon blokami fa spamisul: $1',
'spambot_username'    => 'Spamiklinükam ela MediaWiki',
'spam_reverting'      => 'Geükön ad fomam lätik, kel no älabon yümis lü $1',
'spam_blanking'       => 'Moükam revidas valik (bi ninädons yüms lü $1)',

# Info page
'infosubtitle'   => 'Nüns tefü pad',
'numedits'       => 'Redakamanum (pad): $1',
'numtalkedits'   => 'Redakamanum (bespikapad): $1',
'numwatchers'    => 'Num galädanas: $1',
'numauthors'     => 'Num lautanas distik (pad): $1',
'numtalkauthors' => 'Num lautanas distik (bespikapad): $1',

# Math options
'mw_math_png'    => 'Ai el PNG',
'mw_math_simple' => 'El HTML if go balugik, voto eli PNG',
'mw_math_html'   => 'El HTML if mögos, voto eli PNG',
'mw_math_source' => 'Dakipolöd oni as TeX (pro bevüresodatävöms fomätü vödem)',
'mw_math_modern' => 'Pakomandöl pro bevüresodatävöms nulädik',
'mw_math_mathml' => 'El MathML if mögos (nog sperimänt)',

# Math errors
'math_failure'          => 'Diletam fomüla no eplöpon',
'math_unknown_error'    => 'pök nesevädik',
'math_unknown_function' => 'dun nesevädik',
'math_lexing_error'     => 'vödidiletam no eplöpon',
'math_syntax_error'     => 'süntagapöl',
'math_image_error'      => 'Feajafam ela PNG no eplöpon;
vestigolös stitami verätik ela latex, ela dvips, ela gs, e feajafön',
'math_bad_tmpdir'       => 'No mögos ad penön ini / jafön ragiviär(i) matematik nelaidüpik.',
'math_bad_output'       => 'No mögos ad penön ini / jafön ragiviär(i) matematik labü seks',
'math_notexvc'          => 'Program-texvc ledunovik no petuvon;
logolös eli math/README ad givulön parametemi.',

# Patrolling
'markaspatrolleddiff'                 => 'Zepön',
'markaspatrolledtext'                 => 'Zepön padi at',
'markedaspatrolled'                   => 'Pezepon',
'markedaspatrolledtext'               => 'Fomam pevälöl pezepon.',
'rcpatroldisabled'                    => 'Patrul Votükamas Nulik penegebidükon',
'rcpatroldisabledtext'                => 'Patrul Votükamas Nulik binon anu negebidik.',
'markedaspatrollederror'              => 'No kanon pezepön',
'markedaspatrollederrortext'          => 'Nedol välön fomami ad pazepön.',
'markedaspatrollederror-noautopatrol' => 'No dalol zepön votükamis lönik ola.',

# Patrol log
'patrol-log-page'      => 'Jenotalised zepamas',
'patrol-log-header'    => 'Is lisedons revids pezepöl.',
'patrol-log-line'      => 'Fomam: $1 pada: $2 pezepon $3',
'patrol-log-auto'      => '(itjäfidik)',
'patrol-log-diff'      => 'fomami: $1',
'log-show-hide-patrol' => 'Jenotalised Zepamas: $1',

# Image deletion
'deletedrevision'                 => 'Fomam büik: $1 pemoükon.',
'filedeleteerror-short'           => 'Pöl pö moükam ragiva: $1',
'filedeleteerror-long'            => 'Pöls petuvons dü moükam ragiva:

$1',
'filedelete-missing'              => 'Ragiv: "$1" no kanon pamoükön bi no dabinon.',
'filedelete-old-unregistered'     => 'Ragivafomam: "$1" no binon in nünodem.',
'filedelete-current-unregistered' => 'Ragiv: "$1" no binon in nünodem.',
'filedelete-archive-read-only'    => 'Ragiviär: "$1" no kanon papenön fa dünanünöm bevuresodik.',

# Browsing diffs
'previousdiff' => '← Dif vönädikum',
'nextdiff'     => 'Dif nulikum →',

# Media information
'mediawarning'         => "'''Nuned''': Ragiv at ba ninükon programi(s) badälik.
If ojäfidükol oni, nünömasit olik ba podämükon.",
'imagemaxsize'         => 'Miedükön magodis su pads magodis bepenöls ad:',
'thumbsize'            => 'Gretot magodüla:',
'widthheightpage'      => '$1×$2, {{PLURAL:$3|pad|pads}} $3',
'file-info'            => 'ragivagretot: $1, MIME-pated: $2',
'file-info-size'       => '$1 × $2 pixel, ragivagret: $3, pated MIME: $4',
'file-nohires'         => '<small>Gretot gudikum no pagebidon.</small>',
'svg-long-desc'        => 'ragiv in fomät: SVG, magodaziöbs $1 × $2, gretot: $3',
'show-big-image'       => 'Gretot gudikün',
'show-big-image-thumb' => '<small>Gretot büologeda at: magodaziöbs $1 × $2</small>',

# Special:NewFiles
'newimages'             => 'Pänotem ragivas nulik',
'imagelisttext'         => "Dono binon lised '''$1''' {{PLURAL:$1|ragiva|ragivas}} $2 pedilädölas.",
'newimages-summary'     => 'Pad patik at lisedon ragivis pelöpüköl lätik.',
'newimages-legend'      => 'Sul',
'newimages-label'       => 'Ragivanem (u dil ona):',
'showhidebots'          => '($1 mäikamenis)',
'noimages'              => 'Nos ad logön.',
'ilsubmit'              => 'Sukolöd',
'bydate'                => 'ma dät',
'sp-newimages-showfrom' => 'Jonolöd ragivis nulik, primölo tü düp $2, $1',

# Bad image list
'bad_image_list' => 'Fomät pabevobon ön mod soik:

Te lisedaliens (liens me * primöl) pabevobons. Yüm balid liena muton binön yüm ad magod badik. Yüms votik valik su lien ot palelogons as pläams, a.s. pads, in kelas vödems magod dalon pagebön.',

# Metadata
'metadata'          => 'Ragivanüns',
'metadata-help'     => 'Ragiv at keninükon nünis pluik, luveratiko se käm u numatüköm me kel päjafon. If ragiv at ya pevotükon e no plu leigon ko rigädastad okik, mögos, das pats anik is palisedöls no plu bepenons ragivi in stad anuik.',
'metadata-expand'   => 'Jonön patis pluik',
'metadata-collapse' => 'Klänedön patis pluik',
'metadata-fields'   => 'Nünabinets fomäta: EXIF is palisedöls pojonons su bespikapad magoda ifi nünataib pufärmükon. Nünabinets votik poklänedons.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Vidot',
'exif-imagelength'                 => 'Geilot',
'exif-bitspersample'               => 'Jölätabinets a köl',
'exif-compression'                 => 'Skemat kobopedama',
'exif-photometricinterpretation'   => 'Pixelabinädükam',
'exif-orientation'                 => 'Kämilüodükam',
'exif-samplesperpixel'             => 'Num kölas',
'exif-planarconfiguration'         => 'Leodükam nünodas',
'exif-ycbcrpositioning'            => 'staned Y e C',
'exif-xresolution'                 => 'Distidafäg horitätik',
'exif-yresolution'                 => 'Distidafäg penditik',
'exif-resolutionunit'              => 'Stabäd distidafäga X e Y',
'exif-stripoffsets'                => 'Topam magodanünodas',
'exif-rowsperstrip'                => 'Num kedetas a strip',
'exif-stripbytecounts'             => 'Jöläts a strip pekobopedöl',
'exif-jpeginterchangeformat'       => 'Topätükön ad JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Jöläts nünodas: JPEG',
'exif-transferfunction'            => 'Lovepladamasekät',
'exif-whitepoint'                  => 'Kölöf püna vietik',
'exif-primarychromaticities'       => 'Kölöf stabakölas',
'exif-ycbcrcoefficients'           => 'Koäfs votükamataiba kölaspada',
'exif-referenceblackwhite'         => 'Pär stabavöladas (vietik/blägik)',
'exif-datetime'                    => 'Dät e tim votükama ragiva',
'exif-imagedescription'            => 'Tiäd magoda',
'exif-make'                        => 'Fabrikan aparata',
'exif-model'                       => 'Aparatasot',
'exif-software'                    => 'Nünömaprogram pegeböl',
'exif-artist'                      => 'Lautan',
'exif-copyright'                   => 'Dalaban kopiedagitäta',
'exif-exifversion'                 => 'Fomam-Exif',
'exif-colorspace'                  => 'Kölaspad',
'exif-componentsconfiguration'     => 'Sinif komponena alik',
'exif-compressedbitsperpixel'      => 'Mod kobopedama magoda',
'exif-pixelydimension'             => 'Magodavidot lonöföl',
'exif-pixelxdimension'             => 'Magodageilot lonöföl',
'exif-makernote'                   => 'Penets fabrikana',
'exif-usercomment'                 => 'Küpets gebana',
'exif-relatedsoundfile'            => 'Tonaragiv tefik',
'exif-datetimeoriginal'            => 'Dät e tim jafama nünodas',
'exif-datetimedigitized'           => 'Dät e tim numatükama',
'exif-subsectime'                  => 'Dät e tim (1/100 s)',
'exif-subsectimeoriginal'          => 'Dät e tim rigiks (1/100 s)',
'exif-subsectimedigitized'         => 'Dät e tim numeriks (1/100 s)',
'exif-exposuretime'                => 'Jonamadul',
'exif-exposuretime-format'         => '$1 sek ($2)',
'exif-fnumber'                     => 'Num-F',
'exif-exposureprogram'             => 'Jonamaprogram',
'exif-spectralsensitivity'         => 'Senöfik späktrumik',
'exif-isospeedratings'             => 'Senöf (ISO)',
'exif-shutterspeedvalue'           => 'Färmikamavifot',
'exif-aperturevalue'               => 'Maifamagrad',
'exif-brightnessvalue'             => 'Litöf',
'exif-exposurebiasvalue'           => 'Gudükumam jonama',
'exif-maxaperturevalue'            => 'Maifikam maxumik',
'exif-subjectdistance'             => 'Fagot zeila',
'exif-meteringmode'                => 'Mafamamod',
'exif-lightsource'                 => 'Litafonät',
'exif-flash'                       => 'Kämalelit',
'exif-focallength'                 => 'Foukafagot',
'exif-subjectarea'                 => 'Portät',
'exif-flashenergy'                 => 'Nämet kämalelita',
'exif-spatialfrequencyresponse'    => 'Spadasuvöf',
'exif-focalplanexresolution'       => 'Distidafäg-X foukaplena',
'exif-focalplaneyresolution'       => 'Distidafäg-Y foukaplena',
'exif-focalplaneresolutionunit'    => 'Distidafägastabäd foukaplena',
'exif-subjectlocation'             => 'Staned zeila',
'exif-exposureindex'               => 'Mafädanum litükama',
'exif-sensingmethod'               => 'Senametod',
'exif-filesource'                  => 'Fonät ragiva',
'exif-scenetype'                   => 'Sot süfüla',
'exif-cfapattern'                  => 'Pated-CFA',
'exif-customrendered'              => 'Magodibevobam pelönedüköl',
'exif-exposuremode'                => 'Litükamamod',
'exif-whitebalance'                => 'Vietaleigavet',
'exif-digitalzoomratio'            => 'Gretükamapropor numerik',
'exif-focallengthin35mmfilm'       => 'Foukafagot pro films milmetas 35',
'exif-scenecapturetype'            => 'Sot süfülilasumama',
'exif-gaincontrol'                 => 'Litakontrol',
'exif-contrast'                    => 'Taädam',
'exif-saturation'                  => 'Satükam',
'exif-sharpness'                   => 'Magodakurat',
'exif-devicesettingdescription'    => 'Bepenam parametema aparata',
'exif-subjectdistancerange'        => 'Zeilafagot',
'exif-imageuniqueid'               => 'Magodadientifäd balik',
'exif-gpslatituderef'              => 'Videt Nolüdik u Sulüdik',
'exif-gpslatitude'                 => 'Videt',
'exif-gpslongituderef'             => 'Lunet Lofüdik u Vesüdik',
'exif-gpslongitude'                => 'Lunet',
'exif-gpsaltituderef'              => 'Geilotastab',
'exif-gpsaltitude'                 => 'Geilot',
'exif-gpstimestamp'                => 'tim-GPS (glok taumik)',
'exif-gpssatellites'               => 'Muneds pö mafam pegeböls',
'exif-gpsstatus'                   => 'Getanastad',
'exif-gpsmeasuremode'              => 'Mafamametod',
'exif-gpsdop'                      => 'Kurat mafama',
'exif-gpsspeedref'                 => 'Vifotastabäd',
'exif-gpsspeed'                    => 'Vifot GPS-getiana',
'exif-gpstrackref'                 => 'Stab pro mufalüod',
'exif-gpstrack'                    => 'Mufalüod',
'exif-gpsimgdirectionref'          => 'Stab pro magodalüod',
'exif-gpsimgdirection'             => 'Lüod magoda',
'exif-gpsdestlatituderef'          => 'Stab videta zeila',
'exif-gpsdestlatitude'             => 'Zeilavidet',
'exif-gpsdestlongituderef'         => 'Stab luneta zeila',
'exif-gpsdestlongitude'            => 'Zeilalunet',
'exif-gpsdestbearingref'           => 'Stab lüodükama zeila',
'exif-gpsdestbearing'              => 'Zeilalüod',
'exif-gpsdestdistanceref'          => 'Stab fagota zeila',
'exif-gpsdestdistance'             => 'Fagot jü lükömöp',
'exif-gpsprocessingmethod'         => 'Nem dunamoda-GPS',
'exif-gpsareainformation'          => 'Nem topäda: GPS',
'exif-gpsdatestamp'                => 'Dät ela GPS',

# EXIF attributes
'exif-compression-1' => 'No pekobopedöl',

'exif-unknowndate' => 'Dät nesevädik',

'exif-orientation-1' => 'Nomik',
'exif-orientation-2' => 'Petülöl horitäto',
'exif-orientation-3' => 'Mö 180° pefleköl',
'exif-orientation-4' => 'Petülöl pendito',

'exif-planarconfiguration-1' => 'fomät grobik',

'exif-componentsconfiguration-0' => 'no dabinon',

'exif-exposureprogram-0' => 'No pemiedetöl',
'exif-exposureprogram-1' => 'Gebü nams',
'exif-exposureprogram-2' => 'Program nomöfik',
'exif-exposureprogram-5' => 'Program buüköl feladibi',
'exif-exposureprogram-6' => 'Program buüköl färmikami vifikum',
'exif-exposureprogram-7' => 'Pöträtaprogram (pro fotografam nilao, pödaglun no kuratik)',
'exif-exposureprogram-8' => 'Länodaprogram (pro länodifotografam, pödaglun kuratik)',

'exif-subjectdistance-value' => 'Mets $1',

'exif-meteringmode-0'   => 'Nesevädik',
'exif-meteringmode-1'   => 'Zäned',
'exif-meteringmode-3'   => 'Pünamafam',
'exif-meteringmode-4'   => 'Mödapünamafam',
'exif-meteringmode-5'   => 'Pated',
'exif-meteringmode-6'   => 'Dilik',
'exif-meteringmode-255' => 'Votik',

'exif-lightsource-0'   => 'Nesevädik',
'exif-lightsource-1'   => 'Delalit',
'exif-lightsource-4'   => 'Kämalelit',
'exif-lightsource-9'   => 'Stom gudik',
'exif-lightsource-10'  => 'Stom lefogagik',
'exif-lightsource-11'  => 'Jad',
'exif-lightsource-17'  => 'Stabalit A',
'exif-lightsource-18'  => 'Stabalit B',
'exif-lightsource-19'  => 'Stabalit C',
'exif-lightsource-255' => 'Litafonät votik',

# Flash modes
'exif-flash-fired-0'    => 'Kämalelit no pegebon',
'exif-flash-fired-1'    => 'Kämalelit pegebon',
'exif-flash-mode-1'     => 'Kämalelitigeb bligik',
'exif-flash-mode-2'     => 'Kämalelitinegeb bligik',
'exif-flash-mode-3'     => 'stad itjäfidik',
'exif-flash-function-1' => 'Kämalelit no dabinon',
'exif-flash-redeye-1'   => 'läsükam redaloga',

'exif-focalplaneresolutionunit-2' => 'puids',

'exif-sensingmethod-1' => 'No pemiedetöl',
'exif-sensingmethod-5' => 'Kölisenian mastripik sürfatik',
'exif-sensingmethod-7' => 'Senian killienöfik',
'exif-sensingmethod-8' => 'Kölisenian lienöfik mastripik',

'exif-scenetype-1' => 'Magod pefotograföl nemediko',

'exif-customrendered-0' => 'Kösömik',
'exif-customrendered-1' => 'Fa geban pelönedüköl',

'exif-exposuremode-0' => 'Jonam itjäfidik',
'exif-exposuremode-1' => 'Jonam gebü nams',
'exif-exposuremode-2' => 'Kläm itjäfidik',

'exif-whitebalance-0' => 'Vietaleigavet itjäfidik',
'exif-whitebalance-1' => 'Vietaleigavet gebü nams',

'exif-scenecapturetype-0' => 'Kösömik',
'exif-scenecapturetype-1' => 'Länod',
'exif-scenecapturetype-2' => 'Pöträt',
'exif-scenecapturetype-3' => 'Ün neit',

'exif-gaincontrol-0' => 'Nonik',

'exif-contrast-0' => 'Nomik',
'exif-contrast-1' => 'Fiböfik',
'exif-contrast-2' => 'Nämöfik',

'exif-saturation-0' => 'Nomik',
'exif-saturation-1' => 'Satükam fiböfik',
'exif-saturation-2' => 'Satükam nämöfik',

'exif-sharpness-0' => 'Nomik',
'exif-sharpness-1' => 'Pülik',
'exif-sharpness-2' => 'Nämöfik',

'exif-subjectdistancerange-0' => 'Nesevädik',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Loged nilik',
'exif-subjectdistancerange-3' => 'Loged fägik',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Videt nolüdik',
'exif-gpslatitude-s' => 'Videt  Sulüdik',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'lunet lofüdik',
'exif-gpslongitude-w' => 'lunet vesüdik',

'exif-gpsstatus-a' => 'Mafam padunon',

'exif-gpsmeasuremode-2' => 'mafam 2-mafotik',
'exif-gpsmeasuremode-3' => 'mafam 3-mafotik',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Milmets a düp',
'exif-gpsspeed-m' => 'Liöls a düp',
'exif-gpsspeed-n' => 'Snobs',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Lüod veratik',
'exif-gpsdirection-m' => 'Lüod magnetik',

# External editor support
'edit-externally'      => 'Votükön ragivi at me nünömaprogram plödik',
'edit-externally-help' => '(Reidolös eli [http://www.mediawiki.org/wiki/Manual:External_editors setup instructions] [in Linglänapük] ad tuvön nünis pluik)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'valik',
'imagelistall'     => 'valik',
'watchlistall2'    => 'valikis',
'namespacesall'    => 'valik',
'monthsall'        => 'valik',
'limitall'         => 'valikis',

# E-mail address confirmation
'confirmemail'             => 'Fümedolös ladeti leäktronik',
'confirmemail_noemail'     => 'No labol ladeti leäktronik lonöföl in [[Special:Preferences|gebanabuükams olik]].',
'confirmemail_text'        => 'Vük at flagon, das ofümedol ladeti leäktronik ola büä odälon ole ad gebön poti leäktronik.
Välolös me mugaparat knopi dono ad sedön fümedapenedi ladete olik. Pened oninädon yümi labü fümedakot; sökolös yümi ad fümedön, das ladet olik lonöfon.',
'confirmemail_pending'     => 'Fümedakot ya pesedon ole medü pot leäktronik; if äjafol kali olik brefabüo, stebedolös dü minuts anik, dat fümedakot olükömon, büä osteifülol ad begön koti nulik.',
'confirmemail_send'        => 'Sedön fümedakoti me pot leäktronik',
'confirmemail_sent'        => 'Fümedapened pesedon.',
'confirmemail_oncreate'    => 'Fümedakot pesedon lü ladet leäktronik ola. Kot at no zesüdon ad nunädön oli, ab omutol klavön oni büä okanol gebön ladeti leäktronik ola in vük at.',
'confirmemail_sendfailed'  => '{{SUTENAME}} no eplöpon ad sedön fümedapenedi. Ba ädabinons malats no lonöföls in ladet leäktronik ola.

Potanünöm egesedon: $1',
'confirmemail_invalid'     => 'Fümedakot no lonöfon. Jiniko binon tu bäldik.',
'confirmemail_needlogin'   => 'Nedol $1 ad fümedön ladeti leäktronik ola.',
'confirmemail_success'     => 'Ladet leäktronik ola pefümedon. Nu kanol nunädön oli e juitön vüki at.',
'confirmemail_loggedin'    => 'Ladeti leäktronik ola nu pefümedon.',
'confirmemail_error'       => 'Bos no eplöpon pö registaram fümedama olik.',
'confirmemail_subject'     => 'Fümedam ladeta leäktronik pro: {{SITENAME}}',
'confirmemail_body'        => 'Ek, bo ol, se ladet-IP: $1, ejafon kali: „$2‟ me ladeti leäktronik at lä {{SITENAME}}.

Ad fümedön, das kal at binon jenöfiko olik, ed ad dalön gebön
poti leäktronik in {{SITENAME}}, sökolös yümi fovik me bevüresodatävöm olik:

$3

If *no* binol utan, kel ejafon kali, sökolös yümi fovik ad sädunön fümedami leäktronik:

$5

Fümedakot at operon lonöfi okik ün $4.',
'confirmemail_invalidated' => 'Fümedam ladeta leäktronik penegebidükon',
'invalidateemail'          => 'Negebidükon fümedami ladeta leäktronik',

# Scary transclusion
'scarytranscludefailed'  => '[Tuv samafomota no eplopön kodü $1]',
'scarytranscludetoolong' => '[el URL binon tu lunik]',

# Trackbacks
'trackbackbox'      => 'Gevegs padi at teföls:<br />
$1',
'trackbackremove'   => '([$1 Moükön])',
'trackbacklink'     => 'Geveg',
'trackbackdeleteok' => 'Geveg pemoükon benosekiko.',

# Delete conflict
'deletedwhileediting' => "'''Nuned''': Pad at pemoükon posä äprimol ad redakön oni!",
'confirmrecreate'     => "Geban: [[User:$1|$1]] ([[User talk:$1|talk]]) ämoükon padi at posä äprimol ad redakön oni sekü kod sököl:
: ''$2''
Fümedolös, das jenöfo vilol dönujafön padi at.",
'recreate'            => 'Dönujafön',

# action=purge
'confirm_purge_button' => 'Si!',
'confirm-purge-top'    => 'Vagükön eli caché pada at?',
'confirm-purge-bottom' => 'Vagükam mema nelaidüpik pada müton fomami nulikün ad pubön.',

# Multipage image navigation
'imgmultipageprev' => '← pad büik',
'imgmultipagenext' => 'pad sököl →',
'imgmultigo'       => 'Gololöd!',
'imgmultigoto'     => 'Lü pad: $1',

# Table pager
'ascending_abbrev'         => 'löpio',
'descending_abbrev'        => 'donio',
'table_pager_next'         => 'Pad sököl',
'table_pager_prev'         => 'Pad büik',
'table_pager_first'        => 'Pad balid',
'table_pager_last'         => 'Pad lätik',
'table_pager_limit'        => 'Jonön lienis $1 a pad',
'table_pager_limit_submit' => 'Gololöd',
'table_pager_empty'        => 'Seks nonik',

# Auto-summaries
'autosumm-blank'   => 'Emoükon ninädi valik se pad',
'autosumm-replace' => "Pad pepläadon me '$1'",
'autoredircomment' => 'Lüodükon lü [[$1]]',
'autosumm-new'     => "Ejafon padi ko: '$1'",

# Live preview
'livepreview-loading' => 'Pabelodon…',
'livepreview-ready'   => 'Pabelodon… Efinikon!',
'livepreview-failed'  => 'Büologed vifik no eplöpon! Gebolös büologedi kösömik.',
'livepreview-error'   => 'Yümätam no eplöpon: $1 „$2“. Steifülolös me büologed kösömik.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Votükams ün {{PLURAL:$1|sekun|sekuns}} lätik $1 ba no polisedons is.',

# Watchlist editor
'watchlistedit-numitems'       => 'Galädalised olik labon {{PLURAL:$1|tiädi bal|tiädis $1}}, fakipü bespikapads.',
'watchlistedit-noitems'        => 'Galädalised olik keninükon tiädis nonik.',
'watchlistedit-normal-title'   => 'Redakön galädalisedi',
'watchlistedit-normal-legend'  => 'Moükön tiädis se galädalised',
'watchlistedit-normal-explain' => 'Tiäds su galädalised olik palisedons dono.
Ad moükön tiädi, välolös bugili nilü on e klikolös: "{{int:Watchlistedit-normal-submit}}".
Kanol i [[Special:Watchlist/raw|redakön lisedafonäti]].',
'watchlistedit-normal-submit'  => 'Moükön Tiädis',
'watchlistedit-normal-done'    => '{{PLURAL:$1|tiäd bal pemoükon|tiäds $1 pemoükons}} se galädalised olik:',
'watchlistedit-raw-title'      => 'Redakön fonäti galädaliseda',
'watchlistedit-raw-legend'     => 'Redakön fonäti galädaliseda',
'watchlistedit-raw-explain'    => 'Tiäds galädaliseda olik pajonons dono, e kanons paredakön - paläükön u pamoükön se lised (ai tiäd bal a lien). Pos redakam, klikolös Votükön Galädalisedi.
Kanol i [[Special:Watchlist/edit|gebön redakametodi kösömik]].',
'watchlistedit-raw-titles'     => 'Tiäds:',
'watchlistedit-raw-submit'     => 'Votükön Galädalisedi',
'watchlistedit-raw-done'       => 'Galädalised olik pevotükon.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|Tiäd bal peläükon|Tiäds $1 peläükons}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|Tiäd bal pemoükon|Tiäds $1 pemoükons}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Logön votükamis teföl',
'watchlisttools-edit' => 'Logön e redakön galädalisedi',
'watchlisttools-raw'  => 'Redakön galädalisedi nen fomät',

# Core parser functions
'duplicate-defaultsort' => 'Nüned: Leodükamakik kösömik: „$2“ buon bu leodükamakik kösömik büik: „$1“.',

# Special:Version
'version'                   => 'Fomam',
'version-extensions'        => 'Veitükumams pestitöl',
'version-specialpages'      => 'Pads patik',
'version-skins'             => 'Fomäts',
'version-other'             => 'Votik',
'version-hooks'             => 'Huköms',
'version-hook-name'         => 'Hukömanem',
'version-hook-subscribedby' => 'Pagebon fa',
'version-version'           => '(Fomam $1)',
'version-license'           => 'Dälazöt',
'version-software'          => 'Programs pestitöl',
'version-software-product'  => 'Prodäd',
'version-software-version'  => 'Fomam',

# Special:FilePath
'filepath'         => 'Ragivaluveg',
'filepath-page'    => 'Ragiv:',
'filepath-submit'  => 'Gololöd',
'filepath-summary' => 'Pad patik at tuvon luvegi lölöfik ragiva. Magods pajonons ma fomät gudikün, ragivasots votik pamaifükons stedöfo kobü programs onsik.

Penolös ragivanemi nen foyümot: „{{ns:file}}:“',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Sukön ragivis petelüköl',
'fileduplicatesearch-summary'  => 'Sukön ragivis petelüköl stabü völad kontrolasaedota onsik.',
'fileduplicatesearch-legend'   => 'Sukön telükoti',
'fileduplicatesearch-filename' => 'Ragivanem:',
'fileduplicatesearch-submit'   => 'Sukön',
'fileduplicatesearch-info'     => 'pixels $1 × $2 <br />Ragivagretot: $3<br />MIME-sot: $4',
'fileduplicatesearch-result-1' => 'Ragiv: „$1“ no labon telükoti kuratik.',
'fileduplicatesearch-result-n' => 'Ragiv: „$1“ labon {{PLURAL:$2|telükoti kuratik bal|telükotis kuratik $2}}.',

# Special:SpecialPages
'specialpages'                   => 'Pads patik',
'specialpages-note'              => '----
* Pads patik nomik.
* <strong class="mw-specialpagerestricted">Pads patik pemiedüköl.</strong>',
'specialpages-group-maintenance' => 'Nunods tefü kiped',
'specialpages-group-other'       => 'Pads patik votik',
'specialpages-group-login'       => 'Nunädön oki / jafön kali',
'specialpages-group-changes'     => 'Votükams nulik e jenotaliseds',
'specialpages-group-media'       => 'Nüns e löpükams ragivas',
'specialpages-group-users'       => 'Gebans e gitäts',
'specialpages-group-highuse'     => 'Pads suvo pegeböls',
'specialpages-group-pages'       => 'Padaliseds',
'specialpages-group-pagetools'   => 'Padastumem',
'specialpages-group-wiki'        => 'Nüns e stums vükiks',
'specialpages-group-redirects'   => 'Lüodükam padas patik',
'specialpages-group-spam'        => 'Stums ta el spam',

# Special:BlankPage
'blankpage'              => 'Pad vagik',
'intentionallyblankpage' => 'Pad at pevagükon desino',

# Special:Tags
'tag-filter-submit'   => 'Sul',
'tags-display-header' => 'Logot in votükamaliseds',
'tags-edit'           => 'redakön',
'tags-hitcount'       => '$1 {{PLURAL:$1|votükam|votükams}}',

# Special:ComparePages
'comparepages'   => 'Leigodön padis',
'compare-page1'  => 'Pad 1',
'compare-page2'  => 'Pad 2',
'compare-rev1'   => 'Revid 1',
'compare-rev2'   => 'Revid 2',
'compare-submit' => 'Leigodolöd',

# Database error messages
'dberr-header'    => 'Vük at labon säkädi',
'dberr-problems'  => 'Säkusadolös! Bevüresodatopäd at nu labon säkädis kaenik.',
'dberr-again'     => 'Steifülolös dönu pos stebedüp minutas anik.',
'dberr-info'      => '(No eplöpos ad kosikön ko dünanünöm nünodema: $1)',
'dberr-usegoogle' => 'Kanol sukön me el Google vütimo.',

# HTML forms
'htmlform-submit'              => 'Sedön',
'htmlform-reset'               => 'Sädunön votükamis',
'htmlform-selectorother-other' => 'Votik',

# Special:DisableAccount
'disableaccount-user'       => 'Gebananem:',
'disableaccount-reason'     => 'Kod:',
'disableaccount-nosuchuser' => 'Gebanakal: "$1" no dabinon.',

);

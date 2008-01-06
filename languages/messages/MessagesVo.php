<?php
/** Volapük (Volapük)
 *
 * @addtogroup Language
 *
 * @author Smeira
 * @author Nike
 * @author Malafaya
 * @author Siebrand
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA          => 'Nünamakanäd',
	NS_SPECIAL        => 'Patikos',
	NS_MAIN           => '',
	NS_TALK           => 'Bespik',
	NS_USER           => 'Geban',
	NS_USER_TALK      => 'Gebanibespik',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => 'Bespik_dö_$1',
	NS_IMAGE          => 'Magod',
	NS_IMAGE_TALK     => 'Magodibespik',
	NS_MEDIAWIKI      => 'Sitanuns',
	NS_MEDIAWIKI_TALK => 'Bespik_dö_sitanuns',
	NS_TEMPLATE       => 'Samafomot',
	NS_TEMPLATE_TALK  => 'Samafomotibespik',
	NS_HELP           => 'Yuf',
	NS_HELP_TALK      => 'Yufibespik',
	NS_CATEGORY       => 'Klad',
	NS_CATEGORY_TALK  => 'Kladibespik',
);

$datePreferences = array(
	'default',
	'vo',
	'ISO 8601',
);

$defaultDateFormat = 'vo';

$dateFormats = array(
	'vo time' => 'H:i',
	'vo date' => 'Y F j"id"',
	'vo both' => 'H:i, Y F j"id"',
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Lüodükömstelik', 'Lüodüköms_telik' ),
	'BrokenRedirects'           => array( 'Lüodükömsdädik', 'Lüodüköms_dädik' ),
	'Disambiguations'           => array( 'Telplänovs' ),
	'Userlogin'                 => array( 'Gebananunäd' ),
	'Userlogout'                => array( 'Gebanasenunäd' ),
	'Preferences'               => array( 'Buükams' ),
	'Watchlist'                 => array( 'Galädalised' ),
	'Recentchanges'             => array( 'Votükamsnulik' ),
	'Upload'                    => array( 'Löpükön' ),
	'Imagelist'                 => array( 'Magodalised' ),
	'Newimages'                 => array( 'Magodsnulik', 'Magods_nulik' ),
	'Listusers'                 => array( 'Gebanalised' ),
	'Statistics'                => array( 'Statits' ),
	'Randompage'                => array( 'Padfädik', 'Pad_fädik', 'Fädik' ),
	'Lonelypages'               => array( 'Padssoelöl', 'Pads_soelöl' ),
	'Uncategorizedpages'        => array( 'Padsnenklads', 'Pads_nen_klads' ),
	'Uncategorizedcategories'   => array( 'Kladsnenklads', 'Klads_nen_klads' ),
	'Uncategorizedimages'       => array( 'Magodsnenklads', 'Magods_nen_klads' ),
	'Uncategorizedtemplates'    => array( 'Samafomotsnenklads', 'Samafomots_nen_klads' ),
	'Unusedcategories'          => array( 'Kladsnopageböls', 'Klad_no_pageböls' ),
	'Unusedimages'              => array( 'Magodsnopageböls', 'Magods_no_pageböls' ),
	'Wantedpages'               => array( 'Padspavilöl', 'Yümsdädik', 'Pads_pavilöl', 'Yüms_dädik' ),
	'Wantedcategories'          => array( 'Kladspavilöl', 'Klads_pavilöl' ),
	'Mostlinked'                => array( 'Suvüno_peyümöls' ),
	'Mostlinkedcategories'      => array( 'Klads_suvüno_peyümöls' ),
	'Mostlinkedtemplates'       => array( 'Samafomots_suvüno_peyümöls' ),
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
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Dislienükolöd yümis:',
'tog-highlightbroken'         => 'Jonön yümis dädik <a href="" class="new">ön mod at</a> (voto: ön mod at<a href="" class="internal">?</a>).',
'tog-hideminor'               => 'Klänedön redakamis pülik su lised votükamas nulik',
'tog-extendwatchlist'         => 'Stäänükön galädalisedi ad jonön votükamis tefik valik',
'tog-usenewrc'                => 'Lised pamenodöl votükamas nulik (JavaScript)',
'tog-showtoolbar'             => 'Jonön redakastumemi (JavaScript)',
'tog-editondblclick'          => 'Dälön redakön padis pö drän telik mugaknopa (JavaScript)',
'tog-editsection'             => 'Dälön redakami dilädas me yüms: [redakön]',
'tog-editsectiononrightclick' => 'Dälön redakami diläda me klik mugaknopa detik su dilädatiäds (JavaScript)',
'tog-showtoc'                 => 'Jonön ninädalisedi (su pads labü diläds plu 3)',
'tog-rememberpassword'        => 'Dakipön nunädamanünis obik in nünöm at',
'tog-editwidth'               => 'Redakaspad labon vidoti lölöfik',
'tog-watchcreations'          => 'Läükolöd padis fa ob pejafölis lä galädalised obik',
'tog-watchdefault'            => 'Läükolöd padis fa ob peredakölis la galädalised obik',
'tog-watchmoves'              => 'Läükolöd padis fa ob petopätükölis lä galädalised obik',
'tog-watchdeletion'           => 'Läükolöd padis fa ob pemoükölis lä galädalised obik',
'tog-minordefault'            => 'Lelogolöd redakamis no pebepenölis valikis asä pülikis',
'tog-previewontop'            => 'Jonolöd büologedi bü redakaspad',
'tog-previewonfirst'          => 'Jonolöd büologedi pö redakam balid',
'tog-enotifwatchlistpages'    => 'Sedolös obe penedi leäktronik ven pad se galädalised obik pavotükon',
'tog-enotifusertalkpages'     => 'Sedolös obe penedi leäktronik ven gebanapad obik pavotükon',
'tog-enotifminoredits'        => 'Sedolös obe penedi leäktronik igo pö padavotükams pülik',
'tog-fancysig'                => 'Dispenäd balugik (nen yüms lü gebanapad)',
'tog-externaleditor'          => 'Gebolöd nomiko redakömi plödik',
'tog-externaldiff'            => 'Gebolöd nomiko eli diff plödik',
'tog-showjumplinks'           => 'Dälolöd lügolovi me yüms "lübunöl"',
'tog-forceeditsummary'        => 'Sagolöd obe, ven redakaplän brefik vagon',
'tog-watchlisthideown'        => 'No jonolöd redakamis obik in galädalised',
'tog-watchlisthidebots'       => 'No jonolöd redakamis mäikamenas in galädalised',
'tog-watchlisthideminor'      => 'Klänolöd redakamis pülik se galädalised',
'tog-ccmeonemails'            => 'Sedolöd obe kopiedis penedas, kelis sedob gebanes votik',

'underline-always'  => 'Pö jenets valik',
'underline-never'   => 'Neföro',
'underline-default' => 'Ma bevüresodatävöm',

'skinpreview' => '(Büologed)',

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

# Bits of text used by many pages
'categories'            => 'Klads',
'pagecategories'        => '{{PLURAL:$1|Klad|Klads}}',
'category_header'       => 'Pads in klad: „$1“',
'subcategories'         => 'Donaklads',
'category-media-header' => 'Media in klad: "$1"',
'category-empty'        => "''Klad at anu ninädon padis e ragivis nonikis.''",

'about'          => 'Tefü',
'article'        => 'Ninädapad',
'newwindow'      => '(maifikon in fenät nulik)',
'cancel'         => 'Sädunön',
'qbfind'         => 'Tuvön',
'qbbrowse'       => 'Padön',
'qbedit'         => 'Redakön',
'qbpageoptions'  => 'Pad at',
'qbmyoptions'    => 'Pads obik',
'qbspecialpages' => 'Pads patik',
'moredotdotdot'  => 'Plu...',
'mypage'         => 'Pad obik',
'mytalk'         => 'Bespiks obik',
'navigation'     => 'Nafam',

'errorpagetitle'    => 'Pöl',
'returnto'          => 'Geikön lü $1.',
'tagline'           => 'Se {{SITENAME}}',
'help'              => 'Yuf',
'search'            => 'Suk',
'searchbutton'      => 'Sukolöd',
'go'                => 'Gololöd',
'searcharticle'     => 'Getolöd',
'history'           => 'Padajenotem',
'history_short'     => 'Jenotem',
'info_short'        => 'Nün',
'printableversion'  => 'Fom dabükovik',
'permalink'         => 'Yüm laidüpik',
'print'             => 'Bükön',
'edit'              => 'Redakön',
'editthispage'      => 'Redakolöd padi at',
'delete'            => 'Moükön',
'deletethispage'    => 'Moükolös padi at',
'undelete_short'    => 'Sädunön moükami {{PLURAL:$1|redakama bal|redakamas $1}}',
'protect'           => 'Jelön',
'protectthispage'   => 'Jelön padi at',
'unprotect'         => 'säjelön',
'unprotectthispage' => 'Säjelolöd padi at',
'newpage'           => 'Pad nulik',
'talkpage'          => 'Bespikolöd padi at',
'talkpagelinktext'  => 'Bespik',
'specialpage'       => 'Pad patik',
'personaltools'     => 'Stums pösodik',
'articlepage'       => 'Jonön ninädapadi',
'talk'              => 'Bespik',
'views'             => 'Logams',
'toolbox'           => 'Stumem',
'userpage'          => 'Logön gebanapadi',
'projectpage'       => 'Logedön proyegapadi',
'imagepage'         => 'Jonön magodapad',
'mediawikipage'     => 'Logön nunapadi',
'templatepage'      => 'Logön samafomotapadi',
'viewhelppage'      => 'Jonön yufapadi',
'categorypage'      => 'Jonolöd kladapadi',
'viewtalkpage'      => 'Logedön bespiki',
'otherlanguages'    => 'In püks votik',
'redirectedfrom'    => '(Pelüodükon de pad: $1)',
'redirectpagesub'   => 'Lüodükömapad',
'lastmodifiedat'    => 'Pad at pävotükon lätiküno tü düp $2, ün $1.', # $1 date, $2 time
'protectedpage'     => 'Pad pejelöl',
'jumpto'            => 'Bunön lü:',
'jumptonavigation'  => 'nafam',
'jumptosearch'      => 'suk',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Tefü {{SITENAME}}',
'aboutpage'         => 'Project:Tefü',
'copyright'         => 'Ninäd gebidon ma el $1.',
'copyrightpagename' => 'Kopiedagität {{SITENAME}}a',
'currentevents'     => 'Jenots nuik',
'currentevents-url' => 'Project:Jenots nuik',
'disclaimers'       => 'Nuneds',
'edithelp'          => 'Redakamayuf',
'edithelppage'      => 'Yuf:Redakam',
'faq'               => 'Säks suvo pasäköls',
'helppage'          => 'Help:Ninäd',
'mainpage'          => 'Cifapad',
'portal'            => 'Komotanefaleyan',
'portal-url'        => 'Project:Komotanefaleyan',
'privacy'           => 'Dunamod demü soelöf',
'privacypage'       => 'Project:Dunamod_demü_soelöf',
'sitesupport'       => 'Födagivots',

'badaccess-group0' => 'No pedälol ad ledunön atosi, kelosi ebegol.',

'ok'                      => 'Si!',
'retrievedfrom'           => 'Pekopiedon se "$1"',
'youhavenewmessages'      => 'Su pad ola binons $1 ($2).',
'newmessageslink'         => 'nuns nulik',
'newmessagesdifflink'     => 'votükam lätik',
'youhavenewmessagesmulti' => 'Labol nunis nulik su $1',
'editsection'             => 'redakön',
'editold'                 => 'redakön',
'editsectionhint'         => 'Redakolöd dilädi: $1',
'toc'                     => 'Ninäd',
'showtoc'                 => 'jonolöd',
'hidetoc'                 => 'klänedolöd',
'thisisdeleted'           => 'Jonön u sädunön moükami $1?',
'viewdeleted'             => 'Logedön $1i?',
'restorelink'             => '{{PLURAL:$1|redakama bal|redakamas $1}}',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Yeged',
'nstab-user'      => 'Gebanapad',
'nstab-media'     => 'Nünamakanädapad',
'nstab-special'   => 'Patik',
'nstab-project'   => 'Proyegapad',
'nstab-image'     => 'Ragiv',
'nstab-mediawiki' => 'Vödem',
'nstab-template'  => 'Samafomot',
'nstab-help'      => 'Yufapad',
'nstab-category'  => 'Klad',

# Main script and global functions
'nosuchspecialpage' => 'Pad patik at no dabinon',
'nospecialpagetext' => 'Esukol padi patik no dabinöli. Lised padas patik dabinöl binon su pad: [[Special:Specialpages]].',

# General errors
'error'              => 'Pöl',
'dberrortext'        => 'Süntagapök pö geb vüka at ejenon.
Atos ba sinifön, das dabinon säkäd pö program.
Steifül lätik ad gebön vüki äbinon:
<blockquote><tt>$1</tt></blockquote>
se dunod: "<tt>$2</tt>".
El MySQL ägesedon pökanuni: "<tt>$3: $4</tt>".',
'dberrortextcl'      => 'Süntagapök pö geb vüka at ejenon.
Steifül lätik ad gebön vüki at äbinon:
"$1"
se dunod: "$2".
El MySQL ägesedon pökanuni: "$3: $4"',
'cachederror'        => 'Sökölos binon kopied pasetik pada pevipöl. Mögos, das no binon fomam lätikün.',
'readonly'           => 'Vük pefärmükon',
'readonlytext'       => 'Vük pefärmükon: yegeds e votükams nuliks no kanons padakipön. Atos ejenon bo pro kosididaduns, pos kels vük ogeikon ad stad kösömik.

Guvan, kel äfärmükon vüki, äplänon osi ön mod sököl: $1',
'missingarticle'     => 'Vödem pada tiädü „"$1"“ no petuvon. Kod atosa nomöfiko binon, das yüm bäldik päsökon ad pad ya pemoüköl.

If ye pad at dabinon, ba etuvol säkädi in nünömasit. Nunolös, begö! osi guvanes, ed i ladeti (URL) tefik.',
'readonly_lag'       => 'Vük pefärmükon itjäfidiko du dünanünöms slafik kosädons ko mastanünöm.',
'internalerror'      => 'Pöl ninik',
'internalerror_info' => 'Pöl ninik: $1',
'filecopyerror'      => 'No emögos ad kopiedön ragivi "$1" ad "$2".',
'filedeleteerror'    => 'No emögos ad moükön ragivi "$1".',
'filenotfound'       => 'No eplöpos ad tuvön ragivi: "$1".',
'formerror'          => 'PÖL: no emögos ad bevobön fometi at.',
'badarticleerror'    => 'Dun at no kanon paledunön su pad at.',
'cannotdelete'       => 'No emögos ad moükön padi/ragivi pevälöl. (Ba ya pemoükon fa geban votik.)',
'badtitle'           => 'Tiäd badik',
'perfcachedts'       => 'Nüns sököl kömons se mem nelaidüpik e päbevobons lätiküno ün: $1.',
'viewsource'         => 'Logedön fonäti',
'viewsourcefor'      => 'tefü $1',
'protectedpagetext'  => 'Pad at pejelon ad neletön redakami.',
'viewsourcetext'     => 'Kanol logön e kopiedön fonätakoti pada at:',
'editinginterface'   => "'''Nuned:''' Anu redakol padi, kel labükon vödemi bevüik pro programem. Votükams pada at oflunons logoti gebanasita pro gebans votik.",

# Login and logout pages
'welcomecreation'            => '== Benokömö, o $1! ==

Kal olik pejafon. No glömolöd ad votükön buükamis olik in {{SITENAME}}.',
'yourname'                   => 'Gebananem',
'yourpassword'               => 'Letavöd',
'yourpasswordagain'          => 'Klavolös dönu letavödi',
'remembermypassword'         => 'Dakipolöd ninädamanünis obik in nünöm at',
'login'                      => 'Nunädolös obi',
'userlogin'                  => 'Nunädön oki / jafön kali',
'logout'                     => 'Senunädön oki',
'userlogout'                 => 'Senunädön oki',
'nologin'                    => 'No labol-li kali? $1.',
'nologinlink'                => 'Jafolös bali',
'createaccount'              => 'Jafön kali',
'gotaccount'                 => 'Ya labol-li kali? $1.',
'gotaccountlink'             => 'Nunädolös obi',
'youremail'                  => 'Ladet leäktronik *:',
'username'                   => 'Gebananem:',
'uid'                        => 'Gebanadientif:',
'yourrealname'               => 'Nem jenöfik *:',
'yourlanguage'               => 'Pük:',
'yournick'                   => 'Länem:',
'email'                      => 'Ladet leäktronik',
'prefs-help-email'           => '* Ladet leäktronik (if vilol): dälon votikanes ad kosikön ko ol 
yufü gebanapad u gebanabespikapad olik nes sävilupol dientifi olik.',
'noname'                     => 'No egivol gebananemi lonöföl.',
'loginsuccesstitle'          => 'Enunädol oli benosekiko',
'loginsuccess'               => "'''Binol anu in {{SITENAME}} as \"\$1\".'''",
'nosuchuser'                 => 'No dabinon geban labü nem: "$1". Koräkolös tonatami nema at, u jafolös kali nulik.',
'nosuchusershort'            => 'No dabinon geban labü nem: "$1". Koräkolös tonatami nema at.',
'wrongpassword'              => 'Letavöd neveräton. Steifülolös dönu.',
'wrongpasswordempty'         => 'Letavöd vagon. Steifülolös dönu.',
'mailmypassword'             => 'Sedolös obe letavödi',
'passwordremindertitle'      => 'Letavöd nulik nelaidik in {{SITENAME}}',
'noemail'                    => 'Ladet leäktronik nonik peregistaron pro geban "$1".',
'passwordsent'               => 'Letavöd nulik pesedon ladete leäktronik fa "$1" peregistaröle.<br />
Nunädolös oli dönu posä ogetol oni.',
'acct_creation_throttle_hit' => 'Säkusädolös, ya ejafol kalis $1. No plu kanol jafön kali nulik.',
'emailauthenticated'         => 'Ladet leäktronik olik päfümükon tü düp $1.',
'emailnotauthenticated'      => 'Ladet leäktronik ola no nog pefümedon. Pened nonik posedon me pads sököl.',
'noemailprefs'               => 'Givolös ladeti leäktronik, dat pads at okanons pagebön.',
'emailconfirmlink'           => 'Fümedolös ladeti leäktronik ola',
'accountcreated'             => 'Kal pejafon',
'accountcreatedtext'         => 'Gebanakal pro $1 pejafon.',
'loginlanguagelabel'         => 'Pük: $1',

# Edit page toolbar
'italic_sample'   => 'Korsiv',
'italic_tip'      => 'Korsiv',
'link_sample'     => 'Yümatiäd',
'link_tip'        => 'Yüm ninik',
'extlink_sample'  => 'http://www.sam.com yümatiäd',
'headline_sample' => 'Tiädavödem',
'headline_tip'    => 'Tiäd nivoda 2id',
'math_sample'     => 'Pladolös malatami isio',
'math_tip'        => 'Malatam matematik (LaTeX)',
'nowiki_sample'   => 'Pladolös isio vödemi no pefomätöli',
'nowiki_tip'      => 'Nedemön vükifomätami',

# Edit pages
'summary'                  => 'Plän brefik',
'subject'                  => 'Subyet/tiäd',
'minoredit'                => 'Votükam pülik',
'watchthis'                => 'Galädolöd padi at',
'savearticle'              => 'Dakipolöd padi',
'preview'                  => 'Büologed',
'showpreview'              => 'Jonolöd padalogoti',
'showdiff'                 => 'Jonolöd votükamis',
'anoneditwarning'          => "'''Nuned:''' No enunädol oli. Ladet-IP olik poregistaron su redakamajenotem pada at.",
'blockedtitle'             => 'Geban peblokon',
'blockedtext'              => "<big>'''Gebananam u ladet-IP olik(s) peblokon(s).'''</big>

Blokam at pejenükon fa $1. Kod binon ''$2''.

* Prim blokama: $8
* Fin blokama: $6
* Geban desinik: $7

Kanol penön ele $1, u [[{{MediaWiki:Grouppage-sysop}}|guvanes]], ad bespikön blokami.
Kanol gebön yümi: 'penön gebane at' bisä ladet leäktronik verätik lonöföl patuvon in [[Special:Preferences|buükams kala]] olik. Ladet-IP nuik ola binon $3 e nüm blokama binon #$5. Mäniotolös oni pö säks valik.",
'whitelistedittext'        => 'Mutol $1 ad redakön padis.',
'whitelistacctitle'        => 'No dalol jafön kali',
'confirmedittitle'         => 'Fümedam me pot leäktronik zesüdon ad redakön',
'confirmedittext'          => 'Mutol fümedön ladeti leäktronik ola büä okanol redakön padis. Pladölos e lonöfükölos ladeti olik in [[Special:Preferences|buükams olik]].',
'loginreqlink'             => 'ninädolös obi',
'accmailtitle'             => 'Letavöd pesedon.',
'accmailtext'              => 'Letavöd pro "$1" pasedon lü $2.',
'newarticle'               => '(Nulik)',
'newarticletext'           => "Esökol yümi lü pad, kel no nog dabinon.
Ad jafön padi at, primolös ad klavön vödemi olik in penaspad dono (logolöd [[{{MediaWiki:Helppage}}|yufapadi]] tefü nüns tefik votik).
If binol is pölo, välolös knopi: '''geikön''' bevüresodatävöma olik.",
'anontalkpagetext'         => "----''Bespikapad at duton lü geban nennemik, kel no nog ejafon kali, u no vilon labön u gebön oni. Sekü atos pemütobs ad gebön ladeti-IP ad dientifükön gebani at. Ladets-IP kanons pagebön fa gebans difik. If binol geban nennemik e cedol, das küpets netefik pelüodükons ole, [[Special:Userlogin|jafolös, begö! kali u nunädolös oli]] ad vitön kofudi ko gebans nennemik votik.''",
'noarticletext'            => 'Atimo no dabinon vödem su pad at. Kanol [[{{ns:special}}:Search/{{PAGENAME}}|sukön padatiädi at]] su pads votik u [{{fullurl:{{FULLPAGENAME}}|action=edit}} redakön padi at].',
'clearyourcache'           => "'''Prudö!''' Pos dakip buükamas, mögos, das ozesüdos ad nedemön memi nelaidüpik bevüresodatävöma ad logön votükamis. '''Mozilla / Firefox / Safari:''' kipolöd klavi ''Shift'' dono e välolöd eli ''Reload'' (= dönulodön) me mugaparat, u dränolöd klävis ''Ctrl-Shift-R'' (''Cmd-Shift-R'' pö el Apple Mac); pro el '''IE:''' (Internet Explorer) kipolöd klavi ''Ctrl'' dono e välolöd eli ''Refresh'' (= flifädükön) me mugaparat, u dränolöd klavis ''Ctrl-F5''; '''Konqueror:''': välolöd eli ''Reload'' (= dönulodön) me mugaparat, u dränolöd klavi ''F5''; gebans ela '''Opera''' ba nedons vagükön lölöfiko memi nelaidüpik me ''Tools→Preferences'' (Stumem->Buükams).",
'note'                     => '<strong>Penet:</strong>',
'previewnote'              => '<strong>Is pajonon te büologed; votükams no nog pedakipons!</strong>',
'session_fail_preview'     => '<strong>Pidö! No emögos ad lasumön votükamis olik kodü per redakamanünas.<br />Steifülolös dönu. If no oplöpol, tän senunädolös e genunädolös oli, e steifülolös nogna.</strong>',
'editing'                  => 'Redakam pada: $1',
'editinguser'              => 'Redakam gebanapada: <b>$1</b>',
'editingsection'           => 'Redakam pada: $1 (diläd)',
'editingcomment'           => 'Redakam pada: $1 (küpet)',
'explainconflict'          => 'Ek evotükon padi at sisä äprimol ad redakön oni. Vödem balid jonon padi soäsä dabinon anu. Votükams olik pajonons in vödem telid. Sludolös, vio fomams tel at mutons pabalön. Kanol kopiedön se vödem telid ini balid. 
<b>Te vödem balid podakipon!</b><br />',
'yourtext'                 => 'Vödem olik',
'editingold'               => '<strong>NUNED: Anu redakol fomami büik pada at. If dakipol oni, votükams posik onepubons.</strong>',
'yourdiff'                 => 'Difs',
'copyrightwarning'         => 'Demolös, das keblünots valik lü Vükiped padasumons ma el $2 (logolöd eli $1 tefü notets). If no vilol, das vödems olik poredakons nenmisero e poseagivons ma vil alana, tän no pladolös oni isio.<br />
Garanol obes, das ol it epenol atosi, u das ekopiedol atosi se räyun notidik u se fon libik sümik.<br />
<strong>NO PLADOLÖD ISIO NEN DÄL LAUTANA VÖDEMIS LABÜ KOPIEDAGITÄT!</strong>',
'copyrightwarning2'        => 'Demolös, das keblünots valik lü {{SITENAME}} padasumons ma el $2 (logolöd eli $1 tefü notets). If no vilol, das vödems olik poredakons nenmisero e poseagivons ma vil alana, tän no pladolös onis isio.<br />
Garanol obes, das ol it epenol atosi, u das ekopiedol atosi se räyun notidik u se fon libik sümik.<br />
<strong>NO PLADOLÖD ISIO NEN DÄL LAUTANA VÖDEMIS LABÜ KOPIEDAGITÄT!</strong>',
'longpagewarning'          => '<strong>NUNED: Pad at labon lunoti miljölätas $1; bevüresodatävöms anik ba no fägons ad redakön nendsäkädo padis lunotü miljölats plu 32. Betikolös dilami pada at ad pads smalikum.</strong>',
'readonlywarning'          => '<strong>NUNED: Vük pefärmükon kodü kodididazesüd. No kanol dakipön votükamis olik anu. Kopiedolös vödemi nulik ini program votik e dakipolös oni in nünöm olik. Poso okanol dönu steifülön ad pladön oni isio.</strong>',
'protectedpagewarning'     => '<strong>NUNED: Pad at pejelon, dat te gebans labü guvanagitäts kanons redakön oni.</strong>',
'semiprotectedpagewarning' => "'''Noet:''' Pad at pefärmükon. Te gebans peregistaröl kanons redakön oni.",
'templatesused'            => 'Samafomots su pad at pegeböls:',
'templatesusedpreview'     => 'Samafomots in büologed at pageböls:',
'template-protected'       => '(pejelon)',
'template-semiprotected'   => '(dilo pejelon)',
'recreate-deleted-warn'    => "'''NUNED: Dönujafol padi pemoüköl.'''

Vätälolös, va binos pötik ad lairedakön padi at.<br>
Jenotalised moükama pada at pajonon is as yuf.",

# Account creation failure
'cantcreateaccounttitle' => 'Kal no kanon pajafön',

# History pages
'viewpagelogs'     => 'Jonön jenotalisedis pada at',
'currentrev'       => 'Revid anuik',
'revisionasof'     => 'Revid dätü $1',
'previousrevision' => '←Revid büik',
'cur'              => 'nuik',
'next'             => 'sököl',
'last'             => 'lätik',
'page_first'       => 'balid',
'page_last'        => 'lätik',
'deletedrev'       => '[pemoüköl]',
'histfirst'        => 'Balid',
'histlast'         => 'Lätik',
'historyempty'     => '(vagik)',

# Revision feed
'history-feed-title'       => 'Revidajenotem',
'history-feed-description' => 'Revidajenotem pada at in vük',
'history-feed-empty'       => 'Pad pevipöl no dabinon.
Ba pemoükon se ragivs, u ba pevotanemon.
Kanol [[Special:Search|sukön]] padis nulik tefik.',

# Revision deletion
'rev-delundel'   => 'jonolöd/klänedolöd',
'revdelete-text' => 'Revids pemoüköl nog opubons in padajenotem, ab ninäd (vödem) onsik no gebidons publüge. 

Ninäd peklänedöl at binon ye nog lügolovik guvanes votik vüka at: kanons nog geükön oni medü pads patik, üf miedöfükams u neletians pluiks no pepladons.',

# Diffs
'history-title'           => 'Revidajenotem pada: "$1"',
'difference'              => '(dif vü revids)',
'lineno'                  => 'Lien $1:',
'compareselectedversions' => 'Leigodolöd fomamis pevälöl',
'editundo'                => 'sädunön',

# Search results
'searchresults'         => 'Sukaseks',
'searchresulttext'      => 'Ad lärnön mödikumosi dö suks in {{SITENAME}}, logolös [[{{MediaWiki:Helppage}}|Suks in {{SITENAME}}]].',
'searchsubtitle'        => "Esukol padi: '''[[:$1]]'''",
'searchsubtitleinvalid' => "Esukol padi: '''$1'''",
'noexactmatch'          => "'''No dabinon pad tiädü \"\$1\".''' Kanol [[:\$1|jafön oni]].",
'prevn'                 => 'büik $1',
'nextn'                 => 'sököl $1',
'viewprevnext'          => 'Logedön padis ($1) ($2) ($3).',
'showingresults'        => "Pajonons dono jü {{PLURAL:$1|sukasek '''1'''|sukaseks '''$1'''}}, primölo me nüm #'''$2'''.",
'showingresultsnum'     => "Dono pajonons {{PLURAL:$3:|sek '''1'''|seks '''$3'''}}, primölo me nüm: '''$2'''.",
'powersearch'           => 'Suk',

# Preferences page
'preferences'           => 'Buükams',
'mypreferences'         => 'Buükams obik',
'prefs-edits'           => 'Num redakamas:',
'qbsettings-none'       => 'Nonik',
'changepassword'        => 'Votükön letavödi',
'skin'                  => 'Fomät',
'math'                  => 'Logot formülas',
'dateformat'            => 'Dätafomät',
'datedefault'           => 'Buükam nonik',
'datetime'              => 'Dät e Tim',
'prefs-personal'        => 'Gebananüns',
'prefs-rc'              => 'Votükams nulik',
'prefs-watchlist'       => 'Galädalised',
'prefs-watchlist-days'  => 'Num delas ad pajonön in galädalised:',
'prefs-watchlist-edits' => 'Num redakamas ad pajonön in galädalised pestäänüköl:',
'prefs-misc'            => 'Votikos',
'saveprefs'             => 'Dakipolöd',
'resetprefs'            => 'Buükams rigik',
'oldpassword'           => 'Letavöd büik:',
'newpassword'           => 'Letavöd nulik:',
'retypenew'             => 'Klavolöd dönu letavödi nulik:',
'textboxsize'           => 'Redakam',
'rows'                  => 'Kedets:',
'columns'               => 'Padüls:',
'searchresultshead'     => 'Suk',
'resultsperpage'        => 'Tiäds petuvöl a pad:',
'contextlines'          => 'Kedets a pad petuvöl:',
'contextchars'          => 'Kevödem a kedet:',
'recentchangescount'    => 'Tiäds in lised votükamas nulik:',
'savedprefs'            => 'Buükams olik pedakipons.',
'timezonelegend'        => 'Timatopäd',
'timezonetext'          => 'Num düpas, mö kel tim topik difon de tim dünanünöma (UTC).',
'localtime'             => 'Tim topik',
'timezoneoffset'        => 'Näedot¹',
'servertime'            => 'Tim dünanünöma',
'guesstimezone'         => 'Benüpenolöd yufü befüresodatävöm',
'allowemail'            => 'Fägükolöd siti ad getön poti leäktronik de gebans votik',
'defaultns'             => 'Sukolöd nomiko in nemaspads at:',
'files'                 => 'Ragivs',

# User rights
'saveusergroups' => 'Dakipolöd gebanagrupis',

# Groups
'group'            => 'Grup:',
'group-sysop'      => 'Guvans',
'group-bureaucrat' => 'Bürans',
'group-all'        => '(valik)',

'group-sysop-member'      => 'Guvan',
'group-bureaucrat-member' => 'Büran',

'grouppage-sysop' => '{{ns:project}}:Guvans',

# User rights log
'rightslog' => 'Jenotalised gebanagitätas',

# Recent changes
'nchanges'          => '{{PLURAL:$1|votükam|votükams}} $1',
'recentchanges'     => 'Votükams nulik',
'recentchangestext' => 'Su pad at binons votükams nulikün in vüki at.',
'rcnote'            => "Dono {{PLURAL:$1|binon votükam '''1'''|binons votükams '''$1'''}} lätikün {{PLURAL:$2|dela|delas '''$2'''}} lätikün, pänumädöls tü düp: $3.",
'rclistfrom'        => 'Jonolöd votükamis nulik, primölo tü düp $1',
'rcshowhideminor'   => '$1 votükams pülik',
'rcshowhidebots'    => '$1 elis bot',
'rcshowhideliu'     => '$1 gebanis penunädöl',
'rcshowhideanons'   => '$1 gebanis nennemik',
'rcshowhidemine'    => '$1 redakamis obik',
'rclinks'           => 'Jonolöd votükamis lätik $1 ün dels lätik $2<br />$3',
'diff'              => 'dif',
'hist'              => 'jenotem',
'hide'              => 'Klänedolöd',
'show'              => 'Jonolöd',
'minoreditletter'   => 'p',
'newpageletter'     => 'N',
'boteditletter'     => 'b',

# Recent changes linked
'recentchangeslinked'       => 'Votükams teföl',
'recentchangeslinked-title' => 'Votükams tefü pad: $1',

# Upload
'upload'                      => 'Löpükön ragivi',
'uploadbtn'                   => 'Löpükön ragivi',
'uploadtext'                  => "Gebolös fometi dono ad löpükön ragivis. Ad logedön u sukön ragivis ya pelöpükölis, gololös lü [[Special:Imagelist|lised ragivas pelöpüköl]]. Löpükams e moükams padakipons id in  [[Special:Log/upload|jenotalised löpükamas]].

Ad pladön magodi at ini pad semik, gebolös yümi fomätü: 
'''<nowiki>[[{{ns:image}}:File.jpg]]</nowiki>''',
'''<nowiki>[[{{ns:image}}:File.png|alt text]]</nowiki>''' u
'''<nowiki>[[{{ns:media}}:File.ogg]]</nowiki>''' ad yümön stedöfiko ko ragiv.",
'uploadlog'                   => 'jenotalised löpükamas',
'uploadlogpage'               => 'Jenotalised löpükamas',
'uploadlogpagetext'           => 'Dono binon lised ravigalöpükamas nulikün.',
'filename'                    => 'Ragivanem',
'filedesc'                    => 'Plän brefik',
'fileuploadsummary'           => 'Plän brefik:',
'filesource'                  => 'Fon',
'uploadedfiles'               => 'Ragivs pelöpüköl',
'ignorewarnings'              => 'Nedemolöd nunedis alseimik',
'badfilename'                 => 'Ragivanem pevotükon ad "$1".',
'fileexists-forbidden'        => 'Ragiv labü nem at ya dabinon; geikolös e löpükolös ragivi at me nem votik.[[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Ragiv labü nem at ya dabinon in ragivastok kobädik; geikolös e löpükolös ragivi at me nem votik. [[Image:$1|thumb|center|$1]]',
'savefile'                    => 'Dakipolöd ragivi',
'uploadedimage'               => '"[[$1]]" pelöpüköl',
'sourcefilename'              => 'Ragivanem rigik',
'destfilename'                => 'Ragivanem nulik',
'watchthisupload'             => 'Galädolöd padi at',
'filewasdeleted'              => 'Ragiv labü nem at büo pelöpükon e poso pemoükon. Kontrololös eli $1 büä olöpükol oni dönu.',

'license' => 'Dälastad',

# Image list
'imagelist'                 => 'Ragivalised',
'imagelisttext'             => "Dono binon lised '''$1''' {{plural:$1|ragiva|ragivas}} $2 pedilädölas.",
'ilsubmit'                  => 'Sukolöd',
'byname'                    => 'ma nem',
'bydate'                    => 'ma dät',
'bysize'                    => 'ma gretot',
'imgdelete'                 => 'moük',
'imgdesc'                   => 'bepenam',
'imgfile'                   => 'ragiv',
'filehist'                  => 'Jenotem ragiva',
'filehist-current'          => 'anuik',
'filehist-datetime'         => 'Dät/Tim',
'filehist-user'             => 'Geban',
'filehist-dimensions'       => 'Mafots',
'filehist-filesize'         => 'Ragivagret',
'filehist-comment'          => 'Küpet',
'imagelinks'                => 'Yüms',
'linkstoimage'              => 'Pads sököl payümons ko pad at:',
'sharedupload'              => 'Ragiv at binon komunik e kanon pagebön fa proyegs votik.',
'noimage'                   => 'Ragiv labü nem at no dabinon, kanol $1.',
'noimage-linktext'          => 'löpükön oni',
'uploadnewversion-linktext' => 'Löpükön fomami nulik ragiva at',
'imagelist_date'            => 'Dät',
'imagelist_name'            => 'Nem',
'imagelist_user'            => 'Geban',
'imagelist_description'     => 'Bepenam',

# File deletion
'filedelete-nofile' => "'''$1''' no dabinon in {{SITENAME}}.",

# MIME search
'mimesearch' => 'Sukön (MIME)',
'mimetype'   => 'Klad ela MIME:',

# Unwatched pages
'unwatchedpages' => 'Pads no pagalädöls',

# List redirects
'listredirects' => 'Lised lüodükömas',

# Unused templates
'unusedtemplates'     => 'Samafomots no pageböls',
'unusedtemplatestext' => 'Pad at jonon padis valik in nemaspad "samafomot", kels no paninükons in pad votik. Kontrololös, va dabinons yüms votik lü samafomots at büä omoükol onis.',
'unusedtemplateswlh'  => 'yüms votik',

# Random page
'randompage' => 'Pad fädik',

# Random redirect
'randomredirect' => 'Lüodüköm fädik',

# Statistics
'statistics'             => 'Statits',
'sitestats'              => 'Statits {{SITENAME}}',
'userstats'              => 'Gebanastatits',
'sitestatstext'          => "{{PLURAL:\$1|Dabinon pad '''1'''|Dabinons valodo pads '''\$1'''}} in {{SITENAME}}.
Atos ninükon i \"bespikapadis\", padis dö Vükiped it, padis go smalikis (\"sidis\"), lüodükömis, e votikis, kels luveratiko no kanons palelogön as pads ninädilabik.
Atis fakipölo, retons nog {{PLURAL:\$2|pad '''1''', kel luveratiko binon legiko ninädilabik|pads '''\$2''', kels luveratiko binons legiko ninädilabiks}}. 

{{PLURAL:\$8|Ragiv '''1''' pelöpükon|Ragivs '''\$8''' pelöpükons}}.

Ejenons valodo {{PLURAL:\$3|padilogam '''1'''|padilogams '''\$3'''}}, e {{PLURAL:\$4|padiredakam '''1'''|padiredakams '''\$4'''}}, sisä vük at pästiton.
Kludo, zänedo ebinons redakams '''\$5'''  a pad, e logams '''\$6''' a redakam.

Lunot [http://meta.wikimedia.org/wiki/Help:Job_queue vobodaliseda] binon '''\$7'''.",
'userstatstext'          => "Dabinons gebans peregistaröl '''$1''', bevü kels '''$2''' (ü '''$4%''') binons $5.",
'statistics-mostpopular' => 'Pads suvüno palogedöls:',

'disambiguations'      => 'Telplänovapads',
'disambiguationspage'  => 'Template:Telplänov',
'disambiguations-text' => "Pads sököl payümons ad '''telplanövapad'''. Sötons plao payümon lü yeged pötik.<br />Pad palelogon telplänovapad if gebon samafomoti, lü kel payümon pad [[MediaWiki:disambiguationspage]].",

'doubleredirects'     => 'Lüodüköms telik',
'doubleredirectstext' => 'Kedet alik labon yümis lü lüodüköm balid e telid, ed i kedeti balid vödema lüodüköma telid, kel nomiko ninädon padi, ko kel lüodüköm balid söton payümön.',

'brokenredirects'     => 'Lüodüköms dädik',
'brokenredirectstext' => 'Lüodüköms sököl dugons lü pads no dabinöls:',

'withoutinterwiki'        => 'Pads nen yüms bevüpükik',
'withoutinterwiki-header' => 'Pads sököl no yumons lü fomams in püks votik.',

'fewestrevisions' => 'Yegeds labü revids nemödikün',

# Miscellaneous special pages
'nbytes'                  => '{{PLURAL:$1|jölät|jöläts}} $1',
'ncategories'             => '{{PLURAL:$1|klad|klads}} $1',
'nlinks'                  => '{{PLURAL:$1|yüm|yüms}} $1',
'nmembers'                => '{{PLURAL:$1|liman|limans}} $1',
'specialpage-empty'       => 'Pad at vagon.',
'lonelypages'             => 'Pads, lü kels yüms nonik dugons',
'lonelypagestext'         => 'Pads nonik in vüki at peyümons ad pads sököl.',
'uncategorizedpages'      => 'Pads nen klad',
'uncategorizedcategories' => 'Klads nen klad löpikum',
'uncategorizedimages'     => 'Magods nen klad',
'uncategorizedtemplates'  => 'Samafomots nen klad',
'unusedcategories'        => 'Klads no pageböls',
'unusedimages'            => 'Ragivs no pageböls',
'wantedcategories'        => 'Klads mekabik',
'wantedpages'             => 'Pads mekabik',
'mostlinked'              => 'Pads suvüno peyümöls',
'mostlinkedcategories'    => 'Klads suvüno peyümöls',
'mostlinkedtemplates'     => 'Samafomots suvüno pegeböls',
'mostcategories'          => 'Yegeds labü klads mödikün',
'mostimages'              => 'Magods suvüno peyümöls',
'mostrevisions'           => 'Yegeds suvüno perevidöls',
'allpages'                => 'Pads valik',
'prefixindex'             => 'Lised ma foyümots',
'shortpages'              => 'Pads brefik',
'longpages'               => 'Pads lunik',
'deadendpages'            => 'Pads nen yüms lü votiks',
'deadendpagestext'        => 'Pads sököl no labons yümis ad pads votik in vüki at.',
'protectedpages'          => 'Pads pejelöl',
'protectedpagestext'      => 'Pads fovik pejelons e no kanons patöpätükön u paredakön',
'protectedpagesempty'     => 'Pads nonik pejelons',
'listusers'               => 'Gebanalised',
'specialpages'            => 'Pads patik',
'spheading'               => 'Pads patik pro gebans valik',
'restrictedpheading'      => 'Te pro guvans',
'newpages'                => 'Pads nulik',
'newpages-username'       => 'Gebananem:',
'ancientpages'            => 'Pads bäldikün',
'intl'                    => 'Yüms bevüpükik',
'move'                    => 'Topätükön',
'movethispage'            => 'Topätükolöd padi at',
'unusedcategoriestext'    => 'Kladapads sököl dabinons do yeged u klad votik nonik gebon oni.',

# Book sources
'booksources'               => 'Bukafons',
'booksources-search-legend' => 'Sukön bukafonis:',
'booksources-go'            => 'Getolöd',

'categoriespagetext' => 'Klads sököl dabinons in vüki at.',
'groups'             => 'Gebanagrups',
'alphaindexline'     => '$1 jü $2',
'version'            => 'Fomam',

# Special:Log
'specialloguserlabel'  => 'Geban:',
'speciallogtitlelabel' => 'Tiäd:',
'log'                  => 'Jenotaliseds',
'alllogstext'          => 'Kobojonam jenotalisedas löpükamas, moükamas, jelodamas, blokamas e guvanas.
Ad brefükam lisedi, kanoy välön lisedasoti, gebananemi, u padi tefik.',
'logempty'             => 'No dabinons notets in jenotalised at.',

# Special:Allpages
'nextpage'          => 'Pad sököl ($1)',
'prevpage'          => 'Pad büik ($1)',
'allpagesfrom'      => 'Jonolöd padis, primöl me:',
'allarticles'       => 'Yegeds valik',
'allinnamespace'    => 'Pads valik ($1 nemaspad)',
'allnotinnamespace' => 'Pads valik ($1 nemaspad)',
'allpagesprev'      => 'Büik',
'allpagesnext'      => 'Sököl',
'allpagessubmit'    => 'Jonolöd',
'allpagesprefix'    => 'Jonolöd padis labü foyümot:',
'allpagesbadtitle'  => 'Tiäd pegivöl no lonöfon, u ba labon foyümoti vüpükik u vü-vükik. Mögos i, das labon tonatis u malülis no pedälölis ad penön tiädis.',
'allpages-bad-ns'   => '{{SITENAME}} no labon nemaspadi: "$1".',

# Special:Listusers
'listusersfrom' => 'Jonolöd gebanis primölo me:',

# E-mail user
'emailuser'       => 'Penön gebane at',
'emailpage'       => 'Penön gebane',
'emailpagetext'   => 'If gebane at egivon ladeti leäktronik lonöföl in gebanabuükams onik, 
fomet at osedon one penedi bal. Ladet leäktronik in gebanabuükams olik opubon as fonät (el "De:") peneda at, dat getan okanon gepenön.',
'defemailsubject' => 'Ladet leäktronik ela {{SITENAME}}',
'noemailtitle'    => 'Ladet no dabinon',
'noemailtext'     => 'Geban at no egivon ladeti leäktronik lonöföl, ud ebuükon ad no getön penedis de gebans votik.',
'emailfrom'       => 'De el',
'emailto'         => 'Ele',
'emailsubject'    => 'Yegäd',
'emailmessage'    => 'Nun',
'emailsend'       => 'Sedolöd',
'emailccme'       => 'Sedolöd obe kopiedi peneda obik.',
'emailccsubject'  => 'Kopied peneda olik ele $1: $2',
'emailsent'       => 'Pened pesedon',
'emailsenttext'   => 'Pened leäktronik ola pesedon.',

# Watchlist
'watchlist'            => 'Galädalised obik',
'mywatchlist'          => 'Galädalised obik',
'watchlistfor'         => "(tefü '''$1''')",
'nowatchlist'          => 'Labol nosi in galädalised olik.',
'addedwatch'           => 'Peläüköl lä galädalised',
'addedwatchtext'       => "Pad: \"[[:\$1]]\" peläükon lä [[Special:Watchlist|galädalised]] olik.
Votükams fütürik pada at, äsi bespikapada onik, polisedons us, e pad popenon '''me tonats dagik'''  in [[Special:Recentchanges|lised votükamas nulik]] ad fasilükön tuvi ona.

If vilol poso moükön padi de galädalised olik, välolös lä on knopi: „negalädön“.",
'removedwatch'         => 'Pemoükon de galädalised',
'removedwatchtext'     => 'Pad: „[[:$1]]“ pemoükon se galädalised olik.',
'watch'                => 'Galädön',
'watchthispage'        => 'Galädolöd padi at',
'unwatch'              => 'Negalädön',
'watchnochange'        => 'Nonik padas pagalädöl olik peredakon dü period löpo pejonöl.',
'watchlistcontains'    => 'Galädalised olik labon {{PLURAL:$1|padi|padis}} $1.',
'wlshowlast'           => 'Jonolöd: düpis lätik $1, delis lätik $2, $3',
'watchlist-show-bots'  => 'Jonolöd redakamis elas bots',
'watchlist-hide-bots'  => 'Klänolöd redakamis elas bots',
'watchlist-show-own'   => 'Jonolöd redakamis obik',
'watchlist-hide-own'   => 'Klänolöd redakamis obik',
'watchlist-show-minor' => 'Jonolöd redakamis pülik',
'watchlist-hide-minor' => 'Klänolöd redakamis pülik',

'changed' => 'pevotüköl',
'created' => 'pejafon',

# Delete/protect/revert
'deletepage'                  => 'Moükolöd padi',
'confirm'                     => 'Fümedolös',
'excontent'                   => "ninäd äbinon: '$1'",
'excontentauthor'             => "ninäd äbinon: '$1' (e keblünan teik äbinon '[[Special:Contributions/$2|$2]]')",
'exblank'                     => 'pad ävagon',
'confirmdelete'               => 'Fümedolös moükami',
'deletesub'                   => '(Moükölo padi: "$1")',
'historywarning'              => 'Nuned: pad, keli vilol moükön, labon jenotemi:',
'confirmdeletetext'           => 'Primikol ad moükön laidüpiko padi u magodi sa jenotem valik ona. Fümedolös, das desinol ad dunön atosi, das suemol sekis, e das dunol atosi bai [[{{MediaWiki:Policy-url}}]].',
'actioncomplete'              => 'Peledunon',
'deletedtext'                 => 'Pad: "$1" pemoükon;
$2 jonon moükamis nulik.',
'deletedarticle'              => 'Pad: "[[$1]]" pemoükon',
'dellogpage'                  => 'Jenotalised moükamas',
'dellogpagetext'              => 'Dono binon lised moükamas nulikün.',
'deletionlog'                 => 'jenotalised moükamas',
'reverted'                    => 'Pegeükon ad revid büik',
'deletecomment'               => 'Kod moükama',
'deleteotherreason'           => 'Kod votik:',
'deletereasonotherlist'       => 'Kod votik',
'deletereason-dropdown'       => '*Common delete reasons
** Beg lautana
** Copyright violation
** Vandalism',
'rollback'                    => 'Sädunön redakamis',
'rollback_short'              => 'Sädunön mödiki',
'rollbacklink'                => 'sädunön mödiki',
'rollbackfailed'              => 'Sädunam no eplöpon',
'cantrollback'                => 'Redakam no kanon pasädunön; keblünan lätik binon lautan teik pada at.',
'revertpage'                  => 'Redakams ela [[Special:Contributions/$2|$2]] ([[User talk:$2|Bespik]]) pegeükons; pad labon nu fomami ma redakam lätik ela [[User:$1|$1]]',
'protectlogpage'              => 'Jenotalised jelodamas',
'unprotectedarticle'          => 'Pad: "[[$1]]" pesäjelon.',
'confirmprotect'              => 'Fümedolös jeli',
'protectcomment'              => 'Küpet:',
'unprotectsub'                => '(Säjelölo padi: "$1")',
'protect-default'             => '(pebuüköl)',
'protect-level-autoconfirmed' => 'Blokön gebanis no peregistarölis',
'protect-level-sysop'         => 'Te guvans',

# Restrictions (nouns)
'restriction-edit' => 'Redakön',
'restriction-move' => 'Topätükön',

# Undelete
'undelete'                 => 'Jonön padis pemoüköl',
'undeletepage'             => 'Jonön e sädunön padimoükamis',
'viewdeletedpage'          => 'Jonön padis pemoüköl',
'undeletepagetext'         => 'Pads sököl pemoükons ab binons nog in registar: moükam onas kanon pasädunön.
Registar pavagükon periodiko.',
'undeleteextrahelp'        => "Ad sädunön moükami pada lölik, vagükolös bügilis valik e välolös me mugaparat knopi: '''''Sädunolöd moükami'''''. Ad sädunön moükami no lölöfik, välolös me mugaparat bügilis revidas pavipöl, e tän knopi: '''''Sädunolöd moükami'''''. Knop: '''''Vagükolöd vali''''' vagükön küpeti e bügilis valik.",
'undeleterevisions'        => 'revids $1 peregistarons',
'undeletehistory'          => 'If osädunol moükami pada at, revids valik ogepubons in jenotem onik. 
If pad nulik labü tiäd ot pejafon pos moükam at, revids ogepubons in jenotem pada nulik at, e fomam nuik ona no poplaädon itjäfidiko.',
'undeletehistorynoadmin'   => 'Yeged at pemoükon. Kod moükama pajonon dono, kobü pats gebanas, kels iredakons padi at büä pämoükon. Vödem redakamas pemoüköl at gebidon te guvanes.',
'undeletebtn'              => 'Sädunolöd moükami',
'undeletereset'            => 'Vagükolöd vali',
'undeletecomment'          => 'Küpet:',
'undeletedarticle'         => 'Moükam pada: "[[$1]]" pesädunon',
'undeletedrevisions'       => 'Moükam revidas $1 pesädunon',
'undeletedrevisions-files' => 'Moükam revidas $1 e ragiva(s) $2 pesädunon',
'undeletedfiles'           => 'Moükam ragiva(s) $1 pesädunon',
'cannotundelete'           => 'Sädunam moükama no eplöpon. Ba ek ya esädunon moükami at.',
'undeletedpage'            => "<big>'''Moükam pada: $1 pesädunon'''</big>

Logolös [[Special:Log/delete|lisedi moükamas]] if vilol kontrolön moükamis e sädunamis brefabüikis.",

# Namespace form on various pages
'namespace'      => 'Nemaspad:',
'blanknamespace' => '(Cifik)',

# Contributions
'contributions' => 'Gebanakeblünots',
'mycontris'     => 'Keblünots obik',
'contribsub2'   => 'Tefü $1 ($2)',
'nocontribs'    => 'Votükams nonik petuvons me paramets at.',

'sp-contributions-newbies'     => 'Jonolöd te keblünotis kalas nulik',
'sp-contributions-newbies-sub' => 'Tefü kals nulik',
'sp-contributions-blocklog'    => 'Jenotalised blokamas',
'sp-contributions-search'      => 'Sukön keblünotis',
'sp-contributions-username'    => 'Ladet-IP u gebananem:',
'sp-contributions-submit'      => 'Suk',

'sp-newimages-showfrom' => 'Jonolöd magodis nulik, primölo tü düp $1',

# What links here
'whatlinkshere'       => 'Yüms isio',
'whatlinkshere-title' => 'Pads ad $1 yumöls',
'linklistsub'         => '(Yümalised)',
'linkshere'           => "Pads sököl payümons ko '''[[:$1]]''':",
'nolinkshere'         => "Pads nonik peyümons lü '''[[:$1]]'''.",
'isredirect'          => 'lüodükömapad',
'istemplate'          => 'ninükam',
'whatlinkshere-prev'  => '{{PLURAL:$1|büik|büik $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|sököl|sököl $1}}',
'whatlinkshere-links' => '← yüms',

# Block/unblock
'blockip'                  => 'Blokön gebani',
'blockiptext'              => 'Gebolös padi at ad blokön redakamagitäti gebananema u ladeta-IP semikas. Atos söton padunön teiko ad vitön vandalimi, e bai [[{{MediaWiki:Policy-url}}|dunalesets {{SITENAME}}]]. Penolös dono kodi patik pro blokam (a. s., mäniotolös padis pedobüköl).',
'ipaddress'                => 'Ladet-IP',
'ipadressorusername'       => 'Ladet-IP u gebananem',
'ipbexpiry'                => 'Dü',
'ipbreason'                => 'Kod',
'ipbreasonotherlist'       => 'Kod votik',
'ipbanononly'              => 'Blokön te gebanis nen gebananem',
'ipbcreateaccount'         => 'Neletön kalijafi',
'ipbenableautoblock'       => 'Blokön itjäfidiko ladeti-IP lätik fa geban at pegeböli, äsi ladetis-IP fovik valik, yufü kels osteifülon ad redakön',
'ipbsubmit'                => 'Blokön gebani at',
'ipbother'                 => 'Dul votik',
'ipboptions'               => 'düps 2:2 hours,del 1:1 day,dels 3:3 days,vig 1:1 week,vigs 2:2 weeks,mul 1:1 month,muls 3:3 months,muls 6:6 months,yel 1:1 year,laidüp:infinite', # display1:time1,display2:time2,...
'ipbotheroption'           => 'dul votik',
'ipbotherreason'           => 'Kod(s) votik',
'ipbhidename'              => 'Klänedön gebani u ladeti-IP se jenotalised blokamas, blokamalised anuik e gebanalised',
'badipaddress'             => 'Ladet-IP no lonöfon',
'blockipsuccesssub'        => 'Blokam eplöpon',
'blockipsuccesstext'       => '[[Special:Contributions/$1|$1]] peblokon.
<br />Logolös [[Special:Ipblocklist|lisedi ladetas-IP pebloköl]] ad vestigön blokamis.',
'ipb-unblock-addr'         => 'Säblokön eli $1',
'ipb-unblock'              => 'Säblokön gebananemi u ladeti-IP',
'ipb-blocklist-addr'       => 'Logedön blokamis dabinöl tefü el $1',
'ipb-blocklist'            => 'Logedön blokamis dabinöl',
'unblockip'                => 'Säblokön gebani',
'unblockiptext'            => 'Gebolös padi at ad gegivön redakamafägi gebane (u ladete-IP) büo pibloköle.',
'ipusubmit'                => 'Säblokön ladeti at',
'unblocked'                => '[[User:$1|$1]] pesäblokon',
'ipblocklist'              => 'Lised Ladetas-IP e gebananemas peblokölas',
'ipblocklist-submit'       => 'Suk',
'blocklistline'            => '$1, $2 äblokon $3 ($4)',
'infiniteblock'            => 'laidüpo',
'anononlyblock'            => 'te nennemans',
'createaccountblock'       => 'kalijaf peblokon',
'blocklink'                => 'blokön',
'unblocklink'              => 'säblokön',
'contribslink'             => 'keblünots',
'blocklogpage'             => 'Jenotalised blokamas',
'blocklogentry'            => '"[[$1]]" peblokon dü: $2 $3',
'blocklogtext'             => 'Is binon lised gebanablokamas e gebanasäblokamas. Ladets-IP itjäfidiko pebloköls no pajonons. Logolös blokamis e xilis anu lonöfölis in [[Special:Ipblocklist|lised IP-blokamas]].',
'unblocklogentry'          => '$1 pesäblokon',
'block-log-flags-anononly' => 'te gebans nennemik',
'ipb_expiry_invalid'       => 'Blokamadul no lonöfon.',
'ipb_already_blocked'      => '"$1" ya peblokon',

# Developer tools
'databasenotlocked' => 'Vük at no pefärmükon.',

# Move page
'movepage'                => 'Topätükolöd padi',
'movepagetext'            => "Me fomet at kanoy votükön padanemi, ottimo feapladölo jenotemi lölöfik ona disi nem nulik. Tiäd büik ovedon lüodüköm lü tiäd nulik. Yüms lü padatiäd büik no povotükons; kontrolös dabini lüodükömas telik u dädikas. Gididol ad garanön, das yüms blebons lüodükön lü pads, lü kels mutons lüodükön.

Küpälolös, das pad '''no''' potopätükon if ya dabinon pad labü tiäd nulik, bisä vagon u binon lüodüköm e no labon jenotemi. Atos sinifon, das, if pölol, nog kanol gepladön padi usio, kö äbinon büo, e das no kanol pladön padi nulik sui pad ya dabinöl.

<b>NUNED!</b>
Votükam at kanon binön mu staböfik ä no paspetöl pö pad pöpedik. Suemolös, begö! gudiko sekis duna at büä ofövol oni.",
'movearticle'             => 'Topätükolöd padi',
'newtitle'                => 'Lü tiäd nulik',
'move-watch'              => 'Pladolöd padi at ini galädalised',
'movepagebtn'             => 'Topätükolöd padi',
'pagemovedsub'            => 'Topätükam eplöpon',
'movepage-moved'          => '<big>\'\'\'"$1" petopätükon lü "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'Pad labü nem at ya dabinon, u nem fa ol pevälöl no lonöfon.
Välolös nemi votik.',
'talkexists'              => "'''Pad it petopätükon benosekiko, ab bespikapad onik no petopätükon bi ya dabinon pad labü tiäd ona. Ol it balolös onis.'''",
'movedto'                 => 'petöpätükon lü',
'movetalk'                => 'Topätükolöd bespikapadi tefik',
'talkpagemoved'           => 'I bespikapad tefik petopätükon.',
'talkpagenotmoved'        => 'Bespikapad tefik <strong>no</strong> petopätükon.',
'1movedto2'               => '[[$1]] petopätükon lü [[$2]]',
'1movedto2_redir'         => '[[$1]] petopätükon lü [[$2]] vegü lüodüköm',
'movelogpage'             => 'Jenotalised topätükamas',
'movelogpagetext'         => 'Is palisedons pads petopätüköl.',
'movereason'              => 'Kod',
'revertmove'              => 'sädunön',
'delete_and_move'         => 'Moükolöd e topätükolöd',
'delete_and_move_text'    => '==Moükam peflagon==

Yeged nulik "[[$1]]" ya dabinon. Vilol-li moükön oni ad jafön spadi pro topätükam?',
'delete_and_move_confirm' => 'Si! moükolöd padi',
'delete_and_move_reason'  => 'Pemoükon ad jafön spadi pro topätükam',
'selfmove'                => 'Tiäds nulik e bäldik binons ots; pad no kanon patopätükön sui ok it.',

# Export
'export'        => 'Seveigön padis',
'exporttext'    => 'Kanol seveigön vödemi e redakajenotemi padi u pademi patädik gebölo eli XML. Kanons poso panüveigön ini vük votik medü el MediaWiki me Patikos:Nüveigön padi.

Ad seveigön padis, penolös tiädis in penamaspad dono, tiädi bal a kedet, e välolös, va vilol fomami anuik kobü fomams büik valik, ko kedets padajenotema, u te fomami anuik kobü nüns dö redakam lätikün.

Ön jenet lätik, kanol i gebön yümi, a.s.: [[{{ns:special}}:Export/{{int:mainpage}}]] pro pad {{int:mainpage}}.',
'exportcuronly' => 'Ninükolöd te revidi anuik, no jenotemi valik',
'export-submit' => 'Seveigolöd',

# Namespace 8 related
'allmessages'         => 'Sitanuns',
'allmessagesname'     => 'Nem',
'allmessagesdefault'  => 'Vödem rigädik',
'allmessagescurrent'  => 'Vödem nuik',
'allmessagestext'     => 'Is binon lised sitanunas valik lonöföl in nemaspad: Sitanuns.',
'allmessagesfilter'   => 'Te nunanems labü:',
'allmessagesmodified' => 'Jonolöd te pevotükölis',

# Thumbnails
'thumbnail-more' => 'Gretükön',
'filemissing'    => 'Ragiv deföl',

# Special:Import
'import'       => 'Nüveigön padis',
'importstart'  => 'Nüveigölo padis...',
'importnotext' => 'Vödem vagik',

# Import log
'importlogpage' => 'Jenotalised nüveigamas',

# Tooltip help for the actions
'tooltip-pt-userpage'        => 'Gebanapad obik',
'tooltip-pt-mytalk'          => 'Bespiks obik',
'tooltip-pt-preferences'     => 'Buükams obik',
'tooltip-pt-watchlist'       => 'Lised padas, kö galädol tefü votükams',
'tooltip-pt-mycontris'       => 'Lised keblünotas obik',
'tooltip-pt-login'           => 'Binos gudik, ab no bligik, ad nunädön oyi.',
'tooltip-pt-logout'          => 'Senunädön oki',
'tooltip-ca-talk'            => 'Bespik dö ninädapad',
'tooltip-ca-edit'            => 'Kanol redakön padi at. Gebolös, begö! büologedi bü dakip.',
'tooltip-ca-addsection'      => 'Lüükön küpeti bespike at.',
'tooltip-ca-viewsource'      => 'Pad at pejelon. Kanol logedön fonätakoti onik.',
'tooltip-ca-protect'         => 'Jelön padi at',
'tooltip-ca-delete'          => 'Moükön padi at',
'tooltip-ca-move'            => 'Topätükön padi at',
'tooltip-ca-watch'           => 'Lüükolös padi at lü galädalised olik',
'tooltip-ca-unwatch'         => 'Moükön padi at se galädalised olik',
'tooltip-search'             => 'Sukön in {{SITENAME}}',
'tooltip-p-logo'             => 'Cifapad',
'tooltip-n-mainpage'         => 'Visitolös Cifapadi',
'tooltip-n-portal'           => 'Tefü proyek, kio kanol-li dunön, kiplado tuvön dinis',
'tooltip-n-recentchanges'    => 'Lised votükamas nulik in vüki.',
'tooltip-n-randompage'       => 'Lodön padi fädik',
'tooltip-n-help'             => 'Is kanoy tuvön yufi e nünis.',
'tooltip-n-sitesupport'      => 'Stütolös obsi',
'tooltip-t-whatlinkshere'    => 'Lised padas valik, kels yumons isio',
'tooltip-t-contributions'    => 'Logedön keblünotalisedi gebana at',
'tooltip-t-emailuser'        => 'Sedolös penedi gebane at',
'tooltip-t-upload'           => 'Löpükön magodis u ragivis sümik votik',
'tooltip-t-specialpages'     => 'Lised padas patik valik',
'tooltip-ca-nstab-user'      => 'Logön gebanapadi',
'tooltip-ca-nstab-special'   => 'Atos binon pad patik, no kanol redakön oni',
'tooltip-ca-nstab-project'   => 'Logedön proyegapadi',
'tooltip-ca-nstab-image'     => 'Logön padi magoda',
'tooltip-ca-nstab-mediawiki' => 'Logön sitanuni',
'tooltip-ca-nstab-template'  => 'Logön samafomoti',
'tooltip-ca-nstab-help'      => 'Logön yufapadi',
'tooltip-ca-nstab-category'  => 'Logön kladapadi',
'tooltip-minoredit'          => 'Nemön atosi votükami pülik',
'tooltip-save'               => 'Dakipolös votükamis olik',
'tooltip-preview'            => 'Büologed votükamas olik. Gebolös bü dakip, begö!',
'tooltip-diff'               => 'Jonön votükamis olik in vödem at.',
'tooltip-watch'              => 'Lüükön padi at galädalisede olik',

# Stylesheets
'common.css'   => '/** El CSS isio peplädöl pogebon pro padafomäts valik */',
'monobook.css' => '/* El CSS isio pepladöl otefon gebanis padafomäta: Monobook */',

# Scripts
'common.js' => '/* El JavaScript isik alseimik pogebon pro gebans valik pö padilogam valik. */',

# Attribution
'anonymous'        => 'Geban(s) nennemik {{SITENAME}}a',
'siteuser'         => 'Geban ela {{SITENAME}}: $1',
'lastmodifiedatby' => 'Pad at pävotükon lätiküno tü dÜp $1, ün $2, fa el $3.', # $1 date, $2 time, $3 user
'and'              => 'e',
'others'           => 'votiks',
'siteusers'        => 'Geban(s) ela {{SITENAME}}: $1',

# Spam protection
'subcategorycount'       => 'Dabinon{{PLURAL:$1|&nbsp;donaklad bal|s donaklads $1}} in klad at.',
'categoryarticlecount'   => 'Dabinon{{PLURAL:$1|&nbsp;yeged bal|s yegeds $1}} in klad at.',
'category-media-count'   => 'Dabinon{{PLURAL:$1|&nbsp;ragiv bal|s ragivs $1}} in klad at.',
'listingcontinuesabbrev' => '(fov.)',

# Info page
'infosubtitle' => 'Nüns tefü pad',
'numedits'     => 'Redakamanum (pad): $1',
'numtalkedits' => 'Redakamanum (bespikapad): $1',

# Math options
'mw_math_png'    => 'Ai el PNG',
'mw_math_simple' => 'El HTML if go balugik, voto eli PNG',
'mw_math_html'   => 'El HTML if mögos, voto eli PNG',
'mw_math_source' => 'Dakipolöd oni as TeX (pro bevüresodatävöms fomätü vödem)',
'mw_math_modern' => 'Pakomandöl pro bevüresodatävöms nulädik',
'mw_math_mathml' => 'El MathML if mögos (nog sperimänt)',

# Patrol log
'patrol-log-auto' => '(itjäfidik)',

# Image deletion
'deletedrevision' => 'Moükoy revidi bäldik $1.',

# Browsing diffs
'previousdiff' => '← Dif büik',
'nextdiff'     => 'Dif sököl →',

# Media information
'imagemaxsize'   => 'Miedükön magodis su pads magodis bepenöls ad:',
'thumbsize'      => 'Gretot magodüla:',
'file-info-size' => '($1 × $2 pixel, ragivagret: $3, pated MIME: $4)',

# Special:Newimages
'newimages'    => 'Pänotem ragivas nulik',
'showhidebots' => '($1 mäikamenis)',

# Metadata
'metadata-expand'   => 'Jonön patis pluik',
'metadata-collapse' => 'Klänedön patis pluik',

# EXIF tags
'exif-imagewidth'          => 'Vidot',
'exif-imagedescription'    => 'Tiäd magoda',
'exif-artist'              => 'Lautan',
'exif-colorspace'          => 'Kölaspad',
'exif-exposuretime-format' => '$1 sek ($2)',

'exif-orientation-1' => 'Nomik', # 0th row: top; 0th column: left

'exif-componentsconfiguration-0' => 'no dabinon',

'exif-subjectdistance-value' => 'Mets $1',

'exif-meteringmode-255' => 'Votik',

'exif-lightsource-11' => 'Jad',

'exif-focalplaneresolutionunit-2' => 'puids',

'exif-gaincontrol-0' => 'Nonik',

'exif-contrast-0' => 'Nomik',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Milmets a düp',
'exif-gpsspeed-m' => 'Liöls a düp',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'valik',
'imagelistall'     => 'valik',
'watchlistall2'    => 'valikis',
'namespacesall'    => 'valik',
'monthsall'        => 'valik',

# E-mail address confirmation
'confirmemail'          => 'Fümedolös ladeti leäktronik',
'confirmemail_text'     => 'Vük at flagon, das ofümedol ladeti leäktronik ola büä odälon ole ad gebön poti leäktronik.
Välolös me mugaparat knopi dono ad sedön fümedapenedi ladete olik. Pened oninädon yümi labü fümedakot; sökolös yümi ad fümedön, das ladet olik lonöfon.',
'confirmemail_oncreate' => 'Fümedakot pesedon lü ladet leäktronik ola. Kot at no zesüdon ad nunädön oli, ab omutol klavön oni büä okanol gebön ladeti leäktronik ola in vük at.',
'confirmemail_invalid'  => 'Fümedakot no lonöfon. Jiniko binon tu bäldik.',
'confirmemail_success'  => 'Ladet leäktronik ola pefümedon. Nu kanol nunädön oli e juitön vüki at.',
'confirmemail_loggedin' => 'Ladeti leäktronik ola nu pefümedon.',
'confirmemail_error'    => 'Bos no eplöpon pö registaram fümedama olik.',
'confirmemail_body'     => 'Ek, bo ol se ladet-IP: $1, ejafon kali:
"$2" me ladeti leäktronik at lä {{SITENAME}}.

Ad fümedön, das kal at jenöfiko binon olik, ed ad dalön gebön 
poti leäktronik in {{SITENAME}}, sökolös yümi at in bevüresodatävöm olik:

$3

If no binol utan, kel ejafon kali, no sökolös yümi.
Fümedakot at operon lonöfi okik ün $4.',

# Delete conflict
'deletedwhileediting' => 'Nuned: Pad at pemoükon posä äprimol ad redakön oni!',
'confirmrecreate'     => "Geban: [[User:$1|$1]] ([[User talk:$1|talk]]) ämoükon padi at posä äprimol ad redakön oni sekü kod sököl:
: ''$2''
Fümedolös, das jenöfo vilol dönujafön padi at.",
'recreate'            => 'Dönujafön',

# HTML dump
'redirectingto' => 'Lüodükölo lü: [[$1]]...',

# AJAX search
'articletitles' => "Yegeds me ''$1'' primöls",

# Multipage image navigation
'imgmultipageprev' => '← pad büik',
'imgmultipagenext' => 'pad sököl →',
'imgmultigo'       => 'Gololöd!',

# Table pager
'table_pager_next'         => 'Pad sököl',
'table_pager_prev'         => 'Pad büik',
'table_pager_first'        => 'Pad balid',
'table_pager_last'         => 'Pad lätik',
'table_pager_limit_submit' => 'Gololöd',

# Auto-summaries
'autosumm-blank'   => 'Ninäd valik pemoükon se pad',
'autosumm-replace' => "Pad pepläadon me '$1'",
'autoredircomment' => 'Lüodükon lü [[$1]]',
'autosumm-new'     => 'Pad nulik: $1',

# Watchlist editor
'watchlistedit-raw-titles' => 'Tiäds:',

# Watchlist editing tools
'watchlisttools-edit' => 'Logön e redakön galädalisedi',

);

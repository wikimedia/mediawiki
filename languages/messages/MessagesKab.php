<?php
/** Kabyle (Taqbaylit)
 *
 * @addtogroup Language
 */


$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Uslig',
	NS_MAIN             => '',
	NS_TALK             => 'Mmeslay',
	NS_USER             => 'Amseqdac',
	NS_USER_TALK        => 'Amyannan_umsqedac',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => 'Amyannan_n_$1',
	NS_IMAGE            => 'Tugna',
	NS_IMAGE_TALK       => 'Amyannan_n_tugna',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Amyannan_n_MediaWiki',
	NS_TEMPLATE         => 'Talɣa',
	NS_TEMPLATE_TALK    => 'Amyannan_n_talɣa',
	NS_HELP             => 'Tallat',
	NS_HELP_TALK        => 'Amyannan_n_tallat',
	NS_CATEGORY         => 'Taggayt',
	NS_CATEGORY_TALK    => 'Amyannan_n_taggayt'
);

$namespaceAliases = array(
	'Talγa'            => NS_TEMPLATE,
	'Amyannan_n_talγa' => NS_TEMPLATE_TALK,
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Derrer izdayen:',
'tog-highlightbroken'         => 'Ssken izdayen imerẓa <a href="" class="new">akkagi</a> (neɣ: akkagi<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Err tehri ger wawalen kif-kif',
'tog-hideminor'               => 'Ffer ibeddlen ifessasen deg yibeddlen imaynuten',
'tog-extendwatchlist'         => 'Ssemɣer amuɣ uɛessi iwakken ad muqleɣ akk n wayen zemreɣ ad beddleɣ',
'tog-usenewrc'                => 'Sselhu ibeddlen ifessasen (JavaScript)',
'tog-numberheadings'          => 'Izwal ɣur-sen imḍanen mebla ma serseɣ-iten',
'tog-showtoolbar'             => 'Ssken tanuga n dduzan n ubeddel (JavaScript)',
'tog-editondblclick'          => 'Beddel isebtaren asmi wekkiɣ snat n tikwal (JavaScript)',
'tog-editsection'             => 'Eğğ abeddel n umur s yizdayen [beddel]',
'tog-editsectiononrightclick' => 'Eğğ abeddel n umur asmi wekkiɣ ɣef uyeffus<br /> ɣef yizwal n umur (JavaScript)',
'tog-showtoc'                 => 'Ssken agbur (i isebtaren i yesɛan kter n 3 izwalen)',
'tog-rememberpassword'        => 'Cfu ɣef yisem n wemseqdac inu di uselkim-agi',
'tog-editwidth'               => 'Tankult ubeddel tesɛa tehri ettmam',
'tog-watchcreations'          => 'Rnu isebtaren i xelqeɣ di umuɣ n uɛessi inu',
'tog-watchdefault'            => 'Rnu isebtaren i beddleɣ di umuruɣ n uɛessi inu',
'tog-watchmoves'              => 'Rnu isebtaren i smimḍeɣ di umuɣ n uɛessi inu',
'tog-watchdeletion'           => 'Rnu isebtaren i mḥiɣ di umuɣ n uɛessi inu',
'tog-minordefault'            => 'Rcem akk ibeddlen am ibeddlen ifessasen d ameslugen',
'tog-previewontop'            => 'Ssken pre-timeẓriwt uqbel tankult ubeddel',
'tog-previewonfirst'          => 'Ssken pre-timeẓriwt akk d ubeddel amezwaru',
'tog-nocache'                 => 'Kkes lkac n usebtar',
'tog-enotifwatchlistpages'    => 'Azen-iyi-d e-mail asmi yettubeddel asebtar i ttɛassaɣ',
'tog-enotifusertalkpages'     => 'Azen-iyi-d e-mail asmi sɛiɣ izen amaynut',
'tog-enotifminoredits'        => 'Azen-iyi-d e-mail i ibeddlen ifessasen',
'tog-enotifrevealaddr'        => 'Ssken e-mail inu asmi yettwazen email n talɣut',
'tog-shownumberswatching'     => 'Ssken geddac yellan n yimseqdac iɛessasen',
'tog-fancysig'                => 'Eğğ azmul am yettili (mebla azday otomatik)',
'tog-externaleditor'          => 'Sseqdec ambeddel n berra d ameslugen',
'tog-externaldiff'            => 'Sseqdec ambeddel n berra iwakken ad ẓreɣ imgerraden',
'tog-showjumplinks'           => 'Eğğ izdayen "neggez ar"',
'tog-uselivepreview'          => 'Sseqdec pre-timeẓriwt tağiḥbuṭ (JavaScript) (Experimental)',
'tog-forceeditsummary'        => 'Ini-iyi-d asmi sskecmeɣ agzul amecluc',
'tog-watchlisthideown'        => 'Ffer ibeddlen inu seg umuɣ n uɛessi inu',
'tog-watchlisthidebots'       => 'Ffer ibeddlen n iboṭiyen seg umuɣ n uɛessi inu',
'tog-watchlisthideminor'      => 'Ffer ibeddlen ifessasen seg umuɣ n uɛessi inu',
'tog-nolangconversion'        => 'Kkes abeddel n yimeskilen',
'tog-ccmeonemails'            => 'Azen-iyi-d email n wayen uzneɣ i imseqdacen wiyaḍ',
'tog-diffonly'                => 'Ur temliḍ-iyi-d ara ayen yellan seddaw imgerraden',

'underline-always'  => 'Daymen',
'underline-never'   => 'Abaden',
'underline-default' => 'Browser/Explorateur ameslugen',

'skinpreview' => '(Pre-timeẓriwt)',

# Dates
'sunday'        => 'Lḥedd',
'monday'        => 'Letnayen',
'tuesday'       => 'Ttlata',
'wednesday'     => 'Larebɛa',
'thursday'      => 'Lexmis',
'friday'        => 'Lğemɛa',
'saturday'      => 'Ssebt',
'sun'           => 'Lḥedd',
'mon'           => 'Letnayen',
'tue'           => 'Ttlata',
'wed'           => 'Larebɛa',
'thu'           => 'Lexmis',
'fri'           => 'Lğemɛa',
'sat'           => 'Ssebt',
'january'       => 'Yennayer',
'february'      => 'Furar',
'march'         => 'Meɣres',
'april'         => 'Ibrir',
'may_long'      => 'Mayu',
'june'          => 'Yunyu',
'july'          => 'Yulyu',
'august'        => 'Ɣuct',
'september'     => 'Ctember',
'october'       => 'Tuber',
'november'      => 'Wamber',
'december'      => 'Jember',
'january-gen'   => 'Yennayer',
'february-gen'  => 'Furar',
'march-gen'     => 'Meɣres',
'april-gen'     => 'Ibrir',
'may-gen'       => 'Mayu',
'june-gen'      => 'Yunyu',
'july-gen'      => 'Yulyu',
'august-gen'    => 'Ɣuct',
'september-gen' => 'Ctember',
'october-gen'   => 'Tuber',
'november-gen'  => 'Wamber',
'december-gen'  => 'Jember',
'jan'           => 'Yennayer',
'feb'           => 'Ibrir',
'mar'           => 'Meɣres',
'apr'           => 'Ibrir',
'may'           => 'Mayu',
'jun'           => 'Yunyu',
'jul'           => 'Yulyu',
'aug'           => 'Ɣuct',
'sep'           => 'Ctember',
'oct'           => 'Tuber',
'nov'           => 'Wamber',
'dec'           => 'Jember',

# Bits of text used by many pages
'categories'            => 'Taggayin',
'pagecategories'        => '{{PLURAL:$1|Taggayt|Taggayin}}',
'category_header'       => 'Imagraden deg taggayt "$1"',
'subcategories'         => 'Taggayin tizellumin',
'category-media-header' => 'Media deg taggayt "$1"',

'about'          => 'Awal ɣef...',
'article'        => 'Ayen yella deg usebtar',
'newwindow'      => '(teldi deg ttaq amaynut)',
'cancel'         => 'Eğğ-it am yella',
'qbfind'         => 'Af',
'qbbrowse'       => 'Ẓer isebtaren',
'qbedit'         => 'Beddel',
'qbpageoptions'  => 'Asebtar-agi',
'qbpageinfo'     => 'Asatal',
'qbmyoptions'    => 'Isebtaren inu',
'qbspecialpages' => 'Isebtaren usligen',
'moredotdotdot'  => 'Ugar...',
'mypage'         => 'Asebtar inu',
'mytalk'         => 'Amyannan inu',
'anontalk'       => 'Amyannan n IP-yagi',
'navigation'     => 'Ẓer isebtaren',

'errorpagetitle'    => 'Agul',
'returnto'          => 'Uɣal ar $1.',
'tagline'           => 'Seg {{SITENAME}}',
'help'              => 'Tallat',
'search'            => 'Nadi',
'searchbutton'      => 'Nadi',
'go'                => 'Ẓer',
'searcharticle'     => 'Ẓer',
'history'           => 'Amezruy n usebtar',
'history_short'     => 'Amezruy',
'updatedmarker'     => 'yettubeddel segmi tarzeft taneggarut inu',
'info_short'        => 'Talɣut',
'printableversion'  => 'Tasiwelt iwakken ad timprimiḍ',
'permalink'         => 'Azday ur yettbeddil ara',
'print'             => 'Imprimi',
'edit'              => 'Beddel',
'editthispage'      => 'Beddel asebtar-agi',
'delete'            => 'Mḥu',
'deletethispage'    => 'Mḥu asebtar-agi',
'undelete_short'    => 'Fakk amḥay n {{PLURAL:$1|yiwen ubeddel|$1 yibeddlen}}',
'protect'           => 'Ḥrez',
'protect_change'    => 'beddel tiḥḥerzi',
'protectthispage'   => 'Ḥrez asebtar-agi',
'unprotect'         => 'fakk tiḥḥerzi',
'unprotectthispage' => 'Fakk tiḥḥerzi n usebtar-agi',
'newpage'           => 'Asebtar amaynut',
'talkpage'          => 'Mmeslay ɣef usebtar-agi',
'talkpagelinktext'  => 'Mmeslay',
'specialpage'       => 'Asebtar uslig',
'personaltools'     => 'Dduzan inu',
'postcomment'       => 'Azen awennit',
'articlepage'       => 'Ẓer ayen yellan deg usebtar',
'talk'              => 'Amyannan',
'views'             => 'Tuẓrin',
'toolbox'           => 'Dduzan',
'userpage'          => 'Ẓer asebtar n wemseqdac',
'projectpage'       => 'Ẓer asebtar n usenfar',
'imagepage'         => 'Ẓer asebtar n tugna',
'mediawikipage'     => 'Ẓer asebtar n izen',
'templatepage'      => 'Ẓer asebtar n talɣa',
'viewhelppage'      => 'Ẓer asebtar n tallat',
'categorypage'      => 'Ẓer asebtar n taggayin',
'viewtalkpage'      => 'Ẓer amyannan',
'otherlanguages'    => 'S tutlayin tiyaḍ',
'redirectedfrom'    => '(Yettusmimeḍ seg $1)',
'redirectpagesub'   => 'Asebtar usemmimeḍ',
'lastmodifiedat'    => 'Tikelt taneggarut i yettubeddel asebtar-agi $2, $1.', # $1 date, $2 time
'viewcount'         => 'Asebtar-agi yettwakcem {{plural:$1|yiwet tikelt|$1 tikwal}}.',
'protectedpage'     => 'Asebtar yettwaḥerzen',
'jumpto'            => 'Neggez ar:',
'jumptonavigation'  => 'ẓer isebtaren',
'jumptosearch'      => 'anadi',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Awal ɣef {{SITENAME}}',
'aboutpage'         => '{{ns:project}}:Awal ɣef...',
'bugreports'        => "In'aɣ ibugiyen (bug)",
'bugreportspage'    => "{{ns:project}}:In'aɣ ibugiyen",
'copyright'         => 'Tzemreḍ ad twaliḍ ayen yella deg $1.',
'copyrightpagename' => 'Copyright n {{SITENAME}}',
'copyrightpage'     => 'Asenfar:Copyrights',
'currentevents'     => 'Isallen',
'currentevents-url' => 'Isallen',
'disclaimers'       => 'Iɣtalen',
'disclaimerpage'    => '{{ns:project}}:Iɣtalen',
'edithelp'          => 'Tallat deg ubeddel',
'edithelppage'      => '{{ns:help}}:Abeddel',
'faq'               => 'Isteqsiyen',
'faqpage'           => '{{ns:project}}:Isteqsiyen',
'helppage'          => '{{ns:help}}:Agbur',
'mainpage'          => 'Asebtar amenzawi',
'policy-url'        => 'Project:Policy',
'portal'            => 'Awwur n timetti',
'portal-url'        => '{{ns:project}}:Awwur n timetti',
'privacy'           => 'Tudert tusligt',
'privacypage'       => '{{ns:project}}:Tudert tusligt',
'sitesupport'       => 'Efk-aɣ idrimen',
'sitesupport-url'   => '{{ns:project}}:Efk-aɣ idrimen',

'badaccess'        => 'Agul n turagt',
'badaccess-group0' => 'Ur tettalaseḍ ara ad texedmeḍ tigawt i tseqsiḍ.',
'badaccess-group1' => 'Tigawt i steqsiḍ, llan ala imseqdacen n adrum n $1 i zemren a t-xedmen.',
'badaccess-group2' => 'Tigawt i steqsiḍ, llan ala imseqdacen seg yiwen n yiderman n $1 i zemren a t-xedmen.',
'badaccess-groups' => 'Tigawt i steqsiḍ, llan ala imseqdacen seg yiwen n yiderman n $1 i zemren a t-xedmen.',

'versionrequired'     => 'Yessefk ad tesɛiḍ tasiwelt $1 n MediaWiki',
'versionrequiredtext' => 'Yessefk ad tesɛiḍ tasiwelt $1 n MediaWiki iwakken ad tesseqdceḍ asebtar-agi. Ẓer [[Special:Version|tasiwelt usebtar]].',

'retrievedfrom'       => 'Yettwaddem seg "$1"',
'youhavenewmessages'  => 'Ɣur-k $1 ($2).',
'newmessageslink'     => 'Izen amaynut',
'newmessagesdifflink' => 'Abeddel aneggaru',
'editsection'         => 'beddel',
'editold'             => 'beddel',
'editsectionhint'     => 'Beddel amur: $1',
'toc'                 => 'Agbur',
'showtoc'             => 'Ssken',
'hidetoc'             => 'Ffer',
'thisisdeleted'       => 'Ẓer neɣ err $1 am yella?',
'viewdeleted'         => 'Ẓer $1?',
'restorelink'         => '{{PLURAL:$1|Yiwen abeddel yettumḥan|$1 Ibeddlen yettumḥan}}',
'feedlinks'           => 'Asuddem:',
'feed-invalid'        => 'Anaw n usuddem mačči ṣaḥiḥ.',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Amagrad',
'nstab-user'      => 'Asebtar n wemseqdac',
'nstab-media'     => 'Asebtar n media',
'nstab-special'   => 'Uslig',
'nstab-project'   => 'Awal ɣef...',
'nstab-image'     => 'Afaylu',
'nstab-mediawiki' => 'Izen',
'nstab-template'  => 'Talɣa',
'nstab-help'      => 'Tallat',
'nstab-category'  => 'Taggayt',

# Main script and global functions
'nosuchaction'      => 'Tigawt ulac-itt',
'nosuchactiontext'  => 'Wiki ur teɛqil ara tigawt-nni n URL',
'nosuchspecialpage' => 'Asebtar uslig am wagi ulac-it.',
'nospecialpagetext' => 'Tseqdsiḍ ɣef asebtar uslig ulac-it, yella umuɣ n yisebtaren usligen dagi [[Special:Specialpages|umuɣ n yisebtaren usligen]].',

# General errors
'error'                => 'Agul',
'databaseerror'        => 'Agul n database',
'dberrortext'          => 'Yella ugul n tseddast deg database.
Waqila yella bug deg software.
Query n database taneggarut hatt::
<blockquote><tt>$1</tt></blockquote>
seg tawuri  "<tt>$2</tt>".
MySQL yerra-d agul "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Yella ugul n tseddast deg database.
Query n database taneggarut hatt:
"$1"
seg tawuri "$2".
MySQL yerra-d agul "$3: $4"',
'noconnect'            => 'Suref-aɣ! Wiki-yagi tesɛa igna, ur tezmir ara temmeslay akk d database. <br />
$1',
'nodb'                 => 'Ur yezmir ara ad yextar database $1',
'cachederror'          => 'Wagi d alsaru n lkac n usebtar, waqila ur yesɛi ara akk ibeddlen imaynuten.',
'laggedslavemode'      => 'Aɣtal: Ahat asebtar ur yesɛi akk ibeddlen imaynuten.',
'readonly'             => 'Database d tamsekkert',
'enterlockreason'      => 'Ini ayɣer tsekkreḍ database, ini daɣen melmi ara ifukk asekker',
'readonlytext'         => 'Database d tamsekkert, ahat tettuseggem, qrib ad tuɣal-d.

Win (anedbal) isekker-itt yenna-d: $1',
'missingarticle'       => 'Database ur tufi ara aḍris n usebtar i yessefk ad taf, isem-is "$1".

S umata, wagi yeḍran asmi yettumḥa azady n umezruy/umgerrad ar asebtar.

Lukan mačči akka, waqila yella bug deg software.
G leɛnayek, ini-yas-t-id i unedbal, ur tettuḍ ara ad tefkiḍ tansa n URL.',
'readonly_lag'         => 'Database d tamsekkert (weḥdes) axaṭer kra n serveur ɛeṭṭlen',
'internalerror'        => 'Agul zdaxel',
'filecopyerror'        => 'Ur yezmir ara yexdem alsaru n ufaylu "$1" ar "$2".',
'filerenameerror'      => 'Ur yezmir ara ad ibeddel isem ufaylu "$1" ar "$2".',
'filedeleteerror'      => 'Ur yezmir ara ad yemḥu afaylu "$1".',
'filenotfound'         => 'Ur yezmir ara ad yaf afaylu "$1".',
'unexpected'           => 'Agul: "$1"="$2".',
'formerror'            => 'Agul: ur yezmir ara ad yazen talɣa',
'badarticleerror'      => 'Ur yezmir ara yexdem tigawt-agi deg usebtar-agi.',
'cannotdelete'         => 'Ur yezmir ara ad yemḥu asebtar neɣ afaylu i tebɣiḍ. (Ahat amdan wayeḍ yemḥa-t.)',
'badtitle'             => 'Azwel ur yelhi',
'badtitletext'         => 'Asebtar i testeqsiḍ fell-as mačči ṣaḥiḥ, d ilem, neɣ yella agul deg uzday seg wikipedia s tutlayt tayeḍ neɣ deg uzday n wiki nniḍen. Ahat tesɛa asekkil ur yezmir ara ad yettuseqdac deg uzwel.',
'perfdisabled'         => 'Suref-aɣ! aḍaɣar-agi ur yettuseqdac ara tura axaṭer iɛeṭṭel aṭas database.',
'perfcached'           => 'Talɣut deg ukessar seg lkac u waqila mačči d tasiwelt taneggarut.',
'perfcachedts'         => 'Talɣut deg ukessar seg lkac, tasiwelt taneggarut n wass $1.',
'querypage-no-updates' => 'Ibeddlen n usebtar-agi ur banen ara tura. Tilɣa ines qrib ad banen-d.',
'wrong_wfQuery_params' => 'Imsektayen mačči ṣaḥiḥ deg wfQuery()<br />
Tawuri: $1<br />
Query: $2',
'viewsource'           => 'Ẓer aɣbalu',
'viewsourcefor'        => 'n $1',
'protectedpagetext'    => 'Asebtar-agi d amsekker.',
'viewsourcetext'       => 'Tzemreḍ ad twaliḍ u txedmeḍ alsaru n uɣbalu n usebtar-agi:',
'protectedinterface'   => 'Asebtar-agi d amsekker axaṭer yettuseqdac i weḍris n software.',
'editinginterface'     => "'''Aɣtal:''' Aqla-k tettbeddileḍ asebtar i yettuseqdac i weḍris n software. Tagmett n software i ẓren yimseqdacen wiyaḍ ad tbeddel akk d ibeddlen inek.",
'sqlhidden'            => '(Query n SQL tettwaffer)',
'cascadeprotected'     => 'Asebtar-agi yettwaḥrez seg ubeddil, axaṭer yettusekcem deg isebtaren i ttwaḥerzen ula d nutni (acercur), ahaten:',

# Login and logout pages
'logouttitle'                => 'Tuffɣa',
'logouttext'                 => '<strong>Tura teffɣeḍ.</strong><br />
Tzemreḍ ad tesseqdceḍ {{SITENAME}} d udrig, neɣ tzemreḍ ad tkecmeḍ daɣen s yisem n wemseqdac inek (neɣ nniḍen). Kra n yisebtaren zemren ad sskanen belli mazal-ik s yisem n wemseqdac inek armi temḥuḍ lkac.',
'welcomecreation'            => '== Anṣuf yis-k, $1! ==

Isem n wemseqdac inek yettwaxleq. Ur tettuḍ ara ad tbeddleḍ Isemyifiyen n {{SITENAME}} inek.',
'loginpagetitle'             => 'Takcemt',
'yourname'                   => 'Isem n wemseqdac',
'yourpassword'               => 'Awal n tbaḍnit',
'yourpasswordagain'          => 'Ɛiwed ssekcem awal n tbaḍnit',
'remembermypassword'         => 'Cfu ɣef wawal n tbaḍnit inu di uselkim-agi.',
'yourdomainname'             => 'Taɣult inek',
'externaldberror'            => 'Yella ugul aberrani n database neɣ ur tettalaseḍ ara ad tbeddleḍ isem an wemseqdac aberrani inek.',
'loginproblem'               => '<b>Yella ugur akk d ukcam inek.</b><br />Ɛreḍ daɣen!',
'alreadyloggedin'            => '<strong>A(y) $1, tkecmeḍ yagi!</strong><br />',
'login'                      => 'Kcem',
'loginprompt'                => 'Yessefk ad teğğiḍ ikukiyen (cookies) iwakken ad tkecmeḍ ar {{SITENAME}}.',
'userlogin'                  => 'Kcem / Xleq isem n wemseqdac',
'logout'                     => 'Ffeɣ',
'userlogout'                 => 'Ffeɣ',
'notloggedin'                => 'Ur tekcimeḍ ara',
'nologin'                    => 'Ur tesɛiḍ ara isem n wemseqdac? $1.',
'nologinlink'                => 'Xleq isem n wemseqdac',
'createaccount'              => 'Xleq isem n wemseqdac',
'gotaccount'                 => 'Tesɛiḍ yagi isem n wemseqdac? $1.',
'gotaccountlink'             => 'Kcem',
'createaccountmail'          => 's e-mail',
'badretype'                  => 'Awal n tbaḍnit amezwaru d wis sin mačči d kif-kif.',
'userexists'                 => 'Isem n wemseqdac yeddem-as amdan wayeḍ. Fren yiwen nniḍen.',
'youremail'                  => 'E-mail *:',
'username'                   => 'Isem n wemseqdac:',
'uid'                        => 'Amseqdac ID:',
'yourrealname'               => 'Isem n ṣṣeḥ *:',
'yourlanguage'               => 'Tutlayt:',
'yourvariant'                => 'Ameskil',
'yournick'                   => 'Isem wis sin (mačči d amenṣib):',
'badsig'                     => 'Azmul mačči d ṣaḥiḥ; Ssenqed tags n HTML.',
'prefs-help-realname'        => '* Isem n ṣṣeḥ (am tebɣiḍ): ma textareḍ a t-tefkeḍ, ad yettuseqdac iwakken ad snen medden anwa yura tikkin inek.',
'loginerror'                 => 'Agul n ukcam',
'prefs-help-email'           => '* E-mail (am tebɣiḍ): Teğği imseqdacen wiyaḍ a k-aznen email mebla ma ẓren tansa email inek.',
'nocookiesnew'               => 'Isem n wemseqdac-agi yettwaxleq, meɛna ur tekcimeḍ ara. {{SITENAME}} yesseqdac ikukiyen (cookies) iwakken ad tkecmeḍ. Tekseḍ ikukiyen-nni. Eğğ-aten, umbeɛd kecm s yisem n wemseqdac akk d wawal n tbaḍnit inek.',
'nocookieslogin'             => '{{SITENAME}} yesseqdac ikukiyen (cookies) iwakken ad tkecmeḍ. Tekseḍ ikukiyen-nni. Eğğ-aten iwakken ad tkecmeḍ.',
'noname'                     => 'Ur tefkiḍ ara isem n wemseqdac ṣaḥiḥ.',
'loginsuccesstitle'          => 'Tkecmeḍ!',
'loginsuccess'               => "'''Tkecmeḍ ar {{SITENAME}} s yisem n wemseqdac \"\$1\".'''",
'nosuchuser'                 => 'Ulac isem n wemseqdac s yisem "$1". Ssenqed tira n yisem-nni, neɣ xelq isem n wemseqdac amaynut.',
'nosuchusershort'            => 'Ulac isem n wemseqdac s yisem "$1". Ssenqed tira n yisem-nni.',
'nouserspecified'            => 'Yessefk ad tefkeḍ isem n wemseqdac.',
'wrongpassword'              => 'Awal n tbaḍnit ɣaleṭ. Ɛreḍ daɣen.',
'wrongpasswordempty'         => 'Awal n tbaḍnit ulac-it. Ɛreḍ daɣen.',
'mailmypassword'             => 'Awal n tbaḍnit n e-mail',
'passwordremindertitle'      => 'Asmekti n wawal n tbaḍnit seg {{SITENAME}}',
'passwordremindertext'       => 'Amdan (waqila d kečč, seg tansa IP $1)
yesteqsa iwakken a nazen awal n tbaḍnit amaynut i {{SITENAME}} ($4).
Awal n tbaḍnit i wemseqdac "$2" yuɣal-d tura "$3".
Mliḥ lukan tkecmeḍ u tbeddleḍ awal n tbaḍnit tura.

Lukan mačči d kečč i yesteqsan neɣ tecfiḍ ɣef awal n tbaḍnit, tzemreḍ ad tkemmleḍ mebla ma tbeddleḍ awal n tbaḍnit.',
'noemail'                    => '"$1" ur yesɛi ara email.',
'passwordsent'               => 'Awal n tbaḍnit amaynut yettwazen i emal inek, aylaw n "$1".
G leɛnaya-k, kcem tikelt nniḍen yis-s.',
'blocked-mailpassword'       => 'Tansa n IP inek tɛekkel, ur tezmireḍ ara ad txedmeḍ abeddel,
ur tezmireḍ ara ad tesɛuḍ awal n tbaḍnit i tettuḍ.',
'eauthentsent'               => 'Yiwen e-mail yettwazen-ak iwakken ad tsenteḍ.
Qbel kulci, ḍfer ayen yenn-ak deg e-mail,
iwakken ad tbeyyneḍ belli tansa n email inek.',
'throttled-mailpassword'     => 'Asmekti n wawal n tbaḍnit yettwazen yagi deg $1 sswayeɛ i iɛeddan. Asmekti n wawal n tbaḍnit yettwazen tikelt kan mkul $1 swayeɛ.',
'mailerror'                  => 'Agul asmi yettwazen e-mail: $1',
'acct_creation_throttle_hit' => 'Surf-aɣ, txelqeḍ aṭas n yismawen n wemseqdac ($1). Ur tettalaseḍ ara ad txelqeḍ kter.',
'emailauthenticated'         => 'Tansa e-mail inek tettuɛqel deg $1.',
'emailnotauthenticated'      => 'Tansa e-mail inek mazal ur tettuɛqel. Ḥedd e-mail ur ttwazen i ulaḥedd n iḍaɣaren-agi.',
'noemailprefs'               => 'Efk tansa e-mail iwakken ad leḥḥun iḍaɣaren-nni.',
'emailconfirmlink'           => 'Sentem tansa e-mail inek',
'invalidemailaddress'        => 'Tansa e-mail-agi ur telhi, ur tesɛi ara taseddast n lɛali. Ssekcem tansa e-mail s taseddast n lɛali neɣ ur tefkiḍ acemma.',
'accountcreated'             => 'Isem n wemseqdac yettwaxleq',
'accountcreatedtext'         => 'Isem n wemseqdac i $1 yettwaxleq.',

# Password reset dialog
'resetpass'               => 'Iɛawed awal n tbaḍnit',
'resetpass_announce'      => 'Tkecmeḍ s ungal yettwazen-ak s e-mail (ungal-nni qrib yemmut). Iwekken tkemmleḍ, yessefk ad textareḍ awal n tbaḍnit amaynut dagi:',
'resetpass_text'          => '<!-- Rnu aḍris dagi -->',
'resetpass_header'        => 'Ɛiwed awal n tbaḍnit',
'resetpass_submit'        => 'Eg awal n tbaḍnit u kcem',
'resetpass_success'       => 'Awal n tbaḍnit yettubeddel! Qrib ad tkecmeḍ...',
'resetpass_bad_temporary' => 'Ungal mačči d ṣaḥiḥ. Ahat tbeddleḍ awal n tbaḍnit inek neɣ tetseqsiḍ ɣef wawal n tbaḍnit amaynut.',
'resetpass_forbidden'     => 'Ur tezmireḍ ara ad tbeddleḍ awal n tbaḍnit deg wiki-yagi',
'resetpass_missing'       => 'Ulac talɣut.',

# Edit page toolbar
'bold_sample'     => 'Aḍris aberbuz',
'bold_tip'        => 'Aḍris aberbuz',
'italic_sample'   => 'Aḍris aṭalyani',
'italic_tip'      => 'Aḍris aṭalyani',
'link_sample'     => 'Azwel n uzday',
'link_tip'        => 'Azday zdaxel',
'extlink_sample'  => 'http://www.amedya.com azwel n uzday',
'extlink_tip'     => 'Azday aberrani (cfu belli yessefk at tebduḍ s http://)',
'headline_sample' => 'Aḍris n uzwel azellum',
'headline_tip'    => 'Aswir 2 n uzwel azellum',
'math_sample'     => 'Ssekcem tasemselt dagi',
'math_tip'        => 'Tasemselt tusnakt (LaTeX)',
'nowiki_sample'   => 'Ssekcem aḍris mebla taseddast n wiki dagi',
'nowiki_tip'      => 'Ttu taseddast n wiki',
'image_sample'    => 'Amedya.jpg',
'image_tip'       => 'Tugna yettussekcmen',
'media_sample'    => 'Amedya.ogg',
'media_tip'       => 'Azday n ufaylu media',
'sig_tip'         => 'Azmul inek s uzemz',
'hr_tip'          => 'Ajerriḍ aglawan (ur teččerɛiḍ ara)',

# Edit pages
'summary'                   => 'Agzul',
'subject'                   => 'Asentel/Azwel azellum',
'minoredit'                 => 'Wagi d abeddel afessas',
'watchthis'                 => 'Ɛass asebtar-agi',
'savearticle'               => 'Beddel asebtar',
'preview'                   => 'Pre-Ẓer',
'showpreview'               => 'Ssken pre-timeẓriwt',
'showlivepreview'           => 'Pre-timeẓriwt tağiḥbuṭ',
'showdiff'                  => 'Ssken ibeddlen',
'anoneditwarning'           => "'''Aɣtal:''' Ur tkecmiḍ ara. Tansa IP inek ad tettusmekti deg amezruy n usebtar-agi.",
'missingsummary'            => "'''Ur tettuḍ ara:''' Ur tefkiḍ ara azwel i ubeddel inek. Lukan twekkiḍ ''Smekti'' tikelt nniḍen, abeddel inek ad yettusmekti mebla azwel.",
'missingcommenttext'        => 'Ssekcem awennit deg ukessar.',
'missingcommentheader'      => "'''Ur tettuḍ ara:''' Ur tefkiḍ ara azwel-azellum i ubeddel inek. Lukan twekkiḍ ''Smekti'' tikelt nniḍen, abeddel inek ad yettusmekti mebla azwel-azellum.",
'summary-preview'           => 'Pre-timeẓriwt n ugzul',
'subject-preview'           => 'Pre-timeẓriwt asentel/azwel azellum',
'blockedtitle'              => 'Amseqdac iɛekkel',
'blockedtext'               => "<big>'''Isem n wemseqdac neɣ tansa n IP inek ɛekkelen.'''</big>

$1 iɛekkel-it u yenna-d ''$2''.

Tzemreḍ ad tmeslayeḍ akk d $1 neɣ [[{{MediaWiki:grouppage-sysop}}|anedbal]] nniḍen iwakken ad tsmelayem ɣef uɛekkil-nni.
Lukan ur tefkiḍ ara email saḥih deg [[Special:Preferences|isemyifiyen n wemseqdac]], ur tezmireḍ ara ad tazneḍ email. Tansa n IP inek n tura d $3, ID n uɛekkil d #$5. Smekti-ten u fka-ten i unedbal-nni.",
'blockedoriginalsource'     => "Aɣablu n '''$1''' hat deg ukessar:",
'blockededitsource'         => "Aḍris n '''ubeddel inek''' i '''$1''' hat deg ukessar:",
'whitelistedittitle'        => 'Yessefk ad tkecmeḍ iwakken ad tbeddleḍ',
'whitelistedittext'         => 'Yessefk ad $1 iwakken ad tbeddleḍ isebtaren.',
'whitelistreadtitle'        => 'Yessefk ad tkecmeḍ iwakken ad teqqareḍ',
'whitelistreadtext'         => 'Yessefk ad [[Special:Userlogin|tkecmeḍ]] iwakken ad teqqareḍ isebtaren.',
'whitelistacctitle'         => 'Ur tettalaseḍ ara ad txelqeḍ isem n wemseqdac',
'whitelistacctext'          => 'Aakken ad txelqeḍ isem n wemseqdac deg wiki-yagi yessefk ad [[Special:Userlogin|tkecmeḍ]] u tesɛa izerfan usligen.',
'confirmedittitle'          => 'Yessef ad tsentmeḍ e-mail inek iwakken ad tbeddleḍ',
'confirmedittext'           => 'Yessefk ad tsentmeḍ tansa e-mail inek uqbel abeddel. Xtar tansa e-mail di [[Special:Preferences|isemyifiyen n wemseqdac]].',
'nosuchsectiontitle'        => 'Amur ulac-it',
'nosuchsectiontext'         => 'Tɛerḍeḍ ad tbeddleḍ amur ulac-it. Ulac amur am akka deg usebtar $1.',
'loginreqtitle'             => 'Yessefk ad tkecmeḍ',
'loginreqlink'              => 'Kcem',
'loginreqpagetext'          => 'Yessefk $1 iwakken ad teẓriḍ isebtaren wiyaḍ.',
'accmailtitle'              => 'Awal n tbaḍnit yettwazen.',
'accmailtext'               => 'Awal n tbaḍnit n "$1" yettwazen ar $2.',
'newarticle'                => '(Amaynut)',
'newarticletext'            => 'Tḍefreḍ azday ɣer usebtar mazal ma yettwaxleq.
Akken ad txelqeḍ asebtar-nni, aru deg tankult i tella deg ukessar
(ẓer [[{{MediaWiki:helppage}}|asebtar n tallat]] akken ad tessneḍ kter).
Ma tɣelṭeḍ, wekki kan ɣef tqeffalt "Back/Précédent" n browser/explorateur inek.',
'anontalkpagetext'          => "----''Wagi d asebtar n umyennan n wemseqdac adrig. Ihi, yessef ad as nefk ID, nesseqdac tansa n IP ines akken a t-neɛqel. Tansa n IP nni ahat tettuseqdac sɣur aṭṭas n yimdanen. Lukan ula d kečč aqla-k amseqdac adrig u ur tebɣiḍ ara ad tettwabcreḍ izen am wigini, ihi [[Special:Userlogin|xleq isem n wemseqdac neɣ kcem]].''",
'noarticletext'             => 'Ulac aḍris deg usebtar-agi, tzemreḍ ad [[Special:Search/{{PAGENAME}}|tnadiḍ ɣef uzwel n usebtar-agi]] deg isebtaren wiyaḍ neɣ [{{fullurl:{{FULLPAGENAME}}|action=edit}} tettbeddileḍ asebtar-agi].',
'clearyourcache'            => "'''Tamawt:''' Beɛd asmekti, ahat yessefk ad temḥuḍ lkac n browser/explorateur inek akken teẓriḍ ibeddlen. '''Mozilla / Firefox / Safari:''' qqim twekkiḍ ''Shift'' u wekki ɣef ''Reload/Recharger'', neɣ wekki ɣef ''Ctrl-Shift-R'' (''Cmd-Shift-R'' deg Apple Mac); '''IE:''' qqim twekkiḍ ɣef ''Ctrl'' u wekki ɣef ''Refresh/Actualiser'', neɣ wekki ɣef ''Ctrl-F5''; '''Konqueror:''': wekki kan ɣef taqeffalt ''Reload'', neɣ wekki ɣef ''F5''; '''Opera''' yessefk ad tesseqdceḍ ''Tools→Preferences/Outils→Préférences'' akken ad temḥud akk lkac.",
'usercssjsyoucanpreview'    => "<strong>Tixidest:</strong> Sseqdec taqeffalt 'Ssken pre-timeẓriwt' iwakken ad tɛerḍeḍ CSS/JS amynut inek uqbel ma tesmektiḍ.",
'usercsspreview'            => "'''Smekti belli aql-ak twaliḍ CSS inek kan, mazal ur yettusmekti ara!'''",
'userjspreview'             => "'''Smekti belli aql-ak tɛerḍeḍ JavaScript inek kan, mazal ur yettusmekti ara!'''",
'userinvalidcssjstitle'     => '\'\'\'Aɣtal:\'\'\' Aglim "$1" ulac-it. Ur tettuḍ ara belli isebtaren ".css" d ".js" i txedmeḍ sseqdacen azwel i yesɛan isekkilen imecṭuḥen, s umedya: {{ns:user}}:Foo/monobook.css akk d {{ns:user}}:Foo/Monobook.css.',
'updated'                   => '(Yettubeddel)',
'note'                      => '<strong>Tamawt:</strong>',
'previewnote'               => '<strong>Tagi d pre-timeẓriwt kan, ibeddlen mazal ur ttusmektin ara!</strong>',
'previewconflict'           => 'Pre-timeẓriwt-agi tesskan aḍris i yellan deg d assawen ma tebɣiḍ a tt-tesmektiḍ.',
'session_fail_preview'      => '<strong>Suref-aɣ! ur nezmir ara a nesmekti abeddil inek axaṭer yella ugur.
G leɛnayek ɛreḍ tikelt nniḍen. Lukan mazal yella ugur, ffeɣ umbeɛd kcem.</strong>',
'session_fail_preview_html' => "<strong>Suref-aɣ! ur nezmir ara a nesmekti abeddel inek axaṭer yella ugur.</strong>

''Awaṭer wiki-yagi teğğa HTML, teffer pre-timeẓriwt akken teğğanez antag n JavaScript.''

<strong>Lukan abeddel agi d aḥeqqani, g leɛnayek ɛreḍ tikelt nniḍen.. Lukan mazal yella ugur, ffeɣ umbeɛd kcem.</strong>",
'importing'                 => 'Asekcam n $1',
'editing'                   => 'Abeddel n $1',
'editinguser'               => 'Abeddel n wemseqdac <b>$1</b>',
'editingsection'            => 'Abeddel n $1 (amur)',
'editingcomment'            => 'Abeddel n $1 (awennit)',
'editconflict'              => 'Amennuɣ deg ubeddel: $1',
'explainconflict'           => 'Amdan nniḍen ibeddel asebtar-agi asmi telliḍ tettbeddileḍ.
Aḍris n d asawen yesɛa asebtar am yewğed tura.
Ibeddlen inek ahaten deg ukessar.
Yesfek ad txelṭeḍ ibeddlen inek akk usebtar i yellan.
<b>Ala</b> aḍris n d asawen i yettusmekta asmi twekkiḍ "Smekti asebtar".<br />',
'yourtext'                  => 'Aḍris inek',
'storedversion'             => 'Tasiwelt yettusmketen',
'nonunicodebrowser'         => '<strong>AƔTAL: Browser/Explorateur inek ur yebil ara unicode. Nexdem akken ad tzemreḍ ad tbeddleḍ mebla amihi: isekkilin i mačči ASCII ttbanen deg tankult ubeddel s ungilen hexadecimal.</strong>',
'editingold'                => '<strong>AƔTAL: Aqlak tettbeddileḍ tasiwelt taqdimt n usebtar-agi.
Ma ara t-tesmektiḍ, akk ibeddlen i yexdmen seg tasiwelt-agi ruḥen.</strong>',
'yourdiff'                  => 'Imgerraden',
'copyrightwarning'          => 'Ssen belli akk tikkin deg {{SITENAME}} hatent ttwaznen seddaw $2 (Ẓer $1 akken ad tessneḍ kter). Lukan ur tebɣiḍ ara aru inek yettubeddel neɣ yettwazen u yettwaru deg imkanen nniḍen, ihi ur t-tazneḍ ara dagi.<br />
Aqlak teggaleḍ belli tureḍ wagi d kečč, neɣ teddmiḍ-t seg taɣult azayez neɣ iɣbula tilelliyin.
<strong>UR TEFKIḌ ARA AXDAM S COPYRIGHT MEBLA TURAGT!</strong>',
'copyrightwarning2'         => 'Ssen belli akk tikkin deg {{SITENAME}} zemren ad ttubeddlen neɣ ttumḥan sɣur imdanen wiyaḍ. Lukan ur tebɣiḍ ara aru inek yettubeddel neɣ yettwazen u yettwaru deg imkanen nniḍen, ihi ur t-tazneḍ ara dagi.<br />
Aqlak teggaleḍ belli tureḍ wagi d kečč, neɣ teddmiḍ-t seg taɣult azayez neɣ iɣbula tilelliyin (ẓer $1 akken ad tessneḍ kter).
<strong>UR TEFKIḌ ARA AXDAM S COPYRIGHT MEBLA TURAGT!</strong>',
'longpagewarning'           => '<strong>AƔTAL: Asebtar-agi yesɛa $1 kilobytes/kilooctets; kra n browsers/explorateur ur zemren ara ad beddlen isebtaren i yesɛan 32kB/ko neɣ kter.
G leɛnayek frec asebtar-nni.</strong>',
'longpageerror'             => '<strong>AGUL: Aḍris i tefkiḍ yesɛa $1 kB/ko, tiddi-yagi kter n $2 kB/ko, ur yezmir ara ad yesmekti.</strong>',
'readonlywarning'           => '<strong>AƔTAL: Database d tamsekker akken ad teddwaxdem,
ihi ur tezmireḍ ara ad tesmektiḍ ibeddlen inek tura. Smekti aḍris inek
deg afaylu nniḍen akken tesseqdceḍ-it umbeɛd.</strong>',
'protectedpagewarning'      => '<strong>AƔTAL:  Asebtar-agi yettwaḥrez, ala inedbalen i zemren a t-beddlen</strong>',
'semiprotectedpagewarning'  => "'''Tamawt:''' Asebtar-agi yettwaḥrez, ala imseqdacen i yesɛan isem n wemseqdac i zemren a t-beddlen.",
'cascadeprotectedwarning'   => "'''Aɣtal:''' Asebtar-agi iɛekkel akken ad zemren ala sysop i t-beddlen, axaṭer yettwassekcem deg isebtaren i yettwaḥerzen agi (acercur):",
'templatesused'             => 'Talɣiwin ttuseqdacen deg usebtar-agi:',
'templatesusedpreview'      => 'Talɣiwin ttuseqdacen deg pre-timeẓriwt-agi:',
'templatesusedsection'      => 'Talɣiwin ttuseqdacen deg amur-agi:',
'template-protected'        => '(yettwaḥrez)',
'template-semiprotected'    => '(nnefṣ-yettwaḥrez)',
'edittools'                 => '<!-- Aḍris yettbanen-d seddaw talɣa n ubeddil d uzen. -->',
'nocreatetitle'             => 'Axleq n yisebtaren meḥdud',
'nocreatetext'              => 'Adeg in internet agi iḥedd axleq n yisebtaren imaynuten.
Tzemreḍ ad d-uɣaleḍ u tbeddleḍ asebtar i yellan, neɣ ad [[Special:Userlogin|tkecmeḍ neɣ ad txelqeḍ isem n wemseqdac]].',

# "Undo" feature
'undo-success' => 'Tzemreḍ ad tessefsuḍ abeddil. Ssenqed asidmer akken ad tessneḍ ayen tebɣiḍ ad txdmeḍ d ṣṣeḥ, umbeɛd smekti ibeddlen u tkemmleḍ ad tessefsuḍ abeddil.',
'undo-failure' => 'Ur yezmir ara yessefu abeddel axaṭer yella amennuɣ abusari deg ubeddel.',
'undo-summary' => 'Ssefsu tasiwelt $1 sɣur [[Special:Contributions/$2|$2]] ([[User talk:$2|Meslay]])',

# Account creation failure
'cantcreateaccounttitle' => 'Ur yezmir ara ad yexleq isem n wemseqdac',
'cantcreateaccounttext'  => 'Yeɛkel axlaq n yisem n wemseqdac n tansa n IP agi : (<b>$1</b>). 
Ahat llan aṭas n yimidanen icerrɛen seg lakul inek neɣ provider inek.',

# History pages
'revhistory'          => 'Amezruy n tsiwelt',
'viewpagelogs'        => 'Ẓer aɣmis n usebtar-agi',
'nohistory'           => 'Ulac amezruy n yibeddlen i usebtar-agi.',
'revnotfound'         => 'Ur yezmir ara ad yaf tasiwelt',
'revnotfoundtext'     => 'Tasiwelt taqdimt n usebtar-agi i testeqsiḍ ulac-it.
Ssenqed URL i tesseqdac.',
'loadhist'            => 'Assisi n umezruy n usebtar',
'currentrev'          => 'Tasiwelt n tura',
'revisionasof'        => 'Tasiwelt n wass $1',
'revision-info'       => 'Tasiwelt n wass $1 sɣur $2',
'previousrevision'    => '←Tasiwelt taqdimt',
'nextrevision'        => 'Tasiwelt tamaynut→',
'currentrevisionlink' => 'Tasiwelt n tura',
'cur'                 => 'tura',
'next'                => 'ameḍfir',
'last'                => 'amgirred',
'orig'                => 'ameẓwer',
'page_first'          => 'amezwaru',
'page_last'           => 'aneggaru',
'histlegend'          => 'Axtiri n umgerrad: rcem tankulin akken ad teẓreḍ imgerraden ger tisiwal u wekki ɣef enter/entrée neɣ ɣef taqeffalt deg ukessar.<br />
Tabadut: (tura) = amgirred akk d tasiwelt n tura,
(amgirred) = amgirred akk d tasiwelt ssabeq, M = abeddel afessas.',
'deletedrev'          => '[yettumḥa]',
'histfirst'           => 'Tikkin timezwura',
'histlast'            => 'Tikkin tineggura',
'historysize'         => '($1 bytes/octets)',
'historyempty'        => '(amecluc)',

# Revision feed
'history-feed-title'          => 'Amezruy n tsiwelt',
'history-feed-description'    => 'Amezruy n tsiwelt n usebtar-agi deg wiki',
'history-feed-item-nocomment' => '$1 deg $2', # user at time
'history-feed-empty'          => 'Asebtar i tebɣiḍ ulac-it.
Waqila yettumḥa neɣ yettbeddel isem-is.
Ɛreḍ [[Special:Search|ad tnadiḍ deg wiki]] ɣef isebtaren imaynuten.',

# Revision deletion
'rev-deleted-comment'         => '(awennit yettwakes)',
'rev-deleted-user'            => '(isem n wemseqdac yettwakes)',
'rev-deleted-event'           => '(asekcem yettwakkes)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
Tasiwelt-agi n tettwakkes seg weɣbar azayez.
Waqila yella kter n talɣut deg [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} aɣmis n umḥay].
</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">
Tasiwelt-agi n tettwakkes seg weɣbar azayez.
Kečč d anedbal, tzemreḍ a t-twaliḍ
Waqila yella kter n talɣut [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} aɣmis n umḥay].
</div>',
'rev-delundel'                => 'ssken/ffer',
'revisiondelete'              => 'Mḥu/kkes amḥay tisiwal',
'revdelete-nooldid-title'     => 'Ulac nnican i tasiwelt',
'revdelete-nooldid-text'      => 'Ur textareḍ ara tasiwelt nnican akken ad txedmeḍ tawuri fell-as.',
'revdelete-selected'          => "{{PLURAL:$2|Tasiwelt tettwafren|Tisiwal ttwafernen}} n '''$1:'''",
'logdelete-selected'          => "{{PLURAL:$2|Tamirt n uɣmis tettwafren|Isallen n uɣmis ttwafernen}} n '''$1:'''",
'revdelete-text'              => 'Tisiwal i yettumḥan ad baben deg umezruy n usebtar d weɣmis,
meɛna imuren seg-sen zemren imdanen a ten-ẓren.

Inedbalen wiyaḍ deg wiki-yagi zemren ad ẓren imuren i yettwafren u zemren a ten-mḥan, ḥaca ma llan icekkilen.',
'revdelete-legend'            => 'Eg icekkilen:',
'revdelete-hide-text'         => 'Ffer aḍris n tsiwelt',
'revdelete-hide-name'         => 'Ffer tigawt d nnican',
'revdelete-hide-comment'      => 'Ffer abeddel n uwennit',
'revdelete-hide-user'         => 'Ffer Isem n wemseqdac/IP n umeskar',
'revdelete-hide-restricted'   => 'Eg icekkilen i inedbalen d yimdanen wiyaḍ',
'revdelete-suppress'          => 'Kkes talɣut seg inedbalen d yimdanen wiyaḍ',
'revdelete-hide-image'        => 'Ffer ayen yellan deg ufaylu',
'revdelete-unsuppress'        => 'Kkes icekkilen ɣef tisiwal i yuɣalen-d',
'revdelete-log'               => 'Awennit n uɣmis:',
'revdelete-submit'            => 'Eg-it i tasiwelt tettwafren',
'revdelete-logentry'          => 'asekkud n tasiwelt tettubeddel i  [[$1]]',
'logdelete-logentry'          => 'asekkud n tamirt tettubeddel i [[$1]]',
'revdelete-logaction'         => '$1 {{plural:$1|tasiwelt tettuxdem|tisiwal ttuxedment}} i anaw $2',
'logdelete-logaction'         => '$1 {{plural:$1|tamirt|isallen}} n [[$3]] {{plural:$1|tettuxdem|ttuxedmen}} i anaw $2',
'revdelete-success'           => 'Asekkud n tasiwelt yettuxdem.',
'logdelete-success'           => 'Asekkud n tamirt yettuxdem.',

# Oversight log
'oversightlog'    => 'Aɣmis n oversight',
'overlogpagetext' => 'Deg ukessar, d amuɣ n umḥay d uɛekkil ineggura n wayen yettwaffren seg inedbalen. Ẓer [[Special:Ipblocklist|amuɣ n uɛekkil n IP]].',

# Diffs
'difference'                => '(Imgerraden seg tisiwal)',
'loadingrev'                => 'Assisi tasiwelt n yimgerraden',
'lineno'                    => 'Ajerriḍ $1:',
'editcurrent'               => 'Beddel tasiwelt n tura n usebtar-agi',
'selectnewerversionfordiff' => 'Xtar tasiwelt tamaynut iwakken ad twaliḍ imgerraden',
'selectolderversionfordiff' => 'Xtar tasiwelt taqdimt iwakken ad twaliḍ imgerraden',
'compareselectedversions'   => 'Ẓer imgerraden ger tisiwal i textareḍ',
'editundo'                  => 'ssefsu',
'diff-multi'                => '({{plural:$1|Yiwen tasiwelt tabusarit|$1 n tisiwal tibusarin}} ur ttumlalent ara.)',

# Search results
'searchresults'         => 'Igmad n unadi',
'searchresulttext'      => 'Akken ad tessneḍ amek ara tnadiḍ deg {{SITENAME}}, ẓer [[{{MediaWiki:helppage}}|{{int:help}}]].',
'searchsubtitle'        => "Tnadiḍ ɣef '''[[:$1]]'''",
'searchsubtitleinvalid' => "Tnadiḍ ɣef '''$1'''",
'badquery'              => 'Anadi ur yelhi',
'badquerytext'          => 'Ur nezmir ara ad naf ayen tebɣiḍ.
Axaṭer balak tnadiḍ ɣef awal yesɛan qell n 3 isekkilen,
Neɣ ur turiḍ ara mliḥ ɣef wayen tnadiḍ,
s umedya : "izem d d wuccen".
Ɛreḍ d wawal nniḍen.',
'matchtotals'           => 'Ayen tnadiḍ : "$1" yecban $2 n yizwal n usebtar
d updris n $3 n yisebtaren.',
'noexactmatch'          => "'''Asebtar s yisem \"\$1\" ulac-it.''' Tzemreḍ ad [[:\$1|txelqeḍ asebtar-agi]].",
'titlematches'          => 'Ayen yecban azwel n umegrad',
'notitlematches'        => 'Ulac ayen yecban azwel n umegrad',
'textmatches'           => 'Ayen yecban azwel n usebtar',
'notextmatches'         => 'ulac ayen yecban azwel n usebtar',
'prevn'                 => '$1 ssabeq',
'nextn'                 => '$1 ameḍfir',
'viewprevnext'          => 'Ẓer ($1) ($2) ($3).',
'showingresults'        => "Tamuli n {{PLURAL:$1|'''Yiwen''' wegmud|'''$1''' n yigmad}} seg  #'''$2'''.",
'showingresultsnum'     => "Tamuli n {{PLURAL:$3|'''Yiwen''' wegmud|'''$3''' n yigmad}} seg  #'''$2'''.",
'nonefound'             => "'''Tamawt''': S umata, asmi ur tufiḍ acemma
d ilmen awalen am \"ala\" and \"seg\",
awalen-agi mačči deg tasmult, neɣ tefkiḍ kter n yiwen n wawal (ala isebtaren
i yesɛan akk awalen i banen-d).",
'powersearch'           => 'Nadi',
'powersearchtext'       => 'Nadi deg yismawen n taɣult:<br />$1<br />$2 Amuɣ n yisemmimḍen<br />Nadi ɣef $3 $9',
'searchdisabled'        => 'Anadi deg {{SITENAME}} yettwakkes. Tzemreḍ ad tnadiḍ s Google. Meɛna ur tettuḍ ara, tasmult n google taqdimt.',
'blanknamespace'        => '(Amenzawi)',

# Preferences page
'preferences'              => 'Isemyifiyen',
'mypreferences'            => 'Isemyifiyen inu',
'prefsnologin'             => 'Ur tekcimeḍ ara',
'prefsnologintext'         => 'Yessefk ad [[Special:Userlogin|tkecmeḍ]] iwakken textareḍ isemyifiyen inek.',
'prefsreset'               => 'Iɛawed ad yexdem isemyifiyen inek.',
'qbsettings'               => 'Tanuga tağiḥbuṭ',
'qbsettings-none'          => 'Ulaḥedd',
'qbsettings-fixedleft'     => 'Aẓelmaḍ',
'qbsettings-fixedright'    => 'Ayeffus',
'qbsettings-floatingleft'  => 'Tufeg aẓelmaḍ',
'qbsettings-floatingright' => 'Tufeg ayeffus',
'changepassword'           => 'Beddel awal n tbaḍnit',
'skin'                     => 'Aglim',
'math'                     => 'Tusnakt',
'dateformat'               => 'talɣa n uzemz',
'datedefault'              => 'Ur sɛiɣ ara asemyifi',
'datetime'                 => 'Azemz d ukud',
'math_failure'             => 'Agul n tusnakt',
'math_unknown_error'       => 'Agul mačči d aḍahri',
'math_unknown_function'    => 'Tawuri mačči d taḍahrit',
'math_lexing_error'        => 'Agul n tmawalt',
'math_syntax_error'        => 'Agul n tseddast',
'math_image_error'         => 'Abeddil ɣer PNG yexser; ssenqed installation n latex, dvips, gs, umbeɛd eg abeddel',
'math_bad_tmpdir'          => 'Ur yezmir ara ad yaru ɣef/ɣer tusnakt n temp directory/dossier',
'math_bad_output'          => 'Ur yezmir ara ad yaru ɣef/ɣer tusnakt n tuffɣa directory/dossier',
'math_notexvc'             => "''texvc executable'' / ''executable texvc'' ulac-it; ẓer math/README akken a textareḍ isemyifiyen.",
'prefs-personal'           => 'Profile n wemseqdac',
'prefs-rc'                 => 'Ibeddlen imaynuten',
'prefs-watchlist'          => 'Amuɣ uɛessi',
'prefs-watchlist-days'     => 'Geddac n wussan yessefk ad yesskan deg umuɣ uɛessi:',
'prefs-watchlist-edits'    => 'Geddac n yibeddlen yessefk ad yesskan deg umuɣ uɛessi ameqqran:',
'prefs-misc'               => 'Isemyifiyen wiyaḍ',
'saveprefs'                => 'Smekti',
'resetprefs'               => 'Reset/réinitialiser isemyifiyen',
'oldpassword'              => 'Awal n tbaḍnit aqdim:',
'newpassword'              => 'Awal n tbaḍnit amaynut:',
'retypenew'                => 'Ɛiwed ssekcem n tbaḍnit amaynut:',
'textboxsize'              => 'Abedddil',
'rows'                     => 'Ijerriḍen:',
'columns'                  => 'Tigejda:',
'searchresultshead'        => 'Anadi',
'resultsperpage'           => 'Geddac n tiririyin i mkul usebtar:',
'contextlines'             => 'Geddac n ijerriḍen i mkul tiririt:',
'contextchars'             => 'Geddac n isekkilen n usatal i mkul ajjeriḍ:',
'recentchangescount'       => 'Geddac n izwal deg ibeddilen imaynuten:',
'savedprefs'               => 'Isemyifiyen inek yettusmektan.',
'timezonelegend'           => 'Iẓḍi n ukud',
'timezonetext'             => 'Amgirred ger akud inek d akud n server (UTC) [s swayeɛ].',
'localtime'                => 'Akud inek',
'timezoneoffset'           => 'Amgirred n ukud',
'servertime'               => 'Akud n server',
'guesstimezone'            => 'Sseqdec azal n browser/explorateur',
'allowemail'               => 'Eğğ imseqdacen wiyaḍ a k-aznen email',
'defaultns'                => 'Nadi deg yismawen n taɣult s umeslugen:',
'default'                  => 'ameslugen',
'files'                    => 'Ifayluwen',

# User rights
'userrights-lookup-user'     => 'Laɛej iderman n yimseqdacen',
'userrights-user-editname'   => 'Ssekcem isem n wemseqdac:',
'editusergroup'              => 'Beddel iderman n yimseqdacen',
'userrights-editusergroup'   => 'Beddel iderman n wemseqdac',
'saveusergroups'             => 'Smekti iderman n yimseqdacen',
'userrights-groupsmember'    => 'Amaslad deg:',
'userrights-groupsavailable' => 'Iderman i yellan:',
'userrights-groupshelp'      => 'Xtar anda amseqdac yettwakkes/yettnerni seg/deg iderman.
Iderman ayen ur textareḍ ara ur ttbeddlen ara. Tzemreḍ ad tekkseḍ adrum s CTRL + Click aẓelmaḍ',

# Groups
'group'       => 'Adrum:',
'group-sysop' => 'Inedbalen',
'group-all'   => '(akk)',

'group-sysop-member' => 'Anedbal',

'grouppage-sysop' => '{{ns:project}}:Inedbalen',

# User rights log
'rightslog'      => 'Aɣmis n yizerfan n wemseqdac',
'rightslogtext'  => 'Wagi d aɣmis n yibeddlen n yizerfan n umseqdac',
'rightslogentry' => 'Yettubeddel izerfan n umseqdac $1 seg $2 ar $3',
'rightsnone'     => '(ulaḥedd)',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|Abeddel|Ibeddlen}}',
'recentchanges'                     => 'Ibeddlen imaynuten',
'recentchangestext'                 => 'Ḍfer ibeddilen imaynuten n {{SITENAME}}.',
'recentchanges-feed-description'    => 'Ḍfer ibeddilen imaynuten n wiki-yagi deg usuddem-agi.',
'rcnote'                            => "Deg ukessar {{PLURAL:$1|yella '''yiwen''' ubeddel aneggaru|llan '''$1''' n yibeddlen ineggura}} deg {{PLURAL:$2|wass aneggaru|'''$2''' ussan ineggura}}, deg azemz $3.",
'rcnotefrom'                        => 'Deg ukessar llan ibeddlen seg wasmi <b>$2</b> (ar <b>$1</b>).',
'rclistfrom'                        => 'Ssken ibeddlen imaynuten seg $1',
'rcshowhideminor'                   => '$1 ibeddlen ifessasen',
'rcshowhideliu'                     => '$1 n yimseqdacen i ikecmen',
'rcshowhideanons'                   => '$1 n yimseqdacen udrigen',
'rcshowhidepatr'                    => '$1 n yibeddlen yettwassenqden',
'rcshowhidemine'                    => '$1 ibeddlen inu',
'rclinks'                           => 'Ssken $1 n yibeddlen ineggura di $2 n wussan ineggura<br />$3',
'diff'                              => 'amgirred',
'hist'                              => 'Amezruy',
'hide'                              => 'Ffer',
'show'                              => 'Ssken',
'number_of_watching_users_pageview' => '[$1 aɛessas/iɛessasen]',
'rc_categories'                     => 'Ḥedded i taggayin (ferreq s "|")',
'rc_categories_any'                 => 'Ulayɣer',

# Recent changes linked
'recentchangeslinked'          => 'Ibeddlen imaynuten n yisebtaren myezdin',
'recentchangeslinked-noresult' => 'Ulac abeddel deg isebtaren myezdin deg tawala i textareḍ.',

# Upload
'upload'                      => 'Azen afaylu',
'uploadbtn'                   => 'Azen afaylu',
'reupload'                    => 'Ɛiwed azen',
'reuploaddesc'                => 'Uɣal-d ar talɣa n tuznin.',
'uploadnologin'               => 'Ur tekcimeḍ ara',
'uploadnologintext'           => 'Yessefk [[Special:Userlogin|ad tkecmeḍ]]
iwakken ad tazneḍ afaylu.',
'upload_directory_read_only'  => 'Weserver/serveur Web ur yezmir ara ad yaru deg ($1).',
'uploaderror'                 => 'Agul deg usekcam',
'uploadtext'                  => "Sseqdec talɣa deg ukessar akken ad tazeneḍ tugnawin, akken ad teẓred neɣ ad tnadiḍ tugnawin yettwaznen, ruḥ ɣer [[Special:Imagelist|amuɣ n usekcam n tugnawin]], Amezruy n usekcam d umḥay hatent daɣen deg [[Special:Log/upload|amezruy n usekcam]].

Akken ad tessekcmeḍ tugna deg usebtar, seqdec azay am wagi
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Afaylu.jpg]]</nowiki>''',
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Afaylu.png|aḍris]]</nowiki>''' neɣ
'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:Afaylu.ogg]]</nowiki>''' akken ad iruḥ wezday qbala ar ufaylu.",
'uploadlog'                   => 'amezruy n usekcam',
'uploadlogpage'               => 'Amezruy n usekcam',
'uploadlogpagetext'           => 'Deg ukessar, d amuɣ n n usekcam n ufayluwen imaynuten.',
'filename'                    => 'Isem n ufaylu',
'filedesc'                    => 'Agzul',
'fileuploadsummary'           => 'Agzul:',
'filestatus'                  => 'Aẓayer n copyright',
'filesource'                  => 'Seg way yekka',
'uploadedfiles'               => 'Ifayluwen yettwaznen',
'ignorewarning'               => 'Ttu aɣtal u smekti afaylu',
'ignorewarnings'              => 'Ttu iɣtalen',
'illegalfilename'             => 'Isem n ufaylu "$1" yesɛa isekkilen ur tettalaseḍ ara a ten-tesseqdceḍ deg yizwal n yisebtaren. G leɛnayek beddel isem n ufaylu u azen-it tikkelt nniḍen.',
'badfilename'                 => 'Isem ufaylu yettubeddel ar "$1".',
'filetype-badmime'            => 'Ur tettalaseḍ ara ad tazneḍ ufayluwen n anaw n MIME "$1".',
'filetype-badtype'            => "Ur neqbil ara ufayluwen n anwan am '''\".\$1\"'''
: Amuɣ n inawen i neqbel: \$2",
'filetype-missing'            => 'Afaylu ur yesɛi ara taseggiwit (am ".jpg").',
'large-file'                  => 'Ilaq tiddi n ufayluwen ur tettili kter n $1; tiddi n ufaylu-agi $2.',
'largefileserver'             => 'Afaylu meqqer aṭṭas, server ur t-yeqbil ara.',
'emptyfile'                   => 'Afaylu i tazneḍ d ilem. Waqila tɣelṭeḍ deg isem-is. G leɛnayek ssenqed-it.',
'fileexists'                  => 'Afaylu s yisem-agi yewğed yagi, ssenqed <strong><tt>$1</tt></strong> ma telliḍ mačči meḍmun akken a t-tbeddleḍ.',
'fileexists-extension'        => 'Afaylu s yisem-agi yewğed:<br />
Isem n ufaylu i tazneḍ: <strong><tt>$1</tt></strong><br />
Isem n ufaylu i yewğed: <strong><tt>$2</tt></strong><br />
Amgirred i yella kan deg isekkilen imecṭuḥen/imeqqranen deg taseggiwit (am ".jpg"/".jPg"). G leɛnayek ssenqed-it.',
'fileexists-thumb'            => "'''<center>Tugna i tewğed</center>'''",
'fileexists-thumbnail-yes'    => 'Iban-d belli tugna-nni d tugna tamecṭuht n tugna nniḍen <i>(thumbnail)</i>. G leɛnayek ssenqed tugna-agi <strong><tt>$1</tt></strong>.<br />
Ma llant kif-kif ur tt-taznepd ara.',
'file-thumbnail-no'           => 'Isem n tugna yebda s <strong><tt>$1</tt></strong>. Waqila tugna-nni d tugna tamecṭuht n tugna nniḍen <i>(thumbnail)</i>.
Ma tesɛiḍ tugna-nni s resolution tameqqrant, azen-it, ma ulac beddel isem-is.',
'fileexists-forbidden'        => 'Tugna s yisem kif-kif tewğed yagi; g leɛnayek uɣal u beddel isem-is. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Tugna s yisem kif-kif tewğed yagi; g leɛnayek uɣal u beddel isem-is. [[Image:$1|thumb|center|$1]]',
'successfulupload'            => 'Azen yekfa',
'uploadwarning'               => 'Aɣtal deg wazan n ufayluwen',
'savefile'                    => 'Smekti afaylu',
'uploadedimage'               => '"[[$1]]" yettwazen',
'uploaddisabled'              => 'Suref-aɣ, azen n ufayluwen yettwakkes',
'uploaddisabledtext'          => 'Azen n ufayluwen yettwakkes deg wiki-yagi',
'uploadscripted'              => 'Afaylu-yagi yesɛa angal n HTML/script i yexdem agula deg browser/explorateur.',
'uploadcorrupt'               => 'Afaylu-yagi yexser neɣ yesɛa taseggiwit (am ".jpg") mačči ṣaḥiḥ. G leɛnayek ssenqed-it.',
'uploadvirus'                 => 'Afaylu-nni yesɛa anfafad asenselkim (virus)! Ẓer kter: $1',
'sourcefilename'              => 'And yella afyalu',
'destfilename'                => 'Anda iruḥ afaylu',
'watchthisupload'             => 'Ɛass asebtar-agi',
'filewasdeleted'              => 'Afaylu s yisem-agi yettwazen umbeɛd yettumḥa. Ssenqed $1 qbel ma tazniḍ tikelt nniḍen.',

'upload-proto-error'      => 'Agul deg protokol',
'upload-proto-error-text' => 'Assekcam yenṭerr URL i yebdan s <code>http://</code> neɣ <code>ftp://</code>.',
'upload-file-error'       => 'Agul zdaxel',
'upload-file-error-text'  => 'Agul n daxel yeḍran asmi yeɛreḍ ad yexleq afaylu temporaire deg server.  G leɛnayek, meslay akk d unedbal n system.',
'upload-misc-error'       => 'Agul mačči mechur asmi yettwazen ufaylu',
'upload-misc-error-text'  => 'Agul mačči mechur teḍra asmi yettwazen afaylu.  G leɛnayek sseqed belli URL d ṣaḥiḥ u ɛreḍ tikelt nniḍen.  Ma yella daɣen wagul, mmeslay akk d unedbal n system.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Ur yezmir ara ad yessglu URL',
'upload-curl-error6-text'  => 'Ur yezmir ara ad yessglu URL.  Ssenqed URL-nni.',
'upload-curl-error28'      => 'Yekfa wakud n wazen n ufaylu',
'upload-curl-error28-text' => 'Adeg n internet-agi iɛetṭel aṭas. G leɛnayek ssenqed adeg-nni, ggun cwiṭ umbeɛd ɛreḍ tikelt nniḍen.',

'license'            => 'Turagt',
'nolicense'          => 'Ur textareḍ acemma',
'upload_source_url'  => ' (URL saḥiḥ)',
'upload_source_file' => ' (afaylu deg uselkim inek)',

# Image list
'imagelist'                 => 'Amuɣ n tugniwin',
'imagelisttext'             => "Deg ukessar yella umuɣ n '''$1''' {{plural:$1|ufaylu|yifayluwen}} $2.",
'imagelistforuser'          => 'Wagi yemli tugniwin i yazen $1 kan.',
'getimagelist'              => 'Yeddem amuɣ n tugniwin',
'ilsubmit'                  => 'Nadi',
'showlast'                  => 'Ssken $1 n yifayluwen ineggura $2.',
'byname'                    => 's yisem',
'bydate'                    => 's uzemz',
'bysize'                    => 's tiddi',
'imgdelete'                 => 'mḥu',
'imgdesc'                   => 'aglam',
'imgfile'                   => 'afaylu',
'imglegend'                 => 'Tabadut: (desc) = ssken/beddel aglam n ufaylu.',
'imghistory'                => 'amezruy n ufaylu',
'revertimg'                 => 'ssuɣal',
'deleteimg'                 => 'mḥu',
'deleteimgcompletely'       => 'Mḥu akk tisiwal n ufaylu-yagi',
'imghistlegend'             => 'Tabdut: (tura) = afaylu n tura, (mḥu) = mḥu tasiwelt taqdimt,
(ssuɣal) = ssuɣal ɣer tasiwlt taqdimt-agi.
<br /><i>Wekki ɣef uzemz akken ad teẓriḍ afaylu deg wass-nni</i>.',
'imagelinks'                => 'Izdayen',
'linkstoimage'              => 'Isebtaren-agi sɛan azday ar afaylu-agi',
'nolinkstoimage'            => 'Ulaḥedd seg yisebtaren sɛan azday ar afaylu-agi.',
'sharedupload'              => 'Afaylu-yagi yettuseqdac sɣur wiki tiyaḍ.',
'shareduploadwiki'          => 'Ẓer $1 iwakken ad tessneḍ kter.',
'shareduploadwiki-linktext' => 'Asebtar n uglam n ufaylu',
'noimage'                   => 'Afaylu s yisem-agi ulac-it, tzemreḍ ad $1.',
'noimage-linktext'          => 't-tazneḍ',
'uploadnewversion-linktext' => 'tazneḍ tasiwelt tamaynut n ufaylu-yagi',
'imagelist_date'            => 'Azemz',
'imagelist_name'            => 'Isem',
'imagelist_user'            => 'Amseqdac',
'imagelist_size'            => 'Tiddi (bytes/octets)',
'imagelist_description'     => 'Aglam',
'imagelist_search_for'      => 'Nadi ɣef yisem n tugna:',

# MIME search
'mimesearch'         => 'Anadi n MIME',
'mimesearch-summary' => 'Asebtar-agi yeğğa astay n ifayluwen n unaw n MIME ines. Asekcem: ayen yella/anaw azellum, e.g. <tt>tugna/jpeg</tt>.',
'mimetype'           => 'Anaw n MIME:',
'download'           => 'Ddem-it ɣer uselkim inek',

# Unwatched pages
'unwatchedpages' => 'Isebtaren mebla iɛessasen',

# List redirects
'listredirects' => 'Amuɣ isemmimḍen',

# Unused templates
'unusedtemplates'     => 'Talɣiwin mebla aseqdac',
'unusedtemplatestext' => 'Asebtar-agi yesɛa amuɣ n akk isebtaren n isem n taɣult s yisem "talɣa" iwumi ulca-iten deg ḥedd asebtar. Ur tettuḍ ara ad tessenqdeḍ isebtaren n talɣa wiyaḍ qbel ma temḥuḍ.',
'unusedtemplateswlh'  => 'izdayen wiyaḍ',

# Random redirect
'randomredirect' => 'Asemmimeḍ menwala',

# Statistics
'statistics'             => 'Tisnaddanin',
'sitestats'              => 'Tisnaddanin n {{SITENAME}}',
'userstats'              => 'Tisnaddanin n umseqdac',
'sitestatstext'          => "{{PLURAL:\$1|Yella '''yiwen''' usebtar|Llan '''\$1''' n yisebtaren}} deg database.
Azwil-agi yesɛa daɣen akk isebtaren \"amyannan\", d yisebtaren ɣef {{SITENAME}}, d yisebtaren \"imecṭuḥen\", isebtaren ismimḍen, d wiyaḍ.
Asmi ttwakksen wigini, {{PLURAL:\$2|yella '''yiwen''' asebtar|llan '''\$2''' n yisebtaren}} d {{PLURAL:\$2|asebtar amliḥ|isebtaren imliḥen}} . 

'''\$8''' {{PLURAL:\$8|afaylu|ifayluwen}} ttwaznen.

{{PLURAL:\$3|tella|llant}} '''\$3''' n {{PLURAL:\$3|timeẓriwt|timeẓriwin}}, '''\$4''' n {{PLURAL:\$4|ubeddel|yibeddlen}} n usebtar segwasmi {{SITENAME}} yettwaxleq.
Ihi, {{PLURAL:\$5|yella|llan}} '''\$5''' n {{PLURAL:\$5|ubeddel|yibeddlen}} i mkul asebtar, d '''\$6''' timeẓriwin i mkul abeddel.

Ṭul n [http://meta.wikimedia.org/wiki/Help:Job_queue umuti n wexdam] '''\$7'''.",
'userstatstext'          => "{{PLURAL:$1|Yella '''yiwen''' umseqdac|Llan '''$1''' n yimseqdacen}}, seg-sen
'''$2''' (neɣ '''$4%''') {{PLURAL:$2|yesɛa|sɛan}} izerfan n $5.",
'statistics-mostpopular' => 'Isebtaren mmeẓren aṭṭas',

'disambiguations'      => 'Isebtaren n usefham',
'disambiguationspage'  => 'Talɣa:asefham',
'disambiguations-text' => "Isebtaren-agi sɛan azday ɣer '''usebtar n usefham'''. Yessfak ad sɛun azday ɣer uzwel ṣaḥiḥ mačči ɣer usebtar n usefham.",

'doubleredirects'     => 'Asemmimeḍ yeḍra snat tikwal',
'doubleredirectstext' => 'Mkull ajerriḍ yesɛa azday ɣer asmimeḍ amezwaru d wis sin, ajerriḍ amezwaru n uḍris n usebtar wis sin
daɣen, iwumi yefkan asmimeḍ ṣaḥiḥ i yessefk ad sɛan isebtaren azday ɣur-s.',

'brokenredirects'        => 'Isemmimḍen imerẓa',
'brokenredirectstext'    => 'Isemmimḍen-agi sɛan izdayen ar isebtaren ulac-iten:',
'brokenredirects-edit'   => '(beddel)',
'brokenredirects-delete' => '(mḥu)',

'withoutinterwiki'        => 'Isebtaren mebla izdayen ar isebtaren n wikipedia s tutlayin tiyaḍ',
'withoutinterwiki-header' => 'Isebtaren-agi ur sɛan ara izdayen ar isebtaren n wikipedia s tutlayin tiyaḍ:',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte/octet|bytes/octets}}',
'ncategories'             => '$1 {{PLURAL:$1|Taggayt|Taggayin}}',
'nlinks'                  => '$1 {{PLURAL:$1|azday|izdayen}}',
'nmembers'                => '$1 {{PLURAL:$1|amaslad|imasladen}}',
'nrevisions'              => '$1 {{PLURAL:$1|tasiwelt|tisiwal}}',
'nviews'                  => '$1 {{PLURAL:$1|timeẓriwt|tuẓrin}}',
'specialpage-empty'       => 'Asebtar-agi d ilem.',
'lonelypages'             => 'Isebtaren igujilen',
'lonelypagestext'         => 'Isebtaren-agi ur myezdin ara seg isebtaren wiyaḍ deg wiki-yagi.',
'uncategorizedpages'      => 'Isebtaren mebla taggayt',
'uncategorizedcategories' => 'Taggayin mebla taggayt',
'uncategorizedimages'     => 'Tugna mebla taggayt',
'unusedcategories'        => 'Taggayin ur nettwaseqdac ara',
'unusedimages'            => 'Ifayluwin ur nettwaseqdac ara',
'popularpages'            => 'Isebtaren iɣerfanen',
'wantedcategories'        => 'Taggayin mmebɣant',
'wantedpages'             => 'Isebtaren mmebɣan',
'mostlinked'              => 'Isebtaren myezdin aṭas',
'mostlinkedcategories'    => 'Taggayin myezdint aṭas',
'mostcategories'          => 'Isebtaren i yesɛan aṭṭas taggayin',
'mostimages'              => 'Tugniwin myezdin aṭas',
'mostrevisions'           => 'Isebtaren i yettubedlen aṭas',
'allpages'                => 'Akk isebtaren',
'prefixindex'             => 'Akk isebtaren s yisekkilen imezwura',
'randompage'              => 'Asebtar menwala',
'shortpages'              => 'Isebtaren imecṭuḥen',
'longpages'               => 'Isebtaren imeqqranen',
'deadendpages'            => 'isebtaren mebla izdayen',
'deadendpagestext'        => 'Isebtaren-agi ur sɛan ara azday ɣer isebtaren wiyaḍ deg wiki-yagi.',
'protectedpages'          => 'Isebtaren yettwaḥerzen',
'protectedpagestext'      => 'Isebtaren-agi yettwaḥerzen seg ubeddel neɣ asemmimeḍ',
'protectedpagesempty'     => 'Isebtaren-agi ttwaḥerzen s imsektayen -agi.',
'listusers'               => 'Amuɣ n yimseqdacen',
'specialpages'            => 'Isebtaren usligen',
'spheading'               => 'Isebtaren usligen i akk iseqdacen',
'restrictedpheading'      => 'Isebtaren usligen gedlen',
'rclsub'                  => '(ar isebtaren myezdin seg "$1")',
'newpages'                => 'Isebtaren imaynuten',
'newpages-username'       => 'Isem n wemseqdac:',
'ancientpages'            => 'Isebtaren iqdimen',
'intl'                    => 'Izdayen ar tutlayin nniḍen zdaxel wikipedia',
'move'                    => 'Smimeḍ',
'movethispage'            => 'Smimeḍ asebtar-agit',
'unusedimagestext'        => '<p>Ssen belli ideggen n internet sɛan izdayen ɣer tugna-agi s URL n qbala, ɣas akken tugna-nni hatt da.</p>',
'unusedcategoriestext'    => 'Taggayin-agi weğden meɛna ulac isebtaren neɣ taggayin i sseqdacen-iten.',

# Book sources
'booksources'               => 'Iɣbula n yidlisen',
'booksources-search-legend' => 'Nadi ɣef iɣbula n yidlisen',
'booksources-go'            => 'Ruḥ',
'booksources-text'          => 'Deg ukessar, amuɣ n yizdayen iberraniyen izzenzen idlisen (imaynuten akk d uqdimen), yernu ahat sɛan kter talɣut ɣef idlisen i tettnadiḍ fell-asen:',

'categoriespagetext' => 'Llant taggayin-agi deg wiki-yagi.',
'data'               => 'Talɣut',
'userrights'         => 'Laɛej iserfan n wemseqdac',
'groups'             => 'Iderman n yimseqdacen',
'alphaindexline'     => '$1 ar $2',
'version'            => 'Tasiwelt',

# Special:Log
'specialloguserlabel'  => 'Amseqdac:',
'speciallogtitlelabel' => 'Azwel:',
'log'                  => 'Aɣmis',
'log-search-legend'    => 'Nadi ɣef yiɣmisen',
'log-search-submit'    => 'OK',
'alllogstext'          => 'Ssken akk iɣmisen n {{SITENAME}}.
Tzemreḍ ad textareḍ cwiṭ seg-sen ma tebɣiḍ.',
'logempty'             => 'Ur yufi ara deg uɣmis.',
'log-title-wildcard'   => 'Nadi ɣef izwal i yebdan s uḍris-agi',

# Special:Allpages
'nextpage'          => 'Asebtar ameḍfir ($1)',
'prevpage'          => 'Asebtar ssabeq ($1)',
'allpagesfrom'      => 'Ssken isebtaren seg:',
'allarticles'       => 'Akk imagraden',
'allinnamespace'    => 'Akk isebtaren ($1 isem n taɣult)',
'allnotinnamespace' => 'Akk isebtaren (mačči deg $1 isem n taɣult)',
'allpagesprev'      => 'Ssabeq',
'allpagesnext'      => 'Ameḍfir',
'allpagessubmit'    => 'Ruḥ',
'allpagesprefix'    => 'Ssken isebtaren s uzwir:',
'allpagesbadtitle'  => 'Azwel n usebtar mačči ṣaḥiḥ neɣ yesɛa azwir inter-wiki. Waqila yesɛa isekkilen ur ttuseqdacen ara deg izwal.',

# Special:Listusers
'listusersfrom'      => 'Ssken imseqdacen seg:',
'listusers-submit'   => 'Ssken',
'listusers-noresult' => 'Ur yufi ḥedd (amseqdac).',

# E-mail user
'mailnologin'     => 'Ur yufi ḥedd (tansa)',
'mailnologintext' => 'Yessefk ad [[Special:Userlogin|tkecmeḍ]] u tesɛiḍ tansa e-mail ṭaṣhiḥt deg [[Special:Preferences|isemyifiyen]] inek
iwakken ad tazneḍ email i imseqdacen wiyaḍ.',
'emailuser'       => 'Azen e-mail i wemseqdac-agi',
'emailpage'       => 'Azen e-mail i wemseqdac',
'emailpagetext'   => 'Lukan amseqdac-agi yefka-d tansa n email ṣaḥiḥ
deg imsifiyen ines, talɣa deg ukessar a t-tazen izen.
Tansa n email i tefkiḍ deg imisifyen inek ad tban-d
deg « Expéditeur» n izen inek iwakken amseqdac-nni yezmer a k-yerr.',
'usermailererror' => 'Yella ugul deg uzwel n email:',
'defemailsubject' => 'e-mail n {{SITENAME}}',
'noemailtitle'    => 'E-mail ulac-it',
'noemailtext'     => 'Amseqdac-agi ur yefki ara e-mail ṣaḥiḥ, neɣ ur yebɣi ara e-mailiyen seg medden.',
'emailfrom'       => 'Seg',
'emailto'         => 'i',
'emailsubject'    => 'Asentel',
'emailmessage'    => 'Izen',
'emailsend'       => 'Azen',
'emailccme'       => 'Azen-iyi-d e-mail n ulsaru n izen inu.',
'emailccsubject'  => 'Alsaru n izen inek i $1: $2',
'emailsent'       => 'E-mail yettwazen',
'emailsenttext'   => 'Izen n e-mail inek yettwazen.',

# Watchlist
'watchlist'            => 'Amuɣ uɛessi inu',
'mywatchlist'          => 'Amuɣ uɛessi inu',
'watchlistfor'         => "(n '''$1''')",
'nowatchlist'          => 'Amuɣ uɛessi inek d ilem.',
'watchlistanontext'    => 'G leɛnaya-k $1 iwakken ad twalaḍ neɣ tbeddleḍ iferdas deg umuɣ uɛessi inek.',
'watchlistcount'       => "'''Ɣur-k {{PLURAL:$1|$1 aferdis|$1 iferdas}} deg umuɣ uɛessi inek (s yisebtaren umyannan).'''",
'watchnologin'         => 'Ur tekcimeḍ ara',
'watchnologintext'     => 'Yessefk ad [[Special:Userlogin|tkecmeḍ]] iwakken ad tbeddleḍ amuɣ uɛessi inek.',
'addedwatch'           => 'Yerna ar amuɣ uɛessi',
'addedwatchtext'       => "Asebtar \"[[:\$1]]\" yettwarnu deg [[Special:Watchlist|umuɣ uɛessi]] inek.
Ma llan ibeddlen deg usebtar-nni neɣ deg usbtar umyennan ines, ad banen dagi,
Deg [[Special:Recentchanges|umuɣ n yibeddlen imaynuten]] ad banen s '''yisekkilen ibberbuzen''' (akken ad teẓriḍ).

Ma tebɣiḍ ad tekkseḍ asebtar seg umuɣ uɛessi inek, wekki ɣef \"Fakk aɛessi\".",
'removedwatch'         => 'Yettwakkes seg umuɣ uɛessi',
'removedwatchtext'     => 'Asebtar "[[:$1]]" yettwakkes seg umuɣ uɛessi inek.',
'watch'                => 'Ɛass',
'watchthispage'        => 'Ɛass asebtar-agi',
'unwatch'              => 'Fakk aɛassi',
'unwatchthispage'      => 'Fakk aɛassi',
'notanarticle'         => 'Mačči d amagrad',
'watchnochange'        => 'Ulaḥedd n yiferdas n umuɣ n uɛessi inek ma yettubeddel deg tawala i textareḍ.',
'watchlist-details'    => 'ttɛassaɣ {{PLURAL:$1|$1 usebtar|$1 n yisebtaren}} mebla isebtaren "amyannan".',
'wlheader-enotif'      => '* Yeğğa Email n talɣut.',
'wlheader-showupdated' => "* Isebtaren ttubeddlen segwasmi tkecmeḍ tikelt taneggarut ttbanen-d s '''uḍris aberbuz'''",
'watchmethod-recent'   => 'yessenqed ibeddlen imaynuten n isebtaren i ttɛasseɣ',
'watchmethod-list'     => 'yessenqed isebtaren i ttɛassaɣ i ibeddlen imaynuten',
'watchlistcontains'    => 'Amuɣ uɛessi inek yesɛa $1 n {{PLURAL:$1|usebtar|yisebtaren}}.',
'iteminvalidname'      => "Agnu akk d uferdis '$1', isem mačči ṣaḥiḥ...",
'wlnote'               => "Deg ukessar {{PLURAL:$1|yella yiwen ubeddel aneggaru|llan '''$1''' n yibeddlen ineggura}} deg {{PLURAL:$2|saɛa taneggarut|'''$2''' swayeɛ tineggura}}.",
'wlshowlast'           => 'Ssken $1 n swayeɛ $2 n wussan neɣ $3 ineggura',
'wlsaved'              => 'Tagi d tasiwelt tettusmekta n umuɣ uɛessi inek.',
'watchlist-show-bots'  => 'Ssken ibeddlen n yiboṭiyen (bots)',
'watchlist-hide-bots'  => 'Ffer ibeddlen n yiboṭiyen (bots)',
'watchlist-show-own'   => 'Ssken ibeddlen inu',
'watchlist-hide-own'   => 'Ffer ibeddlen inu',
'watchlist-show-minor' => 'Ssken ibeddlen ifessasen',
'watchlist-hide-minor' => 'Ffer ibeddlen ifessasen',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Ad iɛass...',
'unwatching' => 'Ad ifukk aɛessi...',

'enotif_mailer'      => 'Email n talɣut n {{SITENAME}}',
'enotif_reset'       => 'Rcem akk isebtaren mmeẓren',
'enotif_newpagetext' => 'Wagi d asebtar amaynut.',
'changed'            => 'yettubeddel',
'created'            => 'yettwaxleq',
'enotif_subject'     => 'Asebtar $PAGETITLE n {{SITENAME}} $CHANGEDORCREATED sɣur $PAGEEDITOR',
'enotif_lastvisited' => 'Ẓer $1 i akk ibeddlen segwasmi tkecmeḍ tikelt taneggarut.',
'enotif_body'        => 'Ay $WATCHINGUSERNAME,

Asebtar n {{SITENAME}} $PAGETITLE $CHANGEDORCREATED deg wass $PAGEEDITDATE sɣur $PAGEEDITOR, ẓer $PAGETITLE_URL i tasiwelt n tura.

$NEWPAGE

Abeddel n wegzul: $PAGESUMMARY $PAGEMINOREDIT

Meslay akk d ambeddel:
email: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Ur yelli ara email n talɣut asmi llan ibeddlen deg usebtar ala lukan teẓreḍ asebtar-nni. Tzemreḍ ad terreḍ i zero email n talɣut i akk isebraen i tettɛasseḍ.

             email n talɣut n {{SITENAME}}

--
Akken ad tbeddleḍ n umuɣ uɛessi inek settings, ruḥ ɣer
{{fullurl:{{ns:special}}:Watchlist/edit}}

Tadhelt:
{{fullurl:{{MediaWiki:helppage}}}}',

# Delete/protect/revert
'deletepage'              => 'Mḥu asebtar',
'confirm'                 => 'Sentem',
'excontent'               => "Ayen yella: '$1'",
'excontentauthor'         => "Ayen yella: '$1' ('[[Special:Contributions/$2|$2]]' kan i yekken deg-s)",
'exbeforeblank'           => "Ayen yella uqbal ma yettumḥa: '$1'",
'exblank'                 => 'asebtar yella d ilem',
'confirmdelete'           => 'Validi amḥay',
'deletesub'               => '(Ad yemḥu "$1")',
'historywarning'          => 'Aɣtal: Asebtar i ara temḥuḍ yesɛa amezruy:',
'actioncomplete'          => 'Axdam yekfa',
'deletedtext'             => '"$1" yettumḥa.
Ẓer $2 i aɣmis n yimḥayin imaynuten.',
'deletedarticle'          => '"[[$1]]" yettumḥa',
'dellogpage'              => 'Aɣmis n umḥay',
'dellogpagetext'          => 'Deg ukessar, d amuɣ n yimḥayin imaynuten.',
'deletionlog'             => 'Aɣmis n umḥay',
'reverted'                => 'Asuɣal i tasiwel taqdimt',
'deletecomment'           => 'Ayɣer tebɣiḍ ad temḥuḍ',
'imagereverted'           => 'Asuɣal i tasiwel taqdimt yekfa.',
'cantrollback'            => 'Ur yezmir ara ad yessuɣal; yella yiwen kan amseqdac iwumi ibeddel/yexleq asebtar-agi.',
'editcomment'             => 'Agzul n ubeddel yella: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'              => 'Yessuɣal ibeddlen n [[Special:Contributions/$2|$2]] ([[User talk:$2|Meslay]]); yettubeddel ɣer tasiwelt taneggarut n [[User:$1|$1]]',
'sessionfailure'          => 'Yella ugul akk d takmect inek;
Axdam-agi yebṭel axaṭer waqila yella wemdan nniḍen i yeddem isem n wemseqdac inek.
G leɛnayek wekki ɣef taqeffalt "Back/Précédent" n browser/explorateur inek, umbeɛd wekki ɣef "Actualiser/reload" akk ad tɛerḍeḍ tikelt nniḍen.',
'protectlogpage'          => 'Aɣmis n wemḥay',
'protectedarticle'        => '"[[$1]]" yettwaḥrez',
'protectsub'              => '(Ad yeḥrez "$1")',
'protect-default'         => '(ameslugen)',
'protect-level-sysop'     => 'Inedbalen kan',
'protect-summary-cascade' => 'acercur',
'protect-expiring'        => 'yemmut deg $1 (UTC)',
'restriction-type'        => 'Turagt',
'minimum-size'            => 'Tiddi minimum (bytes/octets)',

# Restrictions (nouns)
'restriction-edit' => 'Beddel',
'restriction-move' => 'Smimeḍ',

# Undelete
'viewdeletedpage'        => 'Ẓer isebtaren yettumḥan',
'undeletecomment'        => 'Awennit:',
'undelete-header'        => 'Ẓer [[Special:Log/delete|aɣmis n umḥay]] i isebtaren ttumḥan tura.',
'undelete-search-box'    => 'Nadi ɣef isebtaren yettumḥan',
'undelete-search-prefix' => 'Ssken isebtaren i yebdan s:',
'undelete-search-submit' => 'Nadi',
'undelete-no-results'    => 'Ur yufi ara ulaḥedd n wawalen i tnadiḍ ɣef isebtaren deg iɣbaren.',

# Namespace form on various pages
'namespace' => 'Isem n taɣult:',
'invert'    => 'Snegdam ayen textareḍ',

# Contributions
'contributions' => 'Tikkin n wemseqdac',
'mycontris'     => 'Tikkin inu',
'contribsub2'   => 'n $1 ($2)',
'nocontribs'    => 'Ur yufi ara abddel i tebɣiḍ.',
'ucnote'        => 'Deg ukessar llan <b>$1</b> n yibeddlen ineggura deg <b>$2</b> n wussan ineggura.',
'uclinks'       => 'Ẓer $1 n yibeddlen ineggura; ẓer $2 n wussan ineggura.',
'uctop'         => ' (taneggarut)',

'sp-contributions-newest'      => 'Tikkin tineggura',
'sp-contributions-oldest'      => 'Tikkin timezwura',
'sp-contributions-newer'       => '$1 ssabeq',
'sp-contributions-older'       => '$1 imeḍfiren',
'sp-contributions-newbies'     => 'Ssken tikkin n yimseqdacen imaynuten kan',
'sp-contributions-newbies-sub' => 'I yisem yimseqdacen imaynuten',
'sp-contributions-blocklog'    => 'Aɣmis n uɛeṭṭil',
'sp-contributions-search'      => 'Nadi i tikkin',
'sp-contributions-username'    => 'Tansa IP neɣ isem n wemseqdac:',
'sp-contributions-submit'      => 'Nadi',

'sp-newimages-showfrom' => 'Ssken tugniwin timaynutin seg $1',

# What links here
'whatlinkshere'      => 'Ayen yewwi-d ɣer dagi',
'notargettitle'      => 'Ulac nnican',
'notargettext'       => 'Ur textareḍ ara asebtar d nnican neɣ asebtar n wemseqdac d nnican.',
'linklistsub'        => '(Amuɣ n yizdayen)',
'linkshere'          => "Isebtaren-agi sɛan azday ɣer '''[[:$1]]''':",
'nolinkshere'        => "Ulac asebtar i yesɛan azday ɣer '''[[:$1]]'''.",
'nolinkshere-ns'     => "Ulac asebtar i yesɛan azday ɣer '''[[:$1]]''' deg yisem n taɣult i textareḍ.",
'isredirect'         => 'Asebtar usemmimeḍ',
'istemplate'         => 'asekcam',
'whatlinkshere-prev' => '{{PLURAL:$1|ssabeq|$1 ssabeq}}',
'whatlinkshere-next' => '{{PLURAL:$1|ameḍfir|$1 imeḍfiren}}',

# Block/unblock
'ipaddress'                   => 'Tansa IP',
'ipadressorusername'          => 'Tansa IP neɣ isem n wemseqdac',
'ipbreason'                   => 'Ayɣer',
'ipbotheroption'              => 'nniḍen',
'badipaddress'                => 'Tansa IP mačči d ṣaḥiḥ',
'ipblocklist-submit'          => 'Nadi',
'contribslink'                => 'tikkin',
'block-log-flags-anononly'    => 'Imseqdacen udrigen kan',
'proxyblockreason'            => 'Tansa n IP inek teɛkel axaṭer nettat "open proxy". G leɛnayek, meslay akk d provider inek.',
'proxyblocksuccess'           => 'D ayen.',
'sorbsreason'                 => 'Tansa n IP inek teɛkel axaṭer nettat "open proxy" deg DNSBL yettuseqdac da.',
'sorbs_create_account_reason' => 'Tansa n IP inek teɛkel axaṭer nettat "open proxy" deg DNSBL yettuseqdac da. Ur tezmireḍ ara ad txelqeḍ isem n wemseqdac',

# Move page
'movepage'                => 'Smimeḍ asebtar',
'movepagetext'            => "Mi tedsseqdceḍ talɣa deg ukessar ad ibddel isem n usebtar, yesmimeḍ akk
umezruy-is ɣer isem amaynut.
Azwel aqdim ad yuɣal azady n wesmimeḍ ɣer azwel amaynut.
Izdayen ɣer azwel aqdim ur ttubeddlen ara; ssenqd-iten
u ssenqed izdayen n snat d tlata tikkwal.
D kečč i yessefk a ten-yessenqed.

Meɛna, ma yella amagrad deg azwel amaynut neɣ azday n wamsmimeḍ
mebla amezruy, asebtar-inek '''ur''' yettusmimeḍ '''ara'''.
Yernu, tzemreḍ ad tesmimeḍ asebtar ɣer isem-is aqdim ma tɣelṭeḍ.",
'movepagetalktext'        => "Asebtar \"Amyannan\" yettusmimeḍ ula d netta '''ma ulac:'''
*Yella asebtar \"Amyannan\" deg isem amaynut, neɣ
*Trecmeḍ tankult deg ukessar.

Lukan akka, yessefk a t-tedmeḍ weḥdek.",
'movearticle'             => 'Smimeḍ asebtar',
'movenologin'             => 'Ur tekcimeḍ ara',
'movenologintext'         => 'Yessefk ad tesɛuḍ isem n wemseqdac u [[Special:Userlogin|tkecmeḍ]]
iwakken ad tesmimḍeḍ asebtar.',
'newtitle'                => 'Ar azwel amaynut',
'move-watch'              => 'Ɛass asebtar-agi',
'movepagebtn'             => 'Smimeḍ asebtar',
'pagemovedsub'            => 'Asemmimeḍ yekfa',
'articleexists'           => 'Yella yagi yisem am wagi, neɣ 
isem ayen textareḍ mačči d ṣaḥiḥ.
Xtar yiwen nniḍen.',
'talkexists'              => "'''Asemmimeḍ n usebtar yekfa, meɛna asebtar umyannan ines ur yettusemmimeḍ ara axaṭer yella yagi yiwen s yisem kif-kif. G leɛnayek, xdem-it weḥd-ek.'''",
'movedto'                 => 'yettusmimeḍ ar',
'movetalk'                => 'Smimeḍ asebtar umyannan (n umagrad-nni)',
'talkpagemoved'           => 'Asebtar umyannan yettusmimeḍ daɣen',
'talkpagenotmoved'        => 'Asebtar umyannan (n umagrad-nni) <strong>ur</strong> yettusmimeḍ <strong>ara</strong>.',
'1movedto2'               => '[[$1]] yettusmimeḍ ar [[$2]]',
'1movedto2_redir'         => '[[$1]] yettusmimeḍ ar [[$2]] s redirect',
'movelogpage'             => 'Aɣmis n usemmimeḍ',
'movelogpagetext'         => 'Akessar yella umuɣ n yisebtaren yettusmimeḍen.',
'movereason'              => 'Ayɣer',
'revertmove'              => 'Uɣal ar tasiwelt ssabeq',
'delete_and_move'         => 'Mḥu u smimeḍ',
'delete_and_move_text'    => '==Amḥay i tebɣiḍ==

Anda tebɣiḍ tesmimeḍ "[[$1]]" yella yagi. tebɣiḍ ad temḥuḍ iwakken yeqqim-d wemkan i usmimeḍ?',
'delete_and_move_confirm' => 'Ih, mḥu asebtar',
'delete_and_move_reason'  => 'Mḥu iwakken yeqqim-d wemkan i usmimeḍ',
'selfmove'                => 'Izwal amezwaru d uneggaru kif-kif; ur yezmir ara yesmimeḍ asebtar ɣur iman-is.',
'immobile_namespace'      => 'Azwel n uɣbalu neɣ anda tebɣiḍ tesmimeḍ d anaw aslig; ur yezmir ara yesmimeḍ isebtaren seg/ɣer isem n taɣult-agi.',

# Export
'export'            => 'Ssufeɣ isebtaren',
'exportcuronly'     => 'Ssekcem tasiwelt n tura kan, mačči akk amezruy-is',
'export-submit'     => 'Ssufeɣ',
'export-addcattext' => 'Rnu isebtaren seg taggayt:',
'export-addcat'     => 'Rnu',

# Namespace 8 related
'allmessages'               => 'Izen n system',
'allmessagesname'           => 'Isem',
'allmessagesdefault'        => 'Aḍris ameslugen',
'allmessagescurrent'        => 'Aḍris n tura',
'allmessagestext'           => 'Wagi d amuɣ n izen n system i yellan di isem n taɣult.',
'allmessagesnotsupportedUI' => 'Interface n tutlayt inek <b>$1</b> ulaci-tt sɣur {{ns:special}}:Allmessages deg udeg-agi.',
'allmessagesnotsupportedDB' => "'''{{ns:special}}:Allmessages''' ut yezmir ara ad yettuseqdac axaṭer '''\$wgUseDatabaseMessages''' yettwakkes.",
'allmessagesfilter'         => 'Tastayt n yisem n izen:',
'allmessagesmodified'       => 'Ssken win yettubeddlen kan',

# Thumbnails
'thumbnail-more'  => 'Ssemɣer',
'missingimage'    => '<b>Tugna ulac-itt</b><br /><i>$1</i>',
'filemissing'     => 'Afaylu ulac-it',
'thumbnail_error' => 'Agul asmi yexleq tugna tamecṭuḥt: $1',

# Special:Import
'import'                     => 'Ssekcem isebtaren',
'importinterwiki'            => 'Assekcem n transwiki',
'import-interwiki-history'   => 'Xdem alsaru n akk tisiwal umezruy n usebtar-agi',
'import-interwiki-submit'    => 'Ssekcem',
'import-interwiki-namespace' => 'Azen isebtaren ar isem n taɣult:',
'importstart'                => 'Asekcem n isebtaren...',
'import-revision-count'      => '$1 {{PLURAL:$1|tasiwelt|tisiwal}}',
'importnopages'              => 'Ulac isebtaren iwekken ad ttussekcmen.',
'importfailed'               => 'Asekcem yexser: $1',
'importunknownsource'        => 'Anaw n uɣbalu n usekcem mačči d mechur',
'importcantopen'             => 'Ur yezmir ara ad yexdem asekcem n ufaylu',
'importbadinterwiki'         => 'Azday n interwiki ur yelhi',
'importnotext'               => 'D ilem neɣ ulac aḍris',
'importsuccess'              => 'Asekcem yekfa!',
'importhistoryconflict'      => 'Amennuɣ ger tisiwal n umezruy (waqila asebtar-agi yettwazen yagi)',
'importnosources'            => 'Asekcam n transwiki ur yexdim ara u amezruy n usekcam yettwakkes.',
'importnofile'               => 'ulaḥedd afaylu usekcam ur yettwazen.',
'importuploaderror'          => 'Ur yezmir ara yazen ufaylu n usekcam; waqila ufaylu meqqer aṭṭas.',

# Import log
'importlogpage'                    => 'Aɣmis n usekcam',
'importlogpagetext'                => 'Adeblan n usekcam n isebtaren i yesɛan amezruy ubeddel seg wiki tiyaḍ.',
'import-logentry-upload'           => 'Yessekcem [[$1]] s usekcam n ufaylu',
'import-logentry-upload-detail'    => '$1 tasiwelt(tisiwal)',
'import-logentry-interwiki'        => '$1 s transwiki',
'import-logentry-interwiki-detail' => '$1 tasiwelt(tisiwal) seg $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Asebtar n wemseqdac inu',
'tooltip-pt-anonuserpage'         => 'Asebtar wemseqdac n IP wukud tekkiḍ',
'tooltip-pt-mytalk'               => 'Asebtar n umyannan inu',
'tooltip-pt-anontalk'             => 'Amyannan ɣef yibeddlen n tansa ip-yagi',
'tooltip-pt-preferences'          => 'Isemyifiyen inu',
'tooltip-pt-watchlist'            => 'Amuɣ uɛessi n yisebtaren i ttɛessaɣ asmi ttubeddlen',
'tooltip-pt-mycontris'            => 'Amuɣ n tikkin inu',
'tooltip-pt-login'                => 'Lukan tkecmeḍ xir, meɛna am tebɣiḍ.',
'tooltip-pt-anonlogin'            => 'Lukan tkecmeḍ xir, meɛna am tebɣiḍ.',
'tooltip-pt-logout'               => 'Ffeɣ',
'tooltip-ca-talk'                 => 'Amyannan ɣef wayen yella deg usebtar',
'tooltip-ca-edit'                 => 'Tzemreḍ ad tbeddleḍ asebtar-agi. Sseqdec pre-timeẓriwt qbel.',
'tooltip-ca-addsection'           => 'Rnu awennit i amyannan-agi.',
'tooltip-ca-viewsource'           => 'Asebtar-agi yettwaḥrez. Tzemreḍ ad twaliḍ aɣbalu-ines.',
'tooltip-ca-history'              => 'Tisiwal ssabeq n usebtar-agi.',
'tooltip-ca-protect'              => 'Ḥrez asebtar-agi',
'tooltip-ca-delete'               => 'Mḥu asebtar-agi',
'tooltip-ca-undelete'             => 'Err akk ibeddlen n usebtar-agi i yellan uqbel ma yettwamḥu usebtar',
'tooltip-ca-move'                 => 'Smimeḍ asebtar-agi',
'tooltip-ca-watch'                => 'Rnu asebtar-agi i umuɣ uɛessi inek',
'tooltip-ca-unwatch'              => 'Kkes asebtar-agi seg umuɣ uɛessi inek',
'tooltip-search'                  => 'Nadi {{SITENAME}}',
'tooltip-p-logo'                  => 'Asebtar amenzawi',
'tooltip-n-mainpage'              => 'Ẓer asebtar amenzawi',
'tooltip-n-portal'                => 'Ɣef usenfar, ayen tzemrḍ ad txedmeḍ, anda tafeḍ tiɣawsiwin',
'tooltip-n-currentevents'         => 'Af ayen yeḍran tura',
'tooltip-n-recentchanges'         => 'Amuɣ n yibeddlen imaynuten deg wiki.',
'tooltip-n-randompage'            => 'Ẓer asebtar menwala',
'tooltip-n-help'                  => 'Amkan ideg tafeḍ.',
'tooltip-n-sitesupport'           => 'Ellil-aɣ',
'tooltip-t-whatlinkshere'         => 'Amuɣ n akk isebtaren i yesɛan azday ar dagi',
'tooltip-t-recentchangeslinked'   => 'Ibeddlen imaynuten deg yisebtaren myezdin seg asebtar-agi',
'tooltip-feed-rss'                => 'RSS feed n usebtar-agi',
'tooltip-feed-atom'               => 'Atom feed n usebtar-agi',
'tooltip-t-contributions'         => 'Ẓer amuɣ n tikkin n wemseqdac-agi',
'tooltip-t-emailuser'             => 'Azen e-mail i wemseqdac-agi',
'tooltip-t-upload'                => 'Azen tugna neɣ afaylu nniḍen',
'tooltip-t-specialpages'          => 'Amuɣ n akk isebtaren usligen',
'tooltip-ca-nstab-main'           => 'Ẓer ayen yellan deg usebtar',
'tooltip-ca-nstab-user'           => 'Ẓer asebtar n wemseqdac',
'tooltip-ca-nstab-media'          => 'Ẓer asebtar n media',
'tooltip-ca-nstab-special'        => 'Wagi asebtar uslig, ur tezmireḍ ara a t-tbeddleḍ',
'tooltip-ca-nstab-project'        => 'Ẓer asebtar usenfar',
'tooltip-ca-nstab-image'          => 'Ẓer asebtar n tugna',
'tooltip-ca-nstab-mediawiki'      => 'Ẓer izen n system',
'tooltip-ca-nstab-template'       => 'Ẓer talɣa',
'tooltip-ca-nstab-help'           => 'Ẓer asebtar n tallat',
'tooltip-ca-nstab-category'       => 'Ẓer asebtar n taggayt',
'tooltip-minoredit'               => 'Wagi d abeddel afessas',
'tooltip-save'                    => 'Smekti ibeddlen inek',
'tooltip-preview'                 => 'G leɛnaya-k, pre-ẓer ibeddlen inek uqbel ma tesmektiḍ!',
'tooltip-diff'                    => 'Ssken ayen tbeddleḍ deg uḍris.',
'tooltip-compareselectedversions' => 'Ẓer amgirred ger snat tisiwlini (i textareḍ) n usebtar-agi.',
'tooltip-watch'                   => 'Rnu asebtar-agi i umuɣ uɛessi inu',
'tooltip-recreate'                => 'Ɛiwed xleq asebtar ɣas akken yettumḥu',

# Attribution
'anonymous'        => 'Amseqdac udrig (Imseqdacen udrigen) n {{SITENAME}}',
'siteuser'         => '{{SITENAME}} amseqdac $1',
'lastmodifiedatby' => 'Tikelt taneggarut asmi yettubeddel asebtar-agi $2, $1 sɣur $3.', # $1 date, $2 time, $3 user
'and'              => 'u',
'othercontribs'    => 'Tikkin n wemseqdac-agi.',
'others'           => 'wiyaḍ',
'siteusers'        => '{{SITENAME}} amseqdac(imseqdacen) $1',
'creditspage'      => 'Win ixedmen asebtar',
'nocredits'        => 'Ulac talɣut ɣef wayen ixedmen asebtar-agi.',

# Spam protection
'spamprotectiontitle'  => 'Aḥraz amgel "Spam"',
'spamprotectiontext'   => "Asebtar i tebɣiḍ ad tesmektiḍ iɛekkel-it ''aḥraz amgel \"Spam\"''. Waqila yella azday aberrani.",
'spamprotectionmatch'  => 'Aḍris-agi ur iɛeğ-it \'\'"aḥraz amgel "Spam"\'\': $1',
'subcategorycount'     => '{{PLURAL:$1|Tella yiwen taggayt tazellumt|Llant $1 taggayin tizellumin}} deg taggayt-agi.',
'categoryarticlecount' => '{{PLURAL:$1|Yella yiwen amagrad|Llan $1 imagraden}} deg taggayt-agi.',
'category-media-count' => '{{PLURAL:$1|Yella yiwen afaylu|Llan $1 ifayluwen}} deg taggayt-agi.',
'spam_reverting'       => 'Asuɣal i tasiwel taneggarut i ur tesɛi ara izdayen ɣer $1',
'spam_blanking'        => 'Akk tisiwal sɛan izdayen ɣer $1, ad yemḥu',

# Info page
'infosubtitle'   => 'Talɣut i usebtar',
'numedits'       => 'Geddac n yibeddlen (amagrad): $1',
'numtalkedits'   => 'Geddac n yibeddlen (asebtar n umyannan): $1',
'numwatchers'    => 'Geddac n yiɛessasen: $1',
'numauthors'     => 'Geddac n yimsedac i yuran (amagrad): $1',
'numtalkauthors' => 'Geddac n yimsedac i yuran (asebtar umyennan): $1',

# Math options
'mw_math_png'    => 'Daymen err-it PNG',
'mw_math_simple' => 'HTML ma yella amraḍi, ma ulac PNG',
'mw_math_html'   => 'HTML ma yezmer neɣ PNG ma ulac',
'mw_math_source' => 'Eğğ-it s TeX (i browsers/explorateurs n weḍris)',
'mw_math_modern' => 'Mliḥ i browsers/explorateurs imaynuten',
'mw_math_mathml' => 'MathML ma yezmer (experimental)',

# Patrolling
'markaspatrolleddiff'                 => 'Rcem "yettwassenqden"',
'markaspatrolledtext'                 => 'Rcem amagrad-agi "yettwassenqden"',
'markedaspatrolled'                   => 'Rcem belli yettwasenqed',
'markedaspatrolledtext'               => 'Tasiwelt i textareḍ tettwassenqed.',
'rcpatroldisabled'                    => 'Yettwakkes asenqad n ibeddlen imaynuten',
'rcpatroldisabledtext'                => 'Yettwakkes asenqad n ibeddlen imaynuten',
'markedaspatrollederror'              => 'Ur yezmir ara ad yercem "yettwassenqden"',
'markedaspatrollederrortext'          => 'Yessefk ad textareḍ tasiwelt akken a tt-trecmeḍ "yettwassenqden".',
'markedaspatrollederror-noautopatrol' => 'Ur tezmireḍ ara ad trecmeḍ ibeddilen inek "yettwassenqden".',

# Patrol log
'patrol-log-page' => 'Aɣmis n wasenqad',
'patrol-log-line' => 'Yercem tasiwelt $1 n $2 "yettwassenqden" $3',
'patrol-log-auto' => '(otomatik)',

# Image deletion
'deletedrevision' => 'Tasiwelt taqdimt $1 tettymḥa',

# Browsing diffs
'previousdiff' => '← Amgirred ssabeq',
'nextdiff'     => 'amgirred ameḍfir →',

# Media information
'mediawarning'         => "'''Aɣtal''': Waqila afaylu-yagi yesɛa angal aḥraymi, lukan a t-tesseqdceḍ yezmer ad ixesser aselkim inek.<hr />",
'imagemaxsize'         => 'Ḥedded tiddi n tugniwin deg yiglamen n tugniwim i:',
'thumbsize'            => 'Tiddi n tugna tamecṭuḥt:',
'file-info'            => '(tiddi n ufaylu: $1, anaw n MIME: $2)',
'file-info-size'       => '($1 × $2 pixel, tiddi n ufaylu: $3, anaw n MIME: $4)',
'file-nohires'         => '<small>Ur tella ara resolution i tameqqrant fell-as.</small>',
'file-svg'             => '<small>Wagi d tugna s lvecteur iwumi truḥ ara taɣarfa ines. Tiddi n ubuḍ: $1 × $2 pixels.</small>',
'show-big-image'       => 'Resolution tameqqrant',
'show-big-image-thumb' => '<small>Tiddi n pre-timeẓriwt-agi: $1 × $2 pixels</small>',

'newimages' => 'Amuɣ n ifayluwen imaynuten',
'noimages'  => 'Tugna ulac-itt.',

'passwordtooshort' => 'Awal n tbaḍnit inek d amecṭuḥ bezzaf. Yessefk ad yesɛu $1 isekkilen neɣ kter.',

# External editor support
'edit-externally'      => 'Beddel afaylu-yagi s usnas aberrani.',
'edit-externally-help' => 'Ẓer [http://meta.wikimedia.org/wiki/Help:External_editors taknut] iwakken tessneḍ kter.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'akk',
'imagelistall'     => 'akk',
'watchlistall2'    => 'akk',
'namespacesall'    => 'akk',

# E-mail address confirmation
'confirmemail'            => 'Sentem tansa n e-mail',
'confirmemail_noemail'    => 'Ur tesɛiḍ ara tansa n email ṣaḥiḥ deg [[Special:Preferences|isemyifiyen n wemseqdac]] inek.',
'confirmemail_text'       => 'Deg wiki-yagi, yessefk ad tvalidiḍ tansa n email inek
qbel ma tesseqdceḍ iḍaɣaren n email. Tella taqeffalt d akessar, wekki fell-as
iwakken yettwazen ungal n usentem semail. Email-nni yesɛa azady, ldi-t.',
'confirmemail_pending'    => '<div class="error">
Yettwazen-ak yagi ungal n usentem; lukan txelqeḍ isem wemseqdac tura kan,
ahat yessefk ad tegguniḍ cwiṭ qbel ma tɛreḍeḍ ad testeqsiḍ ɣef ungal amaynut.
</div>',
'confirmemail_send'       => 'Azen-iyi-d angal n usentem s e-mail iwakken ad snetmeɣ.',
'confirmemail_sent'       => 'E-mail yettwazen iwakken ad tsentmeḍ.',
'confirmemail_oncreate'   => 'Angal n usentem yettwazen ar tansa n e-mail inek.
Yessefk ad tesseqdceḍ angal-agi iwakken ad tkecmeḍ, meɛna yessefk a t-tefkeḍ
iwakken ad xedmen yiḍaɣaren n email deg wiki-yagi.',
'confirmemail_sendfailed' => 'Ur yezmir ara ad yazen asentem n email. Ssenqed tansa n email inek.

Email yuɣal-d: $1',
'confirmemail_invalid'    => 'Angal n usentem mačči ṣaḥiḥ. Waqila yemmut.',
'confirmemail_needlogin'  => 'Yessefk $1 iwakken tesnetmeḍ tansa n email inek.',
'confirmemail_success'    => 'Asentem n tansa n email inek yekfa. Tura tzemreḍ ad tkecmeḍ.',
'confirmemail_loggedin'   => 'Asentem n tansa n email inek yekfa tura.',
'confirmemail_error'      => 'Yella ugur s usmekti n usentem inek.',
'confirmemail_subject'    => 'Asentem n tansa n email seg {{SITENAME}}',
'confirmemail_body'       => 'Amdan, waqila d kečč, seg tansa IP $1, yexleq
isem n wemseqdac "$2" s tansa n e-mail deg {{SITENAME}}.

Iwakken tbeyyneḍ belli isem n wemseqdac inek u terreḍ
iḍaɣaren n email ad xdemen deg {{SITENAME}}, ldi azday agi:

$3

Lukan mačči d *kečč*, ur teḍfireḍ ara azday. Angal n usentem-agi
ad yemmut ass $4.',

# Scary transclusion
'scarytranscludedisabled' => '[Yettwakkes assekcam n yisebtaren seg wiki wiyaḍ]',
'scarytranscludefailed'   => '[Ur yezmir ara yewwi-d talɣa n $1; suref-aɣ]',
'scarytranscludetoolong'  => '[URL d aɣezfan bezzaf; suref-aɣ]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">
Izdayen n zdeffir n umagrad-agi:<br />
$1
</div>',
'trackbackremove'   => ' ([$1 Mḥu])',
'trackbacklink'     => 'Azday n zdeffir',
'trackbackdeleteok' => 'Azday n zdeffir yettumḥa.',

# Delete conflict
'deletedwhileediting' => 'Aɣtal: Asebtar-agi yettumḥa qbel ma tebdiḍ a tt-tbeddleḍ!',
'confirmrecreate'     => "Amseqdac [[User:$1|$1]] ([[User talk:$1|Meslay]]) yemḥu asebtar-agi beɛd ma tebdiḍ abeddel axaṭer:
: ''$2''
G leɛnaya-k sentem belli ṣaḥḥ tebɣiḍ ad tɛiwedeḍ axlaq n usebtar-agi.",
'recreate'            => 'Ɛiwed xleq',

# HTML dump
'redirectingto' => 'Asemmimeḍ ar [[$1]]...',

# action=purge
'confirm_purge' => 'Mḥu lkac n usebtar-agi?

$1',

'youhavenewmessagesmulti' => 'Tesɛiḍ iznan imaynuten deg $1',

'searchcontaining' => "Inadi isebtaren i isɛan ''$1''.",
'searchnamed'      => "Nadi ɣef imagraden ttusemman ''$1''.",
'articletitles'    => "Imagraden i yebdan s ''$1''",
'hideresults'      => 'Ffer igmad',

'loginlanguagelabel' => 'Tutlayt: $1',

# Multipage image navigation
'imgmultipageprev'   => '← asebtar ssabeq',
'imgmultipagenext'   => 'asebtar ameḍfir →',
'imgmultigo'         => 'Ruḥ!',
'imgmultigotopre'    => 'Ruḥ s asebtar',
'imgmultiparseerror' => 'Afaylu n tugna yexser, ihi {{SITENAME}} ur yezmir ara ad yaf amuɣ n yisebtaren.',

# Table pager
'ascending_abbrev'         => 'asawen',
'descending_abbrev'        => 'akessar',
'table_pager_next'         => 'Asebtar ameḍfir',
'table_pager_prev'         => 'Asebtar ssabeq',
'table_pager_first'        => 'Asebtar amezwaru',
'table_pager_last'         => 'Asebtar aneggaru',
'table_pager_limit'        => 'Ssken $1 n yiferdas di mkul asebtar',
'table_pager_limit_submit' => 'Ruḥ',
'table_pager_empty'        => 'Ulac igmad',

# Auto-summaries
'autosumm-blank'   => 'Yekkes akk aḍris seg usebtar',
'autosumm-replace' => "Ibeddel asebtar s '$1'",
'autoredircomment' => 'Asemmimeḍ ar [[$1]]', # This should be changed to the new naming convention, but existed beforehand
'autosumm-new'     => 'Asebtar amaynut: $1',

# Size units
'size-bytes'     => '$1 B/O',
'size-kilobytes' => '$1 KB/KO',
'size-megabytes' => '$1 MB/MO',
'size-gigabytes' => '$1 GB/GO',

# Live preview
'livepreview-loading' => 'Assisi…',
'livepreview-ready'   => 'Assisi… D ayen!',
'livepreview-failed'  => 'Pre-timeẓriwt tağiḥbuṭ texser!
Ɛreḍ pre-timeẓriwt tamagnut.',
'livepreview-error'   => 'Pre-timeẓriwt tağiḥbuṭ texser: $1 "$2"
Ɛreḍ pre-timeẓriwt tamagnut.',

);

?>

<?php
/** Upper Franconian (Mainfränkisch)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Altaileopard
 * @author Silvicola
 */

$fallback = 'de';

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Schbädsjaal',
	NS_TALK             => 'Disghusjoon',
	NS_USER             => 'Bänudsâr',
	NS_USER_TALK        => 'Bänudsârdisghusjoon',
	NS_PROJECT_TALK     => '$1disghusjoon',
	NS_FILE             => 'Dôdaj',
	NS_FILE_TALK        => 'Dôdajdisghusjoon',
	NS_MEDIAWIKI        => 'Meedjawigi',
	NS_MEDIAWIKI_TALK   => 'Meedjawigidisghusjoon',
	NS_TEMPLATE         => 'Foorlaachâ',
	NS_TEMPLATE_TALK    => 'Foorlaachândisghusjoon',
	NS_HELP             => 'Hilwâ',
	NS_HELP_TALK        => 'Hilwâdisghusjoon',
	NS_CATEGORY         => 'Gadâgorii',
	NS_CATEGORY_TALK    => 'Gadâgoriidisghusjoon',
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Dobâldâ_Wajdârlajdungân' ),
	'Userlogin'                 => array( 'Ôômäldâ' ),
	'Userlogout'                => array( 'Ôbmäldâ' ),
	'Preferences'               => array( 'Ôischtälungâ' ),
	'Watchlist'                 => array( 'Bäoobôchdungslisdâ' ),
	'Recentchanges'             => array( 'Lädsdâ_Änârungâ' ),
	'Upload'                    => array( 'Hoochlaadâ' ),
	'Statistics'                => array( 'Schdadisdign' ),
	'Newpages'                  => array( 'Nojâ_Sajdâ' ),
	'Allpages'                  => array( 'Ôlâ_Sajdâ' ),
	'Prefixindex'               => array( 'Indägs' ),
	'Specialpages'              => array( 'Schbädsjaalsajdâ' ),
	'Contributions'             => array( 'Bajdräächâ' ),
	'Emailuser'                 => array( 'Iimäjlâ' ),
	'Confirmemail'              => array( 'Iimäjl_bschdädigâ' ),
	'Movepage'                  => array( 'Sajdâ_färschiibâ' ),
	'Categories'                => array( 'Gadâgoriin' ),
	'Export'                    => array( 'Ägsbordiirn' ),
	'Allmessages'               => array( 'Ôlâ_Nôôchrichdâ' ),
	'Undelete'                  => array( 'Wiidârhärschdälâ' ),
	'Import'                    => array( 'Imbordiirn' ),
	'Unwatchedpages'            => array( 'Unbäoobôchdâdâ_Sajdn' ),
);

$messages = array(
# User preference toggles
'tog-underline'              => 'Linggs undârschdrajchn:',
'tog-highlightbroken'        => 'Linggs auf sajdn, diis ned gajd, soo ôôdsajchn: <a href="" class="new">bajschbiil</a> (sunschd: soo<a href="" class="internal">?</a>)',
'tog-justify'                => 'Dhägsd in Blogsads',
'tog-hideminor'              => 'Glaane ändrungn ned ôôdsajchn',
'tog-hidepatrolled'          => 'Ned dsajchn in dâ „Ledschdâ Ändrungn“, was an andrar schon brüüfd had',
'tog-showtoc'                => 'Inhalds-fârdsajchnis ôôdsajchn baj määr wi 3 iiwârschrifdn',
'tog-rememberpassword'       => 'Uf dem Ghombjuudâr schdändich ôôgmäld blajwn (for a maximum of $1 {{PLURAL:$1|day|days}})',
'tog-watchcreations'         => 'Ale fon miir sälwâr gmachdn sajdn soln uf mâj beoobachdungs-lischdn',
'tog-watchdefault'           => 'Ale fon miir gändârdnd sajdn soln uf mâj beoobachdungs-lischdn',
'tog-watchmoves'             => 'Ale fon miir fârschoobnen sajdn soln uf mâj beoobachdungs-lischdn',
'tog-watchdeletion'          => 'Ale fon miir gleschdn sajdn soln uf mâj beoobachdungs-lischdn',
'tog-minordefault'           => 'Ale maj ändrungn soln als glaane geldn',
'tog-previewontop'           => 'Foorschau owârhalb un ned undârhalb fom arbajds-fenschdâr',
'tog-previewonfirst'         => 'Baj dr ärschdn ändrung imâr ärschd â foorschau ôôdsajchn',
'tog-nocache'                => 'Sajdn-cache ausschaldn',
'tog-enotifwatchlistpages'   => 'Ii wil â iimejl griign, wen sich was beoobachdeds ändârd',
'tog-enotifusertalkpages'    => 'Ii wil â iimejl griign, wen sich uf majnâr disghusjoons-sajdn was duud',
'tog-enotifminoredits'       => 'I wil aa baj bloos glaanân ädrungn â iimejl griign',
'tog-enotifrevealaddr'       => 'Maj iimejl-adresn in iimejls dsur benoochrichdichungs dâdsuu-schrajwn',
'tog-shownumberswatching'    => 'Dii andsôôl dr beoobachdâr ôôdsajchn',
'tog-oldsig'                 => 'foorschau fon dr agduäln signaduur:',
'tog-fancysig'               => 'Signaduur is dhägsd in wighi-sindhags (alsâ ned audomaadisch â lingg)',
'tog-externaleditor'         => 'Schdandardwäässich an ägsdhärnân eedidhâr neemn (nôr for di sich ausghenn, dâdsuu
mus mr ufm ajchnen rächnâr was ajrichdn gehnn)',
'tog-externaldiff'           => 'Â ägsdhärns Brogram dsum ôôdsjachn fon dâ wärsjoons-undârschiid neemn (nôr fir dii sich
ausghenn, mr mus dâdsuu ufm ajchnen rächnâr was âjrichdn ghenn)',
'tog-uselivepreview'         => 'Schnäl-foorschau benudsn (brauchd JavaScript) (ärschd ân fârsuuch)',
'tog-forceeditsummary'       => 'Sich erinärn lasn, wemmâr ghâ dsusamnfasung gschriiwn had',
'tog-watchlisthideown'       => 'Ajchne bearbajdungn ned in dr beoobachdungs-lischdn uffiirn',
'tog-watchlisthidebots'      => 'Bot-bearbajdungn ned in dr beoobachdungs-lischdn uffiirn',
'tog-watchlisthideminor'     => 'Glaane bearbajdungn ned in dr beoobachdungs-lischdn uffiirn',
'tog-watchlisthideliu'       => 'Bearbajdungn fon ôôgmeldedn benudsârn ned in dr beoobachdungs-lischdn uffiirn',
'tog-watchlisthideanons'     => 'Bearbajdungn fon anoniimn benudsârn (IPs) ned in dr beoobachdungs-lischdn uffiirn',
'tog-watchlisthidepatrolled' => 'Ghondroliirde bearbajdungn ned in dr beoobachdungs-lischdn uffiirn',
'tog-ccmeonemails'           => 'Schig mr â ghobii fon jeedâr iimejl, was ii fârschig',
'tog-diffonly'               => 'Bajm värsjoongs-fârglajch nôr dii fârändrungn ôôdsajchn, ned di fole sajdn dâdsuu',
'tog-showhiddencats'         => 'Fârschdegde ghadegoriin dsajchn',

# Dates
'january'       => 'Januaar',
'february'      => 'Feebruaar',
'march'         => 'Märds',
'april'         => 'Abril',
'may_long'      => 'Maj',
'june'          => 'Juuni',
'july'          => 'Juuli',
'august'        => 'Auguschd',
'september'     => 'Säbdembär',
'october'       => 'Ogdoobär',
'november'      => 'Nowembär',
'december'      => 'Dädsembär',
'january-gen'   => 'fom Januaar',
'february-gen'  => 'fom Feebruaar',
'march-gen'     => 'fom Märds',
'april-gen'     => 'fom Abril',
'may-gen'       => 'fom Maj',
'june-gen'      => 'fom Juunii',
'july-gen'      => 'fom Juulii',
'august-gen'    => 'fom Auguschd',
'september-gen' => 'fom Säbdembâr',
'october-gen'   => 'fom Ogdoowâr',
'november-gen'  => 'fom Nowembâr',
'december-gen'  => 'Fom Dädsembâr',
'jan'           => 'Jan.',
'feb'           => 'Feeb.',
'mar'           => 'Mär.',
'apr'           => 'Abr.',
'may'           => 'Maj',
'jun'           => 'Juun.',
'jul'           => 'Juul.',
'aug'           => 'Aug.',
'sep'           => 'Säb.',
'oct'           => 'Ogd.',
'nov'           => 'Now.',
'dec'           => 'Däds.',

# Categories related messages
'pagecategories'         => '{{PLURAL:$1|ghadegorii|ghadegoriin}}',
'category_header'        => 'Sajdn in dr ghadegorii „$1“',
'subcategories'          => 'Unda-ghadegoriin',
'category-media-header'  => 'Meedjen in dr ghadegorii „$1“',
'category-empty'         => "''Dsu däär ghadegorii ghärn dsur dsajd gâ sajdn odr meedjen.''",
'hidden-categories'      => '{{PLURAL:$1|Fârschdegde ghadegorii|Fârschdegde ghadegoriin}}',
'category-subcat-count'  => 'Di ghadegorii umfasd {{PLURAL:$2|bloos a undâr-ghadegorii|dsam $2 undâr-ghadegoriâ, wofoo {{PLURAL:$1|nôr ôône| $1}}}} undn ôôdsajchd wärn.',
'category-article-count' => 'Di ghadegorii umfasd {{PLURAL:$2|bloos a sajdn|$2 sajdn, wofoo hiir {{PLURAL:$1|aane undn ôôdsajchd wärd|l$1 ôôdsajchd undn wärn}}}}.',
'listingcontinuesabbrev' => '(Fôrdsedsung)',

'newwindow'  => '(Wärd in am najn fenschdâ daargschdeld)',
'cancel'     => 'Abbrechn',
'mytalk'     => 'Mâj disghusjoonssajdn',
'navigation' => 'Nawigadsjoon',
'and'        => '&#32;un',

# Cologne Blue skin
'qbfind'         => 'Findn',
'qbbrowse'       => 'Schdeewârn',
'qbedit'         => 'Ändrn',
'qbpageoptions'  => 'Sajdn-âjschdelungn',
'qbmyoptions'    => 'Mâj sajdn',
'qbspecialpages' => 'Sondâr-sajdn',
'faq'            => 'FAQ',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection' => 'Ân najn abschnid ôôfangn',
'vector-action-delete'     => 'Leschn',
'vector-action-move'       => 'Umdaafn',
'vector-action-protect'    => 'Schidsn',
'vector-action-undelete'   => 'Leschn riggängich machn',
'vector-action-unprotect'  => 'Schuds ufgeebn',
'vector-view-create'       => 'Ôôleechn',
'vector-view-edit'         => 'Bearbajdn',
'vector-view-history'      => 'Wärsjoonsfolche',
'vector-view-view'         => 'Leesn',
'vector-view-viewsource'   => 'Gwäl-dhägsd ôôgugn',
'namespaces'               => 'Nôômsrajm',
'variants'                 => 'Warjandn',

'errorpagetitle'    => 'Feelär',
'returnto'          => 'Dsrig dsur sajdn $1.',
'tagline'           => 'Aus {{SITENAME}}',
'help'              => 'Hilfe',
'search'            => 'Suuche',
'searchbutton'      => 'Suchng',
'searcharticle'     => 'Af di sajdn',
'history'           => 'Wärsjoonsfolche',
'history_short'     => 'Wärsjoonsfolche',
'updatedmarker'     => "is gändârd wôrn sajde ds'ledschd dôô wôôr",
'info_short'        => 'Infôrmadsjoon',
'printableversion'  => 'Drug-wärsjoon',
'permalink'         => 'Beschdendichä lingh',
'print'             => 'Ausdrugâ',
'edit'              => 'Beärwâdn',
'create'            => 'Erdsojng',
'editthispage'      => 'Dii sajdn ändârn',
'create-this-page'  => 'Dii sajdn ôôleechn',
'delete'            => 'Leschn',
'deletethispage'    => 'Dii sajdn leschn',
'undelete_short'    => "{{PLURAL:$1|1 wärsjoon|$1 wärsjoon'n}} uugleschd machn",
'protect'           => 'Schidsn',
'protect_change'    => 'Ändârn',
'protectthispage'   => 'Dii sajdn schidsn',
'unprotect'         => 'Nime schidsn',
'unprotectthispage' => 'Dii sajdn nime schidsn',
'newpage'           => 'Naje sajdn',
'talkpage'          => 'Iwâr dii sajdn disghudiirn',
'talkpagelinktext'  => 'Disghusjoon',
'specialpage'       => 'Schbedsjaal-sajdn',
'personaltools'     => 'Ajchne werchdsajch',
'postcomment'       => 'Najn abschnid',
'articlepage'       => "D'inhalds-sajdn dsajchn",
'talk'              => 'Disghusjoon',
'views'             => 'Ôôsichdn',
'toolbox'           => 'Werchdsajch-ghisdn',
'userpage'          => "D'benudsârsajdn dsajchn",
'projectpage'       => "D'brojägdsajdn dsjachn",
'imagepage'         => "D'dadhaj-sajdn dsajchn",
'mediawikipage'     => "D'meldungs-sajdn dsajchn",
'templatepage'      => "D'foorlachn-sajdn dsajchn",
'viewhelppage'      => "D'hilfe-sajdn dsajchn",
'categorypage'      => "D'ghadegorii-sajdn dsajchn",
'viewtalkpage'      => 'Disghusjoon',
'otherlanguages'    => 'In anäre schbrôôchng',
'redirectedfrom'    => '(Wajdagschigd fo $1)',
'redirectpagesub'   => 'Wajdalajdungs-sajdn',
'lastmodifiedat'    => "D'sajdn is dsledsd am $1 um $2 uur gändârd wôrn.",
'viewcount'         => 'Dii sajdn is bis jeds {{PLURAL:$1|aamôôl|$1-môôl}} fârlangd wôrn.',
'protectedpage'     => 'Gschidsde sajdn',
'jumpto'            => 'Wajdä mid:',
'jumptonavigation'  => 'Wohii gea',
'jumptosearch'      => 'Suchng',
'view-pool-error'   => "Schaad, di särwa ghumn grôd ned nôôch, wal dsfiil lajd dii
sajdn ham woln. Ward n'bôôr minuudn un brobiir's dan nochâmôôl.

$1",

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Was {{SITENAME}} is',
'aboutpage'            => 'Project:Iibär',
'copyright'            => 'Was hiir schdäd däfmâr benudsn nach $1',
'copyrightpage'        => '{{ns:project}}:Uurheewâr-rächde',
'currentevents'        => 'Was grôôd basiird is',
'disclaimers'          => 'Imbräsum',
'disclaimerpage'       => 'Project:Imbräsum',
'edithelp'             => 'Hilfe dsum beärbâdn',
'edithelppage'         => 'Help:Beärbâdn',
'helppage'             => 'Help:Inhalds-fârdsajchnis',
'mainpage'             => 'Haubdsajdn',
'mainpage-description' => 'Haubdsajdn',
'policy-url'           => 'Project:Reechln',
'portal'               => 'Ajgang fir miidmachâr',
'portal-url'           => 'Project:Ajgang fir miidmachâr',
'privacy'              => 'Daadnschuds',
'privacypage'          => 'Project:Daadn-schuds',

'badaccess'        => 'Des däfsd duu ned',
'badaccess-group0' => 'So ebâs däfsch duu ned machn',
'badaccess-groups' => 'Des däfm bloos lajd machng, dii wo baj {{PLURAL:$2|där grubbm|anâr fon dâ grubm}} „$1“ sin.',

'versionrequired' => "S'brauchd dii wärsjoon $1 fon MediaWiki.",

'ok'                      => 'In ôrdnung',
'retrievedfrom'           => 'Fon „$1“ ghold',
'youhavenewmessages'      => "S'gajd $1 af dajnâr disghusjoons-sajdn ($2).",
'newmessageslink'         => 'naje middajlunga',
'newmessagesdifflink'     => 'lädschde fârendârung',
'youhavenewmessagesmulti' => "S'gajd naje middajlungn: $1",
'editsection'             => 'Beärbâdn',
'editold'                 => 'Bearbajdn',
'viewsourceold'           => 'Wighidhägsd dsajchn',
'editlink'                => 'beärbâdn',
'viewsourcelink'          => 'Wighidhägsd dsjachn',
'editsectionhint'         => 'Abschnid beärbâdn: $1',
'toc'                     => 'Inhaldsfadsajchnis',
'showtoc'                 => 'Dsajchn',
'hidetoc'                 => 'Färschdeggn',
'thisisdeleted'           => "Nôr in's gleschde $1 najgugn odr $1 uugleschd machn?",
'viewdeleted'             => '$1 dsajchn?',
'restorelink'             => '$1 {{PLURAL:$1|gleschde wärsjoon|gleschde wärsjoon}}',
'site-rss-feed'           => 'RSS-Feed fir $1',
'site-atom-feed'          => 'Atom-Feed fir $1',
'page-rss-feed'           => 'RSS-Feed fir „$1“',
'page-atom-feed'          => 'Atom-Feed fir „$1“',
'red-link-title'          => '$1 (Di sajdn is ned dôô)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Sajdn',
'nstab-user'      => 'Benudsä-sajdn',
'nstab-media'     => 'Meedjân-sajdn',
'nstab-special'   => 'Sonda-sajdn',
'nstab-project'   => 'Brojägd-sajdn',
'nstab-image'     => 'Dadhaj',
'nstab-mediawiki' => 'Sischdeem-mäldung',
'nstab-template'  => 'Foorlaach',
'nstab-help'      => 'Hilfs-sajdn',
'nstab-category'  => 'Ghadegorii',

# Main script and global functions
'nosuchaction'      => 'Des schded ned dsur auswaal',
'nosuchactiontext'  => "Di agdsjoon, dii in dr URL schdäd, ged ned.
Filajchd is di URL falsch gschriiwn, odr duu bisch âm falschn lingg nôôch.
S'ghend aa â brogramiirfäälâr in dr sofdwäâr sâj, dii baj {{SITENAME}} lefd.",
'nosuchspecialpage' => "Dii sôndâr-sajdn gajd's ned",
'nospecialpagetext' => '<strong>Duu hasch â sôndâr-sajdn ôôgruufn, dii wo uugildich is.</strong>

Â lsidn mid richdichn sôndâr-sajdn findsch undâr [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'              => 'Feelâr',
'databaseerror'      => 'Feelâr fon dr Daadnbangg',
'dberrortext'        => 'Bam abfrôôchn fon dr daadnbangg is was schiif gangn.
Filajchd weechn am brogramiir-feelâr?
Jeednfals wôôr di ledsd abfrôôchn:
<blockquote><tt>$1</tt></blockquote>
aus dr fungdsjoon „<tt>$2</tt>“.
Un dôôdruf had dan di daadnbangg den feelâr „<tt>$3: $4</tt>“ gmeld.',
'dberrortextcl'      => 'Dii daadnbangg-abfrôôchn wôôr falsch gschriiwn.
Di abfrôôchn wôôr neemlich
<blockquote><tt>$1</tt></blockquote>
aus dr fungdsjoon "<tt>$2</tt>". Un dôôdruf had dan di daadnbangg den feelâr „$3: $4“ gmeld.',
'laggedslavemode'    => "'''Achdung:''' Filajchd dsajchd dii sajdn noch ned ales, was indswischn gändârd wôrn is.",
'readonly'           => 'Di daadnbangg is gschbärd.',
'enterlockreason'    => 'Bide schrajb, wisoo dii daadnbangg gschbärd wärn sol, un wi lang des dan dauârn mechd.',
'readonlytext'       => "Di daadnbangg is grôôd gschbärd, alsâ ghamâr nids naj râjschrajwn odr ändrn. Brobiir's hald schbäädr noch âmôôl.

Gschbärd is se desdâweechn: $1",
'missing-article'    => "Di daadnbangh had dii sajdn „$1“ $2 ned gfundn.

Wen des basiird, dan massdn`s, wemma â dsu alde bearbajdung ôôschaua wil odâ ane fonra gleschdn sajdn.

Wen's des ned is, bisd womeeglich iwa ân feela in dr sofdwäâr gschdolbäd. In dämm Fall melds´däs, bidde mid där URL, am [[Special:ListUsers/sysop|Administrator]].",
'missingarticle-rev' => '(wärsjoonsnumâr: $1)',
'badtitletext'       => "Dii fârlangde sajdn gibd's ned, odâr sii had ân uugildichn sajdnnôôma ghabd, odâr s'wôôr â gschlambdâr fârwajs fonâm andârn wighi häär. Filajchd is aa â buuchschdôôb drin'n, däär in sajdnnôôm gôôr ned schdena däf.",
'viewsource'         => 'Gwäl-dhägsd ôôgugn',

# Login and logout pages
'yourname'                => 'Benudsârnôômâ',
'yourpassword'            => 'Bhaswôrd:',
'remembermypassword'      => 'Af dem ghombjuudâr schdändich ôôgmäld blajm (for a maximum of $1 {{PLURAL:$1|day|days}})',
'login'                   => 'Ôômeldn',
'nav-login-createaccount' => 'Oomeldn / Ghondoo ooleeng',
'userlogin'               => 'Ôômeldn / Als Bajdräächâr ajschrajm',
'logout'                  => 'Abmeldn',
'userlogout'              => 'Abmeldn',
'nologinlink'             => 'Sich als najâr Ôôgmeldâr ôômäldn',
'mailmypassword'          => 'Â najs passwôrd iwâr iimejl dsuschign lasn',

# Edit page toolbar
'bold_sample'     => 'Dägsd in fäd',
'bold_tip'        => 'Fädâr dhägschd',
'italic_sample'   => 'Ghursiif-dhägsd',
'italic_tip'      => 'Ghursiif-dhägsd',
'link_sample'     => 'Lingg-dhägsd',
'link_tip'        => 'Lingg inârhalb fom Wighi',
'extlink_sample'  => 'http://www.example.com Lingg-dhägsde',
'extlink_tip'     => 'Lingg nach ausârhalb (achdung, „http://“ ghäärd fôrnewäch dâdsu)',
'headline_sample' => 'Iiwârschrifd 2. ôrdnung',
'headline_tip'    => 'Iiwârschrifd 2. ôrdnung',
'math_sample'     => 'Dô dii fôrml  (in TEX) nâjschrajm',
'math_tip'        => 'Mademaadische Fôrml (in LATEX)',
'nowiki_sample'   => 'Dô än uunfôrmadiirdn dhägsd nâjschrajm',
'nowiki_tip'      => 'Uufôrmadiirdâr dhägsd',
'image_tip'       => 'Âjbedâde dadaj',
'media_tip'       => 'Lingh af â meedjâ-dadaj',
'sig_tip'         => "Dâj signadhuur dsamm mid'm dadum",
'hr_tip'          => 'Horidsondaalâr schdrich (bide schbôôrsam âjsedsâ)',

# Edit pages
'summary'                          => 'Dsamfasung:',
'subject'                          => 'Bedräf:',
'minoredit'                        => 'Blos a weng wôs is gändârd wôrn',
'watchthis'                        => 'Dii sajdn undâr beoobachdung nämma',
'savearticle'                      => 'Sajdn schbajchän',
'preview'                          => "Dii sajdn, wii's wärn dääd",
'showpreview'                      => "Schbign, wii's wärn dääd",
'showdiff'                         => 'Fârendârungn ôôdsajchn',
'anoneditwarning'                  => 'Duu hôsd Di ned ôôgmäld dsum ändârn fo derä sajdn. Wennsd´äs uuôôgmäld ändârsd un schbajchârsd, dan wärd Dâj IP-adresn endgildich mid in iirn ändârungsfârlaaf najgschriim un ghan dan eewich fo jeedn ghinfdich gleesn wärn.',
'missingsummary'                   => "'''Achdung:''' Du hasd bis jeds ghâ Dsamfasung dâdsuugschriiwn. Wen De's jeds ned noch mechsd un dan schbajchârsd, dan wärd Daj ändârung gands oone gmachd.",
'missingcommenttext'               => "Schrajb bide aa â dsamfasung, dâmid mr siid, wieso Duu g'ändârd hasd un waas.",
'missingcommentheader'             => "'''Achdung:''' Du hasd hindâr „Bedräf:“ gha ghinfdiche iiwârschrifd ôôgääwn. Wen De's ned noch mechsd un schbajchârsd, dan wäd's gands oone iiwârschrifd iwârnomn.",
'summary-preview'                  => "Was in'd dsusammfasungsdsajln najghumd:",
'subject-preview'                  => 'Wii dr bedräf aussään wärd:',
'blockedtitle'                     => 'Dr bajdreechâr is gschbärd.',
'blockedtext'                      => "'''Du bisd gschbärd wôrn, jee nachdeem mid'm nôômn odâr mid dr IP-adresn.'''

Gmachd had des: $1
Desweechn: ''$2''

* Dii schbärung had dôô ôôgfangn: $8
* Dii schbärung lefd dôô aus: $6
* Des is dâbaj gschbärd: $7

Du ghôôsch De grood an $1 wendn odâr aa an ân andârn [[{{MediaWiki:Grouppage-sysop}}|adminischdraadr]], wen De driiwâr disghudiirn wilst.

Wen De ghâ gildiche iimejl-adresn in Dajn [[Special:Preferences|Ajschdelungn]] ôôgeewen hasd, odr wen mr des iimejln fir Dii gschbärd had, dan ged des ned iiwâr „Iimejl an den bearbajdâr“.

Dâj IP-adressn is: $3
Dii schbärr-ID is: $5
Schrajb des bide als dâdsu, wen De Dich meldsd.",
'newarticle'                       => '(Naj)',
'newarticletext'                   => "Duu bisd âm fârwajs gfolchd, däär noch af ghâ sajdn dsajchd.
Um dii sajdn ôôdsleechn, schrajb Dajn dhägsd in deen rôôma dô undn naj (fir aandslhajdn, schau af dâr [[{{MediaWiki:Helppage}}|hilfesajdn]] nôôch).
Wen'D dich awâr hiirhäär bloos fârlaafn hasd, glig ââfach af '''Zurück''' in Dajm brausâr, dan geedâr dôôhi dsrig, wos'D häärghumma bisd.",
'noarticletext'                    => 'Dii sajdn gibd\'s bis eds no ned.
Duu ghâusch nach däm ausdrug aa [[Special:Search/{{PAGENAME}}|in alle sajdn suchng]],
<span class="plainlinks"> [{{fullurl:{{#special:Log}}|page={{FULLPAGENAMEE}}}} in di dsugheerichng log-biichâr suchng] odâr dii sajdn [{{fullurl:{{FULLPAGENAME}}|action=edit}} ôôleeng un najschrajm]</span>.',
'previewnote'                      => "'''Hiir siggsd bloos, wii's wärn dääd, dii sajdn is ôbâr no ned gschbaichärd!'''",
'editing'                          => 'Beärbâdn fon $1',
'editingsection'                   => 'Beärwâdn fo $1 (bloos abschnid)',
'copyrightwarning'                 => "''Ghobhiir jôô ghâ web-sajdn, dii där ned ghärn, un benuds ghâ uurheewarrechdlich gschidsde wärgghe oone geneemichung fom uurheewâr!'''<br />
Hirmid sagsd, das Du den dhägsd '''selbâr gschriim''' hasd, das däär dhägsd algemajnguud is '''(public domain)''' odâr das där '''uurheewâr'' dâmid '''ajfârschdandn''' is. Wen där dhägsd scho woanärsch fârefendlichd wôrn is, dan schrajb des bidde uf där disghusjoonssajdn.
<i>Achdung, ale {{SITENAME}}-bajdreech faln audomaadisch undâr di „$2“ (ajndslhajdn dâdsuu baj $1). Wen'd awâr doch ned wilsd, das des waas'd hiir gschriim hasd, fo annäre g'ändârd odär fârbrajded wäd, dan däfsd ned  „Sajdn schbajchârn“ glign.</i>",
'templatesused'                    => 'Af däär sajdn {{PLURAL:$1|wärd|wärn}} dii folchnde foorlach benudsd:',
'templatesusedpreview'             => 'Fon däär sajdnvorschau {{PLURAL:$1|wärd dii folchende foorlaach|wärn die folchendn foorlaachn}} benudsd:',
'template-protected'               => '(schrajbgschidsd)',
'template-semiprotected'           => '(ned ôôgmeldede un naje benudsär däfn hiir ned schrajm)',
'hiddencategories'                 => 'Dii sajdn ghäärd dsu {{PLURAL:$1|aanâr fârschdegdn|$1 fârschdegde}} ghadegoriin:',
'permissionserrorstext-withaction' => 'Du däfsd ned $2, des{{PLURAL:$1||}}dâsweechn:',

# History pages
'viewpagelogs'           => 'Logbicher fär dii sajdn dsajchn',
'currentrev-asof'        => 'Jedsiche wärsjoon, am $2 um $3 gmachd',
'revisionasof'           => 'Wärsjoon fom $2 um $3 Uur',
'previousrevision'       => '← wärsjoon dâfoor',
'nextrevision'           => 'Nägsdnajâre wärsjoon →',
'currentrevisionlink'    => 'Geechnwärdiche wärsjoon',
'cur'                    => 'undârschiid dsur jedsichng fasung',
'last'                   => 'Foorhäärich',
'histlegend'             => "Wääl aus, wasde fär ân undârschiid seen wilsd, un glig dan undn  „{{int:compareselectedversions}}“.<br />
* '''({{int:cur}})''' = undârschiid dsur geechnwärdichn wärsjoon, '''({{int:last}})''' = undârschiid dsur foorichn wärsjoon
* Uurdsajd/Daadum = wärsjoon dsu dära dsajd, '''{{int:minoreditletter}}''' = glane ändärung.",
'history-fieldset-title' => 'Suchng in där wärsjoonsfolche',
'histfirst'              => 'Äldâschde',
'histlast'               => 'Najsde',

# Revision deletion
'rev-delundel'   => 'ôôdsajng/fârbärng',
'revdel-restore' => 'Ändârn, was oodsajchd wäd',

# Merge log
'revertmerge' => 'Dsrig fôr dii fârajnichung',

# Diffs
'history-title'           => 'Wärsjoonsfolche fo „$1“',
'difference'              => '(Undârschiid dswischâ wärsjoonâ)',
'lineno'                  => 'Dsajln $1:',
'compareselectedversions' => 'Ausgwäälde wärsjoona fârglajchn',
'editundo'                => 'riggängich machng',

# Search results
'searchresults'             => 'Bam suchng gfundne sachng',
'searchresults-title'       => 'Gfundn bam suchng nach „$1“',
'searchresulttext'          => 'Wenn´sd wisn wilsd, wii genau mä´ alles af {{SITENAME}} suchng ghôô, dan gug af dâr [[{{MediaWiki:Helppage}}|Hilfssajdn]] nôôch.',
'searchsubtitle'            => 'Gsuchd wä´n soll nach: „[[:$1|$1]]“ ([[Special:Prefixindex/$1|aln sajdn, dii wo mid „$1“ ôôfangn]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|aln sajdn, dii wo af „$1“ fârwajsn]])',
'searchsubtitleinvalid'     => 'Daj Suchanfraache: „$1“.',
'notitlematches'            => 'Gha sajdn gfundn, däärn nôômâ basn dääd',
'notextmatches'             => 'Närchnds gfundn.',
'prevn'                     => '{{PLURAL:$1|foorichâr|fooriche $1}}',
'nextn'                     => '{{PLURAL:$1|nägschdâr|nägschde $1}}',
'viewprevnext'              => 'Dsajch ($1 {{int:pipe-separator}} $2) ($3)',
'search-result-size'        => '$1 ({{PLURAL:$2|1 wôrd|$2 wärdâr}})',
'search-result-score'       => 'Âjschleechich: $1 %',
'search-redirect'           => '(Wajdalajdung fon „$1“ häa)',
'search-section'            => '(Abschnid $1)',
'search-suggest'            => 'Hasd ajchndlich gmaand „$1“?',
'search-interwiki-caption'  => 'Schwesder-brojägd',
'search-interwiki-default'  => 'Af $1 gfundn:',
'search-interwiki-more'     => '(noch mäa´)',
'search-mwsuggest-enabled'  => 'mid foorschleech',
'search-mwsuggest-disabled' => 'ghane foorschlääch',
'search-relatedarticle'     => 'Fârwande',
'mwsuggest-disable'         => 'Foorschlääch iwâr AJAX abschdelâ',
'searcheverything-enable'   => 'In aln naamsrajm suuchn',
'searchrelated'             => 'fârwand',
'searchall'                 => 'ale',
'showingresults'            => "Hiâr {{PLURAL:$1|is '''1'''|sin '''$1'''}} Ärgääbnis , ôôgfangn baj numâr '''$2.'''",
'showingresultsnum'         => "Hiâr {{PLURAL:$3|is '''1''' |sin '''$3''' }} Ärgääbnis, oogfangn baj numâr '''$2.'''",
'showingresultsheader'      => "{{PLURAL:$5|Ärgääbnis '''$1''' don '''$3'''|Ärgääbnis '''$1–$2''' fon '''$3'''}} fir '''$4'''",
'nonefound'                 => "'''Oobachd:''' Oone wajdas wäd bloos in ajniche Nôômârajm gsuuchd. Wen'd iwarôôl (aa in disghusjoon'n, foorlaachn usw.) suchng wilsd, musd ''all:'' foorschrajm, oda aa den nôôma (midâm dobl-bhungd) fo genau dem nôômaraum, fon dem de waasd, dass´es nur in däm drin saj ko.",
'search-nonefound'          => 'Dsu dajna suuchfrôôchn is nigs gfundn wôrn.',
'powersearch'               => 'Suuche mid mäa oogaabm',
'powersearch-legend'        => 'Suuche mid mäa oogaam',
'powersearch-ns'            => 'In dena Nôômâsrajm suchng:',
'powersearch-redir'         => 'Wajdälajdunga oodsajng',
'powersearch-field'         => 'Suuch nôôch:',
'powersearch-togglelabel'   => 'Wääl aus:',
'powersearch-toggleall'     => 'Ale dsam',
'powersearch-togglenone'    => 'Gôôr ghane',
'search-external'           => 'Ägsdärne suach',
'searchdisabled'            => 'Diâ {{SITENAME}}-suâch ist ausgschald. Duu ghâusch so lang mid Google suâchn. Dengg drâu, des was mr dôô fir {{SITENAME}} find, ghâu iwârhoold saj.',

# Quickbar
'qbsettings'               => 'Sajdn-lajsdn',
'qbsettings-none'          => 'Ghane',
'qbsettings-fixedleft'     => 'Lings, feschd',
'qbsettings-fixedright'    => 'Rächds, feschd',
'qbsettings-floatingleft'  => 'Lings, schwääbnd',
'qbsettings-floatingright' => 'Rächds, schwääbnd',

# Preferences page
'preferences'               => 'ajschdelunga',
'mypreferences'             => 'Maj ajschdelunga',
'prefs-edits'               => 'So ofd umgmoodld:',
'prefsnologin'              => 'Ned ôôgmäld',
'prefsnologintext'          => 'Ärschd wen\'d <span class="plainlinks">[{{fullurl:{{#special:UserLogin}}|returnto=$1}} ôôgmäld]</span> bisch, ghôôsch dâj âjschdelungn ändârn.',
'changepassword'            => "S'bhaswôrd ändârn",
'prefs-skin'                => 'Schaale',
'skin-preview'              => 'Môôl schbign',
'prefs-math'                => 'TeX',
'datedefault'               => 'Nôrmaal',
'prefs-datetime'            => 'Daadum un dsajd',
'prefs-personal'            => 'Benudsâr-daadn',
'prefs-rc'                  => 'Ledschde Ändrungn',
'prefs-watchlist'           => 'Beoobachdungs-lischdn',
'prefs-watchlist-days'      => 'Wiifiil dääch dsrig in dr beoobachdungs-lischdn:',
'prefs-watchlist-days-max'  => 'Hechschdns 7 dääch',
'prefs-watchlist-edits'     => 'Wiifiil âjdrääch hechschdens:',
'prefs-watchlist-edits-max' => 'Hegschd-dsôôl: 1000',
'prefs-watchlist-token'     => "Token fir d'beoobachdungs-lisdn",
'prefs-misc'                => 'Diff-graam',
'prefs-resetpass'           => 'S#bhaswôrd ändârn',
'prefs-email'               => 'Iimejl-ajschdelungn',
'prefs-rendering'           => 'Ufbuds',
'saveprefs'                 => 'Aâjschdelungn schbajchrn',
'resetprefs'                => 'Nigs iwârneemn',
'restoreprefs'              => 'Uf dii uur-ajschdelungn dsrig',
'prefs-editing'             => 'Bearbajdungs-fenschdâr',
'prefs-edit-boxsize'        => 'Grees fom bearbajdungs-fenschdâr:',
'rows'                      => 'Soofiil dsajln:',
'columns'                   => 'Soofiil schbaldn',
'searchresultshead'         => 'Suuchn',
'resultsperpage'            => 'Broo rudsch dsajchn:',
'contextlines'              => 'Dsajln fir jeedn fund:',
'contextchars'              => 'Soofiil dsjachn broo dsjaln:',
'recentchangesdays'         => 'Wiifiil dääch dsrig baj  „Ledschdn ändrungn“',
'recentchangesdays-max'     => 'Hegschdns $1 {{PLURAL:$1|daach|dääch}}',
'recentchangescount'        => 'Wiifiil ändrungn dsrig baj „Ledschdn ändrungn“',
'localtime'                 => 'Hiisiche Uurdsajd:',
'timezoneuseserverdefault'  => 'Dsajd-dsoon fom server neem',
'timezoneuseoffset'         => 'Andre dsajd-dsoon (fârschiiwung undn ajndraachn)',
'timezoneoffset'            => 'Fârschiiwung¹:',
'servertime'                => 'Uurdsaj ufm Server',
'guesstimezone'             => 'Fom brausa iwârneem',
'timezoneregion-africa'     => 'Afrighaa',
'timezoneregion-america'    => 'Ameerighaa',
'timezoneregion-antarctica' => 'Andargdis',
'timezoneregion-arctic'     => 'Argdis',
'timezoneregion-asia'       => 'Aasjâ',
'timezoneregion-atlantic'   => 'Adlandischâr oodseaan',
'timezoneregion-australia'  => 'Asufraaljâ',
'timezoneregion-europe'     => 'Ojroobhaa',
'timezoneregion-indian'     => 'Indischâr oodseaan',
'timezoneregion-pacific'    => 'Bhadsiifischâr Oodseaan',
'allowemail'                => 'Iimejl-embfang fon andrâ ôôschdeln',

# Groups
'group-sysop' => 'Adminisdradoorn',

'grouppage-sysop' => '{{ns:project}}:Adminisdradoorn',

# User rights log
'rightslog' => 'Brodoghol fo rächde-dsuudaalung an bearbajdâr',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => "dii sajdn ds'beärbâdn",

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|ändrung|ändrunga}}',
'recentchanges'                  => 'ledsde änderunga',
'recentchanges-legend'           => 'Âjschdelunga, wii di ledsdn ändrunga dsajchd wärn solln',
'recentchanges-feed-description' => 'Fârfolch mid dem Fiid dii ledsdn ändrungn in {{SITENAME}}.',
'rcnote'                         => "Des {{Plural:$1|is dii aane ändrung|sin dii '''$1''' ändrunga}}, dii in di {{Plural:$2|ledsdn 24 schdundn|ledsdn '''$2''' doochn}} gmachd wôrn {{Plural:$1|is|sin}}. Schdand is fom $4, $5 uur.",
'rclistfrom'                     => 'Bloos di ändrunga dsajchn sajd $1',
'rcshowhideminor'                => 'Glenâre Ändrungn $1',
'rcshowhidebots'                 => 'Bods (bearbajdâr, dii ajchendlich brograme san) $1',
'rcshowhideliu'                  => 'Ôôgmäldâde bearbajdâr $1',
'rcshowhideanons'                => '$1 uuôôgmäldâde bearbajdâr',
'rcshowhidemine'                 => 'Ajchne bajdrääch $1',
'rclinks'                        => 'Dsajch dii ledsdn $1 ändrunga fo di ledsdn $2 dooch.<br />$3',
'diff'                           => 'undârschiid',
'hist'                           => 'Wärsjoonsfolche',
'hide'                           => 'fârschdegn',
'show'                           => 'dsajchn',
'minoreditletter'                => 'g',
'newpageletter'                  => 'N',
'boteditletter'                  => 'B',
'rc-enhanced-expand'             => 'Ajndslhajdn ôôdsajchn (gäd bloos mid JavaScript)',
'rc-enhanced-hide'               => 'Glaanichghajdn ned dsajng',

# Recent changes linked
'recentchangeslinked'         => 'Ändärunga af sajdn, af dii fo hiir fârwiisn wäd',
'recentchangeslinked-title'   => 'Ändrunga an sajdn, af dii fo „$1“ aus fârwiisn wärd.',
'recentchangeslinked-summary' => "Dii sôndârsajdn fiird di ledsdn ändrunga fon sajdn af, dii wo an däär hiir drôôhänga. Alles, was de dâfoo in daj [[Special:Watchlist|beoobachdunglisdn]] aufgnumma hasd, wäd aa no '''fäd''' ôôdsajchd.",
'recentchangeslinked-page'    => 'Sajdn:',
'recentchangeslinked-to'      => 'Dsajch dii ändrunga af sajdn, di woo hirhäär fârwajsn',

# Upload
'upload'        => 'Nauflôôdn',
'uploadlogpage' => 'Brodoghol fom dadaj-hoochlôôdn',
'uploadedimage' => 'had „[[$1]]“ naufglôôdn',

# File description page
'filehist'                  => 'Wärsjoona bis eds',
'filehist-help'             => 'Glig af ân dsajdbhungd, um dii dôômôôliche fasung ôôdsuschaua',
'filehist-current'          => 'agduäl',
'filehist-datetime'         => 'Âjschdlungs-daadum un -dsajd',
'filehist-thumb'            => 'Schbigbildlâ',
'filehist-thumbtext'        => "Wii d'fasung fom $2, $3 Uur grâub aussiid",
'filehist-user'             => 'Ôôgmeldâr',
'filehist-dimensions'       => 'Maase',
'filehist-filesize'         => 'Dadajgräâs',
'filehist-comment'          => 'Sembf dâdsuâ',
'filehist-missing'          => 'Dadaj fääld',
'imagelinks'                => 'Dsajchn, wo dii dadaj als benudsd wärd',
'linkstoimage'              => 'Dii dadaj wäd fo {{PLURAL:$1|därâ |denâ $1 }} sajdn benudsd:',
'linkstoimage-more'         => "Määr wii {{PLURAL:$1|ane |$1 }} sajdn fârwajsn uf diâ dadaj.
Dii lisdn undn dsajch dâfâu nôr äärschd môôl {{PLURAL:$1|an|$1}} fârwajs.
S'gajd awâr aa â [[Special:WhatLinksHere/$2|lisdn mid alâ fârwajs]].",
'nolinkstoimage'            => 'Diâ dadaj wärd närchends benudsd.',
'morelinkstoimage'          => '[[Special:WhatLinksHere/$1|Diâ folchende]] fârwajs uf diâ dadaj.',
'redirectstofile'           => 'Diâ {{PLURAL:$1|folchende dadaj schigd|folchende $1 dadajâ schigâ}} uf diâ dadaj wajdâr:',
'duplicatesoffile'          => 'Dii {{PLURAL:$1|folchende dadaj is â dublighaad|folchende $1 dadajâ sn dublighaade}} fon dâr dadaj ([[Special:FileDuplicateSearch/$2|wajdâre ôôndlshajdâ]]):',
'sharedupload'              => 'Dii dadaj ghumd fo $1, un mär däf se fär annäre brojägd aa ´heernemâ.',
'sharedupload-desc-there'   => 'Dii dadaj ghumd fon $1, un mr däf se fir andârâ brojägd aa nemâ. Genauârs schded uf dr [$2 beschrajwungssajdâ fon dr dadaj].',
'uploadnewversion-linktext' => 'Â naje wärsjoon fo derä dadaj nauflôôdn',

# Statistics
'statistics' => 'Schdadisdig',

# Miscellaneous special pages
'nbytes'        => '$1 {{PLURAL:$1|Bajd|Bajds}}',
'ncategories'   => '$1 {{PLURAL:$1|GhadegoriiGhadegoriin}}',
'nmembers'      => '{{PLURAL:$1|1 âjdrôôch|$1 âjdrääch}}',
'prefixindex'   => 'Ale sajdn mid brääfigs',
'newpages'      => 'Naje sajdn',
'move'          => 'Umdaafn',
'movethispage'  => 'Sajdn umdaafn',
'pager-newer-n' => '{{PLURAL:$1|nägschdâr|nägschd $1}}',
'pager-older-n' => '{{PLURAL:$1|foorichâr|fooriche $1}}',

# Book sources
'booksources'               => 'ISBN-Suuche',
'booksources-search-legend' => 'Gugn, woo mr biâchâr häärgrichd',
'booksources-go'            => 'Loos-suchng',

# Special:Log
'log' => 'Logbicher',

# Special:AllPages
'allpages'       => 'Ale sajdn',
'alphaindexline' => '$1 bis $2',
'prevpage'       => 'Fooriche sajdn ($1)',
'allpagesfrom'   => 'Sajdn ôôdsajchn ab:',
'allpagesto'     => 'Sajdn ôôdsajchn bis:',
'allarticles'    => 'Ale sajdn',
'allpagessubmit' => "Loos gäd's.",

# Special:LinkSearch
'linksearch' => 'Linggs nach ausârhalb',

# Special:Log/newusers
'newuserlogpage'          => 'Brodoghol iwâr dii naja bearbajdâr-ôômeldunga',
'newuserlog-create-entry' => 'Eds hasd a benudsâr-ghondoo',

# Special:ListGroupRights
'listgrouprights-members' => '(Lisdn fon dâ midgliidâr)',

# E-mail user
'emailuser' => 'Dem ôôgmeldn â iimejl schign',

# Watchlist
'watchlist'         => 'Maj beoobachdungs-lisdn',
'mywatchlist'       => 'Mâj beoobachdungslisdn',
'addedwatch'        => 'Wärd ab jeds beoobachd',
'addedwatchtext'    => "Di sajdn „[[:$1]]“ schdäd eds mid af dajnâr [[Special:Watchlist|beoobachdungs-lisdn]] .

Wen sich af der sajdn oda iirâr disghusjoons-sajdn was duud, wärd se ab eds
 af di „[[Special:RecentChanges|Ledsdn ändrunga]]“ fäd dâdsuugschriim.

Wenns'd dii sajdn irchendwan amôl nimä fârfolchn wilsd, musd bloos af „{{int:Unwatch}}“ glign.",
'removedwatch'      => 'Wärd eds nimä´ beoobachd',
'removedwatchtext'  => 'Dii sajdn „[[:$1]]“ is fo Dajnâr [[Special:Watchlist|beoobachdungslisdn]] nundârgnumma.',
'watch'             => 'Beoobachdn',
'watchthispage'     => 'Dii sajdn undâr beoobachdung nämâ',
'unwatch'           => 'Nimmä beoobachdn',
'watchlist-details' => 'Duu häldsch {{PLURAL:$1|1 sajdn|$1 sajdn}} undâr beoobachdung.',
'wlshowlast'        => 'Dsajch dii ändrunga fo di ledsdn $1 schdundn, $2 dooch odär $3',
'watchlist-options' => 'Was un wii alles af Dajnâr beobachdungslisdn dsajchd wärn sol',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Ghumd undâr beoobachdung ...',
'unwatching' => 'Beobachdn ajschränggn',

# Delete
'deletepage'             => 'Sajdn leschn',
'confirmdeletetext'      => "Duu bisd grôd dâbaj, â sajdn midsamd alle dsugheeriche alde wärsjoona ds'leschn. Bide beschdäädich, das De wasd, was des als bewirgd, un das De Dich dâbaj aa an d'[[{{MediaWiki:Policy-url}}|richliinjen]] fo dem wighi hiir häldsd.",
'actioncomplete'         => 'Erleedichd',
'deletedtext'            => '„<nowiki>$1</nowiki>“ is gleschd wôrn. Im $2 findsd â lisdn mid dâ ledsdn leschunga.',
'deletedarticle'         => 'had „[[$1]]“ gleschd',
'dellogpage'             => 'Logbuch fo di leschunga',
'deletecomment'          => 'Grund:',
'deleteotherreason'      => 'Noch a Grund dâfiir:',
'deletereasonotherlist'  => 'Andrâr Grund',
'deletereason-dropdown'  => "* Iibliche Grind fir's Leschn
** Wal's dr Audhoor woln had
** Wal's uurheewâr-rechd iwârdreedn wôrn is
** Wal anâr nôr ghausd had",
'delete-edit-reasonlist' => "D'grind fir's leschn ändârn",
'delete-toobig'          => "Dii sajdn had iiwâr $1 {{PLURAL:$1|Wersjoon|Wersjoon'n}}, des is fiil. Solche sajdn däf mr nima miir nigs diir nigs leschn, damid dii seewâr ned in d'gnii geen.",
'delete-warning-toobig'  => "Dii sajdn had mäa wii $1 {{PLURAL:$1|wärsjoon|wärsjoon'n}}, des is fiil. Wem ma solchene leschd, ghan dr seerwâr fiir {{SITENAME}} ins scholbârn ghomn.",

# Rollback
'rollback'       => "D'ändrungn riggängich machn",
'rollback_short' => 'riggängich machn',
'rollbacklink'   => 'Dsrigsedsn',
'rollbackfailed' => 'Riggängich machn is ned gangn.',

# Protect
'protectlogpage'              => 'Sajdnschbär-Logbuch',
'protectedarticle'            => 'had „[[$1]]“ gschbärd',
'modifiedarticleprotection'   => 'had gändârd, wii arch „[[$1]]“ gschbärd is',
'protectcomment'              => 'Grund:',
'protectexpiry'               => 'Schbär-dauâr:',
'protect_expiry_invalid'      => 'Dii ôôgeemne schbärdsajd is uugildich.',
'protect_expiry_old'          => 'Dii schbärdsajd is scho ausglofn.',
'protect-text'                => 'Dôô koosd dii fârschiida schbärn fon där sajdn „<nowiki>$1</nowiki>“ ôôschaua un ändârn.',
'protect-locked-access'       => "Duu hôsd nach Dajm Ôôgmäldnschdand (fgl. Daj ghondo)  ned des odâr diâ rechd, um an där schbäre fon derär sajdn was ds'ändârn. Däärdsajd sin dii gsedsdn schbärn fir dii sajdn '''„$1“:'''",
'protect-cascadeon'           => 'Dii sajdn is däärdsajd daal fon âm reghursiifâ schbärn. Des ged iwâr {{PLURAL:$1|folchende sajdn|dii folchendn sajdn}} wech, un wal dii reghursiif gschbärd {{PLURAL:$1|is|san}}, isâs aa dii sajdn hiir. Ob mr jeds hiir loghaal schbärd odr endschbärd, des endârd alâs nigs drôô, was an schbärung fon da heer iwârghomd.',
'protect-default'             => 'Ale benudsâr',
'protect-fallback'            => "Des brauchd  des „$1“-rächd, sunsch gäd's ned.",
'protect-level-autoconfirmed' => 'Naje un ned regischdriirde benudsâr schbärn',
'protect-level-sysop'         => 'Bloos adminischdraadoorn däfm',
'protect-summary-cascade'     => 'reghursiif gschbärd',
'protect-expiring'            => 'bis $2, $3 Uur (UTC)',
'protect-cascade'             => 'Reghursiife schbärâ - ale hiir af där sajdn ajbundne foorlôôchn soln aa gschbärd wärn.',
'protect-cantedit'            => 'Du ghansd ned ändan, was mär auf däa sajdn däf, wal duu hiir gôôr nigs ändârn däfsd.',
'restriction-type'            => 'Was erlaubd is:',
'restriction-level'           => 'Ausmôôs fom schbärn:',

# Undelete
'undeletelink'     => 'ôôgugn/dsrighooln',
'undeletedarticle' => 'had „[[$1]]“ widârhäärgschdeld',

# Namespace form on various pages
'namespace'      => 'Nôômâraum:',
'invert'         => 'Auswaal umdreâ',
'blanknamespace' => '(Sajdn)',

# Contributions
'contributions'       => 'Ajchne bajdrääch',
'contributions-title' => 'Bajdrääch fo „$1“',
'mycontris'           => 'Maj bajdreech',
'contribsub2'         => 'Fär $1 ($2)',
'uctop'               => '(ledsdâr schdand)',
'month'               => 'bis moonad:',
'year'                => 'bis dsum jôôr:',

'sp-contributions-newbies'  => 'Bloos bajdrääch fo naj Ôôgmeldâ dsajchn',
'sp-contributions-blocklog' => 'Schbär-brodoghol',
'sp-contributions-search'   => 'Bajdreech suchng',
'sp-contributions-username' => 'IP-adresn odär nôômâ fom Ôôgmeldn:',
'sp-contributions-submit'   => 'Suchng',

# What links here
'whatlinkshere'            => 'Linggs af däj Saidn',
'whatlinkshere-title'      => 'Sajdn, di af „$1“ fârwajsn',
'whatlinkshere-page'       => 'Sajdn:',
'linkshere'                => "Dii afgfiirdn sajdn fârwajsn af ''„[[:$1]]“''':",
'isredirect'               => 'Wajdârlajdungssajdn',
'istemplate'               => 'Foorlaachn-ajbindung',
'isimage'                  => 'fârwajs af des bild hiir',
'whatlinkshere-prev'       => '{{PLURAL:$1|vorhäärichâr|vorhääriche $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|nägschdâr|nägschde $1}}',
'whatlinkshere-links'      => '← fârwajse hiirhäär',
'whatlinkshere-hideredirs' => '$1 wajdârlajdungn',
'whatlinkshere-hidetrans'  => '$1 Foorlaachn-ajbindunga',
'whatlinkshere-hidelinks'  => '$1 Fârwajse',
'whatlinkshere-hideimages' => '$1 Bild-fârwajse',
'whatlinkshere-filters'    => 'Fildhâr',

# Block/unblock
'blockip'                  => 'IP-Adressn odr ôôgmeldn aus-schbärn',
'blockip-title'            => 'Bearbajdâr aus-schbärn',
'blockip-legend'           => 'IP-Adresn odr Bearbajdâr aus-schbärn',
'ipboptions'               => '2 schdund:2 hours,1 dooch:1 day,3 dooch:3 days,1 wochng:1 week,2 wochng:2 weeks,1 moonad:1 month,3 moonad:3 months,6 moonad:6 months,1 jôôr:1 year,oone dsajdschrangng:infinite',
'ipblocklist'              => 'Gschbärde IP-adresn un Ôôgmelde',
'blocklink'                => 'Schbärn',
'unblocklink'              => 'frajgeem',
'change-blocklink'         => 'Schbärn ändârn',
'contribslink'             => 'Bajdreech',
'blocklogpage'             => 'Benudsär-Schbärr-Logbuch',
'blocklogentry'            => 'had „[[$1]]“ gschbärd fir dii dsajd: $2 wii genau un wesweechn: $3',
'unblocklogentry'          => 'had dii schbärn fo „$1“ afghoom',
'block-log-flags-nocreate' => 'Naj ôôdsmeldn is gschbärd.',

# Move page
'movepagetext'     => "Mid dem fôrmulaar hiir wärd â sajdn umdaafd, midsamd alle foorichâ wärsjoona. Dii sajdn mi'm aldn nôômâ blajbd, fârwajsd danôôch awâr bloos noch af dii naje.
Wajdârlajdunga af den aldn sajdn-nôômâ ghansd audomaadisch ghôrigiirn lasn. Wenns´d des ned magsd,  gug ob's ned dsu [[Special:DoubleRedirects|dobldâ]] odär [[Special:BrokenRedirects|ghabude wajdârlajdunga]] fiird. Du musd dâfiir sorgn, das dii aldn fârwajse aa ghinfdich wajdâr fungdsjoniirn!

Wen's scho â sajdn mid dem naja nôômâ gibd, wärd dii sajdn ned umdaafd wärn, es saj den, dii sajdn mi'm naja nôômâ wäär läär odär bloos â wajdârladung oone äldâre wärsjoon.

Desdaweechn ghansd â beschdeende sajdn nii aus fârseen iwârschrajwn, und wen'D doch an feelâr gmachd hasd mi'm umdaafn, ghansd des dan aa oone wajdârs widâr righgengich machn.

'''Achdung'''
Wen'D des mid'râr  beliibdn sajdn duusd, ghan soo a umdaaf-agdsion â hefdiche sach saj, neemlich mid folchng, di fär andâre rächd iwârraschnd sin. Magg's also bloos, wenn´sd genau waasd, was´d ôôrichsd.",
'movepagetalktext' => "Dii droôhängnde  disghusjoonssajdn wärd mid umdaafd, '''ausâr wen:'''
*es scho undârm naja nôômâ â disghusjoonssajdn geem dääd
*duu sechsd undn, das'd es hald ned wilsd.
Dan musd hald was drinschded fon hand riiwârschafn odr dsamschdudârn, wen de des dôô hôôm wilsd.
Schrajb bide den '''naja'' nôômâ fo dâr sajdn undârals '''Dsiil'' nâj un '''nen dâ grund''' fir des umgedaaf drundâr.",
'movearticle'      => 'Sajdn fârschiibm:',
'newtitle'         => 'Dôôhi:',
'move-watch'       => 'Alde un naje sajdn beoobachdn.',
'movepagebtn'      => 'Sajdn fârschiibm',
'pagemovedsub'     => 'Eds is fârschoom.',
'movepage-moved'   => "'''Dii sajdn „$1“ is edsad nach  „$2“ verschoom wôrn.'''",
'articleexists'    => "Es umdaafn gäd ned, wal's dii sajdn scho gibd, soo wise naj häd haasn soln. Dengg dä´ hald an andârn nôômâ aus.",
'talkexists'       => 'Dii sajdn is fârschoom wôrn, awa baj iira disghusjoonssajdn is ned gangâ, wals dii scho mim naja nôôma gibd. Jeds musd des fo hand dsamwôrschdln.',
'movedto'          => 'fârschoom nach',
'movetalk'         => "Dii disghusjoons-sajdn aa mid fârschiim, wen's gäd",
'1movedto2'        => 'had „[[$1]]“ nach „[[$2]]“ umdaafd',
'1movedto2_redir'  => 'had „[[$1]]“ nach „[[$2]]“ fârschoom un dâbaj â wajdârlajdung ibârschriim',
'movelogpage'      => 'Umdaaf-Logbuch',
'movereason'       => 'Grund:',
'revertmove'       => 'dsrigdaafn af an aldn nôômâ',

# Export
'export' => 'Sajdn ägsbhôrdiirn',

# Thumbnails
'thumbnail-more' => 'Grässär machng',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Daj benudsâr-sajdn',
'tooltip-pt-mytalk'               => 'Daj disghusjoons-sajdn',
'tooltip-pt-preferences'          => 'Daj Âjschdelunga',
'tooltip-pt-watchlist'            => 'Dôô ghummsd dsu dâjnâr beoobachdungslisdn',
'tooltip-pt-mycontris'            => 'Dôô ghumsd dsur lisdn fo dajne bajdreech',
'tooltip-pt-login'                => "S'is gän gseen, das mä´ si ôômäld, mâ däf awâr aa soo raj",
'tooltip-pt-logout'               => 'Abmeldn',
'tooltip-ca-talk'                 => 'Disghusjoon dsur sajdn hiir',
'tooltip-ca-edit'                 => "Dii sajdn ghansd beärbâdn.
Bidde gug's mi´m foorschau-gnobf ôô fôrm schbajchan",
'tooltip-ca-addsection'           => 'An naja abschnid ôôfanga',
'tooltip-ca-viewsource'           => 'Dii sajdn is gecha ändrunga gschbärd. Mär ghôô awâr den gwäl-dhägsd ôôschaua.',
'tooltip-ca-history'              => 'Friâre Fassunga fo där sajdn',
'tooltip-ca-protect'              => 'Dii sajdn schidsn',
'tooltip-ca-delete'               => 'Dii sajdn leschn',
'tooltip-ca-move'                 => 'Damid daafsd dii sajdn um',
'tooltip-ca-watch'                => 'Dôômid nimsd dii sajdn undâr daj beoobachdung',
'tooltip-ca-unwatch'              => 'Mir nime beschajd geewn, wen sich uf där sajdn was duud',
'tooltip-search'                  => 'In {{SITENAME}}suchng',
'tooltip-search-go'               => 'Dii sajdn suchng, dii genau soo haasd',
'tooltip-search-fulltext'         => 'Suuch nach sajdn mid dem dhägsd',
'tooltip-p-logo'                  => "Uf d'haubdsajdn geen",
'tooltip-n-mainpage'              => 'Af di haubdsajdn geä',
'tooltip-n-mainpage-description'  => "Af d'haubdsajdn gea",
'tooltip-n-portal'                => 'Beschrajwung fom brojägd, was de doâ ghausch, wo de waas findsch.',
'tooltip-n-currentevents'         => 'Sich beschajd holn iwâr sachn, dii grôôd basiirn',
'tooltip-n-recentchanges'         => "Was ds'ledsch af {{SITENAME}} anärsch gmachd wôrn is",
'tooltip-n-randompage'            => 'Dsufellicha sajdn',
'tooltip-n-help'                  => 'Hilfssajdn oozaichng',
'tooltip-t-whatlinkshere'         => 'Welche sajdn alle dôô häär zaing',
'tooltip-t-recentchangeslinked'   => "Was ds'ledschd gändârd wôrn is af sajdn, af dii fo hiir fârwiisn wärd",
'tooltip-feed-rss'                => 'RSS-Feed fä´ dii sajdn',
'tooltip-feed-atom'               => 'Atom-Feed fä` dii sajdn',
'tooltip-t-contributions'         => 'Dsajchn, was däär benudsâr alâs gmachd had',
'tooltip-t-emailuser'             => 'Dem ôôgneldn â E-mejl schign',
'tooltip-t-upload'                => 'Dadaia nauflôôdn',
'tooltip-t-specialpages'          => 'Lisdn fo alle Schbedsjalsajdn',
'tooltip-t-print'                 => 'Dii sajdn in drugôôsichd ôôdsajchn',
'tooltip-t-permalink'             => 'Bermanendär lingh zo derä Sajdnwärsjoon',
'tooltip-ca-nstab-main'           => 'Sajdninhald dsajchn',
'tooltip-ca-nstab-user'           => 'Dii Benudsârsajdn ôôdsajchn',
'tooltip-ca-nstab-media'          => 'Dii sajdn fir dii meedjendadaj ôôdsajchn',
'tooltip-ca-nstab-special'        => 'Des is â sonda-sajdn, dii ghôôsch ned ändârn',
'tooltip-ca-nstab-project'        => 'Aaf di bhôrdaalsajdn geä´',
'tooltip-ca-nstab-image'          => 'Di sajdn fo där dadaj oozaing',
'tooltip-ca-nstab-mediawiki'      => 'Dii sischdeem-mäldung ôôdsajchn',
'tooltip-ca-nstab-template'       => 'Dii foorlaachn ôôdsajchn',
'tooltip-ca-nstab-help'           => 'Dii hilfssajdn ôôdsajchn',
'tooltip-ca-nstab-category'       => 'Dii ghadegoriin-sajdn ôôdsajchn',
'tooltip-minoredit'               => 'Dii ändrung als glaa auswajsn',
'tooltip-save'                    => 'Was de gmachd hasch, jeds alâs schbajchârn',
'tooltip-preview'                 => 'Forhäär ôôdsajng, was`d alles af derä Sajdn gändert hôsd. Bidde mach des, befoorsd` endgildich schbajchârsd.',
'tooltip-diff'                    => 'Ôôdsajng, was´d hiir umbasdld hôsd.',
'tooltip-compareselectedversions' => 'Enn undârschiid dswischä dswaa rausgsuchdä wärsjoona fo dera sajdn ôôzajng.',
'tooltip-watch'                   => 'Dii sajdn undâr beobachdung nemâ',
'tooltip-recreate'                => 'Dii sajdn naj ôôleechn, obwool se scho môôl gleschd wôrn is.',
'tooltip-upload'                  => 'Loos midm nauflaadn',
'tooltip-rollback'                => 'Hiir glign machd mid am môl alâs riggängich, was däär benudsâr dsledschd af där sajdn gmachd had.',
'tooltip-undo'                    => 'Hiir glign machd dii aane ändärung riggängich un dsajchd dan ôô, wiis dan ausschaua dääd. Dann koosd aa no â dsamfassung wisoo un warum dâdsuuschrajm.',

# Stylesheets
'common.css'      => '/* CSS hiir beâjflusd ale schelfn */',
'standard.css'    => "/* CSS hiir beâjflusd nôr dii Klassik-schelfn. Wen'd ale uf ôômôôl beâjflusn wilsch, muâsch an [[MediaWiki:Common.css]] was ändârn. */",
'nostalgia.css'   => "/* CSS hiir beâjflusd nôr dii Nostalgia-schelfn. Wen'd ale uf ôômôôl beâjflusn wilsch, muâsch an MediaWiki:Common.css was ändârn. */",
'cologneblue.css' => "/* CSS hiir beâjflusd nôr dii Kölnisch-Blau-schelfn. Wen'd ale uf ôômôôl beâjflusn wilsch, muâsch an MediaWiki:Common.css was ändârn. */",
'monobook.css'    => "/* CSS hiir beâjflusd nôr dii Monobook-schelfn. Wen'd ale uf ôômôôl beâjflusn wilsch, muâsch an MediaWiki:Common.css was ändârn. */",
'myskin.css'      => "/* CSS hiir beâjflusd nôr dii MySkin-schelfn. Wen'd ale uf ôômôôl beâjflusn wilsch, muâsch an MediaWiki:Common.css was ändârn. */",
'chick.css'       => "/* CSS hiir beâjflusd nôr dii Küken-schelfn. Wen'd ale uf ôômôôl beâjflusn wilsch, muâsch an MediaWiki:Common.css was ändârn. */",
'simple.css'      => "/* CSS hiir beâjflusd nôr dii Simple-schelfn. Wen'd ale uf ôômôôl beâjflusn wilsch, muâsch an MediaWiki:Common.css was ändârn. */",
'modern.css'      => "/* CSS hiir beâjflusd nôr dii Modern-schelfn. Wen'd ale uf ôômôôl beâjflusn wilsch, muâsch an MediaWiki:Common.css was ändârn. */

/* Dii glôôschrajwung im nawigadsjoonsberajch fârhindârd des: */
.portlet h5,
.portlet h6,
#p-personal ul,
#p-cactions li a,
#preftoc a {
     text-transform: none;
}",
'vector.css'      => "/* CSS hiir beâjflusd nôr dii Vector-schelfn. Wen'd ale uf ôômôôl beâjflusn wilsch, muâsch an MediaWiki:Common.css was ändârn. */",
'print.css'       => '/* CSS hiir beâjflusd nôr dii drugausgaawe. */',
'handheld.css'    => '/* CSS hiir beâjflusd nôr dii handgerääde, jee nachdeem, welche schelfn in $wgHandheldStyle âjgeschdeld is. */',

# Scripts
'common.js' => '/* Des folchende JavaScript wird fir ale benudsâr glôôdn un fir ale sajdn, dii se ôôgugn. */',

# Browsing diffs
'previousdiff' => '← Dsur foorichn fârändârung',
'nextdiff'     => 'Undârschiid fo där nägsdn ändrung →',

# Media information
'file-info-size'       => '($1 × $2 pigsl, dadajgrääsn: $3, MIME-tib: $4)',
'file-nohires'         => "<small>A he´äre aafleesung gibd's ghaane.</small>",
'svg-long-desc'        => '(SVG-dadaj, ufleesung: $1 × $2 pigsl, dadajgreesn: $3)',
'show-big-image'       => 'Bild in hegsdâr aufleesung',
'show-big-image-thumb' => '<small>Greäs fo där schbigg-ôôsichd: $1 × $2 bhigsl</small>',

# Bad image list
'bad_image_list' => 'Fôrmaad:

Bloos  dsajln, dii mi´m dsajchn * ôôfanga, wärn berigsichdichd. Un dä ärschde linggh af dr dsajln mus dan af â uubasnde dadaj saj. Wen dahindâr noch meâ linggs ghuma, dan geldn dii als ausnôma, wo dâgeechn des - dsum bajschbiil  - bild schdeen däf.',

# Metadata
'metadata'          => 'Meedhaa-daadn',
'metadata-help'     => 'Dii dadaj umfasd annäre ôôgam, dii normaalârwajs fo där digidaal-ghamâraa odär fo am sghänâr häärghumma. Wen dii dadaj indswischn fârändârd wôrn is, meechn dii nimä dsum bild basn.',
'metadata-expand'   => 'Ajdslhajdn dsajchn',
'metadata-collapse' => 'Ajdslhajdn ausblendn',
'metadata-fields'   => 'Hiir afgfiirde fäldâr fo dâ EXIF-medha-daadn wärn af alle bildbeschrajwungs-sajdn afgfiird, aa wen dii medhadaadn-dabelln ajgfalded is. Annäre sin ärschdâmôôl fârschdegd.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'  => 'brajdn',
'exif-imagelength' => 'Heen',

# External editor support
'edit-externally'      => 'Dii dadaj mid an ägsdärna brogram ändârn',
'edit-externally-help' => '(Määr un genauârs dâdsuu baj den [http://www.mediawiki.org/wiki/Manual:External_editors Inschdaladsjoonsanwajsungn])',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'ale',
'namespacesall' => 'ale',
'monthsall'     => 'alle',

# Watchlist editing tools
'watchlisttools-view' => 'Ändrunga in där beoobachdungslisdn',
'watchlisttools-edit' => 'Beobachdungslisdn dsajchn un ändârn',
'watchlisttools-raw'  => "In där beoobachdungslisdn ds'fuâs rumworschdln",

# Special:SpecialPages
'specialpages' => 'Schbedsjaal-sajdn',

);

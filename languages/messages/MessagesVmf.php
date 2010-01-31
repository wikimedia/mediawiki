<?php
/** Mainfränkisch (Mainfränkisch)
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
	'DoubleRedirects'           => array( 'Dobâldâ Wajdârlajdungân' ),
	'Userlogin'                 => array( 'Ôômäldâ' ),
	'Userlogout'                => array( 'Ôbmäldâ' ),
	'Preferences'               => array( 'Ôischtälungâ' ),
	'Watchlist'                 => array( 'Bäoobôchdungslisdâ' ),
	'Recentchanges'             => array( 'Lädsdâ Änârungâ' ),
	'Upload'                    => array( 'Hoochlaadâ' ),
	'Statistics'                => array( 'Schdadisdign' ),
	'Newpages'                  => array( 'Nojâ Sajdâ' ),
	'Allpages'                  => array( 'Ôlâ Sajdâ' ),
	'Prefixindex'               => array( 'Indägs' ),
	'Specialpages'              => array( 'Schbädsjaalsajdâ' ),
	'Contributions'             => array( 'Bajdräächâ' ),
	'Emailuser'                 => array( 'Iimäjlâ' ),
	'Confirmemail'              => array( 'Iimäjl bschdädigâ' ),
	'Movepage'                  => array( 'Sajdâ färschiibâ' ),
	'Categories'                => array( 'Gadâgoriin' ),
	'Export'                    => array( 'Ägsbordiirn' ),
	'Allmessages'               => array( 'Ôlâ Nôôchrichdâ' ),
	'Undelete'                  => array( 'Wiidârhärschdälâ' ),
	'Import'                    => array( 'Imbordiirn' ),
	'Unwatchedpages'            => array( 'Unbäoobôchdâdâ Sajdn' ),
);

$messages = array(
# User preference toggles
'tog-underline'              => 'Linggs undârschdrajchn:',
'tog-highlightbroken'        => 'Linggs auf sajdn, diis ned gajd, soo ôôdsajchn: <a href="" class="new">bajschbiil</a> (sunschd: soo<a href="" class="internal">?</a>)',
'tog-justify'                => 'Dhägsd in Blogsads',
'tog-hideminor'              => 'Glaane ändrungn ned ôôdsajchn',
'tog-hidepatrolled'          => 'Ned dsajchn in dâ „Ledschdâ Ändrungn“, was an andrar schon brüüfd had',
'tog-showtoc'                => 'Inhalds-fârdsajchnis ôôdsajchn baj määr wi 3 iiwârschrifdn',
'tog-rememberpassword'       => 'Uf dem Ghombjuudâr schdändich ôôgmäld blajwn',
'tog-editwidth'              => "S'âjgaawefäld sol soo braad wi dr bildschirm wärn",
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
'september'     => 'Säbdembr',
'october'       => 'Ogdoowr',
'november'      => 'Nowembr',
'december'      => 'Dädsembr',
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
'listingcontinuesabbrev' => '(Fôrdsedsung)',

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
'vector-action-addsection'   => 'Ân najn abschnid ôôfangn',
'vector-action-delete'       => 'Leschn',
'vector-action-move'         => 'Umdaafn',
'vector-action-protect'      => 'Schidsn',
'vector-action-undelete'     => 'Leschn riggängich machn',
'vector-action-unprotect'    => 'Schuds ufgeebn',
'vector-namespace-category'  => 'Ghadegorii',
'vector-namespace-help'      => 'Hilfe-sajdn',
'vector-namespace-image'     => 'Dadhaj',
'vector-namespace-main'      => 'Sajdn',
'vector-namespace-media'     => 'Meedjân-sajdn',
'vector-namespace-mediawiki' => 'Mäldung fon MediaWiki',
'vector-namespace-project'   => 'Brojägd-sajdn',
'vector-namespace-special'   => 'Sondâr-sajdn',
'vector-namespace-talk'      => 'Disghusjoon',
'vector-namespace-template'  => 'Foorlaach',
'vector-namespace-user'      => 'Benudsâr-sajdn',
'vector-view-create'         => 'Ôôleechn',
'vector-view-edit'           => 'Bearbajdn',
'vector-view-history'        => 'Wärsjoonsfolche',
'vector-view-view'           => 'Leesn',
'vector-view-viewsource'     => 'Gwäl-dhägsd ôôgugn',
'namespaces'                 => 'Nôômsrajm',
'variants'                   => 'Warjandn',

# Metadata in edit box
'metadata_help' => 'Medha-daadn:',

'errorpagetitle'    => 'Feelr',
'returnto'          => 'Dsrig dsur sajdn $1.',
'tagline'           => 'Aus {{SITENAME}}',
'search'            => 'Suuche',
'searchbutton'      => 'Suchng',
'searcharticle'     => 'Uf di sajdn',
'history'           => 'Wärsjoonsfolche',
'history_short'     => 'Wärsjoonsfolche',
'updatedmarker'     => "is gändârd wôrn sajde ds'ledschd dôô wôôr",
'info_short'        => 'Infôrmadsjoon',
'printableversion'  => 'Drug-wärsjoon',
'permalink'         => 'Beschdendichä lingg',
'print'             => 'Ausdrugâ',
'edit'              => 'Bearbajdn',
'create'            => 'Erdsojchn',
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
'redirectedfrom'    => '(Wajdagschigd fon $1)',
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
'aboutpage'            => 'Project:About',
'copyright'            => 'Was hiir schdäd däfmâr benudsn nach $1',
'currentevents'        => 'Was grôôd basiird is',
'disclaimers'          => 'Imbräsum',
'disclaimerpage'       => 'Project:Imbräsum',
'edithelp'             => 'Hilfe dsum bearbajdn',
'edithelppage'         => 'Help:Bearbajdn',
'helppage'             => 'Help:Inhalds-fârdsajchnis',
'mainpage'             => 'Haubdsajdn',
'mainpage-description' => 'Haubdsajdn',
'policy-url'           => 'Project:Reechln',
'portal'               => 'Ajgang fir miidmachâr',
'portal-url'           => 'Project:Ajgang fir miidmachâr',
'privacy'              => 'Daadnschuds',
'privacypage'          => 'Project:Daadn-schuds',

'badaccess'        => 'Des däfsch duu ned',
'badaccess-group0' => 'So ebâs däfsch duu ned machn',
'badaccess-groups' => 'Des däfm bloos lajd machng, dii wo baj {{PLURAL:$2|där grubbm|anâr fon dâ grubm}} „$1“ sin.',

'versionrequired' => "S'brauchd dii wärsjoon $1 fon MediaWiki.",

'ok'                      => 'In ôrdnung',
'retrievedfrom'           => 'Fon „$1“ ghold',
'youhavenewmessages'      => "S'gajd $1 uf dahnâr disghusjoons-sajdn ($2).",
'newmessageslink'         => 'naje middajlungn',
'newmessagesdifflink'     => 'lädschde fârendârung',
'youhavenewmessagesmulti' => "S'gajd naje middajlungn: $1",
'editsection'             => 'Bearbajdn',
'editold'                 => 'Bearbajdn',
'viewsourceold'           => 'Wighidhägsd dsajchn',
'editlink'                => 'bearbajdn',
'viewsourcelink'          => 'Wighidhägsd dsjachn',
'editsectionhint'         => 'Abschnid bearbajdn: $1',
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
'nstab-user'      => 'Benudsr-sajdn',
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
'error'           => 'Feelâr',
'databaseerror'   => 'Feelâr fon dr Daadnbangg',
'dberrortext'     => 'Bam abfrôôchn fon dr daadnbangg is was schiif gangn.
Filajchd weechn am brogramiir-feelâr?
Jeednfals wôôr di ledsd abfrôôchn:
<blockquote><tt>$1</tt></blockquote>
aus dr fungdsjoon „<tt>$2</tt>“.
Un dôôdruf had dan di daadnbangg den feelâr „<tt>$3: $4</tt>“ gmeld.',
'dberrortextcl'   => 'Dii daadnbangg-abfrôôchn wôôr falsch gschriiwn.
Di abfrôôchn wôôr neemlich
<blockquote><tt>$1</tt></blockquote>
aus dr fungdsjoon "<tt>$2</tt>". Un dôôdruf had dan di daadnbangg den feelâr „$3: $4“ gmeld.',
'laggedslavemode' => "'''Achdung:''' Filajchd dsajchd dii sajdn noch ned ales, was indswischn gändârd wôrn is.",
'readonly'        => 'Di daadnbangg is gschbärd.',
'enterlockreason' => 'Bide schrajb, wisoo dii daadnbangg gschbärd wärn sol, un wi lang des dan dauârn mechd.',
'readonlytext'    => "Di daadnbangg is grôôd gschbärd, alsâ ghamâr nids naj râjschrajwn odr ändrn. Brobiir's hald schbäädr noch âmôôl.

Gschbärd is se desdâweechn: $1",
'missing-article' => "Di daadnbangg had dii sajdn „$1“ $2 ned gfundn.

Wen des basiird, dan majschdns, wemma â dsu alde bearbajdung ôôgugn wil oda ane fonra gleschdn sajdn.  

Wen's des ned is, bisd womeeglich iwa ân feela in dr sofdwäâr gschdolbad. Dan meld des, bide mid da URL, am [[Special:ListUsers/sysop|Administrator]].",
'viewsource'      => 'Gwäl-dhägsd ôôgugn',

# Login and logout pages
'yourname'                => 'Benudsârnôômn',
'yourpassword'            => 'Bhaswôrd:',
'nav-login-createaccount' => 'Oomeldn / Ghondoo ooleeng',
'logout'                  => 'Abmeldn',
'userlogout'              => 'Abmeldn',

# Edit page toolbar
'bold_sample' => 'Dägsd in fäd',
'bold_tip'    => 'Fädâr dhägschd',
'italic_tip'  => 'Ghursiif-dhägsd',
'math_tip'    => 'Mademaadische Fôrml (in LATEX)',

# Edit pages
'watchthis'              => 'Dii sajdn undâr beoobachdung nämma',
'savearticle'            => 'Sajdn schbajchän',
'summary-preview'        => "Was in'd dsusammfasungsdsajln najghumd:",
'noarticletext'          => 'Dii sajdn gibd\'s bis jeds noch ned.
Duu ghâusch nach dem ausdrug aa [[Special:Search/{{PAGENAME}}|in aln sajdn drin suuchn]], 
<span class="plainlinks"> [{{fullurl:{{#special:Log}}|page={{FULLPAGENAMEE}}}} in deen dsugheerichn log-biichârn suuchn] odâr dii sajdn [{{fullurl:{{FULLPAGENAME}}|action=edit}} ôôleechn un najschrajwn]</span>.',
'template-semiprotected' => '(ned ôôgmeldede un naje benudsr däfn hiir ned schrajm)',

# History pages
'revisionasof'     => 'Wärsjoon fom $2 um $3 Uur',
'previousrevision' => '← wärsjoon dâfoor',
'cur'              => 'undârschiid dsur jedsichn fasung',
'last'             => 'Foorhäärich',
'histfirst'        => 'Äldâschde',
'histlast'         => 'Najschde',

# Revision deletion
'rev-delundel'   => 'ôôdsajchn/fârbärchn',
'revdel-restore' => 'Ändârn, was oodsajchd wäd',

# Merge log
'revertmerge' => 'Dsrig fôr dii fârajnichung',

# Diffs
'lineno'   => 'Dsajln $1:',
'editundo' => 'riggängich machng',

# Search results
'searchresults'             => 'Bam suchng gfundne sachng',
'searchresults-title'       => 'Gfundn bam suchng nach „$1“',
'searchresulttext'          => 'Wende wisn wilsd, wii genau mr ales af {{SITENAME}} suuchn ghôô, dan gug uf dâr [[{{MediaWiki:Helppage}}|filfssajdn]] nôôch.',
'searchsubtitle'            => 'Gsuuchd wän sod nach: „[[:$1|$1]]“ ([[Special:Prefixindex/$1|aln sajdn, dii wo mid „$1“ ôôfangn]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|aln sajdn, dii wo af „$1“ fârwajsn]])',
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
'search-interwiki-caption'  => 'Schweschdr-brojägd',
'search-interwiki-default'  => 'Af $1 gfundn:',
'search-interwiki-more'     => '(noch mäa)',
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
'nonefound'                 => "'''Ufbasd:''' Oone wajdas wäd nôr in ajnichn Naamsrajm gsuuchd. Wen'd iwarôôl (aa in disghusjoon'n, foorlaachn usw.) suchn wilsd, musd ''all:'' foorschrajm, oda aa den naam (midâm dobl-bhungd) fon genau dem naamsraum, fon dem de waasd, dases nur in deem drin saj ghan.",
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
'preferences'               => 'ajschdelungn',
'mypreferences'             => 'Maj ajschdelungn',
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
'group-sysop' => 'Adminischdradoorn',

# Recent changes
'rclistfrom'       => "Nur d'ändrungn dsajchn sajd $1",
'diff'             => 'undârschiid',
'hist'             => 'Wärsjoonsfolche',
'hide'             => 'fârschdegn',
'show'             => 'dsajchn',
'minoreditletter'  => 'g',
'rc-enhanced-hide' => 'Glôônichghajdn ned dsajchn',

# Recent changes linked
'recentchangeslinked'         => 'Ändärunga af sajdn, af dii fo hiir fârwiisn wäd',
'recentchangeslinked-summary' => "Dii sôndârsajdn fiird ale kirdsliche ändrungn fon sajdn uf, dii wo an däär hiir drôôhängn. Ales, was de dâfoo in daj [[Special:Watchlist|beoobachdunglisdn]] ufgnumn hasd, wäd aa noch '''fäd''' ôôdsajchd.",

# Upload
'upload' => 'Nauflôôdn',

# File description page
'filehist'           => "Wärsjoon'n bis jeds",
'filehist-help'      => 'Glig uf ân dsajdbhungd, um dii dôômôôlich fasung ôôdsgugn',
'filehist-datetime'  => 'Âjschdlungs-daadum un -dsajd',
'filehist-thumbtext' => "Wii d'fasung fom $2, $3 Uur grâub aussiid",
'filehist-comment'   => 'Sembf dâdsuâ',
'imagelinks'         => 'Dsajchn, wo dii dadaj als benudsd wärd',

# Miscellaneous special pages
'nbytes'        => '$1 {{PLURAL:$1|Bajd|Bajds}}',
'ncategories'   => '$1 {{PLURAL:$1|GhadegoriiGhadegoriin}}',
'newpages'      => 'Naje sajdn',
'move'          => 'Umdaafn',
'pager-newer-n' => '{{PLURAL:$1|nägschdâr|nägschd $1}}',
'pager-older-n' => '{{PLURAL:$1|foorichâr|fooriche $1}}',

# Book sources
'booksources'    => 'ISBN-Suuche',
'booksources-go' => 'Loos-suuchn',

# Special:AllPages
'allpages'       => 'Ale sajdn',
'allarticles'    => 'Ale sajdn',
'allpagessubmit' => "Loos gäd's.",

# Special:Log/newusers
'newuserlog-create-entry' => 'Jeds hasd an benudsâr-ghondoo',

# Watchlist
'watchlist'      => 'Maj beoobachdungs-lisdn',
'mywatchlist'    => 'Mâj beoobachdungslisdn',
'addedwatch'     => 'Wärd ab jeds beoobachd',
'addedwatchtext' => "Di sajdn „[[:$1]]“ schdeed jeds mid af dajnâr [[Special:Watchlist|beoobachdungs-lisdn]] .

Wen sich af dr sajdn oda iirâr disghusjoons-sajdn was duud, wärd se jeds
 af den „[[Special:RecentChanges|Ledsdn ändrungn]]“ fäd dâdsuugschriim.

Wen'd dii sajdn irchendwan nima fârfolchn wilsd, musd nôr af „{{int:Unwatch}}“ glign.",
'removedwatch'   => 'Wärd jeds nima beoobachd',
'watch'          => 'Beoobachdn',
'unwatch'        => 'Nimmä beoobachdn',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Ghumd undâr beoobachdung ...',
'unwatching' => 'Beobachdn ajschränggn',

# Delete
'actioncomplete'         => 'Erleedichd',
'deletedarticle'         => 'had „[[$1]]“ gleschd',
'deletecomment'          => 'Warum gleschd:',
'deleteotherreason'      => 'Noch ân Grund dâfiir:',
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
'protect-default'             => 'Ale benudsâr',
'protect-fallback'            => "Des brauchd  des „$1“-rächd, sunsch gäd's ned.",
'protect-level-autoconfirmed' => 'Naje un ned regischdriirde benudsâr schbärn',
'protect-level-sysop'         => 'Nôr adminischdraadâr däfn',
'protect-expiring'            => 'bis $2, $3 Uur (UTC)',
'protect-cantedit'            => 'Du ghansd ned ändan, was mr auf däa sajdn däf, wal duu hiir gôôr nigs ändan däfsd.',
'restriction-type'            => 'Was erlaubd is:',

# Undelete
'undeletelink' => 'ôôgugn/dsrighooln',

# Namespace form on various pages
'namespace'      => 'Nôômâsraum:',
'blanknamespace' => '(Sajdn)',

# Contributions
'mycontris' => 'Maj bajdreech',

# What links here
'whatlinkshere'      => 'Linggs af däj Saidn',
'whatlinkshere-page' => 'Sajdn:',

# Block/unblock
'blocklink'        => 'Schbärn',
'unblocklink'      => 'frajgeem',
'change-blocklink' => 'Schbärn ändârn',
'contribslink'     => 'Bajdreech',

# Move page
'movearticle'    => 'Sajdn fârschiibm:',
'newtitle'       => 'Dôôhin:',
'move-watch'     => 'Alde un naje sajdn beoobachdn.',
'movepagebtn'    => 'Sajdn fârschiibm',
'pagemovedsub'   => 'Jeds is fârschoom.',
'movepage-moved' => "<big>'''Dii sajdn „$1“ is jedsdsu  „$2“ wôrn.'''</big>",
'articleexists'  => "S'fârschiibm ged ned, wal's dii sajdn scho gibd, wo's had hin soln. Dengg dir hald an andârn naamn aus.",
'talkexists'     => 'Dii sajdn is fârschoom wôrn, awa baj iira disghusjoonssajdn is ned gangn, wals dii scho mim najn naam gibd. Jeds musd des fon hand dsamwôrschdln.',
'movedto'        => 'fârschoobn nach',
'revertmove'     => 'dsrigdaafn af an aldn nôômâ',

# Thumbnails
'thumbnail-more' => 'Grässär machng',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Daj benudsâr-sajdn',
'tooltip-pt-mytalk'               => 'Daj disghusjoons-sajdn',
'tooltip-pt-preferences'          => 'Daj Âjschdelunga',
'tooltip-pt-watchlist'            => 'Dôô ghumsch dsu dâjrâr beoobachdungslisdn',
'tooltip-pt-mycontris'            => 'Dôô ghumsd dsur lisdn fo dajne bajdreech',
'tooltip-pt-login'                => "S'is gän gsään, dasmr sich ôômäld, ma däf awâr aa soo raj",
'tooltip-pt-logout'               => 'Abmeldn',
'tooltip-ca-talk'                 => 'Disghusjoon dsur sajdn hiir',
'tooltip-ca-edit'                 => "Dii sajdn ghanst bearbajdn.
Bide gug's midm foorschau-gnobf ôô fôrm schbajchan",
'tooltip-ca-history'              => 'Friâre Fasungn fon där sajdn',
'tooltip-ca-protect'              => 'Dii sajdn schidsn',
'tooltip-ca-move'                 => 'Damid daafsd dii sajdn um',
'tooltip-ca-watch'                => 'Dôômid nimsd dii sajdn undâr daj beoobachdung',
'tooltip-search'                  => 'In {{SITENAME}}suchng',
'tooltip-search-go'               => 'Dii sajdn suchng, dii genau soo haasd',
'tooltip-search-fulltext'         => 'Suuch nach sajdn mid dem dhägsd',
'tooltip-p-logo'                  => "Uf d'haubdsajdn geen",
'tooltip-n-mainpage'              => 'Af di haubdsajdn geen',
'tooltip-n-mainpage-description'  => "Af d'haubdsajdn gea",
'tooltip-n-portal'                => 'Beschrajwung fom brojägd, was de doâ ghausch, wo de waas findsch.',
'tooltip-n-currentevents'         => 'Sich beschajd holn iwâr sachn, dii grôôd basiirn',
'tooltip-n-recentchanges'         => "Was ds'ledsch uf {{SITENAME}} andârs gmachd wôrn is",
'tooltip-n-randompage'            => 'Dsufeliche sajdn',
'tooltip-n-help'                  => 'Hilfssajdn oozaichng',
'tooltip-t-whatlinkshere'         => 'Welche sajdn ale hiir häär wajsn',
'tooltip-t-recentchangeslinked'   => "Was ds'ledschd gändârd wôrn is uf sajdn, uf dii dii hiir fârwajsd",
'tooltip-feed-rss'                => 'RSS-Feed fir dii sajdn',
'tooltip-feed-atom'               => 'Atom-Feed fir dii sajdn',
'tooltip-t-contributions'         => 'Dsajchn, was däär benudsâr alâs gmachd had',
'tooltip-t-emailuser'             => 'Dem benudsâr â E-mejl schign',
'tooltip-t-upload'                => 'Dadain nauflôôdn',
'tooltip-t-specialpages'          => 'Lisdn fo alle Schbedsjalsajdn',
'tooltip-t-print'                 => 'Dii sajdn in drugôôsichd ôôdsajchn',
'tooltip-t-permalink'             => 'Bermanendär lingh zo derä Sajdnwärsjoon',
'tooltip-ca-nstab-main'           => 'Sajdninhald dsajchn',
'tooltip-ca-nstab-user'           => 'Dii Benudsârsajdn ôôdsajchn',
'tooltip-ca-nstab-media'          => 'Dii sajdn fir dii meedjendadaj ôôdsajchn',
'tooltip-ca-nstab-special'        => 'Des is â sonda-sajdn, dii ghôôsch ned ändârn',
'tooltip-ca-nstab-project'        => "Uf d'bhôrdaalsajdn geen",
'tooltip-ca-nstab-image'          => 'Di sajdn fo där dadaj oozaing',
'tooltip-ca-nstab-mediawiki'      => 'Dii sischdeem-mäldung ôôdsajchn',
'tooltip-ca-nstab-template'       => 'Dii foorlaachn ôôdsajchn',
'tooltip-ca-nstab-help'           => 'Dii hilfssajdn ôôdsajchn',
'tooltip-ca-nstab-category'       => 'Dii ghadegoriin-sajdn ôôdsajchn',
'tooltip-minoredit'               => 'Dii ändrung als glôô auswajsn',
'tooltip-save'                    => 'Was de gmachd hasch, jeds alâs schbajchârn',
'tooltip-preview'                 => 'Forhäär ôôdsajchn, was de alâs hiir gmachd hasch. Bide mach des, befoor de alâs endgildich schbajchârsch.',
'tooltip-diff'                    => 'Ôôdsajchn, was de hiir alâs umbäschdld hasch.',
'tooltip-compareselectedversions' => "Den undârschiid dswischn dswaa rausgsuchdn wärsjoon'n fon däär sajdn ôôdsajchn.",
'tooltip-watch'                   => 'Dii sajdn fir Dii undâr beobachdung neem',
'tooltip-recreate'                => 'Dii sajdn naj ôôleechn, obwool se scho môôl gleschd wôrn is.',
'tooltip-upload'                  => 'Loos midm nauflaadn',
'tooltip-rollback'                => 'Hiir glign machd mid ôôm môôl alâs riggängich, was däär benudsârs dsledschd uf dr sajdn gmachd had.',
'tooltip-undo'                    => 'Hiir glign machd dii ôône ändrung riggängich un dsajch dan ôô, wiis dan aussään dääd, dan ghausch aa noch â dsusamnfasung wisoo un warum dâdsuuschrajwn.',

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

# Bad image list
'bad_image_list' => 'Fôrmaad:

Nôr dsajln, dii was midem dsajchn * ôôfangn, wärn berigsichdichd. Un dr ärschde lingg uf dr dsajln mus dan uf â uubasnde dadaj sajn. Wen dahindâr noch meâ linggs ghumn, dan geldn dii als ausnaamn, wo dâgeechn des - dsum bajschbiil  - bild schdeen däf.',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'ale',
'namespacesall' => 'ale',

# Special:SpecialPages
'specialpages' => 'Schbedsjaal-sajdn',

);

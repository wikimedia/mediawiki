<?php
/** Wolof (Wolof)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Ahloubadar
 * @author Ibou
 * @author Maax
 * @author Reedy
 * @author SF-Language
 * @author Urhixidur
 */

$fallback = 'fr';

$namespaceNames = array(
	NS_MEDIA            => 'Xibaarukaay',
	NS_SPECIAL          => 'Jagleel',
	NS_TALK             => 'Waxtaan',
	NS_USER             => 'Jëfandikukat',
	NS_USER_TALK        => 'Waxtaani_jëfandikukat',
	NS_PROJECT_TALK     => '$1_waxtaan',
	NS_FILE             => 'Dencukaay',
	NS_FILE_TALK        => 'Waxtaani_dencukaay',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Waxtaani_MediaWiki',
	NS_TEMPLATE         => 'Royuwaay',
	NS_TEMPLATE_TALK    => 'Waxtaani_royuwaay',
	NS_HELP             => 'Ndimbal',
	NS_HELP_TALK        => 'Waxtaani_ndimbal',
	NS_CATEGORY         => 'Wàll',
	NS_CATEGORY_TALK    => 'Waxtaani_wàll',
);

$namespaceAliases = array(
	'Discuter' => NS_TALK,
	'Utilisateur' => NS_USER,
	'Discussion_Utilisateur' => NS_USER_TALK,
	'Discussion_$1' => NS_PROJECT_TALK,
	'Discussion_Image' => NS_FILE_TALK,
	'Discussion_MediaWiki' => NS_MEDIAWIKI_TALK,
	'Modèle' => NS_TEMPLATE,
	'Discussion_Modèle' => NS_TEMPLATE_TALK,
	'Aide' => NS_HELP,
	'Discussion_Aide' => NS_HELP_TALK,
	'Catégorie' => NS_CATEGORY,
	'Discussion_Catégorie' => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Userlogin'                 => array( 'Lonku' ),
	'Userlogout'                => array( 'Lonkiku' ),
	'CreateAccount'             => array( 'Sos am sàq' ),
	'Preferences'               => array( 'Tànneef' ),
	'Watchlist'                 => array( 'Limu toppte' ),
	'Recentchanges'             => array( 'Coppite yu mujj' ),
	'Upload'                    => array( 'Yeb' ),
	'Listfiles'                 => array( 'Limu nataal yi' ),
	'Newimages'                 => array( 'Nataal bu bees' ),
	'Listusers'                 => array( 'Limu jëfandikukat yi' ),
	'Listgrouprights'           => array( 'Limu mboolooy jëfandikukat' ),
	'Randompage'                => array( 'Xët cig mbetteel' ),
	'Lonelypages'               => array( 'Xëtu jirim' ),
	'Uncategorizedpages'        => array( 'Xët yi amul wàll' ),
	'Uncategorizedcategories'   => array( 'Wàll yi amul wàll' ),
	'Uncategorizedimages'       => array( 'Nataal yi amul wàll' ),
	'Uncategorizedtemplates'    => array( 'Royuwaay yi amul wàll' ),
	'Unusedcategories'          => array( 'Royuwaay yiñ jëfandikuwul' ),
	'Unusedimages'              => array( 'Nataal yiñ jëfandikuwul' ),
	'Wantedpages'               => array( 'Xët yiñ laaj' ),
	'Wantedcategories'          => array( 'Wàll yiñ laaj' ),
	'Mypage'                    => array( 'Sama xët' ),
	'Mytalk'                    => array( 'Samay waxtaan' ),
	'Mycontributions'           => array( 'Samay cëru' ),
	'Search'                    => array( 'Ceet' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Rëddaatu lëkkalekaay yi :',
'tog-highlightbroken'         => 'Wone leen <a href="" class="new">ñu xonq</a> lëkkalekaay yiy yóbbe ciy xët yu amul (lu ko moy :  lu mel nii <a href="" class="internal">?</a>)',
'tog-justify'                 => 'Maaseel xise yi',
'tog-hideminor'               => 'Nëbb coppite yu néewal yi mujj',
'tog-hidepatrolled'           => 'Nëbb coppite yi ñuy fuglu ci coppite yu mujj yi',
'tog-newpageshidepatrolled'   => 'Nëbb xët yi ñuy fuglu, yi ci limu xët yu bees yi',
'tog-extendwatchlist'         => 'Yatalal limu toppte bi ngir muy wone bépp coppite, déet yu mujj yi rekk',
'tog-usenewrc'                => 'Jëfandikool coppite yu mujj yees gënal (laaj na JavaScript)',
'tog-numberheadings'          => 'Koj yi jox lim seen bopp',
'tog-showtoolbar'             => 'Wone bànqaasu njëlu coppite bi (JavaScript)',
'tog-editondblclick'          => 'Cuq cuqaatal ngir soppi aw xët (JavaScript)',
'tog-editsection'             => 'Soppi ab xaaj jaare ko cib lëkkalekaay [Soppi]',
'tog-editsectiononrightclick' => 'Soppi ab xaaj cib cuqub ndeyjoor ci kojam  (JavaScript)',
'tog-showtoc'                 => 'Wone tëralinu ne-ne yi (ngir xët yi ëpp 3 xaaj)',
'tog-rememberpassword'        => 'Fattalikul sama baatujàll  (for a maximum of $1 {{PLURAL:$1|day|days}})',
'tog-watchcreations'          => 'Yokk ci sama limu toppte xët yi may sos',
'tog-watchdefault'            => 'Yokk ci sama limu toppte xët yi may soppi',
'tog-watchmoves'              => 'Yokk ci sama limu toppte xët yi may tuddaat',
'tog-watchdeletion'           => 'Yokk ci sama limu toppte xët yi may far',
'tog-minordefault'            => 'jàppe samay coppite ni yu néewal saa su ne',
'tog-previewontop'            => 'Tegal wonendi gi ci kaw balaa boyotu coppite bi',
'tog-previewonfirst'          => 'wone wonendi gi su dee soppi gu njëkk la',
'tog-nocache'                 => 'Doxadil ndenciti xët yi',
'tog-enotifwatchlistpages'    => 'Yónne ma ab bataaxal su aw xët wu ne ci sama limu toppte soppikoo',
'tog-enotifusertalkpages'     => 'Yónne ma ab bataaxal su ay coppite amee ci sama xëtu waxtaanuwaay',
'tog-enotifminoredits'        => 'Yónne ma ab bataaxal donte coppite yu néew lañu',
'tog-enotifrevealaddr'        => 'Wone sama màkkaan bu mbëjfeppal ci bataaxali yëgle yi',
'tog-shownumberswatching'     => 'Wone limu jëfandikukat yiy topp wii xët',
'tog-oldsig'                  => 'Wonendig xaatim gi teew:',
'tog-fancysig'                => 'Soppi sa xaatim (du am lëkkalekaay bu boppu)',
'tog-externaleditor'          => 'Jëfandikoo soppikaay bu biti saa su ne',
'tog-externaldiff'            => 'Jëfandiku ab méngalekaay bu biti saa su ne (ngir jëfandikukat yu xarale yi rekk, dafa laaj yenn kocc-koccal yi ci sa nosukaay)',
'tog-showjumplinks'           => 'Doxalal lëkkalekaay yii di « joowin » ak « seet »',
'tog-uselivepreview'          => 'Jëfandikul wonendi gu gaaw gi (JavaScript)',
'tog-forceeditsummary'        => 'Wax ma ko suma mottaliwul koju coppite bi',
'tog-watchlisthideown'        => 'Nëbb samay coppite ci limu toppte bi',
'tog-watchlisthidebots'       => 'Nëbb coppite yi bot yi def ci biir limu toppte bi',
'tog-watchlisthideminor'      => 'Nëbb coppite yu néewal yi ci biir limu toppte bi',
'tog-watchlisthideliu'        => 'Nëbb coppite yu jëfandikukat yi bindu ci limu toppte bi',
'tog-watchlisthideanons'      => 'Nëbb coppite yu jëfandikukat yi binduwul ci limu toppte bi',
'tog-watchlisthidepatrolled'  => 'Nëbb coppite yi ñu aar, yi ci limu toppte bi',
'tog-ccmeonemails'            => 'Yónne ma ab duppit bu bataaxal yi may yónne yeneen jëfandikukat yi',
'tog-diffonly'                => 'Bul wone ëmbitu xët yi ci suufu fi ngay méngalee ay sumbam',
'tog-showhiddencats'          => 'Wone wàll yi nëbbu',
'tog-norollbackdiff'          => 'Bul wone diff bi ginnaaw bu ma defee ag loppanti',

'underline-always'  => 'Saa su ne',
'underline-never'   => 'Mukk',
'underline-default' => 'Aju ci joowukaay bi',

# Font style option in Special:Preferences
'editfont-style'     => 'Meliinu mbind mu barabu coppite bi:',
'editfont-default'   => 'Bi wàccaaleek joowukaay bi',
'editfont-monospace' => 'Dayoob mbind genn dig-digal',
'editfont-sansserif' => 'Dayoob mbind bu amul-dig',
'editfont-serif'     => 'Dayoob mbind bu am-dig',

# Dates
'sunday'        => 'dibéer',
'monday'        => 'altine',
'tuesday'       => 'talaata',
'wednesday'     => 'àllarba',
'thursday'      => 'alxamis',
'friday'        => 'àjjuma',
'saturday'      => 'gaawu',
'sun'           => 'dib',
'mon'           => 'alt',
'tue'           => 'tal',
'wed'           => 'àll',
'thu'           => 'alx',
'fri'           => 'àjj',
'sat'           => 'gaa',
'january'       => 'Semwiyee',
'february'      => 'Fewriyee',
'march'         => 'Maars',
'april'         => 'Awril',
'may_long'      => 'Mee',
'june'          => 'Suwe',
'july'          => 'Sulet',
'august'        => 'Ut',
'september'     => 'Sattumbar',
'october'       => 'Oktoobar',
'november'      => 'Nowembar',
'december'      => 'Deesàmbar',
'january-gen'   => 'Samwie',
'february-gen'  => 'Fewirie',
'march-gen'     => 'Maars',
'april-gen'     => 'Awril',
'may-gen'       => 'Mee',
'june-gen'      => 'Suwe',
'july-gen'      => 'Sulet',
'august-gen'    => 'Ut',
'september-gen' => 'Sattumbar',
'october-gen'   => 'Oktoobar',
'november-gen'  => 'Nowembar',
'december-gen'  => 'Disembar',
'jan'           => 'Sam',
'feb'           => 'Few',
'mar'           => 'Maa',
'apr'           => 'Awr',
'may'           => 'Mee',
'jun'           => 'Suw',
'jul'           => 'Sul',
'aug'           => 'Ut',
'sep'           => 'Sat',
'oct'           => 'Okt',
'nov'           => 'Now',
'dec'           => 'Dis',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Wàll |Wàll }}',
'category_header'                => 'Xëti wàll wi « $1 »',
'subcategories'                  => 'Ron-wàll',
'category-media-header'          => 'Jukkiy wàll wi « $1 »',
'category-empty'                 => "''Nii-nii wàll wii ëmbul tus, dub ron-wàll, dub jukki, dub dencukaay. ''",
'hidden-categories'              => '{{PLURAL:$1|wàll bi nëbbu|wàll yi nëbbu}}',
'hidden-category-category'       => 'Wàll yi nëbbu',
'category-subcat-count'          => '{{PLURAL:$2|Wii wàll wenn ron-wàll rekk la am, di wiy toftal.| {{PLURAL:$1|Wii ron-wàll |$1 Ron-wàll yii }} bokk  {{PLURAL:$1|na|nañu}} ci $2 ron-wàll yi nekk ci wii wàll.}}',
'category-subcat-count-limited'  => 'Wii wàll am na {{PLURAL:$1|ron-wàll|$1 ron-wàll}}.',
'category-article-count'         => '{{PLURAL:$2|Wii wàll wenn xët rekk la am, di wiy toftal.|{{PLURAL:$1|Wii xët |$1 xët yii }} bokk  {{PLURAL:$1|na|nañu}} ci $2 xët yi nekk ci wii wàll.}}',
'category-article-count-limited' => 'Wii wàll ëmb na {{PLURAL:$1|xët wiy toftal |$1 xët yiy toftal}}.',
'category-file-count'            => '{{PLURAL:$2|Wii wàll benn dencukaay rekk la ëmb, di biy toftal.| {{PLURAL:$1|Bii dencukaay |$1 dencukaay yii }} bokk  {{PLURAL:$1|na|nañu}} ci $2 dencukaay yi nekk ci wii wàll.}}',
'category-file-count-limited'    => 'Wii wàll moo ëmb {{PLURAL:$1|dencukaay biy toftal|$1 dencukaay yiy toftal}}.',
'listingcontinuesabbrev'         => '(desit)',

'mainpagetext'      => "'''Campug MediaWiki gi sotti na . '''",
'mainpagedocfooter' => 'Saytul [http://meta.wikimedia.org/wiki/Ndimbal:Ndefu Gindikaayu jëfandikukat bi] ngir yeneeni xibaar ci jëfandiku gu tëriin gi.

== Tambali ak MediaWiki ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Limu jumtukaayi kocc-koccal gi]
* [http://www.mediawiki.org/wiki/Manual:FAQ FAQ MediaWiki]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Limu waxtaan ci liy-génn ci MediaWiki]',

'about'         => 'Ci mbirum',
'article'       => 'Jukki',
'newwindow'     => '(Day ubbeeku ci beneen palanteer)',
'cancel'        => 'Neenal',
'moredotdotdot' => 'Ak yeneen...',
'mypage'        => 'Samaw xët',
'mytalk'        => 'Xëtu waxtaanuwaay',
'anontalk'      => 'Waxtaan ak bii IP',
'navigation'    => 'Joowiin',
'and'           => '&#32;ak',

# Cologne Blue skin
'qbfind'         => 'Seet',
'qbbrowse'       => 'Lemmi',
'qbedit'         => 'Soppi',
'qbpageoptions'  => 'Xëtuw tànneef',
'qbpageinfo'     => 'Xëtuw xibaar',
'qbmyoptions'    => 'Samay tànneef',
'qbspecialpages' => 'Xëti jagleel',
'faq'            => 'Laaj yi ëpp',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection' => 'Yokkuw tëriit',
'vector-action-delete'     => 'Far',
'vector-action-move'       => 'Tuddewaat',
'vector-action-protect'    => 'Aar',
'vector-action-undelete'   => 'Delloowaat',
'vector-action-unprotect'  => 'Aaradi',
'vector-view-create'       => 'Sos',
'vector-view-edit'         => 'Soppi',
'vector-view-history'      => 'Wone jaar-jaaram',
'vector-view-view'         => 'Jàng',
'vector-view-viewsource'   => 'Wone gongikuwaayam',
'actions'                  => 'Jëf',
'namespaces'               => 'Barabu tur',
'variants'                 => 'Wuute',

'errorpagetitle'    => 'Njuumte',
'returnto'          => 'Dellu ci wii xët $1.',
'tagline'           => 'Jóge  {{SITENAME}}.',
'help'              => 'Ndimbal',
'search'            => 'Seet',
'searchbutton'      => 'Seet',
'go'                => 'Ayca',
'searcharticle'     => 'Ayca',
'history'           => 'Jaar-jaaru xët wi',
'history_short'     => 'Jaar-jaar',
'updatedmarker'     => 'Ci samag nemmeeku gu mujj lañ ko soppi',
'info_short'        => 'Xibaar',
'printableversion'  => 'Sumb bu móolu',
'permalink'         => 'Lëkkalekaay yu sax',
'print'             => 'Móol',
'edit'              => 'Soppi',
'create'            => 'Sos',
'editthispage'      => 'Soppi xët wii',
'create-this-page'  => 'Sos wii xët',
'delete'            => 'Far',
'deletethispage'    => 'Far wii xët',
'undelete_short'    => 'Delloowaat {{PLURAL:$1|1 coppite| $1 ciy coppite}}',
'protect'           => 'Aar',
'protect_change'    => 'soppi',
'protectthispage'   => 'Aar wii xët',
'unprotect'         => 'Aaradi',
'unprotectthispage' => 'Aaradil wii xët',
'newpage'           => 'Xët wu bees',
'talkpage'          => 'Xëtu waxtaanuwaay',
'talkpagelinktext'  => 'Diisoo',
'specialpage'       => 'Xëtu jagleel',
'personaltools'     => 'Samay jumtukaay',
'postcomment'       => 'Xaaj bu bees',
'articlepage'       => 'Gis jukki bi',
'talk'              => 'Waxtaan',
'views'             => 'Xool yo',
'toolbox'           => 'Boyotu jumtukaay yi',
'userpage'          => 'Xëtu jëfandikukat',
'projectpage'       => 'Wone xëtu sémb wi',
'imagepage'         => 'Wone xëtu dencukaay bi',
'mediawikipage'     => 'Wone xëtu bataaxal bi',
'templatepage'      => 'Wone xëtu royuwaay bi',
'viewhelppage'      => 'Xoolal xëtu ndimbal wi',
'categorypage'      => 'Wone xëtu wàll wi',
'viewtalkpage'      => 'Xëtu diisoowaay',
'otherlanguages'    => 'Yeneeni làkk',
'redirectedfrom'    => '(Yoonalaat gu jóge $1)',
'redirectpagesub'   => 'Xëtu yoonalaat',
'lastmodifiedat'    => 'Coppite bu mujj bu xët wii $1 ci $2.<br />',
'viewcount'         => 'Xët wii nemmeeku nañ ko {{PLURAL:$1|$1 yoon|$1 yoon}}.',
'protectedpage'     => 'Xët wees aar',
'jumpto'            => 'Dem :',
'jumptonavigation'  => 'Joowiin',
'jumptosearch'      => 'Seet',
'view-pool-error'   => 'jéggalu, joxekaay yi dañoo xat nii-nii.
Jëfandikukat yiy jéem a ubbi xët wii dañoo bari.
Taaxiirlul ba ci kanam nga jéemaat.

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Ci mbiri {{SITENAME}}',
'aboutpage'            => 'Project:Ci mbiri',
'copyright'            => 'Ëmbit laa ngi  jàppandi ci $1.',
'copyrightpage'        => '{{ns:project}}:Copyright',
'currentevents'        => 'Luy xew',
'currentevents-url'    => 'Project:Luy xew',
'disclaimers'          => 'Ay aartu',
'disclaimerpage'       => 'Project:Aartu yu daj',
'edithelp'             => 'Ndimbal',
'edithelppage'         => 'Help:Nooy soppee aw xët',
'helppage'             => 'Help:Ndimbal',
'mainpage'             => 'Xëtu Njëlbéen',
'mainpage-description' => 'Xët wu njëkk',
'policy-url'           => 'Project:àtte',
'portal'               => 'Buntub askan',
'portal-url'           => 'Project:Xët wu njëkk',
'privacy'              => 'Politigu mbóot',
'privacypage'          => 'Project:Xibaar ci say mbóot',

'badaccess'        => 'Njuumte ci ndigël gi',
'badaccess-group0' => 'Amoo ay sañ-sañ yu doy ngir man a def li nga bëgg a def.',
'badaccess-groups' => 'Jëf ji ngay jéem a def dañu koo jagleel jëfandikukat yi bokk ci {{PLURAL:$2|mbooloo mu|benn ci mbooloo yi toftal}}: $1.',

'versionrequired'     => 'Laaj na $1 sumbub MediaWiki',
'versionrequiredtext' => 'Laaj na $1 sumbum MediaWiki ngir man a jëfandikoo wii xët. Xoolal [[Special:Version|fii]]',

'ok'                      => 'waaw',
'retrievedfrom'           => 'Ci « $1 » lañ ko jële',
'youhavenewmessages'      => 'Am nga $1 ($2).',
'newmessageslink'         => 'Bataaxal yu bees',
'newmessagesdifflink'     => 'Coppite gu mujj',
'youhavenewmessagesmulti' => 'Am nga bataaxal yu yees ci $1',
'editsection'             => 'Soppi',
'editold'                 => 'Soppi',
'viewsourceold'           => 'Xool gongikuwaayam',
'editlink'                => 'soppi',
'viewsourcelink'          => 'xool gongikuwaayam',
'editsectionhint'         => 'Soppi bii xaaj : $1',
'toc'                     => 'Tëraliin',
'showtoc'                 => 'Wone',
'hidetoc'                 => 'Nëbb',
'thisisdeleted'           => 'Da ngaa bëgg a wone walla delloowaat $1 ?',
'viewdeleted'             => 'Xool $1 ?',
'restorelink'             => '{{PLURAL:$1|1 coppite lañ far |$1 ciy coppite lañ far}}',
'feedlinks'               => 'Wal',
'feed-invalid'            => 'Gii xeetu wal baaxul.',
'feed-unavailable'        => 'juumtukaayu wone soppi yu yees yi jàppandiwul',
'site-rss-feed'           => 'Walu RSS gu $1',
'site-atom-feed'          => 'Walu Atom gu $1',
'page-rss-feed'           => 'Walu RSS gu "$1"',
'page-atom-feed'          => 'Walu Atom gu "$1"',
'feed-atom'               => 'Atom',
'feed-rss'                => 'RSS',
'red-link-title'          => '$1 (xët wi amagul)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Xët',
'nstab-user'      => 'Xëtu jëfandikukat',
'nstab-media'     => 'Xibaarukaay',
'nstab-special'   => 'Xëtu jagleel',
'nstab-project'   => 'Xëtu sémb',
'nstab-image'     => 'Dencukaay',
'nstab-mediawiki' => 'Bataaxal',
'nstab-template'  => 'Royuwaay',
'nstab-help'      => 'Xëtu ndimbal',
'nstab-category'  => 'Wàll',

# Main script and global functions
'nosuchaction'      => 'Jëf ji xameesu ko',
'nosuchactiontext'  => 'Jëf ji nga def ci URL bi xameesu  ko.
Xéj-na dangaa juum ci bind URL bi, walla nga topp lëkkalekaay bu baaxul.
Lii man naa doon it ag njuumte ci tëriin bi ñuy jëfandikoo ci {{SITENAME}}.',
'nosuchspecialpage' => 'Xëtu jagleel wu amul',
'nospecialpagetext' => '<strong>Da nga laaj aw xëtu jagleel wu wiki bi xamul.</strong>

Ab limu xëti jagleel yépp, ma nees na koo gis ci [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Njuumte',
'databaseerror'        => 'Njuumtey dàttub njoxe bi',
'dberrortext'          => '�Njuumtey mbindin ci laaj bi nga yónne dàttub njoxe bi.
Man na nekk it ab njuumte ci tëriin bi.
Laaj bees mujje yónne ci dàttub njoxe bi moo doonoon:
<blockquote><tt>$1</tt></blockquote>.
bàyyikoo ci bii solo « <tt>$2</tt> ».
Dàttub njoxe bee delloo bii njuumte « <tt>$3 : $4</tt> ».',
'dberrortextcl'        => 'Ab laajub dàttub njoxe bi jur na njuumte.
Laaj bees mujje yónne dàttub njoxe bi moo doon :
« $1 »
bàyyikoo ci bii solo « $2 ».
Dàttub njoxe bi delloo bii njuumte « $3 : $4 ».',
'laggedslavemode'      => 'Moytul, wii xët man naa bañ a man dékku coppite yi ñu mujjee def',
'readonly'             => 'Dàttub njoxe bi dañu koo caabi',
'enterlockreason'      => 'Biralal ngirtey tëj gi ak diir bi mu war a amee',
'readonlytext'         => 'Ci jamono jii yokk yeek coppitey xët yi jàppandiwuñu ndax dàttub njoxe bi dañu koo caabi, xéj-na dañoo nekk ciy liggéey, su ñu noppee rekk dàttub njoxe bi baaxaat.
Yorkat bi def caabi ji, joxe na yii lay :$1',
'missing-article'      => 'Dàttub njox bi manuta fexe ba gis mbidum ab jukki bu mu waaroona mana gis, mi ngi tudd  « $1 » $2.

Li koy waral yenn saa yi mooy da ngay jéma ubbi ab lëkkalekaay, jaare ko ci ab jaar-jaar walla méngaleeb ay sumb yu aw xët wu faru.

Su dul loolu kon daa am ag njuumte ci tëriinu Mediawiki bi.
Di la sakku nga yegge ko ab [[Special:ListUsers/sysop|yorkat]] , jox ko màkkaan bi.',
'missingarticle-rev'   => '(Limu sumb bi# : $1)',
'missingarticle-diff'  => '(Wuute : $1, $2)',
'readonly_lag'         => 'Dàttub njoxe bi daa caabi boppam ngir may ñaareelu joxekaay yi dap joxekaay bu njëkk bi.',
'internalerror'        => 'Njuumte gu biir',
'internalerror_info'   => 'Njuumte gu biir : $1',
'fileappenderror'      => 'Maneesul a yokk "$1" ci "$2".',
'filecopyerror'        => 'Duppig dencukaay bii di « $1 » jëm « $2 » antuwul.',
'filerenameerror'      => 'Tuddewaatug « $1 » niki « $2 » antuwul.',
'filedeleteerror'      => 'Farug dencukaay bii di « $1 » antuwul.',
'directorycreateerror' => 'Sosug wayndare bii di « $1 » antuwul.',
'filenotfound'         => 'Gisug dencukaay bii di « $1 » antuwul.',
'fileexistserror'      => 'Mbind mi ci wii wayndare « $1 » antuwul : dencukaay bi am na ba noppi',
'formerror'            => 'Tolof-tolof:Maneesu la yonnee mbind mi',
'badarticleerror'      => 'Jii jëf defuwul ci wii xët.',
'cannotdelete'         => 'Farug xët walla dencukaay bi antuwul. (xayna  keneen def na ko ba noppi.)',
'badtitle'             => 'Koj bu baaxul',
'badtitletext'         => 'Kojug xët wi nga laaj baaxul, amul dara walla  day di kojjug diggantelàkk walla diggantesémb yu seen lonkoo baaxul. Xayna it dafa am benn walla ay araf yu ñu manuta jëfandikoo cib koj.',
'perfcached'           => 'Lii ab duppitu ndencitu sumb mi la, kon xéj-na beesul.',
'perfcachedts'         => 'Njoxe yii di toftal ab duppitu nëbbitu dàttub njoxe bi la, te yeesalam gu mujj mi ngi am ci: $1',
'querypage-no-updates' => 'Yeesal yu xët wii dañ leen a doxadil fi mu ne nii. Xibaar yi ne fii ci suuf beesuñu.',
'wrong_wfQuery_params' => 'Njuumte ci xibaar yi ci wfQuery()<br />
Solo : $1<br />
Laaj : $2',
'viewsource'           => 'Xool gongikuwaayam',
'viewsourcefor'        => 'ngir $1',
'actionthrottled'      => 'Jëf jees digal',
'actionthrottledtext'  => 'Ngir xeex spam yi, jëf ji nga namm a def dañ kaa digal ci yoon yoo ko man ci benn diir bu gatt. Te mel na ne romb nga boobu dig. Jéemaatal fii aki simili.',
'protectedpagetext'    => 'Wii xët dañ koo aar ngir bañ ag coppiteem.',
'viewsourcetext'       => 'Man ngaa xool te duppi li nekk ci bii jukki ngir man cee liggéey :',
'protectedinterface'   => 'Xët wii dafa am ay mbind yu bokk ci jokkalekaayu tëriin wi, moo tax ñu caabi ko ngir bañ ku ci ëppal.',
'editinginterface'     => "'''Moytul''': mbindu xët wii dafa bokk ci jokkalekaayu tëriin bi. Bépp coppite boo ci def day feeñ ci bataaxal yi yeneen jëfandikukat yi di gis. Ngir tekki yi ñu lay ñaan nga dem ci   [http://translatewiki.net/wiki/Main_Page?setlang=wo translatewiki.net] di am sémb ngir bennal bataaxal yi.",
'sqlhidden'            => '(Laaju SQL nëbbu na)',
'cascadeprotected'     => 'Xët wii dañ kaa aar ndaxte daa ëmbu ci {{PLURAL:$1|xët wi toftal|xët yi toftal}}, di yu ñu aar :
$2',
'namespaceprotected'   => "Amoo sañ-sañu soppi xët yi ne ci bii barabu tur « '''$1''' ».",
'customcssjsprotected' => 'Amoo sañ-sañu soppi wii xët ndaxte daa ëmb ay tànneefi yeneeni jëfandikukat.',
'ns-specialprotected'  => 'Xët yi ne ci bii barabu tur « {{ns:special}} » kenn maneesu leen a soppi.',
'titleprotected'       => "Koj bii [[User:$1|$1]] moo ko aar ngir bañ sosteefam.
Ngirte li mu joxe mooy ne « ''$2'' ».",

# Virus scanner
'virus-badscanner' => "Tànnéef wu bon: saytukatu wiris bees xamul: ''$1''",
'virus-scanfailed' => 'Saytu gi dog na (code $1)',

# Login and logout pages
'logouttext'                 => "Fi mu nekk nii génn nga.'''

Man ngaa wéy di jëfandikoo {{SITENAME}} ci anam buñ la dul xamme walla nga  [[Special:UserLogin|duggewaat]] ak wenn tur wi walla ak weneen.",
'welcomecreation'            => '== Dalal-jàmm, $1 ! ==
Sosees na sa sàq.
Bul fatte soppi say [[Special:Preferences|{{SITENAME}} tànneef]].',
'yourname'                   => 'Sa turu jëfandikukat',
'yourpassword'               => 'Sa baatujàll',
'yourpasswordagain'          => 'Bindaatal sa baatujàll',
'remembermypassword'         => 'Denc sama yëgley dukkukaay ci bii joowukaay (lu ëpp nag $1 {{PLURAL:$1|day|days}})',
'yourdomainname'             => 'Sa barab',
'externaldberror'            => 'Njuumte judd na ci dàttub njoxe bi, walla day ni rekk amuloo sañ-sañu yeesal sa sàqum biti.',
'login'                      => 'xammeeku',
'nav-login-createaccount'    => 'Dugg / Bindu',
'loginprompt'                => 'Faaw nga doxal cookie yi ngir man a dugg ci {{SITENAME}}.',
'userlogin'                  => 'Dugg / Bindu',
'logout'                     => 'Génnu',
'userlogout'                 => 'Génnu',
'notloggedin'                => 'Duggoo de',
'nologin'                    => "Sosagoo am sàq ? '''$1'''.",
'nologinlink'                => 'Sos ko leegi',
'createaccount'              => 'Sos am sàq',
'gotaccount'                 => "Sos nga am sàq? '''$1'''.",
'gotaccountlink'             => 'Dugg',
'createaccountmail'          => 'Jaare ko ci m-bataaxal',
'badretype'                  => 'Baatujàll yi nga bind yemuñu.',
'userexists'                 => 'Turu jëfandikukat bi nga bind am na boroom ba noppi. Tànnal weneen.',
'loginerror'                 => 'Njuumte ci dugg gi',
'createaccounterror'         => 'Sosug sàq mi antuwul: $1',
'nocookiesnew'               => 'Sàqum jëfandikukat mi sosu na, waaye dugg gi antuwul. {{SITENAME}} day jëfandikoo ay cookie ngir dugg gi, waaye danga leen doxadil. Doxal leen ci sa joowukaay te duggaat ak sa tur ak sa baatujàll bi nga sos.',
'nocookieslogin'             => '{{SITENAME}} day jëfandikoo ay cookie ngir dugg gi, te yaw say cookies dañoo doxadi. Doxal leen ci sa joowukaay te jéem a duggaat.',
'noname'                     => 'Bindoo turu jëfandikukat bi baax.',
'loginsuccesstitle'          => 'Sag dugg jàll na',
'loginsuccess'               => 'Léegi nag dugg nga ci {{SITENAME}} yaay « $1 ».',
'nosuchuser'                 => 'Amul benn jëfandikukat bu tudd « $1 ». Xoolaatal bu baax mbindiin mi walla nga sos meneen sàqum jëfandikukat.',
'nosuchusershort'            => 'Amul benn jëfandikukat bu tudd « <nowiki>$1</nowiki> ». Xoolaatal mbidiin mi.',
'nouserspecified'            => 'Laaj na nga tànn ab turu jëfandikukat',
'wrongpassword'              => 'Bii baatujàll baaxul. Jéemaatal.',
'wrongpasswordempty'         => 'Duggaloo ab baatujàll, jéemaatal.',
'passwordtooshort'           => 'Sa baatujàll war naa am lumu néew  {{PLURAL:$1|1 màndarga|$1 màndarga}}.',
'password-name-match'        => 'Sa baatujàll dafa war a wuute ak sa turu jëfandikukat',
'mailmypassword'             => 'Yónne ma ab baatujàll bu bees',
'passwordremindertitle'      => 'Sa baatujàll bu bees ci {{SITENAME}}',
'passwordremindertext'       => 'Kenn(xéj-na yaw la) ku am bii màkkaanu IP $1 moo laaj ngir ñu yónne ko ab baatujàll bu bees ngir duggam ci {{SITENAME}} ($4).
Baatujàll bu jëfandikukat bii di « $2 » léegi mooy « $3 ».
Di la digal rekk nga dugg te soppi baatujàll bi ci ni mu gëna gaawee.
Baatujáll bii nag diirub dundam {{PLURAL:$5|fan|$5 fan}} la.

Soo doonul ki biral bii laaj, walla fattaliku nga sa baatujàll bu njëkk ba, te nammatoo koo soppi, man ngaa tankamlu bii bataaxal te wéy di jëfandikoo baatujàll bu yàgg ba.',
'noemail'                    => 'Bii jëfandikukat « $1 » amufi benn màkkaanub m-bataaxal.',
'noemailcreate'              => 'Laaj na nga joxe màkkaanub m-bataaxal bu baax',
'passwordsent'               => 'Ab baatujàll bu bees yónne nañ ko ci màkkaanub m-bataaxal bu jëfandikukat bii di « $1 ». Jéemal a duggaat soo ko jotee.',
'blocked-mailpassword'       => 'Ngir faggandiku ci yaq gi, ku ñu téye sa màkkaanu IP ba doo man a soppi dara, doo man a yónneelu baatujàll bu bees.',
'eauthentsent'               => 'Yónnee nañ la ab m-bataaxalub dëggal ci màkkaanub m-bataaxal bi nga joxe. Balaa ñuy yónnee beneen m-bataaxal ci bii màkkaan, fawwu nga topp tektal yiñ la jox ngir dëggal ni yaa moom bii màkkaan.',
'throttled-mailpassword'     => 'Ab m-bataaxal bu lay fattali sa baatujàll yónne nañu la ko, am na {{PLURAL:$1|waxtu|$1 waxtu}}.
Ngir moytu ay yaq, benn m-bataaxalu fattali rek lañu lay yónne ci diiru {{PLURAL:$1|waxtu|$1 hours}}..',
'mailerror'                  => 'Njuumte ci yónneeb m-bataaxal bi : $1',
'acct_creation_throttle_hit' => 'Kenn kuy jëfandikoo bii màkkaanu IP sos na {{PLURAL:$1|am sàq|$1 sàq}} ci bés bu mujj bi, te mooy lim bi ëpp bi ñu la sañalal ci bii diir. Loolu moo tax, jëfandikukatu bii màkkaanu IP manuta sos sàq mu bees ci jii jamono.',
'emailauthenticated'         => '$2 ci $3. Nga dëggal sa màkkaanu m-bataaxal.',
'emailnotauthenticated'      => 'Dëggalagoo sa m-bataaxal. Duñ la man a yónne benn m-bataaxal bu aju ci yii ci suuf.',
'noemailprefs'               => 'Joxeel ab m-bataaxal ngir doxal yii solo',
'emailconfirmlink'           => 'Dëggalal sa m-bataaxal',
'invalidemailaddress'        => 'Dayoob m-bataaxal bi baaxul. Duggalal beneen walla nga bàyyi tool bi ne këmm',
'accountcreated'             => 'léegi bindu nga.',
'accountcreatedtext'         => 'Mbindug jëfandikukat bii di $1 jàll na',
'createaccount-title'        => 'Sosum sàq ci {{SITENAME}}',
'createaccount-text'         => 'Kenn ku sos am sàq ci {{SITENAME}} te tudd $2 ($4).
Baatujàll bu « $2 » mooy « $3 ». Li gën mooy nga dugg ci teel te soppi baatujàll bi.

Jéelaleel bataaxal bii su fekkee ci njuumte nga sosee mii sàq.',
'usernamehasherror'          => 'Turu jëfandikukat bi warul am mandarga yu hash',
'login-throttled'            => 'Jéem ngaa dugg ay yoon te jàllul.

Ngalla néggandikul ab diir door a jéemaat.',
'loginlanguagelabel'         => 'Làkk : $1',

# Password reset dialog
'resetpass'                 => 'Soppi baatujàll bi',
'resetpass_announce'        => 'Danga dugg ak ab baatujàll bu saxul-dakk, buñ la yónne cib bataaxal. Ngir matal mbindu mi, faaw nga roof ab baatujàll bu bees fii:',
'resetpass_text'            => '<!-- Bindal fii -->',
'resetpass_header'          => 'Soppi baatujàllu sàq mi',
'oldpassword'               => 'Baatujàll bu yàgg :',
'newpassword'               => 'Baatujàll bu bees :',
'retypenew'                 => 'Bindaatal baatujàll bu bees bi :',
'resetpass_submit'          => 'Soppil baatujàll bi te dugg',
'resetpass_success'         => 'Coppiteeg baatujàll bi antu na : Yaa ngi dugg...',
'resetpass_forbidden'       => 'Baatujàll bi manoo kaa soppi',
'resetpass-no-info'         => 'faaw nga dugg ngir man a jot ci wii xët.',
'resetpass-submit-loggedin' => 'Soppi baatujàll bi',
'resetpass-wrong-oldpass'   => 'Baatujall bu diiru walla bi teew baaxul.
Xèj-na baatujàll bi soppi nga ko ba noppi, walla xéj-na it dangaa laaj beneen baatujàll bu diiru.',
'resetpass-temp-password'   => 'Baatujàll bu diiru :',

# Edit page toolbar
'bold_sample'     => 'Duufal mbind mi',
'bold_tip'        => 'Duufal mbind mi',
'italic_sample'   => 'Wengal mbind mi',
'italic_tip'      => 'Wengal mbind mi',
'link_sample'     => 'Koju lëkkalekaay bi',
'link_tip'        => 'Lëkkalekaay yu biir',
'extlink_sample'  => 'http://www.example.com koju lëkkalekaay bi',
'extlink_tip'     => 'Lëkkalekaay yu biti (bul fattee jiital http://)',
'headline_sample' => 'Ron-koj',
'headline_tip'    => 'Ron-koj 2 tolluwaay',
'math_sample'     => 'Duggalal sa mbind fii',
'math_tip'        => 'Mbindu xayma (LaTeX)',
'nowiki_sample'   => 'Dugalal fii mbind mi ñu joxul melokaan',
'nowiki_tip'      => 'Jéllaleel mbindinu wiki',
'image_sample'    => 'Misaal.jpg',
'image_tip'       => 'Roof ab nataal',
'media_sample'    => 'Misaal.ogg',
'media_tip'       => 'Lëkkalekaay buy jëme ciw ŋara',
'sig_tip'         => 'Xaatimee waxtu wi',
'hr_tip'          => 'Rëdd wu tëdd (bul ci ëppal)',

# Edit pages
'summary'                          => 'Tënk&nbsp;:',
'subject'                          => 'Tëriit/koj:',
'minoredit'                        => 'Coppite yu néewal',
'watchthis'                        => 'Topp xët wii',
'savearticle'                      => 'Wattu xët wi',
'preview'                          => 'Wonendi',
'showpreview'                      => 'Wonendi',
'showlivepreview'                  => 'Wonendi gu gaaw',
'showdiff'                         => 'Wone samay soppi',
'anoneditwarning'                  => "'''Moytul :''' Duggoo. Sa màkkaanub IP di nañu ko dugal ci jaar-jaaru xët wii.",
'missingsummary'                   => "'''Fattali :''' Defoo ab tënk ci coppite yi nga amal. Soo cuqaate ci «Denc xët wi», say coppite di nañ dugg te duñ am tënk, maanaam duñ xam loo soppi.",
'missingcommenttext'               => 'Di la sakku nga dugal ab tënk ci suuf, jërëjëf.',
'missingcommentheader'             => "'''Fattali :''' Joxoo ab koj say coppite. Soo cuqaate ci «Denc xët wi», di nañ leen dugal te duñ am koj.",
'summary-preview'                  => 'Wonendig tënk bu:',
'subject-preview'                  => 'Wonendi gu tëriit/koj:',
'blockedtitle'                     => 'Bii jëfandikukat dañ kaa téye',
'blockedtext'                      => '\'\'\'Sa sàqum jëfandikukat walla sa màkkaanu IP dañ koo téye .\'\'\'

Ki def téye gi mooy $1 te lii mooy ngirte li : \'\'$2\'\'.

* Ndorteelu téye gi : $8
* Njeextalu téye gi : $6
* Sàq mi ñu téye : $7.

Man ngaa jokkoo ak $1 walla kenn ci [[{{MediaWiki:Grouppage-sysop}}|yorkat]] yi ngir ngeen ma cee waxtaan.

Te nga jàpp ne jumtukaay bii di "yónne bataaxal bii jëfandikukat" du dox su fekke dugaluloo ab m-bataaxal ci say [[Special:Preferences|tànneef]].
Sa màkkaanu IP mooy $3, xammeekaayu téye gi moy #$5.
Di la sakku nga dugal leen fépp fuñ la leen laajee',
'autoblockedtext'                  => 'Sa màkkaanu IP dañ kaa téye ndaxte danga koo bokk ak beneen jëfandikukat, te moom it $1 moo ko téye $1.
Te lii mooy ngirte yi mu joxe :

:\'\'$2\'\'

* Ndoorteelu téye gi: $8
* Njeextalu téye gi : $6
* Sàq mees téye: $7


Man ngaa jookkook $1 walla ak kenn ci [[{{MediaWiki:Grouppage-sysop}}|yorkat]] yi ngir waxtaan ci téye gi.

Su fekkee joxe nga ab màkkaanu m-bataaxal ci say [[Special:Preferences|tànneef]] te terewuñu la nga jëfandikoo ko, man ngaa jëfandikoo jumtukaay bii di "yónne ab m-bataaxal bii jëfandikukat" ngir jookkook ab yorkat.

Sa màkkaanu IP mooy $3 xammeekaayu téye gi mooy #$5. Di la sakku nga joxe leen fuñu la leen laajee.',
'blockednoreason'                  => 'Joxewul benn ngirte',
'blockedoriginalsource'            => "Yoonu gongikuwaay wu wii xët '''$1''' moo ne nii ci suuf:",
'blockededitsource'                => "Ëmbitu '''say coppite''' yi nga def fii '''$1''' mooy lii ci suuf:",
'whitelistedittitle'               => 'Laaj na nga dugg ngir man a soppi xët wi',
'whitelistedittext'                => 'Faaw nga doon $1 ngir am sañ-sañu soppi ëmbit li.',
'confirmedittext'                  => 'Ngir man a soppi dara faaw nga dëggal sa m-bataaxal. Ngir kocc-koccal walla dëggal sa màkkaan demal ci say [[Special:Preferences|tànneef]].',
'nosuchsectiontitle'               => 'Xaaj bi amul',
'nosuchsectiontext'                => 'Da nga doon jéema soppi ab xaaj bu amul.',
'loginreqtitle'                    => 'Laaj na nga bindu',
'loginreqlink'                     => 'Dugg',
'loginreqpagetext'                 => 'Faaw nga $1 ngir gis yeneen xët yi.',
'accmailtitle'                     => 'Baatujàll bi yónne nañ ko.',
'accmailtext'                      => "Ab baatujàll ngir [[User talk:$1|$1]]  yónne nañu ko $2.
Baatujàllub bii jëfandikukat bu bees man nga ko soppi ci ''[[Special:ChangePassword|soppi baatujàll]]'' soo duggee ba noppi.",
'newarticle'                       => '(Bees)',
'newarticletext'                   => "Da ngaa topp ab lëkkalekaay buy jëme ci aw xët wu amagul. ngir sos xët wi léegi, duggalal sa mbind ci boyot bii ci suuf (man ngaa yër [[{{MediaWiki:Helppage}}|xëtu ndimbal wi]] ngir yeneeni xamle). Su fekkee njuumtee la fi indi cuqal ci '''dellu''' bu sa joowukaay.",
'anontalkpagetext'                 => "---- ''Yaa ngi ci xëtu waxtaanuwaayu ab jëfandikukatu alaxam, bu bindoogul ba fim ne mbaa jëfandikoowul am sàqam.
Kon ngir xàmmee ko fàw nga jëfandikoo màkkaanub IP wam. Te màkkaanub IP jëfandikukat yu bari man nañ koo bokk.
Su fekkee jëfandikukatu alaxam nga, te nga gis ne dees laa féetale ay kàddu yoo moomul, ngalla [[Special:UserLogin|bindu]] walla [[Special:UserLogin|dugg]] ngir benn jaxase bañatee am ëllëg .''",
'noarticletext'                    => 'Fi mu ne ni amul menn mbind ci xët wii; man ngaa [[Special:Search/{{PAGENAME}}|seet koju xët wi]] ci yeneen xët, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} seet ci xëtu jagleel wi ],
walla [{{fullurl:{{FULLPAGENAME}}|action=edit}} soppi xët wii]</span>.',
'noarticletext-nopermission'       => 'Nii-nii amul menn mbind ci wii xët.
Man nga [[Special:Search/{{PAGENAME}}|seet bii koj]] ci yeneen xët,
walla <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} nga seet ci yéenekaay yees ko féetaleel]</span>.',
'userpage-userdoesnotexist'        => 'Mii sàqum jëfandikukat « $1 » du bu ku-bindu. Seetal bu baax ndax da ngaa namma sos walla soppi wii xët.',
'clearyourcache'                   => "'''Karmat :''' Soo dence xët wi ba noppi, faaw nga far nëmbiitu sa joowukaay ngir man a gis say coppite, te nga, su dee '''Mozilla / Firefox / Safari :''' cuq ci ''yeesal'' te bësaale ''shift'', walla nga bës ''Shift-R'' walla ''Ctrl-F5'' (Command-R ci Mac ), su dee '''Konqueror''': cuq ''yeesal'' walla nga bës ''F5''; su dee '''Opera''' faral nëbiit li (''Jumtukaay → Tànneef'') su dee '''Internet Explorer:''' cuq ci ''yeesal te bësaale ''Ctrl''  walla nga bës ''Ctrl-F5''.",
'usercssyoucanpreview'             => "'''Xelal :''' di la digël nga cuq ci «Wonendi» ngir gis say xobi CSS walla JavaScript yu bees laata nga leen di denc.",
'userjsyoucanpreview'              => "'''Xelal :''' di la digël nga cuq ci «Wonendi» ngir gis say xobi CSS walla JavaScript yu bees laata nga leen di denc.",
'usercsspreview'                   => "Bul fatte ne lii wonendib sa CSS rekk la; dencagoo say coppite!'''",
'userjspreview'                    => "'''Bul fatte ne lii ab wonendib sa yoonu javaScript rekk la; dencagoo say coppite!'''",
'userinvalidcssjstitle'            => "'''Moytul :''' amul genn col gu tudd « $1 ». Bul fatte ne xët yiy jeexee .css ak .js seeni koj ay araf yu tuut ñoo ciy tegu/.<br />ci misaal, {{ns:user}}:Foo/'''v'''ector.css moo baax, waaye bii du baax {{ns:user}}:Foo/'''V'''ector.css .",
'updated'                          => '(bees na)',
'note'                             => "'''Karmat :'''",
'previewnote'                      => "'''Lii ab wonendi rekk la; coppite yi ci xët wi dencagoo leen!'''",
'previewconflict'                  => "Wonendi bi mengóo na ak mbind yi ne ci boyotu coppite bi te nii lay mel soo cuqe ci 'Denc xët wi'.",
'session_fail_preview'             => "'''Jéegalu! manu noo denc say coppite ngir ñakkug ay njoxe ñeel sag dugg. Di la ñaan nga jéemaat. Su tolof-tolof bi wéyee, Jéemal a génn te duggaat. '''",
'session_fail_preview_html'        => "'''Jéegalu ! manu noo denc say coppite ngir ñakkug ay njoxe ñeel sag dugg.'''

''Segam ci bii dal dañ fee doxal HTML bu ñumm, ngir ay ngirtey kaaraange, wonendi gi du gisu.''

'''Su tolof-tolof bi wéyee, man nga jéem a génn te duggaat .'''",
'token_suffix_mismatch'            => "'''Votre modification n’a pas été acceptée car votre navigateur a mal codé les caractères de ponctuation dans l’identifiant de modification.'''
Ce rejet est nécessaire pour empêcher la corruption du texte de la page.
Ce problème se produit parfois lorsque vous utilisez un serveur mandataire anonyme problématique basé sur le web.",
'editing'                          => 'Coppiteg $1',
'editingsection'                   => 'Coppiteg $1 (xaaj)',
'editingcomment'                   => 'Coppiteg $1 (xaaj bu bees)',
'editconflict'                     => 'jàppanteb coppite ci: $1',
'explainconflict'                  => "Am na beneen jëfandikukat bu soppi xët wi, mu gën a mujj, ci bi ngay soog a door say coppite.
Mbind yi ne ci boyotu coppite bi ci kaw, ñooy yi teew nii ci dàttub njoxe bi, ni ko beneen jëfandikukat bi soppee.
Yaw nag say coppite ñoo nekk ci boyotu coppite bi ci suuf.
Soo nammee denc say coppite, faaw nga dugal leen ci boyot bi ci kaw.
Soo cuqe ci 'Denc xët wi', mbind yi ne ci boyot bi ci kaw rekk ñooy dencu .",
'yourtext'                         => 'Sa mbind',
'storedversion'                    => 'Sumb bees wattu',
'nonunicodebrowser'                => "'''Attention : Votre navigateur ne supporte pas l’unicode. Une solution temporaire a été trouvée pour vous permettre de modifier en tout sûreté un article : les caractères non-ASCII apparaîtront dans votre boîte de modification en tant que codes hexadécimaux. Vous devriez utiliser un navigateur plus récent.'''",
'editingold'                       => "'''Moytul: yaa ngi soppi ab sumb bu yàgg bu xët wii. Soo leen dence, bépp coppite buñu defoon laataa bii sumb, di nañu leen ñakk.'''",
'yourdiff'                         => 'Wuute',
'copyrightwarning'                 => "Bépp cëru ci {{SITENAME}} dañ leen di jàppe niki ay siiwal yoo def te teg leen ci $2 (xoolal $1 ngir yeneeni xamle).
Soo bëggul keneen jël say mbind soppi leen, tas leen teg ci, bu leen fi dugal.<br />
Te it na wóor ne li nga fiy dugal yaa leen moom, yaa leen bind, walla fa nga leen jële gongikuwaay bu ubbeeku la, lu kenn moomul.
'''BUL FI DUGAL LIGGÉEYI KENEEN YU AQI AJI-SOS AAR TE AMOO CI BENN NDIGËL!'''",
'copyrightwarning2'                => "Karmat: Bépp cëru ci {{SITENAME}} yeneen jëfandikukat yi man nañ leen a soppi walla far leen.
Soo bëggul keneen jël say mbind soppi leen, tas leen teg ci, bu leen fi dugal.<br />
Te it na wóor ne li nga fiy dugal yaa leen moom, yaa leen bind, walla fa nga leen jële gongikuwaay bu ubbeeku la, lu kenn moomul (xoolal $1 ngir yeneeni xamle).
'''BUL FI DUGAL LIGGÉEYI KENEEN YU AQI AJI-SOS AAR TE AMOO CI BENN NDIGËL!'''",
'longpageerror'                    => "'''NJUUMTE : mbind mi nga yónne guddee na $1 kio, kon romb na dig bi di $2 kio. Mbind mi maneesu kaa denc.'''",
'readonlywarning'                  => "'''Moytul: dàttub njoxe bi dañu koo caabi ngir ay liggéey,
kon doo man a denc say coppite fi mu nekk nii. Man ngaa duppi mbind mi taf ko cib tëriin bu ñuy binde te taaxirlu ñu ubbi dàttub njoxe bi.'''

Yorkat bi caabi dàttub njoxe bi joxe na yii leeral: $1",
'protectedpagewarning'             => "'''Moytul : wii xët dañ kaa aar.
Jëfandikukat yi nekk yorkat rekk a ko man a soppi.'''",
'semiprotectedpagewarning'         => "'''Karmat :''' wii xët dañ kaa aar ba nga xam ne ñi bindu rekk a ko man a soppi.",
'cascadeprotectedwarning'          => "'''MOYTUL :''' Xët wii dañ kaa aar ba nga xam ne yorkat yi rek ñoo koy man a soppi. Kaaraange googu dañ kaa def ndaxte xët wii dañ kaa dugal ci biir {{PLURAL:$1|aw xët wu ñu aar|ay xët yu ñu aar}}.",
'titleprotectedwarning'            => "'''MOYTUL: wii xët dañ koo aar ba tax laaj na nga am  [[Special:ListGroupRights|yenn sañ-sañ]] yi ngir man koo sos.'''",
'templatesused'                    => '{{PLURAL:$1| Royuwaay bi| Royuwaay yi}} nekk ci wii xët :',
'templatesusedpreview'             => '{{PLURAL:$1| Royuwaay  bi|Royuwaay yi}} nekk ci gii wonendi :',
'templatesusedsection'             => 'Royuwaay yi ne ci bii xaaj:',
'template-protected'               => '(aar)',
'template-semiprotected'           => '(aar-diggu)',
'hiddencategories'                 => '{{PLURAL:$1|wàll bu nëbbu bu|wàll yu nëbbu yu }} xët wii bokk :',
'nocreatetitle'                    => 'Digalu sosteefu xët',
'nocreatetext'                     => 'Jëfandikukat yi bindu rekk a man a sosi xët ci {{SITENAME}}. Man nga dellu ginnaaw walla soppi aw xët wu am ba noppi, [[Special:UserLogin|duggu walla sos am sàq]].',
'nocreate-loggedin'                => 'Amuloo sañ-sañ yu doy ngir man a sosi xët yu bees.',
'permissionserrors'                => 'Njuumte ci sañ-sañ yi',
'permissionserrorstext'            => 'Amuloo sañ-sañu àggali jëf ji nga tambali, ngax {{PLURAL:$1|lii toftal|yii toftal}} :',
'permissionserrorstext-withaction' => 'Amoo sañ-sañu $2, ngir {{PLURAL:$1|lii di toftal |yii di toftal}} :',
'recreate-moveddeleted-warn'       => "'''Moytul: yaa ngi nekk di sosaat aw xët wees faroon.'''

Wóorlul bu baax ndax sosaat xët wi di na doon li gën.
Xoolal yéenekaayu far gi ci suuf.",
'moveddeleted-notice'              => 'Xët wii dañu koo far.
Jaar-jaaru far yeek tuddewaat yi moo ngi ci suuf ngir yeneen xibaar.',
'log-fulllog'                      => 'Wone yéenekaay bu matale',
'edit-hook-aborted'                => 'Dogug coppite gi ak xeet yi.
Li ko waral xameesu ko',
'edit-gone-missing'                => 'Yeesalug xët wi antuwul.
Mel na ne dañu koo far.',
'edit-conflict'                    => 'Jàppante cig coppite.',
'edit-no-change'                   => 'Tankamlu nañu say coppite, ndax defoo benn coppite ci mii mbind.',
'edit-already-exists'              => 'Sosug xët wu bees wi antuwul.
Am na fi ba noppi.',

# Parser/template warnings
'post-expand-template-inclusion-warning'  => 'Moytul: Royuwaay yi ñu dugal ci xët wi dañoo ëpp.
Yenn royuwaay yi duñu man a dugg.',
'post-expand-template-inclusion-category' => 'Xët yu am royuwaay yu ëpp',
'post-expand-template-argument-warning'   => 'Moytul: wii xët daa ëmb ab royuwaay bu dayoom gudd ba ëpp ba tax maneesu koo yokk. Loolu waral ag faram.',
'post-expand-template-argument-category'  => 'Xët yu ëmb ay royuwaay yu seen ëmbit matadi',

# "Undo" feature
'undo-success' => 'Gii coppite man nga kaa neenal. Xoolal méngale gi ne ci suuf ngir wóorlu ne ni ëmbit li mel na ni nga ko bëgge, te nga denc xët wi ngir jeexal.',
'undo-failure' => 'Neenalug coppite gi defuwul: man naa jur ab jàppante ci coppite yi ci diggante bi',
'undo-norev'   => 'Coppite gi manoo koo neenal ndaxte nekkul walla dañu koo far',
'undo-summary' => 'Neenalug coppite $1 yu [[Special:Contributions/$2|$2]] ([[User talk:$2|waxtaan]])',

# Account creation failure
'cantcreateaccounttitle' => 'sag mbindu Manu la nekk .',
'cantcreateaccount-text' => "Sosum sàq mu bàyyikoo ci bii màkkaanu IP ('''$1''') dañ kaa téye [[User:$3|$3]].

Ngirtey téye gi $3 joxe, mooy ne: ''$2''.",

# History pages
'viewpagelogs'           => 'Xool yéenekaayu xët wii',
'nohistory'              => 'Xët wii amulub jaar-jaar.',
'currentrev'             => 'Sumb bi teew',
'currentrev-asof'        => 'Sumb bi teew bu $1',
'revisionasof'           => 'Sumb bu $1',
'revision-info'          => 'Sumb bu $1, bu: $2',
'previousrevision'       => '← Sumb bi jiitu',
'nextrevision'           => 'Sumb bi toftal →',
'currentrevisionlink'    => 'Sumb bi teew',
'cur'                    => 'xamle',
'next'                   => 'toftal',
'last'                   => 'jiitu',
'page_first'             => 'njëkk',
'page_last'              => 'mujj',
'histlegend'             => 'Méngaley sumb: falal sumb yi nga bëgg a méngale te bës ci Ayca walla ci cuquwaay bi ci suuf.

(teew) = li mu wuuteek sumb bi teew, (jii) = li mu wuuteek sumb bi jiitu, <b>c</b> = coppite yu néewal.',
'history-fieldset-title' => 'Joowal ci jaar-jaar gi',
'histfirst'              => 'Cëru yi njëkk',
'histlast'               => 'Cëru yi mujj',
'historysize'            => '({{PLURAL:$1|$1 byte|$1 byte}})',
'historyempty'           => '(këmm)',

# Revision feed
'history-feed-title'          => 'Jaar-jaaru sumb yi',
'history-feed-description'    => 'Jaar-jaaru xët wi ci bii wiki',
'history-feed-item-nocomment' => '$1 ci $2',
'history-feed-empty'          => 'Xët wi nga laaj amul. Xej-na dañ koo dindi ci dal bi walla ñu tuddewaat ko. Man nga jéem a [[Special:Search|seet ci wiki bi]] ndax ay xët yu bees am nañ fi.',

# Revision deletion
'rev-deleted-comment'         => '(sanni-kàddu bi far nañ ko)',
'rev-deleted-user'            => '(turu jëfandikukat bi far nañ ko)',
'rev-deleted-event'           => '(duggit li far nañ ko)',
'rev-deleted-text-permission' => "Sumb bu xët wii dañ koo '''far'''. Xoolal [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} jaar-jaaru farte] yi ngir yeneeni xibaar.",
'rev-deleted-text-unhide'     => "Sumb bu xët wii dañ koo '''far'''.
Man nga am yeneeni xamle ci [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} jaar-jaaru far] yi.
Li nga doon yorkat tax na nga man a  [$1 saytu bii sumb] su la neexee.",
'rev-suppressed-text-unhide'  => "Sumb bu xët wii dees koo '''far'''.
Saytul [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} jaar-jaaru far] yi ngir yeneeni xamle.
Li nga doon saytukat tax na nga man a [$1 gis bii sumb] su la neexee.",
'rev-deleted-text-view'       => "Bii sumb bu xët wii dañ koo '''far'''.
Li nga doon yorkat moo tax nga man gis mbind mi. Saytul [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} jaar-jaaru far] yi ngir yeneeni xibaar.",
'rev-suppressed-text-view'    => "Bii sumb bu wii xët dees koo '''faroon'''.
Saytukat yi man nañu koo gis ba-tay; saytul  [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} jaar-jaaru far] yi ngir yenneni xamle.",
'rev-deleted-no-diff'         => "Manoo wone gii méngale  ndax benn ci sumb yi dañu koo '''far'''.
Man ngaa ami xibaar ci [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} jaar-jaaru far] yi.",
'rev-deleted-unhide-diff'     => "Benn ci sumb yi ngay méngale dees koo '''far'''.
Man ngaa ami xamle ci [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} jaar-jaaru far] yi.
Li nga doon yorkat tax na ba-tay nga man a [$1 gis gii wuute] su la neexee.",
'rev-delundel'                => 'wone/nëbb',
'revisiondelete'              => 'Far/Deloosiwaati sumb',
'revdelete-nooldid-title'     => 'Waxoo ban sumb',
'revdelete-nooldid-text'      => 'Waxoo ci ban sumb bu xët wii ngay amal solo sii.',
'revdelete-nologtype-title'   => 'Joxewoo benn xeetu yéenekaay',
'revdelete-nologtype-text'    => 'Waxoo ci ban xeetu yéenekaay ngay amal jëf jii.',
'revdelete-nologid-title'     => 'Duggiitu yéenekaay bi baaxul',
'revdelete-no-file'           => 'Dencukaay bi amul',
'revdelete-show-file-confirm' => 'Ndax wóor na ne bëgg ngaa wone sumb bees far bu bii dencukaay "<nowiki>$1</nowiki>" bu $2 ci $3?',
'revdelete-show-file-submit'  => 'Waaw',
'revdelete-selected'          => "'''{{PLURAL:$2|Sumbum '''$1''' mi falu|Sumbi '''$1''' yi falu}} :'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Xew-xewu yéenekaay bi falu|Xew-xewi yéenekaay yi falu}}:'''",
'revdelete-text'              => "'''Sumb yi nga far dañuy wéy di feeñ ci jaar-jaaru xët wi, waaye mbind yi ñu ëmb ñépp duñ leen man a gis.'''
Yeneen yorkat yu {{SITENAME}} di nañ man a gis ëmbit yu laqu yi te loppanti leen ci benn jokkalekaay bi, su fekkee defuñu fi ay digal yu leen koy tere man a def.",
'revdelete-legend'            => 'Taxawal ay digal ci sumb yi ñu far:',
'revdelete-hide-text'         => 'Nëbb mbindum sumb bi',
'revdelete-hide-image'        => 'Nëbb ëmbiitu dencukaay bi',
'revdelete-hide-name'         => 'Nëbb jëf ji ak njeexitam',
'revdelete-hide-comment'      => 'Nëbb saraay coppite gi',
'revdelete-hide-user'         => 'Nëbb tur walla màkkaanu IP bu soppikat bi',
'revdelete-hide-restricted'   => 'Nëbb yii xibaar yorkat yi itam',
'revdelete-suppress'          => 'Nëbb xibaar yi yorkat yi tamit.',
'revdelete-unsuppress'        => 'Far digal yi ci sumb yees delloowaat',
'revdelete-log'               => 'Ngirte :',
'revdelete-submit'            => 'Def ko ci sumb bi falu',
'revdelete-logentry'          => 'Gisub sumb bi soppiku na ngir [[$1]]',
'logdelete-logentry'          => 'Gisub xew-xew bii [[$1]] dañ kaa soppi',
'revdelete-success'           => "'''Coppiteg gisub sumb bi, baax na.'''",
'revdelete-failure'           => "'''Gisub sumb bi manoo koo soppi'''
$1",
'logdelete-success'           => "'''Gisub xew-xew bi soppiku na bu baax.'''",
'logdelete-failure'           => "'''Gisub yéenekaay bi manoo koo soppi'''
$1",
'revdel-restore'              => 'Soppi gis bi',
'pagehist'                    => 'Jaar-jaaru xët wi',
'deletedhist'                 => 'Jaar-jaaru far gi',
'revdelete-content'           => 'ëmbiit',
'revdelete-summary'           => 'soppi tënk gi',
'revdelete-uname'             => 'turu jëfandikukat',
'revdelete-restricted'        => 'doxalub digal ngir yorkat yi',
'revdelete-unrestricted'      => 'digal ngir yorkat yi deñ na',
'revdelete-hid'               => 'nëbb $1',
'revdelete-unhid'             => 'wone $1',
'revdelete-log-message'       => '$1 ngir $2 {{PLURAL:$2|sumb|sumb}}',
'logdelete-log-message'       => '$1 ngir $2 {{PLURAL:$2|xew-xew|xew-xew}}',
'revdelete-hide-current'      => 'Njuumte ci nëbbug cër bi taarixam doon $2, $1: mooy sumb bi teew.
Maneesu koo nëbb.',
'revdelete-show-no-access'    => "Njuumte ci woneg cër bi taarixam doon $2, $1: dees koo jàppe niki lees ''digal''.
Manoo cee jot.",
'revdelete-modify-no-access'  => "Njuumte ci coppiteg cër bi taariixam doon $2, $1: dees koo jàppe niki lees ''digal''.
Manoo cee dugg.",
'revdelete-modify-missing'    => 'Njuumte ci coppiteg cër bi xammekaayam doon $1: nekkatul ci dàttub njoxe bi!',
'revdelete-no-change'         => "'''Moytul:''' cër bi taarixam doon $2, $1 laajoon nañu ba noppi anami gisam.",
'revdelete-concurrent-change' => 'Njuumte ci coppiteg cër bi taarixam doon $2, $1: beneen jëfandikukat moo soppi nekkiinam ci jamono ji nga koy jéem a soppi.
Xoolal jaar-jaar yi.',
'revdelete-only-restricted'   => 'Manoo fare yii cër ci gisu yorkat yi te tànnoo lu bon-bon benn ci yeneen tànneefi far yi.',
'revdelete-edit-reasonlist'   => 'Soppi ngirtey far gi',

# Suppression log
'suppressionlog'     => 'Yéenekaayu far yi',
'suppressionlogtext' => 'Ci suuf, di nga fi gis limu far yeek téye yeek coppite yees nëbb yorkat yi. Xoolal
[[Special:IPBlockList|limu IP yees téye]] ngir gis IP yees téye nii-nii.',

# History merging
'mergehistory'                     => 'Booleb jaar-jaar yu aw xët',
'mergehistory-header'              => 'Wii xët day tax nga man a boole sumb yépp yi ne ci jaar-jaaruw xët (di ko wax it xëtu gongikuwaay) ak jaar-jaaru weneen xët wu mujj.
wóorluwul ne coppite gi du yaq jaar-jaaru xët wi.',
'mergehistory-box'                 => 'Boole sumbi ñaari xët:',
'mergehistory-from'                => 'Xëtu gongikuwaay :',
'mergehistory-into'                => 'Xëtu jëmuwaay :',
'mergehistory-list'                => 'Jaar-jaar yi boolewu',
'mergehistory-merge'               => 'Sumb yii di toftal yu [[:$1]] man nañ leen boole ak yu [[:$2]]. Jëfandikul boyotu tànneef yi ci wet gi ngir boole sumb yépp ba taariix ak waxtu bi nga wax.

Soo jëfandikoo lëkkalekaayu joow yi day neenal boyot yi nga jotoon a fal.',
'mergehistory-go'                  => 'Wone coppite yi boolewu',
'mergehistory-submit'              => 'Boole jagal yi',
'mergehistory-empty'               => 'Manuloo boole menn sumb.',
'mergehistory-success'             => '$3 {{PLURAL:$3|sumb|sumb}} mu [[:$1]] boole {{PLURAL:$3|nañ ko|nañ leen}} ci jàmm ak [[:$2]].',
'mergehistory-fail'                => 'Booleb jaar-jaar yi antuwul. Falaatal xët wi ak taariix yi',
'mergehistory-no-source'           => 'Xëtu gongikuwaay bii $1 amul.',
'mergehistory-no-destination'      => 'Xëtu jëmuwaay bii $1 amul.',
'mergehistory-invalid-source'      => 'Xëtu gongikuwaay bi daa war a am koj bu baax.',
'mergehistory-invalid-destination' => 'Xëtu jëmuwaay bi daa war a am koj bu baax.',
'mergehistory-autocomment'         => 'Booleb [[:$1]] ak [[:$2]]',
'mergehistory-comment'             => 'Booleb [[:$1]] ak [[:$2]] : $3',
'mergehistory-same-destination'    => 'Gongikuwaay ak jëmuwaayu xët yi waruñoo bokk',
'mergehistory-reason'              => 'Ngirte :',

# Merge log
'mergelog'           => 'Yéenekaayu boole yi',
'pagemerge-logentry' => 'Booleb [[$1]] ak [[$2]] (sumb ba $3)',
'revertmerge'        => 'Neenal boole yi',
'mergelogpagetext'   => 'Lii ci suuf ab lim la ci boole yu mujj yu jaar-jaaru aw xët ak weneen .',

# Diffs
'history-title'           => 'Jaar-jaaru sumbi « $1 »',
'difference'              => '(Wuute gi ci sumb yi)',
'lineno'                  => 'Rëdd $1 :',
'compareselectedversions' => 'Méngale sumb yi nga fal',
'editundo'                => 'neenal',
'diff-multi'              => '({{PLURAL:$1|am sumb mu diggu feeñul|$1 sumb yu diggu feeñuñu}}.)',

# Search results
'searchresults'                    => 'Ngértey ceet gi',
'searchresults-title'              => 'Ngértey ceet gu "$1"',
'searchresulttext'                 => 'Ngir yeneeni xibaar ci ceet gi ci {{SITENAME}}, xoolal [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'Yaa ngi seet « \'\'\'[[:$1]]\'\'\' » ([[Special:Prefixindex/$1|wépp xët wu tambalee "$1"]]{{int:pipe-separator}}
[[Special:WhatLinksHere/$1|wépp xët wu lëkkalook "$1"]])',
'searchsubtitleinvalid'            => 'Yaa ngi seet « $1 »',
'toomanymatches'                   => 'Dafa bari ay yem-yem. Soppil laaj bi.',
'titlematches'                     => 'Koju xët yi ñoo yam',
'notitlematches'                   => 'Amul benn koju xët wu yam ak ceet gi',
'textmatches'                      => 'Mbindu jukki yi ñoo yam.',
'notextmatches'                    => 'Amul benn mbindu jukki bu yam ak ceet gi.',
'prevn'                            => '{{PLURAL:$1|wi jiitu|$1 yi jiitu}}',
'nextn'                            => '{{PLURAL:$1|$1}} yi toftal',
'prevn-title'                      => '$1 {{PLURAL:$1|ngérte bi jiitu t|ngérte yi jiitu}}',
'nextn-title'                      => '$1 {{PLURAL:$1|ngérte bi toftal|ngérte yi toftal}}',
'shown-title'                      => 'Wone $1 {{PLURAL:$1|ngérte|ngérte}} ciw xët',
'viewprevnext'                     => 'Xool ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => 'Tànneefi ceet',
'searchmenu-exists'                => "'''wenn xët wu tudd « [[:$1]] » moo am ci bii wiki'''",
'searchmenu-new'                   => "'''Sosal xët wii di « [[:$1|$1]] » ci bii wiki !'''",
'searchhelp-url'                   => 'Help:Ndimbal',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Xoolal xët wi tambalee nii]]',
'searchprofile-articles'           => 'Xëti ëmbiit',
'searchprofile-project'            => 'Xëti Ndimbal ak Sémb',
'searchprofile-images'             => 'Barixibaarukaay',
'searchprofile-everything'         => 'Lépp',
'searchprofile-advanced'           => 'Ceet gu xóot',
'searchprofile-articles-tooltip'   => 'Seet ci $1',
'searchprofile-project-tooltip'    => 'Seet ci $1',
'searchprofile-images-tooltip'     => 'Seeti dencukaay',
'searchprofile-everything-tooltip' => 'Seet fépp (ba ci xëti waxtaanuwaay yi)',
'searchprofile-advanced-tooltip'   => 'Seet ci barabi tur yi',
'search-result-size'               => '$1 ({{PLURAL:$2|1 baat|$2 baat}})',
'search-result-score'              => 'Baaxaay: $1%',
'search-redirect'                  => '(jubluwaat bu jëm $1)',
'search-section'                   => '(xaaj $1)',
'search-suggest'                   => 'Xéj-na lii nga doon seet: $1',
'search-interwiki-caption'         => 'Sémbu niroowaale',
'search-interwiki-default'         => '$1 ngérte :',
'search-interwiki-more'            => '(yeneen)',
'search-mwsuggest-enabled'         => 'ak xelal',
'search-mwsuggest-disabled'        => 'ci lu dul xelal',
'search-relatedarticle'            => 'Yeneeni ngérte',
'mwsuggest-disable'                => 'Doxadil xelal yu AJAX',
'searcheverything-enable'          => 'Seet ci barabi tur yépp',
'searchrelated'                    => 'yeneeni ngérte',
'searchall'                        => 'yépp',
'showingresults'                   => 'Woneg <b>$1</b> {{PLURAL:$1|ngérte|ciy ngérte}} doore ko ci #<b>$2</b>.',
'showingresultsnum'                => 'Woneg <b>$3</b> {{PLURAL:$3|ngérte|ciy ngérte}} doore ko ci #<b>$2</b>.',
'showingresultsheader'             => "{{PLURAL:$5|Ngérte '''$1''' bu '''$3'''|Ngértey '''$1 - $2'''}}  ngir '''$4'''",
'nonefound'                        => "<strong>Karmat</strong> : ci yenn barabi tur yi rekk lañuy seet cig tëralnjëkk.
Jéemala bindaale ''all'' ngir seet ci biir ëmbit gépp (boolewaale ci xëti waxtaanuwaay yi, royuwaay yi, añs), walla nga jëfandikoo barabu tur bi la neek",
'search-nonefound'                 => 'Ceet gi jurul dara.',
'powersearch'                      => 'Seet',
'powersearch-legend'               => 'Ceet gu xóot',
'powersearch-ns'                   => 'Seet ci barabi tur yi :',
'powersearch-redir'                => 'Limu jubluwaat yi',
'powersearch-field'                => 'Seet',
'powersearch-togglelabel'          => 'Fal:',
'powersearch-toggleall'            => 'Yépp',
'powersearch-togglenone'           => 'Dara',
'search-external'                  => 'Ceet gu biti',
'searchdisabled'                   => 'Ceet gi ci {{SITENAME}} doxul. Ci négandiku doxal gi, man nga seet ci Google. Jàppal ne, xéj-na ëmbiti {{SITENAME}} gi ci bii seetukaay yeesaluñ leen.',

# Quickbar
'qbsettings'               => 'Banqaasu jumtukaay',
'qbsettings-none'          => 'Kenn',
'qbsettings-fixedleft'     => 'Cammooñ',
'qbsettings-fixedright'    => 'Ndijoor',
'qbsettings-floatingleft'  => 'Ci cammooñ',
'qbsettings-floatingright' => 'Ci ndijoor',

# Preferences page
'preferences'                   => 'Tànneef',
'mypreferences'                 => 'Samay tànneef',
'prefs-edits'                   => 'Limu coppite yi:',
'prefsnologin'                  => 'Duggoo',
'prefsnologintext'              => 'Laaj na nga <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} dugg]</span> ngir soppi say tànneef.',
'changepassword'                => 'Coppiteg baatujàll bi',
'prefs-skin'                    => 'Melokaan',
'skin-preview'                  => 'Wonendil',
'prefs-math'                    => 'Xayma',
'datedefault'                   => 'Benn tànneef',
'prefs-datetime'                => 'Taariix ak waxtu',
'prefs-personal'                => 'Xibaar yu la ñeel',
'prefs-rc'                      => 'Coppite yu mujj',
'prefs-watchlist'               => 'Limu toppte',
'prefs-watchlist-days'          => 'Limu bes yi nga koy ba ci sa limu toppte :',
'prefs-watchlist-days-max'      => 'Du weesu 7 fan',
'prefs-watchlist-edits'         => 'Limu coppite yi ngay wone ci limu toppte gees yaatal gi:',
'prefs-misc'                    => 'Yeneeni tànneef',
'prefs-resetpass'               => 'Soppi baatujàll',
'prefs-email'                   => 'Tànneefi m-bataaxal',
'saveprefs'                     => 'Wattu',
'resetprefs'                    => 'Loppanti tànneef yi',
'restoreprefs'                  => 'Delloo say tànneef nañu meloon ca sag mbindu',
'prefs-editing'                 => 'Boyotu coppite',
'prefs-edit-boxsize'            => 'Dayoo palanteeru coppite bi.',
'rows'                          => 'Rëdd:',
'columns'                       => 'Kenu :',
'searchresultshead'             => 'Seet',
'resultsperpage'                => 'Limu ngérte ci xët wu ne :',
'contextlines'                  => 'Limu rëdd ci tont wu ne :',
'recentchangesdays'             => 'Limu bes yi nga koy wone ci coppite yu mujj yi :',
'recentchangesdays-max'         => '(bi ëpp $1 {{PLURAL:$1|bés|bés}})',
'recentchangescount'            => 'Limu coppite yi ngay wone ci coppite yu mujj yi cig wàccaale:',
'prefs-help-recentchangescount' => 'Dafa ëmb coppite yu mujj, xëti jaar-jaar yeek yéenekaay.',
'savedprefs'                    => 'Say tànneef wattu nañu leen.',
'timezonelegend'                => 'Waxtuw barab:',
'localtime'                     => 'Waxtuw barab:',
'timezoneuseserverdefault'      => 'Jëfandikoo waxtuw joxekaay bi',
'timezoneuseoffset'             => 'Yeneen (waxaaleel wuute gi)',
'timezoneoffset'                => 'Wuute¹ :',
'servertime'                    => 'Waxtu joxekaay bi :',
'guesstimezone'                 => 'Jëfandikoo waxtuw sa joowukaay',
'timezoneregion-africa'         => 'Afrig',
'timezoneregion-america'        => 'Aamerig',
'timezoneregion-antarctica'     => 'Goxub Dottub bëj-saalum',
'timezoneregion-arctic'         => 'Goxub Dottub bëj-gànnaar',
'timezoneregion-asia'           => 'Asi',
'timezoneregion-atlantic'       => 'Mbàmbulaan gu Atlas',
'timezoneregion-australia'      => 'Óstraali',
'timezoneregion-europe'         => 'Tugal',
'timezoneregion-indian'         => 'Mbàmbulaan gu End',
'timezoneregion-pacific'        => 'Mbàmbulaan gu Dal',
'allowemail'                    => 'Joxeel ndigël ngir  yeneeni jëfandikukat man laa yónne bataaxal',
'prefs-searchoptions'           => 'Tànneefi Ceet',
'prefs-namespaces'              => 'dig-digalub tur',
'defaultns'                     => 'Walla nga seet ci barabi tur yi:',
'default'                       => 'wàccaale',
'prefs-files'                   => 'Dencukaay',
'prefs-custom-css'              => 'CSS yees jëmmal',
'prefs-custom-js'               => 'JavaScript yees jëmmal',
'prefs-reset-intro'             => 'Man ngaa jëfandikoo wii xët ngir delloowaat coppitey say tànneef delloo leen ci yi dal bi wàccaale. Doo ko man a neenal.',
'prefs-emailconfirm-label'      => 'Dëggalug sa m-bataaxal :',
'prefs-textboxsize'             => 'Dayoob palanteerub coppite bi',
'youremail'                     => 'Sa màkkaanub m-bataaxal :',
'username'                      => 'Turu jëfandikukat :',
'uid'                           => 'Limu Jëfandikukat :',
'prefs-memberingroups'          => 'Céru {{PLURAL:$1|mbooloo|mbooloo yu}} :',
'prefs-registration'            => 'Taariixu sag mbindu :',
'yourrealname'                  => 'Sa tur dëgg*',
'yourlanguage'                  => 'Làkk :',
'yournick'                      => 'Xaatim ngir say waxtaan :',
'badsig'                        => 'Xaatim gu ñumm gi baaxul; saytul sa yoonub HTML.',
'badsiglength'                  => 'Sa xaatim daa gudd lool, guddaay bi warul romb {{PLURAL:màndarga|$1 màndarga}}.',
'gender-unknown'                => 'Waxeesu ko',
'gender-male'                   => 'Góor',
'gender-female'                 => 'Jigéen',
'email'                         => 'Màkkaanub m-bataaxal',
'prefs-help-realname'           => 'Sa tur dëgg du lu manuta ñakk: soo ko ci bëgge duggal it dañ koy jëfandikoo rek ngir moomale la say cëru.',
'prefs-help-email'              => 'Sa màkkaanub m-bataaxal du lu manuta ñakk: day tax rek ñu man laa yónne ab bataaxal jaare ko ci sa xëtu jëfandikukat walla yónne la baatujàll bu bees soo ko fattee, te du tax sa màkkaan gisu.',
'prefs-help-email-required'     => 'Laaj na ab màkkaanub m-bataaxal',
'prefs-info'                    => 'Xibaaari dàtt',
'prefs-i18n'                    => 'Digganteréewal',
'prefs-signature'               => 'Xaatim',
'prefs-dateformat'              => 'Melokaanu taariix',
'prefs-timeoffset'              => 'Wuutey waxtu',
'prefs-advancedediting'         => 'Tànneef yu xóot',
'prefs-advancedrc'              => 'Tànneef yu xóot',
'prefs-advancedrendering'       => 'Tànneef yu xóot',
'prefs-advancedsearchoptions'   => 'Tànneef yu xóot',
'prefs-advancedwatchlist'       => 'Tànneef yu xóot',
'prefs-displayrc'               => 'Tànneefi wonewiin',
'prefs-diffs'                   => 'Wuute',

# User rights
'userrights'                     => 'Yorug sañ-sañu aji-sos',
'userrights-lookup-user'         => 'Yorug mboolooy jëfandikukat',
'userrights-user-editname'       => 'Dugal ab turu jëfandikukat :',
'editusergroup'                  => 'Coppiteg mboolooy Jëfandikukat',
'editinguser'                    => "Coppiteg sañ-sañi jëfandikukat bii di '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]] | [[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'       => 'Soppi mboolooy jëfandikukat',
'saveusergroups'                 => 'Denc mboolooy jëfandikukat yi',
'userrights-groupsmember'        => 'Cëru mbooloo mu:',
'userrights-groups-help'         => 'Man ngaa soppi mbooloo yi jëfandikukat bi bokk
*Boyot bu ci falu day wone ne jëfandikukat bi bokk na ci
*Boyot bu ci faluwul day wone ne jëfandikukat bi bokku ci
*Ab * day wone ne manoo koo cee jëlee gannaaw boo ko ci boole, walla safaan wa.',
'userrights-reason'              => 'Ngirte :',
'userrights-no-interwiki'        => 'Amoo sañ-sañu soppi sañ-sañi jëfandikukat yi ci yeneen wiki',
'userrights-nodatabase'          => 'Dàttub njoxe bii « $1 » amul walla nekkul ci barab bi.',
'userrights-nologin'             => 'Faaw nga  [[Special:UserLogin|dugg]] ak sàqum ab yorkat ngir man a soppi sañ-sañi ab jëfandukukat.',
'userrights-notallowed'          => 'Sa sàq amul sañ-sañ ngir man a joxi sañ-sañ ab jëfandikukat.',
'userrights-changeable-col'      => 'Mbooloo yees man a soppi',
'userrights-unchangeable-col'    => 'Mbooloo yees manuta soppi',
'userrights-irreversible-marker' => '$1*',

# Groups
'group'               => 'Mbooloo :',
'group-user'          => 'Jëfandikukat',
'group-autoconfirmed' => 'Jëfandikukat bu bindu',
'group-bot'           => 'Bot',
'group-sysop'         => 'Yorkat',
'group-suppress'      => 'Saytukat',
'group-all'           => 'Yépp',

'group-user-member'          => 'Jëfandikukat',
'group-autoconfirmed-member' => 'Jëfandikukat bu bindu',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Yorkat',
'group-suppress-member'      => 'Saytukat',

'grouppage-user'          => '{{ns:project}}:Jëfandikukat',
'grouppage-autoconfirmed' => '{{ns:project}}:Jëfandikukat bu bindu',
'grouppage-bot'           => '{{ns:project}}:Bot',
'grouppage-sysop'         => '{{ns:project}}:Yorkat',
'grouppage-suppress'      => '{{ns:project}}:Saytukat',

# Rights
'right-read'                  => 'Saytu xët yi',
'right-edit'                  => 'Soppi xët yi',
'right-createpage'            => 'Sos ay xët (yu doonul xëti diisoowaay)',
'right-createtalk'            => 'Sos xëti diisoowaay',
'right-createaccount'         => 'Sos sàqi jëfandikukat yu yees',
'right-minoredit'             => 'Fésal coppite yi niki yu néewal',
'right-move'                  => 'Tuddewaat ay xët',
'right-move-subpages'         => 'Tuddewaat ay xët ak seeni ron-xët',
'right-move-rootuserpages'    => 'Tuddewaat xëti njalbeen yu ay jëfandikukat.',
'right-movefile'              => 'Tuddewaat ay dencukaay',
'right-suppressredirect'      => 'Bul sos ab jubluwaat suñu tuddewaatee aw xët',
'right-upload'                => 'Yebi dencukaay',
'right-reupload'              => 'War dencukaay bu fi amoon',
'right-reupload-own'          => 'War dencukaay bu fi amoon, boo yebal sa bopp',
'right-reupload-shared'       => 'War ci barab bi dencukaay bu ne cib dencu bees bokk',
'right-upload_by_url'         => 'Yeb ab dencukaay jëlee ko cib màkkaanub URL',
'right-purge'                 => 'Raxas nëbbiitu dal bi te du jar dëggal',
'right-autoconfirmed'         => 'Soppi xët yees aar-digg',
'right-bot'                   => 'Jàppee ko niki jëfiin ju boppu',
'right-nominornewtalk'        => 'Bu ñu yónne bataaxal jëfandikukat bi su ñu amalee coppite yu néewal ci xëtu diisoowaayam',
'right-apihighlimits'         => 'Jëfandikoo dig yu kawe ci laaji APi yi',
'right-writeapi'              => 'Jëfandikoo API bi cig mbind',
'right-delete'                => 'Far ay xët',
'right-bigdelete'             => 'Far ay xët yu seen jaar-jaar bari',
'right-deleterevision'        => 'Far walla delloosiwaat yenn sumb yu ay xët',
'right-deletedhistory'        => 'Wone duggiitu jaar-jaar jees far, bu ci seen mbind bokkaale',
'right-browsearchive'         => 'Seet xët wees far',
'right-undelete'              => 'Delloosi aw xët',
'right-suppressrevision'      => 'Xoolaat te delloosiwaat sumb yees nëbb yorkat yi',
'right-suppressionlog'        => 'Xool yéenekaay yi kenn moom',
'right-block'                 => 'Téye yeneen jëfandikukat ci mbind mi',
'right-blockemail'            => 'Tere ab jëfandikukat yónney m-bataaxal',
'right-hideuser'              => 'Téye ab jëfandikukat, nëbbaale ko mbooloo mi',
'right-ipblock-exempt'        => 'Jéggi IP yees téye, téye yu boppu ak téyey tooli IP',
'right-proxyunbannable'       => 'Jéggi téye yu proxy yi',
'right-protect'               => 'Soppi tolluwaayu kaaraange gi te soppi xët yees aar',
'right-editprotected'         => 'Soppi xët yees aar',
'right-editinterface'         => 'Soppi jokkalekaayu jëfandikukat bi',
'right-editusercssjs'         => 'Soppi dencukaay yu CSS ak  JS yu yeneen jëfandikukat',
'right-editusercss'           => 'Soppi dencukaay yu CSS yu yeneen jëfandikukat',
'right-edituserjs'            => 'Soppi dencukaay yu JS yu yeneen jëfandikukat',
'right-rollback'              => 'Loppanti gu gaaw gu coppite yub jëfandikukat bu mujj ci wenn xët wi',
'right-markbotedits'          => 'Fésal coppite yi niki yu ab bot def',
'right-noratelimit'           => 'Bu digal ci jëf am njeexiit ci yaw',
'right-import'                => 'Jéggaani ay xët ci yeneen wiki',
'right-importupload'          => 'Jéggaani ay xët cib dencukaay bees yeb',
'right-patrol'                => 'Fésal coppitey ñeneen ñi niki yees fuglu',
'right-autopatrol'            => 'Am say coppite ñu fésal seen bopp niki yees fuglu',
'right-patrolmarks'           => 'Wone fésal gi tukke ci fuglug coppite yu mujj',
'right-unwatchedpages'        => 'Wone limu xët yees toppul',
'right-mergehistory'          => 'Boole jaar-jaaru xët yi',
'right-userrights'            => 'Soppi bépp sañ-sañu jëfandikukat bi',
'right-userrights-interwiki'  => 'Soppi sañ-sañi jëfandikukat yu yeneen wiki',
'right-siteadmin'             => 'Caabi walla caabeedi dàttub njoxe bi',
'right-reset-passwords'       => 'Soppi baatujàllub ay jëfandikukat',
'right-override-export-depth' => 'génne ay xët yu ëmbi xët yu lëkkaloo ba-ci xóotaay bu tolluwaayam àgg 5',

# User rights log
'rightslog'      => 'Sañ-sañi jëfandikukat',
'rightslogtext'  => 'Lii di toftal limu jaar-jaaru coppitey sañ-sañi jëfandikukat yi.',
'rightslogentry' => 'moo jële jëfandikukat bii di « $1 » ci mbooloo  $2  yóbb ko ci $3',
'rightsnone'     => '(menn)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'jàng wii xët',
'action-edit'                 => 'soppi xët wii',
'action-createpage'           => 'Sosi xët',
'action-createtalk'           => 'sos xëti waxtaanuwaay',
'action-createaccount'        => 'sos mii sàqum jëfandikukat',
'action-minoredit'            => 'fésal gii coppite niki gu néewal',
'action-move'                 => 'tuddewaat wii xët',
'action-move-subpages'        => 'tuddewaat wii xët aki ron-xëtam',
'action-move-rootuserpages'   => 'tuddewaat xët wu njëkk wu ab jëfandikukat',
'action-movefile'             => 'tuddewaat bii dencukaay',
'action-upload'               => 'yeb bii dencukaay',
'action-reupload'             => 'war dencukaay bi fi nekkoon',
'action-reupload-shared'      => 'war dencukaay bi nekk cib dencu bu ñu bokk',
'action-upload_by_url'        => 'yeb dencukaay bii jëlee ko cib màkkaanub URL',
'action-writeapi'             => 'jëfandikoo API bi cig mbind',
'action-delete'               => 'far wii xët',
'action-deleterevision'       => 'far bii sumb',
'action-deletedhistory'       => 'wone jaar-jaar bi ñu far bu wii wët',
'action-browsearchive'        => 'seet xët yu ñu far',
'action-undelete'             => 'loppanti wii xët',
'action-suppressrevision'     => 'wone te loppanti sumb yi nëbbu',
'action-block'                => 'téye bii jëfandikukat ci mbind mi',
'action-protect'              => 'soppi tolluwaayu kaaraangeg wii xët',
'action-import'               => 'jëli wii xët ci beneen wiki',
'action-importupload'         => 'jëli wii xët ci ŋara wu ñu yeb',
'action-patrol'               => 'fésal coppite yu yeneen jëfandikukat yi niki yu ñu saytu',
'action-autopatrol'           => 'fésal say coppite niki yoo saytu',
'action-unwatchedpages'       => 'wone limu xët yi kenn toppul',
'action-mergehistory'         => 'boole jaar-jaaru wii xët',
'action-userrights'           => 'soppi aqi jëfanfikukat yépp',
'action-userrights-interwiki' => 'soppi aqi jëfandikukat yu jëfandikukat yu beneen wiki',
'action-siteadmin'            => 'caabi walla caabeedi dàttub njoxe bi',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|coppite|ciy coppite}}',
'recentchanges'                     => 'Coppite yu mujj',
'recentchanges-legend'              => 'tànneefi coppite yu mujj',
'recentchangestext'                 => 'Toppal ci wii xët coppite yu mujj ci {{SITENAME}}.',
'recentchanges-feed-description'    => 'Toppal coppite yu mujj yu bii wiki.',
'recentchanges-label-newpage'       => 'Coppite gi sos na xët wu bees',
'recentchanges-label-minor'         => 'Coppite gu néewal la',
'recentchanges-label-bot'           => 'Coppite bu ab bot def',
'recentchanges-label-unpatrolled'   => 'Coppite bii kenn fugloogu ko',
'rcnote'                            => '{{PLURAL:$1|Lii mooy coppite bu mujj bees def|Yii ñooy coppite yu mujj yees def}} ci {{PLURAL:$2|bés bu mujj bi|<b>$2</b> bés yu mujj yi}}; njoxe yi ñoo ngi leen yeesal $5 ci $4.',
'rcnotefrom'                        => "Yii ñooy coppite yi dalee '''$2''' (ba '''$1''').",
'rclistfrom'                        => 'Wone coppite yi mujj yi dooree $1.',
'rcshowhideminor'                   => '$1 Coppite yu néewal',
'rcshowhidebots'                    => '$1 bot yi',
'rcshowhideliu'                     => '$1 jëfandikukat yu bindu',
'rcshowhideanons'                   => '$1 jëfandikukat yu binduwul',
'rcshowhidepatr'                    => '$1 coppite bees fuglu',
'rcshowhidemine'                    => '$1 samay cëru',
'rclinks'                           => 'Wone $1 coppite yi mujj ci $2  fan yi mujj <br />$3.',
'diff'                              => 'wuute',
'hist'                              => 'Jaar',
'hide'                              => 'Nëbb',
'show'                              => 'Wone',
'minoreditletter'                   => 'm',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[{{PLURAL:$1|jëfandikukat moo koy topp|$1 jëfandikukat ñoo koy topp}}]',
'rc_categories'                     => 'Digalub wàll yi (xaajale leen ak « "|" »)',
'rc_categories_any'                 => 'Yépp',
'rc-change-size'                    => '$1',
'newsectionsummary'                 => '/* $1 */ xaaj bu bees',
'rc-enhanced-expand'                => 'Xool faramfacce yi (laaj na JavaScript)',
'rc-enhanced-hide'                  => 'nëbb faramfacce yi',

# Recent changes linked
'recentchangeslinked'          => 'Coppite yi ko ñeel',
'recentchangeslinked-feed'     => 'Coppite yi ko ñeel',
'recentchangeslinked-toolbox'  => 'Coppite yi ko ñeel',
'recentchangeslinked-title'    => 'Coppite yi ñeel $1',
'recentchangeslinked-backlink' => '← $1',
'recentchangeslinked-noresult' => 'Benn coppite amul ci xët yi mu lëkkalool ci diir bi nga wax.',
'recentchangeslinked-summary'  => "Wii xëtu jagleel moo lay won coppite yu mujj ci xët yi lëkkalook wii. Xët yi ci sa [[Special:Watchlist|limu toppte]] ñoo '''duuf'''.",
'recentchangeslinked-page'     => 'Turu xët wi :',
'recentchangeslinked-to'       => 'Wone coppite yi ñeel xët yi lëkkalook xët wi nga joxe',

# Upload
'upload'                      => 'Yeb ab dencukaay',
'uploadbtn'                   => 'Yeb ŋara wi',
'uploadnologin'               => 'Duggoo',
'uploadnologintext'           => 'Faaw nga [[Special:UserLogin|dugg]] ngir man a yebi ŋara.',
'upload_directory_missing'    => 'Wayndareb yeb bu ($1) nekku fi te joxekaayub web bi manu koo sos.',
'upload_directory_read_only'  => 'Joxekaayub web bi manuta bind ci wayndareb yeb bu ($1).',
'uploaderror'                 => 'Njuumte ci yeb gi',
'upload-permitted'            => 'Xeeti ŋara yiñ fi nangu : $1.',
'upload-preferred'            => 'Xeeti ŋara yiñ fi taamu : $1.',
'upload-prohibited'           => 'Xeeti ŋara yiñ fi tere : $1.',
'uploadlog'                   => 'Dencukaay yiñ fi yeb',
'uploadlogpage'               => 'Dencukaay yiñ fi yeb',
'uploadlogpagetext'           => 'Liy toftal limu dencukaay yiñ fi mujje yeb la.
Saytul [[Special:NewFiles| gaaraluwaayu dencukaay yu yees yi]] ngir gis bu gën a yaatu',
'filename'                    => 'Turu dencukaay bi',
'filedesc'                    => 'Faramfacce',
'fileuploadsummary'           => 'Faramfacce :',
'filereuploadsummary'         => 'Coppitey dencukaay bi :',
'filestatus'                  => 'Ay xibaar ci aqi aji-sos :',
'filesource'                  => 'Gongikuwaay :',
'uploadedfiles'               => 'Dencukaay yiñ fi yeb',
'ignorewarning'               => 'Tanqamlu dànkaafu bi te dugal dencukaay bi.',
'ignorewarnings'              => 'tanqamlu bépp dànkaafu',
'minlength1'                  => 'Turu dencukaay bi war na am benn araf lumu néew néew.',
'illegalfilename'             => 'Turu dencukaay bu « $1 » am na ay araf yuñ dul nangu ci boppi xët yi. Jox ko beneen tur te jéemaat ko yeb.',
'badfilename'                 => 'Nataal bi tuddewaat nañ ko « $1 ».',
'filetype-badmime'            => 'Nanguwuñ ku fi yeb xeeti dencukaay yu MIME « $1 » .',
'filetype-bad-ie-mime'        => 'Yebug dencukaay bi antuwul ndax Internet Explorer daf koo jàppe niki « $1 », ndax li mu man a doon xeetu  dencukaay bu wóoradi',
'filetype-unwanted-type'      => "« .$1 »''' doon na xeetu dencukaay buñ fi taamuwul.
{{PLURAL:$3|xeetu dencukaay biñ fi taamu mooy|xeeti dencukaay yiñ fi taamu ñooy}} $2.",
'filetype-banned-type'        => "'''\".\$1\"''' xeetu dencukaay buñ fi nanguwul la.
{{PLURAL:\$3|biñ fi nangu mooy|yiñ fi nangu ñooy}} \$2.",
'filetype-missing'            => 'Dencukaay bi amul genn lawal (niki « .jpg »).',
'large-file'                  => 'Li gën mooy dayoo dencukaay bi bañ a romb $1; bii dencukaay $2 la.',
'largefileserver'             => 'Dayoo dencukaay bi romb na kem bu joxekaay bi attan.',
'emptyfile'                   => 'dencukaay bi nga bëgg a yeb dafa mel ni amul dara. Xéj-na ag njuumte ci turu dencukaay bi moo ko waral. Seetal bu baax ndax dëgg-dëgg bëgg nga yeb bii dencukaay.',
'fileexists'                  => "Am na dencukaay bu tudd nii ba noppi.
Saytul '''<tt>[[:$1]]</tt>''' su dee wóoru la ne bëgg nga koo soppi.
[[$1|thumb]]",
'filepageexists'              => "Xëtu faramfacce bu dencukaay bi sos nañ ko ba noppi ci bii màkkaan '''<tt>[[:$1]]</tt>''', waaye amagul dencukaay bu ni tudd nii-nii. Faramfacceg xët wi nga dugal ci diirub yeb gi du feeñ ci xëtu waxtaanuwaay wi. Ngir faramfacce gi feeñ ci xëtu waxtaanuwaay wi faaw nga soppi ko ak sa loxo.",
'fileexists-extension'        => "Am na dencukaay bu ni tudd ba noppi: [[$2|thumb]]
* Turu dencukaay bi ngay yeb : '''<tt>[[:$1]]</tt>'''
* Turu dencukaay bi fi am : '''<tt>[[:$2]]</tt>'''
Tànnal weneen tur.",
'fileexists-forbidden'        => 'Am na ŋara wu ni tudd ba noppi te mano koo war; Dellul ginnaaw ngir yeb ŋara wi ak weneen tur
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Am na ŋara wu ni tudd ba noppi ci dencuwaayu ŋara bi ñuy bokk; Dellul ginnaaw ngir yeb ŋara wi ak weneen tur. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'ŋara wi ñaaralub {{PLURAL:$1| ŋara wii di toftal la| ŋara yii di toftal lañu}} :',
'uploadwarning'               => 'Moytul !',
'savefile'                    => 'Denc dencukaay bi',
'uploadedimage'               => 'Yeb na « [[$1]] »',
'overwroteimage'              => 'moo yeb sumb bu bees bu « [[$1]] »',
'uploaddisabled'              => 'Di jéeggalu, yebug dencukaay yi dañ koo doxadilandi.',
'uploaddisabledtext'          => 'Yebug dencukaay yi dañ koo doxadil.',
'uploadscripted'              => 'Wii ŋara dafa am yoonug HTML walla ab script bu ab joowukaayub web mana tekke ci anam gu baaxul.',
'uploadvirus'                 => 'Wii ŋara daa am doomu-jangoro! Ngir yeneen xamle, saytul : $1',
'sourcefilename'              => 'Turu ŋara wi ci cosaan:',
'destfilename'                => 'Tur bi nga bëgg a jox ŋara wi:',
'upload-maxfilesize'          => 'Dayoob ŋara wi warul romb: $1',
'watchthisupload'             => 'Topp dencukaay bii',
'filewasdeleted'              => 'Dencukaay bu tudd nii yeboon nañ ko fi ba noppi, faraat ko. Saytul $1 laataa nga koy yebaat.',
'upload-wasdeleted'           => "'''Moytul: yaa ngi yebaat ab dencukaay buñ fi fare woon ci lu weesu'''

Jaar-jaaru far gi man na laa dimbali ci nga see ndaxam jar na nga yebaat ko.",
'filename-bad-prefix'         => "Turu dencukaay bi ngay yeb moo ngi tambalee '''\"\$1\"''', te muy tur wu nosukaay bi di joxal boppam nataalukaay yi.
Dila ñaan nga tànn tur wuy faramfacce dencukaay bi.",
'upload-success-subj'         => 'Yeb gi jàll na',

# Special:ListFiles
'imgfile'               => 'Dencukaay',
'listfiles'             => 'Limu dencukaay',
'listfiles_date'        => 'Taariix',
'listfiles_name'        => 'Tur',
'listfiles_user'        => 'Jëfandikukat',
'listfiles_size'        => 'Dayoo',
'listfiles_description' => 'Faramfacce',
'listfiles_count'       => 'Sumb',

# File description page
'file-anchor-link'          => 'Dencukaay',
'filehist'                  => 'Jaar-jaaru dencukaay bi',
'filehist-help'             => 'Cuqal cib taariix/waxtu ngir gis ni dencukaay bi meloon ca jamono jooju.',
'filehist-deleteall'        => 'Far lépp',
'filehist-deleteone'        => 'Far',
'filehist-revert'           => 'Delloowaat',
'filehist-current'          => 'teew',
'filehist-datetime'         => 'Taariix ak Waxtu',
'filehist-thumb'            => 'Tuutal',
'filehist-thumbtext'        => 'Tuutal gu  sumb bu $1',
'filehist-user'             => 'Jëfandikukat',
'filehist-dimensions'       => 'Dayoo',
'filehist-filesize'         => 'Dayoo ŋara wi',
'filehist-comment'          => 'Saraa',
'filehist-missing'          => 'Dencukaay bi fi nekkul',
'imagelinks'                => 'Xët yi am bii dencukaay',
'linkstoimage'              => '{{PLURAL:$1|Xët wii ci suuf ëmb na|$1 xët yii ci suuf ëmb nañu}} bii dencukaay:',
'linkstoimage-more'         => 'Lu ëpp $1 {{PLURAL:$1|xët lëkkale nañu leen|xët lëkkale nañu leen}} ak bii dencukaay.
Lim bii di toftal moo lay won {{PLURAL:$1|xët wi ñu njëkk a|xët yi ñu njëkk a}} lëkkale ak wii.
Ab [[Special:WhatLinksHere/$2|lim bu mat]] jàppandi na.',
'nolinkstoimage'            => 'Amul wenn xët wu ëmb bii dencukaay.',
'morelinkstoimage'          => 'Xool [[Special:WhatLinksHere/$1|yeneeni lëkkalekaay]] yuy jëme ci bii dencukaay.',
'redirectstofile'           => '{{PLURAL:$1|dencukaay bii di toftal ab|$1 dencukaay yii di toftal ay}} jubluwaat {{PLURAL:$1|la buy|lañu yuy}} jëme ci dencukaay bii:',
'duplicatesoffile'          => '{{PLURAL:$1|Dencukaay bii|$1 Dencukaay  yii}} di toftal {{PLURAL:$1|ab duppitu|ay duppitu}} bii {{PLURAL:$2|la|lañu}} ([[Special:FileDuplicateSearch/$2|yeneeni faramfacce]])::',
'sharedupload'              => 'Dencukaay bii $1 la bàyyikoo, te man nañu koo jëfandikoo ci yeneen sémb.',
'sharedupload-desc-there'   => 'Dencukaay bii $1 la bàyyikoo te man nañu koo jëfandikoo ci yeneen sémb.
Saytul [$2 xëtu faramfaccewaayu dencukaay bii] ngir yeneeni xibaar.',
'sharedupload-desc-here'    => 'Dencukaay bii $1 la bàyyikoo te man nañu koo jëfandikoo ci yeneen sémb.
Faramfacce gi ci [$2 xëtu faramfaccewaayu xët wi] lañuy wone ci suuf .',
'filepage-nofile'           => 'Amul benn dencukaay bu ni tudd.',
'filepage-nofile-link'      => 'Amul benn dencukaay bu ni tudd, waaye man nga koo [$1 yeb]',
'uploadnewversion-linktext' => 'Yeb sumb bu bees bu bii dencukaay',
'shared-repo-from'          => '$1',
'shared-repo'               => 'ab dencu bees bokk',

# File reversion
'filerevert'                => 'Loppanti $1',
'filerevert-backlink'       => '← $1',
'filerevert-legend'         => 'Delloowaat dencukaay bi',
'filerevert-intro'          => "Yaa ngi waaj a delloowaat dencukaay bii di '''[[Media:$1|$1]]''' ci  [$4 sumb bu $2 ci $3].",
'filerevert-comment'        => 'Saraa:',
'filerevert-defaultcomment' => 'Sumb bu $1 ci $2 delloowaat nañu ko',
'filerevert-submit'         => 'Delloowaat',
'filerevert-success'        => "'''[[Media:$1|$1]]''' delloowaat nañu ko [$4 ci sumb bu $2 ci $3].",
'filerevert-badversion'     => 'Amul sumb yu jiitu bu bii dencukaay ci taariix bi nga joxe.',

# File deletion
'filedelete'                  => 'Far $1',
'filedelete-backlink'         => '← $1',
'filedelete-legend'           => 'Far bii dencukaay',
'filedelete-intro'            => "Yaa ngi waaj a far dencukaay bii di '''[[Media:$1|$1]]''' ak jaar-jaaram bépp.",
'filedelete-intro-old'        => "Yaa ngi waaj a far sumb bu '''[[Media:$1|$1]]''' bu  [$4 $2 ci $3].",
'filedelete-comment'          => 'Ngirte :',
'filedelete-submit'           => 'Far',
'filedelete-success'          => "'''$1''' far nañu ko.",
'filedelete-success-old'      => "Sumbu '''[[Media:$1|$1]]''' bu $2 ci $3 far nañu ko.",
'filedelete-nofile'           => "'''$1''' amul.",
'filedelete-nofile-old'       => "Amul benn sumb bu ñu denc bu '''$1''' ak melokaan yi nga joxe.",
'filedelete-otherreason'      => 'Yeneeni ngirte:',
'filedelete-reason-otherlist' => 'yeneeni ngirte',
'filedelete-reason-dropdown'  => '* Ngirtey far yi ëpp
** jalgati aqi aji-sos
** dencukaay bees ñaaral',
'filedelete-edit-reasonlist'  => 'Soppi ngirtey far gi',

# MIME search
'mimesearch'         => 'Seet ci xeeti ëmbiit yii di MIME',
'mimesearch-summary' => "Xët wii dina la may nga man segg xeeti dencukaay yu MIME.
Duggalal baat bi ci pax mi  ''xeet/''ron-xeet'', ci misaal <tt>image/jpeg</tt>.",
'mimetype'           => 'Xeet wu MIME :',
'download'           => 'yebbi',

# Unwatched pages
'unwatchedpages' => 'Xët yees toppul',

# List redirects
'listredirects' => 'Limu jubluwaat yi',

# Unused templates
'unusedtemplates'     => 'Royuwaay yees jëfandikoowul',
'unusedtemplatestext' => 'Ci wii xët dañ fiy lim xët yépp yi tudd {{ns:template}} yu ñu duggalul ci wenn xët.
Bul fattee seet baxam amul yeneen lëkkalekaay yu lay jëmale ci royuwaay yi balaa nga leen di far.',
'unusedtemplateswlh'  => 'yeneeni lëkkalekaay',

# Random page
'randompage'         => 'Aw xët ci mbetteel',
'randompage-nopages' => 'Amul xët {{PLURAL:$2| wu barabu turam| yu seen barabu tur}} doon: $1.',

# Random redirect
'randomredirect'         => 'Jubluwaat cig mbetteel',
'randomredirect-nopages' => 'Amul benn jubluwaat bu barabu turam doon  « $1 ».',

# Statistics
'statistics'                   => 'Limbari',
'statistics-header-pages'      => 'Limbari ñeel xët yi',
'statistics-header-edits'      => 'Limbari ñeel coppite yi',
'statistics-header-views'      => 'Limbari ñeel saytu yi',
'statistics-header-users'      => 'Limbari ñeel jëfandikukat yi',
'statistics-header-hooks'      => 'Yeneen limbari',
'statistics-articles'          => 'Xëti ëmbiit',
'statistics-pages'             => 'Xët',
'statistics-pages-desc'        => 'Xët yépp yi ci wiki bi, xëti waxtaanuwaay yi, jubluwaat yi, añs.',
'statistics-files'             => 'Xët yees yeb fii',
'statistics-edits'             => 'Coppitey xët yi dalee ca campug  {{SITENAME}}',
'statistics-views-total'       => 'Mbooleem saytu yi',
'statistics-views-peredit'     => 'Saytu ngir soppi',
'statistics-users'             => '[[Special:ListUsers|Jëfandikukat]] yi bindu',
'statistics-users-active'      => 'Jëfandikukat yu yëngu',
'statistics-users-active-desc' => 'Jëfandikukat yi amal ag yëngu-yëngu ci {{PLURAL:$1|bés bu mujj bi|$1 bés yu mujj yi}}',
'statistics-mostpopular'       => 'Xët yees gën a saytu',

'disambiguations'      => 'Xëti turandoo',
'disambiguationspage'  => 'Template:turandoo',
'disambiguations-text' => "Xët yii di toftal dañoo ëmb ay lëkkalekaay yuy jëme ciy '''xëti turandoo'''.
Dañoo waroon a jublu ci jukki yu baax. <br />
Xëti turandoo yi ñooy yi ëmb benn ci royuwaay yees def fii [[MediaWiki:Disambiguationspage]]",

'doubleredirects'            => 'Jubluwaat ñaari yoon',
'doubleredirectstext'        => "Wii xët dafa ëmb mbooleem xët yees jubluwaatal ci yeneen xëti jubluwaat.
Rëdd wu ne am na lëkkalekaay buy jëme ci bu njëkk ak ñaareelu jubluwaat bi, ak rëdduw mbind wu njëkk wu ñaareelu jubluwaat bi, biy ëmb xëtu jëmuwaay wu ''baax'' wi, wi jubluwaat bu njëkk bi war a jublu moom itam.",
'double-redirect-fixed-move' => '[[$1]] tuddewaat nañu ko.
Léegi mi ngi jublu [[$2]].',
'double-redirect-fixer'      => 'Jubbantikaayu jubluwaat',

'brokenredirects'        => 'Jubluwaat yu yaqu',
'brokenredirectstext'    => 'Jubluwaat yii dañuy jëmee ciy xët yu amul :',
'brokenredirects-edit'   => 'Soppi',
'brokenredirects-delete' => 'far',

'withoutinterwiki'         => 'Xët yi amul lëkkalekaay diggantey-làkk',
'withoutinterwiki-summary' => 'Xët yii amuñu lëkkalekaay yuy jëme ci yeneeni làkk:',
'withoutinterwiki-submit'  => 'Wone',

'fewestrevisions' => 'Jukki yi gën a néewi coppite',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|byte}}',
'ncategories'             => '$1 {{PLURAL:$1|wàll|ciy wàll}}',
'nlinks'                  => '$1 {{PLURAL:$1|lëkkalekaay|ciy lëkkalekaay}}',
'nmembers'                => '$1 {{PLURAL:$1|xët|ciy xët}} ci biir',
'nrevisions'              => '$1 {{PLURAL:$1|sumb|sumb}}',
'nviews'                  => '$1 {{PLURAL:$1|nemmeeku|nemmeeku}}',
'specialpage-empty'       => 'Xët wii amul dara',
'lonelypages'             => 'Xëti jiriim',
'lonelypagestext'         => 'Xët yiy toftal amuñuy lëkkalekaay yu ne ci yeneen xët yu leen di ubbi te ëmbuwuñu itam ci benn xëtu {{SITENAME}}.',
'uncategorizedpages'      => 'Xët yi amul wàll',
'uncategorizedcategories' => 'Wàll yi amul wàll',
'uncategorizedimages'     => 'Dencukaay yi amul wàll',
'uncategorizedtemplates'  => 'Royuwaay yi amul Wàll',
'unusedcategories'        => 'Wàll yees jëfandikoowul',
'unusedimages'            => 'Dencukaay yees jëfandikoowul',
'popularpages'            => 'Xët yees gën a saytu',
'wantedcategories'        => 'Wàll yi ñu gën a laaj',
'wantedpages'             => 'Xët yi ñu gën a laaj',
'wantedpages-badtitle'    => 'Koj bu baaxul bu nekk ci ngérte yi : $1',
'wantedfiles'             => 'Ŋara yi ñu gën a soxla',
'wantedtemplates'         => 'Royuwaay yi ñu laaj',
'mostlinked'              => 'Xët yi ñu gën a lëkkale',
'mostlinkedcategories'    => 'Wàll yi ñu gën a jëfandikoo',
'mostlinkedtemplates'     => 'Royuwaay yi ñu gën a jëfandikoo',
'mostcategories'          => 'Jukki yi ëpp yiy jëfandikooy wàll',
'mostimages'              => 'Ŋara yi ñu gën a jëfandikoo',
'mostrevisions'           => 'Jukki yi ñu gën a soppi',
'prefixindex'             => 'wépp xët wu tambalee',
'shortpages'              => 'Xët yu gàtt',
'longpages'               => 'Xët yu gudd',
'deadendpages'            => 'Xët yi amul génnuwaay',
'deadendpagestext'        => 'Xët yii di toftal lëkkaloowuñu ak wenn xët ci bii wiki',
'protectedpages'          => 'Xët yi ñu aar',
'protectedpages-indef'    => 'Yi ñu aarandi rekk',
'protectedpagestext'      => 'Xët yii di toftal dañu leen aar, maneesu leen soppi walla tuddewaat',
'protectedpagesempty'     => 'Nii-nii amul wenn xët wu ñu aar ci gii anam',
'protectedtitles'         => 'Koj yi ñu aar',
'protectedtitlestext'     => 'Maneesul a sos ay xët ak koj yi ñu lim fii',
'protectedtitlesempty'    => 'Nii-nii amul benn koj bu ñu aar ci gii anam.',
'listusers'               => 'Limu jëfandikukat yi',
'listusers-editsonly'     => 'Wone jëfandikukat yi am ay cëru',
'usercreated'             => 'Ñoo ngi ko sos  $1 ci $2',
'newpages'                => 'Xët yu yees',
'newpages-username'       => 'Jëfëndikookat :',
'ancientpages'            => 'Jukki yi gënë néew ay coppite ci lu mujj',
'move'                    => 'Tuddewaat',
'movethispage'            => 'Tuddewaat xët wi',
'unusedimagestext'        => 'Jàppal ne yeneeni dal man nañoo ami lëkkalekaay yuy jëme ci yii dencukaay, kon ab dencukaay man nañu koo jàppe niki bees jëfandikoowul te fekk na ab dalub biti moo ngi koy jëfandikoo.',
'unusedcategoriestext'    => 'Wàll yii toftal, nekk nañu wante amuñu benn jukki walla wàll ci seen biir.',
'notargettitle'           => 'Njoxe yi matuñu',
'notargettext'            => 'Waxoo ci wan xët walla ci ban jëfandikukat ngay amal solo sii.',
'nopagetitle'             => 'Xëtu jubluwaay wi amul',
'nopagetext'              => 'Xët jubluwaay wi nga laaj amul.',
'pager-newer-n'           => '{{PLURAL:$1|gën a bees|$1 yi gën a yees}}',
'pager-older-n'           => '{{PLURAL:$1|gën a yàgg|$1 yi gën a yàgg}}',
'suppress'                => 'Saytu',

# Book sources
'booksources'               => 'Téereb delluwaay',
'booksources-search-legend' => 'Seet ab téereb delluwaay',
'booksources-isbn'          => 'ISBN :',
'booksources-go'            => 'Ayca',
'booksources-text'          => 'Lii ab lima ay lëkkalekaay la yu jëme ciy dal yu biti yuy jaayi téere yu yees ak yu magget, man nga faa ami xibaar ñeel téere yi ngay seet:',
'booksources-invalid-isbn'  => 'ISBN bi nga joxe mel na ni baaxul; xoolal bu baax ndax defoo ag njuumte ci bi nga koy duppi ca gongikuwaayam.',

# Special:Log
'specialloguserlabel'  => 'Jëfandikukat :',
'speciallogtitlelabel' => 'Koj :',
'log'                  => 'Yéenekaay',
'all-logs-page'        => 'Yéenekaay mbooloo yépp',
'alllogstext'          => 'Wone gees boole gu mbooleem yéenekaayu {{SITENAME}}.
Man ngaa wàññi wone gi soo tànnee ab yéenekaay, turu jëfandikukat, walla xët wu mu laal (ñaar ñépp dañuy yëg tolluwaayu mbind mi) .',
'logempty'             => 'Dara nekkul ci jaar-jaaru xët wii.',
'log-title-wildcard'   => 'Seet ay koj yu tambalee mii mbind',

# Special:AllPages
'allpages'          => 'Xët yépp',
'alphaindexline'    => '$1 ba $2',
'nextpage'          => 'Xët wi toftal ($1)',
'prevpage'          => 'Xët wi jiitu ($1)',
'allpagesfrom'      => 'Wone xët yi tambalee ci:',
'allpagesto'        => 'Wone xët yi ba :',
'allarticles'       => 'Xët yépp',
'allinnamespace'    => 'Xët yépp(turu barab bu $1)',
'allnotinnamespace' => 'Xët yépp (génne ci barabu tur bu $1)',
'allpagesprev'      => 'Jiitu',
'allpagesnext'      => 'Toftal',
'allpagessubmit'    => 'Ayca',
'allpagesprefix'    => 'Wone xët yi tambalee :',
'allpagesbadtitle'  => 'Koj bi nga bindal xët wii baaxul. xéj-na dafa am ay araf yu ñu manula jëfandikoo cib koj.',
'allpages-bad-ns'   => 'Barabu tur bii di « $1 » amul ci {{SITENAME}}.',

# Special:Categories
'categories'                    => 'Wàll',
'categoriespagetext'            => '{{PLURAL:$1|Wàll wiy|Wàll yiy}} toftal {{PLURAL:$1|dafa ëmb |dañoo ëmb}} ay xët walla xibaarukaay.
[[Special:UnusedCategories|Wáll yu këmm]] yi wonewuñu leen fi.
Xoolal itam [[Special:WantedCategories|wàll yi ñuy laaj]].',
'categoriesfrom'                => 'Wone wàll yi dalee ko ci :',
'special-categories-sort-count' => 'nosee lim',
'special-categories-sort-abc'   => 'nosee abajada',

# Special:DeletedContributions
'deletedcontributions'             => 'Cëru yees far',
'deletedcontributions-title'       => 'Cëru yees far',
'sp-deletedcontributions-contribs' => 'cëru',

# Special:LinkSearch
'linksearch'      => 'Lëkkalekaay yu biti',
'linksearch-pat'  => 'Kaddu yi ngay seet :',
'linksearch-ns'   => 'Barabu tur:',
'linksearch-ok'   => 'Seet',
'linksearch-line' => '$1 moo ngi ci xët wii di $2',

# Special:ListUsers
'listusersfrom'      => 'Wone jëfandikukat yi doore ko ci:',
'listusers-submit'   => 'Wone',
'listusers-noresult' => 'Benn jëfandikukat giseesu ko',
'listusers-blocked'  => '(téyees)',

# Special:ActiveUsers
'activeusers'          => 'Limu jëfandikukat yu yëngu',
'activeusers-count'    => '$1 coppite {{PLURAL:$2|bu mujj|yu mujj}}',
'activeusers-from'     => 'Wone jëfandikukat yi dalee ko ci:',
'activeusers-noresult' => 'Benn jëfandikukat giseesu ko',

# Special:Log/newusers
'newuserlogpage'              => 'Jëfandikukat yu yees yi',
'newuserlogpagetext'          => 'Xët wii daf lay won limu sáq yi fi mujjee sosu.',
'newuserlog-byemail'          => 'baatujáll bi yónne nañu ko cib bataaxal',
'newuserlog-create-entry'     => 'Jëfandikukat bu bees',
'newuserlog-create2-entry'    => 'moo sos mii sáq mu bees $1',
'newuserlog-autocreate-entry' => 'Sáq mi sos na boppam',

# Special:ListGroupRights
'listgrouprights'                      => 'Sañ-sañi mbooloom jëfandikukat mi',
'listgrouprights-summary'              => 'Lii di toftal mooy limu mboolooy jëfandikukat yi ne ci bii wiki, ak sañ-sañ yi ñu leen féetaleel.
Man ngaa fee gis itam [[{{MediaWiki:Listgrouprights-helppage}}|yeneen xibaar]] ñeel sañ-sañi mbooloo mu ci nekk.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Sañ-sañ yees joxe</span>
* <span class="listgrouprights-revoked">Sañ-sañ yees nangu</span>',
'listgrouprights-group'                => 'Mbooloo',
'listgrouprights-rights'               => 'Sañ-sañ',
'listgrouprights-helppage'             => 'Help:Sañ-sañi mbooloo yi',
'listgrouprights-members'              => '(limu cër yi)',
'listgrouprights-addgroup'             => 'Man ngaa yokk {{PLURAL:$2|mbooloo mi|mbooloo yi}} : $1',
'listgrouprights-removegroup'          => 'Man ngaa far {{PLURAL:$2|mbooloo mi|mbooloo yi}}: $1',
'listgrouprights-addgroup-all'         => 'Man ngaa yokk ci mbooloo yépp',
'listgrouprights-removegroup-all'      => 'Man ngaa faree ci mbooloo yépp',
'listgrouprights-addgroup-self'        => 'Man ngaa yokk {{PLURAL:$2|mbooloo mi|mbooloo yi}} ci sa sàq : $1',
'listgrouprights-removegroup-self'     => 'Man ngaa far {{PLURAL:$2|mbooloo mi|mbooloo yi}} ci sa sàq: $1',
'listgrouprights-addgroup-self-all'    => 'Man ngaa yokk mbooloo yépp ci sa sàq',
'listgrouprights-removegroup-self-all' => 'Man ngaa far mbooloo yépp ci sa sàq',

# E-mail user
'mailnologin'      => 'Amul benn mákkaan boo man a yónne bataaxal bi',
'mailnologintext'  => 'Ngir man a yónney bataaxal laaj na nga [[Special:UserLogin|dugg]] te it am ab màkkaanub m-bataaxal bu baax ci say [[Special:Preferences|tànneef]].',
'emailuser'        => 'Bind bii jëfandikukat',
'emailpage'        => 'Yónne ab m-bataaxal bii jëfandikukat',
'usermailererror'  => 'Njuumte ci koju m-bataaxal bi',
'defemailsubject'  => 'M-bataaxalu {{SITENAME}}',
'noemailtitle'     => 'Amul mákkaanub m-bataaxal',
'noemailtext'      => 'Bii jëfandikukat joxewul ab màkkaanub m-bataaxal bu baax.',
'nowikiemailtitle' => 'Amul m-bataaxal bees nangu',
'nowikiemailtext'  => 'Bii jëfandikukat dafa tànn bañ yeneen jëfandikukat yi yónne koy m-bataaxal.',
'email-legend'     => 'Yónne ab m-bataaxal beneen jëfandikukatu {{SITENAME}}',
'emailfrom'        => 'Yónnekat:',
'emailto'          => 'Jotkat:',
'emailsubject'     => 'Koj:',
'emailmessage'     => 'Bataaxal&nbsp;:',
'emailsend'        => 'Yónne',
'emailccme'        => 'Yónne ab duppiit ci sama màkkaan.',
'emailccsubject'   => 'Duppiitu bataaxal bi nga yónne $1: $2',
'emailsent'        => 'Bataaxal yees yónne',
'emailsenttext'    => 'Sa bataaxal yónne nañu ko.',
'emailuserfooter'  => '$1 moo yónne bii bataaxal $2 jaare ko ci solo su "Yónne ab bataaxal bii jëfandikukat" ci {{SITENAME}}.',

# Watchlist
'watchlist'            => 'Limu toppte',
'mywatchlist'          => 'Limu toppte',
'nowatchlist'          => 'Sa limu toppte amul benn jukki.',
'watchlistanontext'    => 'Ngir mana gis walla soppi jëfkayu sa limu toppte, faw nga  $1.',
'watchnologin'         => 'Duggoo de',
'watchnologintext'     => 'Yaa wara nekk [[Special:UserLogin|duggal]] ngir soppi lim gi.',
'addedwatch'           => 'Yokk ci sa limu toppte',
'addedwatchtext'       => "Xët wii di « [[:$1]] » yokk nañu ko ci sa [[Special:Watchlist|limu toppte]].
Coppite yiy ñëw yu xët wi ak xëtu waxtaanuwaay wi mu àndal di nañu leen fa dugal, dañula koy won mu '''duuf''' ci [[Special:RecentChanges|limu coppite yu mujj yi]] ngir xammee gi yomb.",
'removedwatch'         => 'Jëlee ci sa limu toppte',
'removedwatchtext'     => 'Xët wii di « [[:$1]] » jële nañu ko ci sa [[Special:Watchlist|limu toppte]].',
'watch'                => 'Topp',
'watchthispage'        => 'Topp xët wii',
'unwatch'              => 'Bul toppati',
'unwatchthispage'      => 'Bul toppati',
'watchnochange'        => 'Lenn ci xët yi ngay topp soppikuwul ci diir bii',
'watchlist-details'    => 'Topp nga $1 {{PLURAL:$1|xët|ciy xët}}, soo waññiwaalewul xëti waxtaanuwaay yi.',
'wlheader-showupdated' => '* Xët yi ñu soppiwoon ca sa duggu bu mujj ñoom la ñu fesal ñu <b>xëm</b>',
'watchmethod-recent'   => 'saytug coppite yu mujj yu xët yi ngay topp',
'watchmethod-list'     => 'saytug xët yi ñuy topp ngir ay coppite yu mujj',
'watchlistcontains'    => "Sa limu toppte am na '''$1''' {{PLURAL:$1|xët|xët}}.",
'iteminvalidname'      => 'Ay jafe-jafe ak xët wii di « $1 » : tur bi baaxul.',
'wlnote'               => 'Fii ci suuf {{PLURAL:$1| ngay gis coppite yu mujj yi|ngay gis $1 coppite yu mujj}} ci {{PLURAL:$2|waxtu gu mujj gi|<b>$2</b> waxtu yu mujj}}.',
'wlshowlast'           => 'wone $1 waxtu yu mujj, $2 bess yu mujj, walla $3.',
'watchlist-options'    => 'Tànneefi limu toppte bi',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Topp...',
'unwatching' => 'Farug toppte gi ...',

'enotif_reset'                 => 'Fésal xët yépp niki yoo nemmeeku ba noppi',
'enotif_newpagetext'           => 'Lii aw xët wu bees la.',
'enotif_impersonal_salutation' => 'Jëfandikukat bu {{SITENAME}}',
'changed'                      => 'soppi',
'created'                      => 'sosu na',
'enotif_subject'               => 'Xët wii di $PAGETITLE wu {{SITENAME}}, $PAGEEDITOR moo ko $CHANGEDORCREATED',
'enotif_lastvisited'           => 'Nemmeekul $1 ngir gis bépp coppite dale ba sa nemmeku gu mujj.',
'enotif_lastdiff'              => 'Xoolal $1 ngir gis gii coppite.',
'enotif_anon_editor'           => 'Jëfandikukat bu binduwul $1',

# Delete
'deletepage'             => 'Far xët wi',
'confirm'                => 'Dëggal',
'excontent'              => 'ëmbitam doonoon « $1 »',
'excontentauthor'        => 'ëmbitam doonoon: « $1 » te kenn ki ci cëru doonoon « [[Special:Contributions/$2|$2]] »',
'exbeforeblank'          => 'ëmbitam laataa far gi : $1',
'exblank'                => 'xët wi amul dara',
'delete-confirm'         => 'Far « $1 »',
'delete-backlink'        => '← $1',
'delete-legend'          => 'Far',
'historywarning'         => 'Moytul! xët wi ngay waaja far am na jaar-jaar :',
'confirmdeletetext'      => 'Yaa ngi waaja far ba faaw, ci dáttub njoxe bi, aw xët walla ab nataal ak jaar-jaaram. Dila ñaan nga dëggal ne loolu nga namma def dëgg-dëgg, te xam nga limuy jur, te itam dëppoo na ak átte yi ñu tëral ci [[{{MediaWiki:Policy-url}}]].',
'actioncomplete'         => 'Jëf jees def',
'deletedtext'            => '« <nowiki>$1</nowiki> » far nañu ko.
Xolal $2 ngir gis limu farte bi mujj.',
'deletedarticle'         => 'moo far « [[$1]] »',
'suppressedarticle'      => 'moo far « [[$1]] »',
'dellogpage'             => 'Jaar-jaaru farte bi',
'dellogpagetext'         => 'Li toftal ab limu farte yi mujj la.',
'deletionlog'            => 'jaar-jaaru  farte bi',
'reverted'               => 'Loppanti ci sumb mi weesu',
'deletecomment'          => 'Ngirte :',
'deleteotherreason'      => 'Yeneeni ngirte :',
'deletereasonotherlist'  => 'Yeneeni ngirte',
'deletereason-dropdown'  => '*Ngirtey farte yi gëna bari
** Aji-sos jee ko deflu
** Jalgati aqi aji-sos
** Caay-caay',
'delete-edit-reasonlist' => 'Soppi ngirtey farte gi',
'delete-toobig'          => 'Xët wii dafa am jaar-jaar bu bari, bu weesu $1 {{PLURAL:$1|sumb|sumb}}. Farteg yooyule xët dañu koo digal ngir bañ ay jafe-jafe yu mana am ci doxinu {{SITENAME}}.',
'delete-warning-toobig'  => 'Xët wii dafa am jaar-jaar bu bari, bu weesu $1 {{PLURAL:$1|sumb|sumb}}. Seenug farte man naa jur ag jaxasoo ci dáttub njoxeeb {{SITENAME}} ; def ko ak teey.',

# Rollback
'rollback'         => 'Loppanti coppite yi',
'rollback_short'   => 'Loppanti',
'rollbacklink'     => 'delloowaat',
'rollbackfailed'   => 'Loppanti gi antuwul',
'cantrollback'     => 'Neenal coppite gi manula nekk;
Ki def coppite gi mooy Kenn ki masa cëru ci xët wii.',
'alreadyrolled'    => 'Loppantig coppite gu mujj gu xët wii di « [[:$1]] » manula nekk, ki ko def di [[User:$2|$2]] ([[User talk:$2|Waxtaan]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]);
am na keneen ku jota soppi walla loppanti xët wi.

Ki mujje soppi xët wi mooy [[User:$3|$3]] ([[User talk:$3|Waxtaan]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'      => "Tënkug coppite gi mooy: « ''$1'' ».",
'revertpage'       => 'Loppantig coppite gu [[Special:Contributions/$2|$2]] ([[User talk:$2|Waxtaan]]) dello ko ci sumb mu [[User:$1|$1]]',
'rollback-success' => 'Ki loppanti mooy $1 ;
Ki ko dello ci sumb mu mujj mi mooy $2.',

# Edit tokens
'sessionfailure' => 'Dafa mel ne sa dugg gi am na ay tolof-tolof ;
Noste gi téye na sag dugg ngir wattu kaaraange.
Di la ñaan nga dellu ginnaaw te yesalaat xët wa jóge, te jéemaat',

# Protect
'protectlogpage'              => 'Jaar-jaaru aar yi',
'protectlogtext'              => 'Lii di toftal ab limu xët yi ñu aar ak yi ñu aaradi la.
nemmeekul [[Special:ProtectedPages|limu xët yi ñu aar]] ngir gis ab lim ci xët yi ñu aar nii-nii.',
'protectedarticle'            => 'moo aar « [[$1]] »',
'modifiedarticleprotection'   => 'moo soppi tolluwaayu kaaraange gu « [[$1]] »',
'unprotectedarticle'          => 'moo aaradi « [[$1]] »',
'movedarticleprotection'      => 'moo jële kaaraange gi ci « [[$2]] » jëmale ko « [[$1]] »',
'protect-title'               => 'Soppi tolluwaayu kaaraange gu « [[$1]] »',
'prot_1movedto2'              => '[[$1]] leegi mooy [[$2]]',
'protect-backlink'            => '← $1',
'protect-legend'              => 'Dëggalal aar gi',
'protectcomment'              => 'Ngirte :',
'protectexpiry'               => 'Jeexintal :',
'protect_expiry_invalid'      => 'Waxtub njeextal bi baaxul.',
'protect_expiry_old'          => 'Waxtub njeextal bi weesu na.',
'protect-text'                => "Fii man nga fee gise ak soppi tolluwaayu kaaraange gu wii xët '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "Manoo soppi tolluwaayu kaaraange gi su ñu la téyee.
Nii la xët wi tëdde '''$1''' :",
'protect-locked-access'       => "Sa sàq amul sañ-sañ yu mat ba man a soppi tolluwaayu kaaraangeg xët wi.
Nii-nii kocc-koccalu xët wii ñooy '''$1''':",
'protect-cascadeon'           => 'Xët wii dees koo aar ndax limu ëmbu {{PLURAL:$1|ci xët wiy toftal wees aar|ci xët yiy toftal yees aar}} ak kaaraangeg toftaloo. Man ngaa soppi tolluwaayu kaaraangeg xët wi, waaye du am njeexiit ci kaaraangeg toftaloo gi',
'protect-default'             => 'Sañal jëfandikukat yépp',
'protect-fallback'            => 'Laaj na sañ-sañ bii di « $1 »',
'protect-level-autoconfirmed' => 'Téye jëfandikukat yu binduwul ak yu yees yi',
'protect-level-sysop'         => 'Yorkat yi rekk',
'protect-summary-cascade'     => 'toftaloo',
'protect-expiring'            => 'Njeexte: $1 (UTC)',
'protect-expiry-indefinite'   => 'ba faaw',
'protect-cascade'             => 'Aaraale xët yi ne ci wii (kaaraange cig toftaloo)',
'protect-cantedit'            => 'Manoo soppi tolluwaayu kaaraange gu wii xët , ndax amoo sañ-sañ yu doy ngir soppi ko',
'protect-othertime'           => 'Beneen app:',
'restriction-type'            => 'Sañ-sañ:',
'restriction-level'           => 'Tolluwaayu yamale gi :',
'minimum-size'                => 'Dayoo bi gëna tuuti',
'maximum-size'                => 'Dayoo bi gëna rëy:',

# Restrictions (nouns)
'restriction-edit'   => 'Soppi',
'restriction-move'   => 'Tuddewaat',
'restriction-create' => 'Sos',
'restriction-upload' => 'Yeb',

# Restriction levels
'restriction-level-sysop'         => 'karaange gu mat',
'restriction-level-autoconfirmed' => 'kaarange gu diggu',
'restriction-level-all'           => 'bépp tolluwaay',

# Undelete
'undelete'                     => 'Xool xët yi ñu far',
'undeletepage'                 => 'Xool te loppanti xët yi ñu far',
'undeletepagetitle'            => "'''Lim biy toftal dafa ëmb sumb yi faru yu [[:$1|$1]]'''.",
'viewdeletedpage'              => 'Xool xët yi ñu far',
'undeletepagetext'             => '{{PLURAL:$1|xët wii dañ koo far, waaye moo ngi dencu ba tay, manees na koo loppanti|xët yii dañ leen far, waaye ñoo ngi dencu ba tay, manees na leen loppanti}}. Dencu bi man nañu koo wëyëŋal ci benn diir bi',
'undelete-fieldset-title'      => 'Loppanti sumb yi',
'undelete-nodiff'              => 'Giseesul benn sumb bu weesu.',
'undeletebtn'                  => 'Delloowaat',
'undeletelink'                 => 'Wone/delloowaat',
'undeleteviewlink'             => 'wone',
'undeletereset'                => 'Neenal',
'undeleteinvert'               => 'Jallarbi fal gi',
'undeletecomment'              => 'Tënk :',
'undeletedarticle'             => 'moo delloowaat « [[$1]] »',
'undeletedrevisions'           => '$1 {{PLURAL:$1|delloowaat nañ ab sumb|delloowaat nañ $1 sumb}}',
'undeletedrevisions-files'     => '$1 {{PLURAL:$1|sumb|sumb}} ak $2 {{PLURAL:$2|ŋara|ŋara}} loppanti nañu leen',
'undeletedfiles'               => '{{PLURAL:$1|1 dencukaay|$1 dencukaay}} lañu delloowaat',
'cannotundelete'               => 'Delloowaat gi antuwul. Xéj-na keneen delloowaat na ko ba noppi.',
'undeletedpage'                => "'''Xët wii di $1 delloowaat nañu ko.'''

Saytul [[Special:Log/delete|jaar-jaaru far bi]] ngir xool far yi ak delloowaat yu mujj yi.",
'undelete-header'              => 'Saltul [[Special:Log/delete|jaar-jaaru far bi ]] ngir xool far yi mujj.',
'undelete-search-box'          => 'Seet xët wu ñu far',
'undelete-search-prefix'       => 'Wone xët yi tambalee :',
'undelete-search-submit'       => 'Seet',
'undelete-no-results'          => 'Amul wenn xët wu yem ak li ngay seet.',
'undelete-filename-mismatch'   => 'Neenalug far gu sumb mu $1 antuwul: ŋara wi gisuwul',
'undelete-bad-store-key'       => 'Neenalug far gu sumb mu $1 : ŋara wi jàppandiwul woon laataa far gi.',
'undelete-cleanup-error'       => 'Njuumte ci farug ŋaraw dencu wu ñu jëfandikuwul « $1 ».',
'undelete-missing-filearchive' => 'Loppantig ŋaraw dencu wu xameekaayam doon $1 antuwul, ndax nekkul ci dáttub njoxe bi.
Xéj-na keeneen loppanti na ko ba noppi',
'undelete-error-short'         => 'Njuumte ci loppantig ŋara wu: $1',
'undelete-error-long'          => 'Njuumte ci jéem gee neenal farug dencukaay bi:

$1',
'undelete-show-file-confirm'   => 'Ndax wóor na ne bëgg ngaa wone sumb bees far bu bii dencukaay "<nowiki>$1</nowiki>" bu $2 ci $3?',
'undelete-show-file-submit'    => 'Waaw',

# Namespace form on various pages
'namespace'      => 'Barabu tur :',
'invert'         => 'Jallarbi fal gi',
'blanknamespace' => '(njëkk)',

# Contributions
'contributions'       => 'Cëruy bii jëfandikukat',
'contributions-title' => 'Cëru yu jëfandikukat bii di $1',
'mycontris'           => 'Samay cëru',
'contribsub2'         => 'Ngir $1 ($2)',
'nocontribs'          => 'Amul benn coppite bu melokaanoo nii bu ñu gis.',
'uctop'               => '(bi mujj)',
'month'               => 'Tambali ci weeru (ak yi jiitu) :',
'year'                => 'Tambali ci atum (ak yi jiitu) :',

'sp-contributions-newbies'       => 'Wone cëru yu jëfandikukat yu yees yi rekk',
'sp-contributions-newbies-sub'   => 'yu jëfandikukat yu yees yi',
'sp-contributions-newbies-title' => 'Cëru yu jëfandikukat yu yees yi',
'sp-contributions-blocklog'      => 'Jaar-jaaru téye yi',
'sp-contributions-deleted'       => 'cëru yi ñu far',
'sp-contributions-logs'          => 'Yéenekaay',
'sp-contributions-talk'          => 'waxtaan',
'sp-contributions-userrights'    => 'Yorug sañ-sañi jëfandikukat yi',
'sp-contributions-search'        => 'Seet ay cëru',
'sp-contributions-username'      => 'Makkaanu IP walla turu jëfandikukat :',
'sp-contributions-submit'        => 'Seet',

# What links here
'whatlinkshere'            => 'Xët yi mu lëkkalool',
'whatlinkshere-title'      => 'Xët yi lëkkalook wii « $1 »',
'whatlinkshere-page'       => 'Xët :',
'linkshere'                => 'Xët yii ci suuf am nañ ab lëkkalekaay buy jëm <b>[[:$1]]</b> :',
'nolinkshere'              => 'Amul wenn xët wu lëkkalook wii <b>[[:$1]]</b>.',
'nolinkshere-ns'           => "Amul wenn xët wu lëkkalook wii '''[[:$1]]''' ci barabu tur bi nga tànn.",
'isredirect'               => 'Xëtu jubluwaat',
'istemplate'               => 'mboole',
'isimage'                  => 'lëkkalekaayu nataal bi',
'whatlinkshere-prev'       => '{{PLURAL:$1|wi jiitu|$1 yi jiitu}}',
'whatlinkshere-next'       => '{{PLURAL:$1|wi toftal|$1 yi toftal}}',
'whatlinkshere-links'      => '← lëkkalekaay',
'whatlinkshere-hideredirs' => '$1 jubluwaat',
'whatlinkshere-hidetrans'  => '$1 mboole',
'whatlinkshere-hidelinks'  => '$1 lëkkalekaay',
'whatlinkshere-hideimages' => '$1 lëkkalekaayi nataal',
'whatlinkshere-filters'    => 'Seggukaay',

# Block/unblock
'blockip'                         => 'Téyeb jëfandikukat',
'blockip-legend'                  => 'Téye jëfandikukat bi',
'ipaddress'                       => 'Màkkaanu IP :',
'ipadressorusername'              => 'Màkkaanu IP walla turu jëfandikukat:',
'ipbexpiry'                       => 'Diiru téye gi',
'ipbreason'                       => 'Ngirte :',
'ipbreasonotherlist'              => 'Yeneeni ngirte',
'ipbreason-dropdown'              => '* Ngirtey téye yi ëpp
** Ag caay-caay
** Dugalub xibaar yu dëgguwul
** Farug ëmbitu ay xët
** Dugalub lëkkalekaay ngir yëgle ay dal
** Dugalub ëmbit yu amul-njariñ
** Di jéem a xiixaan walla di xuloo ak nit ñi
** Ëppal ci sosi sàq yu bari
** Turu jëfandikukat buñu fi nanguwul',
'ipbanononly'                     => 'Téye jëfandikukat yu binduwul rekk',
'ipbcreateaccount'                => 'Tere sosug yeneeni sàq',
'ipbemailban'                     => 'Tere jëfandikukat bi yónne ay m-bataaxal',
'ipbenableautoblock'              => 'Téye ci saa si màkkaanu IP bi mu mujje jëfandikoo, ak yeneen yi muy jëfandikoo-ji ngir amali coppite',
'ipbsubmit'                       => 'Téye bii jëfandikukat',
'ipbother'                        => 'Beneen diir:',
'ipboptions'                      => '2 waxtu:2 hours,1 fan:1 day,3 fan:3 days,1 ayubés:1 week,2 ayubés:2 weeks,1 weer:1 month,3 weer:3 months,6 weer:6 months,1 at:1 year,ba-faaw:infinite',
'ipbotheroption'                  => 'beneen',
'ipbotherreason'                  => 'Yeneeni ngirte/faramfacce:',
'ipbhidename'                     => 'Nëbb turu jëfandikukat bi ci coppite yeek lim yi',
'ipbwatchuser'                    => 'Topp xëtu jëfandikukat ak bu waxtaanuwaay bu bii jëfandikukat',
'ipballowusertalk'                => 'Sañal bii jëfandikukat mu man a soppi xëtu waxtaanuwaayam ci diir bees ko téye',
'ipb-change-block'                => 'Téyewaat bii jëfandikukat ak yii anam',
'badipaddress'                    => 'Màkkaanu IP bi baaxul.',
'blockipsuccesssub'               => 'Téye gi antu na',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] dees koo téye.<br />
Xoolal [[Special:IPBlockList|limu IP yees téye]] ngir gis ñees téye ñépp.',
'ipb-edit-dropdown'               => 'Soppi ngirtey téye gi',
'ipb-unblock-addr'                => 'Téyedi $1',
'ipb-unblock'                     => 'Téyedi ab turu jëfandikukat walla ab màkkaanub IP',
'ipb-blocklist'                   => 'Xool téye yi teew',
'ipb-blocklist-contribs'          => 'Cëru yu $1',
'unblockip'                       => 'Téyedi jëfandikukat bi',
'ipusubmit'                       => 'Far gii téye',
'unblocked'                       => '[[User:$1|$1]] dees koo téye',
'unblocked-id'                    => 'Téye gu $1 far nañu ko',
'ipblocklist'                     => 'Màkkaanu IP ak jëfandikukat yees Téye',
'ipblocklist-legend'              => 'Seet jëfandikukat bees téye',
'ipblocklist-username'            => 'Turu jëfandikukat walla màkkaanub IP:',
'ipblocklist-sh-userblocks'       => '$1 sàq yees téye',
'ipblocklist-sh-tempblocks'       => '$1 ñees téyendi',
'ipblocklist-sh-addressblocks'    => '$1 Téyeg IP bu kenn',
'ipblocklist-submit'              => 'Ceet',
'blocklistline'                   => '$1, $2 moo téye $3 ($4)',
'infiniteblock'                   => 'Ba-faww',
'expiringblock'                   => 'jeex $1 ci $2',
'anononlyblock'                   => 'Alaxam rek',
'noautoblockblock'                => 'Téye gu boppu doxadi na',
'createaccountblock'              => 'Téyeg sosug sàq mi',
'emailblock'                      => 'm-bataaxal bees téye',
'blocklist-nousertalk'            => 'Du man a soppi xëtu waxtaanuwaayam',
'ipblocklist-empty'               => 'Limu ñees téye ñi amul dara.',
'ipblocklist-no-results'          => 'Màkkaanu IP walla turu jëfandikukat bi nga laaj du bees téye.',
'blocklink'                       => 'Téye',
'unblocklink'                     => 'téyedi',
'change-blocklink'                => 'soppi téye gi',
'contribslink'                    => 'cëru',
'autoblocker'                     => 'Dañ la téye ndax sa màkkanu IP « $1 » moo ko mujje jëfandikoo. Li waral téyeg $1 mooy ne : « $2 ».',
'blocklogpage'                    => 'Jaar-jaaru téye yi',
'blocklogentry'                   => 'moo téye « [[$1]] » - ci diirub : $2 $3',
'reblock-logentry'                => 'moo soppi anami téye gu [[$1]] ak diirub njeextal bu $2 $3',
'blocklogtext'                    => 'Lii ab lim la ci téye ak téyedi yu jëfandikukat yi. Màkkaani IP yi ñu téye cig boppal limuñu leen fi. yëral [[Special:IPBlockList|limu jëfandikukat yiñ téye]] ngi gis ñi ñu téye nii-nii.',
'unblocklogentry'                 => 'moo téyedi « $1 »',
'block-log-flags-anononly'        => 'jëfandikukat yi binduwul rek',
'block-log-flags-nocreate'        => 'Tere nañ sa sosum sàq',
'block-log-flags-noautoblock'     => 'Téye gu boppu yu IP yi doxadi na',
'block-log-flags-noemail'         => 'm-bataaxal yi téye nañ leen',
'block-log-flags-nousertalk'      => 'Du man a soppi xëtu waxtaanuwaayam',
'block-log-flags-angry-autoblock' => 'téye gu boppu gu xóot gi dox na',
'block-log-flags-hiddenname'      => 'Turu jëfandikukat bees nëbb',
'range_block_disabled'            => 'Man gee téye ay mboolooy IP jàppandiwul nii-nii',
'ipb_expiry_invalid'              => 'Diirub jeexintalu téye gi baaxul',
'ipb_expiry_temp'                 => 'Danki jëfandikukat yi nëbbu dañu wara doon yuy jeex.',
'ipb_hide_invalid'                => 'Maneesul a far mii sàq; dafa mel ne ay coppiteem dañoo bari lool.',
'ipb_already_blocked'             => 'Jëfandikukat bi « $1 » dañ ko téye ba noppi',
'ipb-needreblock'                 => '== Téyees ba noppi ==
$1 dañu koo téye ba noppi.
Dangaa bëgg a soppi anam yi?',
'ipb_cant_unblock'                => 'Njuumte: téyeg $1 gisuwul. Xéj-na dañ kaa téyedi ba noppi.',
'ipb_blocked_as_range'            => 'Njuumte: màkkaan mi $1 téyewuñ ko moom kase, kon doo ko man téyedi. Ci mbooloom $2 la bokk, faww nga téyedi mbooloo mépp.',
'ip_range_invalid'                => 'Mbooloom IP mi baaxul.',
'blockme'                         => 'Téye ma',
'proxyblocker'                    => 'Téyekatu yóbbantekat',
'proxyblocker-disabled'           => 'Bii solo doxul.',
'proxyblockreason'                => 'Dañ téye sa IP ndax dadi ab yóbbantekat bu ubbeeku. Di la ñaan nga jublu ci sa ki la jox internet yegge ko jafe-jafeb kaaraange bi.',
'proxyblocksuccess'               => 'Jàll na.',
'sorbsreason'                     => 'Sa màkkaanu IP dañ ko limaale niki ab yóbbantekat bu ubbeeku ci DNSBL bi {{SITENAME}} di jëfandikoo.',
'sorbs_create_account_reason'     => 'Sa màkkaanu IP dañ ko limaale niki ab yóbbantekat bu ubbeeku ci DNSBL bi {{SITENAME}} di jëfandikoo. Kon sag mbindu du mana nekk.',
'cant-block-while-blocked'        => 'Manoo di téye yeneen jëfandikukat ci diir bi ñu la téye.',

# Developer tools
'lockdb'              => 'Caabi dàttub njoxe bi',
'unlockdb'            => 'Caabeedi dàttub njoxe bi',
'lockdbtext'          => 'Caabi dàttub njoxe bi day tax ba benn jëfandikukat du man a soppi aw xët walla sos weneen, soppi ay tànneefam ak xët yi muy topp, cig matale du man a def lenn luy laaj coppiteg dàttub njoxe bi. Di la ñaan nga dëggal ne lii nga bëgg a def dëgg-dëgg, te nga caabeedi ko soo noppee ci sa liggéey.',
'unlockdbtext'        => 'Caabeedi dàttub njoxe bi day tax ba bépp jëfandikukat man a soppi aw xët walla sos weneen, soppi ay tànneefam ak xët yi muy topp, cig matale, man a def lépp luy laaj coppiteg dàttub njoxe bi. Di la ñaan nga dëggal ne lii nga bëgg a def dëgg-dëgg.',
'lockconfirm'         => 'Waaw, dàttub njoxe bi laa bëgg a caabi',
'unlockconfirm'       => 'Waaw, dàttub njoxe bi laa bëgg a caabeedi.',
'lockbtn'             => 'Caabi dàttub njoxe bi',
'unlockbtn'           => 'Caabeedi dàttub njoxe bi',
'locknoconfirm'       => 'Cuqoo ci néegu dëggal bi.',
'lockdbsuccesssub'    => 'Caabig dàttub njoxe bi jàll na.',
'unlockdbsuccesssub'  => 'Caabi ju dàttub njoxe bi far nañu ko',
'lockdbsuccesstext'   => 'Dàttub njoxe bi caabi nañu koo.<br />
Bul fattee [[Special:UnlockDB|dindi caabi ji]] soo noppee ci sa liggéey.',
'unlockdbsuccesstext' => 'Caabeedi nañu dàttub njoxe bi.',
'lockfilenotwritable' => 'Maneesul a bind ci dencukaayub caabi bi.
Ngir caabi walla caabeedi dàttub njoxe bi, laaj na joxekaayub web bi man cee bind.',
'databasenotlocked'   => 'Kenn caabiwul dàttub njoxe bi',

# Move page
'move-page'                    => 'Tuddewaat $1',
'move-page-backlink'           => '← $1',
'move-page-legend'             => 'Tuddewaat aw xët',
'movepagetext'                 => "Bii palanteeru coppite dafay tax a man a tuddewaat aw xët, toppale jaar-jaaram bépp ci tur wu bees wi.
Xët wu yàgg wa dafay jublu wu bees wi.
Man ngaa def ba xët yi jubluwoon  wu yàgg wa  yeesal seen bopp, jublu koj wu bees wi.
Soo taamoo bañ koo def, saytul baxam sosul [[Special:DoubleRedirects|ñaari jubluwaat]] walla [[Special:BrokenRedirects|jubluwaat bu baaxul]].
Kiy tuddewaat moo sasoo saytu baxam lëkkalekaay yaa ngi jëme fees njort mu jëme fa.

Jàppal ne xët wi '''duñu''' ko man a tuddewaat weneen tur wu am ba noppi, amaana muy wu këmm walla wu jubluwaat te amul benn jaar-jaar bu weesu.
Lii mooy tax nga man a dellu ci koj wu njëkk wu aw xët su tuddewaat gi antuwulee, te maneesul bind-ci-kaw wenn xëtt wu am ba noppi cig njuumte.

'''MOYTUL'''
Jii jëf jees foogeewul man naa juri coppite ciy xët yees di saytu lu bari;
wóorluwul bu beex ne nand nga njeexiti jëf ji laataa ngay wéy.",
'movepagetalktext'             => 'Xëtu waxtaanuwaay wi mu àndal di na tuddewaat boppam, lu dul:
*Aw xët wu këmmul am ci wii tur wu bees, walla
*boyot bi toftal faladi nees ko.

Ci yii anam, faww nga tuddewaat walla yokk wii xëtu waxtaanuwaay ak sa loxo, su la neexee.',
'movearticle'                  => 'Tuddewaatal jukki bi',
'movenologintext'              => 'Ngir man a tuddewaat aw xët, da ngaa war a [[Special:UserLogin|dugg]] ni jëfëndikookat bu bindu te saw sàq war naa am yaggaa bi mu laaj.',
'movenotallowed'               => 'Amoo sañ-sañu tuddewaat xët yi.',
'movenotallowedfile'           => 'Amoo sañ-sañu tuddewaat dencukaay yi.',
'cant-move-user-page'          => 'Amoo sañ-sañu tuddewaat xëti jëfandikukat yi (ba mu des ron-xët yi).',
'cant-move-to-user-page'       => 'Amoo sañ-sañu tuddewaat aw xët jëme ko ciw xëtu jëfandikukat (ba mu des ron-xëti jëfandikukat yi)',
'newtitle'                     => 'Koj bu bees',
'move-watch'                   => 'Topp xët wii',
'movepagebtn'                  => 'Tuddewaat xët wi',
'pagemovedsub'                 => 'Tuddewaat gi antu na',
'movepage-moved'               => "'''« $1 »''' lañu tuddewaat '''« $2 »'''",
'movepage-moved-redirect'      => 'Sosees na ab jubluwaat',
'movepage-moved-noredirect'    => 'Sosug ab jubluwaat far nañu ko.',
'articleexists'                => 'Am na ba noppi ab jukki bu am bii koj, walla koj bi nga tànn baaxul. tànnal bennen.',
'cantmove-titleprotected'      => 'Toppale xët wi du man a nekk ndax tur wu bees wi dañu koo aar njëkk ngir bañ ag sosoom.',
'talkexists'                   => "'''Toppaleb xët wi antu na, waaye xëtu waxtaanuwaay wi mu andaloon toppalewu ko, ndax nekk na fi ak weneen koj wu bees. Faaw nga boole leen ak sa loxo'''",
'movedto'                      => 'Turam bu bees',
'movetalk'                     => 'Tuddewaat tamit xëtu waxtaanukaay wi mu andal',
'move-subpages'                => 'Tuddewaat ron-xët yi (ba $1)',
'move-talk-subpages'           => 'Tuddewaat ron-xëti xëti waxtaanuwaay yi (ba $1)',
'movepage-page-exists'         => 'Xët wii di $1 am na ba noppi te maneesul a bind ci kawam cig boppu',
'movepage-page-moved'          => 'Xët wii di $1 lees tuddewaat $2',
'movepage-page-unmoved'        => 'Xët wii di $1 maneesu koo tuddewaat $2',
'movepage-max-pages'           => 'Lim bi gën-a-bari bu $1 {{PLURAL:$1|xët|xët}} lees tuddewaat te maneesul a tuddewaat yeneen cig boppu.',
'1movedto2'                    => '[[$1]] la tuddewaat [[$2]]',
'1movedto2_redir'              => 'moo jubluwaatal  [[$1]] mu jëm [[$2]]',
'move-redirect-suppressed'     => 'Jubluwaat gees far',
'movelogpage'                  => 'Jaar-jaaru tuddewaat yi',
'movelogpagetext'              => 'Lii mooy limu xët yees mujje tuddewaat.',
'movesubpage'                  => '{{PLURAL:$1|ron-xët|ron-xët}}',
'movesubpagetext'              => 'Wii xët am na $1 ron-xët di yii ci suuf.',
'movenosubpage'                => 'Wii xët amul ron-xët',
'movereason'                   => 'Ngirte :',
'revertmove'                   => 'delloowaat',
'delete_and_move'              => 'Far te tuddewaat',
'delete_and_move_text'         => '== Laajub far ==
Xët wi nga joge niki àgguwaay « [[:$1]] » am na fi.
Dëgg-dëgg namm nga koo far ngir tuddewaat gi mana antu?',
'delete_and_move_confirm'      => 'Waaw, faral xët wi',
'delete_and_move_reason'       => 'Far nañu ko ngir mana amal tuddewaat gi',
'selfmove'                     => 'Koj bu njëkk ba ak bu mujj bi ñoo yam;
Manoo tudewaat aw xët ci wenn tur wi.',
'immobile-source-namespace'    => 'Manoo tuddewaat xët yi ci barabu tur bu « $1 »',
'immobile-target-namespace'    => 'Manoo toppale ay xët ci biir barabu tur bu « $1 »',
'immobile-target-namespace-iw' => 'Ab lëkkalekaayub diggantewiki manuta nekk kojuw xët.',
'immobile-source-page'         => 'Xët wii kenn manuta soppi turam.',
'immobile-target-page'         => 'Xët wii manoo koo jox wii tur.',

# Export
'export'            => 'Génne ay xët',
'export-addcattext' => 'Yokkal xëti Wàll gi :',
'export-addcat'     => 'Yokk',

# Namespace 8 related
'allmessagesname'    => 'Turu tool bi',
'allmessagescurrent' => 'Bataaxal bi fi nekk',
'allmessagestext'    => "Lii mo'y limu bataaxal yëpp yi am ci biir MediaWiki",

# Thumbnails
'thumbnail-more' => 'Ngandal',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Sa xëtu jëfandikukat',
'tooltip-pt-anonuserpage'         => 'Xëtu jëfandikukat wu bii màkkaanu IP',
'tooltip-pt-mytalk'               => 'Sa xëtu waxtaanuwaay',
'tooltip-pt-anontalk'             => 'Xëtu diisoowaay wu bii màkkaanu IP',
'tooltip-pt-preferences'          => 'Say tànneef',
'tooltip-pt-watchlist'            => 'Limu xët yi ngay topp',
'tooltip-pt-mycontris'            => 'Limu say cëru',
'tooltip-pt-login'                => 'Woo nan la ngir nga xammeku, waaye doonul lu manuta ñakk.',
'tooltip-pt-anonlogin'            => 'woo nan la ngir nga xammeku, waaye doonul lu manuta ñakk.',
'tooltip-pt-logout'               => 'Génn',
'tooltip-ca-talk'                 => 'Waxtaan yi ñeel xët wii',
'tooltip-ca-edit'                 => 'Man ngaa soppi xët wi. Ngir yàlla wonendil laataa ngay denc.',
'tooltip-ca-addsection'           => 'Tambali xaaj bu bees',
'tooltip-ca-viewsource'           => 'Xët wii dañ koo aar. Waaye man ngaa xool ëmbitam.',
'tooltip-ca-history'              => 'Sumb yi weesu yu xët wi.',
'tooltip-ca-protect'              => 'Aar xët wii',
'tooltip-ca-delete'               => 'Far xët wii',
'tooltip-ca-undelete'             => 'Loppanti xët wi mu dellu mel na mu meloon laataa far gi',
'tooltip-ca-move'                 => 'Tuddewaatal xët wii',
'tooltip-ca-watch'                => 'Yokk xët wii ci sa limu toppte',
'tooltip-ca-unwatch'              => 'Jële xët wii ci sa limu toppte',
'tooltip-search'                  => 'Seet ci biir {{SITENAME}}',
'tooltip-search-go'               => 'Dem ci xët wi tudd ni nga wax, su dee am na.',
'tooltip-search-fulltext'         => 'Seet xët yi ëmb kàddu gi',
'tooltip-p-logo'                  => 'Xët wu njëkk',
'tooltip-n-mainpage'              => 'Nemmeeku xëtu njëlbéen',
'tooltip-n-mainpage-description'  => 'Nemmeku xët wu njëkk wi',
'tooltip-n-portal'                => 'Ngir xam dara ci mbiri sémb bi, noo ci mana jàppe',
'tooltip-n-currentevents'         => 'Xibaar ci xew-xew yu teew yi',
'tooltip-n-recentchanges'         => 'Limu coppite yi mujj ci wiki bi',
'tooltip-n-randompage'            => 'Wone aw xët ci mbetteel',
'tooltip-n-help'                  => 'Xëtu ndimbal wi',
'tooltip-t-whatlinkshere'         => 'limu xët yi ci wiki bi yi lëkkalook wii',
'tooltip-t-recentchangeslinked'   => 'Limu coppite yu mujj yu xët yi lëkkalook wii',
'tooltip-feed-rss'                => 'Walug RSS ngir wii xët',
'tooltip-feed-atom'               => 'Walug Atom ngir wii xët',
'tooltip-t-contributions'         => 'Xool limu cëru bu bii jëfandikukat',
'tooltip-t-emailuser'             => 'Yónne ab m-bataaxal bii jëfandikukat',
'tooltip-t-upload'                => 'Yeb ay dencukaay',
'tooltip-t-specialpages'          => 'Limu xëti jagleel yépp',
'tooltip-t-print'                 => 'Sumb bu móolu bu xët wii',
'tooltip-t-permalink'             => 'Lëkkalekaay bu sax buy jëme ci bii sumb bu xët wi',
'tooltip-ca-nstab-main'           => 'Xool jukki bi',
'tooltip-ca-nstab-user'           => 'Xool xëtu jëfandikukat wi',
'tooltip-ca-nstab-media'          => 'Xool xëtu dencukaay wi',
'tooltip-ca-nstab-special'        => 'Lii aw xëtu jagleel la, kenn manu kaa soppi.',
'tooltip-ca-nstab-project'        => 'Xool xëtu sémb wi',
'tooltip-ca-nstab-image'          => 'Xool xëtu dencukaay wi',
'tooltip-ca-nstab-mediawiki'      => 'Xool bataaxalu noste bi',
'tooltip-ca-nstab-template'       => 'Xool royuwaay gi',
'tooltip-ca-nstab-help'           => 'Xool xëtu ndimbal wi',
'tooltip-ca-nstab-category'       => 'Xool xëtu wàll wi',
'tooltip-minoredit'               => 'Fésal samay coppite niki yu néewal',
'tooltip-save'                    => 'Wattu say coppite',
'tooltip-preview'                 => 'Jërëjëf ci nga wonendi say coppite balaa nga leen di denc!',
'tooltip-diff'                    => 'Ngir xool coppite yi nga amal ci mbind mi.',
'tooltip-compareselectedversions' => 'Xool wuute gi ne ci diggante ñaari sumb yi falu yu xët wi.',
'tooltip-watch'                   => 'Yokk xët wii ci sa limu toppte',
'tooltip-recreate'                => 'Sosaat xët wi donte dañ kaa faroon',
'tooltip-upload'                  => 'Door yeb gi',
'tooltip-rollback'                => '"Delloowaat" dafay neenal coppitey cërukat bi mujj ci xët wii ci benn cuq.',
'tooltip-undo'                    => '"Neenal" dafay far coppite yi te ubbi palanteeru coppite bi ci anamug wonendi.
Dafay tax nga man a bind ngirte li ci boyotu tënk bi.',

# Stylesheets
'common.css'      => '/* CSS yiñ def fii dañuy am ay njeexit ci col yépp  */',
'standard.css'    => '/* CSS yiñ def fii dañuy am ay njeexit ci jëfandikukatu col gu Standard  */',
'nostalgia.css'   => '/* CSS yiñ def fii dañuy am ay njeexit ci jëfandikukatu col gu Nostalgia  */',
'cologneblue.css' => '/* CSS yiñ def fii dañuy am ay njeexit ci jëfandikukatu col gu Cologne Blue */',
'monobook.css'    => '/* CSS yiñ def fii dañuy am ay njeexit ci jëfandikukatu col gu Monobook. */',
'myskin.css'      => '/* CSS yiñ def fii dañuy am ay njeexit ci jëfandikukatu col gu MySkin */',
'chick.css'       => '/* CSS yiñ def fii dañuy am ay njeexit ci jëfandikukatu col gu Chick */',
'simple.css'      => '/* CSS yiñ def fii dañuy am ay njeexit ci jëfandikukatu col gu Simple */',
'modern.css'      => '/* CSS yiñ def fii dañuy am ay njeexit ci jëfandikukatu col gu Modern */',

# Scripts
'common.js'      => '/* Bépp JavaScript buñ fi duggal, xët yéppa koy yeb ak jëfandikukat bumu manti doon. */',
'standard.js'    => '/* Bépp JavaScript buñ fi duggal jëfandikukat yiy jëfandikoo col gu Standard keppa koy yeb  */',
'nostalgia.js'   => '/* Bépp JavaScript buñ fi duggal jëfandikukat yiy jëfandikoo col gu Nostalgia keppa koy yeb */',
'cologneblue.js' => '/* Bépp JavaScript buñ fi duggal jëfandikukat yiy jëfandikoo col gu Cologne Blue keppa koy yeb */',
'monobook.js'    => '/*Bépp JavaScript buñ fi duggal jëfandikukat yiy jëfandikoo col gu Monobook keppa koy yeb. */',
'myskin.js'      => '/* Bépp JavaScript buñ fi duggal jëfandikukat yiy jëfandikoo col gu MySkin keppa koy yeb */',
'chick.js'       => '/* Bépp JavaScript buñ fi duggal jëfandikukat yiy jëfandikoo col gu Chick keppa koy yeb */',
'simple.js'      => '/* Bépp JavaScript buñ fi duggal jëfandikukat yiy jëfandikoo col gu Simple keppa koy yeb*/',
'modern.js'      => '/* Bépp JavaScript buñ fi duggal jëfandikukat yiy jëfandikoo col gu Modern keppa koy yeb */',

# Metadata
'nodublincore'      => 'Jégginjoxe yu « Dublin Core RDF » dooxuñu ci joxekaay bii.',
'nocreativecommons' => 'Jégginjoxe yu « Creative Commons RDF » doxuñu ci joxekaay bii.',
'notacceptable'     => 'Bii joxekaay bu wiki manuta jébbal ay njoxe cib kem bu sa client mana jàng.',

# Attribution
'anonymous'        => 'Benn walla ay jëfandikukat yu binduwul yu {{SITENAME}}',
'siteuser'         => '$1, Jëfandikukatu {{SITENAME}}',
'lastmodifiedatby' => '$3 moo mujje soppi xët wi ci $1, ci $2.',
'othercontribs'    => 'Mi ngi dàttu ci liggéeyu $1.',
'others'           => 'yeneen',
'siteusers'        => '$1, Jëfandikukat yu {{SITENAME}}',
'creditspage'      => 'Way-sos yu xët wi',
'nocredits'        => 'Amul benn xibaar ci way-sos yi ju jàppandi ngir wii xët.',

# Spam protection
'spamprotectiontitle' => 'Seggukaay lank-spam',
'spam_reverting'      => 'Loppantib sumb mu mujj mu amul lëkkalekaay buy jëme $1',
'spam_blanking'       => 'Setal nañ wecc sumb yi amoon lëkkalekaay buy jëme $1',

# Math options
'mw_math_html' => 'HTML su manee ne, lu ko moy PNG',

# Math errors
'math_failure'          => 'Njuumte ci xayma',
'math_unknown_error'    => 'Njuumte li xamuñ ko',
'math_unknown_function' => 'Solo si xamuñ ko',
'math_lexing_error'     => 'Njuumteg mbindin',
'math_syntax_error'     => 'njuumtey mbindin',

# Browsing diffs
'previousdiff' => '← Coppite yi gën a yàgg',
'nextdiff'     => 'Coppite yi mujj →',

# Media information
'file-info'            => 'Réyaayu file bi : $1, type MIME : $2',
'file-info-size'       => '$1 × $2 pixels, réyaayu file bi : $3, type MIME : $4',
'file-nohires'         => '<small>Amul kem bu ëpp bii bu jàppandi.</small>',
'svg-long-desc'        => 'Dencukaay SVG, kem bu jaadu  $1 × $2 pixel, dayoo dencukaay bi: $3',
'show-big-image'       => 'Ngandalal nataal gii',
'show-big-image-thumb' => '<small>Dayoob wonendi gi : $1 × $2 pixel</small>',

# Special:NewFiles
'ilsubmit' => 'Seet',
'bydate'   => 'ci diir',

# Bad image list
'bad_image_list' => 'Limu  cër yi nekk ci rëdd yi tàmbalee * rekk lees di faale.
Lëkkalekaay bu njëkk bu aw rëdd dafa war a nekk bu ab dencukaay bu baaxul.
Lëkkalekaay yiy toftal, ci wenn rëdd wi, dees leen di jàppee nikiy sette, maanaam xët yi dencukaay bi man a feeñ.',

# Metadata
'metadata'          => 'Jégginjoxe',
'metadata-help'     => 'Bii dencukaay dafa ami xibaar yees ci yokk, xéj-na nataalukaay bu waaraame walla waaraamalekaay bees jëfandikoo moo leen ci yokk. Su fekkee soppees na xar-kanamu dencukaay bi, yenn ci fàramfacce ñeel ko manees nañoo bañ a dëppook li am.',
'metadata-expand'   => 'Wone faramfacce yi',
'metadata-collapse' => 'Nëbb faramfacce yi',
'metadata-fields'   => 'Tool yi ñu jagleel jégginjoxe yu EXIF yi ñu lim ci wii xët di nañu leen wone ci xëtu nataal wi suñu waññee àlliwa bu jegginjoxe yi.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'  => 'Yaatuwaay',
'exif-imagelength' => 'Kawewaay',
'exif-usercomment' => 'Kadduy jëfëndikookat bi',

'exif-componentsconfiguration-0' => 'Amul',

# External editor support
'edit-externally'      => 'Soppi xët wii ak ab tëriin bu biti',
'edit-externally-help' => 'Xoolal [http://www.mediawiki.org/wiki/Manual:External_editors tegtali camp gi] ngir yeneeni xamle',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'Yépp',
'imagelistall'     => 'yépp',
'watchlistall2'    => 'yépp',
'namespacesall'    => 'Yépp',
'monthsall'        => 'Yépp',

# E-mail address confirmation
'confirmemail'         => 'Dëggalal sa m-bataaxal',
'confirmemail_noemail' => 'Jooxewoo ab m-bataaxal bu baax ci say [[Special:Preferences|tànneef]].',

# Trackbacks
'trackbackremove' => '([$1 Dindi])',

# Delete conflict
'confirmrecreate' => "Jëfëndikookat bii [[User:$1|$1]] ([[User talk:$1|Waxtaan]]) moo dindi xët wii, nga xam ne tambaliwoon nga koo defar, ngir ngirte lii :
: ''$2''
Dëgëlël ni bëgg ngaa sakkaat xët wii.",

# Separators for various lists, etc.
'pipe-separator' => '&#32;•&#32;',

# Auto-summaries
'autoredircomment' => 'Jubluwaat fii [[$1]]',
'autosumm-new'     => 'Xët wu bees : $1',

# Watchlist editor
'watchlistedit-numitems'       => 'Sa xëtu toppte am na {{PLURAL:$1|aw xët|$1 ciy xët}}, soo ci gennee xëtu waxtaanukaay yi',
'watchlistedit-noitems'        => 'Sa limu toppte amul benn xët.',
'watchlistedit-normal-title'   => 'Coppiteg xëtu toppte gi',
'watchlistedit-normal-legend'  => 'Dindi ay xët yi limu toppte gi',
'watchlistedit-normal-explain' => 'xët yu sa limu toppte ñooy gisu fii ci suuf.
Ngir dindi am xët (ak xëtu waxtaanukaayam) ci lim gi, kligal ci néeg moomu ci wetam te nga klig ci suuf.
Man nga tamit  [[Special:Watchlist/raw|soppi ko]].',
'watchlistedit-normal-submit'  => 'Dindi xët yi nga tann',
'watchlistedit-normal-done'    => '{{PLURAL:$1|Dindi nañ am xët|$1 ciy xët dindi nañ leen}} ci sa limu toppte :',
'watchlistedit-raw-title'      => 'Coppiteg limu toppte gi',
'watchlistedit-raw-legend'     => 'Coppiteg limu toppte gi',
'watchlistedit-raw-explain'    => 'Limu xët yi nekk ci sa limu toppte moo nekk ci suuf, xëtu waxtaan yi nekku ñu ci(ñoo  ciy duggal seen bopp). Man ngaa soppi lim gi: yokk ci xët yi nga bëgg a topp, am xët ci rëdd mu ne, ak dindi xët yi nga bëggtul a topp. Soo noppee, kligal ci suuf ngir yeesal lim gi. Man nga tamit jëfëndikoo  [[Special:Watchlist/edit|Soppikaay gu mak gi]].',
'watchlistedit-raw-titles'     => 'Xët :',
'watchlistedit-raw-submit'     => 'Yeesal lim gi',
'watchlistedit-raw-done'       => 'Sa limu toppte yeesal nañ ko.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|Yokk nañ ci aw xët|$1 ciy xët lañ fi yokk}} :',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|Dindi nañ am xët|$1 ciy xët dindi nañ leen}} :',

# Watchlist editing tools
'watchlisttools-view' => 'Limu toppte',
'watchlisttools-edit' => 'Xool te soppi limu toppte gi',
'watchlisttools-raw'  => 'Soppi lim gi',

# Special:SpecialPages
'specialpages' => 'Xëti jagleel',

);
